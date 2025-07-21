<?php
if( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ret_billing_model extends CI_Model
{
	function __construct()
    {
        parent::__construct();
    }
    // General Functions
    public function insertData($data,$table)
    {
    	$insert_flag = 0;
		$insert_flag = $this->db->insert($table, $data);
		return ($insert_flag == 1 ? $this->db->insert_id(): 0);
	}
	public function insertBatchData($data,$table)
    {
    	$insert_flag = 0;
		$insert_flag = $this->db->insert_batch($table, $data);
		if ($this->db->affected_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	public function updateData($data, $id_field, $id_value, $table)
    {    
	    $edit_flag = 0;
	    $this->db->where($id_field, $id_value);
		$edit_flag = $this->db->update($table,$data);
		return ($edit_flag==1?$id_value:0);
	}	 
	public function deleteData($id_field,$id_value,$table)
    {
        $this->db->where($id_field, $id_value);
        $status= $this->db->delete($table); 
		return $status;
	}
	
	function get_tag_status($tag_id)
	{
	    $sql=$this->db->query("SELECT * from ret_taging where tag_id=".$tag_id."");
	    return $sql->row_array();
	}
	
	function get_esti_status($est_item_id)
	{
	    $sql=$this->db->query("SELECT * from ret_estimation_items where est_item_id=".$est_item_id."");
	    return $sql->row_array();
	}
	
	function get_old_esti_status($est_old_itm_id)
	{
	    $sql=$this->db->query("SELECT * from ret_estimation_old_metal_sale_details where old_metal_sale_id=".$est_old_itm_id."");
	    return $sql->row_array();
	}
	
	function get_branch($id_branch)
    {
        $sql=$this->db->query("SELECT * from branch where id_branch=".$id_branch."");
        return $sql->row_array();
    }
	/*function generateRefNo($id_branch,$field,$metal_type)
	{
		$branch_code 	='';
		$ref_no 		='';
		$ref_code 		='';
		$fin_year 		= $this->get_FinancialYear();
		
		if($id_branch!='')
		{
			$branch=$this->get_branch($id_branch);
			$branch_code=$branch['short_name'];
		}
		
		$metal_details=$this->get_metal_details($metal_type);
		
		$last_no=$this->get_bill_no($id_branch,$field,$metal_type);
		if($last_no!=null && $last_no!='')
		{
			$code=explode("/",$last_no);
			$bill_fin_year=$code[1];
			$last_no =($fin_year['fin_year_code']>$bill_fin_year ? 0 :$code[4]);
			$bill_number = (int) $last_no;
		  	$bill_number++;
		}
		else{
			$bill_number=1;
		}
		if($field=='sales_ref_no')
		{
			$ref_code='SA';
		}
		else if($field=='pur_ref_no'){
			$ref_code='PU';
		}
		else if($field=='order_adv_ref_no')
		{	
			$ref_code='OR';
		}else if($field=='s_ret_refno')
		{
			$ref_code='SR';
		}
		
		$ref_no=($branch_code!='' ? $branch_code.'/' :'').$fin_year['fin_year_code'].'/'.($ref_code!='' ? $ref_code.'/':'').$metal_details['metal_code'].'/'.$bill_number;
		return $ref_no;
	}*/
	/*function get_bill_no($id_branch,$field,$metal_type)
	{
		$ref_no='';
        $sql=$this->db->query("SELECT ".$field." From ret_billing where ".$field." is not null 
        ".($id_branch!='' ? " and id_branch=".$id_branch."" :'')."
        ".($metal_type!='' ? " and metal_type=".$metal_type."" :'')."
        order by bill_id DESC LIMIT 1");
		//print_r($this->db->last_query());exit;
		$max_no=$sql->row_array();
		if(sizeof($max_no)>0)
		{
			if($max_no[$field]!='' && $max_no[$field]!=null)
			{
				$ref_no=$max_no[$field];
			}
		}
		
		return $ref_no;
	}*/
	
	
	function generateRefNo($id_branch,$field,$metal_type,$ret_sales_type)
	{
		$ref_no 		='';
		$fin_year 		= $this->get_FinancialYear();
	
		//$metal_details=$this->get_metal_details($metal_type);
		
		$last_no=$this->get_bill_no($id_branch,$field,$metal_type,$ret_sales_type);
		
		if($last_no!=NULL)
		{
			$LastBillNo = (int) $last_no;
			$LastBillNo++;
			$code_number = str_pad($LastBillNo, 5, '0', STR_PAD_LEFT);			
			$ref_no= $code_number;
		}
		else
		{
			$code_number = str_pad('1', 5, '0', STR_PAD_LEFT);
			$ref_no= $code_number;
		}
	
	return $ref_no;
	
	}
	function get_bill_no($id_branch,$field,$metal_type,$ret_sales_type)
	{
		$fin_year = $this->get_FinancialYear();
		$sql = "SELECT ".$field." as lastBill_no FROM ret_billing 
		where fin_year_code=".$fin_year['fin_year_code']." and  ".$field." is not null
		".($ret_sales_type!='' ? " and ret_sales_type=".$ret_sales_type."" :'')."
		".($id_branch!='' && $id_branch>0 ? " and id_branch=".$id_branch."" :'')."
		".($metal_type!='' ? " and metal_type=".$metal_type."" :'')."
		ORDER BY bill_id DESC LIMIT 1";
		//print_r($sql);exit;
		return $this->db->query($sql)->row()->lastBill_no;	
	}
	
	
    function get_branch_details($id_branch)
	{
	    $data=array();
		$sql=$this->db->query("SELECT * From ret_bill_gift_voucher_settings  where status=1 and is_default=1".($id_branch!='' && $id_branch>0 ? " and id_branch=".$id_branch."" :'')."");
		$data= $sql->row_array();
		if($data['validity_days']!='')
		{
		    $data['validate_date'] = date("d-m-Y", strtotime($data['validity_days'].'days'));
		}
		
		return $data;
	}
	
	function CheckProductAvailability($id)
	{
	    $sql=$this->db->query("select s.id_gift_voucher,s.id_product,s.issue,s.utilize from ret_gift_issue_redeem_prod s where id_gift_voucher=".$id);
	    return $sql->result_array();
	}
	function get_FinancialYear()
	{
		$sql=$this->db->query("SELECT fin_year_code From ret_financial_year where fin_status=1");
		return $sql->row_array();
	}
	
	function get_ret_settings($settings)
	{
		$data=$this->db->query("SELECT value FROM ret_settings where name='".$settings."'"); 
		return $data->row()->value;
	}
	
    function get_gift_voucher_settings()
	{
		$data=$this->db->query("SELECT metal,gift_type,sale_value,credit_value,validity_days,utilize_for FROM ret_bill_gift_voucher_settings where status=1 and is_default=1"); 
		return $data->row_array();
	}
	
	function get_branchwise_rate($id_branch)
	{
		$is_branchwise_rate=$this->session->userdata('is_branchwise_rate');
		if($id_branch!='' && $id_branch!=0 && $is_branchwise_rate==1)
		{
		    $sql="SELECT  b.name as name,m.mjdmagoldrate_22ct,m.goldrate_22ct,m.goldrate_24ct,m.silverrate_1gm,m.silverrate_1kg,m.mjdmasilverrate_1gm,platinum_1g,
		    Date_format(m.updatetime,'%d-%m%-%Y %h:%i %p')as updatetime,m.goldrate_18ct  
		    FROM metal_rates m 
		    LEFT JOIN branch_rate br on br.id_metalrate=m.id_metalrates
		    left join branch b on b.id_branch=br.id_branch
		    ".($id_branch!='' ?" WHERE br.id_branch=".$id_branch."" :'')." ORDER by br.id_metalrate desc LIMIT 1";
		}
		else
		{
		    $sql="SELECT  m.mjdmagoldrate_22ct,m.goldrate_22ct,m.goldrate_24ct,m.silverrate_1gm,m.silverrate_1kg,m.mjdmasilverrate_1gm,platinum_1g,
		    Date_format(m.updatetime,'%d-%m%-%Y %h:%i %p')as updatetime,m.goldrate_18ct   
		    FROM metal_rates m 
		    ORDER by m.id_metalrates desc LIMIT 1";
		}
		return $this->db->query($sql)->row_array();
	}
	
	function get_customer($id)
    {
		$customers=$this->db->query("Select
		   c.id_customer,c.firstname,c.lastname,c.date_of_birth,c.date_of_wed,
		   a.address1,a.address2,a.address3,ct.name as city,a.pincode,s.name as state,cy.name as country,
		   c.phone,c.mobile,c.email,c.nominee_name,c.nominee_relationship,c.nominee_mobile, 
		   c.cus_img,c.pan,c.pan_proof,c.voterid,c.voterid_proof,c.rationcard,c.rationcard_proof,a.id_country,a.id_city,a.id_state,c.id_employee,
		   (Select count(id_scheme_account) as accounts from scheme_account where id_customer=1 and active=1 and is_closed=0) as accounts, 
   	c.comments,c.username,c.passwd,c.is_new,c.active,c.`date_add`,c.`date_upd`
			From
			  customer c
			left join address a on(c.id_customer=a.id_customer)
			left join country cy on (a.id_country=cy.id_country)
			left join state s on (a.id_state=s.id_state)
			left join city ct on (a.id_city=ct.id_city)
			where c.active=1 and c.id_customer=".$id);
			//print_r($this->db->last_query());exit;
		return $customers->row_array();
	}
    function ajax_getBillingList($data)
    {
        $profile_settings=$this->get_profile_settings($this->session->userdata('profile'));
        if($_POST['dt_range'] != '')
        {
            $dateRange = explode('-',$_POST['dt_range']);
            $from = str_replace('/','-',$dateRange[0]);
            $to = str_replace('/','-',$dateRange[1]);  
            $d1 = date_create($from);
            $d2 = date_create($to);
            $FromDt = date_format($d1,"Y-m-d");
            $ToDt = date_format($d2,"Y-m-d");
        }
        $sql = $this->db->query("SELECT bill.bill_id,bill.bill_no,
        if(bill_type = 1,'Sales',if(bill_type = 2,'Sales&Purchase',if(bill_type = 3,'Sales&Return',if(bill_type = 4,'Purchase',if(bill_type = 5,'Order Advance',if(bill_type = 6,'Advance',if(bill_type = 7,'Sales Return',if(bill_type=8,'Credit Collection',if(bill_type=9,'Order Delivery',if(bill.bill_type=10,'Chit Pre Close',if(bill.bill_type=11,'Repair Order Delivery',if(bill.bill_type=12,'Supplier Sales Bill',if(bill.bill_type=13,'Sales Trasnfer','Sales Return Transfer'))))))))))))) as bill_type,        
        if(bill_status = 1,'Success',if(bill_status = 2,'Cancelled','')) as bill_status,
        date_format(bill_date, '%d-%m-%Y %H:%i') as bill_date,
        if(bill.billing_for=1 || bill.billing_for=2 , concat(cus.firstname,' ',if(cus.lastname!=NULL,cus.lastname,'')),k.firstname ) as customer,
        if(bill.billing_for=1 || bill.billing_for=2 , cus.mobile,k.contactno1 ) as mobile,
        tot_amt_received as tot_bill_amt,print_taken,if(date(d.entry_date)=date(bill.bill_date),'1','0') as allow_cancel,b.name as branch_name,
        k.firstname as karigar_name,bill.bill_type as billing_type,k.contactno1 as karigar_mobile,
        if(emp_bill.emp_name!='',emp_bill.emp_name,if(emp_bill_old.emp_name!='',emp_bill_old.emp_name,e.firstname)) as emp_name,bill.billing_for
        FROM ret_billing as bill
        LEFT JOIN customer as cus ON cus.id_customer = bill.bill_cus_id
        LEFT JOIN ret_karigar k on k.id_karigar=bill.bill_cus_id
        LEFT JOIN ret_day_closing d on d.id_branch=bill.id_branch
        LEFT JOIN branch b on b.id_branch=bill.id_branch
        LEFT JOIN employee e on e.id_employee=bill.created_by
        LEFT JOIN (SELECT r.bill_id,e.est_item_id,est.estimation_id, GROUP_CONCAT(DISTINCT emp.firstname,'.',emp.lastname) as emp_name
		FROM ret_bill_details r 
		LEFT JOIN ret_estimation_items as e ON e.est_item_id = r.esti_item_id
		LEFT JOIN ret_estimation as est ON est.estimation_id = e.esti_id
		LEFT JOIN employee as emp ON emp.id_employee = est.created_by  group by r.bill_id) as emp_bill on emp_bill.bill_id=bill.bill_id
		LEFT JOIN(SELECT est.created_by,bill_old.bill_id,GROUP_CONCAT(DISTINCT emp.firstname,'.',emp.lastname) as emp_name
		FROM ret_bill_old_metal_sale_details  bill_old
        LEFT JOIN ret_estimation_old_metal_sale_details as est_old on est_old.old_metal_sale_id=bill_old.old_metal_sale_id
		LEFT JOIN ret_estimation as est on est.estimation_id=est_old.est_id
		LEFT JOIN employee as emp ON emp.id_employee = est.created_by  group by bill_old.bill_id) as emp_bill_old on emp_bill_old.bill_id=bill.bill_id
		
        where  ".($data['dt_range'] != '' ? 'date(bill.bill_date) BETWEEN "'.$FromDt.'" AND "'.$ToDt.'"' : '')."
        
        ".($data['bill_no']!='' ? " and bill.bill_no=".$data['bill_no']."" :'')." 
        
        ".($data['id_branch']!=0 && $data['id_branch']!='' ? " and bill.id_branch=".$data['id_branch']."" :'')." 
        
       ".($profile_settings['allow_other_bill_types']==1 ? " and (bill.ret_sales_type=1 OR bill.ret_sales_type=2)" :"and bill.ret_sales_type=1")."
        
         ORDER BY bill.bill_id desc");
        //echo $this->db->last_query();exit;
        return $sql->result_array();
    }
	function ajax_getApprovalBillingList($data){
	    if($_POST['dt_range'] != '')
        {
            $dateRange = explode('-',$_POST['dt_range']);
            $from = str_replace('/','-',$dateRange[0]);
            $to = str_replace('/','-',$dateRange[1]);  
            $d1 = date_create($from);
            $d2 = date_create($to);
            $FromDt = date_format($d1,"Y-m-d");
            $ToDt = date_format($d2,"Y-m-d");
        }
        $sql = $this->db->query("SELECT r.id_issue_receipt, r.bill_no, date_format(r.bill_date, '%d-%m-%Y') as billdate,
                                concat(ifnull(cus.firstname, ''), '-', ifnull(cus.mobile,'')) as customer,
                                tag.piece ,tag.tag_code, tag.gross_wt, tag.less_wt, tag.net_wt,
                                concat(ifnull(sup.firstname,''), '-', ifnull(sup.contactno1, '')) as supplier, 
                                date_format(po.po_date, '%d-%m-%Y') as purchaseon, po.po_ref_no, br.name as branchname, emp.lastname as emp_name, 
                                ifnull(cusor.pur_no, '') as order_no, ifnull(date_format(cusor.order_date, '%d-%m-%Y'), '') as orderedon, 
                                ifnull(m.order_status, '') as order_status_msg, ifnull(cusor.order_status,'') as orderstatus, tag.tag_id ,lot.gold_smith as id_karigar,
                                date_format(b.bill_date,'%d-%m-%Y') as sale_billdate,IFNULL(b.bill_no,'') as sale_billno,IFNULL(b.bill_id,'') as sale_billid,
                                tag.tag_status
                                FROM ret_adv_receipt_tags t 
                                LEFT JOIN ret_issue_receipt r ON r.id_issue_receipt = t.adv_rcpt_issue_receipt_id
                                LEFT JOIN ret_taging as tag ON tag.tag_id = t.adv_rcpt_tagid
                                LEFT JOIN ret_lot_inwards as lot ON lot.lot_no = tag.tag_lot_id 
                                LEFT JOIN ret_purchase_order as po ON po.po_id = lot.po_id 
                                LEFT JOIN customerorderdetails as ordet ON ordet.approval_tagid = tag.tag_id 
                                LEFT JOIN customerorder as cusor ON cusor.id_customerorder = ordet.id_customerorder 
                                LEFT JOIN order_status_message m ON m.id_order_msg=cusor.order_status
                                LEFT JOIN customer as cus ON cus.id_customer = r.id_customer
                                LEFT JOIN ret_karigar as sup ON sup.id_karigar = lot.gold_smith 
                                LEFT JOIN branch as br ON br.id_branch = r.id_branch
                                LEFT JOIN employee as emp ON emp.id_employee =  r.created_by
                                LEFT JOIN ret_bill_details dt ON dt.tag_id = tag.tag_id
                                LEFT JOIN ret_billing b ON b.bill_id = dt.bill_id AND b.bill_status = 1
                                WHERE tag.tag_type = 1 and r.bill_status = 1 
                                ".($data['order_status']==0 ? " AND (cusor.order_status = ".$data['order_status']." OR cusor.order_status IS NULL)" :" AND cusor.order_status = ".$data['order_status']."")." 
                                ".($data['order_status']==3 ? " AND tag.tag_status = 11" :" AND (tag.tag_status = 11 OR tag.tag_status = 0 OR tag.tag_status = 1)")."
                                ".($data['dt_range'] != '' ? 'AND date(r.bill_date) BETWEEN "'.$FromDt.'" AND "'.$ToDt.'"' : '')." 
                                ".($data['bill_no']!='' ? " and r.bill_no=".$data['bill_no']."" :'')." 
                                ".($data['id_branch']!=0 && $data['id_branch']!='' ? " and r.id_branch=".$data['id_branch']."" :'')." 
                                ORDER BY r.id_issue_receipt desc");
        //echo $this->db->last_query();exit;
        return $sql->result_array();
        
	}
	
	function get_entry_records($est_id)
	{
		$sql = $this->db->query("SELECT estimation_id, 
				concat(firstname, '-', mobile) as cus_name,  	
				date_format(estimation_datetime, '%d-%m-%Y %H:%i:%s') as estimation_datetime, 
				cus_id, created_by, date_format(created_time, '%d-%m-%Y %H:%i:%s') as created_time,
				has_converted_order, discount, gift_voucher_amt, total_cost, est.id_branch 
				FROM ret_estimation as est 
				LEFT JOIN customer as cus ON cus.id_customer = est.cus_id 
				WHERE est.estimation_id ='".$est_id."'");
		return $sql->result_array()[0];
	}
	
	function getBillingMetalrate($id_branch,$date)
	{
        $date_add=date('Y-m-d',strtotime($date));
		$is_branchwise_rate=$this->session->userdata('is_branchwise_rate');
		if($id_branch!='' && $id_branch!=0 && $is_branchwise_rate==1)
		{
		    $sql="SELECT  b.name as name,m.mjdmagoldrate_22ct,m.goldrate_22ct,m.goldrate_24ct,m.silverrate_1gm,m.silverrate_1kg,m.mjdmasilverrate_1gm,platinum_1g,
		    Date_format(m.updatetime,'%d-%m%-%Y %h:%i %p')as updatetime   
		    FROM metal_rates m 
		    LEFT JOIN branch_rate br on br.id_metalrate=m.id_metalrates
		    left join branch b on b.id_branch=br.id_branch
		   where date(m.updatetime)=".$date_add." ".($id_branch!='' ?" and br.id_branch=".$id_branch."" :'')." ORDER by br.id_metalrate desc LIMIT 1";
		}
		else
		{
		    $sql="SELECT  m.mjdmagoldrate_22ct,m.goldrate_22ct,m.goldrate_24ct,m.silverrate_1gm,m.silverrate_1kg,m.mjdmasilverrate_1gm,platinum_1g,
		    Date_format(m.updatetime,'%d-%m%-%Y %h:%i %p')as updatetime   
		    FROM metal_rates m 
		    where date(m.updatetime)='".$date_add."'
		    ORDER by m.id_metalrates desc LIMIT 1";
		}
		return $this->db->query($sql)->row_array();
	}
    function getBillingDetails($bill_id)
	{
		$items_query = $this->db->query("SELECT b.bill_type,b.bill_cus_id,IFNULL(b.pan_no,'') as pan_no,b.bill_no,date_format(b.bill_date,'%d-%m-%Y') as bill_date, date_format(b.created_time,'%h:%i %p') as bill_time,
		b.bill_id,concat(c.mobile,'-',c.firstname) as cus_name,b.id_branch,b.tot_bill_amount,date_format(b.credit_due_date,'%d-%m-%Y') as credit_due_date,b.is_credit,b.ref_bill_id as ref_bill_id,b.print_taken,b.remark,IFNULL(b.tot_discount,0) as tot_discount,a.id_state as cus_state,c.firstname as customer_name,c.mobile,br.name as branch_name,b.tot_amt_received,round_off_amt,b.pur_ref_no,e.id_employee,
		br.short_name as branch_code,d.name as delivery_location,IFNULL(v.village_name,'') as village_name,IFNULL(c.gst_number,'') as gst_number,a.address1,a.address2,a.pincode,c.cus_type,b.pan_no as pan_number,ct.name as city,
		IFNULL(g.amount,0) as gift_issue_amount,IFNULL(g.weight,0) as gift_issue_weight,date_format(g.valid_to,'%d-%m-%Y') as valid_to,g.code,IFNULL(b.handling_charges,0) as handling_charges,gift.utilize_for,IFNULL(gift.note,'') as note,b.is_trail,
		IFNULL(b.goldrate_22ct,0) as goldrate_22ct,IFNULL(b.silverrate_1gm,0) as silverrate_1gm,b.billing_for,IFNULL(b.id_cmp_emp,'') as id_cmp_emp,concat(IFNULL(cmp.firstname,''),'-',cmp.mobile) as cmp_user_name,IFNULL(b.tcs_tax_amt,0) as tcs_tax_amt,IFNULL(b.tcs_tax_per,0) as tcs_tax_per,b.fin_year_code,
		b.bill_status,IFNULL(b.credit_disc_amt,0) as credit_disc_amt,e.firstname as emp_name, b.metal_type,s.name as cus_state,cy.name as cus_country,IFNULL(b.advance_deposit,0) as advance_deposit,b.make_as_advance,
		concat(k.firstname,'',IFNULL(concat('-','',k.contactno1),'')) as karigar_name,IFNULL(k.address1,'') as karigar_address1,IFNULL(k.address2,'') as karigar_address2,
		b.delivered_at,IFNULL(del_add.address1,'') as del_add_address1,IFNULL(del_add.address2,'') as del_add_address2,
		IFNULL(del_add.address3,'') as del_add_address3,IFNULL(cyt.name,'') as del_country_name,
		IFNULL(st.name,'') as del_state_name,IFNULL(cty.name,'') as del_city_name,IFNULL(del_add.pincode,'') as del_pincode,IFNULL(k.gst_number,'') as karigar_gst_number,
		IFNULL(b.sales_ref_no,'') as sales_ref_no, IFNULL(b.approval_ref_no,'') as approval_ref_no, IFNULL(b.pur_ref_no,'') as pur_ref_no,IFNULL(b.s_ret_refno,'') as s_ret_refno,mt.metal_code,IFNULL(b.credit_coll_refno,'') as credit_coll_refno,IFNULL(b.order_adv_ref_no,'') as order_adv_ref_no,
		IFNULL(b.chit_preclose_refno,'') as chit_preclose_refno,s.state_code,st.state_code,b.goldrate_18ct,
		IFNULL(b.credit_due_amt,0) as credit_due_amt,IFNULL(b.credit_ret_amt,0) as credit_ret_amt,b.to_branch,b.from_branch
		
		FROM ret_billing b
		LEFT JOIN customer c ON c.id_customer=b.bill_cus_id
		LEFT JOIN ret_karigar k on k.id_karigar=b.bill_cus_id
		LEFT JOIN address a on a.id_customer=c.id_customer
		LEFT JOIN branch br on br.id_branch=b.id_branch
		LEFT join employee e on e.id_employee=b.created_by
		LEFT JOIN ret_financial_year f on f.fin_status = 1
		LEFT JOIN ret_sale_delivery d on d.id_sale_delivery=b.id_delivery
		LEFT JOIN village v on v.id_village=c.id_village
		LEFT JOIN gift_card g on g.bill_id=b.bill_id
		LEFT JOIN ret_bill_gift_voucher_settings gift on gift.id_set_gift_voucher=g.id_set_gift_voucher
		LEFT JOIN ret_customer_company_users cmp on cmp.id_cmp_emp=b.id_cmp_emp
		LEFT JOIN metal mt on mt.id_metal=b.metal_type
		LEFT JOIN city ct on a.id_city=ct.id_city
		LEFT JOIN state s on s.id_state=a.id_state
		left join country cy on (a.id_country=cy.id_country)
		
		LEFT JOIN ret_bill_delivery del_add on del_add.bill_id=b.bill_id
		LEFT JOIN country cyt on cyt.id_country=del_add.id_country
		LEFT JOIN state st on st.id_state=del_add.id_state
		LEFT JOIN city cty on cty.id_city=del_add.id_city
		
		
		where b.bill_id=".$bill_id."");
		//echo $this->db->last_query();exit;
		$data=$items_query->row_array();
		if($data['ref_bill_id']!='')
		{
            
			$data['ref_bill_no']=$this->getBill_details($data['ref_bill_id']);
			if($data['bill_type']==8)
			{
			    $data['due_amount']=$this->get_due_bill($data['ref_bill_id']);
			    $data['tot_adv_received']=$this->get_paid_bill($data['ref_bill_id']);
			    $data['tot_paid_amt']=$this->get_credit_pay_amount($data['ref_bill_id']);
			}
		}
		
		$data['transfer_details']=$this->getCompanyDetails($data['to_branch']);
		
		$data['adv_adj_amt']=$this->get_advance_adjusted($bill_id);
		//print_r($data);exit;
		return $data;
	}
	
    function get_order_adj_details($bill_id)
	{
	    $sql = $this->db->query("SELECT id_customerorder FROM ret_billing_advance WHERE adjusted_bill_id = ".$bill_id." GROUP by id_customerorder");
	    return $sql->result_array();
	}
	
	function get_due_bill($bill_id)
	{
		$items_query = $this->db->query("SELECT (b.tot_bill_amount-b.tot_amt_received) as due_amount
		FROM ret_billing b
		where b.bill_id=".$bill_id."");
		return $items_query->row('due_amount');
	}
	
	function get_paid_bill($bill_id)
	{
		$items_query = $this->db->query("SELECT b.tot_amt_received
		FROM ret_billing b
		where b.bill_id=".$bill_id."");
		return $items_query->row('tot_amt_received');
	}
	function getBill_details($bill_id)
	{
		$items_query = $this->db->query("SELECT b.bill_no
		FROM ret_billing b
		where b.bill_id=".$bill_id."");
		return $items_query->row('bill_no');
	}
	
	function getPaymentDetails($bill_id)
	{
		$pay_details = array("pay_details" => array());
		$items_query = $this->db->query("SELECT p.id_billing_payment,p.type,p.bill_id,p.payment_for,p.payment_amount,p.card_no,p.cvv,p.payment_mode,IFNULL(p.payment_ref_number,'') as payment_ref_number,
		if(p.NB_type=1,'RTGS',if(p.NB_type=2,'IMPS',if(p.NB_type=3,'UPI',''))) as transfer_type
		FROM ret_billing_payment p
		where p.bill_id=".$bill_id."");
		$data=$items_query->result_array();
		foreach ($data as $items ) {
				$pay_details['pay_details'][]=array(
						'id_billing_payment'    =>$items['id_billing_payment'],
						'type'                  =>$items['type'],
						'bill_id'               =>$items['bill_id'],
						'payment_for'           =>$items['payment_for'],
						'payment_amount'        =>$items['payment_amount'],
						'cvv'                   =>$items['cvv'],
						'card_no'               =>$items['card_no'],
						'payment_mode'          =>$items['payment_mode'],
						'payment_ref_number'    =>$items['payment_ref_number'],
						'transfer_type'         =>$items['transfer_type'],
				);		
			}
		return $pay_details;
	}
	function getOtherEstimateItemsDetails($bill_id,$bill_type)
	{
	    $item_details=array();
	    
	    $bill_det=$this->getBillingDetails($bill_id);
		$return_data = array("item_details" => array(), "old_matel_details" => array(), "stone_details" => array(), "other_material_details" => array(), "voucher_details" => array(), "chit_details" => array(),"return_details"=>array(),'advance_details'=>array(),"order_adj"=>array(),"order_details"=>array());
		if($bill_type!=5 && $bill_type!=13 && $bill_type!=14)
		{
			$items_query = $this->db->query("SELECT d.bill_det_id,IFNULL(d.esti_item_id,'') as  esti_item_id,est_itms.esti_id,est_itms.item_type,est_itms.purchase_status,
			ifnull(d.product_id, '') as product_id, ifnull(est_itms.tag_id, '') as tag_id,
			ifnull(d.design_id, '') as design_id, ifnull(c.hsn_code,'') as hsn_code,
			d.purity as purid,d.size, CONCAT(s.value,' ',s.name) as size_name, ifnull(d.uom,'') as uom,d.piece,
			ifnull(d.less_wt,'') as less_wt, d.net_wt, d.gross_wt,
			d.calculation_based_on, d.wastage_percent, d.mc_value, d.mc_type,
			d.item_cost, ifnull(pro.product_short_code, '-') as product_short_code,
			ifnull(pro.product_name, '-') as product_name, est_itms.is_partial,est_itms.discount,
			ifnull(des.design_code, '-') as design_code,
			ifnull(des.design_name, '') as design_name, pur.purity as purname,
			pro.tgrp_id as tax_group_id , tgrp_name, ifnull(c.id_metal,'') as metal_type,
			ifnull(des.fixed_rate,0) as fixed_rate,d.is_non_tag,d.id_lot_inward_detail
			
			,d.bill_discount,d.item_total_tax,tax.tax_percentage,tax.tgi_calculation,d.total_cgst,d.total_igst,d.total_sgst,ifnull(est_itms.id_orderdetails,'') as id_orderdetails,
			
			cus.order_no, '' as code_charge, e.firstname as esti_emp_name, e.id_employee AS esti_emp_id,IFNULL(d.mc_discount,0) as mc_discount,
			
			IFNULL(d.wastage_discount,0) as wastage_discount,IFNULL(d.rate_per_grm,0) as rate_per_grm,IFNULL(d.item_blc_discount,0) as item_blc_discount
			From ret_billing b
			Left JOIN ret_bill_details d on d.bill_id=b.bill_id 
			LEFT JOIN ret_taging as tag ON tag.tag_id = d.tag_id 
			LEFT JOIN customerorderdetails ord on ord.id_orderdetails=d.id_orderdetails
			LEFT JOIN customerorder cus on cus.id_customerorder=ord.id_customerorder
			LEFT JOIN ret_product_master as pro ON pro.pro_id = d.product_id
			LEFT JOIN ret_category c on c.id_ret_category = pro.cat_id
			LEFT JOIN metal mt on mt.id_metal=c.id_metal
			LEFT JOIN ret_design_master as des ON des.design_no = d.design_id
			LEFT JOIN ret_estimation_items est_itms on est_itms.est_item_id=d.esti_item_id
			LEFT JOIN ret_estimation esti ON esti.estimation_id = est_itms.esti_id
            LEFT JOIN employee e on e.id_employee = esti.created_by
			LEFT JOIN ret_purity as pur ON pur.id_purity = est_itms.purity
			LEFT JOIN ret_taxgroupmaster as txgrp ON txgrp.tgrp_id = pro.tgrp_id
			LEFT JOIN ret_size s   ON s.id_size = d.size
			LEFT JOIN (select i.tgi_taxcode,i.tgi_tgrpcode,
						m.tax_percentage as tax_percentage,
						i.tgi_calculation as tgi_calculation
						FROM ret_taxgroupitems i
						LEFT JOIN ret_taxmaster m on m.tax_id=i.tgi_taxcode) as tax on tax.tgi_tgrpcode=pro.tgrp_id
			WHERE d.bill_id=".$bill_id."  GROUP by d.bill_det_id HAVING bill_det_id!=''");
			//echo $this->db->last_query();exit;
			$item_details= $items_query->result_array();
		}
		else
		{
		    $items_query = $this->db->query("SELECT IFNULL(SUM(d.piece),0) as piece,
			IFNULL(SUM(d.net_wt),0) as net_wt,IFNULL(SUM(d.gross_wt),0) as gross_wt,
			IFNULL(SUM(d.item_cost),0) as item_cost,IFNULL(SUM(d.item_total_tax),0) as item_total_tax,IFNULL(SUM(d.total_cgst),0) as total_cgst,
			IFNULL(SUM(d.total_igst),0) as total_igst,IFNULL(SUM(d.total_sgst),0) as total_sgst,c.name as category_name,ifnull(c.hsn_code,'') as hsn_code,
			tax.tax_percentage,d.rate_per_grm
			
			From ret_billing b
			Left JOIN ret_bill_details d on d.bill_id=b.bill_id 
			LEFT JOIN ret_taging as tag ON tag.tag_id = d.tag_id 
			LEFT JOIN customerorderdetails ord on ord.id_orderdetails=d.id_orderdetails
			LEFT JOIN customerorder cus on cus.id_customerorder=ord.id_customerorder
			LEFT JOIN ret_product_master as pro ON pro.pro_id = d.product_id
			LEFT JOIN ret_category c on c.id_ret_category = pro.cat_id
			LEFT JOIN metal mt on mt.id_metal=c.id_metal
			LEFT JOIN ret_design_master as des ON des.design_no = d.design_id
			LEFT JOIN ret_purity as pur ON pur.id_purity = tag.purity
			LEFT JOIN ret_taxgroupmaster as txgrp ON txgrp.tgrp_id = pro.tgrp_id
			LEFT JOIN ret_size s   ON s.id_size = d.size
			LEFT JOIN (select i.tgi_taxcode,i.tgi_tgrpcode,
						m.tax_percentage as tax_percentage,
						i.tgi_calculation as tgi_calculation
						FROM ret_taxgroupitems i
						LEFT JOIN ret_taxmaster m on m.tax_id=i.tgi_taxcode) as tax on tax.tgi_tgrpcode=pro.tgrp_id
			WHERE d.bill_id=".$bill_id."  GROUP By pro.cat_id");
			$return_data['sales_trasnfer_details']= $items_query->result_array();
		}
		if(sizeof($item_details)>0)
		{
			foreach($item_details as $item)
			{
				$return_data['item_details'][]=array(
							'calculation_based_on'=>$item['calculation_based_on'],
							'design_code'		  	=>$item['design_code'],
							'design_id'				=>$item['design_id'],
							'design_name'			=>$item['design_name'],
							'discount'				=>$item['discount'],
							'est_item_id'			=>$item['esti_item_id'],
							'esti_id'				=>$item['esti_id'],
							'fixed_rate'			=>$item['fixed_rate'],
							'gross_wt'				=>$item['gross_wt'],
							'hsn_code'				=>$item['hsn_code'],
							'is_partial'			=>$item['is_partial'],
							'is_non_tag'			=>$item['is_non_tag'],
							'item_cost'				=>$item['item_cost'],
							'item_type'				=>$item['item_type'],
							'less_wt'				=>$item['less_wt'],
							'mc_type'				=>$item['mc_type'],
							'mc_value'				=>$item['mc_value'],
							'metal_type'			=>$item['metal_type'],
							'net_wt'				=>$item['net_wt'],
							'product_id'			=>$item['product_id'],
							'product_name'			=>$item['product_name'],
							'product_short_code'	=>$item['product_short_code'],
							'purchase_status'		=>$item['purchase_status'],
							'purid'					=>$item['purid'],
							'purname'				=>$item['purname'],
							'piece'					=>$item['piece'],
							'size'					=>$item['size'],
							'size_name'				=>$item['size_name'],
							'tag_id'				=>$item['tag_id'],
							'tax_group_id'			=>$item['tax_group_id'],
							'tgrp_name'				=>$item['tgrp_name'],
							'tax_percentage'		=>$item['tax_percentage'],
							'tgi_calculation'		=>$item['tgi_calculation'],
							'uom'					=>$item['uom'],
							'wastage_percent'		=>$item['wastage_percent'],
							'item_total_tax'		=>$item['item_total_tax'],
							'bill_discount'			=>$item['bill_discount'],
							'total_igst'			=>$item['total_igst'],
							'total_cgst'			=>$item['total_cgst'],
							'total_sgst'			=>$item['total_sgst'],
							'code_charge'           =>$item['code_charge'],
							'wastage_discount'		=>$item['wastage_discount'],
							'mc_discount'			=>$item['mc_discount'],
							'rate_per_grm'			=>$item['rate_per_grm'],
							'item_blc_discount'			=>$item['item_blc_discount'],
							
                           // 'stone_details'         =>$this->get_tag_stone_details($item['tag_id']),
						    'esti_emp_name'			=>$item['esti_emp_name'],
							'esti_emp_id'			=>$item['esti_emp_id'],
                            'stone_details'         =>($item['esti_item_id']!='' ? $this->get_stone_details($item['esti_item_id']):$this->get_tag_stone_details($item['tag_id'])),
							'order_no'				=>isset($item['order_no'])?$item['order_no']:NULL,
							'charges'				=>($item['esti_item_id']!='' ? $this->get_other_estcharges($item['esti_item_id']) :'')
						);
			}
		}
		$old_metal_query=$this->db->query("SELECT s.old_metal_sale_id,s.bill_id,s.purpose,s.metal_type,s.item_type,s.gross_wt,s.stone_wt,s.dust_wt,s.stone_wt,s.wastage_percent,
		s.wast_wt,s.rate_per_grm as rate_per_gram,s.rate as amount,s.bill_id,s.bill_discount,s.est_id,s.net_wt, e.firstname as esti_emp_name, e.id_employee AS esti_emp_id,
		t.metal_type as old_metal_type
		FROM ret_bill_old_metal_sale_details s 
		LEFT JOIN ret_estimation_old_metal_sale_details  AS est_old ON est_old.old_metal_sale_id = s.esti_old_metal_sale_id
		LEFT JOIN ret_estimation esti ON esti.estimation_id = est_old.est_id
		LEFT JOIN employee e on e.id_employee = esti.created_by
		LEFT JOIN ret_old_metal_type t ON t.id_metal_type=est_old.id_old_metal_type
		where s.bill_id=".$bill_id."");
		//print_r($this->db->last_query());exit;
		$old_matel_details = $old_metal_query->result_array();
		foreach ($old_matel_details as $metal) {
			$return_data['old_matel_details'][]=array(
					'amount'			=>$metal['amount'],
					'bill_id'			=>$metal['bill_id'],
					'est_id'			=>$metal['est_id'],
					'dust_wt'			=>$metal['dust_wt'],
					'stone_wt'			=>$metal['stone_wt'],
					'gross_wt'			=>$metal['gross_wt'],
					'item_type'			=>$metal['item_type'],
					'metal_type'		=>$metal['metal_type'],
					'old_metal_sale_id'	=>$metal['old_metal_sale_id'],
					'purpose'			=>$metal['purpose'],
					'rate_per_gram'		=>$metal['rate_per_gram'],
					'stone_wt'			=>$metal['stone_wt'],
					'wastage_percent'	=>$metal['wastage_percent'],
					'wast_wt'	        =>$metal['wast_wt'],
					'bill_discount'		=>$metal['bill_discount'],
					'old_metal_type'	=>$metal['old_metal_type'],
					'net_wt'		    =>$metal['net_wt'],
					'esti_emp_name'		=>$item['esti_emp_name'],
					'esti_emp_id'		=>$item['esti_emp_id'],
					'stone_details'		=>$this->stone_details_by_bill_id($metal['old_metal_sale_id'])
				);
		}
		$return_details=$this->db->query("SELECT bill_items.bill_det_id, bill.bill_id, bill_items.item_type,bill_items.status,bill_items.return_item_cost,
		ifnull(bill_items.product_id, '') as product_id, ifnull(bill_items.tag_id, '') as tag_id, bill_items.esti_item_id,esti_id,
		ifnull(bill_items.design_id, '') as design_id, ifnull(pro.hsn_code,'') as hsn_code,
		bill_items.size, bill_items.uom, bill_items.piece,
		ifnull(bill_items.less_wt,'') as less_wt, bill_items.net_wt, bill_items.gross_wt,
		bill_items.calculation_based_on, bill_items.wastage_percent, IFNULL(bill_items.mc_value,'') as mc_value, bill_items.mc_type,
		bill_items.item_cost, ifnull(product_short_code, '-') as product_short_code,
		ifnull(product_name, '-') as product_name, bill_items.is_partial_sale,bill_items.bill_discount as discount,bill_items.item_total_tax,
		ifnull(design_code, '-') as design_code,
		ifnull(design_name, '') as design_name, pur.purity as purname,
		pro.tgrp_id as tax_group_id , tgrp_name, ifnull(c.id_metal,'') as metal_type,
		ifnull(des.fixed_rate,0) as fixed_rate,
		if(bill_items.tag_id != null,stn_price,stn_amount) as stone_price,
		if(bill_items.tag_id != null,stn_wgt,stn_wt) as stn_wgt,
		if(bill_items.tag_id != null,othermat_amount,other_mat_price) as othermat_amount,
		if(bill_items.tag_id != null,othermat_wt,other_mat_wgt) as othermat_wt,ref_bill.bill_no as ref_bill_no,ref_bill.bill_date as ref_bill_date,bill_items.total_sgst,bill_items.total_igst,bill_items.total_cgst,tax.tax_percentage, e.firstname as esti_emp_name, e.id_employee AS esti_emp_id
		FROM ret_billing as bill
		LEFT JOIN ret_bill_return_details d on d.bill_id=bill.bill_id
		LEFT JOIN ret_bill_details as bill_items ON bill_items.bill_det_id = d.ret_bill_det_id
		LEFT JOIN(SELECT b.bill_no,b.bill_id,date_format(b.bill_date,'%d-%m-%Y') as bill_date From ret_billing b) as ref_bill on ref_bill.bill_id=d.ret_bill_id
		LEFT JOIN (SELECT esti_id,est_item_id from ret_estimation_items where bil_detail_id is not null and purchase_status=1) as est_itms ON est_itms.est_item_id = bill_items.esti_item_id
		LEFT JOIN ret_estimation esti ON esti.estimation_id = est_itms.esti_id
		LEFT JOIN employee e on e.id_employee = esti.created_by
		
		LEFT JOIN ret_purity as pur ON pur.id_purity = bill_items.purity
		LEFT JOIN (SELECT bill_det_id,sum(price) as stn_price,sum(wt) as stn_wgt FROM `ret_billing_item_stones` GROUP by bill_det_id) as stn_detail ON stn_detail.bill_det_id = bill_items.bill_det_id
		LEFT JOIN (SELECT bill_det_id,sum(price) as other_mat_price,sum(price) as other_mat_wgt FROM `ret_billing_item_other_materials` GROUP by bill_det_id) as est_oth_mat ON est_oth_mat.bill_det_id = bill_items.bill_det_id
		LEFT JOIN (SELECT tag_id,sum(amount) as stn_amount,sum(wt) as stn_wt FROM `ret_taging_stone` GROUP by tag_id) as tag_stn_detail ON tag_stn_detail.tag_id = bill_items.tag_id
		LEFT JOIN (SELECT tag_id,sum(price) as othermat_amount,sum(wt) as othermat_wt FROM `ret_taging_other_materials` GROUP by tag_id) as tag_other_mat ON tag_other_mat.tag_id = bill_items.tag_id
		LEFT JOIN ret_product_master as pro ON pro.pro_id = bill_items.product_id
		LEFT JOIN ret_category c on c.id_ret_category = pro.cat_id
		LEFT JOIN metal mt on mt.id_metal=c.id_metal
		LEFT JOIN ret_design_master as des ON des.design_no = bill_items.design_id
		LEFT JOIN ret_taxgroupmaster as txgrp ON txgrp.tgrp_id = pro.tgrp_id
		LEFT JOIN (select i.tgi_taxcode,i.tgi_tgrpcode,
		GROUP_CONCAT(m.tax_percentage) as tax_percentage,
		GROUP_CONCAT(i.tgi_calculation) as tgi_calculation
		FROM ret_taxgroupitems i
		LEFT JOIN ret_taxmaster m on m.tax_id=i.tgi_taxcode) as tax on tax.tgi_tgrpcode=pro.tgrp_id
		WHERE  bill.bill_id ='".$bill_id."' and (bill.bill_type=7 OR bill.bill_type=3)");
		//print_r($this->db->last_query());exit;
		$return_data["return_details"] = $return_details->result_array();
		
		$sales_ret_trans_details=$this->db->query("SELECT SUM(t.gross_wt) as gross_wt,SUM(d.piece) as piece,SUM(d.item_cost) as item_cost,SUM(d.item_total_tax) as item_total_tax,IFNULL(SUM(d.total_cgst),0) as total_cgst,IFNULL(SUM(d.total_igst),0) as total_igst,IFNULL(SUM(d.total_sgst),0) as total_sgst,
        cat.name as category_name ,cat.hsn_code,d.rate_per_grm,tax.tax_percentage
        FROM ret_billing b
        LEFT JOIN ret_bill_return_details r ON r.bill_id=b.bill_id
        LEFT JOIN ret_bill_details d ON d.bill_det_id=r.ret_bill_det_id 
        LEFT JOIN ret_taging t ON t.tag_id=d.tag_id
        LEFT JOIN ret_product_master pro ON pro.pro_id=t.product_id
        LEFT JOIN ret_category cat ON cat.id_ret_category=pro.cat_id 
        LEFT JOIN metal mt on mt.id_metal=cat.id_metal
        LEFT JOIN (select i.tgi_taxcode,i.tgi_tgrpcode,
		(m.tax_percentage) as tax_percentage,
		(i.tgi_calculation) as tgi_calculation
		FROM ret_taxgroupitems i
		LEFT JOIN ret_taxmaster m on m.tax_id=i.tgi_taxcode) as tax on tax.tgi_tgrpcode=mt.tgrp_id
        WHERE b.bill_id=".$bill_id." and b.bill_type=14
        GROUP by cat.id_ret_category");
        //print_r($this->db->last_query());exit;
        $return_data["sales_ret_trans_details"] = $sales_ret_trans_details->result_array();
        
		
	    $order_adv_details=$this->db->query("SELECT a.bill_adv_id,a.bill_id,a.advance_type,
		a.order_no,a.advance_amount,a.advance_type,a.advance_weight,a.rate_per_gram,a.store_as
		from ret_billing_advance a 
		
		where a.bill_id=".$bill_id."");
		$return_data["advance_details"] = $order_adv_details->result_array();
		
        $chit_details=$this->db->query("SELECT pay.total_installments,pay.scheme_type,pay.paid_installments,pay.firstPayDisc_value,pay.id_scheme,s.closing_amount,c.utilized_amt,c.scheme_account_id,
        concat(sch.code,'-',s.scheme_acc_number) as scheme_acc_number,c.closing_weight,c.savings_in_wastage,c.savings_in_making_charge
		from ret_billing_chit_utilization c  
        left join scheme_account s on s.id_scheme_account=c.scheme_account_id
        LEFT JOIN scheme sch on sch.id_scheme=s.id_scheme
        LEFT JOIN (select s.total_installments,s.scheme_type,s.id_scheme,s.firstPayDisc_value,sa.id_scheme_account,IFNULL(IF(sa.is_opening=1,IFNULL(sa.paid_installments,0)+ IFNULL(if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues)),0), if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues))) ,0)as paid_installments
				FROM payment p
				left join scheme_account sa on sa.id_scheme_account=p.id_scheme_account
				left join scheme s on s.id_scheme=sa.id_scheme
				where p.payment_status=1 group by sa.id_scheme_account) as pay on pay.id_scheme_account=s.id_scheme_account
		where c.bill_id=".$bill_id."");
		$return_data["chit_details"] = $chit_details->result_array();
		
		
		$repair_order_details=$this->db->query("SELECT c.order_no,od.orderno,p.product_name,des.design_name,od.weight,od.totalitems,od.wast_percent,od.mc,od.completed_weight,od.rate,IFNULL(od.total_sgst,0) sgst,IFNULL(od.total_cgst,0) cgst,IFNULL(od.total_igst,0) igst,od.repair_tot_tax,od.repair_percent
        FROM customerorderdetails od
        LEFT JOIN customerorder c ON c.id_customerorder=od.id_customerorder
        LEFT JOIN ret_product_master p ON p.pro_id=od.id_product
        LEFT JOIN ret_design_master des ON des.design_no=od.design_no
        LEFT JOIN ret_billing b ON b.bill_id=od.bill_id 
        WHERE od.bill_id=".$bill_id." ");
		// print_r($this->db->last_query());exit;
        $return_data['repair_order_details']=$repair_order_details->result_array();
		
		$order_adj=$this->db->query("SELECT a.received_amount,a.received_weight,a.rate_per_gram,b.bill_id,a.store_as,a.advance_amount,date_format(b.bill_date,'%d-%m-%Y') as bill_date
		
        FROM ret_billing b
        
        LEFT JOIN ret_billing_advance a ON a.bill_id=b.bill_id
        
        WHERE a.is_adavnce_adjusted=1 and b.bill_status=1 and a.adjusted_bill_id=".$bill_id." ");
        $return_data['order_adj']=$order_adj->result_array();
        
        $voucher_details=$this->db->query("SELECT a.gift_voucher_amt,b.bill_id
		
        FROM ret_billing b
        
        LEFT JOIN ret_billing_gift_voucher_details a ON a.bill_id=b.bill_id
        
        WHERE b.bill_status=1 and b.bill_id=".$bill_id." ");
        
        $return_data['voucher_details']=$voucher_details->result_array();
        
        if($bill_type==5)
        {
            $order_details=$this->db->query("SELECT od.orderno,p.product_name,des.design_name,od.weight,od.totalitems,od.wast_percent,od.mc,
            subDes.sub_design_name,concat(s.value,' ',s.name) as size_name
            FROM customerorderdetails od
            LEFT JOIN customerorder c ON c.id_customerorder=od.id_customerorder
            LEFT JOIN ret_product_master p ON p.pro_id=od.id_product
            LEFT JOIN ret_design_master des ON des.design_no=od.design_no
            LEFT JOIN ret_sub_design_master subDes on subDes.id_sub_design = od.id_sub_design
            LEFT JOIN ret_billing_advance a ON a.id_customerorder=c.id_customerorder
            LEFT JOIN ret_billing b ON b.bill_id=a.bill_id
            LEFT JOIN ret_size s ON s.id_size = od.size 
            WHERE c.order_to=".$bill_det['bill_cus_id']." AND b.bill_id=".$bill_id." AND b.bill_type=5 AND b.bill_status=1 GROUP by od.id_orderdetails");
            $return_data['order_details']=$order_details->result_array();
        }
        
        
        
		return $return_data;
	}
	
    function get_billing_advance_details($bill_id)
	{
        $adv_details_query=$this->db->query("SELECT r.bill_no,date_format(r.bill_date,'%d-%m-%Y') as bill_date,adj.bill_id,adj.utilized_amt as adjuseted_amt,adj.id_issue_receipt,
        r.amount as tot_receipt_amount,IFNULL(a.tot_utilized_amt,0) as tot_utilized_amt,(r.amount-adj.utilized_amt-IFNULL(a.tot_utilized_amt,0)) as bal_amt
        FROM ret_advance_utilized adj 
        LEFT JOIN ret_issue_receipt r ON r.id_issue_receipt = adj.id_issue_receipt
        LEFT JOIN (SELECT IFNULL(SUM(adj.utilized_amt),0) as tot_utilized_amt,adj.id_issue_receipt,adj.bill_id
        FROM ret_advance_utilized adj
        LEFT JOIN ret_billing b ON b.bill_id = adj.bill_id
        WHERE b.bill_status = 1 AND adj.bill_id < ".$bill_id." GROUP by adj.id_issue_receipt) as a ON a.id_issue_receipt = r.id_issue_receipt
        WHERE adj.bill_id = ".$bill_id." ");
        //print_r($this->db->last_query());exit;
	   	return $adv_details_query->result_array();
	}
	
	function get_bill_stone_details($bill_id)
	{
	    $sql=$this->db->query("SELECT s.pieces as st_pcs,IFNULL(s.price,0) as st_price,IFNULL(s.certification_price,0) as certification_price
        FROM ret_billing_item_stones s 
        LEFT JOIN ret_stone st ON st.stone_id=s.stone_id
        WHERE s.bill_id=".$bill_id."");
        return $sql->result_array();
	}
	
	function get_advance_adjusted($bill_id)
	{
	    $advance_adjusted=$this->db->query("SELECT IFNULL(sum(rau.utilized_amt),0) as adj_amt
			FROM ret_billing bill
			LEFT JOIN ret_advance_utilized rau on rau.bill_id=bill.bill_id
			where rau.bill_id is not null
			and bill.bill_status=1 and bill.bill_id=".$bill_id."");
			//print_r($this->db->last_query());exit;
		return $advance_adjusted->row()->adj_amt;
	}
	function get_order_advance($order_no)
	{
		$sql="select IFNULL(sum(a.advance_amount),0) as advance_amount  from ret_billing_advance a where a.order_no='".$order_no."'";
		return $this->db->query($sql)->row('advance_amount');
	}
	function get_empty_record()
    {
		$emptyquery = $this->db->field_data('ret_billing');
		
		$min_pan_amt = $this->get_ret_settings('min_pan_amt');
		
		$is_pan_required = $this->get_ret_settings('is_pan_required');
		
		$spc_gift_voucher = $this->get_ret_settings('spc_gift_voucher');
		
		$min_wt_gram = $this->get_ret_settings('min_wt_gram');
		
		$free_gift_validate_days = $this->get_ret_settings('free_gift_validate_days');
		
		$per_gram_amt = $this->get_ret_settings('per_gram_amt');
		
		$is_counter_req = $this->get_ret_settings('is_counter_req');
		
		$is_tcs_required = $this->get_ret_settings('is_tcs_required');
		
		$tcs_tax_per = $this->get_ret_settings('tcs_tax_per');
		
		$tcs_min_bill_amt = $this->get_ret_settings('tcs_min_bill_amt');
		
		$repair_percentage = $this->get_ret_settings('repair_order_per');
		
		$is_credit_enable = $this->get_ret_settings('is_credit_enable');
		
		$bill_discount_type = $this->get_ret_settings('bill_discount_type');
		
		$weightschemecaltype = $this->get_ret_settings('weightschemecaltype');
		
		$weight_scheme_closure_type = $this->get_ret_settings('weight_scheme_closure_type');
		
		$gift = $this->get_gift_voucher_settings();
		
		$employee_settings=$this->get_employee_settings($this->session->userdata('uid'));
		
		$profile_settings=$this->get_profile_settings($this->session->userdata('profile'));
		
		$id_branch  = $this->session->userdata('id_branch');
		
		$company_details    = $this->getCompanyDetails($id_branch);
		
		$emptydata = array();
		foreach ($emptyquery as $field)
		{
			$emptydata[$field->name] = $field->default;
		}
		$emptydata['bill_date'] = date('d-m-Y H:i:s');
		$emptydata['min_pan_amt'] = $min_pan_amt;
		$emptydata['is_pan_required'] = $is_pan_required;
		
		$emptydata['spc_gift_voucher'] = $spc_gift_voucher;
		
		$emptydata['cus_name'] 			  = '';
		
		$emptydata['is_counter_req'] = $is_counter_req;
		
		$emptydata['is_tcs_required'] = $is_tcs_required;
		
		$emptydata['tcs_tax_per'] = $tcs_tax_per;
		
		$emptydata['tcs_min_bill_amt'] = $tcs_min_bill_amt;
		
		$emptydata['financial_year'] = $this->GetFinancialYear();
		
		$emptydata['disc_limit_type'] = $employee_settings['disc_limit_type'];
		
		$emptydata['disc_limit'] = $employee_settings['disc_limit'];
		
		$emptydata['otp_dis_approval'] = $employee_settings['otp_dis_approval'];
		
		$emptydata['repair_percentage'] = $repair_percentage;
		
		$emptydata['cmp_state'] = $company_details['id_state'];
		
		$emptydata['cmp_country'] = $company_details['id_country'];
		
		$emptydata['is_credit_enable'] = $is_credit_enable;
		
		$emptydata['bill_discount_type'] = $bill_discount_type;
		
		$emptydata['weight_scheme_closure_type'] = $weight_scheme_closure_type;
		
		$emptydata['weightschemecaltype'] = $weightschemecaltype;
		
		$emptydata['allow_other_bill_types'] = $profile_settings['allow_other_bill_types'];
		
		return $emptydata;
	}
	
	function GetFinancialYear()
	{
		$sql=$this->db->query("SELECT fin_year_code,fin_status,fin_year_name From ret_financial_year");
		return $sql->result_array();
	}
	function get_retSettings()
    {
		$min_pan_amt = $this->get_ret_settings('min_pan_amt');
		$is_pan_required = $this->get_ret_settings('is_pan_required');
		$on_exchange_in_billing = $this->get_ret_settings('on_exchange_in_billing');
		$maxcash_setting = $this->get_maxcash_settings();
		$emptydata = array();
		$emptydata['min_pan_amt'] = $min_pan_amt;
		$emptydata['is_pan_required'] = $is_pan_required;
		$emptydata['on_exchange_in_billing'] = $on_exchange_in_billing;
		$emptydata['validate_cash_amt'] = $maxcash_setting['validate_cash_amt'];
		$emptydata['max_cash_amt'] = $maxcash_setting['max_cash_amt'];
		return $emptydata;
	}
	
	public function encrypt($str)
	{
		return base64_encode($str);		
	}
	
	function createNewCustomer($cusname, $cusmobile, $branch,$id_village,$country,$state,$city,$address1,$address2,$address3,$pincode,$mail,$cus_type,$pan_no,$aadharid,$gst_no)
	{
		$customer_check_query = $this->db->query("SELECT * FROM customer WHERE mobile='".$cusmobile."'");
		if($customer_check_query->num_rows() == 0){
			$insert_data = array(
			    "firstname" => strtoupper($cusname), 
			    "id_branch" => $branch, 
			    "mobile"    => $cusmobile, 
			    "username"  => $cusmobile, 
			    "passwd"    => $this->encrypt($cusmobile),
			    "id_village"=>$id_village,
			    "email"       =>($mail!='' ? $mail:NULL),
			    "cus_type"  =>$cus_type,
			    'pan'       =>($pan_no!='' ? $pan_no :NULL),
			    'gst_number'=>($gst_no!='' ? $gst_no :NULL),
			    'aadharid'  =>($aadharid!='' ? $aadharid :NULL),
			    'date_add'=>date("Y-m-d H:i:s")
			  );
			$cus_insert_id = $this->insertData($insert_data, "customer");
			if(!empty($cus_insert_id))
			{
                $insert_addressdata  = array(
                                    "id_country"    =>!empty($country) ? $country :null, 
                                    "id_state"      =>!empty($state) ? $state :null, 
                                    "id_city"       =>!empty($city) ? $city :null,
                                    "company_name"  =>($cus_type==2 ? strtoupper($cusname):NULL),
                                    "id_customer"   =>$cus_insert_id,
                                    "address1"      =>$address1,
                                    'address2'      =>$address2,
                                    'address3'      =>$address3,
                                    'pincode'       =>$pincode,
                                    'date_add'      =>date("Y-m-d H:i:s")
                );
				$cus_addressinsert_id = $this->insertData($insert_addressdata, "address");
				$insert_data["id_customer"] = $cus_insert_id;
				return array("success" => TRUE, "message" => "Customer details added successfully", "response" => $insert_data);
			}else{
				return array("success" => FALSE, "message" => "Could not add customer, please try again", "response" => array());
			}
		}else{
			return array("success" => FALSE, "message" => "Given mobile number already exist", "response" => $customer_check_query->row());
		}
	}
	
	function updateNewCustomer($id_customer,$cusname, $cusmobile, $branch,$id_village,$country,$state,$city,$address1,$address2,$address3,$pincode,$mail,$cus_type,$pan_no,$aadharid,$gst_no)
    {
		$customer_check_query = $this->db->query("SELECT * FROM customer WHERE id_customer !='".$id_customer."' and mobile='".$cusmobile."'");
		if($customer_check_query->num_rows() == 0){
			$update_data = array(
			    "firstname"   => strtoupper($cusname), 
			    "id_branch"   => $branch, 
			    "id_village"  =>($id_village!='' ? $id_village:NULL),
			    "email"       =>($mail!='' ? $mail:NULL),
			    "cus_type"    =>$cus_type,
			    'pan'         =>($pan_no!='' ? $pan_no :NULL),
			    'gst_number'  =>($gst_no!='' ? $gst_no :NULL),
			    'aadharid'    =>($aadharid!='' ? $aadharid :NULL),
			    );
			    
			$cus_update_id = $this->updateData($update_data, "id_customer",$id_customer,"customer");
			//print_r($this->db->last_query());exit;
			
				$cus_addr_delete_id = $this->deleteData("id_customer",$id_customer,"address");
				
				$insert_addressdata  = array(
				    "id_country" =>!empty($country) ? $country :NULL, 
				    "id_state" =>!empty($state) ? $state :NULL, 
				    "id_city" =>!empty($city) ? $city :NULL,
				    "id_customer" =>$id_customer,
				    "address1"=>$address1,
				    'address2'=>$address2,
				    'address3'=>$address3,
				    'pincode'=>$pincode,
				    "company_name"=>($cus_type==2 ? strtoupper($cusname):NULL),
				    'date_add'=>date("Y-m-d H:i:s"));
				    
				$cus_addressinsert_id = $this->insertData($insert_addressdata, "address");
				if($cus_addressinsert_id) {
					$update_data["id_customer"] = $id_customer;
					$update_data["username"] = $cusmobile;
					
					$update_data["mobile"] = $cusmobile;
					return array("success" => TRUE, "message" => "Customer details Updated successfully", "response" => $update_data);
				}
			
		}else{
			return array("success" => FALSE, "message" => "Given mobile number already exist", "response" => $customer_check_query->row());
		}
	}
	
    function getBranchDayClosingData($id_branch)
    {
	    $sql = $this->db->query("SELECT id_branch,is_day_closed,entry_date from ret_day_closing where id_branch=".$id_branch);  
	    return $sql->row_array();
	}
	function getEstimationDetails($estId, $billType, $id_branch, $order_no, $fin_year, $metal_type,$tag_code)
	{
		// 1-Sales, 2-Sales&Purchase, 3-Sales,purchase&Return, 4-Purchase, 5-Order Advance, 6-Advance,7-Sales Return, 15-> Approval sales
		
		$dCData=$this->getBranchDayClosingData($id_branch);
		
		$item_details=array();
		$order_details=array();
		$return_data = array("item_details" => array(), "old_matel_details" => array(), "stone_details" => array(), "other_material_details" => array(), "voucher_details" => array(), "chit_details" => array(),'advance_details'=>array(),"order_details"=>array(),"order_sale_det"=>array(),"packing_details"=>array());
		if($billType == 5 && $order_no != '')
		{ // Order Advance
		$order_query = $this->db->query("SELECT d.id_customerorder,c.order_no,d.id_product,d.design_no,d.wast_percent,d.mc,d.stn_amt,d.weight as net_wt,d.weight as gross_wt,d.totalitems,d.rate,d.id_purity,
        IFNULL(prod.hsn_code,'-') as hsn_code,prod.product_name,des.design_name,p.purity as purname,
        m.tgrp_id as tax_group_id , tgrp_name, ifnull(cat.id_metal,'') as metal_type,prod.calculation_based_on,d.size,des.design_code,prod.gift_applicable,
        
        concat(cus.firstname,' ',cus.mobile) as cus_name,c.order_to,IFNULL(cus.pan,'') as pan_no,0 as est_rate_per_grm
        
        FROM customerorder c
        LEFT JOIN customerorderdetails d on d.id_customerorder=c.id_customerorder
        LEFT JOIN ret_product_master prod on prod.pro_id=d.id_product
        LEFT JOIN ret_design_master des on des.design_no=d.design_no
        LEFT JOIN ret_category cat on cat.id_ret_category=prod.cat_id
        LEFT JOIN metal m ON m.id_metal=cat.id_metal
        LEFT JOIN ret_purity p on p.id_purity=d.id_purity
        LEFT JOIN ret_taxgroupmaster as txgrp ON txgrp.tgrp_id = m.tgrp_id
        
        LEFT JOIN customer cus on cus.id_customer=c.order_to
         
        where c.id_customerorder is not null  and d.orderstatus<=4 and c.order_for=2
        ".($order_no!='' ? " and c.order_no='".$order_no."'" :'')." 
        ".($metal_type!='' ? " and m.id_metal='".$metal_type."'" :'')." 
        ".($fin_year!='' ? " and c.fin_year_code='".$fin_year."'" :'')." 
        ".($id_branch!='' ? " and c.order_from=".$id_branch."" :'')."");
		$return_data['order_details'] = $order_query->result_array();
		//print_r($this->db->last_query());exit;
		}
		else if(($billType == 11) && $order_no != '')
		{
    	    $order_query = $this->db->query("SELECT d.id_customerorder,c.order_no,d.id_product,d.design_no,d.wast_percent,d.mc,d.stn_amt,d.weight as net_wt,d.weight as gross_wt,d.totalitems,d.rate,d.id_purity,
            IFNULL(prod.hsn_code,'-') as hsn_code,prod.product_name,IFNULL(des.design_name,'') as design_name,p.purity as purname,
            prod.tgrp_id as tax_group_id , tgrp_name, ifnull(cat.id_metal,'') as metal_type,prod.calculation_based_on,d.size,des.design_code,prod.gift_applicable,cat.id_ret_category,IFNULL(d.completed_weight,0) as completed_weight,IFNULL(d.rate,0) as amount,d.id_orderdetails,concat(cus.firstname,' ',cus.mobile) as cus_name,c.order_to,
            a.id_state as cus_state,a.id_state as cmp_state,a.id_country as cmp_country,a.id_country as cus_country,IFNULL(cus.pan,'') as pan_no,0 as est_rate_per_grm,c.scheme_closure_benefit
            
            FROM customerorder c
            LEFT JOIN customerorderdetails d on d.id_customerorder=c.id_customerorder
            LEFT JOIN ret_product_master prod on prod.pro_id=d.id_product
            LEFT JOIN ret_design_master des on des.design_no=d.design_no
            LEFT JOIN ret_category cat on cat.id_ret_category=prod.cat_id
            LEFT JOIN metal m ON m.id_metal=cat.id_metal
            LEFT JOIN ret_purity p on p.id_purity=d.id_purity
            LEFT JOIN ret_taxgroupmaster as txgrp ON txgrp.tgrp_id = prod.tgrp_id
            LEFT JOIN customer cus on cus.id_customer=c.order_to
            LEFT JOIN address a on a.id_customer=cus.id_customer join company cmp
            where c.id_customerorder is not null  and d.orderstatus=4 and c.order_for=2
            ".($order_no!='' ? " and c.order_no='".$order_no."'" :'')." 
            ".($id_branch!='' ? ($billType==11 ? " and d.current_branch=".$id_branch."" : " and c.order_from=".$id_branch."") :'')."");
            //echo $this->db->last_query();exit;
            $return_data['order_details'] = $order_query->result_array();
            
            $repair_other_metal_details = $this->db->query("SELECT d.detail_id,r.rate_field,d.id_orderdetails,p.pro_id as product_id,p.product_name,d.id_purity,
            	    d.gross_wt,IFNULL(d.less_wt,0) as less_wt,IFNULL(d.net_wt,0) as net_wt,d.wast_percent as wastage_percent,d.mc_value,d.mc_type,
                    pur.purity,mt.tgrp_id as tax_group_id,'' as esti_item_id,'2' as calculation_based_on,'1' as piece,tgrp_name
                    FROM customer_order_other_details d 
                    LEFT JOIN customerorderdetails ord ON ord.id_orderdetails = d.id_orderdetails
                    LEFT JOIN customerorder c ON c.id_customerorder = ord.id_customerorder
                    LEFT JOIN ret_product_master p ON p.pro_id = d.id_product
                    LEFT JOIN ret_category cat ON cat.id_ret_category = p.cat_id
                    LEFT JOIN ret_purity pur ON pur.id_purity = d.id_purity
                    LEFT JOIN metal mt ON mt.id_metal = cat.id_metal
		            LEFT JOIN ret_taxgroupmaster as txgrp ON txgrp.tgrp_id = p.tgrp_id
		
                    LEFT JOIN ret_metal_purity_rate r on r.id_metal=cat.id_metal and r.id_purity=d.id_purity
                    WHERE ord.orderstatus=4
                    ".($order_no!='' ? " and c.order_no='".$order_no."'" :'')." 
                    ".($id_branch!='' ? ($billType==11 ? " and ord.current_branch=".$id_branch."" : " and c.order_from=".$id_branch."") :'')."
                    ");
            //echo $this->db->last_query();exit;
            $item_details = $repair_other_metal_details->result_array();
            
		}
		else if($billType == 9 && $estId!='')
		
		{ //Order Delivery
		
		$items_query = $this->db->query("SELECT est_itms.est_item_id, esti_id, item_type, est_itms.purchase_status,est.esti_no,
		ifnull(est_itms.product_id, '') as product_id, ifnull(est_itms.tag_id, '') as tag_id,
		ifnull(est_itms.design_id, '') as design_id, ifnull(pro.hsn_code,'') as hsn_code,
		est_itms.purity as purid, IFNULL(est_itms.size,'') as size, ifnull(est_itms.uom,'') as uom,IFNULL(est_itms.piece,'') as piece,
		ifnull(est_itms.less_wt,'') as less_wt,IFNULL(est_itms.net_wt,0) as net_wt,IFNULL(est_itms.gross_wt,0) as gross_wt,
		est_itms.calculation_based_on, IFNULL(est_itms.wastage_percent,0) as wastage_percent , IFNULL(est_itms.mc_value,'') as mc_value, est_itms.mc_type,
		tag.sell_rate as item_cost, ifnull(product_short_code, '-') as product_short_code,
		ifnull(product_name, '-') as product_name, est_itms.is_partial,est_itms.discount,
		ifnull(design_code, '-') as design_code,
		ifnull(design_name, '') as design_name, pur.purity as purname,
		pro.tgrp_id as tax_group_id , tgrp_name, ifnull(c.id_metal,'') as metal_type,
		ifnull(des.fixed_rate,0) as fixed_rate,
		if(est_itms.id_orderdetails!='',ord.stn_amt,if(est_itms.tag_id != '',tag_stn_detail.stn_amount,stn_detail.stn_price)) as stone_price,
		IFNULL(tag_stn_detail.certification_cost,0) as certification_cost,
		if(est_itms.tag_id != null,stn_wgt,stn_wt) as stn_wgt,
		if(est_itms.tag_id != null,othermat_amount,other_mat_price) as othermat_amount,
		if(est_itms.tag_id != null,othermat_wt,other_mat_wgt) as othermat_wt,est_itms.is_non_tag,concat(cus.firstname,' ',cus.mobile) as cus_name,cus.id_customer,
		v.village_name,if(cus.is_vip=1,'Yes','No') as vip,
		(select count(sa.id_scheme_account) from scheme_account sa left join customer cust on cust.id_customer=sa.id_customer) as accounts,
		pro.min_wastage,pro.max_wastage,pro.stock_type,nt.no_of_piece as available_pieces,nt.gross_wt as available_gross_wt,IFNULL(est_itms.orderno,'') as order_no,
		
		ifnull(est_itms.id_orderdetails,'') as id_orderdetails,IFNULL(ord.id_customerorder,'') as id_customerorder,pro.gift_applicable,r.rate_field, rec.charge_value,IFNULL(cus.pan,'') as pan_no,
		IFNULL(est_itms.id_collecion_maping_det,'') as id_collecion_maping_det,est.esti_for, est_itms.id_sub_design,ifnull(tag.tag_code,'') as tag_code,est_itms.est_rate_per_grm,IFNULL(est.discount,0) as est_discount,
		c.scheme_closure_benefit
		
		FROM ret_estimation as est 
		LEFT JOIN ret_estimation_items as est_itms ON est_itms.esti_id = est.estimation_id
		
		left join ret_taging tag on tag.tag_id=est_itms.tag_id
		
		LEFT JOIN customerorderdetails as ord ON ord.id_orderdetails=est_itms.id_orderdetails 
		LEFT JOIN (SELECT est_item_id,sum(price) as stn_price,sum(wt) as stn_wgt FROM `ret_estimation_item_stones` GROUP by est_item_id) as stn_detail ON stn_detail.est_item_id = est_itms.est_item_id
		LEFT JOIN (SELECT est_item_id,sum(price) as other_mat_price,sum(price) as other_mat_wgt FROM `ret_estimation_item_other_materials` GROUP by est_item_id) as est_oth_mat ON est_oth_mat.est_item_id = est_itms.est_item_id
		LEFT JOIN (SELECT tag_id,sum(amount) as stn_amount,sum(certification_cost) as certification_cost,sum(wt) as stn_wt FROM `ret_taging_stone` GROUP by tag_id) as tag_stn_detail ON tag_stn_detail.tag_id = est_itms.tag_id
		LEFT JOIN (SELECT tag_id,sum(price) as othermat_amount,sum(wt) as othermat_wt FROM `ret_taging_other_materials` GROUP by tag_id) as tag_other_mat ON tag_other_mat.tag_id = est_itms.tag_id
		LEFT JOIN ret_product_master as pro ON pro.pro_id = est_itms.product_id
		LEFT JOIN ret_category c on c.id_ret_category = pro.cat_id
		LEFT JOIN metal mt on mt.id_metal=c.id_metal
		LEFT JOIN ret_design_master as des ON des.design_no = est_itms.design_id
		LEFT JOIN ret_purity as pur ON pur.id_purity = est_itms.purity
		LEFT JOIN ret_taxgroupmaster as txgrp ON txgrp.tgrp_id = pro.tgrp_id
		LEFT JOIN customer cus on cus.id_customer=est.cus_id
		LEFT JOIN village v on v.id_village=cus.id_village
		
		LEFT JOIN ret_metal_purity_rate r on r.id_metal=c.id_metal and r.id_purity=est_itms.purity 
		LEFT JOIN (SELECT est_item_id, SUM(IFNULL(amount,0)) AS charge_value FROM ret_estimation_other_charges GROUP BY est_item_id) AS rec ON rec.est_item_id = est_itms.est_item_id
		LEFT JOIN ret_nontag_item nt on nt.product=est_itms.product_id ".($id_branch!='' ? " and nt.branch=".$id_branch."" :'')." 
		WHERE ".(!empty($id_branch)? "est.id_branch=".$id_branch."" :'')." 
		".($estId!='' ? " and est.esti_no ='".$estId."' " :'')." 
		".($metal_type!='' ? " and mt.id_metal='".$metal_type."'" :'')." 
		AND est_itms.est_item_id IS NOT NULL and est_itms.purchase_status=0 AND (tag.tag_status =0 OR tag.tag_status IS NULL OR tag.is_partial=1)
		AND date(est.estimation_datetime)='".$dCData['entry_date']."'
		GROUP by  est_itms.est_item_id
		order by est_itms.esti_id DESC");
		//print_r($this->db->last_query());exit;
		$item_details = $items_query->result_array();
		}
		else if(($billType == 1 || $billType == 2 || $billType == 3) && $estId!=''){
		$items_query = $this->db->query("SELECT est_itms.est_item_id, esti_id, item_type, est_itms.purchase_status,est.esti_no,
		ifnull(est_itms.product_id, '') as product_id, ifnull(est_itms.tag_id, '') as tag_id,
		ifnull(est_itms.design_id, '') as design_id, ifnull(pro.hsn_code,'') as hsn_code,
		est_itms.purity as purid, IFNULL(est_itms.size,'') as size, ifnull(est_itms.uom,'') as uom,IFNULL(est_itms.piece,'') as piece,
		ifnull(est_itms.less_wt,'') as less_wt,IFNULL(est_itms.net_wt,0) as net_wt,IFNULL(est_itms.gross_wt,0) as gross_wt,
		est_itms.calculation_based_on, IFNULL(est_itms.wastage_percent,0) as wastage_percent , IFNULL(est_itms.mc_value,'') as mc_value, est_itms.mc_type,
		ifnull(product_short_code, '-') as product_short_code,
		
		if(est_itms.item_type = 0, tag.sell_rate, (est_itms.item_cost - est_itms.item_total_tax)) as item_cost,
		ifnull(product_name, '-') as product_name, est_itms.is_partial,est_itms.discount,
		ifnull(design_code, '-') as design_code,
		ifnull(design_name, '') as design_name, pur.purity as purname,
		pro.tgrp_id as tax_group_id , tgrp_name, ifnull(c.id_metal,'') as metal_type,
		ifnull(des.fixed_rate,0) as fixed_rate,
		if(est_itms.id_orderdetails!='',ord.stn_amt,if(est_itms.tag_id != '',tag_stn_detail.stn_amount,stn_detail.stn_price)) as stone_price,
		IFNULL(tag_stn_detail.certification_cost,0) as certification_cost,
		if(est_itms.tag_id != null,stn_wgt,stn_wt) as stn_wgt,
		if(est_itms.tag_id != null,othermat_amount,other_mat_price) as othermat_amount,
		if(est_itms.tag_id != null,othermat_wt,other_mat_wgt) as othermat_wt,est_itms.is_non_tag,concat(cus.firstname,' ',cus.mobile) as cus_name,cus.id_customer,
		v.village_name,if(cus.is_vip=1,'Yes','No') as vip,
		(select count(sa.id_scheme_account) from scheme_account sa left join customer cust on cust.id_customer=sa.id_customer) as accounts,
		pro.min_wastage,pro.max_wastage,pro.stock_type,nt.no_of_piece as available_pieces,nt.gross_wt as available_gross_wt,IFNULL(est_itms.orderno,'') as order_no,
		
		ifnull(est_itms.id_orderdetails,'') as id_orderdetails,IFNULL(ord.id_customerorder,'') as id_customerorder,pro.gift_applicable,r.rate_field, 
		ifnull(rec.charge_value, 0) as charge_value,IFNULL(cus.pan,'') as pan_no,IFNULL(est_itms.id_collecion_maping_det,'') as id_collecion_maping_det,est.esti_for, est_itms.id_sub_design,
		
		ifnull(tag.tag_code,'') as tag_code,est_itms.est_rate_per_grm,IFNULL(est.discount,0) as est_discount,c.scheme_closure_benefit,IFNULL(tag.net_wt,0) as tag_net_wt
		
		FROM ret_estimation as est 
		LEFT JOIN ret_estimation_items as est_itms ON est_itms.esti_id = est.estimation_id
		
		left join ret_taging tag on tag.tag_id=est_itms.tag_id
		
		LEFT JOIN customerorderdetails as ord ON ord.id_orderdetails=est_itms.id_orderdetails 
		LEFT JOIN (SELECT est_item_id,sum(price) as stn_price,sum(wt) as stn_wgt FROM `ret_estimation_item_stones` GROUP by est_item_id) as stn_detail ON stn_detail.est_item_id = est_itms.est_item_id
		LEFT JOIN (SELECT est_item_id,sum(price) as other_mat_price,sum(price) as other_mat_wgt FROM `ret_estimation_item_other_materials` GROUP by est_item_id) as est_oth_mat ON est_oth_mat.est_item_id = est_itms.est_item_id
		LEFT JOIN (SELECT tag_id,sum(amount) as stn_amount,sum(certification_cost) as certification_cost,sum(wt) as stn_wt FROM `ret_taging_stone` GROUP by tag_id) as tag_stn_detail ON tag_stn_detail.tag_id = est_itms.tag_id
		LEFT JOIN (SELECT tag_id,sum(price) as othermat_amount,sum(wt) as othermat_wt FROM `ret_taging_other_materials` GROUP by tag_id) as tag_other_mat ON tag_other_mat.tag_id = est_itms.tag_id
		LEFT JOIN ret_product_master as pro ON pro.pro_id = est_itms.product_id
		LEFT JOIN ret_category c on c.id_ret_category = pro.cat_id
		LEFT JOIN metal mt on mt.id_metal=c.id_metal
		LEFT JOIN ret_design_master as des ON des.design_no = est_itms.design_id
		LEFT JOIN ret_purity as pur ON pur.id_purity = est_itms.purity
		LEFT JOIN ret_taxgroupmaster as txgrp ON txgrp.tgrp_id = pro.tgrp_id
		LEFT JOIN customer cus on cus.id_customer=est.cus_id
		LEFT JOIN village v on v.id_village=cus.id_village
		
		LEFT JOIN ret_metal_purity_rate r on r.id_metal=c.id_metal and r.id_purity=est_itms.purity
		LEFT JOIN (SELECT est_item_id, SUM(IFNULL(amount,0)) AS charge_value FROM ret_estimation_other_charges GROUP BY est_item_id) AS rec ON rec.est_item_id = est_itms.est_item_id
		LEFT JOIN ret_nontag_item nt on nt.product=est_itms.product_id ".($id_branch!='' ? " and nt.branch=".$id_branch."" :'')." 
		WHERE ".(!empty($id_branch)? "est.id_branch=".$id_branch."" :'')." 
		".($estId!='' ? " and est.esti_no ='".$estId."' " :'')." 
		".($metal_type!='' ? " and mt.id_metal=".$metal_type."" :'')."
		AND est_itms.est_item_id IS NOT NULL and tag.id_orderdetails IS NULL and est_itms.purchase_status=0 AND (tag.tag_status =0 OR tag.tag_status IS NULL OR tag.is_partial=1)
		AND date(est.estimation_datetime)='".$dCData['entry_date']."' 
		AND IF(ifnull(tag.tag_type,0)=0,IF(IFNULL(est_itms.tag_id,'')!='' ,tag.tag_type=0,est_itms.est_item_id IS NOT null),(tag.tag_type=1 AND tag.is_approval_stock_converted = 1))

		GROUP by  est_itms.est_item_id
		order by est_itms.esti_id DESC");
		//echo $this->db->_error_message(); exit;
		//print_r($this->db->last_query());exit;
		$item_details = $items_query->result_array();
		} 
		else if($billType == 15 && $estId !=''){
    		$items_query = $this->db->query("SELECT est_itms.est_item_id, esti_id, item_type, est_itms.purchase_status,est.esti_no,
    		ifnull(est_itms.product_id, '') as product_id, ifnull(est_itms.tag_id, '') as tag_id,
    		ifnull(est_itms.design_id, '') as design_id, ifnull(pro.hsn_code,'') as hsn_code,
    		est_itms.purity as purid, IFNULL(est_itms.size,'') as size, ifnull(est_itms.uom,'') as uom,IFNULL(est_itms.piece,'') as piece,
    		ifnull(est_itms.less_wt,'') as less_wt,IFNULL(est_itms.net_wt,0) as net_wt,IFNULL(est_itms.gross_wt,0) as gross_wt,
    		est_itms.calculation_based_on, IFNULL(est_itms.wastage_percent,0) as wastage_percent , IFNULL(est_itms.mc_value,'') as mc_value, est_itms.mc_type,
    		ifnull(product_short_code, '-') as product_short_code,
    		
    		if(est_itms.item_type = 0, tag.sell_rate, (est_itms.item_cost - est_itms.item_total_tax)) as item_cost,
    		ifnull(product_name, '-') as product_name, est_itms.is_partial,est_itms.discount,
    		ifnull(design_code, '-') as design_code,
    		ifnull(design_name, '') as design_name, pur.purity as purname,
    		pro.tgrp_id as tax_group_id , tgrp_name, ifnull(c.id_metal,'') as metal_type,
    		ifnull(des.fixed_rate,0) as fixed_rate,
    		if(est_itms.id_orderdetails!='',ord.stn_amt,if(est_itms.tag_id != '',tag_stn_detail.stn_amount,stn_detail.stn_price)) as stone_price,
    		IFNULL(tag_stn_detail.certification_cost,0) as certification_cost,
    		if(est_itms.tag_id != null,stn_wgt,stn_wt) as stn_wgt,
    		if(est_itms.tag_id != null,othermat_amount,other_mat_price) as othermat_amount,
    		if(est_itms.tag_id != null,othermat_wt,other_mat_wgt) as othermat_wt,est_itms.is_non_tag,concat(cus.firstname,' ',cus.mobile) as cus_name,cus.id_customer,
    		v.village_name,if(cus.is_vip=1,'Yes','No') as vip,
    		(select count(sa.id_scheme_account) from scheme_account sa left join customer cust on cust.id_customer=sa.id_customer) as accounts,
    		pro.min_wastage,pro.max_wastage,pro.stock_type,nt.no_of_piece as available_pieces,nt.gross_wt as available_gross_wt,IFNULL(est_itms.orderno,'') as order_no,
    		
    		ifnull(est_itms.id_orderdetails,'') as id_orderdetails,IFNULL(ord.id_customerorder,'') as id_customerorder,pro.gift_applicable,r.rate_field, 
    		ifnull(rec.charge_value, 0) as charge_value,IFNULL(cus.pan,'') as pan_no,IFNULL(est_itms.id_collecion_maping_det,'') as id_collecion_maping_det,est.esti_for, est_itms.id_sub_design,
    		
    		ifnull(tag.tag_code,'') as tag_code,est_itms.est_rate_per_grm,IFNULL(est.discount,0) as est_discount,c.scheme_closure_benefit,IFNULL(tag.net_wt,0) as tag_net_wt
    		
    		FROM ret_estimation as est 
    		LEFT JOIN ret_estimation_items as est_itms ON est_itms.esti_id = est.estimation_id
    		
    		left join ret_taging tag on tag.tag_id=est_itms.tag_id
    		
    		LEFT JOIN customerorderdetails as ord ON ord.id_orderdetails=est_itms.id_orderdetails 
    		LEFT JOIN (SELECT est_item_id,sum(price) as stn_price,sum(wt) as stn_wgt FROM `ret_estimation_item_stones` GROUP by est_item_id) as stn_detail ON stn_detail.est_item_id = est_itms.est_item_id
    		LEFT JOIN (SELECT est_item_id,sum(price) as other_mat_price,sum(price) as other_mat_wgt FROM `ret_estimation_item_other_materials` GROUP by est_item_id) as est_oth_mat ON est_oth_mat.est_item_id = est_itms.est_item_id
    		LEFT JOIN (SELECT tag_id,sum(amount) as stn_amount,sum(certification_cost) as certification_cost,sum(wt) as stn_wt FROM `ret_taging_stone` GROUP by tag_id) as tag_stn_detail ON tag_stn_detail.tag_id = est_itms.tag_id
    		LEFT JOIN (SELECT tag_id,sum(price) as othermat_amount,sum(wt) as othermat_wt FROM `ret_taging_other_materials` GROUP by tag_id) as tag_other_mat ON tag_other_mat.tag_id = est_itms.tag_id
    		LEFT JOIN ret_product_master as pro ON pro.pro_id = est_itms.product_id
    		LEFT JOIN ret_category c on c.id_ret_category = pro.cat_id
    		LEFT JOIN metal mt on mt.id_metal=c.id_metal
    		LEFT JOIN ret_design_master as des ON des.design_no = est_itms.design_id
    		LEFT JOIN ret_purity as pur ON pur.id_purity = est_itms.purity
    		LEFT JOIN ret_taxgroupmaster as txgrp ON txgrp.tgrp_id = pro.tgrp_id
    		LEFT JOIN customer cus on cus.id_customer=est.cus_id
    		LEFT JOIN village v on v.id_village=cus.id_village
    		
    		LEFT JOIN ret_metal_purity_rate r on r.id_metal=c.id_metal and r.id_purity=est_itms.purity
    		LEFT JOIN (SELECT est_item_id, SUM(IFNULL(amount,0)) AS charge_value FROM ret_estimation_other_charges GROUP BY est_item_id) AS rec ON rec.est_item_id = est_itms.est_item_id
    		LEFT JOIN ret_nontag_item nt on nt.product=est_itms.product_id ".($id_branch!='' ? " and nt.branch=".$id_branch."" :'')." 
    		WHERE ".(!empty($id_branch)? "est.id_branch=".$id_branch."" :'')." 
    		".($estId!='' ? " and est.esti_no ='".$estId."' " :'')." 
    		".($metal_type!='' ? " and mt.id_metal=".$metal_type."" :'')."
    		AND est_itms.est_item_id IS NOT NULL and tag.id_orderdetails IS NULL and est_itms.purchase_status=0 AND (tag.tag_status =0 OR tag.tag_status IS NULL OR tag.is_partial=1)
    		AND date(est.estimation_datetime)='".$dCData['entry_date']."' 
    		AND ifnull(tag.tag_type, 0) = 1 
    		GROUP by  est_itms.est_item_id
    		order by est_itms.esti_id DESC");
    		// echo $this->db->_error_message(); exit;
    		//print_r($this->db->last_query());exit;
    		$item_details = $items_query->result_array();
		} 
		
	
		else if(($billType == 1 || $billType == 2 || $billType == 3) && $tag_code!=''){
		$items_query = $this->db->query("SELECT tag.tag_code,ifnull(tag.tag_id,'') as tag_id,ifnull(tag.product_id, '') as product_id,ifnull(tag.design_id, '') as design_id, 
            ifnull(pro.hsn_code,'') as hsn_code,tag.purity as purid,IFNULL(tag.size,'') as size,tag.piece as piece,IFNULL(tag.less_wt,0) as less_wt,
            IFNULL(tag.gross_wt,0) as gross_wt,IFNULL(tag.net_wt,0) as net_wt,tag.sales_value as item_cost,ifnull(product_short_code, '-') as product_short_code,
            ifnull(product_name, '-') as product_name,ifnull(design_code, '-') as design_code,
            ifnull(design_name, '') as design_name, pur.purity as purname,
            mt.tgrp_id as tax_group_id , tgrp_name, ifnull(c.id_metal,'') as metal_type,
            IFNULL(tag_stn_detail.stn_amount,0) as stone_price,IFNULL(tag_stn_detail.certification_cost,0) as certification_cost,r.rate_field, 
            ifnull(rec.charge_value, 0) as charge_value, c.id_ret_category as catid ,
            IFNULL(tag.retail_max_wastage_percent,0) as wastage_percent , IFNULL(tag.tag_mc_value,'') as mc_value, tag.tag_mc_type as mc_type,tag.purity as purid,tag.calculation_based_on,IFNULL(tag.net_wt,0) as tag_net_wt
            FROM ret_taging tag 
            LEFT JOIN ret_product_master as pro ON pro.pro_id = tag.product_id
            LEFT JOIN ret_category c on c.id_ret_category = pro.cat_id
            LEFT JOIN metal mt on mt.id_metal=c.id_metal
            LEFT JOIN ret_design_master as des ON des.design_no = tag.design_id
            LEFT JOIN ret_purity as pur ON pur.id_purity = tag.purity
            LEFT JOIN ret_taxgroupmaster as txgrp ON txgrp.tgrp_id = mt.tgrp_id
            LEFT JOIN ret_metal_purity_rate r on r.id_metal=c.id_metal and r.id_purity=tag.purity
            LEFT JOIN (SELECT tag_id,sum(amount) as stn_amount,sum(certification_cost) as certification_cost,sum(wt) as stn_wt FROM `ret_taging_stone` GROUP by tag_id) as tag_stn_detail ON tag_stn_detail.tag_id = tag.tag_id
            LEFT JOIN (SELECT tag_id,sum(price) as othermat_amount,sum(wt) as othermat_wt FROM `ret_taging_other_materials` GROUP by tag_id) as tag_other_mat ON tag_other_mat.tag_id = tag.tag_id
            LEFT JOIN (SELECT tag_id, SUM(IFNULL(t.charge_value,0)) AS charge_value FROM ret_taging_charges t GROUP BY t.tag_id) AS rec ON rec.tag_id = tag.tag_id
    		WHERE ".(!empty($id_branch)? "tag.current_branch=".$id_branch."" :'')." 
    		".($tag_code!='' ? " and tag.tag_code ='".$tag_code."' " :'')." 
    		AND (tag.tag_status =0)
    		AND ifnull(tag.tag_type, 0) != 1 ");
		//print_r($this->db->last_query());exit;
		$item_details = $items_query->result_array();
		} 
		if(($billType == 2 || $billType == 3 || $billType == 4 || $billType == 5 || $billType == 8 || $billType == 9 ) && $estId!=''){
		$old_matel_query = $this->db->query("SELECT old_metal_sale_id,est_old.est_id, purchase_status,bill_id,
		id_category, type, item_type,IFNULL(gross_wt,0) as gross_wt,IFNULL(net_wt,0) as net_wt,met.id_metal,esti_no,
		ifnull(dust_wt,0.000) as dust_wt,ifnull(stone_wt,0.000) as stone_wt,
		round((ifnull(dust_wt,0.000) - ifnull(stone_wt,0.000)),3) as less_wt,purpose,
		if(type = 1, 'Melting', 'Retag') as reusetype,
		if(item_type = 1, 'Ornament', if(item_type = 2, 'Coin', if(item_type = 3, 'Bar',''))) as receiveditem, est_old.purity as purid, IFNULL(wastage_percent,0) as wastage_percent,
		IFNULL(wastage_wt,0) as wastage_wt, rate_per_gram, amount,
		pur.purity as purname, met.metal,ifnull(stn_detail.stn_price,0) as stone_price,concat(cus.firstname,' ',cus.mobile) as cus_name,cus.id_customer,
		v.village_name,if(cus.is_vip=1,'Yes','No') as vip,
		(select count(sa.id_scheme_account) from scheme_account sa left join customer cust on cust.id_customer=sa.id_customer) as accounts,
		IFNULL(est_old.purity,'') as purity,IFNULL(cus.pan,'') as pan_no,IFNULL(est_old.piece,0) as piece,est.esti_for, 0 as est_rate_per_grm,IFNULL(est.discount,0) as est_discount
		
		FROM ret_estimation as est
		LEFT JOIN ret_estimation_old_metal_sale_details as est_old ON est_old.est_id = est.estimation_id
		LEFT JOIN ret_purity as pur ON pur.id_purity = est_old.purity
		LEFT JOIN metal as met ON met.id_metal = est_old.id_category
		LEFT JOIN customer cus on cus.id_customer=est.cus_id
		LEFT JOIN village v on v.id_village=cus.id_village
		LEFT JOIN (SELECT est_id,sum(price) as stn_price,sum(wt) as stn_wgt FROM `ret_esti_old_metal_stone_details` GROUP by est_id) as stn_detail ON stn_detail.est_id = est_old.est_id
		WHERE ".(!empty($id_branch)? "est.id_branch=".$id_branch.' AND' :'')." 
		".($metal_type!='' ? " met.id_metal=".$metal_type." AND " :'')."
		est.esti_no ='".$estId."' AND old_metal_sale_id IS NOT NULL  and est_old.purchase_status=0 
		AND date(est.estimation_datetime)='".$dCData['entry_date']."'
		and est_old.purchase_status=0 
		order by old_metal_sale_id DESC");
		//echo $this->db->last_query(); exit;
		$old_matel_details = $old_matel_query->result_array();
		foreach ($old_matel_details as $metal) {
			$return_data['old_matel_details'][]=array(
					'amount'			=>$metal['amount'],
					'bill_id'			=>$metal['bill_id'],
					'dust_wt'			=>$metal['dust_wt'],
					'est_id'			=>$metal['est_id'],
					'piece'			    =>$metal['piece'],
					'esti_no'			=>$metal['esti_no'],
					'gross_wt'			=>$metal['gross_wt'],
					'net_wt'            =>$metal['net_wt'],
					'id_metal'          =>$metal['id_metal'],
					'id_category'		=>$metal['id_category'],
					'item_type'			=>$metal['item_type'],
					'less_wt'			=>$metal['less_wt'],
					'metal'				=>$metal['metal'],
					'old_metal_sale_id'	=>$metal['old_metal_sale_id'],
					'purchase_status'	=>$metal['purchase_status'],
					'purid'				=>$metal['purid'],
					'purname'			=>$metal['purname'],
					'purpose'			=>$metal['purpose'],
					'rate_per_gram'		=>$metal['rate_per_gram'],
					'receiveditem'		=>$metal['receiveditem'],
					'reusetype'			=>$metal['reusetype'],
					'stone_wt'			=>$metal['stone_wt'],
					'type'				=>$metal['type'],
					'wastage_percent'	=>$metal['wastage_percent'],
					'wastage_wt'		=>$metal['wastage_wt'],
					'stone_price'		=>$metal['stone_price'],
					'cus_name'		    =>$metal['cus_name'],
					'id_customer'		=>$metal['id_customer'],
					'chit_cus'		    =>($metal['accounts']==0 ? 'No':'Yes'),
					'vip_cus'		    =>$metal['vip'],
					'village_name'		=>$metal['village_name'],
					'purity'		    =>$metal['purity'],
					'pan_no'		    =>$metal['pan_no'],
					
					'esti_for'          =>$metal['esti_for'],
					'stone_details'		=>$this->get_old_metal_stone_details($metal['old_metal_sale_id']),
					
				);
		}
		}
		if(($billType == 5) && ($order_no!=''))
		{
		    $advance=$this->db->query("SELECT a.order_no,a.advance_amount as paid_advance,
			a.advance_weight as paid_weight,s.metal_type,a.store_as,a.advance_type,a.rate_calc,a.rate_per_gram,a.bill_id,a.bill_adv_id
			from ret_billing b
			LEFT JOIN ret_billing_advance a on a.bill_id=b.bill_id
			LEFT JOIN ret_bill_old_metal_sale_details s on s.old_metal_sale_id=a.old_metal_sale_id
			where a.is_adavnce_adjusted=0  and b.bill_status=1
			".($order_no!='' ? " and a.order_no='".$order_no."'" :'')."
			
			".($id_branch!='' ? " and b.id_branch='".$id_branch."'" :'')."
			");
			//print_r($this->db->last_query());exit;
			$return_data["advance_details"] = $advance->result_array();
		}else if($billType==9 && $estId!='')
		{
		    $advance_details=$this->db->query("SELECT a.order_no,a.advance_amount as paid_advance,a.advance_weight as paid_weight,s.metal_type,a.store_as,a.advance_type,a.rate_calc,a.rate_per_gram,a.bill_id,a.bill_adv_id
            FROM ret_billing_advance a 
            LEFT JOIN ret_billing b ON b.bill_id=a.bill_id
            LEFT JOIN customerorder c ON c.id_customerorder=a.id_customerorder
            LEFT JOIN customerorderdetails d ON d.id_customerorder=c.id_customerorder
            LEFT JOIN ret_estimation_items e ON e.id_orderdetails=d.id_orderdetails
            LEFT JOIN ret_bill_old_metal_sale_details s on s.old_metal_sale_id=a.old_metal_sale_id
            LEFT JOIN ret_estimation est ON est.estimation_id=e.esti_id
            where a.is_adavnce_adjusted=0 and b.bill_status=1
            and est.esti_no ='".$estId."' AND date(est.estimation_datetime)='".$dCData['entry_date']."'
            ".($id_branch!='' ? " and b.id_branch='".$id_branch."'" :'')."
            GROUP by a.bill_adv_id");
           // print_r($this->db->last_query());exit;
            $return_data["advance_details"] = $advance_details->result_array();
		}
		if(sizeof($item_details)>0)
		{
		foreach($item_details as $item)
		{
		 
			$return_data['item_details'][]=array(
						'calculation_based_on'=>$item['calculation_based_on'],
						'design_code'		  	=>$item['design_code'],
						'design_id'				=>($item['design_id']!=null ? $item['design_id'] :''),
						'design_name'			=>($item['design_name']!=null ? $item['design_name'] :''),
						'id_sub_design'			=>($item['id_sub_design']!=null ? $item['id_sub_design'] :''),
						'discount'				=>$item['discount'],
						'est_item_id'			=>($item['est_item_id']!=null ? $item['est_item_id']:''),
						'esti_id'				=>($item['esti_id']!=null ? $item['esti_id']:''),
						'esti_no'				=>($item['esti_no']!=null ? $item['esti_no']:''),
						'fixed_rate'			=>($item['fixed_rate']!=null ?$item['fixed_rate'] :''),
						'gross_wt'				=>($item['gross_wt']!=null ? $item['gross_wt']:''),
						'hsn_code'				=>($item['hsn_code']!=null ?$item['hsn_code'] :''),
						'is_partial'			=>($item['is_partial']!=null ? $item['is_partial']:''),
						'is_non_tag'			=>($item['is_non_tag']!=null ? $item['is_non_tag']:''),
						'item_cost'				=>($item['item_cost']!=null ? $item['item_cost']:''),
						'item_type'				=>($item['item_type']!=null ? $item['item_type']:''),
						'less_wt'				=>$item['less_wt'],
						'mc_type'				=>($item['mc_type']!=null ? $item['mc_type']:''),
						'mc_value'				=>($item['mc_value']!='' ? $item['mc_value']:''),
						'metal_type'			=>$item['metal_type'],
						'scheme_closure_benefit'=>$item['scheme_closure_benefit'],
						'net_wt'				=>$item['net_wt'],
						'tag_net_wt'			=>$item['tag_net_wt'],
						'othermat_amount'		=>($item['othermat_amount']!=null ? $item['othermat_amount']:0),
						'othermat_wt'			=>($item['othermat_wt']!=null ? $item['othermat_wt']:0),
						'stock_type'			=>($item['stock_type']!=null ? $item['stock_type']:''),
						'piece'					=>$item['piece'],
						'product_id'			=>$item['product_id'],
						'product_name'			=>$item['product_name'],
						'product_short_code'	=>$item['product_short_code'],
						'purchase_status'		=>$item['purchase_status'],
						'purid'					=>($item['purid']!=null ? $item['purid']:''),
						'purname'				=>$item['purname'],
						'size'					=>($item['size']!=null ? $item['size']:''),
						'stn_wgt'				=>($item['stn_wgt']!=null ? $item['stn_wgt']:''),
						'stone_price'			=>($item['stone_price']!=null ? $item['stone_price']:0),
						'certification_cost'	=>$item['certification_cost'],
						'tag_id'				=>($item['tag_id']!=null ? $item['tag_id'] :''),
						
						'tag_code'              =>($item['tag_code']!=null ? $item['tag_code']:''),
						'tax_group_id'			=>$item['tax_group_id'],
						'tgrp_name'				=>$item['tgrp_name'],
						'uom'					=>($item['uom']!=null ? $item['uom']:''),
						'wastage_percent'		=>($item['wastage_percent']!=null ? $item['wastage_percent']:''),
						'max_wastage'		    =>($item['max_wastage']!=null ? $item['max_wastage']:0),
						'min_wastage'		    =>$item['min_wastage'],
						'cus_name'		        =>$item['cus_name'],
					    'id_customer'		    =>$item['id_customer'],
					    'pan_no'		        =>$item['pan_no'],
					    'chit_cus'		        =>($item['accounts']==0 ? 'No':'Yes'),
					    'vip_cus'		        =>$item['vip'],
					    'village_name'		    =>($item['village_name']!=null ? $item['village_name']:''),
					    'available_pieces'		=>($item['available_pieces']!=null ?$item['available_pieces'] :''),
					    'available_gross_wt'	=>($item['available_gross_wt']!=null ? $item['available_gross_wt']:''),
					    'id_orderdetails'	    =>$item['id_orderdetails'],
					    'id_customerorder'	    =>$item['id_customerorder'],
					    'gift_applicable'		=>$item['gift_applicable'],
					    'rate_calc_from'		=>$item['rate_calc_from'],
					    'rate_field'            =>$item['rate_field'],
						'order_no'				=>($item['order_no']!=null ? $item['order_no'] :''),
						'stone_details'			=>($estId!='' && $item['est_item_id']!='' ? $this->get_stone_details($item['est_item_id']):$this->get_tag_stone_details($item['tag_id'])),
						
						'other_metal_details'	=>($item['tag_id']!='' ? $this->get_other_metal_details($item['tag_id']):[]),
						'charge_value'			=> $item['charge_value'],
						
						'est_rate_per_grm'		=> $item['est_rate_per_grm'],
						
						'est_discount'		    => $item['est_discount'],
						
						'esti_for'              =>($item['esti_for']!=null ? $item['esti_for']:''),
						'id_collecion_maping_det'=> '',
						'charges'				=> ($estId!='' && $item['est_item_id']!='' ? $this->get_other_estcharges($item['est_item_id']) : ($item['tag_id']!='' ? $this->get_charges($item['tag_id']) :'') ),
						'mc_va_limit'			=> ($item['product_id']!=''  && $item['design_id']!='' && $item['id_sub_design']!=''?  $this->get_mc_va_limit($item['product_id'], $item['design_id'], $item['id_sub_design']) :'')
					);
		}
		}
		
		if($item_details[0]['esti_id']!='')
		{
		    $return_data["packing_details"]=$this->get_EstimationPackingItems($item_details[0]['esti_id']);
		}
		
		$est_chit_query = $this->db->query("SELECT chit_ut_id, est_id,scheme_account_id, utl_amount,concat(s.code,'',sa.scheme_acc_number) as scheme_acc_number,s.scheme_type,sa.closing_balance,
		IFNULL(est_chit.closing_weight,0) as closing_weight,IFNULL(est_chit.wastage_per,0) as wastage_per,IFNULL(est_chit.savings_in_wastage,0) as savings_in_wastage,
		IFNULL(est_chit.mc_value,0) as mc_value,IFNULL(est_chit.savings_in_making_charge,0) as savings_in_making_charge,s.total_installments,pay.paid_installments,sa.closing_amount as closing_amount,
		s.is_wast_and_mc_benefit_apply,sa.additional_benefits,sa.closing_add_chgs
        FROM ret_est_chit_utilization as est_chit
        LEFT JOIN scheme_account sa ON sa.id_scheme_account=est_chit.scheme_account_id
        LEFT JOIN scheme s ON s.id_scheme=sa.id_scheme
        LEFT JOIN ret_estimation e ON e.estimation_id=est_chit.est_id 
        LEFT JOIN (select SUM(p.payment_amount) as paid_amount, IFNULL(cp.cash_pay,0) as cash_pay,sa.id_scheme_account,
        IFNULL(IF(sa.is_opening = 1,
        IFNULL(sa.paid_installments,0) + IFNULL(if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues)),0), 
        if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues))) ,0) as paid_installments 
        FROM payment p
        LEFT JOIN (SELECT SUM(IFNULL(pmd.payment_amount,0)) AS cash_pay, id_payment FROM `payment_mode_details` AS pmd WHERE pmd.payment_mode = 'CSH' AND pmd.payment_status = 1 GROUP BY id_payment) AS cp ON cp.id_payment = p.id_payment
        left join scheme_account sa on sa.id_scheme_account=p.id_scheme_account
        left join scheme s on s.id_scheme=sa.id_scheme
        where p.payment_status=1 GROUP BY p.id_scheme_account) as pay on pay.id_scheme_account = sa.id_scheme_account
        WHERE e.esti_no = '".$estId."' AND date(e.estimation_datetime)='".$dCData['entry_date']."'
        ".($id_branch!='' ? " and e.id_branch='".$id_branch."'" :'')."");
        //print_r($this->db->last_query());exit;
		$return_data["chit_details"] = $est_chit_query->result_array();
		
		/*$est_voucher_query = $this->db->query("SELECT gift_voucher_id, est_id,
		voucher_no, gift_voucher_details, est_vouch.gift_voucher_amt
		FROM ret_estimation as est
		LEFT JOIN ret_est_gift_voucher_details as est_vouch ON est_vouch.est_id = est.estimation_id
		WHERE est.estimation_id ='".$estId."' AND cus_id ='".$cusId."' AND voucher_no IS NOT NULL");
		$return_data["voucher_details"] = $est_voucher_query->result_array();
		$est_chit_query = $this->db->query("SELECT chit_ut_id, est_id,
		scheme_account_id, utl_amount
		FROM ret_estimation as est
		LEFT JOIN ret_est_chit_utilization as est_chit ON est_chit.est_id = est.estimation_id  
		WHERE est.estimation_id ='".$estId."' AND cus_id ='".$cusId."' AND scheme_account_id IS NOT NULL");
		$return_data["chit_details"] = $est_chit_query->result_array();*/
		$max_cash = $this->get_maxcash_settings();
		if($max_cash['validate_cash_amt'] == 1) {
			if(($billType == 9 && $estId !='') || ($billType == 5 && $order_no!='')) {
				$bill_ids = array();
				if($billType == 9 && $estId != '') {
					$sql = "SELECT 
								DISTINCT bill.bill_id
							FROM ret_estimation AS est 
							LEFT JOIN ret_estimation_items AS est_itms ON est_itms.esti_id = est.estimation_id 
							LEFT JOIN customerorderdetails AS cod ON cod.id_orderdetails = est_itms.id_orderdetails
							LEFT JOIN ret_billing_advance AS ba ON ba.id_customerorder = cod.id_customerorder
							LEFT JOIN ret_billing AS bill ON bill.bill_id = ba.bill_id
							WHERE esti_no = ".$estId." AND `est_date` = '".$dCData['entry_date']."' AND est_itms.id_orderdetails iS NOT NULL AND ba.advance_type = 1 AND bill.bill_status = 1";
					$query = $this->db->query($sql);
					$bill_ids = $query->result_array();
				} else if($billType == 5 && $order_no != '') {
					$sql = "SELECT
								DISTINCT ba.bill_id
							FROM ret_billing_advance AS ba
							LEFT JOIN ret_billing AS rb ON rb.bill_id = ba.bill_id 
							WHERE rb.bill_status = 1 AND ba.advance_type = 1 AND ba.order_no = '".$order_no."'
							".($id_branch!='' ? " AND rb.id_branch = '".$id_branch."'" :'')."";
					$query = $this->db->query($sql);
					$bill_ids = $query->result_array();
				}
				$where = "";
				$i = 1;
				foreach($bill_ids as $bill_id) {
					if($i == 1) {
						$where = $where." bp.bill_id = ".$bill_id['bill_id'];
					} else {
						$where = $where." OR bp.bill_id = ".$bill_id['bill_id'];
					}
					$i++;
				}
				if($where != "") {
					$where = " AND (".$where.")";
					$sql = "SELECT 
								IFNULL(SUM(bp.payment_amount),0) AS paid_cash
							FROM ret_billing_payment bp
							LEFT JOIN ret_billing bill ON bill.bill_id = bp.bill_id
							WHERE bp.payment_mode = 'Cash' AND bill.bill_status = 1 ".$where;
					$query = $this->db->query($sql);
					$q_res = $query->row_array();
					$cash_amt = round(($max_cash['max_cash_amt'] - $q_res['paid_cash']),2);
					$max_cash['max_cash_amt'] = $cash_amt;
				}
			}
		}
		$return_data['other_details'] = array(
											"max_cash" => $max_cash
										);
		return $return_data;
	} 
	
	function get_maxcash_settings() {
		$maxcash['max_cash_amt'] = $this->get_ret_settings('max_cash_amt');
		$maxcash['validate_cash_amt'] = $this->get_ret_settings('validate_cash_amt');
		return $maxcash;
	}
	
	function get_EstimationPackingItems($estId)
	{
	    $result=[];
	    if($estId!='')
	    {
	        $sql=$this->db->query("SELECT i.esti_id,i.id_other_item,i.no_of_piece,inv.name as item_name,IFNULL(inv.item_image,'') as item_image,inv.sku_id
            FROM ret_estimation_other_inventory_issue i 
            LEFT JOIN ret_other_inventory_item inv ON inv.id_other_item=i.id_other_item
            WHERE i.esti_id=".$estId."");
	        return $sql->result_array();
	    }
	    else
	    {
	        return $result;
	    }
	}
	
	function getEstimationDetailsTags($estId, $billType, $id_branch, $order_no, $fin_year, $metal_type)
	{
		
		$dCData=$this->getBranchDayClosingData($id_branch);
		
		$item_details=array();
		$order_details=array();
		$return_data = array("item_details" => array(),  'advance_details'=>array(), "order_details"=>array(), "order_sale_det"=>array());
		
		
		$items_query = $this->db->query("SELECT est_itms.est_item_id, esti_id, item_type, est_itms.purchase_status,est.esti_no,
		ifnull(est_itms.product_id, '') as product_id, ifnull(est_itms.tag_id, '') as tag_id, tag.tag_code, 
		ifnull(est_itms.design_id, '') as design_id, ifnull(pro.hsn_code,'') as hsn_code,
		est_itms.purity as purid, IFNULL(est_itms.size,'') as size, ifnull(est_itms.uom,'') as uom,IFNULL(est_itms.piece,'') as piece,
		ifnull(est_itms.less_wt,'') as less_wt,IFNULL(est_itms.net_wt,0) as net_wt,IFNULL(est_itms.gross_wt,0) as gross_wt,
		est_itms.calculation_based_on, IFNULL(est_itms.wastage_percent,0) as wastage_percent , IFNULL(est_itms.mc_value,'') as mc_value, est_itms.mc_type,
		tag.sales_value as item_cost, ifnull(product_short_code, '-') as product_short_code,
		ifnull(product_name, '-') as product_name, est_itms.is_partial,est_itms.discount,
		ifnull(design_code, '-') as design_code,
		ifnull(design_name, '') as design_name, pur.purity as purname,
		pro.tgrp_id as tax_group_id , tgrp_name, ifnull(c.id_metal,'') as metal_type,
		ifnull(des.fixed_rate,0) as fixed_rate,
		if(est_itms.id_orderdetails!='',ord.stn_amt,if(est_itms.tag_id != '',tag_stn_detail.stn_amount,stn_detail.stn_price)) as stone_price,
		IFNULL(tag_stn_detail.certification_cost,0) as certification_cost,
		if(est_itms.tag_id != null,stn_wgt,stn_wt) as stn_wgt,
		if(est_itms.tag_id != null,othermat_amount,other_mat_price) as othermat_amount,
		if(est_itms.tag_id != null,othermat_wt,other_mat_wgt) as othermat_wt,est_itms.is_non_tag,concat(cus.firstname,' ',cus.mobile) as cus_name,cus.id_customer,
		v.village_name,if(cus.is_vip=1,'Yes','No') as vip,
		(select count(sa.id_scheme_account) from scheme_account sa left join customer cust on cust.id_customer=sa.id_customer) as accounts,
		pro.min_wastage,pro.max_wastage,pro.stock_type,nt.no_of_piece as available_pieces,nt.gross_wt as available_gross_wt,IFNULL(est_itms.orderno,'') as order_no,
		
		ifnull(est_itms.id_orderdetails,'') as id_orderdetails,IFNULL(ord.id_customerorder,'') as id_customerorder,pro.gift_applicable,r.rate_field, 
		ifnull(rec.charge_value, 0) as charge_value,IFNULL(est_itms.item_cost,0) as est_cost
		FROM ret_estimation as est 
		LEFT JOIN ret_estimation_items as est_itms ON est_itms.esti_id = est.estimation_id
		
		left join ret_taging tag on tag.tag_id=est_itms.tag_id
		
		LEFT JOIN customerorderdetails as ord ON ord.id_orderdetails=est_itms.id_orderdetails 
		LEFT JOIN (SELECT est_item_id,sum(price) as stn_price,sum(wt) as stn_wgt FROM `ret_estimation_item_stones` GROUP by est_item_id) as stn_detail ON stn_detail.est_item_id = est_itms.est_item_id
		LEFT JOIN (SELECT est_item_id,sum(price) as other_mat_price,sum(price) as other_mat_wgt FROM `ret_estimation_item_other_materials` GROUP by est_item_id) as est_oth_mat ON est_oth_mat.est_item_id = est_itms.est_item_id
		LEFT JOIN (SELECT tag_id,sum(amount) as stn_amount,sum(certification_cost) as certification_cost,sum(wt) as stn_wt FROM `ret_taging_stone` GROUP by tag_id) as tag_stn_detail ON tag_stn_detail.tag_id = est_itms.tag_id
		LEFT JOIN (SELECT tag_id,sum(price) as othermat_amount,sum(wt) as othermat_wt FROM `ret_taging_other_materials` GROUP by tag_id) as tag_other_mat ON tag_other_mat.tag_id = est_itms.tag_id
		LEFT JOIN ret_product_master as pro ON pro.pro_id = est_itms.product_id
		LEFT JOIN ret_category c on c.id_ret_category = pro.cat_id
		LEFT JOIN metal mt on mt.id_metal=c.id_metal
		LEFT JOIN ret_design_master as des ON des.design_no = est_itms.design_id
		LEFT JOIN ret_purity as pur ON pur.id_purity = est_itms.purity
		LEFT JOIN ret_taxgroupmaster as txgrp ON txgrp.tgrp_id = pro.tgrp_id
		LEFT JOIN customer cus on cus.id_customer=est.cus_id
		LEFT JOIN village v on v.id_village=cus.id_village
		
		LEFT JOIN ret_metal_purity_rate r on r.id_metal=c.id_metal and r.id_purity=est_itms.purity
		LEFT JOIN (SELECT est_item_id, SUM(IFNULL(amount,0)) AS charge_value FROM ret_estimation_other_charges GROUP BY est_item_id) AS rec ON rec.est_item_id = est_itms.est_item_id
		LEFT JOIN ret_nontag_item nt on nt.design=est_itms.design_id ".($id_branch!='' ? " and nt.branch=".$id_branch."" :'')." 
		WHERE ".(!empty($id_branch)? "est.id_branch=".$id_branch."" :'')." 
		".($estId!='' ? " and est.esti_no ='".$estId."' " :'')." 
		".($metal_type!='' ? " and mt.id_metal=".$metal_type."" :'')."
		AND est_itms.est_item_id IS NOT NULL and est_itms.purchase_status=0
		AND date(est.estimation_datetime)='".$dCData['entry_date']."' 
		order by est_itms.esti_id DESC");
		// echo $this->db->_error_message(); exit;
		//print_r($this->db->last_query());exit;
		$item_details = $items_query->result_array();
	
		if(sizeof($item_details)>0)
		{
		    foreach($item_details as $item)
    		{
    			$return_data['item_details'][]=array(
    						'calculation_based_on'=>$item['calculation_based_on'],
    						'design_code'		  	=>$item['design_code'],
    						'design_id'				=>$item['design_id'],
    						'design_name'			=>$item['design_name'],
    						'discount'				=>$item['discount'],
    						'est_item_id'			=>($item['est_item_id']!=null ? $item['est_item_id']:''),
    						'esti_id'				=>($item['esti_id']!=null ? $item['esti_id']:''),
    						'tag_code'              => $item['tag_code'],
    						'esti_no'				=>($item['esti_no']!=null ? $item['esti_no']:''),
    						'fixed_rate'			=>($item['fixed_rate']!=null ?$item['fixed_rate'] :''),
    						'gross_wt'				=>($item['gross_wt']!=null ? $item['gross_wt']:''),
    						'hsn_code'				=>($item['hsn_code']!=null ?$item['hsn_code'] :''),
    						'is_partial'			=>($item['is_partial']!=null ? $item['is_partial']:''),
    						'is_non_tag'			=>($item['is_non_tag']!=null ? $item['is_non_tag']:''),
    						'item_cost'				=>($item['item_cost']!=null ? $item['item_cost']:''),
    						'item_type'				=>($item['item_type']!=null ? $item['item_type']:''),
    						'less_wt'				=>$item['less_wt'],
    						'mc_type'				=>($item['mc_type']!=null ? $item['mc_type']:''),
    						'mc_value'				=>($item['mc_value']!='' ? $item['mc_value']:''),
    						'metal_type'			=>$item['metal_type'],
    						'net_wt'				=>$item['net_wt'],
    						'othermat_amount'		=>($item['othermat_amount']!=null ? $item['othermat_amount']:0),
    						'othermat_wt'			=>($item['othermat_wt']!=null ? $item['othermat_wt']:0),
    						'stock_type'			=>($item['stock_type']!=null ? $item['stock_type']:''),
    						'piece'					=>$item['piece'],
    						'product_id'			=>$item['product_id'],
    						'product_name'			=>$item['product_name'],
    						'product_short_code'	=>$item['product_short_code'],
    						'purchase_status'		=>$item['purchase_status'],
    						'purid'					=>($item['purid']!=null ? $item['purid']:''),
    						'purname'				=>$item['purname'],
    						'size'					=>($item['size']!=null ? $item['size']:''),
    						'stn_wgt'				=>($item['stn_wgt']!=null ? $item['stn_wgt']:''),
    						'stone_price'			=>($item['stone_price']!=null ? $item['stone_price']:0),
    						'certification_cost'	=>$item['certification_cost'],
    						'tag_id'				=>$item['tag_id'],
    						'tax_group_id'			=>$item['tax_group_id'],
    						'tgrp_name'				=>$item['tgrp_name'],
    						'uom'					=>($item['uom']!=null ? $item['uom']:''),
    						'wastage_percent'		=>($item['wastage_percent']!=null ? $item['wastage_percent']:''),
    						'max_wastage'		    =>($item['max_wastage']!=null ? $item['max_wastage']:0),
    						'min_wastage'		    =>$item['min_wastage'],
    						'cus_name'		        =>$item['cus_name'],
    					    'id_customer'		    =>$item['id_customer'],
    					    'chit_cus'		        =>($item['accounts']==0 ? 'No':'Yes'),
    					    'vip_cus'		        =>$item['vip'],
    					    'village_name'		    =>($item['village_name']!=null ? $item['village_name']:''),
    					    'available_pieces'		=>($item['available_pieces']!=null ?$item['available_pieces'] :''),
    					    'available_gross_wt'	=>($item['available_gross_wt']!=null ? $item['available_gross_wt']:''),
    					    
    					    'id_orderdetails'	    =>$item['id_orderdetails'],
    					    
    					    'id_customerorder'	    =>$item['id_customerorder'],
    					    
    					    'gift_applicable'		=>$item['gift_applicable'],
    					    
    					    'rate_calc_from'		=>$item['rate_calc_from'],
    					    
    					    'rate_field'            =>$item['rate_field'],
    					    'est_cost'              =>$item['est_cost'],
    						'order_no'				=>isset($item['order_no'])?$item['order_no']:NULL,
    						'stone_details'			=>($item['tag_id']=='' ? $this->get_stone_details($item['est_item_id']):$this->get_tag_stone_details($item['tag_id'])),
    						'charge_value'			=> $item['charge_value'],
    						'charges'				=> $this->get_other_estcharges($item['est_item_id']),
    						
    						'other_metal_details'	=> ($item['tag_id']!='' ? $this->get_other_metal_details($item['tag_id']):[]) ,
    					);
    		}
		}
		/*$est_voucher_query = $this->db->query("SELECT gift_voucher_id, est_id,
		voucher_no, gift_voucher_details, est_vouch.gift_voucher_amt
		FROM ret_estimation as est
		LEFT JOIN ret_est_gift_voucher_details as est_vouch ON est_vouch.est_id = est.estimation_id
		WHERE est.estimation_id ='".$estId."' AND cus_id ='".$cusId."' AND voucher_no IS NOT NULL");
		$return_data["voucher_details"] = $est_voucher_query->result_array();
		$est_chit_query = $this->db->query("SELECT chit_ut_id, est_id,
		scheme_account_id, utl_amount
		FROM ret_estimation as est
		LEFT JOIN ret_est_chit_utilization as est_chit ON est_chit.est_id = est.estimation_id  
		WHERE est.estimation_id ='".$estId."' AND cus_id ='".$cusId."' AND scheme_account_id IS NOT NULL");
		$return_data["chit_details"] = $est_chit_query->result_array();*/
		return $return_data;
	} 
	function get_stone_details($est_item_id)
	{
			$est_stone_query=$this->db->query("SELECT est_item_stone_id,est_item_id, 
						   est_st.stone_id, pieces, wt, price as amount, 
                           stone_name, stone_code, uom_name, uom_short_code,is_apply_in_lwt , st.stone_type , stone_cal_type ,rate_per_gram,
                           um.uom_short_code
						   FROM ret_estimation_item_stones as est_st 
                           LEFT JOIN ret_stone as st ON st.stone_id = est_st.stone_id 
                           LEFT JOIN ret_uom as um ON um.uom_id = st.uom_id 
						   WHERE est_st.est_item_id = '".$est_item_id."'");
			return $est_stone_query->result_array();
	}
	
	function get_other_metal_details($tagid)
    {
         $data = $this->db->query("Select * from ret_tag_other_metals where tag_other_itm_tag_id = $tagid");
        return $data->result_array();
    }
    function get_tag_stone_details($tag_id)
    {
        $tag_stone_query=$this->db->query("SELECT s.tag_stone_id,s.tag_id,s.pieces,s.wt,s.amount,s.stone_id,s.certification_cost,s.rate_per_gram,st.stone_name,
        um.uom_short_code
        FROM ret_taging_stone as s 
        LEFT JOIN ret_stone st ON st.stone_id=s.stone_id
        LEFT JOIN ret_uom as um ON um.uom_id = st.uom_id 
        WHERE s.tag_id = '".$tag_id."'");
        return $tag_stone_query->result_array();
    }
	function get_old_metal_stone_details($old_metal_sale_id)
	{
			$est_stone_query=$this->db->query("SELECT est_old_metal_stone_id,est_id, 
						   est_st.stone_id, pieces, wt, price, 
                           stone_name, stone_code, uom_name, uom_short_code 
						   FROM ret_esti_old_metal_stone_details as est_st 
                           LEFT JOIN ret_stone as st ON st.stone_id = est_st.stone_id 
                           LEFT JOIN ret_uom as um ON um.uom_id = st.uom_id 
						   WHERE est_st.est_old_metal_sale_id = '".$old_metal_sale_id."'");
			return $est_stone_query->result_array();
	}
	function stone_details_by_bill_id($old_metal_sale_id)
	{
			$est_stone_query=$this->db->query("SELECT bill_item_stone_id,bill_id,bill_det_id,
						   bill_st.stone_id, bill_st.pieces, bill_st.wt, bill_st.price, 
                           stone_name, stone_code, uom_name, uom_short_code 
						   FROM ret_billing_item_stones as bill_st 
                           LEFT JOIN ret_stone as st ON st.stone_id = bill_st.stone_id 
                           LEFT JOIN ret_uom as um ON um.uom_id = st.uom_id 
						   WHERE bill_st.old_metal_sale_id = '".$old_metal_sale_id."'");
			//print_r($this->db->last_query());exit;
			return $est_stone_query->result_array();
	}
	function stone_details_by_bill_det_id($bill_det_id)
	{
			$est_stone_query=$this->db->query("SELECT bill_item_stone_id,bill_id,bill_det_id,
						   bill_st.stone_id, bill_st.pieces, bill_st.wt, bill_st.price, 
                           stone_name, stone_code, uom_name, uom_short_code 
						   FROM ret_billing_item_stones as bill_st 
                           LEFT JOIN ret_stone as st ON st.stone_id = bill_st.stone_id 
                           LEFT JOIN ret_uom as um ON um.uom_id = st.uom_id 
						   WHERE bill_st.bill_det_id = '".$bill_det_id."'");
			return $est_stone_query->result_array();
	}
	function getAllTaxgroupItems(){
		$return_data = array();
		$taxitems = $this->db->query("SELECT tgi_tgrpcode, tgrp_name, tgi_calculation, tgi_type, tax_percentage 
									FROM ret_taxgroupitems as tx_grp_itm 
									LEFT JOIN ret_taxgroupmaster as grp ON grp.tgrp_id = tx_grp_itm.tgi_tgrpcode 
									LEFT JOIN ret_taxmaster as tx ON tx.tax_id = tx_grp_itm.tgi_taxcode");
		if($taxitems->num_rows() > 0){
			$return_data = $taxitems->result_array();
		}
		return $return_data;
	}
	function getAvailableCustomers($SearchTxt){
		$data = $this->db->query("SELECT id_customer as value, concat(firstname,'-',username) as label, reference_no, id_branch, id_village, title, initials, lastname, firstname, date_of_birth, date_of_wed, gender, id_address, id_employee, email, mobile, phone, nominee_name, nominee_relationship, nominee_mobile, cus_img, pan, pan_proof, ispan_req, voterid, voterid_proof, rationcard, rationcard_proof, comments, username, passwd, profile_complete, active, is_new, date_add, custom_entry_date, date_upd, added_by, notification, gst_number, cus_ref_code, is_refbenefit_crt_cus, emp_ref_code, is_refbenefit_crt_emp, religion, kyc_status FROM customer 
			WHERE username like '%".$SearchTxt."%' OR mobile like '%".$SearchTxt."%' OR firstname like '%".$SearchTxt."%'");
		return $data->result_array();
	}
	function getTaggingBySearch($SearchTxt){
		$bill_type=(isset($_POST['bill_type']) ? $_POST['bill_type']:'');
		
		$return_data=array();
		$tag = $this->db->query("SELECT tag.tag_id, 
				tag_code, tag_datetime, tag.tag_type, tag_lot_id, ifnull(pro.hsn_code,'') as hsn_code, 
				ifnull(tag.design_id,'') as design_id, cost_center, tag.purity, ifnull(tag.size,'') as size, ifnull(uom,'')uom, piece, tag.less_wt, IFNULL(tag.net_wt,0) as net_wt ,IFNULL(tag.gross_wt,0) as gross_wt, ifnull(tag.less_wt,0) as less_wt,
				tag.calculation_based_on, IFNULL(retail_max_wastage_percent,0) as retail_max_wastage_percent, tag_mc_type,retail_max_mc,IFNULL(tag_mc_value,0) as tag_mc_value,
				halmarking, sales_value, pro.tgrp_id, tag.tag_status, product_name, product_short_code, lot_product, pur.purity as purname, ifnull(c.id_metal,'') as metal_type,
				tgrp_name, ifnull(design_code, '-') as design_code, 
				ifnull(design_name, '') as design_name,
				stn_amount,stn_wt,othermat_amount,othermat_wt,pro.disc_type,
				IFNULL(l.order_no,'') as order_no 				
				FROM ret_taging as tag 
				Left join ret_lot_inwards_detail ld on tag.id_lot_inward_detail = ld.id_lot_inward_detail
				LEFT JOIN ret_lot_inwards l on l.lot_no=ld.lot_no					
				LEFT JOIN ret_product_master as pro ON pro.pro_id = ld.lot_product  
                LEFT JOIN ret_category c on c.id_ret_category=pro.cat_id
				LEFT JOIN metal mt on mt.id_metal=c.id_metal 
				LEFT JOIN ret_design_master as des ON des.design_no = tag.design_id 
				LEFT JOIN ret_taxgroupmaster as txgrp ON txgrp.tgrp_id = pro.tgrp_id 
				LEFT JOIN ret_purity as pur ON pur.id_purity = tag.purity 
				LEFT JOIN (SELECT tag_id,sum(amount) as stn_amount,sum(wt) as stn_wt FROM `ret_taging_stone` GROUP by tag_id) as tag_stn_detail ON tag_stn_detail.tag_id = tag.tag_id
				LEFT JOIN (SELECT tag_id,sum(price) as othermat_amount,sum(wt) as othermat_wt FROM `ret_taging_other_materials` GROUP by tag_id) as tag_other_mat ON tag_other_mat.tag_id = tag.tag_id
				WHERE tag.tag_status=0  and tag.id_orderdetails is NULL and tag.tag_code = '".$SearchTxt."' ".($_POST['id_branch']!='' ? "and tag.current_branch=".$_POST['id_branch']."" :'')." ");
				//echo $this->db->last_query();exit;
			$tag_items=$tag->result_array();
			foreach ($tag_items as $item) {
				$return_data[]=array(
						'calculation_based_on'=>$item['calculation_based_on'],
						'cost_center'			=>$item['cost_center'],
						'design_code'		  	=>$item['design_code'],
						'design_id'				=>$item['design_id'],
						'design_name'			=>$item['design_name'],
						'gross_wt'				=>$item['gross_wt'],
						'halmarking'			=>$item['halmarking'],
						'hsn_code'				=>$item['hsn_code'],
						'less_wt'				=>$item['less_wt'],
						'lot_product'			=>$item['lot_product'],
						'net_wt'				=>$item['net_wt'],
						'othermat_amount'		=>$item['othermat_amount'],
						'metal_type'			=>$item['metal_type'],
						'othermat_wt'			=>$item['othermat_wt'],
						'piece'					=>$item['piece'],
						'product_name'			=>$item['product_name'],
						'product_short_code'	=>$item['product_short_code'],
						'purid'					=>$item['purity'],
						'purname'				=>$item['purname'],
						'retail_max_mc'			=>$item['retail_max_mc'],
						'retail_max_wastage_percent'			=>$item['retail_max_wastage_percent'],
						'sales_value'			=>$item['sales_value'],
						'size'					=>$item['size'],
						'stn_amount'			=>$item['stn_amount'],
						'stn_wt'				=>$item['stn_wt'],
						'tag_code'				=>$item['tag_code'],
						'tag_datetime'			=>$item['tag_datetime'],
						'tag_id'				=>$item['tag_id'],
						'tag_lot_id'			=>$item['tag_lot_id'],
						'tag_mc_type'			=>$item['tag_mc_type'],
						'tag_mc_value'			=>$item['tag_mc_value'],
						'tag_status'			=>$item['tag_status'],
						'tag_type'				=>$item['tag_type'],
						'tgrp_id'				=>$item['tgrp_id'],
						'tgrp_name'				=>$item['tgrp_name'],
						'uom'					=>$item['uom'],
						'disc_type'		        =>$item['disc_type'],
						'order_no'		        =>$item['order_no'],
						'stone_details'			=>$this->get_tag_stone_details($item['tag_id'])
					);
			}
		return $return_data;
	}
	function getProductBySearch($SearchTxt){
		$data = $this->db->query("SELECT pro_id as value, 
				product_short_code as label, product_name,
				wastage_type, other_materials, has_stone, 
				has_hook, has_screw, has_fixed_price,
				has_size, less_stone_wt, no_of_pieces, calculation_based_on   
				FROM ret_product_master as pro 
				WHERE product_short_code LIKE '%".$SearchTxt."%' OR product_name LIKE '%".$SearchTxt."%'");
		return $data->result_array();
	}
	function getProductDesignBySearch($SearchTxt, $procode){
		$where = empty($procode) ? "WHERE " : "WHERE product_id =$procode AND ";
		$data = $this->db->query("SELECT design_no as value, 
				design_code as label, design_name,
				min_length, max_length, min_width, max_width,
				min_dia, max_dia,
				min_weight, max_weight, fixed_rate 
				FROM ret_design_master as des 
				".$where." design_code LIKE '%".$SearchTxt."%'");
		return $data->result_array();
	}
	function getMetalTypes(){
		$query = $this->db->query("SELECT id_metal, metal FROM metal");
		return $query->result_array();
	}
	function getUOMDetails()
	{
		$sql = $this->db->query("SELECT * FROM ret_uom where uom_status = 1");
		return $sql->result_array();
	}
	function get_currentBranchName($branch_id){
		$branch_name = "";
		$branch_query = $this->db->query("SELECT id_branch, name FROM branch WHERE id_branch = $branch_id");
		if($branch_query->num_rows() > 0){
			$branch_name = $branch_query->row()->name;
		}
		return $branch_name;
	}
	function get_currentBranches($record_id){
		$record_id = ($record_id==NULL) ? -1 : $record_id;
		$strData="<option value='' ";
		$strData.=$record_id==-1 ? "selected='selected'" : "" ;
		$strData.=">- SELECT -</option>";
		$resultset=$this->db->query("SELECT id_branch, name FROM branch WHERE active = 1 ORDER BY name");
		foreach ($resultset->result() as $row)
		{
		   $strData.= "<option value='".$row->id_branch."' ";
		   $strData.=($record_id==$row->id_branch) ? "selected='selected'" : "" ;
		   $strData.=">".$row->name."</option>";
		}
		$resultset->free_result();
		return $strData;
	}
	
	function code_number_generator($id_branch,$metal_type)
	{
		$lastno = $this->get_last_code_no($id_branch,$metal_type);
		
		if($lastno!=NULL)
		{
		    $code_det       = explode('-',$lastno);
		    $LastBillNo      = $code_det[1];
			$number = (int) $LastBillNo;
			$number++;
			$code_number = str_pad($number, 5, '0', STR_PAD_LEFT);			
			return $code_number;
		}
		else
		{
			$code_number = str_pad('1', 5, '0', STR_PAD_LEFT);
			return $code_number;
		}
	}
	function get_last_code_no($id_branch,$metal_type)
    {
        $fin_year = $this->get_FinancialYear();
		$sql = "SELECT (bill_no) as lastBill_no 
		FROM ret_billing 
		where fin_year_code=".$fin_year['fin_year_code']."
		".($id_branch!='' && $id_branch>0 ? " and id_branch=".$id_branch."" :'')."
		
		".($metal_type!='' ? " and metal_type=".$metal_type."" :'')."
		ORDER BY bill_id DESC LIMIT 1";
		
		//print_r($sql);exit;
		return $this->db->query($sql)->row()->lastBill_no;	
	}
	
	function get_payModes()
    {
		$sql = "SELECT * FROM payment_mode where show_in_pay = 1 ORDER BY sort_order";
		return $this->db->query($sql)->result_array();	
	} 
	//chit account
	function get_closed_accounts($SearchTxt,$id_customer){
		$data = $this->db->query("SELECT c.mobile, sa.id_scheme_account as value, sa.id_scheme_account as label, 
		
		sa.closing_balance, sa.is_closed, s.scheme_type, s.scheme_name, ifnull(s.flexible_sch_type, 0) as flexible_sch_type, 
		sa.closing_amount as closing_amount, sa.closing_add_chgs, pay.paid_installments, s.total_installments, s.firstPayDisc_value, s.scheme_type,
		
		IFNULL(pay.paid_amount,0) as paid_amount,
		IFNULL(sa.additional_benefits,0) as additional_benefits, IFNULL(sa.benefit,0) as sch_benefit, IFNULL(pay.cash_pay, 0) as cash_pay,s.is_wast_and_mc_benefit_apply
				from scheme_account sa
				left join customer c on c.id_customer=sa.id_customer
				left join scheme s on s.id_scheme = sa.id_scheme
				LEFT JOIN (select SUM(p.payment_amount) as paid_amount, IFNULL(cp.cash_pay,0) as cash_pay,sa.id_scheme_account,
				
				IFNULL(IF(sa.is_opening = 1,
				            IFNULL(sa.paid_installments,0) + IFNULL(if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues)),0), 
				            if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues))) ,0) as paid_installments 
				FROM payment p
				LEFT JOIN (SELECT SUM(IFNULL(pmd.payment_amount,0)) AS cash_pay, id_payment FROM `payment_mode_details` AS pmd WHERE pmd.payment_mode = 'CSH' AND pmd.payment_status = 1 GROUP BY id_payment) AS cp ON cp.id_payment = p.id_payment
				left join scheme_account sa on sa.id_scheme_account=p.id_scheme_account
				left join scheme s on s.id_scheme=sa.id_scheme
				where p.payment_status=1 GROUP BY p.id_scheme_account) as pay on pay.id_scheme_account = sa.id_scheme_account
				WHERE sa.id_scheme_account LIKE '%".$SearchTxt."%' and sa.is_closed = 1 and sa.is_utilized = 0
				".($id_customer!='' ? " and sa.id_customer=".$id_customer."" :'')."
				");
		//echo $this->db->last_query();exit;
		return $data->result_array();
	}
	//Adv Adj
	
   function get_advance_details($bill_cus_id)
	{
        $data=$this->db->query("SELECT ir.amount as total_amount, IFNULL(irp.cash_pay,0) AS total_cash_pay, (ir.amount-IFNULL(advance.amount,0)-IFNULL(chit_adj.chit_utilized_amount,0)-IFNULL(advance_adjusted.amount,0)-IFNULL(refund.refund_amount,0))as amount,ir.id_issue_receipt,ir.bill_no, (IFNULL(irp.cash_pay,0)-IFNULL(advance.amount,0)-IFNULL(chit_adj.chit_utilized_amount,0)-IFNULL(advance_adjusted.amount,0)-IFNULL(refund.refund_amount,0))as cash_pay
        from ret_issue_receipt ir
        
        left join (select sum(u.utilized_amt) as amount,ir.id_issue_receipt 
                    from ret_issue_receipt as ir 
                    left JOIN ret_advance_utilized as u on u.id_issue_receipt=ir.id_issue_receipt 
                    LEFT JOIN ret_billing bill on bill.bill_id=u.bill_id
                    where bill.bill_status=1
                    GROUP by ir.id_issue_receipt) as advance on advance.id_issue_receipt=ir.id_issue_receipt
		LEFT JOIN (SELECT SUM(IFNULL(payment_amount,0)) AS cash_pay, id_issue_rcpt FROM ret_issue_rcpt_payment WHERE payment_mode = 'Cash' AND payment_status = 1 GROUP BY id_issue_rcpt) AS irp ON irp.id_issue_rcpt = ir.id_issue_receipt
        left join (select sum(u.utilized_amt) as chit_utilized_amount,ir.id_issue_receipt 
                    from ret_issue_receipt as ir 
                    left JOIN ret_advance_utilized as u on u.id_issue_receipt=ir.id_issue_receipt 
                    LEFT JOIN payment p on p.id_payment=u.id_payment
                    where p.payment_status=1
                    GROUP by ir.id_issue_receipt) as chit_adj on chit_adj.id_issue_receipt=ir.id_issue_receipt
        
         left join (select sum(adj.adjusted_amt) as amount,adj.receipt_for 
                    FROM ret_issue_receipt_advance_adj adj
                    LEFT JOIN ret_issue_receipt ir ON ir.id_issue_receipt=adj.id_issue_receipt
                    where ir.bill_status=1
                    GROUP by adj.receipt_for) as advance_adjusted on advance_adjusted.receipt_for=ir.id_issue_receipt
        
        LEFT JOIN (select a.refund_receipt,IFNULL(SUM(a.refund_amount),0) as refund_amount
                   From ret_advance_refund a
                   LEFT JOIN ret_issue_receipt r on r.id_issue_receipt=a.id_issue_receipt
                   Where r.bill_status=1
                   group by a.refund_receipt) as refund on refund.refund_receipt=ir.id_issue_receipt
        where ir.id_customer=".$bill_cus_id." and ir.type=2 and ir.bill_status=1 AND (ir.receipt_type=2 or ir.receipt_type=3 or ir.receipt_type=4 or ir.receipt_type=5)
        group by ir.id_issue_receipt
        Having amount>0");
        //print_r($this->db->last_query());exit;
        return $data->result_array();
	}
	
	//Adv Adj
		function getBillData($bill_no, $billType, $id_branch,$fin_year,$metal_type)
		{
		// 1-Sales, 2-Sales&Purchase, 3-Sales,purchase&Return, 4-Purchase, 5-Order Advance, 6-Advance,7-Sales Return
		$return_data = array("item_details" => array(), "old_matel_details" => array(), "bill_details" => array(),"due_amount"=>0,"paid_amount"=>0);
		$items_query = $this->db->query("SELECT bill_items.bill_det_id, bill.bill_id, bill_items.item_type,bill_items.status,
		ifnull(bill_items.product_id, '') as product_id, ifnull(bill_items.tag_id, '') as tag_id, bill_items.esti_item_id,esti_id ,
		ifnull(bill_items.design_id, '') as design_id, ifnull(pro.hsn_code,'') as hsn_code,
		bill_items.uom, bill_items.piece,IFNULL(concat(s.value ,s.name),'-')as size,
		ifnull(bill_items.less_wt,'') as less_wt, bill_items.net_wt, bill_items.gross_wt,
		bill_items.calculation_based_on, bill_items.wastage_percent, bill_items.mc_value, bill_items.mc_type,
		bill_items.item_cost, ifnull(product_short_code, '-') as product_short_code,
		ifnull(product_name, '-') as product_name, bill_items.is_partial_sale,bill_items.bill_discount as discount,bill_items.item_total_tax,
		ifnull(design_code, '-') as design_code,
		ifnull(design_name, '') as design_name, pur.purity as purname,
		pro.tgrp_id as tax_group_id , tgrp_name, ifnull(c.id_metal,'') as metal_type,
		ifnull(des.fixed_rate,0) as fixed_rate,
		if(bill_items.tag_id != null,stn_price,stn_amount) as stone_price,
		if(bill_items.tag_id != null,stn_wgt,stn_wt) as stn_wgt,
		if(bill_items.tag_id != null,othermat_amount,other_mat_price) as othermat_amount,
		if(bill_items.tag_id != null,othermat_wt,other_mat_wgt) as othermat_wt,concat(cus.firstname,' ',cus.mobile) as cus_name,v.village_name,
		if(cus.is_vip=1,'Yes','No') as vip,
		(select count(sa.id_scheme_account) from scheme_account sa left join customer cust on cust.id_customer=sa.id_customer) as accounts,cus.id_customer
		FROM ret_billing as bill
		LEFT JOIN ret_bill_details as bill_items ON bill_items.bill_id = bill.bill_id
		LEFT JOIN (SELECT esti_id,est_item_id from ret_estimation_items where bil_detail_id is not null and purchase_status=1) as est_itms ON est_itms.est_item_id = bill_items.esti_item_id
		LEFT JOIN (SELECT bill_det_id,sum(price) as stn_price,sum(wt) as stn_wgt FROM `ret_billing_item_stones` GROUP by bill_det_id) as stn_detail ON stn_detail.bill_det_id = bill_items.bill_det_id
		LEFT JOIN (SELECT bill_det_id,sum(price) as other_mat_price,sum(price) as other_mat_wgt FROM `ret_billing_item_other_materials` GROUP by bill_det_id) as est_oth_mat ON est_oth_mat.bill_det_id = bill_items.bill_det_id
		LEFT JOIN (SELECT tag_id,sum(amount) as stn_amount,sum(wt) as stn_wt FROM `ret_taging_stone` GROUP by tag_id) as tag_stn_detail ON tag_stn_detail.tag_id = bill_items.tag_id
		LEFT JOIN (SELECT tag_id,sum(price) as othermat_amount,sum(wt) as othermat_wt FROM `ret_taging_other_materials` GROUP by tag_id) as tag_other_mat ON tag_other_mat.tag_id = bill_items.tag_id
		LEFT JOIN ret_product_master as pro ON pro.pro_id = bill_items.product_id
		LEFT JOIN ret_category c on c.id_ret_category = pro.cat_id
		LEFT JOIN metal mt on mt.id_metal=c.id_metal
		LEFT JOIN ret_design_master as des ON des.design_no = bill_items.design_id
		LEFT JOIN ret_taxgroupmaster as txgrp ON txgrp.tgrp_id = pro.tgrp_id
		LEFT JOIN customer cus on cus.id_customer=bill.bill_cus_id
		LEFT JOIN village v on v.id_village=cus.id_village
		LEFT JOIN ret_purity pur   ON pur.id_purity = bill_items.purity
		LEFT JOIN ret_size s   ON s.id_size = bill_items.size
		WHERE ".(!empty($id_branch)? "bill.id_branch=".$id_branch.' AND' :'')." bill.bill_no ='".$bill_no."'  AND bill_items.bill_det_id IS NOT NULL and bill.bill_status=1
		".($metal_type!='' ? " and mt.id_metal=".$metal_type."" :'')."
		and bill.fin_year_code=".$fin_year." ");
    	//print_r($this->db->last_query());exit;
		$return_data["item_details"] = $items_query->result_array();
		
		$billing_details = $this->db->query("SELECT (b.tot_bill_amount-b.tot_amt_received) as due_amount,b.tot_amt_received,b.bill_id
        FROM ret_billing b 
        WHERE b.is_credit = 1 and b.bill_status = 1 and b.credit_status = 2
        ".(!empty($id_branch)? " and b.id_branch=".$id_branch.' AND' :'')." b.bill_no ='".$bill_no."'
        and b.fin_year_code=".$fin_year." ");
        
        $result = $billing_details->row_array();
        
        if($result)
        {
            $credit_paid_amt = $this->get_credit_pay_amount($result['bill_id']);
        
		    $return_data["due_amount"] = $result['due_amount']-$credit_paid_amt;
        }
        
        $pay_sql = $this->db->query("SELECT IFNULL(SUM(pay.payment_amount),0) as paid_amount
        FROM ret_billing_payment pay 
        LEFT JOIN ret_billing b ON b.bill_id = pay.bill_id
        WHERE b.bill_status = 1
        ".(!empty($id_branch)? " and b.id_branch=".$id_branch.' AND' :'')." b.bill_no ='".$bill_no."'
        and b.fin_year_code=".$fin_year." ");
        $return_data['paid_amount']=$pay_sql->row()->paid_amount;
        
		return $return_data;
		}
		
		function getreturnBillData($bill_no, $billType, $id_branch)
		{
		$bill=str_replace(',',' OR bill.bill_no=','bill.bill_no='.$bill_no);
		//print_r($bill);exit;
		// 1-Sales, 2-Sales&Purchase, 3-Sales,purchase&Return, 4-Purchase, 5-Order Advance, 6-Advance,7-Sales Return
		$return_data = array("item_details" => array(), "old_matel_details" => array(), "bill_details" => array());
		$items_query = $this->db->query("SELECT bill_items.bill_det_id, bill.bill_id, bill_items.item_type,bill_items.status,
		ifnull(bill_items.product_id, '') as product_id, ifnull(bill_items.tag_id, '') as tag_id,IFNULL(bill_items.esti_item_id,'') as esti_item_id,esti_id ,
		ifnull(bill_items.design_id, '') as design_id, ifnull(pro.hsn_code,'') as hsn_code,
		bill_items.size, bill_items.uom, bill_items.piece,
		ifnull(bill_items.less_wt,'') as less_wt, bill_items.net_wt, bill_items.gross_wt,
		bill_items.calculation_based_on, bill_items.wastage_percent, bill_items.mc_value, bill_items.mc_type,
		bill_items.item_cost, ifnull(product_short_code, '-') as product_short_code,
		ifnull(product_name, '-') as product_name, bill_items.is_partial_sale,bill_items.bill_discount as discount,bill_items.item_total_tax,
		ifnull(design_code, '-') as design_code,
		ifnull(design_name, '') as design_name, purity as purname,
		pro.tgrp_id as tax_group_id , tgrp_name, ifnull(c.id_metal,'') as metal_type,
		ifnull(des.fixed_rate,0) as fixed_rate,
		if(bill_items.tag_id != null,stn_price,stn_amount) as stone_price,
		if(bill_items.tag_id != null,stn_wgt,stn_wt) as stn_wgt,
		if(bill_items.tag_id != null,othermat_amount,other_mat_price) as othermat_amount,
		if(bill_items.tag_id != null,othermat_wt,other_mat_wgt) as othermat_wt,concat(cus.firstname,' ',cus.mobile) as cus_name,v.village_name,
		if(cus.is_vip=1,'Yes','No') as vip,
		(select count(sa.id_scheme_account) from scheme_account sa left join customer cust on cust.id_customer=sa.id_customer) as accounts,
		bill_items.total_sgst,total_igst,total_cgst
		FROM ret_billing as bill
		LEFT JOIN ret_bill_details as bill_items ON bill_items.bill_id = bill.bill_id
		LEFT JOIN (SELECT esti_id,est_item_id from ret_estimation_items where bil_detail_id is not null and purchase_status=1) as est_itms ON est_itms.est_item_id = bill_items.esti_item_id
		LEFT JOIN (SELECT bill_det_id,sum(price) as stn_price,sum(wt) as stn_wgt FROM `ret_billing_item_stones` GROUP by bill_det_id) as stn_detail ON stn_detail.bill_det_id = bill_items.bill_det_id
		LEFT JOIN (SELECT bill_det_id,sum(price) as other_mat_price,sum(price) as other_mat_wgt FROM `ret_billing_item_other_materials` GROUP by bill_det_id) as est_oth_mat ON est_oth_mat.bill_det_id = bill_items.bill_det_id
		LEFT JOIN (SELECT tag_id,sum(amount) as stn_amount,sum(wt) as stn_wt FROM `ret_taging_stone` GROUP by tag_id) as tag_stn_detail ON tag_stn_detail.tag_id = bill_items.tag_id
		LEFT JOIN (SELECT tag_id,sum(price) as othermat_amount,sum(wt) as othermat_wt FROM `ret_taging_other_materials` GROUP by tag_id) as tag_other_mat ON tag_other_mat.tag_id = bill_items.tag_id
		LEFT JOIN ret_product_master as pro ON pro.pro_id = bill_items.product_id
		LEFT JOIN ret_category c on c.id_ret_category = pro.cat_id
		LEFT JOIN metal mt on mt.id_metal=c.id_metal
		LEFT JOIN ret_design_master as des ON des.design_no = bill_items.design_id
		LEFT JOIN ret_taxgroupmaster as txgrp ON txgrp.tgrp_id = pro.tgrp_id
		LEFT JOIN customer cus on cus.id_customer=bill.bill_cus_id
		LEFT JOIN village v on v.id_village=cus.id_village
		WHERE ".(!empty($id_branch)? "bill.id_branch=".$id_branch.' AND' :'')." (".$bill.")  AND bill_items.bill_det_id IS NOT NULL");
    	print_r($this->db->last_query());exit;
		$return_data["item_details"] = $items_query->result_array();
		return $return_data;
		}
		
		function getCreditBillDetails($bill_no,$bill_type,$id_branch,$fin_year_code)
		{
            $return_data = array("bill_details" => array());
            $items_query = $this->db->query("Select b.bill_id,b.tot_bill_amount,b.tot_amt_received,b.bill_cus_id as id_customer,
            concat(cus.firstname,' ',cus.mobile) as cus_name,v.village_name,
            if(cus.is_vip=1,'Yes','No') as vip,
            (select count(sa.id_scheme_account) from scheme_account sa left join customer cust on cust.id_customer=sa.id_customer) as accounts,
            IFNULL(cus.pan,'') as pan_no,IFNULL(ret.credit_ret_amt,0) as credit_ret_amt
            from ret_billing b
            LEFT JOIN customer cus on cus.id_customer=b.bill_cus_id
		    LEFT JOIN village v on v.id_village=cus.id_village
		    
		    LEFT JOIN(SELECT IFNULL(SUM(b.credit_ret_amt),0) as credit_ret_amt,r.ret_bill_id
                     FROM ret_bill_return_details r 
                     LEFT JOIN ret_billing b ON b.bill_id = r.bill_id
                     WHERE b.bill_status = 1
                     GROUP BY r.ret_bill_id) as ret ON ret.ret_bill_id = b.bill_id
            where b.is_credit=1 AND b.bill_status = 1 and b.bill_type!=12 and b.credit_status=2 and b.bill_no='".$bill_no."' and b.bill_type!=8 and b.fin_year_code=".$fin_year_code."
            
            ".($id_branch!='' ? " and b.id_branch=".$id_branch."" :'')."");
            
           // print_r($this->db->last_query());exit;
            $return_data['bill_details']= $items_query->row_array();
            if(!empty($return_data['bill_details']))
            {
                
                    $paid_amount        =  $this->get_credit_pay_amount($return_data["bill_details"]['bill_id']);
                    $old_metal_amount   =  $this->get_credit_old_metal_amount($return_data["bill_details"]['bill_id'],8); //8-Credit collection
                    $return_data['bill_details']['credit_pay_amount']=$paid_amount+$old_metal_amount;
            }
            return $return_data;
		}
		
		function get_credit_old_metal_amount($bill_id)
		{
            $old_metal_query=$this->db->query("SELECT IFNULL(sum(s.rate),0) as amount
            FROM ret_billing b 
            LEFT join  ret_bill_old_metal_sale_details s on s.bill_id=b.bill_id
            where b.bill_status=1 and b.ref_bill_id=".$bill_id."");
            return $old_metal_query->row()->amount;
		}
		
		function get_credit_pay_amount($bill_id)
		{
		    $sql="SELECT IFNULL(sum(tot_amt_received+credit_disc_amt),0) as paid_amount from ret_billing b where b.ref_bill_id=".$bill_id." and b.bill_status=1";
            return $this->db->query($sql)->row()->paid_amount;
		}
		
		function get_BillAmount($bill_id)
		{
		    $sql="SELECT b.tot_bill_amount,b.tot_amt_received from ret_billing b where b.bill_id=".$bill_id."";
            return $this->db->query($sql)->row_array();
		}
		
		function max_metalrate()
       {
          $is_branchwise_rate=$this->session->userdata('is_branchwise_rate');
          $id_branch=$this->session->userdata('id_branch');
       	  $sql="select m.goldrate_22ct,m.silverrate_1gm from  metal_rates m".($is_branchwise_rate==1 && $id_branch!='' ? " left join branch_rate br on br.id_metalrate=m.id_metalrates where br.id_branch=".$id_branch."":'')."";
       	 // print_r($sql);exit;
       	  return $this->db->query($sql)->row_array();
       }
       
       function getBilling_details($from_date,$to_date,$id_branch,$bill_cus_id,$bill_type)
    	{
    		$items_query = $this->db->query("SELECT b.bill_no,c.mobile,c.firstname,b.tot_bill_amount
    		FROM ret_billing b
    		LEFT JOIN customer c on c.id_customer=b.bill_cus_id
    		where  b.bill_cus_id=".$bill_cus_id." and b.id_branch=".$id_branch." and date(b.bill_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."' and b.tot_bill_amount>0".($bill_type==8 ? " and b.is_credit=1 and b.credit_status=2" :" and b.bill_type=1")."");
    		//print_r($this->db->last_query());exit;
    		return $items_query->result_array();
    	}
	function getCompanyDetails($id_branch)
	{
		if($id_branch=='')
		{
			$sql = $this->db->query("Select  c.id_company,c.company_name,c.gst_number,c.short_code,c.pincode,c.mobile,c.whatsapp_no,c.phone,c.email,c.website,c.address1,c.address2,c.id_country,c.id_state,c.id_city,ct.name as city,s.name as state,cy.name as country,cs.currency_symbol,cs.currency_name,cs.mob_code,cs.mob_no_len,c.mail_server,c.mail_password,c.send_through,c.mobile1,c.phone1,c.smtp_user,c.smtp_pass,c.smtp_host,c.server_type,cs.login_branch,
			s.state_code
			from company c
			join chit_settings cs
			left join country cy on (c.id_country=cy.id_country)
			left join state s on (c.id_state=s.id_state)
			left join city ct on (c.id_city=ct.id_city)");
		}
		else
		{
			$sql=$this->db->query("select b.name,b.address1,b.address2,c.company_name,
				cy.name as country,ct.name as city,s.name as state,b.pincode,s.id_state,s.state_code,cy.id_country
				from branch b
				join company c
				left join country cy on (b.id_country=cy.id_country)
				left join state s on (b.id_state=s.id_state)
				left join city ct on (b.id_city=ct.id_city)
				where b.id_branch=".$id_branch."");
		}
		$result = $sql->row_array();
		return $result;
	}
	
	
	// issue and receipt
	function get_account_head()
	{
		$sql=$this->db->query("SELECT a.name,a.id_acc_head
		 FROM ret_account_head a where a.status=1");
		 return  $sql->result_array();
	}
	function get_borrower_details($SearchTxt,$id_branch,$issue_to)
	{
        $return_data=array();
		if($issue_to==1)
		{
			$data=$this->db->query("select e.id_employee as value,concat(e.firstname,'-',e.mobile) as label,e.mobile,e.firstname as barrower_name, e.mobile as mobile 
			from employee e
			where e.mobile like '%".$SearchTxt."%' OR e.firstname like '%".$SearchTxt."%' ".($id_branch!=''  && $id_branch>0 ? " and e.id_branch=".$id_branch."":'')."");
			
			 $return_data=$data->result_array();
		}
		else{
			$data=$this->db->query("select c.id_customer as value,concat(c.firstname,'-',c.mobile) as label,c.mobile,c.firstname as barrower_name,w.id_ret_wallet, 
			c.mobile as mobile,IFNULL(c.pan,'') as pan
			From customer c
			LEFT JOIN  ret_wallet w on w.id_customer=c.id_customer
			where c.mobile like '%".$SearchTxt."%' OR c.firstname like '%".$SearchTxt."%'
			group by c.id_customer");
			
			 $cus_details=$data->result_array();
			 
			 foreach($cus_details as $cus)
			 {
			     $return_data[]=array(
			                          'value'           =>$cus['value'],
			                          'label'           =>$cus['label'],
			                          'barrower_name'   =>$cus['barrower_name'],
			                          'id_ret_wallet'   =>$cus['id_ret_wallet'],
			                          'mobile'          =>$cus['mobile'],
			                          'pan'             =>$cus['pan'],
			                          'wallet_det'      =>$this->get_cus_advance_details($cus['value']),
			                          'advance_details' =>$this->get_receipt_refund($cus['value']),
			                       );
			 }
		}
		 return $return_data;
	}
	
	function get_cus_advance_details($bill_cus_id)
	{
        $data=$this->db->query("SELECT w.amount as amount,w.id_ret_wallet 
        from ret_wallet w
        where w.id_customer=".$bill_cus_id."
        Having amount>0");
        //print_r($this->db->last_query());exit;
        return $data->row_array();
	}
	
	//advance refund
    function get_receipt_refund($bill_cus_id)
	{
		$sql = $this->db->query("SELECT (ir.amount-IFNULL(advance.amount,0)-IFNULL(refund.refund_amount,0)-IFNULL(chit_adj.chit_utilized_amount,0)-IFNULL(advance_adjusted.amount,0))as amount,
		ir.id_issue_receipt,ir.bill_no
        from ret_issue_receipt ir
        
        left join (select sum(u.utilized_amt) as amount,ir.id_issue_receipt 
                    from ret_issue_receipt as ir 
                    left JOIN ret_advance_utilized as u on u.id_issue_receipt=ir.id_issue_receipt 
                    LEFT JOIN ret_billing bill on bill.bill_id=u.bill_id
                    where bill.bill_status=1
                    GROUP by ir.id_issue_receipt) as advance on advance.id_issue_receipt=ir.id_issue_receipt
        
        left join (select sum(u.utilized_amt) as chit_utilized_amount,ir.id_issue_receipt 
                    from ret_issue_receipt as ir 
                    left JOIN ret_advance_utilized as u on u.id_issue_receipt=ir.id_issue_receipt 
                    LEFT JOIN payment p on p.id_payment=u.id_payment
                    where p.payment_status=1
                    GROUP by ir.id_issue_receipt) as chit_adj on chit_adj.id_issue_receipt=ir.id_issue_receipt
        
         left join (select sum(adj.adjusted_amt) as amount,adj.receipt_for 
                    FROM ret_issue_receipt_advance_adj adj
                    LEFT JOIN ret_issue_receipt ir ON ir.id_issue_receipt=adj.id_issue_receipt
                    where ir.bill_status=1
                    GROUP by adj.receipt_for) as advance_adjusted on advance_adjusted.receipt_for=ir.id_issue_receipt
        
        LEFT JOIN (select a.refund_receipt,IFNULL(SUM(a.refund_amount),0) as refund_amount
                   From ret_advance_refund a
                   LEFT JOIN ret_issue_receipt r on r.id_issue_receipt=a.id_issue_receipt
                   Where r.bill_status=1
                   group by a.refund_receipt) as refund on refund.refund_receipt=ir.id_issue_receipt
        
        where ir.id_customer=".$bill_cus_id. " and ir.bill_status=1 AND (ir.receipt_type=2 or ir.receipt_type=3 or ir.receipt_type=4 or ir.receipt_type=5)
        Having amount>0");
		//print_r($this->db->last_query());exit;
		return $sql->result_array();
	}
    //advance refund
	
	function getCreditBill($searchTxt,$id_branch)
	{
		$data=$this->db->query("SELECT r.bill_no as label,r.id_issue_receipt as value,r.issue_to,r.id_customer,r.id_employee,r.amount,
			(Select ifnull(sum(rct.amount),0)  FROM ret_issue_receipt rct where rct.receipt_for=".$searchTxt.") as paid_amount
			FROM ret_issue_receipt r
			where r.is_collect=0 and (r.bill_no LIKE '%".$searchTxt."%' OR r.id_issue_receipt LIKE '%".$searchTxt."%') ".($id_branch!='' ? "and r.id_branch=".$id_branch." " :'')."");
			//print_r($this->db->last_query());exit;
		return  $data->result_array();
	}
	function get_retWallet_details($id_customer)
	{
		$data=$this->db->query("SELECT id_ret_wallet,id_customer FROM ret_wallet w where w.id_customer=".$id_customer."");
		if($data->num_rows()>0)
		{
			return array('status'=>TRUE,'id_ret_wallet'=>$data->row('id_ret_wallet'));
		}else{
			return array('status'=>FALSE,'id_ret_wallet'=>'');
		}
	}
	
	function ajax_getReceiptlist($data)
	{
	    //echo "<pre>";print_r($data);exit;
		if($_POST['dt_range'] != '')
        {
            $dateRange = explode('-',$_POST['dt_range']);
            $from = str_replace('/','-',$dateRange[0]);
            $to = str_replace('/','-',$dateRange[1]);  
            $d1 = date_create($from);
            $d2 = date_create($to);
            $FromDt = date_format($d1,"Y-m-d");
            $ToDt = date_format($d2,"Y-m-d");
        }
		$sql=$this->db->query("SELECT r.bill_no,b.name,if(r.type=1,'Issue','Receipt') as type,IFNULL(e.firstname,'-') as emp_name,IFNULL(c.firstname,'-') as cus_name,IFNULL(r.amount,0) as amount,IFNULL(adv.utilized_amt,0) as utilized_amt,r.bill_status as status,
			date_format(r.bill_date,'%d-%m-%Y') as date_add,r.issue_to,r.id_issue_receipt,IFNULL(r.weight,0) as weight,IF(r.bill_status=1,'Success','Cancelled') as bill_status,if(date(d.entry_date)=date(r.bill_date),'1','0') as allow_cancel,IF(r.amount = adv.utilized_amt,'1','0') as current_status,
			
			r.receipt_type,IFNULL(refund.refund_amount,0) as refund_amount
			FROM ret_issue_receipt r
			left join ret_account_head a on r.issue_to=a.id_acc_head
			LEFT join customer c on c.id_customer=r.id_customer
			LEFT JOIN employee e on e.id_employee=r.id_employee
			LEFT JOIN ret_day_closing d on d.id_branch=r.id_branch
			LEFT JOIN branch b on b.id_branch=r.id_branch
            LEFT JOIN (SELECT b.bill_id,sum(IFNULL(adv.utilized_amt,0)) utilized_amt,adv.id_issue_receipt FROM ret_billing b
                LEFT JOIN ret_advance_utilized adv ON adv.bill_id=b.bill_id
                WHERE b.bill_status=1 AND adv.bill_id is NOT null
                GROUP BY adv.id_issue_receipt) adv ON adv.id_issue_receipt=r.id_issue_receipt
            
            LEFT JOIN (select a.refund_receipt,IFNULL(SUM(a.refund_amount),0) as refund_amount
                   From ret_advance_refund a
                   LEFT JOIN ret_issue_receipt r on r.id_issue_receipt=a.id_issue_receipt
                   Where r.bill_status=1
                   group by a.refund_receipt) as refund on refund.refund_receipt=r.id_issue_receipt
			where r.type=2 
			".($FromDt != '' ? 'and date(r.bill_date) BETWEEN "'.$FromDt.'" AND "'.$ToDt.'"' : '')."
            ".($data['id_branch']!='' && $data['id_branch']!= 0 ? " and r.id_branch=".$data['id_branch']."" :'')." 
            ");
		//print_r($this->db->last_query());exit;
		return  $sql->result_array();
	}
	
	function ajax_getIssuetist($data)
	{
		if($_POST['dt_range'] != '')
        {
            $dateRange = explode('-',$_POST['dt_range']);
            $from = str_replace('/','-',$dateRange[0]);
            $to = str_replace('/','-',$dateRange[1]);  
            $d1 = date_create($from);
            $d2 = date_create($to);
            $FromDt = date_format($d1,"Y-m-d");
            $ToDt = date_format($d2,"Y-m-d");
        }
		$data=$this->db->query("SELECT if(r.type=1,'Issue','Receipt') as type,
			IFNULL(r.amount,0) as amount,if(r.issue_to=1,e.firstname,if(r.issue_to=2,c.firstname,a.name)) as barrower_name,
			date_format(r.created_on,'%d-%m-%Y') as date_add,r.issue_to,r.id_issue_receipt,
			b.name,r.bill_no,IFNULL(r.narration,'') as narration,
			if(r.issue_type=1,'Petty Cash',if(r.issue_type=2,'Credit Issue',if(r.issue_type=3,'Advance Refund',if(r.issue_type=4,'Existing Out Standing','')))) as issue_type,
			if(date(d.entry_date)=date(r.bill_date),'1','0') as allow_cancel,r.bill_status
			FROM ret_issue_receipt r
			left join ret_account_head a on r.id_acc_head=a.id_acc_head
			LEFT join customer c on c.id_customer=r.id_customer
			LEFT JOIN employee e on e.id_employee=r.id_employee
			LEFT JOIN branch b on b.id_branch=r.id_branch
			
			LEFT JOIN ret_day_closing d on d.id_branch=r.id_branch
			where r.type=1
			".($FromDt != '' ? 'and date(r.bill_date) BETWEEN "'.$FromDt.'" AND "'.$ToDt.'"' : '')."
            ".( $data['id_branch']!='' && $data['id_branch']!= 0? " and r.id_branch=".$data['id_branch']."" :'')." ;");
		return  $data->result_array();
	}
	
	function get_issue_details($id)
	{
		$data=$this->db->query("SELECT r.id_issue_receipt,if(r.type=1,'Issue','Receipt') as issue_type,c.mobile,r.issue_to,r.id_customer,r.id_employee,r.issue_type,r.amount,r.weight,r.narration,
		if(r.issue_to=1,e.firstname,if(r.issue_to=2,c.firstname,a.name)) as name,if(r.issue_type=1,'Petty Cash Issue',if(r.issue_type=3,'Advance Refund','Issue')) as receipt_type,
		IFNULL(r.receipt_as,1) as receipt_as,r.bill_no,date_format(r.bill_date,'%d-%m-%Y') as date_add,r.id_branch,e.emp_code,r.id_branch,
		IFNULL(addr.address1,'') as address1,IFNULL(addr.address2,'') as address2,IFNULL(addr.address3,'') as address3,IFNULL(addr.pincode,'') as pincode,
		ct.name as country_name,s.name as state_name,ct.name as city_name,s.name as cus_state,e.firstname as emp_name,IFNULL(r.narration,'') as narration,r.type
			FROM ret_issue_receipt r
			LEFT JOIN customer c on c.id_customer=r.id_customer
			
			LEFT JOIN address addr on addr.id_customer=c.id_customer
		    
		    LEFT JOIN city ct on addr.id_city=ct.id_city
		    LEFT JOIN state s on s.id_state=addr.id_state
		    left join country cy on (addr.id_country=cy.id_country)
		    
			LEFT JOIN employee e on e.id_employee=r.id_employee
			left join ret_account_head a on r.id_acc_head=a.id_acc_head
			WHERE r.type=1 and r.id_issue_receipt=".$id."");
			//print_r($this->db->last_query());exit;
		return $data->row_array();
	}
	
	function get_receipt_advance_details($id)
	{
	    $sql  = $this->db->query("SELECT date_format(r.bill_date,'%d-%m-%Y') as bill_date,r.bill_no,r.amount as receipt_amt,IFNULL(advance.utilized_amt,0) as utilized_amt,IFNULL(refund.refund_amount,0) as refund_amount,
        (r.amount-IFNULL(advance.utilized_amt,0)-IFNULL(refund.refund_amount,0)) as balance_amount
        FROM ret_advance_refund adv
        LEFT JOIN ret_issue_receipt r ON r.id_issue_receipt = adv.refund_receipt
        LEFT JOIN (select sum(u.utilized_amt) as utilized_amt,ir.id_issue_receipt 
        	from ret_issue_receipt as ir 
        	left JOIN ret_advance_utilized as u on u.id_issue_receipt=ir.id_issue_receipt 
        	LEFT JOIN ret_billing bill on bill.bill_id=u.bill_id
        	where bill.bill_status=1
        	GROUP by ir.id_issue_receipt) as advance on advance.id_issue_receipt=adv.refund_receipt
        LEFT JOIN (select a.refund_receipt,IFNULL(SUM(a.refund_amount),0) as refund_amount
        	From ret_advance_refund a
        	LEFT JOIN ret_issue_receipt r on r.id_issue_receipt=a.id_issue_receipt
        	Where r.bill_status=1 and r.issue_type=3
        	group by a.refund_receipt) as refund on refund.refund_receipt=adv.refund_receipt
         WHERE adv.id_issue_receipt = ".$id."");
         return $sql->result_array();
	}
	
	function get_receipt_details($id)
	{
		$sql=$this->db->query("SELECT r.id_customer,r.id_employee,r.issue_type,r.amount,
			r.weight,c.firstname as name,c.mobile,r.narration,r.id_issue_receipt,if(r.receipt_type=1,'Credit Collection Receipt',if(r.receipt_type=2 or r.receipt_type=3 or r.receipt_type=5,'Advance Receipt','Issue Receipt')) as receipt_type,
			r.receipt_type as rct_type,r.receipt_as as rct_as,r.receipt_for,IFNULL(r.receipt_as,1) as receipt_as,r.weight,r.id_branch,r.bill_no,b.name as branch_name,b.short_name,f.fin_year_code,date_format(r.bill_date,'%d-%m-%Y') as date_add,
			IFNULL(a.address1,'') as address1,IFNULL(a.address2,'') as address2,IFNULL(a.address3,'') as address3,IFNULL(a.pincode,'') as pincode,
			ct.name as country_name,s.name as state_name,ct.name as city_name,s.name as cus_state,IFNULL(bill.bill_no,'') as ref_no,e.emp_code,e.firstname as emp_name,r.type
			FROM ret_issue_receipt r
			LEFT JOIN ret_billing bill on bill.bill_id = r.deposit_bill_id
			LEFT JOIN customer c on c.id_customer=r.id_customer
		    LEFT JOIN address a on a.id_customer=c.id_customer
		    
		    LEFT JOIN city ct on a.id_city=ct.id_city
		    LEFT JOIN state s on s.id_state=a.id_state
		    left join country cy on (a.id_country=cy.id_country)
		
			LEFT JOIN employee e on e.id_employee=r.created_by
			LEFT JOIN branch b on b.id_branch=r.id_branch
			LEFT JOIN ret_financial_year f on f.fin_status = 1
			
			WHERE r.type=2 and r.id_issue_receipt=".$id."");
			
		$data=$sql->row_array();
		/*if($data['rct_type']==1)
		{
		   $issue=$this->get_issue_details($data['receipt_for']);
		   $data['mobile']=$issue['mobile'];
		   $data['name']=$issue['name'];
		}*/
		return $data;
	}
	
	function get_receipt_advance_adj_details($id)
	{
	    $sql=$this->db->query("SELECT * FROM `ret_issue_receipt_advance_adj` WHERE id_issue_receipt=".$id."");
	    return $sql->result_array();
	}
	
	function get_est_adv_details($id)
	{
	    $sql=$this->db->query("SELECT * FROM `ret_adv_receipt_weight` WHERE id_issue_receipt=".$id."");
	    return $sql->result_array();
	}
	
	function get_est_adv_tag_details($id)
	{
	    $sql=$this->db->query("SELECT * FROM `ret_adv_receipt_tags` WHERE id_issue_receipt=".$id."");
	    return $sql->result_array();
	}
	
	function get_receipt_payment($id)
	{
		$pay_details = array("pay_details" => array());
		$items_query = $this->db->query("SELECT p.id_issue_rcpt_pay,p.id_issue_rcpt,p.payment_amount,p.card_no,p.cvv,p.payment_mode
		FROM ret_issue_rcpt_payment p
		where p.id_issue_rcpt=".$id."");
		$data=$items_query->result_array();
		return $data;
	}
	
	// issue and receipt
	
	function get_bill_detail($bill_id)
	{
		$items_query = $this->db->query("SELECT b.bill_det_id,IFNULL(b.tag_id,'') as tag_id,bill.bill_type,
			b.piece as no_of_piece,b.gross_wt as gross_wt,b.net_wt as net_wt,bill.id_branch,b.product_id,b.design_id,tag.current_branch
			FROM ret_billing bill
			LEFT JOIN ret_bill_details b on b.bill_id=bill.bill_id
			
			LEFT JOIN ret_taging tag on tag.tag_id = b.tag_id
			where b.bill_id=".$bill_id."");
		return $items_query->result_array();
	}
	
	function checkNonTagItemExist($data){
		$r = array("status" => FALSE);
		$id_sub_design = (isset($data['id_sub_design']) ? ($data['id_sub_design']!='' ? $data['id_sub_design'] :'') :'');
        $sql = "SELECT id_nontag_item FROM ret_nontag_item WHERE product=".$data['id_product']." 
        ".($data['id_design']!='' ? " and design=".$data['id_design']."" :'')."
        ".($id_sub_design!='' ? " and id_sub_design=".$id_sub_design."" :'')."
        AND branch=".$data['id_branch']; 		$res = $this->db->query($sql);
		if($res->num_rows() > 0){
			$r = array("status" => TRUE, "id_nontag_item" => $res->row()->id_nontag_item); 
		}else{
			$r = array("status" => FALSE, "id_nontag_item" => ""); 
		} 
		return $r;
	}
	function updateNTData($data,$arith){ 
		$sql = "UPDATE ret_nontag_item SET no_of_piece=(no_of_piece".$arith." ".$data['no_of_piece']."),gross_wt=(gross_wt".$arith." ".$data['gross_wt']."),net_wt=(net_wt".$arith." ".$data['net_wt']."),updated_by=".$data['updated_by'].",updated_on='".$data['updated_on']."' WHERE id_nontag_item=".$data['id_nontag_item'];  
		$status = $this->db->query($sql);
		return $status;
	}
	
	function no_to_words($no="")
 	{
		$nos = explode('.', $no);
		$val1="";
		$val2="";
		$val="";
		if(isset($nos[0]))
		{
			$val1=$this->no_to_words1($nos[0]);
			$val=$val1." Rupees";
		}
		if(isset($nos[1]) && $nos[1] != 0)
		{
			$val2=$this->no_to_words1($nos[1]);
			if(isset($val2))
			$val=$val1." Rupees and"." ".$val2." Paisa";
		}
		return $val;
	}
   
   	function no_to_words1($nos1="")
 	{
		$words = array('0'=> '' ,'1'=> 'One' ,'2'=> 'Two' ,'3' => 'Three','4' => 'Four','5' => 'Five','6' => 'Six','7' => 'Seven','8' => 'Eight','9' => 'Nine','10' => 'Ten','11' => 'Eleven','12' => 'Twelve','13' => 'Thirteen','14' => 'Fouteen','15' => 'Fifteen','16' => 'Sixteen','17' => 'Seventeen','18' => 'Eighteen','19' => 'Nineteen','20' => 'Twenty','30' => 'Thirty','40' => 'Fourty','50' => 'Fifty','60' => 'Sixty','70' => 'Seventy','80' => 'Eighty','90' => 'Ninty','100' => 'Hundred &','1000' => 'Thousand','100000' => 'Lakh','10000000' => 'Crore');
	$nos[0] = $nos1;
	if($nos[0] == 0 )
        return '';
    else {           
			$novalue='';
			$highno=$nos[0];
			$remainno=0;
			$value=100;
			$value1=1000;
			$temp='';   
			
            while($nos[0]>=100)   
			 { 
                if(($value <= $nos[0]) &&($nos[0]  < $value1))   
				{
                	$novalue=$words["$value"];
                	$highno = (int)($nos[0]/$value);
                	$remainno = $nos[0] % $value;
                	break;
                }
                $value= $value1;
                $value1 = $value * 100;
            }       
          if(array_key_exists("$highno",$words))
		  {
			  return $words["$highno"]." ".$novalue." ". $this-> no_to_words1($remainno);
		  }
          else 
		  {
             $unit=$highno%10;
             $ten =(int)($highno/10)*10;       
             return $words["$ten"]." ".$words["$unit"]." ".$novalue." ". $this->no_to_words1($remainno);
          }
    	
		}
	}
	function advance_details_order_no($orderno)
	{
	    $return_data=array();
	    if($orderno!=null && $orderno!='')
	    {
	       $advance=$this->db->query("SELECT a.order_no,a.advance_amount as paid_advance,
			a.advance_weight as paid_weight,s.metal_type,a.store_as,a.advance_type,a.rate_calc,a.rate_per_gram
			from ret_billing b
			LEFT JOIN ret_billing_advance a on a.bill_id=b.bill_id
			LEFT JOIN ret_estimation_items est on est.orderno=a.order_no
			LEFT JOIN ret_estimation e on e.estimation_id=est.esti_id
			LEFT JOIN ret_bill_old_metal_sale_details s on s.old_metal_sale_id=a.old_metal_sale_id
			where a.is_adavnce_adjusted=0 and a.order_no='".$orderno."'");
	       //print_r($this->db->last_query());exit;
			$return_data=$advance->result_array();
	    }
	    return $return_data;
	}
	
	function get_partial_sale_det($tag_id)
	{
	    $status=true;
	    $partial= $this->db->query("SELECT * FROM ret_partlysold WHERE tag_id='".$tag_id."'");
		if($partial->num_rows()>0){
		  $status=$this->updateData(array('status'=>0),'tag_id',$tag_id, 'ret_partlysold');
		}
		return $status;
	}
	 function get_tag_details($tag_id)
    {
    	$sql=$this->db->query("SELECT t.tag_id,t.gross_wt,t.net_wt,IFNULL(p.sold_nwt,0) as sold_nwt
        FROM ret_taging t 
        LEFT JOIN(SELECT SUM(s.sold_gross_wt) as sold_gwt,SUM(s.sold_net_wt) as sold_nwt,s.tag_id
        FROM ret_partlysold s 
        LEFT JOIN ret_bill_details d ON d.bill_det_id=s.sold_bill_det_id
        LEFT JOIN ret_billing b ON b.bill_id=d.bill_id
        WHERE b.bill_status=1 and s.tag_id=".$tag_id."
        GROUP by d.tag_id) as p ON p.tag_id=t.tag_id
        WHERE t.tag_id=".$tag_id."
        GROUP by t.tag_id");
		//print_r($this->db->last_query());exit;
		return $sql->row_array();
    }
    
    function bill_no_generate($id_branch)
	{
		$lastno = $this->get_max_bill_no($id_branch);
		if($lastno!=NULL)
		{
			$number = (int) $lastno;
			$number++;
			$code_number = str_pad($number, 5, '0', STR_PAD_LEFT);			
			return $code_number;
		}
		else
		{
			$code_number = str_pad('1', 5, '0', STR_PAD_LEFT);
			return $code_number;
		}
	}
	function get_max_bill_no($id_branch)
    {
        $fin_year = $this->get_FinancialYear();
		$sql = "SELECT max(bill_no) as lastBill_no FROM ret_issue_receipt where fin_year_code=".$fin_year['fin_year_code']." ".($id_branch!='' && $id_branch>0 ? " and id_branch=".$id_branch."" :'')." ORDER BY id_issue_receipt DESC";
		return $this->db->query($sql)->row()->lastBill_no;	
	}
	
	function getVoucherDetails($id_branch,$id_cus,$code)
	{
	    $data=array();
	    $responseData=array();
        $sql=$this->db->query("SELECT g.id_gift_card,IFNULL(g.bill_id,'') as bill_id,g.code as label,g.id_gift_card as value,g.free_card,
        g.amount,g.weight,DATE_FORMAT(g.valid_to,'%Y-%m-%d') as valid_to,IFNULL(g.id_gift_voucher,'') as id_gift_voucher,IFNULL(g.id_set_gift_voucher,'') as id_set_gift_voucher,g.status,IFNULL(g.id_branch,'') as id_branch
        FROM gift_card g
        WHERE g.code like '%".$code."%' ");
        
        $gift=$sql->result_array();
        
        if(sizeof($gift)>0)
        {
            foreach($gift as $items)
            {
                    $from_date = strtotime(date("Y-m-d")); 
                    $to_date = strtotime($items['valid_to']);
                    if($from_date>$to_date && $to_date!=null)
                    {
                        $gift_status['status']=3;
                        $this->updateData($gift_status,'id_gift_card',$items['id_gift_card'],'gift_card');
                        $status=3;
                    }else{
                        $status=$items['status'];
                    }
                    
                     $responseData[]=array(
                        'id_gift_card'  =>$items['id_gift_card'],
                        'bill_id'       =>$items['bill_id'],
                        'label'         =>$items['label'],
                        'value'         =>$items['value'],
                        'weight'        =>$items['weight'],
                        'amount'         =>$items['amount'],
                        'valid_to'      =>$items['valid_to'],
                        'free_card'     =>$items['free_card'],
                        'id_branch'     =>$items['id_branch'],
                        'status'        =>$status,
                        'id_gift_voucher' =>$items['id_gift_voucher'],
                        'id_set_gift_voucher'=>$items['id_set_gift_voucher'],
                        'gift_redeem_det'=>($items['id_set_gift_voucher']!='' ? $this->get_purchase_voucher($items['id_set_gift_voucher']):$this->gift_voucher_master($items['id_gift_voucher'])),
                        );
                        
                    $data=array('status'=>TRUE,'responseData'=>$responseData);
            }
        }
        else{
             $data=array('status'=>false,'message'=>'Invalid Voucher Code..');
        }
        
        return $data;
	}
	
    function gift_voucher_master($id_gift_voucher)
    {
        
        $data=array();
        if($id_gift_voucher!='')
        {
            $sql = $this->db->query("select * FROM ret_gift_voucher_master where id_gift_voucher=".$id_gift_voucher);
           
		    $data=$sql->row_array();
        }
		return $data;
	}
	
	 function get_purchase_voucher($id_set_gift_voucher)
    {
        
        $data=array();
        if($id_set_gift_voucher!='')
        {
            $sql = $this->db->query("select * FROM ret_bill_gift_voucher_settings where id_set_gift_voucher=".$id_set_gift_voucher);
           
		    $data=$sql->row_array();
        }
		return $data;
	}
	
	function CheckRedeemProduct($id)
	{
	    $sql=$this->db->query("select s.id_gift_voucher,s.id_product,s.issue,s.utilize from ret_gift_issue_redeem_prod s where utilize=1 and id_gift_voucher=".$id);
	    return $sql->result_array();
	}
	
	function GeneralGiftRedeemProduct($id)
	{
	    $sql=$this->db->query("select s.id_gift_voucher,s.id_product,s.issue,s.utilize from ret_gift_master_redeem_prod s where utilize=1 and id_gift_voucher=".$id);
	    return $sql->result_array();
	}
	
    
     function ret_bill_return_details($bill_id)
    {
        $sql=$this->db->query("select * from ret_bill_return_details where bill_id=".$bill_id."");
        return $sql->result_array();
    }
    
    function get_redeem_details($id)
    {
        $sql=$this->db->query("SELECT g.bill_id,d.bill_gift_voucher_id
        FROM ret_billing_gift_voucher_details d
        LEFT JOIN gift_card g on g.adjusted_bill_id=d.bill_gift_voucher_id
        where d.bill_id=".$id."");
       
        if($sql->num_rows()>0)
        {
            $data=$sql->row_array();
            $this->updateData(array('status'=>0,'adjusted_bill_id'=>NULL),'adjusted_bill_id',$data['bill_gift_voucher_id'], 'gift_card');
        }
        return true;
    }
    
    function get_gift_issue_details($bill_id)
    {
        $sql=$this->db->query("SELECT g.bill_id FROM gift_card g WHERE g.bill_id=".$bill_id."");
       
        if($sql->num_rows()>0)
        {
            $data=$sql->row_array();
            $this->updateData(array('status'=>5,'bill_id'=>NULL),'bill_id',$data['bill_id'], 'gift_card');
        }
        return true;
    }
    
    function getOldMetalRate($id_metal)
	{
	    $sql=$this->db->query("SELECT rate from ret_old_metal_rate where id_metal=".$id_metal." and status=1");
	    return $sql->row()->rate;
	}
	
	
	 function getChitUtilized($bill_id)
	{
	    $sql=$this->db->query("Select * from ret_billing_chit_utilization where bill_id=".$bill_id."");
	    return $sql->result_array();
	}
	
	
		//Business Customers
	
    function getSearchCompanyUsers($SearchTxt,$id_customer)
	{
        $data = $this->db->query("SELECT c.id_cmp_emp as value,concat(c.firstname,'-',c.mobile) as label
        FROM
        ret_customer_company_users c
        WHERE (firstname like '%".$SearchTxt."%' OR mobile like '%".$SearchTxt."%')
        and c.id_customer=".$id_customer.""); 
		return $data->result_array();
	}
	
	function addNewCompanyUsers($data)
	{
		$customer_check_query = $this->db->query("SELECT * FROM ret_customer_company_users WHERE mobile='".$data['mobile']."'");
		if($customer_check_query->num_rows() == 0){
			$insert_data = array("id_customer"=>$data['id_customer'],"firstname" => strtoupper($data['emp_name']),"mobile" => $data['mobile'],'date_add'=>date("Y-m-d H:i:s"),"created_by"=>$this->session->userdata('uid'));
			$cus_insert_id = $this->insertData($insert_data, "ret_customer_company_users");
			if(!empty($cus_insert_id)){
				$insert_data["id_cmp_emp"] = $cus_insert_id;
				return array("success" => TRUE, "message" => "Customer details added successfully", "response" => $insert_data);
			}else{
				return array("success" => FALSE, "message" => "Could not add customer, please try again", "response" => array());
			}
		}else{
			return array("success" => FALSE, "message" => "Given mobile number already exist", "response" => $customer_check_query->row());
		}
	}
	//Business Customers
	
	
	 function get_credit_collection_details($bill_id)
    {
        $return_data=array();
        $total_bill_amount=0;
        $credit_disc_amt=0;
    	$data=$this->db->query("SELECT b.bill_id,b.bill_no,b.bill_type,b.ref_bill_id,b.tot_amt_received,b.credit_disc_amt,
    	b.tot_bill_amount,DATE_FORMAT(b.bill_date,'%d-%m-%Y') as bill_date
    	 From ret_billing b 
    	 where b.bill_status=1 and b.ref_bill_id=".$bill_id."");
        $items=$data->result_array();
        foreach($items as $item)
        {
            $total_bill_amount  += $item['tot_amt_received'];
            $credit_disc_amt    += $item['credit_disc_amt'];
            $old_metal_details  =  $this->getOld_sales_details($item['bill_id'],8);
            $old__metal_amount  =0;
            foreach($old_metal_details as $old_items)
            {
                $old__metal_amount+=$old_items['amount'];
            }
        }
        return $total_bill_amount+$old__metal_amount+$credit_disc_amt;
    }
    
    function getOld_sales_details($bill_id,$bill_type)
	{
		$old_metal_query=$this->db->query("SELECT s.old_metal_sale_id,s.bill_id,s.purpose,s.metal_type,s.item_type,s.gross_wt,s.stone_wt,s.dust_wt,s.stone_wt,s.wastage_percent,s.wast_wt,
		s.net_wt,s.rate_per_grm as rate_per_gram,s.rate as amount,s.bill_id,s.bill_discount,est_id,b.bill_no,b.pur_ref_no
		FROM ret_billing b 
		LEFT join  ret_bill_old_metal_sale_details s on s.bill_id=b.bill_id
		where b.bill_status=1 and s.bill_id=".$bill_id."");
		$old_matel_details = $old_metal_query->result_array();
		return $old_matel_details;
	}
	
	
	
	//Update Wallet Account
	function updateWalletData($data,$arith)
	{ 
		$sql = "UPDATE ret_wallet SET amount=(amount".$arith." ".$data['amount']."),weight=(weight".$arith." ".$data['weight']."),updated_by=".$this->session->userdata('uid').",updated_time='".date("Y-m-d H:i:s")."' WHERE id_customer=".$data['id_customer'];  
		$status = $this->db->query($sql);
		return $status;
	}
	//Update Wallet Account
	
	
	function getAdvanceAdjusted_Details($bill_id)
	{
	    $advance_adjusted=$this->db->query("SELECT utilized_amt as adj_amt FROM ret_advance_utilized WHERE bill_id=".$bill_id."");
		return $advance_adjusted->row()->adj_amt;
	}
	
	function getCompanyPurchaseAmount($id_customer)
	{
	    $sql=$this->db->query("SELECT IFNULL(SUM(d.item_cost),0) as tot_purchase_amt
        FROM ret_billing b 
        LEFT JOIN ret_bill_details d ON d.bill_id=b.bill_id
        WHERE b.bill_status=1
        ".($id_customer!='' ? " and b.bill_cus_id=".$id_customer."" :'')."");
        return $sql->row_array();
	}
	
	
	//Incentive Report
	function getTagDetails($tag_id,$estimation_id)
	{
	    $sql=$this->db->query("SELECT tag.tag_id,c.id_metal,e.created_by as id_employee
        FROM ret_taging tag
        LEFT JOIN ret_product_master p on p.pro_id=tag.product_id
        LEFT JOIN ret_category c ON c.id_ret_category=p.cat_id
        LEFT JOIN metal m ON m.id_metal=c.id_metal
        LEFT JOIN ret_estimation_items est on est.tag_id=tag.tag_id
        LEFT JOIN ret_estimation e on e.estimation_id=est.esti_id
        WHERE tag.tag_mark=1 and tag.tag_id=".$tag_id." and est.est_item_id=".$estimation_id."");
        //print_r($this->db->last_query());exit;
        return $sql->row_array();
	}
	
	function get_wallet_account($id_employee)
	{
	    $return_data=array();
	    $sql=$this->db->query("SELECT * FROM wallet_account WHERE idemployee=".$id_employee);
	    if($sql->num_rows()>0)
	    {
	        $return_data=array('status'=>true,'message'=>'Account Already Exist','id_wallet_account'=>$sql->row()->id_wallet_account);
	    }else{
	         $return_data=array('status'=>false);
	    }
	    return $return_data;
	}
	
	function get_wallet_acc_number()
	{
	  $query = $this->db->query("SELECT LPAD(round(rand() * 10000000),8,0) as myCode
								FROM wallet_account
								HAVING myCode NOT IN (SELECT wallet_acc_number FROM wallet_account) limit 0,1");
		if($query->num_rows()==0){
			$query = $this->db->query("SELECT LPAD(round(rand() * 10000000),8,0) as myCode");
		}
		return $query->row()->myCode;
	}
	
	function getWalletTransDetails($bill_id)
	{
	    $sql=$this->db->query("SELECT * FROM `wallet_transaction` WHERE bill_id=".$bill_id."");
	    return $sql->result_array();
	}
	
	function getWalletTransTagDetails($tag_id)
	{
	    $sql=$this->db->query("SELECT * FROM `wallet_transaction` WHERE ref_no=".$tag_id."");
	    return $sql->row_array();
	}
	function get_charges($tag_id)
	{
		$sql = $this->db->query("SELECT rtc.tag_charge_id, rtc.tag_id, rtc.charge_id, rtc.charge_value, c.code_charge, c.tag_display FROM ret_taging_charges AS rtc LEFT JOIN ret_charges AS c ON rtc.charge_id = c.id_charge  WHERE tag_id=".$tag_id);  
	    return $sql->result_array();
	}
	//Incentive Report
	function get_other_estcharges($est_item_id)
	{
		$sql = $this->db->query("
		SELECT e.id_charge,IFNULL(e.amount,0) AS charge_value,s.code_charge, e.est_item_id
        FROM ret_estimation_other_charges e 
        LEFT JOIN ret_charges s on s.id_charge=e.id_charge
	    WHERE e.est_item_id=".$est_item_id."");  
	    return $sql->result_array();
	}
	
	
	//metal details
	function get_metal_details($id_metal)
	{
	    $sql=$this->db->query("SELECT * FROM `metal` WHERE id_metal=".$id_metal."");
	    return $sql->row_array();
	}
	//metal details
	
	function get_employee_settings($id_employee)
    {
       $sql=$this->db->query("SELECT * FROM `employee_settings` WHERE id_employee=".$id_employee."");
       
       return $sql->row_array();
    }
    
    function get_profile_settings($id_profile)
    {
       $sql=$this->db->query("SELECT * FROM `profile` WHERE id_profile=".$id_profile."");
       return $sql->row_array();
    }
    
    
    
     function get_InventoryCategory($id_other_item_type)
    {
        $id_other_item_type = $this->db->query("SELECT t.id_other_item_type,t.qrcode,i.issue_preference
        FROM ret_other_inventory_item i 
        LEFT JOIN ret_other_inventory_item_type t ON t.id_other_item_type=i.item_for
        WHERE i.id_other_item=".$id_other_item_type."");
		return $id_other_item_type->row_array();
    }
    
     function get_other_inventory_purchase_items_details($id_other_item,$id_branch,$issue_preference,$total_pcs)
    {
        $sql=$this->db->query("SELECT * FROM `ret_other_inventory_purchase_items_details` 
        WHERE other_invnetory_item_id=".$id_other_item." AND current_branch=".$id_branch." AND status=0
        ".($issue_preference==1 ? 'order by pur_item_detail_id ASC' :'order by pur_item_detail_id DESC')."
        LIMIT ".$total_pcs."");
        //print_r($this->db->last_query());exit;
        return $sql->result_array();
    }
    
    function get_old_estimation_details($est_old_itm_id)
    {
        $sql=$this->db->query("SELECT * FROM ret_estimation_old_metal_sale_details s WHERE s.old_metal_sale_id=".$est_old_itm_id.""); 
        return $sql->row_array();
    }
    
    
    //Purchase Item Stock Summary
   /* function checkPurchaseItemStockExist($data)
    {
		$r = array("status" => FALSE);
        $sql = "SELECT * FROM `ret_purchase_item_stock_summary`  WHERE type=".$data['type']."  ".(isset($data['id_old_metal_type']) && $data['id_old_metal_type']!='' ? " and id_old_metal_type=".$data['id_old_metal_type']."" :'')." ".(isset($data['id_product']) && $data['id_product']!='' ? " and id_product=".$data['id_product']."" :'')." ".($data['id_branch']!='' ?  " and id_branch=".$data['id_branch']."" :'')."  "; 		
        $res = $this->db->query($sql);
        
		if($res->num_rows() > 0){
			$r = array("status" => TRUE,); 
		}else{
			$r = array("status" => FALSE); 
		} 
		return $r;
	}
	
	function get_return_bill_details($bill_detail_id)
	{
	    $sql=$this->db->query("SELECT * FROM ret_bill_details d WHERE d.bill_det_id=".$bill_detail_id."");
	    return $sql->row_array();
	}
	
	
	function updatePurItemData($data,$arith){ 
		$sql = "UPDATE ret_purchase_item_stock_summary SET pieces=(pieces".$arith." ".$data['pieces']."),gross_wt=(gross_wt".$arith." ".$data['gross_wt']."),less_wt=(less_wt".$arith." ".$data['less_wt']."),net_wt=(net_wt".$arith." ".$data['net_wt']."),updated_by=".$data['updated_by'].",updated_on='".$data['updated_on']."' WHERE id_product=".$data['id_product']." and id_branch=".$data['id_branch']."";  
		$status = $this->db->query($sql);
		return $status;
	}
	*/
	
	function updatePurItemData($id_stock_summary,$data,$arith){ 
		$sql = "UPDATE ret_purchase_item_stock_summary SET pieces=(pieces".$arith." ".$data['pieces']."),gross_wt=(gross_wt".$arith." ".$data['gross_wt']."),less_wt=(less_wt".$arith." ".$data['less_wt']."),net_wt=(net_wt".$arith." ".$data['net_wt']."),updated_by=".$data['updated_by'].",updated_on='".$data['updated_on']."' WHERE id_stock_summary=".$data['id_stock_summary']." ";  
		$status = $this->db->query($sql);
		return $status;
	}
	
    //Purchase Item Stock Summary
    
    function get_one_time_pre_weight_scheme()
    {
        $sql=$this->db->query("SELECT * FROM `scheme` WHERE one_time_premium=1 AND flexible_sch_type=4 or flexible_sch_type=5");
        return $sql->result_array();
    }
    
    function get_customer_weight_scheme_details($data)
    {
        $sql=$this->db->query("SELECT s.id_scheme_account,IFNULL(pay.paid_installments,0) as paid_installments,s.id_scheme,sch.total_installments,s.account_name
        FROM scheme_account s
        left join scheme sch on sch.id_scheme=s.id_scheme
        LEFT JOIN (
                select s.total_installments,s.scheme_type,s.id_scheme,s.firstPayDisc_value,sa.id_scheme_account,IFNULL(IF(sa.is_opening=1,IFNULL(sa.paid_installments,0)+ IFNULL(if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues)),0), if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues))) ,0)as paid_installments
                FROM payment p
                left join scheme_account sa on sa.id_scheme_account=p.id_scheme_account
                left join scheme s on s.id_scheme=sa.id_scheme
                where p.payment_status=1 AND s.id_scheme=".$data['id_scheme']." 
                group by sa.id_scheme_account
        ) as pay on pay.id_scheme_account=s.id_scheme_account
        
        WHERE s.id_scheme=".$data['id_scheme']." AND s.id_customer=".$data['id_customer']." 
        HAVING  total_installments>paid_installments");
        //print_r($this->db->last_query());exit;
        return $sql->result_array();
    }
    
    function getChitPayDetails($bill_id)
    {
        $sql=$this->db->query("SELECT * FROM `payment_old_metal` WHERE bill_id=".$bill_id."");
        return $sql->row_array();
    }
    
    
    function get_purity_details($purity)
    {
        $sql=$this->db->query("SELECT * FROM ret_purity WHERE purity='".$purity."'");
        return $sql->row_array();
    }
    
    
    //bank account details
	function get_bank_acc_details()
	{
	    	$sql=$this->db->query("SELECT concat(short_code,' ',acc_number) as acc_number,address,id_bank
            FROM bank
            WHERE acc_number is NOT null");
		return $sql->result_array();
	}
	
	function get_payment_device_details()
	{
	    $sql=$this->db->query("SELECT * FROM `ret_bill_pay_device` WHERE status=1");
		return $sql->result_array();
	}
	
	//bank account details
	
	function get_customer_reg_add($id_customer)
	{
		$sql=$this->db->query("SELECT a.id_address,IFNULL(a.id_country,'') as id_country,a.id_city,a.address1,a.address2,a.address3,a.pincode,c.name as country_name,s.name as state_name,
		cy.name as city_name,IFNULL(a.id_state,'') as id_state
		FROM address a 
		LEFT JOIN country c ON c.id_country=a.id_country
		LEFT JOIN state s ON s.id_state=a.id_state
		LEFT JOIN city cy ON cy.id_city=a.id_city
		WHERE a.id_customer=".$id_customer."");
		return $sql->row_array();
	}
	
	function getCusDelivery_address($id_delivery_addr)
	{
		$sql=$this->db->query("SELECT d.id_delivery_addr as id_delivery,d.id_customer,d.id_country,d.id_state,IFNULL(d.address1,'') as address1,IFNULL(d.address2,'') as address2,IFNULL(d.address3,'') as address3,d.pincode,d.address_name,c.name as country_name,s.name as state_name,cy.name as city_name,
		IFNULL(d.id_city,'') as id_city
		FROM customer_delivery_address d 
		LEFT JOIN country c ON c.id_country=d.id_country
		LEFT JOIN state s ON s.id_state=d.id_state
		LEFT JOIN city cy on cy.id_city=d.id_city
		WHERE d.id_delivery_addr=".$id_delivery_addr." ");
		return $sql->row_array();
	}
	
	function get_mydelivery_address($id_customer)
	{
		$sql=$this->db->query("SELECT d.id_delivery_addr as id_delivery,d.id_customer,d.id_country,d.id_state,IFNULL(d.address1,'') as address1,IFNULL(d.address2,'') as address2,IFNULL(d.address3,'') as address3,d.pincode,d.address_name,c.name as country_name,s.name as state_name,cy.name as city_name
		FROM customer_delivery_address d 
		LEFT JOIN country c ON c.id_country=d.id_country
		LEFT JOIN state s ON s.id_state=d.id_state
		LEFT JOIN city cy on cy.id_city=d.id_city
		WHERE d.id_customer=".$id_customer." and d.address_name is not null");
		return $sql->result_array();
	}
    
    
    function getAvailableIndCustomers($SearchTxt){
	    $billing_for=$_POST['billing_for'];
	    
		$data = $this->db->query("SELECT c.id_customer as value, concat(firstname,'-',mobile) as label, mobile,firstname, id_branch,v.village_name,if(c.is_vip=1,'Yes','No') as vip,c.id_village,
			(select count(sa.id_scheme_account) from scheme_account sa where sa.id_customer=c.id_customer) as accounts,
			addr.id_country,addr.id_state,addr.id_city,addr.address1,addr.address2,addr.address3,addr.pincode,ct.name as country_name,s.name as state_name,cy.name as city_name,
			c.email,IFNULL(c.pan,'') as pan_no,IFNULL(c.aadharid,'') as aadharid,IFNULL(c.gst_number,'') as gst_number,IFNULL(c.id_village,'') as id_village
			FROM customer c
			LEFT JOIN village v on v.id_village=c.id_village
			LEFT JOIN address addr on addr.id_customer=c.id_customer
            LEFT JOIN country ct ON ct.id_country=addr.id_country
            LEFT JOIN state s ON s.id_state=addr.id_state
            LEFT JOIN city cy ON cy.id_city=addr.id_city
			WHERE c.id_customer='".$SearchTxt."'
			".($billing_for==2 ? " and c.cus_type=2" :'').""); 
		//print_r($this->db->last_query());exit;
		return $data->result_array();
	}
	
	function get_active_bill_list($bill_no, $branch,$fin_year)
	{
	    	$sql=$this->db->query("SELECT p.id_pay_device,p.payment_mode,p.payment_amount,p.bill_id,b.bill_no,b.id_branch,p.card_no,c.firstname,c.mobile,b.tot_amt_received,p.NB_type,
	    	p.cheque_date,p.cheque_no,p.card_no,p.payment_ref_number,Date_format(p.payment_date,'%Y-%m%-%d ') as payment_date,p.bill_id,p.created_by,
			b.tot_bill_amount 
			from ret_billing b
			left join ret_billing_payment p on p.bill_id=b.bill_id
			left join customer c on c.id_customer=b.bill_cus_id
			WHERE b.bill_no='".$bill_no."' and b.id_branch='".$branch."' 
			and b.bill_status=1 and b.fin_year_code='".$fin_year."'");
		    return $sql->result_array();
	}
	
	function get_active_bill($bill_no,$branch)
	{
	    $sql=$this->db->query("SELECT bill_no from ret_billing WHERE bill_no='".$bill_no."' and id_branch='".$branch."' and bill_status=1");
        return $sql->num_rows();
	}
	
	function get_customer_credit_details($data)
	{
	    $sql=$this->db->query("SELECT r.bill_no,r.id_issue_receipt,r.amount as issue_amt,IFNULL(coll.paid_amt,0) as paid_amt,
        IFNULL(r.amount-IFNULL(coll.paid_amt,0),0) as balance_amount
        FROM ret_issue_receipt r 
        LEFT JOIN (SELECT IFNULL(SUM(c.received_amount),0) as paid_amt,c.receipt_for
                  FROM ret_issue_credit_collection_details c 
                  LEFT JOIN ret_issue_receipt rct ON rct.id_issue_receipt=c.receipt_for
                  LEFT JOIN ret_issue_receipt rc ON rc.id_issue_receipt=c.id_issue_receipt
                  where rc.bill_status=1
                  GROUP by c.receipt_for) as coll ON coll.receipt_for=r.id_issue_receipt
        WHERE r.type=1 AND r.is_collect=0 and (r.issue_type=2 or r.issue_type=4)
        ".($data['id_customer']!='' ? " and r.id_customer=".$data['id_customer']."" :'')."
        HAVING balance_amount>0 ");
        //print_r($this->db->last_query());exit;
        return $sql->result_array();
	}
    function get_mc_va_limit($id_product, $id_design, $id_sub_design, $grossWt = 0, $lot_no = 0) {
        $mc_min = 0;
        
        $wastag_min = 0;
		
		$margin_mrp = 0;
		$po_details = array();
		$po_mc = 0;
		$po_va = 0;
		$where = "";
		if($id_sub_design > 0) {
			$where  =  " AND id_sub_design = ".$id_sub_design;
			if($lot_no > 0 && $lot_no != "") {
				$po_details = $this->get_purchase_details($id_product, $id_design, $id_sub_design, $lot_no);
				$po_mc = is_numeric($po_details[0]['mc_value']) && $po_details[0]['mc_value'] > 0 ? $po_details[0]['mc_value'] : 0;
				$po_va = is_numeric($po_details[0]['item_wastage']) && $po_details[0]['item_wastage'] > 0 ? $po_details[0]['item_wastage'] : 0;
			}
		}
        
        $sql = "SELECT
					id_sub_design_mapping,
					wastage_type,
                    IFNULL(mc_min,0) AS mc_min, 
                    IFNULL(wastag_min ,0) AS wastag_min,
					IFNULL(margin_mrp ,0) AS margin_mrp
                FROM ret_sub_design_mapping 
                WHERE id_product = ".$id_product." AND id_design=".$id_design.$where;
        //echo $sql;exit;
		
        $query_details = $this->db->query($sql);
        if ($query_details->num_rows() > 0)
        {
            $row = $query_details->row_array(); ;
			$id_sub_design_mapping = $row['id_sub_design_mapping'];
			$wastage_type = $row['wastage_type'];
			$margin_mrp = $row['margin_mrp'];
			if($wastage_type == 1) {
				$mc_min = $row['mc_min'];
				$wastag_min = $row['wastag_min'];
			} else if($wastage_type == 2) {
				if($grossWt != "") {
					$sql = "SELECT
								IFNULL(mcrg_min,0) AS mc_min, 
								IFNULL(wc_min,0) AS wastag_min
							FROM ret_design_weight_range_wc 
							WHERE id_sub_design_mapping = ".$id_sub_design_mapping." AND (".$grossWt." >= wc_from_weight AND ".$grossWt." <= wc_to_weight)";
					$query_details = $this->db->query($sql);
					if ($query_details->num_rows() > 0) {
						$row = $query_details->row_array(); 
						$mc_min = is_numeric($row['mc_min']) && $row['mc_min'] > 0 ? $row['mc_min'] : 0;
						$wastag_min = is_numeric($row['wastag_min']) && $row['wastag_min'] > 0 ? $row['wastag_min'] : 0;
					}
				}
			}
        }
		$mc_min = $mc_min > $po_mc ? $mc_min : $po_mc;
		$wastag_min = $wastag_min > $po_va ? $wastag_min : $po_va;
        $result_arr = array("mc_min" => $mc_min, "wastag_min" => $wastag_min, "margin_mrp" => $margin_mrp);
		return $result_arr;
	}
	
	function getBillDetailsData($billId){
	    
	    $det_query = $this->db->query("SELECT sum(total_sgst), sum(total_cgst), sum(total_igst), sum(item_total_tax), sum(item_cost), sum(CAST(((item_cost - item_total_tax) + total_sgst + total_cgst + total_igst) AS DECIMAL(10,2))) as itemwithtax FROM ret_bill_details WHERE bill_id = '".$billId."' GROUP BY bill_id");
	    return $det_query->row_array();
	}
    
    
    //service bill type
    function service_bill_number_generator($id_branch)
	{
		$lastno = $this->get_last_service_bill_no($id_branch);
		if($lastno!=NULL && $lastno!='')
		{
			$number = (int) $lastno;
			$number++;
			$code_number = str_pad($number, 5, '0', STR_PAD_LEFT);			
			return $code_number;
		}
		else
		{
			$code_number = str_pad('1', 5, '0', STR_PAD_LEFT);
			return $code_number;
		}
	}
	function get_last_service_bill_no($id_branch)
    {
        $fin_year = $this->get_FinancialYear();
		$sql = "SELECT (bill_no) as lastBill_no 
		FROM ret_service_bill 
		where fin_year_code=".$fin_year['fin_year_code']."
		".($id_branch!='' && $id_branch>0 ? " and id_branch=".$id_branch."" :'')."
				ORDER BY id_service_bill DESC LIMIT 1";
		
		//print_r($sql);exit;
		return $this->db->query($sql)->row()->lastBill_no;	
	}
	
	 function ajax_getServiceBillList($data)
    {
        
        if($_POST['dt_range'] != '')
        {
            $dateRange = explode('-',$_POST['dt_range']);
            $from = str_replace('/','-',$dateRange[0]);
            $to = str_replace('/','-',$dateRange[1]);  
            $d1 = date_create($from);
            $d2 = date_create($to);
            $FromDt = date_format($d1,"Y-m-d");
            $ToDt = date_format($d2,"Y-m-d");
        }
        $sql = $this->db->query("SELECT b.id_service_bill,b.bill_no,date_format(b.bill_date,'%d-%m-%Y') as bill_date,b.total_bill_amount,if(b.bill_status=1,'Success','Cancelled') as billStatus,
        b.bill_status,c.firstname as cus_name,c.mobile,br.name as branch_name,if(date(d.entry_date)=date(b.bill_date),'1','0') as allow_cancel
        FROM ret_service_bill b 
        LEFT JOIN customer c ON c.id_customer = b.id_customer
        LEFT JOIN branch br ON br.id_branch = b.id_branch
        LEFT JOIN ret_day_closing d on d.id_branch=b.id_branch
        where  ".($data['dt_range'] != '' ? 'date(b.bill_date) BETWEEN "'.$FromDt.'" AND "'.$ToDt.'"' : '')."
        ".($data['bill_no']!='' ? " and b.bill_no=".$data['bill_no']."" :'')." 
        ".($data['id_branch']!=0 && $data['id_branch']!='' ? " and b.id_branch=".$data['id_branch']."" :'')." 
         ORDER BY b.id_service_bill desc");
        //echo $this->db->last_query();exit;
        return $sql->result_array();
    }
    
    function getServiceBillingDetails($id)
    {
        $sql = $this->db->query("SELECT b.id_service_bill,b.bill_no,date_format(b.bill_date,'%d-%m-%Y') as bill_date,b.total_bill_amount,if(b.bill_status=1,'Success','Cancelled') as billStatus,
        b.bill_status,c.firstname as customer_name,c.mobile,br.name as branch_name,if(date(d.entry_date)=date(b.bill_date),'1','0') as allow_cancel,
        a.address1,a.address2,a.address3,ct.name as city,a.pincode,ct.name as city,s.name as state,cy.name as country,b.id_branch,b.total_bill_amount as tot_bill_amount
        FROM ret_service_bill b 
        LEFT JOIN customer c ON c.id_customer = b.id_customer
        LEFT JOIN branch br ON br.id_branch = b.id_branch
        LEFT JOIN ret_day_closing d on d.id_branch=b.id_branch
        LEFT JOIN address a on a.id_customer=c.id_customer
        LEFT JOIN city ct on a.id_city=ct.id_city
		LEFT JOIN state s on s.id_state=a.id_state
		LEFT JOIN country cy on (a.id_country=cy.id_country)
        where  b.id_service_bill=".$id."
        ORDER BY b.id_service_bill desc");
        return $sql->row_array();
    }
    
    function getServiceBillPaymentDetails($bill_id)
	{
		$pay_details = array("pay_details" => array());
		$items_query = $this->db->query("SELECT p.id_billing_payment,p.type,p.payment_for,p.payment_amount,p.card_no,p.cvv,p.payment_mode,IFNULL(p.payment_ref_number,'') as payment_ref_number,
		if(p.NB_type=1,'RTGS',if(p.NB_type=2,'IMPS',if(p.NB_type=3,'UPI',''))) as transfer_type
		FROM ret_service_bill_payment p
		where p.id_service_bill=".$bill_id."");
		$data=$items_query->result_array();
		foreach ($data as $items ) {
				$pay_details['pay_details'][]=array(
						'id_billing_payment'    =>$items['id_billing_payment'],
						'type'                  =>$items['type'],
						'bill_id'               =>$items['bill_id'],
						'payment_for'           =>$items['payment_for'],
						'payment_amount'        =>$items['payment_amount'],
						'cvv'                   =>$items['cvv'],
						'card_no'               =>$items['card_no'],
						'payment_mode'          =>$items['payment_mode'],
						'payment_ref_number'    =>$items['payment_ref_number'],
						'transfer_type'         =>$items['transfer_type'],
				);		
			}
		return $pay_details;
	}
	
	function getServiceBillItemDetails($id)
	{
	    $sql = $this->db->query("SELECT p.product_name,m.name,d.piece,d.weight,d.item_total_tax,d.item_total_cost,d.total_cgst,d.total_sgst,d.tax_percentage
        FROM ret_service_bill_details d 
        LEFT JOIN ret_service_bill b ON b.id_service_bill = d.bill_detail_id
        LEFT JOIN ret_product_master p ON p.pro_id = d.id_product
        LEFT JOIN ret_repair_master m ON m.id_repair_master = d.id_service
        Where d.id_service_bill=".$id."");
        return $sql->result_array();
	}
    
    
    function get_repair_item_details($bill_id)
    {
        $repair_order_details=$this->db->query("SELECT IFNULL(SUM(od.rate),0) as amount,IFNULL(SUM(od.total_sgst),0) sgst,IFNULL(SUM(od.total_cgst),0) cgst,IFNULL(SUM(od.total_igst),0) igst,IFNULL(SUM(od.repair_tot_tax),0) as repair_tot_tax,(IFNULL(SUM(od.rate),0)-IFNULL(SUM(od.repair_tot_tax),0)) as taxable_amount,od.repair_percent
        FROM customerorderdetails od
        LEFT JOIN customerorder c ON c.id_customerorder=od.id_customerorder
        LEFT JOIN ret_product_master p ON p.pro_id=od.id_product
        LEFT JOIN ret_design_master des ON des.design_no=od.design_no
        LEFT JOIN ret_billing b ON b.bill_id=od.bill_id
        WHERE od.bill_id=".$bill_id."");
        return $repair_order_details->row_array();
    }
    //service bill type
    
    function get_headOffice()
	{
		$data=$this->db->query("SELECT b.is_ho,b.id_branch,name FROM branch b where b.is_ho=1");
		return $data->row_array();
	}
    
    function get_approval_tag_details($tag_id)
    {
        $sql = $this->db->query("SELECT * FROM ret_taging WHERE tag_id = '".$tag_id."' ");
        return $sql->row_array();
    }
    
    function get_order_id_details($bill_id){
		$order_id = $this->db->query("SELECT id_orderdetails FROM ret_bill_details WHERE bill_id=".$bill_id."");
		return  $order_id->result_array();
	}
    
    
    function getCustomerDet($id_branch,$id_customer)

	{

	    $return_data=array();

		$data=$this->db->query("SELECT c.firstname,c.id_customer,c.is_vip,c.mobile,v.village_name,b.name as branch_name,IFNULL(esti.tot_est,0) as estimation_no,

        IFNULL(bill_tot.bill_count,0) as bill_count,IFNULL(tot_acc.tot_acc,0) as tot_account,IFNULL(iactive.tot_acc,0)as inactive_acount,

        IFNULL(tot_gold.gold_wt,0) as gold_wt,IFNULL(tot_silver.silver_wt,0) as silver_wt,IFNULL(closed_chit.closed_count,0) as closed_count,IFNULL(tot_payment.pay_amount,0) as tot_amount,

        IFNULL(fixed_rate.item_cost,0) as tot_fixed_rate,IFNULL(active_acc.tot_acc,0) as active_acc,IFNULL(DATE_FORMAT(max(bill.bill_date),'%d-%m-%Y'),'-') as last_billdate,z.name as zone_name

        from ret_billing bill

        left join customer c on (c.id_customer=bill.bill_cus_id)

        left join branch b on (b.id_branch=c.id_branch)

        LEFT JOIN village v on (v.id_village=c.id_village)

        LEFT JOIN village_zone z on (z.id_zone=v.id_zone)

        

       

        left join (select count(est.estimation_id) as tot_est,est.cus_id from ret_estimation est

                   left join customer as c on c.id_customer=est.cus_id where cus_id=".$id_customer.")as esti on esti.cus_id=c.id_customer

       

        left join (select COUNT(bill.bill_id) as bill_count,bill.bill_cus_id from ret_billing as bill

            left join customer as c on c.id_customer=bill.bill_cus_id

            where bill.bill_status=1 and c.id_customer=".$id_customer."

            ) as bill_tot on bill_tot.bill_cus_id=c.id_customer

                   

        left join(select count(sa.id_scheme_account) as tot_acc,sa.id_customer,c.mobile from scheme_account sa

            left join customer c on (c.id_customer=sa.id_customer)

            where sa.scheme_acc_number is not null and c.id_customer=".$id_customer.") as tot_acc on tot_acc.id_customer=c.id_customer

           

        left join(select count(sa.id_scheme_account) as tot_acc,sa.id_customer,c.mobile from scheme_account sa

            left join customer c on (c.id_customer=sa.id_customer)

            where sa.scheme_acc_number is not null and sa.is_closed=0 and c.id_customer=".$id_customer.") as active_acc on active_acc.id_customer=c.id_customer

           

        left join(select count(sa.scheme_acc_number) as closed_count,sa.id_customer,c.mobile from scheme_account sa

            left join customer c on (c.id_customer=sa.id_customer)

            where sa.is_closed=1 and c.id_customer=".$id_customer.") as closed_chit on closed_chit.id_customer=c.id_customer

        

        left join (SELECT COUNT(sa.id_scheme_account) as tot_acc,

            TIMESTAMPDIFF(month, max(p.date_add), current_date()) as month_ago,sa.id_customer

            FROM scheme_account sa

            LEFT JOIN payment p ON p.id_scheme_account=sa.id_scheme_account

            LEFT JOIN scheme s ON s.id_scheme=sa.id_scheme

            LEFT JOIN customer cus ON cus.id_customer=sa.id_customer

            WHERE sa.is_closed=0

            and cus.id_customer=".$id_customer." HAVING month_ago>3) as iactive on iactive.id_customer=c.id_customer

        left join(select sum(bill_det.net_wt) as gold_wt,c.id_customer from ret_billing as bill

            left JOIN ret_bill_details  as bill_det on(bill_det.bill_id=bill.bill_id)

            left join ret_product_master as pro on(pro.pro_id=bill_det.product_id)

            left join ret_category as cat on(cat.id_ret_category=pro.cat_id)

            left join metal as m on(m.id_metal=cat.id_metal)

            left join customer as c on(c.id_customer=bill.bill_cus_id)

            left join branch b on (b.id_branch=c.id_branch)

            LEFT join village v on (v.id_village=c.id_village)

            LEFT JOIN village_zone z on (z.id_zone=v.id_zone)

            where bill.bill_status=1 and m.id_metal=1 

            and c.id_customer=".$id_customer."

             ) as tot_gold on tot_gold.id_customer=c.id_customer

           

        left join(SELECT sum(bill_det.net_wt) as silver_wt,c.id_customer from ret_billing as bill

            left JOIN ret_bill_details  as bill_det on(bill_det.bill_id=bill.bill_id)

            left join ret_product_master as pro on(pro.pro_id=bill_det.product_id)

            left join ret_category as cat on(cat.id_ret_category=pro.cat_id)

            left join metal as m on(m.id_metal=cat.id_metal)

            left join customer as c on(c.id_customer=bill.bill_cus_id)

            left join branch b on (b.id_branch=c.id_branch)

            LEFT join village v on (v.id_village=c.id_village)

            LEFT JOIN village_zone z on (z.id_zone=v.id_zone)

            where bill.bill_status=1 and m.id_metal=2 and c.id_customer=".$id_customer."

            ) as tot_silver on tot_silver.id_customer=c.id_customer

           

         left join(SELECT sum(bill_det.item_cost) as item_cost,c.id_customer from ret_billing as bill

            left JOIN ret_bill_details  as bill_det on(bill_det.bill_id=bill.bill_id)

            left join ret_product_master as pro on(pro.pro_id=bill_det.product_id)

            left join ret_category as cat on(cat.id_ret_category=pro.cat_id)

            left join metal as m on(m.id_metal=cat.id_metal)

            left join customer as c on(c.id_customer=bill.bill_cus_id)

            left join branch b on (b.id_branch=c.id_branch)

            LEFT join village v on (v.id_village=c.id_village)

            LEFT JOIN village_zone z on (z.id_zone=v.id_zone)

            where bill.bill_status=1 and pro.sales_mode=1 and c.id_customer=".$id_customer."

        	) as fixed_rate on fixed_rate.id_customer=c.id_customer

           

        left join(select sum(p.payment_amount) as pay_amount,c.id_customer,c.mobile from customer as c

            left join scheme_account as sa on(sa.id_customer=c.id_customer)

            left join payment as p on (p.id_scheme_account=sa.id_scheme_account)

            where c.id_customer=".$id_customer.") as tot_payment on tot_payment.id_customer=c.id_customer 

            where bill.bill_cus_id is not null

            and c.id_customer=".$id_customer."");

			// print_r($this->db->last_query());exit;

		

		$return_data['cus_details']=$data->result_array();

		

		$sql=$this->db->query("SELECT b.bill_id,b.bill_no,br.name as branch_name,IFNULL(g_wt.net_wt,0) as gold_wt,IFNULL(s_wt.net_wt,0) as silver_wt,IFNULL(fixed_rate.item_cost,0) as mrp_amount,DATE_FORMAT(b.bill_date,'%d-%m-%Y') as bill_date,b.tot_bill_amount,if(b.bill_status=1,'Success','Cancelled') as bill_status

        FROM ret_billing b 

        LEFT JOIN customer c ON c.id_customer=b.bill_cus_id

        LEFT JOIN ret_bill_details d ON d.bill_id=b.bill_id

		LEFT JOIN branch br on br.id_branch=b.id_branch

        

        left JOIN (select d.net_wt,b.bill_id from ret_billing b 

                  left join customer c ON c.id_customer=b.bill_cus_id

                  left JOIN ret_bill_details d ON d.bill_id=b.bill_id

                  left join ret_product_master as pro on pro.pro_id=d.product_id

            	  left join ret_category as cat on cat.id_ret_category=pro.cat_id

            	  left join metal as m on m.id_metal=cat.id_metal WHERE b.bill_status=1

                  and m.id_metal=1 and d.bill_det_id is NOT NULL and c.id_customer=".$id_customer.") as g_wt 

                  ON g_wt.bill_id=b.bill_id

                  

          left JOIN (select d.net_wt,b.bill_id from ret_billing b 

                  left join customer c ON c.id_customer=b.bill_cus_id

                  left JOIN ret_bill_details d ON d.bill_id=b.bill_id

                  left join ret_product_master as pro on pro.pro_id=d.product_id

            	  left join ret_category as cat on cat.id_ret_category=pro.cat_id

            	  left join metal as m on m.id_metal=cat.id_metal WHERE b.bill_status=1

                  and m.id_metal=2 and d.bill_det_id is NOT NULL and c.id_customer=".$id_customer.") as s_wt 

                  ON s_wt.bill_id=b.bill_id

                  

          left JOIN (select d.item_cost,b.bill_id from ret_billing b 

                  left join customer c ON c.id_customer=b.bill_cus_id

                  left JOIN ret_bill_details d ON d.bill_id=b.bill_id

                  left join ret_product_master as pro on pro.pro_id=d.product_id

            	  left join ret_category as cat on cat.id_ret_category=pro.cat_id

            	  left join metal as m on m.id_metal=cat.id_metal WHERE b.bill_status=1

                  and pro.sales_mode=1 and d.bill_det_id is NOT NULL and c.id_customer=".$id_customer.") as fixed_rate

                  ON fixed_rate.bill_id=b.bill_id

        

        WHERE b.bill_status=1 and d.bill_det_id is NOT null and c.id_customer=".$id_customer." 

        GROUP by b.bill_id ORDER BY b.bill_date DESC LIMIT 5");

        

        $return_data['bill_details']=$sql->result_array();

		$return_data['outstanding']=$this->get_credit_pending_details($id_branch,$id_customer);

        

        return $return_data;

    

	}
	
	
	function get_credit_pending_details($id_branch,$id_customer)
    {
        $return_data=array();
		$credit_detail = array();
		$credit_detai2 = array();
		$sql=$this->db->query("SELECT b.tot_amt_received,b.bill_id,b.bill_no,DATE_FORMAT(b.bill_date,'%d-%m-%Y') as bill_date,DATE_FORMAT(b.credit_due_date,'%d-%m-%Y') as credit_due_date,b.tot_bill_amount,b.tot_amt_received,b.bill_cus_id,c.mobile,c.firstname as cus_name,
		if(b.credit_status=1,'Paid','Pending') as credit_status,br.name as branch_name,b.tot_bill_amount,(b.tot_bill_amount-b.tot_amt_received-IFNULL(ret.credit_due_amt,0)) as bal_amt,b.credit_disc_amt,IFNULL(ret.credit_due_amt,0) as credit_due_amt,IFNULL(ret.credit_ret_amt,0) as credit_ret_amt
			from ret_billing b
			LEFT JOIN customer c on c.id_customer=b.bill_cus_id
			LEFT JOIN branch br on br.id_branch=b.id_branch
            LEFT JOIN(SELECT IFNULL((b.credit_due_amt),0) as credit_due_amt,
            r.ret_bill_id,IFNULL(b.credit_ret_amt,0) as credit_ret_amt
            FROM ret_bill_return_details r 
            LEFT JOIN ret_billing b ON b.bill_id = r.bill_id
            WHERE b.bill_status = 1
            GROUP BY r.ret_bill_id) as ret ON ret.ret_bill_id = b.bill_id
			where  b.bill_id is not null and b.is_credit=1 and b.bill_status=1  and b.bill_type!=8 and b.credit_status=2 and b.bill_type!=12
			".($id_branch != '' && $id_branch >0 ? ' and b.id_branch='.$id_branch: '')." 
			".($id_customer != '' && $id_customer >0 ? ' and b.bill_cus_id='.$id_customer: '')." 
			
			ORDER BY b.bill_cus_id");
			// print_r($this->db->last_query());exit;
		$result = $sql->result_array();
		foreach($result as $r){
		    $paid_amount=$this->get_credit_collection_details($r['bill_id']);
			// print_r($paid_amount);exit;
			$credit_detail[] = array(
			                    'type'              =>0,
								'bill_no'           =>$r['bill_no'],
								'bill_date'         =>$r['bill_date'],
								'cus_name'          =>$r['cus_name'],
								'mobile'            =>$r['mobile'],
								'branch_name'       =>$r['branch_name'],
								'tot_bill_amount'   =>$r['tot_bill_amount'],
								'credit_due_amt'    =>$r['credit_due_amt'],
								'credit_ret_amt'    =>$r['credit_ret_amt'],
								'tot_amt_received'    =>$r['tot_amt_received'],

								'bal_amt'           =>$r['bal_amt']-$paid_amount,
								'due_amount'        =>$r['tot_bill_amount']-$r['tot_amt_received'],
								'paid_amount'       =>$paid_amount,
								'bill_id'           =>$r['bill_id'],
								'credit_collection' =>$this->getCreditCollection($r['bill_id'])
							); 
		}
		
		
		
		$issue_sql=$this->db->query("SELECT r.id_issue_receipt as bill_id,r.bill_no as bill_no,cus.mobile,r.amount as due_amount,
        DATE_FORMAT(r.bill_date,'%d-%m-%Y') as bill_date,'' as credit_due_date,IFNULL(r.amount-IFNULL(coll.paid_amt,0),0) as bal_amt,
        IFNULL(coll.paid_amt,0) as paid_amount,cus.firstname as cus_name,br.name as branch_name,'1' as type,'0' as credit_ret_amt,r.amount as tot_bill_amount
        FROM ret_issue_receipt r 
        LEFT JOIN branch br on br.id_branch=r.id_branch
        LEFT JOIN (SELECT IFNULL(SUM(c.received_amount+c.discount_amt),0) as paid_amt,c.receipt_for
        FROM ret_issue_receipt r
        LEFT JOIN ret_issue_credit_collection_details c ON c.id_issue_receipt=r.id_issue_receipt
        where r.bill_status=1
        GROUP by c.receipt_for) as coll ON coll.receipt_for=r.id_issue_receipt
        LEFT JOIN customer cus ON cus.id_customer=r.id_customer
        WHERE r.type=1  and r.bill_status=1 and (r.issue_type=2 or r.issue_type=4)
        ".($id_branch != '' && $id_branch >0 ? ' and r.id_branch='.$id_branch: '')." 
          ");
        //print_r($this->db->last_query());exit;
        $result1 = $issue_sql->result_array();
        	foreach($result1 as $r)
        	{
        	    $issueCreditDetails = $this->get_IssueCreditCollectionDetails($r['bill_id']);
        	    $r['credit_collection']=$issueCreditDetails;
        	    $credit_detai2[]=$r;
        	}
		//echo "<pre>"; print_r($credit_detai2);exit;
		$return_data=array_merge($credit_detail,$credit_detai2);
		return $return_data;
    }
	

	function getCreditCollection($bill_id)
    {
        $return_data=array();
    	$data=$this->db->query("SELECT b.bill_id,b.bill_no,b.bill_type,b.ref_bill_id,b.tot_amt_received,b.credit_disc_amt,
    	b.tot_bill_amount,DATE_FORMAT(b.bill_date,'%d-%m-%Y') as bill_date
    	 From ret_billing b 
    	 where b.bill_status=1 and b.ref_bill_id=".$bill_id."");
        $items=$data->result_array();
        foreach($items as $item)
        {
            $old_metal_details=$this->getOld_sales_detail($item['bill_id'],8);
            $old_metal_amount=0;
            foreach($old_metal_details as $old_items)
            {
                $old_metal_amount+=$old_items['amount'];
            }
            $return_data[]=array(
                                 '0'                =>'type',
                                 'bill_no'          =>$item['bill_no'],
                                 'bill_id'          =>$item['bill_id'],
                                 'bill_type'        =>$item['bill_type'],
                                 'ref_bill_id'      =>$item['ref_bill_id'],
                                 'tot_amt_received' =>$item['tot_amt_received'],
                                 'credit_disc_amt'  =>$item['credit_disc_amt'],
                                 'tot_bill_amount'  =>($item['tot_bill_amount']+$old_metal_amount),
                                 'bill_date'        =>$item['bill_date'],
                                 'old_metal_amount'=>$old_metal_amount,
                                );
        }
        return $return_data;
    }









	function get_IssueCreditCollectionDetails($bill_id)
    {
        $sql=$this->db->query("SELECT r.id_issue_receipt as bill_id,r.bill_no,date_format(r.bill_date,'%d-%m-%Y') as bill_date,r.amount as tot_amt_received,
        coll.discount_amt as credit_disc_amt,'0' as old_metal_amount,'1' as type
        FROM ret_issue_receipt r
        LEFT JOIN ret_issue_credit_collection_details coll ON coll.id_issue_receipt=r.id_issue_receipt
        WHERE r.receipt_type=1 and r.bill_status=1 AND coll.receipt_for=".$bill_id."");
        //print_r($this->db->last_query());exit;
        return $sql->result_array();
    }
	
	
	
	
	
	
	function getOld_sales_detail($bill_id,$bill_type)
	{
		$old_metal_query=$this->db->query("SELECT s.old_metal_sale_id,s.bill_id,s.purpose,s.metal_type,s.item_type,s.gross_wt,s.stone_wt,s.dust_wt,s.stone_wt,s.wastage_percent,s.wast_wt,
		s.net_wt,s.rate_per_grm as rate_per_gram,s.rate as amount,s.bill_id,s.bill_discount,est_id,b.bill_no,b.pur_ref_no
		FROM ret_billing b 
		LEFT join  ret_bill_old_metal_sale_details s on s.bill_id=b.bill_id
		where b.bill_status=1 and s.bill_id=".$bill_id."");
		//print_r($this->db->last_query());exit;
		$old_matel_details = $old_metal_query->result_array();
		return $old_matel_details;
	}
	
	
	function get_old_metal_est_details($old_metal_sale_id)
    {
        $sql = $this->db->query("SELECT s.est_id
        FROM ret_estimation_old_metal_sale_details s 
        WHERE s.old_metal_sale_id =".$old_metal_sale_id."");
        return $sql->row_array();
    }
    
    function get_sale_est_details($est_itm_id)
    {
        $sql = $this->db->query("SELECT s.esti_id
        FROM ret_estimation_items s 
        WHERE s.est_item_id =".$est_itm_id."");
        return $sql->row_array();
    }
    
    
    
}
?>
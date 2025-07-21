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
	function get_ret_settings($settings)
	{
		$data=$this->db->query("SELECT value FROM ret_settings where name='".$settings."'"); 
		return $data->row()->value;
	}
	
	function get_branchwise_rate($id_branch)
	{
		$is_branchwise_rate=$this->session->userdata('is_branchwise_rate');
		if($id_branch!='' && $id_branch!=0 && $is_branchwise_rate==1)
		{
		    $sql="SELECT  b.name as name,m.mjdmagoldrate_22ct,m.goldrate_22ct,m.goldrate_24ct,m.silverrate_1gm,m.silverrate_1kg,m.mjdmasilverrate_1gm,platinum_1g,
		    Date_format(m.updatetime,'%d-%m%-%Y %h:%i %p')as updatetime   
		    FROM metal_rates m 
		    LEFT JOIN branch_rate br on br.id_metalrate=m.id_metalrates
		    left join branch b on b.id_branch=br.id_branch
		    ".($id_branch!='' ?" WHERE br.id_branch=".$id_branch."" :'')." ORDER by br.id_metalrate desc LIMIT 1";
		}
		else
		{
		    $sql="SELECT  b.name as name,m.mjdmagoldrate_22ct,m.goldrate_22ct,m.goldrate_24ct,m.silverrate_1gm,m.silverrate_1kg,m.mjdmasilverrate_1gm,platinum_1g,
		    Date_format(m.updatetime,'%d-%m%-%Y %h:%i %p')as updatetime   
		    FROM metal_rates m 
		    LEFT JOIN branch_rate br on br.id_metalrate=m.id_metalrates
		    left join branch b on b.id_branch=br.id_branch
		    WHERE br.id_branch=1 ORDER by br.id_metalrate desc LIMIT 1";
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
    function ajax_getBillingList($bill_no)
    {
        $sql = $this->db->query("SELECT bill_id, bill_no,
        if(bill_type = 1,'Sales',if(bill_type = 2,'Sales&Purchase',if(bill_type = 3,'Sales&Return',if(bill_type = 4,'Purchase',if(bill_type = 5,'Order Advance',if(bill_type = 6,'Advance',if(bill_type = 7,'Sales Return',''))))))) as bill_type,if(bill_status = 1,'Success',if(bill_status = 2,'Cancelled','')) as bill_status,
        date_format(bill_date, '%d-%m-%Y %H:%i') as bill_date,
        concat(firstname,' ',if(lastname!=NULL,lastname,'')) as customer,mobile,
        tot_amt_received as tot_bill_amt,print_taken
        FROM ret_billing as bill
        LEFT JOIN customer as cus ON cus.id_customer = bill.bill_cus_id
        ".($bill_no!='' ? " where bill.bill_no=".$bill_no."" :'')."  ORDER BY bill.bill_id desc");
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
    
    function getBillingDetails($bill_id)
	{
		$items_query = $this->db->query("SELECT b.bill_type,b.bill_cus_id,b.pan_no,b.bill_no,date_format(b.bill_date,'%d-%m-%Y') as bill_date,
		b.bill_id,concat(c.mobile,'-',c.firstname) as cus_name,b.id_branch,b.tot_bill_amount,
		date_format(b.credit_due_date,'%d-%m-%Y') as credit_due_date,b.is_credit,b.ref_bill_id as ref_bill_id,b.print_taken,b.remark,IFNULL(b.tot_discount,0) as tot_discount,a.id_state as cus_state,c.firstname as customer_name,c.mobile,
		br.name as branch_name,b.tot_amt_received,round_off_amt
		FROM ret_billing b
		LEFT JOIN customer c ON c.id_customer=b.bill_cus_id
		LEFT JOIN address a on a.id_customer=c.id_customer
		LEFT JOIN state s on s.id_state=a.id_state
		LEFT JOIN branch br on br.id_branch=b.id_branch
		where b.bill_id=".$bill_id."");
		$data=$items_query->row_array();
		if($data['ref_bill_id']!='')
		{
			$data['ref_bill_no']=$this->getBill_details($data['ref_bill_id']);
		}
		return $data;
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
		$pay_details = array("pay_details" => array(), "advance_adj" => array());
		$items_query = $this->db->query("SELECT p.id_billing_payment,p.type,p.bill_id,p.payment_for,p.payment_amount,p.card_no,p.cvv,p.payment_mode
		FROM ret_billing_payment p
		where p.bill_id=".$bill_id."");
		$data=$items_query->result_array();
		foreach ($data as $items ) {
				$pay_details['pay_details'][]=array(
						'id_billing_payment'=>$items['id_billing_payment'],
						'type'=>$items['type'],
						'bill_id'=>$items['bill_id'],
						'payment_for'=>$items['payment_for'],
						'payment_amount'=>$items['payment_amount'],
						'cvv'=>$items['cvv'],
						'card_no'=>$items['card_no'],
						'payment_mode'=>$items['payment_mode'],
				);		
			}
		$adv_items=$this->db->query("SELECT a.adjusted_bill_id,sum(a.advance_amount) as advance_amount from ret_billing_advance a where a.adjusted_bill_id=".$bill_id."");
		$pay_details['advance_adj']=$adv_items->row_array();
		return $pay_details;
	}
	function getOtherEstimateItemsDetails($bill_id,$bill_type)
	{
	    $item_details=array();
		$return_data = array("item_details" => array(), "old_matel_details" => array(), "stone_details" => array(), "other_material_details" => array(), "voucher_details" => array(), "chit_details" => array(),"return_details"=>array(),'advance_details'=>array());
		if($bill_type!=5)
		{
			$items_query = $this->db->query("SELECT d.bill_det_id,IFNULL(d.esti_item_id,'') as  esti_item_id,est_itms.esti_id,est_itms.item_type,est_itms.purchase_status,
			ifnull(d.product_id, '') as product_id, ifnull(est_itms.tag_id, '') as tag_id,
			ifnull(d.design_id, '') as design_id, ifnull(pro.hsn_code,'') as hsn_code,
			d.purity as purid,d.size, ifnull(d.uom,'') as uom,d.piece,
			ifnull(d.less_wt,'') as less_wt, d.net_wt, d.gross_wt,
			d.calculation_based_on, d.wastage_percent, d.mc_value, d.mc_type,
			d.item_cost, ifnull(pro.product_short_code, '-') as product_short_code,
			ifnull(pro.product_name, '-') as product_name, est_itms.is_partial,est_itms.discount,
			ifnull(des.design_code, '-') as design_code,
			ifnull(des.design_name, '') as design_name, pur.purity as purname,
			mt.tgrp_id as tax_group_id , tgrp_name, ifnull(c.id_metal,'') as metal_type,
			ifnull(des.fixed_rate,0) as fixed_rate,d.is_non_tag,d.id_lot_inward_detail,
			d.order_no,d.bill_discount,d.item_total_tax,tax.tax_percentage,tax.tgi_calculation,d.total_cgst,total_igst,total_sgst
			From ret_billing b
			Left JOIN ret_bill_details d on d.bill_id=b.bill_id
			LEFT JOIN ret_estimation_items est_itms on est_itms.est_item_id=d.esti_item_id
			LEFT JOIN ret_product_master as pro ON pro.pro_id = d.product_id
			LEFT JOIN ret_category c on c.id_ret_category = pro.cat_id
			LEFT JOIN metal mt on mt.id_metal=c.id_metal
			LEFT JOIN ret_design_master as des ON des.design_no = d.design_id
			LEFT JOIN ret_purity as pur ON pur.id_purity = est_itms.purity
			LEFT JOIN ret_taxgroupmaster as txgrp ON txgrp.tgrp_id = mt.tgrp_id
			LEFT JOIN (select i.tgi_taxcode,i.tgi_tgrpcode,
						GROUP_CONCAT(m.tax_percentage) as tax_percentage,
						GROUP_CONCAT(i.tgi_calculation) as tgi_calculation
						FROM ret_taxgroupitems i
						LEFT JOIN ret_taxmaster m on m.tax_id=i.tgi_taxcode) as tax on tax.tgi_tgrpcode=mt.tgrp_id
			WHERE d.bill_id=".$bill_id."");
			//echo $this->db->last_query();exit;
			$item_details= $items_query->result_array();
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
							'order_no'				=>isset($item['order_no'])?$item['order_no']:NULL
						);
			}
		}
		$old_metal_query=$this->db->query("SELECT s.old_metal_sale_id,s.bill_id,s.purpose,s.metal_type,s.item_type,s.gross_wt,s.stone_wt,s.dust_wt,s.stone_wt,s.wastage_percent,s.wast_wt,s.rate_per_grm as rate_per_gram,s.rate as amount,s.bill_id,s.bill_discount,est_id
		FROM ret_bill_old_metal_sale_details s 
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
					'stone_details'		=>$this->stone_details_by_bill_id($metal['old_metal_sale_id'])
				);
		}
		$return_details=$this->db->query("SELECT bill_items.bill_det_id, bill.bill_id, bill_items.item_type,bill_items.status,bill_items.return_item_cost,
		ifnull(bill_items.product_id, '') as product_id, ifnull(bill_items.tag_id, '') as tag_id, bill_items.esti_item_id,esti_id,
		ifnull(bill_items.design_id, '') as design_id, ifnull(pro.hsn_code,'') as hsn_code,
		bill_items.size, bill_items.uom, bill_items.piece,
		ifnull(bill_items.less_wt,'') as less_wt, bill_items.net_wt, bill_items.gross_wt,
		bill_items.calculation_based_on, bill_items.wastage_percent, bill_items.mc_value, bill_items.mc_type,
		bill_items.item_cost, ifnull(product_short_code, '-') as product_short_code,
		ifnull(product_name, '-') as product_name, bill_items.is_partial_sale,bill_items.bill_discount as discount,bill_items.item_total_tax,
		ifnull(design_code, '-') as design_code,
		ifnull(design_name, '') as design_name, purity as purname,
		mt.tgrp_id as tax_group_id , tgrp_name, ifnull(c.id_metal,'') as metal_type,
		ifnull(des.fixed_rate,0) as fixed_rate,
		if(bill_items.tag_id != null,stn_price,stn_amount) as stone_price,
		if(bill_items.tag_id != null,stn_wgt,stn_wt) as stn_wgt,
		if(bill_items.tag_id != null,othermat_amount,other_mat_price) as othermat_amount,
		if(bill_items.tag_id != null,othermat_wt,other_mat_wgt) as othermat_wt,ref_bill.bill_no as ref_bill_no,ref_bill.bill_date as ref_bill_date,bill_items.total_sgst,bill_items.total_igst,bill_items.total_cgst,tax.tax_percentage
		FROM ret_billing as bill
		LEFT JOIN ret_bill_return_details d on d.bill_id=bill.bill_id
		LEFT JOIN ret_bill_details as bill_items ON bill_items.bill_det_id = d.ret_bill_det_id
		LEFT JOIN(SELECT b.bill_no,b.bill_id,date_format(b.bill_date,'%d-%m-%Y') as bill_date From ret_billing b) as ref_bill on ref_bill.bill_id=d.ret_bill_id
		LEFT JOIN (SELECT esti_id,est_item_id from ret_estimation_items where bil_detail_id is not null and purchase_status=1) as est_itms ON est_itms.est_item_id = bill_items.esti_item_id
		LEFT JOIN (SELECT bill_det_id,sum(price) as stn_price,sum(wt) as stn_wgt FROM `ret_billing_item_stones` GROUP by bill_det_id) as stn_detail ON stn_detail.bill_det_id = bill_items.bill_det_id
		LEFT JOIN (SELECT bill_det_id,sum(price) as other_mat_price,sum(price) as other_mat_wgt FROM `ret_billing_item_other_materials` GROUP by bill_det_id) as est_oth_mat ON est_oth_mat.bill_det_id = bill_items.bill_det_id
		LEFT JOIN (SELECT tag_id,sum(amount) as stn_amount,sum(wt) as stn_wt FROM `ret_taging_stone` GROUP by tag_id) as tag_stn_detail ON tag_stn_detail.tag_id = bill_items.tag_id
		LEFT JOIN (SELECT tag_id,sum(price) as othermat_amount,sum(wt) as othermat_wt FROM `ret_taging_other_materials` GROUP by tag_id) as tag_other_mat ON tag_other_mat.tag_id = bill_items.tag_id
		LEFT JOIN ret_product_master as pro ON pro.pro_id = bill_items.product_id
		LEFT JOIN ret_category c on c.id_ret_category = pro.cat_id
		LEFT JOIN metal mt on mt.id_metal=c.id_metal
		LEFT JOIN ret_design_master as des ON des.design_no = bill_items.design_id
		LEFT JOIN ret_taxgroupmaster as txgrp ON txgrp.tgrp_id = mt.tgrp_id
		LEFT JOIN (select i.tgi_taxcode,i.tgi_tgrpcode,
		GROUP_CONCAT(m.tax_percentage) as tax_percentage,
		GROUP_CONCAT(i.tgi_calculation) as tgi_calculation
		FROM ret_taxgroupitems i
		LEFT JOIN ret_taxmaster m on m.tax_id=i.tgi_taxcode) as tax on tax.tgi_tgrpcode=mt.tgrp_id
		WHERE  bill.bill_id ='".$bill_id."' and (bill.bill_type=7 OR bill.bill_type=3)");
		//print_r($this->db->last_query());exit;
		$return_data["return_details"] = $return_details->result_array();
		
	    $order_adv_details=$this->db->query("SELECT a.bill_adv_id,a.bill_id,a.advance_type,
			a.order_no,a.advance_amount,a.advance_type,a.advance_weight,a.rate_per_gram,a.store_as
		from ret_billing_advance a where a.bill_id=".$bill_id."");
		
		$return_data["advance_details"] = $order_adv_details->result_array();
		
		
		return $return_data;
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
		$emptydata = array();
		foreach ($emptyquery as $field)
		{
			$emptydata[$field->name] = $field->default;
		}
		$emptydata['bill_date'] = date('d-m-Y H:i:s');
		$emptydata['min_pan_amt'] = $min_pan_amt;
		$emptydata['is_pan_required'] = $is_pan_required;
		$emptydata['cus_name'] 			  = '';
		return $emptydata;
	}
	function get_retSettings()
    {
		$min_pan_amt = $this->get_ret_settings('min_pan_amt');
		$is_pan_required = $this->get_ret_settings('is_pan_required');
		$emptydata = array();
		$emptydata['min_pan_amt'] = $min_pan_amt;
		$emptydata['is_pan_required'] = $is_pan_required;
		return $emptydata;
	}
	function createNewCustomer($cusname, $cusmobile, $branch,$id_village,$cus_type,$gst_number)
	{
		$customer_check_query = $this->db->query("SELECT * FROM customer WHERE mobile='".$cusmobile."'");
		if($customer_check_query->num_rows() == 0){
			$insert_data = array("firstname" => $cusname, "id_branch" => $branch, "mobile" => $cusmobile, "username" => $cusmobile, "passwd" => $cusmobile,"id_village"=>$id_village,'cus_type'=>$cus_type,'gst_number'=>$gst_number);
			$cus_insert_id = $this->insertData($insert_data, "customer");
			if(!empty($cus_insert_id)){
				$insert_data["id_customer"] = $cus_insert_id;
				return array("success" => TRUE, "message" => "Customer details added successfully", "response" => $insert_data);
			}else{
				return array("success" => FALSE, "message" => "Could not add customer, please try again", "response" => array());
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



	function getEstimationDetails($estId, $billType, $id_branch, $order_no)
	{
		// 1-Sales, 2-Sales&Purchase, 3-Sales,purchase&Return, 4-Purchase, 5-Order Advance, 6-Advance,7-Sales Return
		
		$dCData=$this->getBranchDayClosingData($id_branch);
		
		$item_details=array();
		$return_data = array("item_details" => array(), "old_matel_details" => array(), "stone_details" => array(), "other_material_details" => array(), "voucher_details" => array(), "chit_details" => array(),'advance_details'=>array());
		if($billType == 5 || ($billType == 1 && $order_no != '') || ($billType == 2 && $order_no != '') || ($billType == 3 && $order_no != '') ){
		$items_query = $this->db->query("SELECT est_itms.est_item_id, esti_id, item_type, est_itms.purchase_status,
		ifnull(est_itms.product_id, '') as product_id, ifnull(est_itms.tag_id, '') as tag_id,
		ifnull(design_id, '') as design_id, ifnull(pro.hsn_code,'') as hsn_code,
		est_itms.purity as purid,IFNULL(size,'') as size, ifnull(uom,'')as uom, piece,
		ifnull(est_itms.less_wt,'') as less_wt, est_itms.net_wt, est_itms.gross_wt,
		est_itms.calculation_based_on, est_itms.wastage_percent, est_itms.mc_value, est_itms.mc_type,
		item_cost, ifnull(product_short_code, '-') as product_short_code,
		ifnull(product_name, '-') as product_name, est_itms.is_partial,est_itms.discount,
		ifnull(design_code, '-') as design_code,
		ifnull(design_name, '') as design_name, pur.purity as purname,
		mt.tgrp_id as tax_group_id , tgrp_name, ifnull(c.id_metal,'') as metal_type,
		ifnull(des.fixed_rate,0) as fixed_rate,
		if(est_itms.tag_id != null,tag_stn_detail.stn_amount,stn_detail.stn_price) as stone_price,IFNULL(tag_stn_detail.certification_cost,0) as certification_cost,
		if(est_itms.tag_id != null,stn_wgt,stn_wt) as stn_wgt,
		if(est_itms.tag_id != null,othermat_amount,other_mat_price) as othermat_amount,
		if(est_itms.tag_id != null,othermat_wt,other_mat_wgt) as othermat_wt,
		0 as advance_paid,order_no,est_itms.is_non_tag,concat(cus.firstname,' ',cus.mobile) as cus_name,cus.id_customer,
		v.village_name,if(cus.is_vip=1,'Yes','No') as vip,
		(select count(sa.id_scheme_account) from scheme_account sa left join customer cust on cust.id_customer=sa.id_customer) as accounts,
		pro.min_wastage,pro.max_wastage,pro.stock_type,nt.no_of_piece as available_pieces,nt.gross_wt as available_gross_wt
		FROM customerorder as cus_order
		LEFT JOIN ret_estimation as est ON est.estimation_id = cus_order.est_id
		LEFT JOIN ret_estimation_items as est_itms ON est_itms.esti_id = est.estimation_id
		LEFT JOIN (SELECT est_item_id,sum(price) as stn_price,sum(wt) as stn_wgt FROM `ret_estimation_item_stones` GROUP by est_item_id) as stn_detail ON stn_detail.est_item_id = est_itms.est_item_id
		LEFT JOIN (SELECT est_item_id,sum(price) as other_mat_price,sum(price) as other_mat_wgt FROM `ret_estimation_item_other_materials` GROUP by est_item_id) as est_oth_mat ON est_oth_mat.est_item_id = est_itms.est_item_id
		LEFT JOIN (SELECT tag_id,sum(amount) as stn_amount,sum(certification_cost) as certification_cost,sum(wt) as stn_wt FROM `ret_taging_stone` GROUP by tag_id) as tag_stn_detail ON tag_stn_detail.tag_id = est_itms.tag_id
		LEFT JOIN (SELECT tag_id,sum(price) as othermat_amount,sum(wt) as othermat_wt FROM `ret_taging_other_materials` GROUP by tag_id) as tag_other_mat ON tag_other_mat.tag_id = est_itms.tag_id
		LEFT JOIN ret_product_master as pro ON pro.pro_id = est_itms.product_id
		LEFT JOIN ret_category c on c.id_ret_category = pro.cat_id
		LEFT JOIN metal mt on mt.id_metal=c.id_metal
		LEFT JOIN ret_design_master as des ON des.design_no = est_itms.design_id
		LEFT JOIN ret_purity as pur ON pur.id_purity = est_itms.purity
		LEFT JOIN ret_taxgroupmaster as txgrp ON txgrp.tgrp_id = mt.tgrp_id
		LEFT JOIN customer cus on cus.id_customer=est.cus_id
		LEFT JOIN village v on v.id_village=cus.id_village
		LEFT JOIN ret_nontag_item nt on nt.design=est_itms.design_id
		WHERE ".(!empty($id_branch)? "est.id_branch=".$id_branch.' AND' :'')." 
		".(!empty($estId)? "est.esti_no=".$estId.' AND' :'')."  cus_order.order_no ='".$order_no."' 
		AND est_itms.item_type!= 0 AND est_itms.is_non_tag=0 AND est_itms.est_item_id IS NOT NULL
		AND date(est.estimation_datetime)=".$dCData['entry_date']."
		order by est_itms.esti_id DESC");
		$item_details = $items_query->result_array();
		}
		else if($billType == 1 || $billType == 2 || $billType == 3){
		$items_query = $this->db->query("SELECT est_itms.est_item_id, esti_id, item_type, est_itms.purchase_status,
		ifnull(est_itms.product_id, '') as product_id, ifnull(est_itms.tag_id, '') as tag_id,
		ifnull(design_id, '') as design_id, ifnull(pro.hsn_code,'') as hsn_code,
		est_itms.purity as purid, IFNULL(size,'') as size, ifnull(uom,'') as uom, piece,
		ifnull(est_itms.less_wt,'') as less_wt, est_itms.net_wt, est_itms.gross_wt,
		est_itms.calculation_based_on, est_itms.wastage_percent, est_itms.mc_value, est_itms.mc_type,
		item_cost, ifnull(product_short_code, '-') as product_short_code,
		ifnull(product_name, '-') as product_name, est_itms.is_partial,est_itms.discount,
		ifnull(design_code, '-') as design_code,
		ifnull(design_name, '') as design_name, pur.purity as purname,
		mt.tgrp_id as tax_group_id , tgrp_name, ifnull(c.id_metal,'') as metal_type,
		ifnull(des.fixed_rate,0) as fixed_rate,
		if(est_itms.tag_id != '',tag_stn_detail.stn_amount,stn_detail.stn_price) as stone_price,IFNULL(tag_stn_detail.certification_cost,0) as certification_cost,
		if(est_itms.tag_id != null,stn_wgt,stn_wt) as stn_wgt,
		if(est_itms.tag_id != null,othermat_amount,other_mat_price) as othermat_amount,
		if(est_itms.tag_id != null,othermat_wt,other_mat_wgt) as othermat_wt,est_itms.is_non_tag,concat(cus.firstname,' ',cus.mobile) as cus_name,cus.id_customer,
		v.village_name,if(cus.is_vip=1,'Yes','No') as vip,
		(select count(sa.id_scheme_account) from scheme_account sa left join customer cust on cust.id_customer=sa.id_customer) as accounts,
		pro.min_wastage,pro.max_wastage,pro.stock_type,nt.no_of_piece as available_pieces,nt.gross_wt as available_gross_wt
		FROM ret_estimation as est 
		LEFT JOIN ret_estimation_items as est_itms ON est_itms.esti_id = est.estimation_id
		LEFT JOIN (SELECT est_item_id,sum(price) as stn_price,sum(wt) as stn_wgt FROM `ret_estimation_item_stones` GROUP by est_item_id) as stn_detail ON stn_detail.est_item_id = est_itms.est_item_id
		LEFT JOIN (SELECT est_item_id,sum(price) as other_mat_price,sum(price) as other_mat_wgt FROM `ret_estimation_item_other_materials` GROUP by est_item_id) as est_oth_mat ON est_oth_mat.est_item_id = est_itms.est_item_id
		LEFT JOIN (SELECT tag_id,sum(amount) as stn_amount,sum(certification_cost) as certification_cost,sum(wt) as stn_wt FROM `ret_taging_stone` GROUP by tag_id) as tag_stn_detail ON tag_stn_detail.tag_id = est_itms.tag_id
		LEFT JOIN (SELECT tag_id,sum(price) as othermat_amount,sum(wt) as othermat_wt FROM `ret_taging_other_materials` GROUP by tag_id) as tag_other_mat ON tag_other_mat.tag_id = est_itms.tag_id
		LEFT JOIN ret_product_master as pro ON pro.pro_id = est_itms.product_id
		LEFT JOIN ret_category c on c.id_ret_category = pro.cat_id
		LEFT JOIN metal mt on mt.id_metal=c.id_metal
		LEFT JOIN ret_design_master as des ON des.design_no = est_itms.design_id
		LEFT JOIN ret_purity as pur ON pur.id_purity = est_itms.purity
		LEFT JOIN ret_taxgroupmaster as txgrp ON txgrp.tgrp_id = mt.tgrp_id
		LEFT JOIN customer cus on cus.id_customer=est.cus_id
		LEFT JOIN village v on v.id_village=cus.id_village
		LEFT JOIN ret_nontag_item nt on nt.design=est_itms.design_id
		WHERE ".(!empty($id_branch)? "est.id_branch=".$id_branch.' AND' :'')." 
		est.esti_no ='".$estId."'  AND est_itms.est_item_id IS NOT NULL
		AND date(est.estimation_datetime)='".$dCData['entry_date']."'
		order by est_itms.esti_id DESC");
		// echo $this->db->_error_message(); exit;
		//print_r($this->db->last_query());exit;
		$item_details = $items_query->result_array();
		} 
		if($billType == 2 || $billType == 3 || $billType == 4 || $billType == 5){
		$old_matel_query = $this->db->query("SELECT old_metal_sale_id,est_old.est_id, purchase_status,bill_id,
		id_category, type, item_type, gross_wt,net_wt,met.id_metal,
		ifnull(dust_wt,0.000) as dust_wt,ifnull(stone_wt,0.000) as stone_wt,
		round((ifnull(dust_wt,0.000) - ifnull(stone_wt,0.000)),3) as less_wt,purpose,
		if(type = 1, 'Melting', 'Retag') as reusetype,
		if(item_type = 1, 'Ornament', if(item_type = 2, 'Coin', if(item_type = 3, 'Bar',''))) as receiveditem, est_old.purity as purid, wastage_percent,
		wastage_wt, rate_per_gram, amount,
		pur.purity as purname, met.metal,ifnull(stn_detail.stn_price,0) as stone_price,concat(cus.firstname,' ',cus.mobile) as cus_name,cus.id_customer,
		v.village_name,if(cus.is_vip=1,'Yes','No') as vip,
		(select count(sa.id_scheme_account) from scheme_account sa left join customer cust on cust.id_customer=sa.id_customer) as accounts
		FROM ret_estimation as est
		LEFT JOIN ret_estimation_old_metal_sale_details as est_old ON est_old.est_id = est.estimation_id
		LEFT JOIN ret_purity as pur ON pur.id_purity = est_old.purity
		LEFT JOIN metal as met ON met.id_metal = est_old.id_category
		LEFT JOIN customer cus on cus.id_customer=est.cus_id
		LEFT JOIN village v on v.id_village=cus.id_village
		LEFT JOIN (SELECT est_id,sum(price) as stn_price,sum(wt) as stn_wgt FROM `ret_esti_old_metal_stone_details` GROUP by est_id) as stn_detail ON stn_detail.est_id = est_old.est_id
		WHERE ".(!empty($id_branch)? "est.id_branch=".$id_branch.' AND' :'')." 
		est.esti_no ='".$estId."' AND old_metal_sale_id IS NOT NULL 
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
					'stone_details'		=>$this->get_old_metal_stone_details($metal['old_metal_sale_id'])
				);
		}
		}
		if(($billType == 5 || $billType == 1)&& ($order_no!=''))
		{
		    $advance=$this->db->query("SELECT a.order_no,a.advance_amount as paid_advance,
			a.advance_weight as paid_weight,s.metal_type,a.store_as,a.advance_type,a.rate_calc
			from ret_billing b
			LEFT JOIN ret_billing_advance a on a.bill_id=b.bill_id
			LEFT JOIN ret_bill_old_metal_sale_details s on s.old_metal_sale_id=a.old_metal_sale_id
			where a.is_adavnce_adjusted=0 and a.order_no='".$order_no."'");
			$return_data["advance_details"] = $advance->result_array();
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
						'est_item_id'			=>$item['est_item_id'],
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
						'othermat_amount'		=>$item['othermat_amount'],
						'othermat_wt'			=>$item['othermat_wt'],
						'stock_type'			=>$item['stock_type'],
						'piece'					=>$item['piece'],
						'product_id'			=>$item['product_id'],
						'product_name'			=>$item['product_name'],
						'product_short_code'	=>$item['product_short_code'],
						'purchase_status'		=>$item['purchase_status'],
						'purid'					=>$item['purid'],
						'purname'				=>$item['purname'],
						'size'					=>$item['size'],
						'stn_wgt'				=>$item['stn_wgt'],
						'stone_price'			=>$item['stone_price'],
						'certification_cost'	=>$item['certification_cost'],
						'tag_id'				=>$item['tag_id'],
						'tax_group_id'			=>$item['tax_group_id'],
						'tgrp_name'				=>$item['tgrp_name'],
						'uom'					=>$item['uom'],
						'wastage_percent'		=>$item['wastage_percent'],
						'max_wastage'		    =>$item['max_wastage'],
						'min_wastage'		    =>$item['min_wastage'],
						'cus_name'		        =>$item['cus_name'],
					    'id_customer'		    =>$item['id_customer'],
					    'chit_cus'		        =>($item['accounts']==0 ? 'No':'Yes'),
					    'vip_cus'		        =>$item['vip'],
					    'village_name'		    =>$item['village_name'],
					    'available_pieces'		=>$item['available_pieces'],
					    'available_gross_wt'	=>$item['available_gross_wt'],
						'order_no'				=>isset($item['order_no'])?$item['order_no']:NULL,
						'stone_details'			=>($item['tag_id']=='' ? $this->get_stone_details($item['est_item_id']):$this->get_tag_stone_details($item['tag_id']))
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
						   est_st.stone_id, pieces, wt, price, 
                           stone_name, stone_code, uom_name, uom_short_code 
						   FROM ret_estimation_item_stones as est_st 
                           LEFT JOIN ret_stone as st ON st.stone_id = est_st.stone_id 
                           LEFT JOIN ret_uom as um ON um.uom_id = st.uom_id 
						   WHERE est_st.est_item_id = '".$est_item_id."'");
			return $est_stone_query->result_array();
	}
	function get_tag_stone_details($tag_id)
	{
		$tag_stone_query=$this->db->query("SELECT tag_stone_id,tag_id, pieces, wt, amount,stone_id,certification_cost
			FROM ret_taging_stone as s 
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
		$return_data=array();
		$tag = $this->db->query("SELECT tag.tag_id, 
				tag_code, tag_datetime, tag.tag_type, tag_lot_id, ifnull(pro.hsn_code,'') as hsn_code, 
				ifnull(tag.design_id,'') as design_id, cost_center, tag.purity, ifnull(tag.size,'') as size, ifnull(uom,'')uom, piece, tag.less_wt, tag.net_wt, tag.gross_wt, ifnull(tag.less_wt,0) as less_wt,
				tag.calculation_based_on, retail_max_wastage_percent, tag_mc_type,retail_max_mc,tag_mc_value,
				halmarking, sales_value, mt.tgrp_id, tag.tag_status, product_name, product_short_code, lot_product, pur.purity as purname, ifnull(c.id_metal,'') as metal_type,
				tgrp_name, ifnull(design_code, '-') as design_code, 
				ifnull(design_name, '') as design_name,
				stn_amount,stn_wt,othermat_amount,othermat_wt,pro.disc_type   				
				FROM ret_taging as tag 
				Left join ret_lot_inwards_detail ld on tag.id_lot_inward_detail = ld.id_lot_inward_detail					
				LEFT JOIN ret_product_master as pro ON pro.pro_id = ld.lot_product  
                LEFT JOIN ret_category c on c.id_ret_category=pro.cat_id
				LEFT JOIN metal mt on mt.id_metal=c.id_metal 
				LEFT JOIN ret_design_master as des ON des.design_no = tag.design_id 
				LEFT JOIN ret_taxgroupmaster as txgrp ON txgrp.tgrp_id = mt.tgrp_id 
				LEFT JOIN ret_purity as pur ON pur.id_purity = tag.purity 
				LEFT JOIN (SELECT tag_id,sum(amount) as stn_amount,sum(wt) as stn_wt FROM `ret_taging_stone` GROUP by tag_id) as tag_stn_detail ON tag_stn_detail.tag_id = tag.tag_id
				LEFT JOIN (SELECT tag_id,sum(price) as othermat_amount,sum(wt) as othermat_wt FROM `ret_taging_other_materials` GROUP by tag_id) as tag_other_mat ON tag_other_mat.tag_id = tag.tag_id
				WHERE tag.tag_status=0 and tag.tag_id = '".$SearchTxt."' ".($_POST['id_branch']!='' ? "and tag.current_branch=".$_POST['id_branch']."" :'')." ");
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
		if($branch_id!='')
		{
		    $branch_query = $this->db->query("SELECT id_branch, name FROM branch WHERE id_branch = $branch_id");
    		if($branch_query->num_rows() > 0){
    			$branch_name = $branch_query->row()->name;
    		}
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
	function code_number_generator()
	{
		$lastno = $this->get_last_code_no();
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
	function get_last_code_no()
    {
		$sql = "SELECT max(bill_no) as lastBill_no FROM ret_billing ORDER BY bill_id DESC";
		return $this->db->query($sql)->row()->lastBill_no;	
	}
	function get_payModes()
    {
		$sql = "SELECT * FROM payment_mode where show_in_pay = 1 ORDER BY sort_order";
		return $this->db->query($sql)->result_array();	
	} 
	//chit account
	function get_closed_accounts($SearchTxt,$bill_cus_id){
		$data = $this->db->query("SELECT  c.mobile,sa.id_scheme_account as value,sa.id_scheme_account as label,sa.closing_balance,sa.is_closed,s.scheme_type,s.scheme_name,sa.closing_amount
				from scheme_account sa
				left join customer c on c.id_customer=sa.id_customer
				left join scheme s on s.id_scheme=sa.id_scheme
				WHERE sa.id_scheme_account LIKE '%".$SearchTxt."%' and sa.is_closed=1 and sa.is_utilized=0");
		//echo $this->db->last_query();exit;
		return $data->result_array();
	}
	//Adv Adj
	function get_advance_details($bill_cus_id)
	{
		$data=$this->db->query("SELECT w.id_ret_wallet,(c.amount-IFNULL(d.amount,0)) as amount,(c.weight-IFNULL(d.weight,0)) as weight,d.amount as amt
						FROM ret_wallet w
						LEFT JOIN (Select t.id_ret_wallet,sum(t.amount) as amount,sum(t.weight) as weight from ret_wallet_transcation t
						    where t.transaction_type=0) c on c.id_ret_wallet=w.id_ret_wallet
						LEFT JOIN (Select t.id_ret_wallet,sum(t.amount) as amount,sum(t.weight) as weight from ret_wallet_transcation t
						    where t.transaction_type=1) d on d.id_ret_wallet=w.id_ret_wallet
						where w.id_customer=".$bill_cus_id."");
		return $data->row_array();
	}
	//Adv Adj
		function getBillData($bill_no, $billType, $id_branch)
		{
		// 1-Sales, 2-Sales&Purchase, 3-Sales,purchase&Return, 4-Purchase, 5-Order Advance, 6-Advance,7-Sales Return
		$return_data = array("item_details" => array(), "old_matel_details" => array(), "bill_details" => array());
		$items_query = $this->db->query("SELECT bill_items.bill_det_id, bill.bill_id, bill_items.item_type,bill_items.status,
		ifnull(bill_items.product_id, '') as product_id, ifnull(bill_items.tag_id, '') as tag_id, bill_items.esti_item_id,esti_id ,
		ifnull(bill_items.design_id, '') as design_id, ifnull(pro.hsn_code,'') as hsn_code,
		bill_items.size, bill_items.uom, bill_items.piece,
		ifnull(bill_items.less_wt,'') as less_wt, bill_items.net_wt, bill_items.gross_wt,
		bill_items.calculation_based_on, bill_items.wastage_percent, bill_items.mc_value, bill_items.mc_type,
		bill_items.item_cost, ifnull(product_short_code, '-') as product_short_code,
		ifnull(product_name, '-') as product_name, bill_items.is_partial_sale,bill_items.bill_discount as discount,bill_items.item_total_tax,
		ifnull(design_code, '-') as design_code,
		ifnull(design_name, '') as design_name, purity as purname,
		mt.tgrp_id as tax_group_id , tgrp_name, ifnull(c.id_metal,'') as metal_type,
		ifnull(des.fixed_rate,0) as fixed_rate,
		if(bill_items.tag_id != null,stn_price,stn_amount) as stone_price,
		if(bill_items.tag_id != null,stn_wgt,stn_wt) as stn_wgt,
		if(bill_items.tag_id != null,othermat_amount,other_mat_price) as othermat_amount,
		if(bill_items.tag_id != null,othermat_wt,other_mat_wgt) as othermat_wt,concat(cus.firstname,' ',cus.mobile) as cus_name,v.village_name,
		if(cus.is_vip=1,'Yes','No') as vip,
		(select count(sa.id_scheme_account) from scheme_account sa left join customer cust on cust.id_customer=sa.id_customer) as accounts
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
		LEFT JOIN ret_taxgroupmaster as txgrp ON txgrp.tgrp_id = mt.tgrp_id
		LEFT JOIN customer cus on cus.id_customer=bill.bill_cus_id
		LEFT JOIN village v on v.id_village=cus.id_village
		WHERE ".(!empty($id_branch)? "bill.id_branch=".$id_branch.' AND' :'')." bill.bill_no ='".$bill_no."'  AND bill_items.bill_det_id IS NOT NULL");
    	//print_r($this->db->last_query());exit;
		$return_data["item_details"] = $items_query->result_array();
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
		mt.tgrp_id as tax_group_id , tgrp_name, ifnull(c.id_metal,'') as metal_type,
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
		LEFT JOIN ret_taxgroupmaster as txgrp ON txgrp.tgrp_id = mt.tgrp_id
		LEFT JOIN customer cus on cus.id_customer=bill.bill_cus_id
		LEFT JOIN village v on v.id_village=cus.id_village
		WHERE ".(!empty($id_branch)? "bill.id_branch=".$id_branch.' AND' :'')." (".$bill.")  AND bill_items.bill_det_id IS NOT NULL");
    	//print_r($this->db->last_query());exit;
		$return_data["item_details"] = $items_query->result_array();
		return $return_data;
		}
		
		function getCreditBillDetails($bill_no,$bill_type,$id_branch)
		{
            $return_data = array("bill_details" => array());
            $items_query = $this->db->query("Select b.bill_id,b.tot_bill_amount,b.tot_amt_received,b.bill_cus_id as id_customer,
            concat(cus.firstname,' ',cus.mobile) as cus_name,v.village_name,
            if(cus.is_vip=1,'Yes','No') as vip,
            (select count(sa.id_scheme_account) from scheme_account sa left join customer cust on cust.id_customer=sa.id_customer) as accounts
            from ret_billing b
            LEFT JOIN customer cus on cus.id_customer=b.bill_cus_id
		    LEFT JOIN village v on v.id_village=cus.id_village
            where b.is_credit=1 and b.credit_status=2 and b.bill_no=".$bill_no." and b.id_branch=".$id_branch."");
            $return_data['bill_details']= $items_query->row_array();
            if(!empty($return_data['bill_details']))
            {
                $return_data['bill_details']['credit_pay_amount']=$this->get_credit_pay_amount($return_data["bill_details"]['bill_id']);
            }
            return $return_data;
		}
		
		function get_credit_pay_amount($bill_id)
		{
		    $sql="SELECT IFNULL(sum(tot_amt_received),0) as paid_amount from ret_billing b where b.ref_bill_id=".$bill_id."";
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
			$sql = $this->db->query("Select  c.id_company,c.company_name,c.gst_number,c.short_code,c.pincode,c.mobile,c.whatsapp_no,c.phone,c.email,c.website,c.address1,c.address2,c.id_country,c.id_state,c.id_city,ct.name as city,s.name as state,cy.name as country,cs.currency_symbol,cs.currency_name,cs.mob_code,cs.mob_no_len,c.mail_server,c.mail_password,c.send_through,c.mobile1,c.phone1,c.smtp_user,c.smtp_pass,c.smtp_host,c.server_type,cs.login_branch
			from company c
			join chit_settings cs
			left join country cy on (c.id_country=cy.id_country)
			left join state s on (c.id_state=s.id_state)
			left join city ct on (c.id_city=ct.id_city)");
		}
		else
		{
			$sql=$this->db->query("select b.name,b.address1,b.address2,c.company_name,
				cy.name as country,ct.name as city,s.name as state,b.pincode,s.id_state
				from branch b
				join company c
				left join country cy on (b.id_country=cy.id_country)
				left join state s on (b.id_state=s.id_state)
				left join city ct on (b.id_city=ct.id_city)");
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
		if($issue_to==1)
		{
			$data=$this->db->query("select e.id_employee as value,concat(e.firstname,'-',e.mobile) as label,e.mobile,e.firstname as barrower_name
			from employee e
			where e.mobile like '%".$SearchTxt."%' OR e.firstname like '%".$SearchTxt."%' ".($id_branch!=''  && $id_branch>0 ? " and e.id_branch=".$id_branch."":'')."");
		}
		else{
			$data=$this->db->query("select c.id_customer as value,concat(c.firstname,'-',c.mobile) as label,c.mobile,c.firstname as barrower_name
			from customer c
			where c.mobile like '%".$SearchTxt."%' OR c.firstname like '%".$SearchTxt."%'
			".($id_branch!='' && $id_branch>0 ? " and c.id_branch=".$id_branch."":'')."");
		}
		 return  $data->result_array();
	}

	function getCreditBill($searchTxt,$id_branch)
	{
		$data=$this->db->query("SELECT r.id_issue_receipt as label,r.id_issue_receipt as value,r.issue_to,r.id_customer,r.id_employee,r.amount,
			(Select ifnull(sum(rct.amount),0)  FROM ret_issue_receipt rct where rct.receipt_for=".$searchTxt.") as paid_amount
			FROM ret_issue_receipt r
			where r.is_collect=0 and r.id_issue_receipt=".$searchTxt." ".($id_branch!='' ? "and r.id_branch=".$id_branch." " :'')."");
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
	function ajax_getReceiptlist()
	{
		$data=$this->db->query("SELECT if(r.type=1,'Issue','Receipt') as type,IFNULL(e.firstname,'-') as emp_name,IFNULL(c.firstname,'-') as cus_name,IFNULL(r.amount,0) as amount,
			date_format(r.created_on,'%d-%m-%Y') as date_add,r.issue_to,r.id_issue_receipt,IFNULL(r.weight,0) as weight
			FROM ret_issue_receipt r
			left join ret_account_head a on r.issue_to=a.id_acc_head
			LEFT join customer c on c.id_customer=r.id_customer
			LEFT JOIN employee e on e.id_employee=r.id_employee
			where r.type=2 ");
		return  $data->result_array();
	}
	function ajax_getIssuetist()
	{
		$data=$this->db->query("SELECT if(r.type=1,'Issue','Receipt') as type,
			IFNULL(r.amount,0) as amount,if(r.issue_to=1,e.firstname,if(r.issue_to=2,c.firstname,a.name)) as barrower_name,
			date_format(r.created_on,'%d-%m-%Y') as date_add,r.issue_to,r.id_issue_receipt,
			if(r.is_collect=1,'Collected','Pending') status
			FROM ret_issue_receipt r
			left join ret_account_head a on r.id_acc_head=a.id_acc_head
			LEFT join customer c on c.id_customer=r.id_customer
			LEFT JOIN employee e on e.id_employee=r.id_employee
			where r.type=1");
		return  $data->result_array();
	}
	
	function get_issue_details($id)
	{
		$data=$this->db->query("SELECT r.id_issue_receipt,if(r.type=1,'Issue','Receipt') as issue_type,r.mobile,r.issue_to,r.id_customer,r.id_employee,r.issue_type,r.amount,r.weight,r.narration,
		if(r.issue_to=1,e.firstname,if(r.issue_to=2,c.firstname,a.name)) as name,if(r.receipt_type=1,'Credit Collection Receipt',if(r.receipt_type=2,'Advance Receipt','Issue Receipt')) as receipt_type,
		IFNULL(r.receipt_as,1) as receipt_as
			FROM ret_issue_receipt r
			LEFT JOIN customer c on c.id_customer=r.id_customer
			LEFT JOIN employee e on e.id_employee=r.id_employee
			left join ret_account_head a on r.id_acc_head=a.id_acc_head
			WHERE r.type=1 and r.id_issue_receipt=".$id."");
			//print_r($this->db->last_query());exit;
		return $data->row_array();
	}

	function get_receipt_details($id)
	{
		$sql=$this->db->query("SELECT r.id_customer,r.id_employee,r.issue_type,r.amount,
			r.weight,c.firstname as name,c.mobile,r.narration,r.id_issue_receipt,if(r.receipt_type=1,'Credit Collection Receipt',if(r.receipt_type=2,'Advance Receipt','Issue Receipt')) as receipt_type,
			r.receipt_type as rct_type,r.receipt_for,IFNULL(r.receipt_as,1) as receipt_as,r.weight
			FROM ret_issue_receipt r
			LEFT JOIN customer c on c.id_customer=r.id_customer
			LEFT JOIN employee e on e.id_employee=r.id_employee
			WHERE r.type=2 and r.id_issue_receipt=".$id."");
		$data=$sql->row_array();
		if($data['rct_type']==1)
		{
		   $issue=$this->get_issue_details($data['receipt_for']);
		   $data['mobile']=$issue['mobile'];
		   $data['name']=$issue['name'];
		}
		return $data;
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
		$items_query = $this->db->query("SELECT b.bill_det_id,IFNULL(b.tag_id,'') as tag_id,
			b.piece as no_of_piece,b.gross_wt as gross_wt,b.net_wt as net_wt,bill.id_branch,b.product_id,b.design_id
			FROM ret_billing bill
			LEFT JOIN ret_bill_details b on b.bill_id=bill.bill_id
			where b.bill_id=".$bill_id."");
		return $items_query->result_array();
	}
	
	function checkNonTagItemExist($data){
		$r = array("status" => FALSE);
        $sql = "SELECT id_nontag_item FROM ret_nontag_item WHERE product=".$data['id_product']." ".($data['id_design']!='' ? " and design=".$data['id_design']."" :'')." AND branch=".$data['id_branch']; 		$res = $this->db->query($sql);
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
	
}
?>
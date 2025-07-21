<?php
if( ! defined('BASEPATH')) exit('No direct script access allowed');
class Payment_model extends CI_Model
{
	const ACC_TABLE 		= "scheme_account";
	const CUS_TABLE			= "customer";
	const SCH_TABLE			= "scheme";
	const SCH_GROUP_TABLE	= "scheme_group";
	const PAY_TABLE			= "payment";
	const CUS_REG_TABLE		= "customer_reg"; 
	const MOD_TABLE			= "payment_mode";
	const DC_TABLE			= "daily_collection";
	//const STAT_TABLE		= "payment_status";
	const SETT_TABLE		= "settlement";  
	const SETT_DET_TABLE	= "settlement_detail"; 
	const PAY_STATUS	    = "payment_status_message"; 
	const BRANCH	    	= "branch";
	const EMPLOYEE_TABLE	= "employee";
	const PURCH_CUS_TABLE   = "purchase_customer";
	const PURCH_PAY_TABLE   = "purchase_payment";
	const TRANS_TABLE		= "transaction";
	
	function __construct()
    {
        parent::__construct();
    }
    
     function get_receipt_no($id_scheme='',$branch='')  // branch wise Receipt Num generaTION  based on the settings//HH
    {
        /* 
            scheme_wise_receipt settings done by HH
            1 - Common,
            2 - branch wise, 
            3 - Scheme-wise,
            4 - Scheme-wise with branch wise
          
        */
        $data = $this->get_settings(); 
        if($data['branch_settings']==1){ // Branch Enabled
      
            if($data['scheme_wise_receipt'] == 2 && $branch >0){  // 2 - branch wise Receipt number
                       $sql = "Select max(p.receipt_no) as receipt_no
				From payment p
				left join scheme_account sa on sa.id_scheme_account=p.id_scheme_account
				left join scheme s on s.id_scheme=sa.id_scheme
				join chit_settings cs 
				Where p.payment_status=1   and sa.id_branch=".$branch." ";
				
            }
            else if($data['scheme_wise_receipt'] == 4){ // 4 - Scheme-wise with branch wise Receipt number
                $sql = "Select max(p.receipt_no) as receipt_no
				From payment p
				left join scheme_account sa on sa.id_scheme_account=p.id_scheme_account
				left join scheme s on s.id_scheme=sa.id_scheme
				join chit_settings cs 
				Where p.payment_status=1 and sa.id_scheme=".$id_scheme." AND sa.id_branch=".$branch."  ";
           
              }
              else  // If other cases fails,generate 1 - common Receipt number
            {
                $sql = "Select max(p.receipt_no) as receipt_no
				From payment p
				left join scheme_account sa on sa.id_scheme_account=p.id_scheme_account
				left join scheme s on s.id_scheme=sa.id_scheme
				join chit_settings cs 
				Where p.payment_status=1 "; 
			//	 print_r($sql);exit;
            }
         
        } else{ 
            
            
            if($data['scheme_wise_receipt'] == 3){   // 3 - Scheme-wise Receipt number 
                
		        $sql = "Select max(p.receipt_no) as receipt_no
				From payment p
				left join scheme_account sa on sa.id_scheme_account=p.id_scheme_account
				left join scheme s on s.id_scheme=sa.id_scheme
				join chit_settings cs 
				Where p.payment_status=1 and sa.id_scheme=".$id_scheme."";
            }
            else  // If other cases fails,generate 1 - common Receipt number
            {
                $sql = "Select max(p.receipt_no) as receipt_no
				From payment p
				left join scheme_account sa on sa.id_scheme_account=p.id_scheme_account
				left join scheme s on s.id_scheme=sa.id_scheme
				join chit_settings cs 
				Where p.payment_status=1 "; 
				 print_r($sql);exit;
            }
				
	    }
	         //print_r($sql);exit;
	        return $this->db->query($sql)->row()->receipt_no;
    }
	
	function get_receipt_no_settings()
	{
		$sql="Select c.scheme_wise_receipt FROM chit_settings c where c.id_chit_settings = 1";
		return $this->db->query($sql)->row()->scheme_wise_receipt;
		
	}
	
    function entry_date_settings()
	{
		$sql="Select c.edit_custom_entry_date FROM chit_settings c where c.id_chit_settings = 1";
		return $this->db->query($sql)->row()->edit_custom_entry_date;
		
	}
    
    function payment_list($id="",$limit="",$type="")
	
   {
		$branchWiseLogin=$this->session->userdata('branchWiseLogin');
			$id_branch=$this->session->userdata('id_branch');
			$uid=$this->session->userdata('uid');
	
	
		if($id!=NULL)
		{
			$sql="SELECT
					cs.has_lucky_draw,s.is_lucky_draw,s.code,IFNULL(sa.group_code,'')as scheme_group_code,p.added_by,
					  p.id_payment,p.is_offline,sa.id_branch,sa.ref_no,sa.id_scheme_account,
					  sa.account_name,p.payment_amount,p.id_branch,
					  if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,c.lastname,c.firstname,
					  c.mobile,c.email,b.name as payment_branch,
					  IFNULL(sa.scheme_acc_number,'Not Allocated') as scheme_acc_number,p.due_type,p.act_amount,
					  s.code,s.scheme_name,
					  p.id_employee,IFNULL(e.emp_code,'-')as emp_code,
                      if(e.lastname is null,e.firstname,concat(e.firstname,' ',e.lastname)) as employee,IFNULL(e.emp_code,'-')as emp_code,
					  if(s.scheme_type=0,'Amount',if(s.scheme_type=1,'Weight','Amount to Weight')) as scheme_type,
					  IFNULL(p.payment_amount,'-') as payment_amount,
					  IFNULL(if(p.metal_rate=0,'-',p.metal_rate), '-') as metal_rate,
					  IFNULL(if(p.metal_weight='0.0000','-',p.metal_weight), '-') as metal_weight,
					  IFNULL(Date_format(p.date_payment,'%d-%m%-%Y'),'-') as date_payment,
			          p.payment_type,
					  IFNULL(p.payment_mode,'-') as payment_mode,
					  IFNULL(sa.scheme_acc_number,'') as msno,
					  IFNULL(p.bank_acc_no,'-') as bank_acc_no,
					  IFNULL(p.bank_name,'-')as bank_name,
					  IFNULL(p.bank_IFSC,'-') as bank_IFSC,
					  IFNULL(p.bank_branch,'-') as bank_branch,
					  
					  
					  IFNULL(cs.receipt_no_set,'-') as receipt_no_set,
					  IFNULL(p.id_transaction,'-') as id_transaction,
					  IFNULL(p.payu_id,'-') as payu_id ,
					  IFNULL(p.card_no,'-') as card_no,
					  psm.payment_status as payment_status,
					  p.payment_status as id_status,
					  psm.color as status_color,
					  IFNULL(p.payment_ref_number,'-') as payment_ref_number,
					if(cs.receipt_no_set=1 || cs.receipt_no_set=3 && p.receipt_no is null,'',p.receipt_no) as receipt_no,
					  IFNULL(p.remark,'-') as remark,cs.currency_name,cs.currency_symbol
				FROM payment p
				join chit_settings cs
				left join scheme_account sa on(p.id_scheme_account=sa.id_scheme_account)
				Left Join employee e On (e.id_employee=p.id_employee)
				Left Join customer c on (sa.id_customer=c.id_customer)
				left join scheme s on(sa.id_scheme=s.id_scheme)
			    Left Join payment_mode pm on (p.payment_mode=pm.id_mode)		
			    Left Join branch b on (p.id_branch=b.id_branch)		
			   
			    Left Join payment_status_message psm On (p.payment_status=psm.id_status_msg)".($id!=NULL?' Where p.id_payment='.$id:'')." 
				 ORDER BY p.date_payment DESC ";
				
				$payment=$this->db->query($sql);
			return $payment->row_array();
		}
		else
		{
			$sql="SELECT
					cs.has_lucky_draw,s.is_lucky_draw,s.code,IFNULL(sa.group_code,'')as scheme_group_code,p.added_by,
					  p.id_payment,p.is_offline,sa.id_branch,sa.ref_no,sa.id_scheme_account,
					  sa.account_name,p.payment_amount,
					  if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,c.lastname,c.firstname,
					  c.mobile,c.email,
					  IFNULL(sa.scheme_acc_number,'Not Allocated') as scheme_acc_number,p.due_type,p.act_amount,
					  s.code,s.scheme_name,
					  p.id_employee,IFNULL(e.emp_code,'-')as emp_code,
                      if(e.lastname is null,e.firstname,concat(e.firstname,' ',e.lastname)) as employee, 
					  if(s.scheme_type=0,'Amount',if(s.scheme_type=1,'Weight','Amount to Weight')) as scheme_type,
					  IFNULL(p.payment_amount,'-') as payment_amount,
					  IFNULL(if(p.metal_rate=0,'-',p.metal_rate), '-') as metal_rate,
					  IFNULL(if(p.metal_weight='0.0000','-',p.metal_weight), '-') as metal_weight,
					  IFNULL(Date_format(p.date_payment,'%d-%m%-%Y'),'-') as date_payment,
			          p.payment_type,
					  IFNULL(p.payment_mode,'-') as payment_mode,
					  IFNULL(sa.scheme_acc_number,'') as msno,
					  IFNULL(p.bank_acc_no,'-') as bank_acc_no,
					  IFNULL(p.bank_name,'-')as bank_name,
					  IFNULL(p.bank_IFSC,'-') as bank_IFSC,
					  IFNULL(p.bank_branch,'-') as bank_branch,
					  IFNULL(cs.receipt_no_set,'-') as receipt_no_set,
					  IFNULL(p.id_transaction,'-') as id_transaction,
					  IFNULL(p.payu_id,'-') as payu_id ,
					  IFNULL(p.card_no,'-') as card_no,
					  psm.payment_status as payment_status,
					  p.payment_status as id_status,
					  psm.color as status_color,
					  IFNULL(p.payment_ref_number,'-') as payment_ref_number,
					if(cs.receipt_no_set=1 || cs.receipt_no_set=3  && p.receipt_no is null,'',p.receipt_no) as receipt_no,
					  IFNULL(p.remark,'-') as remark,cs.currency_name,cs.currency_symbol
				FROM payment p
				join chit_settings cs
				left join scheme_account sa on(p.id_scheme_account=sa.id_scheme_account)
				Left Join employee e On (e.id_employee=p.id_employee)
				Left Join customer c on (sa.id_customer=c.id_customer)
				left join scheme s on(sa.id_scheme=s.id_scheme)
			    Left Join payment_mode pm on (p.payment_mode=pm.id_mode)		
			    Left Join branch b on (sa.id_branch=b.id_branch)		
			   
			    Left Join payment_status_message psm On (p.payment_status=psm.id_status_msg)
			    ".($uid!=1? ($branchWiseLogin==1 ? ($id_branch!='' ? "  Where p.id_branch=".$id_branch." or b.show_to_all=1" :''):''):'')."
				 ORDER BY p.date_payment DESC ";
				
				$payment=$this->db->query($sql);
			return $payment->result_array();
		}
	}
	
	function payment_list_range($from_date,$to_date,$type="",$limit="",$date_type)
	{
		$branch_settings=$this->session->userdata('branch_settings');
		$is_branchwise_cus_reg=$this->session->userdata('is_branchwise_cus_reg');
		$branch=$this->session->userdata('id_branch');
		$uid=$this->session->userdata('uid');
		$id_employee = $this->input->post('id_employee');
		if($this->branch_settings==1)
		{
			$id_branch = $this->input->post('id_branch');
		}else{
			$id_branch = '';
		}
			
		$sql="SELECT
                p.id_payment,p.is_offline,sa.id_branch,sa.ref_no,sa.id_scheme_account,p.id_branch as pay_branch,
                cs.has_lucky_draw, is_lucky_draw,
                sa.account_name,p.act_amount,
                if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,p.added_by,
                c.mobile,
                IFNULL(sa.group_code,'') as scheme_group_code,
                IFNULL(sa.scheme_acc_number,'Not Allocated') as scheme_acc_number,p.due_type,
                s.code,b.name as payment_branch,
                p.id_employee,IFNULL(e.emp_code,'-')as emp_code,
                if(e.lastname is null,e.firstname,concat(e.firstname,' ',e.lastname)) as employee, 
                if(s.scheme_type=0,'Amount',if(s.scheme_type=1,'Weight','Amount to Weight')) as scheme_type,
                IFNULL(p.payment_amount,'-') as payment_amount,
                p.metal_rate,
                IFNULL(if(p.metal_weight='0.0000','-',p.metal_weight), '-') as metal_weight,
                IFNULL(Date_format(p.date_payment,'%d-%m%-%Y'),'-') as date_payment,
                (select IFNULL(IF(sa.is_opening=1,IFNULL(sa.paid_installments,0)+ IFNULL(if(s.scheme_type = 1 and s.min_weight != s.max_weight, COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')), sum(pay.no_of_dues)),0), if(s.scheme_type = 1 and s.min_weight != s.max_weight or (s.scheme_type=3 AND s.firstPayamt_as_payamt = 0), COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')), sum(pay.no_of_dues))) ,0) from payment pay where pay.payment_status=1 and pay.id_scheme_account=p.id_scheme_account group by pay.id_scheme_account)
                as paid_installments,
                p.payment_type,p.is_print_taken,
                p.payment_mode as payment_mode,
                IFNULL(sa.scheme_acc_number,'') as msno,
                IFNULL(p.bank_acc_no,'-') as bank_acc_no,
                IFNULL(p.bank_name,'-')as bank_name,
                IFNULL(p.bank_IFSC,'-') as bank_IFSC,
                IFNULL(p.bank_branch,'-') as bank_branch,
                IFNULL(p.id_transaction,'-') as id_transaction,
                IFNULL(p.payu_id,'-') as payu_id ,
                IFNULL(p.card_no,'-') as card_no,
                psm.payment_status as payment_status,
                p.payment_status as id_status,
                psm.color as status_color,
                IFNULL(p.payment_ref_number,'-') as payment_ref_number,
                IFNULL(p.remark,'-') as remark,
                if(cs.receipt_no_set=1 || cs.receipt_no_set=3 && p.receipt_no is null,'-',p.receipt_no) as receipt_no,
                IFNULL(cs.receipt_no_set,'-') as receipt_no_set, IFNULL(Date_format(p.custom_entry_date,'%d-%m%-%Y'),'-') as entry_Date,cs.edit_custom_entry_date
            FROM payment p
                left join scheme_account sa on(p.id_scheme_account=sa.id_scheme_account)
                Left Join employee e On (e.id_employee=p.id_employee)
                Left Join customer c on (sa.id_customer=c.id_customer)
                left join scheme s on(sa.id_scheme=s.id_scheme)
                Left Join branch b on (p.id_branch=b.id_branch)
                Left Join payment_mode pm on (p.payment_mode=pm.id_mode)		
                Left Join payment_status_message psm On (p.payment_status=psm.id_status_msg)
                join  chit_settings cs
            Where (date(".($date_type!='' ? ($date_type==2 ?"p.custom_entry_date":"p.date_payment") : "p.date_payment").") BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') ".($id_employee!=NULL||$id_employee!=''?' and p.id_employee ='.$id_employee:'')."
                ".($type!=''?"  And p.payment_type=".$type:'')." ".($uid!=1 ? ($branch_settings==1 ? ($id_branch!=0 && $id_branch!='' ? "and p.id_branch=".$id_branch."" : " and (b.show_to_all=1 or b.show_to_all=3)"):'') : ($id_branch!=0 && $id_branch!=''? "and p.id_branch=".$id_branch."" :''))."
            ORDER BY p.id_payment DESC ".($limit!=NULL? " LIMIT ".$limit." OFFSET ".$limit : " ");
       // print_r($sql);exit;
		return $this->db->query($sql)->result_array();			   
	}	
	
	function payment_online_range($from_date,$to_date,$limit="",$pg_code="")
	{
	    
	            	$branch_settings=$this->session->userdata('branch_settings');
					$id_branch=$this->session->userdata('id_branch');
					$uid=$this->session->userdata('uid');
	if($this->branch_settings==1){
				$id_branch  = $this->input->post('id_branch');}
			else{
			$id_branch = '';}
		
		$sql="SELECT
					  p.id_payment,p.ref_trans_id,
					  sa.account_name,
					  if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,
					  c.mobile,
					  IFNULL(sa.scheme_acc_number,'Not Allocated') as scheme_acc_number,
					  s.code,p.is_print_taken,
					  p.id_employee,IFNULL(e.emp_code,'-')as emp_code,
                      if(e.lastname is null,e.firstname,concat(e.firstname,' ',e.lastname)) as employee, 
					  if(s.scheme_type=0,'Amount',if(s.scheme_type=1,'Weight','Amount to Weight')) as scheme_type,
					  if(p.due_type='A' or p.due_type='P',p.act_amount,p.payment_amount) as payment_amount,
					  p.metal_rate,
					  IFNULL(p.metal_weight, '-') as metal_weight,
					  IFNULL(Date_format(p.date_payment,'%d-%m%-%Y'),'-') as date_payment,
			          p.payment_type,
					  p.payment_mode as payment_mode,
					  IFNULL(sa.scheme_acc_number,'') as msno,
					  IFNULL(p.bank_acc_no,'-') as bank_acc_no,
					  IFNULL(p.bank_name,'-')as bank_name,
					  IFNULL(p.bank_IFSC,'-') as bank_IFSC,
					  IFNULL(p.bank_branch,'-') as bank_branch,
					  IFNULL(p.id_transaction,'-') as id_transaction,
					  IFNULL(p.payu_id,'-') as payu_id ,
					  IFNULL(p.card_no,'-') as card_no,
					  psm.payment_status as payment_status,
					  p.payment_status as id_status,
					  psm.color as status_color,
					  IFNULL(p.payment_ref_number,'-') as payment_ref_number,
					  IFNULL(p.remark,'-') as remark,
					  
					  IFNULL(p.add_charges,'-') as bank_charges
				FROM payment p
				left join scheme_account sa on(p.id_scheme_account=sa.id_scheme_account)
				Left Join employee e On (e.id_employee=p.id_employee)
				Left Join customer c on (sa.id_customer=c.id_customer)
				left join scheme s on(sa.id_scheme=s.id_scheme)
			    Left Join payment_mode pm on (p.payment_mode=pm.id_mode)		
			    Left Join gateway g on (g.id_pg=p.id_payGateway)
			    Left Join branch b on (b.id_branch=sa.id_branch)
			    Left Join payment_status_message psm On (p.payment_status=psm.id_status_msg)
       Where (date(p.date_payment) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')   ".($pg_code!='' ? " and  g.pg_code=".$pg_code."" :'')." AND p.payment_type!='Manual' And (p.payment_status='3' OR p.payment_status='7' OR p.payment_status='') 
       ".($uid!=1 ? ($branch_settings==1 ? ($id_branch!=0 && $id_branch!='' ? "and p.id_branch=".$id_branch."" : " and (b.show_to_all=1 or b.show_to_all=3)"):'') : ($id_branch!=0 && $id_branch!=''? "and p.id_branch=".$id_branch."" :''))."
        	 
			   ORDER BY p.id_payment DESC ".($limit!=NULL? " LIMIT ".$limit." OFFSET ".$limit : " ");
				   	print_r($sql);exit;
		return $this->db->query($sql)->result_array();			   
	}	
	
	function payment_online($pg_code="")
	{
		$branchWiseLogin=$this->session->userdata('branchWiseLogin');
		$is_branchwise_cus_reg=$this->session->userdata('is_branchwise_cus_reg');
			$id_branch=$this->session->userdata('id_branch');
			$uid=$this->session->userdata('uid');

		$sql="SELECT
					  p.id_payment,p.ref_trans_id,
					  sa.account_name,
					  if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,
					  c.mobile,
					IFNULL(sa.group_code,'')as group_code,cs.has_lucky_draw, is_lucky_draw,
					 IFNULL(sa.scheme_acc_number,'Not Allocated') as scheme_acc_number,
					  s.code,
					  p.id_employee,IFNULL(e.emp_code,'-')as emp_code,
                      if(e.lastname is null,e.firstname,concat(e.firstname,' ',e.lastname)) as employee, 
					  if(s.scheme_type=0,'Amount',if(s.scheme_type=1,'Weight','Amount to Weight')) as scheme_type,
					  if(p.due_type='A' or p.due_type='P',p.act_amount,p.payment_amount) as payment_amount,
					  p.metal_rate,
					  IFNULL(p.metal_weight, '-') as metal_weight,
					  
					   IFNULL(p.add_charges,'-') as bank_charges,
					  IFNULL(Date_format(p.date_payment,'%d-%m%-%Y'),'-') as date_payment,
			          p.payment_type,
					  p.payment_mode as payment_mode,
					  IFNULL(sa.scheme_acc_number,'') as msno,
					  IFNULL(p.bank_acc_no,'-') as bank_acc_no,
					  IFNULL(p.bank_name,'-')as bank_name,
					  IFNULL(p.bank_IFSC,'-') as bank_IFSC,
					  IFNULL(p.bank_branch,'-') as bank_branch,
					  IFNULL(p.id_transaction,'-') as id_transaction,
					  IFNULL(p.payu_id,'') as payu_id ,
					  IFNULL(p.card_no,'-') as card_no,
					  psm.payment_status as payment_status,
					  p.payment_status as id_status,
					  psm.color as status_color,
					  IFNULL(p.payment_ref_number,'-') as payment_ref_number,
					  IFNULL(p.remark,'-') as remark
				FROM payment p
				left join scheme_account sa on(p.id_scheme_account=sa.id_scheme_account)
				Left Join employee e On (e.id_employee=p.id_employee)
				Left Join customer c on (sa.id_customer=c.id_customer)
				left join scheme s on(sa.id_scheme=s.id_scheme)
			    Left Join payment_mode pm on (p.payment_mode=pm.id_mode)		
			    Left Join branch b on (b.id_branch=sa.id_branch)		
			    Left Join gateway g on (g.id_pg=p.id_payGateway)		
			    Left Join payment_status_message psm On (p.payment_status=psm.id_status_msg)
			    join chit_settings cs
       Where g.pg_code=".$pg_code." And (p.payment_status='3' OR p.payment_status='7' OR p.payment_status='') ".($uid!=1 ? ($branchWiseLogin==1 ||$is_branchwise_cus_reg==1? ($id_branch!='' ? "and b.id_branch=".$id_branch. " or  b.show_to_all=1 ":'') :'') :'')."
        	 
			   ORDER BY p.id_payment DESC ";
			  // ORDER BY p.id_payment DESC ".($limit!=NULL? " LIMIT ".$limit." OFFSET ".$limit : " ");
				
		return $this->db->query($sql)->result_array();			   
	}
	

	 function onlinePayments_range($from_date,$to_date,$limit="",$date_type,$settle="")
	{
		    $branch_settings=$this->session->userdata('branch_settings');
					$branch=$this->session->userdata('id_branch');
					$uid=$this->session->userdata('uid');
					
		if($this->branch_settings==1)
		{
			$id_branch  = $this->input->post('id_branch');}
		else{
			$id_branch = '';}
		$sql="SELECT
					s.code,IFNULL(sa.group_code,'')as scheme_group_code,cs.has_lucky_draw,  s.is_lucky_draw,
					  p.id_payment,p.ref_trans_id,if(sa.ref_no='' ,null ,ifnull(sa.ref_no,null)) as client_id,s.free_payment,sa.is_new,
					  sa.account_name,
					  if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,
					  c.mobile,p.mer_net_amount as net_amt,p.mer_service_fee as service_fee,p.igst,is_settled,gateway_requestaction,
					  IFNULL(sa.scheme_acc_number,'Not Allocated') as scheme_acc_number,p.act_amount,p.no_of_dues,
					  s.code,
					  p.id_employee,IFNULL(e.emp_code,'-')as emp_code,
                      if(e.lastname is null,e.firstname,concat(e.firstname,' ',e.lastname)) as employee, 
					  if(s.scheme_type=0,'Amount',if(s.scheme_type=1,'Weight','Amount to Weight')) as scheme_type,
					  IFNULL(p.payment_amount,'-') as payment_amount,
					  p.metal_rate,
					  IFNULL(p.metal_weight, '-') as metal_weight,
					  IFNULL(Date_format(p.date_payment,'%d-%m%-%Y'),'-') as date_payment,
					  (select IFNULL(IF(sa.is_opening=1,IFNULL(sa.paid_installments,0)+ IFNULL(if(s.scheme_type = 1 and s.min_weight != s.max_weight, COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')), sum(pay.no_of_dues)),0), if(s.scheme_type = 1 and s.min_weight != s.max_weight or s.scheme_type=3, COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')), sum(pay.no_of_dues))) ,0) from payment pay where pay.payment_status=1 and pay.id_scheme_account=p.id_scheme_account group by pay.id_scheme_account)
					   as paid_installments,
			          p.payment_type,
					  p.payment_mode as payment_mode,
					  IFNULL(sa.scheme_acc_number,'') as msno,
					  IFNULL(p.bank_acc_no,'-') as bank_acc_no,
					  IFNULL(p.bank_name,'-')as bank_name,
					  IFNULL(p.bank_IFSC,'-') as bank_IFSC,
					  IFNULL(p.bank_branch,'-') as bank_branch,
					  IFNULL(p.id_transaction,'-') as id_transaction,
					  IFNULL(p.payu_id,'-') as payu_id ,
					  IFNULL(p.card_no,'-') as card_no,
					  psm.payment_status as payment_status,
					  p.payment_status as id_status,
					  psm.color as status_color,
					  IFNULL(p.payment_ref_number,'-') as payment_ref_number,
					  IFNULL(p.remark,'-') as remark
				FROM payment p
				join chit_settings cs
				left join scheme_account sa on(p.id_scheme_account=sa.id_scheme_account)
				Left Join employee e On (e.id_employee=p.id_employee)
				Left Join customer c on (sa.id_customer=c.id_customer)
				left join scheme s on(sa.id_scheme=s.id_scheme)
			    Left Join payment_mode pm on (p.payment_mode=pm.id_mode)		
			    Left Join payment_status_message psm On (p.payment_status=psm.id_status_msg)
				Left join branch b on (b.id_branch=p.id_branch)
       Where   (date(".($date_type!='' ? ($date_type==2 ?"p.custom_entry_date":"p.date_payment") : "p.date_payment").") BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') AND 
        ".($settle==1?"  p.payment_type!='Manual' And p.payment_status=2 and p.is_settled=1  ":($settle==2?" p.payment_type!='Manual' And p.payment_status=2  and p.is_settled=0  ":''))." 
	  ".($uid!=1 ? ($branch_settings==1 ? ($id_branch!=0 && $id_branch!='' ? "and p.id_branch=".$id_branch."" : " and (p.id_branch=".$branch." or b.show_to_all=1 or b.show_to_all=3)"):'') : ($id_branch!=0 && $id_branch!=''? "and p.id_branch=".$id_branch."" :''))."
        	 
			   ORDER BY p.id_payment DESC ".($limit!=NULL? " LIMIT ".$limit." OFFSET ".$limit : " ");
			  //print_r($sql);exit;
		return $this->db->query($sql)->result_array();			   
	}	
	
	function onlinePayments($limit="",$settle="")
	{
		            $branch_settings=$this->session->userdata('branch_settings');
					$branch=$this->session->userdata('id_branch');
					$uid=$this->session->userdata('uid');
					
		if($this->branch_settings==1)
		{
			$id_branch  = $this->input->post('id_branch');}
		else{
			$id_branch = '';}
					
		$sql="SELECT
					s.code,IFNULL(sa.group_code,'')as scheme_group_code,cs.has_lucky_draw, s.is_lucky_draw ,
					  p.id_payment,p.ref_trans_id,if(sa.ref_no='',null ,ifnull(sa.ref_no,null)) as client_id,s.free_payment,sa.is_new,
					  sa.account_name,
					  if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,
					  c.mobile,p.mer_net_amount as net_amt,p.mer_service_fee as service_fee,p.igst,is_settled,gateway_requestaction,
					   IFNULL(sa.scheme_acc_number,'Not Allocated') as scheme_acc_number,
					  s.code,
					  p.id_employee,p.act_amount,p.no_of_dues,
                      if(e.lastname is null,e.firstname,concat(e.firstname,' ',e.lastname)) as employee,IFNULL(e.emp_code,'-')as emp_code,
					  if(s.scheme_type=0,'Amount',if(s.scheme_type=1,'Weight','Amount to Weight')) as scheme_type,
					  IFNULL(p.payment_amount,'-') as payment_amount,
					  p.metal_rate,
					  IFNULL(p.metal_weight, '-') as metal_weight,
					  IFNULL(Date_format(p.date_payment,'%d-%m%-%Y'),'-') as date_payment,
					  (select IFNULL(IF(sa.is_opening=1,IFNULL(sa.paid_installments,0)+ IFNULL(if(s.scheme_type = 1 and s.min_weight != s.max_weight, COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')), sum(pay.no_of_dues)),0), if(s.scheme_type = 1 and s.min_weight != s.max_weight or s.scheme_type=3, COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')), sum(pay.no_of_dues))) ,0) from payment pay where pay.payment_status=1 and pay.id_scheme_account=p.id_scheme_account group by pay.id_scheme_account)
					   as paid_installments,
			          p.payment_type,
					  p.payment_mode as payment_mode,
					  IFNULL(sa.scheme_acc_number,'') as msno,
					  IFNULL(p.bank_acc_no,'-') as bank_acc_no,
					  IFNULL(p.bank_name,'-')as bank_name,
					  IFNULL(p.bank_IFSC,'-') as bank_IFSC,
					  IFNULL(p.bank_branch,'-') as bank_branch,
					  IFNULL(p.id_transaction,'-') as id_transaction,
					  IFNULL(p.payu_id,'-') as payu_id ,
					  IFNULL(p.card_no,'-') as card_no,
					  psm.payment_status as payment_status,
					  p.payment_status as id_status,
					  psm.color as status_color,
					  IFNULL(p.payment_ref_number,'-') as payment_ref_number,
					  IFNULL(p.remark,'-') as remark
				FROM payment p
				join chit_settings cs
				left join scheme_account sa on(p.id_scheme_account=sa.id_scheme_account)
				Left Join employee e On (e.id_employee=p.id_employee)
				Left Join customer c on (sa.id_customer=c.id_customer)
				left join scheme s on(sa.id_scheme=s.id_scheme)
			    Left Join payment_mode pm on (p.payment_mode=pm.id_mode)
			       Left Join branch b on (sa.id_branch=b.id_branch)			
			    Left Join payment_status_message psm On (p.payment_status=psm.id_status_msg)
       Where     ".($settle==1?"  p.payment_type!='Manual' And p.payment_status=2 and p.is_settled=1  ":($settle==2?" p.payment_type!='Manual' And p.payment_status=2  and p.is_settled=0  ":''))." 
       ".($uid!=1 ? ($branch_settings==1 ? ($id_branch!=0 && $id_branch!='' ? "and p.id_branch=".$id_branch."" : " and (p.id_branch=".$branch." or b.show_to_all=1 or b.show_to_all=3)"):'') : ($id_branch!=0 && $id_branch!=''? "and p.id_branch=".$id_branch."" :''))."
        	 
			   ORDER BY p.id_payment DESC ".($limit!=NULL? " LIMIT ".$limit." OFFSET ".$limit : " ");
//	print_r($sql);exit;
		return $this->db->query($sql)->result_array();			   
	}	
// settled pay show in payment apprval page with filter//HH	     
    function getData_matchedRefno($refno)
    {
    	$sql = "SELECT id_payment from payment where due_type='S' and  payment_type='Payu Checkout' and payment_ref_number=".$refno;
    	$splitted = $this->db->query($sql)->result_array();
    	
    	$query = "SELECT id_payment from payment where due_type!='S' and  payment_type='Payu Checkout' and payment_ref_number=".$refno;
    	$origPay = $this->db->query($query)->row('id_payment');
    	
    	return array('splittedId'=>$splitted,'parentId'=>$origPay); 
    }
    function paymentDB($type="",$id="",$pay_array="")
    {
    	
    	switch($type)
    	{
			case 'get':
			     $sql="Select
			      		s.code,IFNULL(sa.group_code,'')as scheme_group_code,cs.has_lucky_draw, s.is_lucky_draw,allow_referral,s.id_scheme,p.redeemed_amount,
						  p.id_payment,s.gst,s.gst_type,is_point_credited,iwa.available_points,sa.id_branch as branch,s.code as group_code,s.sync_scheme_code,
						  ifnull(iwa.mobile,0) as isAvail,c.mobile,redeemed_amount,actual_trans_amt,s.get_amt_in_schjoin,
						  p.id_scheme_account,cs.scheme_wise_acc_no,
						concat(if(cs.has_lucky_draw=1 && s.is_lucky_draw = 1,sa.group_code,s.code),' ', IFNULL(sa.scheme_acc_number,'Not Allocated')) as scheme_acc_number,
						  sa.account_name,p.receipt_no,
						  p.id_transaction,
						  p.payu_id,p.due_type,
						  p.id_post_payment,p.act_amount,if(p.payment_type='Payu Checkout' and (p.due_type='A' or p.due_type='P') and p.payment_status!=1,'Y','N' ) as showPaid,
						  p.id_drawee,
						  da.account_no as drawee_acc_no,
						  da.account_name as drawee_account_name,
						  Date_format(date_payment,'%d-%m-%Y') as date_payment,
						  Date_format(p.custom_entry_date,'%Y-%m-%d') as custom_entry_date,
						  p.payment_type,p.no_of_dues,
						  p.payment_mode,
						  p.payment_amount,
						  p.add_charges,(select charges FROM postdate_payment WHERE id_post_payment=p.id_post_payment) as charges,
						  p.metal_rate,
						  p.metal_weight,
						  p.cheque_date,
						  p.cheque_no,
						  p.bank_acc_no,
						  p.bank_name,
						  p.bank_branch,
						  p.bank_IFSC,
						  p.card_no,
						  p.card_holder,
						  p.cvv,
						  p.exp_date,
						  p.payment_ref_number,
						  p.payment_status as id_payment_status,
						  p.remark,
						  psm.payment_status as payment_status,
						  psm.color as status_color,
						  
						  cs.receipt_no_set,cs.schemeacc_no_set,IFNULL(sa.scheme_acc_number,'') as acc_no,cs.gent_clientid,
						  
						   p.remark,if((cs.receipt_no_set= 1 && p.payment_status =1 ),p.receipt_no,if((cs.receipt_no_set= 0 && p.payment_status =1),p.receipt_no,'')) as receipt_no,
						   cs.edit_custom_entry_date,p.custom_entry_date,s.firstPayamt_as_payamt,s.firstPayamt_maxpayable
							
						From payment p
						Join chit_settings cs
						Left Join scheme_account sa on (p.id_scheme_account=sa.id_scheme_account)
						Left Join scheme s on (s.id_scheme=sa.id_scheme)
						Left Join drawee_account da on (p.id_drawee=p.id_drawee)
						Left Join payment_mode pm on (p.payment_mode=pm.id_mode)
						Left Join payment_status_message psm On (p.payment_status=psm.id_status_msg)
Left Join customer c on (c.id_customer=sa.id_customer)
						LEFT JOIN inter_wallet_account iwa on iwa.mobile=c.mobile
						Where p.id_payment=".$id;
						//print_r($sql);exit;
						
			      return $this->db->query($sql)->row_array();
			break;
			case 'insert':
				 $status = $this->db->insert(self::PAY_TABLE,$pay_array);
				 //	print_r($this->db->last_query());exit;
	 			 return	array('status'=>$status,'insertID'=>($status == TRUE ? $this->db->insert_id():''));
			break;
			case 'update':
				$this->db->where("id_payment",$id);
	           	$status = $this->db->update(self::PAY_TABLE,$pay_array);
			   	return	array('status' => $status, 'updateID' => $id);     
			break;
			case 'delete':
				 $this->db->where("id_payment",$id);
			     $status = $this->db->delete(self::PAY_TABLE);
				 return	array('status' => $status, 'DeleteID' => $id);  		
			break;
			
			default:
			     return array(
							 'id_payment' 		 => NULL,		
							 'id_scheme_account' => NULL,		
							 'date_payment'      => date('Y-m-d'),		
							 'payment_type'      => "Manual",		
							 'payment_mode'      => NULL,		
							 'payment_status'      => NULL,		
						     'payment_amount'    => 0.00,						     
							 'metal_rate'		 => 0.00,
							 'metal_weight'		 => 0.000,
							 'payment_ref_number'	 => NULL,
							 'remark'			 => NULL, 
							  'edit_addpay_page'	 => $this->checkSettings(), 
							  'cost_center'	 => $this->cost_center(), 
							  'allow_wallet'	=>$this->allow_wallet(),
							 'useWalletForChit'	=>$this->wallet(),
							  'isOTPRegForPayment'	=>$this->isOTPRegForPayment(),
							 'allow_preclose'	 =>0, 
							 'pdc' => array(
											 	'date_payment'  => NULL,
										        'cheque_no'     => NULL,
											 	'payee_bank'	=> NULL,
											 	'payee_branch'	=> NULL,
												
												
											 	'payee_ifsc'	=> NULL,
											)
							 
						   );
		}
		            
	}
	
	function cost_center()
	{
		 $sql="Select cost_center from chit_settings where id_chit_settings = 1";
		 return $this->db->query($sql)->row()->cost_center;
	}
	
	function checkSettings()
	{
		 $sql="Select edit_addpay_page from chit_settings where id_chit_settings = 1";
		 return $this->db->query($sql)->row()->edit_addpay_page;
	}
	function allow_wallet()
	{
		 $sql="Select allow_wallet from chit_settings where id_chit_settings = 1";
		 return $this->db->query($sql)->row()->allow_wallet;
	}
	function wallet()
	{
		 $sql="Select useWalletForChit from chit_settings where id_chit_settings = 1";
		 return $this->db->query($sql)->row()->useWalletForChit;
	}
	
	function payment_statusDB($type="",$id="",$pay_array="")
	{
			switch($type)
    	{
			case 'get':
			      $sql="Select * from payment_status where id_payment_status=".$id;
			      return $this->db->query($sql)->row_array();
			break;
			case 'insert':
				 $status = $this->db->insert("payment_status",$pay_array);
	 			 return	array('status'=>$status,'insertID'=>($status == TRUE ? $this->db->insert_id():''));
			break;
			case 'update':
				$this->db->where("id_payment_status",$id);
	           	$status = $this->db->update("payment_status",$pay_array);
			   	return	array('status' => $status, 'updateID' => $id);     
			break;
			case 'delete':
				 $this->db->where("id_payment_status",$id);
			     $status = $this->db->delete("payment_status");
				 return	array('status' => $status, 'DeleteID' => $id);  		
			break;
			
		 }	
	}
	
	function post_paymentlist($id="",$limit="")
    {
		$sql="SELECT
			      pp.id_post_payment,s.code,
			      IFNULL(sa.scheme_acc_number,'Not Allocated') as scheme_acc_number,
			      sa.id_scheme_account,
			      sa.account_name,
			      c.mobile,c.email,c.lastname,c.firstname,s. scheme_name,if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as cus_name,
			      pp.pay_mode,
			      Date_format(pp.date_payment,'%d-%m%-%Y') as date_payment,
			      pp.cheque_no,
			      pp.payee_acc_no,
			      pb.bank_name as payee_bank,
                  pb.short_code as payee_short_code,
                  pp.payee_branch,
                  pp.payee_ifsc,
			      pp.id_drawee,
                  da.account_no as drawee_acc_no,
                  da.account_name as drawee_account_name,
                  db.bank_name as drawee_bank,
                  db.short_code as drawee_short_code,
                  da.branch as drawee_branch,
                  da.ifsc_code as drawee_ifsc,
			      pp.amount,
			      pp.payment_status as id_payment_status,
			      psm.payment_status,
			      psm.color as status_color,cs.currency_symbol,cs.currency_name
			FROM postdate_payment pp
			Left Join scheme_account sa on (pp.id_scheme_account=sa.id_scheme_account)
			Left Join payment_status_message psm on (pp.payment_status=psm.id_status_msg)
            Left Join drawee_account da on (pp.id_drawee=da.id_drawee)
            Left Join bank db on (da.id_bank=db.id_bank)
            Left Join bank pb on (pp.payee_bank=pb.id_bank)
            Left Join customer c on (sa.id_customer=c.id_customer)
            Left join scheme s on(sa.id_scheme=s.id_scheme)
            join chit_settings cs 
			Where pp.payment_status!=1 ".($id!=null? ' and pp.id_post_payment='.$id:'')." 
			ORDER BY pp.date_payment DESC ".($limit!=NULL? " LIMIT ".$limit : " ");
					 
		$payment=$this->db->query($sql);	
		if($id!=NULL)
		{
			return $payment->row_array();
		}
		else
		{
			return $payment->result_array();
		}
		 		 
	}
	function pdc_detail_all($status)
	{
		$sql="SELECT
			      pp.id_post_payment,if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as cus_name,
			      
			      IFNULL(sa.scheme_acc_number,'Not Allocated') as scheme_acc_number,
			      IFNULL(sa.group_code,'Not Allocated')as scheme_group_code,
			      cs.has_lucky_draw, s.is_lucky_draw,s.code,
			      sa.id_scheme_account,
			      sa.account_name,
			      pp.pay_mode,
			      Date_format(pp.date_payment,'%d-%m%-%Y') as date_payment,
			      pp.cheque_no,
			      pp.payee_acc_no,
			      pb.bank_name as payee_bank,
            	  pb.short_code as payee_short_code,
                  pp.payee_branch,
                  pp.payee_ifsc,
			      pp.id_drawee,
                  da.account_no as drawee_acc_no,
            	  da.account_name as drawee_account_name,
            	  db.bank_name as drawee_bank,
            	  db.short_code as drawee_short_code,
                  da.branch as drawee_branch,
            	  da.ifsc_code as drawee_ifsc,
			      pp.amount,
			      pp.payment_status as id_payment_status,
			      psm.payment_status,
			      psm.color as status_color,
			      pp.charges
			FROM postdate_payment pp
			Left Join scheme_account sa on (pp.id_scheme_account=sa.id_scheme_account)
			left join scheme s on(s.code=sa.group_code)
			Left Join customer c on (c.id_customer=sa.id_customer)
			Left Join payment_status_message psm on (pp.payment_status=psm.id_status_msg)
		    Left Join drawee_account da on (pp.id_drawee=da.id_drawee)
		    Left Join bank db on (da.id_bank=db.id_bank)
		    Left Join bank pb on (pp.payee_bank=pb.id_bank)
		    join chit_settings cs
		    Where pp.payment_status='".$status."' 
		     And Date(pp.`date_payment`) <= CURDATE() 
		    Order By pp.pay_mode ASC";
	
		    $r=$this->db->query($sql);
	     	return $r->result_array();	
	}
	
	function pdc_report_detail($filterBy,$mode,$status)
	{
	  $sql="SELECT
			      pp.id_post_payment,
			      IFNULL(sa.scheme_acc_number,'Not Allocated') as scheme_acc_number,
			      sa.id_scheme_account,
			      sa.account_name,
			      pp.pay_mode,
			      Date_format(pp.date_payment,'%d-%m%-%Y') as date_payment,
			      pp.cheque_no,
			      pp.payee_acc_no,
			      pb.bank_name as payee_bank,
            	  pb.short_code as payee_short_code,
                  pp.payee_branch,
                  pp.payee_ifsc,
			      pp.id_drawee,
                  da.account_no as drawee_acc_no,
            	  da.account_name as drawee_account_name,
            	  db.bank_name as drawee_bank,
            	  db.short_code as drawee_short_code,
                  da.branch as drawee_branch,
            	  da.ifsc_code as drawee_ifsc,
			      pp.amount,
			      pp.payment_status as id_payment_status,
			      psm.payment_status,
			      psm.color as status_color,
			      pp.charges
			FROM postdate_payment pp
			Left Join scheme_account sa on (pp.id_scheme_account=sa.id_scheme_account)
			Left Join payment_status_message psm on (pp.payment_status=psm.id_status_msg)
		    Left Join drawee_account da on (pp.id_drawee=da.id_drawee)
		    Left Join bank db on (da.id_bank=db.id_bank)
		    Left Join bank pb on (pp.payee_bank=pb.id_bank)
		    Where pp.pay_mode='".$mode."' And pp.payment_status=".$status;	
		switch(strtoupper($filterBy)){
			case 'Y': //yesterday
			         $sql=$sql." And (Date(pp.`date_payment`) = (CURDATE() - INTERVAL 1 DAY)) ";   
				break;
			case 'T': //Today
			         $sql=$sql." And (Date(pp.`date_payment`) = CURDATE())";   
				break;
			case 'TT': //Till today
		          $sql=$sql." And Date(pp.`date_payment`) <= CURDATE() ";     
		   		break;
			case 'LW': //Last Week
			          $sql=$sql." And (Date(`date_payment`) BETWEEN ((CURDATE() - INTERVAL DAYOFWEEK(CURDATE()) + 6 DAY) AND (CURDATE() - INTERVAL DAYOFWEEK(CURDATE()) - 1 DAY)))";   
				break;
			case 'TW': //This Week
			          $sql=$sql." And (pp.Date(`date_payment`) BETWEEN DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY) AND DATE_ADD(CURDATE(), INTERVAL 7 - DAYOFWEEK(CURDATE()) DAY))";   
				break;	
			case 'TM': //This Month
			          $sql=$sql." And (pp.Date(`date_payment`) BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) AND LAST_DAY(NOW())";   
				break;
			
			case 'ALL':
			         $sql=$sql." ";
				break;
		}	
		
		 $sql=$sql." Order By pp.date_payment ASC ";
		 
		 $r=$this->db->query($sql);
		
		
		return $r->result_array();		
	
	}
	
	function post_payment_statuslog($id)
	{
	
		$sql="Select
			      ps.id_post_payment,
			      psm.payment_status,
					  psm.color as status_color,
			      if(e.lastname=null,concat(e.firstname,' ',e.lastname),e.firstname) as username,
			      ps.charges,
			      ps.date_upd
			From payment_status ps
			Left join employee e on(ps.id_employee=e.id_employee)
			Left Join payment_status_message psm on (ps.id_status_msg=psm.id_status_msg)
			Where ps.id_post_payment=".$id;
			return $this->db->query($sql)->result_array();			
	}
	
	function post_paymentlist_range($from_date,$to_date)
	{
		if($this->branch_settings==1){
				$id_branch  = $this->input->post('id_branch');}
			else{
			$id_branch = '';}
	
	
		$sql="SELECT
			      pp.id_post_payment,
			     IFNULL(sa.scheme_acc_number,'Not Allocated') as scheme_acc_number,
			      sa.id_scheme_account,if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as cus_name,
			      sa.account_name,s.code,
			      pp.pay_mode,
			      Date_format(pp.date_payment,'%d-%m%-%Y') as date_payment,
			      pp.cheque_no,
			      pp.payee_acc_no,
			      pb.bank_name as payee_bank,
           		  pb.short_code as payee_short_code,
            	  pp.payee_branch,
                  pp.payee_ifsc,
			      pp.id_drawee,
                             da.account_no as drawee_acc_no,
                  da.account_name as drawee_account_name,
            	 db.bank_name as drawee_bank,
            	 db.short_code as drawee_short_code,
           	 	 da.branch as drawee_branch,
            	  da.ifsc_code as drawee_ifsc,
			      pp.amount,
			      pp.payment_status as id_payment_status,
			      psm.payment_status,
			      psm.color as status_color
			FROM postdate_payment pp
			Left Join scheme_account sa on (pp.id_scheme_account=sa.id_scheme_account)
			Left Join scheme s on (sa.id_scheme = s.id_scheme)
			Left Join payment_status_message psm on (pp.payment_status=psm.id_status_msg)
      Left Join drawee_account da on (pp.id_drawee=da.id_drawee)
        Left Join customer c on (sa.id_customer=c.id_customer)
      Left Join bank db on (da.id_bank=db.id_bank)
      Left Join bank pb on (pp.payee_bank=pb.id_bank)
			Where pp.payment_status!=1 and(date(pp.date_payment) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')  
			".($id_branch!=NULL?' and sa.id_branch ='.$id_branch:'')."
			   ORDER BY pp.id_post_payment ASC";
		return $this->db->query($sql)->result_array();			   
	}
	
	function get_postpayment($id_scheme_account)
	{
		$sql="SELECT
			      pp.id_post_payment,
			     IFNULL(sa.scheme_acc_number,'Not Allocated') as scheme_acc_number,
			      sa.id_scheme_account,
			      sa.account_name,
			      pp.pay_mode,
			      Date_format(pp.date_payment,'%d-%m%-%Y') as date_payment,
			      pp.cheque_no,
			      pp.payee_acc_no,
			      pb.bank_name as payee_bank,
           		  pb.short_code as payee_short_code,
            	  pp.payee_branch,
                  pp.payee_ifsc,
			      pp.id_drawee,
                  da.account_no as drawee_acc_no,
                  da.account_name as drawee_account_name,
            	 db.bank_name as drawee_bank,
            	 db.short_code as drawee_short_code,
           	 	 da.branch as drawee_branch,
            	 da.ifsc_code as drawee_ifsc,
			     pp.amount,
			     pp.metal_rate,
			      pp.weight,
			     pp.payment_status as id_payment_status,
			     psm.payment_status,
			     psm.color as status_color,
			     pp.charges
			FROM postdate_payment pp
			Left Join scheme_account sa on (pp.id_scheme_account=sa.id_scheme_account)
			Left Join payment_status_message psm on (pp.payment_status=psm.id_status_msg)
		    Left Join drawee_account da on (pp.id_drawee=da.id_drawee)
		    Left Join bank db on (da.id_bank=db.id_bank)
		    Left Join bank pb on (pp.payee_bank=pb.id_bank)
			Where pp.payment_status!=1 and date(pp.date_payment) <= date(curdate()) and  pp.id_scheme_account='".$id_scheme_account."'";
			
			  $payment = $this->db->query($sql);
			     $data = array(
			     				'total' => $payment->num_rows(),
			     				'data'  => $payment->result_array()
			     					
			                  );
			return $data;
	}
	
	function postdated_paymentByID($id)
	{
		$sql ="SELECT
			      pp.id_post_payment,
			     IFNULL(sa.scheme_acc_number,'Not Allocated') as scheme_acc_number,
			      sa.id_scheme_account,
			      sa.account_name,
			      pp.pay_mode,
			      Date_format(pp.date_payment,'%d-%m%-%Y') as date_payment,
			      pp.cheque_no,
			      pp.payee_acc_no,
			      pb.bank_name as payee_bank,
           		  pb.short_code as payee_short_code,
            	  pp.payee_branch,
                  pp.payee_ifsc,
			      pp.id_drawee,
                             da.account_no as drawee_acc_no,
            da.account_name as drawee_account_name,
            	 db.bank_name as drawee_bank,
            	 db.short_code as drawee_short_code,
           	 	 da.branch as drawee_branch,
            	  da.ifsc_code as drawee_ifsc,
			      pp.amount,
			      pp.metal_rate,
			      pp.weight,
			        pp.payment_status as id_payment_status,
			      psm.payment_status,
			      psm.color as status_color,
			      pp.charges,
			      pp.date_presented
			FROM postdate_payment pp
			Left Join scheme_account sa on (pp.id_scheme_account=sa.id_scheme_account)
			Left Join payment_status_message psm on (pp.payment_status=psm.id_status_msg)
      Left Join drawee_account da on (pp.id_drawee=da.id_drawee)
      Left Join bank db on (da.id_bank=db.id_bank)
      Left Join bank pb on (pp.payee_bank=pb.id_bank)
      Where  pp.id_post_payment=".$id;
      return $this->db->query($sql)->row_array();
	}
	
	function postdated_paymentDB($type,$id="",$pay_array="")
	{
		switch($type)
    	{
			case 'get':
			       $sql="SELECT
			      pp.id_post_payment,
			      IFNULL(sa.scheme_acc_number,'Not Allocated') as scheme_acc_number,
			      sa.id_scheme_account,
			      sa.account_name,
			      pp.pay_mode,cs.scheme_wise_acc_no,s.is_lucky_draw,
			      Date_format(pp.date_payment,'%d-%m%-%Y') as date_payment,
			      pp.cheque_no,
			      pp.payee_acc_no,
			      pb.bank_name as payee_bank,
           		  pb.short_code as payee_short_code,
            	  pp.payee_branch,
                  pp.payee_ifsc,
			      pp.id_drawee,
                  da.account_no as drawee_acc_no,
                  da.account_name as drawee_account_name,
            	 db.bank_name as drawee_bank,
            	 db.short_code as drawee_short_code,
           	 	 da.branch as drawee_branch,
            	  da.ifsc_code as drawee_ifsc,
			      pp.amount,
			      pp.metal_rate,
			      pp.weight,
			        pp.payment_status as id_payment_status,
			      psm.payment_status,
			      psm.color as status_color,
			      pp.charges,
			      pp.date_presented
			FROM postdate_payment pp
			Join chit_settings cs
			Left Join scheme_account sa on (pp.id_scheme_account=sa.id_scheme_account)
			Left Join payment_status_message psm on (pp.payment_status=psm.id_status_msg)
			Left Join scheme s on (s.id_scheme=sa.id_scheme)
      Left Join drawee_account da on (pp.id_drawee=da.id_drawee)
      Left Join bank db on (da.id_bank=db.id_bank)
      Left Join bank pb on (pp.payee_bank=pb.id_bank)
						Where pp.payment_status!=1 ".($id!=NULL?' And  pp.id_post_payment='.$id:'');
				 if($id!=NULL)
				 {
				 	  return $this->db->query($sql)->row_array();
				 }	
				 else
				 {
				 	  return $this->db->query($sql)->result_array();
				 }	
			    
			break;
				case 'insert':
				//print_r($pay_array);exit;
				 $status = $this->db->insert("postdate_payment",$pay_array);
	 			 return	array('status'=>$status,'insertID'=>($status == TRUE ? $this->db->insert_id():''));
			break;
			case 'update':
				$this->db->where("id_post_payment",$id);
	           	$status = $this->db->update("postdate_payment",$pay_array);
			   	return	array('status' => $status, 'updateID' => $id);     
			break;
			case 'delete':
				 $this->db->where("id_post_payment",$id);
			     $status = $this->db->delete("postdate_payment");
				 return	array('status' => $status, 'DeleteID' => $id);  		
			break;
			
		}	
	}
	
	function payment_log($id)
	{
		$sql="Select
			      ps.id_post_payment,
			      psm.payment_status,
					  psm.color as status_color,
			      if(e.lastname=null,concat(e.firstname,' ',e.lastname),e.firstname) as username,
			      ps.charges,
			      Date_Format(ps.date_upd,'%d-%m-%Y %H:%m:%s') as date_upd
			From payment_status ps
			Left join employee e on(ps.id_employee=e.id_employee)
			Left Join payment_status_message psm on (ps.id_status_msg=psm.id_status_msg)
			Where ps.id_payment=".$id." Order By ps.id_post_payment Desc";
		    return $this->db->query($sql)->result_array();	
	}
	
	function post_payment_log($id)
	{
		$sql="Select
			      ps.id_post_payment,
			      psm.payment_status,
					  psm.color as status_color,
			      if(e.lastname=null,concat(e.firstname,' ',e.lastname),e.firstname) as username,
			      ps.charges,
			      Date_Format(ps.date_upd,'%d-%m-%Y %H:%m:%s') as date_upd
			From payment_status ps
			Left join employee e on(ps.id_employee=e.id_employee)
			Left Join payment_status_message psm on (ps.id_status_msg=psm.id_status_msg)
			Where ps.id_post_payment=".$id." Order By ps.id_post_payment Desc";
		return $this->db->query($sql)->result_array();	
	}
	
	function get_payment_status()
	{
		return $this->db->query("Select * from payment_status_message")->result_array();
	}
	
	function get_customer_schemes($id_customer)
	{
		$branchWiseLogin=$this->session->userdata('branchWiseLogin');
			$id_branch=$this->session->userdata('id_branch');
			$uid=$this->session->userdata('uid');
		$sql = "Select sa.id_scheme_account, 
		if(cs.has_lucky_draw=1 && s.is_lucky_draw = 1,concat(concat(ifnull(sa.group_code,''),' ',ifnull(sa.scheme_acc_number,'Not Allocated')),' - ',s.code ),concat(s.code,' ',ifnull(sa.scheme_acc_number,'Not Allcoated')))as scheme_acc_number
        		From scheme_account sa 
        		Left join scheme s on sa.id_scheme=s.id_scheme 
        		Left join branch  b on b.id_branch=sa.id_branch
        		join chit_settings cs 
		    Where sa.active=1 and sa.is_closed=0 and sa.id_customer=".$id_customer;
			
			
		
		return $this->db->query($sql)->result_array();       
	}
	
	function get_customer_schemes_amount($id_customer)
	{
		$sql ="Select id_scheme_account,IFNULL(sa.scheme_acc_number,'Not Allocated') as scheme_acc_number,s.scheme_type,s.code
		       From   scheme_account sa
		        left join scheme s on(sa.id_scheme=s.id_scheme)
		       Where  sa.active=1 and sa.is_closed=0 and s.scheme_type=0 and sa.id_customer=".$id_customer;
		return $this->db->query($sql)->result_array();       
	}
	
   	function get_account_detail($id)
	{
		$accounts=$this->db->query("Select
										IFNULL(sa.scheme_acc_number,'Not Allocated') as scheme_acc_number,
										s.id_scheme,
										c.id_customer,
										IF(c.lastname IS NULL,c.firstname,CONCAT(c.firstname,' ',c.lastname)) customer_name,
										sa.scheme_acc_number as scheme_acc_number,
										IFNULL(sa.account_name,if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname))) as account_name,
										c.mobile,
										s.scheme_name,
										if(s.scheme_type=0,'Amount',if(s.scheme_type=1,'Weight','Amount to Weight')) AS scheme_type,
										s.code,
										IFNULL(s.min_chance,0) as min_chance,
										IFNULL(s.max_chance,0) as max_chance,
										Format(IFNULL(s.max_weight,0),3) as max_weight,
										Format(IFNULL(s.min_weight,0),3) as min_weight,
										Date_Format(sa.start_date,'%d-%m-%Y')start_date,
										IF(s.scheme_type=1,s.max_weight,s.amount) as payable,
										s.total_installments,
										IFNULL(IF(sa.is_opening=1,IFNULL(sa.paid_installments,0)+ IFNULL(if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues)),0), if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues))) ,0)
  as paid_installments,
  
  
IFNULL(IF(sa.is_opening=1,IFNULL(balance_amount,0)+IFNULL(SUM(p.payment_amount * p.no_of_dues),0),IFNULL(SUM(p.payment_amount * p.no_of_dues),0)) ,0)
  as total_paid_amount,
  
IFNULL(IF(sa.is_opening=1,IFNULL(balance_weight,0)+IFNULL(SUM(p.metal_weight),0),IFNULL(SUM(p.metal_weight),0)),0.000)
 as total_paid_weight,
										ROUND(IFNULL(cp.total_amount,0),2) as  current_paid_amount,
				    					ROUND(IFNULL(cp.total_weight,0),3) as  current_paid_weight,
				    					IFNULL(cp.paid_installment,0)       as  current_paid_installments,
				    					IFNULL(cp.chances,0)                as  current_chances_used,
										s.is_pan_required,
										 if(s.allow_unpaid=1,s.unpaid_months,0) as allow_unpaid_month,
										Date_Format(max(p.date_payment),'%d-%m-%Y') as last_paid_date,
										TIMESTAMPDIFF(MONTH, max(Date(p.date_payment)), Date(Current_Date())) as duration,
										sa.active as chit_active,
										sa.is_closed as is_closed,
										count(pp.id_post_payment) as pdc,
										Date_Format(max(pp.date_payment),'%d-%m-%Y') as last_pdc_date
									From scheme_account sa
									Left Join scheme s On (sa.id_scheme=s.id_scheme)
									Left Join payment p On (sa.id_scheme_account=p.id_scheme_account and p.payment_status=1)
									Left Join customer c On (sa.id_customer=c.id_customer and c.active=1)
										Left Join
													(	Select
														  sa.id_scheme_account,
														  COUNT(Distinct Date_Format(p.date_payment,'%Y%m')) as paid_installment,
														  COUNT(Date_Format(p.date_payment,'%Y%m')) as chances,
														  SUM(p.payment_amount) as total_amount,
														  SUM(p.metal_weight) as total_weight
														From payment p
														Left Join scheme_account sa on(p.id_scheme_account=sa.id_scheme_account and sa.active=1 and sa.is_closed=0)
														Where p.payment_status=1 and  Date_Format(Current_Date(),'%Y%m')=Date_Format(p.date_payment,'%Y%m')
														Group By sa.id_scheme_account
													) cp On (sa.id_scheme_account=cp.id_scheme_account)
									 Left Join postdate_payment pp on (sa.id_scheme_account=pp.id_scheme_account AND pp.payment_status!=1)				
									Where sa.active=1 and sa.is_closed = 0 and sa.id_scheme_account=".$id."
									Group By sa.id_scheme_account");
				
		return $accounts->row_array();
	}
// function get_paymentContent($id_scheme_account)
// 	{  
// 	   $schemeAcc = array();
// 	  // $schemeAcc1 = array();
	   
// 		$sql="Select
// 					s.min_amt_chance,s.max_amt_chance,s.code,s.min_amount,
// 				   	sa.id_scheme_account,s.gst,s.gst_type,s.max_amount,
// 					s.id_scheme,s.wgt_convert,if(s.cus_refferal=1 || s.emp_refferal=1,sa.referal_code,'')as referal_code,
// 					s.ref_benifitadd_ins_type,s.ref_benifitadd_ins,
// 					c.id_customer,
// 					CONCAT(s.code,'-',IFNULL(sa.scheme_acc_number,'Not Allocated')) as chit_number,
// 					IFNULL(sa.account_name,if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname))) as account_name,
// 					s.scheme_name,
// 					s.scheme_type,
// 					if(scheme_type=3,if(s.max_amount!='',s.max_amount * s.total_installments,s.max_weight),s.amount)as scheme_overall_amount,
// 					IFNULL(s.min_chance,0) as min_chance,
// 					IFNULL(s.max_chance,0) as max_chance,
// 					Format(IFNULL(s.max_weight,0),3) as max_weight,
// 					Format(IFNULL(s.min_weight,0),3) as min_weight,
// 					Date_Format(sa.start_date,'%d-%m-%Y') as start_date,
// 					(SELECT m.goldrate_22ct FROM metal_rates m  order by id_metalrates Desc LIMIT 1) as metal_rate,
					
					
//   IF(s.scheme_type=0 OR s.scheme_type=2,s.amount,IF(s.scheme_type=1 ,s.max_weight,if(s.scheme_type=3,s.min_amount,0))) as payable,  
// 					s.total_installments,
// IFNULL(IF(sa.is_opening=1,IFNULL(sa.paid_installments,0)+ IFNULL(if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues)),0), if(s.scheme_type = 1 and s.min_weight != s.max_weight or s.scheme_type=3 , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues))) ,0)
//   as paid_installments,
  
  
// IFNULL(IF(sa.is_opening=1,IFNULL(balance_amount,0)+IFNULL(SUM(p.payment_amount * p.no_of_dues),0),IFNULL(SUM(p.payment_amount * p.no_of_dues),0)) ,0)
//   as total_paid_amount,
  
// IFNULL(IF(sa.is_opening=1 and s.scheme_type!=0,IFNULL(balance_weight,0)+IFNULL(SUM(p.metal_weight),0),
// IFNULL(SUM(p.metal_weight),0)),0.000) 
//  as total_paid_weight,
 
//   if((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) > s.total_installments, 
// (s.total_installments - COUNT(payment_amount)), 
// ifnull((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) - SUM(p.no_of_dues),if((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) > s.total_installments,s.total_installments,(PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m')))))) 
//   as totalunpaid_1, 
  
//   if((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) > s.total_installments,   (s.total_installments - if(sa.is_opening = 1,(COUNT(payment_amount)+sa.paid_installments),COUNT(payment_amount))),ifnull(((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m')))+1) - IF(sa.is_opening=1,IFNULL(sa.paid_installments,0)+ IFNULL(if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues)),0), if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues))),if((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) > s.total_installments,s.total_installments,(PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m')))))) as totalunpaid,   
  
//    IFNULL(if(Date_Format(max(p.date_add),'%Y%m') = Date_Format(curdate(),'%Y%m'), (select SUM(ip.no_of_dues) from payment ip where Date_Format(ip.date_add,'%Y%m') = Date_Format(curdate(),'%Y%m') and sa.id_scheme_account = ip.id_scheme_account),IF(sa.is_opening=1, if(Date_Format(sa.last_paid_date,'%Y%m') = Date_Format(curdate(),'%Y%m'), 1,0),0)),0) as currentmonthpaycount, 
  
//   (select SUM(pay.no_of_dues) from payment pay where pay.id_scheme_account= sa.id_scheme_account and pay.due_type='AD' and (pay.payment_status=1 or pay.payment_status=2)) as currentmonth_adv_paycount,
  
//   (select SUM(pay.no_of_dues) from payment pay where pay.id_scheme_account= sa.id_scheme_account and pay.due_type='PD' and (pay.payment_status=1 or pay.payment_status=2)) as currentmonth_pend_paycount,
				
 
// IF(s.scheme_type =1 and s.max_weight !=s.min_weight,true,false) as is_flexible_wgt,p.payment_status,
// 					if(scheme_type=3,IFNULL(cp.total_amount,0),Format(IFNULL(cp.total_amount,0),2)) as  current_total_amount,
// 					Format(IFNULL(cp.total_weight,0) + IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),(sa.last_paid_weight),0) ,3) as  current_total_weight,
// 					IFNULL(cp.paid_installment,0)       as  current_paid_installments,
// 						IFNULL(cp.chances,0) + IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),(sa.last_paid_chances),0) as  current_chances_used,
						
						
// 						if(s.scheme_type=3 && s.pay_duration=0 ,IFNULL(sp.chance,0) + IF(Date_Format(Current_Date(),'%d%m')=Date_Format(sa.last_paid_date,'%d%m'),(sa.last_paid_chances),0),IFNULL(cp.chances,0) + IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),(sa.last_paid_chances),0)) as  current_chances_use,
					
// 				IFNULL(sp.chance,0)as dd,
				
// 					s.is_pan_required,
// 					IF(sa.is_opening = 1 and s.scheme_type = 0,
// 					IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),false,true),
// 					true) AS previous_amount_eligible,
// 					count(pp.id_scheme_account) as cur_month_pdc,
// 					IFNULL(Date_Format(max(p.date_add),'%d-%m-%Y'),IFNULL(IF(sa.is_opening=1,Date_Format(sa.last_paid_date,'%d-%m-%Y'),'')  ,0))                 as last_paid_date,
// 					IFNULL(PERIOD_DIFF(Date_Format(curdate(),'%Y%m'),Date_Format(max(p.date_add),'%Y%m')),IF(sa.is_opening=1,PERIOD_DIFF(Date_Format(curdate(),'%Y%m'),Date_Format(sa.last_paid_date,'%Y%m')),0)) as last_paid_duration,
// 				IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),1,0) as  previous_paid,	
// 					sa.disable_payment,
					
// 				s.allow_unpaid,
// 				if(s.allow_unpaid=1,s.unpaid_months,0) as allow_unpaid_months,
// 				s.allow_advance,
// 				if(s.allow_advance=1,s.advance_months,0) as advance_months,
// 					if(s.allow_unpaid=1,s.unpaid_weight_limit,0) as unpaid_weight_limit,
// 					s.allow_advance,
// 					if(s.allow_advance=1,s.advance_weight_limit,0) as advance_weight_limit,
// 					s.allow_preclose,
// 					if(s.allow_preclose=1,s.preclose_months,0) as preclose_months,
// 					if(s.allow_preclose=1,s.preclose_benefits,0) as preclose_benefits,cs.currency_symbol
// 				From scheme_account sa
// 				Left Join scheme s On (sa.id_scheme=s.id_scheme)
// 				Left Join payment p On (sa.id_scheme_account=p.id_scheme_account and (p.payment_status=2 or p.payment_status=1))
// 				Left Join customer c On (sa.id_customer=c.id_customer and c.active=1)
// 				Left Join scheme_group sg On (sa.group_code = sg.group_code and sa.group_code is not null)
// 				Left Join
// 					(	Select
// 						  sa.id_scheme_account,
// 						  COUNT(Distinct Date_Format(p.date_add,'%Y%m')) as paid_installment,
// 						  COUNT(Date_Format(p.date_add,'%Y%m')) as chances,	
// 						  SUM(p.payment_amount) as total_amount,
// 						  SUM(p.metal_weight) as total_weight
// 						From payment p
// 						Left Join scheme_account sa on(p.id_scheme_account=sa.id_scheme_account and sa.active=1 and sa.is_closed=0)
// 						Where  (p.payment_status=2 or p.payment_status=1) and  Date_Format(Current_Date(),'%Y%m')=Date_Format(p.date_add,'%Y%m')
// 						Group By sa.id_scheme_account
// 					) cp On (sa.id_scheme_account=cp.id_scheme_account)
// 					left join(Select sa.id_scheme_account, COUNT(Distinct Date_Format(p.date_add,'%d%m')) as paid_installment,
// 				COUNT(Date_Format(p.date_add,'%d%m')) as chance,
// 			SUM(p.payment_amount) as total_amount,
// 			SUM(p.metal_weight) as total_weight
// 			From payment p
// 			Left Join scheme_account sa on(p.id_scheme_account=sa.id_scheme_account and sa.active=1 and sa.is_closed=0)
// 			Where  (p.payment_status=2 or p.payment_status=1) and  Date_Format(Current_Date(),'%d%m')=Date_Format(p.date_add,'%d%m')
// 		Group By sa.id_scheme_account)sp on(sa.id_scheme_account=sp.id_scheme_account)
					
					
// 				 Left Join postdate_payment pp On (sa.id_scheme_account=pp.id_scheme_account and (pp.payment_status=2 or pp.payment_status=7) and (Date_Format(pp.date_payment,'%Y%m')=Date_Format(curdate(),'%Y%m')))	
// 				 join chit_settings cs 
// 				Where sa.active=1 and sa.is_closed = 0 and sa.id_scheme_account='$id_scheme_account'
// 				Group By sa.id_scheme_account";
		
// 	//	echo $sql;exit;
// 		$records = $this->db->query($sql);
	
// //if(Date_Format(max(p.date_add),'%Y%m') = Date_Format(curdate(),'%Y%m'), SUM(p.no_of_dues),0)  as currentmonthpaycount,	
 	
// 		if($records->num_rows()>0)
// 		{
// 				$record = $records->row();
				
// 				$allowed_due = 0;
// 				$due_type = '';
// 				$checkdues=FALSE;
// 				if($record->has_luckydraw == 1 )
// 				{ 
// 					if( $record->group_start_date == NULL && $record->paid_installments > 1)
// 					{ // block 2nd payment if scheme_group_code is not updated  
// 						$checkDues = FALSE; 
// 					}else if($record->group_start_date != NULL)
// 					{ // block  payment after end date
// 						 if($record->group_end_date >= time() )
// 						 {
// 						 	$checkDues = TRUE;
// 						 }
// 						 else{
// 							$checkDues = FALSE;
// 						 }
// 					}
// 				}
// 				if($checkdues)
// 				{
// 				if($record->paid_installments > 0 || $record->totalunpaid >0)
// 				{
// 					if($record->currentmonthpaycount == 0)
// 					{  // current month not paid (allowed pending due + current due)
// 						if($record->allow_unpaid == 1){
// 							if($record->allow_unpaid_months > 0 && ($record->total_installments - $record->paid_installments) >=  $record->allow_unpaid_months && $record->totalunpaid >0){
// 								if(($record->total_installments - $record->paid_installments) ==  $record->allow_unpaid_months){
// 									$allowed_due = ($record->totalunpaid < $record->allow_unpaid_months ? $record->totalunpaid : $record->allow_unpaid_months) ;  
// 								    $due_type = 'PD'; //  pending
// 								}
// 								else{
// 									$allowed_due =  ($record->totalunpaid < $record->allow_unpaid_months ? $record->totalunpaid : $record->allow_unpaid_months)+1 ;  
// 								    $due_type = 'PN'; // normal and pending
// 								}
								 
// 							}
// 							else{
// 							     $allowed_due =  1;
// 							     $due_type = 'ND'; // normal due
// 							}
// 						}
// 						else{
// 							 $allowed_due =  1;
// 							 $due_type = 'ND'; // normal due
// 						}
// 					}
// 					else{ 	//current month paid
					
// 						if($record->allow_unpaid == 1 && $record->allow_unpaid_months >0 && $record->totalunpaid >0 && ($record->currentmonthpaycount-1) < $record->allow_unpaid_months){  
// 							// can pay previous pending dues if attempts available 
// 							if($record->totalunpaid > $record->allow_unpaid_months){
// 								 $allowed_due =  $record->allow_unpaid_months ;
// 								 $due_type = 'PD'; // pending due
// 							}
// 							else{ 
// 								 $allowed_due =  $record->totalunpaid;
// 								 $due_type = 'PD'; // pending due
// 							}
// 						}
// 						else{  // check allow advance
// 							if($record->allow_advance == 1 && $record->advance_months > 0 && ($record->currentmonth_adv_paycount) < $record->advance_months){
// 								if(($record->advance_months + $record->paid_installments) <= $record->total_installments){
// 									 $allowed_due =  ($record->advance_months - ($record->currentmonth_adv_paycount));
// 									 $due_type = 'AD'; // advance due
// 								}
// 								else{
// 									 $allowed_due =  ($record->total_installments - $record->paid_installments);
// 									 $due_type = 'AD'; // advance due
// 								}
// 							}
// 							else{ // have to check
// 								 $allowed_due =  0;
// 								 $due_type = ''; // normal due
// 							}
						
// 						}
// 					}
// 				}
// 				else
// 				{  // check allow advance and add due with currect month (allowed advance due + current due)
// 					if($record->allow_advance ==1)
// 					{ // check allow advance
// 						if($record->advance_months > 0 && $record->advance_months <= ($record->total_installments - $record->paid_installments)){
// 							if(($record->total_installments - $record->paid_installments) ==  $record->advance_months){
// 									 $allowed_due =  $record->advance_months;
// 									 $due_type = 'AN'; // advance and normal
// 								}
// 								else{
// 									$allowed_due =  $record->advance_months+1 ;  
// 								     $due_type = 'AN'; // advance and normal
// 								}
							
// 						}
// 						else{
// 							 $allowed_due =  1;
// 							 $due_type = 'ND'; // normal due
// 						}
// 					}
// 					else{
// 						 $allowed_due =  1;
// 						 $due_type = 'ND'; // normal due
// 					}
				
// 				}
// 			}	
				
// 				$pdc_det = $this->get_pending_pdc($record->id_scheme_account);
					
// 					$dates=date('d-m-Y');
					
// 				$schemeAcc = array(
						
// 									'metal_rate'=> $record->metal_rate,
// 									'min_amount'=>	$record->min_amount,
// 									'max_amount'=>	$record->max_amount,
// 									'min_amt_chance'=>	$record->min_amt_chance,
// 									'max_amt_chance'=>	$record->max_amt_chance,
// 									'gst' => $record->gst,
									
// 									'scheme_overall_amount' => $record->scheme_overall_amount,
									
									
// 									'gst_type' => $record->gst_type,
// 									'currentmonth_adv_paycount' => $record->currentmonth_adv_paycount,
// 									'currentmonthpaycount' 		=> $record->currentmonthpaycount,
									
// 									'current_date' 		=> $dates,
// 									'totalunpaid' 				=> $record->totalunpaid,
// 									'id_scheme_account' 		=> $record->id_scheme_account,
// 									'start_date' 				=> $record->start_date,
// 									'chit_number' 				=> $record->chit_number,
// 									'account_name' 				=> $record->account_name,
// 									'payable' 					=> $record->payable,
// 									'scheme_name' 				=> $record->scheme_name,
// 									'code' 						=> $record->code,
// 									'scheme_type' 				=> $record->scheme_type,
// 									'currency_symbol'			=> $record->currency_symbol,
// 									'min_weight' 				=> $record->min_weight,
// 									'max_weight' 				=> $record->max_weight,
// 									'wgt_convert' 				=> $record->wgt_convert,
// 									'total_installments' 		=> $record->total_installments,
// 									'paid_installments' 		=> $record->paid_installments,
// 									'total_paid_amount' 		=> $record->total_paid_amount,
// 									'total_paid_weight' 		=> $record->total_paid_weight,
// 									'current_total_amount' 		=> $record->current_total_amount,
// 									'current_paid_installments' => $record->current_paid_installments,
// 									'current_chances_used' 		=> $record->current_chances_used,
// 									'current_chances_use' 		=> $record->current_chances_use,
// 									'current_total_weight' 		=> $record->current_total_weight,
// 									'last_paid_duration' 		=> $record->last_paid_duration,
// 									'last_paid_date' 			=> $record->last_paid_date,
// 									'is_pan_required' 			=> $record->is_pan_required,
// 									'last_transaction'     		=> $this->getLastTransaction($record->id_scheme_account),
// 									'isPaymentExist' 			=> $this->isPaymentExist($record->id_scheme_account),
// 									'previous_amount_eligible'  => $record->previous_amount_eligible,
// 									'cur_month_pdc'             => $record->cur_month_pdc,
// 									'is_flexible_wgt'           => $record->is_flexible_wgt,
									
// 									'max_chance'           => $record->max_chance,
// 									'ref_benifitadd_ins'    => $record->ref_benifitadd_ins,
// 									'ref_benifitadd_ins_type' => $record->ref_benifitadd_ins_type,
// 									'referal_code'         => $record->referal_code,
// 									'max_chance'           => $record->max_chance,
									
// 									/*'allow_pay'  => ($record->scheme_type==3  &&$record->paid_installments <  $record->total_installments  && $record->current_chances_use < $record->max_chance &&$record-> current_total_amount < $record-> max_amount?'Y':($record->disable_payment != 1 && ($record->payment_status !=2) ? ($record->cur_month_pdc < 1 ? ($record->paid_installments <= $record->total_installments ?($record->is_flexible_wgt?($record->current_total_weight >= $record->max_weight || $record->current_chances_used >= $record->max_chance ?'N':'Y'):($record->paid_installments <  $record->total_installments ?($record->allow_unpaid == 1  && $record->totalunpaid >0 && ($record->currentmonthpaycount-1) < $record->allow_unpaid_months ?'Y':($record->allow_advance == 1 && $record->advance_months > 0 && ($record->currentmonthpaycount -1) < $record->advance_months ?'Y':($record->currentmonthpaycount == 0 ? 'Y': 'N'))):'N')):'N'):'N'):'N')),*/
									
// 									'allow_pay'  => ($record->scheme_type==3  && $record->paid_installments <= $record->total_installments  && $record->current_chances_use < $record->max_chance && ($record-> current_total_amount < $record-> max_amount || $record-> current_total_weight < $record-> max_weight ) ?'Y':($record->disable_payment != 1 && ($record->payment_status !=2) ? ($record->cur_month_pdc < 1 ? ($record->paid_installments <= $record->total_installments ?($record->is_flexible_wgt?($record->current_total_weight >= $record->max_weight || $record->current_chances_used >= $record->max_chance ?'N':'Y'):($record->paid_installments <  $record->total_installments ?($record->allow_unpaid == 1  && $record->totalunpaid >0 && ($record->currentmonthpaycount-1) < $record->allow_unpaid_months ?'Y':($record->allow_advance == 1 && $record->advance_months > 0 && ($record->currentmonthpaycount -1) < $record->advance_months ?'Y':($record->currentmonthpaycount == 0 ? 'Y': 'N'))):'N')):'N'):'N'):'N')),
									
// 									 'allowed_dues'  			=>($record->is_flexible_wgt == 1 || $record->scheme_type ==3? 1:$allowed_due),
// 									 'due_type' 		=> ($record->is_flexible_wgt == 1 ? 'ND':$due_type),
// 									 'allow_preclose' 	=> ($record->currentmonthpaycount == 1 ? ($record->allow_preclose ==1?($record->total_installments - $record->paid_installments == $record->preclose_months ? 1 : 0):0):0),
// 									 'pdc_payments'  =>($record->cur_month_pdc > 0 ? $this->get_postdated_payment($record->id_scheme_account) : 0) ,
// 									 'total_pdc'  =>( isset($pdc_det) && $pdc_det !='' ? $pdc_det : 0) ,
// 									 'weights'  => ( $record->scheme_type=='1'? $this->getWeights() :''),
// 									 'preclose' => ($record->allow_preclose ==1?$record->preclose_months:0),
// 									 'preclose_benefits' =>($record->allow_preclose ==1?$record->preclose_benefits:0)
// 									);		
									
									
									
// 				}	
				
// 			//print_r($schemeAcc); exit;
				
// 			return	$schemeAcc;
// 		}	

    function isPaid($paidByMonth,$Ym){
    	foreach($paidByMonth as $p){
    		if($Ym == $p['due_year']."-".str_pad($p['due_month'], 2, '0', STR_PAD_LEFT)){
    			return true;
    		}
    	}
    	return false;
    }
    
    function add_months_to_date($no_of_months, $date){
        $year_month = Date("Y-m", strtotime($date));
        $year_month_incremented = Date("Y-m", strtotime($year_month . " +".$no_of_months." Month "));
        $month_end_dt = strtotime('last day of this month', strtotime($year_month_incremented));
        return $month_end_dt;
    }
	
    function get_paymentContent($id_scheme_account)
	{  
	   $schemeAcc = array();
	   
		$sql="Select s.id_metal,
					maturity_type,maturity_installment,disable_pay_reason,sg.group_code as scheme_group_code, UNIX_TIMESTAMP(Date_Format(sg.start_date,'%Y-%m-%d')) as group_start_date,  UNIX_TIMESTAMP(Date_Format(sg.end_date,'%Y-%m-%d')) as  group_end_date,  cs.has_lucky_draw,s.is_lucky_draw,s.firstPayamt_maxpayable,sa.firstPayment_amt,sa.firstpayment_wgt,sa.is_registered,s.flx_denomintion,s.flexible_sch_type,
					s.allowSecondPay,s.free_payment,s.get_amt_in_schjoin,s.firstPayamt_as_payamt,sa.maturity_date,s.maturity_days,b.name as name,sa.id_branch as id_branch,cs.cost_center,
					if((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) > s.total_installments, 
                    (s.total_installments - COUNT(payment_amount)), 
                    ifnull((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) - SUM(p.no_of_dues),if((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) > s.total_installments,s.total_installments,(PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m')))))) 
                    as missed_ins,sa.avg_payable,s.avg_calc_ins,
					PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m')) as months_from_startdate,PERIOD_DIFF(Date_Format(sa.maturity_date,'%Y%m'),Date_Format(curdate(),'%Y%m')) as tot_ins,
					s.min_amt_chance,s.max_amt_chance,s.code,s.min_amount,c.mobile,
				   	sa.id_scheme_account,s.gst,s.gst_type,s.max_amount,
					s.id_scheme,s.wgt_convert,if(s.cus_refferal=1 || s.emp_refferal=1,sa.referal_code,'')as referal_code,
					s.ref_benifitadd_ins_type,s.ref_benifitadd_ins,
					c.id_customer,
					CONCAT(s.code,'-',IFNULL(sa.scheme_acc_number,'Not Allocated')) as chit_number,
					IFNULL(sa.account_name,if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname))) as account_name,
					s.scheme_name,s.discount_type,s.discount_installment,s.discount,s.firstPayDisc_value,
					s.scheme_type,
					if(scheme_type=3,if(s.max_amount!='',s.max_amount * s.total_installments,s.max_weight),s.amount)as scheme_overall_amount,
					IFNULL(s.min_chance,0) as min_chance,
					IFNULL(s.max_chance,0) as max_chance,
					Format(IFNULL(s.max_weight,0),3) as max_weight,
					Format(IFNULL(s.min_weight,0),3) as min_weight,
					Date_Format(sa.start_date,'%d-%m-%Y') as start_date,
					(SELECT m.goldrate_22ct FROM metal_rates m  order by id_metalrates Desc LIMIT 1) as metal_rate, 
                    IF(s.scheme_type=0 OR s.scheme_type=2,s.amount,IF(s.scheme_type=1 ,s.max_weight,if(s.scheme_type=3,if(flexible_sch_type = 3 ,  s.max_weight,if(sa.firstPayment_amt > 0,sa.firstPayment_amt ,s.min_amount)),0))) as payable,					s.total_installments,
                    
                    IFNULL(IF(sa.is_opening=1,IFNULL(sa.paid_installments,0)+ IFNULL(if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues)),0), if(s.scheme_type = 1 and s.min_weight != s.max_weight or (s.scheme_type=3 AND s.firstPayamt_as_payamt = 0) , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues))) ,0)  as paid_installments,
                    
                    IFNULL(IF(sa.is_opening=1,IFNULL(balance_amount,0)+IFNULL(SUM(p.payment_amount * p.no_of_dues),0),IFNULL(SUM(p.payment_amount * p.no_of_dues),0)) ,0) as total_paid_amount,
                    IFNULL(IF(sa.is_opening=1 and s.scheme_type!=0,IFNULL(balance_weight,0)+IFNULL(SUM(p.metal_weight),0),IFNULL(SUM(p.metal_weight),0)),0.000) as total_paid_weight,
                    if((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) > s.total_installments,   (s.total_installments - if(sa.is_opening = 1,(count(DISTINCT((Date_Format(p.date_payment,'%Y%m'))))+sa.paid_installments),count(DISTINCT((Date_Format(p.date_payment,'%Y%m')))))),ifnull(((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m')))+1) - IF(sa.is_opening=1,IFNULL(sa.paid_installments,0)+ IFNULL(if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues)),0), if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues))),if((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) > s.total_installments,s.total_installments,(PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m')))))) as totalunpaid,   
                    IFNULL(if(Date_Format(max(p.date_add),'%Y%m') = Date_Format(curdate(),'%Y%m'), (select SUM(ip.no_of_dues) from payment ip where Date_Format(ip.date_add,'%Y%m') = Date_Format(curdate(),'%Y%m') and sa.id_scheme_account = ip.id_scheme_account),IF(sa.is_opening=1, if(Date_Format(sa.last_paid_date,'%Y%m') = Date_Format(curdate(),'%Y%m'), 1,0),0)),0) as currentmonthpaycount, 
                    (select count(pay.no_of_dues) from payment pay where pay.id_scheme_account= sa.id_scheme_account and pay.due_type='AD' and (pay.payment_status=1 or pay.payment_status=2)) as currentmonth_adv_paycount,
                    (select count(pay.no_of_dues) from payment pay where pay.id_scheme_account= sa.id_scheme_account and pay.due_type='PD' and (pay.payment_status=1 or pay.payment_status=2)) as currentmonth_pend_paycount,
                    IF(s.scheme_type =1 and s.max_weight !=s.min_weight,true,false) as is_flexible_wgt,p.payment_status,
					if(scheme_type=3,IFNULL(cp.total_amount,0),Format(IFNULL(cp.total_amount,0),2)) as  current_total_amount,
					Format(IFNULL(cp.total_weight,0) + IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),(sa.last_paid_weight),0) ,3) as  current_total_weight,
					IFNULL(cp.paid_installment,0)       as  current_paid_installments,
					IFNULL(cp.chances,0) + IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),(sa.last_paid_chances),0) as  current_chances_used,
					if(s.scheme_type=3 && s.pay_duration=0 ,IFNULL(sp.chance,0) + IF(Date_Format(Current_Date(),'%d%m')=Date_Format(sa.last_paid_date,'%d%m'),(sa.last_paid_chances),0),IFNULL(cp.chances,0) + IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),(sa.last_paid_chances),0)) as  current_chances_use,
					IFNULL(sp.chance,0)as dd,
				    s.is_pan_required,
					IF(sa.is_opening = 1 and s.scheme_type = 0,
					IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),false,true),
					true) AS previous_amount_eligible,
					count(pp.id_scheme_account) as cur_month_pdc,
					IFNULL(Date_Format(max(p.date_payment),'%d-%m-%Y'),IFNULL(IF(sa.is_opening=1,Date_Format(sa.last_paid_date,'%d-%m-%Y'),'')  ,0)) as last_paid_date,
					IFNULL(PERIOD_DIFF(Date_Format(curdate(),'%Y%m'),Date_Format(max(p.date_add),'%Y%m')),IF(sa.is_opening=1,PERIOD_DIFF(Date_Format(curdate(),'%Y%m'),Date_Format(sa.last_paid_date,'%Y%m')),0)) as last_paid_duration,
				    IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),1,0) as  previous_paid,	
					sa.disable_payment,s.disable_sch_payment,s.stop_payment_installment,s.one_time_premium, 
                    s.allow_unpaid,
                    if(s.allow_unpaid=1,s.unpaid_months,0) as allow_unpaid_months,
                    s.allow_advance,
                    if(s.allow_advance=1,s.advance_months,0) as advance_months,
                    if(s.allow_unpaid=1,s.unpaid_weight_limit,0) as unpaid_weight_limit,
                    s.allow_advance,
                    if(s.allow_advance=1,s.advance_weight_limit,0) as advance_weight_limit,
                    s.allow_preclose,
                    if(s.allow_preclose=1,s.preclose_months,0) as preclose_months,
                    if(s.allow_preclose=1,s.preclose_benefits,0) as preclose_benefits,cs.currency_symbol
				From scheme_account sa
				Left Join scheme s On (sa.id_scheme=s.id_scheme)
				Left Join branch b On (b.id_branch=sa.id_branch)
				Left Join payment p On (sa.id_scheme_account=p.id_scheme_account and (p.payment_status=2 or p.payment_status=1))
				Left Join customer c On (sa.id_customer=c.id_customer and c.active=1)
				Left Join scheme_group sg On (sa.group_code = sg.group_code )
				Left Join
					(	Select
						  sa.id_scheme_account,
						  COUNT(Distinct Date_Format(p.date_payment,'%Y%m')) as paid_installment,
						  COUNT(Date_Format(p.date_payment,'%Y%m')) as chances,	
						  SUM(p.payment_amount) as total_amount,
						  SUM(p.metal_weight) as total_weight
						From payment p
						Left Join scheme_account sa on(p.id_scheme_account=sa.id_scheme_account and sa.active=1 and sa.is_closed=0)
						Where  (p.payment_status=2 or p.payment_status=1) and  Date_Format(Current_Date(),'%Y%m')=Date_Format(p.date_payment,'%Y%m')
						Group By sa.id_scheme_account
					) cp On (sa.id_scheme_account=cp.id_scheme_account)
				left join(Select sa.id_scheme_account, COUNT(Distinct Date_Format(p.date_payment,'%d%m')) as paid_installment,
    				COUNT(Date_Format(p.date_payment,'%d%m')) as chance,
    			SUM(p.payment_amount) as total_amount,
    			SUM(p.metal_weight) as total_weight
    			From payment p
    			Left Join scheme_account sa on(p.id_scheme_account=sa.id_scheme_account and sa.active=1 and sa.is_closed=0)
    			Where  (p.payment_status=2 or p.payment_status=1) and  Date_Format(Current_Date(),'%d%m')=Date_Format(p.date_payment,'%d%m')
    		    Group By sa.id_scheme_account)sp on(sa.id_scheme_account=sp.id_scheme_account) 
				Left Join postdate_payment pp On (sa.id_scheme_account=pp.id_scheme_account and (pp.payment_status=2 or pp.payment_status=7) and (Date_Format(pp.date_payment,'%Y%m')=Date_Format(curdate(),'%Y%m')))	
				join chit_settings cs 
				Where sa.active=1 and sa.is_closed = 0 and sa.id_scheme_account='$id_scheme_account'
				Group By sa.id_scheme_account";
//print_r($sql);exit;
		$records = $this->db->query($sql);
//if(Date_Format(max(p.date_add),'%Y%m') = Date_Format(curdate(),'%Y%m'), SUM(p.no_of_dues),0)  as currentmonthpaycount,	
 	
		if($records->num_rows()>0)
		{
				$record = $records->row();
				
				// Calculate max payable [Applicable only for No advance, No pending enabled schemes]
				if((($record->scheme_type == 1 && $record->is_flexible_wgt == 1) || $record->scheme_type == 3)&&  $record->avg_calc_ins > 0){
					$current_installments = ($record->current_paid_installments == 0 ? $record->paid_installments+1 : $record->paid_installments);
					// Prevoius Ins == Average calc installment
					if(($current_installments-1 == $record->avg_calc_ins || $record->avg_payable > 0) && $record->avg_calc_ins > 0){
						if($record->avg_payable > 0){ // Already Average calculated, just set the value
							if($record->scheme_type == 1 && $record->is_flexible_wgt == 1 ){ // Weight - Flexible weight scheme
								// Set max payable
							}
							else if($record->scheme_type == 3 ){
								if($record->flexible_sch_type == 2){ // Flexible - Amount to weight [amount based]
									// Set max payable
								}
								elseif($record->flexible_sch_type == 3){ // Flexible - Amount to weight [weight based]
									$record->max_weight = $record->avg_payable;
								}						
							}
						}else{ // Calculate Average , set the value and updte it in schemme_account table
							$paid_sql = $this->db->query("SELECT date(date_payment) as date_payment,sum(metal_weight) as paid_wgt,sum(payment_amount) as paid_amt FROM `payment` WHERE ( payment_status=1 or payment_status=2 ) and id_scheme_account=".$record->id_scheme_account." GROUP BY YEAR(date_payment), MONTH(date_payment)");
							$paid_wgt = 0;
							$paid_amt = 0;
							$paidByMonth = $paid_sql->result_array();
							foreach($paidByMonth as $p){
								$paid_wgt = $paid_wgt + $p['paid_wgt'];
								$paid_amt = $paid_amt + $p['paid_amt'];
							}
							if($record->scheme_type == 1 && $record->is_flexible_wgt == 1 ){ // Weight - Flexible weight scheme
								// Set max payable
							}
							else if($record->scheme_type == 3 ){
								if($record->flexible_sch_type == 2){ // Flexible - Amount to weight [amount based]
									// Set max payable
								}
								elseif($record->flexible_sch_type == 3){ // Flexible - Amount to weight [weight based]
									$avg_payable = number_format($paid_wgt/$record->avg_calc_ins,3);
									$record->max_weight = $avg_payable;
								}						
							}
							$updData = array( "avg_payable" => $avg_payable, "date_upd" => date("Y-m-d") );
							$this->db->where('id_scheme_account',$record->id_scheme_account); 
		 					$this->db->update("scheme_account",$updData);
						} 
						
					}
					/*else if($current_installments > $record->avg_calc_ins){ // Prevoius Ins > Average calc installment
						if($record->scheme_type == 1 && $record->is_flexible_wgt == 1 ){ // Weight - Flexible weight scheme
							// Set max payable
						}
						else if($record->scheme_type == 3 ){
							if($record->flexible_sch_type == 2){ // Flexible - Amount to weight [amount based]
								// Set max payable
							}
							elseif($record->flexible_sch_type == 3){ // Flexible - Amount to weight [weight based]
								$record->max_weight = $record->avg_payable; 
							}						
						}
					}*/
				}
				
				
				$allowed_due = 0;
				$due_type = '';
				$checkDues = TRUE;
				$allowSecondPay = FALSE;
				$metal_rates=$this->get_metalrate_by_branch($record->id_branch);//For branchwise rate
				
				$maturity_date = strtotime($record->maturity_date);
				$today = time();
				$difference = $today - $maturity_date;
				$days=(abs(floor($difference / 86400)));
			
				if($record->has_lucky_draw == 1 && $record->is_lucky_draw == 1 )
				{ 
					if( $record->group_start_date == NULL && $record->paid_installments >1)
					{ // block 2nd payment if scheme_group_code is not updated 
						$checkDues = FALSE; 
					}
					
					else if($record->group_start_date != NULL){ // block  payment after end date
                        if($record->group_end_date >= time() && $record->group_start_date <= time() ){
                            $checkDues = TRUE;
                        }else{
                            $checkDues = FALSE;
                        }
					}
				}
				
				if($record->maturity_days!=null)
				{
			       $current_date =date("Y-m-d");
			       $maturity_date=$record->maturity_date;
			       if(strtotime($current_date) <= strtotime($maturity_date)) 
                    { 
                        $checkDues=TRUE;
                        if($record->maturity_type == 2){ // 1 - Flexible[Can pay installments and close], 2 - Fixed Maturity, 3 - Fixed Flexible[Increase maturity if has Default]
                         	if(($record->missed_ins+$record->paid_installments)<=$record->total_installments)
            				{
            				    $checkDues=TRUE;
            				}else{
            				    $checkDues = FALSE;
            				}
                        }
                    }
                    else
                    {
                        $checkDues=FALSE;
                    }
				}
				
				// Update Maturity Date in scheme_account table if maturity date is flexible
        		if($record->maturity_type == 3){  // 1 - Flexible[Can pay installments and close], 2 - Fixed Maturity, 3 - Fixed Flexible[Increase maturity if has Default]
        			
    			    $paid_sql = $this->db->query("SELECT due_month,due_year FROM `payment` WHERE ( payment_status=1 or payment_status=2 ) and id_scheme_account=".$record->id_scheme_account." GROUP BY due_month, due_year order by due_year,due_month");
    			    $paidByMonth = $paid_sql->result_array();
    			    $skipped_months = 0;
                     
                    for($i = 0; $i >= 0 ;$i++){
                    	// $date = date('Y-m-d', strtotime("+".$i." months", strtotime($record->start_date)));
                    	// $Ym = date('Y-m', strtotime("+".$i." months", strtotime($record->start_date)));
                        $Ym = date('Y-m', $this->add_months_to_date($i,$record->start_date));
                    	if($Ym != date("Y-m")){
                    		$isPaid = $this->isPaid($paidByMonth,$Ym);
                    		$skipped_months = $skipped_months + ($isPaid ? 0 : 1);
                    		//echo $Ym."--".date("Y-m")."--".$skipped_months."<br/>";
                    	}
                    	else if($Ym == date("Y-m")){ // Quit Loop
                    		$i = -2;
                    	}
                    } 
                    $maturity =  date('Y-m-d', strtotime("+".($record->total_installments+$skipped_months)." months", strtotime($record->start_date)));
                   /* $no_of_months = ( $record->total_installments+$skipped_months );
                    $maturity = date('Y-m-d', $this->add_months_to_date($no_of_months,$record->start_date));  */
                    if($record->maturity_date != $maturity){
        			    $updData = array( "maturity_date" => $maturity, "date_upd" => date("Y-m-d") );
        				$this->db->where('id_scheme_account',$record->id_scheme_account); 
         				$this->db->update("scheme_account",$updData);
                    }
        		}
				
				if($checkDues){
    				if($record->maturity_type != 2){  // 1 - Flexible[Can pay installments and close], 2 - Fixed Maturity, 3 - Fixed Flexible[Increase maturity if has Default] 
    				$proceed = ($record->scheme_type == 3 ? ($record->paid_installments == 0 ? FALSE : TRUE) : ($record->paid_installments > 0 || $record->totalunpaid >0) );
    					if($proceed ){
    						if($record->currentmonthpaycount == 0){  // current month not paid (allowed pending due + current due)
    							if($record->allow_unpaid == 1){
    								if($record->allow_unpaid_months > 0 && ($record->total_installments - $record->paid_installments) >=  $record->allow_unpaid_months && $record->totalunpaid >0){
    									if(($record->total_installments - $record->paid_installments) ==  $record->allow_unpaid_months){
    										$allowed_due = ($record->totalunpaid < $record->allow_unpaid_months ? $record->totalunpaid : $record->allow_unpaid_months) ;  
    									    $due_type = 'PD'; //  pending
    									}
    									else{
    										$allowed_due =  ($record->totalunpaid < $record->allow_unpaid_months ? $record->totalunpaid : $record->allow_unpaid_months)+1 ;  
    									    $due_type = 'PN'; // normal and pending
    									}
    									 
    								}
    								else{
    								     $allowed_due =  1;
    								     $due_type = 'ND'; // normal due
    								}
    							}
    							else{
        							// current month not paid (allowed advance due + current due)
        							if($record->allow_advance ==1){ // check allow advance
                						if($record->advance_months > 0 && $record->advance_months <= ($record->total_installments - $record->paid_installments)){
                							if(($record->total_installments - $record->paid_installments) ==  $record->advance_months){
                									 $allowed_due =  $record->advance_months;
                									 $due_type = 'AN'; // advance and normal
                								}
                								else{
                									$allowed_due =  $record->advance_months+1 ;  
                								     $due_type = 'AN'; // advance and normal
                								}
                							
                						}
                						else{
                							 $allowed_due =  1;
                							 $due_type = 'ND'; // normal due
                						}
                					}
                					else{
                						 $allowed_due =  1;
                						 $due_type = 'ND'; // normal due
                					}
    							}
    						}
    						else{ 	//current month paid
    						
    						    if($record->free_payment == 1 && $record->allowSecondPay == 1 && $record->paid_installments == 1){
    								$allowed_due =  1 ;
        							$due_type = 'AD'; // adv due
        							$allowSecondPay = TRUE;
    							}else{
        							if($record->allow_unpaid == 1 && $record->allow_unpaid_months >0 && $record->totalunpaid >0 && ($record->currentmonthpaycount-1) < $record->allow_unpaid_months){  
        								// can pay previous pending dues if attempts available 
        								if($record->totalunpaid > $record->allow_unpaid_months){
        									 $allowed_due =  $record->allow_unpaid_months ;
        									 $due_type = 'PD'; // pending due
        								}
        								else{ 
        									 $allowed_due =  $record->totalunpaid;
        									 $due_type = 'PD'; // pending due
        								}
        							}
        							else{  // check allow advance
        								if(($record->allow_advance == 1) && ($record->advance_months > 0) && (($record->currentmonth_adv_paycount) < $record->advance_months)){
        									if(($record->advance_months + $record->paid_installments) <= $record->total_installments){
        										 $allowed_due =  ($record->advance_months - ($record->currentmonth_adv_paycount));
        										 $due_type = 'AD'; // advance due
        									}
        									else{
        										 $allowed_due =  ($record->total_installments - $record->paid_installments);
        										 $due_type = 'AD'; // advance due
        									}
        								}
        								else{ // have to check
        									 $allowed_due =  0;
        									 $due_type = ''; // normal due
        								}
        							
        							}
    							}
    						}
    					}
    					else{  // check allow advance and add due with currect month (allowed advance due + current due)
    					    if($record->scheme_type == 3){ // Donot allow advance/pending on first due payment [Flexible scheme]
    					        $allowed_due =  1;
        						$due_type = 'ND'; // normal due
    					    }else{
        						if($record->allow_advance ==1){ // check allow advance
        							if($record->advance_months > 0 && $record->advance_months <= ($record->total_installments - $record->paid_installments)){
        								if(($record->total_installments - $record->paid_installments) ==  $record->advance_months){
        										 $allowed_due =  $record->advance_months;
        										 $due_type = 'AN'; // advance and normal
        									}
        									else{
        										$allowed_due =  $record->advance_months+1 ;  
        									     $due_type = 'AN'; // advance and normal
        									}
        								
        							}
        							else{
        								 $allowed_due =  1;
        								 $due_type = 'ND'; // normal due
        							}
        						}
        						else{
        							 $allowed_due =  1;
        							 $due_type = 'ND'; // normal due
        						}
    					    }
    					}
    				}else{ // Fixed Maturity Date
    				    if($record->maturity_date !=NULL && $record->maturity_date!='') // Jewelone
        				{
        				     $due =  $record->tot_ins - $record->months_from_startdate; // months_from_startdate -> No. of months from start date
        				     if($record->advance_months>$due)
        				     {
        				         $allow_due=$record->advance_months;
        				     }
        				     else
        				     {
        				         $allow_due=$due;
        				     }
        				}
        				
        				if(!empty($record->maturity_days) && $record->allow_unpaid == 0) // ** Advance Only. No Pending allowed. ** //
                        {
        	                if( $record->advance_months > 0){
        		                if($record->current_paid_installments == 0 )  // Current month not Paid (Current+Advance)
        		                {
        			                $allowed_due = $record->total_installments-$record->current_pay_installemnt;
        			                $due_type='AN';
        		                }
        		                else // Current month Paid (Advance)
        		                {
        			                $allowed_due = $record->total_installments - ($record->current_pay_installemnt+$record->current_paid_installments);
        			                $due_type='AD';
        		                }
        	                }
                        } 
    				}
				} 
				
				
				
				$pdc_det = $this->get_pending_pdc($record->id_scheme_account);
				$dates=date('d-m-Y');
				// Allow Pay
				if($record->scheme_type == 3){
					$allow_pay  = ($record->disable_payment != 1 && $record->payment_status !=2  && $record->paid_installments <= $record->total_installments ?   ($record->flexible_sch_type == 3 || $record->flexible_sch_type == 4 ? ($record->current_total_weight >= $record->max_weight || $record->current_chances_used >= $record->max_chance ?($record->paid_installments <  $record->total_installments ?($record->allow_unpaid == 1  && $record->totalunpaid >0 && ($record->currentmonthpaycount-1) < $record->allow_unpaid_months ?'Y':($record->allow_advance == 1 && $record->advance_months > 0 && ($record->currentmonthpaycount -1) < $record->advance_months ?'Y':($record->currentmonthpaycount == 0 ? 'Y': 'N'))):'N'):'Y') : ($record->current_total_amount >= $record->max_amount || ($record->firstPayment_amt >0 && $record->current_total_amount >= $record->firstPayment_amt) || $record->current_chances_used >= $record->max_chance ?($record->paid_installments <  $record->total_installments ?($record->allow_unpaid == 1  && $record->totalunpaid >0 && ($record->currentmonthpaycount-1) < $record->allow_unpaid_months ?'Y':($record->allow_advance == 1 && $record->advance_months > 0 && ($record->currentmonthpaycount -1) < $record->advance_months ?'Y':($record->currentmonthpaycount == 0 ? 'Y': 'N'))):'N'):'Y')):'N');
					//$allow_pay  = ($record->disable_payment != 1 && $record->payment_status !=2  && $record->paid_installments <= $record->total_installments ? ($record->flexible_sch_type == 3 || $record->flexible_sch_type == 4 ? ($record->current_total_weight >= $record->max_weight || $record->current_chances_used >= $record->max_chance ?'N':'Y') : ($record->current_total_amount >= $record->max_amount || ($record->firstPayment_amt >0 && $record->current_total_amount >= $record->firstPayment_amt) || $record->current_chances_used >= $record->max_chance ?'N':'Y')):'N');
				}else{
					$allow_pay  = ($record->disable_payment != 1 && ($record->payment_status !=2) ? ($record->cur_month_pdc < 1 ? ($record->paid_installments <= $record->total_installments ?($record->is_flexible_wgt?($record->current_total_weight >= $record->max_weight || $record->current_chances_used >= $record->max_chance ?'N':'Y'):($record->paid_installments <  $record->total_installments ?($record->allow_unpaid == 1  && $record->totalunpaid >0 && ($record->currentmonthpaycount-1) < $record->allow_unpaid_months ?'Y':($record->allow_advance == 1 && $record->advance_months > 0 && ($record->currentmonthpaycount -1) < $record->advance_months ?'Y':($record->currentmonthpaycount == 0 ? 'Y': 'N'))):'N')):'N'):'N'):'N');
				}	
				
				$rate_field = NULL;
    			$metalRate  = NULL;
    			$sql = $this->db->query("SELECT rate_field,market_rate_field FROM `ret_metal_purity_rate` where id_metal=".$record->id_metal." and id_purity=1");
    			//print_r($this->db->last_query());exit;
    			if($sql->num_rows() == 1){
        			$metalfields = $sql->row_array();
        			$rate_field  = $metalfields['rate_field'];
        			$metalRate   = ( $rate_field == null ? null : $metal_rates[$rate_field] );
    			}
				
				$schemeAcc = array( 
				             
				            'rate_field'                => $rate_field,
				            'id_branch'                 => $record->id_branch,
				            'cost_center'               => $record->cost_center,
				            'name'                      => $record->name,
				            'months_from_startdate'     => $record->months_from_startdate,
				            'maturity_type'             => $record->maturity_type,
				            'missed_ins'                => $record->missed_ins,
				            'maturity_installment'      => $record->maturity_installment,
					     //	'metal_rate'                => $metal_rates['goldrate_22ct'],
					        'metal_rate'                => $metalRate,
					     	'allow_advance'             => $record->allow_advance,
					     	'advance_months'            => $record->advance_months,
					     	'currentmonth_adv_paycount' => $record->currentmonth_adv_paycount,
					     	'min_amount'                => round(($record->scheme_type==3 && $record->min_amount!=0 && $record->min_amount!='' ? (($record->firstPayamt_maxpayable==1 ||$record->firstPayamt_as_payamt==1 ) && $record->firstPayment_amt > 0 ? $record->firstPayment_amt : ($record->max_amount - str_replace(',', '',$record->current_total_amount) > $record->min_amount ? $record->min_amount : $record->max_amount - str_replace(',', '',$record->current_total_amount)) ):($record->min_weight!=0 && $record->min_weight!=''? (($record->firstpayment_wgt > 0 ? $record->firstpayment_wgt : $record->min_weight)*$metal_rates['goldrate_22ct']) : $record->min_amount))),
						    'max_amount'                => round(($record->scheme_type==3 && $record->max_amount!=0 && $record->max_amount!='' ? (($record->firstPayamt_maxpayable==1 ||$record->firstPayamt_as_payamt==1 )&&($record->paid_installments>0 ||$record->get_amt_in_schjoin==1) ?  $record->firstPayment_amt:($record->max_amount - str_replace(',', '',$record->current_total_amount))): ($record->scheme_type==3 && $record->max_weight!=0 && $record->max_weight!=''? (($record->firstpayment_wgt > 0 ? $record->firstpayment_wgt : ($record->max_weight - $record->current_total_weight) )*$metal_rates['goldrate_22ct']) : $record->max_amount))),
							'firstPayamt_maxpayable'    => $record->firstPayamt_maxpayable,
							'firstPayamt_as_payamt'     => $record->firstPayamt_as_payamt,
							'firstPayment_amt'          => $record->firstPayment_amt,
							'firstpayment_wgt'          => $record->firstpayment_wgt,
                            'flx_denomintion'           => $record->flx_denomintion,
							'is_registered'             => $record->is_registered,
							'min_amt_chance'            => $record->min_amt_chance,
							'max_amt_chance'            => $record->max_amt_chance,
							'gst'                       => $record->gst,
							'one_time_premium'          => $record->one_time_premium,
							'flexible_sch_type'         => $record->flexible_sch_type,
							'get_amt_in_schjoin'        => $record->get_amt_in_schjoin,
							'scheme_overall_amount'     => $record->scheme_overall_amount,
							'id_customer'               => $record->id_customer,
							'allow_unpaid'              => $record->allow_unpaid,
							'gst_type'                  => $record->gst_type,
							'currentmonth_adv_paycount' => $record->currentmonth_adv_paycount,
							'currentmonthpaycount' 		=> $record->currentmonthpaycount,
							'mobile'				    => $record->mobile,
							'current_date' 		        => $dates,
							'totalunpaid' 				=> $record->totalunpaid,
							'id_scheme_account' 		=> $record->id_scheme_account,
							'start_date' 				=> $record->start_date,
							'chit_number' 				=> $record->chit_number,
							'account_name' 				=> $record->account_name,
							'discount_type' 			=> $record->discount_type,
						    'discount_installment' 		=> $record->discount_installment,
						    'firstPayDisc_value' 		=> $record->firstPayDisc_value,
						    'discount' 		            => $record->discount,
						    'disable_pay_reason' 		=> $record->disable_pay_reason,
						    'disable_payment' 			=> $record->disable_payment,
						    'disable_sch_payment' 		=> $record->disable_sch_payment,
							'payable'                   =>	( ($record->scheme_type==3 && ($record->paid_installments>0||$record->get_amt_in_schjoin==1) && $record->firstPayamt_as_payamt==1 || $record->is_registered==1) ?$record->firstPayment_amt :$record->payable),
							'scheme_name' 				=> $record->scheme_name,
							'code' 						=> $record->code,
							'scheme_type' 				=> $record->scheme_type,
							'currency_symbol'			=> $record->currency_symbol,
							'min_weight' 				=> $record->min_weight,
							'max_weight' 				=> $record->max_weight,
							'wgt_convert' 				=> $record->wgt_convert,
							'total_installments' 		=> $record->total_installments,
							'paid_installments' 		=> $record->paid_installments,
							'total_paid_amount' 		=> $record->total_paid_amount,
							'total_paid_weight' 		=> $record->total_paid_weight,
							'current_total_amount' 		=> $record->current_total_amount,
							'current_paid_installments' => $record->current_paid_installments,
							'current_chances_used' 		=> $record->current_chances_used,
							'current_chances_use' 		=> $record->current_chances_use,
							'current_total_weight' 		=> $record->current_total_weight,
							'last_paid_duration' 		=> $record->last_paid_duration,
							'last_paid_date' 			=> $record->last_paid_date,
							'is_pan_required' 			=> $record->is_pan_required,
							'last_transaction'     		=> $this->getLastTransaction($record->id_scheme_account),
							'isPaymentExist' 			=> $this->isPaymentExist($record->id_scheme_account),
							'previous_amount_eligible'  => $record->previous_amount_eligible,
							'cur_month_pdc'             => $record->cur_month_pdc,
							'is_flexible_wgt'           => $record->is_flexible_wgt, 
							'max_chance'                => $record->max_chance,
							'ref_benifitadd_ins'        => $record->ref_benifitadd_ins,
							'ref_benifitadd_ins_type'   => $record->ref_benifitadd_ins_type,
							'referal_code'              => $record->referal_code, 
							'allow_pay'				    => $allow_pay,
							'allowed_dues'  			=> ($record->is_flexible_wgt == 1 ? 1:$allowed_due),
							//'allowed_dues'              => ($record->maturity_date==NULL ? $allowed_due : $allow_due) ,
							'due_type' 		            => ($record->is_flexible_wgt == 1 ? 'ND':$due_type),
							'allow_preclose' 	        => ($record->currentmonthpaycount == 1 ? ($record->allow_preclose ==1?($record->total_installments - $record->paid_installments == $record->preclose_months ? 1 : 0):0):0),
							'pdc_payments'              => ($record->cur_month_pdc > 0 ? $this->get_postdated_payment($record->id_scheme_account) : 0) ,
							'total_pdc'                 => ( isset($pdc_det) && $pdc_det !='' ? $pdc_det : 0) ,
							'weights'                   => ( $record->scheme_type==1 ||  $record->flexible_sch_type==4? $this->getWeights() :''),
							'preclose'                  => ($record->allow_preclose ==1?$record->preclose_months:0),
							'preclose_benefits'         => ($record->allow_preclose ==1?$record->preclose_benefits:0)
						);
				}	 
			return	$schemeAcc;
	}
		
   function get_paymentContent_old($id_scheme_account)
	{  
	   $schemeAcc = array();
	   
		$sql="Select
				   	sa.id_scheme_account,s.gst,s.gst_type,
					s.id_scheme,
					c.id_customer,
					CONCAT(s.code,'-',IFNULL(sa.scheme_acc_number,'Not Allocated')) as chit_number,
					IFNULL(sa.account_name,if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname))) as account_name,
					s.scheme_name,
					s.scheme_type,
					s.code,
					IFNULL(s.min_chance,0) as min_chance,
					IFNULL(s.max_chance,0) as max_chance,
					Format(IFNULL(s.max_weight,0),3) as max_weight,
					Format(IFNULL(s.min_weight,0),3) as min_weight,
					Date_Format(sa.start_date,'%d-%m-%Y') as start_date,
					IF(s.scheme_type=0,s.amount,IF(s.scheme_type=1,s.max_weight,s.amount)) as payable,
					s.total_installments,
IFNULL(IF(sa.is_opening=1,IFNULL(sa.paid_installments,0)+ IFNULL(if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues)),0), if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues))) ,0)
  as paid_installments,
  
  
IFNULL(IF(sa.is_opening=1,IFNULL(balance_amount,0)+IFNULL(SUM(p.payment_amount * p.no_of_dues),0),IFNULL(SUM(p.payment_amount * p.no_of_dues),0)) ,0)
  as total_paid_amount,
  
IFNULL(IF(sa.is_opening=1,IFNULL(balance_weight,0)+IFNULL(SUM(p.metal_weight),0),IFNULL(SUM(p.metal_weight),0)),0.000)
 as total_paid_weight,
 
  if((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) > s.total_installments, 
(s.total_installments - COUNT(payment_amount)), 
ifnull((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) - SUM(p.no_of_dues),if((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) > s.total_installments,s.total_installments,(PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m')))))) 
  as totalunpaid_1, 
  
  if((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) > s.total_installments,   (s.total_installments - COUNT(payment_amount)),ifnull(((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m')))+1) - IF(sa.is_opening=1,IFNULL(sa.paid_installments,0)+ IFNULL(if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues)),0), if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues))),if((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) > s.total_installments,s.total_installments,(PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m')))))) as totalunpaid,  
  
  IFNULL(if(Date_Format(max(p.date_add),'%Y%m') = Date_Format(curdate(),'%Y%m'), SUM(p.no_of_dues),IF(sa.is_opening=1, if(Date_Format(sa.last_paid_date,'%Y%m') = Date_Format(curdate(),'%Y%m'), 1,0),0)),0) as currentmonthpaycount,
  
  (select SUM(pay.no_of_dues) from payment pay where pay.id_scheme_account= sa.id_scheme_account and pay.due_type='AD' and (pay.payment_status=1 or pay.payment_status=2)) as currentmonth_adv_paycount,
  
  (select SUM(pay.no_of_dues) from payment pay where pay.id_scheme_account= sa.id_scheme_account and pay.due_type='PD' and (pay.payment_status=1 or pay.payment_status=2)) as currentmonth_pend_paycount,
 
  
IF(s.scheme_type =1 and s.max_weight !=s.min_weight,true,false) as is_flexible_wgt,p.payment_status,
					Format(IFNULL(cp.total_amount,0),2) as  current_total_amount,
					Format(IFNULL(cp.total_weight,0) + IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),(sa.last_paid_weight),0) ,3) as  current_total_weight,
					IFNULL(cp.paid_installment,0)       as  current_paid_installments,
					IFNULL(cp.chances,0) + IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),(sa.last_paid_chances),0) as  current_chances_used,
					s.is_pan_required,
					IF(sa.is_opening = 1 and s.scheme_type = 0,
					IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),false,true),
					true) AS previous_amount_eligible,
					count(pp.id_scheme_account) as cur_month_pdc,
					IFNULL(Date_Format(max(p.date_add),'%d-%m-%Y'),IFNULL(IF(sa.is_opening=1,Date_Format(sa.last_paid_date,'%d-%m-%Y'),'')  ,0))                 as last_paid_date,
					IFNULL(PERIOD_DIFF(Date_Format(curdate(),'%Y%m'),Date_Format(max(p.date_add),'%Y%m')),IF(sa.is_opening=1,PERIOD_DIFF(Date_Format(curdate(),'%Y%m'),Date_Format(sa.last_paid_date,'%Y%m')),0)) as last_paid_duration,
				IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),1,0) as  previous_paid,	
				sa.disable_payment,s.disable_sch_payment,s.stop_payment_installment,
					
				s.allow_unpaid,
				if(s.allow_unpaid=1,s.unpaid_months,0) as allow_unpaid_months,
				s.allow_advance,sa.id_branch,
				if(s.allow_advance=1,s.advance_months,0) as advance_months,
					if(s.allow_unpaid=1,s.unpaid_weight_limit,0) as unpaid_weight_limit,
					s.allow_advance,
					if(s.allow_advance=1,s.advance_weight_limit,0) as advance_weight_limit,
					s.allow_preclose,
					if(s.allow_preclose=1,s.preclose_months,0) as preclose_months,
					if(s.allow_preclose=1,s.preclose_benefits,0) as preclose_benefits,cs.currency_symbol
				From scheme_account sa
				Left Join scheme s On (sa.id_scheme=s.id_scheme)
				Left Join payment p On (sa.id_scheme_account=p.id_scheme_account and (p.payment_status=2 or p.payment_status=1))
				Left Join customer c On (sa.id_customer=c.id_customer and c.active=1)
				Left Join
					(	Select
						  sa.id_scheme_account,
						  COUNT(Distinct Date_Format(p.date_add,'%Y%m')) as paid_installment,
						  COUNT(Date_Format(p.date_add,'%Y%m')) as chances,
						  SUM(p.payment_amount) as total_amount,
						  SUM(p.metal_weight) as total_weight
						From payment p
						Left Join scheme_account sa on(p.id_scheme_account=sa.id_scheme_account and sa.active=1 and sa.is_closed=0)
						Where  (p.payment_status=2 or p.payment_status=1) and  Date_Format(Current_Date(),'%Y%m')=Date_Format(p.date_add,'%Y%m')
						Group By sa.id_scheme_account
					) cp On (sa.id_scheme_account=cp.id_scheme_account)
				 Left Join postdate_payment pp On (sa.id_scheme_account=pp.id_scheme_account and (pp.payment_status=2 or pp.payment_status=7) and (Date_Format(pp.date_payment,'%Y%m')=Date_Format(curdate(),'%Y%m')))	
				 join chit_settings cs
				Where sa.active=1 and sa.is_closed = 0 and sa.id_scheme_account='$id_scheme_account'
				Group By sa.id_scheme_account";
		
		$records = $this->db->query($sql);
//if(Date_Format(max(p.date_add),'%Y%m') = Date_Format(curdate(),'%Y%m'), SUM(p.no_of_dues),0)  as currentmonthpaycount,	
 	
		if($records->num_rows()>0)
		{
				$record = $records->row();
				
				$allowed_due = 0;
				$due_type = '';
				
				if($record->paid_installments > 0 || $record->totalunpaid >0){
					if($record->currentmonthpaycount == 0){  // current month not paid (allowed pending due + current due)
						if($record->allow_unpaid == 1){
							if($record->allow_unpaid_months > 0 && ($record->total_installments - $record->paid_installments) >=  $record->allow_unpaid_months && $record->totalunpaid >0){
								if(($record->total_installments - $record->paid_installments) ==  $record->allow_unpaid_months){
									$allowed_due = ($record->totalunpaid < $record->allow_unpaid_months ? $record->totalunpaid : $record->allow_unpaid_months) ;  
								    $due_type = 'PD'; //  pending
								}
								else{
									$allowed_due =  ($record->totalunpaid < $record->allow_unpaid_months ? $record->totalunpaid : $record->allow_unpaid_months)+1 ;  
								    $due_type = 'PN'; // normal and pending
								}
								 
							}
							else{
							     $allowed_due =  1;
							     $due_type = 'ND'; // normal due
							}
						}
						else{
							 $allowed_due =  1;
							 $due_type = 'ND'; // normal due
						}
					}
					else{ 	//current month paid
					
						if($record->allow_unpaid == 1 && $record->allow_unpaid_months >0 && $record->totalunpaid >0 && ($record->currentmonthpaycount-1) < $record->allow_unpaid_months){  
							// can pay previous pending dues if attempts available 
							if($record->totalunpaid > $record->allow_unpaid_months){
								 $allowed_due =  $record->allow_unpaid_months ;
								 $due_type = 'PD'; // pending due
							}
							else{ 
								 $allowed_due =  $record->totalunpaid;
								 $due_type = 'PD'; // pending due
							}
						}
						else{  // check allow advance
							if($record->allow_advance == 1 && $record->advance_months > 0 && ($record->currentmonth_adv_paycount) < $record->advance_months){
								if(($record->advance_months + $record->paid_installments) <= $record->total_installments){
									 $allowed_due =  ($record->advance_months - ($record->currentmonth_adv_paycount));
									 $due_type = 'AD'; // advance due
								}
								else{
									 $allowed_due =  ($record->total_installments - $record->paid_installments);
									 $due_type = 'AD'; // advance due
								}
							}
							else{ // have to check
								 $allowed_due =  0;
								 $due_type = ''; // normal due
							}
						
						}
					}
				}
				else{  // check allow advance and add due with currect month (allowed advance due + current due)
					if($record->allow_advance ==1){ // check allow advance
						if($record->advance_months > 0 && $record->advance_months <= ($record->total_installments - $record->paid_installments)){
							if(($record->total_installments - $record->paid_installments) ==  $record->advance_months){
									 $allowed_due =  $record->advance_months;
									 $due_type = 'AN'; // advance and normal
								}
								else{
									$allowed_due =  $record->advance_months+1 ;  
								     $due_type = 'AN'; // advance and normal
								}
							
						}
						else{
							 $allowed_due =  1;
							 $due_type = 'ND'; // normal due
						}
					}
					else{
						 $allowed_due =  1;
						 $due_type = 'ND'; // normal due
					}
				
				}
				
				
				$pdc_det = $this->get_pending_pdc($record->id_scheme_account);
				$schemeAcc = array(
									'gst' => $record->gst,
									'gst_type' => $record->gst_type,
									
									'id_branch' => $record->id_branch,
									'currentmonth_adv_paycount' => $record->currentmonth_adv_paycount,
									'currentmonthpaycount' 		=> $record->currentmonthpaycount,
									'totalunpaid' 				=> $record->totalunpaid,
									'id_scheme_account' 		=> $record->id_scheme_account,
									'start_date' 				=> $record->start_date,
									'chit_number' 				=> $record->chit_number,
									'account_name' 				=> $record->account_name,
									'payable' 					=> $record->payable,
									'scheme_name' 				=> $record->scheme_name,
									'code' 						=> $record->code,
									'scheme_type' 				=> $record->scheme_type,
									'currency_symbol'			=> $record->currency_symbol,
									'min_weight' 				=> $record->min_weight,
									'max_weight' 				=> $record->max_weight,
									'total_installments' 		=> $record->total_installments,
									'paid_installments' 		=> $record->paid_installments,
									'total_paid_amount' 		=> $record->total_paid_amount,
									'total_paid_weight' 		=> $record->total_paid_weight,
									'current_total_amount' 		=> $record->current_total_amount,
									'current_paid_installments' => $record->current_paid_installments,
									'current_chances_used' 		=> $record->current_chances_used,
									'current_total_weight' 		=> $record->current_total_weight,
									'last_paid_duration' 		=> $record->last_paid_duration,
									'last_paid_date' 			=> $record->last_paid_date,
									'is_pan_required' 			=> $record->is_pan_required,
									'last_transaction'     		=> $this->getLastTransaction($record->id_scheme_account),
									'isPaymentExist' 			=> $this->isPaymentExist($record->id_scheme_account),
									'previous_amount_eligible'  => $record->previous_amount_eligible,
									'cur_month_pdc'             => $record->cur_month_pdc,
									'is_flexible_wgt'           => $record->is_flexible_wgt,
									'allow_pay'  => ($record->disable_payment != 1 && ($record->payment_status !=2) ? ($record->cur_month_pdc < 1 ? ($record->paid_installments <= $record->total_installments ?($record->is_flexible_wgt?($record->current_total_weight >= $record->max_weight || $record->current_chances_used >= $record->max_chance ?'N':'Y'):($record->paid_installments <  $record->total_installments ?($record->allow_unpaid == 1  && $record->totalunpaid >0 && ($record->currentmonthpaycount-1) < $record->allow_unpaid_months ?'Y':($record->allow_advance == 1 && $record->advance_months > 0 && ($record->currentmonthpaycount -1) < $record->advance_months ?'Y':($record->currentmonthpaycount == 0 ? 'Y': 'N'))):'N')):'N'):'N'):'N'),
									 'allowed_dues'  			=>($record->is_flexible_wgt == 1 ? 1:$allowed_due),
									 'due_type' 		=> ($record->is_flexible_wgt == 1 ? 'ND':$due_type),
									 'allow_preclose' 	=> ($record->currentmonthpaycount == 1 ? ($record->allow_preclose ==1?($record->total_installments - $record->paid_installments == $record->preclose_months ? 1 : 0):0):0),
									 'pdc_payments'  =>($record->cur_month_pdc > 0 ? $this->get_postdated_payment($record->id_scheme_account) : 0) ,
									 'total_pdc'  =>( isset($pdc_det) && $pdc_det !='' ? $pdc_det : 0) ,
									 'weights'  => ( $record->scheme_type=='1'? $this->getWeights() :''),
									 'preclose' => ($record->allow_preclose ==1?$record->preclose_months:0),
									 'preclose_benefits' =>($record->allow_preclose ==1?$record->preclose_benefits:0)
									);		
									
									
									
				}	
				
				
			return	$schemeAcc;
		}	
		
	function get_pending_pdc($id_scheme_account)
	{
		$sql="Select
				    id_scheme_account,
				    Count(id_post_payment) as total_pdc,
				    Max(date_payment) as last_paid_date
				From postdate_payment
				Where (payment_status=2 or payment_status =7) and id_scheme_account='".$id_scheme_account."'
				Group By id_scheme_account";
		return $this->db->query($sql)->row('total_pdc');		
	}				
		
	function get_allpostdated_payment($id_scheme_account)
	{
		$sql="Select
				   pp.id_scheme_account,
				   date(pp.date_payment) as date_payment,
				   pp.pay_mode,
				   pp.cheque_no,
				   pp.payee_acc_no,
				   b.bank_name,
				   b.short_code,
				   pp.payee_branch,
				   pp.payee_ifsc,
				   pp.amount
			From postdate_payment pp
			Left Join bank b On (pp.payee_bank=b.id_bank)
			Where  (pp.payment_status = 2 or pp.payment_status=7)
			       And pp.id_scheme_account='".$id_scheme_account."'";
			 
		return $this->db->query($sql)->row_array();	       
	}	
	
	function get_postdated_payment($id_scheme_account)
	{
		$sql="Select
				   pp.id_scheme_account,
				   date(pp.date_payment) as date_payment,
				   pp.pay_mode,
				   pp.cheque_no,
				   pp.payee_acc_no,
				   b.bank_name,
				   b.short_code,
				   pp.payee_branch,
				   pp.payee_ifsc,
				   pp.amount
			From postdate_payment pp
			Left Join bank b On (pp.payee_bank=b.id_bank)
			Where (Date_Format(Current_Date(),'%Y%m')=Date_Format(pp.date_payment,'%Y%m'))
			       And (pp.payment_status = 2 or pp.payment_status=7)
			       And pp.id_scheme_account='".$id_scheme_account."'";
			 
		return $this->db->query($sql)->row_array();	       
	}
    function getWeights()
    {
		$sql="Select * from weight";
		return $this->db->query($sql)->result_array();	    
	}
  
    //get last paid entry
	function getLastTransaction($id_scheme_account)
	{
		$sql="Select * from payment			
			  Where payment_status=1
			  And id_scheme_account='$id_scheme_account'";
		return $this->db->query($sql)->row_array();	         
	}
	
	
	function isPaymentExist($id_scheme_account)
	{
		$sql = "Select
					  sa.id_scheme_account,c.mobile
				From payment p
				Left Join scheme_account sa On (p.id_scheme_account = sa.id_scheme_account)
				Left Join customer c on (sa.id_customer = c.id_customer)
				Where (p.payment_status = 1) And sa.id_scheme_account= '".$id_scheme_account."' ";
		
			$records = $this->db->query($sql);
		
		if($records->num_rows()>0)
		{
			return TRUE;
		}
	}
	function get_payment_details()
	{
		$sql="Select
				sa.id_scheme_account,if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,c.mobile,
				s.code,if(s.scheme_type=0,'Amount',if(s.scheme_type=1,'Weight','Amount to Weight')) as scheme_type,s.amount,s.total_installments,
				sa.ref_no,IFNULL(sa.scheme_acc_number,'Not Allocated') as scheme_acc_number,sa.start_date,
				if(sa.balance_amount is null,0,sa.balance_amount) as balance_amount,
				(month(now()) - month(sa.start_date)) as duration,
				if(sa.paid_installments is null,0,sa.paid_installments) as paid_installments,
				if(count(distinct month(p.date_payment)) is null,0,count(distinct month(p.date_payment))) as paid,
				(if(sa.paid_installments is null,0,sa.paid_installments) + if(count(distinct month(p.date_payment)) is null,0,count(distinct month(p.date_payment)))) as total_paid,
				(if(s.total_installments is null,0,s.total_installments) -     (if(sa.paid_installments is null,0,sa.paid_installments) + if(count(distinct month(p.date_payment)) is null,0,count(distinct month(p.date_payment))))
				 ) as pending_installments,
				 (if(max(distinct month(p.date_payment)) is null,0,max(distinct month(p.date_payment)))) as last_paid_month,
				(if(if(max(distinct month(p.date_payment)) is null,0,max(distinct month(p.date_payment))) < month(now()),'Un Paid','Paid'))  as status
			From ".self::ACC_TABLE." sa
			Left Join ".self::PAY_TABLE." p On (sa.id_scheme_account=p.id_scheme_account)
			Left Join ".self::CUS_TABLE." c On (sa.id_customer=c.id_customer)
			Left Join ".self::SCH_TABLE." s On (sa.id_scheme=s.id_scheme)
			Where sa.active = 1 and is_closed = 0
			Group By sa.id_scheme_account
			Having pending_installments > 0 and total_paid <= s.total_installments";
	
	  $payments=$this->db->query($sql);
	
	  return $payments->result_array();
	}	
	
	function get_payment_dues_details()
	{
		$sql="Select
					sa.id_scheme_account,if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,c.mobile,
					s.code,if(s.scheme_type=0,'Amount',if(s.scheme_type=1,'Weight','Amount to Weight')) as scheme_type,s.amount,s.total_installments,
					sa.ref_no,IFNULL(sa.scheme_acc_number,'Not Allocated') as scheme_acc_number,sa.start_date,
					if(sa.balance_amount is null,0,sa.balance_amount) as balance_amount,
					(month(now()) - month(sa.start_date)) as duration,
					if(sa.paid_installments is null,0,sa.paid_installments) as paid_installments,
					if(count(distinct month(p.date_payment)) is null,0,count(distinct month(p.date_payment))) as paid,
					(if(sa.paid_installments is null,0,sa.paid_installments) + if(count(distinct month(p.date_payment)) is null,0,count(distinct month(p.date_payment)))) as total_paid,
					(if(s.total_installments is null,0,s.total_installments) -     (if(sa.paid_installments is null,0,sa.paid_installments) + if(count(distinct month(p.date_payment)) is null,0,count(distinct month(p.date_payment))))
					 ) as pending_installments,
					 (if(max(distinct month(p.date_payment)) is null,0,max(distinct month(p.date_payment)))) as last_paid_month,
					(if(if(max(distinct month(p.date_payment)) is null,0,max(distinct month(p.date_payment))) < month(now()),'Un Paid','Paid'))  as status
				From ".self::ACC_TABLE." sa
				Left Join ".self::PAY_TABLE." p On (sa.id_scheme_account=p.id_scheme_account)
				Left Join ".self::CUS_TABLE." c On (sa.id_customer=c.id_customer)
				Left Join ".self::SCH_TABLE." s On (sa.id_scheme=s.id_scheme)
				Where sa.active = 1 and is_closed = 0
				Group By sa.id_scheme_account
				Having pending_installments > 0 and total_paid <= s.total_installments and status!='Paid'";
	
	  $payments=$this->db->query($sql);
	
	  return $payments->result_array();
	}
	function get_payment_employee()
	{
		$sql="Select   
			sa.id_scheme_account,p.id_employee,concat(emp.firstname,' ',emp.lastname)as  employee_name,
			if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,c.mobile,
            
			IFNULL(sa.scheme_acc_number,'Not Allocated') as scheme_acc_number,  sa.ref_no,sa.scheme_acc_number,
            
		   if(s.scheme_type=0,'Amount',if(s.scheme_type=1,'Weight','Amount to Weight')) as scheme_type,
           
			  p.payment_amount,p.payment_status
          
               
			From  payment p
			Left Join scheme_account sa on (p.id_scheme_account=sa.id_scheme_account)
			Left Join  scheme s on (sa.id_scheme=s.id_scheme)
			Left Join customer c on (sa.id_customer=c.id_customer)
            
            
            
            Left Join employee emp on (p.id_employee=emp.id_employee)
			LEFT Join payment_status_message psm ON (p.payment_status=psm.id_status_msg)
			Where p.payment_status =1";
			$payments = $this->db->query($sql)->result_array();
			// $sql="select  concat(emp.firstname,' ',emp.lastname)as  employee_name,emp.id_employee from employee emp";
			// $employee = $this->db->query($sql)->result_array();
	
	  return $payments;
	}
	function get_payment_list($from_date,$to_date,$id_branch,$id_emp)
	{
	    
	    $date_type=$this->input->post('date_type');
	    	$branch_settings=$this->session->userdata('branchWiseLogin');
			$log_branch=$this->session->userdata('id_branch');
			$uid=$this->session->userdata('uid');	 
			
	    //print_r($date_type);exit;
		$sql="Select   
			sa.id_scheme_account,p.id_employee,concat(emp.firstname,' ',emp.lastname)as  employee_name,
			if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,c.mobile,
            
			IFNULL(sa.scheme_acc_number,'Not Allocated') as scheme_acc_number,  sa.ref_no,sa.scheme_acc_number,
            
		   if(s.scheme_type=0,'Amount',if(s.scheme_type=1,'Weight','Amount to Weight')) as scheme_type,
           
			  p.payment_amount,p.payment_status
          
               
			From  payment p
			Left Join scheme_account sa on (p.id_scheme_account=sa.id_scheme_account)
			Left Join  scheme s on (sa.id_scheme=s.id_scheme)
			Left Join customer c on (sa.id_customer=c.id_customer)
            left  join branch b on (b.id_branch=p.id_branch)
            Left Join employee emp on (p.id_employee=emp.id_employee)
			LEFT Join payment_status_message psm ON (p.payment_status=psm.id_status_msg)
			Where p.payment_status =1 ".($from_date!=''&&$to_date!='' ? " and (date(".($date_type!='' ? ($date_type==2 ?"p.custom_entry_date":"p.date_payment") : "p.date_payment").") BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')" :'')."  ".($id_emp!=''?"and p.id_employee=".$id_emp."" :'')." 
			".($uid!=1 ? ($branch_settings==1 ? ($id_branch!=0 && $id_branch!='' ? "and p.id_branch=".$id_branch."" : " and (p.id_branch=".$log_branch." or b.show_to_all=1 or b.show_to_all=3)"):'') : ($id_branch!=0 && $id_branch!=''? "and p.id_branch=".$id_branch."" :''))." ";
		   // print_r($sql);exit;
			$payments = $this->db->query($sql)->result_array();
			return $payments;
	}
	function get_employee_name($id_branch)
	{
		$sql="select  concat(emp.firstname,' ',emp.lastname)as  employee_name,emp.id_employee from employee emp".($id_branch!=0 ?" where emp.id_branch=".$id_branch."" :'')."";
	//	print_r($sql);exit;
		return $this->db->query($sql)->result_array();	
	
	}
	
	function get_payment_report($branch)
	{
	 
			$branchWiseLogin=$this->session->userdata('branchWiseLogin');
			$id_branch=$this->session->userdata('id_branch');
			$uid=$this->session->userdata('uid');	 
	  $sql="Select
				  sa.id_scheme_account,
				  if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,
				  c.mobile,
				  IFNULL(sa.scheme_acc_number,'Not Allocated') as scheme_acc_number,
				  sa.ref_no,
				  sa.scheme_acc_number,
				  if(s.scheme_type=0,'Amount',if(s.scheme_type=1,'Weight','Amount to Weight')) as scheme_type,
				  if(s.scheme_type=0,s.amount,if(s.scheme_type=1,'-',s.amount)) as amount,
     			  if(s.scheme_type=1,s.max_weight,'-') as Max_weight,
				  s.code,
				  sa.start_date,
				  s.total_installments,
				  
					IFNULL(IF(sa.is_opening=1,IFNULL(sa.paid_installments,0)+ IFNULL(if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues)),0), if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues))) ,0)
  as total_paid,
(s.total_installments -  IFNULL(IF(sa.is_opening=1,IFNULL(sa.paid_installments,0)+ IFNULL(if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues)),0), if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues))) ,0))
		as remaining,
				  (select count(date_payment) from payment where month(date_payment)=month(curdate()) and id_scheme_account=sa.id_scheme_account) as Payment_attempts,
				  (select count(date_payment) from payment where month(date_payment)=month(curdate()) and payment_status=1 and id_scheme_account=sa.id_scheme_account) as payment_pending,
				  round(if(sa.balance_amount is null,'0.00',sa.balance_amount)+  (select if(sum(p.payment_amount) is null,0.00,sum(p.payment_amount)) from payment p where payment_status=1 and id_scheme_account=sa.id_scheme_account  ),2) as balance_amount,
				  round((if(sa.balance_weight is null,0.00,sa.balance_weight) + (select if(sum(p.metal_weight) is null,0.00,sum(p.metal_weight))from payment p where payment_status=1 and id_scheme_account=sa.id_scheme_account  )),3) as balance_weight,
				  MONTHNAME(STR_TO_DATE(if(max(distinct month(p.date_payment)) is null,month(sa.last_paid_date),max(distinct month(p.date_payment))), '%m')) as last_paid_month,
				  if(max(distinct month(p.date_payment)) is null,if(max(distinct year(sa.last_paid_date)) < year(now()) , 'Unpaid',if(max(distinct month(sa.last_paid_date)) < month(now()),'Not Paid','Paid')) , if(max(distinct year(p.date_payment)) < year(now()) , 'Unpaid',if(max(distinct month(p.date_payment)) < month(now()),'Not Paid','Paid'))) as current_due,
				psm.payment_status as pay_status
			From ".self::PAY_TABLE." p
			Left Join ".self::ACC_TABLE." sa on (p.id_scheme_account=sa.id_scheme_account)
			Left Join ".self::BRANCH." b on (b.id_branch=sa.id_branch)
			Left Join ".self::SCH_TABLE." s on (sa.id_scheme=s.id_scheme)
			Left Join ".self::CUS_TABLE." c on (sa.id_customer=c.id_customer)
			LEFT Join payment_status_message psm ON (p.payment_status=psm.id_status_msg)
			Where p.payment_status =1 and s.one_time_premium=0 ".($branch!='' ? " and sa.id_branch=".$branch :'')." ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( sa.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')."
			Group By sa.id_scheme_account,sa.id_scheme,s.scheme_type
			Having total_paid <= s.total_installments";
	  $payments=$this->db->query($sql);
	
	  return $payments->result_array();
	}
	
	function get_payment_detail($id)
	{
		$sql="Select
			  IFNULL(sa.scheme_acc_number,'Not Allocated') as scheme_acc_number,concat(c.firstname,' ',c.lastname) as name,sa.account_name,sa.ref_no,c.mobile,
			  s.scheme_name,s.code,if(s.scheme_type=0,'Amount',if(s.scheme_type=1,'Weight','Amount to Weight')) as scheme_type,sa.start_date,s.amount,s.total_installments,
			  count(distinct month(p.date_payment)) as total_paid, if(max(month(p.date_payment))<Now(),'Pay','Paid') as  status
		From  ".self::ACC_TABLE." sa
		Left Join ".self::CUS_TABLE." c on (sa.id_customer=c.id_customer)
		Left Join ".self::SCH_TABLE." s on (sa.id_scheme=s.id_scheme)
		Left Join ".self::PAY_TABLE." p on (sa.id_scheme_account = p.id_scheme_account)
		Group By sa.id_scheme_account
		Having total_paid<=s.total_installments and status!='Paid'
		Where id_scheme=".$id;
	  $payments=$this->db->query($sql);
	  return $payments->row_array();
	}
	
	function get_account_payment($id_scheme_account)
	{  
		$sql = "SELECT
				  p.id_payment,p.gst,p.gst_type,
				  DATE_FORMAT(p.date_payment,'%d-%m-%Y') as date_payment,
				  p.id_scheme_account,s.scheme_type,s.flexible_sch_type,
				  p.metal_rate,
				  p.payment_amount,
				  p.metal_weight,p.discountAmt,
				  p.payment_mode,psm.payment_status as payment_status
				FROM ".self::PAY_TABLE." p
				LEFT Join payment_status_message psm ON (p.payment_status=psm.id_status_msg)
				 Left Join scheme_account sa on (sa.id_scheme_account=p.id_scheme_account)
				  Left Join scheme s on(s.id_scheme=sa.id_scheme)
				
				 WHERE p.id_scheme_account = '$id_scheme_account' AND (p.payment_status=1 or p.payment_status=2  )";
				// print_r($sql);exit;
		$payments = $this->db->query($sql);
	    return $payments->result_array();
	}
	function get_payment_modewise()
	{  
				$sql = "Select pm.id_mode,pm.mode_name,pm.short_code as mode,
						 Count(Case When p.payment_status=1 Then 1 End) as success,
						 Count(Case When p.payment_status=2 Then 1 End) as awaiting,
						 Count(Case When p.payment_status=3 Then 1 End) as failed,
						 Count(Case When p.payment_status=4 Then 1 End) as cancelled,
						 Count(Case When p.payment_status=5 Then 1 End) as returned,
						 Count(Case When p.payment_status=6 Then 1 End) as refund,
						 Count(Case When p.payment_status=7 Then 1 End) as pending
						FROM  ".self::MOD_TABLE." pm 
						Left Join ".self::PAY_TABLE." p on(p.payment_mode=pm.short_code)
						
						 GROUP BY pm.mode_name";
		$payments = $this->db->query($sql);
	    return $payments->result_array();
	}
	
	
	
	
	function ajax_get_payments($status="",$from_date="",$to_date="",$header="")
   {   			
        $sql="select
		  p.id_payment,sa.ref_no,s.code,if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,c.mobile,DATE_FORMAT(p.date_payment, '%d-%m-%Y') as date,p.payment_amount,p.payment_mode,p.bank_acc_no,
		  p.bank_name,p.bank_branch,p.bank_IFSC,id_transaction,if(p.payment_status=1,'Approved','Pending') as payment_status
		from ".self::PAY_TABLE." p
		left join ".self::ACC_TABLE." sa on (p.id_scheme_account=sa.id_scheme_account)
		left join ".self::CUS_TABLE." c on (sa.id_customer=c.id_customer)
		left join ".self::SCH_TABLE." s on (sa.id_scheme=s.id_scheme) ";
		
		switch($status)
		{    //status ALL
			case 0:
			        //only from date  
			        if($from_date!=NULL and $to_date!=NULL)
			        {
						$sql=$sql." where (date(date_payment) BETWEEN '".$from_date."' AND '".$to_date."')";
					}
					else
					{
						$sql=$sql." where date(date_payment) ='".$from_date."'";
					}
				
					
			  break;
			case 1:
					   if($from_date!=NULL and $to_date!=NULL)
					{
						$sql=$sql." where ( date(date_payment)  BETWEEN '".$from_date."' AND '".$to_date."') AND payment_status=1";
					}
					else
					{
						$sql=$sql." where date(date_payment) ='".$from_date."' AND payment_status=1";
					}
					
					
			 break;  
			 case 2:
				    if($from_date!=NULL and $to_date!=NULL)
					{
						$sql=$sql." where (date(date_payment) BETWEEN '".$from_date."' AND '".$to_date."') AND payment_status=0";
					
					}
					else
					{
						$sql=$sql." where date(date_payment) ='".$from_date."' AND payment_status=0";
					}
			 break;  
			  
		}
		return $this->db->query($sql)->result_array();
   		
   }
	
	//get payments other than failure
	function get_online_payments()
	{
		$sql="SELECT
		  p.id_payment,
		  sa.account_name,
		  if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,
		  c.mobile,
		  sa.ref_no,
		  s.code,
		  if(s.scheme_type=0,'Amount',if(s.scheme_type=1,'Weight','Amount to Weight')) as scheme_type,
		  IFNULL(p.payment_amount,'-') as payment_amount,
		  p.metal_rate,
		  IFNULL(p.metal_weight, '-') as metal_weight,
		  IFNULL(p.date_payment,'-') as date_payment,
		  IFNULL(p.payment_mode,'-') as payment_mode,
		  IFNULL(sa.scheme_acc_number,'') as msno,
		  IFNULL(p.bank_acc_no,'-') as bank_acc_no,
		  IFNULL(p.bank_name,'-')as bank_name,
		  IFNULL(p.bank_IFSC,'-') as bank_IFSC,
		  IFNULL(p.bank_branch,'-') as bank_branch,
		  IFNULL(p.id_transaction,'-') as id_transaction,
		  IFNULL(p.payu_id,'-') as payu_id ,
		  IFNULL(p.card_no,'-') as card_no,		  
		  if(payment_status=1,'Approved',if(payment_status=1,'Rejected','Pending')) as payment_status,
		  IFNULL(p.payment_ref_number,'-') as payment_ref_number,
		  IFNULL(p.remark,'-') as remark
		 FROM ".self::PAY_TABLE." p
		 left join ".self::ACC_TABLE." sa on(p.id_scheme_account=sa.id_scheme_account)
		 Left Join ".self::CUS_TABLE." c on (sa.id_customer=c.id_customer)
		 left join ".self::SCH_TABLE." s on(sa.id_scheme=s.id_scheme)
		 Where p.payment_status <> -1
		 ORDER BY p.date_payment DESC ";
		 $online_pay=$this->db->query($sql);
		 return $online_pay->result_array();
	}
	
	function getPaymentByID($id_payment)
	{
		$this->db->select('*');
		$this->db->where('id_payment',$id_payment);
        $r = $this->db->get('payment');
        return $r->row_array();
	}
	
	
	function insert_payment($data)
	{
		$status =$this->db->insert(self::PAY_TABLE,$data);
			//print_r($this->db->last_query());exit;
		return $status;
	}
	
	function add_payment()
	{
		$_POST['pay']['payment_mode'] = 'MANUAL';
		$_POST['pay']['date_payment'] = date( 'Y-m-d H:i:s');
		$status =$this->db->insert(self::PAY_TABLE,$_POST['pay']);
		return $status;
	}
	
	function update_payment_status($id,$data)
	{
		$this->db->where('id_payment',$id); 
		$status=$this->db->update(self::PAY_TABLE,$data);
	//	print_r($this->db->last_query());exit;
		return $status;
	}
	
	
	
	function total_payments()
	{
		$sql="Select 'total_payments',count(id_payment) as total,
				 COUNT(CASE WHEN payment_type='Manual' or  payment_type='PDC/ECS' THEN 1 END) as manual,
				 COUNT(CASE WHEN payment_type='Payu Checkout' THEN 1 END) as online
				  from payment
				Where payment_status=1";
				
	   return	$this->db->query($sql)->row_array();		
	}
	
	function total_paid_unpaid($id_branch="")
	{
		
			$branchWiseLogin=$this->session->userdata('branchWiseLogin');
			$log_branch=$this->session->userdata('id_branch');
			$uid=$this->session->userdata('uid');
		 $sql="Select
         s.id_scheme,
			   s.scheme_name,
			   s.code,
				  COUNT(CASE WHEN IFNULL(cp.paid_installment,0) >0 THEN 1 END) as paid,
				  COUNT(CASE WHEN IFNULL(cp.paid_installment,0) =0 THEN 1 END) as unpaid
				From scheme s
        Left Join scheme_account sa on(s.id_scheme=sa.id_scheme)
        Left Join branch b on(b.id_branch=sa.id_branch)
		
				Left Join
				      (Select
							  sa.id_scheme_account,
							  COUNT(Distinct Date_Format(p.date_payment,'%Y%m')) as paid_installment,
							  COUNT(Date_Format(p.date_payment,'%Y%m')) as chances,
							  SUM(p.payment_amount) as total_amount,
							  SUM(p.metal_weight) as total_weight
							From payment p
							Left Join scheme_account sa on(p.id_scheme_account=sa.id_scheme_account and sa.active=1 and sa.is_closed=0)
							Where p.payment_status =1 and  Date_Format(Current_Date(),'%Y%m')=Date_Format(p.date_payment,'%Y%m')
							Group By sa.id_scheme_account) cp On(sa.id_scheme_account=cp.id_scheme_account)
			Where s.active=1 and sa.active=1 and sa.is_closed=0 ".($id_branch!='' ? " and sa .id_branch=".$id_branch:'')." ".($uid!=1 ? ($branchWiseLogin==1 ? ($log_branch!='' ? " and (sa.id_branch=".$log_branch." or b.show_to_all=1)":''):''):'')." 
 Group By sa.id_scheme";
		
	  $payments=$this->db->query($sql);
	
	  return $payments->result_array();
	}
/* Coded by ARVK */
	
	function payment_datewise($date)
	{
		 $sql_1="select sc.id_classification, sc.classification_name, 
				sum(p.payment_amount) as classification_total
			    FROM sch_classify sc
			      LEFT JOIN scheme s ON (sc.id_classification = s.id_classification)
			      LEFT JOIN scheme_account sa ON (s.id_scheme = sa.id_scheme)
			      LEFT JOIN payment p ON (sa.id_scheme_account = p.id_scheme_account)
			    WHERE sc.active=1 AND p.payment_status=1 AND p.id_scheme_account IS NOT NULL 
			    		AND p.payment_mode!='FP' AND p.due_type!='D'
			    		AND date(p.date_payment)='$date'
			      GROUP BY sc.id_classification";
		
	  	$payments['collection_report'] = $this->db->query($sql_1)->result_array();
		
		/*$sql_2="SELECT IFNULL(SUM(p.payment_amount),0.00) as opening_bal
			    FROM payment p
            WHERE p.payment_status=1
			    		AND p.payment_mode!='FP' AND p.due_type!='D'
			    		AND DATE(p.date_payment)=DATE_SUB('$date', INTERVAL 1 DAY)";*/
		$sql_2="SELECT dc.closing_balance as opening_bal
                    FROM daily_collection dc
                  WHERE dc.date= DATE_SUB('$date', INTERVAL 1 DAY)";
		
	  	$payments['collection_total'] = $this->db->query($sql_2)->row('opening_bal');
	
	  	return $payments;
	}
	
	function yesterday_collection($type="",$date="",$data="")
	{
		 switch($type){
		 	
			case 'get' :
				$sql="select IFNULL(sum(p.payment_amount),0.00) as total_collection,
					 				(SELECT dc.closing_balance
			                    	FROM daily_collection dc
			                  		WHERE dc.date= DATE_SUB('$date', INTERVAL 1 DAY))as old_balance
						    FROM payment p
			            WHERE p.payment_status=1
						    		AND p.payment_mode!='FP' AND p.due_type!='D'
						    		AND date(p.date_payment)='$date'";
					
				$payments=$this->db->query($sql);
				return $payments->row_array();
			
			break;
		 	
		 	case 'insert' :
				
				$status =$this->db->insert(self::DC_TABLE,$data);
				return $status;
			
			break;
		 
		 }
		 	
	}
/* / Coded by ARVK */
	
	function failed_payments()
	{
		
		$sql ="SELECT
		          p.id_payment,
				  IFNULL(p.id_transaction,'') as id_transaction,
				  IFNULL(p.payu_id,'') as payu_id,
                  Date_Format(p.date_payment,'%d-%m-%Y') as trans_date ,
				  IFNULL(p.receipt_no,'') as receipt_no,
				  IF(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,
				  c.mobile as mobile,
				  IFNULL(sa.ref_no,'')as client_id,
				  IF(sa.scheme_acc_number is null,'',concat(s.code,'-',sa.scheme_acc_number)) as chit_number,
				  IFNULL(s.code,'') as group_code,
				  IFNULL(p.payment_amount,'0.00') as amount,
				  IFNULL(p.metal_weight,'0.00') as weight,
				  IFNULL(p.payment_mode,'') as payment_mode,
				  IFNULL(p.bank_name,'') as bank_name,
				  IFNULL(p.bank_branch,'') as  branch_name,
				  IFNULL(s.id_metal,'') as metal,
				  IFNULL(p.card_no,'')    as card_no,
				  IFNULL(p.payment_ref_number,'') as approval_no,
				  IFNULL(sa.id_scheme_account,'') as id_scheme_account,
				  IFNULL(p.id_payment,'') as ref_no,
				  IFNULL(sa.is_new,'') as new_customer,
				  IFNULL(p.metal_rate,'') as rate,
				  IFNULL(p.remark,'') as remark,
				  IF(p.payment_status = 1,'Success',if(p.payment_status = 1,'Rejected',if(p.payment_status = 0,'Pending','Failed')) ) as pay_status
				FROM
					payment p
				LEFT JOIN scheme_account sa ON (p.id_scheme_account = sa.id_scheme_account)
				LEFT JOIN customer c ON (sa.id_customer = c.id_customer)
				LEFT JOIN scheme s ON (sa.id_scheme = s.id_scheme)
			   WHERE (p.payment_status='-1' or p.payment_status is null) AND p.payu_id is null
			   ORDER BY p.date_payment DESC";
			   
		 $payments=$this->db->query($sql);
	     return $payments->result_array();
	}
	
	function paymentByDateRange($from,$to,$status,$mode)
	{
	
		$sql ="SELECT
		          p.id_payment,
				  IFNULL(p.id_transaction,'') as id_transaction,
				  IFNULL(p.payu_id,'') as payu_id,
                  Date_Format(p.date_payment,'%d-%m-%Y') as trans_date ,
				  IFNULL(p.receipt_no,'') as receipt_no,
				  IF(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,
				  c.mobile as mobile,
				  IFNULL(sa.ref_no,'')as client_id,
				  IFNULL(sa.scheme_acc_number,'') as msno,
				  IFNULL(s.code,'') as group_code,
				  IFNULL(p.payment_amount,'0.00') as amount,
				  IFNULL(p.metal_weight,'0.00') as weight,
				  IFNULL(p.payment_mode,'') as payment_mode,
				  IFNULL(p.bank_name,'') as bank_name,
				  IFNULL(p.bank_branch,'') as  branch_name,
				  IFNULL(s.id_metal,'') as metal,
				  IFNULL(p.card_no,'')    as card_no,
				  IFNULL(p.payment_ref_number,'') as approval_no,
				  IFNULL(sa.id_scheme_account,'') as id_scheme_account,
				  IFNULL(p.id_payment,'') as ref_no,
				  IFNULL(sa.is_new,'') as new_customer,
				  IFNULL(p.metal_rate,'') as rate,
				  IFNULL((p.payment_amount),'0.00') as paid_amt,
				  IFNULL(p.remark,'') as remark,
				  psm.payment_status as pay_status
				FROM
					payment p
				LEFT JOIN scheme_account sa ON (p.id_scheme_account = sa.id_scheme_account)
				LEFT JOIN customer c ON (sa.id_customer = c.id_customer)
				LEFT JOIN scheme s ON (sa.id_scheme = s.id_scheme)
				LEFT Join payment_status_message psm ON (p.payment_status=psm.id_status_msg)
			  WHERE (p.date_payment BETWEEN '$from'  AND '$to') ";
		   
		   if($mode!='ALL')
           {
			   $sql.=" AND p.payment_mode ='$mode'  ";
		   }			   
		   
		   if($status!='ALL')
		   {
			   $sql.=" AND p.payment_status = '$status' ";
		   }	   
          
		   $sql.=" ORDER BY p.date_payment DESC ";
		   
		 
         
		 $payments=$this->db->query($sql);
	     return $payments->result_array();
	}
	
	//update gateway response
	function updateGatewayResponse($data,$txnid)
	{
		$this->db->where('ref_trans_id',$txnid); 
		$status = $this->db->update('payment',$data);	
		$data = $this->get_lastUpdateID($txnid);
		$result=array(
		              'status' => $status,
		              'id_payment' => (isset($data['id_payment'])?$data['id_payment']:''),
		              'id_scheme_account' => (isset($data['id_scheme_account'])?$data['id_scheme_account']:''),
		              'redeemed_amount' => (isset($data['redeemed_amount'])?$data['redeemed_amount']:0)
		              );
		
		return $result;
	}
		
	function get_lastUpdateID($txnid)
	{
		$this->db->select('id_payment,id_scheme_account,redeemed_amount');  
		$this->db->where('ref_trans_id',$txnid); 
		$payid = $this->db->get('payment');	
		return $payid->row_array();
	}
	
	 function get_invoiceData($payment_no,$id_scheme_account)
	{
		$records = array();
		$query_invoice = $this->db->query("SELECT pay.id_scheme_account as id_scheme_account, sch_acc.account_name,if(c.has_lucky_draw=1 && sch.is_lucky_draw = 1,concat(ifnull(sch_acc.group_code,''),'  ',ifnull(sch_acc.scheme_acc_number,'Not Allocated')),concat(sch.code,'  ',IFNULL(sch_acc.scheme_acc_number,'Not Allocated')))as scheme_acc_number,
							gst_amount,flexible_sch_type,DATE_FORMAT(sch_acc.maturity_date,'%d-%m-%Y') as maturity_date,DATE_FORMAT(pay.date_payment,'%d-%m-%Y') as date_payment, sch.scheme_name as scheme_name, sch_acc.id_branch,avg_calc_ins,avg_payable,
							sch.code as sch_code,sch.hsn_code,pay.payment_amount as payment_amount,
							IF(cus.lastname is null,cus.firstname,concat(cus.firstname,cus.lastname)) as name,	
							cus.firstname,cus.lastname,pay.is_print_taken,pay.discountAmt,
							 if(e.lastname is null,e.firstname,concat(e.firstname,' ',e.lastname)) as employee,						
							addr.address1 as address1,addr.address2 as address2,addr.address3 as address3,ct.name as city,
							addr.pincode,cus.email,if(payment_mode='CC','Credit Card',if(payment_mode='NB','Net Banking',if(payment_mode='CD','Cheque or DD',if(payment_mode='CO','Cash Pick Up',if(payment_mode='FP','Free Payment',pm.mode_name))))) as payment_mode,id_transaction,payment_ref_number,
							if((c.receipt_no_set= 1 || c.receipt_no_set= 3 && pay.payment_status =1 && pay.receipt_no is null ),pay.receipt_no,if((c.receipt_no_set=1 || c.receipt_no_set= 3 && pay.payment_status =1 && pay.receipt_no!=''),pay.receipt_no,pay.receipt_no)) as receipt_no,bank_name,bank_acc_no,bank_branch,if(sch.scheme_type=0,'-',metal_weight) as metal_weight,if(sch.scheme_type=0,'-',metal_rate) as metal_rate,scheme_type,IF(sch_acc.is_opening=1,IFNULL(sch_acc.paid_installments,0)+ COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')),COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')))  as installment,DATE_FORMAT(Date_add(date_payment,Interval 1 month),'%b %Y') as next_due,pay.id_transaction as trans_id,cus.mobile, pay.due_type,ifnull(pay.add_charges,0.00) as add_charges,pay.payment_type,sch.charge_head,sch.id_scheme,pay.gst,pay.gst_type,pay.date_add,addr.id_state,s.name as state,con.name as country,
							
							(select SUM(pa.metal_weight)  from payment pa left
join scheme_account sch_acc on sch_acc.id_scheme_account=pa.id_scheme_account
left join scheme sch on sch.id_scheme=sch_acc.id_scheme
where pa.id_payment<='".$payment_no."' and pa.id_scheme_account='".$id_scheme_account."' and  pa.payment_status=1)as total_weight,
							
							(select IFNULL(IF(sch_acc.is_opening=1,IFNULL(sch_acc.paid_installments,0)+ IFNULL(if(sch.scheme_type = 1 and sch.min_weight != sch.max_weight , COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')), sum(pay.no_of_dues)),0), if(sch.scheme_type = 1 and sch.min_weight != sch.max_weight or (sch.scheme_type=3 AND sch.firstPayamt_as_payamt = 0), COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')), sum(pay.no_of_dues))) ,0) from payment pay left
 join scheme_account sch_acc on sch_acc.id_scheme_account=pay.id_scheme_account
 left join scheme sch on sch.id_scheme=sch_acc.id_scheme
 where pay.id_payment<='".$payment_no."' and pay.id_scheme_account='".$id_scheme_account."' and  pay.payment_status=1)
  as paid_installments,sch.total_installments
								   FROM payment as pay								
								
									   JOIN  chit_settings c
									   	Left Join employee e On (e.id_employee=pay.id_employee)
										LEFT JOIN scheme_account sch_acc ON sch_acc.id_scheme_account = pay.id_scheme_account
										LEFT JOIN scheme sch ON sch.id_scheme = sch_acc.id_scheme
										LEFT JOIN customer as cus ON cus.id_customer = sch_acc.id_customer
										LEFT JOIN address as addr ON addr.id_customer = cus.id_customer 
										LEFT JOIN city as ct ON addr.id_city = ct.id_city
										LEFT JOIN state as s ON addr.id_state = s.id_state 
							LEFT JOIN country as con ON addr.id_country = con.id_country 
										LEFT JOIN payment_mode pm ON (pay.payment_mode = pm.short_code)
									WHERE pay.payment_status=1 AND id_payment = '".$payment_no."'");
		if($query_invoice->num_rows() > 0)
			{
				foreach($query_invoice->result() as $row)
				{
					$records[] = array('id_scheme_account' => $row->id_scheme_account,'scheme_acc_number' => 
					$row->scheme_acc_number,'date_payment' => $row->date_payment,'scheme_name' => 
					$row->scheme_name, 'sch_code' => $row->sch_code, 'payment_amount' =>
					$row->payment_amount,'name' => $row->name, 
					$row->payment_amount,'firstname' => $row->firstname, 'avg_calc_ins' => $row->avg_calc_ins, 'avg_payable' => $row->avg_payable, 
					$row->payment_amount,'lastname' => $row->lastname, 'maturity_date' => $row->maturity_date,'flexible_sch_type' => $row->flexible_sch_type, 'gst_amount' => $row->gst_amount,
					'id_payment' => $payment_no,'address1' => $row->address1,'address2' => $row->address2,'address3' => $row->address3,'city' => $row->city,'pincode' => $row->pincode,'email' => $row->email,'payment_mode' => $row->payment_mode,'id_transaction' => $row->id_transaction,'payment_ref_number' => $row->payment_ref_number,'receipt_no' => $row->receipt_no,'bank_name' => $row->bank_name,'bank_acc_no' => $row->bank_acc_no,'id_branch' => $row->id_branch,'bank_branch' => $row->bank_branch,'metal_weight' => $row->metal_weight,'metal_rate' => $row->metal_rate,'scheme_type' => $row->scheme_type,'installment'=>$row->installment,'next_due'=>$row->next_due,'trans_id'=>$row->trans_id,'account_name'=>$row->account_name,'mobile'=>$row->mobile,'due_type'=>$row->due_type,'add_charges' => $row->add_charges,'charge_head' => $row->charge_head,'payment_type' => $row->payment_type,'id_scheme' => $row->id_scheme,'gst_type' => $row->gst_type,'gst' => $row->gst,'hsn_code' => $row->hsn_code,'date_add' => $row->date_add,'id_state' => $row->id_state,'is_print_taken ' => $row->is_print_taken,'state' => $row->state,'country' => $row->country,'paid_installments' => $row->paid_installments,'total_installments' => $row->total_installments,'total_weight'=>$row->total_weight,'employee'=>$row->employee,'discountAmt'=>$row->discountAmt);
				}
				 
			}
			 //print_r($this->db->last_query());exit;	
			return $records;
							
	}
	
	
	function monthly_rate($adjust_by)
	{
		$sql =" Select ";
		switch($adjust_by){
			case 1 :
			       $sql=$sql." max(goldrate_22ct) as rate ";
				break;
			
			case 2:
				  $sql=$sql." min(goldrate_22ct) as rate ";	
				break;
				
			case 3:
				 $sql=$sql." Round(Avg(goldrate_22ct),2) as rate ";
				break;
		}
		
		$sql=$sql." From metal_rates
		            Where month(updatetime)=month(curdate())  ";
		            
		return $this->db->query($sql)->row('rate');            
	}
	
	function monthly_rate_variation()
	{
		$sql = "Select max(goldrate_22ct) as max_rate, min(goldrate_22ct) as min_rate, Round(Avg(goldrate_22ct),2) as avg_rate, count(id_metalrates) as count From metal_rates Where month(updatetime) = month(curdate())";
		            
		return $this->db->query($sql)->result_array();  
		       
	}
	
	
	function purchase_rate($adjust_by,$date)
	{
		$sql =" Select ";
		switch($adjust_by){
			case 0 :
			       $sql=$sql." max(goldrate_22ct) as rate ";
				break;
			
			case 1:
				  $sql=$sql." min(goldrate_22ct) as rate ";	
				break;
				
			case 2:
				 $sql=$sql." Round(Avg(goldrate_22ct),2) as rate ";
				break;
		}
		
		$sql=$sql." From metal_rates
		            Where date(updatetime)=date('".$date."')  ";
		            
		return $this->db->query($sql)->row('rate'); 
	}
		
	function scheme_rate($adjust_by,$start_date,$end_date)
	{
		$sql =" Select ";
		switch($adjust_by){
			case 0 :
			       $sql=$sql." max(goldrate_22ct) as rate ";
				break;
			
			case 1:
				  $sql=$sql." min(goldrate_22ct) as rate ";	
				break;
				
			case 2:
				 $sql=$sql." Round(Avg(goldrate_22ct),2) as rate ";
				break;
		}
		
		$sql=$sql." From metal_rates
		            Where date(updatetime) between date('".$start_date."')and date('".$end_date."')  ";
		            
		return $this->db->query($sql)->row('rate'); 
	}
	
	function get_payment_by_scheme($id)
    {
		 $sql = "SELECT
				    p.id_payment,
            		IFNULL(Date_format(p.date_payment,'%d-%m%-%Y'),'-') as date_payment,
			 		if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,
            		sa.scheme_acc_number,
            		c.mobile,
            		IFNULL(p.payment_amount,'-') as payment_amount
            	FROM payment p
				    left join scheme_account sa on(p.id_scheme_account=sa.id_scheme_account)
				    Left Join customer c on (sa.id_customer=c.id_customer)
				    left join scheme s on(sa.id_scheme=s.id_scheme)
			    WHERE sa.id_scheme='".$id."' and p.payment_status=1 and p.fix_weight=0";
		return $this->db->query($sql)->result_array();
	}
	
/* Coded by ARVK*/	
	function getMetalRate()   // last rate[max] taken here //
	{
		$sql="SELECT * FROM metal_rates m WHERE m.id_metalrates=( SELECT max(m.id_metalrates) FROM metal_rates m ) ORDER BY id_metalrates DESC ";	    
	    return $this->db->query($sql)->row_array();
	    
	}
/* / Coded by ARVK*/	
	function payments_by_scheme($id_scheme)
	{
		$sql="Select
			          p.id_payment,
							  IFNULL(p.id_transaction,'') as id_transaction,
							  IFNULL(p.payu_id,'') as payu_id,
			          		  Date_Format(p.date_payment,'%d-%m-%Y') as trans_date ,
							  IFNULL(p.receipt_no,'') as receipt_no,
							  IFNULL(sa.ref_no,'')as client_id,
							  IFNULL(sa.scheme_acc_number,'') as msno,
							  IFNULL(s.code,'') as group_code,
							  IFNULL(p.payment_amount,'0.00') as payment_amount,
							  IFNULL(p.metal_weight,'0.00') as weight,
							  IFNULL(p.payment_mode,'') as payment_mode,
							  IFNULL(p.bank_name,'') as bank_name,
							  IFNULL(p.bank_branch,'') as  branch_name,
							  IFNULL(s.id_metal,'') as metal,
							  IFNULL(p.card_no,'')    as card_no,
							  IFNULL(p.payment_ref_number,'') as approval_no,
							  IFNULL(sa.id_scheme_account,'') as id_scheme_account,
							  IFNULL(p.id_payment,'') as ref_no,
							  IFNULL(sa.is_new,'') as new_customer,
							  IFNULL(p.metal_rate,'') as rate,					
							  IFNULL(p.remark,'') as remark,
			          m.goldrate_22ct as purchase_rate,
			          m.updatetime,
			          s.id_scheme
			From scheme s
			Left Join scheme_account sa on(s.id_scheme=sa.id_scheme)
			Left Join payment p on (sa.id_scheme_account=p.id_scheme_account and p.payment_status=1)
			Left Join metal_rates m on (date(updatetime) = date(p.date_payment))
			Where s.scheme_type=2 and p.fix_weight=0 and s.id_scheme='".$id_scheme."'
			group by id_payment";    
          return $this->db->query($sql)->result_array();
	}
	
	function insert_settlement_detail($data)
	{
		 $status = $this->db->insert(self::SETT_DET_TABLE,$data);
	 	 return	array('status'=>$status,'insertID'=>($status == TRUE ? $this->db->insert_id():''));
	}
	
	function view_settlement_detail($id_settlement)
	{
				$sql = " SELECT
							  p.id_payment,
							  sa.account_name,
							  if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,
							  c.mobile,
							  sa.scheme_acc_number,
							  s.code,
							  p.id_employee,
		                      if(e.lastname is null,e.firstname,concat(e.firstname,' ',e.lastname)) as employee,
							  if(s.scheme_type=0,'Amount',if(s.scheme_type=1,'Weight','Amount to Weight')) as scheme_type,
							  IFNULL(p.payment_amount,'-') as payment_amount,
							  p.metal_rate,
							  IFNULL(p.metal_weight, '-') as metal_weight,
							  IFNULL(Date_format(p.date_payment,'%d-%m%-%Y'),'-') as date_payment,
					          p.payment_type,
							  p.payment_mode as payment_mode,
							  IFNULL(sa.scheme_acc_number,'') as msno,
							  IFNULL(p.bank_acc_no,'-') as bank_acc_no,
							  IFNULL(p.bank_name,'-')as bank_name,
							  IFNULL(p.bank_IFSC,'-') as bank_IFSC,
							  IFNULL(p.bank_branch,'-') as bank_branch,
							  IFNULL(p.id_transaction,'-') as id_transaction,
							  IFNULL(p.payu_id,'-') as payu_id ,
							  IFNULL(p.card_no,'-') as card_no,
							  psm.payment_status as payment_status,
							  p.payment_status as id_status,
							  psm.color as status_color,
							  IFNULL(p.payment_ref_number,'-') as payment_ref_number,
							  IFNULL(p.remark,'-') as remark,
							  
                if(sd.`type`=1,'Monthly','Purchase') as set_type,
                if(sd.`adjust_by`=1,'Highest rate',if(sd.`adjust_by`=2,'Lowest rate',if(sd.`adjust_by`=3,'Average rate','Manual rate'))) as adjust_by
                
		FROM settlement_detail sd
		    Left Join payment p On(sd.id_payment=p.id_payment)
				Left Join scheme_account sa on(p.id_scheme_account=sa.id_scheme_account)
				Left Join employee e On (e.id_employee=p.id_employee)
				Left Join customer c on (sa.id_customer=c.id_customer)
				Left join scheme s on(sa.id_scheme=s.id_scheme)
				Left Join payment_mode pm on (p.payment_mode=pm.id_mode)
				Left Join payment_status_message psm On (p.payment_status=psm.id_status_msg)
		Where sd.id_settlement=".$id_settlement;
		
		
		return $this->db->query($sql)->result_array();
	}
	
	function weight_settlementDB($type="",$id="",$data="")
	{
		switch($type){
			case 'get' :
			         $sql="Select
						        s.id_settlement,
						        schemes,
						        s.date_upd,
						        concat(e.firstname,' ',e.lastname) as employee,
						        s.success,
						        (SELECT COUNT(sd.id_settlement)
						        		FROM settlement_detail sd
						        		WHERE sd.id_settlement = s.id_settlement)as acc_count
						From settlement s
						Left Join employee e On (s.id_employee=e.id_employee)";  
						
			           return $this->db->query($sql)->result_array();
				break;
				
			case 'insert' :
			        $status = $this->db->insert(self::SETT_TABLE,$data);
	 	            return	array('status'=>$status,'insertID'=>($status == TRUE ? $this->db->insert_id():''));
				break;
			case 'update' :
					$this->db->where('id_settlement',$id); 
		            $status = $this->db->update(self::SETT_TABLE,$data);			       
	 	            return	array('status'=>$status,'updateID'=>$id);
				break;		
			case 'delete' :
					$this->db->where('id_settlement',$id); 
		            $status = $this->db->delete(self::SETT_TABLE,$data);			       
	 	            return	array('status'=>$status,'deleteID'=>$id);
				break;
			
			default:
			        return array(
			        				'id_settlement' => NULL,
			        				'type'          => 1,
			        				'adjust_by'     => 1,
			        				'rate'          => '0.00'
			        			);
				break;
		}
	}
	
	//fetch data to send email
	public function getPpayment_data($id){
		 $sql="Select
						  p.id_payment,s.code,
						  p.id_scheme_account,c.firstname,c.lastname,c.email,c.mobile,s.scheme_name,s.total_installments,
						  IFNULL((select IF(sa.is_opening=1,IFNULL(sa.paid_installments,0)+ COUNT(Distinct Date_Format(date_payment,'%Y%m')), if(s.scheme_type = 1 or s.scheme_type=3, COUNT(Distinct Date_Format(date_payment,'%Y%m')), SUM(no_of_dues))) from payment where id_scheme_account=sa.id_scheme_account and payment_status=1 group by id_scheme_account),0)as paid_installments,
 
						  if(cs.has_lucky_draw=1 && s.is_lucky_draw = 1,concat(ifnull(sa.group_code,''),'  ',ifnull(sa.scheme_acc_number,'Not allocated')),concat(ifnull(s.code,''),' ',ifnull(sa.scheme_acc_number,'Not allocated')))as scheme_acc_number,
						  sa.account_name,
						   IFNULL(p.id_transaction,'-') as id_transaction, 
						  p.payu_id,
						  p.id_post_payment,
						  p.id_drawee,
						  da.account_no as drawee_acc_no,
						  da.account_name as drawee_account_name,
						  Date_format(date_payment,'%d-%m-%Y') as date_payment,
						  p.payment_type,
						  p.payment_mode,
						  p.payment_amount,
						  p.add_charges,
						  p.metal_rate,
						  p.metal_weight,
						  p.cheque_date,
						   IFNULL(p.cheque_no,'-') as cheque_no,  
						  p.bank_acc_no,
						  p.bank_name,
						  p.bank_branch,
						  p.bank_IFSC,
						  p.card_no,
						  p.card_holder,
						  p.cvv,
						  p.exp_date,p.act_amount,
						  p.payment_ref_number,
						  p.payment_status as id_payment_status,
						  p.remark,
						  psm.payment_status as payment_status,
						  psm.color as status_color,cs.currency_symbol,cs.currency_name,cs.walletIntegration
						From payment p
						Left Join scheme_account sa on (p.id_scheme_account=sa.id_scheme_account)
						Left Join customer c on (sa.id_customer=c.id_customer)
						Left Join drawee_account da on (p.id_drawee=p.id_drawee)
						Left Join payment_mode pm on (p.payment_mode=pm.id_mode)
						Left Join payment_status_message psm On (p.payment_status=psm.id_status_msg)
						Left join scheme s on(sa.id_scheme=s.id_scheme)
						join chit_settings cs
						Where p.id_payment=".$id;
			      return $this->db->query($sql)->row_array();
	}
	
	//fetch data to send email
	public function getPostpayment_data($id){
		 $sql="SELECT
			      pp.id_post_payment,s.code,
			      IFNULL(sa.scheme_acc_number,'Not Allocated') as scheme_acc_number,c.email,c.mobile,c.firstname,c.lastname,s.scheme_name,
			      sa.id_scheme_account,
			      sa.account_name, pp.amount as act_amount,
			      pp.metal_rate,
			      pp.weight,
			      pp.pay_mode as payment_mode,
			      Date_format(pp.date_payment,'%d-%m%-%Y') as date_payment,
			      pp.cheque_no,
			      pp.payment_status as id_payment_status,
			      psm.payment_status,
			      psm.color as status_color,
			      pp.charges,
			      pp.date_presented,cs.currency_symbol
			FROM postdate_payment pp
			Left Join scheme_account sa on (pp.id_scheme_account=sa.id_scheme_account)
			Left Join customer c on (c.id_customer=sa.id_customer)
			Left Join payment_status_message psm on (pp.payment_status=psm.id_status_msg)
      Left Join drawee_account da on (pp.id_drawee=da.id_drawee)
      Left Join bank db on (da.id_bank=db.id_bank)
      Left Join bank pb on (pp.payee_bank=pb.id_bank)
      Left join scheme s on(sa.id_scheme=s.id_scheme)
      join chit_settings cs
      Where pp.payment_status!=1 And  pp.id_post_payment=".$id;
			      return $this->db->query($sql)->row_array();
	}
	
	//for online payment trans details
	function get_online_payment($id_payment)
	{
		
		$sql="SELECT
		  p.id_payment,p.gst,p.gst_type,ifnull(p.discountAmt,0.00) as discount,
		  sa.account_name,
		  if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,
		  c.mobile,
		  sa.ref_no,
		  s.code,
		  if(s.scheme_type=0,'Amount',if(s.scheme_type=1,'Weight','Amount to Weight')) as scheme_type,
		  IFNULL(p.payment_amount,'-') as payment_amount,
		  p.metal_rate,p.act_amount,p.no_of_dues,
		  IFNULL(p.metal_weight, '-') as metal_weight,
		  IFNULL(p.date_payment,'-') as date_payment,
		  IFNULL(p.payment_mode,'-') as payment_mode,
		  concat(if(cs.has_lucky_draw=1 && s.is_lucky_draw = 1,sa.group_code,s.code),' ', IFNULL(sa.scheme_acc_number,'Not Allocated')) as scheme_acc_number,
		  IFNULL(p.bank_acc_no,'-') as bank_acc_no,
		  IFNULL(p.bank_name,'-')as bank_name,
		  IFNULL(p.bank_IFSC,'-') as bank_IFSC,
		  IFNULL(p.add_charges,0.00) as bank_charges,
		  IFNULL(p.bank_branch,'-') as bank_branch,
		  IFNULL(p.id_transaction,'-') as trans_id,
		  IFNULL(p.payu_id,'-') as payu_id ,
		  IFNULL(p.card_no,'-') as card_no,		  
		  p.payment_status as id_payment_status,
		  psm.payment_status as payment_status,
		  IFNULL(p.payment_ref_number,'-') as payment_ref_number,
		  IFNULL(SUBSTRING(p.remark,45,36),'-') as remark,cs.currency_symbol
		 FROM ".self::PAY_TABLE." p
		 left join ".self::ACC_TABLE." sa on(p.id_scheme_account=sa.id_scheme_account)
		 Left Join ".self::CUS_TABLE." c on (sa.id_customer=c.id_customer)
		 left join ".self::SCH_TABLE." s on(sa.id_scheme=s.id_scheme)
		 Left Join ".self::PAY_STATUS." psm On (p.payment_status=psm.id_status_msg)
		 join chit_settings cs
		 Where p.id_payment=".$id_payment." 
		 ORDER BY p.date_payment DESC ";
		 $online_pay=$this->db->query($sql);
		 return $online_pay->row_array();
	}
//scheme free payments installments
	/*function get_freepaycontents()
	{
					  
				$sql="SELECT
							s.id_scheme, s.code, s.scheme_type, s.total_installments, 
							s.allow_advance,s.advance_months,  s.has_free_ins,s.free_payInstallments 
									FROM scheme s 
										where 
											s.has_free_ins=1 and s.visible=1 and s.active=1";
				 return $this->db->query($sql)->result_array();   
	}*/
	
	
    function get_freepaycust()
	{ 
		$sql="select sc.gst_type,sc.gst,
					sc.id_scheme, sc.code, sc.scheme_type, sc.total_installments, sc.amount,sc.free_payment,cs.receipt_no_set,
					sc.allow_advance,sc.advance_months,  sc.has_free_ins,sc.free_payInstallments ,s.id_scheme_account,sc.min_weight, sc.max_weight,
					
					IFNULL(IF(s.is_opening=1,IFNULL(s.paid_installments,0)+ IFNULL(if(sc.scheme_type = 1 and sc.min_weight != sc.max_weight , COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')), sum(pay.no_of_dues)),0), if(sc.scheme_type = 1 and sc.min_weight != sc.max_weight , COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')), sum(pay.no_of_dues))) ,0)
  as paid_installments,
					cm.company_name, cm.short_code,
					IFNULL(Date_Format(max(pay.date_add),'%m-%Y'),IFNULL(IF(s.is_opening=1,Date_Format(s.last_paid_date,'%m-%Y'),'')  ,0))                 as last_paid_month				
				from scheme_account s
					left join customer c on (s.id_customer=c.id_customer)
					left join scheme sc on (sc.id_scheme=s.id_scheme)
					left join payment pay on (pay.id_scheme_account=s.id_scheme_account  and (pay.payment_status=2 or pay.payment_status=1))
					join chit_settings cs
					join company cm
				Where s.is_closed=0 and sc.has_free_ins=1 and sc.active=1
				group by s.id_scheme_account";				
				return $this->db->query($sql)->result_array();   
			
			
	}
	
	
		
	/*function get_freepayamount($data)
	{
		$sql_scheme = $this->db->query("select s.free_payment, s.amount, s.scheme_type,s.free_payInstallments, s.min_weight, s.max_weight, c.company_name, c.short_code 
				
				from scheme s join company c
				
				where s.id_scheme=".$data);
				
		  	$sch_data = $sql_scheme->row_array();
			return $sch_data;
		
		
	}*/	
// scheme free payments installments
	function isAcnoAvailable($id)
	{
		$sql="SELECT sa.id_scheme,sa.id_branch as branch,sa.scheme_acc_number,s.is_lucky_draw FROM scheme_account sa 
		Left Join scheme s on (s.id_scheme=sa.id_scheme)
		where sa.id_scheme_account=".$id ;
		$result =$this->db->query($sql);
		if($result->num_rows() > 0){
			if($result->row()->scheme_acc_number =='' || $result->row()->scheme_acc_number ==NULL){
				return	array('status'=>TRUE,'id_scheme'=>$result->row()->id_scheme,'branch'=>$result->row()->branch,'is_lucky_draw'=>$result->row()->is_lucky_draw);
			}
			else{
				
				return	array('status'=>false);
			}
		}
		else{
			return	array('status'=>false);
		}
	    //print_r($result);exit;
	}
	function getMetalRateBydate($date)
	{
		//$sql="SELECT max(updatetime) as date,goldrate_22ct FROM metal_rates where date_format(updatetime,'%Y-%m-%d %H:%i:%s')<= date_format('".$date."','%Y-%m-%d %H:%i:%s')" ;
		$sql="SELECT goldrate_22ct FROM metal_rates where date_format(updatetime,'%Y-%m-%d %H:%i:%s')<= date_format('".$date."','%Y-%m-%d %H:%i:%s') ORDER BY id_metalrates DESC LIMIT 1" ;
		
		$result =$this->db->query($sql);
		if($result->num_rows() > 0){
			return $result->row()->goldrate_22ct;
		}
	    
	}
	
//new reports  
	
	
		//  payment_by_daterange chked&updtd emp login branchwise  data show//HH
	
		function payment_list_daterange($from_date,$to_date,$type="",$limit="",$id="",$id_employee,$acc="")
	{   
	
		if($this->branch_settings==1){
				$id_branch  = $this->input->post('id_branch');}
			else{
			$id_branch = '';}
		$branch_settings=$this->session->userdata('branch_settings');
			$branch=$this->session->userdata('id_branch');
			$uid=$this->session->userdata('uid');
            $date_type= $this->input->post('date_type');
		
		$sql="SELECT
					    IFNULL(e.emp_code,'-') as emp_code,p.id_payment,compy.gst_number,p.is_offline,sa.id_scheme_account,
					  sa.account_name,p.act_amount,sa.id_branch,sa.ref_no,chit.has_lucky_draw as has_lucky_draw,s.is_lucky_draw as is_lucky_draw,
					
						if(p.receipt_no!='',p.receipt_no ,p.id_payment)as receipt_no,
					  if(c.lastname is null,c.firstname,c.firstname) as name,
					  c.mobile,sg.group_code as group_code,
					IFNULL(sa.group_code,'') as scheme_group_code,
					 IFNULL(sa.scheme_acc_number,'Not Allocated') as scheme_acc_number,
					 p.due_type,
					  s.code,
					  p.id_employee,
                      if(e.lastname is null,e.firstname,concat(e.firstname,' ',e.lastname)) as employee,
					  if(s.scheme_type=0,'Amount',if(s.scheme_type=1,'Weight','Amount to Weight')) as scheme_type,
					  
					IF(p.payment_mode='FP','0',p.payment_amount) as payment_amount,
					
					IF(p.payment_mode='FP',p.payment_amount,'0') as incentive,
					
					p.discountAmt as discountAmt,
					
					IF(s.gst_type=0,(p.payment_amount-				
					(p.payment_amount*(100/(100+s.gst))))/2,((p.payment_amount*(s.gst/100))/2)) as sgst,
					
					IF(s.gst_type=0,(p.payment_amount-			
					(p.payment_amount*(100/(100+s.gst))))/2,((p.payment_amount*(s.gst/100))/2)) as cgst,
					
					
					IFNULL(p.payment_amount, '0.00') as amount,
					   
					  p.metal_rate,
					  IF(p.metal_weight!='0' && p.metal_weight!='' ,p.metal_weight,' - ') as metal_weight,
					  
					  IFNULL(Date_format(p.date_payment,'%d-%m%-%Y'),'-') as date_payment,
					  IFNULL(e.emp_code,'-') as emp_code,
					  
			          p.payment_type,sa.is_closed, sa.active,
					  
						IFNULL((select IF(sa.is_opening=1,IFNULL(sa.paid_installments,0)+ COUNT(Distinct Date_Format(date_payment,'%Y%m')), if(s.scheme_type = 1 or s.scheme_type=3, COUNT(Distinct Date_Format(date_payment,'%Y%m')), SUM(no_of_dues))) from payment where id_scheme_account=sa.id_scheme_account and payment_status=1 group by id_scheme_account),0)as paid_installments,s.gst_type, s.gst,
					   p.payment_mode as payment_mode,
					  IFNULL(p.bank_acc_no,'-') as bank_acc_no,
					  IFNULL(p.bank_name,'-')as bank_name,
					  IFNULL(p.bank_IFSC,'-') as bank_IFSC,
					
					  IFNULL(p.bank_branch,'-') as bank_branch,
					  IFNULL(p.id_transaction,'-') as id_transaction,
					  IFNULL(p.payu_id,'-') as payu_id ,
					  IFNULL(p.card_no,'-') as card_no,
					  psm.payment_status as payment_status,
					  p.payment_status as id_status,
					  psm.color as status_color,chit.gst_setting,
					  IFNULL(p.payment_ref_number,'-') as payment_ref_number,
					  IFNULL(p.remark,'-') as remark,sa.active as active,sa.is_closed as is_closed
				FROM payment p
				join company compy 
				join chit_settings chit 
				left join scheme_account sa on(p.id_scheme_account=sa.id_scheme_account) and p.payment_status=1 or p.payment_status=4
				Left Join scheme_group sg On (sa.group_code = sg.group_code )
				Left Join employee e On (e.id_employee=p.id_employee)
				Left Join branch b On (b.id_branch=p.id_branch)
				Left Join customer c on (sa.id_customer=c.id_customer)
				left join scheme s on(sa.id_scheme=s.id_scheme)
			    Left Join payment_mode pm on (p.payment_mode=pm.id_mode)		
			    Left Join payment_status_message psm On (p.payment_status=psm.id_status_msg)
			   
       Where ".($acc==1?" sa.is_closed=0 and sa.active=1 and ":($acc==2?" sa.active=0 and sa.is_closed=1 and ":''))."  (date(".($date_type!='' ? ($date_type==2 ?"p.custom_entry_date":"p.date_payment") : "p.date_payment").") BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') And p.payment_status=1  ".($id_employee!='' ? " and p.id_employee=".$id_employee."":'')."
		    ".($id!=''?"  And s.id_scheme=".$id:'')." 
        	".($type!=''?"  And p.payment_type=".$type:'')."   ".($uid!=1 ? ($branch_settings==1 ? ($id_branch!=0 && $id_branch!='' ? "and p.id_branch=".$id_branch."" : ($branch!='' ? "and (p.id_branch=".$branch." or b.show_to_all=2)":'')):'') : ($id_branch!=0 && $id_branch!=''? "and p.id_branch=".$id_branch."" :''))."
        	 
			     ORDER BY p.date_payment asc,s.code asc".($limit!=NULL? " LIMIT ".$limit." OFFSET ".$limit : " ");
   //var_dump($acc);
		//print_r($sql);exit;
		return $this->db->query($sql)->result_array();	
		

		
	}
	
// paymode_wise_list chked&updtd emp login branchwise  data show//HH
	function get_modewise_list($from_date,$to_date,$type="",$limit="",$id="")
	{  		
	
	$branch_settings=$this->session->userdata('branch_settings');
			$branch=$this->session->userdata('id_branch');
			$uid=$this->session->userdata('uid');

		if($this->branch_settings==1){
			$id_branch  = $this->input->post('id_branch');}
		else{
		$id_branch = '';}
		$date_type  = $this->input->post('date_type');
		$sql="SELECT @a:=@a+1 as sno,sum(p.payment_amount) as payment_amount,
			  s.gst_type, s.gst,compy.gst_number,cs.gst_setting,			  
			  IF(s.gst_type=0,(p.payment_amount-(p.payment_amount*(100/(100+s.gst))))/2,((p.payment_amount*(s.gst/100))/2)) as sgst,					
			  IF(s.gst_type=0,(p.payment_amount-(p.payment_amount*(100/(100+s.gst))))/2,((p.payment_amount*(s.gst/100))/2)) as cgst,			  
			  if(p.payment_mode='FP','Free payment',pm.mode_name)as mode_name 
			  FROM payment p 
			  join chit_settings cs
			  join company compy
			  left join scheme_account sa on(p.id_scheme_account=sa.id_scheme_account)
			  Left Join employee e On (e.id_employee=p.id_employee)
			  Left Join branch b On (b.id_branch=p.id_branch)
			  Left Join customer c on (sa.id_customer=c.id_customer)
			  left join scheme s on(sa.id_scheme=s.id_scheme)
			  Left Join payment_mode pm on (p.payment_mode=pm.short_code)
			  Left Join payment_status_message psm On (p.payment_status=psm.id_status_msg),
			  (select @a:=0) as a
				Where (date(".($date_type!='' ? ($date_type==2 ?"p.custom_entry_date":"p.date_payment") : "p.date_payment").") BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')
				".($id!=''?"  And s.id_scheme=".$id:'')." 
				".($type!=''?"  And p.payment_type=".$type:'')." 				
					And p.payment_status=1   ".($uid!=1 ? ($branch_settings==1 ? ($id_branch!=0 && $id_branch!='' ? "and p.id_branch=".$id_branch."" : ($branch!='' ? "and (p.id_branch=".$branch." or b.show_to_all=2)":'')):'') : ($id_branch!=0 && $id_branch!=''? "and p.id_branch=".$id_branch."" :''))."
			     group by pm.mode_name ORDER BY p.date_payment DESC".($limit!=NULL? " LIMIT ".$limit." OFFSET ".$limit : " ");
		//	print_r($sql);exit;
		$payment=$this->db->query($sql);	
		
	return $payment->result_array();
		
	}
// payment_datewise_schemedata chked&updtd emp login branchwise  data show//HH
function payment_datewise_list($date)
	{  
			if($this->branch_settings==1){
			$id_branch  = $this->input->post('id_branch');}
		else{
		$id_branch = '';}	
		$branch_settings=$this->session->userdata('branch_settings');
			$branch=$this->session->userdata('id_branch');
			$uid=$this->session->userdata('uid');
			
		$id_employee  = $this->input->post('id_employee');
		$date_type  = $this->input->post('date_type');
		$added_by  = $this->input->post('added_by');
		
				$sql_1="select  s.code,
					sum(p.payment_amount) as payment_amount,s.gst_type, s.gst,IFNULL(Date_format(p.date_payment,'%d-%m%-%Y'),'-') as date_payment,					
					COUNT(CASE WHEN  (p.receipt_no is not null  || p.receipt_no is null ) and p.payment_status=1 THEN 1 END) as receipt,
					compy.gst_number,cs.gst_setting,
					if(p.payment_mode='FP','FP',p.payment_mode)as payment_mode,
					if(p.payment_mode='CC' || p.payment_mode='DC','Card',p.payment_mode)as payment_mode,					
					IF(s.gst_type=0,(p.payment_amount-(p.payment_amount*(100/(100+s.gst))))/2,((p.payment_amount*(s.gst/100))/2)) as sgst,					
					IF(s.gst_type=0,(p.payment_amount-(p.payment_amount*(100/(100+s.gst))))/2,((p.payment_amount*(s.gst/100))/2)) as cgst					
					FROM sch_classify sc
					 join company compy
					 join chit_settings cs
					LEFT JOIN scheme s ON (sc.id_classification = s.id_classification)
					  LEFT JOIN scheme_account sa ON (s.id_scheme = sa.id_scheme)
					  LEFT JOIN payment p ON (sa.id_scheme_account = p.id_scheme_account)
					  Left Join branch b On (b.id_branch=p.id_branch)
					  LEFT JOIN postdate_payment pp ON (sa.id_scheme_account = pp.id_scheme_account)
						WHERE sc.active=1 AND (p.payment_status=1 or pp.payment_status=1)
							and date(".($date_type!='' ? ($date_type==2 ?"p.custom_entry_date":"p.date_payment") : "p.date_payment").")='$date' ".($id_employee!=NULL?' and p.id_employee ='.$id_employee:'')." 
							".($added_by!='' ? ($added_by==0 ? " and p.added_by=".$added_by."": " and(p.added_by=1 or p.added_by=2)") :'')." 
						".($uid!=1 ? ($branch_settings==1 ? ($id_branch!=0 && $id_branch!='' ? "and p.id_branch=".$id_branch."" : ($branch!='' ? "and (p.id_branch=".$branch." or b.show_to_all=2)":'')):'') : ($id_branch!=0 && $id_branch!=''? "and p.id_branch=".$id_branch."" :''))."
						GROUP BY s.code,p.payment_mode";
					//	print_r($sql_1);exit;
	  	$payments = $this->db->query($sql_1)->result_array();
	  	return $payments;
	}
//paydatewise_schcoll_data chked &updtd emp login branchwise  data show//HH
	
	function paydatewise_schemecoll($date)
	{		
		$branch_settings=$this->session->userdata('branch_settings');
			$branch=$this->session->userdata('id_branch');
			$uid=$this->session->userdata('uid');
	    
		if($this->branch_settings==1){
			$id_branch  = $this->input->post('id_branch');}
		else{
		$id_branch = '';}	
	
	
		$sql_1="select  
				s.scheme_name,sg.group_code,s.gst_type, s.gst,compy.gst_number,s.is_lucky_draw as is_lucky_draw,cs.has_lucky_draw as has_lucky_draw,
				
				 COUNT(CASE WHEN  Date_format(p.date_payment,'%Y-%m-%d') ='$date' and p.payment_mode ='FP' and p.payment_status=1 THEN 1 END) as incentive,
				  COUNT(CASE WHEN  Date_format(p.date_payment,'%Y-%m-%d') ='$date' and p.payment_status=4 THEN 1 END) as cancel_payment,
				 COUNT(CASE WHEN  Date_format(p.date_payment,'%Y-%m-%d') ='$date'  and p.payment_status=1 THEN 1 END) as paid,
				 IFNULL(sum(CASE WHEN  Date_format(p.date_payment,'%Y-%m-%d') ='$date' 
				  and p.payment_status=1 THEN p.payment_amount END),0) as collection,				  
				  IFNULL((CASE WHEN  Date_format(p.date_payment,'%Y-%m-%d') ='$date' 
				  and sa.is_closed=1 THEN sa.closing_balance END),0) as closing_balance,				  
				 IFNULL(sum(CASE WHEN  Date_format(p.date_payment,'%Y-%m-%d') ='$date' and p.payment_mode ='FP'
				 and p.payment_status=1 THEN p.payment_amount END) ,0)as incentive_amt ,
				 IFNULL(SUM(CASE WHEN Date_format(p.date_payment,'%Y-%m-%d') ='$date' and p.payment_status=1 THEN p.add_charges ELSE 0 END+CASE WHEN Date_format(p.date_payment,'%Y-%m-%d') ='$date' and pp.payment_status=1 THEN pp.charges ELSE 0 END),0) as charge,cs.gst_setting,
					(select sum(pay.payment_amount) from payment pay
LEFT JOIN scheme_account sca ON (sca.id_scheme_account = pay.id_scheme_account)
LEFT JOIN scheme sh ON (sca.id_scheme = sh.id_scheme)
where s.id_scheme = sh.id_scheme and Date_format(pay.date_payment,'%Y-%m-%d')<= DATE_SUB('$date', INTERVAL 1 DAY)
and pay.payment_status=1 group by s.id_scheme)  as opening_bal
					from payment p
					join company compy
					 join chit_settings cs
					LEFT JOIN scheme_account sa ON (sa.id_scheme_account = p.id_scheme_account)
					Left Join scheme_group sg On (sa.group_code = sg.group_code )
					LEFT JOIN postdate_payment pp ON (p.id_scheme_account = pp.id_scheme_account) and pp.payment_status=1
					LEFT JOIN scheme s ON (sa.id_scheme = s.id_scheme)
					Left Join branch b On (b.id_branch=p.id_branch)
					LEFT JOIN sch_classify sc ON (sc.id_classification = s.id_classification)
					WHERE p.id_scheme_account IS NOT NULL AND Date_format(p.date_payment,'%Y-%m-%d')<='$date' and (p.payment_status=1 or p.payment_status=4)  
					".($uid!=1 ? ($branch_settings==1 ? ($id_branch!=0 && $id_branch!='' ? "and p.id_branch=".$id_branch."" : ($branch!='' ? "and (p.id_branch=".$branch." or b.show_to_all=2)":'')):'') : ($id_branch!=0 && $id_branch!=''? "and p.id_branch=".$id_branch."" :''))."
					group by s.id_scheme";	
					
				//	print_r($sql_1);exit;
					
				   $payments = $this->db->query($sql_1)->result_array();
		
		
	  	return $payments;
	}
	// payment outstanding chked &updtd emp login branchwise  data show//HH 
	
	
	function payment_outlist($date)
	{		
	    
	    	$branch_settings=$this->session->userdata('branch_settings');
			$branch=$this->session->userdata('id_branch');
			$uid=$this->session->userdata('uid');
	    
		if($this->branch_settings==1){
			$id_branch  = $this->input->post('id_branch');}
		else{
		$id_branch = '';}
	    
		$sql_1="SELECT s.id_scheme,s.code,s.total_installments, IFNULL(sa.scheme_acc_number,'Not Allocated') as scheme_acc_number, IF(c.lastname is null,c.firstname,concat(c.firstname,'',c.lastname))as name,compy.gst_number,
					count(d.next_due) as due_count,cs.has_lucky_draw,  s.is_lucky_draw,IFNULL(sg.group_code,'') as group_code,
					IFNULL(Date_format(sa.start_date,'%d-%m%-%Y'),'-')  as joined_date,c.mobile,
					IFNULL(IF(sa.is_opening=1,IFNULL(sa.paid_installments,0)+ IFNULL(if(s.scheme_type = 1 and s.min_weight != s.max_weight ,
					COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues)),0), if(s.scheme_type = 1 and s.min_weight != s.max_weight or scheme_type=3 ,
					COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues))) ,0)
					 as paid_installments,cs.gst_setting,
					 
					 if(payment_type=1,p.metal_weight,s.amount) as amount,
					CASE WHEN sa.is_opening='1' AND p.date_add is null
					THEN Date_add(sa.last_paid_date,Interval 1 month) when p.date_add is null and sa.is_opening='0' then
					sa.date_add ELSE Date_add(max(p.date_add),Interval 1 month) END AS next_due,
					IFNULL(IF(sa.is_opening=1,IFNULL(balance_amount,0)+
					IFNULL(SUM(p.payment_amount * p.no_of_dues),0),IFNULL(SUM(p.payment_amount * p.no_of_dues),0)) ,0)as total_paid_amount,
					IFNULL(IF(sa.is_opening=1,IFNULL(balance_weight,0)+IFNULL(SUM(p.metal_weight),0),IFNULL(SUM(p.metal_weight),0)),0.000)as total_paid_weight,
					IFNULL(Date_Format(max(p.date_add),'%d-%m-%Y'),IFNULL(IF(sa.is_opening=1,Date_Format(sa.last_paid_date,'%d-%m-%Y'),'')  ,0))as last_paid_date
					FROM scheme s
					join company compy
					join chit_settings cs					
					left join scheme_account sa on(s.id_scheme=sa.id_scheme)
					Left Join scheme_group sg On (sa.group_code = sg.group_code )
					left join customer c on(sa.id_customer=c.id_customer)
					left join payment p on (sa.id_scheme_account=p.id_scheme_account)
					Left Join (Select sa.id_scheme_account, CASE WHEN sa.is_opening='1' AND p.date_payment is null
					THEN Date_add(sa.last_paid_date,Interval 1 month) when p.date_payment is null and sa.is_opening='0' then
					sa.date_add ELSE Date_add(max(p.date_payment),Interval 1 month) END AS next_due
					From scheme_account sa
					Left Join payment p on(p.id_scheme_account=sa.id_scheme_account and sa.active=1 and
					sa.is_closed=0 and p.payment_status='1') Group By sa.id_scheme_account)d on(d.id_scheme_account=sa.id_scheme_account)
					where s.one_time_premium=0 and Date_format(d.next_due,'%Y-%m-%d')='$date'
					
						".($uid!=1 ? ($branch_settings==1 ? ($id_branch!=0 && $id_branch!='' ? "and p.id_branch=".$id_branch."" : ($branch!='' ? "and (p.id_branch=".$branch.")":'')):'') : ($id_branch!=0 && $id_branch!=''? "and p.id_branch=".$id_branch."" :''))."
					Group By sa.id_scheme_account";
					//print_r($sql_1);exit;
				   $payments = $this->db->query($sql_1)->result_array();
	  	return $payments;
	}
	
//end of new reports	
	
 
 
 public function get_gstSplitupData($id,$date_add)
	{
		//NOTE : type with NULL value is GST
	   $sql="SELECT splitup_name,percentage,type FROM gst_splitup_detail WHERE status=1 and type is not null and `id_scheme` =".$id;
		$data=	$this->db->query($sql);
		return $data->result_array();
	}
	
	public function get_schgst($id)
    {
		$sql = "SELECT s.gst,s.gst_type,s.amount,s.max_weight,s.scheme_type,s.flexible_sch_type,cs.metal_wgt_decimal,cs.metal_wgt_roundoff from scheme s
				 LEFT JOIN scheme_account sa on sa.id_scheme = s.id_scheme	
				 join chit_settings cs
				where sa.id_scheme_account =".$id;
		return $this->db->query($sql)->row_array();		
	}
	function avg_applicable($id,$data)
	{
		$this->db->where('id_scheme_account',$id);
		$status = $this->db->update(self::ACC_TABLE,$data);
		return	$status;
	}
	
	function get_scheme_list()
	{
		$sql="SELECT s.id_scheme, s.scheme_name, s.code 
		FROM scheme s where s.visible=1 and s.active=1";
		return $this->db->query($sql)->result_array();	
	}
	//emp reff_report
 
 
 
	
	
	/* function get_empreff_report()
	{
		
	  $sql="select e.id_employee,CONCAT('EMP','-',e.id_employee) as emp_code,
		concat(e.firstname,'',e.lastname)as name,
		count(fp.ref_code)as refferal_count,fp.is_refferal_by,fp.id_scheme,
		sum(fp.emp_refferal_value) as benifits from employee e
		LEFT JOIN (select s.id_scheme,if(s.emp_refferal=1,if(ws.type=0,ws.value,((s.amount*ws.value)/100)),'') as emp_refferal_value,sa.referal_code as ref_code,p.id_scheme_account,sa.is_refferal_by,
		count(id_payment) from payment p left join scheme_account sa on sa.id_scheme_account =p.id_scheme_account
		left join scheme s on sa.id_scheme =s.id_scheme 
		join wallet_settings ws
		where payment_status =1 and sa.is_refferal_by=1 and s.emp_refferal=1 and sa.is_closed=0 and ws.active=1 and ws.id_wallet=2
		group by sa.id_scheme_account) fp on fp.ref_code=CONCAT('EMP','-',e.id_employee)		
		where fp.is_refferal_by=1  group by id_employee"; 
			//echo $sql;exit;
	  $payments=$this->db->query($sql);
	  return $payments->result_array();
	
	} */
	
	function get_empreff_report()
	{
		
	    $sql="select e.id_employee,e.emp_code as emp_code,e.mobile,
         concat(e.firstname,'',e.lastname)as name,
         count(fp.ref_code)as refferal_count,
        sum(fp.payment_amount) as total_amount ,
        sum(fp.referal_value) as benifits
        from employee e
        LEFT JOIN (select p.payment_amount,s.id_scheme,FORMAT(if(chit.empplan_type=1,if(ws.type=0,ws.value,((s.amount*ws.value)/100)),
        if(s.emp_refferal=1 && chit.empplan_type=0,s.emp_refferal_value,'')),0) as referal_value,sa.referal_code as ref_code,p.id_scheme_account,sa.is_refferal_by,
        count(id_payment) from payment p
        left join scheme_account sa on sa.id_scheme_account =p.id_scheme_account
        left join scheme s on sa.id_scheme =s.id_scheme
        join wallet_settings ws
        join chit_settings chit
        where payment_status =1 and sa.is_refferal_by=1 and s.emp_refferal=1 and sa.is_closed=0 and ws.active=1 and ws.id_wallet=2
        group by sa.id_scheme_account) fp on fp.ref_code=e.emp_code
        where fp.is_refferal_by=1  group by id_employee"; 		
	   $payments=$this->db->query($sql);
	 // print_r($this->db->last_query());exit;
	  return $payments->result_array();
	
	
	}
	
	
	 function empreferral_account($emp_code,$from,$to)	
	{
		
	  /*$sql="select e.mobile, IFNULL(Date_format(p.date_payment,'%d-%m%-%Y'),'-') as date_payment,if(sa.scheme_acc_number is null ,s.scheme_name,concat(s.scheme_name,'-',sa.scheme_acc_number))as scheme_acc_number,c.id_customer,if(c.lastname is null ,c.firstname,concat(c.firstname,'',c.lastname))as cus_name from customer c
		left join scheme_account sa on(sa.id_customer=c.id_customer)
		left join payment p on (sa.id_scheme_account=p.id_scheme_account)
		left join scheme s on (s.id_scheme=sa.id_scheme)
		left join employee e on(e.mobile=sa.referal_code) where e.mobile='$id' and payment_status=1 and sa.is_refferal_by=1 group by sa.id_scheme_account";*/
		
		if($from != ''){
	        $sql="SELECT s.amount as payment_amount,s.code,IFNULL(Date_format(wt.date_transaction,'%d-%m%-%Y'),'-') as date_payment,e.mobile,e.emp_code,e.id_employee,
                    if(c.lastname is null ,c.firstname,concat(c.firstname,'',c.lastname))as cus_name,sa.id_scheme_account,c.id_customer,
                    sa.group_code,if(sa.scheme_acc_number is null ,s.scheme_name,concat(s.scheme_name,'-',sa.scheme_acc_number))as scheme_acc_number,
                    sum(case when wt.transaction_type=0 then wt.value end) as benifits,
                    count(case when wt.transaction_type=0 then wt.value else 0 end) as refferal_count,
                    sum(s.amount) as total_amount
                FROM wallet_account wa
                    left join employee e on (wa.idemployee=e.id_employee)
                    left join wallet_transaction wt on(wa.id_wallet_account=wt.id_wallet_account)
                    left join scheme_account sa on sa.id_scheme_account=wt.id_sch_ac
                    left join scheme  s on s.id_scheme =sa.id_scheme
                    left join customer c on c.id_customer=sa.id_customer
                where (date(wt.date_transaction) BETWEEN '".$from."' AND '".$to."') and
                e.emp_code=".$emp_code." and sa.is_refferal_by=1 group by sa.id_scheme_account"; 
	    }else{
	         $sql="SELECT s.amount as payment_amount,s.code,IFNULL(Date_format(wt.date_transaction,'%d-%m%-%Y'),'-') as date_payment,e.mobile,e.emp_code,e.id_employee,
                    if(c.lastname is null ,c.firstname,concat(c.firstname,'',c.lastname))as cus_name,sa.id_scheme_account,c.id_customer,
                    sa.group_code,if(sa.scheme_acc_number is null ,s.scheme_name,concat(s.scheme_name,'-',sa.scheme_acc_number))as scheme_acc_number,
                    sum(case when wt.transaction_type=0 then wt.value end) as benifits,
                    count(case when wt.transaction_type=0 then wt.value else 0 end) as refferal_count,
                    sum(s.amount) as total_amount
                FROM wallet_account wa
                    left join employee e on (wa.idemployee=e.id_employee)
                    left join wallet_transaction wt on(wa.id_wallet_account=wt.id_wallet_account)
                    left join scheme_account sa on sa.id_scheme_account=wt.id_sch_ac
                    left join scheme  s on s.id_scheme =sa.id_scheme
                    left join customer c on c.id_customer=sa.id_customer
                where e.emp_code=".$emp_code." and sa.is_refferal_by=1 group by sa.id_scheme_account"; 
	    }
		//	echo $sql;exit;
	  $payments=$this->db->query($sql);
	
	  return $payments->result_array();
	
	}
	
   function get_empreff_report_by_range($from_date,$to_date)
	{
		
		
	 /* $sql="SELECT e.id_employee,if(e.lastname is not null ,concat(e.firstname,'',e.lastname),e.firstname)as name, 
	        e.emp_code,e.mobile,sum(case when wt.transaction_type=0 then wt.value end) as benifits,
		    count(case when wt.transaction_type=0 then wt.value else 0 end) as refferal_count
		    FROM wallet_account wa
			left join employee e on (wa.idemployee=e.id_employee)
			left join wallet_transaction wt on(wa.id_wallet_account=wt.id_wallet_account)			
		    where ( date(wt.date_transaction) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') and wa.active=1 and wa.idemployee is not null and wt.transaction_type=0  group by e.id_employee";*/
		$sql = "SELECT e.id_employee,if(e.lastname is not null ,concat(e.firstname,' ',e.lastname),e.firstname)as name, e.emp_code,e.mobile,sa.id_scheme_account, 
                sum(case when wt.transaction_type=0 then wt.value end) as benifits, 
                count(case when wt.transaction_type=0 then wt.value else 0 end) as refferal_count,
                sum(s.amount) as total_amount
                FROM wallet_account wa 
                left join employee e on (wa.idemployee=e.id_employee) 
                left join wallet_transaction wt on(wa.id_wallet_account=wt.id_wallet_account) 
                left join scheme_account sa on sa.id_scheme_account=wt.id_sch_ac 
                left join scheme  s on s.id_scheme =sa.id_scheme
                where ( date(wt.date_transaction) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') and wa.active=1 and wa.idemployee is not null and wt.transaction_type=0  group by e.id_employee";
	  $payments=$this->db->query($sql);			 
	 // print_r($this->db->last_query());exit;  
	  return $payments->result_array();  
	
	}
	
	
	 
		
		
	function get_cus_ref_success()
	{
		
	    $sql="select c.id_customer,c.mobile as cus_referalcode,if(c.lastname is null,c.firstname,concat(c.firstname,'',c.lastname)) as name, count(fp.ref_code)as refferal_count,fp.is_refferal_by,fp.id_scheme, sum(fp.referal_value) as benifits,c.mobile from customer c LEFT JOIN (select s.id_scheme,FORMAT(if(chit.cusplan_type=1,if(ws.type=0,ws.value,((s.amount*ws.value)/100)),
		if(s.cus_refferal=1 && chit.cusplan_type=0,s.cus_refferal_value,'')),0) as referal_value,sa.referal_code as ref_code,p.id_scheme_account,sa.is_refferal_by, count(id_payment) 
			from payment p
			join wallet_settings ws
			join chit_settings chit
		    left join scheme_account sa on sa.id_scheme_account =p.id_scheme_account 
		    left join scheme s on sa.id_scheme =s.id_scheme 
			where payment_status =1 and sa.is_refferal_by=0  and ws.id_wallet=1 group by sa.id_scheme_account) fp on fp.ref_code=c.mobile 			
			where fp.is_refferal_by=0  group by c.id_customer";
		  $payments=$this->db->query($sql);
		  return $payments->result_array();
			
		} 
		
	function cus_refferl_account($mobile)	
	{
	  $sql="select IFNULL(Date_format(p.date_payment,'%d-%m%-%Y'),'-') as date_payment,s.scheme_name as scheme_name,sa.id_customer,
			sa.referal_code,if(c.lastname is null ,c.firstname,concat(c.firstname,'',c.lastname))as name,c.firstname
			from scheme_account sa
			left join payment p on (sa.id_scheme_account=p.id_scheme_account)
			left join scheme s on (s.id_scheme=sa.id_scheme)
			left join customer c on(c.id_customer=sa.id_customer and  c.id_customer=sa.id_customer)
			where sa.referal_code=".$mobile." and p.payment_status=1 and sa.is_refferal_by=0 and s.cus_refferal=1  group by sa.id_scheme_account"; 
			//print_r($sql);exit;
	  $payments=$this->db->query($sql); 
	  return $payments->result_array();
	} 	
	
   
	
   /* function cus_refferl_account($id)	
	{
	  $sql="select IFNULL(Date_format(p.date_payment,'%d-%m%-%Y'),'-') as date_payment,s.scheme_name as scheme_name,sa.id_customer,
			sa.referal_code,if(c.lastname is null ,c.firstname,concat(c.firstname,'',c.lastname))as name,c.firstname
			from scheme_account sa
			left join payment p on (sa.id_scheme_account=p.id_scheme_account)
			left join scheme s on (s.id_scheme=sa.id_scheme)
			left join customer c on(c.id_customer=sa.id_customer and  c.id_customer=sa.id_customer)
			where sa.referal_code=".$id." and p.payment_status=1 and sa.is_refferal_by=0 and s.cus_refferal=1  group by sa.id_scheme_account"; 
			//print_r($sql);exit;
	  $payments=$this->db->query($sql); 
	  return $payments->result_array();
	} */
	
	function get_cusreff_report_by_range($from_date,$to_date)
	{
		
	  $sql=" select c.id_customer,IFNULL(c.mobile,'') as cus_referalcode,
		if(c.lastname is null ,c.firstname,concat(c.firstname,'',c.lastname))as name,
		count(fp.ref_code)as refferal_count,fp.is_refferal_by,fp.id_scheme,
		sum(fp.referal_value) as benifits from customer c
		LEFT JOIN (select s.id_scheme,FORMAT(if(chit.cusplan_type=1,if(ws.type=0,ws.value,((s.amount*ws.value)/100)),
		if(s.emp_refferal=1 && chit.cusplan_type=0,s.emp_refferal_value,'')),0) as referal_value,sa.referal_code as ref_code,p.id_scheme_account,sa.is_refferal_by,
		count(id_payment) from payment p 
		join chit_settings chit
		join wallet_settings ws
		left join scheme_account sa on sa.id_scheme_account =p.id_scheme_account
		left join scheme s on sa.id_scheme =s.id_scheme 
		where payment_status =1 and sa.is_refferal_by=0 and cus_refferal=1 and sa.is_closed=0 and
		 ( date(sa.start_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')
		group by sa.id_scheme_account) fp on fp.ref_code=c.mobile		
		where fp.is_refferal_by=0 group by id_customer"; 	
		  //print_r($sql);exit;
		  $payments=$this->db->query($sql);
		  return $payments->result_array();
			
		}
	
	
	
	
	function get_gstsettings()
	{
		$sql="Select c.gst_setting FROM chit_settings c where c.id_chit_settings = 1";
		 return $this->db->query($sql)->row()->gst_setting;
		
	}
	
	
	function get_rptnosettings()
	{
		$sql="Select c.receipt_no_set FROM chit_settings c where c.id_chit_settings = 1";
		return $this->db->query($sql)->row()->receipt_no_set;
		
	}
	
	 
	 /* function get_referrals_datas($id_scheme_account){
		 
		 $sql=("SELECT sa.id_scheme_account, sa.id_scheme,
					s.code,if(cus.lastname is null,cus.firstname,concat(cus.firstname,'',cus.lastname)) as cusname, s.scheme_name,if(s.cus_refferal=1,if(ws.type=0,ws.value,((s.amount*ws.value)/100)),'') as referal_value,
					sa.is_refferal_by,sa.referal_code,s.cus_refferal,
					s.emp_refferal,s.emp_refferal_value,s.cus_refferal_value,ref.name,
					ref.mobile,ref.id_customer as id_customer,ref.id_wallet_account					
					FROM scheme_account sa
					left join scheme s on (sa.id_scheme =s.id_scheme)
					left join(SELECT w.id_customer,w.id_wallet_account,if(c.lastname is null,c.firstname,concat(c.firstname,'',c.lastname)) as name,
					c.mobile, c.referal_code
					FROM customer c
					left join wallet_account w on (c.id_customer=w.id_customer and w.active=1)
					) ref on ref.mobile= sa.referal_code					
					join wallet_settings ws
					left join customer cus on (cus.id_customer=sa.id_customer)
					where sa.id_scheme_account=".$id_scheme_account." and ws.active=1 and ws.id_wallet=1");	   
		$result= $this->db->query($sql)->row_array();
		return $result;
	 }  */
	 
	 function get_referrals_datas($id_scheme_account){
		 
		 $sql=("SELECT sa.id_scheme_account, sa.id_scheme,
					s.code,if(cus.lastname is null,cus.firstname,concat(cus.firstname,'',cus.lastname)) as cusname, s.scheme_name,if(s.cus_refferal=1,s.cus_refferal_value,'') as referal_value,
					sa.is_refferal_by,sa.referal_code,s.cus_refferal,
					s.emp_refferal,s.emp_refferal_value,s.cus_refferal_value,ref.name,
					ref.mobile,ref.id_customer as id_customer,ref.id_wallet_account					
					FROM scheme_account sa
					left join scheme s on (sa.id_scheme =s.id_scheme)
					left join(SELECT w.id_customer,w.id_wallet_account,if(c.lastname is null,c.firstname,concat(c.firstname,'',c.lastname)) as name,
					c.mobile, c.referal_code
					FROM customer c
					left join wallet_account w on (c.id_customer=w.id_customer and w.active=1)
					) ref on ref.mobile= sa.referal_code					
					join wallet_settings ws
					left join customer cus on (cus.id_customer=sa.id_customer)
					where sa.id_scheme_account=".$id_scheme_account." ");	   
		$result= $this->db->query($sql)->row_array();
		return $result;
	 } 
	 
	 function get_refdata($id_scheme_account){
		 
		 $sql=("SELECT sa.referal_code,sa.id_scheme_account,s.ref_benifitadd_ins,
					IFNULL(IF(sa.is_opening=1,IFNULL(sa.paid_installments,0)+ IFNULL(if(s.scheme_type = 1 and s.min_weight != s.max_weight ,
					COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues)),0),
					if(s.scheme_type = 1 and s.min_weight != s.max_weight or s.scheme_type=3 , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues))) ,0)
					as paid_installments,s.ref_benifitadd_ins_type
					     FROM scheme_account sa
					    left join scheme s on (sa.id_scheme=s.id_scheme)
					    left join payment p on (sa.id_scheme_account=p.id_scheme_account) 
						where sa.id_scheme_account=".$id_scheme_account." and  p.payment_status=1 group by sa.id_scheme_account");
			$result=$this->db->query($sql)->row_array();		
			return $result;
	 }
	 
	 
    function get_schemeacountID($id_payment){
	
	   $sql="Select p.id_scheme_account FROM payment p where p.id_payment=".$id_payment."";
		return $this->db->query($sql)->row()->id_scheme_account;
	
	
        }
		
		
		function get_referral_code($id_scheme_account){
	
	     $sql="SELECT s.is_refferal_by, s.referal_code,s.id_customer FROM scheme_account s where s.id_scheme_account=".$id_scheme_account."";
		return $this->db->query($sql)->row_array();	
        }
	
	public function get_settings()
	 {
	     $sql="select * from chit_settings";
	     $result=$this->db->query($sql);
	     return $result->row_array();
	 }
	
 function get_empreferrals_datas($id_scheme_account){
     
     	$data=$this->get_settings();
		 
		 $sql=("SELECT sa.id_scheme_account, sa.id_scheme,
				s.code,if(cus.lastname is null,cus.firstname,concat(cus.firstname,'',cus.lastname)) as cusname, s.scheme_name,
				FORMAT(if(chit.empplan_type=1,if(ws.type=0,ws.value,((s.amount*ws.value)/100)),
				if(s.emp_refferal=1 && chit.empplan_type=0,s.emp_refferal_value,'')),0) as referal_value,
				sa.is_refferal_by,sa.referal_code,chit.empplan_type,
				chit.empbenefitscrt_type,chit.schrefbenifit_secadd,
				s.emp_refferal,s.emp_refferal_value,ref.name,
				ref.mobile,ref.emp_code,ref.idemployee as idemployee,ref.id_wallet_account					
				FROM scheme_account sa
				left join scheme s on (sa.id_scheme =s.id_scheme)
				left join(SELECT w.id_employee,w.id_wallet_account,if(emp.lastname is null,emp.firstname,concat(emp.firstname,'',emp.lastname)) as name,w.idemployee,
				emp.mobile,emp.emp_code
				FROM employee emp
				left join wallet_account w on (emp.id_employee=w.idemployee)
				where w.active=1
				) ref on ".($data['emp_ref_by']==1 ? " ref.mobile= sa.referal_code" : "ref.emp_code= sa.referal_code")." 					
				join wallet_settings ws
				join chit_settings chit
				left join customer cus on (cus.id_customer=sa.id_customer)
				where sa.id_scheme_account=".$id_scheme_account." and ws.active=1 and ws.id_wallet=2");	
		$result= $this->db->query($sql)->row_array();
		return $result;
	 }
	 
	 
	 
	 function get_cusreferrals_datas($id_scheme_account){
		 
		 $sql=("SELECT sa.id_scheme_account, sa.id_scheme,
					s.code,if(cus.lastname is null,cus.firstname,concat(cus.firstname,'',cus.lastname)) as cusname, s.scheme_name,FORMAT(if(chit.cusplan_type=1,if(ws.type=0,ws.value,((s.amount*ws.value)/100)),
				    if(s.cus_refferal=1 && chit.cusplan_type=0,s.cus_refferal_value,'')),0) as referal_value,
					sa.is_refferal_by,sa.referal_code,chit.cusplan_type,chit.schrefbenifit_secadd,
		            chit.empbenefitscrt_type,chit.cusbenefitscrt_type,
					s.cus_refferal,s.cus_refferal_value,ref.name,
					ref.mobile,ref.id_customer as id_customer,ref.id_wallet_account					
					FROM scheme_account sa
					left join scheme s on (sa.id_scheme =s.id_scheme)
					left join(SELECT w.id_customer,w.id_wallet_account,if(c.lastname is null,c.firstname,concat(c.firstname,'',c.lastname)) as name,
					c.mobile, c.cus_ref_code
					FROM customer c
					left join wallet_account w on (c.id_customer=w.id_customer and w.active=1)
					) ref on ref.mobile= sa.referal_code					
					join wallet_settings ws
					join chit_settings chit
					left join customer cus on (cus.id_customer=sa.id_customer)
					where sa.id_scheme_account=".$id_scheme_account." and ws.active=1 and ws.id_wallet=1");	   
		$result= $this->db->query($sql)->row_array();
		return $result;
	 }
	 
	 
	function get_ischkrefamtadd($id_scheme_account){
			
	     $record=$this->db->query("SELECT sa.id_scheme,sa.id_customer,sa.is_refferal_by,chit.schrefbenifit_secadd,
		 chit.empbenefitscrt_type,chit.cusbenefitscrt_type,sa.referal_code 
		 FROM scheme_account sa
 		 join chit_settings chit
		 where sa.id_scheme_account=".$id_scheme_account."");	 
		 if($record->num_rows()>0)
		 { 
		   if($record->row()->is_refferal_by != null){
		       if($record->row()->is_refferal_by==0 && $record->row()->cusbenefitscrt_type==0 && ($record->row()->schrefbenifit_secadd==0 || 
				$record->row()->schrefbenifit_secadd==1)){
		        $status = $this->get_chkrefschcus_joincount($record->row()->id_customer);
				   if($status){					  
				       return true;}
    			}
    			else if($record->row()->is_refferal_by==0 && $record->row()->cusbenefitscrt_type==1 && 
    			$record->row()->schrefbenifit_secadd==1){				
    				$status = $this->get_chkrefschrefcode_joincount($record->row()->id_customer,$record->row()->id_scheme,$record->row()->referal_code);
    				   if($status){					
    						return true;}
    			}else if($record->row()->is_refferal_by==0 && $record->row()->cusbenefitscrt_type==1 && 
    			$record->row()->schrefbenifit_secadd==0){				
    				return true;
    			}
    			else if($record->row()->is_refferal_by==1 && $record->row()->empbenefitscrt_type==0 && ($record->row()->schrefbenifit_secadd==0 || 
    				$record->row()->schrefbenifit_secadd==1)){
    		        $status = $this->get_chkrefschcus_joincount($record->row()->id_customer);
    				   if($status){					  
    				       return true;}
    			}
    			else if($record->row()->is_refferal_by==1 && $record->row()->empbenefitscrt_type==1 && 
    			$record->row()->schrefbenifit_secadd==1){				
    				$status = $this->get_chkrefschrefcode_joincount($record->row()->id_customer,$record->row()->id_scheme,$record->row()->referal_code);
    				   if($status){					
    						return true;}
    			}else if($record->row()->is_refferal_by==1 && $record->row()->empbenefitscrt_type==1 && 
    			$record->row()->schrefbenifit_secadd==0){				
    				return true;
    			}
		   } 
			 return false;
		  }
		  
	 }
	 
	function get_chkrefschcus_joincount($id_customer)
	{	
      $sql="SELECT sa.id_scheme_account,count(p.id_payment)as payment,sa.id_scheme,
	         sa.is_refferal_by,c.is_refbenefit_crt_cus,c.is_refbenefit_crt_emp
	        FROM  scheme_account sa			
				left join scheme s on(s.id_scheme=sa.id_scheme)
				left join customer c on(sa.id_customer=c.id_customer)
				Left Join payment p On (sa.id_scheme_account=p.id_scheme_account and p.payment_status=1)
			where sa.id_customer=".$id_customer."";
		$records = $this->db->query($sql);				
		if($records->num_rows()>0){
			if($records->row()->is_refferal_by==0 && $records->row()->payment>=0 && $records->row()->is_refbenefit_crt_cus==1){
				return true;					
			  }else if($records->row()->is_refferal_by==1 && $records->row()->payment>=0 && $records->row()->is_refbenefit_crt_emp==1){
			  return true;}
		}
		 return false;
		
    } 
        
	
	function get_chkrefschrefcode_joincount($id_customer,$id_scheme,$referalcode)
	{	
      $sql="SELECT count(sa.id_scheme_account)as scheme_account,sa.id_scheme,sa.id_customer,
	         sa.is_refferal_by,c.is_refbenefit_crt
	        FROM  scheme_account sa			
				left join scheme s on(s.id_scheme=sa.id_scheme)
				left join customer c on(sa.id_customer=c.id_customer)
				Left Join payment p On (sa.id_scheme_account=p.id_scheme_account and p.payment_status=1)
			where sa.id_customer=".$id_customer." and sa.id_scheme=".$id_scheme." and sa.referal_code=".$referalcode."";
		//	echo $sql;
		$records = $this->db->query($sql);				
		if($records->num_rows()>0){			
			$record=$records->row();
			if($record->is_refferal_by==0 && $record->scheme_account<=1){					
				return true;					
			  }if($record->is_refferal_by==1 && $record->scheme_account<=1){					
				return true;					
			  }
			  return false;
		}
		
    }
	
		
	function get_empreport($id_employee="")
		{
	
	  $records= array();		
	  $sql="select e.id_employee,e.mobile,e.emp_code,
      concat(e.firstname,' ',e.lastname)as name,
      count(fp.ref_code)as refferal_count,fp.is_refferal_by,fp.id_scheme,fp.code,fp.payment_amount,
      sum(fp.referal_value) as benifits from employee e
      LEFT JOIN (select  s.code,p.payment_amount,s.id_scheme,FORMAT(if(chit.empplan_type=1,if(ws.type=0,ws.value,((s.amount*ws.value)/100)),
      if(s.emp_refferal=1 && chit.empplan_type=0,s.emp_refferal_value,'')),0) as referal_value,sa.referal_code as ref_code,p.id_scheme_account,sa.is_refferal_by,
      count(id_payment) from payment p 
      left join scheme_account sa on sa.id_scheme_account =p.id_scheme_account
      left join scheme s on sa.id_scheme =s.id_scheme
      join wallet_settings ws
      join chit_settings chit	
      where payment_status =1 and sa.is_refferal_by=1 and s.emp_refferal=1 and sa.is_closed=0
		group by sa.id_scheme_account) fp on fp.ref_code=e.emp_code where fp.is_refferal_by=1 ".($id_employee!=''?"and e.id_employee=".$id_employee:"")."
		group by id_employee"; 
	 //print_r($sql);exit;
	  $payments=$this->db->query($sql);	  
	  if($payments->num_rows()>0){
		  
		   $record=$payments->row();
		  
		  $records= array(
		  
		  'id_employee'     =>$record->id_employee,
		  'emp_code'        =>$record->emp_code,
		  'name' 	        =>$record->name,
		  'refferal_count'  =>$record->refferal_count,
		  'is_refferal_by'  =>$record->is_refferal_by,
		  'id_scheme'       =>$record->id_scheme,
		  'benifits'        =>$record->benifits,
		  'code'            =>$record->code,
		  'payment_amount'       =>$record->payment_amount,
		  'referral_deatils' =>$this->empreferral_account_details($record->id_employee) );
		  
	  }

	  return $records;
	
	}
	
	
	function empreferral_account_details($id)	
	{
		
	  $sql="select s.code,p.payment_amount, IFNULL(Date_format(p.date_payment,'%d-%m%-%Y'),'-') as date_payment,if(sa.scheme_acc_number is null ,s.scheme_name,concat(s.scheme_name,'-',sa.scheme_acc_number))as scheme_acc_number,c.id_customer,if(c.lastname is null ,c.firstname,concat(c.firstname,'',c.lastname))as cus_name 
	     from customer c
		left join scheme_account sa on(sa.id_customer=c.id_customer)
		left join payment p on (sa.id_scheme_account=p.id_scheme_account)
		left join scheme s on (s.id_scheme=sa.id_scheme)
		left join employee e on(e.emp_code=sa.referal_code) where e.id_employee='$id' and payment_status=1 and sa.is_refferal_by=1 group by sa.id_scheme_account";
	    //print_r($sql);exit;
	    $payments=$this->db->query($sql);	
	    return $payments->result_array();
	
	}
	
	function get_load_account($id_payment,$id_scheme_account)
	{  
	   $schemeAcc = array();
	   
		$sql="Select
					sg.group_code as scheme_group_code, UNIX_TIMESTAMP(Date_Format(sg.start_date,'%Y-%m-%d')) as group_start_date,  UNIX_TIMESTAMP(Date_Format(sg.end_date,'%Y-%m-%d')) as  group_end_date,  cs.has_lucky_draw, s.is_lucky_draw,
					
					s.min_amt_chance,s.max_amt_chance,s.code,s.min_amount,
				   	sa.id_scheme_account,s.gst,s.gst_type,s.max_amount,
					s.id_scheme,s.wgt_convert,if(s.cus_refferal=1 || s.emp_refferal=1,sa.referal_code,'')as referal_code,
					s.ref_benifitadd_ins_type,s.ref_benifitadd_ins,
					c.id_customer,
					CONCAT(s.code,'-',IFNULL(sa.scheme_acc_number,'Not Allocated')) as chit_number,
					IFNULL(sa.account_name,if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname))) as account_name,
					s.scheme_name,
					s.scheme_type,
					if(scheme_type=3,if(s.max_amount!='',s.max_amount * s.total_installments,s.max_weight),s.amount)as scheme_overall_amount,
					IFNULL(s.min_chance,0) as min_chance,
					IFNULL(s.max_chance,0) as max_chance,
					Format(IFNULL(s.max_weight,0),3) as max_weight,
					Format(IFNULL(s.min_weight,0),3) as min_weight,
					Date_Format(sa.start_date,'%d-%m-%Y') as start_date,
					(SELECT m.goldrate_22ct FROM metal_rates m  order by id_metalrates Desc LIMIT 1) as metal_rate,
					
					
  IF(s.scheme_type=0 OR s.scheme_type=2,s.amount,IF(s.scheme_type=1 ,s.max_weight,if(s.scheme_type=3,s.min_amount,0))) as payable,  
					s.total_installments,
IFNULL(IF(sa.is_opening=1,IFNULL(sa.paid_installments,0)+ IFNULL(if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues)),0), if(s.scheme_type = 1 and s.min_weight != s.max_weight or s.scheme_type=3 , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues))) ,0)
  as paid_installments,
  
  
IFNULL(IF(sa.is_opening=1,IFNULL(balance_amount,0)+IFNULL(SUM(p.payment_amount * p.no_of_dues),0),IFNULL(SUM(p.payment_amount * p.no_of_dues),0)) ,0)
  as total_paid_amount,
  
IFNULL(IF(sa.is_opening=1 and s.scheme_type!=0,IFNULL(balance_weight,0)+IFNULL(SUM(p.metal_weight),0),
IFNULL(SUM(p.metal_weight),0)),0.000) 
 as total_paid_weight,
 
  if((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) > s.total_installments, 
(s.total_installments - COUNT(payment_amount)), 
ifnull((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) - SUM(p.no_of_dues),if((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) > s.total_installments,s.total_installments,(PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m')))))) 
  as totalunpaid_1, 
  
  if((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) > s.total_installments,   (s.total_installments - if(sa.is_opening = 1,(COUNT(payment_amount)+sa.paid_installments),COUNT(payment_amount))),ifnull(((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m')))+1) - IF(sa.is_opening=1,IFNULL(sa.paid_installments,0)+ IFNULL(if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues)),0), if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues))),if((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) > s.total_installments,s.total_installments,(PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m')))))) as totalunpaid,   
  
   IFNULL(if(Date_Format(max(p.date_add),'%Y%m') = Date_Format(curdate(),'%Y%m'), (select SUM(ip.no_of_dues) from payment ip where Date_Format(ip.date_add,'%Y%m') = Date_Format(curdate(),'%Y%m') and sa.id_scheme_account = ip.id_scheme_account),IF(sa.is_opening=1, if(Date_Format(sa.last_paid_date,'%Y%m') = Date_Format(curdate(),'%Y%m'), 1,0),0)),0) as currentmonthpaycount, 
  
  (select SUM(pay.no_of_dues) from payment pay where pay.id_scheme_account= sa.id_scheme_account and pay.due_type='AD' and (pay.payment_status=1 or pay.payment_status=2)) as currentmonth_adv_paycount,
  
  (select SUM(pay.no_of_dues) from payment pay where pay.id_scheme_account= sa.id_scheme_account and pay.due_type='PD' and (pay.payment_status=1 or pay.payment_status=2)) as currentmonth_pend_paycount,
				
 
IF(s.scheme_type =1 and s.max_weight !=s.min_weight,true,false) as is_flexible_wgt,p.payment_status,
					if(scheme_type=3,IFNULL(cp.total_amount,0),Format(IFNULL(cp.total_amount,0),2)) as  current_total_amount,
					Format(IFNULL(cp.total_weight,0) + IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),(sa.last_paid_weight),0) ,3) as  current_total_weight,
					IFNULL(cp.paid_installment,0)       as  current_paid_installments,
						IFNULL(cp.chances,0) + IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),(sa.last_paid_chances),0) as  current_chances_used,
						
						
						if(s.scheme_type=3 && s.pay_duration=0 ,IFNULL(sp.chance,0) + IF(Date_Format(Current_Date(),'%d%m')=Date_Format(sa.last_paid_date,'%d%m'),(sa.last_paid_chances),0),IFNULL(cp.chances,0) + IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),(sa.last_paid_chances),0)) as  current_chances_use,
					
				IFNULL(sp.chance,0)as dd,
				
					s.is_pan_required,
					IF(sa.is_opening = 1 and s.scheme_type = 0,
					IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),false,true),
					true) AS previous_amount_eligible,
					count(pp.id_scheme_account) as cur_month_pdc,
					IFNULL(Date_Format(max(p.date_add),'%d-%m-%Y'),IFNULL(IF(sa.is_opening=1,Date_Format(sa.last_paid_date,'%d-%m-%Y'),'')  ,0))                 as last_paid_date,
					IFNULL(PERIOD_DIFF(Date_Format(curdate(),'%Y%m'),Date_Format(max(p.date_add),'%Y%m')),IF(sa.is_opening=1,PERIOD_DIFF(Date_Format(curdate(),'%Y%m'),Date_Format(sa.last_paid_date,'%Y%m')),0)) as last_paid_duration,
				IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),1,0) as  previous_paid,	
					sa.disable_payment,
					
				s.allow_unpaid,
				if(s.allow_unpaid=1,s.unpaid_months,0) as allow_unpaid_months,
				s.allow_advance,
				if(s.allow_advance=1,s.advance_months,0) as advance_months,
					if(s.allow_unpaid=1,s.unpaid_weight_limit,0) as unpaid_weight_limit,
					s.allow_advance,
					if(s.allow_advance=1,s.advance_weight_limit,0) as advance_weight_limit,
					s.allow_preclose,
					if(s.allow_preclose=1,s.preclose_months,0) as preclose_months,
					if(s.allow_preclose=1,s.preclose_benefits,0) as preclose_benefits,cs.currency_symbol
				From scheme_account sa
				Left Join scheme s On (sa.id_scheme=s.id_scheme)
				Left Join payment p On (sa.id_scheme_account=p.id_scheme_account and (p.payment_status=2 or p.payment_status=1))
				Left Join customer c On (sa.id_customer=c.id_customer and c.active=1)
				Left Join scheme_group sg On (sa.group_code = sg.group_code )
				Left Join
					(	Select
						  sa.id_scheme_account,
						  COUNT(Distinct Date_Format(p.date_add,'%Y%m')) as paid_installment,
						  COUNT(Date_Format(p.date_add,'%Y%m')) as chances,	
						  SUM(p.payment_amount) as total_amount,
						  SUM(p.metal_weight) as total_weight
						From payment p
						Left Join scheme_account sa on(p.id_scheme_account=sa.id_scheme_account and sa.active=1 and sa.is_closed=0)
						Where  (p.payment_status=2 or p.payment_status=1) and  Date_Format(Current_Date(),'%Y%m')=Date_Format(p.date_add,'%Y%m')
						Group By sa.id_scheme_account
					) cp On (sa.id_scheme_account=cp.id_scheme_account)
					left join(Select sa.id_scheme_account, COUNT(Distinct Date_Format(p.date_add,'%d%m')) as paid_installment,
				COUNT(Date_Format(p.date_add,'%d%m')) as chance,
			SUM(p.payment_amount) as total_amount,
			SUM(p.metal_weight) as total_weight
			From payment p
			Left Join scheme_account sa on(p.id_scheme_account=sa.id_scheme_account and sa.active=1 and sa.is_closed=0)
			Where  (p.payment_status=2 or p.payment_status=1) and  Date_Format(Current_Date(),'%d%m')=Date_Format(p.date_add,'%d%m')
		Group By sa.id_scheme_account)sp on(sa.id_scheme_account=sp.id_scheme_account)
					
					
				 Left Join postdate_payment pp On (sa.id_scheme_account=pp.id_scheme_account and (pp.payment_status=2 or pp.payment_status=7) and (Date_Format(pp.date_payment,'%Y%m')=Date_Format(curdate(),'%Y%m')))	
				 join chit_settings cs 
				Where sa.active=1 and sa.is_closed = 0 and p.id_payment<='$id_payment' and sa.id_scheme_account='$id_scheme_account'
				Group By sa.id_scheme_account";
		
	//	echo $sql;exit;
		$records = $this->db->query($sql);
//if(Date_Format(max(p.date_add),'%Y%m') = Date_Format(curdate(),'%Y%m'), SUM(p.no_of_dues),0)  as currentmonthpaycount,	
 	
		if($records->num_rows()>0)
		{
				$record = $records->row();
				
				$allowed_due = 0;
				$due_type = '';
				$checkDues = TRUE;
				
				if($record->has_lucky_draw == 1 && $record-> is_lucky_draw == 1 )
				{ 
					if( $record->group_start_date == NULL && $record->paid_installments >1)
					{ // block 2nd payment if scheme_group_code is not updated 
						$checkDues = FALSE; 
					}
					
					else if($record->group_start_date != NULL)
							{ // block  payment after end date
								 if($record->group_end_date >= time() && $record->group_start_date <= time() ){
						 		$checkDues = TRUE;
						 }else{
							$checkDues = FALSE;
						 }
					}
				}
				 
				if($checkDues){
					
					if($record->paid_installments > 0 || $record->totalunpaid >0){
						if($record->currentmonthpaycount == 0){  // current month not paid (allowed pending due + current due)
							if($record->allow_unpaid == 1){
								if($record->allow_unpaid_months > 0 && ($record->total_installments - $record->paid_installments) >=  $record->allow_unpaid_months && $record->totalunpaid >0){
									if(($record->total_installments - $record->paid_installments) ==  $record->allow_unpaid_months){
										$allowed_due = ($record->totalunpaid < $record->allow_unpaid_months ? $record->totalunpaid : $record->allow_unpaid_months) ;  
									    $due_type = 'PD'; //  pending
									}
									else{
										$allowed_due =  ($record->totalunpaid < $record->allow_unpaid_months ? $record->totalunpaid : $record->allow_unpaid_months)+1 ;  
									    $due_type = 'PN'; // normal and pending
									}
									 
								}
								else{
								     $allowed_due =  1;
								     $due_type = 'ND'; // normal due
								}
							}
							else{
								 $allowed_due =  1;
								 $due_type = 'ND'; // normal due
							}
						}
						else{ 	//current month paid
						
							if($record->allow_unpaid == 1 && $record->allow_unpaid_months >0 && $record->totalunpaid >0 && ($record->currentmonthpaycount-1) < $record->allow_unpaid_months){  
								// can pay previous pending dues if attempts available 
								if($record->totalunpaid > $record->allow_unpaid_months){
									 $allowed_due =  $record->allow_unpaid_months ;
									 $due_type = 'PD'; // pending due
								}
								else{ 
									 $allowed_due =  $record->totalunpaid;
									 $due_type = 'PD'; // pending due
								}
							}
							else{  // check allow advance
								if($record->allow_advance == 1 && $record->advance_months > 0 && ($record->currentmonth_adv_paycount) < $record->advance_months){
									if(($record->advance_months + $record->paid_installments) <= $record->total_installments){
										 $allowed_due =  ($record->advance_months - ($record->currentmonth_adv_paycount));
										 $due_type = 'AD'; // advance due
									}
									else{
										 $allowed_due =  ($record->total_installments - $record->paid_installments);
										 $due_type = 'AD'; // advance due
									}
								}
								else{ // have to check
									 $allowed_due =  0;
									 $due_type = ''; // normal due
								}
							
							}
						}
					}
					else{  // check allow advance and add due with currect month (allowed advance due + current due)
						if($record->allow_advance ==1){ // check allow advance
							if($record->advance_months > 0 && $record->advance_months <= ($record->total_installments - $record->paid_installments)){
								if(($record->total_installments - $record->paid_installments) ==  $record->advance_months){
										 $allowed_due =  $record->advance_months;
										 $due_type = 'AN'; // advance and normal
									}
									else{
										$allowed_due =  $record->advance_months+1 ;  
									     $due_type = 'AN'; // advance and normal
									}
								
							}
							else{
								 $allowed_due =  1;
								 $due_type = 'ND'; // normal due
							}
						}
						else{
							 $allowed_due =  1;
							 $due_type = 'ND'; // normal due
						}
					
					}
				} 
				
				
				$pdc_det = $this->get_pending_pdc($record->id_scheme_account);
					
					$dates=date('d-m-Y');
					
				$schemeAcc = array(
						
									'metal_rate'=> $record->metal_rate,
									'min_amount'=>	$record->min_amount,
									'max_amount'=>	$record->max_amount,
									'min_amt_chance'=>	$record->min_amt_chance,
									'max_amt_chance'=>	$record->max_amt_chance,
									'gst' => $record->gst,
									
									'scheme_overall_amount' => $record->scheme_overall_amount,
									
									
									'gst_type' => $record->gst_type,
									'currentmonth_adv_paycount' => $record->currentmonth_adv_paycount,
									'currentmonthpaycount' 		=> $record->currentmonthpaycount,
									
									'current_date' 		=> $dates,
									'totalunpaid' 				=> $record->totalunpaid,
									'id_scheme_account' 		=> $record->id_scheme_account,
									'start_date' 				=> $record->start_date,
									'chit_number' 				=> $record->chit_number,
									'account_name' 				=> $record->account_name,
									'payable' 					=> $record->payable,
									'scheme_name' 				=> $record->scheme_name,
									'code' 						=> $record->code,
									'scheme_type' 				=> $record->scheme_type,
									'currency_symbol'			=> $record->currency_symbol,
									'min_weight' 				=> $record->min_weight,
									'max_weight' 				=> $record->max_weight,
									'wgt_convert' 				=> $record->wgt_convert,
									'total_installments' 		=> $record->total_installments,
									'paid_installments' 		=> $record->paid_installments,
									'total_paid_amount' 		=> $record->total_paid_amount,
									'total_paid_weight' 		=> $record->total_paid_weight,
									'current_total_amount' 		=> $record->current_total_amount,
									'current_paid_installments' => $record->current_paid_installments,
									'current_chances_used' 		=> $record->current_chances_used,
									'current_chances_use' 		=> $record->current_chances_use,
									'current_total_weight' 		=> $record->current_total_weight,
									'last_paid_duration' 		=> $record->last_paid_duration,
									'last_paid_date' 			=> $record->last_paid_date,
									'is_pan_required' 			=> $record->is_pan_required,
									'last_transaction'     		=> $this->getLastTransaction($record->id_scheme_account),
									'isPaymentExist' 			=> $this->isPaymentExist($record->id_scheme_account),
									'previous_amount_eligible'  => $record->previous_amount_eligible,
									'cur_month_pdc'             => $record->cur_month_pdc,
									'is_flexible_wgt'           => $record->is_flexible_wgt,
									
									'max_chance'           => $record->max_chance,
									'ref_benifitadd_ins'    => $record->ref_benifitadd_ins,
									'ref_benifitadd_ins_type' => $record->ref_benifitadd_ins_type,
									'referal_code'         => $record->referal_code, 
									
									/*'allow_pay'  => ($record->scheme_type==3  &&$record->paid_installments <  $record->total_installments  && $record->current_chances_use < $record->max_chance &&$record-> current_total_amount < $record-> max_amount?'Y':($record->disable_payment != 1 && ($record->payment_status !=2) ? ($record->cur_month_pdc < 1 ? ($record->paid_installments <= $record->total_installments ?($record->is_flexible_wgt?($record->current_total_weight >= $record->max_weight || $record->current_chances_used >= $record->max_chance ?'N':'Y'):($record->paid_installments <  $record->total_installments ?($record->allow_unpaid == 1  && $record->totalunpaid >0 && ($record->currentmonthpaycount-1) < $record->allow_unpaid_months ?'Y':($record->allow_advance == 1 && $record->advance_months > 0 && ($record->currentmonthpaycount -1) < $record->advance_months ?'Y':($record->currentmonthpaycount == 0 ? 'Y': 'N'))):'N')):'N'):'N'):'N')),*/
									
									'allow_pay'  => ($checkDues ? ($record->scheme_type==3  && $record->paid_installments <= $record->total_installments  && $record->current_chances_use < $record->max_chance && ($record-> current_total_amount < $record-> max_amount || $record-> current_total_weight < $record-> max_weight ) ?'Y':($record->disable_payment != 1 && ($record->payment_status !=2) ? ($record->cur_month_pdc < 1 ? ($record->paid_installments <= $record->total_installments ?($record->is_flexible_wgt?($record->current_total_weight >= $record->max_weight || $record->current_chances_used >= $record->max_chance ?'1':'Y'):($record->paid_installments <  $record->total_installments ?($record->allow_unpaid == 1  && $record->totalunpaid >0 && ($record->currentmonthpaycount-1) < $record->allow_unpaid_months ?'Y':($record->allow_advance == 1 && $record->advance_months > 0 && ($record->currentmonthpaycount -1) < $record->advance_months ?'Y':($record->currentmonthpaycount == 0 ? 'Y': 'N'))):'N')):'N'):'N'):'N')) : 'N'),
									
									 'allowed_dues'  			=>($record->is_flexible_wgt == 1 || $record->scheme_type ==3? 1:$allowed_due),
									 'due_type' 		=> ($record->is_flexible_wgt == 1 ? 'ND':$due_type),
									 'allow_preclose' 	=> ($record->currentmonthpaycount == 1 ? ($record->allow_preclose ==1?($record->total_installments - $record->paid_installments == $record->preclose_months ? 1 : 0):0):0),
									 'pdc_payments'  =>($record->cur_month_pdc > 0 ? $this->get_postdated_payment($record->id_scheme_account) : 0) ,
									 'total_pdc'  =>( isset($pdc_det) && $pdc_det !='' ? $pdc_det : 0) ,
									 'weights'  => ( $record->scheme_type=='1'? $this->getWeights() :''),
									 'preclose' => ($record->allow_preclose ==1?$record->preclose_months:0),
									 'preclose_benefits' =>($record->allow_preclose ==1?$record->preclose_benefits:0)
									);		
									
									
									
				}	
				
			//print_r($schemeAcc); exit;
				
			return	$schemeAcc;
		}
		
	function wallet_balance($id_cus)
	{
		$data = array();
		$sql="Select
								  wa.id_wallet_account,
								  c.id_customer,
								  Concat(c.firstname,' ',if(c.lastname!=NULL,c.lastname,'')) as name,
								  c.mobile,
								  Concat(e.firstname,' ',if(e.lastname!=NULL,e.lastname,'')) as emp_name,
								  wa.wallet_acc_number,
								  Date_Format(wa.issued_date,'%d-%m-%Y') as issued_date,
								  wa.remark,
								  wa.active,
								  SUM(CASE WHEN wt.transaction_type=0 THEN wt.`value` ELSE 0 END) as  issues,
								  SUM(CASE WHEN wt.transaction_type=1 THEN wt.`value` ELSE 0 END) as redeem,
								  (SUM(CASE WHEN wt.transaction_type=0 THEN wt.`value` ELSE 0 END) -   SUM(CASE WHEN wt.transaction_type=1 THEN wt.`value` ELSE 0 END)) as balance,
								  cs.wallet_amt_per_points,cs.wallet_balance_type,cs.wallet_points
							From wallet_account wa
								Left Join customer c on (wa.id_customer=c.id_customer)
								Left Join employee e on (wa.id_employee=e.id_employee)
								Left Join wallet_transaction wt on (wa.id_wallet_account=wt.id_wallet_account)
								join chit_settings cs
								where c.id_customer =".$id_cus;
		$result = $this->db->query($sql);
		
		if($result->num_rows()>0){
		
		           $sql1="SELECT w.redeem_percent FROM wallet_category_settings w where w.id_category=1";		
		           $record = $this->db->query($sql1);
				   
				   if($record->num_rows()>0){
					   	$balance= ($result->row()->wallet_balance_type==1 ? (($result->row()->balance/$result->row()->wallet_points)*$result->row()->wallet_amt_per_points) : $result->row()->balance);
				   	//$data=(($result->row()->balance*$record->row()->redeem_percent)/100);
		                 $data = array('redeem_percent'=>$record->row()->redeem_percent,'wal_balance'=>floor($balance));
				   }
		               
		}
		 return $data;
	}
	
	function getWcategorySettings($cat_code = ""){	
    	if($cat_code == ""){
			$sql = $this->db->query("SELECT id_wcat_settings,`value`,`point`,`id_category`,`redeem_percent`,ws.`active`,`code`,ws.`active` FROM wallet_category_settings ws 
        		LEFT JOIN wallet_category wc on  wc.id_wallet_category = ws.id_category and wc.active=1
        WHERE ws.active=1"); 
        	return $sql->result_array();
		}else{
			$sql = $this->db->query("SELECT id_wcat_settings,`value`,`point`,`id_category`,`redeem_percent`,ws.`active`,`code`,ws.`active` FROM wallet_category_settings ws 
        		LEFT JOIN wallet_category wc on  wc.id_wallet_category = ws.id_category and wc.active=1
        WHERE ws.active=1 and wc.code='".$cat_code."'"); 
        	return $sql->row_array();
		}
      //  echo $this->db->last_query();
	
    }
    
	function getWalletPaymentContent($id_scheme_account){
	    $sql="Select iwa.available_points,sa.id_branch as branch, ifnull(iwa.mobile,0) as isAvail,c.mobile,cs.walletIntegration,cs.wallet_points,cs.wallet_amt_per_points,cs.wallet_balance_type
	    From scheme_account sa 
	    Left Join customer c on (c.id_customer=sa.id_customer) 
	    LEFT JOIN inter_wallet_account iwa on iwa.mobile=c.mobile 
	    join chit_settings cs 
	    Where sa.id_scheme_account='".$id_scheme_account."'";
	      return $this->db->query($sql)->row_array();
	} 
    
    function insertData($data,$table){
	    $status = $this->db->insert($table,$data);
	    return	array('status'=>$status,'insertID'=>($status == TRUE ? $this->db->insert_id():''));
	}
    function updateData($data,$tran,$table)
	{
		$this->db->where('bill_no',$tran['bill_no']);
		 if($tran['id_branch'] == ''){
		    $this->db->where('id_branch',null);
		 }else{
		     $this->db->where('id_branch',$tran['id_branch']);
		 }
		 $status = $this->db->update($table,$data); 
		return $status;
	}
	
	function updateTransDetailData($data,$id)
	{
		$this->db->where('id_inter_waltransdetail',$id); 
		 $status = $this->db->update('inter_wallet_trans_detail',$data);
		return $status;
	}
	
	function getInterWalletCustomer($mobile){
		$sql = $this->db->query("SELECT * FROM  inter_wallet_account WHERE mobile=".$mobile);
		if($sql->num_rows() > 0){
			return array('status'=>true,'data' =>$sql->row_array());
		}else{
			return array('status'=>false,'data' =>'');
		}
	}
	
	function updInterWalletAcc($data)
	{
		$this->db->where('mobile',$data['mobile']); 
		$status = $this->db->update('inter_wallet_account',array('available_points'=>$data['available_points'],'last_update'=>date('Y-m-d H:i:s')));
		return $status;
	}
	
	function updwallet($trans,$mobile)
	{
	    $sql = $this->db->query("select c.id_customer,id_wallet_account from customer  c left join wallet_account wa on wa.id_customer = c.id_customer where mobile=".$mobile);
		if($sql->num_rows() > 0){
			$id_wallet_ac = $sql->row('id_wallet_account');
		  // print_r($trans);exit;
			$data = array('id_wallet_account'   => $id_wallet_ac,
						  'date_transaction' 	=> date('Y-m-d H:i:s'),
						  'transaction_type'	=> ($trans['trans_type'] == 1 ? 0 : 1),
						  'value'				=> $trans['trans_points'],
						  'description'			=> $trans['remark'],
						  ); 
						 
			$status = $this->db->insert('wallet_transaction',$data);         
			//var_dump($status);exit; 
			return $status;
		}else{
			return TRUE;
		}
	}
	
	function getSyncWalletData($id_branch){	
    	$sql = "SELECT * FROM  inter_sync_wallet WHERE branch_".$id_branch."= 0";
		return $this->db->query($sql)->result_array();
    }
	 
	function getSyncWalletByMobile($mobile){	
        $sql = "SELECT * FROM  inter_sync_wallet WHERE mobile=".$mobile;
		return $this->db->query($sql)->row_array();
    }
	
	function updateSyncWal($data){	
        $this->db->where('mobile',$data['mobile']); 
		 $status = $this->db->update('inter_sync_wallet',$data);
		return $status;
    }
	function get_payment_gateway()
	{
       $sql="SELECT
			     id_pg,pg_name,pg_code,type
			 FROM gateway where type=0";
       return $this->db->query($sql)->result_array();
	}
	function get_paidinstallmentcount($id){
			
		   $sql="SELECT  p.id_payment,p.id_scheme_account,s.id_scheme
				FROM scheme_account s
				left join payment p on(p.id_scheme_account = s.id_scheme_account) 
				where s.id_scheme_account=".$id." and p.payment_status=1 order by p.id_payment Asc";				
			return $this->db->query($sql)->result_array();
			
			
		}
		
		function get_customer($id_customer)
		{
		$this->db->where('id_customer',$id_customer);

		

		$r=$this->db->get(self::CUS_TABLE);

		

		if($r->num_rows==1)
		{
			$result=$r->row_array();
			return $result;
		}
		else
		{
			return array('status'=>2,'msg'=>'Invalid');
		}
		    
		
		}

		function otp_update($data,$id)

	 {

		$this->db->where('id_sch_acc',$id);

		$status=$this->db->update(self::OTP_TABLE,$data);

		return $status;

	}	
	
	function isOTPRegForPayment()
	{
		 $sql="Select isOTPRegForPayment from chit_settings where id_chit_settings = 1";
		 return $this->db->query($sql)->row()->isOTPRegForPayment;
	}
	function payOTP_exp()
	{
		 $sql="Select payOTP_exp from chit_settings where id_chit_settings = 1";
		 return $this->db->query($sql)->row()->payOTP_exp;
	}
	function isOTPReqToLogin()
	{
		 $sql="Select isOTPReqToLogin from chit_settings where id_chit_settings = 1";
		 return $this->db->query($sql)->row()->isOTPReqToLogin;
	}
	function payment_cancel($type="",$id="",$pay_array="")
	{

	
			switch($type)
    	{
			
			
			case 'update':
				$this->db->where("id_payment",$id);
				
	           	$status = $this->db->update("payment",$pay_array);
			   	return	array('status' => $status, 'updateID' => $id);     
			break;
			
			
		 }	
	}
	
    function firstPayamt_payable()
	{
		$sql="Select c.firstPayamt_payable FROM chit_settings c where c.id_chit_settings = 1";

		return $this->db->query($sql)->row()->firstPayamt_payable;
		
	}
	
	
   
     function getBranchGatewayData($branch_id,$pg_code)
	 {
   		$sql="SELECT param_1,param_2,param_3,param_4,pg_code,api_url from gateway where is_default=1 and  pg_code=".$pg_code." ".($branch_id!='' ?"and id_branch=".$branch_id."" :'')."";
		$result=  $this->db->query($sql)->row_array();
	//	print_r($sql);exit;
		return $result;   	
	 }

	// get payment data//hh
	
	function get_payments_data_list($ref_trans_id)

{
	        //echo $id;
             $sql =("SELECT
					cs.has_lucky_draw, s.is_lucky_draw,s.code,IFNULL(sa.group_code,'')as scheme_group_code,
					  p.id_payment,p.is_offline,sa.id_branch,sa.ref_no,sa.id_scheme_account,
					  sa.account_name,p.payment_amount,
					  if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname, ' ',c.mobile)) as name,c.lastname,c.firstname,
					  c.mobile,c.email,
					  IFNULL(sa.scheme_acc_number,'Not Allocated') as scheme_acc_number,p.due_type,p.act_amount,
					  s.code,s.scheme_name,
					  p.id_employee,IFNULL(e.emp_code,'-')as emp_code,
                      if(e.lastname is null,e.firstname,concat(e.firstname,' ',e.lastname)) as employee, 
					  if(s.scheme_type=0,'Amount',if(s.scheme_type=1,'Weight','Amount to Weight')) as scheme_type,
					  IFNULL(p.payment_amount,'-') as payment_amount,
				      IFNULL(if(p.metal_rate=0,'- ',p.metal_rate), '- ') as metal_rate,
					  IFNULL(if(p.metal_weight=0,'-',p.metal_weight), '-') as metal_weight,
					  IFNULL(Date_format(p.date_payment,'%d-%m%-%Y'),'-') as date_payment,
					  IFNULL(Date_format(p.last_update,'%d-%m%-%Y'),'-') as last_update,
			          p.payment_type,concat(p.payment_type, ' ',p.payment_mode) as payment_type,
					  IFNULL(sa.scheme_acc_number,'') as msno,
					  IFNULL(p.bank_acc_no,'-') as bank_acc_no,
					  IFNULL(p.bank_name,'-')as bank_name,
					  IFNULL(p.bank_IFSC,'-') as bank_IFSC,
					  IFNULL(p.bank_branch,'-') as bank_branch,
					  IFNULL(cs.receipt_no_set,'-') as receipt_no_set,
					  IFNULL(p.id_transaction,'-') as id_transaction,
					  IFNULL(p.payment_ref_number,'-') as payment_ref_number,
					  IFNULL(p.act_amount, '-') as act_amount,
					  IFNULL(p.payu_id,'-') as payu_id ,
					  IFNULL(b.name,'') as id_branch ,
					  IFNULL(p.card_no,'-') as card_no,
					  IFNULL(p.ref_trans_id,'-') as ref_trans_id,
					  psm.payment_status as payment_status,
					  p.payment_status as id_status,
					  psm.color as status_color,
					  
					if(cs.receipt_no_set=1 || cs.receipt_no_set=3 && p.receipt_no is null,'',p.receipt_no) as receipt_no,
					  IFNULL(p.remark,'-') as remark,cs.currency_name,cs.currency_symbol
				FROM payment p
				join chit_settings cs
				left join scheme_account sa on(p.id_scheme_account=sa.id_scheme_account)
				Left Join employee e On (e.id_employee=p.id_employee)
				Left Join customer c on (sa.id_customer=c.id_customer)
				left join scheme s on(sa.id_scheme=s.id_scheme)
			    Left Join payment_mode pm on (p.payment_mode=pm.id_mode)		
			    Left Join branch b on (p.id_branch=b.id_branch)		
			   
			    Left Join payment_status_message psm On (p.payment_status=psm.id_status_msg)
		 where p.ref_trans_id='".$ref_trans_id."'
			
				 
				 ORDER BY p.date_payment DESC");
	//print_r($sql);exit;

		return $this->db->query($sql)->result_array();

	}	
	// get payment data//
	
//emp scheme account report
function get_all_emp_account_by_range($from_date,$to_date,$id_branch,$id_employee)

	{
			
	    	$branch_settings=$this->session->userdata('branchWiseLogin');
			$log_branch=$this->session->userdata('id_branch');
			$uid=$this->session->userdata('uid');
			
		$accounts=$this->db->query("select concat(e.firstname,' ',e.lastname)as  employee_name,e.id_employee,
		
		IFNULL(s.pan_no,'-') as pan_no,cs.has_lucky_draw, sc.is_lucky_draw,
		                          IFNULL(sg.group_code,'') as group_code,
                                 if(s.show_gift_article=1,'Issued','Not Issueed')as gift_article,
							  s.id_scheme_account,IFNULL(s.scheme_acc_number,'Not Allocated') as scheme_acc_number ,IF(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,s.ref_no,s.account_name,DATE_FORMAT(s.start_date,'%d-%m-%Y') as start_date,c.is_new,s.added_by,concat('C','',c.id_customer) as id_customer,cs.schemeacc_no_set,

							  sc.scheme_name,if(s.is_new ='Y','New','Existing') as is_new,sc.code,if(sc.scheme_type=0,'Amount',if(sc.scheme_type=1,'Weight','Amount to Weight'))as scheme_type,sc.total_installments,sc.max_chance,sc.max_weight,sc.amount,c.mobile,if(s.active =1,'Active','Inactive') as active,s.date_add,cs.currency_symbol,sc.scheme_type  as scheme_types,
						
		
		IFNULL(IF(s.is_opening=1,IFNULL(s.paid_installments,0)+ IFNULL(if(sc.scheme_type = 1 and sc.min_weight != sc.max_weight , COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')), sum(pay.no_of_dues)),0), if(sc.scheme_type = 1 and sc.min_weight != sc.max_weight , COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')), sum(pay.no_of_dues))) ,0)
  as paid_installments 

							from
																		
							  ".self::ACC_TABLE." s

							left join ".self::CUS_TABLE." c on (s.id_customer=c.id_customer)

							left join ".self::SCH_TABLE." sc on (sc.id_scheme=s.id_scheme)
							Left Join ".self::SCH_GROUP_TABLE." sg On (sg.group_code = s.group_code )
							
							left join ".self::BRANCH." b on (b.id_branch=s.id_branch)
							
							left join ".self::EMPLOYEE_TABLE." 	e on (e.id_employee=s.employee_approved)

							left join ".self::PAY_TABLE." pay on (pay.id_scheme_account=s.id_scheme_account  and (pay.payment_status=2 or pay.payment_status=1))

							join chit_settings cs

							 Where ( s.is_closed=0 and  date(s.start_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') ".($uid!=1 ? ($branch_settings==1 ? ($id_branch!=0 && $id_branch!='' ? "and s.id_branch=".$id_branch."" : " and (s.id_branch=".$log_branch." or b.show_to_all=1 or b.show_to_all=3)"):'') : ($id_branch!=0 && $id_branch!=''? "and s.id_branch=".$id_branch."" :''))." ".($id_employee!=''? "and s.employee_approved=".$id_employee."":'' )."
								
							group by s.id_scheme_account");
							
 //print_r($this->db->last_query()); exit;


						
		return $accounts->result_array();

		

	}
	
	
	//Employee wise summary
	 function payment_employee_summary($from_date,$to_date,$id_branch)
	{  
		if($this->branch_settings==1){
			$id_branch  = $this->input->post('id_branch');}
		else{
		$id_branch = '';}		
	
				$sql_1="select  s.code,p.id_employee,e.firstname,s.code,IFNULL(b.name,'')as name,
					sum(p.payment_amount) as payment_amount,s.gst_type, s.gst,IFNULL(Date_format(p.date_payment,'%d-%m%-%Y'),'-') as date_payment,					
					COUNT(CASE WHEN  (p.receipt_no is not null  || p.receipt_no is null ) and p.payment_status=1 THEN 1 END) as receipt,
					compy.gst_number,cs.gst_setting,
					if(p.payment_mode='FP','FP',p.payment_mode)as payment_mode,
					if(p.payment_mode='CC' || p.payment_mode='DC','Card',p.payment_mode)as payment_mode					
								
					FROM sch_classify sc
					 join company compy
					 join chit_settings cs
					LEFT JOIN scheme s ON (sc.id_classification = s.id_classification)
					  LEFT JOIN scheme_account sa ON (s.id_scheme = sa.id_scheme)
					  LEFT JOIN payment p ON (sa.id_scheme_account = p.id_scheme_account)
					  Left JOIN branch b on(b.id_branch=p.id_branch)
					  LEFT JOIN employee e ON (e.id_employee=p.id_employee)
					  LEFT JOIN postdate_payment pp ON (sa.id_scheme_account = pp.id_scheme_account)
						WHERE sc.active=1 AND (p.payment_status=1 or pp.payment_status=1)
						    ".($from_date!='' ?" And (date(p.date_payment) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')":'')." ".($id_branch!=0 &&$id_branch!='' ?' and p.id_branch ='.$id_branch:'')."
						GROUP BY p.id_employee,s.code";
	  	$payments = $this->db->query($sql_1)->result_array();
	  	return $payments;
	}
	//Employee wise summary

//mob no,ref no,clientid,sch A/c no wise filter & change options in inter table Data's // 
// Customer Reg& transaction records  // HH	

	function get_intertable_list($mobile,$clientid,$ref_no,$group_code,$cus="")

	{    
            $sql =("SELECT * FROM `customer_reg` where mobile='".$mobile."' or clientid='".$clientid."' or ref_no='".$ref_no."'or group_code='".$group_code."'");
			$this->load->database('default',true);
			//print_r($sql);exit;  
		return $this->db->query($sql)->result_array();

	}			

	
	function get_intertable_translist($client_id,$ref_no,$cus="")

	{    
          $sql =("SELECT * FROM `transaction` where client_id='".$client_id."' or ref_no='".$ref_no."'");
		  $this->load->database('default',true);
		  //print_r($sql);exit;  
		return $this->db->query($sql)->result_array();

	}
    function update_cusdata($id,$mobile,$scheme_ac_no,$group_code)
	{		
	    $data['mobile'] = $mobile;
	    $data['scheme_ac_no'] = $scheme_ac_no;
	    $data['group_code'] = $group_code;
		$this->db->where('id_customer_reg',$id);
		$res = $this->db->update(self::CUS_REG_TABLE,$data);
	  //print_r($this->db->last_query());exit;
		return $res;    	
	}
		
	
//mob no,ref no,clientid,sch A/c no wise filter & change options in inter table Data's // 
// Customer Reg& transaction records  // HH		
  
 	
//Kyc Approval Data status filter with date picker//hh

 	function get_kycdata_range($from_date,$to_date,$status)
    {
    
            //$sql =("SELECT * FROM `kyc` WHERE    status=0");
		  // $sql =("SELECT * FROM `kyc` WHERE   (date(kyc.date_add) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') and status=0");
		   $sql =("SELECT id_kyc,c.id_customer as cus, IF(kyc_type = 1, 'Bank Account', IF(kyc_type = 2, 'PAN Card', IF(kyc_type = 3, 'Aadhaar', ''))) as kyc_type,number,name,bank_ifsc ,bank_branch,IF(status = 0, 'Pending', IF(status = 1, 'In Progress', IF(status = 2, 'Verified', IF(status = 3, 'Rejected', '')))) as status ,IF(verification_type = 1, 'Manual',IF(verification_type = 2,'Auto', '')) as verification_type,last_update,kyc.date_add,Date_format(kyc.dob, '%d-%m-%Y') as dob,
	    Concat(e.firstname,' ',if(e.lastname!=NULL,e.lastname,'')) as emp_verified_by,
	    kyc.emp_verified_by as id_employee ,c.firstname as id_customer
	    FROM `kyc` kyc 
	    Left Join employee e on (kyc.emp_verified_by=e.id_employee) 
	    LEFT JOIN customer c ON (kyc.id_customer=c.id_customer) WHERE   (date(kyc.date_add) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') ".($status!=4 ? " and kyc.status=".$status."":'')."");
		   //print_r($sql);exit;  
	       return $this->db->query($sql)->result_array();	
     } 
     
     	function get_kycdata($status)
	{
	
	    $sql=$this->db->query("SELECT id_kyc,c.id_customer as cus, IF(kyc_type = 1, 'Bank Account', IF(kyc_type = 2, 'PAN Card', IF(kyc_type = 3, 'Aadhaar', ''))) as kyc_type,number,name,bank_ifsc ,bank_branch,IF(status = 0, 'Pending', IF(status = 1, 'In Progress', IF(status = 2, 'Verified', IF(status = 3, 'Rejected', '')))) as status ,IF(verification_type = 1, 'Manual',IF(verification_type = 2,'Auto', '')) as verification_type,last_update,kyc.date_add,Date_format(kyc.dob, '%d-%m-%Y') as dob,
	    Concat(e.firstname,' ',if(e.lastname!=NULL,e.lastname,'')) as emp_verified_by,
	    kyc.emp_verified_by as id_employee ,c.firstname as id_customer
	    FROM `kyc` kyc 
	    Left Join employee e on (kyc.emp_verified_by=e.id_employee) 
	    LEFT JOIN customer c ON (kyc.id_customer=c.id_customer)
	    ".($status!='4'?"  WHERE kyc.status =".$status :"  ")."  ");
		//print_r($this->db->last_query());exit;
		return $sql->result_array();
	}
  
    function updatekyc($data,$id,$id_cus)
	{		
		$this->db->where('id_kyc',$id);
		$status=$this->db->update('kyc',$data);
	    $sql = $this->db->query("SELECT * FROM `kyc` WHERE id_customer=".$id_cus." AND status=2");
	    return array('status' => $status,'verified_kycs'=> $sql->num_rows());
	}
	
	function updatekyccus($data,$id)
	{
	    $this->db->where('id_customer',$id);
		$res = $this->db->update('customer',$data);
	}
	

//Kyc Approval Data status filter with date picker//	
    
    
   /*Functions for pay settled payments Begins */
	
	function updateSettledPayments($data,$txnid,$payuid)
	{
		$this->db->where('ref_trans_id',$txnid); 
		$this->db->where('payu_id',$payuid); 
		$status=$this->db->update(self::PAY_TABLE,$data);
		return $status;
	}
	
	function insertSettledPay($data)
	{
		$status = $this->db->insert('gateway_settled_payments',$data);
		return $status;
	}
	function settledTxnsToUpdate()
	{
		$data = $this->db->query('select * from gateway_settled_payments where  is_updated=0');
		return $data->result_array();
	}
	function updatePayuSettledTrans($data,$txnid,$gateway_id)
	{
		$this->db->where('txnid',$txnid); 
		$this->db->where('gateway_id',$gateway_id); 
		$status=$this->db->update('gateway_settled_payments',$data);
		return $status;
	}
	/*Functions for pay settled payments Ends*/
    
     //Plan 2 and Plan 3 Scheme Enquiry Data with date picker//HH
	function get_sch_enq_list()

	{    
            $sql =("SELECT sch_enquiry.id_sch_enquiry,mobile,c.title, if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as id_customer,sch_enquiry.intresred_amt,sch_enquiry.message,sch_enquiry.intrested_wgt, IFNULL(Date_format(enquiry_date,'%d-%m%-%Y'),'-') as enquiry_date FROM `sch_enquiry` 
            LEFT JOIN customer c on c.id_customer=sch_enquiry.id_customer");
			$this->load->database('default',true);
			//print_r($sql);exit;  
		return $this->db->query($sql)->result_array();

	}			
    function get_sch_enq_list_by_date($from_date,$to_date)

	{    
            $sql =("SELECT sch_enquiry.id_sch_enquiry,mobile,c.title, if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as id_customer,sch_enquiry.intresred_amt,sch_enquiry.message,sch_enquiry.intrested_wgt, IFNULL(Date_format(enquiry_date,'%d-%m%-%Y'),'-') as enquiry_date FROM `sch_enquiry` 
            LEFT JOIN customer c on c.id_customer=sch_enquiry.id_customer where (date(sch_enquiry.enquiry_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')");
			$this->load->database('default',true);
			//print_r($sql);exit;  
		return $this->db->query($sql)->result_array();

	}	
    
     //Plan 2 and Plan 3 Scheme Enquiry Data with date picker//
    function get_metalrate_by_branch($id_branch)
    {
        $data=$this->get_settings();
		if($data['is_branchwise_rate']==1 &&$id_branch!='' && $id_branch!=NULL)
		{
			$sql="select * from metal_rates m
	   		left join branch_rate br on m.id_metalrates=br.id_metalrate 
	   		where br.id_branch=".$id_branch." order by  br.id_metalrate desc limit 1";
		    //echo $sql;exit;
		}
		else if($data['is_branchwise_rate']==1)
		{
			$sql="select * from metal_rates 
			left join branch_rate br on br.id_metalrate=metal_rates.id_metalrates 
			where br.status=1";
		}
		else
		{
			$sql="select * from metal_rates 
			left join branch_rate br on br.id_metalrate=metal_rates.id_metalrates order by id_metalrates desc limit 1";
		}
		$result = $this->db->query($sql);	
		 //print_r($sql);exit;
		return $result->row_array();
    }
    
    
     //Purchase Payment - Akshaya Thiruthiyai Spl updt//HH
     function ajax_get_customers_list($param)
    {
		$customers=$this->db->query("select c.id_purch_customer, IFNULL(c.mobile,'')as mobile,c.firstname
				from  purchase_customer c  
									where  (c.firstname LIKE '$param%'  OR c.mobile LIKE '$param%')");
		 // print_r($this->db->last_query());exit;
		return $customers->result_array(); 
	}
     
     function ajax_get_purchase_payment($from_date,$to_date,$id_purch_customer)
     {
         $sql="select id_purch_payment, c.firstname as name,c.mobile,c.id_purch_customer,if(p.type=1,'Amount','Weight')as type,
         IF(delivery_preference = 1, 'Ornament', IF(delivery_preference = 2, 'Coin', '')) as delivery_preference,
         p.payment_amount,if(p.type=2,p.metal_weight,'-') as metal_weight,psm.payment_status as payment_status,
         date_format(p.date_add,'%d-%m-%Y') as date_add,iFNULL(p.id_transaction,'-') as id_transaction,is_delivered
         from purchase_customer c
         left join purchase_payment p on p.mobile=c.mobile
         Left Join payment_status_message psm On p.payment_status=psm.id_status_msg
         Where (date(p.date_add) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')  
         ".($id_purch_customer!='' ? " and c.id_purch_customer=".$id_purch_customer."" :'')."
         order by p.id_purch_payment desc";
       // print_r($sql);exit;
         return $this->db->query($sql)->result_array();
     }
    
     //otp verify aftr upd pay Tabl when purchase - the jewel for AT special//
     
     	function get_purchasecustomer($mobile)
		{
		   
		$this->db->where('mobile',$mobile);
	
	
		

		$r=$this->db->get(self::PURCH_CUS_TABLE);
        //print_r($this->db->last_query());exit;
		

		if($r->num_rows==1)
		{
			$result=$r->row_array();
			return $result;
		}
		else
		{
			return array('status'=>2,'msg'=>'Invalid');
		}
		    
		
		}
		
		function get_purchase_pay($id_purch_payment)
		{
		   
		$this->db->where('id_purch_payment',$id_purch_payment);
	
	
		

		$r=$this->db->get(self::PURCH_PAY_TABLE);
       // print_r($this->db->last_query());exit;
		

		if($r->num_rows==1)
		{
			$result=$r->row_array();
			return $result;
		}
		else
		{
			return array('status'=>2,'msg'=>'Invalid');
		}
		    
		
		}
		
      	function add_remark($data,$id)

	    {

		$this->db->where('id_purch_payment',$id);

		$status=$this->db->update(self::PURCH_PAY_TABLE,$data);
        //print_r($this->db->last_query());exit;
		return $status;

	     }
        //otp verify aftr upd pay Tabl when purchase - the jewel for AT special//
      //Purchase Payment - Akshaya Thiruthiyai Spl updt//
      
        function get_last_paid_month($id_scheme_account)
    	{
            $sql = "select * from payment where due_month is not null and id_scheme_account=".$id_scheme_account." Order By id_payment DESC";
            $result = $this->db->query($sql)->row_array();
            return $result;
    	} 
    	
    	function generateDueDate($id_scheme_account,$dueType)
    	{
    		if($dueType == 'ND'){
    			$sql = $this->db->query("select due_month,due_year from payment where payment_status <= 2 and due_month=".date('m')." AND due_year=".date('Y')." AND id_scheme_account=".$id_scheme_account); 
    			if($sql->num_rows() == 0){
    				return array("due_month" => date('m'), "due_year" => date('Y'));				
    			}else{
    				$last_paid_sql = $this->db->query("select due_month,due_year from payment where payment_status <= 2 and due_month is not null and id_scheme_account=".$id_scheme_account." Order By id_payment DESC LIMIT 1");
    				$last_m = $last_paid_sql->row_array();
    				$d = $last_m['due_year']."-".$last_m['due_month']."-01";
    				$month      = date('m', strtotime("+1 months", strtotime($d)));
               		$due_year   = date('Y', strtotime("+1 months", strtotime($d)));
               		return array("due_month" => $month, "due_year" => $due_year);			
    			}	        
    		}
    		else if($dueType == 'AD'){
    			$last_paid_sql = $this->db->query("select due_month,due_year from payment where payment_status <= 2 and due_month is not null and id_scheme_account=".$id_scheme_account." Order By id_payment DESC LIMIT 1");
    				$last_m = $last_paid_sql->row_array();
    				$d = $last_m['due_year']."-".$last_m['due_month']."-01";
    				$month      = date('m', strtotime("+1 months", strtotime($d)));
               		$due_year   = date('Y', strtotime("+1 months", strtotime($d)));
               		return array("due_month" => $month, "due_year" => $due_year);	
    		}else{
    			return array("due_month" => date('m'), "due_year" => date('Y'));	
    		}			     
    	}
    	
    // Lucky Draw scheme group data
	function updateGroupCode($id_scheme_account)
	{
		$sql = $this->db->query("SELECT sch.is_lucky_draw,sa.id_scheme_account,sch.code,sa.group_code,sch.id_scheme, sa.id_branch,max_members,
		                         cs.scheme_wise_acc_no
							FROM scheme_account sa  
							LEFT JOIN scheme sch ON sch.id_scheme = sa.id_scheme 
							join chit_settings cs
							WHERE  id_scheme_account =".$id_scheme_account);
							
				//echo $this->db->last_query();//exit;
		if( $sql->num_rows() > 0 ){
			$accData =  $sql->row_array(); 
				//echo ($accData);exit;
			if( strlen($accData['group_code']) == 0 && ($accData['is_lucky_draw'] == 1 && ($accData['scheme_wise_acc_no'] == 1 || $accData['scheme_wise_acc_no']==2 || $accData['scheme_wise_acc_no']==3))){	  
				$max_members = $accData['max_members'];
				$id_scheme = $accData['id_scheme'];
				$id_branch = $accData['id_branch'];
				$id_scheme_account = $accData['id_scheme_account'];
				$group_code = $accData['code']; // Scheme Master Code
				
				// scheme_group - status => 0 - Upcoming, 1 - Active, 2 - Reached Limit, 3 - Group closed
				
				// Get active group
				$sql_1 = $this->db->query("SELECT id_scheme_group,group_code_param_1,group_code_param_2,group_code,group_code_suffix FROM `scheme_group` where ".($id_branch > 0 ? ' id_branch = '.$id_branch.' and' :'' )." status = 1 and id_scheme = ".$id_scheme); 
					//print_r($sql_1);exit;
					
				if( $sql_1->num_rows() > 0 ){ 		
					$active_group = $sql_1->row_array();
					// Get group members count
					$sql_2 = $this->db->query("SELECT count(id_scheme_account) as accounts FROM `scheme_account` where".($id_branch > 0 ? ' id_branch = '.$id_branch.' and' :'' )." id_scheme = ".$id_scheme." and group_code = '".$active_group['group_code']."' and scheme_acc_number is not null and scheme_acc_number != '' ");  
					$accounts = $sql_2->row()->accounts;			
					if( $accounts >= $max_members ){ // Reached group limit, update status of current group and create new scheme group
						// Update current group status as 2
						$updData = array(
										"status"	  =>	2,  // Reached Limit
										"last_update" =>	date("Y-m-d H:i:s")
									);
						$this->db->where('id_scheme_group',$active_group['id_scheme_group']); 
						$upd_status = $this->db->update("scheme_group",$updData); 
						
						// Create new scheme group
						$group_code_suffix  = ++$active_group['group_code_suffix'];
						$group_code_param_1 = $active_group['group_code_param_1'];
						$group_code_param_2 = $active_group['group_code_param_2'];
						$group_code = $group_code_param_1.''.$group_code_param_2.''.$group_code_suffix;
						$insData = array(
										"id_scheme"			 =>	$id_scheme,
										"id_branch"			 =>	$id_branch,
										"group_code"		 =>	$group_code,
										"group_code_param_1" =>	$group_code_param_1,
										"group_code_param_2" =>	$group_code_param_2,
										"group_code_suffix"	 =>	$group_code_suffix,
										"status"	 		 =>	1,
										"date_add"			 =>	date("Y-m-d H:i:s")
									);
						$ins = $this->insertData($insData,"scheme_group");
					}
					else if( $accounts < $max_members ){
						$id_scheme_group = $active_group['id_scheme_group'];
						$group_code = $active_group['group_code'];
					} 
				}else{ 			// Create new scheme group 
					$insData = array(
									"id_scheme"			 =>	$id_scheme,
									"id_branch"			 =>	$id_branch,
									"group_code"		 =>	$group_code.'1',
									"group_code_param_1" =>	$group_code,
									"group_code_param_2" =>	NULL,
									"group_code_suffix"	 =>	1,
									"status"	 		 =>	1,
									"date_add"			 =>	date("Y-m-d H:i:s")
								);
					$group_code = $group_code.'1';
					$ins = $this->insertData($insData,"scheme_group");
				} 
				
				// Update group code in scheme account table
				if(strlen($group_code) > 0){
					$updAccData = array(
									"group_code"=> $group_code,  
									"date_upd" 	=> date("Y-m-d H:i:s")
								);
					$this->db->where('id_scheme_account',$id_scheme_account); 
					$status = $this->db->update("scheme_account",$updAccData); 
					//echo $this->db->last_query();//exit;
					return $status;
				}else{
					return FALSE;
				}
			}else{
				return TRUE;
			}
		}else{
			return TRUE;
		}
	}
	
	function get_notiContent($noti_code,$id)
     {
		//Declaration of variables
		$message ="";
		$noti_msg = "";
		$noti_footer = "";
		$msg = "";
		$customer_data = array();
		$data = array();
			
			 if($noti_code == 'SCH_CMP'){
				$resultset = $this->db->query("SELECT
					  p.id_payment,if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as cus_name,
					  c.firstname as fname, c.mobile,
					if(cs.has_lucky_draw=1,concat(ifnull(sa.group_code,''),'',ifnull(sa.scheme_acc_number,'Not allocated')),concat(ifnull(s.code,''),' ',ifnull(sa.scheme_acc_number,'Not allocated')))as ac_no
				
				FROM payment p
				left join scheme_account sa on(p.id_scheme_account=sa.id_scheme_account)
				Left Join employee e On (e.id_employee=p.id_employee)
				Left Join customer c on (sa.id_customer=c.id_customer)
				left join scheme s on(sa.id_scheme=s.id_scheme)
			    Left Join payment_mode pm on (p.payment_mode=pm.id_mode)		
			    Left Join payment_status_message psm On (p.payment_status=psm.id_status_msg) 
			    join company cmp
			    join chit_settings cs
			    
			    Where  p.id_payment=".$id); 
				//print_r($this->db->last_query());exit;
		
			 }
			foreach($resultset->result() as $row)
				{
					$customer_data = $row;
					//print_r($customer_data);exit;
				//	$mobile=$row->mobile;
				}	
			
			$resultset = $this->db->query("SELECT noti_name,noti_code,noti_footer,noti_msg from notification where noti_code = '".$noti_code."'");
				//print_r($this->db->last_query());exit;
				foreach($resultset->result() as $row)
				{
					$noti_msg = $row->noti_msg;
					
					$noti_footer = $row->noti_footer;
					$noti_header=$row->noti_name;
					$data = $row->noti_msg;
				}
			 
			$resultset->free_result();
			
			//Generating Message content
			$field_name = explode('@@', $noti_msg);
			
			for($i=1; $i < count($field_name); $i+=2) 
        	{
        		$field =  $field_name[$i];
        		if(isset($customer_data->$field))
        		{ 
        		   
        		$noti_msg = str_replace("@@".$field."@@",$customer_data->$field,$noti_msg);
        		}	
        	}
			$field_name_footer = explode('@@', $noti_footer);	
			for($i=1; $i < count($field_name_footer); $i+=2)
			 {
				if(isset($customer_data->$field_name_footer[$i]))
				 { 
					$noti_footer = str_replace("@@".$field_name_footer[$i]."@@",$customer_data->$field_name_footer[$i],$noti_footer);					
				}	
			}
			$noti_msg .= " ".$noti_footer;
		
	//print_r($resultset);exit;
	
	 return (array('message'=>$noti_msg,'header'=>$noti_header,'footer'=>$noti_footer));
	}
	
	function getnotificationids($mobile)
	{
		$sql = $this->db->query("SELECT r.token as token ,c.mobile
									from registered_devices r 
									LEFT JOIN customer c on (c.id_customer=r.id_customer) 
									where c.notification = 1 and mobile=".$mobile);
			//print_r($this->db->last_query());exit;
		$data =$sql->result_array();
		
		return $data;
	}
	
	 // Payment Online/offline collection // HH
    function payments_on_off_collection_list($date)
	{  
			$date_type  = $this->input->post('date_type');
		$added_by  = $this->input->post('added_by');
				$sql_1="select  s.code,
					sum(p.payment_amount) as payment_amount,s.gst_type, s.gst,IFNULL(Date_format(p.date_payment,'%d-%m%-%Y'),'-') as date_payment,					
					COUNT(CASE WHEN  (p.receipt_no is not null  || p.receipt_no is null ) and p.payment_status=1 THEN 1 END) as receipt,
					compy.gst_number,cs.gst_setting,p.payment_type,
					if(p.payment_mode='CC','CC',p.payment_mode)as payment_mode,
					if(p.payment_mode='UPI','UPI',p.payment_mode)as payment_mode,
					if(p.payment_mode='DC','DC',p.payment_mode)as payment_mode,
					if(p.payment_type='Cash Free' || p.payment_type='Manual','Online',p.payment_type)as payment_type,
					IF(s.gst_type=0,(p.payment_amount-(p.payment_amount*(100/(100+s.gst))))/2,((p.payment_amount*(s.gst/100))/2)) as sgst,					
					IF(s.gst_type=0,(p.payment_amount-(p.payment_amount*(100/(100+s.gst))))/2,((p.payment_amount*(s.gst/100))/2)) as cgst					
					FROM sch_classify sc
					 join company compy
					 join chit_settings cs
					LEFT JOIN scheme s ON (sc.id_classification = s.id_classification)
					  LEFT JOIN scheme_account sa ON (s.id_scheme = sa.id_scheme)
					  LEFT JOIN payment p ON (sa.id_scheme_account = p.id_scheme_account)
					  Left Join branch b On (b.id_branch=p.id_branch)
					  LEFT JOIN postdate_payment pp ON (sa.id_scheme_account = pp.id_scheme_account)
						WHERE sc.active=1 AND (p.payment_status=1 or pp.payment_status=1)
							and date(".($date_type!='' ? ($date_type==2 ?"p.custom_entry_date":"p.date_payment") : "p.date_payment").")='$date' 
							".($added_by!='' ? ($added_by==0 ? " and p.added_by=".$added_by."": " and(p.added_by=1 or p.added_by=2)") :'')." 
						GROUP BY p.payment_mode";
						//print_r($sql_1);exit;
	  
	  	//return $payments;
	  	$payment=$this->db->query($sql_1);	
		
	return $payment->result_array();
	}
     
     // Payment Online/offline collection //
	
	// actuall Pending & failed payment taken last 2 days data's only For Auto verify //
	function getPendpayment_Data($previousDay,$currentDay)
	 {
	     	$data = array();
   		$sql="select p.id_branch as id_branch,g.pg_code,p.id_payGateway,p.ref_trans_id as txn_ids,p.payu_id,p.payment_amount as amount,p.payment_status,p.date_payment from payment p 
   		      Left Join gateway g on (g.id_pg=p.id_payGateway) 
   		      where (date(p.date_payment) BETWEEN '".date('Y-m-d',strtotime($previousDay))."' AND '".date('Y-m-d',strtotime($currentDay))."')  And (p.payment_status='3' OR p.payment_status='7')";
		  // print_r($sql);exit;
		  //$result=  $this->db->query($sql)->row_array();
		  	$result =$this->db->query($sql);
		  //print_r($result);exit;
		  
		  
		  		if($result->num_rows() > 0)
			    {
                	foreach($result->result() as $row)
				{
				
				$data[]=    array('id_branch' => $row->id_branch,
				                   'pg_code' => $row->pg_code,
				                    'txn_ids' => $row->txn_ids,
				                    ); 
				           
			}
			    }
		  	else {
		  	    
                 echo "No records to verify.";
          }
         // print_r($data);exit;
		return $data;   	
	 }
	 
	  //Autodebit subscription Status Report//HH
	  function ajax_get_customers_lists($param)
    {
		$customers=$this->db->query("select c.id_customer, IFNULL(c.mobile,'')as mobile,c.firstname
				from  customer c  
									where  (c.firstname LIKE '$param%'  OR c.mobile LIKE '$param%')");
		 // print_r($this->db->last_query());exit;
		return $customers->result_array();
	}
	 
	 function ajax_get_autodebit_subscription($from_date,$to_date,$id_customer)
     {
         $sql="select c.id_customer,sa.id_scheme_account, sa.account_name,c.firstname as name,c.mobile,
         IFNULL(Date_format(sa.date_upd,'%d-%m%-%Y'),'-') as date_upd,IF(sa.auto_debit_status=1,'INITIALIZED',IF(sa.auto_debit_status = 2 , 'BANK_APPROVAL_PENDING',IF(sa.auto_debit_status=3,'ACTIVE',IF(sa.auto_debit_status=4,'ON_HOLD',IF(sa.auto_debit_status=5,'CANCELLED',IF(sa.auto_debit_status=6,'COMPLETED','')))))) as auto_debit_status,sa.id_branch,b.name as branch_name,
         IFNULL(sa.scheme_acc_number,'Not Allocated') as scheme_acc_number
         from scheme_account sa
         left join customer c on c.id_customer=sa.id_customer
         Left Join branch b On b.id_branch=sa.id_branch
         Where sa.auto_debit_status>0  
         ".($id_customer!='' ? " and c.id_customer=".$id_customer."" :'')."
         ".($_POST['id_branch'] > 0 ? " and sa.id_branch=".$_POST['id_branch']."" :'')." order by sa.id_scheme_account desc";
         
       // print_r($sql);exit;
         return $this->db->query($sql)->result_array();
     }
     //Autodebit subscription Status Report//
}
?>
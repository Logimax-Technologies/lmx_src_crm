<?php

if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account_model extends CI_Model

{

	const ACC_TABLE 		= "scheme_account";

	const CUS_TABLE			= "customer";

	const SCH_TABLE			= "scheme";

	const PAY_TABLE			= "payment";

	const REG_TABLE			= "registration";

	const ADD_TABLE			= "address";

	const SYNC_TABLE		= "sync_log";

	const OTP_TABLE			= "otp";

	const SCHGROUP_TABLE    = "scheme_group";

	const BRANCH   			 = "branch";


	function __construct()

    {

        parent::__construct();

    }

    

    function account_empty_record()

    {

		$data=array(

			'id_scheme_account'	=> 0,

			'id_scheme'			=> 0,

			'id_customer'		=> 0,

			'scheme_acc_number'	=> NULL,

			'customer'	        => NULL,

			'account_name'		=> NULL,

			'ref_no'			=> NULL,

			'scheme_type' 		=> NULL,

			'paid_installments'	=> 0,	

			'is_opening'		=> 0,

			'balance_amount'	=> '0.00',

			'balance_weight'	=> '0.000',

			'last_paid_weight'	=> '0.000',

			'last_paid_chances'	=> 0,

			'last_paid_date'	=> NULL,

			'start_date'		=> date('d-m-Y'),

			'employee_approved'	=> 0,	

			'active'			=> 1,	

			'disable_payment'	=> 0,	
			'show_gift_article'	=> 0,	

			'is_new'			=> 'Y',	
			
			'is_refferal_by'	=>NULL,
			
			'referal_code'		=>NULL,

			'remark_open'		=> NULL,
			
			'id_branch'		    => NULL

		);

		return $data;

	}

	

	function getActiveAccounts($id="")

	{

		if($id!=NULL)

		{  

			$sql="Select

					  sa.id_scheme_account,sa.scheme_acc_number,IF(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,sa.ref_no,sa.account_name,sa.start_date,c.is_new,
					  		

					  s.scheme_name,sa.is_new,s.code,if(s.scheme_type=0,'Amount','Weight')as scheme_type,s.total_installments,s.max_chance,s.max_weight,s.amount,c.mobile,if(sa.active =1,'Active','Inactive') as active,sa.date_add

					From

					  ".self::ACC_TABLE." sa

					Left Join ".self::CUS_TABLE." c On (sa.id_customer=c.id_customer)

					Left Join ".self::SCHGROUP_TABLE." sg On (sa.id_scheme=sg.id_scheme)

					Left Join ".self::SCH_TABLE." s On (s.id_scheme=sa.id_scheme)

					Where  (sa.active=1 And sa.is_closed=0 And c.active =1) And sa.id_scheme_account=".$id;

					return $this->db->query($sql)->row_array();

		}

		else

		{

			$sql="Select

					  sa.id_scheme_account,sa.scheme_acc_number,IF(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,sa.ref_no,sa.account_name,sa.start_date,c.is_new,
					  		sg.end_date as end_date,
					  s.scheme_name,sa.is_new,s.code,if(s.scheme_type=0,'Amount',if(s.scheme_type=1,'Weight','Amount to Weight'))as scheme_type,s.total_installments,s.max_chance,s.max_weight,s.amount,c.mobile,if(sa.active =1,'Active','Inactive') as active,sa.date_add

					From

					  ".self::ACC_TABLE." sa

					Left Join ".self::CUS_TABLE." c On (sa.id_customer=c.id_customer)

				

					Left Join ".self::SCH_TABLE." s On (s.id_scheme=sa.id_scheme)

					Where  sa.active=1 And sa.is_closed=0 And c.active =1";

						return $this->db->query($sql)->result_array();

		}

		

	}

	

	// function getAmountSchemeAccounts($id="")

	// {

		

	// 	if($id!=NULL)

	// 	{  

	// 		$sql="Select

	// 				  sa.id_scheme_account,IFNULL(sa.scheme_acc_number,'Not Allocated') as scheme_acc_number,IF(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,sa.ref_no,sa.account_name,sa.start_date,c.is_new,

	// 				  s.scheme_name,sa.is_new,s.code,if(s.scheme_type=0,'Amount',if(s.scheme_type=1,'Weight','Amount to Weight'))as scheme_type,s.total_installments,s.max_chance,s.max_weight,s.amount,c.mobile,if(sa.active =1,'Active','Inactive') as active,sa.date_add

	// 				From

	// 				  ".self::ACC_TABLE." sa

	// 				Left Join ".self::CUS_TABLE." c On (sa.id_customer=c.id_customer)

	// 				Left Join ".self::SCH_TABLE." s On (s.id_scheme=sa.id_scheme)

	// 				Where  (sa.active=1 And sa.is_closed=0 And c.active =1) And (s.scheme_type=0 or s.scheme_type=1 or s.scheme_type=2 or s.scheme_type=3 ) And sa.id_scheme_account=".$id;

	// 				return $this->db->query($sql)->row_array();

	// 	}

	// 	else

	// 	{

	// 		$sql="Select

	// 				  sa.id_scheme_account,IFNULL(sa.scheme_acc_number,'Not Allocated') as scheme_acc_number,IF(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,sa.ref_no,sa.account_name,sa.start_date,c.is_new,

	// 				  s.scheme_name,sa.is_new,s.code,if(s.scheme_type=0,'Amount',if(s.scheme_type=1,'Weight','Amount to Weight'))as scheme_type,s.total_installments,s.max_chance,s.max_weight,s.amount,c.mobile,if(sa.active =1,'Active','Inactive') as active,sa.date_add

	// 				From

	// 				  ".self::ACC_TABLE." sa

	// 				Left Join ".self::CUS_TABLE." c On (sa.id_customer=c.id_customer)

	// 				Left Join ".self::SCH_TABLE." s On (s.id_scheme=sa.id_scheme)

	// 				Where  sa.active=1 And sa.is_closed=0 And c.active =1 And (s.scheme_type=0 or s.scheme_type=1 or s.scheme_type=2 or s.scheme_type=3 )";

	// 					return $this->db->query($sql)->result_array();

	// 	}

		

	// }

	function getAmountSchemeAccounts($id="")

	{

				$id_scheme = $this->input->post('id_scheme');
				$id_customer = $this->input->post('id_customer');

		if($id!=NULL)

		{  

			$sql="Select

					  sa.id_scheme_account,
					  if(cs.has_lucky_draw=1,concat(concat(ifnull(sa.group_code,''),' ',ifnull(sa.scheme_acc_number,'Not Allocated')),' - ',s.code ),concat(s.code,ifnull(sa.scheme_acc_number,'Not Allcoated')))as scheme_acc_number,
					  IF(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) asname,sa.ref_no,sa.account_name,c.is_new,sg.group_code,c.mobile,
					  	
					  s.scheme_name,sa.is_new,s.code,if(s.scheme_type=0,'Amount',if(s.scheme_type=1,'Weight','Amount to Weight'))as scheme_type,s.total_installments,s.max_chance,s.max_weight,s.amount,c.mobile,if(sa.active =1,'Active','Inactive') as active,sa.date_add

					From

					  ".self::ACC_TABLE." sa

					Left Join ".self::CUS_TABLE." c On (sa.id_customer=c.id_customer)

						Left Join ".self::SCHGROUP_TABLE." sg On (sa.id_scheme=sg.id_scheme)

					Left Join ".self::SCH_TABLE." s On (s.id_scheme=sa.id_scheme)
					
					Join chit_settings cs

					Where  (sa.active=1 And sa.is_closed=0 And c.active =1) And (s.scheme_type=0 or s.scheme_type=1 or s.scheme_type=2 or s.scheme_type=3 )  And sa.id_scheme_account=".$id;

					return $this->db->query($sql)->row_array();

		}

		else

		{
			$branchWiseLogin=$this->session->userdata('branchWiseLogin');
			$is_branchwise_cus_reg=$this->session->userdata('is_branchwise_cus_reg');
			$id_branch=$this->session->userdata('id_branch');
			$uid=$this->session->userdata('uid');

			$sql="Select

					  sa.id_scheme_account,
					  if(cs.has_lucky_draw=1,concat(concat(ifnull(sa.group_code,''),' ',ifnull(sa.scheme_acc_number,'Not Allocated')),' - ',s.code ),concat(s.code,ifnull(sa.scheme_acc_number,'Not Allcoated')))as scheme_acc_number,c.mobile,
					  IF(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,sa.ref_no,sa.account_name,sa.start_date,sg.group_code,sg.end_date,c.is_new,

					  s.scheme_name,sa.is_new,s.code,if(s.scheme_type=0,'Amount',if(s.scheme_type=1,'Weight','Amount to Weight'))as scheme_type,s.total_installments,s.max_chance,s.max_weight,s.amount,c.mobile,if(sa.active =1,'Active','Inactive') as active,sa.date_add

					From

					  ".self::ACC_TABLE." sa

					Left Join ".self::CUS_TABLE." c On (sa.id_customer=c.id_customer)

						Left Join ".self::SCHGROUP_TABLE." sg On (sa.id_scheme=sg.id_scheme)

					Left Join ".self::SCH_TABLE." s On (s.id_scheme=sa.id_scheme)

						left join ".self::BRANCH." b on (b.id_branch=sa.id_branch)

					Join chit_settings cs

					Where  sa.active=1 And sa.is_closed=0 ".($id_customer!='' ?"and sa.id_customer=".$id_customer."" :'')." ".($id_scheme!='' ? "and sa.id_scheme=".$id_scheme."":'')." And c.active =1 And  (s.scheme_type=0 or s.scheme_type=1 or s.scheme_type=2 or s.scheme_type=3 ) 
					".($uid!=1?($branchWiseLogin==1||$is_branchwise_cus_reg?($id_branch!='' ?"and b.id_branch=".$id_branch." or b.show_to_all=1":''):''):'')."
					";
							
						return $this->db->query($sql)->result_array();

		}

		

	}

		

	function set_registration_record($id_customer,$id_scheme,$id_register)

    {

		$data=array(

		    'id_register'		=> $id_register,

			'id_scheme_account'	=> 0,

			'id_scheme'			=> $id_scheme,

			'id_customer'		=> $id_customer,

			'account_name'		=> NULL,

			'ref_no'			=> NULL,

			'paid_installments'			=> 0,

			'start_date'		=> date('d-m-Y'),

			'employee_approved'	=> 0,	

			'remark_open'		=> NULL

		);

		return $data;

	}

	

	/*  //Generate 10 digit Account number random

	

	function account_number_generator()

	{

	  $query = $this->db->query("SELECT LPAD(round(rand() * 1000000000),10,0) as myCode

								FROM scheme_account

								HAVING myCode NOT IN (SELECT scheme_acc_number FROM scheme_account) limit 0,1");

		if($query->num_rows()==0){

			$query = $this->db->query("SELECT LPAD(round(rand() * 1000000000),10,0) as myCode");

		}

		return $query->row()->myCode;

	}*/

	

	//Generate account number 

	

	function account_number_generator($id_scheme)

	{

	  $lastno=$this->get_schAccount_no($id_scheme);
	  
//print_r($lastno);exit;

	  if($lastno!=NULL)

		{

		  	$number = (int) $lastno;

		  	$number++;
			
			

			$schAc_number=str_pad($number, 5, '0', STR_PAD_LEFT);;
			
			
	//print_r($schAc_number);exit;	
    		return $schAc_number;

		}

		else

		{

				$schAc_number=str_pad('1', 5, '0', STR_PAD_LEFT);;
				
			

    		return $schAc_number;

			 

		}



	}

	 function get_schAccount_no($id_scheme)

    {
		

		$sql = "SELECT max(scheme_acc_number) as lastSchAcc_no FROM scheme_account where id_scheme=".$id_scheme." AND scheme_acc_number != 'Not Allocated' ORDER BY id_scheme_account DESC ";
		
		//$sql = "SELECT max(TRIM(LEADING '0' FROM scheme_acc_number))  as lastSchAcc_no FROM scheme_account where id_scheme=".$id_scheme." ORDER BY id_scheme_account DESC ";

		
		
			
		return $this->db->query($sql)->row()->lastSchAcc_no;	



	}

	

	

	//check reference exists

	function is_refno_exists($ref_no,$schid)

	{

		$this->db->select('ref_no');

		$this->db->where('scheme_acc_number', $ref_no); 

		$this->db->where('id_scheme', $schid); 

		$status=$this->db->get(self::ACC_TABLE);

		if($status->num_rows()>0)

		{

			

			 return TRUE;

		}

	}

	

	//get scheme_account by customer

	function is_uniqueCode_exists($id_customer)

	{

		$this->db->select('ref_no');

		$this->db->where('id_customer', $id_customer); 

		$status=$this->db->get(self::ACC_TABLE);

	    

		if($status->num_rows()>0)

		{

			

		

			if($status->row()->ref_no=="")

			{

			   return FALSE;	

			}

			else

			{

			   return TRUE;

			}

		}

	}

	function updateUniqueCode($data,$id_customer)

	{

		

		$this->db->where('id_customer',$id_customer);

		$status=$this->db->update(self::ACC_TABLE,$data);

		return $status;

		

	}



//for scheme join sms and mail

	function get_customer_acc($id_scheme_acc)

	{

		$accounts=$this->db->query("select
							  s.id_scheme_account,s.id_branch as sch_join_branch,c.id_branch as cus_reg_branch,sc.id_scheme,
							  if(cs.has_lucky_draw=1,concat(ifnull(s.group_code,''),'',ifnull(s.scheme_acc_number,'Not allocated')),concat(ifnull(sc.code,''),' ',ifnull(s.scheme_acc_number,'Not allocated'))) as scheme_acc_number,
							  IF(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,s.ref_no,c.firstname,ifnull(s.account_name,IF(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname))) as account_name,s.start_date,c.is_new,c.email,sc.min_amount, sc.max_amount,  							  
							  sc.scheme_type, 
							  sc.scheme_name,s.is_new,sc.code,sc.total_installments,sc.max_chance,sc.payment_chances,sc.max_weight,sc.min_weight,
							  sc.amount,c.mobile,if(s.active =1,'Active','Inactive') as active,s.date_add,cs.currency_name,cs.currency_symbol

							from

							  ".self::ACC_TABLE." s

							left join ".self::CUS_TABLE." c on (s.id_customer=c.id_customer)

							left join ".self::SCH_TABLE." sc on (sc.id_scheme=s.id_scheme)

							join chit_settings cs 

							where  s.is_closed=0 and s.id_scheme_account =".$id_scheme_acc);

		return $accounts->row_array();

	}	

	

	

	// for all active and not closed records

	function get_all_account()

	{

		$accounts=$this->db->query("select

							  s.id_scheme_account,s.scheme_acc_number,IF(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,s.ref_no,s.account_name,s.start_date,c.is_new,c.id_customer,

							  sc.scheme_name,s.is_new,sc.code,if(sc.scheme_type=0,'Amount',if(sc.scheme_type=1,'Weight','Amount to Weight'))as scheme_type,sc.total_installments,sc.max_chance,sc.max_weight,sc.amount,c.mobile,if(s.active =1,'Active','Inactive') as active,s.date_add

							from

							  ".self::ACC_TABLE." s

							left join ".self::CUS_TABLE." c on (s.id_customer=c.id_customer)

							left join ".self::SCH_TABLE." sc on (sc.id_scheme=s.id_scheme)

							where  s.is_closed=0");

		return $accounts->result_array();

	}	

	

	

	function get_customer_accounts($id_customer)

	{

		$accounts=$this->db->query("select

							  s.id_scheme_account,s.scheme_acc_number,IF(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,s.ref_no,ifnull(s.account_name,IF(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname))) as account_name,s.start_date,c.is_new,
								c.id_branch as cus_ref_branch,s.id_branch as sch_join_branch,
							  sc.scheme_name,s.is_new,sc.code,if(sc.scheme_type=0,'Amount',if(sc.scheme_type=1,'Weight','Amount to Weight'))as scheme_type,sc.total_installments,sc.max_chance,sc.max_weight,sc.amount,c.mobile,if(s.active =1,'Active','Inactive') as active,s.date_add

							from

							  ".self::ACC_TABLE." s

							left join ".self::CUS_TABLE." c on (s.id_customer=c.id_customer)

							left join ".self::SCH_TABLE." sc on (sc.id_scheme=s.id_scheme)

							where  s.is_closed=0 and c.id_customer =".$id_customer);
							
							//print_r($this->db->last_query());exit;

		return $accounts->result_array();

	}
	


	

/*-- Coded by ARVK --*/			 

	function sch_acc_count()

	{

		$sql = "SELECT id_scheme_account FROM scheme_account";

		return $this->db->query($sql)->num_rows();

	}	

	

	function company_details()

	{

		$sql = "SELECT * FROM company WHERE id_company=1";

		return $this->db->query($sql)->row_array();

	}	

	

	function otp_insert($data)

	{

		$status = $this->db->insert(self::OTP_TABLE,$data);

		return $status;

	}	

	

	function otp_update($data,$id)

	{

		$this->db->where('id_sch_acc',$id);

		$status=$this->db->update(self::OTP_TABLE,$data);

		return $status;

	}	

	

	function otp_select($id)

	{

		$this->db->select('id_sch_acc');

		$this->db->where('id_sch_acc',$id);

		$status=$this->db->get(self::OTP_TABLE);

		if($status->num_rows()>0)

		{

			

			 return TRUE;

		}

	}	

	

	function otp_code_select($id)

	{

		$this->db->select('*');

		$this->db->where('id_sch_acc',$id);

		$status=$this->db->get(self::OTP_TABLE);

		return $status->row_array();

	}	

	

	

/*-- / Coded by ARVK --*/	

	

	

	

	

	function get_all_account_details()

	{

		$accounts=$this->db->query("select

							  s.id_scheme_account,

							  s.scheme_acc_number,

							  IF(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name, 					                              

							  c.id_customer,

							  s.ref_no,

							  s.account_name,

							   DATE_FORMAT(s.start_date,'%d-%m-%Y') as start_date,

							  c.is_new,

							  sc.scheme_name,

							  s.is_new,

							  sc.code,

							  if(sc.scheme_type=0,'Amount',if(sc.scheme_type=1,'Weight','Amount to Weight'))as scheme_type,

							  sc.total_installments,

							  sc.max_chance,

							  sc.max_weight,

							  sc.amount,

							  c.mobile,

							  if(s.active =1,'Active','Inactive') as active,

							  s.date_add,

							  is_opening,

							  IFNULL(cur_pay.curpay_install,0) +  IF(s.is_opening=1, IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(s.last_paid_date,'%Y%m'),1,0),0) AS curpay_install,

							  IFNULL(cur_pay.curpay_amount,0) AS curpay_amount,

							  IFNULL(cur_pay.curpay_weight,0) +  IF(s.is_opening=1 and sc.scheme_type = 1, IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(s.last_paid_date,'%Y%m'),IFNULL(s.last_paid_weight,0),0),0) AS curpay_weight,

							  IFNULL(total_pay.totalpay_install,0) + IF(s.is_opening=1,IFNULL(s.paid_installments,0),0) AS totalpay_install,

							  IFNULL(total_pay.totalpay_amount,0) + IF(s.is_opening=1,IFNULL(s.balance_amount,0),0) AS totalpay_amount,

							  IFNULL(total_pay.totalpay_weight,0) + IF(s.is_opening=1 and sc.scheme_type = 1,IFNULL(balance_weight,0),0) AS totalpay_weight,

							  (IFNULL(cur_pay.chances,0) + IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(s.last_paid_date,'%Y%m'),(s.last_paid_chances),0)) as  chances_used

							FROM

							  ".self::ACC_TABLE." s

							LEFT JOIN ".self::CUS_TABLE." c on (s.id_customer=c.id_customer)

							LEFT JOIN ".self::SCH_TABLE." sc on (sc.id_scheme=s.id_scheme)

							LEFT JOIN (SELECT id_scheme_account,IFNULL(COUNT(DISTINCT(DATE_FORMAT(date_payment,'%Y%m'))),0) AS curpay_install, SUM(IFNULL(payment_amount,0)) AS curpay_amount, SUM(IFNULL(metal_weight,0)) AS curpay_weight,IFNULL(COUNT(DATE_FORMAT(date_payment,'%Y%m')),0) AS chances FROM ".self::PAY_TABLE." WHERE (payment_status = 0 OR payment_status = 1) AND DATE_FORMAT(date_payment,'%Y%m') = DATE_FORMAT(CURDATE(),'%Y%m') GROUP BY id_scheme_account) AS cur_pay ON cur_pay.id_scheme_account = s.id_scheme_account

							LEFT JOIN (SELECT id_scheme_account,COUNT(DISTINCT(DATE_FORMAT(date_payment,'%Y%m'))) AS totalpay_install, SUM(IFNULL(payment_amount,0)) AS totalpay_amount, SUM(IFNULL(metal_weight,0)) AS totalpay_weight FROM ".self::PAY_TABLE." WHERE (payment_status = 0 OR payment_status = 1) GROUP BY id_scheme_account) AS total_pay ON total_pay.id_scheme_account = s.id_scheme_account

							where  s.is_closed=0

							GROUP BY s.id_scheme_account

							");

		return $accounts->result_array();

	}	

	function get_export_data($filter="",$from_date="",$to_date="")

	{

		$sql="select

							  s.id_scheme_account,s.ref_no,concat (c.firstname,' ',if(c.lastname!=Null,c.lastname,'')) as name,c.mobile,s.start_date,sc.code,s.date_add

							from

							  ".self::ACC_TABLE." s

							left join ".self::CUS_TABLE." c on (s.id_customer=c.id_customer)

							left join ".self::SCH_TABLE." sc on (sc.id_scheme=s.id_scheme)

							where s.active=1 and s.is_closed=0 ";

	  switch($filter){

	  	case 0:

	  	         if($from_date!=NULL and $to_date!=NULL)

					{

						$sql=$sql." AND (date(s.date_add) BETWEEN '".$from_date."' AND '".$to_date."') ";

					

					}

					else

					{

						$sql=$sql." And date(s.date_add) ='".$from_date."'";

					}

	  			

	  		break;

	  		case 1:

				  	if($from_date!=NULL and $to_date!=NULL)

				  	{

						$sql=$sql." AND (date(s.date_add)  BETWEEN '".$from_date."' AND '".$to_date."') And s.ref_no IS Not Null";

					

					}

					else

					{

						$sql=$sql." And date(s.date_add) ='".$from_date."' And  s.ref_no IS Not Null";

					}

	  				

	  		break;

	  	case 2:

	  				if($from_date!=NULL and $to_date!=NULL)

					{

						$sql=$sql." AND (date(s.date_add)  BETWEEN '".$from_date."' AND '".$to_date."') And s.ref_no IS NULL";

					

					}

					else

					{

						$sql=$sql." And date(s.date_add) ='".$from_date."' And s.ref_no IS NULL";

					}

	  	       

	  		break;

	  	

	  	  }					

		

		return $this->db->query($sql)->result_array();					

	}

	

//for getting closing request from customer	

	function get_closing_request()

	{

		$accounts=$this->db->query("select

							  s.id_scheme_account,s.scheme_acc_number,concat (c.firstname,' ',if(c.lastname!=Null,c.lastname,'')) as name,s.ref_no,s.account_name,s.start_date,

							  sc.scheme_name,sc.code,if(sc.scheme_type=0,'Amount',if(sc.scheme_type=1,'Weight','Amount to Weight'))as scheme_type,sc.total_installments,sc.max_chance,sc.amount,c.mobile,

							  (if(s.paid_installments is null,0,s.paid_installments) + if(count(distinct month(p.date_payment)) is null,0,count(distinct month(p.date_payment)))) as paid_installments,

      (if(sc.total_installments is null,0,sc.total_installments) -     (if(s.paid_installments is null,0,s.paid_installments) + if(count(distinct month(p.date_payment)) is null,0,count(distinct month(p.date_payment))))) as pending_installments,

       sc.max_chance,sc.amount,c.mobile,

       if(sc.scheme_type=0,(if(s.balance_amount IS NULL,0,s.balance_amount))+if(sum(p.payment_amount) is null,0,sum(p.payment_amount)),'0.00') as closing_amount,

             if(sc.scheme_type=0,(if(s.balance_amount IS NULL,0,s.balance_amount))+if(sum(p.payment_amount) is null,0,sum(p.payment_amount)),(if(s.balance_weight IS NULL,0,s.balance_weight))+if(sum(p.metal_weight) is null,0,sum(p.metal_weight))) as closing_balance,

       remark_close

							from

							  ".self::ACC_TABLE." s

							left join ".self::CUS_TABLE." c on (s.id_customer=c.id_customer)

							left join ".self::SCH_TABLE." sc on (sc.id_scheme=s.id_scheme)

							left join ".self::PAY_TABLE." p on (s.id_scheme_account=p.id_scheme_account)						

							where s.req_close=1 and s.active=1 and s.is_closed=0

							group by s.id_scheme_account ");

		return $accounts->result_array();

	}

	

/* -- Coded by ARVK -- */

	//for single closed account detail

	 function get_closed_account_by_id($id)

	{

		$account=$this->db->query("select

			sa.id_scheme_account,IFNULL(sa.scheme_acc_number,'Not Allocated') as scheme_acc_number,s.total_installments,

			concat (c.firstname,' ',if(c.lastname!=Null,c.lastname,'')) as name,cs.has_lucky_draw,IFNULL(sa.group_code,'')as scheme_group_code,
			
			IFNULL(IF(sa.is_opening=1,IFNULL(sa.paid_installments,0)+ IFNULL(if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues)),0), if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues))) ,0)
  as paid_installments,

			c.mobile,sa.account_name,c.nominee_name,c.nominee_mobile,s.scheme_name,			
			 if(s.scheme_type=0,'Amount',if(s.scheme_type=1,'Weight',if(s.scheme_type=3,'FLEXIBLE_AMOUNT','Amount To Weight')))as scheme_type,							  

			s.amount as sch_amt,s.scheme_type as sch_typ,s.code,
			
			 IFNULL(Date_format(sa.start_date,'%d-%m%-%Y'),'-') as start_date,						  
			 IFNULL(Date_format(sa.closing_date,'%d-%m%-%Y'),'-') as closing_date,		

			(if(sa.balance_amount IS NULL,0,sa.balance_amount))+if(sum(p.payment_amount) is null,0,sum(p.payment_amount)) as total_paid,

			if(s.interest=1,s.total_interest,'0.00') as interest,if(s.tax=1,s.total_tax,'0.00') as tax,

			 if(sum(p.add_charges)!='',sum(p.add_charges),'-') as bank_chgs,

			sa.closing_add_chgs,IFNULL(sa.additional_benefits,0.00) as additional_benefits,

			if(s.scheme_type=0,CONCAT(cs.currency_symbol,' ',sa.closing_balance),sa.closing_balance) as closing_balance,

			if(sa.closed_by=1,sa.rep_name,concat (c.firstname,' ',if(c.lastname!=Null,c.lastname,'')))as closed_by,

			if(sa.closed_by=1,'Nominee','Self')as closedBy,sa.employee_closed,

			

				(select concat (e.firstname,' ',if(e.lastname!=Null,e.lastname,''))

				from employee e where id_employee=sa.employee_closed) as emp_name,

				

			if(sa.remark_close!='',sa.remark_close,'-')as remark_close,

			if(sa.closed_by=1,sa.rep_mobile,c.mobile)as otp_verified_mob,cs.currency_symbol



					from  ".self::ACC_TABLE." sa

					left join ".self::CUS_TABLE." c on (sa.id_customer=c.id_customer)

					left join ".self::SCH_TABLE." s on (sa.id_scheme=s.id_scheme)

					left join ".self::PAY_TABLE." p on (sa.id_scheme_account=p.id_scheme_account)

					join chit_settings cs

					where sa.active=0 and sa.is_closed=1 and p.payment_status=1 and sa.id_scheme_account=".$id);

			return $account->row_array();

	}

/* /-- Coded by ARVK -- */

		

	//for all closed account

	 function get_all_closed_account()

	{
		$branchWiseLogin=$this->session->userdata('branchWiseLogin');
		$is_branchwise_cus_reg=$this->session->userdata('is_branchwise_cus_reg');
			$id_branch=$this->session->userdata('id_branch');
			$uid=$this->session->userdata('uid');

		$accounts=$this->db->query("select

							  s.id_scheme_account,sc.code,IFNULL(s.group_code,'')as scheme_group_code,IFNULL(s.scheme_acc_number,'NOT Allocated')as scheme_acc_number,cs.has_lucky_draw,
							  concat (c.firstname,' ',if(c.lastname!=Null,c.lastname,'')) as name,

							  s.ref_no, s.closing_add_chgs, s.account_name,

							  IFNULL(Date_format(s.start_date,'%d-%m%-%Y'),'-') as start_date,

							  IFNULL(Date_format(s.closing_date,'%d-%m%-%Y'),'-') as closing_date,

							  if(sc.scheme_type=0,CONCAT(cs.currency_symbol,' ',s.closing_balance),s.closing_balance) as closing_balance,

							  c.added_by,sc.scheme_name,sc.code,
							  
							  if(sc.scheme_type=0,'Amount',if(sc.scheme_type=1,'Weight',if(sc.scheme_type=3,'FLEXIBLE_AMOUNT','Amount To Weight')))as scheme_type,
							  FORMAT(if(sc.scheme_type=1,CONCAT('max ',sc.max_weight,' g/month'),if(sc.scheme_type=3 && sc.max_amount!=0,sc.max_amount,if(sc.scheme_type=3 && sc.max_amount=0,(sc.max_weight*(SELECT m.goldrate_22ct FROM metal_rates m  order by id_metalrates Desc LIMIT 1)),sc.amount))),2) as amount					,sc.total_installments,sc.max_chance,c.mobile

							from

							  ".self::ACC_TABLE." s

							left join ".self::CUS_TABLE." c on (s.id_customer=c.id_customer)

							left join ".self::SCH_TABLE." sc on (sc.id_scheme=s.id_scheme)

							left join ".self::BRANCH." b on (b.id_branch=s.id_branch)

							join chit_settings cs

							where s.active=0 and s.is_closed=1 ".($uid!=1 ? ($branchWiseLogin==1||$is_branchwise_cus_reg==1? ($id_branch!='' ?  " and( s.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')."");

		return $accounts->result_array();

	}

	

		// for all active and not closed records

	function get_all_account_by_range($from_date,$to_date,$id_scheme)

	{
			$branchWiseLogin=$this->session->userdata('branchWiseLogin');
			$is_branchwise_cus_reg=$this->session->userdata('is_branchwise_cus_reg');
			$branch=$this->session->userdata('id_branch');
			
			$uid=$this->session->userdata('uid');
			
					if($this->branch_settings==1)
					{
						$id_branch  = $this->input->post('id_branch');
					}
					else{
							$id_branch = '';
					 }
			

		$accounts=$this->db->query("select IFNULL(s.pan_no,'-') as pan_no,if(s.show_gift_article=1,'Issued','Not Issueed')as gift_article,

							  s.id_scheme_account,IFNULL(s.scheme_acc_number,'Not Allocated') as scheme_acc_number ,IF(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,s.ref_no,s.account_name,DATE_FORMAT(s.start_date,'%d-%m-%Y') as start_date,c.is_new,s.added_by,concat('C','',c.id_customer) as id_customer,cs.schemeacc_no_set,

							  sc.scheme_name,if(s.is_new ='Y','New','Existing') as is_new,sc.code,if(sc.scheme_type=0,'Amount',if(sc.scheme_type=1,'Weight','Amount to Weight'))as scheme_type,sc.total_installments,sc.max_chance,sc.max_weight,sc.amount,c.mobile,if(s.active =1,'Active','Inactive') as active,s.date_add,cs.currency_symbol,sc.scheme_type  as scheme_types,
						
		
		IFNULL(IF(s.is_opening=1,IFNULL(s.paid_installments,0)+ IFNULL(if(sc.scheme_type = 1 and sc.min_weight != sc.max_weight , COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')), sum(pay.no_of_dues)),0), if(sc.scheme_type = 1 and sc.min_weight != sc.max_weight , COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')), sum(pay.no_of_dues))) ,0)
  as paid_installments

							from

							  ".self::ACC_TABLE." s

							left join ".self::CUS_TABLE." c on (s.id_customer=c.id_customer)

							left join ".self::SCH_TABLE." sc on (sc.id_scheme=s.id_scheme)
							
							left join ".self::BRANCH." b on (b.id_branch=s.id_branch)

							left join ".self::PAY_TABLE." pay on (pay.id_scheme_account=s.id_scheme_account  and (pay.payment_status=2 or pay.payment_status=1))

							join chit_settings cs

							 Where ( s.is_closed=0 and  date(s.start_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') ".($id_scheme!='' ?"and sc.id_scheme=".$id_scheme."" :'')." ".($uid!=1 ? ($branchWiseLogin==1 || $is_branchwise_cus_reg==1? ($id_branch!='' ?" and s.id_branch=".$id_branch."" :($branch!=''?"and( s.id_branch=".$branch." or b.show_to_all=1 )" :'')):''):'')." 
								
							group by s.id_scheme_account");
							
//print_r($this->db->last_query());exit;


						
		return $accounts->result_array();

		

	}	
	

	
function get_all_closed_accdetails($id)

	{

		$sql="select

							  s.id_scheme_account,IFNULL(s.scheme_acc_number,'Not Allocated') as scheme_acc_number,

							  concat (c.firstname,' ',if(c.lastname!=Null,c.lastname,'')) as name,

							  s.ref_no, s.closing_add_chgs, s.account_name,

							  IFNULL(Date_format(s.start_date,'%d-%m%-%Y'),'-') as start_date,

							  IFNULL(Date_format(s.closing_date,'%d-%m%-%Y'),'-') as closing_date,

							  if(sc.scheme_type=0,CONCAT(cs.currency_symbol,' ',s.closing_balance),CONCAT(s.closing_balance,' ',' g')) as closing_balance,

							  c.added_by,sc.scheme_name,sc.code,

							  if(sc.scheme_type=1,CONCAT('max ',sc.max_weight,' g/month'),sc.amount) as amount,

							  if(sc.scheme_type=0,'Amount',if(sc.scheme_type=1,'Weight','Amount to Weight'))as scheme_type,sc.total_installments,sc.max_chance,c.mobile

							from

							  ".self::ACC_TABLE." s

							left join ".self::CUS_TABLE." c on (s.id_customer=c.id_customer)

							left join ".self::SCH_TABLE." sc on (sc.id_scheme=s.id_scheme)

							join chit_settings cs

							where s.active=0 and s.is_closed=1 and c.id_customer=".$id."
							ORDER by s.id_scheme_account DESC Limit 1 ";

		 $account=$this->db->query($sql);	   

		return $account->row_array();

	}
	function get_all_closed_acccount($id)

	{

		$accounts=$this->db->query("select

							  s.id_scheme_account,IFNULL(s.scheme_acc_number,'Not Allocated') as scheme_acc_number,

							  concat (c.firstname,' ',if(c.lastname!=Null,c.lastname,'')) as name,

							  s.ref_no, s.closing_add_chgs, s.account_name,

							  IFNULL(Date_format(s.start_date,'%d-%m%-%Y'),'-') as start_date,

							  IFNULL(Date_format(s.closing_date,'%d-%m%-%Y'),'-') as closing_date,

							  if(sc.scheme_type=0,CONCAT(cs.currency_symbol,' ',s.closing_balance),CONCAT(s.closing_balance,' ',' g')) as closing_balance,

							  c.added_by,sc.scheme_name,sc.code,

							  if(sc.scheme_type=1,CONCAT('max ',sc.max_weight,' g/month'),sc.amount) as amount,

							  if(sc.scheme_type=0,'Amount',if(sc.scheme_type=1,'Weight','Amount to Weight'))as scheme_type,sc.total_installments,sc.max_chance,c.mobile

							from

							  ".self::ACC_TABLE." s

							left join ".self::CUS_TABLE." c on (s.id_customer=c.id_customer)

							left join ".self::SCH_TABLE." sc on (sc.id_scheme=s.id_scheme)

							join chit_settings cs

							where s.active=0 and s.is_closed=1 and c.id_customer=".$id."");

		return $accounts->num_rows();

	} 
	function get_closed_acc_by_range($from_date,$to_date)

	{
		
			if($this->branch_settings==1){
				$id_branch  = $this->input->post('id_branch');}
			else{
			$id_branch = '';}
		

		$accounts=$this->db->query("select

							  s.id_scheme_account,s.scheme_acc_number,concat (c.firstname,' ',if(c.lastname!=Null,c.lastname,'')) as name,s.ref_no,s.account_name,IFNULL(Date_format(s.start_date,'%d-%m%-%Y'),'-') as start_date,IFNULL(Date_format(s.closing_date,'%d-%m%-%Y'),'-') as closing_date,if(sc.scheme_type=0,CONCAT(cs.currency_symbol,' ',s.closing_balance),CONCAT(s.closing_balance,' ',' g')) as closing_balance,c.added_by,

							  sc.scheme_name,sc.code,if(sc.scheme_type=0,'Amount',if(sc.scheme_type=1,'Weight','Amount to Weight'))as scheme_type,sc.total_installments,sc.max_chance,sc.amount,c.mobile,sc.scheme_type as sch_typ

							from

							  ".self::ACC_TABLE." s

							left join ".self::CUS_TABLE." c on (s.id_customer=c.id_customer)

							left join ".self::SCH_TABLE." sc on (sc.id_scheme=s.id_scheme)

							join chit_settings cs

							 Where ( s.active=0 and s.is_closed=1 and  date(s.closing_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')

							 ".($id_branch!=NULL?' and s.id_branch ='.$id_branch:'')." ");							

		return $accounts->result_array();

	}

	

	function get_all_scheme_account()

	{
		$branchwiselogin=$this->session->userdata('branchWiseLogin');
		$is_branchwise_cus_reg=$this->session->userdata('is_branchwise_cus_reg');
		$id_branch=$this->session->userdata('id_branch');
		$uid=$this->session->userdata('uid');
	
		$accounts=$this->db->query("select IFNULL(s.pan_no,'-') as pan_no,

								sc.code,IFNULL(s.group_code,'')as scheme_group_code,cs.has_lucky_draw,
							  s.id_scheme_account,IFNULL(s.scheme_acc_number,'Not Allocated') as scheme_acc_number ,IF(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,s.ref_no,s.account_name,DATE_FORMAT(s.start_date,'%d-%m-%Y') as start_date,c.is_new,s.added_by,concat('C','',c.id_customer) as id_customer,

							  sc.scheme_name,if(s.is_new ='Y','New','Existing') as is_new,sc.code,if(sc.scheme_type=0,'Amount',if(sc.scheme_type=1,'Weight',if(sc.scheme_type=3,'FLEXIBLE_AMOUNT','Amount To Weight')))as scheme_type,cs.schemeacc_no_set,
							  FORMAT(if(sc.scheme_type=1,sc.max_weight,if(sc.scheme_type=3 && sc.max_amount!=0,sc.max_amount,if(sc.scheme_type=3 && sc.max_amount=0,(sc.max_weight*(SELECT m.goldrate_22ct FROM metal_rates m  order by id_metalrates Desc LIMIT 1)),sc.amount))),2) as amount,
							  
							  sc.scheme_type  as scheme_types,
							  sc.total_installments,sc.max_chance,sc.max_weight,c.mobile,if(s.active =1,'Active','Inactive') as active,s.date_add,cs.currency_symbol,
							  
							  
							 IFNULL(IF(s.is_opening=1,IFNULL(s.paid_installments,0)+ IFNULL(if(sc.scheme_type = 1 and sc.min_weight != sc.max_weight, COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')), sum(pay.no_of_dues)),0), if(sc.scheme_type = 1 and sc.min_weight != sc.max_weight or sc.scheme_type=3, COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')), sum(pay.no_of_dues))) ,0)
                              as paid_installments
							from

							  ".self::ACC_TABLE." s

							left join ".self::CUS_TABLE." c on (s.id_customer=c.id_customer)

							left join ".self::SCH_TABLE." sc on (sc.id_scheme=s.id_scheme)

							left join ".self::PAY_TABLE." pay on (pay.id_scheme_account=s.id_scheme_account  and (pay.payment_status=2 or pay.payment_status=1))

							left join branch b on (b.id_branch=s.id_branch)

							join chit_settings cs

							
							Where s.is_closed=0 ".($uid!=1 ? ($branchwiselogin==1||$is_branchwise_cus_reg?($id_branch!=''? 
								"and s.id_branch=".$id_branch." or b.show_to_all=1 ":'') :'') :'')."

							group by s.id_scheme_account");

				

		return $accounts->result_array();

	}

	

	function get_pay_detail($id)

	{

		$this->db->select('id_scheme_account,account_name,scheme.amount');

		$this->db->join(self::SCH_TABLE,"scheme_account.id_scheme=scheme.id_scheme");

		$this->db->where('id_scheme_account',$id);

		$pay=$this->db->get(self::ACC_TABLE);

		return $pay->row_array();

	}

	

	function get_account_numbers()

	{

		$this->db->select('id_scheme_account,account_name,ref_no');

		$this->db->where('closed_by');

		$accounts=$this->db->get(self::ACC_TABLE);

		return $accounts->result_array();

	}

	

	function get_accounts_range($lower,$upper)

	{

		$accounts=$this->db->query("select

							  s.id_scheme_account,s.scheme_acc_number,concat (c.firstname,' ',if(c.lastname!=Null,c.lastname,'')) as name,s.ref_no,s.account_name,s.start_date,c.email,

							  sc.scheme_name,sc.code,if(sc.scheme_type=0,'Amount',if(sc.scheme_type=1,'Weight','Amount to Weight'))as scheme_type,sc.total_installments,sc.max_chance,sc.amount,c.mobile,s.id_customer

							from

							  ".self::ACC_TABLE." s

							left join ".self::CUS_TABLE." c on (s.id_customer=c.id_customer)

							left join ".self::SCH_TABLE." sc on (sc.id_scheme=s.id_scheme)

							where s.active=1 and s.is_closed=0 and 

							id_scheme_account Between ".$lower." and ".$upper."

							group by s.id_customer");

							

		return $accounts->result_array();

		

	}

	

	function getSchemeAccountByCustomerID($id_customer)

	{

		$this->db->select('id_scheme_account');

		$this->db->where('id_customer',$id_customer);

		$id_scheme_account=$this->db->get(self::ACC_TABLE);

		if($id_scheme_account->num_rows()==1)

		{

		  return $id_scheme_account->row()->id_scheme_account;

		}

		else

		{

		  return 0;

		}

	}

	

	function get_close_account($id)

	{

		$account=$this->db->query("select

			sa.id_scheme_account as id_scheme_account,c.id_customer,concat (c.firstname,' ',if(c.lastname!=Null,c.lastname,'')) as name,
			sa.additional_benefits,sa.closing_add_chgs,sa.closing_weight,sa.closing_balance as closing_amount,IFNULL(p.receipt_no,'') as receipt_no,

      c.cus_img,sa.ref_no,sa.scheme_acc_number,sa.account_name,c.nominee_name,c.nominee_mobile,c.firstname,

      sa.start_date,s.scheme_name,s.code,c.email,s.min_weight,s.max_weight,
	  
   if(s.scheme_type=0,'Amount',if(s.scheme_type=1,'Weight',if(scheme_type=2,'Amt to Wgt','FLXEBLE_AMOUNT')))as scheme_type,
	  
	  s.total_installments,s.amount,s.scheme_type as sch_typ,s.wgt_convert,

      ifnull(if(s.interest=1 && s.scheme_type=0,s.total_interest,interest_weight),'0.00') as interest,

      if(s.tax=1,s.total_tax,'0.00') as tax,

       IFNULL(IF(sa.is_opening=1,IFNULL(sa.paid_installments,0)+ IFNULL(if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues)),0), if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues))) ,0)
  as paid_installments,
  
   (s.total_installments -IFNULL(IF(sa.is_opening=1,IFNULL(sa.paid_installments,0)+ IFNULL(if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues)),0), if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues))) ,0))
  as pending_installments,	
  
   sum(CASE
    WHEN p.due_type = 'PC' THEN 1
    ELSE 0
END )AS pre_close_payments,s.preclose_benefits,
  

       (select count(id_scheme_account) from payment where (payment_status=2 or payment_status=7) and id_scheme_account=sa.id_scheme_account) as unapproved_payment,s.allow_preclose,s.preclose_months,IFNULL((select sum(charges) from postdate_payment where id_scheme_account=sa.id_scheme_account),0.00) as bank_chgs,

       s.max_chance,s.amount,c.mobile,

	       (if(sa.balance_amount IS NULL,0,sa.balance_amount))+if(sum(p.payment_amount) is null,0,sum(p.payment_amount)) as closing_amount,

             if(s.scheme_type=0 or s.scheme_type=3 ,(if(sa.balance_amount IS NULL,0,sa.balance_amount))+if(sum(p.payment_amount) is null,0,sum(p.payment_amount)),(if(sa.balance_weight IS NULL,0,sa.balance_weight))+if(sum(p.metal_weight) is null,0,sum(p.metal_weight))) as closing_balance,s.payment_chances,

              IFNULL(sum(p.add_charges),0.00) as charges,

	   sa.remark_close,(select enable_closing_otp from chit_settings) as enable_closing_otp,(select enable_closing_otp from chit_settings) as currency_symbol



					from  ".self::ACC_TABLE." sa

					left join ".self::CUS_TABLE." c on (sa.id_customer=c.id_customer)

					left join ".self::SCH_TABLE." s on (sa.id_scheme=s.id_scheme)

					left join ".self::PAY_TABLE." p on (sa.id_scheme_account=p.id_scheme_account)

					join chit_settings cs

					where sa.active=1 and sa.is_closed=0 and p.payment_status=1 and sa.id_scheme_account=".$id);

			return $account->row_array();	

		

	}


	//Get particular account detail


	
	function get_account_open($id)

	{

		$sql="SELECT sa.`id_scheme_account`, if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as customer,c.mobile,c.passwd,s.scheme_name,s.scheme_type,sa.`id_scheme`, sa.`id_customer`, IFNULL(sa.scheme_acc_number,'Not Allocated') as scheme_acc_number,sa.`id_branch`,sa.show_gift_article,

				sa.`is_refferal_by`,sa.`referal_code`,cs.`schemeacc_no_set`,

		`account_name`, `ref_no`, sa.`is_new`,IFNULL(IF(sa.is_opening=1,IFNULL(sa.paid_installments,0)+ IFNULL(if(s.scheme_type = 1 and s.min_weight != s.max_weight, COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')), sum(pay.no_of_dues)),0), if(s.scheme_type = 1 and s.min_weight != s.max_weight or s.scheme_type=3, COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')), sum(pay.no_of_dues))) ,0)
  as `paid_installments`,c.email, sa.paid_installments as previous_paid,

		DATE_FORMAT(start_date, '%d-%m-%Y') as start_date, `employee_approved`, `remark_open`

		, `is_opening`,`balance_amount`,`balance_weight`,`last_paid_date`, `last_paid_weight`,`last_paid_chances`,sa.active

		FROM (`".self::ACC_TABLE."` sa)

		JOIN chit_settings cs 
		
		LEFT JOIN customer c ON (sa.id_customer=c.id_customer)

		LEFT JOIN scheme s ON (sa.id_scheme=s.id_scheme) 
		
		LEFT JOIN ".self::PAY_TABLE." pay on (pay.id_scheme_account=sa.id_scheme_account  and (pay.payment_status=2 or pay.payment_status=1))

		WHERE sa.`id_scheme_account` =".$id;

		 $account=$this->db->query($sql);	   

		return $account->row_array();	

	}
	
	
	

	function get_account_detail($id_scheme_account)

	{

		$sql="SELECT 

		            c.id_customer,   

					c.cus_img,

					s.scheme_name,s.code,cs.has_lucky_draw,sa.closing_balance as closing_balance,IFNULL(p.receipt_no,'') as receipt_no,

					IFNULL(sa.group_code,'')as group_code,s.code,IFNULL(sa.scheme_acc_number,'Not Allocated')as scheme_acc_number,
					
					ifnull((sa.closing_add_chgs+ifnull(sum(p.add_charges),'0.00')+if(s.tax=1,s.total_tax,'0.00')),0.00)as deductions,
					
					ifnull((if(IFNULL(IF(sa.is_opening=1,IFNULL(sa.paid_installments,0)+ if(s.scheme_type = 1, COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), SUM(p.no_of_dues)), if(s.scheme_type = 1, COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), SUM(p.no_of_dues))),0)=s.total_installments,if(s.scheme_type=0,if(s.interest=1,s.total_interest,'0.00'),if(s.interest=1,s.interest_weight,'0.000')),0.00)+sa.additional_benefits),'0.00') as benefits,					
					
					
					if(sa.is_closed=1 AND sa.active=0,CONCAT('Closed on',' ',Date_Format(sa.closing_date,'%d-%m-%Y')),'Active')  as status,sa.is_closed,
					

					sa.account_name,
					
					ifnull(c.firstname,concat(c.firstname,' ',c.lastname))as customer_name,
					
					
					
					c.mobile,

					DATE_FORMAT(sa.`start_date`,'%d-%m-%Y') as start_date,
					
                if(s.scheme_type=0,'Amount',if(s.scheme_type=1,'Weight',if(s.scheme_type=2,'Amount to Weight','Flxeable Amount'))) as scheme_type,
					
					s.code as scheme_code,

					s.total_installments,s.max_weight,

					 if(s.scheme_type=1,CONCAT('max ',s.max_weight,' g/month'),s.amount) as payable,

					a.address1 as address1,

					a.address2 as address2,

					a.address3 as address3,st.name as state,ct.name as city,cy.name as country,a.pincode,

					if(sa.balance_amount is null,0,sa.balance_amount) as balance_amount,

					if(sa.balance_weight is null,0,sa.balance_weight) as balance_weight,


					s.scheme_type as type,

					cs.currency_name,

                    cs.currency_symbol,

                    sa.paid_installments as ins,
					
				paid.paid_ins as paid_installments

				from customer c

					left join address  a on(a.id_customer=c.id_customer)

					left join country cy on (a.id_country=cy.id_country)

					left join state st on (a.id_state=st.id_state)

					left join city ct on (a.id_city=ct.id_city)

					left join scheme_account sa on(sa.id_customer=c.id_customer)

					left join scheme s on(s.id_scheme=sa.id_scheme)
					
					left join payment p on(sa.id_scheme_account=p.id_scheme_account and p.payment_status=1)
					left join ( select sch.id_scheme_account , IFNULL(IF(sch.is_opening=1,IFNULL(sch.paid_installments,0)+ IFNULL(if(sc.scheme_type = 1 and sc.min_weight != sc.max_weight ,COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')), sum(pay.no_of_dues)),0),if(sc.scheme_type = 1 and sc.min_weight != sc.max_weight , COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')), sum(pay.no_of_dues))) ,0) as paid_ins From payment pay Left Join scheme_account sch on(pay.id_scheme_account=sch.id_scheme_account) 
					Left Join scheme sc on(sc.id_scheme=sch.id_scheme) Where (pay.payment_status=2 or pay.payment_status=1) Group By sch.id_scheme_account) paid on (sa.id_scheme_account=paid.id_scheme_account ) 

					join chit_settings cs

				WHERE sa.id_scheme_account='$id_scheme_account' group by sa.id_scheme_account";

		$account=$this->db->query($sql);	   

		return $account->row_array();	

	}

	

	function insert_sync($data)

	{

		$status = $this->db->insert(self::SYNC_TABLE,$data);

		return $status;

	}

	

	function insert_account($data)

	{   

		//$data['scheme_acc_number']=NULL;

		/* Coded by ARVK*/				
	
		$sql_scheme = $this->db->query("select s.approvalReqForFP,receipt_no_set, s.free_payment, s.amount, s.scheme_type, s.min_weight, s.max_weight, c.company_name, c.short_code  ,s.gst,s.gst_type
	  			from scheme s join company c
	  			join chit_settings cs		
	  			where s.id_scheme=".$data['id_scheme']);

	  	$sch_data = $sql_scheme->row_array();

	  	

/* / Coded by ARVK*/

		

		 $flag=$this->db->insert(self::ACC_TABLE,$data);

		 

		 $status = array('status' => $flag,'sch_data' => $sch_data,

		                  'insertID' => $this->db->insert_id());
//print_r($this->db->last_query());exit;
		

		return $status;

	}

	

	

	

	//returns last insert id

	function import_insert_account($data)

	{   

		/*$scheme_acc_number=$this->account_number_generator($data['id_scheme']);

		if($scheme_acc_number!=NULL)

		{

			$data['scheme_acc_number']=$scheme_acc_number;

		}*/

		$status=$this->db->insert(self::ACC_TABLE,$data);

		return ($status?$this->db->insert_id():$status);

	}

	

	function update_account($data,$id)

	{

		

		$this->db->where('id_scheme_account',$id);

	//print_r($this->db->last_query());exit;
		$status=$this->db->update(self::ACC_TABLE,$data);

		

		return $status;

	}

	

	function update_reg_status($data,$id)

	{

		$this->db->where('id_register',$id);

		$status=$this->db->update(self::REG_TABLE,$data);

		return $status;

	}

	

function delete_account($id)

	{
		
		$data=$this->check_payment($id);
		if($data['status']==1)

		{

			$this->db->where('id_scheme_account',$id);

			$status=$this->db->delete(self::ACC_TABLE);
			
			//print_r($this->db->last_query());exit;
			
			$status=array("status" => 1);


		}

		else
		{
			$status=array("status" => 0);
		}
		return $status;

	}
		
	function check_payment($id)
	{
		$query =$this->db->query("SELECT p.id_scheme_account FROM payment p where p.id_scheme_account=".$id."");
		if($query->num_rows()>0)
		{
			   return array("status" => 0);
		}
		else
		{
			   return array("status" => 1);
		}
	}
	//delete associated payments

	function delete_payment($data,$id)

	{

		$this->db->where('id_scheme_account',$id);

		$status=$this->db->delete(self::PAY_TABLE,$data);

		return $status;

	}

	

	function get_registration_details()

	{

		$registration=$this->db->query("select

					  id_register,

					  concat(c.firstname,' ',c.lastname) as name,c.mobile,ct.name as city,

					  s.code,if(s.scheme_type=0,'Amount',if(s.scheme_type=1,'Weight','Amount to Weight'))as scheme_type,s.amount,

					  r.id_customer,r.id_scheme,date_register,c.profile_complete

					from ".self::REG_TABLE." r

					left join ".self::CUS_TABLE." c on (r.id_customer=c.id_customer)

					left join ".self::SCH_TABLE." s on (r.id_scheme=s.id_scheme)

					left join ".self::ADD_TABLE." a on (c.id_customer=a.id_customer)

					left join city ct on (a.id_city=ct.id_city)

					where r.is_approved=0");

						

		return $registration->result_array();

	}

	

	//to get id_scheme_account by id_payment

	function getSchemeAccountByPayment($id_payment)

	{

		$sql="Select

				  sa.id_scheme_account

			From payment p

			Left join scheme_account sa On (p.id_scheme_account = sa.id_scheme_account)

			Where p.id_payment='$id_payment';";

		

		$account=$this->db->query($sql);	   

		return $account->row_array();

	}

	

	//ref_no in scheme_account

	function clientid_exists($id_scheme_account)

	{		

		$sql = "select ref_no from scheme_account where id_scheme_account = ".$id_scheme_account;

		$account = $this->db->query($sql);	

		

		if($account->num_rows()>0 && $account->row()->ref_no != '')

		{

			return array("status" => TRUE, "client_id" => $account->row()->ref_no );

		}

		else

		{

			return array("status" => FALSE);

		}	

	}
	
	
	
	

	// function branchname_list()
 //    {		
	
	// 	$branch=$this->db->query("SELECT b.name,b.id_branch FROM branch b");
		
	// 	return $branch->result_array();
			
	// }	

	function branchname_list()
    {		
	
		
		$id_branch=$this->session->userdata('id_branch');
		if( $this->session->userdata('branchWiseLogin')==1)
		{
			if( $this->session->userdata('id_branch')!='')
			{
				$branch=$this->db->query("SELECT b.name,b.id_branch FROM branch b Where id_branch=".$id_branch. " or b.show_to_all=1"  );
		
			}
			else
			{
				$branch=$this->db->query("SELECT b.name,b.id_branch FROM branch b");
			}
		}
		else
		{
			$branch=$this->db->query("SELECT b.name,b.id_branch FROM branch b");
		}
		return $branch->result_array();	
	}	
	
	
	
	function get_rptnosettings()
	{
		$sql="Select c.receipt_no_set FROM chit_settings c where c.id_chit_settings = 1";
		return $this->db->query($sql)->row()->receipt_no_set;
		
	}
	
	function get_accnosettings()
	{
		$sql="Select c.schemeacc_no_set FROM chit_settings c where c.id_chit_settings = 1";
		return $this->db->query($sql)->row()->schemeacc_no_set;
		
	}

	function get_schemegroupsettings()
	{
		$sql="Select c.has_lucky_draw FROM chit_settings c where c.id_chit_settings = 1";
		return $this->db->query($sql)->row()->has_lucky_draw;
		
	}
	
	function update_schemeaccno($id,$data)

	{	$this->db->where('id_scheme_account',$id);

		$status=$this->db->update(self::ACC_TABLE,$data);

		return $status;
	}
	
	// referrals code chk validate //
	
	
	
	
	  
	  function checkreferral_code($mbi){
		  
		 $query =$this->db->query("SELECT c.mobile as mobile 
									FROM customer c where c.mobile=".$mbi."");
		   if($query->num_rows()>0){			
					return TRUE;
			}else{
			$query=$this->db->query("SELECT e.id_employee as id_employee 
								FROM employee e where id_employee=".$mbi."");
		   if($query->num_rows()>0){			   
			   return TRUE;
			   
		   }else{
			  
			  return FALSE;;
		  } 
		}
	  }
		
	

	/*function available_refcode($data){
		
	 $query=$this->db->query("SELECT c.referal_code 
				FROM customer c 
				where c.id_customer=".$data['id_customer']." and c.referal_code='".$data['referal_code']."'");
	   if($query->num_rows()>0){
		   return TRUE;		   
	   }else{
		   
		   $this->db->where('id_customer',$data['id_customer']); 		  
		 $updaterefcode =  $this->db->update('customer',array('referal_code'=>$data['referal_code']));
		  return TRUE;	
	   }
	}*/
	
	function available_refcode($data)
	{
		

	if($data['is_refferal_by']==0 && $data['cus_single']==0)
	{
		$query=$this->db->query("SELECT c.cus_ref_code 
				FROM customer c 
				where c.id_customer=".$data['id_customer']." and c.cus_ref_code='".$data['referal_code']."'");
		
			if($query->num_rows()>0)
	 	 	 {
		  	 return TRUE;		   
	  		 }
	  		 else
	  		 {
	  		 	 $this->db->where('id_customer',$data['id_customer']); 		  
				 $updaterefcode =  $this->db->update('customer',array('cus_ref_code'=>$data['referal_code']));
		 			 return TRUE;	
	  		 }

	}

	else if($data['is_refferal_by']==1 && $data['emp_single']==0)
	{

		$query=$this->db->query("SELECT c.emp_ref_code 
				FROM customer c 
				where c.id_customer=".$data['id_customer']." and c.emp_ref_code='".$data['referal_code']."'");
			if($query->num_rows()>0)
	 	 	 {
		  	 return TRUE;		   
	  		 }
	  		 else
	  		 {
	  		 	 $this->db->where('id_customer',$data['id_customer']); 		  
				 $updaterefcode =  $this->db->update('customer',array('emp_ref_code'=>$data['referal_code']));
		 			 return TRUE;	
	  		 }
	}
	else if( $data['is_refferal_by']==0||$data['is_refferal_by']==1 && ($data['cus_single']==1&&$data['emp_single']==1))
	{
		 $this->db->where('id_customer',$data['id_customer']); 		  
				 $updaterefcode =  $this->db->update('customer',array('cus_ref_code'=>$data['cus_ref_code'],'emp_ref_code'=>$data['emp_ref_code']));
		 			 return TRUE;	
	}


	}
	
	
	
	public function veriflyreferral_code($mbi) 
	{
			$this->db->select('mobile');
			$this->db->where('mobile',$mbi); 
			$status=$this->db->get('customer');
			if($status->num_rows()>0)
			{
				  
				$this->db->select('mobile');
			    $this->db->where('mobile',$mbi); 
			    $status=$this->db->get('employee');
				if($status->num_rows()>0){				
				  return array("status" => TRUE,'user'=>'EMP');
				}
				
				return array("status" => TRUE,'user'=>'CUS');
			}else{
				
				$this->db->select('mobile');
			    $this->db->where('mobile',$mbi); 
			    $status=$this->db->get('employee');
				if($status->num_rows()>0){
				
				return array("status" => TRUE,'user'=>'EMP');
			  }
			   return array("status" => FALSE);
		   }
		   return array("status" => FALSE);
	}
	  
	  
	/* public function check_refcode($ref_code)
	{
			$this->db->select('mobile');
			$this->db->where('mobile',$ref_code); 
			$status=$this->db->get('customer');
			if($status->num_rows()>0)
			{
				return TRUE;
			}else{
				$this->db->select('mobile');
				$this->db->where('mobile',$ref_code); 
				$status=$this->db->get('employee');
				if($status->num_rows()>0)
				{
					return TRUE;
				}
			}
			return FALSE;
	}*/
	
		function check_refcode($mbi,$id_customer)

	{
		  
	   		 $isEnteredCodevalid =$this->veriflyreferral_code($mbi);

				if($isEnteredCodevalid['user'] == 'CUS')
				{

					$referal_cod = $this->db->query("select c.id_customer,c.cus_ref_code as referal_code,cs.cusbenefitscrt_type,cs.empbenefitscrt_type  from customer c join chit_settings cs where c.id_customer='".$id_customer."'");

				}
				else
				{
					
				$referal_cod = $this->db->query("select c.id_customer,c.emp_ref_code as referal_code,cs.cusbenefitscrt_type,cs.empbenefitscrt_type  from customer c join chit_settings cs where c.id_customer='".$id_customer."'");	
				}
					

	   		$referal_code=$referal_cod->row()->referal_code;
	   		$empSingle=$referal_cod->row()->empbenefitscrt_type;
	   		$cusSingle=$referal_cod->row()->cusbenefitscrt_type;

if($referal_code == null || $referal_code == ""){	
	// $isEnteredCodevalid = check whether entered referral code is valid using verify function
	$isEnteredCodevalid = $this->veriflyreferral_code($mbi);
	if($isEnteredCodevalid['status'] == true){

		$result = array('status' => true, 'msg' => 'Valid referal Code' );
	}else{
		$result = array('status' => false, 'msg' => 'Invalid referal Code' );
	}
}else
{
	$checkCusRefCodeType = $this->veriflyreferral_code($referal_code);
		$isEnteredCodevalid = $this->veriflyreferral_code($mbi);
		
		if($isEnteredCodevalid['status']==0)
		{
		$result = array('status' => false, 'msg' => 'Invalid referal Code' );

		}else
		{
			if($checkCusRefCodeType['user'] == 'CUS')
		{

			$isEnteredCodevalid =$this->veriflyreferral_code($mbi);
			
			if($isEnteredCodevalid['user']=='CUS')
			{
			if($cusSingle == 0){
			$result = array('status' => false, 'msg' => 'Referal Code Used' );
			}else{
			$isEnteredCodevalid =$this->veriflyreferral_code($mbi);
			if($isEnteredCodevalid['status'] == true){
				$result = array('status' => true, 'msg' => 'Valid referal Code' );
			}else{
				$result = array('status' => false, 'msg' => 'Invalid referal Code' );
			}
		}
			}
			else if($isEnteredCodevalid['user']=='EMP')
			{
			if($empSingle == 0){
			$result = array('status' => false, 'msg' => 'Referal Code Used' );
		}else{
			$isEnteredCodevalid =$this->veriflyreferral_code($mbi);
			if($isEnteredCodevalid['status'] == true){
				$result = array('status' => true, 'msg' => 'Valid referal Code' );
			}else{
				$result = array('status' => false, 'msg' => 'Invalid referal Code' );
			}
		}
			}
		
			

			

		
	}else if($checkCusRefCodeType['user'] == 'EMP')
	{
		$isEnteredCodevalid =$this->veriflyreferral_code($mbi);

			if($isEnteredCodevalid['user']=='CUS')
			{
				if($cusSingle == 0){
			$result = array('status' => false, 'msg' => 'Referal Code Used' );
		}else{
			$isEnteredCodevalid =$this->veriflyreferral_code($mbi);
			if($isEnteredCodevalid['status'] == true){
				$result = array('status' => true, 'msg' => 'Valid referal Code' );
			}else{
				$result = array('status' => false, 'msg' => 'Invalid referal Code' );
			}
		}
			}

			else
			{
				if($empSingle == 0){

			$result = array('status' => false, 'msg' => 'Referal Code Used' );
		}else{
			
			if($isEnteredCodevalid['status'] == true){
				$result = array('status' => true, 'msg' => 'Valid referal Code' );
			}else{
				$result = array('status' => false, 'msg' => 'Invalid referal Code' );
			}
		}
			}

		
	}
		} 

}
		return $result;    
			
	   }
	
	function get_requests_range($from_date,$to_date,$status)
	{
		$qry=$this->db->query("SELECT c.email,schReg.added_by,id_reg_request,if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,sch.id_scheme,
		  if(cs.has_lucky_draw = 1,IFNULL(sg.group_code,''),'') as scheme_group_code , cs.has_lucky_draw,schReg.id_scheme_group,c.mobile,if(remark = '','-',remark) as remark,schReg.id_customer,schReg.scheme_acc_number,schReg.ac_name,schReg.id_branch,DATE_FORMAT(schReg.date_add,'%d-%m-%Y') AS date_add,schReg.status,schReg.id_scheme,br.id_branch,br.name as branch_name ,schReg.ac_name AS ac_name ,schReg.pan_no,cs.firstPayamt_payable, IFNULL(schReg.firstPayment_amt,'')as firstPayment_amt,sch.scheme_type
		from scheme_reg_request schReg
		LEFT JOIN scheme AS sch ON sch.id_scheme = schReg.id_scheme
		LEFT JOIN branch AS br ON br.id_branch = schReg.id_branch
		LEFT JOIN customer c  ON c.id_customer = schReg.id_customer
		LEFT JOIN scheme_group sg ON sg.id_scheme_group = schReg.id_scheme_group
		JOIN chit_settings cs 
		WHERE schReg.status =  ".$status." and (date(schReg.date_add) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')");
		return $qry->result_array();
	}
	
	function get_existingSchRequests($status)
	{
		$branchWiseLogin=$this->session->userdata('branchWiseLogin');
		$is_branchwise_cus_reg=$this->session->userdata('is_branchwise_cus_reg');
			$id_branch=$this->session->userdata('id_branch');
			$uid=$this->session->userdata('uid');
			
		
		$qry=$this->db->query("SELECT c.email,schReg.added_by,id_reg_request,if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,sch.id_scheme,
		  if(cs.has_lucky_draw = 1,IFNULL(sg.group_code,''),'') as scheme_group_code,cs.has_lucky_draw,schReg.id_scheme_group,c.mobile,if(remark = '','-',remark) as remark,schReg.id_customer,schReg.scheme_acc_number,schReg.ac_name,schReg.id_branch,DATE_FORMAT(schReg.date_add,'%d-%m-%Y') AS date_add,schReg.status,schReg.id_scheme,br.id_branch,br.name as branch_name ,schReg.ac_name AS ac_name,schReg.pan_no,cs.firstPayamt_payable, IFNULL(schReg.firstPayment_amt,'')as firstPayment_amt,sch.scheme_type
		from scheme_reg_request schReg
		LEFT JOIN scheme AS sch ON sch.id_scheme = schReg.id_scheme
		LEFT JOIN branch AS br ON br.id_branch = schReg.id_branch
		LEFT JOIN customer c  ON c.id_customer = schReg.id_customer
		LEFT JOIN scheme_group sg ON sg.id_scheme_group = schReg.id_scheme_group
		JOIN chit_settings cs 
		
		".($status!='3'?"  WHERE schReg.status =".$status :" ".($uid!=1 ? ($branchWiseLogin==1 ||$is_branchwise_cus_reg==1 ? ($id_branch!='' ? " Where br.id_branch=".$id_branch. " or  br.show_to_all=1 ":'') :'') :'')." ")."  ");
		
		return $qry->result_array();
	}
	
		//existingSchRequests//
	
        function get_existingSchRequests_dashboard($status)
        {
        
            $branchWiseLogin=$this->session->userdata('branchWiseLogin');
            $id_branch=$this->session->userdata('id_branch');
            $uid=$this->session->userdata('uid');
			$dashboard_branch=$this->session->userdata('dashboard_branch');
			
				
            $qry=$this->db->query("SELECT schReg.id_scheme_group,c.email,schReg.added_by,id_reg_request,if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,sch.id_scheme,
             c.mobile,if(remark = '','-',remark) as remark,schReg.id_customer,schReg.scheme_acc_number,schReg.ac_name,schReg.id_branch,DATE_FORMAT(schReg.date_add,'%d-%m-%Y') AS date_add,schReg.status,schReg.id_scheme,br.id_branch,br.name as branch_name ,schReg.ac_name AS ac_name  
            from scheme_reg_request schReg
            LEFT JOIN scheme AS sch ON sch.id_scheme = schReg.id_scheme
            LEFT JOIN branch AS br ON br.id_branch = schReg.id_branch
            LEFT JOIN customer c  ON c.id_customer = schReg.id_customer
            LEFT JOIN scheme_group sg ON sg.id_scheme_group = schReg.id_scheme_group
            JOIN chit_settings cs  ".($dashboard_branch!=0 ? ($status!=3 ? "Where schReg.status=".$status." and schReg.id_branch=".$dashboard_branch : " where schReg.id_branch=".$dashboard_branch) : ($status!=3 ? "where schReg.status=".$status: "") )." ");
			
			
			//".($status!=3 ?" Where schReg.status=".$status  : "")." ");
			
			//($uid!=1 ? ($branchWiseLogin==1 ? ($id_branch!='' ? "Where schReg.id_branch=".$id_branch. " or br.show_to_all=1" :''):''):'')
			
			//".($dashboard_branch!=0 ? " Where sr.id_branch=".$dashboard_branch :'')."  ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and  sr.id_branch=".$id_branch." or b.show_to_all=1 ":''):''):'').""
			
			
			//print_r($this->db->last_query());
			
            $existing_data =0;	
            foreach($qry->result_array() as $row)
            {
                $existing_data +=1;
            }
            return  $existing_data;
             
        } 
	
	
	
	function get_requests_byBranch($id,$status)
	{
		
		$qry = $this->db->query("SELECT c.email,schReg.added_by,id_reg_request,schReg.id_scheme_group,if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,sch.id_scheme,
		  if(cs.has_lucky_draw = 1,IFNULL(sg.group_code,''),'') as scheme_group_code ,cs.has_lucky_draw,c.mobile,if(remark = '','-',remark) as remark,schReg.id_customer,schReg.scheme_acc_number,schReg.ac_name,schReg.id_branch,DATE_FORMAT(schReg.date_add,'%d-%m-%Y') AS date_add,schReg.status,schReg.id_scheme,br.id_branch,br.name as branch_name ,schReg.ac_name AS ac_name,cs.firstPayamt_payable, IFNULL(schReg.firstPayment_amt,'')as firstPayment_amt,sch.scheme_type  
		from scheme_reg_request schReg
		LEFT JOIN scheme AS sch ON sch.id_scheme = schReg.id_scheme
		LEFT JOIN branch AS br ON br.id_branch = schReg.id_branch
		LEFT JOIN customer c  ON c.id_customer = schReg.id_customer
		LEFT JOIN scheme_group sg ON sg.id_scheme_group = schReg.id_scheme_group
		JOIN chit_settings cs 
		".($status!='3'?"  WHERE schReg.status =".$status ." and schReg.id_branch=".$id:" WHERE schReg.id_branch=".$id)." ");			
		return $qry->result_array(); 
	}	
	
	function get_schemes()
	{		
		$qry = $this->db->query("SELECT id_scheme,code from scheme");
		return $qry->result_array();
	}	
	
	function updateRequest($data,$id)
	{		
		$this->db->where('id_reg_request',$id);
		$status=$this->db->update('scheme_reg_request',$data);
		return $status;		
	}
	
	function getDevicetokens()
	{
		$sql = $this->db->query("SELECT r.token as token ,c.mobile
									from registered_devices r 
									LEFT JOIN customer c on (c.id_customer=r.id_customer) 
									where c.notification = 1;");
		
		$token = array_map(function ($value) {
					return  $value['token'];
					}, $sql->result_array());
					$data =$sql->result_array();
		
		return $data;
	}
	function get_notiContent($id_notification)
     {
		//Declaration of variables
		$message ="";
		$noti_msg = "";
		$noti_footer = "";
		$msg = "";
		$customer_data = array();
		$data = array();
			
			
			$resultset = $this->db->query("SELECT noti_name,noti_name, noti_footer,noti_msg from notification where id_notification = '".$id_notification."'");
				foreach($resultset->result() as $row)
				{
					$noti_msg = $row->noti_msg;
					$noti_footer = $row->noti_footer;
					$noti_header=$row->noti_name;
					$data = $row->noti_msg;
				}
			$resultset->free_result();
			
			/*$field_name_footer = explode('@@', $noti_footer);	
			for($i=1; $i < count($field_name_footer); $i+=2)
			 {
				if(isset($customer_data->$field_name_footer[$i]))
				 { 
					$noti_footer = str_replace("@@".$field_name_footer[$i]."@@",$customer_data->$field_name_footer[$i],$noti_footer);					
				}	
			}
					
			//Generating Message content
			$field_name = explode('@@', $noti_msg);	
			$a=0;
		foreach($data as $row)
		{
			$customer_data = $row;		
			$msgContent = $noti_msg;
			for($i=1; $i < count($field_name); $i+=2) 
			{	
				if(isset($customer_data[$field_name[$i]])) 
				{
					$msgContent = str_replace("@@".$field_name[$i]."@@",$customer_data[$field_name[$i]],$msgContent);					$data[$a]['message']=$msgContent;
				}
			}	
			unset($msgContent);
			$a++;
		}*/
	 return (array('data'=>$data,'header'=>$noti_header,'footer'=>$noti_footer));
	}
	
	function getnotificationids($mobile)
	{
		$sql = $this->db->query("SELECT r.token as token ,c.mobile
									from registered_devices r 
									LEFT JOIN customer c on (c.id_customer=r.id_customer) 
									where c.notification = 1 and mobile=".$mobile);
		
		$data =$sql->result_array();
		
		return $data;
	}
	
		function checkClientID($id_scheme_account="",$client_id="")
    {	
       if($id_scheme_account == "" && $client_id != ""){
           $sql = "select id_scheme_account,ref_no from scheme_account where ref_no = '$client_id'";
       }else{
           $sql = "select id_scheme_account,ref_no from scheme_account where id_scheme_account = ".$id_scheme_account;
       }
    
    $account = $this->db->query($sql);	
    if($account->num_rows()>0 && $account->row()->ref_no != '')
    {
    return array("status" => TRUE, "client_id" => $account->row()->ref_no,'id_scheme_account'=>$account->row()->id_scheme_account );
    }
    else
    {
    return array("status" => FALSE);
    }	
    
    }
    
    function isPaymentExist($data)
	{
	    if($data['id_branch'] == NULL || $data['id_branch'] == ''){
	        $sql = $this->db->query("select id_scheme_account from scheme_account where id_scheme =".$data['id_scheme']." and id_customer =".$data['id_customer']." and (id_branch is null or id_branch=0) and scheme_acc_number =".$data['scheme_acc_number']);
	    }else{
	        $sql = $this->db->query("select id_scheme_account from scheme_account where id_scheme =".$data['id_scheme']." and id_customer =".$data['id_customer']." and id_branch =".$data['id_branch']." and scheme_acc_number =".$data['scheme_acc_number']);
	    }
	    if($sql->num_rows() > 0){
	        $pay = $this->db->query("select id_payment from payment where id_scheme_account =".$sql->row('id_scheme_account')); 
	       
	        if($pay->num_rows() > 0){
	            return array('status' => true , 'id_scheme_account' => $sql->row('id_scheme_account'));
	        }else{
	            return array('status' => false , 'id_scheme_account' => $sql->row('id_scheme_account'));
	        }
	    }else{
	        return array('status' => false , 'id_scheme_account' => NULL);
	    }
	}  
	
    function deleteAcc($data,$id)
	{
	    $this->db->where('id_scheme_account',$id);
		$status=$this->db->delete(self::ACC_TABLE,$data);
		return $status;
	}

//get_Schemegroup
	
	function get_schemegroup() 
	{
		$sql="SELECT s.id_scheme_group, s.id_scheme, s.group_code,sch.code as scheme_code, DATE_FORMAT(s.start_date,'%d-%m-%Y') as start_date, DATE_FORMAT(s.end_date,'%d-%m-%Y') as end_date 		
		FROM scheme_group s
		left join scheme sch on (sch.id_scheme=s.id_scheme)";
	   return $this->db->query($sql)->result_array();	
	//return $this->db->query($sql)->r_array();	
	}
	
	
	
 function group_empty()

    {

		$data=array(

			'id_scheme_group'	  => NULL,

			'id_scheme'=>			NULL,

			'scheme_code'		  => NULL,

			'group_code'		=> NULL,

			'start_date'		=> NULL,
			
			'end_date'		    => NULL,

			
			'last_update'      =>NULL,
			  
			 'date_add'         => date('d-m-Y')
			

			
			

		);

		return $data;

	}
	
	function insert_groupaccount($data)
	{   
		$status=$this->db->insert(self::SCHGROUP_TABLE,$data);		
		//echo $this->db->last_query($status);exit;
		return ($status?$this->db->insert_id():$status);

    }
	function get_groupaccount_details($id)
	{   
	
		$sql="SELECT s.id_scheme_group, s.id_scheme, s.group_code,sch.code as scheme_code,
		 DATE_FORMAT(s.start_date,'%d-%m-%Y') as start_date, DATE_FORMAT(s.end_date,'%d-%m-%Y') as end_date 		
		FROM scheme_group s
		left join scheme sch on (sch.id_scheme=s.id_scheme)
		WHERE s.id_scheme_group=".$id."";
	
		$account=$this->db->query($sql);	   

		return $account->row_array();	


    }

    function update_groupaccount($data,$id)
	{   
		$this->db->where("id_scheme_group",$id);
		//echo $this->db->last_query($status);exit;
		$status = $this->db->update(self::SCHGROUP_TABLE,$data);
		return	array('status' => $status, 'updateID' => $id);     
    }

	function get_groups()
    {	
        $qry = $this->db->query("SELECT id_scheme_group,group_code from scheme_group");
        return $qry->result_array();
    }
    
    function code_available($group_code)
    {
        $this->db->select('group_code');
        $this->db->where('group_code', $group_code);
        $status=$this->db->get(self::SCHGROUP_TABLE); 
        if($status->num_rows()>0)
        {
            return TRUE;
        }
    }
    
    function delete_group($data,$id)
    {

    	$code=$data['group_code'];

 		$this->db->select('group_code');
        $this->db->where('group_code', $code);
        $status=$this->db->get(self::ACC_TABLE); 
 		// print_r($status); exit;

        if($status->num_rows()>0)
        {
 		return FALSE;
        }
        else
        {
        $this->db->where('id_scheme_group',$id);
        $status=$this->db->delete(self::SCHGROUP_TABLE);
	     return TRUE;
            }

   
    }

  
    
    function get_customerenquiry() 
    {
       $sql="select * FROM cust_enquiry ";
       return $this->db->query($sql)->result_array();
    }
    
    function get_customerenquiry_by_date($from_date,$to_date) 
    {
       $sql="select * FROM cust_enquiry Where (date(date_add) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')";
       return $this->db->query($sql)->result_array();
    }
	
	function get_all_scheme_account_list($mobile)

	{    
		
	
		$accounts=$this->db->query("select IFNULL(s.pan_no,'-') as pan_no,

								sc.code,IFNULL(s.group_code,'')as scheme_group_code,cs.has_lucky_draw,
							  s.id_scheme_account,IFNULL(s.scheme_acc_number,'Not Allocated') as scheme_acc_number ,IF(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,s.ref_no,s.account_name,DATE_FORMAT(s.start_date,'%d-%m-%Y') as start_date,c.is_new,s.added_by,concat('C','',c.id_customer) as id_customer,

							  sc.scheme_name,if(s.is_new ='Y','New','Existing') as is_new,sc.code,if(sc.scheme_type=0,'Amount',if(sc.scheme_type=1,'Weight',if(sc.scheme_type=3,'FLEXIBLE_AMOUNT','Amount To Weight')))as scheme_type,cs.schemeacc_no_set,
							  FORMAT(if(sc.scheme_type=1,sc.max_weight,if(sc.scheme_type=3 && sc.max_amount!=0,sc.max_amount,if(sc.scheme_type=3 && sc.max_amount=0,(sc.max_weight*(SELECT m.goldrate_22ct FROM metal_rates m  order by id_metalrates Desc LIMIT 1)),sc.amount))),2) as amount,
							  
							  sc.scheme_type  as scheme_types,
							  sc.total_installments,sc.max_chance,sc.max_weight,c.mobile,if(s.active =1,'Active','Inactive') as active,s.date_add,cs.currency_symbol,
							  
							  
							 IFNULL(IF(s.is_opening=1,IFNULL(s.paid_installments,0)+ IFNULL(if(sc.scheme_type = 1 and sc.min_weight != sc.max_weight, COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')), sum(pay.no_of_dues)),0), if(sc.scheme_type = 1 and sc.min_weight != sc.max_weight or sc.scheme_type=3, COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')), sum(pay.no_of_dues))) ,0)
                              as paid_installments
							from

							  ".self::ACC_TABLE." s

							left join ".self::CUS_TABLE." c on (s.id_customer=c.id_customer)

							left join ".self::SCH_TABLE." sc on (sc.id_scheme=s.id_scheme)

							left join ".self::PAY_TABLE." pay on (pay.id_scheme_account=s.id_scheme_account  and (pay.payment_status=2 or pay.payment_status=1))

							left join branch b on (b.id_branch=s.id_branch)

							join chit_settings cs

							
							Where s.is_closed=0 and c.mobile like '".$mobile."%'

							group by s.id_scheme_account");

				
		return $accounts->result_array();

	}
	function select_otp($otp)

	{

		$this->db->select('*');

		$this->db->where('otp_code',$otp);

		$status=$this->db->get(self::OTP_TABLE);

		return $status->row_array();

	}
	
	function otp_update_payment($data,$id)

	{

		$this->db->where('id_otp',$id);

		$status=$this->db->update(self::OTP_TABLE,$data);

		return $status;

	}
	
	function get_scheme_type_closed_account($id_branch,$from_date,$to_date,$type="")

	{
		
		
		$accounts=$this->db->query("select

							  s.id_scheme_account,sc.code,IFNULL(s.group_code,'')as scheme_group_code,IFNULL(s.scheme_acc_number,'NOT Allocated')as scheme_acc_number,cs.has_lucky_draw,
							  concat (c.firstname,' ',if(c.lastname!=Null,c.lastname,'')) as name,

							  s.ref_no, s.closing_add_chgs, s.account_name,

							  IFNULL(Date_format(s.start_date,'%d-%m%-%Y'),'-') as start_date,

							  IFNULL(Date_format(s.closing_date,'%d-%m%-%Y'),'-') as closing_date,

							  if(sc.scheme_type=0,CONCAT(cs.currency_symbol,' ',s.closing_balance),s.closing_balance) as closing_balance,

							  c.added_by,sc.scheme_name,sc.code,
							  
							  if(sc.scheme_type=0,'Amount',if(sc.scheme_type=1,'Weight',if(sc.scheme_type=3,'FLEXIBLE_AMOUNT','Amount To Weight')))as scheme_type,
							  FORMAT(if(sc.scheme_type=1,CONCAT('max ',sc.max_weight,' g/month'),if(sc.scheme_type=3 && sc.max_amount!=0,sc.max_amount,if(sc.scheme_type=3 && sc.max_amount=0,(sc.max_weight*(SELECT m.goldrate_22ct FROM metal_rates m  order by id_metalrates Desc LIMIT 1)),sc.amount))),2) as amount					,sc.total_installments,sc.max_chance,c.mobile

							from

							  ".self::ACC_TABLE." s

							left join ".self::CUS_TABLE." c on (s.id_customer=c.id_customer)

							left join ".self::SCH_TABLE." sc on (sc.id_scheme=s.id_scheme)

							left join ".self::BRANCH." b on (b.id_branch=s.id_branch)

							join chit_settings cs

							where s.active=0 and s.is_closed=1  and  (date(s.closing_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') 
							
							and s.id_branch=".$id_branch." ".($type!='' ? "and sc.scheme_type=".$type."" :'')." ");
							
							

		return $accounts->result_array();

	}
	
	
	
	 /*SCHEME WISE OUTSTANDING REPORT STARTS */
	function get_all_scheme_account_by_range()
	 { 
	    //$common_db = $this->load->database('common_db',true);
		//$from_date= $this->input->post('from_date');
		//$to_date= $this->input->post('to_date');
		$id_scheme  = $this->input->post('id_scheme');
		$id_branch  = $this->input->post('id_branch');
		$company_settings=$this->session->userdata('company_settings');
		$id_company = $this->session->userdata('id_company');
		$branch=$this->session->userdata('id_branch');
		$group_code=$this->input->post('id_group');
		
        $start = $this->input->post('start');
        $length = $this->input->post('length');
		// Turn caching on 
         $this->db->cache_on();
		 $sql=$this->db->query("select IFNULL(Date_Format(max(pay.date_payment),'%d-%m-%Y'),'-') as last_paid_date,
		 s.id_scheme_account,
		 IFNULL(s.scheme_acc_number,'NOT ALLOCATED') as scheme_acc_number,
		 IF(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,
		 s.account_name,DATE_FORMAT(s.start_date,'%d-%m-%Y') as start_date,sc.code,IFNULL(s.group_code,'-') as group_code,sc.id_metal,
		 
		 sc.scheme_name,IF(sc.scheme_type=0,'Amount',IF(sc.scheme_type=1,'Weight',if(sc.scheme_type=2,'Amount to Weight','Flexible'))) AS scheme_type,sc.amount,c.mobile,if(s.active =1 and s.is_closed = 0,'Live','Closed') as active,cs.currency_symbol,
        IFNULL((select IFNULL(IF(s.is_opening=1,IFNULL(s.paid_installments,0)+ IFNULL(if(sc.scheme_type = 1 and sc.min_weight != sc.max_weight, COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')), sum(pay.no_of_dues)),0), if(sc.scheme_type = 1 and sc.min_weight != sc.max_weight or (sc.scheme_type=3), COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')), sum(pay.no_of_dues))) ,0) from payment pay where pay.payment_status=1 and pay.id_scheme_account=s.id_scheme_account group by pay.id_scheme_account),0) as paid_installments,
		 SUM(IFNULL(pay.payment_amount,0)) AS totalpay_amount, IF(sc.scheme_type = 0, 'Amount', IF(sc.scheme_type = 1,'Weight',IF(sc.scheme_type = 2,'Amount to Weight',IF(sc.scheme_type = 3, 'Flexible','-')))) as scheme_type,SUM(IFNULL(pay.metal_weight,0)) AS total_wgt,
		 if(s.added_by=1,'Admin',if(s.added_by=0,'Web App',if(s.added_by=2,'Mobile App',if(s.added_by=3,'Collection App',if(s.added_by=4,'Retail',if(s.added_by=5,'Sync',if(s.added_by=6,'Import','-'))))))) as joined_thru,
		 IFNULL(adrs.address1,'-') as address1,IFNULL(adrs.address2,'-') as address2,IFNULL(adrs.address3,'-') as address3,IFNULL(adrs.pincode,'-') as pincode,IFNULL(city.name,'-') as city,IFNULL(state.name,'-') as state,Date_Format(DATE_ADD(date(s.start_date), INTERVAL sc.total_installments  MONTH),'%d-%m-%Y') as maturity_date,
		 IFNULL((select concat(IFNULL(e.firstname,''),' ',IFNULL(e.lastname,''),'-',IFNULL(e.emp_code,'')) from employee e left join scheme_account ssa on ssa.referal_code=e.emp_code WHERE ssa.id_scheme_account=s.id_scheme_account and ssa.referal_code is not null and ssa.referal_code!='' and ssa.is_refferal_by is not null and ssa.is_refferal_by=1),'-') as referred_employee
		 from
		 ".self::ACC_TABLE." s
		 left join ".self::CUS_TABLE." c on (s.id_customer=c.id_customer)
		 left join ".self::SCH_TABLE." sc on (sc.id_scheme=s.id_scheme)
		 left join ".self::BRANCH." b on (b.id_branch=s.id_branch)
		 
		 left join address adrs on c.id_address=adrs.id_address
		 left join city city on city.id_city=adrs.id_city
		 left join state state on state.id_state=adrs.id_state
		 left join ".self::PAY_TABLE." pay on (pay.id_scheme_account=s.id_scheme_account  and (pay.payment_status=1))
		 join chit_settings cs
		 Where s.scheme_acc_number IS NOT NULL and s.active=1 and sc.active=1
		 ".($id_branch!='' && $id_branch!=0 && $branch==0 ? " and s.id_branch=".$id_branch."":'')."
		 ".($id_scheme!='' && $id_scheme!=0  ? " and s.id_scheme=".$id_scheme."":'')."
		 ".($id_company!='' && $id_company!=0 && $company_settings==1 ? " and c.id_company=".$id_company."":'')."
		 ".($group_code!=''  ? " and s.group_code ='".$group_code."'":'')."
		 group by s.id_scheme_account"); 
        $result = []; 
		//echo "<pre>";echo $this->db->last_query();exit;
		// Turn caching off for this one query 
        $this->db->cache_off(); 
        $payment = $sql->result_array();
       // echo "<pre>";print_r($payment);exit;
        if($sql->num_rows() > 0)
		{
		    $sno = 1;
            foreach($payment as $rcpt)
			{
			   $rcpt['sno'] = $sno;
              // $rcpt['scheme_acc_number'] = $this->customer_model->format_accRcptNo('Account',$rcpt['id_scheme_account']);
			   $return_data[$rcpt['scheme_name']][]=$rcpt; 
			   $result = $return_data;
			   $sno++;
           } 
        }
        
        /*$result['draw'] = $this->input->post('draw');
        $result['recordsTotal'] = $sql->num_rows();
        $result['recordsFiltered'] = $sql->num_rows();*/
        return $result;
	 }
	 function scheme_summary_data()
	 {
		
		$modifiedArray =[];  // Create an empty array to store modified values
		$from_date= $this->input->post('from_date');
		$to_date= $this->input->post('to_date');
		$id_scheme  = $this->input->post('id_scheme');
		$id_branch  = $this->input->post('id_branch');
		$company_settings=$this->session->userdata('company_settings');
		$id_company = $this->session->userdata('id_company');
		$branch=$this->session->userdata('id_branch');
		$singledatefilter  = $this->input->post('singlefilter');


        if($singledatefilter!=''){
			$collectionamt= $this->get_collection_amt($singledatefilter);
			$oldclosedamt=$this->get_oldclosed_amt($singledatefilter);
			$newclosedamt=$this->get_newclosed_amt($singledatefilter);
 
		}
		if($from_date !='' && $to_date!=''){
			$collectionamt= $this->get_collection_amt($singledatefilter="");
			$oldclosedamt=$this->get_oldclosed_amt($singledatefilter="");
			 $newclosedamt=$this->get_newclosed_amt($singledatefilter="");
		}




        //  $accounts=$this->db->query("SELECT s.group_code,sc.code,sc.id_metal,sc.id_scheme,sc.scheme_name,COUNT(s.id_scheme_account)as scheme_count,SUM(total_pay.totalpay_amount) as paid_amount,SUM(total_pay.metal_weight) as metal_weight,SUM((select IFNULL(IF(s.is_opening=1,IFNULL(s.paid_installments,0)+ IFNULL(if(sc.scheme_type = 1 and sc.min_weight != sc.max_weight, COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')), sum(pay.no_of_dues)),0), if(sc.scheme_type = 1 and sc.min_weight != sc.max_weight or (sc.scheme_type=3), COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')), sum(pay.no_of_dues))) ,0) from payment pay where pay.payment_status=1 and pay.id_scheme_account=s.id_scheme_account group by pay.id_scheme_account)) as paid_installments,sc.id_classification,cls.classification_name
		//  FROM scheme_account s 
		//  LEFT JOIN scheme sc ON sc.id_scheme=s.id_scheme 
		//  left join sch_classify cls on cls.id_classification = sc.id_classification
		//  LEFT JOIN (SELECT id_scheme_account,SUM(IFNULL(payment_amount,0)) AS totalpay_amount,sum(IFNULL(metal_weight,0)) as metal_weight FROM payment Where payment_status=1 GROUP BY id_scheme_account ) AS total_pay ON total_pay.id_scheme_account = s.id_scheme_account 
		//  Where s.scheme_acc_number IS NOT NULL and s.active=1 and sc.active=1
		//  ".($id_branch!='' && $id_branch!=0 && $branch==0 ? " and s.id_branch=".$id_branch."":'')."
		//  ".($id_scheme!='' && $id_scheme!=0  ? " and s.id_scheme=".$id_scheme."":'')."
		//  ".($id_company!='' && $id_company!=0 && $company_settings==1 ? " and sc.id_company=".$id_company."":'')."
		//  GROUP BY s.id_scheme"); 

		$accounts=$this->db->query("SELECT sc.code,total_pay.date_payment,sc.id_metal, s.id_scheme_account,  sc.id_scheme,sc.scheme_name,COUNT(s.id_scheme_account)as scheme_count,sc.is_lucky_draw,SUM(total_pay.totalpay_amount) as paid_amounts ,
		SUM(total_pay.metal_weight) as oldmetal_weight,
		if(sc.scheme_type!=0 and (sc.flexible_sch_type is null or sc.flexible_sch_type!=1),SUM(IFNULL(total_pay.metal_weight,0)),0) as metal_weight,
		SUM((select IFNULL(IF(s.is_opening=1,IFNULL(s.paid_installments,0)+ IFNULL(if(sc.scheme_type = 1 and sc.min_weight != sc.max_weight, COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')), sum(pay.no_of_dues)),0), if(sc.scheme_type = 1 and sc.min_weight != sc.max_weight or (sc.scheme_type=3 AND sc.firstPayamt_as_payamt = 0), COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')), sum(pay.no_of_dues))) ,0) from payment pay where pay.payment_status=1 and pay.id_scheme_account=s.id_scheme_account group by pay.id_scheme_account)) as paid_installments,sc.id_classification,cls.classification_name
		FROM scheme_account s 
		LEFT JOIN scheme sc ON sc.id_scheme=s.id_scheme 
		left join sch_classify cls on cls.id_classification = sc.id_classification
		
		
		LEFT JOIN (
			SELECT p.id_scheme_account,date_payment 
			,SUM(IFNULL(payment_amount,0)) AS totalpay_amount,
			sum(IFNULL(metal_weight,0)) as metal_weight 
			FROM payment p Where payment_status=1   ".($singledatefilter!=''  ? " and date(p.date_payment) <= DATE_SUB('".$singledatefilter."',INTERVAL 1 DAY)" :'')."
			GROUP BY id_scheme_account )
			 AS total_pay ON total_pay.id_scheme_account = s.id_scheme_account 
 
			
 
		Where s.scheme_acc_number IS NOT NULL and s.active=1 and s.is_closed=0
		".($id_branch!='' && $id_branch!=0 && $branch==0 ? " and s.id_branch=".$id_branch."":'')."
		".($id_scheme!='' && $id_scheme!=0  ? " and s.id_scheme=".$id_scheme."":'')."
		".($id_company!='' && $id_company!=0 && $company_settings==1 ? " and sc.id_company=".$id_company."":'')."
		
		GROUP BY s.id_scheme"); 
		//  print_r($this->db->last_query());exit;
		$account_data=$accounts->result_array();
        foreach ($account_data as $key => $val) {
			$matchFoundCollectionAmt = false;
			$matchFoundClosedAmt = false;
		   $matchFoundnewClosedAmt = false;
		
			foreach ($collectionamt as $collection) {
				if ($val['id_scheme'] == $collection['id_scheme']) {
					$val['collection_amount'] = $collection['collectionamt'];
					$matchFoundCollectionAmt = true;
					break;
				}
			}
		
			foreach ($oldclosedamt as $ca) {
				if ($val['id_scheme'] == $ca['id_scheme']) {
					$val['oldclosed_amount'] = $ca['closed_amt'];
					$matchFoundClosedAmt = true;
					break;
				}
			}
			foreach ($newclosedamt as $ca) {
				if ($val['id_scheme'] == $ca['id_scheme']) {
					$val['closed_amount'] = $ca['closed_amt'];
					$matchFoundnewClosedAmt = true;
					break;
				}
			}
		
		
			if (!$matchFoundCollectionAmt) {
				$val['collection_amount'] = NULL;
			}
		
			if (!$matchFoundClosedAmt) {
				$val['oldclosed_amount'] = NULL;
			}
				if (!$matchFoundnewClosedAmt) {
				$val['closed_amount'] = NULL;
			}
		 // Calculate balance amount
		 $paidAmount = floatval($val['paid_amounts']);
		 $collectionAmount = floatval($val['collection_amount']);
		 $closedAmount = floatval($val['oldclosed_amount']);
		 $closedAmountnew = floatval($val['closed_amount']) ;
 // 		 print_r($paidAmount); echo '<br>'; exit;
		 
		$val['paid_amount']  =abs($paidAmount -  $closedAmount);
		 $balanceAmount = $val['paid_amount'] + $collectionAmount - $closedAmountnew;
 
	 
		 $val['balance_amount'] = $balanceAmount;
			$modifiedArray[] = $val;
		}
// print_r($modifiedArray);exit;
		return $modifiedArray;
		 //print_r($this->db->last_query());exit;
		//  return $accounts->result_array();
	 }


	 function get_oldclosed_amt($date=""){
		
		$from_date= $this->input->post('from_date');
		$to_date= $this->input->post('to_date');
		$id_branch  = $this->input->post('id_branch');
		$company_settings=$this->session->userdata('company_settings');
		$id_company = $this->session->userdata('id_company');
		$branch=$this->session->userdata('id_branch');
		$singledatefilter  = $this->input->post('singlefilter');
		
		$collection_accounts=$this->db->query("SELECT SUM(p.payment_amount) as closed_amt,sc.id_scheme,sc.is_lucky_draw
		FROM scheme_account s 
		LEFT JOIN scheme sc ON sc.id_scheme=s.id_scheme 
		left join payment p on p.id_scheme_account=s.id_scheme_account

		Where s.scheme_acc_number IS NOT NULL and s.active=0   and s.is_closed=1
		".($id_branch!='' && $id_branch!=0 && $branch==0 ? " and s.id_branch=".$id_branch."":'')."
		".($id_scheme!='' && $id_scheme!=0  ? " and s.id_scheme=".$id_scheme."":'')."
		".($id_company!='' && $id_company!=0 && $company_settings==1 ? " and sc.id_company=".$id_company."":'')."
        ".($date != '' ? " AND DATE(s.closing_date) <= DATE_SUB('".$date."', INTERVAL 1 DAY)" : '')."
		".($from_date!='' && $to_date!='' ? " AND DATE(p.date_payment) BETWEEN '" . $from_date . "' AND '" . $to_date . "'":'')."
		GROUP BY s.id_scheme "); 
		  //print_r($this->db->last_query()) ;exit;
	    return $collection_accounts->result_array();
	



	 }

	 function get_newclosed_amt($date=""){
		
		$from_date= $this->input->post('from_date');
		$to_date= $this->input->post('to_date');
		$id_branch  = $this->input->post('id_branch');
		$company_settings=$this->session->userdata('company_settings');
		$id_company = $this->session->userdata('id_company');
		$branch=$this->session->userdata('id_branch');
		$singledatefilter  = $this->input->post('singlefilter');
		
		$collection_accounts=$this->db->query("SELECT SUM(p.payment_amount) as closed_amt,sc.id_scheme,sc.is_lucky_draw
		FROM scheme_account s 
		LEFT JOIN scheme sc ON sc.id_scheme=s.id_scheme 
		left join payment p on p.id_scheme_account=s.id_scheme_account

		Where s.scheme_acc_number IS NOT NULL and s.active=0   and s.is_closed=1
		".($id_branch!='' && $id_branch!=0 && $branch==0 ? " and s.id_branch=".$id_branch."":'')."
		".($id_scheme!='' && $id_scheme!=0  ? " and s.id_scheme=".$id_scheme."":'')."
		".($id_company!='' && $id_company!=0 && $company_settings==1 ? " and sc.id_company=".$id_company."":'')."
        ".($date != '' ? " AND DATE(s.closing_date) = '".$date."'" : '')."
		".($from_date!='' && $to_date!='' ? " AND DATE(p.date_payment) BETWEEN '" . $from_date . "' AND '" . $to_date . "'":'')."
		GROUP BY s.id_scheme "); 
		  //print_r($this->db->last_query()) ;exit;
	    return $collection_accounts->result_array();
	



	 }
	 function get_collection_amt($date) {

		$from_date= $this->input->post('from_date');
		$to_date= $this->input->post('to_date');
		$id_branch  = $this->input->post('id_branch');
		$company_settings=$this->session->userdata('company_settings');
		$id_company = $this->session->userdata('id_company');
		$branch=$this->session->userdata('id_branch');
		$singledatefilter  = $this->input->post('singlefilter');
		
		$collection_accounts=$this->db->query("SELECT SUM(p.payment_amount) as collectionamt,sc.id_scheme,sc.is_lucky_draw
		FROM scheme_account s 
		LEFT JOIN scheme sc ON sc.id_scheme=s.id_scheme 
		left join payment p on p.id_scheme_account=s.id_scheme_account

		Where s.scheme_acc_number IS NOT NULL and s.active=1  and s.is_closed=0 and p.payment_status=1
		".($id_branch!='' && $id_branch!=0 && $branch==0 ? " and s.id_branch=".$id_branch."":'')."
		".($id_scheme!='' && $id_scheme!=0  ? " and s.id_scheme=".$id_scheme."":'')."
		".($id_company!='' && $id_company!=0 && $company_settings==1 ? " and sc.id_company=".$id_company."":'')."
		".($date!=''  ? " and date(p.date_payment) ='".$date."'":'')."
		".($from_date!='' && $to_date!='' ? " AND DATE(p.date_payment) BETWEEN '" . $from_date . "' AND '" . $to_date . "'":'')."
		GROUP BY s.id_scheme "); 
		    // print_r($this->db->last_query()) ;exit;
	    return $collection_accounts->result_array();
		
	}





	 function scheme_group_summary_data($id)
	{
		//$from_date= $this->input->post('from_date');
		//$to_date= $this->input->post('to_date');
		$id_branch  = $this->input->post('id_branch');
		$company_settings=$this->session->userdata('company_settings');
		$id_company = $this->session->userdata('id_company');
		$branch=$this->session->userdata('id_branch');
		$group_code=$this->input->post('id_group');
		//$is_live=$this->input->post('is_live');
		 $accounts=$this->db->query("SELECT sc.code,sc.id_metal,s.group_code,COUNT(s.group_code) as count,SUM(total_pay.totalpay_amount) as paid_amount,SUM(total_pay.metal_weight) as metal_weight,SUM((select IFNULL(IF(s.is_opening=1,IFNULL(s.paid_installments,0)+ IFNULL(if(sc.scheme_type = 1 and sc.min_weight != sc.max_weight, COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')), sum(pay.no_of_dues)),0), if(sc.scheme_type = 1 and sc.min_weight != sc.max_weight or (sc.scheme_type=3), COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')), sum(pay.no_of_dues))) ,0) from payment pay where pay.payment_status=1 and pay.id_scheme_account=s.id_scheme_account group by pay.id_scheme_account)) as paid_installments 
		 FROM scheme_account s
		 LEFT JOIN scheme sc ON sc.id_scheme=s.id_scheme
		 LEFT JOIN (SELECT id_scheme_account,SUM(IFNULL(payment_amount,0)) AS totalpay_amount,SUM(IFNULL(metal_weight,0)) as metal_weight FROM payment Where payment_status=1 GROUP BY id_scheme_account ) AS total_pay ON total_pay.id_scheme_account = s.id_scheme_account 
		 Where s.scheme_acc_number IS NOT NULL and s.active=1 and s.id_scheme= $id and sc.active=1 and s.group_code IS NOT NULL 
		 ".($id_branch!='' && $id_branch!=0 && $branch==0 ? " and s.id_branch=".$id_branch."":'')."
		 ".($id_company!='' && $id_company!=0 && $company_settings==1 ? " and sc.id_company=".$id_company."":'')."
		  
		 ".($group_code!=''  ? " and s.group_code ='".$group_code."'":'')."
		 GROUP BY s.group_code"); 
				//print_r($this->db->last_query());exit;
		$account=$accounts->result_array();
		foreach($account as $r)
        {
			$return_data[$r['group_code']]=$r;
		}
		 return $return_data;
	}
	function is_luckly_draw_scheme($id)
	{
		$accounts=$this->db->query("SELECT s.id_scheme, s.scheme_name, s.code,s.is_lucky_draw 
			FROM scheme s where s.visible=1 and s.active=1 and s.id_scheme = $id");
		$account=$accounts->row_array();
		return $account;
	}
	function get_group_scheme_code($id)
	{
		$accounts=$this->db->query("SELECT sg.id_scheme_group,sg.group_code,sg.id_scheme FROM `scheme_group` sg WHERE sg.id_scheme = $id");
		$account=$accounts->result_array();
		return $account;
	}
	 /*SCHEME WISE OUTSTANDING REPORT ENDS */

	
}


?>
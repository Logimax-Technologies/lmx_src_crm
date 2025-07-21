<?php
if( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dashboard_model extends CI_Model
{
	const ACC_TABLE 		= "scheme_account";
	const CUS_TABLE			= "customer";
	const SCH_TABLE			= "scheme";
	const PAY_TABLE			= "payment";
	const ENQ_TABLE			= "cust_enquiry";
	const ADD_TABLE			= "address";
	const SCH_REQ  			="scheme_reg_request";
	const BRANCH  			="branch";
	function enquiry_report($filterBy)
	{
	  $result=0;	
	   $sql="Select count(id_enquiry) as total from ".self::ENQ_TABLE;	
		switch($filterBy){
			case 'Y': //yesterday
			         $sql=$sql." WHERE Date(`date_enquiry`) = CURDATE() - INTERVAL 1 DAY ";   
				break;
			case 'T': //Today
			         $sql=$sql." WHERE Date(`date_enquiry`) = CURDATE() ";   
				break;
			case 'LW': //Last Week
			          $sql=$sql." WHERE Date(`date_enquiry`) BETWEEN (CURDATE() � INTERVAL DAYOFWEEK(CURDATE())+6 DAY) AND (CURDATE() � INTERVAL DAYOFWEEK(CURDATE())-1 DAY) ";   
				break;
			case 'TW': //This Week
			          $sql=$sql." WHERE Date(`date_enquiry`) BETWEEN DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY) AND DATE_ADD(CURDATE(), INTERVAL 7-DAYOFWEEK(CURDATE()) DAY) ";   
				break;	
			case 'TM': //This Month
			          $sql=$sql." WHERE Date(`date_enquiry`) BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) AND LAST_DAY(NOW()) ";   
				break;
			case 'ALL':
			         $sql=$sql."";
				break;
		}	
		$enquiry=$this->db->query($sql);
		if($enquiry->num_rows()>0)
		{
			$result= $enquiry->row('total');
		}
		return $result;
	}	
	function enquiry_detail_report($filterBy)
	{
	  $sql="Select * from ".self::ENQ_TABLE;	
		switch($filterBy){
			case 'Y': //yesterday
			         $sql=$sql." WHERE Date(`date_enquiry`) = CURDATE() - INTERVAL 1 DAY ";   
				break;
			case 'T': //Today
			         $sql=$sql." WHERE Date(`date_enquiry`) = CURDATE() ";   
				break;
			case 'LW': //Last Week
			          $sql=$sql." WHERE Date(`date_enquiry`) BETWEEN (CURDATE() � INTERVAL DAYOFWEEK(CURDATE())+6 DAY) AND (CURDATE() � INTERVAL DAYOFWEEK(CURDATE())-1 DAY) ";   
				break;
			case 'TW': //This Week
			          $sql=$sql." WHERE Date(`date_enquiry`) BETWEEN DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY) AND DATE_ADD(CURDATE(), INTERVAL 7-DAYOFWEEK(CURDATE()) DAY) ";   
				break;	
			case 'TM': //This Month
			          $sql=$sql." WHERE Date(`date_enquiry`) BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) AND LAST_DAY(NOW()) ";   
				break;
			case 'ALL':
			         $sql=$sql."";
				break;
		}	
		$r=$this->db->query($sql);
		return $r->result_array();
	}
	/* coded by RANJ */
	function cust_wo_acc()
	{

		 $sql="SELECT c.id_customer
		 		FROM customer c
				WHERE NOT EXISTS(
				    SELECT 1 FROM scheme_account sa
				    WHERE c.id_customer=sa.id_customer AND sa.is_closed=0
				)";
			$count = $this->db->query($sql);
			return $count->num_rows();
	}
	
	function acc_wo_pay()
	{
		$branchWiseLogin=$this->session->userdata('branchWiseLogin');
			$id_branch=$this->session->userdata('id_branch');
			$uid=$this->session->userdata('uid');
			$dashboard_branch=$this->session->userdata('dashboard_branch');
		 $sql="SELECT sa.id_scheme_account
				FROM scheme_account sa
				LEFT join branch b on (sa.id_branch=b.id_branch)
        			WHERE NOT EXISTS(
						SELECT p.id_scheme_account FROM payment p
						WHERE sa.id_scheme_account = p.id_scheme_account
						)AND sa.paid_installments=0 AND sa.is_closed=0 	".($dashboard_branch!=0 ? "and sa.id_branch=".$dashboard_branch :'')." ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( sa.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')." ";
			$count1 = $this->db->query($sql);
			return $count1->num_rows();
	}
	/* /coded by RANJ */
	 /* coded by vishnu*/
/* 	function cust_wo_acc_details()
	{
		$sql="SELECT c.id_customer,concat (c.firstname,' ',if(c.lastname!='',c.lastname,'')) as name,
				if(c.is_new='Y','New User','Existing User')as is_new,
				if(c.profile_complete=1,'Yes','No') as profile_complete,
				if(c.active=1,'Active','Disabled') as active,
				if(c.added_by=0,'Web App',if(c.added_by=1,'Admin','Mobile App'))as reg_by,
				c.mobile,c.date_add
					FROM customer c
					WHERE NOT EXISTS(
						SELECT 1 FROM scheme_account sa
						WHERE c.id_customer=sa.id_customer AND sa.is_closed=0
					)";
			$r=$this->db->query($sql);
		return $r->result_array();
	} */
	
	function cust_wo_acc_details()
	{
		$sql="SELECT c.id_customer,
				concat (c.firstname,' ',if(c.lastname!='',c.lastname,'')) as name,
				if(c.is_new='Y','New User','Existing User')as is_new,
				if(c.profile_complete=1,'Yes','No') as profile_complete,
				if(c.active=1,'Active','Disabled') as active,
				if(c.added_by=0,'Web App',if(c.added_by=1,'Admin','Mobile App'))as added_by,
				c.date_add, c.mobile
						FROM customer c
						WHERE NOT EXISTS(
						SELECT 1 FROM scheme_account sa
						WHERE c.id_customer=sa.id_customer AND sa.is_closed=0
					)";		
					//print_r($sql);exit;			
			$r=$this->db->query($sql); 
		return $r->result_array();
	}
	 /* /coded by vishnu*/
	function reg_stat($filterBy)
	{
	  $result=0;	
	   $sql="Select count(id_customer) as total from ".self::CUS_TABLE;	
		switch($filterBy){
			case 'Y': //yesterday
			         $sql=$sql." WHERE Date(`date_add`) = CURDATE() - INTERVAL 1 DAY ";   
				break;
			case 'T': //Today
			         $sql=$sql." WHERE Date(`date_add`) = CURDATE() ";   
				break;
			case 'LW': //Last Week
			          $sql=$sql." WHERE Date(`date_add`) BETWEEN (CURDATE() � INTERVAL DAYOFWEEK(CURDATE())+6 DAY) AND (CURDATE() � INTERVAL DAYOFWEEK(CURDATE())-1 DAY) ";   
				break;
			case 'TW': //This Week
			          $sql=$sql." WHERE Date(`date_add`) BETWEEN DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY) AND DATE_ADD(CURDATE(), INTERVAL 7-DAYOFWEEK(CURDATE()) DAY) ";   
				break;	
			case 'TM': //This Month
			          $sql=$sql." WHERE Date(`date_add`) BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) AND LAST_DAY(NOW()) ";   
				break;
			case 'ALL':
			         $sql=$sql."";
				break;
			case 'PC': //profile complete
			        $sql=" Select count(id_customer) as total from ".self::CUS_TABLE." where profile_complete = 1 ";
			    break;
			case 'PI': //profile Incomplete
			        $sql=" Select count(id_customer) as total from ".self::CUS_TABLE." where profile_complete is null or profile_complete!=1";
			    break;	
		}	
		$r=$this->db->query($sql);
		if($r->num_rows()>0)
		{
			$result= $r->row('total');
		}
		return $result;
	}
	function req_stat($filterBy)
	{
		$branchWiseLogin=$this->session->userdata('branchWiseLogin');
			$id_branch=$this->session->userdata('id_branch');
			$uid=$this->session->userdata('uid');
			$dashboard_branch=$this->session->userdata('dashboard_branch');
	  $result=0;	
	   $sql="Select count(id_reg_request) as total from scheme_reg_request sr 
	   			left join branch b on (b.id_branch=sr.id_branch) ";	
		switch($filterBy){
			case 'Y': //yesterday
			         $sql=$sql." WHERE Date(`date_add`) = CURDATE() - INTERVAL 1 DAY ".($dashboard_branch!=0 ? "and sr.id_branch=".$dashboard_branch :'')." ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( sr.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')."";   
				break;
			case 'T': //Today
			         $sql=$sql." WHERE Date(`date_add`) = CURDATE() ".($dashboard_branch!=0 ? "and sr.id_branch=".$dashboard_branch :'')." ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( sr.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')."";   
				break;
			case 'LW': //Last Week
			          $sql=$sql." WHERE Date(`date_add`) BETWEEN (CURDATE() � INTERVAL DAYOFWEEK(CURDATE())+6 DAY) AND (CURDATE() � INTERVAL DAYOFWEEK(CURDATE())-1 DAY)  ".($dashboard_branch!=0 ? "and sr.id_branch=".$dashboard_branch :'')." ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( sr.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')."";   
				break;
			case 'TW': //This Week
			          $sql=$sql." WHERE Date(`date_add`) BETWEEN DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY) AND DATE_ADD(CURDATE(), INTERVAL 7-DAYOFWEEK(CURDATE()) DAY) ".($dashboard_branch!=0 ? "and sr.id_branch=".$dashboard_branch :'')." ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( sr.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')."";   
				break;	
			case 'TM': //This Month
			          $sql=$sql." WHERE Date(`date_add`) BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) AND LAST_DAY(NOW()) ".($dashboard_branch!=0 ? "and sr.id_branch=".$dashboard_branch :'')." ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( sr.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')."";   
				break;
			case 'ALL':
			         //$sql=$sql."      ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " Where  sr.id_branch=".$id_branch." or b.show_to_all=1 ":''):''):'')."";
					  $sql=$sql."   ".($dashboard_branch!=0 ? " Where sr.id_branch=".$dashboard_branch :'')."  ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and  sr.id_branch=".$id_branch." or b.show_to_all=1 ":''):''):'')."";
			   //  print_r($sql);exit;
				break;
		}	
		$r=$this->db->query($sql);
		if($r->num_rows()>0)
		{
			$result= $r->row('total');
		}
		return $result;
	}
/*Coded by ARVK*/
	function get_scheme()
	{
		$sql= "select s.id_scheme from scheme s";
		$count = $this->db->query($sql)->num_rows();
		return $count;
	}
	function get_scheme_group()
	{
		$sql= "select sg.id_scheme_group from scheme_group sg";
		$count = $this->db->query($sql)->num_rows();
		return $count;
	}
	
	function acc_wo_pay_details()
	{
		$sql= "SELECT sa.id_scheme_account,if(sa.scheme_acc_number  is not null,sa.scheme_acc_number,'Not Allocated') as scheme_acc_number, IFNULL(sa.group_code,'')as group_code,cs.has_lucky_draw,
				   concat (c.firstname,' ',if(c.lastname!=Null,c.lastname,'')) as name,
	       		   c.mobile, s.code, sa.start_date,s.scheme_type as sch_type,
	       		    if(s.scheme_type=0,'Amount',if(s.scheme_type=1,'Weight',if(s.scheme_type=3,'FLEXIBLE_AMOUNT','Amount To Weight')))as scheme_type, 
	       		   if(sa.added_by=0,'Web App',if(sa.added_by=1,'Admin','Mobile App'))as added_by,
	       		   s.total_installments,  CAST(if(s.scheme_type=1,s.max_weight,if(s.scheme_type=3 && s.max_amount!=0,s.max_amount,if(s.scheme_type=3 && s.max_amount=0,(s.max_weight*(SELECT m.goldrate_22ct FROM metal_rates m  order by id_metalrates Desc LIMIT 1)),s.amount))) as int) as amount, s.max_weight, cs.currency_symbol
	         	FROM scheme_account sa
			        LEFT JOIN customer c ON (c.id_customer = sa.id_customer)
			        LEFT JOIN scheme s ON (s.id_scheme = sa.id_scheme)
			        JOIN chit_settings cs
				        WHERE NOT EXISTS(SELECT p.id_scheme_account FROM payment p
										WHERE sa.id_scheme_account = p.id_scheme_account)
						AND sa.paid_installments=0 AND sa.is_closed=0";
		$data = $this->db->query($sql)->result_array();
		// print_r($data);exit;
		return $data;
	}
	function total_payment_details()
	{
		$branchWiseLogin=$this->session->userdata('branchWiseLogin');
			$id_branch=$this->session->userdata('id_branch');
			$uid=$this->session->userdata('uid');
			$dashboard_branch=$this->session->userdata('dashboard_branch');
		$sql= "SELECT p.id_payment,cs.has_lucky_draw,s.code,
		  sa.account_name,if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,c.mobile,if(p.payment_ref_number = '','-',p.payment_ref_number) as ref_no,s.code,IFNULL(sa.group_code,'')as group_code,IFNULL(scheme_acc_number,'Not Allocated')as scheme_acc_number,if(s.scheme_type=0,'Amount',if(s.scheme_type=1,'Weight',if(s.scheme_type=3,'FLEXIBLE_AMOUNT','Amount To Weight')))as scheme_type, 
		  p.payment_amount,p.date_payment,p.payment_mode,p.bank_acc_no,bank_name,
		  p.bank_IFSC,p.id_transaction, psm.payment_status,psm.color,p.remark
		 FROM ".self::PAY_TABLE." p
		 left join ".self::ACC_TABLE." sa on(p.id_scheme_account=sa.id_scheme_account)
		 Left Join ".self::CUS_TABLE." c on (sa.id_customer=c.id_customer)
		 left join ".self::SCH_TABLE." s on(sa.id_scheme=s.id_scheme) 
		  left join ".self::BRANCH." b on(b.id_branch=sa.id_branch) 
		 Left Join payment_status_message psm On (p.payment_status=psm.id_status_msg)
		 join chit_settings cs
		  WHERE p.payment_status =1 ".($dashboard_branch!='' ? "and sa.id_branch=".$dashboard_branch :'')." ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( sa.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')." ";
		$data = $this->db->query($sql)->result_array();
		return $data;
	}
	function payment_join($filterBy)
	{
		$branchWiseLogin=$this->session->userdata('branchWiseLogin');
			$id_branch=$this->session->userdata('id_branch');
			$uid=$this->session->userdata('uid');
			$dashboard_branch=$this->session->userdata('dashboard_branch');
	  $result=0;	
	   $sql="Select COUNT(id_payment) as joined_thro From payment p
	   		LEFT join scheme_account sa on (sa.id_scheme_account=p.id_scheme_account)
	   		LEFT join branch b on (b.id_branch=sa.id_branch)";	
		switch($filterBy){
			case 'MOB': //Joined thro Mobile App
			         $sql=$sql." where p.added_by = 2 and payment_status=1 ".($dashboard_branch!=0 ? "and sa.id_branch=".$dashboard_branch :'')." ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( sa.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')."  ";   
				break;
			case 'WEB': //Joined thro Web App
			         $sql=$sql." where p.added_by = 1 and payment_status=1 ".($dashboard_branch!=0 ? "and sa.id_branch=".$dashboard_branch :'')." ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( sa.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')." ";   
				break;
			case 'ADMIN': //Joined thro Admin
			          $sql=$sql." where p.added_by = 0 and payment_status=1 ".($dashboard_branch!=0 ? "and sa.id_branch=".$dashboard_branch :'')." ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( sa.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')." ";   
				break;
			case 'COLLECTION': //Joined thro Admin
			          $sql=$sql." where p.added_by = 3 and payment_status=1 ".($dashboard_branch!=0 ? "and sa.id_branch=".$dashboard_branch :'')." ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( sa.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')." ";   
				break;
		}	
		return $this->db->query($sql)->row_array();		
	}
	function customer_join($filterBy)
	{
	  $result=0;	
	  $branchWiseLogin=$this->session->userdata('branchWiseLogin');
			$id_branch=$this->session->userdata('id_branch');
			$uid=$this->session->userdata('uid');
			$dashboard_branch=$this->session->userdata('dashboard_branch');	
	   $sql="Select COUNT(id_customer) as joined_thro From customer c";	
		switch($filterBy){
			case 'MOB': //Joined thro Mobile App
			         $sql=$sql." where added_by = 2 ";   
				break;
			case 'WEB': //Joined thro Web App
			         $sql=$sql." where added_by = 0 ";   
				break;
			case 'ADMIN': //Joined thro Admin
			          $sql=$sql."  where added_by = 1  ".($uid!=1 ? ($branchWiseLogin==1 ? " and c.id_employee=".$this->session->userdata('uid')." " :'') : '')." ";    
				break;	
		}	
		return $this->db->query($sql)->row_array();		
	}	
/*  / Coded by ARVK*/	
	function reg_detail_stat($filterBy)
	{
	  $result=0;	
			$branchWiseLogin=$this->session->userdata('branchWiseLogin');
			$id_branch=$this->session->userdata('id_branch');
			$uid=$this->session->userdata('uid');
			$dashboard_branch=$this->session->userdata('dashboard_branch');	
			$sql="select
	        c.id_customer,
			concat (c.firstname,' ',if(c.lastname!=Null,c.lastname,'')) as name,
			c.date_of_birth,
			c.mobile,
			if(profile_complete=1,'Yes','No') as profile_complete,
			if(c.active=1,'Active','Disabled') as active,
			if(c.is_new=0,'New','Existing') as is_new,
			c.date_add,
			if(c.added_by=0,'Web App',if(c.added_by=1,'Admin','Mobile App'))as added_by,sa.closing_date,ifnull(sa.closing_balance,0.00) as closing_balance
			from customer c 
			left join scheme_account sa on(c.id_customer=sa.id_customer) ";	
		switch($filterBy){
			case 'Y': //yesterday
			         $sql=$sql." WHERE Date(c.`date_add`) = CURDATE() - INTERVAL 1 DAY";   
				break;
			case 'T': //Today
			         $sql=$sql." WHERE Date(c.`date_add`) = CURDATE()";   
				break;
			case 'LW': //Last Week
					$sql=$sql." WHERE Date(`date_add`) BETWEEN (CURDATE() � INTERVAL DAYOFWEEK(CURDATE())+6 DAY) AND (CURDATE() � INTERVAL DAYOFWEEK(CURDATE())-1 DAY) ";     
				break;
			case 'TW': //This Week
			          $sql=$sql." WHERE Date(c.`date_add`) BETWEEN DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY) AND DATE_ADD(CURDATE(), INTERVAL 7-DAYOFWEEK(CURDATE()) DAY) ";   
				break;	
			case 'TM': //This Month
			          $sql=$sql." WHERE Date(c.`date_add`) BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) AND LAST_DAY(NOW()) ";   
				break;	
			case 'MA': //Mobile App
			          $sql=$sql." WHERE c.added_by = 2";   
				break;	
			case 'WA': //Web App
			          $sql=$sql." WHERE c.added_by = 0 ";   
				break;	
			case 'a': //Admin
			          $sql=$sql." WHERE c.added_by = 1 ".($uid!=1 ? ($branchWiseLogin==1 ? " and c.id_employee=".$this->session->userdata('uid')." " :'') : '')." ";   
				break;
			// case 'ALL':
			// 		//$id_branch = $this->input->post('id_branch');
			// 		$from_date=$this->input->post('from_date');
			// 		$to_date=$this->input->post('to_date');
			// 		//print_r($to_date);exit;
			//          $sql=$sql."where (date(c.`date_add`) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') ";
			// 	break;
			case 'ALL':
					
			         $sql=$sql."";
				break;
			case 'PC': //profile complete
			        $sql=$sql." where c.profile_complete = 1";
			    break;
			case 'PI': //profile Incomplete
			        $sql=$sql." where c.profile_complete != 1 or c.profile_complete = ''   ";
			    break;	
		}	
		$sql = $sql." group by c.id_customer ";
		$r=$this->db->query($sql);
		return $r->result_array();
	}
	
	function account_stat($filterBy)
	{
		$branchWiseLogin=$this->session->userdata('branchWiseLogin');
			$id_branch=$this->session->userdata('id_branch');
			$uid=$this->session->userdata('uid');
			$dashboard_branch=$this->session->userdata('dashboard_branch');
	  $result=0;	
	   $sql="Select count(id_scheme_account) as total from ".self::ACC_TABLE." s 
	   		Left join ".self::BRANCH." b on (s.id_branch=b.id_branch)";
		switch($filterBy){
			case 'Y': //yesterday
			         $sql=$sql." WHERE Date(`date_add`) = CURDATE() - INTERVAL 1 DAY AND s.active='1' ".($dashboard_branch!=0 ? "and s.id_branch=".$dashboard_branch :'')."  ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( s.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')." ";   
				break;
			case 'T': //Today
			         $sql=$sql." WHERE Date(`date_add`) = CURDATE() AND s.active='1' ".($dashboard_branch!=0 ? "and s.id_branch=".$dashboard_branch :'')." ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( s.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')."  ";   
				break;
			case 'LW': //Last Week
			          $sql=$sql." WHERE Date(`date_add`) BETWEEN (CURDATE() � INTERVAL DAYOFWEEK(CURDATE())+6 DAY) AND (CURDATE() � INTERVAL DAYOFWEEK(CURDATE())-1 DAY) AND s.active='1' ".($dashboard_branch!=0 ? "and s.id_branch=".$dashboard_branch :'')." ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( s.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')." ";   
				break;
			case 'TW': //This Week
			          $sql=$sql." WHERE Date(`date_add`) BETWEEN DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY) AND DATE_ADD(CURDATE(), INTERVAL 7-DAYOFWEEK(CURDATE()) DAY) AND s.active='1' ".($dashboard_branch!=0 ? "and s.id_branch=".$dashboard_branch :'')." ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( s.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')."  ";   
				break;	
			case 'TM': //This Month
			          $sql=$sql." WHERE Date(`date_add`) BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) AND LAST_DAY(NOW()) AND s.active='1' ".($dashboard_branch!=0 ? "and s.id_branch=".$dashboard_branch :'')." ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( s.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')." ";   
				break;
			case 'ALL':
			         $sql=$sql."where s.active=1 and s.is_closed=0 ".($dashboard_branch!=0 ? "and s.id_branch=".$dashboard_branch :'')." ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( s.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')." ";
				break;	
		}	
		$r=$this->db->query($sql);
		if($r->num_rows()>0)
		{
			$result= $r->row('total');
		}
		return $result;
	}
/*Function added by ARVK*/
	function account_join($filterBy)
	{
	$branchWiseLogin=$this->session->userdata('branchWiseLogin');
			$id_branch=$this->session->userdata('id_branch');
			$uid=$this->session->userdata('uid');
			$dashboard_branch=$this->session->userdata('dashboard_branch');
	  $result=0;	
	   $sql="Select COUNT(id_scheme_account) as joined_thro From scheme_account sa
	   		LEFT join branch b on (b.id_branch=sa.id_branch)";	
		switch($filterBy){
			case 'MOB': //Joined thro Mobile App
			         $sql=$sql." where added_by = 2 ".($dashboard_branch!=0 ? "and sa.id_branch=".$dashboard_branch :'')."".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( sa.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')." ";   
				break;
			case 'WEB': //Joined thro Web App
			         $sql=$sql." where added_by = 0  ".($dashboard_branch!=0 ? "and sa.id_branch=".$dashboard_branch :'')." ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( sa.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')."";  
				break;
			case 'ADMIN': //Joined thro Admin
			          $sql=$sql." where added_by = 1 ".($dashboard_branch!=0 ? "and sa.id_branch=".$dashboard_branch :'')." ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( sa.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')."";  
				break;
			case 'COLLECTION': //Joined thro Admin
			          $sql=$sql." where added_by = 3 ".($dashboard_branch!=0 ? "and sa.id_branch=".$dashboard_branch :'')." ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( sa.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')."";  
				break;	
			
		}	
		return $this->db->query($sql)->row_array();		
	}	
/*  / Function added by ARVK*/	
/*Function added by ARVK with KCP's query*/
	function due_stat($filterBy)
	{
	  $result=0;	
	   $sql="Select sa.scheme_acc_number, count(d.next_due) as due_count, sum(d.payment_amount) as amount	From scheme_account sa
  Left Join
     (Select
sa.id_scheme_account,p.payment_amount,
       CASE
         WHEN sa.is_opening='1' AND p.date_payment is null THEN Date_add(sa.last_paid_date,Interval 1 month)
         when p.date_payment is null and sa.is_opening='0' then sa.date_add
      ELSE Date_add(max(p.date_payment),Interval 1 month)
       END AS next_due
From scheme_account sa
Left Join payment p on(p.id_scheme_account=sa.id_scheme_account and sa.active=1 and sa.is_closed=0 and p.payment_status='1')
           Group By sa.id_scheme_account)d on(d.id_scheme_account=sa.id_scheme_account)";	
		switch($filterBy){
			case 'T': //Today Due
			         $sql=$sql." where scheme_acc_number !='null' and date(d.next_due)= CURDATE() AND sa.is_closed!=1";   
				break;
			case 'Y': //yesterday Due
			         $sql=$sql." where scheme_acc_number !='null' and date(d.next_due)= CURDATE()- INTERVAL 1 DAY AND sa.is_closed!=1";   
				break;
			case 'TW': //This Week Due
			         //$sql=$sql." where date(d.next_due)= CURDATE() - INTERVAL 1 WEEK ";   
			         $sql=$sql." where scheme_acc_number !='null' and Date(d.`next_due`) BETWEEN DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY) AND DATE_ADD(CURDATE(), INTERVAL 7-DAYOFWEEK(CURDATE()) DAY) AND sa.is_closed!=1";   
				break;
			case 'ALL': //all dues 
			        $sql=$sql." ";   
				break;
			case 'TM': //This Month Due
			         //$sql=$sql." where date(d.next_due)= CURDATE() - INTERVAL 1 WEEK ";   
			         $sql=$sql." where scheme_acc_number !='null' and Date(d.`next_due`) BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) AND LAST_DAY(NOW()) AND sa.is_closed!=1";   
				break;			
		}	
		// print_r($sql);exit;
		return $this->db->query($sql)->row_array();		
	}	
/*  / Function added by ARVK with KCP's query*/	
/*Function added by ARVK with KCP's query*/
	function due_list($filterBy)
	{
	  $result=0;	
	   $sql="Select sa.id_scheme_account,IFNULL(sa.group_code,'')as group_code,
	   		IFNULL(sa.scheme_acc_number,'')as scheme_acc_number,p.payment_amount as amount,
	   		cs.has_lucky_draw,
			  if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,
			  c.mobile,s.code,
			  if(s.scheme_type=0,'Amount',if(s.scheme_type=1,'Weight',if(s.scheme_type=3 && s.flexible_sch_type=1,'Flx Amount',if(s.scheme_type=3 && s.flexible_sch_type=2,'Flx AmtToWgt[Amt]',if(s.scheme_type=3 && s.flexible_sch_type=3,'Flx AmtToWgt[Wgt]',if(s.scheme_type=3 && s.flexible_sch_type=4,'Flx Wgt [Wgt]','Amount To Weight'))))))as scheme_type,
			  IFNULL(Date_Format(MAX(DISTINCT  p.date_payment),'%d-%m-%Y'),IFNULL(Date_Format(sa.last_paid_date,'%d-%m-%Y'),'-')) AS last_paid_date,
			  IFNULL(MONTHNAME(IFNULL(MAX(DISTINCT  p.date_payment),sa.last_paid_date)),'-') AS last_paid_month,
	   Date_Format(d.next_due,'%d-%m-%Y') as due_date	From scheme_account sa 
  Left Join
     (Select
sa.id_scheme_account,
       CASE
         WHEN sa.is_opening='1' AND p.date_payment is null THEN Date_add(sa.last_paid_date,Interval 1 month)
         when p.date_payment is null and sa.is_opening='0' then sa.date_add
      ELSE Date_add(max(p.date_payment),Interval 1 month)
       END AS next_due
From scheme_account sa
Left Join payment p on(p.id_scheme_account=sa.id_scheme_account and sa.active=1 and sa.is_closed=0 and p.payment_status='1')
           Group By sa.id_scheme_account)d on(d.id_scheme_account=sa.id_scheme_account)
LEFT JOIN payment p ON (sa.id_scheme_account = p.id_scheme_account AND p.payment_status = 1)
           LEFT JOIN customer c ON (sa.id_customer = c.id_customer)
           join chit_settings cs
LEFT JOIN scheme s ON (sa.id_scheme = s.id_scheme) where scheme_acc_number!= ''" ;	
		switch($filterBy){
			case 'T': //Today Due
			         $sql=$sql." and date(d.next_due)= CURDATE() ";   
				break;
			case 'Y': //Yesterday Due
			         $sql=$sql." and date(d.next_due)= CURDATE()- INTERVAL 1 DAY ";   
				break;
			case 'TW': //This Week Due
			         //$sql=$sql." where date(d.next_due)= CURDATE() - INTERVAL 1 WEEK ";   
			         $sql=$sql." and Date(d.`next_due`) BETWEEN DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY) AND DATE_ADD(CURDATE(), INTERVAL 7-DAYOFWEEK(CURDATE()) DAY) ";   
				break;
			case 'ALL': //all dues 
			        $sql=$sql." ";   
				break;
			case 'TM': //This Month
			          $sql=$sql." and Date(d.next_due) BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) AND LAST_DAY(NOW()) ";   
				break;	
		}	
				$sql=$sql." GROUP BY sa.id_scheme_account ";
				//print_r($sql);exit;
				$r=$this->db->query($sql);
		return $r->result_array();
		//return $this->db->query($sql)->row_array();		
	}	
/*  / Function added by ARVK with KCP's query*/	
	function acc_detail_stat($filterBy)
	{
		$branchWiseLogin=$this->session->userdata('branchWiseLogin');
			$id_branch=$this->session->userdata('id_branch');
			$uid=$this->session->userdata('uid');
			$dashboard_branch=$this->session->userdata('dashboard_branch');
	  $result=0;	
	   $sql="select
			  s.id_scheme_account,if(s.scheme_acc_number is not null,s.scheme_acc_number,'Not Allocated')as scheme_acc_number,sc.code,cs.has_lucky_draw,IFNULL(s.group_code,'')as group_code,concat (c.firstname,' ',if(c.lastname!=Null,c.lastname,'')) as name,s.ref_no,s.account_name,s.start_date,if(s.added_by=0,'Web App',if(s.added_by=1,'Admin',if(s.added_by=5,'Offline','Mobile App')))as added_by,			  
			    if(sc.scheme_type=0,'Amount',if(sc.scheme_type=1,'Weight',if(sc.scheme_type=3 && sc.flexible_sch_type=1,'Flx Amount',if(sc.scheme_type=3 && sc.flexible_sch_type=2,'Flx AmtToWgt[Amt]',if(sc.scheme_type=3 && sc.flexible_sch_type=3,'Flx AmtToWgt[Wgt]',if(sc.scheme_type=3 && sc.flexible_sch_type=4,'Flx Wgt [Wgt]','Amount To Weight'))))))as scheme_type,
			  sc.scheme_name,sc.code,sc.total_installments,sc.max_weight,sc.max_chance,c.mobile,s.date_add,
			FORMAT(if(sc.scheme_type=1,sc.max_weight,if(sc.scheme_type=3 && sc.max_amount!=0,sc.max_amount,if(sc.scheme_type=3 && sc.max_amount=0,(sc.max_weight*(SELECT m.goldrate_22ct FROM metal_rates m  order by id_metalrates Desc LIMIT 1)),sc.amount))),2) as amount
			from
			  ".self::ACC_TABLE." s
			left join ".self::CUS_TABLE." c on (s.id_customer=c.id_customer)
			join chit_settings cs
			left join ".self::SCH_TABLE." sc on (sc.id_scheme=s.id_scheme) 
			left join ".self::BRANCH." b on (s.id_branch=b.id_branch)";	
		switch($filterBy){
			case 'Y': //yesterday
			         $sql=$sql." WHERE Date(s.`date_add`) = CURDATE() - INTERVAL 1 DAY and s.active =1  ".($dashboard_branch!=0 ? "and s.id_branch=".$dashboard_branch :'')." ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( s.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')." ";   
				break;
			case 'T': //Today
			         $sql=$sql." WHERE Date(s.`date_add`) = CURDATE() and s.active =1 	".($dashboard_branch!=0 ? "and s.id_branch=".$dashboard_branch :'')." ".($uid!=1?($branchWiseLogin==1?($id_branch!='' ? "and b.id_branch=".$id_branch. " or b.show_to_all=1":''):'') :'')." ";   
				break;
			case 'LW': //Last Week
			          $sql=$sql." WHERE Date(s.`date_add`) BETWEEN (CURDATE() � INTERVAL DAYOFWEEK(CURDATE())+6 DAY) AND (CURDATE() � INTERVAL DAYOFWEEK(CURDATE())-1 DAY) and s.active =1 	".($dashboard_branch!=0 ? "and s.id_branch=".$dashboard_branch :'')." ".($uid!=1?($branchWiseLogin==1?($id_branch!='' ? "and b.id_branch=".$id_branch. " or b.show_to_all=1":''):'') :'')." ";   
				break;
			case 'TW': //This Week
			          $sql=$sql." WHERE Date(s.`date_add`) BETWEEN DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY) AND DATE_ADD(CURDATE(), INTERVAL 7-DAYOFWEEK(CURDATE()) DAY) and s.active =1 	".($dashboard_branch!=0 ? "and s.id_branch=".$dashboard_branch :'')."".($uid!=1?($branchWiseLogin==1?($id_branch!='' ? "and b.id_branch=".$id_branch. " or b.show_to_all=1":''):'') :'')."  ";   
				break;	
			case 'TM': //This Month
			          $sql=$sql." WHERE Date(s.`date_add`) BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) AND LAST_DAY(NOW()) and s.active = 1 	".($dashboard_branch!=0 ? "and s.id_branch=".$dashboard_branch :'')." ".($uid!=1?($branchWiseLogin==1?($id_branch!='' ? "and b.id_branch=".$id_branch. " or b.show_to_all=1":''):'') :'')." ";   
				break;
			case 'MA': //Mobile App
			          $sql=$sql." WHERE s.added_by = 2 	".($dashboard_branch!=0 ? "and s.id_branch=".$dashboard_branch :'')." ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( s.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')." ";   
				break;	
			case 'WA': //Web App
			          $sql=$sql." WHERE s.added_by = 0 	".($dashboard_branch!=0 ? "and s.id_branch=".$dashboard_branch :'')." ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( s.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')." ";   
				break;	
			case 'A': //Admin
			          $sql=$sql." WHERE s.added_by = 1  	".($dashboard_branch!=0 ? "and s.id_branch=".$dashboard_branch :'')."".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( s.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')." ";   
				break;
			case 'ALL':
			         $sql=$sql." where s.active=1 and s.is_closed=0  	".($dashboard_branch!=0 ? "and s.id_branch=".$dashboard_branch :'')."".($uid!=1?($branchWiseLogin==1?($id_branch!='' ? "and b.id_branch=".$id_branch. " or b.show_to_all=1":''):'') :'')."";
				break;
			case 'PC': //profile complete
			        $sql=$sql." where c.profile_complete = 1 and s.active =1 ";
			    break;
			case 'PI': //profile Incomplete
			        $sql=$sql." where c.profile_complete != 1 or c.profile_complete = ''  and s.active =1  ";
			    break;	
		}	
		$r=$this->db->query($sql);
		return $r->result_array();
	}
	function pay_stat($filterBy)
	{
		$branchWiseLogin=$this->session->userdata('branchWiseLogin');
			$id_branch=$this->session->userdata('id_branch');
			$uid=$this->session->userdata('uid');
	  $result=0;	
	   $sql="Select
				COUNT(CASE WHEN IFNULL(cp.paid_installment,0) >0 THEN 1 END) as paid,
				COUNT(CASE WHEN IFNULL(cp.paid_installment,0) =0 THEN 1 END) as unpaid,
				COUNT(CASE WHEN IFNULL(pp.previous_paid,0) =1 THEN 1 END) as previous_paid
			From scheme_account sa
				Left Join 
				      (Select
							  sa.id_scheme_account,COUNT(Date_Format(sa.last_paid_date,'%Y%m')) as chances,
							 IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),1,0) as  previous_paid
							 From scheme_account sa
              Group By sa.id_scheme_account) pp On(sa.id_scheme_account=pp.id_scheme_account)
							Left Join
							      (Select
										  sa.id_scheme_account,
										  COUNT(Distinct Date_Format(p.date_payment,'%Y%m')) as paid_installment,
										  COUNT(Date_Format(p.date_payment,'%Y%m')) as chances,
										  SUM(p.payment_amount) as total_amount,
										  SUM(p.metal_weight) as total_weight
										From payment p
										Left Join scheme_account sa on(p.id_scheme_account=sa.id_scheme_account and sa.active=1 and sa.is_closed=0 )
										left join branch b on (sa.id_branch=b.id_branch)
											Where p.payment_status =1 ".($uid!=1 ? ($branchWiseLogin==1 ? ($id_branch!='' ? " and b.id_branch=".$id_branch. " or b.show_to_all=1":''):''):'')." ";		
		switch($filterBy){
			case 'Y': //yesterday
			         $sql=$sql." And Date(`date_payment`) = CURDATE() - INTERVAL 1 DAY ";   
				break;
			case 'T': //Today
			         $sql=$sql." And Date(`date_payment`) = CURDATE() ";   
				break;
			case 'LW': //Last Week
			          $sql=$sql." And Date(`date_payment`) BETWEEN (CURDATE() � INTERVAL DAYOFWEEK(CURDATE())+6 DAY) AND (CURDATE() � INTERVAL DAYOFWEEK(CURDATE())-1 DAY) ";   
				break;
			case 'TW': //This Week
			          $sql=$sql." And Date(`date_payment`) BETWEEN DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY) AND DATE_ADD(CURDATE(), INTERVAL 7-DAYOFWEEK(CURDATE()) DAY) ";   
				break;	
			case 'TM': //This Month
			          $sql=$sql." And Date(`date_payment`) BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) AND LAST_DAY(NOW()) ";   
				break;
			case 'ALL':
			         $sql=$sql."";
				break;
		}	
		$sql=$sql."Group By sa.id_scheme_account) cp On(sa.id_scheme_account=cp.id_scheme_account)
		  Where sa.active=1 and sa.is_closed=0";
		  // print_r($sql);exit;
		return $this->db->query($sql)->row_array();		
	}	
	function pymt_status($filterBy)
	{
		$branchWiseLogin=$this->session->userdata('branchWiseLogin');
			$id_branch=$this->session->userdata('id_branch');
			$uid=$this->session->userdata('uid');
			$dashboard_branch=$this->session->userdata('dashboard_branch');
	  $result=0;	
	   $sql="Select IFNULL(sum(payment_amount),0) as paid From payment p left join scheme_account s on (s.id_scheme_account=p.id_scheme_account) LEFT join branch b on (b.id_branch=s.id_branch) Where p.payment_status =1 ".($dashboard_branch!=0 ? "and s.id_branch=".$dashboard_branch :'')." ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( s.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')." ";	
		switch($filterBy){
			case 'Y': //yesterday
			         $sql=$sql." And Date(`date_payment`) = CURDATE() - INTERVAL 1 DAY ";   
				break;
			case 'T': //Today
			         $sql=$sql." And Date(`date_payment`) = CURDATE()  ";   
				break;
			case 'TW': //This Week
			          $sql=$sql." And Date(`date_payment`) BETWEEN DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY) AND DATE_ADD(CURDATE(), INTERVAL 7-DAYOFWEEK(CURDATE()) DAY) ";   
				break;	
			case 'TM': //This Month
				          $sql=$sql." And Date(`date_payment`) BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) AND LAST_DAY(NOW()) ";   
				break;
			case 'ALL':
			         $sql=$sql."";
				break;
		}	
		return $this->db->query($sql)->row_array();		
	}	
function awaiting_pymt()
	{
		$branchWiseLogin=$this->session->userdata('branchWiseLogin');
			$id_branch=$this->session->userdata('id_branch');
			$uid=$this->session->userdata('uid');
			$dashboard_branch=$this->session->userdata('dashboard_branch');
	  $result=0;	
	   $sql="Select COUNT(payment_status) as awtng_count 
	   From payment p
	   left join scheme_account sa on (sa.id_scheme_account=p.id_scheme_account) 
	   left join branch b on (b.id_branch=p.id_branch)
	   Where p.payment_status =2 	
	   ".($dashboard_branch!=0 ? "and p.id_branch=".$dashboard_branch :'')." ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( p.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')."";	
		return $this->db->query($sql)->row_array();		
	}	
	function pay_detail_stat($filterBy)
	{
		$branchWiseLogin=$this->session->userdata('branchWiseLogin');
			$id_branch=$this->session->userdata('id_branch');
			$uid=$this->session->userdata('uid');
			$dashboard_branch=$this->session->userdata('dashboard_branch');
	  $result=0;	
	   $sql="SELECT
		  p.id_payment,
		  sa.account_name,if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,c.mobile,if(p.payment_ref_number = '','-',p.payment_ref_number) as ref_no,s.code, IFNULL(sa.group_code,'')as group_code,IFNULL(sa.scheme_acc_number,'Not Allocated')as scheme_acc_number,s.code,cs.has_lucky_draw,
			if(s.scheme_type=0,'Amount',if(s.scheme_type=1,'Weight',if(s.scheme_type=3 && s.flexible_sch_type=1,'Flx Amount',if(s.scheme_type=3 && s.flexible_sch_type=2,'Flx AmtToWgt[Amt]',if(s.scheme_type=3 && s.flexible_sch_type=3,'Flx AmtToWgt[Wgt]',if(s.scheme_type=3 && s.flexible_sch_type=4,'Flx Wgt [Wgt]','Amount To Weight'))))))as scheme_type,
		  p.payment_amount,p.date_payment,p.payment_mode,p.bank_acc_no,bank_name,
		  p.bank_IFSC,p.id_transaction, psm.payment_status,psm.color,p.remark
		 FROM ".self::PAY_TABLE." p
		 left join ".self::ACC_TABLE." sa on(p.id_scheme_account=sa.id_scheme_account)
		 Left Join ".self::CUS_TABLE." c on (sa.id_customer=c.id_customer)
		 left join ".self::SCH_TABLE." s on(sa.id_scheme=s.id_scheme) 
		 left join ".self::BRANCH." b on(b.id_branch=sa.id_branch) 
		 join chit_settings cs 
		 Left Join payment_status_message psm On (p.payment_status=psm.id_status_msg)";	
		switch($filterBy){
			case 'Y': //yesterday
			         $sql=$sql." WHERE Date(`date_payment`) = CURDATE() - INTERVAL 1 DAY AND p.payment_status =1  ".($dashboard_branch!=0 ? "and p.id_branch=".$dashboard_branch :'')." ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( p.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')."";   
				break;
			case 'T': //Today
			         $sql=$sql." WHERE Date(`date_payment`) = CURDATE() AND p.payment_status =1 ".($dashboard_branch!=0 ? "and p.id_branch=".$dashboard_branch :'')." ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( p.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')." ";   
				break;
			case 'LW': //Last Week
			          $sql=$sql." WHERE Date(`date_payment`) BETWEEN (CURDATE() � INTERVAL DAYOFWEEK(CURDATE())+6 DAY) AND (CURDATE() � INTERVAL DAYOFWEEK(CURDATE())-1 DAY) AND p.payment_status =1 ".($dashboard_branch!=0 ? "and p.id_branch=".$dashboard_branch :'')." ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( sa.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')."";   
				break;
			case 'TW': //This Week
			          $sql=$sql." WHERE Date(`date_payment`) BETWEEN DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY) AND DATE_ADD(CURDATE(), INTERVAL 7-DAYOFWEEK(CURDATE()) DAY) AND p.payment_status =1 ".($dashboard_branch!=0 ? "and p.id_branch=".$dashboard_branch :'')." ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( p.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')."";   
				break;	
			case 'TM': //This Month
			          $sql=$sql." WHERE Date(`date_payment`) BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) AND LAST_DAY(NOW()) AND p.payment_status =1  ".($dashboard_branch!=0 ? "and sa.id_branch=".$dashboard_branch :'')."".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( p.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')."";   
				break;
			case 'ALL':
			         $sql=$sql."WHERE p.payment_status =1 ".($dashboard_branch!=0 ? "and p.id_branch=".$dashboard_branch :'')." ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( sa.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')."";
				break;
			case 'MOBILE': //mobile payment
					$sql=$sql."WHERE p.payment_status =1 and p.added_by=2 ".($dashboard_branch!=0 ? "and p.id_branch=".$dashboard_branch :'')." ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( sa.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')."";
				break;
			case 'WEB': //web payment
					$sql=$sql."WHERE p.payment_status =1 and p.added_by=1 ".($dashboard_branch!=0 ? "and p.id_branch=".$dashboard_branch :'')." ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( sa.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')."";
				break;
			case 'ADMIN': //admin payment
					$sql=$sql."WHERE p.payment_status =1 and p.added_by=0 ".($dashboard_branch!=0 ? "and p.id_branch=".$dashboard_branch :'')." ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( sa.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')."";
				break;
		}	
		$r=$this->db->query($sql);
		return $r->result_array();
	}
	function await_detail_stat()
	{
		$branchWiseLogin=$this->session->userdata('branchWiseLogin');
			$id_branch=$this->session->userdata('id_branch');
			$uid=$this->session->userdata('uid');
			$dashboard_branch=$this->session->userdata('dashboard_branch');
	  $result=0;	
	   $sql="SELECT
		  p.id_payment,
		  sa.account_name,if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,c.mobile,if(p.payment_ref_number = '','-',p.payment_ref_number) as ref_no,s.code, if(sa.scheme_acc_number  is not null,sa.scheme_acc_number,'Not Allocated') as scheme_acc_number,if(s.scheme_type=0,'Amount',if(s.scheme_type=1,'Weight',if(s.scheme_type=3,'FLEXIBLE_AMOUNT','Amount To Weight')))as scheme_type,cs.has_lucky_draw,s.code, IFNULL(sa.group_code,'')as group_code,
		  p.payment_amount,p.date_payment,p.payment_mode,p.bank_acc_no,bank_name,
		  p.bank_IFSC,p.id_transaction, psm.payment_status,psm.color,p.remark
		 FROM ".self::PAY_TABLE." p
		 left join ".self::ACC_TABLE." sa on(p.id_scheme_account=sa.id_scheme_account)
		 Left Join ".self::CUS_TABLE." c on (sa.id_customer=c.id_customer)
		 left join ".self::SCH_TABLE." s on(sa.id_scheme=s.id_scheme) 
		 join chit_settings cs
		 Left Join payment_status_message psm On (p.payment_status=psm.id_status_msg) WHERE p.payment_status =2
	".($dashboard_branch!=0 ? "and p.id_branch=".$dashboard_branch :'')." ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( p.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')."";		
		$r=$this->db->query($sql);
		return $r->result_array();
	}
	function paid_unpaid_records($filterBy,$id_scheme="")
	{
		$sql="Select
				  COUNT(CASE WHEN IFNULL(cp.paid_installment,0) >0 THEN 1 END) as paid,
				  COUNT(CASE WHEN IFNULL(cp.paid_installment,0) =0 THEN 1 END) as unpaid
			  From scheme_account sa
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
			Where sa.active=1 and sa.is_closed=0";
	    switch($filterBy)
        {
			case 'tp': //total paid
			         $sql=$sql." WHERE sa.active=1 and c.active = 1 
					            GROUP BY sa.id_scheme_account 
					            HAVING paid_status='paid' ";   
				break;
			case 'td': //total dues
			         $sql=$sql." WHERE sa.active=1 and c.active = 1 
								 GROUP BY sa.id_scheme_account 
					             HAVING paid_status='unpaid' ";   
				break;	
			case 'sp': //scheme-wise paid
			         $sql=$sql." WHERE sa.active=1 and c.active = 1 AND s.id_scheme = '$id_scheme' 
								 GROUP BY sa.id_scheme_account
								 HAVING paid_status='paid' ";   
				break;	
			case 'sd': //scheme-wise dues
			         $sql=$sql." WHERE sa.active=1 and c.active = 1 AND s.id_scheme = '$id_scheme' 
								 GROUP BY sa.id_scheme_account
								 HAVING paid_status='unpaid' ";   
				break;
		}		
		 $sql=$sql."ORDER BY sa.id_scheme_account";	
		 $r=$this->db->query($sql);
		return $r->result_array();		
	}
		function paid_unpaid_detail($filterBy,$id_scheme="")
	{
		$sql="SELECT
			  sa.id_scheme_account,
			  scheme_acc_number as account_no,
			  if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,
			  c.mobile,s.code,
			  if(s.scheme_type=1,'Weight','Amount') as scheme_type,
			  IFNULL(Date_Format(MAX(DISTINCT  p.date_payment),'%d-%m-%Y'),IFNULL(Date_Format(sa.last_paid_date,'%d-%m-%Y'),'-')) AS last_paid_date,
			  IFNULL(MONTHNAME(IFNULL(MAX(DISTINCT  p.date_payment),sa.last_paid_date)),'-') AS last_paid_month,
			  IF(IFNULL(DATE_FORMAT(IFNULL(MAX(DISTINCT  p.date_payment),sa.last_paid_date),'%Y%m'),0) < DATE_FORMAT(NOW(),'%Y%m'),'Unpaid','Paid') AS paid_status
			FROM scheme_account sa
			LEFT JOIN payment p ON (sa.id_scheme_account = p.id_scheme_account AND p.payment_status = 1)
			LEFT JOIN customer c ON (sa.id_customer = c.id_customer)
			LEFT JOIN scheme s ON (sa.id_scheme = s.id_scheme)";
	    switch($filterBy)
        {
			case 'tp': //total paid
			         $sql=$sql." WHERE sa.active=1 and c.active = 1 
					            GROUP BY sa.id_scheme_account 
					            HAVING paid_status='paid' ";   
				break;
			case 'td': //total dues
			         $sql=$sql." WHERE sa.active=1 and c.active = 1 
								 GROUP BY sa.id_scheme_account 
					             HAVING paid_status='unpaid' ";   
				break;	
			case 'sp': //scheme-wise paid
			         $sql=$sql." WHERE sa.active=1 and c.active = 1 AND s.id_scheme = '$id_scheme' 
								 GROUP BY sa.id_scheme_account
								 HAVING paid_status='paid' ";   
				break;	
			case 'sd': //scheme-wise dues
			         $sql=$sql." WHERE sa.active=1 and c.active = 1 AND s.id_scheme = '$id_scheme' 
								 GROUP BY sa.id_scheme_account
								 HAVING paid_status='unpaid' ";   
				break;
		}		
		 $sql=$sql."ORDER BY sa.id_scheme_account";	
		 $r=$this->db->query($sql);
		return $r->result_array();		
	}
	function total_payments()
	{
		 $sql="select count(t.id_scheme_account) as total
				from scheme s
				left join
				(select
				  sa.id_scheme_account,
				  sa.id_scheme,
				  if( if(max(distinct month(p.date_payment)) is null,month(sa.last_paid_date),max(distinct month(p.date_payment))) < month(now()),'Not Paid','Paid') as current_due
				from scheme_account sa
				Left Join payment p on (sa.id_scheme_account=p.id_scheme_account)
				where sa.active=1
				group by sa.id_scheme_account
				having current_due='Not Paid') t on (s.id_scheme=t.id_scheme)";
	  $payments=$this->db->query($sql);
	  return $payments->row()->total;
	}
	function schemewise_payment()
	{
		 $sql="SELECT
				  sch.id_scheme,
				  sch.scheme_name,
				  sch.code,
				  sch.scheme_type,
				  COUNT(CASE WHEN w.paid_status='Paid' THEN 1 END) as paid,
				  COUNT(CASE WHEN w.paid_status='Unpaid' THEN 1 END) as unpaid
			   FROM scheme sch
			   LEFT JOIN
					(SELECT
					  sa.id_scheme_account,
					  sa.id_scheme,
					  IFNULL(MAX(DISTINCT  p.date_payment),sa.last_paid_date) AS last_paid_date,
					  IFNULL(MONTH(IFNULL(MAX(DISTINCT  p.date_payment),sa.last_paid_date)),0) AS last_paid_month,
					  IF(IFNULL(MONTH(IFNULL(MAX(DISTINCT  p.date_payment),sa.last_paid_date)),0) < MONTH(NOW()),'Unpaid','Paid') AS paid_status
					FROM scheme_account sa
					LEFT JOIN payment p ON (sa.id_scheme_account = p.id_scheme_account AND p.payment_status =1)
					WHERE sa.active=1
					GROUP BY sa.id_scheme_account) w ON (sch.id_scheme = w.id_scheme AND sch.active=1)
			   WHERE sch.active = 1
			   GROUP BY sch.id_scheme
			   ORDER BY sch.scheme_type,sch.id_scheme";
	  $payments=$this->db->query($sql);
	  return $payments->result_array();
	}
	function total_paid_unpaid()
	{
		 $sql="Select
				  COUNT(CASE WHEN IFNULL(cp.paid_installment,0) >0 THEN 1 END) as paid,
				  COUNT(CASE WHEN IFNULL(cp.paid_installment,0) =0 THEN 1 END) as unpaid
				From scheme_account sa
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
			Where sa.active=1 and sa.is_closed=0";
	  $payments=$this->db->query($sql);
	  return $payments->row_array();
	}
// accounts_schemewise Report chked&updtd emp login branchwise  data show//HH	
	function schemewise_accounts($id_branch="")
	{
			$branchWiseLogin=$this->session->userdata('branchWiseLogin');
			$log_branch=$this->session->userdata('id_branch');
			$uid=$this->session->userdata('uid');
		$sql="Select
			  s.id_scheme,s.is_lucky_draw as is_lucky_draw,cs.has_lucky_draw as has_lucky_draw,
			  s.scheme_name,sg.group_code,
			  s.code,
			  Count(Case When sa.active=1 Then 1 End) as accounts,
              Count(Case When sa.active=0 Then 1 End) as inactive
			From scheme s
			Left Join scheme_account sa on (s.id_scheme=sa.id_scheme)
			Left Join scheme_group sg On (sa.group_code = sg.group_code )
			  Left Join branch b On (b.id_branch=sa.id_branch)
			  join chit_settings cs
			Where s.active=1 ".($id_branch!='' && $id_branch!=0 ? " and sa .id_branch=".$id_branch:'')." ".($uid!=1 ? ($branchWiseLogin==1 ? ($log_branch!='' ? " and (sa.id_branch=".$log_branch.")":''):''):'')."  
			Group By s.id_scheme,s.scheme_type";
		$accounts=$this->db->query($sql);
		return $accounts->result_array();
	}
	function postdated_payments()
	{
		$sql="Select pay_mode,psm.payment_status,Count(id_post_payment) as payments
From postdate_payment p
Left Join payment_status_message psm On (p.payment_status=psm.id_status_msg)
Group By psm.payment_status";
		$accounts=$this->db->query($sql);
		return $accounts->result_array();
	}	
	function current_postdated_payments()
	{
		$sql="Select pay_mode,psm.payment_status,Count(id_post_payment) as payments
From postdate_payment p
Left Join payment_status_message psm On (p.payment_status=psm.id_status_msg)
Where Date_Format(date_payment,'%Y%m') =  Date_Format(now(),'%Y%m')
Group By psm.payment_status";
		$accounts=$this->db->query($sql);
		return $accounts->result_array();
	}
	function pdc_report($filterBy,$mode,$payment_status)
	{
	   $sql="Select pay_mode,psm.payment_status,Count(id_post_payment) as payments
			From postdate_payment p
			Left Join payment_status_message psm on (p.payment_status=psm.id_status_msg)
			Where pay_mode='".$mode."' and p.payment_status=".$payment_status;	
		switch($filterBy){
			case 'Y': //yesterday
			         $sql=$sql." And Date(`date_payment`) = CURDATE() - INTERVAL 1 DAY ";   
				break;
			case 'T': //Today
			         $sql=$sql." And Date(`date_payment`) = CURDATE() ";   
				break;
			case 'LW': //Last Week
			          $sql=$sql." And Date(`date_payment`) BETWEEN (CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+6 DAY) AND (CURDATE() - INTERVAL DAYOFWEEK(CURDATE()) - 1 DAY) ";   
				break;
			case 'TW': //This Week
			          $sql=$sql." And Date(`date_payment`) BETWEEN DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY) AND DATE_ADD(CURDATE(), INTERVAL 7-DAYOFWEEK(CURDATE()) DAY) ";   
				break;	
			case 'TM': //This Month
			          $sql=$sql." And Date(`date_payment`) BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) AND LAST_DAY(NOW()) ";   
				break;
			case 'TT': //Till today
			          $sql=$sql." And Date(`date_payment`) <= CURDATE() ";     
			    break;
			case 'ALL':
			         $sql="Select pay_mode,psm.payment_status,Count(id_post_payment) as payments
			From postdate_payment p
			Left Join payment_status_message psm on (p.payment_status=psm.id_status_msg)";
				break;
		}	
	return $this->db->query($sql)->row_array();
	}	
	function pdc_report_detail($filterBy,$mode,$status)
	{
	  $sql="SELECT
			      pp.id_post_payment,
			      sa.scheme_acc_number,
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
			          $sql=$sql." And (Date(pp.`date_payment`) BETWEEN ((CURDATE() - INTERVAL DAYOFWEEK(CURDATE()) + 6 DAY) AND (CURDATE() - INTERVAL DAYOFWEEK(CURDATE()) - 1 DAY)))";   
				break;
			case 'TW': //This Week
			          $sql=$sql." And (Date(`date_payment`) BETWEEN DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY) AND DATE_ADD(CURDATE(), INTERVAL 7 - DAYOFWEEK(CURDATE()) DAY)";   
				break;	
			case 'TM': //This Month
			          $sql=$sql." And (Date(`date_payment`) BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) AND LAST_DAY(NOW())";   
				break;
			case 'ALL':
			         $sql=$sql." ";
				break;
		}	
		 $sql=$sql." Group By pp.pay_mode Order By pp.date_payment ASC ";
		 $r=$this->db->query($sql);
		return $r->result_array();		
	}	
	function rateWeekStat()
	{
		$sql= " SELECT updatetime, goldrate_22ct as rate
				FROM   metal_rates
				ORDER BY id_metalrates Desc
				LIMIT 7	";
			//WHERE  YEARWEEK(`updatetime`, 1) = YEARWEEK(CURDATE(), 1) 	
		return $this->db->query($sql)->result_array();		
	}
	function total_wallets()
	{
		$sql="Select count(id_wallet_account) as wallet
				From wallet_account
				Where active=1";
		return $this->db->query($sql)->row('wallet');			
	}
	function total_closed_accounts()
	{
		$branchWiseLogin=$this->session->userdata('branchWiseLogin');
			$id_branch=$this->session->userdata('id_branch');
			$uid=$this->session->userdata('uid');
		$sql="Select count(id_scheme_account) as closed_acc
				From scheme_account sa
				left join branch b on (b.id_branch=sa.id_branch)
				Where sa.active=0 and sa.is_closed=1 ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( sa.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')."";
		return $this->db->query($sql)->row('closed_acc');			
	}
	function total_abt_to_cls($ins_type)
	{
		//old paid installments commented and replaced by Durga 08.02.2023
		/* IF(s.is_opening=1,IFNULL(s.paid_installments,0)+
		 COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')),
		 if(sc.scheme_type = 1 and sc.min_weight != sc.max_weight, 
		 COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')), sum(pay.no_of_dues))) 
		as paid_installments,*/
		$sql="Select s.paid_installments,s.is_opening,
		IF(s.is_opening=1,IFNULL(s.paid_installments,0)+ COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')),
		COUNT(Distinct Date_Format(pay.date_payment,'%Y%m'))) as paid_installments,
 			sc.total_installments
			from
			  ".self::ACC_TABLE." s
			LEFT JOIN ".self::PAY_TABLE." pay on (s.id_scheme_account = pay.id_scheme_account)
			left join ".self::SCH_TABLE." sc on (sc.id_scheme=s.id_scheme) 		
			Where s.active=1 and s.is_closed=0 
				GROUP BY s.id_scheme_account
			".($ins_type == '1'?' HAVING paid_installments= sc.total_installments-1':' HAVING paid_installments= sc.total_installments-2')."";
			// echo $sql;exit;
		return $this->db->query($sql)->num_rows();	
	}
	function closed_acc_detail_stat($filterBy)
	{
	  $result=0;	
	   $sql="select
			  s.id_scheme_account,concat (c.firstname,' ',if(c.lastname!=Null,c.lastname,'')) as name,s.ref_no,s.account_name,s.start_date,IFNULL(s.scheme_acc_number,'Not Allocated')as scheme_acc_number,cs.has_lucky_draw,IFNULL(s.group_code,'')as group_code,
			  sc.scheme_name,sc.code,if(sc.scheme_type=0,'Amount','Weight')as scheme_type,sc.total_installments,s.closing_date,s.closing_balance,sc.max_weight,sc.max_chance,sc.amount,c.mobile,s.date_add,IF(s.is_opening=1,IFNULL(s.paid_installments,0)+ COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')),COUNT(Distinct Date_Format(pay.date_payment,'%Y%m'))) as paid_installments,sc.total_installments
			from
			  ".self::ACC_TABLE." s
			LEFT JOIN ".self::PAY_TABLE." pay on (s.id_scheme_account = pay.id_scheme_account)
			left join ".self::CUS_TABLE." c on (s.id_customer=c.id_customer)
			join chit_settings cs
			left join ".self::SCH_TABLE." sc on (sc.id_scheme=s.id_scheme) ";	
		switch($filterBy){
			case 'ALL':
			         $sql=$sql." where s.active=0 and s.is_closed=1";
				break;
			case 'ONEPENDING':
			         $sql=$sql." where s.active=1 and s.is_closed=0 GROUP BY s.id_scheme_account HAVING paid_installments=(sc.total_installments - 1) ";
				break;
			case 'TWOPENDING':
				         $sql=$sql." where s.active=1 and s.is_closed=0 GROUP BY s.id_scheme_account HAVING paid_installments=(sc.total_installments - 2) ";
					break;
		}	
		$r=$this->db->query($sql);
		return $r->result_array();
	}
	function renewal_stat($filterBy)
	{
	  $result=0;	
	   $sql="SELECT cls_sch.closed_on,s.id_scheme_account, s.id_scheme, s.id_customer, s.scheme_acc_number,concat(c.firstname,' ',if(c.lastname!=Null,c.lastname,'')) as name,sc.code,sc.amount,sc.max_weight,sc.total_installments,c.mobile,
	   				s.account_name,if(sc.scheme_type=0,'Amount','Weight')as scheme_type,s.date_add, s.date_upd, s.is_closed, s.active, s.start_date
 			FROM ".self::ACC_TABLE." s
			LEFT JOIN (SELECT s.id_customer,s.closing_date as closed_on from ".self::ACC_TABLE." s where s.active=0 GROUP BY s.id_customer) cls_sch on (cls_sch.id_customer=s.id_customer)
			left join ".self::CUS_TABLE." c on (s.id_customer=c.id_customer)
			left join ".self::SCH_TABLE." sc on (sc.id_scheme=s.id_scheme)
			WHERE s.active=1 AND s.is_closed=0 AND s.start_date>=cls_sch.closed_on
 			GROUP BY s.id_scheme_account";	
 				$r=$this->db->query($sql);
		switch($filterBy)
		{
			case 'RENEWALS':
			        return $r->num_rows();
				break;
			case 'ALL':
			         return $r->result_array();
				break;
		}
	}
	function closed_acc_stat()
	{
	   $sql="select
			  s.id_scheme_account,s.scheme_acc_number,concat (c.firstname,' ',if(c.lastname!=Null,c.lastname,'')) as name,s.ref_no,s.account_name,s.start_date,
			  if(sc.scheme_type=0,'Amount',if(sc.scheme_type=1,'Weight',if(sc.scheme_type=3 && sc.flexible_sch_type=1,'Flx Amount',if(sc.scheme_type=3 && sc.flexible_sch_type=2,'Flx AmtToWgt[Amt]',if(sc.scheme_type=3 && sc.flexible_sch_type=3,'Flx AmtToWgt[Wgt]',if(sc.scheme_type=3 && sc.flexible_sch_type=4,'Flx Wgt [Wgt]','Amount To Weight'))))))as scheme_type,
			  sc.total_installments,s.closing_date,s.closing_balance,sc.max_weight,sc.max_chance,sc.amount,c.mobile,s.date_add
			from
			  ".self::ACC_TABLE." s
			left join ".self::CUS_TABLE." c on (s.id_customer=c.id_customer)
			left join ".self::SCH_TABLE." sc on (sc.id_scheme=s.id_scheme) 	
		 where s.active=0 and s.is_closed=1";
		$r=$this->db->query($sql);
		return $r->result_array();
	}
	function get_enquiry() 
	{
		$sql= "select count(id_enquiry) as id_enquiry from cust_enquiry";
		$count = $this->db->query($sql)->row('id_enquiry');
		return $count;
	}
	function inter_wallet($filterBy)
	{
		$branchWiseLogin=$this->session->userdata('branchWiseLogin');
			$id_branch=$this->session->userdata('id_branch');
			$uid=$this->session->userdata('uid');
			$dashboard_branch=$this->session->userdata('dashboard_branch');
	  $result=0;	
	   $sql="Select round(if(cs.wallet_balance_type=1,((IFNULL(sum(trans_points),0)/cs.wallet_points) * wallet_amt_per_points) ,  IFNULL(sum(trans_points),0))) as total 
	  		from inter_wallet_trans_detail iwd
	  		LEFT join inter_wallet_trans iwt on (iwd.id_inter_wallet_trans=iwt.id_inter_wallet_trans)
	   		LEFT join branch b on (b.id_branch=iwt.id_branch)
	   		join chit_settings cs ";
	    $sql_R="Select round(if(cs.wallet_balance_type=1,((IFNULL(sum(actual_redeemed),0)/cs.wallet_points) * wallet_amt_per_points) ,  IFNULL(sum(actual_redeemed),0)))   as total 
	  		from inter_wallet_trans iwt
	   		LEFT join branch b on (b.id_branch=iwt.id_branch)
	   		join chit_settings cs ";
		switch($filterBy){
			case 'Y': //yesterday
			         $sql=$sql." WHERE Date(iwt.date_add) = CURDATE() - INTERVAL 1 DAY  and iwt.trans_type=1 ".($dashboard_branch!=0 ? "and iwt.id_branch=".$dashboard_branch :'')." ".($uid!=1 ?($branchWiseLogin==1 ? ($id_branch!='' ? " and iwt.id_branch=".$id_branch:''):''):'')." ";   
				break;
			case 'T': //Today
			         $sql=$sql." WHERE Date(iwt.date_add) = CURDATE() and iwt.trans_type=1 ".($dashboard_branch!=0 ? "and iwt.id_branch=".$dashboard_branch :'')." ".($uid!=1 ?($branchWiseLogin==1 ? ($id_branch!='' ? " and iwt.id_branch=".$id_branch:''):''):'')." ";   
				break;
			case 'LW': //Last Week
			          $sql=$sql." WHERE Date(iwt.date_add) BETWEEN (CURDATE() � INTERVAL DAYOFWEEK(CURDATE())+6 DAY) AND (CURDATE() � INTERVAL DAYOFWEEK(CURDATE())-1 DAY) and iwt.trans_type=1 ".($dashboard_branch!=0 ? "and iwt.id_branch=".$dashboard_branch :'')." ".($uid!=1 ?($branchWiseLogin==1 ? ($id_branch!='' ? " and iwt.id_branch=".$id_branch:''):''):'')." ";   
				break;
			case 'TW': //This Week
			          $sql=$sql." WHERE Date(iwt.date_add) BETWEEN DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY) AND DATE_ADD(CURDATE(), INTERVAL 7-DAYOFWEEK(CURDATE()) DAY) and iwt.trans_type=1 ".($dashboard_branch!=0 ? "and iwt.id_branch=".$dashboard_branch :'')."  ".($uid!=1 ?($branchWiseLogin==1 ? ($id_branch!='' ? " and iwt.id_branch=".$id_branch:''):''):'')."";   
				break;	
			case 'TM': //This Month
			          $sql=$sql." WHERE Date(iwt.date_add) BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) AND LAST_DAY(NOW()) and iwt.trans_type=1  ".($dashboard_branch!=0 ? "and iwt.id_branch=".$dashboard_branch :'')." ".($uid!=1 ?($branchWiseLogin==1 ? ($id_branch!='' ? " and iwt.id_branch=".$id_branch:''):''):'')."";   
				break;
				case 'YR': //yesterday
			         $sql=$sql_R." WHERE Date(iwt.date_add) = CURDATE() - INTERVAL 1 DAY  and actual_redeemed >0   ".($dashboard_branch!=0 ? "and iwt.id_branch=".$dashboard_branch :'')."".($uid!=1 ?($branchWiseLogin==1 ? ($id_branch!='' ? " and iwt.id_branch=".$id_branch:''):''):'')."";   
				break;
			case 'TR': //Today
			         $sql=$sql_R." WHERE Date(iwt.date_add) = CURDATE() and iwt.actual_redeemed > 0 ".($dashboard_branch!=0 ? "and iwt.id_branch=".$dashboard_branch :'')." ".($uid!=1 ?($branchWiseLogin==1 ? ($id_branch!='' ? " and iwt.id_branch=".$id_branch:''):''):'')." ";   
				break;
			case 'LWR': //Last Week
			          $sql=$sql_R." WHERE Date(iwt.date_add) BETWEEN (CURDATE() � INTERVAL DAYOFWEEK(CURDATE())+6 DAY) AND (CURDATE() � INTERVAL DAYOFWEEK(CURDATE())-1 DAY) and actual_redeemed >0 ".($dashboard_branch!=0 ? "and iwt.id_branch=".$dashboard_branch :'')." ".($uid!=1 ?($branchWiseLogin==1 ? ($id_branch!='' ? " and iwt.id_branch=".$id_branch:''):''):'')." ";   
				break;
			case 'TWR': //This Week
			          $sql=$sql_R." WHERE Date(iwt.date_add) BETWEEN DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY) AND DATE_ADD(CURDATE(), INTERVAL 7-DAYOFWEEK(CURDATE()) DAY) and actual_redeemed >0  ".($dashboard_branch!=0 ? "and iwt.id_branch=".$dashboard_branch :'')." ".($uid!=1 ?($branchWiseLogin==1 ? ($id_branch!='' ? " and iwt.id_branch=".$id_branch:''):''):'')."";   
				break;	
			case 'TMR': //This Month
			          $sql=$sql_R." WHERE Date(iwt.date_add) BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) AND LAST_DAY(NOW()) and actual_redeemed >0 ".($dashboard_branch!=0 ? "and iwt.id_branch=".$dashboard_branch :'')." ".($uid!=1 ?($branchWiseLogin==1 ? ($id_branch!='' ? " and iwt.id_branch=".$id_branch:''):''):'')."";   
				break;
			case 'ALL':
			         $sql=$sql;
				break;	
		}	
		$r=$this->db->query($sql);
		if($r->num_rows()>0)
		{
			$result= $r->row('total');
		}
		return $result;
	}
	function inter_wallet_detail_stat($type)
	{
		$branchWiseLogin=$this->session->userdata('branchWiseLogin');
			$id_branch=$this->session->userdata('id_branch');
			$uid=$this->session->userdata('uid');
			$dashboard_branch=$this->session->userdata('dashboard_branch');
	  $result=0;	
	   $sql="SELECT 
				sum(iwd.trans_points)as amount,iwd.id_inter_wallet_trans,iwt.mobile,iwt.date_add
				FROM inter_wallet_trans_detail iwd
				LEFT JOIN inter_wallet_trans iwt on
				iwd.id_inter_wallet_trans=iwt.id_inter_wallet_trans
				LEFT join branch b on b.id_branch=iwt.id_branch";	
		$sql_R = "SELECT 
				sum(iwt.actual_redeemed)as amount,iwt.id_inter_wallet_trans,iwt.mobile,iwt.date_add
				FROM inter_wallet_trans iwt			
				LEFT join branch b on b.id_branch=iwt.id_branch";	
		switch($type){
			case 'Y': //yesterday
			         $sql=$sql." WHERE date(iwt.date_add) = CURDATE() - INTERVAL 1 DAY and iwt.trans_type=1 ".($dashboard_branch!=0 ? "and iwt.id_branch=".$dashboard_branch :'')." ".($uid!=1 ?($branchWiseLogin==1 ? ($id_branch!='' ? " and iwt.id_branch=".$id_branch." or b.show_to_all=1" :''):''):'')." GROUP BY iwd.id_inter_wallet_trans";   
				break;
			case 'T': //Today
			         $sql=$sql." WHERE Date(iwt.date_add) = CURDATE()  and iwt.trans_type=1 ".($dashboard_branch!=0 ? "and iwt.id_branch=".$dashboard_branch :'')." ".($uid!=1 ?($branchWiseLogin==1 ? ($id_branch!='' ? " and iwt.id_branch=".$id_branch." or b.show_to_all=1" :''):''):'')." GROUP BY iwd.id_inter_wallet_trans ";   
				break;
			case 'LW': //Last Week
			          $sql=$sql." WHERE Date(iwt.date_add) BETWEEN (CURDATE() � INTERVAL DAYOFWEEK(CURDATE())+6 DAY) AND (CURDATE() � INTERVAL DAYOFWEEK(CURDATE())-1 DAY) and iwt.trans_type=1  ".($dashboard_branch!=0 ? "and iwt.id_branch=".$dashboard_branch :'')." ".($uid!=1 ?($branchWiseLogin==1 ? ($id_branch!='' ? " and iwt.id_branch=".$id_branch." or b.show_to_all=1" :''):''):'')." GROUP BY iwd.id_inter_wallet_trans";  
				break;
			case 'TW': //This Week
			          $sql=$sql." WHERE Date(iwt.date_add) BETWEEN DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY) AND DATE_ADD(CURDATE(), INTERVAL 7-DAYOFWEEK(CURDATE()) DAY) and iwt.trans_type=1  ".($dashboard_branch!=0 ? "and iwt.id_branch=".$dashboard_branch :'')." ".($uid!=1 ?($branchWiseLogin==1 ? ($id_branch!='' ? " and iwt.id_branch=".$id_branch." or b.show_to_all=1" :''):''):'')." GROUP BY iwd.id_inter_wallet_trans";
				break;	
			case 'TM': //This Month
			          $sql=$sql." WHERE Date(iwt.date_add) BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) AND LAST_DAY(NOW()) and iwt.trans_type=1 ".($dashboard_branch!=0 ? "and iwt.id_branch=".$dashboard_branch :'')." ".($uid!=1 ?($branchWiseLogin==1 ? ($id_branch!='' ? " and iwt.id_branch=".$id_branch." or b.show_to_all=1" :''):''):'')." GROUP BY iwd.id_inter_wallet_trans";   
				break;
				case 'YR': //yesterday
			         $sql=$sql_R." WHERE date(iwt.date_add) = CURDATE() - INTERVAL 1 DAY and  iwt.actual_redeemed >0  ".($dashboard_branch!=0 ? "and iwt.id_branch=".$dashboard_branch :'')." ".($uid!=1 ?($branchWiseLogin==1 ? ($id_branch!='' ? " and iwt.id_branch=".$id_branch." or b.show_to_all=1" :''):''):'')." GROUP BY iwt.id_inter_wallet_trans";   
				break;
			case 'TR': //Today
			         $sql=$sql_R." WHERE Date(iwt.date_add) = CURDATE()  and iwt.actual_redeemed > 0 ".($dashboard_branch!=0 ? "and iwt.id_branch=".$dashboard_branch :'')." ".($uid!=1 ?($branchWiseLogin==1 ? ($id_branch!='' ? " and iwt.id_branch=".$id_branch." or b.show_to_all=1" :''):''):'')." GROUP BY iwt.id_inter_wallet_trans ";   
				break;
			case 'LWR': //Last Week
			          $sql=$sql_R." WHERE Date(iwt.date_add) BETWEEN (CURDATE() � INTERVAL DAYOFWEEK(CURDATE())+6 DAY) AND (CURDATE() � INTERVAL DAYOFWEEK(CURDATE())-1 DAY) and  iwt.actual_redeemed >0 ".($dashboard_branch!=0 ? "and iwt.id_branch=".$dashboard_branch :'')." ".($uid!=1 ?($branchWiseLogin==1 ? ($id_branch!='' ? " and iwt.id_branch=".$id_branch." or b.show_to_all=1" :''):''):'')." GROUP BY iwt.id_inter_wallet_trans";   
				break;
			case 'TWR': //This Week
			          $sql=$sql_R." WHERE Date(iwt.date_add) BETWEEN DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY) AND DATE_ADD(CURDATE(), INTERVAL 7-DAYOFWEEK(CURDATE()) DAY) and  iwt.actual_redeemed >0 ".($dashboard_branch!=0 ? "and iwt.id_branch=".$dashboard_branch :'')."  ".($uid!=1 ?($branchWiseLogin==1 ? ($id_branch!='' ? " and iwt.id_branch=".$id_branch." or b.show_to_all=1" :''):''):'')." GROUP BY iwt.id_inter_wallet_trans";   
				break;	
			case 'TMR': //This Month
			          $sql=$sql_R." WHERE Date(iwt.date_add) BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) AND LAST_DAY(NOW()) and  iwt.actual_redeemed >0 ".($dashboard_branch!=0 ? "and iwt.id_branch=".$dashboard_branch :'')."  ".($uid!=1 ?($branchWiseLogin==1 ? ($id_branch!='' ? " and iwt.id_branch=".$id_branch." or b.show_to_all=1" :''):''):'')." GROUP BY iwt.id_inter_wallet_trans";   
				break;
		}	  
		$r=$this->db->query($sql);
		return $r->result_array();
	}
	function scheme_group()
	{
		$sql="SELECT cs.has_lucky_draw,cs.walletIntegration from chit_settings cs";
		$r=$this->db->query($sql);
		return $r->row_array();
	}
	function wallet_amt()
	{
		$sql="SELECT  cs.wallet_amt_per_points,cs.wallet_balance_type,cs.wallet_points
		       from chit_settings cs";
		$r=$this->db->query($sql);
		return $r->row_array();
	}
	function inter_wallet_accounts()
	{
		$sql="Select count(iwa.id_inter_wal_ac) as wallet 
				From  inter_wallet_account iwa
				left join customer c on iwa.id_customer=c.id_customer
				Where iwa.id_customer IS NOT NULL ";
		return $this->db->query($sql)->row('wallet');			
	}
	function inter_wallet_accounts_woc()
	{
		$sql="Select count(iwa.id_inter_wal_ac) as wallet From inter_wallet_account iwa 
				Where iwa.id_customer IS NULL ";
				return $this->db->query($sql)->row('wallet');	
	}
	function inter_wallet_accounts_detail()
		{
		$sql="SELECT iwa.id_inter_wal_ac, c.firstname,c.mobile,COUNT(sa.id_scheme_account)as accounts ,c.date_add ,iwa.available_points,c.mobile 
		FROM customer c 
		LEFT JOIN inter_wallet_account iwa on (c.id_customer=iwa.id_customer) 
		LEFT JOIN (select COUNT(sa.id_scheme_account) as accounts,sa.id_customer,sa.id_scheme_account
				FROM scheme_account sa GROUP by sa.id_scheme_account) sa on sa.id_customer=iwa.id_customer 
		where iwa.id_customer IS NOT NULL   GROUP by iwa.id_inter_wal_ac";
		$r=$this->db->query($sql);
		return $r->result_array();
		}
		function inter_wallet_woc()
		{
		$sql="SELECT iwa.id_inter_wal_ac, iwa.available_points,iwa.mobile,iwa.date_add from  inter_wallet_account iwa  where iwa.id_customer IS NULL  ";
		$r=$this->db->query($sql);
		return $r->result_array();
		}
	function payment_status($from_date,$to_date)
	{
	    $id_branch = $this->input->post('id_branch');
	   $sql=$sql="Select IFNULL(sum(payment_amount),0) as paid,p.id_branch,b.name as branch_name,b.short_name,
	   			IFNULL(COUNT(id_payment),0) as id_payment,cs.currency_symbol,cs.currency_name
	   			From payment p 
	   			left join branch b on b.id_branch=p.id_branch
	   				join chit_settings cs
	   			 where (date(p.date_payment) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') and p.payment_status=1
	   			 ".($id_branch!='' && $id_branch!=0 && $id_branch != null ? " and p.id_branch=".$id_branch:'')."
	   			GROUP by p.id_branch ";	
		return $this->db->query($sql)->result_array();		
	}
function get_payment_list($id_branch,$from_date,$to_date,$type="")
	{
	   $sql="SELECT
		  p.id_payment,
		  sa.account_name,if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,c.mobile,if(p.payment_ref_number = '','-',p.payment_ref_number) as ref_no,s.code, IFNULL(sa.group_code,'')as group_code,IFNULL(sa.scheme_acc_number,'Not Allocated')as scheme_acc_number,s.code,cs.has_lucky_draw,
			if(s.scheme_type=0,'Amount',if(s.scheme_type=1,'Weight',if(s.scheme_type=3,'FLEXIBLE_AMOUNT','Amount To Weight')))as scheme_type,
		  p.payment_amount,p.date_payment,p.payment_mode,p.bank_acc_no,bank_name,
		  p.bank_IFSC,IF(p.id_transaction='', p.id_transaction,'-') as id_transaction, psm.payment_status,psm.color,p.remark
		 FROM ".self::PAY_TABLE." p
		 left join ".self::ACC_TABLE." sa on(p.id_scheme_account=sa.id_scheme_account)
		 Left Join ".self::CUS_TABLE." c on (sa.id_customer=c.id_customer)
		 left join ".self::SCH_TABLE." s on(sa.id_scheme=s.id_scheme) 
		 left join ".self::BRANCH." b on(b.id_branch=p.id_branch) 
		 join chit_settings cs 
		 Left Join payment_status_message psm On (p.payment_status=psm.id_status_msg) 
		 where p.id_branch=".$id_branch." and p.payment_status=1 and (date(p.date_payment) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')  ".($type!='' ? " and s.scheme_type=".$type."" :'')." Group by p.id_payment";
		 $r=$this->db->query($sql);
		return $r->result_array();
	}
function payment_join_through($filterBy,$from_date,$to_date)
	{
	    $id_branch = $this->input->post('id_branch');
	  $result=0;	
	   $sql="Select COUNT(id_payment) as joined_thro,ifnull(SUM(p.payment_amount),'0') as amount,(Select cs.currency_symbol from chit_settings cs) as currency_symbol  
	   From payment p
	   		LEFT join scheme_account sa on (sa.id_scheme_account=p.id_scheme_account)
	   		LEFT join branch b on (b.id_branch=p.id_branch)
	   		";	
		switch($filterBy){
			case 'MOB': //Joined thro Mobile App
			         $sql=$sql." where p.added_by = 2 and payment_status=1  and (date(p.date_payment) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')  
			          ".($id_branch!='' && $id_branch!=0 && $id_branch != null ? " and p.id_branch=".$id_branch:'')." ";   
				break;
			case 'WEB': //Joined thro Web App
			         $sql=$sql." where p.added_by = 1 and payment_status=1 and (date(p.date_payment) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') 
			          ".($id_branch!='' && $id_branch!=0 && $id_branch != null ? " and p.id_branch=".$id_branch:'')." ";   
				break;
			case 'ADMIN': //Joined thro Admin
			          $sql=$sql." where p.added_by = 0 and payment_status=1 and (date(p.date_payment) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') 
			           ".($id_branch!='' && $id_branch!=0 && $id_branch != null ? " and p.id_branch=".$id_branch:'')." ";   
				break;
			case 'COLLECTION': //Joined thro Admin
			          $sql=$sql." where p.added_by = 3 and payment_status=1 and (date(p.date_payment) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') 
			           ".($id_branch!='' && $id_branch!=0 && $id_branch != null ? " and p.id_branch=".$id_branch:'')." ";   
				break;	
		}	
		
		
		return $this->db->query($sql)->row_array();		
	}
    function account_status($from_date,$to_date)
	{
	 $result=0;	
	 $id_branch=$this->input->post('id_branch');
	   /*$sql="Select count(id_scheme_account) as total,b.id_branch,b.name as  branch_name from ".self::ACC_TABLE." s 
	   		Left join ".self::BRANCH." b on (s.id_branch=b.id_branch) 
	   		where (date(s.date_add) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') group by s.id_branch"; */
	   		
	   		$sql="Select count(s.id_scheme_account) as total,b.id_branch,b.name as branch_name,b.short_name,ifnull(SUM(p.payment_amount),'-') as amount,cs.currency_symbol ,
	   		IF(sch.scheme_type = 1 || sch.scheme_type = 2 || sch.scheme_type = 3 && (sch.flexible_sch_type = 2 || sch.flexible_sch_type = 3 || sch.flexible_sch_type = 4 || sch.flexible_sch_type = 5),CONCAT(SUM(p.metal_weight),' g'),'-') as weight
	        from ".self::ACC_TABLE." s 
	   		Left join ".self::BRANCH." b on (b.id_branch=s.id_branch) 
	   		LEFT join payment p on (p.id_scheme_account=s.id_scheme_account)
	   		join chit_settings cs
	   		LEFT JOIN scheme sch ON (sch.id_scheme = s.id_scheme) 
	   		where s.scheme_acc_number IS NOT NULL AND p.payment_status = 1 AND (date(s.start_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')
	   		AND (date(p.date_payment) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') 
	   		".($id_branch!='' && $id_branch!=0 && $id_branch != null ? " and s.id_branch=".$id_branch:'')."
	   		group by s.id_branch";
		$r=$this->db->query($sql);
		return $r->result_array();
	}
	
	
	function acc_wo_payment($from_date,$to_date)
	{
	    $id_branch=$this->input->post('id_branch');
			 $sql="SELECT count(sa.id_scheme_account)as count,b.name
				FROM scheme_account sa
				LEFT join branch b on (sa.id_branch=b.id_branch)
        			WHERE NOT EXISTS(
						SELECT p.id_scheme_account FROM payment p
						WHERE sa.id_scheme_account = p.id_scheme_account
						)AND sa.paid_installments=0 AND sa.is_closed=0 AND (date(sa.date_add) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') 
					".($id_branch!='' && $id_branch!=0 && $id_branch != null ? " and sa.id_branch=".$id_branch:'')."
						GROUP by sa.id_branch	";
			$r = $this->db->query($sql);
			return $r->result_array();
	}
    function account_join_list($filterBy,$from_date,$to_date)
	{
	    	$id_branch=$this->input->post('id_branch');
	  $result=0;	
	   $sql="Select COUNT(sa.id_scheme_account) as joined_thro,IFNULL(sum(p.payment_amount),0) as amount,(Select cs.currency_symbol from chit_settings cs) as currency_symbol
	       From scheme_account sa
	       LEFT join payment p on (p.id_scheme_account=sa.id_scheme_account AND p.payment_status = 1)
	   		LEFT join branch b on (b.id_branch=sa.id_branch)";	
		switch($filterBy){
			case 'MOB': //Joined thro Mobile App
			         $sql=$sql." where sa.added_by = 2 and (date(sa.start_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') 
		            and (date(p.date_payment) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')
		            ".($id_branch!='' && $id_branch!=0 && $id_branch != null ? " and sa .id_branch=".$id_branch:'')." ";   
				break;
			case 'WEB': //Joined thro Web App
			         $sql=$sql." where sa.added_by = 0 and (date(sa.start_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') 
		            and (date(p.date_payment) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')
		            ".($id_branch!='' && $id_branch!=0 && $id_branch != null ? " and sa .id_branch=".$id_branch:'')." ";  
				break;
			case 'ADMIN': //Joined thro Admin
			          $sql=$sql." where sa.added_by = 1 and (date(sa.start_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') 
		            and (date(p.date_payment) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')
		           ".($id_branch!='' && $id_branch!=0 && $id_branch != null ? " and sa .id_branch=".$id_branch:'')." ";  
				break;	
			case 'COLLECTION': //Joined thro Admin
			          $sql=$sql." where sa.added_by = 3 and (date(sa.start_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') 
		            and (date(p.date_payment) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')
		            ".($id_branch!='' && $id_branch!=0 && $id_branch != null ? " and sa .id_branch=".$id_branch:'')." ";  
				break;	
		}	
		
		
	//	print_r($sql);exit;
		return $this->db->query($sql)->row_array();		
	}
    function get_account_list($id_branch,$from_date,$to_date,$type="")
	{
	    $id_employee = $_POST['id_employee'];
	  $result=0;	
	   $sql="select CONCAT(e.firstname,' ',e.lastname) as emp_name,IFNULL(e.emp_code,'-') as emp_code,
			  s.id_scheme_account,if(s.scheme_acc_number is not null,s.scheme_acc_number,'Not Allocated')as scheme_acc_number,sc.code,cs.has_lucky_draw,IFNULL(s.group_code,'')as group_code,concat (c.firstname,' ',if(c.lastname!=Null,c.lastname,'')) as name,s.ref_no,s.account_name,s.start_date,if(s.added_by=0,'Web App',if(s.added_by=1,'Admin','Mobile App'))as added_by,			  
			    if(sc.scheme_type=0,'Amount',if(sc.scheme_type=1,'Weight',if(sc.scheme_type=3,'FLEXIBLE_AMOUNT','Amount To Weight')))as scheme_type, FORMAT(if(sc.scheme_type=1,sc.max_weight,''),2) as max_weight,sc.scheme_type as sch_type,
			  sc.scheme_name,sc.code,sc.total_installments,sc.max_weight,sc.max_chance,c.mobile,s.date_add,
			CAST(if(sc.scheme_type=3 && sc.max_amount!=0,sc.max_amount,if(sc.scheme_type=3 && sc.max_amount=0,(sc.max_weight*(SELECT m.goldrate_22ct FROM metal_rates m  order by id_metalrates Desc LIMIT 1)),sc.amount)) as int) as amount,ifnull(p.payment_amount,'0') as payment_amount,cs.currency_symbol
			from
			  ".self::ACC_TABLE." s
			left join ".self::CUS_TABLE." c on (s.id_customer=c.id_customer)
			join chit_settings cs
			left join ".self::SCH_TABLE." sc on (sc.id_scheme=s.id_scheme) 
			left join ".self::BRANCH." b on (s.id_branch=b.id_branch)
			LEFT JOIN employee e ON (e.id_employee = s.id_employee)
			left join payment p on (p.id_Scheme_account=s.id_Scheme_account AND p.payment_status = 1)
			where s.scheme_acc_number IS NOT NULL AND (date(s.start_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') 
			AND (date(p.date_payment) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')
			and s.id_branch=".$id_branch."  ".($type!='' ?  " and s.added_by=".$type  :'')."
			".($id_employee!='' ?  " and s.id_employee=".$id_employee  :'')."";
		
			$r=$this->db->query($sql);
		return $r->result_array();	
	}	
	function get_account_joined($from_date,$to_date,$type)
	{
	     $id_employee = $_POST['id_employee'];
	  $result=0;	
	   $sql="select  CONCAT(e.firstname,' ',e.lastname) as emp_name,IFNULL(e.emp_code,'-') as emp_code,
			  s.id_scheme_account,if(s.scheme_acc_number is not null,s.scheme_acc_number,'Not Allocated')as scheme_acc_number,sc.code,cs.has_lucky_draw,IFNULL(s.group_code,'')as group_code,concat (c.firstname,' ',if(c.lastname!=Null,c.lastname,'')) as name,s.ref_no,s.account_name,s.start_date,if(s.added_by=0,'Web App',if(s.added_by=1,'Admin','Mobile App'))as added_by,	FORMAT(if(sc.scheme_type=1,sc.max_weight,''),2) as max_weight,sc.scheme_type as sch_type,		  
			    if(sc.scheme_type=0,'Amount',if(sc.scheme_type=1,'Weight',if(sc.scheme_type=3,'FLEXIBLE_AMOUNT','Amount To Weight')))as scheme_type,
			  sc.scheme_name,sc.code,sc.total_installments,sc.max_weight,sc.max_chance,c.mobile,s.date_add,
			CAST(if(sc.scheme_type=3 && sc.max_amount!=0,sc.max_amount,if(sc.scheme_type=3 && sc.max_amount=0,(sc.max_weight*(SELECT m.goldrate_22ct FROM metal_rates m  order by id_metalrates Desc LIMIT 1)),sc.amount)) as int) as amount,ifnull(p.payment_amount,'-') as payment_amount,cs.currency_symbol
			from
			  ".self::ACC_TABLE." s
			left join ".self::CUS_TABLE." c on (s.id_customer=c.id_customer)
			join chit_settings cs
			left join ".self::SCH_TABLE." sc on (sc.id_scheme=s.id_scheme) 
			left join ".self::BRANCH." b on (s.id_branch=b.id_branch)
			LEFT JOIN employee e ON (e.id_employee = s.id_employee)
			left join payment p on (p.id_Scheme_account=s.id_Scheme_account AND p.payment_status = 1)
			where s.added_by=".$type." and (date(s.start_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')
			and (date(p.date_payment) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')
			".($id_employee!='' ?  " and s.id_employee=".$id_employee  :'')."";
			$r=$this->db->query($sql);
		return $r->result_array();	
		}	
	function payment_join_through_list($from_date,$to_date,$type)
	{
	  $result=0;	
	     $sql="SELECT
		  p.id_payment,
		  sa.account_name,if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,c.mobile,if(p.payment_ref_number = '','-',p.payment_ref_number) as ref_no,s.code, IFNULL(sa.group_code,'')as group_code,IFNULL(sa.scheme_acc_number,'Not Allocated')as scheme_acc_number,s.code,cs.has_lucky_draw,
			if(s.scheme_type=0,'Amount',if(s.scheme_type=1,'Weight',if(s.scheme_type=3,'FLEXIBLE_AMOUNT','Amount To Weight')))as scheme_type,
		  p.payment_amount,p.date_payment,p.payment_mode,p.bank_acc_no,bank_name,
		  p.bank_IFSC,p.id_transaction, psm.payment_status,psm.color,p.remark
		 FROM ".self::PAY_TABLE." p
		 left join ".self::ACC_TABLE." sa on(p.id_scheme_account=sa.id_scheme_account)
		 Left Join ".self::CUS_TABLE." c on (sa.id_customer=c.id_customer)
		 left join ".self::SCH_TABLE." s on(sa.id_scheme=s.id_scheme) 
		 left join ".self::BRANCH." b on(b.id_branch=p.id_branch) 
		 join chit_settings cs 
		 Left Join payment_status_message psm On (p.payment_status=psm.id_status_msg) where p.added_by=".$type." and p.payment_status=1 and (date(p.date_payment) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') Group by p.id_payment";
		return $this->db->query($sql)->result_array();		
	}
		function inter_wallet_credit($from_date,$to_date)
	{
	  $result=0;	
	   $sql="Select cs.currency_symbol,b.id_branch,b.name as branch_name,round(if(cs.wallet_balance_type=1,((IFNULL(sum(trans_points),0)/cs.wallet_points) * wallet_amt_per_points) ,  IFNULL(sum(trans_points),0))) as total 
	  		from inter_wallet_trans_detail iwd
	  		LEFT join inter_wallet_trans iwt on (iwd.id_inter_wallet_trans=iwt.id_inter_wallet_trans)
	   		LEFT join branch b on (b.id_branch=iwt.id_branch)
	   		join chit_settings cs
	   		where iwt.trans_type=1 and (date(iwt.date_add) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')  group by iwt.id_branch";
		$r=$this->db->query($sql);
		return $r->result_array();
	}
	function inter_wallet_redeem($from_date,$to_date)
	{
	  $result=0;	
	   $sql="Select cs.currency_symbol,b.id_branch, b.name as branch_name,round(if(cs.wallet_balance_type=1,((IFNULL(sum(actual_redeemed),0)/cs.wallet_points) * wallet_amt_per_points) ,  IFNULL(sum(actual_redeemed),0)))   as total 
	  		from inter_wallet_trans iwt
	   		LEFT join branch b on (b.id_branch=iwt.id_branch)
	   		join chit_settings cs
			where (date(iwt.date_add) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')  group by iwt.id_branch";
		$r=$this->db->query($sql);
		return $r->result_array();
	}
	function interCreditAndDebit($from_date,$to_date)
	{
	   $result=0;	
	   $sql = "Select b.id_branch,b.name as branch_name,
            round(if(cs.wallet_balance_type=1,((IFNULL(sum(trans_points),0)/cs.wallet_points) * wallet_amt_per_points) ,  IFNULL(sum(trans_points),0))) as credit ,
            round(if(cs.wallet_balance_type=1,((IFNULL(sum(actual_redeemed),0)/cs.wallet_points) * wallet_amt_per_points) ,  IFNULL(sum(actual_redeemed),0)))   as debit 
	  		from inter_wallet_trans_detail iwd
	  		LEFT join inter_wallet_trans iwt on (iwd.id_inter_wallet_trans=iwt.id_inter_wallet_trans)
	   		LEFT join branch b on (b.id_branch=iwt.id_branch)
	   		join chit_settings cs
	   		where  (date(iwt.date_add) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')  group by iwt.id_branch";
		$r = $this->db->query($sql);
		return $r->result_array();
	}
	function allBranches(){
	    $sql="Select b.id_branch, b.name as branch_name from branch b";
		$r=$this->db->query($sql);
		return $r->result_array();
	}
	function inter_wallet_detail($id_branch,$from_date,$to_date,$type)
	{
	  $result=0;	
			if($type==1)
			{
				$sql="SELECT 
				sum(iwd.trans_points)as amount,iwd.id_inter_wallet_trans,iwt.mobile,iwt.date_add,cs.currency_symbol,
				round(if(cs.wallet_balance_type=1,((IFNULL(sum(trans_points),0)/cs.wallet_points) * wallet_amt_per_points) ,  IFNULL(sum(trans_points),0))) as equivalent_amt 
				FROM inter_wallet_trans_detail iwd
				LEFT JOIN inter_wallet_trans iwt on
				iwd.id_inter_wallet_trans=iwt.id_inter_wallet_trans
				LEFT join branch b on b.id_branch=iwt.id_branch 
				join chit_settings cs	
				where iwt.id_branch=".$id_branch." and  iwt.trans_type=".$type." and (date(iwt.date_add) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')  GROUP BY iwd.id_inter_wallet_trans";	
			}
			else
			{
				$sql="SELECT 
				(iwt.actual_redeemed)as amount,iwt.id_inter_wallet_trans,iwt.mobile,iwt.date_add,cs.currency_symbol,
				 round(if(cs.wallet_balance_type=1,((IFNULL((iwt.actual_redeemed),0)/cs.wallet_points) * wallet_amt_per_points) ,  IFNULL((actual_redeemed),0)))   as equivalent_amt
				FROM inter_wallet_trans iwt			
				LEFT join branch b on b.id_branch=iwt.id_branch 
				join chit_settings cs				
				where iwt.id_branch=".$id_branch." and iwt.actual_redeemed>0 and (date(iwt.date_add) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') ";	
			}
		$r=$this->db->query($sql);
		return $r->result_array();
	}
	function paydatewise_schemecoll($date)
	{		
		if($this->session->userdata('branch_settings')==1){
			$id_branch  = $this->input->post('id_branch');}
		else{
		    $id_branch = '';
		} 
		$sql="select cs.currency_symbol,b.name as branch_name,if(s.scheme_type=0,'Amount',if(s.scheme_type=1,'Weight',if(s.scheme_type=3,'FLEXIBLE_AMOUNT','Amount To Weight')))as scheme_type, 
                COUNT(CASE WHEN Date_format(p.date_payment,'%Y-%m-%d') ='$date' and p.payment_status=1 THEN 1 END) as paid, 
                IFNULL(sum(CASE WHEN Date_format(p.date_payment,'%Y-%m-%d') ='$date' and p.payment_status=1 THEN p.payment_amount END),0) as collection,
                IFNULL((CASE WHEN Date_format(p.date_payment,'%Y-%m-%d') ='$date' and sa.is_closed=1 THEN sa.closing_balance END),0) as closing,
                (select sum(pay.payment_amount) from payment pay
                	LEFT JOIN scheme_account sca ON (sca.id_scheme_account = pay.id_scheme_account)
                	LEFT JOIN scheme sh ON (sca.id_scheme = sh.id_scheme)
                	LEFT JOIN branch br ON (br.id_branch = pay.id_branch) 
                	where b.id_branch = br.id_branch and Date_format(pay.date_payment,'%Y-%m-%d')< '$date'
                	and pay.payment_status=1 group by b.id_branch)  as opening_bal
                from payment p
                LEFT JOIN scheme_account sa ON (sa.id_scheme_account = p.id_scheme_account)
                LEFT JOIN postdate_payment pp ON (p.id_scheme_account = pp.id_scheme_account) and pp.payment_status=1
                LEFT JOIN scheme s ON (sa.id_scheme = s.id_scheme) 
                LEFT JOIN branch b ON (b.id_branch = p.id_branch) 
				join chit_settings cs
                WHERE p.id_scheme_account IS NOT NULL AND Date_format(p.date_payment,'%Y-%m-%d')<='$date' and (p.payment_status=1 ) group by b.id_branch";
				   $payments = $this->db->query($sql)->result_array();
  	  	return $payments;
	}
	function reg_existing()
	{
		 $sql="Select reg_existing from chit_settings where id_chit_settings = 1";
		 return $this->db->query($sql)->row()->reg_existing;
	}	
	function get_cancelled_payment_list($id_branch,$from_date,$to_date,$type="")
	{
	   $sql="SELECT
		  p.id_payment,
		  sa.account_name,if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,c.mobile,if(p.payment_ref_number = '','-',p.payment_ref_number) as ref_no,s.code, IFNULL(sa.group_code,'')as group_code,IFNULL(sa.scheme_acc_number,'Not Allocated')as scheme_acc_number,s.code,cs.has_lucky_draw,
			if(s.scheme_type=0,'Amount',if(s.scheme_type=1,'Weight',if(s.scheme_type=3,'FLEXIBLE_AMOUNT','Amount To Weight')))as scheme_type,
		  p.payment_amount,p.date_payment,p.payment_mode,p.bank_acc_no,bank_name,
		  p.bank_IFSC,p.id_transaction, psm.payment_status,psm.color,p.remark
		 FROM ".self::PAY_TABLE." p
		 left join ".self::ACC_TABLE." sa on(p.id_scheme_account=sa.id_scheme_account)
		 Left Join ".self::CUS_TABLE." c on (sa.id_customer=c.id_customer)
		 left join ".self::SCH_TABLE." s on(sa.id_scheme=s.id_scheme) 
		 left join ".self::BRANCH." b on(b.id_branch=p.id_branch) 
		 join chit_settings cs 
		 Left Join payment_status_message psm On (p.payment_status=psm.id_status_msg) 
		 Left Join payment_status ps On (ps.id_payment=p.id_payment) 
		 where p.id_branch=".$id_branch." and p.payment_status=4 and (date(ps.date_upd) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')  ".($type!='' ? " and s.scheme_type=".$type."" :'')." Group by p.id_payment";
		 $r=$this->db->query($sql);
		return $r->result_array();
	}
	function get_cust($mobile)
    {
		$customers=$this->db->query("Select
		   c.id_customer,c.firstname,c.lastname,c.date_of_birth,c.date_of_wed,c.gst_number,a.company_name,
		   a.address1,a.address2,a.address3,ct.name as city,a.pincode,s.name as state,cy.name as country,
		   c.phone,c.mobile,c.email,c.nominee_name,c.nominee_relationship,c.nominee_mobile,
		   c.cus_img,c.pan,c.pan_proof,c.voterid,c.voterid_proof,c.rationcard,c.rationcard_proof,a.id_country,a.id_city,a.id_state,c.id_employee,c.gender,
   	c.comments,c.username,c.passwd,c.is_new,c.active,c.`date_add`,c.`date_upd`
			From
			  customer c
			left join address a on(c.id_customer=a.id_customer)
			left join country cy on (a.id_country=cy.id_country)
			left join state s on (a.id_state=s.id_state)
			left join city ct on (a.id_city=ct.id_city)
			where c.mobile=".$mobile);
		return $customers->row_array();
	} 
// filter Date wise cus reg in dashboard//hh	
function customer_join_list($filterBy,$from_date,$to_date)
	{
	    $id_branch = $this->input->post('id_branch');
 $sql="Select COUNT(id_customer) as joined_thro From customer c
	   		LEFT join branch b on (b.id_branch=c.id_branch)";	
		switch($filterBy){
			case 'MOB': //Joined thro Mobile App
			         $sql=$sql." where added_by = 2  and (date(c.date_add) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') 
			          ".($id_branch!='' && $id_branch!=0 && $id_branch != null ? " and c.id_branch=".$id_branch:'')." ";   
				break;
			case 'WEB': //Joined thro Web App
			         $sql=$sql." where added_by = 0 and  (date(c.date_add) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')
			          ".($id_branch!='' && $id_branch!=0 && $id_branch != null ? " and c.id_branch=".$id_branch:'')." ";  
				break;
			case 'ADMIN': //Joined thro Admin
			          $sql=$sql." where added_by = 1  and (date(c.date_add) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')
			           ".($id_branch!='' && $id_branch!=0 && $id_branch != null ? " and c.id_branch=".$id_branch:'')." ";  
				break;
			case 'COLLECTION': //Joined thro Admin
			          $sql=$sql." where added_by = 3  and (date(c.date_add) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')
			           ".($id_branch!='' && $id_branch!=0 && $id_branch != null ? " and c.id_branch=".$id_branch:'')." ";  
				break;
		}	
		
		//print_r($sql);exit;
		return $this->db->query($sql)->row_array();		
	}
	function get_customer_joined($from_date,$to_date,$type)
{
    $id_branch = $this->input->post('id_branch');
		 /*$sql = "Select c.id_customer,concat(firstname,' ',if(lastname!=NULL,lastname,'')) as name,c.mobile, count(sa.id_scheme_account) as accounts, c.profile_complete,c.`date_add`,c.active From customer c  left join scheme_account sa on (c.id_customer=sa.id_customer and sa.active=1 and sa.is_closed=0)
       where c.added_by=".$type." and (date(c.date_add) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')"; */ 
       
      $sql = "Select c.id_customer,c.cus_type,p.profile_name,if(c.added_by=0,'Web App',if(c.added_by=1,'Admin','Mobile App'))as added_by,concat(firstname,' ',if(lastname!=NULL,lastname,'')) as name,c.mobile, count(sa.id_scheme_account) as accounts,c.is_new,if(c.profile_complete=1,'Yes','No') as profile_complete,c.`date_add`,if(c.active=1,'Yes','No') as active From customer c  left join scheme_account sa on (c.id_customer=sa.id_customer and sa.active=1 and sa.is_closed=0) left join profile p on c.profile_complete=p.id_profile
       where c.added_by=".$type." and (date(c.date_add) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') 
       ".($id_branch!='' && $id_branch!=0 && $id_branch != null ? " and c.id_branch=".$id_branch:'')." 
       GROUP by c.id_customer";   
       // print_r($sql);exit;
	return $customers=$this->db->query($sql)->result_array();
	}
 /*function get_customers_list($from_date,$to_date,$type="")
    {
		$customers=$this->db->query("Select c.id_customer,concat(firstname,' ',if(lastname!=NULL,lastname,'')) as name,c.date_of_birth,c.date_of_wed,c.added_by, a.address1,a.address2,a.address3,ct.name as city,a.pincode,s.name as state,cy.name as country, c.phone,c.mobile,c.email,c.nominee_name,c.nominee_relationship,c.nominee_mobile, c.cus_img,c.pan,c.pan_proof,c.voterid,c.voterid_proof,c.rationcard,c.rationcard_proof,a.id_country,a.id_city,a.id_state,c.id_employee, count(sa.id_scheme_account) as accounts, c.comments,c.username,c.passwd,c.is_new,c.active,c.profile_complete,c.`date_add`,c.`date_upd`,DATE_FORMAT(c.custom_entry_date,'%d-%m-%Y') as custom_entry_date 
		From customer c 
		left join address a on(c.id_customer=a.id_customer) 
		left join country cy on (a.id_country=cy.id_country) 
		left join state s on (a.id_state=s.id_state) 
		left join city ct on (a.id_city=ct.id_city) 
		left join scheme_account sa on (c.id_customer=sa.id_customer and sa.active=1 and sa.is_closed=0) 
									where (date(c.date_add) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."' and  ".($type!='' ?  " and c.added_by=".$type  :'')."");
				//print_r($this->db->last_query());exit;
		return $customers->result_array();
	}*/
function customer_status($from_date,$to_date)
	{
	 $sql="Select count(id_customer) as total,b.id_branch,b.name as  branch_name from ".self::CUS_TABLE." c 
	   		Left join ".self::BRANCH." b on (c.id_branch=b.id_branch) 
	   		where (date(c.date_add) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') group by c.id_branch";
		$r=$this->db->query($sql);
   //print_r($sql);exit;
		return $r->result_array();
	}
 /* function customer_stat($filterBy)
	{
	  $result=0;	
	   $sql="Select count(id_customer) as total from ".self::CUS_TABLE." c 
	   		Left join ".self::BRANCH." b on (c.id_branch=b.id_branch)";
		switch($filterBy){
			case 'Y': //yesterday
			         $sql=$sql." WHERE Date(`date_add`) = CURDATE() - INTERVAL 1 DAY AND c.active='1' ".($dashboard_branch!=0 ? "and c.id_branch=".$dashboard_branch :'')."  ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( c.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')." ";   
				break;
			case 'T': //Today
			         $sql=$sql." WHERE Date(`date_add`) = CURDATE() AND c.active='1' ".($dashboard_branch!=0 ? "and c.id_branch=".$dashboard_branch :'')." ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( c.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')."  ";   
				break;
			case 'LW': //Last Week
			          $sql=$sql." WHERE Date(`date_add`) BETWEEN (CURDATE() � INTERVAL DAYOFWEEK(CURDATE())+6 DAY) AND (CURDATE() � INTERVAL DAYOFWEEK(CURDATE())-1 DAY) AND c.active='1' ".($dashboard_branch!=0 ? "and c.id_branch=".$dashboard_branch :'')." ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( c.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')." ";   
				break;
			case 'TW': //This Week
			          $sql=$sql." WHERE Date(`date_add`) BETWEEN DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY) AND DATE_ADD(CURDATE(), INTERVAL 7-DAYOFWEEK(CURDATE()) DAY) AND s.active='1' ".($dashboard_branch!=0 ? "and c.id_branch=".$dashboard_branch :'')." ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( c.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')."  ";   
				break;	
			case 'TM': //This Month
			          $sql=$sql." WHERE Date(`date_add`) BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) AND LAST_DAY(NOW()) AND c.active='1' ".($dashboard_branch!=0 ? "and c.id_branch=".$dashboard_branch :'')." ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( c.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')." ";   
				break;
			case 'ALL':
			         $sql=$sql."where c.active=1 and c.is_closed=0 ".($dashboard_branch!=0 ? "and s.id_branch=".$dashboard_branch :'')." ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( c.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')." ";
				break;	
		}	
		$r=$this->db->query($sql);
		if($r->num_rows()>0)
		{
			$result= $r->row('total');
		}
		return $result;
	}*/ 

// filter Date wise cus reg in dashboard//HH
       
       public function updateData($data,$id_field,$id_value,$table)
    {    
	    $edit_flag = 0;
	    $this->db->where($id_field,$id_value);
		$edit_flag = $this->db->update($table,$data);
		//print_r($this->db->last_query());exit;
		return ($edit_flag==1?$id_value:0);
	}
	// customer wedding and birthday dates	
		function cus_wishes_list($type,$filterBy) //$type   =>1-birthday,2-wedding day
	{
	    
	    
	    if($type==2)
	    {
	        switch($filterBy){
    			case 'T': //Today
    			         $sql=" and date_format(`date_of_wed`,'%d-%m') = date_format(CURDATE(),'%d-%m') ";   
    				break;
    		    case 'TMRW': //tomorrow
    			         $sql=" and date_format(`date_of_wed`,'%d-%m') = date_format(CURDATE(),'%d-%m')+ INTERVAL 1 DAY";   
    			break;
    			case 'TW': //This Week
    			          $sql=" and date_format(`date_of_wed`,'%d-%m') between date_format(CURDATE(),'%d-%M') and  date_format(CURDATE()+ INTERVAL 7 DAY,'%d-%m') ";  						  
    				break;	
    		}
	    }
	    else
	    {
	        switch($filterBy){
		
    			case 'T': //Today
    			         $sql=" and date_format(`date_of_birth`,'%d-%m') = date_format(CURDATE(),'%d-%m') ";   
    				break;
    		    case 'TMRW': //tomorrow
    			         $sql=" and date_format(`date_of_birth`,'%d-%m') = date_format(CURDATE()+ INTERVAL 1 DAY,'%d-%m')";   
    			break;
    			case 'TW': //This Week
    			          $sql=" and date_format(`date_of_birth`,'%d-%m') between date_format(CURDATE(),'%d-%m') and  date_format(CURDATE()+ INTERVAL 7 DAY,'%d-%m') ";  						  
    				break;	
    		}
	    }
		
        $query=$this->db->query("SELECT cus.id_customer,cus.firstname as cus_name,cus.mobile as cus_mobile,IFNULL(active_acc.tot_acc,0) as active_acc,
        IFNULL(closed_chit.closed_count,0) as closed_count,IFNULL(g.total_wt,0) as total_gold_wt,IFNULL(s.total_wt,0) as total_silver_wt,
        ifnull(date_format(cus.date_of_birth,'%d-%m-%Y'),'-') as date_of_birth,
		ifnull(date_format(cus.date_of_wed,'%d-%m-%Y'),'-') as date_of_wed, IFNULL(v.village_name,'') as village_name
        FROM customer cus 
        LEFT JOIN village v on (v.id_village=cus.id_village)
        
        LEFT JOIN(select count(sa.id_scheme_account) as tot_acc,sa.id_customer,c.mobile from scheme_account sa
        LEFT JOIN customer c on (c.id_customer=sa.id_customer)
        where sa.scheme_acc_number is not null and sa.is_closed=0 GROUP by sa.id_customer) as active_acc on active_acc.id_customer=cus.id_customer
        
        LEFT JOIN(select count(sa.scheme_acc_number) as closed_count,sa.id_customer,c.mobile from scheme_account sa
        LEFT JOIN customer c on (c.id_customer=sa.id_customer)
        where sa.is_closed=1 GROUP by sa.id_customer) as closed_chit on closed_chit.id_customer=cus.id_customer
        
        LEFT JOIN(SELECT SUM(d.gross_wt) as total_wt,b.bill_cus_id
        FROM ret_billing b 
        LEFT JOIN ret_bill_details d ON d.bill_id=b.bill_id
        LEFT JOIN ret_product_master p ON p.pro_id=d.product_id
        LEFT JOIN ret_category c ON c.id_ret_category=p.cat_id
        WHERE b.bill_status=1 AND c.id_metal=1
        group by b.bill_cus_id) as g on g.bill_cus_id=cus.id_customer
        
        LEFT JOIN(SELECT SUM(d.gross_wt)as total_wt,b.bill_cus_id
        FROM ret_billing b 
        LEFT JOIN ret_bill_details d ON d.bill_id=b.bill_id
        LEFT JOIN ret_product_master p ON p.pro_id=d.product_id
        LEFT JOIN ret_category c ON c.id_ret_category=p.cat_id
        WHERE b.bill_status=1 AND c.id_metal=2 group by b.bill_cus_id) as s on s.bill_cus_id=cus.id_customer
        
           
        WHERE cus.id_customer IS NOT NULL
        ".($type==1 ? ' and cus.date_of_birth is NOT NULL' :' and cus.date_of_wed is NOT NULL')." ".$sql."
        ");	
        
        
        // print_r($this->db->last_query());exit;
        return $query->result_array();
        
        
        
	}
//cus birthday
	function cus_birthday($filterBy)
	{
	  $sql="SELECT count(id_customer) as tot_cus FROM customer WHERE date_of_birth is NOT NULL ";	
		switch($filterBy){
		
			case 'T': //Today
			         $sql=$sql." and date_format(`date_of_birth`,'%d-%m') = date_format(CURDATE(),'%d-%m') ";   
				break;
		    case 'TMRW': //tomorrow
			         $sql=$sql." and date_format(`date_of_birth`,'%d-%m') = date_format(CURDATE()+ INTERVAL 1 DAY,'%d-%m')";   
			break;
			case 'TW': //This Week
			          $sql=$sql." and date_format(`date_of_birth`,'%d-%m') between date_format(CURDATE(),'%d-%m') and date_format(CURDATE()+ INTERVAL 7 DAY,'%d-%m') ";   
				break;	
		}	
		$r=$this->db->query($sql);
		$result=$r->row_array();
		// return $result['tot_cus'];
		return $result['tot_cus'];
	}
	
	function cus_wedding_day($filterBy)
	{
	  $sql="SELECT count(id_customer) as tot_cus FROM customer WHERE date_of_wed is NOT NULL ";	
		switch($filterBy){
		
			case 'T': //Today
			         $sql=$sql." and date_format(`date_of_wed`,'%d-%m') = date_format(CURDATE(),'%d-%m') ";   
				break;
		    case 'TMRW': //tomorrow
			         $sql=$sql." and date_format(`date_of_wed`,'%d-%m') = date_format(CURDATE()+ INTERVAL 1 DAY,'%d-%m')";   
			break;
			case 'TW': //This Week
			          $sql=$sql." and date_format(`date_of_wed`,'%d-%m') between date_format(CURDATE(),'%d-%m') and date_format(CURDATE()+ INTERVAL 7 DAY,'%d-%m') ";   
				break;
		}	
		$r=$this->db->query($sql);
		$result=$r->row_array();
		return $result['tot_cus'];
	}
 // customer wedding and birthday dates
//Created by Kanaga Durga R starts here 
//function to get customer count between daterange 
 function reg_stat_count($from_date="",$to_date="",$id_branch="")
 {
	$branchWiseLogin=$this->session->userdata('branchWiseLogin');
			//$id_branch=$this->session->userdata('id_branch');
			$uid=$this->session->userdata('uid');
			$dashboard_branch=$this->session->userdata('dashboard_branch');
	$sql="Select count(id_customer) as total from customer where 
	date(date_add) between'".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."'"
	.($id_branch!='' && $id_branch!=0?  " and id_branch=".$id_branch : '')."";	
	//print_r($sql);exit;
	$r=$this->db->query($sql);
		if($r->num_rows()>0)
		{
			$result= $r->row('total');
		}
		//print_r($result);exit;
		return $result;
 }
//function to get customer list between daterange 
 function customer_detail_bydate($from_date="",$to_date="",$id_branch="")
	{
		$branchWiseLogin=$this->session->userdata('branchWiseLogin');
			//$id_branch=$this->session->userdata('id_branch');
			$uid=$this->session->userdata('uid');
			$dashboard_branch=$this->session->userdata('dashboard_branch');	
			$sql="select
	        c.id_customer,
			concat (c.firstname,' ',if(c.lastname!=Null,c.lastname,'')) as name,
			c.date_of_birth,
			c.mobile,
			if(profile_complete=1,'Yes','No') as profile_complete,
			if(c.active=1,'Active','Disabled') as active,
			if(c.is_new=0,'New','Existing') as is_new,
			c.date_add,
			if(c.added_by=0,'Web App',if(c.added_by=1,'Admin','Mobile App'))as added_by,sa.closing_date,ifnull(sa.closing_balance,0.00) as closing_balance
			from customer c 
			left join scheme_account sa on(c.id_customer=sa.id_customer) 
			where 
			(date(c.`date_add`) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') 
			".($id_branch!='' && $id_branch!=0 ?  " and( c.id_branch=".$id_branch.")":'')."
			
			group by c.id_customer";
			//".($dashboard_branch!='' ? "and c.id_branch=".$dashboard_branch :'')." ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( c.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')."	
			//print_r($sql);exit;
			$r=$this->db->query($sql);
			return $r->result_array();
					
	}
	//getting customer without account count ,by date 
	function cust_wo_acc_bydate()
	{
		$from_date=$this->input->post(from_date);
		$to_date=$this->input->post(to_date);
		$id_branch=$this->input->post(id_branch);
		 $sql="SELECT c.id_customer
		 		FROM customer c
				WHERE NOT EXISTS(
				    SELECT 1 FROM scheme_account sa
				    WHERE c.id_customer=sa.id_customer AND sa.is_closed=0
					
				) and (date(c.`date_add`) BETWEEN
				 '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')
				 ".($id_branch!='' && $id_branch!=0 ?  " and( c.id_branch=".$id_branch.")":'')."";
				
			$count = $this->db->query($sql);
			//print_r($count->num_rows);exit;
			return $count->num_rows();
	}
	//coded by Durga 09.03.2023
	function cust_wo_acc_details_bydate($from_date,$to_date,$id_branch)
	{
		$sql="SELECT c.id_customer,
				concat (c.firstname,' ',if(c.lastname!='',c.lastname,'')) as name,
				if(c.is_new='Y','New User','Existing User')as is_new,
				if(c.profile_complete=1,'Yes','No') as profile_complete,
				if(c.active=1,'Active','Disabled') as active,
				if(c.added_by=0,'Web App',if(c.added_by=1,'Admin','Mobile App'))as added_by,
				c.date_add, c.mobile
						FROM customer c
						WHERE NOT EXISTS(
						SELECT 1 FROM scheme_account sa
						WHERE c.id_customer=sa.id_customer AND sa.is_closed=0 
					)and
					(date(c.`date_add`) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '"
					.date('Y-m-d',strtotime($to_date))."')
					".($id_branch!='' && $id_branch!=0 ?  " and( c.id_branch=".$id_branch.")":'').""
					;		
					//print_r($sql);exit;			
			$r=$this->db->query($sql); 
		return $r->result_array();
	}
	function acc_wo_pay_bydate()
	{
		$from_date=$this->input->post(from_date);
		$to_date=$this->input->post(to_date);
		$id_branch=$this->input->post(id_branch);
		$branchWiseLogin=$this->session->userdata('branchWiseLogin');
			//$id_branch=$this->session->userdata('id_branch');
			$uid=$this->session->userdata('uid');
			$dashboard_branch=$this->session->userdata('dashboard_branch');
		 $sql="SELECT sa.id_scheme_account
				FROM scheme_account sa
				LEFT join branch b on (sa.id_branch=b.id_branch)
        			WHERE NOT EXISTS(
						SELECT p.id_scheme_account FROM payment p
						WHERE sa.id_scheme_account = p.id_scheme_account
						)AND sa.paid_installments=0 AND sa.is_closed=0 	".($dashboard_branch!=0 ? "and sa.id_branch=".$dashboard_branch :'')." ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( sa.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'')." 
						  and (date(sa.`start_date`) BETWEEN '"
						  .date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')"
						  .($id_branch!='' && $id_branch!=0 ?  " and( sa.id_branch=".$id_branch.")":'')."";
						//print_r($sql);exit;
			$count1 = $this->db->query($sql);
			return $count1->num_rows();
	}
	function acc_wo_pay_details_bydate($from_date,$to_date,$id_branch)
	{
		$branchWiseLogin=$this->session->userdata('branchWiseLogin');
			//$id_branch=$this->session->userdata('id_branch');
			$uid=$this->session->userdata('uid'); 
			$dashboard_branch=$this->session->userdata('dashboard_branch');
		$sql= "SELECT sa.id_scheme_account,if(sa.scheme_acc_number  is not null,sa.scheme_acc_number,'Not Allocated') as scheme_acc_number, IFNULL(sa.group_code,'')as group_code,cs.has_lucky_draw,
				   concat (c.firstname,' ',if(c.lastname!=Null,c.lastname,'')) as name,
	       		   c.mobile, s.code, sa.start_date,s.scheme_type as sch_type,
	       		    if(s.scheme_type=0,'Amount',if(s.scheme_type=1,'Weight',if(s.scheme_type=3,'FLEXIBLE_AMOUNT','Amount To Weight')))as scheme_type, 
	       		   if(sa.added_by=0,'Web App',if(sa.added_by=1,'Admin','Mobile App'))as added_by,
	       		   s.total_installments,  CAST(if(s.scheme_type=1,s.max_weight,if(s.scheme_type=3 && s.max_amount!=0,s.max_amount,if(s.scheme_type=3 && s.max_amount=0,(s.max_weight*(SELECT m.goldrate_22ct FROM metal_rates m  order by id_metalrates Desc LIMIT 1)),s.amount))) as int) as amount, s.max_weight, cs.currency_symbol
	         	FROM scheme_account sa
			        LEFT JOIN customer c ON (c.id_customer = sa.id_customer)
			        LEFT JOIN scheme s ON (s.id_scheme = sa.id_scheme)
			        JOIN chit_settings cs
				        WHERE NOT EXISTS(SELECT p.id_scheme_account FROM payment p
										WHERE sa.id_scheme_account = p.id_scheme_account)
						AND sa.paid_installments=0 AND sa.is_closed=0 
						".($id_branch!='' && $id_branch!=0 ?  " and( sa.id_branch=".$id_branch.")":'')."
						and (date(sa.`start_date`) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')";
		$data = $this->db->query($sql)->result_array();
		//.($dashboard_branch!='' ? "and sa.id_branch=".$dashboard_branch :'')." ".($uid!=1 ? ($branchWiseLogin==1? ($id_branch!='' ?  " and( sa.id_branch=".$id_branch." or b.show_to_all=1 )":''):''):'').""
		 //print_r($sql);exit;
		return $data;
	}
	//Created by Kanaga Durga R ends here 
	
	
		public function cus_wishes_list_bydate($from_date,$to_date)
{
	$today_date=date("Y-m-d");
	$yesterday=date("Y-m-d",strtotime("-1 days"));
	
	
	if($from_date==$today_date && $to_date==$today_date)
	{
	$sql="(month(cus.date_of_birth)=month('".$today_date."') and 
	DAYOFMONTH(cus.date_of_birth)=day('".$today_date."')) or 
	(month(cus.date_of_birth)=month('".$today_date."') and 
	DAYOFMONTH(cus.date_of_birth)=day('".$today_date."'))
	or
	(month(cus.date_of_wed)=month('".$today_date."') and 
	DAYOFMONTH(cus.date_of_wed)=day('".$today_date."')) or 
	(month(cus.date_of_wed)=month('".$today_date."') and 
	DAYOFMONTH(cus.date_of_wed)=day('".$today_date."'))";  
	}
	else if($from_date==$yesterday && $to_date==$yesterday)
	{
		$sql="(month(cus.date_of_birth)=month('".$yesterday."') and 
		DAYOFMONTH(cus.date_of_birth)=day('".$yesterday."')) or 
		(month(cus.date_of_birth)=month('".$yesterday."') and 
		DAYOFMONTH(cus.date_of_birth)=day('".$yesterday."'))
		or
		(month(cus.date_of_wed)=month('".$yesterday."') and 
		DAYOFMONTH(cus.date_of_wed)=day('".$yesterday."')) or 
		(month(cus.date_of_wed)=month('".$yesterday."') and 
		DAYOFMONTH(cus.date_of_wed)=day('".$yesterday."'))";  
	}
	
	else
	{
	//add this part  
	
		$sql="(month(cus.date_of_birth) between month('".$from_date."') and month('".$to_date."'))
		or 
		(month(cus.date_of_wed) between month('".$from_date."') and month('".$to_date."'))";
	
	//or this part 
	
	/*	$sql="(month(cus.date_of_birth)=month('".$from_date."') and 
		DAYOFMONTH(cus.date_of_birth)>=day('".$from_date."')) or 
		(month(cus.date_of_birth)=month('".$to_date."') and 
		DAYOFMONTH(cus.date_of_birth)<=day('".$to_date."'))
		or
		(month(cus.date_of_wed)=month('".$from_date."') and 
		DAYOFMONTH(cus.date_of_wed)>=day('".$from_date."')) or 
		(month(cus.date_of_wed)=month('".$to_date."') and 
		DAYOFMONTH(cus.date_of_wed)<=day('".$to_date."'))";*/
	}

	
	
	$query=$this->db->query("SELECT cus.id_customer,cus.firstname as cus_name,cus.mobile as cus_mobile,IFNULL(active_acc.tot_acc,0) as active_acc,
	IFNULL(closed_chit.closed_count,0) as closed_count,IFNULL(g.total_wt,0) as total_gold_wt,IFNULL(s.total_wt,0) as total_silver_wt,
	date_format(cus.date_of_birth,'%d-%m-%Y') as date_of_birth,date_format(cus.date_of_wed,'%d-%m-%Y') as date_of_wed,IFNULL(v.village_name,'') as village_name
	FROM customer cus 
	LEFT JOIN village v on (v.id_village=cus.id_village)
	
	LEFT JOIN(select count(sa.id_scheme_account) as tot_acc,sa.id_customer,c.mobile from scheme_account sa
	LEFT JOIN customer c on (c.id_customer=sa.id_customer)
	where sa.scheme_acc_number is not null and sa.is_closed=0 GROUP by sa.id_customer) as active_acc on active_acc.id_customer=cus.id_customer
	
	LEFT JOIN(select count(sa.scheme_acc_number) as closed_count,sa.id_customer,c.mobile from scheme_account sa
	LEFT JOIN customer c on (c.id_customer=sa.id_customer)
	where sa.is_closed=1 GROUP by sa.id_customer) as closed_chit on closed_chit.id_customer=cus.id_customer
	
	LEFT JOIN(SELECT SUM(d.gross_wt) as total_wt,b.bill_cus_id
	FROM ret_billing b 
	LEFT JOIN ret_bill_details d ON d.bill_id=b.bill_id
	LEFT JOIN ret_product_master p ON p.pro_id=d.product_id
	LEFT JOIN ret_category c ON c.id_ret_category=p.cat_id
	WHERE b.bill_status=1 AND c.id_metal=1
	group by b.bill_cus_id) as g on g.bill_cus_id=cus.id_customer
	
	LEFT JOIN(SELECT SUM(d.gross_wt)as total_wt,b.bill_cus_id
	FROM ret_billing b 
	LEFT JOIN ret_bill_details d ON d.bill_id=b.bill_id
	LEFT JOIN ret_product_master p ON p.pro_id=d.product_id
	LEFT JOIN ret_category c ON c.id_ret_category=p.cat_id
	WHERE b.bill_status=1 AND c.id_metal=2 group by b.bill_cus_id) as s on s.bill_cus_id=cus.id_customer
	
	   
	WHERE cus.id_customer IS NOT NULL 
	And ".$sql." ");	
	
	/*
	(month(cus.date_of_birth)=month('".$from_date."') and 
	DAYOFMONTH(cus.date_of_birth)>=day('".$from_date."')) or 
	(month(cus.date_of_birth)=month('".$to_date."') and 
	DAYOFMONTH(cus.date_of_birth)<=day('".$to_date."'))
	or
	(month(cus.date_of_wed)=month('".$from_date."') and 
	DAYOFMONTH(cus.date_of_wed)>=day('".$from_date."')) or 
	(month(cus.date_of_wed)=month('".$to_date."') and 
	DAYOFMONTH(cus.date_of_wed)<=day('".$to_date."'))

	*/
	
	//print_r($this->db->last_query());exit;
	return $query->result_array();

}
	
}
?>
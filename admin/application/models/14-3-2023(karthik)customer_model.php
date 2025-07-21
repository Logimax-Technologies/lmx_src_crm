<?php

if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer_model extends CI_Model

{

	const TABLE_NAME 	= "customer";

	const ADDRESS_TABLE = "address";

	

	

	function __construct()

    {

        parent::__construct();

    }

    

    	//encrypt

	public function __encrypt($str)

	{

		return base64_encode($str);		

	}	

	

	//decrypt

	public function __decrypt($str)

	{

		return base64_decode($str);		

	}

	

   

	// Customer List chked&updtd emp login branchwise  data show//HH

    function get_all_customers($limit="",$id_branch='',$id_village='')

    {
		        	$branch_settings=$this->session->userdata('branch_settings');
					$branch=$this->session->userdata('id_branch');
					$uid=$this->session->userdata('uid');
					
					$company_settings = $this->session->userdata('company_settings');
                    $id_company = $this->session->userdata('id_company');

$limit = "";
		 $sql = "Select CONCAT(ag.firstname,' ',IFNULL(ag.lastname,''),' - ',ag.agent_code) as agent_name,c.id_customer,concat(c.firstname,' ',if(c.lastname!=NULL,c.lastname,'')) as name,c.mobile, count(sa.id_scheme_account) as accounts,c.profile_complete,c.`date_add`,
		IFNULL(c.custom_entry_date,' ') as custom_entry_date,c.active,cs.edit_custom_entry_date,c.added_by
		From customer c  
		 left join scheme_account sa on (c.id_customer=sa.id_customer and sa.active=1 and sa.is_closed=0)
		 left join agent ag ON (ag.id_agent = c.id_agent)
		 left join branch b on b.id_branch=c.id_branch
		 join chit_settings cs 
	  
	   where c.active=1   
	   ".($uid!=1 && $uid!=2 ? ($branch_settings==1 ? ($id_branch!='' && $id_branch!=0 ? "and c.id_branch=".$id_branch."" : ($branch!='' ? "and(c.id_branch=".$branch." or b.show_to_all=2)":'')):'') : ($id_branch!='' && $id_branch!=0 ? "and c.id_branch=".$id_branch."" :''))." 
	   ".($id_village!='' ? " and c.id_village='".$id_village."'":'')." 
	    ".($id_company!='' &&  $company_settings == 1? " and c.id_company='".$id_company."'":'')."
	  Group by c.id_customer
	  Order By c.id_customer Desc ".($limit!=NULL? " LIMIT ".$limit : " ");
	  
	 // print_r($sql);exit;

		return $customers=$this->db->query($sql)->result_array();

	}   

	

	 function get_customers_by_date($from_date,$to_date,$id_branch)

    {
        	$date_type = $this->input->post('date_type');
        	$company_settings = $this->session->userdata('company_settings');
            $id_company = $this->session->userdata('id_company');
            
		 $sql = "Select CONCAT(ag.firstname,' ',IFNULL(ag.lastname,''),' - ',ag.agent_code) as agent_name,

		   c.id_customer,concat(firstname,' ',if(lastname!=NULL,lastname,'')) as name,c.date_of_birth,c.date_of_wed,c.added_by,

		   a.address1,a.address2,a.address3,ct.name as city,a.pincode,s.name as state,cy.name as country,

		   c.phone,c.mobile,c.email,c.nominee_name,c.nominee_relationship,c.nominee_mobile,

		   c.cus_img,c.pan,c.pan_proof,c.voterid,c.voterid_proof,c.rationcard,c.rationcard_proof,a.id_country,a.id_city,a.id_state,c.id_employee,

       count(sa.id_scheme_account) as accounts,

   	c.comments,c.username,c.passwd,c.is_new,c.active,c.profile_complete,c.`date_add`,c.`date_upd`,IFNULL(Date_format(c.custom_entry_date,'%d-%m%-%Y'),'') as custom_entry_date

			From

			  customer c

			left join address a on(c.id_customer=a.id_customer)
			
			left join agent ag ON (ag.id_agent = c.id_agent)

			left join country cy on (a.id_country=cy.id_country)

			left join state s on (a.id_state=s.id_state)

			left join city ct on (a.id_city=ct.id_city)

      left join scheme_account sa on (c.id_customer=sa.id_customer and sa.active=1 and sa.is_closed=0)

        Where (date(".($date_type!='' ? ($date_type==2 ?"c.custom_entry_date":"c.date_add") : "c.date_add").") BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') ".($id_branch!='' ? " and c.id_branch=".$id_branch."" :'')."
        ".($id_company!='' &&  $company_settings == 1? " and c.id_company='".$id_company."'":'')."

	  Group by c.id_customer";
    
       // print_r($sql);exit;
	

		return $customers=$this->db->query($sql)->result_array();

	

	}

	

	 function ajax_get_unallocated_customers()

    {

		$sql = "select id_customer as id,concat(firstname,' ',if(lastname!=NULL,lastname,'')) as name,mobile

			    from  ".self::TABLE_NAME." 

				where  active=1 

				and   id_customer not in (select id_customer from scheme_account sa where active=1 or is_closed=1)

				order by name";

		$customers=$this->db->query($sql);

		return $customers->result_array();

	}

	

	 function ajax_get_all_customers()

    {

		$customers=$this->db->query("select c.id_customer as id,if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,c.mobile 
		from customer c 
		where c.active=1 ");

		return $customers->result_array();

	}



	function ajax_get_customers($param)

    {

		$customers=$this->db->query("select c.id_customer as value,if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,c.mobile as location, if(c.cus_img = '','../../default_icon.png',CONCAT(c.cus_img,'.jpg')) as avator from customer c where c.active=1 and c.firstname LIKE '$param%' OR c.lastname LIKE '$param%' OR c.mobile LIKE '$param%'");

		return $customers->result_array();

	}

	

	function get_customer($id)

    {

		$customers=$this->db->query("Select

		   c.id_customer,c.firstname,c.lastname,c.date_of_birth,c.date_of_wed,if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname)) as name,

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

	

	function get_cust($id)

    {

		$customers=$this->db->query("Select

		   c.id_profession,c.id_customer,c.firstname,c.lastname,c.date_of_birth,c.date_of_wed,c.gst_number,a.company_name,c.id_branch,c.religion,

		   a.address1,a.address2,a.address3,ct.name as city,a.pincode,s.name as state,cy.name as country,c.id_village,v.post_office,v.taluk,

		   c.phone,c.mobile,c.email,c.nominee_name,c.nominee_relationship,c.nominee_mobile,c.is_cus_synced,

		   c.cus_img,c.pan,c.pan_proof,c.voterid,c.voterid_proof,c.rationcard,c.rationcard_proof,a.id_country,a.id_city,a.id_state,c.id_employee,c.gender,

   	c.comments,c.username,c.passwd,c.is_new,c.active,c.`date_add`,c.`date_upd`,c.cus_type

			From

			  customer c

			left join address a on(c.id_customer=a.id_customer)

			left join country cy on (a.id_country=cy.id_country)

			left join state s on (a.id_state=s.id_state)

			left join city ct on (a.id_city=ct.id_city)
			
			left join village v on (v.id_village=c.id_village)

			where c.id_customer=".$id);
         //print_r($this->db->last_query());exit;
		return $customers->row_array();

	}

	

	

	

	public function get_cus_address($id)

    {

		$this->db->select('id_country,id_state,id_city,address,address1,pincode');

		$this->db->where('id_customer', $id); 

		$list_data=$this->db->get(self::ADDRESS_TABLE);

		return $list_data->row_array();

	}

    function empty_record()

    {

		$data=array(

			'id_customer'			=> NULL,
			
			'id_village'			=> NULL,
			
			'id_branch'			=> NULL,

			'lastname'				=> NULL,

			'firstname'				=> NULL,

			'date_of_birth'			=> NULL,

			'date_of_wed'			=> NULL,

		    'id_employee'			=> 0,

		    'id_country' 			=> 0,

			'id_state' 				=> 0,

			'id_city' 				=> 0,

			'address1' 				=> NULL,

			'address2' 				=> NULL,

			'address3' 				=> NULL,

			'pincode' 				=> NULL,
			
			'post_office' 				=> NULL,
			
		    'taluk' 				=> NULL,

			'email'					=> NULL,	

			'gender'				=> -1,

			'age'					=> NULL,

			'mobile'				=> NULL,

			'phone'					=> NULL,

			'nominee_name'			=> NULL,

			'nominee_relationship'	=> NULL,

			'nominee_mobile'		=> NULL,	

			'pan'					=> NULL,

			'pan_proof'				=> NULL,

			'voterid'				=> NULL,

			'voterid_proof'			=> NULL,

			'rationcard'			=> NULL,

			'rationcard_proof'		=> NULL,

			'comments'				=> NULL,

			'username'				=> NULL,

			'passwd'				=> NULL,
			'gst_number'			=>null,
			'company_name'			=>null,
			'religion'	    		=>null,
			'active'				 =>1,
			'is_cus_synced'         =>0,
			'cus_type'				=> 1,

		);

		

		return $data;

	}

    

    public function insert_customer($data)

    {

    	$cus_id=0;

    	$insert_flag=0;

		$cus_info=$this->db->insert(self::TABLE_NAME,$data['info']);

		

		if($cus_info)
		{	

			$cus_id=$this->db->insert_id();

			$data['address']['id_customer']=$cus_id;

			$insert_flag=$this->db->insert(self::ADDRESS_TABLE,$data['address']);
            
			if($insert_flag){
				
				$id_address=$this->db->insert_id();
				$address = array('id_address' => $id_address);
				$this->db->where('id_customer',$cus_id); 
		        $this->db->update(self::TABLE_NAME,$address);				
			}

		}

		return ($insert_flag==1?$cus_id:0);

	}

	

	//to insert imported customer

	public function insert_imported_customer($data)

    {

    	$cus_id=0;

    	$insert_flag=0;

		$cus_info=$this->db->insert(self::TABLE_NAME,$data['customer']);

		if($cus_info)

		{

			$cus_id=$this->db->insert_id();

			$data['address']['id_customer']=$cus_id;

			

			$insert_flag=$this->db->insert(self::ADDRESS_TABLE,$data['address']);

			

		}

		return ($insert_flag==1?$cus_id:0);

	}

		

	public function insert_customer_only($data)

	{

		$status=$this->db->insert(self::TABLE_NAME,$data);

		$id=$this->db->insert_id();		

		return $id;

	}

	

	public function username_available($username)

	{

		$this->db->select('username');

		$this->db->where('username', $username); 

		$user=$this->db->get(self::TABLE_NAME);

		return ($user->num_rows() > 0? TRUE : FALSE);

	}

	

	public function update_customer($data,$id)
    {    	
    	$edit_flag=0;
    	$this->db->where('id_customer',$id); 
		$cus_info=$this->db->update(self::TABLE_NAME,$data['info']);
		
	//	echo $this->db->last_query();exit;
		
		if($id)
		{			
		    $qry = $this->db->query('select id_customer from address where id_customer='.$id);
		    if($qry->num_rows() > 0){
				$this->db->where('id_customer',$id); 
			    $edit_flag = $this->db->update(self::ADDRESS_TABLE,$data['address']);
			}
			else{
			    $data['address']['id_customer']=$id;
				$edit_flag = $this->db->insert(self::ADDRESS_TABLE,$data['address']);
			}
			
		//echo $this->db->last_query();exit;
		}
		return ($edit_flag==1?$id:0);
	}

	



		

	public function update_customer_only($data,$id)

    {    	

    	$edit_flag=0;

    	$this->db->where('id_customer',$id); 

		$cus_info=$this->db->update(self::TABLE_NAME,$data);		

		return $cus_info;

	}

	

	public function update_images($id,$data)

	{

		$this->db->where('id_customer',$id); 

		$cus=$this->db->update(self::TABLE_NAME,$data);

		return $cus;

	}

	

    	function check_pay_records($id)

	{

		$sql = "select p.* from customer c

                LEFT JOIN scheme_account sa on (c.id_customer = sa.id_customer)

				LEFT JOIN payment p on (sa.id_scheme_account = p.id_scheme_account)

				where p.payment_status <> -1 AND c.id_customer = ".$id;

		$status = $this->db->query($sql);

		

		if($status->num_rows()>0)

		{

			return TRUE;

		}

	}	

	

	function check_acc_records($id)

	{

		$sql = "select * from scheme_account where id_customer = ".$id;

		$status = $this->db->query($sql);

		

		if($status->num_rows()>0)

		{

			return TRUE;

		}

	}	

/*-- Coded by ARVK --*/			 

	function customer_count()

	{

		$sql = "SELECT id_customer FROM customer";

		return $this->db->query($sql)->num_rows();

	}	

/*-- / Coded by ARVK --*/			 

	public function delete_customer($id)

    {

    	

        $this->db->where('id_customer', $id);

        $child= $this->db->delete(self::ADDRESS_TABLE);

        if($child)

        {

		  $this->db->where('id_customer', $id);

           $status= $this->db->delete(self::TABLE_NAME); 
           $this->db->where('id_customer', $id);
           $wal_ac = $this->db->delete('wallet_account'); 
		}

  

		

		return $status;

	} 

	public function getCustomerByMobile($mobile)

	{

		$this->db->select('id_customer');

		$this->db->where('mobile',$mobile);

		$customer=$this->db->get(self::TABLE_NAME);

	   	

		if($customer->num_rows()==1)

		{

		  return $customer->row()->id_customer;

		}

		else

		{

		  return 0;

		}

		

		

	}

	public function get_login_detail($id){

		$cust=$this->db->query("Select c.firstname as name,c.passwd,c.mobile,c.email

			From

			  customer c

			where c.id_customer=".$id);

			$loginData = $cust->row_array();

			$cus =array('name'=>$loginData['name'],'email'=>$loginData['email'],'passwd'=>($this->__decrypt($loginData['passwd'])),'mobile'=>$loginData['mobile']);

		return $cus;

	}

	

	public function mobile_available($mobile,$id_customer="")

	{
	    
	    $id_company=$this->session->userdata('id_company');
        $company_settings=$this->session->userdata('company_settings');

		$this->db->select('mobile');

		$this->db->where('mobile', $mobile);

        if($id_customer)

        {

			$this->db->where('id_customer <>', $id_customer); 

		}	
		if($id_company != 0 && $company_settings == 1)
		{
		    $this->db->where('id_company',$id_company);
		}

		

		$status=$this->db->get(self::TABLE_NAME);

	  

		if($status->num_rows()>0)

		{

			return TRUE;

		}

	}		

	

		

	public function email_available($email,$id_customer="")

	{
	    
	    $id_company=$this->session->userdata('id_company');
        $company_settings=$this->session->userdata('company_settings');

		$this->db->select('email');

		$this->db->where('email', $email); 

		if($id_customer)

        {

			$this->db->where('id_customer <>', $id_customer); 

		}			
		
		if($id_company != 0 && $company_settings == 1)
		{
		    $this->db->where('id_company',$id_company);
		}

		

		$status=$this->db->get(self::TABLE_NAME);

		

		if($status->num_rows()>0)

		{

			return TRUE;

		}		

	}
	
	 function ajax_get_customers_list($param)

    {
        
        $company_settings = $this->session->userdata('company_settings');
        $id_company = $this->session->userdata('id_company');
        
		$customers=$this->db->query("select c.id_customer, IFNULL(c.mobile,'')as mobile,c.firstname,c.lastname
				from  customer c  
									where c.active=1 
									".($id_company!='' &&  $company_settings == 1? " and c.id_company='".$id_company."'":'')."
									and (c.firstname LIKE '$param%'  OR c.lastname LIKE '$param%' OR c.mobile LIKE '$param%')");
				//print_r($this->db->last_query());exit;
		return $customers->result_array();

	}

	

	public function check_password($id_customer,$pswd)

	{

		$this->db->select('passwd');

		$this->db->where('id_customer', $id_customer); 

		$this->db->where('passwd', $pswd); 

		

		$status=$this->db->get(self::TABLE_NAME);

		

		if($status->num_rows()>0)

		{

			return TRUE;

		}	

	}
	
	function get_customer_by_mobile($mobile)
    {
        
        $id_company=$this->session->userdata('id_company');
        $company_settings=$this->session->userdata('company_settings');
        
		$customers="Select c.mobile	From customer c where  c.mobile=".$mobile."".($id_company!=0 && $company_settings == 1? " and c.id_company=".$id_company."" :'')."";
					$records = $this->db->query($customers);
				    if($records->num_rows()>0)
				    {
				    	return 1;
				    }
				    else
				    {
				    	return 0;
				    }
	}
	
	function get_village()
    {
		$sql="Select *From village";
		return $data = $this->db->query($sql)->row_array();			
	}
	
	/**	
	* Sync Customer accounts On Registration by Mobile Number
	* Scheme Accounts and payments
	*/
	
	function getBranchCode($id_branch)
    {
		$sql="Select short_name from branch where id_branch=".$id_branch;
		$data = $this->db->query($sql);
		return $data->row()->short_name;			
	}
	
	function insExisAcByMobile($data) 
	{
	   $result = array();
	   if($data['branch_code'] != NULL && $data['branch_code'] != ""){
	       $resultset = $this->db->query("select * from customer_reg where record_to=2 and is_registered_online=0 and is_closed=0 and branch_code='".$data['branch_code']."' and mobile=".$data['mobile']);
	   }
	   else if($data['id_branch'] != '' || $data['id_branch'] != NULL){
	       $resultset = $this->db->query("select * from customer_reg where record_to=2 and is_registered_online=0 and is_closed=0 and id_branch='".$data['id_branch']."' and mobile=".$data['mobile']);
	   }
	   else{
	         $resultset = $this->db->query("select * from customer_reg where record_to=2 and is_registered_online=0 and is_closed=0 and mobile=".$data['mobile']);
	   } 
		if($resultset->num_rows() > 0 ){
			$records = array();
			foreach($resultset->result() as $row)
			{
			    $data['sync_scheme_code'] = $row->sync_scheme_code;
			    $data['client_id'] = $row->clientid;
			    $id_scheme = $this->getschId($data);
			    
			    $sql =$this->db->query("SELECT * FROM scheme_account WHERE ref_no='".$row->clientid."'");
			    $existing_sch = $sql->row_array();
			    if($sql->num_rows() > 0 ){
				    $data['id_sch_ac'] = $existing_sch['id_scheme_account'];
				   
    				$result[] =  $data;
    				
			    }else{
			        
			        if($id_scheme > 0){
					$records = array( 	'id_customer' 		=> $data['id_customer'],
									'id_scheme'			=> $id_scheme,
									'scheme_acc_number' => $row->scheme_ac_no,
									'ref_no'            => $row->clientid,
									'account_name' 		=> $row->account_name,
									'group_code' 		=> $row->group_code,
									'start_date' 		=> $row->reg_date,
									'maturity_date' 	=> $row->maturity_date,
									'is_new' 			=> $row->new_customer,
									'firstPayment_amt'	=> $row->firstPayment_amt,
									'firstpayment_wgt'  => $row->firstpayment_wgt,
									'fixed_rate_on' 	=> $row->fixed_rate_on,
									'fixed_metal_rate' 	=> $row->fixed_metal_rate,
									'fixed_wgt' 		=> $row->fixed_wgt,
									'id_branch' 		=> ($row->id_branch > 0 ? $row->id_branch : $data['id_branch'] ),
									'date_add' 			=> date("Y-m-d H:i:s"),
									'is_registered' 	=> 1,
									'active' 			=> 1, 
									'added_by' 			=> 1
								); 
					$status = $this->db->insert('scheme_account',$records);					
					//echo '$updateCus-true';exit; 
					if($status){
						$data['id_sch_ac'] = $this->db->insert_id();
						$result[] =  $data;
					}
				}
			    } 
			} 
		}
		return $result; 
	} 
	
	function getschId($data) 
	{
		$branchwise_scheme = 0;
    	$settings = $this->db->query("select branchwise_scheme from chit_settings"); 
    	if($settings->num_rows() > 0 ){
    		$branchwise_scheme =  $settings->row()->branchwise_scheme;
    	}  
    	if($branchwise_scheme == 1 && ($data['id_branch'] != '' || $data['id_branch'] != NULL)){
    	   $result = $this->db->query("SELECT s.id_scheme
                                       FROM `scheme` s
                                        LEFT JOIN scheme_branch sb ON sb.id_scheme = s.id_scheme
                                       WHERE sync_scheme_code='".$data['sync_scheme_code']."' AND sb.id_branch='".$data['id_branch']."'" 
                                    );  
    	}else{
    		$result = $this->db->query("select id_scheme from scheme where sync_scheme_code='".$data['sync_scheme_code']."'");  
    	}
    	if($result->num_rows() > 0 ){
    		return $result->row()->id_scheme;
    	}
    	else{
    		return null;
    	}
	}
	
	function syncPayData($ac_data) 
	{
		//echo "<pre>";print_r($ac_data);
		$succeedIds = array();
		$no_records = 0;
		foreach($ac_data as $data){	 
			$resultset = $this->db->query("select * from transaction where is_transferred='N' and record_to=2 and client_id='".$data['client_id']."'"); 
			$i = 1;
			if($resultset->num_rows() > 0 ){
				$records = array();
				foreach($resultset->result() as $row)
				{				
				    if($row->is_modified == 0){
    					$records = array( 'id_scheme_account'   =>$data['id_sch_ac'],
    	                                    'metal_rate'	    =>$row->rate,
    	                                    'receipt_no'        =>$row->receipt_no,
    	                                    'metal_weight'      =>$row->weight,
    	                                    'payment_amount'	=>$row->amount,
    	                                     'payment_ref_number' =>$row->ref_no,
    	                                    'actual_trans_amt'	=>$row->amount,
    	                                    'date_payment'	    =>$row->payment_date,
    	                                    'date_add'	        =>$row->payment_date,
    	                                    'id_branch' 		=> ($row->id_branch > 0 ? $row->id_branch : $data['id_branch'] ),
    	                                    'custom_entry_date'	=>$row->custom_entry_date,
    	                                    'payment_status'	=>1, 
    	                                    'payment_mode'      =>$row->payment_mode,
    	                                    'payment_status'    =>$row->payment_status,
    	                                    //	'dues' =>$row->NO_OF_INSTAL,
    	                                    'payment_type'      =>'Offline',
    	                                    'is_offline'	    => 1,
    	                                    'due_type'          =>$row->due_type,
    	                                    'due_month'         =>$row->due_month,
    	                                    'due_year'          =>$row->due_year,
    	                                    'added_by'          =>0, //admin
    	                                    'installment'       => $row->installment_no,
    	                                    'discountAmt'       =>(!empty($row->discountAmt) ? $row->discountAmt :	0.00)
    	                                );
    	               	$status = $this->db->insert('payment',$records);
				    }elseif($row->is_modified == 1){
				        //update if offline record is with cancelled status
            				$upd_array = array ( "payment_status" 	=> 4,
            			   					    'receipt_no'        =>$row->receipt_no,
            			   						"remark" 			=> $row->remarks,
            			   						"date_upd" 			=> date('Y-m-d H:i:s'),
            			   						"payment_ref_number"=> $row->ref_no
            			    					);
            			    $status = $this->db->insert('payment',$upd_array);
				    }
										
				
				 	 /*echo $this->db->_error_message();
				 	echo $this->db->last_query();exit; */
					if($status){
					    $succeedIds[] = $row->id_transaction;
					}
					$i++;
					
				} 
			}else{
				$no_records += 1; 
				// There wont be any reords for plan 2 & 3 schemes so by default set valuse as 1.
			} 
		}
		return array('succeedIds' => $succeedIds,'no_records' => $no_records);
	}
	
	function updateInterTableStatus($data,$payData)
	{	
	    $transtatus = false; 
	    foreach($data as $accdata){
			$arrdata = array("id_scheme_account"=>$accdata['id_sch_ac'],"is_registered_online"=>1,"is_modified"=>0,"transfer_date"=>date('Y-m-d H:i:s'),'is_transferred'=>'Y');
			$this->db->where('clientid',$accdata['client_id']); 
			//$this->db->where('id_branch',$accdata['id_branch']); 
			$accstatus= $this->db->update('customer_reg',$arrdata);
			if($accstatus){
			    foreach($payData as $data){
    		        $upddata = array("is_modified"=>0,"transfer_date"=>date('Y-m-d H:i:s'),'is_transferred'=>'Y');
    				$this->db->where('id_transaction',$data); 
    				$transtatus= $this->db->update('transaction',$upddata);
			    }
			}
		} 
		return $transtatus;
	} 
	
	// . Sync accounts function ends

		function get_entrydate($id)

	{
        $sql = "SELECT entry_date as custom_entry_date,cs.edit_custom_entry_date FROM ret_day_closing 
		join chit_settings cs 
		".($id!=''?  "where id_branch=".$id." " :'')."";
         //print_r($sql);exit;
	    return $this->db->query($sql)->row_array();
  	
	}
	
	function isCustomerExist($mobile,$id_branch){
	    $sql = $this->db->query("SELECT id_customer FROM customer WHERE mobile=".$mobile);
	    if($sql->num_rows() == 1){
	        return $sql->row('id_customer');
	    }else{
	        return 0;
	    }
	}
	
	public function update_address($id,$data)
	{
		$this->db->where('id_customer',$id); 
		$cus=$this->db->update('address',$data);
		return $cus;
	}
	function get_state_id($name)
	{
	    $sql = $this->db->query("SELECT id_state FROM state WHERE name = '.$name.'");
	    if($sql->num_rows() == 1){
	        return $sql->row('id_state');
	    }else{
	        return 0;
	    }
	}
	function get_city_id($name)
	{
	    $sql = $this->db->query("SELECT id_city FROM city WHERE name = '.$name.'");
	    if($sql->num_rows() == 1){
	        return $sql->row('id_city');
	    }else{
	        return 0;
	    }
	}
	
	function get_customer_range($lower,$upper)
	{
		$accounts=$this->db->query("select
							  c.id_customer,concat (c.firstname,' ',if(c.lastname!=Null,c.lastname,'')) as name,c.email,a.address1,a.address2,c.mobile
							from customer c
							left join address a on (a.id_customer=c.id_customer)
							where c.id_customer Between ".$lower." and ".$upper."
							");
		return $accounts->result_array();
	}
	public function getAddressId($id_customer)
	{
	
		$this->db->select('id_address');

		$this->db->where('id_customer',$id_customer);

		$customer=$this->db->get('address');

		if($customer->num_rows()==1)
		{
		  return $customer->row()->id_address;

		}
		else
		{
		  return 0;

		}
		
	}
	
	public function insert_address($data)

	{

		$status=$this->db->insert('address',$data);

		$id=$this->db->insert_id();		

		return $id;

	}
	function Searchcustomer($SearchTxt)
	{
        $data = $this->db->query("SELECT c.id_customer as value, concat(c.firstname,'-',c.mobile) as label,c.id_village,v.village_name,if(c.is_vip=1,'Yes','No') as vip,c.firstname,c.lastname,
        a.id_country,a.id_city,a.id_state,c.date_of_birth,c.date_of_wed,c.added_by,a.address1,a.address2,a.address3,ct.name as city,a.pincode,s.name as state,cy.name as country,c.email,c.gender,
        c.send_promo_sms,c.religion
        FROM customer c
        left join address a on(c.id_customer=a.id_customer)
        left join village v on v.id_village=c.id_village
        left join country cy on (a.id_country=cy.id_country)
		left join state s on (a.id_state=s.id_state)
		left join city ct on (a.id_city=ct.id_city)
        WHERE username like '%".$SearchTxt."%' OR mobile like '%".$SearchTxt."%' OR firstname like '%".$SearchTxt."%'"); 
        return $data->result_array();
	}
	/*public function update_customer($data,$id)
    {    	
    	$edit_flag=0;
    	$this->db->where('id_customer',$id); 
		$cus_info=$this->db->update(self::TABLE_NAME,$data['info']);
				
		    $qry = $this->db->query('select id_customer from address where id_customer='.$id);
		    if($qry->num_rows() > 0){
				$this->db->where('id_customer',$id); 
			    $edit_flag = $this->db->update(self::ADDRESS_TABLE,$data['address']);
			}
			else{
			    $data['address']['id_customer']=$id;
				$edit_flag = $this->db->insert(self::ADDRESS_TABLE,$data['address']);
			}
			
			//echo $this->db->last_query();exit;
		return ($edit_flag==1?$id:0);
	}*/
	


    function getAllActiveAgents(){
            $sql="Select id_agent,CONCAT(firstname,' ',IFNULL(lastname,''),' - ',agent_code) as agent_data,agent_code From agent where active = 1";
    		return $data = $this->db->query($sql)->result_array();	
    }
     
    public function allocate_agent($data,$id)
    {    	
	    $cus_info=0;

    	$this->db->where('id_customer',$id); 

		$cus_info=$this->db->update(self::TABLE_NAME,$data);		

		return ($cus_info==1? 1 : 0);

    }
    
    

    
}

?>
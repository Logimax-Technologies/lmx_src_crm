<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');  
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');
require(APPPATH.'libraries/REST_Controller.php');
require_once(APPPATH.'libraries/payu.php');
function payment_success()
{
	$userObj = new Paymt();
	$result = $userObj->successURL();	
}
function payment_failure()
{
	$userObj = new Paymt();
	$result = $userObj->failureURL();
}
class Mobile_api extends REST_Controller
{
	const MOD_MOB = "mobileapi_model";
	const SCH_MOD = "scheme_modal";
	const PAYU_KEY    = 'gWrBuQ';
	const PAYU_SALT   = 'qHs42ie1';
	const CUS_IMG_PATH = 'admin/assets/img/customer/';
	const CUS_ADHAR_PATH = 'assets/aadhar_file/';
	//const PAY_URL     = base_url('index.php/mobile_api/');
	function __construct()
	{
		parent::__construct();
		$this->response->format = 'json';
		$this->load->model(self::MOD_MOB);
		$this->load->model('email_model');
		$this->load->model('services_modal');
		$this->load->model('login_model');
		$this->load->model('registration_model');
		$this->load->model('scheme_modal');
		$this->load->model('payment_modal');
		$this->load->model('sms_model');
		ini_set('date.timezone', 'Asia/Calcutta');
		//NOTE:set maintenance mode in index page to put maintenance for both mobile,web app
		//$this->maintenance_mode = '1';	//0-YES , 1-NO
		//$this->maintenance_text= "Text here";
	     $this->current_android_version= "1.0.0";
         $this->new_android_version = "1.0.1";
         $this->current_ios_version = "1.0.0";
         $this->new_ios_version = "1.0.1";
		$this->upgrade_text = "New version available in play store,Upgrade now!!"; // Version upgrade alert text
		$this->comp = $this->mobileapi_model->company_details();
		$this->sms_data = $this->services_modal->sms_info();
		//$this->payment_gateways = $this->payment_modal->get_payment_gateway();
		$this->payment_status = array(
										'pending'   => 7,
										'awaiting'  => 2,
										'success'   => 1,
										'failure'   => 3,
										'cancel'    => 4
									  );
	}	
	public function payment_gateway($id_branch,$id_pg)
	{		
		/*param_1 => key
		param_2 => salt
		param_3 => Access code for ccavenue/ Merchant code for Techpr…
		param_4 => merchant_id for ccavenue / iv for techprocess*/
		//$data = $this->payment_modal->getBranchGateways($id_branch);
		$data = $this->mobileapi_model->getBranchGatewayData($id_branch,$id_pg);  
		return $data;
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
	/**
	* General functions   
	*/	
	function IsNullOrEmptyString($str){
        return (!isset($str) || trim($str) === '');
    }
    //funtion to get post values
    function get_values()
    {
		return (array)json_decode(file_get_contents('php://input'));
	}
	// Otp message
	function otp_sms($otpStr)
	{  
		/*-- Coded by ARVK --*/
		$model = self::MOD_MOB;
		$company = $this->$model->company_details();
		$expiry = date("d-m-Y H:i:s",strtotime($otpStr['expiry']));
        $msg ="Your OTP ".$otpStr['otp']." for ".$this->comp['company_name']." purchase plan is valid till ".$expiry."";
	  //$msg ="Your OTP ".$otpStr['otp']." for ".$this->comp['company_name']." purchase plan is valid till ".$expiry.". For queries contact customer care ".$company['mobile'];//
		return $msg;
		/*-- / Coded by ARVK --*/
	}
	//check customer has active scheme_account without payment entry
	function checkNotPaidAcc($id_customer)
	{
		$model = self::MOD_MOB;
		return $this->$model->notPaidAccounts($id_customer);		
	}
	//array sorting
	function array_sort($array, $on, $order=SORT_ASC){
		$new_array = array();
		$sortable_array = array();
		if (count($array) > 0) {
			foreach ($array as $k => $v) {
				if (is_array($v)) {
					foreach ($v as $k2 => $v2) {
						if ($k2 == $on) {
							$sortable_array[$k] = $v2;
						}
					}
				} else {
					$sortable_array[$k] = $v;
				}
			}
			switch ($order) {
				case SORT_ASC:
					asort($sortable_array);
					break;
				case SORT_DESC:
					arsort($sortable_array);
					break;
			}
			foreach ($sortable_array as $k => $v) {
				//$new_array[$k] = $array[$k];
				$new_array[] = $array[$k];
			}
		}
		return $new_array;
	}
	/**
	* API functions 
	*/
	function getMetalrate_get()
	{		
		$filename = 'api/rate.txt'; 
	    $data = file_get_contents($filename);
	    $metalrates = (array) json_decode($data);
		$this->response($metalrates);	
	}
	 function getWeights_get()
	{
		$model = self::MOD_MOB;
		$weights = array();
		$weights_data = $this->$model->get_weights();
		$filename = 'api/rate.txt'; 
	    $file_data = file_get_contents($filename);
	    $metalrates = (array) json_decode($file_data);
	    foreach($weights_data as $weight)
	    {
	    	$rate = (float) $metalrates['goldrate_22ct'] * (float) $weight['weight'];
			$weights[]=array(
								'id_weight' => $weight['id_weight'],
								'weight'    => $weight['weight']		,
								'rate'      => number_format($rate,2,'.','')
							);
		}
	    $result = array('rates' => $metalrates,'weights'=>$weights);
		$this->response($result,200);
	}
	 function getSchemes_get()
	{
		$model = self::MOD_MOB;
        $schemes['schemes'] = $this->$model->get_schemesAll($this->get('id_branch'));
	  //$schemes['schemes'] = $this->$model->get_schemesAll();
		$schemes['chit_settings'] = $this->$model->get_settings();
		$this->response($schemes,200);
	}
	public function get_groups_get()
    {
        $model = self::MOD_MOB;
        $result = $this->scheme_modal->get_groups($this->get('id_scheme'));
        $this->response($result,200);
    }
	// to get visible and active true schemes
	 function getActiveSchemes_get()
	{
		$model = self::MOD_MOB;
		$id_branch = ($this->get('id_branch') == 'null' ? '':$this->get('id_branch'));
		$schemes = $this->$model->get_activeSchemes($id_branch,$this->get('id_customer'));
		$this->response($schemes,200);
	}
	/*function getScheme_get()
	{
		$model = self::MOD_MOB;
		$scheme = $this->$model->get_scheme($this->get('id_scheme'),$this->get('id_customer'));	
		$allow_unpaid =$this->scheme_modal->allowUnpaid();	//allow customer not paid single installment		
		$allow_multiple = $this->scheme_modal->allowMultipleChits(); //allow multiple chits for customer		
		$allowNewsch = $this->scheme_modal->allowNewscheme_join(); //allow New scheme join to customer 
		$unpaid = $this->scheme_modal->check_unpaid_schemes($this->get('id_customer')); 
		$unClosedAcc = $this->scheme_modal->hasUnclosedAccounts($this->get('id_customer')); 
		$result ='';
	if($allowNewsch == TRUE){
		if($allow_multiple == TRUE )
		{
			if($allow_unpaid == TRUE)
			{
				$allow_join = array('status'=> TRUE);
			}
			else
			{
				if($unpaid == TRUE)
				{
					$allow_join = array('status'=> FALSE, 'msg' => 'You can\'t join now, as you have scheme accounts without single payment, make payments for unpaid before joining new scheme' );
				}
				else
				{
					$allow_join = array('status'=> TRUE);
				}
			}
		}
		else
		{
			if($unClosedAcc == TRUE)
			{
				$allow_join = array('status'=> FALSE, 'msg' => 'You have unclosed chits, kindly contact customer care and close to join new scheme.' );
			}
			else
			{
				$allow_join = array('status'=> TRUE);
			}
		}
	}else
			{
				$allow_join = array('status'=> FALSE, 'msg' => 'Kindly visit our showroom for new scheme enrollment....' ); 
			}
		$weights = array();
		$weights_data = $this->$model->get_weights();
	    foreach($weights_data as $weight)
	    {
			$weights[]=array(
								'weight'    => $weight['weight']	
							);
		}
		$result = array('scheme' => $scheme,'allow_join' => $allow_join,'weights' => $weights);
		$this->response($result,200);
	}*/
	function getScheme_get()
	{
		$show_referral=true;
		$model = self::MOD_MOB;
		$scheme = $this->$model->get_scheme($this->get('id_scheme'),$this->get('id_customer'));	
		$cus_single=$scheme['cusbenefitscrt_type'];
		$emp_single=$scheme['empbenefitscrt_type'];
		$cus_ref_code=$scheme['cus_ref_code'];
		$emp_ref_code=$scheme['emp_ref_code'];
		if($cus_single==0 && $emp_single==0)
		{
			if($cus_ref_code!='' && $emp_ref_code!='')
			{
				$show_referral=false;
			}
			else if($cus_ref_code=='' && $emp_ref_code=='')
			{
				$show_referral=true;
			}
			else if($cus_ref_code!='' || $emp_ref_code!='')
			{
				$show_referral=true;
			}
			else{
				return $show_referral;
			}
		}
		$groups = $this->scheme_modal->get_groups($this->get('id_scheme'),$this->get('id_branch'));
		$allow_unpaid =$this->scheme_modal->allowUnpaid();	//allow customer not paid single installment		
		$allow_multiple = $this->scheme_modal->allowMultipleChits(); //allow multiple chits for customer		
		$allowNewsch = $this->scheme_modal->allowNewscheme_join(); //allow New scheme join to customer 
		$unpaid = $this->scheme_modal->check_unpaid_schemes($this->get('id_customer')); 
		$unClosedAcc = $this->scheme_modal->hasUnclosedAccounts($this->get('id_customer')); 
		$result ='';
	if($allowNewsch == TRUE){
		if($allow_multiple == TRUE )
		{
			if($allow_unpaid == TRUE)
			{
				$allow_join = array('status'=> TRUE);
			}
			else
			{
				if($unpaid == TRUE)
				{
					$allow_join = array('status'=> FALSE, 'msg' => 'You can\'t join now, as you have purchase plan without single payment, make payments for unpaid before joining new scheme' );
				}
				else
				{
					$allow_join = array('status'=> TRUE);
				}
			}
		}
		else
		{
			if($unClosedAcc == TRUE)
			{
				$allow_join = array('status'=> FALSE, 'msg' => 'You have unclosed chits, kindly contact customer care and close to join new scheme.' );
			}
			else
			{
				$allow_join = array('status'=> TRUE);
			}
		}
	}else
			{
				$allow_join = array('status'=> FALSE, 'msg' => 'Kindly visit our showroom for new scheme enrollment....' ); 
			}
		$weights = array();
		$weights_data = $this->$model->get_weights();
	    foreach($weights_data as $weight)
	    {
			$weights[]=array(
								'weight'    => $weight['weight']	
							);
		}
		$result = array('scheme' => $scheme,'show_referral' => $show_referral,'allow_join' => $allow_join,'weights' => $weights,'groups'=>$groups);
		$result['wallet_balance'] = $this->$model->wallet_balance($this->get('id_customer'));
		$this->response($result,200);
	}
	 function getCountry_get()
	{
		$model = self::MOD_MOB;
		$schemes = $this->$model->get_country();
		$this->response($schemes,200);
	}
	 function getState_get()
	{
		$model = self::MOD_MOB;
		$schemes = $this->$model->get_state($this->get('id_country'));
		$this->response($schemes,200);
	}
	 function getCity_get()
	{
		$model = self::MOD_MOB;
		$schemes = $this->$model->get_city($this->get('id_state'));
		$this->response($schemes,200);
	}
	function getAllLocations_get()
	{
		$model = self::MOD_MOB;
		$result['countries'] = $this->$model->get_country();
		$result['states'] = $this->$model->get_cityAll();
		$result['cities'] = $this->$model->get_stateAll();
		$this->response($result,200);
	}
	//to get customer schemes
	function customerSchemes_get()
	{ 
		$model = self::MOD_MOB;
		$id_branch = $this->$model->getCusBranch($this->get('id_customer'));
		//Sync Existing Data					
   	    if($this->config->item("integrationType") == 3 || $this->config->item("autoSyncExisting") == 1){
		  $syncData = $this->sync_existing_data($this->get('mobile'),$this->get('id_customer'), $id_branch,'customerSchemes');
	    }
	    // Get Branch Name and ID 
	    if($this->comp['is_branchwise_cus_reg'] == 0 && $this->comp['branchwise_scheme'] == 1){
	        $schemes['branches'] = $this->$model->branchesData(); 
	    }
		$schemeAcc = $this->$model->get_payment_details($this->get('id_customer'),"");
		$schemes['allowDelete'] = $this->scheme_modal->deleteUnpaid();
		$schemes['chits'] = $this->array_sort($schemeAcc['chits'], 'allow_pay',SORT_DESC);
		$schemes['wallet_balance'] = $this->$model->wallet_balance($this->get('id_customer'));
		$this->response($schemes,200);
	}
	function paymentHistory_get()
	{ 
		$model = self::MOD_MOB;
		$branchWiseLogin = $this->comp['branchWiseLogin'];
		// Get Branch Name and ID 
	    if($this->comp['is_branchwise_cus_reg'] == 0 && $this->comp['branchwise_scheme'] == 1){
	        $payment['branches'] = $this->$model->branchesData(); 
	    }
		$payment['payments'] = $this->$model->get_paymenthistory($this->get('mobile'),$branchWiseLogin);
		$this->response($payment,200);
	}
	//to get individual scheme detail
	function getSchemeDetail_get()
	{
		$model = self::MOD_MOB;
		$chit = $this->$model->chit_scheme_detail($this->get('id_scheme_account'));
		$this->response($chit,200);	
	}
	//To get customer detail
	function getCustomer_post()
	{
		$model = self::MOD_MOB;
		$data = $this->get_values();
		$customer = $this->$model->get_customerProfile($data['id_customer']);
		$this->response($customer,200);	
	}
	function getCustomer_get()
	{
		$model = self::MOD_MOB;
		$data = $this->get_values();
		$customer = $this->$model->get_customerProfile(15);
		$this->response($customer,200);	
	}
	//to update customer by mobile
	function updateCustomerByMobile_post()
	{
	   $result = array();
	   $model = self::MOD_MOB;
	   $data = $this->get_values();
	   $customer = array('passwd' => $this->__encrypt($data['passwd']));
	   $flag = $this->$model->update_customerByMobile($customer,$data['mobile']);  
	   if($flag)
	   {
		   	$result = array('status' => TRUE, 'msg' => 'Password updated successfully...');
	   }
	   else
	   {
	   		$result = array('status' => FALSE, 'msg' => 'Unable to proceed the request');
	   }
	   $this->response($result,200);				
	}
	//to update customer
	function updateCustomer_post()
	{
	   $result = array();
	   $model = self::MOD_MOB;
	   $data = $this->get_values();
	   $customer = array('passwd' => $this->__encrypt($data['passwd']));
	   $flag = $this->$model->update_customer($customer,$data['id_customer']);  
	   if($flag)
	   {
		   	$result = array('status' => TRUE, 'msg' => 'Password updated successfully...');
	   }
	   else
	   {
	   		$result = array('status' => FALSE, 'msg' => 'Unable to proceed the request');
	   }
	   $this->response($result,200);				
	}
	function sendFeedback_mail_post()
	{
		 $data = $this->get_values();
        $to =  $this->comp['email']; 
        // $bcc ="pavithra@vikashinfosolutions.com";
        $bcc="";
        $subject = "Reg - ".$this->comp['company_name']." customer feedback";
        $message = $this->load->view('include/emailContact',$data,true); 
        $sendEmail = $this->email_model->send_email($to,$subject,$message,$bcc,"");
        echo $sendEmail;
	} 
	function getEnqProductGrms_get()
	{
	    $result = array( "1" => "1 Gram", "2" => "2 Gram", "4" => "4 Gram", "8" => "8 Gram", "10" => "10 Gram");
		$this->response($result,200);	
	}
    function sendFeedback_post()
    {	   
        $model     = self::MOD_MOB;
        $data      = $this->get_values(); 
		$type      = gettype($data['type']); 
		if($type == "string") {  //Types Records Single	
			//$data['type'] = is_array($data['type'])== true ? $data['type'][0]:$data['type'];
	        $title = ($data['type'] == 1 ? 'Enquiry' :
					 ($data['type'] == 2 ? 'Suggestion' :
					 ($data['type'] == 3 ? 'Complaint': 
					 ($data['type']  == 4  ? 'Others':
					 ($data['type']  == 5 ? 'DTH':
					 ($data['type'] == 6 ? 'Experience Center':
					 ($data['type'] == 7 ? 'Coin Enquiry':'')))))));
	        $insData = array('mobile'       => $data['mobile'],
	                         'name'         => $data['name'],
	                         'title'  	    => $title,
	                         'id_customer'  => (isset($data['id_customer'])?$data['id_customer']:NULL),
	                         'address'      => (isset($data['address'])?$data['address']:NULL),
	                         'date_of_birth'=> (isset($data['date_of_birth'])?$data['date_of_birth']:NULL),
	                         'date_of_wed'  => (isset($data['date_of_wed'])?$data['date_of_wed']:NULL),
	                         'profession'   => (isset($data['profession'])?$data['profession']:NULL),
	                         'email'  	    => (isset($data['email'])?$data['email']:NULL),
	                         'date_add'     => date('Y-m-d H:i:s'),
	                         'type'         => $data['type'], // Feedback type
	                         'pincode'      => $data['pincode'],
	                         'enq_from'     => 2, // mobile app
	                         'comments'  	=> $data['message']);
	                         // print_r($data);exit;
	        if($data['type'] == 3 ){
	            $ticketno = $this->$model->genTicketNo();
	            $insData['ticket_no'] = $ticketno;
	        } 
	        $result = $this->$model->insCusFeedback($insData);
	        if($data['type'] == 7 && $result['status']){
	            $prodData = array(
	                            "id_enquiry"    => $result['insertID'] ,
	                            "product_name"  => $data['product_name'],
	                            "gram"          => $data['gram'],
	                            "coin_type "    => $data['coin_type'],
	                            "qty "          => $data['qty']
	                        );
	            $this->$model->insertData($prodData,'cust_enquiry_product');
	        }
		}
		else if($type == "array") //Both DTH and Experience Center
		{
		    foreach($data['type'] as $type)
			{
				     $title = ($type==5 ?'DTH':'Experience Center');
	                 $insData = array(
					         'mobile'       => $data['mobile'],
	                         'name'         => $data['name'],
	                         'title'  	    => $title,
	                         'id_customer'  => (isset($data['id_customer'])?$data['id_customer']:NULL),
	                         'address'      => (isset($data['address'])?$data['address']:NULL),
	                         'date_of_birth'=> (isset($data['date_of_birth'])?$data['date_of_birth']:NULL),
	                         'date_of_wed'  => (isset($data['date_of_wed'])?$data['date_of_wed']:NULL),
	                         'profession'   => (isset($data['profession'])?$data['profession']:NULL),
	                         'email'  	    => (isset($data['email'])?$data['email']:NULL),
	                         'date_add'     => date('Y-m-d H:i:s'),
	                         'type'         => $type, // Feedback type
	                         'pincode'      => $data['pincode'],
	                         'enq_from'     => 2, // mobile app
	                         'comments'  	=> $data['message']
							 );
	              $result = $this->$model->insCusFeedback($insData);
			}
			if(count($data['type'])>1)
			{
				$records_detalis    ='DTH and Experience Center';
				$insData['title']   = $records_detalis;
			}
		}
        if($result['status'] == true){
            $to = $this->comp['email']; 
            //  $cc = array("pavithra@vikashinfosolutions.com");	
            $cc = "";
            $subject = "Reg - ".$this->comp['company_name']." customer enquiry"; 
            $message = $this->load->view('include/emailContact',$insData,true); 
            $sendEmail = $this->email_model->send_email($to,$subject,$message,$cc,""); 
            $this->response(array('status' => true ,'msg' => 'Thanks for contacting us.Your ref no is - '.$ticketno));
        } else {
            $this->response(array('status' => false ,'msg' => 'Please try after sometime'));
		} 
    }  
	function sendVendorEnquiry_post()
	{
        $model = self::MOD_MOB;
        $data = $this->get_values(); 
		$to = $this->comp['email'];  
	//	$to = "hari@vikashinfosolutions.com";
	//	$bcc ="hari@vikashinfosolutions.com";
		$bcc ="";
		$subject = "Reg - ".$this->comp['company_name']." vendor enquiry"; 
		$message = $this->load->view('include/emailVendorEnquiry',$data,true); 
		$sendEmail = $this->email_model->send_email($to,$subject,$message,$bcc);
		//echo $sendEmail;
		if($sendEmail == 1){
		   $this->response(array('status' => true ,'msg' => 'Thanks for your Vendor Enquiry'));
        } else {
            $this->response(array('status' => false ,'msg' => 'Please try after sometime'));
        }
	}
	 //to update profile
	function updateProfile_post()
	{
		$result = array();
		$model = self::MOD_MOB;
		$data = $this->get_values(); 	 
		$id_customer = $data['id_customer'];
		$myDate = date('Y-m-d H:i:s');
		$customer = array(
							'firstname' => ucwords($data['firstname']),
							'lastname'  => ucwords($data['lastname']),
							'title'  	    =>$data['title'],
							'email' 	=> strtolower($data['email']),
							'pan' 		=> $data['pan'],
							'date_of_birth'	=> $data['date_of_birth'],
							'date_of_wed'	=> $data['date_of_wed'],
							'nominee_name'  => isset($data['nominee_name']) ? $data['nominee_name'] : NULL,
							'nominee_relationship'  => isset($data['nominee_relationship']) ? $data['nominee_relationship'] : NULL,
							'nominee_mobile'  => isset($data['nominee_mobile']) ? $data['nominee_mobile'] : NULL,
							'nominee_address1'  => isset($data['nominee_address1']) ? $data['nominee_address1'] : NULL,   
							'nominee_address2'  => isset($data['nominee_address2']) ? $data['nominee_address2'] : NULL,   
						);
//print_r($customer);exit;
		$customer['date_upd'] = $myDate;		
		$address = array(
							'address1' 	  => ucfirst($data['address1']),
							'address2' 	  => ucfirst($data['address2']),
							'id_country'  => $data['id_country'],
							'id_state'    => $data['id_state'],
							'id_city' 	  => $data['id_city'],
							//'city' 	  => $data['city'],
							'pincode' 	  => $data['pincode']
						);	
		$cflag = $this->$model->update_customer($customer,$id_customer);  	
//print_r($cflag);exit;
		$isExists = $this->$model->isAddressExist($id_customer);  
		if($isExists)
		{
			$address['date_upd'] = $myDate;				
			$aflag = $this->$model->update_customerAdd($address,$id_customer);  			
		}	
		else
		{ 
		  $address['id_customer'] = $id_customer;
		  $address['date_add'] = $myDate;				
		  $aflag = $this->$model->insert_customerAdd($address);  	
		}
		if($cflag)
		{
			 $result = array('status'=> TRUE,'msg' => "Your profile updated successfully");						
		}
		else
		{
			 $result = array('status' => FALSE, 'msg' => 'Unable to proceed the request');
		}
		$this->response($result,200);	
	}
	function getDashboard_get()
	{
		$model = self::MOD_MOB;
		$customer = $this->$model->get_customer_dashboard($this->get('id_customer'));
		$customer['showcomingsoon']=0; // to show or hide scheme account and payment block		
		$this->response($customer,200);	
	} 
	function getMatchingCountry_get()
	{
	  	$model = self::MOD_MOB;
		$countries = $this->$model->getMatchingCountry($this->get('query'));
		$this->response($countries,200);	
	}
	function getMatchingState_get()
	{
	  	$model = self::MOD_MOB;
		$states = $this->$model->getMatchingState($this->get('query'),$this->get('id_country'));
		$this->response($states,200);	
	}
	function getMatchingCity_get()
	{
	  	$model = self::MOD_MOB;
		$cities = $this->$model->getMatchingCity($this->get('query'),$this->get('id_state'));
		$this->response($cities,200);	
	}
	//to check the mobile number already registered
	function isNumberRegistered_get()
	{
		$model = self::MOD_MOB;
		$mobile = $this->get('mobile');    
		$email = $this->get('email');    
		if(strlen($mobile) != 10 || !is_numeric($mobile)){
		    $result = array(
							'mobile' => $mobile,
							'is_reg' => TRUE,
							'msg'	 => "Enter 10 digit mobile number"
						);
		    $this->response($result, 200);
		}
		
	    $m_exist =	$this->$model->isMobileExists($mobile);	
	    $e_exist =	( $this->config->item('isCusEmailReq') == 0 ? ($email != '' ? $this->$model->clientEmail($email) : FALSE) : $this->$model->clientEmail($email));	
	    $limit= $this->services_modal->limitDB('get','1');
		$count= $this->services_modal->customer_count();
		$limit_exceed = 0;
		if($limit['limit_cust']==1 && $count >= $limit['cust_max_count'])
		{
			$limit_exceed = 1;
		}
		$is_reg = ($m_exist ? TRUE : ($e_exist ? TRUE : FALSE));
		$result = array(
							'mobile' => $mobile,
							'is_reg' => $is_reg,
							'msg'	 => ($is_reg ? ($m_exist && $e_exist)?"Mobile and Email already registered":($m_exist ?"Mobile already registered":"E-mail already Registered" ): ($limit_exceed == 1 ? 'Temporarily New user registration is unavailable, Kindly contact Customer care...' : FALSE))
						);
		$this->response($result, 200);
	}
	//to check the mobile registered
	function checkMobileReg_get()
	{
		$model = self::MOD_MOB;
		$mobile = $this->get('mobile');    
	    $status =	$this->$model->isMobileExists($mobile);	
	   	$result = array(
							'mobile' => $mobile,
							'is_reg' =>	($status ? $status : FALSE)
				);
		$this->response($result, 200);
	}
	function get_invoiceData($payment_no,$mobile)
	{
		$records = array();
		$query_invoice = $this->db->query("SELECT pay.id_scheme_account as id_scheme_account, sch_acc.ref_no as scheme_acc_number, DATE_FORMAT(pay.date_payment,'%d-%m-%Y') as date_payment, sch.scheme_name as scheme_name, pay.payment_amount as payment_amount,cus.firstname as firstname, cus.lastname as lastname, cus.mobile, addr.address1 as address1,email,if(payment_mode='CC','Credit Card',if(payment_mode='NB','Net Banking',if(payment_mode='CD','Cheque or DD',if(payment_mode='CO','Cash Pick Up',if(payment_mode='OP','Other',''))))) as payment_mode,trans_id,payment_ref_number,pay.receipt_no,bank_name,bank_acc_no,bank_branch,metal_weight,scheme_type
							FROM payment as pay
							LEFT JOIN scheme_account sch_acc ON sch_acc.id_scheme_account = pay.id_scheme_account
							LEFT JOIN scheme sch ON sch.id_scheme = sch_acc.id_scheme
							LEFT JOIN customer as cus ON cus.id_customer = sch_acc.id_customer
							LEFT JOIN address as addr ON addr.id_customer = cus.id_customer WHERE id_payment = '".$payment_no."' AND mobile='".$mobile."'");
		if($query_invoice->num_rows() > 0)
			{
				foreach($query_invoice->result() as $row)
				{
					$records[] = array('id_scheme_account' => $row->id_scheme_account,'scheme_acc_number' => $row->scheme_acc_number,'date_payment' => $row->date_payment,'scheme_name' => $row->scheme_name, 'payment_amount' => $row->payment_amount,'firstname' => $row->firstname,'lastname' => $row->lastname, 'id_payment' => $payment_no,'address1' => $row->address1,'email' => $row->email,'mobile' => $row->mobile,'payment_mode' => $row->payment_mode,'trans_id' => $row->trans_id,'payment_ref_number' => $row->payment_ref_number,'receipt_no' => $row->receipt_no,'bank_name' => $row->bank_name,'bank_acc_no' => $row->bank_acc_no,'bank_branch' => $row->bank_branch,'metal_weight' => $row->metal_weight,'scheme_type' => $row->scheme_type);
				}
			}
			return $records;
	}
	//to validate user login
	function authenticate_post()
	{
		$model = self::MOD_MOB;
		$sch_model = self::SCH_MOD;
		$data = $this->get_values();
		$status = $this->$model->isValidLogin($data['mobile'],$this->__encrypt($data['passwd']));
		$result = array(
						   'mobile'   => $data['mobile'],
						   'is_valid' => ($status ? $status : FALSE)
						);
		 if($result['is_valid'] === TRUE)
		 {
		 	$result['customer']        = $this->$model->get_customerByMobile($data['mobile']);
			$result['accounts']        = $this->$model->countSchemes($result['customer']['id_customer']);
			$result['payments']        = $this->$model->countPayments($result['customer']['id_customer']);
			$result['wallet']          = $this->$model->countWallets($result['customer']['id_customer']);
			$result['notification']    = $this->$model->get_cus_noti_settings($result['customer']['id_customer']);
			if($data['token'] != 'null'){
			//insert device token , uuid , device type
			$device_data = array(
								'token'		  => $data['token'],
								'uuid'		  => $data['uuid'],
								'device_type' => $data['device_type'],
								'id_customer' => $result['customer']['id_customer']
								);
			$insertData = $this->$model->update_deviceData($device_data,$device_data['id_customer']);
			//print_r($this->db->last_query());exit;
		   }
		 }	
		$id_branch   = (isset($result['customer']['id_branch']) ? $result['customer']['id_branch'] :0);
		$currency = $this->$model->get_currency($id_branch);
		$result['currency'] = $currency;
		//Sync Existing Data					
   	    if($this->config->item("integrationType") == 3 || $this->config->item("autoSyncExisting") == 1){
		  $syncData = $this->sync_existing_data($data['mobile'],$result['customer']['id_customer'], $id_branch,'authenticate');
	    }
		$this->response($result, 200);
	}
	//to generate otp and send message to customer
	/*function generateOTP_get()
	{			
		$model = self::MOD_MOB;
		$otp   = $this->$model->generateOTP();
		$this->$model->send_sms($this->get('mobile'),$this->otp_sms($otp));
		$this->response($otp,200);		
	}*/
	function generateOTP_get()
	{			
		$model = self::MOD_MOB;
		$email=$this->get('email');
		$otp   = $this->$model->generateOTP();
		if($email!=''&& $email!='undefined')
		{
			$to = $email;
			$data['otp']=$otp['otp'];
			$data['type'] = 4;
			$data['company_details'] = $this->comp;
				$data['name'] = $mail['firstname'];
				$subject = "Reg: ".$this->comp['company_name']." Purchase plan";
				$message = $this->load->view('include/emailAccount',$data,true);
				$this->load->model('email_model');
				$sendEmail = $this->email_model->send_email($to,$subject,$message,"","");
				if($this->config->item('sms_gateway') == '1'){
        		    $this->sms_model->sendSMS_MSG91($this->get('mobile'),$this->otp_sms($otp),'','');		
        		}
        		elseif($this->config->item('sms_gateway') == '2'){
        	        $this->sms_model->sendSMS_Nettyfish($this->get('mobile'),$this->otp_sms($otp),'trans');	
        		}
        		if($service['serv_whatsapp'] == 1)
        		{
            	    $this->services_modal->send_whatsApp_message($this->get('mobile'),$message);
                }
				$this->response($otp,200);		
		}
		else
		{
		$mail= $this->$model->forgetUser($this->get('mobile'));
		$to = $mail['email'];
		$data['otp']=$otp['otp'];
		$data['type'] = 0;
		$data['company_details'] = $this->comp;
		$data['name'] = $mail['firstname'];
		$subject = "Reg: ".$this->comp['company_name']." purchase plan forgot password";
		$message = $this->load->view('include/emailAccount',$data,true);
		$this->load->model('email_model');
		$sendEmail = $this->email_model->send_email($to,$subject,$message,"","");
		if($this->config->item('sms_gateway') == '1'){
		    $this->sms_model->sendSMS_MSG91($this->get('mobile'),$this->otp_sms($otp),'','');		
		}
		elseif($this->config->item('sms_gateway') == '2'){
	        $this->sms_model->sendSMS_Nettyfish($this->get('mobile'),$this->otp_sms($otp),'trans');	
		}
		if($service['serv_whatsapp'] == 1)
        {
        	    $this->services_modal->send_whatsApp_message($this->get('mobile'),$message);
        }
		$this->response($otp,200);
		}
	}
	//check otp from db
	function verifyOTP_post()
	{
		$model = self::MOD_MOB;
		$result ="";
		$data = $this->get_values();		
		$last_otp = $this->$model->get_lastOTP($data['id_customer']);
		$otp_time = date('Y-m-d H:i:s');
		if($data['otp'] == $last_otp['last_generated_otp'])
		{
			if($otp_time <= date('Y-m-d H:i:s',strtotime($last_otp['last_otp_expiry'])))
			{
				$result =  array('is_valid' => TRUE, 'msg' => 'Success');
			}
			else
			{
				$result = array('is_valid' => FALSE, 'msg' => 'Your OTP expired');
			}
		}
		else
		{
			$result =  array('is_valid' => FALSE, 'msg' => 'Your OTP is invalid');
		}
		$this->response($result,200);			    				
	}
	//Check registration otp
	function check_regOTP_post()
	{
		$data = $this->get_values();	
		$otp_time = date('Y-m-d H:i:s');
		if($data['sysotp'] == $data['userotp'])
		{
			if($otp_time <= date('Y-m-d H:i:s',strtotime($data['last_otp_expiry'])))
			{
				$result =  array('is_valid' => TRUE, 'msg' => 'Success');
			}
			else
			{
				$result = array('is_valid' => FALSE, 'msg' => 'Your OTP expired');
			}
		}	
		else
		{
			$result =  array('is_valid' => FALSE, 'msg' => 'Your OTP is invalid');
		}
		$this->response($result,200);
	}
	//change password
	function resetPassword_post()
	{
		$model = self::MOD_MOB;
		$data = $this->get_values();
		$result ="";
		$status = $this->$model->update_customer(array('passwd' => $this->__encrypt($data['passwd'])),$data['id_customer']);
		if($status)
		{
			$result = array( "status" =>TRUE,"msg" => "Your password updated successfully");
		}
		else
		{
			$result = array( "status" =>FALSE,"msg" => "Unable to proceed your request");
		}
		$this->response($result,200);		
	}
    //Register customer	
	function createCustomer_post()
	{
		$model = self::MOD_MOB;
		$data = $this->get_values();
		$no_deviceData = FALSE;
		$result ='';
		$isBranchReq = $this->$model->isBranchWiseReg();   // Branch is required For reg if- branch settings enabled //
        if($isBranchReq){
            if($this->IsNullOrEmptyString($data['id_branch'])){
                $result = array( "status" =>FALSE, "msg" => "Branch is required.");
                $this->response($result,200);
            }
        }
		$customer = array(
						'info'=>array( 
							'firstname' => ucwords($data['firstname']),
							'lastname'  => ucfirst($data['lastname']),
							'mobile'    => $data['mobile'],
							'title' => (isset($data['title'])?$data['title']:NULL),
							'email'     => $data['email'],
							'passwd'    => $this->__encrypt($data['passwd']),
							'active'    => 1,
						    'date_add'  => date('Y-m-d H:i:s'),
						    'added_by'  => 2,
							'id_branch' => (isset($data['id_branch'])?$data['id_branch']:NULL)
							),
							'address'=>array(
								'address1'			=>	(isset($data['address1'])?$data['address1']:NULL),
								'address2'			=>	(isset($data['address2'])?$data['address2']:NULL)
							)
						 );
		$status = $this->$model->insert_customer($customer);	
		if($status['status']==TRUE)
		{
			//Sync Existing Data 	
			if($this->config->item("integrationType") == 5){ // Do customer registration in offline
               $this->load->model("integration_model");
               $postData = array(
        						"appCustomerCode"	=> 0 ,
        						"custName" 			=> ucfirst($this->input->post('firstname')),
        						"mobileNo" 			=> trim($this->input->post('mobile')),
        						"emailId" 			=> $this->input->post('email'),
        						"preferdBranch" 	=> "",
        						"branchCode" 		=> "",
        						"custdetails" 		=> array("city" => "Coimbatore" , "state" => "Tamil Nadu")
        					);
               $response = $this->integration_model->khimji_curl('registerCustomerWithoutValidateOtp',$postData);
               if($response['status'] == 1){
                   	$resData = $response['data'];
                   	if($resData->status == 1 && $resData->errorCode == 0 && $resData->result[0]->mobileNo == $this->input->post('mobile')){
                       $updCus = array("reference_no" => $resData->result[0]->customerCode, "date_upd" => date('Y-m-d H:i:s'));
                       $this->integration_model->updateData($updCus,'mobile',$resData->result[0]->mobileNo,'customer');
                    }else{
    					if (!file_exists('log/khimji')) {
    		                mkdir('log/khimji', 0777, true);
    		            }
    		            $log_path = 'log/khimji/'.date("Y-m-d").'.txt';  
    		            $logData = "\n".date('d-m-Y H:i:s')."\n API : registerCustomerWithoutValidateOtp \n Response : ".json_encode($resData,true);
    				    file_put_contents($log_path,$logData,FILE_APPEND | LOCK_EX);
    				}
               }
            }
	   	    else if($this->config->item("integrationType") == 3|| $this->config->item("autoSyncExisting") == 1){
				  $syncData = $this->sync_existing_data($data['mobile'],$status['insertID'],$customer['info']['id_branch'],'createCustomer');
			} 
			
			$wallet_acc =  $this->registration_model->wallet_accno_generator(); 
			if($wallet_acc['wallet_account_type']==1){
				$this->wallet_account_create($status['insertID'],$data['mobile']);
				}
			$id = $status['insertID'];
			$deviceData = array(
						'token'		  => $data['token'],
						'uuid'		  => $data['uuid'],
						'device_type' => $data['device_type'],
						'id_customer' => $id
						);
		if($data['token'] != null){
			$insStatus = $this->$model->insert_deviceData($deviceData);	
			if($insStatus){
				 $cus = array('notification' => 1);
	 			 $flag = $this->$model->update_customer($cus,$id);  
			}
		}
		else{
			$insStatus['status'] = FALSE ;
			$no_deviceData = TRUE;
		}
			if($insStatus['status']==TRUE || $no_deviceData = TRUE){
			  $serviceID = 1;
			  $company = $this->$model->company_details();
			  $service = $this->services_modal->checkService($serviceID);
				if($service['sms'] == 1)
				{
					$data =$this->services_modal->get_SMS_data($serviceID,$id);
					$mobile =$data['mobile'];
					$message = $data['message'];
					if($this->config->item('sms_gateway') == '1'){
            		    $this->sms_model->sendSMS_MSG91($mobile,$message,'',$service['dlt_te_id']);		
            		}
            		elseif($this->config->item('sms_gateway') == '2'){
            	        $this->sms_model->sendSMS_Nettyfish($mobile,$message,'trans');	
            		}
				}
				if($service['serv_whatsapp'] == 1)
				{
                   $this->services_modal->send_whatsApp_message($mobile,$message);
                }
				if($service['email'] == 1 && $customer['info']['email'] != '')
				{
					$to =$customer['info']['email'];
					$data['name'] = $customer['info']['firstname'];
					$data['mobile'] = $customer['info']['mobile'];
					$data['passwd'] = $this->__decrypt($customer['info']['passwd']);
					$data['company_details']=$company;
					$data['type'] = 3;
					$subject = "Reg: ".$this->comp['company_name']." purchase plan registration";
					$message = $this->load->view('include/emailAccount',$data,true);
					$sendEmail = $this->email_model->send_email($to,$subject,$message,"","");
				}
				$result = array('status'=> TRUE, 'msg'=>'Your number '.$data['mobile'].' registered successfully.'); 				
			}
			else
			{
				$result = array( "status" =>FALSE, "msg" => "Unable to proceed your request,please try again..");			
			}
		}
		else
		{
			$result = array( "status" =>FALSE, "msg" => "Unable to proceed your request");			
		}	
		$this->response($result,200);		 
	}
	//get currency info
	function currency_get()
	{
		$model = self::MOD_MOB;
		$id_branch = $this->get('id_branch');
		$currency = $this->$model->get_currency($id_branch);
		$this->response($currency, 200);	
	}	
	//company info
	function company_get()
	{
		$model = self::MOD_MOB;
		$company = $this->$model->company_details();
		$company['facebook'] = 'https://www.facebook.com/sriammanjewellers/';
		$company['instagram'] = 'https://www.instagram.com/sriammanjewellerstirupur/?hl=en';
		$company['youtube'] = 'https://www.youtube.com/channel/UCBC8TM4j5F2uhCfA_sL37oA/about';
		
		$this->response($company, 200);	
	}	
	//Wallet
	function get_customerWallets_get()
	{
		$model = self::MOD_MOB;
		$id_customer = $this->get('id_customer');    
		$id_wal_trans = $this->get('lastIdWalTrans');    
	    $wallets =	$this->$model->get_wallet_accounts($id_customer);	
	   $balance   = ($wallets[0]['wallet_balance_type'] == 0 ? $this->comp['currency_symbol'].' '.$wallets[0]['balance'] : $wallets[0]['balance']);
	    $transactions =	$this->$model->get_wallet_transactions($id_customer,$id_wal_trans);	
		$result = array('wallets' => $wallets,'balance' => $balance,'transactions' => $transactions);
		$this->response($result, 200);	
	}	
	//Delete scheme Account
	function deleteScheme_get()
	{
		//$model = self::MOD_MOB;
		$model = self::SCH_MOD;
		$id_scheme_account = $this->get('id_scheme_account');    
	    $status = $this->$model->delete_scheme_account($id_scheme_account);
	    if($status['status']=='1')
		 {
		 	$result=array('status'=>true,'msg'=>'Your purchase plan deleted successfully');
	     }
	    else{
	    	$result=array('status'=>false,'msg'=>'Unable to proceed your request');
		  }
		$this->response($result, 200);	
	}	
	function set_maturity_date($no_of_months, $date){
    	$year_month = Date("Y-m", strtotime($date));
    	$year_month_incremented = Date("Y-m", strtotime($year_month . " +".$no_of_months." Month "));
    	$month_end_dt = strtotime('last day of this month', strtotime($year_month_incremented));
    	return date('Y-m-d', $month_end_dt);
    }

    function get_entrydate($id_branch)
	{
	    if($id_branch > 0){
            $sql = "SELECT entry_date as custom_entry_date,cs.edit_custom_entry_date FROM ret_day_closing 
    		join chit_settings cs 
    		".($id_branch!='' ?" WHERE id_branch=".$id_branch."" :'')."";
    	     $result=$this->db->query($sql);
    	     return $result->row_array();
	    }else{
	        return date("Y-m-d H:i:s");
	    }
	}
	
/*	function get_entrydate($id_branch)
	{
	    if($id_branch > 0){
            $sql = "SELECT entry_date as custom_entry_date,cs.edit_custom_entry_date FROM ret_day_closing 
    		join chit_settings cs 
    		".($id_branch!='' ?" WHERE id_branch=".$id_branch."" :'')."";
    	     
	    }
    	     else if($id_branch==0)
    	     {
    	         $sql = "SELECT entry_date as custom_entry_date,cs.edit_custom_entry_date FROM ret_day_closing 
    		join chit_settings cs ";
    	
    	     }
    	    
	    else{
	        return date("Y-m-d H:i:s");
	    }
	    $result=$this->db->query($sql);
    	      return $result->row_array();
	}*/
    
	//join scheme
	function createAccount_post()
	{
		$model = self::MOD_MOB;
		$data = $this->get_values();		
    	$flag = FALSE;
		$scheme_acc_number  ='';
		$result ='';
		$is_referral_by = NULL;
        $cus = $this->$model->get_customerByID($data['id_customer']); 
        $settings = $this->$model->is_branchwise_cus_reg(); 
		if($settings['branch_settings']==1)
		{	
			if($settings['is_branchwise_cus_reg']==1)
			{
				$id_branch  = $cus['id_branch'];
			}
			else
			{
				$id_branch  = (isset($data['id_branch'])?$data['id_branch']:NULL);
			}
		}
		else{
			$id_branch =NULL;
		}
		$schDetail = $this->$model->get_scheme($data['id_scheme'],$data['id_customer']);
        // 	1 - Flexible[Can pay installments and close], 2 - Fixed Maturity, 3 - Fixed Flexible[Increase maturity if has Default]
        $maturity_date = ( $schDetail['maturity_type'] == 2 ? ($schDetail['maturity_days'] > 0 ? date('Y-m-d', strtotime(date('Y-m-d'). '+'.$schDetail['maturity_days'].' days')) : NULL) : ($schDetail['maturity_type'] == 3 ? date('Y-m-d', strtotime(date('Y-m-d'). '+'.$schDetail['total_installments'].' months')) : NULL ) );
 
        $entry_date = $this->get_entrydate($id_branch); // Taken from ret_day_closing  table branch wise //HH
		$custom_entry_date = $entry_date['custom_entry_date'];
 
    //  print_r($settings);exit;
		$schAcc = array(
						 'id_customer'       => $data['id_customer'],
						 'id_scheme'         => $data['id_scheme'],
						 'start_date'        => date('Y-m-d H:i:s'),
						 'maturity_date'     => $maturity_date,
						 'group_code'        => $data['group_code'],
						 'scheme_acc_number' =>	($data['is_new'] == 'N' ? $data['scheme_acc_number'] : NULL),
						 'account_name'      => ucwords($data['account_name']),
						 'is_new'		     => $data['is_new'],
						 'active'            => 1,
						 'date_add'          => date('Y-m-d H:i:s'),
						 'added_by' 		 => 2,
						 'id_branch'         => $id_branch,
				 //		 'custom_entry_date' => isset($settings['custom_entry_date'])?$settings['custom_entry_date']:NULL,
						 'custom_entry_date' => ($custom_entry_date ? $custom_entry_date:NULL),
						 
						// "is_referral_by" 	 => $is_referral_by,	
						'firstPayment_amt'  =>(isset($data['payable']) ?$data['payable']:NULL),
						 'referal_code' 	 => (isset($data['referal_code'])?$data['referal_code']:NULL),
						 'pan_no'		     => ($data['pan_no'] != null ? strtoupper($data['pan_no']): NULL)	
						);	
		if(isset($data['is_new']) &&  $data['is_new'] =='N')
		{  
			if($data['regExistingReqOtp'] == 0){
				$schAcc['id_scheme_group'] = (!empty($data['id_scheme_group'])?$data['id_scheme_group']:'');
				$isAccNoExist = $this->scheme_modal->verify_existing($schAcc);
				if(!$isAccNoExist)
				{
					$scheme_acc  = array("id_scheme" => ($data['id_scheme']!=''?$data['id_scheme']:0),
					                	"id_customer" =>  $data['id_customer'],
					                	"scheme_acc_number" => ($data['scheme_acc_number']!=''? $data['scheme_acc_number']:NULL),
					                	"ac_name" => ($data['account_name']!=''?$data['account_name']:NULL),
										 "id_branch" => $id_branch,
					                	"id_employee" => NULL,
					                	'added_by' 		 => 2,
					                	"date_add" => date('Y-m-d H:i:s'),
					                	"id_scheme_group" => (isset($data['id_scheme_group'])?$data['id_scheme_group']:NULL),
					                	'pan_no'		     => ($data['pan_no'] != null ? strtoupper($data['pan_no']): NULL),	
					                	"status" => 0 // processing
					             	);
				  $status =	$this->scheme_modal->join_existing($scheme_acc);  
				  if($status['status'])
				  {
				  	$result = array( "status" =>TRUE, "msg" => 'Kindly wait for your scheme activation, once activated you will be notified');
				  }
				  else
				  {
				  	$result = array( "status" =>FALSE, "msg" => 'Unable to proceed your request..');			
				  }			
				}
				else
				{
					if($isAccNoExist['table'] == 'scheme_account'){
						$msg = 'Your account already exist, Please contact customer care to proceed your request';
					}else{
						$msg = 'You have already sent request for this account... Please wait until we verify your account. Check dashboard for status.';
					}
					$result = array( "status" =>FALSE, "msg" => $msg);			
				}
			}else{
				$result = $this->join_existing_byacc($schAcc);
			}					
			$this->response($result,200);	
		}else{ // New account joining
            $this->db->trans_begin();
            if($data['referal_code']!='')
			{ 		
				if($data['referal_code']!= $data['mobile'])
				{
					$referral = $this->scheme_modal->checkreferral_code($data['referal_code'],$data['id_customer']);
					if($referral['status'] == false)
					{												
						$this->db->trans_rollback();
						$result =array('status'=>FALSE,'msg'=> $referral['msg']);
						$this->response($result,200); 
					}
					else
					{
						$is_referral_by = (strtoupper($referral['is_referral_by']) == 'CUS' ? 0 :(strtoupper($referral['is_referral_by']) == 'EMP'?1:NULL));
					}	
				}
				else
				{
					$result =array('status'=>FALSE,'msg'=>'Invalid Referral code');
						$this->response($result,200); 
				}
			}
			if($data['agent_code']!='')
			{ 	
			    $agent_ref = $this->scheme_modal->verifyagent_code($data['agent_code']);
			    if($agent_ref == 0)
			    {
			        $result =array('status'=>FALSE,'msg'=>'Invalid Agent code');
					$this->response($result,200); 
			    }
			    else{
			        $schAcc['id_agent'] = $agent_ref['id_agent'];
			        $schAcc['agent_code'] = $agent_ref['agent_code'];
			    }
			}
            $schAcc['is_refferal_by'] = $is_referral_by;
            $status = $this->$model->insert_schemeAcc($schAcc);
            $flash_msg = '';
            if($status['sch_data']['free_payment'] == 1)
            {
                $pay_insert_data = $this->mobileapi_model->free_payment_data($status['sch_data'],$status['insertID']);
                if($status['sch_data']['receipt_no_set'] == 1){
                    $pay_insert_data['receipt_no'] = $this->generate_receipt_no();
                }
                $pay_add_status = $this->payment_modal->addPayment($pay_insert_data);
                $flash_msg = 'As Free Installment offer, 1st installment of your scheme credited successfully. Kindly pay your 2nd installment';  
                $scheme_acc_no=$this->$model->accno_generatorset();
                $schAcc['id_branch']=$branch;
                if($scheme_acc_no['status']==1 && $scheme_acc_no['schemeacc_no_set']==0)
                {
                      $ac_group_code = NULL;
    										// Lucky draw
											if($schDetail['is_lucky_draw'] == 1 ){ // Based on scheme settings 
												// Update Group code in scheme_account table 
												$updCode = $this->payment_modal->updateGroupCode($schAcc); 
												$ac_group_code = ($updCode['status'] ? $updCode['group_code'] : NULL);
											}
                      $scheme_acc_number=$this->$model->account_number_generator($schAcc['id_scheme'],$branch,$ac_group_code);
                    if($scheme_acc_number!=NULL && $scheme_acc_number !="Not Allocated")
                    {
                        $updateData['scheme_acc_number']=$scheme_acc_number;
                    }
                    $updSchAc = $this->$model->update_account($updateData,$status['insertID']);
                }
            }
            if($this->db->trans_status()===TRUE)
            {
                $this->db->trans_commit();
                $schData1 = $this->$model->get_schemeaccount_detail($status['insertID']);
                $this->load->model("scheme_modal");
                $schData = $this->scheme_modal->getJoinedScheme($status['insertID']);
                $serviceID = 2;
                $service = $this->services_modal->checkService($serviceID);
                $company = $this->$model->company_details();	
                if($service['sms'] == 1)
                {
                    $id=$status['insertID'];
                    $data =$this->services_modal->get_SMS_data($serviceID,$id);
                    $mobile =$data['mobile'];
                    $message = $data['message'];
                    if($this->config->item('sms_gateway') == '1'){
            		    $this->sms_model->sendSMS_MSG91($mobile,$message,'',$service['dlt_te_id']);		
            		}
            		elseif($this->config->item('sms_gateway') == '2'){
            	        $this->sms_model->sendSMS_Nettyfish($mobile,$message,'trans');	
            		}
                }
                if($service['email'] == 1 && isset($schData[0]['email']) && $schData[0]['email'] != '')
                {
                    $data['schData'] = $schData[0];
                    $data['company'] =$company;
                    $data['type'] = 1;
                    $to = $schData[0]['email'];
                    $subject = "Reg.  ".$this->comp['company_name']." scheme joining";
                    $message = $this->load->view('include/emailScheme',$data,true);
                    $sendEmail = $this->email_model->send_email($to,$subject,$message,"","");
                }
                $result = array('status'=> TRUE, 'msg'=>'Purchase plan added to your account.','chit' =>$schData1[0],'free_pay' =>$flash_msg); 
            }
            else{
                $this->db->trans_rollback();
                $result = array( "status" =>FALSE, "msg" => "Unable to proceed your request");	
            }	
        }
		$this->response($result,200);		 				
	}
	function generate_receipt_no()
	{
		$rcpt_no = "";
		$rcpt = $this->payment_modal->get_receipt_no();
		if($rcpt!=NULL)
		{
		  	$temp = explode($this->comp['short_code'],$rcpt);
			 	if(isset($temp))
			 	{
					$number = (int) $temp[1];
					$number++;
					$rcpt_no =$this->comp['short_code'].str_pad($number, 6, '0', STR_PAD_LEFT);
				}		   
		}
		else
		{
			 	$rcpt_no =$this->comp['short_code']."000001";
		}
		return $rcpt_no;
	}
/* / Coded by ARVK*/
	//chit payment
    function chitPayment_post()
    {
        $payData = $this->get_values();	
	    $pay_flag = FALSE;
		$model = self::MOD_MOB;
           $rcvdAmount = (float) $payData['amount'];
			if(isset($payData))
			{ 
				if( $payData['scheme_type']===1)
			   {
			   	  $metal_rate = $this->$model->getMetalRate();	
			      $gold_rate = (float) $metal_rate['goldrate_22ct'];
			      $actAmount = $gold_rate * $payData['udf2'];			      
			      $pay_flag =  ($rcvdAmount >= $actAmount? TRUE :FALSE);
			   }	
			   else
			   {
			   	 $chit = $this->$model->get_schemeByChit($payData['udf1']);			  
			   	 $pay_flag =  ($rcvdAmount >= $chit['amount']? TRUE :FALSE);
			   }
			}
			   if((isset($payData) && $payData['udf1']!='' && $payData['amount'] && $pay_flag == TRUE) ||(isset($_POST)&& $_POST['txnid'] && $_POST['udf1']))//cross check received and actual amount
			   {	
	    	if(isset($payData) && $payData['udf1']!='' )
	    	{  
	    	      //generate unique id
	    		  $txnid =uniqid('VSD');
	    		  //set insert data
					$insertData = array(
								"id_scheme_account"	 => (isset($payData['udf1'])? $payData['udf1'] : NULL ),
								"payment_amount" 	 => (isset($payData['amount'])? $payData['amount'] : NULL ), 
								"bank_charges" 	     => (isset($payData['udf5']) && $payData['ud5']!='' ?$payData['udf5'] : 0.00), 
								"date_payment" 		 => date('Y-m-d H:i:s'),
								"metal_rate"         => (isset($payData['udf3']) && $payData['udf3'] !='' ? $payData['udf3'] : 0.00),
								"metal_weight"       => (isset($payData['udf2']) && $payData['udf2'] !='' ? $payData['udf2'] : 0.000),
								"trans_id"           => (isset($txnid) ? $txnid : NULL),
								"added_by"			 =>  2,
								"payment_status"     => -1 //status - 0 (pending), will change to 1 after approved at backend
							);
							//inserting pay_data before gateway
							$payment = $this->$model->insert_payment($insertData);
				}
			$data =	array (
							'key' 			=>  $this->config->item('key'), 
							'txnid' 		=> $txnid, 
							'amount' 		=> (isset($payData['amount'])   ? $payData['amount']:''),
							'firstname' 	=> (isset($payData['firstname'])? $payData['firstname'] :''),
							'lastname' 		=> (isset($payData['lastname']) ? $payData['lastname']:''),
						    'email' 		=> (isset($payData['email'])    ? $payData['email']:''), 
							'phone' 		=> (isset($payData['phone'])    ? $payData['phone'] :''),
							'productinfo'	=> (isset($payData['productinfo'])? $payData['productinfo']: ''), 
							'address1'		=> (isset($payData['address1']) ? $payData['address1']:''), 
							'address2'		=> (isset($payData['address2']) ? $payData['address2'] :''), 
							'city'			=> (isset($payData['city']) ? $payData['city'] :''), 
							'state'			=> (isset($payData['state']) ? $payData['state'] : ''), 
							'country'		=> (isset($payData['country']) ? $payData['country'] : ''), 
							'zipcode'		=> (isset($payData['zipcode']) ? $payData['zipcode'] :''), 
							'udf1' 			=> (isset($payData['udf1'])    ? $payData['udf1'] :''),
							'udf2' 			=> (isset($payData['udf2'])    ? $payData['udf2'] :''),
							'udf3'			=> (isset($payData['udf3'])    ? $payData['udf3'] :''),
							'udf4' 			=> (isset($payData['udf4'])    ? $payData['udf4'] : ''),
							'udf5' 			=> (isset($payData['udf5'])? $payData['udf5']:'') 
						  );
				$hash_sequence =   Misc::get_hash( $data,$this->config->item('salt'));
                //$data['surl'] = 'payment_success'; 
				//$data['furl'] = 'payment_failure';
				$data['surl'] = base_url('index.php/paymt/payview_success');
				$data['furl'] = base_url('index.php/paymt/payview_failure');
				$data['hash'] = $hash_sequence;
			  $pay = new Payment( $this->config->item('salt'),'test');
			  $status = $pay->pay($data);
			  // $status = $pay->pay($data);
			// $pay_status = Misc::show_reponse($status);
		$this->response($status,200);
		}
	} 
	function successURL_post()
	{
	//echo "Payment Success" . "<pre>" . print_r( $_POST, true ) . "</pre>";
       $trans_id = $_POST['txnid'];
       $pay_status = array();
       $model = self::MOD_MOB;
	   $updateData = array(
						"bank_name"			 =>	(isset($_POST['issuing_bank']) ? $_POST['issuing_bank'] : NULL),
						"payment_mode"       => (isset($_POST['mode']) ? $_POST['mode'] : NULL),
						"card_no"			 => (isset($_POST['mode']) && ($_POST['mode'] == 'CC' || $_POST['mode'] == 'DC') ? $_POST['cardnum'] :NULL ),
						"payu_id"             => (isset($_POST['mihpayid']) ? $_POST['mihpayid'] : NULL),
						"remark"             => (isset($_POST['field9']) ? $_POST['field9'] : NULL),
						"payment_ref_number" => (isset($_POST['bank_ref_num']) ? $_POST['bank_ref_num'] : NULL ),
						"payment_status"     => 0 //status - 0 (pending), will change to 1 after approved at backend
			);
		$payment = $this->$model->updateGatewayResponse($updateData,$trans_id);
		$serviceID = 3;
			$service = $this->services_modal->checkService($serviceID);
			$payData = $this->$model->get_invoiceData(isset($payment['id_payment'])?$payment['id_payment']:'');
		if($payment['status'] == true)
		{
			if($service['sms'] == 1)
			{
				$mobile = $payData[0]['mobile'];
				$message = "Hi ".$payData[0]['firstname'].", Thanks for your payment with ".$this->comp['company_name'].". Your payment amount Rs. ".$payData[0]['payment_amount']." (sub. to realisation) is processed successfully.";
				$senderid = $this->config->item('sms_senderid');
				$arr = array("@customer_mobile@" => $mobile,"@message@" => $message,"@senderid@" => $senderid);
				$url = $this->config->item('sms_url');
				$user_sms_url = strtr($url,$arr);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $user_sms_url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
				$result = curl_exec($ch);
				curl_close($ch);
				unset($ch);
			}
			if($service['email'] == 1 && isset($payData[0]['email']) && $payData[0]['email'] != '')
			{
				$to = $payData[0]['email'];
				$subject = "Reg - ".$this->comp['company_name']." payment for the chitscheme";
				$data['payData'] = $payData[0];
				$data['type'] = 2;
				$message = $this->load->view('include/emailPayment',$data,true);
				$sendEmail = $this->email_model->send_email($to,$subject,$message,"","");			
			}	
			$pay_status =array('status' => TRUE, 'msg' =>  'Payment successful. Thanks for your payment with'.$this->comp['company_name']);
			//$this->response($pay_status,200);
			redirect('paymt/payview_success');
		}
		else
		{
			$pay_status =array('status' => FALSE, 'msg' =>  'Error in updating the database.Please contact administrator at your earliest convenience.' );
					$this->response($pay_status,200);
		}
	}
	/* Payment failed - logic goes here. */
	function failureURL_post() 
	{
		//echo "Payment Failure" . "<pre>" . print_r( $_POST, true ) . "</pre>";
		$trans_id = $_POST['txnid'];
		$pay_status = array();
		$updateData = array(
							"bank_name"			 =>	(isset($_POST['issuing_bank']) ? $_POST['issuing_bank'] : NULL),
							"payment_mode"       => (isset($_POST['mode']) ? $_POST['mode'] : NULL),
							"card_no"			 => (isset($_POST['mode']) && ($_POST['mode'] == 'CC' || $_POST['mode'] == 'DC') ? $_POST['cardnum'] :NULL ),
							"payu_id"             => (isset($_POST['mihpayid']) ? $_POST['mihpayid'] : NULL),
							"remark"             => (isset($_POST['field9']) ? $_POST['field9'] : NULL),
							"payment_ref_number" => (isset($_POST['bank_ref_num']) ? $_POST['bank_ref_num'] : NULL ),
							"payment_status"     => -1 //status - 0 (pending), will change to 1 after approved at backend
							);
		$payment = $this->$model->updateGatewayResponse($updateData,$trans_id);
		$payData = $this->$model->get_invoiceData(isset($payment['id_payment'])?$payment['id_payment']:'');
		//$payData = $this->registration_model->get_cusData($this->session->userdata('username'));
		$serviceID = 3;
		$service = $this->$model->checkService($serviceID);
		if($service['sms'] == 1)
		{
			$mobile = $payData[0]['mobile'];
			$message = "Hi ".$payData[0]['firstname'].", Error occured in processing your payment. Please contact administrator.";
			$senderid = $this->config->item('sms_senderid');
			$arr = array("@customer_mobile@" => $mobile,"@message@" => $message,"@senderid@" => $senderid);
			$url = $this->config->item('sms_url');
			$user_sms_url = strtr($url,$arr);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $user_sms_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
			$result = curl_exec($ch);
			curl_close($ch);
			unset($ch);
		}
		if($service['email'] == 1 && isset($payData[0]['email']) && $payData[0]['email'] != '')
		{
			$to = $payData[0]['email'];
			$subject = "Reg - ".$this->comp['company_name']." payment for the chitscheme";
			$data['payData'] = $payData[0];
			$data['type'] = -1;
			$message = $this->load->view('include/emailPayment',$data,true);
			$sendEmail = $this->email_model->send_email($to,$subject,$message,"","");	
		}
		$scheme_failure = array("errMsg" => 'Payment failure.Please try again later');
	    redirect('paymt/payview_failure');
	}
	//generate hash
	function generateHash_post()
	{
		  $payData = $this->get_values();	
		    //generate unique id
	    		  $txnid =uniqid('VSD');
		  	$data =	array (
							'key' 			=>  $this->config->item('key'), 
							'txnid' 		=> $txnid, 
							'amount' 		=> (isset($payData['amount'])   ? $payData['amount']:''),
							'firstname' 	=> (isset($payData['firstname'])? $payData['firstname'] :''),
							'lastname' 		=> (isset($payData['lastname']) ? $payData['lastname']:''),
						    'email' 		=> (isset($payData['email'])    ? $payData['email']:''), 
							'phone' 		=> (isset($payData['phone'])    ? $payData['phone'] :''),
							'productinfo'	=> (isset($payData['productinfo'])? $payData['productinfo']: ''), 
							'address1'		=> (isset($payData['address1']) ? $payData['address1']:''), 
							'address2'		=> (isset($payData['address2']) ? $payData['address2'] :''), 
							'city'			=> (isset($payData['city']) ? $payData['city'] :''), 
							'state'			=> (isset($payData['state']) ? $payData['state'] : ''), 
							'country'		=> (isset($payData['country']) ? $payData['country'] : ''), 
							'zipcode'		=> (isset($payData['zipcode']) ? $payData['zipcode'] :''), 
							'udf1' 			=> (isset($payData['udf1'])    ? $payData['udf1'] :''),
							'udf2' 			=> (isset($payData['udf2'])    ? $payData['udf2'] :''),
							'udf3'			=> (isset($payData['udf3'])    ? $payData['udf3'] :''),
							'udf4' 			=> (isset($payData['udf4'])    ? $payData['udf4'] : ''),
							'udf5' 			=> (isset($payData['udf5'])? $payData['udf5']:'') 
						  );
				$hash_sequence =   Misc::get_hash( $data,$this->config->item('salt'));
				$userpay =array('paydata'=>$data,'hash'=>$hash_sequence,'txnid'=>$txnid);
				$this->response($userpay,200);
	}
		//to get offers
   	function offers_get()
	{ 
		$model = self::MOD_MOB;
		$data['offers_banners'] = $this->$model->get_offersAndBanners();
		$data['offers'] = $this->$model->get_offers($this->get('id_branch'));
		$data['banners'] = $this->$model->get_banners();
		if($data['offers'] == 0)
		{
			$data['offers'] = [];
			$data['msg'] = 'No offers found';
		}
		$this->response($data,200);
	}
	//to get new arrivals
	function new_arrivals_get()
	{ 
		$model = self::MOD_MOB;
		$data['new_arrivals'] = $this->$model->get_new_arrivals($this->get('id_branch'));
		if($data['new_arrivals'] == 0)
		{
			$data['new_arrivals'] = [];
			$data['msg'] = 'No New Arrivals found';
		}
		$this->response($data,200);
	}
	//to get particular data
	function newArrDetail_get()
	{ 
		$id = $this->get('id_new_arrival');
		$model = self::MOD_MOB;
		$result= $this->$model->get_newArrdetail($id);
		$this->response($result,200);
	}
	//to get particular data
	function offerDetail_get()
	{ 
		$id = $this->get('id_offer');
		$model = self::MOD_MOB;
		$result= $this->$model->get_offerdetail($id);
		$this->response($result,200);
	}
	function gift_items_get()
	{ 
		$model = self::MOD_MOB;
		$data['gift_items'] = $this->$model->get_gift_items();
		$this->response($data,200);
	}
    function giftItemDetail_get()
	{ 
		$id = $this->get('id_new_arrival');
		$model = self::MOD_MOB;
		$data['gift_items'] = $this->$model->get_giftItemDetail($id);
		$this->response($data,200);
	}
	//to chit_settings data
	function getSettings_get()
	{ 
		$model = self::MOD_MOB;
		$result['chit_settings']= $this->$model->get_settings();
		$this->response($result,200);
	}
	//to noti enable/disable by customer
	function getcusSettings_get()
	{ 
		$id = $this->get('id_customer');
		$model = self::MOD_MOB;
		$result = $this->$model->get_cus_noti_settings($id);
		$this->response($result,200);
	}
	//to noti enable/disable by customer
	function updatecusSettings_post()
	{ 
		$data = $this->get_values();
		$model = self::MOD_MOB;
		$updData = array("id_customer" =>$data['id_customer'],
						 "notification" =>$data['notification'] );
		$deviceData = array(
						'token'		  => $data['token'],
						'uuid'		  => $data['uuid'],
						'device_type' => $data['device_type'],
						'id_customer' => $data['id_customer']
						);
		$this->db->trans_begin();				
		$insStatus = $this->$model->update_cusnotisettings($updData);				
			if($insStatus){
				if($data['notification']==1){	
					if($data['token'] != null ){
					    $res= $this->$model->insert_deviceData($deviceData);
					    if($this->db->trans_status()===TRUE)
							{				
								$this->db->trans_commit();	
					  			$status['status'] = $res['status'];
							}
						else{
							$this->db->trans_rollback();
							$status['status'] = FALSE;
						}
					}
					else{
						$this->db->trans_rollback();
						$status['status'] = FALSE;
					}
				}
				else{
					  if($this->db->trans_status()===TRUE)
						{				
							$this->db->trans_commit();	
				  			$status['status'] = TRUE;
						}
						else{
							$this->db->trans_rollback();
							//$status['status'] = FALSE;
						}
				}
			}
			else{
				$this->db->trans_rollback();
				$status['status'] = FALSE;
			}
		/*}*/
		$this->response($status,200);
	}
	function closedAcc_get()
	{
		$model = self::MOD_MOB;
		$cus_id = $this->get('id_customer');
		$branches = [];
        // Get Branch Name and ID 
        if($this->comp['branch_settings'] == 1 && $this->comp['is_branchwise_cus_reg'] == 0){
    	    $branches = $this->$model->branchesData(); 
        }
		$closed = $this->$model->get_closed_account($cus_id);
		$result = array('closed_acc' => $closed,'branches' => $branches,'msg' => sizeof($closed) > 0?'':'Closed schemes not found');
		$this->response($result,200);
	}
	function getClassificationAll_get()
	{ 
		$model = self::MOD_MOB;
		$result = $this->$model->getClassification();
		$this->response($result,200);
	}
	//to get visible to customers schemes only
	 function getVisClass_get() // based on the branch settings to showed classify //HH
     {
        $model = self::MOD_MOB;
        $result['branches'] = [];
        if($this->comp['branch_settings'] == 1){
            $result['clasification'] = $this->$model->getVisClass($this->get('id_branch'));
            // Get Branch Name and ID 
    	    if($this->comp['is_branchwise_cus_reg'] == 0){
    	        $result['branches'] = $this->$model->branchesData(); 
    	    }
        }
        else{
            $result['clasification'] = $this->$model->getVisClass_withoutbranch();
        }
        $this->response($result,200);
      }
	// check maintenance mode and version_compare
	function getVersion_get()
    {
      $version['android'] = $this->current_android_version;
      $version['newver_android'] = $this->new_android_version;
      $version['playPackage'] = $this->config->item('aPackage');
      $version['aMsg'] = "New version available at play store.";// version alert message
      $version['ios'] = $this->current_ios_version;
      $version['newver_ios'] = $this->new_ios_version;
      $version['iPackage'] = $this->config->item('iPackage');
      $version['iMsg'] = "New version available at app store.";// version alert message
      $version['comp'] = $this->comp;
      $version['mode'] = $version['comp']['maintenance_mode'];
      $version['text'] = $version['comp']['maintenance_text']; // maintenance text
      $version['title'] = "Update Available";
      $version['comp']['playstore_url'] = $this->config->item('paystore_url');
      $version['sms_sender_id'] = $this->sms_data['sms_sender_id'];
      $popup = $this->mobileapi_model->getPopup();
      $version['showpopup'] = !empty($popup) ? 1 : 0;
      $version['popupimg'] = $popup;
      $this->response($version,200);
    }
    function getVerioniOSv1_get()
    {
        $app_version = $this->get('app_version');
        $ios_ver     = $this->current_ios_version;
        $new_ios_ver = $this->new_ios_version;
        if($app_version == $ios_ver || $app_version == $new_ios_ver) {
            $version['updateAvail']  = 0;
            $version['iMsg'] = "";
        } else {
            $version['updateAvail']  = 1;
            $version['iMsg'] = "New version available at app store.";// version alert message
        }
        $version['iPackage'] = $this->config->item('iPackage');
        $version['comp'] = $this->comp;
        $version['mode'] = $version['comp']['maintenance_mode'];
        $version['text'] = $version['comp']['maintenance_text']; // maintenance text
        $version['title'] = "Update Available";
        $version['sms_sender_id'] = $this->sms_data['sms_sender_id'];
        $popup = $this->mobileapi_model->getPopup();
        $version['showpopup'] = !empty($popup) ? 1 : 0;
        $version['popupimg'] = $popup;
        $this->response($version,200);
    }
	/*function getVersion_get()
	{ 
	   	$version['android'] = $this->current_android_version;
	   	$version['new_android_ver'] = $this->new_android_version;
		$version['ios'] = $this->current_ios_version;
		$version['comp'] = $this->comp;
		$version['mode'] = $version['comp']['maintenance_mode'];
		$version['text'] = $version['comp']['maintenance_text']; // maintenance text
		$version['msg'] = "New version available at store.";// version alert message
		$version['comp']['playstore_url'] =$this->config->item('paystore_url');
		$version['sms_sender_id'] = $this->sms_data['sms_sender_id'];
		$popup = $this->mobileapi_model->getPopup();
		$version['showpopup'] = !empty($popup) ? 1 : 0; 
		$version['popupimg'] = $popup; 
		$this->response($version,200);
	}*/
	// payview page	
	function getActivecardBrands_get()
	{ 
		$model = self::MOD_MOB;
		$result['cc']= $this->$model->getActivecardBrands(1);
		$result['dc']= $this->$model->getActivecardBrands(2);
		$this->response($result,200);
	}
	function getsavedCards_get()
	{ 
        $model = self::MOD_MOB;
        $mobile = $this->get('mobile');
        $id_customer = $this->get('id_customer');
        $data=$this->$model->get_customerByID($id_customer);
        $gateway=$this->$model->getBranchGatewayData($data['id_branch'],1);
		if($mobile && $gateway)
		{  
		   $key = $gateway['param_1'];
		   $var1 = $gateway['param_1'].':'.$mobile;
		   $command = 'payment_related_details_for_mobile_sdk';
		   /*Security parameter– SHA512(key|command|var1|salt)*/
			$hash_sequence = $key.'|'.$command.'|'.$var1.'|'.$gateway['param_2'];	
			$hash_value =  strtolower( hash( 'sha512', $hash_sequence ) );
			$url = $gateway['api_url'];
			$data = array(
			               'key'    =>$gateway['param_1'],
			               'command' =>$command,
			               'hash'   =>$hash_value ,
			               'var1'   => $var1,
			               'salt'   => $gateway['param_2']
			              );
			$response = array();             
			$response =  $this->httpPost($url,$data);	
			if($response!='')
			{
			    	$this->response($response,200);	
			}else{
			    $res=[];
			    $this->response($res,200);	
			}
		}
	}	
	/*Delete saved card*/
	function deleteSavedCard_get()
	{ 
		  $mobile = $this->get('mobile');
		  $var2 = $this->get('card_token');
			if($mobile)
			{  
				$data=$this->payment_gateway('1','1');
			   $key = $data['param_1'];
			   $var1 = $data['param_1'].':'.$mobile;
			   $command = 'delete_user_card';
			   /*Security parameter– SHA512(key|command|var1|salt)*/
				$hash_sequence = $key.'|'.$command.'|'.$var1.'|'.$data['param_2'];	
				$hash_value =  strtolower( hash( 'sha512', $hash_sequence ) );
				$url = $data['api_url'];
				$data = array(
				               'key'    =>$data['param_1'],
				               'command' =>$command,
				               'hash'   =>$hash_value ,
				               'var1'   => $var1,
				               'var2'   => $var2			               
				              );
				$response = array();             
				$response =  $this->httpPost($url,$data);	
				$this->response($response,200);	
			}
	}
	function httpPost($url,$params)
	{
	  $postData = '';
	   //create name value pairs seperated by &
	   foreach($params as $k => $v) 
	   { 
	      $postData .= $k . '='.$v.'&'; 
	   }
	   $postData = rtrim($postData, '&');
       $output =array();
        $ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POST, count($postData));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		$output = curl_exec($ch);
		/*curl_close($ch);
		var_dump($output);exit;*/
	 	return json_decode($output);
	}
	// functions to register existing scheme with validation
	/*function checkAccData_post()
	{
	   $result = array();
	   $model = self::MOD_MOB;
	   $data = $this->get_values();
	   if(isset($data['mobile'])){
	   	 $array_data = array(
							'mobile' 		=> $data['mobile']
							//'id_branch' 		=> $data['id_branch']
						);
	   }
	   else{
		  $array_data = array(
					//'id_branch' 		=> $data['id_branch'],
					'group_name' 		=> strtoupper($data['group_name']),
					'scheme_acc_number' => $data['scheme_acc_number']
				);
	   }
	   $res = $this->$model->isAccExist($array_data);
	   if($res['status'])
	   {
	   		$result = array('status' => TRUE,'mobile'=>$res['mobile'],'msg'=>$res['msg']);
	   }
	   else
	   {
	   		$result = array('status' => FALSE,'msg'=>$res['msg']);
	   }
	   $this->response($result,200);			
	}
	function insertExisAcc_post()
	{
	   $result = array();
	   $model = self::MOD_MOB;
	   $data = $this->get_values();
	   $this->db->trans_begin();
	   $res = $this->$model->insertExisAccData($data);
	   echo $this->db->last_query();
	   if($res['status'])
	   {
	   		$payData = $this->$model->insert_paymentData($res['data']);
	   		if($payData['status']){
				$update = $this->$model->updateOfflineData($res['data']);
		   		if($this->db->trans_status()===TRUE)
				{
					$this->db->trans_commit();
			   		$result = array('status' => $update);		   		
		   		}
		   		else{
					$this->db->trans_rollback();
					$result = array('status' => $update,'msg'=>"Unable to proceed your request.");
				}
			}
			 else
			   {
			   		$this->db->trans_rollback();
			   		$result = array('status' => FALSE,'msg'=>"Unable to proceed your request");
			   }
	   }
	   else
	   {
	   		$this->db->trans_rollback();
	   		$result = array('status' => FALSE,'msg'=>"Unable to proceed your request,contact customer care.");
	   }
	   $this->response($result,200);			
	}*/
	// END OF -- functions to register existing scheme with validation
// branch name list
public function get_branch_get()
{
	$model = self::MOD_MOB;
	$data=$this->get_branch();
    echo json_encode($data);
}
function viewall_parentcategory_get()
{
	$model = self::MOD_MOB;
	$data=$this->get_category();
    echo json_encode($data);
}
function get_branch()
{
	$sql = "SELECT b.id_branch, b.name, b.active, b.short_name,
	b.id_employee,b.address1, b.address2, b.id_country, b.id_state, 
	b.id_city, b.phone,b.mobile, b.cusromercare, b.pincode,
	b.metal_rate_type,c.branch_settings FROM branch b
	join chit_settings c  where active =1 and show_to_all != 3 order by b.sort";
	$branch = $this->db->query($sql)->result_array();
	return $branch;
} 
function get_category()
	{
	 //CONCAT('".base_url()."/admin/assets/img/category/','',catimage)
		$sql = "SELECT id_category, catimage as image ,categoryname as category_name,id_parent, ifnull(description, '') as description, if(active = 1,'Active','Inactive') as active
		FROM category cat where id_parent='1' and cat.active=1";
		$category = $this->db->query($sql)->result_array();		
		return $category;
	} 	
 function view_subparent_category_get()
 {
        $category = $this->get('id_category');		
		$sql = "SELECT id_category,catimage as image ,categoryname as category_name,id_parent, ifnull(description, '') as description, if(active = 1,'Active','Inactive') as active
		FROM category cat where cat.active=1 and id_parent='".$category."'";
		$result = $this->db->query($sql);	
		if($result->num_rows() > 0){
		    $resposeData = array('type' => 'subcat','responseData' => $this->db->query($sql)->result_array(),'msg'=>'Sub-category fetched successfully');	
		}else{
	    	$sql = "SELECT id_product,productname as product_name,proimage
		      FROM products pro
			  LEFT JOIN category as cat ON cat.id_category =pro.id_category
			  where pro. active=1 and pro.id_category='".$category."'";
			  if($this->db->query($sql)->num_rows() > 0){
    		    $resposeData = array('type' => 'product','responseData' =>$this->db->query($sql)->result_array(),'msg'=>'Products fetched successfully');
    		  }else{
    		      $resposeData = array('type' => 'product','responseData' => array(),'msg'=>'No Products found');
    		  }
		}
	    echo json_encode($resposeData);
 }
  function productDetail_post()
 {
	    $data = $this->get_values();
		$sql = "SELECT id_product,pro.id_category as id_category,productname as product_name, ifnull(pro.description, '') as description, if(pro.active = 1,'Active','Inactive') as active,proimage,weight,size,type,price,code,purity
			      FROM products pro 
				  where pro.id_product=".$data['id_product'];
		$product = $this->db->query($sql)->row_array();		
	    echo json_encode($product);
 }
// branch name list 
// wallet work  //
	function wallet_account_create($cus_id,$mobile)
	{
	$this->load->model('services_modal');
	$this->load->model('email_model');
	$model = self::MOD_MOB;
	$wallet_acc_no =  $this->services_modal->get_wallet_acc_number();				
	$insertData=array( 
				   'id_customer' 	   => (isset($cus_id) && $cus_id!=''? $cus_id:NULL),
				   'id_employee' 	   =>  2,
				   'wallet_acc_number' => (isset($wallet_acc_no)?$wallet_acc_no:NULL),
				   'issued_date' 	   => date('y-m-d H:i:s'),
				   'remark' 		    => "Credits",
				   'active'		        => 1	                        
				   );
		   //inserting data                  
		   $status = $this->services_modal->walletacc_insert($insertData);
		   $wallAcc = $this->services_modal->get_walletacc($status['insertID']);
		   $this->mobileapi_model->insChitwallet($status['insertID'],$mobile,$cus_id);
		   if($status)
			{
				 $serviceID = 8;
				 $service =  $this->services_modal->checkService($serviceID);			
					if($service['sms'] == 1)
					{
						$id =$status['insertID'];
						$data =$this->services_modal->get_SMS_data($serviceID,$status['insertID']);
						$mobile =$data['mobile'];
						$message = $data['message'];
						if($this->config->item('sms_gateway') == '1'){
                		    $this->sms_model->sendSMS_MSG91($mobile,$message,'',$service['dlt_te_id']);		
                		}
                		elseif($this->config->item('sms_gateway') == '2'){
                	        $this->sms_model->sendSMS_Nettyfish($mobile,$message,'trans');	
                		}
					}
					if($service['serv_whatsapp'] == 1)
					{
                	  $this->services_modal->send_whatsApp_message($mobile,$message);
                    }
			}
	}
// wallet work  //
   function referral_linksend_get(){
	   $mobile = $this->get('mobile');
	   $isReferral = $this->get('allow_referral');
	   $serviceID = ($isReferral == 1 ? 15 :19);
	   if($mobile!=''){	
			$data = $this->services_modal->get_SMS_data($serviceID,$mobile);	
			$link = $this->shortenurl($this->config->item('paystore_url')); 
			$message = str_replace('##',$link,$data['message']);			
			$result = array('status' =>TRUE,'message' => $message);			
	   }else{
		   $result = array('status' => FALSE,'msg'=>"Unable to proceed your request,contact customer care.");
	   }	 
		$this->response($result,200);
   }
	function shortenurl($url){
		$ch = curl_init();  
		$timeout = 5;  
		curl_setopt($ch,CURLOPT_URL,'https://tinyurl.com/api-create.php?url='.$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);  
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);  
		$data = curl_exec($ch);  
		curl_close($ch);  
		return $data;  
	}
	// Netbanking list  
	function getNetbankingStatus_get()
		{ 		 
		 	$model = self::MOD_MOB;
		  $var1 = 'default';
		  $command = 'getNetbankingStatus';	
		 $id_customer=$this->get('id_customer');		  
		$data=$this->$model->get_customerByID($id_customer);
		 $payData = $this->$model->getBranchGatewayData($data['id_branch'],1);
		 $hash_sequence = $payData['param_1'].'|'.$command.'|'.$var1.'|'.$payData['param_2'];
		 $hash_value =  strtolower( hash( 'sha512', $hash_sequence ) );
		 $url =$payData['api_url'];
		$data = array(
					  'key'    =>$payData['param_1'],
					  'command' =>$command,
					  'hash'   =>$hash_value,
					  'var1'   =>$var1,
					  'salt'   =>$payData['param_2']
					 );
//print_r( $this->payment_gateway);exit;
		  $response = array();             
		  $response =  $this->httpPost($url,$data);
		  $this->response($response,200);	
		}
// Existing scheme Registration functions
	function join_existing_byacc($data)
	{
	   $scheme_acc  = array("group_code" => ($data['group_code']!=''? $data['group_code']:''),
		                    "scheme_acc_number" => ($data['scheme_acc_number']!=''? $data['scheme_acc_number']:''),
		                    "account_name" => ($data['account_name']!=''? $data['account_name']:''),
							"id_branch" => (isset($data['id_branch']) ? ($data['id_branch']==''? NULL:$data['id_branch']):NULL)
							);
	    $acc = $this->scheme_modal->isAccExist($scheme_acc);	
	//  echo $this->db->last_query();
		if($acc['status']){	
		 	$model = self::MOD_MOB;
			$otp   = $this->$model->generateOTP(); 
			if($this->config->item('sms_gateway') == '1'){
    		    $sendotp = $this->sms_model->sendSMS_MSG91($acc['mobile'],$this->otp_sms($otp),'','');		
    		}
    		elseif($this->config->item('sms_gateway') == '2'){
    	        $sendotp = $this->sms_model->sendSMS_Nettyfish($acc['mobile'],$this->otp_sms($otp),'trans');	
    		}
    		if($service['serv_whatsapp'] == 1)
    		{
            	$sendotp = $this->services_modal->send_whatsApp_message($acc['mobile'],$message);
            }
			$this->send_mail($acc['mobile'],$acc['email'],$acc['name'],$otp['otp']);
			if($sendotp){
			   // return array('status' => TRUE,'otpDetail'=>$otp, 'msg' => 'OTP has been sent to registered mobile number.');
				return array('status' => TRUE,'otpDetail'=>$otp, 'msg' => 'OTP has been sent to registered mobile number.','register_mobile'=>$acc['mobile']);									
			}
		}
		else
		  {
		  	//$this->session->set_flashdata('successMsg','Unable to proceed your request');
		  	//return array('status' => FALSE, 'msg' => $acc['msg']);
		  	return array('status' => FALSE, 'msg' => $acc['msg'],'register_mobile'=>$acc['mobile']);
		  }
	}
	public function send_mail($mobile,$email="",$name="",$OTP="")
	{
						 $to = $email;	
						$data['otp']=$OTP;
						$data['type'] = 20;
						$data['company_details'] = $this->comp;
						$data['name'] = $name;
						$subject = "Reg: ".$this->comp['company_name']." Existing OTP Verification";
						$message = $this->load->view('include/emailAccount',$data,true);
						$this->load->model('email_model');
						$sendEmail = $this->email_model->send_email($to,$subject,$message,"","");
						return TRUE;
	}
	public function submit_schregOtp_post()
	{
		$model = self::MOD_MOB;
		 $data = $this->get_values();	
		 if($data['sysotp'] == $data['userotp'])
		 {
		 	$otp_time = date('Y-m-d H:i:s');
			if($otp_time <= date('Y-m-d H:i:s',strtotime($data['last_otp_expiry'])))	{
				   $this->db->trans_begin();
		   		   $res = $this->scheme_modal->insertExisAccData($data);
				   if($res['status'])
				   {
				   		$payData = $this->scheme_modal->insert_paymentData($res['data'],$res['insertID']);
				   	    if($payData['status']){
						$this->scheme_modal->updateInterTableStatus($res['data'],$res['insertID'],$payData['suceedIds']);
					   		if($this->db->trans_status()===TRUE)
							{
								$this->db->trans_commit();
						   		$response =  array('status' => TRUE, 'msg' => 'Purchase plan registered successfully.');
								$this->response($response,200);	
					   		}
					   		else{
								$this->db->trans_rollback();
								$response =  array('status' => FALSE, 'msg' => 'Unable to proceed your request,contact customer care.');
								$this->response($response,200);	
							}
						}
						 else
						   {
						   		$this->db->trans_rollback();
						   		$response =  array('status' => FALSE, 'msg' => 'Unable to proceed your request,contact customer care.');
								$this->response($response,200);	
						   }
				   }
				   	else
					{
					$response =  array('status' => FALSE, 'msg' => 'Unable to proceed your request,contact customer care.');
						$this->response($response,200);	
					}
				}else{
					$result = array('status' => FALSE, 'msg' => 'Your OTP expired');
				}
			}else
			{
				$result =  array('status' => FALSE, 'msg' => 'Your OTP is invalid');
			}
		$this->response($response,200);
	}
	function existingRegReq_get()
	{ 
		$model = self::MOD_MOB; 
		$data = $this->$model->get_exisRegReq($this->get('id_customer'));  
		$this->response($data,200);
	}
    function uploadCusimage_post(){
        $model = self::MOD_MOB;
        $arrdata = $_POST;
		$data = json_decode(json_encode($arrdata), FALSE); 
        $cus_id = $data->id_customer;
        $img_path = self::CUS_IMG_PATH."/".$cus_id;
    	if($cus_id && $_FILES){
    	    if (!is_dir($img_path)) {
    		    mkdir($img_path, 0777, TRUE); 
    	    }
       	 if($_FILES['file']['name'])
       	 {    
            $img = $_FILES['file']['tmp_name'];
            $path = self::CUS_IMG_PATH.$cus_id."/customer.jpg";
    		$filename = "customer.jpg";	 	
    	 	if (($img_info = getimagesize($img)) === FALSE)
        	{
        		// die("Image not found or not an image");
        		$this->response(array('status' => false , 'filename' => null),200);
        	}
        	$width = $img_info[0];
        	$height = $img_info[1];
        	switch ($img_info[2]) {
        	  case IMAGETYPE_GIF  : $src = imagecreatefromgif($img);
        	  						$tmp = imagecreatetruecolor($width, $height);
        	  						$kek=imagecolorallocate($tmp, 255, 255, 255);
        				      		imagefill($tmp,0,0,$kek);
        	  						break;
        	  case IMAGETYPE_JPEG : $src = imagecreatefromjpeg($img); 
        	  						$tmp = imagecreatetruecolor($width, $height);
        	 						break;
        	  case IMAGETYPE_PNG  : $src = imagecreatefrompng($img);
        						    $tmp = imagecreatetruecolor($width, $height);
        	  						$kek=imagecolorallocate($tmp, 255, 255, 255);
        				     		imagefill($tmp,0,0,$kek);
        				     		break;
        	  default : //die("Unknown filetype");	
        	  $this->response(array('status' => false , 'path' => null),200);
        	}		
        	  imagecopyresampled($tmp, $src, 0, 0, 0, 0, $width, $height, $width, $height);
        	  imagejpeg($tmp, $path);	
        	  $status =  $this->$model->update_customer(array('cus_img'=>$filename ),$cus_id);  
              if($status){
                  $this->response(array('status' => true ,  'path' => base_url().$path.'?nocache='.time()),200);
              }else{
                   $this->response(array('status' => false   ,'path' => null),200);
              }
    	  } 
    	}
    }
    function getAllPaymentGateways_get()
    {  
        $model = self::MOD_MOB;
		$id_customer=$this->get('id_customer');
		if($this->get('id_branch')){
			$id_branch = $this->get('id_branch');
		}else{
			$data = $this->$model->get_customerByID($id_customer); 
			$id_branch = $data['id_branch'];
		}
        $result['pgData'] = $this->$model->getBranchGateways($id_branch);
        //$result['pgData'] = $this->$model->getAllPG();
        $result['cc']= $this->$model->getActivecardBrands(1);
        $result['dc']= $this->$model->getActivecardBrands(2);
        $result['msg'] = "";
        $this->response($result,200); 
    }
    function mobile_FullPayredeem_post()
	 { 
	   $payData = $this->get_values();
   	   $pay_flag = TRUE;
	   $allow_flag = FALSE;
	   $submit_pay = FALSE; 
	   $cusData = $this->payment_modal->get_customer($payData['phone']);
	   $redeemed_amount = $payData['redeemed_amount']; 	
		//generate txnid
	     $txnid = uniqid(time());
		 $i=1;
		 $udf1= "";
		 $udf2= "";
		 $udf3= "";
		 $productinfo= ""; 
		 $this->db->trans_begin();
		foreach ($payData['pay_arr'] as $pay){	 
				//validate amount
			   $chit = $this->payment_modal->get_schemeByChit($pay->udf1);
			   if( $pay->scheme_type == 1)
			   {						   
					  $metal_rate = $this->payment_modal->getMetalRate();	
					  $gold_rate = (float) $metal_rate['goldrate_22ct'];
					  $amt = $gold_rate * $pay->udf2;
					  $allow_flag =  ($pay->amount >= $amt? TRUE :FALSE);
			   }
			   else
			   {						   
					$allow_flag =  ($pay->amount >= $chit['amount']? TRUE :FALSE);
			   }
			  if($pay->scheme_type == 2 || $pay->scheme_type == 3){
			   	    $data = array('metal_rate'=>$pay->udf3,'amount'=>$pay->udf4);
					$metal_wgt = $this->amount_to_weight($data);
			   }
			   else{	
					$metal_wgt = (isset($pay->udf2) && $pay->udf2 !='' ? $pay->udf2 : 0.000);
			   }
			   if($allow_flag){
				//set insert data					
					$insertData = array(
						"id_scheme_account"	 => (isset($pay->udf1)? $pay->udf1 : NULL ),
						"payment_amount" 	 => (isset($pay->amount)? $pay->amount+($pay->discount==""?0.00:$pay->discount) : NULL ), 
						"gst" 	    		 => (isset( $chit['gst'])? $chit['gst'] : 0 ),
						"gst_type" 	    	 => (isset($chit['gst_type'])? $chit['gst_type'] : 0 ),
						"payment_type" 	     => "Wallet Redeem",
						"due_type" 	   		 => $pay->due_type,
						"no_of_dues" 	     => 1,
						"actual_trans_amt"   => 0,
						"date_payment" 		 =>  date('Y-m-d H:i:s'),
						"metal_rate"         => (isset($pay->udf3) && $pay->udf3 !='' ? $pay->udf3 : 0.00),
						"metal_weight"       =>  $metal_wgt,
						"id_transaction"     => (isset($txnid) ? $txnid.'-'.$i : NULL),
						"ref_trans_id"       => (isset($txnid) ? $txnid : NULL),// to update pay status after trans complete.
						"added_by"			 =>  2,
						"add_charges" 	     => (isset($pay->charge) ?$pay->charge : NULL), 
						"discountAmt"        => ($pay->discount!="" ? $pay->discount : 0.00),
						"remark"             => "Wallet Redeem",
						"payment_status"     => 1,
						"redeemed_amount"    => (isset($redeemed_amount) ?$redeemed_amount :0.00),
						"id_branch"     	 => ($cusData['branchWiseLogin']== 1  ? $this->config->item('pay_branchId') :NULL),
						//status - 0 (pending), will change to 1 after approved at backend
						);
				$udf1 = $udf1." ".$pay->udf1;
				$udf2 = $udf2." ".$pay->udf2;
				$udf3 = $udf3." ".$pay->udf3;
				$productinfo = $productinfo." ".$pay->chit_number;
				//inserting pay_data before gateway process
				$payment = $this->payment_modal->addPayment($insertData);	
				$i++;
			}
		 } 
		 if($this->db->trans_status()=== TRUE)
	     {
		 	$this->db->trans_commit();
		 	$transData = array();
	        $pay = $this->payment_modal->getWalletPaymentContent($txnid); 
	        if($pay['redeemed_amount'] > 0){ 
			$transData = array('mobile' 			=> $pay['mobile'],
							 'actual_trans_amt' => $pay['actual_trans_amt'],
							 'available_points'	=> ($pay['isAvail'] == 0 ?0:$pay['available_points']),
							 'isAvail'			=> ($pay['isAvail'] == 0 ?0:1),
							 'redeemed_amount'	=> $pay['redeemed_amount'], 
							 'id_payment'       => $pay['id_payment'],
							 'txnid'            => $txnid.'- D',
							 'branch'           => $pay['branch'],
		    				 'walletIntegration'=> $pay['walletIntegration'],
							 'id_customer'=> $pay['id_customer'],
							 'wallet_points'=> $pay['wallet_points'],
							 'wallet_balance_type'=> $pay['wallet_balance_type'],
							 'wallet_amt_per_points'=> $pay['wallet_amt_per_points']
							); 
	    		if(!empty($transData)){
	    		    $result=$this->insertWalletTrans($transData); 
					if($result['status'] == TRUE)
					{
						$pay_setting=  $this->payment_modal->paymentDB($payment['insertID']); 
						if($pay_setting['schemeacc_no_set'] == 0 )
									{   
										// Generate a/c no
										if($pay_setting['acc_no'] == '' ||  $pay_setting['acc_no'] == null)
										{
									          $ac_group_code = NULL;
    										// Lucky draw
											if($pay_setting['is_lucky_draw'] == 1 ){ // Based on scheme settings 
												// Update Group code in scheme_account table 
												$updCode = $this->payment_modal->updateGroupCode($pay_setting); 
												$ac_group_code = ($updCode['status'] ? $updCode['group_code'] : NULL);
											}
											$scheme_acc_number = $this->payment_modal->account_number_generator($pay_setting['id_scheme'],$pay_setting['branch'],$ac_group_code);
											if($scheme_acc_number != NULL && $scheme_acc_number !="Not Allocated"){
												$updateData['scheme_acc_number'] = $scheme_acc_number;
											}
											$updSchAc = $this->payment_modal->update_account($updateData,$pay_setting['id_scheme_account']);
										}
									}
									if($pay_setting['receipt_no_set'] == 1 )
									{  
										$receipt_no = $this->generate_receipt_no();
										$pay_array['receipt_no'] = $receipt_no;
										$result =  $this->payment_modal->update_receipt($payment['insertID'],$pay_array); 
									}
									 $this->response(array('status'=>TRUE,'msg'=>'Paid Successfully'),200);	
						}
	    		}				    		 
	         }
	         $this->response(array('status'=>TRUE,'msg'=>'Paid Successfully'),200);	
		 }
		 else{
		 	$this->db->trans_rollback();
		 	$this->response(array('status'=>FALSE,'msg'=>'Couldn\'t proceed your request,try after sometime.'),200);	
		}
	}
	function amount_to_weight($to_pay)
	{
		$converted_metal_wgt = $to_pay['amount']/$to_pay['metal_rate'];
		return $converted_metal_wgt;
	}
	function insertWalletTrans($tran){  
	  if($tran)
	  {
		 $redeemed_amount=$tran['redeemed_amount'];
		 if($tran['wallet_balance_type']==1)
		 {
			$redeemed_amount=(($redeemed_amount/$tran['wallet_amt_per_points'])*$tran['wallet_points']);
		 }
	  	$transDetailData = array(	
		            				"amount" 		 => $tran['actual_trans_amt'],
		            				"date_add"       => date('Y-m-d H:i:s'),
		            				"remark" 		 => 'Debited for purchase plan payment '.$tran['txnid'],
		            				'trans_points' 	 => $redeemed_amount
		            			); 
	  	if($tran['walletIntegration'] == 0){ // 0 - No integration, 1 - Req integration as like SSS
		  	$transDetailData['trans_type'] = 2;
			$updwallet = $this->payment_modal->updwallet($transDetailData,$tran['mobile']);
			$r = array("status" 	=> $updwallet);
			return $r;
		}else{
		   	   $cat_code = 'SS';
		   	   $wal_cat_settings = $this->payment_modal->getWcategorySettings($cat_code);
		   	   // Begin the transaction
		   	   $this->db->trans_begin();
			   if($wal_cat_settings){
			   	   $transData = array(	
		            				"record_to" 	 =>  1, //1 - offline , 2 - online
		            				"entry_date" 	 =>  date('Y-m-d H:i:s'), 
		            				"bill_no" 	 	 =>  $tran['txnid'],
		            				"id_branch" 	 =>  $tran['branch'],
		            				"trans_type"     =>  2,
		            				"mobile" 	 	 =>  $tran['mobile'],
		            				"is_transferred" =>  'N',
		            				"is_modified"    =>  0,
		            				"date_add"       =>  date('Y-m-d H:i:s'), 
		            				'actual_redeemed' => $redeemed_amount,
		            				"use_points" 	 => 1,
				            		"redeem_req_pts" => $redeemed_amount,
		            				"record_type"	 =>  2// 1 - offline , 2 - online
		            			);
		           $transDetailData['id_wcat_settings'] = $wal_cat_settings['id_wcat_settings'];
	           	   $transDetailData['category_code'] = $cat_code;
			   	   $wallAccount = $this->payment_modal->getInterWalletCustomer($transData['mobile']); 
			   	   $allow = FALSE;
			   	   if($wallAccount['status']){
				   		$upd_data = array(	
                    				"available_points"  => ($tran['available_points']-$redeemed_amount),
                    				"last_update"       => date('Y-m-d H:i:s'),
                    				"mobile" 	 	 	=> $tran['mobile'],
                    			);
				   		$w_status  = $this->payment_modal->updInterWalletAcc($upd_data);
				   		if($w_status){
							$allow = TRUE;
						}
				   }else{
				   		$transDetailData['trans_type'] = 2;
				   		$transDetailData['bill_no'] = $tran['txnid'];
				   		$transDetailData['id_branch'] = $tran['branch'];
				   		$updwallet = $this->payment_modal->updwallet($transDetailData,$tran['mobile']);
				   		$msg = 'Thank you for using '.$this->comp['company_name'].' wallet. Redeemed '.number_format($redeemed_amount,'2','.','').' points for scheme payment.'; 	
						if($updwallet){
							$smsData  = array('mobile' => $tran['mobile'],
											   'message'=> $msg
							); 
							$r = array('message' 	=> 'Sucessfully updated',
										"status" 	=> TRUE  
										);
					    }else{
							$r = array('message' 	=> $this->db->_error_message(),
										"query" 	=> $this->db->last_query(),
										"status" 	=> false
										);
						}
				   }
				   if($allow){
			           $t_status  = $this->payment_modal->insertData($transData,'inter_wallet_trans');
				   		if($t_status['status']){
				   			$transDetailData['id_inter_wallet_trans'] = $t_status['insertID'];
				   			$td_status = $this->payment_modal->insertData($transDetailData,'inter_wallet_trans_detail');
				   			if($td_status['status']){
					   	   /*if(!$wallAccount['status']){
					   	        $ac_data = array(	
	                    				"available_points"  => 0,
	                    				"date_add"          => date('Y-m-d H:i:s'),
	                    				"mobile" 	 	 	=> $tran['mobile'],
	                    			);
						   		$w_status  = $this->payment_modal->insertData($ac_data,'inter_wallet_account');  
						   		if($w_status['status']){
									$allow = TRUE;
								}
						   }else{
						   		$upd_data = array(	
		                    				"available_points"  => (float)($tran['available_points']-$tran['redeemed_amount']),
		                    				"last_update"       => date('Y-m-d H:i:s'),
		                    				"mobile" 	 	 	=> $tran['mobile'],
		                    			);
						   		$w_status  = $this->payment_modal->updInterWalletAcc($upd_data);
						   		if($w_status){
									$allow = TRUE;
								}
						   } */  			   
						   		$transDetailData['trans_type'] = 2;
						   		$transDetailData['bill_no'] = $tran['txnid'];
						   		$transDetailData['id_branch'] = $tran['branch'];
						   		$updwallet = $this->payment_modal->updwallet($transDetailData,$tran['mobile']);
							   	$syncWalData = array(
													"points"  	=> (float) ($tran['available_points']-$redeemed_amount),
								    				"mobile" 	=> $tran['mobile'],
													);
								if($tran['branch'] != ''){
									foreach($this->branch as $bran){
										$syncWalData['branch_'.$bran] = 0;								
									}
								}else{
									$syncWalData['branch_1'] = 0;
								}
							    $isExist = $this->payment_modal->getSyncWalletByMobile($tran['mobile']);
								if($isExist){
									$syncWalData['last_update'] = date('Y-m-d H:i:s');
									$this->payment_modal->updateSyncWal($syncWalData);
								}else{
									$syncWalData['date_add'] = date('Y-m-d H:i:s');
									$this->payment_modal->insertData($syncWalData,'inter_sync_wallet');
								}
							//	$msg = 'Thank you for purchasing at Saravana Selvarathnam Retail Pvt Ltd . Your Wallet Balance '.number_format($tran['available_points']+$tran['redeemed_amount'],'2','.','').' points. Redeemed '.number_format($tran['redeemed_amount'],'2','.','').' points. New Wallet Balance '.number_format($tran['available_points'],'2','.','').' Points.'; 
	    						$msg = 'Thank you for purchasing at '.$this->comp['company_name'].' . Your Wallet Balance '.number_format($tran['available_points'],'2','.','').' points. Redeemed '.number_format($redeemed_amount,'2','.','').' points. New Wallet Balance '.number_format(($tran['available_points']-$redeemed_amount),'2','.','').' Points.'; 
								if($updwallet){
									$smsData = array('mobile' => $tran['mobile'],
													   'message'=> $msg
									);
								}
			   			}else{
							$r = array('message' 	=> $this->db->_error_message(),
							"query" 	=> $this->db->last_query(),
										"status" 	=> false
										);
						}
					  }else{
								$r = array('message' 	=> $this->db->_error_message(),
								"query" 	=> $this->db->last_query(),
											"status" 	=> false
											);
					  }
					}else{
						$r = array('message' 	=> $this->db->_error_message(),
						"query" 	=> $this->db->last_query(),
									"status" 	=> false
									);								
					}
			   }else{
			   		$r = array('message' 	=> $this->db->_error_message(),
								"query" 	=> $this->db->last_query(),
								"status" 	=> false
								);							
			   } 
		  	  if( $this->db->trans_status() === TRUE ){
			  	$this->db->trans_commit();
			  	$this->send_sms_wallet($smsData);
			  	$r = array('message' 	=> 'Sucessfully updated',
							"status" 	=> TRUE  ,
							"query" 	=> $this->db->last_query(),
							);
			  }else{
			  	$this->db->trans_rollback();
			  	 $r = array('message' 	=> 'Something went worng',
							"status" 	=> false  ,
							"query" 	=> $this->db->last_query(),
							);
			  }	
			   return $r;
		   }
	   }
	   $r = array('message' 	=> 'No records found',
					"status" 	=> false  
					);
	   return $r;
	} 
	/*function insertWalletTrans($tran){
	  if($tran){
		 if($tran['wallet_balance_type']==1) // wallet_balance_type : 1 -> amount
		 {
			$redeemed_amt = (($tran['redeemed_amount']/$tran['wallet_amt_per_points'])*$tran['wallet_points']);
		 }else{
		 	$redeemed_amt = $tran['redeemed_amount'];
		 }
	  	$transDetailData = array(	
		            				"amount" 		 => $tran['amount'],
		            				"date_add"       => date('Y-m-d H:i:s'),
		            				"remark" 		 => 'Debited for purchase plan payment',
		            				'trans_points' 	 => $redeemed_amt
		            			); 
	  	if($tran['walletIntegration'] == 0){ // 0 - No integration, 1 - Req integration as like SSS
	  		$transDetailData['trans_type'] = 2;
			$updwallet = $this->payment_modal->updwallet($transDetailData,$tran['mobile']);
			$r = array("status" => $updwallet);
			return $r;
		}else{
		   	   $cat_code = 'SS';
		   	   $wal_cat_settings = $this->payment_modal->getWcategorySettings($cat_code);
		   	   // Begin the transaction
		   	   $this->db->trans_begin();
			   if($wal_cat_settings){
			   	   $transData = array(	
		            				"record_to" 	 =>  1, //1 - offline , 2 - online
		            				"entry_date" 	 =>  date('Y-m-d H:i:s'), 
		            				"bill_no" 	 	 =>  $tran['txnid'],
		            				"id_branch" 	 =>  $tran['branch'],
		            				"trans_type"     =>  2,
		            				"mobile" 	 	 =>  $tran['mobile'],
		            				"is_transferred" =>  'N',
		            				"is_modified"    =>  0,
		            				"date_add"       =>  date('Y-m-d H:i:s'), 
		            				"record_type"	 =>  2// 1 - offline , 2 - online
		            			);
		           $transDetailData['id_wcat_settings'] = $wal_cat_settings['id_wcat_settings'];
	           	   $transDetailData['category_code'] = $cat_code;
		            $t_status  = $this->payment_modal->insertData($transData,'inter_wallet_trans');
			   		if($t_status['status']){
			   			$transDetailData['id_inter_wallet_trans'] = $t_status['insertID'];
			   			$td_status = $this->payment_modal->insertData($transDetailData,'inter_wallet_trans_detail');
			   			if($td_status['status']){
			   			   $wallAccount = $this->payment_modal->getInterWalletCustomer($transData['mobile']); 
			   			   $allow = FALSE; 
						   if($wallAccount['status']){
						   		$upd_data = array(	
		                    				"available_points"  => ($tran['available_points']-$redeemed_amt),
		                    				"last_update"       => date('Y-m-d H:i:s'),
		                    				"mobile" 	 	 	=> $tran['mobile'],
		                    			);
						   		$w_status  = $this->payment_modal->updInterWalletAcc($upd_data);
						   		if($w_status){
									$allow = TRUE;
								}
						   }else{
						   		$transDetailData['trans_type'] = 2;
						   		$transDetailData['bill_no'] = $tran['txnid'];
						   		$transDetailData['id_branch'] = $tran['branch'];
						   		$updwallet = $this->payment_modal->updwallet($transDetailData,$tran['mobile']);
						   		$msg = 'Thank you for using '.$this->comp['company_name'].' wallet. Redeemed '.number_format($redeemed_amt,'2','.','').' points for scheme payment.'; 	
								if($updwallet){
									$smsData[] = array('mobile' => $tran['mobile'],
													   'message'=> $msg
									); 
									$r = array('message' 	=> 'Sucessfully updated',
												"status" 	=> TRUE  
												);
							    }else{
									$r = array('message' 	=> $this->db->_error_message(),
												"query" 	=> $this->db->last_query(),
												"status" 	=> false
												);
								}
						   }
						   if($allow){
						   		$transDetailData['trans_type'] = 2;
						   		$transDetailData['bill_no'] = $tran['txnid'];
						   		$transDetailData['id_branch'] = $tran['branch'];
						   		$updwallet = $this->payment_modal->updwallet($transDetailData,$tran['mobile']);
							   	$syncWalData = array(
													"points"  	=> ($tran['available_points']-$redeemed_amt),
								    				"mobile" 	=> $tran['mobile'],
													);
								if($tran['branch'] != ''){
									foreach($this->branch as $bran){
										if($tran['branch'] != $bran){
											$syncWalData['branch_'.$bran] = 0; // unsynced
										}else{
											$syncWalData['branch_'.$bran] = 1; // synced
										}								
									}
								}else{
									$syncWalData['branch_1'] = 0;
								}
							    $isExist = $this->payment_modal->getSyncWalletByMobile($tran['mobile']);
								if($isExist){
									$syncWalData['last_update'] = date('Y-m-d H:i:s');
									$syncWalData['id_inter_sync_wallet'] = $isExist['id_inter_sync_wallet']; 
									$this->payment_modal->updateSyncWal($syncWalData);
								}else{
									$syncWalData['date_add'] = date('Y-m-d H:i:s');
									$this->payment_modal->insertData($syncWalData,'inter_sync_wallet');
								}
	    					$msg = 'Thank you for purchasing at '.$this->comp['company_name'].' . Your Wallet Balance '.number_format($tran['available_points'],'2','.','').' points. Redeemed '.number_format($redeemed_amt,'2','.','').' points. New Wallet Balance '.number_format(($tran['available_points']-$redeemed_amt),'2','.','').' Points.'; 	
								if($updwallet){
									$smsData[] = array('mobile' => $tran['mobile'],
													   'message'=> $msg
									);
								}
						   }else{
								$r = array('message' 	=> $this->db->_error_message(),
								"query" 	=> $this->db->last_query(),
											"status" 	=> false
											);
							}
			   			}else{
							$r = array('message' 	=> $this->db->_error_message(),
							"query" 	=> $this->db->last_query(),
										"status" 	=> false
										);
						}
					}else{
						$r = array('message' 	=> $this->db->_error_message(),
						"query" 	=> $this->db->last_query(),
									"status" 	=> false
									);
					}
			   }else{
			   		$r = array('message' 	=> $this->db->_error_message(),
								"query" 	=> $this->db->last_query(),
								"status" 	=> false
								);
			   } 
		  	  if( $this->db->trans_status() === TRUE ){
			  	$this->db->trans_commit();
			  	$this->send_sms_wallet($smsData);
			  	$r = array('message' 	=> 'Sucessfully updated',
							"status" 	=> TRUE  
							);
			  }else{
			  	$this->db->trans_rollback();
			  	 $r = array('message' 	=> 'Something went worng',
							"status" 	=> false  
							);
			  }
			   return $r;
		   }
	   }
	   $r = array('message' 	=> 'No records found',
					"status" 	=> false  
					);
	   return $r;
	} */
	function send_sms_wallet($data){
	    $serviceID = 17;
		$service = $this->services_modal->checkService($serviceID);
		if($service['sms'] == 1)
		{
		    $this->load->model('sms_model'); 
    		$mobile =$data['mobile'];
    		$message = $data['message'];
    		if($this->config->item('sms_gateway') == '1'){
    		    $this->sms_model->sendSMS_MSG91($mobile,$message,'',$service['dlt_te_id']);		
    		}
    		elseif($this->config->item('sms_gateway') == '2'){
    	        $this->sms_model->sendSMS_Nettyfish($mobile,$message,'trans');	
    		}
		}
		if($service['serv_whatsapp'] == 1)
		{
        	$sendotp = $this->services_modal->send_whatsApp_message($mobile,$message);
        }
		return TRUE;		
	}
	function getNotifications_get(){ 
	    $model = self::MOD_MOB; 
	    $data = array();
	    if($this->get('cusID') != ''){
	        $data = $this->$model->getNotifications($this->get('cusID'),$this->get('lastNotiID'));
	    }
		$this->response($data,200);
	}
	function ajax_getproducts_post()
	{ 
		$model = self::MOD_MOB;
		$id_product = $this->input->post('id_product');	
		$data = $this->$model->get_products($id_product);  
		$this->response($data,200);
	}
		function productenquriy_post()
    {	   
        $model = self::MOD_MOB;
		$payData = $this->get_values();
        $insData = array('mobile'       =>$payData['mobile'],
                         'first_name'   =>$payData['firstname'],
                         'last_name'    =>$payData['lastname'],
                         'email'  	    => $payData['email'],
                         'id_product'  	=> $payData['id_product'],
                         'date_add'     => date('Y-m-d H:i:s'),
                         'message'  	=> $payData['message']);
        $result = $this->$model->insProduct_enquiry($insData);
        if($result['status'] == true){
           $to = $this->comp['email']; 
            //$to = "karthik@vikashinfosolutions.com"; 
            //$bcc ="pavithra@vikashinfosolutions.com";	
            $bcc="";
            $subject = "Reg - ".$this->comp['company_name']." customer enquiry"; 
            $insData['product_name']=$payData['productname'];
           //print_r($insdata);exit;
            $message = $this->load->view('include/emailEnquiry',$insData,true); 
            $sendEmail = $this->email_model->send_email($to,$subject,$message,$bcc,""); 
            $this->response(array('status' => true ,'msg' => 'Thanks for your Product Enquiry'));
        } else {
            $this->response(array('status' => false ,'msg' => 'Please try after sometime'));
        } 
    }
    public function getRegBranches_get()
	{ 
		$sd_playURL = "https://play.google.com/store/apps/details?id=com.sktm.gss.savingscheme&hl=en";
		$sd_itunes = "https://play.google.com/store/apps/details?id=com.sktm.gss.savingscheme&hl=en";
		$sd_msg = "Kindly install Swarna Dharaa app to register with selected branch.";
		$scm_playURL = "https://play.google.com/store/apps/details?id=com.scm.winchit";
		$scm_itunes = "https://itunes.apple.com/us/app/tktm/id1229733970?ls=1&mt=8";
		$scm_msg = "Kindly install SKTM Mayil app to register with selected branch.";
		$tktm_playURL = "https://play.google.com/store/apps/details?id=com.sktm.winchit";		
		$tktm_itunes = "https://itunes.apple.com/us/app/tktm/id1229733970?ls=1&mt=8";
		$tktm_msg = "Kindly install TKTM app to register with selected branch."; 
		$otherBranches = array(
								array( 
									"name"		=> 'Karur', 
									"playstore"	=> $tktm_playURL,
									"itunes"	=> $tktm_itunes,
									"msg"		=> $tktm_msg,
									"group"		=> "TKTM",
								 ),
								 array( 
									"name"		=> 'Ooty', 
									"playstore"	=> $tktm_playURL,
									"itunes"	=> $tktm_itunes,
									"msg"		=> $tktm_msg,
									"group"		=> "TKTM",
								 ),array
								 ( 
									"name"		=> 'Trichy', 
									"playstore"	=> $tktm_playURL,
									"itunes"	=> $tktm_itunes,
									"msg"		=> $tktm_msg,
									"group"		=> "TKTM",
								 ),array( 
									"name"		=> 'Vellore', 
									"playstore"	=> $tktm_playURL,
									"itunes"	=> $tktm_itunes,
									"msg"		=> $tktm_msg,
									"group"		=> "TKTM",
								 ),array
								 ( 
									"name"		=> 'Kumbakonam', 
									"playstore"	=> $tktm_playURL,
									"itunes"	=> $tktm_itunes,
									"msg"		=> $tktm_msg,
									"group"		=> "TKTM",
								 ),array( 
									"name"		=> 'Thanjavur', 
									"playstore"	=> $tktm_playURL,
									"itunes"	=> $tktm_itunes,
									"msg"		=> $tktm_msg,
									"group"		=> "TKTM",
								 ),array
								 ( 
									"name"		=> 'Pudukottai', 
									"playstore"	=> $tktm_playURL,
									"itunes"	=> $tktm_itunes,
									"msg"		=> $tktm_msg,
									"group"		=> "TKTM",
								 ),array
								 ( 
									"name"		=> 'Villupuram', 
									"playstore"	=> $scm_playURL,
									"itunes"	=> $scm_itunes,
									"msg"		=> $scm_msg,
									"group"		=> "SCM",
								 ),array
								 ( 
									"name"		=> 'Puducherry', 
									"playstore"	=> $scm_playURL,
									"itunes"	=> $scm_itunes,
									"msg"		=> $scm_msg,
									"group"		=> "SCM",
								 ),array
								 ( 
									"name"		=> 'Chennai - Chrompet', 
									"playstore"	=> $sd_playURL,
									"itunes"	=> $sd_itunes,
									"msg"		=> $sd_msg,
									"group"		=> "SD",
								 ),array
								 ( 
									"name"		=> 'Chennai - Velachery', 
									"playstore"	=> $sd_playURL,
									"itunes"	=> $sd_itunes,
									"msg"		=> $sd_msg,
									"group"		=> "SD",
								 ),array
								 ( 
									"name"		=> 'Covai', 
									"playstore"	=> $sd_playURL,
									"itunes"	=> $sd_itunes,
									"msg"		=> $sd_msg,
									"group"		=> "SD",
								 ),array
								 ( 
									"name"		=> 'Covai - Vivagaa', 
									"playstore"	=> $sd_playURL,
									"itunes"	=> $sd_itunes,
									"msg"		=> $sd_msg,
									"group"		=> "SD",
								 ),array
								 ( 
									"name"		=> 'Erode', 
									"playstore"	=> $sd_playURL,
									"itunes"	=> $sd_itunes,
									"msg"		=> $sd_msg,
									"group"		=> "SD",
								 ),array
								 ( 
									"name"		=> 'HYD - Kukatpally', 
									"playstore"	=> $sd_playURL,
									"itunes"	=> $sd_itunes,
									"msg"		=> $sd_msg,
									"group"		=> "SD",
								 ),array
								 ( 
									"name"		=> 'HYD - Mehdipatnam', 
									"playstore"	=> $sd_playURL,
									"itunes"	=> $sd_itunes,
									"msg"		=> $sd_msg,
									"group"		=> "SD",
								 ),array
								 ( 
									"name"		=> 'Madurai', 
									"playstore"	=> $sd_playURL,
									"itunes"	=> $sd_itunes,
									"msg"		=> $sd_msg,
									"group"		=> "SD",
								 ),array
								 ( 
									"name"		=> 'Namakkal', 
									"playstore"	=> $sd_playURL,
									"itunes"	=> $sd_itunes,
									"msg"		=> $sd_msg,
									"group"		=> "SD",
								 ),array
								 ( 
									"name"		=> 'Rajapalayam', 
									"playstore"	=> $sd_playURL,
									"itunes"	=> $sd_itunes,
									"msg"		=> $sd_msg,
									"group"		=> "SD",
								 ),array
								 ( 
									"name"		=> 'Salem', 
									"playstore"	=> $sd_playURL,
									"itunes"	=> $sd_itunes,
									"msg"		=> $sd_msg,
									"group"		=> "SD",
								 ),array
								 ( 
									"name"		=> 'Thiruvallur', 
									"playstore"	=> $sd_playURL,
									"itunes"	=> $sd_itunes,
									"msg"		=> $sd_msg,
									"group"		=> "SD",
								 ),array
								 ( 
									"name"		=> 'Udumalaipet', 
									"playstore"	=> $sd_playURL,
									"itunes"	=> $sd_itunes,
									"msg"		=> $sd_msg,
									"group"		=> "SD",
								 ),
							   );
		$model = self::MOD_MOB;
		$data['branch'] = $this->get_branch();
		$data['otherbr'] = $otherBranches;
	    echo json_encode($data); 
	}
	function giftIssuedByCusId_get()
	{
		$model = self::MOD_MOB;
		$id_customer = $this->get('id_customer'); 
	    $result =	$this->$model->get_giftsAccwise($id_customer);  
		$this->response($result, 200);	
	}
	function giftIssuedByAcId_get()
	{
		$model = self::MOD_MOB;
		$id_scheme_ac = $this->get('id_scheme_ac'); 
	    $result =	$this->$model->get_giftsListAccwise($id_scheme_ac);  
		$this->response($result, 200);	
	}
	function getModules_get(){ 
	    $model = self::MOD_MOB;  
	    $result['modules'] = $this->$model->getModules();
		$this->response($result,200);
	}
    // complaint listing
    function custComplaints_get()
	{ 
		$model = self::MOD_MOB;
		$result= $this->$model->get_custComplaints($this->get('id_customer')); 
		$this->response($result,200);
	}
	function custComplaintStatus_get()
	{ 
		$model = self::MOD_MOB;
		$result= $this->$model->get_custComplaintStatus($this->get('id_enquiry')); 
		$this->response($result,200);
	}
	// DTH listing
    function custDTHRequests_get()
	{ 
		$model = self::MOD_MOB;
		$result= $this->$model->get_custDTHRequests($this->get('id_customer'));  
		$this->response($result,200);
	}
	function custDTHStatus_get()
	{ 
		$model = self::MOD_MOB;
		$result= $this->$model->get_custComplaintStatus($this->get('id_enquiry')); 
		$this->response($result,200);
	}
	// Coin enquiry listing
    function custCoinEnq_get()
	{ 
		$model = self::MOD_MOB;
		$result= $this->$model->get_custCoinEnq($this->get('mobile')); 
		$this->response($result,200);
	}
	function custCoinEnqStatus_get()
	{ 
		$model = self::MOD_MOB;
		$result= $this->$model->get_custCoinEnqStatus($this->get('id_enquiry')); 
		$this->response($result,200);
	}
	function test_get(){
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://coimbatorejewellery.in/wcrm/v4_1/index.php/mobile_api/rate_history",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 300,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "{\r\n  \"branch_settings\": \"1\",\r\n  \"id_branch\": 28,\r\n  \"from\": \"2019-12-01\",\r\n  \"to\": \"2019-12-03\"\r\n}",
          CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Postman-Token: 4bfdb38a-121c-4684-a49d-891e818b54fe",
            "cache-control: no-cache"
          ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          echo $response;
        }
	}
	function rate_history_post(){
	    $model = self::MOD_MOB;
		$postData = $this->get_values();
		$result = array();
		$LMX_ratehistory = array();
    	$EJ_ratehistory  = array();
		$from_date = $postData['from'];
		$to_date = $postData['to'];
	    if(strtotime($from_date) < strtotime('01/14/2020') && strtotime($to_date) < strtotime('01/14/2020') ){
    		$EJ_ratehistory = $this->$model->ej_rate_history($postData['id_branch'],$postData['from'],$postData['to'],$postData['branch_settings'],'ej');
	    }
	    elseif(strtotime($from_date) < strtotime('01/14/2020') && strtotime($to_date) >= strtotime('01/14/2020') ){
    		$LMX_ratehistory =  $this->$model->ej_rate_history($postData['id_branch'],$postData['from'],$postData['to'],$postData['branch_settings'],'lmx');
    		$EJ_ratehistory =  $this->$model->ej_rate_history($postData['id_branch'],$postData['from'],$postData['to'],$postData['branch_settings'],'ej');
	    }
	    elseif(strtotime($from_date) >= strtotime('01/14/2020')){
    		$LMX_ratehistory =  $this->$model->ej_rate_history($postData['id_branch'],$postData['from'],$postData['to'],$postData['branch_settings'],'lmx');
	    }
		$goldrate_22ct = array();
		$silverrate_1gm = array();
		$platinum_1g = array();
        if(sizeof($EJ_ratehistory) > 0)
        {
            foreach($EJ_ratehistory as $row)
            {
                if($row['goldrate_22ct']>0)
                {
                    $goldrate_22ct[]=array(
                    'updatetime'=>$row['updatetime'],
                    'goldrate_22ct'=>$row['goldrate_22ct']
                    );
                }
            }
            foreach($EJ_ratehistory as $row)
            {
                if($row['silverrate_1gm']>0)
                {
                    $silverrate_1gm[]=array(
                    'silverrate_1gm'=>$row['silverrate_1gm']
                    );
                }  
            }
            foreach($EJ_ratehistory as $row)
            {
                         if($row['platinum_1g']>0)
                         {
                               $platinum_1g[]=array(
                                'platinum_1g'=>$row['platinum_1g']
                                );
                         }
            }
              $gold_rate_length=count($goldrate_22ct);
              for($i=0;$i<$gold_rate_length;$i++)
              {
                     if(isset($platinum_1g[$i]['platinum_1g']))
                     {
                         $previous_platinum=$platinum_1g[$i]['platinum_1g'];
                     }
                  $result[$i]['updatetime'] = $goldrate_22ct[$i]['updatetime'] ;
                  $result[$i]['goldrate_22ct'] = $goldrate_22ct[$i]['goldrate_22ct'] ;
                  $result[$i]['silverrate_1gm'] = $silverrate_1gm[$i]['silverrate_1gm'];
                  $result[$i]['platinum_1g'] = (isset($platinum_1g[$i]['platinum_1g']) ? $platinum_1g[$i]['platinum_1g']:$previous_platinum); 
              } 
        }
        $response = array_merge($LMX_ratehistory,$result);
        //$result['q'] = $this->db->last_query();
		$this->response($response,200);	
	}
	function uploadAadhar_post(){
    	$this->load->helper(array('form','url'));
    	$data = json_decode(json_encode($arrdata), FALSE); 
        $cus_id = $data->id_customer;
    	$file_path = self::CUS_ADHAR_PATH; 
    	if (!is_dir($file_path)) {
    		mkdir($file_path, 0777, TRUE);
    	}
    	$config['encrypt_name'] = FALSE;
        // set path to store uploaded files
        $config['upload_path'] =  $file_path;
        // set allowed file types
        $config['allowed_types'] = 'pdf';
        // set upload limit, set 0 for no limit
        $config['max_size']    = 20000;
        // load upload library with custom config settings
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        // if upload failed , display errors
        if (!$this->upload->do_upload('file'))
        {
            //print_r($this->upload->display_errors());exit;.
            $res =  array('status'=>false,'file_name'=>null);
            $this->response($res,200);	
        }
        else
        {
            $result = $this->upload->data();
            $res =  array('status'=>true,'file_name'=>$result['file_name']);
            $this->response($res,200);	
        }
    }
    function insertkyc_post()
    {
      $kycData = $this->get_values();	 
      /*if(sizeof($kycData) == 0){ // Verify aadhaar without storing pdf in server
          $kycData = json_decode(json_encode($_POST), FALSE); 
      } */
      $model = self::MOD_MOB;
      $approval_type = "Auto"; 
      if($kycData['kyc_type'] !='' ){
        $insData = array('id_customer'  => (isset($kycData['id_customer'])?$kycData['id_customer']:NULL),
             'kyc_type'             => (isset($kycData['kyc_type'])?$kycData['kyc_type']:NULL),
             'number'               => (isset($kycData['number'])?$kycData['number']:NULL),
             'name'                 => (isset($kycData['name'])?$kycData['name']:NULL),
             'bank_ifsc'  	        => (isset($kycData['bank_ifsc'])?$kycData['bank_ifsc']:NULL),
             'status'               => 0,
             'emp_verified_by'      => (isset($kycData['emp_verified_by'])?$kycData['emp_verified_by']:NULL),
             'date_add' 	        => date('Y-m-d H:i:s'),
             'dob'                  => (isset($kycData['dob'])?$kycData['dob']:NULL),
             'bank_branch'          => (isset($kycData['bank_branch'])?$kycData['bank_branch']:NULL)
             );
            if($kycData['kyc_type'] == 1){
            	if($approval_type == "Auto"){
					$response = $this->zoopapiCurl("verify-bank",array("Account"=>$kycData['number'],"IFSC"=>$kycData['bank_ifsc']));
					if(isset($response->statusCode)){ 
						$data['kyc_status'] = 0; 
						$data['status'] = FALSE; 
						$data['msg'] = $response->message;
					}
					else{
						if($response->transaction_status == 1){
							if($response->data->Status == "VERIFIED"){
								$insData['status'] = 2;
								$insData['name'] = $response->data->BeneName;
								$insData['verification_type'] = 2; // 1-Manual,2-Auto
								$data = $this->$model->insert_kyc($insData);
								if($data['status'] == TRUE){
									$this->load->model("mobileapi_model");
									$data['kyc_status'] = $this->mobileapi_model->checkCusKYC($insData['id_customer']); 
									$data['msg'] = "Bank Account verified successfully";
								} 
							}else{
								$data['kyc_status'] = 0;
								$data['status'] = FALSE; 
								$data['msg'] = "Invalid Bank Details "; 
							}
						}elseif($response->transaction_status == 2){
							$data['kyc_status'] = 0;
							$data['status'] = FALSE; 
							if(isset($response->data->Remark)){
								$data['msg'] = $response->data->Remark; 
							}else{
								$data['msg'] = $response->response_message;
							}
						}
					}
				}else{
				    $insData['verification_type'] = 1; // 1-Manual,2-Auto
					$data = $this->$model->insert_kyc($insData);
					if($data['status'] == TRUE){
						$this->load->model("mobileapi_model");
						$data['kyc_status'] = $this->mobileapi_model->checkCusKYC($insData['id_customer']); 
						$data['msg'] = "Bank Account details submitted successfully. Verification in progress. Keep in touch with us. ";
					}else{
						$data['kyc_status'] = 0 ;
						$data['status'] = FALSE; 
						$data['err'] = $this->db->_error_message(); 
						$data['qry'] = $this->db->last_query(); 
						$data['msg'] = "Unable to submit KYC. Kindly contact Administrator."; 
					} 
				}                 
			}
			else if($kycData['kyc_type'] == 2){ 
				if($approval_type == "Auto"){
					$response = $this->zoopapiCurl("pan",array("pan" => $insData['number']));
					if(isset($response->statusCode)){
					    $data['kyc_status'] = 0;
						$data['status'] = FALSE; 
						$data['msg'] = $response->message;
					}
					elseif($response->response_code == 1){
						$res = $response->data[0];
						if($res->pan_status == "VALID"){
							$insData['name'] = $res->first_name.' '.$res->last_name;
							$insData['status'] = 2;
							$insData['verification_type'] = 2; // 1-Manual,2-Auto
							$data = $this->$model->insert_kyc($insData);
							if($data['status'] == TRUE){
								$this->load->model("mobileapi_model");
								$data['kyc_status'] = $this->mobileapi_model->checkCusKYC($insData['id_customer']); 
								$data['msg'] = "PAN Card verified successfully";
							} 
						}else{
							$data['status'] = FALSE; 
							$data['msg'] = "Invalid PAN Details "; 
						}
					}
				}else{
				    $insData['verification_type'] = 1; // 1-Manual,2-Auto
					$data = $this->$model->insert_kyc($insData);
					if($data['status'] == TRUE){
						$this->load->model("mobileapi_model");
						$data['kyc_status'] = $this->mobileapi_model->checkCusKYC($insData['id_customer']); 
						$data['msg'] = "PAN Card details submitted successfully. Verification in progress. Keep in touch with us. ";
					}else{
						$data['kyc_status'] = 0 ;
						$data['status'] = FALSE; 
						$data['msg'] = "Invalid PAN Card Details "; 
					} 
				} 
			}
			else if($kycData['kyc_type'] == 3) // Aadhar
			{	
                //$b64Doc = chunk_split(base64_encode(file_get_contents($_FILES['file']['tmp_name'])));
                $file_path = base_url()."".self::CUS_ADHAR_PATH."".$kycData['file_name'];
               // echo $file_path;exit;
                $b64Doc = (base64_encode(file_get_contents($file_path)));
        //   $this->response(array( "mode" => "pdf","file" => $b64Doc,"password" => $kycData['pdf_password'],"purpose" => 'For Purchase Plan KYC verification',"request_consent" => 'Y'),200);
				if($approval_type == "Auto"){
					$response = $this->zoopapiCurl("extract-aadhaar-data",array( "mode" => "pdf","file" => $b64Doc,"password" => $kycData['pdf_password'],"purpose" => 'For Purchase Plan KYC verification',"request_consent" => 'Y'));
				//	var_dump($response);
					if(isset($response->statusCode)){
						$data['status'] = FALSE; 
						$data['msg'] = $response->message;
						$data['kyc_status'] = 0 ;
					}
					elseif($response->transaction_status == 5){
						$res = $response->result->BasicInfo; 
                        $date = str_replace('/', '-', $res->DOB);
                        $dob = date('Y-m-d', strtotime($date));
						$insData['name'] = $res->Name;
						$insData['number'] = $response->result->AadhaarInfo;
						$insData['dob'] = $dob ;
						$insData['status'] = 2; 
						$insData['verification_type'] = 2; // 1-Manual,2-Auto
						$data = $this->$model->insert_kyc($insData);
						if($data['status'] == TRUE){
							$this->load->model("mobileapi_model");
							$data['kyc_status'] = $this->mobileapi_model->checkCusKYC($insData['id_customer']); 
							$data['msg'] = "Aadhaar Details verified successfully";
						} 
					}else{
						$data['kyc_status'] = 0 ;
						$data['status'] = FALSE; 
						$data['msg'] = "There was some issue, please try after sometime .."; 
					}
					unlink( $file_path.''.$kycData['file_name'] );
				}else{
				    $insData['verification_type'] = 1; // 1-Manual,2-Auto
					$data = $this->$model->insert_kyc($insData);
					if($data['status'] == TRUE){
						$this->load->model("mobileapi_model");
						$data['kyc_status'] = $this->mobileapi_model->checkCusKYC($insData['id_customer']); 
						$data['msg'] = "Aadhaar details submitted successfully. Verification in progress. Keep in touch with us. ";
					}else{
						$data['kyc_status'] = 0 ;
						$data['status'] = FALSE; 
						$data['msg'] = "Invalid Aadhaar Details "; 
					} 
				}
			}
			else if($kycData['kyc_type'] == 4){ // Driving Licence
				if($approval_type == "Auto"){
				    /*$date = date_create($insData['dob']);
                    $dob = date_format($date,"d-m-Y");*/
					$response = $this->zoopapiCurl("verify-dl-advance/v2",array("dl_no" => $insData['number'],"consent" => "Y" , "consent_text" => "For Purchase Plan KYC verification"));
					if(isset($response->statusCode)){
					    $data['kyc_status'] = 0;
						$data['status'] = FALSE; 
						$data['msg'] = $response->message;
					}
					else{
						if($response->transaction_status == 1){
							if($response->response_msg == "Success"){
								$insData['status'] = 2; 
								$insData['number'] = $response->result->dlNumber;
								$insData['dob'] = date("Y-m-d",strtotime($response->result->dob));
								$insData['address'] = $response->result->address[0]->completeAddress;
								$insData['verification_type'] = 2; // 1-Manual,2-Auto
								$data = $this->$model->insert_kyc($insData);
								$data['query'] = $this->db->last_query();
								if($data['status'] == TRUE){
									$this->load->model("mobileapi_model");
									$data['kyc_status'] = $this->mobileapi_model->checkCusKYC($insData['id_customer']); 
									$data['msg'] = "Driving Licence verified successfully";
								} 
							}else{
								$data['kyc_status'] = 0;
								$data['status'] = FALSE; 
								$data['msg'] = "Invalid Driving Licence "; 
							}
						}elseif($response->transaction_status == 0){
							$data['kyc_status'] = 0;
							$data['status'] = FALSE; 
							if(isset($response->error_message)){
								$data['msg'] = $response->data->error_message; 
							}else{
								$data['msg'] = $response->response_msg;
							}
						}
					}
				}else{
				    $insData['verification_type'] = 1; // 1-Manual,2-Auto
					$data = $this->$model->insert_kyc($insData);
					if($data['status'] == TRUE){
						$this->load->model("mobileapi_model");
						$data['kyc_status'] = $this->mobileapi_model->checkCusKYC($insData['id_customer']); 
						$data['msg'] = "Driving Licence details submitted successfully. Verification in progress. Keep in touch with us. ";
					}else{
						$data['kyc_status'] = 0 ;
						$data['status'] = FALSE; 
						$data['msg'] = "Invalid Driving Licence Details "; 
					} 
				} 
			}
			$data['kyc_type_status'] =($insData['status']==0? 'Pending':( $insData['status']==2 ? 'Verified':( $insData['status']==3 ? 'Rejected':($insData['status']==1 ? 'In Progress':''))));
			$data['color']=($insData['status']==0 ? 'medium':( $insData['status']==2 ? 'success':( $insData['status']==3 ? 'danger':($insData['status']==1 ? 'warning':''))));
			/*$data['qry'] = $this->db->last_query();
			$data['err'] = $this->db->_error_message();*/
            $this->response($data);
      }       
      else {
            $this->response(array('status' => false ,'msg' => 'KYC type is empty', 'kyc_status' => 0));
      } 
    }
    /**
	* 	ZOOP API CURL Call
	* 	Documentation : https://docs.aadhaarapi.com/?version=latest
	* 
	* @return
	*/
	function zoopapiCurl($api,$postData){
//	   print_r(json_encode($postData));
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $this->config->item('zoop_url')."".$api,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 60,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => json_encode($postData),
		  CURLOPT_HTTPHEADER => array(
		    "Content-Type: application/json",
		    "cache-control: no-cache",
		    "qt_agency_id: ".$this->config->item('agency_id'),
		    "qt_api_key: ".$this->config->item('api_key')
		  ),
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
//exit;
		if ($err) { 
		  echo "cURL Error #:" . $err;exit;
		} else {
		 //   echo $response;exit;
		  return json_decode($response);
		}
	}
    function readKyc_get()
	{ 
		$model = self::MOD_MOB;
		$result['data']= $this->$model->get_kycstatus($this->get('id_customer'));  
		$result['aadhaarlink']='https://eaadhaar.uidai.gov.in/#/';
		$this->response($result,200);
	} 
	// Store Locator Listing
	function getAllBranchDetail_get()
	{ 
		$model = self::MOD_MOB;
		$result = $this->$model->branch_details();
		$this->response($result,200);
	}
    function testCURL_get(){
        $this->load->model('sms_model');
        $send =  $this->sms_model->sendSMS_Nettyfish("7010198473","Welcome to jewelone.",'trans');
        var_dump($send);
    }
	function sch_enquiry_post()
    {
        $model = self::MOD_MOB;
		$data = $this->get_values();
		$sch_data=array(
		                'id_customer'     =>$data['id_customer'],
		                'id_scheme'      =>$data['id_scheme'],
		                'intresred_amt' =>(isset($data['intresred_amt']) ?$data['intresred_amt'] :0),
		                'intrested_wgt' =>(isset($data['intrested_wgt']) ?$data['intrested_wgt'] :0),
		                'message'       =>(isset($data['message']) ?$data['message'] :0),
		                'enquiry_date'     => date('Y-m-d H:i:s'),
		                );
		$customer = $this->$model->insert_sch_enquiry($sch_data);
		if($customer['status']==true)
		{
		            $cus_data=$this->$model->get_customerProfile($data['id_customer']);
		            $to = $this->comp['email']; 
    	        	$sch['type'] = 3;
    	        	$sch['name'] =$cus_data['firstname'];
    	        	$sch['amount']=$data['intresred_amt'];
    	        	$data['weight']=$data['interseted_weight'].' '.'Grams';
    	        	$cc =  'karthik@vikashinfosolutions.com';
    	        	$data['company'] =$this->comp;
					$subject = "Reg.  ".$this->comp['company_name']." scheme Enquiry";
					$message = $this->load->view('include/emailScheme',$sch,true);
					$sendEmail = $this->email_model->send_email($to,$subject,$message,$cc,"");
		}
		 $this->response($customer,200);
    }
    function sync_existing_data($mobile,$id_customer,$id_branch,$page)
	{   
	    if($id_customer > 0 && strlen($mobile) == 10 ){
	       if (!file_exists('log/existing')) {
	            mkdir('log/existing', 0777, true);
	       }
	       $log_path = 'log/existing/'.date("Y-m-d").'.txt'; 
	       $allow_sync = false;
	       $last_sync_time = $this->registration_model->getLastSyncTime($mobile);
	       if(!empty($last_sync_time)){
	           $fifteen_min_earlier = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s")) - (15 * 60));
	           if(strtotime($last_sync_time) <= strtotime($fifteen_min_earlier)){
	               $allow_sync = true;
	           }else{
	               $allow_sync = false;
	           }
	       }else{
	           $allow_sync = true;
	       }
	       $this->registration_model->updateLastSyncTime($id_customer);
		   
	       if($allow_sync){
	    	   $data['id_customer'] = $id_customer;  
	    	   $data['id_branch'] = $id_branch;  
	    	   $data['branch_code'] = ($id_branch > 0 && $this->config->item("integrationType") == 4 ? $this->registration_model->getBranchCode($id_branch) : NULL);  
	    	   $data['branchWise'] = 0;  
	    	   $data['mobile'] = $mobile;  
	    	   $data['added_by'] = 2;
	    	   $res = $this->registration_model->insExisAcByMobile($data);  
	    	   $TESTRes = array("status" => "On ENTER", "e" => $this->db->_error_message() ,"q" => $this->db->last_query(), "res" => $res, "data" => $data);
	    	   $logData = "\n".date('d-m-Y H:i:s')."\n API : mobile_api \n Response : ".json_encode($TESTRes,true);
	    	   file_put_contents($log_path,$logData,FILE_APPEND | LOCK_EX);
	    	   if(sizeof($res) > 0)
	    	   {
	    	   		$payData = $this->registration_model->syncPayData($res);  
	    	   	    if((sizeof($payData['succeedIds']) > 0 || $payData['no_records'] > 0) && $this->db->trans_status() === TRUE){
	    				$status = $this->registration_model->updateInterTableStatus($res,$payData['succeedIds']);
	    				if($status === TRUE && $this->db->trans_status() === TRUE)
	    				{
	    				    $this->db->trans_commit();
	    					/*echo $this->db->_error_message();
	    					echo $this->db->last_query();*/
	    					return array("status" => TRUE, "msg" => "Purchase Plan registered successfully"); 
	    				}
	    				else{
	    				    $this->db->trans_rollback();
	    				    $response = array("status" => FALSE, "e" => $this->db->_error_message() ,"q" => $this->db->last_query() ,"msg" => "Error in updating intermediate tables");
	    				    $logData = "\n".date('d-m-Y H:i:s')."\n API : mobile_api \n Response : ".json_encode($response,true);
	                        file_put_contents($log_path,$logData,FILE_APPEND | LOCK_EX);
	    					return $response;
	    				}
	    			}
	    			else
	    			{
	    			    $response = array("status" => FALSE, "e" => $this->db->_error_message() ,"q" => $this->db->last_query() ,"msg" => "Error in updating payment tables");
	    			    $logData = "\n".date('d-m-Y H:i:s')."\n API : mobile_api \n Response : ".json_encode($response,true);
	    			    file_put_contents($log_path,$logData,FILE_APPEND | LOCK_EX);
	    				$this->db->trans_rollback();
	    				return $response;
	    			}
	    	   }
	    	   else
	    	   {
	    	        $response = array("status" => FALSE, "id_customer" => $id_customer ,"mobile" => $mobile , "branch_code" => $data['branch_code'], "msg" => "No records to update in scheme account tables");
	    	        if($this->db->trans_status() === TRUE)
	    		    {
	    				$this->db->trans_commit();
	    		    }else{
	    		        echo $this->db->_error_message();
	    		        $this->db->trans_rollback();
	    		    }
	    		    $logData = "\n".date('d-m-Y H:i:s')."\n API : mobile_api \n Response : ".json_encode($response,true);
	    		    file_put_contents($log_path,$logData,FILE_APPEND | LOCK_EX);
	    	   		return $response;
	    	   } 
	    	}else{
	    	    $logData = "\n".date('d-m-Y H:i:s')."\n API : mobile_api \n sync called less than 15 min. CUS ID : ".$id_customer." | ".$mobile." | BRN ID :".$id_branch." | ".$page;
	    		file_put_contents($log_path,$logData,FILE_APPEND | LOCK_EX);
	    		return $logData;
	    	}
	    }else{
	        return array("status" => FALSE, "msg" => "Invalid customer data");
	    }
	}
	// To Get Bearer Token for ERP api call
	public function getBearerToken(){
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('erp_baseURL')."loginRequest",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 300,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "UserName=".$this->config->item('ejUserName')."&Password=".$this->config->item('ejPassword')."&grant_type=password",
          CURLOPT_HTTPHEADER => array(
            "Content-Type: application/x-www-form-urlencoded",
            "cache-control: no-cache"
          ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
          echo "cURL Error #:" . $err;
          return false;
        } else {
            $result =  json_decode($response);
            return $result->access_token; 
        }
	}
	function verifyRateFixOTP_post()
	{
		$model = self::MOD_MOB;
		$result ="";
		$data = $this->get_values();
		$otp_time = date('Y-m-d H:i:s');
		if($data['sysotp'] == $data['userotp'])
		{
		    if($otp_time <= date('Y-m-d H:i:s',strtotime($data['last_otp_expiry']))){
		        if($this->config->item("integrationType") == 3){
    	            $bearer = $this->getBearerToken();
    			    if($bearer){
        				$rate = $this->$model->getGold22ct(1,$data['id_branch']);
        				if($rate != 0){
        					$postData = array((array("SchemeAccountNo" => trim($data["sch_ac_no"]),"MetalRate" => $rate,"FixRequestFrom" => 2)));
        					$curl = curl_init();   
        				    curl_setopt_array($curl, array(
        				    CURLOPT_URL => $this->config->item('erp_baseURL')."RateFixing",
        				    CURLOPT_RETURNTRANSFER => true,
        				    CURLOPT_ENCODING => "",
        				    CURLOPT_MAXREDIRS => 10,
        				    CURLOPT_TIMEOUT => 300,
        				    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        				    CURLOPT_CUSTOMREQUEST => "POST",
        				    CURLOPT_POSTFIELDS => json_encode($postData),
        				    CURLOPT_HTTPHEADER => array(
        				      //"Authorization: Basic ".$this->config->item('erpAuthKey'),
        				      "Authorization: Bearer ".$bearer,
        				      "Content-Type: application/json",
        				      "cache-control: no-cache"
        				    ),
        				   ));
        				   $response = curl_exec($curl);
        				   $err = curl_error($curl); 
        				   curl_close($curl);
        				   if ($err) {
        				    	echo "cURL Error #:" . $err;
        				    	$result =  array('is_valid' => TRUE, 'status' => FALSE, 'msg' => 'Sorry for the inconvenience, we are unable to fix rate at the moment. Try again later.');
        				   } else {
        				   		$res = json_decode($response);
        				   		if(gettype($res) == "array"){
        				   			if($res[0]->Flag == TRUE){
        								$updData = array(
        				   							"fixed_wgt" => $res[0]->BookedWeight,
        				   							"fixed_metal_rate" => $res[0]->MetalRate,
        				   							"rate_fixed_in" => $res[0]->FixRequestFrom,
        				   							"fixed_rate_on" => date("Y-m-d H:i:s", strtotime($res[0]->BookedDate))
        				   						); 
        				   				$status = $this->$model->updFixedRate($updData,$data['id_sch_ac']);
        				   				if($status){
        									$result = array('is_valid' => TRUE,'status' => TRUE, 'msg' => $res[0]->Status);
        								}else{
        									$result = array('is_valid' => TRUE,'status' => FALSE, 'msg' => 'Sorry for the inconvenience, we are unable to fix rate at the moment. Try again later..');
        								}				   				
        							}else{
        								$result = array('is_valid' => TRUE,'status' => FALSE,'msg' => $res[0]->Status);
        							}							
        						}else{ 
        							$result = array('is_valid' => TRUE,'status' => FALSE, 'msg' => 'Sorry for the inconvenience, we are unable to fix rate at the moment. Try again later...'.$response);
        						}
        				   }
        				}
        				else{ 
        					$result =  array('is_valid' => FALSE,'status' => FALSE, 'msg' => 'Sorry for the inconvenience, we are unable to fix rate at the moment. Try again later....');
        				}
    			    }else{ 
    					$result =  array('is_valid' => FALSE,'status' => FALSE, 'msg' => 'Sorry we are unable to fix rate at the moment. Try again later....');
    				}
    		    }
    		    else{ // Rate Fixed by LMX
    		        $rate = $this->$model->getGold22ct(1,$data['id_branch']);
        			if($rate != 0){
    		            $isRateFixed = $this->$model->isRateFixed($data['id_sch_ac']);
        			    if($isRateFixed['status'] == 0){
            		        $updData = array(
        	   							"fixed_wgt" => $data['amount']/$rate,
        	   							"fixed_metal_rate" => $rate,
        	   							"rate_fixed_in" => 2,
        	   							"fixed_rate_on" => date("Y-m-d H:i:s")
        	   						); 
        	   				$status = $this->$model->updFixedRate($updData,$data['id_sch_ac']);
        	   				if($status){
        						$result = array('is_valid' => TRUE,'status' => TRUE, 'msg' => "Rate fixed successfully");
        					}else{
        						$result = array('is_valid' => TRUE,'status' => FALSE, 'msg' => 'Sorry for the inconvenience, we are unable to fix rate at the moment. Try again later..');
        					}
        			    }else{
        			        $result = array('is_valid' => TRUE,'status' => TRUE, 'msg' => "Rate already fixed !!");
        			    }
        			}else{ 
    					$result =  array('is_valid' => FALSE,'status' => FALSE, 'msg' => 'Sorry for the inconvenience, we are unable to fix rate at the moment. Try again later....');
    				}
    		    }
			}
			else
			{
				$result = array('is_valid' => FALSE,'status' => FALSE, 'msg' => 'Your OTP expired');
			}
		}
		else
		{
			$result = array('is_valid' => FALSE,'status' => FALSE, 'msg' => 'Your OTP is Invalid');
		}
		$this->response($result,200);			    				
	}
	function getMyGifts_post(){
	    $model = self::MOD_MOB;
	    $data = $this->get_values();
	    $result = $this->$model->getMyGifts($data['id_customer'],$data['mobile']);
	    $this->response($result,200);	
	}
	function getGiftedCards_post(){
	    $model = self::MOD_MOB;
	    $data = $this->get_values();
	    $result = $this->$model->getGiftedCards($data['id_customer'],$data['mobile']);
	    $this->response($result,200);	
	}
	function getGiftcardstatus_get(){ //gift card payment status
		$model = self::MOD_MOB;
		$result= $this->$model->getGiftcardstatus($this->get('id_customer')); 
		$this->response($result,200);
	}
	function terms_and_conditions_get()
	{
	    $model = self::MOD_MOB;
	    $result= $this->$model->get_terms_and_conditions(); 
	    $result['general_terms'] = "For enrolling online saving plan payment system, a customer should have valid mobile number
        Installments, Duration, Benefits and Terms would be varied in accordance with the plan. To know about each plans kindly visit respective sections
        Online payment status would be updated to the customer, once settlement done from payment gateway and such delay varies based on payment mode and concern banks
        Online payment system users can get the membership card at showroom
        On completion of plan, customer can purchase Jewellery at showroom only
        The company reserves the right to alter, amend, add or delete part or whole of the privileges of the plan without prior notice";
	    $result['about']="";
		$this->response($result,200);
	}
	function getMatchingVillage_get()
    {
     	$model = self::MOD_MOB;
        $villages = $this->$model->getMatchingVillage($this->get('query'));
     	$this->response($villages,200);	
	}
	/* Video Shopping Appointment - STARTS */
	function fetchAvailableSlots_get()
	{
		$result = [];
		$this->load->model('vs_model');
		$result['slots'] = $this->vs_model->getAvailableSlots(); 
		if(sizeof($result['slots']) == 0 ){
			$result['status'] = FALSE;
			$result['msg'] = "Sorry!! Currently no appointment slots available.."; 
		}else{
			$result['status'] = TRUE;
			$result['calOptions'] = array(
										"defaultDate"	=> date("Y-m-d"),
										"minDate"		=> date('Y-m-d',strtotime("-1 days")),
										"maxDate"		=> date('Y-m-d',strtotime("+60 days")),
										"disabledDates"	=> [],
									);
			$result['msg'] = ""; 
		}
		$this->response($result,200);
	}
	function fetchApptBookings_post()
	{
		$this->load->model('vs_model');
		$data = $this->get_values();
		$result = $this->vs_model->getUserVSAppts($data['mobile']);
		$this->response($result,200);
	}
	function fetchApptBookDetail_post()
	{
		$this->load->model('vs_model');
		$data = $this->get_values();
		$result = $this->vs_model->getApptDetail($data['id_appt_request']);
		$this->response($result,200);
	}
	function bookVSAppt_post()
	{
		$this->load->model('vs_model');
		$data = $this->get_values(); 
		$settings = $this->vs_model->getSettings();
		$msg = "";
		$result = array('status' => FALSE,'msg' => "");
		$insData = array('name'         => $data['name'],
						'email'       	=> (isset($data['email']) ?$data['email']:NULL),
						'mobile'        => (isset($data['mobile']) ?$data['mobile']:NULL),
						'location'      => (isset($data['location']) ?$data['location']:NULL),
						'whats_app_no'  => (isset($data['whats_app_no']) ?$data['whats_app_no']:NULL),
						'pref_category' => (isset($data['pref_category']) ?$data['pref_category']:NULL),
						'pref_item'  	=> (isset($data['pref_item']) ?$data['pref_item']:NULL),
						'preferred_slot'=> (isset($data['preferred_slot']) ?$data['preferred_slot']:NULL),
						'description'   => (isset($data['description']) ?$data['description']:NULL),
						'created_on'    => date('Y-m-d H:i:s')
						);
		$this->db->trans_begin(); 
		$alreadyRequested = $this->vs_model->isPrevRequested($data['preferred_slot'],$data['mobile']);
		if($alreadyRequested){ 
			$result = array('status' => FALSE,'title' =>"Booking Failed" ,'msg' => "You have already requested this slot..");
		}else{
			$isAvail = $this->vs_model->isSlotAvailable($data['preferred_slot']);
			if($isAvail){ 
				if($settings['appt_auto_assign'] == 1){
					$insData['status'] = 1;
					$insData['alloted_slot'] = (isset($data['pref_slot']) ?$data['pref_slot']:NULL);
					$msg = 'Video Shopping Appointment has been placed successfully..';
					$remark = 'Appointment Created';
				}else{
					$msg = 'Your request for Video Shopping Appointment has been placed successfully.. We will contact you shortly.';
					$remark = 'Appointment Requested';
				}
				$ins = $this->vs_model->insertData($insData,'appt_request');
				$logdata = array(  
				    "id_appt_request"  => $ins['insertID'],
				    "status"	       => (isset($insData['status'])?$insData['status'] = 1:0), 
				    "event_date"       => date('Y-m-d H:i:s'),
				    "id_employee"      => NULL,
				    "remark"      	   => $remark
				   ); 
				$this->vs_model->insertData($logdata,'appt_request_log'); 
				$result = array('status' => TRUE,'title' =>"Appointment Booked", 'msg' =>$msg, "id_appt_request" => $ins['insertID']);
			}else{
				$result = array('status' => FALSE,'title' =>"Booking Failed", 'msg' => "Sorry!! Requested slot not available, please choose someother slot.."); 
			}
			if($this->db->trans_status() === TRUE && $result['status'] == TRUE){
				$this->db->trans_commit();
				/* Send Alert to Customer SMS,Email */
				$cusServData = $this->sms_model->SMS_dataByServCode('VS_REQST',$ins['insertID']);  
				if($cusServData['serv_sms'] == 1 && isset($data['mobile']) && $data['mobile'] != ''){ 
					if($this->config->item('sms_gateway') == '1'){
		    		    $this->sms_model->sendSMS_MSG91($data['mobile'],$cusServData['message'],'','');
		    		}
		    		elseif($this->config->item('sms_gateway') == '2'){
		    	        $this->sms_model->sendSMS_Nettyfish($data['mobile'],$cusServData['message'],'trans');
		    		} 
				}
				if($cusServData['serv_email'] == 1 && isset($data['email']) && $data['email'] != ''){
					$to = $data['email'];
					$subject = "Reg - ".$this->comp['company_name']." video shopping appointment";
					$data['mailData'] = $cusServData['cus_data'];
					$data['type'] = 1;
					$data['company_details'] = $this->comp;
					$message = $this->load->view('include/vs_appt',$data,true);
					$sendEmail = $this->email_model->send_email($to,$subject,$message,"",""); 
				}
				/* Send Alert to Admin SMS,Email */
				if($settings['vs_send_sms_to'] != '' && strlen($settings['vs_send_sms_to']) == 10){
					$adm_msg = "New VS appointment received.";
					if($this->config->item('sms_gateway') == '1'){
		    		    $this->sms_model->sendSMS_MSG91($settings['vs_send_sms_to'],$adm_msg,'','');		
		    		}
		    		elseif($this->config->item('sms_gateway') == '2'){
		    	        $this->sms_model->sendSMS_Nettyfish($settings['vs_send_sms_to'],$adm_msg,'trans');	
		    		}
				} 
				if($settings['vs_send_mail_to'] != ''){
					$to = $settings['vs_send_mail_to'];
					$subject = "Reg - ".$this->comp['company_name']." video shopping appointment";
					$data['mailData'] = $cusServData['cus_data'];	
					$data['type'] = 1;
					$data['company_details'] = $this->comp;
					$message = $this->load->view('include/vs_appt',$data,true); 
					$sendEmail = $this->email_model->send_email($to,$subject,$message,"",""); 
				} 
			}else{
				$this->db->trans_rollback();
				$result = array('status' => FALSE,'title' =>"Booking Failed" ,'msg' => "Unable to proceed your request. Please contact admin..");
			}
		}  
		$this->response($result,200); 
	}
	function updVSFeedback_post()
	{	   
		$data = $this->get_values();
		$this->load->model('vs_model');
		$updData = array(  'customer_feedback' => $data['customer_feedback'],
							'updated_on'    	=> date('Y-m-d H:i:s'),
							'updated_by'    	=> NULL,
							"status"	        => 4 // Closed
					    );
		$this->db->trans_begin();
		$this->vs_model->updateData($updData,'id_appt_request',$data['id_appt_request'],'appt_request'); 
		$logdata = array(  
				    "id_appt_request"  => $data['id_appt_request'],
				    "status"	       => 4, // Closed
				    "event_date"       => date('Y-m-d H:i:s'),
				    "id_employee"      => NULL,
				    "remark"      	   => "Feedback Given."
				   ); 
		$this->vs_model->insertData($logdata,'appt_request_log'); 
		if($this->db->trans_status() === TRUE){
			$this->db->trans_commit(); 
			$result = array('status' => TRUE,'msg' => "Feedback updated successfully..");	
		}else{
			$this->db->trans_rollback(); 
			$result = array('status' => TRUE,'msg' => "Unable to updated your feedback. Please contact admin..");
		}
		$this->response($result,200); 
	}
	function generateVsOTP_get()
	{			
		$model = self::MOD_MOB;
		$otp   = $this->$model->generateOTP();
		$expiry = date("d-m-Y H:i:s",strtotime($otp['expiry']));
        $msg ="Your OTP ".$otp['otp']." for ".$this->comp['company_name']." video shopping reg is valid till ".$expiry."";
		if($this->config->item('sms_gateway') == '1'){
		    $this->sms_model->sendSMS_MSG91($this->get('mobile'),$msg,'','');		
		}
		elseif($this->config->item('sms_gateway') == '2'){
	        $this->sms_model->sendSMS_Nettyfish($this->get('mobile'),$msg,'trans');	
		}
		$this->response($otp,200);		
	}
	/* Video Shopping Appointment - ENDS */
	function payDuesData_get()
	{
		$model = self::MOD_MOB;
		// Currency
		$result['currency'] = $this->$model->get_currency($this->get('id_branch'));
		// Weights
		$weights = array();
		$weights_data = $this->$model->get_weights();
	    $metalrates = $result['currency']['metal_rates'];
	    foreach($weights_data as $weight)
	    {
	    	$rate = (float) $metalrates['goldrate_22ct'] * (float) $weight['weight'];
			$result['weights'][] = array(
								'id_weight' => $weight['id_weight'],
								'weight'    => $weight['weight']		,
								'rate'      => number_format($rate,2,'.','')
							);
		}
	    // Pending Dues
	    if($this->get('id_branch')){
	    	if($this->get('id_branch') != 'undefined'){
	        	$id_branch = $this->get('id_branch');
	        }else{
				$id_branch = $this->$model->getCusBranch($this->get('id_customer'));
			}
	    }else{
	        $id_branch = $this->$model->getCusBranch($this->get('id_customer'));
	    }
		$schemeAcc = $this->$model->get_payment_details($this->get('id_customer'),'Y');
		$result['chits'] = $this->array_sort($schemeAcc['chits'], 'allow_pay',SORT_DESC);
		$result['wallet_balance'] = $this->$model->wallet_balance($this->get('id_customer'));
		//Sync Existing Data					
   	    if($this->config->item("integrationType") == 3 || $this->config->item("autoSyncExisting") == 1){
		  $syncData = $this->sync_existing_data($this->get('mobile'),$this->get('id_customer'), $id_branch,'payDuesData');
	    }
	    // Get Branch Name and ID 
	    if($result['currency']['currency']['cost_center'] == 3){
	        $result['branches'] = $this->$model->branchesData(); 
	    }
	    $cusLastPayData = $this->$model->getCusData($this->get('id_customer'));
	    if(!empty($cusLastPayData['last_payment_on'])){
	        $block_pay_mins = $result['currency']['currency']['block_pay_mins'];
	        $last_pay_sett = strtotime($cusLastPayData['last_payment_on'])+(60*$block_pay_mins);
	        //$tm = time() - (60*$result['currency']['currency']['block_pay_mins']);
	       
	        if($last_pay_sett < time()) {
                $result['show_timer'] = false;
            }else{
                $result['show_timer'] = true;
                $sec = $last_pay_sett - time(); 
                $result['remaining_sec'] = $sec;
                $result['remaining_min'] = gmdate("i:s", $sec);
                $result['timer_desc'] = "Retry payment in ";
            }
        }else{
          $result['show_timer'] = false; 
        } 
		//echo "<pre>";print_r($result);exit;
		$this->response($result, 200);			
	}
	
	/* Cashfree Auto-Debit Functions */
	function cf_subscription_post()
	{
	    $postData = $this->get_values();
	    $type = $postData['type'];
	    $id_sch_ac = $postData['id_sch_ac'];
	    $result = array('status' => FALSE,'msg' => "");
		// $type => 1 - Subscribe, 2 - UnSubscribe
		$auth_status = array(
							'INITIALIZED'	=> 1,
							'BANK_APPROVAL_PENDING' => 2,
							'ACTIVE'		=> 3,
							'ON_HOLD'		=> 4,
							'CANCELLED'    	=> 5,
							'COMPLETED'    	=> 6
						  );
		switch($type)
		{
			case '1': // Subscribe
				$planDetail		=	$this->scheme_modal->get_plan_detail($id_sch_ac);
				$subscriptionId	=	uniqid(time());
				if(sizeof($planDetail) > 0){
					if($planDetail['id_auto_debit_subscription'] > 0 ){ 
					    $result = array('status' => FALSE,'msg' => "Already subscription created, Kindly check the authorization status...");
					}else{
						$pendingIns	= $planDetail['total_installments']-$planDetail['paid_installments'];
						if($pendingIns > 0){
							$expires_on = date('Y-m-d H:i:s', strtotime("+".($pendingIns)." months", strtotime(date('Y-m-d H:i:s'))));
							$gen_email 	=  $this->random_strings(8).'@gmail.com';
							$insSubscription = array(
													"id_scheme_account"		=>	$id_sch_ac,
													"subscription_id"		=>	$subscriptionId,
													"plan_id"				=>	$planDetail['sync_scheme_code'],
													"first_charge_delay"	=>	1,
													"expires_on"			=>	$expires_on,
													"status"				=>	0,
													"created_on"			=>	date('Y-m-d H:i:s'),
													"added_by"				=>	0,  // Web App
												);
							$ins = $this->scheme_modal->insertData($insSubscription,'auto_debit_subscription');					
							if($ins['status']){
								$subscriptionData = array(
													"subscriptionId"	=>	$subscriptionId,
													"planId"			=>	$planDetail['sync_scheme_code'],
													"customerName"		=>	$planDetail['cus_name'],
													"customerEmail"		=>	!empty($planDetail['email']) ? $planDetail['email'] : $gen_email,
													"customerPhone"		=>	$planDetail['mobile'],
													"firstChargeDelay"	=>	1,
													"authAmount"		=>	1,
													"expiresOn"			=>	$expires_on,
													"returnUrl"			=>	$this->config->item('base_url').'index.php/cf_autodebit/autoDebitRURL/'.$id_sch_ac
												);
												//echo "<pre>";print_r($subscriptionData);exit;
				                $res = $this->cf_curl('',$subscriptionData,$planDetail);
				                if ($res['status'] == FALSE) {
				                   $result = array('status' => FALSE,'msg' => "Error in processing request...");
				                } 
				                else { 
				                	$response = $res['result']; 
				                    if($response->status == "OK"){ // OK - Api call success  - ERROR Failed
				                    	$updSubscription = array(
																"message"			=>	$response->message,
																"sub_reference_id"	=>	$response->subReferenceId,
																"auth_status"		=>	$auth_status[$response->subStatus],
																"auth_link"			=>	$response->authLink,
																"status"			=>	1,
																"last_update"		=>	date('Y-m-d H:i:s')
																);
										$upd = $this->scheme_modal->updateData($updSubscription,'id_auto_debit_subscription',$ins['insertID'],'auto_debit_subscription');
										$updSchAc = array(
														"auto_debit_status"	=>	$auth_status[$response->subStatus],
														"date_upd"			=>	date('Y-m-d H:i:s')
														);
										$this->scheme_modal->updateData($updSchAc,'id_scheme_account',$id_sch_ac,'scheme_account');											
										if($upd > 0){
										    $result = array('status' => TRUE,'msg' => "Subscription created successfully.. Kindly do the authorization process by clicking the Authorize button ...", 'auth_link' => $response->authLink);
										}else{
										    $result = array('status' => FALSE,'msg' => "Error in subscriptiion process, kindly contact admin...");
										}
				                    }else{
										 $result = array('status' => FALSE,'msg' => "Error in subscription process, try again later...");
									}
				                }
							}
							else
							{
								$result = array('status' => FALSE,'msg' => "Error in subscriptiion process, kindly contact admin...");
							}						
						} else{
						    $result = array('status' => FALSE,'msg' => "You have already paid your installments...");
						}
					}
				}else{
				    $result = array('status' => FALSE,'msg' => "No record found...");
				}
			break;
			case '2': // UnSubscribe
				$planDetail	= $this->scheme_modal->get_subscriptionData($id_sch_ac);
				$post_data	= array("subReferenceId" => $planDetail['sub_reference_id']);
				$res 		= $this->cf_curl($planDetail['sub_reference_id'].'/cancel',$post_data,$planDetail);
                if ($res['status'] == FALSE) {
                   $result = array('status' => FALSE,'msg' => "Error in processing request...");
                } 
                else { 
                	$response = $res['result'];
                    if($response->status == "OK"){ // OK - Api call success  - ERROR Failed
                    	$updSubscription = array(
												"message"			=>	$response->message,
												"auth_status"		=>	5,
												"status"			=>	0,
												"last_update"		=>	date('Y-m-d H:i:s')
												);
						$upd = $this->scheme_modal->updateData($updSubscription,'id_auto_debit_subscription',$planDetail['id_auto_debit_subscription'],'auto_debit_subscription');
						$updSchAc = array(
										"auto_debit_status"	=>	5,
										"date_upd"			=>	date('Y-m-d H:i:s')
										);
						$this->scheme_modal->updateData($updSchAc,'id_scheme_account',$id_sch_ac,'scheme_account');											
						if($upd > 0){
						    $result = array('status' => TRUE,'msg' => "You are successfully unsubscribed from cashfree auto-debit process..");
						}else{
						    $result = array('status' => FALSE,'msg' => "Error in unsubscriptiion process, kindly contact admin...");
						}
                    }else{
                        $result = array('status' => FALSE,'msg' => "Error in unsubscriptiion process, try again later...");
					}
                }
			break;
		}
		$this->response($result,200); 
	}
    function cf_curl($subsc_api,$postData,$gateway)
    {
    	$curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $gateway['api_url'].'api/v2/subscriptions/'.$subsc_api,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($postData),
            // Getting  server response parameters //
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded",
                "X-Client-Id: ".$gateway['param_3'],
                "X-Client-Secret:".$gateway['param_1']
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return array('status' => FALSE, 'result' => $err);
        } 
        else {
            /*echo $gateway['api_url'].'api/v2/subscriptions/'.$subsc_api;
            echo "<pre>";print_r($gateway);
            echo "<pre>";print_r($postData);
            echo "<pre>";print_r($response);
            exit;*/
        	return array('status' => TRUE, 'result' => json_decode($response));
        }	
    }	
    function random_strings($length)
    {
         $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
         return substr(str_shuffle($str_result), 0, $length);
    }
	/* .Cashfree Auto-Debit Functions */
	
	function mobile_payment_get()
	{ 
	   $payData = array(
    	                'firstname'     => $this->get('firstname'), 
                        'lastname'      => $this->get('lastname'),
                        'phone'         => $this->get('phone'),
                        'id_branch'     => $this->get('id_branch'),
                        'amount'        => $this->get('amount'),
                        'redeemed_amount' => $this->get('redeemed_amount'),
                        'productinfo'   => $this->get('productinfo'),
                        'email'         => $this->get('email'),
                        'gateway'       => $this->get('gateway'),
                        'pg_code'       => $this->get('pg_code'),
                        'pay_arr'       => $this->get('pay_arr')
	                );
	   //echo "<pre>"; print_r($payData);exit;             
	   if(!empty($payData['phone']))
	   {
			//get the values posted from mobile in array 
		   //$payData = $_GET;
		   $pay_flag = TRUE;
		   $allow_flag = FALSE;
		   $submit_pay = FALSE;
		   $actAmount = (float) $payData['amount'];  // sum of all chit amount
		   $cusData = $this->payment_modal->get_customer($payData['phone']);
		   $gateway = ( isset($payData['gateway']) ? ($payData['gateway'] == 'undefined' ? 1 : $payData['gateway']) :1 );
		   $paycred = $this->payment_gateway($cusData['id_branch'],$payData['gateway']); 
		   //print_r($payData);exit;
		   
		   //check pay_flag
		   if($pay_flag )
		   {
				//generate txnid
                 $txnid = uniqid(time());
				 $i=1;
				 $sch_payment = json_decode($payData['pay_arr']);
				 $udf1= "";
				 $udf2= "";
				 $udf3= "";
				 $productinfo= "";
				 
				// $this->db->trans_begin();	
				foreach ($sch_payment as $pay){	
					if($cusData['branch_settings']==1)
			   {
				    if($cusData['is_branchwise_cus_reg']==1)
					{
						$id_branch  = $cusData['id_branch'];
					}
					else
					{
						$id_branch  = $pay->id_branch;
						//$id_branch  = $branch['sch_join_branch'];
					}
				}
				else{
					$id_branch = NULL;
				} 				
					//validate amount
				   $chit = $this->payment_modal->get_schemeByChit($pay->udf1);
				   if( $pay->scheme_type == 1)
				   {						   
						  $metal_rate = $this->payment_modal->getMetalRate();	
						  $gold_rate = (float) $metal_rate['goldrate_22ct'];
						  $amt = $gold_rate * $pay->udf2;
						  $allow_flag =  ($pay->amount >= $amt? TRUE :FALSE);
				   }
				   else
				   {
						$allow_flag =  ($pay->amount >= $chit['amount']? TRUE :FALSE);
				   }
				   if($pay->scheme_type == 2 || ($pay->scheme_type == 3 && $chit['wgt_convert']==0)){
				   	    $data = array('metal_rate'=>$pay->udf3,'amount'=>$pay->udf4);
						$metal_wgt = $this->amount_to_weight($data);
				   }
				   else{	
						$metal_wgt = (isset($pay->udf2) && $pay->udf2 !='' ? $pay->udf2 : 0.000);
				   }
				   
				   	//rate fixed at the time of scheme join
			if($chit['one_time_premium']==1 && $chit['rate_fix_by'] == 0 && $chit['rate_select'] == 1)
			{
			    $metal_rate = $this->payment_modal->getMetalRate();	
				$gold_rate = (float) $metal_rate['goldrate_22ct'];
			    if($gold_rate != 0)
			    {
			        
			        $isRateFixed = $this->mobileapi_model->isRateFixed($pay->udf1);
            			    if($isRateFixed['status'] == 0){
                		        $updData = array(
            	   							"fixed_wgt" => $pay->amount/$gold_rate,
            	   							"firstPayment_amt" => $pay->amount,
            	   							"fixed_metal_rate" => $gold_rate,
            	   							"rate_fixed_in" => 1,
            	   							"fixed_rate_on" => date("Y-m-d H:i:s")
            	   						); 
            	   					//print_r($updData);exit; 
            	   				$ratestatus = $this->mobileapi_model->updFixedRate($updData,$pay->udf1);
            			    }else{
            			        $data = array('is_valid' => TRUE,'success' => TRUE, 'msg' => "Rate already fixed !!");
            			    }
			    }
			}
				   if($allow_flag){
					  // 	echo $pay->due_type;exit;
						//set insert data					
							$insertData = array(
									"id_scheme_account"	 => (isset($pay->udf1)? $pay->udf1 : NULL ),
									"payment_amount" 	 => (isset($pay->amount)? $pay->amount+($pay->discount==""?0.00:$pay->discount) : NULL ), 
									"gst" 	    		 => (isset( $chit['gst'])? $chit['gst'] : 0 ),
									"gst_type" 	    	 => (isset($chit['gst_type'])? $chit['gst_type'] : 0 ),
									"payment_type" 	     => (isset($paycred) ? ($paycred['pg_name']) : ''),
									"due_type" 	   		 => $pay->due_type,
									"no_of_dues" 	     => 1,
									"actual_trans_amt"      => (isset($actAmount) ? $actAmount : 0.00),
									"date_payment" 		 =>  date('Y-m-d H:i:s'),
									"metal_rate"         => (isset($pay->udf3) && $pay->udf3 !='' ? $pay->udf3 : 0.00),
									"metal_weight"       =>  $metal_wgt,
									"id_transaction"     => (isset($txnid) ? $txnid.'-'.$i : NULL),
									"ref_trans_id"       => (isset($txnid) ? $txnid : NULL),// to update pay status after trans complete.
									"remark"             =>  'Paid for '.$pay->udf5.($pay->udf5 >1?'months':'month'),
									"added_by"			 =>  2,
									"add_charges" 	     => (isset($pay->charge) ?$pay->charge : NULL), 
									"discountAmt"        => ($pay->discount!="" ? $pay->discount : 0.00),
									"payment_status"     => $this->payment_status['pending'],
									"id_payGateway"      => (isset($gateway) ? $gateway: 1),
									'id_branch'          => $id_branch,
							// 		"redeemed_amount"     => (isset($payData['redeemed_amount']) ?$payData['redeemed_amount'] :0.00),
							// 	    "is_point_credited"   => 1
									//status - 0 (pending), will change to 1 after approved at backend
								);  
						$udf1 = $udf1." ".$pay->udf1;
						$udf2 = $udf2." ".$pay->udf2;
						$udf3 = $udf3." ".$pay->udf3;
						$productinfo = $productinfo." ".$pay->chit_number;
						//inserting pay_data before gateway process
						/*echo "<pre>";
					    print_r($insertData);
					    echo "</pre>";	*/
						$payment = $this->payment_modal->addPayment($insertData);	
						$i++;
					}
				}
				
				if($this->db->trans_status()=== TRUE)
	            {
				 	$this->db->trans_commit();
				 	$submit_pay = TRUE;
				}
				else{
				 	$this->db->trans_rollback();
					$submit_pay = FALSE;
				}
				
				if($submit_pay)
				{ 
					$updData = array("last_payment_on" => date("Y-m-d H:i:s"));
			        $this->payment_modal->updData($updData,'id_customer',$cusData['id_customer'],'customer');
                    $secretKey = $paycred['param_1']; //Shared by Cashfree  
                    $appId     = $paycred['param_3'];
					//set data for hash generation
					$data['pay'] =	array (
						'key' 			=> $secretKey, 
						'txnid' 		=> $txnid, 
						'amount' 		=> (isset($payData['amount'])   ? $payData['amount']:''),
						'productinfo'	=> (isset($productinfo)    ? $productinfo :''),
						'firstname' 	=> (isset($cusData['firstname'])? $cusData['firstname'] :''),
						'lastname' 		=> (isset($cusData['lastname']) ? $cusData['lastname']:''),
						'email' 		=> (isset($cusData['email'])    ? $cusData['email']:''), 
						'phone' 		=> (isset($cusData['mobile'])    ? $cusData['mobile'] :''),
						'address1'		=> (isset($cusData['address1']) ? $cusData['address1']:''), 
						'address2'		=> (isset($cusData['address2']) ? $cusData['address2'] :''), 
						'city'			=> (isset($cusData['city']) ? $cusData['city'] :''), 
						'state'			=> (isset($cusData['state']) ? $cusData['state'] : ''), 
						'country'		=> (isset($cusData['country']) ? $cusData['country'] : ''), 
						'zipcode'		=> (isset($cusData['pincode']) ? $cusData['pincode'] :''), 
						'udf1' 			=> (isset($udf1)    ? $udf1 :''),
						'udf2' 			=> (isset($udf2)    ? $udf2 :''),
						'udf3'			=> (isset($udf3)    ? $udf3 :''),
						'udf4' 			=> (isset($payData['udf4'])    ? $payData['udf4'] : ''),
						'udf5' 			=> (isset($payData['udf5'])? $payData['udf5']:'') 
					);
					
					if($payData['pg_code'] == 4){   // cash free 
    					$gen_email =  $this->random_strings(8).'@gmail.com';  
                        $data['cashfreepay'] =	array (
                                                    'appId'         => $appId,  //Shared by Cashfree
                                                    'orderId'       => $txnid,
                                                    'orderAmount' 	=> (string) $actAmount, 
                                                    'orderCurrency'	=> 'INR',
                                                    'orderNote'	    => 'Purchase plan payment from mobile app',
                                                    'customerName' 	=> (isset($payData['firstname'])? (isset($payData['lastname']) ? $payData['firstname'].' '.$payData['lastname']:$payData['firstname']) :''),
                                                    'customerEmail' => !empty($cusData['email']) ? $cusData['email'] : $gen_email,
                                                    'customerPhone' => (isset($payData['phone'])    ? $payData['phone'] :''),
                                					"notifyUrl"     => $this->config->item('base_url')."index.php/services/cashfreeStatusNotify/".$payData['gateway'],
                                                    "stage"         =>  $paycred['type'] == 0 ? "TEST" : 'PROD',//"TEST/PROD",
                                                ); 
                        // Write log 
                        /*$log_path = 'log/cashfree/mobiletest'.date("Y-m-d").'.txt';
                        $ldata = "\n".date('d-m-Y H:i:s')." \n Mobile : ".json_encode($data['cashfreepay'],true);
                        file_put_contents($log_path,$ldata,FILE_APPEND | LOCK_EX);*/
                        $token = $this->generateCFtoken($txnid,$actAmount,$data['cashfreepay'],$paycred); 
                		$this->response($token,200);
			    } 
				
		        }
		   }
	    }else{
	        $result['status'] = false;
            $result['message'] = 'Invalid payment request...';
	        $this->response($result,200); 
	    }
	}
	
	function generateCFtoken($txnid, $orderAmount, $params,$paycred){
	    $secretKey = $paycred['param_1']; //Shared by Cashfree  
        $appId     = $paycred['param_3'];
    	$postData  = array(
						  "orderId" 		=> $txnid,
						  "orderAmount"		=> $orderAmount,
						  "orderCurrency"	=> "INR"
						);
						
		$curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $paycred['api_url'].'api/v2/cftoken/order',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($postData),
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "Content-Type: application/json",
                "x-client-id: ".$appId,
                "x-client-secret: ".$secretKey
            ),
        ));       
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            //echo "cURL Error #:" . $err;
            $result = array('status' => false, "message" => 'Sorry for inconvenience, we are unable to proceed your request. Kindly contact customer care..');
            return $result;
        } 
        else {  
            $tokenData = json_decode($response);
            if($tokenData->status == 'OK'){
                $gen_email =  $this->random_strings(8).'@gmail.com'; 
                $result['status'] = true;
                $result['params'] = $params;
                $result['params']['tokenData'] = $tokenData->cftoken;
            }
            else if($tokenData->status == 'ERROR'){
                $result['status'] = false;
                $result['message'] = 'Sorry for inconvenience, we are unable to proceed your request. Kindly contact customer care...';
            } 
            return $result;
        }
	}
	
	public function cf_payment_status_post()
    {
        
        /*{"paymentMode":"CREDIT_CARD","orderId":"16305780766130a59c7beaf","txTime":"2021-09-02 15:51:38","referenceId":"1043610","type":"CashFreeResponse","txMsg":"Transaction Successful","signature":"MT1j+VSkIjS5hg2zGKt\/xmZjHWoz70dKm4\/w8LK6qbg=","orderAmount":"1000.00","txStatus":"SUCCESS"}

        {"paymentMode":"CREDIT_CARD","orderId":"16305785416130a76de4d2a","txTime":"2021-09-02 15:59:17","referenceId":"1043658","type":"CashFreeResponse","txMsg":"Your transaction has failed.","signature":"QRgBU7lujaC0qc5SMoxPczO4qpWAI1zWJI6CKoinroI=","orderAmount":"1000.00","txStatus":"FAILED"}
        
        {"orderId":"16305784926130a73c56f7b","type":"CashFreeResponse","txStatus":"CANCELLED"}*/
        
        $postData = $this->get_values();
        $trans_id      = $postData["orderId"];
        $txStatus      = $postData["txStatus"]; 
        $orderAmount   = isset($postData["orderAmount"]) ? $postData["orderAmount"] : NULL;
        $referenceId   = isset($postData["referenceId"]) ? $postData["referenceId"] : NULL;
        $paymentMode   = isset($postData["paymentMode"]) ? $postData["paymentMode"] : NULL;
        $txMsg         = isset($postData["txMsg"]) ? $postData["txMsg"] : NULL;
        $txTime        = isset($postData["txTime"]) ? $postData["txTime"] : NULL;
         
        //$computedSignature = base64_encode($hash_hmac); 
        // Write log 
        $log_path = 'log/cashfree/mob_response_'.date("Y-m-d").'.txt';
        $ldata = "\n".date('d-m-Y H:i:s')." \n Mobile : ".json_encode($postData,true);
        file_put_contents($log_path,$ldata,FILE_APPEND | LOCK_EX);
        
        /* // Signature Verification
        $data = $orderId.$orderAmount.$referenceId.$txStatus.$paymentMode.$txMsg.$txTime;
        $hash_hmac = hash_hmac('sha256', $data, $secretkey, true) ;
        $computedSignature = base64_encode($hash_hmac);
        if ($signature == $computedSignature) {
            // Proceed
        } else {
            // Reject this call
        }
        */ 
          
        if(!empty($trans_id) && $trans_id != NULL)
        {
            $pay = $this->payment_modal->getWalletPaymentContent($trans_id); 
    	    $updateData = array( 
    						"payment_mode"       => ($paymentMode== "CREDIT_CARD" ? "CC":($paymentMode == "DEBIT_CARD" ? "DC":($paymentMode == "NET_BANKING" ? "NB":$paymentMode))),
    						"payu_id"            => $referenceId,
    						"remark"             => $txMsg."[".$txTime."] mbl-status",
    						"payment_ref_number" => $referenceId,
    						"payment_status"     => ($txStatus==="SUCCESS" ? ($this->config->item('auto_pay_approval') == 1 ||  $this->config->item('auto_pay_approval') == 2 ? $this->payment_status['success']: $this->payment_status['awaiting']) : ($txStatus==="CANCELLED" ? $this->payment_status['cancel'] :($txStatus==="FAILED" ? $this->payment_status['failure'] : $this->payment_status['pending']) ))
    					    ); 
			$payment = $this->payment_modal->updateGatewayResponse($updateData,$trans_id);   
    		if($payment['status'] == true)
    		{
    		    $serviceID = 0;
    		    $pay_msg = "Your payment has been ".$txStatus;
        		if($txStatus==="SUCCESS")
        		{
        		    $serviceID = 3; 
        		    $type = 2;
        		    $pay_msg = 'Transaction ID: '.$trans_id.'. Payment amount INR. '.$orderAmount.' is paid successfully. Thanks for your payment with '.$this->comp['company_name'];
        	    }
        		else if($txStatus === "FAILED")
        		{ 
        		    $serviceID = 7; 
        		    $type = -1;
        		    $pay_msg = 'Your payment has been failed. Please try again. Transaction ID: '.$trans_id;
        		}
        		else if($txStatus === "CANCELLED")
        		{ 
        		    $serviceID = 7; 
        		    $type = 3;
        		    $pay_msg = 'Your payment has been cancelled.';
        		}
        		if($serviceID > 0){
        		    if($txStatus==="SUCCESS")
        		    {
        		        $payIds = $this->payment_modal->getPayIds($trans_id);
    					if(sizeof($payIds) > 0)
    					{
    						$updData = array("last_payment_on" => NULL);
							$this->payment_modal->updData($updData,'id_customer',$payIds[0]['id_customer'],'customer'); 
    						foreach ($payIds as $pay)
    						{
    						    $schData = [];
    						    $cusRegData = [];
    						    $transData = [];
    						    if($pay['allow_referral'] == 1){
	            				    $ref_data	=	$this->payment_modal->get_refdata($pay['id_scheme_account']);
	            					$ischkref	=	$this->payment_modal->get_ischkrefamtadd($pay['id_scheme_account']);	
	            			 		if($ref_data['ref_benifitadd_ins_type'] == 1 && $ref_data['referal_code'] != '' && ($ref_data['ref_benifitadd_ins'] == $ref_data['paid_installments']) && $ischkref == TRUE){	
	            						$this->insert_referral_data($ref_data['id_scheme_account'],$ref_data);
	            					}else if($ref_data['ref_benifitadd_ins_type'] == 0 && $ref_data['referal_code'] != '' && $ischkref == TRUE){
	            						$this->insert_referral_data($ref_data['id_scheme_account'],$ref_data);
	            					}
	            			 	}
	            			 	//agent referal
	            			 	if($pay['agent_refferal'] == 1 && $pay['agent_credit_type'] == 1)
            			 	{
            			 	    $agent_refral = $this->payment_modal->get_agent_refdata($pay['id_scheme_account'],$pay['id_payment']);
            			 	    $agent_benefits = $this->payment_modal->get_agentBenefits($agent_refral['id_scheme'],$agent_refral['payment_amount']);
            			 	    $insert_array = array("ly_trans_type" => 3,
            			 	                    "cus_loyal_cus_id" => $agent_refral['cus_loyal_cus_id'],
            			 	                    "id_agent"   => $agent_refral['id_agent'],
            			 	                    "id_scheme_account" => $pay['id_scheme_account'],
            			 	                    "id_payment"  => $pay['id_payment'],
            			 	                    "cash_point" => $agent_benefits['cash_point'],
            			 	                    "status"    => 1,
            			 	                    "tr_cus_type" => 1,
            			 	                    "cr_based_on" => 3,
            			 	                    "date_add"       => date('Y-m-d H:i:s')
            			 	                            );
            			 	           $this->payment_modal->insert_agent_transaction($insert_array);
            			 	}
	    						// Generate account  number  
								if($pay['schemeacc_no_set'] == 0 || $pay['schemeacc_no_set']==3)
								{
									if($pay['scheme_acc_number'] == '' ||  $pay['scheme_acc_number'] == null)
									{
									    $ac_group_code = NULL;
										// Lucky draw
										if($pay['is_lucky_draw'] == 1 ){ // Based on scheme settings 
											// Update Group code in scheme_account table 
											$updCode = $this->payment_modal->updateGroupCode($pay); 
											$ac_group_code = ($updCode['status'] ? $updCode['group_code'] : NULL);
										}
										$scheme_acc_number = $this->payment_modal->account_number_generator($pay['id_scheme'],$pay['branch'],$ac_group_code); 
										if($scheme_acc_number != NULL && $pay['id_scheme_account'] > 0){
											$schData['scheme_acc_number'] = $scheme_acc_number;
											$cusRegData['scheme_acc_number'] = $scheme_acc_number;
										}
									}
								}
								
								// Generate Client ID
								if($pay['gent_clientid'] ==1 && $scheme_acc_number != NULL && empty($pay['ref_no'])){  
									$cliData = array(
													 "cliID_short_code"	=> $this->config->item('cliIDcode'),
													 "sync_scheme_code"	=> $pay['sync_scheme_code'],
													 "code"	            => $pay['group_code'],
													 "ac_no"			=> $scheme_acc_number
													);											
									$schData['ref_no'] = $this->payment_modal->generateClientID($cliData);
									$cusRegData['ref_no'] = $schData['ref_no'];
									$transData['ref_no'] = $schData['ref_no'];
									$cusRegData['group_code'] =$pay['group_code'];
								}
								
		    						// Generate receipt number
								if($pay['receipt_no_set'] == 1 && ($this->config->item('auto_pay_approval') == 1 ||  $this->config->item('auto_pay_approval') == 2) )
								{ 
									$receipt['receipt_no'] = $this->generate_receipt_no($pay['id_scheme'],$pay['branch']);
									$payment['status'] = $this->payment_modal->update_receipt($pay['id_payment'],$receipt);
								}
								if($pay['id_scheme_account'] > 0){
									if(sizeof($schData) > 0){ // Update scheme account
										$this->payment_modal->update_account($schData,$pay['id_scheme_account']);
									}
									if(sizeof($cusRegData) > 0){ 
			                                $this->payment_modal->update_cusreg($cusRegData,$pay['id_scheme_account']); // Update Customer reg - Client ID, Ac No
									}
									if(sizeof($transData) > 0){ 
			                                $this->payment_modal->update_trans($transData,$pay['id_scheme_account']); // Update Transaction - Client ID
									}
								 }
    							 //Update First Payment Amount In Scheme Account
    							 $approval_type = $this->config->item('auto_pay_approval');
        						 if(($approval_type == 1 || $approval_type == 2 || $approval_type == 3) && ($pay['firstPayamt_maxpayable']==1 || $pay['firstPayamt_as_payamt']==1) && ($pay['firstPayment_amt'] == '' ||  $pay['firstPayment_amt'] == null || $pay['firstPayment_amt'] == 0))
        						 {
        							if($pay['flexible_sch_type'] == 4 && ($pay['firstPayment_wgt'] == null || $pay['firstPayment_wgt'] == "") ){ // Fix First payable as weight
    									$fixPayable = array('firstPayment_wgt'  =>  $pay['metal_weight'] );
    								}else{
    									$fixPayable = array('firstPayment_amt'  =>  $pay['payment_amount'] );
    								}
    								$status = $this->payment_modal->update_account($fixPayable,$pay['id_scheme_account']);	 
        						 }
        						 if($pay['one_time_premium'] == 1)
        						 {
        						     $fixPayableamt = array('firstPayment_amt'  =>  $pay['payment_amount']);
        						     $this->payment_modal->update_account($fixPayableamt,$pay['id_scheme_account']);	
        						 }
    							if( $this->config->item('auto_pay_approval') == 2 && ($this->config->item('integrationType') == 2 || $this->config->item('integrationType') == 3))
    							{ 	 
    								/*if($pay['edit_custom_entry_date']==1)
    								{
    										$receipt['custom_entry_date']=$pay['custom_entry_date'];
    										$payment['status'] = $this->payment_modal->update_receipt($pay['id_payment'],$receipt);
    								}*/
    								//$this->insert_common_data($pay['id_payment']);  
    								  if($this->config->item('integrationType') == 1){
    								        $this->insert_common_data_jil($pay['id_payment']);
    								    }else if($this->config->item('integrationType') == 2){
    								        $this->insert_common_data($pay['id_payment']);
    								    }  		            
        		        		}
        		        		
        		        		
    						}
    					}
        		    }
        		     
        		    $service = $this->services_modal->checkService($serviceID); 
        			if($service['sms'] == 1)
        			{
        				$id=$payment['id_payment'];
        				$data =$this->services_modal->get_SMS_data($serviceID,$id);
        				$mobile =$data['mobile'];
        				$message = $data['message'];
        				$this->mobileapi_model->send_sms($mobile,$message,$service['dlt_te_id']);
        			}
        			
        			if($service['email'] == 1 && isset($payData['email']) && $payData['email'] != '')
        			{ 
        				$invoiceData = $this->payment_modal->get_invoiceDataM(isset($payment['id_payment'])?$payment['id_payment']:'');
        				$to = $payData['email'];
        				$subject = "Reg - ".$this->comp['company_name']." payment for the saving scheme";
        				$data['payData'] = $invoiceData[0];
        				$data['type'] = $type;
        				$data['company_details'] = $this->comp;
        				$message = $this->load->view('include/emailPayment',$data,true);
        				$sendEmail = $this->email_model->send_email($to,$subject,$message);	
        			} 
        		}  
			    //redirect('paymt/hdfcTransStatus/success/'.$resData['txnid'].'/'.$resData['amount']);
        	    $response = array("status" => TRUE, "title" => "Transaction Completed", "msg" => $pay_msg);
            	 	
            }else{ 
                //redirect('paymt/hdfcTransStatus/completed/'.$resData['txnid'].'/'.$resData['amount']);
                $response = array("status" => TRUE, "title" => "Transaction Completed", "msg" => "Payment amount of INR ".$resData['amount']." paid successfully"); 
            } 
        }else{ 
            //redirect('paymt/hdfcTransStatus/completed/'.$resData['txnid'].'/'.$resData['amount']);
            $response = array("status" => TRUE, "title" => "Transaction Completed", "msg" => "Payment amount of INR ".$resData['amount']." paid successfully");
        }
        
        $this->response($response,200); 

    }
    
    function aadhar_upload(){
		$cus_id = $this->get('id_customer');
		$approval_type = "Auto";
		
    }
    
    function aadhar_form_get()
	{
		//$pageType = array('page' => 'kyc_form','currency_symbol'=>$this->comp['currency_symbol'],'scheme_status' =>  $this->scheme_status,'company_name'=>$this->comp['company_name'],'mobile'=>$this->comp['mobile'],'phone'=>$this->comp['phone'],'email'=>$this->comp['email']);

		//$data['header_data'] = $pageType;
		//$data['footer_data'] = $pageType;
		//$data['content'] = $data;
		//$data['fileName'] = 'chitscheme/kyc_form';
		$data['id_customer'] = $this->get('id_customer');
		$this->load->view('mobile/aadhar_upload', $data);
	}
	
	function aadhar_get($type){
	    echo "<div align='center'><h4>Redirecting to app..</h4></div>";
	}
    
    function verifyAadhar_post(){
		$cus_id = $_POST['id_customer'];
		$approval_type = "Auto";
	 
		$aadhar = $_POST; 
		$kyc_detail = array(
			'id_customer'    	 => $cus_id,
			'kyc_type'    	 	 => $aadhar['form_type'],	
			'number'    	 	 => (isset($aadhar['aadhar_number'])?$aadhar['aadhar_number']:NULL),
			'name'    	 		 => (isset($aadhar['aadhar_cardname'])?$aadhar['aadhar_cardname']:NULL),
			'dob'    	 		 => (isset($aadhar['dob'])?date("Y-m-d",strtotime($aadhar['dob'])):NULL),
			'date_add'			 =>  date('Y-m-d H:i:s')
			);  
			
		$response = $this->zoopapiCurl("extract-aadhaar-data",array( "mode" => "pdf","file" => $aadhar['file'],"password" => $aadhar['password'],"purpose" => 'For Purchase Plan KYC verification',"request_consent" => 'Y'));
		
		if(isset($response->statusCode)){
			$data['status'] = FALSE; 
			$data['msg'] = $response->message;
			$data['kyc_status'] = 0 ;
		}
		elseif($response->transaction_status == 5){
			$res = $response->result->BasicInfo; 
			$date = str_replace('/', '-', $res->DOB);
            $dob = date('Y-m-d', strtotime($date));
			$kyc_detail['name'] = $res->Name;
			$kyc_detail['number'] = $response->result->AadhaarInfo;
			$kyc_detail['dob'] = $dob;
			$kyc_detail['status'] = 2;
			$kyc_detail['verification_type'] = 2; // 1-Manual,2-Auto
			$data = $this->scheme_modal->kyc_insert($kyc_detail); 
			if($data['status'] == TRUE){
				//$data['kyc_status'] = $this->mobileapi_model->checkCusKYC($cus_id); 
				$data['kyc_status'] = 1 ;
				$data['msg'] = "Aadhaar Details verified successfully";
			    $data['number'] = $response->result->AadhaarInfo;
			}
		}else{
			$data['kyc_status'] = 0 ;
			$data['status'] = FALSE; 
			$data['msg'] = "There was some issue, please try after sometime .."; 
		}
		//echo "<pre>";print_r($response);
		if($data['kyc_status'] == 1 && $approval_type == "Auto"){
		  $this->session->set_flashdata('successMsg',$data['msg']);
		}
		$data['approval_type'] = $approval_type;
		echo json_encode($data);
	}
	
	//To insert payment and registration details in intermediate table
	function insert_common_data($id_payment)
	{
		$model = self::API_MODEL;
		$this->load->model($model);
		//getting payment detail
		$pay_data = $this->$model->getPaymentByID($id_payment);	
		//storing temp values
		$ref_no = $pay_data[0]['ref_no'];
		$id_scheme_account = $pay_data[0]['id_scheme_account']; 
		$isCusRegExists = $this->$model->checkCusRegExists($id_scheme_account,$ref_no);
		if(!$isCusRegExists['status']){
		     $reg = $this->$model->getCustomerByID($id_scheme_account);	
             //insert customer registration detail
             if($reg)
             {
            	$reg[0]['record_to']= 1 ;
            	$reg[0]['is_registered_online']= 2 ;  // 2 - online record
            	$reg[0]['ref_no']		= $ref_no;
            	$status = $this->$model->insert_CustomerReg($reg[0]);
            	//echo $this->db->last_query();exit;
             }	
             //echo $this->db->last_query();exit;
		}
		$isTranExists = $this->$model->checkTransExists($ref_no);
		if(!$isTranExists)
		{
            //insert payment detail
            $pay_data[0]['record_to'] = 1;	
            $pay_data[0]['payment_type'] = 1;	// 1 - online 
            $status =	$this->$model->insert_transaction($pay_data[0]); 
		}
		//echo $this->db->last_query();exit;
		return true;
	}
	//To insert payment and registration details in intermediate table
	function insert_common_data_jil($id_payment)
	{
		$model = self::CHITAPI_MODEL;
		$this->load->model($model);
		//getting payment detail
		$pay_data = $this->$model->getPaymentByID($id_payment);	
		//storing temp values
		$trans_date = $pay_data[0]['trans_date'];
		$approval_no = $pay_data[0]['approval_no'];
		$ref_no = $pay_data[0]['ref_no'];
		$id_scheme_account = $pay_data[0]['id_scheme_account'];
		//getting customer detail to post registration again
		 $reg = $this->$model->getCustomerByID($id_scheme_account,$id_payment);		 
		//echo $this->db->last_query();
		 $isExists = $this->$model->checkTransExists($trans_date,$approval_no,$ref_no);
		if(!$isExists)
		{
			//insert payment detail
			$status =	$this->$model->insert_transaction($pay_data[0]);
			//echo $this->db->last_query();exit;
			  if($status)
			  {
				  //insert registration detail
				  if($reg)
				 {
					$reg[0]['transfer_jil']	= 'N';
					$reg[0]['transfer_date']= NULL ;
					$reg[0]['ref_no']		= $ref_no;
					$status = $this->$model->insert_CustomerReg($reg[0]);
						//echo $this->db->last_query();exit;
				 }	 
			  }	
		}	
			return true;
	}
	
	function insert_referral_data($id_scheme_account,$referral_data)
	{ 
		$status=FALSE;			
		$serviceID=16;
	
		if($referral_data['referal_code']!='' && $referral_data['is_refferal_by']==1){			
		  $data = $this->payment_modal->get_empreferrals_datas($id_scheme_account);
		}else if($referral_data['referal_code']!='' && $referral_data['is_refferal_by']==0){			
			$data = $this->payment_modal->get_cusreferrals_datas($id_scheme_account);
		}	
		
		if(!empty($data))
		{			
		   
			if($data['referal_code']!='' && $data['referal_value']!=''  &&  $data['id_wallet_account']!=''){
			// insert wallet transaction data //
							$wallet_data = array(
							'id_wallet_account' => $data['id_wallet_account'],
							'id_sch_ac'         => $id_scheme_account,
							'date_transaction' =>  date("Y-m-d H:i:s"),
							'transaction_type' =>  0,
							'value'            => $data['referal_value'],
							'description'      => 'Referral Benefits - '.$data['cusname'].' ref no. '.$data['id_scheme_account']
							);
				$status =$this->payment_modal->wallet_transactionDB($wallet_data);
				  if($status)
				  {
				  		// Update credit flag in customer table
					  	/* is_refbenefit_crt = 0 -> already  benefit credited  & 1-> yet to credit benefits' */					 		
					  	if($referral_data['is_refferal_by']==0 && $data['cusbenefitscrt_type']==0 && ($data['schrefbenifit_secadd']==0 || $data['schrefbenifit_secadd']==1) ){
							// customer referal - single  
							$this->payment_modal->update_customer_only(array('is_refbenefit_crt_cus'=>0),$chkreferral['id_customer']);
						}else if($referral_data['is_refferal_by']==0 && $data['cusbenefitscrt_type']==1 && $data['schrefbenifit_secadd']==1){
							// customer referal - multiple  
							$this->payment_modal->update_customer_only(array('is_refbenefit_crt_cus'=>1),$chkreferral['id_customer']);
						}else if($referral_data['is_refferal_by']==1 && $data['empbenefitscrt_type']==0 && ($data['schrefbenifit_secadd']==0 || $data['schrefbenifit_secadd']==1)){	
							 // emp referal - single  					
							$this->payment_modal->update_customer_only(array('is_refbenefit_crt_emp'=>0),$chkreferral['id_customer']);
						}else if($referral_data['is_refferal_by']==1 && $data['empbenefitscrt_type']==1 && $data['schrefbenifit_secadd']==1){	
							// emp referal - single  			
							$this->payment_modal->update_customer_only(array('is_refbenefit_crt_emp'=>1),$chkreferral['id_customer']);
						}
				  } 
				  $service = $this->services_modal->checkService($serviceID);
				  if($service['sms'] == 1 && $data['mobile']){
				       $sms_data =$this->services_modal->get_SMS_data($serviceID,$data['id_scheme_account']); 
				       if($this->config->item('sms_gateway') == '1'){
			    		  $this->sms_model->sendSMS_MSG91($sms_data['mobile'],$sms_data['message'],'',$service['dlt_te_id']);		
			    	   }
			    	   elseif($this->config->item('sms_gateway') == '2'){
			    	      $this->sms_model->sendSMS_Nettyfish($sms_data['mobile'],$sms_data['message'],'trans');	
			    	   }
				  }
				 }
		}
	}
	
	

	
}	
?>
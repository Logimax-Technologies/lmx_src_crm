<?php
if( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once (APPPATH.'libraries/techprocess/TransactionRequestBean.php');
require_once (APPPATH.'libraries/techprocess/TransactionResponseBean.php');
require_once(APPPATH.'libraries/hdfc.php');
class Admin_payment extends CI_Controller
{
	const PAY_MODEL = 'payment_model';
	const PAY_VIEW  = "payment/";
	const SET_VIEW  = "scheme/settlement/";
	const API_MODEL = 'syncapi_model';
	const CHITAPI_MODEL = 'chitapi_model';
	const ACC_MODEL = 'account_model';
	const SET_MODEL = 'admin_settings_model';
	const SMS_MODEL = 'admin_usersms_model';
	const ADM_MODEL = "chitadmin_model";
	const LOG_MODEL = "log_model";
	const MAIL_MODEL = "email_model";
	const WALL_MODEL = "Wallet_model";
	const WALL_API_MOD = 'sync_walletapi_model';
	const CUS_MODEL = 'customer_model';
	const CHIT_MODEL = 'chitadmin_model';
	function __construct()
	{
		parent::__construct();
		ini_set('date.timezone', 'Asia/Calcutta');
		$this->load->model(self::PAY_MODEL);
		$this->load->model(self::API_MODEL);
		$this->load->model(self::CHITAPI_MODEL);
		$this->load->model(self::WALL_API_MOD);
		$this->load->model(self::ACC_MODEL);
		$this->load->model(self::SET_MODEL);
		$this->load->model(self::SMS_MODEL);
		$this->load->model(self::ADM_MODEL);
		$this->load->model(self::LOG_MODEL);
		$this->load->model(self::MAIL_MODEL);
		$this->load->model(self::WALL_MODEL);	
		$this->load->model(self::CUS_MODEL);
		$this->load->model(self::CHIT_MODEL);
		$this->load->model("sms_model");
		$this->employee =  $this->session->userdata('uid');
		$this->company = $this->admin_settings_model->get_company();
		$this->branch_settings =  $this->session->userdata('branch_settings');
		if(!$this->session->userdata('is_logged'))
		{
			redirect('admin/login');
		}	
		$this->id_log =  $this->session->userdata('id_log');
		$this->payment_status = array(
										'pending'   => 7,
										'awaiting'  => 2,
										'success'   => 1,
										'failure'   => 3,
										'cancel'    => 4,
										'refund'    => 6
									  );
	}
	 function ajax_form_data($id="")
    {
		$acc =	self::ACC_MODEL;
		$set =	self::SET_MODEL;
		$pay =	self::PAY_MODEL;
		$data['account']=$this->$acc->getAmountSchemeAccounts(($id!=NULL?$id:''));
		$data['mode']=$this->$set->paymodeDB('get',($id!=NULL?$id:''));	 
		$data['bank']=$this->$set->bankDB('get',($id!=NULL?$id:''));
		$data['payment_status']=$this->$pay->get_payment_status();	 
		$data['drawee']=$this->$set->draweeDB('get',($id!=NULL?$id:''));	 
		echo json_encode($data);
	}
	function generate_receipt_no($id_scheme)
	{
		$model =	self::PAY_MODEL;
		$rcpt_no = "";
		if($this->$model->get_receipt_no_settings()==1)			
		{
			$rcpt = $this->$model->get_receipt_no($id_scheme);
		}
		else
		{
			$rcpt = $this->$model->get_receipt_no();
		}
		//
		if($rcpt!=NULL)
		{
			//print_r($rcpt);exit;
		  	$temp = explode($this->company['short_code'],$rcpt);
			 	if(isset($temp))
			 	{
					$number = (int) $temp[1];
					$number++;
					$rcpt_no =$this->company['short_code'].str_pad($number, 6, '0', STR_PAD_LEFT);
				}		   
		}
		else
		{
			 	$rcpt_no =$this->company['short_code']."000001";
		}
//print_r($rcpt_no);exit;
		return $rcpt_no;
	}
	function ajax_account_detail($id)
	{
		$model =	self::PAY_MODEL;
		$data['account']=$this->$model->get_paymentContent($id);
		//echo "<pre>"; print_r($data); echo "<pre>";exit;
		echo json_encode($data);
	}
	function ajax_payment_status()
	{
		$model =	self::PAY_MODEL;
		$data['payment_status']=$this->$model->get_payment_status();	 
		echo json_encode($data);
	}
	function ajax_customer_schemes($id_customer)
	{
		$model =	self::PAY_MODEL;
		$data['accounts']=$this->$model->get_customer_schemes($id_customer);
		$data['wallet_balance']=$this->$model->wallet_balance($id_customer);	
//echo "<pre>";print_r($data);echo "</pre>";exit;		
		echo json_encode($data);
	}
	function ajax_customer_schemes_amount($id_customer)
	{
		$model =	self::PAY_MODEL;
		$data=$this->$model->get_customer_schemes_amount($id_customer);	 
		echo json_encode($data);
	}	
	function ajax_payment_stat()
	{
		$model =	self::PAY_MODEL;
		$payment_stat=$this->$model->total_payments();	 
		echo json_encode($payment_stat);
	}
	function ajax_payment_range()
	{      
	      	$model =	self::PAY_MODEL;
		 	$set_model=self::SET_MODEL;
		    $access = $this->$set_model->get_access('payment/list');
		  	if(!empty($_POST))
		  	{
				$range['from_date']  = $this->input->post('from_date');
				$range['to_date']  = $this->input->post('to_date');
				$range['type']  = $this->input->post('type');
				$range['limit']  = $this->input->post('limit');
				$items=$this->$model->payment_list_range($range['from_date'],$range['to_date'],$range['type'],$range['limit']);
			}
			else
			{
				$items=$this->$model->payment_list(($id!=NULL?$id:''),10);	 
			}
	        $payment = array(
	        				    'access' => $access,
	        					'data'   => $items
	        				);  
			echo json_encode($payment);	 
	}
	public function ajax_get_scheme()
	{
	$id_arr= $this->input->post('id_scheme');
	$data=array();
	  $model=self::PAY_MODEL;
		foreach($id_arr as $id)
		{
		$result=$this->$model->get_payment_by_scheme($id);
		$data=array_merge($result,$data);
		}
		echo json_encode($data);
	}
	function ajax_postpayment_data()
	{
		$model=	self::PAY_MODEL;
		$detail=$this->input->post('payment');
		//$data['payments'] = $this->$model->pdc_report_detail($detail['filter'],strtoupper($detail['mode']),$detail['status']);
		$data['payments'] = $this->$model->pdc_detail_all($detail['status']);
		$data['payment_status']=$this->$model->get_payment_status();
		echo json_encode($data);
	}
	function postdate_payment_form($type="",$id)
	{
		$model =	self::PAY_MODEL;
   		$sms_model= self::SMS_MODEL;
  		$set_model = self::SET_MODEL;
   	  switch($type)
   	  {
   	 	case 'Edit':
   	 	      $data['payment']=$this->$model->postdated_paymentDB('get',$id,"");
   	 		  $data['main_content'] = self::PAY_VIEW."postdated/entry_form" ;
	 		  $this->load->view('layout/template', $data);    
   	 	break;
   	 	case 'Update':
   	        $payment= $this->input->post('payment');
   	          $pay_array = array(
   	          	'charges' 			=> (isset($payment['charges'])?$payment['charges']:0),
   	          	'payment_status' 	=> (isset($payment['payment_status'])?$payment['payment_status']:7)
   	          );
   	          if($pay_array['payment_status']==2)
   	          {
			  	$pay_array['date_presented']		= (isset($payment['date_presented'])? date('Y-m-d',strtotime(str_replace("/","-",$payment['date_presented']))): NULL); 	
			  }
			  else
			  {
			  	$pay_array['date_presented']  = NULL;		
			  }
		  	 $status = $this->$model->postdated_paymentDB("update",$id,$pay_array);
   	           if($status)
				{
					if($pay_array['payment_status']==1){
					  	$acdata = $this->$model->isAcnoAvailable($payment['id_scheme_account']);
						$scheme_acc_no=$this->$set_model->accno_generatorset();
			 			       	 	if($acdata['status'] && ($scheme_acc_no['status']==1 && $scheme_acc_no['schemeacc_no_set']==0)){
			 			       	 		$scheme_acc_number=$this->account_model->account_number_generator($acdata['id_scheme']);
									   if($scheme_acc_number!=NULL)
										{
											$updateData['scheme_acc_number']=$scheme_acc_number;
										}
										$updSchAc = $this->account_model->update_account($updateData,$payment['id_scheme_account']);
									}
					  }
					 $pay_status_array= array(
	 			       	'id_post_payment'	=>  (isset($status ['updateID'])?$status['updateID']: NULL), 	
	 			       	'id_status_msg' 	=>  (isset($pay_array['payment_status'])?$pay_array['payment_status']:NULL), 
	 			       	'charges' 			=>  (isset($pay_array['charges'])?$pay_array['charges']:NULL),
	 			       	'id_employee' 		=>  $this->session->userdata('uid'),
	 			       	'date_upd'			=>  date('Y-m-d H:i:s')
	 			       );
	 			       $ppsm = $this->$model->payment_statusDB("insert","",$pay_status_array);
					 $this->session->set_flashdata('chit_alert', array('message' => 'Payment updated successfully','class' => 'success','title'=>'Post Dated Payment'));
				}else{
						  $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed the requested operation','class' => 'danger','title'=>'Scheme Payment'));
				}
		$check_status = $this->$model->postdated_paymentDB("get",$id);		
		 if($pay_array['payment_status']!=1)
		  {
	 		  if($check_status['id_payment_status']!=$pay_array['payment_status']){	
				  	 $serviceID = 6;
		      		 $service = $this->$set_model->get_service($serviceID);
		      		  $mail_model=self::MAIL_MODEL;
		     			 $company = $this->$set_model->get_company();   
		     			 $id=$status['updateID'];
						 $data =$this->$sms_model->get_SMS_data($serviceID,$id);
						 $mobile =$data['mobile'];
						 $message = $data['message'];
						if($service['serv_sms'] == 1)
									{
										if($this->config->item('sms_gateway') == '1'){
							    		    $this->sms_model->sendSMS_MSG91($mobile,$message);		
							    		}
							    		elseif($this->config->item('sms_gateway') == '2'){
							    	        $this->sms_model->sendSMS_Nettyfish($mobile,$message,'trans');	
							    		}
									 }
									    if($service['serv_whatsapp'] == 1){
                    	                    $this->admin_usersms_model->send_whatsApp_message($mobile,$message); 
                                         } 
						 $payData = $this->$model->getPostpayment_data($status['updateID']);
								if($service['serv_email'] == 1  && $payData['email']!= '')
									{
										$data['payData'] = $payData;
										$data['company_details'] = $company;
										$data['type'] = 3;
										$to = $payData['email'];
										$subject = "Reg- ".$company['company_name']." saving scheme payment details";
										$message = $this->load->view('include/emailPayment',$data,true);
										$sendEmail = $this->$mail_model->send_email($to,$subject,$message);
									}	
				}
				$this->session->set_flashdata('chit_alert', array('message' => 'Post-dated Payment updated successfully','class' => 'success','title'=>'Post Dated Payment'));			
				redirect('postdated/payment/list');
		  }	
		  else
		  {
		  	   $pay=$this->$model->postdated_paymentByID($id);
		  	  // $receipt_no = $this->generate_receipt_no();
		  	   if($this->$model->get_rptnosettings()==0){						
				   $receipt_no = $this->generate_receipt_no();}
				   else{						
					$receipt_no;}
		  	   	   $txnid =uniqid(time());  
	 	    	 $ins_pay_array = array(		
	 	    	 						'id_scheme_account'			=>  (isset($pay['id_scheme_account'])?$pay['id_scheme_account']: NULL), 				       			 
	 	    	 						'id_transaction'			=>  $txnid, 				       			 
										'date_payment'		=>	(isset($pay['date_payment'])? date('Y-m-d',strtotime(str_replace("/","-",$pay['date_payment']))): NULL), 	
										'payment_type' 			=>  "Manual",
										'payment_mode' 			=>  (isset($pay['pay_mode'])?$pay['pay_mode']: NULL),
										'payment_amount' 			=>  (isset($pay['amount'])?$pay['amount']: 0.00),
										'metal_rate' 			=>  (isset($pay['metal_rate'])?$pay['metal_rate']: 0.00),
										'metal_weight' 			=>  (isset($pay['weight'])?$pay['weight']: 0.000),
										'payment_ref_number' 			=>  (isset($pay['payment_ref_number'])?$pay['payment_ref_number']: NULL),
										'id_post_payment' 			=>  (isset($pay['id_post_payment'])?$pay['id_post_payment']: NULL),
										'cheque_no' 			=>  (isset($pay['cheque_no'])?$pay['cheque_no']: NULL),
										'bank_acc_no' 			=>  (isset($pay['payee_acc_no'])?$pay['payee_acc_no']: NULL),
										'bank_name' 			=>  (isset($pay['payee_bank'])?$pay['payee_bank']: NULL),
										'bank_branch' 			=>  (isset($pay['payee_branch'])?$pay['payee_branch']: NULL),
										'bank_ifsc' 			=>  (isset($pay['payee_ifsc]'])?$pay['payee_ifsc]']: NULL),
										'id_drawee' 			=>  (isset($pay['id_drawee'])?$pay['id_drawee']: NULL),
										'remark' 			 =>  (isset($pay['remark'])?$pay['remark']: NULL),
										'payment_status'     =>  (isset($pay['id_payment_status'])?$pay['id_payment_status']: 1),
										'approval_date'     =>  (isset($pay['id_payment_status']) == 1 ? date('Y-m-d H:i:s') : null) ,
										'receipt_no'   		 =>  ($pay['id_payment_status']==1?$receipt_no: NULL)
	 	    	 				    );
	 	    	 $status = $this->$model->paymentDB("insert","",$ins_pay_array);
	 	    	 $serviceID = 6;
     			 $service = $this->$set_model->get_service($serviceID);
     			 $mail_model=self::MAIL_MODEL;
     			 $company = $this->$set_model->get_company();   
	 	    	  if($status)
				  {
					 if($this->config->item('integrationType') == 1){
					     $this->insert_common_data_jil($status['insertID']);
					 }else if($this->config->item('integrationType') == 2){
					     $this->insert_common_data($status['insertID']);
					 }
					 $pay_status_array= array(
	 			       	'id_payment'		=>  (isset($status['insertID'])?$status['insertID']: NULL), 				    	      							'id_status_msg'		=>  (isset($pay['id_payment_status'])?$pay['id_payment_status']:NULL),
	 			       	'charges' 			=>  (isset($pay['payment_amount'])?$pay['payment_amount']:NULL),
	 			       	'id_employee' 		=>  $this->session->userdata('uid'),
	 			       	'date_upd'			=>  date('Y-m-d H:i:s')
	 			       );
	 			       $ppsm = $this->$model->payment_statusDB("insert","",$pay_status_array);
	 			       $payData = $this->$model->getPpayment_data($pay_status_array['id_payment']);
	 			       if($ppsm['status'] == 1 && $check_status['id_payment_status']!=$pay_array['payment_status'])
	 			       {
	 			           $id=$ins_pay_array['id_post_payment'];
						   $data =$this->$sms_model->get_SMS_data($serviceID,$id);
						   $mobile =$data['mobile'];
						   $message = $data['message'];
					   	   if($service['serv_sms'] == 1)
							{ 
								if($this->config->item('sms_gateway') == '1'){
					    		    $this->sms_model->sendSMS_MSG91($mobile,$message);		
					    		}
					    		elseif($this->config->item('sms_gateway') == '2'){
					    	        $this->sms_model->sendSMS_Nettyfish($mobile,$message,'trans');	
					    		}
							}   
							 if($service['serv_whatsapp'] == 1){
                            	$this->admin_usersms_model->send_whatsApp_message($mobile,$message); 
                             }
						if($service['serv_email'] == 1  && $payData['email']!= '')
							{
								$data['payData'] = $payData;
								$data['company_details'] = $company;
								$data['type'] = 3;
								$to = $payData['email'];
								$subject = "Reg- ".$company['company_name']." saving scheme payment details";
								$message = $this->load->view('include/emailPayment',$data,true);
								$sendEmail = $this->$mail_model->send_email($to,$subject,$message);
							}	
					   }
					 $this->session->set_flashdata('chit_alert', array('message' => 'Payment added successfully','class' => 'success','title'=>'Scheme Payment'));
				}else{
						  $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed the requested operation','class' => 'danger','title'=>'Scheme Payment'));
				}
				redirect('payment/list');
		  }	
   	 	break;
   	 	case 'Status':
   	 		 $data['payment']=$this->$model->postdated_paymentDB('get',$id,"");
   	 		 $data['status_log']=$this->$model->post_payment_log($id);
   	 		  $data['main_content'] = self::PAY_VIEW."postdated/payment_status" ;
	 		  $this->load->view('layout/template', $data);    
   	 	break;
   	  }	
	}
   function payment($type,$id="")
    {
   	 $model =	self::PAY_MODEL;
   	 $set_model =	self::SET_MODEL;
   	 $sms_model =	self::SMS_MODEL;
     $log_model = self::LOG_MODEL;
	  $accountmodel = self::ACC_MODEL;
	  $cus_model=self::CUS_MODEL;
   	 switch($type)
   	 {
	 	case 'List':
	 		  //$data['payment']=$this->$model->menuDB('get',($id!=NULL?$id:''));
	 		  $data['main_content'] = self::PAY_VIEW."list" ;
	 		  $this->load->view('layout/template', $data);    
	 	break;
	 	case 'View':
	 	    if($id!=NULL)
	 	    {
				$data['pay'] = $this->$model->paymentDB('get',$id);
				$data['main_content'] = self::PAY_VIEW."edit_form" ;
			}
			else
			{
			   $data['pay'] = $this->$model->paymentDB();
			    $data['cus']=$this->$cus_model->ajax_get_all_customers();
			  //echo "<pre>";print_r($data);echo "</pre>";exit;
			   $data['main_content'] = self::PAY_VIEW."form" ;
			}				
			$this->load->view('layout/template', $data); 	 	      
	 	break;
	 	case 'Status':
	 		$data['pay'] = $this->$model->paymentDB('get',$id);
	 		$data['status_log']=$this->$model->payment_log($id);	 		
	 		$data['main_content'] = self::PAY_VIEW."status_form" ;
			$this->load->view('layout/template', $data);  
	 	break;
	 	case 'SaveAll':
	 	  	  $payments = $this->input->post('pay');
	 	      $generic = $this->input->post('generic');
	 	      $pdc = $this->input->post('pdc');
			  $submit_type  = $this->input->post('type');
           // echo"<pre>"; print_r($generic);
			$otp=$this->session->userdata('pay_OTP');
			$data = $this->$accountmodel->select_otp($otp);	
			$id_otp=(isset($data['id_otp']) ? $data['id_otp'] :NULL);
			 $cusData=$this->$accountmodel->get_customer_acc($generic['id_scheme_account']);
			  $used_wallet = FALSE;
	 	  	  $is_use_wallet = (isset($generic['is_use_wallet']) ? 1 :0);
	 	  	  $redeemed_amount = 0.00;
	 	  	   $redeem_request =  (isset( $generic['redeem_request']) ? floor($generic['redeem_request']) : 0) ;
	 	      $amount =0;
              $date_payment = (isset($generic['date_payment'])? date('Y-m-d',strtotime(str_replace("/","-",$generic['date_payment']))):NULL);
              $custom_entry_date = $cusData['custom_entry_date'];
              if($generic['installments']>1)
              {
			  	$amount = ($generic['payment_amount']+$generic['discountedAmt'])/$generic['installments'];
			  }
			  else
			  {
			  	$amount = $generic['payment_amount']+$generic['discountedAmt'];
			  }
			  // echo"<pre>"; print_r($generic);exit;
			  //GST calculation
			  $sch_data = $this->$model->get_schgst($generic['id_scheme_account']);
			  $gst_val = 0;
			  $gst_amt = 0;
			    if($sch_data['gst'] > 0 ){
				 	 if($sch_data['gst_type'] == 1){
						  $gst_val = $amount*($sch_data['gst']/100);	
					 	$gst_amt = $gst_val;
					 }	
				else	
				{
					 $gst_val = $amount-($amount*(100/(100+$sch_data['gst'])));
					 $gst_amt = $gst_val;
				}					
			   }
			  // END of GST calculation
			  $totalamount = ($amount*$generic['installments']);
			  // Wallet
			  $walletData = $this->$model->wallet_balance($generic['id_customer']);
	 	      if($is_use_wallet == 1 ){	
				 $allowed_redeem = ($totalamount*($walletData['redeem_percent']/100)); 
			  	 if( $allowed_redeem > $walletData['wal_balance'] ){
				 	$can_redeem = $walletData['wal_balance'];
				 }else{
				 	$can_redeem = $allowed_redeem;
				 }
				 $used_wallet = TRUE;
				 $redeemed_amount = floor($redeem_request <= $can_redeem ? $redeem_request : $can_redeem);
		      }
	 	      for($i=1;$i<=$generic['installments'];$i++)
	 	      {
	 	        	if($generic['fix_weight']==2)//scheme_type is assigned to fix weight
	 	        	{	
	 	        	    $amt = ($sch_data['gst_type'] == 0 ? ($generic['sch_amt'] - $gst_amt) :$generic['sch_amt'] );			
	 	        		$data = array ('sch_amt'=>$amt,
	 	        					   'metal_rate'=>$generic['metal_rate']);
						$metal_wgt = $this->amount_to_weight($data);
					}
					else if($generic['fix_weight']==3 && $generic['wgt_convert']==0)
					{
						$amt = $generic['payment_amount'];
	 	        		$data = array ('sch_amt'=>$amt,
	 	        					   'metal_rate'=>$generic['metal_rate']);
						$metal_wgt = $this->amount_to_weight($data);
					}
					else
					{	
						$metal_wgt = (isset($generic['metal_weight'])?$generic['metal_weight']: 0.000);
					}
					//$receipt_no = $this->generate_receipt_no();
					if($this->$model->get_rptnosettings()==1)
					{		
					  $receipt_no = $this->generate_receipt_no($cusData['id_scheme']);
					} 
					else
					{						
						$receipt_no=null;
					}
					// ND - normal, PN - pending & normal, AN - adv & normal, PD pending due ,AD-adv due
					$dueType = ''; 
					if($generic['due_type'] == 'PN'){
						$dueType = ($i==1 ? 'ND' : 'PD');
					}
					else if($generic['due_type'] == 'AN'){
						$dueType = ($i==1 ? 'ND' : 'AD');
					}
					else{
						$dueType = $generic['due_type'];
					}
					if($this->session->userdata('branch_settings')==1)
					{	
						if($this->session->userdata('is_branchwise_cus_reg')==1 && $this->config->item('payOtherBranch') == 0)
						{
							$id_branch  = $cusData['cus_reg_branch']; 
						}
						if($this->session->userdata('branchWiseLogin')==1 && $this->config->item('payOtherBranch') == 0)
						{
							$id_branch  = (isset($sch_acc['id_branch'])?$sch_acc['id_branch']:(isset($cusData['sch_join_branch'])?$cusData['sch_join_branch']: NULL));
						}
						else
						{
							$id_branch  = ($generic['id_branch']!=''?$generic['id_branch']:NULL);
						}
					}
					else{
						$id_branch =NULL;
					}
				$pay_array = array(
	 	       							'gst_type'	  => (isset($generic['gst_type'])?$generic['gst_type']: 0), 				       			 
	 	       							'gst'	  => (isset($generic['gst'])?$generic['gst']: 0), 				       			 
	 	       							'id_scheme_account'	  => (isset($generic['id_scheme_account'])?$generic['id_scheme_account']: NULL), 				       			 
	 	       							'is_editing_enabled'	  => (empty($generic['enable_editing'])?0:$generic['enable_editing']), 				       			 
	 	       							'id_employee' 		  =>  $this->session->userdata('uid'),
										'id_otp' 		  =>	 (isset($id_otp) ? $id_otp :NULL),
									//	'id_transaction'	  =>  $txnid,
								//		'date_payment'        =>  ($i > 1 ? date('Y-m-d',strtotime (($generic['total_paid']>0 ?'-':'+').($i-1).' month' , strtotime ( date('Y-m-d',strtotime($date_payment)) ))): $date_payment ), 	
										'date_payment'        =>  $date_payment,
										'custom_entry_date'   =>($cusData['edit_custom_entry_date']==1 ? $custom_entry_date:NULL),
										'id_branch' 		  => $id_branch,
										'due_type'      	  =>  $dueType, 	
										'payment_type' 	      =>  $redeemed_amount == $totalamount ? 'Wallet Payment':(isset($generic['payment_type'])?$generic['payment_type']: NULL),
										'payment_mode' 		  =>  $redeemed_amount == $totalamount ? 'Wallet':(isset($generic['payment_mode'])?$generic['payment_mode']: NULL),
										'payment_amount' 	  => $amount,
										'act_amount'          => $generic['payment_amount'],  
										'metal_rate' 		  =>  (isset($generic['metal_rate'])?$generic['metal_rate']: 0),	
										'metal_weight' 		  =>  $metal_wgt,
										'payment_ref_number'  =>  (isset($generic['payment_ref_number'])?$generic['payment_ref_number']: NULL),
										'id_post_payment' 	  =>  (isset($generic['id_post_payment'])?$generic['id_post_payment']: NULL),
										'id_drawee' 		  =>  (isset($generic['id_drawee'])?$generic['id_drawee']: NULL),	
										'remark'              =>  (isset($generic['remark'])?$generic['remark']: NULL),	
										'payment_status'      =>  $redeemed_amount == $totalamount ? 1:(isset($generic['payment_status'])?$generic['payment_status']: 1),
										'receipt_no'		  =>  $receipt_no,
										'cheque_no' 		=> (isset($pdc['cheque_no'])?$pdc['cheque_no']:''),
				    					'cheque_date' 		=> (isset($pdc['date_payment'])? date('Y-m-d',strtotime(str_replace("/","-",$pdc['date_payment']))): NULL),
				    					'bank_name' 		=>(isset($pdc['payee_bank'])?$pdc['payee_bank']:''),
									    'bank_branch' 		=> (isset($pdc['payee_branch'])?$pdc['payee_branch']:''),
									    'bank_IFSC' 		=> (isset($pdc['payee_ifsc'])?$pdc['payee_ifsc']:''),
									    'bank_acc_no' 		=> (isset($pdc['payee_acc_no'])?$pdc['payee_acc_no']:''),
									    'id_drawee' 		  =>  (isset($pdc['id_drawee'])?$pdc['id_drawee']: NULL),
									    'added_by'			=>	0,
	 			       					'date_upd'			=>  date('Y-m-d H:i:s'),
	 			       					'approval_date'		=>  date('Y-m-d H:i:s'),
	 			       					"redeemed_amount"     => (isset($redeemed_amount) ?$redeemed_amount :0.00),
	 	                            );
	 	                  if(($this->$model->firstPayamt_payable()==1)&& ($generic['paid_installments']==0))
        					{						
        					$firstPayment_amt=array(
        							  				'firstPayment_amt'=>$amount
        							  				);
        				$status = $this->$accountmodel->update_account($firstPayment_amt,$generic['id_scheme_account']);
        					}   
						 // echo"<pre>"; 	print_r($pay_array);exit;
						  if($generic['discount_installment']==$i||$generic['discount_type']==0)
						  {
						      $pay_array['discountAmt']=$generic['discountedAmt'];
						  }
	 	                     $status = $this->$model->paymentDB("insert","",$pay_array);
							//echo "<pre>"; print_r($status);
					         if($status)
							{
								// referral benefits  credit //
									$installments =($generic['installments']>1?$generic['ref_benifitadd_ins']:1);
									 $ischkref=$this->$model->get_ischkrefamtadd($generic['id_scheme_account']);
									if($generic['ref_benifitadd_ins_type']==1 && $generic['referal_code']!='' && $i==$installments && (($generic['paid_installments']+$i)== $generic['ref_benifitadd_ins']) && $ischkref==TRUE)
									{
									$this->insert_referral_data($generic['id_scheme_account']);
									}elseif($generic['ref_benifitadd_ins_type']==0 && $generic['referal_code']!='' && $ischkref==TRUE){							
										$this->insert_referral_data($generic['id_scheme_account']);
									}
									// referral benefits  credit //	
								$payid = array();
								 $pay_status_array = array(
				 			       	'id_payment'		=>  (isset($status ['insertID'])?$status['insertID']: NULL), 				    	      	
				 			       	'id_status_msg' 	=>  (isset($generic['payment_status'])?$generic['payment_status']:NULL),
				 			       	'charges' 			=>  $amount,
				 			       	'id_employee' 		=>  $this->session->userdata('uid'),
				 			       	'date_upd'			=>  date('Y-m-d H:i:s')
				 			       );
								   $payid[] = $status ['insertID'];
								   //print_r($payid); exit; 
				 			       $ppsm = $this->$model->payment_statusDB("insert","",$pay_status_array);
					 			  if( $generic['payment_status'] == 1){
								    if($this->config->item('integrationType') == 1){
								        $this->insert_common_data_jil($status['insertID']);
								    }else if($this->config->item('integrationType') == 2){
								        $this->insert_common_data($status['insertID']);
								    }
								    $acdata = $this->$model->isAcnoAvailable($generic['id_scheme_account']); 
								    $scheme_acc_no = $this->$set_model->accno_generatorset();
									// scheme account number generate
									if($acdata['status'] && ($scheme_acc_no['status']==1 && $scheme_acc_no['schemeacc_no_set']==0)){
			 			       	 		$scheme_acc_number = $this->account_model->account_number_generator($acdata['id_scheme']);
									   if($scheme_acc_number!=NULL)
										{
											$updateData['scheme_acc_number'] = $scheme_acc_number;
										}
										$updSchAc = $this->account_model->update_account($updateData,$generic['id_scheme_account']);
									} 
								 	   $payData =  $this->$model->getPpayment_data($status ['insertID']);  
										// insert wallet transactions and update intermediate wallet tables
					 	               if($used_wallet){					 
										 if($redeemed_amount > 0){
											$transData = array();
									        $pay = $this->$model->getWalletPaymentContent($generic['id_scheme_account']);  
									        if($redeemed_amount > 0){ 
							    				$transData = array('mobile' 			=> $pay['mobile'],
							    									 'actual_trans_amt'	=> $totalamount,
							    									 'available_points'	=> ($pay['isAvail'] == 0 ?0:$pay['available_points']),
							    									 'isAvail'			=> ($pay['isAvail'] == 0 ?0:1),
							    									 'redeemed_amount'	=> $redeemed_amount, 
							    									 'txnid'            => time().'-'.$generic['id_scheme_account'].'-ADM-D',
							    									 'branch'           => $pay['branch'],
							    									 'walletIntegration'=>$pay['walletIntegration'],
							    									 'wallet_points'=>$pay['wallet_points'],
							    									 'wallet_amt_per_points'=>$pay['wallet_amt_per_points'],
							    									 'wallet_balance_type'=>$pay['wallet_balance_type']
							    									); 
									    		if(!empty($transData)){
									    		    $this->insertWalletTrans($transData); 
									    		}				    		 
									         }
											 $submitpay_flag = FALSE;
											 $this->session->set_flashdata('successMsg','Payment successful');
										 }					 
									   }
					 			       	//send sms/mail to Customer 
									      $mailSubject = "Reg- ".$this->company['company_name']." saving scheme payment details";
										  $mailtype= 2;
									      $this->sendSMSMail('3',$payData,$mailSubject,$mailtype,$status['insertID']);
									   }
									   else {
									   	//send sms/mail to Customer 
									      $payData =  $this->$model->getPpayment_data($status['insertID']);  
										  $mailSubject = "Reg- ".$this->company['company_name']." saving scheme payment details";
										  $mailtype= 3;
									      $this->sendSMSMail('7',$payData,$mailSubject,$mailtype,$status['insertID']);
									  	 }
									    $this->session->set_flashdata('chit_alert', array('message' => 'Payment added successfully','class' => 'success','title'=>'Scheme Payment'));
							}        
	 	               }
					$data['payid']=$payid;
					$data['type']=$submit_type;
					$data['payment_status']=$generic['payment_status'];
					//echo $this->db->_error_message();
				//echo"<pre>";	print_r($data);exit;
				 echo json_encode($data);
	 	break;
	 	case 'Save': 
	 	    	 $pay = $this->input->post('pay');
	 	    	// $receipt_no = $this->generate_receipt_no();
				if($this->$model->get_rptnosettings()==0){						
				  $receipt_no = $this->generate_receipt_no();}
				else{						
				  $receipt_no;}
	 	    	 $txnid =uniqid(time());      
	 	    	 $pay_array = array(		
	 	    	 						'id_scheme_account'			=>  (isset($pay['id_scheme_account'])?$pay['id_scheme_account']: NULL), 				       			 
	 	    	 						'id_employee' 		=>  $this->session->userdata('uid'),
	 	    	 						'id_transaction'			=>  $txnid, 				       			 
										'date_payment'		=>	(isset($pay['date_payment'])? date('Y-m-d',strtotime(str_replace("/","-",$pay['date_payment']))): NULL), 	
										'custom_entry_date'	=>	(isset($pay['custom_entry_date'])? date('Y-m-d',strtotime(str_replace("/","-",$pay['custom_entry_date']))): NULL), 
										'payment_type' 			=>  (isset($pay['payment_type'])?$pay['payment_type']: NULL),
										'payment_mode' 			=>  (isset($pay['payment_mode'])?$pay['payment_mode']: NULL),
										'payment_amount' 			=>  (isset($pay['payment_amount'])?$pay['payment_amount']: 0),
										'metal_rate' 			=>  (isset($pay['metal_rate'])?$pay['metal_rate']: 0),
										'metal_weight' 			=>  (isset($pay['metal_weight'])?$pay['metal_weight']: 0.000),
										'payment_ref_number' 			=>  (isset($pay['payment_ref_number'])?$pay['payment_ref_number']: NULL),
										'id_post_payment' 			=>  (isset($pay['id_post_payment'])?$pay['id_post_payment']: NULL),
										'id_drawee' 			=>  (isset($pay['id_drawee'])?$pay['id_drawee']: NULL),
										'remark' 			=>  (isset($pay['remark'])?$pay['remark']: NULL),
										'payment_status'    =>  (isset($pay['payment_status'])?$pay['payment_status']: 1),
										'receipt_no'    =>  ($pay['payment_status'] == 1?$receipt_no: NULL)
	 	    	 				    );
		         $status = $this->$model->paymentDB("insert","",$pay_array);
		         if($status)
				{
					 $pay_status_array= array(
	 			       	'id_payment'	=>  (isset($status ['insertID'])?$status['insertID']: NULL), 				    	       'id_status_msg' 			=>  (isset($pay['payment_status'])?$pay['payment_status']:NULL),
	 			       	'charges' 			=>  (isset($pay['payment_amount'])?$pay['payment_amount']:NULL),
	 			       	'id_employee' 		=>  $this->session->userdata('uid'),
	 			       	'date_upd'			=>  date('Y-m-d H:i:s')
	 			       );
	 			         $ppsm = $this->$model->payment_statusDB("insert","",$pay_status_array);
 						 if( $pay['payment_status'] == 1){
		 			       	//send sms/mail to Customer if success
						      $payData =  $this->$model->getPpayment_data($status ['insertID']);  
							  $mailSubject = "Reg- ".$this->company['company_name']." saving scheme payment details";
							  $mailtype= 2;
						      $this->sendSMSMail('3',$payData,$mailSubject,$mailtype,$status['insertID']);
						   }
						 else {
						   	//send pay status sms/mail to Customer 
						      $payData =  $this->$model->getPpayment_data($status['insertID']);  
							  $mailSubject = "Reg- ".$this->company['company_name']." saving scheme payment details";
							  $mailtype= 3;
						      $this->sendSMSMail('7',$payData,$mailSubject,$mailtype,$status['insertID']);
						  	 }	
	 			                 $log_data = array(
													'id_log'     => $this->id_log,
													'event_date' => date("Y-m-d H:i:s"),
													'module'     => 'Payment',
													'operation'  => 'Add',
													'record'     => $status['insertID'],  
													'remark'     => 'Payment added successfully'
												 );
						        $this->$log_model->log_detail('insert','',$log_data);	
					 $this->session->set_flashdata('chit_alert', array('message' => 'Payment added successfully','class' => 'success','title'=>'Scheme Payment'));
				}else{
						  $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed the requested operation','class' => 'danger','title'=>'Scheme Payment'));
				}
				redirect('payment/list');
	 	break;
	 	case 'Update':
	 	    	  $pay = $this->input->post('pay');	 
			 $cusData=$this->$accountmodel->get_customer_acc($pay['id_scheme_account']);
	 	    	  $check_status = $this->$model->paymentDB("get",$id);
				  $submit_type  = $this->input->post('type1');
	 	    	 // $receipt_no = $this->generate_receipt_no();
				  if($this->$model->get_rptnosettings()==1){						
				     $receipt_no = $this->generate_receipt_no($cusData['id_scheme']);}
				 else{						
					  $receipt_no = NULL;}
	 	    	  $pay_array = array(		
	 	    	 						'id_scheme_account'			=>  (isset($pay['id_scheme_account'])?$pay['id_scheme_account']: NULL), 
	 	    	 						'id_employee' 		=>  $this->session->userdata('uid'),
										'date_payment'		=>	(isset($pay['date_payment'])? date('Y-m-d',strtotime(str_replace("/","-",$pay['date_payment']))): NULL), 	
										'payment_type' 			=>  (isset($pay['payment_type'])?$pay['payment_type']: NULL),
										'payment_mode' 			=>  (isset($pay['payment_mode'])?$pay['payment_mode']: NULL),
										'payment_amount' 			=>  (isset($pay['payment_amount'])?$pay['payment_amount']: 0),
										'metal_rate' 			=>  (isset($pay['metal_rate'])?$pay['metal_rate']: 0),
										'metal_weight' 			=>  (isset($pay['metal_weight'])?$pay['metal_weight']: 0.000),
										'payment_ref_number' 			=>  (isset($pay['payment_ref_number'])?$pay['payment_ref_number']: 0.000),
										'remark' 			=>  (isset($pay['remark'])?$pay['remark']: NULL),
										'payment_status'    =>  (isset($pay['payment_status'])?$pay['payment_status']: 2),
										//'receipt_no'    =>  ($pay['payment_status']==1?($check_status['receipt_no'] == '' ? $receipt_no : $check_status['receipt_no']):''),
										'receipt_no'    =>  ($pay['payment_status']==1 && $check_status['receipt_no_set'] ==1 && $check_status['receipt_no_set']==1?$pay['receipt_no']:($pay['payment_status']==1?($check_status['receipt_no'] == '' ? $receipt_no : $check_status['receipt_no']):''))
	 	    	 				    );
		       // delete data in customer_reg and transaction if changed from success to other status
				if($check_status['id_payment_status'] == 1 && $pay['payment_status'] !== 1){
					// $this->deleteCusandPaydata($id);
				}
				$status = $this->$model->paymentDB("update",$id,$pay_array);
				 //print_r($this->db->last_query());exit;
				$scheme_acc_no=$this->$set_model->accno_generatorset();
				if(($status['status']==1 && $pay['scheme_acc_number']=='Not Allocated' && $pay['payment_status']==1) && ($scheme_acc_no['status']==1 && $scheme_acc_no['schemeacc_no_set']==0))
				{
					$acdata = $this->$model->isAcnoAvailable($pay_array['id_scheme_account']);
					$scheme_acc_number=$this->account_model->account_number_generator($acdata['id_scheme']);
				   if($scheme_acc_number!=NULL)
					{
						$updateData['scheme_acc_number']=$scheme_acc_number;
					}
				 $updSchAc = $this->account_model->update_account($updateData,$pay['id_scheme_account']);
			   }
		         if($status)
				{
					if(in_array($check_status['due_type'], array('S','P','A')) && $check_status['id_payment_status']!=$pay['payment_status'] && $pay['payment_type']=='Payu Checkout'){
					 $getPayments = $this->$model->getData_matchedRefno($pay['payment_ref_number']);
					 $array = array(	'payment_status'=>$pay_array['payment_status'],'receipt_no'=>'');
					 $stat = $this->$model->paymentDB("update",$getPayments['parentId'],$array);
					 if($stat){
					 	foreach($getPayments['splittedId'] as $payid){
						 $delete = $this->$model->paymentDB("delete",$payid['id_payment']);
					      if($delete)
							{
								  $log_data = array(
														'id_log'     => $this->id_log,
														'event_date' => date("Y-m-d H:i:s"),
														'module'     => 'Payment',
														'operation'  => 'Delete',
														'record'     => $payid['id_payment'],  
														'remark'     => 'Splitted Payment deleted successfully'
													 );
								  $this->$log_model->log_detail('insert','',$log_data);	
							}
					  	 }	
					   }			
					}
					  $log_data = array(
											'id_log'     => $this->id_log,
											'event_date' => date("Y-m-d H:i:s"),
											'module'     => 'Payment',
											'operation'  => 'Edit',
											'record'     => $status['updateID'],  
											'remark'     => 'Payment updated successfully'
										 );
					  $this->$log_model->log_detail('insert','',$log_data);	
					$paymentid = array();
					 $pay_status_array= array(
	 			       	'id_payment'	=>  (isset($status ['updateID'])?$status['updateID']: NULL), 				    	       'id_status_msg' 			=>  (isset($pay['payment_status'])?$pay['payment_status']:NULL),
	 			       	 	'charges' 			=>  (isset($pay['payment_amount'])?$pay['payment_amount']:NULL),
	 			       	'id_employee' 		=>  $this->session->userdata('uid'),
	 			       	'date_upd'			=>  date('Y-m-d H:i:s')
	 			       );
	 			       //print_r($this->db->last_query());exit;
					   $paymentid[] = $status ['updateID'];
	 			       $ppsm = $this->$model->payment_statusDB("insert","",$pay_status_array);
	 			       if($check_status['id_payment_status']!=$pay['payment_status']){
	 			        if( $pay['payment_status'] == 1){
	 			        	 if($this->config->item('integrationType') == 1){
	 			        	     $this->insert_common_data_jil($status['updateID']);
	 			        	 }else if($this->config->item('integrationType') == 2){
	 			        	     $this->insert_common_data($status['updateID']);
	 			        	 }
		 			       	//send sms/mail to Customer if success
						      $payData =  $this->$model->getPpayment_data($status['updateID']);  
							  $mailSubject = "Reg- ".$this->company['company_name']." saving scheme payment details";
							  $mailtype= 2;
						      $this->sendSMSMail('3',$payData,$mailSubject,$mailtype,$status['updateID']);
						   }
						 else {
						   	//send pay status sms/mail to Customer 
						      $payData =  $this->$model->getPpayment_data($status['updateID']);  
							  $mailSubject = "Reg- ".$this->company['company_name']." saving scheme payment details";
							  $mailtype= 3;
						      $this->sendSMSMail('7',$payData,$mailSubject,$mailtype,$status['updateID']);
						  	 }
						}
								$data['paymentid']=$paymentid;
								$data['type1']=$submit_type;
								$data['payment_status']=$pay['payment_status'];
							 echo json_encode($data); 
					// $this->session->set_flashdata('chit_alert', array('message' => 'Payment updated successfully','class' => 'success','title'=>'Scheme Payment'));
				}else{
						        $data['paymentid']=$paymentid;
								$data['type1']=0;
								$data['payment_status']=$pay['payment_status'];
							 echo json_encode($data); 
				}
				//redirect('payment/list');
	 	break;
	 	case 'Delete':
	 	      $status = $this->$model->paymentDB("delete",$id);
		         if($status)
				{
					  $log_data = array(
											'id_log'     => $this->id_log,
											'event_date' => date("Y-m-d H:i:s"),
											'module'     => 'Payment',
											'operation'  => 'Delete',
											'record'     => $id,  
											'remark'     => 'Payment deleted successfully'
										 );
					  $this->$log_model->log_detail('insert','',$log_data);	
					 $this->session->set_flashdata('chit_alert', array('message' => 'Payment deleted successfully','class' => 'success','title'=>'Scheme Payment'));
				}else{
						  $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed the requested operation','class' => 'danger','title'=>'Scheme Payment'));
				}
				redirect('payment/list');
	 	break;
	 	default:
			 	$set_model=self::SET_MODEL;
			    $access = $this->$set_model->get_access('payment/list');
				$profile=$this->session->userdata('profile');
			  	if(!empty($_POST))
			  	{
					$range['from_date']  = $this->input->post('from_date');
					$range['to_date']  = $this->input->post('to_date');
					$date_type=$this->input->post('date_type');
					$items=$this->$model->payment_list_range($range['from_date'],$range['to_date'],'','',$date_type);					
				}
				else
				{					
					$items=$this->$model->payment_list(($id!=NULL?$id:''),50);	
				}
		        $payment = array(
		        				    'access' => $access,
		        					'data'   => $items,
									'profile'=>$profile,
		        				);  
				echo json_encode($payment);
	 }
   }
	function amount_to_weight($to_pay)
	{
		$converted_metal_wgt = $to_pay['sch_amt']/$to_pay['metal_rate'];
		return $converted_metal_wgt;
	}
   function postdate_payment($type,$id="")
   {
   		$model		 = self::PAY_MODEL;
   		$log_model	 = self::LOG_MODEL;
   		$set_model	 = self::SET_MODEL;
   		$sms_model	 = self::SMS_MODEL;
   		$company = $this->$set_model->get_company();
		$mail_model=self::MAIL_MODEL;
   		$total_inserted = array();
   		switch($type)
   		{
			case 'List':
				$data['pay'] = $this->$model->paymentDB();
			    $data['main_content'] = self::PAY_VIEW."postdated/list" ;
			    $this->load->view('layout/template', $data); 
			break;
			case 'View':
			   $data['main_content'] = self::PAY_VIEW."postdated/form" ;
			   $this->load->view('layout/template', $data); 
			break;
			case 'Save':
			  $payments = $this->input->post('pay') ;
			 /* echo "<pre>";
			  print_r($payments); echo "</pre>";exit;*/
			  foreach($payments as $pay )
			  {//print_r($pay);print_r($pay['id_scheme_account']);exit;
			  	 $pay_array=array(
				    'date_payment' 		=> (isset($pay['date_payment'])? date('Y-m-d',strtotime(str_replace("/","-",$pay['date_payment']))): NULL),
				    'cheque_no' 		=> (isset($pay['cheque_no'])?$pay['cheque_no']:''),
				    'id_employee' 		=>  $this->session->userdata('uid'),
				    'id_scheme_account' => (isset($pay['id_scheme_account'])?$pay['id_scheme_account']:''),
				    'pay_mode' 			=> (isset($pay['pay_mode'])?$pay['pay_mode']:''),
				    'payee_bank' 		=>(isset($pay['payee_bank'])?$pay['payee_bank']:''),
				    'payee_branch' 		=> (isset($pay['payee_branch'])?$pay['payee_branch']:''),
				    'payee_ifsc' 		=> (isset($pay['payee_ifsc'])?$pay['payee_ifsc']:''),
				    'payee_acc_no' 		=> (isset($pay['payee_acc_no'])?$pay['payee_acc_no']:''),
				    'id_drawee' 	=>  (isset($pay['id_drawee'])?$pay['id_drawee']:''),
				    'amount' 			=> (isset($pay['amount'])?$pay['amount']:''),
				    'payment_status' 	=>  7
			  	 );
			  	 //print_r($pay_array);exit;
			  	 if($pay_array['id_scheme_account']!=''){
				 	   $status = $this->$model->postdated_paymentDB("insert","",$pay_array);
				 	    $log_data = array(
											'id_log'     => $this->id_log,
											'event_date' => date("Y-m-d H:i:s"),
											'module'     => 'Post-Dated Payment',
											'operation'  => 'Add',
											'record'     => $status['updateID'],  
											'remark'     => 'Post-Dated Payment added successfully'
										 );
					  $this->$log_model->log_detail('insert','',$log_data);
				 	    $pay_status_array= array(
	 			       	'id_post_payment'	=>  (isset($status ['insertID'])?$status['insertID']: NULL), 				    	     
	 			       	'id_status_msg' 			=>  7,
	 			       	'id_employee' 		=>  $this->session->userdata('uid'),
	 			       	'date_upd'			=>  date('Y-m-d H:i:s')
	 			       );
	 			       $ppsm = $this->$model->payment_statusDB("insert","",$pay_status_array);
	 			       $total_inserted[]=$ppsm ['insertID'];
				 	}
			  }
			  					$serviceID = 5;
		     				  	$service = $this->$set_model->get_service($serviceID);	
	     				  		$payData = $this->$model->post_paymentlist($status ['insertID']);
	     				  		$company = $this->$set_model->get_company();
						  	    $mail_model=self::MAIL_MODEL;
						  	    $id=$status['insertID'];
								$data =$this->$sms_model->get_SMS_data($serviceID,$id);
								$mobile =$data['mobile'];
								$message = $data['message'];
		     				  		 	if($service['serv_sms'] == 1 )
										{	
                                            if($this->config->item('sms_gateway') == '1'){
								    		    $this->sms_model->sendSMS_MSG91($mobile,$message);	
								    		}
								    		elseif($this->config->item('sms_gateway') == '2'){
								    	        $this->sms_model->sendSMS_Nettyfish($mobile,$message,'trans');	
								    		}
										}
										if($service['serv_whatsapp'] == 1){
                                	        $this->admin_usersms_model->send_whatsApp_message($mobile,$message); 
                                            }
										if($service['serv_email'] == 1  && $payData['email']!= '')
											{
												$data['payData'] =  $payData;
												$data['company_details'] = $company;
												$data['type'] = 4;
												$data['total_chq'] = count($total_inserted);
												$to =  $payData['email'];;
												$subject = "Reg- ".$company['company_name']." saving scheme payment details";
												$message = $this->load->view('include/emailPayment',$data,true);
												$sendEmail = $this->$mail_model->send_email($to,$subject,$message);
											}	
			   	 $this->session->set_flashdata('chit_alert', array('message' => 'Post dated payment added successfully','class' => 'success','title'=>'Post dated payment'));
				 redirect('postdated/payment/list');
			break;
			case 'Update':
			     //to update presented data in dashboard
			      $payments  = (array)json_decode($this->input->post("postpay_data"));
	 			   foreach($payments as $pay)
	 			   {
	 			   	 $update_array = array('payment_status'  => $pay->payment_status);
			         $id_post_payment = $pay->id_payment;
			         $ppay_insert = $this->$model->postdated_paymentDB("update",$id_post_payment,$update_array);
	 			       //insert status Table
	 			        $log_data = array(
											'id_log'     => $this->id_log,
											'event_date' => date("Y-m-d H:i:s"),
											'module'     => 'Post-Dated Payment',
											'operation'  => 'Edit',
											'record'     => $id_post_payment,  
											'remark'     => 'Post-Dated Payment edited successfully'
										 );
					  $this->$log_model->log_detail('insert','',$log_data);
	 			       $pay_status_array= array(
	 			       	'id_post_payment'	=>  (isset($pay->id_payment)?$pay->id_payment: NULL), 				    				'id_status_msg' 	=>  (isset($pay->payment_status)?$pay->payment_status:NULL),
	 			       	'id_employee' 		=>  $this->session->userdata('uid'),
	 			       	'date_upd'			=>  date('Y-m-d H:i:s')
	 			       );
	 			       $ppsm = $this->$model->payment_statusDB("insert","",$pay_status_array);
	 			   	   //insert in payment Table on success status
	 			   	  if($pay->payment_status == 1) 
	 			   	  {
	 			   	  	 //$receipt_no = $this->generate_receipt_no();
						 if($this->$model->get_rptnosettings()==0){						
						   $receipt_no = $this->generate_receipt_no();}
						   else{						
							$receipt_no;}
					  	 $pay_array = array(		
		 	    	 						'id_scheme_account'			=>  (isset($pay->id_scheme_account)?$pay->id_scheme_account: NULL), 				       			 
											'date_payment'		=>	(isset($pay->date_payment)? date('Y-m-d',strtotime(str_replace("/","-",$pay->date_payment))): NULL), 	
											'payment_type' 			=>  "PDC/ECS",
											'payment_mode' 			=>  (isset($pay->pay_mode)?$pay->pay_mode: NULL),
											'payment_amount' 			=>  (isset($pay->payment_amount)?$pay->payment_amount: 0.00),
											'metal_weight' 			=>  (isset($pay->weight)?$pay->weight: 0.000),
											'metal_rate' 			=>  (isset($pay->metal_rate)?$pay->metal_rate: 0.000),
											'cheque_no' 			=>  (isset($pay->cheque_no)?$pay->cheque_no: NULL),
											'bank_acc_no' 			=>  (isset($pay->bank_acc_no)?$pay->bank_acc_no: NULL),
											'bank_name' 			=>  (isset($pay->bank_name)?$pay->bank_name:NULL),
											'payment_ref_number' 			=>  (isset($pay->payment_ref_number)?$pay->payment_ref_number:NULL),
											'payment_status' 			=>  (isset($pay->payment_status)?$pay->payment_status:NULL),
											'remark' 			=>  (isset($pay->remark)?$pay->remark: NULL),
											'receipt_no' 			=>  $receipt_no
		 	    	 				    );
		 	    	      $pay_insert = $this->$model->paymentDB("insert","",$pay_array);
		 	    	      if($pay_insert['status'])
		 	    	      {
							  if($this->config->item('integrationType') == 1){
						        $this->insert_common_data_jil($pay_insert['insertID']);
		 	    	          }else if($this->config->item('integrationType') == 2){
		 	    	              $this->insert_common_data($pay_insert['insertID']);
		 	    	          }
		 	    	      	$acdata = $this->$model->isAcnoAvailable($pay_array['id_scheme_account']);
	 			       	 	if($acdata['status']){
	 			       	 		$scheme_acc_number=$this->account_model->account_number_generator($acdata['id_scheme']);
							   if($scheme_acc_number!=NULL)
								{
									$updateData['scheme_acc_number']=$scheme_acc_number;
								}
								$updSchAc = $this->account_model->update_account($updateData,$pay_array['id_scheme_account']);
							}
						  	  $payData = $this->$model->getPpayment_data($pay_insert['insertID']);
							  $mailSubject =$subject = "Reg- ".$this->company['company_name']." saving scheme payment details";
							  $mailtype = 3;
						      $this->sendSMSMail('6',$payData,$mailSubject,$mailtype,$pay_status_array['id_post_payment']);
						  }		   
		 	    	      //log entry
		 	    	       $log_data = array(
													'id_log'     => $this->id_log,
													'event_date' => date("Y-m-d H:i:s"),
													'module'     => 'Payment',
													'operation'  => 'Add',
													'record'     => $pay_insert['insertID'],  
													'remark'     => 'Payment added successfully'
												 );
						        $this->$log_model->log_detail('insert','',$log_data);	
			          }
						 if($ppay_insert['status'])
					 	 {	       
						  	  $payData = $this->$model->getPostpayment_data($pay_status_array['id_post_payment']);
							  $mailSubject =$subject = "Reg- ".$this->company['company_name']." saving scheme payment details";
						      $this->sendSMSMail('6',$payData,$mailSubject,'3',$pay_status_array['id_post_payment']);
						   }  		 
		 	    }	 	
			      echo "Status updated successfully";
			break;
			case 'Delete':
			break;
			default:
				$set_model=self::SET_MODEL;
			    $access = $this->$set_model->get_access('postdated/payment/list');
			  	if(!empty($_POST))
			  	{
					$range['from_date']  = $this->input->post('from_date');
					$range['to_date']  = $this->input->post('to_date');
					$items=$this->$model->post_paymentlist_range($range['from_date'],$range['to_date']);
				}
				else
				{
					$items=$this->$model->post_paymentlist(($id!=NULL?$id:''));	 
				}
		        $payment = array(
		        				    'access' => $access,
		        					'data'   => $items
		        				);  
				echo json_encode($payment);
		}
   }
    public function generateInvoice($payment_no,$id_scheme_account)
   {
   	  $model =	self::PAY_MODEL;	
   	  $set =	self::SET_MODEL;	
	  $data['is_print_taken']=1;
		$result= $this->$model->update_payment_status($payment_no,$data);
   	   $data['records'] = $this->$model->get_invoiceData($payment_no,$id_scheme_account);
   	  $data['records_sch'] = $this->$model->get_paymentContent($data['records'][0]['id_scheme_account']);
   	  $data['gstSplitup'] = $this->$model->get_gstSplitupData($data['records'][0]['id_scheme'],$data['records'][0]['date_add']);
   	  $data['receipt'] = $this->$set->receipt_type();
	    if($this->branch_settings==1){
			$data['comp_details']=$this->$set->get_branchcompany($data['records'][0]['id_branch']);
		}else{
			 $data['comp_details'] = $this->$set->get_company();
		    }
   	  $data['records'][0]['amount_in_words'] = $this->no_to_words($data['records'][0]['payment_amount']);
   	 	//echo "<pre>";print_r($data);echo "</pre>";exit;
   	   //create PDF receipt
		if($data['receipt']['receipt']==0)
		{
	   	    $this->load->helper(array('dompdf', 'file'));
	        $dompdf = new DOMPDF();
			//$html = $this->load->view('include/receipt1', $data,true);
			$html = $this->load->view('include/receipt_temp', $data,true);
			//echo $html;exit;
		    $dompdf->load_html($html); 
			$dompdf->set_paper("a4", "portriat" );
			$dompdf->render();
			$dompdf->stream("receipt1.pdf",array('Attachment'=>0));
		}
		else if($data['receipt']['receipt']==1)
		{
			$this->load->helper(array('dompdf', 'file'));
	        $dompdf = new DOMPDF();
			//$html = $this->load->view('include/receipt1', $data,true);
			$html = $this->load->view('include/receipt_custom', $data,true);
			//echo $html;exit;
		    $dompdf->load_html($html); 
			$customPaper = array(0,0,210,400);
			$dompdf->set_paper($customPaper, "portriat" );
			$dompdf->render();
			$dompdf->stream("receipt1.pdf",array('Attachment'=>0));
		}
		else if($data['receipt']['receipt']==2)
		{
			$this->load->helper(array('dompdf', 'file'));
	        $dompdf = new DOMPDF();
			//$html = $this->load->view('include/receipt1', $data,true);
			$html = $this->load->view('include/receipt_thermal', $data,true);
		    $dompdf->load_html($html);
			$customPaper = array(0,0,40,20);
			$dompdf->set_paper($customPaper, "portriat" );
			$dompdf->render();
			$dompdf->stream("receipt1.pdf",array('Attachment'=>0));
		}
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
	function online_payment_list()
	{
		 $data['main_content'] = self::PAY_VIEW."online_payments" ;
		 $this->load->view('layout/template', $data); 
	}
	function verify_payment_view()
	{
		 $data['main_content'] = self::PAY_VIEW."verify_payment" ;
		 $this->load->view('layout/template', $data); 
	}
// settled pay show in payment apprval page with filter//HH
	function ajax_onlinePayments()
	{      
	      	$model =	self::PAY_MODEL;
		 	$set_model=self::SET_MODEL;
		  		if(!empty($_POST['from_date']))
		  	{
				$range['from_date']  = $this->input->post('from_date');
				$range['to_date']  = $this->input->post('to_date');
				$range['type']  = $this->input->post('type');
				$range['limit']  = $this->input->post('limit');
				$date_type  = $this->input->post('date_type');
				$range['settle']  = $this->input->post('settle');
				$data['data']=$this->$model->onlinePayments_range($range['from_date'],$range['to_date'],$range['limit'],$date_type,$range['settle']);
			}
			else
			{
               $range['settle']  = $this->input->post('settle'); 
				$data['data']=$this->$model->onlinePayments('',$range['settle']);
			}
					echo json_encode($data);
	}
// settled pay show in payment apprval page with filter//	
	// for payment approval status update 
	function update_pay_status() 
	{
		$model      = self::PAY_MODEL;
		$log_model = self::LOG_MODEL;
		$set_model = self::SET_MODEL;
		$p_status   = $this->input->post('pay_status');
		$pay_ids    = $this->input->post('pay_id');
		$transData  = array();
		if(!empty($pay_ids) && count($pay_ids)>0 && $p_status!=NULL)
		{
			$pay_status = array('payment_status'=>$p_status); 
			$ischkref=FALSE;
			foreach($pay_ids as $id_payment)
			{
				$update	=	$this->$model->update_payment_status($id_payment,$pay_status);
				/*For success pay: insert data in inter tables.Based on settings generate a/c no & receipt no*/
				if( $p_status == 1 ){
					if($this->config->item('integrationType') == 1){
					    $this->insert_common_data_jil($id_payment);
					}else if($this->config->item('integrationType') == 2){
					    $this->insert_common_data($id_payment);
					}
					$pay =  $this->$model->paymentDB("get",$id_payment); 	
					// Referral Code :- allow_referral - 0 => No , 1 => Yes
					if($pay['allow_referral'] == 1){
					    $ref_data	=	$this->$model->get_refdata($pay['id_scheme_account']);
    					$ischkref	=	$this->$model->get_ischkrefamtadd($pay['id_scheme_account']);	
    					if($ref_data['ref_benifitadd_ins_type'] == 1 && $ref_data['referal_code'] != '' && ($ref_data['ref_benifitadd_ins'] == $ref_data['paid_installments']) && $ischkref == TRUE){	
    						$this->insert_referral_data($ref_data['id_scheme_account']);
    					}else if($ref_data['ref_benifitadd_ins_type'] == 0 && $ref_data['referal_code'] != '' && $ischkref == TRUE){
    						$this->insert_referral_data($ref_data['id_scheme_account']);
    					}
					}
					// Account number :- schemeacc_no_set - 0 => generate a/c no ,  0 => manual a/c no , 2 => integration
					if($pay['schemeacc_no_set'] == 0 ){   
						// Generate a/c no
						if($pay['acc_no'] == '' ||  $pay['acc_no'] == null){
							$scheme_acc_number = $this->account_model->account_number_generator($pay['id_scheme']);
							if($scheme_acc_number != NULL){
								$updateData['scheme_acc_number'] = $scheme_acc_number;
							}
							$updSchAc = $this->account_model->update_account($updateData,$pay['id_scheme_account']);
						}
					}
					// Receipt Number :-  receipt_no_set - 0 => Donot generate , 1 => generate
					if($pay['receipt_no_set'] == 1 ){  
						$receipt_no = $this->generate_receipt_no($pay['id_scheme']);
						$pay_array = array('receipt_no'=>$receipt_no,'approval_date'=>date("Y-m-d H:i:s"));  
						$result =  $this->$model->paymentDB("update",$id_payment,$pay_array); 
					}
					if($pay['edit_custom_entry_date'] == 1 ){  
						$pay_array = array('custom_entry_date'=>$pay['custom_entry_date']);  
						$result =  $this->$model->paymentDB("update",$id_payment,$pay_array); 
					}
					if($pay['firstPayamt_as_payamt'] == 1 || $pay['firstPayamt_payable'] == 1)
					{
					    	$pay_array = array('firstPayment_amt'=>$pay['payment_amount']);
					    	$result =  $this->account_model->update_account($pay_array,$pay['id_scheme_account']); 
					}
				}
				// Update log and send sms and email on successful updation
				if($update == 1){
					$log_data = array(
					'id_log'     => $this->id_log,
					'event_date' => date("Y-m-d H:i:s"),
					'module'     => 'Payment',
					'operation'  => 'Update',
					'record'     => $id_payment,  
					'remark'     => 'Online Payment updated successfully'
					);
					$this->$log_model->log_detail('insert','',$log_data);
					$pay_data =  $this->$model->getPpayment_data($id_payment);  
					$mailSubject = "Reg- ".$this->company['company_name']." saving scheme account payment details";
					$mailtype = 3;
					$this->sendSMSMail('7',$pay_data,$mailSubject,$mailtype,$id_payment);
				}
			}
			$this->session->set_flashdata('chit_alert',array('message'=> count($pay_ids).' Payment record updated as successfully...','class'=>'success','title'=>'Payment Approval'));	
		}
		else
		{
			$this->session->set_flashdata('chit_alert',array('message'=> 'Unable to proceed the requested operation...','class'=>'danger','title'=>'Payment Approval'));
		}	
	}
	function ajax_get_payment($id_payment)
	{
		$model      = self::PAY_MODEL;
		$payment = $this->$model->get_online_payment($id_payment);
		//echo "<pre>";print_r($payment);echo "</pre>";exit;
		echo json_encode($payment);
	}
	function ajax_online_payment()
	{      
	      	$model =	self::PAY_MODEL;
		 	$set_model=self::SET_MODEL;
		   	$range['pg_code']=$this->input->post('pg_code');
		  	if($_POST['from_date']!='' &&$_POST['to_date']!='')
		  	{
				$range['from_date']  = $this->input->post('from_date');
				$range['to_date']  = $this->input->post('to_date');
				$range['type']  = $this->input->post('type');
				$range['limit']  = $this->input->post('limit');
				$range['pg_code']=$this->input->post('pg_code');
				 $data['data']=$this->$model->payment_online_range($range['from_date'],$range['to_date'],$range['limit'],$range['pg_code']);
			}
			else
			{
				$data['data']=$this->$model->payment_online($range['pg_code']);
			}
			echo json_encode($data);
	}
	function split_payment($id_payment)
	{
		//    $serv_model= self::SERV_MODEL;
		$payment = $this->payment_model->getPaymentByID($id_payment);
		if(!empty($payment))
		{
			$date_paid   		 = $payment['date_payment'];
			$txnid        		 = $payment['id_transaction'];
			$act_amt       		 = $payment['act_amount'];
		    $dues                = $payment['no_of_dues'] - 1;   
			for($i=1;$i<=$dues;$i++)
			{
			//   $paid_date = date('Y-m-d H:i:s', strtotime($date_paid.' +'.$i.' months'));
			   //$receipt_no 		 = $this->generate_receipt_no();
			   if($this->$model->get_rptnosettings()==0){						
				   $receipt_no = $this->generate_receipt_no();}
				   else{						
					$receipt_no;}
			   $insertData = array(
									"id_scheme_account"	 => $payment['id_scheme_account'],
									"id_transaction" 	 => $txnid."-S".$i, 
									"payment_amount" 	 => $payment['payment_amount'], 
									"payment_type" 	     => "Payu Checkout", 
									"due_type" 		     => "S", 
									"date_payment" 		 => $date_paid,
									"act_amount" 		 => $act_amt,
									"metal_rate" 		 => (isset($payment['metal_rate']) ? $payment['metal_rate'] : '0.00'),
									"metal_weight" 		 => (isset($payment['metal_weight']) ? $payment['metal_weight'] : '0.00'),
									"bank_name"			 =>	(isset($payment['issuing_bank']) ? $payment['issuing_bank'] : NULL),
									"payment_mode"       => (isset($payment['payment_mode']) ? $payment['payment_mode'] : NULL),
									"card_no"			 => (isset($payment['card_no']) ? $payment['card_no'] : NULL),
									"card_holder"		 => (isset($payment['card_holder']) ? $payment['card_holder'] : NULL),
									"payment_ref_number" => (isset($payment['payment_ref_number']) ? $payment['payment_ref_number'] : NULL ),
									"remark"             =>  ' Splitted from transactionid '.$txnid.' paid on '.$date_paid,   
									"payment_status"     => 1,
									"receipt_no"		 => $receipt_no,
									'approval_date'		=>date("Y-m-d H:i:s")
								);
				  $this->db->trans_begin();				
				$split_data = $this->payment_model->insert_payment($insertData);   
				/*
				$serviceID =7;
				$service = $this->services_modal->checkService($serviceID);
				if($split_data['status'] == true && isset($split_data['insertID']))
				{
					$id=$split_data['insertID'];
					if($service['sms'] == 1)
					{
						$data =$this->$serv_model->get_SMS_data($serviceID,$id);
						$mobile =$data['mobile'];
						$message = $data['message'];
						$this->send_sms($mobile,$message);
					}
					$invoiceData = $this->payment_modal->get_paymenMailData($id);
					if($service['email'] == 1 && isset($invoiceData[0]['email']) && $invoiceData[0]['email'] != '')
					{ 
						$to = $invoiceData[0]['email'];
						$subject = "Reg - ".$this->comp['company_name'].($payment['due_type'] =='A'?' advance ':' pending ')." payment for the saving scheme";
						$data['payData'] = $invoiceData[0];
						$data['type'] = 3;
						$data['company_details'] = $this->comp;
						$message = $this->load->view('include/emailPayment',$data,true);
						$sendEmail = $this->email_model->send_email($to,$subject,$message);	
					}		
				}	 */			
			}
			 if($this->db->trans_status()===TRUE)
	             {
				 	$this->db->trans_commit();
				 	return TRUE;
				 }
				 else{
				    return FALSE;
				 }
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
		curl_close($ch);
	  return json_decode($output);
	}	
	function monthly_rate($by)
	{
		$model = self::PAY_MODEL;
		$data['rate'] = $this->$model->monthly_rate($by);
		echo json_encode($data);
	}
    function update_settlement()
    {
    	$model = self::PAY_MODEL;
    	$scheme = array();
    	$scheme = $_POST['scheme'];
    	$set_data =array(
    						'schemes' =>count($scheme),
    						'id_employee' => $this->employee
    					);
        $set_status =	$this->$model->weight_settlementDB('insert','',$set_data);	
      	 foreach($scheme as $scheme)
    	 {
			$payments = $this->$model->payments_by_scheme($scheme['id']);
			if(!empty($payments))
			{
				foreach($payments as $pay)
				{
					$rate = $scheme['rate'];	
					if($rate!=null && $rate!='')
					{	
					    $weight = $pay['payment_amount']/$rate;
						//update payment				
							$pay_array = array(
									'metal_rate'   => $rate,
									'metal_weight' => number_format((float)$weight, 3, '.', ''),		
									'fix_weight' => 1		
					             );
					      $pay_status =  $this->$model->paymentDB("update",$pay['id_payment'],$pay_array); 
					      //send sms/mail to Customer 
					      $pay_data =  $this->$model->getPpayment_data($pay['id_payment']);  
						  $mailSubject = "Reg- ".$this->company['company_name']." saving scheme account settlement details";
					      $this->sendSMSMail('14',$pay_data,$mailSubject,'5',$pay['id_payment']);
					      //insert settlement detail    
					      if($pay_status['status'])
					      {
						  	 $set_det = array(
						  	 					'id_settlement' 	=> $set_status['insertID'],
						  	 					'id_payment'        => $pay['id_payment'],
						  	 					'id_scheme_account'	=> $pay['id_scheme_account'],
						  	 					'metal_rate'       	=> $rate,
									            'metal_weight'     	=> number_format((float)$weight, 3, '.', ''),
						  	 					'type' 				=> $scheme['type'],
						  	 					'adjust_by' 		=> $scheme['adjust_by']
						  	 			);
						  	  $setDet_status =	$this->$model->insert_settlement_detail($set_det);				
						  }
						   //update flag
					$upd_set = array('success' => 1); 
					$status=$this->$model->weight_settlementDB('update', $set_status['insertID'],$upd_set);
					}
				}
			}	
		}		
		echo "Metal weight updated successfully";	
	}
    function weight_settlement($type="",$id="")
    {
    	$model = self::PAY_MODEL;
		switch($type)
		{
			case 'View':
			    $data['set'] = $this->$model->weight_settlementDB();
			    $data['rate'] = $this->$model->monthly_rate_variation();/*Added by ARVK*/
			   	$data['main_content'] = self::SET_VIEW.'form';
                $this->load->view('layout/template', $data);
			break;
			case 'List':
			      $data['main_content'] = self::SET_VIEW.'list';
                  $this->load->view('layout/template', $data);
			    break;			
			default:
			      $data['set'] = $this->$model->weight_settlementDB('get');
			      echo json_encode($data);
			    break;
		}
	}
	function weight_settlement_detail($type="",$id="")
	{	
	    $model = self::PAY_MODEL;
		switch($type)
		{
			case 'List':
			      $data['main_content'] = self::SET_VIEW.'detail_list';
                  $this->load->view('layout/template', $data);
			    break;		
			case 'get':
			    $data['set'] = $this->$model->view_settlement_detail($id);
			      return $data;
			    break;
			 default:
			        $data['set'] = $this->$model->view_settlement_detail($id);
			      echo json_encode($data);
			   break;   
		}
	}
	function send_sms($mobile,$message,$dtl_te_id)
	{
		$model = self::ADM_MODEL;
		if($this->config->item('sms_gateway') == '1'){
		    $this->sms_model->sendSMS_MSG91($mobile,$message,'',$dtl_te_id);	
		}
		elseif($this->config->item('sms_gateway') == '2'){
	        $this->sms_model->sendSMS_Nettyfish($mobile,$message,'trans');	
		}
	}
	function sendSMSMail($serviceID,$data,$subject,$type,$id)
	{
		$ser_model = self::SET_MODEL;
	    $mail_model=self::MAIL_MODEL;
		$service = $this->$ser_model->get_service($serviceID);
		$email	=  $data['email'];
		$sms_model= self::SMS_MODEL;
		if($service['serv_email'] == 1  && $email!= '')
				{
					$data['payData'] = $data;
					$data['company_details'] = $this->company;
					$data['type'] = $type;
					$to = $email;
					$message = $this->load->view('include/emailPayment',$data,true);
					$sendEmail = $this->$mail_model->send_email($to,$subject,$message);
				}
		$data =$this->$sms_model->get_SMS_data($serviceID,$id);
		$mobile_number =$data['mobile'];
		$message = $data['message'];
		if($service['serv_sms'] == 1)
		{	
			$this->send_sms($mobile_number,$message,$service['dlt_te_id']);
		}
		if($service['serv_whatsapp'] == 1){
        	$this->admin_usersms_model->send_whatsApp_message($mobile_number,$message); 
        }
		return true;
	}
	function getMetalRateBydate()
	{
		$model = self::PAY_MODEL;
		$date_pay=$this->input->post('date_pay');
		$data = $this->$model->getMetalRateBydate($date_pay);
		echo json_encode($data);
	}
// offline datas transaction_details
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
				 }	 
			  }	
		}	
			return true;
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
            	$reg[0]['id_branch']= ( $reg[0]['id_branch'] == 0 ? NULL : $reg[0]['id_branch']);
            	$reg[0]['is_registered_online']= 2 ;  // 2 - online record
            	$reg[0]['ref_no']		= $ref_no;
            	$status = $this->$model->insert_CustomerReg($reg[0]);
             }	
		}
		$isTranExists = $this->$model->checkTransExists($ref_no);
		if(!$isTranExists)
		{
            //insert payment detail
            $pay_data[0]['record_to'] = 1;
            $pay_data[0]['id_branch']= ( $pay_data[0]['id_branch'] == 0 ? NULL : $pay_data[0]['id_branch']);
            $pay_data[0]['payment_type'] = 1;	// 1 - online
           // echo "<pre>";print_r($pay_data[0]);echo "<pre>";exit;
            $status =	$this->$model->insert_transaction($pay_data[0]);
           // echo $this->db->_error_message();
		}	
		return true;
	}
	public function insertTransInPayment(){
		//get offline payment records which are not updated in payment table
	   $model = self::CHITAPI_MODEL;
	    $getPayments  = $this->$model->getofflinePaymentsbyStatus('N');
		$total_rec =0;
		$add_rec =0;
	    $upd_rec =0;
	   foreach($getPayments as $payData)
		{			 
		   $id_sch_ac = $this->$model->getIdschemeAC($payData);   
		   if($id_sch_ac){
			if(!empty($payData['payment_mode'])){
				 $expArray = explode('-',$payData['payment_mode']);
				 $pay_mode = $expArray[0];
				 }
				 else{
				 	$pay_mode ='Offline';
				 }
			     $isCancelled = (substr($payData['payment_amount'],0,1)== '-' ? TRUE :FALSE);
			if(!$isCancelled){
				$pay_array = array ( "id_scheme_account" => $id_sch_ac,
			   						"date_payment" 		=> $payData['date_payment'],
			   					//	"id_metal" 			=> $payData['id_metal'],
			   						"metal_rate" 		=> $payData['metal_rate'],
			   						"payment_amount"	=> $payData['payment_amount'],
			   						"metal_weight" 		=> $payData['metal_weight'],
			   						"payment_mode" 		=> $pay_mode,
			   						"payment_status" 	=> 1,
			   						"payment_type" 		=> "Offline",
			   						"instalment" 		=> $payData['instalment'],
			   						"receipt_no" 		=> $payData['receipt_jil'],
			   						"remark" 			=> $payData['remark'],
			   						"discountAmt"		=> $payData['discountAmt'],
			   						"payment_ref_number"=> $payData['brefno'],
									"date_upd" 			=> date('Y-m-d H:i:s')
			    					);	
			    $insPayment  = $this->$model->insertPayment($pay_array);
			    if($insPayment){					
					$total_rec ++;
				    $add_rec ++;
				}
			   //   echo $insPayment;exit;
			}
			else{
				//update if offline record is with cancelled status
				$upd_array = array ( "payment_status" 	=> 2,
			   						"receipt_jil" 		=> $payData['receipt_jil'],
			   						"remark" 			=> $payData['remark'],
			   						"date_upd" 			=> date('Y-m-d H:i:s'),
			   						"payment_ref_number"=> $payData['brefno']
			    					);	
			    $updPayment  = $this->$model->updatePayment($upd_array);
				if($updPayment){
					$total_rec ++;
					$upd_rec ++;
				}
			}
		   }
	   }
	   if($total_rec >0){
	   	 $this->session->set_flashdata('chit_alert', array('message' => 'Total '.$total_rec.' records affected.Added  '.$add_rec.' payment records and updated '.$upd_rec.' payment records. ','class' => 'success','title'=>'Update Client Details'));	
	   }
	   else{
	  $this->session->set_flashdata('chit_alert', array('message' => 'No updates to proceed','class' => 'danger','title'=>'Update Client Details'));
	   }
	   redirect('payment/list');
	}
	function deleteCusandPaydata($id_payment){
		$model = self::API_MODEL;
	    $delete  = $this->$model->deletePayandCus($id_payment);
	    return $delete;
	}
	// offline datas transaction_details
	function manual_receiptnumber(){
		$model=self::PAY_MODEL;
		$payment = $this->input->post('selected');			
		$upd_rec =0;
		if(!empty($payment) && count($payment)>0 && $payment!=NULL)
		{
			$this->db->trans_begin();		
			foreach($payment as $data){
			$pay_account = array('receipt_no'=>$data['receipt_no']); 			
			$update=$this->$model->update_payment_status($data['id_payment'],$pay_account);
			if($update){				
				$this->db->trans_commit();  
				$upd_rec++;
			}else{				
				 $this->db->trans_rollback();
				}		
			}
			echo $upd_rec;
			if($upd_rec>0)
			{				
			$this->session->set_flashdata('chit_alert',array('message'=> $upd_rec.' Receipt number record updated as successfully...','class'=>'success','title'=>'Receipt number generated'));		
			}
		}else {
	   	  $this->session->set_flashdata('chit_alert',array('message'=> 'Unable to proceed the requested operation...','class'=>'danger','title'=>'Receipt number generate'));
	   }		
	}
// referral data insert wallet transaction//
   /* function insert_referral_data($id_scheme_account)
	{
	    $log_model    = self::LOG_MODEL;
		$model        = self::PAY_MODEL;
		$model_name   = self::WALL_MODEL;
		$sms_model    = self::SMS_MODEL; 
		$ser_model    = self::SET_MODEL;
		$status=FALSE;			
		$serviceID=16;
		$data = $this->$model->get_referrals_datas($id_scheme_account);	
		if(!empty($data))
		{			
			if($data['referal_code']!=''&& $data['referal_value']!='' && $data['id_customer']!=''&& ($data['cus_refferal']==1 || $data['emp_refferal']==1)){
			// insert wallet transaction data //
							$wallet_data = array(
							'id_wallet_account' => $data['id_wallet_account'],
							'date_transaction' =>  date("Y-m-d H:i:s"),
							'id_employee'      =>  $this->session->userdata('uid'),
							'transaction_type' =>  0,
							'value'            => $data['referal_value'],
							'description'      => 'Referral Benefits - '.$data['cusname'].''
							);
				$status =$this->$model_name->wallet_transactionDB('insert','',$wallet_data);
			     }			
				  if($status)
				  {
						$log_data = array(
										'id_log'     => $this->id_log,
										'event_date' => date("Y-m-d H:i:s"),
										'module'     => 'Wallet Transaction',
										'operation'  => 'Delete',
										'record'     => $status['insertID'],  
										'remark'     => 'Wallet Transaction Insert successfully'
									 );
					$this->$log_model->log_detail('insert','',$log_data);							  
				  }
				  $service = $this->$ser_model->get_service($serviceID);
				  if($service['serv_sms'] == 1 && $data['mobile']){
					   $sms_data =$this->$sms_model->get_SMS_data($serviceID,$data['id_scheme_account']);					
				       $this->send_sms($sms_data['mobile'],$sms_data['message']);
				  }
		}
	} */
	function insert_referral_data($id_scheme_account)
	{
	    $log_model    = self::LOG_MODEL;
		$model        = self::PAY_MODEL;
		$model_name   = self::WALL_MODEL;
		$sms_model    = self::SMS_MODEL; 
		$set_model    = self::SET_MODEL;		
		$cusmodel     =	self::CUS_MODEL;
		$status=FALSE;			
		$serviceID=16;
		$chkreferral=$this->$model->get_referral_code($id_scheme_account);
		if($chkreferral['referal_code']!='' && $chkreferral['is_refferal_by']==1){			
		  $data = $this->$model->get_empreferrals_datas($id_scheme_account);
		}else if($chkreferral['referal_code']!='' && $chkreferral['is_refferal_by']==0){			
			$data = $this->$model->get_cusreferrals_datas($id_scheme_account);
		}
		if(!empty($data))
		{			
			if($data['referal_code']!='' && $data['referal_value']!=''  &&  $data['id_wallet_account']!=''){
			// insert wallet transaction data //
							$wallet_data = array(
							'id_wallet_account' => $data['id_wallet_account'],
							'date_transaction' =>  date("Y-m-d H:i:s"),
							'id_employee'      =>  $this->session->userdata('uid'),
							'transaction_type' =>  0,
							'value'            => $data['referal_value'],
							'description'      => 'Referral Benefits - '.$data['cusname'].''
							);
						//	echo"<pre>"; print_r($wallet_data);exit;
				$status =$this->$model_name->wallet_transactionDB('insert','',$wallet_data);
				  if($status)
				  {
				  		// Update credit flag in customer table
					  	/* is_refbenefit_crt = 0 -> already  benefit credited  & 1-> yet to credit benefits' */					 		if($chkreferral['is_refferal_by']==0 && $data['cusbenefitscrt_type']==0 && ($data['schrefbenifit_secadd']==0 || $data['schrefbenifit_secadd']==1) ){
							// customer referal - single  
							$this->$cusmodel->update_customer_only(array('is_refbenefit_crt_cus'=>0),$chkreferral['id_customer']);
						}else if($chkreferral['is_refferal_by']==0 && $data['cusbenefitscrt_type']==1 && $data['schrefbenifit_secadd']==1){
							// customer referal - multiple  
							$this->$cusmodel->update_customer_only(array('is_refbenefit_crt_cus'=>1),$chkreferral['id_customer']);
						}else if($chkreferral['is_refferal_by']==1 && $data['empbenefitscrt_type']==0 && ($data['schrefbenifit_secadd']==0 || $data['schrefbenifit_secadd']==1)){	
							 // emp referal - single  					
							$this->$cusmodel->update_customer_only(array('is_refbenefit_crt_emp'=>0),$chkreferral['id_customer']);
						}else if($chkreferral['is_refferal_by']==1 && $data['empbenefitscrt_type']==1 && $data['schrefbenifit_secadd']==1){	
							// emp referal - single  			
							$this->$cusmodel->update_customer_only(array('is_refbenefit_crt_emp'=>1),$chkreferral['id_customer']);
						}
						$log_data = array(
										'id_log'     => $this->id_log,
										'event_date' => date("Y-m-d H:i:s"),
										'module'     => 'Wallet Transaction',
										'operation'  => 'Delete',
										'record'     => $status['insertID'],  
										'remark'     => 'Wallet Transaction Insert successfully'
									 );
					$this->$log_model->log_detail('insert','',$log_data);							  
				  }
				  $service = $this->$set_model->get_service($serviceID);
				  $sms_data =$this->$sms_model->get_SMS_data($serviceID,$data['id_scheme_account']);
				  if($service['serv_sms'] == 1 && $data['mobile']){
				       $this->send_sms($sms_data['mobile'],$sms_data['message']);
				  }
				  if($service['serv_whatsapp'] == 1 && $data['mobile']){
					   $this->admin_usersms_model->send_whatsApp_message($sms_data['mobile'],$sms_data['message']);
				  }
				 }
		}
	}
	function insertWalletTrans($tran){ 
	  if($tran)
	  {		 
		$redeemed_amount=$tran['redeemed_amount'];
		if($tran['wallet_balance_type']==1){				
			$redeemed_amount=(($redeemed_amount/$tran['wallet_amt_per_points'])*$tran['wallet_points']);
		}
	  	$transDetailData = array(	
		            				"amount" 		 => $tran['actual_trans_amt'], 
		            				"remark" 		 => 'Debited for saving scheme payment '.$tran['txnid'],
		            				'ref_no'         => $tran['txnid'],
		            				'trans_points' 	 => $redeemed_amount
		            			); 
	  	if($tran['walletIntegration'] == 0){ // 0 - No integration, 1 - Req integration as like SSS
		  	$transDetailData['trans_type'] = 2;
			$updwallet = $this->payment_model->updwallet($transDetailData,$tran['mobile']);
			$r = array("status" 	=> $updwallet);
			return $r;
		}else{
		   	   $cat_code = 'SS';
		   	   $wal_cat_settings = $this->payment_model->getWcategorySettings($cat_code);
		   	   // Begin the transaction
		   	   $this->db->trans_begin();
			   if($wal_cat_settings){
			   	   $newRecord = array(	
		                    		"bill_date" 	 => date('Y-m-d H:i:s'),
		                    		"bill_no" 	 	 =>  $tran['txnid'],
		                    		"bill_amount" 	 =>  $tran['actual_trans_amt'], 
		                    		"cat_amt" 	 	 =>  $tran['actual_trans_amt'], 
		                    		"cat_code" 	 	 =>  $cat_code, 	
		                    		"mobile" 	 	 =>  $tran['mobile'],
		                    		"id_wcat_settings"=> $wal_cat_settings['id_wcat_settings'],
		                    		"redeem_req_pts" =>  $redeemed_amount,
		                    		"debit_points" 	 =>  $redeemed_amount,
		                    		"cat_cr_pts" 	 =>  0,
		                    		"bill_avail_wal_pt"=>$tran['available_points'], 
		                    		"avail_wal_pt"	=>$tran['available_points'], 
		                    		"id_branch" 	 =>  $tran['branch'],
		                    		"date_add"       =>  date('Y-m-d H:i:s'), 
		                    		"record_type"	 =>  2,// 1 - offline , 2 - online
		                    	); 
			   	   $wallAccount = $this->payment_model->getInterWalletCustomer($tran['mobile']); 
			   	   $allow = FALSE;
			   	   if($wallAccount['status']){
				   		$upd_data = array(	
                    				"available_points"  => ($tran['available_points']-$redeemed_amount),
                    				"last_update"       => date('Y-m-d H:i:s'),
                    				"mobile" 	 	 	=> $tran['mobile'],
                    			);
				   		$w_status  = $this->payment_model->updInterWalletAcc($upd_data);
				   		if($w_status){
							$allow = TRUE;
						}
				   }
				   if($allow){
			           $t_status  = $this->payment_model->insertData($newRecord,'inter_wallet_bills');
				   		if($t_status['status']){				   			
						   		$transDetailData['trans_type'] = 2;  
						   		$updwallet = $this->payment_model->updwallet($transDetailData,$tran['mobile']);
						   		// Bill wise point debit :: FIFO based on expiry 
                			    $interData = array(
                			                    "mobile"        => $tran['mobile'],
                			                    "debit_points"  => $redeemed_amount,
                			                    "date_add"      => date("Y-m-d H:i:s")
                			        );
                			    $this->payment_model->insertData($interData,'inter_walsync_debit'); 
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
							    $isExist = $this->payment_model->getSyncWalletByMobile($tran['mobile']);
								if($isExist){
									$syncWalData['last_update'] = date('Y-m-d H:i:s');
									$syncWalData['type'] = 1; // Online Redeem
									$this->payment_model->updateSyncWal($syncWalData);
								}else{
									$syncWalData['date_add'] = date('Y-m-d H:i:s');
									$syncWalData['type'] = 1; // Online Redeem
									$this->payment_model->insertData($syncWalData,'inter_sync_wallet');
								}
								$verifcode = "";
								$msg = 'Thanks for shopping at '.$this->comp['company_name'].'.Your Wallet Balance '.number_format($tran['available_points'],'0','.','').' pts.Redeemed '.number_format($redeemed_amount,'2','.','').' pts.New Wallet Balance '.number_format(($tran['available_points']-$redeemed_amount),'0','.','').' pts.'.$verifcode; 
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
			  /* echo $this->db->_error_message();
			   echo $this->db->last_query();
			  exit;*/
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
	function send_sms_wallet($smsData){
		$set_model = self::SET_MODEL;
		$serviceID = 17;
	  	$service = $this->$set_model->get_service($serviceID);	
		$company = $this->$set_model->get_company();
	 	if($service['serv_sms'] == 1 )
		{	
			foreach($smsData as $data){
				//$data =$this->$sms_model->get_SMS_data($serviceID,$id);
				$mobile =$data['mobile'];
				$message = $data['message'];
				$model = self::ADM_MODEL;
				$this->$model->send_sms($mobile,$message);
			}
		}
		return TRUE;		
	}
    // To revert payment status
    function revertApproval_jil(){;
		$apimodel   = self::CHITAPI_MODEL;
		$log_model = self::LOG_MODEL;	
		$status = FALSE;
		$payData   = $this->input->post('payData');
		$reverted_ids = 0;
		if(!empty($payData) && count($payData)>0)
	    {   	
		   	foreach($payData as $pay)
			{
        		$revert_pay = $this->$apimodel->revertPayment($pay['id_payment']);
        		if($revert_pay){
        			// delete data in transaction and customer_reg tables
        			$status = $this->$apimodel->deletePayandCus($pay['id_payment']);
        			if($status){
        			    //array_push($reverted_ids,$pay['id_payment']);
        			    $reverted_ids +=1;
        			}
        		}
			}
	        if(($reverted_ids) > 0){
        		// update in log table
        		$log_data = array(
        							'id_employee'=> $this->session->userdata('uid'),
        							'event_date' => date("Y-m-d H:i:s"),
        							'module'     => 'Payment',
        							'operation'  => 'Revert',
        							'record'     => $reverted_ids,  
        							'remark'     => 'Payment reverted successfully'
        						 );	
        		$this->$log_model->log_detail('insert','',$log_data);
        		$this->session->set_flashdata('chit_alert',array('message'=> ($reverted_ids).' Payment record updated as successfully...','class'=>'success','title'=>'Revert Approval'));	
        	}else{
        	    $this->session->set_flashdata('chit_alert',array('message'=> 'No records to proceed the requested operation...','class'=>'danger','title'=>'Revert Approval'));
        	}
	    }else{
	      $this->session->set_flashdata('chit_alert',array('message'=> 'No records to proceed your request ....','class'=>'danger','title'=>'Revert Approval'));  
	    }
	}
	function revertApproval(){
		$model=self::CHITAPI_MODEL;		
		$log_model = self::LOG_MODEL;	
	    $payData   = $this->input->post('payData');
	  //  print_r($payData);exit;
	    $reverted_ids = 0;
	    if(!empty($payData) && count($payData)>0)
	    {   	
		   	foreach($payData as $pay)
			{
				// check whether customer registration exists for this payment_id (ref_no)
				$isCusRegExists = $this->$model->checkCusRegExists('',$pay['id_payment']);
				if($isCusRegExists['status'] == true){
					$tranCount = $this->$model->checkTransCount($isCusRegExists['id_scheme_account']);
					if($tranCount == 1){
						$this->db->trans_begin();
						$this->$model->revertPayment($pay['id_payment']);
						$delete  = $this->$model->deletePayandCus($pay['id_payment']);		
						$logData = array("delete_customer_reg" 	=> 1, // 1 - delete customer reg record 0 - no
										 "id_branch" 			=> ($pay['id_branch'] == 'null' ? NULL:$pay['id_branch']),
										 "ref_no" 				=> $pay['id_payment'],
										 "clientid" 			=> ($pay['clientid'] == 'null' ? NULL:$pay['clientid']),
										 'id_employee' 		=>  $this->session->userdata('uid'),
										);
						$log  = $this->$model->revert_approve_log($logData);				
						 if($this->db->trans_status()===TRUE)
			             {
						 	$this->db->trans_commit();
						 	$reverted_ids +=1;
						 	return TRUE;
						 }
						 else{
						    return FALSE;
						 }
					}
				}else{	
					$this->db->trans_begin();
					$this->$model->revertPayment($pay['id_payment']);
					/*$delete  = $this->$model->deleteTrans($pay['id_payment']);	
					$logData = array("delete_customer_reg" 	=> 0, // 1 - delete customer reg record 0 - no
									 "id_branch" 			=> ($pay['id_branch'] == 'null' ? NULL:$pay['id_branch']),
									 "ref_no" 				=> $pay['id_payment'],
									 "clientid" 			=> ($pay['clientid'] == 'null' ? NULL:$pay['clientid']),
									 'id_employee' 		=>  $this->session->userdata('uid'),
									);
					$log  = $this->$model->revert_approve_log($logData);*/							
					 if($this->db->trans_status()===TRUE)
		             {
					 	$this->db->trans_commit();
					 	$reverted_ids +=1;
					 	return TRUE;
					 }
					 else{
					    return FALSE;
					 }
				}
			}
			$this->session->set_flashdata('chit_alert',array('message'=> count($reverted_ids).' Payment record updated as successfully...','class'=>'success','title'=>'Revert Approval'));	
	    }
	    else
	    {
	   	  $this->session->set_flashdata('chit_alert',array('message'=> 'Unable to proceed the requested operation...','class'=>'danger','title'=>'Revert Approval'));
	    }
	}
	function ajax_load_account()
    {
        $id_payment =$_GET['id_payment'];
        $id_scheme_account =$_GET['id_sch_ac'];
        $model =	self::PAY_MODEL;
        $data['account']=$this->$model->get_load_account($id_payment,$id_scheme_account);
        echo json_encode($data);
    }
    function free_payment_data($sch_data,$sch_acc_id)
	{
		$metal_rate = $this->payment_model->getMetalRate();
		$gold_rate = number_format((float)$metal_rate['goldrate_22ct'], 2, '.', '');
		$gst_amt = 0;
		if($sch_data['gst'] > 0){
			if($sch_data['gst_type'] == 0){
				$gst_amt =$sch_data['amount']-($sch_data['amount']*(100/(100+$sch_data['gst'])));
				$converted_wgt = number_format((float)(($sch_data['amount']-$gst_amt)/$gold_rate), 3, '.', '');
			}
			else{
				$gst_amt = $sch_data['amount']*($sch_data['gst']/100); 
				$converted_wgt = number_format((float)($sch_data['amount']/$gold_rate), 3, '.', '');
			}
		}
		else{
			$converted_wgt = number_format((float)($sch_data['amount']/$gold_rate), 3, '.', '');
		}
		$fxd_wgt = $sch_data['max_weight'];
		$insertData = array(
								"id_scheme_account"	 => $sch_acc_id,
								"gst"	 			 => $sch_data['gst'],
								"gst_type"	 		 => $sch_data['gst_type'],
								"id_employee"	 	 => $this->session->userdata('uid'),
								"date_payment" 		 => date('Y-m-d H:i:s'),
								"payment_type" 	     => "Cost free payment", 
								"payment_mode" 	     => "FP", 
								"act_amount" 	     => $sch_data['amount'], 								
								"payment_amount" 	 => $sch_data['amount'], 
								"due_type" 	         => 'D', 
								"no_of_dues" 	     => '1', 								
								"metal_rate"         => $gold_rate,
								"metal_weight"       => ($sch_data['scheme_type']==2 ? $converted_wgt : ($sch_data['scheme_type']==1 ? $fxd_wgt : 0.000)),
								"remark"             => "Paid by ".$this->company['company_name'],
								"payment_status"     => '1'
							);
					return 	$insertData;	
	}
	function verify_payment()
	{ 
        $txns = $_POST;  
		if($txns['pg_code']==3){
			$this->verifyWithTechProcess($txns);
		}
		else if ($txns['pg_code'] == 2){
		    $this->verify_hdfcpayment($txns);
		}
		else if ($txns['pg_code'] == 1){
		    $this->verify_PayUpayments($txns);
		}
		else if ($txns['pg_code'] == 4){
		    $this->verify_cashfreepayment($txns);
		}
	}
	
	
		/*  CASHFREE - STATUS API
	*
	*   Document Links : 
	*       1. https://docs.cashfree.com/pg/restapi
	*       2. https://docs.cashfree.com/docs/rest/guide/#get-status
	*		3. https://docs.cashfree.com/docs/resources/#response-status
	*
	*   REQUEST PARAMETERS :
    *        Parameter	Required	Description
    *        appId	    Yes	        Your app id
    *        secretKey	Yes	        Your Secret Key
    *        orderId	Yes	        Order/Invoice Id*
    *
    *	RESPONSE PARAMETERS :
    *       Parameter	                Description
    *       status	                    Status of API call. Values are - OK and ERROR
    *       paymentLink	                link of payment page for that order. Returned when status is OK
    *       reason	                    reason of failure when status is ERROR
    *       txStatus	                transaction status, if a payment has been attempted
    *       txTime	                    transaction time, if payment has been attempted
    *       txMsg	                    transaction message, if payment has been attempted
    *       referenceId	                transaction reference id, if payment has been attempted
    *       paymentMode	                payment mode of transaction, if payment has been attempted
    *       orderCurrency	            currency of the order
    *       paymentDetails.paymentMode	payment mode of transaction, if payment has been attempted
    *       paymentDetails.bankName	    Name of the bank if payment has been attempted (only in case of Netbanking)
    *       paymentDetails.cardNumber	Masked card number if payment has been attempted (only in case of Debit & Credit Cards)
    *       paymentDetails.cardCountry	Country code of the card if payment has been attempted (only in case of Debit & Credit Cards)
    *       paymentDetails.cardScheme	Scheme of the card (eg: VISA) if payment has been attempted (only in case of Debit & Credit Cards)
    *   
    *   PAYMENT STATUS
    *       Case	                    event.name	        event.status
    *       Successful Payment	        PAYMENT_RESPONSE	SUCCESS
    *       Payment Failed	            PAYMENT_RESPONSE	FAILED
    *       Pending Payment	            PAYMENT_RESPONSE	PENDING
    *       Payment cancelled by user	PAYMENT_RESPONSE	CANCELLED
    *       Payment successful but kept 
            on hold by risk system	    PAYMENT_RESPONSE	FLAGGED
    *       Invalid inputs	            VALIDATION_ERROR	-
    */

	function verify_cashfreepayment($data)
	{
	    $model=	self::PAY_MODEL;
	    $set_model=	self::SET_MODEL;
	    $gateway_info = $this->$model->getBranchGatewayData($data['id_branch'],$data['pg_code']);
	    //print_r($gateway_info);exit;
	   
        $secretKey      = $gateway_info['param_1'];   
        $appId          = $gateway_info['param_3'];   
        //$transData      = $data['transData']; 
        $transData      = $data['txn_ids']; 
		$vCount = 0;
	
		if(sizeof($transData) > 0){
    		foreach($transData as $tran)
    		{
    		   
    		    //$postData = "appId=".$appId."&secretKey=".$secretKey."&orderId=".$tran['txn_id'];
    		    $postData = "appId=".$appId."&secretKey=".$secretKey."&orderId=".$tran;
    			
    			$curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $gateway_info['api_url'].'api/v1/order/info/status',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => $postData,
                    // Getting  server response parameters //
                    CURLOPT_HTTPHEADER => array(
                        "cache-control: no-cache",
                        "content-type: application/x-www-form-urlencoded"
                    ),
                ));
    
                $response = curl_exec($curl);
                 
                $err = curl_error($curl);
                curl_close($curl);
                if ($err) {
                    echo "cURL Error #:" . $err;
                } 
                else { 
                    $response = json_decode($response);
                    //echo "<pre>"; print_r($response);exit;
                    //var_dump ($response->status);
                    if($response->status == "OK"){ // OK - Api call success  - ERROR Failed
                        //echo "<pre>";print_r($response);exit;
                        $status_code = $response->txStatus ; // SUCCESS,
                        $txn_id      = $tran;
                        
                        if($txn_id != "" && $status_code != 'PENDING' && $status_code != 'FLAGGED' && $status_code != '')
                        {   
                            
                        	$updateData = array( 
                        		"payu_id"           => $response->referenceId, // referenceId
                        		"payment_ref_number"=> $response->referenceId, 
                        		"payment_mode"      => ($response->paymentMode == "CREDIT_CARD" ? "CC":($response->paymentMode == "DEBIT_CARD" ? "DC":($response->paymentMode == "NET_BANKING" ? "NB":$response->paymentMode))), 
                        		"remark"            => $response->txMsg,
                        	    "payment_status"    => ($status_code == 'SUCCESS' ? $this->payment_status['awaiting']:($status_code == 'CANCELLED'?$this->payment_status['cancel']:($status_code == 'FAILED'?$this->payment_status['failure']:($status_code == 'REFUND'?$this->payment_status['refund']:$this->payment_status['pending']))))
                        	); 	
                        	
                            $this->db->trans_begin();		
                		    $result =	$this->$model->updateGatewayResponse($updateData,$txn_id);
                		   
                		    /*
                		    //FOR WALLET CLIENTS LIKE SSS ONLY
                		    
                		    if($result['id_payment'] != ''  && $updateData['payment_status'] == 2){ 
                		      $payContent = $this->$model->getWalletPaymentContent($result['id_scheme_account']);
                			  if($result['redeemed_amount'] > 0){ 
                				    $transData = array('mobile' 			=> $payContent['mobile'],
                										 'actual_trans_amt' => $payContent['actual_trans_amt'],
                										 'available_points'	=> ($payContent['isAvail'] == 0 ?0:$payContent['available_points']),
                										 'isAvail'			=> ($payContent['isAvail'] == 0 ?0:1),
                										 'redeemed_amount'	=> $result['redeemed_amount'],
                										 //'is_point_credited'=> $pay['is_point_credited'], 
                										 'txnid'            => $payContent['ref_trans_id'].' - D',
                										 'branch'           => $payContent['branch'],
                				    					 'walletIntegration'=> $payContent['walletIntegration'],
                				    					 'wallet_balance_type' => $payContent['wallet_balance_type'],
                				    					 'wallet_points' => $payContent['wallet_points'],
                				    					 'wallet_amt_per_points' => $payContent['wallet_amt_per_points'],
                				    					 'wallet_balance_type'=>$pay['wallet_balance_type']
                										);  
                		    		if(!empty($transData)){
                		    		    $this->insertWalletTrans($transData); 
                		    		}
                		      } 
                		      //FOR WALLET CLIENTS LIKE SSS ONLY - ENDS
                		    }*/
                			if($this->db->trans_status() === TRUE)
                		  	{
                		  	    $vCount = $vCount + 1;
                		  	    $this->db->trans_commit();
                		  	    $pay_data =  $this->$model->getPpayment_data($result['id_payment']); 
                			    $mailSubject = "Reg- ".$this->company['company_name']." saving scheme payment details";
                		        $this->sendSMSMail('7',$pay_data,$mailSubject,'3',$result['id_payment']);
                			}else{
                			    $this->db->trans_rollback();
                			}				
                        }else{
                			//$response_msg[] = array ( 'msg' => $status_msg , 'Transaction ID' => $txn_id 	);        	
                		}
                    } 
                }
    		}
            if($vCount > 0){
                echo $vCount." payment records verified successfully."; 	
            }
            else
            {
                echo " No records to verify. Message ". print_r($response); 
            }
		}
		else
        {
            echo "Select Payments to verify."; 
        }
	}
	// .CashFree
	
	function verify_PayUpayments($txns){ 
        $model=	self::PAY_MODEL;
        $set_model=	self::SET_MODEL;
	    $txn_sequence='';
		if($txns)
		{  
		    $gateway_info = $this->$set_model->gateway_settingsDB('get_default');
		    $key = $gateway_info[0]['key'].'|verify_payment';
			foreach($txns['txn_ids'] as  $txn)
			{
				$txn_sequence = $txn_sequence.$txn_sequence.'|'.$txn;
			}		
			$hash_sequence = $key.$txn_sequence.'|'.$gateway_info[0]['salt'];	
			$hash_value =  strtolower( hash( 'sha512', $hash_sequence ) );
			$url = $gateway_info[0]['api_url'];
			$data = array(
			               'key'    =>$gateway_info[0]['key'],
			               'command' =>'verify_payment',
			               'hash'   =>$hash_value ,
			               'var1'   => ltrim($txn_sequence, '|'),
			               'salt'   => $gateway_info[0]['salt']
			              );
			$response = array();  
			$response =  $this->httpPost($url,$data); 
			if($response->status == 1)
			{  
				$vCount = 0;
				foreach($response->transaction_details as $key=>$trans)
				{  
				    $trans_id = $key;
					$record = array(
    								"bank_name"			 =>	(isset($trans->issuing_bank) ? $trans->issuing_bank : NULL),
    								"payment_mode"       => (isset($trans->mode) ? $trans->mode : NULL),
    								"card_no"			 => (isset($trans->mode) && ($trans->mode == 'CC' || $trans->mode == 'DC') ? $trans->card_no :NULL ),
    								"payu_id"             => (isset($trans->mihpayid) ? $trans->mihpayid : NULL),
    								"remark"             => (isset($trans->field9) ? $trans->field9 : NULL),
    								"payment_ref_number" => (isset($trans->bank_ref_num) ? $trans->bank_ref_num : NULL ),
    								"payment_status"     => (isset($trans->status) ? ($trans->status == 'success'? 2 : ($trans->status == 'pending'? 7 : ($trans->status == 'failure'? 3 : NULL  )  )  ): NULL ) 
    							    );
				    $this->db->trans_begin();		
				    $result =	$this->$model->updateGatewayResponse($record,$trans_id);
				    if($result['id_payment'] != '' && $record['payment_status'] == 2){ 
				      $payContent = $this->$model->getWalletPaymentContent($result['id_scheme_account']);
    				  if($result['redeemed_amount'] > 0){ 
    					    $transData = array('mobile' 			=> $payContent['mobile'],
        										 'actual_trans_amt' => $payContent['actual_trans_amt'],
        										 'available_points'	=> ($payContent['isAvail'] == 0 ?0:$payContent['available_points']),
        										 'isAvail'			=> ($payContent['isAvail'] == 0 ?0:1),
        										 'redeemed_amount'	=> $result['redeemed_amount'],
        										 //'is_point_credited'=> $pay['is_point_credited'], 
        										 'txnid'            => $payContent['ref_trans_id'].' - D',
        										 'branch'           => $payContent['branch'],
        				    					 'walletIntegration'=> $payContent['walletIntegration'],
        				    					 'wallet_balance_type' => $payContent['wallet_balance_type'],
        				    					 'wallet_points' => $payContent['wallet_points'],
        				    					 'wallet_amt_per_points' => $payContent['wallet_amt_per_points'],
        				    					 'wallet_balance_type'=>$pay['wallet_balance_type']
        										);  
        		    		if(!empty($transData)){
        		    		    $this->insertWalletTrans($transData); 
        		    		}
        		      } 
				    }
					if($this->db->trans_status() === TRUE)
				  	{
				  	    $this->db->trans_commit();
				  	    $pay_data =  $this->$model->getPpayment_data($result['id_payment']); 
        			    $mailSubject = "Reg- ".$this->company['company_name']." saving scheme payment details";
        		        $this->sendSMSMail('7',$pay_data,$mailSubject,'3',$result['id_payment']);
						$vCount = $vCount + 1;
					}else{
					    $this->db->trans_rollback();
					}	
				} 
				echo $vCount." payment records verified successfully..";
			}
			else 
			{
				echo "No records to verify";
			}
		}
	}
	function verify_hdfcpayment($txns_details)
	{
	    $model=	self::PAY_MODEL;
	    $set_model=	self::SET_MODEL;
	    $gateway_info = $this->$set_model->gateway_settingsDB('get_default'); 
        $working_key = $gateway_info[1]['key'];   //Shared by CCAVENUES
        $access_code = $gateway_info[1]['param_1'];   //Shared by CCAVENUES 
		$vCount = 0;
		foreach($txns_details as $txt)
		{
			$merchant_json_data =array(
						'order_no'     =>$txt['txn_ids'],
						'reference_no' => $txt['ref_no']	
				);	
			$merchant_data = json_encode($merchant_json_data);
			$encrypted_data = encrypt($merchant_data, $working_key);
			$postData = "request_type=JSON&access_code=".$access_code."&command=orderStatusTracker&response_type=string&enc_request=".$encrypted_data;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,"https://login.ccavenue.com/apis/servlet/DoWebTrans");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_VERBOSE, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$postData);
			// Get server response ...
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec ($ch);
			curl_close ($ch);
			$information=explode('&',$result);
			$dataSize=sizeof($information);  
    		$status =  (explode('=',$information[0]) ); 
			if($status[1] == 0){ // 0 - API Call success , 1 - API call Failed
				$info_value=explode('=',$information[1]);	
				if($info_value[0] == 'enc_response'){ 
					$res = decrypt($info_value[1], $working_key);
					$trans = explode('|',$res);	
					$trans_id = $trans[22];  
					if($trans_id != ''){ 
					    $record = array( 
                			"payu_id"            => (isset($trans[2]) ? $trans[2] : NULL),
                			"remark"             => (isset($trans[1]) ? $trans[1].''.(isset($trans[4]) ? '-'.$trans[4] : '' ) : NULL),
                			"payment_ref_number" => (isset($trans[3]) ? $trans[3] : NULL ),
                		);   
                		/* NOTES :
                		FORMAT : status|order_status|reference_no|order_bank_ref_no|order_bank_response|order_bill_name|order_bill_email|order_bill_address|order_bill_city|order_bill_state|order_bill_country|order_bill_telephone_no|order_bill_city_zip|order_card_name|order_currency|order_date_time|order_delivery_details|order_device_type|order_fraud_status|order_gateway_id|order_iP|order_no|order_notes|order_option_type|order_shiping_name|order_ship_email|order_ship_address|order_ship_city|order_ship_state|order_ship_country|order_ship_telephone_no|order_ship_zip|order_status_date_time|order_TDS|order_amount|order_capture_amount|order_discount|order_fee_flat|order_fee_perc|order_fee_perc_value|order_gross_amount|order_tax
                        SAMPLE : 0|Unsuccessful|108530298835|000000|NOT CAPTURED|Julia Martha|||||9710881620||Visa|INR|2019-03-05 08:17:51.027||MOB|NA||HDFC|157.51.238.218||SST15517540655c7de35188ff8||OPTCRDC||||||||Unsuccessful|2019-03-05 08:19:24.233|0.0|||1554.0|0.0|0.0|0.0|27.97|1554.0|0.0|0 */
                         /*HDFC Payment Status :-
    					Aborted (transaction is cancelled by the User)
    					Awaited (transaction is processed from billing shipping page but no response is received)
    					Cancelled (transaction is cancelled by merchant )
    					Chargeback
    					Auto-Cancelled(transaction has not confirmed within 12 days hence auto cancelled by system)
    					Auto-Reversed (two identical transactions for same order number, both were successful at bank's end but we got response for only one of them, then next day during reconciliation we mark one of the transaction as auto reversed )
    					Invalid(Transaction sent to HDFC with Invalid parameters, hence could not be processed further)
    					Fraud (we update this during recon,the amount is different at bank’s end and at HDFC due to tampering)
    					Initiated (transaction just arrived on billing shipping page and not processed further )
    					Refunded (Transaction is refunded.)
    					Shipped (transaction is confirmed)
    					Successful
    					Unsuccessful*/
                          if($trans[1] == 'Successful' || $trans[1] == 'Aborted' || $trans[1] == 'Awaited' || $trans[1] == 'Initiated' || $trans[1] == 'Shipped' || $trans[1] == 'Unsuccessful' || $trans[1] == 'Cancelled' || $trans[1] == 'Refunded'){		
                        	if($trans[1] == 'Aborted' || $trans[1] == 'Awaited' || $trans[1] == 'Initiated' || $trans[1] == 'Unsuccessful' || $trans[1] == 'Cancelled'){				
    							$record['payment_status'] = -1; // Failed
    						}else if($trans[1] == 'Successful' || $trans[1] == 'Shipped'){
    							$record['payment_status'] = 0; // pending
    						}else if($trans[1] == 'Refunded'){
    							$record['payment_status'] = 2; // Rejected
    						}
    					}  
                        $this->db->trans_begin();		
    				    $result =	$this->$model->updateGatewayResponse($record,$trans_id);
    				    if($result['id_payment'] != ''  && $record['payment_status'] == 2){ 
    				      $payContent = $this->$model->getWalletPaymentContent($result['id_scheme_account']);
        				  if($result['redeemed_amount'] > 0){ 
        					    $transData = array('mobile' 			=> $payContent['mobile'],
            										 'actual_trans_amt' => $payContent['actual_trans_amt'],
            										 'available_points'	=> ($payContent['isAvail'] == 0 ?0:$payContent['available_points']),
            										 'isAvail'			=> ($payContent['isAvail'] == 0 ?0:1),
            										 'redeemed_amount'	=> $result['redeemed_amount'],
            										 //'is_point_credited'=> $pay['is_point_credited'], 
            										 'txnid'            => $payContent['ref_trans_id'].' - D',
            										 'branch'           => $payContent['branch'],
            				    					 'walletIntegration'=> $payContent['walletIntegration'],
            				    					 'wallet_balance_type' => $payContent['wallet_balance_type'],
            				    					 'wallet_points' => $payContent['wallet_points'],
            				    					 'wallet_amt_per_points' => $payContent['wallet_amt_per_points'],
            				    					 'wallet_balance_type'=>$pay['wallet_balance_type']
            										);  
            		    		if(!empty($transData)){
            		    		    $this->insertWalletTrans($transData); 
            		    		}
            		      } 
    				    }
    					if($this->db->trans_status() === TRUE)
    				  	{
    				  	    $this->db->trans_commit();
    				  	    $pay_data =  $this->$model->getPpayment_data($result['id_payment']); 
            			    $mailSubject = "Reg- ".$this->company['company_name']." saving scheme payment details";
            		        $this->sendSMSMail('7',$pay_data,$mailSubject,'3',$result['id_payment']);
    						$vCount = $vCount + 1;
    					}else{
    					    $this->db->trans_rollback();
    					}
					}
				} 
			}else{
			    //print_r($information);
			}    
		}
		echo $vCount.' payments verified.';
	}
	function verifyWithTechProcess($data)
	{    
	    $model = self::PAY_MODEL;
	    $set_model = self::SET_MODEL;
		$transData = $data['transData'];		
        $response_msg =[];
	    foreach($transData as $trans) 
		{ 
			  $payData  = array( 
				'phone'      => $trans['mobile'],
              	'firstname'  =>	$trans['name'],
              	'txnid'      => $trans['txn_id'],
              	'amount'     => $trans['amount'],
              	'tpsl_txn_id'=> $trans['payu_id'],
              	'txnDate'	 => $trans['date_payment']
           	  );
       // $gateway_info = $this->$set_model->gateway_settingsDB('get_default');
		$gateway_info = $this->$model->getBranchGatewayData($data['id_branch'],$data['pg_code']);
			$iv=$gateway_info['param_4'];
			$key=$gateway_info['param_1'];
			$mrctCode=$gateway_info['param_3'];
			//print_r($mrctCode);exit;
	   /* $mrctCode = $gateway_info[2]['m_code'];  
        $key = $gateway_info[2]['key'];   
	    $iv = $gateway_info[2]['param_1']; */
	    $ClientMetaData = $payData['phone']; 
	    $reqType = 'O';
	    $currency = 'INR';
	    $returnURL =  site_url('paymt/techProcessResponseURL'); 
	    $ShoppingCartDetails = 'FIRST_'.$payData['amount'].'_0.0'; 
	    $locatorURL = "https://www.tpsl-india.in/PaymentGateway/TransactionDetailsNew.wsdl";  // LIVE 
	    $timeOut = '60000'; 
        $transactionRequestBean = new TransactionRequestBean();
        //Set all values here
        $transactionRequestBean->setMerchantCode($mrctCode);
        $transactionRequestBean->setITC($ClientMetaData);
        $transactionRequestBean->setRequestType($reqType);
        $transactionRequestBean->setCurrencyCode($currency);
        $transactionRequestBean->setReturnURL($returnURL);
        $transactionRequestBean->setShoppingCartDetails($ShoppingCartDetails); // conditional
        $transactionRequestBean->setTxnDate($payData['txnDate']); 
        $transactionRequestBean->setKey($key);
        $transactionRequestBean->setIv($iv);
        $transactionRequestBean->setWebServiceLocator($locatorURL);
        $transactionRequestBean->setTimeOut($timeOut); 
        $transactionRequestBean->setCustomerName($payData['firstname']);
        $transactionRequestBean->setMerchantTxnRefNumber($payData['txnid']);
        $transactionRequestBean->setAmount($payData['amount']); 
        if($reqType == "R"){
            $transactionRequestBean->setTPSLTxnID($payData['tpsl_txn_id']);
        }
        $responseDetails = $transactionRequestBean->getTransactionToken();
        $responseDetails = (array)$responseDetails;
        $response = $responseDetails[0]; 
       // print_r($response);exit;
		$payData = explode("|", $response); 
        $status_code = "";
        $status_msg = "";
        $err_msg = "";
        $txn_id = "";
        $payu_id = "";
        $vCount = 0;
        foreach($payData as $pay){ 
        	$r = explode("=", $pay); 
        	if($r[0] === "txn_status") $status_code = $r[1];
        	if($r[0] === "txn_msg") $status_msg = $r[1];
        	if($r[0] === "txn_err_msg") $err_msg = $r[1];
        	if($r[0] === "clnt_txn_ref") $txn_id = $r[1]; 
        	if($r[0] === "tpsl_txn_id") $payu_id = $r[1];
        }
        if($txn_id != "" && $status_code != 9999)
        {   
        	$updateData = array( 
        		"payu_id"           => (isset($payu_id) ? $payu_id : NULL), // tpsl_txn_id
        		"remark"            => $status_msg.' - '.$status_code.' - '.($err_msg != 'NA' ? $err_msg : ''),
        	    "payment_status"    => ($status_code == '0300' ? $this->payment_status['awaiting']:($status_code == '0392'?$this->payment_status['cancel']:($status_code == '0399'?$this->payment_status['failure']:($status_code == '0400'?$this->payment_status['refund']:$this->payment_status['failure']))))
        	); 	
            $this->db->trans_begin();		
		    $result =	$this->$model->updateGatewayResponse($updateData,$txn_id);
		    if($result['id_payment'] != ''  && $updateData['payment_status'] == 2){ 
		      $payContent = $this->$model->getWalletPaymentContent($result['id_scheme_account']);
			  if($result['redeemed_amount'] > 0){ 
				    $transData = array('mobile' 			=> $payContent['mobile'],
										 'actual_trans_amt' => $payContent['actual_trans_amt'],
										 'available_points'	=> ($payContent['isAvail'] == 0 ?0:$payContent['available_points']),
										 'isAvail'			=> ($payContent['isAvail'] == 0 ?0:1),
										 'redeemed_amount'	=> $result['redeemed_amount'],
										 //'is_point_credited'=> $pay['is_point_credited'], 
										 'txnid'            => $payContent['ref_trans_id'].' - D',
										 'branch'           => $payContent['branch'],
				    					 'walletIntegration'=> $payContent['walletIntegration'],
				    					 'wallet_balance_type' => $payContent['wallet_balance_type'],
				    					 'wallet_points' => $payContent['wallet_points'],
				    					 'wallet_amt_per_points' => $payContent['wallet_amt_per_points'],
				    					 'wallet_balance_type'=>$pay['wallet_balance_type']
										);  
		    		if(!empty($transData)){
		    		    $this->insertWalletTrans($transData); 
		    		}
		      } 
		    }
			if($this->db->trans_status() === TRUE)
		  	{
		  	    $this->db->trans_commit();
		  	    $pay_data =  $this->$model->getPpayment_data($result['id_payment']); 
			    $mailSubject = "Reg- ".$this->company['company_name']." saving scheme payment details";
		        $this->sendSMSMail('7',$pay_data,$mailSubject,'3',$result['id_payment']);
				$vCount = $vCount + 1;
			}else{
			    $this->db->trans_rollback();
			}				
        }else{
			$response_msg[] = array ( 'msg' => $status_msg , 'clnt_txn_ref' => $txn_id 	);        	
		}
      }
      if($vCount > 0){
      	echo $vCount." payment records verified successfully..."; 	
	  }
      else
	  {
		echo " No records to verify. Message ". print_r($response_msg);
	  }
    }
      function PaymentGateway()
	{
	  $model = self::PAY_MODEL;
	  $data=$this->$model->get_payment_gateway();
	  echo json_encode($data);
	}
	public function thermal_invoice($id,$type,$date="")
    {
   	  $model =	self::PAY_MODEL;	
   	  $set =	self::SET_MODEL; 
   	  $cus   =  self::CUS_MODEL;
   	  $account   =  self::ACC_MODEL;
	  $wallet   =  self::WALL_MODEL;
	  //echo $id;exit;
	  $data['records'] = $this->$model->get_invoiceData($id,"");
		if($type=='Payment'){ 
			  $data['records_sch'] = $this->$model->get_paymentContent($data['records'][0]['id_scheme_account']);
			  $data['gstSplitup'] = $this->$model->get_gstSplitupData($data['records'][0]['id_scheme'],$data['records'][0]['date_add']);
				 $paidinstll=$this->$model->get_paidinstallmentcount($data['records'][0]['id_scheme_account']);
			       $i=1;
					foreach($paidinstll as $x => $x_value) {											
						  if($x_value['id_payment']==$id){
							 $data['records'][0]['installment']=$i;				  
						   } $i++;
					}
					if($this->branch_settings==1){
						$data['comp_details']=$this->$set->get_branchcompany($data['records'][0]['id_branch']);
					}else{
						 $data['comp_details'] = $this->$set->get_company();
						}
			  $data['records'][0]['amount_in_words'] = $this->no_to_words($data['records'][0]['payment_amount']);
			  $html = $this->load->view('include/receipt_thermal', $data,true);
		}else if($type=='Customer'){				
		    $data['customer']= $this->$cus->get_cust($id); 		 
		 $html = $this->load->view('include/cusdetails_thermal', $data,true);
		}else if($type=='CloseAccount'){
			if($this->branch_settings==1){
					$data['comp_details']=$this->$set->get_branchcompany($data['records'][0]['id_branch']);
				}else{
					 $data['comp_details'] = $this->$set->get_company();
					}
		     $data['account']= $this->$account->get_closed_account_by_id($id);
		     $html = $this->load->view('include/schemeaccount', $data,true);
			 $this->load->helper(array('dompdf', 'file'));
			$dompdf = new DOMPDF();
			$dompdf->load_html($html); 
			$dompdf->set_paper("a4", "portriat" );
			$dompdf->render();
			$dompdf->stream("receipt1.pdf",array('Attachment'=>0));
			//die();
		}else if($type=='WalletTransaction'){
			if($this->branch_settings==1){
					$data['comp_details']=$this->$set->get_branchcompany($data['records'][0]['id_branch']);
				}else{
					 $data['comp_details'] = $this->$set->get_company();
					}
		     $data['wallet']= $this->$wallet->wallet_transactionDB('get',$id,'',$date,$date);
		     $html = $this->load->view('include/wallet_thermal', $data,true);			 
		}
		$this->load->helper(array('dompdf', 'file'));
        $dompdf = new DOMPDF();		
	    $dompdf->load_html($html);
		$customPaper = array(0,0,55,50);
		$dompdf->set_paper($customPaper, "portriat" );
		$dompdf->render();
		$dompdf->stream("receipt1.pdf",array('Attachment'=>0));  
   }
     function generateotp()
   {
        $model =	self::PAY_MODEL;
        $account   =  self::ACC_MODEL;
        $chit_model = self::CHIT_MODEL;
        $mail_model=self::MAIL_MODEL;
        $id_customer = $this->input->post('id_customer');
        $data= $this->$model->get_customer($id_customer);
		 $payOTP_exp= $this->$model->payOTP_exp();
        $mobile=$data['mobile'];
        $firstname=$data['firstname'];
        $OTP = mt_rand(100001,999999);  
         //$OTP = 111111;  
        $this->session->set_userdata('pay_OTP',$OTP);
        //$duration = $this->config->item('payOTP_exp'); // in seconds
        $duration = $payOTP_exp; // in seconds
        //$duration = 10; // in seconds
        $this->session->set_userdata('pay_OTP_expiry',time()+$duration);
        $message="Dear ".$firstname.", Your OTP for  Saving Scheme Payment is ".$OTP." Will expire within ". $duration." Sec. ";
		if($this->config->item('sms_gateway') == '1'){
			$this->sms_model->sendSMS_MSG91($mobile,$message);		
		}
		elseif($this->config->item('sms_gateway') == '2'){
			$this->sms_model->sendSMS_Nettyfish($mobile,$message,'trans');	
		} 
        $otp['otp_gen_time'] = date("Y-m-d H:i:s");
        $otp['otp_code'] = $OTP;
        if($data['email'] != ''){
			$edata['company_details'] = $this->company;
			$edata['type'] = 4;
			$edata['otp'] = $OTP;
			$edata['duration'] = $duration;
			$to = $data['email'];
			$subject = "Reg - OTP for ".$this->comp['company_name']. " saving scheme payment";
			$emessage = $this->load->view('include/emailAccount',$edata,true);
			$sendEmail = $this->$mail_model->send_email($to,$subject,$emessage);
		}
        $status=$this->$account->otp_insert($otp);
        $data=array('result'=>3 ,'msg'=>'"OTP Sent Successfully','otp'=>$OTP);
        echo json_encode($data);
   }
 function update_otp()
   {
    	$accountmodel=	self::ACC_MODEL;
    	$payment =	self::PAY_MODEL;
    	$otp  = $this->input->post('otp');
    	$data = $this->$accountmodel->select_otp($otp);	
    	if($otp==$this->session->userdata('pay_OTP'))
    	{
    		if(time() >= $this->session->userdata('pay_OTP_expiry'))
    	{
    		$this->session->unset_userdata('pay_OTP');
    		$this->session->unset_userdata('pay_OTP_expiry');
    		$data=array('result'=>5 ,'msg'=>'OTP has been expired');
    	}
    	else if($otp=$this->session->userdata('pay_OTP'))
    	{
    		$data['is_verified']	= '1';
    		$data['verified_time']= date("Y-m-d H:i:s");
    		$status=$this->$accountmodel->otp_update_payment($data,$data['id_otp']);
    		$data=array('result'=>1 ,'msg'=>'OTP updated successfully');
    	}
    	}
    	else
    	{
    		$data=array('result'=>6 ,'msg'=>'Invalid OTP');
    	}
    	echo json_encode($data);
    }
	function resend_otp()
   {
        $model =	self::PAY_MODEL;
        $account   =  self::ACC_MODEL;
        $chit_model = self::CHIT_MODEL;
        $mail_model=self::MAIL_MODEL;
        $id_customer = $this->input->post('id_customer');
        $data= $this->$model->get_customer($id_customer);
		 $payOTP_exp= $this->$model->payOTP_exp();
        $mobile=$data['mobile'];
        $firstname=$data['firstname'];
        $OTP = mt_rand(100001,999999);   
        $this->session->set_userdata('pay_OTP',$OTP);
        $duration = $payOTP_exp; // in seconds 
        $this->session->set_userdata('pay_OTP_expiry',time()+$duration);
        $message="Dear ".$firstname.", Your OTP for   Saving Scheme Payment is ".$OTP." Will expire within ". $duration." Sec. ";
		if($this->config->item('sms_gateway') == '1'){
			$sendSMS = $this->sms_model->sendSMS_MSG91($mobile,$message);		
		}
		elseif($this->config->item('sms_gateway') == '2'){
			$sendSMS = $this->sms_model->sendSMS_Nettyfish($mobile,$message,'trans');	
		} 
		if($data['email'] != ''){
			$edata['company_details'] = $this->company;
			$edata['type'] = 4;
			$edata['otp'] = $OTP;
			$edata['duration'] = $duration;
			$to = $data['email'];
			$subject = "Reg - OTP for ".$this->comp['company_name']. " saving scheme payment";
			$message = $this->load->view('include/emailAccount',$edata,true);
			$sendEmail = $this->$mail_model->send_email($to,$subject,$message); 
		}
        $otp['otp_gen_time'] = date("Y-m-d H:i:s");
        $otp['otp_code'] = $OTP;
        $status=$this->$account->otp_insert($otp);
        $data=array('result'=>3 ,'msg'=>'"OTP Sent Successfully','otp'=>$OTP);
        echo json_encode($data);
   }
// payment data uSING Trans Id//HH
	public function payments_data()
	{
		$model=	self::PAY_MODEL;
	    $data['main_content'] = self::PAY_VIEW.'paymentdata/pay_list';
	 	//print_r($data);exit;
        $this->load->view('layout/template', $data);
	}	
    function payments_data_list()
	{
		$model =	self::PAY_MODEL;				
		$ref_trans_id=$_POST['ref_trans_id'];
    	//$id_branch=$_POST['id_branch'];
    //	print_r($ref_trans_id);exit;
		$data = $this->$model->get_payments_data_list($ref_trans_id);
		echo json_encode($data);    
	}
	/* Settled Payment functions Begins */
	/**
	*  PAYU get_settlement_details :
	*  To retrieve Settlement Details for the merchant. The input is the date for which Settlement Details are required.
	*  HASH formula :  sha512(key|command|var1|salt)
	*  RESPONSE DATA :-
	*  Array
		(
			 [status] => 1
			 [msg] => 6565 transactions settled on 2015-08-01
			 [Txn_details] => Array
			 (
				 [0] => Array
				 (
					 [payuid] => 204131224
					 [txnid] => GOFLCF519911416076450
					 [txndate] => 2014-11-16 00:08:40
					 [mode] => DC
					 [amount] => 2580.00
					 [requestid] => 262698935
					 [requestdate] => 2015-08-01 17:43:25
					 [requestaction] => capture
					 [requestamount] => 186.00
					 [mer_utr] => CITIH15213701843
					 [mer_service_fee] => 0.00000
					 [mer_service_tax] => 0.00000
					 [mer_net_amount] => 186.00000
					 [bank_name] => VISA
					 [issuing_bank] => BOB
					 [merchant_subvention_amount] => 0.00
					 [cgst] => 0.00000
					 [igst] => 0.00000
					 [sgst] => 0.00000
				  )
			 )
		)
	* 
	* 
	*/
	function gtway_settlement($type)
	{
	    switch($type)
		{
			case 'sync_view':
        	    $data['main_content'] = self::PAY_VIEW.'sync_settled_txns';
                $this->load->view('layout/template', $data);
		}
	}	  
	function fetch_settled_payments()
	{ 
	  /*$file    = "../api/payusettled.txt";
	  $isUnsyncAvail = json_decode(file_get_contents($file));*/
	  $records = array();
	  $model = self::PAY_MODEL;
	  $date = date('Y-m-d',strtotime($_POST['request_date']));
	  if($_POST['id_gateway'] == 2){   
	  	$res = $this->hdfcSettlement($_POST['request_date']);
	  	echo $res;
	  }
	  else if($_POST['id_gateway'] == 4){ // CashFree 
	  	$response = $this->cashfreeSettlement($_POST);
	  	echo $response;
	  }
	  else{ 
    	 /* if(!$isUnsyncAvail){*/
    		  if($date){ 
    			   $key = $this->config->item('key').'|get_settlement_details';
    			   	/*hash formula :  sha512(key|command|var1|salt)*/	
    				$hash_sequence = $key.'|'.$date.'|'.$this->config->item('salt');	
    				$hash_value =  strtolower( hash( 'sha512', $hash_sequence ) );
    				$url = $this->config->item('verify_url');
    				$data = array(
    				               'key'    =>$this->config->item('key'),
    				               'command' =>'get_settlement_details',
    				               'hash'   =>$hash_value ,
    				               'var1'   => $date,
    				               'salt'   => $this->config->item('salt')
    				              );
    				$response = array();             
    				$response =  $this->httpPost($url,$data);
    				if($response->status == 1)
    				{
    					$vCount = 0;
    					/*foreach($response->Txn_details as $trans)
    					{ 
    					    $trans_id = $trans->txnid;
    					    $gateway_id = $trans->payuid;
    						$record = array("is_settled" => 1);
    					    $result =	$this->$model->updateSettledPayments($record,$trans_id,$gateway_id);		
    					  	if($result['status'] ==1)
    					  	{
    							$vCount = $vCount + 1;
    						}	
    					}*/
    				//print_r($response->Txn_details);exit;
    					foreach($response->Txn_details as $trans)
    					{ 
        						$records[] = array( "is_settled"  => 1,
        											'gateway_id'  => $trans->txnid,
        											'payuid'	  => $trans->payuid,
        											'amount'	  => $trans->amount,
        											'requestdate' => $trans->requestdate,
        											'requestaction'=> $trans->requestaction
        										  );	
        						$insData=array(
        										'txnid'		      => $trans->txnid,
        										'id_payGateway'	  => 1,
        										'gateway_id'	  => $trans->payuid,
        										'payment_date'	  => $trans->requestdate,
        										'mer_net_amount'  => $trans->mer_net_amount,
        										'mer_service_fee' => $trans->mer_service_fee,
        										'cgst' 			  => $trans->cgst,
        										'igst' 			  => $trans->igst,
        										'sgst' 			  => $trans->sgst,
        										'gateway_requestaction'=> $trans->requestaction
        										);
        				//	print_r($this->db->last_query());exit;
        						$this->$model->insertSettledPay($insData);
    					}
    					/*if($response->Txn_details){
    						$content = json_encode($response,TRUE);
    						file_put_contents($file,$content);	
    					}*/
    				}
    				echo json_encode(array('transactions'=>$records,'msg'=> $response->msg));
    			 // redirect('settled_payments/sync');
    			}
    		/*}else{
    			echo json_encode(array('transactions'=>$records,'msg'=>'Records pending to update !!'));
    		}*/
	    }
	}
	
		/**
	* 
	* CashFree - Settlement API
	* 
	* DOCUMENT LINKS :
	* 	1.https://docs.cashfree.com/docs/rest/guide/#fetch-all-settlements
	* 
	* REQUEST PARAMETERS
	*	Parameter	Required	Description
	*	appId		Yes			Your app id
	*	secretKey	Yes			Your Secret Key
	*	startDate	Yes			Date(in the format of YYYY-MM-DD), from which you want the data
	*	endDate		Yes			Date till you want the data (this date is included)
	*	lastId		No			Use it for paginated response. Settlements having id greater than this value will be returned
	*	count		No			Number of settlements you want to receive. Default is 20 and max is 50.
	* 
	* RESPONSE PARAMETERS
	*	Parameter	Description
	*	status		Status of API call. Values are - OK and ERROR
	*	settlements	List of settlements
	*	message		response message (will have the reason when status is sent as ERROR)
	*	lastId		ID of the last transaction returned. Use it in your next request if current one didn’t return all the transactions
	* 
	* SETTLEMENT ARRAY
	*	Parameter			Description
	*	id					Settlement Id (use it to fetch transactions that are part of this settlement)
	*	totalTxAmount		Total transactions amount
	*	settlementAmount	Amount after deducting the TDR
	*	adjustment			Any adjustments (because of refunds OR disputes)
	*	amountSettled		Amount settled after including the adjustments
	*	transactionFrom		transaction included from this day
	*	transactionTill		transactions included till this day
	*	utr	Bank 			Reference number
	*	settledOn			Time of settlement (this could be different than credit date shown on the account statement)
	*  
	*/
	function cashFreeCurl($postData,$api_url){
	   $curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $api_url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => $postData,
			CURLOPT_HTTPHEADER => array(
			"cache-control: no-cache",
			"content-type: application/x-www-form-urlencoded"
			),
		));
		$response = curl_exec($curl);
		$err = curl_error($curl); 
		curl_close($curl);
		$curl = curl_init();
		if ($err) {
		    echo "cURL Error #:" . $err;
			print_r($err);exit;
		}else{
		    //echo "<pre>";print_r($response);exit;  
		    return json_decode($response); 
		}
	}
	
    function cashfreeSettlement($req_data)
	{
	    $model=	self::PAY_MODEL; 
		$gateway_info = $this->$model->getBranchGatewayData($req_data['id_branch'],4); 
        $secretKey    = $gateway_info['param_1'];   
        $appId        = $gateway_info['param_3'];   
        if($req_data['request_date'] != $this->session->userdata('settlement_date') || $this->session->userdata('id_branch') != $req_data['id_branch']){
            $last_settlement_id = '';
            $last_trans_setl_id = '';
        }else{
            $last_settlement_id = (!$this->session->userdata('last_settlement_sync') ? '': $this->session->userdata('last_settlement_sync')); 
	        $last_trans_setl_id = (!$this->session->userdata('last_trans_setl_id') ? '': $this->session->userdata('last_trans_setl_id'));
        } 
        
        $vCount  = 0;
        $records = [];
        
        //echo "<pre>";print_r($this->session->all_userdata());exit;
        // FETCH SETTLEMENTS
		$postData = "appId=".$appId."&secretKey=".$secretKey."&startDate=".date('Y-m-d',strtotime( $req_data['request_date']))."&endDate=".date('Y-m-d',strtotime( $req_data['request_date']))."&lastId=".$last_settlement_id."&count=50";
	    $api = $gateway_info['api_url']."api/v1/settlements";
	    
	    /* echo $postData;
	    echo $this->db->last_query();
	    echo "<pre>";print_r($gateway_info);
        echo "<pre>";print_r($result);exit;  
		echo $result->message;*/
		
		$result = $this->cashFreeCurl($postData,$api);
		$api_msg = $result->message;
		if($result->status == "OK"){  
			$last_settlement_id = $result->lastId; 
			foreach($result->settlements as $settlement)
			{  
		        // FETCH SETTLEMENT TRANSACTION DETAILS
		        $trans_det_postData = "appId=".$appId."&secretKey=".$secretKey."&settlementId=".$settlement->id."&lastId=".$last_trans_setl_id."&count=50"; 
                $trans_det_api = $gateway_info['api_url']."api/v1/settlement";
		        $trans_detail = $this->cashFreeCurl($trans_det_postData,$trans_det_api);
		        
		        if($trans_detail->status == "OK" && $trans_detail->reason ==''){ 
        			$last_trans_setl_id = $trans_detail->lastId; 
        			foreach($trans_detail->transactions as $trans)
        			{
    					$records[] = array( "is_settled"  => 1,
    										'gateway_id'  => $trans->referenceId,
    										'txnid'	      => $trans->orderId,
    										'amount'	  => $trans->txAmount,
    										'requestdate' => $trans->txTime,
    										'requestaction'=> 'Settled'
    									  );	
    					$insData=array(
    									'txnid'		      => $trans->orderId,
    									'id_payGateway'	  => 1,
    									'gateway_id'	  => $trans->referenceId,
    									'payment_date'	  => $trans->txTime,
    									'mer_net_amount'  => $trans->mer_net_amount,
    									'mer_service_fee' => $trans->serviceCharge,
    									'cgst' 			  => $trans->cgst,
    									'igst' 			  => $trans->serviceTax,
    									'sgst' 			  => $trans->sgst,
    									'gateway_requestaction'=> 'Settled'
    									); 
    					$this->$model->insertSettledPay($insData);
        			}
		        }else{
		           	echo $trans_detail->reason;
		        } 
			}			
		}
		
		$last_settlement_sync  = array (
		                               'settlement_date'   => $req_data['request_date'],
    		                           'last_settlement_id'=> $last_settlement_id,
    		                           'last_trans_setl_id'=> $last_trans_setl_id,
    		                           'id_branch'         => $req_data['id_branch']
		                           );
		$this->session->set_userdata($last_settlement_sync); 
		if(sizeof($records) > 0){
		    $msg = sizeof($records)." transactions settled on ".$req_data['request_date'];
		}else{
		    $msg = "No pending transaction records.";
		}
		
		return json_encode(array('transactions'=>$records,'msg'=> $msg, 'api_msg' => $api_msg));
		
	}
	// .CashFree Settlement //
	
	function hdfcApiCurl($post_data){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,"https://login.ccavenue.com/apis/servlet/DoWebTrans");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
		// Get server response ...
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$res = curl_exec ($ch);
		curl_close ($ch);
		return $res;
	}
    function hdfcSettlement($settl_date)
	{
        $model=	self::PAY_MODEL;
		$gateway_info = $this->$set_model->gateway_settingsDB('get_default'); 
        $working_key = $gateway_info[1]['key'];   //Shared by CCAVENUES
        $access_code = $gateway_info[1]['param_1'];   //Shared by CCAVENUES 
        $vCount = 0;
        $records = [];
        $err_msg = "";
		$setl_date = array(
					'settlement_date'     => $settl_date
					);	
		$enc_data = json_encode($setl_date);
		$encrypted_data = encrypt($enc_data, $working_key);
		$postData = "request_type=JSON&access_code=".$access_code."&command=payoutSummary&response_type=JSON&enc_request=".$encrypted_data."&version=1.2";
		// echo "Post data : ".$postData."<br/>";
		$result = $this->hdfcApiCurl($postData);
		$information=explode('&',$result);
		$dataSize=sizeof($information);  
		$status =  (explode('=',$information[0]) ); 
		if($status[1] == 0){ // 0 - API Call success , 1 - API call Failed
			$info_value=explode('=',$information[1]);	
			if($info_value[0] == 'enc_response'){ 
				$res = decrypt(trim($info_value[1]), $working_key);
				$resData = json_decode($res);
				//echo "<b>Payout Summary : </b>\n<pre>";print_r($resData); 
				$summary = $resData->Payout_Summary_Result->payout_summary_list->payout_summary_details;
				$errdesc = $resData->Payout_Summary_Result->error_desc;
				if(!empty($errdesc)){
					$err_arr = explode(':',$errdesc);	
					$err_msg = $err_arr[1];	
				}
				$hdfc_pay_Id = $summary->pay_Id;
				if(!empty($hdfc_pay_Id)){
					$det_data = array(
						'settlement_date'     => $settl_date,
						'pay_id'     => $hdfc_pay_Id
					);	
					$det_enc_data = json_encode($det_data);
					$det_encrypted_data = encrypt($det_enc_data, $working_key);
					$postData = "request_type=JSON&access_code=".$access_code."&command=payIdDetails&response_type=JSON&enc_request=".$det_encrypted_data."&version=1.2";
					// echo "Post data : ".$postData."<br/>";
					$detRes = $this->hdfcApiCurl($postData);
					$details = explode('&',$detRes); 
					$resp =  (explode('=',$details[0]) ); 
					if($status[1] == 0){ // 0 - API Call success , 1 - API call Failed
						$value = explode('=',$details[1]);	
						if($value[0] == 'enc_response'){ 
							$decrypted = decrypt(trim($value[1]), $working_key);
							$detData = json_decode($decrypted);
							//echo "<b>Pay id Detail Summary : </b>\n<pre>";print_r($detData); 
							$txn_details = $detData->pay_id_details_Result->pay_id_txn_details_list->pay_id_txn_details;
							foreach($txn_details as $trans)
							{ 
	    						$records[] = array( "is_settled"  => 1,
	    											'txnid'	  	  => $trans->order_no,
	    											'gateway_id'  => $trans->ccavenue_ref_no,
	    											'amount'	  => $trans->amount,
	    											'requestdate' => $trans->date_time,
	    											'requestaction'=> $trans->txn_type
	    										  );	
	    						$insData=array(
	    										'txnid'		 	  => $trans->order_no,
	    										'id_payGateway'	  => 2,
	    										'gateway_id'	  => $trans->ccavenue_ref_no,
	    										'payment_date'	  => $trans->date_time,
	    										'mer_net_amount'  => $trans->amt_payable,
	    										'mer_service_fee' => $trans->fees,
	    										'cgst' 			  => 0.00,
	    										'igst' 			  => $trans->tax,
	    										'sgst' 			  => 0.00,
	    										'gateway_requestaction'=> $trans->txn_type
	    										);  
	    						$this->$model->insertSettledPay($insData);
							} 
						}
					}
				}
			} 
		}else{
		    echo "API Call Failed";
		} 
		return json_encode(array('transactions'=>$records,'msg'=> ($err_msg == "" ? sizeof($records)." transactions settled on ".$settl_date : $err_msg) ));
	}
	function updateGtwaySettlement()
	{ 
	  /*$file    = "../api/payusettled.txt";
	  $transactions = json_decode(file_get_contents($file),true);*/
	  $model = self::PAY_MODEL;
	  $transactions = $this->$model->settledTxnsToUpdate();
	  $vCount = 0;
	  $i=0;
	  $pending_avail = 0;
	  if($transactions){	
	  	    $this->db->trans_begin();  	
		    foreach($transactions as $key=>$trans)
			{ 
				if($i < 100){ 
				 	$trans_id = $trans['txnid'];
				    $gateway_id = $trans['gateway_id']; 
					$record = array(
									"is_settled" 	  => 1,
									'mer_net_amount'  => $trans['mer_net_amount'],
									'mer_service_fee' => $trans['mer_service_fee'],
									'cgst' 			  => $trans['cgst'],
									'igst' 			  => $trans['igst'],
									'sgst' 			  => $trans['sgst'],
									'gateway_requestaction'   => $trans['gateway_requestaction']
									);
				    $result =	$this->$model->updateSettledPayments($record,$trans_id,$gateway_id);		
				  	if($result)
				  	{
						$vCount = $vCount + 1;
						$this->$model->updatePayuSettledTrans(array('is_updated'=>1),$trans_id,$gateway_id);	
						/*unset($transactions['Txn_details'][$key]);*/
					} 
				}else{
					$pending_avail = 1;
				}		 
				$i++;	
			}
			if($this->db->trans_status() === TRUE){
				$this->db->trans_commit();		
			}else{
				$this->db->trans_rollback();		
			}
			/*if($transactions['Txn_details']){
				$content = json_encode($transactions,TRUE);
				file_put_contents($file,$content);	
				$pending_avail = 1;
			}else{
				file_put_contents($file,'');	
			}*/
		echo json_encode(array('msg'=> 'Updated transactions.','pending_avail'=>$pending_avail));
	 }else{
	 	echo json_encode(array('msg'=> 'No transactions to update','pending_avail'=>$pending_avail));
	 }		
	}
	/*End of Settled Payment functions */
     //offline date insert manual
	function insert_trans_record()
	{
		 $data['main_content'] = self::PAY_VIEW."insertTrans" ;
		 $this->load->view('layout/template', $data); 
	}
	public function instrans_post() 
{
	$model =	self::PAY_MODEL;
	$ins_tran=$this->input->post('instran');
	//echo"<pre>";print_r($_POST);	
	 $instran_info = array(
	 'client_id'			=>(isset($ins_tran['client_id'])?$ins_tran['client_id']:NULL),
	 'payment_date'			=>(isset($ins_tran['payment_date'])?$ins_tran['payment_date']:NULL),
	 'custom_entry_date'	=>(isset($ins_tran['payment_date'])?$ins_tran['payment_date']:NULL),
	 'amount'				=>(isset($ins_tran['amount'])?$ins_tran['amount']:NULL),
	 'weight'				=>(isset($ins_tran['weight'])?$ins_tran['weight']:NULL),
	 'rate'					=>(isset($ins_tran['rate'])?$ins_tran['rate']:NULL),
	 'metal'				=> 1,
	 'record_to'			=> 2,
	 'payment_type '		=> 2,
	 'payment_mode'			=>(isset($ins_tran['payment_mode'])?$ins_tran['payment_mode']:NULL),
	 'ref_no'				=>(isset($ins_tran['ref_no'])?$ins_tran['ref_no']:NULL),
	 'new_customer'			=>(isset($ins_tran['active'])?$ins_tran['active']:NULL),
	 'discountAmt'			=>(isset($ins_tran['discountAmt'])?$ins_tran['discountAmt']:0),
	 'id_branch'			=>(isset($ins_tran['id_branch'])?$ins_tran['id_branch']:NULL),
	 'payment_status'		=>(isset($ins_tran['payment_status'])?$ins_tran['payment_status']:NULL),
	 'receipt_no'			=>(isset($ins_tran['receipt_no'])?$ins_tran['receipt_no']:NULL),
	 'installment_no'		=>(isset($ins_tran['installment_no'])?$ins_tran['installment_no']:NULL),
	 'remarks'				=>(isset($ins_tran['remarks'])?$ins_tran['remarks']:NULL),
	 //'emp_code'			=>(isset($ins_tran['emp_code'])?$ins_tran['emp_code']:NULL)
	 'transfer_date'		=> date("Y-m-d H:i:s"),
	 'emp_code' 	        => $this->session->userdata('uid')
	 );
	 $this->db->trans_begin();
	 $res = $this->$model->instrans_rec($instran_info);
	  if( $this->db->trans_status()===TRUE)
	   {
		  $this->db->trans_commit();
		  $this->session->set_flashdata('chit_alert', array('message' => 'Payment inserted successfully','class' => 'success','title'=>'Offline payment Status'));
          redirect('payment/list');
	   }			  
	   else
	   {
	   	 $this->db->trans_rollback();
	   	 $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Offline payment Status'));
	   	  redirect('payment/list');
	   }
}
	//offline date insert manual









//AT Verify Payment
	function At_verify_payment()
	{ 
	    $model =	self::PAY_MODEL;
        $transData = $_POST['req_data'];  
        
		$gateway_info = $this->$model->getBranchGatewayData('',4);
        $secretKey      = $gateway_info['param_1'];   
        $appId          = $gateway_info['param_3'];   
		$vCount = 0;
		$return_data=array();
		if(sizeof($transData) > 0){
    		foreach($transData as $tran)
    		{
    		    //$postData = "appId=".$appId."&secretKey=".$secretKey."&orderId=".$tran['txn_id'];
    		    $postData = "appId=".$appId."&secretKey=".$secretKey."&orderId=".$tran['id_transaction'];
    			$curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $gateway_info['api_url'].'api/v1/order/info/status',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => $postData,
                    // Getting  server response parameters //
                    CURLOPT_HTTPHEADER => array(
                        "cache-control: no-cache",
                        "content-type: application/x-www-form-urlencoded"
                    ),
                ));
    
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);
                if ($err) {
                    echo "cURL Error #:" . $err;
                } 
                else { 
                    $response = json_decode($response);
                    
                    if($response->status == "OK"){ // OK - Api call success  - ERROR Failed
                       
                        $status_code = $response->txStatus ; // SUCCESS,
                        $txn_id      = $tran['id_transaction'];
                        
                        if($txn_id != "" && $status_code != 'PENDING' && $status_code != 'FLAGGED' && $status_code != '')
                        {   
                         
                        	$updateData = array( 
                        		"payu_id"           => $response->referenceId, // referenceId
                        		"payment_ref_number"=> $response->referenceId, 
                        		"payment_mode"      => ($response->paymentMode == "CREDIT_CARD" ? "CC":($response->paymentMode == "DEBIT_CARD" ? "DC":($response->paymentMode == "NET_BANKING" ? "NB":$response->paymentMode))), 
                        		"remark"            => $response->txMsg,
                        	    "payment_status"    => ($status_code == 'SUCCESS' ? $this->payment_status['success']:($status_code == 'CANCELLED'?$this->payment_status['cancel']:($status_code == 'FAILED'?$this->payment_status['failure']:($status_code == 'REFUND'?$this->payment_status['refund']:$this->payment_status['pending']))))
                        	); 	
                        	
                            $this->db->trans_begin();		
                		    //$result =	$this->$model->updateGatewayResponse($updateData,$txn_id);
                		    $result= $this->$model->updateAtData($updateData,'ref_trans_id',$txn_id,'purchase_payment');
                		    
                			if($this->db->affected_rows() == '1')
                		  	{
                		  	    //print_r($this->db->last_query());exit;
                		  	    $vCount = $vCount + 1;
                		  	    $this->db->trans_commit();
            
                			}else{
                			    $this->db->trans_rollback();
                			}			
                        }else{
                			//$response_msg[] = array ( 'msg' => $status_msg , 'Transaction ID' => $txn_id 	);        	
                		}
                    } 
                }
    		}
            if($vCount > 0){
               $return_data=array('status'=>true,'message'=>$vCount." payment records verified successfully.");
            }
            else
            {
                $return_data=array('status'=>false,'message'=>"No records to verify");
            }
		}
		else
        {
            $return_data=array('status'=>false,'message'=>"Select Payments to verify");
        }
        echo json_encode($return_data);
	}
//AT Verify Payment
	
	
	
}	
?>
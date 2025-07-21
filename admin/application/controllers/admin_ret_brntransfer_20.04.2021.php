<?php
if( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin_ret_brntransfer extends CI_Controller
{
	const IMG_PATH  = 'assets/img/';
	const SERV_MODEL = "admin_usersms_model";
	function __construct()
	{
	    
		parent::__construct();
		ini_set('date.timezone', 'Asia/Calcutta');
		$this->load->model('ret_brntransfer_model');
		$this->load->model('sms_model');
		$this->load->model('log_model');
		$this->load->model(self::SERV_MODEL);
		
		if(!$this->session->userdata('is_logged'))
		{
			redirect('admin/login');
		}	
		elseif($this->session->userdata('access_time_from') != NULL && $this->session->userdata('access_time_from') != "")
		{
			$now = time(); 
			$from = $this->session->userdata('access_time_from'); 
			$to = $this->session->userdata('access_time_to');  
			$allowedAccess = ($now > $from && $now < $to) ? TRUE : FALSE ;
			if($allowedAccess == FALSE){
				$this->session->set_flashdata('login_errMsg','Exceeded allowed access time!!');
				redirect('chit_admin/logout');	
			}			
		}
	}
		
	public function index()
	{	
		 
	}	
	
	public function branch_transfer($type="",$id="",$s_type="",$print_type=""){
		$model = "ret_brntransfer_model"; 
		switch($type)
		{
			case 'approval_list':
					$data['other_issue_branch'] = $this->$model->getSettigsByName('other_issue_branch');
					$data['main_content'] = "branch_transfer/approval_list" ;
					$this->load->view('layout/template', $data);
				break;
			case 'list':
					$data['main_content'] = "branch_transfer/list" ;
					$this->load->view('layout/template', $data);
				break;
			case 'add': 
					$data['other_issue_branch'] = $this->$model->getSettigsByName('other_issue_branch');
					$data['main_content'] = "branch_transfer/form" ;
					$this->load->view('layout/template', $data);
				break; 
			case "save":  
//					echo "<pre>";print_r($_POST);exit;
					$trans_code = $this->$model->trans_code_generator(); 
					$success = 0; 
					$failed = 0;
					$data = array( 
			 				'branch_trans_code'		=> $trans_code,
			 				'transfer_from_branch'	=> (isset($_POST['transfer_from']) ? $_POST['transfer_from'] : NULL),
			 				'transfer_to_branch'	=> (isset($_POST['transfer_to']) ? $_POST['transfer_to'] : NULL),
			 				'transfer_item_type'	=> (isset($_POST['item_tag_type']) ? $_POST['item_tag_type'] : NULL),
			 				'pieces'				=> (isset($_POST['pieces']) ? $_POST['pieces'] : 0),
			 				'grs_wt'				=> (isset($_POST['grs_wt']) ? $_POST['grs_wt'] : 0),
			 				'net_wt'				=> (isset($_POST['net_wt']) ? $_POST['net_wt'] : 0),
			 				'is_other_issue'		=> (isset($_POST['isOtherIssue']) ? $_POST['isOtherIssue'] : 0),
			 				'create_by'				=> $this->session->userdata('uid'),
			 				'created_time'			=> date('Y-m-d H:i:s'),
						  );
						  
					if($_POST['item_tag_type'] == 2){ // Non Tagged
						$this->db->trans_begin();
						foreach($_POST['trans_data'] as $nt_data){
							$data['id_lot_inward_detail']	= (isset($nt_data['id_lot_inward_detail']) ? ($nt_data['id_lot_inward_detail'] == '' ? NULL :$nt_data['id_lot_inward_detail']) : NULL);
							$data['id_nontag_item']	= (isset($nt_data['id_nontag_item']) && $nt_data['id_nontag_item'] != '' ? $nt_data['id_nontag_item'] : NULL);
			 				$data['pieces']	= (isset($nt_data['pieces']) ? $nt_data['pieces'] : 0);
			 				$data['grs_wt']	= (isset($nt_data['grs_wt']) ? $nt_data['grs_wt'] : 0);
			 				$data['net_wt']	= (isset($nt_data['net_wt']) ? $nt_data['net_wt'] : 0);
			 				
							$status = $this->$model->insertData($data,'ret_branch_transfer');
							if($status){
								$success = $success+1;
							}
						}
					}
					if($_POST['item_tag_type'] == 1){ // Tagged
						$this->db->trans_begin();
						$data['id_lot_inward_detail'] = (isset($_POST['id_lot_inward_detail']) ? $_POST['id_lot_inward_detail'] : NULL);
						$branch_transfer_id = $this->$model->insertData($data,'ret_branch_transfer');
						if($branch_transfer_id > 0 && isset($_POST['trans_data'])){
							if(sizeof($_POST['trans_data']) > 0){
								foreach($_POST['trans_data'] as $tag_data){
								    if($tag_data['tag_id'] > 0){
    									$items = array(
    												'transfer_id'	=> $branch_transfer_id,
    												'tag_id'		=> $tag_data['tag_id'], 
    												'id_lot_inward_detail'		=> $tag_data['id_lot_inward_detail'],
    												);
    									$status = $this->$model->insertData($items,'ret_brch_transfer_tag_items');
    									if($status){
    										$success = $success+1;
    									}
								    }else{
										$failed++;
									}
								}
							} 
						}
					}
								  
					if($this->db->trans_status()===TRUE)
					{
					    $log_data = array(
                        	'id_log'        => $this->session->userdata('id_log'),
                        	'event_date'	=> date("Y-m-d H:i:s"),
                        	'module'      	=> 'BT Entry',
                        	'operation'   	=> 'Add',
                        	'record'        => $trans_code,  
                        	'remark'       	=> "Trans code : ".$trans_code." ".$_POST['item_tag_type'] == 1 ? "Tags added to Branch Transfer" : "Non-Tag items added to Branch Transfer"
                        );
                        $this->log_model->log_detail('insert','',$log_data);
						$this->db->trans_commit();
						$this->session->set_flashdata('chit_alert',array('message'=> ($success >0?$success.' ':'').'Records added successfully. '.($failed >0?$failed.' records failed to add in Branch Transfer':''),'class'=>'success','title'=>'Add to Transfer')); 
						$result = array( "status" => 1, "trans_code" => $trans_code, "s_type" => $data['transfer_item_type']);
					}
					else
					{ 
						echo $this->db->last_query();
						echo $this->db->_error_message(); 
						
						$this->db->trans_rollback();			 	
						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Add to Transfer')); 
						$result['status'] = 0;
					} 
					echo json_encode($result);
					break; 	 	
			case 'updateStatus':  
					$success = 0;
					$dCData = $this->admin_settings_model->getBranchDayClosingData(($_POST['approval_type'] == 1 ? $_POST['trans_ids'][0]['from_branch'] : $_POST['trans_ids'][0]['to_branch']));
					$date = ($dCData['entry_date'] == date("Y-m-d") ? date("Y-m-d H:i:s") : $dCData['entry_date'].' '.date("H:i:s"));
					// approval_type - 1 => Transit Approval, 2 => Stock Download 
					$data = array( 
			 				'status'			=> ($_POST['approval_type'] == 2 ? 4 : 2),
			 				'approved_by'		=> $this->session->userdata('uid'), 
			 				'updated_time'		=> date('Y-m-d H:i:s'),
						  ); 
					if($_POST['approval_type'] == 1 ){
						$data['approved_datetime'] = $date;
					}else{
						$data['dwnload_datetime'] = $date;
					}
					$this->db->trans_begin();
					foreach($_POST['trans_ids'] as $trans){
						$status = $this->$model->updateData($data,'branch_transfer_id',$trans['trans_id'],'ret_branch_transfer'); 
						if($_POST['trans_type'] == 1){ // Tagged
							$btTags = $this->$model->getBTtags($trans['trans_id']);
							foreach($btTags as $tag){ 
								if($_POST['approval_type'] == 1){ // Transit Approval
									$tag_data = array(
										'current_branch'	=> $trans['to_branch'],
										'tag_status'		=> 4,
										'updated_time'		=> date('Y-m-d H:i:s'),
									);
								}
								else if($_POST['approval_type'] == 2){ // Stock Download 
								    $tag_data = array(
										'current_branch'	=> $trans['to_branch'],
										'updated_time'		=> date('Y-m-d H:i:s'),
									);
									if( $_POST['is_other_issue'] == 1){
										$tag_data['tag_status']	= 3; 
									}else{
										$tag_data['tag_status']	= 0;
									} 
								}
								$this->$model->updateData($tag_data,'tag_id',$tag['tag_id'],'ret_taging');
								$tag_log = array(
												"tag_id"		=> $tag['tag_id'],
												"status"		=> $tag_data['tag_status'],
												"from_branch"	=> ( $tag_data['tag_status'] == 3 ? NULL : $trans['from_branch'] ),
												"to_branch"		=> $trans['to_branch'],
												"created_by"	=> $this->session->userdata('uid'),
												"created_on"	=> date('Y-m-d H:i:s'),
												"date"			=> $date
												);
								$this->$model->insertData($tag_log,'ret_taging_status_log');
							} 
						}else{
						    if($_POST['approval_type'] == 1){ // Transit Approval
						        // Insert log record
    							$nontag_log = array(
												'product'		=> $trans['id_product'],
												'design'		=> $trans['id_design'],
												'no_of_piece'	=> $trans['no_of_piece'],
												'gross_wt'		=> $trans['gross_wt'],
												'net_wt'		=> $trans['net_wt'],
												"status"		=> 4,
												"from_branch"	=> $trans['from_branch'],
												"to_branch"		=> $trans['to_branch'],
												"created_by"	=> $this->session->userdata('uid'),
												"created_on"	=> date('Y-m-d H:i:s'),
												"date"			=> $date
												);
								$this->$model->insertData($nontag_log,'ret_nontag_item_log');
								//print_r($this->db->last_query());exit;
						    }
						    else if($_POST['approval_type'] == 2){ // Stock Download 
						        // Check if product and design exist in NT Table
    							$isExist = $this->$model->checkNonTagItemExist($trans);
    							if($isExist['status'] == TRUE){ // UPDATE 
    								$nt_data = array(
    												'gross_wt'		=> $trans['gross_wt'],
    												'net_wt'		=> $trans['net_wt'],   
    												'no_of_piece'	=> $trans['no_of_piece'],   
    												'updated_by'	=> $this->session->userdata('uid'),
    												'updated_on'	=> date('Y-m-d H:i:s'),
    											);
    								if($trans['id_nontag_item'] != ''){ // If BT is from ret_nontag_item Table
    									// Deduct in `FROM BRANCH` STOCK 
    									$nt_data['id_nontag_item'] = $trans['id_nontag_item'];
    									$this->$model->updateNTData($nt_data,'-');
    								} 
    								// Add in `To BRANCH` STOCK 
    								$nt_data['id_nontag_item'] = $isExist['id_nontag_item'];
    								$nt_status = $this->$model->updateNTData($nt_data,'+');
    							}else{ // INSERT
    								$nt_data = array(
    												'branch'		=> $trans['to_branch'],
    												'product'		=> $trans['id_product'],
    												'design'		=> $trans['id_design'],
    												'no_of_piece'	=> $trans['no_of_piece'],
    												'gross_wt'		=> $trans['gross_wt'],
    												'net_wt'		=> $trans['net_wt'],  
    												'created_by'	=> $this->session->userdata('uid'),
    												'created_on'	=> date('Y-m-d H:i:s'),
    											);
    								if($trans['id_nontag_item'] != ''){ // NOT Head Office
    									// Deduct in `FROM BRANCH` STOCK [Only if FROM BRANCH is not Head Office]
    									$nt_data['id_nontag_item'] = $trans['id_nontag_item'];
    									$nt_data['updated_by'] = $this->session->userdata('uid');
    									$nt_data['updated_on'] = date('Y-m-d H:i:s');
    									$this->$model->updateNTData($nt_data,'-');
    									unset($nt_data['id_nontag_item']);
    								} 
    								$nt_status = $this->$model->insertData($nt_data,'ret_nontag_item');
    							} 
						    
						        if( $_POST['is_other_issue'] == 1){
									$ntlog_status	= 3; 
								}else{
									$ntlog_status	= 0;
								} 
						        $nontag_log = array(
												'product'		=> $trans['id_product'],
												'design'		=> $trans['id_design'],
												'no_of_piece'	=> $trans['no_of_piece'],
												'gross_wt'		=> $trans['gross_wt'],
												'net_wt'		=> $trans['net_wt'],
												"status"		=> $ntlog_status,
												"from_branch"	=> $trans['from_branch'],
												"to_branch"		=> $trans['to_branch'],
												"created_by"	=> $this->session->userdata('uid'),
												"created_on"	=> date('Y-m-d H:i:s'),
												"date"			=> $date
												);
								$this->$model->insertData($nontag_log,'ret_nontag_item_log');
						    
						    } 
						}
						if($status){
							$success = $success+1;
						}
					}  
					
					if($this->db->trans_status()===TRUE)
					{
					    $log_data = array(
                        	'id_log'        => $this->session->userdata('id_log'),
                        	'event_date'	=> date("Y-m-d H:i:s"),
                        	'module'      	=> 'BT Approval',
                        	'operation'   	=> 'Add',
                        	'record'        => NULL,  
                        	'remark'       	=> $_POST['approval_type'] == 1 ? "Status updated as In-Transit" : "Stock downloaded Successfully"
                        );
                        $this->log_model->log_detail('insert','',$log_data);
						$this->db->trans_commit();
						$result = array('message'=> ($success >0?$success.' ':'').'Records updated successfully','class'=>'success','title'=>'Branch Transfer Approval') ; 
					}
					else
					{ 
						$this->db->trans_rollback();			 	
						$result = array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Branch Transfer Approval','q'=>$this->db->last_query(),'err'=>$this->db->_error_message()) ; 
					}
					echo json_encode($result);
				break;
			case 'getLotsByBranch':
					$data = $this->$model->getLotsByFilter($_POST);	 
					echo json_encode($data);
				break;
			case 'getDesignByFilter':
					$data = $this->$model->getDesignByFilter($_POST);	 
					echo json_encode($data);
				break;
			case 'getTagsByFilter': 				
					$data = $this->$model->fetchTagsByFilter($_POST);	 
					echo json_encode($data);
				break;
			case 'getEstiTagsByFilter': 				
					$data = $this->$model->fetchEstiTagsByFilter($_POST);	 
					echo json_encode($data);
				break;
			case 'getNonTaggedItem': 				
					$data = $this->$model->fetchNonTaggedItems($_POST);	 
					echo json_encode($data);
				break;
			case 'print': 	 
					$trans_code = $id;
					$this->load->model('admin_settings_model'); 
					$data['type']=$print_type;
					$data['btrans'] = $this->$model->getBTransData($trans_code,$s_type,$print_type);
					$data['comp_details'] = $this->admin_settings_model->get_company();
					/*if($this->session->userdata('branch_settings') == 1){
						 $data['comp_details'] = $this->admin_settings_model->get_branchcompany($data['records'][0]['id_branch']);
					}else{
						 $data['comp_details'] = $this->admin_settings_model->get_company();
					} */
					$data['comp_details'] = $this->admin_settings_model->get_company(); 
			   	    //create PDF receipt
					$this->load->helper(array('dompdf', 'file'));
			        $dompdf = new DOMPDF();
					$html = $this->load->view('branch_transfer/print', $data,true);
				    $dompdf->load_html($html); 
					$dompdf->set_paper("a4", "portriat" );
					$dompdf->render();
					$dompdf->stream("btrans.pdf",array('Attachment'=>0));	 
				break;
			case 'approval_pending': 	 
					$list = $this->$model->getApprovalListing($_POST);	 
				  	$access = $this->admin_settings_model->get_access('admin_ret_brntransfer/branch_transfer/list');
			        $data = array(
			        					'list' => $list,
										'access' => $access
			        			 );  
					echo json_encode($data);	 
				break;
			default: 
				  	$from_date=$this->input->post('from_date');
			        $to_date=$this->input->post('to_date');
				  	$list=$this->$model->get_ajaxBranchTransferlist($from_date,$to_date);
				  	$profile=$this->$model->get_profile_details($this->session->userdata('profile'));
				  	$data = array('list' => $list,'profile' => $profile);
				  	echo json_encode($data);
		    break;
		}
	}  	
	
	function verify_otp()
	{
		$model	=	"ret_tag_model"; 
		$post_otp	 = $this->input->post('otp');
		$session_otp = $this->session->userdata('bt_approval_otp'); 
		$this->db->trans_begin();
		if($session_otp == $post_otp)
		{
			if(time() >= $this->session->userdata('bt_approval_otp_exp'))
			{
				$this->session->unset_userdata('bt_approval_otp');
				$this->session->unset_userdata('bt_approval_otp_exp');
				$status = array('status' => false,'msg' => 'OTP has been expired'); 
			}
			else
			{
				$status = array('status' => true,'msg' => 'OTP Verified successfully.Proceed Approval.');
			} 
		}
		else
		{	
			$status	= array('status' => false,'msg'=>'Please Enter Valid OTP');
		} 
	  	echo json_encode($status); 
	}

	function send_otp()
	{  
		$branch		= ($this->session->userdata('id_branch') == '' || $this->session->userdata('id_branch') == 0 ? 1 : $this->session->userdata('id_branch'));  
		$mobile     = $this->ret_brntransfer_model->get_verifMobNo($branch); 
		$service = $this->admin_settings_model->get_service_by_code('bt_trans');
		$sent_otp	= '';
		if($mobile)
		{
			$this->db->trans_begin();
			$this->session->unset_userdata("bt_approval_otp");
			$this->session->unset_userdata("bt_approval_otp_exp");
			$OTP = mt_rand(100001,999999);
			$sent_otp.= $OTP;
			$this->session->set_userdata('bt_approval_otp',$sent_otp);
			$this->session->set_userdata('bt_approval_otp_exp',time()+60);
			$message="Hi, Your OTP For Branch Transfer Approval is :".$OTP.". Will expire within 1 minute.";
			$otp_gen_time = date("Y-m-d H:i:s");
			$insData = array(
						'mobile'		=>	$mobile,
						'otp_code'		=>	$OTP,
						'otp_gen_time'	=>	date("Y-m-d H:i:s"),
						'module'		=>	'Branch Transfer Approval',
						'send_resend'	=>	1,
						'id_emp'		=>	$this->session->userdata('uid')
						);
			$insId = $this->ret_brntransfer_model->insertData($insData,'otp');
			if($insId)
			{
				$send_sms = $this->send_sms($mobile,$message,$service['dlt_te_id']);
				
				$this->admin_usersms_model->send_whatsApp_message($mobile,$message);
				
				//$send_sms = TRUE;
				if($send_sms){
					$this->db->trans_commit();
	  				$status=array('status'=>true,'msg'=>'OTP sent Successfully','OTP'=>$OTP);	
  				}else{
					$this->db->trans_rollback();
  					$status=array('status'=>false,'msg'=>'Unabe To Send Try Again');	
				}
			}else{ 
				$status=array('status'=>false,'msg'=>'Unabe To Send Try Again');	
			}
		} 
	  	else
	  	{
  			$status=array('status'=>false,'msg'=>'Mobile number is empty');	
	  	} 
		echo json_encode($status);
	}
	
	function send_sms($mobile,$message,$dlt_te_id)
	{
		if($this->config->item('sms_gateway') == '1'){
		    $status = $this->sms_model->sendSMS_MSG91($mobile,$message,"",$dlt_te_id);		
		}
		elseif($this->config->item('sms_gateway') == '2'){
	        $status = $this->sms_model->sendSMS_Nettyfish($mobile,$message,'trans');	
		}
		return $status;
	}
	
	function bt_get_branches()
	{ 
		$branches = $this->ret_brntransfer_model->getBTBranches();  
		echo json_encode($branches);
	}
  
	/*function nonTagStock($type="",$id="",$s_type=""){ 
		$model = "ret_brntransfer_model"; 
		switch($type)
		{
			case 'updateStock':
					if($this->db->trans_status() === TRUE)
					{
						$this->db->trans_commit();
						$this->session->set_flashdata('chit_alert',array('message'=> ($success >0?$success.' ':'').'Records added successfully','class'=>'success','title'=>'Add to Transfer')); 
						$result = array( "status" => 1, "trans_code" => $trans_code, "s_type" => $data['transfer_item_type']);
					}
					else
					{  				
						$this->db->trans_rollback();			 	
						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Add to Transfer')); 
						$result['status'] = 0;
					} 
					echo json_encode($result);
				break; 
			case 'get':
					$list = $this->$model->getApprovalListing($_POST);	 
				  	$access = $this->admin_settings_model->get_access('admin_ret_brntransfer/branch_transfer/list');
			        $data = array(
			        					'list' => $list,
										'access' => $access
			        			 );  
					echo json_encode($data); 
				break;
			default:
					$data['main_content'] = "branch_transfer/non_tag/form";
					$this->load->view('layout/template', $data);
				break;
		}
	}*/ 
	
	
	function update_branch_transfer_cancel()
	{
		$model="ret_brntransfer_model";
		$reqdata   = $this->input->post('req_data');
		$this->db->trans_begin();
		foreach($reqdata as $trans)
		{
		    $data = array(
		               'status'  	  => 3,
					   'updated_time' => date("Y-m-d H:i:s"),
					   'updated_by'   => $this->session->userdata('uid')
					);
			$status = $this->$model->updateData($data,'branch_transfer_id',$trans['branch_transfer_id'],'ret_branch_transfer');
			if($status)
			{
			    $log_data = array(
                            'id_log'        => $this->session->userdata('id_log'),
                            'event_date'    =>  date("Y-m-d H:i:s"),
                            'module'        => 'Branch Trasnfer',
                            'operation'     => 'Cancel',
                            'record'        =>  $trans['branch_transfer_id'],  
                            'remark'        => 'Reject Branch Trasnfer'
                            );
                $this->log_model->log_detail('insert','',$log_data);
			}
		}
		
		if($this->db->trans_status()===TRUE)
         {
		 	$this->db->trans_commit();
			$this->session->set_flashdata('chit_alert',array('message'=>'Branch Transfer Rejected successfully.','class'=>'success','title'=>'Branch Trasnfer'));			
		}	
		else
		{
			$this->db->trans_rollback();
			$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Branch Trasnfer'));
		}	
	}
	
}	
?>
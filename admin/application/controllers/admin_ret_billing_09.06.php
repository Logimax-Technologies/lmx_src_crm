<?php
if( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin_ret_billing extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		ini_set('date.timezone', 'Asia/Calcutta');
		$this->load->model('ret_billing_model');
		$this->load->model('admin_settings_model');
		$this->load->model("sms_model");
		if(!$this->session->userdata('is_logged'))
		{
			redirect('admin/login');
		}	
	}
		
	public function index()
	{	
		 
	}	
	
	function set_image($id,$img_path,$file)
	{
	 	if($_FILES[$file]['name'])
	   	 {   
	        $img = $_FILES[$file]['tmp_name'];
			$status = $this->upload_img($file,$img_path,$img);
			return $status;
		 } 
	}
	 	
    function upload_img( $outputImage,$dst, $img)
	{	
		if (($img_info = getimagesize($img)) === FALSE)
		{
			// die("Image not found or not an image");
			return false;
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
		  return false;
		}		
		 
		imagecopyresampled($tmp, $src, 0, 0, 0, 0, $width, $height, $width, $height);
		$res = imagejpeg($tmp, $dst); 
		return $res;
	}

	function rrmdir($path) {
     // Open the source directory to read in files
        $i = new DirectoryIterator($path);
        foreach($i as $f) {
            if($f->isFile()) {
                unlink($f->getRealPath());
            } else if(!$f->isDot() && $f->isDir()) {
                rrmdir($f->getRealPath());
            }
        }
        rmdir($path);
	}
	
	function remove_img($file,$id) {
		$path = self::PROD_PATH.$id."/".$file ;
		chmod(self::PROD_PATH.$id,0777);
		unlink($path);
		$model=self::CAT_MODEL;
		$status = $this->$model->delete_prodimage($file);
		if($status){
			echo "Picture removed successfully";
		}	       
	}
	
	/**
	* Billing Functions Starts
	*/
	
	public function billing($type="", $id=""){
		$model = "ret_billing_model";
		$data['type']	= $type;
		switch($type)
		{
			case 'add':
					$data['billing']		= $this->$model->get_empty_record();
					$data['bill_other_item'] = array("item_details" => array(), "old_matel_details" => array(), "stone_details" => array(), "other_material_details" => array(), "voucher_details" => array(), "chit_details" => array(), "advance_details" => array());
					$data['uom']		= $this->$model->getUOMDetails();
					$data['main_content'] = "billing/form" ;
					$this->load->view('layout/template', $data);
				break;
			case 'list':
					$data['main_content'] = "billing/list" ;
					$this->load->view('layout/template', $data);
				break;
			case "save": 
					// 1-Sales, 2-Sales&Purchase, 3-Sales,purchase&Return, 4-Purchase, 5-Order Advance, 6-Advance,7-Sales Return
					$addData = $_POST['billing'];
					$chit_details		= json_decode($addData['chit_uti'],true);
					$voucher_details	= json_decode($addData['vocuher'],true);
					$card_pay_details	= json_decode($addData['card_pay'],true);
					$adv_adj_details	= json_decode($addData['adv_adj'],true); 
					$cheque_details	    = json_decode($addData['chq_pay'],true); 
					$net_banking_details = json_decode($addData['net_bank_pay'],true); 
					//echo "<pre>";print_r($addData);exit;
					
					$addData['bill_date'] = date('Y-m-d',strtotime(str_replace("/","-",$addData['bill_date'])));
					$bill_no = $this->$model->code_number_generator();
					$ref_bill_id = ( $addData['bill_type'] == 3 || $addData['bill_type'] ==7 || $addData['bill_type'] ==8 ?(!empty($addData['ret_bill_id']) ? $addData['ret_bill_id'] : NULL ): NULL );
					
					$data = array(						
						'bill_no'		    => $bill_no,
						'ref_bill_id'	    => $ref_bill_id,
						'bill_type'		    => $addData['bill_type'],
						'pan_no'		    => (!empty($addData['pan_no']) ? $addData['pan_no'] : NULL ),
						'bill_cus_id'   	=> (!empty($addData['bill_cus_id']) ? $addData['bill_cus_id'] :NULL ),
						'tot_discount'	    => (!empty($addData['discount']) ? $addData['discount'] : 0 ),
						'tot_bill_amount'	=> (!empty($addData['total_cost']) ? $addData['total_cost'] : 0 ),
						'tot_amt_received'	=> (!empty($addData['tot_amt_received']) ? $addData['tot_amt_received'] : 0),
						'is_credit'			=> (!empty($addData['is_credit']) ? $addData['is_credit'] : 0 ),
						'credit_status'		=> (!empty($addData['is_credit'] && $addData['is_credit']==1) ? 2: 1 ),
						'credit_due_date'	=> (!empty($addData['credit_due_date']) ? ($addData['is_credit'] == 1 ? $addData['credit_due_date']: NULL ) : NULL ),
						'bill_date'	        => date("Y-m-d H:i:s"),
						'created_time'	    => date("Y-m-d H:i:s"),
						'created_by'        => $this->session->userdata('uid'),
						'id_branch'         => $addData['id_branch']
					); 
	 				$this->db->trans_begin();
	 				$insId = $this->$model->insertData($data,'ret_billing');

	 				
	 				if($insId)
	 				{
	 				    if($addData['bill_type'] == 8)
	 				    {
	 				        $credit_pay_amount=$this->$model->get_credit_pay_amount($ref_bill_id);
	 				        $bill_details=$this->$model->get_BillAmount($ref_bill_id);
	 				        if(($bill_details['tot_amt_received']+$credit_pay_amount)==$bill_details['tot_bill_amount'])
	 				        {
	 				            $updCredit = array("credit_status" 	=> 1);
							    $this->$model->updateData($updCredit,'bill_id',$ref_bill_id,'ret_billing');
	 				        }
	 				    }
	 					// Return Bill
	 					if($addData['bill_type'] == 3 || $addData['bill_type']== 7) 
 						{ 							
 							echo "<pre>"; print_r($_POST['sales_return']);exit;
 							foreach($_POST['sales_return'] as $return_detail){
 								// Update Bill Return status
						 		$this->$model->updateData(array( "return_status" => 1),'bill_id',$ref_bill_id, 'ret_billing');
						 		// Update Bill Details Return status
						 		$updBillDetail = array( 
											 		"status" 				=> 2,
											 		"sales_return_discount" =>($return_detail['sale_ret_disc_amt']!='' ? $return_detail['sale_ret_disc_amt']:NULL),
											 		"return_item_cost" 		=> $return_detail['sale_ret_amt']
											 	 );
						 		$this->$model->updateData($updBillDetail,'bill_det_id',$return_detail['bill_det_id'], 'ret_bill_details');
						 		//print_r($this->db->last_query());exit;
 								// Reverse Esti Status for Returned item Bill
	 							$updEstiRet = array( 
	 											"purchase_status" 	=> 2, // 1-Purchased,2-Returned
							 					"bil_detail_id" 	=> $ref_bill_id
							 				  );
						 		$this->$model->updateData($updEstiRet,'est_item_id',$return_detail['est_itm_id'], 'ret_estimation_items');
						 		// Reverse Tag Status for Returned item Bill 
						 		$this->$model->updateData(array( "tag_status" => 0),'tag_id',$return_detail['tag'], 'ret_taging');
						 	}	 					 
		 				}
		 				
	 					//Amount Advance
	 					if($addData['bill_type']==6 || $addData['bill_type']==5) 
	 					{
								$arrayAdv = array(
									'bill_id'			=>$insId,
									'advance_type'		=> ($addData['bill_type']==6 ? 3:($addData['bill_type']==5 ? 2:1)),//Amount Advance
									'advance_amount'	=> (!empty($addData['tot_amt_received']) ? $addData['tot_amt_received'] : 0),
									'order_no'			=> (!empty($addData['filter_order_no']) ? $addData['filter_order_no'] :NULL),
									'advance_date'		=> (!empty($addData['bill_date']) ? $addData['bill_date'] : NULL ),
									'created_time'		=> date("Y-m-d H:i:s"),
									'created_by'    	=> $this->session->userdata('uid')
									);
							$advInsId = $this->$model->insertData($arrayAdv,'ret_billing_advance');
	 					}
	 					
	 					//Cash Payment
 						if($addData['cash_payment']>0)
	 					{
	 						$arrayCashPay=array(
	 									'bill_id'=>$insId,
	 									'payment_amount'=>$addData['cash_payment'],
	 									'payment_mode'=>'Cash',
	 									'payment_for'		=>($addData['bill_type']==6 ?2:1),
	 									'payment_status'=>1,
	 									'payment_date'		=>(!empty($addData['bill_date']) ? $addData['bill_date'] : NULL ),
	 									'created_time'	=> date("Y-m-d H:i:s"),
										'created_by'	=> $this->session->userdata('uid')
	 								);
								if(!empty($arrayCashPay)){
								$cashPayInsert = $this->$model->insertData($arrayCashPay,'ret_billing_payment'); 
								}
	 					}
	 					//chit Payment
	 					if(sizeof($chit_details)>0)
	 					{
	 						foreach($chit_details as $chit_uti)
	 						{
	 							$arrayChit[]=array('bill_id'=>$insId,'scheme_account_id'=>$chit_uti['scheme_account_id']
	 											);
	 						}
	 						if(!empty($arrayChit))
	 						{
								$chitInsert = $this->$model->insertBatchData($arrayChit,'ret_billing_chit_utilization'); 
								if($chitInsert)
								{
								    foreach($chit_details as $chit_uti)
								    {
								        $updData=array('is_utilized'=>1);
								        $updID= $this->$model->updateData($updData,'id_scheme_account',$chit_uti['scheme_account_id'],'scheme_account');
								    }
								}
							}
	 					}
	 					if(sizeof($voucher_details)>0)
	 					{
	 						foreach($voucher_details as $voucher)
	 						{
	 							$arrayVoucher[]=array('voucher_no'=>$vocuher['voucher_no'],'bill_id'=>$insId,'gift_voucher_amt'=>$voucher['gift_voucher_amt']);
	 						}
	 						if(!empty($arrayChit)){
								$voucerPayInsert = $this->$model->insertBatchData($arrayVoucher,' ret_billing_gift_voucher_details'); 
							}
	 					}
	 					if(sizeof($card_pay_details)>0)
	 					{
	 						foreach($card_pay_details as $card_pay)
		 					{
								$arrayCardPay[]=array(
									'bill_id'		=>$insId,
									'payment_amount'=>$card_pay['card_amt'],
									'payment_for'	=>($addData['bill_type']==6 ?2:1),
									'payment_status'=>1,
									'payment_date'	=>(!empty($addData['bill_date']) ? $addData['bill_date'] : NULL ),
									'payment_mode'	=>($card_pay['card_type']==1 ?'CC':'DC'),
									'card_no'		=>($card_pay['card_no']!='' ? $card_pay['card_no']:NULL),
									'created_time'	=> date("Y-m-d H:i:s"),
									'created_by'	=> $this->session->userdata('uid')
								);
	 						}
		 						if(!empty($arrayCardPay)){
									$cardPayInsert = $this->$model->insertBatchData($arrayCardPay,'ret_billing_payment'); 
								}
	 					}
	 					if(sizeof($cheque_details)>0)
	 					{
	 						foreach($cheque_details as $chq_pay)
		 					{
								$arraychqPay[]=array(
									'bill_id'		=>$insId,
									'payment_amount'=>$chq_pay['payment_amount'],
									'payment_for'	=>($addData['bill_type']==6 ?2:1),
									'payment_status'=>1,
									'cheque_date'	=>(!empty($addData['bill_date']) ? $addData['bill_date'] : NULL ),
									'payment_mode'	=>'CHQ',
									'cheque_no'		=>($chq_pay['cheque_no']!='' ? $chq_pay['cheque_no']:NULL),
									'bank_name'		=>($chq_pay['bank_name']!='' ? $chq_pay['bank_name']:NULL),
									'bank_branch'	=>($chq_pay['bank_branch']!='' ? $chq_pay['bank_branch']:NULL),
									'created_time'	=> date("Y-m-d H:i:s"),
									'created_by'	=> $this->session->userdata('uid')
								);
	 						}
		 						if(!empty($arraychqPay)){
									$chqPayInsert = $this->$model->insertBatchData($arraychqPay,'ret_billing_payment'); 
								}
	 					}
	 					if(sizeof($net_banking_details)>0)
	 					{
	 						foreach($net_banking_details as $nb_pay)
		 					{
								$arrayNBPay[]=array(
									'bill_id'		=>$insId,
									'payment_amount'=>$nb_pay['amount'],
									'payment_for'	=>($addData['bill_type']==6 ?2:1),
									'payment_status'=>1,
									'payment_mode'	=>'NB',
									'payment_ref_number'=>($nb_pay['ref_no']!='' ? $nb_pay['ref_no']:NULL),
									'NB_type'       =>($nb_pay['nb_type']!='' ? $nb_pay['nb_type']:NULL),
									'created_time'	=> date("Y-m-d H:i:s"),
									'created_by'	=> $this->session->userdata('uid')
								);
	 						}
		 						if(!empty($arrayNBPay)){
									$NbPayInsert = $this->$model->insertBatchData($arrayNBPay,'ret_billing_payment'); 
								}
	 					}
	 					
	 					if(sizeof($adv_adj_details)>0)
	 					{
	 						foreach($adv_adj_details as $adv_adj)
	 						{
	 							$arratAdvAdj=array(
	 											'is_adavnce_adjusted'=>1,
	 											'adjusted_bill_id'=>$insId,
	 											'updated_time'	=> date("Y-m-d H:i:s"),
												'updated_by'	=> $this->session->userdata('uid')
	 										);
	 							$update_status = $this->$model->updateData($arratAdvAdj,'bill_adv_id',$adv_adj['bill_adv_id'], 'ret_billing_advance');
	 						}
	 					}

						$billSale = (isset($_POST['sale']) ? $_POST['sale']:'');
						//echo "<pre>"; print_r($billSale);exit;
						//echo "<pre>"; print_r($billSale);exit;
						if(!empty($billSale)){
							$arrayBillSales = array();
							foreach($billSale['is_est_details'] as $key => $val){
								$arrayBillSales= array(
									'bill_id' => $insId,
									'esti_item_id'  => (isset($billSale['est_itm_id'][$key]) ? $billSale['est_itm_id'][$key]:NULL),
									'item_type' 	=> $billSale['itemtype'][$key], 
									'bill_type' 	=> $billSale['is_est_details'][$key],
									'product_id' 	=> ($billSale['product'][$key]!='' ? $billSale['product'][$key]:NULL),
									'design_id' 	=> ($billSale['design'][$key]!='' ? $billSale['design'][$key]:NULL),
									'tag_id'		=> ($billSale['tag'][$key]!='' ? $billSale['tag'][$key]:NULL),
									'quantity' 		=> 1, 
									'purity' 		=> ($billSale['purity'][$key]!='' ? $billSale['purity'][$key]:NULL), 
									'size' 			=> ($billSale['size'][$key]!='' ? $billSale['size'][$key]:NULL), 
									'uom' 			=> ($billSale['uom'][$key]!='' ?  $billSale['uom'][$key]:NULL), 
									'piece' 		=> $billSale['pcs'][$key], 
									'less_wt' 		=> ($billSale['less'][$key]!='' ? $billSale['less'][$key]:NULL), 
									'net_wt' 		=> $billSale['net'][$key], 
									'gross_wt' 		=> $billSale['gross'][$key], 
									'calculation_based_on' => $billSale['calltype'][$key], 
									'wastage_percent' => $billSale['wastage'][$key], 
									'mc_value' 		=> $billSale['mc'][$key], 
									'mc_type' 		=> $billSale['bill_mctype'][$key], 
									'item_cost' 	=> $billSale['billamount'][$key], 
									'item_total_tax'=> $billSale['item_total_tax'][$key], 
									'tax_group_id'  => $billSale['taxgroup'][$key],
									'bill_discount' => empty($billSale['discount'][$key]) ? 0 : $billSale['discount'][$key], 
									'rate_per_grm'  => $billSale['per_grm'][$key],
									'is_partial_sale'=>$billSale['is_partial'][$key]
								); 

								if(!empty($arrayBillSales)){


								
								$tagInsert = $this->$model->insertData($arrayBillSales,'ret_bill_details'); 

								//print_r($this->db->last_query());exit;
								if($billSale['stone_details'][$key])
								{
								$stone_details=json_decode($billSale['stone_details'][$key],true);
								foreach($stone_details as $stone)
								{
								$stone_data=array(
								'bill_id'        =>$insId,
								'bill_det_id'   =>$tagInsert,
								'pieces'        =>$stone['stone_pcs'],
								'wt'            =>$stone['stone_wt'],
								'stone_id'      =>$stone['stone_id'],
								'price'         =>$stone['stone_price'],
								'item_type'     =>1 //Sale item
								);
								$stoneInsert = $this->$model->insertData($stone_data,'ret_billing_item_stones');
								}										
								}
								if(isset($billSale['est_itm_id'][$key]))
								{
									//Update Estimation Items by est_itm_id
									$this->$model->updateData(array('purchase_status'=>1,'bil_detail_id'=>$tagInsert),'est_item_id',(isset($billSale['est_itm_id'][$key])? $billSale['est_itm_id'][$key]:''), 'ret_estimation_items');	
								}
								if(isset($billSale['tag'][$key]))
								{
									//Update Estimation Items by est_itm_id
									$this->$model->updateData(array('purchase_status'=>1,'bil_detail_id'=>$tagInsert),'tag_id',(isset($billSale['tag'][$key])? $billSale['tag'][$key]:''), 'ret_estimation_items');	
								}

								if($addData['filter_order_no']!='')
								{

									//Update Advance Adj By Order No
								$this->$model->updateData(array('is_adavnce_adjusted'=>1,'adjusted_bill_id'=>$insId,'updated_time'	=> date("Y-m-d H:i:s"),'updated_by'	=> $this->session->userdata('uid')),'order_no',$addData['filter_order_no'], 'ret_billing_advance');	
								}
								
								//print_r($this->db->last_query());exit;
								}

								$this->$model->updateData(array('tag_status'=>1),'tag_id',$billSale['tag'][$key], 'ret_taging');
								
							}
							
						}
					$billPurchase = (isset($_POST['purchase']) ? $_POST['purchase']:'');
					//echo "<pre>"; print_r($billPurchase);exit;
						if(!empty($billPurchase)){
							$arrayPurchaseBill = array();
							foreach($billPurchase['is_est_details'] as $key => $val)
							{
								if($billPurchase['is_est_details'][$key] == 1)
								{
									$arrayPurchaseBill= array(
									                'bill_id' => $insId, 
									                'metal_type' => $billPurchase['metal_type'][$key], 
									                'item_type' => $billPurchase['itemtype'][$key], 
									                'est_id' => $billPurchase['estid'][$key], 
									                'gross_wt' => $billPurchase['gross'][$key], 
									                'stone_wt' => $billPurchase['stone_wt'][$key],
									                'dust_wt' => $billPurchase['dust_wt'][$key],
									                'wastage_percent' => $billPurchase['wastage'][$key], 
									                'rate' => $billPurchase['billamount'][$key], 
									                'rate_per_grm' => $billPurchase['rate_per_grm'][$key], 
									                'bill_discount' => empty($billPurchase['discount'][$key]) ? 0 : $billPurchase['discount'][$key]); 
								}
								
								if(!empty($arrayPurchaseBill)){
    								$oldMetal = $this->$model->insertData($arrayPurchaseBill,'ret_bill_old_metal_sale_details');
    								if($oldMetal)
    								{

    									if($billPurchase['stone_details'][$key])
										{
										$stone_details=json_decode($billPurchase['stone_details'][$key],true);
										foreach($stone_details as $stone)
										{
										$stone_data=array(
										'bill_id'        =>$insId,
										'old_metal_sale_id'=>$oldMetal,
										'pieces'        =>$stone['stone_pcs'],
										'wt'            =>$stone['stone_wt'],
										'stone_id'      =>$stone['stone_id'],
										'price'         =>$stone['stone_price'],
										'item_type'     =>2 //Purchase item
										);
										$stoneInsert = $this->$model->insertData($stone_data,'ret_billing_item_stones');
										}										
										}

    								    //Update Estimation Items
								        $this->$model->updateData(array('purchase_status'=>1,'bill_id'=>$insId),'old_metal_sale_id',$billPurchase['est_itm_id'][$key], 'ret_estimation_old_metal_sale_details');	
    								}
    							}
							}
							
						}
						
					}
					if($this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
						$this->session->set_flashdata('chit_alert',array('message'=>'Billing added successfully','class'=>'success','title'=>'Add Billing'));
				 	
					}
					else
					{
						$this->db->trans_rollback();
						echo $this->db->_error_message()."<br/>";					   
						echo $this->db->last_query();exit;						 	
						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Add Billing'));
					}
					
					redirect('admin_ret_billing/billing/list');	
				break;
					
			case "edit":
		 			//$data['billing'] = $this->$model->get_entry_records($id);
					//$data['uom']= $this->$model->getUOMDetails();
					$data['billing'] = $this->$model->getBillingDetails($id);
					$data['payment'] = $this->$model->getPaymentDetails($id);
					$data['est_other_item'] = $this->$model->getOtherEstimateItemsDetails($id);
					//echo "<pre>"; print_r($data);exit;
					$data['main_content'] = "billing/form" ;
		 			$this->load->view('layout/template', $data);
				break; 	 
						
			case 'delete':
					$this->db->trans_begin();
					$this->$model->deleteData('estimation_id', $id, 'ret_estimation');
					$this->$model->deleteData('est_id', $id, 'ret_est_gift_voucher_details'); 
						$this->$model->deleteData('est_id', $id, 'ret_est_chit_utilization'); 
						$this->$model->deleteData('esti_id', $id, 'ret_estimation_items'); 
						$this->$model->deleteData('est_id', $id, 'ret_estimation_item_stones'); 
						$this->$model->deleteData('est_id', $id, 'ret_estimation_item_other_materials'); 
						$this->$model->deleteData('est_id', $id, 'ret_estimation_old_metal_sale_details');					
					if( $this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
						$this->session->set_flashdata('chit_alert', array('message' => 'Billing deleted successfully','class' => 'success','title'=>'Delete Billing'));	  
					}			  
					else
					{
						$this->db->trans_rollback();
						$this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete Billing'));
					}
					redirect('admin_ret_billing/billing/list');	
				break;
					
			case "update":
		 			$updateData = $_POST['billing'];
		 			//echo "<pre>"; print_r($_POST);exit;
		 			$ref_bill_id = ( $updateData['bill_type'] == 3 || $updateData['bill_type'] ==7 ?(!empty($updateData['ret_bill_id']) ? $updateData['ret_bill_id'] : NULL ): NULL );
		 			$data = array(						
						'ref_bill_id'	=> (!empty($updateData['ret_bill_id']) ? $updateData['ret_bill_id'] : NULL ),
						'bill_type'		=> $updateData['bill_type'],
						'pan_no'		=> (!empty($updateData['pan_no']) ? $updateData['pan_no'] : NULL ),
						'bill_date'		=> (!empty($updateData['bill_date']) ? $updateData['bill_date'] : NULL ),
						'bill_cus_id'	=> (!empty($updateData['bill_cus_id']) ? $updateData['bill_cus_id'] :NULL ),
						'tot_discount'	=> (!empty($updateData['discount']) ? $updateData['discount'] : 0 ),
						'tot_bill_amount'	=> (!empty($updateData['total_cost']) ? $updateData['total_cost'] : 0 ),
						'tot_amt_received'	=> (!empty($updateData['tot_amt_received']) ? $updateData['tot_amt_received'] : 0),
						'is_credit'			=> (!empty($updateData['is_credit']) ? $updateData['is_credit'] : 0 ),
						'credit_status'		=> (!empty($updateData['is_credit'] && $updateData['is_credit']==1) ? 2: 1 ),
						'credit_due_date'	=> (!empty($updateData['credit_due_date']) ? ($updateData['is_credit'] == 1 ? $updateData['credit_due_date']: NULL ) : NULL ),
						'updated_time'	=> date("Y-m-d H:i:s"),
						'approved_by'    => $this->session->userdata('uid'),
						'id_branch'     => $updateData['id_branch']
					); 
					$this->db->trans_begin();
				    $update_status = $this->$model->updateData($data,'bill_id',$id, 'ret_billing');
				   // print_r($this->db->last_query());exit;
					if($update_status)
					{

						

						if($updateData['cash_payment']>0)
	 					{
	 						$arrayCashPay=array(
	 									'payment_amount'=>$updateData['cash_payment'],
	 									'payment_mode'=>'Cash',
	 									'payment_for'		=>($updateData['bill_type']==6 ?2:1),
	 									'payment_status'=>1,
	 									'payment_date'		=>(!empty($updateData['bill_date']) ? $updateData['bill_date'] : NULL ),
	 									'updated_time'	=> date("Y-m-d H:i:s"),
										'updated_by'	=> $this->session->userdata('uid')
	 								);
								if(!empty($arrayCashPay)){
								 $pay_upadate = $this->$model->updateData($arrayCashPay,'bill_id',$id, 'ret_billing_payment');

								}
	 					}
	 					$billSale = (isset($_POST['sale']) ? $_POST['sale']:'');
	 					//echo "<pre>"; print_r($billSale);exit;
	 					if(!empty($billSale)){
							$arrayBillSales = array();
							foreach($billSale['is_est_details'] as $key => $val){
								$arrayBillSales= array(
									'bill_id' => $id,
									'esti_item_id'  => (isset($billSale['est_itm_id'][$key]) ? $billSale['est_itm_id'][$key]:NULL),
									'item_type' 	=> $billSale['itemtype'][$key], 
									'bill_type' 	=> $billSale['is_est_details'][$key],
									'product_id' 	=> ($billSale['product'][$key]!='' ? $billSale['product'][$key]:NULL),
									'design_id' 	=> ($billSale['design'][$key]!='' ? $billSale['design'][$key]:NULL),
									'tag_id'		=> ($billSale['tag'][$key]!='' ? $billSale['tag'][$key]:NULL),
									'quantity' 		=> 1, 
									'purity' 		=> ($billSale['purity'][$key]!='' ? $billSale['purity'][$key]:NULL), 
									'size' 			=> ($billSale['size'][$key]!='' ? $billSale['size'][$key]:NULL), 
									'uom' 			=> ($billSale['uom'][$key]!='' ?  $billSale['uom'][$key]:NULL), 
									'piece' 		=> $billSale['pcs'][$key], 
									'less_wt' 		=> ($billSale['less'][$key]!='' ? $billSale['less'][$key]:NULL), 
									'net_wt' 		=> $billSale['net'][$key], 
									'gross_wt' 		=> $billSale['gross'][$key], 
									'calculation_based_on' => $billSale['calltype'][$key], 
									'wastage_percent' => $billSale['wastage'][$key], 
									'mc_value' 		=> $billSale['mc'][$key], 
									'mc_type' 		=> $billSale['bill_mctype'][$key], 
									'item_cost' 	=> $billSale['billamount'][$key], 
									'item_total_tax'=> $billSale['item_total_tax'][$key], 
									'tax_group_id'  => $billSale['taxgroup'][$key],
									'bill_discount' => empty($billSale['discount'][$key]) ? 0 : $billSale['discount'][$key], 
									'rate_per_grm'  => $billSale['per_grm'][$key],
									'is_partial_sale'=>$billSale['is_partial'][$key]
								); 

								if(!empty($arrayBillSales))
								{	
									if(isset($billSale['bill_det_id'][$key]) && $billSale['bill_det_id'][$key]!='')
									{
										 $update_status = $this->$model->updateData($arrayBillSales,'bill_det_id',$billSale['bill_det_id'][$key], 'ret_bill_details');
										 //print_r($this->db->last_query());exit;
									}
									else
									{

										$tagInsert = $this->$model->insertData($arrayBillSales,'ret_bill_details'); 
										if($billSale['stone_details'][$key])
										{
											$stone_details=json_decode($billSale['stone_details'][$key],true);
											foreach($stone_details as $stone)
											{
												$stone_data=array(
												'bill_id'        =>$id,
												'bill_det_id'   =>$tagInsert,
												'pieces'        =>$stone['stone_pcs'],
												'wt'            =>$stone['stone_wt'],
												'stone_id'      =>$stone['stone_id'],
												'price'         =>$stone['stone_price'],
												'item_type'     =>1 //Sale item
												);
												$stoneInsert = $this->$model->insertData($stone_data,'ret_billing_item_stones');
											}										
										}
										if(isset($billSale['est_itm_id'][$key]))
										{
											//Update Estimation Items by est_itm_id
											$this->$model->updateData(array('purchase_status'=>1,'bil_detail_id'=>$tagInsert),'est_item_id',(isset($billSale['est_itm_id'][$key])? $billSale['est_itm_id'][$key]:''), 'ret_estimation_items');	
										}
										if(isset($billSale['tag'][$key]))
										{
											//Update Estimation Items by est_itm_id
											$this->$model->updateData(array('purchase_status'=>1,'bil_detail_id'=>$tagInsert),'tag_id',(isset($billSale['tag'][$key])? $billSale['tag'][$key]:''), 'ret_estimation_items');	

											$this->$model->updateData(array('tag_status'=>1),'tag_id',$billSale['tag'][$key], 'ret_taging');
										}
										if($addData['filter_order_no']!='')
										{
											//Update Advance Adj By Order No
										$this->$model->updateData(array('is_adavnce_adjusted'=>1,'adjusted_bill_id'=>$insId,'updated_time'	=> date("Y-m-d H:i:s"),'updated_by'	=> $this->session->userdata('uid')),'order_no',$addData['filter_order_no'], 'ret_billing_advance');	
										}
									}							
									
								}
								
							}
							
						}

						$billPurchase = (isset($_POST['purchase']) ? $_POST['purchase']:'');
						//echo "<pre>"; print_r($billPurchase);exit;
						if(!empty($billPurchase)){
							$arrayPurchaseBill = array();
							foreach($billPurchase['is_est_details'] as $key => $val)
							{
								if($billPurchase['is_est_details'][$key] == 1)
								{
									$arrayPurchaseBill= array(
									'bill_id' => $id, 
									'metal_type' => $billPurchase['metal_type'][$key], 
									'item_type' => $billPurchase['itemtype'][$key], 
									'est_id' => $billPurchase['estid'][$key], 
									'gross_wt' => $billPurchase['gross'][$key], 
									'stone_wt' => $billPurchase['stone_wt'][$key],
									'dust_wt' => $billPurchase['dust_wt'][$key],
									'wastage_percent' => $billPurchase['wastage'][$key], 
									'rate' => $billPurchase['billamount'][$key], 
									'rate_per_grm' => $billPurchase['rate_per_grm'][$key], 
									'bill_discount' => empty($billPurchase['discount'][$key]) ? 0 : $billPurchase['discount'][$key]
									); 
								}
								if(!empty($arrayPurchaseBill)){
									if(isset($billPurchase['old_metal_sale_id'][$key]) && ($billPurchase['old_metal_sale_id'][$key]!=''))
									{
										 $update_status = $this->$model->updateData($arrayPurchaseBill,'old_metal_sale_id',$billPurchase['old_metal_sale_id'][$key], 'ret_bill_old_metal_sale_details');
										
										 //Update Estimation Items
								        $this->$model->updateData(array('purchase_status'=>1,'bill_id'=>$id),'old_metal_sale_id',$billPurchase['est_itm_id'][$key], 'ret_estimation_old_metal_sale_details');
									}
									else
									{
										$oldMetal = $this->$model->insertData($arrayPurchaseBill,'ret_bill_old_metal_sale_details');
										if($oldMetal)
										{
											if($billPurchase['stone_details'][$key])
											{
												$stone_details=json_decode($billPurchase['stone_details'][$key],true);
												foreach($stone_details as $stone)
												{
												$stone_data=array(
												'bill_id'        =>$id,
												'old_metal_sale_id'=>$oldMetal,
												'pieces'        =>$stone['stone_pcs'],
												'wt'            =>$stone['stone_wt'],
												'stone_id'      =>$stone['stone_id'],
												'price'         =>$stone['stone_price'],
												'item_type'     =>2 //Purchase item
												);
												$stoneInsert = $this->$model->insertData($stone_data,'ret_billing_item_stones');
												}										
											}

											  //Update Estimation Items
								        $this->$model->updateData(array('purchase_status'=>1,'bill_id'=>$id),'old_metal_sale_id',$billPurchase['est_itm_id'][$key], 'ret_estimation_old_metal_sale_details');
										}
									}
    							}
							}
							
						}				
					}
					if($this->db->trans_status()=== TRUE)
					{
						$this->db->trans_commit();

						$this->session->set_flashdata('chit_alert',array('message'=>'Billing updated successfully','class'=>'success','title'=>'Update Billing'));
						redirect('admin_ret_billing/billing/list');
					}
					else
					{
						$this->db->trans_rollback();						 	
						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Update Billing'));
						redirect('admin_ret_billing/billing/list');
 					}
				break;
			
						
			case 'update_status':
						redirect('admin_ret_billing/billing/list');
				
			default: 
					  	$list = $this->$model->ajax_getBillingList();	 
					  	$access = $this->admin_settings_model->get_access('admin_ret_billing/billing/list');
				        $data = array(
				        					'list'  => $list,
											'access'=> $access
				        				);  
						echo json_encode($data);
		}
	}
	public function createNewCustomer(){
		$model = "ret_billing_model";
		if(!empty($_POST['cusName']) && !empty($_POST['cusMobile']) && !empty($_POST['cusBranch']) && !empty($_POST['id_village'])){
			$data = $this->$model->createNewCustomer($_POST['cusName'], $_POST['cusMobile'], $_POST['cusBranch'],$_POST['id_village']);
			echo json_encode($data);
		}else{
			echo json_encode(array("success" => FALSE, "response" => array(), "message" => "Please fill all the required fields"));
		}
	}
	public function getEstimationDetails(){
		$model = "ret_billing_model";
		if((!empty($_POST['estId']) || !empty($_POST['order_no'])) && !empty($_POST['billType'])){
			$data = $this->$model->getEstimationDetails($_POST['estId'], $_POST['billType'], $_POST['id_branch'], $_POST['order_no']);
			if(sizeof($data['item_details'])>0 || sizeof($data['old_matel_details'])>0)
			{
				echo json_encode(array('success' => TRUE, 'message' => 'Records reterived successfully.', 'responsedata' => $data));
			}
			else{
				echo json_encode(array('success' => FALSE, 'message' => 'No record found for given details'));
			}
		}else{
			echo json_encode(array("success" => FALSE, "response" => array(), "message" => "Please fill all the required fields"));
		}
	}
	public function getAllTaxgroupItems(){
		$model = "ret_billing_model";
		$data = $this->$model->getAllTaxgroupItems();
		echo json_encode($data);
	}
	public function getCustomersBySearch(){
		$model = "ret_billing_model";
		$data = $this->$model->getAvailableCustomers($_POST['searchTxt']);	  
		echo json_encode($data);
	}
	public function getTaggingBySearch(){
		$model = "ret_billing_model";
		$data = $this->$model->getTaggingBySearch($_POST['tagId']);	  
		echo json_encode($data);
	}
	public function getProductBySearch(){
		$model = "ret_billing_model";
		$data = $this->$model->getProductBySearch($_POST['searchTxt']);	  
		echo json_encode($data);
	}
	public function getProductDesignBySearch(){
		$model = "ret_billing_model";
		$data = $this->$model->getProductDesignBySearch($_POST['searchTxt'], $_POST['ProCode']);	  
		echo json_encode($data);
	}
	public function getMetalTypes(){
		$model = "ret_billing_model";
		$data = $this->$model->getMetalTypes();	  
		echo json_encode($data);
	}

	//chit acc
	public function get_scheme_accounts()
	{
		$model = "ret_billing_model";
		$searchTxt=$this->input->post('searchTxt');
		$bill_cus_id=$this->input->post('bill_cus_id');
		$data=$this->$model->get_closed_accounts($searchTxt,$bill_cus_id);
		echo json_encode($data);
	}
	//chit acc

	//Advance Adj
	public function get_advance_details()
	{
		$model = "ret_billing_model";
		$bill_cus_id=$this->input->post('bill_cus_id');
		$data=$this->$model->get_advance_details($bill_cus_id);
		echo json_encode($data);
	}
	//Advance Adj

	public function getBillDetails()
	{
		$model = "ret_billing_model";
		if(!empty($_POST['billNo']) && !empty($_POST['billType'])){
		$data = $this->$model->getBillData($_POST['billNo'], $_POST['billType'], $_POST['id_branch']);
		if(!empty($data)){
		echo json_encode(array('success' => TRUE, 'message' => 'Records reterived successfully.', 'responsedata' => $data));
		}else{
		echo json_encode(array('success' => FALSE, 'message' => 'No record found for given details'));
		}
		}else{
		echo json_encode(array("success" => FALSE, "response" => array(), "message" => "Please fill all the required fields"));
		}
	}
	
	public function getCreditBillDetails()
	{
		$model = "ret_billing_model";
		if(!empty($_POST['billNo']) && !empty($_POST['billType'])){
		$data = $this->$model->getCreditBillDetails($_POST['billNo'], $_POST['billType'], $_POST['id_branch']);
		if(sizeof($data['bill_details'])>0){
		echo json_encode(array('success' => TRUE, 'message' => 'Records reterived successfully.', 'responsedata' => $data));
		}else{
		echo json_encode(array('success' => FALSE, 'message' => 'No record found for given details'));
		}
		}else{
		echo json_encode(array("success" => FALSE, "response" => array(), "message" => "Please fill all the required fields"));
		}
	}
	
	function sendotp()
	{
		$model="ret_billing_model";
		$mobile_num     = $this->input->post('mobile');
		$send_resend     = $this->input->post('send_resend');
		$sent_otp='';
		if($mobile_num!='')
		{
			$this->db->trans_begin();
			$this->session->unset_userdata("bill_chit_otp");
			$this->session->unset_userdata("bill_chit_otp_exp");
			$OTP = mt_rand(100001,999999);
			$this->session->set_userdata('bill_chit_otp',$OTP);
			$this->session->set_userdata('bill_chit_otp_exp',time()+60);
			$message="Hi Your OTP  For Chit Billing is :  ".$OTP." Will expire within 1 minute.";
			$otp_gen_time = date("Y-m-d H:i:s");
			$insData=array(
			'mobile'=>$mobile_num,
			'otp_code'=>$OTP,
			'otp_gen_time'=>date("Y-m-d H:i:s"),
			'module'=>'Billing Chit Utilization',
			'send_resend'=>$send_resend,
			'id_emp'=>$this->session->userdata('uid')
			);
			$insId = $this->$model->insertData($insData,'otp');
		}
	    if($insId)
	  	{		

	  			$this->db->trans_commit();
	  			//$this->send_sms($mobile_num,$message);
	  			$status=array('status'=>true,'msg'=>'OTP sent Successfully','otp'=>$OTP);	
	  	}
	  	else
	  	{
	  			$this->db->trans_rollback();
	  			$status=array('status'=>false,'msg'=>'Unabe To Send Try Again');	
	  	}
		
		echo json_encode($status);
	}
	
	function update_otp()
	{
	    $model="ret_billing_model";
	    $user_otp=$this->input->post('user_otp');
	    $otp=$this->session->userdata('bill_chit_otp');
	    if($otp==$user_otp)
	    {
	        if(time() >= $this->session->userdata('bill_chit_otp_exp'))
			{
				$this->session->unset_userdata('bill_chit_otp');
				$this->session->unset_userdata('bill_chit_otp_exp');
				$data=array('status'=>false,'msg'=>'OTP has been expired');
			}
			else
			{
				$updData=array('is_verified'=>1,'verified_time'=>date("Y-m-d H:i:s"));
				$update_otp=$this->$model->updateData($updData,'otp_code',$user_otp,'otp');
				$data=array('status'=>true,'msg'=>'OTP Verified Successfully');
			}
	    }
	    else
	    {
	        $data=array('status'=>false,'msg'=>'Please Enter Valid OTP');
	    }
	    echo json_encode($data);
	}
	
	function send_sms($mobile,$message)
	{
		if($this->config->item('sms_gateway') == '1'){
		    $this->sms_model->sendSMS_MSG91($mobile,$message);		
		}
		elseif($this->config->item('sms_gateway') == '2'){
	        $this->sms_model->sendSMS_Nettyfish($mobile,$message,'trans');	
		}
	}
	
	function billing_invoice($id)
	{

		$model="ret_billing_model";
		$data['billing'] = $this->$model->getBillingDetails($id);
		$data['payment'] = $this->$model->getPaymentDetails($id);
		$data['metal_rate'] = $this->$model->max_metalrate();
		$data['est_other_item'] = $this->$model->getOtherEstimateItemsDetails($id,$data['billing']['bill_type']);
		//echo "<pre>"; print_r($data);exit;
		$this->load->helper(array('dompdf', 'file'));

		$this->load->helper(array('dompdf', 'file'));

        $dompdf = new DOMPDF();

		$html = $this->load->view('billing/print/receipt_billing', $data,true);

	    $dompdf->load_html($html);
		
		$dompdf->set_paper("a4", "portriat" );

		$dompdf->render();

		$dompdf->stream("Receipt.pdf",array('Attachment'=>0));
	}
	
}	
?>
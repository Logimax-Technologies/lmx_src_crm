<?php

if( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'libraries/dompdf/autoload.inc.php');

use Dompdf\Dompdf;

class Admin_ret_billing extends CI_Controller

{

	const IMG_PATH  = 'assets/img/';

	function __construct()

	{

		parent::__construct();

		ini_set('date.timezone', 'Asia/Calcutta');

		$this->load->model('ret_billing_model');

		$this->load->model('admin_settings_model');

		$this->load->model("sms_model");

		$this->load->model("admin_usersms_model");

		$this->load->model("log_model");

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

	public function base64ToFile($imgBase64){

		$data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imgBase64)); // might not work on some systems, specify your temp path if system temp dir is not writeable

		$temp_file_path = tempnam(sys_get_temp_dir(), 'tempimg');

		file_put_contents($temp_file_path, $data);

		$image_info = getimagesize($temp_file_path); 

		$imgFile = array(

		     'name' => uniqid().'.'.preg_replace('!\w+/!', '', $image_info['mime']),

		     'tmp_name' => $temp_file_path,

		     'size'  => filesize($temp_file_path),

		     'error' => UPLOAD_ERR_OK,

		     'type'  => $image_info['mime'],

		);

		return $imgFile;

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

	public function billing($type="", $id="", $billno=""){

		$model = "ret_billing_model";

		$set_model="admin_settings_model";

		$sms_model="admin_usersms_model";

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

					// 1-Sales, 2-Sales&Purchase, 3-Sales,purchase&Return, 4-Purchase, 5-Order Advance, 6-Advance,7-Sales Return,8-Credit Collection,9-Order Delivery,10-Chit Pre Close

					$addData = $_POST['billing'];

					$allow_submit=TRUE;

					$dCData = $this->admin_settings_model->getBranchDayClosingData($addData['id_branch']);

					$fin_year       = $this->$model->get_FinancialYear();

					$billSale = (isset($_POST['sale']) ? $_POST['sale']:'');

					$billPurchase = (isset($_POST['purchase']) ? $_POST['purchase']:'');

					if(!empty($billSale))

					{

					    foreach($billSale['is_est_details'] as $key => $val)

					    {

					        $est_itm_id =(isset($billSale['est_itm_id'][$key]) ? ($billSale['est_itm_id'][$key]!='' ? $billSale['est_itm_id'][$key]:''):'');

					        $tag_id     =($billSale['tag'][$key]!='' ? $billSale['tag'][$key]:'');

					        if($tag_id!='')

					        {

					            $tag_status=$this->$model->get_tag_status($tag_id);

					            if($tag_status['tag_status']==0)

					            {

					                $allow_submit=TRUE;

					            }else{

					                $allow_submit=FALSE;

					                break;

					            }

					        }

					        if($est_itm_id!='')

					        {

					            $est_status=$this->$model->get_esti_status($est_itm_id);

					            if($est_status['purchase_status']==0)

					            {

					                $allow_submit=TRUE;

					            }else{

					                $allow_submit=FALSE;

					                break;

					            }

					        }

					    }

					    

					}

					if(!empty($billPurchase))

					{

					   foreach($billPurchase['is_est_details'] as $key => $val)

					   {

					       $est_old_itm_id=$billPurchase['est_old_itm_id'][$key];

					       if($est_old_itm_id)

					       {

					           $old_est_status=$this->$model->get_old_esti_status($est_old_itm_id);

					            if($est_status['purchase_status']==0)

					            {

					                $allow_submit=TRUE;

					            }else{

					                $allow_submit=FALSE;

					                break;

					            }

					       }

					   }

					}

					if($allow_submit)

					{

					    $serviceID=26;

    				    if(sizeof($dCData) > 0){

    						$chit_details		        = json_decode($addData['chit_uti'],true);

    						$voucher_details	        = json_decode($addData['vocuher'],true);

    						$card_pay_details	        = json_decode($addData['card_pay'],true);

    						$adv_adj			        = json_decode($addData['adv_adj'],true); 

    						$adv_adj_details            =  $adv_adj[0];

    						$cheque_details	            = json_decode($addData['chq_pay'],true); 

    						$net_banking_details        = json_decode($addData['net_bank_pay'],true); 

    						$order_adv_adj_details		= json_decode($addData['order_adv_adj'],true);



    						

    						$bill_no = $this->$model->code_number_generator($addData['id_branch']);   //Bill Number Generate 



    					    $ref_bill_id = ($addData['bill_type'] ==8 ?(!empty($addData['ret_bill_id']) ? $addData['ret_bill_id'] : NULL ): NULL );

    					    $bill_date = ($dCData['entry_date'] == date("Y-m-d") ? date("Y-m-d H:i:s") : $dCData['entry_date']);

    						$data = array(						

    							'bill_no'		    => $bill_no,

    							'fin_year_code'		=> $fin_year['fin_year_code'],

    							'ref_bill_id'	    => $ref_bill_id,

    							'bill_type'		    => $addData['bill_type'],

    							'round_off_amt'		=> $addData['round_off'],

    							'goldrate_22ct'		=> $addData['goldrate_22ct'],

    							'silverrate_1gm'	=> $addData['silverrate_1gm'],

    							'handling_charges'	=> ($addData['handling_charges']!='' ? $addData['handling_charges']:0),

    							'pan_no'		    => (!empty($addData['pan_no']) ? $addData['pan_no'] : NULL ),

    							'bill_cus_id'   	=> (!empty($addData['bill_cus_id']) ? $addData['bill_cus_id'] :NULL ),

    							'tot_discount'	    => (!empty($addData['discount']) ? $addData['discount'] : 0 ),

    							'tot_bill_amount'	=> (!empty($addData['total_cost']) ? $addData['total_cost'] : 0 ),

    							'tot_amt_received'	=> (!empty($addData['tot_amt_received']) ? $addData['tot_amt_received'] : 0),

    							'is_credit'			=> (!empty($addData['is_credit']) ? $addData['is_credit'] : 0 ),

    							'credit_status'		=> (!empty($addData['is_credit'] && $addData['is_credit']==1) ? 2: 1 ),

    							'credit_due_date'	=> (!empty($addData['credit_due_date']) ? ($addData['is_credit'] == 1 ? $addData['credit_due_date']: NULL ) : NULL ),

    							'bill_date'	        => $bill_date,

    							'created_time'	    => date("Y-m-d H:i:s"),

    							'created_by'        => $this->session->userdata('uid'),

    							'counter_id'        => ($this->session->userdata('counter_id')!='' ? $this->session->userdata('counter_id'):NULL),

    							'id_branch'         => $addData['id_branch'],

    							'id_delivery'       => $addData['id_delivery'],

    							'remark'   	        => (!empty($addData['remark']) ? $addData['remark'] :NULL ),

    							'credit_disc_amt'	=> (!empty($addData['credit_discount_amt']) ? $addData['credit_discount_amt'] : 0),

    							'billing_for'       => $addData['billing_for'],

    							'id_cmp_emp'        => (!empty($addData['id_cmp_emp']) ? $addData['id_cmp_emp']:NULL),

    							'tcs_tax_amt'       => (!empty($addData['tcs_tax_amt']) ? $addData['tcs_tax_amt']:0),

    							'tcs_tax_per'       => ($addData['tcs_tax_amt']>0 ? $addData['tcs_tax_per']:0),

    						); 

    		 				$this->db->trans_begin();

    		 				$insId = $this->$model->insertData($data,'ret_billing');

    		 				if($insId)

    		 				{

    		 				    

    		 				    if($addData['pan_no']!='')

    		 				    {

    		 				        $this->$model->updateData(array('pan'=>strtoupper($addData['pan_no'])),'id_customer',$addData['bill_cus_id'],'customer');

    		 				    }

    		 				    

    		 				    //Gift voucher details

    		 				    if($addData['gift_voucher_amt']>0 )

    		 				    {

    		 				        $customer=$this->$model->get_customer($addData['bill_cus_id']);

    		 				        $ret_settings=$this->$model->get_empty_record();

    		 				        $code = substr(strtoupper($customer['firstname']), 0, 4).mt_rand(1001,9999);

    		 				        $gift_card_data=array(

    		 				                           'id_branch'              =>$addData['id_branch'],

    		 				                           'bill_id'                =>$insId,

    		 				                           'code'                   =>$code,

    		 				                           'weight'                 =>($addData['gift_type']==2 || $addData['gift_type']==4 ? $addData['gift_voucher_amt']:0),

    		 				                           'amount'                 =>($addData['gift_type']!=2 && $addData['gift_type']!=4 ? $addData['gift_voucher_amt']:0),

    		 				                           'id_set_gift_voucher'    =>$addData['id_set_gift_voucher'],

    		 				                           'date_add'               =>date("Y-m-d"),

    		 				                           'valid_from'             =>date("Y-m-d"),

    		 				                           'valid_to'               =>date("Y-m-d", strtotime($addData['validity_days'].'days')),

    		 				                           'purchased_by'           =>$addData['bill_cus_id'],

    		 				                           'free_card'              =>1,

    		 				                           'status'                 =>0,

    		 				                           'type'                   =>2,

    		 				                           'gift_for'               =>2,  //Customer

    		 				                           'remark'                 =>'SALE GIFT ISSUED',  //Customer

    		 				                           'emp_created'            =>$this->session->userdata('uid'),

    		 				                        );

    		 				         $this->$model->insertData($gift_card_data,'gift_card');

    		 				         //print_r($this->db->last_query());exit;

    		 				    }

    		 				    //Gift voucher details

    		 				    

    		 				    

    		 				    //Pan Images

    		 					$p_ImgData = json_decode($addData['pan_img']);  

    							if(sizeof($p_ImgData) > 0){

    								foreach($p_ImgData as $precious){

    									$imgFile = $this->base64ToFile($precious->src);

    									$_FILES['pan_img'][] = $imgFile;

    								}

    							}

    		 					if(isset($_FILES['pan_img']))

    							{ 

    								$pan_imgs = "";

    								$folder =  self::IMG_PATH."billing/".$bill_no;

    								if (!is_dir($folder)) {

    									mkdir($folder, 0777, TRUE);

    								}

    								foreach($_FILES['pan_img'] as $file_key => $file_val){

    								if($file_val['tmp_name'])

    								{

    								// unlink($folder."/".$product['image']); 

    									$img_name =  "P_". mt_rand(120,1230).".jpg";

    									$path = $folder."/".$img_name; 

    									$result = $this->upload_img('image',$path,$file_val['tmp_name']);

    									if($result){

    									$pan_imgs = strlen($pan_imgs) > 0 ? $pan_imgs."#".$img_name : $img_name;

    									}

    									}

    								}

    								$this->$model->updateData(array('pan_image'=>$pan_imgs),'bill_id',$insId,'ret_billing');

    							}

    							

    		 				   

    		 					// Return Bill

    		 					if($addData['bill_type'] == 3 || $addData['bill_type']== 7) 

    	 						{ 							

    	 							//Update Ref No

    								$ref_no=$this->$model->generateRefNo($addData['id_branch'],'s_ret_refno');

    								$this->$model->updateData(array('s_ret_refno'=>$ref_no),'bill_id',$insId,'ret_billing');

                                    

    	 							foreach($_POST['sales_return'] as $return_detail){

    	 								// Update Bill Return status

    							 		$this->$model->updateData(array( "return_status" => 1),'bill_id',$ref_bill_id, 'ret_billing');

    							 		$updBillDetail = array( 

    												 		"status" 				=> 2,

    												 		"sales_return_discount" =>($return_detail['sale_ret_disc_amt']!='' ? $return_detail['sale_ret_disc_amt']:NULL),

    												 		"return_item_cost" 		=> $return_detail['sale_ret_amt']

    												 	 );

    							 		$this->$model->updateData($updBillDetail,'bill_det_id',$return_detail['bill_det_id'], 'ret_bill_details');

    							 		

    							        //Update return bill details

    							 		$upd_ret_data=array(

    							 				'bill_id'           =>$insId,

    							 				'ret_bill_id'       =>$return_detail['bill_id'],

    							 				'ret_bill_det_id'   =>$return_detail['bill_det_id'],

    							 		);

    							 		$this->$model->insertData($upd_ret_data,'ret_bill_return_details');

    							

    	 								// Reverse Esti Status for Returned item Bill

    		 							$updEstiRet = array( 

    		 											"purchase_status" 	=> 2, // 1-Purchased,2-Returned

    								 					"bil_detail_id" 	=> $ref_bill_id

    								 				  );

    							 		$this->$model->updateData($updEstiRet,'est_item_id',$return_detail['est_itm_id'], 'ret_estimation_items');

    							 		

    							 		// Reverse Tag Status for Returned item Bill 

    							 		$this->$model->updateData(array( "tag_status" => 6),'tag_id',$return_detail['tag'], 'ret_taging');

    							 		

    							 		//gift voucher

                    					$this->$model->get_gift_issue_details($return_detail['bill_id']); //Issued Voucher Cancel



                    					//Wallet Debit Transcation

                    					if($return_detail['tag']!='')

                    					{

                    					     $tag_Details=$this->$model->getWalletTransTagDetails($return_detail['tag']);

                    					  

                    					    if($tag_Details['ref_no']!='')

                    					    {

                    					        $WalletinsData=array(

                                                'id_wallet_account'=>$tag_Details['id_wallet_account'],

                                                'transaction_type' =>1,

                                                'type'             =>1,

                                                'bill_id'          =>$insId,

                                                'ref_no'           =>$tag_Details['ref_no'],

                                                'value'            =>$tag_Details['value'],

                                                'id_employee'      =>$this->session->userdata('uid'),

                                                'description'      =>'Green Tag Sales Incentive Debit',

                                                'date_transaction' =>date("Y-m-d H:i:s"),

                                                'date_add'	       =>date("Y-m-d H:i:s"),

                                                );

                        						$this->$model->insertData($WalletinsData,'wallet_transaction'); 

                    					    }

                    					}

                    					   

                                                

                    					 //Wallet Transcation Debit

                    					 

    							 	}	 					 

    			 				}

    		 					//Amount Advance

        	 					if($addData['bill_type']==5 && $addData['tot_amt_received']>0) 

        	 					{

        	 						$metal_rate=$this->$model->get_branchwise_rate($addData['id_branch']);

        							$arrayAdv = array(

        							'bill_id'           =>$insId,

        							'advance_weight'    =>($addData['sale_store_as']==2 ? ($addData['tot_amt_received']/($addData['rate_calc']==1 ?$metal_rate['goldrate_22ct'] :$metal_rate['silverrate_1gm'])):0),

        							'advance_amount'    =>($addData['sale_store_as']==1 ? ($addData['tot_amt_received']):0),

        							'advance_type'      =>1,

        							'rate_per_gram'     =>(isset($addData['rate_calc']) ?($addData['rate_calc']==1 ?$metal_rate['goldrate_22ct'] :$metal_rate['silverrate_1gm']) :$metal_rate['goldrate_22ct']),

        							'received_amount'   =>$addData['tot_amt_received'],

        							'store_as'          =>$addData['sale_store_as'],

        							'rate_calc'         =>(isset($addData['rate_calc']) ? $addData['rate_calc']:NULL),

        							'order_no'          => (!empty($addData['filter_order_no']) ? $addData['filter_order_no'] :NULL),

        							'id_customerorder'  => $addData['id_customerorder'],

        							'advance_date'		=> date("Y-m-d H:i:s"),

        							'created_time'		=> date("Y-m-d H:i:s"),

        							'created_by'    	=> $this->session->userdata('uid')

        							);

        							$advInsId = $this->$model->insertData($arrayAdv,'ret_billing_advance');

        							if($advInsId)

        							{

        							    $service = $this->$set_model->get_service_by_code('CUS_ORD');

        							     

        							    if($service['serv_whatsapp'] == 1)

                    					{

                    					    $sms_data=$this->admin_usersms_model->Get_service_code_sms('CUS_ORD',$addData['id_customerorder'],'');

                    						if($sms_data['mobile']!='')

                    						{

                    						    $whatsapp=$this->admin_usersms_model->send_whatsApp_message($sms_data['mobile'],$sms_data['message']);

                    						}

                    					}

        							}

    

        							//Update Ref No

    								$ref_no=$this->$model->generateRefNo($addData['id_branch'],'order_adv_ref_no');

    								$this->$model->updateData(array('order_adv_ref_no'=>$ref_no),'bill_id',$insId,'ret_billing');

    								//Update Ref No

        	 					}

    		 		

    	 						if($addData['cash_payment']>0)

    		 					{

    		 						$arrayCashPay=array(

    								'bill_id'           =>$insId,

    								'payment_amount'    =>($addData['pay_to_cus']>0 ?'-'.$addData['cash_payment']:$addData['cash_payment']),

    								'payment_mode'      =>'Cash',

    								'type'              =>($addData['pay_to_cus']>0 ?2:($addData['pay_to_cus']>0 ?3:1)),

    								'payment_for'	    =>($addData['bill_type']==6 ?2:($addData['pay_to_cus']>0 ?3:1)),

    								'payment_status'    =>1,

    								'payment_date'		=>date("Y-m-d H:i:s"),

    								'created_time'	    => date("Y-m-d H:i:s"),

    								'created_by'	    => $this->session->userdata('uid')

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

    		 							$arrayChit[]=array(

    		 							    'bill_id'=>$insId,

    		 							    'scheme_account_id'=>$chit_uti['scheme_account_id'],

    		 							    'utilized_amt'=>$chit_uti['chit_amt'],

    		 								);

    		 						}

    		 						if(!empty($arrayChit))

    		 						{

    									$chitInsert = $this->$model->insertBatchData($arrayChit,'ret_billing_chit_utilization'); 

    									if($chitInsert)

    									{

    									    foreach($chit_details as $chit_uti)

    									    {

    									        $updData=array('is_utilized'=>1,'utilized_type'=>($addData['bill_type']=10 ? 1:2));

    									        $updID= $this->$model->updateData($updData,'id_scheme_account',$chit_uti['scheme_account_id'],'scheme_account');

    									    }

    									}

    								}

    		 					}

    		 					//Gift Voucher

    		 					if(sizeof($voucher_details)>0)

    		 					{

    		 						foreach($voucher_details as $voucher)

    		 						{

    		 							$arrayVoucher=array('voucher_no'=>$voucher['id_gift_card'],'bill_id'=>$insId,'gift_voucher_amt'=>$voucher['gift_voucher_amt']);

        		 						if(!empty($arrayVoucher))

        		 						{

        									$voucerPayInsert = $this->$model->insertData($arrayVoucher,'ret_billing_gift_voucher_details'); 

        									if($voucerPayInsert)

        									{

        									    $giftUti=array(

        									                    'adjusted_bill_id'  =>$voucerPayInsert,

        									                    'redeemed_by'       =>$addData['bill_cus_id'],

        									                    'redeemed_on'       =>date("Y-m-d H:i:s"),

        									                    'redeem_type'       =>1,

        									                    'status'            =>2,

        									                 );

        									   $this->$model->updateData($giftUti,'id_gift_card',$voucher['id_gift_card'], 'gift_card');

        									}

        									//print_r($this->db->last_query());exit;

        								}

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

    										'payment_date'		=>date("Y-m-d H:i:s"),

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

    										'payment_date'		=>date("Y-m-d H:i:s"),

    										'cheque_date'		=>date("Y-m-d H:i:s"),

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

    										'bill_id'		    =>$insId,

    										'payment_amount'    =>($addData['pay_to_cus']>0 ?'-'.$nb_pay['amount']:$nb_pay['amount']),

    										'payment_for'	    =>($addData['bill_type']==6 ?2:($addData['pay_to_cus']>0 ?3:1)),

    										'payment_status'    =>1,

    										'type'              =>($addData['pay_to_cus']>0 ?2:1),

    										'payment_date'		=>date("Y-m-d H:i:s"),

    										'payment_mode'	    =>'NB',

    										'payment_ref_number'=>($nb_pay['ref_no']!='' ? $nb_pay['ref_no']:NULL),

    										'NB_type'           =>($nb_pay['nb_type']!='' ? $nb_pay['nb_type']:NULL),

    										'created_time'	    => date("Y-m-d H:i:s"),

    										'created_by'	    => $this->session->userdata('uid')

    									);

    		 						}

    			 						if(!empty($arrayNBPay)){

    										$NbPayInsert = $this->$model->insertBatchData($arrayNBPay,'ret_billing_payment'); 

    									}

    		 					}

    		 					if($adv_adj_details['adjusted_amt']>0)

    		 					{

    		 						

    								$arratAdvAdj=array(

    								'bill_no'		=>$insId,

    								'transaction_type'=>1,

    								'id_ret_wallet'	=>$adv_adj_details['id_ret_wallet'],

    								'amount'		=>$adv_adj_details['wallet_amt'],

    								'weight'		=>$adv_adj_details['wallet_wt'],

    								'rate_per_gram' =>$adv_adj_details['rate_per_gram'],

    								'created_on'	=> date("Y-m-d H:i:s"),

    								'created_by'	=> $this->session->userdata('uid'),

    								'remarks'       =>'Advance Adjusted BillId'.$insId

    								);

    		 						$this->$model->insertData($arratAdvAdj,'ret_wallet_transcation');

    		 						if($adv_adj_details['wallet_blc']>0)

    		 						{

    		 							$amount=0;$weight=0;

    		 							if($adv_adj_details['store_receipt_as']==1)

    		 							{

    		 								$amount=$adv_adj_details['wallet_blc'];

    		 							}else{

    		 								$weight=$adv_adj_details['wallet_blc'];

    		 							}

    		 							$walletBlc=array(

    		 							 'id_ret_wallet'=>$adv_adj_details['id_ret_wallet'],

    		 							 'transaction_type'=>0,

    		 							 'amount'		=>$amount,

    		 							 'weight'		=>$weight,

    		 							 'remarks'		=>'Wallet Balance After Adjust Billing',

    		 							 'created_by'	=> $this->session->userdata('uid'),

    		 							 'created_on'	=> date("Y-m-d H:i:s")

    		 							);

    		 							$this->$model->insertData($walletBlc,'ret_wallet_transcation');

    		 						}

    		 					}

    		 					

    		 					

    		 					//Sales Items

    							if(!empty($billSale)){

    								$arrayBillSales = array();

    								

    								//Update Ref No

    								$ref_no=$this->$model->generateRefNo($addData['id_branch'],'sales_ref_no');

    								$this->$model->updateData(array('sales_ref_no'=>$ref_no),'bill_id',$insId,'ret_billing');



    								foreach($billSale['is_est_details'] as $key => $val){

    									$arrayBillSales= array(

    										'bill_id' => $insId,

    										'esti_item_id'  => (isset($billSale['est_itm_id'][$key]) ? ($billSale['est_itm_id'][$key]!='' ? $billSale['est_itm_id'][$key]:NULL):NULL),

    										'item_type' 	=> ($billSale['itemtype'][$key]!='' ? $billSale['itemtype'][$key]:NULL), 

    										'bill_type' 	=> $billSale['is_est_details'][$key],

    										'total_cgst' 	=> $billSale['total_cgst'][$key], 

    										'total_sgst' 	=> $billSale['total_sgst'][$key], 

    										'total_igst' 	=> $billSale['total_igst'][$key], 

    										'product_id' 	=> ($billSale['product'][$key]!='' ? $billSale['product'][$key]:NULL),

    										'design_id' 	=> ($billSale['design'][$key]!='' ? $billSale['design'][$key]:NULL),

    										'tag_id'		=> ($billSale['tag'][$key]!='' ? $billSale['tag'][$key]:NULL),

    										'quantity' 		=> 1, 

    										'purity' 		=> ($billSale['purity'][$key]!='' ? $billSale['purity'][$key]:NULL), 

    										'size' 			=> ($billSale['size'][$key]!='' ? $billSale['size'][$key]:NULL), 

    										'uom' 			=> ($billSale['uom'][$key]!='' ?  $billSale['uom'][$key]:NULL), 

    										'piece' 		=> ($billSale['pcs'][$key]!='' ? $billSale['pcs'][$key]:NULL), 

    										'less_wt' 		=> ($billSale['less'][$key]!='' ? $billSale['less'][$key]:NULL), 

    										'net_wt' 		=> $billSale['net'][$key], 

    										'gross_wt' 		=> $billSale['gross'][$key], 

    										'calculation_based_on' => $billSale['calltype'][$key], 

    										'wastage_percent' => $billSale['wastage'][$key], 

    										'mc_value' 		=> ($billSale['mc'][$key]!='' ? $billSale['mc'][$key]:0), 

    										'mc_type' 		=> ($billSale['bill_mctype'][$key]!='' ? $billSale['bill_mctype'][$key]:NULL), 

    										'item_cost' 	=> $billSale['billamount'][$key], 

    										'item_total_tax'=> $billSale['item_total_tax'][$key], 

    										'tax_group_id'  => $billSale['taxgroup'][$key],

    									    'bill_discount'  => $billSale['discount'][$key],

    										'rate_per_grm'  => $billSale['per_grm'][$key],

    										'is_partial_sale'=>($billSale['is_partial'][$key]!='' ? $billSale['is_partial'][$key] :0),

    										'id_orderdetails'=>(isset($billSale['id_orderdetails'][$key]) ?($billSale['id_orderdetails'][$key]!='' ? $billSale['id_orderdetails'][$key] :NULL) :NULL)

    									); 

    									if(!empty($arrayBillSales))

    									{

    										$tagInsert = $this->$model->insertData($arrayBillSales,'ret_bill_details'); 

    										if($tagInsert)

    										{

    										    

    										    //Sales Incentive

    										    if(isset($billSale['tag'][$key]) && $billSale['tag'][$key]!='')

    										    {

    										        $sales_incetive=$this->$model->get_ret_settings('sales_incentive_green_tag');  //Is incentive is enabled

    										        if($sales_incetive==1)

    										        {

    										            $tag_details=$this->$model->getTagDetails($billSale['tag'][$key],$billSale['est_itm_id'][$key]);

    										            if(!empty($tag_details))

    										            {

    										                

    										            if($tag_details['id_metal']==1)  // Gold

    										            {

    										                $gold_per_gram_amt=$this->$model->get_ret_settings('emp_sales_incentive_gold_perg');      //GOld Per Gram Value

    										                $wallet_amt=$billSale['net'][$key]*$gold_per_gram_amt;

    										            }

    										            else if($tag_details['id_metal']==2) //Silver

    										            {

    										                 $silver_per_gram_amt=$this->$model->get_ret_settings('emp_sales_incentive_silver_perg'); //Silver Per Gram Value

    										                 $wallet_amt=$billSale['net'][$key]*$silver_per_gram_amt;

    										            }

    										            if($wallet_amt>0)

    										            {

    										                $wallet_acc=$this->$model->get_wallet_account($tag_details['id_employee']); // Check Wallet Acc Exists

    										                if($wallet_acc['status'])

    										                {

    										                    $WalletinsData=array(

    										                                    'id_wallet_account'=>$wallet_acc['id_wallet_account'],

    										                                    'transaction_type' =>0, //0-Credit,2-Debit

    										                                    'type'             =>1, //Retail

    										                                    'bill_id'          =>$insId,

    										                                    'ref_no'           =>$billSale['tag'][$key],

    										                                    'value'            =>$wallet_amt,

    										                                    'description'      =>'Green Tag Sales Incentive',

    										                                    'date_transaction' => date("Y-m-d H:i:s"),

    										                                    'id_employee'      =>$this->session->userdata('uid'),

    										                                    'date_add'	       => date("Y-m-d H:i:s"),

    										                                    );

    										                  $this->$model->insertData($WalletinsData,'wallet_transaction'); 

    										                }

    										                else

    										                {

    										                    $wallet_acc_no =  $this->$model->get_wallet_acc_number();

                                                                $walletAcc=array( 

                                                                'idemployee' 	   => $tag_details['id_employee'],

                                                                'id_employee' 	   => $this->session->userdata('uid'),

                                                                'wallet_acc_number'=> $wallet_acc_no,

                                                                'issued_date' 	   => date('y-m-d H:i:s'),

                                                                'remark' 		   => "Credits",

                                                                'active'		   => 1	      

                                                                );

                                                                $id_wallet_acc=$this->$model->insertData($walletAcc,'wallet_account'); 

                                                               // print_r($this->db->last_query());exit;

                                                                if($id_wallet_acc)

                                                                {

                                                                    $WalletinsData=array(

    										                                    'id_wallet_account'=>$id_wallet_acc,

    										                                    'transaction_type' =>0, //0-Credit,2-Debit

    										                                    'type'             =>1, //Retail

    										                                    'bill_id'          =>$insId,

    										                                    'ref_no'           =>$billSale['tag'][$key],

    										                                    'value'            =>$wallet_amt,

    										                                    'description'      =>'Green Tag Sales Incentive',

    										                                    'date_transaction' => date("Y-m-d H:i:s"),

    										                                    'id_employee'      =>$this->session->userdata('uid'),

    										                                    'date_add'	       => date("Y-m-d H:i:s"),

    										                                    );

    										                        $this->$model->insertData($WalletinsData,'wallet_transaction');

                                                                }

    										                }

    										                

    										            }

    										            }

    										        }

    										    }

    										    //Sales Incentive

    										    

    											//Partly sale

                    							$status=$this->$model->get_partial_sale_det($billSale['tag'][$key]);

                    							if($billSale['is_partial'][$key]==1 || ($billSale['itemtype'][$key]==2 && $billSale['tag'][$key]!=''))

                    							{

                    								$tag=$this->$model->get_tag_details($billSale['tag'][$key]);

                    								$blc_net_wt=($tag['tot_nwt']-($tag['tot_sold_nwt']+$billSale['net'][$key]));

                    								$partly_data=array(

                    								'tag_id'		=>$billSale['tag'][$key],

                    								'sold_bill_det_id'=>$tagInsert,

                    								'product'		=>$billSale['product'][$key],

                    								'design'		=>($billSale['design'][$key]!='' ? $billSale['design'][$key]:NULL),

                    								'sold_gross_wt'	=>$billSale['gross'][$key],

                    								'sold_less_wt'	=>($billSale['less'][$key]!='' ? $billSale['less'][$key]:NULL),

                    								'sold_net_wt'	=>$billSale['net'][$key],

                    								'blc_gross_wt'	=>($tag['tot_gwt']-($tag['tot_sold_gwt']+$billSale['gross'][$key])),

                    								'blc_less_wt'	=>($tag['tot_lwt']-($tag['tot_sold_lwt']+$billSale['less'][$key])),

                    								'blc_net_wt'	=> $blc_net_wt,

                    								'created_on'  	=> date("Y-m-d H:i:s"),

                    								'created_by'   	=> $this->session->userdata('uid'),	

                    								'status'   		=>($blc_net_wt==0 ? 0:1),	

                    								);

                    								$this->$model->insertData($partly_data,'ret_partlysold');

                    							}	

    											//Partly sale

    

    										    //stock maintaince

    											if($billSale['is_non_tag'][$key]==1)

    											{

    												$existData=array('id_product'=>$billSale['product'][$key],'id_design'=>$billSale['design'][$key],'id_branch'=>$addData['id_branch']);

    												$isExist = $this->$model->checkNonTagItemExist($existData);

    												if($isExist['status'] == TRUE)

    												{

    

    													$nt_data = array(

    													'id_nontag_item'=>$isExist['id_nontag_item'],

    	  										        'no_of_piece'	=> ($billSale['pcs'][$key]!='' ? $billSale['pcs'][$key]:0),

    													'gross_wt'		=> $billSale['gross'][$key],

    													'net_wt'		=> $billSale['net'][$key],  

    													'less_wt'		=> $billSale['less'][$key],  

    													'updated_by'	=> $this->session->userdata('uid'),

    													'updated_on'	=> date('Y-m-d H:i:s'),

    													);

    													$this->$model->updateNTData($nt_data,'-');

    													

    													$non_tag_data=array(

                            								'from_branch'	=>$addData['id_branch'],

                            								'to_branch'	    =>NULL,

                            								'no_of_piece'   =>($billSale['pcs'][$key]!='' ? $billSale['pcs'][$key]:NULL), 

                            								'less_wt' 		=> ($billSale['less'][$key]!='' ? $billSale['less'][$key]:NULL), 

    										                'net_wt' 		=> $billSale['net'][$key], 

    										                'gross_wt' 		=> $billSale['gross'][$key], 

                            								'product'		=>$billSale['product'][$key],

                            								'design'		=>($billSale['design'][$key]!='' ? $billSale['design'][$key]:NULL),

                            								'date'  	    => $bill_date,

                            								'created_on'  	=> date("Y-m-d H:i:s"),

                            								'created_by'   	=> $this->session->userdata('uid'),	

                            								'status'   		=>1,

                            								'bill_id'       =>$insId

                            								);

                            								$this->$model->insertData($non_tag_data,'ret_nontag_item_log');

    												}

    												

    											}

    											//stock maintaince

    											

    											

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

            	                                        'certification_price'=>($stone['certification_cost']!='' ?$stone['certification_cost']:NULL),

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

    											if($billSale['tag'][$key]!='')

    											{

    												//Update Estimation Items by est_itm_id

    												//$this->$model->updateData(array('purchase_status'=>1,'bil_detail_id'=>$tagInsert),'tag_id',(isset($billSale['tag'][$key])? $billSale['tag'][$key]:''), 'ret_estimation_items');	

    												$this->$model->updateData(array('tag_status'=>1),'tag_id',$billSale['tag'][$key], 'ret_taging');

    												

    												//Update Tag Log status

    												$tag_log=array(

    													'tag_id'	  =>$billSale['tag'][$key],

    													'date'		  =>$bill_date,

    													'status'	  =>1,

    													'from_branch' =>$addData['id_branch'],

    													'to_branch'	  =>NULL,

    													'created_on'  =>date("Y-m-d H:i:s"),

    													'created_by'  =>$this->session->userdata('uid'),

    													);

    													$this->$model->insertData($tag_log,'ret_taging_status_log');

    											}

    											

    											$order_no = (isset($billSale['order_no'][$key]) ? $billSale['order_no'][$key]:'');

    											if(sizeof($order_adv_adj_details)>0)

    											{

    											    foreach($order_adv_adj_details as $order)

    											    {

    											        if($order['order_no']!='')

    											        {

    											            $this->$model->updateData(array('is_adavnce_adjusted'=>1,'adjusted_bill_id'=>$insId,'updated_time'	=> date("Y-m-d H:i:s"),'updated_by'	=> $this->session->userdata('uid')),'bill_adv_id',$order['bill_adv_id'], 'ret_billing_advance');	

    											        }

    											    }

    											}

    										

    											$id_orderdetails=(isset($billSale['id_orderdetails'][$key]) ? ($billSale['id_orderdetails'][$key]!='' ? $billSale['id_orderdetails'][$key]:''):'');

    											if($id_orderdetails!='')

    											{

    												$this->$model->updateData(array('orderstatus'=>5,'delivered_date'=>date("Y-m-d H:i:s")),'id_orderdetails',$billSale['id_orderdetails'][$key], 'customerorderdetails');

    											}

    										}

    									}

    								}

    								

    									$service = $this->$set_model->get_service($serviceID);

            							if($service['serv_sms'] == 1)

            							{

            	        						$cus_details=$this->$model->get_customer($addData['bill_cus_id']);

            	        						if($cus_details['mobile'])

            	        						{

                	        						$sms_data =$this->$sms_model->get_SMS_data($serviceID,$insId);

                	        						$message=$sms_data['message'];

            	        						    $sms=$this->send_sms($sms_data['mobile'],$message,$service['dlt_te_id']);

            	        						    

            	        						}

            							}

            							

            							if($service['serv_whatsapp'] == 1)

            							{

            	        						$cus_details=$this->$model->get_customer($addData['bill_cus_id']);

            	        						if($cus_details['mobile'])

            	        						{

                	        						$sms_data =$this->$sms_model->get_SMS_data($serviceID,$insId);

                	        						$message=$sms_data['message'];

            	        						    $whatsapp=$this->admin_usersms_model->send_whatsApp_message($cus_details['mobile'],$message);

            	        						}

            							}

    							}

    							

    							//Purchase Items

    							if(!empty($billPurchase))

    							{

    								//Update Ref No

    								$ref_no=$this->$model->generateRefNo($addData['id_branch'],'pur_ref_no');

    								$this->$model->updateData(array('pur_ref_no'=>$ref_no),'bill_id',$insId,'ret_billing');

    								//Update Ref No

    								$arrayPurchaseBill = array();

    								foreach($billPurchase['is_est_details'] as $key => $val)

    								{

    									if($billPurchase['is_est_details'][$key] == 1)

    									{

    										$arrayPurchaseBill= array(

    										                'bill_id'                   => $insId, 

    										                'current_branch'            => $addData['id_branch'],

    										                'metal_type'                => $billPurchase['metal_type'][$key], 

    										                'item_type'                 => $billPurchase['itemtype'][$key], 

    										                'esti_old_metal_sale_id'    => $billPurchase['est_old_itm_id'][$key],  

    										                'gross_wt'                  => $billPurchase['gross'][$key], 

    										                'stone_wt'                  => $billPurchase['stone_wt'][$key],

    										                'dust_wt'                   => $billPurchase['dust_wt'][$key],

    										                'net_wt'                    => $billPurchase['net'][$key],

    										                'wast_wt'                   => $billPurchase['wastage_wt'][$key],

    										                'wastage_percent'           => $billPurchase['wastage'][$key], 

    										                'rate'                      => $billPurchase['billamount'][$key], 

    										                'rate_per_grm'              => $billPurchase['rate_per_grm'][$key], 

    										                'old_metal_rate'            =>$this->$model->getOldMetalRate($billPurchase['metal_type'][$key]),

    										                'bill_discount'             => empty($billPurchase['discount'][$key]) ? 0 : $billPurchase['discount'][$key]); 

    									}

    									if(!empty($arrayPurchaseBill)){

    	    								$oldMetal = $this->$model->insertData($arrayPurchaseBill,'ret_bill_old_metal_sale_details');

    	    								if($oldMetal)

    	    								{

    	    								    if($addData['bill_type']==5) 

        										{

            										$arrayAdv = array(

            										'bill_id'           => $insId,

            										'advance_type'      => 2,

            										'old_metal_sale_id' => $oldMetal,

            										//'rate_per_gram'     => $billPurchase['rate_per_grm'][$key],

            										'rate_per_gram'     => $addData['goldrate_22ct'],

            										'advance_weight'    => ($addData['pur_store_as']==2 ? $billPurchase['net'][$key]:0),

            										'advance_amount'    => ($addData['pur_store_as']==1 ? ($billPurchase['net'][$key]*$billPurchase['rate_per_grm'][$key]):0),

            										'received_weight'   => $billPurchase['net'][$key],

            										'store_as'          => $addData['pur_store_as'],

            										'order_no'          => (!empty($addData['filter_order_no']) ? $addData['filter_order_no'] :NULL),

            										'id_customerorder'  => $addData['id_customerorder'],

            										'advance_date'		=> date("Y-m-d H:i:s"),

            										'created_time'		=> date("Y-m-d H:i:s"),

            										'created_by'    	=> $this->session->userdata('uid')

            										);

            										$advInsId = $this->$model->insertData($arrayAdv,'ret_billing_advance');

        										}

    										

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

    									        $this->$model->updateData(array('purchase_status'=>1,'bill_id'=>$insId),'old_metal_sale_id',$billPurchase['est_old_itm_id'][$key], 'ret_estimation_old_metal_sale_details');	

    	    								

    	    								    //Insert into Old Metal Log Table

    									        $old_metal_log=array(

    									                             'old_metal_sale_id'=>$oldMetal,

    									                             'from_branch'      =>NULL,

    									                             'to_branch'        =>$addData['id_branch'],

    									                             'status'           =>1,

    									                             'date'             =>$bill_date,

    									                             'created_on'       =>date("Y-m-d H:i:s"),

    													             'created_by'      =>$this->session->userdata('uid'),

    									                            );

    									       $this->$model->insertData($old_metal_log,'ret_old_metal_stock_log');

    	    								    

    	    								}

    	    							}

    								}

    								

    								$service = $this->$set_model->get_service($serviceID);

        							if($service['serv_sms'] == 1)

        							{

        							        

        	        						$cus_details=$this->$model->get_customer($addData['bill_cus_id']);

        	        						if($cus_details['mobile'])

        	        						{

        	        						$sms_data =$this->$sms_model->get_SMS_data($serviceID,$insId);

        	        						$this->send_sms($sms_data['mobile'],$sms_data['message'],'');

        	        						}

        							}

    							}

    							

    							//Update Credit Status

    							if($addData['bill_type'] == 8)

    		 				    {

    		 				        $credit_pay_amount  = $this->$model->get_credit_collection_details($ref_bill_id);

    		 				        $bill_details       = $this->$model->get_BillAmount($ref_bill_id);

    		 				        if(($bill_details['tot_bill_amount']-$bill_details['tot_amt_received'])==$credit_pay_amount)

    		 				        {

    		 				            $updCredit = array("credit_status" 	=> 1);

    								    $this->$model->updateData($updCredit,'bill_id',$ref_bill_id,'ret_billing');

    		 				        }

    		 				    }

    		 				    //Update Credit Status

    							

    						}

    						if($this->db->trans_status()===TRUE)

    						{

    						   

    							$this->db->trans_commit();

    							

    							 $log_data = array(

                                'id_log'        => $this->session->userdata('id_log'),

                                'event_date'    => date("Y-m-d H:i:s"),

                                'module'        => 'Billing',

                                'operation'     => 'Add',

                                'record'        =>  $insId,  

                                'remark'        => 'Record added successfully'

                                );

                                $this->log_model->log_detail('insert','',$log_data);

    							$return_data=array('status'=>TRUE,'id'=>$insId);

    							 

    							$log_path = 'billing_log/'.$insId.'/';

                                if (!is_dir($log_path)) 

                                {  

                                    mkdir($log_path, 0777, TRUE);

                                } 

    							 $log_path = $log_path.'/post_data.txt';

    							 $post_data=array(

    							                    'general_details'=>$addData,

    							                    'sales_ddetails'=>$billSale,

    							                    'purchase_details'=>$billPurchase

    							                );

    							$log_detail=json_encode($post_data);

    							file_put_contents($log_path,$log_detail,FILE_APPEND | LOCK_EX);

    							 

    							$this->session->set_flashdata('chit_alert',array('message'=>'Billing added successfully','class'=>'success','title'=>'Add Billing'));

    						}

    						else

    						{

    						    

    							$this->db->trans_rollback();

    							$return_data=array('status'=>FALSE,'id'=>'');

    							echo $this->db->_error_message()."<br/>";					   

    							echo $this->db->last_query();exit;						 	

    							$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Add Billing'));

    						}

    						echo json_encode($return_data); 

    					}else{

    						$return_data=array('status'=>FALSE,'id'=>'');

    						$this->session->set_flashdata('chit_alert',array('message'=>'Kindly update Day closing data to add Bill','class'=>'danger','title'=>'Add Billing')); 

    						echo json_encode($return_data);

    					}

		            }

		            else

		            {

		                $return_data=array('status'=>FALSE,'id'=>'');

    					$this->session->set_flashdata('chit_alert',array('message'=>'Kindly Check The Tag or Estimation No','class'=>'danger','title'=>'Add Billing')); 

    					echo json_encode($return_data);

		            }

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

			case 'cancell':

					$upd_data = array(

									"bill_status"	=> 2,

									'updated_time'	=> date("Y-m-d H:i:s"),

									'cancelled_date'=> date("Y-m-d H:i:s"),

									'updated_by'	=> $this->session->userdata('uid')

								);

					$this->db->trans_begin();

					$status=$this->$model->updateData($upd_data,'bill_id',$id, 'ret_billing');

					if($status)

					{

					    $bill_detail=$this->$model->get_bill_detail($id);

					    foreach($bill_detail as $bill)

					    {

					        

					        //Estimation

					        $updData=array('purchase_status'=>0,'bil_detail_id'=>NULL);

					        $this->$model->updateData($updData,'bil_detail_id',$bill['bill_det_id'], 'ret_estimation_items');

					        

					        //Estimation Old Items

					         $oldUpdata=array('purchase_status'=>0,'bill_id'=>NULL);

					         $this->$model->updateData($oldUpdata,'bill_id',$id, 'ret_estimation_old_metal_sale_details');

					         

					         

					        if($bill['tag_id']!='')

					        {

					           $this->$model->updateData(array('tag_status'=>6,'updated_time'=>date("Y-m-d H:i:s"),'updated_by'=>$this->session->userdata('uid')),'tag_id',$bill['tag_id'], 'ret_taging');

					           

					           $log_data=array(

								'tag_id'	  =>$bill['tag_id'],

								'date'		  =>date("Y-m-d H:i:s"),

								'status'	  =>6,

								'from_branch' =>NULL,

								'to_branch'	  =>$bill['id_branch'],

								'created_on'  =>date("Y-m-d H:i:s"),

								'created_by'  =>$this->session->userdata('uid'),

								);

								$this->$model->insertData($log_data,'ret_taging_status_log'); //Update Tag lot status

					        }

					        //stock maintaince

					        $existData=array('id_product'=>$bill['product_id'],'id_design'=>$bill['design_id'],'id_branch'=>$bill['id_branch']);

							$isExist = $this->$model->checkNonTagItemExist($existData);

							if($isExist['status'] == TRUE)

							{

								$nt_data = array(

								'id_nontag_item'=> $isExist['id_nontag_item'],

							    'no_of_piece'   => $bill['no_of_piece'],

								'gross_wt'		=> $bill['gross_wt'],

								'net_wt'		=> $bill['net_wt'],  

								'updated_by'	=> $this->session->userdata('uid'),

								'updated_on'	=> date('Y-m-d H:i:s'),

								);

								$this->$model->updateNTData($nt_data,'+');

							}

					        //stock maintaince

					    }

					    

					}

					if($this->db->trans_status()=== TRUE)

					{

						$this->db->trans_commit();

						$this->session->set_flashdata('chit_alert',array('message'=>'Bill No.'.$billno.' cancelled successfully','class'=>'success','title'=>'Cancell Bill'));

					}

					else

					{

						$this->db->trans_rollback();						 	

						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Cancell Bill'));

 					}

					redirect('admin_ret_billing/billing/list');

			default: 

			           	$list = $this->$model->ajax_getBillingList($_POST);	 

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

			$data = $this->$model->createNewCustomer($_POST['cusName'], $_POST['cusMobile'], $_POST['cusBranch'],$_POST['id_village'],$_POST['cus_type'],$_POST['gst_no']);

			echo json_encode($data);

		}else{

			echo json_encode(array("success" => FALSE, "response" => array(), "message" => "Please fill all the required fields"));

		}

	}

	public function getEstimationDetails(){

		$model = "ret_billing_model";

		if((!empty($_POST['estId']) || !empty($_POST['order_no'])) && !empty($_POST['billType'])){

			$data = $this->$model->getEstimationDetails($_POST['estId'], $_POST['billType'], $_POST['id_branch'], $_POST['order_no'],$_POST['fin_year']);

			if(sizeof($data['item_details'])>0 || sizeof($data['old_matel_details'])>0 || sizeof($data['order_details'])>0  )

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

		$data = $this->$model->getBillData($_POST['billNo'], $_POST['billType'], $_POST['id_branch'],$_POST['fin_year']);

		if(!empty($data)){

		echo json_encode(array('success' => TRUE, 'message' => 'Records reterived successfully.', 'responsedata' => $data));

		}else{

		echo json_encode(array('success' => FALSE, 'message' => 'No record found for given details'));

		}

		}else{

		echo json_encode(array("success" => FALSE, "response" => array(), "message" => "Please fill all the required fields"));

		}

	}

	

	public function get_return_Bill_details()

	{

		$model = "ret_billing_model";

		if(!empty($_POST['billNo']) && !empty($_POST['billType'])){

		$data = $this->$model->getreturnBillData($_POST['billNo'], $_POST['billType'], $_POST['id_branch']);

		if(!empty($data)){

		echo json_encode(array('success' => TRUE, 'message' => 'Records reterived successfully.', 'responsedata' => $data));

		}else{

		echo json_encode(array('success' => FALSE, 'message' => 'No record found for given details'));

		}

		}else{

		echo json_encode(array("success" => FALSE, "response" => array(), "message" => "Please fill all the required fields"));

		}

	}

	

	public function getBillingDetails()

	{

		$model = "ret_billing_model";

		if(!empty($_POST['from_date']) && !empty($_POST['to_date'])){

		$data = $this->$model->getBilling_details($_POST['from_date'], $_POST['to_date'], $_POST['id_branch'],$_POST['bill_cus_id'],$_POST['bill_type']);

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

		$data = $this->$model->getCreditBillDetails($_POST['billNo'], $_POST['billType'], $_POST['id_branch'],$_POST['fin_year']);

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

	function send_sms($mobile,$message,$dlt_te_id)

	{

		if($this->config->item('sms_gateway') == '1'){

		    $this->sms_model->sendSMS_MSG91($mobile,$message,'',$dlt_te_id);		

		}

		elseif($this->config->item('sms_gateway') == '2'){

	        $this->sms_model->sendSMS_Nettyfish($mobile,$message,'trans');	

		}

	}

	function billing_invoice($id)

	{

		$model="ret_billing_model";

		$data['billing'] = $this->$model->getBillingDetails($id);

		$data['billing']['app_qrcode']=$this->config->item('base_url')."mobile_app_qrcode/skj_app_qrcode.png";

		$data['billing']['playstore']=$this->config->item('base_url')."mobile_app_qrcode/playstore.png";

		$data['payment'] = $this->$model->getPaymentDetails($id);

		$data['metal_rate'] = $this->$model->getBillingMetalrate($data['billing']['id_branch'],$data['billing']['bill_date']);

		$data['est_other_item'] = $this->$model->getOtherEstimateItemsDetails($id,$data['billing']['bill_type']);

		$data['comp_details']=$this->$model->getCompanyDetails($data['billing']['id_branch']);

		$data['settings']		= $this->$model->get_retSettings();

		//echo "<pre>"; print_r($data);exit;

	    $print_taken=$data['billing']['print_taken'];

		if($print_taken==0)

		{

			$print_taken++;

			$this->$model->updateData(array('print_taken'=>$print_taken),'bill_id',$id,'ret_billing');

		}else

		{

			$this->$model->insertData(array('bill_id'=>$id,'id_employee'=>$this->session->userdata('uid'),'print_date'=>date("Y-m-d H:i:s")),'ret_bill_duplicate_copy');

		}

	    $this->load->helper(array('dompdf', 'file'));

		$this->load->helper(array('dompdf', 'file'));

        $dompdf = new DOMPDF();

		$html = $this->load->view('billing/print/receipt_billing', $data,true);

	    $dompdf->load_html($html);

		$dompdf->set_paper("a4", "portriat" );

		$dompdf->render();

		$dompdf->stream("Receipt.pdf",array('Attachment'=>0));

	}

	

	//issue and receipt

	 public function issue($type="", $id="", $billno="")

	{

		$model = "ret_billing_model";

		switch($type)

		{

			case 'add':

			$data['settings']		= $this->$model->get_retSettings();

			$data['main_content'] = "billing/issueReceipt/issueForm" ;

			$this->load->view('layout/template', $data);

			break;



			case 'list':

				$data['main_content'] = "billing/issueReceipt/issueList" ;

				$this->load->view('layout/template', $data);

			break;



			case 'save':

				$addData=$_POST['issue'];

				$payment=$_POST['payment'];

				$card_pay_details	= json_decode($payment['card_pay'],true);

				$cheque_details	    = json_decode($payment['chq_pay'],true); 

				$net_banking_details = json_decode($payment['net_bank_pay'],true); 

				//echo "<pre>"; print_r($addData);exit;

				

				$bill_no = $this->$model->bill_no_generate($addData['id_branch']);

			    $dCData = $this->admin_settings_model->getBranchDayClosingData($addData['id_branch']);

				$bill_date = ($dCData['entry_date'] == date("Y-m-d") ? date("Y-m-d H:i:s") : $dCData['entry_date']);

				$fin_year  = $this->$model->get_FinancialYear();

				

				$insData=array(

				    'bill_no'	        =>$bill_no,

				    'fin_year_code'     => $fin_year['fin_year_code'],

				    'bill_date'         =>$bill_date,

					'type'              =>1,

					'id_branch'         => $addData['id_branch'],

					'mobile'            =>$addData['mobile'],

					'name'              =>$addData['barrower_name'],

					'amount'            =>$addData['amount'],

					'issue_to'          =>$addData['issue_to'],

					'issue_type'        =>$addData['issue_type'],

					'id_customer'       =>($addData['id_customer']!='' ? $addData['id_customer'] :NULL),

				    'id_employee'       =>$this->session->userdata('uid'),

					'id_acc_head'       =>($addData['id_acc_head']!='' ? $addData['id_acc_head'] :NULL),

					'narration'	        =>($addData['narration']!='' ?$addData['narration'] :NULL),

					'created_by'        =>$this->session->userdata('uid'),

					'counter_id'        => ($this->session->userdata('counter_id')!='' ? $this->session->userdata('counter_id'):NULL),

					'created_on'        => date("Y-m-d H:i:s"),

					);

				$this->db->trans_begin();

				$updData=$_POST['payment'];

				 //echo "<pre>"; print_r($updData);exit;

	 			$insId = $this->$model->insertData($insData,'ret_issue_receipt');

	 			//print_r($this->db->last_query());exit;

	 			if($insId)

	 			{

	 				$updData=$_POST['payment'];

	 				$pay_data=array(

	 					'id_issue_rcpt'	=>$insId,

	 					'payment_amount'=>$updData['cash_payment'],

	 					'payment_mode'	=>'Cash',

	 					'payment_status'=>1,

	 					'type'			=>2,

	 					'payment_type'	=>'Manual',

						'payment_date'	=>date("Y-m-d H:i:s"),

						'created_time'	=> date("Y-m-d H:i:s"),

						'created_by'	=> $this->session->userdata('uid')

	 				);

	 				$this->$model->insertData($pay_data,'ret_issue_rcpt_payment');



	 				if(sizeof($card_pay_details)>0)

	 					{

	 						foreach($card_pay_details as $card_pay)

		 					{

								$arrayCardPay[]=array(

								'id_issue_rcpt'	=>$insId,

								'payment_amount'=>$card_pay['card_amt'],

								'payment_status'=>1,

								'type'			=>2,

								'payment_date'	=>date("Y-m-d H:i:s"),

								'payment_mode'	=>($card_pay['card_type']==1 ?'CC':'DC'),

								'card_no'		=>($card_pay['card_no']!='' ? $card_pay['card_no']:NULL),

								'created_time'	=> date("Y-m-d H:i:s"),

								'created_by'	=> $this->session->userdata('uid')

								);

	 						}

		 						if(!empty($arrayCardPay)){

									$cardPayInsert = $this->$model->insertBatchData($arrayCardPay,'ret_issue_rcpt_payment'); 

								}

	 					}

	 					if(sizeof($cheque_details)>0)

	 					{

	 						foreach($cheque_details as $chq_pay)

		 					{

								$arraychqPay[]=array(

									'id_issue_rcpt'	=>$insId,

									'payment_amount'=>$chq_pay['payment_amount'],

									'payment_status'=>1,

									'type'			=>2,

									'payment_date'	=>date("Y-m-d H:i:s"),

									'cheque_date'	=>date("Y-m-d H:i:s"),

									'payment_mode'	=>'CHQ',

									'cheque_no'		=>($chq_pay['cheque_no']!='' ? $chq_pay['cheque_no']:NULL),

									'bank_name'		=>($chq_pay['bank_name']!='' ? $chq_pay['bank_name']:NULL),

									'bank_branch'	=>($chq_pay['bank_branch']!='' ? $chq_pay['bank_branch']:NULL),

									'created_time'	=> date("Y-m-d H:i:s"),

									'created_by'	=> $this->session->userdata('uid')

								);

	 						}

		 						if(!empty($arraychqPay)){

									$chqPayInsert = $this->$model->insertBatchData($arraychqPay,'ret_issue_rcpt_payment'); 

								}

	 					}

	 					if(sizeof($net_banking_details)>0)

	 					{

	 						foreach($net_banking_details as $nb_pay)

		 					{

								$arrayNBPay[]=array(

									'id_issue_rcpt'	=>$insId,

									'payment_amount'=>$nb_pay['amount'],

									'payment_status'=>1,

									'type'			=>2,

									'payment_date'	=>date("Y-m-d H:i:s"),

									'payment_mode'	=>'NB',

									'payment_ref_number'=>($nb_pay['ref_no']!='' ? $nb_pay['ref_no']:NULL),

									'NB_type'       =>($nb_pay['nb_type']!='' ? $nb_pay['nb_type']:NULL),

									'created_time'	=> date("Y-m-d H:i:s"),

									'created_by'	=> $this->session->userdata('uid')

								);

	 						}

		 						if(!empty($arrayNBPay)){

									$NbPayInsert = $this->$model->insertBatchData($arrayNBPay,'ret_issue_rcpt_payment'); 

								}

	 					}

	 					

	 					if($addData['issue_type']==3)

	 					{

	 					    $insWallet=array(

    						'id_ret_wallet'		=>$addData['id_ret_wallet'],

    						'id_issue_receipt'	=>$insId,

    						'amount'			=>$addData['amount'],

    						'transaction_type'	=>1,

    						'created_by' 		=>$this->session->userdata('uid'),

    						'created_on' 		=> date("Y-m-d H:i:s"),

    						'remarks'	 		=>'Advace Refund Amount'

    						 );

    						$this->$model->insertData($insWallet,'ret_wallet_transcation');

	 					}



	 			}

				if($this->db->trans_status()===TRUE)

					{

						$this->db->trans_commit();

						$this->session->set_flashdata('chit_alert',array('message'=>'Issue Given successfully','class'=>'success','title'=>'Add Issue'));

					}

					else

					{

						$this->db->trans_rollback();

						echo $this->db->_error_message()."<br/>";					   

						echo $this->db->last_query();exit;						 	

						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Add Issue'));

					}

					redirect('admin_ret_billing/issue/list');	

			break;

			case 'issue_print':

					$model="ret_billing_model";

					$data['comp_details']=$this->$model->getCompanyDetails(1);

					$data['issue']=$this->$model->get_issue_details($id);

					$data['payment'] = $this->$model->get_receipt_payment($id);

					//echo "<pre>"; print_r($data);exit;

					$this->load->helper(array('dompdf', 'file'));

					$dompdf = new DOMPDF();

					$html = $this->load->view('billing/issueReceipt/print/issue', $data,true);

					$dompdf->load_html($html);

					$dompdf->set_paper("a4", "portriat" );

					$dompdf->render();

					$dompdf->stream("Receipt.pdf",array('Attachment'=>0));

			break;

			default: 

					  	$list = $this->$model->ajax_getIssuetist();	 

					  	$access = $this->admin_settings_model->get_access('admin_ret_billing/receipt/list');

				        $data = array(

				        					'list'  => $list,

											'access'=> $access

				        				);  

						echo json_encode($data);	



		}

	}



	public function receipt($type="", $id="", $billno="")

	{

		$model = "ret_billing_model";

		switch($type)

		{

			case 'add':

			$data['settings']		= $this->$model->get_retSettings();

			$data['main_content'] = "billing/issueReceipt/receiptForm" ;

			$this->load->view('layout/template', $data);

			break;



			case 'list':

				$data['main_content'] = "billing/issueReceipt/receiptList" ;

				$this->load->view('layout/template', $data);

			break;





			case 'credit_bill':

				$searchTxt=$this->input->post('searchTxt');

				$id_branch=$this->input->post('id_branch');

				$data=$this->$model->getCreditBill($searchTxt,$id_branch);

				echo json_encode($data);

			break;



			case 'save':

				$addData=$_POST['receipt'];

				$payment=$_POST['payment'];

				$card_pay_details	= json_decode($payment['card_pay'],true);

				$cheque_details	    = json_decode($payment['chq_pay'],true); 

				$net_banking_details = json_decode($payment['net_bank_pay'],true); 

				//echo "<pre>"; print_r($_POST);exit;

				$amount=0;

				$weight=0;

				$metal_rate=$this->$model->get_branchwise_rate($addData['id_branch']);

				$bill_no = $this->$model->bill_no_generate($addData['id_branch']);

			    $dCData = $this->admin_settings_model->getBranchDayClosingData($addData['id_branch']);

				$bill_date = ($dCData['entry_date'] == date("Y-m-d") ? date("Y-m-d H:i:s") : $dCData['entry_date']);

				$fin_year       = $this->$model->get_FinancialYear();

				if($addData['amount']>0)

				{

					if($addData['store_receipt_as']==1)

					{

						$amount=$addData['amount'];

					}else if($addData['store_receipt_as']==2)

					{

						if($addData['rate_calc']==1)

						{

							$weight=$addData['amount']/$metal_rate['goldrate_22ct'];

						}else{

							$weight=$addData['amount']/$metal_rate['silverrate_1gm'];	

						}

					}

				}



				if($addData['weight']>0)

				{

					if($addData['store_receipt_as']==2)

					{

						$weight=$addData['weight'];

					}

					else if($addData['store_receipt_as']==1)

					{

						if($addData['rate_calc']==1)

						{

							$amount=$addData['weight']*$metal_rate['goldrate_22ct'];

						}else{

							$amount=$addData['weight']*$metal_rate['silverrate_1gm'];	

						}

					}

				}



				$insData=array(

					'type'			=>2,

					'amount'		=>$amount,

					'weight'		=>$weight,

					'bill_no'		=>$bill_no,

					'receipt_type'	=>$addData['receipt_type'],

					'id_branch'		=> $addData['id_branch'],

					'id_customer'	=>($addData['id_customer']!='' ? $addData['id_customer']:NULL),

					'rate_per_gram'	=>($addData['rate_calc']==1 ?$metal_rate['goldrate_22ct']:$metal_rate['silverrate_1gm']),

					'rate_calc'		=>$addData['rate_calc'],

					'receipt_as'	=>$addData['receipt_as'],

					'store_receipt_as'=>$addData['store_receipt_as'],

					'pan_no'		=> (!empty($addData['pan_no']) ? $addData['pan_no'] : NULL ),

					'narration'	 	=>($addData['narration']!='' ?$addData['narration'] :NULL),

					'created_by' 	=>$this->session->userdata('uid'),

					'counter_id'    => ($this->session->userdata('counter_id')!='' ? $this->session->userdata('counter_id'):NULL),

					'created_on' 	=> date("Y-m-d H:i:s"),

					'bill_date' 	=> $bill_date,

					'fin_year_code' => $fin_year['fin_year_code'],

				);

				//echo "<pre>"; print_r($insData);exit;

				$this->db->trans_begin();

				$insId = $this->$model->insertData($insData,'ret_issue_receipt');

				//print_r($this->db->last_query());exit;

				if($insId)

	 			{



	 			//Update Credit status

	 			if($addData['receipt_type']==1 && ($addData['due_amount']==($addData['paid_amount']+$payment['tot_amt_received'])))

 				{

 					$this->$model->updateData(array('is_collect'=>1),'id_issue_receipt',$addData['receipt_for'],'ret_issue_receipt');

 				}

	 			//Update Credit status



 				//Pan Images

	 					$p_ImgData = json_decode($addData['pan_img']);  

						if(sizeof($p_ImgData) > 0){

							foreach($p_ImgData as $precious){

								$imgFile = $this->base64ToFile($precious->src);

								$_FILES['pan_img'][] = $imgFile;

							}

						}

	 					if(isset($_FILES['pan_img']))

						{ 

							$pan_imgs = "";

							$folder =  self::IMG_PATH."billing/IssueAndReceipt/".$insId;

							if (!is_dir($folder)) {

								mkdir($folder, 0777, TRUE);

							}

							foreach($_FILES['pan_img'] as $file_key => $file_val){

							if($file_val['tmp_name'])

							{

							// unlink($folder."/".$product['image']); 

								$img_name =  "P_". mt_rand(120,1230).".jpg";

								$path = $folder."/".$img_name; 

								$result = $this->upload_img('image',$path,$file_val['tmp_name']);

								if($result){

								$pan_imgs = strlen($pan_imgs) > 0 ? $pan_imgs."#".$img_name : $img_name;

								}

								}

							}

							$this->$model->updateData(array('pan_image'=>$pan_imgs),'id_issue_receipt',$insId,'ret_issue_receipt');

						}

				//pan images



 				//Insert and Update ret wallet



	 		if($addData['receipt_type']==2 && $addData['id_customer']!='')

	 			{

	 				$wallet=$this->$model->get_retWallet_details($addData['id_customer']);

	 				if($wallet['status'])

	 				{

						$insWallet=array(

						'id_ret_wallet'		=>$wallet['id_ret_wallet'],

						'id_issue_receipt'	=>$insId,

						'amount'			=>$amount,

						'weight'			=>$weight,

						'transaction_type'	=>0,

						'created_by' 		=>$this->session->userdata('uid'),

						'created_on' 		=> date("Y-m-d H:i:s"),

						'remarks'	 		=>'Billing Advace Amount'

						 );

						$this->$model->insertData($insWallet,'ret_wallet_transcation');

						//echo "<pre>"; print_r($metal_rate);exit;

	 				}

	 				else

	 				{

	 				   $wallet_acc=array('id_customer'=>$addData['id_customer'],

	 				   	'created_by'=>$this->session->userdata('uid'),

						'created_time' => date("Y-m-d H:i:s"));

 					   $insWalletAcc= $this->$model->insertData($wallet_acc,'ret_wallet');

 					  	

 					   if($insWalletAcc)

 					   {

	 					   	 $insWallet=array(

	 					   	 'id_ret_wallet'	=>$insWalletAcc,

							'id_issue_receipt'	=>$insId,

							'amount'			=>$amount,

							'weight'			=>$weight,

							'transaction_type'	=>0,

							'created_by' 		=>$this->session->userdata('uid'),

							'created_on' 		=> date("Y-m-d H:i:s"),

							'remarks'	 		=>'Billing Advace Amount'

							 );

							$this->$model->insertData($insWallet,'ret_wallet_transcation');

 					   }

 					   

	 				}

	 			}

	 			//print_r($this->db->last_query());exit;

	 			//insert and update ret wallet

	 			$updData=$_POST['payment'];

		 			if(sizeof($updData['cash_payment'])>0)

		 			{

		 				$pay_data=array(

		 					'id_issue_rcpt'	=>$insId,

		 					'payment_amount'=>$updData['cash_payment'],

		 					'payment_mode'	=>'Cash',

		 					'payment_status'=>1,

		 					'type'			=>1,

		 					'payment_type'	=>'Manual',

							'payment_date'	=>date("Y-m-d H:i:s"),

							'created_time'	=> date("Y-m-d H:i:s"),

							'created_by'	=> $this->session->userdata('uid')

		 				);

		 				$this->$model->insertData($pay_data,'ret_issue_rcpt_payment');

		 			}

	 				

	 				if(sizeof($card_pay_details)>0)

 					{

 						foreach($card_pay_details as $card_pay)

	 					{

							$arrayCardPay[]=array(

								'id_issue_rcpt'		=>$insId,

								'payment_amount'=>$card_pay['card_amt'],

								'payment_status'=>1,

								'payment_date'		=>date("Y-m-d H:i:s"),

								'payment_mode'	=>($card_pay['card_type']==1 ?'CC':'DC'),

								'card_no'		=>($card_pay['card_no']!='' ? $card_pay['card_no']:NULL),

								'created_time'	=> date("Y-m-d H:i:s"),

								'created_by'	=> $this->session->userdata('uid')

							);

 						}

	 						if(!empty($arrayCardPay)){

								$cardPayInsert = $this->$model->insertBatchData($arrayCardPay,'ret_issue_rcpt_payment'); 

							}

 					}

 					if(sizeof($cheque_details)>0)

 					{

 						foreach($cheque_details as $chq_pay)

	 					{

							$arraychqPay[]=array(

								'id_issue_rcpt'		=>$insId,

								'payment_amount'=>$chq_pay['payment_amount'],

								'payment_status'=>1,

								'payment_date'		=>date("Y-m-d H:i:s"),

								'cheque_date'		=>date("Y-m-d H:i:s"),

								'payment_mode'	=>'CHQ',

								'cheque_no'		=>($chq_pay['cheque_no']!='' ? $chq_pay['cheque_no']:NULL),

								'bank_name'		=>($chq_pay['bank_name']!='' ? $chq_pay['bank_name']:NULL),

								'bank_branch'	=>($chq_pay['bank_branch']!='' ? $chq_pay['bank_branch']:NULL),

								'created_time'	=> date("Y-m-d H:i:s"),

								'created_by'	=> $this->session->userdata('uid')

							);

 						}

	 						if(!empty($arraychqPay)){

								$chqPayInsert = $this->$model->insertBatchData($arraychqPay,'ret_issue_rcpt_payment'); 

							}

 					}

 					if(sizeof($net_banking_details)>0)

 					{

 						foreach($net_banking_details as $nb_pay)

	 					{

							$arrayNBPay[]=array(

								'id_issue_rcpt'		=>$insId,

								'payment_amount'=>$nb_pay['amount'],

								'payment_status'=>1,

								'payment_date'		=>date("Y-m-d H:i:s"),

								'payment_mode'	=>'NB',

								'payment_ref_number'=>($nb_pay['ref_no']!='' ? $nb_pay['ref_no']:NULL),

								'NB_type'       =>($nb_pay['nb_type']!='' ? $nb_pay['nb_type']:NULL),

								'created_time'	=> date("Y-m-d H:i:s"),

								'created_by'	=> $this->session->userdata('uid')

							);

 						}

	 						if(!empty($arrayNBPay)){

								$NbPayInsert = $this->$model->insertBatchData($arrayNBPay,'ret_issue_rcpt_payment'); 

								//print_r($this->db->last_query());exit;

							}

 					}



	 				$billPurchase = (isset($_POST['purchase']) ? $_POST['purchase']:'');

					//echo "<pre>"; print_r($billPurchase);exit;

						if(!empty($billPurchase)){

							$arrayPurchaseBill = array();

							foreach($billPurchase['esti_detail_id'] as $key => $val)

							{

								$arrayPurchaseBill= array(

								'id_issue_receipt' => $insId, 

								'purpose' => $billPurchase['purpose'][$key], 

								'metal_type' => $billPurchase['id_metal'][$key], 

								//'item_type' => $billPurchase['item_type'][$key], 

								'item_type' => 1, 

								'esti_detail_id' => $billPurchase['esti_detail_id'][$key], 

								'gross_wt' => $billPurchase['gross_wt'][$key], 

								'stone_wt' => $billPurchase['stone_wt'][$key],

								'dust_wt' => $billPurchase['dust_wt'][$key],

								'net_wt' => $billPurchase['net_wt'][$key],

								'wastage_percent' => $billPurchase['wastage_percent'][$key], 

								'wast_wt' => $billPurchase['wastage_wt'][$key], 

								'rate' => $billPurchase['amount'][$key], 

								'rate_per_grm' => $billPurchase['rate_per_gram'][$key]); 

								if(!empty($arrayPurchaseBill))

								{

    								$oldMetal = $this->$model->insertData($arrayPurchaseBill,'ret_receipt_wgt_detail');

    								if($oldMetal)

    								{

    								    $oldUpdata=array('purchase_status'=>1,'bill_id'=>NULL);

					                    $this->$model->updateData($oldUpdata,'old_metal_sale_id',$billPurchase['esti_detail_id'][$key], 'ret_estimation_old_metal_sale_details');

    								}

    							}

							}

						}



	 			}

				if($this->db->trans_status()===TRUE)

					{

						$this->db->trans_commit();

						$return_data=array('status'=>TRUE,'id'=>$insId);

						$this->session->set_flashdata('chit_alert',array('message'=>'Receipt  successfully','class'=>'success','title'=>'Add Receipt'));

					}

					else

					{

					    

						$this->db->trans_rollback();

						$return_data=array('status'=>FALSE,'id'=>'');

						echo $this->db->_error_message()."<br/>";					   

						echo $this->db->last_query();exit;						 	

						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Add Receipt'));

					}

					echo json_encode($return_data);

					//redirect('admin_ret_billing/receipt/list');

			break;

			case 'receipt_print':

					$model="ret_billing_model";

					$data['comp_details']=$this->$model->getCompanyDetails(1);

					$data['issue']=$this->$model->get_receipt_details($id);

					$data['payment'] = $this->$model->get_receipt_payment($id);

					$data['metal_rate']=$this->$model->get_branchwise_rate($data['issue']['id_branch']);

					

					//echo "<pre>"; print_r($data);exit;

					$this->load->helper(array('dompdf', 'file'));

					$dompdf = new DOMPDF();

					$html = $this->load->view('billing/issueReceipt/print/issue', $data,true);

					$dompdf->load_html($html);

					$dompdf->set_paper("a4", "portriat" );

					$dompdf->render();

					$dompdf->stream("Receipt.pdf",array('Attachment'=>0));

			break;

			default: 

					  	$list = $this->$model->ajax_getReceiptlist();	 

					  	$access = $this->admin_settings_model->get_access('admin_ret_billing/receipt/list');

				        $data = array(

				        					'list'  => $list,

											'access'=> $access

				        				);  

						echo json_encode($data);	

		}

	}



	public function get_account_head()

	{

		$model= "ret_billing_model";

		$data =$this->$model->get_account_head();

		echo json_encode($data);

 	}



 	public function get_borrower()

 	{

 		$model= "ret_billing_model";

 		$id_branch=$this->input->post('id_branch');

 		$issue_to=$this->input->post('issue_to');

 		$SearchTxt=$this->input->post('searchTxt');

		$data =$this->$model->get_borrower_details($SearchTxt,$id_branch,$issue_to);

		echo json_encode($data);

 	}

	//issue and receipt

	

	function cancel_bill()

	{

		$model= "ret_billing_model";

		$remarks=$this->input->post('remarks');

 		$bill_id=$this->input->post('bill_id');



 		$upd_data = array(

						"bill_status"	=> 2,

						'updated_time'	=> date("Y-m-d H:i:s"),

						'cancelled_date'=> date("Y-m-d H:i:s"),

						'cancel_reason'=>$remarks,

						'updated_by'	=> $this->session->userdata('uid')

						);

					$this->db->trans_begin();

					$status=$this->$model->updateData($upd_data,'bill_id',$bill_id, 'ret_billing');



					if($status)

					{

					    $bill_detail=$this->$model->get_bill_detail($bill_id);

					    foreach($bill_detail as $bill)

					    {

					        

					         $dCData = $this->admin_settings_model->getBranchDayClosingData($bill['id_branch']);

				             $bill_date = ($dCData['entry_date'] == date("Y-m-d") ? date("Y-m-d H:i:s") : $dCData['entry_date']);

				             

					        $updData=array('purchase_status'=>0,'bil_detail_id'=>NULL);

					        $this->$model->updateData($updData,'bil_detail_id',$bill['bill_det_id'], 'ret_estimation_items');

					        //print_r($this->db->last_query());exit;

					        if($bill['tag_id']!='')

					        {

					           $this->$model->updateData(array('tag_status'=>0,'updated_time'=>date("Y-m-d H:i:s"),'updated_by'=>$this->session->userdata('uid')),'tag_id',$bill['tag_id'], 'ret_taging');

					           

					           $log_data=array(

								'tag_id'	  =>$bill['tag_id'],

								'date'		  =>$bill_date,

								'status'	  =>6,

								'from_branch' =>$bill['id_branch'],

								'to_branch'	  =>NULL,

								'created_on'  =>date("Y-m-d H:i:s"),

								'created_by'  =>$this->session->userdata('uid'),

								);

								$this->$model->insertData($log_data,'ret_taging_status_log'); //Update Tag lot status

					        }

					        //stock maintaince

					        $existData=array('id_product'=>$bill['product_id'],'id_design'=>$bill['design_id'],'id_branch'=>$bill['id_branch']);

							$isExist = $this->$model->checkNonTagItemExist($existData);

							if($isExist['status'] == TRUE)

							{

								$nt_data = array(

								'id_nontag_item'=> $isExist['id_nontag_item'],

							    'no_of_piece'   => ($bill['no_of_piece']!=''  && $bill['no_of_piece']!=null? $bill['no_of_piece']:0),

								'gross_wt'		=> $bill['gross_wt'],

								'net_wt'		=> $bill['net_wt'],  

								'updated_by'	=> $this->session->userdata('uid'),

								'updated_on'	=> date('Y-m-d H:i:s'),

								);

								$this->$model->updateNTData($nt_data,'+');

								

								$non_tag_data=array(

                                'from_branch'	=> NULL,

                                'to_branch'	    => $bill['id_branch'],

                                'no_of_piece'   => $bill['no_of_piece'], 

                                'net_wt' 		=> $bill['net_wt'], 

                                'gross_wt' 		=> $bill['gross_wt'], 

                                'product'		=> $bill['product_id'],

                                'design'		=> $bill['design_id'],

                                'date'  	    => $bill_date,

                                'created_on'  	=> date("Y-m-d H:i:s"),

                                'created_by'   	=> $this->session->userdata('uid'),	

                                'status'   		=>6,

                                'bill_id'       =>$bill_id

                                );

                                $this->$model->insertData($non_tag_data,'ret_nontag_item_log');

							}

					        //stock maintaince

					    }

					    $oldUpdata=array('purchase_status'=>0,'bill_id'=>NULL);

					    $this->$model->updateData($oldUpdata,'bill_id',$bill_id, 'ret_estimation_old_metal_sale_details');

					    

					    

					    $ret_bill_details=$this->$model->ret_bill_return_details($bill_id);

					    foreach($ret_bill_details as $items)

					    {

					        $retUpdata=array('status'=>1);

					        $this->$model->updateData($retUpdata,'bill_det_id',$items['ret_bill_det_id'], 'ret_bill_details');

					    }

					    

					    //gift voucher

					       $this->$model->get_gift_issue_details($bill_id); //Issued Voucher Cancel

					       $this->$model->get_redeem_details($bill_id); //Redeemed Voucher Cancel

					   //gift voucher

					   

					   //Chit Utilized Revert

					   $chit_details=$this->$model->getChitUtilized($bill_id);

					   foreach($chit_details as $chit)

					    {

					        $chitUpdData=array('is_utilized'=>0,'utilized_type'=>NULL);

					        $this->$model->updateData($chitUpdData,'id_scheme_account',$chit['scheme_account_id'], 'scheme_account');

					    }

					    

					     //Wallet Debit Transcation

					    $tag_Details=$this->$model->getWalletTransDetails($bill_id);

					    //print_r($this->db->last_query());exit;

					    foreach($tag_Details as $items)

					    {

                            $WalletinsData=array(

                            'id_wallet_account'=>$items['id_wallet_account'],

                            'transaction_type' =>1,

                            'type'             =>1,    

                            'bill_id'          =>$items['bill_id'],

                            'ref_no'           =>$items['ref_no'],

                            'value'            =>$items['value'],

                            'id_employee'      =>$this->session->userdata('uid'),

                            'description'      =>'Green Tag Sales Incentive Debit',

                            'date_transaction' =>date("Y-m-d H:i:s"),

                            'date_add'	       =>date("Y-m-d H:i:s"),

                            );

    						$this->$model->insertData($WalletinsData,'wallet_transaction'); 

    						

					    }

					    //Wallet Debit Transcation

					   

					}

					if($this->db->trans_status()=== TRUE)

					{

						$this->db->trans_commit();

						$return_data=array('status'=>TRUE);

						$this->session->set_flashdata('chit_alert',array('message'=>'Bill No cancelled successfully','class'=>'success','title'=>'Cancell Bill'));

					}

					else

					{

						$this->db->trans_rollback();

						$return_data=array('status'=>false);						 	

						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Cancell Bill'));

 					}

 					echo json_encode($return_data);

					//redirect('admin_ret_billing/billing/list');

	}

	

	function get_branch_details()

	{

	    $model= "ret_billing_model";

 		$id_branch=$this->input->post('id_branch');

		$data =$this->$model->get_branch_details($id_branch);

		echo json_encode($data);

	}

	

	function getVoucherDetails()

	{

	    $model= "ret_billing_model";

 		$id_branch=$this->input->post('id_branch');

 		$id_cus=$this->input->post('bill_cus_id');

 		$code=$this->input->post('searchTxt');

		$data =$this->$model->getVoucherDetails($id_branch,$id_cus,$code);

		echo json_encode($data);

	}

	

	function getGiftProducts()

	{

	    $model= "ret_billing_model";

	    $data=$this->$model->CheckProductAvailability($_POST['id_set_gift_voucher']);

	    echo json_encode($data);

	}

	

	function GiftRedeemProduct()

	{

	    $model= "ret_billing_model";

	    $data=$this->$model->CheckRedeemProduct($_POST['id_set_gift_voucher']);

	    echo json_encode($data);

	}

	

	function GeneralGiftRedeemProduct()

	{

	    $model= "ret_billing_model";

	    $data=$this->$model->GeneralGiftRedeemProduct($_POST['id_gift_voucher']);

	    echo json_encode($data);

	}

	

	

	

	//Business Customers

	public function getSearchCompanyUsers(){

		$model = "ret_billing_model";

		$data = $this->$model->getSearchCompanyUsers($_POST['searchTxt'],$_POST['id_customer']);	  

		echo json_encode($data);

	}

	

	

    public function addNewCompanyUsers()

    {

        $model = "ret_billing_model";

        $data = $this->$model->addNewCompanyUsers($_POST);

        echo json_encode($data);

	}

	

	public function getCompanyPurchaseAmount()

	{

	    $model = "ret_billing_model";

		$data = $this->$model->getCompanyPurchaseAmount($_POST['id_customer']);	  

		echo json_encode($data);

	}

	

	//Business Customers

	

}	

?>
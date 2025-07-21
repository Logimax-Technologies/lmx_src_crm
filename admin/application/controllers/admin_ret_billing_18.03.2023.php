<?php

if( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'libraries/dompdf/autoload.inc.php');

use Dompdf\Dompdf;

class Admin_ret_billing extends CI_Controller

{

	const IMG_PATH  = 'assets/img/';

	const CUS_IMG_PATH = 'assets/img/customer';

	function __construct()

	{

		parent::__construct();

		ini_set('date.timezone', 'Asia/Calcutta');

		$this->load->model('ret_billing_model');

		$this->load->model('ret_purchase_order_model');

		$this->load->model('admin_settings_model');

		$this->load->model("sms_model");

		$this->load->model("admin_usersms_model");

		$this->load->model("log_model");

		$this->load->model("payment_model");

		$this->load->model("account_model");

		$this->load->model('ret_order_model');

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

	function set_image($id,$img_path,$file,$path)

    {
        if (!is_dir($path)) {
            mkdir($path, 0777, TRUE);
            
        }
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

		$pur_model = "ret_purchase_order_model";

		$set_model="admin_settings_model";

		$sms_model="admin_usersms_model";

		$ordermodel = "ret_order_model";

		$data['type']	= $type;

		switch($type)

		{

			case 'add':

					$profile                                    = $this->admin_settings_model->profileDB("get",$this->session->userdata('profile'));

					$data['billing']		                    = $this->$model->get_empty_record();

					$data['billing']['credit_sales_otp_req']    = $profile['credit_sales_otp_req'];

					$data['bill_other_item']                    = array("item_details" => array(), "old_matel_details" => array(), "stone_details" => array(), "other_material_details" => array(), "voucher_details" => array(), "chit_details" => array(), "advance_details" => array());

					$data['uom']		                        = $this->$model->getUOMDetails();

					

					//print_r($this->session->all_userdata());exit;

					

					$data['main_content'] = "billing/form" ;

					

					//echo "<pre>"; print_r($data);exit;

					$this->load->view('layout/template', $data);

				break;

			case 'list':

					$data['main_content'] = "billing/list" ;

					$this->load->view('layout/template', $data);

				break;

			case 'approvallist':

			        $data['main_content'] = "billing/approvallist" ;

					$this->load->view('layout/template', $data);

				break;

			case "save": 

					// 1-Sales, 2-Sales&Purchase, 3-Sales,purchase&Return, 4-Purchase, 5-Order Advance, 6-Advance,7-Sales Return,8-Credit Collection,9-Order Delivery,10-Chit Pre Close

					$addData = $_POST['billing'];

					

					//echo "<pre>";print_r($addData);exit;

					

					$allow_submit=TRUE;

					$dCData                     = $this->admin_settings_model->getBranchDayClosingData($addData['id_branch']);

					$fin_year                   = $this->$model->get_FinancialYear();

					$billSale                   = (isset($_POST['sale']) ? $_POST['sale']:'');

					$supplier_sales_bill        = (isset($_POST['supplier_sales_bill']) ? $_POST['supplier_sales_bill']:'');

					$supplierMetalDetilas       = (isset($_POST['metal_details']) ? $_POST['metal_details']:'');

					$billPurchase               = (isset($_POST['purchase']) ? $_POST['purchase']:'');

					$repair_orders              =  (isset($_POST['order']) ? $_POST['order']:'');

					$form_secret                = isset($addData["form_secret"])?$addData["form_secret"]:'';

				

					

					if(!empty($billSale))

					{

					    foreach($billSale['is_est_details'] as $key => $val)

					    {

					        $est_itm_id =(isset($billSale['est_itm_id'][$key]) ? ($billSale['est_itm_id'][$key]!='' ? $billSale['est_itm_id'][$key]:''):'');

					        $tag_id     =($billSale['tag'][$key]!='' ? $billSale['tag'][$key]:'');

					        if($tag_id!='')

					        {

					            $tag_status=$this->$model->get_tag_status($tag_id);

					            if($tag_status['is_partial']==0)

					            {

					                if($tag_status['tag_status']==0)

    					            {

    					                $allow_submit=TRUE;

    					            }else{

    					                $allow_submit=FALSE;

    					                break;

    					            }

					            }

					            else if($tag_status['is_partial']==1)

					            {

					                 $allow_submit=TRUE;

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

					        

					         if($billSale['billamount'][$key] > 0 && $billSale['item_total_tax'][$key] <= 0 ){

					            $allow_submit=FALSE;

					            break;

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

					

					if(!empty($supplier_sales_bill))

					{

					    $tagged_item_list = json_decode($addData['returntaggeditemlist'],true);

					    foreach($tagged_item_list as $val)

					    {

					            $tag_status=$this->$model->get_tag_status($val['tag_id']);

					            if($tag_status['tag_status']==0)

					            {

					                $allow_submit=TRUE;

					            }else{

					                $allow_submit=FALSE;

					                break;

					            }

					    }

					}

					

					if($this->session->userdata('FORM_SECRET'))

					{

					    if(strcasecmp($form_secret, ($this->session->userdata('FORM_SECRET'))) === 0)

					    {

					        if($allow_submit)

        					{

                                $metal_details                  =$this->$model->get_metal_details($addData['metal_type']);

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

            						$chit_deposit_acc_details	= json_decode($addData['chit_deposit_details'],true);

            						$bill_no                    = $this->$model->code_number_generator($addData['id_branch'],$addData['metal_type']);   //Bill Number Generate 

            					    $ref_bill_id                = ($addData['bill_type'] ==8 ?(!empty($addData['ret_bill_id']) ? $addData['ret_bill_id'] : NULL ): NULL );

            					    $bill_date                  = ($dCData['entry_date'] == date("Y-m-d") ? date("Y-m-d H:i:s") : $dCData['entry_date']);

            					    $metal_rate                 = $this->$model->get_branchwise_rate($addData['id_branch']);

            					    

            						$data = array(						

            							'bill_no'		    => $metal_details['metal_code'].'-'.$bill_no,

            							'fin_year_code'		=> $fin_year['fin_year_code'],

            							'ref_bill_id'	    => $ref_bill_id,

            							'bill_type'		    => $addData['bill_type'],

            							'round_off_amt'		=> $addData['round_off'],

            							'goldrate_22ct'		=> $addData['goldrate_22ct'],

            							'silverrate_1gm'	=> $addData['silverrate_1gm'],

            							'goldrate_18ct'	    => $metal_rate['goldrate_18ct'],

            							'form_secret'	    => $addData['form_secret'],

                        				'metal_type'	    => $addData['metal_type'],

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

            							'make_as_advance'   => ($addData['make_as_advance']!=NULL ? $addData['make_as_advance']:0),

            							'advance_deposit'   => ($addData['advance_deposit']!=NULL ? $addData['advance_deposit']:0),

            							'tcs_tax_amt'       => (!empty($addData['tcs_tax_amt']) ? $addData['tcs_tax_amt']:0),

            							'tcs_tax_per'       => ($addData['tcs_percent']>0 ? $addData['tcs_percent']:0),

            							'tds_percent'       => ($addData['tds_percent']>0 ? $addData['tds_percent']:0),

            							'tds_tax_amt'       => ($addData['tds_tax_value']>0 ? $addData['tds_tax_value']:0),

            							'delivered_at' 		=> $addData['delivered_at'],

            							'delivery_address_type'=> $addData['delivery_address_type'],
            							
            							'ret_sales_type'    => $addData['ret_sales_type'],

            							'credit_ret_amt'    => ($addData['credit_ret_amt']>0 ? $addData['credit_ret_amt']: ($addData['credit_ret_amt']*-1) ),

            							'credit_due_amt'    => ($addData['credit_due_amt']>0 ? $addData['credit_due_amt']:0),

            						); 

            		 				$this->db->trans_begin();

            		 				$insId = $this->$model->insertData($data,'ret_billing');

            		 				if($insId)

            		 				{

            		 				    

            		 				    

            		 				    //Update Ref No

            			

            								if($addData['bill_type']==8)

            								{

            								    $credit_coll_refno=$this->$model->generateRefNo($addData['id_branch'],'credit_coll_refno','',$addData['ret_sales_type']);

            							        $this->$model->updateData(array('credit_coll_refno'=>$credit_coll_refno),'bill_id',$insId,'ret_billing');

            								}

            								

            								if($addData['bill_type']==10)

            								{

            								    $chit_preclose_refno=$this->$model->generateRefNo($addData['id_branch'],'chit_preclose_refno','',$addData['ret_sales_type']);

            							        $this->$model->updateData(array('chit_preclose_refno'=>$chit_preclose_refno),'bill_id',$insId,'ret_billing');

            								}

            								

            							//Update Ref No

            							

            		 				    //DELIVERY ADDRESS DETAILS

            		 				    //echo "<pre>";print_r($addData);exit;

            		 				       if($addData['delivery_address_type']==1)

            		 				        {

            		 				            $delivery_address=$this->$model->get_customer_reg_add($addData['bill_cus_id']);

            		 				            

            		 				            $addressData=array(

            		 							'bill_id'	    =>$insId,

            									'id_customer'   =>$addData['bill_cus_id'],

            									'id_country'    =>($delivery_address['id_country']!='' ? strtoupper($delivery_address['id_country']):NULL),

            									'id_state'      =>($delivery_address['id_state']!='' ? strtoupper($delivery_address['id_state']):NULL),

            									'id_city'       =>($delivery_address['id_city']!='' ? strtoupper($delivery_address['id_city']):NULL),

            									'address1'      =>($delivery_address['address1']!='' ? strtoupper($delivery_address['address1']):NULL),

            									'address2'      =>($delivery_address['address2']!='' ? strtoupper($delivery_address['address2']):NULL),

            									'address3'      =>($delivery_address['address3']!='' ? strtoupper($delivery_address['address3']):NULL),

            									'pincode'       =>($delivery_address['pincode']!='' ? strtoupper($delivery_address['pincode']):NULL),

            		 							);

            		 							

            		 				        }

            		 				        else if($addData['delivery_address_type']==0)

            		 				        {

            		 				            //echo "<pre>";print_r($addData);exit;

            		 				            if(isset($addData['id_delivery_address']))

            		 				            {

            		 				                $delivery_address=$this->$model->getCusDelivery_address($addData['id_delivery_address']);

            		 				                

            		 				                $addressData=array(

                		 							'bill_id'	    =>$insId,

                									'id_customer'   =>$addData['bill_cus_id'],

                									'id_country'    =>($delivery_address['id_country']!='' ? strtoupper($delivery_address['id_country']):NULL),

                									'id_state'      =>($delivery_address['id_state']!='' ? strtoupper($delivery_address['id_state']):NULL),

                									'id_city'       =>($delivery_address['id_city']!='' ? strtoupper($delivery_address['id_city']):NULL),

                									'address1'      =>($delivery_address['address1']!='' ? strtoupper($delivery_address['address1']):NULL),

                									'address2'      =>($delivery_address['address2']!='' ? strtoupper($delivery_address['address2']):NULL),

                									'address3'      =>($delivery_address['address3']!='' ? strtoupper($delivery_address['address3']):NULL),

                									'pincode'       =>($delivery_address['pincode']!='' ? strtoupper($delivery_address['pincode']):NULL),

                		 							);

            		 							

            		 				            }

            		 				            else

            		 				            {

            		 				                $addressData=array(

                		 							'bill_id'	    =>$insId,

                									'id_customer'   =>$addData['bill_cus_id'],

                									'id_country'    =>($addData['del_country']!='' ? strtoupper($addData['del_country']):NULL),

                									'id_state'      =>($addData['del_state']!='' ? strtoupper($addData['del_state']):NULL),

                									'id_city'       =>($addData['del_city']!='' ? strtoupper($addData['del_city']):NULL),

                									'address1'      =>($addData['del_address1']!='' ? strtoupper($addData['del_address1']):NULL),

                									'address2'      =>($addData['del_address2']!='' ? strtoupper($addData['del_address2']):NULL),

                									'address3'      =>($addData['del_address3']!='' ? strtoupper($addData['del_address3']):NULL),

                									'pincode'       =>($addData['del_pincode']!='' ? strtoupper($addData['del_pincode']):NULL),

                		 							);

                		 							

                		 							$cusAddressData=array(

                									'address_name'  =>$addData['del_address_name'],

                									'id_customer'   =>$addData['bill_cus_id'],

                									'id_country'    =>($addData['del_country']!='' ? strtoupper($addData['del_country']):NULL),

                									'id_state'      =>($addData['del_state']!='' ? strtoupper($addData['del_state']):NULL),

                									'id_city'       =>($addData['del_city']!='' ? strtoupper($addData['del_city']):NULL),

                									'address1'      =>($addData['del_address1']!='' ? strtoupper($addData['del_address1']):NULL),

                									'address2'      =>($addData['del_address2']!='' ? strtoupper($addData['del_address2']):NULL),

                									'address3'      =>($addData['del_address3']!='' ? strtoupper($addData['del_address3']):NULL),

                									'pincode'       =>($addData['del_pincode']!='' ? strtoupper($addData['del_pincode']):NULL),

                		 							);

                		 							

                		 							$this->$model->insertData($cusAddressData,'customer_delivery_address');

                		 							

            		 				            }

            		 				        }

            		 				    

            		 				    $this->$model->insertData($addressData,'ret_bill_delivery');

            		 				    

            		 				    //DELIVERY ADDRESS DETAILS

            		 				    

            		 				    

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

            							    

            								$pan_imgs       = "";

            								$folder         =  self::IMG_PATH."billing/".$bill_no;

            								$cus_pan_folder =  self::CUS_IMG_PATH.'/'.$addData['bill_cus_id'];

            								if (!is_dir($folder)) {

            									mkdir($folder, 0777, TRUE);

            								}

            								

            								if (!is_dir($cus_pan_folder)) {

            									mkdir($cus_pan_folder, 0777, TRUE);

            								}

            								

            								foreach($_FILES['pan_img'] as $file_key => $file_val)

            								{

                								if($file_val['tmp_name'])

                								{

                								    // unlink($folder."/".$product['image']); 

                									$img_name       =  "P_". mt_rand(120,1230).".jpg";

                									$cus_pan_path   =   $cus_pan_folder."/".'pan.jpg'; 

                									$path           =   $folder."/".$img_name; 

                									$result = $this->upload_img('image',$path,$file_val['tmp_name']);

                									$this->upload_img('image',$cus_pan_path,$file_val['tmp_name']);

                									if($result){

                    									$pan_imgs = strlen($pan_imgs) > 0 ? $pan_imgs."#".$img_name : $img_name;

                									}

            									}

            								}

            								$this->$model->updateData(array('pan_image'=>$pan_imgs),'bill_id',$insId,'ret_billing');

            								

            								$this->$model->updateData(array('pan_proof'=>$pan_imgs),'id_customer',$addData['bill_cus_id'],'customer');



            							}

            							

            		 					// Return Bill

            		 					if($addData['bill_type'] == 3 || $addData['bill_type']== 7) 

            	 						{ 							

            	 							//Update Ref No

            								$ref_no=$this->$model->generateRefNo($addData['id_branch'],'s_ret_refno',$addData['metal_type'],$addData['ret_sales_type']);

            								$this->$model->updateData(array('s_ret_refno'=>$ref_no),'bill_id',$insId,'ret_billing');

            	 							foreach($_POST['sales_return'] as $return_detail)

            	 							{

            	 								// Update Bill Return status

            							 		$this->$model->updateData(array( "return_status" => 1),'bill_id',$ref_bill_id, 'ret_billing');

            							 		$updBillDetail = array( 

            												 		"status" 				=> 2,

            												 		"current_branch"        => $addData['id_branch'],

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

            							 		$returnBillStatus=$this->$model->updateData($updEstiRet,'est_item_id',$return_detail['est_itm_id'], 'ret_estimation_items');

            							 		

            							 

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

                        					 //Insert into Purchase Item Log Table

    									        $salesReturnLog=array(

    									                             'bill_id'     =>$insId,

    									                             'tag_id'      =>$return_detail['tag'],

    									                             'from_branch' =>NULL,

    									                             'to_branch'   =>$addData['id_branch'],

    									                             'status'      =>1,//Inward

    									                             'item_type'   =>2, // Sales Return

    									                             'date'        =>$bill_date,

    									                             'created_on'  =>date("Y-m-d H:i:s"),

    													             'created_by'  =>$this->session->userdata('uid'),

    									                            );

    									       $this->$model->insertData($salesReturnLog,'ret_purchase_items_log');

            							 	}	 					 

            			 				}

            			 				

            		 					//Amount Advance

                	 					if($addData['bill_type']==5) 

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

            								$ref_no=$this->$model->generateRefNo($addData['id_branch'],'order_adv_ref_no','',$addData['ret_sales_type']);

            								$this->$model->updateData(array('order_adv_ref_no'=>$ref_no),'bill_id',$insId,'ret_billing');

            								//Update Ref No

                	 					}

                	 					

                	 					

                	 					if($addData['make_as_advance']==1)

                	 			        {

												$bill_no = $this->$model->bill_no_generate($addData['id_branch']);

												$bill_date = ($dCData['entry_date'] == date("Y-m-d") ? date("Y-m-d H:i:s") : $dCData['entry_date']);

                                                $wallet=$this->$model->get_retWallet_details($addData['bill_cus_id']);

                                                if($wallet['status'])

                                                {

                                                    $weight=0;

                                                    $updStatus=$this->$model->updateWalletData(array('amount'=>$addData['advance_deposit'],'weight'=>$weight,'id_customer'=>$addData['bill_cus_id']),'+');

                                                    if($updStatus)

                                                    {

                                                        $insWallet=array(

                                                        'id_ret_wallet'		=>$wallet['id_ret_wallet'],

                                                        'deposit_bill_id'	=>$insId,

                                                        'amount'			=>$addData['advance_deposit'],

                                                        'transaction_type'	=>0,

                                                        'created_by' 		=>$this->session->userdata('uid'),

                                                        'created_on' 		=> date("Y-m-d H:i:s"),

                                                        'remarks'	 		=>'Billing Advace Deposit Amount'

                                                        );

                                                        $this->$model->insertData($insWallet,'ret_wallet_transcation');

                                                    }

													$advance=array(

														'fin_year_code' => $fin_year['fin_year_code'],

														'bill_no'	    => $bill_no,

														'deposit_bill_id'=>$insId,

														'bill_date'     => $bill_date,

														'type'          => 2,

														'id_branch'     => $addData['id_branch'],

														'amount'        => $addData['advance_deposit'],

														'receipt_type'    => 3, // sale return advance

														'id_customer'   => ($addData['bill_cus_id']!='' ? $addData['bill_cus_id'] :NULL),

														'id_employee'   => $this->session->userdata('uid'),

														'id_acc_head'   => ($addData['id_acc_head']!='' ? $addData['id_acc_head'] :NULL),

														'narration'	    => ($addData['narration']!='' ?$addData['narration'] : ''),

														'created_by'    => $this->session->userdata('uid'),

														'counter_id'    => ($this->session->userdata('counter_id')!='' ? $this->session->userdata('counter_id'):NULL),

														'created_on'    => date("Y-m-d H:i:s"),

													);

													$this->$model->insertData($advance,'ret_issue_receipt');

                                                }

                                                else

                                                {

                                                    $wallet_acc=array(

                                                        'id_customer'   =>$addData['bill_cus_id'],

                                                        'amount'        =>$addData['advance_deposit'],

                                                        'created_by'    =>$this->session->userdata('uid'),

                                                        'created_time'  =>date("Y-m-d H:i:s")

                                                    );

                                                    $insWalletAcc= $this->$model->insertData($wallet_acc,'ret_wallet');

                                                    if($insWalletAcc)

                                                    {

                                                            $insWallet=array(

                                                                'id_ret_wallet'	    =>$insWalletAcc,

                                                                'deposit_bill_id'	=>$insId,

                                                                'amount'			=>$addData['advance_deposit'],

                                                                'transaction_type'	=>0,

                                                                'created_by' 		=>$this->session->userdata('uid'),

                                                                'created_on' 		=> date("Y-m-d H:i:s"),

                                                                'remarks'	 		=>'Billing Advace Deposit Amount'

                                                            );

                                                            $this->$model->insertData($insWallet,'ret_wallet_transcation');

                                                    }

													$advance=array(

														'fin_year_code' => $fin_year['fin_year_code'],

														'bill_no'	    => $bill_no,

														'bill_date'     => $bill_date,

														'deposit_bill_id'=>$insId,

														'type'          => 2,

														'id_branch'     => $addData['id_branch'],

														'amount'        => $addData['advance_deposit'],

														'receipt_type'    => 3, // sale return advance

														'id_customer'   => ($addData['bill_cus_id']!='' ? $addData['bill_cus_id'] :NULL),

														'id_employee'   => $this->session->userdata('uid'),

														'id_acc_head'   => ($addData['id_acc_head']!='' ? $addData['id_acc_head'] :NULL),

														'narration'	    => ($addData['narration']!='' ?$addData['narration'] : ''),

														'created_by'    => $this->session->userdata('uid'),

														'counter_id'    => ($this->session->userdata('counter_id')!='' ? $this->session->userdata('counter_id'):NULL),

														'created_on'    => date("Y-m-d H:i:s"),

													);

													$this->$model->insertData($advance,'ret_issue_receipt');

                                                }

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

            		 							    'bill_id'                   =>$insId,

            		 							    'scheme_account_id'         =>$chit_uti['scheme_account_id'],

            		 							    'utilized_amt'              =>$chit_uti['utl_amount'],

            		 							    'closing_weight'            => ($chit_uti['closing_weight']!='' ? $chit_uti['closing_weight']:0),

										            'wastage_per'               => ($chit_uti['wastage_per']!='' ? $chit_uti['wastage_per']:0),

										            'savings_in_wastage'        => ($chit_uti['savings_in_wastage']!='' ? $chit_uti['savings_in_wastage']:0),

										            'mc_value'                  => ($chit_uti['mc_value']!='' ? $chit_uti['mc_value'] :''),

										            'savings_in_making_charge'  => ($chit_uti['savings_in_making_charge']!='' ? $chit_uti['savings_in_making_charge']:0),

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

            										'card_type'     =>$card_pay['card_name'],

            										'payment_amount'=>$card_pay['card_amt'],

            										'id_pay_device' =>($card_pay['id_device']!='' ? $card_pay['id_device']:NULL),

            										'payment_for'	=>($addData['bill_type']==6 ?2:1),

            										'payment_status'=>1,

            										'payment_date'		=>date("Y-m-d H:i:s"),

            										'payment_mode'	=>($card_pay['card_type']==1 ?'CC':'DC'),

            										'card_no'		=>($card_pay['card_no']!='' ? $card_pay['card_no']:NULL),

            										'payment_ref_number' =>($card_pay['ref_no']!='' ? $card_pay['ref_no']:NULL),	

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

            			 					    $cheque_deposit_date = ($chq_pay['cheque_date']!='' ?date_create($chq_pay['cheque_date']) :NULL);

            			 					    $cheque_date = ($chq_pay['cheque_date']!='' ? date_format($cheque_deposit_date,"Y-m-d"):NULL);

            									$arraychqPay[]=array(

            										'bill_id'		=>$insId,

            										'payment_amount'=>($addData['pay_to_cus']>0 ?'-'.$chq_pay['payment_amount']:$chq_pay['payment_amount']),

            										'payment_for'	=>($addData['bill_type']==6 ?2:1),

            										'type'          =>($addData['pay_to_cus']>0 ?2:1),

            										'payment_status'=>1,

            										'payment_date'		=>date("Y-m-d H:i:s"),

            										'cheque_date'		=>($chq_pay['cheque_date']!='' ? $cheque_date:NULL),

            										'payment_mode'	=>'CHQ',

            										'cheque_no'		=>($chq_pay['cheque_no']!='' ? $chq_pay['cheque_no']:NULL),

            										'bank_name'		=>($chq_pay['bank_name']!='' ? $chq_pay['bank_name']:NULL),

            										'bank_branch'	=>($chq_pay['bank_branch']!='' ? $chq_pay['bank_branch']:NULL),

            										'id_bank'	    =>($chq_pay['id_bank']!='' ? $chq_pay['id_bank']:NULL),

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

            										'id_pay_device'     =>($nb_pay['id_device']!='' ? $nb_pay['id_device']:NULL),

            										'payment_status'    =>1,

            										'type'              =>($addData['pay_to_cus']>0 ?2:1),

            										'payment_date'		=>date("Y-m-d H:i:s"),

            										'payment_mode'	    =>'NB',

            										'id_bank'           =>($nb_pay['id_bank']!='' ? $nb_pay['id_bank']:NULL),

            										'payment_ref_number'=>($nb_pay['ref_no']!='' ? $nb_pay['ref_no']:NULL),

            										'NB_type'           =>($nb_pay['nb_type']!='' ? $nb_pay['nb_type']:NULL),

            										'net_banking_date'  =>($nb_pay['nb_date']!='' ? $nb_pay['nb_date']:date("Y-m-d")),

            										'created_time'	    => date("Y-m-d H:i:s"),

            										'created_by'	    => $this->session->userdata('uid')

            									);

            		 						}

            			 						if(!empty($arrayNBPay)){

            										$NbPayInsert = $this->$model->insertBatchData($arrayNBPay,'ret_billing_payment'); 

            									}

            		 					}

            		 				    

            		 				    //Advance Adjusement

            		 					$advan_amout = $_POST['adv']['advance_muliple_receipt'][0];

                    					if(count($advan_amout)>0)

                    					{

                        					$advance_amount_adj = json_decode($advan_amout);

                        					$advance_amt=0;

                        					foreach($advance_amount_adj as $obj)

                        					{

                        					    $advance_amt+=$obj->adj_amount;

                                                $id_ret_wallet      = $obj->id_ret_wallet;

                                                $data_adv_amount    = array(

                                                                            'id_issue_receipt'  => $obj->id_issue_receipt,

                                                                            'bill_id'           => $insId,

                                                                            'utilized_amt'      => $obj->adj_amount

                                                                        );

                                                $insId_adv_amount = $this->$model->insertData($data_adv_amount,'ret_advance_utilized');

                                                if($obj->refund_amount > 0)

                                                {

                                                       $issue_bill_no = $this->$model->bill_no_generate($addData['id_branch']);

                                                       $IssueData=array(

                                    				    'fin_year_code' => $fin_year['fin_year_code'],

                                    				    'bill_no'	    => $issue_bill_no,

                                    				    'bill_date'     => $bill_date,

                                    					'type'          => 1, // Issue

                                    					'id_branch'     => $addData['id_branch'],

                                    					'amount'        => $obj->refund_amount,

                                    					'issue_to'      => 2, // customer

                                    					'issue_type'    => 3, // advance refund

                                    					'id_customer'   => $addData['bill_cus_id'],

                                    					'id_employee'   => $this->session->userdata('uid'),

                                    					'narration'	    => 'Advance Amount Refund From Billing',

                                    					'created_by'    => $this->session->userdata('uid'),

                                    					'counter_id'    => ($this->session->userdata('counter_id')!='' ? $this->session->userdata('counter_id'):NULL),

                                    					'refno'         => NULL ,

                                    					'created_on'    => date("Y-m-d H:i:s"),

                                    					);

					                                    $IssueinsId = $this->$model->insertData($IssueData,'ret_issue_receipt');

					                                    if($IssueinsId)

					                                    {

					                                        $refundData=array(

                                                                'id_issue_receipt' =>$IssueinsId,

                                                                'refund_receipt'   =>$obj->id_issue_receipt,

                                                                'refund_amount'    =>$obj->refund_amount,

                                                            );

                                                            $this->$model->insertData($refundData,'ret_advance_refund');

                                                            

                                                            $pay_data=array(

                                        	 					'id_issue_rcpt'	=>$IssueinsId,

                                        	 					'payment_amount'=>$obj->refund_amount,

                                        	 					'payment_mode'	=>($obj->pay_mode=='CSH' ? 'Cash' :($obj->pay_mode=='CHQ' ? 'CHQ' :'')),

                                        	 					'payment_status'=>1,

                                        	 					'type'			=>2,

                                        	 					'payment_type'	=>'Manual',

                                        						'payment_date'	=>date("Y-m-d H:i:s"),

                                        						'created_time'	=> date("Y-m-d H:i:s"),

                                        						'created_by'	=> $this->session->userdata('uid')

                                            	 				);

                                            	 				$this->$model->insertData($pay_data,'ret_issue_rcpt_payment');

    	 				

					                                    }

                                                        

                                                }

                                            }

                                            if($insId_adv_amount)

                                            {

                                                $this->$model->updateWalletData(array('amount'=>$advance_amt,'weight'=>0,'id_customer'=>$addData['bill_cus_id']),'-');

                                            }

                    					}

                    					//Advance Adjusement

                    					

                    					

                    					//SUPPLIER BILL SALES

                    						if(!empty($supplierMetalDetilas))

                    						{

                    						    $arrayBillDetails=array();

                    						    $ref_no=$this->$model->generateRefNo($addData['id_branch'],'sales_ref_no',$addData['metal_type'],$addData['ret_sales_type']);

                    						    $this->$model->updateData(array('sales_ref_no'=>$ref_no),'bill_id',$insId,'ret_billing');

                    						    foreach($supplierMetalDetilas['id_product'] as $key => $val)

                    						    {

                    						        $purity_details=$this->$model->get_purity_details($supplierMetalDetilas['purity'][$key]);

                                                    $arrayBillDetails=array(

                                                    'bill_id' => $insId,

                                                    'product_id' 	=> ($supplierMetalDetilas['id_product'][$key]!='' ? $supplierMetalDetilas['id_product'][$key]:NULL),

                                                    'design_id' 	=> ($supplierMetalDetilas['id_design'][$key]!='' ? $supplierMetalDetilas['id_design'][$key]:NULL),

                                                    'id_sub_design' => ($supplierMetalDetilas['id_sub_design'][$key]!='' ? $supplierMetalDetilas['id_sub_design'][$key]:NULL),

                                                    'purity' 	    => $purity_details['id_purity'],

                                                    'piece' 		=> ($supplierMetalDetilas['pcs'][$key]!='' ? $supplierMetalDetilas['pcs'][$key]:NULL), 

                                                    'gross_wt' 		=> $supplierMetalDetilas['weight'][$key], //gross wt

                                                    'net_wt' 		=> $supplierMetalDetilas['weight'][$key], //gross wt

                                                    'rate_per_grm' 	=> $supplierMetalDetilas['rate_per_gram'][$key], 

                                                    'total_cgst' 	=> $supplierMetalDetilas['item_total_cgst'][$key], 

                                                    'total_sgst' 	=> $supplierMetalDetilas['item_total_sgst'][$key], 

                                                    'total_igst' 	=> $supplierMetalDetilas['item_total_igst'][$key], 

                                                    'item_total_tax'=> $supplierMetalDetilas['item_total_tax'][$key], 

                                                    'item_cost' 	=> $supplierMetalDetilas['item_cost'][$key], 

                                                    'is_non_tag'    => 1,

                                                    );

                                                    $billDetailStatus = $this->$model->insertData($arrayBillDetails,'ret_bill_details'); 

                                                   

                                                     //UPDATE INTO PURCHASE ITEM STOCK SUMMARY

                                                       

                                                        $existData=array('id_product'=>$supplierMetalDetilas['id_product'][$key],'id_sub_design'=>$supplierMetalDetilas['id_sub_design'][$key],'design'=>$supplierMetalDetilas['id_design'][$key],'id_branch'=>$addData['id_branch']);

                                    						        

                        						        $isExist = $this->$model->checkNonTagItemExist($existData);

                        						        

                        						        if($isExist['status'] == TRUE)

                        						        {

                        						            $nt_data = array(

                                                            'id_nontag_item'=>$isExist['id_nontag_item'],

                                                            'gross_wt'		=> $supplierMetalDetilas['weight'][$key],

                                                            'net_wt'		=> $supplierMetalDetilas['weight'][$key],  

                                                            'no_of_piece'   => $supplierMetalDetilas['pcs'][$key],

                                                            'updated_by'	=> $this->session->userdata('uid'),

                                                            'updated_on'	=> date('Y-m-d H:i:s'),

                                                            );

                                                            $this->$model->updateNTData($nt_data,'-');

                                                													

                                                            $non_tag_data=array(

                                                            'product'	    => $supplierMetalDetilas['id_product'][$key],

                                                            'design'	    => $supplierMetalDetilas['id_design'][$key],

                                                            'id_sub_design'	=> $supplierMetalDetilas['id_sub_design'][$key],

                                                            'gross_wt'		=> $supplierMetalDetilas['weight'][$key],

                                                            'net_wt'		=> $supplierMetalDetilas['weight'][$key],

                                                            'no_of_piece'   => $supplierMetalDetilas['pcs'][$key],

                                                            'from_branch'	=> $addData['id_branch'],

                                                            'to_branch'	    => NULL,

                                                            'bill_id'	    => $insId,

                                                            'status'	    => 1,

                                                            'date'          => $bill_date,

                                                            'created_on'    => date("Y-m-d H:i:s"),

                                                            'created_by'    =>  $this->session->userdata('uid')

                                                            );

                                                            $this->$model->insertData($non_tag_data,'ret_nontag_item_log');

                        						        }

    						        

                                                    //UPDATE INTO PURCHASE ITEM STOCK SUMMARY

                    						    }

                    						}

                    					//SUPPLIER BILL SALES

                    					

            		 					//Sales Items

            							if(!empty($billSale)){

            								$arrayBillSales = array();

            								//Update Ref No

            								if($addData['bill_type'] == 15){ // For Approval Bill

            								    $ref_no =   $this->$model->generateRefNo($addData['id_branch'],'approval_ref_no',$addData['metal_type'],$addData['ret_sales_type']);

            								    $this->$model->updateData(array('approval_ref_no'=>$ref_no),'bill_id',$insId,'ret_billing');

            								}else{

            								    $ref_no =   $this->$model->generateRefNo($addData['id_branch'],'sales_ref_no',$addData['metal_type'],$addData['ret_sales_type']);

            								    $this->$model->updateData(array('sales_ref_no'=>$ref_no),'bill_id',$insId,'ret_billing');

            								}

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

            										'is_partial_sale'   =>($billSale['is_partial'][$key]!='' ? $billSale['is_partial'][$key] :0),

            										'mc_discount'       =>($billSale['mc_discount'][$key]!='' ? $billSale['mc_discount'][$key] :0),

            										'wastage_discount'  =>($billSale['wastage_discount'][$key]!='' ? $billSale['wastage_discount'][$key] :0),

            										'item_blc_discount'  =>($billSale['item_blc_discount'][$key]!='' ? $billSale['item_blc_discount'][$key] :0),

            										'bill_discount_type' =>$addData['bill_discount_type'],

            										'id_collecion_maping_det'=>($billSale['id_collecion_maping_det'][$key]!='' ? $billSale['id_collecion_maping_det'][$key] :0),

            										'is_non_tag'    => !empty($billSale['is_non_tag'][$key]) ? $billSale['is_non_tag'][$key] : 0,

            										'id_orderdetails'=>(isset($billSale['id_orderdetails'][$key]) ?($billSale['id_orderdetails'][$key]!='' ? $billSale['id_orderdetails'][$key] :NULL) :NULL)

            									); 

            									if(!empty($arrayBillSales))

            									{

            										$tagInsert = $this->$model->insertData($arrayBillSales,'ret_bill_details'); 

            										if($tagInsert)

            										{

            										    //Sales Incentive

            										    if(isset($billSale['tag'][$key]) && $billSale['tag'][$key]!='' && $billSale['est_itm_id'][$key]!='')

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

                            								$blc_gross_wt=$tag['gross_wt']-($tag['sold_gwt']+$billSale['gross'][$key]);

                            								$blc_net_wt=($tag['net_wt']-($tag['sold_nwt']+$billSale['net'][$key]));

                            								$partly_data=array(

                            								'tag_id'		=>$billSale['tag'][$key],

                            								'sold_bill_det_id'=>$tagInsert,

                            								'product'		=>$billSale['product'][$key],

                            								'design'		=>($billSale['design'][$key]!='' ? $billSale['design'][$key]:NULL),

                            								'sold_gross_wt'	=>$billSale['gross'][$key],

                            								'sold_less_wt'	=>($billSale['less'][$key]!='' ? $billSale['less'][$key]:NULL),

                            								'sold_net_wt'	=>$billSale['net'][$key],

                            								'blc_gross_wt'	=>$blc_gross_wt,

                            								'blc_less_wt'	=>0,

                            								'blc_net_wt'	=> $blc_net_wt,

                            								'created_on'  	=> date("Y-m-d H:i:s"),

                            								'created_by'   	=> $this->session->userdata('uid'),	

                            								'status'   		=>($blc_net_wt==0 ? 0:1),	

                            								);

                            								$this->$model->insertData($partly_data,'ret_partlysold');

                            								//Insert into Purchase Item Log Table

                									        $partlySaleLog=array(

                									                             'sold_bill_det_id'=>$tagInsert,

                									                             'tag_id'      =>$billSale['tag'][$key],

                									                             'gross_wt'    =>$blc_gross_wt,

                                                                                 'net_wt'      =>$billSale['net'][$key],

                									                             'from_branch' =>NULL,

                									                             'to_branch'   =>$addData['id_branch'],

                									                             'status'      =>1,

                									                             'item_type'   =>3, // Partly Sale

                									                             'date'        =>$bill_date,

                									                             'created_on'  =>date("Y-m-d H:i:s"),

                													             'created_by'  =>$this->session->userdata('uid'),

                									                            );

                									       $this->$model->insertData($partlySaleLog,'ret_purchase_items_log');

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

            											//echo "<pre>";print_r($billSale['stone_details'][$key]);exit;

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

                    											'item_type'     =>1, //Sale item,

                    											'is_apply_in_lwt'=>$stone['is_apply_in_lwt'],

                                                            	'stone_cal_type' =>$stone['stone_cal_type'],

                                                            	'rate_per_gram'  =>$stone['rate_per_gram'],

                    											);

                    											$stoneInsert = $this->$model->insertData($stone_data,'ret_billing_item_stones');

                                                                //print_r($this->db->last_query());exit;

                											}										

            											}

            											

            											if($billSale['other_metal_details'][$key])

            											{

                											$other_metal_details=json_decode($billSale['other_metal_details'][$key],true);

                											foreach($other_metal_details as $other_metal_det)

                                                            {

                                                                $est_other_metals=array(

                                                            	'bill_det_id'               =>$tagInsert,

                                                            	'tag_other_itm_metal_id'    =>$other_metal_det['tag_other_itm_metal_id'],

                                                            	'tag_other_itm_pur_id'      =>$other_metal_det['tag_other_itm_pur_id'],

                                                            	'tag_other_itm_grs_weight'  =>$other_metal_det['tag_other_itm_grs_weight'],

                                                            	'tag_other_itm_wastage'     =>$other_metal_det['tag_other_itm_wastage'],

                                                            	'tag_other_itm_uom'         =>$other_metal_det['tag_other_itm_uom'],

                                                            	'tag_other_itm_cal_type'    =>$other_metal_det['tag_other_itm_cal_type'],

                                                            	'tag_other_itm_mc'          =>$other_metal_det['tag_other_itm_mc'],

                                                            	'tag_other_itm_rate'        =>$other_metal_det['tag_other_itm_rate'],

                                                            	'tag_other_itm_pcs'         =>$other_metal_det['tag_other_itm_pcs'],

                                                            	'tag_other_itm_amount'      =>$other_metal_det['tag_other_itm_amount'],

                                                            	);

                                                               $this->$model->insertData($est_other_metals,'ret_bill_other_metals');

                                                            }										

            											}

            											

            											if(isset($billSale['est_itm_id'][$key]))

            											{

            												//Update Estimation Items by est_itm_id

            												$this->$model->updateData(array('purchase_status'=>1,'bil_detail_id'=>$tagInsert),'est_item_id',(isset($billSale['est_itm_id'][$key])? $billSale['est_itm_id'][$key]:''), 'ret_estimation_items');	
                                                            $est_details = $this->$model->get_sale_est_details($billSale['est_itm_id'][$key]);
                                                            
                                                            if($est_details['esti_id']!=''){
                                                                $this->$model->updateData(array('estbillid'=>$insId),'estimation_id',$est_details['esti_id'], 'ret_estimation');
                                                            }
            											}

            											if($billSale['tag'][$key]!='')

            											{

            												//Update Estimation Items by est_itm_id

            												//$this->$model->updateData(array('purchase_status'=>1,'bil_detail_id'=>$tagInsert),'tag_id',(isset($billSale['tag'][$key])? $billSale['tag'][$key]:''), 'ret_estimation_items');	

            												

            												$this->$model->updateData(array('tag_status'=>1),'tag_id',$billSale['tag'][$key], 'ret_taging');

            												

            												if($billSale['itemtype'][$key]==0)

            												{

            												    $this->$model->updateData(array('is_partial'=>$billSale['is_partial'][$key]),'tag_id',$billSale['tag'][$key], 'ret_taging');

            												}

            												//Update Tag Log status

            												$tag_log=array(

            													'tag_id'	  =>$billSale['tag'][$key],

            													'date'		  =>$bill_date,

            													'status'	  =>1,

            													'from_branch' =>$addData['id_branch'],

            													'to_branch'	  =>NULL,

            													'issuspensestock' => $addData['bill_type'] == 15 ? 1 : 0,

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

            									/*$service = $this->$set_model->get_service($serviceID);

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

                    							}*/

                    							

                    							$service = $this->$set_model->get_service_by_code('BILLING');

            								    

                							    if($service['serv_whatsapp'] == 1 || $service['serv_sms'] == 1)

                            					{

                            					    $sms_data=$this->admin_usersms_model->Get_service_code_sms('BILLING',$insId,'');

                            					    

                            						if($sms_data['mobile']!='')

                            						{

                            						    $whatsapp=$this->send_sms($sms_data['mobile'],$sms_data['message'],$service['dlt_te_id']);

                            						}

                            					}

                            					

            							}

            							//Purchase Items

            							if(!empty($billPurchase))

            							{

            								//Update Ref No

            								$ref_no=$this->$model->generateRefNo($addData['id_branch'],'pur_ref_no',$addData['metal_type'],$addData['ret_sales_type']);

            								$this->$model->updateData(array('pur_ref_no'=>$ref_no),'bill_id',$insId,'ret_billing');

            								//Update Ref No

            								$arrayPurchaseBill = array();

            								$old_metal_weight=0;

            								$old_metal_amount=0;

            								foreach($billPurchase['is_est_details'] as $key => $val)

            								{

            									if($billPurchase['is_est_details'][$key] == 1)

            									{

            									    $old_metal_amount+=$billPurchase['billamount'][$key];

            									    $old_metal_weight+=($billPurchase['net'][$key]*($billPurchase['purity'][$key])/91.6);

            										$arrayPurchaseBill= array(

            										                'bill_id'                   => $insId, 

            										                'current_branch'            => $addData['id_branch'],

            										                'metal_type'                => $billPurchase['metal_type'][$key], 

            										                'item_type'                 => $billPurchase['itemtype'][$key], 

            										                'esti_old_metal_sale_id'    => $billPurchase['est_old_itm_id'][$key],

            										                'piece'                     => empty($billPurchase['piece'][$key]) ? $billPurchase['pcs'][$key] : $billPurchase['piece'][$key], 

            										                'gross_wt'                  => $billPurchase['gross'][$key], 

            										                'stone_wt'                  => $billPurchase['stone_wt'][$key],

            										                'dust_wt'                   => $billPurchase['dust_wt'][$key],

            										                'net_wt'                    => $billPurchase['net'][$key],

            										                'wast_wt'                   => $billPurchase['wastage_wt'][$key],

            										                'wastage_percent'           => $billPurchase['wastage'][$key], 

            										                'rate'                      => $billPurchase['billamount'][$key], 

            										                'rate_per_grm'              => $billPurchase['rate_per_grm'][$key], 

            										                'purity'                    => ($billPurchase['purity'][$key]!='' ? $billPurchase['purity'][$key]:0), 

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

                    										'advance_amount'    => ($addData['pur_store_as']==1 ? ($billPurchase['billamount'][$key]):0),

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
                                                        
                                                        $est_details = $this->$model->get_old_metal_est_details($billPurchase['est_old_itm_id'][$key]);
                                                        if($est_details['est_id']!=''){
                                                            $this->$model->updateData(array('estbillid'=>$insId),'estimation_id',$est_details['est_id'], 'ret_estimation');
                                                        }
            	    								     //Insert into Old Metal Log Table

            									        $old_metal_log=array(

            									                             'old_metal_sale_id'=>$oldMetal,

            									                             'from_branch'      =>NULL,

            									                             'to_branch'        =>$addData['id_branch'],

            									                             'status'           =>1,

            									                             'item_type'        =>1, // Old Metal

            									                             'date'             =>$bill_date,

            									                             'created_on'       =>date("Y-m-d H:i:s"),

            													             'created_by'      =>$this->session->userdata('uid'),

            									                            );

            									       $this->$model->insertData($old_metal_log,'ret_purchase_items_log');

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

                							

                							if($addData['make_as_advance']==2)

                							{

                							    if(sizeof($chit_deposit_acc_details)>0)

                							    {

                							        foreach($chit_deposit_acc_details as $acc)

                							        {

                							            if($old_metal_weight>0)

                        							    {

                        							        

                        							        if($this->payment_model->get_rptnosettings()==1)

                                        					{		

                                        					  $receipt_no = $this->generate_receipt_no($acc['id_scheme'], $addData['id_branch']);

                                        					} 

                                        					else

                                        					{						

                                        						$receipt_no=NULL;

                                        					}

					

                        							        $pay_array = array(

                                                                   'id_scheme_account'	  =>  $acc['id_scheme_account'], 				       			 

                                                                    'id_employee' 		  =>  $this->session->userdata('uid'),

                                                                    'date_payment'        =>  date('Y-m-d H:i:s'),

                                                                    'id_branch' 		  =>  $addData['id_branch'],

                                                                    'due_type'      	  =>  'ND', 	

                                                                    'payment_mode' 		  =>  'OG',

                                                                    'payment_status'      =>  1,

                                                                    'receipt_no'		  =>  $receipt_no,

                                                                    'gst'			      =>  0,

                                                                    'added_by'			  =>  0,

                                                                    'date_add'            =>  date('Y-m-d H:i:s'),

                                                                    'date_upd'			  =>  date('Y-m-d H:i:s'),

                                                                    'approval_date'		  =>  date('Y-m-d H:i:s'),

                                                                    "payment_amount"      =>  number_format($old_metal_amount,2,'.',''),

                                                                    "metal_weight"        =>  number_format(($old_metal_amount/$addData['goldrate_22ct']),3,'.','')

                                                                    );

                                                             $payId = $this->$model->insertData($pay_array,'payment'); 

                                                            // print_r($payId);exit;

                                                             if($payId)

                                                             {

                                                                 

                                                                 $payment_mode_detail=array(

                                                                        'id_payment'        => $payId,

                                                                        'payment_amount'    => number_format($old_metal_amount,2,'.',''),

                                                                        'payment_mode'      => 'OG',

                                                                        'payment_status'    => 1,

                                                                        'payment_date'		=> date("Y-m-d H:i:s"),

                                                                        'created_time'	    => date("Y-m-d H:i:s"),

                                                                        'created_by'	    => $this->session->userdata('uid')

                                                                        );

                                                                   $this->$model->insertData($payment_mode_detail,'payment_mode_details'); 

                                                                   

                                                                 $old_metal_data=array('id_payment'=>$payId,'bill_id'=>$insId);

                                                                 

                                                                    $acdata = $this->payment_model->isAcnoAvailable($acc['id_scheme_account']); 

                                								    $scheme_acc_no = $this->$set_model->accno_generatorset();

                                									// scheme account number generate  based on one more settings Integ Auto//hh

                                									if(($scheme_acc_no['status']==1 && ($scheme_acc_no['schemeacc_no_set']==0 || $scheme_acc_no['schemeacc_no_set']==3)))

                                									{

                                			 			       	 	    $scheme_acc_number = $this->account_model->account_number_generator($acc['id_scheme'],$addData['id_branch']);

                                									    if($scheme_acc_number!=NULL)

                                										{

                                											$updateData['scheme_acc_number'] = $scheme_acc_number;

                                												$updateData['fixed_wgt'] = number_format(($old_metal_amount/$addData['goldrate_22ct']),3,'.','');



                                    									    if($acc['id_scheme_account'] > 0)

                                									        {

                                									             $updSchAc = $this->account_model->update_account($updateData,$acc['id_scheme_account']);

                                									        }

                                										}

                                									}

									

							                                     $old_metal_insert = $this->$model->insertData($old_metal_data,'payment_old_metal'); 

                                                             }

                        							    }

                							        }

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

            		 				    

            		 				    		//Repair Orders Update

        						    	if(!empty($repair_orders))

            							{

            							    //echo "<pre>";print_r($repair_orders);exit;

            								$repairOrderArray=array();

            								

            								//Update Ref No

            								$ref_no=$this->$model->generateRefNo($addData['id_branch'],'repair_del_ref_no','',$addData['ret_sales_type']);

            								$this->$model->updateData(array('repair_del_ref_no'=>$ref_no),'bill_id',$insId,'ret_billing');

            								if($addData['is_against_order'] ==2)

            								{

                                    				$order_from     = $addData['id_branch'];

                                    				$order_no       = $this->$ordermodel->generateOrderNo($order_from, 3);

                                    				$dCData         = $this->admin_settings_model->getBranchDayClosingData($addData['order_from']);

                                    				$order_datetime = ($dCData['entry_date'] == date("Y-m-d") ? date("Y-m-d H:i:s") : $dCData['entry_date']);

                                    				

                                     				$order = array( 

                                     				    'fin_year_code'     => $fin_year['fin_year_code'],

                                    	 				'order_type'		=> 3,

                                    	 				'order_from'		=> $order_from,

                                    	 				'order_no'          => $order_no,

                                    	 				'order_for'			=> 2,

                                    	 				'order_date'		=> $order_datetime,

                                    	 				'order_to'			=> (!empty($addData['bill_cus_id']) ? $addData['bill_cus_id'] :NULL ),

                                    					'createdon'         => date("Y-m-d H:i:s"),

                                    					'order_taken_by'    => $this->session->userdata('uid')

                                    				);

                                    				$insOrder = $this->$model->insertData($order,'customerorder');

                                    				//print_r($this->db->last_query());exit;

            								}

            								

            								foreach($repair_orders['is_est_details'] as $key => $val)

            								{

            								    $i=1;

            									if($repair_orders['is_est_details'][$key] == 1)

            									{

            									    if($addData['is_against_order'] ==2)

            									    {

            									         $orderDetails = array( 

                                    			 				'orderno'			=> $order_no."-".$i,

                                    			 				'id_customerorder'	=> $insOrder,

                                    			 				'ortertype'		    => 3,

                                    			 				'id_product'		=> $repair_orders['product'][$key],

                                    			 			    'id_repair_master'	=> $repair_orders['repair'][$key],

                                    			 				'totalitems'		=> $repair_orders['piece'][$key],

                                    			 				'weight'			=> $repair_orders['completed_weight'][$key],

                                    			 				'completed_weight'	=> $repair_orders['completed_weight'][$key],

                                    			 				'current_branch'	=> $order_from,

                                    			 				'orderstatus'	    => 5,

                                    			 				'rate'	            => $repair_orders['amount'][$key],

                                    							'id_employee'       => $this->session->userdata('uid'),

                                    						);

                                    						$id_orderdetails = $this->$model->insertData($orderDetails,'customerorderdetails');

                                    						//print_r($this->db->last_query());exit;

                                    						$i++;

            									    }else

            									    {

            									        $id_orderdetails = $repair_orders['id_orderdetails'][$key];

            									    }

            										$repairOrderArray=array(

        											'orderstatus'		    =>5,

        											'bill_id'			    =>$insId,

        											'total_cgst'			=>$repair_orders['cgst'][$key],

        											'total_sgst'			=>$repair_orders['sgst'][$key],

        											'total_igst'			=>(($repair_orders['igst'][$key]!='') ? $repair_orders['igst'][$key] : NULL),

        											'repair_percent'		=>$repair_orders['repair_percent'][$key],

        											'repair_tot_tax'		=>$repair_orders['repair_tot_tax'][$key],);

            										$status=$this->$model->updateData($repairOrderArray,'id_orderdetails',$id_orderdetails, 'customerorderdetails');

            									}

            								}

            							}

            							//Repair Orders Update

            							//echo "<pre>";print_r($supplier_sales_bill);exit;

            							if(!empty($supplier_sales_bill))

            							{

            							    $ref_no=$this->$model->generateRefNo($addData['id_branch'],'sales_ref_no',$addData['metal_type']);

                    						$this->$model->updateData(array('sales_ref_no'=>$ref_no),'bill_id',$insId,'ret_billing');

            							    foreach($supplier_sales_bill['catid'] as $key => $val)

                							{

                							    $arraySupplierBillSales= array(

            										'bill_id'               => $insId,

            										'pur_ret_cat_id'        => $supplier_sales_bill['catid'][$key],

            										'pur_ret_cat_pcs'       => $supplier_sales_bill['pcs'][$key],

            										'pur_ret_cat_gwt'       => $supplier_sales_bill['gross_wt'][$key],

            										'pur_ret_cat_leswt'     => $supplier_sales_bill['less_wt'][$key],

            										'pur_ret_cat_netwt'     => $supplier_sales_bill['net_wt'][$key],

            										'pur_ret_rate'          => $supplier_sales_bill['rate_per_gram'][$key],

            										'pur_ret_tax_rate'      => $supplier_sales_bill['pur_ret_tax_rate'][$key],

            										'pur_ret_tax_value'     => $supplier_sales_bill['total_tax'][$key],

            										'pur_ret_cgst'          => $supplier_sales_bill['cgst_cost'][$key],

            										'pur_ret_sgst'          => $supplier_sales_bill['sgst_cost'][$key],

            										'pur_ret_igst'          => $supplier_sales_bill['igst_cost'][$key],

            										'pur_ret_item_cost'     => $supplier_sales_bill['amount'][$key],

            										'calc_type'             => $supplier_sales_bill['caltype'][$key],

            									); 

            									$id_orderdetails = $this->$model->insertData($arraySupplierBillSales,'ret_bill_supplier_sales_details');

            										

                							}

                							$tagged_item_list = json_decode($addData['returntaggeditemlist'],true);

                							$nontagreturnitemlist = json_decode($addData['nontagreturnitemlist'],true);

                							if(sizeof($tagged_item_list)>0)

                							{

                							    foreach($tagged_item_list as $val)

                							    {

                							        $tag=$this->$model->get_tag_status($val['tag_id']);

                							        $tag_list= array(

            										    'bill_id'       => $insId,

                										'product_id'    => $tag['product_id'],

                										'design_id'     => $tag['design_id'],

                										'id_sub_design' => $tag['id_sub_design'],

                										'purity'        => $tag['purity'],

                										'piece'         => $val['piece'],

                										'gross_wt'      => $val['gross_wt'],

                										'net_wt'        => $val['net_wt'],

                										'tag_id'        => $val['tag_id'],

                									); 

                									$this->$model->insertData($tag_list,'ret_bill_details');

                									

                									$this->$model->updateData(array('tag_status'=>1),'tag_id',$val['tag_id'], 'ret_taging');



                                                    //Update Tag Log status

                                                    $tag_log=array(

                                                    'tag_id'	  =>$val['tag_id'],

                                                    'date'		  =>$bill_date,

                                                    'status'	  =>1,

                                                    'from_branch' =>$addData['id_branch'],

                                                    'to_branch'	  =>NULL,

                                                    'issuspensestock' => $addData['bill_type'] == 15 ? 1 : 0,

                                                    'created_on'  =>date("Y-m-d H:i:s"),

                                                    'created_by'  =>$this->session->userdata('uid'),

                                                    );

                                                    $this->$model->insertData($tag_log,'ret_taging_status_log');

                							    }

                							}

                							

                							if(sizeof($nontagreturnitemlist)>0)

                							{

                							    foreach($nontagreturnitemlist as $val)

                							    {

                							        $non_tag_list= array(

                										    'bill_id'       => $insId,

                    										'product_id'    => $val['id_product'],

                    										'design_id'     => $val['id_design'],

                    										'id_sub_design' => $val['id_sub_design'],

                    										'piece'         => $val['piece'],

                    										'gross_wt'      => $val['gross_wt'],

                    										'net_wt'        => $val['net_wt'],

                    										'tax_group_id'  => $val['tgrp_id'],

                    									); 

                    								$this->$model->insertData($non_tag_list,'ret_bill_details');

                    								

                                                    $existData=array('id_product'=>$val['id_product'],'id_design'=>$val['id_design'],'id_sub_design'=>$val['id_sub_design'],'id_branch'=>$addData['id_branch']);

                                                    $isExist = $this->$model->checkNonTagItemExist($existData);

                                                    if($isExist['status'] == TRUE)

                                                    {

                                                        $nt_data = array(

                                                        'id_nontag_item'=> $isExist['id_nontag_item'],

                                                        'no_of_piece'	=> ($val['piece']!='' ? $val['piece']:0),

                                                        'gross_wt'		=> $val['gross_wt'],

                                                        'net_wt'		=> $val['net_wt'],  

                                                        'less_wt'		=> 0,  

                                                        'updated_by'	=> $this->session->userdata('uid'),

                                                        'updated_on'	=> date('Y-m-d H:i:s'),

                                                        );

                                                        $this->$model->updateNTData($nt_data,'-');

                                                        $non_tag_data=array(

                                                        'from_branch'	=> $addData['id_branch'],

                                                        'to_branch'	    => NULL,

                                                        'no_of_piece'   => $val['piece'], 

                                                        'less_wt' 		=> 0,

                                                        'net_wt' 		=> $val['net_wt'], 

                                                        'gross_wt' 		=> $val['gross_wt'], 

                                                        'product'		=> $val['id_product'],

                                                        'design'		=> $val['id_design'],

                                                        'date'  	    => $bill_date,

                                                        'created_on'  	=> date("Y-m-d H:i:s"),

                                                        'created_by'   	=> $this->session->userdata('uid'),	

                                                        'status'   		=> 1,

                                                        'bill_id'       => $insId

                                                        );

                                                        $this->$model->insertData($non_tag_data,'ret_nontag_item_log');

                                                    }

            												

                							    }

                							    

                							}

            							}

            							

            							

            		 				    //Update Credit Status

            						}

            						

            						//Update roundoff amount by fetching the billing details information and update in billing table

            						

            						

            						$billDetails = $this->$model->getBillDetailsData($insId);

            						if(!empty($billDetails)){

            						    $total_amount = $billDetails['itemwithtax'];

            						    $final_cost     = number_format($total_amount, 0, '.', '');

            						    $round_val      = number_format(($final_cost - $total_amount), 2, '.', '');

            						    $this->$model->updateData(array('round_off_amt' => $round_val),'bill_id', $insId, 'ret_billing');

            						}

            						

            						

            						if($this->db->trans_status()===TRUE)

            						{

            							$this->db->trans_commit();

                                        $est_oth_inv = (isset($_POST['est_oth_inv']) ?$_POST['est_oth_inv']:'');

        								if(!empty($est_oth_inv))

        								{

            						    	foreach($est_oth_inv['id_other_item'] as $key => $val)

            						    	{

            						    	    $insData=array(

                                                    'id_other_item'     =>$est_oth_inv['id_other_item'][$key],

                                                    'issue_form'        =>1,

                                                    'issue_date'        =>$bill_date,

                                                    'bill_id'           =>$insId,

                                                    'no_of_pieces'      =>$est_oth_inv['no_of_pcs'][$key],

                                                    'id_branch'         =>$addData['id_branch'],

                                                    'created_on'        =>date("Y-m-d H:i:s"),

                                                    'created_by'        =>$this->session->userdata('uid')

                                                );

                                                $otherInvIssue =$this->$model->insertData($insData,'ret_other_invnetory_issue');

                                                if($otherInvIssue)

                                                {

                                                    $inventoryItem=$this->$model->get_InventoryCategory($est_oth_inv['id_other_item'][$key]);

                                                    $itemDetails    = $this->$model->get_other_inventory_purchase_items_details($est_oth_inv['id_other_item'][$key],$addData['id_branch'],$inventoryItem['issue_preference'],$est_oth_inv['no_of_pcs'][$key]);

                                                    foreach($itemDetails as $items)

                                                    {

                                                        $updData=array(

                                                                      'id_inventory_issue'=>$otherInvIssue,

                                                                      'status'            =>1

                                                                      );

                                                        $this->$model->updateData($updData,'pur_item_detail_id',$items['pur_item_detail_id'],'ret_other_inventory_purchase_items_details');

                                                    }

                                                    $logData=array(

                                                        'item_id'      =>$est_oth_inv['id_other_item'][$key],

                                                        'no_of_pieces' =>$est_oth_inv['no_of_pcs'][$key],

                                                        'amount'       =>0,

                                                        'date'         =>$bill_date,

                                                        'status'       =>1,

                                                        'from_branch'  =>$addData['id_branch'],

                                                        'to_branch'    =>NULL,

                                                        'created_on'   =>date("Y-m-d H:i:s"),

                                                        'created_by'   =>$this->session->userdata('uid')

                                                    );

                                                    $this->$model->insertData($logData,'ret_other_inventory_purchase_items_log'); 

                                                }

            						    	}

        								}

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

            						/*	$log_path = 'billing_log/'.$insId.'/';

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

            							file_put_contents($log_path,$log_detail,FILE_APPEND | LOCK_EX);*/

            							$this->session->set_flashdata('chit_alert',array('message'=>'Billing added successfully','class'=>'success','title'=>'Add Billing'));

            							//$this->session->unset_userdata('FORM_SECRET');

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

        		            //$this->session->unset_userdata('FORM_SECRET');

					    }

					    else

					    {

					         $return_data=array('status'=>FALSE,'id'=>'');

            				 $this->session->set_flashdata('chit_alert',array('message'=>'Unable To Proceed Your Request.Invalid Form Submit','class'=>'danger','title'=>'Add Billing')); 

            				 echo json_encode($return_data);

					     }

					}

				 else{

	                $return_data=array('status'=>FALSE,'id'=>'');

    				$this->session->set_flashdata('chit_alert',array('message'=>'Form Already Submitted','class'=>'danger','title'=>'Add Billing')); 

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

				    break;

			case 'ajaxapprovallist': 

			           	$list = $this->$model->ajax_getApprovalBillingList($_POST);	 

			           	$profile = $this->admin_settings_model->profileDB("get",$this->session->userdata('profile'));

					  	$access = $this->admin_settings_model->get_access('admin_ret_billing/billing/approvallist');

				        $data = array(

				        					'list'  => $list,

											'access'=> $access,

											'profile'=> $profile

				        				);  

						echo json_encode($data);

					 break;

			default: 

			           	$list = $this->$model->ajax_getBillingList($_POST);	 

			           	$profile = $this->admin_settings_model->profileDB("get",$this->session->userdata('profile'));

					  	$access = $this->admin_settings_model->get_access('admin_ret_billing/billing/list');

				        $data = array(

				        					'list'  => $list,

											'access'=> $access,

											'profile'=> $profile

				        				);  

						echo json_encode($data);

		}

	}

	

	public function createNewCustomer(){

		$model = "ret_billing_model";

		if(!empty($_POST['cusName']) && !empty($_POST['cusMobile']) && !empty($_POST['cusBranch'])){

			$data = $this->$model->createNewCustomer($_POST['cusName'], $_POST['cusMobile'], $_POST['cusBranch'],$_POST['id_village'],$_POST['id_country'],$_POST['id_state'],$_POST['id_city'],$_POST['address1'],$_POST['address2'],$_POST['address3'],$_POST['pincode'],($_POST['mail'] != '' ? $_POST['mail'] :null),$_POST['cus_type'],$_POST['pan_no'],$_POST['aadharid'],$_POST['gst_no']?$_POST['gst_no']:null);
            if(isset($_FILES['cust_img']['name']))	
	        { 
				$img_path="assets/img/customer/".$data['response']['id_customer']."/customer.jpg";
		     	$path="assets/img/customer/".$data['response']['id_customer'];
    		      if($this->set_image($data['response']['id_customer'],$img_path,'cust_img',$path )){
    				$data['image_upload']=$img_path;
    			  } 
	        }
			echo json_encode($data);

		}else{

			echo json_encode(array("success" => FALSE, "response" => array(), "message" => "Please fill all the required fields"));

		}

	}

	

	public function updateNewCustomer(){

		$model = "ret_billing_model";

		if(!empty($_POST['cusName']) && !empty($_POST['cusMobile']) && !empty($_POST['cusBranch'])){

			//die;

			$data = $this->$model->updateNewCustomer($_POST['id_customer'],$_POST['cusName'], $_POST['cusMobile'], $_POST['cusBranch'],$_POST['id_village'],$_POST['id_country'],$_POST['id_state'],$_POST['id_city'],$_POST['address1'],$_POST['address2'],$_POST['address3'],$_POST['pincode'],($_POST['mail'] != '' ? $_POST['mail'] :''),$_POST['cus_type'],$_POST['pan_no'],$_POST['aadharid'],$_POST['gst_no']?$_POST['gst_no']:null);
            
            if(isset($_FILES['cust_img']['name']))	
	        { 
				$img_path="assets/img/customer/".$_POST['id_customer']."/customer.jpg";
		     	$path="assets/img/customer/".$_POST['id_customer'];
    		      if($this->set_image($_POST['id_customer'],$img_path,'cust_img',$path )){
    				$data['image_upload']=$img_path;
    			  } 
	        }
			echo json_encode($data);

		}else{

			echo json_encode(array("success" => FALSE, "response" => array(), "message" => "Please fill all the required fields"));

		}

	}

	

	public function getEstimationDetails(){

		$model = "ret_billing_model";

		if((!empty($_POST['estId']) || !empty($_POST['tag_code']) || !empty($_POST['order_no'])) && !empty($_POST['billType'])){

			$data = $this->$model->getEstimationDetails($_POST['estId'], $_POST['billType'], $_POST['id_branch'], $_POST['order_no'],$_POST['fin_year'],$_POST['metal_type'],$_POST['tag_code']);

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

	public function getEstimationDetailsTags(){

		$model = "ret_billing_model";

		if((!empty($_POST['estId']) || !empty($_POST['order_no'])) && !empty($_POST['billType'])){

			$data = $this->$model->getEstimationDetailsTags($_POST['estId'], $_POST['billType'], $_POST['id_branch'], $_POST['order_no'],$_POST['fin_year'],$_POST['metal_type']);

			if(sizeof($data['item_details'])>0  )

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

		$id_customer=(isset($_POST['id_customer']) ? $_POST['id_customer']:'');

		$data=$this->$model->get_closed_accounts($searchTxt,$id_customer);

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

		$data = $this->$model->getBillData($_POST['billNo'], $_POST['billType'], $_POST['id_branch'],$_POST['fin_year'],$_POST['metal_type']);

		if(sizeof($data['item_details'])>0){

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

		$data['receiptDetails'] =  $this->$model->get_billing_advance_details($id);

		//echo "<pre>"; print_r($data); echo "</pre>";exit;

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

		$html = $this->load->view('billing/print/receipt_billing', $data,true);

		echo $html;exit;

		/*$dompdf = new DOMPDF();

	    $dompdf->load_html($html);

		$dompdf->set_paper("a4", "portriat" );

		$dompdf->render();

		$dompdf->stream("Receipt.pdf",array('Attachment'=>0));*/

	}

	

	

	function repair_order_thermal_print($id)

	{

		$model="ret_billing_model";

		$data['billing'] = $this->$model->getBillingDetails($id);

		

		$data['payment'] = $this->$model->getPaymentDetails($id);

		$data['metal_rate'] = $this->$model->getBillingMetalrate($data['billing']['id_branch'],$data['billing']['bill_date']);

		$data['repair_details'] = $this->$model->get_repair_item_details($id);

		$data['comp_details']=$this->$model->getCompanyDetails($data['billing']['id_branch']);

		$data['settings']		= $this->$model->get_retSettings();

		$data['receiptDetails'] =  $this->$model->get_billing_advance_details($id);

		//echo "<pre>"; print_r($data); echo "</pre>";exit;

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

        $dompdf = new DOMPDF();

		$html = $this->load->view('billing/print/thermal_print', $data,true);

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

			/*case 'save':

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

			break;*/

			case 'save':

				$addData=$_POST['issue'];

				$payment=$_POST['payment'];

				$card_pay_details	= json_decode($payment['card_pay'],true);

				$cheque_details	    = json_decode($payment['chq_pay'],true);

				$net_banking_details = json_decode($payment['net_bank_pay'],true);

				$multiple_receipt = json_decode($addData['multiple_receipt_id'],true);

				// echo "<pre>"; print_r($multiple_receipt);exit;

				$bill_no = $this->$model->bill_no_generate($addData['id_branch']);

			    $dCData = $this->admin_settings_model->getBranchDayClosingData($addData['id_branch']);

				$bill_date = ($dCData['entry_date'] == date("Y-m-d") ? date("Y-m-d H:i:s") : $dCData['entry_date']);

				$fin_year       = $this->$model->get_FinancialYear();

				$insData=array(

				    'fin_year_code' => $fin_year['fin_year_code'],

				    'bill_no'	    => $bill_no,

				    'bill_date'     => $bill_date,

					'type'          => 1,

					'id_branch'     => $addData['id_branch'],

					'mobile'        => $addData['mobile'],

					'name'          => $addData['barrower_name'],

					'amount'        => $addData['amount'],

					'issue_to'      => $addData['issue_to'],

					'issue_type'    => $addData['issue_type'],

					'id_customer'   => ($addData['id_customer']!='' ? $addData['id_customer'] :NULL),

					'id_employee'   => $this->session->userdata('uid'),

					'id_acc_head'   => ($addData['id_acc_head']!='' ? $addData['id_acc_head'] :NULL),

					'narration'	    => ($addData['narration']!='' ?$addData['narration'] :NULL),

					'created_by'    => $this->session->userdata('uid'),

					'counter_id'    => ($this->session->userdata('counter_id')!='' ? $this->session->userdata('counter_id'):NULL),

					'refno'         => !empty($addData['refno']) ? $addData['refno'] : NULL ,

					'created_on'    => date("Y-m-d H:i:s"),

					);

				$this->db->trans_begin();

				$updData=$_POST['payment'];

	 			$insId = $this->$model->insertData($insData,'ret_issue_receipt');

				if(sizeof($multiple_receipt)>0)

				{

					foreach($multiple_receipt as $refund)

					{

						$refundData=array(

						                 'id_issue_receipt' =>$insId,

						                 'refund_receipt'   =>$refund['id_issue_receipt'],

						                 'refund_amount'    =>$refund['amount'],

						                 );

						 $this->$model->insertData($refundData,'ret_advance_refund');

					}

				}

	 			if($insId)

	 			{

	 				$updData=$_POST['payment'];

	 				if($updData['cash_payment']!='')

	 				{

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

	 				}

	 				if(sizeof($card_pay_details)>0)

	 					{

	 						foreach($card_pay_details as $card_pay)

		 					{

								$arrayCardPay[]=array(

								'id_issue_rcpt'	=>$insId,

								'card_type'     =>$card_pay['card_name'],

								'payment_amount'=>$card_pay['card_amt'],

								'payment_status'=>1,

								'type'			=>2,

								'payment_date'	=>date("Y-m-d H:i:s"),

								'payment_mode'	=>($card_pay['card_type']==1 ?'CC':'DC'),

								'card_no'		=>($card_pay['card_no']!='' ? $card_pay['card_no']:NULL),

								'card_no'		=>($card_pay['ref_no']!='' ? $card_pay['ref_no']:NULL),

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

		 					    $cheque_deposit_date = ($chq_pay['cheque_date']!='' ?date_create($chq_pay['cheque_date']) :NULL);

            			 		$cheque_date = ($chq_pay['cheque_date']!='' ? date_format($cheque_deposit_date,"Y-m-d"):NULL);

								$arraychqPay[]=array(

									'id_issue_rcpt'	=>$insId,

									'payment_amount'=>$chq_pay['payment_amount'],

									'payment_status'=>1,

									'type'			=>2,

									'payment_date'	=>date("Y-m-d H:i:s"),

									'cheque_date'		=>($chq_pay['cheque_date']!='' ? $cheque_date:NULL),

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

									'id_issue_rcpt'	    =>$insId,

									'payment_amount'    =>$nb_pay['amount'],

									'payment_status'    =>1,

									'type'			    =>2,

									'payment_date'	    =>date("Y-m-d H:i:s"),

									'payment_mode'	    =>'NB',

									'payment_ref_number'=>($nb_pay['ref_no']!='' ? $nb_pay['ref_no']:NULL),

									'NB_type'           =>($nb_pay['nb_type']!='' ? $nb_pay['nb_type']:NULL),

									'net_banking_date'  =>($nb_pay['nb_date']!='' ? $nb_pay['nb_date']:date("Y-m-d")),

									'id_bank'           =>($nb_pay['id_bank']!='' && $nb_pay['id_bank']!=null ? $nb_pay['id_bank']:NULL),

									'created_time'	    => date("Y-m-d H:i:s"),

									'created_by'	    => $this->session->userdata('uid')

								);

	 						}

		 						if(!empty($arrayNBPay)){

									$NbPayInsert = $this->$model->insertBatchData($arrayNBPay,'ret_issue_rcpt_payment'); 

								}

	 					}

						if($addData['issue_type']==3)

						{							

							$this->$model->updateWalletData(array('amount'=>$addData['amount'],'weight'=>0,'id_customer'=>$addData['id_customer']),'-');

						}

						// echo "<pre>";print_r($multiple_receipt);exit;

						foreach($multiple_receipt as $val)

						{

							if($addData['issue_type']==3)

							{

								$insWallet=array(

								'id_ret_wallet'		=>$addData['id_ret_wallet'],

								'id_issue_receipt'	=>$val['id_issue_receipt'],

								'amount'			=>$val['amount'],

								'transaction_type'	=>1,

								'created_by' 		=>$this->session->userdata('uid'),

								'created_on' 		=> date("Y-m-d H:i:s"),

								'remarks'	 		=>'Advace Refund Amount'

								);

								$this->$model->insertData($insWallet,'ret_wallet_transcation');

							}

							// echo "<pre>"; print_r($insWallet);

						}

						// exit;

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

					

					$data['issue']=$this->$model->get_issue_details($id);

					$data['comp_details']=$this->$model->getCompanyDetails($data['issue']['id_branch']);

					$data['metal_rate']=$this->$model->get_branchwise_rate($data['issue']['id_branch']);

					$data['payment'] = $this->$model->get_receipt_payment($id);

					$data['receipt_adv_details'] = $this->$model->get_receipt_advance_details($id);

					//echo "<pre>"; print_r($data);exit;

					$this->load->helper(array('dompdf', 'file'));

					$dompdf = new DOMPDF();

					$html = $this->load->view('billing/issueReceipt/print/issue', $data,true);

					$dompdf->load_html($html);

					$dompdf->set_paper("a4", "portriat" );

					$dompdf->render();

					$dompdf->stream("Receipt.pdf",array('Attachment'=>0));

			break;

			case 'cancel':

					$model="ret_billing_model";

					$data = array(

					'bill_status' => 2,  

					'updated_by'	=> $this->session->userdata('uid'),

					'updated_on'	=> date('Y-m-d H:i:s'));

					

					$this->db->trans_begin();

					

					$status=$this->$model->updateData($data,'id_issue_receipt',$_POST['id_issue_receipt'], 'ret_issue_receipt');

					

					if($this->db->trans_status()===TRUE)

					{

						$this->db->trans_commit();

						$return_data=array('status'=>TRUE,'message'=>'Payment Cancelled Successfully');

						

					}

					else

					{

						$this->db->trans_rollback();

						$return_data=array('status'=>FALSE,'message'=>'Unable to proceed the requested process');

						echo $this->db->_error_message()."<br/>";					   

						echo $this->db->last_query();exit;						 	

						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Cancel Payment'));

					}

					

					echo json_encode($return_data);

			break;

			default: 

					  	$list = $this->$model->ajax_getIssuetist($_POST);	 

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

				$addData    = $_POST['receipt'];

				$payment    = $_POST['payment'];

				$card_pay_details	= json_decode($payment['card_pay'],true);

				$cheque_details	    = json_decode($payment['chq_pay'],true); 

				$net_banking_details = json_decode($payment['net_bank_pay'],true);

				$purchase           = $_POST['purchase'];

				/*echo "<pre>"; print_r($addData);	echo "</pre>";

				echo "<pre>"; print_r($payment);	echo "</pre>";

				exit;*/

				$amount=0;

				$weight=0;

				$metal_rate=$this->$model->get_branchwise_rate($addData['id_branch']);

				$bill_no = $this->$model->bill_no_generate($addData['id_branch']);

			    $dCData = $this->admin_settings_model->getBranchDayClosingData($addData['id_branch']);

				$bill_date = ($dCData['entry_date'] == date("Y-m-d") ? date("Y-m-d H:i:s") : $dCData['entry_date']);

				

				$receipt_date = isset($addData['receipt_date']) ? date("Y-m-d", strtotime($addData['receipt_date']))." ".date("H:i:s") : $bill_date;





				$fin_year       = $this->$model->get_FinancialYear();

				if($addData['amount'] > 0)

				{

					if($addData['store_receipt_as']==1)

					{

						$amount = $addData['amount'];

						//$amount = $payment['tot_amt_received'];

					}else if($addData['store_receipt_as'] == 2)

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

						/*if($addData['rate_calc']==1)

						{

							$amount=$addData['weight']*$metal_rate['goldrate_22ct'];

						}else{

							$amount=$addData['weight']*$metal_rate['silverrate_1gm'];	

						}*/

						

						$amount=$addData['amount'];

						

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

					'bill_date' 	=> ($addData['receipt_type']==6 ?$receipt_date :$bill_date),

					'fin_year_code' => $fin_year['fin_year_code'],

					'receipt_for'   => (!empty($addData['receipt_ref_id']) ? $addData['receipt_ref_id'] : NULL ),

				);

				//echo "<pre>"; print_r($insData);exit;

				$this->db->trans_begin();

				$insId = $this->$model->insertData($insData,'ret_issue_receipt');

				//print_r($this->db->last_query());exit;

				if($insId)

	 			{

	 			    

	 			       if($addData['pan_no']!='')

                    	{

                    		$this->$model->updateData(array('pan'=>strtoupper($addData['pan_no'])),'id_customer',$addData['id_customer'],'customer');

                    	}

                    	

	 			        //Update Credit status

        	 			if($addData['receipt_type']==1)

         				{      

         				       $multiple_receipt = json_decode($addData['multiple_receipt_id'],true);

         				       

         					   if(sizeof($multiple_receipt)>0)

                				{

                					foreach($multiple_receipt as $val)

                					{

                						$creditDetails = array(

                							'id_issue_receipt'	=> $insId,

                							'receipt_for'		=> $val['id_issue_receipt'],

                							'received_amount'	=> $val['payable_amount'],

                							'discount_amt'	    => $val['discount_amt'],

                						);

                						$creditStatus = $this->$model->insertData($creditDetails,'ret_issue_credit_collection_details');

                						

                						if($creditStatus)

                						{

                						    $balance_amout = ($val['issue_amt']-$val['paid_amt']+$val['payable_amount']+$val['discount_amt']);

                						    

                						    if($balance_amout==0)

                						    {

                						        $this->$model->updateData(array('is_collect'=>1),'id_issue_receipt',$val['id_issue_receipt'],'ret_issue_receipt');

                						    }

                						    

                						    $wallet=$this->$model->get_retWallet_details($addData['id_customer']);

                        	 				if($wallet['status'])

                        	 				{

                        	 				    $this->$model->updateWalletData(array('amount'=>$val['payable_amount']+$val['discount_amt'],'weight'=>0,'id_customer'=>$addData['id_customer']),'+');

                        						$insWallet=array(

                        						'id_ret_wallet'		=>$wallet['id_ret_wallet'],

                        						'id_issue_receipt'	=>$insId,

                        						'amount'			=>($val['payable_amount']+$val['discount_amt']),

                        						'transaction_type'	=>0,

                        						'created_by' 		=>$this->session->userdata('uid'),

                        						'created_on' 		=> date("Y-m-d H:i:s"),

                        						'remarks'	 		=>'CREDIT COLLECTION AMOUNT'

                        						 );

                        						$this->$model->insertData($insWallet,'ret_wallet_transcation');

                        						//echo "<pre>"; print_r($metal_rate);exit;

                        	 				}

        	 				

                						}

                					}

                				}

                				

                				//Advance Adjusement

    		 					$advance_adj_details = json_decode($addData['advance_muliple_receipt'],true);

    		 					//print_r($advance_adj_details);exit;

            					if(sizeof($advance_adj_details)>0)

            					{

                					foreach($advance_adj_details as $obj)

                					{

                					    $advance_amt+=$obj->adj_amount;

                                        $id_ret_wallet      = $obj->id_ret_wallet;

                                        $data_adv_amount    = array(

                                                                    'id_issue_receipt' => $insId,

                                                                    'receipt_for'      => $obj['id_issue_receipt'],

                                                                    'adjusted_amt'     => $obj['adj_amount']

                                                                );

                                        $insId_adv_amount = $this->$model->insertData($data_adv_amount,'ret_issue_receipt_advance_adj');

                                        //print_r($this->db->last_query());exit;

                                    }

                                    

                                    if($insId_adv_amount)

                                    {

                                        //$this->$model->updateWalletData(array('amount'=>$advance_amt,'weight'=>0,'id_customer'=>$addData['id_customer']),'-');

                                    }

    

            					}

            					//Advance Adjusement

            					

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

        							

        							$cus_pan_folder =  self::CUS_IMG_PATH.'/'.$addData['id_customer'];

        							

        							if (!is_dir($folder)) {

        								mkdir($folder, 0777, TRUE);

        							}

        							

        							if (!is_dir($cus_pan_folder)) {

        								mkdir($cus_pan_folder, 0777, TRUE);

        							}

        							

        							foreach($_FILES['pan_img'] as $file_key => $file_val){

        							if($file_val['tmp_name'])

        							{

        							// unlink($folder."/".$product['image']); 

        								$img_name       =   "P_". mt_rand(120,1230).".jpg";

        								$cus_pan_path   =   $cus_pan_folder."/".'pan.jpg'; 

        								$path           =   $folder."/".$img_name; 

        								$result         =   $this->upload_img('image',$path,$file_val['tmp_name']);

        								$this->upload_img('image',$cus_pan_path,$file_val['tmp_name']);

        								if($result){

        								    $pan_imgs = strlen($pan_imgs) > 0 ? $pan_imgs."#".$img_name : $img_name;

        								}

        								}

        							}

        							$this->$model->updateData(array('pan_image'=>$pan_imgs),'id_issue_receipt',$insId,'ret_issue_receipt');

        							$this->$model->updateData(array('pan_proof'=>$pan_imgs),'id_customer',$addData['id_customer'],'customer');



        						}

        				//pan images

         				//Insert and Update ret wallet

        	 		    if($addData['receipt_type']==2 && $addData['id_customer']!='')

        	 			{

        	 				$wallet=$this->$model->get_retWallet_details($addData['id_customer']);

        	 				if($wallet['status'])

        	 				{

        	 				    $this->$model->updateWalletData(array('amount'=>$amount,'weight'=>$weight,'id_customer'=>$addData['id_customer']),'+');

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

        	 				   $wallet_acc=array(

        						'id_customer'=>$addData['id_customer'],

        						'amount'=>$amount,

        						'weight'=>$weight,

        	 				   	'created_by'=>$this->session->userdata('uid'),

        						'created_time' => date("Y-m-d H:i:s")

        						);

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

        								'card_type'         =>$card_pay['card_name'],

        								'payment_amount'    =>$card_pay['card_amt'],

        								'id_pay_device'     =>($card_pay['id_device']!='' ? $card_pay['id_device']:NULL),

        								'payment_status'    =>1,

        								'payment_date'		=>date("Y-m-d H:i:s"),

        								'payment_mode'	    =>($card_pay['card_type']==1 ?'CC':'DC'),

        								'card_no'		    =>($card_pay['card_no']!='' ? $card_pay['card_no']:NULL),

        								'payment_ref_number'=>($card_pay['ref_no']!='' ? $card_pay['ref_no']:NULL),

        								'created_time'	    => date("Y-m-d H:i:s"),

        								'created_by'	    => $this->session->userdata('uid')

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

        	 					    $cheque_deposit_date = ($chq_pay['cheque_date']!='' ?date_create($chq_pay['cheque_date']) :NULL);

            			 			$cheque_date = ($chq_pay['cheque_date']!='' ? date_format($cheque_deposit_date,"Y-m-d"):NULL);

        							$arraychqPay[]=array(

        								'id_issue_rcpt'		=>$insId,

        								'payment_amount'=>$chq_pay['payment_amount'],

        								'payment_status'=>1,

        								'payment_date'		=>date("Y-m-d H:i:s"),

        								'cheque_date'		=>($chq_pay['cheque_date']!='' ? $cheque_date:NULL),

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

        								'payment_amount'    =>$nb_pay['amount'],

        								'payment_status'    =>1,

        								'payment_date'		=>date("Y-m-d H:i:s"),

        								'payment_mode'	    =>'NB',

        								'id_pay_device'     =>($nb_pay['id_device']!='' ? $nb_pay['id_device']:NULL),

        								'payment_ref_number'=>($nb_pay['ref_no']!='' ? $nb_pay['ref_no']:NULL),

        								'NB_type'           =>($nb_pay['nb_type']!='' ? $nb_pay['nb_type']:NULL),

        								'net_banking_date'  =>($nb_pay['nb_date']!='' ? $nb_pay['nb_date']:date("Y-m-d")),

        								'id_bank'           =>($nb_pay['id_bank']!='' && $nb_pay['id_bank']!=null ? $nb_pay['id_bank']:NULL),

        								'created_time'	    => date("Y-m-d H:i:s"),

        								'created_by'	    => $this->session->userdata('uid')

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

        					$billSales = (isset($_POST['estsales']) ? $_POST['estsales']:'');

        					//echo "<pre>"; print_r($billSales);exit;

        						if(!empty($billSales)){

        							$arraySalesBill = array();

        							foreach($billSales['esti_detail_id'] as $key => $val)

        							{

        								$arraySalesBill= array(

            								'adv_rcpt_issue_receipt_id' => $insId, 

            								'adv_rcpt_tagid'            => $billSales['tag_id'][$key], 

            								'adv_rcpt_esti_detail_id'   => $billSales['esti_detail_id'][$key]

        								); 

        								if(!empty($arraySalesBill))

        								{

            								$estitagsadv = $this->$model->insertData($arraySalesBill,'ret_adv_receipt_tags');

            								

            								if($billSales['tag_id'][$key]!='')

                					        {

                					           $this->$model->updateData(array('tag_status'=>11,'updated_time'=>date("Y-m-d H:i:s"),'updated_by'=>$this->session->userdata('uid')),'tag_id',$billSales['tag_id'][$key], 'ret_taging');

                					           $log_data=array(

                								'tag_id'	          =>$billSales['tag_id'][$key],

                								'date'		          =>$bill_date,

                								'status'	          =>1,

                								'issuspensestock'	  =>1,

                								'from_branch'         =>$addData['id_branch'],

                								'to_branch'           =>NULL,

                								'created_on'          =>date("Y-m-d H:i:s"),

                								'created_by'          =>$this->session->userdata('uid'),

                								);

                								$this->$model->insertData($log_data,'ret_taging_status_log'); //Update Tag lot status

                					        }

					        

            							}

        							}

        						}

        					if($addData['receipt_as']==2)

        					{

            					if(!empty($purchase))

            					{

            							$arrayEstBill = array();

            							foreach($purchase['old_metal_sale_id'] as $key => $val)

            							{

            								$arrayEstBill= array(

                								'id_issue_receipt'     => $insId, 

                								'est_old_metal_sale_id'=> $purchase['old_metal_sale_id'][$key], 

            								); 

            								if(!empty($arrayEstBill))

            								{

                								$esti_wt_adv = $this->$model->insertData($arrayEstBill,'ret_adv_receipt_weight');

                								if($esti_wt_adv)

                								{

                								    $this->$model->updateData(array('purchase_status'=>3),'old_metal_sale_id',$purchase['old_metal_sale_id'][$key], 'ret_estimation_old_metal_sale_details');

                								    

                								    $old_metal_log=array(

            									                             'id_issue_receipt'=>$insId,

            									                             'from_branch'      =>NULL,

            									                             'to_branch'        =>$addData['id_branch'],

            									                             'status'           =>1,

            									                             'item_type'        =>7, // Old Metal

            									                             'date'             =>$bill_date,

            									                             'created_on'       =>date("Y-m-d H:i:s"),

            													             'created_by'      =>$this->session->userdata('uid'),

            									                            );

            									       $this->$model->insertData($old_metal_log,'ret_purchase_items_log');

                								}

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

					

					$data['issue']=$this->$model->get_receipt_details($id);

					$data['comp_details']=$this->$model->getCompanyDetails($data['issue']['id_branch']);

					$data['payment'] = $this->$model->get_receipt_payment($id);

					$data['metal_rate']=$this->$model->get_branchwise_rate($data['issue']['id_branch']);

					$data['advance_adj_details']=$this->$model->get_receipt_advance_adj_details($id);

					//echo "<pre>"; print_r($data);exit;

					$this->load->helper(array('dompdf', 'file'));

					$dompdf = new DOMPDF();

					$html = $this->load->view('billing/issueReceipt/print/issue', $data,true);

					$dompdf->load_html($html);

					$dompdf->set_paper("a4", "portriat" );

					$dompdf->render();

					$dompdf->stream("Receipt.pdf",array('Attachment'=>0));

			break;

			case 'cancel':

					$model="ret_billing_model";

					$data = array(

					'bill_status' => 2,  

					'updated_by'	=> $this->session->userdata('uid'),

					'updated_on'	=> date('Y-m-d H:i:s'));

					

					$this->db->trans_begin();

					

					$status=$this->$model->updateData($data,'id_issue_receipt',$_POST['id_issue_receipt'], 'ret_issue_receipt');

					

					$receipt_det=$this->$model->get_receipt_details($_POST['id_issue_receipt']);

					

					if($receipt_det['rct_type']==2) // Update in Wallet

					{

					    $this->$model->updateWalletData(array('amount'=>$receipt_det['amount'],'weight'=>0,'id_customer'=>$receipt_det['id_customer']),'-');

					}

					

					

					if($receipt_det['rct_type']==2 && $receipt_det['rct_as']==2) // For Weight Advance

					{

					    $advDetails=$this->$model->get_est_adv_details($_POST['id_issue_receipt']);

					    foreach($advDetails as $adv)

					    {

					        $this->$model->updateData(array('purchase_status'=>0),'old_metal_sale_id',$adv['est_old_metal_sale_id'],'ret_estimation_old_metal_sale_details');

					    }

					}

					

					$advance_tag_details = $this->$model->get_est_adv_tag_details($_POST['id_issue_receipt']);

					if(sizeof($advance_tag_details) > 0)

					{

					    foreach($advance_tag_details as $adv)

					    {

					        $this->$model->updateData(array('purchase_status'=>0,'bil_detail_id'=>NULL),'est_item_id',$adv['adv_rcpt_esti_detail_id'], 'ret_estimation_items');

					        

					        $this->$model->updateData(array('tag_status'=>0,'updated_time'=>date("Y-m-d H:i:s"),'updated_by'=>$this->session->userdata('uid')),'tag_id',$adv['adv_rcpt_tagid'], 'ret_taging');



					    }

					}

					

					

					

					

					

					if($this->db->trans_status()===TRUE)

					{

						$this->db->trans_commit();

						$return_data=array('status'=>TRUE,'message'=>'Receipt Cancelled Successfully');

						

					}

					else

					{

						$this->db->trans_rollback();

						$return_data=array('status'=>FALSE,'message'=>'Unable to proceed the requested process');

						echo $this->db->_error_message()."<br/>";					   

						echo $this->db->last_query();exit;						 	

						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Add Receipt'));

					}

					

					echo json_encode($return_data);

			break;

			default: 

					  	$list = $this->$model->ajax_getReceiptlist($_POST);	 

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

 	

 	public function get_customer_advance_details()

 	{

 		$model= "ret_billing_model";

 		$id_customer=$this->input->post('id_customer');

		$data =$this->$model->get_receipt_refund($id_customer);

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

					    

					    //Receipt Revert

					    $irUpdata=array('bill_status'=>2);

                        $this->$model->updateData($irUpdata,'deposit_bill_id',$bill_id,'ret_issue_receipt');

                        //Receipt Revert

                        

                        

                        

                        

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

								'to_branch'   =>$bill['current_branch'],

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

					    

					    //CHIT DEPOSIT REVERT

					    $bill_details=$this->$model->getBillingDetails($bill_id);

					    if($bill_details['make_as_advance']==2)

					    {

					        $PayDetails=$this->$model->getChitPayDetails($bill_id);

					        if($PayDetails['id_payment']!='')

					        {

					            $this->$model->updateData(array('payment_status'=>4),'id_payment',$PayDetails['id_payment'], 'payment');

					        }

					    }

					    //CHIT DEPOSIT REVERT

					    

					    //REPAIR ORDER REVERT

					    $billing = $this->$model->getBillingDetails($bill_id);

					    if($billing['bill_type']==11) // repair bill type

					    {

					        $this->$model->updateData(array('orderstatus'=>4),'bill_id',$bill_id, 'customerorderdetails');

					        //print_r($this->db->last_query());exit;

					    }

					    //REPAIR ORDER REVERT

					    

					    //CUSTOMER ORDER REVERT

					    $cus_order_details = $this->$model->get_order_id_details($bill_id);

					    if(sizeof($cus_order_details) > 0)

					    {

					         foreach($cus_order_details as $ord)

    					    {

    					        $this->$model->updateData(array('orderstatus'=>4),'id_orderdetails',$ord['id_orderdetails'], 'customerorderdetails');

    					    }

    					    $this->$model->updateData(array('is_adavnce_adjusted'=>0),'adjusted_bill_id',$bill_id,'ret_billing_advance');

					    }

					   

					    //CUSTOMER ORDER REVERT

					    

					    // Credit Collection Cancel

                        if($billing['bill_type']==8)

                        {

                            $this->$model->updateData(array('credit_status'=>2),'bill_id',$billing['ref_bill_id'], 'ret_billing');

                            //print_r($this->db->last_query());exit;

                        }

                        // Credit Collection Cancel

					    

					  

					    

					    

					}

					if($this->db->trans_status()=== TRUE)

					{

						$this->db->trans_commit();

						$return_data=array('status'=>TRUE);

						$this->session->set_flashdata('chit_alert',array('message'=>'Bill No cancelled successfully','class'=>'success','title'=>'Cancel Bill'));

					}

					else

					{

						$this->db->trans_rollback();

						$return_data=array('status'=>false);						 	

						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Cancel Bill'));

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

	

    function get_one_time_pre_weight_scheme()

    {

        $model = "ret_billing_model";

		$data = $this->$model->get_one_time_pre_weight_scheme();	  

		echo json_encode($data);

    }

    

    function get_customer_weight_scheme_details()

    {

        $model = "ret_billing_model";

		$data = $this->$model->get_customer_weight_scheme_details($_POST);	  

		echo json_encode($data);

    }

    

    

    function generate_receipt_no($id_scheme,$branch)

	{

		$model =	"payment_model";

		$rcpt_no = "";

		

		$rcpt = $this->$model->get_receipt_no($id_scheme,$branch);

		  

		if($rcpt!=NULL)

		{

			

			if($this->config->item('receipTcode') != ''){          // based on the config settings to removed comp shortcode front of recp num //HH

		  	$temp = explode($this->company['short_code'],$rcpt);

			 	if(isset($temp))

			 	{

					$number = (int) $temp[1];

					$number++;

					$rcpt_no =$this->company['short_code'].str_pad($number, 7, '0', STR_PAD_LEFT);

					//print_r($rcpt_no);exit;

				}

			}

			else{

                $number = (int) $rcpt;

                $number++;

                $rcpt_no = str_pad($number, 7, '0', STR_PAD_LEFT);

                //print_r($rcpt_no);exit;

			}

		}

		else

		{

		    if($this->config->item('receipTcode') != ''){

			 	$rcpt_no =$this->company['short_code']."000001";

		    }

		    else{

		        $rcpt_no ="000001";

		    }

		}

     //print_r($rcpt_no);exit;

		return $rcpt_no;

	}

	

		//bank account details

	function get_bank_acc_details()

	{

		$model = "ret_billing_model";

        $data= $this->$model->get_bank_acc_details();

        echo json_encode($data);

	}

	

	function get_payment_device_details()

	{

		$model = "ret_billing_model";

        $data= $this->$model->get_payment_device_details();

        echo json_encode($data);

	}

	

	//bank account details

        

    function get_customer_address()

	{

		$model = "ret_billing_model";

        $data['registered_address'] = $this->$model->get_customer_reg_add($_POST['id_customer']);

        echo json_encode($data);

	}

	

	function get_mydelivery_address()

	{

		$model = "ret_billing_model";

        $data['delivered_address'] = $this->$model->get_mydelivery_address($_POST['id_customer']);

        echo json_encode($data);

	}

	

	function update_mydelivery_address()

	{

	    $model = "ret_billing_model";

	    $addData=$_POST;

	    $insData=array(

	                    'id_customer'   =>$addData['id_customer'],

	                    'id_country'    =>$addData['id_country'],

	                    'id_state'      =>$addData['id_state'],

	                    'id_city'       =>$addData['id_city'],

	                    'address1'      =>($addData['address1']!='' ?strtoupper($addData['address1']) :NULL),

	                    'address2'      =>($addData['address2']!='' ?strtoupper($addData['address2']) :NULL),

	                    'address3'      =>($addData['address3']!='' ?strtoupper($addData['address3']) :NULL),

	                    'pincode'       =>($addData['pincode']!='' ? $addData['pincode']:NULL),

	                    'address_name'  =>strtoupper($addData['del_address_name']),

	                    'created_on'    =>date("Y-m-d H:i:s"),

						'created_by'    =>$this->session->userdata('uid'),

	                  );

	   	$this->db->trans_begin();

	    $this->$model->insertData($insData,'customer_delivery_address');

	    

        if($this->db->trans_status() === TRUE)

        {

            $this->db->trans_commit();

            $responseData=array('status'=>TRUE,'msg'=>'Address Added Successfully');

        }else{

            $this->db->trans_rollback();

            $responseData=array('status'=>FALSE,'msg'=>'Unable to Proceed Your Request.');

        }

        echo json_encode($responseData);

	}

	

	public function getCustomersindRecords()

	{

		$model = "ret_billing_model";

		$data = $this->$model->getAvailableIndCustomers($_POST['cus_id']);	  
        $path="assets/img/customer/".$_POST['cus_id'];
		if (is_dir($path)) {
			$path="assets/img/customer/".$_POST['cus_id']."/customer.jpg";
			$data[0]['img_path']=base_url($path);
		}else{
			$path="assets/img/default.png";
			$data[0]['img_path']=base_url($path);
		}
		echo json_encode($data);

	}

	

	

	//BILL EDIT

	function paymentmode_edit($type="")

	{

	    $model = "ret_billing_model";

		switch ($type) {

			case 'list':

			    $data['billing']		= $this->$model->get_empty_record();

				$data['main_content'] = "billing/paymentmode_edit" ;

				$this->load->view('layout/template', $data);

			break;

			case 'active_bill_list':

				$model = "ret_billing_model";

				$bill_no=$this->input->post('bill_no');

				$branch=$this->input->post('branch');

				$fin_year=$this->input->post('fin_year');

                $data= $this->$model->get_active_bill_list($bill_no, $branch,$fin_year);

				echo json_encode($data);

			break;

			case 'save':

			    

				

				$data = false;

				$check= $this->$model->get_active_bill($_POST['bill_no'],$_POST['branch']);

				if($check > 0)

				{

				    $this->db->trans_begin();

					$status = $this->$model->deleteData('bill_id', $_POST['bill_id'], 'ret_billing_payment');

					if($status)

					{

						if($_POST['cash_pay'])

						{

								$arrayCashPay=array(

    								'bill_id'		=>$_POST['bill_id'],

    								'payment_amount'    => $_POST['cash_pay'],

    								'payment_mode'      => 'Cash',

    								'type'              => 1,

    								'payment_for'	    => 1,

    								'payment_status'    => 1,

    								'payment_date'		=>$_POST['payment_date'],

    								'created_time'	    => $_POST['payment_date'],

									'created_by'	    => $_POST['created_by'],

									'updated_time'  	=> date("Y-m-d H:i:s"),

									'updated_by'   	=> $this->session->userdata('uid')

    								);

    									if(!empty($arrayCashPay))

    									{

    									  $cashPayInsert = $this->$model->insertData($arrayCashPay,'ret_billing_payment'); 

    									}

										//print_r($arrayCashPay);

							

						}

						if(count($_POST['chq_payment']) > 0)

    		 					{

    		 						foreach($_POST['chq_payment'] as $value)

							{

    									$arraychqPay[]=array(

    										'bill_id'		=>$_POST['bill_id'],

    										'payment_amount'=>$value['payment_amount'],

    										'payment_for'	=>1,

    										'payment_status'=>1,

											'type'=>1,

    										'payment_date'		=>$_POST['payment_date'],

    										'cheque_date'		=>$value['cheque_date'],

    										'payment_mode'	=>'CHQ',

    										'cheque_no'		=>$value['cheque_no'],

    										'id_bank'		=>$value['id_bank'],

    										'created_time'	    => $_POST['payment_date'],

											'created_by'	    => $_POST['created_by'],

											'updated_time'  	=> date("Y-m-d H:i:s"),

											'updated_by'   	=> $this->session->userdata('uid')

    									);

    		 						}

    			 						if(!empty($arraychqPay))

    			 						{

    										$chqPayInsert = $this->$model->insertBatchData($arraychqPay,'ret_billing_payment'); 

    									}

										//print_r($arraychqPay);

    		 					}

							if(count($_POST['nb_payment']) > 0)

    		 					{

    		 						foreach($_POST['nb_payment'] as $value)

    			 					{

    									$arrayNBPay[]=array(

    										'bill_id'		=>$_POST['bill_id'],

    										'payment_amount'=>$value['amount'],

    										'payment_for'	=>1,

    										'payment_status'=>1,

    										'type'=>1,

    										'payment_date'		=>$_POST['payment_date'],

    										'payment_mode'	=>'NB',

    										'payment_ref_number'=>$value['ref_no'],

    										'NB_type'       =>$value['nb_type'],

    										'id_pay_device' =>$value['id_device'],

    										'id_bank'		=>$value['id_bank'],

    										'created_time'	    => $_POST['payment_date'],

											'created_by'	    => $_POST['created_by'],

											'updated_time'  	=> date("Y-m-d H:i:s"),

											'updated_by'   	=> $this->session->userdata('uid')

    									);

    		 						}

    			 						if(!empty($arrayNBPay))

    			 						{

    										$NbPayInsert = $this->$model->insertBatchData($arrayNBPay,'ret_billing_payment'); 

    									}

										//print_r($arrayNBPay);

    		 					}	

							if(count($_POST['card_payment']) > 0)

    		 					{

    		 					   // print_r($_POST['card_payment']);exit;

    		 						foreach($_POST['card_payment'] as $value)

    			 					{

    									$arrayCardPay[]=array(

    										'bill_id'		=>$_POST['bill_id'],

    										'payment_amount'=>$value['card_amt'],

    										'payment_for'	=>1,

    										'payment_status'=>1,

											'type'=>1,

    										'payment_date'		=>$_POST['payment_date'],

    										'payment_mode'	=>($value['card_type']==1 ?'CC':'DC'),

    										'card_no'		=>$value['card_no'],

    										'id_bank'		=>$value['id_bank'],

    										'id_pay_device'		=>$value['id_device'],

    										'created_time'	    => $_POST['payment_date'],

											'created_by'	    => $_POST['created_by'],

											'updated_time'  	=> date("Y-m-d H:i:s"),

											'updated_by'   	=> $this->session->userdata('uid')

    									);

    		 						}

    			 						if(!empty($arrayCardPay))

    			 						{

    										$cardPayInsert = $this->$model->insertBatchData($arrayCardPay,'ret_billing_payment'); 

    									}

										//print_r($arrayCardPay);

    		 					}	

								$data = true;

					}

				}else{

					$responseData=array('status'=>FALSE,'message'=>'No Record Found..');

				}

				

				if($this->db->trans_status()===TRUE)

				{

					$this->db->trans_commit();

					$this->session->set_flashdata('chit_alert', array('message' => 'Billing Edited successfully','class' => 'success','title'=>'Billing'));	  

					$responseData=array('status'=>TRUE,'message' => 'Billing Edited successfully');

				}			  

				else

				{

					$this->db->trans_rollback();

					$this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Billing'));

					$responseData=array('status'=>FALSE,'message' => 'Unable to proceed your request');

				}

					

				echo json_encode($responseData);

			break;

		}

		

	}

	//BILL EDIT 

	

	

	

	//credit issue details

	function get_customer_credit_details()

	{

	    $model = "ret_billing_model";

		$data = $this->$model->get_customer_credit_details($_POST);	  

		echo json_encode($data);

	}

	//credit issue details

	

	

	//bill cancel otp

	

	function send_bill_cancel_otp()

	{  

		$model="ret_billing_model";

    	$data           = $this->$model->get_ret_settings('otp_approval_nos');

    	$mobile_num     = array(explode(',',$data));

    	$sent_otp='';

    	$comp_details=$this->admin_settings_model->get_company();

    	

    	foreach($mobile_num[0] as $mobile)

            {

                if($mobile)

                {

                    $this->session->unset_userdata("billcancel_otp");

                    $OTP = mt_rand(100001,999999);

                    $sent_otp.=$OTP.',';

                    $this->session->set_userdata('billcancel_otp',$sent_otp);

                    $this->session->set_userdata('billcancel_otp_exp',time()+60);

                    

                    //$service = $this->admin_settings_model->get_service_by_code('BILL_DISC');

                                                

                    $expiry = 1;                                

                    $message="Hi Your OTP For Bill Cancel is : ".$OTP." Will expire within ".$expiry." minute.".strtoupper($comp_details['company_name']).".";

                    $otp_gen_time = date("Y-m-d H:i:s");

                    $insData=array(

                        'mobile'        =>$mobile,

                        'otp_code'      =>$OTP,

                        'otp_gen_time'  =>date("Y-m-d H:i:s"),

                        'module'        =>'Bill Cancellation Approval',

                        'id_emp'        =>$this->session->userdata('uid')

                    );

                    $this->db->trans_begin();

                    $insId = $this->$model->insertData($insData,'otp');

                     if($insId)

                     {

                         $this->send_sms($mobile,$message,'');

                     }

                }

            }

            if($insId)

              {        

                      $this->db->trans_commit();

                      $status=array('status'=>true,'msg'=>'OTP sent Successfully');    

              }

              else

              {

                      $this->db->trans_rollback();

                      $status=array('status'=>false,'msg'=>'Unabe To Send Try Again');    

              }

            echo json_encode($status);

	}

	

	function verify_otp_for_billcancel()

    {

    		$model                ="ret_billing_model";

    		$post_otp             =$this->input->post('otp');

    		$session_otp          =$this->session->userdata('billcancel_otp');

    		$otp                  = array(explode(',',$session_otp));

    		foreach($otp[0] as $OTP)

    		{

    				if($OTP==$post_otp)

    				{

    					if(time() >= $this->session->userdata('billcancel_otp_exp'))

    					{

    						$this->session->unset_userdata('billcancel_otp');

    						$this->session->unset_userdata('billcancel_otp_exp');

    						$status=array('status'=>false,'msg'=>'OTP has been expired');

    					}

    					else

    					{

    						$updData=array(

    								'is_verified'=>1,

    								'verified_time'=>date("Y-m-d H:i:s"),

    							);

    						  $this->db->trans_begin();

    						  $update_otp=$this->$model->updateData($updData,'otp_code',$post_otp,'otp');

    						  if($update_otp)

    						  {

    								$status=array('status'=>true,'msg'=>'OTP Verified Successfully..');

    								  $this->db->trans_commit();

    						  }else{

    								 $status=array('status'=>false,'msg'=>'Unable to Proceed Your Request..');

    								  $this->db->trans_rollback();

    						  }

    					}

    					break;

    				}

    				else

    				{    

    					$status=array('status'=>false,'msg'=>'Please Enter Valid OTP');

    				}

    		}

    		  echo json_encode($status);

    }



	

	//bill cancel otp

	

	

		//Discount otp

    function admin_approval()

	{

		$model="ret_billing_model";

		$data           = $this->$model->get_ret_settings('otp_approval_nos');

		$mobile_num     = array(explode(',',$data));

		$sent_otp='';

		$comp_details=$this->admin_settings_model->get_company();

		foreach($mobile_num[0] as $mobile)

		{

			if($mobile)

			{

				$this->session->unset_userdata("discount_otp");

				$OTP = mt_rand(100001,999999);

				$sent_otp.=$OTP.',';

				$this->session->set_userdata('discount_otp',$sent_otp);

				$this->session->set_userdata('discount_otp_exp',time()+60);

				

				$service = $this->admin_settings_model->get_service_by_code('BILL_DISC');

            								

                $expiry = 1;            					

				$message="Hi Your OTP For Billing Discount Approval : ".$OTP." Will expire within ".$expiry." minute.".strtoupper($comp_details['company_name']).".";

				$otp_gen_time = date("Y-m-d H:i:s");

				$insData=array(

    				'mobile'        =>$mobile,

    				'otp_code'      =>$OTP,

    				'otp_gen_time'  =>date("Y-m-d H:i:s"),

    				'module'        =>'Billing Discount Approval',

    				'id_emp'        =>$this->session->userdata('uid')

				);

				$this->db->trans_begin();

				$insId = $this->$model->insertData($insData,'otp');

				 if($insId)

				 {

				 	$this->send_sms($mobile,$message,$service['dlt_te_id']);

				 }

			}

		}

	    if($insId)

	  	{		

	  			$this->db->trans_commit();

	  			$status=array('status'=>true,'msg'=>'OTP sent Successfully');	

	  	}

	  	else

	  	{

	  			$this->db->trans_rollback();

	  			$status=array('status'=>false,'msg'=>'Unabe To Send Try Again');	

	  	}

		echo json_encode($status);

	}

	

	

	function verify_otp()

	{

			$model                ="ret_billing_model";

			$post_otp             =$this->input->post('otp');

			$session_otp          =$this->session->userdata('discount_otp');

			$otp                  = array(explode(',',$session_otp));

			foreach($otp[0] as $OTP)

			{

					if($OTP==$post_otp)

					{

						if(time() >= $this->session->userdata('discount_otp_exp'))

						{

							$this->session->unset_userdata('discount_otp');

							$this->session->unset_userdata('discount_otp_exp');

							$status=array('status'=>false,'msg'=>'OTP has been expired');

						}

						else

						{

							$updData=array(

                                    'is_verified'=>1,

                                    'verified_time'=>date("Y-m-d H:i:s"),

								);

							  $this->db->trans_begin();

							  $update_otp=$this->$model->updateData($updData,'otp_code',$post_otp,'otp');

							  if($update_otp)

							  {

							        $status=array('status'=>true,'msg'=>'OTP Verified Successfully..');

							      	$this->db->trans_commit();

							  }else{

							         $status=array('status'=>false,'msg'=>'Unable to Proceed Your Request..');

							      	$this->db->trans_rollback();

							  }

						}

						break;

					}

					else

					{	

						$status=array('status'=>false,'msg'=>'Please Enter Valid OTP');

					}

			}

		  	echo json_encode($status);

	}

	//Discount otp

	

	

	public function send_credit_bill_otp()

	{

	    $model="ret_billing_model";

    	$data           = $this->$model->get_ret_settings('otp_approval_nos');

    	$mobile_num     = array(explode(',',$data));

    	$sent_otp='';

    	$comp_details=$this->admin_settings_model->get_company();

    	

    	foreach($mobile_num[0] as $mobile)

            {

                if($mobile)

                {

                    $this->session->unset_userdata("credit_bill_otp");

                    $this->session->unset_userdata("credit_bill_otp_exp");

                    $OTP = mt_rand(100001,999999);

                    $sent_otp.=$OTP.',';

                    $this->session->set_userdata('credit_bill_otp',$sent_otp);

                    $this->session->set_userdata('credit_bill_otp_exp',time()+60);

                    

                    //$service = $this->admin_settings_model->get_service_by_code('BILL_DISC');

                                                

                    $expiry = 1;                                

                    $message="Hi Your OTP For credit Bill sales Approval is : ".$OTP." Will expire within ".$expiry." minute.".strtoupper($comp_details['company_name']).".";

                    $otp_gen_time = date("Y-m-d H:i:s");

                    $insData=array(

                        'mobile'        =>$mobile,

                        'otp_code'      =>$OTP,

                        'otp_gen_time'  =>date("Y-m-d H:i:s"),

                        'module'        =>'Bill Cancellation Approval',

                        'id_emp'        =>$this->session->userdata('uid')

                    );

                    $this->db->trans_begin();

                    $insId = $this->$model->insertData($insData,'otp');

                     if($insId)

                     {

                         $this->send_sms($mobile,$message,'');

                     }

                }

            }

            if($insId)

              {        

                      $this->db->trans_commit();

                      $status=array('status'=>true,'msg'=>'OTP sent Successfully');    

              }

              else

              {

                      $this->db->trans_rollback();

                      $status=array('status'=>false,'msg'=>'Unabe To Send Try Again');    

              }

            echo json_encode($status);



	}

	

	

	function verify_credit_otp()

    {

    		$model                ="ret_billing_model";

    		$post_otp             =$this->input->post('otp');

    		$session_otp          =$this->session->userdata('credit_bill_otp');

    		$otp                  = array(explode(',',$session_otp));

    		foreach($otp[0] as $OTP)

    		{

    				if($OTP==$post_otp)

    				{

    					if(time() >= $this->session->userdata('credit_bill_otp_exp'))

    					{

    						$this->session->unset_userdata('credit_bill_otp');

    						$this->session->unset_userdata('credit_bill_otp_exp');

    						$status=array('status'=>false,'msg'=>'OTP has been expired');

    					}

    					else

    					{

    						$updData=array(

    								'is_verified'=>1,

    								'verified_time'=>date("Y-m-d H:i:s"),

    							);

    						  $this->db->trans_begin();

    						  $update_otp=$this->$model->updateData($updData,'otp_code',$post_otp,'otp');

    						  if($update_otp)

    						  {

    								$status=array('status'=>true,'msg'=>'OTP Verified Successfully..');

    								  $this->db->trans_commit();

    						  }else{

    								 $status=array('status'=>false,'msg'=>'Unable to Proceed Your Request..');

    								  $this->db->trans_rollback();

    						  }

    					}

    					break;

    				}

    				else

    				{    

    					$status=array('status'=>false,'msg'=>'Please Enter Valid OTP');

    				}

    		}

    		  echo json_encode($status);

    }

    

    

    function getBranchDayClosingData()

    {

        $model="ret_billing_model";

        $data = $this->$model->getBranchDayClosingData($_POST['id_branch']);

        echo json_encode($data);

    }

    

    

    public function service_bill($type="", $id="", $billno="")

    {

	    $model = "ret_billing_model";

	    switch($type)

		{

		    case 'list':

		        $data['main_content'] = "billing/service_bill/list" ;

				$this->load->view('layout/template', $data);

		    break;

			case 'add':

					$profile                                    = $this->admin_settings_model->profileDB("get",$this->session->userdata('profile'));

					$data['billing']		                    = $this->$model->get_empty_record();

					$data['billing']['credit_sales_otp_req']    = $profile['credit_sales_otp_req'];

					$data['bill_other_item']                    = array("item_details" => array(), "old_matel_details" => array(), "stone_details" => array(), "other_material_details" => array(), "voucher_details" => array(), "chit_details" => array(), "advance_details" => array());

					$data['uom']		                        = $this->$model->getUOMDetails();

					

					//print_r($this->session->all_userdata());exit;

					

					$data['main_content'] = "billing/service_bill/form" ;

					

					//echo "<pre>"; print_r($data);exit;

					$this->load->view('layout/template', $data);

				break;

			case 'save':

                    $addData            = $_POST['billing'];

                    $allow_submit       = TRUE;

                    $dCData             = $this->admin_settings_model->getBranchDayClosingData($addData['id_branch']);

                    $fin_year           = $this->$model->get_FinancialYear();

                    $item_details       = (isset($_POST['order']) ? $_POST['order']:'');

                    $form_secret        = isset($addData["form_secret"])?$addData["form_secret"]:'';

                    if($this->session->userdata('FORM_SECRET'))

                    {

                        if(strcasecmp($form_secret, ($this->session->userdata('FORM_SECRET'))) != 0)

                        {

                            $allow_submit = FALSE;

                            $return_data = array('status'=>FALSE,'message'=>'Invalid Form Submit.');

                            $this->session->set_flashdata('chit_alert',array('message'=>'Invalid Form Submit.','class'=>'danger','title'=>'Add Billing'));

                        }

                        

                        if($allow_submit)

                        {

                            if(sizeof($dCData) > 0)

                            {

                                    

            						$cheque_details	            = json_decode($addData['chq_pay'],true); 

            						$net_banking_details        = json_decode($addData['net_bank_pay'],true); 

            						$card_pay_details	        = json_decode($addData['card_pay'],true);

            						$bill_no                    = $this->$model->service_bill_number_generator($addData['id_branch']);   //Bill Number Generate 

            					    $bill_date                  = ($dCData['entry_date'] == date("Y-m-d") ? date("Y-m-d H:i:s") : $dCData['entry_date']);

            					    $metal_rate                 = $this->$model->get_branchwise_rate($addData['id_branch']);

            					    

            					    $data = array(						

            							'bill_no'		        => $bill_no,

            							'fin_year_code'		    => $fin_year['fin_year_code'],

            						    'form_secret'	        => $addData['form_secret'],

            							'id_customer'   	    => (!empty($addData['bill_cus_id']) ? $addData['bill_cus_id'] :0 ),

            							'total_bill_amount'	    => (!empty($addData['total_cost']) ? $addData['total_cost'] : 0 ),

            							'total_amount_received'	=> (!empty($addData['tot_amt_received']) ? $addData['tot_amt_received'] : 0),

            							'bill_date'	            => $bill_date,

            							'created_on'	        => date("Y-m-d H:i:s"),

            							'created_by'            => $this->session->userdata('uid'),

            							'counter_id'            => ($this->session->userdata('counter_id')!='' ? $this->session->userdata('counter_id'):NULL),

            							'id_branch'             => $addData['id_branch'],

            						); 

            		 				$this->db->trans_begin();

            		 				$insId = $this->$model->insertData($data,'ret_service_bill');

            		 				if($insId)

            		 				{

            		 				    if(!empty($item_details)){

            								$arrayBillSales = array();

            								foreach($item_details['is_est_details'] as $key => $val){

            									$arrayBillSales= array(

            										'id_service_bill' => $insId,

            										'id_service'      => $item_details['repair'][$key],

            										'id_product'      => $item_details['product'][$key],

            										'piece'           => $item_details['piece'][$key],

            										'weight'          => $item_details['completed_weight'][$key],

            										'total_cgst'      => $item_details['cgst'][$key],

            										'total_sgst'      => $item_details['sgst'][$key],

            										'total_igst'      => $item_details['igst'][$key],

            										'item_total_tax'  => $item_details['repair_tot_tax'][$key],

            										'item_total_cost' => $item_details['amount'][$key],

            										'tax_percentage'  => $item_details['repair_percent'][$key],

            										);

            										$billDetId = $this->$model->insertData($arrayBillSales,'ret_service_bill_details');

            								}

            		 				    }

            		 				    

            		 				    if($addData['cash_payment']>0)

            		 					{

                		 						$arrayCashPay=array(

                								'id_service_bill'   =>$insId,

                								'payment_amount'    =>$addData['cash_payment'],

                								'payment_mode'      =>'Cash',

                								'type'              =>($addData['pay_to_cus']>0 ?2:($addData['pay_to_cus']>0 ?3:1)),

                								'payment_for'	    =>($addData['bill_type']==6 ?2:($addData['pay_to_cus']>0 ?3:1)),

                								'payment_status'    =>1,

                								'payment_date'		=>date("Y-m-d H:i:s"),

                								'created_time'	    => date("Y-m-d H:i:s"),

                								'created_by'	    => $this->session->userdata('uid')

                								);

            									if(!empty($arrayCashPay)){

            									$cashPayInsert = $this->$model->insertData($arrayCashPay,'ret_service_bill_payment'); 

            									}

            		 					}

            		 					

            		 			

            		 					if(sizeof($card_pay_details)>0)

            		 					{

                		 						foreach($card_pay_details as $card_pay)

                			 					{

                									$arrayCardPay[]=array(

                										'id_service_bill'=>$insId,

                										'card_type'     =>$card_pay['card_name'],

                										'payment_amount'=>$card_pay['card_amt'],

                										'id_pay_device' =>($card_pay['id_device']!='' ? $card_pay['id_device']:NULL),

                										'payment_status'=>1,

                										'payment_date'	=>date("Y-m-d H:i:s"),

                										'payment_mode'	=>($card_pay['card_type']==1 ?'CC':'DC'),

                										'card_no'		=>($card_pay['card_no']!='' ? $card_pay['card_no']:NULL),

                										'payment_ref_number' =>($card_pay['ref_no']!='' ? $card_pay['ref_no']:NULL),	

                										'created_time'	=> date("Y-m-d H:i:s"),

                										'created_by'	=> $this->session->userdata('uid')

                									);

                		 						}

            			 						if(!empty($arrayCardPay)){

            										$cardPayInsert = $this->$model->insertBatchData($arrayCardPay,'ret_service_bill_payment'); 

            									}

            		 					}

            		 					if(sizeof($cheque_details)>0)

            		 					{

                		 						foreach($cheque_details as $chq_pay)

                			 					{

                			 					    $cheque_deposit_date = ($chq_pay['cheque_date']!='' ?date_create($chq_pay['cheque_date']) :NULL);

            			 					        $cheque_date = ($chq_pay['cheque_date']!='' ? date_format($cheque_deposit_date,"Y-m-d"):NULL);

                									$arraychqPay[]=array(

                										'id_service_bill'   => $insId,

                										'payment_amount'    => $chq_pay['payment_amount'],

                										'payment_status'    => 1,

                										'payment_date'		=> date("Y-m-d H:i:s"),

                										'cheque_date'		=>($chq_pay['cheque_date']!='' ? $cheque_date:NULL),

                										'payment_mode'	    => 'CHQ',

                										'cheque_no'		    => ($chq_pay['cheque_no']!='' ? $chq_pay['cheque_no']:NULL),

                										'bank_name'		    => ($chq_pay['bank_name']!='' ? $chq_pay['bank_name']:NULL),

                										'bank_branch'	    => ($chq_pay['bank_branch']!='' ? $chq_pay['bank_branch']:NULL),

                										'created_time'	    => date("Y-m-d H:i:s"),

                										'created_by'	    => $this->session->userdata('uid')

                									);

                		 						}

            			 						if(!empty($arraychqPay)){

            										$chqPayInsert = $this->$model->insertBatchData($arraychqPay,'ret_service_bill_payment'); 

            									}

            		 					}

            		 					if(sizeof($net_banking_details)>0)

            		 					{

                		 						foreach($net_banking_details as $nb_pay)

                			 					{

                									$arrayNBPay[]=array(

                										'id_service_bill'	=>$insId,

                										'payment_amount'    =>$nb_pay['amount'],

                										'id_pay_device'     =>($nb_pay['id_device']!='' ? $nb_pay['id_device']:NULL),

                										'payment_status'    =>1,

                										'payment_date'		=>date("Y-m-d H:i:s"),

                										'payment_mode'	    =>'NB',

                										'id_bank'           =>($nb_pay['id_bank']!='' ? $nb_pay['id_bank']:NULL),

                										'payment_ref_number'=>($nb_pay['ref_no']!='' ? $nb_pay['ref_no']:NULL),

                										'NB_type'           =>($nb_pay['nb_type']!='' ? $nb_pay['nb_type']:NULL),

                										'net_banking_date'  =>($nb_pay['nb_date']!='' ? $nb_pay['nb_date']:date("Y-m-d")),

                										'created_time'	    => date("Y-m-d H:i:s"),

                										'created_by'	    => $this->session->userdata('uid')

                									);

                		 						}

            			 						if(!empty($arrayNBPay)){

            										$NbPayInsert = $this->$model->insertBatchData($arrayNBPay,'ret_service_bill_payment'); 

            									}

            		 					}

            		 				    

            		 				    

            		 				}

            		 				

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

    							$this->session->set_flashdata('chit_alert',array('message'=>'Billing added successfully','class'=>'success','title'=>'Add Billing'));

    							//$this->session->unset_userdata('FORM_SECRET');

    						}

    						else

    						{

    							$this->db->trans_rollback();

    							$return_data=array('status'=>FALSE,'id'=>'');

    							echo $this->db->_error_message()."<br/>";					   

    							echo $this->db->last_query();exit;						 	

    							$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Add Billing'));

    						}

                        }

                    }

                    else

                    {

                        $return_data = array('status'=>FALSE,'message'=>'Unbale to Set the Form Secret Value.Plase Try Again');

                    }

                    echo json_encode($return_data); 

			break;

			default: 

           	$list = $this->$model->ajax_getServiceBillList($_POST);	 

           	$profile = $this->admin_settings_model->profileDB("get",$this->session->userdata('profile'));

		  	$access = $this->admin_settings_model->get_access('admin_ret_billing/service_bill/list');

	        $data = array(

	        					'list'  => $list,

								'access'=> $access,

								'profile'=> $profile

	        				);  

			echo json_encode($data);

		}

    }

    

    function service_bill_invoice($id)

	{

		$model="ret_billing_model";

		$data['billing'] = $this->$model->getServiceBillingDetails($id);

		$data['payment'] = $this->$model->getServiceBillPaymentDetails($id);

		$data['item_details'] = $this->$model->getServiceBillItemDetails($id);

		$data['comp_details']=$this->$model->getCompanyDetails($data['billing']['id_branch']);

		$data['metal_rate']=$this->$model->get_branchwise_rate($data['billing']['id_branch']);

		$data['settings']		= $this->$model->get_retSettings();

	    //echo "<pre>";print_r($data);exit;

		$this->load->helper(array('dompdf', 'file'));

        $dompdf = new DOMPDF();

		$html = $this->load->view('billing/service_bill/receipt_billing', $data,true);

	    $dompdf->load_html($html);

		$dompdf->set_paper("a4", "portriat" );

		$dompdf->render();

		$dompdf->stream("Receipt.pdf",array('Attachment'=>0));

	}

	

	

	function cancel_service_bill()

	{

	    $model   = "ret_billing_model";

	    $bill_id = $_POST['bill_id'];

	    $remarks = $_POST['remarks'];

	    $upd_data = array(

        "bill_status"	=> 2,

        'updated_on'	=> date("Y-m-d H:i:s"),

        'cancelled_date'=> date("Y-m-d H:i:s"),

        'cancel_reason'=>$remarks,

        'cancelled_by'	=> $this->session->userdata('uid')

        );

        $this->db->trans_begin();

        $status=$this->$model->updateData($upd_data,'id_service_bill',$bill_id, 'ret_service_bill');

        if($this->db->trans_status()=== TRUE)

		{

			$this->db->trans_commit();

			$return_data=array('status'=>TRUE);

			$this->session->set_flashdata('chit_alert',array('message'=>'Bill No cancelled successfully','class'=>'success','title'=>'Cancel Bill'));

		}

		else

		{

			$this->db->trans_rollback();

			$return_data=array('status'=>false);						 	

			$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Cancel Bill'));

 		}

 		echo json_encode($return_data);

	}

	

	

	function order_place()

	{

	    $model          = "ret_order_model";

	    $billing_model          = "ret_billing_model";

	    $fin_year       = $this->$model->get_FinancialYear();

	    $req_data       = $_POST['req_data']; 

	    $karigar_details    =[];

	   

        foreach($req_data as $r){

			$karigar_details[$r['id_karigar']][] = $r; 

		}





	    foreach($karigar_details as $id_karigar => $tag_details)

	    {

	        $pur_no = $this->$model->generatePurNo();

	        $total_pcs = 0;

	        $total_weight = 0;

	        $order = array( 

 			    'fin_year_code'     => $fin_year['fin_year_code'],

 				'pur_no'            => $pur_no,

 				'order_status'		=> 3,

 				'order_type'		=> 1,

 				'order_pcs'			=> 0,

 				'order_approx_wt'	=> 0,

 				'order_for'			=> 1,

 				'is_against_approval_stock'			=> 1,

 				'id_karigar'		=> $id_karigar,

 				'order_date'		=> date("Y-m-d H:i:s"),

				'createdon'         => date("Y-m-d H:i:s"),

				'order_taken_by'    => $this->session->userdata('uid')

			);

			$this->db->trans_begin();

            $insOrder = $this->$model->insertData($order,'customerorder');

                

	        foreach($tag_details as $val)

	        {

	            

	            $tag_det = $this->$billing_model->get_approval_tag_details($val['tag_id']);

	            

	            $total_pcs+=$tag_det['piece'];

	            $total_weight+=$tag_det['gross_wt'];

	            

	            $orderDetails = array( 

				'id_customerorder'	=>$insOrder,

				'approval_tagid'	=>$val['tag_id'],

				'orderstatus'		=>3,

				'id_weight_range'	=> NULL,

				'id_product'		=> (!empty($tag_det['product_id']) ? $tag_det['product_id'] :NULL ),

				'design_no'			=> (!empty($tag_det['design_id']) ? $tag_det['design_id'] :NULL ),

				'id_sub_design'		=> (!empty($tag_det['id_sub_design']) ? $tag_det['id_sub_design'] :NULL ),

				'totalitems'		=> (!empty($tag_det['piece']) ? $tag_det['piece'] :NULL ),

				'weight'		    => (!empty($tag_det['gross_wt']) ? $tag_det['gross_wt'] :NULL ),

				'size'				=> (!empty($tag_det['size']) ? $tag_det['size'] :NULL ),

				'smith_due_date'	=> NULL,

				'order_date'		=> date("Y-m-d H:i:s"),

				'id_employee'       => $this->session->userdata('uid'),

				);

				$insOrderdet = $this->$model->insertData($orderDetails,'customerorderdetails');

	        }

	        $this->$model->updateData(array('order_pcs'=>$total_pcs,'order_approx_wt'=>$total_weight),'id_customerorder',$insOrder,'customerorder');

	    }

    	if($this->db->trans_status()===TRUE)

		{

			$this->db->trans_commit();

			$this->session->set_flashdata('chit_alert', array('message' => 'Order Placed Successfully','class' => 'success','title'=>'Order'));

			$response_data=array('status'=>TRUE,'msg'=>'Order Placed Successfully..');

		}

		else

		{ 

		    //echo $this->db->_error_message();

		    echo $this->db->last_query();exit;

			$this->db->trans_rollback();

			$this->session->set_flashdata('chit_alert', array('message' => 'Unable to Proceed Your Request..','class' => 'danger','title'=>'Order'));

			$response_data=array('status'=>FALSE,'msg'=>'Unable to Proceed Your Request..');

		}

        echo json_encode($response_data);

	}

	

	

	function update_branch()

	{

	    $billing_model          = "ret_billing_model";

	    $req_data       = $_POST['req_data']; 

	    $karigar_details    =[];

	    $ho              =  $this->$billing_model->get_headOffice();

	   

	    foreach($req_data as $val)

	    {

	        $tag_det = $this->$billing_model->get_approval_tag_details($val['tag_id']);

	        

	        if($tag_det['tag_status']==11)

	        {

    	        $dCData = $this->admin_settings_model->getBranchDayClosingData($tag_det['current_branch']);

    	        

    	        $bill_date = ($dCData['entry_date'] == date("Y-m-d") ? date("Y-m-d H:i:s") : $dCData['entry_date']);

    	        

                $this->$billing_model->updateData(array('tag_status'=>0,'is_approval_stock_converted'=>1,'app_stk_converted_date'=>date("Y-m-d H:i:s"),'app_stk_converted_by'=>$this->session->userdata('uid'),'updated_time'=>date("Y-m-d H:i:s"),'updated_by'=>$this->session->userdata('uid')),'tag_id',$val['tag_id'], 'ret_taging');

                

                $ho_log_data=array(

                'tag_id'	  =>$val['tag_id'],

                'date'		  =>$bill_date,

                'status'	  =>0,

                'from_branch' =>NULL,

                'to_branch'   =>$ho['id_branch'],

                'created_on'  =>date("Y-m-d H:i:s"),

                'created_by'  =>$this->session->userdata('uid'),

                );

                $this->$billing_model->insertData($ho_log_data,'ret_taging_status_log'); //Update Tag lot status

                

                if($ho['id_branch']!=$tag_det['current_branch'])

                {

                    $ho_log_data=array(

                    'tag_id'	  =>$val['tag_id'],

                    'date'		  =>$bill_date,

                    'status'	  =>4,

                    'from_branch' =>$ho['id_branch'],

                    'to_branch'   =>$tag_det['current_branch'],

                    'created_on'  =>date("Y-m-d H:i:s"),

                    'created_by'  =>$this->session->userdata('uid'),

                    );

                    $this->$billing_model->insertData($ho_log_data,'ret_taging_status_log'); //Update Tag lot status

                    

                    $branch_log_data=array(

                    'tag_id'	  =>$val['tag_id'],

                    'date'		  =>$bill_date,

                    'status'	  =>0,

                    'from_branch' =>$ho['id_branch'],

                    'to_branch'   =>$tag_det['current_branch'],

                    'created_on'  =>date("Y-m-d H:i:s"),

                    'created_by'  =>$this->session->userdata('uid'),

                    );

                    $this->$billing_model->insertData($branch_log_data,'ret_taging_status_log'); //Update Tag lot status

                }

                

	        }

	    }

    	if($this->db->trans_status()===TRUE)

		{

			$this->db->trans_commit();

			$this->session->set_flashdata('chit_alert', array('message' => 'Tag Status Changed Successfully','class' => 'success','title'=>'Order'));

			$response_data=array('status'=>TRUE,'msg'=>'Tag Status Changed Successfull..');

		}

		else

		{ 

		    //echo $this->db->_error_message();

		    echo $this->db->last_query();exit;

			$this->db->trans_rollback();

			$this->session->set_flashdata('chit_alert', array('message' => 'Unable to Proceed Your Request..','class' => 'danger','title'=>'Order'));

			$response_data=array('status'=>FALSE,'msg'=>'Unable to Proceed Your Request..');

		}

        echo json_encode($response_data);

	}

    
    public function getCustomerDet(){
		$model="ret_billing_model";
		$data=$this->$model->getCustomerDet($_POST['id_branch'],$_POST['id_customer']);
		echo json_encode($data);
	}
	

}	

?>
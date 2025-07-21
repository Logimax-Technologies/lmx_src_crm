<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH.'libraries/dompdf/autoload.inc.php');
use Dompdf\Dompdf;
class Admin_ret_purchase extends CI_Controller {
	const VIEW_FOLDER = 'ret_purchase/';
	const RET_PUR_ORDER_MODEL = 'ret_purchase_order_model'; 
	const IMG_PATH  = 'assets/img/';
	function __construct()
	{
		parent::__construct();
		ini_set('date.timezone', 'Asia/Calcutta');
		$this->load->model(self::RET_PUR_ORDER_MODEL); 
		$this->load->model("admin_settings_model"); 
		$this->load->model("log_model");
		$this->load->model("admin_usersms_model");
		$this->load->model('email_model');
		$this->load->model("sms_model");
		$this->load->model("ret_reports_model");
		$this->load->model("ret_catalog_model"); 

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
	
	function index(){
		
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
	public function imgTobase64($path)
	{
		$type = pathinfo($path, PATHINFO_EXTENSION);
		$data = file_get_contents($path);
		$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
		return $base64;
	}
    
    //Order Description
	function order_description($type="",$id="")
	{   
		$model=	self::RET_PUR_ORDER_MODEL;
		switch($type)
		{
			case 'list':
					$data['main_content'] = self::VIEW_FOLDER.'order_description/list';
        			$this->load->view('layout/template', $data);
			break;
			case 'save':
			    $insData=array(
			                  'description'=>$_POST['order_des'],
			                  'created_on' => date("Y-m-d H:i:s"),
				              'created_by' => $this->session->userdata('uid')
			                  );
			     $this->db->trans_begin();
			     $insOrder = $this->$model->insertData($insData,'ret_purchase_order_description');
			    if($this->db->trans_status()===TRUE)
    			{
    				$this->db->trans_commit();
    				$this->session->set_flashdata('chit_alert',array('message'=>'Order Instructions Added Successfully','class'=>'success','title'=>'Order Instructions')); 
    				$return_data=array('status'=>TRUE,'message'=>'Order Instructions Added Successfully..');
    			}
    			else
    			{ 
    		
    			    echo $this->db->last_query();exit;
    				$this->db->trans_rollback();						 	
    				$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Order Instructions')); 
    				$return_data=array('status'=>FALSE,'message'=>'Unable to proceed the requested process');
    			}
    			echo json_encode($return_data);
			break;
			
			case 'delete':
    			   $this->$model->deleteData('id_order_des',$id,'ret_purchase_order_description');
    	           if($this->db->trans_status()===TRUE)
    			    {
    			    	  $this->db->trans_commit();
    					  $this->session->set_flashdata('chit_alert', array('message' => 'Order Instructions deleted successfully','class' => 'success','title'=>'Delete Order Instructions'));	  
    				}			  
    			   else
    			    {
    				 $this->db->trans_rollback();
    				 $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete Order Instructions'));
    			    }
    			 redirect('admin_ret_purchase/order_description/list');	
			 break;
			 
			 case 'edit':
			    $model  =	self::RET_PUR_ORDER_MODEL;
                $data   =  $this->$model->get_orderdescription($id);
                echo json_encode($data);
			 break;
			 
			 case 'update':
			     $updData=array(
			                   'description'=>$_POST['order_des'],
			                   'updated_on' => date("Y-m-d H:i:s"),
				               'updated_by' => $this->session->userdata('uid')
			                   );
			     $this->db->trans_begin();
			     $this->$model->updateData($updData,'id_order_des',$_POST['id_order_des'],'ret_purchase_order_description');
			    if($this->db->trans_status()===TRUE)
    			{
    				$this->db->trans_commit();
    				$this->session->set_flashdata('chit_alert',array('message'=>'Order Instructions Updated Successfully','class'=>'success','title'=>'Order Instructions')); 
    				$return_data=array('status'=>TRUE,'message'=>'Order Instructions Added Successfully..');
    			}
    			else
    			{ 
    		
    			    echo $this->db->last_query();exit;
    				$this->db->trans_rollback();						 	
    				$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Order Instructions')); 
    				$return_data=array('status'=>FALSE,'message'=>'Unable to proceed the requested process');
    			}
    			echo json_encode($return_data);
			 break;
			
		    default:
			        $list=$this->$model->get_order_description(); 
				  	$access = $this->admin_settings_model->get_access('admin_ret_purchase/order_description/list');
			        $data = array(
			        					'list'  => $list,
										'access'=> $access
			        				);  
					echo json_encode($data);

		}
	}
	//Order Description
    
    //Purchase Order
   
    
    function get_customer_order_pending_details()
	{
	    $model=	self::RET_PUR_ORDER_MODEL;
        $data= $this->$model->get_customer_order_pending_details($_POST);
        echo json_encode($data);
	}
	
	
	function get_stock_repair_order_details()
	{
	    $model=	self::RET_PUR_ORDER_MODEL;
        $data= $this->$model->get_stock_repair_order($_POST);
        echo json_encode($data);
	}
    
    function purchase($type="",$id="")
	{   
		$model=	self::RET_PUR_ORDER_MODEL;
		$set_model          ="admin_settings_model";
		switch($type)
		{
			case 'list':
			        $this->session->unset_userdata('pur_entry_secret_key');
					$data['main_content'] = self::VIEW_FOLDER.'purchase_entry_list';
        			$this->load->view('layout/template', $data);
			break;
			
			case 'pur_order':
					$data['main_content'] = self::VIEW_FOLDER.'pur_order_list';
        			$this->load->view('layout/template', $data);
			break;
			
			case 'add':
			    
			        $data['po_item']=$this->$model->get_empty_record();
					$data['main_content'] = self::VIEW_FOLDER.'order_form';
        			$this->load->view('layout/template', $data);
			break;
			
			case 'purchase_add':
			        if($id!='')
			        {
			            $data['po_item']=$this->$model->getPurchaseOrderDet($id);
			            $data['po_item_details']=$this->$model->getPurchaseOrderItemDet($id);
			        }
			        else
			        {
			            $data['po_item']=$this->$model->get_empty_record();
			            $data['po_item_details']=[];
			        }
			        //echo "<pre>";print_r($data);exit;
			        $data['comp_details']   = $this->$set_model->get_company();
					$data['main_content'] = self::VIEW_FOLDER.'pur_entry_form';
        			$this->load->view('layout/template', $data);
			break;
			
			case 'order_status':
					$data['main_content'] = self::VIEW_FOLDER.'order_status';
        			$this->load->view('layout/template', $data);
			break;
			
			case 'qc_status':
					$data['main_content'] = self::VIEW_FOLDER.'qc_status';
        			$this->load->view('layout/template', $data);
			break;
			
			case 'order_delivery':
					$data['main_content'] = self::VIEW_FOLDER.'order_delivery';
        			$this->load->view('layout/template', $data);
			break;
			
			
		case "save":
		    //print_r($_POST);exit;
            $addData   = $_POST['order'];
            $order_details   = $_POST['order_details'];
            
			$fin_year       = $this->$model->get_FinancialYear();
			$order_no       = $this->$model->generatePurNo();

 			$order = array( 
 			    'fin_year_code'     => $fin_year['fin_year_code'],
 				'pur_no'            => $order_no,
 				'order_status'		=> 3,
 				'order_type'		=> 1,
 				'order_pcs'			=> $addData['order_pcs'],
 				'order_approx_wt'	=> $addData['order_wt'],
 				'order_for'			=> $addData['order_for'],
 				'id_karigar'		=> $addData['id_karigar'],
 				'cus_ord_ref'		=> ($addData['id_customer_order']!='' && $addData['id_customer_order']!='null' ? $addData['id_customer_order'] :NULL),
 				'order_date'		=> date("Y-m-d H:i:s"),
				'createdon'         => date("Y-m-d H:i:s"),
				'order_taken_by'    => $this->session->userdata('uid')
			);
			$this->db->trans_begin();
			$insOrder = $this->$model->insertData($order,'customerorder');
		    //print_r($this->db->last_query());exit;
		    if($insOrder)
		    {
		        
		        		
		        if(!empty($order_details))
		        {
		            foreach($order_details['product'] as $key => $val)
		            {
		               
		                $smith_due_date = (!empty($addData['smith_due_dt']) ?date('Y-m-d',strtotime(str_replace("/","-",$addData['smith_due_dt']))):NULL ); 
		                
		                $orderDetail=array(
		                                  'id_customerorder'=> $insOrder, 
		                                  'id_product'      => $order_details['product'][$key], 
		                                  'design_no'       => ($order_details['design'][$key]!='' ? $order_details['design'][$key]:NULL), 
		                                  'id_sub_design'   => ($order_details['sub_design'][$key]!='' ? $order_details['sub_design'][$key]:NULL), 
		                                  'id_weight_range' => ($order_details['weight_range'][$key]!='' && $order_details['weight_range'][$key]!='null' ? $order_details['weight_range'][$key] :NULL), 
		                                  'weight'          => ($order_details['order_wt'][$key]!='' ? $order_details['order_wt'][$key]:NULL), 
		                                  'size'            => ($order_details['size'][$key]!='' && $order_details['size'][$key]!='null' ? $order_details['size'][$key] :NULL), 
		                                  'description'     => ($order_details['description'][$key]!='' ? $order_details['description'][$key] :NULL), 
		                                  'totalitems'      => $order_details['piece'][$key], 
		                                  'smith_due_date'  => $smith_due_date, 
		                                  'orderstatus'    =>3,
		                                  );
		                  $id_order_Details=$this->$model->insertData($orderDetail,'customerorderdetails');
		                  //print_r($this->db->last_query());exit;
		                  if($id_order_Details)
		                  {
		                    
		                    if($order_details['id_orderdetails'][$key]!='')
            		        {
            				    $this->$model->updateData(array('orderstatus'=>3),'id_orderdetails',$order_details['id_orderdetails'][$key],'customerorderdetails'); // For Customer Order Status
            		        }
		        
		                    $img_details=json_decode(rawurldecode($order_details['order_images'][$key])); 
		                    $img = json_decode(($img_details));
		                    $_FILES['order_image']    =  array();
		                    foreach($img as $image)
		                    {
		                       $imgFile = $this->base64ToFile($image->src);
    						   $_FILES['order_image'][] = $imgFile;
		                    }
    	                	
    	                    if(!empty($_FILES)) {
        						$folder =  self::IMG_PATH."order/purchase_order/"; 
        						if (!is_dir($folder)) {  
        							mkdir($folder, 0777, TRUE);
        						}   
        						if(isset($_FILES['order_image'])){ 
        							$precious_imgs = "";
        							// Image Table Update
        							$arrayimg_tag   = array();
        							foreach($_FILES['order_image'] as $file_key => $file_val){
        							
        								if($file_val['tmp_name'])
        								{
        									$img_name =  $id_order_Details."_". mt_rand(100001,999999).".jpg";
        									$path = $folder."/".$img_name; 
        									$result = $this->upload_img('image',$path,$file_val['tmp_name']);
        									if($result){
        										$precious_imgs = $img_name;
        										$arrayimg_tag = array(
        										 'id_orderdetails' => $id_order_Details,
        										 'image'           => $img_name,
        										);
        									$img_arr[]   = $arrayimg_tag;
        									  $insImageId = $this->$model->insertData($arrayimg_tag,'customer_order_image');  
        									}
        								}
        							}
        						}
        					} 
					
		                      $jobOrder=array(
		                                     'id_order'=>$id_order_Details,
		                                     'orderstatus'=>3,
		                                     'assigndate'=>date("Y-m-d H:i:s"),
		                                     'id_vendor'=>$addData['id_karigar'],
		                                     );
		                      $this->$model->insertData($jobOrder,'joborder');
		                  }
		                  
		                  if(isset($order_details['id_orderdetails'][$key]))
		                  {
		                        if($order_details['id_orderdetails'][$key]!='' && $order_details['id_orderdetails'][$key]!="undefined")
                		        {
                				    $this->$model->updateData(array('orderstatus'=>3),'id_orderdetails',$order_details['id_orderdetails'][$key],'customerorderdetails'); // For Customer Order Status
                		        } 
		                  }
		            }
		        }
		       
		        if($this->db->trans_status()===TRUE)
    			{
    				$this->db->trans_commit();
    				$this->session->set_flashdata('chit_alert',array('message'=>'New Order added successfully','class'=>'success','title'=>'Add Order')); 
    				$return_data=array('status'=>TRUE,'msg'=>'Order Created successfully..','id_customerorder'=>$insOrder,'order_for');
    			}
    			else
    			{ 
    		
    			    echo $this->db->last_query();exit;
    				$this->db->trans_rollback();						 	
    				$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Add Order')); 
    				$return_data=array('status'=>FALSE,'msg'=>'');
    			}
    			echo json_encode($return_data);
			
		    }
		   
			
			break;
			
			case 'po_entry_save':
			    $responsData=array();
			   // echo "<pre>";print_r($_POST);echo "</pre>";exit;
			    $orderData=$_POST['order'];
			    $orderDetails=$_POST['order_item'];
			    
                $ho              = $this->$model->get_headOffice();
                $fin_year       = $this->$model->get_FinancialYear();
                $dCData          = $this->admin_settings_model->getBranchDayClosingData($ho['id_branch']);
				$bill_date       = ($dCData['entry_date'] == date("Y-m-d") ? date("Y-m-d H:i:s") : $dCData['entry_date']);
				
				$karigar_details=$this->$model->get_karigar_details($orderData['id_karigar']);
				
				/*1.IF APPROVAL STOCK -  PA
				2.AGANINST ORDER - IF REGISTERED KARIGAR - P , NON REGISTERED PM*/
				
				/*if(!empty($orderData['purchase_type'])){
    				if($orderData['purchase_type']==1) // Aganist Order
    				{
    				    if($karigar_details['karigar_type']==0)
    				    {
    				        $gst_bill_type=$karigar_details['karigar_type'];
    				        $last_no       = $this->$model->generatePurRefOrderNo(0,$gst_bill_type);
    				        $po_ref_no='PM-'.$last_no;
    				    }
    				    else
    				    {
    				        $last_no       = $this->$model->generatePurRefOrderNo(0,'');
    				        $po_ref_no='P-'.$last_no;
    				    }
    				}
    				else
    				{
    				    $last_no       = $this->$model->generatePurRefOrderNo($orderData['is_suspense_stock'],'');
    				    if($orderData['is_suspense_stock']==0)
        				{
        				    $po_ref_no='P-'.$last_no;
        				}
        				else
        				{
        				    $po_ref_no='PA-'.$last_no;
        				}
    				}
				}else{
				    $last_no       = $this->$model->generatePurRefOrderNo($orderData['is_suspense_stock'],'');
    				    if($orderData['is_suspense_stock']==0)
        				{
        				    $po_ref_no='P-'.$last_no;
        				}
        				else
        				{
        				    $po_ref_no='PA-'.$last_no;
        				}
				}*/
				$is_suspense_stock = (isset($orderData['is_suspense_stock']) ? $orderData['is_suspense_stock'] :0);
				$last_no       = $this->$model->generatePurRefOrderNo($is_suspense_stock,'');
			    if($is_suspense_stock==0)
				{
				    $po_ref_no='P-'.$last_no;
				}
				else
				{
				    $po_ref_no='PA-'.$last_no;
				}
				
				
     			$order = array( 
     				'po_ref_no'         => $po_ref_no,
     				'fin_year_code'		=> $fin_year['fin_year_code'],
     				'po_grn_id'         => $orderData['id_grn'],
     				//'purchase_order_no' => ($orderData['purchase_type']==1 ?$orderData['purchase_order_no'] :NULL), //purchase_type=>1-Aganist Order
     				'is_suspense_stock' => ($orderData['purchase_type']==1 ? 0 :$orderData['is_suspense_stock']),
     				'isratefixed'       => $_POST['is_rate_fixed'],
     				'gst_bill_type'     => $karigar_details['karigar_type'],
     				'purchase_type'     => !empty($orderData['purchase_type']) ? $orderData['purchase_type'] : $orderData['po_type'],
     				'po_type'           => $orderData['po_type'],
     				'po_karigar_id'     => $orderData['id_karigar'],
     				'id_category'       => $orderData['id_category'],
     				'id_purity'         =>!empty($orderData['id_purity']) ? $orderData['id_purity'] : 0,
     				'ewaybillno'        => ($orderData['ewaybillno']!='' ? $orderData['ewaybillno']:NULL),
     				'po_irnno'          => ($orderData['po_irnno']!='' ? $orderData['po_irnno']:NULL),
     				'po_supplier_ref_no'=> !empty($orderData['po_supplier_ref_no']) ? $orderData['po_supplier_ref_no']:NULL,
     				'po_ref_date'       => !empty($orderData['po_ref_date']) ? date('Y-m-d', strtotime($orderData['po_ref_date'])):NULL,
     				'despatch_through'  => $orderData['despatch_through'],
     				'tot_purchase_amt'  => $orderData['total_cost'],
     				'tot_purchase_wt'   => $orderData['tot_purchase_wt'],
     				'po_date'		    => $bill_date,
    				'created_on'        => date("Y-m-d H:i:s"),
    				'po_othere_charges' => !empty($orderData['other_charges_amount']) ? $orderData['other_charges_amount'] : 0,
    				'po_discount'       => !empty($orderData['discount']) ? $orderData['discount']:0,
    				'created_by'        => $this->session->userdata('uid'),
    				'tcs_percent'       => $orderData['tcs_percent'],
    				'tcs_tax_value'     => $orderData['tcs_tax_value'],
    				'tds_percent'       => $orderData['tds_percent'],
    				'tds_tax_value'     => $orderData['tds_tax_value'],
    				'form_secret'       => $orderData['pur_entry_form_secret'],
    			);
    			//echo"<pre>";print_r($orderDetails);exit;
    			$this->db->trans_begin();
    			$insOrder = $this->$model->insertData($order,'ret_purchase_order');
    			if($insOrder)
    			{
    			    
    			     //Update Order Status
    			    if($orderData['purchase_type']==1)
    			    {
    			        foreach($orderDetails['id_category'] as $okey => $oval){
        			        $PurorderDetails = $this->$model->get_pur_order_det($orderDetails['po_order_no'][$okey]);
        			        if($PurorderDetails['order_pcs'] > 0)
        			        {
        			            $order_details = array('delivered_qty'=>$orderDetails['tot_pcs'][$okey],'delivered_wt'=> $orderDetails['tot_gwt'][$okey],'id_customerorder'=>$orderDetails['po_order_no'][$okey]);
                                $this->$model->updatePurOrderStatus($order_details,'+');
                                $orderdetails = $this->$model->get_cus_order_details($orderDetails['po_order_no'][$okey], $orderDetails['id_product'][$key], $orderDetails['id_design'][$key], $orderDetails['id_sub_design'][$key]);
                        		$updateorderDetail = array('orderstatus'=> 5,'delivered_qty'=> $orderDetails['tot_pcs'][$key]); 
                        		$this->$model->updateData($updateorderDetail,'id_orderdetails',$orderdetails['id_orderdetails'],'customerorderdetails');
                                if($PurorderDetails['order_pcs']<=($orderDetails['tot_pcs'][$okey]+$PurorderDetails['delivered_qty']))
                                {
                                    $this->$model->updateData(array('order_status'=> 5),'id_customerorder',$orderDetails['po_order_no'][$okey],'customerorder');        
                                }

        			        }
    			        }
    			            
    			    }
    			    //Update Order Status
    
    			
    			    if(!empty($orderDetails))
    			    {
    			        	foreach($orderDetails['id_category'] as $key => $val){
    			        	   
    			        	    $arrayItems=array(
    			        	                      'po_item_po_id'       =>$insOrder,
    			        	                      'po_item_cat_id'      =>$orderDetails['id_category'][$key],
    			        	                      'po_order_no'         => $orderDetails['po_order_no'][$key],
    			        	                      'po_purchase_mode'    => $orderDetails['po_purchase_mode'][$key],
    			        	                      'po_item_pro_id'      =>($orderDetails['id_product'][$key] !='' && $orderDetails['id_product'][$key]!='null' ? $orderDetails['id_product'][$key]:NULL),
    			        	                      'po_item_des_id'      =>($orderDetails['id_design'][$key]!='' && $orderDetails['id_design'][$key]!='null' ? $orderDetails['id_design'][$key]:NULL),
    			        	                      'po_item_sub_des_id'  =>($orderDetails['id_sub_design'][$key]!='' && $orderDetails['id_sub_design'][$key]!='null' ? $orderDetails['id_sub_design'][$key]:NULL),
    			        	                      'id_purity'           =>($orderDetails['id_purity'][$key]!='' ? $orderDetails['id_purity'][$key]:NULL),
    			        	                      'gross_wt'            =>$orderDetails['tot_gwt'][$key],
    			        	                      'less_wt'             =>($orderDetails['tot_lwt'][$key]!='' ? $orderDetails['tot_lwt'][$key] :0),
    			        	                      'net_wt'              =>$orderDetails['tot_nwt'][$key],
    			        	                      'calculation_based_on'=>$orderDetails['calculation_based_on'][$key],
    			        	                      'item_wastage'        =>($orderDetails['tot_wastage_perc'][$key]!='' ?$orderDetails['tot_wastage_perc'][$key] :NULL),
    			        	                      'no_of_pcs'           =>($orderDetails['tot_pcs'][$key]!='' ? $orderDetails['tot_pcs'][$key] :0),
    			        	                      'is_suspense_stock'   =>$orderDetails['is_suspense_stock'][$key],
    			        	                      'is_halmarked'        =>$orderDetails['is_halmerked'][$key],
    			        	                      'is_halmark_from'     =>($orderDetails['is_halmerked'][$key]==1 ? $orderDetails['is_halmerked'][$key]:NULL),
    			        	                      'is_rate_fixed'       =>$orderDetails['is_rate_fixed'][$key],
    			        	                      'fix_rate_per_grm'    =>($orderDetails['rate_per_gram'][$key]!='' ? $orderDetails['rate_per_gram'][$key] :0),
    			        	                      'mc_value'            =>($orderDetails['mc_value'][$key]!='' ? $orderDetails['mc_value'][$key] :0),
    			        	                      'purchase_touch'      =>($orderDetails['purchase_touch'][$key]!='' ? $orderDetails['purchase_touch'][$key] :0),
    			        	                      'mc_type'             =>$orderDetails['mc_type'][$key],
    			        	                      'item_cost'           =>($orderDetails['item_cost'][$key]!='' ?$orderDetails['item_cost'][$key] :0),
    			        	                      'item_pure_wt'        =>($orderDetails['tot_purewt'][$key]!='' ? $orderDetails['tot_purewt'][$key]:0),
    			        	                      'uom'                 =>($orderDetails['gwt_uom'][$key]!='' ? $orderDetails['gwt_uom'][$key]:0),
    			        	                      'cal_type'            =>($orderDetails['cal_type'][$key]!='' ? $orderDetails['cal_type'][$key]:0),
    			        	                      'total_tax'           =>($orderDetails['total_tax_rate'][$key]!='' && $orderDetails['total_tax_rate'][$key]!=null ? $orderDetails['total_tax_rate'][$key]:0),
    			        	                      'total_cgst'          =>($orderDetails['cgst_cost'][$key]!='' ? $orderDetails['cgst_cost'][$key]:0),
    			        	                      'total_sgst'          =>($orderDetails['sgst_cost'][$key]!='' ? $orderDetails['sgst_cost'][$key]:0),
    			        	                      'total_igst'          =>($orderDetails['igst_cost'][$key]!='' ? $orderDetails['igst_cost'][$key]:0),
    			        	                      'tax_percentage'      =>($orderDetails['tax_percentage'][$key]!='' ? $orderDetails['tax_percentage'][$key]:0),
    			        	                      'tax_group'           =>($orderDetails['tax_group'][$key]!='' ? $orderDetails['tax_group'][$key]:0),
    			        	                      'tax_type'            =>($orderDetails['tax_type'][$key]!='' ? $orderDetails['tax_type'][$key]:0),
    			        	                      'pure_wt_calc_type'   =>($orderDetails['pure_wt_calc_type'][$key]!='' ? $orderDetails['pure_wt_calc_type'][$key]:0),
    			        	                      'remark'              =>($orderDetails['item_remarks'][$key]!='' ? $orderDetails['item_remarks'][$key]:NULL),
    			        	                     );
    			        	    //echo "<pre>";print_r($arrayItems);exit;
    			        	    $itemStatus=$this->$model->insertData($arrayItems,'ret_purchase_order_items');
    			        	    
    			        	    if($itemStatus)
    			        	    {
    			        	        //Supplier Ledger Log
    			        	        if($orderDetails['tot_purewt'][$key]!=''){
    			        	            $ledger_data = array(
    			        	                                'trans_date'        =>$bill_date,
    			        	                                'id_karigar'        =>$orderData['id_karigar'],
    			        	                                'cat_id'            =>$orderDetails['id_category'][$key],
    			        	                                'trans_type'        =>2,//Debit
    			        	                                'trans_rec_type'    =>1,//Weight
    			        	                                'trans_pcs'         =>($orderDetails['tot_pcs'][$key]!='' ? $orderDetails['tot_pcs'][$key] :0),
    			        	                                'trans_grswt'       =>$orderDetails['tot_gwt'][$key],
    			        	                                'trans_netwt'       =>$orderDetails['tot_nwt'][$key],
    			        	                                'trans_purewt'      =>$orderDetails['tot_purewt'][$key],
    			        	                                'trans_screen_id'   =>1,//Supplier Bill entry
    			        	                                'ref_id'            =>$itemStatus,
    			        	                                'remarks'           =>'From Suuplier Bill Entry',
    			        	                                'created_by'        =>$this->session->userdata('uid'),
    			        	                                'created_on'        =>date("Y-m-d H:i:s"),
    			        	                                );
    			        	            $this->$model->insertData($ledger_data,'ret_supplier_ledger_log');
    			        	        }
    			        	        //Supplier Ledger Log
    			        	        
    			        	        $charge_details = json_decode($orderDetails['other_chrg_details'][$key],true);
    			        	        if(sizeof($charge_details)>0)
    			        	        {
                                    	foreach($charge_details as $charges)
                                    	{
                                        	$charge_data = array(
                                            	'pur_othr_po_id'            => $insOrder,
                                            	'pur_po_item_id'            => $itemStatus,
                                            	'pur_othr_charge_id'        => $charges['charge_id'],
                                            	'calc_type'                 => $charges['calc_type'],
                                            	'pur_othr_charge_value'     => $charges['charge_value'],
                                            	'tax_percentage'            => $charges['tax_percentage'],
                                            	'item_total_tax'            => $charges['charge_tax_value'],
                                            	'total_charge_value'        => $charges['total_charge_value'],
                                        	);
                                        	//print_r($charge_data);exit;
                                        	$chrg_status = $this->$model->insertData($charge_data, 'ret_purchase_other_charges');
                                        	if($chrg_status)
                                        	{
                                        	    $ledger_data = array(
    			        	                                'trans_date'        =>$bill_date,
    			        	                                'id_karigar'        =>$orderData['id_karigar'],
    			        	                                'id_charge'         =>$charges['charge_id'],
    			        	                                'trans_type'        =>2,//Debit
    			        	                                'trans_rec_type'    =>2,//Amount
    			        	                                'trans_amount'      =>number_format($total_charge,2,'.',''),
    			        	                                'trans_screen_id'   =>1,//Supplier Bill entry
    			        	                                'ref_id'            =>$itemStatus,
    			        	                                'remarks'           =>'From Suuplier Bill Entry Charges',
    			        	                                'created_by'        =>$this->session->userdata('uid'),
    			        	                                'created_on'        =>date("Y-m-d H:i:s"),
    			        	                                );
    			        	                        $this->$model->insertData($ledger_data,'ret_supplier_ledger_log');
                                        	}
                                    	}
                    			    }

			        	        	if($orderDetails['stone_details'][$key])
                                	{
                                    	$stone_details=json_decode($orderDetails['stone_details'][$key],true);
                                    	foreach($stone_details as $stone)
                                    	{
                                        	$stone_data=array(
                                        	'po_item_id'    =>$itemStatus,
                                        	'po_stone_id'   =>$stone['stone_id'],
                                        	'po_stone_pcs'  =>$stone['stone_pcs'],
                                        	'po_stone_wt'   =>$stone['stone_wt'],
                                        	'po_stone_uom'  =>$stone['stone_uom_id'],
                                        	'po_stone_rate' =>$stone['stone_rate'],
                                        	'po_stone_amount'=>$stone['stone_price'],
                                        	'is_apply_in_lwt'=> $stone['show_in_lwt'],
                                        	'po_stone_calc_based_on'=> $stone['stone_cal_type']
                                        	);
                                        	$stoneInsert = $this->$model->insertData($stone_data,'ret_po_stone_items');
                                        	//echo $this->db->last_query();exit;
                                    	}										
                                	}
                                    	
                                	if($orderDetails['other_metal_details'][$key])
                                	{
                                    	$othermetal_details=json_decode($orderDetails['other_metal_details'][$key],true);
                                    	foreach($othermetal_details as $othermetal)
                                    	{
                                        	$other_metal_data = array(
                                    	    'po_item_id'                    =>$itemStatus,
                                        	'po_item_metal'             =>$othermetal['id_metal'],
                                        	'po_other_item_gross_weight'=>$othermetal['gwt'],
                                        	'po_other_item_purity'   =>$othermetal['id_purity'],
                                        	'po_other_item_rate'  =>$othermetal['rate_per_gram'],
                                        	'po_other_item_pcs' =>$othermetal['pcs'],
                                        	'po_other_item_amount'=>$othermetal['amount'],
                                        	);
                                        	$othermetalInsert = $this->$model->insertData($other_metal_data,'ret_po_other_item');
                                        	//echo $this->db->last_query();exit;
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
                        	'module'      	=> 'SUPPLIER BILL ENTRY',
                        	'operation'   	=> 'Add',
                        	'record'        =>  $insOrder,  
                        	'remark'       	=> 'Supplier Entry Added Successfully..'
                        );
                        $this->log_model->log_detail('insert','',$log_data);
                
        				$this->db->trans_commit();
        				$this->session->set_flashdata('chit_alert',array('message'=>'Purchase Entry added successfully','class'=>'success','title'=>'Purchase Entry')); 
        				$responsData=array('status'=>TRUE,'msg'=>'Purchase Entry added successfully..');
        			}
        			else
        			{ 
        				$this->db->trans_rollback();						 	
        				$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Add Order')); 
        				$responsData=array('status'=>FALSE,'msg'=>'');
        			}
        			echo json_encode($responsData);
    			}
    			    
			break;
			
			case 'po_entry_update':

            	$updateorderData = $_POST['order'];
            
            	$updateorderDetails = $_POST['order_item'];
            
            	$id = $updateorderData['po_id'];
            
            	//echo"<pre>";print_r($updateorderDetails);exit;
            
            	$pur_order = array( 
            		'po_grn_id'         => $updateorderData['id_grn'],		
            		'is_suspense_stock' => ($updateorderData['purchase_type']==1 ? 0 :$updateorderData['is_suspense_stock']),
            		'isratefixed'       => $_POST['is_rate_fixed'],
            		//'gst_bill_type'     => $karigar_details['karigar_type'],
            		'purchase_type'     => !empty($updateorderData['purchase_type']) ? $updateorderData['purchase_type'] : $updateorderData['po_type'],
            		'po_type'           => $updateorderData['po_type'],
            		'po_karigar_id'     => $updateorderData['id_karigar'],
            		'id_category'       => $updateorderData['id_category'],
            		'id_purity'         =>!empty($updateorderData['id_purity']) ? $updateorderData['id_purity'] : 0,
            		'ewaybillno'        => ($updateorderData['ewaybillno']!='' ? $updateorderData['ewaybillno']:NULL),
            		'po_irnno'          => ($updateorderData['po_irnno']!='' ? $updateorderData['po_irnno']:NULL),
            		'po_supplier_ref_no'=> !empty($updateorderData['po_supplier_ref_no']) ? $updateorderData['po_supplier_ref_no']:NULL,
            		'po_ref_date'       => !empty($updateorderData['po_ref_date']) ? date('Y-m-d', strtotime($updateorderData['po_ref_date'])):NULL,
            		'despatch_through'  => $updateorderData['despatch_through'],
            		'tot_purchase_amt'  => $updateorderData['total_cost'],
            		'tot_purchase_wt'   => $updateorderData['tot_purchase_wt'],
            		// 'po_date'		    => $bill_date,
            		'updated_on'        => date("Y-m-d H:i:s"),
            		'po_othere_charges' => !empty($updateorderData['other_charges_amount']) ? $updateorderData['other_charges_amount'] : 0,
            		'po_discount'       => !empty($updateorderData['discount']) ? $updateorderData['discount']:0,
            		'updated_by'        => $this->session->userdata('uid'),
            		'tcs_percent'       => $updateorderData['tcs_percent'],
            		'tcs_tax_value'     => $updateorderData['tcs_tax_value'],
            		'tds_percent'       => $updateorderData['tds_percent'],
            		'tds_tax_value'     => $updateorderData['tds_tax_value']
            	);
            
            	$this->db->trans_begin();
            
            	$update_status = $this->$model->updateData($pur_order,'po_id',$id, 'ret_purchase_order');
            
            
            	if($update_status)
            	{
            
            		foreach($updateorderDetails['id_category'] as $key => $val)
            		{
            
            			$arrayItems=array(
            
            				'po_item_cat_id' =>$updateorderDetails['id_category'][$key],
            				
            				//'po_order_no'         => $updateorderDetails['po_order_no'][$key],
            				
            				'po_purchase_mode'    => $updateorderDetails['po_purchase_mode'][$key],
            				
            				'po_item_pro_id'      =>($updateorderDetails['id_product'][$key] !='' && $updateorderDetails['id_product'][$key]!='null' ? $updateorderDetails['id_product'][$key]:NULL),
            				
            				'po_item_des_id'      =>($updateorderDetails['id_design'][$key]!='' && $updateorderDetails['id_design'][$key]!='null' ? $updateorderDetails['id_design'][$key]:NULL),
            				
            				'po_item_sub_des_id'  =>($updateorderDetails['id_sub_design'][$key]!='' && $updateorderDetails['id_sub_design'][$key]!='null' ? $updateorderDetails['id_sub_design'][$key]:NULL),
            				
            				'id_purity'           =>($updateorderDetails['id_purity'][$key]!='' ? $updateorderDetails['id_purity'][$key]:NULL),
            				
            				'gross_wt'            =>$updateorderDetails['tot_gwt'][$key],
            				
            				'less_wt'             =>($updateorderDetails['tot_lwt'][$key]!='' ? $updateorderDetails['tot_lwt'][$key] :0),
            				
            				'net_wt'              =>$updateorderDetails['tot_nwt'][$key],
            				
            				'item_wastage'        =>($updateorderDetails['tot_wastage_perc'][$key]!='' ?$updateorderDetails['tot_wastage_perc'][$key] :NULL),
            				
            				'no_of_pcs'           =>($updateorderDetails['tot_pcs'][$key]!='' ? $updateorderDetails['tot_pcs'][$key] :0),
            				
            				'is_suspense_stock'   =>$updateorderDetails['is_suspense_stock'][$key],
            				
            				'is_halmarked'        =>$updateorderDetails['is_halmerked'][$key],
            				
            				'is_halmark_from'     =>($updateorderDetails['is_halmerked'][$key]==1 ? $updateorderDetails['is_halmerked'][$key]:NULL),
            				
            				'is_rate_fixed'       =>$updateorderDetails['is_rate_fixed'][$key],
            				
            				'fix_rate_per_grm'    =>($updateorderDetails['rate_per_gram'][$key]!='' ? $updateorderDetails['rate_per_gram'][$key] :NULL),
            				
            				'mc_value'            =>($updateorderDetails['mc_value'][$key]!='' ? $updateorderDetails['mc_value'][$key] :NULL),
            				
            				'purchase_touch'      =>($updateorderDetails['purchase_touch'][$key]!='' ? $updateorderDetails['purchase_touch'][$key] :NULL),
            				
            				'mc_type'             =>$updateorderDetails['mc_type'][$key],
            				
            				'item_cost'           =>($updateorderDetails['item_cost'][$key]!='' ?$updateorderDetails['item_cost'][$key] :0),
            				
            				'item_pure_wt'        =>($updateorderDetails['tot_purewt'][$key]!='' ? $updateorderDetails['tot_purewt'][$key]:0),
            				
            				'uom'                 => ($updateorderDetails['gwt_uom'][$key]!='' ? $updateorderDetails['gwt_uom'][$key]:0),
            				
            				'cal_type'            => ($updateorderDetails['cal_type'][$key]!='' ? $updateorderDetails['cal_type'][$key]:0),
            				
            				'remark'              => ($updateorderDetails['remark'][$key]!='' ? $updateorderDetails['remark'][$key]:NULL),
            
            			);
            
            			// echo"<pre>";print_r($arrayItems);exit;
            
            
            			if(!empty($updateorderDetails['po_item_id'][$key]))
            			{
            
            				$this->db->trans_begin();
            	
            				$this->$model->updateData($arrayItems,'po_item_id',$updateorderDetails['po_item_id'][$key],'ret_purchase_order_items');
            				//print_r($this->db->last_query());exit;
            
            				if($updateorderDetails['stone_details'][$key])
            				{
            					$stone_details=json_decode($updateorderDetails['stone_details'][$key],true);
            
            					$po_stone_delete_id = $this->$model->deleteData("po_item_id",$updateorderDetails['po_item_id'][$key],'ret_po_stone_items');
            					
            					foreach($stone_details as $stone)
            					{
            						$stone_data=array(
            						'po_item_id'    =>$updateorderDetails['po_item_id'][$key],
            						'po_stone_id'   =>$stone['stone_id'],
            						'po_stone_pcs'  =>$stone['stone_pcs'],
            						'po_stone_wt'   =>$stone['stone_wt'],
            						'po_stone_uom'  =>$stone['stone_uom_id'],
            						'po_stone_rate' =>$stone['stone_rate'],
            						'po_stone_amount'=>$stone['stone_price'],
            						'is_apply_in_lwt'=> $stone['show_in_lwt']
            						);
            						$stoneInsert = $this->$model->insertData($stone_data,'ret_po_stone_items');
            						//echo $this->db->last_query();exit;
            					}										
            				}
            								
            				if($orderDetails['other_metal_details'][$key])
            				{
            					$othermetal_details=json_decode($orderDetails['other_metal_details'][$key],true);
            					
            					$po_othermetal_id = $this->$model->deleteData("po_item_id",$updateorderDetails['po_item_id'][$key],'ret_po_other_item');
            
            					foreach($othermetal_details as $othermetal)
            					{
            						$other_metal_data = array(
            						'po_item_id'                    =>$itemStatus,
            						'po_item_metal'             =>$othermetal['id_metal'],
            						'po_other_item_gross_weight'=>$othermetal['gwt'],
            						'po_other_item_purity'   =>$othermetal['id_purity'],
            						'po_other_item_rate'  =>$othermetal['rate_per_gram'],
            						'po_other_item_pcs' =>$othermetal['pcs'],
            						'po_other_item_amount'=>$othermetal['amount'],
            						);
            						$othermetalInsert = $this->$model->insertData($other_metal_data,'ret_po_other_item');
            						//echo $this->db->last_query();exit;
            					}										
            				}
            								
            
            			}
            			else
            			{
            				if($id > 0)
            				{
            					$arrayItems['po_item_po_id'] = $id;
            
            					$this->db->trans_begin();
            	
            					$insPurorderDet = $this->$model->insertData($arrayItems,'ret_purchase_order_items');
            				}
            
            				$stone_details=json_decode($updateorderDetails['stone_details'][$key],true);
            
            				if(sizeof($stone_details)>0)
            				{
            					foreach($stone_details as $stone)
            					{
            						$stone_data=array(
            						'po_item_id'    =>$insPurorderDet,
            						'po_stone_id'   =>$stone['stone_id'],
            						'po_stone_pcs'  =>$stone['stone_pcs'],
            						'po_stone_wt'   =>$stone['stone_wt'],
            						'po_stone_uom'  =>$stone['stone_uom_id'],
            						'po_stone_rate' =>$stone['stone_rate'],
            						'po_stone_amount'=>$stone['stone_price'],
            						'is_apply_in_lwt'=> $stone['show_in_lwt']
            						);
            						$stoneInsert = $this->$model->insertData($stone_data,'ret_po_stone_items');
            						//echo $this->db->last_query();exit;
            					}		
            
            				}
            
            				$othermetal_details=json_decode($orderDetails['other_metal_details'][$key],true);
            
            				if(sizeof($othermetal_details)>0)
            				{
            					foreach($othermetal_details as $othermetal)
            					{
            						$other_metal_data = array(
            						'po_item_id'                    =>$insPurorderDet,
            						'po_item_metal'             =>$othermetal['id_metal'],
            						'po_other_item_gross_weight'=>$othermetal['gwt'],
            						'po_other_item_purity'   =>$othermetal['id_purity'],
            						'po_other_item_rate'  =>$othermetal['rate_per_gram'],
            						'po_other_item_pcs' =>$othermetal['pcs'],
            						'po_other_item_amount'=>$othermetal['amount'],
            						);
            						$othermetalInsert = $this->$model->insertData($other_metal_data,'ret_po_other_item');
            						//echo $this->db->last_query();exit;
            					}	
            
            				}
            
            
            			}
            
            		}
            
            	}
            
            	if($this->db->trans_status()===TRUE)
            	{
            
            		$this->db->trans_commit();
            
            		$this->session->set_flashdata('chit_alert',array('message'=>' Purchase Entry updated successfully','class'=>'success','title'=>'Purchase Entry')); 
            
            		$responsData=array('status'=>TRUE,'msg'=>'Purchase Entry Updated successfully..');
            
            	}
            	else
            
            	{
            
            		//echo $this->db->last_query();exit;
            
            		$this->db->trans_rollback();						 	
            
            		$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Purchase Entry')); 
            
            		$responsData=array('status'=>FALSE,'msg'=>'');
            
            	}
            
            	echo json_encode($responsData);
            
            
            break;
			
			case 'purchase_order':
			        $list=$this->$model->get_purchase_order_Details($_POST); 
				  	$access = $this->admin_settings_model->get_access('admin_ret_purchase/purchase/pur_order');
			        $data = array(
			        					'list'  => $list,
										'access'=> $access
			        				);  
					echo json_encode($data);
			break;
			
			case 'purchase_entry':
			        $from_date	= $this->input->post('from_date');
			        $to_date	= $this->input->post('to_date'); 
			        $list=$this->$model->get_purchase_entry_details($from_date,$to_date); 
				  	$access = $this->admin_settings_model->get_access('admin_ret_purchase/purchase/list');
			        $data = array(
			        					'list'  => $list,
										'access'=> $access
			        				);  
					echo json_encode($data);
			break;
			
			case 'active_grns':
		            $list = $this->$model->getActiveGRNsDetails($_POST); 
					echo json_encode($list);
			break;
			
			case 'job_receipt':
				$data['po_item']=$this->$model->getPurchaseOrderDet($id);
				$data['po_item_details']=$this->$model->get_purchase_order_item_det($id);
				$data['comp_details']=$this->admin_settings_model->getCompanyDetails("");
				//echo "<pre>";print_r($data);exit;
				$html = $this->load->view(self::VIEW_FOLDER.'job_receipt', $data,true);
				echo $html;exit;
				
			break;
			
			case 'grncatdetailsbygrnid':
			    $catitems = $this->$model->getGRNsCatDetailsbyGRNId($_POST); 
				echo json_encode($catitems);
				break;
				
			case 'ajax': 
					$list=$this->$model->getStockAgeDetails($_POST); 
				  	$access = $this->admin_settings_model->get_access('admin_ret_reports/stock_age/list');
			        $data = array(
			        					'list'  => $list,
										'access'=> $access
			        				);  
					echo json_encode($data);
				break;
		}
	    
    } 
    
    function approvalstock($type="")
	{   
		$model=	self::RET_PUR_ORDER_MODEL;
		switch($type)
		{
			case 'list':
					$data['main_content'] = self::VIEW_FOLDER.'approvalstock/purchase_entry_list';
        			$this->load->view('layout/template', $data);
			break;
			
			case 'pur_order':
					$data['main_content'] = self::VIEW_FOLDER.'pur_order_list';
        			$this->load->view('layout/template', $data);
			break;
			
			case 'add':
					$data['main_content'] = self::VIEW_FOLDER.'order_form';
        			$this->load->view('layout/template', $data);
			break;
			
			case 'ajax_purchase_list':
			        $list=$this->$model->get_approval_purchase_entry_details(); 
				  	$access = $this->admin_settings_model->get_access('admin_ret_purchase/approvalstock/list');
			        $data = array(
			        					'list'  => $list,
										'access'=> $access
			        				);  
					echo json_encode($data);
			break;
		}
        
    }
    
    
   function send_karigar_sms()
    {
    
        $responseData=array();
        $model              =	self::RET_PUR_ORDER_MODEL;
        $set_model          =   "admin_settings_model";
        $id_customer_order  =   $_POST['id_customer_order'];
        $mobile             =   $_POST['mobile'];
        
        $service = $this->$set_model->get_service_by_code('KAR_ALOC');
       
        $orders=$this->$model->get_karigar_order_details($id_customer_order);
        
        
        
       /* $data['order']['pur_no']=$orders[0]['pur_no'];
        $data['order']['emp_name']=$orders[0]['emp_name'];
        $data['order']['order_date']=$orders[0]['order_date'];
        $data['order']['smith_due_date']=$orders[0]['smith_due_date'];
        $data['order_details']=$this->$model->get_karigar_order_products($id_customer_order);
        $data['orders']=$orders;
        //echo "<pre>";print_r($orders);exit;
        
        $data['karigar']=$this->$model->get_karigar_details($orders[0]['id_vendor']);
        $data['description']=$this->$model->get_order_description();*/
        
        
        $data['order_items']=$orders;
        $data['order']['pur_no']=$orders[0]['pur_no'];
        $data['order']['emp_name']=$orders[0]['emp_name'];
        $data['order']['order_date']=$orders[0]['order_date'];
        $data['order']['order_for']=$orders[0]['order_for'];
        $data['order']['smith_due_date']=$orders[0]['smith_due_date'];
        
        $data['comp_details'] = $this->$set_model->get_company(1);
        $data['order_details']=$this->$model->get_karigar_order_products($id_customer_order);
        $data['karigar']=$this->$model->get_karigar_details($orders[0]['id_karigar']);
        $data['description']=$this->$model->get_order_description();
        
    
		$data['comp_details'] = $this->$set_model->get_company(1);
        $this->load->helper(array('dompdf', 'file'));
        $dompdf = new DOMPDF();
        $html = $this->load->view('ret_purchase/vendor_ack', $data,true);
    
        $dompdf->load_html($html);
        $dompdf->set_paper('A4', "portriat" );
        $dompdf->render();
        $file = $dompdf->output();
		
		
		$folderPath='assets/vendor_ack/'.$data['order']['pur_no'];
		$file_name =$folderPath.'/'.time().'_'.$data['order']['pur_no'].'.pdf';
		
		$attachementUrl=$file_name;
		
		if (!is_dir($folderPath)) {
    	mkdir($folderPath, 0777, TRUE);
    	}
		file_put_contents($file_name, $file);
		
	    
	    //print_r($service);exit; 
	    
	    /*if($service['serv_whatsapp'] == 1)
		{*/
			if($mobile!='')
			{
			    $sms_data=$this->admin_usersms_model->Get_service_code_sms('KAR_ALOC',$id_customer_order,$attachementUrl);
			    $message='New Order Received From '.$data['comp_details']['company_name'].'.For More Details Please Find The Below Attachement.';
			    $status=$this->sms_model->sendSMS_MSG91($mobile,$message,'',$dlt_te_id,'');
			    if($attachementUrl!='')
			    {
			        $this->sms_model->sendSMS_MSG91($mobile,$message,'',$dlt_te_id,$this->config->item('base_url').'/'.$attachementUrl,$data['order']['pur_no'].'.pdf');
			        
			    }
			    //$whatsapp=$this->admin_usersms_model->send_whatsApp_message($mobile,"New Order From -".$data['comp_details']['company_name'],$this->config->item('base_url').'/'.$attachementUrl,$data['order']['pur_no'].'.pdf');
			    if($status)
			    {
			        $responseData=array('status'=>true,'message'=>'Order Details Sent Successfully');
			    }
			}
	//	}
		
		if($service['serv_email'] == 1)
		{
			if($orders[0]['email']!='')
			{
			    $sms_data=$this->admin_usersms_model->Get_service_code_sms('KAR_ALOC',$id_customer_order,$attachement_url);
			   
			    $send_mail_from =$this->$model->get_ret_settings('pur_order_email');
			    
			    if($send_mail_from!='')
			    {
			        $send_mail_to =$orders[0]['email'];
			    
                    $email_subject="New Order -".$data['comp_details']['company_name'];
                    
                    $this->company = $this->admin_settings_model->get_company(1);
                    
                    $email_model = "email_model";
                    
                    
                    $message = $this->load->view('ret_purchase/vendor_ack_email',$orders, true);
    
                    $sendEmail = $this->$model->send_email($send_mail_from,$send_mail_to,$email_subject,$message,'',$send_mail_from,$attachementUrl);
                    
                    if($sendEmail)
    			    {
    			        $responseData=array('status'=>true,'message'=>'Order Details Sent Successfully');
    			    }
			    }
			    
			   
    											
			}
		}
		
		echo json_encode($responseData);			
    }
    
    function get_karigar_acknowladgement($id_customer_order)
    {
        $model=	self::RET_PUR_ORDER_MODEL;
        $set_model = "admin_settings_model";
        
        $orders=$this->$model->get_karigar_order_details($id_customer_order);
        //echo "<pre>";print_r($orders);exit;
        $data['order_items']=$orders;
        $data['order']['pur_no']=$orders[0]['pur_no'];
        $data['order']['emp_name']=$orders[0]['emp_name'];
        $data['order']['order_date']=$orders[0]['order_date'];
        $data['order']['order_for']=$orders[0]['order_for'];
        $data['order']['smith_due_date']=$orders[0]['smith_due_date'];
        
        $data['comp_details'] = $this->$set_model->get_company(1);
        $data['order_details']=$this->$model->get_karigar_order_products($id_customer_order);
        $data['karigar']=$this->$model->get_karigar_details($orders[0]['id_karigar']);
        $data['description']=$this->$model->get_order_description();
        
        //echo "<pre>";print_r($data);exit;
        
    	$this->load->helper(array('dompdf', 'file'));
    	$dompdf = new DOMPDF();
        $html = $this->load->view('ret_purchase/vendor_ack', $data,true);
        $dompdf->load_html($html);
        $dompdf->set_paper('A4', "portriat" );
       
		$dompdf->render();
		 $file = $dompdf->output();
		$dompdf->stream("VendorAck.pdf",array('Attachment'=>0));
		
		
		$file_name =$order[0]['order_no'].'.pdf';
		if (!is_dir('vendor_ack')) {
    	mkdir('vendor_ack', 0777, TRUE);
    	}
		file_put_contents('vendor_ack/'.$file_name, $file);
	    $attachement_url=$this->shortenurl($this->config->item('base_url').'/vendor_ack/'.$file_name);
    }

    //Purchase Order
    
    
    function get_KarigarOrders()
    {
        $model=	self::RET_PUR_ORDER_MODEL;
        $data= $this->$model->get_KarigarOrders($_POST);
        echo json_encode($data);
    }
    
    
    function get_ActiveWeightRange()
    {
        $model=	self::RET_PUR_ORDER_MODEL;
        $data= $this->$model->get_ActiveWeightRange($_POST);
        echo json_encode($data);
    }
    
    
    function get_pur_order_Details()
    {
        $model=	self::RET_PUR_ORDER_MODEL;
        $data= $this->$model->get_purchase_order_status($_POST);
        echo json_encode($data);
    }
    
    
    function get_purchase_issue_entry_items()
    {
        $model=	self::RET_PUR_ORDER_MODEL;
        $data= $this->$model->get_qc_receipt_items($_POST);
        echo json_encode($data);
    }
    
    
    function update_qc_status()
    {
        $model=	self::RET_PUR_ORDER_MODEL;
        $itemDetails=$_POST['order_items'];
        $po_id=$_POST['order']['po_ref_no'];
        //echo "<pre>";print_r($_POST);exit;
        $responseData=array();
        $this->db->trans_begin();
        foreach($itemDetails['id_qc_issue_details'] as $key => $val)
        {
                $updData=array(
                                'status'        => 1, // QC Completed
                                'failed_pcs'    => $itemDetails['failed_pcs'][$key],
                                'failed_gwt'    => $itemDetails['failed_gwt'][$key],
                                'failed_lwt'    => $itemDetails['failed_lwt'][$key],
                                'failed_nwt'    => $itemDetails['failed_nwt'][$key],
                                'passed_pcs'    => $itemDetails['qc_acc_pcs'][$key],
                                'passed_gwt'    => $itemDetails['qc_acc_gwt'][$key],
                                'passed_lwt'    => $itemDetails['qc_acc_lwt'][$key],
                                'passed_nwt'    => $itemDetails['qc_acc_nwt'][$key],
                              );
                $status = $this->$model->updateData($updData,'id_qc_issue_details',$itemDetails['id_qc_issue_details'][$key],'ret_po_qc_issue_details');
                
               if($status)
    	       {
    	            //echo "<pre>"; print_r($itemDetails['stone_details'][$key]);exit;
    	          	if(!empty($itemDetails['stone_details'][$key]))
    				{
    					$stone_details = json_decode($itemDetails['stone_details'][$key],true);
    					//echo "<pre>"; print_r($stone_details);exit;
    					foreach($stone_details as $val)
    					{
    						$stone_data = array(
    							'qc_rejected_pcs'     => $val['po_stone_rejected_pcs'],
    							'qc_rejected_wt'      => $val['po_stone_rejected_wt'],
    							'qc_passed_pcs'       => $val['po_stone_accepted_pcs'],
    							'qc_passed_wt'        => $val['po_stone_accepted_wt'],
    						);
    						$this->$model->updateData($stone_data,'ret_qc_issue_stn_id',$val['ret_qc_issue_stn_id'],'ret_po_qc_issue_stone_details');
    					}
    				}
    	       }
        }

        if($this->db->trans_status()===TRUE)
        {
            $this->db->trans_commit();
            //Generate LOT
            /*if($po_id!='')
            {
                $LotStatus=TRUE;
                $pur_ord_det=$this->$model->get_purchase_order_qc_details($po_id); // CHECKING QC STATUS AD H.M STATUS FOR LOT GENERATE
                foreach($pur_ord_det as $po_items)
                {
                    if($po_items['status']==0)
                    {
                        $LotStatus=FALSE;
                    }
                }
                
                if($LotStatus)
                {
                    $this->generate_lot($po_id);
                }
            }*/
            //Generate LOT
            $log_data = array(
                        	'id_log'        => $this->session->userdata('id_log'),
                        	'event_date'	=> date("Y-m-d H:i:s"),
                        	'module'      	=> 'QUALITY CONTROL',
                        	'operation'   	=> 'Update',
                        	'record'        =>  $po_id,  
                        	'remark'       	=> 'QC Receipt Updated Successfully..'
                        );
            $this->log_model->log_detail('insert','',$log_data);
                        
            
            $responseData=array('status'=>true,'message'=>'QC Updated successfully.');
            $this->session->set_flashdata('chit_alert',array('message'=>'QC Updated successfully.','class'=>'success','title'=>'QC Entry'));			
        }	
        else
        {
            $this->db->trans_rollback();
            $responseData=array('status'=>false,'message'=>'Unable to proceed the requested operation.');
            $this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'QC Entry'));
        }
        echo json_encode($responseData);
    } 
    
    
    function generate_lot($po_id)
    {
        $model=	self::RET_PUR_ORDER_MODEL;
        $itemDetails = $this->$model->get_purchase_by_category($po_id);
        $ho              =  $this->$model->get_headOffice();
		$dCData          =  $this->admin_settings_model->getBranchDayClosingData($ho['id_branch']);
		$bill_date       =  ($dCData['entry_date'] == date("Y-m-d") ? date("Y-m-d H:i:s") : $dCData['entry_date']);
        foreach($itemDetails as $addData)
        {
            $data = array(
            'lot_date'				=> date("Y-m-d H:i:s"),
            'created_branch'		=> (isset($ho['id_branch']) ? $ho['id_branch']:NULL ),
            'lot_received_at'       => $ho['id_branch'],
            'stock_type'            => $addData['stock_type'],
            'lot_from'              => 2,
            'po_id'                 => $po_id,
            'gold_smith'			=> (isset($addData['po_karigar_id']) ? $addData['po_karigar_id'] :NULL ),
            'id_category'			=> (isset($addData['id_category']) ? $addData['id_category'] :NULL ),
            'id_purity'				=> (isset($addData['id_purity']) ? $addData['id_purity'] :NULL ),
            'created_on'	  		=> date("Y-m-d H:i:s"),
            'created_by'      		=> $this->session->userdata('uid')
            ); 
            $this->db->trans_begin();
    	 	$insId = $this->$model->insertData($data,'ret_lot_inwards');
    	 	if($insId)
    	 	{
    	 	    $po_order_details=$this->$model->get_purchase_orders_by_product($po_id,$addData['id_category'],$addData['id_purity'],$addData['stock_type']);
    	 	    //print_r($po_order_details);exit;
    	 	    foreach($po_order_details as $items)
    	 	    {
    	 	        $item_details = array(
                        'lot_no'	    =>$insId,
                        'lot_product'   =>($items['id_product']!='' ? $items['id_product']:NULL),
                        'no_of_piece'   =>($items['total_pcs']!='' ? $items['total_pcs']:0),
                        'gross_wt'      =>($items['total_gwt']!='' ? $items['total_gwt']:0),
                        'less_wt'       =>($items['total_lwt']!='' ? $items['total_lwt']:0),
                        'net_wt'        =>($items['total_nwt']!='' ? $items['total_nwt']:0),
                    ); 
                    $detail_insId = $this->$model->insertData($item_details,'ret_lot_inwards_detail');  
                    //echo "<pre>"; print_r($this->db->last_query());exit;
                    if($detail_insId)
                    {
                        $this->$model->updateMultipleWhereData(array('is_lot_created'=>1,'lot_no'=>$insId),array('po_item_po_id'=>$po_id,'po_item_pro_id'=>$items['id_product'],'id_purity'=>$items['id_purity']),'ret_purchase_order_items');
                        //echo "<pre>"; print_r($this->db->last_query());exit;
                        if($addData['stock_type']==2)
                        {
                            $purchase_order_details = $this->$model->get_purchase_order_items($po_id,$items['id_product']);
                            foreach($purchase_order_details as $val)
                            {
                                $existData=array('id_product'=>$val['po_item_pro_id'],'design'=>$val['po_item_des_id'],'id_sub_design'=>$val['po_item_sub_des_id'],'id_branch'=>$ho['id_branch']);
                                						        
						        $isExist = $this->$model->checkNonTagItemExist($existData);
						        
						        if($isExist['status'] == TRUE)
						        {
						            $nt_data = array(
                                    'id_nontag_item'=>$isExist['id_nontag_item'],
                                    'gross_wt'		=> $val['total_gwt'],
                                    'net_wt'		=> $val['total_nwt'],  
                                    'no_of_piece'   => $val['total_pcs'],
                                    'updated_by'	=> $this->session->userdata('uid'),
                                    'updated_on'	=> date('Y-m-d H:i:s'),
                                    );
                                    $this->$model->updateNTData($nt_data,'+');
                        													
                                    $non_tag_data=array(
                                    'product'	    => $val['po_item_pro_id'],
                                    'design'	    => $val['po_item_des_id'],
                                    'id_sub_design'	=> $val['po_item_sub_des_id'],
                                    'no_of_piece'   => $val['total_pcs'],
                                    'gross_wt'		=> $val['total_gwt'],
                                    'net_wt'		=> $val['total_nwt'],
                                    'to_branch'	    => $ho['id_branch'],
                                    'from_branch'	=> NULL,
                                    'ref_no'	    => $insId,
                                    'status'	    => 0,
                                    'date'          => $bill_date,
                                    'created_on'    => date("Y-m-d H:i:s"),
                                    'created_by'    =>  $this->session->userdata('uid')
                                    );
                                    $this->$model->insertData($non_tag_data,'ret_nontag_item_log');
						        }else
						        {
                                        $nt_data=array(
                                        'branch'	    => $ho['id_branch'],
                                        'product'	    => $val['po_item_pro_id'],
                                        'design'	    => $val['po_item_des_id'],
                                        'id_sub_design'	=> $val['po_item_sub_des_id'],
                                        'no_of_piece'   => $val['total_pcs'],
                                        'gross_wt'		=> $val['total_gwt'],
                                        'net_wt'		=> $val['total_nwt'],
                                        'created_on'    => date("Y-m-d H:i:s"),
                                        'created_by'    => $this->session->userdata('uid')
                                        );
                                        $this->$model->insertData($nt_data,'ret_nontag_item'); 
                                    
                                        $non_tag_data=array(
                                        'product'	    => $val['po_item_pro_id'],
                                        'design'	    => $val['po_item_des_id'],
                                        'id_sub_design'	=> $val['po_item_sub_des_id'],
                                        'no_of_piece'   => $val['total_pcs'],
                                        'gross_wt'		=> $val['total_gwt'],
                                        'net_wt'		=> $val['total_nwt'],
                                        'from_branch'	=> NULL,
                                        'to_branch'	    => $ho['id_branch'],
                                        'ref_no'	    => $insId,
                                        'status'	    => 0,
                                        'date'          => $bill_date,
                                        'created_on'    => date("Y-m-d H:i:s"),
                                        'created_by'    =>  $this->session->userdata('uid')
                                        );
                                        $this->$model->insertData($non_tag_data,'ret_nontag_item_log'); 
						        }
                            }
                                    
                        }
                        
                    }
    	 	            
    	 	    }
    	 	   
    	 	    if($detail_insId)
    	 	    {
    	 	        $order_items        =$this->$model->get_po_purchase_items($po_id,$addData['id_category']);
    	 	        $total_payable_wt   =0;
    	 	        $total_mc_value     =0;
    	 	        $total_weight_amt   =0;
    	 	        $total_making_charge   =0;
    	 	        foreach($order_items as $order)
    	 	        {
    	 	            $total_weight       =($order['item_wastage']>0 ? ((($order['total_nwt']*$order['item_wastage'])/100)+$order['total_nwt']) : $order['total_nwt'] );
    	 	            
    	 	            //$pure_wt          =(($total_weight*22)/24); // For calculating PURE WEIGHT (22CT to 24 CT)
    	 	            
    	 	            $pure_wt            = $order['item_pure_wt'];
    	 	            
    	 	            $total_mc_value+=($order['mc_type']==1 ? ($order['total_gwt']*$order['mc_value']):($order['total_pcs']*$order['mc_value']));
    	 	            
    	 	            $item_mc_value  =($order['mc_type']==1 ? ($order['total_gwt']*$order['mc_value']):($order['total_pcs']*$order['mc_value']));
    	 	            
    	 	            
    	 	            $total_payable_wt+=$pure_wt;
    	 	            
    	 	            
    	 	            
    	 	            /*//Update Rate Fixing
    	 	           
    	 	            if($order['fix_rate_per_grm']>0 && $order['is_rate_fixed']==1)
    	 	            {
    	 	                $total_amount =($order['item_pure_wt']*$order['fix_rate_per_grm']);
    	 	                
    	 	                $tax_amt      = (($total_amount*$order['tax_percentage'])/100);
    	 	                
    	 	                $total_weight_amt+=$tax_amt+$total_amount;
    	 	                
    	 	                $rateFixData=array(
                               'rate_fix_po_item_id'    =>$order['po_item_id'],
                               'rate_fix_type'          =>1,
                               'rate_fix_wt'            =>$order['item_pure_wt'],
                               'tax_group_id'           =>$order['tgrp_id'],
                               'rate_fix_rate'          =>$order['fix_rate_per_grm'],
                               'rate_fix_created_from'  =>2,
                               'total_tax_amount'       =>$tax_amt,
                               'total_amount'           =>($tax_amt+$total_amount),
                               'rate_fix_create_by'     =>$this->session->userdata('uid'),
                               'rate_fix_created_on'    =>date("Y-m-d H:i:s"),
                               );
                               $insertId=$this->$model->insertData($rateFixData,'ret_po_rate_fix');
                              if($insertId)
        			          {
        			               $log_data = array(
                                    	'id_log'        =>  $this->session->userdata('id_log'),
                                    	'event_date'	=>  date("Y-m-d H:i:s"),
                                    	'module'      	=> 'RATE FIXING',
                                    	'operation'   	=> 'insert',
                                    	'record'        =>  $order['po_item_id'],  
                                    	'remark'       	=> 'Rate Fixed Successfully..'
                                    );
                                    $this->log_model->log_detail('insert','',$log_data);
        			          }
    	 	            }
    	 	            
    	 	            if($order['mc_value']>0) // Update tax and insert into rate fix table for mc and other charges
    	 	            {
    	 	                $tax_amount=(($item_mc_value*$order['tax_percentage'])/100);
    	 	                
    	 	                $total_making_charge+=$tax_amount+$item_mc_value;
    	 	                
    	 	                $mc_payable_data=array(
                               'rate_fix_po_item_id'    =>$order['po_item_id'],
                               'rate_fix_type'          =>2,
                               'rate_fix_amt'           =>$item_mc_value,
                               'tax_group_id'           =>$order['tgrp_id'],
                               'rate_fix_rate'          =>0,
                               'rate_fix_created_from'  =>2,
                               'total_tax_amount'       =>$tax_amount,
                               'total_amount'           =>($tax_amount+$item_mc_value),
                               'rate_fix_create_by'     =>$this->session->userdata('uid'),
                               'rate_fix_created_on'    =>date("Y-m-d H:i:s"),
                               );
                               $insertId=$this->$model->insertData($mc_payable_data,'ret_po_rate_fix');
                               //print_r($this->db->last_query());exit;
    	 	            }
    	 	            */
    	 	            //Update Rate Fixing
    	 	            
    	 	        }
    	 	        //Update Payable weight and amount
    	 	        $payInsData=array(
    	 	                        'total_payable_wt'=>number_format($total_payable_wt,3,'.',''),
    	 	                        'total_payable_amt'=>number_format($total_weight_amt+$total_making_charge,2,'.',''),
    	 	                        'po_id'=>$po_id,
    	 	                        );
    
    	 	        $paymentData=$this->$model->update_po_paymentData($payInsData,'+');
    	 	        //Update Payable weight and amount
                
    	 	    }
                if($this->db->trans_status()===TRUE)
                {
                    
                    $log_data = array(
                    	'id_log'        => $this->session->userdata('id_log'),
                    	'event_date'	=> date("Y-m-d H:i:s"),
                    	'module'      	=> 'Lot',
                    	'operation'   	=> 'Add',
                    	'record'        => $insId,  
                    	'remark'       	=> 'Lot added successfully From QC Receipt'
                    );
                    $this->log_model->log_detail('insert','',$log_data);
                            
                    $this->db->trans_commit();
                    $responseData=array('status'=>true,'message'=>'Lot Created successfully.');
                }	
                else
                {
                    $this->db->trans_rollback();
                    $responseData=array('status'=>false,'message'=>'Unable to proceed the requested operation.');
                }
                
    	 	}
        }   
    }
    
    
    function generate_lot_from_halmarking($hm_process_id)
    {
        $model=	self::RET_PUR_ORDER_MODEL;
        $addData=$this->$model->get_halmarking_purchase_order($hm_process_id);
        
        $settings = $this->$model->get_ret_settings('lot_recv_branch');
    	if($settings == 1){ // HO Only
			$ho = $this->$model->get_headOffice();
		} 
		
        $data = array(
        'lot_date'				=> date("Y-m-d H:i:s"),
        'created_branch'		=> (isset($ho['id_branch']) ? $ho['id_branch']:NULL ),
        'lot_received_at'       => $ho['id_branch'],
        'stock_type'            => 1,
        'lot_from'              => 2,
        'po_id'                 => $addData['po_id'],
        'gold_smith'			=> (isset($addData['po_karigar_id']) ? $addData['po_karigar_id'] :NULL ),
        'id_category'			=> (isset($addData['id_category']) ? $addData['id_category'] :NULL ),
        'id_purity'				=> (isset($addData['id_purity']) ? $addData['id_purity'] :NULL ),
        'created_on'	  		=> date("Y-m-d H:i:s"),
        'created_by'      		=> $this->session->userdata('uid')
        ); 
        $this->db->trans_begin();
	 	$insId = $this->$model->insertData($data,'ret_lot_inwards');
	 	//echo "<pre>"; print_r($this->db->last_query());exit;
	 	if($insId)
	 	{
	 	    $po_order_details=$this->$model->get_purchase_orders_by_product($addData['po_id']);
	 	    //echo "<pre>"; print_r($po_order_details);exit;
	 	    foreach($po_order_details as $items)
	 	    {
	 	        $item_details = array(
                    'lot_no'	    =>$insId,
                    'lot_product'   =>($items['id_product']!='' ? $items['id_product']:NULL),
                    'no_of_piece'   =>($items['total_pcs']!='' ? $items['total_pcs']:0),
                    'gross_wt'      =>($items['total_gwt']!='' ? $items['total_gwt']:0),
                    'less_wt'       =>($items['total_lwt']!='' ? $items['total_lwt']:0),
                    'net_wt'        =>($items['total_nwt']!='' ? $items['total_nwt']:0),
                ); 
                $detail_insId = $this->$model->insertData($item_details,'ret_lot_inwards_detail');  
                //echo "<pre>"; print_r($this->db->last_query());exit;
	 	    }
	 	   
	 	    if($detail_insId)
	 	    {
	 	        $order_items=$this->$model->check_purchase_halmarking_details($addData['po_id']);
	 	        $total_payable_wt=0;
	 	        $total_mc_value=0;
	 	        $total_making_charge   =0;
	 	        foreach($order_items as $order)
	 	        {
	 	            $total_weight=($order['item_wastage']>0 ? ((($order['total_nwt']*$order['item_wastage'])/100)+$order['total_nwt']) : $order['total_nwt'] );
	 	            
	 	           // $pure_wt=(($total_weight*22)/24); // For calculating PURE WEIGHT (22CT to 24 CT)
	 	            $pure_wt = $order['item_pure_wt'];
	 	            
	 	            $total_mc_value+=($order['mc_type']==1 ? ($order['total_gwt']*$order['mc_value']):($order['total_pcs']*$order['mc_value']));
	 	            
	 	            $this->$model->updateData(array('is_lot_created'=>1,'lot_no'=>$insId,'item_pure_wt'=>$pure_wt),'po_item_id',$order['po_item_id'],'ret_purchase_order_items');
	 	            
	 	            $item_mc_value  =($order['mc_type']==1 ? ($order['total_gwt']*$order['mc_value']):($order['total_pcs']*$order['mc_value']));
	 	            
	 	            $total_payable_wt+=$pure_wt;
	 	            
	 	            
	 	             //Update Rate Fixing
	 	           
	 	            /*if($order['fix_rate_per_grm']>0 && $order['is_rate_fixed']==1)
	 	            {
	 	                $total_amount =($order['item_pure_wt']*$order['fix_rate_per_grm']);
	 	                
	 	                $tax_amt      = (($total_amount*$order['tax_percentage'])/100);
	 	                
	 	                $total_weight_amt+=$tax_amt+$total_amount;
	 	                
	 	                $rateFixData=array(
                           'rate_fix_po_item_id'    =>$order['po_item_id'],
                           'rate_fix_type'          =>1,
                           'rate_fix_wt'            =>$order['item_pure_wt'],
                           'tax_group_id'           =>$order['tgrp_id'],
                           'rate_fix_rate'          =>$order['fix_rate_per_grm'],
                           'rate_fix_created_from'  =>2,
                           'total_tax_amount'       =>$tax_amt,
                           'total_amount'           =>($tax_amt+$total_amount),
                           'rate_fix_create_by'     =>$this->session->userdata('uid'),
                           'rate_fix_created_on'    =>date("Y-m-d H:i:s"),
                           );
                           $insertId=$this->$model->insertData($rateFixData,'ret_po_rate_fix');
                          if($insertId)
    			          {
    			               $log_data = array(
                                	'id_log'        =>  $this->session->userdata('id_log'),
                                	'event_date'	=>  date("Y-m-d H:i:s"),
                                	'module'      	=> 'RATE FIXING',
                                	'operation'   	=> 'insert',
                                	'record'        =>  $order['po_item_id'],  
                                	'remark'       	=> 'Rate Fixed Successfully..'
                                );
                                $this->log_model->log_detail('insert','',$log_data);
    			          }
	 	            }
	 	            
	 	            if($order['mc_value']>0) // Update tax and insert into rate fix table for mc and other charges
	 	            {
	 	                $tax_amount=(($item_mc_value*$order['tax_percentage'])/100);
	 	                
	 	                $total_making_charge+=$tax_amount+$item_mc_value;
	 	                
	 	                $mc_payable_data=array(
                           'rate_fix_po_item_id'    =>$order['po_item_id'],
                           'rate_fix_type'          =>2,
                           'rate_fix_amt'           =>$item_mc_value,
                           'tax_group_id'           =>$order['tgrp_id'],
                           'rate_fix_rate'          =>0,
                           'rate_fix_created_from'  =>2,
                           'total_tax_amount'       =>$tax_amount,
                           'total_amount'           =>($tax_amount+$item_mc_value),
                           'rate_fix_create_by'     =>$this->session->userdata('uid'),
                           'rate_fix_created_on'    =>date("Y-m-d H:i:s"),
                           );
                           $insertId=$this->$model->insertData($mc_payable_data,'ret_po_rate_fix');
                          // print_r($this->db->last_query());exit;
	 	            }
	 	            */
	 	            //Update Rate Fixing
	 	            
	 	        }
	 	        
	 	        //Update Payable weight and amount
	 	        $payInsData=array(
	 	                        'total_payable_wt'=>number_format($total_payable_wt,3,'.',''),
	 	                       'total_payable_amt'=>number_format($total_weight_amt+$total_making_charge,2,'.',''),
	 	                        'po_id'=>$addData['po_id'],
	 	                        );

	 	        $paymentData=$this->$model->update_po_paymentData($payInsData,'+');
	 	        //print_r($this->db->last_query());exit;
	 	        //Update Payable weight and amount
            
	 	    }
	 	   
            if($this->db->trans_status()===TRUE)
            {
                
                $log_data = array(
                	'id_log'        => $this->session->userdata('id_log'),
                	'event_date'	=> date("Y-m-d H:i:s"),
                	'module'      	=> 'Lot',
                	'operation'   	=> 'Add',
                	'record'        => $insId,  
                	'remark'       	=> 'Lot added successfully From QC Receipt'
                );
                $this->log_model->log_detail('insert','',$log_data);
                        
                $this->db->trans_commit();
                $responseData=array('status'=>true,'message'=>'Lot Created successfully.');
            }	
            else
            {
                $this->db->trans_rollback();
                //echo $this->db->last_query();exit;
                $responseData=array('status'=>false,'message'=>'Unable to proceed the requested operation.');
            }
            
	 	}
    }
    
    
    function get_qc_status_details()
    {
        $model=	self::RET_PUR_ORDER_MODEL;
        $data= $this->$model->get_qc_status_details();
        echo json_encode($data);
    }
    
    //Lot generate
    function lot_generate($type="")
	{   
		$model=	self::RET_PUR_ORDER_MODEL;
		switch($type)
		{
			case 'list':
					$data['main_content'] = self::VIEW_FOLDER.'generate_lot/list';
        			$this->load->view('layout/template', $data);
			break;
			case 'add':
					$data['main_content'] = self::VIEW_FOLDER.'generate_lot/form';
        			$this->load->view('layout/template', $data);
			break;
			
			case 'po_details':
			    $data= $this->$model->get_qc_receipt_lot();
                echo json_encode($data);
			break;
			
			case 'qc_item_details':
			    $data= $this->$model->get_lot_generate_item_details($_POST);
                echo json_encode($data);
			break;
			
			case 'purchase_receipt':
			    $data= $this->$model->get_qc_issue_details();
                echo json_encode($data);
			break;
			
			case 'qc_entry':
					$data['main_content'] = self::VIEW_FOLDER.'qc_process/qc_entry';
        			$this->load->view('layout/template', $data);
			break;
			
			case 'ajax': 
				$list=$this->$model->get_purchase_qc_details($_POST); 
			  	$access = $this->admin_settings_model->get_access('admin_ret_purchase/qc_issue_receipt/list');
		        $data = array(
		        					'list'  => $list,
									'access'=> $access
		        				);  
				echo json_encode($data);
			break;
		}
	}
    //Lot generate
    
    
    //QC Issue / Receipt
    function qc_issue_receipt($type="")
	{   
		$model=	self::RET_PUR_ORDER_MODEL;
		switch($type)
		{
			case 'list':
					$data['main_content'] = self::VIEW_FOLDER.'qc_process/list';
        			$this->load->view('layout/template', $data);
			break;
			case 'add':
					$data['main_content'] = self::VIEW_FOLDER.'qc_process/form';
        			$this->load->view('layout/template', $data);
			break;
			
			case 'purchase_issue':
			    $data= $this->$model->purchase_issue();
                echo json_encode($data);
			break;
			
			case 'qc_item_details':
			    $data= $this->$model->get_purchase_item_details($_POST['po_id']);
                echo json_encode($data);
			break;
			
			case 'purchase_receipt':
			    $data= $this->$model->get_qc_issue_details();
                echo json_encode($data);
			break;
			
			case 'qc_entry':
					$data['main_content'] = self::VIEW_FOLDER.'qc_process/qc_entry';
        			$this->load->view('layout/template', $data);
			break;
			
			case 'ajax': 
				$list=$this->$model->get_purchase_qc_details($_POST); 
			  	$access = $this->admin_settings_model->get_access('admin_ret_purchase/qc_issue_receipt/list');
		        $data = array(
		        					'list'  => $list,
									'access'=> $access
		        				);  
				echo json_encode($data);
			break;
		}
	}
	
	function update_qc_issue()
	{
	    $model=	self::RET_PUR_ORDER_MODEL;
	    $id_employee = $_POST['id_employee'];
	    $total_pcs   = $_POST['total_pcs'];
	    $total_gwt   = $_POST['total_gwt'];
	    $total_lwt   = $_POST['total_lwt'];
	    $total_nwt   = $_POST['total_nwt'];
	    $req_data    = $_POST['req_data'];
	    $ref_no      = $this->$model->get_qc_ref_no();
	    $insData=array(
           'ref_no'         =>$ref_no,
           'qc_id_vendor'   =>$id_employee,
           'issue_gross_wt' =>$total_gwt,
           'issue_less_wt'  =>$total_lwt,
           'issue_net_wt'   =>$total_nwt,
           'issue_pcs'      =>$total_pcs,
           'created_at'     =>date("Y-m-d H:i:s"),
	       'created_by'     =>$this->session->userdata('uid')
          );
        $this->db->trans_begin();
        $insId=$this->$model->insertData($insData,'ret_po_qc_issue_process');
        if($insId)
        {
            foreach($req_data as $items)
    	    {
    	        $itemDetails=array(
    	                       'qc_process_id'  =>$insId,
    	                       'po_item_id'     =>$items['po_item_id'],
    	                       'issue_pcs'      =>$items['qc_issue_pcs'],
    	                       'issue_gwt'      =>$items['qc_issue_gross_wt'],
    	                       'issue_lwt'      =>$items['qc_issue_less_wt'],
    	                       'issue_nwt'      =>$items['qc_issue_net_wt'],
    	                      );
    	       $status=$this->$model->insertData($itemDetails,'ret_po_qc_issue_details');
    	       if($status)
    	       {
    	          	if(!empty($items['stone_details']))
    				{
    					$stone_details = json_decode($items['stone_details'],true);
    					foreach($stone_details as $val)
    					{
    						$stone_data = array(
    							'id_qc_issue_details'       => $status,
    							'po_st_id'                  => $val['po_st_id'],
    							'stone_pcs'                 => $val['stone_pcs'],
    							'stone_wt'                  => $val['stone_wt'],
    						);
    						$this->$model->insertData($stone_data, 'ret_po_qc_issue_stone_details');
    						//echo $this->db->last_query();exit;
    					}
    				}
    	       }
    	    }
    	    if($this->db->trans_status()===TRUE)
            {
                
                $log_data = array(
                        	'id_log'        => $this->session->userdata('id_log'),
                        	'event_date'	=> date("Y-m-d H:i:s"),
                        	'module'      	=> 'QUALITY CONTROL',
                        	'operation'   	=> 'Update',
                        	'record'        =>  NULL,  
                        	'remark'       	=> 'QC Issued Successfully..'
                        );
                $this->log_model->log_detail('insert','',$log_data);
            
                $this->db->trans_commit();
                $responseData=array('status'=>true,'message'=>'QC Issued successfully.');
                $this->session->set_flashdata('chit_alert',array('message'=>'QC Issued successfully.','class'=>'success','title'=>'QC Issue'));			
            }	
            else
            {
                $this->db->trans_rollback();
                $responseData=array('status'=>false,'message'=>'Unable to proceed the requested operation.');
                $this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'QC Issue'));
            }
            echo json_encode($responseData);
        }
	}
	
	
    //QC Issue / Receipt
    
    
    //Halmarking issue/receipt
    function halmarking_issue_receipt($type="")
	{   
		$model=	self::RET_PUR_ORDER_MODEL;
		switch($type)
		{
			case 'list':
					$data['main_content'] = self::VIEW_FOLDER.'halmarking_process/list';
        			$this->load->view('layout/template', $data);
			break;
			case 'add':
					$data['main_content'] = self::VIEW_FOLDER.'halmarking_process/form';
        			$this->load->view('layout/template', $data);
			break;
			
			case 'get_pending_halmarking_items':
			    $data= $this->$model->get_pending_halmarking_items();
                echo json_encode($data);
			break;
			
			case 'purchase_receipt':
			    $data= $this->$model->purchase_receipt_orders();
                echo json_encode($data);
			break;
			
			case 'hm_receipt':
					$data['main_content'] = self::VIEW_FOLDER.'halmarking_process/hm_receipt';
        			$this->load->view('layout/template', $data);
			break;
			
			case 'get_halmarking_issue_orders':
			    $data= $this->$model->get_halmarking_issue_orders();
                echo json_encode($data);
			break;
			
			case 'ajax': 
				$list=$this->$model->get_halmarking_details($_POST); 
			  	$access = $this->admin_settings_model->get_access('admin_ret_purchase/halmarking_issue_receipt/list');
		        $data = array(
		        					'list'  => $list,
									'access'=> $access
		        				);  
				echo json_encode($data);
			break;
		}
	}
	
	
	function update_halmarking_issue()
	{
	    $model=	self::RET_PUR_ORDER_MODEL;
	    $id_karigar  = $_POST['id_karigar'];
	    $total_pcs   = $_POST['total_pcs'];
	    $total_gwt   = $_POST['total_gwt'];
	    $total_lwt   = $_POST['total_lwt'];
	    $total_nwt   = $_POST['total_nwt'];
	    $req_data   =$_POST['req_data'];
	    $insData=array(
	       'hm_ref_no'              =>$this->$model->generate_HalmarkingRefNo(),
           'hm_vendor_id'           =>$id_karigar,
           'hm_process_gwt'         =>$total_gwt,
           'hm_process_lwt'         =>$total_lwt,
           'hm_process_nwt'         =>$total_nwt,
           'hm_process_pcs'         =>$total_pcs,
           'hm_process_created_at'  =>date("Y-m-d H:i:s"),
	       'hm_process_created_by'  =>$this->session->userdata('uid')
          );
        $this->db->trans_begin();
        $insId=$this->$model->insertData($insData,'ret_po_halmark_process');
        //print_r($this->db->last_query());exit;
        if($insId)
        {
            foreach($req_data as $items)
    	    {
    	        $itemDetails=array(
    	                       'hm_issue_id'    =>$insId,
    	                       'hm_po_item_id'  =>$items['po_item_id'],
    	                      );
    	       $status=$this->$model->insertData($itemDetails,'ret_po_hm_process_details');
    	       if($status)
    	       {
    	           $this->$model->updateData(array('status'=>3),'po_item_id',$items['po_item_id'],'ret_purchase_order_items');
    	       }
    	    }
    	    if($this->db->trans_status()===TRUE)
            {
                
                $log_data = array(
                        	'id_log'        => $this->session->userdata('id_log'),
                        	'event_date'	=> date("Y-m-d H:i:s"),
                        	'module'      	=> 'HALMARKING',
                        	'operation'   	=> 'Update',
                        	'record'        =>  NULL,  
                        	'remark'       	=> 'hm Issued Successfully..'
                        );
                $this->log_model->log_detail('insert','',$log_data);
                
                $this->db->trans_commit();
                $responseData=array('status'=>true,'message'=>'Halmarking Issued successfully.');
                $this->session->set_flashdata('chit_alert',array('message'=>'QC Issued successfully.','class'=>'success','title'=>'Halmarking Issue'));			
            }	
            else
            {
                $this->db->trans_rollback();
                $responseData=array('status'=>false,'message'=>'Unable to proceed the requested operation.');
                $this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Halmarking Issue'));
            }
            echo json_encode($responseData);
        }
	}
	
	function update_halmarking_receipt()
	{
	    $model=	self::RET_PUR_ORDER_MODEL;
	    $req_data   =$_POST['req_data'];
	    $hm_process_id   =$_POST['hm_process_id'];
	    $hm_vendor_ref_id = !empty($_POST['hm_vendor_ref_id']) ? $_POST['hm_vendor_ref_id'] : NULL;
        $this->db->trans_begin();
        $halmarking_status=$this->$model->updateData(array('status'=>2,'hm_received_at'=>date("Y-m-d H:i:s")),'hm_process_id',$hm_process_id,'ret_po_halmark_process');
        if($halmarking_status)
        {
            $total_hm_charges=0;
            foreach($req_data as $items)
    	    {
    	        $total_hm_charges+=$items['halmarking_charges'];
    	        $updData=array(
    	                      'status'              =>4,
    	                      'hm_rejected_pcs'     =>$items['hm_rejected_pcs'],
    	                      'hm_rejected_gwt'     =>$items['hm_rejected_gwt'],
    	                      'hm_rejected_lwt'     =>$items['hm_rejected_lwt'],
    	                      'hm_rejected_nwt'     =>$items['hm_rejected_nwt'],
    	                      'halmarking_charges'  =>$items['halmarking_charges'],
    	                      'is_halmark_from'     =>2,
    	                      'is_halmarked'        =>1
    	                      );
    	                      
    	       $status=$this->$model->updateData($updData,'po_item_id',$items['po_item_id'],'ret_purchase_order_items');
    	       //print_r($this->db->last_query());exit;
    	    }
    	    if($this->db->trans_status()===TRUE)
            {
                $this->$model->updateData(array('total_hm_charges'=>$total_hm_charges, 'hm_vendor_ref_id' => $hm_vendor_ref_id),'hm_process_id',$hm_process_id,'ret_po_halmark_process');
                
                //Generate LOT
                /*if($hm_process_id!='')
                {
                    $LotStatus=TRUE;
                    $pur_ord_det=$this->$model->get_purchase_order_hm_details($hm_process_id); // CHECKING QC STATUS AD H.M STATUS
                    foreach($pur_ord_det as $po_items)
                    {
                        if($po_items['status']==0)
                        {
                            $LotStatus=FALSE;
                        }
                    }
                    if($LotStatus)
                    {
                        $this->generate_lot_from_halmarking($hm_process_id);
                    }
                }*/
                //Generate LOT
                
                $log_data = array(
                        	'id_log'        => $this->session->userdata('id_log'),
                        	'event_date'	=> date("Y-m-d H:i:s"),
                        	'module'      	=> 'HALMARKING',
                        	'operation'   	=> 'Update',
                        	'record'        =>  $hm_process_id,  
                        	'remark'       	=> 'HM Updated Successfully..'
                        );
                $this->log_model->log_detail('insert','',$log_data);
                $this->db->trans_commit();
                $responseData=array('status'=>true,'message'=>'Halmarking Receipt added successfully.');
                $this->session->set_flashdata('chit_alert',array('message'=>'HM Receipt successfully.','class'=>'success','title'=>'Halmarking Receipt'));			
            }	
            else
            {
                $this->db->trans_rollback();
                $responseData=array('status'=>false,'message'=>'Unable to proceed the requested operation.');
                $this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Halmarking Issue'));
            }
	    }
        echo json_encode($responseData);
        
	}
	
    //Halmarking issue/receipt
    
    
    //Purchase Payment
    /*function supplier_po_payment($type="", $id="")
	{   
		$model=	self::RET_PUR_ORDER_MODEL;
		switch($type)
		{
			case 'list':
					$data['main_content'] = self::VIEW_FOLDER.'popayment/list';
        			$this->load->view('layout/template', $data);
			break;
			case 'add':
					$data['main_content'] = self::VIEW_FOLDER.'popayment/form';
        			$this->load->view('layout/template', $data);
			break;
			
			case 'purchase_payment_details':
			    $data= $this->$model->purchase_payment_details($_POST);
                echo json_encode($data);
			break;
			
			case 'save':
			    //echo "<pre>";print_r($_POST);echo "</pre>";exit;
			    $billing        = $_POST;
			    
			    if($billing['bill_type']==1)
			    {
			        $orderData  = $this->$model->get_purOrders($billing['po_ref_no']);
			        $pay_po_id  = $orderData['po_id'];
			    }else
			    {
			        $pay_po_id=NULL;
			    }
			    
			    
			    $pay_refno       = $this->$model->generatePaymentNo();
			    
		        $payData=array(
		                  'pay_sup_id'      => $billing['suppay']['pay_sup_id'],
		                  'bill_type'       => $billing['suppay']['bill_type'],
		                  'type'            => 1,
		                  'pay_refno'       => $pay_refno,
		                  'pay_amt'         => $billing['suppay']['received_amount'],
		                  // 'pay_create_on'   => date("Y-m-d H:i:s"),
		                  'pay_created_by'  => $this->session->userdata('uid'),
		                  );
			   
			    $this->db->trans_begin();
			    $insId = $this->$model->insertData($payData,'ret_po_payment');
			    
                if($insId)
                {
                    
                    //insert into po reference table
                    if($billing['suppay']['bill_type'] ==1) // If againist PO
                    {
                        //ret_supplier_pay_po_details
                        foreach( $billing['popay']['po_item_pay'] as $key => $val)
        		        {
        		            
        		            $payDetailData = array(
        		                  'po_pay_id'           => $insId,
        		                  'pay_po_ref_id'       => $billing['popay']['po_item_pay'][$key],
        		                  'pay_po_adj_amount'   => $billing['popay']['curpayable'][$key]
        		              );
        		              
        		              	$this->$model->insertData($payDetailData,'ret_supplier_pay_po_details');
        		            
        		        }
                               
                               
                    }
                    
                    
                    $net_banking_details = json_decode($billing['suppay']['net_banking'],true);
                    $sales_details		 = json_decode($billing['suppay']['sales_details'],true);
                    
                    if($billing['suppay']['bill_type']==2) // If Advance
                    {
                        $wallet = $this->$model->get_retWallet_details($billing['suppay']['pay_sup_id']);
    	 				if($wallet['status'])
    	 				{
    						$insWallet=array(
    						'id_wallet'	=>$wallet['id_wallet'],
    						'po_pay_id'	=>$insId,
    						'amount'	=>$billing['suppay']['received_amount'],
    						'type'	    =>1,
    						'remarks'   =>'Karigar Advace Amount'
    						 );
    						$this->$model->insertData($insWallet,'ret_karigar_wallet_transcation');
    						//echo "<pre>"; print_r($metal_rate);exit;
    	 				}
    	 				else
    	 				{
    	 				   $wallet_acc=array(
        	 				        'id_karigar'    =>$billing['suppay']['pay_sup_id'],
        	 				        'amount'        =>$billing['suppay']['received_amount'],
        	 				   	    'created_by'    =>$this->session->userdata('uid'),
        						    'created_on'    =>date("Y-m-d H:i:s")
    						    );
     					   $insWalletAcc= $this->$model->insertData($wallet_acc,'ret_karigar_wallet');
     					   //print_r($this->db->last_query());exit;
     					   if($insWalletAcc)
     					   {
    	 					   	$insWallet=array(
        						'id_wallet'	=>$insWalletAcc,
        						'po_pay_id'	=>$insId,
        						'amount'	=>$billing['suppay']['received_amount'],
        						'type'	    =>1,
        						'remarks'   =>'Karigar Advace Amount'
        						 );
        						$this->$model->insertData($insWallet,'ret_karigar_wallet_transcation');
        						//print_r($this->db->last_query());exit;
     					   }
     					   
    	 				}
                    }
                    
                    if(sizeof($net_banking_details)>0)
                    {
                        foreach($net_banking_details as $pay)
                        {
                            $netBankingPaymentData[]=array(
                                             'pay_id'           =>$insId,
                                             'type'             =>1,
                                             'pay_mode'         =>$pay['nb_type'],
                                             'payment_amount'   =>$pay['pay_amount'],
                                             'ref_no'           =>($pay['ref_no']!='' ? $pay['ref_no']:NULL),
                                            );
                            $this->$model->insertBatchData($netBankingPaymentData,'ret_po_payment_detail');
                        }
                    }
                    
                    if(sizeof($sales_details)>0)
                    {
                        foreach($sales_details as $pay)
                        {
                            $SalesPaymentData[]=array(
                                             'pay_id'         =>$insId,
                                             'type'           =>2,
                                             'payment_amount' =>$pay['pay_amount'],
                                             'bill_id'        =>$pay['bill_id'],
                                            );
                            $this->$model->insertBatchData($SalesPaymentData,'ret_po_payment_detail');
                        }
                    }
                    
                    
                    	
                    if($this->db->trans_status()===TRUE)
                    {
                        $log_data = array(
                                	'id_log'        => $this->session->userdata('id_log'),
                                	'event_date'	=> date("Y-m-d H:i:s"),
                                	'module'      	=> 'PURCHASE PAYMENT',
                                	'operation'   	=> 'insert',
                                	'record'        =>  $orderData['po_id'],  
                                	'remark'       	=> 'Purchase Payment Successfully..'
                                );
                        $this->log_model->log_detail('insert','',$log_data);
                        
                        
                        
                        $this->db->trans_commit();
                        $responseData=array('status'=>true,'message'=>'Receipt added successfully.');
                        $this->session->set_flashdata('chit_alert',array('message'=>'Receipt successfully.','class'=>'success','title'=>'Purchase Payment'));			
                    }	
                    else
                    {
                        echo $this->db->last_query();exit;
                        $this->db->trans_rollback();
                        $responseData=array('status'=>false,'message'=>'Unable to proceed the requested operation.');
                        $this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Purchase Payment'));
                    }
                }else
                {
                        echo $this->db->last_query();exit;
                        $this->db->trans_rollback();
                        $responseData=array('status'=>false,'message'=>'Unable to proceed the requested operation.');
                        $this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Purchase Payment'));
                   
                }
                
                echo json_encode($responseData);
			 break;
			
			case 'pendingpos':
			    $pendingpos = $this->$model->get_karigarPendingPos($this->input->post('id_karigar'));
			    echo json_encode(array('status' => TRUE, 'pendingpos' => $pendingpos));
			break;
			
			case 'paymenthistory':
			    $paymenthistory = $this->$model->get_karigarPosPaidHistory($this->input->post('id_karigar'));
			    echo json_encode(array('status' => TRUE, 'payhistory' => $paymenthistory));
			break;
			
			case 'paymentacknolodgement':
			    $set_model = "admin_settings_model";
        		$data['comp_details']   = $this->$set_model->get_company();
        		$data['paymentdetails'] = $this->$model->get_po_paid_detail($id);
        		
        		//echo "<pre>"; print_r($data);exit;
        		$this->load->helper(array('dompdf', 'file'));
        	        $dompdf = new DOMPDF();
        			$html = $this->load->view(self::VIEW_FOLDER.'popayment/payment_ack', $data,true);
        			$dompdf->load_html($html);
        			$dompdf->set_paper('A4', "portriat" );
        			$dompdf->render();
        			$dompdf->stream("VendorAck.pdf",array('Attachment'=>0));
			break;
			
			case 'ajax': 
				$list=$this->$model->get_PurchaseSupplierPaymentList($_POST); 
			  	$access = $this->admin_settings_model->get_access('admin_ret_purchase/halmarking_issue_receipt/list');
		        $data = array(
		        					'list'  => $list,
									'access'=> $access
		        				);  
				echo json_encode($data);
			break;
		}
	}*/
	
	
	function supplier_po_payment($type="", $id="")
	{   
		$model=	self::RET_PUR_ORDER_MODEL;
		switch($type)
		{
			case 'list':
					$data['main_content'] = self::VIEW_FOLDER.'popayment/list';
        			$this->load->view('layout/template', $data);
			break;
			case 'add':
					$data['main_content'] = self::VIEW_FOLDER.'popayment/form';
        			$this->load->view('layout/template', $data);
			break;
			
			case 'purchase_payment_details':
			    $data= $this->$model->purchase_payment_details($_POST);
                echo json_encode($data);
			break;
			
			case 'get_supplier_pay_details':
			    $data= $this->$model->get_supplier_pay_details($_POST);
                echo json_encode($data);
			break;
			
			case 'supplier_advance_details':
			    $data= $this->$model->get_supplier_advance_details($_POST);
                echo json_encode($data);
			break;
			
			case 'edit':
								
            	$data['main_content'] = self::VIEW_FOLDER.'popayment/form';
                    		
            	$this->load->view('layout/template', $data);
            			
            break;
            
            
            
            
            case 'po_pay_details':
            
            	$data = $this->$model->get_po_payment($id);
            
            	echo json_encode($data);
            			
            break;
			
			case 'cancel_pay_entry':
			    $ho              =  $this->$model->get_headOffice();
        		$dCData          =  $this->admin_settings_model->getBranchDayClosingData($ho['id_branch']);
        		$bill_date       =  ($dCData['entry_date'] == date("Y-m-d") ? date("Y-m-d H:i:s") : $dCData['entry_date']);
        		$pay_id      = $_POST['pay_id'];
			    $updData=array(
                    'pay_status'        => 2,
                    'cancel_reason'     => $_POST['cancel_reason'],
                    'cancelled_date'    => $bill_date,
                    'cancelled_by'      => $this->session->userdata('uid')
                    );
                    $this->db->trans_begin();
                    $ret_status = $this->$model->updateData($updData,'pay_id',$pay_id,'ret_po_payment');
                    if($this->db->trans_status()===TRUE)
                    {
                        $this->db->trans_commit();
                        $this->session->set_flashdata('chit_alert',array('message'=>'Payment Cancelled Successfully','class'=>'success','title'=>'Purchase Payment')); 
                        $return_data=array('status'=>TRUE,'message'=>'Order Instructions Added Successfully..');
                    }
                    else
                    { 
                        echo $this->db->last_query();exit;
                        $this->db->trans_rollback();						 	
                        $this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Purchase Payment')); 
                        $return_data=array('status'=>FALSE,'message'=>'Unable to proceed the requested process');
                    }
                echo json_encode($return_data);
			break;
			
			case 'save':
			    $billing        =$_POST['billing'];
			    
			    $pay_refno       = $this->$model->generatePaymentNo();
			    
		        $payData[]=array(
		                  'pay_sup_id'      =>$billing['id_karigar'],
		                  'bill_type'       =>$billing['bill_type'],
		                  'pay_refno'       =>$pay_refno,
		                  'pay_amt'         =>$billing['tot_amt_received'],
		                  'pay_narration'   =>($billing['remark']!='' ? $billing['remark']:NULL),
		                  'pay_create_on'   =>date("Y-m-d H:i:s"),
		                  'pay_created_by'=>$this->session->userdata('uid'),
		                  );
			   
			    $this->db->trans_begin();
			    $insId=$this->$model->insertBatchData($payData,'ret_po_payment');
			    
                if($insId)
                {
                    $net_banking_details = json_decode($billing['net_banking'],true);
                    //$sales_details		 = json_decode($billing['sales_details'],true);
                    //$adv_details		 = json_decode($billing['adv_details'],true);
                    
                    if($billing['bill_type']==2)
                    {
                        $wallet=$this->$model->get_retWallet_details($billing['id_karigar']);
    	 				if($wallet['status'])
    	 				{
    	 				    $updStatus=$this->$model->updateWalletData(array('amount'=>$billing['tot_amt_received'],'id_karigar'=>$billing['id_karigar']),'+');
    	 				     
    						$insWallet=array(
    						'id_wallet'	=>$wallet['id_wallet'],
    						'po_pay_id'	=>$insId,
    						'amount'	=>$billing['tot_amt_received'],
    						'type'	    =>1,
    						'remarks'   =>($billing['remark']!='' ? $billing['remark']:NULL)
    						 );
    						$this->$model->insertData($insWallet,'ret_karigar_wallet_transcation');
    						//echo "<pre>"; print_r($metal_rate);exit;
    	 				}
    	 				else
    	 				{
    	 				   $wallet_acc=array(
        	 				        'id_karigar'    =>$billing['id_karigar'],
        	 				        'amount'        =>$billing['tot_amt_received'],
        	 				   	    'created_by'    =>$this->session->userdata('uid'),
        						    'created_on'    =>date("Y-m-d H:i:s")
    						    );
     					   $insWalletAcc= $this->$model->insertData($wallet_acc,'ret_karigar_wallet');
     					   //print_r($this->db->last_query());exit;
     					   if($insWalletAcc)
     					   {
    	 					   	$insWallet=array(
        						'id_wallet'	=>$insWalletAcc,
        						'po_pay_id'	=>$insId,
        						'amount'	=>$billing['tot_amt_received'],
        						'type'	    =>1,
        						'remarks'   =>($billing['remark']!='' ? $billing['remark']:NULL)
        						 );
        						$this->$model->insertData($insWallet,'ret_karigar_wallet_transcation');
        						//print_r($this->db->last_query());exit;
     					   }
     					   
    	 				}
                    }
                    if($billing['cash_amount']!=0)
                    {
                        $cash_payment_data = array(
                                             'pay_id'           =>$insId,
                                             'type'             =>4,
                                             'pay_mode'         =>'CSH',
                                             'payment_amount'   =>$billing['cash_amount'],
                                            );
                         $this->$model->insertData($cash_payment_data,'ret_po_payment_detail');
                    }
                    
                    if(sizeof($net_banking_details)>0)
                    {
                        foreach($net_banking_details as $pay)
                        {
                            $netBankingPaymentData[]=array(
                                             'pay_id'           =>$insId,
                                             'type'             =>1,
                                             'pay_mode'         =>$pay['nb_type'],
                                             'payment_amount'   =>$pay['pay_amount'],
                                             'ref_no'           =>($pay['ref_no']!='' ? $pay['ref_no']:NULL),
                                             'id_bank'          =>($pay['id_bank']!='' ? $pay['id_bank']:NULL),
                                             'ref_date'          =>($pay['nb_date']!='' ? $pay['nb_date']:date("Y-m-d")),
                                            );
                        }
                        $this->$model->insertBatchData($netBankingPaymentData,'ret_po_payment_detail');
                    }
                    
                   
                    if($this->db->trans_status()===TRUE)
                    {
                        $log_data = array(
                                	'id_log'        => $this->session->userdata('id_log'),
                                	'event_date'	=> date("Y-m-d H:i:s"),
                                	'module'      	=> 'PURCHASE PAYMENT',
                                	'operation'   	=> 'insert',
                                	'record'        =>  $insId,  
                                	'remark'       	=> 'Purchase Payment Successfully..'
                                );
                        $this->log_model->log_detail('insert','',$log_data);
                        
                        
                        
                        $this->db->trans_commit();
                        $responseData=array('status'=>true,'message'=>'Receipt added successfully.');
                        $this->session->set_flashdata('chit_alert',array('message'=>'Receipt successfully.','class'=>'success','title'=>'Purchase Payment'));			
                    }	
                    else
                    {
                        echo $this->db->last_query();exit;
                        $this->db->trans_rollback();
                        $responseData=array('status'=>false,'message'=>'Unable to proceed the requested operation.');
                        $this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Purchase Payment'));
                    }
                }else
                {
                        echo $this->db->last_query();exit;
                        $this->db->trans_rollback();
                        $responseData=array('status'=>false,'message'=>'Unable to proceed the requested operation.');
                        $this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Purchase Payment'));
                   
                }
                
                echo json_encode($responseData);

			 break;
			 
			 case 'update':
				
            	$updData = $_POST;
            
            	$id=$updData['billing']['pay_id'];
            
            	$data=array(
            
            		'pay_amt'         =>$updData['billing']['tot_amt_received'],
            		            
            		'pay_narration'   =>($updData['billing']['remark']!='' ? $updData['billing']['remark']:NULL),
            
            	);
            
            	$this->db->trans_begin();
            				
            				
            	$update_status = $this->$model->updateData($data,'pay_id',$id, 'ret_po_payment');
            
            				
            	//print_r($this->db->last_query());exit;
            
            
            
            				
            
            	if($update_status)
            	{
            
            		$net_banking_details = json_decode($updData['billing']['net_banking'],true);
            
            		$nb_delete_id = $this->$model->deleteData("pay_id",$id,'ret_po_payment_detail');
            
            		if(sizeof($net_banking_details)>0)
            		{
            
            			foreach($net_banking_details as $pay)
            			{
            				$netBankingPaymentData[]=array(
            					'pay_id'           =>$id,
            					'type'             =>1,
            					'pay_mode'         =>$pay['nb_type'],
            					'payment_amount'   =>$pay['pay_amount'],
            					'ref_no'           =>($pay['ref_no']!='' ? $pay['ref_no']:NULL),
            					'id_bank'          =>($pay['id_bank']!='' ? $pay['id_bank']:NULL),
            					'ref_date'          =>($pay['nb_date']!='' ? $pay['nb_date']:date("Y-m-d")),
            				);
            
            			}
            
            			$this->$model->insertBatchData($netBankingPaymentData,'ret_po_payment_detail');
            
            		}
            
            	}
            
            
            		if($this->db->trans_status()===TRUE)
                                {                    
                                    
                                    $this->db->trans_commit();
                                    $responseData=array('status'=>true,'message'=>'Receipt updated successfully.');
                                    $this->session->set_flashdata('chit_alert',array('message'=>'Receipt updated successfully.','class'=>'success','title'=>'Purchase Payment'));			
                                }	
                                else
                                {
                                    echo $this->db->last_query();exit;
                                    $this->db->trans_rollback();
                                    $responseData=array('status'=>false,'message'=>'Unable to proceed the requested operation.');
                                    $this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Purchase Payment'));
                                }
                            
                            
                            echo json_encode($responseData);
            
            break;
			
			case 'pendingpos':
			    $pendingpos = $this->$model->get_karigarPendingPos($this->input->post('id_karigar'));
			    echo json_encode(array('status' => TRUE, 'pendingpos' => $pendingpos));
			break;
			
			case 'paymenthistory':
			    $paymenthistory = $this->$model->get_karigarPosPaidHistory($this->input->post('id_karigar'));
			    echo json_encode(array('status' => TRUE, 'payhistory' => $paymenthistory));
			break;
			
			case 'paymentacknolodgement':
			    $set_model = "admin_settings_model";
        		$data['comp_details']   = $this->$set_model->get_company();
        		$data['paymentdetails'] = $this->$model->get_po_paid_detail($id);
        		
        		//echo "<pre>"; print_r($data);exit;
        		$this->load->helper(array('dompdf', 'file'));
        	        $dompdf = new DOMPDF();
        			$html = $this->load->view(self::VIEW_FOLDER.'popayment/payment_ack', $data,true);
        			$dompdf->load_html($html);
        			$dompdf->set_paper('A4', "portriat" );
        			$dompdf->render();
        			$dompdf->stream("VendorAck.pdf",array('Attachment'=>0));
			break;
			
			case 'ajax': 
			    $from_date	= $this->input->post('from_date');
			    $to_date	= $this->input->post('to_date'); 
				$list       = $this->$model->get_PurchaseSupplierPaymentList($from_date, $to_date);
			  	$access = $this->admin_settings_model->get_access('admin_ret_purchase/supplier_po_payment/list');
		        $data = array(
		        					'list'  => $list,
									'access'=> $access
		        				);  
				echo json_encode($data);
			break;
		}
	}
	
    //Purchase Payment
    
    
    
    
    //Purchase Payment
    function purchase_payment($type="")
	{   
		$model=	self::RET_PUR_ORDER_MODEL;
		switch($type)
		{
			case 'list':
					$data['main_content'] = self::VIEW_FOLDER.'payment/list';
        			$this->load->view('layout/template', $data);
			break;
			case 'add':
					$data['main_content'] = self::VIEW_FOLDER.'payment/form';
        			$this->load->view('layout/template', $data);
			break;
			
			case 'purchase_payment_details':
			    $data= $this->$model->purchase_payment_details($_POST);
                echo json_encode($data);
			break;
			
			case 'save':
			    //echo "<pre>";print_r($_POST);exit;
			    $billing        =$_POST['billing'];
			    
			    $pay_refno       = $this->$model->generatePaymentNo();
			    
		        $payData[]=array(
		                  'pay_sup_id'      =>$billing['id_karigar'],
		                  'bill_type'       =>$billing['bill_type'],
		                  'pay_refno'       =>$pay_refno,
		                  'pay_amt'         =>$billing['tot_amt_received'],
		                  'pay_narration'   =>($billing['remark']!='' ? $billing['remark']:NULL),
		                  'pay_create_on'   =>date("Y-m-d H:i:s"),
		                  'pay_created_by'=>$this->session->userdata('uid'),
		                  );
			   
			    $this->db->trans_begin();
			    $insId=$this->$model->insertBatchData($payData,'ret_po_payment');
			    
                if($insId)
                {
                    $net_banking_details = json_decode($billing['net_banking'],true);
                    $sales_details		 = json_decode($billing['sales_details'],true);
                    
                    if($billing['bill_type']==2)
                    {
                        $wallet=$this->$model->get_retWallet_details($billing['id_karigar']);
    	 				if($wallet['status'])
    	 				{
    	 				    $updStatus=$this->$model->updateWalletData(array('amount'=>$billing['tot_amt_received'],'id_wallet'=>$wallet['id_wallet']),'+');
    	 				     
    						$insWallet=array(
    						'id_wallet'	=>$wallet['id_wallet'],
    						'po_pay_id'	=>$insId,
    						'amount'	=>$billing['tot_amt_received'],
    						'type'	    =>1,
    						'remarks'   =>($billing['remark']!='' ? $billing['remark']:NULL)
    						 );
    						$this->$model->insertData($insWallet,'ret_karigar_wallet_transcation');
    						//echo "<pre>"; print_r($metal_rate);exit;
    	 				}
    	 				else
    	 				{
    	 				   $wallet_acc=array(
        	 				        'id_karigar'    =>$billing['id_karigar'],
        	 				        'amount'        =>$billing['tot_amt_received'],
        	 				   	    'created_by'    =>$this->session->userdata('uid'),
        						    'created_on'    =>date("Y-m-d H:i:s")
    						    );
     					   $insWalletAcc= $this->$model->insertData($wallet_acc,'ret_karigar_wallet');
     					   //print_r($this->db->last_query());exit;
     					   if($insWalletAcc)
     					   {
    	 					   	$insWallet=array(
        						'id_wallet'	=>$insWalletAcc,
        						'po_pay_id'	=>$insId,
        						'amount'	=>$billing['tot_amt_received'],
        						'type'	    =>1,
        						'remarks'   =>($billing['remark']!='' ? $billing['remark']:NULL)
        						 );
        						$this->$model->insertData($insWallet,'ret_karigar_wallet_transcation');
        						//print_r($this->db->last_query());exit;
     					   }
     					   
    	 				}
                    }
                    
                    if(sizeof($net_banking_details)>0)
                    {
                        foreach($net_banking_details as $pay)
                        {
                            $netBankingPaymentData[]=array(
                                             'pay_id'           =>$insId,
                                             'type'             =>1,
                                             'pay_mode'         =>$pay['nb_type'],
                                             'payment_amount'   =>$pay['pay_amount'],
                                             'ref_no'           =>($pay['ref_no']!='' ? $pay['ref_no']:NULL),
                                            );
                            $this->$model->insertBatchData($netBankingPaymentData,'ret_po_payment_detail');
                        }
                    }
                    
                    if(sizeof($sales_details)>0)
                    {
                        foreach($sales_details as $pay)
                        {
                            $SalesPaymentData[]=array(
                                             'pay_id'         =>$insId,
                                             'type'           =>2,
                                             'payment_amount' =>$pay['pay_amount'],
                                             'bill_id'        =>$pay['bill_id'],
                                            );
                            $this->$model->insertBatchData($SalesPaymentData,'ret_po_payment_detail');
                        }
                    }
                    	
                    if($this->db->trans_status()===TRUE)
                    {
                        $log_data = array(
                                	'id_log'        => $this->session->userdata('id_log'),
                                	'event_date'	=> date("Y-m-d H:i:s"),
                                	'module'      	=> 'PURCHASE PAYMENT',
                                	'operation'   	=> 'insert',
                                	'record'        =>  $insId,  
                                	'remark'       	=> 'Purchase Payment Successfully..'
                                );
                        $this->log_model->log_detail('insert','',$log_data);
                        
                        
                        
                        $this->db->trans_commit();
                        $responseData=array('status'=>true,'message'=>'Receipt added successfully.');
                        $this->session->set_flashdata('chit_alert',array('message'=>'Receipt successfully.','class'=>'success','title'=>'Purchase Payment'));			
                    }	
                    else
                    {
                        echo $this->db->last_query();exit;
                        $this->db->trans_rollback();
                        $responseData=array('status'=>false,'message'=>'Unable to proceed the requested operation.');
                        $this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Purchase Payment'));
                    }
                }else
                {
                        echo $this->db->last_query();exit;
                        $this->db->trans_rollback();
                        $responseData=array('status'=>false,'message'=>'Unable to proceed the requested operation.');
                        $this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Purchase Payment'));
                   
                }
                
                echo json_encode($responseData);
			 break;
			
			
			case 'ajax': 
				$list=$this->$model->get_PurchasePaymentList($_POST); 
			  	$access = $this->admin_settings_model->get_access('admin_ret_purchase/halmarking_issue_receipt/list');
		        $data = array(
		        					'list'  => $list,
									'access'=> $access
		        				);  
				echo json_encode($data);
			break;
		}
	}
    //Purchase Payment
    
    //rate fixing
    
    function get_rate_fixing_po_no()
    {
        $model=	self::RET_PUR_ORDER_MODEL;
        $data= $this->$model->get_rate_fixing_po_no($_POST);
        echo json_encode($data);
    }
    
    function rate_fixing($type="")
	{   
		$model=	self::RET_PUR_ORDER_MODEL;
		switch($type)
		{
			case 'list':
					$data['main_content'] = self::VIEW_FOLDER.'rate_fixing/list';
        			$this->load->view('layout/template', $data);
			break;
			case 'add':
					$data['main_content'] = self::VIEW_FOLDER.'rate_fixing/form';
        			$this->load->view('layout/template', $data);
			break;
			
			case 'rate_fixing_items':
			    $data= $this->$model->get_rate_fixing_items($_POST);
                echo json_encode($data);
			break;
			
			case 'save':
		        $item_details=$_POST['rate_fixing_items'];
		
		        if(!empty($item_details))
		        {
    		        foreach($item_details['po_item_id'] as $key => $val)
    		        {
    		            if($item_details['fix_weight'][$key]!='' && $item_details['fix_weight'][$key]>0)
    		            {
    		             
    		                //$order              = $this->$model->get_purchase_order_item_search($item_details['po_item_id'][$key]); 
    		                $total_amount       = ($item_details['fix_weight'][$key]*$item_details['rate_per_gram'][$key]);
    	 	                $tax_amt            = (($total_amount*$order['tax_percentage'])/100);
    	 	                $rateFixData=array(
                               'rate_fix_po_item_id'    =>$item_details['po_item_id'][$key],
                               'rate_fix_type'          =>1,
                               'rate_fix_wt'            =>$item_details['fix_weight'][$key],
                               'rate_fix_rate'          =>$item_details['rate_per_gram'][$key],
                               'rate_fix_created_from'  =>2,
                               'total_amount'           =>$item_details['payable_amt'][$key],
                               'rate_fix_create_by'     =>$this->session->userdata('uid'),
                               'rate_fix_created_on'    =>date("Y-m-d H:i:s"),
                               );
                              $this->db->trans_begin();
                              $insId=$this->$model->insertData($rateFixData,'ret_po_rate_fix');

    			          if($insId)
    			          {  
    			             $log_data = array(
                                	'id_log'        => $this->session->userdata('id_log'),
                                	'event_date'	=> date("Y-m-d H:i:s"),
                                	'module'      	=> 'RATE FIXING',
                                	'operation'   	=> 'insert',
                                	'record'        =>  $item['po_item_id'][$key],  
                                	'remark'       	=> 'Rate Fixed Successfully..'
                                );
                                $this->log_model->log_detail('insert','',$log_data);
    			          }
    		            }
    		        }
    		        if($this->db->trans_status()===TRUE)
                    {
                        $this->db->trans_commit();
                        $responseData=array('status'=>true,'message'=>'Rate Fixed successfully.');
                        $this->session->set_flashdata('chit_alert',array('message'=>'Rate Fixed successfully.','class'=>'success','title'=>'Rate Fixing'));			
                    }	
                    else
                    {
                        echo $this->db->last_query();exit;
                        $this->db->trans_rollback();
                        $responseData=array('status'=>false,'message'=>'Unable to proceed the requested operation.');
                        $this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Rate Fixing'));
                    }
		        }else
		        {
		            $responseData=array('status'=>false,'message'=>'Unable to proceed the requested operation.');
		        }
                echo json_encode($responseData);
			break;
			
			
			default: 
			   	$from_date	= $this->input->post('from_date');
			    $to_date	= $this->input->post('to_date'); 
				$list       = $this->$model->get_PO_Ratefix_List($from_date, $to_date); 
		  	    $access     = $this->admin_settings_model->get_access('admin_ret_purchase/rate_fixing/list');
		        $data = array(
		        					'list'  => $list,
									'access'=> $access
		        				);  
				echo json_encode($data);
			break;
		}
	}
    //rate fixing
    
	public function getOrderNosBySearch(){
		$model=	self::RET_PUR_ORDER_MODEL;
		$data = $this->$model->getAvailableOrders($_POST['searchTxt'], $_POST['supplierId']);	  
		echo json_encode($data);
	}
	
	
	public function get_bill_details()
	{
		$model=	self::RET_PUR_ORDER_MODEL;
		$searchTxt=$this->input->post('searchTxt');
		$data=$this->$model->get_bill_details($searchTxt,$bill_cus_id);
		echo json_encode($data);
	}
	
	//Purchase return
	
	public function returnpoitems()
	{
		$model =	self::RET_PUR_ORDER_MODEL;

		$item_details = $_POST['returnitems'];
		
		$item_category_details = $_POST['returnpuritems'];

		$pur_ret_ref_no = $this->$model->pur_ret_refno($_POST['purchase_type']);
		$ho              =  $this->$model->get_headOffice();
		$dCData          =  $this->admin_settings_model->getBranchDayClosingData($ho['id_branch']);
		$bill_date       =  ($dCData['entry_date'] == date("Y-m-d") ? date("Y-m-d H:i:s") : $dCData['entry_date']);
		
		$purchasereturn = array(
			"pur_ret_supplier_id"                   => $_POST['id_karigar'],
			"bill_date"                             => $bill_date,
			"pur_ret_ref_no"                        => $pur_ret_ref_no,
			"pur_ret_reason"                        => $_POST['returnreason'], 
			"pur_ret_remark"                        => $_POST['narration'], 
			"stock_type"                            => $_POST['stock_type'],
			"purchase_type"                         => $_POST['purchase_type'],
			"return_total_cost"                     => ($_POST['returnamount']!='' ? $_POST['returnamount']:0), 
			"pur_ret_round_off"                     => ($_POST['returnroundoff']!='' ? ($_POST['round_off_type']==1 ? '+'.$_POST['returnroundoff'] :'-'.$_POST['returnroundoff']):0),
			"pur_ret_discount"                      => ($_POST['return_discount']!='' ? $_POST['return_discount']:0),
			"pur_ret_other_charges"                 => ($_POST['other_charges']!='' ? $_POST['other_charges']:0),
			"pur_ret_tds_percent"                   => ($_POST['tds_percent']!='' ? $_POST['tds_percent']:0),
			"pur_ret_tds_value"                     => ($_POST['tds_tax_value']!='' ? $_POST['tds_tax_value']:0),
			"pur_ret_other_charges_tds_percent"     => ($_POST['charges_tds_percent']!='' ? $_POST['charges_tds_percent']:0),
			"pur_ret_other_charges_tds_value"       => ($_POST['other_charges_tds_tax_value']!='' ? $_POST['other_charges_tds_tax_value']:0),
			"pur_ret_tcs_percent"                   => ($_POST['tcs_percent']!='' ? $_POST['tcs_percent']:0),
			"pur_ret_tcs_value"                     => ($_POST['tcs_tax_value']!='' ? $_POST['tcs_tax_value']:0), 
			"pur_ret_created_by"                    => $this->session->userdata('uid') 
		);
		$this->db->trans_begin();
		$insId = $this->$model->insertData($purchasereturn,'ret_purchase_return');

		foreach($item_category_details as $key => $val)
		{
			//echo "<pre>"; print_r($val);exit;

			$purchasereturn_items = array(

				"pur_ret_id"            => $insId, 
				"return_item_type"      => $val['ret_item_type'],
				"pur_ret_po_item_id"    => $val['po_item_id'],
				"id_qc_issue_details"   => $val['id_qc_issue_details'],
				"tag_id"                => $val['tag_id'],
				'id_product'            => $val['pro_id'],
				'id_design'             => $val['des_id'],
				'id_sub_design'         => $val['id_sub_design'],
				"pur_ret_pcs"           => $val['purretpcs'],
				"pur_ret_gwt"           => $val['purretgwt'],
				"pur_ret_lwt"           => $val['purretlwt'],
				"pur_ret_nwt"           => $val['purretnwt'],
				"pur_ret_pur_wt"        => $val['purreturnpure'],
				"pur_ret_purchase_touch"=> $val['purreturntouch'],
				"pur_ret_wastage"       => $val['purreturnwastper'],
				"pur_ret_wastage_wt"    => $val['purreturnwastwgt'],
				"pur_ret_mc_type"       => $val['purreturnmctype'],
				"pur_ret_mc_value"      => $val['purreturnmc'],
				"pur_ret_debit_note_amt" => $val['ret_item_cost'],
				"pur_ret_tax_id"         => $val['return_item_tax_id'],
                "pur_ret_tax_percent"    => $val['return_item_tax_percent'],
                "pur_ret_tax_type"       => $val['return_item_tax_type'],
                "total_cgst_cost"        => $val['total_cgst_cost'],
                "total_sgst_cost"        => $val['total_sgst_cost'],
                "total_igst_cost"        => $val['total_igst_cost'],
                "total_total_tax"        => $val['total_total_tax'],
                "calculation_based_on"   => $val['calculation_based_on'],
                "pure_wt_calc_type"      => $val['pure_wt_calc_type'],
                "pur_ret_rate"           => $val['pur_ret_rate'],
			);

			$insert_item_id = $this->$model->insertData($purchasereturn_items,'ret_purchase_return_items');
		

			if($insert_item_id)
			{
				$ledger_data = array(
					'trans_date'        =>$bill_date,
					'id_karigar'        =>$_POST['id_karigar'],
					// 'cat_id'         =>$orderDetails['id_category'][$key],
					'trans_type'        =>1,//Credit
					'trans_rec_type'    =>1,//Weight
					'trans_pcs'         =>$val['purretpcs'],
					'trans_grswt'       =>$val['purretgwt'],
					'trans_netwt'       =>$val['purretnwt'],
					'trans_purewt'      =>$val['purreturnpure'],
					'trans_screen_id'   =>4,//Supplier Bill entry
					'ref_id'            =>$insert_item_id,
					'remarks'           =>'From Purchase Return',
					'created_by'        =>$this->session->userdata('uid'),
					'created_on'        =>date("Y-m-d H:i:s"),
				);

				$this->$model->insertData($ledger_data,'ret_supplier_ledger_log');

				if($val['tag_id']!='')
				{

					$tagDetails = $this->$model->get_tag_details($val['tag_id']);
		            $dCData = $this->admin_settings_model->getBranchDayClosingData($tagDetails['current_branch']);
		            $bill_date = ($dCData['entry_date'] == date("Y-m-d") ? date("Y-m-d H:i:s") : $dCData['entry_date']);
                    $tagStatus=$this->$model->updateData(array('tag_status'=>9,'updated_by'=>$this->session->userdata('uid'),'updated_time'=>date("Y-m-d H:i:s")),'tag_id',$val['tag_id'],'ret_taging');
					if($tagStatus)
                    {
                        //Update Tag Log status
                            $tag_log=array(
                            'tag_id'	            =>$val['tag_id'],
                            'date'		            =>$bill_date,
                            'status'	            =>14,
                            'from_branch'           =>$tagDetails['current_branch'],
                            'to_branch'	            =>NULL,
                            'issuspensestock'	    =>$_POST['stock_type'],
                            'created_on'            =>date("Y-m-d H:i:s"),
                            'created_by'            =>$this->session->userdata('uid'),
                            );
                            $this->$model->insertData($tag_log,'ret_taging_status_log');
                    }
				}
				else
				{
					$existData=array('id_product'=>$val['pro_id'],'design'=>$val['des_id'],'id_sub_design'=>$val['id_sub_design'],'id_branch'=>$ho['id_branch']);

					//print_r($existData);exit;


					$isExist = $this->$model->checkNonTagItemExist($existData);

					if($isExist['status'] == TRUE)
	        		{
						$nt_data = array(
							'id_nontag_item'=>$isExist['id_nontag_item'],
							'gross_wt'		=> $val['purretgwt'],
							'net_wt'		=> $val['purretnwt'],  
							'no_of_piece'   => $val['purretpcs'],
							'updated_by'	=> $this->session->userdata('uid'),
							'updated_on'	=> date('Y-m-d H:i:s'),
						);
						$this->$model->updateNTData($nt_data,'-');
						
						$non_tag_data=array(
							'product'	    => $val['pro_id'],
							'design'	    => $val['des_id'],
							'id_sub_design'	=> $val['id_sub_design'],
							'no_of_piece'   => $val['purretpcs'],
							'gross_wt'		=> $val['purretgwt'],
							'net_wt'		=> $val['purretnwt'],
							'from_branch'	=> $ho['id_branch'],
							'to_branch'	    => NULL,
							'ref_no'	    => $insId,
							'status'	    => 8,//Purchase Return
							'date'          => $bill_date,
							'created_on'    => date("Y-m-d H:i:s"),
							'created_by'    =>  $this->session->userdata('uid')
						);
						$this->$model->insertData($non_tag_data,'ret_nontag_item_log');
					}
				}
								
				if(!empty($val['other_metal_details']))
				{
					$othermetal_details = json_decode($val['other_metal_details'],true);
					foreach($othermetal_details as $om)
					{
						$om_data = array(
							'pur_ret_return_id'         => $insert_item_id,
							'ret_other_itm_metal_id'    => $om['id_metal'],
							'ret_other_itm_pur_id'      => $om['id_purity'],
							'ret_other_itm_grs_weight'  => $om['gwt'],
							//'ret_other_itm_cal_type'    => $om['calc_type'],
							'ret_other_itm_pcs'         => $om['pcs'],
							'ret_other_itm_rate'        => $om['rate_per_gram'],
							'ret_other_itm_amount'      => $om['amount'],
							
						);
						$returnomInsert = $this->$model->insertData($om_data, 'ret_purchase_return_other_metal');
						//echo $this->db->last_query();exit;
					}
				}

				if(!empty($val['stone_details']))
				{
					$stone_details = json_decode($val['stone_details'],true);
					foreach($stone_details as $om)
					{
						$stone_data = array(
							'pur_ret_return_id'         => $insert_item_id,
							'ret_stone_id'              => $om['stone_id'],
							'ret_stone_pcs'             => $om['stone_pcs'],
							'ret_stone_wt'              => $om['stone_wt'],
							'ret_stone_uom'             => $om['stone_uom_id'],
							'ret_stone_calc_based_on'   => $om['stone_cal_type'],
							'ret_stone_rate'            => $om['stone_rate'],
							'ret_stone_amount'          => $om['stone_price'],
							
						);
						$returnstoneInsert = $this->$model->insertData($stone_data, 'ret_purchase_return_stone_items');
						//echo $this->db->last_query();exit;
					}
				}
				
				if(!empty($val['other_charges_details']))
				{
					$othercharge_details = json_decode($val['other_charges_details'],true);

					foreach($othercharge_details as $ch)
					{

						$charge_data = array(
							'pur_ret_itm_id'        => $insert_item_id,
							'pur_ret_charge_id'     => $ch['charge_id'],
							'pur_ret_charge_value'  => $ch['charge_value'],
							'tax_percentage'        => $ch['tax_percentage'],
							'item_total_tax'        => $ch['item_total_tax'],
							'cgst_cost'             => $ch['cgst_cost'],
							'sgst_cost'             => $ch['sgst_cost'],
							'igst_cost'             => $ch['igst_cost'],
							'total_charge_value'    => $ch['total_charge_value'],
						);
						$returnchargegsInsert = $this->$model->insertData($charge_data, 'ret_purchase_return_other_charges');

					}

				}
				
			}

		}

	

		if($this->db->trans_status()===TRUE)
		{
			$this->db->trans_commit();
			$responseData=array('success'=>true,'message'=>'Return data updated successfully.','return_id'=>$insId);
			
		}	
		else
		{
			echo $this->db->last_query();exit;
			$this->db->trans_rollback();
			$responseData=array('success' => false,'message'=>'Unable to proceed the requested operation.');
		}
		echo json_encode($responseData);
	}
	
    function purchasereturn($type="")
	{   
		$model=	self::RET_PUR_ORDER_MODEL;
		$set_model = "admin_settings_model";
		switch($type)
		{
			case 'list':
					$data['main_content'] = self::VIEW_FOLDER.'purchasereturn/list';
        			$this->load->view('layout/template', $data);
			break;
			case 'add':
			        $data['comp_details']   = $this->$set_model->get_company();
					$data['main_content'] = self::VIEW_FOLDER.'purchasereturn/form';
        			$this->load->view('layout/template', $data);
			break;			
			
			case 'cancel_ret_entry':
			    $ho              =  $this->$model->get_headOffice();
        		$dCData          =  $this->admin_settings_model->getBranchDayClosingData($ho['id_branch']);
        		$bill_date       =  ($dCData['entry_date'] == date("Y-m-d") ? date("Y-m-d H:i:s") : $dCData['entry_date']);
        		
			    $pur_return_id = $_POST['pur_return_id'];
			    $cancel_reason = $_POST['cancel_reason'];
			    $updData=array(
                    'bill_status'       => 2,
                    'cancel_reason'     => $_POST['cancel_reason'],
                    'cancelled_date'    => $bill_date,
                    'cancelled_by'      => $this->session->userdata('uid')
                    );
                    $this->db->trans_begin();
                    $ret_status = $this->$model->updateData($updData,'pur_return_id',$pur_return_id,'ret_purchase_return');
                    if($ret_status)
                    {
                        $non_tag_details = $this->$model->get_return_non_tag_details($pur_return_id);
                        $ret_tag_details = $this->$model->get_return_tag_details($pur_return_id);
                        if(sizeof($non_tag_details) > 0)
                        {
                            foreach($non_tag_details as $non_tag)
                            {
                                $existData=array('id_product'=>$non_tag['id_product'],'design'=>$non_tag['id_design'],'id_sub_design'=>$non_tag['id_sub_design'],'id_branch'=>$ho['id_branch']);
                                	
                                	$isExist = $this->$model->checkNonTagItemExist($existData);
    				
                    				if($isExist['status'] == TRUE)
                			        {
                			            $nt_data = array(
                                        'id_nontag_item'=>$isExist['id_nontag_item'],
                                        'gross_wt'		=> $non_tag['pur_ret_gwt'],
                                        'net_wt'		=> $non_tag['pur_ret_gwt'],  
                                        'no_of_piece'   => $non_tag['pur_ret_pcs'],
                                        'updated_by'	=> $this->session->userdata('uid'),
                                        'updated_on'	=> date('Y-m-d H:i:s'),
                                        );
                                        $this->$model->updateNTData($nt_data,'+');
                            													
                                        $non_tag_data=array(
                                        'product'	    => $non_tag['id_product'],
                                        'design'	    => $non_tag['id_design'],
                                        'id_sub_design'	=> $non_tag['id_sub_design'],
                                        'no_of_piece'   => $non_tag['pur_ret_pcs'],
                                        'gross_wt'		=> $non_tag['pur_ret_gwt'],
                                        'net_wt'		=> $non_tag['pur_ret_gwt'],
                                        'to_branch'	    => $ho['id_branch'],
                                        'from_branch'	=> NULL,
                                        'ref_no'	    => $insId,
                                        'status'	    => 9,//Purchase Return Cancel
                                        'date'          => $bill_date,
                                        'created_on'    => date("Y-m-d H:i:s"),
                                        'created_by'    =>  $this->session->userdata('uid')
                                        );
                                        $this->$model->insertData($non_tag_data,'ret_nontag_item_log');
                			        }
                            }
                        }
                        if(sizeof($ret_tag_details) > 0)
                        {
                            foreach($ret_tag_details as $val)
                            {
                                $tagStatus=$this->$model->updateData(array('tag_status'=>0,'updated_by'=>$this->session->userdata('uid'),'updated_time'=>date("Y-m-d H:i:s")),'tag_id',$val['tag_id'],'ret_taging');
                                $tag_log=array(
                                'tag_id'	  =>$val['tag_id'],
                                'date'		  =>$bill_date,
                                'status'	  =>0,
                                'from_branch' =>NULL,
                                'to_branch'	  =>$ho['id_branch'],
                                'created_on'  =>date("Y-m-d H:i:s"),
                                'created_by'  =>$this->session->userdata('uid'),
                                );
                                $this->$model->insertData($tag_log,'ret_taging_status_log');
                            }
                        }
                    }
                    if($this->db->trans_status()===TRUE)
                    {
                        $this->db->trans_commit();
                        $this->session->set_flashdata('chit_alert',array('message'=>'Purchase Return Entry Cancelled Successfully','class'=>'success','title'=>'Purchase Return')); 
                        $return_data=array('status'=>TRUE,'message'=>'Order Instructions Added Successfully..');
                    }
                    else
                    { 
                        echo $this->db->last_query();exit;
                        $this->db->trans_rollback();						 	
                        $this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Purchase Return')); 
                        $return_data=array('status'=>FALSE,'message'=>'Unable to proceed the requested process');
                    }
                echo json_encode($return_data);
			break;
			
			case 'ajax': 
				$list=$this->$model->getReturnedRequestList(); 
			  	$access = $this->admin_settings_model->get_access('admin_ret_purchase/halmarking_issue_receipt/list');
		        $data = array(
		        					'list'  => $list,
									'access'=> $access
		        				);  
				echo json_encode($data);
			break;
		}
	}
	public function getreturn_po_list(){
		$model=	self::RET_PUR_ORDER_MODEL;
		$data = $this->$model->getRejectedPos($_POST);	  
		echo json_encode($data);
	}
	public function get_qc_faild_items_by_poid(){
		$model =	self::RET_PUR_ORDER_MODEL;
		$data  = $this->$model->getRejectedItemsByPoId($_POST['porefid'],$_POST['purchase_type']);	  
		echo json_encode($data);
	}
	public function get_qc_faild_items_by_supid(){
		$model =	self::RET_PUR_ORDER_MODEL;
		$data  = $this->$model->getRejectedItemsBySupId($_POST['supid']);	  
		echo json_encode($data);
	}
	
	public function updateporeturnitems(){
	    
		$model =	self::RET_PUR_ORDER_MODEL;
		$item_details = $_POST['returnitems'];
		$item_category_details = $_POST['returncatitems'];
		$nontagreturnitemlist = $_POST['nontagreturnitemlist'];
		$returntaggeditemlist = $_POST['returntaggeditemlist'];
		$purchasereturnitemlist = $_POST['purchasereturnitemlist'];
		
		$pur_ret_ref_no = $this->$model->pur_ret_refno();
		$ho              =  $this->$model->get_headOffice();
		$dCData          =  $this->admin_settings_model->getBranchDayClosingData($ho['id_branch']);
		$bill_date       =  ($dCData['entry_date'] == date("Y-m-d") ? date("Y-m-d H:i:s") : $dCData['entry_date']);
		$purchasereturn = array(
		                    "pur_ret_supplier_id"                   => $_POST['id_karigar'],
		                    "bill_date"                             => $bill_date,
		                    "pur_ret_ref_no"                        => $pur_ret_ref_no,
							"pur_ret_reason"                        => $_POST['returnreason'], 
							"pur_ret_remark"                        => $_POST['narration'], 
							"stock_type"                            => $_POST['stock_type'], 
							"return_total_cost"                     => ($_POST['returnamount']!='' ? $_POST['returnamount']:0), 
							"pur_ret_round_off"                     => ($_POST['returnroundoff']!='' ? ($_POST['round_off_type']==1 ? '+'.$_POST['returnroundoff'] :'-'.$_POST['returnroundoff']):0),
							"pur_ret_discount"                      => ($_POST['return_discount']!='' ? $_POST['return_discount']:0),
							"pur_ret_other_charges"                 => ($_POST['other_charges']!='' ? $_POST['other_charges']:0),
							"pur_ret_tds_percent"                   => ($_POST['tds_percent']!='' ? $_POST['tds_percent']:0),
							"pur_ret_tds_value"                     => ($_POST['tds_tax_value']!='' ? $_POST['tds_tax_value']:0),
							"pur_ret_other_charges_tds_percent"     => ($_POST['charges_tds_percent']!='' ? $_POST['charges_tds_percent']:0),
							"pur_ret_other_charges_tds_value"       => ($_POST['other_charges_tds_tax_value']!='' ? $_POST['other_charges_tds_tax_value']:0),
							"pur_ret_tcs_percent"                   => ($_POST['tcs_percent']!='' ? $_POST['tcs_percent']:0),
							"pur_ret_tcs_value"                     => ($_POST['tcs_tax_value']!='' ? $_POST['tcs_tax_value']:0), 
							"pur_ret_created_by"                    => $this->session->userdata('uid') 
						);
		$this->db->trans_begin();
        $insId = $this->$model->insertData($purchasereturn,'ret_purchase_return');
	
		foreach($item_category_details as $key => $val)
		{
    			$purchasereturn_items = array(
    							"pur_ret_id"         => $insId, 
    							"pur_ret_cat_id"     => $val['cat_id'], 
    							"pur_ret_cat_pcs"    => $val['cat_pcs'],
    							"pur_ret_cat_gwt"    => $val['cat_gwt'],
    							"pur_ret_cat_leswt"    => $val['cat_lwt'],
    							"pur_ret_cat_netwt"  => $val['cat_nwt'],
    							"pur_ret_rate"       => $val['pur_ret_rate'],
    							"purreturncaltype"   => $val['purreturncaltype'],
    							"pur_ret_item_cost"  => $val['ret_item_cost'],
    							"pur_ret_tax_rate"   => $val['ret_tax_rate'],
    							"pur_ret_tax_value"  => $val['ret_tax_value'],
    							"pur_ret_cgst"       => $val['ret_cgst_value'],
    							"pur_ret_sgst"       => $val['ret_sgst_value'],
    							"pur_ret_igst"       => $val['ret_igst_value'],
    						);
    		    $insert_item_id = $this->$model->insertData($purchasereturn_items,'ret_purchase_return_cat_items');
    		    if($insert_item_id)
    		    {
    		        
        		    if(!empty($val['other_metal_details'])){
                    	$othermetal_details = json_decode($val['other_metal_details'],true);
                    	foreach($othermetal_details as $om)
                    	{
                    		$om_data = array(
                    			'pur_ret_return_id'         => $insert_item_id,
                    			'ret_other_itm_metal_id'    => $om['id_metal'],
                    			'ret_other_itm_pur_id'      => $om['id_purity'],
                    			'ret_other_itm_grs_weight'  => $om['gwt'],
                    			//'ret_other_itm_cal_type'    => $om['calc_type'],
                    			'ret_other_itm_pcs'         => $om['pcs'],
                    			'ret_other_itm_rate'        => $om['rate_per_gram'],
                    			'ret_other_itm_amount'      => $om['amount'],
                    			
                    		);
                    		$returnomInsert = $this->$model->insertData($om_data, 'ret_purchase_return_other_metal');
                    		//echo $this->db->last_query();exit;
                    	}
                    }
                    if(!empty($val['stone_details'])){
                    	$stone_details = json_decode($val['stone_details'],true);
                    	foreach($stone_details as $om)
                    	{
                    		$stone_data = array(
                    			'pur_ret_return_id'         => $insert_item_id,
                    			'ret_stone_id'              => $om['stone_id'],
                    			'ret_stone_pcs'             => $om['stone_pcs'],
                    			'ret_stone_wt'              => $om['stone_wt'],
                    			'ret_stone_uom'             => $om['stone_uom_id'],
                    			'ret_stone_calc_based_on'   => $om['stone_cal_type'],
                    			'ret_stone_rate'            => $om['stone_rate'],
                    			'ret_stone_amount'          => $om['stone_price'],
                    			
                    		);
                    		$returnstoneInsert = $this->$model->insertData($stone_data, 'ret_purchase_return_stone_items');
                    		//echo $this->db->last_query();exit;
                    	}
                    }
		        }
    	}
    	
    	foreach($returntaggeditemlist as $key => $val)
	    {
	        $purchasereturn_tag_items = array(
				"pur_ret_id"            => $insId, 
				"tag_id"                => $val['tag_id'],
				"return_item_type"      => 2,
				"pur_ret_pcs"           => $val['piece'],
				"pur_ret_gwt"           => $val['gross_wt'],
				"pur_ret_nwt"           => $val['net_wt'],
			);
			$this->$model->insertData($purchasereturn_tag_items,'ret_purchase_return_items');
			if($val['tag_id']!='')
		    {
		            $tagDetails = $this->$model->get_tag_details($val['tag_id']);
		            $dCData = $this->admin_settings_model->getBranchDayClosingData($tagDetails['current_branch']);
		            $bill_date = ($dCData['entry_date'] == date("Y-m-d") ? date("Y-m-d H:i:s") : $dCData['entry_date']);
                    $tagStatus=$this->$model->updateData(array('tag_status'=>9,'updated_by'=>$this->session->userdata('uid'),'updated_time'=>date("Y-m-d H:i:s")),'tag_id',$val['tag_id'],'ret_taging');
                    if($tagStatus)
                    {
                        //Update Tag Log status
                            $tag_log=array(
                            'tag_id'	            =>$val['tag_id'],
                            'date'		            =>$bill_date,
                            'status'	            =>14,
                            'from_branch'           =>$tagDetails['current_branch'],
                            'to_branch'	            =>NULL,
                            'issuspensestock'	    =>$_POST['stock_type'],
                            'created_on'            =>date("Y-m-d H:i:s"),
                            'created_by'            =>$this->session->userdata('uid'),
                            );
                            $this->$model->insertData($tag_log,'ret_taging_status_log');
                    }
		    }
	    }
	    foreach($nontagreturnitemlist as $key => $val)
	    {
	        $purchasereturn_nontag_items = array(
				"pur_ret_id"            => $insId, 
				"return_item_type"      => 3,
				'id_product'            => $val['id_product'],
				'id_design'             => $val['id_design'],
				'id_sub_design'         => $val['id_sub_design'],
				"pur_ret_pcs"           => $val['piece'],
				"pur_ret_gwt"           => $val['gross_wt'],
				"pur_ret_lwt"           => 0,
				"pur_ret_nwt"           => $val['gross_wt'],
			);
			$this->$model->insertData($purchasereturn_nontag_items,'ret_purchase_return_items');
			
            $existData=array('id_product'=>$val['id_product'],'design'=>$val['id_design'],'id_sub_design'=>$val['id_sub_design'],'id_branch'=>$ho['id_branch']);
                                    						        
			$isExist = $this->$model->checkNonTagItemExist($existData);
			
			if($isExist['status'] == TRUE)
	        {
	            $nt_data = array(
                'id_nontag_item'=>$isExist['id_nontag_item'],
                'gross_wt'		=> $val['gross_wt'],
                'net_wt'		=> $val['gross_wt'],  
                'no_of_piece'   => $val['piece'],
                'updated_by'	=> $this->session->userdata('uid'),
                'updated_on'	=> date('Y-m-d H:i:s'),
                );
                $this->$model->updateNTData($nt_data,'-');
    													
                $non_tag_data=array(
                'product'	    => $val['id_product'],
                'design'	    => $val['id_design'],
                'id_sub_design'	=> $val['id_sub_design'],
                'no_of_piece'   => $val['piece'],
                'gross_wt'		=> $val['gross_wt'],
                'net_wt'		=> $val['gross_wt'],
                'from_branch'	=> $ho['id_branch'],
                'to_branch'	    => NULL,
                'ref_no'	    => $insId,
                'status'	    => 8,//Purchase Return
                'date'          => $bill_date,
                'created_on'    => date("Y-m-d H:i:s"),
                'created_by'    =>  $this->session->userdata('uid')
                );
                $this->$model->insertData($non_tag_data,'ret_nontag_item_log');
	        }
	    }
	    foreach($purchasereturnitemlist as $val)
	    {
	        $purchasereturn_po_items = array(
				"pur_ret_id"            => $insId, 
				"pur_ret_po_item_id"    => $val['po_item_id'],
				"return_item_type"      => 1,
				"pur_ret_pcs"           => $val['piece'],
				"pur_ret_gwt"           => $val['gross_wt'],
				"pur_ret_pur_wt"        => $val['gross_wt'],
			);
			$this->$model->insertData($purchasereturn_po_items,'ret_purchase_return_items');
	    }
		
		if(!empty($_POST['other_charges_details'])){
        	$charge_details = json_decode($_POST['other_charges_details'],true);
        	foreach($charge_details as $charges)
        	{
        	    
        	    $cgst_cost = 0;
        	    $sgst_cost = 0;
        	    $igst_cost = 0;
        	    $total_tax = number_format(($charges['charge_tax_value']),2,'.','');
        	    if($addData['cmp_country']==$addData['supplier_country'])
        	    {
        	        if($addData['cmp_state']==$addData['supplier_state'])
        	        {
        	            $cgst_cost = ($total_tax/2);
        	            $sgst_cost = ($total_tax/2);
        	        }
        	        else
        	        {
        	            $igst_cost = $total_tax;
        	        }
        	    }
        	    else
        	    {
        	        $cgst_cost = ($total_tax/2);
        	        $sgst_cost = ($total_tax/2);
        	    }
                    	    
        		$charge_data = array(
        			'pur_ret_id'                => $insId,
        			'pur_ret_charge_id'         => $charges['charge_id'],
        			'pur_ret_charge_value'      => $charges['charge_value'],
        			'pur_ret_charge_tax'        => $charges['charge_tax'],
        			'pur_ret_charge_tax_value'  => $charges['charge_tax_value'],
        			'cgst_cost'                 => $cgst_cost,
        			'sgst_cost'                 => $sgst_cost,
        			'igst_cost'                 => $igst_cost,
        		);
        		$returnchargegsInsert = $this->$model->insertData($charge_data, 'ret_purchase_return_other_charges');
        		//echo $this->db->last_query();exit;
        	}
        }
        
        
		
		if($this->db->trans_status()===TRUE)
		{
			$this->db->trans_commit();
			$responseData=array('success'=>true,'message'=>'Return data updated successfully.','return_id'=>$insId);
			
		}	
		else
		{
			//echo $this->db->last_query();exit;
			$this->db->trans_rollback();
			$responseData=array('success' => false,'message'=>'Unable to proceed the requested operation.');
		}
		echo json_encode($responseData);
	
	}
	
	public function return_receipt_acknowladgement($id, $Print_Type = "")
    {
        $model =    self::RET_PUR_ORDER_MODEL;
        $set_model                      = "admin_settings_model";
        $data['comp_details']           = $this->$set_model->get_company(1);
        $data['issue']                  = $this->$model->getReturnReceipt($id);
        $data['receipt_details']        = $this->$model->getReturnReceiptDetails($id);
        $data['gst_details']            = $this->$model->getReturnReceiptGSTDetails($id);
        $data['item_details']      = $this->$model->get_purchaseReturn_item_details($id);
        $data['type'] = $Print_Type; // 1-summary , 2-Detailed
        //echo "<pre>"; print_r($data);exit;

        $this->load->helper(array('dompdf', 'file'));
        $dompdf = new DOMPDF();
        /*if ($data['type'] == 1) {
            // $html = $this->load->view('ret_purchase/purchasereturn/vendor_ack', $data, true);
             $html = $this->load->view(self::VIEW_FOLDER . 'purchasereturn/vendor_ack', $data, true);

        } else {
            $html = $this->load->view('ret_purchase/purchasereturn/vendor_ack_detailed', $data, true);
        }*/
        
        $html = $this->load->view(self::VIEW_FOLDER . 'purchasereturn/vendor_ack', $data, true);
        
        echo $html;

        // $dompdf->load_html($html);
        // $dompdf->set_paper('A4', "portriat");
        // $dompdf->render();
        // $dompdf->stream("VendorAck.pdf", array('Attachment' => 0));
    }
    //Purchase return
    
    
    //Order cancel and close
    function update_order_close()
	{
	    $model=	self::RET_PUR_ORDER_MODEL;
	    $req_data   =$_POST['req_data'];
        foreach($req_data as $items)
	    {
	       $this->db->trans_begin();
	       $this->$model->updateData(array('order_status'=>7,'closed_date'=>date("Y-m-d H:i:s"),'closed_by'=>$this->session->userdata('uid')),'id_customerorder',$items['id_customerorder'],'customerorder');
	    }
    	    if($this->db->trans_status()===TRUE)
            {
                $log_data = array(
                        	'id_log'        => $this->session->userdata('id_log'),
                        	'event_date'	=> date("Y-m-d H:i:s"),
                        	'module'      	=> 'Order Closed',
                        	'operation'   	=> 'Update',
                        	'record'        =>  NULL,  
                        	'remark'       	=> 'Order Closed.'
                        );
                $this->log_model->log_detail('insert','',$log_data);
                
                $this->db->trans_commit();
                $responseData=array('status'=>true,'message'=>'Order Closed successfully.');
            }	
            else
            {
                $this->db->trans_rollback();
                $responseData=array('status'=>false,'message'=>'Unable to proceed the requested operation.');
            }
            echo json_encode($responseData);
	}
	
	
	function update_order_cancel()
	{
	    $model=	self::RET_PUR_ORDER_MODEL;
	    $req_data   =$_POST['req_data'];
        foreach($req_data as $items)
	    {
	       $this->db->trans_begin();
	       $this->$model->updateData(array('order_status'=>6,'cancelled_date'=>date("Y-m-d H:i:s"),'cancelled_by'=>$this->session->userdata('uid')),'id_customerorder',$items['id_customerorder'],'customerorder');
	    }
    	    if($this->db->trans_status()===TRUE)
            {
                $log_data = array(
                        	'id_log'        => $this->session->userdata('id_log'),
                        	'event_date'	=> date("Y-m-d H:i:s"),
                        	'module'      	=> 'Order Closed',
                        	'operation'   	=> 'Update',
                        	'record'        =>  NULL,  
                        	'remark'       	=> 'Order Cancelled.'
                        );
                $this->log_model->log_detail('insert','',$log_data);
                
                $this->db->trans_commit();
                $responseData=array('status'=>true,'message'=>'Order Cancelled successfully.');
            }	
            else
            {
                $this->db->trans_rollback();
                $responseData=array('status'=>false,'message'=>'Unable to proceed the requested operation.');
            }
            echo json_encode($responseData);
	}
	
    //Order cancel and close
    
    
    //Order Delivery
    function get_karigar_pending_orders()
    {
        $model=	self::RET_PUR_ORDER_MODEL;
        $data= $this->$model->get_karigar_pending_orders($_POST);
        echo json_encode($data);
    }
    
    function get_karigar_pending_order_details()
    {
        $model=	self::RET_PUR_ORDER_MODEL;
        $data= $this->$model->get_purchase_order_pending_details($_POST);
        echo json_encode($data);
    }
    
    function update_order_delivery()
	{
	    $model=	self::RET_PUR_ORDER_MODEL;
	    $req_data   =$_POST['req_data'];
        foreach($req_data as $items)
	    {
	       $this->db->trans_begin();
	       if($items['order_pcs']>($items['delivered_pcs']+$items['tot_delivered_pcs']))
	       {
                $orderData=array(
                    'delivered_qty'     =>$items['delivered_pcs'],
                    'delivered_wt'      =>($items['delivered_wt']!='' ? $items['delivered_wt']:0),
                    'id_orderdetails'   =>$items['id_orderdetails'],
                    'is_partial_delivery'=>1
                    );
                $status=$this->$model->update_partial_order_delivery($orderData,'+');
                //print_r($this->db->last_query());exit;
                if($status)
                {
                    $insData=array(
                                  'id_joborder' =>$items['id_joborder'],
                                  'delivered_qty'=>$items['delivered_pcs'],
                                  'delivered_date'=>date("Y-m-d H:i:s")
                                  );
                    $insOrder = $this->$model->insertData($insData,'joborder_partial_delivery');
                    
                }
	       }
	       else if($items['order_pcs']<=$items['delivered_pcs']+$items['tot_delivered_pcs'])
	       {
	           $updData=array(
	                          'delivered_date'=>date("Y-m-d H:i:s"),
	                          'orderstatus'   =>5,
	                          'delivered_by'  =>$this->session->userdata('uid'),
	                         );
	          
	           $this->$model->updateData($updData,'id_orderdetails',$items['id_orderdetails'],'customerorderdetails');
	           
	            $orderData=array(
                    'delivered_qty'     =>$items['delivered_pcs'],
                    'delivered_wt'      =>$items['delivered_wt'],
                    'id_orderdetails'   =>$items['id_orderdetails'],
                    );
                $status=$this->$model->update_order_delivery($orderData,'+');
                
	       }
	    }
    	    if($this->db->trans_status()===TRUE)
            {
                
                $log_data = array(
                        	'id_log'        => $this->session->userdata('id_log'),
                        	'event_date'	=> date("Y-m-d H:i:s"),
                        	'module'      	=> 'Order Delivery',
                        	'operation'   	=> 'Update',
                        	'record'        =>  NULL,  
                        	'remark'       	=> 'Order Delivered.'
                        );
                $this->log_model->log_detail('insert','',$log_data);
                
                $this->db->trans_commit();
                $responseData=array('status'=>true,'message'=>'Order Delivered successfully.');
            }	
            else
            {
                echo $this->db->last_query();exit;
                $this->db->trans_rollback();
                $responseData=array('status'=>false,'message'=>'Unable to proceed the requested operation.');
            }
            echo json_encode($responseData);
	}
	
    //Order Delivery
    
    //Metal Issue
    
    public function karigarmetalissue_acknowladgement($id)
	{
		$model=	self::RET_PUR_ORDER_MODEL;
		$set_model = "admin_settings_model";
		$data['comp_details'] = $this->$set_model->get_company(1);
		$data['issue']        = $this->$model->getMetalIssue($id);
		$data['issue_details']        = $this->$model->getMetalIssueDetails($id);
		$data['type']=$type;
		//echo "<pre>"; print_r($data);exit;
		$this->load->helper(array('dompdf', 'file'));
	        $dompdf = new DOMPDF();
			$html = $this->load->view('ret_purchase/metalissue/vendor_ack', $data,true);
			$dompdf->load_html($html);
			$dompdf->set_paper('A4', "portriat" );
			$dompdf->render();
			$dompdf->stream("VendorAck.pdf",array('Attachment'=>0));
	}
	
  function karigarmetalissue($type="")
	{   
		$model=	self::RET_PUR_ORDER_MODEL;
		switch($type)
		{
			case 'list':
					$data['main_content'] = self::VIEW_FOLDER.'metalissue/list';
        			$this->load->view('layout/template', $data);
			break;
			case 'add':
					$data['main_content'] = self::VIEW_FOLDER.'metalissue/form';
        			$this->load->view('layout/template', $data);
			break;
			
			case 'available_stock_details':
				    $data=$this->$model->get_available_stock_details($_POST);
				    echo json_encode($data);
			break;
			
			case'save':
			        $responseData = array();
			        //echo "<pre>";print_r($_POST);exit;
			        $addData         =  $_POST['issue'];
			        $ref_no          =  $this->$model->get_metal_issue_ref_no();
			        $ho              =  $this->$model->get_headOffice();
                    $dCData          =  $this->admin_settings_model->getBranchDayClosingData($ho['id_branch']);
				    $bill_date       =  ($dCData['entry_date'] == date("Y-m-d") ? date("Y-m-d H:i:s") : $dCData['entry_date']);
				
				
			        $insData=array(
			                      'met_issue_karid'     =>$addData['id_karigar'],
			                      'id_branch'           =>$ho['id_branch'],
			                      'issue_aganist'       =>$addData['issue_aganist'],
			                      'id_order'            =>(!empty($addData['id_order']) ? $addData['id_order']:NULL),
			                      'met_issue_ref_id'    =>$ref_no,
			                      'met_issue_date'      =>$bill_date,
			                      'met_issue_created_on'=>date("Y-m-d H:i:s"),
			                      'met_issue_created_by'=>$this->session->userdata('uid'),
			                      );
			        $this->db->trans_begin();
			        $insId = $this->$model->insertData($insData,'ret_karigar_metal_issue');
			        if($insId)
			        {
			            $metal_details=$_POST['metal_details'];
			            foreach($metal_details['metal'] as $key => $val)
			            {
			                //$purity=$this->$model->get_metal_issue_purity($metal_details['purity'][$key]);
			                $metal_data=array(
			                                 'issue_met_parent_id'      =>$insId,
			                                 'issue_metal'              =>$metal_details['metal'][$key],
			                                 'issue_cat_id'             =>$metal_details['category'][$key],
			                                 'issue_pur_id'             =>$metal_details['purity'][$key],
			                                 'issu_met_pro_id'          =>$metal_details['id_product'][$key],
			                                 'issu_met_id_design'       =>$metal_details['id_design'][$key],
			                                 'issu_met_id_sub_design'   =>$metal_details['id_sub_design'][$key],
			                                 //'issue_pcs'                =>$metal_details['pcs'][$key],
			                                 'issue_metal_wt'           =>$metal_details['weight'][$key],
			                                 'issue_metal_pur_wt'       =>$metal_details['pur_weight'][$key],
			                                 );
			               $status=$this->$model->insertData($metal_data,'ret_karigar_metal_issue_details');
			               if($status)
			               {
			                   
			                   
			                        $existData=array('id_product'=>$metal_details['id_product'][$key],'design'=>$metal_details['id_design'][$key],'id_sub_design'=>$metal_details['id_sub_design'][$key],'id_branch'=>$ho['id_branch']);
                                    						        
    						        $isExist = $this->$model->checkNonTagItemExist($existData);
    						        
    						        if($isExist['status'] == TRUE)
    						        {
    						            $nt_data = array(
                                        'id_nontag_item'=>$isExist['id_nontag_item'],
                                        'no_of_piece'	=> $metal_details['pcs'][$key],
                                        'gross_wt'		=> $metal_details['weight'][$key],
                                        'net_wt'		=> $metal_details['weight'][$key],  
                                        'updated_by'	=> $this->session->userdata('uid'),
                                        'updated_on'	=> date('Y-m-d H:i:s'),
                                        );
                                        $this->$model->updateNTData($nt_data,'-');
                            													
                                        $non_tag_data=array(
                                        'product'	    => $metal_details['id_product'][$key],
                                        'design'	    => $metal_details['id_design'][$key],
                                        'id_sub_design'	=> $metal_details['id_sub_design'][$key],
                                        'no_of_piece'	=> $metal_details['pcs'][$key],
                                        'gross_wt'		=> $metal_details['weight'][$key],
                                        'net_wt'		=> $metal_details['weight'][$key],
                                        'from_branch'	=> $ho['id_branch'],
                                        'to_branch'	    => NULL,
                                        'ref_no'	    => $insId,
                                        'status'	    => 7,
                                        'date'          => $bill_date,
                                        'created_on'    => date("Y-m-d H:i:s"),
                                        'created_by'    =>  $this->session->userdata('uid')
                                        );
                                        $this->$model->insertData($non_tag_data,'ret_nontag_item_log');
    						        }
                                    						        
                                    						        
			                   //UPDATE INTO PURCHASE ITEM STOCK SUMMARY
    			        	       /* $itemExistData=array('id_product'=>$metal_details['id_product'][$key],'id_branch'=>$ho['id_branch'],'purity'=>$metal_details['purity'][$key]); 
    			        	        $is_po_item_exist = $this->$model->checkPurchaseItemStockExist($itemExistData); //CHECK ITEM EXISTS IN TABLE 
    			        	       // print_r($this->db->last_query());exit;
    			        	        $pur_item_stock_summary = array(
  										        'id_branch'	        => $ho['id_branch'],
  										        'id_product'	    => $metal_details['id_product'][$key],
  										        'pieces'		    => 0,  
  										        'gross_wt'		    => $metal_details['weight'][$key],  
  										        'purity'		    => $metal_details['purity'][$key],  
  										        'less_wt'		    => 0,  
												'net_wt'		    => $metal_details['weight'][$key],  
												);
									if($is_po_item_exist['status']) //IF ITEM EXISTS ALREADY IN TABLE
									{
									    	 $pur_item_stock_summary['updated_by']=$this->session->userdata('uid');
									    	 $pur_item_stock_summary['updated_on']=date('Y-m-d H:i:s');
									    	 
									    	 $pur_stock_Status=$this->$model->updatePurItemData($is_po_item_exist['id_stock_summary'],$pur_item_stock_summary,'-');
    										 $id_stock_summary=$is_po_item_exist['id_stock_summary'];
    											 
									}
            					    //UPDATE INTO PURCHASE ITEM STOCK SUMMARY
            					    
            					    $logData=array(
                                    'id_product'       =>$metal_details['id_product'][$key],
                                    'gross_wt'         =>$metal_details['weight'][$key],
                                    'net_wt'           =>$metal_details['weight'][$key],
                                    'date'			   =>$bill_date,
                                    'status'		   =>3,	//Outward
                                    'from_branch'	   =>$ho['id_branch'],
                                    'to_branch'	       =>NULL,
                                    'item_type'	       =>6, // Bullion or Stone Purchase
                                    "created_by"	   => $this->session->userdata('uid'),
                                    "created_on"	   => date('Y-m-d H:i:s'),
                                    );
                                    $this->$model->insertData($logData,'ret_purchase_items_log');
                                    
                                    
                                    $stock_log_data=array(
                                    'id_stock_summary'=>$id_stock_summary,
                                    'date_add'        =>date('Y-m-d H:i:s'),
                                    'ref_no'          =>$ref_no,
                                    'gross_wt'        =>$metal_details['weight'][$key],
                                    'net_wt'          =>$metal_details['weight'][$key],
                                    'transcation_type'=>1,
                                    'debit_type'      =>2,
                                    'remarks'         =>'FROM KARIGAR METAL ISSUE'
                                    );
                                    $this->$model->insertData($stock_log_data,'ret_purchase_item_stock_summary_log');*/
                                    
			               }
			            }
			            
			        }
			        
			            if($this->db->trans_status()===TRUE)
                        {
                            $log_data = array(
                                    	'id_log'        => $this->session->userdata('id_log'),
                                    	'event_date'	=> date("Y-m-d H:i:s"),
                                    	'module'      	=> 'Karigar Metal Issue',
                                    	'operation'   	=> 'Add',
                                    	'record'        =>  NULL,  
                                    	'remark'       	=> 'Karigar Metal Issue.'
                                    );
                            $this->log_model->log_detail('insert','',$log_data);
                            
                            $this->db->trans_commit();
                            $responseData=array('status'=>true,'message'=>'Metal Issued successfully.','insId'=>$insId);
                        }	
                        else
                        {
                            //echo $this->db->last_query();exit;
                            $this->db->trans_rollback();
                            $responseData=array('status'=>false,'message'=>'Unable to proceed the requested operation.');
                        }
                        echo json_encode($responseData);
                        
			break;
			
			case 'metalissue_cancel':
				$this->db->trans_begin();
				$ho   =  $this->$model->get_headOffice();
				$metal_issue_id = $_POST['metal_issue_id'];
				$this->$model->updateData(array('bill_status' => 2),'met_issue_id',$metal_issue_id,'ret_karigar_metal_issue');
				$issuedet = $this->$model->get_KarigarMetal_issue($metal_issue_id);
				foreach($issuedet as $val)
				{
					$existData=array('id_product'=>$val['pro_id'],'id_branch'=>$ho['id_branch']);
					$isExist = $this->$model->checkNonTagItemExist($existData);
					if($isExist['status'] == TRUE)
					{
						$nt_data = array(
							'id_nontag_item'=> $isExist['id_nontag_item'],
							'gross_wt'		=> $val['weight'],
							'net_wt'		=> $val['weight'],  
							'no_of_piece'   =>0,
							'updated_by'	=> $this->session->userdata('uid'),
							'updated_on'	=> date('Y-m-d H:i:s'),
							);
							$this->$model->updateNTData($nt_data,'+');
					}
				}
				if( $this->db->trans_status()===TRUE)
			    {
			    	 $this->db->trans_commit();
			    	 echo json_encode(array("stattus" => true, "message" => "ISSUE cancel successfully"));
			    }else{
			         $this->db->trans_rollback();
			         
			         echo json_encode(array("stattus" => false, "message" => "Could not cancel this issue"));
			    }
            break;
			
			case 'ajax': 
				$list=$this->$model->getKarigarMetalIssueList(); 
			  	$access = $this->admin_settings_model->get_access('admin_ret_purchase/karigarmetalissue/list');
		        $data = array(
		        					'list'  => $list,
									'access'=> $access
		        				);  
				echo json_encode($data);
			break;
		}
	}
	
	
	function get_karigar_pending_ordes()
	{
	    $model=	self::RET_PUR_ORDER_MODEL;
	    $data=$this->$model->get_karigar_pending_ordes($_POST);
	    echo json_encode($data);
	}
	
	function get_karigar_details(){
	    $model=	self::RET_PUR_ORDER_MODEL;
	    $data = $this->$model->get_karigar_details($_POST['karId']);
	    echo json_encode($data);
	}
	
	function get_OrderProducts()
	{
	    $model=	self::RET_PUR_ORDER_MODEL;
	    $data=$this->$model->get_OrderProducts($_POST);
	    echo json_encode($data);
	}
	
	function get_OrderProductsDesign()
	{
	    $model=	self::RET_PUR_ORDER_MODEL;
	    $data=$this->$model->get_OrderProductsDesign($_POST);
	    echo json_encode($data);
	}
	
	function get_OrderSubDesigns()
	{
	    $model=	self::RET_PUR_ORDER_MODEL;
	    $data=$this->$model->get_OrderSubDesigns($_POST);
	    echo json_encode($data);
	}
	
	
		//GRN ENTRY
	function grnentry($type="",$id="")
	{   
		$model=	self::RET_PUR_ORDER_MODEL;
		$ret_catalog_model = "ret_catalog_model";
		$set_model = "admin_settings_model";
		switch($type)
		{
			case 'list':
					$data['main_content'] = self::VIEW_FOLDER.'grn_entry/list';
        			$this->load->view('layout/template', $data);
			break;
			case 'add':
			        $data['grn_details']['grn_type'] = 1;
			        $data['grn_details']['grn_karigar_id'] = '';
			        $data['grn_details']['grn_supplier_ref_no'] = '';
			        $data['grn_details']['grn_ewaybillno'] = '';
			        $data['grn_details']['grn_irnno'] = '';
			        $data['grn_details']['grn_ref_date'] = '';
			        $data['comp_details']   = $this->$set_model->get_company();
					$data['main_content'] = self::VIEW_FOLDER.'grn_entry/form';
        			$this->load->view('layout/template', $data);
			break;
			
			case 'edit':
            	$data['grn_details']    = $this->$model->get_grn_details($id);
            	$data['categories']   = $this->$ret_catalog_model->getActiveCategorymtr($_POST);
            	$data['comp_details']   = $this->$set_model->get_company();	
            	//echo "<pre>";print_r($data);exit;
            	$data['main_content'] = self::VIEW_FOLDER.'grn_entry/form';
            	$this->load->view('layout/template', $data);
            break;
			
			case 'save':
			  
			    
    			$addData   = $_POST['order'];
                $order_details   = $_POST['order_details'];
                
    			$fin_year       = $this->$model->get_FinancialYear();
    			$grn_refno      = $this->$model->generate_grn_refno($addData['grn_type']);
                
                 
     			$insData = array( 
     			    'grn_fin_year_code'     => $fin_year['fin_year_code'],
     			    'grn_karigar_id'        => $addData['id_karigar'],
     			    'grn_date'              => date("Y-m-d H:i:s"),
     				'grn_ref_no'            => $grn_refno,
     				'grn_supplier_ref_no'   => ($addData['po_supplier_ref_no']!='' ? $addData['po_supplier_ref_no']:NULL),
     				'grn_ref_date'          => !empty($addData['po_ref_date']) ? date('Y-m-d', strtotime($addData['po_ref_date'])):NULL,
     				'grn_type'              => $addData['grn_type'],
     				'grn_ewaybillno'        => ($addData['ewaybillno']!='' ? $addData['ewaybillno']:NULL),
     				'grn_despatch_through'  => ($addData['despatch_through']!='' ? $addData['despatch_through']:NULL),
     				'grn_irnno'             => ($addData['invoice_ref_no']!='' ? $addData['invoice_ref_no']:NULL),
     				'grn_other_charges'     => ($addData['other_charges_amount']!='' ? ($addData['other_charges_amount']+$addData['other_charges_tax']):0),
     				'grn_discount'          => ($addData['discount']!='' ? $addData['discount']:0),
     				'grn_purchase_amt'      => ($addData['total_cost']!='' ? $addData['total_cost']:0),
     				'grn_round_off'         => ($addData['round_off']!='' ? ($addData['round_off_type']==1 ? '+'.$addData['round_off'] :'-'.$addData['round_off']):0),
     				'grn_tcs_percent'       => ($addData['tcs_percent']!='' ? $addData['tcs_percent']:0),
     				'grn_tcs_value'         => ($addData['tcs_tax_value']!='' ? $addData['tcs_tax_value']:0),
     				'grn_pay_tds_percent'           => ($addData['tds_percent']!='' ? $addData['tds_percent']:0),
     				'grn_pay_tds_value'             => ($addData['tds_tax_value']!='' ? $addData['tds_tax_value']:0),
     				'grn_other_charges_tds_value'   => ($addData['other_charges_tds_tax_value']!='' ? $addData['other_charges_tds_tax_value']:0),
     				'grn_other_charges_tds_percent' => ($addData['charges_tds_percent']!='' ? $addData['charges_tds_percent']:0),
     				'form_secret'                   => ($addData['form_secret']!='' ? $addData['form_secret']:NULL),
     				'remarks'                       => ($addData['remarks']!='' ? $addData['remarks']:NULL),
    				'created_on'                    => date("Y-m-d H:i:s"),
    				'created_by'                    => $this->session->userdata('uid')
    			);
    			$this->db->trans_begin();
    			$insId = $this->$model->insertData($insData,'ret_grn_entry');
			    if($insId)
			    {
			        if($addData['grn_type']==1 || $addData['grn_type']==2)
			        {
			            $billSale = $_POST['item'];
    			        if(!empty($billSale))
    			        {
    			            foreach($billSale['category'] as $key => $val){
    			                
                                $array_item_details = array(
                                'grn_item_grn_id'       =>$insId,
                                'grn_item_cat_id'       =>(isset($billSale['category'][$key]) ? $billSale['category'][$key]:NULL),
                                'grn_gross_wt'          =>(isset($billSale['gross_wt'][$key]) ? $billSale['gross_wt'][$key]:NULL),
                                'grn_less_wt'           =>(isset($billSale['less_wt'][$key]) ? $billSale['less_wt'][$key]:NULL),
                                'grn_net_wt'            =>(isset($billSale['net_wt'][$key]) ? $billSale['net_wt'][$key]:NULL),
                                'grn_wastage'           =>(isset($billSale['wastage'][$key]) ? $billSale['wastage'][$key]:0),
                                'grn_no_of_pcs'         =>(isset($billSale['pcs'][$key]) ? $billSale['pcs'][$key]:NULL),
                                'grn_rate_per_grm'      =>(isset($billSale['rate_per_gram'][$key]) ? $billSale['rate_per_gram'][$key]:NULL),
                                'grn_item_cost'         =>(isset($billSale['item_cost'][$key]) ? $billSale['item_cost'][$key]:NULL),
                                'itemratecaltype'       =>(isset($billSale['rate_type'][$key]) ? $billSale['rate_type'][$key]:NULL),
                                'grn_item_gst_rate'     =>(isset($billSale['item_total_tax'][$key]) ? $billSale['item_total_tax'][$key]:NULL),
                                'grn_item_gst_value'    =>number_format(($billSale['tax_percentage'][$key]),2,'.',''),
                                'grn_item_cgst'         =>number_format(($billSale['item_cgst'][$key]),2,'.',''),
                                'grn_item_sgst'         =>number_format(($billSale['item_sgst'][$key]),2,'.',''),
                                'grn_item_igst'         =>number_format(($billSale['item_igst'][$key]),2,'.',''),
                                );
                                 $grn_item_id = $this->$model->insertData($array_item_details,'ret_grn_items');
                                 if($grn_item_id)
                                 {
                                     if($billSale['stone_details'][$key])
                                     {
                                            $stone_details=json_decode($billSale['stone_details'][$key],true);
                                            //echo "<pre>";print_r($stone_details);exit;
            								foreach($stone_details as $stone)
            								{
            									$stone_data=array(
            									'grn_item_id'    =>$grn_item_id,
            									'pieces'         =>$stone['stone_pcs'],
            									'wt'             =>$stone['stone_wt'],
            									'stone_id'       =>$stone['stone_id'],
            									'amount'          =>$stone['stone_price'],
            									'is_apply_in_lwt'=>$stone['show_in_lwt'],
                                            	'stone_cal_type' =>$stone['stone_cal_type'],
                                            	'rate_per_gram'  =>$stone['stone_rate'],
            									);
            									$stoneInsert = $this->$model->insertData($stone_data,'ret_grn_item_stone');
                                                //print_r($this->db->last_query());exit;
            								}
                                     }
                                     if($billSale['other_metal_details'][$key])
                                     {
                                        $othermetal_details=json_decode($billSale['other_metal_details'][$key],true);
                                        foreach($othermetal_details as $othermetal)
                                    	{
                                        	$other_metal_data = array(
                                    	    'grn_itms_id'               =>$grn_item_id,
                                        	'grn_other_itm_metal_id'    =>$othermetal['id_metal'],
                                        	'grn_other_itm_grs_weight'  =>$othermetal['gwt'],
                                        	'grn_other_itm_pur_id'      =>$othermetal['id_purity'],
                                        	'grn_other_itm_wastage'     =>$othermetal['wastage_perc'],
                                        	'grn_other_itm_mc'          =>$othermetal['making_charge'],
                                        	'grn_other_itm_cal_type'    =>$othermetal['calc_type'],
                                        	'grn_other_itm_rate'        =>$othermetal['rate_per_gram'],
                                        	'grn_other_itm_pcs'         =>$othermetal['pcs'],
                                        	'grn_other_itm_amount'      =>$othermetal['amount'],
                                        	);
                                        	$othermetalInsert = $this->$model->insertData($other_metal_data,'ret_grn_other_metals');
                                        	//echo $this->db->last_query();exit;
                                    	}
                                     }
                                 }
                                 
    			            }
    			        }
			        }
			        
			        if(!empty($addData['other_charges_details'])){
    			        $charge_details = json_decode($addData['other_charges_details'],true);
                    	foreach($charge_details as $charges)
                    	{
                    	    $cgst_cost = 0;
                    	    $sgst_cost = 0;
                    	    $igst_cost = 0;
                    	    $total_tax = number_format(($charges['char_with_tax'] - $charges['charge_value']),2,'.','');
                    	    if($addData['cmp_country']==$addData['supplier_country'])
                    	    {
                    	        if($addData['cmp_state']==$addData['supplier_state'])
                    	        {
                    	            $cgst_cost = ($total_tax/2);
                    	            $sgst_cost = ($total_tax/2);
                    	        }
                    	        else
                    	        {
                    	            $igst_cost = $total_tax;
                    	        }
                    	    }
                    	    else
                    	    {
                    	        $cgst_cost = ($total_tax/2);
                    	        $sgst_cost = ($total_tax/2);
                    	    }
                        	$charge_data = array(
                            	'grn_id'           => $insId,
                            	'grn_charge_id'    => $charges['charge_id'],
                            	'grn_charge_value' => $charges['charge_value'],
                            	'char_with_tax'    => $charges['char_with_tax'],
                            	'char_tax'         => $charges['charge_tax'],
                            	'total_tax'        => number_format(($charges['char_with_tax'] - $charges['charge_value']),2,'.',''),
                            	'igst_cost'        => $igst_cost,
                            	'cgst_cost'        => $cgst_cost,
                            	'sgst_cost'        => $sgst_cost,
                        	);
                        	$stoneInsert = $this->$model->insertData($charge_data, 'ret_grn_other_charges');
                        	//echo $this->db->last_query();exit;
                    	}
    			        
    			    }
    			    
			        if($this->db->trans_status()===TRUE)
        			{
        				$this->db->trans_commit();
        				$this->session->set_flashdata('chit_alert',array('message'=>'GRN Entry Added Successfully','class'=>'success','title'=>'GRN Entry')); 
        				$return_data=array('status'=>TRUE,'message'=>'GRN Entry Added Successfully..');
        			}
        			else
        			{ 
        		
        			    echo $this->db->last_query();exit;
        				$this->db->trans_rollback();						 	
        				$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'GRN Entry')); 
        				$return_data=array('status'=>FALSE,'message'=>'Unable to proceed the requested process');
        			}
			    }
			    else
			    {
			            echo $this->db->last_query();exit;
        				$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'GRN Entry')); 
        				$return_data=array('status'=>FALSE,'message'=>'Unable to proceed the requested process');
			    }
			   
    			echo json_encode($return_data);
			break;
			
			case 'delete':
    			   $this->$model->deleteData('id_order_des',$id,'ret_purchase_order_description');
    	           if($this->db->trans_status()===TRUE)
    			    {
    			    	  $this->db->trans_commit();
    					  $this->session->set_flashdata('chit_alert', array('message' => 'Order Instructions deleted successfully','class' => 'success','title'=>'Delete Order Instructions'));	  
    				}			  
    			   else
    			    {
    				 $this->db->trans_rollback();
    				 $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete Order Instructions'));
    			    }
    			 redirect('admin_ret_purchase/order_description/list');	
			 break;
			 
			 case 'edit':
			    $model  =	self::RET_PUR_ORDER_MODEL;
                $data   =  $this->$model->get_orderdescription($id);
                echo json_encode($data);
			 break;
			 
			 case 'update':
			     
				
				$updData = $_POST['order'];


				$id = $updData['grn_id'];

				//echo "<pre>";print_r($updData);exit;

				$fin_year       = $this->$model->get_FinancialYear();


				$order = array( 
					'grn_fin_year_code'     => $fin_year['fin_year_code'],
					'grn_karigar_id'        => $updData['id_karigar'],
					'grn_supplier_ref_no'   => ($updData['po_supplier_ref_no']!='' ? $updData['po_supplier_ref_no']:NULL),
					'grn_ref_date'          => !empty($updData['po_ref_date']) ? date('Y-m-d', strtotime($updData['po_ref_date'])):NULL,
					'grn_type'              => $updData['grn_type'],
					'grn_ewaybillno'        => ($updData['ewaybillno']!='' ? $updData['ewaybillno']:NULL),
					'grn_despatch_through'  => ($updData['despatch_through']!='' ? $updData['despatch_through']:NULL),
					'grn_irnno'             => ($updData['invoice_ref_no']!='' ? $updData['invoice_ref_no']:NULL),
					'grn_other_charges'     => ($updData['other_charges_amount']!='' ? ($updData['other_charges_amount']+$updData['other_charges_tax']):0),
					'grn_discount'          => ($updData['discount']!='' ? $updData['discount']:0),
					'grn_purchase_amt'      => ($updData['total_cost']!='' ? $updData['total_cost']:0),
					'grn_round_off'         => ($updData['round_off']!='' ? ($updData['round_off_type']==1 ? '+'.$updData['round_off'] :'-'.$updData['round_off']):0),
					'grn_tcs_percent'       => ($updData['tcs_percent']!='' ? $updData['tcs_percent']:0),
					'grn_tcs_value'         => ($updData['tcs_tax_value']!='' ? $updData['tcs_tax_value']:0),
					'grn_pay_tds_percent'           => ($updData['tds_percent']!='' ? $updData['tds_percent']:0),
					'grn_pay_tds_value'             => ($updData['tds_tax_value']!='' ? $updData['tds_tax_value']:0),
					'grn_other_charges_tds_value'   => ($updData['other_charges_tds_tax_value']!='' ? $updData['other_charges_tds_tax_value']:0),
					'grn_other_charges_tds_percent' => ($updData['charges_tds_percent']!='' ? $updData['charges_tds_percent']:0),
					'form_secret'                   => ($updData['form_secret']!='' ? $updData['form_secret']:NULL),
					'remarks'                       => ($updData['remarks']!='' ? $updData['remarks']:NULL),
				   	'updated_on'                    => date("Y-m-d H:i:s"),
				   	'updated_by'                    => $this->session->userdata('uid')
			   );
			   
			   $this->db->trans_begin();
			 
			   $update_status = $this->$model->updateData($order,'grn_id',$id,'ret_grn_entry');	

    			if($update_status)
    			{
    				$this->$model->deleteData('grn_item_grn_id', $id, 'ret_grn_items'); 
    
    			   	if($updData['grn_type']==1 || $updData['grn_type']==2)
    				{
    
    					$updItems = $_POST['item'];
    
    					foreach($updItems['category'] as $key => $val)
    					{
    
    						$this->$model->deleteData('grn_item_id', $updItems['grn_item_id'][$key], 'ret_grn_item_stone'); 
    
    						$this->$model->deleteData('grn_itms_id', $updItems['grn_item_id'][$key], 'ret_grn_other_metals'); 
    						
    
    						$ItemDetails = array(
    							
    							'grn_item_grn_id'       =>$id,
    							'grn_item_cat_id'       =>(isset($updItems['category'][$key]) ? $updItems['category'][$key]:NULL),
    							'grn_gross_wt'          =>(isset($updItems['gross_wt'][$key]) ? $updItems['gross_wt'][$key]:NULL),
    							'grn_less_wt'           =>(isset($updItems['less_wt'][$key]) ? $updItems['less_wt'][$key]:NULL),
    							'grn_net_wt'            =>(isset($updItems['net_wt'][$key]) ? $updItems['net_wt'][$key]:NULL),
    							'grn_wastage'           =>(isset($updItems['wastage'][$key]) ? $updItems['wastage'][$key]:0),
    							'grn_no_of_pcs'         =>(isset($updItems['pcs'][$key]) ? $updItems['pcs'][$key]:NULL),
    							'grn_rate_per_grm'      =>(isset($updItems['rate_per_gram'][$key]) ? $updItems['rate_per_gram'][$key]:NULL),
    							'grn_item_cost'         =>(isset($updItems['item_cost'][$key]) ? $updItems['item_cost'][$key]:NULL),
    							'itemratecaltype'       =>(isset($updItems['rate_type'][$key]) ? $updItems['rate_type'][$key]:NULL),
    							'grn_item_gst_rate'     =>(isset($updItems['item_total_tax'][$key]) ? $updItems['item_total_tax'][$key]:NULL),
    							'grn_item_gst_value'    =>number_format(($updItems['tax_percentage'][$key]),2,'.',''),
    							'grn_item_cgst'         =>number_format(($updItems['item_cgst'][$key]),2,'.',''),
    							'grn_item_sgst'         =>number_format(($updItems['item_sgst'][$key]),2,'.',''),
    							'grn_item_igst'         =>number_format(($updItems['item_igst'][$key]),2,'.',''),
    						);
    
    						$grn_item_id = $this->$model->insertData($ItemDetails,'ret_grn_items');
    
    						if($grn_item_id)
    						{
    
    							if($updItems['stone_details'][$key])
    							{
    									$stone_details=json_decode($updItems['stone_details'][$key],true);
    									//echo "<pre>";print_r($stone_details);exit;
    									foreach($stone_details as $stone)
    									{
    										$stone_data=array(
    										'grn_item_id'    =>$grn_item_id,
    										'pieces'         =>$stone['stone_pcs'],
    										'wt'             =>$stone['stone_wt'],
    										'stone_id'       =>$stone['stone_id'],
    										'amount'          =>$stone['stone_price'],
    										'is_apply_in_lwt'=>$stone['show_in_lwt'],
    										'stone_cal_type' =>$stone['stone_cal_type'],
    										'rate_per_gram'  =>$stone['stone_rate'],
    										);
    										$stoneInsert = $this->$model->insertData($stone_data,'ret_grn_item_stone');
    										//print_r($this->db->last_query());exit;
    									}
    							}
    							if($updItems['other_metal_details'][$key])
    							{
    								$othermetal_details=json_decode($updItems['other_metal_details'][$key],true);
    								foreach($othermetal_details as $othermetal)
    								{
    									$other_metal_data = array(
    									'grn_itms_id'               =>$grn_item_id,
    									'grn_other_itm_metal_id'    =>$othermetal['id_metal'],
    									'grn_other_itm_grs_weight'  =>$othermetal['gwt'],
    									'grn_other_itm_pur_id'      =>$othermetal['id_purity'],
    									'grn_other_itm_wastage'     =>$othermetal['wastage_perc'],
    									'grn_other_itm_mc'          =>$othermetal['mc_value'],
    									'grn_other_itm_cal_type'    =>$othermetal['calc_type'],
    									'grn_other_itm_rate'        =>$othermetal['rate_per_gram'],
    									'grn_other_itm_pcs'         =>$othermetal['pcs'],
    									'grn_other_itm_amount'      =>$othermetal['amount'],
    									);
    									$othermetalInsert = $this->$model->insertData($other_metal_data,'ret_grn_other_metals');
    									//echo $this->db->last_query();exit;
    								}
    							}
    
    
    
    						}
    						
    
    					}
    				}
    
    					if(!empty($updData['other_charges_details']))
    					{
    						$this->$model->deleteData('grn_id', $id, 'ret_grn_other_charges');
    
        			        $charge_details = json_decode($updData['other_charges_details'],true);
    
    						//echo "<pre>";print_r($charge_details);exit; 
    
                        	foreach($charge_details as $charges)
                        	{
                        	    $cgst_cost = 0;
                        	    $sgst_cost = 0;
                        	    $igst_cost = 0;
                        	    $total_tax = number_format(($charges['char_with_tax'] - $charges['charge_value']),2,'.','');
                        	    if($updData['cmp_country']==$updData['supplier_country'])
                        	    {
                        	        if($updData['cmp_state']==$updData['supplier_state'])
                        	        {
                        	            $cgst_cost = ($total_tax/2);
                        	            $sgst_cost = ($total_tax/2);
                        	        }
                        	        else
                        	        {
                        	            $igst_cost = $total_tax;
                        	        }
                        	    }
                        	    else
                        	    {
                        	        $cgst_cost = ($total_tax/2);
                        	        $sgst_cost = ($total_tax/2);
                        	    }
                            	$charge_data = array(
                                	'grn_id'           => $id,
                                	'grn_charge_id'    => $charges['charge_id'],
                                	'grn_charge_value' => $charges['charge_value'],
                                	'char_with_tax'    => $charges['char_with_tax'],
                                	'char_tax'         => $charges['charge_tax'],
                                	'total_tax'        => number_format(($charges['char_with_tax'] - $charges['charge_value']),2,'.',''),
                                	'igst_cost'        => $igst_cost,
                                	'cgst_cost'        => $cgst_cost,
                                	'sgst_cost'        => $sgst_cost,
                            	);
                            	$stoneInsert = $this->$model->insertData($charge_data, 'ret_grn_other_charges');
                            	//echo $this->db->last_query();exit;
                        	}
        			        
        			    }
        			    
    				
    			}
    
			    if($this->db->trans_status()===TRUE)
    			{
    				$this->db->trans_commit();
    				$this->session->set_flashdata('chit_alert',array('message'=>'GRN Entry Updated Successfully','class'=>'success','title'=>'GRN ENTRY')); 
    				$return_data=array('status'=>TRUE,'message'=>'GRN Entry Updated Successfully');
    			}
    			else
    			{ 
    		
    			    echo $this->db->last_query();exit;
    				$this->db->trans_rollback();						 	
    				$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'GRN ENTRY')); 
    				$return_data=array('status'=>FALSE,'message'=>'Unable to proceed the requested process');
    			}
    			echo json_encode($return_data);
            break;
			 
			 case 'cancel_grn_entry':
                    $updData=array(
                    'grn_bill_status'   => 2,
                    'cancel_reason'     => $_POST['cancel_reason'],
                    'updated_on'        => date("Y-m-d H:i:s"),
                    'updated_by'        => $this->session->userdata('uid')
                    );
                    $this->db->trans_begin();
                    $this->$model->updateData($updData,'grn_id',$_POST['grn_id'],'ret_grn_entry');
                    if($this->db->trans_status()===TRUE)
                    {
                        $this->db->trans_commit();
                        $this->session->set_flashdata('chit_alert',array('message'=>'GRN Entry Cancelled Successfully','class'=>'success','title'=>'GRN Entry')); 
                        $return_data=array('status'=>TRUE,'message'=>'Order Instructions Added Successfully..');
                    }
                    else
                    { 
                        echo $this->db->last_query();exit;
                        $this->db->trans_rollback();						 	
                        $this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'GRN Entry')); 
                        $return_data=array('status'=>FALSE,'message'=>'Unable to proceed the requested process');
                    }
                    echo json_encode($return_data);
			 break;
			
		    default:
		            $from_date	= $this->input->post('from_date');
			        $to_date	= $this->input->post('to_date'); 
			        $list=$this->$model->ajax_getGrnentrydetails($from_date, $to_date); 
				  	$access = $this->admin_settings_model->get_access('admin_ret_purchase/grnentry/list');
			        $data = array(
			        					'list'  => $list,
										'access'=> $access
			        				);  
					echo json_encode($data);

		}
	}
	
	function grn_invoice($id)
	{
	    $model=	self::RET_PUR_ORDER_MODEL;
	    $set_model = "admin_settings_model";
        $data['comp_details']   = $this->$set_model->get_company();
        $data['grn_details']    = $this->$model->get_grn_details($id);
        //echo "<pre>";print_r($data);exit;
        $this->load->helper(array('dompdf', 'file'));
        $dompdf = new DOMPDF();
        $html = $this->load->view(self::VIEW_FOLDER.'grn_entry/invoice', $data,true);
        $dompdf->load_html($html);
        $dompdf->set_paper('A4', "portriat" );
		$dompdf->render();
		$dompdf->stream("VendorAck.pdf",array('Attachment'=>0));
	}
	
	
	//GRN ENTRY
	
	//Supplier Update
	function update_karigar(){
		$model=	self::RET_PUR_ORDER_MODEL;

		$updData=array(
			'firstname'=>$_POST['first_name'],
			'pan_no'=>$_POST['pan_no'],
			'gst_number'=>$_POST['gst_no'],
			'address1'=>$_POST['address1'],
			'address2'=>$_POST['address2'],
			'address3'=>$_POST['address3'],
			'pincode'=>$_POST['pin_code_add'],
			'id_city'=>$_POST['id_city'],
			'id_state'=>$_POST['id_state'],
			'id_country'=>$_POST['id_country'],
			);
		  $this->db->trans_begin();
		  $status = $this->$model->updateData($updData,'id_karigar',$_POST['id_karigar'],'ret_karigar');
		  if($this->db->trans_status()===TRUE)
		  {
			  $this->db->trans_commit();
			  $return_data=array('status'=>TRUE,'title'=>'Success','priority'=>'success','msg'=>'Karigar Updated successfully..');
		  }
		  else
		  { 
	  
			  $this->db->trans_rollback();						 	
			  $return_data=array('status'=>FALSE,'title'=>'Warning','priority'=>'danger','msg'=>'Unable to proceed your request');
		  }
			  echo json_encode($return_data);

	}
	//Supplier Update
	
	
	
	//approval stock tag list
	function approvalstock_tag($type="")
	{   
		$model=	self::RET_PUR_ORDER_MODEL;
		$set_model = "admin_settings_model";
		switch($type)
		{
			case 'list':
					$data['main_content'] = self::VIEW_FOLDER.'approval_tag';
        			$this->load->view('layout/template', $data);
			break;
			
			case 'ajax': 
				$list=$this->$model->get_approval_stock_tags($_POST); 
			  	$access = $this->admin_settings_model->get_access('admin_ret_purchase/approvalstock_tag/list');
		        $data = array(
		        					'list'  => $list,
									'access'=> $access
		        				);  
				echo json_encode($data);
			break;
		}
	}
	
	function order_place()
	{
	    $model=	self::RET_PUR_ORDER_MODEL;
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
	            
	            
    	        
	            $tag_det = $this->$model->get_approval_tag_details($val['tag_id']);
	            
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
				
				if($insOrderdet)
				{
				    $dCData = $this->admin_settings_model->getBranchDayClosingData($val['current_branch']);
    	            $bill_date = ($dCData['entry_date'] == date("Y-m-d") ? date("Y-m-d H:i:s") : $dCData['entry_date']);
    	            
				    $tag_log=array(
                            'tag_id'	  =>$val['tag_id'],
                            'date'		  =>$bill_date,
                            'status'	  =>13,
                            'from_branch' =>$tag_det['current_branch'],
                            'to_branch'	  =>NULL,
                            'issuspensestock'	  =>1,
                            'created_on'  =>date("Y-m-d H:i:s"),
                            'created_by'  =>$this->session->userdata('uid'),
                            );
                            $this->$model->insertData($tag_log,'ret_taging_status_log');
				}
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
	
	
	function convert_to_normal_stock()
	{
	    $model=	self::RET_PUR_ORDER_MODEL;
	    $req_data       = $_POST['req_data']; 
	    $karigar_details    =[];
	    $ho              =  $this->$model->get_headOffice();
	    foreach($req_data as $val)
	    {
	        $tag_det = $this->$model->get_approval_tag_details($val['tag_id']);
	        
	        if($tag_det['tag_status']==0)
	        {
    	        $dCData = $this->admin_settings_model->getBranchDayClosingData($tag_det['current_branch']);
    	        
    	        $bill_date = ($dCData['entry_date'] == date("Y-m-d") ? date("Y-m-d H:i:s") : $dCData['entry_date']);
    	        
                $this->$model->updateData(array('is_approval_stock_converted'=>1,'app_stk_converted_date'=>date("Y-m-d H:i:s"),'app_stk_converted_by'=>$this->session->userdata('uid')),'tag_id',$val['tag_id'], 'ret_taging');
                
                $ho_log_data=array(
                'tag_id'	  =>$val['tag_id'],
                'date'		  =>$bill_date,
                'status'	  =>0,
                'from_branch' =>NULL,
                'to_branch'   =>$ho['id_branch'],
                'created_on'  =>date("Y-m-d H:i:s"),
                'created_by'  =>$this->session->userdata('uid'),
                );
                $this->$model->insertData($ho_log_data,'ret_taging_status_log'); //Update Tag lot status
                
                if($ho['id_branch']!=$tag_det['current_branch'])
                {
                    $ho_log_data=array(
                    'tag_id'	  =>$val['tag_id'],
                    'date'		  =>$bill_date,
                    'status'	  =>4,
                    'from_branch' =>1,
                    'to_branch'   =>$tag_det['current_branch'],
                    'created_on'  =>date("Y-m-d H:i:s"),
                    'created_by'  =>$this->session->userdata('uid'),
                    );
                    $this->$model->insertData($ho_log_data,'ret_taging_status_log'); //Update Tag lot status
                    
                    $branch_log_data=array(
                    'tag_id'	  =>$val['tag_id'],
                    'date'		  =>$bill_date,
                    'status'	  =>0,
                    'from_branch' =>1,
                    'to_branch'   =>$tag_det['current_branch'],
                    'created_on'  =>date("Y-m-d H:i:s"),
                    'created_by'  =>$this->session->userdata('uid'),
                    );
                    $this->$model->insertData($branch_log_data,'ret_taging_status_log'); //Update Tag lot status
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
	
	
	//approval stock tag list
	
	
	//Lot generate
	function generateLot()
	{
	    $model           =	self::RET_PUR_ORDER_MODEL;
	    $po_id           =  $_POST['po_id'];
	    $item_details    =  $_POST['req_data'];
	    $lotData = [];
        foreach($item_details as $r){
			$lotData[$r['stock_type']][$r['cat_id']][$r['id_purity']][] = $r; 
		}
		
        $ho              =  $this->$model->get_headOffice();
		$dCData          =  $this->admin_settings_model->getBranchDayClosingData($ho['id_branch']);
		$bill_date       =  ($dCData['entry_date'] == date("Y-m-d") ? date("Y-m-d H:i:s") : $dCData['entry_date']);
		$po_details      = $this->$model->get_po_details($po_id);
    	foreach($lotData as $stk_type =>$stock_type)
		{
		    foreach($stock_type as $cat_id =>$category)
		    {
		        foreach($category as $id_purity =>$product_details)
		        {
		            $data = array(
                        'lot_date'				=> date("Y-m-d H:i:s"),
                        'created_branch'		=> (isset($ho['id_branch']) ? $ho['id_branch']:NULL ),
                        'lot_received_at'       => $ho['id_branch'],
                        'stock_type'            => $stk_type,
                        'lot_from'              => 2,
                        'po_id'                 => $po_id,
                        'gold_smith'			=> $po_details['po_karigar_id'],
                        'id_category'			=> $cat_id,
                        'id_purity'				=> $id_purity,
                        'created_on'	  		=> date("Y-m-d H:i:s"),
                        'created_by'      		=> $this->session->userdata('uid')
                        );
                        $this->db->trans_begin();
    	 	            $insId = $this->$model->insertData($data,'ret_lot_inwards');
    	 	            if($insId)
    	 	            {
    	 	                foreach($product_details as $items)
    	 	                {
    	 	                    $isLotExist = $this->$model->checkLotItemExist(array('id_product'=>$items['pro_id'],'lot_no'=>$insId));
    	 	                    if($isLotExist['status'] == TRUE)
    	 	                    {
    	 	                        $nt_data = array(
                                            'id_lot_inward_detail'  => $isLotExist['id_lot_inward_detail'],
                                            'gross_wt'		        => $items['gross_wt'],
                                            'net_wt'		        => $items['net_wt'],  
                                            'less_wt'		        => $items['less_wt'],
                                            'no_of_piece'           => $items['pcs'],
                                            'updated_by'	        => $this->session->userdata('uid'),
                                            'updated_on'	        => date('Y-m-d H:i:s'),
                                            );
                                        $this->$model->update_lot_data($nt_data,'+');
    	 	                    }else
    	 	                    {
    	 	                        $lotDetails = array(
                                        'lot_no'	    =>$insId,
                                        'lot_product'   =>($items['pro_id']!='' ? $items['pro_id']:NULL),
                                        'no_of_piece'   =>($items['pcs']!='' ? $items['pcs']:0),
                                        'gross_wt'      =>($items['gross_wt']!='' ? $items['gross_wt']:0),
                                        'less_wt'       =>($items['less_wt']!='' ? $items['less_wt']:0),
                                        'net_wt'        =>($items['net_wt']!='' ? $items['net_wt']:0),
										'lot_id_category'=> $cat_id,
										'lot_id_purity'  => $id_purity,
                                    ); 
                                    
                                    $detail_insId = $this->$model->insertData($lotDetails,'ret_lot_inwards_detail'); 
                                    if($detail_insId)
                                    {
                                        $stone_details = json_decode($items['stone_details'],true);
                                        //echo "<pre>";print_r($stone_details);exit;
                                        if(sizeof($stone_details)>0){
                                            foreach($stone_details as $val)
                                            {
                                                $stnData = array(
                                                        'id_lot_inward_detail'  =>$detail_insId,
                                                        'stone_id'              =>$val['stone_id'],
                                                        'uom_id'                =>$val['uom_id'],
                                                        'stone_pcs'             =>$val['qc_passed_pcs'],
                                                        'stone_wt'              =>$val['qc_passed_wt'],
                                                        );
                                                $this->$model->insertData($stnData,'ret_lot_inwards_stone_detail'); 
                                            }
                                        }
                                        
                                    }
    	 	                    }
    	 	                    if($stk_type==2)
    	 	                    {
    	 	                        $existData=array('id_product'=>$items['pro_id'],'design'=>$items['id_design'],'id_sub_design'=>$items['id_sub_design'],'id_branch'=>$ho['id_branch']);
                                						        
    						        $isExist = $this->$model->checkNonTagItemExist($existData);
    						        
    						        if($isExist['status'] == TRUE)
    						        {
    						            $nt_data = array(
                                        'id_nontag_item'=>$isExist['id_nontag_item'],
                                        'gross_wt'		=> $items['gross_wt'],
                                        'net_wt'		=> $items['net_wt'],  
                                        'no_of_piece'   => $items['pcs'],
                                        'updated_by'	=> $this->session->userdata('uid'),
                                        'updated_on'	=> date('Y-m-d H:i:s'),
                                        );
                                        $this->$model->updateNTData($nt_data,'+');
                            													
                                        $non_tag_data=array(
                                        'product'	    => $items['pro_id'],
                                        'design'	    => $items['id_design'],
                                        'id_sub_design'	=> $items['id_sub_design'],
                                        'no_of_piece'   => $items['pcs'],
                                        'gross_wt'		=> $items['gross_wt'],
                                        'net_wt'		=> $items['net_wt'],
                                        'to_branch'	    => $ho['id_branch'],
                                        'from_branch'	=> NULL,
                                        'ref_no'	    => $insId,
                                        'status'	    => 0,
                                        'date'          => $bill_date,
                                        'created_on'    => date("Y-m-d H:i:s"),
                                        'created_by'    =>  $this->session->userdata('uid')
                                        );
                                        $this->$model->insertData($non_tag_data,'ret_nontag_item_log');
    						        }else
    						        {
                                            $nt_data=array(
                                            'branch'	    => $ho['id_branch'],
                                            'product'	    => $items['pro_id'],
                                            'design'	    => $items['id_design'],
                                            'id_sub_design'	=> $items['id_sub_design'],
                                            'no_of_piece'   => $items['pcs'],
                                            'gross_wt'		=> $items['gross_wt'],
                                            'net_wt'		=> $items['net_wt'],
                                            'created_on'    => date("Y-m-d H:i:s"),
                                            'created_by'    => $this->session->userdata('uid')
                                            );
                                            $this->$model->insertData($nt_data,'ret_nontag_item'); 
                                        
                                            $non_tag_data=array(
                                            'product'	    => $items['pro_id'],
                                            'design'	    => $items['id_design'],
                                            'id_sub_design'	=> $items['id_sub_design'],
                                            'no_of_piece'   => $items['pcs'],
                                            'gross_wt'		=> $items['gross_wt'],
                                            'net_wt'		=> $items['net_wt'],
                                            'from_branch'	=> NULL,
                                            'to_branch'	    => $ho['id_branch'],
                                            'ref_no'	    => $insId,
                                            'status'	    => 0,
                                            'date'          => $bill_date,
                                            'created_on'    => date("Y-m-d H:i:s"),
                                            'created_by'    =>  $this->session->userdata('uid')
                                            );
                                            $this->$model->insertData($non_tag_data,'ret_nontag_item_log'); 
    						        }
    	 	                    }
    	 	                    $this->$model->updateData(array('is_lot_created'=>1,'lot_no'=>$insId),'id_qc_issue_details',$items['id_qc_issue_details'],'ret_po_qc_issue_details');
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
            	'module'      	=> 'Lot',
            	'operation'   	=> 'Add',
            	'record'        => $insId,  
            	'remark'       	=> 'Lot added successfully From QC Receipt'
            );
            $this->log_model->log_detail('insert','',$log_data);
            $this->db->trans_commit();
            $responseData=array('status'=>true,'message'=>'Lot Created successfully.');
        }	
        else
        {
            $this->db->trans_rollback();
            $responseData=array('status'=>false,'message'=>'Unable to proceed the requested operation.');
        }
        echo json_encode($responseData);
	}
	//Lot generate
	
	
	
	function update_po_approval()
	{
		$model=	self::RET_PUR_ORDER_MODEL;
		$response_data=array();
		$insert_data=$this->input->post('req_data');
		foreach ($insert_data as $value)
		{
            $Data=array( 
            'po_id' => $value['id_po'],
            'is_approved' 	 => 1,
			'approved_by' 	 =>  $this->session->userdata('uid'),
            'approved_date'  => date("Y-m-d H:i:s"),

            );
            $this->db->trans_begin();
			$this->$model->updateData($Data,'po_id',$Data['po_id'],'ret_purchase_order');

		}
		if($this->db->trans_status()===TRUE)
        {
		 	$this->db->trans_commit();
		 	$response_data=array('status'=>TRUE,'message'=>'Approved Successfully.');
		}	
		else
		{
			$this->db->trans_rollback();
			$response_data=array('status'=>FALSE,'message'=>'Unable to proceed Your Request.');
		}
		echo json_encode($response_data);	

	}
    
    
    //Weight and Amount Conversation
    function supplier_rate_cut($type = "",$id="",$status="")
	{
		$model =	self::RET_PUR_ORDER_MODEL;
		switch ($type) {
			case 'list':
				$data['main_content'] = self::VIEW_FOLDER . 'amt_weight_conversation/list';
				$this->load->view('layout/template', $data);
				break;
			case 'add':

				$data['main_content'] = self::VIEW_FOLDER . 'amt_weight_conversation/form';
				$this->load->view('layout/template', $data);
				break;
			case 'save':
				$addData                = $_POST['supplier_rate_cut'];
				$ho                     =  $this->$model->get_headOffice();
				$entry_date             =  $this->admin_settings_model->getBranchDayClosingData($ho['id_branch']);
				$date_add               =  ($entry_date['entry_date'] == date("Y-m-d") ? date("Y-m-d H:i:s") : $entry_date['entry_date']);
				$data = array(

					'id_branch'         => ($addData['id_branch'] != '' ? $addData['id_branch'] : 1),
					'id_karigar'        => ($addData['id_karigar'] != '' ? $addData['id_karigar'] : NULL),
					'rate_cut_type'     => ($addData['rate_cut_type'] != '' ? $addData['rate_cut_type'] : NULL),
					'id_metal'          => ($addData['id_metal'] != '' ? $addData['id_metal'] : NULL),
					'date_add'          => ($date_add != '' ? $date_add : NULL),
					'amount'            => ($addData['type1_amt'] != '' ? $addData['type1_amt'] : ($addData['type2_amt'] != '' ? $addData['type2_amt'] : NULL)),
					'weight'            => ($addData['type1_wt'] != '' ? $addData['type1_wt'] : ($addData['type2_wt'] != '' ? $addData['type2_wt'] : NULL)),
					'rate_per_gram'     => ($addData['src_rate'] != '' ? $addData['src_rate'] : NULL),
					'status'            => 1,
					'narration'         => ($addData['src_remark'] != '' ? $addData['src_remark'] : NULL),
					'created_on'        => date("Y-m-d H:i:s"),
					'created_by'        => $this->session->userdata('uid'),


				);
				$this->db->trans_begin();
				$insOrder = $this->$model->insertData($data, 'ret_supplier_rate_cut');
				//    print_r($this->db->last_query());exit;
				if ($this->db->trans_status() === TRUE) {
					$this->db->trans_commit();
					$this->session->set_flashdata('chit_alert', array('message' => 'Added Successfully', 'class' => 'success', 'title' => 'Rate Cut'));
					$return_data = array('status' => TRUE, 'message' => 'Order Instructions Added Successfully..');
				} else {

					echo $this->db->last_query();
					exit;
					$this->db->trans_rollback();
					$this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed the requested process', 'class' => 'danger', 'title' => 'Rate Cut'));
					$return_data = array('status' => FALSE, 'message' => 'Unable to proceed the requested process');
				}
				echo json_encode($return_data);
				break;


			case 'ajax':
				$list = $this->$model->get_supplier_rate_cut_details($_POST);
				$access = $this->admin_settings_model->get_access('admin_ret_purchase/supplier_rate_cut/list');
				$data = array(
					'list'  => $list,
					'access' => $access
				);
				echo json_encode($data);
				break;

				case 'cancel':
				    $ho                     =  $this->$model->get_headOffice();
				    $entry_date             =  $this->admin_settings_model->getBranchDayClosingData($ho['id_branch']);
				    $date_add               =  ($entry_date['entry_date'] == date("Y-m-d") ? date("Y-m-d H:i:s") : $entry_date['entry_date']);
				    
					$data = array(
				            'status'            => 2,
					        'cancelled_on'	    => $date_add,
					        'updated_on'	    => date("Y-m-d H:i:s"),
					        'cancelled_by'      => $this->session->userdata('uid'),
					        'updated_by'        => $this->session->userdata('uid'),
					        'cancelled_reason'  => ($_POST['cancel_reason']!='' ? $_POST['cancel_reason'] :NULL),
					        );
					$this->db->trans_begin();
					$updstatus = $this->$model->updateData($data,'id_supplier_rate_cut',$_POST['id_supplier_rate_cut'],'ret_supplier_rate_cut');
    				if ($this->db->trans_status() === TRUE) 
    				{
    					$this->db->trans_commit();
    					$this->session->set_flashdata('chit_alert', array('message' => 'Cancelled Successfully', 'class' => 'success', 'title' => 'Weight Conversion'));
    					$return_data = array('status' => TRUE, 'message' => 'Cancelled Successfully..');
    				}else{
    					$this->db->trans_rollback();
    					$this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed the requested process', 'class' => 'danger', 'title' => 'Weight Conversion'));
    					$return_data = array('status' => FALSE, 'message' => 'Unable to proceed the requested process');
    				}
    				echo json_encode($return_data);
				break;

				echo json_encode($data);
		}
	}
    //Weight and Amount Conversation
    
    
    function lot_and_tag_wise_report($type="")
	{   
		$model=	self::RET_PUR_ORDER_MODEL;
		$set_model = "admin_settings_model";
		switch($type)
		{
			case 'list':
					$data['main_content'] = self::VIEW_FOLDER.'lot_and_tag_wise_report';
        			$this->load->view('layout/template', $data);
			break;
			
			case 'ajax': 
				$list=$this->$model->get_lot_and_tag_wise_report($_POST); 
			  	$access = $this->admin_settings_model->get_access('admin_ret_purchase/lot_and_tag_wise_report/list');
		        $data = array(
		        					'list'  => $list,
									'access'=> $access
		        				);  
				echo json_encode($data);
			break;
		}
	}
	
	function headoffice_valut_report($type="")
	{   
		$model=	self::RET_PUR_ORDER_MODEL;
		$set_model = "admin_settings_model";
		switch($type)
		{
			case 'list':
					$data['main_content'] = self::VIEW_FOLDER.'reports/headoffice_valut_report';
        			$this->load->view('layout/template', $data);
			break;
			
			case 'ajax': 
				$list=$this->$model->get_headoffice_valut_report($_POST); 
			  	$access = $this->admin_settings_model->get_access('admin_ret_purchase/headoffice_valut_report/list');
		        $data = array(
		        					'list'  => $list,
									'access'=> $access
		        				);  
				echo json_encode($data);
			break;
		}
	}
	
	
}
?>
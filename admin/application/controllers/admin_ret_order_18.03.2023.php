<?php
if( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH.'libraries/dompdf/autoload.inc.php');
use Dompdf\Dompdf;
class Admin_ret_order extends CI_Controller
{ 
	const IMG_PATH  = 'assets/img/';
	function __construct()
	{
		parent::__construct();
		ini_set('date.timezone', 'Asia/Calcutta');
		$this->load->model('ret_order_model');
		$this->load->model('admin_settings_model');
		$this->load->model("admin_usersms_model");
		$this->load->model("ret_billing_model");
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
	
	
	/**
	* Order Functions Starts
	*/
    
    
    function shortenurl($url)
	{
		$ch = curl_init();  
		$timeout = 5;  
		curl_setopt($ch,CURLOPT_URL,'https://tinyurl.com/api-create.php?url='.$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);  
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);  
		$data = curl_exec($ch);  
		curl_close($ch);  
		return $data;  
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
	
	public function isValueset($field)
	{
		$data=($field ? $field:'-');
		return $data;
	}
	
	public function taxGroupItems()
	{
		$tgrp = $this->ret_order_model->getAvailableTaxGroupItems($_POST['tgrp_id']);
		echo json_encode($tgrp);
	}
	
	public function order($type="",$id="",$order_no=""){
		$model = "ret_order_model";
		$this->load->model('ret_catalog_model');
		$ret_catalog_model = "ret_catalog_model";
		switch($type)
		{
			case 'getOrderNosBySearch': 
				  	$result = $this->$model->getOrderNos($_POST['searchTxt']);	
					echo json_encode($result); 
				break;
			case 'list':
					$data['main_content'] = "order/list" ;
					$this->load->view('layout/template', $data);
				break;
			case 'add':
					$data = $this->$model->empty_rec_order(); 
					$data['categories'] = $this->ret_order_model->getActiveCategories(); 
					$data['main_content'] = "order/form" ;
					$this->load->view('layout/template', $data);
				break; 
			case "save":
					$addData = $_POST; 

					$fin_year       = $this->$model->get_FinancialYear();
					$order_from = (!empty($addData['order']['order_from']) ? $addData['order']['order_from'] :NULL );
					$order_no = $this->$model->generateOrderNo($order_from, 2);
					$dCData = $this->admin_settings_model->getBranchDayClosingData($addData['order']['order_from']);
					$order_datetime = ($dCData['entry_date'] == date("Y-m-d") ? date("Y-m-d H:i:s") : $dCData['entry_date']);
					
	 				$order = array( 
	 				    'fin_year_code'     => $fin_year['fin_year_code'],
		 				'order_type'		=> 2, // Customer Order
		 				'order_from'		=> $order_from,
		 				'order_no'          => $order_no,
		 				'order_for'			=> (!empty($addData['order']['order_for']) ? $addData['order']['order_for'] :NULL ),
		 				'rate_calc_from'	=> (!empty($addData['order']['rate_calc_from']) ? $addData['order']['rate_calc_from'] :1 ),
		 				'order_date'		=> $order_datetime,
		 				'order_to'			=> ($addData['order']['order_for'] == 1 ? $addData['order']['id_branch'] :$addData['order']['order_to'] ),
		 				'est_id'			=> (!empty($addData['order']['est_no']) ? $addData['order']['est_no'] :NULL ),
						'createdon'         => date("Y-m-d H:i:s"),
						'order_taken_by'         => $this->session->userdata('uid')
					);
					$this->db->trans_begin();
					$insOrder = $this->$model->insertData($order,'customerorder');
					$i = 1;
					
				    foreach ($addData['o_item'] as $d){
				        if($d['is_set_items']==0)
				        {
    					    for($total_items=1;$total_items<=$d['totalitems'];$total_items++)
    					    {
    					            $d['smith_remainder_date'] = (!empty($d['smith_remainder_date']) ?date('Y-m-d',strtotime(str_replace("/","-",$d['smith_remainder_date']))):NULL ); 
            						$d['cus_due_date'] = (!empty($d['cus_due_date']) ?date('Y-m-d',strtotime(str_replace("/","-",$d['cus_due_date']))):NULL ); 
            						$d['smith_due_date'] = (!empty($d['smith_due_date']) ?date('Y-m-d',strtotime(str_replace("/","-",$d['smith_due_date']))):NULL ); 
            					
            						$orderDetails = array( 
            			 				'orderno'			=> $order_no."-".$i,
            			 				'ortertype'			=> (!empty($d['orter_type']) ?$d['orter_type'] :NULL ),
            			 				'id_product'		=> (!empty($d['product']) ? $d['product'] :NULL ),
            			 				//'itemname'			=> (!empty($d['design']) ? $d['design'] :NULL ),
            			 				'design_no'         => (!empty($d['design']) ? $d['design'] :NULL ),
            			 				'id_sub_design'	    => (!empty($d['sub_design']) ? $d['sub_design'] :NULL ),
            			 				'id_purity'			=> (!empty($d['purity']) ? $d['purity'] :NULL ),
            			 				'totalitems'		=> 1,
            			 				'weight'			=> (!empty($d['weight']) ? $d['weight'] :NULL ),
            			 				'mc'				=> (!empty($d['mc']) ? $d['mc'] :0 ),
            			 				'id_mc_type'=> (!empty($d['id_mc_type']) ? $d['id_mc_type'] :2),
            			 				'wast_percent'		=> (!empty($d['wast_percent']) ? $d['wast_percent'] :0 ),
            			 				'stn_amt'			=> ((!empty($d['stn_amt'])&&$d['stn_amt']!=null ) ? $d['stn_amt'] :0 ),
            			 				'charge_value'      => (!empty($d['value_charge']) && $d['value_charge']!=null ? $d['value_charge'] : 0 ),
            			 				'rate'				=> (!empty($d['rate']) ? $d['rate'] :0 ),
            			 				'size'				=> (!empty($d['size']) ? $d['size'] :NULL ),
            			 				'order_date'		=> $order_datetime,
            			 				'cus_due_date'       =>($d['cus_due_day'][$key]!='' ?date('Y-m-d',(strtotime('+'.$d['cus_due_day'][$key].' day'))) :NULL),
            			 				'description'		=> (!empty($d['description']) ? $d['description'] :NULL ),
            							'id_employee'       => $this->session->userdata('uid'),
            						);
            						
            						if($insOrder > 0){
            							$orderDetails['id_customerorder'] = $insOrder;
            							$insOrderDet = $this->$model->insertData($orderDetails,'customerorderdetails');
            							if($insOrderDet)
            							{
            							       //Image Upload
                    		 					$precious_imgs = "";
                    		 					$p_ImgData=[];
                    		 					$p_ImgData = json_decode($d['order_img']);  
                    		 					
                    							if(sizeof($p_ImgData) > 0){
                    								foreach($p_ImgData as $precious){
                    									$imgFile = $this->base64ToFile($precious->src);
                    									$_FILES['order_img'][] = $imgFile;
                    								}
                    							} 
                    						//	print_r($_FILES);exit;
                    							if(!empty($_FILES)){
                    								$img_arr = array();
                    							    $folder =  self::IMG_PATH."orders/"; 
                    								if (!is_dir($folder)) {  
                    									mkdir($folder, 0777, TRUE);
                    								}   
                    								if(isset($_FILES['order_img'])){ 
                    									$order_images = "";
                    
                    									foreach($_FILES['order_img'] as $file_key => $file_val){
                    
                    										if($file_val['name'])
                    										{
                    											// unlink($folder."/".$product['image']); 
                    											$img_name =  $insOrderDet."_". mt_rand(100001,999999).".jpg";
                    											$path = $folder."/".$img_name; 
                    											$result = $this->upload_img('image',$path,$file_val['tmp_name']);
                    											if($result){
                    												$insImageId = $this->$model->insertData(array('id_orderdetails'=>$insOrderDet,'image'=>$img_name),'customer_order_image'); 
                    											}
                    										}
                    									}
                    								}
                    							}
                    							
                    							$stone_details = json_decode($d['stone_details'],true);
                    							if(sizeof($stone_details)>0)
                                                {
                                                	foreach($stone_details as $stn)
                                                	{
                                                		$stone_data=array(
                                                			'order_id'          =>    $insOrder,
                                                			'order_item_id'     =>    $insOrderDet,
                                                			'pieces'            =>    $stn['stone_pcs'],
                                                			'wt'                =>	  $stn['stone_wt'],
                                                			'stone_type'        =>	  $stn['stones_type'],
                                                			'stone_id'          =>	  $stn['stone_id'],
                                                			'uom_id'            =>    $stn['stone_uom_id'],
                                                			'price'             =>	  $stn['stone_price'],
                                                			'is_apply_in_lwt'   =>	  $stn['show_in_lwt'],
                                                			'stone_cal_type'    =>	  $stn['stone_cal_type'],
                                                			'rate_per_gram'     =>	  $stn['stone_rate'],
                                                		);
                                                		$stoneInsert = $this->$model->insertData($stone_data,'ret_order_item_stones');
                                                	}
                                                }
                                                $charges_details = json_decode($d['charges_details'],true);
                                                if(sizeof($charges_details)>0)
                                                {
                                                	foreach($charges_details as $charge)
                                                	{
                                                		$charge_data=array(
                                                				'order_id'  	=>	$insOrder,
                                                				'order_item_id'	=>	$insOrderDet,
                                                				'id_charge'    	=>	$charge['id_charge'],
                                                				'amount'       	=>	$charge['value_charge'],
                                                		);
                                                		$this->$model->insertData($charge_data,'ret_order_other_charges');
                                                	}
                                                }
            							}
            						}
            						$i++;
    					    }
    						$total_items=1;
				        }
				        else
				        {
				             $d['smith_remainder_date'] = (!empty($d['smith_remainder_date']) ?date('Y-m-d',strtotime(str_replace("/","-",$d['smith_remainder_date']))):NULL ); 
            						$d['cus_due_date'] = (!empty($d['cus_due_date']) ?date('Y-m-d',strtotime(str_replace("/","-",$d['cus_due_date']))):NULL ); 
            						$d['smith_due_date'] = (!empty($d['smith_due_date']) ?date('Y-m-d',strtotime(str_replace("/","-",$d['smith_due_date']))):NULL ); 
            					
            						$orderDetails = array( 
            			 				'orderno'			=> $order_no."-".$i,
            			 				'ortertype'			=> (!empty($d['orter_type']) ?$d['orter_type'] :NULL ),
            			 				'id_product'		=> (!empty($d['product']) ? $d['product'] :NULL ),
            			 				//'itemname'			=> (!empty($d['design']) ? $d['design'] :NULL ),
            			 				'design_no'         => (!empty($d['design']) ? $d['design'] :NULL ),
            			 				'id_sub_design'	    => (!empty($d['sub_design']) ? $d['sub_design'] :NULL ),
            			 				'id_purity'			=> (!empty($d['purity']) ? $d['purity'] :NULL ),
            			 				'totalitems'		=> $d['totalitems'],
            			 				'weight'			=> (!empty($d['weight']) ? $d['weight'] :NULL ),
            			 				'mc'				=> (!empty($d['mc']) ? $d['mc'] :0 ),
            			 				'id_mc_type'=> (!empty($d['id_mc_type']) ? $d['id_mc_type'] :2),
            			 				'wast_percent'		=> (!empty($d['wast_percent']) ? $d['wast_percent'] :0 ),
            			 				'stn_amt'			=> ((!empty($d['stn_amt'])&&$d['stn_amt']!=null ) ? $d['stn_amt'] :0 ),
            			 				'charge_value'      => (!empty($d['value_charge']) && $d['value_charge']!=null ? $d['value_charge'] : 0 ),
            			 				'rate'				=> (!empty($d['rate']) ? $d['rate'] :0 ),
            			 				'size'				=> (!empty($d['size']) ? $d['size'] :NULL ),
            			 				'order_date'		=> $order_datetime,
            			 				'cus_due_date'       =>($d['cus_due_day'][$key]!='' ?date('Y-m-d',(strtotime('+'.$d['cus_due_day'][$key].' day'))) :NULL),
            			 				'description'		=> (!empty($d['description']) ? $d['description'] :NULL ),
            							'id_employee'       => $this->session->userdata('uid'),
            						);
            						
            						if($insOrder > 0){
            							$orderDetails['id_customerorder'] = $insOrder;
            							$insOrderDet = $this->$model->insertData($orderDetails,'customerorderdetails');
            							if($insOrderDet)
            							{
            							       //Image Upload
                    		 					$precious_imgs = "";
                    		 					$p_ImgData=[];
                    		 					$p_ImgData = json_decode($d['order_img']);  
                    		 					
                    							if(sizeof($p_ImgData) > 0){
                    								foreach($p_ImgData as $precious){
                    									$imgFile = $this->base64ToFile($precious->src);
                    									$_FILES['order_img'][] = $imgFile;
                    								}
                    							} 
                    						//	print_r($_FILES);exit;
                    							if(!empty($_FILES)){
                    								$img_arr = array();
                    							    $folder =  self::IMG_PATH."orders/"; 
                    								if (!is_dir($folder)) {  
                    									mkdir($folder, 0777, TRUE);
                    								}   
                    								if(isset($_FILES['order_img'])){ 
                    									$order_images = "";
                    
                    									foreach($_FILES['order_img'] as $file_key => $file_val){
                    
                    										if($file_val['name'])
                    										{
                    											// unlink($folder."/".$product['image']); 
                    											$img_name =  $insOrderDet."_". mt_rand(100001,999999).".jpg";
                    											$path = $folder."/".$img_name; 
                    											$result = $this->upload_img('image',$path,$file_val['tmp_name']);
                    											if($result){
                    												$insImageId = $this->$model->insertData(array('id_orderdetails'=>$insOrderDet,'image'=>$img_name),'customer_order_image'); 
                    											}
                    										}
                    									}
                    								}
                    							}
                    							
                    							$stone_details = json_decode($d['stone_details'],true);
                    							if(sizeof($stone_details)>0)
                                                {
                                                	foreach($stone_details as $stn)
                                                	{
                                                		$stone_data=array(
                                                			'order_id'          =>    $insOrder,
                                                			'order_item_id'     =>    $insOrderDet,
                                                			'pieces'            =>    $stn['stone_pcs'],
                                                			'wt'                =>	  $stn['stone_wt'],
                                                			'stone_type'        =>	  $stn['stones_type'],
                                                			'stone_id'          =>	  $stn['stone_id'],
                                                			'uom_id'            =>    $stn['stone_uom_id'],
                                                			'price'             =>	  $stn['stone_price'],
                                                			'is_apply_in_lwt'   =>	  $stn['show_in_lwt'],
                                                			'stone_cal_type'    =>	  $stn['stone_cal_type'],
                                                			'rate_per_gram'     =>	  $stn['stone_rate'],
                                                		);
                                                		$stoneInsert = $this->$model->insertData($stone_data,'ret_order_item_stones');
                                                	}
                                                }
                                                $charges_details = json_decode($d['charges_details'],true);
                                                if(sizeof($charges_details)>0)
                                                {
                                                	foreach($charges_details as $charge)
                                                	{
                                                		$charge_data=array(
                                                				'order_id'  	=>	$insOrder,
                                                				'order_item_id'	=>	$insOrderDet,
                                                				'id_charge'    	=>	$charge['id_charge'],
                                                				'amount'       	=>	$charge['value_charge'],
                                                		);
                                                		$this->$model->insertData($charge_data,'ret_order_other_charges');
                                                	}
                                                }
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
					break;
			case "edit":
			    
						$data['order']  = $this->ret_order_model->getOrder($id); 
						$data['categories'] = $this->ret_order_model->getActiveCategories(); 
						$data['purity'] = $this->ret_catalog_model->getActivePurity();
						$data['main_content'] = "order/form" ;
						//echo "<pre>";print_r($data);exit;
						$this->load->view('layout/template', $data);
						break; 
	 		case "estiData":
						//$data = $this->ret_order_model->getOrderDetails($id); 
						//echo json_encode($data);
                         $estiData['categories'] = $this->ret_order_model->getActiveCategories();
                         $estiData['products'] = $this->ret_order_model->getActiveproducts();
                         $estiData['designs'] = $this->ret_order_model->getActivedesigns();
                         $estiData['subdesigns'] = $this->ret_order_model->getActivesubdesigns();
	 					 $order_detail = $this->ret_order_model->getOrderDetails($id); 
	 					 
	 					
                        foreach ($order_detail as $orders)
                        {
                            
                            $estiData['order_det'][]=array(
                            'est_no'=>$orders['est_no'],
                            'id_orderdetails'=>$orders['id_orderdetails'],
                            'orderno'=>$orders['orderno'],
                            'ortertype'=>$orders['ortertype'],
                            'id_ret_category'=>$orders['id_ret_category'],
                            'category'=>$orders['category'],
                            'id_purity'=>$orders['id_purity'],
                            'purity'=>$orders['purity'],
                            'id_product'=>$orders['id_product'],
                            'product_name'=>$orders['product_name'],
                            'design_no'=>$orders['design_no'],
                            'design_name'=>$orders['design_name'],
                            'id_sub_design'=>$orders['id_sub_design'],
                            'sub_design_name'=>$orders['sub_design_name'],
                            'weight'=>$orders['weight'],
                            'id_size'=>$orders['size'],
                            'size'=>$orders['size_name'],
                            'piecs'=>$orders['totalitems'],
                            'wastage'=>$orders['wast_percent'],
                            'mc_type'=>$orders['mc_type'],
                            'mc'=>$orders['mc'],
                            'cus_due_date'=>$orders['cus_due_date'],
                            'orderstatus'=>$orders['orderstatus'],
                            'itemname'=>$orders['itemname'],
                            'design_no'=>((isset($orders['design_no'])) ?$orders['design_no'] :''),
                            'rate'=>$orders['rate'],
                            'status'=>$orders['status'],
                            'image'=>$this->$model->get_order_images($orders['id_orderdetails']),
                            'purity'=> $this->$ret_catalog_model->getCatPurity($orders['id_ret_category']),
                            'sizes' =>$this->$model->get_product_size($orders['id_product']),
                            'stn_amt'=>$orders['stn_amt'],
                            'charge_value'=>$orders['charge_value'],
                            'stone_details' =>$this->$model->get_order_stones($orders['id_orderdetails']),
                            'other_charge_details' =>$this->$model->get_order_charges($orders['id_orderdetails']),

                            );  
                        }
                        
						echo json_encode($estiData);
						break; 
	 	
			case 'delete':
						 $this->db->trans_begin();
						 $this->$model->deleteData('id_customerorder',$id,'customerorder');
						 $this->$model->deleteData('id_customerorder',$id,'customerorderdetails');
						 $this->$model->deleteData('order_id',$id,'ret_order_advance_payment');
				           if( $this->db->trans_status()===TRUE)
						    {
						    	  $this->db->trans_commit();
								  $this->session->set_flashdata('chit_alert', array('message' => 'Order deleted successfully','class' => 'success','title'=>'Delete Order'));	  
							}			  
						   else
						    {
							 $this->db->trans_rollback();
							 $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete Order'));
						    }
						 redirect('admin_ret_order/order/list');	
						 break;
					
			case "update":
			      //echo"<pre>"; print_r($id);exit;
			        
		 			$updData = $_POST;
					$updData['order']['order_date'] = date('Y-m-d',strtotime(str_replace("/","-",$updData['order']['order_date'])));
				    $id=$updData['order']['id_customerorder'];
				    
				    //echo "<pre>";print_r($updData['o_item']);exit;
				    
					foreach ($updData['o_item'] as $d){
                    $i=1;
						//$details=$this->$model->get_orderdetails_by_id($d['id_orderdetails']);
						$d['smith_remainder_date'] = (!empty($d['smith_remainder_date']) ?date('Y-m-d',strtotime(str_replace("/","-",$d['smith_remainder_date']))):NULL ); 
						$d['cus_due_date'] = (!empty($d['cus_due_date']) ?date('Y-m-d',strtotime(str_replace("/","-",$d['cus_due_date']))):NULL ); 
						$d['smith_due_date'] = (!empty($d['smith_due_date']) ?date('Y-m-d',strtotime(str_replace("/","-",$d['smith_due_date']))):NULL ); 
						
						$orderDetails = array( 
			 				'ortertype'			=> (!empty($d['orter_type']) ?$d['orter_type'] :NULL ),
			 				'id_product'		=> (!empty($d['product']) ? $d['product'] :NULL ),
			 				'design_no'         => (!empty($d['design']) ? $d['design'] :NULL ),
			 				'id_sub_design'	    => (!empty($d['sub_design']) ? $d['sub_design'] :NULL ),
			 				'id_purity'			=> (!empty($d['purity']) ? $d['purity'] :NULL ),
			 				'totalitems'		=> (!empty($d['piecs']) ? $d['piecs'] :0 ),
			 				'weight'			=> (!empty($d['weight']) ? $d['weight'] :0 ),
			 				'mc'				=> (!empty($d['mc']) ? $d['mc'] :0 ),
			 				'id_mc_type'        => (!empty($d['id_mc_type']) ? $d['id_mc_type'] :2),
			 				'wast_percent'		=> (!empty($d['wast_percent']) ? $d['wast_percent'] :0 ),
			 				'stn_amt'			=> ((!empty($d['stn_amt'])&&$d['stn_amt']!=null ) ? $d['stn_amt'] :0 ),
			 				'charge_value'      => (!empty($d['value_charge']) && $d['value_charge']!=null ? $d['value_charge'] : 0 ),
			 				'rate'				=> (!empty($d['rate']) ? $d['rate'] :0 ),
			 				'size'				=> (!empty($d['size']) ? $d['size'] :NULL ),
			 				'order_date'		=> $order_datetime,
			 				'cus_due_date'       =>($d['cus_due_date'][$key]!='' ?date('Y-m-d',(strtotime('+'.$d['cus_due_date'][$key].' day'))) :NULL),
			 				'description'		=> (!empty($d['description']) ? $d['description'] :NULL ),
							'id_employee'       => $this->session->userdata('uid'),
							'image'			    => (!empty($d['image']) ? $d['image'] :NULL ),
						);
					    //echo "<pre>"; print_r($orderDetails);exit;
						if(isset($d['id_orderdetails']))
						{
						   
							$orderDetails['id_customerorder'] = $id;
							$this->db->trans_begin();
							$this->$model->updateData($orderDetails,'id_orderdetails',$d['id_orderdetails'],'customerorderdetails');
							
							
							if($d['id_orderdetails'])
							{
							       //Image Upload
        		 					$precious_imgs = "";
        		 					$p_ImgData=[];
        		 					$p_ImgData = json_decode($d['order_img']);  
        		 					
        							if(sizeof($p_ImgData) > 0){
        								foreach($p_ImgData as $precious){
        									$imgFile = $this->base64ToFile($precious->src);
        									$_FILES['order_img'][] = $imgFile;
        								}
        							} 
        						//	print_r($_FILES);exit;
        							if(!empty($_FILES)){
        								$img_arr = array();
        							    $folder =  self::IMG_PATH."orders/"; 
        								if (!is_dir($folder)) {  
        									mkdir($folder, 0777, TRUE);
        								}   
        								if(isset($_FILES['order_img'])){ 
        									$order_images = "";
        
        									foreach($_FILES['order_img'] as $file_key => $file_val){
        
        										if($file_val['name'])
        										{
        											// unlink($folder."/".$product['image']); 
        											$img_name =  $d['id_orderdetails']."_". mt_rand(100001,999999).".jpg";
        											$path = $folder."/".$img_name; 
        											$result = $this->upload_img('image',$path,$file_val['tmp_name']);
        											if($result){
        												$insImageId = $this->$model->insertData(array('id_orderdetails'=>$d['id_orderdetails'],'image'=>$img_name),'customer_order_image'); 
        											}
        										}
        									}
        								}
        							} 
        							$stone_details = json_decode($d['stone_details'],true);
        							$order_stone_delete_id = $this->$model->deleteData("order_item_id",$d['id_orderdetails'],'ret_order_item_stones');
        							if(sizeof($stone_details)>0)

                                    {
                                    
                                    	foreach($stone_details as $stn)
                                    	
                                    	{
                                    												
                                    		$stone_data=array(
                                    
                                    				'order_id'          =>   $id,
                                    
                                    				'order_item_id'     =>   $d['id_orderdetails'],
                                    												
                                    				'pieces'            =>   $stn['stone_pcs'],
                                    
                                    				'wt'                =>	 $stn['stone_wt'],
                                    
                                    				'stone_type'        =>	 $stn['stones_type'],
                                    
                                    				'stone_id'          =>	 $stn['stone_id'],
                                    
                                       				'uom_id'      	    =>	 $stn['uom_id'],
                                    
                                    				'price'             =>	 $stn['stone_price'],
                                    
                                    				'is_apply_in_lwt'   =>	 $stn['show_in_lwt'],
                                    
                                    				'stone_cal_type'    =>	 $stn['stone_cal_type'],
                                    
                                    				'rate_per_gram'     =>	 $stn['stone_rate'],
                                    												
                                    												
                                    			);
                                    											
                                    			$stoneInsert = $this->$model->insertData($stone_data,'ret_order_item_stones');
                                    
                                    			//print_r($this->db->last_query());exit;
                                    
                                    		}
                                    
                                    }
                                    
                                    $charges_details = json_decode($d['charges_details'],true);
                                    $order_charge_delete_id = $this->$model->deleteData("order_item_id",$d['id_orderdetails'],'ret_order_other_charges');


                                    if(sizeof($charges_details)>0)
                                    {
                                    
                                    	foreach($charges_details as $charge)
                                    	{
                                    
                                    		$charge_data=array(
                                    
                                    			'order_id'          =>  $id,
                                    
                                    			'order_item_id'     => $d['id_orderdetails'],
                                    
                                    			'id_charge'    	    =>	$charge['id_charge'],
                                    
                                    			'amount'       	=>	$charge['value_charge'],
                                    
                                    		);
                                    
                                    		$this->$model->insertData($charge_data,'ret_order_other_charges');
                                    
                                    		//print_r($this->db->last_query());exit;
                                    
                                    	}
                                    
                                    }

							}
							
						}else{
							$str = date('m').''.date('y');
							$order_no = str_pad($str,6,"0",STR_PAD_LEFT).'-'.time();  
							
							if($id > 0){
								$orderDetails['orderno'] = $updData['order']['order_no']."-".$i;
								$orderDetails['id_customerorder'] = $id;
								$orderDetails['id_employee'] = $this->session->userdata('uid');
								$this->db->trans_begin();
								$insOrderDet = $this->$model->insertData($orderDetails,'customerorderdetails');
							    //print_r($this->db->last_query());exit;
							        if($insOrderDet)
        							{
        							       //Image Upload
                		 					$precious_imgs = "";
                		 					$p_ImgData=[];
                		 					$p_ImgData = json_decode($d['order_img']);  
                		 					
                							if(sizeof($p_ImgData) > 0){
                								foreach($p_ImgData as $precious){
                									$imgFile = $this->base64ToFile($precious->src);
                									$_FILES['order_img'][] = $imgFile;
                								}
                							} 
                						//	print_r($_FILES);exit;
                							if(!empty($_FILES)){
                								$img_arr = array();
                							    $folder =  self::IMG_PATH."orders/"; 
                								if (!is_dir($folder)) {  
                									mkdir($folder, 0777, TRUE);
                								}   
                								if(isset($_FILES['order_img'])){ 
                									$order_images = "";
                
                									foreach($_FILES['order_img'] as $file_key => $file_val){
                
                										if($file_val['name'])
                										{
                											// unlink($folder."/".$product['image']); 
                											$img_name =  $insOrderDet."_". mt_rand(100001,999999).".jpg";
                											$path = $folder."/".$img_name; 
                											$result = $this->upload_img('image',$path,$file_val['tmp_name']);
                											if($result){
                												$insImageId = $this->$model->insertData(array('id_orderdetails'=>$insOrderDet,'image'=>$img_name),'customer_order_image'); 
                											}
                										}
                									}
                								}
                							} 
                							
                							
                                    
                                    $stone_details = json_decode($d['stone_details'],true);
                                    $order_stone_delete_id = $this->$model->deleteData("order_item_id",$insOrderDet,'ret_order_item_stones');
                                    if(sizeof($stone_details)>0)
                                    {
                                        foreach($stone_details as $stn)
                                        {
                                        								
                                            $stone_data=array(
                                            'order_id'          =>   $id,
                                            'order_item_id'     =>   $insOrderDet,
                                            'pieces'            =>   $stn['stone_pcs'],
                                            'wt'                =>	 $stn['stone_wt'],
                                            'stone_type'        =>	 $stn['stones_type'],
                                            'stone_id'          =>	 $stn['stone_id'],
                                            'uom_id'      	    =>	 $stn['uom_id'],
                                            'price'             =>	 $stn['stone_price'],
                                            'is_apply_in_lwt'   =>	 $stn['show_in_lwt'],
                                            'stone_cal_type'    =>	 $stn['stone_cal_type'],
                                            'rate_per_gram'     =>	 $stn['stone_rate'],
                                            );
                                            $stoneInsert = $this->$model->insertData($stone_data,'ret_order_item_stones');
                                        }
                                    }
                                    
        							   $charges_details = json_decode($d['charges_details'],true);
                                       $order_charge_delete_id = $this->$model->deleteData("order_item_id",$d['id_orderdetails'],'ret_order_other_charges');
                                        if(sizeof($charges_details)>0)
                                        {
                                        	foreach($charges_details as $charge)
                                        	{
                                        		$charge_data=array(
                                        			'order_id'          =>  $id,
                                        			'order_item_id'     => $insOrderDet,
                                        			'id_charge'    	    =>	$charge['id_charge'],
                                        			'amount'       	=>	$charge['value_charge'],
                                        		);
                                        		$this->$model->insertData($charge_data,'ret_order_other_charges');
                                        		//print_r($this->db->last_query());exit;
                                        	}
                                        }
                                    
        							}
							}
						}
						$i++;
					} 
					
					  
					if($this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
						$this->session->set_flashdata('chit_alert',array('message'=>'Order updated successfully','class'=>'success','title'=>'Update Order')); 
						$return_data=array('status'=>TRUE,'msg'=>'Order Created successfully..','id_customerorder'=>$insOrder,'order_for');
					}
					else
					{ 
					    echo $this->db->last_query();exit;
						$this->db->trans_rollback();						 	
						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Update Order')); 
							$return_data=array('status'=>false,'msg'=>'Unable to proceed the requested process','id_customerorder'=>'');
					}
					echo json_encode($return_data);
					break;
					
					
		    case 'ajax_order_cancel':
                    $this->db->trans_begin();
                    //update order status as cancel
                    $orderid = $_POST['order_id'];
                    $this->$model->updateData(array('order_status' => 6, 'reject_reason' => $_POST['remarks']),'id_customerorder',$orderid,'customerorder');
                    
                    $this->$model->updateData(array('orderstatus' => 6, 'reject_reason' => $_POST['remarks']),'id_customerorder',$orderid,'customerorderdetails');
                    $orderdetails = $this->$model->get_order_details($orderid);
                    foreach($orderdetails as $okey=>$oval){
                        $this->$model->updateData(array('id_orderdetails' => NULL),'id_orderdetails',$oval['id_orderdetails'],'ret_taging');
                    }
                    $order_advance = $this->$model->get_order_total_advance($orderid);
                    
                    $bill_no = $this->$model->bill_no_generate($orderdetails[0]['order_from']);
                    $fin_year       = $this->$model->get_FinancialYear();
                    if($order_advance > 0){
                        $id_customer = $this->$model->get_order_customer_id($orderid);
                        //Update into issue receipt table
                        $receiptinsData = array(
        					'type'			=>2,
        					'amount'		=>$order_advance,
        					'weight'		=> 0,
        					'bill_no'		=> $bill_no,
        					'receipt_type'	=>5,
        					'id_branch'		=> $orderdetails[0]['order_from'],
        					'id_customer'	=>$id_customer,
        					'rate_per_gram'	=>NULL,
        					'rate_calc'		=>1,
        					'receipt_as'	=>1,
        					'store_receipt_as'=>1,
        					'pan_no'		=>  NULL ,
        					'narration'	 	=> 'Order Advance Transfer - ' . $orderid,
        					'created_by' 	=>$this->session->userdata('uid'),
        					'counter_id'    => NULL,
        					'created_on' 	=> date("Y-m-d H:i:s"),
        					'bill_date' 	=> date("Y-m-d H:i:s"),
        					'fin_year_code' => $fin_year['fin_year_code'],
        					'id_customerorder' =>$orderid,
        				);
                        $receiptinsId = $this->$model->insertData($receiptinsData,'ret_issue_receipt');
                        
                    }
                    if( $this->db->trans_status()===TRUE)
				    {
				    	 $this->db->trans_commit();
				    	 echo json_encode(array("stattus" => true, "message" => "Order cancel successfully"));
				    }else{
				         $this->db->trans_rollback();
				         
				         echo json_encode(array("stattus" => false, "message" => "Could not cancel this order"));
				    }
			break;
			
			case 'cancel_order_item':
			    $this->db->trans_begin();
			    $id_orderdetails = $_POST['id_orderdetails'];
			    $this->$model->updateData(array('orderstatus' => 6, 'reject_reason' => $_POST['remarks'],'cancelled_by'=>$this->session->userdata('uid'),'order_cancelled_date'=>date("Y-m-d H:i:s")),'id_orderdetails',$id_orderdetails,'customerorderdetails');
			    if($this->db->trans_status()===TRUE)
			    {
			    	 $this->db->trans_commit();
			    	 echo json_encode(array("status" => true, "message" => "Order cancel successfully"));
			    }else{
			         $this->db->trans_rollback();
			         
			         echo json_encode(array("status" => false, "message" => "Could not cancel this order"));
			    }
			break;
				
				
			case 'getOrderByCus':
						$data = $this->$model->getOrderByCus($_POST['id_customer']);
						echo json_encode($data);
					break;
			case 'update_status':
			
						$data = array('status_karigar' => $status,
						'updateon'	  => date("Y-m-d H:i:s"),
					    'updatedby'      => $this->session->userdata('uid'));
						$model=self::CAT_MODEL;
						$updstatus = $this->$model->updateData($data,'id_karigar',$id,'ret_karigar');
						if($updstatus)
						{
							$this->session->set_flashdata('chit_alert',array('message'=>'User status updated as '.($status==1 ? 'Approved' : 'Not Approved').' successfully.','class'=>'success','title'=>'User Status'));
						}	
						else
						{
							$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'User Status'));
						}	
						redirect('admin_ret_order/order/list');
						break;	
			case 'repair':
					$data['main_content'] = "order/repair_order/list" ;
					$this->load->view('layout/template', $data);
			break;
			
			case 'repair_item_details':
					$data['main_content'] = "order/repair_order/item_details" ;
					$this->load->view('layout/template', $data);
			break;
			
			
			case 'repair_order_list':
				$data=$this->$model->get_repair_orders_list($_POST);
				echo json_encode($data);
			break;
				
			default: 
						$range['from_date']  = $this->input->post('from_date');
						$range['to_date']  = $this->input->post('to_date');
					  	$orders = $this->$model->ajax_getOrders($_POST);	 
					  	$access = $this->admin_settings_model->get_access('admin_ret_order/order/list');
				        $data = array(
				        					'orders' => $orders,
											'access' => $access
				        			 );  
						echo json_encode($data);
		}
	}
	
	function get_img_by_order_id()
    {
        $model="ret_order_model";
        $data = $this->$model->get_order_images($_POST['order_id']);
        echo json_encode($data);
    }
    
	function estimation($type=""){
		switch($type){
			case 'getEstiBySearch':
					$data = $this->ret_order_model->fetchEstiBySearch($_POST['searchTxt']);	 
					echo json_encode($data);
				break;
			case 'getEstiDetails':
					$data = $this->ret_order_model->getEstiDetailsById($_POST['esti_id']);	 
					echo json_encode($data);
				break;
		} 
	  	
	}

	function neworders()
	{
	     $model = "ret_order_model";
	    $data['order']       = $this->$model->empty_rec_order();
	    $data['branch']   =$data['order']['order']['is_ho_branch'];
		$data['main_content'] = "order/neworder/list" ;
		$this->load->view('layout/template', $data);
	}

	function ajax_get_neworder()
	{

		$model = "ret_order_model";
		$from_date=$this->input->post('from_date');
		$to_date=$this->input->post('to_date');
		$id_branch=$this->input->post('id_branch');
		$id_karigar=$this->input->post('id_karigar');
		$customer_order=array();
		$order=$this->$model->get_new_orderlist($from_date,$to_date,$id_branch,$id_karigar);
		
		
		foreach ($order as $data) {
			$order_img= array(explode('#',$data['image']));
			if($order_img[0][0])
			{
				 $img=$this->config->item('base_url').'assets/img/orders/'.$order_img[0][0];
			}
			else{
				$img='';
			}
		    
			 $customer_order[]=array(
			 			'orderno'=>$data['orderno'],
			 			'id_orderdetails'=>$data['id_orderdetails'],
			 			'id_customerorder'=>$data['orderno'],
			 			'size'=>$this->isValueset($data['size']),
			 			'totalitems'=>$this->isValueset($data['totalitems']),
			 			'weight'=>$this->isValueset($data['weight']),
			 			'description'=>$this->isValueset($data['description']),
			 			'reject_reason'=>$this->isValueset($data['reject_reason']),
			 			'cus_name'=>$this->isValueset($data['cus_name']),
			 			'karigar_name'=>$this->isValueset($data['karigar_name']),
			 			'cus_due_date'=>$this->isValueset($data['cus_due_date']),
			 			'id_employee'=>$this->isValueset($data['id_employee']),
			 			'customer_ref_no'=>$this->isValueset($data['customer_ref_no']),
			 			'id_vendor'=>$this->isValueset($data['id_vendor']),
			 			'emp_name'=>$this->isValueset($data['emp_name']),
			 			'branch_name'=>$this->isValueset($data['branch_name']),
			 			'orter_type'=>'Customer Repair',
			 			'id_product'=>$data['id_product'],
			 			'id_category'=>$data['cat_id'],
			 			'order_img'=>$img,
			 			'product_name'=>$data['product_name'],
			 			'sub_design_name'=>$data['sub_design_name'],
			 			'design_name'=>$data['design_name'],
			 			'ortertype'=>$data['ortertype'],
			 			'cus_mobile'=>$data['mobile'],
			 			'hsn_code'=>$data['hsn_code'],
			 			'product_short_code'=>$data['product_short_code'],
			 			'order_date'=>$data['order_date'],
			 			'orderstatus'=>$data['order_status'],
			 			'color'=>$data['color'],
			 			'cus_ord_status'=>$data['cus_ord_status'],
			 			'smith_due_date'=>$data['smith_due_date'],
			 			

			 );
		}
		echo json_encode($customer_order);
	}

	function assign_customer_order()
	{

		$req_status=$this->input->post('req_status');
		$req_data=$this->input->post('req_data');
		$id_vendor=$this->input->post('id_vendor');
		$id_branch=$this->input->post('id_branch');
		$assign_to 		=$this->input->post('assign_to');
		$id_employee 	=$this->input->post('id_employee');
		$model = "ret_order_model";
		$set_model="admin_settings_model";
		$id_customer_orders='';
    if($req_status==1)
    {
		foreach ($req_data as $data) 
		{
			$assigned = TRUE;
			$customer_order_details=$this->$model->get_customerorder_details($data['id_orderdetails']);
		    $smith_due_dt=date_create($data['smith_due_dt']);
			$this->db->trans_begin();
		    $upd_data=array('assign_to'=>$assign_to,'id_karigar'=>($assign_to ==1 ? $id_vendor:NULL),'emp_id'=>($assign_to ==2 ? $id_employee:NULL),'orderstatus'=>3,'smith_due_date'=>date_format($smith_due_dt,"Y-m-d"),'smith_remainder_date'=>date("Y-m-d", strtotime('+2 days'))); // Work in Progress
			$this->$model->updateData($upd_data,'id_orderdetails',$customer_order_details['id_orderdetails'],'customerorderdetails');
		}
		if($this->db->trans_status()===TRUE)
		{
    		$this->db->trans_commit();
    		$response_data=array('status'=>true,'msg'=>'Karigar Assinged Successfully','id_customerorder'=>$id_customer_orders);
    		$this->session->set_flashdata('chit_alert',array('message'=>'Karigar Assinged Successfully','class'=>'success','title'=>'Assign Karigar'));
		}
		else
		{ 
    		$this->db->trans_rollback();
    		$response_data=array('status'=>false,'msg'=>'Unable to Procees Your Request');	
    		$this->session->set_flashdata('chit_alert',array('message'=>'Unable to Procees Your Request','class'=>'danger','title'=>'Assign Karigar'));					 	
		}
	}
	else
	{
		foreach ($req_data as $data) 
		{
			$assigned = TRUE;
			$customer_order_details=$this->$model->get_customerorder_details($data['id_orderdetails']);
			$assign_order=$this->$model->get_joborder_details($customer_order_details['id_orderdetails']);
			$this->db->trans_begin();
		
					$upd_data=array('orderstatus'=>6);
					$this->$model->updateData($upd_data,'id_orderdetails',$customer_order_details['id_orderdetails'],'customerorderdetails');
					
					$response_data=array('status'=>true,'msg'=>'New order Rejected Successfully');
					$this->session->set_flashdata('chit_alert',array('message'=>'New order Rejected Successfully','class'=>'success','title'=>'New Order'));

		}
		if($upd_data)
		{
		$this->db->trans_commit();
		$response_data=array('status'=>true,'msg'=>'New order Rejected Successfully');
		$this->session->set_flashdata('chit_alert',array('message'=>'New order Rejected Successfully','class'=>'success','title'=>'New Order'));
		}
		else
		{ 
		$this->db->trans_rollback();
		$response_data=array('status'=>false,'msg'=>'Already Assigned to Karigar');	
						$this->session->set_flashdata('chit_alert',array('message'=>'Already Assigned to Karigar','class'=>'danger','title'=>'New Order'));				 	
		}
	}
	    echo json_encode($response_data);
}

function get_karigar_acknowladgement()
{
	$model = "ret_order_model";
    $set_model="admin_settings_model";
     $service = $this->$set_model->get_service_by_code('KAR_ALOC');
     $id_customer_orders=$_GET['id_order'];
    $orders=explode(',',$id_customer_orders);
    foreach($orders as $items)
    {
        	$data['order']=$this->$model->get_karigar_orders($items);
        	//echo "<pre>";print_r($data);exit;
        	$this->load->helper(array('dompdf', 'file'));
        	$dompdf = new DOMPDF();
            $html = $this->load->view('order/stock_order/print/vendor_ack', $data,true);
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
}

function get_all_branch()
{
	$model="ret_order_model";
	$data=$this->$model->get_all_branch();
	echo json_encode($data);
}

function get_ordersby_id($id)
{
	$model="ret_order_model";
	$data=$this->$model->get_orderdetails_by_id($id);
	$order_img= array(explode('#',$data['image']));
	 $imges=array();	
	foreach ($order_img[0] as $img) {

			if($img)
			{
				 $imges[]=$this->config->item('base_url').'assets/img/orders/'.$img;
			}
	}
	
		 $customer_order=array(
			 			'orderno'=>$data['orderno'],
			 			'id_orderdetails'=>$data['id_orderdetails'],
			 			'id_customerorder'=>$data['orderno'],
			 			'size'=>$this->isValueset($data['size']),
			 			'totalitems'=>$this->isValueset($data['totalitems']),
			 			'weight'=>$this->isValueset($data['weight']),
			 			'description'=>$this->isValueset($data['description']),
			 			'reject_reason'=>$this->isValueset($data['reject_reason']),
			 			'cus_name'=>$this->isValueset($data['cus_name']),
			 			'karigar_name'=>$this->isValueset($data['karigar_name']),
			 			'cus_due_date'=>$this->isValueset($data['cus_due_date']),
			 			'id_employee'=>$this->isValueset($data['id_employee']),
			 			'customer_ref_no'=>$this->isValueset($data['customer_ref_no']),
			 			'id_vendor'=>$this->isValueset($data['id_vendor']),
			 			'emp_name'=>$this->isValueset($data['emp_name']),
			 			'branch_name'=>$this->isValueset($data['branch_name']),
			 			'orter_type'=>($data['ortertype']==1 ? 'Catalog Order' : ($data['ortertype']==2) ? 'Customer Order' : ($data['ortertype']==3 ? 'Repair Order':'') ),
			 			'id_product'=>$data['id_product'],
			 			'id_category'=>$data['cat_id'],
			 			'product_name'=>$data['product_name'],
			 			'ortertype'=>$data['ortertype'],
			 			'mobile'=>$data['mobile'],
			 			'hsn_code'=>$data['hsn_code'],
			 			'product_short_code'=>$data['product_short_code'],
			 			'order_date'=>$data['order_date'],
			 			'order_image'=>$imges,
			 			'orderstatus'=>$data['order_status'],
			 			'color'=>$data['color'],
			 			'purity'=>$data['purity'],
			 			

			 );
	echo json_encode($customer_order);
}

function updatereject_reason()
{
    $model="ret_order_model";
	$upd_data['reject_reason']=$this->input->post('reject_reason');
	$id_orderdetails=$this->input->post('id_orderdetails');
	$this->db->trans_begin();
	if($upd_data['reject_reason']!='')
	{
		$upd_data['orderstatus']=8;
		$customer_order_details=$this->$model->get_customerorder_details($id_orderdetails);
		$status=$this->$model->updateData($upd_data,'id_orderdetails',$id_orderdetails,'customerorderdetails');
	}
	else
	{
		$status=false;
		
	}
	if($status==true)
	{
	$this->db->trans_commit();
	$this->session->set_flashdata('chit_alert',array('message'=>'New order Rejected  Successfully','class'=>'success','title'=>'New Order'));
	}
	else
	{ 
	$this->db->trans_rollback();
	$this->session->set_flashdata('chit_alert',array('message'=>'Unable to Proceed Your Request','class'=>'danger','title'=>'New Order'));				 	
	}
	echo true;
}
	
function get_order_images($orders)
	{
			$order_img= array(explode('#',$orders['image']));
			$images=array();
			foreach ($order_img[0] as $img) 
			{
				if($img)
				{
					$images[]=array(
					'src'=>$this->config->item('base_url').'assets/img/orders/'.$img,
					'img_name'=>$img
					);
				}				
			}
			return $images;
	}

function upload_orderimg()
{
	$imgpath='assets/img/orders/';
	$model='ret_order_model';
	$file_name='';
	$name='';
	$data=array();
	foreach($_FILES['file']['name'] as $file_key => $files)
	{

	if (!is_dir($imgpath)) {
	mkdir($imgpath, 0777, TRUE);
	}
	if($files)
	{
	$name=time().$files;
	$file_name.=time().$files.'#';
	}
	$img=$_FILES['file']['tmp_name'][$file_key]; 
	$imgpath='assets/img/orders/'.$name;
	$result = $this->upload_img('orderimg',$imgpath,$img);
	}
	$data=array('msg'=>true,'name'=>$file_name);
	echo json_encode($data);
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
		  imagejpeg($tmp, $dst);
		 
		  return true;
	}

	function remove_img()
	{
		$model="ret_order_model";
		$file=$this->input->post('file');
		$id=$this->input->post('id');
		$images=$this->$model->get_orderdetails_by_id($id);
		$imgs = explode('#',$images['image']);
		unlink(SELF::IMG_PATH.'orders/'.$file);
		$totImgs = sizeof($imgs);
			foreach($imgs as $k => $img){
				
				if($img == $file) { // img1.jpg#img1.jpg
					if($k == $totImgs-1)  
						$newImgValue = str_replace($file, '', $images['image']);
					else
						$newImgValue = str_replace($file."#", '', $images['image']);
				}
			}
			
			$status=$this->$model->updateData(array('image' =>(!empty($newImgValue)) ? $newImgValue:NULL),'id_orderdetails',$id,'customerorderdetails');
			//print_r($this->db->last_query());exit;
			if($status){
			echo "Picture removed successfully";
		}
	
	}
	
	
	function get_weight_range()
    {
    	$model = "ret_order_model";
    	$data=$this->$model->get_weight_range($_POST['searchTxt']);
    	echo json_encode($data);
    }
    
    function get_product_size()
    {
    	$model = "ret_order_model";
    	$data=$this->$model->get_product_size($_POST['id_product']);
    	echo json_encode($data);
    }
    
    
    public function vendor_acknowladgement($id)
	{
		$set_model = "admin_settings_model";
		$model = "ret_order_model";
		$data['comp_details'] = $this->$set_model->get_company();
	    $data['order']=$this->$model->get_karigar_orders($id);
		$this->load->helper(array('dompdf', 'file'));
	        $dompdf = new DOMPDF();
			$html = $this->load->view('order/stock_order/print/vendor_ack', $data,true);
			$dompdf->load_html($html);
			$dompdf->set_paper('A4', "portriat" );
			$dompdf->render();
			$dompdf->stream("VendorAck.pdf",array('Attachment'=>0));
	}
	
	
	//Order Images and Description
		public function get_img_by_id()
		{
			$response_data = [];
			$id_orderdetails = $this->input->post('id_orderdetails');
			$model = "ret_order_model";
			$data=$this->$model->get_img_by_id($id_orderdetails);
			$image=explode("##",$data['image']);
			
			foreach($image as $val)
			{
				if($val!='')
				{
					$response_data[]=array(
						'src'=>$this->config->item('base_url').'assets/img/order/customer_order/'.$id_orderdetails.'/'.$val,
						'id_orderdetails'=>$id_orderdetails,
						'img_name'=>$val,
					);
				}	
			}
			echo json_encode($response_data);
		}
		public function update_order_image()
		{
			$response_data=array();
			$img_array = [];
			$model = "ret_order_model";
			$id_orderdetails = $this->input->post('id_orderdetails');
			$image_data = $this->input->post('image');
			$this->db->trans_begin();
			$img_details=$this->$model->get_img_by_id($id_orderdetails);
			foreach($image_data as $image)
			{
				$folder =  self::IMG_PATH."order/customer_order/".$id_orderdetails; 
				if (!is_dir($folder)) {  
				mkdir($folder, 0777, TRUE);
				} 
				$image_parts = explode(";base64,",$image);
				$image_type_aux = explode("image/", $image_parts[0]);
				$image_type = $image_type_aux[1];
				$image_base64 = base64_decode($image_parts[1]);
				$file = $folder.'/'.mt_rand(120,1230).".jpg";
				$fileName = basename($file, ".jpg");
				file_put_contents($file, $image_base64);
				$name = "##".$fileName.'.jpg';
				array_push($img_array, $name);
			 }
			$img_data = implode("",$img_array);
			$data['image']= $img_details['image'].$img_data;
		
			
			$status=$this->$model->updateData($data,'id_orderdetails',$id_orderdetails,'customerorderdetails');
			if($this->db->trans_status()===TRUE)
			{
				$this->db->trans_commit();
				$response_data=array('status'=>TRUE,'message'=>'Image Uploaded Successfully');
			}else{
				$this->db->trans_rollback();		
				$response_data=array('status'=>FALSE,'message'=>'Unable To Proceed Your Request');				 
			}
			echo json_encode($response_data);		
		}
		public function update_and_retrive_order_image()
		{
			$img_array = [];
			$id_orderdetails = $this->input->post('id_orderdetails');
			$retrive_image = $this->input->post('retrive_image');
			$image_data = $this->input->post('new_image');
			foreach($retrive_image as $value)
			{
				$add_hash = "#".$value;
				array_push($img_array, $add_hash);
			}
			foreach($image_data as $image)
			{
				if($addData['order']['order_for']==1)
				
					$folder =  self::IMG_PATH."customer_order/"; 
					if (!is_dir($folder)) {  
					mkdir($folder, 0777, TRUE);
					} 
   
				   $image_parts = explode(";base64,",$image);
   
				   $image_type_aux = explode("image/", $image_parts[0]);
   
				   $image_type = $image_type_aux[1];
   
				   $image_base64 = base64_decode($image_parts[1]);
   
				   $file = mt_rand(120,1230).".jpg";
				   
				   $fileName = basename($file, ".jpg");
   
				   file_put_contents($file, $image_base64);
				   
				   $name = "##".$fileName;
				   
				   array_push($img_array, $name);
			}
				   $img_data = implode("",$img_array);
				   $data['image'] = $img_data;
				   $model = "ret_order_model";
				   $status=$this->$model->updateData($data,'id_orderdetails',$id_orderdetails,'customerorderdetails');
				   echo json_encode($status);		
		}
		public function insert_retrive_img()
		{
			$id_orderdetails = $this->input->post('id_orderdetails');
			echo json_encode(true);
		}
		public function delete_order_img()
		{
			$response_data=array();
			$model= "ret_order_model";
			$id_orderdetails = $this->input->post('id_orderdetails');
			$delete_image = $this->input->post('image');
			$data = $this->$model->get_img_by_id($id_orderdetails);
			$image=explode("##",$data['image']);
			$updData['image']=NULL;
			foreach($image as $val)
			{
				if($val!='')
				{
					if($val!=$delete_image)
					{
						$updData['image'].='##'.$val;
					}
				}
			}
			
			$this->db->trans_begin();
			$status=$this->$model->updateData($updData,'id_orderdetails',$id_orderdetails,'customerorderdetails');
			unlink(SELF::IMG_PATH.'order/customer_order/'.$id_orderdetails.'/'.$delete_image);
				if($this->db->trans_status()===TRUE)
				{
					$this->db->trans_commit();
					$response_data=array('status'=>TRUE,'message'=>'Image Deleted Successfully');
				}else{
					$this->db->trans_rollback();		
					$response_data=array('status'=>FALSE,'message'=>'Unable To Proceed Your Request');				 
				}
				
			
			echo json_encode($response_data);
		}

		
		public function update_order_des()
		{
			$id_orderdetails = $this->input->post('id_orderdetails');
			$description = $this->input->post('description');
			$model = "ret_order_model";
			$data_des['description'] = $description;
			$status=$this->$model->updateData($data_des,'id_orderdetails',$id_orderdetails,'customerorderdetails');
		    if($status)
		    {
		        $responseData=array('status'=>TRUE,'message'=>'Description Added Successfully..');
		    }else{
		        $responseData=array('status'=>FALSE,'message'=>'Description Added Successfully..');

		    }
		    echo json_encode($responseData);
		}
		public function get_dec_by_id()
		{
			$id_orderdetails = $this->input->post('id_orderdetails');
			$model = "ret_order_model";
			$data=$this->$model->get_dec_by_id($id_orderdetails);
			echo json_encode($data);
		}
	   //Order Images and Description
	   
	   //SUB DESIGNS
	  function get_ActiveSubDesingns()
      {
    	     $model="ret_order_model";
    	     $data=$this->$model->getSearchSubDesign($_POST);
    	     echo json_encode($data);
      }
      
      function get_ActiveDesingns()
      {
    	     $model="ret_order_model";
    	     $data=$this->$model->getSearchDesign($_POST);
    	     echo json_encode($data);
      }
	   //SUB DESIGNS
	  
	public function active_cat_product_list()
	{
	    $model = "ret_order_model";
		$data = $this->$model->get_active_cat_product_list();
		echo json_encode($data);
	}
	
	public function getIssueTaggingBySearch(){

		$model = "ret_order_model";

		$data = $this->$model->getIssueTaggingBySearch($_POST['searchTxt'], $_POST['searchField'], $_POST['id_branch']);	  

		echo json_encode($data);

	}
	
	public function get_tag_scan_details(){
		$model = "ret_order_model";
		$data = $this->$model->get_tag_scan_details($_POST);	  
		echo json_encode($data);
	}
	
	
	function repair_order($type,$id="")
	{
	    $model = "ret_order_model";
		$this->load->model('ret_catalog_model');
		switch($type)
		{
		
			case 'list':
					$data['main_content'] = "order/repair_order/list" ;
					$this->load->view('layout/template', $data);
			break;
			
			case 'add':
					$data['main_content'] = "order/repair_order/form" ;
					$this->load->view('layout/template', $data);
			break;
			
			case 'repair_order_status':
			    $data['categories'] = $this->ret_order_model->getActiveCategories(); 
				$data['main_content'] = "order/repair_order/order_status" ;
				$this->load->view('layout/template', $data);
			break;
			case 'repair_order_list':
				$data=$this->$model->get_repair_orders_list($_POST);
				echo json_encode($data);
			break;
			
			case 'save':
			    $addData=$_POST['order'];
			    
			    /*echo "<pre>";
			    print_r($_POST);
			    echo "</pre>"; exit;*/
			    
		        $fin_year       = $this->$model->get_FinancialYear();
				$order_from     = (!empty($addData['order_from']) ? $addData['order_from'] :NULL );
				$order_no       = $this->$model->generateOrderNo($order_from, $addData['order_type']);
				$dCData         = $this->admin_settings_model->getBranchDayClosingData($addData['order_from']);
				$order_datetime = ($dCData['entry_date'] == date("Y-m-d") ? date("Y-m-d H:i:s") : $dCData['entry_date']);
				
 				$order = array( 
 				    'fin_year_code'     => $fin_year['fin_year_code'],
	 				'order_type'		=> $addData['order_type'],
	 				'order_from'		=> $order_from,
	 				'order_no'          => $order_no,
	 				'order_for'			=> ($addData['order_type']==3 ? 2 :1),
	 				'order_date'		=> $order_datetime,
	 				'order_to'			=> (!empty($addData['order_to']) ? $addData['order_to']: NULL),
					'createdon'         => date("Y-m-d H:i:s"),
					'order_taken_by'    => $this->session->userdata('uid')
				);
				$this->db->trans_begin();
				$insOrder = $this->$model->insertData($order,'customerorder');
					
			    if($insOrder)
			    {
			        $orderItems = $_POST['order_item'];
			        $i=1;
			        $order_pcs=0;
			        $order_approx_wt=0;
			        foreach($orderItems['product'] as $key =>$val)
			        {
			            $order_pcs += $orderItems['piece'][$key];
			            $order_approx_wt+=$orderItems['weight'][$key];
			            $orderDetails = array( 
			 				'orderno'			=> $order_no."-".$i,
			 				'ortertype'		    => $addData['order_type'],
			 				'id_product'		=> $orderItems['product'][$key],
			 				'design_no'		    => (isset($orderItems['id_design'][$key]) ? $orderItems['id_design'][$key]:NULL),
			 				'id_sub_design'		=> (isset($orderItems['id_sub_design'][$key]) ? $orderItems['id_sub_design'][$key]:NULL),
			 				'pure_wt'		    => (isset($orderItems['purewt'][$key]) ? $orderItems['purewt'][$key]:0),
			 				'tag_id'		    => (isset($orderItems['tag_id'][$key]) ? $orderItems['tag_id'][$key]:NULL),
			 				'id_repair_master'	=> (isset($orderItems['repair'][$key]) ? $orderItems['repair'][$key]:NULL),
			 				'totalitems'		=> $orderItems['piece'][$key],
			 				'weight'			=> $orderItems['weight'][$key],
			 				'description'		=> ($orderItems['description'][$key]!='' ? $orderItems['description'][$key]:NULL),
			 				'order_date'		=> $order_datetime,
			 				'current_branch'	=> $order_from,
			 				'cus_due_date'		=> ($orderItems['cus_due_days'][$key]!='' ?date('Y-m-d',(strtotime('+'.$orderItems['cus_due_days'][$key].' day'))) :NULL),
							'id_employee'       => $this->session->userdata('uid'),
						);
						
						if($insOrder > 0){
							$orderDetails['id_customerorder'] = $insOrder;
							$insOrderDet = $this->$model->insertData($orderDetails,'customerorderdetails');
							//print_r($this->db->last_query());exit;
							if($insOrderDet && $addData['order_type']==4)
							{
							    $this->$model->updateData(array('tag_status'=>8),'tag_id',$orderItems['tag_id'][$key],'ret_taging');
							    
							    $tag_log=array(
                                            'tag_id'	  =>$orderItems['tag_id'][$key],
                                            'date'		  =>$order_datetime,
                                            'status'	  =>8,
                                            'from_branch' =>$addData['order_from'],
                                            'to_branch'	  =>NULL,
                                            'created_on'  =>date("Y-m-d H:i:s"),
                                            'created_by'  =>$this->session->userdata('uid'),
                                            );
                                            $this->$model->insertData($tag_log,'ret_taging_status_log');
							}
							//echo"<pre>"; print_r($orderItems['order_img'][$key]);exit;
							//Image Upload
		 					$precious_imgs = "";
		 					$p_ImgData=[];
		 					$p_ImgData = json_decode($orderItems['order_img'][$key]);  
		 					
							if(sizeof($p_ImgData) > 0){
								foreach($p_ImgData as $precious){
									$imgFile = $this->base64ToFile($precious->src);
									$_FILES['order_img'][] = $imgFile;
								}
							} 
						//	print_r($_FILES);exit;
							if(!empty($_FILES)){
								$img_arr = array();
							    $folder =  self::IMG_PATH."orders/"; 
								if (!is_dir($folder)) {  
									mkdir($folder, 0777, TRUE);
								}   
								if(isset($_FILES['order_img'])){ 
									$order_images = "";

									foreach($_FILES['order_img'] as $file_key => $file_val){

										if($file_val['name'])
										{
											// unlink($folder."/".$product['image']); 
											$img_name =  $insOrderDet."_". mt_rand(100001,999999).".jpg";
											$path = $folder."/".$img_name; 
											$result = $this->upload_img('image',$path,$file_val['tmp_name']);
											if($result){
												$insImageId = $this->$model->insertData(array('id_orderdetails'=>$insOrderDet,'image'=>$img_name),'customer_order_image'); 
											}
										}
									}
								}
							} 
                							
						}
						$i++;
			        }
			        
			        $this->$model->updateData(array('order_pcs'=> $order_pcs, 'order_approx_wt' => $order_approx_wt),'id_customerorder',$insOrder,'customerorder');
			        //echo $this->db->last_query();exit;
			    }
			    
			    if($this->db->trans_status()===TRUE)
				{
					$this->db->trans_commit();
					$response_data=array('status'=>TRUE,'message'=>'Order Created Successfully','id_customerorder'=>$insOrder);
					$this->session->set_flashdata('chit_alert',array('message'=>'Order Created Successfully','class'=>'success','title'=>'New Order'));
				}else{
					$this->db->trans_rollback();		
					$response_data=array('status'=>FALSE,'message'=>'Unable To Proceed Your Request');			
					$this->session->set_flashdata('chit_alert',array('message'=>'Unable To Proceed Your Request','class'=>'danger','title'=>'New Order'));
				}
				
				echo json_encode($response_data);
				
			break;
				
			default: 
						$orders = $this->$model->ajax_getRepairOrders();	 
					  	$access = $this->admin_settings_model->get_access('admin_ret_order/repair_order/list');
				        $data = array(
				        					'orders' => $orders,
											'access' => $access
				        			 );  
						echo json_encode($data);
		}
	}
	
	function repair_acknowledgement($repair_id)	
    {		
        $model = "ret_order_model";		
        $set_model = "admin_settings_model";		
        $data['comp_details'] = $this->$set_model->get_company();	
        $data['repair']=$this->$model->repair_detail($repair_id);		
        $data['repair_details']=$this->$model->get_repair_details($repair_id);				
        //echo "<pre>";print_r($data);exit;
        $this->load->helper(array('dompdf', 'file'));	        
        $dompdf = new DOMPDF();			
        $html = $this->load->view('order/repair_order/repair_print', $data,true);			
        $dompdf->load_html($html);					
        $dompdf->set_paper('A4', "portriat" );		
        $dompdf->render();		
        $dompdf->stream("Receipt.pdf",array('Attachment'=>0));
    }	
	
	
	function repair_order_status()
	{

		$req_data 		=$this->input->post('req_data');
		$model 			= "ret_order_model";
		foreach($req_data as $order) 
		{
			$updData=array(
			"completed_weight"=> $order['completed_weight'],
			"rate"  		 =>  $order['final_amount'],
			"orderstatus" 	 =>  4,
			);
			$this->db->trans_begin();
			$status=$this->$model->updateData($updData,'id_orderdetails',$order['id_orderdetails'],'customerorderdetails');
		}
		if($this->db->trans_status()===TRUE)
		{
    		$this->db->trans_commit();
    		$response_data=array('status'=>true,'msg'=>'Order Status Updated Successfully');
    		$this->session->set_flashdata('chit_alert',array('message'=>'Order Status Updated Successfully','class'=>'success','title'=>'Order Status'));
		}
		else
		{ 
    		$this->db->trans_rollback();
    		$response_data=array('status'=>false,'msg'=>'Unable to Procees Your Request');	
    		$this->session->set_flashdata('chit_alert',array('message'=>'Unable to Procees Your Request','class'=>'danger','title'=>'Order Status'));					 	
		}
	    echo json_encode($response_data);
    }
    
    function get_repair_damage_master()
    {
        $model = "ret_order_model";
        $data=$this->$model->get_repair_damage_master();
        echo json_encode($data);
    }
    
    
      //Cart Items
	   function cart($type="")
	   {
	        $model = "ret_order_model";
    		switch($type){
    			case 'list':
    					$data['main_content'] = "order/cart_list" ;
    					$this->load->view('layout/template', $data);
    				break;
    				
    			case 'cart_status':
					$data['main_content'] = "order/cart_status" ;
					$this->load->view('layout/template', $data);
				break;
    				
    			case "order_place":
    			    //echo "<pre>";print_r($_POST);exit;
			        $response_data=array();
					$addData = $_POST; 
					$this->db->trans_begin();
					$i=1;
					$row_karigar='';
					$service_id=28;
					$fin_year       = $this->$model->get_FinancialYear();
					if($_POST['status']==1)
					{
					    $order_pcs=0;
						$weight_range_value=0;
					    foreach($addData['req_data'] as $d)
					    {
					        $order_pcs+=$d['totalitems'];
							$weight_range_value+=$d['weight_range_value'];
					    }
					    
					    $pur_no = $this->$model->generatePurNo();
								
						$order = array( 
             			    'fin_year_code'     => $fin_year['fin_year_code'],
             				'pur_no'            => $pur_no,
             				'order_status'		=> 3,
             				'order_type'		=> 1,
             				'order_pcs'			=> $order_pcs,
             				'order_approx_wt'	=> number_format(($order_pcs*$weight_range_value),3,'.',''),
             				'order_for'			=> 1,
             				'id_karigar'		=> $addData['id_karigar'],
             				'order_date'		=> date("Y-m-d H:i:s"),
            				'createdon'         => date("Y-m-d H:i:s"),
            				'order_taken_by'    => $this->session->userdata('uid')
            			);
            			$this->db->trans_begin();
                        $insOrder = $this->$model->insertData($order,'customerorder');	
						foreach($addData['req_data'] as $d)
						{
						    if($insOrder!='')
							{
							    $smith_due_date        = (!empty($addData['smith_due_dt']) ?date('Y-m-d',strtotime(str_replace("/","-",$addData['smith_due_dt']))):NULL ); 
                                
								$orderDetails = array( 
								'id_customerorder'	=>$insOrder,
								'orderstatus'		=>3,
								'id_weight_range'	=> (!empty($d['id_wt_range']) ? $d['id_wt_range'] :NULL ),
								'id_product'		=> (!empty($d['id_product']) ? $d['id_product'] :NULL ),
								'design_no'			=> (!empty($d['design_no']) ? $d['design_no'] :NULL ),
								'id_sub_design'		=> (!empty($d['id_sub_design']) ? $d['id_sub_design'] :NULL ),
								'totalitems'		=> (!empty($d['totalitems']) ? $d['totalitems'] :NULL ),
								'size'				=> (!empty($d['size']) ? $d['size'] :NULL ),
								'smith_due_date'	=> (!empty($smith_due_date) ? $smith_due_date :NULL ),
								'order_date'		=> date("Y-m-d H:i:s"),
								'id_employee'       => $this->session->userdata('uid'),
								);
								$insOrderdet = $this->$model->insertData($orderDetails,'customerorderdetails');
								
								if($insOrderdet)
								{
									$updstatus = $this->$model->updateData(array('orderstatus'=>1,'id_orderdetails'=>$insOrderdet),'id_cart_order',$d['id_cart_order'],'order_cart');
								}
								$row_karigar=$addData['id_karigar'];
							}						
						}

						if($insOrder)
						{
						    $res_msg='Order Placed Successfully..';
							$response_data=array('status'=>TRUE,'msg'=>$res_msg);
                            /*foreach($orders as $id_cus_order)
                            {
                                
						    }*/
						}
					}
					else
					{
						foreach($addData['req_data'] as $d)
						{
							$updata=$this->$model->updateData(array('orderstatus'=>2,'updated_by'=>$this->session->userdata('uid'),'date_upd'=>date("Y-m-d H:i:s"),
								'reject_reason'=>'Order Rejected by '.$this->session->userdata('uid').''),'id_cart_order',$d['id_cart_order'],'order_cart');
						}
						if($updata)
						{
						    $res_msg='Order Rejected Successfully..';
							$response_data=array('status'=>TRUE,'msg'=>$res_msg,'insIds'=>'');
						}
					}
					if($this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
						$this->session->set_flashdata('chit_alert', array('message' => $res_msg,'class' => 'success','title'=>'Order'));	 
					}
					else
					{ 
					    //echo $this->db->_error_message();
					    //echo $this->db->last_query();exit;
						$this->db->trans_rollback();
						$this->session->set_flashdata('chit_alert', array('message' => 'Unable to Proceed Your Request..','class' => 'danger','title'=>'Order'));
						$response_data=array('status'=>FALSE,'msg'=>'Unable to Proceed Your Request..');
					}
			        echo json_encode($response_data);
				break;
				
				case 'order_status':
    				$data=$this->$model->getCartDetails();
    				echo json_encode($data);
    			break;
					
    			default: 
                    $orders = $this->$model->ajax_getCartOrders($_POST);	 
                    $access = $this->admin_settings_model->get_access('admin_ret_order/cart/list');
                    $data = array(
                    'orders' => $orders,
                    'access' => $access
                    );  
                    echo json_encode($data);
    		} 
	 }
	
	   function add_to_cart()
	   {
            $model = "ret_order_model";
            $reqdata   = $this->input->post('req_data');
            $id_branch = $this->input->post('id_branch');
            $this->db->trans_begin();
            foreach($reqdata as $items)
            {
                $data = array(
                'id_branch'  	=> $id_branch,
                'id_product'  	=> $items['product_id'],
                'design_no'  	=> $items['design_id'],
                'id_sub_design' => $items['id_sub_design'],
                'size'  	    => $items['id_size'],
                'id_wt_range'   => $items['id_wt_range'],
                'totalitems'  	=> $items['shortage_pcs'],
                'created_on' 	=> date("Y-m-d H:i:s"),
                'created_by' 	=> $this->session->userdata('uid')
                );
                $insOrder = $this->$model->insertData($data,'order_cart');
            }
        	if($this->db->trans_status()===TRUE)
			{
				$this->db->trans_commit();
				$response_data=array('status'=>TRUE,'message'=>'Item Added to Cart Successfully..');
			}else{
				$this->db->trans_rollback();		
				$response_data=array('status'=>FALSE,'message'=>'Unable To Proceed Your Request');				 
			}
			echo json_encode($response_data);
	   }
	   
	    function karigar_search()
    	{
    		$model = "ret_order_model";
    		$data =$this->$model->karigar_search($_POST['searchTxt']);
    		echo json_encode($data);
    	}
	
	   //Cart Items
	   
	    function repair_order_acknowladgement($id="")	
        {		
            $insOrder=$id;
            $model = "ret_order_model";		
            $set_model = "admin_settings_model";
            $billmodel="ret_billing_model";
            $data['create_repair_details']=$this->$model->repair_order_acknowladgement($insOrder);
            $data['comp_details'] = $this->$model->getCompanyDetails($data['create_repair_details'][0]['order_from']);	
            $data['metal_rate']=$this->$billmodel->get_branchwise_rate($data['create_repair_details']['order_from']);
    	   // echo "<pre>" ;print_r($data);exit;				
            $this->load->helper(array('dompdf', 'file'));	        
            $dompdf = new DOMPDF();			
            $html = $this->load->view('order/repair_order/repair_print', $data,true);			
            $dompdf->load_html($html);					
            $dompdf->set_paper('A4', "portriat" );		
            $dompdf->render();		
            $dompdf->stream("Receipt.pdf",array('Attachment'=>0));
        }		
        
        
        function get_metal()
        {
            $model = "ret_order_model";
            $data=$this->$model->get_active_metal();
            echo json_encode($data);
        }
        
        
        function get_cus_product()
        {
            $model = "ret_order_model";
            $id_metal   = $this->input->post('id_metal');
            $data=$this->$model->get_cus_product($id_metal);
            echo json_encode($data);
        }
        
        function get_ActiveProducts()
       {
            $model = "ret_order_model";
            $data=$this->$model->get_ActiveProducts($_POST);
            echo json_encode($data);
       }
    
       function get_active_design_products()
       {
         
            $model = "ret_order_model";
            $data=$this->$model->get_active_design_products($_POST);
            echo json_encode($data);
       }
        
	
	    
	public function customer_order_acknowladgement($id)
	{
		$set_model = "admin_settings_model";
		$model = "ret_order_model";
		$billmodel="ret_billing_model";
		
		$data['cus_details'] =$this->$model->get_order_cus_details($id);
	    $data['order']=$this->$model->get_customer_orders($id);
	    $data['comp_details']=$this->$model->getCompanyDetails($data['cus_details']['id_branch']);
	    $data['metal_rate']=$this->$billmodel->get_branchwise_rate($data['cus_details']['id_branch']);
		//echo "<pre>"; print_r($data);exit;
		$this->load->helper(array('dompdf', 'file'));
	        $dompdf = new DOMPDF();
			$html = $this->load->view('order/cus_order_print', $data,true);
			$dompdf->load_html($html);
			$dompdf->set_paper('A4', "portriat" );
			$dompdf->render();
			$dompdf->stream("VendorAck.pdf",array('Attachment'=>0));
	}
	
	
	
	function update_repair_order_other_details()
    {
        $req_data 		 = $this->input->post('order_item');
		$model 			 = "ret_order_model";
		$id_orderdetails = $_POST['id_orderdetails'];
		$service_charge = $_POST['service_charge'];
		
		$this->db->trans_begin();
		$this->$model->deleteData('id_orderdetails',$id_orderdetails,'customer_order_other_details');
		$orderDetails = $this->$model->get_customerorder_details($id_orderdetails);
		foreach($req_data['id_ret_category'] as $key =>$order) 
		{
		    
		    $weight+=$req_data['gross_wt'][$key];
		    
		    $metalDetails=array(
		                       'id_orderdetails'=>$id_orderdetails,
		                       'id_ret_category'=>$req_data['id_ret_category'][$key],
		                       'id_purity'      =>$req_data['id_purity'][$key],
		                       'id_product'     =>$req_data['id_product'][$key],
		                       'gross_wt'       =>$req_data['gross_wt'][$key],
		                       'less_wt'        =>$req_data['less_wt'][$key],
		                       'net_wt'         =>$req_data['net_wt'][$key],
		                       'wast_percent'   =>$req_data['wast_per'][$key],
		                       'mc_value'       =>$req_data['mc_value'][$key],
		                       'mc_type'        =>$req_data['mc_type'][$key],
		                       );
		     
		    $insId = $this->$model->insertData($metalDetails,'customer_order_other_details');
		    
		    if($req_data['stone_details'][$key])
			{
				$stone_details=json_decode($req_data['stone_details'][$key],true);
				foreach($stone_details as $stone)
				{
					$stone_data=array(
									'id_other_details'      =>$insId,
									'pieces'                =>$stone['stone_pcs'],
									'wt'                    =>$stone['stone_wt'],
									'stone_id'              =>$stone['stone_id'],
									'uom_id'                =>$stone['stone_uom_id'],
									'amount'                =>$stone['stone_price'],
									'rate_per_gram'         =>$stone['stone_rate'],
									'is_apply_in_lwt'       =>$stone['show_in_lwt'],
									'stone_cal_type'        =>$stone['stone_cal_type'],
									);
					$stoneInsert = $this->$model->insertData($stone_data,'customer_order_stone_details');
				}										
			}
							
		}
		$status=$this->$model->updateData(array("orderstatus"=>4,'rate'=>$service_charge,'completed_weight'=>number_format($weight+$orderDetails['weight'],3,'.','')),'id_orderdetails',$id_orderdetails,'customerorderdetails');
		if($this->db->trans_status()===TRUE)
		{
    		$this->db->trans_commit();
    		$response_data=array('status'=>true,'message'=>'Order Status Updated Successfully');
    		$this->session->set_flashdata('chit_alert',array('message'=>'Order Status Updated Successfully','class'=>'success','title'=>'Order Status'));
		}
		else
		{ 
    		$this->db->trans_rollback();
    		$response_data=array('status'=>false,'message'=>'Unable to Procees Your Request');	
    		$this->session->set_flashdata('chit_alert',array('message'=>'Unable to Procees Your Request','class'=>'danger','title'=>'Order Status'));					 	
		}
	    echo json_encode($response_data);
    }
    

}	
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH.'libraries/dompdf/autoload.inc.php');
use Dompdf\Dompdf;
class Admin_ret_other_inventory extends CI_Controller {
	const VIEW_FOLDER = 'other_inventory/';
	const OTHER_INVENTORY_MODEL = 'ret_other_inventory_model'; 
	const SETT_MOD = 'admin_settings_model'; 
	
	function __construct()
	{
		parent::__construct();
		ini_set('date.timezone', 'Asia/Calcutta');
		$this->load->model(self::OTHER_INVENTORY_MODEL); 
		$this->load->model("admin_settings_model"); 
		$this->load->model("log_model");
		$this->load->model("admin_settings_model"); 
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
	
	
    function set_image_other($id,$skuid)
    {
        $model = self::OTHER_INVENTORY_MODEL;
        if($_FILES['other']['name']['other_item_img'])
        { 
            $path='assets/img/other_inventory/'.$skuid;
            if (!is_dir($path)) {
            mkdir($path, 0777, TRUE);
            }
    
            $img=$_FILES['other']['tmp_name']['other_item_img'];
            $filename =time().".jpg";
            $imgpath=$path.'/'.$filename;
            $upload=$this->upload_img('image',$imgpath,$img);	
            $data['item_image']= $filename;
            //print_r($data['image']);exit;
            //$this->$model->updateData("update",$id['ID'],$data);
            $id=$this->$model->updateData($data,'id_other_item',$id,'ret_other_inventory_item');
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
	  imagejpeg($tmp, $dst, 60);
	}
    
    //Item Type
    public function other_inventory($type="",$id="")
    {
    	$model=self::OTHER_INVENTORY_MODEL;
    	switch($type)
    	{
    		case "add":
    			$data['main_content'] = "other_inventory/form";
    			$this->load->view('layout/template', $data);
    		break;
    		
    		case 'list':
    			$data['main_content'] = "other_inventory/list" ;
    			$this->load->view('layout/template', $data);
    		break;
    		
    		case 'save'	:
    		    $addData = $_POST['other'];
    		    $pieces = $_POST['pieces'];
    			$data=array ( 
    			              'name'            =>strtoupper($addData['name']),
    				          'id_inv_size'     =>($addData['id_size']!='' ?$addData['id_size'] :NULL),
    				          'stock_id_uom'    =>($addData['id_uom']!='' ?$addData['id_uom'] :NULL),
    				          'item_for'        =>$addData['item_for'],
    				          'issue_preference'=>$addData['issue_preference'],
    				          'created_on'	    =>date("Y-m-d H:i:s"),
    				          'created_by'      => $this->session->userdata('uid')
                            ); 
    						$this->db->trans_begin();
    					    $id_other_item =$this->$model->insertData($data,'ret_other_inventory_item');
    					
    					    if($id_other_item)
    					    {
                                if(sizeof($pieces)>0)
                                {
                                    foreach($pieces as $items)
                                    {
                                        $insdata=array(
                                        'id_branch'	            => $items['id_branch'],
                                        'id_other_item'	        => $id_other_item,
                                        'max_pcs'	            => $items['max_pcs'],
                                        'min_pcs'	            => $items['min_pcs'],
                                        );
                                        $this->$model->insertData($insdata,'ret_other_inventory_reorder_settings');
                                    }
                                }
                                $this->$model->update_other_inventory(array('sku_id'=>$id_other_item),$id_other_item,'id_other_item','ret_other_inventory_item');
    					    }
    					
    						if(isset($_FILES['other']['name']))
    						{
    							if($id_other_item>0)
    							{
    								$this->set_image_other($id_other_item,$addData['sku_id']);
    							}
    						}
    						
    						/*$this->load->library('phpqrcode/qrlib');
    						 $SERVERFILEPATH = 'other_qrcode/'.$addData['sku_id'];
    						 if (!is_dir($SERVERFILEPATH)) {  
    						 mkdir($SERVERFILEPATH, 0777, TRUE);
    						 } 
    						 $folder = $SERVERFILEPATH;
    						 $file_name1 = time().$addData['sku_id']. ".png";
    						 $file_name = $SERVERFILEPATH.'/'.$file_name1;
    					     QRcode::png($addData['sku_id'],$file_name);  */
    					     
    						if($this->db->trans_status()===TRUE)
    						{
    							 $this->db->trans_commit();
    							 $this->session->set_flashdata('chit_alert', array('message' => 'Item added successfully','class' => 'success','title'=>'New Item'));	  
    						}
    						else
    						{
    							$this->db->trans_rollback();
    							$this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'New Item'));
    						}
    						redirect('admin_ret_other_inventory/other_inventory/list');
    		break;
    		
    		case "edit":
    			$data['other']              = $this->$model->get_other_inventory_records($id);
    			$data['reorder_details']    = $this->$model->get_inv_item_reorder_details($id);
    			//echo "<pre>";print_r($data);exit;
    			$data['main_content'] = "other_inventory/form" ;
    			$this->load->view('layout/template', $data);
    	    break; 	 				
    		
    		case 'delete':
    			$this->db->trans_begin();
    			$this->$model->deleteData('id_other_item',$id,'ret_other_inventory_item'); 
    			if($this->db->trans_status()===TRUE)
    			{
    				$this->db->trans_commit();
    				$this->session->set_flashdata('chit_alert', array('message' => 'Item deleted successfully','class' => 'success','title'=>'Other Item'));	  
    			}
    			else
    			{
    			   $this->db->trans_rollback();
    			   $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Other Item'));
    			}
    			redirect('admin_ret_other_inventory/other_inventory/list');	
    		break;
    		
    		case "update":
    			$addData = $_POST['other'];
    			$pieces = $_POST['pieces'];
    			$inv_data=$this->$model->get_other_inventory_records($id);
    			$data=array(   
    			              'name'            =>strtoupper($addData['name']),
    				          'id_inv_size'    =>($addData['id_size']!='' ?$addData['id_size'] :NULL),
    				          'item_for'        =>$addData['item_for'],
    				          'issue_preference'=>$addData['issue_preference'],
    						  'updated_on'	    => date("Y-m-d H:i:s"),
    						  'updated_by'    => $this->session->userdata('uid')
    						);
    						$this->db->trans_begin();
    						$item=$this->$model->update_other_inventory($data,$id,'id_other_item','ret_other_inventory_item');
    						
    						if($item)
    						{
    						    if(sizeof($pieces)>0)
                                {
                                    $this->$model->deleteData('id_other_item',$id,'ret_other_inventory_reorder_settings'); 
                                    foreach($pieces as $items)
                                    {
                                        $insdata=array(
                                        'id_branch'	            => $items['id_branch'],
                                        'id_other_item'	        => $id,
                                        'max_pcs'	            => $items['max_pcs'],
                                        'min_pcs'	            => $items['min_pcs'],
                                        );
                                        $this->$model->insertData($insdata,'ret_other_inventory_reorder_settings');
                                        //print_r($this->db->last_query());exit;
                                    }
                                }
    						}
    						
    						if(isset($_FILES['other']['name']['other_item_img']) && $_FILES['other']['name']['other_item_img'] !='')	
    			            {
    							if($id>0)
    						    {
    							    $this->set_image_other($item,$inv_data['sku_id']);
    						    }
    						}
    						
    						$this->load->library('phpqrcode/qrlib');
    						$SERVERFILEPATH = 'other_qrcode/'.$addData['sku_id'];
    						if (!is_dir($SERVERFILEPATH)) {  
    						mkdir($SERVERFILEPATH, 0777, TRUE);
    						} 
    						$folder = $SERVERFILEPATH;
    						$file_name1 = time().$addData['sku_id']. ".png";
    						$file_name = $SERVERFILEPATH.'/'.$file_name1;
    						QRcode::png($addData['sku_id'],$file_name);  //Passing QR data
    					   
    						
    						if($this->db->trans_status()===TRUE)
    						{
    							$this->db->trans_commit();
    							$this->session->set_flashdata('chit_alert',array('message'=>'Item modified successfully','class'=>'success','title'=>'Selected Item'));			
    						}
    						else
    						{
    						   $this->db->trans_rollback();						 	
    						   $this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Selected Item'));
    				        }
    						redirect('admin_ret_other_inventory/other_inventory/list');	
    		break;	
    		
    		case 'print_qrcode':
    			$addData=$this->$model->get_other_inventory_records($id);
                $this->load->library('phpqrcode/qrlib');
    			$SERVERFILEPATH = 'other_qrcode/'.$addData['sku_id'];
    			if (!is_dir($SERVERFILEPATH)) 
    			{  
    			mkdir($SERVERFILEPATH, 0777, TRUE);
    			} 
    			$folder = $SERVERFILEPATH;
    			$file_name1 = time().$addData['sku_id']. ".png";
    			$file_name = $SERVERFILEPATH.'/'.$file_name1;
    			QRcode::png($addData['sku_id'],$file_name);  //Passing QR data
    			$src['img'][]=array( 'sku_id'     =>$addData['sku_id'],
    						         'src'        =>$this->config->item('base_url')."other_qrcode".'/'.$addData['sku_id'].'/'.$file_name1
    							    );
    								//echo "<pre>"; print_r($src);exit;
    			                    $html1 = $this->load->view('other_qrcode/item_qrcode', $src,true);
    							    $html = preg_replace('/>\s+</', "><", $html1); //Remove Blank page
    							    $this->load->helper(array('dompdf', 'file'));
    						        $dompdf = new DOMPDF();
    						        $dompdf->load_html($html);
    						        //$customPaper = array(0,0,220,111);
    						        //$customPaper = array(0,0,50,45);
    						        $dompdf->set_paper("portriat" );
    						        $dompdf->render();
    						        $dompdf->stream("other.pdf",array('Attachment'=>0));
    	    break; 
    		case 'active_skuid':
    
    			$data = $this->$model->getActiveskuid($_POST['searchTxt'],$_POST['searchField']);	  
    			echo json_encode($data);
    	   break;
    	   
                                             
    		default:
    			$SETT_MOD = self::SETT_MOD;
    			$list = $this->$model->ajax_get_other_inventory();	 
    			$access = $this->$SETT_MOD->get_access('admin_ret_other_inventory/other_inventory/list');
    			$data =array( 'list' =>$list,
    	                       'access'=>$access
    						);
    						echo json_encode($data);
    	}
    }
    
    
    function check_sku_id()
    {
        $sku_id=$this->input->post('sku_id');	
        $model_name=self::OTHER_INVENTORY_MODEL;		
        $available=$this->$model_name->skuid_available($sku_id);	
        if($available)
        {
            echo 1;	
        }
        else
        {
            echo 0;
        }
    } 
    
    //Category
    public function inventory_category($type="",$id="")
    {
    	$model=self::OTHER_INVENTORY_MODEL;	
    	switch($type)
     		{
    			case "add":
    				    $data['main_content'] = "other_inventory/category/form";
    		    		$this->load->view('layout/template', $data);
    	                break;
    	
    	           case 'list':
    				$data['main_content'] = "other_inventory/category/list" ;
    		    		$this->load->view('layout/template', $data);
    	   	       break;
    		
    		      case 'save':
    				$addData = $_POST['item'];
    				$data =array( 
    				          'name'                 =>  $addData['name'],
    					      'outward_type'         =>  $addData['outward_type'],
    					      'asbillable'           =>  $addData['as_bill'],
    					      'expirydatevalidate'   =>  $addData['exp_date'],
    					      'reorderlevel'         =>  $addData['reorder_level'],
    					      'created_on'	         =>  date("Y-m-d H:i:s"),
    					      'created_by'           => $this->session->userdata('uid')
    	                                     );
    							$this->db->trans_begin();
    							$id_other_item_type =$this->$model->insertData($data,'ret_other_inventory_item_type');
    							//print_r($this->db->last_query());exit;
    							if($this->db->trans_status()===TRUE)
    							{
    								$this->db->trans_commit();
    								$this->session->set_flashdata('chit_alert', array('message' => 'Category added successfully','class' => 'success','title'=>'Category'));	  
    							}			  
    							else
    							{
    								$this->db->trans_rollback();
    								$this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Category'));
    							}
    							
    							redirect('admin_ret_other_inventory/inventory_category/list');	
    		    break;
    		
    		   case "edit":
    				$data['item'] = $this->$model->get_other_item_records($id);
    				//echo "<pre>";print_r($data);exit;
    				$data['main_content'] = "other_inventory/category/form";
    				$this->load->view('layout/template', $data);
    		  break; 	 				
    					 
    
    		 case 'delete':
    			     $this->db->trans_begin();
    			     $this->$model->deleteData('id_other_item_type',$id,'ret_other_inventory_item_type'); 
    			     if($this->db->trans_status()===TRUE)
    			     {
    				$this->db->trans_commit();
    				$this->session->set_flashdata('chit_alert', array('message' => 'Item deleted successfully','class' => 'success','title'=>'Other Item'));	  
    			     }			  
    			    else
    			    {
    				$this->db->trans_rollback();
    				$this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Other Item'));
    			    }
    				redirect('admin_ret_other_inventory/inventory_category/list');	
    		break;
    						 
    
                  case "update":
                      
    			      $addData = $_POST['item'];
    			      
    			      $data = array( 
    			             'name'                 =>	$addData['name'],
    					     'outward_type'         =>	$addData['outward_type'],
    					     'asbillable'     	    =>	$addData['as_bill'],
    					     'expirydatevalidate'   =>	$addData['exp_date'],
    					     'reorderlevel' 	    =>	$addData['reorder_level'],
    					     'updated_on'	    =>  date("Y-m-d H:i:s"),
    					     'updated_by'           =>  $this->session->userdata('uid')
    					   ); 
    								$this->db->trans_begin();
    								$this->$model->update_otheritem($data,$id,'id_other_item_type','ret_other_inventory_item_type');
    					
    								if($this->db->trans_status()===TRUE)
    								{
    									$this->db->trans_commit();
    									$this->session->set_flashdata('chit_alert',array('message'=>'Item modified successfully','class'=>'success','title'=>'Selected Item'));			
    								}
    								else
    								{
    									$this->db->trans_rollback();						 	
    									$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Selected Item'));
    									
    								 }
    					redirect('admin_ret_other_inventory/inventory_category/list');	
    		break;
     
    		case 'active_itemname': 
    			$data = $this->$model->getActiveItemname();	  
    		        echo json_encode($data);
    	        break;
    	  
    	  	default:
    	  		$SETT_MOD = self::SETT_MOD;
    	  		$list = $this->$model->ajax_getotheritem();	 
    	  		$access = $this->$SETT_MOD->get_access('admin_ret_other_inventory/inventory_category/list');
    	  		$data = array(
    	 				'list' =>$list,
    	 				'access'=>$access
    	        	     	     );
    		echo json_encode($data);
         }
    }
    
    function otheritem_status($status,$id)
    {
        $data = array('status' => $status);
        $model=self::OTHER_INVENTORY_MODEL;
        $updstatus = $this->$model->update_otheritem($data,$id);
        if($updstatus)
        {
            $this->session->set_flashdata('chit_alert',array('message'=>'Item status updated as '.($status ==1 ? 'Active' : 'Inactive').' successfully.','class'=>'success','title'=>'Item  Status'));			
        }	
        else
        {
            $this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Item  Status'));
        }	
        redirect('admin_ret_other_inventory/inventory_category/list');
    }
    
    function get_inventory_category()
    {
        $model=self::OTHER_INVENTORY_MODEL;
        $data=$this->$model->get_inventory_category();
        echo json_encode($data);
    }

    //Category
    
    
    //Purhcase Entry
    
    public function purchase_entry($type="",$id="")
    {
    	$model=self::OTHER_INVENTORY_MODEL;	
    	switch($type)
     		{
    			case "add":
    				    $data['main_content'] = "other_inventory/purchase/form";
    		    		$this->load->view('layout/template', $data);
    	                break;
    	
    	           case 'list':
    				$data['main_content'] = "other_inventory/purchase/list" ;
    		    		$this->load->view('layout/template', $data);
    	   	       break;
    	           case 'save':
    	               $responseData=array();
    	               $addData=$_POST['purchase'];
    	               $order_items=$_POST['order_items'];
    	               
    	               
    	               $branch      = $this->$model->get_headOffice();
    				   $dCData      = $this->admin_settings_model->getBranchDayClosingData($branch['id_branch']);
    				   $entry_date  = ($dCData['entry_date'] == date("Y-m-d") ? date("Y-m-d H:i:s") : $dCData['entry_date']);
    	               
    	               
    	                $ref_no =$this->$model->generatePurNo();
                        $insData=array(
                            'otr_inven_pur_supplier'    =>$addData['id_karigar'],
                            'entry_date'                =>$entry_date,
                            'supplier_order_ref_no'     =>$addData['sup_refno'],
                            'otr_inven_pur_order_ref'   =>$ref_no,
                            'supplier_bill_date'        =>($addData['sup_billdate']!='' ?$addData['sup_billdate'] :date("Y-m-d")),
                            'otr_inven_pur_created_on'  =>date("Y-m-d H:i:s"),
                            'otr_inven_pur_created_by'  =>$this->session->userdata('uid')
                        );
                        $this->db->trans_begin();
    					$insId =$this->$model->insertData($insData,'ret_other_inventory_purchase');
    					//print_r($this->db->last_query());exit;
    					if($insId)
    					{
    					    //echo "<pre>"; print_r($order_items);exit;
    					    if(!empty($order_items))
    					    {
    					        foreach($order_items['itemid'] as $key =>$val)
    					        {
    					            $itemData=array(
    					                           'otr_inven_pur_id'   =>$insId,
    					                           'inv_pur_itm_itemid' =>$order_items['itemid'][$key],
    					                           'inv_pur_itm_qty'    =>$order_items['quantity'][$key],
    					                           'inv_pur_itm_rate'   =>$order_items['rate'][$key],
    					                           'inv_pur_itm_total'  =>$order_items['amount'][$key],
    					                           );
    					           $item_InsId=$this->$model->insertData($itemData,'ret_other_inventory_purchase_items');
    					           if($item_InsId)
    					           {
    					               for($i=1;$i<=$order_items['quantity'][$key];$i++)
    					               {
    					                   $item_ref_no =$this->$model->generateItemRefNo();
        					               //Item Details
        					               $itemDetail=array(
        					                                'inv_pur_itm_id'=>$item_InsId,
        					                                'other_invnetory_item_id'=>$order_items['itemid'][$key],
        					                                'amount'        =>($order_items['rate'][$key]),
        					                                'piece'         =>1,
        					                                'item_ref_no'   =>$item_ref_no,
        					                                'current_branch'=>$branch['id_branch'],
        					                                'status'        =>0,
        					                                );
        					               $this->$model->insertData($itemDetail,'ret_other_inventory_purchase_items_details');
        					               //Item Details
    					               }
    					               
    					               
    					               //Updating Log
    					               
    					               $inventory_category=$this->$model->get_InventoryCategory($order_items['itemid'][$key]);
    					               //echo "<pre>";print_r($inventory_category);exit;
					                   
					                       $logData=array(
                                                'item_id'      =>$order_items['itemid'][$key],
                                                'no_of_pieces' =>$order_items['quantity'][$key],
                                                'amount'       =>$order_items['amount'][$key],
                                                'date'         =>$entry_date,
                                                'status'       =>0,
                                                'from_branch'  =>NULL,
                                                'to_branch'    =>$branch['id_branch'],
                                                'created_on'   =>date("Y-m-d H:i:s"),
                                                'created_by'   =>$this->session->userdata('uid')
                                            );
                                            $this->$model->insertData($logData,'ret_other_inventory_purchase_items_log');
                                            //echo "<pre>";print_r($this->db->last_query());exit;

    					                //Updating Log
    					                
    					           }
    					        }
    					    }
    					    if($this->db->trans_status()===TRUE)
							{
								$this->db->trans_commit();
								
								$log_data = array(
                                        	'id_log'        => $this->session->userdata('id_log'),
                                        	'event_date'	=> date("Y-m-d H:i:s"),
                                        	'module'      	=> 'Other Inventory',
                                        	'operation'   	=> 'Add',
                                        	'record'        =>  NULL,  
                                        	'remark'       	=> 'Other Inventory.'
                                        );
                                $this->log_model->log_detail('insert','',$log_data);
                
								$responseData=array('status'=>true,'message'=>'Purchase Entry Added successfully');
								$this->session->set_flashdata('chit_alert',array('message'=>'Purchase Entry Added successfully','class'=>'success','title'=>'Other Inventory'));			
							}
							else
							{
								$this->db->trans_rollback();
								$responseData=array('status'=>false,'message'=>'Unable to proceed the requested process');
								$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Other Inventory'));
								
							 }
    					}
    					else
    					{
    					    $responseData=array('status'=>false,'message'=>'Unable to Add Other Inventory Purchase');
    					}
    	                echo json_encode($responseData);
    	           break;
        		   case "edit":
        				$data['item'] = $this->$model->get_other_item_records($id);
        				//echo "<pre>";print_r($data);exit;
        				$data['main_content'] = "other_inventory/category/form";
        				$this->load->view('layout/template', $data);
        		  break; 
        		  default:
        		      $SETT_MOD = self::SETT_MOD;
            	  		$list = $this->$model->ajax_getPurchaseEntrylist($_POST);	 
            	  		$access = $this->$SETT_MOD->get_access('admin_ret_other_inventory/purchase_entry/list');
            	  		$data = array(
            	 				'list' =>$list,
            	 				'access'=>$access
            	        	     	     );
            		    echo json_encode($data);
         }
    }
    
    
    public function stock_details($type="",$id="")
    {
    	$model=self::OTHER_INVENTORY_MODEL;	
    	switch($type)
     		{
    	           case 'list':
    				$data['main_content'] = "other_inventory/report/stock_report" ;
    		    		$this->load->view('layout/template', $data);
    	   	       break;
    	          
        		  default:
                    $data = $this->$model->other_inventory_stock($_POST);	 
                    echo json_encode($data);
         }
    }
    
    
    function get_other_inventory_item()
    {
        $model=self::OTHER_INVENTORY_MODEL;
        $data=$this->$model->get_other_inventory_item();
        echo json_encode($data);
    }
    
    function get_supplier()
    {
        $model=self::OTHER_INVENTORY_MODEL;
        $data=$this->$model->get_supplier();
        echo json_encode($data);
    }
    
    public function issue_item($type="",$id="")
    {
    	$model=self::OTHER_INVENTORY_MODEL;	
    	switch($type)
     		{
    	           case 'list':
    				$data['main_content'] = "other_inventory/issue/list" ;
    		    		$this->load->view('layout/template', $data);
    	   	       break;
    	   	       
    	   	       case 'add':
    				$data['main_content'] = "other_inventory/issue/form" ;
    		    		$this->load->view('layout/template', $data);
    	   	       break;
    	   	       
    	   	       case 'save':
                            $addData        =$_POST['issue'];
                            $responseData   =array();
                            
                            $dCData      = $this->admin_settings_model->getBranchDayClosingData($addData['id_branch']);
    				        $entry_date  = ($dCData['entry_date'] == date("Y-m-d") ? date("Y-m-d H:i:s") : $dCData['entry_date']);
    				   
                            $insData=array(
                                'id_other_item'     =>$addData['id_other_item'],
                                'issue_form'        =>2,
                                'issue_date'        =>$entry_date,
                                'bill_id'           =>$addData['bill_id'],
                                'no_of_pieces'      =>$addData['total_pcs'],
                                'id_branch'         =>$addData['id_branch'],
                                'remarks'           =>$addData['remarks'],
                                'created_on'        =>date("Y-m-d H:i:s"),
                                'created_by'        =>$this->session->userdata('uid')
                            );
                            $this->db->trans_begin();
                            $insId =$this->$model->insertData($insData,'ret_other_invnetory_issue');
                            //print_r($this->db->last_query());exit;
                            if($insId)
                            {
                                $inventoryItem=$this->$model->get_InventoryCategory($addData['id_other_item']);
                                
                                $itemDetails    = $this->$model->get_other_inventory_purchase_items_details($addData['id_other_item'],$addData['id_branch'],$inventoryItem['issue_preference'],$addData['total_pcs']);
                                
                                $total_amount=0;
                                foreach($itemDetails as $items)
                                {
                                    $total_amount+=$items['amount'];
                                    $updData=array(
                                                  'id_inventory_issue'=>$insId,
                                                  'status'=>1
                                                  );
                                    $this->$model->updateData($updData,'pur_item_detail_id',$items['pur_item_detail_id'],'ret_other_inventory_purchase_items_details');
                                    //print_r($this->db->last_query());exit;
                                }
                                
                                $logData=array(
                                    'item_id'      =>$addData['id_other_item'],
                                    'no_of_pieces' =>$addData['total_pcs'],
                                    'amount'       =>$total_amount,
                                    'date'         =>$entry_date,
                                    'status'       =>1,
                                    'from_branch'  =>$addData['id_branch'],
                                    'to_branch'    =>NULL,
                                    'created_on'   =>date("Y-m-d H:i:s"),
                                    'created_by'   =>$this->session->userdata('uid')
                                );
                                $this->$model->insertData($logData,'ret_other_inventory_purchase_items_log'); 
                                
                            	if($this->db->trans_status()===TRUE)
								{
									$this->db->trans_commit();
									$this->session->set_flashdata('chit_alert',array('message'=>'Item Issued successfully','class'=>'success','title'=>'Item Issue'));	
									$responseData=array('status'=>TRUE,'message'=>'Item Issued successfully');
								}
								else
								{
									$this->db->trans_rollback();						 	
									$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Item Issue'));
									$responseData=array('status'=>false,'message'=>'Unable to proceed the requested process');
								}
                            }
                            else
                            {
                                	$responseData=array('status'=>TRUE,'message'=>'Unable to Issue Gift Items.');
                            }
                            echo json_encode($responseData);
    	   	       break;
    	          
        		  default:
                    $data = $this->$model->get_OtherInventoryIssueDetails($_POST);	 
                    echo json_encode($data);
         }
    }
    
    //Purhcase Entry
    
    function get_bill_details()
    {
        $model=self::OTHER_INVENTORY_MODEL;
        $data=$this->$model->get_bill_details($_POST);
        echo json_encode($data);
    }

    function get_invnetory_item()
    {
        $model=self::OTHER_INVENTORY_MODEL;
        $data=$this->$model->get_invnetory_item($_POST);
        echo json_encode($data);
    }
    
    
   
    
    function get_customer()
    {
        $model=self::OTHER_INVENTORY_MODEL;
        $data=$this->$model->get_customer();
        echo json_encode($data);
    }
    
    
    //size master
    public function item_size($type="",$id="")
    {
    	$model=self::OTHER_INVENTORY_MODEL;	
    	switch($type)
     		{
    	           case 'list':
    				$data['main_content'] = "other_inventory/size_list" ;
    		    		$this->load->view('layout/template', $data);
    	   	       break;
    	   	       
    	   	       case 'edit':
    	   	            $data=$this->$model->get_packaging_size($id);
    	   	            echo json_encode($data);
    	   	        break;
    	   	       
    	   	       case 'save':
                            $size_name        =$_POST['size_name'];
                            $responseData   =array();
                            
                            
                            $insData=array(
                                'size_name'  =>$size_name,
                                'created_on' =>date("Y-m-d H:i:s"),
                                'created_by' =>$this->session->userdata('uid')
                            );
                            $this->db->trans_begin();
                            $insId =$this->$model->insertData($insData,'ret_other_inventory_size');
                            
                        	if($this->db->trans_status()===TRUE)
							{
								$this->db->trans_commit();
								$this->session->set_flashdata('chit_alert',array('message'=>'Item Size Added successfully','class'=>'success','title'=>'Item Size'));	
								$responseData=array('status'=>TRUE,'message'=>'Item Size successfully');
							}
							else
							{
								$this->db->trans_rollback();						 	
								$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Item Size'));
								$responseData=array('status'=>false,'message'=>'Unable to proceed the requested process');
							}
                            echo json_encode($responseData);
    	   	       break;
    	   	       
    	   	       case 'get_ActivePackagingItemSize':
    	   	           $data=$this->$model->get_ActivePackagingItemSize();
    	   	           echo json_encode($data);
    	   	       break;
    	   	       
    	   	       case 'update':
                            $size_name        =$_POST['size_name'];
                            $id_inv_size      =$_POST['id_inv_size'];
                            $responseData   =array();
                            
                            
                            $updData=array(
                                'size_name'  =>$size_name,
                                'updated_on' =>date("Y-m-d H:i:s"),
                                'updated_by' =>$this->session->userdata('uid')
                            );
                            $this->db->trans_begin();
                            $this->$model->updateData($updData,'id_inv_size',$id_inv_size,'ret_other_inventory_size');
                        	if($this->db->trans_status()===TRUE)
							{
								$this->db->trans_commit();
								$this->session->set_flashdata('chit_alert',array('message'=>'Item Size Updated successfully','class'=>'success','title'=>'Item Size'));	
								$responseData=array('status'=>TRUE,'message'=>'Item Size Updated successfully');
							}
							else
							{
								$this->db->trans_rollback();						 	
								$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Item Size'));
								$responseData=array('status'=>false,'message'=>'Unable to proceed the requested process');
							}
                            echo json_encode($responseData);
    	   	       break;
    	   	       
    	   	       case 'delete':
            			$this->db->trans_begin();
            			$this->$model->deleteData('id_inv_size',$id,'ret_other_inventory_size'); 
            			if($this->db->trans_status()===TRUE)
            			{
            				$this->db->trans_commit();
            				$this->session->set_flashdata('chit_alert', array('message' => 'Item Size deleted successfully','class' => 'success','title'=>'Other Item Size'));	  
            			}
            			else
            			{
            			   $this->db->trans_rollback();
            			   $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Other Item Size'));
            			}
            			redirect('admin_ret_other_inventory/item_size/list');	
            		break;
    	          
        		  default:
                        $SETT_MOD = self::SETT_MOD;
            	  		$list = $this->$model->ajax_getOtherInventorySizeList($_POST);	 
            	  		$access = $this->$SETT_MOD->get_access('admin_ret_other_inventory/item_size/list');
            	  		$data = array(
            	 				'list' =>$list,
            	 				'access'=>$access
            	        	     	     );
            		    echo json_encode($data);
         }
    }
    
    function packaging_item_size_status($status,$id)
	{
		$model=self::OTHER_INVENTORY_MODEL;
		$updStatus=$this->$model->updateData(array('status' => $status),'id_inv_size',$id,'ret_other_inventory_size');
		if($updStatus)
		{
			$this->session->set_flashdata('chit_alert',array('message'=>'Size updated as '.($status ? 'active' : 'inactive').' successfully.','class'=>'success','title'=>'Item Size'));			
		}	
		else
		{
			$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Item Size'));
		}	
		redirect('admin_ret_other_inventory/item_size/list');
	}
	
    //size master
    
    
    //Available Stock
    public function available_stock($type="",$id="")
    {
    	$model=self::OTHER_INVENTORY_MODEL;	
    	switch($type)
     		{
    	           case 'list':
    				$data['main_content'] = "other_inventory/report/available_stock" ;
    		    		$this->load->view('layout/template', $data);
    	   	       break;
    	          
        		  default:
                    $data = $this->$model->get_AvailableStockDetails($_POST);	 
                    echo json_encode($data);
         }
    }
    //Available Stock
    
    
    
    //Product Mapping
    public function product_mapping($type="",$id="")
    {
    	$model=self::OTHER_INVENTORY_MODEL;	
    	switch($type)
     		{
    	           case 'list':
    				$data['main_content'] = "other_inventory/product_mapping" ;
    		    		$this->load->view('layout/template', $data);
    	   	       break;
    	          
        		  default:
                    $SETT_MOD = self::SETT_MOD;
                        $list = $this->$model->get_item_mapping_details($_POST);
                        $access = $this->$SETT_MOD->get_access('admin_ret_other_inventory/product_mapping/list');
                        $data = array(
                            'list' =>$list,
                            'access'=>$access
                        );
                   
                    echo json_encode($data);
         }
    }
    
    function delete_product_mapping()
	{
	    $model=self::OTHER_INVENTORY_MODEL;
	    $reqdata =$this->input->post('req_data');
	   
	    foreach($reqdata as $items)
	    {
	       $this->db->trans_begin();
	       $this->$model->deleteData('inv_des_id',$items['inv_des_id'],'ret_other_inventory_product_link');
	    }
	   
        if($this->db->trans_status()===TRUE)
		{	
			$this->db->trans_commit();
			$status=array('status'=>true,'msg'=>'Product Mapped Deleted successfully');	
		}
		else
		{
			$this->db->trans_rollback();
			$status=array('status'=>false,'msg'=>'Unable to Proceed Your Request');	
		}
  	   echo json_encode($status);
	}
    
    function update_product_mapping()
	{
	    $model=self::OTHER_INVENTORY_MODEL;
	    $products     = $this->input->post('id_product');
	    $id_other_item      = $this->input->post('id_other_item');
	    //print_r($products);exit;
	    if($products[0]==0)
	    {
	        $product=$this->$model->get_ActiveProduct();
	        foreach($product as $val)
    	    {
    	        if($this->$model->check_other_inv_products_maping($val['pro_id'],$id_other_item))
        	    {
                    $insdata=array(
                    'inv_pro_id'          =>$val['pro_id'],
                    'inv_des_otheritemid' =>$id_other_item,
                    'inv_des_created_by'  =>$this->session->userdata('uid'),
                    'inv_des_created_on'  =>date("Y-m-d H:i:s"),
                    );
                    $this->db->trans_begin();
                    $this->$model->insertData($insdata,'ret_other_inventory_product_link');
        	    }
    	    }
	    }
	    else
	    {
	        foreach($products as $pro_id)
    	    {
    	        if($this->$model->check_other_inv_products_maping($pro_id,$id_other_item))
        	    {
                    $insdata=array(
                    'inv_pro_id'          =>$pro_id,
                    'inv_des_otheritemid' =>$id_other_item,
                    'inv_des_created_by'  =>$this->session->userdata('uid'),
                    'inv_des_created_on'  =>date("Y-m-d H:i:s"),
                    );
                    $this->db->trans_begin();
                    $this->$model->insertData($insdata,'ret_other_inventory_product_link');
        	    }
    	    }
	    }
	    
	   
        if($this->db->trans_status()===TRUE)
		{	
			$this->db->trans_commit();
			$status=array('status'=>true,'msg'=>'Product Mapped successfully');	
		}
		else
		{
			$this->db->trans_rollback();
			$status=array('status'=>false,'msg'=>'Unable to Proceed Your Request');	
		}
  	   echo json_encode($status);
	}
	
	function get_productMappedDetails()
	{
	    $model=self::OTHER_INVENTORY_MODEL;
	    $data=$this->$model->get_productMappedDetails($_POST['id_branch']);
	    echo json_encode($data);
	}
	
    //Product Mapping
    
    
    //Reporder Report
    public function reorder_report($type="",$id="")
    {
    	$model=self::OTHER_INVENTORY_MODEL;	
    	switch($type)
     		{
	           case 'list':
				$data['main_content'] = "other_inventory/report/reorder" ;
		    		$this->load->view('layout/template', $data);
	   	       break;
	          
    		  default:
                $SETT_MOD = self::SETT_MOD;
                    $list = $this->$model->get_reorder_report($_POST);
                    $access = $this->$SETT_MOD->get_access('admin_ret_other_inventory/reorder_report/list');
                    $data = array(
                        'list' =>$list,
                        'access'=>$access
                    );
               
                echo json_encode($data);
         }
    }
    //Reporder Report
    
    

}
?>
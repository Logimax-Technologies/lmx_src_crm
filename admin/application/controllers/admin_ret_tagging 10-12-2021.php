<?php
if( ! defined('BASEPATH')) exit('No direct script access allowed');
use Dompdf\Dompdf;
class Admin_ret_tagging extends CI_Controller
{
	const IMG_PATH  = 'assets/img/';
	const PROD_PATH  = 'assets/img/products/';
	const SERV_MODEL = "admin_usersms_model";
	
	function __construct()
	{
		parent::__construct();
		ini_set('date.timezone', 'Asia/Calcutta');
		$this->load->model('ret_tag_model');
		$this->load->model('admin_settings_model');
		$this->load->model("sms_model");
		$this->load->model("log_model");
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
	
	/**
	* Tagging Functions Starts
	*/
	
	public function tagging($type="",$id="", $tag_print_id=""){

		$model = "ret_tag_model";
		switch($type)
		{
			case 'add':
						$data['tag_prints'] = $tag_print_id;
						$data['tagging']= $this->$model->get_empty_record();
						$data['uom']= $this->$model->getUOMDetails();
						$data['main_content'] = "tagging/form" ;
						$this->load->view('layout/template', $data);
						break;
			case 'list':
						$data['main_content'] = "tagging/tag_list" ;
						$this->load->view('layout/template', $data);
						break;
		    case 'duplicate_print':
						$data['main_content'] = "tagging/re_print" ;
						$this->load->view('layout/template', $data);
			break;
			case 'tag_scan':
						$data['main_content'] = "tagging/tag_scan" ;
						$this->load->view('layout/template', $data);
			break;
			case 'tag_mark':
						$data['main_content'] = "tagging/tag_mark" ;
						$this->load->view('layout/template', $data);
			break;
			case 'tag_edit':
						$data['main_content'] = "tagging/tag_edit" ;
						$this->load->view('layout/template', $data);
			break;
			case "tag_link":
					$data['main_content'] = "tagging/tag_link" ;
		 			$this->load->view('layout/template', $data);
			break;
			case "save": 
						/*echo "<pre>";
						print_r($_POST);
						echo "</pre>";
						exit;*/
						$addData = $_POST['lt_item'];
						//echo "<pre>"; print_r($addData); echo "</pre>"; exit;
						$tag_insert_id = '';
						$id_bt = NULL;
						$piece = 0;
						$gross_wt = 0;
						$less_wt = 0;
						$net_wt = 0;
						$trans_code = '';
						$toBranch = $addData['to_branch'];
					    $ref_no = time()."-".$this->session->userdata('uid');
						
						$dCData = $this->admin_settings_model->getBranchDayClosingData($addData['id_branch']);
						$tag_datetime = ($dCData['entry_date'] == date("Y-m-d") ? date("Y-m-d H:i:s") : $dCData['entry_date']);
						 
						/*echo "<pre>";
						print_r($_POST);
						echo "<pre>";exit;*/
						//$fin_year=$this->$model->get_financialyear_by_status();
						
						//$tag_datetime= date('Y-m-d',strtotime(str_replace("/","-",$addData['tag_datetime'])));
						$this->db->trans_begin();
						foreach($addData['lot_no'] as $k => $v)
						{ 
							$piece 		= $piece + (isset($addData['no_of_piece'][$k]) ? $addData['no_of_piece'][$k] : 0);
							$gross_wt 	= $gross_wt + (isset($addData['gross_wt'][$k]) ? $addData['gross_wt'][$k] : 0);
							$less_wt 	= $less_wt + (isset($addData['less_wt'][$k]) ? $addData['less_wt'][$k] : 0);
							$net_wt 	= $net_wt + (isset($addData['net_wt'][$k]) ? $addData['net_wt'][$k] : 0);
						}
						// Add to Branch Transfer
						if($addData['id_branch'] != $addData['to_branch'] && $addData['to_branch'] != ""){
							$this->load->model("ret_brntransfer_model");
							$trans_code = $this->ret_brntransfer_model->trans_code_generator();

							$bt_data = array( 
								'branch_trans_code'		=> $trans_code,
								'transfer_from_branch'	=> (isset($addData['id_branch']) ? $addData['id_branch'] : NULL),
								'transfer_to_branch'	=> (isset($addData['to_branch']) ? $addData['to_branch'] : NULL),
								'transfer_item_type'	=> 1,
								'pieces'				=> $piece,
								'grs_wt'				=> $gross_wt,
								'net_wt'				=> $net_wt,
								'added_type'			=> 2,
								'create_by'				=> $this->session->userdata('uid'),
								'created_time'			=> date('Y-m-d H:i:s'),
								'id_lot_inward_detail' 	=> (isset($_POST['id_lot_inward_detail']) ? $_POST['id_lot_inward_detail'] : 0)
							  );
							$id_bt = $this->$model->insertData($bt_data,'ret_branch_transfer');
						} 	
	 					foreach($addData['lot_no'] as $key => $val)
	 					{ 
	 					    
	 					    //$ref_no=$this->$model->getLotRefNo($addData['lot_no'][$key]);
	 					    
		 					//$tag_code = $this->$model->code_number_generator();
		 					
		 					
		 					//Image Upload
		 					$precious_imgs = "";
		 					$p_ImgData = json_decode($addData['tag_img'][$key]);  
							if(sizeof($p_ImgData) > 0){
								foreach($p_ImgData as $precious){
									$imgFile = $this->base64ToFile($precious->src);
									$_FILES['precious'][] = $imgFile;
								}
							} 
							if(!empty($_FILES)){
								$img_arr = array();
								$folder =  self::IMG_PATH."tag/"; 
								if (!is_dir($folder)) {  
									mkdir($folder, 0777, TRUE);
								}   
								if(isset($_FILES['precious'])){ 
									$precious_imgs = "";
									foreach($_FILES['precious'] as $file_key => $file_val){
										if($file_val['tmp_name'])
										{
											// unlink($folder."/".$product['image']); 
											$img_name =  "t_". mt_rand(120,1230).".jpg";
											$path = $folder."/".$img_name; 
											$result = $this->upload_img('image',$path,$file_val['tmp_name']);
											if($result){
												$precious_imgs = strlen($precious_imgs) > 0 ? $precious_imgs."#".$img_name : $img_name;
											}
										}
									}
								}
							}

							if($addData['cert_image'][$key] != "")
								$cert_img = uniqid().".jpg";
							else
								$cert_img = "";

							$arrayTag = array(
								//'tag_code' 			=> $addData['product_short_code'][$key]."-".$tag_code,
								'current_branch' 	=> $addData['id_branch'], 
								'id_branch' 		=> $addData['id_branch'], 
								'cost_center' 		=> $addData['id_branch'], 
								'tag_lot_id' 		=> $addData['lot_no'][$key],  
								'product_id' 		=> $addData['lot_product'][$key], 
								'design_id' 		=> $addData['lot_id_design'][$key], 
								'id_sub_design' 	=> $addData['lot_id_sub_design'][$key], 
								'design_for' 		=> 0,//$addData['design_for'][$key], 
								'purity' 			=> $addData['purity'][$key], 
								'id_orderdetails'=>(isset($addData['id_orderdetails'][$key]) ?($addData['id_orderdetails'][$key]!='' ? $addData['id_orderdetails'][$key]:NULL) :NULL),
								'id_lot_inward_detail' => $addData['id_lot_inward_detail'][$key], 
								'size' 				=> ($addData['size'][$key]!='' ? ($addData['size'][$key]!='null' ? $addData['size'][$key]:NULL):NULL), 
								'piece' 			=> (($addData['no_of_piece'][$key]!='') ? $addData['no_of_piece'][$key]:NULL), 
								'gross_wt' 			=> (($addData['gross_wt'][$key]!='') ? $addData['gross_wt'][$key]:NULL), 
								'less_wt' 			=> (($addData['less_wt'][$key]!='') ? $addData['less_wt'][$key]:NULL), 
								'net_wt' 			=> (($addData['net_wt'][$key]!='') ? $addData['net_wt'][$key]:NULL), 
								'calculation_based_on' => $addData['calculation_based_on'][$key],
								'retail_max_wastage_percent' => (($addData['wastage_percentage'][$key]!='') ? $addData['wastage_percentage'][$key]:NULL), 
								'tag_mc_type' 		=> (($addData['id_mc_type'][$key]!='') ? $addData['id_mc_type'][$key]:NULL), 
								'tag_mc_value' 	    => (($addData['making_charge'][$key]!='') ? $addData['making_charge'][$key]:0), 
								'sell_rate' 		=> (($addData['sell_rate'][$key]!='') ? $addData['sell_rate'][$key]:NULL), 
								'item_rate' 		=> (($addData['adjusted_item_rate'][$key]!='') ? $addData['adjusted_item_rate'][$key]:NULL), 
								'sales_value' 		=> (($addData['sale_value'][$key]!='') ? $addData['sale_value'][$key]:NULL), 
								'hu_id'             => (($addData['huid'][$key]!='') ? $addData['huid'][$key]:NULL), 
								'hu_id2'            => (($addData['huid2'][$key]!='') ? $addData['huid2'][$key]:NULL), 
								'image'				=> ((strlen($precious_imgs) > 0)?$precious_imgs : NULL),
								'tag_datetime'	  	=> $tag_datetime,
								'created_time'	  	=> date("Y-m-d H:i:s"),
								'created_by'      	=> $this->session->userdata('uid'),
								'ref_no'      		=> $ref_no,
								"cert_no"			=> (($addData['cert_no'][$key]!='') ? $addData['cert_no'][$key]:NULL), 
								"cert_img"			=> $cert_img, 
								'manufacture_code' 	=> $addData['manufacture_code'][$key],
								'style_code' 		=> $addData['style_code'][$key],
								'remarks' 			=> $addData['remarks'][$key]
							);  
						//echo "<pre>"; print_r($arrayTag);exit;
	 					$insId = $this->$model->insertData($arrayTag,'ret_taging');  
						$tag_insert_id = $insId;
						$tag_code='';
	 					//print_r($this->db->last_query());exit;
	 					if($insId)
	 					{
							$dir_certimg = $this->config->item('tag_cert_img_path').$insId."/";

							$base64_img = $addData['cert_image'][$key];

							if($base64_img != "") {

								if (!file_exists($dir_certimg)) {
									mkdir($dir_certimg, 0777, true);
								}

								$path = $dir_certimg.$cert_img;

								$this->save_base64_image($base64_img, $path);

							}
							
	 						/*//Update order Status
	 						if($addData['id_orderdetails'][$key]!='')
	 						{
	 							$this->$model->updateData(array('orderstatus'=>5),'id_orderdetails',$addData['id_orderdetails'][$key],'customerorderdetails');
	 						}*/
	 						//Update order Status
	 					    
	 					    $log_data=array(
	 					    	'tag_id'	  =>$insId,
	 					    	'date'		  =>$tag_datetime,
	 					    	'status'	  =>0,
	 					    	'from_branch' =>NULL,
	 					    	'to_branch'	  =>$addData['id_branch'],
	 					    	'created_on'  =>date("Y-m-d H:i:s"),
								'created_by'  =>$this->session->userdata('uid'),
	 					    );
	 					    $this->$model->insertData($log_data,'ret_taging_status_log');

	 					    //$code_number=str_pad($insId, 5, '0', STR_PAD_LEFT);
	 					    
	 					    //Tag Code Generation
							$catArr = $this->$model->get_category_from_productid($arrayTag['product_id']);
							$catcode = $catArr['cat_code'];

							$curr_year = date("y");

	 					    $tagCode = $this->$model->getlastTagCode(); 

	 					    $tag_no  = $this->generateTagCode($tagCode);

							$tag_code=$catcode.$curr_year.$addData['product_short_code'][$key].'-'.$tag_no;

	 						$this->$model->updateData(array('tag_code'=>$tag_code),'tag_id',$insId,'ret_taging');
	 						//Tag Code Generation
	 						
	 						
	 						//Taggig code log data
	 						$tag_log_data[]=array('last_tag_code'=>$tagCode,'new_tag_code'=>$tag_no,'tag_id'=>$insId,'date_time'=>date("Y-m-d H:i:s"),'ip_address'=>$this->session->userdata('ip_address'));
	 						
	 						
	 						// Add to BT items
	 						if($id_bt != NULL){
								$items = array(
										'transfer_id'	=> $id_bt,
										'tag_id'		=> $insId, 
										'id_lot_inward_detail'	=> $addData['id_lot_inward_detail'][$key],
										);
								$this->$model->insertData($items,'ret_brch_transfer_tag_items');
							}
	 												
	 						// Stone Details
		 					if($addData['stone_details'][$key])
							{
								$stone_details=json_decode($addData['stone_details'][$key],true);
								foreach($stone_details as $stone)
								{
									$stone_data=array(
													'tag_id'                =>$insId,
													'pieces'                =>$stone['stone_pcs'],
													'wt'                    =>$stone['stone_wt'],
													'stone_id'              =>$stone['stone_id'],
													'uom_id'                =>$stone['stone_uom_id'],
													'amount'                =>$stone['stone_price'],
													'rate_per_gram'         =>$stone['stone_rate'],
													'is_apply_in_lwt'       =>$stone['show_in_lwt'],
													);
									$stoneInsert = $this->$model->insertData($stone_data,'ret_taging_stone');
								}										
							}

								//update lot table if tag is completed
							$total_tag=$this->$model->get_tagged_details($addData['id_lot_inward_detail'][$key]);
							if($total_tag['total_pieces']==$total_tag['tagged_pieces'])
							{
							   $lot['tag_status']=1;
							   $tagInsert= $this->$model->updateData($lot,'id_lot_inward_detail',$addData['id_lot_inward_detail'][$key],'ret_lot_inwards_detail');
							 
							}

							if($addData['charges'][$key])
							{
								$charges = (array)json_decode($addData['charges'][$key], true);

								foreach($charges as $charge)
								{
									$charge_data=array(
													'tag_id'                =>$insId,
													'charge_id'             =>$charge['charge_id'],
													'charge_value'          =>$charge['charge_value'],
													);
									$this->$model->insertData($charge_data,'ret_taging_charges');
								}
							}

							if($addData['attributes'][$key])
							{
								$attributes = (array)json_decode($addData['attributes'][$key], true);

								foreach($attributes as $attrs)
								{
									if($attrs['attr_id'] > 0 && $attrs['attr_val_id'] > 0) {

										$attr_data	=	array(
															'id_tagging'    =>	$insId,
															'attr_id'     	=>	$attrs['attr_id'],
															'attr_val_id' 	=>	$attrs['attr_val_id'],
															'created_on'  	=>	date("Y-m-d H:i:s"),
															'created_by'  	=>	$this->session->userdata('uid')
														);
										$this->$model->insertData($attr_data,'ret_tagging_attributes');

									}
								}
							}

						}

					}
	 				
/*echo "<pre>";print_r($_POST);
echo "<pre>";print_r($lot_no);exit;*/
						
					
					if($this->db->trans_status()===TRUE)
					{
					    $log_data = array(
                        	'id_log'        => $this->session->userdata('id_log'),
                        	'event_date'	=> date("Y-m-d H:i:s"),
                        	'module'      	=> 'Tag',
                        	'operation'   	=> 'Add',
                        	'record'        => $ref_no,  
                        	'remark'       	=> 'Tags created successfully.Ref no is'.$ref_no.'.'
                        );
                        $this->log_model->log_detail('insert','',$log_data);
						$this->db->trans_commit();
						
						$log_path = 'tagging_log/'.date("Y-m-d").'/'.$this->session->userdata('uid');
                        if (!is_dir($log_path)) 
                        {  
                            mkdir($log_path, 0777, TRUE);
                        } 
						 $log_path = $log_path.'/post_data.txt';
						 
						$log_detail=json_encode($tag_log_data);
						file_put_contents($log_path,$log_detail,FILE_APPEND | LOCK_EX);
						
						//Generate TagQR Code
						$QrcodeUrl=$this->generate_tagqrcode($insId);
						//Generate TagQR Code
						
						$responseData=array('status'=>true,'message'=>'Tagging added successfully','trans_code'=>$trans_code,'ref_no'=>$ref_no,'to_branch'=>$toBranch,'qrcodeUrl'=>$QrcodeUrl,'tag_id'=>$insId,'tag_code'=>$tag_code);
						$this->session->set_flashdata('chit_alert',array('message'=>'Tagging added successfully','class'=>'success','title'=>'Add Tagging'));
				 	
					}
					else
					{
						/*echo $this->db->last_query();
						echo $this->db->_error_message();exit;*/
						$this->db->trans_rollback();
						$responseData=array('status'=>false,'message'=>'Unable to proceed the requested process','trans_code'=>'','ref_no'=>'','to_branch'=>'');
						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Add Tagging'));
					}
					echo json_encode($responseData);
					/*if($trans_code != '') {
						if(count($addData['lot_no']) == 1 && $tag_insert_id != '')
							redirect('admin_ret_tagging/tagging/add/0/'.$tag_insert_id);
						else
						  	redirect('admin_ret_tagging/bt_tag_list/'.$trans_code.'/'.$ref_no.'/'.$toBranch);	
					} else {
						//redirect('admin_ret_tagging/tagging/list');
						redirect('admin_ret_tagging/tagging/add/0/'.$tag_insert_id);
					}*/
				break; 
			case "edit":
		 			$data['tagging'] = $this->$model->get_entry_records($id);
		 			if($data['tagging']['image']!='')
		 			{
		 				$images=explode('#',$data['tagging']['image']);
			 			foreach($images as $image)
			 			{
			 					$path=$this->config->item('base_url').'/assets/img/tag/'.$image;
			 					$data['tagging']['img_source'][]['src']=$this->imgTobase64($path);
			 			}
		 			}
		 			else
		 			{
		 				$data['tagging']['img_source']='';
		 			}
		 			
		 			$data['tagging']['lot_recv_branch'] = $this->$model->get_ret_settings('lot_recv_branch');
		 			$data['tag_balance']=$this->$model->get_balance_details($data['tagging']['id_lot_inward_detail'],$data['tagging']['design_id']);
		 			$data['tax_percentage']=$this->$model->get_tax_percentage($data['tagging']['tag_lot_id']);
		 			$data['metal_rate']=$this->$model->get_branchwise_rate($data['tagging']['lot_recv_branch']);
		 			$data['stone_details']=$this->$model->get_stone_details($data['tagging']['tag_id']);
					//$data['uom']= $this->$model->getUOMDetails();
					 /*echo "<pre>";
						print_r($data);
						echo "<pre>";exit; */
					//$data['main_content'] = "tagging/form" ;
		 			//$this->load->view('layout/template', $data);
						echo json_encode($data);
				break; 	 
						
			case 'delete':
					$this->db->trans_begin();
					$data = array('tag_status'  =>2,
					'updated_time'=>date("Y-m-d H:i:s"),
					'updated_by'  =>$this->session->userdata('uid')
					);
					$tag_details=$this->$model->get_entry_records($id);
					$updID= $this->$model->updateData($data,'tag_id',$id,'ret_taging');
					if($updID)
					{
						$log_data=array(
	 					    	'tag_id'	  =>$id,
	 					    	'date'		  =>date("Y-m-d H:i:s"),
	 					    	'status'	  =>2,
	 					    	'from_branch' =>$tag_details['current_branch'],
	 					    	'to_branch'	  =>$tag_details['current_branch'],
	 					    	'created_on'  =>date("Y-m-d H:i:s"),
								'created_by'  =>$this->session->userdata('uid'),
	 					    );
	 					    $this->$model->insertData($log_data,'ret_taging_status_log');
					}
					//$this->$model->deleteData('tag_id',$id,'ret_taging'); 
					if( $this->db->trans_status()===TRUE)
					{
					    $log_data = array(
                        	'id_log'        => $this->session->userdata('id_log'),
                        	'event_date'	=> date("Y-m-d H:i:s"),
                        	'module'      	=> 'Tag',
                        	'operation'   	=> 'Delete',
                        	'record'        => $id,  
                        	'remark'       	=> 'Tag deleted successfully'
                        );
                        $this->log_model->log_detail('insert','',$log_data);
						$this->db->trans_commit();
						$this->session->set_flashdata('chit_alert', array('message' => 'Tag deleted successfully','class' => 'success','title'=>'Delete Tag'));	  
					}			  
					else
					{
						$this->db->trans_rollback();
						$this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete Tag'));
					}
					redirect('admin_ret_tagging/tagging/list');	
				break;
					
			case "update":
		 			
		 			
	 				$addData = $_POST['tagging'];
	 				//$fin_year=$this->$model->get_financialyear_by_status();
					//$tag_code=$this->$model->code_number_generator();
	 				
					$this->db->trans_begin();
					
					$addData['tag_datetime'] = date('Y-m-d',strtotime(str_replace("/","-",$addData['tag_datetime'])));
					$data=array(
						 //  'tag_code'	=> (isset($fin_year['fin_year_code']) ?$tag_code.'-'.$fin_year['fin_year_code'] : NULL ),
							'tag_datetime'	=> (isset($addData['tag_datetime']) ? $addData['tag_datetime'] : NULL ),
							'tag_type'				=> (isset($addData['tag_type']) ? $addData['tag_type'] :NULL ),
							'counter'				=> (isset($addData['counter']) ? $addData['counter'] :NULL ),
							'tag_lot_id'		=> (isset($addData['tag_lot_id']) ? $addData['tag_lot_id'] :NULL ),
							'design_id'		=> (isset($addData['design_id']) ? $addData['design_id'] :NULL ),
							'cost_center'		=> (isset($addData['cost_center']) ? $addData['cost_center'] :NULL ),
							'purity'			=> (isset($addData['purity']) ? $addData['purity'] :NULL ),
							'size'				=> (isset($addData['size']) ? $addData['size'] :NULL ),
							'uom'			=> (isset($addData['uom']) ? $addData['uom'] :NULL ),
							'piece'		=> (isset($addData['piece']) ? $addData['piece'] :NULL ),
							'less_wt'			=> (isset($addData['less_wt']) ? $addData['less_wt'] :NULL ),
							'net_wt'			=> (isset($addData['net_wt']) ? $addData['net_wt'] :NULL ),
							'gross_wt'	=> (isset($addData['gross_wt']) ? $addData['gross_wt'] :NULL ),
							'calculation_based_on'				=> (isset($addData['calculation_based_on']) ? $addData['calculation_based_on']: NULL),
							'retail_max_wastage_percent'			=> (isset($addData['retail_max_wastage_percent']) ? $addData['retail_max_wastage_percent'] :NULL ),
							'tag_mc_type'				=> (isset($addData['tag_mc_type']) ? $addData['tag_mc_type'] :NULL ),
							'tag_mc_value'				=> (isset($addData['tag_mc_value']) ? $addData['tag_mc_value'] :NULL ),
							'halmarking'			=> (isset($addData['halmarking']) ? $addData['halmarking'] :NULL ),
							'sell_rate' 		=> (($addData['sell_rate']!='') ? $addData['sell_rate']:NULL), 
							'round_off' 		=> (($addData['round_off']!='') ? $addData['round_off']:NULL), 
							'sales_value'				=> (isset($addData['sales_value']) ? $addData['sales_value'] :NULL ),
//							'tax_group_id'			=> (isset($addData['tax_group_id']) ? $addData['tax_group_id'] :NULL ),
							'current_counter'		=> (isset($addData['current_counter']) ? $addData['current_counter'] :0 ),
							'retail_max_mc'				=> (isset($addData['retail_max_mc']) ? $addData['retail_max_mc'] :NULL ),
							'updated_time'	  		=> date("Y-m-d H:i:s"),
							'created_by'      		=> (isset($addData['created_by']) ? $addData['created_by'] :0 ),
							'current_branch'      	=> (isset($addData['current_branch']) ? $addData['current_branch'] :NULL),
							'id_branch'      	=> (isset($addData['current_branch']) ? $addData['current_branch'] :NULL),
							'updated_by'      		=> $this->session->userdata('uid')
						); 
					  $updID= $this->$model->updateData($data,'tag_id',$id,'ret_taging');
					if($updID)
					{
						$tagStone = $_POST['tagstone'];
						if(isset($tagStone)){
							$arrayTagStones = array();
							foreach($tagStone['stone_id'] as $key => $val){
								$arrayTagStones= array('stone_id' => $tagStone['stone_id'][$key], 'pieces' => $tagStone['pcs'][$key], 'wt' => $tagStone['weight'][$key], 'uom_id' => $tagStone['uom_id'][$key], 'amount' => $tagStone['amount'][$key]);
							$upd= $this->$model->updateData($arrayTagStones,'tag_id',$id,'ret_taging_stone');
							
							}
						}

						$tagMaterials = $_POST['tagmaterials'];
						if(isset($tagMaterials)){
							$arrayTagMaterials = array();
							foreach($tagMaterials['material_id'] as $key => $val){
								$arrayTagMaterials= array('material_id' => $tagMaterials['material_id'][$key], 'wt' => $tagMaterials['weight'][$key], 'price' => $tagMaterials['amount'][$key]);
								$upd_another= $this->$model->updateData($arrayTagMaterials,'tag_id',$id,'ret_taging_other_materials');
							}
							
						}

						//update lot table if tag is completed
						$total_tag=$this->$model->get_tagged_details($addData['tag_lot_id']);
						if($total_tag['no_of_piece']==$total_tag['tagged_pieces'])
						{
						   $lot['tag_status']=1;
						   $upd_data= $this->$model->updateData($lot,'lot_no',$addData['tag_lot_id'],'ret_lot_inwards');
						}
						else
						{
								$lot['tag_status']=0;
						   		$upd_data= $this->$model->updateData($lot,'lot_no',$addData['tag_lot_id'],'ret_lot_inwards');
						}
							
					}
					if($this->db->trans_status()===TRUE)
					{
					    $log_data = array(
                        	'id_log'        => $this->session->userdata('id_log'),
                        	'event_date'	=> date("Y-m-d H:i:s"),
                        	'module'      	=> 'Tag',
                        	'operation'   	=> 'Edit',
                        	'record'        => $id,  
                        	'remark'       	=> 'Tag modified successfully'
                        );
                        $this->log_model->log_detail('insert','',$log_data);
						$this->db->trans_commit(); 
						$this->session->set_flashdata('chit_alert',array('message'=>'Tagging modified successfully','class'=>'success','title'=>'Edit Tagging'));
						
					}
					else
					{
						$this->db->trans_rollback();						 	
						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Edit Tagging'));
						
 					}
 					redirect('admin_ret_tagging/tagging/list');	
				break;
				
			
			case 'tag_update':
			            $addData = $_POST['lt_item'];
			            $tag_id  =$_POST['tag_id'];
			            $responseData=array();

						foreach($addData['lot_no'] as $key => $val)
			            {
							if($addData['cert_image'][$key] != "")
								$cert_img = uniqid().".jpg";
							else
								$cert_img = "";

			                 $arrayTag = array(
                                'id_branch' 		=> $addData['id_branch'], 
                                'cost_center' 		=> $addData['id_branch'], 
                                'tag_lot_id' 		=> $addData['lot_no'][$key],  
                                'product_id' 		=> $addData['lot_product'][$key], 
                                'design_id' 		=> $addData['lot_id_design'][$key], 
                                'id_sub_design' 	=> $addData['lot_id_sub_design'][$key], 
                                'design_for' 		=> 0,//$addData['design_for'][$key], 
                                'purity' 			=> $addData['purity'][$key], 
                                'id_orderdetails'=>(isset($addData['id_orderdetails'][$key]) ?($addData['id_orderdetails'][$key]!='' ? $addData['id_orderdetails'][$key]:NULL) :NULL),
                                'id_lot_inward_detail' => $addData['id_lot_inward_detail'][$key], 
                                'size' 				=> ($addData['size'][$key]!='' ? ($addData['size'][$key]!='null' ? $addData['size'][$key]:NULL):NULL), 
                                'piece' 			=> (($addData['no_of_piece'][$key]!='') ? $addData['no_of_piece'][$key]:NULL), 
                                'gross_wt' 			=> (($addData['gross_wt'][$key]!='') ? $addData['gross_wt'][$key]:NULL), 
                                'less_wt' 			=> (($addData['less_wt'][$key]!='') ? $addData['less_wt'][$key]:NULL), 
                                'net_wt' 			=> (($addData['net_wt'][$key]!='') ? $addData['net_wt'][$key]:NULL), 
                                'calculation_based_on' => $addData['calculation_based_on'][$key],
                                'retail_max_wastage_percent' => (($addData['wastage_percentage'][$key]!='') ? $addData['wastage_percentage'][$key]:NULL), 
                                'tag_mc_type' 		=> (($addData['id_mc_type'][$key]!='') ? $addData['id_mc_type'][$key]:NULL), 
                                'tag_mc_value' 	    => (($addData['making_charge'][$key]!='') ? $addData['making_charge'][$key]:0), 
                                'sell_rate' 		=> (($addData['sell_rate'][$key]!='') ? $addData['sell_rate'][$key]:NULL), 
                                'item_rate' 		=> (($addData['adjusted_item_rate'][$key]!='') ? $addData['adjusted_item_rate'][$key]:NULL), 
                                'sales_value' 		=> (($addData['sale_value'][$key]!='') ? $addData['sale_value'][$key]:NULL), 
                                'hu_id'             => (($addData['hu_id'][$key]!='') ? $addData['hu_id'][$key]:NULL), 
								'hu_id2'            => (($addData['hu_id2'][$key]!='') ? $addData['hu_id2'][$key]:NULL), 
                                //'image'				=> ((strlen($precious_imgs) > 0)?$precious_imgs : NULL),
                                //'tag_datetime'	  	=> $tag_datetime,
								"cert_no"			=> (($addData['cert_no'][$key]!='') ? $addData['cert_no'][$key]:NULL), 
								"cert_img"			=> $cert_img, 
								'manufacture_code' 			=> $addData['manufacture_code'][$key],
								'style_code' 				=> $addData['style_code'][$key],
								'remarks' 					=> $addData['remarks'][$key],
                                'updated_time'	  	=> date("Y-m-d H:i:s"),
                                'updated_by'      	=> $this->session->userdata('uid'),
                                );
                                
                                $this->db->trans_begin();
                                $status=$this->$model->updateData($arrayTag,'tag_id',$tag_id,'ret_taging');
                                if($status)
                                {
									$dir_certimg = $this->config->item('tag_cert_img_path').$tag_id."/";

									array_map('unlink', glob("$dir_certimg/*.*"));

									$base64_img = $addData['cert_image'][$key];

									if($base64_img != "") {

										if (!file_exists($dir_certimg)) {
											mkdir($dir_certimg, 0777, true);
										}

										$path = $dir_certimg.$cert_img;

										$this->save_base64_image($base64_img, $path);

									}

									//update lot table if tag is completed
									$total_tag=$this->$model->get_tagged_details($addData['id_lot_inward_detail'][$key]);
									if($total_tag['total_pieces']==$total_tag['tagged_pieces'])
									{
										$lot['tag_status']=1;
										$tagInsert= $this->$model->updateData($lot,'id_lot_inward_detail',$addData['id_lot_inward_detail'][$key],'ret_lot_inwards_detail');
									}

									$this->$model->deleteData('tag_id',$tag_id,'ret_taging_charges'); 

									$this->$model->deleteData('id_tagging',$tag_id,'ret_tagging_attributes'); 

									if($addData['charges'][$key])
									{
										$charges = (array)json_decode($addData['charges'][$key], true);

										foreach($charges as $charge)
										{
											$charge_data=array(
															'tag_id'                =>$tag_id,
															'charge_id'             =>$charge['charge_id'],
															'charge_value'          =>$charge['charge_value'],
															);
											$this->$model->insertData($charge_data,'ret_taging_charges');
										}
									}

									if($addData['attributes'][$key])
									{
										$attributes = (array)json_decode($addData['attributes'][$key], true);
		
										foreach($attributes as $attrs)
										{
											if($attrs['attr_id'] > 0 && $attrs['attr_val_id'] > 0) {

												$attr_data	=	array(
																	'id_tagging'    =>	$tag_id,
																	'attr_id'     	=>	$attrs['attr_id'],
																	'attr_val_id' 	=>	$attrs['attr_val_id'],
																	'created_on'  	=>	date("Y-m-d H:i:s"),
																	'created_by'  	=>	$this->session->userdata('uid')
																);
												$this->$model->insertData($attr_data,'ret_tagging_attributes');
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
                            	'module'      	=> 'Tag',
                            	'operation'   	=> 'Add',
                            	'record'        => $tag_id,  
                            	'remark'       	=> 'Tags Updated successfully.Ref no is'.$tag_id.'.'
                            );
                            $this->log_model->log_detail('insert','',$log_data);
    						$this->db->trans_commit();

    						//Generate TagQR Code
    						$QrcodeUrl=$this->generate_tagqrcode($tag_id);
    						//Generate TagQR Code

							$tagArr = $this->$model->get_tag_details_by_tag_id($tag_id);
							$tag_code = $tagArr['tag_code'];

    						$responseData=array('status'=>true,'message'=>'Tagging Updated successfully','trans_code'=>'','ref_no'=>$ref_no,'to_branch'=>'','qrcodeUrl'=>$QrcodeUrl,'tag_id'=>$tag_id,'tag_code'=>$tag_code);
    						$this->session->set_flashdata('chit_alert',array('message'=>'Tagging Updated successfully','class'=>'success','title'=>'Edit Tagging'));

    					}
    					else
    					{
    						$this->db->trans_rollback();
    						$responseData=array('status'=>false,'message'=>'Unable to proceed the requested process','trans_code'=>'','ref_no'=>'','to_branch'=>'');
    						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Edit Tagging'));
    					}
					echo json_encode($responseData);
                        
                    
			break;
			
						
			case 'update_status':			
						$data = array('status' => $status); 
						$status = $this->$model->updateData($id,$data);
						if($status)
						{
							$this->session->set_flashdata('chit_alert',array('message'=>'floor status updated as '.($status ? 'active' : 'inactive').' successfully.','class'=>'success','title'=>'floor  Status'));			
						}	
						else
						{
							$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'floor  Status'));
						}	
						redirect('admin_ret_lot/lot_inward/list');
			break;

			case 'generate_barcode':
						//$tagging=json_decode($_GET['tag'],true);
						$tagging = array(explode(',',$_GET['tag']));
						foreach($tagging[0] as $tag)
			 		    {
			 		        if($tag!='')
			 		    	{
                                $data=$this->$model->getTagDetails($tag);

								//Get Charge Code
								$charge_code = '';
								$charges=$this->$model->getTagCharges($tag, 1);
								//echo "<pre>"; print_r($charges);exit;
								foreach($charges as $charge)
								{
									$charge_code = $charge['code_charge'];
								}

								//echo "<pre>"; print_r($data);exit;
                                //Update No of Print Taken
                                $print_taken=$data['tot_print_taken'];
                                $print_taken++;
                                $this->$model->updateData(array('tot_print_taken'=>$print_taken),'tag_id',$tag,'ret_taging');
                                
                                if($data['tag_mark']==1)
                                {
                                     $this->$model->updateData(array('is_green_tag_printed'=>1),'tag_id',$tag,'ret_taging');
                                }else{
                                    $this->$model->updateData(array('is_green_tag_printed'=>0),'tag_id',$tag,'ret_taging');
                                }
                                
                                $insData=array(
                                'tag_id'	 =>$data['tag_id'],
                                'id_employee'=> $this->session->userdata('uid'),
                                'print_date' => date('Y-m-d H:i:s'),
                                );
                                $this->$model->insertData($insData,'ret_tag_duplicate_copy');
                                //Update No of Print Taken
                                
                                $this->load->library('phpqrcode/qrlib');
                                $SERVERFILEPATH = 'qrcode/'.$data['tag_code'];
                                if (!is_dir($SERVERFILEPATH)) {  
                                mkdir($SERVERFILEPATH, 0777, TRUE);
                                } 
                                $code = time().$data['tag_code'];
                                $text1= substr($code, 0,9);						
                                $folder = $SERVERFILEPATH;
                                $file_name1 = time().$data['tag_code']. ".png";
                                $file_name = $SERVERFILEPATH.'/'.$file_name1;
                                
                                QRcode::png($data['tag_code'],$file_name);  //Passing QR data
                                
                              
                                    $src['img'][]=array(
                                    'code'              =>$code,
                                    'product_name'      =>$data['product_name'],
                                    'design_name'       =>$data['design_name'],
									'sub_design_name'   =>$data['sub_design_name'],
                                    'product_id'        =>$data['product_short_code'],
                                    'gross_wt'          =>$data['gross_wt'],
									'net_wt'         	=>$data['net_wt'],
                                    'tag_id'            =>$data['tag_id'],
                                    'tag_code'          =>$data['tag_code'],
                                    'size'              =>$data['size'],
                                    'code_karigar'      =>$data['code_karigar'],
                                    'short_code'        =>$data['short_code'],
                                    'sales_value'       =>$data['sales_value'],
    			 			        'sales_mode'        =>$data['sales_mode'],
    			 			        'sell_rate'         =>$data['sell_rate'],
									'metal_type'		=>$data['metal_type'],
    			 			        'tag_mark'          =>$data['tag_mark'],
    			 			        'mc_cal_type'       =>$data['mc_cal_type'],
    			 			        'tag_mc_value'      =>$data['tag_mc_value'],
    			 			        'stn_amount'        =>$data['stn_amount'],
    			 			        'stn_wt'            =>$data['stn_wt'],
    			 			        'stuom'             =>$data['stuom'],
									'dia_amount'        =>$data['dia_amount'],
									'dia_wt'        	=>$data['dia_wt'],
									'dia_uom_name'      =>$data['dia_uom_name'],
									'dia_uom_short_code'=>$data['dia_uom_short_code'],
    			 			        'hu_id'             =>$data['hu_id'],
									'stuom_short_code'  =>$data['stuom_short_code'],
									'charge_code'		=>$charge_code,
    			 			         'retail_max_wastage_percent'  =>$data['retail_max_wastage_percent'],
                                    'src'               =>$this->config->item('base_url')."qrcode/".$data['tag_code'].'/'.$file_name1
                                    );
			 		        }
			 		    }
			 		   // echo "<pre>"; print_r($src);exit;
			 		    $html1 = $this->load->view('qrcode/tag_qrcode', $src,true);
			 		    $html = preg_replace('/>\s+</', "><", $html1); //Remove Blank page
			 			$this->load->helper(array('dompdf', 'file'));
						$dompdf = new DOMPDF();
						$dompdf->load_html($html);
						//$customPaper = array(0,0,220,111);
						//$customPaper = array(0,0,50,45);
						$dompdf->set_paper("portriat" );
						$dompdf->render();
						$dompdf->stream("Tag.pdf",array('Attachment'=>0));

				break;
			case 'generate_all_qrcode':
						//$tagging=json_decode($_GET['tag'],true);
							if($_GET['lot_id']!='')
			 		    	{
    			 		    	$tagging=$this->$model->getTagDetailsby_lot($_GET['lot_id']);
    			 		        //Update No of Print Taken
    			 		        foreach($tagging as $data)
    			 		        {
        			 		        $print_taken=$data['tot_print_taken'];
        							$print_taken++;
        							$this->$model->updateData(array('tot_print_taken'=>$print_taken),'tag_id',$data['tag_id'],'ret_taging');
        							
        							if($data['tag_mark']==1)
                                    {
                                         $this->$model->updateData(array('is_green_tag_printed'=>1),'tag_id',$tag,'ret_taging');
                                    }else{
                                        $this->$model->updateData(array('is_green_tag_printed'=>0),'tag_id',$tag,'ret_taging');
                                    }

        							$insData=array(
        							'tag_id'	 =>$data['tag_id'],
        							'id_employee'=> $this->session->userdata('uid'),
        							'print_date' => date('Y-m-d H:i:s'),
        							);
        							$this->$model->insertData($insData,'ret_tag_duplicate_copy');
        							//Update No of Print Taken

									//Get Charge Code
									$charge_code = '';
									$charges=$this->$model->getTagCharges($data['tag_id'], 1);
									//echo "<pre>"; print_r($charges);exit;
									foreach($charges as $charge)
									{
										$charge_code = $charge['code_charge'];
									}

        			 		    	$this->load->library('phpqrcode/qrlib');
        							$SERVERFILEPATH = 'qrcode/'.$data['tag_code'];
        							if (!is_dir($SERVERFILEPATH)) {  
        								mkdir($SERVERFILEPATH, 0777, TRUE);
        							} 
        							
        							
        							$code = time().$data['tag_code'];
        							$text1= substr($code, 0,9);						
        							$folder = $SERVERFILEPATH;
        							$file_name1 = time().$data['tag_code']. ".png";
        							$file_name = $SERVERFILEPATH.'/'.$file_name1;
        						    
        						    QRcode::png($data['tag_code'],$file_name);  //Passing QR data
        						     
        							$src['img'][]=array(
            			 			 'code'         =>$code,
            			 			 'product_name' =>$data['product_name'],
            			 			 'design_name'  =>$data['design_name'],
								     'sub_design_name' =>$data['sub_design_name'],
            			 			 'product_id'   =>$data['product_short_code'],
            			 			 'gross_wt'     =>$data['gross_wt'],
									 'net_wt'       =>$data['net_wt'],
            			 			 'tag_id'       =>$data['tag_id'],
            			 			 'tag_code'     =>$data['tag_code'],
            			 			 'size'         =>$data['size'],
            			 			 'code_karigar' =>$data['code_karigar'],
            			 			 'short_code'   =>$data['short_code'],
            			 			 'sales_value'  =>$data['sales_value'],
            			 			 'sell_rate'         =>$data['sell_rate'],
            			 			 'sales_mode'   =>$data['sales_mode'],
									 'metal_type'   =>$data['metal_type'],
            			 			 'tag_mark'     =>$data['tag_mark'],
            			 			 'mc_cal_type'  =>$data['mc_cal_type'],
    			 			        'tag_mc_value'  =>$data['tag_mc_value'],
    			 			        'stn_amount'        =>$data['stn_amount'],
    			 			        'stn_wt'            =>$data['stn_wt'],
									'dia_amount'        =>$data['dia_amount'],
									'dia_wt'        	=>$data['dia_wt'],
									'dia_uom_name'      =>$data['dia_uom_name'],
									'dia_uom_short_code'=>$data['dia_uom_short_code'],
    			 			        'hu_id'        	 	=>$data['hu_id'],
									'stuom_short_code'  =>$data['stuom_short_code'],
									'charge_code'		=>$charge_code,
    			 			        'retail_max_wastage_percent'  =>$data['retail_max_wastage_percent'],
            			 			 'src'          =>$this->config->item('base_url')."qrcode/".$data['tag_code'].'/'.$file_name1
            			 			 );
    							}
							}
			 		    //echo "<pre>"; print_r($src);exit;
			 		    $html1 = $this->load->view('qrcode/tag_qrcode', $src,true);
			 		    $html = preg_replace('/>\s+</', "><", $html1); //Remove Blank page
			 			$this->load->helper(array('dompdf', 'file'));
						$dompdf = new DOMPDF();
						$dompdf->load_html($html);
						//$customPaper = array(0,0,220,111);
						//$customPaper = array(0,0,50,45);
						$dompdf->set_paper("portriat" );
						$dompdf->render();
						$dompdf->stream("Tag.pdf",array('Attachment'=>0));

				break;

			case "bulk_edit":
					$data['uom']= $this->$model->getUOMDetails();
					$data['main_content'] = "tagging/bulk_edit" ;
		 			$this->load->view('layout/template', $data);
				break;
				
		}
	}
	
	function bt_tag_list($transCode,$ref_no,$toBranch){
		$fData = array( 'ref_no' => $ref_no, 'toBranch' => $toBranch);
		$data['list'] = $this->ret_tag_model->ajax_getTaggingList('','','',$fData,'');
		$data['trans_code'] = $transCode;
		$data['main_content'] = "tagging/bt_tag_list" ;
		$this->load->view('layout/template', $data);
	}

	public function updateTag()
	{
		$model = "ret_tag_model";
		$est_stones_item=(isset($_POST['est_stones_item'])  ? $_POST['est_stones_item']:'');

		//Image Upload
		$img_source=$this->input->post('img_source');
		$precious_imgs = "";
				$p_ImgData = json_decode($img_source); 
			if(sizeof($p_ImgData) > 0){
				foreach($p_ImgData as $precious){
					$imgFile = $this->base64ToFile($precious->src);
					$_FILES['precious'][] = $imgFile;
				}
			}
		if(!empty($_FILES)){
					$img_arr = array();
						$folder =  self::IMG_PATH."tag/"; 
						if (!is_dir($folder)) {  
							mkdir($folder, 0777, TRUE);
						}   
						if(isset($_FILES['precious'])){ 
							$precious_imgs = "";
							foreach($_FILES['precious'] as $file_key => $file_val){
								if($file_val['tmp_name'])
								{
									// unlink($folder."/".$product['image']); 
									$img_name =  "t_". mt_rand(120,1230).".jpg";
									$path = $folder."/".$img_name; 
									$result = $this->upload_img('image',$path,$file_val['tmp_name']);
									if($result){
										$precious_imgs = strlen($precious_imgs) > 0 ? $precious_imgs."#".$img_name : $img_name;
									}
								}
						}
				}
			}
		//Image Upload
		$calculation_based_on=$this->input->post('calculation_based_on');
		$gross_wt			=$this->input->post('gross_wt');
		$less_wt			=$this->input->post('less_wt');
		$net_wt				=$this->input->post('net_wt');
		$size 				=$this->input->post('size');
		$pieces 			=$this->input->post('pieces');
		$sale_value 		=$this->input->post('sale_value');
		$tag_id 			=$this->input->post('tag_id');
		$tag_mc_value 		=$this->input->post('tag_mc_value');
		$retail_max_wastage_percent 			=$this->input->post('retail_max_wastage_percent');
		$this->db->trans_begin();
		$updateData=array(
			'calculation_based_on'=>$calculation_based_on,
			'gross_wt'						=>$gross_wt,
			'less_wt'						=>($less_wt!='' ? $less_wt:NULL),
			'net_wt'						=>($net_wt!='' ? $net_wt:NULL),
			'size'							=>($size!='' ? $size:NULL),
			'piece'							=>($pieces!='' ? $pieces:NULL),
			'tag_mc_value'					=>($tag_mc_value!='' ? $tag_mc_value:''),
			'retail_max_wastage_percent'	=> ($retail_max_wastage_percent!='' ? $retail_max_wastage_percent:''),
			'sales_value'					=>($sale_value!='' ? $sale_value:0),
			'image'							=>($precious_imgs!='' ? $precious_imgs:NULL),
			'updated_time'					=> date("Y-m-d H:i:s"),
			'updated_by'    				=> $this->session->userdata('uid')
			
		);
		$updID= $this->$model->updateData($updateData,'tag_id',$tag_id,'ret_taging');

		if($updID)
		{

			if($est_stones_item)
			{
				$this->$model->deleteData('tag_id', $tag_id, 'ret_taging_stone'); 
				foreach($est_stones_item['stone_id'] as $key => $val)
				{
				
				$stone_data=array(
				'tag_id'=>$updID,
				'pieces'=>$est_stones_item['stone_pcs'][$key],
				'wt'=>$est_stones_item['stone_wt'][$key],
				'stone_id'=>$est_stones_item['stone_id'][$key],
				'amount'=>$est_stones_item['stone_price'][$key],
				);
				$stoneInsert = $this->$model->insertData($stone_data,'ret_taging_stone');
				}	
			}
		}
		if($this->db->trans_status()===TRUE)
		{
		    $log_data = array(
                        	'id_log'        => $this->session->userdata('id_log'),
                        	'event_date'	=> date("Y-m-d H:i:s"),
                        	'module'      	=> 'Tag',
                        	'operation'   	=> 'Edit',
                        	'record'        => $tag_id,  
                        	'remark'       	=> 'Tag modified successfully'
                        );
            $this->log_model->log_detail('insert','',$log_data);
			$this->db->trans_commit();
			$this->session->set_flashdata('chit_alert',array('message'=>'Tagging modified successfully','class'=>'success','title'=>'Edit Tagging'));
			$data=array('status'=>true,'msg'=>'Tagging modified successfully');
			
		}
		else
		{
			$this->db->trans_rollback();
			$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Edit Tagging'));
			$data=array('status'=>false,'msg'=>'Unable to proceed the requested process');
			
		}
		echo json_encode($data);
	}
	
		function generateTagsByRefNo($ref_no,$type){  //$type = new , duplicate
	    if($ref_no!='')
		{
			$model = "ret_tag_model";
			$tags = $this->$model->getTagByRefNo($ref_no);
			foreach($tags as $data)
			{	            
	            // Print Images Data Settings
				//$data_shown_status	=	$this->$model->get_ret_settings('print_img_in_tag');
	            //Update No of Print Taken
	            $print_taken = $data['tot_print_taken'];
	            $print_taken=$print_taken+1;
	            $this->$model->updateData(array('tot_print_taken'=>$print_taken),'tag_id',$data['tag_id'],'ret_taging');
	            /*$insData = array(
					            'tag_id'	 =>$data['tag_id'],
					            'id_employee'=> $this->session->userdata('uid'),
					            'print_date' => date('Y-m-d H:i:s'),
				            );
	            $this->$model->insertData($insData,'ret_tag_duplicate_copy');*/
	            $this->load->library('phpqrcode/qrlib');
	            $SERVERFILEPATH = 'qrcode/'.$data['tag_code'];
	            if (!is_dir($SERVERFILEPATH)) {  
	            	mkdir($SERVERFILEPATH, 0777, TRUE);
	            } 
	            $code = time().$data['tag_code'];
	            $text1= substr($code, 0,9);						
	            $folder = $SERVERFILEPATH;
	            $file_name1 = time().$data['tag_code']. ".png";
	            $file_name = $SERVERFILEPATH.'/'.$file_name1;
	            QRcode::png($data['tag_code'],$file_name);  //Passing QR data
	            //Get Charge Code
					$charge_code = '';
					$charges=$this->$model->getTagCharges($data['tag_id'], 1);
					//echo "<pre>"; print_r($charges);exit;
					foreach($charges as $charge)
					{
						$charge_code = $charge['code_charge'];
					}
	            
	           $src['img'][]=array(
                                    'code'              =>$code,
                                    'product_name'      =>$data['product_name'],
                                    'design_name'       =>$data['design_name'],
									'sub_design_name'   =>$data['sub_design_name'],
                                    'product_id'        =>$data['product_short_code'],
                                    'gross_wt'          =>$data['gross_wt'],
									'net_wt'         	=>$data['net_wt'],
                                    'tag_id'            =>$data['tag_id'],
                                    'tag_code'          =>$data['tag_code'],
                                    'size'              =>$data['size'],
                                    'code_karigar'      =>$data['code_karigar'],
                                    'short_code'        =>$data['short_code'],
                                    'sales_value'       =>$data['sales_value'],
                                    'sell_rate'         =>$data['sell_rate'],
    			 			        'sales_mode'        =>$data['sales_mode'],
									'metal_type'		=>$data['metal_type'],
    			 			        'tag_mark'          =>$data['tag_mark'],
    			 			        'mc_cal_type'       =>$data['tag_mc_type'],
    			 			        'tag_mc_value'      =>$data['tag_mc_value'],
    			 			        'stn_amount'        =>$data['stn_amount'],
    			 			        'stn_wt'            =>$data['stn_wt'],
    			 			        'stuom'             =>$data['stuom'],
									'dia_amount'        =>$data['dia_amount'],
									'dia_wt'        	=>$data['dia_wt'],
									'dia_uom_name'      =>$data['dia_uom_name'],
									'dia_uom_short_code'=>$data['dia_uom_short_code'],
    			 			        'hu_id'             =>$data['hu_id'],
									'stuom_short_code'  =>$data['stuom_short_code'],
									'charge_code'		=>$charge_code,
    			 			         'retail_max_wastage_percent'  =>$data['retail_max_wastage_percent'],
                                    'src'               =>$this->config->item('base_url')."qrcode/".$data['tag_code'].'/'.$file_name1
                                    );
	    	} 
    	} 
		$show_tag_img = $this->$model->get_ret_settings('print_img_in_tag');
		    $html1 = $this->load->view('qrcode/tag_qrcode', $src,true);
		    $html = preg_replace('/>\s+</', "><", $html1); //Remove Blank page
		    //echo "<pre>";print_r($src['img']);exit;
			$this->load->helper(array('dompdf', 'file'));
			$dompdf = new DOMPDF();
			$dompdf->load_html($html);
			//$customPaper = array(0,0,220,111);
			    //$customPaper = array(0,0,50,45);
			$dompdf->set_paper("portriat" );
			$dompdf->render();
			$dompdf->stream("Tag.pdf",array('Attachment'=>0));
	
	}
	
	public function get_tag_types(){
		$model = "ret_tag_model";
		$data = $this->$model->getAvailabletags();	  
		echo json_encode($data);
	}
	public function get_tag_purities(){
		$model = "ret_tag_model";
		$data = $this->$model->getAvailablePurities();	  
		echo json_encode($data);
	}
	public function get_lot_ids(){
		$model = "ret_tag_model";
		$data = $this->$model->getAvailableLots();	  
		echo json_encode($data);
	}
	public function getDesignNosBySearch(){
		$model = "ret_tag_model";
		$data = $this->$model->getAvailableDesigns($_POST['searchTxt']);	  
		echo json_encode($data);
	}
	public function getDesignDetails(){
		$model = "ret_tag_model";
		$data = $this->$model->getDesignDetails($_POST['designId']);	  
		echo json_encode($data);
	}
	public function getDesignPurityByDesignId(){
		$model = "ret_tag_model";
		$data = $this->$model->getDesignPurityByDesignId($_POST['designId']);	  
		echo json_encode($data);
	}
	public function getDesignStonesByDesignId(){
		$model = "ret_tag_model";
		$data = $this->$model->getDesignStonesByDesignId($_POST['designId']);	  
		echo json_encode($data);
	}
	public function getTagStoneByTagId(){
		$model = "ret_tag_model";
		$data = $this->$model->getTagStoneByTagId($_POST['tagId']);	  
		echo json_encode($data);
	}
	public function getTagMaterialByTagId(){
		$model = "ret_tag_model";
		$data = $this->$model->getTagMaterialByTagId($_POST['tagId']);	  
		echo json_encode($data);
	}
	public function getDesignMaterialsByDesignId(){
		$model = "ret_tag_model";
		$data = $this->$model->getDesignMaterialsByDesignId($_POST['designId']);	  
		echo json_encode($data);
	}
	public function getAvailableTaxGroups(){
		$model = "ret_tag_model";
		$data = $this->$model->getAvailableTaxGroups();	  
		echo json_encode($data);
	}
	public function getAvailableTaxGroupItems(){
		$model = "ret_tag_model";
		$data = $this->$model->getAvailableTaxGroupItems($_POST['taxGroupId']);	  
		echo json_encode($data);
	}
	public function getStoneItems(){
		$model = "ret_tag_model";
		$data = $this->$model->getAvailableStones();	  
		echo json_encode($data);
	}
	
	public function getOtherCharges(){
		$model = "ret_tag_model";
		$data = $this->$model->getChargesDetails();	  
		echo json_encode($data);
	}
	
	public function getStoneTypes(){
		$model = "ret_tag_model";
		$data = $this->$model->getAvailableStoneTypes();	  
		echo json_encode($data);
	}
	
	public function get_ActiveUOM(){
		$model = "ret_tag_model";
		$data = $this->$model->get_ActiveUOM();	  
		echo json_encode($data);
	}
	
	public function getAvailableMaterials(){
		$model = "ret_tag_model";
		$data = $this->$model->getAvailableMaterials();	  
		echo json_encode($data);
	}
	
	function get_metal_rates_by_branch()
	{
		$model="ret_tag_model";
		$id_branch=$this->input->post('id_branch');
		$data=$this->$model->get_branchwise_rate($id_branch);
		echo json_encode($data);
	}
	
    //Bulk Edit
	function get_tag_number()
	{
			$model="ret_tag_model";
			$tag_lot_id=$this->input->post('tag_lot_id');
			$id_branch=$this->input->post('id_branch');
			$tag_id=$this->input->post('tag_id');
			$data=$this->$model->get_tag_numbr($tag_lot_id,$id_branch,$tag_id);
			echo json_encode($data);
	}
	function get_prod_by_tagno()
	{
			$model="ret_tag_model";
			$tag_lot_id=$this->input->post('tag_lot_id');
			$prod_name=$this->input->post('prod_name');
			$data=$this->$model->get_prod_by_tagno($tag_lot_id,$prod_name);
			echo json_encode($data);
	}

	function get_tag_details()
	{
			$model="ret_tag_model";
			$data=$this->$model->get_tag_details();
			echo json_encode($data);
	}

    function update_tagging_data()
	{
			$model="ret_tag_model";
			$service = $this->admin_settings_model->get_service_by_code('tag_edit');
			$retail_max_wastage_percent =$this->input->post('retail_max_wastage_percent');
			$design_id                  =$this->input->post('design_id');
			$reqdata                    =$this->input->post('req_data');
			$post_otp                   =$this->input->post('tag_otp');
			$session_otp                =$this->session->userdata('tagging_otp');

			$otp                        = array(explode(',',$session_otp));
			$tag_ids['record']='';
			$this->db->trans_begin();
			if($service['serv_sms']==1)
			{
    			foreach($otp[0] as $OTP)
    			{
					if($OTP==$post_otp)
					{
						if(time() >= $this->session->userdata('tagging_otp_exp'))
						{
							$this->session->unset_userdata('tagging_otp');
							$this->session->unset_userdata('tagging_otp_exp');
							$status=array('status'=>false,'msg'=>'OTP has been expired');
							$update_otp=false;
						}
						else
						{
							$updData=array(
											'is_verified'=>1,
											'verified_time'=>date("Y-m-d H:i:s"),
										  );
							  $update_otp=$this->$model->updateData($updData,'otp_code',$post_otp,'otp');
						}
						break;
					}
					else
					{	
						$status=array('status'=>false,'msg'=>'Please Enter Valid OTP');
						$update_otp=false;
					}
    			}
	        }
	        else
	        {
	            $update_otp=true;
	        }
    		  if($update_otp)
    		  {
				$type = 0;
				foreach($reqdata as $data)
				{
					$tagId = $data['tag_id'];
					$tag_ids['record'].=$tagId.',';
					$employee= $this->session->userdata('uid');
					$bulk_edit_options = $data['bulk_edit_options'];

					$type = $bulk_edit_options;
					
					if($bulk_edit_options == 1) 
					{
						$upd_data=array(
						'sales_value'               =>$data['sales_value'],
						'tag_mc_value'              =>$data['tag_mc_value'],
						'tag_mc_type'               =>$data['id_mc_type'],
						'retail_max_wastage_percent'=>$data['retail_max_wastage_percent'],
						'updated_time'              =>date("Y-m-d H:i:s"),
						'updated_by'                =>$employee,
						);
						
						$tag_update_id=$this->$model->updateData($upd_data,'tag_id',$tagId,'ret_taging');
						$update_status = $tag_update_id > 0 ? true : false;
						if(!$update_status)
							break;
					}
					else if($bulk_edit_options == 2) 
					{
						if($data['attribute_type'] == 1)
						{
							foreach($data['attributes'] as $attrkey => $attrval)
							{
								$curr_des_attrs = $this->$model->getTagAttributes($tagId, $attrval['attr_name']);

								$total_count_attrs = count($curr_des_attrs);

								if($total_count_attrs == 0)
								{
									$tag_attrs = array(
										"id_tagging"			=>	$tagId,
										"attr_id"  				=>  $attrval['attr_name'],
										"attr_val_id"    		=>  $attrval['attr_value'],
										'created_on'	  		=> 	date("Y-m-d H:i:s"),
										'created_by'      		=> 	$this->session->userdata('uid')
									);

									$attr_tag_id = $this->$model->insertData($tag_attrs,'ret_tagging_attributes');
									$update_status = $attr_tag_id > 0 ? true : false;
									if(!$update_status)
										break 2;
								}
								else
								{
									$update_status = true;
								}
							}
						}
						else if($data['attribute_type'] == 2)
						{
							$update_status = $this->$model->deleteData('id_tagging',$tagId,'ret_tagging_attributes');
							if(!$update_status)
								break;
						}
					}
				}
				if($update_status)
				{
					if($type == 1) {
						$remark = 'Tags Modified Successfully. OTP : '.$post_otp;
						$message = 'Tag Details Updated Successfully';
					} else if($type == 2) {
						$remark = 'Tag Attributes Modified Successfully. OTP : '.$post_otp;
						$message = 'Tag Attributes Updated Successfully';
					}
				    $log_data = array(
                        	'id_log'        => $this->session->userdata('id_log'),
                        	'event_date'	=> date("Y-m-d H:i:s"),
                        	'module'      	=> 'Tag',
                        	'operation'   	=> 'Bulk Edit',
                        	'record'        => NULL,  
                        	'remark'       	=> $remark
                        );
                    $this->log_model->log_detail('insert','',$log_data);
					$this->db->trans_commit();
					if($post_otp!='')
					{
					     $update_otp=$this->$model->updateData($tag_ids,'otp_code',$post_otp,'otp');
					}
					$status=array('status'=>true,'msg'=>$message);	
				}
				else
				{
					$this->db->trans_rollback();
					$status=array('status'=>false,'msg'=>'Unable to Proceed Your Request');	
				}
    		  }
		  	echo json_encode($status);
	}

	function admin_approval()
	{
		$model="ret_tag_model";
		$data           =$this->$model->get_ret_settings('otp_approval_nos');
		$mobile_num     = array(explode(',',$data));
		$sent_otp='';
		$service = $this->admin_settings_model->get_service_by_code('tag_edit');
		if($service['serv_sms']==1)
		{
    		foreach($mobile_num[0] as $mobile)
    		{
    				if($mobile)
    				{
    					 $this->db->trans_begin();
    					$this->session->unset_userdata("tagging_otp");
    					$OTP = mt_rand(100001,999999);
    					$sent_otp.=$OTP.',';
    					$this->session->set_userdata('tagging_otp',$sent_otp);
    					$this->session->set_userdata('tagging_otp_exp',time()+60);
                        $message="Hi Your OTP  For Tag Editing is :  ".$OTP." Will expire within 1 minute";
    					$otp_gen_time = date("Y-m-d H:i:s");
    					$insData=array(
    					'mobile'=>$mobile,
    					'otp_code'=>$OTP,
    					'otp_gen_time'=>date("Y-m-d H:i:s"),
    					'module'=>'Bulk Tag Edit',
    					'id_emp'=>$this->session->userdata('uid')
    					);
    					$insId = $this->$model->insertData($insData,'otp');
    						 if($insId)
    						 {
    						 	$this->send_sms($mobile,$message,$service['dlt_te_id']);
    						 	$status=array('status'=>true,'msg'=>'OTP sent Successfully','sms_req'=>$service['serv_sms']);	
    						 }else{
    						     $status=array('status'=>false,'msg'=>'Unabe To Send Try Again');
    						 }
    				}
    		}
	    }
	    else
	    {
	        $status=array('status'=>true,'sms_req'=>$service['serv_sms']);	
	    }
		echo json_encode($status);
	}

	function resendotp()
	{
		$model="ret_tag_model";
		$data           =$this->$model->get_ret_settings('otp_approval_nos');
		$mobile_num     = array(explode(',',$data));
		$sent_otp='';
		foreach($mobile_num[0] as $mobile)
		{
				if($mobile)
				{
					$this->db->trans_begin();
					$this->session->unset_userdata("tagging_otp");
					$this->session->unset_userdata("tagging_otp_exp");
					$OTP = mt_rand(100001,999999);
					$sent_otp.=$OTP.',';
					$this->session->set_userdata('tagging_otp',$sent_otp);
					$this->session->set_userdata('tagging_otp_exp',time()+60);
					$message="Hi Your OTP  For Tag Editing is :  ".$OTP." Will expire within 1 minute.";
					$otp_gen_time = date("Y-m-d H:i:s");
					$insData=array(
					'mobile'=>$mobile,
					'otp_code'=>$OTP,
					'otp_gen_time'=>date("Y-m-d H:i:s"),
					'module'=>'Bulk Tag Edit',
					'send_resend'=>1,
					'id_emp'=>$this->session->userdata('uid')
					);
					$insId = $this->$model->insertData($insData,'otp');
						 if($insId)
						 {
						 	$this->send_sms($mobile,$message);
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

	function send_sms($mobile,$message,$dlt_te_id)
	{
		if($this->config->item('sms_gateway') == '1'){
		    $this->sms_model->sendSMS_MSG91($mobile,$message,"",$dlt_te_id);		
		}
		elseif($this->config->item('sms_gateway') == '2'){
	        $this->sms_model->sendSMS_Nettyfish($mobile,$message,'trans');	
		}
	}

	function get_lot_inward_details()
	{
		$model="ret_tag_model";
		$lot_no=$this->input->post('lot_no');
		$lot_product=$this->input->post('lot_product');
		$lot_id_design=$this->input->post('lot_id_design');
		$data=$this->$model->get_lot_inward_details($lot_no,$lot_product,$lot_id_design);
		echo json_encode($data);
	}

	function get_lot_products()
	{
		$model="ret_tag_model";
		$lot_no=$this->input->post('lot_no');
		$searchTxt=$this->input->post('searchTxt');
		$data=$this->$model->get_lot_products($lot_no,$searchTxt);
		echo json_encode($data);
	}

	function get_lot_designs()
	{
		$model="ret_tag_model";
		$lot_no=$this->input->post('lot_no');
		$lot_product=$this->input->post('lot_product');
		$searchTxt=$this->input->post('searchTxt');
		$data=$this->$model->get_lot_designs($lot_no,$lot_product,$searchTxt);
		echo json_encode($data);
	}
	
	function get_tagging_details()
	{
	   $model="ret_tag_model";
	   $range['from_date']  = $this->input->post('from_date');
	   $range['to_date']  = $this->input->post('to_date');
	   $data=$this->$model->get_tagging_details($range['from_date'],$range['to_date']);
	   echo json_encode($data);
	}
	
	function get_tag_detail_list()
	{
		$data['main_content'] = "tagging/list" ;
		$this->load->view('layout/template', $data);
	}
	
	function lot_tag_detail()
	{
	     $model="ret_tag_model";
        $range['from_date']  = $this->input->post('from_date');
        $range['to_date']   = $this->input->post('to_date');
        $tag_lot_id         =$this->input->post('tag_lot_id');
        $id_employee        =$this->input->post('id_employee');
        $list = $this->$model->ajax_getTaggingList($range['from_date'],$range['to_date'],$tag_lot_id,'',$id_employee);	 						
        $access = $this->admin_settings_model->get_access('admin_ret_tagging/tagging/list');
        $data = array(
        'list'  => $list,
        'access'=> $access
        );  
        echo json_encode($data);
	}
	
	//Duplicate
	function get_duplicate_tag()
	{
		$model="ret_tag_model";
		$data=$_POST;
		$tag=$this->$model->get_duplicate_tag($data);
		echo json_encode($tag);
	}

	function send_tag_otp()
	{
		$model="ret_tag_model";
		$data           =$this->$model->get_ret_settings('otp_approval_nos');
		$mobile_num     = array(explode(',',$data));
		$sent_otp='';
		$service = $this->admin_settings_model->get_service_by_code('duplicate_tag');	
    	 if($service['serv_sms']==1)
    	 {
    		foreach($mobile_num[0] as $mobile)
    		{
    			if($mobile)
    			{
    				$this->db->trans_begin();
    				$this->session->unset_userdata("tagging_otp");
    				$OTP = mt_rand(100001,999999);
    				$sent_otp.=$OTP.',';
    				$this->session->set_userdata('tagging_otp',$sent_otp);
    				$this->session->set_userdata('tagging_otp_exp',time()+60);
    				$message="Hi Your OTP  For Duplicate Tag is :  ".$OTP." Will expire within 60Sec.";
    				$otp_gen_time = date("Y-m-d H:i:s");
    				$insData=array(
    				'mobile'=>$mobile,
    				'otp_code'=>$OTP,
    				'otp_gen_time'=>date("Y-m-d H:i:s"),
    				'module'=>'Duplicate Tag Edit',
    				'id_emp'=>$this->session->userdata('uid')
    				);
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
    		     $status=array('status'=>true,'msg'=>'OTP sent Successfully','sms_req'=>$service['serv_sms']);	
    		 }else{
    		      $this->db->trans_rollback();
    		     $status=array('status'=>false,'msg'=>'Unabe To Send Try Again');
    		 }
	    }
	    else
	    {
	        $status=array('status'=>true,'sms_req'=>$service['serv_sms']);	
	    }
		echo json_encode($status);
	}

	function verify_otp()
	{
		$model="ret_tag_model";
		$post_otp=$this->input->post('otp');
		$session_otp=$this->session->userdata('tagging_otp');
		$otp = array(explode(',',$session_otp));
		$this->db->trans_begin();
		if($post_otp!='')
		{
    		foreach($otp[0] as $OTP)
    		{
    			if($OTP==$post_otp)
    			{
    				if(time() >= $this->session->userdata('tagging_otp_exp'))
    				{
    					$this->session->unset_userdata('tagging_otp');
    					$this->session->unset_userdata('tagging_otp_exp');
    					$status=array('status'=>false,'msg'=>'OTP has been expired');
    				}
    				else
    				{
    					$this->db->trans_commit();
    					$updData=array('is_verified'=>1,'verified_time'=>date("Y-m-d H:i:s"));
    					$updStatus=$this->$model->updateData($updData,'otp_code',$post_otp,'otp');
    					$status=array('status'=>true,'msg'=>'OTP Verified Successfully.');
    				}
    				break;
    			}
    			else
    			{	
    				$status=array('status'=>false,'msg'=>'Please Enter Valid OTP');
    			}
    	    }
	   }else{
	       $status=array('status'=>false,'msg'=>'Please Enter Valid OTP');
	   }
	  	echo json_encode($status);
	}
	//Duplicate
	
	//Tag scan
	function get_tag_scan_details()
	{
		$model="ret_tag_model";
		$tag_id=$this->input->post('tag_id');
		$print_taken=$this->input->post('print_taken');
		$tag_details=$this->$model->get_entry_records($tag_id);
		$status=$this->$model->get_scanned_details($tag_id);
		$id_branch=$this->session->userdata('id_branch');
		//$id_branch=1;
		if($status)
		{
			$this->db->trans_begin();
			if($this->session->userdata('branch_settings')==1)
			{
				if($tag_details['current_branch']==$id_branch)
				{
					$insData=array(
					'tag_id'	=>$tag_id,
					'id_branch' => ($id_branch!='' ? $id_branch:NULL),
					'date_add'	=> date("Y-m-d H:i:s"),
					'created_by'=> $this->session->userdata('uid'),
					);
					$insId=$this->$model->insertData($insData,'ret_tag_scanned');
					if($insId)
					{
						$data=array('status'=>TRUE,'tag_details'=>$tag_details,'msg'=>'Tag Scanned Successfully.');	
					}else{
						$data=array('status'=>FALSE,'msg'=>'Unable To Proceed Your Request');
					}
				}
				else{
					$data=array('status'=>FALSE,'msg'=>'Invalid Branch');

				}
			}
			else
			{
				$insData=array(
				'tag_id'	=>$tag_id,
				'id_branch' => ($id_branch!='' ? $id_branch:NULL),
				'date_add'	=> date("Y-m-d H:i:s"),
				'created_by'=> $this->session->userdata('uid'),
				);
				$insId=$this->$model->insertData($insData,'ret_tag_scanned');
				if($insId)
				{
					$data=array('status'=> TRUE,'tag_details'=>$tag_details,'msg'=>'Tag Scanned Successfully.');	
				}
				else{
						$data=array('status'=>FALSE,'msg'=>'Unable To Proceed Your Request');
					}
			}
		}
		else{
			$data=array('status'=>FALSE,'msg'=>'Tag Already Scanned.');	
		}
		if($this->db->trans_status()===TRUE)
		{
			$this->db->trans_commit();
		}else{
			$this->db->trans_rollback();		
		}
		echo json_encode($data);	
	}
	//Tag scan

	function get_order_details()
	{
		$model="ret_tag_model";
		$data=$this->$model->get_order_details($_POST['lot_no']);
		echo json_encode($data);
	}
	
	function get_tag_marking()
	{
		$model="ret_tag_model";
		$responseData=array();
		if($_POST['est_no']=='')
		{
		    $data=$this->$model->get_tag_marking($_POST);
		    $responseData=array('status'=>true,'data'=>$data);
		}
		else if($_POST['est_no']!='')
		{
	    	if($this->session->userdata('branch_settings')==1 && ($_POST['id_branch']!='' && $_POST['id_branch']!=0))
	    	{
	    	    $data=$this->$model->getEstTaglist($_POST);
	    	    $responseData=array('status'=>true,'data'=>$data);
	    	}
	    	else if($this->session->userdata('branch_settings')==1 && ($_POST['id_branch']=='' || $_POST['id_branch']==0))
	    	{
	    	   $responseData=array('status'=>false,'message'=>'Invalid Branch..');
	    	}
		}
		
		echo json_encode($responseData);
	}

	function update_tag_mark($status,$id)
	{
		$model="ret_tag_model";
		$this->db->trans_begin();
		$data = array('tag_mark'  		=> $status,
					   'updated_time' 	=> date("Y-m-d H:i:s"),
					   'updated_by' 	=> $this->session->userdata('uid')
					);
		$status = $this->$model->updateData($data,'tag_id',$id,'ret_taging');
		if($this->db->trans_status()===TRUE)
         {
		 	$this->db->trans_commit();
			$this->session->set_flashdata('chit_alert',array('message'=>'Tag Mark updated successfully.','class'=>'success','title'=>'Tagging'));			
		}	
		else
		{
			$this->db->trans_rollback();
			$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Tagging'));
		}	
		redirect('admin_ret_tagging/tagging/tag_mark');	
	}
	
	
	function get_tag_edit_det()
	{
		$model="ret_tag_model";
		$data=$this->$model->get_tag_edit_det($_POST);
		echo json_encode($data);
	}
	
	function update_tag()
	{
		$model="ret_tag_model";
		$reqdata   = $this->input->post('req_data');
		$id_design   = $this->input->post('id_design');
		$id_sub_design   = $this->input->post('id_sub_design');
		$id_size   = $this->input->post('id_size');
		$this->db->trans_begin();
		foreach($reqdata as $tag)
		{
		    $data = array(
		               'design_id'  	=> ($id_design!='' ? $id_design:$tag['id_design']),
		               'size'  	        => ($id_size!='' ? ($id_size!='' ? $id_size:NULL):($tag['id_size']!='' ?$tag['id_size'] :NULL)),
		               'id_sub_design' => ($id_sub_design!='' ? ($id_sub_design!='' ? $id_sub_design:NULL):($tag['id_sub_design']!='' ?$tag['id_sub_design'] :NULL)),
					   'updated_time' 	=> date("Y-m-d H:i:s"),
					   'updated_by' 	=> $this->session->userdata('uid')
					);
			$status = $this->$model->updateData($data,'tag_id',$tag['tag_id'],'ret_taging');
			//print_r($this->db->last_query());exit;
			if($status)
			{
			    $log_data = array(
                            'id_log'        => $this->session->userdata('id_log'),
                            'event_date'    => date("Y-m-d H:i:s"),
                            'module'        => 'Billing',
                            'operation'     => 'Update',
                            'record'        =>  $tag['tag_id'],  
                            'remark'        => 'Tag Design Updated successfully'
                            );
                $this->log_model->log_detail('insert','',$log_data);
			}
		}
		
		if($this->db->trans_status()===TRUE)
         {
		 	$this->db->trans_commit();
			$this->session->set_flashdata('chit_alert',array('message'=>'Tag  updated successfully.','class'=>'success','title'=>'Tagging'));			
		}	
		else
		{
			$this->db->trans_rollback();
			$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Tagging'));
		}	
	}
	
	function get_employee()
	{
		$model="ret_tag_model";
		$data=$this->$model->get_employee();
		echo json_encode($data);
	}
	
	function get_ActiveSize()
	{
		$model="ret_tag_model";
		$data=$this->$model->get_ActiveSize($_POST['id_product']);
		echo json_encode($data);
	}
	
	//Order link
	public function getTaggingBySearch(){
		$model = "ret_tag_model";
		$data = $this->$model->getTaggingBySearch($_POST['searchTxt'],$_POST['id_branch']);	  
		echo json_encode($data);
	}
	
	public function getOrdersBySearch()
	{
	    $model = "ret_tag_model";
		$data = $this->$model->getOrdersBySearch($_POST['searchTxt'],$_POST['id_branch'],$_POST['id_product'],$_POST['id_design']);	  
		echo json_encode($data);
	}
	
	function getOrderDetailBySearch()
	{
	    $model = "ret_tag_model";
		$data = $this->$model->getOrderDetailBySearch($_POST['id_customerorder'],$_POST['id_product'],$_POST['id_design']);	  
		echo json_encode($data);
	}
	
		function update_order_link()
	{
	    $model="ret_tag_model";
	    $set_model="admin_settings_model";
		$reqdata   = $this->input->post('req_data');
		$this->db->trans_begin();
		foreach($reqdata as $tag)
		{
		    $data = array(
		               'id_orderdetails'=> $tag['id_orderdetails'],
					   'updated_time' 	=> date("Y-m-d H:i:s"),
					   'updated_by' 	=> $this->session->userdata('uid')
					);
			$status = $this->$model->updateData($data,'tag_id',$tag['tag_id'],'ret_taging');
			$this->$model->updateData(array('orderstatus'=>4,'deliverydate'=> date("Y-m-d H:i:s")),'id_orderdetails',$tag['id_orderdetails'],'customerorderdetails');
			$this->$model->updateData(array('orderstatus'=>4,'deliveredon'=> date("Y-m-d H:i:s")),'id_order',$tag['id_orderdetails'],'joborder');
			if($status)
			{
			    $service = $this->$set_model->get_service_by_code('CUS_ORD_DEL');
			    
			    if($service['serv_whatsapp'] == 1)
				{
				    $sms_data=$this->admin_usersms_model->Get_service_code_sms('CUS_ORD_DEL',$tag['id_orderdetails'],'');
					if($sms_data['mobile']!='')
					{
					    $whatsapp=$this->admin_usersms_model->send_whatsApp_message($sms_data['mobile'],$sms_data['message']);
					}
				}
			    
			    $log_data = array(
                            'id_log'        => $this->session->userdata('id_log'),
                            'event_date'    => date("Y-m-d H:i:s"),
                            'module'        => 'Tagging',
                            'operation'     => 'Update',
                            'record'        =>  $tag['tag_id'],  
                            'remark'        => 'Order Link Updated successfully'
                            );
                $this->log_model->log_detail('insert','',$log_data);
			}
		}
		
		if($this->db->trans_status()===TRUE)
         {
		 	$this->db->trans_commit();
			$this->session->set_flashdata('chit_alert',array('message'=>'Order  Linked successfully.','class'=>'success','title'=>'Tagging'));			
		}	
		else
		{
			$this->db->trans_rollback();
			$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Tagging'));
		}
	}
	
	
	//Order link
	
	//Generate Tag Code
	function generateTagCode($lastTagCode){
	    $tagCode        = $lastTagCode; // Saple : 1-A12345 or 1-12345
        $code_det       = explode('-',$tagCode);
        $alpha_char		='';
        // Split Alphabet and Serial number
        if(preg_match('/[A-Z]+\K/',$code_det[1]))
        {
        	$str_split = preg_split('/[A-Z]+\K/',$code_det[1]);
        	$tag_number=$str_split[1];
        	$alpha_char=$str_split[0];
        }else{
        	$tag_number=$code_det[1];
        }
        //  Increment Number
        if($tag_number!=NULL && $tag_number!='' && $tag_number!=99999)
        {
        	$number = (int) $tag_number;
        	$number++;
        	$code_number=str_pad($number, 5, '0', STR_PAD_LEFT);
        }
        else
        {
        	$code_number=str_pad('1', 5, '0', STR_PAD_LEFT);
        }
        //  Increment Alphabet if reached 99999
        if($tag_number==99999)
        {
        	if($alpha_char == '')
        	{
        		$alpha_char='A';
        	}else{
        		$alpha_char=++$alpha_char;
        	}
        	
        } 
        return $alpha_char.''.$code_number;
	}
	
	//Generate Tag Code
	
	public function validate_huid()
	{
	    $model = "ret_tag_model";
		$sku_id   = $this->input->post('sku_id');
		$data = $this->$model->validate_huid($sku_id);
        if(count($data) > 0)
		{
			$validate = true;
		}else{
			$validate = false;
		}
		echo json_encode($validate);
	}
	
	
	function generate_tagqrcode($tag)
	{
	    $model = "ret_tag_model";
        $data=$this->$model->getTagDetails($tag);
        //Get Charge Code
        $charge_code = '';
        $charges=$this->$model->getTagCharges($tag, 1);
        //echo "<pre>"; print_r($charges);exit;
        foreach($charges as $charge)
        {
            $charge_code = $charge['code_charge'];
        }
        
        //echo "<pre>"; print_r($data);exit;
        //Update No of Print Taken
        $print_taken=$data['tot_print_taken'];
        $print_taken++;
        $this->$model->updateData(array('tot_print_taken'=>$print_taken),'tag_id',$tag,'ret_taging');
        
        if($data['tag_mark']==1)
        {
             $this->$model->updateData(array('is_green_tag_printed'=>1),'tag_id',$tag,'ret_taging');
        }else{
            $this->$model->updateData(array('is_green_tag_printed'=>0),'tag_id',$tag,'ret_taging');
        }
        
        $insData=array(
        'tag_id'	 =>$data['tag_id'],
        'id_employee'=> $this->session->userdata('uid'),
        'print_date' => date('Y-m-d H:i:s'),
        );
        $this->$model->insertData($insData,'ret_tag_duplicate_copy');
        //Update No of Print Taken
        
        $this->load->library('phpqrcode/qrlib');
        $SERVERFILEPATH = 'qrcode/'.$data['tag_code'];
        if (!is_dir($SERVERFILEPATH)) {  
        mkdir($SERVERFILEPATH, 0777, TRUE);
        } 
        $code = time().$data['tag_code'];
        $text1= substr($code, 0,9);						
        $folder = $SERVERFILEPATH;
        $file_name1 = time().$data['tag_code']. ".png";
        $file_name = $SERVERFILEPATH.'/'.$file_name1;
        QRcode::png($data['tag_code'],$file_name);  //Passing QR data
        $src['img'][]=array(
        'code'              =>$code,
        'product_name'      =>$data['product_name'],
        'design_name'       =>$data['design_name'],
		'sub_design_name'   =>$data['sub_design_name'],
        'product_id'        =>$data['product_short_code'],
        'gross_wt'          =>$data['gross_wt'],
        'net_wt'         	=>$data['net_wt'],
        'tag_id'            =>$data['tag_id'],
        'tag_code'          =>$data['tag_code'],
        'size'              =>$data['size'],
        'code_karigar'      =>$data['code_karigar'],
        'short_code'        =>$data['short_code'],
        'sales_value'       =>$data['sales_value'],
        'sales_mode'        =>$data['sales_mode'],
        'sell_rate'         =>$data['sell_rate'],
        'metal_type'		=>$data['metal_type'],
        'tag_mark'          =>$data['tag_mark'],
        'mc_cal_type'       =>$data['mc_cal_type'],
        'tag_mc_value'      =>$data['tag_mc_value'],
        'stn_amount'        =>$data['stn_amount'],
        'stn_wt'            =>$data['stn_wt'],
        'stuom'             =>$data['stuom'],
		'dia_amount'        =>$data['dia_amount'],
		'dia_wt'        	=>$data['dia_wt'],
		'dia_uom_name'      =>$data['dia_uom_name'],
		'dia_uom_short_code'=>$data['dia_uom_short_code'],
        'hu_id'             =>$data['hu_id'],
        'stuom_short_code'  =>$data['stuom_short_code'],
        'charge_code'		=>$charge_code,
        'retail_max_wastage_percent'  =>$data['retail_max_wastage_percent'],
        'src'               =>$this->config->item('base_url')."qrcode/".$data['tag_code'].'/'.$file_name1
        );
        
        
        $html1 = $this->load->view('qrcode/tag_qrcode', $src,true);
 	    $html = preg_replace('/>\s+</', "><", $html1); //Remove Blank page
 		$this->load->helper(array('dompdf', 'file'));
		$dompdf = new DOMPDF();
		
		$dompdf->load_html($html);
		$dompdf->set_paper("portriat" );
		$dompdf->render(); 
		$output = $dompdf->output();

		$file = $folder.'/'.$file_name1 = time().$data['tag_code']. ".pdf";;
		file_put_contents($file,$output);
		
		return $this->config->item('base_url').''.$file;
						
	}
	
	
	
		//GET DESIGN PRODUCTS
	function get_active_design_products()
	{
	     $model="ret_tag_model";
	     $data=$this->$model->get_active_design_products($_POST);
	     echo json_encode($data);
	}
	
	function get_ActiveSubDesingns()
	{
	     $model="ret_tag_model";
	     $data=$this->$model->get_ActiveSubDesingns($_POST);
	     echo json_encode($data);
	}
	//GET DESIGN PRODUCTS
	
	//V.A and M.C Settings
	function get_wastage_settings_details()
	{
	     $model="ret_tag_model";
	     $data=$this->$model->get_wastage_settings_details();
	     echo json_encode($data);
	}
	//V.A and M.C Settings

	//Attributes for tagging

	function get_attributes_from_subdesign()
	{
		$model="ret_catalog_model";
		$this->load->model($model);

		$product_id 	= $_POST['product_id'];
		$design_id 		= $_POST['design_id'];
		$subdesign_id 	= $_POST['subdesign_id'];

	    $data=$this->$model->get_attributes_from_subdesign($product_id, $design_id, $subdesign_id);
	    echo json_encode($data);
	}

	function get_product_charges()
	{
		$prod_id = $_POST['prod_id'];

		$model="ret_catalog_model";

		$this->load->model($model);

		$data = $this->$model->get_product_charges($prod_id);

		echo json_encode($data);
	}
	
	function get_tag_charges($tag_id)
	{
		$model="ret_tag_model";

		$data = $this->$model->getTagCharges($tag_id);

		echo json_encode($data);

	}

	function get_tag_attributes($tag_id)
	{
		$model="ret_tag_model";

		$data = $this->$model->getTagAttributes($tag_id);

		echo json_encode($data);
	}

	function save_base64_image($base64_string, $path)
	{

		$data = str_replace('data:image/png;base64,', '', $base64_string);

		$data = str_replace(' ', '+', $data);

		$data = base64_decode($data);

		$success = file_put_contents($path, $data);

		$data = base64_decode($data); 

		$source_img = imagecreatefromstring($data);

		$rotated_img = imagerotate($source_img, 90, 0); 

		$imageSave = imagejpeg($rotated_img, $path, 10);

		imagedestroy($source_img);

		return true;
	}
	
}	
?>
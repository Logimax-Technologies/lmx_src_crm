<?php
if( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin_ret_tagging extends CI_Controller
{
	const IMG_PATH  = 'assets/img/';
	const PROD_PATH  = 'assets/img/products/';
	
	function __construct()
	{
		parent::__construct();
		ini_set('date.timezone', 'Asia/Calcutta');
		$this->load->model('ret_tag_model');
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
	
	/**
	* Tagging Functions Starts
	*/
	
	public function tagging($type="",$id=""){
		$model = "ret_tag_model";
		switch($type)
		{
			case 'add':
						$data['tagging']= $this->$model->get_empty_record();
						$data['uom']= $this->$model->getUOMDetails();
						$data['main_content'] = "tagging/form" ;
						$this->load->view('layout/template', $data);
						break;
			case 'list':
						$data['main_content'] = "tagging/list" ;
						$this->load->view('layout/template', $data);
						break;
			case "save": 
						$addData = $_POST['lt_item'];
						//echo "<pre>";print_r($_POST);echo "<pre>";exit;
						//$fin_year=$this->$model->get_financialyear_by_status();
						
						$tag_datetime= date('Y-m-d',strtotime(str_replace("/","-",$addData['tag_datetime'])));
						$this->db->trans_begin();
						foreach($addData['lot_no'] as $key => $val)
	 					{
	 					$tag_code=$this->$model->code_number_generator();

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
													$img_name =  "P_". mt_rand(120,1230).".jpg";
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
	 					
						$arrayTag= array(
							'tag_code' => $tag_code, 
							'tag_datetime' => $tag_datetime, 
							'current_branch' => $addData['current_branch'], 
							'cost_center' => $addData['current_branch'], 
							'tag_lot_id' => $addData['lot_no'][$key], 
							'design_id' => $addData['lot_id_design'][$key], 
							'size' => $addData['size'][$key], 
							'purity' => $addData['purity'], 
							'size' => (!empty($addData['no_of_piece'][$key]) ? $addData['no_of_piece'][$key]:NULL), 
							'gross_wt' => (!empty($addData['gross_wt'][$key]) ? $addData['gross_wt'][$key]:NULL), 
							'less_wt' => (!empty($addData['less_wt'][$key]) ? $addData['less_wt'][$key]:NULL), 
							'net_wt' => (!empty($addData['net_wt'][$key]) ? $addData['net_wt'][$key]:NULL), 
							'calculation_based_on' => $addData['calculation_based_on'][$key],
							'retail_max_wastage_percent' => (!empty($addData['wastage_percentage'][$key]) ? $addData['wastage_percentage'][$key]:NULL), 
							'tag_mc_type ' => (!empty($addData['id_mc_type'][$key]) ? $addData['id_mc_type'][$key]:NULL), 
							'tag_mc_value ' => (!empty($addData['making_charge'][$key]) ? $addData['making_charge'][$key]:NULL), 
							'sales_value ' => (!empty($addData['sale_value'][$key]) ? $addData['sale_value'][$key]:NULL), 
							'image'		=> ((strlen($precious_imgs) > 0)?$precious_imgs : NULL),
							); 

	 					$insId = $this->$model->insertData($arrayTag,'ret_taging'); 
	 					if($addData['stone_details'][$key])
										{
											$stone_details=json_decode($addData['stone_details'][$key],true);
											foreach($stone_details as $stone)
											{
												$stone_data=array(
																'tag_id'=>$insId,
																'pieces'=>$stone['stone_pcs'],
																'wt'=>$stone['stone_wt'],
																'stone_id'=>$stone['stone_id'],
																'amount'=>$stone['stone_price']
																);
												$stoneInsert = $this->$model->insertData($stone_data,'ret_taging_stone');
							
											}										
										}
					}
	
						//update lot table if tag is completed
						/*$total_tag=$this->$model->get_tagged_details($addData['tag_lot_id']);
						if($total_tag['no_of_piece']==$total_tag['tagged_pieces'])
						{
						   $lot['tag_status']=1;
						   $tagInsert= $this->$model->updateData($lot,'lot_no',$addData['tag_lot_id'],'ret_lot_inwards');
						}*/
					
					if($this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
						$this->session->set_flashdata('chit_alert',array('message'=>'Tagging added successfully','class'=>'success','title'=>'Add Tagging'));
				 	
					}
					else
					{
						/*echo $this->db->last_query();
						echo $this->db->_error_message();exit;*/
						$this->db->trans_rollback();						 	
						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Add Tagging'));
					}
					redirect('admin_ret_tagging/tagging/list');	
				break;
					
			case "edit":
		 			$data['tagging'] = $this->$model->get_entry_records($id);
		 			$data['tagging']['lot_recv_branch'] = $this->$model->get_ret_settings('lot_recv_branch');
		 			$data['tag_balance']=$this->$model->get_lot_inward_details($data['tagging']['tag_lot_id'],'',$data['tagging']['design_id']);
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
					$this->$model->deleteData('floor_id',$id,'ret_lot_inward'); 
					if( $this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
						$this->session->set_flashdata('chit_alert', array('message' => 'floor deleted successfully','class' => 'success','title'=>'Delete floor'));	  
					}			  
					else
					{
						$this->db->trans_rollback();
						$this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete floor'));
					}
					redirect('admin_ret_lot/lot_inward/list');	
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

			case 'generate_barcode':


			 			$data['tagging'] = $this->$model->get_entry_records($id);
			 			$this->load->library('zend');
			 			$this->zend->load('Zend/Barcode');
			 			$file = Zend_Barcode::draw('code128', 'image', array('text' => $data['tagging']['tag_code']), array());
			 			 $code = time().$data['tagging']['tag_code'];
			 			 $data['code']=$code;
			 			 $data['src']=$this->config->item('base_url')."/barcode/".$code;
			 			$store_image = imagepng($file,"./barcode/{$code}.png");	
			 			$html = $this->load->view('barcode/receipt_barcode', $data,true);
			 			$this->load->helper(array('dompdf', 'file'));
						$dompdf = new DOMPDF();
						$dompdf->load_html($html);
						$customPaper = array(0,0,150,50);
						$dompdf->set_paper($customPaper, "portriat" );
						$dompdf->render();
						$dompdf->stream("receipt1.pdf",array('Attachment'=>0));  

				break;

			case "bulk_edit":
					$data['uom']= $this->$model->getUOMDetails();
					$data['main_content'] = "tagging/bulk_edit" ;
		 			$this->load->view('layout/template', $data);
				break;
				
			default: 
				  	$range['from_date']  = $this->input->post('from_date');
					$range['to_date']  = $this->input->post('to_date');
				  	$list = $this->$model->ajax_getTaggingList($range['from_date'],$range['to_date']);	 						$access = $this->admin_settings_model->get_access('admin_ret_tagging/tagging/list');
			        $data = array(
		        					'list'  => $list,
									'access'=> $access
		        				);  
					echo json_encode($data);
		}
	}

	public function updateTag()
	{
		$model = "ret_tag_model";
		$est_stones_item=$_POST['est_stones_item'];
		//echo "<pre>"; print_r($_POST);exit;
		$calculation_based_on=$this->input->post('calculation_based_on');
		$gross_wt			=$this->input->post('gross_wt');
		$less_wt			=$this->input->post('less_wt');
		$net_wt				=$this->input->post('net_wt');
		$size 				=$this->input->post('size');
		$pieces 			=$this->input->post('pieces');
		$sale_value 		=$this->input->post('sale_value');
		$tag_id 			=$this->input->post('tag_id');
		$this->db->trans_begin();
		$updateData=array(
			'calculation_based_on'=>$calculation_based_on,
			'gross_wt'=>$gross_wt,
			'less_wt'=>(!empty($less_wt) ? $less_wt:NULL),
			'net_wt'=>(!empty($net_wt) ? $net_wt:NULL),
			'size'=>(!empty($size) ? $size:NULL),
			'piece'=>(!empty($pieces) ? $pieces:NULL),
			'sales_value'=>(!empty($sale_value) ? $sale_value:NULL),
			
		);
		$updID= $this->$model->updateData($updateData,'tag_id',$tag_id,'ret_taging');
		//print_r($this->db->last_query());exit;
		if($updID)
		{
			if($est_stones_item>0)
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
				//print_r($this->db->last_query());exit;
				}	
			}
		}
		if($this->db->trans_status()===TRUE)
		{
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
	public function getAvailableMaterials(){
		$model = "ret_tag_model";
		$data = $this->$model->getAvailableMaterials();	  
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
			$retail_max_wastage_percent=$this->input->post('retail_max_wastage_percent');
			$design_id=$this->input->post('design_id');
			$reqdata=$this->input->post('req_data');
			$post_otp=$this->input->post('tag_otp');
			$session_otp=$this->session->userdata('tagging_otp');
			$otp = array(explode(',',$session_otp));
			$tag_ids['record']='';
			 $this->db->trans_begin();
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

		  if($update_otp)
		  {
				foreach($reqdata as $data)
				{
					$tag_ids['record'].=$data['tag_id'].',';
					$employee= $this->session->userdata('uid');
					$upd_data=array(
					'design_id'=>$design_id,
					'sales_value'=>$data['sales_value'],
					'tag_mc_value'=>$data['tag_mc_value'],
					'retail_max_wastage_percent'=>$data['retail_max_wastage_percent'],
					'updated_time'=>date("Y-m-d H:i:s"),
					'updated_by'=>$employee,
					);
					$update_status=$this->$model->updateData($upd_data,'tag_id',$data['tag_id'],'ret_taging');
				}
				if($update_status)
				{		
					$this->db->trans_commit();
					 $update_otp=$this->$model->updateData($tag_ids,'otp_code',$post_otp,'otp');
					$status=array('status'=>true,'msg'=>'Tag Details Updated successfully');	
				}
				else
				{
					$this->db->trans_rollback();
					$status=array('status'=>false,'msg'=>'Unable to Proceed Your Request');	
				}
		  }
		  	echo json_encode($status);

	}

	function get_metal_rates_by_branch()
	{
		$model="ret_tag_model";
		$id_branch=$this->input->post('id_branch');
		$data=$this->$model->get_branchwise_rate($id_branch);
		echo json_encode($data);
	}

	function admin_approval()
	{
		$model="ret_tag_model";
		$data           =$this->$model->get_ret_settings('otp_approval_nos');
		$mobile_num     = array(explode(',',$data['value']));
		$sent_otp='';
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
					$message="Hi Your OTP  For Tag Editing is :  ".$OTP." Will expire within 1 minutes";
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

	function resendotp()
	{
		$model="ret_tag_model";
		$data           =$this->$model->get_ret_settings('otp_approval_nos');
		$mobile_num     = array(explode(',',$data['value']));
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
					$message="Hi Your OTP  For Tag Editing is :  ".$OTP." Will expire within 1 minutes";
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

	function send_sms($mobile,$message)
	{
		if($this->config->item('sms_gateway') == '1'){
		    $this->sms_model->sendSMS_MSG91($mobile,$message);		
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
		$data=$this->$model->get_lot_products($lot_no);
		echo json_encode($data);
	}

	function get_lot_designs()
	{
		$model="ret_tag_model";
		$lot_no=$this->input->post('lot_no');
		$lot_product=$this->input->post('lot_product');
		$data=$this->$model->get_lot_designs($lot_no,$lot_product);
		echo json_encode($data);
	}
}	
?>
<?php
if( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin_ret_lot extends CI_Controller
{
	const IMG_PATH  = 'assets/img/';
	const PROD_PATH  = 'assets/img/products/';
	
	function __construct()
	{
		parent::__construct();
		ini_set('date.timezone', 'Asia/Calcutta');
		$this->load->model('ret_lot_model');
		$this->load->model('admin_settings_model');
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
	
	function remove_img()
	{
		$status = FALSE;
		$model = "ret_lot_model";
		if($_POST['folder'] == 'certificates'){
			$path = SELF::IMG_PATH."".$_POST['folder']."/".$_POST['id']."/".$_POST['file'];
			chmod(SELF::IMG_PATH."".$_POST['folder'],0777);
			unlink($path);
			$newImgValue = NULL;
			$imgs = explode('#',$_POST['imgs']);
			$totImgs = sizeof($imgs);
			foreach($imgs as $k => $img){
				if($img == $_POST['file']) { // img1.jpg#img1.jpg
					if($k == $totImgs-1)  
						$newImgValue = str_replace($_POST['file'], '', $_POST['imgs']);
					else
						$newImgValue = str_replace($_POST['file']."#", '', $_POST['imgs']);
				}
			}
			$status = $this->$model->updateData(array($_POST['field'] => $newImgValue),'lot_no',$_POST['id'],'ret_lot_inwards');
		}
		else if($_POST['folder'] == 'lot')
		{
			$path = SELF::IMG_PATH."".$_POST['folder']."/".$_POST['file'];
			chmod(SELF::IMG_PATH."".$_POST['folder'],0777);
			unlink($path);
			$newImgValue = NULL;
			$imgs = explode('#',$_POST['imgs']);
			$totImgs = sizeof($imgs);
			foreach($imgs as $k => $img){
				if($img == $_POST['file']) { // img1.jpg#img1.jpg
					if($k == $totImgs-1)  
						$newImgValue = str_replace($_POST['file'], '', $_POST['imgs']);
					else
						$newImgValue = str_replace($_POST['file']."#", '', $_POST['imgs']);
				}
			}
			$status = $this->$model->updateData(array($_POST['field'] => $newImgValue),'lot_no',$_POST['id'],'ret_lot_inwards');
		}
		if($status){
			echo "Picture removed successfully";
		}      
	}
	
	/**
	* LOT Inward Functions Starts
	*/
	
	public function lot_inward($type="",$id=""){
		$model = "ret_lot_model";
		$this->load->model('ret_catalog_model');
		switch($type)
		{
			case 'add':
						$data['inward'] = $this->$model->empty_record_inward();
						$data['uom'] = $this->ret_catalog_model->getActiveUOM();
						$data['main_content'] = "lot/form" ;
						$this->load->view('layout/template', $data);
					break;
			case 'list':
						$data['main_content'] = "lot/list" ;
						$this->load->view('layout/template', $data);
					break;
			case "save": 
						$addData = $_POST['inward'];
						$addData['lot_date'] = date('Y-m-d',strtotime(str_replace("/","-",$addData['lot_date'])));
						$data=array(
							'lot_date'				=> (!empty($addData['lot_date']) ? $addData['lot_date'] :NULL ),
							'lot_type'				=> (!empty($addData['lot_type']) ? $addData['lot_type'] :NULL ),
							'lot_received_at'		=> (!empty($addData['lot_received_at']) ? $addData['lot_received_at'] :NULL ),
							'current_branch'		=> (!empty($addData['lot_received_at']) ? $addData['lot_received_at'] :NULL ),
							'gold_smith'			=> (!empty($addData['gold_smith']) ? $addData['gold_smith'] :NULL ),
							'order_no'				=> (!empty($addData['order_no']) ? $addData['order_no'] :NULL ),
							'lot_product'			=> (!empty($addData['lot_product']) ? $addData['lot_product'] :NULL ),
							'lot_id_design'			=> (!empty($addData['lot_id_design']) ? $addData['lot_id_design'] :NULL ),
							'no_of_piece'			=> (!empty($addData['no_of_piece']) ? $addData['no_of_piece'] :0 ),
							'stock_type'			=> (!empty($addData['stock_type']) ? $addData['stock_type'] :0 ),
							'gross_wt'				=> (!empty($addData['gross_wt']) ? $addData['gross_wt'] :0 ),
							'gross_wt_uom'			=> (!empty($addData['gross_wt_uom']) ? $addData['gross_wt_uom'] :NULL ),
							'net_wt'				=> (!empty($addData['net_wt']) ? $addData['net_wt'] :0 ),
							'net_wt_uom'			=> (!empty($addData['net_wt_uom']) ? $addData['net_wt_uom'] :NULL ),
							'less_wt'				=> (!empty($addData['less_wt']) ? $addData['less_wt'] :0 ),
							'less_wt_uom'			=> (!empty($addData['less_wt_uom']) ? $addData['less_wt_uom'] :NULL ),
							'precious_stone'		=> (!empty($addData['precious_stone']) ? $addData['precious_stone'] :0 ),
							'semi_precious_stone'	=> (!empty($addData['semi_precious_stone']) ? $addData['semi_precious_stone'] :0 ),
							'normal_stone'			=> (!empty($addData['normal_stone']) ? $addData['normal_stone'] :0 ),
							'precious_st_pcs'		=> (!empty($addData['precious_st_pcs']) ? $addData['precious_st_pcs'] :NULL ),
							'precious_st_wt'		=> (!empty($addData['precious_st_wt']) ? $addData['precious_st_wt'] :NULL ),
							'precious_st_uom'		=> (!empty($addData['precious_st_uom']) ? $addData['precious_st_uom'] :NULL ),
							'semi_precious_st_pcs'	=> (!empty($addData['semi_precious_st_pcs']) ? $addData['semi_precious_st_pcs'] :NULL ),
							'semi_precious_st_wt'	=> (!empty($addData['semi_precious_st_wt']) ? $addData['semi_precious_st_wt'] :NULL ),
							'semi_precious_st_uom'	=> (!empty($addData['semi_precious_st_uom']) ? $addData['semi_precious_st_uom'] :NULL ),
							'normal_st_pcs'			=> (!empty($addData['normal_st_pcs']) ? $addData['normal_st_pcs'] :NULL ),
							'normal_st_wt'			=> (!empty($addData['normal_st_wt']) ? $addData['normal_st_wt'] :NULL ),
							'normal_st_wt_uom'		=> (!empty($addData['normal_st_wt_uom']) ? $addData['normal_st_wt_uom'] :NULL ),
							'wastage_percentage'	=> (!empty($addData['wastage_percentage']) ? $addData['wastage_percentage'] :NULL ),
							'making_charge'			=> (!empty($addData['making_charge']) ? $addData['making_charge'] :NULL ),
							'mc_type'				=> (!empty($addData['mc_type']) ? $addData['mc_type'] :NULL ),
							'narration'				=> (!empty($addData['narration']) ? $addData['narration'] :NULL ),
							'lot_images'			=> (!empty($addData['image_name']) ? $addData['image_name'] :NULL ),
							'id_category'			=> (!empty($addData['id_category']) ? $addData['id_category'] :NULL ),
							'id_purity'				=> (!empty($addData['id_purity']) ? $addData['id_purity'] :NULL ),
							'created_on'	  		=> date("Y-m-d H:i:s"),
						    'created_by'      		=>  (!empty($addData['created_by']) ? $addData['created_by'] :NULL )
						); 
	 				$this->db->trans_begin();
	 				$insId = $this->$model->insertData($data,'ret_lot_inwards'); 
	 				if($insId){
						if(!empty($_FILES)){
							$img_arr = array();

							if($insId > 0)
							{
								$folder =  self::IMG_PATH."certificates/".$insId ;

								if (!is_dir($folder)) {
									mkdir($folder, 0777, TRUE);
								}  
								//check array and upload remaining images
								if(!empty($_FILES)){
									if(isset($_FILES['normal'])){
										$normal_imgs = "";
										foreach($_FILES['normal']['tmp_name'] as $key => $tmp_name){
											if($tmp_name)
											{  
												// unlink($folder."/".$product['image']); 
												$img_name =  $insId."_CERT_N_". mt_rand(120,1230).".jpg";
												$path = $folder."/".$img_name; 
												$result = $this->upload_img('image',$path,$tmp_name);
												if($result){
													$normal_imgs = strlen($normal_imgs) > 0 ? $normal_imgs."#".$img_name : $img_name;
												}
											}
										}
										if(strlen($normal_imgs) > 0){
											$imgdata['normal_st_certif'] = $normal_imgs;
											$this->$model->updateData($imgdata,'lot_no',$insId,'ret_lot_inwards');
										}
									}
									if(isset($_FILES['semi'])){ 
										$sp_imgs = "";
										foreach($_FILES['semi']['tmp_name'] as $key => $tmp_name){
											if($tmp_name)
											{
												// unlink($folder."/".$product['image']); 
												$img_name =  $insId."_CERT_SP_". mt_rand(120,1230).".jpg";
												$path = $folder."/".$img_name; 
												$result = $this->upload_img('image',$path,$tmp_name);
												if($result){
													$sp_imgs = strlen($sp_imgs) > 0 ? $sp_imgs."#".$img_name : $img_name;
												}
											}
										} 
										if(strlen($sp_imgs) > 0){
											$imgdata['semiprecious_st_certif'] = $sp_imgs;
											$this->$model->updateData($imgdata,'lot_no',$insId,'ret_lot_inwards');
										}
									}
									if(isset($_FILES['precious'])){ 
										$precious_imgs = "";
										foreach($_FILES['precious']['tmp_name'] as $key => $tmp_name){
											if($tmp_name)
											{
												// unlink($folder."/".$product['image']); 
												$img_name =  $insId."_CERT_P_". mt_rand(120,1230).".jpg";
												$path = $folder."/".$img_name; 
												$result = $this->upload_img('image',$path,$tmp_name);
												if($result){
													$precious_imgs = strlen($precious_imgs) > 0 ? $precious_imgs."#".$img_name : $img_name;
												}
											}
										}
										if(strlen($precious_imgs) > 0){
											$imgdata['precious_st_certif'] = $precious_imgs;
											$this->$model->updateData($imgdata,'lot_no',$insId,'ret_lot_inwards');
										}
									}
								}
							} 
						}
					}
					if($this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
						$this->session->set_flashdata('chit_alert',array('message'=>'Lot added successfully','class'=>'success','title'=>'Add Lot'));
				 	
					}
					else
					{
						$this->db->trans_rollback();						 	
						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Add Lot'));
					}
					redirect('admin_ret_lot/lot_inward/list');	
				break;
					
			case "edit":
					$data['uom'] = $this->ret_catalog_model->getActiveUOM();
		 			$data['inward'] = $this->$model->get_lotInward($id);
		 			$data['inward']['lot_receive_settings'] = $this->$model->get_ret_settings('lot_recv_branch'); ;
		 			$data['main_content'] = "lot/form" ;
					$this->load->view('layout/template', $data);
				break; 	 
						
			case 'delete':
					$this->db->trans_begin();
					$this->$model->deleteData('lot_no',$id,'ret_lot_inwards'); 
					if( $this->db->trans_status()===TRUE)
					{
						$path = SELF::IMG_PATH."certificates/".$id;
						chmod(SELF::IMG_PATH."certificates",0777);
						$this->rrmdir($path);
						$this->db->trans_commit();
						$this->session->set_flashdata('chit_alert', array('message' => 'Lot deleted successfully','class' => 'success','title'=>'Delete Lot'));	  
					}			  
					else
					{
						$this->db->trans_rollback();
						$this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete Lot'));
					}
					redirect('admin_ret_lot/lot_inward/list');	
				break;
					
			case "update":
		 				$updData = $_POST['inward']; 
		 				$updData['lot_date'] = date('Y-m-d',strtotime(str_replace("/","-",$updData['lot_date'])));
		 				$lot_details=$this->$model->get_lotInward($id);
		 				
		 			//	echo "<pre>";print_r($updData);exit;
						$data=array(
							'lot_date'				=> (!empty($updData['lot_date']) ? $updData['lot_date'] :NULL ),
							'lot_type'				=> (!empty($updData['lot_type']) ? $updData['lot_type'] :NULL ),
							'lot_received_at'		=> (!empty($updData['lot_received_at']) ? $updData['lot_received_at'] :NULL ),
							'current_branch'		=> (!empty($updData['lot_received_at']) ? $updData['lot_received_at'] :NULL ),
							'gold_smith'			=> (!empty($updData['gold_smith']) ? $updData['gold_smith'] :NULL ),
							'order_no'				=> (!empty($updData['order_no']) ? $updData['order_no'] :NULL ),
							'lot_product'			=> (!empty($updData['lot_product']) ? $updData['lot_product'] :NULL ),
							'lot_id_design'			=> (!empty($updData['lot_id_design']) ? $updData['lot_id_design'] :NULL ),
							'no_of_piece'			=> (!empty($updData['no_of_piece']) ? $updData['no_of_piece'] :NULL ),
							'stock_type'			=> (!empty($updData['stock_type']) ? $updData['stock_type'] :NULL ),
							'gross_wt'				=> (!empty($updData['gross_wt']) ? $updData['gross_wt'] :NULL ),
							'gross_wt_uom'			=> (!empty($updData['gross_wt_uom']) ? $updData['gross_wt_uom'] :NULL ),
							'net_wt'				=> (!empty($updData['net_wt']) ? $updData['net_wt'] :NULL ),
							'net_wt_uom'			=> (!empty($updData['net_wt_uom']) ? $updData['net_wt_uom'] :NULL ),
							'less_wt'				=> (!empty($updData['less_wt']) ? $updData['less_wt'] :NULL ),
							'less_wt_uom'			=> (!empty($updData['less_wt_uom']) ? $updData['less_wt_uom'] :NULL ),
							'precious_stone'		=> (!empty($updData['precious_stone']) ? $updData['precious_stone'] :0 ),
							'semi_precious_stone'	=> (!empty($updData['semi_precious_stone']) ? $updData['semi_precious_stone'] :0 ),
							'normal_stone'			=> (!empty($updData['normal_stone']) ? $updData['normal_stone'] :0 ),
							'precious_st_pcs'		=> (!empty($updData['precious_st_pcs']) ? $updData['precious_st_pcs'] :NULL ),
							'precious_st_wt'		=> (!empty($updData['precious_st_wt']) ? $updData['precious_st_wt'] :NULL ),
							'precious_st_uom'		=> (!empty($updData['precious_st_uom']) ? $updData['precious_st_uom'] :NULL ),
							'semi_precious_st_pcs'	=> (!empty($updData['semi_precious_st_pcs']) ? $updData['semi_precious_st_pcs'] :NULL ),
							'semi_precious_st_wt'	=> (!empty($updData['semi_precious_st_wt']) ? $updData['semi_precious_st_wt'] :NULL ),
							'semi_precious_st_uom'	=> (!empty($updData['semi_precious_st_uom']) ? $updData['semi_precious_st_uom'] :NULL ),
							'normal_st_pcs'			=> (!empty($updData['normal_st_pcs']) ? $updData['normal_st_pcs'] :NULL ),
							'normal_st_wt'			=> (!empty($updData['normal_st_wt']) ? $updData['normal_st_wt'] :NULL ),
							'normal_st_wt_uom'		=> (!empty($updData['normal_st_wt_uom']) ? $updData['normal_st_wt_uom'] :NULL ),
							'wastage_percentage'	=> (!empty($updData['wastage_percentage']) ? $updData['wastage_percentage'] :NULL ),
							'making_charge'			=> (!empty($updData['making_charge']) ? $updData['making_charge'] :NULL ),
							'mc_type'				=> (!empty($updData['mc_type']) ? $updData['mc_type'] :NULL ),
							'narration'				=> (!empty($updData['narration']) ? $updData['narration'] :NULL ),
							'lot_images'			=> (!empty($updData['image_name']) ? $updData['image_name'] :NULL ),
							'id_category'			=> (!empty($updData['id_category']) ? $updData['id_category'] :NULL ),
							'id_purity'				=> (!empty($updData['id_purity']) ? $updData['id_purity'] :NULL ),
							'lot_images'			=> (!empty($updData['image_name']) ?$lot_details['lot_images'].$updData['image_name'] :((!empty($lot_details['lot_images']) ?$lot_details['lot_images'] :'' ))),
							'updated_on'	  		=> date("Y-m-d H:i:s"),
							'updated_by'      		=> $this->session->userdata('uid'),
							'created_by'      		=>  (!empty($updData['created_by']) ? $updData['created_by'] :NULL ),
						); 
	 				$this->db->trans_begin();
	 				$updId = $this->$model->updateData($data,'lot_no',$id,'ret_lot_inwards'); 
	 				if($updId){
						if(!empty($_FILES)){
							$img_arr = array();

							if($updId > 0)
							{
								$folder =  self::IMG_PATH."certificates/".$updId ;

								if (!is_dir($folder)) {
									mkdir($folder, 0777, TRUE);
								}  
								//check array and upload remaining images
								if(!empty($_FILES)){
									if(isset($_FILES['normal'])){
										$normal_imgs = "";
										foreach($_FILES['normal']['tmp_name'] as $key => $tmp_name){
											if($tmp_name)
											{  
												// unlink($folder."/".$product['image']); 
												$img_name =  $updId."_CERT_N_". mt_rand(120,1230).".jpg";
												$path = $folder."/".$img_name; 
												$result = $this->upload_img('image',$path,$tmp_name);
												if($result){
													$normal_imgs = strlen($normal_imgs) > 0 ? $normal_imgs."#".$img_name : $img_name;
												}
											}
										}
										if(strlen($normal_imgs) > 0){
											$imgdata['normal_st_certif'] = $normal_imgs;
											$this->$model->updateData($imgdata,'lot_no',$updId,'ret_lot_inwards');
										}
									}
									if(isset($_FILES['semi'])){ 
										$sp_imgs = "";
										foreach($_FILES['semi']['tmp_name'] as $key => $tmp_name){
											if($tmp_name)
											{
												// unlink($folder."/".$product['image']); 
												$img_name =  $updId."_CERT_SP_". mt_rand(120,1230).".jpg";
												$path = $folder."/".$img_name; 
												$result = $this->upload_img('image',$path,$tmp_name);
												if($result){
													$sp_imgs = strlen($sp_imgs) > 0 ? $sp_imgs."#".$img_name : $img_name;
												}
											}
										} 
										if(strlen($sp_imgs) > 0){
											$imgdata['semiprecious_st_certif'] = $sp_imgs;
											$this->$model->updateData($imgdata,'lot_no',$updId,'ret_lot_inwards');
										}
									}
									if(isset($_FILES['precious'])){ 
										$precious_imgs = "";
										foreach($_FILES['precious']['tmp_name'] as $key => $tmp_name){
											if($tmp_name)
											{
												// unlink($folder."/".$product['image']); 
												$img_name =  $updId."_CERT_P_". mt_rand(120,1230).".jpg";
												$path = $folder."/".$img_name; 
												$result = $this->upload_img('image',$path,$tmp_name);
												if($result){
													$precious_imgs = strlen($precious_imgs) > 0 ? $precious_imgs."#".$img_name : $img_name;
												}
											}
										}
										if(strlen($precious_imgs) > 0){
											$imgdata['precious_st_certif'] = $precious_imgs;
											$this->$model->updateData($imgdata,'lot_no',$updId,'ret_lot_inwards');
										}
									}
								}
							} 
						}
					}
					if($this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
						$this->session->set_flashdata('chit_alert',array('message'=>'Lot updated successfully','class'=>'success','title'=>'Update Lot'));
				 	
					}
					else
					{
						$this->db->trans_rollback();						 	
						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Update Lot'));
					}
					redirect('admin_ret_lot/lot_inward/list');	
				break;
			default: 
					  	$from_date	= $this->input->post('from_date');
			        	$to_date	= $this->input->post('to_date'); 
			        	$received_at	= $this->input->post('rcvd_at_branch'); 
					  	$list = $this->$model->ajax_getLotList($received_at,$from_date,$to_date);
					  	$access = $this->admin_settings_model->get_access('admin_ret_lot/lot_inward/list');
				        $data = array(
				        					'list'  => $list,
											'access'=> $access
				        				);  
						echo json_encode($data);
		}
	}

	function upload_lotimg()
{
	$imgpath='assets/img/lot/';
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
	$imgpath='assets/img/lot/'.$name;
	$result = $this->upload_img('orderimg',$imgpath,$img);
	}
	$data=array('msg'=>true,'name'=>$file_name);
	echo json_encode($data);
}


}	
?>
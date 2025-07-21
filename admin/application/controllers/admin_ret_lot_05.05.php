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
		$this->load->model('ret_catalog_model');
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
			$status = $this->$model->updateData(array($_POST['field'] => $newImgValue),'lot_no',$_POST['id'],'ret_lot_inwards_detail');
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
	
	public function lot_inward($type="",$id=""){
		$model = "ret_lot_model";
		switch($type)
		{
			case 'add':
						$data['inward'] = $this->$model->empty_record_inward();
						$data['uom']= $this->ret_catalog_model->getActiveUOM();
						$data['main_content'] = "lot/form" ;
						$this->load->view('layout/template', $data);
					break;
			case 'list':
						$data['main_content'] = "lot/list" ;
						$this->load->view('layout/template', $data);
					break;
			case "save":   
					
					$addData = $_POST['inward']; 
					
					$data = array(
							'lot_date'				=> date("Y-m-d H:i:s"),
							'lot_type'				=> (isset($addData['lot_type']) ? $addData['lot_type'] :NULL ),
							'lot_received_at'		=> (isset($addData['lot_received_at']) ? $addData['lot_received_at'] :NULL ),
							'created_branch'		=> (isset($addData['lot_received_at']) ? $addData['lot_received_at'] :NULL ),
							//'current_branch'		=> (isset($addData['lot_received_at']) ? $addData['lot_received_at'] :NULL ),
							'gold_smith'			=> (isset($addData['gold_smith']) ? $addData['gold_smith'] :NULL ),
							'order_no'				=> (isset($addData['order_no']) ? $addData['order_no'] :NULL ),
							'id_category'			=> (isset($addData['id_category']) ? $addData['id_category'] :NULL ),
							'id_purity'				=> (isset($addData['id_purity']) ? $addData['id_purity'] :NULL ),
							'narration'      		=> (isset($addData['narration']) ? $addData['narration'] :NULL ),
							'created_on'	  		=> date("Y-m-d H:i:s"),
							'created_by'      		=> $this->session->userdata('uid')
						); 
	 				$this->db->trans_begin();
	 				$insId = $this->$model->insertData($data,'ret_lot_inwards');
	 				/*print_r($this->db->last_query());echo $insId;exit; */
	 				if($insId > 0){  
	 					$lot_imgs = ""; 
						if(isset($_FILES['lot_image'])){ 
							$folder =  self::IMG_PATH."lot/".$insId;
							if (!is_dir($folder)) {
								mkdir($folder, 0777, TRUE);
							}
//							foreach($_FILES['lot_image'] as $file_key => $file_val){
							if($_FILES['lot_image']['tmp_name'] != ''){
								$img_name =  mt_rand(120,1230).".jpg";
								$path = $folder."/".$img_name; 
								$result = $this->upload_img('image',$path,$_FILES['lot_image']['tmp_name']);
								if($result){
									$lot_imgs = strlen($lot_imgs) > 0 ? $lot_imgs."#".$img_name : $img_name;
								}
								$this->$model->updateData(array('lot_images'=>$lot_imgs),'lot_no',$insId,'ret_lot_inwards');
							} 
						}
	 					  
	 					
	 					if(sizeof($_POST['inward_item'])>0)
	 					{ 
	 						foreach($_POST['inward_item'] as $itemData)
	 						{ 
	 							$precious_imgs = "";
	 							$sp_imgs = "";
	 							$normal_imgs = "";
	 							
	 							// Precious Stone Images
			 					$p_ImgData = json_decode($itemData['precious_st_certif']);  
								if(sizeof($p_ImgData) > 0){
									foreach($p_ImgData as $precious){
										$imgFile = $this->base64ToFile($precious->src);
										$_FILES['precious'][] = $imgFile;
									}
								}
								// Semi-precious Stone Images
			 					$sp_ImgData = json_decode($itemData['semiprecious_st_certif']); 
			 					if(sizeof($sp_ImgData) > 0) {
									foreach($sp_ImgData as $semi){
										$imgFile = $this->base64ToFile($semi->src);
										$_FILES['semi'][] = $imgFile;
									}
								}
								// Normal Stone Images
			 					$n_ImgData = json_decode($itemData['normal_st_certif']);  
			 					if(sizeof($n_ImgData) > 0) {
									foreach($n_ImgData as $normal){
										$imgFile = $this->base64ToFile($normal->src);
										$_FILES['normal'][] = $imgFile;
									} 
								}   
							 	if(!empty($_FILES)){
									$img_arr = array();
									if($insId > 0)
									{ 
										$folder =  self::IMG_PATH."lot/".$insId."/"."certificates" ; 
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
										if(isset($_FILES['semi'])){ 
											$sp_imgs = "";
											foreach($_FILES['semi'] as $spfile_key => $spfile_val){
												if($spfile_val['tmp_name'])
												{
													// unlink($folder."/".$product['image']); 
													$img_name =  "SP_". mt_rand(120,1230).".jpg";
													$path = $folder."/".$img_name; 
													$result = $this->upload_img('image',$path,$spfile_val['tmp_name']);
													if($result){
														$sp_imgs = strlen($sp_imgs) > 0 ? $sp_imgs."#".$img_name : $img_name;
													}
												}
											} 
										}
										if(isset($_FILES['normal'])){ 
											foreach($_FILES['normal'] as $nfile_key => $nfile_val){
												if($nfile_val['tmp_name']) 
												{  
													// unlink($folder."/".$product['image']); 
													$img_name =  "N_". mt_rand(120,1230).".jpg";
													$path = $folder."/".$img_name; 
													$result = $this->upload_img('image',$path,$nfile_val['tmp_name']);
													if($result){
														$normal_imgs = strlen($normal_imgs) > 0 ? $normal_imgs."#".$img_name : $img_name;
													}
												}
											}
										} 
									} 
								} 
	 							$item_details = array(
    	 							'lot_no'	                =>$insId,
    								'lot_product'				=> (isset($itemData['lot_product']) ? $itemData['lot_product']:NULL),
    								'lot_id_design'				=> (isset($itemData['lot_id_design']) ? $itemData['lot_id_design']:NULL),
    								'no_of_piece'				=> (isset($itemData['pcs']) ? $itemData['pcs']:0),
    								'stock_type'				=> (isset($itemData['lot_product']) ? $itemData['lot_product']:1),
    								'gross_wt'				    => (isset($itemData['gross_wt']) ? $itemData['gross_wt']:NULL),
    								'gross_wt_uom'				=> (isset($itemData['gross_wt_uom']) ? $itemData['gross_wt_uom']:NULL),
    								'net_wt'				    => (isset($itemData['net_wt']) ? $itemData['net_wt']:NULL),
    								'net_wt_uom'				=> (isset($itemData['net_wt_uom']) ? $itemData['net_wt_uom']:NULL),
    								'less_wt'				    => (isset($itemData['less_wt']) ? $itemData['less_wt']:NULL),
    								'less_wt_uom'				=> (isset($itemData['less_wt_uom']) ? $itemData['less_wt_uom']:NULL),
    								'wastage_percentage'		=> (isset($itemData['wastage_percentage']) ? $itemData['wastage_percentage']:NULL),
    								'mc_type'				    => (isset($itemData['id_mc_type']) ? $itemData['id_mc_type']:1),
    								'making_charge'				=> (isset($itemData['making_charge']) ? $itemData['making_charge']:0),
    								'precious_stone'			=> (isset($itemData['precious_stone']) ? $itemData['precious_stone']:0),
    								'precious_st_pcs'			=> (isset($itemData['precious_st_pcs']) ? $itemData['precious_st_pcs']:NULL),
    								'precious_st_wt'			=> (isset($itemData['precious_st_wt']) ? $itemData['precious_st_wt']:NULL),
    								'semi_precious_stone'		=> (isset($itemData['semi_precious_stn']) ? $itemData['semi_precious_stn']:0),
    								'semi_precious_st_pcs'		=> (isset($itemData['semi_precious_st_pcs']) ? $itemData['semi_precious_st_pcs']:NULL),
    								'semi_precious_st_wt'		=> (isset($itemData['semi_precious_st_wt']) ? $itemData['semi_precious_st_wt']:NULL),
    								'normal_stone'				=> (isset($itemData['normal_stn']) ? $itemData['normal_stn']:0),
    								'normal_st_pcs'				=> (isset($itemData['normal_st_pcs']) ? $itemData['normal_st_pcs']:NULL),
    								'normal_st_wt'				=> (isset($itemData['normal_st_wt']) ? $itemData['normal_st_wt']:NULL),
    								'normal_st_wt_uom'			=> (isset($itemData['nor_wt_uom']) ? $itemData['nor_wt_uom']:NULL),
    								'semi_precious_st_uom'		=> (isset($itemData['semi_wt_uom']) ? $itemData['semi_wt_uom']:NULL),
    								'precious_st_uom'			=> (isset($itemData['pre_wt_uom']) ? $itemData['pre_wt_uom']:NULL),
    								'normal_st_certif'			=> ((strlen($normal_imgs) > 0)?$normal_imgs : NULL),
    								'semiprecious_st_certif'	=> ((strlen($sp_imgs) > 0)?$sp_imgs : NULL),
    								'precious_st_certif'		=> ((strlen($precious_imgs) > 0)?$precious_imgs : NULL),
    								//'updated_on'	  		    => date("Y-m-d H:i:s"),
    								//'updated_by'      		=>  $this->session->userdata('uid')
								); 
								$detail_insId = $this->$model->insertData($item_details,'ret_lot_inwards_detail');  
								
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
					  echo $this->db->last_query();	 
					  echo $this->db->_error_message();	exit;
						$this->db->trans_rollback();					 	
						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Add Lot'));
					}
					redirect('admin_ret_lot/lot_inward/list');	
				break;
					
			case "edit":
					$data['uom'] = $this->ret_catalog_model->getActiveUOM();
		 			$data['inward'] = $this->$model->get_lotInward($id);
		 			$data['inward_details'] = $this->$model->get_lotInward_detail($id);
		 			$data['inward']['lot_receive_settings'] = $this->$model->get_ret_settings('lot_recv_branch');
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
		 			$addData = $_POST['inward'];  
		 			$lot_imgs = ""; 
					if(isset($_FILES['lot_image'])){ 
						$folder =  self::IMG_PATH."lot/".$updId;
						if (!is_dir($folder)) {
							mkdir($folder, 0777, TRUE);
						}
//							foreach($_FILES['lot_image'] as $file_key => $file_val){
						if($_FILES['lot_image']['tmp_name'] != ''){
							$img_name =  mt_rand(120,1230).".jpg";
							$path = $folder."/".$img_name; 
							$result = $this->upload_img('image',$path,$_FILES['lot_image']['tmp_name']);
							if($result){
								$lot_imgs = strlen($lot_imgs) > 0 ? $lot_imgs."#".$img_name : $img_name;
							}
						} 
					}
		 			$data = array( 
								'lot_type'				=> (isset($addData['lot_type']) ? $addData['lot_type'] :NULL ),
								'lot_received_at'		=> (isset($addData['lot_received_at']) ? $addData['lot_received_at'] :NULL ),
								'created_branch'		=> (isset($addData['lot_received_at']) ? $addData['lot_received_at'] :NULL ),
								//'current_branch'		=> (isset($addData['lot_received_at']) ? $addData['lot_received_at'] :NULL ),
								'gold_smith'			=> (isset($addData['gold_smith']) ? $addData['gold_smith'] :NULL ),
								'order_no'				=> (isset($addData['order_no']) ? $addData['order_no'] :NULL ),
								'id_category'			=> (isset($addData['id_category']) ? $addData['id_category'] :NULL ),
								'id_purity'				=> (isset($addData['id_purity']) ? $addData['id_purity'] :NULL ),
								'narration'      		=> (isset($addData['narration']) ? $addData['narration'] :NULL ),							
								'updated_on'	  		=> date("Y-m-d H:i:s"),
								'updated_by'      		=> $this->session->userdata('uid')
							); 
					if(strlen($lot_imgs) > 0){
						$data['lot_images'] = $lot_imgs;
					}
	 				$this->db->trans_begin();
	 				$updId = $this->$model->updateData($data,'lot_no',$id,'ret_lot_inwards'); 
	 				if($updId > 0){  
	 					if(sizeof($_POST['inward_item'])>0)
	 					{ 
	 						foreach($_POST['inward_item'] as $itemData)
	 						{ 
	 							$precious_imgs = "";
	 							$sp_imgs = "";
	 							$normal_imgs = "";
	 							
	 							// Precious Stone Images
			 					$p_ImgData = json_decode($itemData['precious_st_certif']);  
								if(sizeof($p_ImgData) > 0){
									foreach($p_ImgData as $precious){
										$imgFile = $this->base64ToFile($precious->src);
										$_FILES['precious'][] = $imgFile;
									}
								}
								// Semi-precious Stone Images
			 					$sp_ImgData = json_decode($itemData['semiprecious_st_certif']); 
			 					if(sizeof($sp_ImgData) > 0) {
									foreach($sp_ImgData as $semi){
										$imgFile = $this->base64ToFile($semi->src);
										$_FILES['semi'][] = $imgFile;
									}
								}
								// Normal Stone Images
			 					$n_ImgData = json_decode($itemData['normal_st_certif']);  
			 					if(sizeof($n_ImgData) > 0) {
									foreach($n_ImgData as $normal){
										$imgFile = $this->base64ToFile($normal->src);
										$_FILES['normal'][] = $imgFile;
									} 
								}   
							 	if(!empty($_FILES)){
									$img_arr = array();
									$folder =  self::IMG_PATH."lot/".$updId."/"."certificates" ; 
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
									if(isset($_FILES['semi'])){ 
										$sp_imgs = "";
										foreach($_FILES['semi'] as $spfile_key => $spfile_val){
											if($spfile_val['tmp_name'])
											{
												// unlink($folder."/".$product['image']); 
												$img_name =  "SP_". mt_rand(120,1230).".jpg";
												$path = $folder."/".$img_name; 
												$result = $this->upload_img('image',$path,$spfile_val['tmp_name']);
												if($result){
													$sp_imgs = strlen($sp_imgs) > 0 ? $sp_imgs."#".$img_name : $img_name;
												}
											}
										} 
									}
									if(isset($_FILES['normal'])){ 
										foreach($_FILES['normal'] as $nfile_key => $nfile_val){
											if($nfile_val['tmp_name']) 
											{  
												// unlink($folder."/".$product['image']); 
												$img_name =  "N_". mt_rand(120,1230).".jpg";
												$path = $folder."/".$img_name; 
												$result = $this->upload_img('image',$path,$nfile_val['tmp_name']);
												if($result){
													$normal_imgs = strlen($normal_imgs) > 0 ? $normal_imgs."#".$img_name : $img_name;
												}
											}
										}
									} 
								} 
	 							$item_details = array(
    	 							'lot_no'	                =>$insId,
    								'lot_product'				=> (isset($itemData['lot_product']) ? $itemData['lot_product']:NULL),
    								'lot_id_design'				=> (isset($itemData['lot_id_design']) ? $itemData['lot_id_design']:NULL),
    								'no_of_piece'				=> (isset($itemData['pcs']) ? $itemData['pcs']:0),
    								'stock_type'				=> (isset($itemData['lot_product']) ? $itemData['lot_product']:1),
    								'gross_wt'				    => (isset($itemData['gross_wt']) ? $itemData['gross_wt']:NULL),
    								'gross_wt_uom'				=> (isset($itemData['gross_wt_uom']) ? $itemData['gross_wt_uom']:NULL),
    								'net_wt'				    => (isset($itemData['net_wt']) ? $itemData['net_wt']:NULL),
    								'net_wt_uom'				=> (isset($itemData['net_wt_uom']) ? $itemData['net_wt_uom']:NULL),
    								'less_wt'				    => (isset($itemData['less_wt']) ? $itemData['less_wt']:NULL),
    								'less_wt_uom'				=> (isset($itemData['less_wt_uom']) ? $itemData['less_wt_uom']:NULL),
    								'wastage_percentage'		=> (isset($itemData['wastage_percentage']) ? $itemData['wastage_percentage']:NULL),
    								'mc_type'				    => (isset($itemData['id_mc_type']) ? $itemData['id_mc_type']:1),
    								'making_charge'				=> (isset($itemData['making_charge']) ? $itemData['making_charge']:0),
    								'precious_stone'			=> (isset($itemData['precious_stone']) ? $itemData['precious_stone']:0),
    								'precious_st_pcs'			=> (isset($itemData['precious_st_pcs']) ? $itemData['precious_st_pcs']:NULL),
    								'precious_st_wt'			=> (isset($itemData['precious_st_wt']) ? $itemData['precious_st_wt']:NULL),
    								'semi_precious_stone'		=> (isset($itemData['semi_precious_stn']) ? $itemData['semi_precious_stn']:0),
    								'semi_precious_st_pcs'		=> (isset($itemData['semi_precious_st_pcs']) ? $itemData['semi_precious_st_pcs']:NULL),
    								'semi_precious_st_wt'		=> (isset($itemData['semi_precious_st_wt']) ? $itemData['semi_precious_st_wt']:NULL),
    								'normal_stone'				=> (isset($itemData['normal_stn']) ? $itemData['normal_stn']:0),
    								'normal_st_pcs'				=> (isset($itemData['normal_st_pcs']) ? $itemData['normal_st_pcs']:NULL),
    								'normal_st_wt'				=> (isset($itemData['normal_st_wt']) ? $itemData['normal_st_wt']:NULL),
    								'normal_st_wt_uom'			=> (isset($itemData['nor_wt_uom']) ? $itemData['nor_wt_uom']:NULL),
    								'semi_precious_st_uom'		=> (isset($itemData['semi_wt_uom']) ? $itemData['semi_wt_uom']:NULL),
    								'precious_st_uom'			=> (isset($itemData['pre_wt_uom']) ? $itemData['pre_wt_uom']:NULL),
    								'normal_st_certif'			=> ((strlen($normal_imgs) > 0)?$normal_imgs : NULL),
    								'semiprecious_st_certif'	=> ((strlen($sp_imgs) > 0)?$sp_imgs : NULL),
    								'precious_st_certif'		=> ((strlen($precious_imgs) > 0)?$precious_imgs : NULL),
    								//'updated_on'	  		    => date("Y-m-d H:i:s"),
    								//'updated_by'      		=>  $this->session->userdata('uid')
								); 
								$detail_insId = $this->$model->insertData($item_details,'ret_lot_inwards_detail');  
								
	 						}
	 					} 
					}
	 				if($updId){
	 					$this->$model->deleteData('lot_no', $id, 'ret_lot_inwards_detail');
	 					// DELETE UPLOADED IMAGES
	 					$itemData = $_POST['inward_item']; 
	 					if(isset($itemData))
	 					{
	 						foreach($itemData['product'] as $key => $val)
	 						{
	 							// Precious Stone Images
			 					$p_ImgData = json_decode($itemData['precious_st_certif']);  
								if(sizeof($p_ImgData) > 0){
									foreach($p_ImgData as $precious){
										$imgFile = $this->base64ToFile($precious->src);
										$_FILES['precious'][] = $imgFile;
									}
								}
								// Semi-precious Stone Images
			 					$sp_ImgData = json_decode($itemData['semiprecious_st_certif']); 
			 					if(sizeof($sp_ImgData) > 0) {
									foreach($sp_ImgData as $semi){
										$imgFile = $this->base64ToFile($semi->src);
										$_FILES['semi'][] = $imgFile;
									}
								}
								// Normal Stone Images
			 					$n_ImgData = json_decode($itemData['normal_st_certif']);  
			 					if(sizeof($n_ImgData) > 0) {
									foreach($n_ImgData as $normal){
										$imgFile = $this->base64ToFile($normal->src);
										$_FILES['normal'][] = $imgFile;
									} 
								}  
								/*echo "<pre>";print_r($itemData); 
								echo "<pre>";print_r($_FILES);exit;*/
							 	if(!empty($_FILES)){
									$img_arr = array();
									if($insId > 0)
									{
										$folder =  self::IMG_PATH."certificates/".$insId ;
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
										if(isset($_FILES['semi'])){ 
											$sp_imgs = "";
											foreach($_FILES['semi'] as $spfile_key => $spfile_val){
												if($spfile_val['tmp_name'])
												{
													// unlink($folder."/".$product['image']); 
													$img_name =  "SP_". mt_rand(120,1230).".jpg";
													$path = $folder."/".$img_name; 
													$result = $this->upload_img('image',$path,$spfile_val['tmp_name']);
													if($result){
														$sp_imgs = strlen($sp_imgs) > 0 ? $sp_imgs."#".$img_name : $img_name;
													}
												}
											} 
										}
										if(isset($_FILES['normal'])){
											$normal_imgs = "";
											foreach($_FILES['normal'] as $nfile_key => $nfile_val){
												if($nfile_val['tmp_name']) 
												{  
													// unlink($folder."/".$product['image']); 
													$img_name =  "N_". mt_rand(120,1230).".jpg";
													$path = $folder."/".$img_name; 
													$result = $this->upload_img('image',$path,$nfile_val['tmp_name']);
													if($result){
														$normal_imgs = strlen($normal_imgs) > 0 ? $normal_imgs."#".$img_name : $img_name;
													}
												}
											}
										} 
									} 
								}
	 							$item_details=array(
		 							'lot_no'	=>$updId,
									'lot_product'				=> (isset($itemData['lot_product']) ? $itemData['lot_product']:NULL),
									'lot_id_design'				=> (isset($itemData['lot_id_design']) ? $itemData['lot_id_design']:NULL),
									'no_of_piece'				=> (isset($itemData['pcs']) ? $itemData['pcs']:0),
									'stock_type'				=> (isset($itemData['lot_product']) ? $itemData['lot_product']:NULL),
									'gross_wt'				    => (isset($itemData['gross_wt']) ? $itemData['gross_wt']:NULL),
									'gross_wt_uom'				=> (isset($itemData['gross_wt_uom']) ? $itemData['gross_wt_uom']:NULL),
									'net_wt'				    => (isset($itemData['net_wt']) ? $itemData['net_wt']:NULL),
									'net_wt_uom'				=> (isset($itemData['net_wt_uom']) ? $itemData['net_wt_uom']:NULL),
									'less_wt'				    => (isset($itemData['less_wt']) ? $itemData['less_wt']:NULL),
									'less_wt_uom'				=> (isset($itemData['less_wt_uom']) ? $itemData['less_wt_uom']:NULL),
									'wastage_percentage'		=> (isset($itemData['wastage_percentage']) ? $itemData['wastage_percentage']:NULL),
									'mc_type'				    => (isset($itemData['id_mc_type']) ? $itemData['id_mc_type']:NULL),
									'making_charge'				=> (isset($itemData['making_charge']) ? $itemData['making_charge']:0),
									'precious_stone'			=> (isset($itemData['precious_stone']) ? $itemData['precious_stone']:0),
									'precious_st_pcs'			=> (isset($itemData['precious_st_pcs']) ? $itemData['precious_st_pcs']:NULL),
									'precious_st_wt'			=> (isset($itemData['precious_st_wt']) ? $itemData['precious_st_wt']:NULL),
									'semi_precious_stone'		=> (isset($itemData['semi_precious_stn']) ? $itemData['semi_precious_stn']:0),
									'semi_precious_st_pcs'		=> (isset($itemData['semi_precious_st_pcs']) ? $itemData['semi_precious_st_pcs']:NULL),
									'semi_precious_st_wt'		=> (isset($itemData['semi_precious_st_wt']) ? $itemData['semi_precious_st_wt']:NULL),
									'normal_stone'				=> (isset($itemData['normal_stn']) ? $itemData['normal_stn']:0),
									'normal_st_pcs'				=> (isset($itemData['normal_st_pcs']) ? $itemData['normal_st_pcs']:NULL),
									'normal_st_wt'				=> (isset($itemData['normal_st_wt']) ? $itemData['normal_st_wt']:NULL),
									'normal_st_wt_uom'			=> (isset($itemData['nor_wt_uom']) ? $itemData['nor_wt_uom']:NULL),
									'semi_precious_st_uom'		=> (isset($itemData['semi_wt_uom']) ? $itemData['semi_wt_uom']:NULL),
									'precious_st_uom'			=> (isset($itemData['pre_wt_uom']) ? $itemData['pre_wt_uom']:NULL),
									'normal_st_certif'			=> ((strlen($normal_imgs) > 0)?$normal_imgs : NULL),
    								'semiprecious_st_certif'	=> ((strlen($sp_imgs) > 0)?$sp_imgs : NULL),
    								'precious_st_certif'		=> ((strlen($precious_imgs) > 0)?$precious_imgs : NULL),
									'updated_on'	  		    => date("Y-m-d H:i:s"),
									'updated_by'      		    => $this->session->userdata('uid')
								); 
								$insId = $this->$model->insertData($item_details,'ret_lot_inwards_detail');
	 						}
	 						
	 					}
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
												$img_name =  $updId."_N_". mt_rand(120,1230).".jpg";
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
												$img_name =  $updId."_SP_". mt_rand(120,1230).".jpg";
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
												$img_name =  $updId."_P_". mt_rand(120,1230).".jpg";
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

function getActiveUOM()
{
	$model="ret_catalog_model";
	$data= $this->$model->getActiveUOM();
	echo json_encode($data);
}
 
public function get_lotInward_detail()
{
	$id=$this->input->post('id');
	$model = "ret_lot_model";
	$data=$this->$model->get_lotInward_detail($id);
	echo "<pre>"; print_r($data);exit;
	echo json_encode($data);
}


}	
?>
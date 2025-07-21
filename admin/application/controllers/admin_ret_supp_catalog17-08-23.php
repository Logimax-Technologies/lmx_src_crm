<?php
if( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin_ret_supp_catalog extends CI_Controller
{

	const SUPPIMG_PATH = 'assets/img/supplier/';

	function __construct()
	{
		parent::__construct();
		ini_set('date.timezone', 'Asia/Calcutta');
		$this->load->model('ret_supp_catalog_model');

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
	* Supplier Catalog Functions Starts
	*/
	public function supplier_catalog($type="",$id="") {
		
		$model = "ret_supp_catalog_model";
		
		switch($type) {
		
			case 'list':
				
				$data['main_content'] = "order/supplier_catalog/list" ;
				
				$this->load->view('layout/template', $data);
				
				break;
			case "add":
				
				$data['main_content'] = "order/supplier_catalog/form";

				$data['supp'] = $this->$model->get_empty_record();
				
				$this->load->view('layout/template', $data);
				
				break;
			
			case "edit":
			
				$data['main_content'] = "order/supplier_catalog/form" ;

				$data['supp'] = $this->$model->ajax_getSupplierCatList($id)[0];

				/*echo "<pre>";
				print_r($data['supp']);
				echo "</pre>";
				exit;*/

				$this->load->view('layout/template', $data);

				break;
			
			case "save":
					
				$datetime = date("Y-m-d H:i:s");

				$image_name = NULL;

				$supp_cat_img = self::SUPPIMG_PATH;

				$tmp_name = isset($_FILES['supp_cat_image']['tmp_name']) && $_FILES['supp_cat_image']['tmp_name'] != "" ? $_FILES['supp_cat_image']['tmp_name'] : '';

				if($tmp_name != "") {

					$image_name = uniqid().".jpg";

				}

				/*echo "<pre>";
				print_r($_POST);
				print_r($_FILES);
				echo "</pre>";exit;*/

				$product_id 	= $_POST['product_id'];
				$design_id 		= $_POST['design_id'];
				$id_sub_design 	= $_POST['subdesign_id'];
				$design_code    = $_POST['design_code'];
				$uid			= $this->session->userdata('uid');

				$insArray = array(
					'ctl_datetime'	=>	$datetime,
					'product_id'	=>	$product_id,
					'design_id'		=>	$design_id,
					'id_sub_design'	=>	$id_sub_design,
					'image'			=>	$image_name,
					'status'		=>	1,
					'design_code'	=>	$design_code,
					'created_on'	=>	$datetime,
					'created_by'	=>	$uid
				);

				$this->db->trans_begin();

				$insertId = $this->$model->insertData($insArray,'ret_supp_catalogue');

				if($insertId > 0) {

					$items = $_POST['item'];

					foreach($items['weight'] as $key => $item) {

						$weight 			= $items['weight'][$key];
						$purity 			= trim(trim($items['id_purity'][$key]),",");
						$size 				= trim(trim($items['id_size'][$key]),",");
						$wastage 			= $items['wastage'][$key];
						$display_va 		= $items['display_va'][$key];
						$mc_value 			= $items['mc_value'][$key];
						$mc_type 			= $items['mc_type'][$key];
						$display_mc 		= $items['display_mc'][$key];
						$delivery_duration 	= $items['delivery_duration'][$key];
						$display_duration 	= $items['display_duration'][$key];
						$karigar 			= trim(trim($items['id_karigar'][$key]),",");

						$insArray = array(
							'id_supp_catalogue '	=>	$insertId,
							'weight'				=>	$weight,
							'purity'				=>	$purity,
							'size'					=>	$size,
							'wastage'				=>	$wastage,
							'mc_value'				=>	$mc_value,
							'mc_type'				=>	$mc_type,
							'delivery_duration'		=>	$delivery_duration,
							'karigar'				=>	$karigar,
							'display_va'			=>  $display_va,
							'display_mc'			=>  $display_mc,
							'display_duration'		=>	$display_duration,
							'calculation_based_on'	=>	2
						);

						$this->$model->insertData($insArray,'ret_supp_catalogue_weight');

					}

				}

				if($this->db->trans_status()==TRUE) {

					$save_image = true;

					if($tmp_name != "") {

						$image_name = $insertId."-".$image_name;

						$save_image = $this->save_image($supp_cat_img, $image_name, $tmp_name);

					}

					if($save_image) {

						$this->db->trans_commit();

						$this->session->set_flashdata('chit_alert',array('message'=>'Supplier catalogue added successfully','class'=>'success','title'=>'Add Supplier Catalogue'));

					} else {

						$this->db->trans_rollback();

						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Add Supplier catalogue'));

					}

				} else {
				
					$this->db->trans_rollback();

					$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Add Supplier catalogue'));

				}

				redirect('admin_ret_supp_catalog/supplier_catalog/list');	

				break;

			case "update":

				$datetime = date("Y-m-d H:i:s");

				$image_name = NULL;

				$supp_cat_img = self::SUPPIMG_PATH;

				$tmp_name = isset($_FILES['supp_cat_image']['tmp_name']) && $_FILES['supp_cat_image']['tmp_name'] != "" ? $_FILES['supp_cat_image']['tmp_name'] : '';

				/*echo "<pre>";
				print_r($_POST);
				print_r($_FILES);
				echo "</pre>";exit;*/

				$product_id 	= $_POST['product_id'];
				$design_id 		= $_POST['design_id'];
				$id_sub_design 	= $_POST['subdesign_id'];
				$design_code    = $_POST['design_code'];
				$old_image		= $_POST['supp_old_image'];
				$uid			= $this->session->userdata('uid');

				$updArray = array(
					'ctl_datetime'	=>	$datetime,
					'product_id'	=>	$product_id,
					'design_id'		=>	$design_id,
					'id_sub_design'	=>	$id_sub_design,
					'status'		=>	1,
					'design_code'	=>	$design_code,
					'updated_on'	=>	$datetime,
					'updated_by'	=>	$uid
				);

				if($tmp_name != "") {

					$image_name = uniqid().".jpg";

					$updArray['image'] = $image_name;

				}

				$this->db->trans_begin();

				$updateId = $this->$model->updateData($updArray, 'id_supp_catalogue', $id, 'ret_supp_catalogue');

				if($updateId > 0) {

					if($this->$model->deleteData("id_supp_catalogue", $id, 'ret_supp_catalogue_weight')) {

						$items = $_POST['item'];

						foreach($items['weight'] as $key => $item) {

							$weight 			= $items['weight'][$key];
							$purity 			=  trim(trim($items['id_purity'][$key]),",");
							$size 				=  trim(trim($items['id_size'][$key]),",");
							$wastage 			= $items['wastage'][$key];
							$display_va 		= $items['display_va'][$key];
							$mc_value 			= $items['mc_value'][$key];
							$mc_type 			= $items['mc_type'][$key];
							$display_mc 		= $items['display_mc'][$key];
							$delivery_duration 	= $items['delivery_duration'][$key];
							$display_duration 	= $items['display_duration'][$key];
							$karigar 			=  trim(trim($items['id_karigar'][$key]),",");

							$insArray = array(
								'id_supp_catalogue '	=>	$updateId,
								'weight'				=>	$weight,
								'purity'				=>	$purity,
								'size'					=>	$size,
								'wastage'				=>	$wastage,
								'mc_value'				=>	$mc_value,
								'mc_type'				=>	$mc_type,
								'delivery_duration'		=>	$delivery_duration,
								'karigar'				=>	$karigar,
								'display_va'			=>  $display_va,
								'display_mc'			=>  $display_mc,
								'display_duration'		=>	$display_duration,
								'calculation_based_on'	=>	2
							);

							$this->$model->insertData($insArray,'ret_supp_catalogue_weight');

						}

					}

				}

				if($this->db->trans_status()==TRUE) {

					$save_image = true;

					if($tmp_name != "") {

						if($old_image != "") {

							$imgpath = $supp_cat_img."/".$updateId."-".$old_image;

							unlink($imgpath);

						}

						$image_name = $updateId."-".$image_name;

						$save_image = $this->save_image($supp_cat_img, $image_name, $tmp_name);

					}

					if($save_image) {

						$this->db->trans_commit();

						$this->session->set_flashdata('chit_alert',array('message'=>'Supplier catalogue added successfully','class'=>'success','title'=>'Add Supplier Catalogue'));

					} else {

						$this->db->trans_rollback();

						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Add Supplier catalogue'));

					}

				} else {
				
					$this->db->trans_rollback();

					$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Add Supplier catalogue'));

				}

				redirect('admin_ret_supp_catalog/supplier_catalog/list');	

				break;

			case 'delete' :

				$this->db->trans_begin();

				$imgpath = self::SUPPIMG_PATH;

				$supp_cat = $this->$model->ajax_getSupplierCatList($id)[0];

				$imgname = $id."-".$supp_cat['image'];

				$this->$model->deleteData('id_supp_catalogue',$id,'ret_supp_catalogue_weight');

				$this->$model->deleteData('id_supp_catalogue',$id,'ret_supp_catalogue');
				
				if( $this->db->trans_status()===TRUE) {

					$this->db->trans_commit();

					$imgpath = $imgpath.$imgname;

					unlink($imgpath);
					
					$this->session->set_flashdata('chit_alert', array('message' => 'Supplier catalogue deleted successfully','class' => 'success','title'=>'Settings'));	

				} else {
					
					$this->db->trans_rollback();

					$this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Settings'));
				}

				redirect('admin_ret_supp_catalog/supplier_catalog/list');
				
				break;

			default: 
				
				$list = $this->$model->ajax_getSupplierCatList();	 
				
				$access = $this->admin_settings_model->get_access('admin_ret_supp_catalog/supplier_catalog/list');
				
				$data = array(
								'list'  => $list,
								'access'=> $access
							);  
				
				echo json_encode($data);
		}
	}

	function save_image($imgpath, $imgname, $tmpname) {

        if (!is_dir($imgpath)) {

        	mkdir($imgpath, 0777, TRUE);

        }

		$imgpath = $imgpath."/".$imgname;

		if(!file_exists($imgpath)) {

			$this->upload_img($imgpath,$tmpname);

			return true;

		} else {

			return true;

		}

	}

	function upload_img($dst, $img)
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
}	
?>
<?php

if( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'libraries/dompdf/autoload.inc.php');

use Dompdf\Dompdf;

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

		$this->load->model('ret_catalog_model');

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

	 	

    function upload_img( $outputImage,$dst, $img,$quality=100)//$quality is a number between 1 and 100

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

		$res = imagejpeg($tmp, $dst,$quality); 

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

						$data['product_division']= $this->$model->getProductDivision();

						$data['main_content'] = "lot/form" ;

						$this->load->view('layout/template', $data);

					break;

			case 'list':

						$data['main_content'] = "lot/list" ;

						$this->load->view('layout/template', $data);

					break;

			case "save":   

					

					$addData = $_POST['inward']; 

					//echo "<pre>"; print_r($_POST);exit;

                    

                    $dCData          = $this->admin_settings_model->getBranchDayClosingData($addData['lot_received_at']);

			        $bill_date       = ($dCData['entry_date'] == date("Y-m-d") ? date("Y-m-d H:i:s") : $dCData['entry_date']);

					$purity 		 = (isset($addData['id_purity']) ? $addData['id_purity'] :NULL );

					$data = array(

							'lot_date'				=> $bill_date,

							'lot_type'				=> (isset($addData['lot_type']) ? $addData['lot_type'] :NULL ),

							'lot_received_at'		=> (isset($addData['lot_received_at']) ? $addData['lot_received_at'] :NULL ),

							'created_branch'		=> (isset($addData['lot_received_at']) ? $addData['lot_received_at'] :NULL ),

							'stock_type'            => (isset($addData['stock_type']) ? $addData['stock_type']:1),

							'gold_smith'			=> (isset($addData['gold_smith']) ? $addData['gold_smith'] :NULL ),

							'order_no'				=> (isset($addData['order_no']) && $addData['order_no'] != ""? $addData['order_no'] :NULL ),

							'id_category'			=> (isset($addData['id_category']) ? $addData['id_category'] :NULL ),

							'id_purity'				=> $purity,

							'product_division'		=> (isset($addData['id_product_division']) ? $addData['id_product_division'] :NULL ),

							'narration'      		=> (isset($addData['narration']) ? $addData['narration'] :NULL ),

							'order_branch'      	=> (($addData['order_branch']!='') ? $addData['order_branch'] :NULL ),

							'created_on'	  		=> date("Y-m-d H:i:s"),

							'created_by'      		=> $this->session->userdata('uid')

						); 

	 				$this->db->trans_begin();

	 				$insId = $this->$model->insertData($data,'ret_lot_inwards');

	 				//print_r($this->db->last_query());echo $insId;exit; 

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

								$result = $this->upload_img('image',$path,$_FILES['lot_image']['tmp_name'],100);

								if($result){

									$lot_imgs = strlen($lot_imgs) > 0 ? $lot_imgs."#".$img_name : $img_name;

								}

								$this->$model->updateData(array('lot_images'=>$lot_imgs),'lot_no',$insId,'ret_lot_inwards');

							} 

						}

	 					//echo "<pre>"; print_r($this->db->last_query());exit;

	 					if(sizeof($_POST['inward_item'])>0)

	 					{  	 						

	 						foreach($_POST['inward_item'] as $itemData)

	 						{  

	 							$_FILES = [];

	 							$precious_imgs = "";

	 							$sp_imgs = "";

	 							$normal_imgs = "";

	 							

	 							// Precious Stone Images

			 					$p_ImgData = json_decode($itemData['precious_st_certif']);  

			 				    if(!empty($p_ImgData))

			 				    {

			 				        if(sizeof($p_ImgData) > 0){

									foreach($p_ImgData as $precious){

										$imgFile = $this->base64ToFile($precious->src);

										$_FILES['precious'][] = $imgFile;

									}

								    }

			 				    }

								

								

								// Semi-precious Stone Images

			 					$sp_ImgData = json_decode($itemData['semiprecious_st_certif']); 

			 					if(!empty($sp_ImgData))

			 					{

			 					if(sizeof($sp_ImgData) > 0) {

									foreach($sp_ImgData as $semi){

										$imgFile = $this->base64ToFile($semi->src);

										$_FILES['semi'][] = $imgFile;

									}

								}

	 						    }

								// Normal Stone Images

			 					$n_ImgData = json_decode($itemData['normal_st_certif']);  

			 					if(!empty($n_ImgData))

			 					{

			 					    	if(sizeof($n_ImgData) > 0) {

									foreach($n_ImgData as $normal){

										$imgFile = $this->base64ToFile($normal->src);

										$_FILES['normal'][] = $imgFile;

									} 

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

    								'id_section'				=> ($itemData['id_section']!='' ? $itemData['id_section']:NULL),

    								'lot_product'				=> ($itemData['lot_product']!='' ? $itemData['lot_product']:NULL),

    								'lot_id_design'				=> ($itemData['lot_id_design']!='' ? $itemData['lot_id_design']:NULL),

    								'id_sub_design'             => ($itemData['id_sub_design']!='' ? $itemData['id_sub_design']:NULL),

    								'no_of_piece'				=> ($itemData['pcs']!='' ? $itemData['pcs']:0),

    								'gross_wt'				    => ($itemData['gross_wt']!='' ? $itemData['gross_wt']:NULL),

    								'gross_wt_uom'				=> ($itemData['gross_wt_uom']!='' ? $itemData['gross_wt_uom']:NULL),

    								'net_wt'				    => ($itemData['net_wt']!=''? $itemData['net_wt']:NULL),

    								'net_wt_uom'				=> ($itemData['net_wt_uom']!='' ? $itemData['net_wt_uom']:NULL),

    								'less_wt'				    => ($itemData['less_wt']!='' ? $itemData['less_wt']:NULL),

    								'less_wt_uom'				=> ($itemData['less_wt_uom']!='' ? $itemData['less_wt_uom']:NULL),

    								'wastage_percentage'		=> ($itemData['wastage_percentage']!='' ? $itemData['wastage_percentage']:NULL),

    								'mc_type'				    => ($itemData['id_mc_type']!='' ? $itemData['id_mc_type']:1),

    								'making_charge'				=> ($itemData['making_charge']!='' ? $itemData['making_charge']:0),

    								'precious_stone'			=> ($itemData['precious_stone']!='' ? $itemData['precious_stone']:0),

    								'precious_st_pcs'			=> ($itemData['precious_st_pcs']!='' ? $itemData['precious_st_pcs']:NULL),

    								'precious_st_wt'			=> ($itemData['precious_st_wt']!='' ? $itemData['precious_st_wt']:NULL),

    								'semi_precious_stone'		=> ($itemData['semi_precious_stn']!='' ? $itemData['semi_precious_stn']:0),

    								'semi_precious_st_pcs'		=> ($itemData['semi_precious_st_pcs']!='' ? $itemData['semi_precious_st_pcs']:NULL),

    								'semi_precious_st_wt'		=> ($itemData['semi_precious_st_wt']!='' ? $itemData['semi_precious_st_wt']:NULL),

    								'normal_stone'				=> ($itemData['normal_stn']!='' ? $itemData['normal_stn']:0),

    								'normal_st_pcs'				=> ($itemData['normal_st_pcs']!='' ? $itemData['normal_st_pcs']:NULL),

    								'normal_st_wt'				=> ($itemData['normal_st_wt']!='' ? $itemData['normal_st_wt']:NULL),

    								'normal_st_wt_uom'			=> ($itemData['nor_wt_uom']!='' ? $itemData['nor_wt_uom']:NULL),

    								'semi_precious_st_uom'		=> ($itemData['semi_wt_uom']!='' ? $itemData['semi_wt_uom']:NULL),

    								'precious_st_uom'			=> ($itemData['pre_wt_uom']!='' ? $itemData['pre_wt_uom']:NULL),

    								'normal_st_certif'			=> ((strlen($normal_imgs) > 0)?$normal_imgs : NULL),

    								'semiprecious_st_certif'	=> ((strlen($sp_imgs) > 0)?$sp_imgs : NULL),

    								'precious_st_certif'		=> ((strlen($precious_imgs) > 0)?$precious_imgs : NULL),

    								'current_branch'		    => (isset($addData['lot_received_at']) ? $addData['lot_received_at'] :NULL ),

    								'buy_rate'					=> ($itemData['buy_rate']!='' ? $itemData['buy_rate']:NULL),

    								'sell_rate'					=> ($itemData['sell_rate']!='' ? $itemData['sell_rate']:NULL),

    								'size'					=> ($itemData['size']!='' ? $itemData['size']:NULL),

    								'design_for'					=> ($itemData['design_for']!='' ? $itemData['design_for']:NULL),

									'lot_id_purity'				=> $purity

    								//'updated_on'	  		    => date("Y-m-d H:i:s"),

    								//'updated_by'      		=>  $this->session->userdata('uid')

								); 

								//echo "<pre>";print_r($item_details);exit;

								$detail_insId = $this->$model->insertData($item_details,'ret_lot_inwards_detail');  

								if($addData['stock_type']==2 && $detail_insId)

								{

								    $existData=array('id_section'=>$itemData['id_section'],'id_product'=>$itemData['lot_product'],'id_design'=>$itemData['lot_id_design'],'id_branch'=>$addData['lot_received_at'],'id_sub_design'=>$itemData['id_sub_design']);

								    $isExist = $this->$model->checkNonTagItemExist($existData);

							    	if($isExist['status'] == TRUE)

							    	{

							    	    $nt_data = array(

                                        'id_nontag_item'=>$isExist['id_nontag_item'],

                                        'no_of_piece'	=> ($itemData['pcs']!='' ? $itemData['pcs']:0),

                                        'gross_wt'		=> $itemData['gross_wt'],

                                        'net_wt'		=> $itemData['net_wt'],  

                                        'less_wt'		=> 0,  

                                        'updated_by'	=> $this->session->userdata('uid'),

                                        'updated_on'	=> date('Y-m-d H:i:s'),

                                        );

                                        $update_nt = $this->$model->updateNTData($nt_data,'+');

							    	}

							    	else

							    	{

                                        $non_tag_data_insert=array(

                                        'branch'        => $addData['lot_received_at'],

                                        'id_section'    => $itemData['id_section'],

                                        'product'	    => ($itemData['lot_product']!='' ? $itemData['lot_product']:NULL),

                                        'design'	    => ($itemData['lot_id_design']!='' ? $itemData['lot_id_design']:NULL),

                                        'id_sub_design'	=> ($itemData['id_sub_design']!='' ? $itemData['id_sub_design']:NULL),

                                        'no_of_piece'	=> ($itemData['pcs']!='' ? $itemData['pcs']:0),

                                        'gross_wt'		=> ($itemData['gross_wt']!='' ? $itemData['gross_wt']:NULL),

                                        'net_wt'		=> ($itemData['net_wt']!=''? $itemData['net_wt']:NULL),

                                        'created_on'    => date("Y-m-d H:i:s"),

                                        'created_by'    => $this->session->userdata('uid')

                                        );

                                        $ins_id = $this->$model->insertData($non_tag_data_insert,'ret_nontag_item');

							    	}

								    

								    $non_tag_data=array(

                                                'product'	    => ($itemData['lot_product']!='' ? $itemData['lot_product']:NULL),

                                                'design'	    => ($itemData['lot_id_design']!='' ? $itemData['lot_id_design']:NULL),

                                                'id_sub_design'	=> ($itemData['id_sub_design']!='' ? $itemData['id_sub_design']:NULL),

                                                'no_of_piece'	=> ($itemData['pcs']!='' ? $itemData['pcs']:0),

                                                'gross_wt'		=> ($itemData['gross_wt']!='' ? $itemData['gross_wt']:NULL),

                                                'net_wt'		=> ($itemData['net_wt']!=''? $itemData['net_wt']:NULL),

                                                'from_branch'	=> NULL,

                                                'to_branch'	    => $addData['lot_received_at'],

                                                'status'	    => 0,

                                                'date'          => $bill_date,

                                                'created_on'    => date("Y-m-d H:i:s"),

                                                'created_by'    => $this->session->userdata('uid')

								                 );

								    $this->$model->insertData($non_tag_data,'ret_nontag_item_log');

								    

								    

								    $item_non_tag_data=array(

										'to_section'	=> $itemData['id_section'],

										'from_section'	=> NULL,

										'product'	    => ($itemData['lot_product']!='' ? $itemData['lot_product']:NULL),

										'design'	    => ($itemData['lot_id_design']!='' ? $itemData['lot_id_design']:NULL),

										'id_sub_design'	=> ($itemData['id_sub_design']!='' ? $itemData['id_sub_design']:NULL),

										'no_of_piece'	=> ($itemData['pcs']!='' ? $itemData['pcs']:0),

										'gross_wt'		=> ($itemData['gross_wt']!='' ? $itemData['gross_wt']:NULL),

										'net_wt'		=> ($itemData['net_wt']!=''? $itemData['net_wt']:NULL),

										'from_branch'	=> NULL,

                                        'to_branch'	    => $addData['lot_received_at'],

										'status'	    => 0,

										'date'          => $bill_date,

										'created_on'    => date("Y-m-d H:i:s"),

										'created_by'    => $this->session->userdata('uid')

										 );

							           $this->$model->insertData($item_non_tag_data,'ret_section_nontag_item_log');

							           

								    //print_r($this->db->last_query());exit;

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

                        	'remark'       	=> 'Lot added successfully'

                        );

                        $this->log_model->log_detail('insert','',$log_data);

						$this->db->trans_commit();

						$this->session->set_flashdata('chit_alert',array('message'=>'Lot added successfully','class'=>'success','title'=>'Add Lot'));

						redirect('admin_ret_lot/lot_acknowladgement/1/'.$insId.'');



					}

					else

					{ 				

					 	echo $this->db->last_query();	 

					 	echo $this->db->_error_message();	exit;

						$this->db->trans_rollback();					 	

						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Add Lot'));

						redirect('admin_ret_lot/lot_inward/list');	



					}





				break;

					

			case "edit":

					$data['uom'] = $this->ret_catalog_model->getActiveUOM();

		 			$data['inward'] = $this->$model->get_lotInward($id);

		 			$data['inward_details'] = $this->$model->get_lotInward_detail($id);

		 			$data['product_division']= $this->$model->getProductDivision();

		 			//echo "<pre>"; print_r($data);exit;

		 			$data['inward']['lot_receive_settings'] = $this->$model->get_ret_settings('lot_recv_branch');

		 			$data['main_content'] = "lot/form" ;

					$this->load->view('layout/template', $data);

				break; 	 

						

			case 'delete':

					$this->db->trans_begin();

					$this->$model->deleteData('lot_no',$id,'ret_lot_inwards'); 

					if( $this->db->trans_status()===TRUE)

					{

						$path = SELF::IMG_PATH."lot/".$id;

						//chmod(SELF::IMG_PATH."lot",0777);

						$this->rrmdir($path);

						$log_data = array(

                        	'id_log'        => $this->session->userdata('id_log'),

                        	'event_date'	=> date("Y-m-d H:i:s"),

                        	'module'      	=> 'Lot',

                        	'operation'   	=> 'Delete',

                        	'record'        => $id,  

                        	'remark'       	=> 'Lot deleted successfully'

                        );

                        $this->log_model->log_detail('insert','',$log_data);

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

                   // echo "<pre>"; print_r($addData);print_r($_POST['inward_item']);exit;

                    $lot_imgs = "";

                    if(isset($_FILES['lot_image']))

                    {

                        $folder =  self::IMG_PATH."lot/".$id;

                        if (!is_dir($folder))

                        {

                            mkdir($folder, 0777, TRUE);

                        }

                        // foreach($_FILES['lot_image'] as $file_key => $file_val){

                        if($_FILES['lot_image']['tmp_name'] != '')

                        {

                            $img_name =  mt_rand(120,1230).".jpg";

                            $path = $folder."/".$img_name;

                            $result = $this->upload_img('image',$path,$_FILES['lot_image']['tmp_name']);

                            if($result)

                            {

                                $lot_imgs = strlen($lot_imgs) > 0 ? $lot_imgs."#".$img_name : $img_name;

                            }

                        }

                    }

					$purity = (isset($addData['id_purity']) ? $addData['id_purity'] :NULL );

                    $data = array(

                    'lot_type'          => (isset($addData['lot_type']) ? $addData['lot_type'] :NULL ),

                    'lot_received_at'   => (isset($addData['lot_received_at']) ? $addData['lot_received_at'] :NULL ),

                    'created_branch'    => (isset($addData['lot_received_at']) ? $addData['lot_received_at'] :NULL ),

                    'stock_type'        => (isset($addData['stock_type']) ? $addData['stock_type']:1),

                    'gold_smith'        => (isset($addData['gold_smith']) ? $addData['gold_smith'] :NULL ),

                    'order_no'          => (!empty($addData['order_no']) ? $addData['order_no'] :NULL ),

                    'id_category'       => (isset($addData['id_category']) ? $addData['id_category'] :NULL ),

                    'id_purity'         => $purity,

                    'product_division'	=> (isset($addData['id_product_division']) ? $addData['id_product_division'] :NULL ),

                    'narration'         => (isset($addData['narration']) ? $addData['narration'] :NULL ),

                    'updated_on'        => date("Y-m-d H:i:s"),

                    'updated_by'        => $this->session->userdata('uid')

                    );

                    if(strlen($lot_imgs) > 0)

                    {

                        $data['lot_images'] = $lot_imgs;

                    }

                    $this->db->trans_begin();

                    $updId = $this->$model->updateData($data,'lot_no',$id,'ret_lot_inwards');

                    

                    //print_r($this->db->last_query());exit;

                    if($updId > 0)

                    {  

                        if(sizeof($_POST['inward_item'])>0)

                        {

                            foreach($_POST['inward_item'] as $itemData)

                            {

                                $_FILES = [];

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

                                

                                if(!empty($_FILES))

                                {

                                    $img_arr = array();

                                    $folder =  self::IMG_PATH."lot/".$id."/"."certificates" ;

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

                                    

                                    

                                    if(isset($_FILES['semi']))

                                    {

                                        $sp_imgs = "";

                                        foreach($_FILES['semi'] as $spfile_key => $spfile_val)

                                        {

                                            if($spfile_val['tmp_name'])

                                            {

                                                // unlink($folder."/".$product['image']);

                                                $img_name =  "SP_". mt_rand(120,1230).".jpg";

                                                $path = $folder."/".$img_name;

                                                $result = $this->upload_img('image',$path,$spfile_val['tmp_name']);

                                                if($result)

                                                {

                                                    $sp_imgs = strlen($sp_imgs) > 0 ? $sp_imgs."#".$img_name : $img_name;

                                                }

                                            }

                                        }

                                    }

                                    

                                    if(isset($_FILES['normal']))

                                    {

                                        foreach($_FILES['normal'] as $nfile_key => $nfile_val)

                                        {

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

                                        'lot_no'=>$updId,

                                        'design_for'            => ($itemData['design_for']!='' ? $itemData['design_for']:NULL),

                                        'size'                  => ($itemData['size']!='' ? $itemData['size']:NULL),

                                        'lot_product'           => ($itemData['lot_product']!='' ? $itemData['lot_product']:NULL),

                                        'lot_id_design'         => ($itemData['lot_id_design']!='' ? $itemData['lot_id_design']:NULL),

                                        'no_of_piece'           => ($itemData['pcs']!='' ? $itemData['pcs']:0),

                                        'gross_wt'              => ($itemData['gross_wt']!='' ? $itemData['gross_wt']:NULL),

                                        'gross_wt_uom'          => ($itemData['gross_wt_uom']!='' ? $itemData['gross_wt_uom']:NULL),

                                        'net_wt'                => ($itemData['net_wt']!=''? $itemData['net_wt']:NULL),

                                        'net_wt_uom'            => ($itemData['net_wt_uom']!='' ? $itemData['net_wt_uom']:NULL),

                                        'less_wt'               => ($itemData['less_wt']!='' ? $itemData['less_wt']:NULL),

                                        'less_wt_uom'           => ($itemData['less_wt_uom']!='' ? $itemData['less_wt_uom']:NULL),

                                        'wastage_percentage'    => ($itemData['wastage_percentage']!='' ? $itemData['wastage_percentage']:NULL),

                                        'mc_type'               => ($itemData['id_mc_type']!='' ? $itemData['id_mc_type']:1),

                                        'making_charge'         => ($itemData['making_charge']!='' ? $itemData['making_charge']:0),

                                        'precious_stone'        => ($itemData['precious_stone']!='' ? $itemData['precious_stone']:0),

                                        'precious_st_pcs'       => ($itemData['precious_st_pcs']!='' ? $itemData['precious_st_pcs']:NULL),

                                        'precious_st_wt'        => ($itemData['precious_st_wt']!='' ? $itemData['precious_st_wt']:NULL),

                                        'semi_precious_stone'   => ($itemData['semi_precious_stn']!='' ? $itemData['semi_precious_stn']:0),

                                        'semi_precious_st_pcs'  => ($itemData['semi_precious_st_pcs']!='' ? $itemData['semi_precious_st_pcs']:NULL),

                                        'semi_precious_st_wt'   => ($itemData['semi_precious_st_wt']!='' ? $itemData['semi_precious_st_wt']:NULL),

                                        'normal_stone'          => ($itemData['normal_stn']!='' ? $itemData['normal_stn']:0),

                                        'normal_st_pcs'         => ($itemData['normal_st_pcs']!='' ? $itemData['normal_st_pcs']:NULL),

                                        'normal_st_wt'          => ($itemData['normal_st_wt']!='' ? $itemData['normal_st_wt']:NULL),

                                        'normal_st_wt_uom'      => ($itemData['nor_wt_uom']!='' ? $itemData['nor_wt_uom']:NULL),

                                        'semi_precious_st_uom'  => ($itemData['semi_wt_uom']!='' ? $itemData['semi_wt_uom']:NULL),

                                        'precious_st_uom'       => ($itemData['pre_wt_uom']!='' ? $itemData['pre_wt_uom']:NULL),

                                        'current_branch'        => (isset($addData['lot_received_at']) ? $addData['lot_received_at'] :NULL ),

                                        'buy_rate'              => ($itemData['buy_rate']!='' ? $itemData['buy_rate']:NULL),

                                        'sell_rate'             => ($itemData['sell_rate']!='' ? $itemData['sell_rate']:NULL),

										'lot_id_purity'			=> $purity

                                        //'updated_on'      => date("Y-m-d H:i:s"),

                                        //'updated_by'       =>  $this->session->userdata('uid')

                                );

                                

                                if(!empty($itemData['id_lot_inward_detail'])){// UPDATE

                                if(strlen($normal_imgs) > 0){

                                $item_details['normal_st_certif'] = $normal_imgs;

                                }

                                if(strlen($sp_imgs) > 0){

                                $item_details['semiprecious_st_certif'] = $sp_imgs;

                                }

                                if(strlen($precious_imgs) > 0){

                                $item_details['precious_st_certif'] = $precious_imgs;

                                }

                                $item_details['updated_on'] = date("Y-m-d H:i:s");

                                $item_details['updated_by'] = $this->session->userdata('uid');

                                

                                $idLtDetail = $this->$model->updateData($item_details,'id_lot_inward_detail',$itemData['id_lot_inward_detail'],'ret_lot_inwards_detail');

                                //print_r($this->db->last_query());exit;

                                }else{ // INSERT

                                

                                $detail_insId = $this->$model->insertData($item_details,'ret_lot_inwards_detail');  

                                //echo "<pre>"; print_r($this->db->last_query());exit;

                                }

                            }

                        }

                    }

                    //print_r($this->db->_error_message());exit;

                    if($this->db->trans_status()===TRUE)

                    {

                       $log_data = array(

                                            'id_log'        => $this->session->userdata('id_log'),

                                            'event_date' => date("Y-m-d H:i:s"),

                                            'module'       => 'Lot',

                                            'operation'   => 'Edit',

                                            'record'        => $id,  

                                            'remark'       => 'Lot updated successfully'

                                            );

                                            $this->log_model->log_detail('insert','',$log_data);

                    $this->db->trans_commit();

                    $this->session->set_flashdata('chit_alert',array('message'=>'Lot updated successfully','class'=>'success','title'=>'Update Lot'));

                    

                    }

                    else

                    {

                    $this->db->trans_rollback();

                    echo $this->db->last_query();

                       echo $this->db->_error_message(); exit;

                    $this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Update Lot'));

                    }

                    redirect('admin_ret_lot/lot_inward/list');

				break;

			default: 

					  	$from_date	= $this->input->post('from_date');

			        	$to_date	= $this->input->post('to_date'); 

			        	$received_at	= $this->input->post('rcvd_at_branch'); 

			        	$id_metal	= $this->input->post('id_metal'); 

			        	$emp_id	= $this->input->post('emp_id'); 

					  	$list = $this->$model->ajax_getLotList($from_date,$to_date,$id_metal,$emp_id);

					  	$access = $this->admin_settings_model->get_access('admin_ret_lot/lot_inward/list');

				        $data = array(

				        					'list'  => $list,

											'access'=> $access

				        				);  

						echo json_encode($data);

		}

	}



	 

	public function get_lotInward_detail()

	{

		$id=$this->input->post('id');

		$model = "ret_lot_model";

		$data=$this->$model->get_lotInward_detail($id);

		echo "<pre>"; print_r($data);exit;

		echo json_encode($data);

	}



	public function lot_inwards_detail()

	{

		$model = "ret_lot_model";

		$id_lot_inward_detail=$this->input->post('id_lot_inward_detail');

		$this->db->trans_begin();

		$this->$model->deleteData('id_lot_inward_detail',$id_lot_inward_detail,

			'ret_lot_inwards_detail'); 

		//print_r($this->db->last_query());exit;

		if( $this->db->trans_status()===TRUE)

		{

		    $log_data = array(

                        	'id_log'        => $this->session->userdata('id_log'),

                        	'event_date'	=> date("Y-m-d H:i:s"),

                        	'module'      	=> 'Lot',

                        	'operation'   	=> 'Delete',

                        	'record'        => $id_lot_inward_detail,  

                        	'remark'       	=> 'Lot Item deleted successfully'

                        );

            $this->log_model->log_detail('insert','',$log_data);

			$this->db->trans_commit();

			$data=array('status'=>true,'message'=>'Lot Item Deleted successfully');

			

		}			  

		else

		{

			$this->db->trans_rollback();

			$data=array('status'=>false,'message'=>'Unable To proceed your request');

		}

		echo json_encode($data);

	} 

	

	public function vendor_acknowladgement($type,$lot_id)

	{

		$model = "ret_lot_model";

		$set_model = "admin_settings_model";

		$data['comp_details'] = $this->$set_model->get_company();

		$data['lot_inwards_detail']=$this->$model->lotInward_detail($lot_id);

		$data['lot_det']=$this->$model->get_lot_details($lot_id);

		$data['type']=$type;

		//echo "<pre>"; print_r($data);exit;

		$this->load->helper(array('dompdf', 'file'));

	        $dompdf = new DOMPDF();

			$html = $this->load->view('lot/print/vendor_ack', $data,true);

			$dompdf->load_html($html);

			$dompdf->set_paper('A4', "portriat" );

			$dompdf->render();

			$dompdf->stream("VendorAck.pdf",array('Attachment'=>0));

	}



	public function lot_acknowladgement($type,$lot_id)

	{

		$model = "ret_lot_model";

		$set_model = "admin_settings_model";

		$data['comp_details'] = $this->$set_model->get_company();

		$data['lot_inwards_detail']=$this->$model->lotInward_detail($lot_id);

		$data['lot_det']=$this->$model->get_lot_details($lot_id);       //Lot Summary

		$data['tag_det']=$this->$model->get_lot_tag_details($lot_id);   //Branch Summary

		//echo "<pre>"; print_r($data);exit;

		$data['type']=$type;

		$this->load->helper(array('dompdf', 'file'));

	        $dompdf = new DOMPDF();

			$html = $this->load->view('lot/print/office_ack', $data,true);

			$dompdf->load_html($html);

			$dompdf->render();

			$dompdf->set_paper('A4', "portriat");			

			$dompdf->stream("VendorAck.pdf",array('Attachment'=>0));

	}



	public function branch_acknowladgement($type,$lot_id,$id_branch)

	{

		$model = "ret_lot_model";

		$set_model = "admin_settings_model";

		$data['comp_details'] = $this->$set_model->get_company();

		$data['lot_inwards_detail']=$this->$model->lotInward_detail($lot_id);

		$data['tag_details']=$this->$model->get_tagdetails_by_lot($lot_id,$id_branch);

		$data['summary']=$this->$model->get_branch_summary($lot_id,$id_branch);

		$data['type']=$type;

		//echo "<pre>"; print_r($data);exit;

		$this->load->helper(array('dompdf', 'file'));

	        $dompdf = new DOMPDF();

			$html = $this->load->view('lot/print/branch_ack', $data,true);

			$dompdf->load_html($html);

			$dompdf->render();

			$dompdf->set_paper('A4', "portriat");	

			$dompdf->stream("VendorAck.pdf",array('Attachment'=>0));

	}



	function getOrderNosBySearch()

	{

		$model = "ret_lot_model";

		$result = $this->$model->getOrderNos($_POST['searchTxt']);	

		echo json_encode($result); 

	}



	function get_order_details()

	{

		$model = "ret_lot_model";

		$data  = $this->$model->get_order_details($_POST['orderno'],$_POST['id_karigar'],$_POST['id_branch']);

		echo json_encode($data);

	}

	

	

	function get_karigar_list()

	{

		$model = "ret_lot_model";

		$result = $this->$model->get_karigar_list($_POST['order_no']);	

		echo json_encode($result); 

	}

	

	 public function getProductBySearch(){

		$model = "ret_lot_model";

		$CatCode=(isset($_POST['cat_id']) ? $_POST['cat_id']:'');

		$stock_type=(isset($_POST['stock_type']) ? $_POST['stock_type']:'');  //1-Tagged,2-Non Tagged

		$data = $this->$model->getProductBySearch($_POST['searchTxt'], $CatCode,$stock_type);	  

		echo json_encode($data);

	}



	public function lot_merge($type="",$id="")

	{		

		$model = "ret_lot_model";

		switch($type)

		{		

			case 'list':

				$data['inward'] = $this->$model->empty_record_inward();

				$data['uom']= $this->ret_catalog_model->getActiveUOM();

				$data['main_content'] = "lot/lot_merge" ;

				$this->load->view('layout/template', $data);

			break;



			case 'getLotNos':

				$data  = $this->$model->getLotNoForMerge($_POST);

		        echo json_encode($data);

			break;	



			case 'getLotidsforMerge':

				$data  = $this->$model->getLotidsforMerge($_POST);

		        echo json_encode($data);

			break;	



			case 'save':

				$addData = $_POST['inward'];

				//echo "<pre>"; print_r($_POST);exit;

				$data = array(

					'lot_date'				=> date("Y-m-d H:i:s"),

					'lot_received_at'		=> (isset($addData['lot_received_at']) ? $addData['lot_received_at'] :NULL ),

					'created_branch'		=> (isset($addData['lot_received_at']) ? $addData['lot_received_at'] :NULL ),

					'stock_type'            => (isset($addData['stock_type']) ? $addData['stock_type']:1),

					'gold_smith'			=> (isset($addData['gold_smith']) ? $addData['gold_smith'] :NULL ),

					'lot_from'              => 7,

					'created_on'	  		=> date("Y-m-d H:i:s"),

					'created_by'      		=> $this->session->userdata('uid')

				); 

				$this->db->trans_begin();

				$insId = $this->$model->insertData($data,'ret_lot_inwards');

				if($insId > 0)

				{

					if(sizeof($_POST['merge_item'])>0)

					{

						foreach($_POST['merge_item'] as $itemData)

						{

					

							$item_details = array(

								'lot_no'	                => $insId,

								'lot_id_category'           => ($itemData['lm_cat']!='' ? $itemData['lm_cat']:NULL),

								'lot_product'				=> ($itemData['lm_pro_id']!='' ? $itemData['lm_pro_id']:NULL),

								'lot_id_design'				=> ($itemData['lm_des_id']!='' ? $itemData['lm_des_id']:NULL),

								'lot_id_purity'				=> ($itemData['lm_purity']!='' ? $itemData['lm_purity']:NULL),

								'no_of_piece'				=> ($itemData['pcs']!='' ? $itemData['pcs']:0),

								'gross_wt'				    => ($itemData['gross_wt']!='' ? $itemData['gross_wt']:NULL),

								'gross_wt_uom'				=> ($itemData['gross_wt_uom']!='' ? $itemData['gross_wt_uom']:NULL),

								'net_wt'				    => ($itemData['net_wt']!=''? $itemData['net_wt']:NULL),

								'net_wt_uom'				=> ($itemData['net_wt_uom']!='' ? $itemData['net_wt_uom']:NULL),

								'less_wt'				    => ($itemData['less_wt']!='' ? $itemData['less_wt']:NULL),

								'less_wt_uom'				=> ($itemData['less_wt_uom']!='' ? $itemData['less_wt_uom']:NULL),

								'wastage_percentage'		=> ($itemData['wastage_percentage']!='' ? $itemData['wastage_percentage']:NULL),

								'mc_type'				    => ($itemData['id_mc_type']!='' ? $itemData['id_mc_type']:1),

								'making_charge'				=> ($itemData['making_charge']!='' ? $itemData['making_charge']:0),

								'size'					    => ($itemData['size']!='' ? $itemData['size']:NULL),

							);

							$detail_insId = $this->$model->insertData($item_details,'ret_lot_inwards_detail');

							if($detail_insId)

							{

							



								if($addData['stock_type']==2 && $detail_insId)

								{

									$existData=array('id_product'=>$itemData['lm_pro_id'],'id_design'=>$itemData['lm_des_id'],'id_branch'=>$addData['lot_received_at']);

									$isExist = $this->$model->checkNonTagItemExist($existData);

									if($isExist['status'] == TRUE)

									{

										$nt_data = array(

										'id_nontag_item'=>$isExist['id_nontag_item'],

										'no_of_piece'	=> ($itemData['pcs']!='' ? $itemData['pcs']:0),

										'gross_wt'		=> $itemData['gross_wt'],

										'net_wt'		=> $itemData['net_wt'],  

										'less_wt'		=> 0,  

										'updated_by'	=> $this->session->userdata('uid'),

										'updated_on'	=> date('Y-m-d H:i:s'),

										);

										$update_nt = $this->$model->updateNTData($nt_data,'+');

									}

									else

									{

										$non_tag_data_insert=array(

										'branch'        => $addData['lot_received_at'],

										'product'	    => ($itemData['lm_pro_id']!='' ? $itemData['lm_pro_id']:NULL),

										'design'	    => ($itemData['lm_des_id']!='' ? $itemData['lm_des_id']:NULL),

										'no_of_piece'	=> ($itemData['pcs']!='' ? $itemData['pcs']:0),

										'gross_wt'		=> ($itemData['gross_wt']!='' ? $itemData['gross_wt']:NULL),

										'net_wt'		=> ($itemData['net_wt']!=''? $itemData['net_wt']:NULL),

										'created_on'    => date("Y-m-d H:i:s"),

										'created_by'    => $this->session->userdata('uid')

										);

										$ins_id = $this->$model->insertData($non_tag_data_insert,'ret_nontag_item');

									}

								

									$non_tag_data=array(

												'product'	    => ($itemData['lm_pro_id']!='' ? $itemData['lm_pro_id']:NULL),

												'design'	    => ($itemData['lm_des_id']!='' ? $itemData['lm_des_id']:NULL),

												'no_of_piece'	=> ($itemData['pcs']!='' ? $itemData['pcs']:0),

												'gross_wt'		=> ($itemData['gross_wt']!='' ? $itemData['gross_wt']:NULL),

												'net_wt'		=> ($itemData['net_wt']!=''? $itemData['net_wt']:NULL),

												'from_branch'	=> NULL,

												'to_branch'	    => 1,

												'status'	    => 0,

												'date'          => date("Y-m-d H:i:s"),

												'created_on'    => date("Y-m-d H:i:s"),

												'created_by'    => $this->session->userdata('uid')

													);

									$this->$model->insertData($non_tag_data,'ret_nontag_item_log'); 

									//print_r($this->db->last_query());exit;

								}

							}

						} 

						

					}

					if(sizeof($_POST['lot_merge'])>0)

					{

						foreach($_POST['lot_merge'] as $items)

						{

							$details = array(

								'lot_no'                =>  $insId,

								'id_lot_inward_detail'  =>  $items['lot_det_id'],

								'created_on'            =>  date("Y-m-d H:i:s"),

								'created_by'            =>  $this->session->userdata('uid')

							);

							$lotmerge_id = $this->$model->insertData($details,'ret_lot_merge');



							if(sizeof($items['stone_details'])>0)

							{							

								$stone_details = json_decode($items['stone_details'],true);								

								foreach($stone_details as $stn)

								{

									$stone_data = array(										

										"id_lot_inward_detail"  =>  $detail_insId,

										"stone_id"              =>  $stn['stone_id'],

										"uom_id"                =>  $stn['uom_id'],

										"stone_pcs"             =>  $stn['stn_pcs'],

										"stone_wt"              =>  $stn['stn_wt'],

									);

									//echo "<pre>";print_r($stone_details);exit;

									$stoneInsert = $this->$model->insertData($stone_data,'ret_lot_inwards_stone_detail');

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

						'remark'       	=> 'Lot added successfully'

					);

					$this->log_model->log_detail('insert','',$log_data);

					$this->db->trans_commit();

					$this->session->set_flashdata('chit_alert',array('message'=>'Lot added successfully','class'=>'success','title'=>'Add Lot'));

					redirect('admin_ret_lot/lot_acknowladgement/1/'.$insId.'');



				}

				else

				{ 				

					echo $this->db->last_query();	 

					echo $this->db->_error_message();	exit;

					$this->db->trans_rollback();					 	

					$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Add Lot'));

					redirect('admin_ret_lot/lot_merge/list');	



				}

	

			break;

		}

	}





	function get_ActiveProduct()

    {

    	$model = "ret_lot_model";

    	$data=$this->$model->get_ActiveProduct($_POST);

    	echo json_encode($data);

    }





	function lot_split($type="")

	{

		$model = "ret_lot_model";

		switch($type)

		{		

			case 'list':

				$data['inward'] = $this->$model->empty_record_inward();

				$data['uom']= $this->ret_catalog_model->getActiveUOM();

				$data['main_content'] = "lot/lot_split" ;

				$this->load->view('layout/template', $data);

			break;

			case 'lotNosForsplit':

				$data  = $this->$model->getLotNoForSplit($_POST);

		        echo json_encode($data);

			break;

			

			case 'getLotDetails':

				$data  = $this->$model->getLotDetails($_POST);

		        echo json_encode($data);

			break;	



			case 'getLotidsforSplit':

				$data  = $this->$model->getLotidsforSplit();

		        echo json_encode($data);

			break;	



			case 'save':

				//echo "<pre>";print_r($_POST);exit;

				if(sizeof($_POST['split_item'])>0)

				{

					foreach($_POST['split_item'] as $itemData)

					{

						$this->$model->updateData(array('is_lot_split' => 1),'lot_no',$itemData['lot_no'],'ret_lot_inwards');

						$item_details = array(

							'id_lot_inward_detail'  =>  $itemData['id_lot_inward_detail'],

							'id_employee'           =>  $itemData['id_employee'],

							'id_category'           =>  $itemData['cat_id'],

							'id_purity'             =>  $itemData['id_purity'],

							'id_product'            =>  $itemData['pro_id'],

							'split_pcs'             =>  $itemData['split_pcs'],

							'split_grs_wt'          =>  $itemData['split_wt'],

							'split_net_wt'          =>  $itemData['split_nwt'],

							'split_stn_pcs'         =>  $itemData['split_stn_pcs'],

							'split_stn_wt'          =>  $itemData['split_stn_wt'],

							'split_dia_pcs'         =>  $itemData['split_dia_pcs'],

							'split_dia_wt'          =>  $itemData['split_dia_wt'],

							'created_on'	  		=> date("Y-m-d H:i:s"),

							'created_by'      		=> $this->session->userdata('uid')

						);

						$splitinsId = $this->$model->insertData($item_details,'ret_lot_split_details');

					}

				}

				

				if($this->db->trans_status()===TRUE)

				{ 

					$this->db->trans_commit();

					$this->session->set_flashdata('chit_alert',array('message'=>'Lot Splited successfully','class'=>'success','title'=>'Add Lot'));

					redirect('admin_ret_lot/lot_split/list');

				}

				else

				{ 				

					echo $this->db->last_query();	 

					echo $this->db->_error_message();exit;

					$this->db->trans_rollback();					 	

					$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Add Lot'));

					redirect('admin_ret_lot/lot_split/list');	

				}

			break;

		}

	}

	function lot_completed()
	{
		$model = "ret_lot_model";
		$lot_completed = $_POST['completed_lot'];
		$id_branch = $_POST['branch'];
		$dCData          = $this->admin_settings_model->getBranchDayClosingData($id_branch);
		$lot_closed_date   = ($dCData['entry_date'] == date("Y-m-d") ? date("Y-m-d H:i:s") : $dCData['entry_date']);
		$this->db->trans_begin();
		foreach($lot_completed as $key=>$val)
		{
			$statusData =array(			
				'is_closed'      => 1,				
				'closed_by'   => $this->session->userdata('uid'),				
				'closed_on'   => $lot_closed_date,
			);

			$this->$model->updateData($statusData,'lot_no ',$val['lot_no'],'ret_lot_inwards');
			//print_r($this->db->last_query());exit;
		}
		if($this->db->trans_status()===TRUE)
		{
			$this->db->trans_commit();
			$this->session->set_flashdata('chit_alert',array('message'=>'Closed successfully','class'=>'success','title'=>'Lot'));
			$return_data=array('message'=>'Closed successfully','status'=>true);
			echo json_encode($return_data);
		}
		else
		{
			$this->db->trans_rollback();						 	
			$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Lot'));
			$return_data=array('message'=>'Unable to proceed the requested process','status'=>false);
			echo json_encode($return_data);
		}
	}



}	

?>
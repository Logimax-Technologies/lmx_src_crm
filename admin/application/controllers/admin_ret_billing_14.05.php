<?php
if( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin_ret_billing extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		ini_set('date.timezone', 'Asia/Calcutta');
		$this->load->model('ret_billing_model');
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
	
	public function billing($type="", $id=""){
		$model = "ret_billing_model";
		$data['type']	= $type;
		switch($type)
		{
			case 'add':
					$data['billing']		= $this->$model->get_empty_record();
					$data['bill_other_item'] = array("item_details" => array(), "old_matel_details" => array(), "stone_details" => array(), "other_material_details" => array(), "voucher_details" => array(), "chit_details" => array(), "advance_details" => array());
					$data['uom']		= $this->$model->getUOMDetails();
					$data['main_content'] = "billing/form" ;
					$this->load->view('layout/template', $data);
				break;
			case 'list':
					$data['main_content'] = "billing/list" ;
					$this->load->view('layout/template', $data);
				break;
			case "save": 
					$addData = $_POST['billing'];
					/* echo "<pre>";
					print_r($_POST);
					echo "</pre>";
					exit; */
					
					$addData['bill_date'] = date('Y-m-d',strtotime(str_replace("/","-",$addData['bill_date'])));
					$bill_no = $this->$model->code_number_generator();
					$data = array(
						'bill_no'		=> $bill_no,
						'bill_date'		=> (!empty($addData['bill_date']) ? $addData['bill_date'] : NULL ),
						'bill_cus_id'	=> (!empty($addData['bill_cus_id']) ? $addData['bill_cus_id'] :NULL ),
						'tot_discount'	=> (!empty($addData['discount']) ? $addData['discount'] : 0 ),
						'tot_bill_amt'	=> (!empty($addData['total_cost']) ? $addData['total_cost'] : 0 ),
						'created_time'	=> date("Y-m-d H:i:s"),
						'created_by'    => $this->session->userdata('uid'),
						'id_branch'     => $addData['id_branch']
					); 
	 				$this->db->trans_begin();
	 				$insId = $this->$model->insertData($data,'ret_billing');
	 				if($insId){
						$billSale = $_POST['sale'];
						if(!empty($billSale)){
							$arrayBillSales = array();
							foreach($billSale['is_est_details'] as $key => $val){
								$arrayBillSales[] = array(
									'bill_id' => $insId, 
									'item_type' 	=> $billSale['itemtype'][$key], 
									'bill_type' 	=> $billSale['is_est_details'][$key],
									'product_id' 	=> $billSale['product'][$key],
									'tag_id'		=> $billSale['tag'][$key],
									'quantity' 		=> 1, 
									'purity' 		=> $billSale['purity'][$key], 
									'size' 			=> $billSale['size'][$key], 
									'uom' 			=> $billSale['uom'][$key], 
									'piece' 		=> $billSale['pcs'][$key], 
									'less_wt' 		=> $billSale['less'][$key], 
									'net_wt' 		=> $billSale['net'][$key], 
									'gross_wt' 		=> $billSale['gross'][$key], 
									'calculation_based_on' => $billSale['calltype'][$key], 
									'wastage_percent' => $billSale['wastage'][$key], 
									'mc_per_grm' 	=> $billSale['mc'][$key], 
									'item_cost' 	=> $billSale['billamount'][$key], 
									'tax_group_id'  => $billSale['taxgroup'][$key],
									'bill_discount' => empty($billSale['discount'][$key]) ? 0 : $billSale['discount'][$key], 'rate_per_grm' => $billSale['per_grm'][$key]
								); 
							}
							if(!empty($arrayBillSales)){
								$tagInsert = $this->$model->insertBatchData($arrayBillSales,'ret_bill_details'); 
								$this->$model->updateData(array('tag_status'=>1),'tag_id',$billSale['tag'][$key], 'ret_taging');
							}
						}
						$billPurchase = $_POST['purchase'];
						if(!empty($billPurchase)){
							$arrayPurchaseBill = array();
							foreach($billPurchase['is_est_details'] as $key => $val){
								if($billPurchase['is_est_details'][$key] == 1){
									$arrayPurchaseBill[] = array('bill_id' => $insId, 'metal_type' => $billPurchase['metal_type'][$key], 'item_type' => $billPurchase['itemtype'][$key], 'gross_wt' => $billPurchase['gross'][$key], 'stone_wt' => $billPurchase['less'][$key],'dust_wt' => $billPurchase['less'][$key],'wastage_percent' => $billPurchase['wastage'][$key], 'rate' => $billPurchase['billamount'][$key], 'rate_per_grm' => $billPurchase['rate_per_grm'][$key], 'bill_discount' => empty($billPurchase['discount'][$key]) ? 0 : $billPurchase['discount'][$key]); 
								}
							}
							if(!empty($arrayPurchaseBill)){
								$tagInsert = $this->$model->insertBatchData($arrayPurchaseBill,'ret_bill_old_metal_sale_details'); 
							}
						}
						
					}
					if($this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
						$this->session->set_flashdata('chit_alert',array('message'=>'Billing added successfully','class'=>'success','title'=>'Add Billing'));
				 	
					}
					else
					{
						$this->db->trans_rollback();						 	
						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Add Billing'));
					}
					//echo $this->db->_error_message()."<br/>";					   echo $this->db->last_query();exit;
					redirect('admin_ret_billing/billing/list');	
				break;
					
			case "edit":
		 			$data['billing'] = $this->$model->get_entry_records($id);
					$data['uom']= $this->$model->getUOMDetails();
					$data['est_other_item'] = $this->$model->getOtherEstimateItemsDetails($id);
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

					$this->db->trans_begin();
					$update_status = $this->$model->updateData($data,'estimation_id',$id, 'ret_estimation');
					if($update_status){
												
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
			
						
			case 'update_status':
						redirect('admin_ret_billing/billing/list');
				
			default: 
					  	$list = $this->$model->ajax_getBillingList();	 
					  	$access = $this->admin_settings_model->get_access('admin_ret_billing/billin/list');
				        $data = array(
				        					'list'  => $list,
											'access'=> $access
				        				);  
						echo json_encode($data);
		}
	}
	public function createNewCustomer(){
		$model = "ret_billing_model";
		if(!empty($_POST['cusName']) && !empty($_POST['cusMobile']) && !empty($_POST['cusBranch'])){
			$data = $this->$model->createNewCustomer($_POST['cusName'], $_POST['cusMobile'], $_POST['cusBranch']);
			echo json_encode($data);
		}else{
			echo json_encode(array("success" => FALSE, "response" => array(), "message" => "Please fill all the required fields"));
		}
	}
	public function getEstimationDetails(){
		$model = "ret_billing_model";
		if(!empty($_POST['cusId']) && !empty($_POST['estId'])){
			$data = $this->$model->getEstimationDetails($_POST['cusId'], $_POST['estId']);
			if(!empty($data)){
				echo json_encode(array('success' => TRUE, 'message' => 'Records reterived successfully.', 'responsedata' => $data));
			}else{
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
	
}	
?>
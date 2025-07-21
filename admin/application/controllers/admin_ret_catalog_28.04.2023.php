<?php
if( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin_ret_catalog extends CI_Controller
{
	
	const CAT_MODEL	= "ret_catalog_model";
	const SETT_MOD	= "admin_settings_model";
	const CATE_VIEW	= "master/category/";
	const SUBCATE_VIEW	= "master/sub_category/";
	const IMG_PATH  = 'assets/img/';
	const PROD_PATH  = 'assets/img/products/';
	const DESIGN_PATH  = 'assets/img/designs/';
	const MAS_VIEW 		= 'master/';
	
	function __construct()
	{
		parent::__construct();
		ini_set('date.timezone', 'Asia/Calcutta');
		$this->load->model(self::CAT_MODEL);
		$this->load->model(self::SETT_MOD);
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
		//$this->cus_list();
	}
		
	public function ajax_active_subCtg(){
		$model=self::CAT_MODEL;
		$subctg= $this->$model->getActiveSubctg();	
		 $data = array(
    					'sub_category'=> $subctg
    	        		);  
	echo json_encode($data);
	}
	
	public function active_color(){
		$model = self::CAT_MODEL;
		$color = $this->$model->getActivecolor();	
		 $data = array(
    					'color'=> $color
    	        		);  
	echo json_encode($data);
	}
	 
	
	public function active_metals(){
		$model = self::CAT_MODEL;
		$data = $this->$model->getActiveMetals();
	echo json_encode($data);
	}
	
	public function active_masters(){
		$model = self::CAT_MODEL;
		$cut = $this->$model->getActiveCut();	
		$clarity = $this->$model->getActiveClarity();	
		//$carat = $this->$model->getActiveCarat();	
		 $data = array(
    					'cut'	 => $cut,
    					'clarity'=> $clarity,
    					'carat'  => $carat
    	        		);  
	echo json_encode($data);
	}
	/*public function active_carat(){
		$model = self::CAT_MODEL;
		$data = $this->$model->getActiveCarat();	
	echo json_encode($data);
	}*/
	public function active_clarity(){
		$model = self::CAT_MODEL;	
		$data = $this->$model->getActiveClarity();
	echo json_encode($data);
	}
	public function active_cut(){
		$model = self::CAT_MODEL;
		$data = $this->$model->getActiveCut();	
	echo json_encode($data);
	}
	
	public function metal_info_list($prod_id){
		$model = self::CAT_MODEL;
		$prod_mInfo = $this->$model->getmetalInfo($prod_id);	
		$prod_dInfo = $this->$model->getdiamondInfo($prod_id);
		$cut = $this->$model->getActiveCut();	
		$clarity = $this->$model->getActiveClarity();	
//		$carat = $this->$model->getActiveCarat();
		$color = $this->$model->getActivecolor();	
		$purity = $this->$model->getActivePurity();	
		 $data = array(
    					'prod_mInfo'	 => $prod_mInfo,
    					'prod_dInfo'	 => $prod_dInfo,
    					'cut'			 => $cut,
    					'clarity'		 => $clarity,
//    					'carat'  		 => $carat,
    					'color'  		 => $color,
    					'purity'  		 => $purity
    	        		);
	echo json_encode($data);
	}
	
	
	public function sub_product($type="",$id=""){
		$model=self::CAT_MODEL;
		switch($type)
		{
			case 'active_subprodBySearch':
						$data = $this->$model->getActiveSearchSubProd($_POST['searchTxt'],$_POST['prodId']); 
						echo json_encode($data);
						break;
			case 'active_subprods':
						$data = $this->$model->getActiveSubProducts();
						echo json_encode($data);
						break;
		}					
	}	
					
	public function product($type="",$id=""){
		$model=self::CAT_MODEL;
		switch($type)
		{
			case 'active_prods':
						$data = $this->$model->getActiveProducts();
						echo json_encode($data);
						break;
			case 'active_prodBySearch':
						$data = $this->$model->getActiveSearchProd($_POST['searchTxt']);
						//echo $this->db->last_query();
						echo json_encode($data);
						break;
		}
	}
			
	public function purity($type="",$id=""){
		$model=self::CAT_MODEL;
		switch($type)
		{
			case "Add":
	 				$data=array
							('purity'=>$this->input->post("purity"), 
								'description'=>$this->input->post("description"), 
								'created_by'=>$this->session->userdata('uid'));
	 				$this->db->trans_begin();
	 				$this->$model->insertData($data,'ret_purity'); 
		 			if($this->db->trans_status()===TRUE)
		             {
					 	$this->db->trans_commit();
					 	$this->session->set_flashdata('chit_alert',array('message'=>'New Purity added successfully','class'=>'success','title'=>'Add Purity'));
					 	echo 1;
					 }
					 else
					 {
					 	$this->db->trans_rollback();						 	
					 	$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Add Purity'));
					 	echo 0;
					 }
					
	 		break;
	 	case "Edit":
	 			$data['Purity'] = $this->$model->get_purity($id);
	 			echo json_encode($data['Purity']);
	 		break; 
	 	
	 	case 'Delete':
						 $this->db->trans_begin();
						 $this->$model->deleteData('id_purity',$id,'ret_purity');
				           if( $this->db->trans_status()===TRUE)
						    {
						    	  $this->db->trans_commit();
								  $this->session->set_flashdata('chit_alert', array('message' => 'Purity deleted successfully','class' => 'success','title'=>'Delete Purity'));	  
							}			  
						   else
						   {
							 $this->db->trans_rollback();
							 $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete Purity'));
						   }
						 redirect('purity/list');	
				break;
					
	 	case "Update":
	 			$data['purity']=$this->$model->get_purity($id);
	 			$purity=$this->input->post('purity');
	 			$description=$this->input->post('description');
	 			$data=array("purity"=>$purity , "description"=>$description, 'updated_by'=>$this->session->userdata('uid'));
	 			
	 			 $this->db->trans_begin();
			            
			          $purity_id = $this->$model->update_purity($data,$id);
			          
			            if($this->db->trans_status()===TRUE)
			             {
						 	$this->db->trans_commit();
						 	
						 	$this->session->set_flashdata('chit_alert',array('message'=>'Purity record modified successfully','class'=>'success','title'=>'Edit Purity'));
						 	
						 }
						 else
						 {
						 	 $this->db->trans_rollback();						 	
						 	$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Edit Purity'));
						 	
						 }
	 		break;
	 		
			case 'List':
			
						$data['main_content'] = "master/purity/list" ;
						$this->load->view('layout/template', $data);
					break;
			case 'active_purities':
						$purity = $this->$model->getActivePurity();	
						echo json_encode($purity);
					break;				
			default:
						$SETT_MOD = self::SETT_MOD;
					  	$purity = $this->$model->ajax_getPurity();	 
					  	$access = $this->$SETT_MOD->get_access('purity/list');
				        $data = array(
				        					'purity' =>$purity,
											'access'=>$access
				        				);  
						echo json_encode($data);
		}
	}
	
	function ajax_getPurity()
	{
	    $model=self::CAT_MODEL;
	    $purity = $this->$model->ajax_getPurity();	 
	    echo json_encode($purity);
	}
	
	function purity_status($status,$id)
	{
		$data = array('status' => $status);
		$model=self::CAT_MODEL;
		$updstatus = $this->$model->update_purity($data,$id);
	if($updstatus)
					{
						$this->session->set_flashdata('chit_alert',array('message'=>'Purity status updated as '.($status==1 ? 'Active' : 'In Active').' successfully.','class'=>'success','title'=>'Purity Status'));
					}	
					else
					{
						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Purity  Status'));
						
					}	
		redirect('purity/list');
	}
	
	/*public function carat($type="",$id=""){
		$model=self::CAT_MODEL;
		switch($type)
		{
			case "Add":
	 				$carat= $this->input->post("carat");
	 				$description= $this->input->post("description");
	 				$data=array('carat'=>$carat , 'description'=>$description , 'id_employee'=>$this->session->userdata('uid'));
	 				$this->db->trans_begin();
	 				$this->$model->insert_carat($data);
	 				print_r($data);
	 			if($this->db->trans_status()===TRUE)
	             {
				 	$this->db->trans_commit();
				 	$this->session->set_flashdata('chit_alert',array('message'=>'New carat added successfully','class'=>'success','title'=>'Add carat'));
				 	
				 }
				 else
				 {
				 	 $this->db->trans_rollback();						 	
				 	$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Add carat'));
				 	
				 }
				redirect('carat/list');
	 				
	 		break;
	 	case "Edit":
	 			$data['carat'] = $this->$model->get_carat($id);
	 			echo json_encode($data['carat']);
	 		break; 
	 	
	 	case 'Delete':
						 $this->db->trans_begin();
						 $this->$model->delete_carat($id);
				           if( $this->db->trans_status()===TRUE)
						    {
						    	  $this->db->trans_commit();
								  $this->session->set_flashdata('chit_alert', array('message' => 'carat deleted successfully','class' => 'success','title'=>'Delete carat'));	  
							}			  
						   else
						   {
							 $this->db->trans_rollback();
							 $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete carat'));
						   }
						 redirect('carat/list');	
				break;
					
	 	case "Update":
	 			$data['carat']=$this->$model->get_carat($id);
	 			$carat=$this->input->post('carat');
	 			$description=$this->input->post('description');
	 			$data=array("carat"=>$carat , "description"=>$description, 'id_employee'=>$this->session->userdata('uid'));
	 			
	 			 $this->db->trans_begin();
			            
			          $carat_id = $this->$model->update_carat($data,$id);
			          
			            if($this->db->trans_status()===TRUE)
			             {
						 	$this->db->trans_commit();
						 	
						 	$this->session->set_flashdata('chit_alert',array('message'=>'carat record modified successfully','class'=>'success','title'=>'Edit carat'));
						 	redirect('carat/list');
						 }
						 else
						 {
						 	 $this->db->trans_rollback();						 	
						 	$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Edit carat'));
						 	redirect('carat/list');
						 }
	 		break;
	 		
			case 'List':
			
						$data['main_content'] = "master/carat/list" ;
						$this->load->view('layout/template', $data);
						break;
				
			default:
						$SETT_MOD = self::SETT_MOD;
					  	$carat = $this->$model->ajax_getcarat();	 
					  	$access = $this->$SETT_MOD->get_access('carat/list');
				        $data = array(
				        					'carat' =>$carat,
											'access'=>$access
				        				);  
						echo json_encode($data);
		}
	}
	
	function carat_status($status,$id)
	{
		$data = array('status' => $status);
		$model=self::CAT_MODEL;
		$status = $this->$model->update_carat($data,$id);
		if($status)
		{
			$this->session->set_flashdata('chit_alert',array('message'=>'carat status updated as '.($status ? 'active' : 'inactive').' successfully.','class'=>'success','title'=>'carat  Status'));			
		}	
		else
		{
			$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'carat  Status'));
		}	
		redirect('carat/list');
	}*/
	
	public function color($type="",$id=""){
		$model=self::CAT_MODEL;
		switch($type)
		{
			case "Add":
	 				$color= $this->input->post("color");
	 				$description= $this->input->post("description");
	 				$data=array('color'=>$color , 'description'=>$description , 'id_employee'=>$this->session->userdata('uid'));
	 				$this->db->trans_begin();
	 				$this->$model->insert_color($data);
	 			if($this->db->trans_status()===TRUE)
	             {
				 	$this->db->trans_commit();
				 	$this->session->set_flashdata('chit_alert',array('message'=>'New color added successfully','class'=>'success','title'=>'Add color'));
				 }
				 else
				 {
				 	 $this->db->trans_rollback();						 	
				 	$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Add color'));
				 	
				 }
	 		break;
	 	case "Edit":
	 			$data['color'] = $this->$model->get_color($id);
	 			echo json_encode($data['color']);
	 		break; 
	 	
	 	case 'Delete':
						 $this->db->trans_begin();
						 $this->$model->delete_color($id);
				           if( $this->db->trans_status()===TRUE)
						    {
						    	  $this->db->trans_commit();
								  $this->session->set_flashdata('chit_alert', array('message' => 'color deleted successfully','class' => 'success','title'=>'Delete color'));	  
							}			  
						   else
						   {
							 $this->db->trans_rollback();
							 $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete color'));
						   }
						 redirect('color/list');	
				break;
					
	 	case "Update":
	 			$data['color']=$this->$model->get_color($id);
	 			$color=$this->input->post('color');
	 			$description=$this->input->post('description');
	 			$data=array("color"=>$color , "description"=>$description, 'id_employee'=>$this->session->userdata('uid'));
	 			
	 			 $this->db->trans_begin();
			            
			          $color_id = $this->$model->update_color($data,$id);
			          
			            if($this->db->trans_status()===TRUE)
			             {
						 	$this->db->trans_commit();
						 	
						 	$this->session->set_flashdata('chit_alert',array('message'=>'color record modified successfully','class'=>'success','title'=>'Edit color'));
						 	
						 }
						 else
						 {
						 	 $this->db->trans_rollback();						 	
						 	$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Edit color'));
						 
						 }
	 		break;
	 		
			case 'List':
			
						$data['main_content'] = "master/color/list" ;
						$this->load->view('layout/template', $data);
						break;
				
			default:
						$SETT_MOD = self::SETT_MOD;
					  	$color = $this->$model->ajax_getcolor();	 
					  	$access = $this->$SETT_MOD->get_access('color/list');
				        $data = array(
				        					'color' =>$color,
											'access'=>$access
				        				);  
						echo json_encode($data);
		}
	}
	
	function color_status($status,$id)
	{
		$data = array('status' => $status);
		$model=self::CAT_MODEL;
		$updstatus = $this->$model->update_color($data,$id);
		if($updstatus)
		{
			$this->session->set_flashdata('chit_alert',array('message'=>'color status updated as '.($status ==1 ? 'Active' : 'Inactive').' successfully.','class'=>'success','title'=>'color  Status'));			
		}	
		else
		{
			$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'color  Status'));
		}	
		redirect('color/list');
	}
	
	public function cut($type="",$id=""){
		$model=self::CAT_MODEL;
		switch($type)
		{
			case "Add":
	 				$cut= $this->input->post("cut");
	 				$description= $this->input->post("description");
	 				$data=array('cut'=>$cut , 'description'=>$description , 'id_employee'=>$this->session->userdata('uid'));
	 				$this->db->trans_begin();
	 				$this->$model->insert_cut($data);
	 				print_r($data);
	 			if($this->db->trans_status()===TRUE)
	             {
				 	$this->db->trans_commit();
				 	$this->session->set_flashdata('chit_alert',array('message'=>'New cut added successfully','class'=>'success','title'=>'Add cut'));
				 	
				 }
				 else
				 {
				 	 $this->db->trans_rollback();						 	
				 	$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Add cut'));
				 	
				 }
	 		break;
	 	case "Edit":
	 			$data['cut'] = $this->$model->get_cut($id);
	 			echo json_encode($data['cut']);
	 		break; 
	 	
	 	case 'Delete':
						 $this->db->trans_begin();
						 $this->$model->delete_cut($id);
				           if( $this->db->trans_status()===TRUE)
						    {
						    	  $this->db->trans_commit();
								  $this->session->set_flashdata('chit_alert', array('message' => 'cut deleted successfully','class' => 'success','title'=>'Delete cut'));	  
							}			  
						   else
						   {
							 $this->db->trans_rollback();
							 $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete cut'));
						   }
						 redirect('cut/list');	
				break;
					
	 	case "Update":
	 			$data['cut']=$this->$model->get_cut($id);
	 			$cut=$this->input->post('cut');
	 			$description=$this->input->post('description');
	 			$data=array("cut"=>$cut , "description"=>$description, 'id_employee'=>$this->session->userdata('uid'));
	 			
	 			 $this->db->trans_begin();
			            
			          $cut_id = $this->$model->update_cut($data,$id);
			          
			            if($this->db->trans_status()===TRUE)
			             {
						 	$this->db->trans_commit();
						 	
						 	$this->session->set_flashdata('chit_alert',array('message'=>'Cut record modified successfully','class'=>'success','title'=>'Edit cut'));
						 }
						 else
						 {
						 	 $this->db->trans_rollback();						 	
						 	 $this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Edit cut'));
				
						 }
	 		break;
	 		
			case 'List':
			
						$data['main_content'] = "master/cut/list" ;
						$this->load->view('layout/template', $data);
						break;
				
			default:
						$SETT_MOD = self::SETT_MOD;
					  	$cut = $this->$model->ajax_getcut();	 
					  	$access = $this->$SETT_MOD->get_access('cut/list');
				        $data = array(
				        					'cut' =>$cut,
											'access'=>$access
				        				);  
						echo json_encode($data);
		}
	}
	
	function cut_status($status,$id)
	{
		$data = array('status' => $status);
		$model=self::CAT_MODEL;
		$updstatus = $this->$model->update_cut($data,$id);
		if($updstatus)
		{
			$this->session->set_flashdata('chit_alert',array('message'=>'cut status updated as '.($status ==1 ? 'Active' : 'Inactive').' successfully.','class'=>'success','title'=>'cut  Status'));			
		}	
		else
		{
			$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'cut  Status'));
		}	
		redirect('cut/list');
	}
	
	public function clarity($type="",$id=""){
		$model=self::CAT_MODEL;
		switch($type)
		{
			case "Add":
	 				$clarity= $this->input->post("clarity");
	 				$description= $this->input->post("description");
	 				$data=array('clarity'=>$clarity , 'description'=>$description , 'id_employee'=>$this->session->userdata('uid'));
	 				$this->db->trans_begin();
	 				$this->$model->insert_clarity($data); 
	 			if($this->db->trans_status()===TRUE)
	             {
				 	$this->db->trans_commit();
				 	$this->session->set_flashdata('chit_alert',array('message'=>'New clarity added successfully','class'=>'success','title'=>'Add clarity'));
				 	
				 }
				 else
				 {
				 	 $this->db->trans_rollback();						 	
				 	$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Add clarity'));
				 	
				 }
				redirect('clarity/list');
	 				
	 		break;
	 	case "Edit":
	 			$data['clarity'] = $this->$model->get_clarity($id);
	 			echo json_encode($data['clarity']);
	 		break; 
	 	
	 	case 'Delete':
						 $this->db->trans_begin();
						 $this->$model->delete_clarity($id);
				           if( $this->db->trans_status()===TRUE)
						    {
						    	  $this->db->trans_commit();
								  $this->session->set_flashdata('chit_alert', array('message' => 'clarity deleted successfully','class' => 'success','title'=>'Delete clarity'));	  
							}			  
						   else
						   {
							 $this->db->trans_rollback();
							 $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete clarity'));
						   }
						 redirect('clarity/list');	
				break;
					
	 	case "Update":
	 			$data['clarity']=$this->$model->get_clarity($id);
	 			$clarity=$this->input->post('clarity');
	 			$description=$this->input->post('description');
	 			$data=array("clarity"=>$clarity , "description"=>$description, 'id_employee'=>$this->session->userdata('uid'));
	 			
	 			 $this->db->trans_begin();
			            
			          $clarity_id = $this->$model->update_clarity($data,$id);
			          
			            if($this->db->trans_status()===TRUE)
			             {
						 	$this->db->trans_commit();
						 	
						 	$this->session->set_flashdata('chit_alert',array('message'=>'clarity record modified successfully','class'=>'success','title'=>'Edit clarity'));
						 	redirect('clarity/list');
						 }
						 else
						 {
						 	 $this->db->trans_rollback();						 	
						 	$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Edit clarity'));
						 	redirect('clarity/list');
						 }
	 		break;
	 		
			case 'List':
			
						$data['main_content'] = "master/clarity/list" ;
						$this->load->view('layout/template', $data);
						break;
				
			default:
						$SETT_MOD = self::SETT_MOD;
					  	$clarity = $this->$model->ajax_getclarity();	 
					  	$access = $this->$SETT_MOD->get_access('clarity/list');
				        $data = array(
				        					'clarity' =>$clarity,
											'access'=>$access
				        				);  
						echo json_encode($data);
		}
	}
	
	function clarity_status($status,$id)
	{
		$data = array('status' => $status);
		$model=self::CAT_MODEL;
		$updstatus = $this->$model->update_clarity($data,$id);
		if($updstatus)
		{
			$this->session->set_flashdata('chit_alert',array('message'=>'clarity status updated as '.($status ==1 ? 'Active' : 'Inactive').' successfully.','class'=>'success','title'=>'clarity  Status'));			
		}	
		else
		{
			$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'clarity  Status'));
		}	
		redirect('clarity/list');
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
	 
	 	function set_image_ret($id)
    {
     //$data=array();
     //print_r($data);exit;
     $model = self::CAT_MODEL;
   	 if($_FILES['product']['name']['ret_product_img'])
     { 
   	 	$path='assets/img/ret_product/';
	
         if (!is_dir($path)) {

            mkdir($path, 0777, TRUE);
		}
		else{
			$file = $path.$id['ID'].".jpg" ;
            chmod($path,0777);
            unlink($file);
		}

   	 	$img=$_FILES['product']['tmp_name']['ret_product_img'];

		$filename = mt_rand(100001,999999).".jpg";	



   	 	$imgpath='assets/img/ret_product/'.$filename;



	 	$upload=$this->upload_img('image',$imgpath,$img);	



	 	$data['image']= base_url().$imgpath;
	 	//print_r($data['image']);exit;
	 	//$this->$model->updateData("update",$id['ID'],$data);
       $status=$this->$model->updateData($data,'pro_id',$id,'ret_product_master');


	 } 
 }
	 	
   function upload_img($outputImage,$dst, $img)
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
	
	public function uom($type="",$id="",$status=""){
		$model=self::CAT_MODEL;
		switch($type)
		{
			case "add":
	 				$data=array(
						'uom_name'	      => $this->input->post("uom_name"),
						'uom_short_code'  => $this->input->post("uom_short_code"),
						'uom_status'	  => $this->input->post("uom_status"),
						'created_on'	  => date("Y-m-d H:i:s"),
						'created_by'      => $this->session->userdata('uid')
					);
	 				$this->db->trans_begin();
	 				$this->$model->insertData($data,'ret_uom');
					if($this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
						$result = array('message'=>'New UOM added successfully','class'=>'success','title'=>'Add UOM : ');
					
					}
					else
					{
						$this->db->trans_rollback();						 	
						$result = array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Add UOM : ');
						
					}	
					echo json_encode($result);
					break;
			case "edit":
		 			$data['uom'] = $this->$model->get_uom($id);
		 			echo json_encode($data['uom']);
				break; 
	 	
			case 'delete':
					$this->db->trans_begin();
					$this->$model->deleteData('uom_id',$id,'ret_uom');
					if( $this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
						$this->session->set_flashdata('chit_alert', array('message' => 'UOM deleted successfully','class' => 'success','title'=>'Delete UOM'));	
						echo 1;
					}			  
					else
					{
						$this->db->trans_rollback();
						$this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete UOM'));
						echo 0;
					}
					redirect('admin_ret_catalog/uom/list');
				break;
					
			case "update":
					$data=array(
						'uom_name'	      => $this->input->post("uom_name"),
						'uom_short_code'  => $this->input->post("uom_short_code"),
						'uom_status'	  => $this->input->post("uom_status"),
						'updated_on'	  => date("Y-m-d H:i:s"),
						'updated_by'      => $this->session->userdata('uid')
					);
					$this->db->trans_begin();

					$this->$model->updateData($data,'uom_id',$id,'ret_uom');
					if($this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();

						$this->session->set_flashdata('chit_alert',array('message'=>'UOM record modified successfully','class'=>'success','title'=>'Edit UOM'));
						echo 1;
					}
					else
					{
						$this->db->trans_rollback();						 	
						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Edit UOM'));
						echo 0;
					}
						 
				break;
			case 'list':
					$data['main_content'] = "master/uom/list" ;
					$this->load->view('layout/template', $data);
				break;
			case 'update_status':
					$data = array('uom_status' => $status,'updated_on' => date("Y-m-d H:i:s"),'updated_by' => $this->session->userdata('uid'));
					$updstatus = $this->$model->updateData($data,'uom_id',$id,'ret_uom');
					if($updstatus)
					{
						$this->session->set_flashdata('chit_alert',array('message'=>'UOM status updated as '.($status == 1 ? 'Active' : 'Inactive').' successfully.','class'=>'success','title'=>'UOM  Status'));
						echo 1;
					}	
					else
					{
						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'UOM  Status'));
						echo 0;
					}	
				   redirect('admin_ret_catalog/uom/list');
				break;
			case 'active_uom': 
				  	$data = $this->$model->getActiveUOM();	  
					echo json_encode($data);
				break;
			default:
					$SETT_MOD 	= self::SETT_MOD;
			     	$range['from_date']	= $this->input->post('from_date');
			        $range['to_date']	= $this->input->post('to_date');
				  	$uom 		= $this->$model->ajax_getUOM($range['from_date'],$range['to_date']);	 
				  	$access 	= $this->$SETT_MOD->get_access('admin_ret_catalog/uom/list');
			        $data 		= array(
			        					'uom' =>$uom,
										'access'=>$access
			        				);  
					echo json_encode($data);
		}
	}
	
	public function branch_floor($type="",$id="",$status=""){
		$model=self::CAT_MODEL;
		switch($type)
		{
			case "add":
	 				$data=array(
						'branch_id'		  => $this->input->post("branch_id"),
						'floor_name'	  => $this->input->post("floor_name"),
						'floor_short_code'=> $this->input->post("floor_short_code"),
						'floor_status'	  => $this->input->post("floor_status"),
						'created_on'	  => date("Y-m-d H:i:s"),
						'created_by'      => $this->session->userdata('uid')
					);
	 				$this->db->trans_begin();
	 				$this->$model->insertData($data,'ret_branch_floor');
					if($this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
					    $result = array('message'=>'New branch floor added successfully!..','class'=>'success','title'=>'Add Branch floor : ');
					}
					else
					{
						$this->db->trans_rollback();						 	
						$result = array('message'=>'Unable to proceed the requested process..','class'=>'danger','title'=>'Add floor : ');
					}	
					echo json_encode($result);
					break;
			case "edit":
		 			$data['floor'] = $this->$model->get_floor($id); 
		 			echo json_encode($data['floor']);
				break; 
	 	
			case 'delete':
					$this->db->trans_begin();
					$this->$model->deleteData('floor_id',$id,'ret_branch_floor');
					if( $this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
						$this->session->set_flashdata('chit_alert', array('message' => 'Floor deleted successfully','class' => 'success','title'=>'Delete floor'));	
						echo 1;
					}			  
					else
					{
						$this->db->trans_rollback();
						$this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete floor'));
						echo 0;
					}
					redirect('admin_ret_catalog/branch_floor/list');
				break;
					
			case "update":
					$data=array(
					'branch_id'		  => $this->input->post("branch_id"),
					'floor_name'	  => $this->input->post("floor_name") ,
					'floor_short_code'=> $this->input->post("floor_short_code"),
					'floor_status'	  => $this->input->post("floor_status"),
					'updated_on'	  => date("Y-m-d H:i:s"),
					'updated_by'      => $this->session->userdata('uid')
					);
					$this->db->trans_begin();

					$this->$model->updateData($data,'floor_id',$id,'ret_branch_floor');
					if($this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
						$this->session->set_flashdata('chit_alert',array('message'=>'Floor record modified successfully','class'=>'success','title'=>'Edit floor'));
						echo 1;
					}
					else
					{
						$this->db->trans_rollback();						 	
						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Edit floor'));
						echo 0;
					}
						 
				break;
			case 'list':
					$data['main_content'] = "master/floor/list" ;
					$this->load->view('layout/template', $data);
				break;
			case 'update_status':
			
					$data = array('floor_status' => $status,'updated_on' => date("Y-m-d H:i:s"),'updated_by' => $this->session->userdata('uid'));
					$updstatus = $this->$model->updateData($data,'floor_id',$id,'ret_branch_floor');
					if($updstatus)
					{
						$this->session->set_flashdata('chit_alert',array('message'=>'Floor status updated as '.($status == 1 ? 'Active' : 'Inactive').' successfully.','class'=>'success','title'=>'Floor  Status'));		 
					}	
					else
					{
						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Floor  Status')); 
					}	
				   redirect('admin_ret_catalog/branch_floor/list');
				break;
			case 'active_floors':								
					$data = $this->$model->getActiveFloors();
					echo json_encode($data);
				break;
			
			default:
					$SETT_MOD = self::SETT_MOD;
				  	$range['from_date'] = $this->input->post('from_date');
			       	$range['to_date'] = $this->input->post('to_date');
					$id_branch = $this->input->post('branch_id');
			  		$floor = $this->$model->ajax_getfloor($range['from_date'],$range['to_date'],$id_branch); 
				  	$access = $this->$SETT_MOD->get_access('admin_ret_catalog/branch_floor/list');
			        $data = array(
			        					'floor' =>$floor,
										'access'=>$access
			        				);  
					echo json_encode($data);
		}
	}
	
	public function floor_counter($type="",$id="",$status=""){
		$model=self::CAT_MODEL;
		switch($type)
		{
			case "add":
	 				$data=array(
						'floor_id'		  => $this->input->post("floor_id"),
						'counter_name'	  => $this->input->post("counter_name"),
						'counter_short_code'=> $this->input->post("counter_short_code"),
						'counter_status'	  => $this->input->post("counter_status"),
						'created_on'	  => date("Y-m-d H:i:s"),
						'created_by'      => $this->session->userdata('uid')
					);
	 				$this->db->trans_begin();
	 				$this->$model->insertData($data,'ret_branch_floor_counter');
					if($this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
						$result = array('message'=>'New branch floor counter added successfully!..','class'=>'success','title'=>'Add Branch floor : ');
					
					}
					else
					{
						$this->db->trans_rollback();						 	
						$result = array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Add floor');
					
					}
					echo json_encode($result);
					break;
			case "edit":
		 			$data['counter'] = $this->$model->get_counter($id);
		 			echo json_encode($data['counter']);
				break; 
	 	
			case 'delete':
					$this->db->trans_begin();
					$this->$model->deleteData('counter_id',$id,'ret_branch_floor_counter');
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
					redirect('admin_ret_catalog/floor_counter/list');	
				break;
					
			case "update":
					$data['counter']        = $this->$model->get_counter($id);
					$data = array(
						'floor_id'		  => $this->input->post("floor_id"),
						'counter_name'	  => $this->input->post("counter_name") ,
						'counter_short_code'=> $this->input->post("counter_short_code"),
						'counter_status'  => $this->input->post("counter_status"),
						'updated_on'	  => date("Y-m-d H:i:s"),
						'updated_by'      => $this->session->userdata('uid')
					);

					$this->db->trans_begin();
					$id_floor = $this->$model->updateData($data,'counter_id',$id,'ret_branch_floor_counter'); 
					if($this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
						$this->session->set_flashdata('chit_alert',array('message'=>'floor record modified successfully','class'=>'success','title'=>'Edit floor'));
						echo 1;
					}
					else
					{
						$this->db->trans_rollback();						 	
						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Edit floor'));
						echo 0;
					}
				break;
			case 'list':
					$data['main_content'] = "master/floor_counter/list" ;
					$this->load->view('layout/template', $data);
				break;
			case 'update_status':
					$data = array('counter_status' => $status,
					'updated_on'	  => date("Y-m-d H:i:s"),
				    'updated_by'      => $this->session->userdata('uid'));
					$model=self::CAT_MODEL;
					$updstatus = $this->$model->updateData($data,'counter_id',$id,'ret_branch_floor_counter');
					//echo $this->db->_error_message();
					//exit;
					if($updstatus)
					{
						$this->session->set_flashdata('chit_alert',array('message'=>'counter status updated as '.($status==1 ? 'Active' : 'Inactive').' successfully.','class'=>'success','title'=>'Counter  Status')); 
					}	
					else
					{
						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'floor  Status')); 
					}							
					redirect('admin_ret_catalog/floor_counter/list');	
				break;
			case 'active_counters':
						$data = $this->$model->getActiveCounters();
						echo json_encode($data);
					break;
			default:
						$SETT_MOD = self::SETT_MOD;
					  	$branch   = $this->input->post('id_branch');
						$floor   = $this->input->post('floor_id');
						$range['from_date']  = $this->input->post('from_date');
						$range['to_date']  = $this->input->post('to_date');
						
						$counters = $this->$model->ajax_getcounter($branch,$floor,$range['from_date'],$range['to_date']);
						$access = $this->$SETT_MOD->get_access('admin_ret_catalog/floor_counter/list');
						       $data = array(
							        	'counter' 	=> $counters,
										'access'	=> $access
						       		   );  
						echo json_encode($data);
		}
	}
	
	public function making_type($type="",$id="",$status=""){
		$model=self::CAT_MODEL;
		switch($type)
		{
			case "add":
	 				$data=array(
						'mak_name'	  	  => $this->input->post("mak_name"),
						'mak_short_code'  => $this->input->post("mak_short_code"),
						'mak_status'	  => $this->input->post("mak_status"),
						'created_on'	  => date("Y-m-d H:i:s"),
						'created_by'      => $this->session->userdata('uid')
					);
	 				$this->db->trans_begin();
	 				$this->$model->insertData($data,'ret_making_type');
					if($this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
						$result = array('message'=>'New making type added successfully!..','class'=>'success','title'=>'Add Making Type : ');
						
					}
					else
					{
						$this->db->trans_rollback();						 	
						$result = array('message'=>'Unable to proceed the requested process..','class'=>'danger','title'=>'Add Making type : ');
						
					}
					echo json_encode($result);
					break;
			case "edit":
		 			$data['make'] = $this->$model->get_make_type($id);
		 			echo json_encode($data['make']);
				break; 
	 	
			case 'delete':
					$this->db->trans_begin();
					$this->$model->deleteData('mak_id',$id,'ret_making_type');
					if( $this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
						$this->session->set_flashdata('chit_alert', array('message' => 'type deleted successfully','class' => 'success','title'=>'Delete Making type'));	  
					}			  
					else
					{
						$this->db->trans_rollback();
						$this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete Making type'));
					}
					redirect('admin_ret_catalog/making_type/list');	
				break;
					
			case "update": 
						$data=array(
						'mak_name'	  	 => $this->input->post("mak_name") ,
						'mak_short_code' => $this->input->post("mak_short_code"),
						'mak_status'	 => $this->input->post("mak_status"),
						'updated_on'	 => date("Y-m-d H:i:s"),
						'updated_by'     => $this->session->userdata('uid')
						);

						$this->db->trans_begin();

						$id_floor = $this->$model->updateData($data,'mak_id',$id,'ret_making_type'); 

						if($this->db->trans_status()===TRUE)
						{
						$this->db->trans_commit();

						$this->session->set_flashdata('chit_alert',array('message'=>'Making type record modified successfully','class'=>'success','title'=>'Edit Making Type'));
						echo 1;
						}
						else
						{
							$this->db->trans_rollback();						 	
							$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Edit Making Type'));
							echo 0;
						}
				break;
			case 'list':
						$data['main_content'] = "master/making_type/list" ;
						$this->load->view('layout/template', $data);
					break;
			case 'update_status':
						$data = array('mak_status' => $status,
						'updated_on'	  => date("Y-m-d H:i:s"),
					    'updated_by'      => $this->session->userdata('uid'));
						$model=self::CAT_MODEL;
						$updstatus = $this->$model->updateData($data,'mak_id',$id,'ret_making_type');
						if($updstatus)
						{
							$this->session->set_flashdata('chit_alert',array('message'=>'Making status updated as '.($status==1 ? 'Active' : 'Inactive').' successfully.','class'=>'success','title'=>'Making Type Status'));
							echo 1;
						}	
						else
						{
							$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Making Type Status'));
							echo 0;
						}	
						redirect('admin_ret_catalog/making_type/list');
					break;
			case 'active_making_type':
								
						$data = $this->$model->getActiveMaking();
						echo json_encode($data);
						break;
			default:
						$SETT_MOD = self::SETT_MOD;
						$range['from_date']  = $this->input->post('from_date');
						$range['to_date']  = $this->input->post('to_date');
					  	$make = $this->$model->ajax_get_makingtype($range['from_date'],$range['to_date']);	 
					  	$access = $this->$SETT_MOD->get_access('admin_ret_catalog/making_type/list');
				        $data = array(
				        					'make' =>$make,
											'access'=>$access
				        			);  
						echo json_encode($data);
		}
	}
	
	public function theme($type="",$id="",$status=""){
		$model=self::CAT_MODEL;
		switch($type)
		{
			case "add":
	 				$data=array(
						'theme_code'	  => $this->input->post("theme_code"),
						'theme_name'	  => $this->input->post("theme_name"),
						'theme_desc'      => $this->input->post("theme_desc"),
						'theme_status'	  => $this->input->post("theme_status"),
						'created_on'	  => date("Y-m-d H:i:s"),
						'created_by'      => $this->session->userdata('uid')
					);
	 				$this->db->trans_begin();
	 				$this->$model->insertData($data,'ret_theme');
					if($this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
						$result = array('message'=>'New Theme added successfully!..','class'=>'success','title'=>'Add Theme : ');
					}
					else
					{
						$this->db->trans_rollback();						 	
						$result = array('message'=>'Unable to proceed the requested process..','class'=>'danger','title'=>'Add Theme : ');
					}	
					echo json_encode($result);
					break;
			case "edit":
		 			$data['theme'] = $this->$model->get_theme($id);
		 			echo json_encode($data['theme']);
				break; 
	 	
			case 'delete':
					$this->db->trans_begin();
					$this->$model->deleteData('id_theme',$id,'ret_theme');
					if( $this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
						$this->session->set_flashdata('chit_alert', array('message' => 'Theme deleted successfully','class' => 'success','title'=>'Delete Theme'));	
						echo 1;
					}			  
					else
					{
						$this->db->trans_rollback();
						$this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete Theme'));
						echo 0;
					}
					redirect('admin_ret_catalog/theme/list');
				break;
			case 'active_theme':
						$data = $this->$model->getActiveTheme();  
						echo json_encode($data);
				break;
			case "update":
		 			$data=array(
						'theme_code'	  => $this->input->post("theme_code"),
						'theme_name'	  => $this->input->post("theme_name"),
						'theme_desc'      => $this->input->post("theme_desc"),
						'theme_status'	  => $this->input->post("theme_status"),
						'updated_on'	  => date("Y-m-d H:i:s"),
						'updated_by'      => $this->session->userdata('uid')
					);
		 			 $this->db->trans_begin();
			            
			          $this->$model->updateData($data,'id_theme',$id,'ret_theme');
			            if($this->db->trans_status()===TRUE)
			             {
						 	$this->db->trans_commit();
						 	
						 	$this->session->set_flashdata('chit_alert',array('message'=>'Theme record modified successfully','class'=>'success','title'=>'Edit Theme'));
						 	echo 1;
						 }
						 else
						 {
						 	$this->db->trans_rollback();						 	
						 	$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Edit Theme'));
						 	echo 0;
						 }
						 
				break;
			case 'list':
						$data['main_content'] = "master/theme/list" ;
						$this->load->view('layout/template', $data);
						break;
			case 'update_status':
			
						$data = array('theme_status' => $status,'updated_on' => date("Y-m-d H:i:s"),'updated_by' => $this->session->userdata('uid'));
						$updstatus = $this->$model->updateData($data,'id_theme',$id,'ret_theme');
						if($updstatus)
						{
							$this->session->set_flashdata('chit_alert',array('message'=>'Theme status updated as '.($status == 1 ? 'Active' : 'Inactive').' successfully.','class'=>'success','title'=>'Theme  Status'));		
							echo 1;
						}	
						else
						{
							$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Theme  Status'));
							echo 0;
						}	
					   redirect('admin_ret_catalog/theme/list');
			default:
						$SETT_MOD 	= self::SETT_MOD;
				     	$range['from_date']	= $this->input->post('from_date');
				        $range['to_date']	= $this->input->post('to_date');
						//$id_theme	= $this->input->post('id_theme');
					  	$theme 		= $this->$model->ajax_gettheme($range['from_date'],$range['to_date']);	 
					  	$access 	= $this->$SETT_MOD->get_access('admin_ret_catalog/theme/list');
				        $data 		= array(
				        					'theme' =>$theme,
											'access'=>$access
				        				);  
						echo json_encode($data);
		}
	}
	
	public function material($type="",$id="",$status=""){
		$model=self::CAT_MODEL;
		switch($type)
		{
			case "add":
	 				$data=array(
						'material_name'	  => $this->input->post("material_name"),
						'material_code'	  => $this->input->post("material_code"),
						'material_status'	  => $this->input->post("material_status"),
						'created_on'	  => date("Y-m-d H:i:s"),
						'created_by'      => $this->session->userdata('uid')
					);
	 				$this->db->trans_begin();
	 				$this->$model->insertData($data,'ret_material');
					if($this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
						$result = array('message'=>'New Material added successfully!..','class'=>'success','title'=>'Add Material : ');
						
					}
					else
					{
						$this->db->trans_rollback();						 	
						$result = array('message'=>'Unable to proceed the requested process..','class'=>'danger','title'=>'Add Material : ');
						
					}	
					echo json_encode($result);
				break;
			case "edit":
		 			$data['material'] = $this->$model->get_material($id);
		 			echo json_encode($data['material']);
				break; 
	 	
			case 'delete':
					$this->db->trans_begin();
					$this->$model->deleteData('material_id',$id,'ret_material');
			        if( $this->db->trans_status()===TRUE)
					{
					      $this->db->trans_commit();
						  $this->session->set_flashdata('chit_alert', array('message' => 'Material deleted successfully','class' => 'success','title'=>'Delete Material'));	
						  echo 1;
					}			  
				    else
				    {
						 $this->db->trans_rollback();
						 $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete Material'));
						 echo 0;
				    }
					redirect('admin_ret_catalog/material/list');
				break;
					
			case "update":
					$data=array(
					'material_name'	  => $this->input->post("material_name"),
					'material_code'	  => $this->input->post("material_code"),
					'material_status' => $this->input->post("material_status"),
					'updated_on'	  => date("Y-m-d H:i:s"),
					'updated_by'      => $this->session->userdata('uid')
					);
					$this->db->trans_begin();
					$this->$model->updateData($data,'material_id',$id,'ret_material');
					if($this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
						$this->session->set_flashdata('chit_alert',array('message'=>'Material record modified successfully','class'=>'success','title'=>'Edit Material'));
						echo 1;
					}
					else
					{
						$this->db->trans_rollback();						 	
						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Edit Material'));
						echo 0;
					}

				break;
			case 'active_material':
					$data = $this->$model->getActiveMaterial();  
					echo json_encode($data);
					break;
			case 'list':
					$data['main_content'] = "master/material/list" ;
					$this->load->view('layout/template', $data);
					break;
			case 'update_status':
					$data = array('material_status' => $status,'updated_on' => date("Y-m-d H:i:s"),'updated_by' => $this->session->userdata('uid'));
					$updstatus = $this->$model->updateData($data,'material_id',$id,'ret_material');
					if($updstatus)
					{
					$this->session->set_flashdata('chit_alert',array('message'=>'Material status updated as '.($status == 1 ? 'Active' : 'Inactive').' successfully.','class'=>'success','title'=>'Material  Status'));		
					echo 1;
					}	
					else
					{
					$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Material  Status'));
					echo 0;
					}	
					redirect('admin_ret_catalog/material/list');
				break;
			default:
					$SETT_MOD 	= self::SETT_MOD;
					$range['from_date']	= $this->input->post('from_date');
					$range['to_date']	= $this->input->post('to_date');
					$material 		= $this->$model->ajax_getmaterial($range['from_date'],$range['to_date']);	 
					$access 	= $this->$SETT_MOD->get_access('admin_ret_catalog/material/list');
					$data 		= array(
										'material' =>$material,
										'access'=>$access
									   );  
					echo json_encode($data);
		}
	}
	
	public function stone($type="",$id="",$status=""){
		$model=self::CAT_MODEL;
		switch($type)
		{
			case "add":
	 				$data=array(
						'uom_id'	           => $this->input->post("uom_id"),
						'stone_name'           => $this->input->post("stone_name"),
						'stone_code'           => $this->input->post("stone_code"),
						'stone_type'   		   => $this->input->post("stone_type"),
						'is_certificate_req'   => $this->input->post("is_certificate_req"),
						'is_4c_req'            => $this->input->post("is_4c_req"),
						'stone_status'		   => $this->input->post("stone_status"),
						'created_on'           => date("Y-m-d H:i:s"),
						'created_by'           => $this->session->userdata('uid')
					);
	 				$this->db->trans_begin();
	 				$this->$model->insertData($data,'ret_stone');
					if($this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
						$result = array('message'=>'New stone added successfully','class'=>'success','title'=>'Add Stone : ');
						
					}
					else
					{
						$this->db->trans_rollback();						 	
						$result = array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Add Stone : ');
					}
					echo json_encode($result);
					break;
			case "edit":
						$data['stone'] = $this->$model->get_stone($id);
						echo json_encode($data['stone']);
						break; 
	 	
			case 'delete':
						 $this->db->trans_begin();
						 $this->$model->deleteData('stone_id',$id,'ret_stone');
				           if( $this->db->trans_status()===TRUE)
						    {
						    	  $this->db->trans_commit();
								  $this->session->set_flashdata('chit_alert', array('message' => 'Stone deleted successfully','class' => 'success','title'=>'Delete Stone'));	  
							}			  
						   else
						    {
							 $this->db->trans_rollback();
							 $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete Stone'));
						    }
						 redirect('admin_ret_catalog/stone/list');	
						 break;
					
			case "update":
	 			$data['stone']        = $this->$model->get_stone($id);
	 			$data=array(
					    'uom_id'	           => $this->input->post("uom_id"),
						'stone_name'           => $this->input->post("stone_name"),
						'stone_code'           => $this->input->post("stone_code"),
						'stone_type'   		   => $this->input->post("stone_type"),
						'is_certificate_req'   => $this->input->post("is_certificate_req"),
						'is_4c_req'            => $this->input->post("is_4c_req"),
						'stone_status'		   => $this->input->post("stone_status"),
						'updated_on'	       => date("Y-m-d H:i:s"),
						'updated_by'           => $this->session->userdata('uid')
				);
	 			
	 			        $this->db->trans_begin();
			            $id_floor = $this->$model->updateData($data,'stone_id',$id,'ret_stone'); 
			          
			            if($this->db->trans_status()===TRUE)
			             {
						 	$this->db->trans_commit();
						 	$this->session->set_flashdata('chit_alert',array('message'=>'Stone record modified successfully','class'=>'success','title'=>'Edit Stone'));
						 	echo 1;
						 }
						 else
						 {
						 	$this->db->trans_rollback();						 	
						 	$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Edit Stone'));
						 	echo 0;
						 }
				          break;
			case 'list':
						$data['main_content'] = "master/stone/list" ;
						$this->load->view('layout/template', $data);
						  break;
			case 'update_status':
			
						$data = array('stone_status' => $status,
						'updated_on'	  => date("Y-m-d H:i:s"),
					    'updated_by'      => $this->session->userdata('uid'));
						$model=self::CAT_MODEL;
						$updstatus = $this->$model->updateData($data,'stone_id',$id,'ret_stone');
						if($updstatus)
						{
							$this->session->set_flashdata('chit_alert',array('message'=>'Stone status updated as '.($status==1 ? 'Active' : 'Inactive').' successfully.','class'=>'success','title'=>'Stone Status'));
							echo 1;
						}	
						else
						{
							$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Stone Status'));
							echo 0;
						}	
						redirect('admin_ret_catalog/stone/list');
			case 'active_stones':
						$data = $this->$model->getActiveStone();
						echo json_encode($data);
						break;
			case 'ActiveStones':
				$data=$this->$model->get_ActiveStones($_POST);
				echo json_encode($data);
			break;	
			default:
						$SETT_MOD = self::SETT_MOD;
						$range['from_date']  = $this->input->post('from_date');
						$range['to_date']  = $this->input->post('to_date');
					  	$stone = $this->$model->ajax_get_stone($range['from_date'],$range['to_date']);	 
					  	$access = $this->$SETT_MOD->get_access('admin_ret_catalog/stone/list');
				        $data = array(
				        					'stone' =>$stone,
											'access'=>$access
				        			);  
						echo json_encode($data);
		}
	}
	
	function get_catByMetal($id_cat)
	{
		 $model=self::CAT_MODEL;
		 $data = $this->$model->get_catByMetal($id_cat);  
		 echo json_encode($data);
	}
	public function category($type="",$id="",$status=""){
		$model=self::CAT_MODEL;
		switch($type)
		{	
			case "add":
					$purity1      = explode(',',$this->input->post('id_purity')); 	
	 				$data=array(
						'name'	        => strtoupper($this->input->post("name")),
						'cat_code'      => $this->input->post("cat_code"),
						'hsn_code'      => $this->input->post("hsn_code"),
						'description'   => ($this->input->post("description")!='' ? $this->input->post("description"):NULL),
						'id_metal'      => $this->input->post("id_metal"),
						'cat_type' 		=> $this->input->post("cat_type"),
						'tgrp_id'       => $this->input->post("tgrp_id"),
						'is_multimetal' => $this->input->post("is_multimetal"),
						'image'         => ($this->input->post("image") != '')?$this->input->post("image"):NULL,
						'created_on'	=> date("Y-m-d H:i:s"),
						'created_by'    => $this->session->userdata('uid')
					);
	 				$this->db->trans_begin();
					$result = $this->$model->insertData($data,'ret_category');
					//print_r($this->db->last_query);exit;
						if(isset($_FILES['file']['name']))
						{ 
							$path='assets/img/ret_category/';
							$file_name = $_FILES['file']['name'];
							if (!is_dir($path)) 
							{
							  mkdir($path,0777, TRUE);
							}
							else
							{
								$file = $path.$file_name;	
								chmod($path,0777);
							   // unlink($file);
							}
							$img=$_FILES['file']['tmp_name'];
							$filename = $result.'_CAT_'.mt_rand(120,1230).".jpg";		
							$imgpath  = 'assets/img/ret_category/'.$filename;
							$upload   = $this->upload_img('image',$imgpath,$img);	
							$data['image']= $filename;
							$this->$model->updateData($data,'id_ret_category ',$result,'ret_category');
						} 

	 				if($result>0)
					{
						
						foreach($purity1 as $k){

						$data['purity'] = array(
							'id_category' => $result,
							'id_purity'			=> $k,
							'created_on'		=> date("Y-m-d H:i:s"),
							'created_by'    	=> $this->session->userdata('uid')
							);
						 $this->$model->insertData($data['purity'],'ret_metal_cat_purity');
						
						}							
					}						
					if($this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
						$result = array('message'=>'New Category added successfully!..','class'=>'success','title'=>'Add Category : ');
					}
					else
					{
						$this->db->trans_rollback();						 	
						$result = array('message'=>'Unable to proceed the requested process..','class'=>'danger','title'=>'Add Category : ');
					}	
					echo json_encode($result);
					break;
			case "edit":
					$data['categorymtr'] = $this->$model->get_ret_category($id);
					$purity = $this->$model->get_category_purity($id);
					$data['purity']   =array('id_purity'  => $purity);
					$data['categorymtr']['purity']=$purity;
					echo json_encode($data['categorymtr']);
					break; 
			case 'delete':
					 $this->db->trans_begin();
					 $this->$model->deleteData('id_ret_category ',$id,'ret_category');
					   if($this->db->trans_status()===TRUE)
						{
						  $this->db->trans_commit();
						  $this->session->set_flashdata('chit_alert', array('message' => 'category deleted successfully','class' => 'success','title'=>'Delete category'));	
						  echo 1;
						}			  
					   else
					   {
						 $this->db->trans_rollback();
						 $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete category'));
						 echo 0;
					   }
						redirect('admin_ret_catalog/category/list');
					break;
					
			case "update":
					$data=array(
						'name'	        => strtoupper($this->input->post("name")),
						'description'   => ($this->input->post("description")!='' ? $this->input->post("description"):NULL),
						'id_metal'      => $this->input->post("id_metal"),
						'is_multimetal' => $this->input->post("is_multimetal"),
						'cat_code'      => $this->input->post("cat_code"),
						'cat_type' 		=> $this->input->post("cat_type"),
						'tgrp_id'       => $this->input->post("tgrp_id"),
						'hsn_code'      => $this->input->post("hsn_code"),
						'updated_on'	=> date("Y-m-d H:i:s"),
						'updated_by'    => $this->session->userdata('uid')
					);
					$this->db->trans_begin();
						if($_FILES['file']['name'])
							 { 
								$path='assets/img/ret_category/';
								$file_name = $_FILES['file']['name'];
								if (!is_dir($path)) 
								{
								  mkdir($path,0777, TRUE);
								}
								else
								{
									$file = $path.$file_name;	
									chmod($path,0777);
								   // unlink($file);
								}
								$img=$_FILES['file']['tmp_name'];
								$filename = $id.'_CAT_'.mt_rand(120,1230).".jpg";	
								$imgpath  = 'assets/img/ret_category/'.$filename;
								$upload   = $this->upload_img('image',$imgpath,$img);	
								$data['image']= $filename;
							 }
						$id=$this->$model->updateData($data,'id_ret_category ',$id,'ret_category');
						$this->$model->deleteData('id_category ',$id,'ret_metal_cat_purity');
						$purity1  = explode(',',$this->input->post('id_purity')); 
						if($id > 0)
						{
							foreach($purity1 as $k){	
								$data['purity'] = array(
									'id_category' 	    => $id,
									'id_purity'			=> $k,
									'created_on'		=> date("Y-m-d H:i:s"),
							        'created_by'    	=> $this->session->userdata('uid')
								);
							$this->$model->insertData($data['purity'],'ret_metal_cat_purity');
							}							
						}			
						
							if($this->db->trans_status()===TRUE)
							 {
								$this->db->trans_commit();
								
								$this->session->set_flashdata('chit_alert',array('message'=>'Category record modified successfully','class'=>'success','title'=>'Edit Category'));
								echo 1;
							 }
							 else
							 {
								$this->db->trans_rollback();						 	
								$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Edit Category'));
								echo 0;
							 }
							 
					break;
			case 'list':
					$data['main_content'] = "master/ret_category/list" ;
					$this->load->view('layout/template', $data);
					break;
			case 'update_status':
					$data = array('status' => $status,'updated_on' => date("Y-m-d H:i:s"),'updated_by' => $this->session->userdata('uid'));
					$updstatus = $this->$model->updateData($data,'id_category ',$id,'ret_category');
					if($updstatus)
					{
						$this->session->set_flashdata('chit_alert',array('message'=>'Category status updated as '.($status == 1 ? 'Active' : 'Inactive').' successfully.','class'=>'success','title'=>'Category  Status'));	
						echo 1;
					}	
					else
					{
						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Category  Status'));
						echo 0;
					}	
				   redirect('admin_ret_catalog/category/list');
				   break;
			case 'active_category': 
					$data = $this->$model->getActiveCategorymtr($_POST);	  
					echo json_encode($data);
					break;
			case 'cat_purity': 
					$data = $this->$model->getCatPurity($_POST['id_category']);	  
					echo json_encode($data);
					break;
			default:
					$SETT_MOD 	= self::SETT_MOD;
					$range['from_date']	= $this->input->post('from_date');
					$range['to_date']	= $this->input->post('to_date');
					$categorymtr 		= $this->$model->ajax_getcategory($range['from_date'],$range['to_date']);	 
					$access 	= $this->$SETT_MOD->get_access('admin_ret_catalog/category/list');
					$data 		= array(
										'categorymtr' => $categorymtr,
										'access'   => $access
									);  
					echo json_encode($data);
		}
	}
	
	function get_category($id)
	{
		$model=self::CAT_MODEL;
		$data = $this->$model->get_catByMetal($id);  
		echo json_encode($data);
	}

	public function karigar($type="",$id="",$status="")
    {
		$model=self::CAT_MODEL;
		$set_model = self::SETT_MOD;
		switch($type)
		{	
			case 'add':
				$data['main_content'] = "master/karigar/form" ;
				$this->load->view('layout/template', $data);
			break;
			case 'print':	
				$data['comp_details'] = $this->$model->getCompanyDetails();
				$data['karigar'] = $this->$model->get_print_karigar($id);
				$data['wastage'] = $this->$model->get_karigar_wastage_details($id);
				$data['stone'] = $this->$model->get_karigar_gem_stones($id);
				$data['bank'] = $this->$model->get_bank_details($id);

			   // echo"<pre>"; print_r($data);exit;
				$html = $this->load->view('master/karigar/karigar_print',$data,true);
		        echo $html;exit;
			break;
			case 'product_details':
			    $data['categories'] = $this->$model->getActiveCategorymtr(array("id_metal"=>''));
			    $data['purities'] = $this->$model->getCatPurity('');
			    $karigar_wastages = $this->$model->get_karigar_wastages($id);
			    foreach($karigar_wastages as $val)
				{
				    $val['karigar_charges'] = $this->$model->get_karigar_charges($val['id_karikar_wast']);
					$val['karigar_wastage_images'] = $this->$model->get_karigar_images($val['id_karikar_wast']);
				    $data['karigar_wastages'][] = $val;
				    
				}
			    $data['products'] = $this->$model->get_ActiveProducts(array("id_ret_category"=>''));
			    $data['designs'] = $this->$model->get_ProductDesign(array("id_product"=>''));
			    $data['sub_designs'] = $this->$model->get_ActiveSubDesigns(array("id_product"=>'',"design_no"=>''));
				//echo"<pre>"; print_r($data['karigar_wastages']);exit;
				echo json_encode($data);
			break;

			case 'kyc_details':
				$data['karigar_kyc_det'] = $this->$model->get_karigar_kyc_det($id);
				$data['kyc'] = $this->$model->get_ActiveKYC();
				echo json_encode($data);
			break;	

			case 'stone_details':
				$data['karigar_stones'] = $this->$model->get_karigar_stones($id);
				$data['stone_type'] = $this->$model->getActiveStoneTypes();
				$data['uom'] = $this->$model->getActiveUOM();
				$data['stones']  = $this->$model->getActiveStone();
				//echo"<pre>"; print_r($data);exit;
				echo json_encode($data);
			break;	
			
			case 'edit':
				$data['karigar'] = $this->$model->get_karigar($id);
				$karigar_wastages = $this->$model->get_karigar_wastages($id);
				$data['bank'] =$this->$model->get_bank_details($id);
				foreach($karigar_wastages as $val)
				{
				    $val['karigar_charges'] = $this->$model->get_karigar_charges($val['id_karikar_wast']);
					$val['karigar_wastage_images'] = $this->$model->get_karigar_images($val['id_karikar_wast']);
				    $data['karigar_wastages'][] = $val;
				    
				}
				$data['karigar_stones'] = $this->$model->get_karigar_stones($id);
				$prod_args = array( "id_category" => '' );
				$data['wastages']['product'] = $this->$model->getActiveProduct($prod_args);
				$data['stones']['stone_type'] = $this->$model->getActiveStoneTypes();
				$data['stones']['uom'] = $this->$model->getActiveUOM();
				$data['charge']['charge_name'] = $this->$model->getActiveCharges();
				foreach($data['karigar_stones'] as $key => $stones)
				{
					$stn_type =array(
						"stone_type" => $stones['stone_type']

					);
					$data['karigar_stones'][$key]['stones']  = $this->$model->get_ActiveStones($stn_type);
				}
                if($data['karigar']['id_country']!='')
                {
                    $data['country'] = json_decode($this->$set_model->get_country(), TRUE);
    				$data['state'] 	 = json_decode($this->$set_model->get_state($data['karigar']['id_country']), TRUE);
    				$data['city'] 	 = json_decode($this->$set_model->get_city($data['karigar']['id_state']), TRUE);
                }
				
				//echo"<pre>"; print_r($data);exit;
				$data['main_content'] = "master/karigar/form" ;
				$this->load->view('layout/template', $data);
			break;
	 		case "save":
					
					$addData=$_POST['karigar'];
					$product_details = $_POST['o_item'];
					$kyc_details = $_POST['kyc_det'];
					$stone_details = $_POST['stn'];
					$bank_details=$_POST['bank'];

					//echo "<pre>";print_r($stone_details);exit;
					
	 				$data=array(
						'firstname'	           => strtoupper($addData['first_name']),
						'lastname'             => $addData['last_name_karigar'],
						//'code_karigar'         => $addData['karigar_code'],
						'karigar_type'         => $addData['user_type'],
						'is_tcs'               => $addData['is_tcs'],
						'is_tds'               => $addData['is_tds'],
						'tcs_tax'              => $addData['tcs_tax'],
						'tds_tax'              => $addData['tds_tax'],
						'karigar_for'          => $addData['karigar_for'],
						'address1'   		   => (!empty($addData['address1']) ? $addData['address1']:NULL),
						'address2'   		   => (!empty($addData['address2']) ? $addData['address2']:NULL),
						'address3'             => (!empty($addData['address3']) ? $addData['address3']:NULL),
						'id_country'		   => (!empty($addData['country']) ? $addData['country']:NULL),
						'id_state'		       => (!empty($addData['stateval']) ? $addData['stateval']:NULL),
						'id_city'		       => (!empty($addData['cityval']) ? $addData['cityval']:NULL),
						'pincode'		       => (!empty($addData['pincode']) ? $addData['pincode']:NULL),
						'contactno2'		   => (!empty($addData['phone']) ? $addData['phone']:NULL),
						'status_karigar'	   => 1,
						'email'		           => (!empty($addData['email']) ? $addData['email']:NULL),
						'contactno1'		   => (!empty($addData['mobile']) ? $addData['mobile']:NULL),
						'urname'		       => (!empty($addData['user_name']) ? $addData['user_name']:NULL),
						'psword'		       => (!empty($addData['password']) ? $addData['password']:NULL),
						'company'		       =>(!empty($addData['company_karigar']) ? $addData['company_karigar']:NULL),
						'gst_number'		   =>(!empty($addData['gst_number']) ? $addData['gst_number']:NULL),
						'ifsc_code'		       =>(!empty($addData['ifsc_code']) ? $addData['ifsc_code']:NULL),
						'acc_number'		   =>(!empty($addData['acc_number']) ? $addData['acc_number']:NULL),
						'pan_no'		   	   =>(!empty($addData['pan_number']) ? $addData['pan_number']:NULL),
						//'karigar_calc_type'	   =>(!empty($addData['karigar_calc_type']) ? $addData['karigar_calc_type']:NULL),
						'remarks'              =>(!empty($addData['remarks']) ? $addData['remarks']:NULL),
						'bank_name'            =>(!empty($addData['bank_name']) ? $addData['bank_name']:NULL),
						'acc_holder_name'      =>(!empty($addData['acc_holder']) ? $addData['acc_holder']:NULL),
						'createdon'            => date("Y-m-d H:i:s"),
						'createdby'            => $this->session->userdata('uid')
					);
					//echo"<pre>"; print_r($data);exit;
	 				$this->db->trans_begin();
						 if($_FILES['image']['name'])
							{
							$path='assets/img/karigar/';
							$file_name = $_FILES['image']['name'];
							if (!is_dir($path))
							{
							 mkdir($path,0777, TRUE);
							}
							else
							{
							$file = $path.$file_name;
							chmod($path,0777);
							  // unlink($file);
							}
							$img=$_FILES['image']['tmp_name'];
							$filename = $id.'_'.mt_rand(120,1230).".jpg";	
							$imgpath  = 'assets/img/karigar/'.$filename;
							$upload   = $this->upload_img('image',$imgpath,$img);
							$data['image']= $filename;
							$status=$this->$model->updateData($data,'id_karigar',$id,'ret_karigar');
							}
					$id_karigar=$this->$model->insertData($data,'ret_karigar');
					//print_r($this->db->last_query());exit;
                    if($id_karigar!='')
					{
						$data=array(
							'code_karigar'         => $id_karigar,
						);
						$this->$model->updateData($data,'id_karigar',$id_karigar,'ret_karigar'); 
					}
					//echo "<pre>"; print_r($product_details);exit;
					foreach($product_details as $val) 
					{
					    if($val['is_item_select']==1)
					    {
    						$wast = array(
    						    
    							'id_karikar'	=>	$id_karigar,
    							'id_category'   =>  $val['category'],
                                'id_purity'     =>  $val['purity'],
                                'karigar_calc_type' => $val['kar_calc_type'],
    							'id_product' 	=>	$val['product'],
    							'id_design' 	=>	$val['design'],
    							'id_sub_design' =>	$val['sub_design'],
    							'wastage_type'  =>  ($val['va_type']!='' ? $val['va_type'] : 1),
    							'wastage_per' 	=>	($val['va_type']==1 ? ($val['wast_percent']!='' ? $val['wast_percent']:0):0),
    							'wastage_wt' 	=>	($val['va_type']==2 ? ($val['wast_wgt']!='' ? $val['wast_wgt']:0):0),
        						'mc_type'       =>  $val['id_mc_type'],
        						'mc_value'      =>  ($val['mc']!='' ? $val['mc']:0),
        						'pur_touch'     =>  ($val['pur_touch']!='' ? $val['pur_touch']:0),
        						'calc_type'	    =>	$val['calc_type'],
    							'active'        =>  0,
    							'status'        =>  0,
    							'created_on'	=>  date("Y-m-d H:i:s"),
    							'created_by'    =>  $this->session->userdata('uid')						
    						);
    						
						    $id_karikar_wast = $this->$model->insertData($wast,'ret_karikar_items_wastage');
						    if($id_karikar_wast)
    						{

								/*Karigar Wastage Product Image*/
								$WastProImg = $val['order_img'];

								$p_ImgData = json_decode($WastProImg); 
								

								$is_defalut_img='';
								
								$is_default=0;

								if(sizeof($p_ImgData) > 0)
								{
									$_FILES['precious']    =  array();
									foreach($p_ImgData as $key => $precious)
									{
										if($precious->is_default==1)
										{
											$is_defalut_img=$key;
										}
										$imgFile = $this->base64ToFile($precious->src);
										$_FILES['precious'][] = $imgFile;


									}
								} 

								if(!empty($p_ImgData))
								{
									$folder =  self::IMG_PATH."karigar/product_wastage/".$id_karikar_wast; 

									if (!is_dir($folder)) {  
										mkdir($folder, 0777, TRUE);
									} 

									if(isset($_FILES['precious']))
									{
										//echo "<pre>";print_r($_FILES['wast_pro_images']);exit;

										foreach($_FILES['precious'] as $file_key => $file_val)
										{
											if($file_val['name'])
    		            					{
												$img_name =  "$id_karikar_wast". mt_rand(100001,999999).".jpg";
												$path = $folder."/".$img_name; 
												$result=$this->upload_img('image',$path,$file_val['tmp_name']);
												if($result)
												{   
													if($is_defalut_img==$file_key)
													{
														$is_default=1;
													}else{
														$is_default=0;
													}
													$imgData=array('is_default'=>$is_default,'id_karikar_wast'=>$id_karikar_wast,'image_name'=>$img_name);

													$this->$model->insertData($imgData,'ret_karigar_item_wast_pro_images'); 

													
												}
											}
										}
										
									}
								}
								//echo "<pre>";print_r($imgData);exit;
                                /*Karigar Wastage Product Image*/

    						    $charges_details = json_decode($val['charges_details'],true);
    						    if(sizeof($charges_details)>0)
    						    {
    						        foreach($charges_details as $charge)
    						        {
    						            $charge_data = array(
                								'id_karigar'         =>$id,
                								'id_karikar_wast'    =>$id_karikar_wast,
                								'charge_id'          =>$charge['charge_id'],
                								'calc_type'          =>$charge['calc_type'],
                								'charge_value'       =>$charge['charge_value'],
                								'active'             =>0,
                								'updated_on'	     =>date("Y-m-d H:i:s"),
                								'updated_by'         =>$this->session->userdata('uid')
                							);
                							$this->$model->insertData($charge_data,'ret_karigar_charges');
    						        }
    						    }
    						}
					    }
					}

					foreach($stone_details as  $s)
					{
						//echo"<pre>"; print_r($s);exit;
						if($s['is_stn_select']==1)
					    {
							$stn = array(

								'id_karigar'         =>   $id_karigar,
								'stone_type'         =>   $s['stn_type'],
								'stone_id'           =>   $s['stn_name'],
								'stone_cal_type'     =>   $s['stn_calc_type'],
								'uom_id'             =>   $s['uom_id'],
								'rate_per_gram'      =>   $s['stone_rate'],
								'active'        =>  0,
								'status'        =>  0,
								'created_on'	=>  date("Y-m-d H:i:s"),
								'created_by'    =>  $this->session->userdata('uid')
	
							);

							$this->$model->insertData($stn,'ret_karigar_stones');
							//print_r($this->db->last_query());exit;
						}
					}

				    foreach($kyc_details as $val)
					{
						$kyc = array(
							'id_karigar'	=>	$id_karigar,
							'id_kyc' 	    =>	$val['proof_name'],
							'kyc_number'    =>  $val['id_reg_exp'],
							'document'      =>  $val['document_file'],
							'created_on'	=>  date("Y-m-d H:i:s"),
							'created_by'    =>  $this->session->userdata('uid')			
						);

						if($val['img_type']==1)
						{
							$img_type='jpg';
						}
						else if($val['img_type']==2)
						{
							$img_type='jpeg';
						}
						else
						{
							$img_type='png';
						}
						
						if($val['order_img_fr']!='')
						{  

							
							$p_ImgData=[];
							$p_ImgData = json_decode($val['order_img_fr']);
							
							if(sizeof($p_ImgData) > 0)
							{
								foreach($p_ImgData as $precious){
									$imgFile = $this->base64ToFile($precious->src);
									$_FILES['order_img_fr'][] = $imgFile;
								}

							} 

							if(!empty($_FILES))
							{
								$img_arr = array();
								$folder =  self::IMG_PATH."karigar/kyc/";
								if (!is_dir($folder)) {  
									mkdir($folder, 0777, TRUE);
								}   
								if(isset($_FILES['order_img_fr'])){ 
									$order_images = "";

									foreach($_FILES['order_img_fr'] as $file_key => $file_val){

										if($file_val['name'])
										{
											// unlink($folder."/".$product['image']); 
											$img_name =  $insOrderDet."_". mt_rand(100001,999999).'.'.$img_type;
											$path = $folder."/".$img_name; 
											$result = $this->upload_img('image',$path,$file_val['tmp_name']);
											if($result)
											{
												$kyc['front_images'] = $img_name;
											}
										}
									}
								}
							}
						}

						if($val['order_img_bk']!='')
						{

							$p_ImgData=[];
							$p_ImgData = json_decode($val['order_img_bk']);
							
							if(sizeof($p_ImgData) > 0)
							{
								foreach($p_ImgData as $precious){
									$imgFile = $this->base64ToFile($precious->src);
									$_FILES['order_img_bk'][] = $imgFile;
								}

							} 

							if(!empty($_FILES))
							{
								$img_arr = array();
								$folder =  self::IMG_PATH."karigar/kyc/";
								if (!is_dir($folder)) {  
									mkdir($folder, 0777, TRUE);
								}   
								if(isset($_FILES['order_img_bk'])){ 
									$order_images = "";

									foreach($_FILES['order_img_bk'] as $file_key => $file_val){

										if($file_val['name'])
										{
											// unlink($folder."/".$product['image']); 
											$img_name =  $insOrderDet."_". mt_rand(100001,999999).'.'.$img_type;
											$path = $folder."/".$img_name; 
											$result = $this->upload_img('image',$path,$file_val['tmp_name']);
											if($result)
											{
												$kyc['back_images'] = $img_name;
											}
										}
									}
								}
							}
						}

						$id_karigar_kyc = $this->$model->insertData($kyc,'ret_karigar_kyc');
					}
					
					if(!empty($bank_details)){
						$arraybank = array();
						foreach($bank_details['bank_name'] as $key => $val)
						{
                            $arraybank= array(
                            'id_karigar'     =>	 $id,
                            'account_name'   =>	 $bank_details['acc_holder'][$key],
                            'account_number' =>	 $bank_details['acc_number'][$key], 
                            'bank_name'      =>	 $bank_details['bank_name'][$key], 
                            'ifsc_code '     =>	 $bank_details['ifsc_code'][$key],
                            );
                            $this->$model->insertData($arraybank,'ret_karigar_bank_acc_details');
						}
					}
					
					if($this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
						$result = array('message'=>'New User added successfully!..','class'=>'success','title'=>'Add User : ');
						$this->session->set_flashdata('chit_alert', array('message' => 'New User added successfully!..','class' => 'success','title'=>'Add User'));	  

					}
					else
					{
						$this->db->trans_rollback();						 	
						$result = array('message'=>'Unable to proceed the requested process..','class'=>'danger','title'=>'Add User : ');
						$this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Add User'));
					}
					redirect('admin_ret_catalog/karigar/list');	
					break;
	
	 	
			case 'delete':
						 $this->db->trans_begin();
						 $this->$model->deleteData('id_karigar',$id,'ret_karigar');
						 $this->$model->deleteData('id_karikar',$id,'ret_karikar_items_wastage');
						 $this->$model->deleteData('id_karigar',$id,'ret_karigar_kyc');
				           if( $this->db->trans_status()===TRUE)
						    {
						    	  $this->db->trans_commit();
								  $this->session->set_flashdata('chit_alert', array('message' => 'User deleted successfully','class' => 'success','title'=>'Delete User'));	  
							}			  
						   else
						    {
							 $this->db->trans_rollback();
							 $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete User'));
						    }
						 redirect('admin_ret_catalog/karigar/list');	
						 break;
					
			case "update":
				$addData=$_POST['karigar'];
				$product_details = $_POST['o_item'];
				$stone_details = $_POST['stn'];
				$kyc_details = $_POST['kyc_det'];
				$bank_details = (isset($_POST['bank']) ?$_POST['bank']:'');	
				//echo"<pre>"; print_r($addData);exit;
				 $data=array(
					'firstname'	           => strtoupper($addData['first_name']),
					'lastname'             => $addData['last_name_karigar'],
					'code_karigar'         => $addData['karigar_code'],
					'karigar_type'         => $addData['user_type'],
				    'is_tcs'               => $addData['is_tcs'],
					'is_tds'               => $addData['is_tds'],
					'tcs_tax'              => $addData['tcs_tax'],
					'tds_tax'              => $addData['tds_tax'],
					'karigar_for'          => $addData['karigar_for'],
					'address1'   		   => (!empty($addData['address1']) ? $addData['address1']:NULL),
					'address2'   		   => (!empty($addData['address2']) ? $addData['address2']:NULL),
					'address3'             => (!empty($addData['address3']) ? $addData['address3']:NULL),
					'id_country'		   => (!empty($addData['country']) ? $addData['country']:NULL),
					'id_state'		       => (!empty($addData['stateval']) ? $addData['stateval']:NULL),
					'id_city'		       => (!empty($addData['cityval']) ? $addData['cityval']:NULL),
					'pincode'		       => (!empty($addData['pincode']) ? $addData['pincode']:NULL),
					'contactno2'		   => (!empty($addData['phone']) ? $addData['phone']:NULL),
					'status_karigar'	   => 1,
					'email'		           => (!empty($addData['email']) ? $addData['email']:NULL),
					'contactno1'		   => (!empty($addData['mobile']) ? $addData['mobile']:NULL),
					'urname'		       => (!empty($addData['user_name']) ? $addData['user_name']:NULL),
					'psword'		       => (!empty($addData['password']) ? $addData['password']:NULL),
					'company'		       =>(!empty($addData['company_karigar']) ? $addData['company_karigar']:NULL),
					'gst_number'		   =>(!empty($addData['gst_number']) ? $addData['gst_number']:NULL),
					'ifsc_code'		       =>(!empty($addData['ifsc_code']) ? $addData['ifsc_code']:NULL),
					'acc_number'		   =>(!empty($addData['acc_number']) ? $addData['acc_number']:NULL),
					'bank_name'            =>(!empty($addData['bank_name']) ? $addData['bank_name']:NULL),
					'acc_holder_name'      =>(!empty($addData['acc_holder']) ? $addData['acc_holder']:NULL),
					'pan_no'		   	   =>(!empty($addData['pan_number']) ? $addData['pan_number']:NULL),
					//'karigar_calc_type'	   =>(!empty($addData['karigar_calc_type']) ? $addData['karigar_calc_type']:NULL),
					'remarks'	           =>(!empty($addData['remarks']) ? $addData['remarks']:NULL),
					'createdon'            => date("Y-m-d H:i:s"),
					'createdby'            => $this->session->userdata('uid')
				);
	 			
	 			        $this->db->trans_begin();
	 			       // echo "<pre>"; print_r($_FILES);exit;
							if($_FILES['karigar']['name']['user_img'])
							{
								$path='assets/img/karigar/';
								if (!is_dir($path))
								{
								 mkdir($path,0777, TRUE);
								}
    							else
    							{
    								$file = $path;
    								chmod($path,0777);
    							}
								$img=$_FILES['karigar']['tmp_name']['user_img'];
							
								$filename = $id.'_'.mt_rand(00001,99999).".jpg";
								$imgpath  = 'assets/img/karigar/'.$filename;
								$upload   = $this->upload_img('image',$imgpath,$img);
								$data['image']= $filename;
							}
						
						$this->$model->updateData($data,'id_karigar',$id,'ret_karigar'); 
						//print_r($this->db->last_query());exit;
				
						foreach($product_details as $val) 
    					{
    					    if($val['is_item_select']==1)
    					    {	
								$this->$model->deleteData('id_karikar_wast',$val['id_karikar_wast'],'ret_karikar_items_wastage');
        						$wast = array(
        							'id_karikar'	=>	$id,
									'id_category'   =>  $val['category'],
        							'id_product' 	=>	$val['product'],
        							'id_design' 	=>	$val['design'],
        							'id_sub_design' =>	$val['sub_design'],
									'id_purity'     =>  $val['purity'],
        							'wastage_type'  =>  ($val['va_type']!='' ? $val['va_type'] : 1),
        							'wastage_per' 	=>	($val['wast_percent']!='' ? $val['wast_percent']:0),
        							'wastage_wt' 	=>	($val['wast_wgt']!='' ? $val['wast_wgt']:0),
        							'mc_type'       =>  $val['id_mc_type'],
        							'mc_value'      =>  ($val['mc']!='' ? $val['mc']:0),
        							'pur_touch'     =>  ($val['pur_touch']!='' ? $val['pur_touch']:0),
									'karigar_calc_type' => $val['kar_calc_type'],
        							'calc_type'	    =>	$val['calc_type'],
        							'active'        =>  0,
        							'status'        =>  0,
        							'created_on'	=>  date("Y-m-d H:i:s"),
        							'created_by'    =>  $this->session->userdata('uid')						
        						);
        
        						$id_karikar_wast = $this->$model->insertData($wast,'ret_karikar_items_wastage');
        						if($id_karikar_wast)
        						{
        							/*Karigar Wastage Product Image*/
								$WastProImg = $val['order_img'];

								$p_ImgData = json_decode($WastProImg); 
								

								$is_defalut_img='';
								
								$is_default=0;

								if(sizeof($p_ImgData) > 0)
                                {
                                	$_FILES['precious']    =  array();
                                
                                	foreach($p_ImgData as $key => $precious)
                                	{
                                		if($precious->is_default==1)
                                		{
                                			$is_defalut_img=$key;
                                		}
                                		$imgFile = $this->base64ToFile($precious->src);
                                		$_FILES['precious'][] = $imgFile;
                                
                                
                                	}
                                } 

							if(!empty($p_ImgData))
                            {
                            	$folder =  self::IMG_PATH."karigar/product_wastage/".$id_karikar_wast; 
                            
                            	if (!is_dir($folder)) {  
                            		mkdir($folder, 0777, TRUE);
                            	} 
                            
                            	if(isset($_FILES['precious']))
                            	{
                            		//echo "<pre>";print_r($_FILES['wast_pro_images']);exit;
                            
                            		foreach($_FILES['precious'] as $file_key => $file_val)
                            		{
                            			//echo "<pre>";print_r($file_val);exit;
                            			if($file_val['name'])
                            			{
                            				$img_name =  "$id_karikar_wast". mt_rand(100001,999999).".jpg";
                            				$path = $folder."/".$img_name; 
                            				$result=$this->upload_img('image',$path,$file_val['tmp_name']);
                            				if($result)
                            				{
                            					if($is_defalut_img==$file_key)
                            					{
                            						$is_default=1;
                            					}else{
                            						$is_default=0;
                            					}
                            
                            					$imgData=array('is_default'=>$is_default,'id_karikar_wast'=>$id_karikar_wast,'image_name'=>$img_name);
                            					$this->$model->insertData($imgData,'ret_karigar_item_wast_pro_images'); 
                            
                            				}
                            			}
                            		}										
                            	}
                            }

								/*Karigar Wastage Product Image*/


        						    $charges_details = json_decode($val['charges_details'],true);
        						    if(sizeof($charges_details)>0)
        						    {
        						        foreach($charges_details as $charge)
        						        {
        						            $charge_data = array(
                    								'id_karigar'         =>$id,
                    								'id_karikar_wast'    =>$id_karikar_wast,
                    								'charge_id'          =>$charge['charge_id'],
                    								'charge_value'       =>$charge['charge_value'],
                    								'calc_type'          =>$charge['calc_type'],
                    								'active'             =>0,
                    								'updated_on'	     =>date("Y-m-d H:i:s"),
                    								'updated_by'         =>$this->session->userdata('uid')
                    							);
                    							$this->$model->insertData($charge_data,'ret_karigar_charges');
        						        }
        						    }
        						}
    					    }
    					}

						foreach($stone_details as  $s)
						{
							//echo"<pre>"; print_r($s);exit;
							if($s['is_stn_select']==1)
							{
								$stn = array(

									'id_karigar'         =>   $id,
									'stone_type'         =>   $s['stn_type'],
									'stone_id'           =>   $s['stn_name'],
									'stone_cal_type'     =>   $s['stn_calc_type'],
									'uom_id'             =>   $s['uom_id'],
									'rate_per_gram'      =>   $s['stone_rate'],
									'active'             =>  0,
									'status'             =>  0,
									'updated_on'	     =>  date("Y-m-d H:i:s"),
									'updated_by'         =>  $this->session->userdata('uid')
		
								);

								$this->$model->insertData($stn,'ret_karigar_stones');
								//print_r($this->db->last_query());exit;
							}
						}

						$this->$model->deleteData('id_karigar',$id,'ret_karigar_kyc');

						foreach($kyc_details as $val)
						{
							$kyc = array(
								'id_karigar'	=>	$id,
								'id_kyc' 	    =>	$val['proof_name'],
								'kyc_number'    =>  $val['id_reg_exp'],
								'document'      =>  $val['document_file'],
								'created_on'	=>  date("Y-m-d H:i:s"),
								'created_by'    =>  $this->session->userdata('uid')			
							);

							if($val['img_type']==1)
							{
								$img_type='jpg';
							}
							else if($val['img_type']==2)
							{
								$img_type='jpeg';
							}
							else
							{
								$img_type='png';
							}
							
							if($val['order_img_fr']!='')
							{  

								
								$p_ImgData=[];
								$p_ImgData = json_decode($val['order_img_fr']);
								
								if(sizeof($p_ImgData) > 0)
								{
									foreach($p_ImgData as $precious){
										$imgFile = $this->base64ToFile($precious->src);
										$_FILES['order_img_fr'][] = $imgFile;
									}

								} 

								if(!empty($_FILES))
								{
									$img_arr = array();
									$folder =  self::IMG_PATH."karigar/kyc/";
									if (!is_dir($folder)) {  
										mkdir($folder, 0777, TRUE);
									}   
									if(isset($_FILES['order_img_fr'])){ 
										$order_images = "";

										foreach($_FILES['order_img_fr'] as $file_key => $file_val){

											if($file_val['name'])
											{
												// unlink($folder."/".$product['image']); 
												$img_name =  $insOrderDet."_". mt_rand(100001,999999).'.'.$img_type;
												$path = $folder."/".$img_name; 
												$result = $this->upload_img('image',$path,$file_val['tmp_name']);
												if($result)
												{
													$kyc['front_images'] = $img_name;
												}
											}
										}
									}
								}
							}

							if($val['order_img_bk']!='')
							{

								$p_ImgData=[];
								$p_ImgData = json_decode($val['order_img_bk']);
								
								if(sizeof($p_ImgData) > 0)
								{
									foreach($p_ImgData as $precious){
										$imgFile = $this->base64ToFile($precious->src);
										$_FILES['order_img_bk'][] = $imgFile;
									}

								} 

								if(!empty($_FILES))
								{
									$img_arr = array();
									$folder =  self::IMG_PATH."karigar/kyc/";
									if (!is_dir($folder)) {  
										mkdir($folder, 0777, TRUE);
									}   
									if(isset($_FILES['order_img_bk'])){ 
										$order_images = "";

										foreach($_FILES['order_img_bk'] as $file_key => $file_val){

											if($file_val['name'])
											{
												// unlink($folder."/".$product['image']); 
												$img_name =  $insOrderDet."_". mt_rand(100001,999999).'.'.$img_type;
												$path = $folder."/".$img_name; 
												$result = $this->upload_img('image',$path,$file_val['tmp_name']);
												if($result)
												{
													$kyc['back_images'] = $img_name;
												}
											}
										}
									}
								}
							}

							$id_karigar_kyc = $this->$model->insertData($kyc,'ret_karigar_kyc');
						}
						
						$this->$model->deleteData('id_karigar',$id,'ret_karigar_bank_acc_details');
						if(!empty($bank_details)){
							$arraybank = array();
							foreach($bank_details['bank_name'] as $key => $val)
							{
                                $arraybank= array(
                                'id_karigar'     =>	 $id,
                                'account_name'   =>	 $bank_details['acc_holder'][$key],
                                'account_number' =>	 $bank_details['acc_number'][$key], 
                                'bank_name'      =>	 $bank_details['bank_name'][$key], 
                                'ifsc_code '     =>	 $bank_details['ifsc_code'][$key],
                                );
                                $this->$model->insertData($arraybank,'ret_karigar_bank_acc_details');
							}
						}

			            if($this->db->trans_status()===TRUE)
						{
							$this->db->trans_commit();
							$this->session->set_flashdata('chit_alert',array('message'=>'User record modified successfully','class'=>'success','title'=>'Edit User'));			
						}
						else
						{
							$this->db->trans_rollback();						 	
							$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Edit User'));
							
						}
						redirect('admin_ret_catalog/karigar/list');	
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
							echo 1;
						}	
						else
						{
							$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'User Status'));
							echo 0;
						}	
						redirect('admin_ret_catalog/karigar/list');
			break;
			case 'active_list': 
					  	$data = $this->$model->getActiveKarigar($id);	  
						echo json_encode($data);
					    break;
			case 'list':
						$data['main_content'] = "master/karigar/list" ;
						$this->load->view('layout/template', $data);
					    break;
			case 'mobile_available':
				$data=$this->$model->mobile_available($_POST['mobile']);
				echo json_encode($data);
			break;

			case 'email_available':
				$data=$this->$model->email_available($_POST['email']);
				echo json_encode($data);
			break;
				
			default:
						$SETT_MOD = self::SETT_MOD;
						$range['from_date']  = $this->input->post('from_date');
						$range['to_date']  = $this->input->post('to_date');
						$range['karigar_for']  = $this->input->post('karigar_for');
					  	$karigar = $this->$model->ajax_getkarigar($range['from_date'],$range['to_date'],$range['karigar_for'],$_POST['karigar_type']);	 
					  	$access = $this->$SETT_MOD->get_access('admin_ret_catalog/karigar/list');
				        $data = array(
				        					'karigar' =>$karigar,
											'access'=>$access
				        			 );  
						echo json_encode($data);
		}
	}
	
	public function material_rate($type="",$id="",$status=""){
		$model=self::CAT_MODEL;
		switch($type)
		{
			case "add":
	 				$data=array(
					    'material_id'     => $this->input->post("material_id"),
						'mat_rate_id'     => $this->input->post("mat_rate_id"),
						'mat_rate'	      => $this->input->post("mat_rate"),
						'effective_date'  => date('Y-m-d',strtotime(str_replace("/","-",$this->input->post("effective_date")))),
						'created_on'	  => date("Y-m-d H:i:s"),
						'created_by'      => $this->session->userdata('uid')
					);
	 				$this->db->trans_begin();
	 				$this->$model->insertData($data,'ret_material_rate');
					if($this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
						$result = array('message'=>'New Material rate added successfully!..','class'=>'success','title'=>'Add Material Rate : ');
					}
					else
					{
						$this->db->trans_rollback();						 	
						$result=array('message'=>'Unable to proceed the requested process..','class'=>'danger','title'=>'Add Material Rate : ');
					
					}	
					echo json_encode($result);
					break;
			case "edit":
	 			$data['mtrrate'] = $this->$model->get_materialrate($id);
	 			echo json_encode($data['mtrrate']);
				break; 
			case 'delete':
						 $this->db->trans_begin();
						 $this->$model->deleteData('mat_rate_id',$id,'ret_material_rate');
				           if( $this->db->trans_status()===TRUE)
						    {
						      $this->db->trans_commit();
							  $this->session->set_flashdata('chit_alert', array('message' => 'Material rate deleted successfully','class' => 'success','title'=>'Delete Material Rate'));	
							  echo 1;
							}			  
						   else
						   {
							 $this->db->trans_rollback();
							 $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete Material Rate'));
							 echo 0;
						   }
						 	redirect('admin_ret_catalog/material_rate/list');
				break;
					
			case "update":
	 			$data=array(
					'material_id'    => $this->input->post("material_id"),
					'mat_rate'	      => $this->input->post("mat_rate"),
					'effective_date'  => date('Y-m-d',strtotime(str_replace("/","-",$this->input->post("effective_date")))),
				  );
	 			 $this->db->trans_begin();
			            
			          $this->$model->updateData($data,'mat_rate_id',$id,'ret_material_rate');
			            if($this->db->trans_status()===TRUE)
			             {
						 	$this->db->trans_commit();
						 	
						 	$this->session->set_flashdata('chit_alert',array('message'=>'Material rate record modified successfully','class'=>'success','title'=>'Edit Material Rate'));
						 	echo 1;
						 }
						 else
						 {
						 	$this->db->trans_rollback();						 	
						 	$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Edit Material Rate'));
						 	echo 0;
						 }
						 
				break;
			case 'material_lst':								
					$data = $this->$model->get_material_lst();
					echo json_encode($data);
				break;
			case 'list':
						$data['main_content'] = "master/material/rate_list" ;
						$this->load->view('layout/template', $data);
						break;
			default:
						$SETT_MOD 	= self::SETT_MOD;
						
				     	$range['from_date']	= $this->input->post('from_date');
				        $range['to_date']	= $this->input->post('to_date');
						$material_id	    = $this->input->post('material_id');
					  	$mtrrate 		    = $this->$model->ajax_getmtrrate($range['from_date'],$range['to_date'],$material_id);	
					  	$access 	        = $this->$SETT_MOD->get_access('admin_ret_catalog/material_rate/list');
				        $data 		        = array(
				        					  'mtrrate' => $mtrrate,
											  'access'   => $access
				        				      );									  
						echo json_encode($data);
		}
	}
	public function tag($type="",$id="",$status=""){
		$model=self::CAT_MODEL;
		switch($type)
		{
			case "add":
	 				$data=array(
						'tag_name'	      => $this->input->post("tag_name"),
						'tag_status'	  => $this->input->post("tag_status"),
						'created_time'	  => date("Y-m-d H:i:s"),
						'create_by'      => $this->session->userdata('uid')
					);
	 				$this->db->trans_begin();
	 				$this->$model->insertData($data,'ret_tag_type_master');
					if($this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
						$result = array('message'=>'New Tag added successfully!..','class'=>'success','title'=>'Add Tag : ');
					
					}
					else
					{
						$this->db->trans_rollback();						 	
						$result=array('message'=>'Unable to proceed the requested process..','class'=>'danger','title'=>'Add Tag : ');
				
					}	
					echo json_encode($result);
					break;
			case "edit":
	 			$data['tag'] = $this->$model->get_tag($id);
	 			echo json_encode($data['tag']);
				break; 
	 	
			case 'delete':
						 $this->db->trans_begin();
						 $this->$model->deleteData('tag_id',$id,'ret_tag_type_master');
				           if( $this->db->trans_status()===TRUE)
						    {
						      $this->db->trans_commit();
							  $this->session->set_flashdata('chit_alert', array('message' => 'Tag deleted successfully','class' => 'success','title'=>'Delete Tag'));	
							  echo 1;
							}			  
						   else
						   {
							 $this->db->trans_rollback();
							 $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete Tag'));
							 echo 0;
						   }
						 	redirect('admin_ret_catalog/tag/list');
				break;
					
			case "update":
	 			$data=array(
					'tag_name'	      => $this->input->post("tag_name"),
					'tag_status'	  => $this->input->post("tag_status"),
					'updated_time'	  => date("Y-m-d H:i:s"),
					'updated_by'      => $this->session->userdata('uid')
				);
	 			 $this->db->trans_begin();
			            
			          $this->$model->updateData($data,'tag_id',$id,'ret_tag_type_master');
			            if($this->db->trans_status()===TRUE)
			             {
						 	$this->db->trans_commit();
						 	
						 	$this->session->set_flashdata('chit_alert',array('message'=>'Tag record modified successfully','class'=>'success','title'=>'Edit Tag'));
						 	echo 1;
						 }
						 else
						 {
						 	$this->db->trans_rollback();						 	
						 	$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Edit Tag'));
						 	echo 0;
						 }
						 
				break;
			case 'list':
						$data['main_content'] = "master/tag/list" ;
						$this->load->view('layout/template', $data);
						break;
			case 'update_status':
			
						$data = array('tag_status' => $status,'updated_time' => date("Y-m-d H:i:s"),'updated_by' => $this->session->userdata('uid'));
						$updstatus = $this->$model->updateData($data,'tag_id',$id,'ret_tag_type_master');
						if($updstatus)
						{
							$this->session->set_flashdata('chit_alert',array('message'=>'Tag status updated as '.($status == 1 ? 'Active' : 'Inactive').' successfully.','class'=>'success','title'=>'Tag  Status'));		
							echo 1;
						}	
						else
						{
							$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Tag  Status'));
							echo 0;
						}	
					   redirect('admin_ret_catalog/tag/list');
					   break;
			case 'active_tag': 
					  	$data = $this->$model->getActivetag();	  
						echo json_encode($data);
					    break;
			default:
						$SETT_MOD 	= self::SETT_MOD;
				     	$range['from_date']	= $this->input->post('from_date');
				        $range['to_date']	= $this->input->post('to_date');
					  	$tag 		= $this->$model->ajax_gettag($range['from_date'],$range['to_date']);	 
					  	$access 	= $this->$SETT_MOD->get_access('admin_ret_catalog/tag/list');
				        $data 		= array(
				        					'tag' =>$tag,
											'access'=>$access
				        				);  
						echo json_encode($data);
		}
	}
	
	public function design($type="",$id="",$status=""){
		$model=self::CAT_MODEL; 
		switch($type)
		{			 
			case 'active_designBySearch': 
				  	$data = $this->$model->getSearchDesign($_POST['searchTxt'],$_POST['product_id']); 						
				  	echo json_encode($data);
				break; 
			case 'd_sizes': 
				  	$data = $this->$model->getDesignSizes($_POST['design_no']); 										
				  	echo json_encode($data);
				break; 
			case 'd_purities': 
				  	$data = $this->$model->getDesignPurities($_POST['design_no']); 										
				  	echo json_encode($data);
				break; 
		}
	}
	
	public function tax($type="",$id="",$status=""){
		$model=self::CAT_MODEL;
		switch($type)
		{
			case "add":
	 				$data=array(
						'tax_name'	      => $this->input->post("tax_name"),
						'tax_code'	      => $this->input->post("tax_code"),
						'tax_percentage'  => $this->input->post("tax_percentage"),
						'branch_code'     => NULL,//$this->input->post("branch_code"),
						'tax_status'	  => $this->input->post("tax_status"),
						'created_on'	  => date("Y-m-d H:i:s"),
						'created_by'       => $this->session->userdata('uid')
					);
	 				$this->db->trans_begin();
	 				$this->$model->insertData($data,'ret_taxmaster');
					if($this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
						$result = array('message'=>'New Tax added successfully!..','class'=>'success','title'=>'Add Tax : ');
					}
					else
					{
						$this->db->trans_rollback();						 	
						$result=array('message'=>'Unable to proceed the requested process..','class'=>'danger','title'=>'Add Tax : ');
					}	
					echo json_encode($result);
					break;
			case "edit":
	 			$data['tax'] = $this->$model->get_tax($id);
	 			echo json_encode($data['tax']);
				break; 
	 	
			case 'delete':
						 $this->db->trans_begin();
						 $this->$model->deleteData('tax_id',$id,'ret_taxmaster');
				           if( $this->db->trans_status()===TRUE)
						    {
						      $this->db->trans_commit();
							  $this->session->set_flashdata('chit_alert', array('message' => 'Tax deleted successfully','class' => 'success','title'=>'Delete Tax'));	
							  echo 1;
							}			  
						   else
						   {
							 $this->db->trans_rollback();
							 $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete Tax'));
							 echo 0;
						   }
						 	redirect('admin_ret_catalog/tax/list');
				break;
					
			case "update":
	 			$data=array(
					'tax_name'	      => $this->input->post("tax_name"),
					'tax_code'	      => $this->input->post("tax_code"),
					'tax_percentage'  => $this->input->post("tax_percentage"),
					'branch_code'     => NULL,//$this->input->post("branch_code"),
					'tax_status'	  => $this->input->post("tax_status"),
					'modified_time'	  => date("Y-m-d H:i:s"),
					'modified_by'      => $this->session->userdata('uid')
				);
	 			 $this->db->trans_begin();
			            
			          $this->$model->updateData($data,'tax_id',$id,'ret_taxmaster');
			            if($this->db->trans_status()===TRUE)
			             {
						 	$this->db->trans_commit();
						 	
						 	$this->session->set_flashdata('chit_alert',array('message'=>'Tax record modified successfully','class'=>'success','title'=>'Edit Tax'));
						 	echo 1;
						 }
						 else
						 {
						 	$this->db->trans_rollback();						 	
						 	$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Edit Tax'));
						 	echo 0;
						 }
						 
				break;
			case 'list':
						$data['main_content'] = "master/tax/list" ;
						$this->load->view('layout/template', $data);
						break;
			case 'update_status':
			
						$data = array('tax_status' => $status,'modified_on' => date("Y-m-d H:i:s"),'modified_by' => $this->session->userdata('uid'));
						$updstatus = $this->$model->updateData($data,'tax_id',$id,'ret_taxmaster');
						if($updstatus)
						{
							$this->session->set_flashdata('chit_alert',array('message'=>'Tax status updated as '.($status == 1 ? 'Active' : 'Inactive').' successfully.','class'=>'success','title'=>'Tax  Status'));		
							echo 1;
						}	
						else
						{
							$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Tax  Status'));
							echo 0;
						}	
					   redirect('admin_ret_catalog/tax/list');
					   break;
			case 'active_tax': 
					  	$data = $this->$model->getActivetax();	  
						echo json_encode($data);
					    break;
			default:
						$SETT_MOD 	= self::SETT_MOD;
				     	$range['from_date']	= $this->input->post('from_date');
				        $range['to_date']	= $this->input->post('to_date');
					  	$tax 		= $this->$model->ajax_gettax($range['from_date'],$range['to_date']);	 
					  	$access 	= $this->$SETT_MOD->get_access('admin_ret_catalog/tax/list');
				        $data 		= array(
				        					'tax'   =>$tax,
											'access'=>$access
				        				);  
						echo json_encode($data);
		}
	}
	
	public function ret_product($type="",$id="",$status=""){
		$model=self::CAT_MODEL;
		switch($type)
		{
			case "add":		
						$data['product']=$this->$model->getProd_empty_record();
						$data['main_content'] = "master/ret_product/form" ;
						$this->load->view('layout/template', $data);
						break;
	 		case "save":
					$addData = $_POST['product'];
					//echo "<pre>"; print_r($addData); echo "</pre>"; exit;
					//$short_code = $this->$model->genProdShortCode();
	 				$data = array(
						'cat_id'	           => (!empty($addData['cat_id']) ? $addData['cat_id'] :NULL ),
						'tgrp_id'	           => (!empty($addData['tgrp_id']) ? $addData['tgrp_id'] :NULL ),
						'id_section'	       => (!empty($addData['id_section']) ? $addData['id_section'] :NULL ),
						'hsn_code'             => (!empty($addData['hsn_code']) ? $addData['hsn_code'] :NULL ),
						'stock_type'           => (!empty($addData['stock_type']) ? $addData['stock_type'] :1 ),
						'sales_mode'   		   => (!empty($addData['sales_mode']) ? $addData['sales_mode'] :1 ),
						'purchase_mode'   	   => (!empty($addData['purchase_mode']) ? $addData['purchase_mode'] :1 ),
						'wastage_type'   	   => (!empty($addData['wastage_type']) ? $addData['wastage_type'] :1 ),
						'min_wastage'   	   => (!empty($addData['min_wastage']) ? $addData['min_wastage'] :1 ),
						'max_wastage'   	   => (!empty($addData['max_wastage']) ? $addData['max_wastage'] :1 ),
						'other_materials'      => (!empty($addData['other_materials']) ? $addData['other_materials'] :0 ),
						'gift_applicable'      => (!empty($addData['gift_applicable']) ? $addData['gift_applicable'] :0 ),
						'has_stone'		       => (!empty($addData['has_stone']) ? $addData['has_stone'] :0 ),
						'has_hook'		       => (!empty($addData['has_hook']) ? $addData['has_hook'] :0 ),
						'has_screw'		       => (!empty($addData['has_screw']) ? $addData['has_screw'] :0 ),
						'has_fixed_price'	   => (!empty($addData['has_fixed_price']) ? $addData['has_fixed_price'] :0 ),
						'metal_type'		   => (!empty($addData['metal_type']) ? $addData['metal_type'] :NULL ),
						'product_short_code'   => (!empty($addData['product_short_code']) ? $addData['product_short_code'] :NULL ),
						'product_name'		   => (!empty($addData['product_name']) ? strtoupper($addData['product_name']) :NULL ),
						'has_size'		       => (!empty($addData['has_size']) ? $addData['has_size'] :0 ),
						'less_stone_wt'		   => (!empty($addData['less_stone_wt']) ? $addData['less_stone_wt'] :0 ),
						'tag_split'		       => (!empty($addData['tag_split']) ? $addData['tag_split'] :0),
						'tag_merge'		       => (!empty($addData['tag_merge']) ? $addData['tag_merge'] :0 ),
						'tag_type'		       => (!empty($addData['tag_type']) ? $addData['tag_type'] :0 ),
						'other_charges'		   => (!empty($addData['other_charges']) ? $addData['other_charges'] :0 ),
						'net_wt'		       => (!empty($addData['net_wt']) ? $addData['net_wt'] :0 ),
						'stock_report'		   => (!empty($addData['stock_report']) ? $addData['stock_report'] :0 ),
						'central_exces_duty'   => (!empty($addData['central_exces_duty']) ? $addData['central_exces_duty'] :0 ),
						'no_of_pieces'		   => (!empty($addData['no_of_pieces']) ? $addData['no_of_pieces'] :NULL ),
						'rfid_required'		   => (!empty($addData['rfid_required']) ? $addData['rfid_required'] :0 ),
						'rfid_in_stock'		   => (!empty($addData['rfid_in_stock']) ? $addData['rfid_in_stock'] :0 ),
						'hallmark'		       => (!empty($addData['hallmark']) ? $addData['hallmark'] :0 ),
						'counter'		       => (!empty($addData['counter']) ? $addData['counter'] :1 ),
						'stone_board_rate_cal' => (!empty($addData['stone_board_rate_cal']) ? $addData['stone_board_rate_cal'] :0 ),
						'calculation_based_on' => (!empty($addData['calculation_based_on']) ? $addData['calculation_based_on'] :NULL ),
						'sales_markup'		   => (!empty($addData['sales_markup']) ? $addData['sales_markup'] :0 ),
						'max_markup_per_for_rateitems'		       => (!empty($addData['max_markup_per_for_rateitems']) ? $addData['max_markup_per_for_rateitems'] :NULL ),
						'no_of_tags_to_print'		               => (!empty($addData['no_of_tags_to_print']) ? $addData['no_of_tags_to_print'] :NULL ),
						'tax_group_id'		                       => (!empty($addData['tax_group_id']) ? $addData['tax_group_id'] :NULL ),
						'product_status'		                   => 1,
						'created_time'                             => date("Y-m-d H:i:s"),
						'create_by'                                => $this->session->userdata('uid'),
						'image'                 =>  (!empty($addData['image']) ? $addData['image'] :NULL ),
						'tax_type'	           => (!empty($addData['tax_type']) ? $addData['tax_type'] : NULL),
						'display_purity'       => (isset($addData['display_purity']) ? $addData['display_purity'] :1 ),
					);
				//	print_r($data);exit;
	 				$this->db->trans_begin();
					$id_karigar=$this->$model->insertData($data,'ret_product_master');
					
					if(isset($_FILES['product']['name']))	
					{
						if($id_karigar>0)
						{
							$this->set_image_ret($id_karigar);

							foreach($addData['charges']['id'] as $key => $charge)
							{
								$charge_data = array(
												'prod_id'   		=>	$id_karigar,
												'charge_id'      	=>	$addData['charges']['id'][$key],
												'charge_value'   	=>	$addData['charges']['value'][$key],
												'created_on'   	 	=> 	date("Y-m-d H:i:s"),
												'created_by'     	=> 	$this->session->userdata('uid')
											);

								$this->$model->insertData($charge_data,'ret_product_charges');
							}
						}
					}
					
					if($this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
						$this->session->set_flashdata('chit_alert',array('message'=>'New Product added successfully','class'=>'success','title'=>'Add Product'));
						//echo 1;
					}
					else
					{
						$this->db->trans_rollback();						 	
						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Add Product'));
						//echo 0;
					}
					redirect('admin_ret_catalog/ret_product/list');
					break;
			case "edit":
						$data['product'] = $this->$model->get_ret_product($id);
						$data['charges_list'] = $this->$model->get_charges_list();
						$data['charges'] = $this->$model->get_product_charges($id);
						//$data['charges']
						//echo "<pre>";print_r($data);exit;
						$data['main_content'] = "master/ret_product/form" ;
						$this->load->view('layout/template', $data);
						break; 
			case 'delete':
						 $this->db->trans_begin();
						 $this->$model->deleteData('pro_id',$id,'ret_product_master');
						 $this->$model->deleteData('prod_id',$id,'ret_product_charges');
				           if( $this->db->trans_status()===TRUE)
						    {
						    	  $this->db->trans_commit();
								  $this->session->set_flashdata('chit_alert', array('message' => 'product deleted successfully','class' => 'success','title'=>'Delete product'));	  
							}			  
						   else
						    {
							 $this->db->trans_rollback();
							 $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete product'));
						    }
						 redirect('admin_ret_catalog/ret_product/list');	
						 break;
					
			case "update":
				    $updData = $_POST['product']; 
				    //echo "<pre>"; print_r($updData); echo "</pre>"; exit;
		 			$data=array(
						'cat_id'	           => (!empty($updData['cat_id']) ? $updData['cat_id'] :NULL ),
						'tgrp_id'	           => (!empty($updData['tgrp_id']) ? $updData['tgrp_id'] :NULL ),
						'id_section'	       => (!empty($updData['id_section']) ? $updData['id_section'] :NULL ),
						'hsn_code'             => (!empty($updData['hsn_code']) ? $updData['hsn_code'] :NULL ),
						'stock_type'           => (!empty($updData['stock_type']) ? $updData['stock_type'] :1 ),
						'sales_mode'   		   => (!empty($updData['sales_mode']) ? $updData['sales_mode'] :1 ),
						'purchase_mode'   	   => (!empty($updData['purchase_mode']) ? $updData['purchase_mode'] :1 ),
						'wastage_type'   	   => (!empty($updData['wastage_type']) ? $updData['wastage_type'] :1 ),
						'min_wastage'   	   => (!empty($updData['min_wastage']) ? $updData['min_wastage'] :1 ),
						'max_wastage'   	   => (!empty($updData['max_wastage']) ? $updData['max_wastage'] :1 ),
						'other_materials'      => (!empty($updData['other_materials']) ? $updData['other_materials'] :0 ),
						'gift_applicable'      => (!empty($updData['gift_applicable']) ? $updData['gift_applicable'] :0 ),
						'has_stone'		       => (!empty($updData['has_stone']) ? $updData['has_stone'] :0 ),
						'has_hook'		       => (!empty($updData['has_hook']) ? $updData['has_hook'] :0 ),
						'has_screw'		       => (!empty($updData['has_screw']) ? $updData['has_screw'] :0 ),
						'has_fixed_price'	   => (!empty($updData['has_fixed_price']) ? $updData['has_fixed_price'] :0 ),
						'metal_type'		   => (!empty($updData['metal_type']) ? $updData['metal_type'] :NULL ),
						'product_short_code'   => (!empty($updData['product_short_code']) ? $updData['product_short_code'] :NULL ),
						'product_name'		   => (!empty($updData['product_name']) ? strtoupper($updData['product_name']) :NULL ),
						'has_size'		       => (!empty($updData['has_size']) ? $updData['has_size'] :0 ),
						'less_stone_wt'		   => (!empty($updData['less_stone_wt']) ? $updData['less_stone_wt'] :0 ),
						'tag_split'		       => (!empty($updData['tag_split']) ? $updData['tag_split'] :0),
						'tag_merge'		       => (!empty($updData['tag_merge']) ? $updData['tag_merge'] :0 ),
						'tag_type'		       => (!empty($updData['tag_type']) ? $updData['tag_type'] :0 ),
						'other_charges'		   => (!empty($updData['other_charges']) ? $updData['other_charges'] :0 ),
						'net_wt'		       => (!empty($updData['net_wt']) ? $updData['net_wt'] :0 ),
						'stock_report'		   => (!empty($updData['stock_report']) ? $updData['stock_report'] :0 ),
						'central_exces_duty'   => (!empty($updData['central_exces_duty']) ? $updData['central_exces_duty'] :0 ),
						'no_of_pieces'		   => (!empty($updData['no_of_pieces']) ? $updData['no_of_pieces'] :NULL ),
						'rfid_required'		   => (!empty($updData['rfid_required']) ? $updData['rfid_required'] :0 ),
						'rfid_in_stock'		   => (!empty($updData['rfid_in_stock']) ? $updData['rfid_in_stock'] :0 ),
						'hallmark'		       => (!empty($updData['hallmark']) ? $updData['hallmark'] :0 ),
						'counter'		       => (!empty($updData['counter']) ? $updData['counter'] :1 ),
						'stone_board_rate_cal' => (!empty($updData['stone_board_rate_cal']) ? $updData['stone_board_rate_cal'] :0 ),
						'calculation_based_on' => (!empty($updData['calculation_based_on']) ? $updData['calculation_based_on'] :NULL ),
						'sales_markup'		   => (!empty($updData['sales_markup']) ? $updData['sales_markup'] :0 ),
						'max_markup_per_for_rateitems'		=> (!empty($updData['max_markup_per_for_rateitems']) ? $updData['max_markup_per_for_rateitems'] :NULL ),
						'no_of_tags_to_print'		        => (!empty($updData['no_of_tags_to_print']) ? $updData['no_of_tags_to_print'] :NULL ),
						'tax_group_id'		    => (!empty($updData['tax_group_id']) ? $updData['tax_group_id'] :NULL ),
						'product_status'		=> (!empty($updData['product_status']) ? $updData['product_status'] :NULL ),
						'tax_type'	           => (!empty($updData['tax_type']) ? $updData['tax_type'] : NULL),
						'display_purity'       => (isset($updData['display_purity']) ? $updData['display_purity'] :1 ),
						'updated_time'	        => date("Y-m-d H:i:s"),
						'updated_by'            => $this->session->userdata('uid'),
						'image'                 => (!empty($updData['image']) && $updData['image']!=''? $updData['image']:NULL)
					);
	 			
	 			        $this->db->trans_begin();
						$prod=$this->$model->updateData($data,'pro_id',$id,'ret_product_master'); 

						if($prod>0)
						{
							if(isset($_FILES['product']['name']['ret_product_img']) && $_FILES['product']['name']['ret_product_img'] !='')	
							{
								$this->set_image_ret($prod);
							}

							$this->$model->deleteData('prod_id',$prod,'ret_product_charges');

							foreach($updData['charges']['id'] as $key => $charge)
							{
								$charge_data = array(
												'prod_id'   		=>	$prod,
												'charge_id'      	=>	$updData['charges']['id'][$key],
												'charge_value'   	=>	$updData['charges']['value'][$key],
												'created_on'   	 	=> 	date("Y-m-d H:i:s"),
												'created_by'     	=> 	$this->session->userdata('uid')
											);

								$this->$model->insertData($charge_data,'ret_product_charges');
							}

						}
						
			            if($this->db->trans_status()===TRUE)
			             {
						 	$this->db->trans_commit();
						 	$this->session->set_flashdata('chit_alert',array('message'=>'Product record modified successfully','class'=>'success','title'=>'Edit Product'));
						 	//echo 1;
						 }
						 else
						 {
						 	$this->db->trans_rollback();						 	
						 	$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Edit Product'));
						 	//echo 0;
						 }
						 redirect('admin_ret_catalog/ret_product/list');
				    break;
			case 'update_status':
			
						$data = array('product_status' => $status,
						'updated_time'	  => date("Y-m-d H:i:s"),
					    'updated_by'      => $this->session->userdata('uid'));
						$model=self::CAT_MODEL;
						$updstatus = $this->$model->updateData($data,'pro_id',$id,'ret_product_master');
						if($updstatus)
						{
							$this->session->set_flashdata('chit_alert',array('message'=>'Product status updated as '.($status==1 ? 'Active' : 'In Active').' successfully.','class'=>'success','title'=>'Product Status'));
							echo 1;
						}	
						else
						{
							$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Product Status'));
							echo 0;
						}	
						redirect('admin_ret_catalog/ret_product/list');
						break;
			case 'active_list': 
					  	$data = $this->$model->getActiveProduct();	  
						echo json_encode($data);
					    break;
			case 'list':
						$data['main_content'] = "master/ret_product/list" ;
						$this->load->view('layout/template', $data);
					    break;
			case 'active_metal':
						$data = $this->$model->getActiveMetal();	  
						echo json_encode($data);
					    break;
			default:
						$SETT_MOD = self::SETT_MOD;
						$range['from_date']  = $this->input->post('from_date');
						$range['to_date']  = $this->input->post('to_date');
					  	$product = $this->$model->ajax_get_retProduct($range['from_date'],$range['to_date']);	 
					  	$access = $this->$SETT_MOD->get_access('admin_ret_catalog/ret_product/list');
				        $data = array(
				        					'product' =>$product,
											'access'=>$access
				        			 );  
						echo json_encode($data);
		}
	}
	
	public function bulkprodupdated($type="",$id="",$status="",$tax_group_id=""){
		$model=self::CAT_MODEL;
		switch($type)
		{
			case "update":
					$this->db->trans_begin();
					if(isset($_POST['product_ids'])){
						foreach ($_POST['product_ids'] as $prod_id){
							if(isset($_POST['status'])){ 
								$data['product_status'] = $_POST['status'];
							}
							if(!empty($_POST['tax_grp'])){
								$data['tax_group_id'] = $_POST['tax_grp'];
							}
							$data['updated_time'] = date("Y-m-d H:i:s");
							$data['updated_by'] = $this->session->userdata('uid');
							
							$this->$model->updateData($data,'pro_id',$prod_id,'ret_product_master');
						}  
					}   
					if($this->db->trans_status()===TRUE)
					 {
						$this->db->trans_commit();
						$result = array('message'=>'Bulk Product record modified successfully','class'=>'success','title'=>'Edit Bulk Product');
					 }
					 else
					 {
						$this->db->trans_rollback();						 	
						$result = array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Edit Bulk Product');
						 }
						 return $result;
				    break;
			case 'list':
						$data['main_content'] = "master/ret_product/bulk_prod_upd" ;
						$this->load->view('layout/template', $data);
					    break;
			default:
						$SETT_MOD = self::SETT_MOD;
						$pro_id             = $this->input->post('pro_id');
						$tax_group_id       = $this->input->post('tax_group_id');
						$product_status     = $this->input->post('product_status');
						$range['from_date'] = $this->input->post('from_date');
						$range['to_date']  	= $this->input->post('to_date');
					  	$product = $this->$model->ajax_get_TaxProd($pro_id,$tax_group_id,$product_status,$range['from_date'],$range['to_date']);	 
					  	$access = $this->$SETT_MOD->get_access('product/list');
				        $data = array(
				        					'product' =>$product,
											'access'=>$access
				        			 ); //print_r($data);exit; 
						echo json_encode($data);
		}
	}
	
	public function ret_sub_product($type="",$id="",$status=""){
		$model=self::CAT_MODEL;
		switch($type)
		{
			case "add":		
						$data['sub_product']=$this->$model->get_empty_subproduct();
						$data['main_content'] = "master/ret_sub_product/form" ;
						$this->load->view('layout/template', $data);
						break;
	 		case "save":
					$addData = $_POST['sub_product'];
	 				$data=array(
						'less_tax'	           => (!empty($addData['less_tax']) ? $addData['less_tax'] :0 ),
						'wastage_billing'      => (!empty($addData['wastage_billing']) ? $addData['wastage_billing'] :0 ),
						'stock_type'           => (!empty($addData['stock_type']) ? $addData['stock_type'] :1 ),
						'sales_mode'   		   => (!empty($addData['sales_mode']) ? $addData['sales_mode'] :1 ),
						'wastage_type'   	   => (!empty($addData['wastage_type']) ? $addData['wastage_type'] :1 ),
						'other_materials'      => (!empty($addData['other_materials']) ? $addData['other_materials'] :0 ),
						'has_stone'		       => (!empty($addData['has_stone']) ? $addData['has_stone'] :0 ),
						'metal_type'		   => (!empty($addData['metal_type']) ? $addData['metal_type'] :0 ),
						'sub_pro_code'         => (!empty($addData['sub_pro_code']) ? $addData['sub_pro_code'] :NULL ),
						'sub_pro_name'		   => (!empty($addData['sub_pro_name']) ? $addData['sub_pro_name'] :NULL ),
						'has_size'		       => (!empty($addData['has_size']) ? $addData['has_size'] :0 ),
						'less_stone_wt'		   => (!empty($addData['less_stone_wt']) ? $addData['less_stone_wt'] :0 ),
						'tag_split'		       => (!empty($addData['tag_split']) ? $addData['tag_split'] :0 ),
						'tag_merge'		       => (!empty($addData['tag_merge']) ? $addData['tag_merge'] :0 ),
						'tag_type'		       => (!empty($addData['tag_type']) ? $addData['tag_type'] :0 ),
						'other_charges'		   => (!empty($addData['other_charges']) ? $addData['other_charges'] :0 ),
						'net_wt'		       => (!empty($addData['net_wt']) ? $addData['net_wt'] :0 ),
						'stock_report'		   => (!empty($addData['stock_report']) ? $addData['stock_report'] :0 ),
						'central_exces_duty'   => (!empty($addData['central_exces_duty']) ? $addData['central_exces_duty'] :0 ),
						'no_of_pieces'		   => (!empty($addData['no_of_pieces']) ? $addData['no_of_pieces'] :NULL ),
						'rfid_required'		   => (!empty($addData['rfid_required']) ? $addData['rfid_required'] :0 ),
						'rfid_in_stock'		   => (!empty($addData['rfid_in_stock']) ? $addData['rfid_in_stock'] :0 ),
						'hallmark'		       => (!empty($addData['hallmark']) ? $addData['hallmark'] :0 ),
						'counter'		       => (!empty($addData['counter']) ? $addData['counter'] :1 ),
						'stone_board_rate_cal' => (!empty($addData['stone_board_rate_cal']) ? $addData['stone_board_rate_cal'] :0 ),
						'calculation_based_on' => (!empty($addData['calculation_based_on']) ? $addData['calculation_based_on'] :NULL ),
						'tax_group_id'		                       => (!empty($addData['tax_group_id']) ? $addData['tax_group_id'] :NULL ),
						'sub_pro_status'		                   => 1,
						'created_time'                             => date("Y-m-d H:i:s"),
						'create_by'                                => $this->session->userdata('uid')
					);
	 				$this->db->trans_begin();
					$id_karigar=$this->$model->insertData($data,'ret_sub_product_master');
					if($this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
						$this->session->set_flashdata('chit_alert',array('message'=>'New SubProduct added successfully','class'=>'success','title'=>'Add SubProduct'));
						echo 1;
					}
					else
					{
						$this->db->trans_rollback();						 	
						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Add SubProduct'));
						echo 0;
					}
					redirect('admin_ret_catalog/ret_sub_product/list');
					break;
			case "edit":
						$data['sub_product'] = $this->$model->get_ret_subproduct($id);
						$data['main_content'] = "master/ret_sub_product/form" ;
						$this->load->view('layout/template', $data);
						break; 
			case 'delete':
						 $this->db->trans_begin();
						 $this->$model->deleteData('sub_pro_id',$id,'ret_sub_product_master');
				           if( $this->db->trans_status()===TRUE)
						    {
						    	  $this->db->trans_commit();
								  $this->session->set_flashdata('chit_alert', array('message' => 'SubProduct deleted successfully','class' => 'success','title'=>'Delete Subproduct'));	  
							}			  
						   else
						    {
							 $this->db->trans_rollback();
							 $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete Subproduct'));
						    }
						 redirect('admin_ret_catalog/ret_sub_product/list');	
						 break;
					
			case "update":
				    $updData = $_POST['sub_product'];
		 			$data=array(
						'less_tax'	           => (!empty($updData['less_tax']) ? $updData['less_tax'] :0 ),
						'wastage_billing'      => (!empty($updData['wastage_billing']) ? $updData['wastage_billing'] :0 ),
						'stock_type'           => (!empty($updData['stock_type']) ? $updData['stock_type'] :1 ),
						'sales_mode'   		   => (!empty($updData['sales_mode']) ? $updData['sales_mode'] :1 ),
						'wastage_type'   	   => (!empty($updData['wastage_type']) ? $updData['wastage_type'] :1 ),
						'other_materials'      => (!empty($updData['other_materials']) ? $updData['other_materials'] :0 ),
						'has_stone'		       => (!empty($updData['has_stone']) ? $updData['has_stone'] :0 ),
						'metal_type'		   => (!empty($updData['metal_type']) ? $updData['metal_type'] :0 ),
						'sub_pro_code'         => (!empty($updData['sub_pro_code']) ? $updData['sub_pro_code'] :NULL ),
						'sub_pro_name'		   => (!empty($updData['sub_pro_name']) ? $updData['sub_pro_name'] :NULL ),
						'has_size'		       => (!empty($updData['has_size']) ? $updData['has_size'] :0 ),
						'less_stone_wt'		   => (!empty($updData['less_stone_wt']) ? $updData['less_stone_wt'] :0 ),
						'tag_split'		       => (!empty($updData['tag_split']) ? $updData['tag_split'] :0 ),
						'tag_merge'		       => (!empty($updData['tag_merge']) ? $updData['tag_merge'] :0 ),
						'tag_type'		       => (!empty($updData['tag_type']) ? $updData['tag_type'] :0 ),
						'other_charges'		   => (!empty($updData['other_charges']) ? $updData['other_charges'] :0 ),
						'net_wt'		       => (!empty($updData['net_wt']) ? $updData['net_wt'] :0 ),
						'stock_report'		   => (!empty($updData['stock_report']) ? $updData['stock_report'] :0 ),
						'central_exces_duty'   => (!empty($updData['central_exces_duty']) ? $updData['central_exces_duty'] :0 ),
						'no_of_pieces'		   => (!empty($updData['no_of_pieces']) ? $updData['no_of_pieces'] :NULL ),
						'rfid_required'		   => (!empty($updData['rfid_required']) ? $updData['rfid_required'] :0 ),
						'rfid_in_stock'		   => (!empty($updData['rfid_in_stock']) ? $updData['rfid_in_stock'] :0 ),
						'hallmark'		       => (!empty($updData['hallmark']) ? $updData['hallmark'] :0 ),
						'counter'		       => (!empty($updData['counter']) ? $updData['counter'] :1 ),
						'stone_board_rate_cal' => (!empty($updData['stone_board_rate_cal']) ? $updData['stone_board_rate_cal'] :0 ),
						'calculation_based_on' => (!empty($updData['calculation_based_on']) ? $updData['calculation_based_on'] :NULL ),
						'tax_group_id'		       => (!empty($updData['tax_group_id']) ? $updData['tax_group_id'] :NULL ),
						'sub_pro_status'		       => (!empty($updData['sub_pro_status']) ? $updData['sub_pro_status'] :NULL ),
						'updated_time'	           => date("Y-m-d H:i:s"),
						'updated_by'            => $this->session->userdata('uid')
					);
	 			
	 			        $this->db->trans_begin();
						$this->$model->updateData($data,'sub_pro_id',$id,'ret_sub_product_master'); 
						
			            if($this->db->trans_status()===TRUE)
			             {
						 	$this->db->trans_commit();
						 	$this->session->set_flashdata('chit_alert',array('message'=>'SubProduct record modified successfully','class'=>'success','title'=>'Edit SubProduct'));
						 	echo 1;
						 }
						 else
						 {
						 	$this->db->trans_rollback();						 	
						 	$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Edit Product'));
						 	echo 0;
						 }
						 redirect('admin_ret_catalog/ret_sub_product/list');
				    break;
			case 'update_status':
			
						$data = array('sub_pro_status' => $status,
						'updated_time'	  => date("Y-m-d H:i:s"),
					    'updated_by'      => $this->session->userdata('uid'));
						$model=self::CAT_MODEL;
						$updstatus = $this->$model->updateData($data,'sub_pro_id',$id,'ret_sub_product_master');
						if($updstatus)
						{
							$this->session->set_flashdata('chit_alert',array('message'=>'SubProduct status updated as '.($status==1 ? 'Active' : 'In Active').' successfully.','class'=>'success','title'=>'SubProduct Status'));
							echo 1;
						}	
						else
						{
							$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'SubProduct Status'));
							echo 0;
						}	
						redirect('admin_ret_catalog/ret_sub_product/list');
						break;
			case 'active_list': 
					  	$data = $this->$model->getActiveSubProducts();	  
						echo json_encode($data);
					    break;
			case 'list':
						$data['main_content'] = "master/ret_sub_product/list" ;
						$this->load->view('layout/template', $data);
					    break;
			default:
						$SETT_MOD = self::SETT_MOD;
						$range['from_date']  = $this->input->post('from_date');
						$range['to_date']  = $this->input->post('to_date');
					  	$sub_product = $this->$model->ajax_get_retSubProduct($range['from_date'],$range['to_date']);	 
					  	$access = $this->$SETT_MOD->get_access('admin_ret_catalog/ret_sub_product/list');
				        $data = array(
				        					'sub_product' =>$sub_product,
											'access'=>$access
				        			 );  
						echo json_encode($data);
		}
	}
	
	public function tgrp($type="",$id="",$status=""){
		$model=self::CAT_MODEL;
		switch($type)
		{
			case "add":
					$data['tgrp']         = $this->$model->get_empty_tgrp();
					$data['tgi']          = $this->$model->get_empty_tgi();
					$data['main_content'] = "master/tax/tax group/form";
					$this->load->view('layout/template', $data);
					break;
			case 'list':
					$data['main_content'] = "master/tax/tax group/list" ;
					$this->load->view('layout/template', $data);
					break;
			case "save":
				$tgrp    = $this->input->post('tgrp');
				$tgitem  = $this->input->post('tgi');
				
				$data['tgrp'] = array( 
					'tgrp_name' 	=>  (isset($tgrp['tgrp_name'])?$tgrp['tgrp_name']: NULL), 
					'tgrp_status' 	=>  (isset($tgrp['tgrp_status'])?$tgrp['tgrp_status']: 1),
					'created_by'	=>  ($this->session->userdata('uid')), 
					'created_time' 	=>   date("Y-m-d H:i:s")
					);
					$this->db->trans_begin();
	 				$tgrp_id = $this->$model->insertData($data['tgrp'],'ret_taxgroupmaster');
				     		if($tgrp_id>0){
							foreach($tgitem as $tgi){
							$data['tgi'] = array(
				     		    'tgi_tgrpcode'	=>  $tgrp_id, 
								'tgi_taxcode' 	=>  (isset($tgi['tax_id'])?$tgi['tax_id']: NULL), 
								'tgi_calculation'=> (isset($tgi['tgi_calculation'])?$tgi['tgi_calculation']: NULL),
								'tgi_type' 	    =>  (isset($tgi['tgi_type'])?$tgi['tgi_type']: NULL)
				     			); 
								$result = $this->$model->insertData($data['tgi'],'ret_taxgroupitems');
							}
							}
					if($this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
						$this->session->set_flashdata('chit_alert',array('message'=>'New Save added successfully','class'=>'success','title'=>'Save Tax Group'));
						redirect('admin_ret_catalog/tgrp/list');
					}
					else
					{
						$this->db->trans_rollback();						 	
						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Save Tax Group'));
						redirect('admin_ret_catalog/tgrp/list');
					}	
					break;
			case 'edit':
						$tgrp = $this->$model->get_tgrp($id);
						$data['tgrp'] = array( 
						'tgrp_id'       =>  (isset($tgrp['tgrp_id'])?$tgrp['tgrp_id']:NULL),
						'tgrp_name' 	=>  (isset($tgrp['tgrp_name'])?$tgrp['tgrp_name']: NULL), 
						'tgrp_status' 	=>  (isset($tgrp['tgrp_status'])?$tgrp['tgrp_status']: 1),
						'modified_by'	=>  ($this->session->userdata('uid')), 
						'modified_time' =>   date("Y-m-d H:i:s")
						);
					
						$data['main_content'] = "master/tax/tax group/form" ;
						$this->load->view('layout/template', $data);
				        break;	
			case "update":
				$tgrp   = $this->input->post('tgrp');
				$tgitem  = $this->input->post('tgi');
				$data= array( 
				     'tgrp_id'      =>  (isset($tgrp['tgrp_id'])?$tgrp['tgrp_id']:NULL),
					'tgrp_name' 	=>  (isset($tgrp['tgrp_name'])?$tgrp['tgrp_name']: NULL), 
					'tgrp_status' 	=>  (isset($tgrp['tgrp_status'])?$tgrp['tgrp_status']: 1),
					'modified_by'	=>  ($this->session->userdata('uid')), 
					'modified_time' =>   date("Y-m-d H:i:s")
					);
					$this->db->trans_begin();
					$tgrp_id=$this->$model->updateData($data,'tgrp_id',$id,'ret_taxgroupmaster');
					if($tgrp_id > 0){
						
							foreach($tgitem as $tgi){
				     		$data['tgi'] = array(
				     		   'tgi_tgrpcode'	 =>  $tgrp_id, 
								'tgi_taxcode' 	 => (isset($tgi['tax_id'])?$tgi['tax_id']: NULL), 
								'tgi_calculation'=> (isset($tgi['tgi_calculation'])?$tgi['tgi_calculation']: NULL),
								'tgi_type' 	     => (isset($tgi['tgi_type'])?$tgi['tgi_type']: NULL)
				     			);
				     			$result = $this->$model->insertData($data['tgi'],'ret_taxgroupitems');
								
					}
					
					}
					if($this->db->trans_status()===TRUE)
			             {
						 	$this->db->trans_commit();
						 	
						 	$this->session->set_flashdata('chit_alert',array('message'=>'Tax Group record modified successfully','class'=>'success','title'=>'Edit Tax Group'));
						 	redirect('admin_ret_catalog/tgrp/list');
						 }
						 else
						 {
						 	$this->db->trans_rollback();						 	
						 	$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Edit Tax Group'));
						 	echo 0;
						 }
					break;
			case 'delete':
						 $this->db->trans_begin();
						 $this->$model->deleteData('tgrp_id',$id,'ret_taxgroupmaster');
				           if( $this->db->trans_status()===TRUE)
						    {
						      $this->db->trans_commit();
							  $this->session->set_flashdata('chit_alert', array('message' => 'Tax Group deleted successfully','class' => 'success','title'=>'Delete Tax Group'));	
							  //echo 1;
							}			  
						   else
						   {
							 $this->db->trans_rollback();
							 $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete Tax Group'));
							// echo 0;
						   }
						 	redirect('admin_ret_catalog/tgrp/list');
				break;
			case 'update_status':
			
						$data = array('tgrp_status' => $status,'modified_time' => date("Y-m-d H:i:s"),'modified_by' => $this->session->userdata('uid'));
						//print_r($data);exit;
						$updstatus = $this->$model->updateData($data,'tgrp_id',$id,'ret_taxgroupmaster');
						if($updstatus)
						{
							$this->session->set_flashdata('chit_alert',array('message'=>'Tax Group status updated as '.($status == 1 ? 'Active' : 'Inactive').' successfully.','class'=>'success','title'=>'Tax  Status'));		
							 redirect('admin_ret_catalog/tgrp/list');
						}	
						else
						{
							$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Tax Group Status'));
							echo 0;
						}	
					  // redirect('admin_ret_catalog/tgrp/list');
					   break;
			case 'active_tgrp': 
					  	$data = $this->$model->getActivetgrp();	  
						echo json_encode($data);
					    break;
			case 'active_tgi': 
					  	$data = $this->$model->get_tgi();	  
						echo json_encode($data);
					    break;
			default:
						$SETT_MOD 	= self::SETT_MOD;
						$range['from_date']	= $this->input->post('from_date');
				        $range['to_date']	= $this->input->post('to_date');
					  	$tgrp 		= $this->$model->ajax_gettgrp($range['from_date'],$range['to_date']);	 
					  	$access 	= $this->$SETT_MOD->get_access('admin_ret_catalog/tgrp/list');
				        $data 		= array(
				        					'tgrp'   =>$tgrp,
											'access'=>$access
				        				);  
						echo json_encode($data);
		}
	}
	
	public function metal($type="",$id=""){
		$model=self::CAT_MODEL;
		switch($type)
		{
			case "add":
	 				$data=array(
						'metal'  	  => $this->input->post("metal"),
						'tgrp_id'   => $this->input->post("tgrp_id"),
						'metal_code'  => $this->input->post("metal_code"),
						'metal_status'=> $this->input->post("metal_status"),
						'created_on'  => date("Y-m-d H:i:s"),
						'created_by'  => $this->session->userdata('uid')
					);
	 				$this->db->trans_begin();
	 				$this->$model->insertData($data,'metal');
					if($this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
						$result = array('message'=>'New Metal added successfully!..','class'=>'success','title'=>'Add Metal : ');
			
					}
					else
					{
						$this->db->trans_rollback();						 	
						$result = array('message'=>'Unable to proceed the requested process..','class'=>'danger','title'=>'Add Metal : ');
	
					}
					echo json_encode($result);
					break;
			case "edit":
					$data['metal'] = $this->$model->get_metals($id);
					echo json_encode($data['metal']);
					break; 
			case 'delete':
						 $this->db->trans_begin();
						 $this->$model->deleteData('id_metal',$id,'metal');
				           if( $this->db->trans_status()===TRUE)
						    {
						      $this->db->trans_commit();
							  $this->session->set_flashdata('chit_alert', array('message' => 'Metal deleted successfully','class' => 'success','title'=>'Delete Metal'));	
							  echo 1;
							}			  
						   else
						   {
							 $this->db->trans_rollback();
							 $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete Metal'));
							 echo 0;
						   }
						 	redirect('admin_ret_catalog/metal/list');
				break;
					
			case "update":
					$data=array(
						'metal'	          => $this->input->post("metal"),
						'tgrp_id'   => $this->input->post("tgrp_id"),
						'metal_code'      => $this->input->post("metal_code"),
						'metal_status'  => $this->input->post("metal_status"),
						'updated_on'	  => date("Y-m-d H:i:s"),
						'updated_by'      => $this->session->userdata('uid')
						);
	 			 $this->db->trans_begin();
			            
			          $this->$model->updateData($data,'id_metal',$id,'metal');
			            if($this->db->trans_status()===TRUE)
			             {
						 	$this->db->trans_commit();
						 	
						 	$this->session->set_flashdata('chit_alert',array('message'=>'Metal record modified successfully','class'=>'success','title'=>'Edit Metal'));
						 	echo 1;
						 }
						 else
						 {
						 	$this->db->trans_rollback();						 	
						 	$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Edit Metal'));
						 	echo 0;
						 }
						 
				break;
			case 'list':
						$data['main_content'] = "master/metal/list" ;
						$this->load->view('layout/template', $data);
						break;
			case 'update_status':
						$data = array('metal_status' => $status,'updated_on' => date("Y-m-d H:i:s"),'updated_by' => $this->session->userdata('uid'));
						$updstatus = $this->$model->updateData($data,'id_metal',$id,'metal');
						if($updstatus)
						{
							$this->session->set_flashdata('chit_alert',array('message'=>'Metal status updated as '.($status == 1 ? 'Active' : 'Inactive').' successfully.','class'=>'success','title'=>'Metal  Status'));
							 redirect('admin_ret_catalog/metal/list');
						}	
						else
						{
							$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Metal Status'));
							echo 0;
						}	
					   break;
		
			default:
						$SETT_MOD 	= self::SETT_MOD;
				     	$range['from_date']	= $this->input->post('from_date');
				        $range['to_date']	= $this->input->post('to_date');
					  	$metal 		= $this->$model->ajax_getmetal($range['from_date'],$range['to_date']);	 
					  	$access 	= $this->$SETT_MOD->get_access('admin_ret_catalog/metal/list');
				        $data 		= array(
				        					'metal' =>$metal,
											'access'=>$access
				        				);  
						echo json_encode($data);
		}
	}
	
	function removeDesign_img($file="",$id="") {
		$folder =  self::IMG_PATH."designs"."/".$id ;
		$img_name =  $id."_DESIGN.jpg";
		$path = $folder."/".$img_name;
		unset($path);
		$model=self::CAT_MODEL;
		$status = $this->$model->delete_designimage($file);
		if($status){
			echo "Picture removed successfully";
		}      
	}
	
	public function get_hooktype()
	{
		 $model=self::CAT_MODEL;
		 $data = $this->$model->getHooktype();  
	     echo json_encode($data);
	}
	public function get_screwtype()
	{
		$model=self::CAT_MODEL;
		$data = $this->$model->get_screwtype();  
	    echo json_encode($data);
	}
	public function get_productData()
	{
		 $model=self::CAT_MODEL;
		 $id = $this->input->post('pro_id');
		 $data = $this->$model->get_productData($id);  
		 echo json_encode($data);
	}
	
	
	public function ret_design($type="",$id="",$status=""){
		$model=self::CAT_MODEL;
		switch($type)
		{
			case "add":		
						$data['design']=$this->$model->get_empty_design();
						$data['karigar']=$this->$model->getkarigar();
						$data['purity']=$this->$model->getpurity();
						$data['material']=$this->$model->getmaterial();
						$data['stone']=$this->$model->getstone();
						$data['size']=$this->$model->getsize();
						$data['main_content'] = "master/ret_design/form" ;
						$this->load->view('layout/template', $data);
						break;
	 		case "save":
					$addData = $this->input->post('design');
					//echo "<pre>"; print_r($addData);exit;
					$karigar     = [];
					$purity      = [];  
					$size = [];
					$other_materials = [];
					$stone = [];
					if($this->input->post('karigars') != '' ){
						$karigar     = explode(',',$this->input->post('karigars'));
					}
					if($this->input->post('purity') != '' ){
						$purity          = explode(',',$this->input->post('purity'));  
					}
					if($this->input->post('materials') != '' ){
						$other_materials = explode(',',$this->input->post('materials'));
					}
					if($this->input->post('size') != '' ){
						$size            = $this->input->post('size');
					}
					if($this->input->post('stone') != '' ){
						$stone   = $this->input->post('stone');
					} 
					$design_code = $this->$model->genDesignShortCode();
	 				$data['design']=array(
					    'design_status'        => (isset($addData['design_status']) ? $addData['design_status'] :NULL ),
						'design_code'		   => $design_code,
						'design_name'		   => (!empty($addData['design_name']) ? strtoupper($addData['design_name']) :NULL ),
						'product_id'		   => (!empty($addData['product_id']) ? $addData['product_id'] :NULL ),
						'theme'		           => (!empty($addData['theme']) ? $addData['theme'] :NULL ),
						'hook_type'		       => (!empty($addData['hook_type']) ? $addData['hook_type'] :NULL ),
						'screw_type'		   => (!empty($addData['screw_type']) ? $addData['screw_type'] :NULL ),
						'design_for'		   => (!empty($addData['design_for']) ? $addData['design_for'] :NULL ),
						'min_length'		   => (!empty($addData['min_length']) ? $addData['min_length'] :NULL ),
						'max_length'		   => (!empty($addData['max_length']) ? $addData['max_length'] :NULL ),
						'min_width'		       => (!empty($addData['min_width']) ? $addData['min_width'] :NULL ),
						'max_width'            => (!empty($addData['max_width']) ? $addData['max_width'] :NULL ),
						'min_dia'		       => (!empty($addData['min_dia']) ? $addData['min_dia'] :NULL ),
						'max_dia'		       => (!empty($addData['max_dia']) ? $addData['max_dia'] :NULL ),
						'min_weight'		   => (!empty($addData['min_weight']) ? $addData['min_weight'] :NULL ),
						'max_weight'		   => (!empty($addData['max_weight']) ? $addData['max_weight'] :NULL ),
						'fixed_rate'		   => (!empty($addData['fixed_rate']) ? $addData['fixed_rate'] :NULL ),
						'fixed_rate'		   => (!empty($addData['fixed_rate']) ? $addData['fixed_rate'] :NULL ),
						'id_size'               => (!empty($addData['id_size']) ? $addData['id_size'] :NULL ),
						'created_time'         => date("Y-m-d H:i:s"),
						'create_by'            => $this->session->userdata('uid')
					);
	 				$this->db->trans_begin();
					$id=$this->$model->insertData($data['design'],'ret_design_master');
					//print_r($this->db->last_query());exit;
					if($id>0)
					{
						if(sizeof($karigar) > 0){
							foreach($karigar as $k){
								$data['karigar'] = array(
									'karigar_id'    =>  (isset($k)?($k):NULL),
									'design_id'    =>   $id
								);
								$result = $this->$model->insertData($data['karigar'],'ret_design_karigars');
							}
						}
						if(sizeof($purity) > 0){
							foreach($purity as $k){
				     			$data['purity'] = array(
				       			'design_id'		    =>   $id,
				       			'pur_id'			=>  (isset($k)?($k):NULL)
				     			);
								$result = $this->$model->insertData($data['purity'],'ret_design_purity');
			     			}
			     		}	
						if(sizeof($other_materials) > 0){ 
						 	foreach($other_materials as $k){
					     		$data['material'] = array(
					       			'design_id'		    =>   $id,
					       			'material_id'		=>  (isset($k)?($k):NULL)
					     			);
								$result = $this->$model->insertData($data['material'],'ret_design_other_materials');
						 	 }
					 	 }
						 if(sizeof($size) > 0){
							  foreach($size as $det){
					     		$data['size'] = array(
				       			'design_id'		    =>   $id, 
				       			'size'			    =>  (isset($det['size'])?($det['size']):NULL),
								'uom_id'			=>  (isset($det['uom_id'])?($det['uom_id']):NULL)
				     			);
								 $result = $this->$model->insertData($data['size'],'ret_design_sizes');
						 	 }
						 }
						 if(sizeof($stone) > 0){	
							foreach($stone as $det){
				     			$data['stone'] = array(
				       			'design_id'		    =>   $id, 
				       			'stone_id'			=>  (isset($det['stone_id'])?$det['stone_id']:NULL),
								'stone_pcs'			=>  (isset($det['stone_pcs'])?$det['stone_pcs']:NULL)
				     			);
								$result = $this->$model->insertData($data['stone'],'ret_design_stone');
						 	 }
						  }
					}
					if(!empty($_FILES)){
						$img_arr = array();
						 if($id > 0)
						   {
						   	 $folder =  self::IMG_PATH."designs/".$id ;
						   	 
						   	 if (!is_dir($folder)) {
							    mkdir($folder, 0777, TRUE);
							}
							//upload default image 	
							 if($_FILES['default_img']){
										
								 if($_FILES['default_img']['name'])
								   	 {  
								   	 
								   	    $img_name =  $id."_DESIGN". mt_rand(120,1230).".jpg";
							   		    $path = $folder."/".$img_name;
								        $img = $_FILES['default_img']['tmp_name'];
									 	$result = $this->upload_img('image',$path,$img);
									 	  	$data = array(
								 					'image'		 => $img_name,
								 					'id_design'  =>  $id,
								 					'date_add'	 => date("Y-m-d H:i:s"),
								 					'is_default' =>0
											 	);
											$this->$model->insertData($data,'ret_design_images');
										 }
								 }
						//remove default image from array
						 unset($_FILES['default_img']);	
						
						 if(!empty($_FILES)){	
							foreach($_FILES as $file){
								 if($file['name'])
								   	 {  
								   	   // unlink($folder."/".$product['image']); 
								   	    $img_name =  $id."_DESIGN". mt_rand(120,1230).".jpg";
							   		    $path = $folder."/".$img_name;
								        $img = $file['tmp_name'];
									 	$result = $this->upload_img('image',$path,$img);
									 	  if($result){
										 	$img_arr[] = $img_name;
										 }
									 }
								 }
								 }
					   		 }
					   		  if(!empty($img_arr)){
					   		  	 foreach($img_arr as $img){
								 	$data = array(
								 					'image'		 => $img,
								 					'id_design' => $id,
								 					'date_add'	 => date("Y-m-d H:i:s")
								 	);
								 	$this->$model->insertData($data,'ret_design_images');
								 } 	
							   }
						}
					
					if($this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
						$this->session->set_flashdata('chit_alert',array('message'=>'New Design added successfully','class'=>'success','title'=>'Add Design'));
						//echo 1;
					}
					else
					{
						$this->db->trans_rollback();						 	
						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Add Design'));
						//echo 0;
					}
					redirect('admin_ret_catalog/ret_design/list');
					break;
			case "edit":
						$edit     = $this->$model->get_ret_design($id);
						$karigar  = json_encode($this->$model->get_design_karigar($id));
						$material = json_encode($this->$model->get_design_material($id));
						$purity   = json_encode($this->$model->get_design_purity($id));
						$size     = $this->$model->get_design_size($id);
						$data['images'] = $this->$model->get_designimage($id);
						$data['design'] = array( 
						'design_no' 		   => (!empty($edit['design_no'])?$edit['design_no']: NULL), 
						'default_img' 		   => (!empty($edit['default_img'])?$edit['default_img']: NULL), 
						'design_status'        => (!empty($edit['design_status']) ? $edit['design_status'] :NULL ),
						'design_code'		   => (!empty($edit['design_code']) ? $edit['design_code'] :NULL ),
						'design_name'		   => (!empty($edit['design_name']) ? strtoupper($edit['design_name']) :NULL ),
						'product_id'		   => (!empty($edit['product_id']) ? $edit['product_id'] :NULL ),
						'theme'		           => (!empty($edit['theme']) ? $edit['theme'] :NULL ),
						'hook_type'		       => (!empty($edit['hook_type']) ? $edit['hook_type'] :NULL ),
						'screw_type'		   => (!empty($edit['screw_type']) ? $edit['screw_type'] :NULL ),
						'design_for'		   => (!empty($edit['design_for']) ? $edit['design_for'] :NULL ),
						'min_length'		   => (!empty($edit['min_length']) ? $edit['min_length'] :NULL ),
						'max_length'		   => (!empty($edit['max_length']) ? $edit['max_length'] :NULL ),
						'min_width'		       => (!empty($edit['min_width']) ? $edit['min_width'] :NULL ),
						'max_width'            => (!empty($edit['max_width']) ? $edit['max_width'] :NULL ),
						'min_dia'		       => (!empty($edit['min_dia']) ? $edit['min_dia'] :NULL ),
						'max_dia'		       => (!empty($edit['max_dia']) ? $edit['max_dia'] :NULL ),
						'min_weight'		   => (!empty($edit['min_weight']) ? $edit['min_weight'] :NULL ),
						'max_weight'		   => (!empty($edit['max_weight']) ? $edit['max_weight'] :NULL ),
						'fixed_rate'		   => (!empty($edit['fixed_rate']) ? $edit['fixed_rate'] :NULL ),
						'usage_type'           => (!empty($edit['usage_type']) ? $edit['usage_type'] :NULL ),
						'id_size'              => (!empty($edit['id_size']) ? $edit['id_size'] :NULL ),
						'mc_cal_type'          => $edit['mc_cal_type'],
						'mc_cal_value'         => !empty($edit['mc_cal_value']) ? $edit['mc_cal_value'] : 0,
						'wastag_value'         => !empty($edit['wastag_value']) ? $edit['wastag_value'] : 0,
						);
						$data['image']    =array('image'        =>  (!empty($image['image'])?$image['image']: NULL));
						$data['karigar']  =array('karigar_id'   =>  (!empty($karigar)?$karigar:NULL));
						$data['material'] =array('material_id'  =>  (!empty($material)?$material:NULL));
						$data['purity']   =array('pur_id'       =>  (!empty($purity)?$purity:NULL));
						$data['size']     =array('uom_id'       =>  (!empty($size['uom_id'])?$size['uom_id']: NULL));
						//echo "<pre>"; print_r($data);exit;
						$data['main_content'] = "master/ret_design/form";
						$this->load->view('layout/template', $data);
						break; 
			case 'delete':
						  $this->db->trans_begin();
						 $return_data=array();
						 $design_no=$this->input->post('design_no');
						 $this->$model->deleteData('design_no',$design_no,'ret_design_master');
				           if( $this->db->trans_status()===TRUE)
						    {
						    	  $this->db->trans_commit();
						    	  $return_data=array('message' => 'Design deleted successfully','class' => 'success','title'=>'Delete Design');
								  $this->session->set_flashdata('chit_alert', array('message' => 'Design deleted successfully','class' => 'success','title'=>'Delete Design'));	  
							}			  
						   else
						    {
							 $this->db->trans_rollback();
							 $return_data=array('message' =>'Unable to proceed your request','class' => 'success','title'=>'Delete Design');
							 $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete Design'));
						    }
						    echo json_encode($return_data);
						 //redirect('admin_ret_catalog/ret_design/list');
						 break;
					
			case "update":
				    $updData = $this->input->post('design');
				    /*echo "<pre>";
				    print_r($updData);
				    echo "</pre>";exit;*/
					$karigar_ids       = explode(',',$this->input->post('karigars'));
					$purity            = explode(',',$this->input->post('purity'));  
					$other_materials   = explode(',',$this->input->post('materials'));
					$size              = $this->input->post('size');
					$stone             = $this->input->post('stone');
		 		$data['design']=array(
					  'design_status'          => (!empty($updData['design_status']) ? $updData['design_status'] :NULL ),
						//'design_code'		   => (!empty($updData['design_code']) ? $updData['design_code'] :NULL ),
						'design_name'		   => (!empty($updData['design_name']) ? strtoupper($updData['design_name']) :NULL ),
						//'product_id'		   => (!empty($updData['product_id']) ? $updData['product_id'] :NULL ),
						'theme'		           => (!empty($updData['theme']) ? ($updData['theme']!='' ? $updData['theme']:NULL) :NULL ),
						'hook_type'		       => (!empty($updData['hook_type']) ? ($updData['hook_type']!='' ? $updData['hook_type']:NULL) :NULL ),
						'screw_type'		   => (!empty($updData['screw_type']) ? ($updData['screw_type']!='' ? $updData['screw_type']:NULL) :NULL ),
						'design_for'		   => (!empty($updData['design_for']) ? $updData['design_for'] :NULL ),
						'min_length'		   => (!empty($updData['min_length']) ? $updData['min_length'] :NULL ),
						'max_length'		   => (!empty($updData['max_length']) ? $updData['max_length'] :NULL ),
						'min_width'		       => (!empty($updData['min_width']) ? $updData['min_width'] :NULL ),
						'max_width'            => (!empty($updData['max_width']) ? $updData['max_width'] :NULL ),
						'min_dia'		       => (!empty($updData['min_dia']) ? $updData['min_dia'] :NULL ),
						'max_dia'		       => (!empty($updData['max_dia']) ? $updData['max_dia'] :NULL ),
						'min_weight'		   => (!empty($updData['min_weight']) ? $updData['min_weight'] :NULL ),
						'max_weight'		   => (!empty($updData['max_weight']) ? $updData['max_weight'] :NULL ),
						'fixed_rate'		   => (!empty($updData['fixed_rate']) ? $updData['fixed_rate'] :NULL ),
						'usage_type'           => (!empty($updData['usage_type']) ? $updData['usage_type'] :NULL ),
						'id_size'              => (!empty($updData['id_size']) ? $updData['id_size'] :NULL ),
						'updated_time'	       => date("Y-m-d H:i:s"),
						'updated_by'           => $this->session->userdata('uid')
					);
	 			
	 			
	 			        $this->db->trans_begin();
						$id=$this->$model->updateData($data['design'],'design_no',$id,'ret_design_master');
						
			            if($id > 0)
			            {
							$this->$model->deleteData('design_id',$id,'ret_design_karigars');
							if($karigar_ids[0]!='')
							{
							    foreach($karigar_ids as $k)
            					{
            							$karigar = array(
            								'karigar_id'    =>  (isset($k)?($k):NULL),
            							    'design_id'     =>   $id
                                            );
                                   $result = $this->$model->insertData($karigar,'ret_design_karigars');
                                }
							}
							$this->$model->deleteData('design_id',$id,'ret_design_purity');
							if($purity[0]!='')
							{
							    foreach($purity as $k)
        						{
        							$purity = array(
        								'pur_id'			=> (isset($k)?($k):NULL),
        					       	    'design_id'	        => $id
                                        );
                                    $result = $this->$model->insertData($purity,'ret_design_purity');
                               }
							}
							$this->$model->deleteData('design_id',$id,'ret_design_other_materials');
							if($other_materials[0]!='')
							{
							   foreach($other_materials as $k)
        						{
        							$other_material = array(
        								'design_id'		    =>   $id,
        					       		'material_id'		=>  (isset($k)?($k):NULL)
                                        );
                                    $result = $this->$model->insertData($other_material,'ret_design_other_materials');
                               } 
							}
							if($size[0]!='')
							{
							    foreach($size as $det)
    							 {
    					     		$size= array(
    					       			'design_id'		    =>   $id, 
    					       			'size'			    =>  (isset($det['size'])?($det['size']):NULL),
    									'uom_id'			=>  (isset($det['uom_id'])?($det['uom_id']):NULL)
    					     			);
    				     			$result = $this->$model->updateData($size,'design_id',$id,'ret_design_sizes');
    						 	 }   
							}
							if($stone[0]!='')
							{
							    foreach($stone as $det)
                                {
                                    $stone = array(
                                    'design_id'		    =>   $id, 
                                    'stone_id'			=>  (isset($det['stone_id'])?$det['stone_id']:NULL),
                                    'stone_pcs'			=>  (isset($det['stone_pcs'])?$det['stone_pcs']:NULL)
                                    );
                                    $result = $this->$model->updateData($stone,'design_id',$id,'ret_design_stone');
                                }   
							}
                            
						} 
						if(!empty($_FILES)){
						$img_arr = array();
						
						 if($id > 0)
						   {
						   	 $folder =  self::IMG_PATH."designs/".$id ;
						   	if (!is_dir($folder)) {
							    mkdir($folder, 0777, TRUE);
						   }
							//upload default image 	
							 if($_FILES['default_img']){
										
								 if($_FILES['default_img']['name'])
								   	 {  
								   	    $img_name =  $id."_DESIGN". mt_rand(120,1230).".jpg";
							   		    $path = $folder."/".$img_name;
								        $img = $_FILES['default_img']['tmp_name'];
									 	$result = $this->upload_img('image',$path,$img);
									 	  	$data = array(
								 					'image'		 => $img_name,
								 					'id_design' => $id,
								 					'date_add'	 => date("Y-m-d H:i:s"),
								 					'is_default' =>0
											 	);
											$this->$model->insertData($data,'ret_design_images');
										 }
								 }
						//remove default image from array
						 unset($_FILES['default_img']);	
						 //check array and upload remaining images
						 if(!empty($_FILES)){	
							foreach($_FILES as $file){
								 if($file['name'])
								   	 {  
								   	    $img_name =  $id."_DESIGN". mt_rand(120,1230).".jpg";
							   		    $path = $folder."/".$img_name;
								        $img = $file['tmp_name'];
									 	$result = $this->upload_img('image',$path,$img);
									 	  if($result){
										 	$img_arr[] = $img_name;
										 }
									 }
								 }
								 }
					   		 }
					   		  if(!empty($img_arr)){
					   		  	 foreach($img_arr as $img){
								 	$data = array(
								 					'image'		 => $img,
								 					'id_design' => $id,
								 					'date_add'	 => date("Y-m-d H:i:s")
								 	);
								 	$this->$model->insertData($data,'ret_design_images');
								 } 	
							   }
						}
			            if($this->db->trans_status()===TRUE)
			             {
						 	$this->db->trans_commit();
						 	$this->session->set_flashdata('chit_alert',array('message'=>'Design record modified successfully','class'=>'success','title'=>'Edit Design'));
						 	//echo 1;
						 }
						 else
						 {
						 	$this->db->trans_rollback();						 	
						 	$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Edit Design'));
						 	//echo 0;
						 }
						 redirect('admin_ret_catalog/ret_design/list');
				    break;
			case 'update_status':
			
						$data = array('design_status' => $status,
						'updated_time'	  => date("Y-m-d H:i:s"),
					    'updated_by'      => $this->session->userdata('uid'));
						$model=self::CAT_MODEL;
						$updstatus = $this->$model->updateData($data,'design_no',$id,'ret_design_master');
						if($updstatus)
						{
							$this->session->set_flashdata('chit_alert',array('message'=>'Design status updated as '.($status==1 ? 'Active' : 'In Active').' successfully.','class'=>'success','title'=>'Design Status'));
							echo 1;
						}	
						else
						{
							$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Design Status'));
							echo 0;
						}	
						redirect('admin_ret_catalog/ret_design/list');
						break;
			case 'active_designBySearch': 
				  	    $data = $this->$model->getSearchDesign($_POST['searchTxt'],$_POST['product_id']); 						echo json_encode($data);
				        break;  
			case 'list':
						$data['main_content'] = "master/ret_design/list" ;
						$this->load->view('layout/template', $data);
					    break;
			case 'bulk_edit':
			        	$data['main_content'] = "master/ret_design/bulk_edit_list" ;
						$this->load->view('layout/template', $data);
					    break;
			case 'ajax_get_retmaster':
			            $SETT_MOD = self::SETT_MOD;
			        	$retmater = $this->$model->ajax_get_retmaster();
					  	$access = $this->$SETT_MOD->get_access('admin_ret_catalog/ret_design/list');
				        $data = array(
			        					'masterdet' => $retmater,
										'access'    => $access
				        			 );  
						echo json_encode($retmater);
						break;
			case 'ajax_update_bulk_retdesign':
			            $reqdata = $this->input->post('req_data');
						//echo "<pre>"; print_r($reqdata); echo "</pre>"; exit;
			            $update_status = false;
						$attr_update_status = false;
 
						$mc_va_update = "";
						$attr_update = "";

			            $this->db->trans_begin();
						foreach($reqdata as $data)
						{
							$update_status 			= $data['mc_va_update'] == 1 ? false : true;
							$attr_update_status 	= $data['attribute_update'] == 1 ? false : true;

							$mc_va_update 		= $data['mc_va_update'];
							$attr_update  		= $data['attribute_update'];

							if($mc_va_update == 1)
							{
								$update_mc_status = true;
								$employee = $this->session->userdata('uid');

								if($data['mc_cal_type'] != 0 && $data['mc_cal_type'] != ""){
									$upd_data['mc_cal_type']   = $data['mc_cal_type'];
								}
								if($data['mc_cal_value'] != ""){
									$upd_data['mc_cal_value']   = $data['mc_cal_value'];
								}

								if(trim($data['mc_min']) != ""){
									$upd_data['mc_min']   = trim($data['mc_min']);
								}
								if(trim($data['mc_max']) != ""){
									$upd_data['mc_max']   = trim($data['mc_max']);
								}

								if($data['wastageType']==1 && $data['wastag_value'] != "")
								{
									$upd_data['wastag_value']   = $data['wastag_value'];
								}

								if(trim($data['wastag_min']) != ""){
									$upd_data['wastag_min']   = trim($data['wastag_min']);
								}
								if(trim($data['wastag_max']) != ""){
									$upd_data['wastag_max']   = trim($data['wastag_max']);
								}

								if(trim($data['margin_mrp']) != ""){
									$upd_data['margin_mrp']   = trim($data['margin_mrp']);
								}

								else if($data['wastageType']==2)
								{
									foreach($data['wastag_value'] as $wkey => $wval)
									{
										$mcrg_min 	= trim($wval['mcrg_min']) != "" ? trim($wval['mcrg_min']) : NULL;
										$mcrg_max 	= trim($wval['mcrg_max']) != "" ? trim($wval['mcrg_max']) : NULL;
										$wc_min 	= trim($wval['wc_min']) != "" ? trim($wval['wc_min']) : NULL;
										$wc_max 	= trim($wval['wc_max']) != "" ? trim($wval['wc_max']) : NULL;

										$wcweightranges = array(
											"id_sub_design_mapping"=>$data['id_sub_design_mapping'],
											"wc_from_weight"  =>  $wval['from_wt'],
											"wc_to_weight"    =>  $wval['to_wt'],
											"wc_percent"     =>  $wval['wc_percent'],
											"mc"             =>  $wval['mc'],
											"mcrg_min"       =>  $mcrg_min,
											"mcrg_max"       =>  $mcrg_max,
											"wc_min"         =>  $wc_min,
											"wc_max"         =>  $wc_max
										);
										$wastage_id = $this->$model->insertData($wcweightranges,'ret_design_weight_range_wc');
										$update_mc_status = $wastage_id > 0 ? true : false;
										if(!$update_mc_status)
											break;
									}
								}
								//echo "<pre>";print_r($upd_data);exit;
								$upd_data['wastage_type']   = $data['wastageType'];

								if($update_mc_status) {
									$des_id = $this->$model->updateData($upd_data, 'id_sub_design_mapping', $data['id_sub_design_mapping'], 'ret_sub_design_mapping');
									$update_status = $des_id > 0 ? true : false;
									if(!$update_status)
										break;

								} else {
									$update_status = false;
									break;
								}
							}
							else if($attr_update == 1)
							{
								if($data['attribute_type'] == 1)
								{
									foreach($data['attributes'] as $attrkey => $attrval)
									{
										$curr_des_attrs = $this->$model->get_design_attr_values($data['id_sub_design_mapping'], $attrval['attr_name']);

										$total_count_attrs = count($curr_des_attrs);

										if($total_count_attrs > 0) {
											foreach($curr_des_attrs as $curr_attrs) {
												$attr_update_status = $this->$model->deleteData('attr_des_id',$curr_attrs['attr_des_id'],'ret_design_attributes');
												if(!$attr_update_status)
													break 3;
											}
										}

										$des_attrs = array(
											"id_sub_design_mapping"	=>	$data['id_sub_design_mapping'],
											"attr_id"  				=>  $attrval['attr_name'],
											"attr_val_id"    		=>  $attrval['attr_value'],
											'created_on'	  		=> 	date("Y-m-d H:i:s"),
											'created_by'      		=> 	$this->session->userdata('uid')
										);

										$attr_des_id = $this->$model->insertData($des_attrs,'ret_design_attributes');
										$attr_update_status = $attr_des_id > 0 ? true : false;
										if(!$attr_update_status)
											break 2;
										
									}
								}
								else if($data['attribute_type'] == 2)
								{
									$attr_update_status = $this->$model->deleteData('id_sub_design_mapping',$data['id_sub_design_mapping'],'ret_design_attributes');
									if(!$attr_update_status)
										break;
								}
							}
						}

        				if($update_status && $attr_update_status)
        				{		
        					$this->db->trans_commit();
							$msg = "";
							if($mc_va_update == 1 && $attr_update == 1)
								$msg = 'MC / Wastage / Attributes updated successfully';
							else if($mc_va_update == 1)
								$msg = 'MC / Wastage updated successfully';
							else if($attr_update == 1)
								$msg = 'Attributes updated successfully';

        					$status	= array('status' => true,	'msg' => $msg);	
        				}
        				else
        				{
        					$this->db->trans_rollback();
        					$status=array('status'=>false,'msg'=>'Unable to proceed your request');	
        				}
        		  	echo json_encode($status);
			        break;
			default:
						$SETT_MOD = self::SETT_MOD;
						$range['from_date']  = $this->input->post('from_date');
						$range['to_date']  = $this->input->post('to_date');
						$id_product  = $this->input->post('id_product');
					  	$design = $this->$model->ajax_get_design($range['from_date'],$range['to_date'],$id_product);	 
					  	$access = $this->$SETT_MOD->get_access('admin_ret_catalog/ret_design/list');
				        $data = array(
				        					'design' =>$design,
											'access'=>$access
				        			 );  
						echo json_encode($data);
		}
	}
	
	public function screw($type="",$id="",$status=""){
		$model=self::CAT_MODEL;
		switch($type)
		{
			case "add":
	 				$data=array(
						'screw_name'	  => $this->input->post("screw_name"),
						'screw_short_code'=> $this->input->post("screw_short_code"),
						'screw_status'	  => $this->input->post("screw_status"),
						'created_on'	  => date("Y-m-d H:i:s"),
						'created_by'      => $this->session->userdata('uid')
					);
	 				$this->db->trans_begin();
	 				$this->$model->insertData($data,'ret_screw_type');
					if($this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
						$result = array('message'=>'New Screw added successfully!..','class'=>'success','title'=>'Add Screw : ');
					}
					else
					{
						$this->db->trans_rollback();						 	
						$result=array('message'=>'Unable to proceed the requested process..','class'=>'danger','title'=>'Add Screw : ');
					}	
					echo json_encode($result);			
					break;
			case "edit":
		 			$data['screw'] = $this->$model->get_screw($id);
		 			echo json_encode($data['screw']);
				break; 
	 	
			case 'delete':
					$this->db->trans_begin();
					$this->$model->deleteData('screw_id',$id,'ret_screw_type');
					if( $this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
						$this->session->set_flashdata('chit_alert', array('message' => 'Screw deleted successfully','class' => 'success','title'=>'Delete Screw'));	
						echo 1;
					}			  
					else
					{
						$this->db->trans_rollback();
						$this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete Screw'));
						echo 0;
					}
					redirect('admin_ret_catalog/screw/list');
				break;
					
			case "update":
					$data=array(
						'screw_name'	  => $this->input->post("screw_name"),
						'screw_short_code'=> $this->input->post("screw_short_code"),
						'screw_status'	  => $this->input->post("screw_status"),
						'updated_on'	  => date("Y-m-d H:i:s"),
						'updated_by'      => $this->session->userdata('uid')
					);
					$this->db->trans_begin();

					$this->$model->updateData($data,'screw_id',$id,'ret_screw_type');
					if($this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();

						$this->session->set_flashdata('chit_alert',array('message'=>'Screw record modified successfully','class'=>'success','title'=>'Edit Screw'));
						echo 1;
					}
					else
					{
						$this->db->trans_rollback();						 	
						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Edit Screw'));
						echo 0;
					}
						 
				break;
			case 'list':
					$data['main_content'] = "master/screw/list" ;
					$this->load->view('layout/template', $data);
				break;
			case 'update_status':
					$data = array('screw_status' => $status,'updated_on' => date("Y-m-d H:i:s"),'updated_by' => $this->session->userdata('uid'));
					$updstatus = $this->$model->updateData($data,'screw_id',$id,'ret_screw_type');
					if($updstatus)
					{
						$this->session->set_flashdata('chit_alert',array('message'=>'Screw status updated as '.($status == 1 ? 'Active' : 'Inactive').' successfully.','class'=>'success','title'=>'Screw Status'));
						echo 1;
					}	
					else
					{
						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Screw  Status'));
						echo 0;
					}	
				   redirect('admin_ret_catalog/screw/list');
				break;
			case 'active_screw': 
				  	$data = $this->$model->getActivescrew();	  
					echo json_encode($data);
				break;
			default:
					$SETT_MOD 	= self::SETT_MOD;
			     	$range['from_date']	= $this->input->post('from_date');
			        $range['to_date']	= $this->input->post('to_date');
				  	$screw 		= $this->$model->ajax_getscrew($range['from_date'],$range['to_date']);	 
				  	$access 	= $this->$SETT_MOD->get_access('admin_ret_catalog/screw/list');
			        $data 		= array(
			        					'screw' =>$screw,
										'access'=>$access
			        				);  
					echo json_encode($data);
		}
	}
	
	public function hook($type="",$id="",$status=""){
		$model=self::CAT_MODEL;
		switch($type)
		{
			case "add":
	 				$data=array(
						'hook_name'	  => $this->input->post("hook_name"),
						'hook_short_code'=> $this->input->post("hook_short_code"),
						'hook_status'	  => $this->input->post("hook_status"),
						'created_on'	  => date("Y-m-d H:i:s"),
						'created_by'      => $this->session->userdata('uid')
					);
	 				$this->db->trans_begin();
	 				$this->$model->insertData($data,'ret_hook_type');
					if($this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
						$result = array('message'=>'New Hook added successfully!..','class'=>'success','title'=>'Add Hook : ');
					}
					else
					{
						$this->db->trans_rollback();						 	
						$result=array('message'=>'Unable to proceed the requested process..','class'=>'danger','title'=>'Add Hook : ');
					}	
					echo json_encode($result);					
					break;
			case "edit":
		 			$data['hook'] = $this->$model->get_hook($id);
		 			echo json_encode($data['hook']);
				break; 
	 	
			case 'delete':
					$this->db->trans_begin();
					$this->$model->deleteData('hook_id',$id,'ret_hook_type');
					if( $this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
						$this->session->set_flashdata('chit_alert', array('message' => 'Hook deleted successfully','class' => 'success','title'=>'Delete Hook'));	
						echo 1;
					}			  
					else
					{
						$this->db->trans_rollback();
						$this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete Hook'));
						echo 0;
					}
					redirect('admin_ret_catalog/hook/list');
				break;
					
			case "update":
					$data=array(
						'hook_name'	      => $this->input->post("hook_name"),
						'hook_short_code' => $this->input->post("hook_short_code"),
						'hook_status'	  => $this->input->post("hook_status"),
						'updated_on'	  => date("Y-m-d H:i:s"),
						'updated_by'      => $this->session->userdata('uid')
					);
					$this->db->trans_begin();

					$this->$model->updateData($data,'hook_id',$id,'ret_hook_type');
					if($this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();

						$this->session->set_flashdata('chit_alert',array('message'=>'Hook record modified successfully','class'=>'success','title'=>'Edit Hook'));
						echo 1;
					}
					else
					{
						$this->db->trans_rollback();						 	
						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Edit Hook'));
						echo 0;
					}
						 
				break;
			case 'list':
					$data['main_content'] = "master/hook/list" ;
					$this->load->view('layout/template', $data);
				break;
			case 'update_status':
					$data = array('hook_status' => $status,'updated_on' => date("Y-m-d H:i:s"),'updated_by' => $this->session->userdata('uid'));
					$updstatus = $this->$model->updateData($data,'hook_id',$id,'ret_hook_type');
					if($updstatus)
					{
						$this->session->set_flashdata('chit_alert',array('message'=>'Hook status updated as '.($status == 1 ? 'Active' : 'Inactive').' successfully.','class'=>'success','title'=>'Hook Status'));
						echo 1;
					}	
					else
					{
						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Hook Status'));
						echo 0;
					}	
				   redirect('admin_ret_catalog/hook/list');
				break;
			case 'active_hook': 
				  	$data = $this->$model->getActivehook();	  
					echo json_encode($data);
				break;
			default:
					$SETT_MOD 	= self::SETT_MOD;
			     	$range['from_date']	= $this->input->post('from_date');
			        $range['to_date']	= $this->input->post('to_date');
				  	$hook		= $this->$model->ajax_gethook($range['from_date'],$range['to_date']);	 
				  	$access 	= $this->$SETT_MOD->get_access('admin_ret_catalog/hook/list');
			        $data 		= array(
			        					'hook' =>$hook,
										'access'=>$access
			        				);  
					echo json_encode($data);
		}
	}
	
	public function financial_year($type="",$id=""){
		$model = "ret_catalog_model";
		switch($type)
		{
			case 'add':
						$data['finance']= $this->$model->fincnce_empty_record();
						$data['main_content'] = "master/finance_year/form" ;
						$this->load->view('layout/template', $data);
						break;
			case 'list':
						$data['main_content'] = "master/finance_year/list" ;
						$this->load->view('layout/template', $data);
						break;
			case "save": 
						$addData = $_POST['finance']; 
						$data=array(
							'fin_year_code'	=> (!empty($addData['fin_year_code']) ? $addData['fin_year_code'] : NULL ),
							'fin_year_name'	=> (!empty($addData['fin_year_name']) ? $addData['fin_year_name'] : NULL ),
							'fin_year_from'	=> (!empty($addData['fin_year_from']) ? $addData['fin_year_from'] : NULL ),
							'fin_year_to'	=> (!empty($addData['fin_year_to']) ? $addData['fin_year_to'] : NULL ),
							'created_on'	=> date("Y-m-d H:i:s"),
							'created_by'    => $this->session->userdata('uid')
						); 
	 				$this->db->trans_begin();
	 				$insId = $this->$model->insertData($data,'ret_financial_year'); 
	 				
					if($insId>1)
					{
						$this->$model->update_financialstatus($insId);
					}
					if($this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
						$this->session->set_flashdata('chit_alert',array('message'=>'Financial Year added successfully','class'=>'success','title'=>'Add Tagging'));
					}
					else
					{
						$this->db->trans_rollback();						 	
						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Add Financial Year'));
					}
					redirect('admin_ret_catalog/financial_year/list');	
				break;			
			case "edit":
		 			$data['finance'] = $this->$model->get_finance_entry_records($id);
					$data['main_content'] = "master/finance_year/form" ;
		 			$this->load->view('layout/template', $data);
				    break; 	 				
			case 'delete':
					$this->db->trans_begin();
					$this->$model->deleteData('fin_id',$id,'ret_financial_year'); 
					if($this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
						$this->session->set_flashdata('chit_alert', array('message' => 'Financial Year deleted successfully','class' => 'success','title'=>'Financial Year'));	  
					}			  
					else
					{
						$this->db->trans_rollback();
						$this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Financial Year'));
					}
					redirect('admin_ret_catalog/financial_year/list');	
				break;				
			case "update":
	 				$addData = $_POST['finance'];

						$data=array(
						    'fin_year_code'	=> (!empty($addData['fin_year_code']) ? $addData['fin_year_code'] : NULL ),
							'fin_year_name'	=> (!empty($addData['fin_year_name']) ? $addData['fin_year_name'] : NULL ),
							'fin_year_from'	=> (!empty($addData['fin_year_from']) ? $addData['fin_year_from'] : NULL ),
							'fin_year_to'	=> (!empty($addData['fin_year_to']) ? $addData['fin_year_to'] : NULL ),
							'updated_on'	=> date("Y-m-d H:i:s"),
							'updated_by'    => $this->session->userdata('uid')
						); 
					$this->db->trans_begin();
					$id_floor = $this->$model->update_financialData($data,$id,'fin_id','ret_financial_year');
		
					if($this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
						$this->session->set_flashdata('chit_alert',array('message'=>'Financial Year modified successfully','class'=>'success','title'=>'Financial Year'));			
					}
					else
					{
						$this->db->trans_rollback();						 	
						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Financial Year'));
						
 					}
 					redirect('admin_ret_catalog/financial_year/list');	
				break;
			
			default: 
					  	$list = $this->$model->ajax_get_financial_year_List();	 
					  	$access = $this->admin_settings_model->get_access('admin_ret_catalog/financial_year/list');
				        $data = array(
				        					'list'  => $list,
											'access'=> $access
				        			 );  
						echo json_encode($data);
		}
	}

	function financial_status($status,$id)
	{
		$data = array('fin_status' => $status);
		$model = "ret_catalog_model";
	    $this->$model->setFinancialYearStatus();
		$updstatus =$this->$model->updateData($data,'fin_id',$id,'ret_financial_year');
		if($updstatus)
		{
			$this->session->set_flashdata('chit_alert',array('message'=>'Financial Year updated as '.($status == 1 ? 'Active' : 'Inactive').' successfully.','class'=>'success','title'=>'Financial Year'));			
		}	
		else
		{
			$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Financial Year'));
		}	
		redirect('admin_ret_catalog/financial_year/list');	
	}

	//Old Metal Rate
/*	function old_metal_rate()
	{
		$model="ret_catalog_model";
		$id_metal=$this->input->post('id_metal');
		$id_branch=$this->input->post('id_branch');
		$data=array();
		$old_metal_rate=$this->$model->get_old_metal_rate($id_metal,$id_branch);
		$purities=$this->get_all_purity();
		
		foreach ($old_metal_rate as $rate) {
			$html='';
			foreach($purities as $purity)
			{
				$selected = ($purity['id_purity'] == $rate['id_purity']) ? 'selected="selected"' : '';
				 $html.= '<option value="' . $purity['id_purity'] . '"  ' .$selected. '>' . $purity['purity'] . '</option>';
			}
			$data[]=array(
						'id_old_metal_rate'=>$rate['id_old_metal_rate'],
						'metal'=>$rate['metal'],
						'metal_code'=>$rate['metal_code'],
						'id_metal'=>$rate['id_metal'],
						'rate'=>$rate['rate'],
						'id_purity'=>$rate['id_purity'],
						'html'		=>'<select class="form-control purity" id="' .$rate['id_old_metal_rate']. '">'.$html.'</select><input class="id_purity" type="hidden"  id="' .$rate['id_old_metal_rate']. '_id_purity" value="'.$rate['id_purity'].'">',
					   );
		}
		echo json_encode($data);
	}*/

	function update_rate_data()
	{
		$model     = "ret_catalog_model";
	    $id_branch    = $this->input->post('id_branch');
	    $id_metal    = $this->input->post('id_metal');
	    $reqdata   = $this->input->post('req_data'); 
	  
	    if(!empty($reqdata) && sizeof($reqdata)>0 )
	    {
		   	 foreach($reqdata as $data){
		   	    $employee= $this->session->userdata('uid');
		   	   
		   	    $this->db->trans_begin();
		   	    if($data['id_old_metal_rate']=='undefined' || $data['id_old_metal_rate']=='')
		   	    {

		   	    		 $insdata=array(
								'rate'=>$data['rate'],
								'id_metal'=>$id_metal,
								'id_branch'=>($id_branch!='' ?$id_branch :NULL),
								'id_purity'=>($data['id_purity']!='' ?$data['id_purity'] :NULL),
								'created_on'=>date("Y-m-d H:i:s"),
								'created_by'=>$employee,
								'status'=>1
		   	    				 );
		   	    		$updStat=$this->$model->insertData($insdata,'ret_old_metal_rate');
		   	    		
		   	    }
		   	    else
		   	    {	
		   	    	$upd_status=array('status'=>0);
		   	    
		   	    	$update_status=$this->$model->updateData($upd_status,'id_old_metal_rate',$data['id_old_metal_rate'],'ret_old_metal_rate');

		   	    	 $updatedata=array(
								'rate'=>$data['rate'],
								'id_metal'=>$id_metal,
								'id_branch'=>($id_branch!='' ?$id_branch :NULL),
								'id_purity'=>($data['id_purity']!='' ?$data['id_purity'] :NULL),
								'status'=>1,
								'created_on'=>date("Y-m-d H:i:s"),
								'created_by'=>$employee,
		   	    				 );

		   	    	$updStat = $this->$model->insertData($updatedata,'ret_old_metal_rate');
		   	    	
		   	    }
			 }	
		
	   }
	  	
	  	if($updStat)
	  	{		
	  			$this->db->trans_commit();
	  			$this->session->set_flashdata('chit_alert',array('message'=>'Old Metal Rate  Added successfully.','class'=>'success','title'=>'Old Metal rate'));		
	  	}
	  	else
	  	{
	  			$this->db->trans_rollback();	
	  	}
	}

	function get_all_purity()
	{
		$model="ret_catalog_model";
		$data=$this->$model->getActivePurity();
		return $data;
	}
	//Old Metal Rate


	//Material Rate

	function update_mrrate_data()
	{
		$model     = "ret_catalog_model";
	    $material_id    = $this->input->post('material_id');
	    $reqdata   = $this->input->post('req_data'); 
	  
	    if(!empty($reqdata) && sizeof($reqdata)>0 )
	    {
		   	 foreach($reqdata as $data){
		   	    $employee= $this->session->userdata('uid');
		   	   
		   	    $this->db->trans_begin();
		   	    if($data['mat_rate_id']=='undefined' || $data['mat_rate_id']=='')
		   	    {

		   	    		 $insdata=array(
								'mat_rate'=>$data['mat_rate'],
								'material_id'=>$material_id,
								'effective_date'=>date("Y-m-d H:i:s"),
								'created_on'=>date("Y-m-d H:i:s"),
								'created_by'=>$employee,
								'status'=>1
		   	    				 );
		   	    		$updStat=$this->$model->insertData($insdata,'ret_material_rate');
		   	    		
		   	    }
		   	    else
		   	    {	
		   	    	$upd_status=array('status'=>0);
		   	    
		   	    	$update_status=$this->$model->updateData($upd_status,'mat_rate_id',$data['mat_rate_id'],'ret_material_rate');

		   	    	 $updatedata=array(
								'mat_rate'=>$data['mat_rate'],
								'material_id'=>$material_id,
								'status'=>1,
								'created_on'=>date("Y-m-d H:i:s"),
								'effective_date'=>date("Y-m-d H:i:s"),
								'created_by'=>$employee,
		   	    				 );

		   	    	$updStat = $this->$model->insertData($updatedata,'ret_material_rate');
		   	    	
		   	    }
		   	    //echo $this->db->last_query();exit;
			 }	
		
	   }
	  	
	  	if($updStat)
	  	{		
	  			$this->db->trans_commit();
	  			$res = array('message'=>'Old Metal Rate  Added successfully.','class'=>'success','title'=>'Old Metal rate');		
	  	}
	  	else
	  	{
	  			$this->db->trans_rollback();
	  			$res = array('message'=>'Old Metal Rate  Added successfully.','class'=>'success','title'=>'Old Metal rate');		
	  	}
	  	echo json_encode($res);
	}
	
  function weight($type="",$id="")
  {
  	 $model     = "ret_catalog_model";
  	 switch($type)
  	 {
	 	case "Add":
	 	    
                $wt_data=array(
                'value'          =>$this->input->post('name'),
                'from_weight'   =>$this->input->post('from_weight'),
                'to_weight'     =>$this->input->post('to_weight'),
                'id_product'    =>$this->input->post('id_product'),
                'id_design'     =>($this->input->post('id_design')!='' ? $this->input->post('id_design'):NULL),
                'id_sub_design' =>($this->input->post('id_sub_design')!='' ? $this->input->post('id_sub_design'):NULL),
                'id_uom'        =>$this->input->post('uom'),
                'weight_description'  => !empty($this->input->post('weight_desc')) ? $this->input->post('weight_desc') : NULL,
                );
                
     			$this->db->trans_begin();
     			$this->$model->insertData($wt_data,'ret_weight');
     			if($this->db->trans_status()===TRUE)
                 {
                 	$this->db->trans_commit();
    			 	$data=array('status'=>true,'msg'=>'Weight Range Updated Successfully');
    			 }
    			 else
    			 {
    			 	$this->db->trans_rollback();
    			 	 $data=array('status'=>true,'msg'=>'Unable To Proceed Your request');
    			 }
    			echo json_encode($data);
	 		break;
	 	case "Edit":
	 			$data['weight']=$this->$model->get_weight($id);
	 			echo json_encode($data['weight']);
	 		break; 
	 	case "Delete":
	 	        $status=$this->$model->deleteData('id_weight',$id,'ret_weight');
	 			
	 			if($status)
	 			{
					 $this->session->set_flashdata('chit_alert', array('message' => 'Weight deleted successfully','class' => 'success','title'=>'Delete Weight'));
				}
	 			redirect('admin_ret_catalog/weight/list');
	 		break;	
	 	case "Update":
	 			$data['weight']=$this->$model->get_weight($id);
	 			
	 			$wt_data=array(
                'value'         =>$this->input->post('name'),
                'from_weight'   =>$this->input->post('from_weight'),
                'to_weight'     =>$this->input->post('to_weight'),
                'id_product'    =>$this->input->post('id_product'),
                'id_uom'        =>$this->input->post('uom'),
                'id_design'     =>($this->input->post('id_design')!='' && $this->input->post('id_design')!=null ?$this->input->post('id_design'):NULL),
                'id_sub_design' =>($this->input->post('id_sub_design')!='' && $this->input->post('id_sub_design')!=null ?$this->input->post('id_sub_design'):NULL),
                'weight_description'  => !empty($this->input->post('weight_desc')) ? $this->input->post('weight_desc') : NULL,
                );
                $this->db->trans_begin();
	 			$status=$this->$model->updateData($wt_data,'id_weight',$id,'ret_weight');
	 			//print_r($this->db->last_query());exit;
	 		    if($status)
  	            {
  	                $return_data=array('status'=>true,'msg'=>'Weight Updated Successfully');
  	            }else{
  	                $return_data=array('status'=>false,'msg'=>'Unable to Proceed');
  	            }
  	            
              	if($this->db->trans_status()===TRUE)
				{
					$this->db->trans_commit();
					$this->session->set_flashdata('chit_alert',array('message'=>'Weight Updated Successfully','class'=>'success','title'=>'Weight Range'));			
				}
				else
				{
					$this->db->trans_rollback();						 	
					$this->session->set_flashdata('chit_alert',array('message'=>'Unable to Proceed','class'=>'danger','title'=>'Weight Range'));
					
 				}
 				//redirect('admin_ret_catalog/weight/list');	
  	            
  	            echo json_encode($return_data);
	 		break;
	 	case "list":
	 	     $data['main_content'] = "master/ret_weight/list" ;
	        $this->load->view('layout/template', $data);
	 	break;
	 	case "weight_details":
	 	        $data=$this->$model->weight_details($id);
	 			echo json_encode($data);
	 	break;
	 	default:
	 	      $SETT_MOD = self::SETT_MOD;
	 	        $id_product=$this->input->post('id_product');
	 	    	$items=$this->$model->ajax_get_weights($id_product,'','');  
  	            $access=$this->$SETT_MOD->get_access('admin_ret_catalog/weight/list'); 
            	$weights = array(
            	    'access' => $access,
            	    'data'   => $items
            		); 
	           echo json_encode($weights);
	 		break;		
	 }
  }
  
  function get_weight_range_details()
  {
    $model=self::CAT_MODEL;
    $id_product=$this->input->post('id_product');
    $id_design=$this->input->post('id_design');
    $id_sub_design=$this->input->post('id_sub_design');
    $data=$this->$model->weight_details($id_product,$id_design,$id_sub_design);
    echo json_encode($data);
  }
  
  public function reorder_settings($type="",$id="",$status=""){
		$model=self::CAT_MODEL;
		switch($type)
		{
			case "add":
			    $addData=$_POST['settings'];
				$pieces=$_POST['pieces'];
				$this->db->trans_begin();
				 //echo "<pre>"; print_r($_POST);exit;
				if(sizeof($pieces)>0 && $addData['branch_settings']==1)
				{
				   
				    foreach($pieces as $items)
				    {
				        if($items['max_pcs']!='' && $items['min_pcs']!='')
				        {
				            $checkItemExist=$this->$model->checkReorderItemExist($items['id_branch'],$addData);
    				        if($checkItemExist)
    				        {
    				            $insdata=array(
            					'id_branch'	            => $items['id_branch'],
            					'id_wt_range'	        => $addData['weight_range'],
            					'id_product'            => $addData['id_product'],
            					'id_design'	            => $addData['id_design'],
            					'id_sub_design'	        => $addData['id_sub_design'],
            					'max_pcs'	            => $items['max_pcs'],
            					'min_pcs'	            => $items['min_pcs'],
            					'size'		            => $addData['id_size'],
            					'created_on'	  		=> date("Y-m-d H:i:s"),
            					'created_by'      		=> $this->session->userdata('uid')
            					);
            					$this->$model->insertData($insdata,'ret_reorder_settings');
    				        }
				        }
				        
				    }
				}
				else
				{
				    if($items['max_pcs']!='' && $items['min_pcs']!='')
				    {
				        $insdata=array(
                        'id_wt_range'	        => $addData['weight_range'],
                        'id_product'            => $addData['id_product'],
                        'id_design'	            => $addData['id_design'],
                        'id_sub_design'	        => $addData['id_sub_design'],
                        'max_pcs'	            => $addData['max_pcs'],
                        'min_pcs'	            => $addData['min_pcs'],
                        'size'		            => $addData['id_size'],
                        'created_on'	  		=> date("Y-m-d H:i:s"),
                        'created_by'      		=> $this->session->userdata('uid')
                        );
                        $this->$model->insertData($insdata,'ret_reorder_settings');
				    }
                    
				}
				

				//print_r($this->db->last_query());exit;
				if($this->db->trans_status()===TRUE)
	             {
	             	$this->db->trans_commit();
	             	$this->session->set_flashdata('chit_alert', array('message' => 'Re-Order Settings Added successfully','class' => 'success','title'=>'Settings'));	
				 	$data=array('status'=>true,'msg'=>'Re-Order Settings Added Successfully');
				 }
				 else
				 {
				 	$this->db->trans_rollback();
				 	$this->session->set_flashdata('chit_alert', array('message' => 'Unable To Proceed Your request','class' => 'success','title'=>'Settings'));	
				 	 $data=array('status'=>true,'msg'=>'Unable To Proceed Your request');
				 }
				echo json_encode($data);
			break;
			case "edit":
		 			$data['material'] = $this->$model->ajax_getreorder_settings($id,'','','');
		 			echo json_encode($data['material']);
				break; 
	 	
			case 'delete':
					$this->db->trans_begin();
					$this->$model->deleteData('id_reorder_settings',$id,'ret_reorder_settings');
			        if( $this->db->trans_status()===TRUE)
					{
					      $this->db->trans_commit();
						  $this->session->set_flashdata('chit_alert', array('message' => 'Re-Order Settings deleted successfully','class' => 'success','title'=>'Settings'));	
						  echo 1;
					}			  
				    else
				    {
						 $this->db->trans_rollback();
						 $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Settings'));
						 echo 0;
				    }
					redirect('admin_ret_catalog/reorder_settings/list');
				break;
					
			case "update":
					$id_branch=$this->input->post('id_branch');
					$wt_range=$this->input->post('wt_range');
					$product=$this->input->post('product');
					$id_design=$this->input->post('id_design');
					$id_sub_design=$this->input->post('id_sub_design');
					$size=$this->input->post('size');
					$min_pcs=$this->input->post('min_pcs');
					$max_pcs=$this->input->post('max_pcs');
					$id_reorder_settings=$this->input->post('id');
					$UpdData=array(
						'id_branch'	    =>(!empty($id_branch) ? $id_branch: NULL),
						'id_wt_range'	=>(!empty($wt_range) ? $wt_range: NULL),
						'id_product'    =>(!empty($product) ? $product: NULL),
						'id_design'	    =>(!empty($id_design) ? $id_design: NULL),
						'id_sub_design'	=>(!empty($id_sub_design) ? $id_sub_design: NULL),
						'max_pcs'	    =>(!empty($max_pcs) ? $max_pcs: NULL),
						'min_pcs'	    =>(!empty($min_pcs) ? $min_pcs: NULL),
						'size'		    =>(!empty($size) ? $size: NULL),
						'updated_on'    => date("Y-m-d H:i:s"),
						'updated_by'    => $this->session->userdata('uid')
						);
				$this->db->trans_begin();
				
				$checkItemExist=$this->$model->checkReorderSettingsUpdateItemExist($id_reorder_settings,$UpdData);
				if($checkItemExist)
				{
				    	$this->$model->updateData($UpdData,'id_reorder_settings',$id_reorder_settings,'ret_reorder_settings');
				    	
				    	 if($this->db->trans_status()===TRUE)
    		             {
    		             	$this->db->trans_commit();
    		             	$this->session->set_flashdata('chit_alert', array('message' => 'Re-Order Settings Updated successfully','class' => 'success','title'=>'Settings'));	
    					 	$data=array('status'=>true,'msg'=>'Re-Order Settings Updated Successfully');
    					 }
    					 else
    					 {
    					 	$this->db->trans_rollback();
    					 	$this->session->set_flashdata('chit_alert', array('message' => 'Unable To Proceed Your request','class' => 'success','title'=>'Settings'));	
    					 	 $data=array('status'=>true,'msg'=>'Unable To Proceed Your request');
    					 }
				}
				else
				{
				    $this->session->set_flashdata('chit_alert', array('message' => 'Re-Order Settings Already Exists','class' => 'danger','title'=>'Settings'));	
    				$data=array('status'=>true,'msg'=>'Re-Order Settings Already Exists');
				}
				
					
					echo json_encode($data);

				break;
			case 'active_design':
					$data = $this->$model->ajax_get_design('','',$_POST['id_product']);  
					echo json_encode($data);
					break;
			case 'weight_range':
					$data=$this->$model->ajax_get_weights($_POST['id_product'],$_POST['id_design'],$_POST['id_sub_design']); 
					echo json_encode($data); 
			break;
			case 'list':
					$data['main_content'] = "master/ret_reorder_settings/list" ;
					$this->load->view('layout/template', $data);
					break;
			case 'update_status':
					$data = array('material_status' => $status,'updated_on' => date("Y-m-d H:i:s"),'updated_by' => $this->session->userdata('uid'));
					$updstatus = $this->$model->updateData($data,'material_id',$id,'ret_material');
					if($updstatus)
					{
					$this->session->set_flashdata('chit_alert',array('message'=>'Material status updated as '.($status == 1 ? 'Active' : 'Inactive').' successfully.','class'=>'success','title'=>'Material  Status'));		
					echo 1;
					}	
					else
					{
					$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Material  Status'));
					echo 0;
					}	
					redirect('admin_ret_catalog/material/list');
				break;
			default:
					$SETT_MOD 	= self::SETT_MOD;
					$id_product = (isset($_POST['id_product']) ? $_POST['id_product'] :'');
					$id_design = (isset($_POST['id_design']) ? $_POST['id_design'] :'');
					$id_wt_range = (isset($_POST['id_wt_range']) ? $_POST['id_wt_range'] :'');
					$data 		= $this->$model->ajax_getreorderSett($_POST);	 
					$access 	= $this->$SETT_MOD->get_access('admin_ret_catalog/reorder_settings/list');
					$data 		= array(
										'responseData' =>$data,
										'access'=>$access
									   );  
					echo json_encode($data);
		}
	}
	

	public function ret_delivery($type="",$id=""){
		$model=self::CAT_MODEL;
		switch($type)
		{
			case "Add":
	 				$data=array('name'=>$this->input->post("name"), 
	 							'date_add'=>date("Y-m-d H:i:s"),
								'created_by'=>$this->session->userdata('uid')
							    );
	 				$this->db->trans_begin();
	 				$this->$model->insertData($data,'ret_sale_delivery'); 
		 			if($this->db->trans_status()===TRUE)
		             {
					 	$this->db->trans_commit();
					 	$this->session->set_flashdata('chit_alert',array('message'=>'New Location added successfully','class'=>'success','title'=>'Add Location'));
					 	echo 1;
					 }
					 else
					 {
					 	$this->db->trans_rollback();						 	
					 	$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Add Location'));
					 	echo 0;
					 }
					
	 		break;
	 	case "Edit":
	 			$data['delivery'] = $this->$model->get_delivery($id);
	 			echo json_encode($data['delivery']);
	 		break; 
	 	
	 	case 'delete':
						 $this->db->trans_begin();
						 $this->$model->deleteData('id_sale_delivery',$id,'ret_sale_delivery');
				           if( $this->db->trans_status()===TRUE)
						    {
						    	  $this->db->trans_commit();
								  $this->session->set_flashdata('chit_alert', array('message' => 'Location deleted successfully','class' => 'success','title'=>'Delete Location'));	  
							}			  
						   else
						   {
							 $this->db->trans_rollback();
							 $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete Location'));
						   }
						 redirect('admin_ret_catalog/ret_delivery/List');	
				break;
		
					
	 	case "Update":
	 			$name=$this->input->post('name');
	 			$data=array("name"=>$name,
	 						'updated_by'=>$this->session->userdata('uid'),
	 						'date_upd'   => date("Y-m-d H:i:s"),
	 						);
	 			
	 			 $this->db->trans_begin();
			            
			         $status = $this->$model->updateData($data,'id_sale_delivery',$id,'ret_sale_delivery');
			          
			            if($this->db->trans_status()===TRUE)
			             {
						 	$this->db->trans_commit();
						 	
						 	$this->session->set_flashdata('chit_alert',array('message'=>'Location Updated  successfully','class'=>'success','title'=>'Location'));
						 	
						 }
						 else
						 {
						 	 $this->db->trans_rollback();						 	
						 	$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Edit Purity'));
						 	
						 }
	 		break;
	 		
			case 'List':
			
						$data['main_content'] = "master/ret_delivery/list" ;
						$this->load->view('layout/template', $data);
					break;
			case 'active_purities':
						$purity = $this->$model->getActivePurity();	
						echo json_encode($purity);
					break;				
			default:
						$SETT_MOD = self::SETT_MOD;
					  	$purity = $this->$model->ajax_getDelivery();	 
					  	$access = $this->$SETT_MOD->get_access('admin_ret_catalog/ret_delivery/List');
				        $data = array(
				        					'delivery' =>$purity,
											'access'=>$access
				        				);  
						echo json_encode($data);
		}
	}

	function update_location($status,$id)
	{
		$model=self::CAT_MODEL;
		$this->db->trans_begin();
		$data = array('is_default'  => $status,
					   'date_upd'   => date("Y-m-d H:i:s"),
					   'updated_by' => $this->session->userdata('uid')
					);
		$this->$model->update_location();
		$status = $this->$model->updateData($data,'id_sale_delivery',$id,'ret_sale_delivery');
		if($this->db->trans_status()===TRUE)
         {
		 	$this->db->trans_commit();
			$this->session->set_flashdata('chit_alert',array('message'=>'Location updated successfully.','class'=>'success','title'=>'Location'));			
		}	
		else
		{
			$this->db->trans_rollback();
			$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Customer Status'));
		}	
		redirect('admin_ret_catalog/ret_delivery/List');	
	}
	
	function set_ret_prod_image($id)
   {
     $data=array();
     $model=self::CAT_MODEL;
     //print_r($this->db->last_query());exit;
   	 if($_FILES['product']['name']['ret_product_img'])
     { 
   	 	$path='assets/img/ret_product/';
	
         if (!is_dir($path)) {

            mkdir($path, 0777, TRUE);
		}
		else{
			$file = $path.$id['ID'].".jpg" ;
            chmod($path,0777);
            unlink($file);
		}

   	 	$img= str_replace(' ', '_', $_FILES['product']['tmp_name']['ret_product_img']);

		$filename =str_replace(' ','_', $_FILES['product']['name']['ret_product_img']);	



   	 	$imgpath='assets/img/ret_product/'.$filename;



	 	$upload=$this->upload_img('image',$imgpath,$img);	



	 	$data['image']= base_url().$imgpath;

	 	$this->$model->updateData("update",$id['ID'],$data);



	 } 
   }
   
    function get_Activesize()
    {
         $model=self::CAT_MODEL;
         $data=$this->$model->get_Activesize($_POST['id_product']);
         echo json_encode($data);
    }
    
    public function ret_size($type="",$id=""){
		$model=self::CAT_MODEL;
		switch($type)
		{
			case "Add":
	 				$data=array(
	 							'id_product'=>$this->input->post("id_product"), 
	 							'value'		=>$this->input->post("size"), 
	 							'name'		=>$this->input->post("unit"), 
	 							'created_on'=>date("Y-m-d H:i:s"),
								'created_by'=>$this->session->userdata('uid')
							    );
	 				$this->db->trans_begin();
	 				$this->$model->insertData($data,'ret_size'); 
		 			if($this->db->trans_status()===TRUE)
		             {
					 	$this->db->trans_commit();
					 	$this->session->set_flashdata('chit_alert',array('message'=>'New Size added successfully','class'=>'success','title'=>'Add Size'));
					 	echo 1;
					 }
					 else
					 {
					 	$this->db->trans_rollback();						 	
					 	$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Add Location'));
					 	echo 0;
					 }
					
	 		break;
	 	case "Edit":
	 			$data['size'] = $this->$model->get_size($id);
	 			echo json_encode($data['size']);
	 		break; 
	 	
	 	case 'delete':
						 $this->db->trans_begin();
						 $this->$model->deleteData('id_size',$id,'ret_size');
				           if( $this->db->trans_status()===TRUE)
						    {
						    	  $this->db->trans_commit();
								  $this->session->set_flashdata('chit_alert', array('message' => 'Size deleted successfully','class' => 'success','title'=>'Delete Size'));	  
							}			  
						   else
						   {
							 $this->db->trans_rollback();
							 $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete Size'));
						   }
						 redirect('admin_ret_catalog/ret_size/list');	
				break;
		
					
	 	case "Update":
	 			$id_product	=$this->input->post('id_product');
	 			$name 		=$this->input->post('units');
	 			$value 		=$this->input->post('size');
	 			$data=array(
	 						"id_product"=>$id_product,
	 						"name"=>$name,
	 						"value"=>$value,
	 						'updated_by'=>$this->session->userdata('uid'),
	 						'updated_on'   => date("Y-m-d H:i:s"),
	 						);
	 			
	 			 $this->db->trans_begin();
			            
			         $status = $this->$model->updateData($data,'id_size',$id,'ret_size');
			          //print_r($this->db->last_query());exit;
			            if($this->db->trans_status()===TRUE)
			             {
						 	$this->db->trans_commit();
						 	
						 	$this->session->set_flashdata('chit_alert',array('message'=>'Size Updated  successfully','class'=>'success','title'=>'Location'));
						 	
						 }
						 else
						 {
						 	 $this->db->trans_rollback();						 	
						 	$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Edit Size'));
						 	
						 }
	 		break;
	 		
			case 'list':
						$data['main_content'] = "master/ret_size/ret_size" ;
						$this->load->view('layout/template', $data);
			break;
						
			default:
						$SETT_MOD = self::SETT_MOD;
					  	$size = $this->$model->ajax_getsize();	 
					  	$access = $this->$SETT_MOD->get_access('admin_ret_catalog/ret_size/list');
				        $data = array(
				        					'size' =>$size,
											'access'=>$access
				        				);  
						echo json_encode($data);
		}
	}

	function update_size_status($status,$id)
	{
		$model=self::CAT_MODEL;
		$this->db->trans_begin();
		$data = array('active'  => $status,
					   'updated_on'   => date("Y-m-d H:i:s"),
					   'updated_by' => $this->session->userdata('uid')
					);
		$status = $this->$model->updateData($data,'id_size',$id,'ret_size');
		//print_r($this->db->last_query());exit;
		if($this->db->trans_status()===TRUE)
         {
		 	$this->db->trans_commit();
			$this->session->set_flashdata('chit_alert',array('message'=>'Size updated successfully.','class'=>'success','title'=>'Size'));			
		}	
		else
		{
			$this->db->trans_rollback();
			$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Size Status'));
		}	
		redirect('admin_ret_catalog/ret_size/list');		
	}
	
		//Old Metal Type
	public function metal_type($type="",$id="",$status="")
	{
		$model=self::CAT_MODEL;
		switch($type)
		{	
	 		case "add":
                    $data=array(
                    'metal_type'=>strtoupper($this->input->post("metal_type")),
                    'id_metal'=>$this->input->post('id_metal'),
                    'date_add'=>date("Y-m-d H:i:s"),
                    'created_by'=>$this->session->userdata('uid')
                    );
	 				$this->db->trans_begin();
						 
					$this->$model->insertData($data,'ret_old_metal_type');
					
					if($this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
						$this->session->set_flashdata('chit_alert',array('message'=>'Metal Type Added Successfully.','class'=>'success','title'=>'Metal Type'));	
						$result = array('message'=>'New Metal TYpe added successfully!..','class'=>'success','title'=>'Metal Type : ');
						
					}
					else
					{
						$this->db->trans_rollback();
						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to Proceed.','class'=>'danger','title'=>'Metal Type'));	
						$result = array('message'=>'Unable to proceed the requested process..','class'=>'danger','title'=>'Metal Type : ');
						
					}
					echo json_encode($result);
					break;
			case "edit":
						$data= $this->$model->get_old_metal_type($id);
						echo json_encode($data);
						break; 
	 	
			case 'delete':
						 $this->db->trans_begin();
						 $this->$model->deleteData('id_metal_type',$id,'ret_old_metal_type');
				           if( $this->db->trans_status()===TRUE)
						    {
						    	  $this->db->trans_commit();
								  $this->session->set_flashdata('chit_alert', array('message' => 'Metal Type deleted successfully','class' => 'success','title'=>'Delete Metal Type'));	  
							}			  
						   else
						    {
							 $this->db->trans_rollback();
							 $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete Metal Type'));
						    }
						 redirect('admin_ret_catalog/metal_type/list');	
						 break;
					
			case "update":
    		 			 $data=array(
                        'metal_type'=>strtoupper($this->input->post("ed_metal_type")),
                        'id_metal'=>$this->input->post('id_metal'),
                        'date_upd'=>date("Y-m-d H:i:s"),
                        'updated_by'=>$this->session->userdata('uid')
                        );
	 			        
	 			        $this->db->trans_begin();
	 			     
						$this->$model->updateData($data,'id_metal_type',$this->input->post('id_metal_type'),'ret_old_metal_type'); 
						//print_r($this->db->last_query());exit;
			            if($this->db->trans_status()===TRUE)
			             {
						 	$this->db->trans_commit();
						 	$this->session->set_flashdata('chit_alert',array('message'=>'User record modified successfully','class'=>'success','title'=>'Metal Type'));
						 	echo 1;
						 }
						 else
						 {
						 	$this->db->trans_rollback();						 	
						 	$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'etal Type'));
						 	echo 0;
						 }
				    break;
		

			case 'list':
						$data['main_content'] = "master/metal_type/list" ;
						$this->load->view('layout/template', $data);
					    break;
				
			default:
						$SETT_MOD = self::SETT_MOD;
					  	$list = $this->$model->ajax_getOldMetalType();	 
					  	$access = $this->$SETT_MOD->get_access('admin_ret_catalog/metal_type/list');
				        $data = array(
				        					'list' =>$list,
											'access'=>$access
				        			 );  
						echo json_encode($data);
		}
	}
	//Old Metal Type
	
	//Old Metal Category
	public function old_metal_cat($type="",$id="",$status="")
	{
		$model=self::CAT_MODEL;
		switch($type)
		{	
				case "add":
					$id_old_metal_type 	= $this->input->post("id_old_metal_type");
					$data				= $this->$model->get_old_metal_type($id_old_metal_type);

					$data=array(
					'old_metal_cat'=>strtoupper($this->input->post("old_metal_cat")),
					'old_metal_perc'=>$this->input->post("old_metal_perc"),
					'id_old_metal_type'=>$this->input->post("id_old_metal_type"),
					'id_metal'=>$data['id_metal'],
					'date_add'=>date("Y-m-d H:i:s"),
					'created_by'=>$this->session->userdata('uid')
					);
					
					$this->db->trans_begin();
							
					$this->$model->insertData($data,'ret_old_metal_category');
					
					if($this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
						$this->session->set_flashdata('chit_alert',array('message'=>'Old Metal Category Added Successfully.','class'=>'success','title'=>'Old Metal Category'));	
						$result = array('message'=>'New Old Metal Category added successfully!..','class'=>'success','title'=>'Metal Type : ');
					}
					else
					{
						$this->db->trans_rollback();
						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to Proceed.','class'=>'danger','title'=>'Old Metal Category'));	
						$result = array('message'=>'Unable to proceed the requested process..','class'=>'danger','title'=>'Old Metal Category : ');
						
					}
					echo json_encode($result);
					break;
			case "edit":
						$data= $this->$model->get_old_metal_category($id);
						echo json_encode($data);
						break; 
			
			case 'delete':
							$this->db->trans_begin();
							$this->$model->deleteData('id_old_metal_cat',$id,'ret_old_metal_category');
							if( $this->db->trans_status()===TRUE)
							{
									$this->db->trans_commit();
									$this->session->set_flashdata('chit_alert', array('message' => 'Old Metal Category deleted successfully','class' => 'success','title'=>'Delete Old Metal Category'));	  
							}			  
							else
							{
								$this->db->trans_rollback();
								$this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete Old Metal Category'));
							}
							redirect('admin_ret_catalog/old_metal_cat/list');	
							break;
					
			case "update":
						$id_old_metal_type 	= $this->input->post("ed_id_old_metal_type");
						$data				= $this->$model->get_old_metal_type($id_old_metal_type);

						$data=array(
							'old_metal_cat'=>strtoupper($this->input->post("ed_old_metal_cat")),
							'old_metal_perc'=>$this->input->post("ed_old_metal_perc"),
							'id_old_metal_type'=>$id_old_metal_type,
							'id_metal'=>$data['id_metal'],
							'date_upd'=>date("Y-m-d H:i:s"),
							'updated_by'=>$this->session->userdata('uid')
						);
							
							$this->db->trans_begin();
						
						$this->$model->updateData($data,'id_old_metal_cat',$this->input->post('id_old_metal_cat'),'ret_old_metal_category'); 
						//print_r($this->db->last_query());exit;
						if($this->db->trans_status()===TRUE)
							{
								$this->db->trans_commit();
								$this->session->set_flashdata('chit_alert',array('message'=>'User record modified successfully','class'=>'success','title'=>'Old Metal Category'));
								echo 1;
							}
							else
							{
								$this->db->trans_rollback();						 	
								$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Old Metal Category'));
								echo 0;
							}
					break;
		

			case 'list':
						$data['main_content'] = "master/metal_category/list" ;
						$this->load->view('layout/template', $data);
						break;
			case 'active_oldmetal':
						$data= $this->$model->ajax_getOldMetalType();
						echo json_encode($data);
						break; 
			default:
						$SETT_MOD = self::SETT_MOD;
							$list = $this->$model->ajax_getOldMetalCategory();	 
							$access = $this->$SETT_MOD->get_access('admin_ret_catalog/old_metal_cat/list');
						$data = array(
											'list' =>$list,
											'access'=>$access
										);  
						echo json_encode($data);
		}
	}
	//Old Metal Category
	
	//Old Metal Rate
	public function old_metal_rate($type="",$id="",$status="")
	{
		$model=self::CAT_MODEL;
		switch($type)
		{	
	 		case "add":
	 		    $this->db->trans_begin();
	 		    
	 		    $this->$model->updateData(array('status'=>0),'id_metal',1,'ret_old_metal_rate'); 
	 		    $this->$model->updateData(array('status'=>0),'id_metal',2,'ret_old_metal_rate'); 
	 		    
                    $data=array(
                        'id_metal'  =>1,
                        'id_purity' =>1,
                        'created_on'=>date("Y-m-d H:i:s"),
                        'rate'      =>$this->input->post('gold_rate'),
                        'created_by'=>$this->session->userdata('uid')
                    );
                    
	 				
						 
					$this->$model->insertData($data,'ret_old_metal_rate');
					
					$data=array(
                        'id_metal'  =>2,
                        'id_purity' =>1,
                        'rate'      =>$this->input->post('silver_rate'),
                        'created_on'=>date("Y-m-d H:i:s"),
                        'created_by'=>$this->session->userdata('uid')
                    );
                    
                    $this->$model->insertData($data,'ret_old_metal_rate');
					
					if($this->db->trans_status()===TRUE)
					{
						$this->db->trans_commit();
						$this->session->set_flashdata('chit_alert',array('message'=>'Metal Rate Added Successfully.','class'=>'success','title'=>'Metal Rate'));	
						$result = array('message'=>'Metal Rate added successfully!..','class'=>'success','title'=>'Metal Rate : ');
						
					}
					else
					{
						$this->db->trans_rollback();
						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to Proceed.','class'=>'danger','title'=>'Metal Rate'));	
						$result = array('message'=>'Unable to proceed the requested process..','class'=>'danger','title'=>'Metal Rate : ');
						
					}
					echo json_encode($result);
					//redirect('admin_ret_catalog/metal_type/list');	
					break;
			case "edit":
						$data= $this->$model->getOldMetalRate($id);
						echo json_encode($data);
						break; 
	 	
			case 'delete':
						 $this->db->trans_begin();
						 $this->$model->deleteData('id_metal_type',$id,'ret_old_metal_type');
				           if( $this->db->trans_status()===TRUE)
						    {
						    	  $this->db->trans_commit();
								  $this->session->set_flashdata('chit_alert', array('message' => 'Metal Type deleted successfully','class' => 'success','title'=>'Delete Metal Type'));	  
							}			  
						   else
						    {
							 $this->db->trans_rollback();
							 $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete Metal Type'));
						    }
						 redirect('admin_ret_catalog/metal_type/list');	
						 break;
					
			case "update":
			            $data=array(
                        'updated_on'=>date("Y-m-d H:i:s"),
                        'updated_by'=>$this->session->userdata('uid')
                        );
			            if($this->input->post('id_metal')==1)
			            {
			                $data['rate']=$this->input->post('ed_gold_rate');
			            }else{
			                $data['rate']=$this->input->post('ed_silver_rate');
			            }
    		 			
	 			        
	 			        $this->db->trans_begin();
	 			     
						$this->$model->updateData($data,'id_old_metal_rate',$this->input->post('id_old_metal_rate'),'ret_old_metal_rate'); 
						//print_r($this->db->last_query());exit;
			            if($this->db->trans_status()===TRUE)
			             {
						 	$this->db->trans_commit();
						 	$this->session->set_flashdata('chit_alert',array('message'=>'Rate successfully','class'=>'success','title'=>'Metal Rate'));
						 	echo 1;
						 }
						 else
						 {
						 	$this->db->trans_rollback();						 	
						 	$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Metal Rate'));
						 	echo 0;
						 }
				    break;
		

			case 'list':
						$data['main_content'] = "master/old_metal_rate/list" ;
						$this->load->view('layout/template', $data);
			break;
			
			default:
						$SETT_MOD = self::SETT_MOD;
					  	$list = $this->$model->ajax_GetOldMetalRate();	 
					  	$access = $this->$SETT_MOD->get_access('admin_ret_catalog/old_metal_rate/list');
				        $data = array(
				        					'list' =>$list,
											'access'=>$access
				        			 );  
						echo json_encode($data);
		}
	}

	//Old Metal Rate
	
	
	
	//Section
	
	public function ret_section($type="",$id="")
	{
		$model=self::CAT_MODEL;
		switch($type)
		{
			case "Add":
	 				$data=array(
	 							'section_name'		=> strtoupper($this->input->post("section_name")), 
	 							'section_short_code' => strtoupper($this->input->post("short_code")),	
					            'status'            => $this->input->post("section_status"), 
	 							'date_add'          => date("Y-m-d H:i:s"),
								'created_by'        => $this->session->userdata('uid')
							    );
	 				$this->db->trans_begin();
	 				$result=$this->$model->insertData($data,'ret_section'); 
        			if($result>0)
        			{
        				foreach($sect_branch as $val)
        				{
        					$data['branch']=array(
        						'id_section' => $result,
        						'id_branch'  => $val,
        						'created_on' => date("Y-m-d H:i:s"),
        						'created_by' => $this->session->userdata('uid')
        					);
        					$this->$model->insertData($data['branch'],'ret_section_branch');
        					//print_r($this->db->last_query());
        				}
        
        			}
		 			if($this->db->trans_status()===TRUE)
		             {
					 	$this->db->trans_commit();
					 	$this->session->set_flashdata('chit_alert',array('message'=>'Section added successfully','class'=>'success','title'=>'Add Section'));
					 	$return_data=array('message'=>'Section added successfully','status'=>true);
					 	echo json_encode($return_data);
					 }
					 else
					 {
					 	$this->db->trans_rollback();						 	
					 	$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Add Section'));
					 	$return_data=array('message'=>'Unable to proceed the requested process','status'=>false);
					 	echo json_encode($return_data);
					 }
					
	 		break;
	 	case "Edit":
	 			$data['section'] = $this->$model->get_sections($id);
	 			$section_branch = $this->$model->get_sectionsbranch($id);
                $data['branch'] = array('id_branch' => $section_branch);
                $data['section']['branch']=$section_branch;
	 			echo json_encode($data['section']);
	 		break; 
	 	
	 	case 'delete':
						 $this->db->trans_begin();
						 $this->$model->deleteData('id_section',$id,'ret_section');
						 $this->$model->deleteData('id_section',$id,'ret_section_branch');
				           if( $this->db->trans_status()===TRUE)
						    {
						    	  $this->db->trans_commit();
								  $this->session->set_flashdata('chit_alert', array('message' => 'Section deleted successfully','class' => 'success','title'=>'Delete Section'));	  
							}			  
						   else
						   {
							 $this->db->trans_rollback();
							 $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete Section'));
						   }
						 redirect('admin_ret_catalog/ret_section/list');	
				break;
		
					
	 	case "Update":
	 			$section_name =strtoupper($this->input->post("section_name"));
            	$section_short_code = strtoupper($this->input->post("short_code"));
            	$section_status=$this->input->post("section_status");
            	$data=array(
            				"section_name"  => $section_name,
            				'section_short_code' =>$section_short_code,
            				'status'            =>$section_status,
            				'updated_by'    => $this->session->userdata('uid'),
            				'date_upd'      => date("Y-m-d H:i:s"),
            				);
	
        		$this->db->trans_begin();
        			
        			$status = $this->$model->updateData($data,'id_section',$id,'ret_section');
        
        			$this->$model->deleteData('id_section',$id,'ret_section_branch');
        			
        			$sect_branch = $this->input->post('id_branch');
        			
        			if($status>0)
        			{
        			foreach($sect_branch as $val)
        			{
        				$data['branch']=array(
        					'id_section' => $id,
        					'id_branch'  => $val,
        					'updated_on' => date("Y-m-d H:i:s"),
        					'updated_by' => $this->session->userdata('uid')
        				);
        				$this->$model->insertData($data['branch'],'ret_section_branch');
        				//print_r($this->db->last_query());exit;
        
        			}
        
        			}
        			if($this->db->trans_status()===TRUE)
    				{
    				$this->db->trans_commit();
    				
    				$this->session->set_flashdata('chit_alert',array('message'=>'Section Updated  successfully','class'=>'success','title'=>'Section'));
    				
    				}
    				else
    				{
    					$this->db->trans_rollback();						 	
    				$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Section'));
    				
    				}
	 		break;
	 		
	 		case 'update_status':
            	$data = array('status' => $status,
            	'date_upd'	  => date("Y-m-d H:i:s"),
            	'updated_by'      => $this->session->userdata('uid'));
            	$model=self::CAT_MODEL;
            	$updstatus = $this->$model->updateData($data,'id_section',$id,'ret_section');
            	//print_r($this->db->last_query());exit;
            	//echo $this->db->_error_message();
            	//exit;
            	if($updstatus)
            	{
            		$this->session->set_flashdata('chit_alert',array('message'=>'Section status updated as '.($status==1 ? 'Active' : 'Inactive').' successfully.','class'=>'success','title'=>'Section  Status')); 
            	}	
            	else
            	{
            		$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Section  Status')); 
            	}							
            	redirect('admin_ret_catalog/ret_section/list');	
            break;

			case 'list':
						$data['main_content'] = "master/ret_section/list" ;
						$this->load->view('layout/template', $data);
			break;
						
			default:
						$SETT_MOD = self::SETT_MOD;
					  	$list = $this->$model->ajax_getSection();	 
					  	$access = $this->$SETT_MOD->get_access('admin_ret_catalog/ret_section/list');
				        $data = array(
				        					'list' =>$list,
											'access'=>$access
				        				);  
						echo json_encode($data);
		}
	}
	
	function get_sectionBranchwise()
	{
		$model=self::CAT_MODEL;
		$data=$this->$model->get_sectionBranchwise($_POST);
		echo json_encode($data);
	}
	
	//Section
	
	
	//Section Mapping
	
	function get_section()
	{
        $model=self::CAT_MODEL;
        $data=$this->$model->get_section();
        echo json_encode($data);
    }
    
	public function update_product_section()
    {
            $model=self::CAT_MODEL;
            $i=0;
            foreach($_POST['req_data'] as $section)
                {
                    $i++;
                    $data = array(  
                               'id_section'    => $section['product_section_select'],
                               'updated_time'  => date("Y-m-d H:i:s"),
                               'updated_by'    => $this->session->userdata('uid')
                            );
                    $this->db->trans_begin();
                    
                    $this->$model->updateData($data,'pro_id',$section['pro_id'],'ret_product_master');
                }
                 if($this->db->trans_status()===TRUE)
                 {
                     $this->db->trans_commit();
                    $this->session->set_flashdata('chit_alert',array('message'=>'Section updated successfully No of Updated Records '.$i,'class'=>'success','title'=>'Section'));   
                    $return_data=array('status'=>true,'message'=>'Section updated successfully.');
                }   
                else
                {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Section'));
                    $return_data=array('status'=>false,'message'=>'Section not updated.');
                }
                echo json_encode($return_data);
        }
	//Section Mapping
	
		//Feedback Master
	function feedback($type="",$id="")
    {
        $model=self::CAT_MODEL;
        switch($type){
            case 'add':
                $data=array(
                    'name'          =>strtoupper($this->input->post('name')), 
                    'created_by'    =>$this->session->userdata('uid'),
                    'created_date'  => date("Y-m-d H:i:s")
                );
                $this->db->trans_begin();
                $this->$model->insertData($data,'customer_feedback_master'); 
                if($this->db->trans_status()===TRUE)
                {
                    $this->db->trans_commit();
                    $this->session->set_flashdata('chit_alert',array('message'=>'New Feedback added successfully','class'=>'success','title'=>'Add Feedback'));
                        
                }
                else
                {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed your request','class'=>'danger','title'=>'Add Feedback'));
                }
            break;

            case "edit":
                $data['feedback'] = $this->$model->get_feedback($id);
                echo json_encode($data['feedback']);
            break; 

            case "update":
                
               $data=array(
                           "name"          => strtoupper($this->input->post('name')),
                           'updated_by'    => $this->session->userdata('uid'),
                           'updated_date'  => date("Y-m-d H:i:s")
                           );
                
                $this->db->trans_begin();
                $status = $this->$model->updateData($data,'id_feedback',$id,'customer_feedback_master');
                if($this->db->trans_status()===TRUE)
                {
                    $this->db->trans_commit();
                    $this->session->set_flashdata('chit_alert',array('message'=>'Feedback Updated  successfully','class'=>'success','title'=>'Feedback'));
                }
                else
                {
                    $this->db->trans_rollback();                           
                    $this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Feedback'));
                }
            break;

            case 'delete':
                $this->db->trans_begin();
                $this->$model->deleteData('id_feedback',$id,'customer_feedback_master');
                  if( $this->db->trans_status()===TRUE)
                   {
                         $this->db->trans_commit();
                         $this->session->set_flashdata('chit_alert', array('message' => 'Feedback deleted successfully','class' => 'success','title'=>'Delete Feedback'));    
                   }              
                  else
                  {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete Feedback'));
                  }
                redirect('admin_ret_catalog/feedback/list');    
            break;

            case 'list':
                $data['main_content']="master/customer/feedback/list";
                $this->load->view('layout/template',$data);
            break;

            case 'save':
                // print_r($_POST);exit;
                $feedback=$_POST['id_feedback'];
                $i=1;
                    $insData=array(
                        'id_customer'       =>$_POST['id_customer'],
                        'feedback_date'     => date("Y-m-d H:i:s"),
                        'feedback_taken_by' =>$this->session->userdata('uid'),
                        'comments'          =>$_POST['comments'],
                    );
                    
                    $this->db->trans_begin();
                    $insId=$this->$model->insertData($insData,'customer_feedback');
                    foreach($feedback as $key =>$val)
                    {
                        $responseData=array(
                            'id_cus_feedback'   =>$insId,
                            'id_feedback'       =>$feedback[$key],
                            'feedback_response' =>$_POST['feedback_option_'.$i],
                        );
                    $i++;
                    $this->$model->insertData($responseData,'customer_feedback_response');
                    if($this->db->trans_status()===TRUE)
                    {
                        $this->db->trans_commit();
                        $response_data=array('status'=>TRUE,'message'=>'Feedback added successfully');
                        $this->session->set_flashdata('chit_alert',array('message'=>'Feedback added successfully','class'=>'success','title'=>'Add Feedback'));
                    }
                    else{
                        $this->db->trans_rollback();
                        $response_data=array('status'=>TRUE,'message'=>'Unable to proceed the requested process');
                        $this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Feedback'));
                    }
                }
                echo json_encode($response_data);
                
            break;

            default:
                        $SETT_MOD = self::SETT_MOD;
                        $list = $this->$model->ajax_getFeedback();   
                        $access = $this->$SETT_MOD->get_access('admin_ret_catalog/feedback/list');
                        $data = array(
                                            'list' =>$list,
                                            'access'=>$access
                                        );  
                        echo json_encode($data);
            
        }
    }
	//Feedback Master

	//Charges 
	function charges($type="",$id="")
	{
		$model=self::CAT_MODEL;
		
		switch($type)
  	    {
		        case "list":
		            
					       $data['main_content'] = "master/charges/list" ;
		                   $this->load->view('layout/template', $data);
	            break;
		 
		        case "Add":
	 	    
						   $charge_name=$this->input->post('charge_name');
						   $charge_code=$this->input->post('charge_code');
						   $charge_description=$this->input->post('charge_description');
						   $value_charge=$this->input->post('value_charge');
						   $tag_display=$this->input->post('tag_display');
						   $data = array (
									'name_charge' => $charge_name,
									'code_charge' => $charge_code,
									'description_charge' => $charge_description,
									'value_charge' => $value_charge,
									'tag_display'	=> $tag_display,
									'charge_tax'    => $this->input->post('charge_tax'),
									'created_on'	  => date("Y-m-d H:i:s"),
									'created_by'      => $this->session->userdata('uid'));
						   $this->db->trans_begin();
						   $this->$model->insertData($data,'ret_charges');
						   
							if($this->db->trans_status()===TRUE)
							 {
								$this->db->trans_commit();
								$data=array('status'=>true,'msg'=>'Charge Updated Successfully');
							 }
							 else
							 {
								$this->db->trans_rollback();
								 $data=array('status'=>true,'msg'=>'Unable To Proceed Your request');
							 }
							echo json_encode($data);
							
	 	        break;
		
		        case "edit":
		            
							$id_charge = $this->input->post('id_charge');
							$data=$this->$model->get_charges_list_edit($id_charge);
							echo json_encode($data);
					 
	 	        break;
		  
		        case "update":
		            
					        $name_charge=$this->input->post('name_charge');
							$charge_code=$this->input->post('charge_code');
							$description_charge=$this->input->post('description_charge');
							$value_charge=$this->input->post('value_charge');
							$id_charge=$this->input->post('id_charge');
							$tag_display=$this->input->post('tag_display');
		
							$data = array (
							'name_charge' => $name_charge,
							'code_charge' => $charge_code,
							'description_charge' => $description_charge,
							'value_charge' => $value_charge,
							'tag_display'	=> $tag_display,
							'charge_tax'    => $this->input->post('charge_tax'),
							'updated_on'	  => date("Y-m-d H:i:s"),
							'updated_by'      => $this->session->userdata('uid'));
							
							
							$id_branch_group=$this->$model->updateData($data, 'id_charge', $id_charge, 'ret_charges');
							echo json_encode($id_branch_group);
	 	              
					 
                break;
		 
		        case "delete":
		            
					
							$id_charge=$this->input->post('id_charge');		
							$data=$this->$model->deleteData('id_charge', $id_charge, 'ret_charges');
							echo json_encode($data);
					 
	 	        break;

				case "getActiveChargesList":

					$list=$this->$model->get_charges_list();
					echo json_encode($list);
				
				break;

		        default:
		
							$range['from_date']=$this->input->post("from_date");
							$range['to_date']=$this->input->post("to_date");
						    if($range['from_date'])
							{
								
								$list=$this->$model->get_charges_list_date($range['to_date'],$range['from_date']);
								
							  
							}
							else{
								$list=$this->$model->get_charges_list();
							   
							}
							
							$SETT_MOD = self::SETT_MOD;
							$access = $this->$SETT_MOD->get_access('admin_ret_catalog/charges/list');
							$data = array(
												'charges' =>$list,
												'access'=>$access
											);  
							echo json_encode($data);
		
		}
	}
	// Charges functions :: ENDS
	
	
	
	
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
	
	
		//ret sub design master
	public function ret_sub_design($type="",$id="",$status="")
	{
		$model=self::CAT_MODEL;
		switch($type)
		{
		    	case "add":		
						$data['subdesign']=$this->$model->get_empty_subproduct();
						$data['main_content'] = "master/ret_sub_design/form" ;
						$this->load->view('layout/template', $data);
				 break;
			case "save":
			        $short_code = $this->$model->genSubDesignShortCode();
			        $addData=$_POST['subdesign'];
			        //echo "<pre>";print_r($addData);exit;
	 				$data=array(
	 							'sub_design_name'	=> strtoupper($addData['sub_design_name']), 
								'sub_design_code'	=> $short_code,
								'status'	        => 1,
	 							'created_on'        => date("Y-m-d H:i:s"),
								'created_by'        => $this->session->userdata('uid')
							    );
	 				$this->db->trans_begin();
	 				$design_no=$this->$model->insertData($data,'ret_sub_design_master'); 
	 			   
		 			if($this->db->trans_status()===TRUE)
		             {
					 	$this->db->trans_commit();
					 	$this->session->set_flashdata('chit_alert',array('message'=>'New Sub Design added successfully','class'=>'success','title'=>'Add Sub Design'));
					 	redirect('admin_ret_catalog/ret_sub_design/list');	
					 }
					 else
					 {
					 	$this->db->trans_rollback();						 	
					 	$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Add Sub Design'));
					 	$return_data=array('message'=>'Unable to proceed the requested process','status'=>false);
					 	redirect('admin_ret_catalog/ret_sub_design/list');	
					 }
					
	 		break;
	 	case "edit":
	 			$data['subdesign'] = $this->$model->get_sub_design($id);
				$data['main_content'] = "master/ret_sub_design/form" ;
				$this->load->view('layout/template', $data);
	 		break; 
	 	
	 	case 'delete':
						 $this->db->trans_begin();
						 $this->$model->deleteData('id_sub_design',$id,'ret_sub_design_master');
				           if( $this->db->trans_status()===TRUE)
						    {
						    	  $this->db->trans_commit();
								  $this->session->set_flashdata('chit_alert', array('message' => 'Sub Design deleted successfully','class' => 'success','title'=>'Delete Sub Design'));	  
							}			  
						   else
						   {
							 $this->db->trans_rollback();
							 $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete Sub Design'));
						   }
						 redirect('admin_ret_catalog/ret_sub_design/list');	
				break;
		
		case 'sub_design_images':
		            $status= $this->$model->ret_sub_design_mapping_images($_POST['id_sub_design_mapping']);
		            echo json_encode($status);
		break;
		
		case 'get_karigar_products':
		            $result= $this->$model->get_karigar_products($_POST['id_sub_design_mapping']);
		            $data=array('id_karigar'  => $result);
					$karigars=$data;
		            echo json_encode($karigars);
		break;
		
		case 'sub_design_description':
		            $status= $this->$model->get_sub_design_mapping($_POST['id_sub_design_mapping']);
		            echo json_encode($status);
		break;
		
					
	 	case "update":
	 			
	 			$addData=$_POST['subdesign'];
 				$data=array(
 							'sub_design_name'	=> strtoupper($addData['sub_design_name']), 
							'updated_on'        => date("Y-m-d H:i:s"),
							'updated_by'        => $this->session->userdata('uid')
						    );
							   
	 			 $this->db->trans_begin();
			   
                $status = $this->$model->updateData($data,'id_sub_design',$id,'ret_sub_design_master');

                if($this->db->trans_status()===TRUE)
                {
                    $this->db->trans_commit();
                    $this->session->set_flashdata('chit_alert',array('message'=>'Sub Design Updated  successfully','class'=>'success','title'=>'Sub design'));
                }
                else
                {
                    $this->db->trans_rollback();						 	
                    $this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Sub design'));
                
                }
                redirect('admin_ret_catalog/ret_sub_design/list');	
	 		break;
	 		
	 		case 'active_list': 
					  	$data = $this->$model->getActiveSubDesigns();	  
						echo json_encode($data);
					    break;
	 		
			case 'list':
						$data['main_content'] = "master/ret_sub_design/list" ;
						$this->load->view('layout/template', $data);
			            break;
			
			case 'update_status':
			
					$data = array('status' => $status,'updated_on' => date("Y-m-d H:i:s"),'updated_by' => $this->session->userdata('uid'));
					$updstatus = $this->$model->updateData($data,'id_sub_design',$id,'ret_sub_design_master');
					if($updstatus)
					{
						$this->session->set_flashdata('chit_alert',array('message'=>'Sub design status updated as '.($status == 1 ? 'Active' : 'Inactive').' successfully.','class'=>'success','title'=>'Design  Status'));		 
					}	
					else
					{
						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Design  Status')); 
					}	
				   redirect('admin_ret_catalog/ret_sub_design/list');
				break;
						
			default:
						$SETT_MOD = self::SETT_MOD;
					  	$list = $this->$model->ajax_getSubDesign($_POST);	 
					  	$access = $this->$SETT_MOD->get_access('admin_ret_catalog/ret_sub_design/list');
				        $data = array(
				        					'list' =>$list,
											'access'=>$access
				        				);  
						echo json_encode($data);
		}
	}
	
	
	function get_ActiveDesign()
	{
	    $model=self::CAT_MODEL;
		$data=$this->$model->get_ActiveDesign();
		echo json_encode($data);
	}
	
	function update_design()
	{
			$model=self::CAT_MODEL;
			$reqdata =$this->input->post('req_data');
			$id_design =$this->input->post('id_design');
			
		  
			foreach($reqdata as $data)
			{
				if($this->$model->check_subdesign_maping($data['id_sub_design'],$id_design))
				{
					$insdata=array(
					'id_sub_design' =>$data['id_sub_design'],
					'design_no'     =>$id_design,
					);
					$this->db->trans_begin();
					$this->$model->insertData($insdata,'ret_sub_design_mapping');
				}
			}
			if($this->db->trans_status()===TRUE)
			{	
				$this->db->trans_commit();
				$status=array('status'=>true,'msg'=>'Design Mapped successfully');	
			}
			else
			{
				$this->db->trans_rollback();
				$status=array('status'=>false,'msg'=>'Unable to Proceed Your Request');	
			}
	  	   echo json_encode($status);
	}
	
	function update_design_products()
	{
			$model=self::CAT_MODEL;
			$reqdata =$this->input->post('req_data');
			$id_product =$this->input->post('id_product');
			
		  
			foreach($reqdata as $data)
			{
				if($this->$model->check_design_products_maping($data['design_no'],$id_product))
				{
					$insdata=array(
					'design_no' =>$data['design_no'],
					'pro_id'    =>$id_product,
					);
					$this->db->trans_begin();
					$this->$model->insertData($insdata,'ret_design_products');
				}
			}
			if($this->db->trans_status()===TRUE)
			{	
				$this->db->trans_commit();
				$status=array('status'=>true,'msg'=>'Design Mapped successfully');	
			}
			else
			{
				$this->db->trans_rollback();
				$status=array('status'=>false,'msg'=>'Unable to Proceed Your Request');	
			}
	  	   echo json_encode($status);
	}
	
	
	
	//Product Mapping
	public function ret_products_mapping($type="",$id="",$status=""){
		$model=self::CAT_MODEL;
		switch($type)
		{
			case 'list':
						$data['main_content'] = "master/ret_product/product_mapping" ;
						$this->load->view('layout/template', $data);
					    break;
					    
			case 'delete':
				 $this->db->trans_begin();
				 $this->$model->deleteData('mapping_id',$id,'ret_product_mapping');
		           if( $this->db->trans_status()===TRUE)
				    {
				    	  $this->db->trans_commit();
						  $this->session->set_flashdata('chit_alert', array('message' => 'Design Maping Deleted successfully','class' => 'success','title'=>'Design Mapping'));	  
					}			  
				   else
				   {
					 $this->db->trans_rollback();
					 $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Design Mapping'));
				   }
				 redirect('admin_ret_catalog/ret_products_mapping/list');	
				break;
		
			
			default:
						$SETT_MOD = self::SETT_MOD;
						$product = $this->$model->ajax_ProductMapingDetails($_POST);	 
					  	$access = $this->$SETT_MOD->get_access('admin_ret_catalog/ret_products_mapping/list');
				        $data = array(
				        					'product' =>$product,
											'access'=>$access
				        			 );  
						echo json_encode($data);
		}
	}
	
	

	function update_product_design_mapping()
	{
	    $model=self::CAT_MODEL;
	    $id_product     = $this->input->post('id_product');
	    $designs      = $this->input->post('id_design');
	   
	    foreach($designs as $id_design)
	    {
	        if($this->$model->check_products_design_maping($id_product,$id_design))
    	    {
                $insdata=array(
                'id_design'         =>$id_design,
                'pro_id'            =>$id_product,
                );
                $this->db->trans_begin();
                $this->$model->insertData($insdata,'ret_product_mapping');
                //print_r($this->db->last_query());exit;
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
	
	
	function delete_product_design_mapping()
	{
	    $model=self::CAT_MODEL;
	    $reqdata =$this->input->post('req_data');
	   
	    foreach($reqdata as $items)
	    {
	       $this->db->trans_begin();
	       $this->$model->deleteData('mapping_id',$items['mapping_id'],'ret_product_mapping');
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
	

	
	public function ret_subdesign_mapping($type="",$id="",$status=""){
		$model=self::CAT_MODEL;
		switch($type)
		{
			case 'list':
						$data['main_content'] = "master/ret_product/subdesign_mapping" ;
						$this->load->view('layout/template', $data);
					    break;
					    
			case 'delete':
				 $this->db->trans_begin();
				 $this->$model->deleteData('id_sub_design_mapping',$id,'ret_sub_design_mapping');
		           if( $this->db->trans_status()===TRUE)
				    {
				    	  $this->db->trans_commit();
						  $this->session->set_flashdata('chit_alert', array('message' => 'Design Maping Deleted successfully','class' => 'success','title'=>'Sub Design Mapping'));	  
					}			  
				   else
				   {
					 $this->db->trans_rollback();
					 $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Sub Design Mapping'));
				   }
				 redirect('admin_ret_catalog/ret_subdesign_mapping/list');	
				break;
		
			
			default:
						$SETT_MOD = self::SETT_MOD;
						$product = $this->$model->ajax_subDesignMapingDetails($_POST);	 
					  	$access = $this->$SETT_MOD->get_access('admin_ret_catalog/ret_subdesign_mapping/list');
				        $data = array(
				        					'product' =>$product,
											'access'=>$access
				        			 );  
						echo json_encode($data);
		}
	}
	
	
	function update_sub_design_image()
	{
	    $model=self::CAT_MODEL;
	    $subdesign_images      = $_POST['subdesign']['subdesign_images'];
	    $id_sub_design_mapping = $_POST['subdesign']['id_sub_design_mapping'];
	    $p_ImgData = json_decode($subdesign_images); 
	    //echo "<pre>";print_r($p_ImgData);exit;
         $is_defalut_img='';
         $is_default=0;
          $this->$model->deleteData('id_sub_design_mapping',$id_sub_design_mapping,'ret_sub_design_mapping_images');
         if(sizeof($p_ImgData) > 0){
    			foreach($p_ImgData as $key => $precious){
    			    
    			    if($precious->is_default==1)
    			    {
    			        $is_defalut_img=$key;
    			    }
    				$imgFile = $this->base64ToFile($precious->src);
    				$_FILES['subdesign_images'][] = $imgFile;
    			}
    		} 
    		 
    		if(!empty($p_ImgData)){
    		    $folder =  self::IMG_PATH."sub_design/".$id_sub_design_mapping; 
    		    if (!is_dir($folder)) {  
    				mkdir($folder, 0777, TRUE);
    			} 
    		    if(isset($_FILES['subdesign_images']))
    		    {
    		        foreach($_FILES['subdesign_images'] as $file_key => $file_val)
    		        {
    		            if($file_val['name'])
    		            {
    		                $img_name =  "$id_sub_design_mapping_". mt_rand(100001,999999).".jpg";
    		                $path = $folder."/".$img_name; 
    		                $result=$this->upload_img('image',$path,$file_val['tmp_name']);
    		                if($result)
    		                {   
    		                     if($is_defalut_img==$file_key)
    		                     {
    		                          $is_default=1;
    		                     }else{
    		                         $is_default=0;
    		                     }
    		                     $imgData=array('is_default'=>$is_default,'id_sub_design_mapping'=>$id_sub_design_mapping,'image_name'=>$img_name);
    		                     $this->$model->insertData($imgData,'ret_sub_design_mapping_images'); 
    		                }
    		            }
    		        }
    		    }
    		}
    		if($this->db->trans_status()===TRUE)
    		{	
    			$this->db->trans_commit();
    			$status=array('status'=>true,'msg'=>'Image Updated Successfully..');	
    		}
    		else
    		{
    			$this->db->trans_rollback();
    			$status=array('status'=>false,'msg'=>'Unable to Proceed Your Request');	
    		}
      	   echo json_encode($status);
	}
	
	function update_subdesign_mapping()
	{
	    $model=self::CAT_MODEL;
	    $id_product     = $this->input->post('id_product');
	    $id_design     = $this->input->post('id_design');
	    $sub_designs      = $this->input->post('id_sub_design');
	    
	   
	    foreach($sub_designs as $id_sub_design)
	    {
	        if($this->$model->check_sub_design_mapping($id_product,$id_design,$id_sub_design))
    	    {
                $insdata=array(
                'id_design'     =>$id_design,
                'id_product'    =>$id_product,
                'id_sub_design' =>$id_sub_design,
                );
                $this->db->trans_begin();
                $insId=$this->$model->insertData($insdata,'ret_sub_design_mapping');
    	    }
	    }
	   
        if($this->db->trans_status()===TRUE)
		{	
			$this->db->trans_commit();
			$status=array('status'=>true,'msg'=>'Sub Design Mapped successfully');	
		}
		else
		{
			$this->db->trans_rollback();
			$status=array('status'=>false,'msg'=>'Unable to Proceed Your Request');	
		}
  	   echo json_encode($status);
	}
	
	public function update_subdesign_des()
	{
	    $model=self::CAT_MODEL;
	    	
		$id_sub_design_mapping = $this->input->post('id_sub_design_mapping');
		$description = $this->input->post('description');
		$data_des['description'] = $description;
		
		$status=$this->$model->updateData($data_des,'id_sub_design_mapping',$id_sub_design_mapping,'ret_sub_design_mapping');
	//	print_r($this->db->last_query());exit;
	    if($status)
	    {
	        $responseData=array('status'=>TRUE,'message'=>'Description Added Successfully..');
	    }else{
	        $responseData=array('status'=>FALSE,'message'=>'Description Added Successfully..');

	    }
	    echo json_encode($responseData);
	}
	
	public function update_karigar_products()
	{
	    $model=self::CAT_MODEL;
	    	
		$id_sub_design_mapping = $this->input->post('id_sub_design_mapping');
		$karigars = $this->input->post('id_karigar');
		
		$this->$model->deleteData('id_sub_design_mapping',$id_sub_design_mapping,'ret_karigar_products');
		foreach($karigars as $id_karigar)
		{
		    $data=array('id_karigar'=>$id_karigar,'id_sub_design_mapping'=>$id_sub_design_mapping);
		    $this->db->trans_begin();
    		$this->$model->insertData($data,'ret_karigar_products');
		}
		
	    if($this->db->trans_status()===TRUE)
		{	
			$this->db->trans_commit();
			$responseData=array('status'=>true,'message'=>'Karigar Mapped successfully');	
		}
		else
		{
			$this->db->trans_rollback();
			$responseData=array('status'=>false,'message'=>'Unable to Proceed Your Request');	
		}
	    echo json_encode($responseData);
	}
	
	function delete_sub_design_mapping()
	{
	    $model=self::CAT_MODEL;
	    $reqdata =$this->input->post('req_data');
	   
	    
	    foreach($reqdata as $items)
	    {
	       $this->db->trans_begin();
	       $this->$model->deleteData('id_sub_design_mapping',$items['id_sub_design_mapping'],'ret_sub_design_mapping');
	    }
	   
        if($this->db->trans_status()===TRUE)
		{	
			$this->db->trans_commit();
			$status=array('status'=>true,'msg'=>'Sub Design Mapped Deleted successfully');	
		}
		else
		{
			$this->db->trans_rollback();
			$status=array('status'=>false,'msg'=>'Unable to Proceed Your Request');	
		}
  	   echo json_encode($status);
	}
	
	
	function get_ProductDesign()
	{
	     $model=self::CAT_MODEL;
	     $data=$this->$model->get_ProductDesign($_POST);
	     echo json_encode($data);
	}
	
	public function getSubDesignName(){
		$model=self::CAT_MODEL;
		$data = $this->$model->getSubDesignName($_POST['sub_design_name']);	  
		echo json_encode($data);
	}
	
	public function getDesignName(){
		$model=self::CAT_MODEL;
		$data = $this->$model->getDesignName($_POST['design_name']);	  
		echo json_encode($data);
	}
	
	//Product Mapping
	
	
		//GET DESIGN PRODUCTS
	function get_active_design_products()
	{
	     $model=self::CAT_MODEL;
	     $data=$this->$model->get_active_design_products($_POST);
	     echo json_encode($data);
	}
	
	function get_ActiveSubDesigns()
	{
	     $model=self::CAT_MODEL;
	     $data=$this->$model->get_ActiveSubDesigns($_POST);
	     echo json_encode($data);
	}
	//GET DESIGN PRODUCTS
	
	
	
	//V.A / M.C Attribute Settings
	function get_DesignSettingsDetails()
	{
	     $model=self::CAT_MODEL;
	     $data=$this->$model->get_DesignSettingsDetails($_POST);
	     echo json_encode($data);
	}
	
	function get_wastage_details($id)
	{
	     $model=self::CAT_MODEL;
         $data=$this->$model->get_wastage_details($id);
         echo json_encode($data);
	}

	function get_design_attr_values($id)
	{
	     $model=self::CAT_MODEL;
         $data=$this->$model->get_design_attr_values($id);
         echo json_encode($data);
	}

	function delete_design_attribute($attr_des_id)
	{
		$model=self::CAT_MODEL;
		$status = $this->$model->deleteData('attr_des_id',$attr_des_id,'ret_design_attributes');

		if($status == 1)
			$msg = "Attribute deleted successfully";
		else
			$msg = "Error occured. Please try again";

		$responseData =	array('status'=> $status == 1 ? TRUE : FALSE, 'msg'=> $msg);
		echo json_encode($responseData);
	}

	function delete_design_weight_range($wastage_des_id)
	{
		$model=self::CAT_MODEL;
		$status = $this->$model->deleteData('id_wc',$wastage_des_id,'ret_design_weight_range_wc');

		if($status == 1)
			$msg = "Weight range deleted successfully";
		else
			$msg = "Error occured. Please try again";

		$responseData =	array('status'=> $status == 1 ? TRUE : FALSE, 'msg'=> $msg);
		echo json_encode($responseData);
	}
	//V.A / M.C Attribute Settings

	// Attributes 

	public function attribute($type="",$id="",$status="")
    {
		$model=self::CAT_MODEL;
		$set_model = self::SETT_MOD;
		switch($type)
		{
			case 'add':

					$data['main_content'] = "master/attribute/form" ;
					$data['attr']	= $this->$model->get_empty_attribute();
					$this->load->view('layout/template', $data);
					break;

			case 'edit':

					$data['attr'] = $this->$model->get_attribute($id);
					$data['attr_val'] = $this->$model->get_attribute_values($id);
					//echo"<pre>"; print_r($data); echo"</pre>";exit;
					$data['main_content'] = "master/attribute/form" ;
					$this->load->view('layout/template', $data);
					break;

	 		case "save":

					$addData=$_POST['attr'];
					//echo"<pre>"; print_r($addData); echo "</pre>";  exit;
					$data=array(
						'attr_name'	           => strtoupper($addData['attr_name']),
						'attr_status'          => $addData['attr_status'],
						'created_on'           => date("Y-m-d H:i:s"),
						'created_by'           => $this->session->userdata('uid')
					);
					//echo"<pre>"; print_r($data);exit;
	 				
					$this->db->trans_begin();
					$id_attribute	=	$this->$model->insertData($data,'ret_attribute');

					//print_r($this->db->last_query());exit;

					foreach($addData['attr_val'] as $wkey => $wval) {

						$attr = array(
							'attr_id'	=>	$id_attribute,
							'attr_val' 	=>	$wval
						);

						$this->$model->insertData($attr,'ret_attribute_values');
					}
					if($this->db->trans_status()===TRUE) {
						$this->db->trans_commit();
						$result = array('message'=>'New Attribute added successfully!..','class'=>'success','title'=>'Add Attribute : ');
						$this->session->set_flashdata('chit_alert', array('message' => 'New Attribute added successfully!..','class' => 'success','title'=>'Add Attribute'));

					} else {
						$this->db->trans_rollback();						 	
						$result = array('message'=>'Unable to proceed the requested process..','class'=>'danger','title'=>'Add Attribute : ');
						$this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Add Attribute'));

					}

					redirect('admin_ret_catalog/attribute/list');	
					break;

			case 'delete':

					$this->db->trans_begin();
					$this->$model->deleteData('attr_id',$id,'ret_attribute_values');
					$this->$model->deleteData('attr_id',$id,'ret_attribute');

					if( $this->db->trans_status()===TRUE) {
						$this->db->trans_commit();
						$this->session->set_flashdata('chit_alert', array('message' => 'Attribute deleted successfully','class' => 'success','title'=>'Delete Attribute'));

					} else {
						$this->db->trans_rollback();
						$this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete Attribute'));

					}

					redirect('admin_ret_catalog/attribute/list');	
					break;

			case "update":

					
				$addData=$_POST['attr'];
				//echo"<pre>"; print_r($addData);
				$data=array(
					'attr_name'	           => strtoupper($addData['attr_name']),
					'attr_status'          => $addData['attr_status'],
					'updated_on'           => date("Y-m-d H:i:s"),
					'updated_by'           => $this->session->userdata('uid')
				);

				$this->db->trans_begin();

				$this->$model->updateData($data,'attr_id',$id,'ret_attribute'); 

				$attr_values = $this->$model->get_attribute_values($id);

				$diff_data = array_diff_key($addData['attr_val'], $addData['attr_val_id']);

				foreach($attr_values as $vals) {

					$curr_val_id = $vals['attr_val_id'];

					$has_attr_val = false;

					foreach($addData['attr_val_id'] as $akey => $avalue) {

						if($addData['attr_val_id'][$akey] == $curr_val_id) {

							$has_attr_val = true;

							$data = array(
										'attr_val'	=> $addData['attr_val'][$akey]
									);

							$this->$model->updateData($data,'attr_val_id',$curr_val_id,'ret_attribute_values');

							break;

						}

					}

					if($has_attr_val == false) {

						$this->$model->deleteData('attr_val_id',$curr_val_id,'ret_attribute_values');

					}

				}

				foreach($diff_data as $insVal) {

					$attr = array(
						'attr_id'	=>	$id,
						'attr_val' 	=>	$insVal
					);

					$this->$model->insertData($attr,'ret_attribute_values');
				}

				if($this->db->trans_status()===TRUE)
				{
					$this->db->trans_commit();
					$this->session->set_flashdata('chit_alert',array('message'=>'Attribute record modified successfully','class'=>'success','title'=>'Edit Attribute'));
				}
				else
				{
					$this->db->trans_rollback();						 	
					$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Edit Attribute'));
				}
				redirect('admin_ret_catalog/attribute/list');	
				break;

			case 'update_status':

					$data = array(
						'attr_status' => $status,
						'updated_on'	  => date("Y-m-d H:i:s"),
						'updated_by'   => $this->session->userdata('uid')
					);

					$model=self::CAT_MODEL;
					$updstatus = $this->$model->updateData($data,'attr_id',$id,'ret_attribute');
					
					//print_r($this->db->last_query());exit;
					if($updstatus) {

						$this->session->set_flashdata('chit_alert',array('message'=>'Attribute status updated as '.($status==1 ? 'Approved' : 'Not Approved').' successfully.','class'=>'success','title'=>'Attribute Status'));
						echo 1;

					} else {

						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Attribute Status'));
						echo 0;

					}
					redirect('admin_ret_catalog/attribute/list');
					break;

			case 'active_list':

					$data = $this->$model->getActiveAttribute();	  
					echo json_encode($data);
					break;

			case 'activeValues_list':

					$attr_id=$_POST['attr_id'];
					$data = $this->$model->get_attribute_values($attr_id);	  
					echo json_encode($data);
					break;

			case 'get_attribute_with_values':

					$attr_id = isset($_POST['attr_id']) && $_POST['attr_id'] > 0 ? $_POST['attr_id'] : 0;
					$status = 1;
					$data = $this->$model->get_attribute_with_values($attr_id, $status);	  
					echo json_encode($data);
					break;

			case 'list':

					$data['main_content'] = "master/attribute/list" ;
					$this->load->view('layout/template', $data);
					break;

			default:

					$SETT_MOD = self::SETT_MOD;
					$range['from_date']  = $this->input->post('from_date');
					$range['to_date']  = $this->input->post('to_date');
					$attribute 	= $this->$model->ajax_getattribute($range['from_date'],$range['to_date']);	 
					$access 	= $this->$SETT_MOD->get_access('admin_ret_catalog/attribute/list');
					$data = array(
								'attribute' =>	$attribute,
								'access'	=>	$access
							);
					echo json_encode($data);

		}
	}

	// Attributes 
	
	
		//Product,Design,Sub Design Filter
	function get_ActiveProducts()
	{
	     $model=self::CAT_MODEL;
         $data=$this->$model->get_ActiveProducts($_POST);
         echo json_encode($data);
	}
	
	
	function get_MetalCategory()
	{
	    $model=self::CAT_MODEL;
         $data=$this->$model->get_MetalCategory($_POST);
         echo json_encode($data);
	}
	
	function get_NonTagProducts()
	{
	    $model=self::CAT_MODEL;
        $data=$this->$model->get_NonTagProducts($_POST);
        echo json_encode($data);
	}
	
	
	//Product,Design,Sub Design Filter
	
	
	
	//Karigar V.A Details
	function get_karigar_wise_wastage()
	{
	    $model=self::CAT_MODEL;
	    $id_kaigar = $_POST['id_karigar'];
        $data['approved_details']=$this->$model->get_karigar_wise_wastage($id_kaigar);
        $data['yet_approved_details']=$this->$model->karigar_waiting_for_app_products($id_kaigar);
        echo json_encode($data);
	}
	//Karigar V.A Details


	//Karigar stone Details
	function get_karigar_wise_stones()
	{
	    $model=self::CAT_MODEL;
        $data=$this->$model->get_karigar_wise_stones();
        echo json_encode($data);
	}
	//Karigar stone Details
	
	//karigar charges Details
	function get_karigar_wise_charges()
	{
	    $model=self::CAT_MODEL;
        $data=$this->$model->get_karigar_wise_charges();
        echo json_encode($data);
	}

	//karigar charges Details
	
	//Karigar Product Mapping
	public function ret_karigar_product($type="",$id="",$status="")
	{
		$model=self::CAT_MODEL;
		switch($type)
		{
			case 'list':
                $data['main_content']="master/karigar/ret_product_mapping";
                $this->load->view('layout/template',$data);
            break;
            
            case 'update_status':
                
                if($status==1)
				{
				    $karigar_pro=$this->$model->get_karigar_products($id);
				}
				
				$data = array('status' => $status,'updated_on' => date("Y-m-d H:i:s"),'updated_by' => $this->session->userdata('uid'));
				$updstatus = $this->$model->updateData($data,'id_karigar_product',$id,'ret_karigar_products');
				if($updstatus)
				{
					$this->session->set_flashdata('chit_alert',array('message'=>'Sub design status updated as '.($status == 1 ? 'Active' : 'Inactive').' successfully.','class'=>'success','title'=>'Design  Status'));		 
				}	
				else
				{
					$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Design  Status')); 
				}	
			   redirect('admin_ret_catalog/ret_karigar_product/list');
			break;
				
			default:
            $SETT_MOD = self::SETT_MOD;
            $list = $this->$model->get_karigar_product_mapping($_POST);   
            $access = $this->$SETT_MOD->get_access('admin_ret_catalog/ret_karigar_product/list');
            $data = array(
                                'list' =>$list,
                                'access'=>$access
                            );  
            echo json_encode($data);
		}					
	}
	
	
	
	function update_product_mapping()
	{
	    $model=self::CAT_MODEL;
	    $id_product     = $this->input->post('id_product');
	    $id_design      = $this->input->post('id_design');
	    $karigar      = $this->input->post('id_karigar');
	   
	    foreach($karigar as $kid)
	    {
	        if($this->$model->check_design_products_maping($id_product,$id_design,$kid))
    	    {
                $insdata=array(
                'id_karigar' =>$kid,
                'id_product' =>$id_product,
                'id_design'  =>$id_design,
                'created_by' =>$this->session->userdata('uid'),
                'created_on' =>date("Y-m-d H:i:s"),
                );
                $this->db->trans_begin();
                $this->$model->insertData($insdata,'ret_karigar_products');
                //print_r($this->db->last_query());exit;
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
	
	function delete_product_mapping()
	{
	    $model=self::CAT_MODEL;
	    $reqdata =$this->input->post('req_data');
	   
	    foreach($reqdata as $items)
	    {
	       $this->db->trans_begin();
	       $this->$model->deleteData('id_karigar_product',$items['id_karigar_product'],'ret_karigar_products');
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
	
	//Karigar Product Mapping
	
	
	//web registered devices
    function web_devices($type="",$id="")
    {
        $model = self::CAT_MODEL;
        switch($type){
            case "edit":
                if($id!=NULL)
                {
                $data['device'] = $this->$model->web_device_settingDB('get',$id);
                }
                else
                {
                $data['device'] = $this->$model->web_device_settingDB();					
                }
                $data['main_content'] = self::MAS_VIEW.'web_devices/form';
                $this->load->view('layout/template', $data);  
            break;
            
            case 'add':
                $data['main_content'] = self::MAS_VIEW.'web_devices/form';
                $this->load->view('layout/template', $data);  
            break;
            
            case "list":
                $data['main_content'] = self::MAS_VIEW.'web_devices/list';
                $this->load->view('layout/template', $data);  
            break;
            
            case "save":
                $addData = $this->input->post('device');
               
                $insData=array(
                                'device_name'   =>strtoupper($addData['device_name']),
                                'token_id'      =>$addData['token_id'],
                                'browser'       =>strtoupper($addData['browser']),
                                'id_branch'     =>$addData['id_branch'],
                                'id_counter'   =>$addData['id_counter'],
                                'id_floor'     =>$addData['id_floor'],
                              );
                //inserting data  
                $this->db->trans_begin();
                $status = $this->$model->web_device_settingDB("insert","",$insData);
                if($this->db->trans_status()===TRUE)
                {
                $this->db->trans_commit();
                $this->session->set_flashdata('chit_alert', array('message' => 'Device Added successfully','class' => 'success','title'=>'Device List'));
                }
                else
                {
                $this->db->trans_rollback();						 	
                $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed the requested operation','class' => 'danger','title'=>'Device List'));
                }
                redirect('admin_ret_catalog/web_devices/list');               
            break;
            case "update":
                   $addData = $this->input->post('device');
                    
                    $insData=array(
                    'device_name'   =>strtoupper($addData['device_name']),
                    'token_id'      =>$addData['token_id'],
                    'browser'       =>strtoupper($addData['browser']),
                    'id_branch'     =>$addData['id_branch'],
                    'id_counter'    =>$addData['id_counter'],
                    'id_floor'     =>$addData['id_floor'],
                    );                
                    $this->db->trans_begin();                
                    $status = $this->$model->web_device_settingDB("update",$id,$insData);
                    if($this->db->trans_status()===TRUE)
                    {
                        $this->db->trans_commit();
                        $this->session->set_flashdata('chit_alert', array('message' => 'Device Updated successfully','class' => 'success','title'=>'Device List'));
                    }
                    else
                    {
                        $this->db->trans_rollback();						 	
                        $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed the requested operation','class' => 'danger','title'=>'Device List'));
                    }
                    redirect('admin_ret_catalog/web_devices/list');
            break;
            case 'Delete':
                $status = $this->$model->web_device_settingDB("delete",$id);
                if($status)
                {
                $this->session->set_flashdata('chit_alert', array('message' => 'Village Records deleted successfully','class' => 'success','title'=>'Category Records'));
                }else{
                $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed the requested operation','class' => 'danger','title'=>'Category Records'));
                }
                redirect('settings/village'); 
                break;				  
            default:
                $list = $this->$model->get_DeviceList();
                $access = $this->admin_settings_model->get_access('admin_ret_catalog/web_devices/list');
                $data = array(
                'list'  => $list,
                'access'=> $access
                );  
                echo json_encode($data);
            break;
        }
    }
    
    function get_ActiveCounter()
	{
	    $model=self::CAT_MODEL;
        $data=$this->$model->get_ActiveCounter($_POST);
        echo json_encode($data);
	}
	
	function get_ActiveBranchFloor()
	{
	    $model=self::CAT_MODEL;
        $data=$this->$model->get_ActiveBranchFloor($_POST);
        echo json_encode($data);
	}
	
    //web registered devices
    
    
    //collection
    
    public function ret_collection($type="",$id="",$status="")
	{
		$model=self::CAT_MODEL;
		switch($type)
		{
			case 'list':
                $data['main_content']="master/ret_collection/list";
                $this->load->view('layout/template',$data);
            break;
            
            case "save":
                $addData = $_POST;
                $insData=array(
                                'collection_name' =>strtoupper($addData['collection_name']),
                                'created_on'      =>date("Y-m-d H:i:s"),
						        'created_by'      =>$this->session->userdata('uid')
                               
                              );
                //inserting data  
                $this->db->trans_begin();
                $this->$model->insertData($insData,'ret_collection_master'); 
                if($this->db->trans_status()===TRUE)
                {
                    $this->db->trans_commit();
                    //$this->session->set_flashdata('chit_alert', array('message' => 'Collection Added successfully','class' => 'success','title'=>'Collection List'));
                    $responsData=array('status'=>TRUE,'message'=>'Collection Added successfully');
                }
                else
                {
                    $this->db->trans_rollback();						 	
                    //$this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed the requested operation','class' => 'danger','title'=>'Collection List'));
                    $responsData=array('status'=>FALSE,'message'=>'Collection Added successfully');
                }
                echo json_encode($responsData);            
            break;
            
            case "update":
                $addData = $_POST;
                $updData=array(
                                'collection_name' =>strtoupper($addData['collection_name']),
                                'updated_on'      =>date("Y-m-d H:i:s"),
						        'updated_by'      =>$this->session->userdata('uid')
                               
                              );
                //inserting data  
                $this->db->trans_begin();
                $updstatus = $this->$model->updateData($updData,'id_collection',$id,'ret_collection_master');
                if($this->db->trans_status()===TRUE)
                {
                    $this->db->trans_commit();
                    $responsData=array('status'=>TRUE,'message'=>'Collection Updated successfully');
                }
                else
                {
                    $this->db->trans_rollback();						 	
                    $responsData=array('status'=>FALSE,'message'=>'Collection Updated successfully');
                }
                echo json_encode($responsData);            
            break;
            
            case 'update_status':
                
				$data = array('status' => $status,'updated_on' => date("Y-m-d H:i:s"),'updated_by' => $this->session->userdata('uid'));
				
				$updstatus = $this->$model->updateData($data,'id_collection',$id,'ret_collection_master');
				
				if($updstatus)
				{
					$this->session->set_flashdata('chit_alert',array('message'=>'Sub design status updated as '.($status == 1 ? 'Active' : 'Inactive').' successfully.','class'=>'success','title'=>'Collection'));		 
				}	
				else
				{
					$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Collection')); 
				}	
			   redirect('admin_ret_catalog/ret_collection/list');
			break;
			
			case 'Delete':
                $this->db->trans_begin();
                $this->$model->deleteData('id_collection',$id,'ret_collection_master');
                if( $this->db->trans_status()===TRUE)
                {
                    $this->db->trans_commit();
                    $this->session->set_flashdata('chit_alert', array('message' => 'Collection deleted successfully','class' => 'success','title'=>'Collection'));	  
                }			  
                else
                {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Collection'));
                }
                redirect('admin_ret_catalog/ret_collection/list');	
			break;
			
			case 'edit':
					$data=$this->$model->get_colection($id);
					echo json_encode($data);
			break;
					
				
			default:
            $SETT_MOD = self::SETT_MOD;
            $list = $this->$model->ajax_collection_master($_POST);   
            $access = $this->$SETT_MOD->get_access('admin_ret_catalog/ret_collection/list');
            $data = array(
                                'list' =>$list,
                                'access'=>$access
                            );  
            echo json_encode($data);
		}					
	}
	
    //collection
    
    
     //repair master
    
    public function repair_master($type="",$id="",$status="")
	{
		$model=self::CAT_MODEL;
		switch($type)
		{
			case 'list':
                $data['main_content']="master/ret_repair_master/list";
                $this->load->view('layout/template',$data);
            break;
            
            case "save":
                $addData = $_POST;
                $insData=array(
                                'name'        =>strtoupper($addData['name']),
                                'created_date'=>date("Y-m-d H:i:s"),
						        'created_by'  =>$this->session->userdata('uid')
                               
                              );
                //inserting data  
                $this->db->trans_begin();
                $this->$model->insertData($insData,'ret_repair_master'); 
                if($this->db->trans_status()===TRUE)
                {
                    $this->db->trans_commit();
                    $responsData=array('status'=>TRUE,'message'=>'Repair Added successfully');
                }
                else
                {
                    $this->db->trans_rollback();						 	
                    $responsData=array('status'=>FALSE,'message'=>'Repair Added successfully');
                }
                echo json_encode($responsData);            
            break;
            
            case "update":
                $addData = $_POST;
                $updData=array(
                                'name'         =>strtoupper($addData['name']),
                                'updated_date' =>date("Y-m-d H:i:s"),
						        'updated_by'   =>$this->session->userdata('uid')
                               
                              );
                //inserting data  
                $this->db->trans_begin();
                $updstatus = $this->$model->updateData($updData,'id_repair_master',$id,'ret_repair_master');
                if($this->db->trans_status()===TRUE)
                {
                    $this->db->trans_commit();
                    $responsData=array('status'=>TRUE,'message'=>'Repair Updated successfully');
                }
                else
                {
                    $this->db->trans_rollback();						 	
                    $responsData=array('status'=>FALSE,'message'=>'Repair Updated successfully');
                }
                echo json_encode($responsData);            
            break;
            
            case 'update_status':
                
				$data = array('status' => $status,'updated_on' => date("Y-m-d H:i:s"),'updated_by' => $this->session->userdata('uid'));
				
				$updstatus = $this->$model->updateData($data,'id_repair_master',$id,'ret_repair_master');
				
				if($updstatus)
				{
					$this->session->set_flashdata('chit_alert',array('message'=>'Repair status updated as '.($status == 1 ? 'Active' : 'Inactive').' successfully.','class'=>'success','title'=>'Repair'));		 
				}	
				else
				{
					$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Repair')); 
				}	
			   redirect('admin_ret_catalog/repair_master/list');
			break;
			
			case 'Delete':
                $this->db->trans_begin();
                $this->$model->deleteData('id_collection',$id,'ret_collection_master');
                if( $this->db->trans_status()===TRUE)
                {
                    $this->db->trans_commit();
                    $this->session->set_flashdata('chit_alert', array('message' => 'Repair deleted successfully','class' => 'success','title'=>'Repair'));	  
                }			  
                else
                {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Repair'));
                }
                redirect('admin_ret_catalog/repair_master/list');	
			break;
			
			case 'edit':
					$data=$this->$model->get_repair_items($id);
					echo json_encode($data);
			break;
					
				
			default:
            $SETT_MOD = self::SETT_MOD;
            $list = $this->$model->ajax_repair_master($_POST);   
            $access = $this->$SETT_MOD->get_access('admin_ret_catalog/repair_master/list');
            $data = array(
                                'list' =>$list,
                                'access'=>$access
                            );  
            echo json_encode($data);
		}					
	}
	
    //repair master
	
	public function product_division($type="",$id=""){
		$model=self::CAT_MODEL;
		$date_time = date("Y-m-d H:i:s");
		switch($type)
		{
			case "Add":
	 				$pd= $this->input->post("div_value");
	 				$data=array('div_value'=>$pd , 'status'=>1 , 'created_on' => $date_time, 'created_by'=>$this->session->userdata('uid'));
	 				$this->db->trans_begin();
	 				$this->$model->insert_prod_division($data); 
	 			if($this->db->trans_status()===TRUE)
	             {
				 	$this->db->trans_commit();
				 	$this->session->set_flashdata('chit_alert',array('message'=>'New product division added successfully','class'=>'success','title'=>'Add product division'));
				 }
				 else
				 {
				 	 $this->db->trans_rollback();						 	
				 	$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Add product division'));
				 	
				 }
				redirect('product_division/list');
	 				
	 		break;
	 	case "Edit":
	 			$data['product_division'] = $this->$model->get_prod_division($id);
	 			echo json_encode($data['product_division']);
	 		break; 
	 	
	 	case 'Delete':
				$this->db->trans_begin();
				$this->$model->delete_prod_division($id);
				if( $this->db->trans_status()===TRUE)
				{
					$this->db->trans_commit();
					$this->session->set_flashdata('chit_alert', array('message' => 'Product division deleted successfully','class' => 'success','title'=>'Delete product division'));
				}			  
				else
				{
					$this->db->trans_rollback();
					$this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete product division'));
				}
				redirect('product_division/list');	
				break;
	 	case "Update":

				$pd= $this->input->post("div_value");
	 			
				$data=array("div_value"=>$pd , 'status'=>1 , 'updated_on' => $date_time, 'updated_by'=>$this->session->userdata('uid'));

				$this->db->trans_begin();

				$this->$model->update_prod_division($data,$id);
				
				if($this->db->trans_status()===TRUE)
				{
					$this->db->trans_commit();
				
					$this->session->set_flashdata('chit_alert',array('message'=>'Product division record modified successfully','class'=>'success','title'=>'Edit product division'));

					redirect('product_division/list');	
				
				}
				else
				{
					$this->db->trans_rollback();						 	
					
					$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Edit product division'));
					
					redirect('product_division/list');	
				}

	 		break;
	 		
			case 'List':

					$data['main_content'] = "master/product_division/list" ;

					$this->load->view('layout/template', $data);

					break;

			default:
					$SETT_MOD = self::SETT_MOD;
					$pd = $this->$model->ajax_getprod_division();	 
					$access = $this->$SETT_MOD->get_access('product_division/list');
					$data = array(
									'product_division' 	=>	$pd,
									'access'	=>	$access
								);
					echo json_encode($data);
		}
	}

	function product_division_status($status,$id)
	{
		$data = array('status' => $status);

		$model=self::CAT_MODEL;

		$updstatus = $this->$model->update_prod_division($data,$id);

		if($updstatus)
		{
			$this->session->set_flashdata('chit_alert',array('message'=>'Product division status updated as '.($status ==1 ? 'Active' : 'Inactive').' successfully.','class'=>'success','title'=>'Product Division Status'));			
		}	
		else
		{
			$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Product Division Status'));
		}

		redirect('product_division/list');	
	}
	
	
	//Metal Rate Purity
	public function MetalSelect(){
		$model=self::CAT_MODEL;
		$data = $this->$model->MetalSelectModel();	
		echo json_encode($data);
	}
	public function PuritySelect(){
		$model=self::CAT_MODEL;
		$data = $this->$model->PuritySelectModel($_POST['id_metal']);
		echo json_encode($data);
	}
	public function MetalRates(){
		$model=self::CAT_MODEL;
		$data = $this->$model->MetalRatesModel();
		echo json_encode($data);
	}

	public function ret_metalpurity($type="",$id=""){
		$model=self::CAT_MODEL;
				switch ($type) {
				case 'list':
					$data['main_content'] = "master/ret_rate_purity_master/list" ;
					$this->load->view('layout/template', $data);
					break;
				case "add":	
					//  print_r("added");exit;
                    $data=$this->$model->addmodel($_POST['Metal_input'],$_POST['Purity_input'],$_POST['Rate_input']);	
					 if($data['status'] == '1'){
						//  print_r("Hey");exit;
							 $data=array(
							'id_metal'    => $this->input->post("Metal_input"),
							'id_purity'   => $this->input->post("Purity_input"),
							'rate_field'  => $this->input->post("Rate_input"),
							'market_rate_field'  => $this->input->post("Rate_input"),
							'created_on'         => date("Y-m-d H:i:s"),
							'created_by'         => $this->session->userdata('uid')
						);
						$this->db->trans_begin();
				  		$this->$model->insertData($data,'ret_metal_purity_rate');

						 if($this->db->trans_status()===TRUE)
						 {
							 $this->db->trans_commit();
							 $this->session->set_flashdata('chit_alert', array('message'=>'New Metal Rate Purity added successfully!..','class'=>'success','title'=>'Add Metal Purity Rate : '));

						 }
						 else
						 {
										 	
							 $this->session->set_flashdata('chit_alert', array('message'=>'Unable to proceed the requested process..','class'=>'danger','title'=>'Add Metal Purity Rate: '));
						 }
						
					 	}
						else
						{
						// echo $this->db->_error_message()."<br/>";					   

							$this->session->set_flashdata('chit_alert', array('message'=>'Unable to proceed the requested process..','class'=>'danger','title'=>'Already Exsits '));
						}
						echo json_encode($data);
					// redirect('admin_ret_catalog/ret_metalpurity/list');	
						
				break;
				case "edit":
                    // $data=array($_POST['Metal_input'],$_POST['Purity_input'],$_POST['Rate_input']);						
						$data = $this->$model->get_Purityratemetals($id);
						echo json_encode($data);
				break;
				case "update":
					$data=$this->$model->editmodel($_POST['Metal_input'],$_POST['Purity_input']);	

					if($data['status'] == '1'){
						$data = array(
						'id_metal' 			 => $this->input->post("Metal_input"),
						'id_purity'			 => $this->input->post("Purity_input"),
						'rate_field' 	     => $this->input->post("Rate_input"),
						'market_rate_field'  => $this->input->post("Rate_input"),
						'created_on'         => date("Y-m-d H:i:s"),
						'created_by'         => $this->session->userdata('uid')
					);				
					$this->db->trans_begin();
					$data =$this->$model->updateData($data,'id_metal_purity_rate',$id,'ret_metal_purity_rate');
				   if($this->db->trans_status()===TRUE)
				  	{
					   $this->db->trans_commit();
					   $this->session->set_flashdata('chit_alert', array('message'=>' Metal Rate Purity updated successfully!..','class'=>'success','title'=>'Updated Metal Rate Purity: '));
		   			}
				   else
				   {
					   $this->db->trans_rollback();						 	
					   $this->session->set_flashdata('chit_alert', array('message'=>'Unable to proceed the requested process..','class'=>'danger','title'=>'Updated Metal Rate Purity : '));
   				   }
				}

				else{
					$this->session->set_flashdata('chit_alert', array('message'=>'Unable to proceed the requested process..','class'=>'danger','title'=>'Cannot Update, Already Exsits '));

				}
				echo json_encode($data);

					break;

				case "delete":
						$this->db->trans_begin();
						$this->$model->deleteData('id_metal_purity_rate',$id,'ret_metal_purity_rate');
					   if($this->db->trans_status()===TRUE)
					   {
						   $this->db->trans_commit();
						   $this->session->set_flashdata('chit_alert', array('message'=>' Metal Rate Purity Deleted successfully!..','class'=>'success','title'=>'Deleted: '));
					   }
					   else
					   {
						   $this->db->trans_rollback();						 	
						   $this->session->set_flashdata('chit_alert', array('message'=>'Unable to proceed the requested process..','class'=>'danger','title'=>'Deleted Metal Rate Purity : '));
				     	}
						 redirect('admin_ret_catalog/ret_metalpurity/list');
					break;
					
					default:
					$SETT_MOD 	= self::SETT_MOD;
				 	$Purity = $this->$model->get_saved_purity();
					$access 	= $this->$SETT_MOD->get_access('admin_ret_catalog/ret_metalpurity/list');
					$data 		= array(
										'Purity'=>$Purity,
										'access'=>$access
									);  
					echo json_encode($data);
				}
				
		}
    //Purity Rate Master

	
	public function	bank_deposit($type="",$id="") {

		$model	=	self::CAT_MODEL;

		switch($type) {

			case 'List':

				$data['main_content'] = "master/deposit/list" ;

				$cash = $this->$model->getall_cashamt();
				
				$retail_cash 	= $cash['retail_cash'];

				$chit_cash 		= $cash['chit_cash'];

				$dep_cur_balance_retail = 0;

				$dep_cur_balance_chit = 0;

				$max_arr_retail = $this->$model->get_deposit(0, 'max', 1, '', '', 1);

				if(count($max_arr_retail) > 0) {

					$dep_cur_balance_retail = $max_arr_retail[0]['dep_cur_balance'];

				}

				$max_arr_chit = $this->$model->get_deposit(0, 'max', 1, '', '', 2);

				if(count($max_arr_chit) > 0) {

					$dep_cur_balance_chit = $max_arr_chit[0]['dep_cur_balance'];

				}

				$data['dep_cur_balance_retail'] = $retail_cash - $dep_cur_balance_retail;

				$data['dep_cur_balance_chit'] 	= $chit_cash - $dep_cur_balance_chit;

				$this->load->view('layout/template', $data);

				break;

			case "View":

				$dep_cur_balance = 0;

				$data['bank_name'] = $this->$model->get_banks();

				$data['payModes']	= $this->$model->get_payment_mode();

				$data['employee']	= $this->$model->get_employee();

				$dep_cur_balance_retail = 0;

				$dep_cur_balance_chit = 0;

				$cash = $this->$model->getall_cashamt();
				
				$retail_cash 	= $cash['retail_cash'];

				$chit_cash 		= $cash['chit_cash'];

				if($id!=NULL) {

					$data['deposit'] = $this->$model->get_deposit($id)[0];

					$max_arr_retail = $this->$model->get_deposit(0, 'max', 2, '', '', 1);

					if(count($max_arr_retail) > 0) {

						if(count($max_arr_retail) == 2) {
	
							$dep_cur_balance_retail = $max_arr_retail[1]['dep_cur_balance'];
	
						} else {
	
							$dep_cur_balance_retail = 0;
	
						}

					}

					$max_arr_chit = $this->$model->get_deposit(0, 'max', 2, '', '', 2);

					if(count($max_arr_chit) > 0) {

						if(count($max_arr_chit) == 2) {
	
							$dep_cur_balance_chit = $max_arr_chit[1]['dep_cur_balance'];
	
						} else {
	
							$dep_cur_balance_chit = 0;
	
						}

					}

				} else {

					$data['deposit'] = $this->$model->getDeposit_empty_record();

					$max_arr_retail = $this->$model->get_deposit(0, 'max', 1, '', '', 1);

					if(count($max_arr_retail) > 0) {

						$dep_cur_balance_retail = $max_arr_retail[0]['dep_cur_balance'];

					}

					$max_arr_chit = $this->$model->get_deposit(0, 'max', 1, '', '', 2);

					if(count($max_arr_chit) > 0) {

						$dep_cur_balance_chit = $max_arr_chit[0]['dep_cur_balance'];

					}

				}

				$data['dep_cur_balance_retail'] = $retail_cash - $dep_cur_balance_retail;

				$data['dep_cur_balance_chit'] 	= $chit_cash - $dep_cur_balance_chit;

				/*echo "<pre>";
				print_r($data);
				echo "</pre>";
				exit;*/

				$data['main_content'] = "master/deposit/form" ;

				$this->load->view('layout/template', $data);  

				break;

			case "Save":

				$dep_branch = $this->input->post("dep_branch");

				$cash_type = $this->input->post("type");

				$cash = $this->$model->getall_cashamt($dep_branch);
				
				$retail_cash 	= $cash['retail_cash'];

				$chit_cash 		= $cash['chit_cash'];

				$dep_cur_balance = 0;

				$dep_sum = $this->$model->get_sum_of_deposits($dep_branch, $cash_type);

				if($cash_type == 1) {

					$available_balance = $retail_cash - $dep_sum;

					$dep_cur_balance = $dep_sum;

				} else if($cash_type == 2) {

					$available_balance = $chit_cash - $dep_sum;

					$dep_cur_balance = $dep_sum;

				}

				if($this->input->post("dep_amount") <= $available_balance) {

					$dep_cur_balance = $dep_cur_balance + $this->input->post("dep_amount");

					$data=array(

						'dep_branch'		=> $dep_branch,

						'type'				=> $cash_type,

						'dep_amount'		=> $this->input->post("dep_amount"),

						'dep_type'			=> 1,

						'dep_bank '			=> $this->input->post("dep_bank"),

						'dep_ref_id'		=> $this->input->post("dep_ref_id"),

						'dep_cur_balance'	=> $dep_cur_balance,

						'dep_narration'		=> $this->input->post("dep_narration"),

						'dep_by'			=> $this->input->post("dep_by"),

						'dep_date'          => date("Y-m-d H:i:s", strtotime($this->input->post("dep_date"))),

						'cash_date'          => date("Y-m-d", strtotime($this->input->post("cash_date"))),

						'dep_mode'			=> 1, 

						'created_on'        => date("Y-m-d H:i:s"),

						'created_by'        => $this->session->userdata('uid')

					);

					/*echo "<pre>";
					print_r($data);
					echo "</pre>";
					exit;*/

					$this->db->trans_begin();

					$this->$model->insertData($data,'ret_bank_deposit'); 

					if($this->db->trans_status()===TRUE) {

						$this->db->trans_commit();

						$this->session->set_flashdata('chit_alert',array('message'=>'Deposit added successfully','class'=>'success','title'=>'Add Deposit'));

					} else {

						$this->db->trans_rollback();						 	

						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Add Deposit'));

					}

					redirect('deposit/list');

				} else {				 	

					$this->session->set_flashdata('chit_alert',array('message'=>'Deposit amount should be less than Cash In Hand','class'=>'danger','title'=>'Add Deposit'));

					redirect('deposit/add');

				}

				break;

			case "Update":

				$dep_branch = $this->input->post("dep_branch");

				$cash_type = $this->input->post("type");

				$cash = $this->$model->getall_cashamt($dep_branch);
				
				$retail_cash 	= $cash['retail_cash'];

				$chit_cash 		= $cash['chit_cash'];

				$dep_cur_balance = 0;

				$dep_sum = $this->$model->get_sum_of_deposits($dep_branch, $cash_type);

				$curr_deposit = $this->$model->get_deposit($id)[0];

				$curr_deposit = $curr_deposit['dep_amount'];

				$dep_sum = $dep_sum - $curr_deposit;

				if($cash_type == 1) {

					$available_balance = $retail_cash - $dep_sum;

					$dep_cur_balance = $dep_sum;

				} else if($cash_type == 2) {

					$available_balance = $chit_cash - $dep_sum;

					$dep_cur_balance = $dep_sum;

				}

				if($this->input->post("dep_amount") <= $available_balance) {

					$dep_cur_balance = $dep_cur_balance + $this->input->post("dep_amount");

					$data=array(

						'dep_branch'		=> $dep_branch,

						'type'				=> $cash_type,

						'dep_amount'		=> $this->input->post("dep_amount"),

						'dep_type'			=> 1,

						'dep_bank'			=> $this->input->post("dep_bank"),

						'dep_ref_id'		=> $this->input->post("dep_ref_id"),

						'dep_cur_balance'	=> $dep_cur_balance,

						'dep_narration'		=> $this->input->post("dep_narration"),

						'dep_by'			=> $this->input->post("dep_by"),

						'dep_date'          => date("Y-m-d H:i:s", strtotime($this->input->post("dep_date"))),

						'cash_date'          => date("Y-m-d", strtotime($this->input->post("cash_date"))),

						'dep_mode'			=> 1, 

						'updated_on'        => date("Y-m-d H:i:s"),

						'updated_by'        => $this->session->userdata('uid')

					);

					$this->db->trans_begin();

					$status = $this->$model->updateData($data,'dep_id',$id,'ret_bank_deposit');

					if($this->db->trans_status()===TRUE) {

						$this->db->trans_commit();

						$this->session->set_flashdata('chit_alert',array('message'=>'Deposit Updated successfully','class'=>'success','title'=>'Deposit'));

					}
					else
					{
						$this->db->trans_rollback();						 	
						
						$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Deposit'));
					
					}

					redirect('deposit/list'); 

				} else {				 	

					$this->session->set_flashdata('chit_alert',array('message'=>'Deposit amount should be less than Cash In Hand','class'=>'danger','title'=>'Add Deposit'));

					redirect('deposit/edit/'.$id);

				}

				break;

			case 'Delete':

				$this->db->trans_begin();

				$this->$model->deleteData('dep_id',$id,'ret_bank_deposit');

				if( $this->db->trans_status()===TRUE) {

					$this->db->trans_commit();

					$this->session->set_flashdata('chit_alert', array('message' => 'Deposit deleted successfully','class' => 'success','title'=>'Delete Deposit'));	  
				}			  
				else
				{
					$this->db->trans_rollback();

					$this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete Deposit'));
				}

				redirect('deposit/list');	

				break;

			case 'get_cash_in_hand':

				$cash_in_hand = 0;

				$id_branch = $this->input->post("id_branch") > 0 ? $this->input->post("id_branch") : 0;

				$cash_type = $this->input->post("cash_type") > 0 ? $this->input->post("cash_type") : 0;

				$cash = $this->$model->getall_cashamt($id_branch);

				$cash_sum = $this->$model->get_sum_of_deposits($id_branch, $cash_type);
				

				if($cash_type == 1) {

					$cash_in_hand = $cash['retail_cash'] - $cash_sum;

				} else if($cash_type == 2) {

					$cash_in_hand = $cash['chit_cash'] - $cash_sum;

				} else {

					$cash_in_hand = $cash['total_pay'] - $cash_sum;

				}

				$cash_in_hand = $this->moneyFormatIndia(number_format((float)($cash_in_hand),2,'.',''));

				$result = array("status" => true, "message" => "Data retrieved successfully", "cash_in_hand" => $cash_in_hand);

				echo json_encode($result);

				break;

			default:

				$SETT_MOD = self::SETT_MOD;

				$list = $this->$model->get_deposit();
				
				$max_arr = $this->$model->get_deposit(0, 'max', 1);

				if(count($max_arr) > 0) {

					$max_id = $max_arr[0]['dep_id'];

				}

				$access = $this->$SETT_MOD->get_access('deposit/list');

				$data = array(

								'deposits' => $list,

								'max_id' => isset($max_id) ? $max_id : '',

								'access'=> $access

							);

				echo json_encode($data);
		}

	}

	function moneyFormatIndia($num) {

		return preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $num);

	}



	/*Karigar Approval*/


	function karigar_approval($type="")
	{

		$model	=	self::CAT_MODEL;

		switch($type) 
		{
			case 'list':
				//$data['karigar_wastages'] = $this->$model->get_karigar_approvalWastage_list($id);
				$data['main_content'] = "master/karigar/approval_list" ;
				$this->load->view('layout/template', $data);
			break;


			
			case 'save':

				$status = $_POST['status'];


				$approval_for = $_POST['approval_for'];
                
				$this->db->trans_begin();
				
			 
				foreach($_POST['approved_data'] as $key => $val)
				{

				
					if($status==1)
					{
						if($approval_for==0) // Karigar Wastages Update
						{
							$this->$model->update_karigar_wastages($val['id_karigar'],$val['cat_id'],$val['pro_id'],$val['des_id'],$val['sub_des_id']);
							$statusData=array(
								'calc_type'                => $val['calc_type'],
								'wastage_type'             => $val['wastage_type'],
								'wastage_per'              => $val['wastage_per'],
								'wastage_wt'               => $val['wastage_wt'],
								'mc_type'                  => $val['mc_type'],
								'mc_value'                 => $val['mc_value'],
								'pur_touch'                => $val['pur_touch'],
								'karigar_calc_type'        => $val['kar_calc_type'],
								'status'                   => 1,
								'active'                   => 1,
								'approved_by'              => $this->session->userdata('uid'),
								'approved_on'              => date("Y-m-d H:i:s")
							);							
							$this->$model->updateData($statusData,'id_karikar_wast',$val['id_karikar_wast'],'ret_karikar_items_wastage');
							
							$this->$model->deleteData('id_karikar_wast',$val['id_karikar_wast'],'ret_karigar_charges');
                            $charges_details = json_decode($val['charges_details'],true);
                            if(sizeof($charges_details)>0)
                            {
                            	foreach($charges_details as $charge)
                            	{
                            		$charge_data = array(
                            				'id_karigar'         =>$val['id_karigar'],
                            				'id_karikar_wast'    =>$val['id_karikar_wast'],
                            				'charge_id'          =>$charge['charge_id'],
                            				'calc_type'          =>$charge['calc_type'],
                            				'charge_value'       =>$charge['charge_value'],
                            				'active'             =>0,
                            				'updated_on'	     =>date("Y-m-d H:i:s"),
                            				'updated_by'         =>$this->session->userdata('uid')
                            			);
                            			$this->$model->insertData($charge_data,'ret_karigar_charges');
                            	}
                            }
						}

						else if($approval_for==1) // Karigar stones Update
						{
							$this->$model->update_karigar_stones($val['id_karigar'],$val['stone_type_id'],$val['stone_id']);
							
							$statusData=array('status' => 1,'active' => 1,'approved_by' => $this->session->userdata('uid'),'approved_on'  => date("Y-m-d H:i:s"));
							
							$this->$model->updateData($statusData,'id_karigar_stone',$val['id_karigar_stone'],'ret_karigar_stones');
							
						}
					

						
					}

					else
					{
						$statusData=array(
			
							'status' => 2,

							'active' => 0,
							
							'approved_by' => $this->session->userdata('uid'),
		
							'approved_on'  => date("Y-m-d H:i:s"),
						
						);
						
						if($approval_for==1) // Karigar stones Update
						{
						    $this->$model->updateData($statusData,'id_karigar_stone',$val['id_karigar_stone'],'ret_karigar_stones');
						}else{
						    $this->$model->updateData($statusData,'id_karikar_wast',$val['id_karikar_wast'],'ret_karikar_items_wastage');
						}
						
						
						
						

					}

				}
				if($this->db->trans_status()===TRUE)
				{
					$this->db->trans_commit();
					$this->session->set_flashdata('chit_alert',array('message'=>'Approved successfully','class'=>'success','title'=>'Karigar Approval'));
					$return_data=array('message'=>'Aprroved successfully','status'=>true);
					echo json_encode($return_data);
				}
				else
				{
					$this->db->trans_rollback();						 	
					$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Karigar Approval'));
					$return_data=array('message'=>'Unable to proceed the requested process','status'=>false);
					echo json_encode($return_data);
				}

			break;

			case'wastages_list':
				$SETT_MOD = self::SETT_MOD;
				$list = $this->$model->get_karigar_approvalWastage_list($_POST);	 
				$access = $this->$SETT_MOD->get_access('admin_ret_catalog/karigar/list');
				$data = array(
									'list' =>$list,
									'access'=>$access
								);  
				echo json_encode($data);
			break;	

			case'stones_list':
				$SETT_MOD = self::SETT_MOD;
				$list = $this->$model->get_karigar_approvalStones_list($_POST);	 
				$access = $this->$SETT_MOD->get_access('admin_ret_catalog/karigar/list');
				$data = array(
									'list' =>$list,
									'access'=>$access
								);  
				echo json_encode($data);
			break;	

			case'charges_list':
				$SETT_MOD = self::SETT_MOD;
				$list = $this->$model->get_karigar_approvalCharges_list($_POST);	 
				$access = $this->$SETT_MOD->get_access('admin_ret_catalog/karigar/list');
				$data = array(
									'list' =>$list,
									'access'=>$access
								);  
				echo json_encode($data);
			break;	
		}

		/*Karigar Approval*/


	

	}
	
	function get_WastApprvKarigars()
    {
    		$model	=	self::CAT_MODEL;
    		$data = $this->$model->get_WastApprvKarigars();
    		echo json_encode($data);
    }


	function get_KarigarWastPurity()
	{
		$model	=	self::CAT_MODEL;
		$data = $this->$model->getCatPurity($_POST['id_category']);
		echo json_encode($data);
	}

    function get_ActiveKYC()
	{
		$model	=	self::CAT_MODEL;
		$data = $this->$model->get_ActiveKYC();
		echo json_encode($data);
	}

	function getKycDetails()
	{

		$model	=	self::CAT_MODEL;
		$data = $this->$model->get_ActiveKYCDetails($_POST);
		echo json_encode($data);

	}
    
    function get_kar_img_by_id()
    {
        $model	=	self::CAT_MODEL;
        $data = $this->$model->get_karigar_images($_POST['id_karigar_wast']);
        echo json_encode($data);
    }

    function do_upload()
    {
    		
    		//echo"<pre>";print_r($_FILES);exit;
    		
    		$config['upload_path']          = self::IMG_PATH."karigar/kyc/documents";
    		$config['allowed_types']        = 'pdf|docx';
    		$config['max_size']             = '15000000';
    		
			if (!is_dir($config['upload_path'])) {  
				mkdir($config['upload_path'], 0777, TRUE);
			} 
    
    		$this->load->library('upload', $config);
    
    		$this->upload->initialize($config);
    
    		if (!$this->upload->do_upload('doc_file'))
    		{
    			$error = array('error' => $this->upload->display_errors());
    
    			print_r($error);exit;
    		}
    		else
    		{
    			$data = array('status'=>true,'response' => $this->upload->data());
    
    			//echo"<pre>";print_r($data);exit;
    
    		}
    
    		echo json_encode($data);
    
    }
    
    
    //Diamond quality function Starts
    public function diamond($type="",$id="",$status=""){
    $model=self::CAT_MODEL;
    switch($type)
    {
        case 'list':
            $data['main_content'] = "master/diamond/list" ;
            $this->load->view('layout/template', $data);
        break;
        
        case "add":		
            $data['quality']=$this->$model->get_empty_quality();
            $data['main_content'] = "master/diamond/form" ;
            $this->load->view('layout/template', $data);
        break;
        
        case "save":      
        $quality    = $this->input->post('quality');
        //print_r($quality);exit;
        $data['quality'] = array( 
            'code'      	=>  (isset($quality['code'])?$quality['code']: NULL),
            'clarity_id' 	=>  (isset($quality['clarity_id'])?$quality['clarity_id']: NULL),
            'color_id'   	=>  (isset($quality['color_id'])?$quality['color_id']: NULL),
            'cut_id' 	    =>  (isset($quality['cut_id'])?$quality['cut_id']: NULL),
            'shape_id'   	=>  (isset($quality['shape_id'])?$quality['shape_id']: NULL),
            'status'        =>  (isset($quality['status'])?$quality['status']: 1),
            'created_by_id'	=>  ($this->session->userdata('uid')), 
            'created_on'	=>   date("Y-m-d H:i:s"),
            'updated_by_id'	=>  ($this->session->userdata('uid')), 
            'updated_on' 	=>   date("Y-m-d H:i:s")
        );
        // print_r($data['quality']);exit;
        $this->db->trans_begin();
        $this->$model->insertData($data['quality'],'ret_quality_code');
        //echo "<pre>";print_r($this->db->last_query());exit;
        
        if($this->db->trans_status()===TRUE)
        {
            $this->db->trans_commit();
            $this->session->set_flashdata('chit_alert',array('message'=>'New Save added successfully','class'=>'success','title'=>'Save Diamond Quality'));
            redirect('admin_ret_catalog/diamond/list');
        }
        else
        {
            $this->db->trans_rollback();						 	
            $this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Save Diamond Quality'));
            redirect('admin_ret_catalog/diamond/list');
        }	
        break;   
        
        case 'edit':
            $quality = $this->$model->get_quality($id);
            $data['quality'] = array( 
            'quality_id'      	=>  (isset($quality['quality_id'])?$quality['quality_id']: NULL),
            'code'      	    =>  (isset($quality['code'])?$quality['code']: NULL),
            'clarity_id' 	    =>  (isset($quality['clarity_id'])?$quality['clarity_id']: NULL),
            'color_id' 	        =>  (isset($quality['color_id'])?$quality['color_id']: NULL),
            'cut_id' 	        =>  (isset($quality['cut_id'])?$quality['cut_id']: NULL),
            'shape_id' 	        =>  (isset($quality['shape_id'])?$quality['shape_id']: NULL),
            'status'            =>  (isset($quality['status'])?$quality['status']: 1),
            'created_by_id'	    =>  ($this->session->userdata('uid')), 
            'created_on'	    =>   date("Y-m-d H:i:s"),
            'updated_by_id'	    =>  ($this->session->userdata('uid')), 
            'updated_on' 	    =>   date("Y-m-d H:i:s")
            );
            //print_r($data['quality']);exit;	
            $data['main_content'] = "master/diamond/form" ;
            $this->load->view('layout/template', $data);
        break;
        case "update":
            $quality   = $this->input->post('quality');
            $data= array( 
                'code'      	=>  (isset($quality['code'])?$quality['code']: NULL),
                'clarity_id' 	=>  (isset($quality['clarity_id'])?$quality['clarity_id']: NULL),
                'color_id'   	=>  (isset($quality['color_id'])?$quality['color_id']: NULL),
                'cut_id' 	    =>  (isset($quality['cut_id'])?$quality['cut_id']: NULL),
                'shape_id'   	=>  (isset($quality['shape_id'])?$quality['shape_id']: NULL),
                'status'        =>  (isset($quality['status'])?$quality['status']: 1),
                'created_by_id'	=>  ($this->session->userdata('uid')), 
                'created_on'	=>   date("Y-m-d H:i:s"),
                'updated_by_id'	=>  ($this->session->userdata('uid')), 
                'updated_on' 	=>   date("Y-m-d H:i:s")
            );
            $this->db->trans_begin();
            $this->$model->updateData($data,'quality_id',$id,'ret_quality_code');
            if($this->db->trans_status()===TRUE)
            {
                $this->db->trans_commit();
                $this->session->set_flashdata('chit_alert',array('message'=>'Diamond record modified successfully','class'=>'success','title'=>'Edit Diamond Quality'));
                redirect('admin_ret_catalog/diamond/list');
            }
            else
            {
                $this->db->trans_rollback();						 	
                $this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Edit Diamond Quality'));
                redirect('admin_ret_catalog/diamond/list');
            }
        break;	
        case 'delete':
            $this->db->trans_begin();
            $this->$model->deleteData('quality_id',$id,'ret_quality_code');
            if( $this->db->trans_status()===TRUE)
            {
                $this->db->trans_commit();
                $this->session->set_flashdata('chit_alert', array('message' => 'Diamond Quality deleted successfully','class' => 'success','title'=>'Delete Diamond Quality'));	
            }			  
            else
            {
                $this->db->trans_rollback();
                $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete Diamond Quality'));
            }
            redirect('admin_ret_catalog/diamond/list');
        break;
        case 'update_status':
        
        $data = array('status' => $status,'updated_on' => date("Y-m-d H:i:s"),'updated_by_id' => $this->session->userdata('uid'));
        //print_r($data);exit;
        $updstatus = $this->$model->updateData($data,'quality_id',$id,'ret_quality_code');
        if($updstatus)
        {
            $this->session->set_flashdata('chit_alert',array('message'=>'Diamond Quality status updated as '.($status == 1 ? 'Active' : 'Inactive').' successfully.','class'=>'success','title'=>'Diamond  Quality Status'));		
            redirect('admin_ret_catalog/diamond/list');
        }	
        else
        {
            $this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Diamond Quality Status'));
            redirect('admin_ret_catalog/diamond/list');
        }	
        case 'active_diamondclarity':
            $data = $this->$model->getclarity();	  
            echo json_encode($data);
        break;
        case 'active_diamondcolor':
            $data = $this->$model->getcolor();	  
            echo json_encode($data);
        break;
        case 'active_diamondcut':
            $data = $this->$model->getcut();	  
            echo json_encode($data);
        break;
        case 'active_diamondshape':
            $data = $this->$model->getshape();	  
        echo json_encode($data);
        break;
        default:
            $SETT_MOD = self::SETT_MOD;
            $quality = $this->$model->ajax_getquality();	 
            $access = $this->$SETT_MOD->get_access('admin_ret_catalog/diamond/list');
            $data = array(
            'quality' =>$quality,
            'access'=>$access
            );  
            echo json_encode($data); 
        }
    }

    public function rate($type="",$id="",$status="",$quality_code_id="")
    { 
        $model=self::CAT_MODEL;
        switch($type)
        {  
            case "list":
                $data['main_content'] = "master/diamond/diamond_rate/list" ;
                $this->load->view('layout/template', $data);
            break;
            case "add":		
                $data['rate'] = $this->$model->get_empty_rate();
                $data['rate_list'] = $this->$model->get_empty_rate_list();
                $data['main_content'] = "master/diamond/diamond_rate/form" ;
                $this->load->view('layout/template', $data);
            break;
            
            case "save":
                $Diamond_rate = (isset($_POST['rate_list']) ? $_POST['rate_list']:'');
                $rate  = $this->input->post('rate');
                //$diamond_rate_list  = $this->input->post('rate_list');
                $quality_code_id = $rate['quality_code_id'];
                $status=$this->$model->get_status($quality_code_id);
                if($status['status']){
                $data=array(
                'rate_status'=> 0
                );
                $this->$model->updateData($data,'quality_code_id',$quality_code_id,'ret_diamond_rate');
                }
                
                $data['rate'] = array( 
                'effective_date'  => date("Y-m-d"),
                'quality_code_id' => (isset($rate['quality_code_id'])?$rate['quality_code_id']:NULL),
                'rate_status'     => 1,
                'created_on'      => date("Y-m-d H:i:s"),
                'created_by_id'   => $this->session->userdata('uid')
                );
                
                $this->db->trans_begin();
                $result = $this->$model->insertData($data['rate'],'ret_diamond_rate');
                if(!empty($Diamond_rate['from_cent']))
                {
                    foreach($Diamond_rate['from_cent'] as $key => $val){
                        $data = array(
                        'id_rate_id'    => $result, 
                        'from_cent' 	=> ($Diamond_rate['from_cent'][$key]!=''?$Diamond_rate['from_cent'][$key]: NULL), 
                        'to_cent'       => ($Diamond_rate['to_cent'][$key]!=''?$Diamond_rate['to_cent'][$key]: NULL),
                        'rate' 	        => ($Diamond_rate['rate'][$key]!=''?$Diamond_rate['rate'][$key]: NULL)
                        ); 						
                        $this->$model->insertData($data,'ret_diamond_cent_rates');
                    }
                }
                if($this->db->trans_status()===TRUE)
                {
                    $this->db->trans_commit();
                    $responsData=array('status'=>TRUE,'message'=>'Diamond Rate Added successfully');
                }
                else
                {
                    $this->db->trans_rollback();						 	
                    $responsData=array('status'=>FALSE,'message'=>'Diamond Rate Added successfully');
                }
                echo json_encode($responsData);  
            
            break;  
            
            
            case 'edit':            
                $Diamond_rate = (isset($_POST['rate_list']) ? $_POST['rate_list']:'');
                $data['rate'] = $this->$model->get_quality_rate($id);
                $data['rate_list'] = $this->$model->get_rate($id);
                $data['main_content'] = "master/rate/form" ;
                $this->load->view('layout/template', $data);
            break;
            case "update":          
            
            $Diamond_rate = (isset($_POST['rate_list']) ? $_POST['rate_list']:'');
            $rate = $this->input->post('rate');
            //print_r($this->db->last_query());exit;
            $data['rate'] = array( 
            'effective_date'  => date("Y-m-d"),
            'quality_code_id' => (isset($rate['quality_code_id'])?$rate['quality_code_id']:NULL),
            'rate_status'     => (isset($rate['rate_status'])?$rate['rate_status']: 1),
            'updated_on'      => date("Y-m-d H:i:s"),
            'updated_by_id'   => $this->session->userdata('uid')
            );
            $this->db->trans_begin();
            $result = $this->$model->updateData($data['rate'],'rate_id',$id,'ret_diamond_rate');
            $this->$model->deleteData('id_rate_id',$id,'ret_diamond_cent_rates');
            
            if(!empty($Diamond_rate['from_cent']))
            {
                foreach($Diamond_rate['from_cent'] as $key => $val){
                    $data = array(
                    'id_rate_id'    => $result, 
                    'from_cent' 	=> ($Diamond_rate['from_cent'][$key]!=''?$Diamond_rate['from_cent'][$key]: NULL), 
                    'to_cent'       => ($Diamond_rate['to_cent'][$key]!=''?$Diamond_rate['to_cent'][$key]: NULL),
                    'rate' 	        => ($Diamond_rate['rate'][$key]!=''?$Diamond_rate['rate'][$key]: NULL),
                    'updated_on'    => date("Y-m-d H:i:s"),
                    'updated_by_id' => $this->session->userdata('uid')
                    ); 						
                    $this->$model->insertData($data,'ret_diamond_cent_rates');
                }
            }
            if($this->db->trans_status()===TRUE)
            {
                $this->db->trans_commit();
                $responsData=array('status'=>TRUE,'message'=>'Diamond Rate Updated successfully');
            }
            else
            {
                $this->db->trans_rollback();						 	
                $responsData=array('status'=>FALSE,'message'=>'Diamond Rate Updated successfully');
            }
            echo json_encode($responsData);            
            break;  
            case 'update_status':
                $this->db->trans_begin();
                $quality_status=$this->$model->get_status($quality_code_id);
                if($quality_status['status']){
                    $data=array(
                    'rate_status'=> 0
                    );
                    $this->$model->updateData($data,'quality_code_id',$quality_code_id,'ret_diamond_rate');
                }
                $data = array('rate_status' => $status,'updated_on' => date("Y-m-d H:i:s"),'updated_by_id' => $this->session->userdata('uid'));
                $updstatus = $this->$model->updateData($data,'rate_id',$id,'ret_diamond_rate');
                if($this->db->trans_status()===TRUE)
                {
                    $this->db->trans_commit();
                    $this->session->set_flashdata('chit_alert',array('message'=>'Diamond Quality status updated as '.($status == 1 ? 'Active' : 'Inactive').' successfully.','class'=>'success','title'=>'Diamond Rate Status'));		
                    redirect('admin_ret_catalog/rate/list');
                }	
                else
                {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested operation','class'=>'danger','title'=>'Diamond Rate Status'));
                    redirect('admin_ret_catalog/rate/list');
                }	
            
            break;
            
            
            case 'delete':
                $this->db->trans_begin();
                $this->$model->deleteData('rate_id',$id,'ret_diamond_rate');
                $this->$model->deleteData('id_rate_id',$id,'ret_diamond_cent_rates');
                if( $this->db->trans_status()===TRUE)
                {
                    $this->db->trans_commit();
                    $this->session->set_flashdata('chit_alert', array('message' => 'Diamond deleted successfully','class' => 'success','title'=>'Delete Diamond Rate'));	
                    //echo 1;
                }			  
                else
                {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete Diamond Rate'));
                    // echo 0;
                }
                redirect('admin_ret_catalog/diamond/diamond_rate/list');
            break;										 												
            case 'active_diamondquality':
            
            $data = $this->$model->getquality();	  
            echo json_encode($data);
            break;	
            
            default:
                $SETT_MOD = self::SETT_MOD;
                $rate = $this->$model->ajax_getrate($_POST);	
                //print_r($rate); 
                $access = $this->$SETT_MOD->get_access('admin_ret_catalog/diamond/diamond_rate/list');
                $data = array(
                'rate' =>$rate,
                'access'=>$access
                );  
                echo json_encode($data); 																					
        }  
    }
    
    
    //V.A and M.C Settings
    public function wastage_mc_settings($type,$id=""){
		$model=self::CAT_MODEL; 
		switch($type)
		{			 
			case 'add': 
				$data['main_content'] = "master/ret_selling_settings/form" ;
				$data['is_va_mc_based_on_branch'] =$this->$model->get_ret_settings('is_va_mc_based_on_branch');
				$this->load->view('layout/template', $data);
				break; 
			case 'list': 
				$data['main_content'] = "master/ret_selling_settings/list" ;
				$data['is_va_mc_based_on_branch'] =$this->$model->get_ret_settings('is_va_mc_based_on_branch');
				$this->load->view('layout/template', $data);
				break;
			case 'edit': 
				$data['main_content'] = "master/ret_selling_settings/form" ;
				$data['is_va_mc_based_on_branch'] =$this->$model->get_ret_settings('is_va_mc_based_on_branch');
				$this->load->view('layout/template', $data);
				break;
			case 'get_wastage_mc_settings': 
				$data = $this->$model->get_wastage_mc_settings($id); 										
				echo json_encode($data);
				break;
			case 'get_wastage_mc_setting_WeightDetails': 
				$data = $this->$model->get_wastage_details_settings($id); 										
				echo json_encode($data);
				break;
			case 'save': 
				
				$addData=$_POST['settings'];
				//print_r($_POST);exit;
		        $is_va_mc_based_on_branch = $this->$model->get_ret_settings('is_va_mc_based_on_branch');
		       
		        
		        if($addData['wastage_type']==1)
		        {
		            $settings=$this->$model->check_selling_rate_settings($addData['id_product'],$addData['id_design'],$addData['id_sub_design']);
		            if($settings['status'] || $is_va_mc_based_on_branch == 1 )
		            {
		                  $insData=array(
		                       'id_product'     =>$addData['id_product'],
		                       'id_design'      =>$addData['id_design'],
		                       'id_sub_design'  =>($addData['id_sub_design']!='' ?$addData['id_sub_design'] :NULL),
		                       'type'           =>$addData['wastage_type'],
		                       'wastage_perc'   =>($addData['wastag_value']!='' ? $addData['wastag_value']:NULL),
							   'wastag_min'   =>($addData['min_wastag_value']!='' ? $addData['min_wastag_value']:NULL),
		                       'mc_value'       =>($addData['mc_value']!='' ? $addData['mc_value']:NULL),
							   'mc_min'       =>($addData['min_mc_value']!='' ? $addData['min_mc_value']:NULL),
		                       'mc_type'        =>$addData['mc_type'],
							   'margin_mrp'        =>$addData['margin_mrp'],
		                       );
					
							$this->db->trans_begin();
							if($is_va_mc_based_on_branch == 1){

								$branch=$_POST['settings']['branch'];
								$Data=[];
								foreach($branch as $id_branch){

									$check=$this->$model->check_selling_rate_settings($addData['id_product'],$addData['id_design'],$addData['id_sub_design'],$id_branch);
									if($check['status']){
										$insData['id_branch']=$id_branch;
										$Data[]=$insData;
										$status=True;
									}else{
										
										$responseData=array('status'=>$settings['status'],'message'=>$settings['message']);	
										$status=FALSE;
										break;
									}
									
								}
								if($status){

								$insId=$this->$model->insertBatchData($Data,'ret_selling_settings');

								}
							}else{
								$insId=$this->$model->insertData($insData,'ret_selling_settings');
							}
            		      
            		        
                           
                            if($insId)
                            {
                                $responseData=array('status'=>true,'message'=>'V.A and MC Settings Added successfully');	
                            }
		            }
		            else
		            {
		                $responseData=array('status'=>$settings['status'],'message'=>$settings['message']);	
		            }
		        }
		        else
		        {
                    if(  $is_va_mc_based_on_branch == 1){

						$branch=$_POST['settings']['branch'];
                       
						foreach($branch as $id_branch){
							
							$check=$this->$model->check_selling_rate_settings($addData['id_product'],$addData['id_design'],$addData['id_sub_design'],$id_branch);

							if($check['status'])
							{
										$insData=array(
										'id_product'     =>$addData['id_product'],
										'id_design'      =>$addData['id_design'],
										'id_sub_design'  =>($addData['id_sub_design']!='' ?$addData['id_sub_design'] :NULL),
										'type'           =>$addData['wastage_type'],
										'wastage_perc'   =>($addData['wastag_value']!='' ? $addData['wastag_value']:NULL),
										'mc_value'       =>($addData['mc_value']!='' ? $addData['mc_value']:NULL),
										'mc_type'        =>$addData['mc_type'],
										'id_branch'        =>$id_branch,
										'margin_mrp'        =>$addData['margin_mrp'],
										);
									$this->db->trans_begin();
									$id_selling_settings=$this->$model->insertData($insData,'ret_selling_settings');
							}else if($check['type'] == 2)
							{
								$id_selling_settings=$check['id_selling_settings'];

							}else{

								$id_selling_settings=NUll;

							}

							if($id_selling_settings)
							{
								
								$weightDetails=$_POST['wcrange'];
								
								foreach($weightDetails['from_weight'] as $key => $val)
								{
									if($weightDetails['wastage'][$key]!='' && $weightDetails['making_charge'][$key]!='')
									{
										$weightSettings=$this->$model->check_selling_rate_weight_settings($weightDetails['from_weight'][$key],$weightDetails['to_weight'][$key],$addData['id_product'],$addData['id_design'],$addData['id_sub_design'],$id_branch);
										
										if($weightSettings['status'])
										{
											
											$itemDetails=array(
												'id_selling_settings' => $id_selling_settings,
												'wc_from_weight'      =>$weightDetails['from_weight'][$key],
												'wc_to_weight'        =>$weightDetails['to_weight'][$key],
												'wc_percent'          =>($weightDetails['wastage'][$key]!='' ? $weightDetails['wastage'][$key]:NULL),
												'mc'                  =>($weightDetails['making_charge'][$key]!='' ? $weightDetails['making_charge'][$key]:NULL),
												'wc_min'              =>($weightDetails['wc_min'][$key]!='' ? $weightDetails['wc_min'][$key]:NULL),
												'mcrg_min'            =>($weightDetails['mcrg_min'][$key]!='' ? $weightDetails['mcrg_min'][$key]:NULL),
												);

											$this->$model->insertData($itemDetails,'ret_design_weight_range_wc');


										}
									}
								}
								
								$responseData=array('status'=>true,'message'=>'V.A and MC Settings Added successfully');	

							}else{

								$responseData=array('status'=>true,'message'=>'Already Added For This Product as Fixed');	
							}

					    }
					}    
					else{

					 $settings=$this->$model->check_selling_rate_settings($addData['id_product'],$addData['id_design'],$addData['id_sub_design']);

					if($settings['status'])
		            {
		                     $insData=array(
		                       'id_product'     =>$addData['id_product'],
		                       'id_design'      =>$addData['id_design'],
		                       'id_sub_design'  =>($addData['id_sub_design']!='' ?$addData['id_sub_design'] :NULL),
		                       'type'           =>$addData['wastage_type'],
		                       'wastage_perc'   =>($addData['wastag_value']!='' ? $addData['wastag_value']:NULL),
		                       'mc_value'       =>($addData['mc_value']!='' ? $addData['mc_value']:NULL),
		                       'mc_type'        =>$addData['mc_type'] ,
							   'margin_mrp'        =>$addData['margin_mrp'],
		                       );
            		        $this->db->trans_begin();
                            $id_selling_settings=$this->$model->insertData($insData,'ret_selling_settings');
		            }else
		            {
		                $id_selling_settings=$settings['id_selling_settings'];
		            }
                    if($id_selling_settings)
                    {
                        if($addData['wastage_type']==2)
                        {
                            $weightDetails=$_POST['wcrange'];
                            foreach($weightDetails['from_weight'] as $key => $val)
                            {
                                if($weightDetails['wastage'][$key]!='' && $weightDetails['making_charge'][$key]!='')
                                {
                                    $weightSettings=$this->$model->check_selling_rate_weight_settings($weightDetails['from_weight'][$key],$weightDetails['to_weight'][$key],$addData['id_product'],$addData['id_design'],$addData['id_sub_design']);
                                    if($weightSettings['status'])
                                    {
										$itemDetails=array(
											'id_selling_settings' => $id_selling_settings,
											'wc_from_weight'      =>$weightDetails['from_weight'][$key],
											'wc_to_weight'        =>$weightDetails['to_weight'][$key],
											'wc_percent'          =>($weightDetails['wastage'][$key]!='' ? $weightDetails['wastage'][$key]:NULL),
											'mc'                  =>($weightDetails['making_charge'][$key]!='' ? $weightDetails['making_charge'][$key]:NULL),
											'wc_min'              =>($weightDetails['wc_min'][$key]!='' ? $weightDetails['wc_min'][$key]:NULL),
											'mcrg_min'            =>($weightDetails['mcrg_min'][$key]!='' ? $weightDetails['mcrg_min'][$key]:NULL),
											);
                                        $this->$model->insertData($itemDetails,'ret_design_weight_range_wc');
                                    }
                                }
                            }
                        }
                        $responseData=array('status'=>true,'message'=>'V.A and MC Settings Added successfully');	
                    }
		        }
			    }
		        
                if($this->db->trans_status()===TRUE)
        		{	
					
        			$this->db->trans_commit();
        		}
        		else
        		{
        			$this->db->trans_rollback();
        			$responseData=array('status'=>false,'message'=>'Unable to Proceed Your Request');	
        		}
        	    echo json_encode($responseData);
				break; 
			case 'get_product_weight': 
					  $data = $this->$model->get_product_weight($_POST); 										
					  echo json_encode($data);
		    break; 
			case 'delete': 
				$status = $this->$model->delete_wastage_details_settings($id); 										
				if($status){
					$this->session->set_flashdata('chit_alert',array('message'=>'Successfully Delete WA Settings','class'=>'success','title'=>'Delete WA Settings'));
				}else{
					$this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Delete WA Settings'));
				}
				redirect('admin_ret_catalog/wastage_mc_settings/list');
	        break; 
			case 'update': 
				$addData=$_POST['settings'];
		        $is_va_mc_based_on_branch = $this->$model->get_ret_settings('is_va_mc_based_on_branch');
				$id_selling_settings=$_POST['id_selling_settings'];
				
                $check=$this->$model->check_wastage_details_settings($id_selling_settings);
                

		        
		        if($addData['wastage_type']==1)
		        {
		            $settings=$this->$model->check_selling_rate_settings($addData['id_product'],$addData['id_design'],$addData['id_sub_design']);
					
		            if($settings['status'] || $is_va_mc_based_on_branch == 1 )
		            {
		                  $insData=array(
		                       'id_product'     =>$addData['id_product'],
		                       'id_design'      =>$addData['id_design'],
		                       'id_sub_design'  =>($addData['id_sub_design']!='' ?$addData['id_sub_design'] :NULL),
		                       'type'           =>$addData['wastage_type'],
		                       'wastage_perc'   =>($addData['wastag_value']!='' ? $addData['wastag_value']:NULL),
							   'wastag_min'     =>($addData['min_wastag_value']!='' ? $addData['min_wastag_value']:NULL),
		                       'mc_value'       =>($addData['mc_value']!='' ? $addData['mc_value']:NULL),
							   'mc_min'         =>($addData['min_mc_value']!='' ? $addData['min_mc_value']:NULL),
		                       'mc_type'        =>$addData['mc_type'],
							   'margin_mrp'     =>$addData['margin_mrp'],
		                       );
							 
							
							if($is_va_mc_based_on_branch == 1){

								$branch=$_POST['id_branch'];
								$check=$this->$model->check_selling_rate_settings($addData['id_product'],$addData['id_design'],$addData['id_sub_design'],$branch,$id_selling_settings);
							
								if($check['status']){
									$insData['id_branch']=$branch;
									
									$status=True;
								}else{
									
									$responseData=array('status'=>$check['status'],'message'=>$check['message'],'check'=>$check);	
									$status=FALSE;
									
								}
								if($status){

								$insId=$this->$model->updateData($insData,'id_selling_settings',$id_selling_settings,'ret_selling_settings');

								}
							}else{

								
									$insId=$this->$model->updateData($insData,'id_selling_settings',$id_selling_settings,'ret_selling_settings');
								
								
							}
            		      
            		        
                           
                            if($insId)
                            {
                                $responseData=array('status'=>true,'message'=>'V.A and MC Settings Added successfully');
								if($check){
									$this->$model->deleteData('id_selling_settings',$id_selling_settings,'ret_design_weight_range_wc');
								}	
                            }
		            }
		            else
		            {
		                $responseData=array('status'=>$settings['status'],'message'=>$settings['message']);	
		            }
		        }
		        else
		        {
                    if(  $is_va_mc_based_on_branch == 1){

						$id_branch=$_POST['id_branch'];
						$check=$this->$model->check_selling_rate_settings($addData['id_product'],$addData['id_design'],$addData['id_sub_design'],$id_branch,$id_selling_settings);

						if($check['status'])
						{
									$insData=array(
									'id_product'     =>$addData['id_product'],
									'id_design'      =>$addData['id_design'],
									'id_sub_design'  =>($addData['id_sub_design']!='' ?$addData['id_sub_design'] :NULL),
									'type'           =>$addData['wastage_type'],
									'wastage_perc'   =>($addData['wastag_value']!='' ? $addData['wastag_value']:NULL),
									'mc_value'       =>($addData['mc_value']!='' ? $addData['mc_value']:NULL),
									'mc_type'        =>$addData['mc_type'],
									'id_branch'        =>$id_branch,
									'margin_mrp'        =>$addData['margin_mrp'],
									);
								$this->db->trans_begin();

								$insData=$this->$model->updateData($insData,'id_selling_settings',$id_selling_settings,'ret_selling_settings');
							
						}else{

							$id_selling_settings=NUll;
							

						}

						if($id_selling_settings)
						{

						
							if($check){
								$this->$model->deleteData('id_selling_settings',$id_selling_settings,'ret_design_weight_range_wc');
							}	
							$weightDetails=$_POST['wcrange'];
							
							foreach($weightDetails['from_weight'] as $key => $val)
							{
								if($weightDetails['wastage'][$key]!='' && $weightDetails['making_charge'][$key]!='')
								{
									$weightSettings=$this->$model->check_selling_rate_weight_settings($weightDetails['from_weight'][$key],$weightDetails['to_weight'][$key],$addData['id_product'],$addData['id_design'],$addData['id_sub_design'],$id_branch);
									
									if($weightSettings['status'])
									{
										
										$itemDetails=array(
											'id_selling_settings' => $id_selling_settings,
											'wc_from_weight'      =>$weightDetails['from_weight'][$key],
											'wc_to_weight'        =>$weightDetails['to_weight'][$key],
											'wc_percent'          =>($weightDetails['wastage'][$key]!='' ? $weightDetails['wastage'][$key]:NULL),
											'mc'                  =>($weightDetails['making_charge'][$key]!='' ? $weightDetails['making_charge'][$key]:NULL),
											'wc_min'              =>($weightDetails['wc_min'][$key]!='' ? $weightDetails['wc_min'][$key]:NULL),
											'mcrg_min'            =>($weightDetails['mcrg_min'][$key]!='' ? $weightDetails['mcrg_min'][$key]:NULL),
											);

										$this->$model->insertData($itemDetails,'ret_design_weight_range_wc');


									}
								}
							}
							
							$responseData=array('status'=>true,'message'=>'V.A and MC Settings Added successfully');	

						}else{

							$responseData=array('status'=>true,'message'=>'Already Added For This Product ');	
						}

					    
					}    
					else{

					 $settings=$this->$model->check_selling_rate_settings($addData['id_product'],$addData['id_design'],$addData['id_sub_design']);

					if($settings['status'])
		            {
		                     $insData=array(
		                       'id_product'     =>$addData['id_product'],
		                       'id_design'      =>$addData['id_design'],
		                       'id_sub_design'  =>($addData['id_sub_design']!='' ?$addData['id_sub_design'] :NULL),
		                       'type'           =>$addData['wastage_type'],
		                       'wastage_perc'   =>($addData['wastag_value']!='' ? $addData['wastag_value']:NULL),
		                       'mc_value'       =>($addData['mc_value']!='' ? $addData['mc_value']:NULL),
		                       'mc_type'        => $addData['mc_type'] ,
							   'margin_mrp'        =>$addData['margin_mrp'],
		                       );
            		        $this->db->trans_begin();
                            $id_selling_settings=$this->$model->insertData($insData,'ret_selling_settings');
		            }else
		            {
		                $id_selling_settings=$settings['id_selling_settings'];
		            }
                    if($id_selling_settings)
                    {
                        if($addData['wastage_type']==2)
                        {
                            $weightDetails=$_POST['wcrange'];
                            foreach($weightDetails['from_weight'] as $key => $val)
                            {
                                if($weightDetails['wastage'][$key]!='' && $weightDetails['making_charge'][$key]!='')
                                {
                                    $weightSettings=$this->$model->check_selling_rate_weight_settings($weightDetails['from_weight'][$key],$weightDetails['to_weight'][$key],$addData['id_product'],$addData['id_design'],$addData['id_sub_design']);
                                    if($weightSettings['status'])
                                    {
										$itemDetails=array(
											'id_selling_settings' => $id_selling_settings,
											'wc_from_weight'      =>$weightDetails['from_weight'][$key],
											'wc_to_weight'        =>$weightDetails['to_weight'][$key],
											'wc_percent'          =>($weightDetails['wastage'][$key]!='' ? $weightDetails['wastage'][$key]:NULL),
											'mc'                  =>($weightDetails['making_charge'][$key]!='' ? $weightDetails['making_charge'][$key]:NULL),
											'wc_min'              =>($weightDetails['wc_min'][$key]!='' ? $weightDetails['wc_min'][$key]:NULL),
											'mcrg_min'            =>($weightDetails['mcrg_min'][$key]!='' ? $weightDetails['mcrg_min'][$key]:NULL),
											);
                                        $this->$model->insertData($itemDetails,'ret_design_weight_range_wc');
                                    }
                                }
                            }
                        }
                        $responseData=array('status'=>true,'message'=>'V.A and MC Settings Added successfully');	
                    }
		        }
			    }
		        
                if($this->db->trans_status()===TRUE)
        		{	
					
        			$this->db->trans_commit();
        		}
        		else
        		{
        			$this->db->trans_rollback();
        			$responseData=array('status'=>false,'message'=>'Unable to Proceed Your Request');	
        		}
        	   echo json_encode($responseData);
	        break; 
			case 'get_wastage_details': 
				$data = $this->$model->get_wastage_details_settings($id); 										
				echo json_encode($data);
	        break; 
			default:
    		$SETT_MOD = self::SETT_MOD;
    		$list = $this->$model->ajax_get_wastage_settings_details($_POST);	 
    	  	$access = $this->$SETT_MOD->get_access('admin_ret_catalog/selling_price/list');
            $data = array(
            					'list' =>$list,
    							'access'=>$access
            			 );  
    		echo json_encode($data);

		}
	}
    //V.A and M.C Settings

}	
?>
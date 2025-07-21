<?php
if (! defined('BASEPATH')) exit('No direct script access allowed');
class Admin_customer extends CI_Controller
{
	const CUS_MODEL	= "customer_model";
	const ADM_MODEL	= "chitadmin_model";
	const SET_MODEL = 'admin_settings_model';
	const WALL_MODEL = 'wallet_model';
	const LOG_MODEL = "log_model";
	const SMS_MODEL = "admin_usersms_model";
	const MAIL_MODEL = "email_model";
	const CUS_VIEW = "master/customer/";
	const CUS_IMG_PATH = 'assets/img/customer/';
	const DEF_CUS_IMG_PATH = 'assets/img/default.png/';
	const DEF_IMG_PATH = 'assets/img/no_image.png/';
	const CUS_IMG = 'customer.jpg';
	const PAN_IMG = 'pan.jpg';
	const RATION_IMG = 'rationcard.jpg';
	const VOTERID_IMG = 'voterid.jpg';
	const KYC_PAN_PATH = 'assets/kyc/pan/';
	const KYC_AADHAR_PATH = 'assets/kyc/aadhar/';
	function __construct()
	{
		parent::__construct();
		ini_set('date.timezone', 'Asia/Calcutta');
		$this->load->model(self::CUS_MODEL);
		$this->load->model(self::ADM_MODEL);
		$this->load->model(self::SET_MODEL);
		$this->load->model(self::WALL_MODEL);
		$this->load->model(self::LOG_MODEL);
		$this->load->model(self::MAIL_MODEL);
		$this->load->model(self::SMS_MODEL);
		$this->load->model("sms_model");
		if (!$this->session->userdata('is_logged')) {
			redirect('admin/login');
		}
		$access = $this->admin_settings_model->get_access('customer');
		if ($access['view'] == 0) {
			redirect('admin/dashboard');
		}
	}
	public function index()
	{
		$this->cus_list();
	}
	public function check_multiple_chits()
	{
		$model_name = self::ADM_MODEL;
		$allowed = $this->$model_name->allow_multiple_chit();
		return $allowed['allow_join_multiple'];
	}
	public function ajax_get_customers()
	{
		$model_name = self::CUS_MODEL;
		$multiple_chit = $this->check_multiple_chits();
		if ($multiple_chit == 1) {
			$cus_data = $this->$model_name->ajax_get_all_customers();
		} else {
			$cus_data = $this->$model_name->ajax_get_unallocated_customers();
		}
		$customers = $cus_data;
		echo json_encode($customers);
		//echo "<pre>" print_r($customers);exit; echo "<pre>";;
	}
	//	public function ajax_get_customer($cus_name,$id)
	public function ajax_get_customer($id)
	{
		$model_name = self::CUS_MODEL;
		$customer = $this->$model_name->get_customer($id);
		echo json_encode($customer);
	}
	public function cus_list($msg = "")
	{
		$model_name = self::CUS_MODEL;
		$data['message'] = $msg;
		/*$cus_data=$this->$model_name->get_all_customers();
		if($cus_data)
		{
			$data['customers']=$cus_data;
		}*/
		$data['main_content'] = self::CUS_VIEW . 'list';
		$data['entry_date'] = $this->admin_settings_model->settingsDB('get', '', '');
		//	 echo"<pre>";	print_r($data);exit;
		$this->load->view('layout/template', $data);
	}
	public function ajax_customers()
	{
		$model_name = self::CUS_MODEL;
		$set_model = self::SET_MODEL;
		$data['access'] = $this->$set_model->get_access('customer');
		$range['from_date']  = $this->input->post('from_date');
		$range['to_date']  = $this->input->post('to_date');
		$range['id_branch']  = $this->input->post('id_branch');
		$range['id_village']  = $this->input->post('id_village');
		// code by jothika on 14.7.2025 [getting data using mobile number]
		$range['mobile'] = $this->input->post('mobile');
		if ($range['from_date'] != '' && $range['to_date'] != '') {
			$data['customer'] = $this->$model_name->get_customers_by_date($range['from_date'], $range['to_date'], $range['id_branch']);
		} else {
			$data['customer'] = $this->$model_name->get_all_customers('', $range['id_branch'], $range['id_village'], $range['mobile']);
		}
		echo json_encode($data);
	}
	function birthday($birthday)
	{
		$age = date_create($birthday)->diff(date_create('today'))->y;
		return $age;
	}
	//Open form
	public function cus_form($type = "", $id = "")
	{
		$model = self::CUS_MODEL;
		switch ($type) {
			case 'Add':
				/*-- Coded by ARVK --*/
				$set_model = self::SET_MODEL;
				$limit = $this->$set_model->limitDB('get', '1');
				$count = $this->$model->customer_count();
				//print_r($count);exit;
				$data['financial_year'] = $this->$model->GetFinancialYear();
				if ($limit['limit_cust'] == 1) {
					if ($count < $limit['cust_max_count']) {
						$data['customer'] = $this->$model->empty_record();
						$data['customer']['cus_img_path']	= self::CUS_IMG_PATH . "../default.png";
						$data['customer']['pan_path']		= self::CUS_IMG_PATH . "../no_image.png";
						$data['customer']['voterid_path']	= self::CUS_IMG_PATH . "../no_image.png";
						$data['customer']['rationcard_path'] = self::CUS_IMG_PATH . "../no_image.png";
						$data['customer']['cus_img']       = NULL;
						$data['customer']['age']     		= NULL;
						$data['main_content'] = self::CUS_VIEW . "form";
						$this->load->view('layout/template', $data);
					} else {
						$this->session->set_flashdata('chit_alert', array('message' => 'Customer creation limit exceeded, Kindly contact Super Admin...', 'class' => 'danger', 'title' => 'Customer creation'));
						redirect('customer');
					}
				} else {
					$data['customer'] = $this->$model->empty_record();
					// echo "<pre>";print_r($data['customer']);exit;
					$data['customer']['cus_img_path']	= self::CUS_IMG_PATH . "../default.png";
					$data['customer']['pan_path']		= self::CUS_IMG_PATH . "../no_image.png";
					$data['customer']['voterid_path']	= self::CUS_IMG_PATH . "../no_image.png";
					$data['customer']['rationcard_path'] = self::CUS_IMG_PATH . "../no_image.png";
					$data['customer']['cus_img']       = NULL;
					$data['customer']['age']     		= NULL;
					$data['main_content'] = self::CUS_VIEW . "form";
					$this->load->view('layout/template', $data);
				}
				/*--/ Coded by ARVK --*/
				break;
			case 'Edit':
				$cus = $this->$model->get_cust($id);
				//echo "<pre>";print_r($cus);echo "</pre>";exit; 
				$data['financial_year'] = $this->$model->GetFinancialYear();
				$age = $this->birthday($cus['date_of_birth']);
				$data['customer'] = array(
					'id_customer'		=> (isset($cus['id_customer']) ? $cus['id_customer'] : NULL),
					'firstname'			=> (isset($cus['firstname']) ? $cus['firstname'] : NULL),
					'lastname' 			=> (isset($cus['lastname']) ? $cus['lastname'] : NULL),
					'id_branch'	    	=> (isset($cus['id_branch']) ? $cus['id_branch'] : NULL),
					'religion'		    => (isset($cus['religion']) ? $cus['religion'] : NULL),
					'post_office'		=> (isset($cus['post_office']) ? $cus['post_office'] : NULL),
					'taluk'		=> (isset($cus['taluk']) ? $cus['taluk'] : NULL),
					'id_village'		=> (isset($cus['id_village']) ? $cus['id_village'] : NULL),
					'date_of_birth'		=> (isset($cus['date_of_birth']) && $cus['date_of_birth'] != '' ? date('d/m/Y', strtotime(str_replace("/", "-", $cus['date_of_birth']))) : NULL),
					'date_of_wed'		=> (isset($cus['date_of_wed']) && $cus['date_of_wed'] != '' ? date('d/m/Y', strtotime(str_replace("/", "-", $cus['date_of_wed']))) : NULL),
					'gst_number'				=> (isset($cus['gst_number']) ? $cus['gst_number'] : NULL),
					'email'				=> (isset($cus['email']) ? $cus['email'] : NULL),
					'gender'			=> (isset($cus['gender']) ? $cus['gender'] : NULL),
					'cus_type'			=>	$cus['cus_type'],
					'mobile'			=> (isset($cus['mobile']) ? $cus['mobile'] : NULL),
					'phone'				=> (isset($cus['phone']) ? $cus['phone'] : NULL),
					'title'				=> (isset($cus['title']) ? $cus['title'] : NULL),
					'age'				=> (isset($age) ? $age : NULL),
					'nominee_name'			=> (isset($cus['nominee_name']) ? $cus['nominee_name'] : NULL),
					'nominee_relationship'		=> (isset($cus['nominee_relationship']) ? $cus['nominee_relationship'] : NULL),
					'nominee_mobile'	=> (isset($cus['nominee_mobile']) ? $cus['nominee_mobile'] : NULL),
					'pan'				=> (isset($cus['pan']) ? $cus['pan'] : NULL),
					'pan_proof'			=> (isset($cus['pan_proof']) && $cus['pan_proof'] != NULL ? $cus['pan_proof'] : self::DEF_IMG_PATH),
					'voterid'			=> (isset($cus['voterid']) ? $cus['voterid'] : NULL),
					'voterid_proof'		=> (isset($cus['voterid_proof']) && $cus['voterid_proof'] != NULL ? $cus['voterid_proof'] : self::DEF_IMG_PATH),
					'rationcard'		=> (isset($cus['rationcard']) ? $cus['rationcard'] : NULL),
					'rationcard_proof'	=> (isset($cus['rationcard_proof']) && $cus['rationcard_proof'] != NULL ? $cus['rationcard_proof'] : self::DEF_IMG_PATH),
					'comments'			=> (isset($cus['comments']) ? $cus['comments'] : NULL),
					'username'			=> (isset($cus['username']) ? $cus['username'] : NULL),
					'passwd'			=> (isset($cus['passwd']) ? $cus['passwd'] : NULL),
					'active'			=> (isset($cus['active']) ? $cus['active'] : 0),
					'is_cus_synced'		=> (isset($cus['is_cus_synced']) ? $cus['is_cus_synced'] : 0),
					'id_country'		=> (isset($cus['id_country']) ? $cus['id_country'] : 0),
					'id_state' 			=> (isset($cus['id_state']) ? $cus['id_state'] : 0),
					'id_city'			=> (isset($cus['id_city']) ? $cus['id_city'] : 0),
					'company_name'			=> (isset($cus['company_name']) ? $cus['company_name'] : NULL),
					'address1'			=> (isset($cus['address1']) ? $cus['address1'] : NULL),
					'address2'			=> (isset($cus['address2']) ? $cus['address2'] : NULL),
					'address3'			=> (isset($cus['address3']) ? $cus['address3'] : NULL),
					'pincode'			=> (isset($cus['pincode']) ? $cus['pincode'] : NULL),
					'cus_img'           => (isset($cus['cus_img']) && $cus['cus_img'] != NULL ? $cus['cus_img'] : self::DEF_CUS_IMG_PATH),
					'id_profession'	    	=> (isset($cus['id_profession']) ? $cus['id_profession'] : NULL),
					'aadharid'	    	=> (isset($cus['aadharid']) ? $cus['aadharid'] : NULL),
					'driving_license_no'			=> (isset($cus['driving_license_no']) ? $cus['driving_license_no'] : NULL),
					'passport_no'			        => (isset($cus['passport_no']) ? $cus['passport_no'] : NULL),
					'fin_year_code' 	     => (isset($cus['fin_year_code']) ? $cus['fin_year_code'] : NULL),
					'opening_balance_amount' => (isset($cus['opening_balance_amount']) ? $cus['opening_balance_amount'] : 0.00),
					'nominee_pan'			=> (isset($cus['nominee_pan']) ? $cus['nominee_pan'] : NULL),
					'nominee_address1'			=> (isset($cus['nominee_address1']) ? $cus['nominee_address1'] : NULL),
					'nominee_address2'			=> (isset($cus['nominee_address2']) ? $cus['nominee_address2'] : NULL),
				);
				// if(is_dir(self::CUS_IMG_PATH.$id))
				// {
				$cus_img = self::CUS_IMG_PATH . $id . "/" . self::CUS_IMG;
				$pan_img = self::CUS_IMG_PATH . $id . "/" . self::PAN_IMG;
				$voter_img = self::CUS_IMG_PATH . $id . "/" . self::VOTERID_IMG;
				$ration_img = self::CUS_IMG_PATH . $id . "/" . self::RATION_IMG;
				if (file_exists($cus_img)) {
					$data['customer']['cus_img_path']		= $cus_img;
				}
				if (file_exists($pan_img)) {
					$data['customer']['pan_path']		= $pan_img;
				}
				if (file_exists($voter_img)) {
					$data['customer']['voterid_path']		= $voter_img;
				}
				if (file_exists($ration_img)) {
					$data['customer']['rationcard_path']		= $ration_img;
				}
				//get kyc images stored .... addedOn 21/06/2023 by: ABI
				/*$kycImages = $this->retrive_KycImages($cus);
					   $data['customer']['pan_img_path'] = $kycImages['pan_img_path'] ;
					   $data['customer']['aadhar_img_path'] = $kycImages['pan_img_path'] ;
					   $data['customer']['dl_img_path'] = $kycImages['pan_img_path'] ;  */
				$existing_pan = $this->$model->get_kyc_byid($cus['id_customer'], 2);
				if ($existing_pan != '' && $existing_pan != NULL) {
					$pan_img = $existing_pan[0]['img_url'];
					$pan_back_img = $existing_pan[0]['back_img_url'];
					$pan_pdf_url = $existing_pan[0]['document_url'];
					$data['customer']['pan_img_path']		= $pan_img;
					$data['customer']['pan_back_img_path']	= $pan_back_img;
					$data['customer']['pan_pdf_url']	= $pan_pdf_url;
				}
				$existing_aadhar = $this->$model->get_kyc_byid($cus['id_customer'], 3);
				if ($existing_aadhar != '' && $existing_aadhar != NULL) {
					$aadhar_img = $existing_aadhar[0]['img_url'];
					$aadhar_back_img = $existing_aadhar[0]['back_img_url'];
					$aadhar_pdf_url = $existing_aadhar[0]['document_url'];
					$data['customer']['aadhar_img_path']		= $aadhar_img;
					$data['customer']['aadhar_back_img_path']		= $aadhar_back_img;
					$data['customer']['aadhar_pdf_url']	= $aadhar_pdf_url;
				}
				// $pan_img = self::CUS_IMG_PATH.$cus['id_customer'].'/'.$cus['pan_ImgName'];
				//  $aadhar_img = self::CUS_IMG_PATH.$cus['id_customer'].'/'.$cus['aadhar_ImgName'];
				$dl_img = self::CUS_IMG_PATH . $cus['id_customer'] . '/' . $cus['dl_ImgName'];
				$pp_ImgName = self::CUS_IMG_PATH . $cus['id_customer'] . '/' . $cus['pp_ImgName'];
				if (file_exists($dl_img)) {
					$data['customer']['dl_img_path']		= $dl_img;
				}
				if (file_exists($pp_ImgName)) {
					$data['customer']['pp_img_path']		= $pp_ImgName;
				}
				// }
				$data['main_content'] = self::CUS_VIEW . "form";
				$this->load->view('layout/template', $data);
				break;
		}
	}
	function sync_existing_data($mobile, $id_customer, $id_branch)
	{
		$data['id_customer'] = $id_customer;
		$data['id_branch'] = $id_branch;
		$data['branch_code'] = ($id_branch > 0 && $this->config->item("integrationType") == 4 ? $this->customer_model->getBranchCode() : NULL);
		$data['branchWise'] = 0;
		$data['mobile'] = $mobile;
		$res = $this->customer_model->insExisAcByMobile($data);
		//echo $this->db->last_query();exit;
		if (sizeof($res) > 0) {
			$payData = $this->customer_model->syncPayData($res);
			if (sizeof($payData['succeedIds']) > 0 || $payData['no_records'] > 0) {
				$status = $this->customer_model->updateInterTableStatus($res, $payData['succeedIds']);
				if ($status === TRUE) {
					return array("status" => TRUE, "msg" => "Purchase Plan registered successfully");
				} else {
					return array("status" => FALSE, "msg" => "Error in updating intermediate tables");
				}
			} else {
				return array("status" => FALSE, "msg" => "Error in updating payment tables");
			}
		} else {
			return array("status" => FALSE, "msg" => "No records to update in scheme account tables");
		}
	}
	//db transactions
	public function cus_post($type = "", $id = "")
	{
		$model = self::CUS_MODEL;
		$setmodel = self::SET_MODEL;
		switch ($type) {
			case 'Add':
				$cus = $this->input->post('customer');
				//   $entry_date = $this->admin_settings_model->settingsDB('get','','');
				if ($cus['id_branch'] != '') {
					$branch_date = $this->customer_model->get_entrydate($cus['id_branch']); // Taken from ret_day_closing  table branch wise //HH
					if ($branch_date['edit_custom_entry_date'] == 1) {
						// $entry_date=$entry_date['custom_entry_date'];
						$entry_date = $branch_date['custom_entry_date'];     //pr day close code update 19/06/2023
					}
				} else {
					$entry_date = NULL;
				}
				$cus_data = array(
					'info' => array(
						'firstname'			=> (isset($cus['firstname']) ? ucfirst($cus['firstname']) : NULL),
						'lastname' 			=> (isset($cus['lastname']) ? ucfirst($cus['lastname']) : NULL),
						'title' 			=> (isset($cus['title']) ? $cus['title'] : NULL),
						'id_branch'	    	=> (isset($cus['id_branch']) ? $cus['id_branch'] : NULL),
						'id_village'		=> ($cus['id_village'] != '' ? $cus['id_village'] : NULL),
						'id_employee' 	    =>  $this->session->userdata('uid'),
						'date_of_birth'		=> (isset($cus['date_of_birth']) && $cus['date_of_birth'] != '' ? date('Y-m-d', strtotime(str_replace("/", "-", $cus['date_of_birth']))) : NULL),
						'date_of_wed'		=> (isset($cus['date_of_wed']) && $cus['date_of_wed'] != '' ? date('Y-m-d', strtotime(str_replace("/", "-", $cus['date_of_wed']))) : NULL),
						'gst_number'		=> (isset($cus['gst_number']) ? $cus['gst_number'] : NULL),
						'email'				=> (isset($cus['email']) ? $cus['email'] : NULL),
						'mobile'			=> (isset($cus['mobile']) ? $cus['mobile'] : NULL),
						'gender'			=> (isset($cus['gender']) ? $cus['gender'] : '-1'),
						'cus_type'			=> (isset($cus['cus_type']) ? $cus['cus_type'] : '1'),
						'phone'				=> (isset($cus['phone']) ? $cus['phone'] : NULL),
						'nominee_name'		=> (isset($cus['nominee_name']) ? $cus['nominee_name'] : NULL),
						'nominee_relationship'		=> (isset($cus['nominee_relationship']) ? $cus['nominee_relationship'] : NULL),
						'nominee_mobile'	=> (isset($cus['nominee_mobile']) ? $cus['nominee_mobile'] : NULL),
						'cus_img'			=>	 NULL,
						'nominee_pan'       => (isset($cus['nominee_pan']) ? $cus['nominee_pan'] : NULL),
						'pan'				=> (isset($cus['pan']) ? $cus['pan'] : NULL),
						/* 'pan_proof'			=>	NULL,  */
						'voterid'			=> (isset($cus['voterid']) ? $cus['voterid'] : NULL),
						/* 'voterid_proof'		=>	 NULL,  */
						'rationcard'		=> (isset($cus['rationcard']) ? $cus['rationcard'] : NULL),
						/* 'rationcard_proof'	=>	NULL, 	 */
						'comments'			=> (isset($cus['comments']) ? $cus['comments'] : NULL),
						'username'			=> (isset($cus['username']) ? $cus['username'] : NULL),
						'passwd'			=> (isset($cus['passwd']) ? $this->$model->__encrypt($cus['passwd']) : NULL),
						'active'			=> (isset($cus['active']) ? $cus['active'] : 1),
						'date_add'			=>   date("Y-m-d H:i:s"),
						//	'custom_entry_date'	=>	(isset($cus['custom_entry_date'])? date('Y-m-d',strtotime(str_replace("/","-",$cus['custom_entry_date']))): NULL), 
						//		'custom_entry_date'   =>($entry_date[0]['edit_custom_entry_date']==1 ? $entry_date[0]['custom_entry_date']:NULL),
						'custom_entry_date'   => $entry_date,
						'added_by'			=> 1,
						'id_profession'	    	=> (isset($cus['id_profession']) ? $cus['id_profession'] : NULL),
						'aadharid'	    	=> (isset($cus['aadharid']) ? $cus['aadharid'] : NULL),
						'religion'	    	=> (isset($cus['religion']) && $cus['religion'] != '' ? $cus['religion'] : NULL),
						//store kyc details from kyc tab (pan & aadhar available in personal tab)....AddedOn:21/06/2023 By:Abi
						'driving_license_no'			=> (isset($cus['driving_license_no']) ? $cus['driving_license_no'] : NULL),
						'passport_no'			        => (isset($cus['passport_no']) ? $cus['passport_no'] : NULL),
						'fin_year_code'			        => (isset($cus['fin_year_code']) ? $cus['fin_year_code'] : NULL),
						'opening_balance_amount'	    => (isset($cus['opening_balance_amount']) ? $cus['opening_balance_amount'] : 0.00),
						'nominee_address1' =>(isset($cus['nominee_address1'])? $cus['nominee_address1']: ""),
						'nominee_address2' =>(isset($cus['nominee_address2'])? $cus['nominee_address2'] : "")
					),
					'address'				=> array(
						'id_country'		=> (isset($cus['country']) ? $cus['country'] : NULL),
						'id_state' 			=> (isset($cus['state']) ? $cus['state'] : NULL),
						'id_city'			=> (isset($cus['city']) ? $cus['city'] : NULL),
						'company_name'		=> ($cus['cus_type'] == 2 ? $cus['firstname'] : NULL),
						'address1'			=> (isset($cus['address1']) ? $cus['address1'] : NULL),
						'address2'			=> (isset($cus['address2']) ? $cus['address2'] : NULL),
						'address3'			=> (isset($cus['address3']) ? $cus['address3'] : NULL),
						'pincode'			=> (isset($cus['pincode']) ? $cus['pincode'] : NULL),
						'active'			=> (isset($cus['active']) ? $cus['active'] : 0),
						'date_add'			=>	date("Y-m-d H:i:s")
					)
				);
				// echo "<pre>";print_r($cus_data);echo "</pre>";exit;  
				$this->db->trans_begin();
				$cus_id =  $this->$model->insert_customer($cus_data);
				if ($cus_id > 0) {
					// Sync Existing Data					
					if ($this->config->item("integrationType") == 3 || $this->config->item("autoSyncExisting") == 1) {
						$syncData = $this->sync_existing_data($cus_data['info']['mobile'], $cus_id, $cus_data['info']['id_branch']);
					}
					// Create wallet account
					$wallet_acc =  $this->$setmodel->settingsDB('get', '', '');
					if ($wallet_acc[0]['wallet_account_type'] == 1) {
						$this->wallet_account_create($cus_id, $cus_data['info']['mobile']);
					}
					//webcam upload, #AD On:20-12-2022,Cd:CLin,Up:AB --->starts
					$p_ImgData  = json_decode(rawurldecode($cus['cus_img']));
					$precious = $p_ImgData[0];
					if (sizeof($p_ImgData) > 0) {
						$_FILES['cus_img']    =  array();
						$imgFile = $this->base64ToFile($precious->src);
						$_FILES['cus_img'] = $imgFile;
					}
					//webcam upload ends...
					//webcam kyc document upload starts... added_on : 21/6/2023 #ABI...
					if ($cus['aadhar_img'] != '' || $cus['pan_img'] != '' || $cus['dl_img'] != '' || $cus['pp_img'] != '') {
						$this->store_KycImages($cus, $cus_id);
					}
					//image data for kyc master starts new entry in kyc 
					$pan_number = (isset($cus['pan']) && $cus['pan'] != NULL && $cus['pan'] != '' ? $cus['pan'] : NULL);
					$kyc_data_img = $this->get_kyc_images($cus, $cus_id);
					//	print_r($kyc_data_img);exit;
					$pan_front_img = (isset($kyc_data_img['pan_front_img_url']) && $kyc_data_img['pan_front_img_url'] != NULL && $kyc_data_img['pan_front_img_url'] != '' ? $kyc_data_img['pan_front_img_url'] : NULL);
					$pan_back_img = (isset($kyc_data_img['pan_back_img_url']) && $kyc_data_img['pan_back_img_url'] != NULL && $kyc_data_img['pan_back_img_url'] != '' ? $kyc_data_img['pan_back_img_url'] : NULL);
					$doc_url = (isset($kyc_data_img['pan_doc_url']) && $kyc_data_img['pan_doc_url'] != NULL && $kyc_data_img['pan_doc_url'] != '' ? $kyc_data_img['pan_doc_url'] : NULL);
					if ($pan_number != '' || $pan_front_img != '' || $pan_back_img != '' || $doc_url != '') {
						//inserting pan data for the customer in kyc table
						//checking front image
						$kyc_ins_data = array(
							'id_customer'		=>	$cus_id,
							'kyc_type'			=>	2,
							'added_by'			=> 	1,
							'number'	    	=>  $pan_number,
							'status'			=> 	2,
							'verification_type'	=>	1,
							'date_add'			=>	date("Y-m-d H:i:s"),
							'img_url'			=>	$pan_front_img,
							'back_img_url'		=>	$pan_back_img,
							'document_url'		=>	$doc_url,
							'added_by'			=>	1,
							'emp_verified_by'   =>  $this->session->userdata('uid'),
						);
						//print_r($kyc_ins_data);exit;
						$ins_status =  $this->$model->insert_kyc($kyc_ins_data);
					}
					$aadhar_number = (isset($cus['aadharid']) ? $cus['aadharid'] : NULL);
					$aadhar_front_img = (isset($kyc_data_img['aadhar_img_front_url']) && $kyc_data_img['aadhar_img_front_url'] != NULL && $kyc_data_img['aadhar_img_front_url'] != '' ? $kyc_data_img['aadhar_img_front_url'] : NULL);
					$aadhar_back_img = (isset($kyc_data_img['aadhar_img_back_url']) && $kyc_data_img['aadhar_img_back_url'] != NULL && $kyc_data_img['aadhar_img_back_url'] != '' ? $kyc_data_img['aadhar_img_back_url'] : NULL);
					$aadhar_doc_url = (isset($kyc_data_img['aadhar_doc_url']) && $kyc_data_img['aadhar_doc_url'] != NULL && $kyc_data_img['aadhar_doc_url'] != '' ? $kyc_data_img['aadhar_doc_url'] : NULL);
					//inserting aadhar data for the customer in kyc table
					if ($aadhar_number != '' || $aadhar_front_img != '' || $aadhar_back_img != '' || $aadhar_doc_url != '') {
						$kyc_ins_data = array(
							'id_customer'		=>	$cus_id,
							'kyc_type'			=>	3,
							'added_by'			=> 	1,
							'number'	    	=>  $aadhar_number,
							'status'			=> 	2,
							'verification_type'	=>	1,
							'date_add'			=>	date("Y-m-d H:i:s"),
							'img_url'			=>	$aadhar_front_img,
							'document_url'		=>	$aadhar_doc_url,
							'back_img_url'		=>	$aadhar_back_img,
							'added_by'			=>	1,
							'emp_verified_by'   =>  $this->session->userdata('uid'),
						);
						//print_r($kyc_ins_data);exit;
						$ins_status =  $this->$model->insert_kyc($kyc_ins_data);
						//print_r($this->db->last_query());exit;
					}
					//image data for kyc master ends new entry in kyc
					//webcam kyc document upload ends
					// Upload image
					if (isset($_FILES['cus_img']['name']) || isset($_FILES['pan_proof']['name']) || isset($_FILES['voterid_proof']['name']) || isset($_FILES['rationcard_proof']['name'])) {
						$this->set_image($cus_id);
					}
				}
				if ($this->db->trans_status() === TRUE) {
					$this->db->trans_commit();
					$this->session->set_flashdata('chit_alert', array('message' => 'Customer record added successfully', 'class' => 'success', 'title' => 'Create Customer'));
					$this->session->set_userdata(array('cus_id'=>$cus_id,'cus_name'=>$cus['firstname'],'cus_mobile'=>$cus['mobile']));  // esakki 11-11
					redirect('account/add');
				} else {
					$this->db->trans_rollback();
					$this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed the requested process', 'class' => 'danger', 'title' => 'Create Customer'));
					redirect('customer');
				}
				break;
			case 'Edit':
				$cus = $this->input->post('customer');
				$pwd_check = $this->$model->check_password($id, $cus['passwd']);
				//   echo "<pre>";print_r($cus);exit;
				$cus_data = array(
					'info' => array(
						'firstname'			=> (isset($cus['firstname']) ? $cus['firstname'] : NULL),
						'lastname' 			=> (isset($cus['lastname']) ? $cus['lastname'] : NULL),
						'title' 			=> (isset($cus['title']) ? $cus['title'] : NULL),
						'id_branch'		=> (isset($cus['id_branch']) ? $cus['id_branch'] : NULL),
						'religion'		=> (!empty($cus['religion']) ? $cus['religion'] : NULL),
						'id_village'		=> ($cus['id_village'] != '' ? $cus['id_village'] : NULL),
						'date_of_birth'		=> (isset($cus['date_of_birth']) && $cus['date_of_birth'] != '' ? date('Y-m-d', strtotime(str_replace("/", "-", $cus['date_of_birth']))) : NULL),
						'date_of_wed'		=> (isset($cus['date_of_wed']) && $cus['date_of_wed'] != '' ? date('Y-m-d', strtotime(str_replace("/", "-", $cus['date_of_wed']))) : NULL),
						'gst_number'				=> (isset($cus['gst_number']) ? $cus['gst_number'] : NULL),
						'email'				=> (isset($cus['email']) ? $cus['email'] : NULL),
						'mobile'			=> (isset($cus['mobile']) ? $cus['mobile'] : NULL),
						'gender'			=> (isset($cus['gender']) ? $cus['gender'] : '-1'),
						'cus_type'			=>	$cus['cus_type'],
						'nominee_pan'       => (isset($cus['nominee_pan']) ? $cus['nominee_pan'] : NULL),
						'phone'				=> (isset($cus['phone']) ? $cus['phone'] : NULL),
						'nominee_name'			=> (isset($cus['nominee_name']) ? $cus['nominee_name'] : NULL),
						'nominee_relationship'		=> (isset($cus['nominee_relationship']) ? $cus['nominee_relationship'] : NULL),
						'nominee_mobile'	=> (isset($cus['nominee_mobile']) ? $cus['nominee_mobile'] : NULL),
						/* 	'cus_img'			=> (isset($cus['cus_img'])? $cus['cus_img']: (isset($cus['customer_img']) && $cus['customer_img'] != NULL ? $cus['customer_img'] : NULL )),  */
						'pan'				=> (isset($cus['pan']) ? $cus['pan'] : NULL),
						/* 'pan_proof'			=>	(isset($cus['pan_proof'])?$cus['pan_proof']: (isset($cus['pan_img']) && $cus['pan_img'] != NULL ? $cus['pan_img'] : NULL)),  */
						'voterid'			=> (isset($cus['voterid']) ? $cus['voterid'] : NULL),
						/* 'voterid_proof'		=>	(isset($cus['voterid_proof'])?$cus['voterid_proof']: (isset($cus['voter_img']) && $cus['voter_img'] != NULL ? $cus['voter_img'] : NULL)),  */
						'rationcard'		=> (isset($cus['rationcard']) ? $cus['rationcard'] : NULL),
						/* 	'rationcard_proof'	=>	(isset($cus['rationcard_proof'])?$cus['rationcard_proof']: (isset($cus['ration_img']) && $cus['ration_img'] != NULL ? $cus['ration_img'] : NULL)), 	 */
						'comments'			=> (isset($cus['comments']) ? $cus['comments'] : NULL),
						'username'			=> (isset($cus['username']) ? $cus['username'] : NULL),
						'passwd'			=> (isset($cus['passwd']) ? ($pwd_check == TRUE ? $cus['passwd'] : $this->$model->__encrypt($cus['passwd'])) : NULL),
						'active'			=> (isset($cus['active']) ? $cus['active'] : 0),
						'date_upd'			=>   date("Y-m-d H:i:s"),
						'id_profession'	    	=> (isset($cus['id_profession']) ? $cus['id_profession'] : NULL),
						'aadharid'	    	=> (isset($cus['aadharid']) ? $cus['aadharid'] : NULL),
						'religion'	    	=> (isset($cus['religion']) && $cus['religion'] != '' ? $cus['religion'] : NULL),
						//store kyc details from kyc tab (pan & aadhar available in personal tab)....AddedOn:21/06/2023 By:Abi
						'driving_license_no'			=> (isset($cus['driving_license_no']) ? $cus['driving_license_no'] : NULL),
						'passport_no'			        => (isset($cus['passport_no']) ? $cus['passport_no'] : NULL),
						'fin_year_code'			        => (isset($cus['fin_year_code']) ? $cus['fin_year_code'] : NULL),
						'opening_balance_amount'	    => (isset($cus['opening_balance_amount']) ? $cus['opening_balance_amount'] : 0.00),
						'nominee_address1' =>(isset($cus['nominee_address1'])? $cus['nominee_address1']: ""),
						'nominee_address2' =>(isset($cus['nominee_address2'])? $cus['nominee_address2'] : "")
					),
					'address'				=> array(
						'id_country'		=> (isset($cus['country']) ? $cus['country'] : NULL),
						'id_state' 			=> (isset($cus['state']) ? $cus['state'] : NULL),
						'id_city'			=> (isset($cus['city']) ? $cus['city'] : NULL),
						'company_name'		=> ($cus['cus_type'] == 2 ? $cus['firstname'] : NULL),
						'address1'			=> (isset($cus['address1']) ? $cus['address1'] : NULL),
						'address2'			=> (isset($cus['address2']) ? $cus['address2'] : NULL),
						'address3'			=> (isset($cus['address3']) ? $cus['address3'] : NULL),
						'pincode'			=> (isset($cus['pincode']) ? $cus['pincode'] : NULL),
						'active'			=> (isset($cus['active']) ? $cus['active'] : 0),
						'date_upd'			=>	date("Y-m-d H:i:s")
					)
				);
				$this->db->trans_begin();
				//echo "<pre>";print_r($cus_data);exit;
				$cus_id = $this->$model->update_customer($cus_data, $id);
				if ($id > 0) {
					// Sync Existing Data					
					if ($this->config->item("integrationType") == 3 || $this->config->item("autoSyncExisting") == 1) {
						$syncData = $this->sync_existing_data($cus_data['info']['mobile'], $id, $cus_data['info']['id_branch']);
					}
					//webcam upload, #AD On:20-12-2022,Cd:CLin,Up:AB     
					$p_ImgData  = json_decode(rawurldecode($cus['cus_img']));
					$precious = $p_ImgData[0];
					if (sizeof($p_ImgData) > 0) {
						$_FILES['cus_img']    =  array();
						$imgFile = $this->base64ToFile($precious->src);
						$_FILES['cus_img'] = $imgFile;
					}
					//webcam upload ends...
					//webcam kyc document upload starts... added_on : 21/6/2023 #ABI...
					if ($cus['aadhar_img'] != '' || $cus['pan_img'] != '' || $cus['dl_img'] != '' || $cus['pp_img'] != '') {
						$this->store_KycImages($cus, $id);
					}
					//image data for kyc master starts
					$existingkyc = $this->$model->get_kyc_byid($cus_id);
					//print_r($existingkyc);exit;
					if ($existingkyc != null && $existingkyc != '') {
						$kyc_data_img = $this->get_kyc_images($cus, $cus_id);
						//print_r($kyc_data_img['pan_front_img_url']);exit;
						$existing_pan = $this->$model->get_kyc_byid($cus_id, 2);
						//print_r($existing_pan);exit;
						if ($existing_pan != null && $existing_pan != '') {
							//front image
							if (isset($kyc_data_img['pan_front_img_url']) && $kyc_data_img['pan_front_img_url'] != NULL) {
								$img_data = $kyc_data_img['pan_front_img_url'];
							} else if ($existing_pan[0]['img_url'] != '' && $existing_pan[0]['img_url'] != NULL) {
								$img_data = $existing_pan[0]['img_url'];
							} else {
								$img_data = NULL;
							}
							//back image
							if (isset($kyc_data_img['pan_back_img_url']) && $kyc_data_img['pan_back_img_url'] != NULL) {
								$back_img_data = $kyc_data_img['pan_back_img_url'];
								//print_r($back_img_data);exit;
							} else if ($existing_pan[0]['back_img_url'] != '' && $existing_pan[0]['back_img_url'] != NULL) {
								$back_img_data = $existing_pan[0]['back_img_url'];
							} else {
								$back_img_data = NULL;
							}
							//document
							if (isset($kyc_data_img['pan_doc_url']) && $kyc_data_img['pan_doc_url'] != NULL && $kyc_data_img['pan_doc_url'] != '') {
								$doc_url = $kyc_data_img['pan_doc_url'];
								//  print_r($doc_url);exit;
							} else if ($existing_pan[0]['document_url'] != '' && $existing_pan[0]['document_url'] != NULL) {
								$doc_url = $existing_pan[0]['document_url'];
							} else {
								$doc_url = NULL;
							}
							if ($kyc_data_img != '' && $kyc_data_img != null) {
								$kyc_ins_data = array(
									'last_update'		=>	date("Y-m-d H:i:s"),
									'img_url'			=>	$img_data,
									'back_img_url'      =>  $back_img_data,
									'document_url'      =>  $doc_url,
								);
								//	print_r($kyc_ins_data);exit;
							}
							$kyc_ins_data['number'] =	(isset($cus['pan']) ? $cus['pan'] : NULL);
							//	print_r($kyc_ins_data);exit;
							$ins_status =  $this->$model->updkycData($kyc_ins_data, $id, 2);
							//	print_r($this->db->last_query());exit;
						} else {
							$pan_number = (isset($cus['pan']) && $cus['pan'] != '' ? $cus['pan'] : NULL);
							$pan_front_img = (isset($kyc_data_img['pan_front_img_url']) && $kyc_data_img['pan_front_img_url'] != NULL && $kyc_data_img['pan_front_img_url'] != '' ? $kyc_data_img['pan_front_img_url'] : NULL);
							$pan_back_img = (isset($kyc_data_img['pan_back_img_url']) && $kyc_data_img['pan_back_img_url'] != NULL && $kyc_data_img['pan_back_img_url'] != '' ? $kyc_data_img['pan_back_img_url'] : NULL);
							$doc_url = (isset($kycData['pan_doc_url']) && $kycData['pan_doc_url'] != NULL && $kycData['pan_doc_url'] != '' ? $kycData['pan_doc_url'] : NULL);
							if ($pan_number != '' || $pan_front_img != '' || $pan_back_img != '') {
								$kyc_ins_data = array(
									'id_customer'		=>	$cus_id,
									'kyc_type'			=>	2,
									'added_by'			=> 	1,
									'number'	    	=>  $pan_number,
									'status'			=> 	2,
									'verification_type'	=>	1,
									'date_add'			=>	date("Y-m-d H:i:s"),
									//'img_url'			=>	$kyc_data_img['pan_front_img_url'],
									'img_url'			=>	$pan_front_img,
									'back_img_url'		=>	$pan_back_img,
									'document_url'		=>	$doc_url,
									'added_by'			=>	1,
									'emp_verified_by'   =>  $this->session->userdata('uid'),
								);
								//print_r($kyc_ins_data);exit;
								$ins_status =  $this->$model->insert_kyc($kyc_ins_data);
							}
						}
						$existing_aadhar = $this->$model->get_kyc_byid($cus_id, 3);
						// print_r($existing_aadhar);exit;
						if ($existing_aadhar != null && $existing_aadhar != '') {
							$img_data = '';
							//  print_r($kyc_data_img);exit;
							//front image
							if (isset($kyc_data_img['aadhar_img_front_url']) && $kyc_data_img['aadhar_img_front_url'] != NULL) {
								$img_data = $kyc_data_img['aadhar_img_front_url'];
								//print_r($img_data);exit;
							} else if ($existing_aadhar[0]['img_url'] != '' && $existing_aadhar[0]['img_url'] != NULL) {
								$img_data = $existing_aadhar[0]['img_url'];
							} else {
								$img_data = NULL;
							}
							//back image
							$back_img_data = '';
							// print_r($kyc_data_img['aadharimg_url']);exit;
							if (isset($kyc_data_img['aadhar_img_back_url']) && $kyc_data_img['aadhar_img_back_url'] != NULL) {
								$back_img_data = $kyc_data_img['aadhar_img_back_url'];
								// print_r($back_img_data);exit;
							} else if ($existing_aadhar[0]['back_img_url'] != '' && $existing_aadhar[0]['back_img_url'] != NULL) {
								$back_img_data = $existing_aadhar[0]['back_img_url'];
							} else {
								$back_img_data = NULL;
							}
							// print_r($kyc_data_img['aadhar_doc_url']);exit;
							//document
							if (isset($kyc_data_img['aadhar_doc_url']) && $kyc_data_img['aadhar_doc_url'] != NULL) {
								$aadhar_doc_url = $kyc_data_img['aadhar_doc_url'];
								//print_r($aadhar_doc_url);exit;
							} else if ($existing_aadhar[0]['document_url'] != '' && $existing_aadhar[0]['document_url'] != NULL) {
								$aadhar_doc_url = $existing_aadhar[0]['document_url'];
							} else {
								$aadhar_doc_url = NULL;
							}
							// print_r($aadhar_doc_url);exit;
							if ($kyc_data_img != '' && $kyc_data_img != null) {
								$kyc_ins_data = array(
									'last_update'		=>	date("Y-m-d H:i:s"),
									'img_url'			=>	$img_data,
									'back_img_url'		=>	$back_img_data,
									'document_url'		=>	$aadhar_doc_url,
								);
							}
							$kyc_ins_data['number'] = (isset($cus['aadharid']) ? $cus['aadharid'] : NULL);
							//print_r($kyc_ins_data);exit;
							$ins_status =  $this->$model->updkycData($kyc_ins_data, $id, 3);
							//print_r($this->db->last_query());exit;
						} else {
							$aadhar_number = (isset($cus['aadharid']) && $cus['aadharid'] != '' ? $cus['aadharid'] : NULL);
							$aadhar_front_img = (isset($kyc_data_img['aadhar_img_front_url']) && $kyc_data_img['aadhar_img_front_url'] != NULL && $kyc_data_img['aadhar_img_front_url'] != '' ? $kyc_data_img['aadhar_img_front_url'] : NULL);
							$aadhar_back_img = (isset($kyc_data_img['aadhar_img_back_url']) && $kyc_data_img['aadhar_img_back_url'] != NULL && $kyc_data_img['aadhar_img_back_url'] != '' ? $kyc_data_img['aadhar_img_back_url'] : NULL);
							$aadhar_doc_url = (isset($kyc_data_img['aadhar_doc_url']) && $kyc_data_img['aadhar_doc_url'] != NULL && $kyc_data_img['aadhar_doc_url'] != '' ? $kyc_data_img['aadhar_doc_url'] : NULL);
							if ($aadhar_number != '' || $aadhar_front_img != '' || $aadhar_back_img != '' || $aadhar_doc_url != '') {
								$kyc_ins_data = array(
									'id_customer'		=>	$cus_id,
									'kyc_type'			=>	3,
									'added_by'			=> 	1,
									'number'	    	=>  $aadhar_number,
									'status'			=> 	2,
									'verification_type'	=>	1,
									'date_add'			=>	date("Y-m-d H:i:s"),
									//'img_url'			=>	$kyc_data_img['aadharimg_url'],
									'img_url'			=>	$aadhar_front_img,
									'back_img_url'		=>	$aadhar_back_img,
									'document_url'		=>	$aadhar_doc_url,
									'added_by'			=>	1,
									'emp_verified_by'   =>  $this->session->userdata('uid'),
								);
								//print_r($kyc_ins_data);exit;
								$ins_status =  $this->$model->insert_kyc($kyc_ins_data);
							}
						}
					} else {
						//image data for kyc master starts new entry in kyc 
						$pan_number = (isset($cus['pan']) ? $cus['pan'] : NULL);
						$kyc_data_img = $this->get_kyc_images($cus, $cus_id);
						$pan_front_img = (isset($kyc_data_img['pan_front_img_url']) ? $kyc_data_img['pan_front_img_url'] : NULL);
						$pan_back_img = (isset($kyc_data_img['pan_back_img_url']) ? $kyc_data_img['pan_back_img_url'] : NULL);
						$doc_url = (isset($kycData['pan_doc_url']) && $kycData['pan_doc_url'] != NULL && $kycData['pan_doc_url'] != '' ? $kycData['pan_doc_url'] : NULL);
						//	print_r($kyc_data_img);exit;
						if ($pan_number != '' || $pan_front_img != '' || $pan_back_img != '') {
							//inserting pan data for the customer in kyc table
							//checking front image
							$kyc_ins_data = array(
								'id_customer'		=>	$cus_id,
								'kyc_type'			=>	2,
								'added_by'			=> 	1,
								'number'	    	=>  $pan_number,
								'status'			=> 	2,
								'verification_type'	=>	1,
								'date_add'			=>	date("Y-m-d H:i:s"),
								'img_url'			=>	$pan_front_img,
								'back_img_url'		=>	$pan_back_img,
								'document_url'		=>	$doc_url,
								'added_by'			=>	1,
								'emp_verified_by'   =>  $this->session->userdata('uid'),
							);
							//print_r($kyc_ins_data);exit;
							$ins_status =  $this->$model->insert_kyc($kyc_ins_data);
						}
						$aadhar_number = (isset($cus['aadharid']) ? $cus['aadharid'] : NULL);
						$aadhar_front_img = (isset($kyc_data_img['aadhar_img_front_url']) ? $kyc_data_img['aadhar_img_front_url'] : NULL);
						$aadhar_back_img = (isset($kyc_data_img['aadhar_img_back_url']) ? $kyc_data_img['aadhar_img_back_url'] : NULL);
						$aadhar_doc_url = (isset($kyc_data_img['aadhar_doc_url']) && $kyc_data_img['aadhar_doc_url'] != NULL && $kyc_data_img['aadhar_doc_url'] != '' ? $kyc_data_img['aadhar_doc_url'] : NULL);
						//inserting aadhar data for the customer in kyc table
						if ($aadhar_number != '' || $aadhar_front_img != '' || $aadhar_back_img != '' || $aadhar_doc_url != '') {
							$kyc_ins_data = array(
								'id_customer'		=>	$cus_id,
								'kyc_type'			=>	3,
								'added_by'			=> 	1,
								'number'	    	=>  $aadhar_number,
								'status'			=> 	2,
								'verification_type'	=>	1,
								'date_add'			=>	date("Y-m-d H:i:s"),
								'img_url'			=>	$aadhar_front_img,
								'back_img_url'		=>	$aadhar_back_img,
								'document_url'		=>	$aadhar_doc_url,
								'added_by'			=>	1,
								'emp_verified_by'   =>  $this->session->userdata('uid'),
							);
							//print_r($kyc_ins_data);exit;
							$ins_status =  $this->$model->insert_kyc($kyc_ins_data);
						}
						//image data for kyc master ends new entry in kyc
					}
					//image data for kyc master ends
					//webcam kyc document upload ends
					// upload image
					if (isset($_FILES['cus_img']['name']) || isset($_FILES['pan_proof']['name']) || isset($_FILES['voterid_proof']['name']) || isset($_FILES['rationcard_proof']['name'])) {
						$this->set_image($id);
					}
				}
				if ($this->db->trans_status() === TRUE) {
					$this->db->trans_commit();
					$this->session->set_flashdata('chit_alert', array('message' => 'Customer record modified successfully', 'class' => 'success', 'title' => 'Edit Customer'));
					redirect('customer');
				} else {
					$this->db->trans_rollback();
					$this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed the requested process', 'class' => 'danger', 'title' => 'Edit Customer'));
					redirect('customer');
				}
				break;
			case 'Delete':
				$acc = $this->$model->check_acc_records($id);
				$pay = $this->$model->check_pay_records($id);
				if ($acc == TRUE || $pay == TRUE) {
					if ($pay == TRUE) {
						$this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request, Check whether payment entry exists for this customer', 'class' => 'danger', 'title' => 'Delete Scheme'));
					} else {
						$this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request, Check whether savings scheme account is assigned for this customer', 'class' => 'danger', 'title' => 'Delete Scheme'));
					}
				} else {
					$this->db->trans_begin();
					$this->$model->delete_customer($id);
					if ($this->db->trans_status() === TRUE) {
						//Remove image and its folder
						if (is_dir(self::CUS_IMG_PATH . $id)) {
							$this->rrmdir(self::CUS_IMG_PATH . $id);
						}
						$this->db->trans_commit();
						$this->session->set_flashdata('chit_alert', array('message' => 'Customer deleted successfully', 'class' => 'success', 'title' => 'Delete Customer'));
					} else {
						$this->db->trans_rollback();
						$this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request', 'class' => 'danger', 'title' => 'Delete Customer'));
					}
				}
				redirect('customer');
				break;
		}
	}
	function check_username($username)
	{
		$model_name = self::CUS_MODEL;
		$is_taken = $this->$model_name->username_available($username);
		if ($is_taken) {
			$this->session->set_flashdata('chit_alert', array('message' => 'User name already taken, Please try another name', 'class' => 'danger', 'title' => 'Check Username Available'));
		}
		echo $is_taken;
	}
	function check_mobile()
	{
		$mobile = $this->input->post('mobile');
		$id_customer = $this->input->post('id_customer');
		$model_name = self::CUS_MODEL;
		if ($id_customer) {
			$available = $this->$model_name->mobile_available($mobile, $id_customer);
		} else {
			$available = $this->$model_name->mobile_available($mobile);
		}
		if ($available) {
			echo 1;
		} else {
			echo 0;
		}
	}
	function check_email()
	{
		$email = $this->input->post('email');
		$id_customer = $this->input->post('id_customer');
		$model_name = self::CUS_MODEL;
		if ($id_customer) {
			$available = $this->$model_name->email_available($email, $id_customer);
		} else {
			$available = $this->$model_name->email_available($email);
		}
		if ($available) {
			echo TRUE;
		} else {
			echo FALSE;
		}
	}
	function upload_img__($field, $img_path, $filename)
	{
		if (!is_dir($img_path)) {
			mkdir($img_path, 0777, TRUE);
		}
		if ($_FILES && $_FILES[$field]["tmp_name"] != "") {
			//print_r($_FILES);
			list($w, $h) = getimagesize($_FILES[$field]["tmp_name"]);
			/* calculate new image size with ratio */
			$width = 900;
			$height = 900;
			$ratio = max($width / $w, $height / $h);
			$h = ceil($height / $ratio);
			$x = ($w - $width / $ratio) / 2;
			$w = ceil($width / $ratio);
			/* new file name */
			$path = trim($img_path) . $filename;
			/* read binary data from image file */
			$imgString = file_get_contents($_FILES[$field]['tmp_name']);
			/* create image from string */
			$image = imagecreatefromstring($imgString);
			$tmp = imagecreatetruecolor($width, $height);
			imagecopyresampled(
				$tmp,
				$image,
				0,
				0,
				$x,
				0,
				$width,
				$height,
				$w,
				$h
			);
			/* Save image */
			switch ($_FILES[$field]['type']) {
				case 'image/jpeg':
					imagejpeg($tmp, $path, 60);
					break;
				case 'image/png':
					imagepng($tmp, $path, 0);
					break;
				case 'image/gif':
					imagegif($tmp, $path);
					break;
				default:
					exit;
					break;
			}
			$file_name = $path;
			imagedestroy($image);
			imagedestroy($tmp);
		}
	}
	function upload_img($outputImage, $dst, $img)
	{
		if (($img_info = getimagesize($img)) === FALSE) {
			// die("Image not found or not an image");
			return false;
		}
		$width = $img_info[0];
		$height = $img_info[1];
		switch ($img_info[2]) {
			case IMAGETYPE_GIF:
				$src = imagecreatefromgif($img);
				$tmp = imagecreatetruecolor($width, $height);
				$kek = imagecolorallocate($tmp, 255, 255, 255);
				imagefill($tmp, 0, 0, $kek);
				break;
			case IMAGETYPE_JPEG:
				$src = imagecreatefromjpeg($img);
				$tmp = imagecreatetruecolor($width, $height);
				break;
			case IMAGETYPE_PNG:
				$src = imagecreatefrompng($img);
				$tmp = imagecreatetruecolor($width, $height);
				$kek = imagecolorallocate($tmp, 255, 255, 255);
				imagefill($tmp, 0, 0, $kek);
				break;
			default: //die("Unknown filetype");	
				return false;
		}
		imagecopyresampled($tmp, $src, 0, 0, 0, 0, $width, $height, $width, $height);
		imagejpeg($tmp, $dst);
	}
	function set_image($cus_id)
	{
		$data = array();
		$img_path = self::CUS_IMG_PATH . "/" . $cus_id;
		if (!is_dir($img_path)) {
			mkdir($img_path, 0777, TRUE);
		}
		if ($_FILES['cus_img']['name']) {
			//  $ext = pathinfo($_FILES['cus_img']['name'], PATHINFO_EXTENSION);
			$img = $_FILES['cus_img']['tmp_name'];
			$path = self::CUS_IMG_PATH . $cus_id . "/" . self::CUS_IMG;
			$filename = self::CUS_IMG . ".jpg";
			$this->upload_img('cus_img', $path, $img);
			$data['cus_img'] = $filename;
		}
		if ($_FILES['pan_proof']['name'] != "") {
			//$ext = pathinfo($_FILES['pan_proof']['name'], PATHINFO_EXTENSION); 
			$img = $_FILES['pan_proof']['tmp_name'];
			$path = self::CUS_IMG_PATH . $cus_id . "/" . self::PAN_IMG;
			$filename = self::PAN_IMG . ".jpg";
			$this->upload_img('pan_proof', $path, $img);
			$data['pan_proof'] = $filename;
		}
		if ($_FILES['voterid_proof']['name'] != "") {
			// $ext = pathinfo($_FILES['voterid_proof']['name'], PATHINFO_EXTENSION); 
			$img = $_FILES['voterid_proof']['tmp_name'];
			$path = self::CUS_IMG_PATH . $cus_id . "/" . self::VOTERID_IMG;
			$filename = self::VOTERID_IMG . ".jpg";
			$this->upload_img('voterid_proof', $path, $img);
			$data['voterid_proof'] =  $filename;
		}
		if ($_FILES['rationcard_proof']['name'] != "") {
			//$ext = pathinfo($_FILES['rationcard_proof']['name'], PATHINFO_EXTENSION);  
			$img = $_FILES['rationcard_proof']['tmp_name'];
			$path = self::CUS_IMG_PATH . $cus_id . "/" . self::RATION_IMG;
			$filename = self::RATION_IMG . ".jpg";
			$this->upload_img('rationcard_proof', $path, $img);
			$data['rationcard_proof'] = $filename;
		}
	}
	function rrmdir($path)
	{
		// Open the source directory to read in files
		$i = new DirectoryIterator($path);
		foreach ($i as $f) {
			if ($f->isFile()) {
				unlink($f->getRealPath());
			} else if (!$f->isDot() && $f->isDir()) {
				rrmdir($f->getRealPath());
			}
		}
		rmdir($path);
	}
	function profile_status($status, $id)
	{
		$data = array('profile_complete' => $status);
		$model = self::CUS_MODEL;
		$status = $this->$model->update_customer_only($data, $id);
		if ($status) {
			$this->session->set_flashdata('chit_alert', array('message' => 'Profile status updated as ' . ($status ? 'complete' : 'incomplete') . ' successfully.', 'class' => 'success', 'title' => 'Profile Status'));
		} else {
			$this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed the requested operation', 'class' => 'danger', 'title' => 'Profile Status'));
		}
		redirect('customer');
	}
	function customer_status($status, $id)
	{
		$data = array('active' => $status);
		$model = self::CUS_MODEL;
		$status = $this->$model->update_customer_only($data, $id);
		if ($status) {
			$this->session->set_flashdata('chit_alert', array('message' => 'Customer status updated as ' . ($status ? 'active' : 'inactive') . ' successfully.', 'class' => 'success', 'title' => 'Customer Status'));
		} else {
			$this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed the requested operation', 'class' => 'danger', 'title' => 'Customer Status'));
		}
		redirect('customer');
	}
	//To download files
	public function download($id, $file)
	{
		$img_path = self::CUS_IMG_PATH . $id . "/" . $file . ".jpg";
		//load the download helper
		$this->load->helper('download');
		$data = file_get_contents($img_path);
		$name = $file . '_' . $id . '.jpg';
		force_download($name, $data);
		return TRUE;
	}
	// wallet account insert //
	function wallet_account_create($cus_id, $mobile)
	{
		$set_model   = self::SET_MODEL;
		$wallmodel   = self::WALL_MODEL;
		$log_model   = self::LOG_MODEL;
		$chits_model = self::ADM_MODEL;
		$sms_model   = self::SMS_MODEL;
		$mail_model  = self::MAIL_MODEL;
		$wallet_acc_no =  $this->$wallmodel->get_wallet_acc_number();
		$insertData = array(
			'id_customer' 	   => (isset($cus_id) && $cus_id != '' ? $cus_id : NULL),
			'id_employee' 	   =>  $this->session->userdata('uid'),
			'wallet_acc_number' => (isset($wallet_acc_no) ? $wallet_acc_no : NULL),
			'issued_date' 	   => date('y-m-d H:i:s'),
			'remark' 		    => "Credits",
			'active'		        => 1
		);
		//inserting data                  
		$status = $this->$wallmodel->wallet_accountDB("insert", "", $insertData);
		$wallAcc = $this->$wallmodel->wallet_accountDB("get", $status['insertID']);
		//  $this->$wallmodel->insChitwallet($status['insertID'],$mobile,$cus_id);
		if ($status) {
			$log_data = array(
				'id_log'     => $this->session->userdata('id_log'),
				'event_date' => date("Y-m-d H:i:s"),
				'module'     => 'Wallet Account',
				'operation'  => 'Add',
				'record'     => $status['insertID'],
				'remark'     => 'Wallet Account added successfully'
			);
			$this->$log_model->log_detail('insert', '', $log_data);
			$serviceID = 8;
			$service =  $this->$set_model->get_service($serviceID);
			$company = $this->$set_model->get_company();
			if ($service['serv_sms'] == 1) {
				//   '0 - promotion sms , 1 - otp',
				$otp_promotion = 1;
				$id = $status['insertID'];
				$data = $this->$sms_model->get_SMS_data($serviceID, $id);
				$mobile = $data['mobile'];
				$message = $data['message'];
				$dlt_te_id = $service['dlt_te_id'];
				if ($this->config->item('sms_gateway') == '1') {
					$this->sms_model->sendSMS_MSG91($mobile, $message, $otp_promotion, $dlt_te_id);
				} elseif ($this->config->item('sms_gateway') == '2') {
					$this->sms_model->sendSMS_Nettyfish($mobile, $message, 'promo', $dlt_te_id);
				} elseif ($this->config->item('sms_gateway') == '3') {
					$this->sms_model->sendSMS_SpearUC($mobile, $message, '', $dlt_te_id);
				} elseif ($this->config->item('sms_gateway') == '4') {
					$this->sms_model->sendSMS_Asterixt($mobile, $message, '', $dlt_te_id);
				} elseif ($this->config->item('sms_gateway') == '5') {
					$this->sms_model->sendSMS_Qikberry($mobile, $message, '', $dlt_te_id);
				}
			}
			$email	=   $wallAcc['email'];
			if ($service['serv_email'] == 1  && $email != '') {
				$data['walData'] = $wallAcc;
				$data['company'] = $company;
				$data['type'] = 1;
				$to = $email;
				$subject = "Reg- " . $company['company_name'] . " saving scheme wallet account creation.";
				$message = $this->load->view('include/emailWallet', $data, true);
				$sendEmail = $this->$mail_model->send_email($to, $subject, $message);
			}
		}
	}
	// wallet account insert //
	public function get_customer_by_mobile()
	{
		$model_name = self::CUS_MODEL;
		$mobile = $this->input->post('mobile');
		$customer = $this->$model_name->get_customer_by_mobile($mobile);
		echo json_encode($customer);
	}
	public function ajax_get_customers_list()
	{
		$mobile = $this->input->post('mobile');
		$id_scheme = $this->input->post('id_scheme');
		$model_name = self::CUS_MODEL;
		$cus_data = $this->$model_name->ajax_get_customers_list($mobile, $id_scheme);
		//	echo "<pre>"; print_r($cus_data);exit;
		echo json_encode($cus_data);
	}
	public function ajax_get_village()
	{
		$model_name = self::CUS_MODEL;
		$cus_data = $this->$model_name->get_village();
		echo json_encode($cus_data);
	}
	public function cus_profile($type = "", $id = "")
	{
		$model = self::CUS_MODEL;
		switch ($type) {
			case 'list':
				$data['main_content'] = self::CUS_VIEW . "profile";
				$this->load->view('layout/template', $data);
				break;
			case 'edit':
				$data = $this->$model->Searchcustomer($_POST['searchTxt']);
				echo json_encode($data);
				break;
			case 'update':
				$cus = $this->input->post('customer');
				$cus_data = array(
					'info' => array(
						'firstname'			=> (isset($cus['firstname']) ? strtoupper($cus['firstname']) : NULL),
						'lastname' 			=> (isset($cus['lastname']) ? strtoupper($cus['lastname']) : NULL),
						'id_branch'		    => ($this->session->userdata('id_branch') != '' ? $this->session->userdata('id_branch') : NULL),
						'religion'		    => (!empty($cus['religion']) ? $cus['religion'] : NULL),
						'id_village'		=> ($cus['id_village'] != '' ? $cus['id_village'] : NULL),
						'date_of_birth'		=> (isset($cus['date_of_birth']) && $cus['date_of_birth'] != '' ? date('Y-m-d', strtotime(str_replace("/", "-", $cus['date_of_birth']))) : NULL),
						'date_of_wed'		=> (isset($cus['date_of_wed']) && $cus['date_of_wed'] != '' ? date('Y-m-d', strtotime(str_replace("/", "-", $cus['date_of_wed']))) : NULL),
						'email'				=> (isset($cus['email']) ? $cus['email'] : NULL),
						'gender'			=> (isset($cus['gender']) ? $cus['gender'] : '-1'),
						'send_promo_sms'	=> (isset($cus['send_promo_sms']) ? $cus['send_promo_sms'] : 0),
						'date_upd'			=>   date("Y-m-d H:i:s")
					),
					'address'				=> array(
						'id_country'		=> (isset($cus['country']) ? $cus['country'] : NULL),
						'id_state' 			=> (isset($cus['state']) ? $cus['state'] : NULL),
						'id_city'			=> (isset($cus['city']) ? $cus['city'] : NULL),
						'company_name'			=> (isset($cus['company_name']) ? $cus['company_name'] : NULL),
						'address1'			=> (isset($cus['address1']) ? strtoupper($cus['address1']) : NULL),
						'address2'			=> (isset($cus['address2']) ? strtoupper($cus['address2']) : NULL),
						'address3'			=> (isset($cus['address3']) ? strtoupper($cus['address3']) : NULL),
						'pincode'			=> (isset($cus['pincode']) ? $cus['pincode'] : NULL),
						'active'			=> (isset($cus['active']) ? $cus['active'] : 0),
						'date_upd'			=>	date("Y-m-d H:i:s")
					),
				);
				$this->db->trans_begin();
				$cus_id = $this->$model->update_customer($cus_data, $cus['id_customer']);
				//echo "<pre>"; print_r($this->db->last_query());exit;
				if ($this->db->trans_status() === TRUE) {
					$log_data = array(
						'id_log'        => $this->session->userdata('id_log'),
						'event_date'	=> date("Y-m-d H:i:s"),
						'module'      	=> 'Customer',
						'operation'   	=> 'Edit',
						'record'        => $id,
						'remark'       	=> 'Record edited successfully'
					);
					$this->log_model->log_detail('insert', '', $log_data);
					$this->db->trans_commit();
					$return_data = array('status' => true);
					$this->session->set_flashdata('chit_alert', array('message' => 'Customer record modified successfully', 'class' => 'success', 'title' => 'Edit Customer'));
				} else {
					$this->db->trans_rollback();
					$return_data = array('status' => false);
					$this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed the requested process', 'class' => 'danger', 'title' => 'Edit Customer'));
				}
				echo json_encode($return_data);
				break;
		}
	}
	function ajax_getAllActiveAgents()
	{
		$model_name = self::CUS_MODEL;
		$agent_select = $this->$model_name->getAllActiveAgents();
		echo json_encode($agent_select);
	}
	function allocate_agent_toCuctomers()
	{
		$model = self::CUS_MODEL;
		$cus = $this->input->post('id_customer');
		$id_agent = $this->input->post('id_agent');
		$total = count($cus);
		$total = array();
		if ($total > 0) {
			$this->load->model($model);
			$i = 0;
			foreach ($cus as $cusid) {
				$upd_data = array('id_agent' => $id_agent);
				$cusData = $this->$model->allocate_agent($upd_data, $cusid);
				if ($this->db->trans_status() === TRUE) {
					$i++;
				} else {
					$not_allocated[] = $cusid;
				}
			}
			$result = $i;
		}
		if (count($cus) == $i) {
			echo json_encode(array('status' => 1, 'total' => count($cus)));
		} elseif ($i == 0) {
			echo json_encode(array('status' => 0, 'total' => count($cus), 'not_allocated' => $not_allocated));
		} else {
			echo json_encode(array('status' => 2, 'total' => count($cus), 'not_allocated' => $not_allocated));
		}
	}
	//webcam upload, #AD On:20-12-2022,Cd:CLin,Up:AB	
	public function base64ToFile($imgBase64)
	{
		$data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imgBase64)); // might not work on some systems, specify your temp path if system temp dir is not writeable
		$temp_file_path = tempnam(sys_get_temp_dir(), 'tempimg');
		file_put_contents($temp_file_path, $data);
		$image_info = getimagesize($temp_file_path);
		$imgFile = array(
			'name' => uniqid() . '.' . preg_replace('!\w+/!', '', $image_info['mime']),
			'tmp_name' => $temp_file_path,
			'size'  => filesize($temp_file_path),
			'error' => UPLOAD_ERR_OK,
			'type'  => $image_info['mime'],
		);
		return $imgFile;
	}
	public function ajax_get_scheme_account_list()
	{
		$model_name = self::CUS_MODEL;
		$scheme_acc_number = $this->input->post('acc_no');
		$scheme_code = $this->input->post('scheme_code');
		$cus_data = $this->$model_name->ajax_get_scheme_account_list($scheme_code, $scheme_acc_number);
		echo json_encode($cus_data);
	}
	function format_accRcptNo($type, $id)
	{
		$format = $this->customer_model->format_accRcptNo($type, $id);
		echo json_encode($format);
	}
	public function  store_KycImages($cus, $id)
	{
		$model = self::CUS_MODEL;
		//webcam kyc document upload starts... added_on : 7/6/2023 #AB...
		$pan_img  = json_decode(rawurldecode($cus['pan_img']));
		if (sizeof($pan_img) > 0) {
			$_FILES['pan_img']    =  array();
			$pan = explode(";base64,", $pan_img[0]->src);
			$panimagebase64  = base64_decode($pan[1]);
			$img_path = "assets/img/customer/" . $id . "/";
			if (!is_dir($img_path)) {
				mkdir($img_path, 0777, TRUE);
			}
			if (file_put_contents($img_path . 'pan_' . $id . '.png', $panimagebase64)) {
				$updCus = array('pan_ImgName' => 'pan_' . $id . '.png');
				$this->$model->updData($updCus, 'id_customer', $id, 'customer');
			}
		}
		$aadhar_img  = json_decode(rawurldecode($cus['aadhar_img']));
		if (sizeof($aadhar_img) > 0) {
			$_FILES['pan_img']    =  array();
			$aadhar = explode(";base64,", $aadhar_img[0]->src);
			$aadharimagebase64  = base64_decode($aadhar[1]);
			$img_path = "assets/img/customer/" . $id . "/";
			if (!is_dir($img_path)) {
				mkdir($img_path, 0777, TRUE);
			}
			if (file_put_contents($img_path . 'aadhar_' . $id . '.png', $aadharimagebase64)) {
				$updCus = array('aadhar_ImgName' => 'aadhar_' . $id . '.png');
				$this->$model->updData($updCus, 'id_customer', $id, 'customer');
			}
		}
		$dl_img  = json_decode(rawurldecode($cus['dl_img']));
		if (sizeof($dl_img) > 0) {
			$_FILES['pan_img']    =  array();
			$dl = explode(";base64,", $dl_img[0]->src);
			$dlimagebase64  = base64_decode($dl[1]);
			$img_path = "assets/img/customer/" . $id . "/";
			if (!is_dir($img_path)) {
				mkdir($img_path, 0777, TRUE);
			}
			if (file_put_contents($img_path . 'dl_' . $id . '.png', $dlimagebase64)) {
				$updCus = array('dl_ImgName' => 'dl_' . $id . '.png');
				$this->$model->updData($updCus, 'id_customer', $id, 'customer');
			}
		}
		$pp_img  = json_decode(rawurldecode($cus['pp_img']));
		if (sizeof($pp_img) > 0) {
			$_FILES['pan_img']    =  array();
			$pp = explode(";base64,", $pp_img[0]->src);
			$ppimagebase64  = base64_decode($pp[1]);
			$img_path = "assets/img/customer/" . $id . "/";
			if (!is_dir($img_path)) {
				mkdir($img_path, 0777, TRUE);
			}
			if (file_put_contents($img_path . 'pp_' . $id . '.png', $ppimagebase64)) {
				$updCus = array('pp_ImgName' => 'pp_' . $id . '.png');
				$this->$model->updData($updCus, 'id_customer', $id, 'customer');
			}
		}
		//webcam kyc document upload starts
		return TRUE;
	}
	public function retrive_KycImages($cus)
	{
		//retrieve webcam kyc document file starts... added_on : 7/6/2023 #AB...	   
		$pan_img = self::CUS_IMG_PATH . $cus['id_customer'] . '/' . $cus['pan_ImgName'];
		$aadhar_img = self::CUS_IMG_PATH . $cus['id_customer'] . '/' . $cus['aadhar_ImgName'];
		$dl_img = self::CUS_IMG_PATH . $cus['id_customer'] . '/' . $cus['dl_ImgName'];
		$pp_img = self::CUS_IMG_PATH . $cus['id_customer'] . '/' . $cus['pp_ImgName'];
		if (file_exists($pan_img)) {
			$data['customer']['pan_img_path']		= $pan_img;
		}
		if (file_exists($aadhar_img)) {
			$data['customer']['aadhar_img_path']		= $aadhar_img;
		}
		if (file_exists($dl_img)) {
			$data['customer']['dl_img_path']		= $dl_img;
		}
		if (file_exists($pp_img)) {
			$data['customer']['pp_img_path']		= $pp_img;
		}
		// print_r($data);exit;
		return $data;
		//retrieve webcam kyc document file ends
	}
	public function get_kyc_images($cus, $id)
	{
		// echo "<pre>"; print_r($cus);exit;
		$kycData = [];
		//image pan front path for kyc master starts
		$pan_img_front  = json_decode(rawurldecode($cus['pan_img_front']));
		$kyc_panpath = "";
		if (sizeof($pan_img_front) > 0) {
			//	$_FILES['pan_images_front']    =  array();
			$pan = explode(";base64,", $pan_img_front->src);
			$panimagebase64  = base64_decode($pan[1]);
			$kyc_path = self::KYC_PAN_PATH . "" . $id;
			if (!is_dir($kyc_path)) {
				mkdir($kyc_path, 0777, TRUE);
			}
			$kycpath = self::KYC_PAN_PATH . "" . $id . "/pan_front.png";
			file_put_contents($kycpath, $panimagebase64);
			$kycData['pan_front_img_url'] = isset($kycpath) ? base_url() . "" . $kycpath : NULL;
			//	 echo "<pre>"; print_r($kycData);exit;
		}
		//image pan front path for kyc master ends
		//image pan back path for kyc master starts
		$pan_img_back  = json_decode(rawurldecode($cus['pan_img_back']));
		$kyc_panpath = "";
		if (sizeof($pan_img_back) > 0) {
			//	$_FILES['pan_images_back']    =  array();
			$pan = explode(";base64,", $pan_img_back->src);
			$panimagebase64  = base64_decode($pan[1]);
			$kyc_path = self::KYC_PAN_PATH . "" . $id;
			if (!is_dir($kyc_path)) {
				mkdir($kyc_path, 0777, TRUE);
			}
			$kycpath = self::KYC_PAN_PATH . "" . $id . "/pan_back.png";
			file_put_contents($kycpath, $panimagebase64);
			$kycData['pan_back_img_url'] = isset($kycpath) ? base_url() . "" . $kycpath : NULL;
		}
		//image pan back path for kyc master ends
		$aadhar_img_front  = json_decode(rawurldecode($cus['aadhar_img_front']));
		//print_r($aadhar_img);exit;
		$kyc_aadharpath = "";
		if (sizeof($aadhar_img_front) > 0) {
			//image path for aadhar kyc master starts
			//	$_FILES['pan_img']    =  array();
			$aadhar = explode(";base64,", $aadhar_img_front->src);
			$aadharimagebase64  = base64_decode($aadhar[1]);
			$file_path = self::KYC_AADHAR_PATH . "" . $id;
			if (!is_dir($file_path)) {
				mkdir($file_path, 0777, TRUE);
			}
			$kyc_aadharpath = self::KYC_AADHAR_PATH . "" . $id . "/aadhar_front.png";
			//print_r($path);exit;
			file_put_contents($kyc_aadharpath, $aadharimagebase64);
			$kycData['aadhar_img_front_url'] = isset($kyc_aadharpath) ? base_url() . "" . $kyc_aadharpath : NULL;
			//image path for aadhar kyc master ends
		}
		$aadhar_img_back  = json_decode(rawurldecode($cus['aadhar_img_back']));
		//print_r($aadhar_img);exit;
		$kyc_aadharpath = "";
		if (sizeof($aadhar_img_back) > 0) {
			//image path for aadhar kyc master starts
			//	$_FILES['pan_img']    =  array();
			$aadhar = explode(";base64,", $aadhar_img_back->src);
			$aadharimagebase64  = base64_decode($aadhar[1]);
			$file_path = self::KYC_AADHAR_PATH . "" . $id;
			if (!is_dir($file_path)) {
				mkdir($file_path, 0777, TRUE);
			}
			$kyc_aadharpath = self::KYC_AADHAR_PATH . "" . $id . "/aadhar_back.png";
			//print_r($path);exit;
			file_put_contents($kyc_aadharpath, $aadharimagebase64);
			$kycData['aadhar_img_back_url'] = isset($kyc_aadharpath) ? base_url() . "" . $kyc_aadharpath : NULL;
			//image path for aadhar kyc master ends
		}
		if ($_FILES['pdf_file_input']['name']) {
			$fileName = $_FILES["pdf_file_input"]["tmp_name"];
			$files = $_FILES["pdf_file_input"]["name"];
			$fileExtension = pathinfo($files, PATHINFO_EXTENSION); // Get the file extension
			//	print_r($fileExtension);exit;
			$file_path = self::KYC_PAN_PATH . "" . $id;
			if (!is_dir($file_path)) {
				mkdir($file_path, 0777, TRUE);
			}
			$kyc_panpath = self::KYC_PAN_PATH . "" . $id . "/pan." . $fileExtension;
			// file_put_contents($kyc_panpath,$fileName);
			$content = file_get_contents($fileName); // Get content from the uploaded file
			file_put_contents($kyc_panpath, $content);
			$kycData['pan_doc_url'] = isset($kyc_panpath) ? base_url() . "" . $kyc_panpath : NULL;
			//	print_r($kycData['pan_doc_url']);exit;
		}
		if ($_FILES['pdf_aadhar_file_input']['name']) {
			$fileName = $_FILES["pdf_aadhar_file_input"]["tmp_name"];
			$files = $_FILES["pdf_aadhar_file_input"]["name"];
			$fileExtension = pathinfo($files, PATHINFO_EXTENSION); // Get the file extension
			$file_path = self::KYC_AADHAR_PATH . "" . $id;
			if (!is_dir($file_path)) {
				mkdir($file_path, 0777, TRUE);
			}
			$kyc_aadharpath = self::KYC_AADHAR_PATH . "" . $id . "/aadhar." . $fileExtension;
			$content = file_get_contents($fileName); // Get content from the uploaded file
			file_put_contents($kyc_aadharpath, $content);
			$kycData['aadhar_doc_url'] = isset($kyc_aadharpath) ? base_url() . "" . $kyc_aadharpath : NULL;
			//	print_r($kycData);exit;
		}
		// print_r($_FILES['pan_img_front']);exit;
		// print_r($_FILES);exit;
		if ($_FILES['pan_img_front']['name']) {
			$fileName = $_FILES["pan_img_front"]["tmp_name"];
			$files = $_FILES["pan_img_front"]["name"];
			// $fileExtension = pathinfo($files, PATHINFO_EXTENSION); // Get the file extension
			$file_path = self::KYC_PAN_PATH . "" . $id;
			if (!is_dir($file_path)) {
				mkdir($file_path, 0777, TRUE);
			}
			$kycpath = self::KYC_PAN_PATH . "" . $id . "/pan_front.png";
			$content = file_get_contents($fileName);
			file_put_contents($kycpath, $content);
			$kycData['pan_front_img_url'] = isset($kycpath) ? base_url() . "" . $kycpath : NULL;
			//	print_r($kycData);exit;
		}
		if ($_FILES['pan_img_back']['name']) {
			$fileName = $_FILES["pan_img_back"]["tmp_name"];
			$files = $_FILES["pan_img_back"]["name"];
			// $fileExtension = pathinfo($files, PATHINFO_EXTENSION); // Get the file extension
			$file_path = self::KYC_PAN_PATH . "" . $id;
			if (!is_dir($file_path)) {
				mkdir($file_path, 0777, TRUE);
			}
			$kycpath = self::KYC_PAN_PATH . "" . $id . "/pan_back.png";
			$content = file_get_contents($fileName);
			file_put_contents($kycpath, $content);
			$kycData['pan_back_img_url'] = isset($kycpath) ? base_url() . "" . $kycpath : NULL;
			//	print_r($kycData);exit;
		}
		if ($_FILES['aadhar_img_front']['name']) {
			$fileName = $_FILES["aadhar_img_front"]["tmp_name"];
			$files = $_FILES["aadhar_img_front"]["name"];
			// $fileExtension = pathinfo($files, PATHINFO_EXTENSION); // Get the file extension
			$file_path = self::KYC_AADHAR_PATH . "" . $id;
			if (!is_dir($file_path)) {
				mkdir($file_path, 0777, TRUE);
			}
			$kycpath = self::KYC_AADHAR_PATH . "" . $id . "/aadhar_front.png";
			$content = file_get_contents($fileName);
			file_put_contents($kycpath, $content);
			$kycData['aadhar_img_front_url'] = isset($kycpath) ? base_url() . "" . $kycpath : NULL;
			//	print_r($kycData);exit;
		}
		if ($_FILES['aadhar_img_back']['name']) {
			$fileName = $_FILES["aadhar_img_back"]["tmp_name"];
			$files = $_FILES["aadhar_img_back"]["name"];
			// $fileExtension = pathinfo($files, PATHINFO_EXTENSION); // Get the file extension
			$file_path = self::KYC_AADHAR_PATH . "" . $id;
			if (!is_dir($file_path)) {
				mkdir($file_path, 0777, TRUE);
			}
			$kycpath = self::KYC_AADHAR_PATH . "" . $id . "/aadhar_back.png";
			$content = file_get_contents($fileName);
			file_put_contents($kycpath, $content);
			$kycData['aadhar_img_back_url'] = isset($kycpath) ? base_url() . "" . $kycpath : NULL;
			//	print_r($kycData);exit;
		}
		return $kycData;
	}
	function getkycdata_byid()
	{
		$cus_id = $_POST['cus_id'];
		$model = self::CUS_MODEL;
		$data = $this->$model->get_kyc_byid($cus_id);
		echo json_encode($data);
	}
	//Zone Master
	public function zone($type = "", $id = "", $status = "")
	{
		$model = self::CUS_MODEL;
		$set_model   = self::SET_MODEL;
		switch ($type) {
			case "add":
				$insertData = array(
					'id_branch' => $this->input->post('id_branch'),
					'name' 	   => strtoupper($this->input->post('name')),
					'date_add' => date('y-m-d H:i:s'),
					'created_by' => $this->session->userdata('uid'),
				);
				$this->db->trans_begin();
				$this->$model->insertData($insertData, 'village_zone');
				if ($this->db->trans_status() === TRUE) {
					$this->db->trans_commit();
					$this->session->set_flashdata('chit_alert', array('message' => 'Zone Added Successfully.', 'class' => 'success', 'title' => 'Zone'));
					$result = array('status' => true, 'message' => 'Zone added successfully!..', 'class' => 'success', 'title' => 'Zone: ');
				} else {
					$this->db->trans_rollback();
					$this->session->set_flashdata('chit_alert', array('message' => 'Unable to Proceed.', 'class' => 'danger', 'title' => 'Zone'));
					$result = array('status' => false, 'message' => 'Unable to proceed the requested process..', 'class' => 'danger', 'title' => 'Zone : ');
				}
				echo json_encode($result);
				//redirect('admin_ret_catalog/metal_type/list');	
				break;
			case "edit":
				$data = $this->$model->getVillageZone($id);
				echo json_encode($data);
				break;
			case 'delete':
				$this->db->trans_begin();
				$this->$model->deleteData('id_zone', $id, 'village_zone');
				if ($this->db->trans_status() === TRUE) {
					$this->db->trans_commit();
					$this->session->set_flashdata('chit_alert', array('message' => 'Zone deleted successfully', 'class' => 'success', 'title' => 'Delete Zone'));
				} else {
					$this->db->trans_rollback();
					$this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request', 'class' => 'danger', 'title' => 'Delete Zone'));
				}
				redirect('customer/zone/list');
				break;
			case "update":
				$updData = array(
					'id_branch' => $this->input->post('ed_id_branch'),
					'name' 	   => strtoupper($this->input->post('ed_name')),
					'date_upd' => date('y-m-d H:i:s'),
					'updated_by' => $this->session->userdata('uid'),
				);
				$this->db->trans_begin();
				$this->$model->updateData($updData, 'id_zone', $this->input->post('id_zone'), 'village_zone');
				if ($this->db->trans_status() === TRUE) {
					$this->db->trans_commit();
					$this->session->set_flashdata('chit_alert', array('message' => 'Rate successfully', 'class' => 'success', 'title' => 'Zone'));
					$result = array('status' => true, 'message' => 'Zone Updated successfully!..', 'class' => 'success', 'title' => 'Zone: ');
				} else {
					$this->db->trans_rollback();
					$this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed the requested process', 'class' => 'danger', 'title' => 'Zone'));
					$result = array('status' => false, 'message' => 'Unable to proceed the requested process..', 'class' => 'danger', 'title' => 'Zone : ');
				}
				echo json_encode($result);
				break;
			case 'list':
				$data['main_content'] = "master/village/zone/list";
				$data['access']= $this->$set_model->get_access('customer/zone/list');
				$this->load->view('layout/template', $data);
				break;
			default:
				$list = $this->$model->get_ajaxzone_list();
				$access = $this->$set_model->get_access('customer/zone/list');
				$data = array(
					'list' => $list,
					'access' => $access
				);
				echo json_encode($data);
		}
	}
	//Zone Master
	function wallet_account_cus()
	{
		$model = self::CUS_MODEL;
		$customer = $this->$model->get_cus();
		if (sizeof($customer) > 0) {
			foreach ($customer as $cus) {
				$wallet = $this->wallet_account_create($cus['id_customer'], $cus['mobile']);
				echo '<br/>';
				echo 'Mobile- ' . $cus['mobile'] . '| Created sucessfully';
			}
		} else {
			echo '<br/>';
			echo 'Mobile- ' . $cus['mobile'] . '| Unable to proceed';
		}
	}
}

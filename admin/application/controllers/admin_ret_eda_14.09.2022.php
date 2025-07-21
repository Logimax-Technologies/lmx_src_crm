<?php

if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_ret_eda extends CI_Controller

{

	function __construct()

	{

		parent::__construct();

		ini_set('date.timezone', 'Asia/Calcutta');

		$this->load->model('ret_eda_model');

		$this->load->model('admin_settings_model');

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


	/**

	* EDA Functions Starts

	*/

	public function eda($type="",$id="") {

		$model = "ret_eda_model";

		switch($type) {

			case 'list':

					$data['main_content'] = "estimation/eda/list" ;

					$this->load->view('layout/template', $data);

					break;

			case "update":

					$esti_id = $_POST['esti_id'];

					$type = $_POST['type'];

					if($type == 1) {

						$estimate_final_amt = $_POST['estimate_final_amt'];

						$data	=	array(

							'estimate_final_amt' => $estimate_final_amt,

							'is_eda_approved' => 1

						);
					
						$this->db->trans_begin();
						
						$update_status = $this->$model->updateData($data,'estimation_id',$esti_id, 'ret_estimation');
						
						$estdetails = $this->$model->getEstDetails($esti_id);
						
						foreach($estdetails as $est){
							if(!empty($est['tag_id'])){
								$this->$model->updateData(array('tag_status'=> 10),'tag_id',$est['tag_id'], 'ret_taging');
							
								//Update Tag Log status

								$tag_log=array(

									'tag_id'	  => !empty($est['tag_id']) ? $est['tag_id'] : NULL,

									'date'		  => date("Y-m-d H:i:s"),

									'status'	  => 10,

									'from_branch' => $est['id_branch'],

									'to_branch'	  =>NULL,

									'created_on'  =>date("Y-m-d H:i:s"),

									'created_by'  =>$this->session->userdata('uid'),

									);

									$this->$model->insertData($tag_log,'ret_taging_status_log');
									
							}
							
						}
						
						if($this->db->trans_status()===TRUE)

								{

									$return_data['status']=true;

									$this->db->trans_commit();

									

									$log_data = array(

									'id_log'        => $this->session->userdata('id_log'),

									'event_date'    => date("Y-m-d H:i:s"),

									'module'        => 'EDA Estimation',

									'operation'     => 'Approval',

									'record'        =>  $esti_id,  

									'remark'        => 'Record Approved successfully'

									);

									$this->log_model->log_detail('insert','',$log_data);


					

						} else {

							$return_data['status'] = false;

						}

					} else if($type == 2) {

						$data	=	array(

							'is_eda_approved' => 2

						);
                    
						$update_status = $this->$model->updateData($data,'estimation_id',$esti_id, 'ret_estimation');

						if($update_status) {

							$return_data['status'] = true;

						} else {

							$return_data['status'] = false;

						}

					}

					echo json_encode($return_data);

					break;

			default: 

					$id_branch  = $this->input->post('id_branch');

					$list = $this->$model->ajax_getEdaList($id_branch);	 

					$access = $this->admin_settings_model->get_access('admin_ret_eda/eda/list');

					$data = array(

										'list'  => $list,

										'access'=> $access

									);  

					echo json_encode($data);

		}

	}

}	

?>
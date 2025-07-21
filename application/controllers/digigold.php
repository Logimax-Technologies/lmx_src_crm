<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');  
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');
require(APPPATH.'libraries/REST_Controller.php');

class Digigold extends REST_Controller
{
    function __construct()
	{
	    parent::__construct();
	    ini_set('date.timezone', 'Asia/Calcutta');
		$this->response->format = 'json';
		
		$this->log_dir = 'log/'.date("d-m-Y");
    	if (!is_dir($this->log_dir)) {
            mkdir($this->log_dir, 0777, TRUE); 
        }
        
		$this->load->model('digigold_modal');
		
	}	
	
	function get_values()
    {
		return (array)json_decode(file_get_contents('php://input'));
	}
	
	/**
	* To get digi gold savings scheme details and account data customer wise
	* One customer can have only one active digi gold account. 
	* If customer has active digi account; allow for making payments
	* Else allow for new joining
	*/
	function digidata_post(){
        $data = $this->get_values();
		$chit = $this->digigold_modal->getCusDigiData(array('mobile' => $data['mobile'], 'id_customer' => $data['id_customer']));
		$this->response($chit,200);	
    }
    
    function set_digiTarget_post(){
        $data = $this->get_values();
        
        $updData = array("dg_target_value_wgt" => $data['dg_target_value_wgt']);
		$set_target = $this->digigold_modal->updData($updData,'id_scheme_account',$data['id_scheme_account'],'scheme_account');
		if($set_target > 0){
		    $res = array('status' => TRUE, 'msg' => 'Target set successfully...');
		}else{
		    $res = array('status' => FALSE, 'msg' => 'Unable to proceed your request...');   
		}
        $this->response($res,200);	
    }
    
}
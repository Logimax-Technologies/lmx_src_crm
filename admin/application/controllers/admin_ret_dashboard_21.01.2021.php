<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_ret_dashboard extends CI_Controller {
	const VIEW_FOLDER = 'ret_dashboard/';
	const RET_DAS_MODEL = 'ret_dashboard_model';
	function __construct()
	{
		parent::__construct();
		ini_set('date.timezone', 'Asia/Calcutta');
		$this->load->model(self::RET_DAS_MODEL);
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
	
	function index(){
		
	}

	function get_retail_dashboard_details()
	{
		$model=	self::RET_DAS_MODEL;
		$from_date	= $this->input->post('from_date');
		$to_date	= $this->input->post('to_date');
		$data['estimation'] =$this->$model->get_estimation($from_date,$to_date);
		$data['billing'] =$this->$model->get_billing($from_date,$to_date);          
		echo json_encode($data);

	}
	function get_order_data()
	{
		$model=	self::RET_DAS_MODEL;
		$from_date = $this->input->post('from_date');
		$to_date   = $this->input->post('to_date');
		$data['tot_catalog'] =$this->$model->total_order('tot_catalog'); 
		$data['tot_custom'] =$this->$model->total_order('tot_custom'); 
		$data['tot_repair'] =$this->$model->total_order('tot_repair'); 
		$branch = $this->$model->allBranches(); 
		foreach($branch as $br)
		{
			$order_data = $this->$model->get_order_data($br['id_branch'],$from_date,$to_date);		
			$order[] = array(
							'catalog' 	            => $order_data['catalog']['catalog'],
							'custom'		        => $order_data['cus']['custom'],
							'repair'		        => $order_data['repair']['repair'],
							'id_branch'             => $br['id_branch'],
							'branch_name'           => $br['branch_name']
							);  
		}
		$data['order'] = $order;
		echo json_encode($data);	 
	
	}
	
	function get_estimation()
	{
	    $data['main_content'] = self::VIEW_FOLDER.'reports/live_estimation';
        $this->load->view('layout/template', $data);
    }

    function get_estimation_details()
    {
    	$model=	self::RET_DAS_MODEL;
		$from_date	= $this->input->post('from_date');
		$to_date	= $this->input->post('to_date');
		$id_branch   =$this->input->post('id_branch');
		$type   	=$this->input->post('type');
		$data=array();
		$estimation=$this->$model->get_estimation_details($from_date,$to_date,$id_branch);
		foreach($estimation as $est)
		{
				$data[]=array(
							'estimation_id'		=>$est['estimation_id'],
							'discount'	   		=>number_format(($type!=2 ? $est['discount']:0),2),
							'gift_voucher_amt'	=>$est['gift_voucher_amt'],
							'cus_id'			=>$est['cus_id'],
							'item_type'			=>$est['item_type'],
							'cus_name'			=>$est['cus_name'],
							'chit_amt'			=>number_format(($type==0 || $type==2 ? $est['chit_amt']:0),2),
							'item_cost'			=>number_format(($type==0 || $type==1 ? $est['item_cost'] :0),2),
							'pur_wt'			=>number_format(($type==0 || $type==1 ? $est['pur_wt'] :0),2),
							'sales_amt'			=>number_format(($type==0 || $type==2 ? $est['sales_amt'] :0),2),
							'sales_wt'			=>number_format(($type==0 || $type==2 ? $est['sales_wt'] :0),2),
							'total_cost'		=>number_format(($type==0 ? $est['total_cost'] :($type==1 ?$est['item_cost']-$est['discount']:$est['sales_amt']+$est['chit_amt'])),2),
							);
		}
		echo json_encode($data);
    }
    
    function ajax_lot_data()
	{
		$model=	self::RET_DAS_MODEL;
		$from_date = $this->input->post('from_date');
		$to_date   = $this->input->post('to_date');
		$data['gross_wt']=$this->$model->get_lot_data('get_total_gross_wt',$from_date,$to_date);
		$data['net_wt']=$this->$model->get_lot_data('get_total_net_wt',$from_date,$to_date);
		
		$branch = $this->$model->allBranches(); 
		foreach($branch as $br)
		{
			$lot_data = $this->$model->lot_branchwise_data($br['id_branch'],$from_date,$to_date);		
			$lot[] = array(
							'lots' 	            => $lot_data['count']['lots'],
							'net_weight' 	    => $lot_data['count']['net_weight'],
							'grs_wt' 	        => $lot_data['count']['grs_wt'],
							'id_branch'         => $br['id_branch'],
							'branch_name'       => $br['branch_name']
							);  
		}
		$data['lot'] = $lot;
		echo json_encode($data);
	}
	function ajax_tag_data()
	{
		$model=	self::RET_DAS_MODEL;
		$from_date = $this->input->post('from_date');
		$to_date   = $this->input->post('to_date');
		$data['grs_wt']=$this->$model->get_tag_data('get_total_gross_wt',$from_date,$to_date);
		$data['nt_wt']=$this->$model->get_tag_data('get_total_net_wt',$from_date,$to_date);
		$branch = $this->$model->allBranches(); 
		foreach($branch as $br)
		{
			$tag_data = $this->$model->tag_branchwise_data($br['id_branch'],$from_date,$to_date);		
			$tag[] = array(
							'tags' 	            => $tag_data['total']['tags'],
							'nt' 	            => $tag_data['total']['nt'],
							'gt' 	            => $tag_data['total']['gt'],
							'id_branch'         => $br['id_branch'],
							'branch_name'       => $br['branch_name']
							);  
		}
		$data['tag'] = $tag;
		echo json_encode($data);
	}
	
	function get_sales_details()
	{
		$model=	self::RET_DAS_MODEL;
		$from_date	= $this->input->post('from_date');
		$to_date	= $this->input->post('to_date');
		$branch = $this->$model->allBranches(); 
		foreach($branch as $br)
		{
			$items=$this->$model->get_saleBill($br['id_branch'],$from_date,$to_date);   
			 	
			$bill[]=array(
							'id_branch'   => $br['id_branch'],
							'branch_name' => $br['branch_name'],
							 'billing'	  =>$items['billing']
						);
		}
		$data['sales_details']=$bill;
		echo json_encode($data);

	}

	function get_MetalBill_details()
	{
		$model=	self::RET_DAS_MODEL;
		$branch = $this->$model->allBranches();
		$data['sales_details']=$this->$model->metal_bill_details();
		echo json_encode($data);
			
	}

}
?>
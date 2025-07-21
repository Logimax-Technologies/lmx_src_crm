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
    
    
    function get_EstimationStatus()
	{
		$model=	self::RET_DAS_MODEL;
		$from_date	= $this->input->post('from_date');
		$to_date	= $this->input->post('to_date');
		$id_branch	= $this->input->post('id_branch');
		$data['dash_estmation'] = $this->$model->get_dashboard_estimation($from_date, $to_date,$id_branch);
		//print_r($this->db->last_query());exit;
		echo json_encode($data);
	}
	
	function get_BillingStatus()
	{
		$model=	self::RET_DAS_MODEL;
		$from_date	= $this->input->post('from_date');
		$to_date	= $this->input->post('to_date');
		$id_branch	= $this->input->post('id_branch');
		$data['dash_billing'] = $this->$model->get_dashboard_billings($from_date, $to_date,$id_branch);
		$data['silver_wt'] = $this->$model->get_dashboard_billings_sl_wt($from_date, $to_date,$id_branch);
		$data['mrp'] = $this->$model->get_dashboard_billings_mrp($from_date, $to_date,$id_branch);
		echo json_encode($data);
	}
	
	function get_VitrualTagStatus()
	{
		$model=	self::RET_DAS_MODEL;
		$from_date	= $this->input->post('from_date');
		$to_date	= $this->input->post('to_date');
		$id_branch	= $this->input->post('id_branch');
		$data['dash_virturaltag_details'] = $this->$model->get_dashboard_virturaltag_details($from_date, $to_date);
		echo json_encode($data);
	}
	
	function get_old_metal_purchase()
	{
		$model=	self::RET_DAS_MODEL;
		$from_date	= $this->input->post('from_date');
		$to_date	= $this->input->post('to_date');
		$id_branch	= $this->input->post('id_branch');
	    $data['dash_old_metal_purchase'] = $this->$model->get_dashboard_old_metal_purchase($from_date, $to_date,$id_branch);
		echo json_encode($data);
	}
	
	function get_CreditSalesDetails()
	{
		$model=	self::RET_DAS_MODEL;
		$from_date	= $this->input->post('from_date');
		$to_date	= $this->input->post('to_date');
		$id_branch	= $this->input->post('id_branch');
	    $data['dash_credeit_sales'] = $this->$model->get_dashboard_credit_sales($from_date, $to_date,$id_branch);
		echo json_encode($data);
	}
	
	
	function get_GiftVoucherDetails()
	{
		$model=	self::RET_DAS_MODEL;
		$from_date	= $this->input->post('from_date');
		$to_date	= $this->input->post('to_date');
		$id_branch	= $this->input->post('id_branch');
	    $data['dash_gift_vouchers'] = $this->$model->get_dashboard_gift_vouchers($from_date, $to_date,$id_branch);
		echo json_encode($data);
	}
	
	function get_BillClassficationDetails()
	{
		$model=	self::RET_DAS_MODEL;
		$from_date	= $this->input->post('from_date');
		$to_date	= $this->input->post('to_date');
		$id_branch	= $this->input->post('id_branch');
	    $data['dash_bills_clasfication'] = $this->$model->get_dashboard_bills_clasfications($from_date, $to_date,$id_branch);
		echo json_encode($data);
	}
	
	function get_BranchTransferDetails()
	{
		$model=	self::RET_DAS_MODEL;
		$from_date	= $this->input->post('from_date');
		$to_date	= $this->input->post('to_date');
		$id_branch	= $this->input->post('id_branch');
	    $data['dash_approval_pendings'] = $this->$model->get_dashboard_approval_pendings($from_date, $to_date,$id_branch);
		echo json_encode($data);
	}
	
    function get_lot_tag_details()
	{
		$model=	self::RET_DAS_MODEL;
		$from_date	= $this->input->post('from_date');
		$to_date	= $this->input->post('to_date');
		$id_branch	= $this->input->post('id_branch');
	    $data['dash_lot_tag_details'] = $this->$model->get_dashboard_lot_tag_details($from_date, $to_date);
		echo json_encode($data);
	}
	
	function get_OrderDetails()
	{
		$model=	self::RET_DAS_MODEL;
		$from_date	= $this->input->post('from_date');
		$to_date	= $this->input->post('to_date');
		$id_branch	= $this->input->post('id_branch');
	    $data['dash_orders_details'] = $this->$model->get_dashboard_orders_details($from_date, $to_date,$id_branch);
		echo json_encode($data);
	}
	
	
	function get_StockDetails()
	{
		$model=	self::RET_DAS_MODEL;
		$from_date	= $this->input->post('from_date');
		$to_date	= $this->input->post('to_date');
		$id_branch	= $this->input->post('id_branch');
		$id_metal	= $this->input->post('id_metal');
	    $data['dash_stock_details']=$this->$model->AvailableStockDetails($from_date,$to_date,$id_branch,$id_metal);
		echo json_encode($data);
	}
	
	function get_silver_StockDetails()
    {
        $model= self::RET_DAS_MODEL;
        $from_date  = $this->input->post('from_date');
        $to_date    = $this->input->post('to_date');
        $id_branch  = $this->input->post('id_branch');
        $data['dash_stock_details']=$this->$model->Available_SilverStockDetails($from_date,$to_date,$id_branch);
        echo json_encode($data);
    }
	
	function get_ReorderDetails()
	{
		$model=	self::RET_DAS_MODEL;
		$from_date	= $this->input->post('from_date');
		$to_date	= $this->input->post('to_date');
		$id_branch	= $this->input->post('id_branch');
	    $data['dash_reorder_items']=$this->$model->getReorderItems($id_branch);
		echo json_encode($data);
	}
	
	function get_KarigarOrderDetails()
	{
		$model=	self::RET_DAS_MODEL;
	    $data['karigar_orders']['today_delivered'] = $this->$model->karigar_orders('T');
		$data['karigar_orders']['today_pending'] = $this->$model->karigar_orders('TODDY_PENDING');
      	$data['karigar_orders']['tm_delivery'] = $this->$model->karigar_orders('TM');
      	$data['karigar_orders']['over_due_orders'] = $this->$model->karigar_orders('OVER_DUE');
      	$data['karigar_orders']['work_in_progress'] = $this->$model->karigar_orders('WIP');
		echo json_encode($data);
	}
	
/*	function get_customerOrderDetails()
	{
		$model=	self::RET_DAS_MODEL;
	    $data['customer_orders']['today_delivered'] = $this->$model->customer_orders('T');
      	$data['customer_orders']['today_pending'] = $this->$model->customer_orders('T');
      	$data['customer_orders']['tm_ready_for_delivery'] = $this->$model->customer_orders('TM');
      	$data['customer_orders']['tm_pending'] = $this->$model->customer_orders('TMRW_PENDING');
      	$data['customer_orders']['over_due_orders'] = $this->$model->customer_orders('OVER_DUE');
      	$data['customer_orders']['work_in_progress'] = $this->$model->customer_orders('WIP');
		echo json_encode($data);
	}*/
	
	function get_customerOrderDetails()
    {
        $model= self::RET_DAS_MODEL;
        $id_branch  = $this->input->post('id_branch');
        $data['customer_order']=$this->$model->customer_orders($_POST['from_date'],$_POST['to_date'],$id_branch);
        echo json_encode($data);
    }
	
	function get_MetalStockDetails()
	{
		$model=	self::RET_DAS_MODEL;
	    $data['stock_metal_details']=$this->$model->StockMetalWise($id_branch);
		echo json_encode($data);
	}
	
	function get_CustomerDetails()
	{
		$model=	self::RET_DAS_MODEL;
		$from_date	= $this->input->post('from_date');
		$to_date	= $this->input->post('to_date');
		$id_branch	= $this->input->post('id_branch');
	    $data['dash_customer_details'] = $this->$model->get_dashboard_customer_details($from_date, $to_date);
		echo json_encode($data);
	}
	
	function get_RecentBillDetails()
	{
		$model=	self::RET_DAS_MODEL;
		$from_date	= $this->input->post('from_date');
		$to_date	= $this->input->post('to_date');
		$id_branch	= $this->input->post('id_branch');
	    $data['dash_bills_details'] = $this->$model->get_dashboard_bills_details($from_date, $to_date,$id_branch);
		echo json_encode($data);
	}
	
	function get_cash_abstract_details()
	{
		$model=	self::RET_DAS_MODEL;
		$from_date	= $this->input->post('from_date');
		$to_date	= $this->input->post('to_date');
		$id_branch	= $this->input->post('id_branch');
	    $data['dash_cash_abstarct_details'] = $this->$model->get_dashboard_cash_abstarct_details($from_date, $to_date,$id_branch);
		echo json_encode($data);
	}
	
	
	function getEstimationDetails()
	{
		$model=	self::RET_DAS_MODEL;
		$from_date	= $this->input->post('from_date');
		$to_date	= $this->input->post('to_date');
		$id_branch	= $this->input->post('id_branch');
	    $data['dash_estimation_details'] = $this->$model->get_dashboard_estimation_details($from_date, $to_date,$id_branch);
		echo json_encode($data);
	}
	

		
	
	
	function get_retail_dashboard_details()
	{
		$model=	self::RET_DAS_MODEL;
		$from_date	= $this->input->post('from_date');
		$to_date	= $this->input->post('to_date');
		$data['dash_estmation'] = $this->$model->get_dashboard_estimation($from_date, $to_date);
		$data['dash_billing'] = $this->$model->get_dashboard_billings($from_date, $to_date);
		$data['dash_old_metal_purchase'] = $this->$model->get_dashboard_old_metal_purchase($from_date, $to_date);
		$data['dash_credeit_sales'] = $this->$model->get_dashboard_credit_sales($from_date, $to_date);
		$data['dash_gift_vouchers'] = $this->$model->get_dashboard_gift_vouchers($from_date, $to_date);
		$data['dash_bills_clasfication'] = $this->$model->get_dashboard_bills_clasfications($from_date, $to_date);
		$data['dash_approval_pendings'] = $this->$model->get_dashboard_approval_pendings($from_date, $to_date);
		$data['dash_lot_tag_details'] = $this->$model->get_dashboard_lot_tag_details($from_date, $to_date);
		$data['dash_orders_details'] = $this->$model->get_dashboard_orders_details($from_date, $to_date);
		$data['dash_customer_details'] = $this->$model->get_dashboard_customer_details($from_date, $to_date);
		$data['dash_bills_details'] = $this->$model->get_dashboard_bills_details($from_date, $to_date);
		$data['dash_estimation_details'] = $this->$model->get_dashboard_estimation_details($from_date, $to_date);
		$data['dash_cash_abstarct_details'] = $this->$model->get_dashboard_cash_abstarct_details($from_date, $to_date);
		$data['dash_virturaltag_details'] = $this->$model->get_dashboard_virturaltag_details($from_date, $to_date);
		
		$data['stock_metal_details']=$this->$model->StockMetalWise();
		$data['karigar_orders']['today_delivered'] = $this->$model->karigar_orders('T');
		$data['karigar_orders']['today_pending'] = $this->$model->karigar_orders('TODDY_PENDING');
      	$data['karigar_orders']['tm_delivery'] = $this->$model->karigar_orders('TM');
      	$data['karigar_orders']['over_due_orders'] = $this->$model->karigar_orders('OVER_DUE');
      	$data['karigar_orders']['work_in_progress'] = $this->$model->karigar_orders('WIP');
     
     	$data['customer_orders']['today_delivered'] = $this->$model->customer_orders('T');
      	$data['customer_orders']['today_pending'] = $this->$model->customer_orders('T');
      	$data['customer_orders']['tm_ready_for_delivery'] = $this->$model->customer_orders('TM');
      	$data['customer_orders']['tm_pending'] = $this->$model->customer_orders('TMRW_PENDING');
      	$data['customer_orders']['over_due_orders'] = $this->$model->customer_orders('OVER_DUE');
      	$data['customer_orders']['work_in_progress'] = $this->$model->customer_orders('WIP');
      	
      	$data['dash_stock_details']=$this->$model->AvailableStockDetails($from_date,$to_date);
      	
      	$data['dash_reorder_items']=$this->$model->getReorderItems();

		$data['estimation'] =$this->$model->get_estimation($from_date,$to_date);
		$data['billing'] =$this->$model->get_billing($from_date,$to_date);          
		echo json_encode($data);

	}


	function get_SaleBill_details()
	{
		$model      = self::RET_DAS_MODEL;
		$from_date	= $this->input->post('from_date');
		$to_date	= $this->input->post('to_date');
		$branch     = $this->$model->allBranches(); 
		foreach($branch as $br)
		{
			//Sales Report On Category Wise
			$items  = $this->$model->get_saleBillRecords($br['id_branch'],$from_date,$to_date);   
			$bill[] = array(
							'id_branch'                   => $br['id_branch'],
							'branch_name'                 => $br['branch_name'],
							'branchwise_sales_details'	  => $items
						);
			//Sales Report On Payment Wise
			$pay_items  = $this->$model->get_paymentBillRecords($br['id_branch'],$from_date,$to_date);   
			$payment_bill[] = array(
							'id_branch'                   => $br['id_branch'],
							'branch_name'                 => $br['branch_name'],
							'paymentwise_sales_details'	  => $pay_items
						);
    		// Sales Report On Metal Wise
			$metal_items  = $this->$model->get_metalBillRecords($br['id_branch'],$from_date,$to_date); 
			$metal_bill[] = array(
							'id_branch'                   => $br['id_branch'],
							'branch_name'                 => $br['branch_name'],
							'metalwise_sales_details'	  => $metal_items
						);		
		}
		$data['categorywise_records']   = $bill;
		$data['paymentwise_records']    = $payment_bill;
		$data['metalwise_records']      = $metal_bill;
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
	
	
	//credit history tab
	function get_CreditDetils()
	{
		$model=	self::RET_DAS_MODEL;
		$from_date	= $this->input->post('from_date');
		$to_date	= $this->input->post('to_date');
		$id_branch	= $this->input->post('id_branch');
		$data['bill_credit_details']=$this->$model->get_CreditDetils($from_date,$to_date,$id_branch);
		echo json_encode($data);
			
	}
	
	
	function get_PendingDueDetails()
	{
		$model=	self::RET_DAS_MODEL;
		$data['pending_details']=$this->$model->get_due_pending_details();
		echo json_encode($data);
			
	}
	
	//credit history tab
	
	//Stock and Branch Transfer
	
	function get_metal_stock_details()
	{
	    $model=	self::RET_DAS_MODEL;
		$from_date	= $this->input->post('from_date');
		$to_date	= $this->input->post('to_date');
		$id_branch	= $this->input->post('id_branch');
		$data['stock_details']=$this->$model->get_stock_category_details($from_date,$to_date,$id_branch);
		echo json_encode($data);
	}
	
	function get_branch_transfer_details()
	{
	    $model=	self::RET_DAS_MODEL;
		$from_date	= $this->input->post('from_date');
		$to_date	= $this->input->post('to_date');
		$id_branch	= $this->input->post('id_branch');
		$data['branch_transfer_details']=$this->$model->get_branch_transfer_details();
		echo json_encode($data);
	}
	
	//Stock and Branch Transfer
	
	
	//Area Wise Sales and New Customer
	function get_new_customer()
	{
	    $model=	self::RET_DAS_MODEL;
		$from_date	= $this->input->post('from_date');
		$to_date	= $this->input->post('to_date');
		$id_branch	= $this->input->post('id_branch');
		$data['new_customers']=$this->$model->get_new_customer($from_date,$to_date,$id_branch);
		echo json_encode($data);
	}
	//Area Wise Sales and New Customer
	
	
	//Order Management
	function get_customer_order_details()
	{
	    $model=	self::RET_DAS_MODEL;
		$from_date	= $this->input->post('from_date');
		$to_date	= $this->input->post('to_date');
		$id_branch	= $this->input->post('id_branch');
		$order_type=2;
		$data['cus_orders']=$this->$model->get_order_details($from_date,$to_date,$order_type,$id_branch);
		$data['cus_orders_details']=$this->$model->get_customer_order_details($from_date,$to_date);
		echo json_encode($data);
	}
	
	function get_karigar_order_details()
	{
	    $model=	self::RET_DAS_MODEL;
		$from_date	= $this->input->post('from_date');
		$to_date	= $this->input->post('to_date');
		$id_branch	= $this->input->post('id_branch');
		$order_type=1;
		$data['karigar_orders']=$this->$model->get_order_details($from_date,$to_date,$order_type,$id_branch);
		echo json_encode($data);
	}
	
	//Order Management

}
?>
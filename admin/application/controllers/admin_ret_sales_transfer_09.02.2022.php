<?php
if( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin_ret_sales_transfer extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		ini_set('date.timezone', 'Asia/Calcutta');
		$this->load->model('ret_sales_transfer_model');
		$this->load->model('admin_settings_model');
		$this->load->model('ret_billing_model');
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
	public function sales_transfer($type="",$id="") {
		$model = "ret_sales_transfer_model";
		switch($type) {
		        case 'list':
					$data['main_content'] = "sales_transfer/list" ;
					$this->load->view('layout/template', $data);
				break;
				case 'add': 
				    $data['fin_year'] = $this->$model->get_FinancialYear();
					$data['main_content'] = "sales_transfer/sales_trasnfer" ;
					$this->load->view('layout/template', $data);
				break; 
				case 'ret_add':
					$data['fin_year'] = $this->$model->get_FinancialYear();
					$data['main_content'] = "sales_transfer/sales_ret_transfer" ;
					$this->load->view('layout/template', $data);
				break;
				case 'sales_trans_tag':
    			    $data=$this->$model->get_sales_transfer_tag_details($_POST);
    			    echo json_encode($data);
    			break;
    			case 'sales_trans_approval_tag':
    			    $data=$this->$model->get_sales_trans_approval_tag($_POST);
    			    echo json_encode($data);
    			break;
    			case 'sales_return_trans_tag':
    			    $data=$this->$model->get_sales_return_trans_req_tag($_POST);
    			    echo json_encode($data);
    			break;	
    			case 'sales_return_trans_approval_tag':			    
    			    $data=$this->$model->get_sales_return_trans_approval_tag($_POST);			    
    			    echo json_encode($data);			
    			break;
			
		}
	}
	
	
	function create_sales_transfer()
	{
	    $model = "ret_billing_model";
	    $sales_trans_model = "ret_sales_transfer_model";
	    
	    $return_data            = [];
	    $from_branch            = $this->input->post('from_brn');
	    $to_brn                 = $this->input->post('to_brn');
	    $tot_bill_amount        = $this->input->post('tot_bill_amount');
	    $req_data               = $this->input->post('req_data');
	    $form_secret               = $this->input->post('form_secret');

	    $from_branch_details = $this->$sales_trans_model->get_branch_details($from_branch);
	    $to_branch_details = $this->$sales_trans_model->get_branch_details($to_brn);
	  
	        foreach($req_data as $items)
	        {
	            $tot_bill_amount = 0;
	            $category_details = $this->$sales_trans_model->get_category_details($items['cat_id']);
	            $bill_no     = $this->$model->code_number_generator($from_branch,$category_details['id_metal']);
                $dCData      = $this->admin_settings_model->getBranchDayClosingData($from_branch);
                $fin_year    = $this->$model->get_FinancialYear();
                $bill_date   = ($dCData['entry_date'] == date("Y-m-d") ? date("Y-m-d H:i:s") : $dCData['entry_date']);
                $ref_no      =$this->$model->generateRefNo($from_branch,'sales_ref_no',$category_details['id_metal']);
	    
	           $data = array(						
            		'bill_no'		    => $category_details['metal_code'].'-'.$bill_no,
            		'metal_type'		=> $category_details['id_metal'],
            		'sales_ref_no'		=> $ref_no,
            		'fin_year_code'		=> $fin_year['fin_year_code'],
            		'bill_type'		    => 13, //Sales Trasnfer
            		'tot_bill_amount'	=> 0,
            	    'bill_date'	        => $bill_date,
            		'created_time'	    => date("Y-m-d H:i:s"),
            		'created_by'        => $this->session->userdata('uid'),
            		'id_branch'         => $from_branch,
            		'goldrate_22ct' 	=> ($category_details['id_metal']==1 ? $items['rate_per_grm']:0),
            		'silverrate_1gm' 	=> ($category_details['id_metal']==2 ? $items['rate_per_grm']:0),
            		'remark'   	        => 'SALES TRASNFER',
            		'billing_for'       => 3,
            		'from_branch'       => $from_branch,
            		'to_branch'         => $to_brn,
            		'is_credit'         => 1,
            		'credit_status'     => 2,
            		'form_secret'	    => $form_secret,
            	); 
            	$this->db->trans_begin();
            	$insId = $this->$model->insertData($data,'ret_billing');
    	
	           $bill_details=$this->$sales_trans_model->get_category_tag_details($items['cat_id'],$from_branch);
	
	           foreach($bill_details as $billSale)
    	       {
    	           $total_igst = 0;
    	           $total_sgst = 0;
    	           $total_cgst = 0;
				   if($items['calc_type']==1){
					$taxable_amt = ($billSale['gross_wt'] * $items['rate_per_grm']);
				   }
				   else{
					$taxable_amt = ($billSale['piece'] * $items['rate_per_grm']);
				   }
				  
    	           $tax_amount  = (($taxable_amt*3)/100);
    	           $item_cost   = ($taxable_amt+$tax_amount);
    	           
    	           if($from_branch_details['id_country']==$to_branch_details['id_country'])
				   {
				       if($from_branch_details['id_state']==$to_branch_details['id_state'])
				       {
				           $total_sgst = number_format($tax_amount/2,2,".","");
				           $total_cgst = number_format($tax_amount/2,2,".","");
				       }else
				       {
				           $total_igst = $tax_amount;
				       }
				   }
				   else
				   {
				       $total_igst = $tax_amount;
				   }
    	           $tot_bill_amount+=$item_cost;
        	        $arrayBillSales = array(
        				'bill_id'       => $insId,
        				'bill_type' 	=> 2,
        			    'total_igst' 	=> $tax_amount, 
        				'product_id' 	=> $billSale['product_id'],
        				'design_id' 	=> $billSale['design_id'],
        				'tag_id'		=> $billSale['tag_id'],
        				'purity' 		=> $billSale['purity'], 
        				'piece' 		=> $billSale['piece'], 
        				'less_wt' 		=> 0, 
        				'net_wt' 		=> $billSale['net_wgt'], 
        				'gross_wt' 		=> $billSale['gross_wt'], 
        				'calculation_based_on' => $billSale['calculation_based_on'], 
        				'item_cost' 	=> $item_cost, 
        				'total_igst' 	=> $total_igst, 
        				'total_sgst' 	=> $total_sgst, 
        				'total_cgst' 	=> $total_cgst, 
        				'item_total_tax'=> $tax_amount, 
        				'rate_per_grm'  => $items['rate_per_grm'],
        			);
        			
        			$tagInsert = $this->$model->insertData($arrayBillSales,'ret_bill_details');
        			//print_r($this->db->last_query());exit;
        			if($tagInsert)
        			{
        			    $this->$model->updateData(array('tag_status'=>4),'tag_id',$billSale['tag_id'], 'ret_taging');
        			    
        			    $tag_log=array(
                        'tag_id'	  =>$billSale['tag_id'],
                        'date'		  =>$bill_date,
                        'status'	  =>11,
                        'from_branch' =>$from_branch,
                        'to_branch'	  =>NULL,
                        'form_secret' => $form_secret,
                        'created_on'  =>date("Y-m-d H:i:s"),
                        'created_by'  =>$this->session->userdata('uid'),
                        );
                        $this->$model->insertData($tag_log,'ret_taging_status_log');
        			}
    	       }
    	       
    	       $this->$model->updateData(array('tot_bill_amount'=>$tot_bill_amount),'bill_id',$insId, 'ret_billing');
    	       
	        }
	   
	    if($this->db->trans_status() === TRUE)
	    {
	        $this->db->trans_commit();
	        $return_data=array('status'=>TRUE,'id'=>$insId,'message'=>'Sales Trasnfer Added Successfully..');
	    }
	    else
	    {
	        $this->db->trans_rollback();
	        $return_data=array('status'=>FALSE,'id'=>'','message'=>'Unable to proceed the requested process..');
	    }
	    echo json_encode($return_data);
	}
	
	
	function update_sales_transfer_request()
	{
		// print_r($_POST);exit;
	    $model = "ret_sales_transfer_model";
	    $from_branch            = $this->input->post('from_brn');
	    $to_brn                 = $this->input->post('to_brn');
	    $req_data               = $this->input->post('req_data');
	    $form_secret            = $this->input->post('form_secret');
    
        $dCData = $this->admin_settings_model->getAllBranchDCData();
	    $bill_date   = ($dCData['entry_date'] == date("Y-m-d") ? date("Y-m-d H:i:s") : $dCData['entry_date']);
	    
	    
	    $fb_entry_date = NULL;
	    $tb_entry_date = NULL;
	    foreach($dCData as $dayClose){
	        	
	        if($from_branch == $dayClose['id_branch']){ // From Branch
	            $fb_entry_date = $dayClose['entry_date'];
	        }
	        if($to_brn == $dayClose['id_branch']){ // To Branch
	            $tb_entry_date = $dayClose['entry_date'];
	        }
	    }
	    //print_r($tb_entry_date);exit;
	    if(strtotime($tb_entry_date) < strtotime($fb_entry_date))
		{ 
			$this->db->trans_rollback();			 	
			$result = array('message'=>'Check day closing in to branch. From branch : '.$fb_entry_date.' To Branch : '.$tb_entry_date,'class'=>'danger','title'=>'Branch Transfer Approval') ; 
			echo json_encode($result);
			exit;
		}
	    
	    foreach($req_data as $billSale)
	    {
	        $this->db->trans_begin();
	        $status=$this->$model->updateData(array("download_date"=>$tb_entry_date,"download_by"=>$this->session->userdata('uid')),'bill_id',$billSale['bill_id'],'ret_billing');
	        if($status)
	        {
	            $bill_details=$this->$model->getSalesTrans_Tag($billSale['bill_id']);
    	        foreach($bill_details as $bill)
    	        {
    	            $this->$model->updateData(array("tag_status"=>0,'current_branch'=>$to_brn),'tag_id',$bill['tag_id'],'ret_taging');
    	            //print_r($this->db->last_query());exit;
                
                    $tag_log = array(
                        "tag_id"		=> $bill['tag_id'],
                        "status"		=> 0,
                        "from_branch"	=> $from_branch,
                        "to_branch"		=> $to_brn,
                        "created_by"	=> $this->session->userdata('uid'),
                        "created_on"	=> date('Y-m-d H:i:s'),
                        "date"			=> $tb_entry_date,
                        'form_secret'   => $form_secret,
                    );
                    
                    $this->$model->insertData($tag_log,'ret_taging_status_log');
                    //print_r($this->db->last_query());exit;
    	        }
	        }
	    }
	    
	    if($this->db->trans_status() === TRUE)
	    {
	        $this->db->trans_commit();
	        $result=array('status'=>TRUE,'id'=>$insId,'message'=>'Sales Trasnfer Updated Successfully..');
	    }
	    else
	    {
	        $this->db->trans_rollback();
	        $result=array('status'=>FALSE,'id'=>'','message'=>'Unable to proceed the requested process..');
	    }
	    echo json_encode($result);
	}
	
	
	
	function create_sales_ret_transfer()
	{
		// print_r($_POST);exit;
	    $model = "ret_billing_model";
	    $sales_trans_model = "ret_sales_transfer_model";
	    $return_data            = [];
	    $from_branch            = $this->input->post('from_brn');
	    $to_brn                 = $this->input->post('to_brn');
	    $tot_bill_amount        = 0;
	    $req_data               = $this->input->post('req_data');
	    $sales_bill_no          = $this->input->post('bill_no');
	    $fin_year_code          = $this->input->post('fin_year_code');
	    $form_secret            = $this->input->post('form_secret');
	    
	     foreach($req_data as $items)
	        {
	            $category_details = $this->$sales_trans_model->get_category_details($items['cat_id']);
	            $bill_no     = $this->$model->code_number_generator($from_branch,$category_details['id_metal']);
        	    $dCData      = $this->admin_settings_model->getBranchDayClosingData($from_branch);
        	    $fin_year    = $this->$model->get_FinancialYear();
        		$bill_id	= $this->$sales_trans_model->getBillId($to_brn,$sales_bill_no,$fin_year_code);
        	    $bill_date   = ($dCData['entry_date'] == date("Y-m-d") ? date("Y-m-d H:i:s") : $dCData['entry_date']);
        	    $ref_no      =$this->$model->generateRefNo($from_branch,'s_ret_refno',$category_details['id_metal']);
	    
	            $data = array(						
            		'bill_no'		    => $category_details['metal_code'].'-'.$bill_no,
            		'metal_type'		=> $category_details['id_metal'],
            		's_ret_refno'		=> $ref_no,
            		'ref_bill_id'		=> $bill_id,
            		'fin_year_code'		=> $fin_year['fin_year_code'],
            		'bill_type'		    => 14, //Sales return Trasnfer
            	    'bill_date'	        => $bill_date,
            		'created_time'	    => date("Y-m-d H:i:s"),
            		'created_by'        => $this->session->userdata('uid'),
            		'id_branch'         => $from_branch,
            		'goldrate_22ct' 	=> 0,
            		'remark'   	        => 'SALES RETURN TRASNFER',
            		'billing_for'       => 3,
            		'from_branch'       => $from_branch,
            		'to_branch'         => $to_brn,
            		'form_secret'	    => $form_secret,
            	); 
            	$this->db->trans_begin();
     	        $insId = $this->$model->insertData($data,'ret_billing');
     	
     	
	            $bill_details=$this->$sales_trans_model->get_sales_return_req_tag_details($items['cat_id'],$to_brn,$bill_id,$from_branch);
	            
	           foreach($bill_details as $billSale)
    	       {
    	           $tot_bill_amount+= $billSale['item_cost'];
    	           
                    $upd_ret_data=array(
                    'bill_id'           =>$insId,
                    'ret_bill_id'       =>$billSale['bill_id'],
                    'ret_bill_det_id'   =>$billSale['bill_det_id'],
                    );
                    $this->$model->insertData($upd_ret_data,'ret_bill_return_details');
            							 		
        			//print_r($this->db->last_query());exit;
        		    if($billSale['tag_status']==0)
        		    {
        		        $this->$model->updateData(array('tag_status'=>4),'tag_id',$billSale['tag_id'], 'ret_taging');
 
        			    $tag_log=array(
                        'tag_id'	  =>$billSale['tag_id'],
                        'date'		  =>$bill_date,
                        'status'	  =>12,
                        'from_branch' =>$from_branch,
                        'to_branch'	  =>NULL,
                        'created_on'  =>date("Y-m-d H:i:s"),
                        'created_by'  =>$this->session->userdata('uid'),
                        'form_secret' =>$form_secret
                        );
                        
                        $this->$model->insertData($tag_log,'ret_taging_status_log');
        		    }
    			    
    	       }
    	       $this->$model->updateData(array('tot_bill_amount'=>'-'.number_format($tot_bill_amount,2,'.','')),'bill_id',$insId, 'ret_billing');
	        }
	        
	    
	    if($this->db->trans_status() === TRUE)
	    {
	        $this->db->trans_commit();
	        $return_data=array('status'=>TRUE,'id'=>$insId,'message'=>'Sales return Trasnfer Added Successfully..');
	    }
	    else
	    {
	        $this->db->trans_rollback();
	        //print_r($this->db->last_query());exit;
	        $return_data=array('status'=>FALSE,'id'=>'','message'=>'Unable to proceed the requested process..');
	    }
	    echo json_encode($return_data);
	}
	
	
	function update_sales_ret_transfer()
	{
		$model = "ret_sales_transfer_model";
	    $return_data            = [];
	    $from_branch            = $this->input->post('from_brn');
	    $to_brn                 = $this->input->post('to_brn');
	    $tot_bill_amount        = $this->input->post('tot_bill_amount');
	    $req_data               = $this->input->post('req_data');
	    $sales_bill_no          = $this->input->post('bill_no');
	    $fin_year_code          = $this->input->post('fin_year_code');
        $form_secret            = $this->input->post('form_secret');

		$bill_id	=  $this->$model->getBillId($to_brn,$sales_bill_no,$fin_year_code);
        
        $status=$this->$model->updateData(array("download_date"=>$tb_entry_date,"download_by"=>$this->session->userdata('uid')),'bill_id',$bill_id,'ret_billing');
	        
		
	        foreach($req_data as $items)
	        {
	            $bill_details=$this->$model->get_sales_return_tag_details($items['cat_id'],$from_branch,$items['bill_id']);
	            
	           foreach($bill_details as $billSale)
    	       {
                    
        			if($billSale['tag_status']==4)
        			{
        			    $this->$model->updateData(array('tag_status'=>0,'current_branch'=>$to_brn),'tag_id',$billSale['tag_id'], 'ret_taging');
    			    
                        $dayCData = $this->admin_settings_model->getAllBranchDCData(); 
                	    $fb_entry_date = NULL;
                	    $tb_entry_date = NULL;
                	    foreach($dayCData as $dayClose){
                	        	
                	        if($from_branch == $dayClose['id_branch']){ // From Branch
                	            $fb_entry_date = $dayClose['entry_date'];
                	        }
                	        if($to_brn == $dayClose['id_branch']){ // To Branch
                	            $tb_entry_date = $dayClose['entry_date'];
                	        }
                	    }
    	    
                        $tag_log = array(
                            "tag_id"		=> $billSale['tag_id'],
                            "status"		=> 0,
                            "from_branch"	=> $from_branch,
                            "to_branch"		=> $to_brn,
                            "created_by"	=> $this->session->userdata('uid'),
                            "created_on"	=> date('Y-m-d H:i:s'),
                            "date"			=> $tb_entry_date,
                            'form_secret'   =>$form_secret
                        );
                        $this->$model->insertData($tag_log,'ret_taging_status_log');
        			}
        		    else if($billSale['tag_status']==6)
        		    {
        		        $this->$model->updateData(array('current_branch'=>$to_brn),'tag_id',$billSale['tag_id'], 'ret_taging');
        		    }
    			    
                    
                    
    	       }
	        }
	    
	    if($this->db->trans_status() === TRUE)
	    {
	        $this->db->trans_commit();
	        $return_data=array('status'=>TRUE,'id'=>$insId,'message'=>'Sales Trasnfer Added Successfully..');
	    }
	    else
	    {
	        $this->db->trans_rollback();
	        //print_r($this->db->last_query());exit;
	        $return_data=array('status'=>FALSE,'id'=>'','message'=>'Unable to proceed the requested process..');
	    }
	    echo json_encode($return_data);
	}
	
	
	
}	
?>
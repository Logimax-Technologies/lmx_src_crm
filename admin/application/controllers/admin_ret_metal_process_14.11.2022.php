<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH.'libraries/dompdf/autoload.inc.php');
use Dompdf\Dompdf;
class Admin_ret_metal_process extends CI_Controller {
	const VIEW_FOLDER = 'ret_metal_process/';
	const RET_PROCESS_MODEL = 'ret_metal_process_model'; 
	function __construct()
	{
		parent::__construct();
		ini_set('date.timezone', 'Asia/Calcutta');
		$this->load->model(self::RET_PROCESS_MODEL); 
		$this->load->model("admin_settings_model"); 
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

    function index(){
		
	}

    function get_old_metal_type()
    {
        $model=	self::RET_PROCESS_MODEL;
        $data=$this->$model->get_old_metal_type();
        echo json_encode($data);
    }
    
    
    function metal_process($type="",$id="")
	{   
       
		$model=	self::RET_PROCESS_MODEL;
		switch($type)
		{
			case 'list': 
					$data['main_content'] = self::VIEW_FOLDER.'process_master/list';
        			$this->load->view('layout/template', $data);
			break;
            case 'add': 
                $data['main_content'] = self::VIEW_FOLDER.'process_master/form';
                $this->load->view('layout/template', $data);
            break;
            case 'edit': 
                $data['process']=$this->$model->getMetalProcess($id);
                $data['main_content'] = self::VIEW_FOLDER.'process_master/form';
        		$this->load->view('layout/template', $data);
            break;
			case 'save':
				$addData=$_POST['process'];
				$insData=array(
					'process_name'	=>strtoupper($addData['process_name']),
					'has_charge'    =>$addData['has_charge'],
					'charge_type'   =>$addData['charge_type'],
					'created_by'    =>$this->session->userdata('uid'),
					'created_on'    =>date("Y-m-d H:i:s"),
				);
				$this->db->trans_begin();
				$insId = $this->$model->insertData($insData,'ret_old_metal_process_master');
				if($insId)
				{
				    if($this->db->trans_status()===TRUE)
    				{
    					$this->db->trans_commit();
    					$this->session->set_flashdata('chit_alert',array('message'=>'Process Created successfully','class'=>'success','title'=>'Add Process'));
    				}
    				else
    				{
    					$this->db->trans_rollback();
    					 $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Add Process'));
    				}
				}
				
			redirect('admin_ret_metal_process/metal_process/list');	
			break;
			
			case 'update':
				$addData=$_POST['process'];
				$insData=array(
					'process_name'	=>strtoupper($addData['process_name']),
					'has_charge'    =>$addData['has_charge'],
					'charge_type'   =>$addData['charge_type'],
					'updated_by'    =>$this->session->userdata('uid'),
					'updated_on'    =>date("Y-m-d H:i:s"),
				);
				$this->db->trans_begin();
				$status = $this->$model->updateData($insData,'id_metal_process',$id,'ret_old_metal_process_master');
				if($status)
				{
				    if($this->db->trans_status()===TRUE)
    				{
    					$this->db->trans_commit();
    					$this->session->set_flashdata('chit_alert',array('message'=>'Process Updated successfully','class'=>'success','title'=>'Add Process'));
    				}
    				else
    				{
    					$this->db->trans_rollback();
    					 $this->session->set_flashdata('chit_alert', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Add Process'));
    				}
				}
				
			redirect('admin_ret_metal_process/metal_process/list');	
			break;
			
			case 'ajax': 
					$list=$this->$model->ajax_getMetalProcess($_POST); 
				  	$access = $this->admin_settings_model->get_access('admin_ret_metal_process/metal_process/list');
			        $data = array(
		        					'list'  => $list,
									'access'=> $access
		        				 );  
					echo json_encode($data);
				break;
		}
	    
    }
    
    
    function get_ActiveMetalProcess()
    {
        $model=	self::RET_PROCESS_MODEL;
        $data = $this->$model->ajax_getMetalProcess();
        echo json_encode($data);
    }

    function metal_pocket($type="")
	{   
       
		$model=	self::RET_PROCESS_MODEL;
		switch($type)
		{
			case 'list': 
					$data['main_content'] = self::VIEW_FOLDER.'pocket/list';
        			$this->load->view('layout/template', $data);
			break;
            case 'add': 
                $data['main_content'] = self::VIEW_FOLDER.'pocket/form';
                $this->load->view('layout/template', $data);
            break;
            case 'metal_list': 
                $data=$this->$model->get_metal_stock_list($_POST);
                echo json_encode($data);
            break;
			case 'save':
				$addData=$_POST;
				//echo "<pre>";print_r($addData);exit;
				$pocket_no = $this->$model->code_number_generator();   //Bill Number Generate 
				$insData=array(
					'date' 		=> date("Y-m-d"),
					'pocket_no' => $pocket_no,
					'piece'	    =>$addData['total_pcs'],
					'gross_wt'	=>$addData['total_gross_wt'],
					'net_wt'	=>$addData['total_net_wt'],
					'avg_purity'=>$addData['avg_purity_per'],
					'total_purity'=>$addData['total_item_purity'],
					'amount'    =>$addData['total_amount'],
					'created_by'=>$this->session->userdata('uid'),
					'created_on' => date("Y-m-d H:i:s"),
				);
				$this->db->trans_begin();
				$insId = $this->$model->insertData($insData,'ret_old_metal_pocket');
				
				foreach($addData['req_data'] as $val)
				{
					$metal_details=array(
						'id_metal_pocket'	=>$insId,
						'id_metal_type'		=>(isset($val['id_old_metal_type']) ? $val['id_old_metal_type']:NULL),
						'old_metal_sale_id' =>(isset($val['old_metal_sale_id']) ? $val['old_metal_sale_id']:NULL),
						'tag_id'            =>(isset($val['tag_id']) ? $val['tag_id']:NULL),
						'type'			    =>$val['pocket_type'],//1-Old Metal,2-Sales Return
						'piece'			    =>$val['piece'],
						'piece'			    =>$val['piece'],
						'gross_wt'			=>$val['gross_wt'],
						'net_wt'			=>$val['net_wt'],
						'item_cost'			=>$val['item_cost'],
						'purity'			=>$val['purity_per'],
					);
					$status=$this->$model->insertData($metal_details,'ret_old_metal_pocket_details');
					//print_r($this->db->last_query());exit;
					if($status)
					{
					    if($val['pocket_type']==1)
					    {
					        $this->$model->updateData(array('is_pocketed'=>1),'old_metal_sale_id',$val['old_metal_sale_id'], 'ret_bill_old_metal_sale_details');
					    }
					    else if($val['pocket_type']==2 || $val['pocket_type']==3)
					    {
					        $this->$model->updateData(array('is_pocketed'=>1),'tag_id',$val['tag_id'],'ret_taging');
					    }
						
						
					}
				}
				if($this->db->trans_status()===TRUE)
				{
					$this->db->trans_commit();
					$response_data=array('status'=>TRUE,'message'=>'Pocket Created successfully..');
				}
				else
				{
					$this->db->trans_rollback();
					$response_data=array('status'=>FALSE,'message'=>'Unable To Proceed Your Request..');
				}
				echo json_encode($response_data);
			break;
			case 'ajax': 
					$list=$this->$model->get_pocket_list($_POST); 
				  	$access = $this->admin_settings_model->get_access('admin_ret_reports/old_metal_purchase/list');
			        $data = array(
		        					'list'  => $list,
									'access'=> $access
		        				 );  
					echo json_encode($data);
				break;
		}
	    
    }
    
    
    function metal_process_issue($type="")
	{   
       
		$model=	self::RET_PROCESS_MODEL;
		switch($type)
		{
			case 'list': 
					$data['main_content'] = self::VIEW_FOLDER.'metal_process/list';
        			$this->load->view('layout/template', $data);
			break;
            case 'add': 
                $data['main_content'] = self::VIEW_FOLDER.'metal_process/form';
                $this->load->view('layout/template', $data);
            break;
            case 'metal_list': 
                $data=$this->$model->get_metal_stock_list($_POST);
                echo json_encode($data);
            break;
            
            case 'save':
                $addData=$_POST['process'];
                $receipt_payment=$_POST['receipt_payment'];
               // echo "<pre>";print_r($addData);exit;
               
               $ho              = $this->$model->get_headOffice();
               
                $this->db->trans_begin();
                $process_no=$this->$model->generate_process_number($addData['process_for'],$addData['id_metal_process']);
                $insData=array(
                                'process_no'        =>$process_no,
                                'process_for'       =>$addData['process_for'], //1-Issue,2-Receipt
                                'id_metal_process'  =>$addData['id_metal_process'],
                                'id_karigar'        =>$addData['id_karigar'],
                                'created_on'        =>date("Y-m-d H:i:s"),
                                'created_by'        =>$this->session->userdata('uid'),
                               );
                $insId = $this->$model->insertData($insData,'ret_old_metal_process');
                
                if($insId)
                {
                    if($addData['id_metal_process']==1) // MELTING
                    {
                        if($addData['process_for']==1) // Issue
                        {
                                $melting_data=array(
                                                    'id_old_metal_process' =>$insId,
                                                    'piece'          =>(isset($addData['piece']) ?$addData['piece'] :0),
                                                    'gross_wt'       =>$addData['gross_wt'],
                                                    'net_wt'         =>$addData['net_wt'],
                                                    'amount'         =>$addData['amount'],
                                                    'purity'         =>$addData['purity'],
                                                    'melting_status' =>0,
                                                    'created_on'     =>date("Y-m-d H:i:s"),
                                                    'created_by'     =>$this->session->userdata('uid'),
                                                   );
                                $melting_status = $this->$model->insertData($melting_data,'ret_old_metal_melting');
                                if($melting_status)
                                {
                                    $pocket_details=$_POST['pocket'];
                		 		    if(!empty($pocket_details))
                		 		    {
                		 		        foreach($pocket_details['id_metal_pocket'] as $key => $val)
                		 		        {
                                            $melting_details=array(
                                                'id_melting'    =>$melting_status,
                                                'id_pocket'     =>$pocket_details['id_metal_pocket'][$key],
                                                'issue_pcs'     =>$pocket_details['piece'][$key],
                                                'issue_gwt'     =>$pocket_details['gross_wt'][$key],
                                                'issue_nwt'     =>$pocket_details['net_wt'][$key],
                                                'issue_purity'  =>$pocket_details['avg_purity'][$key],
                                            );
                                            $status=$this->$model->insertData($melting_details,'ret_old_metal_melting_details');
                                            if($status)
                                            {
                                                
                                                
                                                $pocket_update_data = array(
                                                                            'issue_pcs'=>$pocket_details['piece'][$key],
                                                                            'issue_gwt'=>$pocket_details['gross_wt'][$key],
                                                                            'issue_nwt'=>$pocket_details['net_wt'][$key],
                                                                            'issue_purity'=>($pocket_details['avg_purity'][$key]*$pocket_details['piece'][$key]),
                                                                            'updated_on' =>date("Y-m-d H:i:s"),
                                                                            'updated_by' =>$this->session->userdata('uid'),
                                                                            );
                                                 $pocketStatus = $this->$model->updatePocketItem($pocket_details['id_metal_pocket'][$key],$pocket_update_data,'+');
                                                
                                                 /*$pocketStatus=$this->$model->updateData(array('status'=>$addData['id_metal_process']),'id_metal_pocket',$pocket_details['id_metal_pocket'][$key],'ret_old_metal_pocket');
                                                 if($pocketStatus)
                                                 {
                                                    $pocketDetails=$this->$model->getPocketingDetails($pocket_details['id_metal_pocket'][$key]); 
                                                    //UPDATEING PURCHASE ITEM LOG 
                                                    if(sizeof($pocketDetails)>0)
                                                    {
                                                        $branchDetails=$this->$model->get_branch_details();
                                                        foreach($pocketDetails as $items)
                                                        {
                                                                $old_metal_log=array(
                                                                'old_metal_sale_id'=>$items['old_metal_sale_id'],
                                                                'from_branch'      =>$branchDetails['id_branch'],
                                                                'to_branch'        =>NULL,
                                                                'status'           =>3,
                                                                'item_type'        =>1, // Old Metal
                                                                'date'             =>$branchDetails['entry_date'],
                                                                'created_on'       =>date("Y-m-d H:i:s"),
                                                                'created_by'      =>$this->session->userdata('uid'),
                                                                );
                                                                $this->$model->insertData($old_metal_log,'ret_purchase_items_log');
                                                        }
                                                    }
                                                    //UPDATEING PURCHASE ITEM LOG 
                                                 }*/
                                            }
                		 		        }
                		 		    }
                		 		    if($this->db->trans_status()===TRUE)
                		 		    {
                		 		        $this->db->trans_commit();
            							 $log_data = array(
                                        'id_log'        => $this->session->userdata('id_log'),
                                        'event_date'    => date("Y-m-d H:i:s"),
                                        'module'        => 'Melting',
                                        'operation'     => 'Add',
                                        'record'        =>  $insId,  
                                        'remark'        => 'Melting added successfully'
                                        );
                                        $this->log_model->log_detail('insert','',$log_data);
                                        $this->session->set_flashdata('chit_alert',array('message'=>'Process Given to Melting successfully','class'=>'success','title'=>'Melting'));
                                        $responseData=array('status'=>TRUE,'message'=>'Process Given to Melting');
                		 		    }else{
                		 		        $this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Melting'));
                		 		        $responseData=array('status'=>FALSE,'message'=>'Unable to proceed the requested process');
                		 		    }
                                }
                            }
                            if($addData['process_for']==2) // Receipt
                            {
                                $receipt_details=$_POST['receipt'];
                                //echo "<pre>";print_r($receipt_payment);exit;
                                if(!empty($receipt_details))
                                {
                                    $branchDetails=$this->$model->get_branch_details();
                                    foreach($receipt_details['is_melting_select'] as $key => $val)
                                    {
                                       if($receipt_details['is_melting_select'][$key]==1)
                                       {
                                            $updMeltingData=array(
                                                            'melting_status'    =>1,
                                                            //'received_category' =>$receipt_details['id_ret_category'][$key],
                                                            'received_wt'       =>$receipt_details['recd_gwt'][$key],
                                                            'received_less_wt'  =>$receipt_details['received_less_wt'][$key],
                                                            'receipt_charges'   =>$receipt_details['charge'][$key],
                                                            'receipt_ref_no'    =>($receipt_details['ref_no'][$key]!='' ? $receipt_details['ref_no'][$key] : NULL),
                                                            'id_old_metal_process_receipt'=>$insId,
                                                            'updated_on'        =>date("Y-m-d H:i:s"),
                                                            'updated_by'        =>$this->session->userdata('uid'),
                                                          );
                                            $melting_receipt_status = $this->$model->updateData($updMeltingData,'id_melting',$receipt_details['id_melting'][$key],'ret_old_metal_melting');
                                            
                                            
                                            
                                            $CategoryDetails=json_decode($receipt_details['category_details'][$key],true);
                                            //print_r($CategoryDetails);exit;
                                                if(!empty($CategoryDetails))
                                                {
                                                    foreach($CategoryDetails as $cat)
                                                    {
                                                        $cateegoryData=array(
                                                                            'id_melting'        =>$receipt_details['id_melting'][$key],
                                                                            'melting_status'    =>1,
                                                                            'received_category' =>$cat['id_ret_category'],
                                                                            'id_product'        =>$cat['id_product'],
                                                                            'received_wt'       =>$cat['recd_gross_wt'],
                                                                            );
                                                        $this->$model->insertData($cateegoryData,'ret_old_metal_melting_recd_details');
                                                        
                                                        $categoryDetails=$this->$model->getCategoryDetails($cat['id_ret_category']);
                                                        
                                                        if($categoryDetails['cat_type']==3)
                                                        {
                                                                //UPDATE INTO PURCHASE ITEM STOCK SUMMARY

                                			        	        $is_po_item_exist = $this->$model->checkStoneItemStockExist($cat['id_ret_category'],$cat['id_product'],$branchDetails['id_branch']); //CHECK ITEM EXISTS IN TABLE 
                                			        	        
                                			        	        $pur_item_stock_summary = array(
                              										        'id_branch'	        => $branchDetails['id_branch'],
                              										        'id_ret_category'	=> $cat['id_ret_category'],
                              										        'id_product'	    => $cat['id_product'],
                              										        //'type'              => 5,//Old Metal Stone
                              										        'gross_wt'		    => $cat['recd_gross_wt'],  
                            												'net_wt'		    => $cat['recd_gross_wt'],  
                            												);
                            											
                            									if($is_po_item_exist['status']) //IF ITEM EXISTS ALREADY IN TABLE
                            									{
                            									    	 $pur_item_stock_summary['updated_by']=$this->session->userdata('uid');
                            									    	 $pur_item_stock_summary['updated_on']=date('Y-m-d H:i:s');
                            											 $this->$model->updateStoneItemData($pur_item_stock_summary,'+');
                            									}
                            									else // INSERT INTO PURCHASE ITEM STOCK SUMMARY
                            									{
                            									    $pur_item_stock_summary['created_by']=$this->session->userdata('uid');
                            									    $pur_item_stock_summary['created_on']=date('Y-m-d H:i:s');
                            									    $this->$model->insertData($pur_item_stock_summary,'ret_purchase_item_stock_summary');
                            									}
                                                                //UPDATE INTO PURCHASE ITEM STOCK SUMMARY
                                                        }
                                                        
                                                        $pur_items_log=array(
                                                        'id_old_metal_process'  =>$insId,
                                                        'to_branch'             =>$branchDetails['id_branch'],
                                                        'from_branch'           =>NULL,
                                                        'status'                =>1,
                                                        'item_type'             =>4, // Old Metal Process
                                                        'date'                  =>$branchDetails['entry_date'],
                                                        'created_on'            =>date("Y-m-d H:i:s"),
                                                        'created_by'            =>$this->session->userdata('uid'),
                                                        );
                                                        $this->$model->insertData($pur_items_log,'ret_purchase_items_log');
                                                    }
                                                }
                                                    
                                                    
                                            //print_r($this->db->last_query());exit;
                                            if($melting_receipt_status)
                                            {
                                                $pur_items_log=array(
                                                'id_old_metal_process'  =>$receipt_details['id_old_metal_process'][$key],
                                                'to_branch'             =>$branchDetails['id_branch'],
                                                'from_branch'           =>NULL,
                                                'status'                =>1,
                                                'item_type'             =>4, // Old Metal Process
                                                'date'                  =>$branchDetails['entry_date'],
                                                'created_on'            =>date("Y-m-d H:i:s"),
                                                'created_by'            =>$this->session->userdata('uid'),
                                                );
                                                $this->$model->insertData($pur_items_log,'ret_purchase_items_log');
                                            }
                                        }
                                    }
                                    if($this->db->trans_status()===TRUE)
                		 		    {
                		 		        $this->db->trans_commit();
            							 $log_data = array(
                                        'id_log'        => $this->session->userdata('id_log'),
                                        'event_date'    => date("Y-m-d H:i:s"),
                                        'module'        => 'Melting',
                                        'operation'     => 'Add',
                                        'record'        =>  $insId,  
                                        'remark'        => 'Melting Receipt successfully'
                                        );
                                        $this->log_model->log_detail('insert','',$log_data);
                                        $this->session->set_flashdata('chit_alert',array('message'=>'Melting Receipt','class'=>'success','title'=>'Melting'));
                                        $responseData=array('status'=>TRUE,'message'=>'Melting Receipt');
                		 		    }else{
                		 		        $this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Melting'));
                		 		        $responseData=array('status'=>FALSE,'message'=>'Unable to proceed the requested process');
                		 		    }
                                }
                                //echo "<pre>";print_r($receipt_details);exit;
                            }
                    }
                    else if($addData['id_metal_process']==2) // TESTING
                    {
                        if($addData['process_for']==1) // Issue
                        {
                             $testing_issue_details=$_POST['testing_issue'];
                             if(!empty($testing_issue_details))
                             {
                                    foreach($testing_issue_details['is_melting_select'] as $key => $val)
                                    {
                                       if($testing_issue_details['is_melting_select'][$key]==1)
                                       {
                                            $testing_issue_data=array(
                                                'id_old_metal_process' =>$insId,
                                                'id_melting_recd'      =>$testing_issue_details['id_melting_recd'][$key],
                                                'net_wt'               =>$testing_issue_details['weight'][$key],
                                                'amount'               =>$testing_issue_details['amount'][$key],
                                                'purity'               =>$testing_issue_details['purity'][$key],
                                            );
                                            $testing_issue_status = $this->$model->insertData($testing_issue_data,'ret_old_metal_testing');
                                            if($testing_issue_status)
                                            {
                                                $TestingIssue=$this->$model->updateData(array('melting_status'=>2),'id_melting_recd',$testing_issue_details['id_melting_recd'][$key],'ret_old_metal_melting_recd_details');
                                                 $branchDetails=$this->$model->get_branch_details();
                                                        $testing_issue_log=array(
                                                        'id_old_metal_process'=>$insId,
                                                        'from_branch'      =>$branchDetails['id_branch'],
                                                        'to_branch'        =>NULL,
                                                        'status'           =>3,
                                                        'item_type'        =>5, // Old Metal Process Outward
                                                        'date'             =>$branchDetails['entry_date'],
                                                        'created_on'       =>date("Y-m-d H:i:s"),
                                                        'created_by'      =>$this->session->userdata('uid'),
                                                        );
                                                        $this->$model->insertData($testing_issue_log,'ret_purchase_items_log');
                                            }
                                       }
                                    }
                                    if($this->db->trans_status()===TRUE)
                		 		    {
                		 		        $this->db->trans_commit();
            							 $log_data = array(
                                        'id_log'        => $this->session->userdata('id_log'),
                                        'event_date'    => date("Y-m-d H:i:s"),
                                        'module'        => 'Testing',
                                        'operation'     => 'Add',
                                        'record'        =>  $insId,  
                                        'remark'        => 'Testing Issue'
                                        );
                                        $this->log_model->log_detail('insert','',$log_data);
                                        $this->session->set_flashdata('chit_alert',array('message'=>'Testing Issue Successfully','class'=>'success','title'=>'Testing'));
                                        $responseData=array('status'=>TRUE,'message'=>'Testing Issue');
                		 		    }else{
                		 		        $this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Melting'));
                		 		        $responseData=array('status'=>FALSE,'message'=>'Unable to proceed the requested process');
                		 		    }
                             }
                        }
                        else if($addData['process_for']==2) // Receipt
                        {
                              $receipt_details=$_POST['testing_receipt'];
                              //echo "<pre>";print_r($receipt_details['process_type']);exit;
                                if(!empty($receipt_details))
                                {
                                    $branchDetails=$this->$model->get_branch_details();
                                    foreach($receipt_details['is_melting_select'] as $key => $val)
                                    {
                                       if($receipt_details['is_melting_select'][$key]==1)
                                       {
                                           
                                           if($_POST['process']['process_type']==2)
                                           {
                                               /*$stockStatus=$this->$model->check_purity_stock_details($receipt_details['received_purity'][$key],$receipt_details['received_category'][$key],$receipt_details['id_product'][$key],$branchDetails['id_branch']);

                                                $pur_item_stock_summary = array(
                                                    'id_branch'	        => $branchDetails['id_branch'],
                                                    'purity'            => $receipt_details['received_purity'][$key],
                                                    'id_ret_category'   => $receipt_details['received_category'][$key],
                                                    'id_product'        => $receipt_details['id_product'][$key],
                                                    //'type'              => 0,//Bullion Purchase
                                                    'gross_wt'		    => $receipt_details['recd_gwt'][$key],  
                                                    'less_wt'		    => 0,  
                                                    'net_wt'		    => $receipt_details['recd_gwt'][$key], 
                                                );
        												
                                               	if($stockStatus['status']) //IF ITEM EXISTS ALREADY IN TABLE
            									{
            									         $id_stock_summary=$stockStatus['id_stock_summary'];
            									    	 $pur_item_stock_summary['updated_by']=$this->session->userdata('uid');
            									    	 $pur_item_stock_summary['updated_on']=date('Y-m-d H:i:s');
            											 $this->$model->updateStockItemData($id_stock_summary,$pur_item_stock_summary,'+');
            									}
            									else // INSERT INTO PURCHASE ITEM STOCK SUMMARY
            									{
            									    $pur_item_stock_summary['created_by']=$this->session->userdata('uid');
            									    $pur_item_stock_summary['created_on']=date('Y-m-d H:i:s');
            									    $id_stock_summary=$this->$model->insertData($pur_item_stock_summary,'ret_purchase_item_stock_summary');
            									}
            									
            									$stock_log_data=array(
                                                'id_stock_summary'=>$id_stock_summary,
                                                'date_add'        =>date('Y-m-d H:i:s'),
                                                'ref_no'          =>$process_no,
                                                'gross_wt'        =>$receipt_details['recd_gwt'][$key],
                                                'net_wt'          =>$receipt_details['recd_gwt'][$key],
                                                'transcation_type'=>0,
                                                'remarks'         =>'FROM TESTING PROCESS'
                                                );
                                                $this->$model->insertData($stock_log_data,'ret_purchase_item_stock_summary_log');*/
                                                
                                                $Lotdata = array(
                            							'lot_date'				=> date("Y-m-d H:i:s"),
                            							'lot_from'				=> 5,
                            							'lot_type'				=> 1,
                            							'stock_type'			=> 2,
                            							'gold_smith'            => $addData['id_karigar'],
                            							'lot_received_at'		=> $branchDetails['id_branch'],
                            							'created_branch'		=> $branchDetails['id_branch'],
                            						    'id_category'			=> $receipt_details['received_category'][$key],
                            							'id_purity'				=> $receipt_details['received_purity'][$key],
                            							'narration'      		=> 'From Testing Process',
                            							'id_metal_process'      => $insId,
                            							'created_on'	  		=> date("Y-m-d H:i:s"),
                            							'created_by'      		=> $this->session->userdata('uid')
                            						);
                            						$lotId = $this->$model->insertData($Lotdata,'ret_lot_inwards');
                                                

                						        $item_details=array('lot_no'=>$lotId,'lot_product'=>$receipt_details['id_product'][$key],'no_of_piece'=>1,'gross_wt'=>$receipt_details['recd_gwt'][$key],'net_wt'=>$receipt_details['recd_gwt'][$key]); 
                						        
                						        $this->$model->insertData($item_details,'ret_lot_inwards_detail');
                						        
                						        $existData=array('id_product'=>$receipt_details['id_product'][$key],'id_branch'=>$branchDetails['id_branch']);
                						        
                						        $isExist = $this->$model->checkNonTagItemExist($existData);
                						        
                						        if($isExist['status'] == TRUE)
                						        {
                						            $nt_data = array(
                                                    'id_nontag_item'=>$isExist['id_nontag_item'],
                                                    'gross_wt'		=> $receipt_details['recd_gwt'][$key],
                                                    'net_wt'		=> $receipt_details['recd_gwt'][$key], 
                                                    'no_of_piece'   =>1,
                                                    'updated_by'	=> $this->session->userdata('uid'),
                                                    'updated_on'	=> date('Y-m-d H:i:s'),
                                                    );
                                                    $this->$model->updateNTData($nt_data,'+');
                                        													
                                                    $non_tag_data=array(
                                                    'product'	    => $receipt_details['id_product'][$key],
                                                    'gross_wt'		=> $receipt_details['recd_gwt'][$key],
                                                    'net_wt'		=> $receipt_details['recd_gwt'][$key],
                                                    'no_of_piece'   =>1,
                                                    'from_branch'	=> NULL,
                                                    'to_branch'	    => $branchDetails['id_branch'],
                                                    'status'	    => 0,
                                                    'date'          => $branchDetails['entry_date'],
                                                    'created_on'    => date("Y-m-d H:i:s"),
                                                    'created_by'    =>  $this->session->userdata('uid')
                                                    );
                                                    $this->$model->insertData($non_tag_data,'ret_nontag_item_log');
                						        }else
                						        {
                						            $nt_data=array(
                                                        'branch'	    => $branchDetails['id_branch'],
                                                        'product'	    => $receipt_details['id_product'][$key],
                                                        'gross_wt'		=> $receipt_details['recd_gwt'][$key],
                                                        'net_wt'		=> $receipt_details['recd_gwt'][$key],
                                                        'no_of_piece'   =>1,
                                                        'created_on'    => date("Y-m-d H:i:s"),
                                                        'created_by'    => $this->session->userdata('uid')
                                                    );
                                                    $this->$model->insertData($nt_data,'ret_nontag_item'); 
                                                    
                                                    $non_tag_data=array(
                                                    'product'	    => $receipt_details['id_product'][$key],
                                                    'gross_wt'		=> $receipt_details['recd_gwt'][$key],
                                                    'net_wt'		=> $receipt_details['recd_gwt'][$key],
                                                    'no_of_piece'   =>1,
                                                    'from_branch'	=> NULL,
                                                    'to_branch'	    => $branchDetails['id_branch'],
                                                    'status'	    => 0,
                                                    'date'          => $branchDetails['entry_date'],
                                                    'created_on'    => date("Y-m-d H:i:s"),
                                                    'created_by'    =>  $this->session->userdata('uid')
                                                    );
                                                    $this->$model->insertData($non_tag_data,'ret_nontag_item_log'); 
                						        }
                						        
                                    
                                           }
                                           
                                            $updTestingReceiptData=array(
                                                            'received_purity'   =>$receipt_details['received_purity'][$key],
                                                            'received_wt'       =>$receipt_details['recd_gwt'][$key],
                                                            'production_loss'   =>$receipt_details['received_less_wt'][$key],
                                                            'receipt_charges'   =>$receipt_details['receipt_charges'][$key],
                                                            'receipt_ref_no'    =>($receipt_details['receipt_ref_no'][$key]!='' ? $receipt_details['receipt_ref_no'][$key] : NULL),
                                                            'testing_status'    =>($_POST['process']['process_type']==2 ? 2:1),
                                                            'id_old_metal_process_receipt'=>$insId,
                                                            'updated_on'        =>date("Y-m-d H:i:s"),
                                                            'updated_by'        =>$this->session->userdata('uid'),
                                                          );
                                            $melting_receipt_status = $this->$model->updateData($updTestingReceiptData,'id_metal_testing',$receipt_details['id_metal_testing'][$key],'ret_old_metal_testing');
                                            //print_r($this->db->last_query());exit;
                                            if($melting_receipt_status)
                                            {
                                                
                                                $this->$model->updateData(array('melting_status'=>3),'id_melting_recd',$receipt_details['id_melting_recd'][$key],'ret_old_metal_melting_recd_details');
                                                //print_r($this->db->last_query());exit;
                                                $pur_items_log=array(
                                                'id_old_metal_process'  =>$insId,
                                                'to_branch'             =>$branchDetails['id_branch'],
                                                'from_branch'           =>NULL,
                                                'status'                =>1,
                                                'item_type'             =>4, // Old Metal Process
                                                'date'                  =>$branchDetails['entry_date'],
                                                'created_on'            =>date("Y-m-d H:i:s"),
                                                'created_by'            =>$this->session->userdata('uid'),
                                                );
                                                $this->$model->insertData($pur_items_log,'ret_purchase_items_log');
                                            }
                                        }
                                    }
                                    if($this->db->trans_status()===TRUE)
                		 		    {
                		 		        $this->db->trans_commit();
            							 $log_data = array(
                                        'id_log'        => $this->session->userdata('id_log'),
                                        'event_date'    => date("Y-m-d H:i:s"),
                                        'module'        => 'Melting',
                                        'operation'     => 'Add',
                                        'record'        =>  $insId,  
                                        'remark'        => 'Testing Receipt successfully'
                                        );
                                        $this->log_model->log_detail('insert','',$log_data);
                                        $this->session->set_flashdata('chit_alert',array('message'=>'Testing Receipt','class'=>'success','title'=>'Testing Receipt'));
                                        $responseData=array('status'=>TRUE,'message'=>'Testing Receipt');
                		 		    }else{
                		 		        $this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Testing Receipt'));
                		 		        $responseData=array('status'=>FALSE,'message'=>'Unable to proceed the requested process');
                		 		    }
                                }
                        }
                    }
                    else if($addData['id_metal_process']==3) // Refining
                    {
                        if($addData['process_for']==1) // Issue
                        {
                             $refining_issue_details=$_POST['refining_issue'];
                             if(!empty($refining_issue_details))
                             {
                                    foreach($refining_issue_details['is_melting_select'] as $key => $val)
                                    {
                                       if($refining_issue_details['is_melting_select'][$key]==1)
                                       {
                                            $refining_issue_data=array(
                                                'id_old_metal_process' =>$insId,
                                                'id_metal_testing'     =>$refining_issue_details['id_metal_testing'][$key],
                                                'refining_status'      =>0,
                                            );
                                            $refining_issue_status = $this->$model->insertData($refining_issue_data,'ret_old_metal_refining');
                                            if($refining_issue_status)
                                            {
                                                $TestingIssue=$this->$model->updateData(array('melting_status'=>4),'id_melting_recd',$refining_issue_details['id_melting_recd'][$key],'ret_old_metal_melting_recd_details');
                                                
                                                 $branchDetails=$this->$model->get_branch_details();
                                                        $testing_issue_log=array(
                                                        'id_old_metal_process'=>$insId,
                                                        'from_branch'      =>$branchDetails['id_branch'],
                                                        'to_branch'        =>NULL,
                                                        'status'           =>3,
                                                        'item_type'        =>5, // Old Metal Process Outward
                                                        'date'             =>$branchDetails['entry_date'],
                                                        'created_on'       =>date("Y-m-d H:i:s"),
                                                        'created_by'      =>$this->session->userdata('uid'),
                                                        );
                                                        $this->$model->insertData($testing_issue_log,'ret_purchase_items_log');
                                            }
                                       }
                                    }
                                    if($this->db->trans_status()===TRUE)
                		 		    {
                		 		        $this->db->trans_commit();
            							 $log_data = array(
                                        'id_log'        => $this->session->userdata('id_log'),
                                        'event_date'    => date("Y-m-d H:i:s"),
                                        'module'        => 'Refining',
                                        'operation'     => 'Add',
                                        'record'        =>  $insId,  
                                        'remark'        => 'Refining Issue'
                                        );
                                        $this->log_model->log_detail('insert','',$log_data);
                                        $this->session->set_flashdata('chit_alert',array('message'=>'Refining Issue Successfully','class'=>'success','title'=>'Refining'));
                                        $responseData=array('status'=>TRUE,'message'=>'Refining Issue');
                		 		    }else{
                		 		        $this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Refining'));
                		 		        $responseData=array('status'=>FALSE,'message'=>'Unable to proceed the requested process');
                		 		    }
                             }
                        }
                        else if($addData['process_for']==2) // Receipt
                        {
                             $refining_receipt_details=$_POST['refining_receipt'];
                             //echo "<pre>";print_r($refining_receipt_details);exit;
                              if(!empty($refining_receipt_details))
                                {
                                    $branchDetails=$this->$model->get_branch_details();
                                    foreach($refining_receipt_details['is_melting_select'] as $key => $val)
                                    {
                                       if($refining_receipt_details['is_melting_select'][$key]==1)
                                       {
                                            $updRefiningReceiptData=array(
                                                            'refining_status'   =>1,
                                                            'id_old_metal_process_receipt'=>$insId,
                                                            'receipt_charges'   =>$receipt_details['receipt_charges'][$key],
                                                            'receipt_ref_no'    =>($receipt_details['receipt_ref_no'][$key]!='' ? $receipt_details['receipt_ref_no'][$key] : NULL),
                                                            'updated_on'        =>date("Y-m-d H:i:s"),
                                                            'updated_by'        =>$this->session->userdata('uid'),
                                                          );
                                            $refining_receipt_status = $this->$model->updateData($updRefiningReceiptData,'id_metal_refining',$refining_receipt_details['id_metal_refining'][$key],'ret_old_metal_refining');
                                            //print_r($this->db->last_query());exit;
                                           
                                           $this->$model->updateData(array('melting_status'=>5),'id_melting_recd',$refining_receipt_details['id_melting_recd'][$key],'ret_old_metal_melting_recd_details');
                                           
                                           
                                            if($refining_receipt_status)
                                            {
                                                $CategoryDetails=json_decode($refining_receipt_details['category_details'][$key],true);
                                                
                                                if(!empty($CategoryDetails))
                                                {
                                                    foreach($CategoryDetails as $cat)
                                                    {
                                                        $cateegoryData=array(
                                                                            'id_metal_refining' =>$refining_receipt_details['id_metal_refining'][$key],
                                                                            'received_category' =>$cat['id_ret_category'],
                                                                            'id_product'        =>$cat['id_product'],
                                                                            'received_wt'       =>$cat['recd_gross_wt'],
                                                                            'purity'           =>$cat['purity'],
                                                                            );
                                                        $insStatus=$this->$model->insertData($cateegoryData,'ret_old_metal_refining_details');
                                                        
                                                        //UPDATE INTO PURCHASE ITEM STOCK SUMMARY
                                                        if($insStatus)
                                                        {
                                			        	        $itemExistData=array('id_product'=>$cat['id_product'],'id_branch'=>$branchDetails['id_branch']); 
                                			        	        //echo "<pre>";print_r($itemExistData);exit;
                                			        	        $is_po_item_exist = $this->$model->checkPurchaseItemStockExist($cat['id_product'],$branchDetails['id_branch'],$cat['purity']); //CHECK ITEM EXISTS IN TABLE 
                                			        	        $pur_item_stock_summary = array(
                              										        'id_branch'	        => $branchDetails['id_branch'],
                              										        'id_ret_category'	=> $cat['id_ret_category'],
                              										        'id_product'	    => $cat['id_product'],
                              										        //'type'              => 0,//Old Metal Process
                              										        'gross_wt'		    => $cat['recd_gross_wt'],  
                            												'net_wt'		    => $cat['recd_gross_wt'],  
                            												'purity'            => $cat['purity'],
                            												);
                            											
                            									if($is_po_item_exist['status']) //IF ITEM EXISTS ALREADY IN TABLE
                            									{
                            									        $id_stock_summary=$is_po_item_exist['id_stock_summary'];
                            									    	 $pur_item_stock_summary['updated_by']=$this->session->userdata('uid');
                            									    	 $pur_item_stock_summary['updated_on']=date('Y-m-d H:i:s');
                            											 $this->$model->updatePurItemData($is_po_item_exist['id_stock_summary'],$pur_item_stock_summary,'+');
                            									}
                            									else // INSERT INTO PURCHASE ITEM STOCK SUMMARY
                            									{
                            									    $pur_item_stock_summary['created_by']=$this->session->userdata('uid');
                            									    $pur_item_stock_summary['created_on']=date('Y-m-d H:i:s');
                            									    $id_stock_summary=$this->$model->insertData($pur_item_stock_summary,'ret_purchase_item_stock_summary');
                            									}
                            									
                            									
                            									$stock_log_data=array(
                                                                'id_stock_summary'=>$id_stock_summary,
                                                                'ref_no'          =>$insId,
                                                                'piece'           =>0,
                                                                'gross_wt'        =>$cat['recd_gross_wt'],
                                                                'net_wt'          =>$cat['recd_gross_wt'],
                                                                'credit_type'     =>2,
                                                                'transcation_type'=>0,
                                                                'date_add'        =>date('Y-m-d H:i:s'),
                                                                'remarks'         =>'FROM REFINING PROCESS'
                                                                );
                                                                $this->$model->insertData($stock_log_data,'ret_purchase_item_stock_summary_log');
                                                                
                                                        }
                                                        //UPDATE INTO PURCHASE ITEM STOCK SUMMARY
            					    
                                                        
                                                        $pur_items_log=array(
                                                        'id_old_metal_process'  =>$insId,
                                                        'to_branch'             =>$branchDetails['id_branch'],
                                                        'from_branch'           =>NULL,
                                                        'status'                =>1,
                                                        'item_type'             =>4, // Old Metal Process
                                                        'date'                  =>$branchDetails['entry_date'],
                                                        'created_on'            =>date("Y-m-d H:i:s"),
                                                        'created_by'            =>$this->session->userdata('uid'),
                                                        );
                                                        $this->$model->insertData($pur_items_log,'ret_purchase_items_log');
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    if($this->db->trans_status()===TRUE)
                		 		    {
                		 		        $this->db->trans_commit();
            							 $log_data = array(
                                        'id_log'        => $this->session->userdata('id_log'),
                                        'event_date'    => date("Y-m-d H:i:s"),
                                        'module'        => 'Melting',
                                        'operation'     => 'Add',
                                        'record'        =>  $insId,  
                                        'remark'        => 'Testing Receipt successfully'
                                        );
                                        $this->log_model->log_detail('insert','',$log_data);
                                        $this->session->set_flashdata('chit_alert',array('message'=>'Refining Receipt','class'=>'success','title'=>'Refining Receipt'));
                                        $responseData=array('status'=>TRUE,'message'=>'Refining Receipt');
                		 		    }else{
                		 		        $this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Refining Receipt'));
                		 		        $responseData=array('status'=>FALSE,'message'=>'Unable to proceed the requested process');
                		 		    }
                                }
                        }
                    }
                    else if($addData['id_metal_process']==4) // POLISHING
                    {
                        if($addData['process_for']==1) // Issue
                        {
                                $polishing_data=array(
                                                    'id_old_metal_process' =>$insId,
                                                    'no_of_piece'    =>$addData['piece'],
                                                    'gross_wt'       =>$addData['gross_wt'],
                                                    'net_wt'         =>$addData['net_wt'],
                                                    'amount'         =>0,
                                                    'status'         =>0,
                                                    'created_on'     =>date("Y-m-d H:i:s"),
                                                    'created_by'     =>$this->session->userdata('uid'),
                                                   );
                                $melting_status = $this->$model->insertData($polishing_data,'ret_old_metal_polishing');
                                if($melting_status)
                                {
                                    $pocket_details=$_POST['pocket'];
                		 		    if(!empty($pocket_details))
                		 		    {
                		 		        foreach($pocket_details['id_metal_pocket'] as $key => $val)
                		 		        {
                                            $melting_details=array(
                                                'id_polishing'  =>$melting_status,
                                                'id_pocket'     =>$pocket_details['id_metal_pocket'][$key],
                                                'id_old_metal_type'=>$pocket_details['id_metal_type'][$key],
                                                'issue_pcs'     =>$pocket_details['issue_pcs'][$key],
                                                'issue_gwt'     =>$pocket_details['issue_gwt'][$key],
                                                'issue_nwt'     =>$pocket_details['issue_nwt'][$key],
                                                'issue_purity'  =>$pocket_details['issue_purity'][$key],
                                            );
                                            $status=$this->$model->insertData($melting_details,'ret_old_metal_polishing_details');
                                            if($status)
                                            {
                                                 $pocket_update_data = array(
                                                                            'issue_pcs'         =>$pocket_details['issue_pcs'][$key],
                                                                            'issue_gwt'         =>$pocket_details['issue_gwt'][$key],
                                                                            'issue_nwt'         =>$pocket_details['issue_nwt'][$key],
                                                                            'issue_purity'      =>($pocket_details['issue_purity'][$key]*$pocket_details['issue_pcs'][$key]),
                                                                            'updated_on'        =>date("Y-m-d H:i:s"),
                                                                            'updated_by'        =>$this->session->userdata('uid'),
                                                                            );
                                                 $pocketStatus = $this->$model->updatePocketItem($pocket_details['id_metal_pocket'][$key],$pocket_update_data,'+');
                                                 /*if($pocketStatus)
                                                 {
                                                    $pocketDetails=$this->$model->getPocketingDetails($pocket_details['id_metal_pocket'][$key]); 
                                                    //UPDATEING PURCHASE ITEM LOG 
                                                    if(sizeof($pocketDetails)>0)
                                                    {
                                                        $branchDetails=$this->$model->get_branch_details();
                                                        foreach($pocketDetails as $items)
                                                        {
                                                                $old_metal_log=array(
                                                                'old_metal_sale_id'=>$items['old_metal_sale_id'],
                                                                'from_branch'      =>$branchDetails['id_branch'],
                                                                'to_branch'        =>NULL,
                                                                'status'           =>3,
                                                                'item_type'        =>1, // Old Metal
                                                                'date'             =>$branchDetails['entry_date'],
                                                                'created_on'       =>date("Y-m-d H:i:s"),
                                                                'created_by'      =>$this->session->userdata('uid'),
                                                                );
                                                                $this->$model->insertData($old_metal_log,'ret_purchase_items_log');
                                                        }
                                                    }
                                                    //UPDATEING PURCHASE ITEM LOG 
                                                 }*/
                                            }
                		 		        }
                		 		    }
                		 		    if($this->db->trans_status()===TRUE)
                		 		    {
                		 		        $this->db->trans_commit();
            							 $log_data = array(
                                        'id_log'        => $this->session->userdata('id_log'),
                                        'event_date'    => date("Y-m-d H:i:s"),
                                        'module'        => 'Melting',
                                        'operation'     => 'Add',
                                        'record'        =>  $insId,  
                                        'remark'        => 'Melting added successfully'
                                        );
                                        $this->log_model->log_detail('insert','',$log_data);
                                        $this->session->set_flashdata('chit_alert',array('message'=>'Process Given to Polishing successfully','class'=>'success','title'=>'Polishing'));
                                        $responseData=array('status'=>TRUE,'message'=>'Process Given to Polishing');
                		 		    }else{
                		 		        $this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Polishing'));
                		 		        $responseData=array('status'=>FALSE,'message'=>'Unable to proceed the requested process');
                		 		    }
                                }
                            }
                            if($addData['process_for']==2) // Receipt
                            {
                                $receipt_details=$_POST['polishing_receipt'];
                                if(!empty($receipt_details))
                                {
                                    $branchDetails=$this->$model->get_branch_details();
                                    $total_recd_gwt = 0;
                                    $total_recd_nwt = 0;
                                    $total_no_of_piece = 0;
                                    
                                    
                                    
                                    foreach($receipt_details['is_polishing_select'] as $key => $val)
                                    {
                                       if($receipt_details['is_polishing_select'][$key]==1)
                                       {
                                            $total_recd_gwt+=$receipt_details['recd_gwt'][$key];
                                            $total_recd_nwt+=$receipt_details['recd_nwt'][$key];
                                            $total_no_of_piece+=$receipt_details['recd_pcs'][$key];
                                            $updMeltingData=array(
                                                            'status'            =>1,
                                                            'received_pcs'       =>$receipt_details['recd_pcs'][$key],
                                                            'received_gwt'       =>$receipt_details['recd_gwt'][$key],
                                                            'received_nwt'       =>$receipt_details['recd_nwt'][$key],
                                                          );
                                            $polishing_receipt_status = $this->$model->updateData($updMeltingData,'id_polishing_details',$receipt_details['id_polishing_details'][$key],'ret_old_metal_polishing_details');
                                            $this->$model->updateData(array("id_old_metal_process_receipt"=>$insId,"updated_on"=>date("Y-m-d H:i:s"),"updated_by"=>$this->session->userdata('uid')),'id_polishing',$receipt_details['id_polishing'][$key],'ret_old_metal_polishing');

                                            $CategoryDetails=json_decode($receipt_details['category_details'][$key],true);
                                            
                                            if(!empty($CategoryDetails))
                                            {
                                                $lotDetails=array();
                                                foreach($CategoryDetails as $cat)
                                                {
                                                    $lotDetails [$cat['id_ret_category']][$cat['id_purity']][$cat['is_non_tag']][] = $cat;
                                                }
                                                //echo "<pre>";print_r(($lotDetails));exit;
                                                if(sizeof($lotDetails)>0)
                                                {
                                                    foreach($lotDetails as $k =>$lot)
                                                    {
                                                        foreach($lot as $cat_key => $category)
                                                        {
                                                            
                                                            foreach($category as $pur_key => $purity)
                                                            {
                                                                $Lotdata = array(
                                        							'lot_date'				=> date("Y-m-d H:i:s"),
                                        							'lot_from'				=> 5,
                                        							'lot_type'				=> 1,
                                        							'gold_smith'            => $addData['id_karigar'],
                                        							'lot_received_at'		=> $branchDetails['id_branch'],
                                        							'created_branch'		=> $id_branch,
                                        						    'id_category'			=> $k,
                                        							'id_purity'				=> $cat_key,
                                        							'narration'      		=> 'From Polishing Process',
                                        							'id_metal_process'      => $insId,
                                        							'created_on'	  		=> date("Y-m-d H:i:s"),
                                        							'created_by'      		=> $this->session->userdata('uid')
                                        						);
                                        						//echo "<pre>";print_r($Lotdata);exit;
                                        						$lotId = $this->$model->insertData($Lotdata,'ret_lot_inwards');
                                        						
                                        						foreach($purity as $product)
                                        						{
                                        						    if($product['is_non_tag']!=1)
                                        						    {
                                        						        $item_details=array('lot_no'=>$lotId,'lot_product'=>$product['id_product'],'no_of_piece'=>$product['recd_pcs'],'gross_wt'=>$product['recd_gross_wt'],'net_wt'=>$product['recd_nwt']); 
                    						                            $this->$model->insertData($item_details,'ret_lot_inwards_detail');
                                        						    }
                                        						    else
                                        						    {
                                        						        $this->$model->updateData(array("stock_type"=>2),'lot_no',$lotId,'ret_lot_inwards');
                                        						        
                                        						        $item_details=array('lot_no'=>$lotId,'lot_product'=>$product['id_product'],'no_of_piece'=>$product['recd_pcs'],'gross_wt'=>$product['recd_gross_wt'],'net_wt'=>$product['recd_nwt']); 
                                        						        
                                        						        $this->$model->insertData($item_details,'ret_lot_inwards_detail');
                                        						        
                                        						        $existData=array('id_product'=>$product['id_product'],'id_branch'=>$branchDetails['id_branch']);
                                        						        
                                        						        $isExist = $this->$model->checkNonTagItemExist($existData);
                                        						        
                                        						        if($isExist['status'] == TRUE)
                                        						        {
                                        						            $nt_data = array(
                                                                            'id_nontag_item'=>$isExist['id_nontag_item'],
                                                                            'gross_wt'		=> $product['recd_gross_wt'],
                                                                            'net_wt'		=> $product['recd_nwt'],  
                                                                            'no_of_piece'   => $product['recd_pcs'],
                                                                            'updated_by'	=> $this->session->userdata('uid'),
                                                                            'updated_on'	=> date('Y-m-d H:i:s'),
                                                                            );
                                                                            $this->$model->updateNTData($nt_data,'+');
                                                                													
                                                                            $non_tag_data=array(
                                                                            'product'	    => $product['id_product'],
                                                                            'gross_wt'		=> $product['recd_gross_wt'],
                                                                            'net_wt'		=> $product['recd_nwt'],
                                                                            'no_of_piece'   => $product['recd_pcs'],
                                                                            'from_branch'	=> NULL,
                                                                            'to_branch'	    => $branchDetails['id_branch'],
                                                                            'status'	    => 0,
                                                                            'date'          => $branchDetails['entry_date'],
                                                                            'created_on'    => date("Y-m-d H:i:s"),
                                                                            'created_by'    =>  $this->session->userdata('uid')
                                                                            );
                                                                            $this->$model->insertData($non_tag_data,'ret_nontag_item_log');
                                        						        }else
                                        						        {
                                        						            $nt_data=array(
                                                                                'branch'	    => $branchDetails['id_branch'],
                                                                                'product'	    => $product['id_product'],
                                                                                'gross_wt'		=> $product['recd_gross_wt'],
                                                                                'net_wt'		=> $product['recd_nwt'],
                                                                                'no_of_piece'   => $product['recd_pcs'],
                                                                                'created_on'    => date("Y-m-d H:i:s"),
                                                                                'created_by'    => $this->session->userdata('uid')
                                                                            );
                                                                            $this->$model->insertData($nt_data,'ret_nontag_item'); 
                                                                            
                                                                            $non_tag_data=array(
                                                                            'product'	    => $product['id_product'],
                                                                            'gross_wt'		=> $product['recd_gross_wt'],
                                                                            'net_wt'		=> $product['recd_nwt'],
                                                                            'no_of_piece'   => $product['recd_pcs'],
                                                                            'from_branch'	=> NULL,
                                                                            'to_branch'	    => $branchDetails['id_branch'],
                                                                            'status'	    => 0,
                                                                            'date'          => $branchDetails['entry_date'],
                                                                            'created_on'    => date("Y-m-d H:i:s"),
                                                                            'created_by'    =>  $this->session->userdata('uid')
                                                                            );
                                                                            $this->$model->insertData($non_tag_data,'ret_nontag_item_log'); 
                                        						        }
                                        						    }
                                        						}
                                        						
                                        						
                                        						
                                                            }
                                                        }
                                                    }
                                                }
                                                
                                                //echo "<pre>";print_r($lotDetails);exit;
                                            }
                                      }
                                    }
                                    if($this->db->trans_status()===TRUE)
                		 		    {
                		 		        $this->db->trans_commit();
            							 $log_data = array(
                                        'id_log'        => $this->session->userdata('id_log'),
                                        'event_date'    => date("Y-m-d H:i:s"),
                                        'module'        => 'Polishing',
                                        'operation'     => 'Add',
                                        'record'        =>  $insId,  
                                        'remark'        => 'Polishing Receipt successfully'
                                        );
                                        $this->log_model->log_detail('insert','',$log_data);
                                        $this->session->set_flashdata('chit_alert',array('message'=>'Polishing Receipt','class'=>'success','title'=>'Polishing'));
                                        $responseData=array('status'=>TRUE,'message'=>'Polishing Receipt');
                		 		    }else{
                		 		        $this->session->set_flashdata('chit_alert',array('message'=>'Unable to proceed the requested process','class'=>'danger','title'=>'Polishing'));
                		 		        $responseData=array('status'=>FALSE,'message'=>'Unable to proceed the requested process');
                		 		    }
                                }
                                //echo "<pre>";print_r($receipt_details);exit;
                            }
                    }
                    if(!empty($receipt_payment))
		 		    {
		 		        if($receipt_payment['cash_amount']>0)
		 		        {
                            $payData=array(
                                'id_old_metal_process'=>$insId,
                                'type'                =>1,
                                'payment_mode'        =>'Cash',
                                'payment_amount'      =>$receipt_payment['cash_amount'],
                                'payment_date'        =>date("Y-m-d H:i:s"),
                            );
                            $this->$model->insertData($payData,'ret_old_metal_process_payment');
		 		        }
		 		        if($receipt_payment['net_banking_amount']>0)
		 		        {
                            $payData=array(
                                'id_old_metal_process'=>$insId,
                                'type'                =>1,
                                'payment_mode'        =>'NB',
                                'payment_ref_number'  =>($receipt_payment['net_banking_ref_no']!='' ? $receipt_payment['net_banking_ref_no'] :NULL),
                                'payment_amount'      =>$receipt_payment['cash_amount'],
                                'payment_date'        =>date("Y-m-d H:i:s"),
                            );
                            $this->$model->insertData($payData,'ret_old_metal_process_payment');
		 		        }
		 		        
		 		    }
                		 		    
                    echo json_encode($responseData); 
                }
            
            break;
		
			case 'ajax': 
					$list=$this->$model->ajax_get_metal_process($_POST); 
				  	$access = $this->admin_settings_model->get_access('admin_ret_reports/old_metal_purchase/list');
			        $data = array(
		        					'list'  => $list,
									'access'=> $access
		        				 );  
					echo json_encode($data);
				break;
		}
    }
    
    function process_acknowladgement($id)
	{
		$model=	self::RET_PROCESS_MODEL;
		$data['process'] = $this->$model->get_metal_process($id);
		if($data['process']['id_metal_process']==1)
		{
		    if($data['process']['process_for']==1)
		    {
		        $data['melting_details'] = $this->$model->get_melting_issue_details($id);
		    }else
		    {
		        $data['melting_details'] = $this->$model->get_melting_receipt_details($id);
		    }
		    
		}
		
		if($data['process']['id_metal_process']==2)
		{
		    if($data['process']['process_for']==1)
		    {
		        $data['melting_details'] = $this->$model->get_testing_issue_details($id);
		    }
		    else
		    {
		        $data['melting_details'] = $this->$model->get_TestingReceiptAcknowladgement($id);
		    }
		}
		
		if($data['process']['id_metal_process']==3)
		{
		    if($data['process']['process_for']==1)
		    {
		        $data['melting_details'] = $this->$model->get_RefiningIssueAcknowladgement($id);
		    }
		    else
		    {
		        $data['melting_details'] = $this->$model->get_refiningReceiptAcknowladgement($id);
		    }
		}
		
		if($data['process']['id_metal_process']==4)
		{
		    if($data['process']['process_for']==1)
		    {
		        $data['polishing_details'] = $this->$model->get_PolishingIssueAcknowladgement($id);
		    }
		    else
		    {
		        $data['polishing_details'] = $this->$model->get_refiningReceiptAcknowladgement($id);
		    }
		}
		
		$data['process_payment'] = $this->$model->get_old_metal_process_payment($id);
		//echo "<pre>";print_r($data);exit;
		$this->load->helper(array('dompdf', 'file'));
        $dompdf = new DOMPDF();
		$html = $this->load->view('ret_metal_process/metal_process/process_acknowladgement', $data,true);
	    $dompdf->load_html($html);
		$dompdf->set_paper("a4", "portriat" );
		$dompdf->render();
		$dompdf->stream("Receipt.pdf",array('Attachment'=>0));
	}
    
    function metal_process_receipt($type="")
	{   
       
		$model=	self::RET_PROCESS_MODEL;
		switch($type)
		{
			case 'list': 
					$data['main_content'] = self::VIEW_FOLDER.'receipt/list';
        			$this->load->view('layout/template', $data);
			break;
            case 'add': 
                $data['main_content'] = self::VIEW_FOLDER.'receipt/form';
                $this->load->view('layout/template', $data);
            break;
            case 'metal_list': 
                $data=$this->$model->get_metal_stock_list($_POST);
                echo json_encode($data);
            break;
		
			case 'ajax': 
					$list=$this->$model->get_pocket_list($_POST); 
				  	$access = $this->admin_settings_model->get_access('admin_ret_reports/old_metal_purchase/list');
			        $data = array(
		        					'list'  => $list,
									'access'=> $access
		        				 );  
					echo json_encode($data);
				break;
		}
    }
    
    function get_pocket_details()
    {
        $model=	self::RET_PROCESS_MODEL;
        $data=$this->$model->get_pocket_details();
        echo json_encode($data);
    }
    
    function get_polish_pocket_details()
    {
        $model=	self::RET_PROCESS_MODEL;
        $data=$this->$model->get_polish_pocket();
        echo json_encode($data);
    }
    
    
    
    //melting receipt
    function get_KarigarMeltingIssueDetilas()
    {
        $model=	self::RET_PROCESS_MODEL;
        $data=$this->$model->get_KarigarMeltingIssueDetilas($_POST);
        echo json_encode($data);
    }
    //melting receipt
    
    
    //Testing Issue
    function get_testing_issue_details()
    {
        $model=	self::RET_PROCESS_MODEL;
        $data=$this->$model->get_melting_details($_POST);
        echo json_encode($data);
    }
    
    function get_testing_receipt_details()
    {
        $model=	self::RET_PROCESS_MODEL;
        $data=$this->$model->get_testing_receipt_details($_POST);
        echo json_encode($data);
    }
    //Testing Issue
    
    
    
    //Refining Issue
    function get_RefiningIssueDetails()
    {
        $model=	self::RET_PROCESS_MODEL;
        $data=$this->$model->get_RefiningIssueDetails();
        echo json_encode($data);
    }
    
    function get_RefiningReceiptDetails()
    {
        $model=	self::RET_PROCESS_MODEL;
        $data=$this->$model->get_RefiningReceiptDetails($_POST);
        echo json_encode($data);
    }
    
    //Refining Issue
    
    
    //Polishing
    function get_PolishingReceiptDetails()
    {
        $model=	self::RET_PROCESS_MODEL;
        $data=$this->$model->get_PolishingReceiptDetails($_POST);
        echo json_encode($data);
    }
    //Polishing
    
    
    function get_ActiveCategoryPurity()
    {
        $model=	self::RET_PROCESS_MODEL;
        $data=$this->$model->get_ActiveCategoryPurity();
        echo json_encode($data);
    }
    
    
    
    function process_report($type="",$id="")
	{   
       
		$model=	self::RET_PROCESS_MODEL;
		switch($type)
		{
			case 'list': 
					$data['main_content'] = self::VIEW_FOLDER.'reports/process_report';
        			$this->load->view('layout/template', $data);
			break;
			
			case 'detailed_report': 
					$data['main_content'] = self::VIEW_FOLDER.'reports/detailed_report';
        			$this->load->view('layout/template', $data);
			break;
			
			case 'detail_porcess_report': 
					$data=$this->$model->get_detail_porcess_report($_POST); 
					echo json_encode($data);
			break;
			
			case 'process_details': 
					$data=$this->$model->get_process_pocket_details($_POST); 
					echo json_encode($data);
			break;
         
			case 'ajax': 
					$list=$this->$model->get_metal_process_reports($_POST); 
				  	$access = $this->admin_settings_model->get_access('admin_ret_metal_process/process_report/list');
			        $data = array(
		        					'list'  => $list,
									'access'=> $access
		        				 );  
					echo json_encode($data);
				break;
		}
	    
    }
    
    
    

}
?>
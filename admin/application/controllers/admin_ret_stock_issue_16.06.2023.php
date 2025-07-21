<?php
if( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH.'libraries/dompdf/autoload.inc.php');
use Dompdf\Dompdf;
class Admin_ret_stock_issue extends CI_Controller
{ 
	const IMG_PATH  = 'assets/img/';
	function __construct()
	{
		parent::__construct();
		ini_set('date.timezone', 'Asia/Calcutta');
		$this->load->model('ret_stock_issue_model');
		$this->load->model('admin_settings_model');
			$this->load->model("admin_usersms_model");
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
	* Order Functions Starts
	*/
    
    
    function shortenurl($url)
	{
		$ch = curl_init();  
		$timeout = 5;  
		curl_setopt($ch,CURLOPT_URL,'https://tinyurl.com/api-create.php?url='.$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);  
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);  
		$data = curl_exec($ch);  
		curl_close($ch);  
		return $data;  
	}
	
	public function isValueset($field)
	{
		$data=($field ? $field:'-');
		return $data;
	}

	
	//STOCK ISSUE
	function stock_issue($type="",$id="")
	{
	    $model = "ret_stock_issue_model";
		switch($type){
		    
		    case 'list':
					$data['main_content'] = "ret_stock_issue/list" ;
					$this->load->view('layout/template', $data);
		    break;
		    
		    case 'add':
					$data['main_content'] = "ret_stock_issue/form" ;
					$this->load->view('layout/template', $data);
		    break;
		    
			case 'save':
			    
			    $form_secret    =$_POST['form_secret'];
				$addData        =$_POST['order'];
				$tag_details    =$_POST['tag_id'];
				$rate_per_gram  = $_POST['rate_per_gram'];
				$return_data    =array();
				$allow_submit   = false;
				// echo "<pre>";print_r($_POST);exit;
				if($this->session->userdata('FORM_SECRET'))
				{
				    if(strcasecmp($form_secret, ($this->session->userdata('FORM_SECRET'))) === 0)
				    {
				        $allow_submit = TRUE;
				    }
				}
				if($allow_submit)
				{
				    $dCData     = $this->admin_settings_model->getBranchDayClosingData($addData['order_from']);
				    $issue_date = ($dCData['entry_date'] == date("Y-m-d") ? date("Y-m-d H:i:s") : $dCData['entry_date']);
				
        			if($addData['issue_receipt_type']==1) // ISSUE
        			{
        
        				$issue_no       = $this->$model->generateIssueNo();
        				//echo "<pre>";print_r($tag_details);exit;
        				    					
        				$insData=array(
                            'issue_no'   => $issue_no,
                            'issue_date' => $issue_date,
                            'status'     => 1,
                            'id_branch'  => $addData['order_from'],
                            'issue_type' => $addData['issue_type'],
                            'issued_to'  => $addData['id_employee'],
							'id_customer' => $addData['cus_id'],
                            'remarks'    => ($addData['remark']!='' ? $addData['remark'] : NULL),
                            'form_secret'=> $form_secret,
                            'created_on' => date("Y-m-d H:i:s"),
                            'created_by' => $this->session->userdata('uid'),
                        );
        			    $this->db->trans_begin();
        				$insId = $this->$model->insertData($insData,'ret_stock_issue');
        				// print_r($this->db->last_query());
        				if($insId)
        				{
        				    $issue_type_det=$this->$model->stock_issue_type_detail($addData['issue_type']);
        				        foreach($tag_details as $tag_id)
        				        {
        				            $tagDetails = $this->$model->getTagDetails($tag_id);
        				                $issueDetail=array(
                                            'id_stock_issue' =>$insId,
                                            'tag_id'         =>$tag_id,
											'rate_per_gram'  =>$rate_per_gram
                                            );
                                        $IssueTagstatus=$this->$model->insertData($issueDetail,'ret_stock_issue_detail');
										// print_r($this->db->last_query());exit;
                                        if($IssueTagstatus)	
                                        {
                                            $this->$model->updateData(array('tag_status'=>7),'tag_id',$tag_id,'ret_taging');
                                            //Update Tag Log status
                                            if($issue_type_det['is_remove_from_stock']==1)
                                            {
                                                    $tag_log=array(
                                                    'tag_id'	  =>$tag_id,
                                                    'date'		  =>$issue_date,
                                                    'status'	  =>7,
                                                    'from_branch' =>$addData['order_from'],
                                                    'to_branch'	  =>NULL,
                                                    'form_secret' =>$form_secret.'_'.$tag_id,
                                                    'created_on'  =>date("Y-m-d H:i:s"),
                                                    'created_by'  =>$this->session->userdata('uid'),
                                                    );
                                                    $this->$model->insertData($tag_log,'ret_taging_status_log');
                                                    if($tagDetails['id_section']!='')
                                                    {
                                                        $Secttag_log=array(
                                                    	'tag_id'	  =>$tag_id,
                                                    	'date'		  =>$issue_date,
                                                    	'form_secret' =>$form_secret.'_'.$tag_id,
                                                    	'status'	  =>7,
                                                    	'from_branch' =>$addData['order_from'],
                                                    	'to_branch'	  =>NULL,
                                                    	'from_section'=>NULL,
                                                    	'to_section'  =>$tagDetails['id_section'],
                                                    	'created_on'  =>date("Y-m-d H:i:s"),
                                                    	'created_by'  =>$this->session->userdata('uid'),
                                                    	);
                                                    	$this->$model->insertData($Secttag_log,'ret_section_tag_status_log');
                                                    }
                                            }
        									
                                        }
        				        }
        				}
        				if($this->db->trans_status()===TRUE)
        				{
        					$this->db->trans_commit();
        					$return_data=array('status'=>TRUE,'message'=>'Stock Issued successfully..','id_stock_issue'=>$insId);
        				}
        				else
        				{ 
        			
        				    echo $this->db->last_query();exit;
        					$this->db->trans_rollback();						 	
        					$return_data=array('status'=>FALSE,'message'=>'Unable to proceed the requested process');
        				}
        		    }
        		    else if($addData['issue_receipt_type']==2)
        		    {
        		        $issue_details=$this->$model->get_IssueItems($addData['issue_id']);
        		        $issue_type_det=$this->$model->stock_issue_type_detail($issue_details['issue_type']);
        	    
        		        foreach($tag_details as $tag_id)
        		        {
        		            if($tag_id!='')
        		            {
            		            $this->db->trans_begin();
            		            $this->$model->updateData(array('tag_status'=>0),'tag_id',$tag_id,'ret_taging');
            		            $this->$model->updateData(array('status'=>3,'received_date'=>date("Y-m-d H:i:s"),'updated_by'=>$this->session->userdata('uid')),'tag_id',$tag_id,'ret_stock_issue_detail');
                                //Update Tag Log status
                                if($issue_type_det['is_remove_from_stock']==1)
                                {
                                    $tagDetails = $this->$model->getTagDetails($tag_id);
                                    $tag_log=array(
                                    'tag_id'	  =>$tag_id,
                                    'date'		  =>$issue_date,
                                    'form_secret' => $form_secret.'_'.$tag_id,
                                    'status'	  =>0,
                                    'to_branch'   =>$issue_details['id_branch'],
                                    'created_on'  =>date("Y-m-d H:i:s"),
                                    'created_by'  =>$this->session->userdata('uid'),
                                    );
                                    $this->$model->insertData($tag_log,'ret_taging_status_log');
                                    if($tagDetails['id_section']!='')
                                    {
                                        $Secttag_log=array(
                                    	'tag_id'	  =>$tag_id,
                                    	'date'		  =>$issue_date,
                                    	'form_secret' => $form_secret.'_'.$tag_id,
                                    	'status'	  =>0,
                                    	'to_branch'   =>$issue_details['id_branch'],
                                    	'to_section'  =>$tagDetails['id_section'],
                                    	'created_on'  =>date("Y-m-d H:i:s"),
                                    	'created_by'  =>$this->session->userdata('uid'),
                                    	);
                                    	$this->$model->insertData($Secttag_log,'ret_section_tag_status_log');
                                    }
                                }
        		            }
        		        }
        		        if($this->db->trans_status()===TRUE)
        				{
        					$this->db->trans_commit();
        					$return_data=array('status'=>TRUE,'message'=>'Stock Receipt Added successfully..');
        				}
        				else
        				{ 
        			
        				    echo $this->db->last_query();exit;
        					$this->db->trans_rollback();						 	
        					$return_data=array('status'=>FALSE,'message'=>'Unable to proceed the requested process');
        				}
        		    }
		    }
		    else
		    {
		        $return_data=array('status'=>FALSE,'message'=>'Invalid Form Submit..');
		    }
			echo json_encode($return_data);
					
			break;
			
			case 'issue_print':

        		$data['issue'] = $this->$model->get_IssueItems($id);
        		$data['item_details']=$this->$model->get_issue_item_details($id,$data['issue']['issue_type'],$data['issue']['repair_type']);
				// $data['rate_details'] = $this->$model->get_rate_details($data['issue']['issue_date']);
				$data['comp_details']=$this->admin_settings_model->getCompanyDetails("");
				//  echo "<pre>";print_r($data);exit;
        		// $this->load->helper(array('dompdf','file'));
                // $dompdf = new DOMPDF();
        		 $html = $this->load->view('ret_stock_issue/issue_ack', $data,true);
				 print_r($html);exit;
        	    //$dompdf->load_html($html);
        		// $dompdf->set_paper("a4", "portriat");
        		// $dompdf->render();
        		// $dompdf->stream("Receipt.pdf",array('Attachment'=>0));
			break;
			
			default: 
    			$list = $this->$model->ajax_getStockIssueList();	 
    		  	$access = $this->admin_settings_model->get_access('admin_ret_stock_issue/stock_issue/list');
    	        $data = array(
                            'list'   => $list,
                            'access' => $access
	        			 );  
			echo json_encode($data);
						
		} 
	  	
	}
	
	public function get_tag_scan_details(){
		$model = "ret_stock_issue_model";
		$data = $this->$model->get_tag_scan_details($_POST);	  
		echo json_encode($data);
	}
	
	
	public function get_receipt_tag_scan_details(){
		$model = "ret_stock_issue_model";
		$data = $this->$model->get_receipt_tag_scan_details($_POST);	  
		echo json_encode($data);
	}
	
	
	function get_stock_issue_type()
	{
	    $model = "ret_stock_issue_model";
		$data = $this->$model->get_stock_issue_type();	  
		echo json_encode($data);
	}
	
	
	function get_StockIssuedItems()
	{
	    $model = "ret_stock_issue_model";
		$data = $this->$model->get_StockIssuedItems();	  
		echo json_encode($data);
	}
	
	
	//STOCK ISSUE

}	
?>
<?php
if( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin_scheme extends CI_Controller
{
	const SCH_MODEL	="scheme_model";
	const SCH_VIEW ="master/scheme/";
	const SET_MODEL = "admin_settings_model";
	const PAY_MODEL = "payment_model";
	const LOG_MODEL = "log_model";
	function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('is_logged'))
		{
		    redirect('admin/login');
	   	}  
		ini_set('date.timezone', 'Asia/Calcutta');
		$this->load->model(self::SCH_MODEL);
		$this->load->model(self::SET_MODEL);
		$this->load->model(self::PAY_MODEL);
		$this->load->model(self::LOG_MODEL);
		$this->id_log =  $this->session->userdata('id_log');
	}
	public function index()
	{
		$this->sch_list();
	}
	 public function ajax_get_schemes_list()
	{
		$set_model= self::SET_MODEL;
		$access= $this->$set_model->get_access('scheme');
		$model=	self::SCH_MODEL;
		$items=$this->$model->get_all_schemes();	
		$scheme = array(
							'access' => $access,
							'data'   => $items
						);  
		echo json_encode($scheme);
	}  
	public function ajax_fixweight_schemes()
	{
		$model=	self::SCH_MODEL;
		$scheme=$this->$model->get_fixweight_schemes();
		echo json_encode($scheme);
	}   
	/* public function ajax_fixweight_schemes()
	{
		$model=	self::SCH_MODEL;
		$scheme=$this->$model->gst_settings();
		echo json_encode($scheme);
	}    */
	public function sch_list($msg="")
	{
		$model_name=self::SCH_MODEL;
		$data['message']=$msg;
		$sch_data=$this->$model_name->get_all_schemes();
		if($sch_data)
		{
			$data['schemes']=$sch_data;
		}
		$data['main_content'] = self::SCH_VIEW.'list';
	    $this->load->view('layout/template', $data);
	}
	/* public function sch_form($type="",$id="")
	{
		$model= self::SCH_MODEL;
		$pay_model = self::PAY_MODEL;
		switch($type)
		{
			case 'Add':
						$set_model=self::SET_MODEL;
						$limit= $this->$set_model->limitDB('get','1');
						$discount= $this->$set_model->discount_db('get','1');
						$count= $this->$model->scheme_count();
						$gst_data=$this->$set_model->get_gstsettings();
						//print_r($count);exit;
						if($limit['limit_sch']==1)
						{
							if($count < $limit['sch_max_count'])
							{
								 	$data['sch'] = $this->$model->empty_record();
								 	$data['discount'] = $discount;
								 	$data['gst'] = $gst_data;
				   					$data['main_content'] = self::SCH_VIEW."form" ;
				   					$this->load->view('layout/template', $data);
							}else
							{
						 	$this->session->set_flashdata('sch_info', array('message' => 'Scheme creation limit exceeded, Kindly contact Super Admin...','class' => 'danger','title'=>'Scheme creation'));
						 	redirect('scheme');
							}
						}else
						{
								 	$data['sch']=$this->$model->empty_record();
								 	$data['discount'] = $discount;
								 	$data['gst'] = $gst_data;
				   					$data['main_content'] = self::SCH_VIEW."form" ;
				   					$this->load->view('layout/template', $data);
						}
				break;
			case 'Edit':
					    $set_model=self::SET_MODEL;					
					    $datas        =  $this->$model->get_scheme1($id);						
						$gst_data=$this->$set_model->get_gstsettings();
						$branch=implode(',',$datas);
					    $data['sch']     =  $this->$model->get_scheme($id);
						$data['sch']['id_branch']=$branch;
						$data['gst'] = $gst_data;
						$data['gst_data']=$this->$model->get_gstSplitupData($id);
							$data['discount']= $this->admin_settings_model->discount_db('get','1');	
							$data['main_content'] = self::SCH_VIEW."form" ;
							//
							$this->load->view('layout/template', $data);
						//}
				break;
		}
	}
	 */
	public function sch_form($type="",$id="")
	{
		$model= self::SCH_MODEL;
		$pay_model = self::PAY_MODEL;
		switch($type)
		{
			case 'Add':
/*-- Coded by ARVK --*/				
						$set_model=self::SET_MODEL;
						$limit= $this->$set_model->limitDB('get','1');
						$discount= $this->$set_model->discount_db('get','1');
						$count= $this->$model->scheme_count();
						$gst_data=$this->$set_model->get_gstsettings();
						//print_r($count);exit;
						if($limit['limit_sch']==1)
						{
							if($count < $limit['sch_max_count'])
							{
								 	$data['sch'] = $this->$model->empty_record();
								 	$data['discount'] = $discount;
									$data['gst'] = $gst_data;
				   					$data['main_content'] = self::SCH_VIEW."form" ;
				   					$this->load->view('layout/template', $data);
							}else
							{
						 	$this->session->set_flashdata('sch_info', array('message' => 'Scheme creation limit exceeded, Kindly contact Super Admin...','class' => 'danger','title'=>'Scheme creation'));
						 	redirect('scheme');
							}
						}else
						{
								 	$data['sch']=$this->$model->empty_record();
								 	$data['discount'] = $discount;
									$data['gst'] = $gst_data;
				   					$data['main_content'] = self::SCH_VIEW."form" ;
				   					$this->load->view('layout/template', $data);
						}
/*--/ Coded by ARVK --*/
				break;
				case 'Edit':
							$set_model=self::SET_MODEL;
							$gst_data=$this->$set_model->get_gstsettings();
							$data['sch']=$this->$model->get_scheme($id);
							
							$data['chartData'] = $this->$model->get_benfit_rdeduct_data($id);
							$data['preclosechartdata'] = $this->$model->get_benfit_rdeduct_preclose__data($id);
							
							//echo "<pre>";print_r($data['chartData']);echo "</pre>";exit;
							$data['gst_data']=$this->$model->get_gstSplitupData($id);
							//$data['branch_data']=$this->$model->get_branch_data($id);
							$data['branch_data'] = json_encode($this->scheme_model->get_branch_edit($id));
								$data['gst'] = $gst_data;							
							$data['discount']= $this->admin_settings_model->discount_db('get','1');
							//echo "<pre>";print_r($data);echo "</pre>";exit;
							$data['main_content'] = self::SCH_VIEW."form" ;
						    $data['service_list'] = array(
                            array(
                            'value'     	=> 'time',
                            'text'			=> 'time'
                            ),array(
                            'value'     	=> 'rate',
                            'text'			=> 'rate'
                            )
                            );
							$this->load->view('layout/template', $data);
				break;
		}
	}
	public function sch_post($type="",$id="")
	{
	    
	    /* echo "<pre>";
        print_r($_POST);
         echo "</pre>";
		 die;*/
	    
		$model= self::SCH_MODEL;
		$log_model	 = self::LOG_MODEL;
		switch($type)
		{
			case 'Add':
			          $sch_data=$this->input->post("sch");
			          $gst_data=$this->input->post("gst_data");
			          $branch_data=$this->input->post("branch_data");				  
					  //echo"<pre>";  print_r($sch_data);exit;
			         $sch_info=array(	
								'min_amount'			=> number_format((!empty($sch_data['min_amount'])?$sch_data['min_amount']:0),2,".",""),
								'max_amount'			=> number_format((!empty($sch_data['max_amount'])?$sch_data['max_amount']:0),2,".",""),
								'max_amt_chance'			=> number_format((!empty($sch_data['max_amt_chance'])?$sch_data['max_amt_chance']:0),2,".",""),
								'min_amt_chance'			=> number_format((!empty($sch_data['min_amt_chance'])?$sch_data['min_amt_chance']:0),2,".",""),
								'pay_duration'				=>(!empty($sch_data['pay_duration'])?$sch_data['pay_duration']:0),
								'wgt_convert'					=>(!empty($sch_data['wgt_convert'])?$sch_data['wgt_convert']:2),
								'noti_msg'			=>(!empty($sch_data['noti_msg'])?$sch_data['noti_msg']:NULL),
			   					'scheme_name'			=>(!empty($sch_data['scheme_name'])?$sch_data['scheme_name']:NULL),
								'code'					=>(!empty($sch_data['code'])?$sch_data['code']:NULL),
								'sync_scheme_code'		=>(!empty($sch_data['sync_scheme_code'])?$sch_data['sync_scheme_code']:NULL),
								'id_metal'				=>(!empty($sch_data['id_metal'])?$sch_data['id_metal']:0),
								'id_classification'		=>(!empty($sch_data['id_classification'])?$sch_data['id_classification']:0),
								'scheme_type'			=>(!empty($sch_data['scheme_type'])?$sch_data['scheme_type']:0),
								'amount'				=> number_format((isset($sch_data['amount'])?$sch_data['amount']:0),2,".",""),
								'total_installments'	=>(!empty($sch_data['total_installments'])?$sch_data['total_installments']:0),
								'maturity_installment'	=>(!empty($sch_data['maturity_installment'])?$sch_data['maturity_installment']:NULL),
								'maturity_type'	        =>(!empty($sch_data['maturity_type'])?$sch_data['maturity_type']:1),
								'payment_chances'		=>(!empty($sch_data['payment_chances'])?$sch_data['payment_chances']:0),
								//'min_chance'			=>(isset($sch_data['min_chance'])?$sch_data['min_chance']:0),
								//'max_chance'			=>(isset($sch_data['max_chance'])?$sch_data['max_chance']:0),
								'sch_limit_value'	=>(!empty($sch_data['sch_limit_value'])?$sch_data['sch_limit_value']:NULL),
								'min_chance'	=>($sch_data['payment_chances']==0?1:(isset($sch_data['min_chance'])?$sch_data['min_chance']:0)),
								'max_chance'	=>($sch_data['payment_chances']==0?1:(isset($sch_data['max_chance'])?$sch_data['max_chance']:0)),
								'min_weight'			=> number_format((!empty($sch_data['min_weight'])?$sch_data['min_weight']:0),3,".",""),
								'max_weight'			=> number_format((!empty($sch_data['max_weight'])?$sch_data['max_weight']:0),3,".",""),
								'allow_unpaid'			=>(!empty($sch_data['allow_unpaid'])?$sch_data['allow_unpaid']:0),
								'unpaid_months'			=>(!empty($sch_data['unpaid_months'])?$sch_data['unpaid_months']:0),
								'unpaid_weight_limit'	=>(!empty($sch_data['unpaid_weight_limit'])?$sch_data['unpaid_weight_limit']:0),
								'allow_advance'			=>(!empty($sch_data['allow_advance'])?$sch_data['allow_advance']:0),
								'advance_months'		=>(!empty($sch_data['advance_months'])?$sch_data['advance_months']:0),
								'advance_weight_limit'	=>(!empty($sch_data['advance_weight_limit'])?$sch_data['advance_weight_limit']:0),
								'allow_preclose'	=>(!empty($sch_data['allow_preclose'])?$sch_data['allow_preclose']:0),
								'preclose_months'	=>(!empty($sch_data['preclose_months'])?$sch_data['preclose_months']:0),
								'preclose_benefits'	=>(!empty($sch_data['preclose_benefits'])?$sch_data['preclose_benefits']:0),
								'interest'				=>(!empty($sch_data['interest'])?$sch_data['interest']:0),	
								'interest_by'			=>(!empty($sch_data['interest_by'])?$sch_data['interest_by']:0),
								'interest_value'		=>number_format((!empty($sch_data['interest_value'])?$sch_data['interest_value']:0),2,".",""),
								'total_interest'		=>number_format((!empty($sch_data['total_interest'])?$sch_data['total_interest']:0),2,".",""),
								'tax'					=>(!empty($sch_data['tax'])?$sch_data['tax']:0),
								'tax_by'				=>(!empty($sch_data['tax_by'])?$sch_data['tax_by']:0),
								'tax_value'				=>number_format((!empty($sch_data['tax_value'])?$sch_data['tax_value']:0),2,".",""),	
								'total_tax'				=>number_format((!empty($sch_data['total_tax'])?$sch_data['total_tax']:0),2,".",""),	
								'description'			=>(!empty($sch_data['description'])?$sch_data['description']:NULL),
								'is_pan_required'			=>(!empty($sch_data['is_pan_required'])?$sch_data['is_pan_required']:0),
								'pan_req_amt'		    =>(!empty($sch_data['pan_req_amt'])?$sch_data['pan_req_amt']:NULL),
								/*'fix_weight'			=>(isset($sch_data['fix_weight'])?$sch_data['fix_weight']:0),*/
								'setlmnt_type'			=>(!empty($sch_data['type'])?$sch_data['type']:3),
								'setlmnt_adjust_by'		=>(!empty($sch_data['adjust_by'])?$sch_data['adjust_by']:2),
								'free_payment'		=>(!empty($sch_data['free_payment'])?$sch_data['free_payment']:0),
								'allowSecondPay'		=>(!empty($sch_data['allowSecondPay'])?$sch_data['allowSecondPay']:0),
								'approvalReqForFP'		=>(!empty($sch_data['approvalReqForFP'])?$sch_data['approvalReqForFP']:0),
								'free_payInstallments'	=>(!empty($sch_data['free_payInstallments'])?$sch_data['free_payInstallments']:NULL),
								'has_free_ins'	=>(!empty($sch_data['has_free_ins'])?$sch_data['has_free_ins']:0),
								'active'				=>(!empty($sch_data['active'])?$sch_data['active']:0),
								'visible'				=>(!empty($sch_data['visible'])?$sch_data['visible']:0),
								'disable_sch_payment'	 => (!empty($sch_data['disable_sch_payment'])?$sch_data['disable_sch_payment']:0),
								'stop_payment_installment'=>(!empty($sch_data['stop_payment_installment'])?($sch_data['stop_payment_installment']!='' ? $sch_data['stop_payment_installment']:0):0),
								'firstPayDisc_value'	=> number_format((!empty($sch_data['firstPayDisc_value'])?$sch_data['firstPayDisc_value']:0),2,".",""),	
								'firstPayDisc'			=>(!empty($sch_data['firstPayDisc'])?$sch_data['firstPayDisc']:0),
								'firstPayDisc_by'		=>(!empty($sch_data['firstPayDisc_by'])?$sch_data['firstPayDisc_by']:0),
								'all_pay_disc'			=>(!empty($sch_data['all_pay_disc'])?$sch_data['all_pay_disc']:0),
								'allpay_disc_value'	=> number_format((!empty($sch_data['allpay_disc_value'])?$sch_data['allpay_disc_value']:0),2,".",""),
								'allpay_disc_by'		=>(!empty($sch_data['allpay_disc_by'])?$sch_data['allpay_disc_by']:0),
								'charge_head'			=>(!empty($sch_data['charge_head'])?$sch_data['charge_head']:NULL),
								'charge_type'			=>(!empty($sch_data['charge_type'])?$sch_data['charge_type']:0),
								'charge'				=>(!empty($sch_data['charge'])?$sch_data['charge']:0.00),
								'gst_type'				=>(!empty($sch_data['gst_type'])?$sch_data['gst_type']:0),
								'hsn_code'				=>(!empty($sch_data['hsn_code'])?$sch_data['hsn_code']:0),
								//'gst'					=>(isset($sch_data['gst'])?$sch_data['gst']:0),
								'Emp_ref_values'				=>(!empty($sch_data['Emp_ref_values'])?$sch_data['Emp_ref_values']:0),
								'cus_ref_values'		=>(!empty($sch_data['cus_ref_values'])?$sch_data['cus_ref_values']:0),	
								'cus_refferal_value'	=>(!empty($sch_data['cus_refferal']) ? (($sch_data['cus_refferal_by']==0 && ($sch_data['scheme_type']==0 || $sch_data['scheme_type']==2))? $sch_data['amount']* $sch_data['cus_ref_values']/100:$sch_data['cus_ref_values']):0),
								'emp_refferal_value'	=>(!empty($sch_data['emp_refferal']) ?(($sch_data['emp_refferal_by']==0 && ($sch_data['scheme_type']==0 || $sch_data['scheme_type']==2))?$sch_data['amount']* $sch_data['Emp_ref_values']/100:$sch_data['Emp_ref_values']) :0),
								'cus_refferal_by'		=>(!empty($sch_data['cus_refferal_by'])?$sch_data['cus_refferal_by']:0),
								'emp_refferal_by'		=>(!empty($sch_data['emp_refferal_by'])?$sch_data['emp_refferal_by']:0),
								'cus_refferal'			=>(!empty($sch_data['cus_refferal'])?$sch_data['cus_refferal']:0),
								'emp_refferal'			=>(!empty($sch_data['emp_refferal'])?$sch_data['emp_refferal']:0),
								'date_upd'				=> date("Y-m-d H:i:s"),
								'ref_benifitadd_ins_type'	  =>(!empty($sch_data['ref_benifitadd_ins_type'])?$sch_data['ref_benifitadd_ins_type']:0),
								'ref_benifitadd_ins'		  =>(!empty($sch_data['ref_benifitadd_ins'])?$sch_data['ref_benifitadd_ins']:0),
								'discount_type'	        =>(!empty($sch_data['discount_type'])?$sch_data['discount_type']:0),
								'discount_installment'	=>(!empty($sch_data['discount_installment'])?$sch_data['discount_installment']:0),
								'discount'		         =>(!empty($sch_data['discount'])?$sch_data['discount']:0),
								'otp_price_fixing'		  =>(!empty($sch_data['otp_price_fixing'])?$sch_data['otp_price_fixing']:0),
								'otp_price_fix_type'		  =>(!empty($sch_data['otp_price_fix_type'])?$sch_data['otp_price_fix_type']:1),
								'one_time_premium'		  =>(!empty($sch_data['one_time_premium'])?$sch_data['one_time_premium']:0),
								'flexible_sch_type'		  =>(!empty($sch_data['flexible_sch_type'])?$sch_data['flexible_sch_type']:NULL),
								//'maturity_days'				=>(isset($sch_data['maturity_days'])?$sch_data['maturity_days']:NULL),
								'maturity_days' =>(!empty($sch_data['maturity_days'])? ($sch_data['maturity_days'] > 0 ? $sch_data['maturity_days'] : NULL):NULL),
								'avg_calc_ins'		  	=>(!empty($sch_data['avg_calc_ins'])?($sch_data['avg_calc_ins']!='' ?$sch_data['avg_calc_ins'] :NULL):NULL),
								'apply_benefit_min_ins'	=>(!empty($sch_data['apply_benefit_min_ins'])?($sch_data['apply_benefit_min_ins']!='' ?$sch_data['apply_benefit_min_ins'] :NULL):NULL),
								'firstPayamt_as_payamt'	 => (!empty($sch_data['firstPayamt_as_payamt'])?$sch_data['firstPayamt_as_payamt']:0),
								'firstPayamt_maxpayable'	 => (!empty($sch_data['firstPayamt_maxpayable'])?$sch_data['firstPayamt_maxpayable']:0),
								'get_amt_in_schjoin'	 => (!empty($sch_data['get_amt_in_schjoin'])?$sch_data['get_amt_in_schjoin']:0),
								'flx_denomintion'				=> number_format((isset($sch_data['flx_denomintion'])?$sch_data['flx_denomintion']:0),2,".",""),
								'is_lucky_draw'    	 => (!empty($sch_data['is_lucky_draw'])?$sch_data['is_lucky_draw']:0),
								'max_members'	=>(!empty($sch_data['max_members'])?$sch_data['max_members']:NULL),
								'has_prize'    	 => (!empty($sch_data['has_prize'])?$sch_data['has_prize']:0),
								'apply_benefit_by_chart'				=>(!empty($sch_data['apply_benefit_by_chart'])?$sch_data['apply_benefit_by_chart']:0),
								'apply_debit_on_preclose'				=>(!empty($sch_data['apply_debit_on_preclose'])?$sch_data['apply_debit_on_preclose']:0),
								'has_gift'				=>(!empty($sch_data['has_gift'])?$sch_data['has_gift']:0),
								'closing_maturity_days' =>(!empty($sch_data['closing_maturity_days'])? ($sch_data['closing_maturity_days'] > 0 ? $sch_data['closing_maturity_days'] : NULL):NULL),
								'auto_debit_plan_type'    	  => (!empty($sch_data['auto_debit_plan_type'])?$sch_data['auto_debit_plan_type']:0)
						); 
								//print_r($sch_info);exit;
				      $this->db->trans_begin();
				     $res = $this->$model->insert_scheme($sch_info);
				    if(isset($_FILES['sch']['name']['edit_sch_img']))	
					{
					  if($res['id_scheme']>0)
					   {
						  $result= $this->set_scheme_image($res['id_scheme']);
					   }
					}
					
					
				    if($res['status']){
				        // Branch-wise scheme
				        if($this->session->userdata('branch_settings') == 1 && $branch_data!= '')
    					{
    						$branch_list       = implode(",",$branch_data);					
    			            $branch_id         = array(explode(',',$branch_list));
    						foreach($branch_id[0] as $branch){
    						$data = array(
    								    'id_scheme'	  =>(isset($res['id_scheme'])?$res['id_scheme']:NULL),
    								    'id_branch'	  =>(isset($branch)?$branch:NULL),
    									'scheme_active'=>1,
    									'date_add'		=> date("Y-m-d H:i:s")
    							     );
    							$status = $this->$model->scheme_branch($data);
    						}
    				    }
				        
				    	// GST Split-up Detail
				    	foreach($gst_data as $data){
							$gstData = array(
											  'splitup_name'  =>(isset($data['splitup_name'])?$data['splitup_name']:NULL),
											  'percentage'	  =>(isset($data['percentage'])?$data['percentage']:0),
											  'type'	      =>((isset($data['type']) && $data['type']!='') ?$data['type']:NULL),
											  'id_scheme'	  =>(isset($res['id_scheme'])?$res['id_scheme']:NULL)
										);
							$this->$model->insert_gstSplitup($gstData);
							if($gstData['type'] == NULL){
								 $schGst['gst'] = $gstData['percentage'];
								 $this->$model->update_scheme($schGst,$res['id_scheme']);
							}
						}
						
						// Interest deduction cart [ On pre-close ] 
						if($sch_data['apply_benefit_by_chart']==1 && $sch_data['apply_debit_on_preclose']==0)  
    					{
    					    $postData = $_POST;
                    		foreach($postData['installmentchart'] as $chartdata){
                    		
                                $insArrdata = array(
                                    'id_scheme'	            => (isset($res['id_scheme'])?$res['id_scheme']:NULL),
                                    'installment_from'	    => $chartdata['installment_from'],
                                    'installment_to'		=> $chartdata['installment_to'],
                                    'interest_type' 	    => $chartdata['interest_type'],
                                    'interest_value'		=> $chartdata['interest_value'],
                                    'created_by'            => $this->session->userdata('uid'),
                                    'date_add'              => date('Y-m-d H:i:s')
                                ); 
                                
                                $data = $this->$model->insertData($insArrdata,'scheme_benefit_deduct_settings');
                    		}
    					}
						
						
						// Closing Balance debit chart [ On pre-close ]
						if($sch_data['apply_benefit_by_chart']==0 && $sch_data['apply_debit_on_preclose']==1)   
    					{
    					    $post_Data = $_POST;
    					  
                    		foreach($post_Data['installmentpreclosechart'] as $preclosechartdata){
                    		
                                $insArrdata = array(
                                    'id_scheme'	            =>(isset($res['id_scheme'])?$res['id_scheme']:NULL),
                                    'installment_from'	    => $preclosechartdata['installment_from'],
                                    'installment_to'		=> $preclosechartdata['installment_to'],
                                    'deduction_type' 	    => $preclosechartdata['deduction_type'],
                                    'deduction_value'		=> $preclosechartdata['deduction_value'],
                                    'created_by'            => $this->session->userdata('uid'),
                                    'date_add'              => date('Y-m-d H:i:s')
                                ); 
                                
                                $data = $this->$model->insertDatas($insArrdata,'scheme_debit_settings');
                    		}
    					}
					}
			 	    $log_data = array(																
									'id_log'     => $this->id_log,
									'event_date' => date("Y-m-d H:i:s"),
									'module'     => 'Scheme',
									'operation'  => 'Add',
									'record'     => $res['id_scheme'],  
									'remark'     => 'Scheme added successfully'
								 );
					$this->$log_model->log_detail('insert','',$log_data);
				   if( $this->db->trans_status()===TRUE)
				   {
					  $this->db->trans_commit();
					  $this->session->set_flashdata('sch_info', array('message' => 'Scheme created successfully','class' => 'success','title'=>'Create Scheme'));
	                 redirect('scheme');
				   }			  
				   else
				   {
				   	 $this->db->trans_rollback();
				   	 $this->session->set_flashdata('sch_info', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Create Scheme'));
				   	  redirect('scheme');
				   }
				break;
			case 'Edit':
			      $postData['installmentchart']=$this->input->post("postData");
			      $post_Data['installmentpreclosechart']=$this->input->post("post_Data");
					$sch_data=$this->input->post("sch");
					$gst_data=$this->input->post("gst_data");
					$branch_data=$this->input->post("branch_data");
				//	echo"<pre>"; print_r($sch_data);exit;
				    $sch_info=array(	
								'min_amount'			=> number_format((!empty($sch_data['min_amount'])?$sch_data['min_amount']:0),2,".",""),
								'max_amount'			=> number_format((!empty($sch_data['max_amount'])?$sch_data['max_amount']:0),2,".",""),
								'max_amt_chance'			=> number_format((!empty($sch_data['max_amt_chance'])?$sch_data['max_amt_chance']:0),2,".",""),
								'min_amt_chance'			=> number_format((!empty($sch_data['min_amt_chance'])?$sch_data['min_amt_chance']:0),2,".",""),
								'pay_duration'				=>(!empty($sch_data['pay_duration'])?$sch_data['pay_duration']:0),
								'wgt_convert'					=>(!empty($sch_data['wgt_convert'])?$sch_data['wgt_convert']:2),
								'noti_msg'			=>(!empty($sch_data['noti_msg'])?$sch_data['noti_msg']:NULL),
			   					'scheme_name'			=>(!empty($sch_data['scheme_name'])?$sch_data['scheme_name']:NULL),
								'code'					=>(!empty($sch_data['code'])?$sch_data['code']:NULL),
								'sync_scheme_code'		=>(!empty($sch_data['sync_scheme_code'])?$sch_data['sync_scheme_code']:NULL),
								'id_metal'				=>(!empty($sch_data['id_metal'])?$sch_data['id_metal']:0),
								'id_classification'		=>(!empty($sch_data['id_classification'])?$sch_data['id_classification']:0),
								'scheme_type'			=>(!empty($sch_data['scheme_type'])?$sch_data['scheme_type']:0),
								'amount'				=> number_format((isset($sch_data['amount'])?$sch_data['amount']:0),2,".",""),
								'total_installments'	=>(!empty($sch_data['total_installments'])?$sch_data['total_installments']:0),
								'maturity_installment'	=>(!empty($sch_data['maturity_installment'])?$sch_data['maturity_installment']:NULL),
								'maturity_type'	        =>(!empty($sch_data['maturity_type'])?$sch_data['maturity_type']:1),
								'payment_chances'		=>(!empty($sch_data['payment_chances'])?$sch_data['payment_chances']:0),
								//'min_chance'			=>(isset($sch_data['min_chance'])?$sch_data['min_chance']:0),
								//'max_chance'			=>(isset($sch_data['max_chance'])?$sch_data['max_chance']:0),
								'sch_limit_value'	=>(!empty($sch_data['sch_limit_value'])?$sch_data['sch_limit_value']:NULL),
								'min_chance'	=>($sch_data['payment_chances']==0?1:(isset($sch_data['min_chance'])?$sch_data['min_chance']:0)),
								'max_chance'	=>($sch_data['payment_chances']==0?1:(isset($sch_data['max_chance'])?$sch_data['max_chance']:0)),
								'min_weight'			=> number_format((!empty($sch_data['min_weight'])?$sch_data['min_weight']:0),3,".",""),
								'max_weight'			=> number_format((!empty($sch_data['max_weight'])?$sch_data['max_weight']:0),3,".",""),
								'allow_unpaid'			=>(!empty($sch_data['allow_unpaid'])?$sch_data['allow_unpaid']:0),
								'unpaid_months'			=>(!empty($sch_data['unpaid_months'])?$sch_data['unpaid_months']:0),
								'unpaid_weight_limit'	=>(!empty($sch_data['unpaid_weight_limit'])?$sch_data['unpaid_weight_limit']:0),
								'allow_advance'			=>(!empty($sch_data['allow_advance'])?$sch_data['allow_advance']:0),
								'advance_months'		=>(!empty($sch_data['advance_months'])?$sch_data['advance_months']:0),
								'advance_weight_limit'	=>(!empty($sch_data['advance_weight_limit'])?$sch_data['advance_weight_limit']:0),
								'allow_preclose'	=>(!empty($sch_data['allow_preclose'])?$sch_data['allow_preclose']:0),
								'preclose_months'	=>(!empty($sch_data['preclose_months'])?$sch_data['preclose_months']:0),
								'preclose_benefits'	=>(!empty($sch_data['preclose_benefits'])?$sch_data['preclose_benefits']:0),
								'interest'				=>(!empty($sch_data['interest'])?$sch_data['interest']:0),	
								'interest_by'			=>(!empty($sch_data['interest_by'])?$sch_data['interest_by']:0),
								'interest_value'		=>number_format((!empty($sch_data['interest_value'])?$sch_data['interest_value']:0),2,".",""),
								'total_interest'		=>number_format((!empty($sch_data['total_interest'])?$sch_data['total_interest']:0),2,".",""),
								'tax'					=>(!empty($sch_data['tax'])?$sch_data['tax']:0),
								'tax_by'				=>(!empty($sch_data['tax_by'])?$sch_data['tax_by']:0),
								'tax_value'				=>number_format((!empty($sch_data['tax_value'])?$sch_data['tax_value']:0),2,".",""),	
								'total_tax'				=>number_format((!empty($sch_data['total_tax'])?$sch_data['total_tax']:0),2,".",""),	
								'description'			=>(!empty($sch_data['description'])?$sch_data['description']:NULL),
								'is_pan_required'			=>(!empty($sch_data['is_pan_required'])?$sch_data['is_pan_required']:0),
								'pan_req_amt'		    =>(!empty($sch_data['pan_req_amt'])?$sch_data['pan_req_amt']:NULL),
								/*'fix_weight'			=>(isset($sch_data['fix_weight'])?$sch_data['fix_weight']:0),*/
								'setlmnt_type'			=>(!empty($sch_data['type'])?$sch_data['type']:3),
								'setlmnt_adjust_by'		=>(!empty($sch_data['adjust_by'])?$sch_data['adjust_by']:2),
								'free_payment'		=>(!empty($sch_data['free_payment'])?$sch_data['free_payment']:0),
								'allowSecondPay'		=>(!empty($sch_data['allowSecondPay'])?$sch_data['allowSecondPay']:0),
								'approvalReqForFP'		=>(!empty($sch_data['approvalReqForFP'])?$sch_data['approvalReqForFP']:0),
								'free_payInstallments'	=>(!empty($sch_data['free_payInstallments'])?$sch_data['free_payInstallments']:NULL),
								'has_free_ins'	=>(!empty($sch_data['has_free_ins'])?$sch_data['has_free_ins']:0),
								'active'				=>(!empty($sch_data['active'])?$sch_data['active']:0),
								'visible'				=>(!empty($sch_data['visible'])?$sch_data['visible']:0),
								'disable_sch_payment'	 => (!empty($sch_data['disable_sch_payment'])?$sch_data['disable_sch_payment']:0),
								'stop_payment_installment'=>(!empty($sch_data['stop_payment_installment'])?($sch_data['stop_payment_installment']!='' ? $sch_data['stop_payment_installment']:0):0),
								'firstPayDisc_value'	=> number_format((!empty($sch_data['firstPayDisc_value'])?$sch_data['firstPayDisc_value']:0),2,".",""),	
								'firstPayDisc'			=>(!empty($sch_data['firstPayDisc'])?$sch_data['firstPayDisc']:0),
								'firstPayDisc_by'		=>(!empty($sch_data['firstPayDisc_by'])?$sch_data['firstPayDisc_by']:0),
								'all_pay_disc'			=>(!empty($sch_data['all_pay_disc'])?$sch_data['all_pay_disc']:0),
								'allpay_disc_value'	=> number_format((!empty($sch_data['allpay_disc_value'])?$sch_data['allpay_disc_value']:0),2,".",""),
								'allpay_disc_by'		=>(!empty($sch_data['allpay_disc_by'])?$sch_data['allpay_disc_by']:0),
								'charge_head'			=>(!empty($sch_data['charge_head'])?$sch_data['charge_head']:NULL),
								'charge_type'			=>(!empty($sch_data['charge_type'])?$sch_data['charge_type']:0),
								'charge'				=>(!empty($sch_data['charge'])?$sch_data['charge']:0.00),
								'gst_type'				=>(!empty($sch_data['gst_type'])?$sch_data['gst_type']:0),
								'hsn_code'				=>(!empty($sch_data['hsn_code'])?$sch_data['hsn_code']:0),
								//'gst'					=>(isset($sch_data['gst'])?$sch_data['gst']:0),
								'Emp_ref_values'				=>(!empty($sch_data['Emp_ref_values'])?$sch_data['Emp_ref_values']:0),
								'cus_ref_values'		=>(!empty($sch_data['cus_ref_values'])?$sch_data['cus_ref_values']:0),	
								'cus_refferal_value'	=>(!empty($sch_data['cus_refferal']) ? (($sch_data['cus_refferal_by']==0 && ($sch_data['scheme_type']==0 || $sch_data['scheme_type']==2))? $sch_data['amount']* $sch_data['cus_ref_values']/100:$sch_data['cus_ref_values']):0),
								'emp_refferal_value'	=>(!empty($sch_data['emp_refferal']) ?(($sch_data['emp_refferal_by']==0 && ($sch_data['scheme_type']==0 || $sch_data['scheme_type']==2))?$sch_data['amount']* $sch_data['Emp_ref_values']/100:$sch_data['Emp_ref_values']) :0),
								'cus_refferal_by'		=>(!empty($sch_data['cus_refferal_by'])?$sch_data['cus_refferal_by']:0),
								'emp_refferal_by'		=>(!empty($sch_data['emp_refferal_by'])?$sch_data['emp_refferal_by']:0),
								'cus_refferal'			=>(!empty($sch_data['cus_refferal'])?$sch_data['cus_refferal']:0),
								'emp_refferal'			=>(!empty($sch_data['emp_refferal'])?$sch_data['emp_refferal']:0),
								'date_upd'				=> date("Y-m-d H:i:s"),
								'ref_benifitadd_ins_type'	  =>(!empty($sch_data['ref_benifitadd_ins_type'])?$sch_data['ref_benifitadd_ins_type']:0),
								'ref_benifitadd_ins'		  =>(!empty($sch_data['ref_benifitadd_ins'])?$sch_data['ref_benifitadd_ins']:0),
								'discount_type'	        =>(!empty($sch_data['discount_type'])?$sch_data['discount_type']:0),
								'discount_installment'	=>(!empty($sch_data['discount_installment'])?$sch_data['discount_installment']:0),
								'discount'		         =>(!empty($sch_data['discount'])?$sch_data['discount']:0),
								'otp_price_fixing'		  =>(!empty($sch_data['otp_price_fixing'])?$sch_data['otp_price_fixing']:0),
								'otp_price_fix_type'		  =>(!empty($sch_data['otp_price_fix_type'])?$sch_data['otp_price_fix_type']:1),
								'one_time_premium'		  =>(!empty($sch_data['one_time_premium'])?$sch_data['one_time_premium']:0),
								'flexible_sch_type'		  =>(!empty($sch_data['flexible_sch_type'])?$sch_data['flexible_sch_type']:NULL),
								//'maturity_days'				=>(isset($sch_data['maturity_days'])?$sch_data['maturity_days']:NULL),
								'maturity_days' =>(!empty($sch_data['maturity_days'])? ($sch_data['maturity_days'] > 0 ? $sch_data['maturity_days'] : NULL):NULL),
								'avg_calc_ins'		  	=>(!empty($sch_data['avg_calc_ins'])?($sch_data['avg_calc_ins']!='' ?$sch_data['avg_calc_ins'] :NULL):NULL),
								'apply_benefit_min_ins'	=>(!empty($sch_data['apply_benefit_min_ins'])?($sch_data['apply_benefit_min_ins']!='' ?$sch_data['apply_benefit_min_ins'] :NULL):NULL),
								'firstPayamt_as_payamt'	 => (!empty($sch_data['firstPayamt_as_payamt'])?$sch_data['firstPayamt_as_payamt']:0),
								'firstPayamt_maxpayable'	 => (!empty($sch_data['firstPayamt_maxpayable'])?$sch_data['firstPayamt_maxpayable']:0),
								'get_amt_in_schjoin'	 => (!empty($sch_data['get_amt_in_schjoin'])?$sch_data['get_amt_in_schjoin']:0),
								'flx_denomintion'				=> number_format((isset($sch_data['flx_denomintion'])?$sch_data['flx_denomintion']:0),2,".",""),
								'is_lucky_draw'    	 => (!empty($sch_data['is_lucky_draw'])?$sch_data['is_lucky_draw']:0),
								'max_members'	=>(!empty($sch_data['max_members'])?$sch_data['max_members']:NULL),
								'has_prize'    	 => (!empty($sch_data['has_prize'])?$sch_data['has_prize']:0),
								'apply_benefit_by_chart'				=>(!empty($sch_data['apply_benefit_by_chart'])?$sch_data['apply_benefit_by_chart']:0),
								'apply_debit_on_preclose'				=>(!empty($sch_data['apply_debit_on_preclose'])?$sch_data['apply_debit_on_preclose']:0),
								'has_gift'				=>(!empty($sch_data['has_gift'])?$sch_data['has_gift']:0),
								'closing_maturity_days' =>(!empty($sch_data['closing_maturity_days'])? ($sch_data['closing_maturity_days'] > 0 ? $sch_data['closing_maturity_days'] : NULL):NULL),
								'auto_debit_plan_type'    	  => (!empty($sch_data['auto_debit_plan_type'])?$sch_data['auto_debit_plan_type']:0)
						); 
				    $this->db->trans_begin();
				    $res = $this->$model->update_scheme($sch_info,$id); 
				   //print_r($this->db->last_query());exit;
					if(isset($_FILES['sch']['name']['edit_sch_img']))	
					{
					  if($res['id_scheme']>0)
					   {
						  $result= $this->set_scheme_image($res['id_scheme']);
					   }
					}
					
					// Interest deduction cart [ On pre-close ] 
					if($sch_data['apply_benefit_by_chart']==1 && $sch_data['apply_debit_on_preclose']==0)	{  
					    $postData = $_POST;
					    $this->$model->delete_benfit_rdeduct($id);
					    
                		foreach($postData['installmentchart'] as $chartdata){
                		
                            $insArrdata = array(
                                'id_scheme'	            =>(isset($res['id_scheme'])?$res['id_scheme']:NULL),
                                'installment_from'	    => $chartdata['installment_from'],
                                'installment_to'		=> $chartdata['installment_to'],
                                'interest_type' 	    => $chartdata['interest_type'],
                                'interest_value'		=> $chartdata['interest_value'],
                                 'created_by'           => $this->session->userdata('uid'),
                                'date_add'              => date('Y-m-d H:i:s')
                            ); 
                             
                            $data = $this->$model->insertData($insArrdata,'scheme_benefit_deduct_settings');
                           
                		}
					}	
					
					// Closing Balance debit chart [ On pre-close ]
                	if($sch_data['apply_benefit_by_chart']==0 && $sch_data['apply_debit_on_preclose']==1)	{ 
                		$post_Data = $_POST;
					    $this->$model->delete_benfit_rdeduct_preclose($id);
					    
                		foreach($post_Data['installmentpreclosechart'] as $preclosechartdata){
                		
                		
                            $insArrdata = array(
                                'id_scheme'	            =>(isset($res['id_scheme'])?$res['id_scheme']:NULL),
                                'installment_from'	    => $preclosechartdata['installment_from'],
                                'installment_to'		=> $preclosechartdata['installment_to'],
                                'deduction_type' 	    => $preclosechartdata['deduction_type'],
                                'deduction_value'		=> $preclosechartdata['deduction_value'],
                                 'created_by'           => $this->session->userdata('uid'),
                                'date_add'              => date('Y-m-d H:i:s')
                            ); 
                            
                            $data = $this->$model->insertDatas($insArrdata,'scheme_debit_settings');
                           
                		}
		            }
						if(($this->session->userdata('branch_settings')==1 && $branch_data!= '' && $res))
						{
								$branch_list       = implode(",",$branch_data);					
					            $branch_id         = array(explode(',',$branch_list));
							$this->$model->delete_scheme_branch($id);
									foreach($branch_id[0] as $branch){
									$data = array(
											    'id_scheme'	  =>(isset($id)?$id:NULL),
											    'id_branch'	  =>(isset($branch)?$branch:NULL),
												'scheme_active'=>1,
												'date_add'		=> date("Y-m-d H:i:s")
										     );
										$status = $this->$model->scheme_branch($data);
									}
				         }
				    if($res && (isset($sch_data['update_gst']) ? $sch_data['update_gst'] == 1 : FALSE) ){
				   	 $result = $this->$model->update_gstSplitup($id);
				   	  if($result){
					  	foreach($gst_data as $data){
							$gstData = array(
											  'splitup_name'  =>(isset($data['splitup_name'])?$data['splitup_name']:NULL),
											  'percentage'	  =>(isset($data['percentage'])?$data['percentage']:0),
											  'type'	      =>((isset($data['type']) && $data['type']!='') ?$data['type']:NULL),
											  'id_scheme'	  => $id
										);
							$this->$model->insert_gstSplitup($gstData);	
							if($gstData['type'] == NULL){
								 $schGst['gst'] = $gstData['percentage'];
								 $this->$model->update_scheme($schGst,$id);
							}						
						}	
					  }
				    }
				  $log_data = array(																//scheme log details
													'id_log'     => $this->id_log,
													'event_date' => date("Y-m-d H:i:s"),
													'module'     => 'Scheme',
													'operation'  => 'Edit',
													'record'     => $id,  
													'remark'     => 'Scheme edited successfully'
												 );
												// print_r($log_data );exit;
					$this->$log_model->log_detail('insert','',$log_data);
				   if( $this->db->trans_status()===TRUE)
				   {
					  $this->db->trans_commit();
					  $this->session->set_flashdata('sch_info', array('message' => 'Scheme modified successfully','class' => 'success','title'=>'Edit Scheme'));
	                  redirect('scheme');
				   }			  
				   else
				   {
				   	 $this->db->trans_rollback();
				   	 $this->session->set_flashdata('sch_info', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Edit Scheme'));
				   }
				break;
		case 'Delete':
					$acc = $this->$model->check_acc_records($id);
						$log_data = array(																//scheme log details
													'id_log'     => $this->id_log,
													'event_date' => date("Y-m-d H:i:s"),
													'module'     => 'Scheme',
													'operation'  => 'Delete',
													'record'     => $id,  
													'remark'     => 'Scheme Deleted successfully'
												 );
												// print_r($log_data );exit;
					$this->$log_model->log_detail('insert','',$log_data);
				  if($acc == TRUE )
					{
					   $this->session->set_flashdata('sch_info', array('message' => 'Unable to proceed your request, Check whether customers exist in this savings scheme.','class' => 'danger','title'=>'Delete Scheme'));
						 redirect('scheme');	                         						
					}
				  else{
		 			$this->db->trans_begin();
				     $this->$model->delete_scheme($id);
				   if( $this->db->trans_status()===TRUE)
				   {
					  $this->db->trans_commit();
					  $this->session->set_flashdata('sch_info', array('message' => 'Scheme deleted successfully','class' => 'success','title'=>'Delete Scheme'));
	                  redirect('scheme');
				   }			  
				   else
				   {
				   	 $this->db->trans_rollback();
				   	 $this->session->set_flashdata('sch_info', array('message' => 'Unable to proceed your request','class' => 'danger','title'=>'Delete Scheme'));
				   }
				  }
		 		break;			
		}
	}
	public function get_metals()
	{
		$model= self::SCH_MODEL;
		$metals=$this->$model->get_metals();
		echo json_encode($metals);
	}
	public function get_classifications()
	{
		$model= self::SCH_MODEL;
		$classifications=$this->$model->get_classifications();
		echo json_encode($classifications);
	}
	public function get_units()
	{
		$model= self::SCH_MODEL;
		$units=$this->$model->get_installment_amount();
		echo json_encode($units);
	}
	public function scheme_business($id)
	{
	   $scheme=array();
	   $model=self::SCH_MODEL;
		$sch=$this->$model->get_scheme($id);
		$scheme['id_scheme']		= $sch['id_scheme'];
		$scheme['scheme_name']		= $sch['scheme_name'];
		$scheme['code']				= $sch['code'];
		$scheme['metal']			= $sch['metal'];
		$scheme['sch_limit_value']	= $sch['sch_limit_value'];
		$scheme['flx_denomintion']	= $sch['flx_denomintion'];
		$scheme['flexible_sch_type']	= $sch['flexible_sch_type'];
		$scheme['get_amt_in_schjoin']	= $sch['get_amt_in_schjoin'];
		$scheme['firstPayamt_as_payamt']	= $sch['firstPayamt_as_payamt'];
		$scheme['sch_type']	= $sch['sch_type'];
		$scheme['min_amount']	= $sch['min_amount'];
		$scheme['max_amount']	= $sch['max_amount'];
		$scheme['disable_payment']	= $sch['disable_payment'];
		$scheme['get_amt_in_schjoin']	= $sch['get_amt_in_schjoin'];
		$scheme['sch_joined_acc']=$this->$model->get_scheme_count($scheme['id_scheme']);
		$scheme['sch_limit']=$this->$model->sch_limit();
		$scheme['has_gift']		= $sch['has_gift'];
		if($sch['scheme_type']==1)
		{ 
			$scheme['scheme_type']	= 'Weight';
			$scheme['min_weight']	= $sch['min_weight'];
			$scheme['max_weight']	= $sch['max_weight'];
		}			
		else if($sch['scheme_type']==2)
		{
			$scheme['scheme_type'] 	= 'Amount to Weight';
			$scheme['amount']	= $sch['amount'];			 	
		}
		else
		{
			$scheme['scheme_type'] 	= 'Amount';
			$scheme['amount']	= $sch['amount'];			 	
		}
		if($sch['payment_chances']==0)
		{
			$scheme['payment_type'] = 'Single';	 	
			$scheme['max_chance']	= 1;
		}
		else
		{
			$scheme['payment_type'] = 'Multiple';
			$scheme['min_chance']	= $sch['min_chance'];
			$scheme['max_chance']	= $sch['max_chance'];					
		}
		$scheme['total_installments']	=  $sch['total_installments'];
		$amount= $sch['amount'] *  $sch['total_installments'];
		if(isset($sch['interest']) && $sch['interest']==1)
		{
			$scheme['interest']= ($sch['interest_by']==1? $sch['interest_value'] : ($amount * ($sch['interest_value']/100)));
		}	
		if(isset($sch['tax']) && $sch['interest']==1)
		{
			$scheme['tax']	= ($sch['tax']==1?$sch['tax_value']: ($amount * ($sch['tax_value']/100)));			
		}
		$scheme['description']=$sch['description'];
		return $scheme;
	}
	
  /*public function ajax_get_schemes()
	{
		$model=self::SCH_MODEL;
		$schemes=$this->$model->get_schemes();
		echo json_encode($schemes);
	} */
	
	public function ajax_get_schemes($id)
	{
		$model=self::SCH_MODEL;
		$schemes=$this->$model->get_schemes($id);
		echo json_encode($schemes);
	}
	
	public function getSchemeTypeByID($id)
	{
		$model=self::SCH_MODEL;
		$schemes=$this->$model->get_schemes();
		echo json_encode($schemes);
	}
	public function ajax_get_scheme($id)
	{
		$scheme=$this->scheme_business($id);
		echo json_encode($scheme);
	}
	public function getFreeInsBySchId($id)
	{
		$scheme=$this->scheme_model->getFreeInsBySchId($id);
		echo json_encode($scheme);
	}
	public function get_branch_edit($id)
	{
		//
		$scheme=$this->scheme_model->get_branch_edit($id);
		/* $branch=implode(',',$scheme); */
		//echo "<pre>";print_r($scheme);echo "</pre>";exit;
		echo json_encode($scheme);
	}
	// to add gst splitup data for existing schemes
		public function gstsplitupinsert()
	{
		$scheme=$this->scheme_model->insetrgstsplitup();
		echo json_encode($scheme);
	}
	public function get_branches()
	{
		$model= self::SCH_MODEL;
		$branches=$this->$model->get_branches();
		echo json_encode($branches);
	}
         function set_scheme_image($id)
         { 
         	 $data = array();
             $model = self::SCH_MODEL;
           	 if($_FILES['sch']['name']['edit_sch_img'])
           	 { 
           	 	$path='assets/img/sch_image/';
           	 	$file_name=$_FILES['sch']['name']['edit_sch_img'];
        	    if (!is_dir($path)) 
        	    {
        		  mkdir($path, 0777, TRUE);
        		}
        		else
        		{
        			$file = $path.$file_name;	
        			chmod($path,0777);
        	        unlink($file);
        		}
           	 	$img=$_FILES['sch']['tmp_name']['edit_sch_img'];
        		$filename = $_FILES['sch']['name']['edit_sch_img'];	
           	 	$imgpath='assets/img/sch_image/'.$filename;
        	  	$upload=$this->upload_img('logo',$imgpath,$img);	
        	 	$data['logo']= $filename;
        	 	$status=$this->$model->update_scheme($data,$id);
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
	  default : die("Unknown filetype");	
	  return false;
	  }		
	  imagecopyresampled($tmp, $src, 0, 0, 0, 0, $width, $height, $width, $height);
	  imagejpeg($tmp, $dst, 60);
	}
	/*	public function create_chart()
    {
        $model = self::SCH_MODEL;
        $chart_data = $this->input->post('chart_data') ;
       
    
        foreach($chart_data as $data){
            $insArrdata = array(
                "id_debit_settings"			=> $data['id_debit_settings'],
                "id_scheme"           => $this->input->post('id_scheme'),
                "installment_from"	=> $data['installment_from'],
                "installment_to"		=> $data['installment_to'],
                "deduction_type" 	=> $data['deduction_type'],
                "deduction_value"		    => $data['deduction_value'],
                "created_by"        => $this->session->userdata('uid'),
                "date_add"          => date('Y-m-d H:i:s')
            ); 
            //print_r($this->db->last_query());exit;
            	$data = $this->$model->insertData($insArrdata,'scheme_debit_settings');
            	echo json_encode($data);	
    }
    }*/
}
?>
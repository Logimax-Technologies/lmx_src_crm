<?php
if( ! defined('BASEPATH')) exit('No direct script access allowed');
class Scheme_model extends CI_Model
{
	const SCH_TABLE='scheme';
	const BRANCH_TABLE='scheme_branch';
	function __construct()
    {
        parent::__construct();
    }
    function get_metals()
    {
		$this->db->select('id_metal,metal');
		$metals=$this->db->get('metal');
		$data[]=array('id'=>0,'name'=>"-- Select --");
		foreach($metals->result() as $metal)
		{
			$data[]=array(
				'id'=> $metal->id_metal,
				'name' => $metal->metal
			);
		} 
		return $data;
	}  
   	function get_classifications()
    {
		$this->db->select('*');
		$this->db->where('active','1');
		$classifications=$this->db->get('sch_classify');
		/*$data[]=array('id'=>0,'name'=>"-- Select --");*/
		foreach($classifications->result() as $classification)
		{
			$data[]=array(
				'id'=> $classification->id_classification,
				'name' => $classification->classification_name
			);
		} 
		return $data;
	}  
   	public function get_all_schemes()
	{
		$this->db->select('id_scheme,scheme_name,code,scheme_type,amount,max_weight,total_installments,interest,description,active,date_add');
		$data=$this->db->get(self::SCH_TABLE);
		return $data->result_array();
	}  	
		public function get_schemes()
		{
			$this->db->select('id_scheme,scheme_name as name,is_pan_required,active');
			$this->db->where('active','1');
			$this->db->where('visible','1');
			if($this->session->userdata('uid')!=1)
			{
				$this->db->where('active','1');
			}
			$data=$this->db->get(self::SCH_TABLE);
			//print_r($this->db->last_query());exit;
			return $data->result_array();
		}
	public function get_schemes_type($id)
	{
		$this->db->select('id_scheme,scheme_type,scheme_name as name');
		$this->db->where('id_scheme',$id);
		$this->db->where('active','1');
		$data=$this->db->get(self::SCH_TABLE);
		return $data->result_array();
	}
	 public function get_scheme($id)
	{
	   $sql="SELECT s.logo,sb.id_branch,s.gst_type,s.gst,s.hsn_code, s.id_scheme, s.scheme_name, 	s.code,s.wgt_convert,sync_scheme_code,s.ref_benifitadd_ins_type, s.ref_benifitadd_ins,
		m.metal, s.id_metal,s.cus_ref_values,s. Emp_ref_values,s.min_amount,max_amount,max_amt_chance,min_amt_chance,pay_duration,allowSecondPay,approvalReqForFP,
		s.scheme_type,s.charge_head,s.charge,s.charge_type,s.cus_refferal,s.cus_refferal_by,
		s.cus_refferal_value,s.emp_refferal_value,s.emp_refferal_by,s.emp_refferal, s.id_classification, sc.classification_name,s.interest_weight, s.amount,
		s.total_installments,s.payment_chances, s.min_chance, s.max_chance,s.min_weight,s.allow_unpaid,s.unpaid_months,s.unpaid_weight_limit, s.allow_advance,
		s.advance_months,s.advance_weight_limit, s.allow_preclose,s.preclose_months,s.preclose_benefits, s.max_weight,s.interest, s.interest_by,s.active,
		s.visible, s.interest_value, s.total_interest, s.tax,s.tax_by,s.tax_value,s.scheme_type as sch_type,
		s.total_tax,s.is_pan_required,s.description,
		s.setlmnt_type as type, s.setlmnt_adjust_by as adjust_by, s.free_payment,s.free_payInstallments,s.has_free_ins,s.firstPayDisc,
		s.firstPayDisc_by,s.firstPayDisc_value,s.sch_limit_value,s.all_pay_disc,s.allpay_disc_by,s.allpay_disc_value,s.discount_installment,s.discount_type,s.discount,otp_price_fixing,otp_price_fix_type,one_time_premium,flexible_sch_type,maturity_days,flx_denomintion,flexible_sch_type,noti_msg
		FROM (scheme s)
		LEFT JOIN metal m ON s.id_metal=m.id_metal
		left join scheme_branch sb on s.id_scheme=sb.id_scheme
		LEFT JOIN sch_classify sc ON s.id_classification=sc.id_classification WHERE s.id_scheme =$id";
	$data=	$this->db->query($sql);
		//print_r($this->db->last_query());exit;
	return $data->row_array();
	}
	 public function get_scheme1($id)
	{
		$sql = $this->db->query("SELECT sb.id_branch as id_branch FROM scheme s
LEFT JOIN metal m ON s.id_metal=m.id_metal
left join scheme_branch sb on s.id_scheme=sb.id_scheme
LEFT JOIN sch_classify sc ON s.id_classification=sc.id_classification WHERE s.id_scheme =$id");
$id_branch = array_map(function ($value) {
return  $value['id_branch'];
}, $sql->result_array()); 
return $id_branch;
	}
	public function get_scheme_active($id)
	{
	    $sql="SELECT
				    s.id_scheme, s.scheme_name, s.code, m.metal, s.id_metal, s.scheme_type, 
				    s.amount, s.total_installments,s.payment_chances,s.allow_unpaid,s.unpaid_months,                                  s.allow_advance,s.advance_months,s.advance_weight_limit,
				    s.allow_preclose,s.preclose_months,s.preclose_benefits,
				     s.min_chance,s.max_chance,s.min_weight, s.max_weight,s.interest,s.interest_by,s.active,
				    s.interest_value, s.total_interest, s.tax,s.tax_by,s.tax_value, 
				    s.total_tax,s.is_pan_required,s.description
				FROM (scheme s)
				JOIN metal m ON s.id_metal=m.id_metal
				WHERE `id_scheme` =".$id."  
				AND `active` =  1" ;
			$data=	$this->db->query($sql);
		return $data->row_array();
	}
	public function get_scheme_id($scheme_code)
	{
		$this->db->select('id_scheme');
		$this->db->where('code',$scheme_code);
		$id_scheme=$this->db->get(self::SCH_TABLE);	
		return ($id_scheme->num_rows==1? $id_scheme->row()->id_scheme:0);
	}
	public function get_weight_scheme_id($type,$scheme_code)
	{
		$this->db->select('id_scheme');
		$this->db->where('scheme_type',$type);
		$this->db->where('code',$scheme_code);
		$id_scheme=$this->db->get(self::SCH_TABLE);		
		return ($id_scheme->num_rows==1? $id_scheme->row()->id_scheme:0);
	}
    public function empty_record()
    {
		$data=array(			
				'id_scheme'				=> 0,
				'scheme_name'			=> NULL,
				'code'					=> NULL,
				'id_metal'				=> 0,
				'id_classification'		=> 1,
				'scheme_type'			=> 0,
				'amount'				=> 0.00,
				'total_installments'	=> NULL,
				'payment_chances'		=> 0,
				'min_chance'			=> 1,
				'allow_unpaid'			=> 0,
				'unpaid_months'			=> 0,
				'unpaid_weight_limit'	=> '0.000',
				'allow_advance'			=> 0,
				'advance_months'		=> 0,
				'advance_weight_limit'	=> '0.000',
				'allow_preclose'		=> 0,
				'preclose_months'		=> 0,
				'preclose_benefits'		=> 0,
				'max_chance'			=> 1,
				'min_weight'			=> '0.500',
				'max_weight'			=> '8.000',
				'avg_calc_ins'			=> NULL,
				'apply_benefit_min_ins' => NULL,
				'max_amt_chance'			=> NULL,
				'min_amt_chance'			=> NULL,
				'min_amount'			=> NULL,
				'max_amount'			=> NULL,
				'interest'				=> 0,	
				'interest_by'			=> 0,
				'interest_value'		=> '0.00',
				'total_interest'		=> '0.00',				
				'tax'					=> 0,
				'tax_by'				=> 0,
				'tax_value'				=> '0.00',	
				'total_tax'				=> 0.00,	
				'description'			=> NULL,
				'pay_duration'			=> 0,
				'is_pan_required'		=> 0,
				'active'				=> 1,
				'visible'				=> 1,
				'type'          		=> 1,
				'adjust_by'     		=> 1,				
				'rate'          		=> '0.00',
				'charge_type'   		=> '0',
				'charge'		   		=> '0.00',
				'firstPayDisc'			=> 0,
				'firstPayDisc_by'		=> 0,
				'firstPayDisc_value'	=> 0.00,	
				'firstPayDisc_total'	=> 0.00,
				'all_pay_disc'			=> 0,
				'allpay_disc_by'		=> 0,
				'allpay_disc_value'		=> 0.00,	
				'charge_head'   		=> 'Convenience fees',
				'free_payInstallments'  => NULL,
				'free_payment'     		=> 0,
				'has_free_ins' 			=> 0,
				'interest_weight' 		=> '0.00',
				'gst_type'  			=> 0,
				'gst'  					=> 0,
				'hsn_code'  			=> NULL,
				'cus_refferal'  		=> 0,
				'cus_refferal_by'  		=> 0,
				'cus_refferal_value'  	=> 0,
				'emp_refferal_value'    => 0,
				'emp_refferal_by'  		=> 0,
				'Emp_ref_values'  		=> NULL,
				'cus_ref_values'  		=> NULL,
				'id_branch'				=>NULL,
				'pay_duration'			=>0,
		        'min_amt_chance'		=>NULL,
		        'max_amt_chance'		=>NULL,
		        'max_amt_chance'		=>NULL,
				'max_amount'			=>NULL,
				'min_amount'			=>NULL,				
				'emp_refferal'       	=> 0,
				'wgt_convert'			=>0,
				'ref_benifitadd_ins_type' =>0,
				'ref_benifitadd_ins'	=>NULL,
				'sync_scheme_code'      =>NULL,
				'allowSecondPay'        =>0,
				'approvalReqForFP'      =>0,
				'discount_type' =>0,
				'discount_installment'	=>NULL,
				'discount'				=>0,
				'otp_price_fixing'			=> 0,
				'otp_price_fix_type'		=> 1,
				'sch_limit'				=>$this->sch_limit(),
				'one_time_premium'		=>0,
				'flexible_sch_type'		=>'',
				'noti_msg'	        => NULL
		);
		return $data;
	}
    function sch_limit()
	{
		$sql="Select c.sch_limit FROM chit_settings c where c.id_chit_settings = 1";
		return $this->db->query($sql)->row()->sch_limit;
	}
/*-- Coded by ARVK --*/		
	function scheme_count()
	{
		$sql = "SELECT id_scheme FROM scheme";
		return $this->db->query($sql)->num_rows();
	}
/*-- / Coded by ARVK --*/	
	/* public function insert_scheme($data)
	{
		$status=$this->db->insert(self::SCH_TABLE,$data);
		return array('status'=>$status,'id_scheme'=>$this->db->insert_id());
	}
	 */
	/*   public function insert_scheme($data)
	{
		if($this->session->userdata('branch_settings')==1)
		{
		$sch_id=0;
    	$insert_flag=0;
		$sch_info=$this->db->insert(self::SCH_TABLE,$data['info']);
		$sch_id=$this->db->insert_id();
		if($sch_info)
		{	
			$branch = $data['branch']['id_branch'];	
			$id_branch=explode(',',$branch);
			foreach($id_branch as $b)
			{
				$data['branch']['id_branch']=$b;
				$data['branch']['id_scheme']=$sch_id;
			$insert_flag=$this->db->insert(self::BRN_TABLE,$data['branch']);
			}
		}
				return array($insert_flag==1?$cus_id:0);
		}
		else
		{
			$status=$this->db->insert(self::SCH_TABLE,$data);
			return array('status'=>$status,'id_scheme'=>$this->db->insert_id());
		}
	} */
	 public function insert_scheme($data)
	{
		$status=$this->db->insert(self::SCH_TABLE,$data);
		return array('status'=>$status,'id_scheme'=>$this->db->insert_id());
	}
	public function update_scheme($data,$id)
	{
		$this->db->where('id_scheme',$id);  
		$status=$this->db->update(self::SCH_TABLE,$data);
		return array('status'=>$status,'id_scheme'=>$id);
	}	
	public function update_scheme_free_payment($data)
	{
		$this->db->where('free_payment',1);  
		$status=$this->db->update(self::SCH_TABLE,$data);
		return $status;
	}	
	public function delete_scheme($id)
	{
		$status = false;
		$this->db->where('id_scheme',$id);  
		$child = $this->db->delete('gst_splitup_detail');
		if($child){
			$this->db->where('id_scheme',$id);  
			$status=$this->db->delete(self::SCH_TABLE);
		}		
		return $status;
	}
	function check_acc_records($id)
	{
		$sql = "select * from scheme_account where  id_scheme = ".$id;
		$status = $this->db->query($sql);
		if($status->num_rows()>0)
		{
			return TRUE;
		}
	}
	function get_fixweight_schemes()/* -- edited by ARVK -- */
	{
		$sql="SELECT
				    s.id_scheme, s.scheme_name, s.code, m.metal, s.id_metal, s.scheme_type,
				    s.amount, s.total_installments,s.payment_chances, s.min_chance,
				    s.max_chance,s.min_weight,s.allow_unpaid,s.unpaid_months,s.unpaid_weight_limit,
				    s.allow_advance,s.advance_months,s.advance_weight_limit,
				    s.allow_preclose,s.preclose_months,s.preclose_benefits,
				    s.max_weight,s.interest, s.interest_by,s.active,
				    s.interest_value, s.total_interest, s.tax,s.tax_by,s.tax_value,
				    s.total_tax,s.is_pan_required,s.description ,count(sa.id_scheme_account) as accounts,
				    count(p.id_payment) as payments,
            if(s.setlmnt_type=1,'Monthly','Purchase')as type,s.setlmnt_type,
            if(s.setlmnt_adjust_by=1,'Highest rate',if(s.setlmnt_adjust_by=2,'Lowest rate',
            if(s.setlmnt_adjust_by=3,'Average rate','Manual')))as adjust_by,s.setlmnt_adjust_by,
            if(s.setlmnt_adjust_by=1,(SELECT max(mr.goldrate_22ct) from metal_rates mr
                                      where month(mr.updatetime) = month(curdate()) and year(mr.updatetime) = year(curdate())),
            if(s.setlmnt_adjust_by=2,(SELECT min(mr.goldrate_22ct) from metal_rates mr
                                      where month(mr.updatetime) = month(curdate()) and year(mr.updatetime) = year(curdate())),
            if(s.setlmnt_adjust_by=3,(SELECT Round(Avg(mr.goldrate_22ct),2) from metal_rates mr
                                      where month(mr.updatetime) = month(curdate()) and year(mr.updatetime) = year(curdate())),'0.00'))) as rate
            FROM scheme s
			LEFT JOIN metal m ON (s.id_metal=m.id_metal)
			LEFT JOIN scheme_account sa ON (s.id_scheme=sa.id_scheme and sa.active=1)
			LEFT JOIN payment p ON (sa.id_scheme_account=p.id_scheme_account and p.fix_weight=0 and p.payment_status=1)
			WHERE s.active=1 AND s.scheme_type=2 and s.setlmnt_type!=3
			GROUP BY s.id_scheme";
	   return $this->db->query($sql)->result_array();
	}
	function getFreeInsBySchId($id){
		$sql = $this->db->query("select free_payInstallments,discount_installment,ref_benifitadd_ins,avg_calc_ins,apply_benefit_min_ins from scheme where  id_scheme='$id'");
		return $sql->row_array();		
	}
	function get_branch_edit($id){
		$sql = $this->db->query("select * from scheme_branch sb left join scheme s on(sb.id_scheme=s.id_scheme) where s.id_scheme='$id'");
				 $id_branch = array_map(function ($value) {
		return  $value['id_branch'];
		}, $sql->result_array()); 
return $id_branch; 
		/* return $sql->result_array(); */		
	}
	/* function get_branch_edit_mrate($id){
		$sql = $this->db->query("select * from scheme_branch sb left join scheme s on(sb.id_scheme=s.id_scheme) where s.id_scheme='$id'");
				 $id_branch = array_map(function ($value) {
		return  $value['id_branch'];
		}, $sql->result_array()); 
return $id_branch; */ 
		/* return $sql->result_array(); */		
	public function get_gstSplitupData($id)
	{
	   $sql="SELECT * FROM gst_splitup_detail WHERE status=1 and `id_scheme` =".$id;
		$data=	$this->db->query($sql);
		return $data->result_array();
	}
	public function insert_gstSplitup($data)
	{
		$status=$this->db->insert('gst_splitup_detail',$data);
		return $status;
	}
	public function update_gstSplitup($id)
	{
		$data['status'] = 0;
		$this->db->where('id_scheme',$id);  
		$status=$this->db->update('gst_splitup_detail',$data);
		//echo $this->db->last_query();exit;
		return $status;
	}	
	// to add gst splitup data for existing schemes
	public function insetrgstsplitup()
	{
		$sql ="SELECT id_scheme FROM scheme s";		
		$count = 0;
		$res = $this->db->query($sql);
		foreach($res->result_array() as $row){
			++$count;
			$insQuery = $this->db->query("INSERT INTO `gst_splitup_detail` (`id_gst_splitup`, `id_scheme`, `splitup_name`, `percentage`, `status`, `type`, `effective_date`, `date_upd`) VALUES (NULL, ".$row['id_scheme'].", 'GST', '3.00', '1', NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
			$insQuery = $this->db->query("INSERT INTO `gst_splitup_detail` (`id_gst_splitup`, `id_scheme`, `splitup_name`, `percentage`, `status`, `type`, `effective_date`, `date_upd`) VALUES (NULL, ".$row['id_scheme'].", 'SGST', '1.50', '1', 0, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
			$insQuery = $this->db->query("INSERT INTO `gst_splitup_detail` (`id_gst_splitup`, `id_scheme`, `splitup_name`, `percentage`, `status`, `type`, `effective_date`, `date_upd`) VALUES (NULL, ".$row['id_scheme'].", 'CGST', '1.50', '1', 0, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
			$insQuery = $this->db->query("INSERT INTO `gst_splitup_detail` (`id_gst_splitup`, `id_scheme`, `splitup_name`, `percentage`, `status`, `type`, `effective_date`, `date_upd`) VALUES (NULL, ".$row['id_scheme'].", 'IGST', '3.00', '1', 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
		}
		return $count;
	}	
		function get_branches()
    {
		$this->db->select('*');
		$this->db->where('active','1');
		$classifications=$this->db->get('branch');
		/*$data[]=array('id'=>0,'name'=>"-- Select --");*/
		foreach($classifications->result() as $classification)
		{
			$data[]=array(
				'id_branch'=> $classification->id_branch,
				'name' => $classification->name
			);
		} 
		return $data;
	}  
/*public function scheme_branch($data)
	{
		$status=$this->db->insert(self::BRANCH_TABLE,$data);
		return array('status'=>$status,'id_scheme'=>$this->db->insert_id());
	}
	 */
	 public function scheme_branch($data)
	{
		$status=$this->db->insert(self::BRANCH_TABLE,$data);
		return array('status'=>$status,'id_scheme'=>$this->db->insert_id());
	}
	public function delete_scheme_branch($id)
	{
		$this->db->where('id_scheme',$id);  
		$status=$this->db->delete(self::BRANCH_TABLE);
	}
	public function get_branch_data($id)
	{
	   $sql="SELECT id_scheme, id_branch FROM scheme_branch WHERE scheme_active=1 and `id_scheme` =".$id;
		$data=	$this->db->query($sql);
		return $data->result_array();
	}
		function get_scheme_count($id_scheme)
	{
		$sql = "SELECT id_scheme FROM scheme_account where id_scheme=".$id_scheme."";
		return $this->db->query($sql)->num_rows();
	}
}
?>
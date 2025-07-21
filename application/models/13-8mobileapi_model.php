<?php
if( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mobileapi_model extends CI_Model
{
	const TAB_ADD     = "address";
	const TAB_ST      = "state";
	const TAB_CY      = "country";
	const TAB_CT      = "city";
	const TAB_CUS     = "customer";
	const TAB_ACC     = "scheme_account";
	const TAB_SCH     = "scheme";
	const TAB_PAY     = "payment";
	const SCH_ENQ     = "sch_enquiry";
	//const TAB_KYC    = "kyc";
    const CUS_IMG_PATH = 'admin/assets/img/customer';
    const BRN_IMG_PATH = 'admin/assets/img/branch';
	function __construct()
    {      
        parent::__construct();
        $this->load->model('services_modal');
		$this->sms_data = $this->services_modal->sms_info();		
		$this->sms_chk =  $this->services_modal->otp_smsavilable();  
	}
	public function insertData($data,$table)
    {
    	$insert_flag = 0;
		$insert_flag = $this->db->insert($table, $data);
		return ($insert_flag == 1 ? $this->db->insert_id(): 0);
	}
	
	public function updData($data, $id_field, $id_value, $table)
    {    
	    $edit_flag = 0;
	    $this->db->where($id_field, $id_value);
		$edit_flag = $this->db->update($table,$data);
		return ($edit_flag==1?$id_value:0);
	}
	
    /**  Read data functions  **/
   function company_details()
	{
		$sql = " Select  cs.branchWiseLogin,cs.is_branchwise_cus_reg,cs.branch_settings,cs.branchwise_scheme,cs.maintenance_text,cs.maintenance_mode,cs.mob_code as call_prefix,
		c.id_company,c.whatsapp_no,c.company_name,c.short_code,c.pincode,c.mobile,c.phone,c.email,c.website,c.address1,c.address2,c.id_country,c.id_state,c.id_city,ct.name as city,s.name as state,c.tollfree1 as tollfree,cy.name as country,cs.currency_symbol,
		cs.currency_name,c.phone1,c.mobile1,cs.allow_notification,c.comp_name_in_sms as cmp_name_sms
				from company c
					join chit_settings cs
					left join country cy on (c.id_country=cy.id_country)
					left join state s on (c.id_state=s.id_state)
					left join city ct on (c.id_city=ct.id_city) ";
		$result = $this->db->query($sql);	
		return $result->row_array();
	}
	function get_currency($id_branch)
	{
	    $sql = " Select cs.branchwise_scheme ,cost_center,cs.pledge_calculator,cs.enable_coin_enq,cs.rate_history,cs.estimation,cs.is_kyc_required,c.company_name,cs.currency_symbol,cs.show_closed_list,cs.currency_name,cs.allow_notification,cs.reg_existing,cs.regExistingReqOtp,cs.useWalletForChit,allow_catlog ,c.tollfree1 as tollfree,
		cs.allow_referral,cs.has_lucky_draw,cs.is_multi_commodity,cs.enableSilver_rateDisc,cs.enableGoldrateDisc,cs.is_branchwise_cus_reg,cs.branch_settings,curr_symb_html,enable_dth,1 as allow_shareonly,allow_wallet  ,
		compare_plan_img,IFNULL((select m_app from modules where m_code='VS' and m_active=1),0) as vs_appt,auto_debit
				from company c
					join chit_settings cs ";
		$data=$this->get_chit_settings();
		if($data['is_branchwise_rate']==1) // Branchwise rate
		{
		    if($device_uuid != '' && $device_type != '' && $id_branch == ''){
    		    $cusBranch = $this->db->query("SELECT id_branch from customer c LEFT JOIN registered_devices d on d.id_customer = c.id_customer where uuid='".$device_uuid."' and device_type=".$device_type." ORDER BY d.id_customer desc limit 1");
    		    if($cusBranch->num_rows() > 0){
    		        $id_branch = $cusBranch->row->id_branch;
    		    }else{
    		        $id_branch = $this->config->item('defBranchRate');  
    		    }
    		}
		    if($id_branch!='' && $id_branch!=0)        
		    {
                $sql1="SELECT  b.name as name,m.mjdmagoldrate_22ct,m.goldrate_22ct,m.goldrate_24ct,m.silverrate_1gm,m.silverrate_1kg,m.mjdmasilverrate_1gm,platinum_1g,
                Date_format(m.updatetime,'%d-%m%-%Y %h:%i %p')as updatetime   
                FROM metal_rates m 
                LEFT JOIN branch_rate br on br.id_metalrate=m.id_metalrates
                left join branch b on b.id_branch=br.id_branch
                ".($id_branch!='' ?" WHERE br.id_branch=".$id_branch."" :'')." ORDER by br.id_metalrate desc LIMIT 1";
		    }
		    else
		    {
		        $sql1="SELECT  b.name as name,m.mjdmagoldrate_22ct,m.goldrate_22ct,m.goldrate_24ct,m.silverrate_1gm,m.silverrate_1kg,m.mjdmasilverrate_1gm,platinum_1g,
                Date_format(m.updatetime,'%d-%m%-%Y %h:%i %p')as updatetime   
                FROM metal_rates m 
                LEFT JOIN branch_rate br on br.id_metalrate=m.id_metalrates
                left join branch b on b.id_branch=br.id_branch
                ORDER by br.id_metalrate desc LIMIT 1";   
		    }
		}
		else{
		$sql1="SELECT  m.mjdmagoldrate_22ct,m.goldrate_22ct,m.goldrate_24ct,m.silverrate_1gm,m.silverrate_1kg,m.mjdmasilverrate_1gm,platinum_1g,
				Date_format(m.updatetime,'%d-%m%-%Y %h:%i %p')as updatetime   FROM metal_rates m 
				WHERE m.id_metalrates=( SELECT max(m.id_metalrates) FROM metal_rates m )";
		}
		$data = $this->db->query($sql);	
		$result['currency']=$data->row_array();
		$result['currency']['ecom_url'] = $this->config->item('ecom_url');
		$result['currency']['sbi_virtual_pay_url'] = $this->config->item('sbi_virtual_pay_url');
		// Compare Plan
		if($result['currency']['compare_plan_img'] != NULL && $result['currency']['compare_plan_img'] != ""){
		    $file = base_url().'assets/img/'.$result['currency']['compare_plan_img'];
	        $result['currency']['compare_plan_img'] = (file_exists('assets/img/'.$result['currency']['compare_plan_img'])?  $file : null ); 
		}
		$rate = $this->db->query($sql1);	
		$result['metal_rates']=$rate->row_array();
		if($result['metal_rates']['silverrate_1gm']==0)
		{
		    $silver="SELECT m.id_metalrates,m.silverrate_1gm FROM metal_rates m WHERE m.silverrate_1gm!='0.00' ORDER by m.id_metalrates DESC LIMIT 1";
		    $silver_rate = $this->db->query($silver)->row_array();
		    $result['metal_rates']['silverrate_1gm']=$silver_rate['silverrate_1gm'];
		}
		if($result['metal_rates']['platinum_1g']==0)
		{
		    $silver="SELECT m.id_metalrates,m.platinum_1g FROM metal_rates m WHERE m.silverrate_1gm!='0.00' ORDER by m.id_metalrates DESC LIMIT 1";
		    $silver_rate = $this->db->query($silver)->row_array();
		    $result['metal_rates']['platinum_1g']=$silver_rate['platinum_1g'];
		}
		$result['reg_custom_fields'] = $this->config->item('custom_fields');
		return $result;
	}
	function get_chit_settings()
	{
		$sql="select * from chit_settings";
		$result = $this->db->query($sql);	
		return $result->row_array();
	}
	//get customer ID by mobile number	
    function get_customerID($mobile)
    {
		$this->db->select('id_customer');
		$this->db->where('mobile',$mobile);
		$customer = $this->db->get(self::TAB_CUS);
	  return $customer->row_array();
	}  
	//get last generated otp by mobile number
	function get_lastOTP($id_customer)
	{
		$this->db->select('id_customer,last_generated_otp,last_otp_expiry');
		$this->db->where('id_customer',$id_customer);
		$customer = $this->db->get(self::TAB_CUS);
	  return $customer->row_array();
	}
	function get_customerByMobile($mobile)
	{
		$record = array();
		$sql="Select c.id_customer,c.firstname,c.lastname,c.notification,c.mobile,c.email,c.id_branch,c.cus_img,c.kyc_status,cs.is_kyc_required from customer c
		JOIN chit_settings cs
		where c.mobile=".$mobile;
		$result = $this->db->query($sql);
		if($result->num_rows() > 0)
		{
			$row = $result->row_array(); 
			$file = self::CUS_IMG_PATH.'/'.$row['id_customer'].'/customer.jpg';
			$img_path = ($row['cus_img'] != null ? (file_exists($file)? $file : null ):null);
			$record = array('id_customer' => $row['id_customer'],'kyc_status' => $row['kyc_status'],'is_kyc_required' => $row['is_kyc_required'], 'email' => $row['email'],'id_branch' => $row['id_branch'], 'lastname' =>ucfirst( $row['lastname']),'firstname' => ucfirst($row['firstname']), 'cus_img' => $img_path);
		}
		//print_r($this->db->last_query());exit;
	    return $record;
	}
	function get_customerProfile($id_customer)
	{
	    $file = base_url().'admin/assets/img/customer/'.$id_customer.'/customer.jpg';
        $cus_img = (file_exists('admin/assets/img/customer/'.$id_customer.'/customer.jpg')?  $file."?nocache=".time() : null );
		$sql="Select
		   c.id_customer,c.title,c.firstname,c.lastname,DATE_FORMAT(c.date_of_birth, '%d-%m-%Y') as date_of_birth,DATE_FORMAT(c.date_of_wed, '%d-%m-%Y') as date_of_wed,
		   a.address1,a.address2,a.address3,ct.name as city,a.pincode,s.name as state,cy.name as country,
		   c.phone,c.mobile,c.email,c.nominee_name,c.nominee_relationship,c.nominee_mobile, 
		   ifnull('".$cus_img."',null)  as cus_img,c.pan,c.pan_proof,c.voterid,c.voterid_proof,c.rationcard,c.rationcard_proof,a.id_country,a.id_city,a.id_state,c.id_employee,
   	       c.comments,c.username,c.is_new,c.active,c.`date_add`,c.`date_upd`,cy.name as countryname,s.name as statename,ct.name as cityname,c.notification
			From
			  customer c
			left join ".self::TAB_ADD." a on(c.id_customer=a.id_customer)
			left join ".self::TAB_CY." cy on (a.id_country=cy.id_country)
			left join ".self::TAB_ST." s on (a.id_state=s.id_state)
			left join ".self::TAB_CT." ct on (a.id_city=ct.id_city)
			where c.id_customer='".$id_customer."'";
		$result = $this->db->query($sql);	
		return $result->row_array();
	}	
	//get customer by id
	function get_customerByID($id_customer)
	{
		$sql="Select
		   c.id_customer,c.firstname,c.lastname,c.date_of_birth,c.id_branch,
		   a.address1,a.address2,a.address3,ct.name as city,a.pincode,s.name as state,cy.name as country,
		   c.phone,c.mobile,c.email,c.nominee_name,c.nominee_relationship,c.nominee_mobile,
		   c.cus_img,c.pan,c.pan_proof,c.voterid,c.voterid_proof,c.rationcard,c.rationcard_proof,a.id_country,a.id_city,a.id_state,c.id_employee,
   	c.comments,c.username,c.passwd,c.is_new,c.active,c.`date_add`,c.`date_upd`,cy.name as countryname,s.name as statename,ct.name as cityname
			From
			  customer c
			left join ".self::TAB_ADD." a on(c.id_customer=a.id_customer)
			left join ".self::TAB_CY." cy on (a.id_country=cy.id_country)
			left join ".self::TAB_ST." s on (a.id_state=s.id_state)
			left join ".self::TAB_CT." ct on (a.id_city=ct.id_city)
			where c.id_customer='".$id_customer."'";
		$result = $this->db->query($sql);	
	    return $result->row_array();
	}
	function get_wallet_accounts($id_customer)
	{
		$sql="Select
					  wa.id_wallet_account,
					  c.id_customer,
					  Concat(c.firstname,' ',if(c.lastname!=NULL,c.lastname,'')) as name,
					  c.mobile,
					  Concat(e.firstname,' ',if(e.lastname!=NULL,e.lastname,'')) as emp_name,
					  wa.wallet_acc_number,
					  Date_Format(wa.issued_date,'%d-%m-%Y') as issued_date,
					  wa.remark,
					  if(wa.active=1,'Active','Inactive') as active,
					  SUM(CASE WHEN wt.transaction_type=0 THEN wt.`value` ELSE 0 END) as  issues,
					  SUM(CASE WHEN wt.transaction_type=1 THEN wt.`value` ELSE 0 END) as redeem,
					  (SUM(CASE WHEN wt.transaction_type=0 THEN wt.`value` ELSE 0 END) -   SUM(CASE WHEN wt.transaction_type=1 THEN wt.`value` ELSE 0 END)) as balance,
					   cs.wallet_amt_per_points,cs.wallet_balance_type,cs.wallet_points
				From wallet_account wa
					Left Join customer c on (wa.id_customer=c.id_customer)
					Left Join employee e on (wa.id_employee=e.id_employee)
					Left Join wallet_transaction wt on (wa.id_wallet_account=wt.id_wallet_account)
					join chit_settings cs
					Where c.id_customer='".$id_customer."'
					Group By wa.id_wallet_account"; 
			$result = $this->db->query($sql);	
	        return $result->result_array();		
	}
	function get_wallet_transactions($id_customer,$id_wallet_trans)
	{
	    $data = array(); 
	    if($id_wallet_trans == ''){
		    $sql="Select
				  wt.id_wallet_transaction,
				  wt.id_wallet_account,
				  c.id_customer,
				  Concat(c.firstname,' ',if(c.lastname!=NULL,c.lastname,'')) as name,
				  c.mobile,
				  Concat(e.firstname,' ',if(e.lastname!=NULL,e.lastname,'')) as emp_name,
				  wa.wallet_acc_number,
				  wa.issued_date,
				  Date_Format(wt.date_transaction,'%d-%m-%Y') as date_transaction,
				  wt.transaction_type,
				  wt.value,
				  wt.description,
				  wa.active
			From wallet_transaction wt
			Left Join wallet_account wa on (wt.id_wallet_account=wa.id_wallet_account)
			Left Join customer c on (wa.id_customer=c.id_customer)
			Left Join employee e on (wa.id_employee=e.id_employee)
			Where c.id_customer =".$id_customer;
	    }else{
	        $sql="Select
				  wt.id_wallet_transaction,
				  wt.id_wallet_account,
				  c.id_customer,
				  Concat(c.firstname,' ',if(c.lastname!=NULL,c.lastname,'')) as name,
				  c.mobile,
				  Concat(e.firstname,' ',if(e.lastname!=NULL,e.lastname,'')) as emp_name,
				  wa.wallet_acc_number,
				  wa.issued_date,
				  Date_Format(wt.date_transaction,'%d-%m-%Y') as date_transaction,
				  wt.transaction_type,
				  wt.value,
				  wt.description,
				  wa.active
			From wallet_transaction wt
			Left Join wallet_account wa on (wt.id_wallet_account=wa.id_wallet_account)
			Left Join customer c on (wa.id_customer=c.id_customer)
			Left Join employee e on (wa.id_employee=e.id_employee)
			Where c.id_customer =".$id_customer." ". ($id_wallet_trans ==0 || $id_wallet_trans == '' ? ' order by id_wallet_transaction desc limit 30':' and id_wallet_transaction < '.$id_wallet_trans.' order by id_wallet_transaction desc limit 30');  
	    }
	    $result = $this->db->query($sql);	
 		if($result->num_rows() > 0){
 		  $data =  $result->result_array();
 		} 
 		return $data;	
	}
	function get_weights()
	{
		$this->db->select('*');
		$this->db->where('active',1);
		return $this->db->get('weight')->result_array();
	}
	function get_country()
	{
		$this->db->select('*');	
	    return $this->db->get('country')->result_array();	
	}
	function get_countryByID($id_country)
	{
		$this->db->select('*');		
		$this->db->where('id_country',$id_country);	
	    return $this->db->get('country')->row_array();	
	}
	function get_state($id_country)
	{
		$this->db->select('*');
		$this->db->where('id_country',$id_country);
		return $this->db->get('state')->result_array();
	}
	function get_stateByID($id_state)
	{
		$this->db->select('*');		
		$this->db->where('id_state',$id_state);	
	    return $this->db->get('state')->row_array();	
	}
	function get_city($id_state)
	{
		$this->db->select('*');
		$this->db->where('id_state',$id_state);
		return $this->db->get('city')->result_array();
	}
	function get_cityByID($id_city)
	{
		$this->db->select('*');		
		$this->db->where('id_city',$id_city);	
	    return $this->db->get('city')->row_array();	
	}
	//get all locations
	function get_cityAll()
	{
	   $this->db->select('*');	
	   return $this->db->get('city')->result_array();	
	}
	function getMatchingCountry($char)
	{
	    $sql = "Select * from country where name Like '$char%'";
	    return	$result = $this->db->query($sql)->result_array();		
	}
	function getMatchingState($char,$id_country)
	{
	    $sql = "Select * from state where name Like '$char%' And id_country='$id_country'";
	    return	$result = $this->db->query($sql)->result_array();		
	}
	function getMatchingCity($char,$id_state)
	{
	    $sql = "Select * from city where name Like '$char%' And id_state='$id_state'";
	    return	$result = $this->db->query($sql)->result_array();		
	}
	function get_stateAll()
	{
	   $this->db->select('*');	
	   return $this->db->get('state')->result_array();	
	}
	//get Schemes
	function get_schemesAll($id_branch)
	{
	    $sql = "SELECT s.id_scheme,s.id_classification,s.scheme_name,s.max_weight,s.min_weight,s.amount,s.code,s.description,s.scheme_type,s.total_installments ,cs.branch_settings,s.is_pan_required,sb.id_branch
            FROM scheme s
	    	join chit_settings cs 
	    	".($data['branchwise_scheme']==1 ? 'left join scheme_branch sb on sb.id_scheme=s.id_scheme' :'')."
	    	where s.active=1 and s.visible=1  ".($id_branch!=''&&$data['branchwise_scheme']==1 ?'and sb.id_branch='.$id_branch.'' :'')." group by s.id_scheme";
	    		//print_r($sql);exit;
	    	$scheme = $this->db->query($sql)->result_array();		
	    	return $scheme;
	}
	//get Schemes
	function get_groups($id_scheme)
   {	
$sql="SELECT s.id_scheme_group, s.id_scheme, s.group_code,sch.code as scheme_code, DATE_FORMAT(s.start_date,'%d-%m-%Y') as start_date, DATE_FORMAT(s.end_date,'%d-%m-%Y') as end_date FROM scheme_group s left join scheme sch on (sch.id_scheme=s.id_scheme)where s.id_scheme=".$id_scheme;	
return $this->db->query($sql)->result_array();	
   }   
	function get_activeSchemes($id_branch,$id_customer)
	{
		$data=$this->get_costcenter();
		$file_path = base_url()."admin/assets/img/sch_image"; 
		 $sql = ('SELECT is_pan_required,if(s.logo=null,s.logo ,concat("'.$file_path.'","/",s.logo) ) as sch_logo,if(scheme_type = 1 ,if(max_weight=min_weight,1,0),"a") as type,s.id_scheme,min_amount,max_amount,classification_name as cls_name,s.id_classification,scheme_name,max_weight,min_weight,amount,s.description,scheme_type,total_installments,interest,interest_by,interest_value,s.flx_denomintion,s.flexible_sch_type,s.one_time_premium,s.is_enquiry
		FROM scheme s
		left join sch_classify cls on cls.id_classification = s.id_classification
		'.($data['branchwise_scheme']==1 ? "left join scheme_branch sb on sb.id_scheme=s.id_scheme" :"").'
		join chit_settings cs
		where s.active=1 and visible=1 '.($id_branch!=''&& $data['is_branchwise_cus_reg']==1 && $data['branchwise_scheme']==1 ?'and sb.id_branch='.$id_branch.'' :'').'');
		//print_r($sql);exit;
	    $schemes = $this->db->query($sql)->result_array();
	    if($id_customer > 0){
	        $i = 0;
    	    foreach($schemes as $scheme){
    	        if($scheme['is_pan_required'] == 2 || $scheme['is_nominee_required'] == 2){
    	            $cus = $this->db->query("select pan,nominee_name,nominee_mobile, nominee_relationship,nominee_address1,nominee_address2 from customer where id_customer=".$id_customer);
    	            $cusData = $cus->row_array();
    	            //$schemes[$i]['get_cus_nominee'] = ($scheme['is_nominee_required'] == 2 && (($cusData['nominee_name'] == null || $cusData['nominee_name'] =='') || ($cusData['nominee_address1'] == null || $cusData['nominee_address1'] =='') || ($cusData['nominee_address2'] == null || $cusData['nominee_address2']=='') || ($cusData['nominee_relationship'] == null || $cusData['nominee_relationship']=='')) ? 1 : 0 );
    	            $schemes[$i]['get_cus_nominee'] = ($scheme['is_nominee_required'] == 2 && (empty($cusData['nominee_name']) || empty($cusData['nominee_address1']) || empty($cusData['nominee_address2']) || empty($cusData['nominee_relationship'])) ? 1 : 0 );
                    $schemes[$i]['get_cus_pan'] = ($scheme['is_pan_required'] == 2 && ($cusData['pan'] == null || $cusData['pan'] == '')?1:0 );
                }
                $i++;
    	    }
	    }
	    return $schemes;
	}
/*	function getVisClass($id_branch)
	{
     $data = $this->get_costcenter();
     $CLSFY_IMG_PATH = base_url().'admin/assets/img/sch_classify/';
     $sql = "SELECT  id_classification, classification_name, description,concat('".$CLSFY_IMG_PATH."','',sc.logo) as logo
     FROM sch_classify sc
     WHERE EXISTS (SELECT id_classification
     FROM scheme s ".($data['branchwise_scheme']==1 ? 'left join scheme_branch sb on sb.id_scheme=s.id_scheme' :'')."
     WHERE ".($id_branch!='' && $data['is_branchwise_cus_reg']==1 && $data['branchwise_scheme']==1 ?' sb.id_branch='.$id_branch.' and'  :'')."   sc.id_classification = s.id_classification and s.active=1 and s.visible=1) ";
     //print_r($sql);exit;
     $classifications = $this->db->query($sql)->result_array();
     return $classifications;
    }*/
    function getVisClass($id_branch)
    {
	    $result = [];
	    $data = $this->get_costcenter();
	    $CLSFY_IMG_PATH = base_url().'admin/assets/img/sch_classify/';
	    $sql = "SELECT  id_classification, classification_name, description,concat('".$CLSFY_IMG_PATH."','',sc.logo) as logo
	    FROM sch_classify sc
	    WHERE active =1";
	    //print_r($sql);exit;
	    $classifications = $this->db->query($sql)->result_array();
	    foreach($classifications as $clsfy){
	    	if($data['branchwise_scheme']==1){
				$sch_sql = $this->db->query("SELECT id_classification,sb.id_branch,b.name
										    FROM scheme s 
											    left join scheme_branch sb on sb.id_scheme=s.id_scheme and scheme_active=1
											    LEFT JOIN branch b on b.id_branch = sb.id_branch
		    								WHERE ".($id_branch!='' && $data['is_branchwise_cus_reg'] ==1 ?' sb.id_branch='.$id_branch.' and'  :'')."  s.active=1 and s.visible=1 and id_classification=".$clsfy['id_classification']. " GROUP BY id_classification,id_branch");
			}else{
				$sch_sql = $this->db->query("SELECT id_classification,null as id_branch
											    FROM scheme s 
											    WHERE s.active=1 and s.visible=1 and id_classification=".$clsfy['id_classification']. " GROUP BY id_classification");
			}
		   //print_r($this->db->last_query());exit;
		    $schemes = $sch_sql->result_array();
		    foreach($schemes as $sch){
		         $result[] = array(
						         "id_classification" => $clsfy['id_classification'],
						         "id_branch" => $sch['id_branch'],
						         "classification_name"=> $clsfy['classification_name'],
						         "description"=> $clsfy['description'],
						         "logo"=> $clsfy['logo']
						         );
		    }
	    }
	    return $result;
    }
    function getVisClass_withoutbranch()
	{
	    $classifications = [];
         $data = $this->get_costcenter();
         $CLSFY_IMG_PATH = base_url().'admin/assets/img/sch_classify/';
         $sql = $this->db->query("SELECT  id_classification, classification_name, description,concat('".$CLSFY_IMG_PATH."','',sc.logo) as logo
         FROM sch_classify sc WHERE active =1") ;
          //print_r($this->db->last_query());exit;
         foreach($sql->result_array() as $clsfy){
             $classifications[] = array(
    				         "id_classification" => $clsfy['id_classification'],
    				         "id_metal" => isset($clsfy['id_metal'])?$clsfy['id_metal']:NULL,
    				         "metal" => isset($clsfy['metal'])?$clsfy['metal']:NULL,
    				         "classification_name"=> $clsfy['classification_name'],
    				         "description"=> $clsfy['description'],
    				         "logo"=> $clsfy['logo']
    				         );
        }
        return $classifications;
    }
    function get_scheme($id_scheme,$id_customer)
    {
    	$sql =('select is_enquiry,flexible_sch_type,s.id_metal,s.is_lucky_draw,cls.description as terms,if(s.scheme_type = 1 ,if(s.max_weight=s.min_weight,1,0),"a") as type,s.id_scheme,cs.cusName_edit,s.scheme_name,s.min_amount,if(scheme_type=3 && max_amount!=0,max_amount,round(max_weight *(SELECT m.goldrate_22ct FROM metal_rates m  order by id_metalrates Desc LIMIT 1))) as max_amount,s.max_weight,s.min_weight,s.amount,s.description,s.scheme_type,s.total_installments,s.interest,s.interest_by, s.interest_value,cs.branch_settings,if(c.cus_ref_code!="",c.cus_ref_code,"") as cus_ref_code,if(c.emp_ref_code!="",c.emp_ref_code,"") as emp_ref_code,cs.cusbenefitscrt_type,cs.empbenefitscrt_type,s.flx_denomintion,get_amt_in_schjoin,s.maturity_type,maturity_days,
    	CONCAT(if(cs.has_lucky_draw = 1 && s.is_lucky_draw = 1,sg.group_code,s.code)) as code,
    	cs.is_branchwise_cus_reg,cs.branchwise_scheme,c.id_branch,0 as askBranch,s.one_time_premium,
    	s.is_pan_required,s.pan_req_amt,s.is_aadhaar_required,s.aadhaar_required_amt,
    	concat(c.firstname," ",if(c.lastname!=NULL,c.lastname,"")) as cus_name
    	from scheme s
    	Left Join scheme_group sg On (s.id_scheme = sg.id_scheme )
    	left join sch_classify cls on cls.id_classification = s.id_classification
    	left join customer c on c.id_customer='.$id_customer.'
    	join chit_settings cs
    	where s.id_scheme='.$id_scheme.'  and s.active=1 and c.active=1 and c.id_customer='.$id_customer.' group by s.id_scheme');
    	//echo $sql;
    	$result =  $this->db->query($sql)->row_array();
    	if($result['is_pan_required'] == 2 || $result['is_aadhaar_required'] == 2){
    	    $kyc = $this->db->query("SELECT kyc_type,number from kyc where status=2 and id_customer = ".$id_customer);
	    	$kycData = $kyc->result_array();
	    	foreach($kycData as $k){
	    	    if($k['kyc_type'] == 2){
	    	        $result['pan'] = $k['number'];
	    	    }
	    	    else if($k['kyc_type'] == 3){
	    	        $result['aadhaar_no'] = $k['number'];
	    	    }
	    	}
    	}
    	if($result['branch_settings'] == 1){
    		if($result['is_branchwise_cus_reg'] == 0){ 
	    		if($result['branchwise_scheme'] == 1){ 
	    			$sch_branch = $this->db->query("SELECT id_branch from scheme_branch where id_scheme = ".$id_scheme);
	    			$schBrData = $sch_branch->result_array();
	    			if($sch_branch->num_rows() == 1){ // Donot ask the branch set the available branch
						$result['id_branch'] = $schBrData[0]['id_branch'];
					}
					else if($sch_branch->num_rows() > 0){ // Ask the branch
						$result['askBranch'] = 1;
					}
	    		}else{
					$result['askBranch'] = 1;
				}
			}
		}
    	
    	return $result;
    }
	/*  //Generate 10 digit Account number random
	function account_number_generator()
	{
	  $query = $this->db->query("SELECT LPAD(round(rand() * 1000000000),10,0) as myCode
								FROM scheme_account
								HAVING myCode NOT IN (SELECT scheme_acc_number FROM scheme_account) limit 0,1");
		if($query->num_rows()==0){
			$query = $this->db->query("SELECT LPAD(round(rand() * 1000000000),10,0) as myCode");
		}
		return $query->row()->myCode;
	}*/
	//Generate account number 
	function account_number_generator($id_scheme,$branch)
	{
	  $lastno=$this->get_schAccount_no($id_scheme,$branch);
	  if($lastno!=NULL)
		{
		  	$number = (int) $lastno;
			$number++;
    		$schAc_number=str_pad($number, 5, '0', STR_PAD_LEFT);;
    		return $schAc_number;
		}
		else
		{
			$schAc_number=str_pad('1', 5, '0', STR_PAD_LEFT);;
    		return $schAc_number;
		}
	}
	 function get_schAccount_no($id_scheme,$branch)  // branch wise scheme acc num generaTION  based on the settings//HH
    {
		$data=$this->get_costcenter();
        if($data['scheme_wise_acc_no']==1 || $data['scheme_wise_acc_no']==0){
    //  $sql = "SELECT max(TRIM(LEADING '0' FROM scheme_acc_number))  as lastSchAcc_no FROM scheme_account where id_scheme=".$id_scheme." ORDER BY id_scheme_account DESC ";
		$sql = "SELECT max(TRIM(LEADING '0' FROM scheme_acc_number))  as lastSchAcc_no FROM scheme_account sa
		where sa.id_scheme=".$id_scheme." ORDER BY id_scheme_account DESC ";
		//$sql = "SELECT max(TRIM(LEADING '0' FROM scheme_acc_number))  as lastSchAcc_no FROM scheme_account where id_scheme=".$id_scheme." ORDER BY id_scheme_account DESC ";
        }
        else if($data['branch_settings']==1 && $data['scheme_wise_acc_no']==2){
            $sql = "SELECT max(TRIM(LEADING '0' FROM scheme_acc_number))  as lastSchAcc_no FROM scheme_account sa 
            LEFT JOIN scheme_branch sb ON sb.id_scheme = sa.id_scheme
            where sa.id_scheme=".$id_scheme."  ".($data['scheme_wise_acc_no']==2 ? 'and sa.id_branch='.$branch.'':'')." ORDER BY id_scheme_account DESC ";
        }    
		return $this->db->query($sql)->row()->lastSchAcc_no;		
	}
	// to check scheme_acc_number already exists
	/*function verify_existing($scheme_acc_number)
	{
		$this->db->select('id_scheme_account,id_customer');
		$this->db->where('scheme_acc_number',$scheme_acc_number);
		$schAcc = $this->db->get('scheme_account');
		if($schAcc->num_rows() > 0)
		{
			return TRUE;
		}	
	}*/		function verify_existing($schAcc)	{		$this->db->select('id_scheme_account,id_customer');		$this->db->where('scheme_acc_number',$schAcc['scheme_acc_number']);		$schAcc = $this->db->get('scheme_account');		if($schAcc['group_code'] != NULL)		{   	  	 $this->db->where('group_code',$schAcc['group_code']);  	 	}		if($schAcc->num_rows() > 0)		{			return TRUE;		}		}
	function checkService($serviceID)
	{
		$email = 0;
		$sms   = 0;
		$query = $this->db->get_where('services',array('id_services' => $serviceID));
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$email = $row->serv_email;
			$sms   = $row->serv_sms;
		}
		$data = array("email" => $email, "sms" => $sms);
		return $data;
	}
	//get customer schemes_accounts
	function get_scheme_accounts($id_customer)
	{
		$sql="Select sa.id_scheme_account,s.code,sa.scheme_acc_number,s.scheme_name,s.scheme_type,ref_no as client_id,sa.start_date,
       IF(s.scheme_type=0,s.amount,IF(s.scheme_type=1,s.max_weight,s.amount)) as installment,
       s.total_installments,
       COUNT(DISTINCT DATE_FORMAT(p.date_payment,'%Y%m')) as paid_installment,
       MAX(DISTINCT DATE_FORMAT(p.date_payment,'%Y/%m')) as last_paid_month,
       MAX(DISTINCT p.date_payment) as last_paid,
       FORMAT(IFNULL(SUM(p.metal_weight),0),3) as total_weight,
       FORMAT(IFNULL(SUM(p.payment_amount),0),2) as total_paid_amount,
       FORMAT(IFNULL(SUM(py.metal_weight),0),3) as cur_total_weight,
       FORMAT(IFNULL(s.max_weight,0) - IFNULL(SUM(py.metal_weight),0),3) as eligible_weight,
       FORMAT(IFNULL(SUM(py.payment_amount),0),2) as cur_total_paid,
       IF(COUNT(DISTINCT DATE_FORMAT(p.date_payment,'%Y%m')) <= s.total_installments,IF(s.scheme_type=0,(IF(IFNULL(SUM(py.payment_amount),0)=0,'Y','N')),IF(IFNULL(SUM(py.metal_weight),0) < s.max_weight,'Y','N')),'Completed') as pay_now
From scheme_account sa
Left join scheme s On (sa.id_scheme=s.id_scheme)
Left join payment p On (sa.id_scheme_account=p.id_scheme_account and p.payment_status=1)
Left join payment py On (sa.id_scheme_account=py.id_scheme_account and py.payment_status=1  and DATE_FORMAT(py.date_payment,'%Y%m')=DATE_FORMAT(now(),'%Y%m'))
Where sa.active=1 and s.active=1 and sa.id_customer='$id_customer'
Group By sa.id_scheme_account";
	  return	$result = $this->db->query($sql)->result_array();	
	}
	function get_schemeaccount_detail($id_scheme_account)
	{  
	   $schemeAcc = array();
		$sql="Select s.gst,s.gst_type,p.payment_status,if(s.free_payment = 1 , s.allowSecondPay,0) as redirectToPay,approvalReqForFP,s.id_metal,if(s.id_metal=1,'goldrate_22ct',if(s.id_metal=2,'silverrate_1gm','')) as rate_field,
				IF(s.firstPayDisc=1,s.firstPayDisc_value,0.00) as discount,s.firstPayDisc_by,s.firstPayDisc,sa.is_new,
			    sa.id_scheme_account,sa.id_branch as sch_join_branch,
			    s.id_scheme,cs.is_branchwise_rate,
			    c.id_customer,
			   sa.scheme_acc_number as chit_number,
			    IFNULL(sa.account_name,if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname))) as account_name,
			    c.mobile,
			    s.scheme_type,
			    s.code,
			    IFNULL(s.min_chance,0) as min_chance,
			    IFNULL(s.max_chance,0) as max_chance,
			    Format(IFNULL(s.max_weight,0),3) as max_weight,
			    Format(IFNULL(s.min_weight,0),3) as min_weight,
			    Date_Format(sa.start_date,'%d-%m-%Y') as start_date,
			    IF(s.scheme_type=0,s.amount,IF(s.scheme_type=1,s.max_weight,s.amount)) as payable,
			    s.total_installments,
			   IFNULL(IF(sa.is_opening=1,IFNULL(sa.paid_installments,0)+ IFNULL(if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues)),0), if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues))) ,0)
  as paid_installments,get_amt_in_schjoin,sa.firstPayment_amt,sa.is_registered,
IFNULL(IF(sa.is_opening=1,IFNULL(balance_amount,0)+IFNULL(SUM(p.payment_amount * p.no_of_dues),0),IFNULL(SUM(p.payment_amount * p.no_of_dues),0)) ,0)
  as total_paid_amount,
IFNULL(IF(sa.is_opening=1,IFNULL(balance_weight,0)+IFNULL(SUM(p.metal_weight),0),IFNULL(SUM(p.metal_weight),0)),0.000)
 as total_paid_weight,
  	round(IF(s.scheme_type=0 OR s.scheme_type=2,s.amount,IF(s.scheme_type=1 ,s.max_weight,
				if(s.scheme_type=3 && s.max_amount!='',s.max_amount,0)))) as max_amount,firstPayamt_maxpayable,firstPayamt_as_payamt,s.flexible_sch_type,sa.firstPayment_amt,
  if((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) > s.total_installments, 
(s.total_installments - COUNT(payment_amount)), 
ifnull((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) - SUM(p.no_of_dues),if((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) > s.total_installments,s.total_installments,(PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m')))))) 
as totalunpaid_1, 
   if((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) > s.total_installments,   (s.total_installments - if(sa.is_opening = 1,(COUNT(payment_amount)+sa.paid_installments),COUNT(payment_amount))),ifnull(((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m')))+1) - IF(sa.is_opening=1,IFNULL(sa.paid_installments,0)+ IFNULL(if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues)),0), if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues))),if((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) > s.total_installments,s.total_installments,(PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m')))))) as totalunpaid,  
IFNULL(if(Date_Format(max(p.date_add),'%Y%m') = Date_Format(curdate(),'%Y%m'), (select SUM(ip.no_of_dues) from payment ip where Date_Format(ip.date_add,'%Y%m') = Date_Format(curdate(),'%Y%m') and sa.id_scheme_account = p.id_scheme_account),IF(sa.is_opening=1, if(Date_Format(sa.last_paid_date,'%Y%m') = Date_Format(curdate(),'%Y%m'), 1,0),0)),0) as currentmonthpaycount,
  (select SUM(pay.no_of_dues) from payment pay where pay.id_scheme_account= sa.id_scheme_account and pay.due_type='AD' and (pay.payment_status=1 or pay.payment_status=2)) as currentmonth_adv_paycount,
  (select SUM(pay.no_of_dues) from payment pay where pay.id_scheme_account= sa.id_scheme_account and pay.due_type='PD' and (pay.payment_status=1 or pay.payment_status=2)) as currentmonth_pend_paycount,
IF(s.scheme_type =1 and s.max_weight != s.min_weight,true,false) as is_flexible_wgt,
			    Format(IFNULL(cp.total_amount,0),2) as  current_total_amount,
			    Format(IFNULL(cp.total_weight,0) + IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),(sa.last_paid_weight),0) ,3) as  current_total_weight,
			    IFNULL(cp.paid_installment,0)       as  current_paid_installments,
			    IFNULL(cp.chances,0) + IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),(sa.last_paid_chances),0) as  current_chances_used,
			    s.is_pan_required,
			    IFNULL(Date_Format(max(p.date_payment),'%d-%m-%Y'),IFNULL(IF(sa.is_opening=1,Date_Format(sa.last_paid_date,'%d-%m-%Y'),'')  ,0))                 as last_paid_date,
					IFNULL(PERIOD_DIFF(Date_Format(curdate(),'%Y%m'),Date_Format(max(p.date_add),'%Y%m')),IF(sa.is_opening=1,PERIOD_DIFF(Date_Format(curdate(),'%Y%m'),Date_Format(sa.last_paid_date,'%Y%m')),0)) as last_paid_duration,
				IF(sa.is_opening = 1 and s.scheme_type = 0 || s.scheme_type = 2,
				IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),false,true),
				true) AS previous_amount_eligible,
				count(pp.id_scheme_account) as cur_month_pdc,
				s.allow_unpaid,
				if(s.allow_unpaid=1,s.unpaid_months,0) as allow_unpaid_months,
				s.allow_advance,
				if(s.allow_advance=1,s.advance_months,0) as advance_months,
				IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),1,0) as  previous_paid,
				sa.disable_payment,s.disable_sch_payment
			From scheme_account sa
			Left Join scheme s On (sa.id_scheme=s.id_scheme)
			Left Join payment p On (sa.id_scheme_account=p.id_scheme_account and p.payment_status=1)
			Left Join customer c On (sa.id_customer=c.id_customer and c.active=1)
			Left Join
			(	Select
				  sa.id_scheme_account,
				  COUNT(Distinct Date_Format(p.date_payment,'%Y%m')) as paid_installment,
				  COUNT(Date_Format(p.date_payment,'%Y%m')) as chances,
				  SUM(p.payment_amount) as total_amount,
				  SUM(p.metal_weight) as total_weight
				From payment p
				Left Join scheme_account sa on(p.id_scheme_account=sa.id_scheme_account and sa.active=1 and sa.is_closed=0)
				Where p.payment_status=1 and  Date_Format(Current_Date(),'%Y%m')=Date_Format(p.date_payment,'%Y%m')
				Group By sa.id_scheme_account
			) cp On (sa.id_scheme_account=cp.id_scheme_account)
			 Left Join postdate_payment pp On (sa.id_scheme_account=pp.id_scheme_account and (pp.payment_status=2 or pp.payment_status=7) and (Date_Format(pp.date_payment,'%Y%m')=Date_Format(curdate(),'%Y%m')))
			 	JOIN chit_settings cs 
		Where sa.active=1 and sa.is_closed = 0 and sa.id_scheme_account='$id_scheme_account'";
	//	print_r($sql);exit;
		$records = $this->db->query($sql);
		if($records->num_rows()>0)
		{
			foreach($records->result() as $record)
			{
				$allowed_due = 0;
				$due_type = '';
				$metal_rates=$this->get_metalrate($record->sch_join_branch,$record->is_branchwise_rate);//For branchwise rate
				if($record->paid_installments > 0 || $record->totalunpaid >0){
					if($record->currentmonthpaycount == 0){  // current month not paid (allowed pending due + current due)
						if($record->allow_unpaid == 1){
							if($record->allow_unpaid_months > 0 && ($record->total_installments - $record->paid_installments) >=  $record->allow_unpaid_months && $record->totalunpaid >0){
								if(($record->total_installments - $record->paid_installments) ==  $record->allow_unpaid_months){
									$allowed_due =  $record->allow_unpaid_months ;  
								    $due_type = 'PD'; //  pending
								}
								else{
									$allowed_due =  $record->allow_unpaid_months+1 ;  
								    $due_type = 'PN'; // normal and pending
								}
							}
							else{
							     $allowed_due =  1;
							     $due_type = 'ND'; // normal due
							}
						}
						else{
							 $allowed_due =  1;
							 $due_type = 'ND'; // normal due
						}
					}
					else{ 	//current month paid
						if($record->allow_unpaid == 1 && $record->allow_unpaid_months >0 && $record->totalunpaid >0 && ($record->currentmonthpaycount-1) < $record->allow_unpaid_months){  
							// can pay previous pending dues if attempts available 
							if($record->totalunpaid > $record->allow_unpaid_months){
								 $allowed_due =  $record->allow_unpaid_months ;
								 $due_type = 'PD'; // pending due
							}
							else{ 
								 $allowed_due =  $record->totalunpaid;
								 $due_type = 'PD'; // pending due
							}
						}
						else{  // check allow advance
							if($record->allow_advance == 1 && $record->advance_months > 0 && ($record->currentmonth_adv_paycount) < $record->advance_months){
								if(($record->advance_months + $record->paid_installments) <= $record->total_installments){
									 $allowed_due =  ($record->advance_months - ($record->currentmonth_adv_paycount));
									 $due_type = 'AD'; // advance due
								}
								else{
									 $allowed_due =  ($record->total_installments - $record->paid_installments);
									 $due_type = 'AD'; // advance due
								}
							}
							else{ // have to check
								 $allowed_due =  0;
								 $due_type = ''; // normal due
							}
						}
					}
				}
				else{  // check allow advance and add due with currect month (allowed advance due + current due)
					if($record->allow_advance ==1){ // check allow advance
						if($record->advance_months > 0 && $record->advance_months <= ($record->total_installments - $record->paid_installments)){
							if(($record->total_installments - $record->paid_installments) ==  $record->advance_months){
									 $allowed_due =  $record->advance_months;
									 $due_type = 'AN'; // advance and normal
								}
								else{
									$allowed_due =  $record->advance_months+1 ;  
								     $due_type = 'AN'; // advance and normal
								}
						}
						else{
							 $allowed_due =  1;
							 $due_type = 'ND'; // normal due
						}
					}
					else{
						 $allowed_due =  1;
						 $due_type = 'ND'; // normal due
					}
				} 
				$schemeAcc[] = array(
										'gst_type'			=> $record->gst_type,
										'gst' 				=> $record->gst,
										'id_scheme_account' => $record->id_scheme_account,
										'chit_number' 		=> $record->chit_number,
										'account_name' 		=> $record->account_name,
										'start_date' 		=> $record->start_date,
										'mobile' 		=> $record->mobile,
										'id_branch'         =>$record->sch_join_branch,
										//'payable' 		=> $record->payable,
										'payable' => (($record->scheme_type==3 && $record->max_amount!=0 &&($record->flexible_sch_type==1 || $record->flexible_sch_type==2) && $record->max_amount!=''?((($record->firstPayamt_maxpayable==1||$record->firstPayamt_as_payamt==1)&&($record->paid_installments>0||$record->get_amt_in_schjoin==1)||($record->is_registered==1))?round($record->firstPayment_amt) :round($record->max_amount-str_replace(',', '',$record->current_total_amount))):($record->scheme_type==3 && ($record->max_weight!=0 || $record->max_weight!='')? round(($record->max_weight - $record->current_total_weight)*$record->metal_rate) : $record->payable))),
                                        'metal_rate'                => $metal_rates[$record->rate_field],
										'code' 		=> $record->code,
										'scheme_type' 		=> $record->scheme_type,
										'total_installments' 		=> $record->total_installments,
										'paid_installments' 		=> $record->paid_installments,
										'total_paid_amount' 		=> $record->total_paid_amount,
										'total_paid_weight' 		=> $record->total_paid_weight,
										'current_total_amount' 		=> $record->current_total_amount,
										'current_paid_installments' 		=> $record->current_paid_installments,
										'current_chances_used' 		=> $record->current_chances_used,
										'eligible_weight' 		    => ($record->min_weight == $record->max_weight ? $record->min_weight : ($record->max_weight - $record->current_total_weight)),
										'last_paid_date' 		=> $record->last_paid_date,
										'is_pan_required' 		=> $record->is_pan_required,
										'last_transaction'     => $last,
										'isPaymentExist'	 => $this->isPaymentExist($record->id_scheme_account),
										'isPendingStatExist' => $this->isPendingStatExist($record->id_scheme_account),
										'max_weight' => $record->max_weight,
										'current_total_weight' => $record->current_total_weight,
										'previous_amount_eligible' => $record->previous_amount_eligible,
										'cur_month_pdc' => $record->cur_month_pdc,
										'redirectToPay' => $record->redirectToPay,
										'approvalReqForFP'  => $record->approvalReqForFP,
									//	'allow_pay'  => ($record->disable_payment != 1 && ($record->payment_status !=2) ? ($record->cur_month_pdc < 1 ? ($record->paid_installments <= $record->total_installments ?($record->is_flexible_wgt?($record->current_total_weight >= $record->max_weight || $record->current_chances_used >= $record->max_chance ?'N':($record->paid_installments == $record->total_installments && $record->currentmonthpaycount == 0 ? 'N':'Y')):($record->paid_installments <  $record->total_installments ?($record->allow_unpaid == 1  && $record->totalunpaid >0 && ($record->currentmonthpaycount-1) < $record->allow_unpaid_months ?'Y':($record->allow_advance == 1 && $record->advance_months > 0 && ($record->currentmonthpaycount -1) < $record->advance_months ?'Y':($record->currentmonthpaycount == 0 ? 'Y': 'N'))):'N')):'N'):'N'):'N'),
										'allow_pay'  => ($record->disable_payment != 1 && ($record->payment_status !=2) ? ($record->cur_month_pdc < 1 ? ($record->paid_installments <= $record->total_installments ?($record->is_flexible_wgt?($record->current_total_weight >= $record->max_weight || $record->current_chances_used >= $record->max_chance ?'N':($record->paid_installments == $record->total_installments && $record->currentmonthpaycount == 0 ? 'N':'Y')):($record->paid_installments <  $record->total_installments ?($record->allow_unpaid == 1  && $record->totalunpaid >0 && ($record->currentmonthpaycount-1) < $record->allow_unpaid_months ?'Y':($record->allow_advance == 1 && $record->advance_months > 0 && ($record->currentmonthpaycount -1) < $record->advance_months ?'Y':($record->currentmonthpaycount == 0 ? 'Y': 'N'))):'N')):'N'):'N'):'N'),
										'allowed_dues'  			=>($record->is_flexible_wgt == 1 ? 1:$allowed_due),
										'allowPayDisc'     			=> ($record->is_new=='Y'?($record->scheme_type==0?($record->paid_installments==0 && $record->firstPayDisc==1 ? 1 :0 ):($record->current_chances_used==0 && $record->paid_installments==0 && $record->firstPayDisc==1 ? 1: 0 )):0),
										'firstPayDisc' 		=> $record->firstPayDisc,
										'firstPayDisc_by' 	=> $record->firstPayDisc_by,
										'discount' 			=> $record->discount,
									 	'due_type' 		=> ($record->is_flexible_wgt == 1 ? 'ND':$due_type),
										'max_allowed_limit'  =>($record->is_flexible_wgt == 1 ? 1:$allowed_due), 
										'min_allowed_limit'  =>1, 
										'sel_due'  =>1,   //default selected due
										'pdc_payments'  =>($record->cur_month_pdc > 0 ? $this->get_postdated_payment($record->id_scheme_account) : NULL),
										'max_amount'             => round(($record->scheme_type==3 && $record->max_amount!=0 && $record->max_amount!='' && ($record->flexible_sch_type==1 ||$record->flexible_sch_type==2)? (($record->firstPayamt_maxpayable==1 ||$record->firstPayamt_as_payamt==1 )&&($record->paid_installments>0 ||$record->get_amt_in_schjoin==1) ? $record->firstPayment_amt:($record->max_amount - str_replace(',', '',$record->current_total_amount))):
							         	($record->scheme_type==3 && $record->max_weight!=0 && $record->max_weight!=''? (($record->max_weight - $record->current_total_weight)*$metal_rates[$record->rate_field]) : $record->max_amount))),
									);				
			}			
				  return $schemeAcc;
		}		
	}	
	function isPaid($paidByMonth,$Ym){
    	foreach($paidByMonth as $p){
    		if($Ym == $p['due_year']."-".str_pad($p['due_month'], 2, '0', STR_PAD_LEFT)){
    			return true;
    		}
    	}
    	return false;
    }
    function add_months_to_date($no_of_months, $date){
        $year_month = Date("Y-m", strtotime($date));
        $year_month_incremented = Date("Y-m", strtotime($year_month . " +".$no_of_months." Month "));
        $month_end_dt = strtotime('last day of this month', strtotime($year_month_incremented));
        return $month_end_dt;
    }
    //didn't show due Aftre Acc Apprvl in Admin chked //HH
	function get_payment_details($id_customer,$filter_allowpay)
	{  
		$showGCodeInAcNo = $this->config->item('showGCodeInAcNo'); 
		$filename = base_url().'api/rate.txt'; 
	    $data = file_get_contents($filename);
		$result['metal_rates'] = (array) json_decode($data);
	    $schemeAcc = array();
		$sql="Select s.allow_unpaid_in,s.allow_advance_in,sa.ref_no,s.set_as_min_from,s.set_as_max_from,s.rate_fix_by,s.rate_select,Date_Format(sa.start_date,'%Y-%m-%d') as join_date,
				cs.auto_debit_allow_app_pay,sa.auto_debit_status,s.auto_debit_plan_type, ad.auth_link,
				s.id_metal,if(s.id_metal=1,'goldrate_22ct',if(s.id_metal=2,'silverrate_1gm','')) as rate_field,
                maturity_type,curr_symb_html,date_format(CURRENT_DATE(),'%m') as cur_month,if((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) > s.total_installments, 
                (s.total_installments - COUNT(payment_amount)), 
                ifnull((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) - SUM(p.no_of_dues),if((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) > s.total_installments,s.total_installments,(PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m')))))) 
                as missed_ins,sa.avg_payable,s.avg_calc_ins,p.payment_status,
                PERIOD_DIFF(Date_Format(CURRENT_DATE(),'%Y%m'),Date_Format(sa.start_date,'%Y%m')) as current_pay_ins, 
		    	PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m')) as months_from_startdate,PERIOD_DIFF(Date_Format(sa.maturity_date,'%Y%m'),Date_Format(curdate(),'%Y%m')) as tot_ins,sa.maturity_date as maturity_date,
			    sg.group_code as scheme_group_code, UNIX_TIMESTAMP(Date_Format(sg.start_date,'%Y-%m-%d')) as group_start_date,  UNIX_TIMESTAMP(Date_Format(sg.end_date,'%Y-%m-%d')) as  group_end_date,  cs.has_lucky_draw,get_amt_in_schjoin,otp_price_fixing,fixed_rate_on,
                s.allowSecondPay,s.free_payment,firstPayamt_maxpayable,sa.firstPayment_amt,sa.firstpayment_wgt,sa.is_registered,CONCAT(if(".$showGCodeInAcNo."=1,if(has_lucky_draw = 1 && is_lucky_draw = 1,sg.group_code,s.code),'') ,' ',ifnull(sa.scheme_acc_number,'Not Allocated')) as chit_number,
			    s.gst_type,s.gst,sa.id_scheme_account,
			    IF(s.discount=1,s.firstPayDisc_value,0.00) as discount_val,s.firstPayDisc_by,s.firstPayDisc,sa.is_new,
			    s.id_scheme,br.id_branch, br.short_name, br.name as branch_name, 
			    c.id_customer,s.min_amount,s.max_amount,s.pay_duration,s.discount_type,s.discount_installment,s.discount,sa.id_branch as sch_join_branch,cs.is_branchwise_rate,
			    IFNULL(sa.account_name,if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname))) as account_name,
			    c.mobile,s.id_metal,
			    s.scheme_type,s.maturity_days,sa.maturity_date,firstPayamt_as_payamt,s.flexible_sch_type,s.one_time_premium,s.is_enquiry,
			    s.fix_weight,sa.fixed_metal_rate,sa.fixed_wgt,
			    s.code,
			    IFNULL(s.min_chance,0) as min_chance,
			    IFNULL(s.max_chance,0) as max_chance,
			    Format(IFNULL(s.max_weight,0),3) as max_weight, IF(s.max_weight=s.min_weight,'1','0') as wgt_type,
			    Format(IFNULL(s.min_weight,0),3) as min_weight,s.wgt_convert,
			    Date_Format(sa.start_date,'%d-%m-%Y') as start_date,s.flx_denomintion,
			    Date_Format(sa.maturity_date,'%d-%m-%Y') as maturity_date,
			    IF(s.scheme_type=0 OR s.scheme_type=2,TRIM(s.amount),IF(s.scheme_type=1 ,s.max_weight,if(s.scheme_type=3,if(flexible_sch_type = 3 ,  s.max_weight,if(firstPayamt_as_payamt=1,sa.firstPayment_amt ,TRIM(s.min_amount))),0))) as payable,
				round(IF(s.scheme_type=0 OR s.scheme_type=2,s.amount,IF(s.scheme_type=1 ,s.max_weight,
				if(s.scheme_type=3 && s.max_amount!='',s.max_amount,0)))) as max_amount,
				 (SELECT m.goldrate_22ct FROM metal_rates m  order by id_metalrates Desc LIMIT 1)as metal_rate,
				s.total_installments,IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),1,0) as  previous_paid,
 				IFNULL(IF(sa.is_opening=1,IFNULL(sa.paid_installments,0)+ IFNULL(if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues)),0), if(s.scheme_type = 1 and s.min_weight != s.max_weight or (s.scheme_type=3 and s.payment_chances=1 AND s.firstPayamt_as_payamt = 0) , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues))) ,0)as paid_installments,
 cs.branch_settings,
IFNULL(IF(sa.is_opening=1,IFNULL(balance_amount,0)+IFNULL(SUM(p.payment_amount * p.no_of_dues),0),IFNULL(SUM(p.payment_amount * p.no_of_dues),0)) ,0)
  as total_paid_amount,
FORMAT(sum(if(p.gst > 0,if((p.gst_type = 1),0,p.payment_amount-(p.payment_amount*(100/(100+p.gst)))),0)),0) as paid_gst,
IFNULL(IF(sa.is_opening=1,IFNULL(balance_weight,0)+IFNULL(SUM(p.metal_weight),0),IFNULL(SUM(p.metal_weight),0)),0.000)
 as total_paid_weight,
  if((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) > s.total_installments,   (s.total_installments - if(sa.is_opening = 1,(count(DISTINCT((Date_Format(p.date_payment,'%Y%m'))))+sa.paid_installments),count(DISTINCT((Date_Format(p.date_payment,'%Y%m')))))),ifnull(((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m')))+1) - IF(sa.is_opening=1,IFNULL(sa.paid_installments,0)+ IFNULL(if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues)),0), if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues))),if((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) > s.total_installments,s.total_installments,(PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m')))))) as totalunpaid,   
   IFNULL(if(Date_Format(max(p.date_payment),'%Y%m') = Date_Format(curdate(),'%Y%m'), (select SUM(ip.no_of_dues) from payment ip where Date_Format(ip.date_payment,'%Y%m') = Date_Format(curdate(),'%Y%m') and sa.id_scheme_account = ip.id_scheme_account),IF(sa.is_opening=1, if(Date_Format(sa.last_paid_date,'%Y%m') = Date_Format(curdate(),'%Y%m'), 1,0),0)),0) as currentmonthpaycount,  
  (select SUM(pay.no_of_dues) from payment pay where pay.id_scheme_account= sa.id_scheme_account and pay.due_type='AD' and (pay.payment_status=1 or pay.payment_status=2)) as currentmonth_adv_paycount,
  (select SUM(pay.no_of_dues) from payment pay where pay.id_scheme_account= sa.id_scheme_account and pay.due_type='PD' and (pay.payment_status=1 or pay.payment_status=2)) as currentmonth_pend_paycount,
IF(s.scheme_type =1 and s.max_weight!=s.min_weight,true,false) as is_flexible_wgt,  
			    round(IFNULL(cp.total_amount,0)) as  current_total_amount,
			    Format(IFNULL(cp.total_weight,0) + IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),(sa.last_paid_weight),0) ,3) as  current_total_weight,
			    IFNULL(cp.paid_installment,0)       as  current_paid_installments,
			   			    IFNULL(cp.chances,0) + IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),(sa.last_paid_chances),0) as  current_chances_used,
							if(s.scheme_type=3 && s.pay_duration=0 ,IFNULL(sp.chance,0) + IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),(sa.last_paid_chances),0),IFNULL(cp.chances,0) + IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),(sa.last_paid_chances),0)) as  current_chances_pay,
			    s.is_pan_required,
			    IFNULL(Date_Format(max(p.date_payment),'%d-%m-%Y'),IFNULL(IF(sa.is_opening=1,Date_Format(sa.last_paid_date,'%d-%m-%Y'),'')  ,0))                 as last_paid_date,
					IFNULL(PERIOD_DIFF(Date_Format(curdate(),'%Y%m'),Date_Format(max(p.date_payment),'%Y%m')),IF(sa.is_opening=1,PERIOD_DIFF(Date_Format(curdate(),'%Y%m'),Date_Format(sa.last_paid_date,'%Y%m')),0)) as last_paid_duration,
			    month(max(p.date_payment)) as last_paid_month,
				IF(sa.is_opening = 1 and s.scheme_type = 0 || s.scheme_type = 2,
				IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),false,true),
				true) AS previous_amount_eligible,
				count(pp.id_scheme_account) as cur_month_pdc,
				s.allow_unpaid,
				if(s.allow_unpaid=1,s.unpaid_months,0) as allow_unpaid_months,
				s.allow_advance,
				if(s.allow_advance=1,s.advance_months,0) as advance_months,
				if(s.allow_preclose=1,preclose_months,0) as allow_preclose_months,
				sa.disable_payment,sa.disable_pay_reason,s.charge,s.charge_type,s.charge_head,s.disable_sch_payment,
				cs.currency_name,
				cs.currency_symbol,metal_wgt_roundoff,metal_wgt_decimal
			From scheme_account sa
			Left Join scheme s On (sa.id_scheme=s.id_scheme)
			Left Join branch br  On (br.id_branch=sa.id_branch)
				Left Join metal m  On (m.id_metal = s.id_metal )
			Left Join scheme_group sg On (sa.group_code = sg.group_code )
			Left Join payment p On (sa.id_scheme_account=p.id_scheme_account and (p.payment_status=1 or p.payment_status=2 or p.payment_status=8))
			Left Join customer c On (sa.id_customer=c.id_customer and c.active=1)
			LEFT JOIN auto_debit_subscription ad on ad.id_scheme_account = sa.id_scheme_account and ad.status=1
			Left Join
			(	Select
				  sa.id_scheme_account,
				  COUNT(Date_Format(p.date_payment,'%Y%m')) as paid_installment,
				  COUNT(Date_Format(p.date_payment,'%Y%m')) as chances,
				  SUM(p.payment_amount) as total_amount,
				  SUM(p.metal_weight) as total_weight
				From payment p
				Left Join scheme_account sa on(p.id_scheme_account=sa.id_scheme_account and sa.active=1 and sa.is_closed=0)
				Where (p.payment_status=1 or p.payment_status=2 ) and  Date_Format(Current_Date(),'%Y%m')=Date_Format(p.date_payment,'%Y%m')
				Group By sa.id_scheme_account
			) cp On (sa.id_scheme_account=cp.id_scheme_account)	
			left join(Select sa.id_scheme_account, COUNT(Distinct Date_Format(p.date_payment,'%d%m')) as paid_installment,
					COUNT(Date_Format(p.date_payment,'%d%m')) as chance,
					SUM(p.payment_amount) as total_amount,
					SUM(p.metal_weight) as total_weight
					From payment p
					Left Join scheme_account sa on(p.id_scheme_account=sa.id_scheme_account and sa.active=1 and sa.is_closed=0)
					Where  (p.payment_status=2 or p.payment_status=1) and  Date_Format(Current_Date(),'%d%m')=Date_Format(p.date_payment,'%d%m')
					Group By sa.id_scheme_account)sp on(sa.id_scheme_account=sp.id_scheme_account)
			 Left Join postdate_payment pp On (sa.id_scheme_account=pp.id_scheme_account and (pp.payment_status=2 or pp.payment_status=7) and (Date_Format(pp.date_payment,'%Y%m')=Date_Format(curdate(),'%Y%m')))
				JOIN chit_settings cs 
		Where sa.active=1 and sa.is_closed = 0 and s.is_enquiry = 0 and c.id_customer='$id_customer' 
			Group By sa.id_scheme_account";
		//	echo $sql;exit;
		$records = $this->db->query($sql);
		if($records->num_rows()>0)
		{
			foreach($records->result() as $record)
			{
			     $current_installments = ($record->current_paid_installments == 0 ? $record->paid_installments+1 : $record->paid_installments);
			     $current_amt_min = '';
			     if(($record->get_amt_in_schjoin == 1 && $record->firstPayment_amt > 0) && ($record->set_as_min_from > 0 || $record->set_as_max_from > 0)){
			         
			        if($record->paid_installments > 1 && $record->firstPayment_amt != 0)
			          {
							if($record->current_total_amount < $record->firstPayment_amt)
							{
								$current_amt_min = 'Y';
							}
							else
							{
								$current_amt_min = 'N';
							}
			                
			             }    
			        if($current_installments >= $record->set_as_min_from)
			        {
			             if($record->paid_installments == 0)
			             {
			                   $record->min_amount = $record->firstPayment_amt;
			             }
			             else if($record->paid_installments > 1 && $record->paid_installments <= $record->set_as_max_from)
			             {
			                 $record->min_amount = $record->min_amount;
			             }
			        }
			        if($current_installments >= $record->set_as_max_from)
			        {
			            $record->min_amount = $record->flx_denomintion;
			             $record->max_amount = $record->firstPayment_amt;
			        }
			       
			    }
				// Calculate max payable [Applicable only for No advance, No pending enabled schemes]
				if((($record->scheme_type == 1 && $record->is_flexible_wgt == 1) || $record->scheme_type == 3) &&  $record->avg_calc_ins > 0){
					$current_installments = ($record->current_paid_installments == 0 ? $record->paid_installments+1 : $record->paid_installments);
					// Previous Ins == Average calc installment
					if(($current_installments-1 == $record->avg_calc_ins || $record->avg_payable > 0) && $record->avg_calc_ins > 0){
						if($record->avg_payable > 0){ // Already Average calculated, just set the value
							if($record->scheme_type == 1 && $record->is_flexible_wgt == 1 ){ // Weight - Flexible weight scheme
								// Set max payable
							}
							else if($record->scheme_type == 3 ){
								if($record->flexible_sch_type == 2){ // Flexible - Amount to weight [amount based]
									// Set max payable
								}
								elseif($record->flexible_sch_type == 3){ // Flexible - Amount to weight [weight based]
									$record->max_weight = $record->avg_payable;
								}
								elseif($record->flexible_sch_type == 1){ // Flexible - Amount to weight [weight based]
									$record->max_amount = $record->avg_payable;
								}
							}
						}else{ // Calculate Average , set the value and updte it in schemme_account table
							$paid_sql = $this->db->query("SELECT date(date_payment) as date_payment,sum(metal_weight) as paid_wgt,sum(payment_amount) as paid_amt FROM `payment` WHERE ( payment_status=1 or payment_status=2 ) and id_scheme_account=".$record->id_scheme_account." GROUP BY YEAR(date_payment), MONTH(date_payment)");
							$paid_wgt = 0;
							$paid_amt = 0;
							$paidByMonth = $paid_sql->result_array();
							foreach($paidByMonth as $p){
								$paid_wgt = $paid_wgt + $p['paid_wgt'];
								$paid_amt = $paid_amt + $p['paid_amt'];
							}
							if($record->scheme_type == 1 && $record->is_flexible_wgt == 1 ){ // Weight - Flexible weight scheme
								// Set max payable
							}
							else if($record->scheme_type == 3 ){
								if($record->flexible_sch_type == 2){ // Flexible - Amount to weight [amount based]
									// Set max payable
								}
								elseif($record->flexible_sch_type == 3){ // Flexible - Amount to weight [weight based]
									$avg_payable = number_format($paid_wgt/$record->avg_calc_ins,3);
									$record->max_weight = $avg_payable;
								}
								elseif($record->flexible_sch_type == 1){ // Flexible - Amount Based
									$avg_payable = $paid_amt/$record->avg_calc_ins;
									$record->max_amount = $avg_payable;
								}
							}
							$updData = array( "avg_payable" => $avg_payable, "date_upd" => date("Y-m-d") );
							$this->db->where('id_scheme_account',$record->id_scheme_account); 
		 					$this->db->update("scheme_account",$updData);
						} 
					}
					/*else if($current_installments > $record->avg_calc_ins){ // Previous Ins > Average calc installment
						if($record->scheme_type == 1 && $record->is_flexible_wgt == 1 ){ // Weight - Flexible weight scheme
							// Set max payable
						}
						else if($record->scheme_type == 3 ){
							if($record->flexible_sch_type == 2){ // Flexible - Amount to weight [amount based]
								// Set max payable
							}
							elseif($record->flexible_sch_type == 3){ // Flexible - Amount to weight [weight based]
								$record->max_weight = $record->avg_payable; 
							}						
						}
					}*/
				}
				$allowed_due = 0;
				$due_type = '';
				$checkDues = TRUE;
				$allowSecondPay = FALSE;
				if($record->rate_select == 0 || $record->rate_select == 1)
				{
				    $metal_rates=$this->get_metalrate($record->sch_join_branch,$record->is_branchwise_rate);//For branchwise rate
				}
				else if($record->rate_select == 2 || $record->rate_select == 1)
				{
				    $metal_rates = $this->get_ratesByJoin($record->join_date);
				}
				
				$disclaimer = "";
				if($record->one_time_premium ==1 && $record->otp_price_fixing == 1)
				{
				    $disclaimer = "Disclaimer : 

                    On fixing the Plan 2 Gold Rate
                    Customer has the option of fixing the gold rate at any time from the date of joining to date of maturity on the prevailing rate.
                    Gold rate once fixed cannot be changed under any circumstance.
                    In case the gold rate has not been fixed during the tenure of this scheme as per agreed terms, then the rate prevailing on the date of delivery would be applicable.";
				}
				if($record->has_lucky_draw == 1 )
				{ 
					if( $record->group_start_date == NULL && $record->paid_installments > 1)
					{ // block 2nd payment if scheme_group_code is not updated 
						$checkDues = FALSE; 
					}
				    else if($record->group_start_date != NULL)
					{ // block before start date and payment after end date 
						 if($record->group_end_date >= time() && $record->group_start_date <= time() ){
        				 		$checkDues = TRUE;
        				 }else{
        					$checkDues = FALSE;
        				 }
					}
				}
				if($record->maturity_days!=null)
				{
			       $current_date =date("Y-m-d");
			       $maturity_date=$record->maturity_date;
			       if(strtotime($current_date) <= strtotime($maturity_date)) 
                    { 
                        $checkDues=TRUE;
                        if($record->maturity_type == 2){ // 1 - Flexible[Can pay installments and close], 2 - Fixed Maturity, 3 - Fixed Flexible[Increase maturity if has Default]
                         	if(($record->missed_ins+$record->paid_installments)<=$record->total_installments)
            				{
            				    $checkDues=TRUE;
            				}else{
            				    $checkDues = FALSE;
            				}
                        }
                    }
                    else
                    {
                        $checkDues=FALSE;
                    }
				}
				// Update Maturity Date in scheme_account table if maturity date is flexible
        		if($record->maturity_type == 3){  // 1 - Flexible[Can pay installments and close], 2 - Fixed Maturity, 3 - Fixed Flexible[Increase maturity if has Default]
    			    $paid_sql = $this->db->query("SELECT due_month,due_year FROM `payment` WHERE ( payment_status=1 or payment_status=2 ) and id_scheme_account=".$record->id_scheme_account." GROUP BY due_month, due_year order by due_year,due_month");
    			    $paidByMonth = $paid_sql->result_array();
    			    $skipped_months = 0;
                    for($i = 0; $i >= 0 ;$i++){
                    	/*
                    	$date = date('Y-m-d', strtotime("+".$i." months", strtotime($record->start_date)));
                    	$Ym = date('Y-m', strtotime("+".$i." months", strtotime($record->start_date)));
                    	*/
                    	$Ym = date('Y-m', $this->add_months_to_date($i,$record->start_date));
                    	if($Ym != date("Y-m")){
                    		$isPaid = $this->isPaid($paidByMonth,$Ym);
                    		$skipped_months = $skipped_months + ($isPaid ? 0 : 1);
                    		//echo $Ym."--".date("Y-m")."--".$skipped_months."<br/>";
                    	}
                    	else if($Ym == date("Y-m")){ // Quit Loop
                    		$i = -2;
                    	}
                    } 
                    $maturity =  date('Y-m-d', strtotime("+".($record->total_installments+$skipped_months)." months", strtotime($record->start_date)));
                    /*$no_of_months = ( $record->total_installments+$skipped_months );
                    $maturity = date('Y-m-d', $this->add_months_to_date($no_of_months,$record->start_date));*/
                    if($record->maturity_date != $maturity){
        			    $updData = array( "maturity_date" => $maturity, "date_upd" => date("Y-m-d") );
        				$this->db->where('id_scheme_account',$record->id_scheme_account); 
         				$this->db->update("scheme_account",$updData);
                    }
        		}
				if($checkDues){
    				if($record->maturity_type != 2){  // 1 - Flexible[Can pay installments and close], 2 - Fixed Maturity, 3 - Fixed Flexible[Increase maturity if has Default] 
    				$proceed = ($record->scheme_type == 3 ? ($record->paid_installments == 0 ? FALSE : TRUE) : ($record->paid_installments > 0 || $record->totalunpaid >0) );
    					if($proceed ){
    						if($record->currentmonthpaycount == 0){  // current month not paid (allowed pending due + current due)
    							if($record->allow_unpaid == 1){
    								if($record->allow_unpaid_months > 0 && ($record->total_installments - $record->paid_installments) >=  $record->allow_unpaid_months && $record->totalunpaid >0){
    									if(($record->total_installments - $record->paid_installments) ==  $record->allow_unpaid_months){
    										$allowed_due = ($record->totalunpaid < $record->allow_unpaid_months ? $record->totalunpaid : $record->allow_unpaid_months) ;  
    									    $due_type = 'PD'; //  pending
    									}
    									else{
    										$allowed_due =  ($record->totalunpaid < $record->allow_unpaid_months ? $record->totalunpaid : $record->allow_unpaid_months)+1 ;  
    									    $due_type = 'PN'; // normal and pending
    									}
    								}
    								else{
    								     $allowed_due =  1;
    								     $due_type = 'ND'; // normal due
    								}
    							}
    							else{
        							// current month not paid (allowed advance due + current due)
        							if($record->allow_advance ==1){ // check allow advance
                						if($record->advance_months > 0 && $record->advance_months <= ($record->total_installments - $record->paid_installments)){
                							if(($record->total_installments - $record->paid_installments) ==  $record->advance_months){
                									 $allowed_due =  $record->advance_months;
                									 $due_type = 'AN'; // advance and normal
                								}
                								else{
                									$allowed_due =  $record->advance_months+1 ;  
                								     $due_type = 'AN'; // advance and normal
                								}
                						}
                						else{
                							 $allowed_due =  1;
                							 $due_type = 'ND'; // normal due
                						}
                					}
                					else{
                						 $allowed_due =  1;
                						 $due_type = 'ND'; // normal due
                					}
    							}
    						}
    						else{ 	//current month paid
    						    if($record->free_payment == 1 && $record->allowSecondPay == 1 && $record->paid_installments == 1){
    								$allowed_due =  1 ;
        							$due_type = 'AD'; // adv due
        							$allowSecondPay = TRUE;
    							}else{
        							if($record->allow_unpaid == 1 && $record->allow_unpaid_months >0 && $record->totalunpaid >0 && ($record->currentmonthpaycount-1) < $record->allow_unpaid_months){  
        								// can pay previous pending dues if attempts available 
        								if($record->totalunpaid > $record->allow_unpaid_months){
        									 $allowed_due =  $record->allow_unpaid_months ;
        									 $due_type = 'PD'; // pending due
        								}
        								else{ 
        									 $allowed_due =  $record->totalunpaid;
        									 $due_type = 'PD'; // pending due
        								}
        							}
        							else{  // check allow advance
        								if(($record->allow_advance == 1) && ($record->advance_months > 0) && (($record->currentmonth_adv_paycount) < $record->advance_months)){
        									if(($record->advance_months + $record->paid_installments) <= $record->total_installments){
        										 $allowed_due =  ($record->advance_months - ($record->currentmonth_adv_paycount));
        										 $due_type = 'AD'; // advance due
        									}
        									else{
        										 $allowed_due =  ($record->total_installments - $record->paid_installments);
        										 $due_type = 'AD'; // advance due
        									}
        								}
        								else{ // have to check
        									 $allowed_due =  0;
        									 $due_type = ''; // normal due
        								}
        							}
    							}
    						}
    					}
    					else{  // check allow advance and add due with currect month (allowed advance due + current due)
    					    if($record->scheme_type == 3){ // Donot allow advance/pending on first due payment [Flexible scheme]
    					        $allowed_due =  1;
        						$due_type = 'ND'; // normal due
    					    }else{
        						if($record->allow_advance ==1){ // check allow advance
        							if($record->advance_months > 0 && $record->advance_months <= ($record->total_installments - $record->paid_installments)){
        								if(($record->total_installments - $record->paid_installments) ==  $record->advance_months){
        										 $allowed_due =  $record->advance_months;
        										 $due_type = 'AN'; // advance and normal
        									}
        									else{
        										$allowed_due =  $record->advance_months+1 ;  
        									     $due_type = 'AN'; // advance and normal
        									}
        							}
        							else{
        								 $allowed_due =  1;
        								 $due_type = 'ND'; // normal due
        							}
        						}
        						else{
        							 $allowed_due =  1;
        							 $due_type = 'ND'; // normal due
        						}
    					    }
    					}
    				}else{ // Fixed Maturity Date
    				    if($record->maturity_date !=NULL && $record->maturity_date!='') // Jewelone
        				{
        				     $due =  $record->tot_ins - $record->months_from_startdate; // months_from_startdate -> No. of months from start date
        				     if($record->advance_months>$due)
        				     {
        				         $allow_due=$record->advance_months;
        				     }
        				     else
        				     {
        				         $allow_due=$due;
        				     }
        				}
        				if(!empty($record->maturity_days) && $record->allow_unpaid == 0) // ** Advance Only. No Pending allowed. ** //
                        {
        	                if( $record->advance_months > 0){
        		                if($record->current_paid_installments == 0 )  // Current month not Paid (Current+Advance)
        		                {
        			                $allowed_due = $record->total_installments-$record->current_pay_installemnt;
        			                $due_type='AN';
        		                }
        		                else // Current month Paid (Advance)
        		                {
        			                $allowed_due = $record->total_installments - ($record->current_pay_installemnt+$record->current_paid_installments);
        			                $due_type='AD';
        		                }
        	                }
                        } 
    				}
				}
				// allow pay
	            if($record->scheme_type == 3){
	                if($record->one_time_premium == 0){
    					// old 1 // $allow_pay  = ($record->disable_payment != 1 && $record->payment_status !=2  && $record->paid_installments <= $record->total_installments ?   ($record->flexible_sch_type == 3 || $record->flexible_sch_type == 4 ? ($record->current_total_weight >= $record->max_weight || $record->current_chances_used >= $record->max_chance ?($record->paid_installments <  $record->total_installments ?($record->allow_unpaid == 1  && $record->totalunpaid >0 && ($record->currentmonthpaycount-1) < $record->allow_unpaid_months ?'Y':($record->allow_advance == 1 && $record->advance_months > 0 && ($record->currentmonthpaycount -1) < $record->advance_months ?'Y':($record->currentmonthpaycount == 0 ? 'Y': 'N'))):'N'):'Y') : ($record->current_total_amount >= $record->max_amount || ($record->firstPayment_amt >0 && $record->current_total_amount >= $record->firstPayment_amt) || $record->current_chances_used >= $record->max_chance ?($record->paid_installments <  $record->total_installments ?($record->allow_unpaid == 1  && $record->totalunpaid >0 && ($record->currentmonthpaycount-1) < $record->allow_unpaid_months ?'Y':($record->allow_advance == 1 && $record->advance_months > 0 && ($record->currentmonthpaycount -1) < $record->advance_months ?'Y':($record->currentmonthpaycount == 0 ? 'Y': 'N'))):'N'):'Y')):'N');
    					$allow_pay  = ($record->disable_payment != 1 && $record->payment_status !=2 && $record->paid_installments <= $record->total_installments ?   ($record->flexible_sch_type == 3 || $record->flexible_sch_type == 4 ? ($record->current_total_weight >= $record->max_weight || $record->current_chances_used >= $record->max_chance ?($record->paid_installments <  $record->total_installments ?($record->allow_unpaid == 1  && $record->totalunpaid >0 && ($record->currentmonthpaycount-1) < $record->allow_unpaid_months ?'Y':($record->allow_advance == 1 && $record->advance_months > 0 && ($record->currentmonthpaycount -1) < $record->advance_months ?'Y':($record->currentmonthpaycount == 0 ? 'Y': 'N'))):'N'):'Y') : ($record->current_total_amount >= $record->max_amount || ($record->firstPayment_amt >0 && $record->current_total_amount >= $record->firstPayment_amt) || ($record->current_chances_used >= $record->max_chance && $record->max_chance > 0) ?($record->paid_installments <  $record->total_installments ?($record->allow_unpaid == 1  && $record->totalunpaid >0 && ($record->currentmonthpaycount-1) < $record->allow_unpaid_months ?'Y':($record->allow_advance == 1 && $record->advance_months > 0 && ($record->currentmonthpaycount -1) < $record->advance_months ?'Y':($record->currentmonthpaycount == 0 || $record->current_chances_used < $record->max_chance ? 'Y': 'N'))):'N'):'Y')):'N');
				    }else{
				        $allow_pay  = ($record->disable_payment != 1 && $record->payment_status !=2  && $record->paid_installments == 0 && $record->is_enquiry == 0 ? ($record->flexible_sch_type == 1 || $record->flexible_sch_type == 4 || $record->flexible_sch_type == 5 ? ($record->current_total_amount >= $record->max_amount || $record->current_chances_used >= $record->max_chance ?'N':'Y') : 'N'):'N');
				    }
	            }else{
						$allow_pay  = ($record->disable_payment != 1 && ($record->payment_status !=2) ? ($record->cur_month_pdc < 1 ? ($record->paid_installments <= $record->total_installments ?($record->is_flexible_wgt?($record->current_total_weight >= $record->max_weight || $record->current_chances_used >= $record->max_chance ?'N':'Y'):($record->paid_installments <  $record->total_installments ?($record->allow_unpaid == 1  && $record->totalunpaid >0 && ($record->currentmonthpaycount-1) < $record->allow_unpaid_months ?'Y':($record->allow_advance == 1 && $record->advance_months > 0 && ($record->currentmonthpaycount -1) < $record->advance_months ?'Y':($record->currentmonthpaycount == 0 ? 'Y': 'N'))):'N')):'N'):'N'):'N');
				}
				$dates= date('d-m-Y');
				$min_amount = round(($record->scheme_type==3 && $record->min_amount!=0 && $record->min_amount!='' ? (($record->firstPayamt_maxpayable==1 || $record->firstPayamt_as_payamt==1) && $record->firstPayment_amt > 0 ? $record->firstPayment_amt : ($record->max_amount - str_replace(',', '',$record->current_total_amount) > $record->min_amount ? $record->min_amount : $record->max_amount - str_replace(',', '',$record->current_total_amount)) ):($record->scheme_type==3 && $record->min_weight!=0 && $record->min_weight!=''? (($record->min_weight)*$metal_rates[$record->rate_field]) : $record->min_amount)));
				$max_amount = round(($record->scheme_type==3 && $record->max_amount!=0 && $record->max_amount!='' ? (($record->firstPayamt_maxpayable==1 || $record->firstPayamt_as_payamt==1)&&($record->paid_installments>0 ||$record->get_amt_in_schjoin==1) ?  $record->firstPayment_amt:($record->max_amount - str_replace(',', '',$record->current_total_amount))): ($record->scheme_type==3 && $record->max_weight!=0 && $record->max_weight!=''? (($record->firstpayment_wgt > 0 ? $record->firstpayment_wgt : ($record->max_weight - $record->current_total_weight) )*$metal_rates[$record->rate_field]) : $record->max_amount)));
				if($maxamount == 0 && $record->scheme_type==3 && $record->set_as_min_from > 0 && $record->set_as_min_from > 0)
				{
				    $allow_pay = 'N';
				}
				$eligible_wgt = ( $record->flexible_sch_type == 3 || $record->scheme_type == 1 ? ($record->min_weight == $record->max_weight ? $record->min_weight : ($record->max_weight - $record->current_total_weight)) : ($record->max_amount-str_replace(',', '',$record->current_total_amount))/$metal_rates[$record->rate_field]);
				// metal_wgt_decimal = 2 means only 2 decimals are allowed for metal wgt, hence bcdiv() is used to make the weight to 2 decimals and 0 is appended as last digit.
    			$decimal = $record->metal_wgt_decimal;   
                $round_off = $record->metal_wgt_roundoff ; 
                $elig_wgt =  ($round_off == 1 ? bcdiv($eligible_wgt,1,$decimal) : $eligible_wgt );
                
                // Check subscription settings if payment allowed
    			if($allow_pay == 'Y' && $record->auto_debit_status > 0){
    				// auto_debit_allow_app_pay =>	1 - Allow app payment 
    				if($record->auto_debit_allow_app_pay == 0){  //0 - Block app payment
    					$allow_pay = 'N';
    				}else if($record->auto_debit_allow_app_pay == 2){//2-Allow app payment when subscription status is not ACTIVE
    					if($record->auto_debit_status == 3){ //Active
    						$allow_pay = 'N';
    					}
    				}
    			}
    			$last_transaction = $this->getLastTransaction($record->id_scheme_account);
    			/*if($record->ref_no == null || $record->ref_no == ''){
    			    $allow_pay = 'N';
    			}*/
    			$allowExtraPayinWeb = ($record->allow_unpaid_in == 0 || $record->allow_unpaid_in == 4 || $record->allow_advance_in == 0 || $record->allow_advance_in == 4 ) ? TRUE : FALSE;
				
                if( ($filter_allowpay == 'Y' && $allow_pay == 'Y') || $filter_allowpay == "" ){
                    $schemeAcc[] = array(
                    					'auto_debit_allow_app_pay'	=> $record->auto_debit_allow_app_pay,
				                        'auto_debit_status'			=> $record->auto_debit_status,
				                        'auto_debit_plan_type'		=> $record->auto_debit_plan_type,
				                        'auth_link'                 => $record->auth_link,
                                        'metal_rates' 				=> $metal_rates,
                                        'rate_field' 				=> $record->rate_field,
                                        'id_metal' 				    => $record->id_metal,
                                        'is_enquiry' 				    => $record->is_enquiry,
				                        'scheme_type' 				=> $record->scheme_type,
										'eligible_weight' 		    => $elig_wgt, 
										'curr_symb_html'            => $record->curr_symb_html,
										'gst_type'					=> $record->gst_type,
										'pay_duration' 		        => $record->pay_duration,
										'branch_settings' 		    => $record->branch_settings,
										'disable_payment'           => $record->disable_payment,
										'disable_pay_reason'        => $record->disable_pay_reason,
										'min_chance' 		        => $record->min_chance,
										'max_chance' 		        => $record->max_chance, 
                                        'min_amount'            	=> $min_amount,
										'max_amount'            	=> $max_amount,
										'firstPayment_amt' 		      => $record->firstPayment_amt,
										'firstPayamt_maxpayable' 		   => $record->firstPayamt_maxpayable,
										'flx_denomintion' 		        => $record->flx_denomintion,
										'firstPayamt_as_payamt' 		=> $record->firstPayamt_as_payamt,
										'flexible_sch_type' 		    => $record->flexible_sch_type,
										'get_amt_in_schjoin' 		    => $record->get_amt_in_schjoin,
										'one_time_premium' 		         => $record->one_time_premium,
										'otp_price_fixing' 		         => $record->otp_price_fixing,
										//'multiply_value' 		        => NULL,
										'fixed_wgt' 		        => $record->fixed_wgt,
										'fixed_rate' 		        => $record->fixed_metal_rate,
										'maturity_date' 		    => $record->maturity_date,
										'fixed_metal_rate' 		    =>($record->fixed_rate_on==NULL ?'NO' :'YES') ,
										'metal_rate'                => $metal_rates[$record->rate_field],
									    'join_date'                 => $record->join_date,
										'gst' 						=> $record->gst,
										'paid_gst' 					=> $record->paid_gst,
										'id_branch' 				=> $record->sch_join_branch,
										'short_name' 				=> $record->short_name,
										'branch_name' 				=> $record->branch_name,
										'currentmonthpaycount' 		=> $record->currentmonthpaycount,
										'totalunpaid' 				=> $record->totalunpaid,
										'id_scheme_account' 		=> $record->id_scheme_account,
										'max_wgt_rate' 				=> ($record->is_flexible_wgt == 1?($record->max_weight - $record->current_total_weight):$record->max_weight) * $metal_rates[$record->rate_field],
										'charge_head' 				=> $record->charge_head,
										'charge_type' 				=> $record->charge_type,
										'charge' 					=> $record->charge,
										'chit_number' 				=> ($record->chit_number==' Not Allocated' ?$this->config->item('default_acno_label'):$record->chit_number),
										'account_name' 				=> $record->account_name,
										'start_date' 				=> $record->start_date,
										'mobile' 					=> $record->mobile,
										'is_flexible_wgt' 	    	=> $record->is_flexible_wgt,
										'currency_symbol' 			=> $record->currency_symbol,
									    //'payable'                 => ($record->scheme_type==3 && $record->min_amount!=0 &&($record->flexible_sch_type==1 || $record->flexible_sch_type==2) && $record->min_amount!=''?((($record->firstPayamt_maxpayable==1||$record->firstPayamt_as_payamt==1)&&($record->paid_installments>0||$record->get_amt_in_schjoin==1)||($record->is_registered==1))?round($record->firstPayment_amt) : $min_amount):($record->scheme_type==3 && ($record->max_weight!=0 || $record->max_weight!='')? round(($record->max_weight - $record->current_total_weight)*$record->metal_rate) : $record->payable)),
										'payable'                   => ($record->scheme_type==3 && $record->min_amount!=0 &&($record->flexible_sch_type==1 || $record->flexible_sch_type==2) && $record->min_amount!=''?((($record->firstPayamt_maxpayable==1||$record->firstPayamt_as_payamt==1)&&($record->paid_installments>0||$record->get_amt_in_schjoin==1 || $record->is_registered==1 ))?round($record->firstPayment_amt) : $min_amount):($record->scheme_type==3 && ($record->max_weight!=0 || $record->max_weight!='')? round(($record->max_weight - $record->current_total_weight)*$record->metal_rate) : $record->payable)),
										'code' 						=> ($record->has_lucky_draw == 1 ?  $record->scheme_group_code : $record->code),
										'total_installments'		=> $record->total_installments,
										'paid_installments' 		=> $record->paid_installments,
										'total_paid_amount' 		=> $record->total_paid_amount,
										'total_paid_weight' 		=> $record->total_paid_weight,
										'current_total_amount' 		=> $record->current_total_amount,
										'current_paid_installments'	=> $record->current_paid_installments,
										'current_chances_used' 		=> $record->current_chances_used,
										'current_chances_pay'       => $record->current_chances_pay,
										'allow_unpaid_months' 			=> $record->allow_unpaid_months,
										'last_paid_duration' 			=> $record->last_paid_duration,
										'last_paid_date' 			=> $record->last_paid_date,
										'current_date' 		    => $dates,
										'last_paid_month' 			=> ($record->last_paid_month!='' || $record->last_paid_month!=NULL ? $record->last_paid_month : 0),
										'is_pan_required' 			=> $record->is_pan_required,
										'wgt_convert' 			   => $record->wgt_convert, 
										'last_transaction'  	    => $last_transaction,
										'isPaymentExist' 			=> $this->isPaymentExist($record->id_scheme_account),
										'isPendingStatExist' 		=> $this->isPendingStatExist($record->id_scheme_account),
										'max_weight' 				=> $record->max_weight,
										'min_weight' 				=> $record->min_weight,
										'current_total_weight' 		=> $record->current_total_weight,
										'previous_amount_eligible' 	=> $record->previous_amount_eligible,
										'cur_month_pdc' 			=> $record->cur_month_pdc,
										'allow_pay' 			=> $allow_pay,
									    //'allowed_dues'  			=>($record->is_flexible_wgt == 1 ? 1:$allowed_due),
									    'allowed_dues'  			=>($record->is_flexible_wgt == 1 ? 1: ($allowExtraPayinWeb ? $allowed_due:1)),
										'allowPayDisc'      => ($record->discount == 1 ? ($record->discount_type == 0? 'All': $record->discount_installment ) : 0),
										'firstPayDisc' 		=> $record->firstPayDisc,
										'firstPayDisc_by' 	=> $record->firstPayDisc_by,
										'discount_val' 			=> $record->discount_val,
									 	'due_type' 		=> ($record->is_flexible_wgt == 1 ? 'ND':$due_type),
										'max_allowed_limit'  =>($record->is_flexible_wgt == 1 ? 1:$allowed_due),
										'sel_due'  =>1,   //default selected due
										'pdc_payments'  =>($record->cur_month_pdc > 0 ? $this->get_postdated_payment($record->id_scheme_account) : NULL),
										'set_as_min_from' => $record->set_as_min_from,
										'set_as_max_from' => $record->set_as_max_from,
										'is_amt_min'            => $current_amt_min,
										'rate_fix_by'   => $record->rate_fix_by,
										'rate_select'  => $record->rate_select,
										'disclaimer'   => $disclaimer
									);	 
                }
			}		
			return array('chits' => $schemeAcc);
		}else{
		    return array('chits' => []);
		}		
	}
	function get_postdated_payment($id_scheme_account)
	{
		$sql="Select
				   pp.id_scheme_account,
				   date(pp.date_payment) as date_payment,
				   pp.pay_mode,
				   pp.cheque_no,
				   pp.payee_acc_no,
				   b.bank_name,
				   b.short_code,
				   pp.payee_branch,
				   pp.payee_ifsc,
				   pp.amount
			From postdate_payment pp
			Left Join bank b On (pp.payee_bank=b.id_bank)
			Where (Date_Format(Current_Date(),'%Y%m')=Date_Format(pp.date_payment,'%Y%m'))
			       And (pp.payment_status = 2 or pp.payment_status=7)
			       And pp.id_scheme_account='".$id_scheme_account."'";
		return $this->db->query($sql)->row_array();	       
	}
	function get_metalrate($id_branch,$is_branchwise_rate)
	{
	    if($is_branchwise_rate==1) // Branchwise rate
		{
		    if($id_branch!='' && $id_branch!=0)        
		    {
                $sql1="SELECT  b.name as name,m.mjdmagoldrate_22ct,m.goldrate_22ct,m.goldrate_24ct,m.silverrate_1gm,m.silverrate_1kg,m.mjdmasilverrate_1gm,platinum_1g,
                Date_format(m.updatetime,'%d-%m%-%Y %h:%i %p')as updatetime   
                FROM metal_rates m 
                LEFT JOIN branch_rate br on br.id_metalrate=m.id_metalrates
                left join branch b on b.id_branch=br.id_branch
                ".($id_branch!='' ?" WHERE br.id_branch=".$id_branch."" :'')." ORDER by br.id_metalrate desc LIMIT 1";
		    }
		    else
		    {
		        $sql1="SELECT  b.name as name,m.mjdmagoldrate_22ct,m.goldrate_22ct,m.goldrate_24ct,m.silverrate_1gm,m.silverrate_1kg,m.mjdmasilverrate_1gm,platinum_1g,
                Date_format(m.updatetime,'%d-%m%-%Y %h:%i %p')as updatetime   
                FROM metal_rates m 
                LEFT JOIN branch_rate br on br.id_metalrate=m.id_metalrates
                left join branch b on b.id_branch=br.id_branch
                ORDER by br.id_metalrate desc LIMIT 1";   
		    }
		}
		else{
			$sql1="SELECT  m.mjdmagoldrate_22ct,m.goldrate_22ct,m.goldrate_24ct,m.silverrate_1gm,m.silverrate_1kg,m.mjdmasilverrate_1gm,platinum_1g,
					Date_format(m.updatetime,'%d-%m%-%Y %h:%i %p')as updatetime   FROM metal_rates m 
					WHERE m.id_metalrates=( SELECT max(m.id_metalrates) FROM metal_rates m )";
		}
		return $this->db->query($sql1)->row_array();
	}
	function get_scheme_detail($id_scheme_account)
	{
		$sql="select
				   sa.id_scheme_account,
				   s.id_scheme,
				   c.id_customer,
				   IF(sa.scheme_acc_number !='',CONCAT(s.code,' ',sa.scheme_acc_number),'') as chit_number,
				   s.scheme_name,
				   s.scheme_type,flexible_sch_type ,
				   s.code,
				   IF(s.scheme_type=0,s.amount,IF(s.scheme_type=1,s.max_weight,s.amount)) as payable,
				   IF(s.scheme_type=1,s.max_weight,0) as  eligible_weight
			from scheme_account sa
			Left Join scheme s On (sa.id_scheme = s.id_scheme)
			Left Join customer c On (sa.id_customer = c.id_customer)
			Where sa.id_scheme_account='$id_scheme_account'";
		$result = $this->db->query($sql);	
		return $result->row_array();	
	}
	//individual scheme and payment details
	function chit_scheme_detail($id_scheme_account)
	{
	    $result = array();
	    $showGCodeInAcNo = $this->config->item('showGCodeInAcNo'); 
		//scheme detail
		$sql_scheme ="Select
					sa.fixed_wgt,sa.fixed_metal_rate,IF(rate_fixed_in = 1, 'Web App', IF(rate_fixed_in = 2, 'Mobile App', IF(rate_fixed_in = 3, 'Offline', '-'))) as rate_fixed_in,
					Date_Format(sa.fixed_rate_on,'%d-%m-%Y') as fixed_rate_on,IF(sa.fixed_rate_on is NULL,'NO','YES') as is_rate_fixed, 
				    sa.id_scheme_account,firstPayamt_maxpayable,sa.firstPayment_amt,sa.is_registered,sa.fixed_metal_rate,sa.fixed_wgt, Date_Format(sa.maturity_date,'%d-%m-%Y') as maturity_date,s.flexible_sch_type,
				    s.id_scheme,s.one_time_premium,firstPayamt_as_payamt,get_amt_in_schjoin,
				    c.id_customer,s.has_prize,s.has_gift,
				    CONCAT(if(".$showGCodeInAcNo."=1,if(has_lucky_draw = 1 && is_lucky_draw = 1,sg.group_code,s.code),'') ,' ',ifnull(sa.scheme_acc_number,'Not Allocated')) as chit_number,
				    IFNULL(sa.account_name,if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname))) as account_name,
				    c.mobile,
				    s.scheme_name,
				    s.scheme_type,
				    s.code,
				    IFNULL(s.min_chance,0) as min_chance,
				    IFNULL(s.max_chance,0) as max_chance,
				    Format(IFNULL(s.max_weight,0),3) as max_weight,
				    Format(IFNULL(s.min_weight,0),3) as min_weight,
				    Date_Format(sa.start_date,'%d-%m-%Y')start_date,
				    sa.disable_payment,sa.disable_pay_reason,
				    Format(IF(s.scheme_type=0 OR s.scheme_type=2,s.amount,IF(s.scheme_type=1 ,s.max_weight,if(s.scheme_type=3,if((s.min_amount!='' && s.max_amount!='0'),s.max_amount,round((s.max_weight * (SELECT m.goldrate_22ct FROM metal_rates m  order by id_metalrates Desc LIMIT 1)))),0))),2) as payable,
				    s.total_installments,
                    IFNULL(IF(sa.is_opening=1,IFNULL(sa.paid_installments,0)+ IFNULL(if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues)),0), if(s.scheme_type = 1 and s.min_weight != s.max_weight or (s.scheme_type=3 and s.payment_chances=1) , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues))) ,0)as paid_installments,
                    IFNULL(IF(sa.is_opening=1,IFNULL(balance_amount,0)+IFNULL(SUM(p.payment_amount * p.no_of_dues),0),IFNULL(SUM(p.payment_amount * p.no_of_dues),0)) ,0)
                    as total_paid_amount,
                    FORMAT(sum(if(p.gst > 0,if( p.gst_type = 1,0,p.payment_amount-(p.payment_amount*(100/(100+p.gst)))),0)),0) as gst,
                    cp.paid_installment,
                    IFNULL(IF(sa.is_opening=1,IFNULL(balance_weight,0)+IFNULL(SUM(p.metal_weight),0),IFNULL(SUM(p.metal_weight),0)),0.000)
                    as total_paid_weight,
				    Format(IFNULL(cp.total_amount,0),2) as  current_total_amount,
				    Format(IFNULL(cp.total_weight,0),3) as  current_total_weight,
				    IFNULL(cp.paid_installment,0)       as  current_paid_installments,
				    IFNULL(cp.chances,0)                as  current_chances_used,
				    s.is_pan_required,
				    max(p.date_payment)                 as last_paid_date,
					sa.active as chit_active,
					sa.is_closed as is_closed, cs.has_lucky_draw,sa.group_code as scheme_group_code
				From scheme_account sa
				Left Join scheme s On (sa.id_scheme=s.id_scheme)
				Left Join scheme_group sg On (sa.group_code = sg.group_code )
				Left Join payment p On (sa.id_scheme_account=p.id_scheme_account and (p.payment_status=1 or p.payment_status=2 or p.payment_status=8) )
				Left Join customer c On (sa.id_customer=c.id_customer and c.active=1)
				Left Join
					(	Select
						  sa.id_scheme_account,
						  COUNT(Distinct Date_Format(p.date_payment,'%Y%m')) as paid_installment,
						  COUNT(Date_Format(p.date_payment,'%Y%m')) as chances,
						  SUM(p.payment_amount) as total_amount,
						  SUM(p.metal_weight) as total_weight
						From payment p
						Left Join scheme_account sa on(p.id_scheme_account=sa.id_scheme_account and sa.active=1 and sa.is_closed=0)
						Where (p.payment_status=1 or p.payment_status=2 or p.payment_status=8) and  Date_Format(Current_Date(),'%Y%m')=Date_Format(p.date_payment,'%Y%m')
						Group By sa.id_scheme_account
					) cp On (sa.id_scheme_account=cp.id_scheme_account)
					JOIN chit_settings cs 
				Where sa.active=1 and sa.is_closed = 0 and sa.`id_scheme_account` ='$id_scheme_account'";   
               $record = $this->db->query($sql_scheme)->row_array();
		       $result['chit']=array(	'account_name'  => $record['account_name'],
                                        'chit_active' =>$record['chit_active'],
                                        'chit_number' =>($record['chit_number']==' Not Allocated' ?$this->config->item('default_acno_label') :$record['chit_number']) ,
                                        'code' =>	$record['has_lucky_draw']=1? $record['scheme_group_code']: $record['code'],
                                        'current_chances_used' => $record['current_chances_used'],
                                        'current_paid_installments' =>$record['current_paid_installments'],
                                        'current_total_amount' => $record['current_total_amount'],
                                        'current_total_weight' => $record['current_total_weight'],
                                        'gst' => $record['gst'],
                                        'id_customer' => $record['id_customer'],
                                        'id_scheme' =>$record['id_scheme'],
                                        'paid_installment' =>$record['paid_installment'],
                                        'id_scheme_account' =>$record['id_scheme_account'],
                                        'is_closed' => $record['is_closed'],
                                        'is_pan_required' =>$record['is_pan_required'],
                                        'last_paid_date' => $record['last_paid_date'],
                                        'max_chance' => $record['max_chance'],
                                        'max_weight' => $record['max_weight'],
                                        'min_chance' => $record['min_chance'],
                                        'min_weight' => $record['min_weight'],
                                        'mobile' => $record['mobile'],
                                        'paid_installments' =>$record['paid_installments'],
                                        'payable' =>($record['scheme_type']==3 && $record['firstPayment_amt'] > 0 ? $record['firstPayment_amt'] :$record['payable']),
                                        'scheme_name' =>$record['scheme_name'],
                                        'scheme_type' =>$record['scheme_type'],
                                        'flexible_sch_type' =>$record['flexible_sch_type'],
                                        'start_date' => $record['start_date'],
                                        'total_installments' =>$record['total_installments'],
                                        'total_paid_amount' => $record['total_paid_amount'],
                                        'total_paid_weight' => $record['total_paid_weight'],
                                        'maturity_date' => $record['maturity_date'],
                                        'fixed_metal_rate' => $record['fixed_metal_rate'],
                                        'fixed_wgt' => $record['fixed_wgt'],
                                        'one_time_premium' => $record['one_time_premium'],
                                        'firstPayment_amt' => $record['firstPayment_amt'],
                                        'is_rate_fixed' => $record['is_rate_fixed'] ,
                                        'fixed_wgt' => $record['fixed_wgt'],
                                        'fixed_metal_rate' => $record['fixed_metal_rate'],
                                        'fixed_rate_on' => $record['fixed_rate_on'],
                                        'rate_fixed_in' => $record['rate_fixed_in'],
                                        'disable_payment' => $record['disable_payment'],
                                        'disable_pay_reason' => $record['disable_pay_reason'],
                                        'has_prize' => $record['has_prize'],
                                        'has_gift' => $record['has_gift']
                                        );
				//payments
		$sql_payments = "Select
						   p.id_payment,
						  IFNULL(p.id_transaction,'') as id_transaction,
						  IFNULL(p.payu_id,'') as payu_id,
						  Date_Format(p.date_payment,'%d-%m-%Y') as trans_date ,
						  IFNULL(p.payment_amount,'0.00') as amount,
						  IFNULL(p.metal_weight,'0.00') as weight,
						  if(p.payment_mode is null,'',if(p.payment_mode = 'FP','Free',p.payment_mode )) as payment_mode,
						  IFNULL(p.bank_name,'') as bank_name,
						  IFNULL(p.bank_branch,'') as  branch_name,
						  IFNULL(p.card_no,'')    as card_no,
						  IFNULL(p.payment_ref_number,'') as approval_no,
						  IFNULL(p.receipt_no,'') as receipt_jil,
						  IFNULL(p.id_scheme_account,'') as id_scheme_account,
						  IFNULL(p.metal_rate,'') as rate,
						  IFNULL(p.remark,'') as remark,
						  IF(p.payment_status = 2,'Awaiting',psm.payment_status) as pay_status,
						  p.payment_status as id_status,
						  psm.color as status_color,
						  concat(if(p.due_month=1,'JAN',if(p.due_month=2,'FEB',if(p.due_month=3,'MAR',if(p.due_month=4,'APR',if(p.due_month=5,'MAY',if(p.due_month=6,'JUN',if(p.due_month=7,'JULY',if(p.due_month=8,'AUG',if(p.due_month=9,'SEP',if(p.due_month=10,'OCT',if(p.due_month=11,'NOV',if(p.due_month=12,'DEC','')))))))))))),'-',p.due_year) as due_month
					 From payment p
					 Left Join payment_mode pm on (p.payment_mode=pm.id_mode)		
	                 Left Join payment_status_message psm On (p.payment_status=psm.id_status_msg)
					 Where (p.payment_status=1 or p.payment_status=2 or p.payment_status=8) and id_scheme_account='$id_scheme_account' order by p.due_year,p.due_month";
	    $result['payments'] = $this->db->query($sql_payments)->result_array();	
        $sql_gifts = $this->db->query("SELECT  gift_desc, Date_Format(date_issued,'%d-%m-%Y') as date_issued FROM `gift_issued` where type = 1 AND id_scheme_account=".$id_scheme_account);
        $result['gifts']['data'] = $sql_gifts->result_array();
        $result['gifts']['msg'] = ($sql_gifts->num_rows() > 0 ? "Records retrieved successfully" : "No gifts issued" );
        $sql_prices = $this->db->query("SELECT  gift_desc, Date_Format(date_issued,'%d-%m-%Y') as date_issued FROM `gift_issued` where type = 2 AND id_scheme_account=".$id_scheme_account);
        $result['prizes']['data'] = $sql_prices->result_array();	
        $result['prizes']['msg'] = ($sql_prices->num_rows() > 0 ? "Records retrieved successfully": "No prizes issued" );
		//print_r($sql_scheme);exit;	 
		return $result;	 
	}
	function get_settings()
	{   
	    $result = array();
		$this->db->select('allow_notification,delete_unpaid,reg_existing,show_closed_list,branch_settings,regExistingReqOtp');
		$result= $this->db->get('chit_settings');
		return $result->row();
	}
	function get_customer_dashboard($id_customer)
	{   
	     $result = array();
		$result['total_accounts']    = $this->countSchemes($id_customer);
		$result['wallets']           = $this->countWallets($id_customer);
		$wb = $this->wallet_balance($id_customer); 	
 		$result['wallet_balance']   = (isset($wb['wal_balance1']) ?$wb['wal_balance1'] : 0);
		$result['giftItems_enable']  = $this->get_giftItems();
		$scheme_types =   $this->totalAmtWgt($id_customer);    // closed a/c Amt is Reduced in Dashboard page//HH
		 $result['amount']['total_amount'] = $scheme_types['amount'];
		 $result['weight']['total_weight'] = $scheme_types['weight'];
		 $result['payments'] = $this->countPayments($id_customer);
		 $result['customer'] = $this->get_customerByID($id_customer);
		 $result['dues'] = $this->countDues($id_customer);
		 $result['closed_acc'] = $this->total_closed_acc($id_customer); 
		return $result;
	}
	function totalAmtWgt($id_customer){
		$sql="select 
		        SUM(CASE WHEN (s.scheme_type = 0 or (s.scheme_type = 3 and s.flexible_sch_type = 1) ) THEN ifnull(payment_amount,0) ELSE 0 END) as  amount,
		        SUM(CASE WHEN (s.scheme_type = 1 or s.scheme_type = 2 or (s.scheme_type = 3 and s.flexible_sch_type > 1) ) THEN ifnull(metal_weight,0) ELSE 0 END) as  weight 
			From payment p
         		Left Join scheme_account sa  on (sa.id_scheme_account = p.id_scheme_account and (p.payment_status=1 ))
         		Left Join scheme s on s.id_scheme = sa.id_scheme
         	WHERE sa.active=1 AND id_customer = '".$id_customer."'"; 
		return $this->db->query($sql)->row_array();
	}
	function total_closed_acc($id_customer)
	{
		$sql="Select count(id_scheme_account) as closed_acc 
				From scheme_account 
				Where is_closed=1 and active=0 and id_customer='".$id_customer."'";
		return $this->db->query($sql)->row()->closed_acc;
	}
	function countDues($id_customer){
		$dues =0;
		$this->load->model('payment_modal');
		$payrec = $this->get_payment_details($id_customer,'');
		   if(isset($payrec))
			{
				foreach($payrec['chits'] as $pay)
				{
					if($pay['allow_pay']=='Y')
					{
						$dues++;
					}
				}
			}
		return $dues;
		}
	function schemeType_wise($id_customer)
	{
			$sql="Select scheme_type,count(distinct sa.id_scheme_account) as schemes,
					sum(ifnull(payment_amount,0)) as amount,sum(ifnull(metal_weight,0)) as weight,
					count(distinct  p.id_payment) as payments
					From scheme_account sa
					Left Join scheme s On (sa.id_scheme = s.id_scheme)
					Left Join payment p  on (sa.id_scheme_account = p.id_scheme_account and (p.payment_status=2 or p.payment_status=1 ))
					Where sa.active=1 and sa.id_customer ='$id_customer'
					group by s.scheme_type";
		 $result = $this->db->query($sql);	
	     return $result->result_array();	
	} 
	//count no schemes by the customer
	function countSchemes($id_customer)
	{
		$sql = " Select count(id_scheme) as schemes 
		         From scheme_account 
		         Where active=1 and id_customer ='$id_customer'";
		$result = $this->db->query($sql);	
		return $result->row()->schemes;
	}	
	//count no wallet by the customer
	function countWallets($id_customer)
	{
		$sql = " Select count(id_wallet_account) as wallets
		         From wallet_account 
		         Where active=1 and id_customer ='$id_customer'";
		$result = $this->db->query($sql);	
		return $result->row()->wallets;
	}
	function wallet_balance($id_customer)
	{
		$data = array();
		$sql="Select
								  wa.id_wallet_account,
								  c.id_customer,
								  Concat(c.firstname,' ',if(c.lastname!=NULL,c.lastname,'')) as name,
								  c.mobile,
								  Concat(e.firstname,' ',if(e.lastname!=NULL,e.lastname,'')) as emp_name,
								  wa.wallet_acc_number,
								  Date_Format(wa.issued_date,'%d-%m-%Y') as issued_date,
								  wa.remark,
								  wa.active,
								  SUM(CASE WHEN wt.transaction_type=0 THEN wt.`value` ELSE 0 END) as  issues,
								  SUM(CASE WHEN wt.transaction_type=1 THEN wt.`value` ELSE 0 END) as redeem,
								  (SUM(CASE WHEN wt.transaction_type=0 THEN wt.`value` ELSE 0 END) -   SUM(CASE WHEN wt.transaction_type=1 THEN wt.`value` ELSE 0 END)) as balance,
								   cs.wallet_amt_per_points,cs.wallet_balance_type,cs.wallet_points
							From wallet_account wa
								Left Join customer c on (wa.id_customer=c.id_customer)
								Left Join employee e on (wa.id_employee=e.id_employee)
								Left Join wallet_transaction wt on (wa.id_wallet_account=wt.id_wallet_account)
								join chit_settings cs
								where c.id_customer =".$id_customer;
		$result = $this->db->query($sql);
		if($result->num_rows()>0){
		           $sql1="SELECT w.redeem_percent FROM wallet_category_settings w where active=1 and w.id_category=".$this->config->item('wallet_cat_id');	
		           $record = $this->db->query($sql1);
				   if($record->num_rows()>0)
				   {
			    $balance= ($result->row()->wallet_balance_type==1 ? (($result->row()->balance/$result->row()->wallet_points)*$result->row()->wallet_amt_per_points) : $result->row()->balance);
			  //$data = array('redeem_percent'=>$record->row()->redeem_percent,'wal_balance1'=>$result->row()->balance,'wal_balance'=>$balance,'wallet_balance_type'=>$result->row()->wallet_balance_type,'wallet_points'=>$result->row()->wallet_points,'wallet_amt_per_points'=>$result->row()->wallet_amt_per_points);
				$data = array('redeem_percent'=>$record->row()->redeem_percent,'wal_balance1'=>$balance,'wal_balance'=>$result->row()->balance,'wallet_balance_type'=>$result->row()->wallet_balance_type,'wallet_points'=>$result->row()->wallet_points,'wallet_amt_per_points'=>$result->row()->wallet_amt_per_points);
				   }
		}
		 return $data;
	}
	/*function countWalletsblc($id_customer)
	{
		$sql = " Select count(id_wallet_account) as wallet
		         From wallet_account 
		         Where active=1 and id_customer ='$id_customer'
		         GROUP BY id_customer";
		$result = $this->db->query($sql);	
		return $result->row()->wallets;
	}*/
	function countPayments($id_customer)
	{
		$sql = "Select count(distinct  p.id_payment) as payments
				From scheme_account sa
				Left Join payment p  on (sa.id_scheme_account = p.id_scheme_account and (p.payment_status=2 or p.payment_status=1 ))
         		Where sa.active=1 and sa.id_customer ='$id_customer'";
				$result = $this->db->query($sql);	
		        return $result->row()->payments;
	}
	//Check whether the customer has same scheme
	function sameSchemeExist($id_customer,$id_scheme)
	{
		$sql = " Select count(id_scheme) as schemes 
		         From scheme_account 
		         Where active=1  And id_customer ='$id_customer' And id_scheme='$id_scheme'";
		$result = $this->db->query($sql);	
		return $result->row_array();
	}
	//to count number of  payments made by customer on particular scheme
	function countSchemePayments($id_customer,$id_scheme)
  	{
		$sql="Select count(id_payment) as sch_payments
				From scheme_account sa
				Left Join payment p On(sa.id_scheme_account = p.id_scheme_account)
				Where sa.active=1 And p.paymentstatus=1 and sa.id_customer='$id_customer' and sa.id_scheme='$id_scheme'";
		$result = $this->db->query($sql);	
		return $result->row_array();		
	}
	//to check any scheme accounts without payment
	function notPaidAccounts($id_customer)
	{ $result ='';
		$sql = "Select sa.id_scheme_account,c.id_customer,sa.ref_no as client_id,s.id_scheme,s.code,sa.scheme_acc_number,count(id_payment) as sch_payments
			From scheme_account sa
			Left Join payment p On(sa.id_scheme_account = p.id_scheme_account)
			Left Join scheme s On (sa.id_scheme = s.id_scheme)
			Left Join customer c On (sa.id_customer = c.id_customer)
			Where sa.active =1 and p.payment_status = 1 and sa.id_customer='$id_customer' 
			Group By sa.id_scheme_account
			Having sch_payments=0;";	
			$account = $this->db->query($sql);
		if($account->num_rows()>0)
		{
			$result = array('not_paid_acc' => TRUE,'data' => $account->result_array());
		}
		else
		{
			$result = array('not_paid' => FALSE,'data' => '');
		}	
		return $result;
	}
	function isAddressExist($id_customer)
	{
		$sql = "Select * from address Where id_customer='".$id_customer."' ";
			$records = $this->db->query($sql);
		if($records->num_rows()>0)
		{
			return TRUE;
		}
	}
	//to check whether customer has payment entry
	function isPaymentExist($id_scheme_account)
	{
		$sql = "Select
					  sa.id_scheme_account,c.mobile
				From payment p
				Left Join scheme_account sa On (p.id_scheme_account = sa.id_scheme_account)
				Left Join customer c on (sa.id_customer = c.id_customer)
				Where (p.payment_status = 2 or p.payment_status = 1) And sa.id_scheme_account= '".$id_scheme_account."' ";
			$records = $this->db->query($sql);
		if($records->num_rows()>0)
		{
			return TRUE;
		}else{
			return FALSE;
		}
	}
	//to check whether customer has pending status payment entry
	function isPendingStatExist($id_scheme_account)
	{
		$sql = "Select
					  sa.id_scheme_account,c.mobile
				From payment p
				Left Join scheme_account sa On (p.id_scheme_account = sa.id_scheme_account)
				Left Join customer c on (sa.id_customer = c.id_customer)
				Where (p.payment_status = 7) And sa.id_scheme_account= '".$id_scheme_account."' ";
			$records = $this->db->query($sql);
		if($records->num_rows()>0)
		{
			return TRUE;
		}else{
			return FALSE;
		}
	}
	//get last paid entry
	function getLastTransaction($id_scheme_account)
	{
		$sql="Select no_of_dues,payment_amount,due_type,act_amount,payment_status from payment			
			  Where (payment_status=1 Or payment_status=2 Or payment_status=7)	
			         And id_scheme_account='$id_scheme_account'";
		return $this->db->query($sql)->row_array();	         
	}
	/** Customer functions  **/
    function insert_customer($data)
    {
		  $status = $this->db->insert(self::TAB_CUS,$data['info']);  
		  //	print_r($this->db->last_query());exit;
		  $insertID = $this->db->insert_id();
			if($insertID){
					$data['address']['id_customer']=$insertID;
					$res=$this->db->insert(self::TAB_ADD,$data['address']);
					if($res){						
						$id_address=$this->db->insert_id();
						$address = array('id_address' => $id_address);
						$this->db->where('id_customer',$insertID); 
						$this->db->update(self::TAB_CUS,$address);
						$status = array("status" => true, "insertID" => $insertID);
					}else{
				 	   $status = array("status" => false, "insertID" => '');
				    }				
				}
				else{
					$status = array("status" => false, "insertID" => '');
				  }
		return $status;
	}
	function update_customer($data,$id)
	{
		$this->db->where('id_customer',$id); 
		//	print_r($this->db->last_query());exit;
		return $this->db->update(self::TAB_CUS,$data);		
	} 
	function insert_customerAdd($data)
    {
		$status = $this->db->insert(self::TAB_ADD,$data);
		return array('status' => $status, 'insertID' => $this->db->insert_id());
	}
	function insCusFeedback($data)  
    {
		$status = $this->db->insert('cust_enquiry',$data);
		return array('status' => $status, 'insertID' => $this->db->insert_id());
	}
	function update_customerAdd($data,$id)
	{
		$this->db->where('id_customer',$id); 
		return $this->db->update(self::TAB_ADD,$data);		
	}
	function update_customerByMobile($data,$mobile)
	{
		$this->db->where('mobile',$mobile); 
		return $this->db->update(self::TAB_CUS,$data);		
	}
	 /** Scheme Account functions  **/
	function update_schemeAcc($data,$id)
	{
		$this->db->where('id_scheme_account',$id); 
		return $this->db->update(self::TAB_ACC,$data);		
	}
    /** payment functions  **/
      function insert_schemeAcc($data)
    {
/* Coded by ARVK*/				
				$sql_scheme = $this->db->query("select  s.approvalReqForFP,cs.receipt_no_set,s.free_payment, s.amount, s.scheme_type, s.min_weight, s.max_weight, c.company_name, c.short_code  ,s.gst,s.gst_type,cs.cusbenefitscrt_type,cs.empbenefitscrt_type
			  										from scheme s join company c
                                                    join chit_settings cs		 
			  										where s.id_scheme=".$data['id_scheme']);
			  	$sch_data = $sql_scheme->row_array();
/* / Coded by ARVK*/
		if($sch_data){
				$cus_single=$sch_data['cusbenefitscrt_type'];
				$emp_single=$sch_data['empbenefitscrt_type'];
			 if($data['referal_code']!='' &&  ($data['is_refferal_by']==0 && $cus_single==0) || ($data['is_refferal_by']==1 && $emp_single==0) )
			 {
				 if($data['is_refferal_by']==0)
				 {
					$cus_data  = array(
								  'referal_code'=>$data['referal_code'],
								 'id_customer'=>$data['id_customer'],
								  'is_refferal_by'=>$data['is_refferal_by'],
									'cus_single'=>$cus_single
					  			 );	 
				 }
				 else
				 {
					 $cus_data  = array(
								  'referal_code'=>$data['referal_code'],
								 'id_customer'=>$data['id_customer'],
								  'is_refferal_by'=>$data['is_refferal_by'],
								'emp_single'=>$emp_single
					  			 );	
				 }
				$this->available_refcode($cus_data);
			}else if($data['referal_code']!='' && ($data['is_refferal_by']==0 && $cus_single==1) || ($data['is_refferal_by']==1 && $emp_single==1)){	
				$cus_data =array(  
				          'id_customer'=>$data['id_customer'],
						   'cus_single'=>$cus_single,
							'emp_single'=>$emp_single,
							'is_refferal_by'=>$data['is_refferal_by'],
							'emp_ref_code' => NULL,
							'cus_ref_code' => NULL
				);
			}
		}
		$status = $this->db->insert(self::TAB_ACC,$data);
		return array('status' => $status,'sch_data' => $sch_data, 'insertID' => $this->db->insert_id());
	}
	/*function available_refcode($data){  
	 $query=$this->db->query("SELECT c.referal_code 
				FROM customer c 
				where c.id_customer=".$data['id_customer']." and c.referal_code='".$data['referal_code']."'");
	   if($query->num_rows()>0){
		   return TRUE;		   
	   }else{
		   $this->db->where('id_customer',$data['id_customer']); 		  
		   $updaterefcode =  $this->db->update('customer',array('referal_code'=>$data['referal_code']));
		  return TRUE;	
	   }
	} */
	function available_refcode($data)
	{
	if($data['is_refferal_by']==0 && $data['cus_single']==0)
	{
		$query=$this->db->query("SELECT c.cus_ref_code 
				FROM customer c 
				where c.id_customer=".$data['id_customer']." and c.cus_ref_code='".$data['referal_code']."'");
			if($query->num_rows()>0)
	 	 	 {
		  	 return TRUE;		   
	  		 }
	  		 else
	  		 {
	  		 	 $this->db->where('id_customer',$data['id_customer']); 		  
				 $updaterefcode =  $this->db->update('customer',array('cus_ref_code'=>$data['referal_code']));
		 			 return TRUE;	
	  		 }
	}
	else if($data['is_refferal_by']==1 && $data['emp_single']==0)
	{
		$query=$this->db->query("SELECT c.emp_ref_code 
				FROM customer c 
				where c.id_customer=".$data['id_customer']." and c.emp_ref_code='".$data['referal_code']."'");
			if($query->num_rows()>0)
	 	 	 {
		  	 return TRUE;		   
	  		 }
	  		 else
	  		 {
	  		 	 $this->db->where('id_customer',$data['id_customer']); 		  
				 $updaterefcode =  $this->db->update('customer',array('emp_ref_code'=>$data['referal_code']));
		 			 return TRUE;	
	  		 }
	}
	else if( $data['is_refferal_by']==0||$data['is_refferal_by']==1 && ($data['cus_single']==1&&$data['emp_single']==1))
	{
		 $this->db->where('id_customer',$data['id_customer']); 		  
				 $updaterefcode =  $this->db->update('customer',array('cus_ref_code'=>$data['referal_code'],'emp_ref_code'=>$data['referal_code']));
		 			 return TRUE;	
	}
	}
/* Coded by ARVK*/	
	function free_payment_data($sch_data,$sch_acc_id)
	{
		$sche_acc=$this->get_acc($sch_acc_id);  // id_metal besed to taken silver/gold rate//HH
	    $metal_rate = $this->getMetalRate();
	    $rate_field = NULL;
        $metalRate  = NULL;
        $sql = $this->db->query("SELECT rate_field,market_rate_field FROM `ret_metal_purity_rate` where id_metal=".$sche_acc['id_metal']." and id_purity=1");
         //print_r($this->db->last_query());exit;
         if($sql->num_rows() == 1){
         $metalfields = $sql->row_array();
         $rate_field  = $metalfields['rate_field'];
         $metalRate   = ( $rate_field == null ? null : $metal_rate[$rate_field] );
         //print_r($metalRate);exit;
        }
        
		$gold_rate = number_format((float)$metal_rate['goldrate_22ct'], 2, '.', '');
		$gst_amt = 0;
		if($sch_data['gst'] > 0){
			$gst_amt = $sch_data['amount']*($sch_data['gst']/100); 
			if($sch_data['gst_type'] == 0){
				$converted_wgt = number_format((float)(($sch_data['amount']-$gst_amt)/$gold_rate), 3, '.', '');
			}
			else{
				$converted_wgt = number_format((float)($sch_data['amount']/$gold_rate), 3, '.', '');
			}
		}
		else{
			$converted_wgt = number_format((float)($sch_data['amount']/$gold_rate), 3, '.', '');
		}
		$fxd_wgt = $sch_data['max_weight'];
		$insertData = array(
								"id_scheme_account"	 => $sch_acc_id,
								"gst"	 			 => $sch_data['gst'],
								"gst_type"	 		 => $sch_data['gst_type'],
								"id_employee"	 	 => NULL,
								"date_payment" 		 => date('Y-m-d H:i:s'),
								"payment_type" 	     => "Cost free payment", 
								"payment_mode" 	     => "FP", 
								"act_amount" 	     => $sch_data['amount'], 								
								"payment_amount" 	 => $sch_data['amount'], 
								"due_type" 	         => 'D', 
								"no_of_dues" 	     => '1', 								
							//	"metal_rate"         => $gold_rate,
							     'metal_rate'            => $metalRate,
								"metal_weight"       => ($sch_data['scheme_type']==2 ? $converted_wgt : ($sch_data['scheme_type']==1 ? $fxd_wgt : 0.000)),
								"remark"             => "Paid by ".$sch_data['company_name'],
								"installment"        => 1, // only for 1st ins free
								"payment_status"     => ($sch_data['approvalReqForFP'] == 1 ? 2 :1),
								"added_by"           =>2
							);
					return 	$insertData;	
	}
	function getMetalRate()
	{
		$filename = base_url().'api/rate.txt'; 	
	    $data = file_get_contents($filename);
	    $metalrates = (array) json_decode($data);	    
	    return $metalrates;
	}
	
	function get_acc($sch_acc_id)  
    {	
		$sql = $this->db->query("SELECT sa.id_branch,s.id_metal  FROM  scheme_account sa 
		Left join scheme s On (s.id_scheme = sa.id_scheme)
		where sa.id_scheme_account=".$sch_acc_id.""); 
		//print_r($this->db->last_query());exit;
	    return $sql->row_array();
     } 	
	
/* / Coded by ARVK*/	
    function insert_payment($data)
    {
		$status = $this->db->insert(self::TAB_PAY,$data);
		return array('status' => $status, 'insertID' => $this->db->insert_id());
	}
	function update_payment($data,$id)
	{
		$this->db->where('id_payment',$id); 
		return $this->db->update(self::TAB_PAY,$data);		
	}
	/*** General Functions ***/
	//Checking the customer mobile already registered
	function isMobileExists($mobile)
	{
		$this->db->select('mobile');
		$this->db->where('mobile', $mobile);
		$customer= $this->db->get(self::TAB_CUS);	  
		if($customer->num_rows()>0)
		{
			return TRUE;
		}			
	}
	//validate user login
	function isValidLogin($mobile,$passwd)
	{
		$this->db->select('mobile');
		$this->db->where('mobile', $mobile);
		$this->db->where('passwd', $passwd);
		$this->db->where('active',1);
		$login= $this->db->get(self::TAB_CUS);
		if($login->num_rows()>0)
		{
			return TRUE;
		}			
	}
	function generateOTP() 
	{
	     $data['otp']    = mt_rand(100000, 999999);
	     $data['expiry'] = date("Y-m-d H:i:s", strtotime('+1 hour'));
         return $data;
	}
//Promotion sms and otp setting
	function send_sms($mobile,$message)
	{		
		$url = $this->sms_data['sms_url'];
		$senderid  = $this->sms_data['sms_sender_id'];
	if($this->sms_chk['debit_sms']!=0){
		$arr = array("@customer_mobile@" => $mobile,"@message@" => str_replace(array("\n","\r"), '', $message),"@senderid@" => $senderid);
		$user_sms_url = strtr($url,$arr);
    	$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $user_sms_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
		$result = curl_exec($ch);
		curl_close($ch);
		unset($ch);
		$status=$this->update_otp();
		if($status==1){
		  return TRUE;
		}
		return FALSE;
	}else{
		return FALSE;
	}
 }
  function update_otp()
  {
		$query_validate=$this->db->query('UPDATE sms_api_settings SET debit_sms = debit_sms - 1 
				WHERE id_sms_api =1 and debit_sms > 0');  			
	         if($query_validate>0)
			{
				return true;
			}else{
				return false;
			}
  }
//Promotion sms and otp setting
		//update gateway response
	function updateGatewayResponse($data,$txnid)
	{
		$this->db->where('id_transaction',$txnid); 
		$status = $this->db->update(self::TAB_PAY,$data);	
		$result=array(
		              'status' => $status,
		              'id_payment' => $this->get_lastUpdateID($txnid) 
		              );
		return $result;
	}	
	function get_lastUpdateID($txnid)
	{
		$this->db->select('id_payment');  
		$this->db->where('id_transaction',$txnid); 
		$payid = $this->db->get('payment');	
		return $payid->row()->id_payment;
	}
	function get_schemeByChit($id_scheme_account)
	{
		$sql ="select 
		s.id_scheme,s.code,s.scheme_type,s.amount
		from scheme s
		left join scheme_account sa on sa.id_scheme=s.id_scheme
		where sa.id_scheme_account='$id_scheme_account'";
      $result = $this->db->query($sql);
      if($result->num_rows()>0)
      {
	  	return $result->row_array();
	  }     
	}
		function get_invoiceData($payment_no)
	{
		$records = array();
		$query_invoice = $this->db->query("SELECT pay.id_scheme_account as id_scheme_account, sch_acc.ref_no as scheme_acc_number, DATE_FORMAT(pay.date_payment,'%d-%m-%Y') as date_payment, sch.scheme_name as scheme_name, pay.payment_amount as payment_amount,cus.firstname as firstname, cus.lastname as lastname, cus.mobile, addr.address1 as address1,email,if(payment_mode='CC','Credit Card',if(payment_mode='NB','Net Banking',if(payment_mode='CD','Cheque or DD',if(payment_mode='CO','Cash Pick Up',if(payment_mode='OP','Other',''))))) as payment_mode,id_transaction,payment_ref_number,pay.receipt_no,bank_name,bank_acc_no,bank_branch,metal_weight,scheme_type
							FROM payment as pay
							LEFT JOIN scheme_account sch_acc ON sch_acc.id_scheme_account = pay.id_scheme_account
							LEFT JOIN scheme sch ON sch.id_scheme = sch_acc.id_scheme
							LEFT JOIN customer as cus ON cus.id_customer = sch_acc.id_customer
							LEFT JOIN address as addr ON addr.id_customer = cus.id_customer WHERE id_payment = '".$payment_no."' AND mobile='".$this->session->userdata('username')."'");
		if($query_invoice->num_rows() > 0)
			{
				foreach($query_invoice->result() as $row)
				{
					$records[] = array('id_scheme_account' => $row->id_scheme_account,'scheme_acc_number' => $row->scheme_acc_number,'date_payment' => $row->date_payment,'scheme_name' => $row->scheme_name, 'payment_amount' => $row->payment_amount,'firstname' => $row->firstname,'lastname' => $row->lastname, 'id_payment' => $payment_no,'address1' => $row->address1,'email' => $row->email,'mobile' => $row->mobile,'payment_mode' => $row->payment_mode,'id_transaction' => $row->id_transaction,'payment_ref_number' => $row->payment_ref_number,'receipt_no' => $row->receipt_no,'bank_name' => $row->bank_name,'bank_acc_no' => $row->bank_acc_no,'bank_branch' => $row->bank_branch,'metal_weight' => $row->metal_weight,'scheme_type' => $row->scheme_type);
				}
			}
			return $records;
	}
	 function get_branchWiseLogin()
{
	$sql="select cs.branchWiseLogin from chit_settings cs";
		$records = $this->db->query($sql)->row()->branchWiseLogin;
		return $records;
}
		// closed a/c pay entries removed in pay histy page//hh
	function get_paymenthistory($mobile,$branchWiseLogin)
	{
	    $showGCodeInAcNo = $this->config->item('showGCodeInAcNo');
		$records = array();
		if($branchWiseLogin==1)
		{
			$query_scheme = $this->db->query("select id_payment,sch.id_metal,m.metal,DATE_FORMAT(date_payment,'%d-%m-%Y') AS date_payment, DATE_FORMAT(Date_add(date_payment,Interval 1 month),'%b %Y') as next_due,metal_rate, payment_amount, metal_weight,pay.receipt_no,pay.add_charges,if(pay.payment_type = 'Payu checkout',(payment_amount+ifnull(add_charges,0)), payment_amount) as total_amt,sch.charge_head,pay.gst,pay.gst_type,br.id_branch, br.short_name, br.name as branch_name,cs.branch_settings,
										 if(payment_mode='CC','Credit Card',if(payment_mode='NB','Net Banking', 
										  if(payment_mode='CD','Cheque or DD',if(payment_mode='CO','Cash Pick Up',
										  if(payment_mode='OP','Other',if(payment_mode='DC','Debit Card', if(payment_mode='FP','Enrollment Offer',payment_mode) )))))) as payment_mode,IFNULL(id_transaction,'-') as id_transaction, if(payment_status = 1, 'Paid',if(payment_status = 2, 'Awaiting',if(payment_status = 5, 'Returned',if(payment_status = 6, 'Refund',if(payment_status = 7, 'Pending',if(payment_status = 3, 'Failed',if(payment_status = 4, 'Cancelled','')))))))
										  as payment_status ,sch.code , 
										  CONCAT(if(".$showGCodeInAcNo."=1,if(has_lucky_draw = 1 && is_lucky_draw = 1,sg.group_code,sch.code),'') ,' ',ifnull(sa.scheme_acc_number,'Not Allocated')) as scheme_acc_number,
										  ref_no AS client_id, scheme_name,cs.currency_symbol,pay.payment_type,
											if(scheme_type = 0,'Amount Scheme',IF(scheme_type=1,'Weight Scheme','Amt to Wgt scheme')) as scheme_type,scheme_type as sch_type,flexible_sch_type,
											sa.group_code as scheme_group_code,cs.has_lucky_draw
											FROM payment as pay
											left join scheme_account AS sa on sa.id_scheme_account = pay.id_scheme_account
											Left Join branch br  On (pay.id_branch=br.id_branch)
											left join scheme as sch on sch.id_scheme = sa.id_scheme
											Left Join scheme_group sg On (sa.group_code = sg.group_code )
											left join customer as cus on  cus.id_customer = sa.id_customer
											left join payment_mode pm on pay.payment_mode=pm.short_code
											left join metal m on(m.id_metal=sch.id_metal)
											join chit_settings cs
											WHERE is_closed=0 and cus.mobile='".$mobile."' and sa.active=1 ORDER By id_payment DESC");
		}
		else{
			$query_scheme = $this->db->query("select id_payment,sch.id_metal,m.metal,DATE_FORMAT(date_payment,'%d-%m-%Y') AS date_payment, DATE_FORMAT(Date_add(date_payment,Interval 1 month),'%b %Y') as next_due,metal_rate, payment_amount, metal_weight,pay.receipt_no,pay.add_charges,if(pay.payment_type = 'Payu checkout',(payment_amount+ifnull(add_charges,0)), payment_amount) as total_amt,sch.charge_head,pay.gst,pay.gst_type,br.id_branch, br.short_name, br.name as branch_name,cs.branch_settings,
										 if(payment_mode='CC','Credit Card',if(payment_mode='NB','Net Banking', 
										  if(payment_mode='CD','Cheque or DD',if(payment_mode='CO','Cash Pick Up',
										  if(payment_mode='OP','Other',if(payment_mode='DC','Debit Card', if(payment_mode='FP','Enrollment Offer',payment_mode) )))))) as payment_mode,IFNULL(id_transaction,'-') as id_transaction, if(payment_status = 1, 'Paid',if(payment_status = 2, 'Awaiting',if(payment_status = 5, 'Returned',if(payment_status = 6, 'Refund',if(payment_status = 7, 'Pending',if(payment_status = 3, 'Failed',if(payment_status = 4, 'Cancelled','')))))))
										  as payment_status ,sch.code , 
										  CONCAT(if(".$showGCodeInAcNo."=1,if(has_lucky_draw = 1,sg.group_code,sch.code),'') ,' ',ifnull(sa.scheme_acc_number,'Not Allocated')) as scheme_acc_number,
										  ref_no AS client_id, scheme_name,cs.currency_symbol,pay.payment_type,
											if(scheme_type = 0,'Amount Scheme',IF(scheme_type=1,'Weight Scheme','Amt to Wgt scheme')) as scheme_type,scheme_type as sch_type,flexible_sch_type,
											sa.group_code as scheme_group_code,cs.has_lucky_draw
											FROM payment as pay
											left join scheme_account AS sa on sa.id_scheme_account = pay.id_scheme_account
											Left Join branch br  On (br.id_branch=sa.id_branch)
											left join scheme as sch on sch.id_scheme = sa.id_scheme
											Left Join scheme_group sg On (sa.group_code = sg.group_code )
											left join customer as cus on  cus.id_customer = sa.id_customer
											left join payment_mode pm on pay.payment_mode=pm.short_code
											left join metal m on(m.id_metal=sch.id_metal)
											join chit_settings cs
											WHERE is_closed=0 and cus.mobile='".$mobile."' and sa.active=1 ORDER By id_payment DESC");
		}
			if($query_scheme->num_rows() > 0)
			{
				foreach($query_scheme->result() as $row)
				{
			/*Add GST GST Amount = ( Original Cost * GST% ) / 100 Net Price = Original Cost + GST Amount
			Remove GST GST Amount = Original Cost - ( Original Cost * ( 100 / ( 100 + GST% ) ) ) Net Price = Original Cost - GST Amount */
			$paid_gst = 0.00;
			$add_gst = 0.00;
			if($row->gst > 0){				
				if($row->gst_type == 1){				
					$paid_gst = $row->payment_amount*($row->gst/100);	
					$add_gst = $paid_gst;			
				}
				else{
					$paid_gst = $row->payment_amount-($row->payment_amount*(100/(100+$row->gst)));	
				}
			}
					$records[] = array(
					                'id_payment' => $row->id_payment,
					                'sch_type'  => $row->sch_type,
					                'flexible_sch_type' => $row->flexible_sch_type,
					                'date_payment' => $row->date_payment,
					                'next_due'     => $row->next_due,
					                'receipt_no' => $row->receipt_no,
					                'metal_rate' => $row->metal_rate, 
					                'payment_amount' => $row->payment_amount,
					                'metal_weight' => $row->metal_weight,
					                'payment_mode' => $row->payment_mode,
					                'id_metal' => $row->id_metal,
					                'metal' => $row->metal,
					                'id_branch' => $row->id_branch,
					                'short_name' => $row->short_name,
					                'branch_name' => $row->branch_name,	
					                'branch_settings' => $row->branch_settings,
					                'id_transaction' => $row->id_transaction,
					                'payment_status' => $row->payment_status,
					                'scheme_acc_number' =>($row->scheme_acc_number==' Not Allocated' ?$this->config->item('default_acno_label'):$row->scheme_acc_number) ,
					                'client_id' => $row->client_id,
					                'scheme_name' => $row->scheme_name, 
					                'scheme_type' => $row->scheme_type, 
					                'currency_symbol' => $row->currency_symbol, 
					                'add_charges' => $row->add_charges, 
					                'payment_type' => $row->payment_type, 
					                'total_amt' => number_format(($row->total_amt+$add_gst),'0','.',''), 
					                'charge_head' => $row->charge_head, 
					                'gst' => $row->gst, 
					                'gst_type' => $row->gst_type,
					                'paid_gst'=>number_format($paid_gst,'2','.',''));
				}
			}
		return $records;
	}
	/*function get_offers()
	{
		$this->db->select('*'); 
		$this->db->where('active',1);
		$this->db->where('type',0); 
		$offers = $this->db->get('offers');	
		//print_r($this->db->last_query());exit;
		return $offers->result_array();
	}HH*/
		function get_offers($id_branch)  // based on the branch settings to showed Offers //HH
	{
	    $data=$this->get_costcenter();
	    //print_r($id_branch);exit;
		$offers=$this->db->query("SELECT  o.id_offer,o.name,o.offer_img_path,o.offer_content FROM offers o where active=1 and type=0 
		".($id_branch != 'null' && $data['is_branchwise_cus_reg']==1 && $data['branch_settings']==1 ?' and o.id_branch='.$id_branch.' ' :'')." ");
		if($offers->num_rows() > 0)
		{
			 return $offers->result_array();
		}
		else{
			return 0;
		}
    
	}
	function get_banners()
	{
		$this->db->select('*'); 
		$this->db->where('active',1);
		$this->db->where('type',1); 
		$offers = $this->db->get('offers');	
		return $offers->result_array();
		
		
	}
	function get_offersAndBanners()
	{
		$this->db->select('*'); 
		$this->db->where('active',1);
		$this->db->where('type',0);
		$this->db->or_where('type',1);
		$offers = $this->db->get('offers');	
		return $offers->result_array();
		
	}
	/*function get_new_arrivals()
	{
		/* $this->db->select('*'); 
		$this->db->where('active',1); 
		$new_arrivals = $this->db->get('new_arrivals');	
		return $new_arrivals->result_array(); */
	/*	$sql = "SELECT n.id_new_arrivals, n.name, n.new_arrivals_content, n.new_arrivals_img_path FROM new_arrivals n
		where active=1  and show_rate=1 or show_rate=0  and new_type=1";
		return $this->db->query($sql)->result_array();
	}HH*/
		function get_new_arrivals($id_branch)   // based on the branch settings to showed Newarrivals //HH
	{
	    $data=$this->get_costcenter();
	    //print_r($id_branch);exit;
		$offers=$this->db->query("SELECT n.id_new_arrivals, n.name, n.new_arrivals_content, n.new_arrivals_img_path FROM new_arrivals n
		where active=1  and (show_rate=1 or show_rate=0)  and new_type=1 
		".($id_branch != 'null' && $data['is_branchwise_cus_reg']==1 && $data['branch_settings']==1 ?' and n.id_branch='.$id_branch.' ' :'')." ");
		if($offers->num_rows() > 0)
		{
			return $offers->result_array();
		}
		else{
			return 0;
		}
     
	}
	//Price show based on sett//hh
	function get_newArrdetail($id)
	{
		/* $this->db->select('*'); 
		$this->db->where('id_new_arrivals',$id); 
		$new_arrivals = $this->db->get('new_arrivals');	
		return $new_arrivals->row_array(); */
		 $sql = "SELECT * from new_arrivals where active=1 
				AND  new_type=1 and show_rate=1 or show_rate=0 AND id_new_arrivals=".$id;
			return $this->db->query($sql)->row_array();
	}
// gift_artical_detail
	function get_gift_items(){
		$sql = "SELECT n.id_new_arrivals,n.price,if(Date_Format(n.date_add,'%Y-%m-%d') = CURDATE(),1,0) as is_new, n.name, n.new_arrivals_content, n.new_arrivals_img_path,show_rate,gift_type FROM new_arrivals n
		where active=1 and gift_type=1";
		return $this->db->query($sql)->result_array();
	}
	function get_giftItemDetail($id)
	{
			$sql = "SELECT * FROM new_arrivals where active=1 AND gift_type=1 AND id_new_arrivals=".$id; 
			return $this->db->query($sql)->row_array();
	} 
	function get_giftItems()
	{
			$sql = "SELECT * FROM new_arrivals where active=1 AND gift_type=1"; 
			$gift=$this->db->query($sql);
			if($gift->num_rows() > 0)
			{
				return TRUE ;
		     }
		else{
			return  FALSE;
		}
	}	
// gift_artical_detail
	function get_offerdetail($id)
	{
		$this->db->select('*'); 
		$this->db->where('id_offer',$id); 
		$offers = $this->db->get('offers');	
		return $offers->row_array();
	}
	function getClassification()
	{
		$this->db->select('*'); 
		$this->db->where('active','1'); 
		$offers = $this->db->get('sch_classify');	
		return $offers->result_array();
	}
	function get_cus_noti_settings($id_customer)
	{
		$sql = "Select notification
				From customer 
         		Where active=1 and id_customer ='$id_customer'";
				$result = $this->db->query($sql);	
		        return $result->row()->notification;
	}
	function update_cusnotisettings($noti)
	{
		$status = false;
		if($noti['notification']==0){
			/*echo 1;*/
			$this->db->where("id_customer",$noti['id_customer']);
			$stat = $this->db->delete("registered_devices");
			if($stat){
				$this->db->where('id_customer',$noti['id_customer']); 
			    $status = $this->db->update('customer',$noti);	
			}
		}
		else{
			$this->db->where('id_customer',$noti['id_customer']); 
			    $status = $this->db->update('customer',$noti);	
		}		
		return $status;
	}
	/* Device registration functions */
	 function insert_deviceData($data)
    {
		$status = $this->db->insert('registered_devices',$data);
		 //	print_r($this->db->last_query());exit;
		return array('status' => $status, 'insertID' => $this->db->insert_id());
	}
	function update_deviceData($data,$id)
	{
		$sql ="select * from registered_devices where id_customer=".$id;
		$res=$this->db->query($sql);
		if($res->num_rows() > 0){
			$this->db->where('id_customer',$id); 
			return $this->db->update('registered_devices',$data);	
		}
		else{
			return $this->db->insert('registered_devices',$data);
		}			
	}
	//to get closed account by customer
	 function get_closed_account($id_cus)
	{
		$sql ="select sc.flexible_sch_type,s.id_scheme_account,sc.is_lucky_draw,sg.group_code as group_code,sc.id_metal,m.metal,
		concat(if(has_lucky_draw = 1 && is_lucky_draw = 1,sg.group_code,sc.code),' ',ifnull(s.scheme_acc_number,'Not Allocated')) as scheme_acc_number,
		concat (c.firstname,' ',if(c.lastname!=Null,c.lastname,'')) as name,s.ref_no,s.account_name,Date_Format(s.start_date,'%d-%m-%Y') as start_date,Date_Format(s.closing_date,'%d-%m-%Y') as closing_date,
		if(sc.scheme_type=0 || (sc.scheme_type=3 && (sc.flexible_sch_type=1 || sc.flexible_sch_type=2 || sc.flexible_sch_type=0)),CONCAT(s.closing_balance),CONCAT(s.closing_balance,' ',' g')) as closing_balance,cs.curr_symb_html,
							 sc.max_weight, s.closing_weight,sc.scheme_name,sc.code,sc.scheme_type,sc.total_installments,sc.max_chance,c.mobile,s.group_code as scheme_group_code, cs.has_lucky_draw,s.is_closed,date_format(s.maturity_date,'%d-%m-%Y') as maturity_date,
							 Format(IF(sc.scheme_type=0 OR sc.scheme_type=2,sc.amount,IF(sc.scheme_type=1 OR sc.flexible_sch_type=0 ,sc.max_weight,if(sc.scheme_type=3,if((sc.min_amount!='' && sc.max_amount!='0'),sc.max_amount,round((sc.max_weight * (SELECT m.goldrate_22ct FROM metal_rates m  order by id_metalrates Desc LIMIT 1)))),0))),2) as amount
							from
							  scheme_account s
							  Left Join scheme_group sg On (s.group_code = sg.group_code )
							left join customer c on (s.id_customer=c.id_customer)
							left join scheme sc on (sc.id_scheme=s.id_scheme)
							left join metal m on(m.id_metal=sc.id_metal)
							join chit_settings cs
							where ((s.active=0 and s.is_closed=1) or  (sc.maturity_days > 0 and DATE_FORMAT(s.maturity_date,'%Y%m%d')) < (DATE_FORMAT(CURRENT_DATE(),'%Y%m%d'))) and s.id_customer='$id_cus'";
							/*where ((s.active=0 and s.is_closed=1) or  (DATE_FORMAT(s.maturity_date,'%Y%m%d')) < (DATE_FORMAT(CURRENT_DATE(),'%Y%m%d'))) and s.id_customer='$id_cus'";*/
   	     //  echo $sql;exit;
   		$accounts = $this->db->query($sql);	
   		$records = $accounts->result_array();
   		foreach($records as $record){
   		    $result[] = array('account_name' => $record['account_name'],
                        	'amount' => $record['amount'],
                        	'closing_balance' => $record['closing_balance'],
                        	'closing_weight' => $record['closing_weight'],
                        	'closing_date' => $record['closing_date'],
                        	'code' => ($record['has_lucky_draw'] == 0 ? $record['code'] : $record['group_code']),
                        	'id_scheme_account' => $record['id_scheme_account'],
                        	'max_chance' => $record['max_chance'],
                        	'metal' => $record['metal'],
                        	'mobile' => $record['mobile'],
                        	'name' => $record['name'],
                        	'ref_no' => $record['ref_no'],
                        	'scheme_acc_number' => $record['scheme_acc_number'],
                        	'group_code' => $record['group_code'],
                        	'scheme_name' => $record['scheme_name'],
                        	'scheme_type' => $record['scheme_type'],
                        	'start_date' => $record['start_date'],
                        	'total_installments' => $record['total_installments'],
                        	'flexible_sch_type' => $record['flexible_sch_type'],
                        	'is_closed' => $record['is_closed'],
                        	'maturity_date' => $record['maturity_date'],
                        	"max_weight"    => $record['max_weight'],
                        	"curr_symb_html"    => $record['curr_symb_html'],
                        );
   		}
		return $result;
	}
	function get_matured_account($id_cus)
	{
	    $sql ="select sc.flexible_sch_type,s.id_scheme_account,s.scheme_acc_number,concat (c.firstname,' ',if(c.lastname!=Null,c.lastname,'')) as name,s.ref_no,s.account_name,Date_Format(s.start_date,'%d-%m-%Y') as start_date,Date_Format(s.closing_date,'%d-%m-%Y') as closing_date,s.closing_balance,
							 s.closing_weight,sc.scheme_name,sc.code,sc.scheme_type,sc.total_installments,sc.max_chance,sc.amount,c.mobile,s.group_code as scheme_group_code, cs.has_lucky_draw,s.is_closed,date_format(s.maturity_date,'%d-%m-%Y') as maturity_date
							from
							  scheme_account s
							left join customer c on (s.id_customer=c.id_customer)
							left join scheme sc on (sc.id_scheme=s.id_scheme)
							join chit_settings cs
							where s.active=0  and (DATE_FORMAT(s.maturity_date,'%Y%m%d')) < (DATE_FORMAT(CURRENT_DATE(),'%Y%m%d')) and s.id_customer='$id_cus'";
			//	echo $sql;exit;
		$accounts = $this->db->query($sql);	
		$records = $accounts->result_array();
	}
	function clientEmail($id) 
	{
	$resultset = $this->db->query("select email from customer where email='".$id."'");
		return ($resultset->num_rows() > 0 ? TRUE : FALSE);	
	}
	function update_account($data,$id)
	{
		$this->db->where('id_scheme_account',$id);
		$status=$this->db->update('scheme_account',$data);
		return $status;
	}
	function getActivecardBrands($type)
	{
		$this->db->select('*'); 
		$this->db->where('active',1);
		$this->db->where('card_type',$type);
		$res = $this->db->get('card_brand');
		return $res->result_array();
	}
	function get_payment_gateway($idPGSettings)
	{
       $sql="SELECT
			      id_gateway,
			      `key`,
			      salt,
			      api_url,param_1,m_code,pg_name,
			      if(type=0,'Demo','Real') as type,
			      is_default
			 FROM gateway
			 WHERE is_default=1 and pg_settings_id=".$idPGSettings;
       return $this->db->query($sql)->row_array();
	}
	// functions to register existing scheme with validation
	function isAccExist_sktm($data) 
	{
		if($this->session->userdata('branch_settings')==1)
			{
				$id_branch  = $this->input->post('id_branch');
			}
			else
			{
				$id_branch =NULL;
			}
		if(isset($data['mobile'])){		
			$resultset = $this->db->query("select is_acc_registered,mobile_no from chit_customer where mobile_no='".$data['mobile']."' ".($id_branch!=NULL?' and BRANCH ='.$id_branch:'')." ");
		}
		else{
			$resultset = $this->db->query("select is_acc_registered,mobile_no from chit_customer where group_cus_no='".$data['scheme_acc_number']."' and group_name='".$data['group_name']."'");
		}
		if($resultset->num_rows() > 0 ){
			 if($resultset->row()->mobile_no !=NULL && $resultset->row()->is_acc_registered==0){
		 		return array('status'=>TRUE , 'mobile'=>$resultset->row()->mobile_no,'msg'=>'We will send OTP to mobile number associated with this account');	
			 }
			 elseif($resultset->row()->is_acc_registered==1){
			 	return array('status'=> FALSE,'msg'=>'Account already registered');	
			 }
			 else
			 	return array('status'=> FALSE,'msg'=>'Update mobile number in our branch');	
			 }
		else{
			 	return array('status'=> FALSE,'msg'=>'Enter valid details');	
			}
	}
	function insertExisAccData_sktm($data) 
	{
		$acc_data = array();
		//$branch_code = $this->get_branchid($data['id_branch']);
		if(isset($data['mobile'])){	
			$resultset = $this->db->query("select * from chit_customer where mobile_no='".$data['mobile']."'");	
		}
		else{
			$resultset = $this->db->query("select * from chit_customer where group_cus_no='".$data['scheme_acc_number']."' and group_name='".$data['group_name']."'");
		}
		if($resultset->num_rows() > 0 ){
			$records = array();
		foreach($resultset->result() as $row)
			{
			if($this->session->userdata('branch_settings')==1)
			{
				$id_branch  = $this->input->post('id_branch');
			}
			else
			{
				$id_branch =NULL;
			}
				$data['scheme_type']  = $row->scheme_type; 
				$data['amount']       = $row->AMOUNT; 
				$data['group_name']   = $row->GROUP_NAME; 
				$records = array(   'id_customer' 		=> $data['id_customer'],
									'id_scheme' 		=> $this->getschId($data),
									'scheme_acc_number' => $row->GROUP_CUS_NO,
									'account_name' 		=> $row->NAME,
									'start_date' 		=> $row->ENTRYDATE,
									'is_new' 			=> $row->is_new,
									'date_add' 			=> date("Y-m-d H:i:s"),
									'is_registered' 	=> 1,
									'active' 			=> 1,
									'is_opening' 		=> $row->is_opening,
									'balance_amount' 	=> $row->balance_amount,
									'balance_weight'	=> $row->balance_weight,
									'last_paid_weight' 	=> $row->last_paid_weight,
									'last_paid_chances'	=> $row->last_paid_chances,
									'last_paid_date' 	=> $row->last_paid_date,
									'paid_installments' => $row->paid_installments,
									'id_branch' 		=> $row->id_branch,
									'added_by' 			=> 1
									);
				$addData=$this->get_cityData($row->city_name);
				$address = array( 'id_customer' =>$data['id_customer'],
							  'address1' =>$row->ADDRESS1,
							  'address2' =>$row->ADDRESS2,
							  'address3' =>$row->ADDRESS3,
							  'id_city'	 =>$addData['id_city'],
							  'id_state' =>$addData['id_state']
							);
			$sql =$this->db->query("select * from address where id_customer=".$data['id_customer']);
				if($sql->num_rows() > 0){
					 $this->db->where('id_customer',$data['id_customer']);
				$updateCus = $this->db->update('address',$address);
				} 
				else{
					$updateCus = $this->db->insert('address',$address);	
				}
				if($updateCus){
					$status = $this->db->insert('scheme_account',$records);
					$acc_data[] = array('group_cus_no' =>$row->GROUP_CUS_NO,
										  'group_name' =>$row->GROUP_NAME,
										  'id_scheme_account' =>$this->db->insert_id()
										 );					
				}
				else{			 	
			 		return array('status'=> FALSE,'msg'=>'Unable to proceed your request,try again later or contact customer care');	
				}				
			}			 	
		  return array('status'=> TRUE,'data'=>$acc_data);						
		}
		 else{			 	
			 	return array('status'=> FALSE,'msg'=>'Unable to proceed your request,try again later or contact customer care');	
			 }
		}
	function getschId_sktm($data) 
		{
			if($data['scheme_type'] == 1){
				$result = $this->db->query("SELECT id_scheme FROM scheme s where s.code='".$data['group_name']."' and scheme_type='".$data['scheme_type']."'");
			}
			else{
				$result = $this->db->query("SELECT id_scheme FROM scheme s where s.amount='".$data['amount']."' and scheme_type='".$data['scheme_type']."'");
			}
			if($result->num_rows() > 0 ){
					return $result->row()->id_scheme;
				}
				else{
						return '';
				}
		}
	function insert_paymentData_sktm($data) 
	{
		foreach($data as $rowData){
		$resultset = $this->db->query("select * from chit_transaction c where group_cus_no='".$rowData['group_cus_no']."' and group_name='".$rowData['group_name']."'");
	if($resultset->num_rows() > 0 ){
		$records = array();
		$dues=1;
		foreach($resultset->result() as $row)
			{				
				$records = array( 	'id_scheme_account' =>$rowData['id_scheme_account'],
									'metal_rate' 		=>$row->GOLD_RATE,
									'receipt_no' 		=>$row->RECEIPT_NO,
									'metal_weight' 		=>$row->WEIGHT,
									'payment_amount' 	=>$row->AMOUNT,
									'date_payment' 		=>$row->RECEIPT_DATE,
									'payment_status' 	=>1,
									'date_add'			=>$row->RECEIPT_DATE,
									'payment_mode' 		=>($row->is_free_payment == 1?'FP':$row->CASH_TYPE) ,	
									'dues' 				=>$row->NO_OF_INSTAL,
									'payment_type' 		=>'Manual',
									'is_offline'		=> 1,
									'due_type' 			=>$row->due_type,
									'added_by' 			=>0, //admin
									'is_offline' 		=>1,
									'discountAmt' 		=>$row->discountAmt	
									);
				$status = $this->db->insert('payment',$records);
				if(!$status){
					return array('status' =>  FALSE);
				}
				$dues++;
			}			
		}
		else{
			return array('status' =>  FALSE);
		}
	  }
		return array('status' =>  $status);
	}
	function updateOfflineData($data)
	{	
		foreach($data as $rowData){
			$arrdata=array("is_acc_registered"=>1);			
			$this->db->where('group_name',$rowData['group_name']); 
			$this->db->where('group_cus_no',$rowData['group_cus_no']); 
			$status = $this->db->update('chit_customer',$arrdata);
			if(!$status){
				return array('status' =>  FALSE);
			}
		}	
	/*	echo $this->db->last_query()	
		echo $status;exit;*/
		return $status;
	}
	// END OF -- functions to register existing scheme with validation
	function get_branch()
	{
		$sql = "SELECT * FROM branch b  where show_to_all != 3 order by sort ";
		$branch = $this->db->query($sql)->result_array();		
		return $branch;
	} 
	function branchesData()
	{
		$sql = "SELECT id_branch,name,short_name FROM branch b  where show_to_all != 3 order by sort ";
		$branch = $this->db->query($sql)->result_array();		
		return $branch;
	} 
    function insChitwallet($id_wal_ac,$mobile,$id_customer)
	{
		$redeem_updated = [];
		$sql = $this->db->query("select date_format(iwt.entry_date,'%d-%m-%Y') as bill_date,iwd.trans_points,iwt.actual_redeemed,iwt.bill_no,category_code,trans_type from inter_wallet_trans	 iwt
		LEFT JOIN  inter_wallet_trans_detail iwd on iwd.id_inter_wallet_trans = iwt.id_inter_wallet_trans
		where mobile=".$mobile);
    	if($sql->num_rows() > 0){
		    foreach($sql->result_array() as $record){ 
		    	$b_date = date_create($record['bill_date']);
                $bill_date = date_format($b_date,"Y-m-d H:i:s");
    		        if($record['actual_redeemed'] > 0 ){
    		        	$debitdata = array('id_wallet_account'  => $id_wal_ac,
                						  'date_add' 	=> date('Y-m-d H:i:s'),
                						  'date_transaction' 	=> $bill_date,
                						  'transaction_type'	=> 1, // debit
                						  'value'				=> $record['actual_redeemed'],
                						  'ref_no'              => $record['bill_no'].'-'.$record['category_code'],
                						  'description'			=> 'Debited for bill no '.$record['bill_no'].' on '.$record['bill_date'],
                						  );
    		        	if(sizeof($redeem_updated) > 0){
    		        		$alreadyUpdated = 0;
    		        		foreach($redeem_updated as $k=>$v){
								if($k == $record['bill_no']){
									$alreadyUpdated = 1;
								}
							}	
							if($alreadyUpdated == 0){
								$this->db->insert('wallet_transaction',$debitdata);
    				    		$redeem_updated[$record['bill_no']]=1;
							}
						}else{
    				    	$this->db->insert('wallet_transaction',$debitdata);
    				    	$redeem_updated[$record['bill_no']]=1;
						}
    		        } 
    		        if($record['trans_type'] == 1 && $record['trans_points'] >0){
    		        	$data = array('id_wallet_account'   => $id_wal_ac,
            						  'date_add' 	=> date('Y-m-d H:i:s'),
                					  'date_transaction' 	=> $bill_date,
            						  'transaction_type'	=> ($record['trans_type'] == 1 ? 0 :1),
            						  'value'				=> $record['trans_points'],
            						  'ref_no'              => $record['bill_no'].'-'.$record['category_code'],
            						  'description'			=> 'Credited for bill no. '.$record['bill_no'].' on '.$record['bill_date'],
            						  );
        			    $status = $this->db->insert('wallet_transaction',$data);
    		        }
        			// Update Customer ID in inter_wallet_account
        			$this->db->where('mobile',$mobile);
        			$this->db->update('inter_wallet_account',array('id_customer' => $id_customer));
		    }
		}
		$sql->free_result();
		$tmp_redeem_updated_1 = [];
		// To insert data from temp table
		$tmp_table_1 = $this->db->query("select date_format(iwt.entry_date,'%d-%m-%Y') as bill_date,iwd.trans_points,iwt.actual_redeemed,category_code,iwt.bill_no,trans_type 
		from inter_wallet_trans_tmp_2	 iwt
		LEFT JOIN  inter_waltransdetail_tmp_1 iwd on iwd.id_inter_wallet_trans = iwt.id_inter_waltrans_tmp
		where mobile=".$mobile);
    	if($tmp_table_1->num_rows() > 0){
		    foreach($tmp_table_1->result_array() as $record){ 
		    	$b_date = date_create($record['bill_date']);
                $bill_date = date_format($b_date,"Y-m-d H:i:s");
    		        if($record['actual_redeemed'] > 0){
    		            $debitdata = array('id_wallet_account'  => $id_wal_ac,
                						  'date_add' 			=> date('Y-m-d H:i:s'),
                						  'date_transaction' 	=> $bill_date,
                						  'transaction_type'	=> 1, // debit
                						  'value'				=> $record['actual_redeemed'],
                						  'ref_no'              => $record['bill_no'].'-'.$record['category_code'],
                						  'description'			=> 'Debited for bill no '.$record['bill_no'].' on '.$record['bill_date'],
                						  );  
    				    if(sizeof($tmp_redeem_updated_1) > 0){
    		        		$talreadyUpdated = 0;
    		        		foreach($tmp_redeem_updated_1 as $k=>$v){
								if($k == $record['bill_no']){
									$talreadyUpdated = 1;
								}
							}	
							if($talreadyUpdated == 0){
								$this->db->insert('wallet_transaction',$debitdata);
    				    		$tmp_redeem_updated_1[$record['bill_no']]=1;
							}
						}else{
    				    	$this->db->insert('wallet_transaction',$debitdata);
    				    	$tmp_redeem_updated_1[$record['bill_no']]=1;
						}   
    		        } 
    		        if($record['trans_type'] == 1 && $record['trans_points'] >0){
    		        	$data = array('id_wallet_account'   => $id_wal_ac,
            						  'date_add' 			=> date('Y-m-d H:i:s'),
                					  'date_transaction' 	=> $bill_date,
            						  'transaction_type'	=> ($record['trans_type'] == 1 ? 0 :1),
            						  'value'				=> $record['trans_points'],
            						  'ref_no'              => $record['bill_no'].'-'.$record['category_code'],
            						  'description'			=> 'Credited for bill no. '.$record['bill_no'].' on '.$record['bill_date'],
            						  );
        			    $status = $this->db->insert('wallet_transaction',$data);
    		        }
        			// Update Customer ID in inter_wallet_account
        			$this->db->where('mobile',$mobile);
        			$this->db->update('inter_wallet_account',array('id_customer' => $id_customer));
		    }
		}
		$tmp_table_1->free_result();
		$tmp_redeem_updated = [];
		// To insert data from temp table
		$tmp_table = $this->db->query("select date_format(iwt.entry_date,'%d-%m-%Y') as bill_date,iwd.trans_points,iwt.actual_redeemed,category_code,iwt.bill_no,trans_type from inter_wallet_trans_tmp	 iwt
		LEFT JOIN  inter_waltransdetail_tmp iwd on iwd.id_inter_wallet_trans = iwt.id_inter_waltrans_tmp
		where mobile=".$mobile);
    	if($tmp_table->num_rows() > 0){
		    foreach($tmp_table->result_array() as $record){ 
		    	$b_date = date_create($record['bill_date']);
                $bill_date = date_format($b_date,"Y-m-d H:i:s");
    		        if($record['actual_redeemed'] > 0){
    		            $debitdata = array('id_wallet_account'  => $id_wal_ac,
                						  'date_add' 			=> date('Y-m-d H:i:s'),
                						  'date_transaction' 	=> $bill_date,
                						  'transaction_type'	=> 1, // debit
                						  'value'				=> $record['actual_redeemed'],
                						  'ref_no'              => $record['bill_no'].'-'.$record['category_code'],
                						  'description'			=> 'Debited for bill no '.$record['bill_no'].' on '.$record['bill_date'],
                						  );  
    				    if(sizeof($tmp_redeem_updated) > 0){
    		        		$talreadyUpdated = 0;
    		        		foreach($tmp_redeem_updated as $k=>$v){
								if($k == $record['bill_no']){
									$talreadyUpdated = 1;
								}
							}	
							if($talreadyUpdated == 0){
								$this->db->insert('wallet_transaction',$debitdata);
    				    		$tmp_redeem_updated[$record['bill_no']]=1;
							}
						}else{
    				    	$this->db->insert('wallet_transaction',$debitdata);
    				    	$tmp_redeem_updated[$record['bill_no']]=1;
						}   
    		        } 
    		        if($record['trans_type'] == 1 && $record['trans_points'] >0){
    		        	$data = array('id_wallet_account'   => $id_wal_ac,
            						  'date_add' 			=> date('Y-m-d H:i:s'),
                					  'date_transaction' 	=> $bill_date,
            						  'transaction_type'	=> ($record['trans_type'] == 1 ? 0 :1),
            						  'value'				=> $record['trans_points'],
            						  'ref_no'              => $record['bill_no'].'-'.$record['category_code'],
            						  'description'			=> 'Credited for bill no. '.$record['bill_no'].' on '.$record['bill_date'],
            						  );
        			    $status = $this->db->insert('wallet_transaction',$data);
    		        }
        			// Update Customer ID in inter_wallet_account
        			$this->db->where('mobile',$mobile);
        			$this->db->update('inter_wallet_account',array('id_customer' => $id_customer));
		    }
		}
		return TRUE;
	}
    function get_exisRegReq($id_cus)
    {
        $query = $this->db->query("SELECT if(remark = '','-',remark) as remark,id_customer,
        if(cs.has_lucky_draw=1,concat(ifnull(sg.group_code,''),' ',IFNULL(schReg.scheme_acc_number,'Not Allocated')),concat(ifnull(sch.code,''),' ',IFNULL(schReg.scheme_acc_number,'Not Allocated')))as scheme_acc_number,
		sch.code as group_code,
        schReg.ac_name,DATE_FORMAT(schReg.date_add,'%d-%m-%Y') AS date_add,schReg.id_scheme,schReg.ac_name AS ac_name,if(schReg.status=1,'A/c Created',if(schReg.status=2,'Rejected',if(schReg.status=0,'processing','')))as status 
        from scheme_reg_request schReg
        LEFT JOIN scheme AS sch ON sch.id_scheme = schReg.id_scheme	
        LEFT JOIN scheme_group as sg on  sg.id_scheme_group=schReg.id_scheme_group	
        join chit_settings cs
        WHERE id_customer = ".$id_cus." order by id_reg_request desc");	
        //LEFT JOIN branch AS br ON br.id_branch = schReg.id_branch	
        if($query->num_rows()>0)
        {
            return $query->result_array();
        }else{
            return array();
        }
    }
    //scheme_ acc_ no setting//
    function accno_generatorset() 
    {
        $resultset = $this->db->query("SELECT c.schemeacc_no_set FROM chit_settings c");	
            if($resultset->row()->schemeacc_no_set == 0)
            {
        return array('status'=>TRUE , 'schemeacc_no_set'=>$resultset->row()->schemeacc_no_set);
           }else{
        return array('status'=> FALSE, 'schemeacc_no_set'=>$resultset->row()->schemeacc_no_set);
          }
    }
    function getAllPG(){ 
       $record = array();
       $sql ="Select active,creditCard, debitCard,description,id_pg_settings,is_primary_gateway,netBanking,pg_code,pg_icon,pg_name,saveCard,sort from payment_gateway order by sort asc";
       $result = $this->db->query($sql); 
       if($result->num_rows() > 0){
        foreach( $result->result_array() as $row){
           	   $file =base_url().'admin/assets/img/gateway/'.$row['pg_icon'];
           	   $img_path = ($row['pg_icon'] != null ? (file_exists('admin/assets/img/gateway/'.$row['pg_icon'])? $file : null ):null);
           	$record[] = array( 'pg_name' => $row['pg_name'],'pg_code' => $row['pg_code'],'netBanking' => $row['netBanking'],'is_primary_gateway' => $row['is_primary_gateway'],'active' => $row['active'], 'description' => $row['description'],'id_pg_settings' => $row['id_pg_settings'],'saveCard' => $row['saveCard'],'creditCard' => $row['creditCard'],'debitCard' => $row['debitCard'], 'pg_icon' => $img_path);
        }
       }
       return $record;
    }
    function forgetUser($mobile)
	{
		$sql="select firstname ,email from customer where mobile=".$mobile;
		$result = $this->db->query($sql);	
	    return $result->row_array();	
	}
	function getPopup()
    {
	     $this->db->select('offer_img_path');
	   	 $this->db->where('type',2);
	   	 $this->db->where('active',1);
	   	 $res = $this->db->get('offers');
	   	 return $res->row('offer_img_path');
    }
    function getNotifications($id_cus,$id_sent_noti)
	{
	    $data = array();
		$sql="select id_sent_noti,noti_service,sn.id_customer,noti_title,noti_subtitle,noti_content,targetUrl,noti_img,sn.date_add,c.date_add from sent_notifications  sn
		Left join customer c on c.id_customer=".$id_cus." 
		where unix_timestamp(sn.date_add) >= unix_timestamp(c.date_add) and (sn.id_customer=0 or sn.id_customer=".$id_cus.") ".($id_sent_noti ==0 ? ' order by id_sent_noti desc limit 10':' and id_sent_noti < '.$id_sent_noti.' order by id_sent_noti desc limit 10');    
 		$result = $this->db->query($sql);	 
 		if($result->num_rows() > 0){
 		  $data =  $result->result_array();
 		} 
 		return $data;
	}
	function is_branchwise_cus_reg()
	{
		$sql="select cs.is_branchwise_cus_reg,cs.scheme_wise_acc_no,cs.branchWiseLogin,cs.branch_settings from chit_settings cs";
			$records = $this->db->query($sql)->row_array();
			return $records;
	}
	function getBranchGateways($branch_id)
	{
   		//$sql="SELECT * from gateway_branchwise where is_default=1 and id_branch=".$branch_id."";
		$data=$this->get_costcenter();
   		$sql="SELECT id_pg,id_branch,pg_name,pg_code,param_1,param_2,param_3,param_4,api_url,type,type,pg_icon,pg_icon,saveCard,saveCard,debitCard,netBanking,creditCard,date_add,is_primary_gateway,description,active from gateway where active=1 and is_default=1 ".($branch_id >0 && ($data['cost_center']==1 || $data['cost_center']==3) ? "and id_branch=".$branch_id."":'')." ";
		//print_r($sql);exit;
		$result = $this->db->query($sql); 
       if($result->num_rows() > 0){
        foreach( $result->result_array() as $row){
           	   $file =base_url().'admin/assets/img/gateway/'.$row['pg_icon'];
           	   $img_path = ($row['pg_icon'] != null ? (file_exists('admin/assets/img/gateway/'.$row['pg_icon'])? $file : null ):null);
           	$record[] = array( 'pg_name' => $row['pg_name'],'pg_code' => $row['pg_code'],'netBanking' => $row['netBanking'],'is_primary_gateway' => $row['is_primary_gateway'],'active' => $row['active'], 'description' => $row['description'],'id_pg' => $row['id_pg'],'saveCard' => $row['saveCard'],'creditCard' => $row['creditCard'],'debitCard' => $row['debitCard'], 'pg_icon' => $img_path);
        }
       }		
		//echo"<pre>"; print_r($record);exit; echo"<pre>";
       return $record;
   }
   	function getBranchGatewayData($branch_id,$pg_id)
  	{
		$data=$this->get_costcenter();	
   		$sql="SELECT param_1,param_2,param_3,param_4,pg_code,api_url,type,pg_name from gateway where 
   		id_pg=".$pg_id." ".($branch_id!='' &&$data['cost_center']==1 ? "and id_branch=".$branch_id."":'')."";
		$result=  $this->db->query($sql)->row_array();
		return $result;   	
   }
   function get_costcenter()
  	{
   		$sql="SELECT * from  chit_settings";
		$result=  $this->db->query($sql)->row_array();
		return $result;   	
   }
     function get_products($id_product)
	   {
		   $sql="select * from products where id_product=".$id_product."";
		   $result=  $this->db->query($sql)->row_array();
			return $result;   	
	   }
   	function insProduct_enquiry($data)  
    { 
		$status = $this->db->insert('product_enquiry',$data);
		return array('status' => $status, 'insertID' => $this->db->insert_id());
	}
    function getModules(){
		$sql2="SELECT  id_module,m_code,m_app,m_active from modules ";
			$module = $this->db->query($sql2); 
			return $module->result_array();
	}
	function get_giftsAccwise($id_customer)
	{
	    $file_path = base_url()."admin/assets/img/sch_image";
		$sql="select if(s.logo=null,s.logo ,concat('".$file_path."','/',s.logo) ) as logo,s.scheme_name,sa.id_scheme_account,if(cs.has_lucky_draw=1,concat(ifnull(sa.group_code,''),'  ',ifnull(sa.scheme_acc_number,'Not Allocated')),concat(s.code,'  ',IFNULL(sa.scheme_acc_number,'Not Allocated')))as scheme_acc_number,(select count(id_gift_issued) from gift_issued gi where gi.id_scheme_account=sa.id_scheme_account) as gift_issued 
		from  scheme_account sa
left join gift_issued g on sa.id_scheme_account = g.id_scheme_account
left join scheme s on s.id_scheme = sa.id_scheme 
left join customer c on c.id_customer = c.id_customer
join chit_settings cs
where sa.id_customer=".$id_customer." GROUP by sa.id_scheme_account";
		$result = $this->db->query($sql);	
	    return $result->result_array();	
	}
	function get_giftsListAccwise($id_scheme_ac)
	{
		$sql="select * from gift_issued  where id_scheme_account=".$id_scheme_ac;
		$result = $this->db->query($sql);	
	    return $result->result_array();	
	}
	function genTicketNo()
	{
		$sql="select max(ticket_no) as last_ticket_no from cust_enquiry";
		$result = $this->db->query($sql);	
	    $last_ticket_no = $result->row('last_ticket_no');
	    if($last_ticket_no == NULL){
	        $ticket_no = 1;
	        return str_pad($ticket_no, 6, '0', STR_PAD_LEFT);
	    }else{
	        $ticket_no = $last_ticket_no+1;
	        return str_pad($ticket_no, 6, '0', STR_PAD_LEFT);
	    }
	}
	    // User complaint listing //hh
	function get_custComplaints($id_cus) 
    {
       $sql="select  comments,id_enquiry,date_format(date_add,'%d-%m-%Y') as date_add,ticket_no, IF(status = 0, 'blue', IF(status = 1, 'yellow', IF(status = 2, 'green', 'stable')))  as color,IF(status = 0, 'Open', IF(status = 1, 'In Follow Up', IF(status = 2, 'Closed', ''))) as status FROM cust_enquiry 
       where type=3 and id_customer=".$id_cus."  order by date_add DESC";
       // print_r($sql);exit;
       return $this->db->query($sql)->result_array();
    }	
    function get_custComplaintStatus($id)  //last added complaint showed at the top/HH
    {
       $sql="select id_cusenq_status, IF(enq_status = 0, 'blue', IF(enq_status = 1, 'yellow', IF(enq_status = 2, 'green', 'stable')))  as color,IF(enq_status = 0, 'Open', IF(enq_status = 1, 'In Follow Up', IF(enq_status = 2, 'Closed', '')))  as status, `enq_description`, date_format(ces.date_add,'%d-%m-%Y') as date_add
       FROM cust_enquiry_status ces 
       Where id_enquiry=".$id." order by date_add DESC";   
      //print_r($sql);exit;
       return $this->db->query($sql)->result_array();
    }
    	    // User complaint listing //hh
    function get_custDTHRequests($id_cus) 
    {
       $sql="select  comments,type,id_enquiry,date_format(date_add,'%d-%m-%Y') as date_add,ticket_no, IF(status = 0, 'blue', IF(status = 1, 'yellow', IF(status = 2, 'green', 'stable')))  as color,IF(status = 0, 'Open', IF(status = 1, 'In Follow Up', IF(status = 2, 'Closed', ''))) as status FROM cust_enquiry where (type=5 or type=6) and id_customer=".$id_cus;
       // print_r($sql);exit;
       return $this->db->query($sql)->result_array();
    }	 
    // Coin enquiry listing
    function get_custCoinEnq($mobile,$id_cus) 
    {
       $sql=$this->db->query("select  
                comments,ce.id_enquiry,date_format(date_add,'%d-%m-%Y') as date_add,
                IF(status = 0, 'blue', IF(status = 1, 'yellow', IF(status = 2, 'green', 'stable')))  as color,
                IF(status = 0, 'Open', IF(status = 1, 'In Follow Up', IF(status = 2, 'Closed', ''))) as status,
                IF(coin_type = 1, 'With Neck', IF(coin_type = 2, 'Without Neck', IFNULL(coin_type,'-'))) as coin_type, IFNULL(gram,'-') as gram, IFNULL(product_name,'-') as product_name
            FROM cust_enquiry ce
            LEFT JOIN cust_enquiry_product cep on cep.id_enquiry = ce.id_enquiry
            WHERE type=7 and mobile='".$mobile."' or (id_customer=".$id_cus.")  order by date_add DESC"); 
            //print_r($this->db->last_query());exit;
       $result = $sql->result_array();
       return $result;
    }	
    function get_custCoinEnqStatus($id)
    {
       $sql="select 
                id_cusenq_status, IF(enq_status = 0, 'blue', IF(enq_status = 1, 'yellow', IF(enq_status = 2, 'green', 'stable')))  as color,
                IF(enq_status = 0, 'Open', IF(enq_status = 1, 'In Follow Up', IF(enq_status = 2, 'Closed', '')))  as status, `enq_description`, 
                date_format(ces.date_add,'%d-%m-%Y') as date_add,
                IF(coin_type = 1, 'With Neck', IF(coin_type = 2, 'Without Neck', IFNULL(coin_type,'-'))) as coin_type, IFNULL(gram,'-') as gram, IFNULL(product_name,'-') as product_name
           FROM cust_enquiry_status ces 
           LEFT JOIN cust_enquiry_product cep on cep.id_enquiry = ces.id_enquiry
           Where ces.id_enquiry=".$id." order by date_add DESC";   
      //print_r($sql);exit;
       return $this->db->query($sql)->result_array();
    }
    function ej_rate_history($id_branch,$from_date,$To_date,$branch_settings,$type)
	{
		if($type == 'lmx')
		{ 
    		if($branch_settings==1 && $id_branch!=null )
    		{
    			$sql="select date_format(updatetime,'%d-%m-%Y %H:%i:%s') as updatetime,goldrate_22ct,silverrate_1gm,platinum_1g from metal_rates m
    	   		left join branch_rate br on m.id_metalrates=br.id_metalrate 
    	   		where br.id_branch=".$id_branch." and date(updatetime) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($To_date))."' order by  br.id_metalrate desc ";
    		}
    	/*	else if($branch_settings==1)
    		{
    			$sql="select * from metal_rates 
    			left join branch_rate br on br.id_metalrate=metal_rates.id_metalrates 
    			where br.status=1 and date(updatetime) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($To_date))."'";
    		}*/
    		else
    		{
    		    //return array(); 
    			$sql="select date_format(updatetime,'%d-%m-%Y %H:%i:%s') as updatetime,goldrate_22ct,silverrate_1gm,platinum_1g from metal_rates m
    	   		left join branch_rate br on m.id_metalrates=br.id_metalrate 
    	   		where date(updatetime) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($To_date))."' order by  br.id_metalrate desc ";
    		}
	    }else
	    {
	        if($id_branch!=null){
	            $sql="select TRANSDATE as updatetime,if(m.Metaltype='Gold',m.RATE,'0.00')as goldrate_22ct,if(m.Metaltype='Silver',m.Rate,'0.00')as silverrate_1gm,
	                     if(m.Metaltype='Platinum',m.RATE,'0.00')as platinum_1g 
	                     from ej_metalratehistory m
	                     where (date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($To_date))."' ".($id_branch!='' ? " and m.id_branch=".$id_branch."" :'')." ORDER by TRANSDATE ASC";
	        }else
    		{
    			//return array();
    			$sql="select TRANSDATE as updatetime,if(m.Metaltype='Gold',m.RATE,'0.00')as goldrate_22ct,if(m.Metaltype='Silver',m.Rate,'0.00')as silverrate_1gm,
	                     if(m.Metaltype='Platinum',m.RATE,'0.00')as platinum_1g 
	                     from ej_metalratehistory m
	                     where (date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($To_date))."' ORDER by TRANSDATE ASC";
    		}
	           // echo $sql;exit;
	    }
		$result = $this->db->query($sql);
		return $result->result_array();
	}
    function get_rate_history($id_branch,$from,$to,$branch_settings)
	{			
		$result = []; 
		if($branch_settings == 0){
			$sql="SELECT  platinum_1g,m.goldrate_22ct,m.silverrate_1gm,Date_format(m.updatetime,'%d-%m%-%Y %h:%i %p')as updatetime
				FROM metal_rates m 
				WHERE (date(updatetime) between '".$from."' and '".$to."') ORDER by id_metalrates desc";
		}
		else if(!empty($id_branch) && $id_branch!=0 && $branch_settings == 1)
		{
			$sql="SELECT  platinum_1g,m.goldrate_22ct,m.silverrate_1gm,Date_format(m.updatetime,'%d-%m%-%Y %h:%i %p')as updatetime
				FROM metal_rates m
				LEFT JOIN branch_rate br on br.id_metalrate=m.id_metalrates
				WHERE (date(m.updatetime) between '".$from."' and '".$to."') and  br.id_branch=".$id_branch." ORDER by br.id_metalrate desc";
		}else{
			return $result;
		}
		$rate = $this->db->query($sql);
		$result = $rate->result_array();
		return $result;
	}
 //kyc Insert & Read Api// hh  
    function insert_kyc($data)
    {
		$status = $this->db->insert('kyc',$data); 
		return array('status' => $status, 'insertID' => $this->db->insert_id());
	}
  function get_kycstatus($id_cus) 
    {
       $sql="select bank_branch,name,IF(status = 0,'Pending',IF(status = 1,'In Progress',IF(status = 2,'Verified',IF(status = 3,'Rejected','')))) as status,IF(status = 0,'medium',IF(status = 1,'warning',IF(status = 2,'success',IF(status = 3,'danger','')))) as color,dob,id_kyc,date_format(date_add,'%d-%m-%Y') as date_add,number, bank_ifsc,IF(kyc_type = 1, 'Bank Account', IF(kyc_type = 2, 'PAN Card', IF(kyc_type = 3, 'Aadhaar', ''))) as kyc_type_name,kyc_type,IF(emp_verified_by = 0, 'Pending', IF(emp_verified_by = 1, 'In Progress', IF(emp_verified_by = 2, 'Verified',IF(emp_verified_by = 3, 'Rejected', '')))) as emp_verified_by,IF(kyc_type=3,'https://uidai.gov.in/','') as aadhaarlink FROM kyc where id_customer=".$id_cus;
         //print_r($sql);exit;
       return $this->db->query($sql)->result_array();
    }
 //kyc Insert & Read Api// hh    
   // User DTH listing And Status //hh
	function get_custDth($id_cus) 
    {
       $sql="select  comments,id_enquiry,date_format(date_add,'%d-%m-%Y') as date_add,title,type,IF(status = 0, 'blue', IF(status = 1, 'yellow', IF(status = 2, 'green', 'stable')))  as color,IF(status = 0, 'Open', IF(status = 1, 'In Follow Up', IF(status = 2, 'Closed', ''))) as status FROM cust_enquiry where (type=5 or type=6) and id_customer=".$id_cus;
       // print_r($sql);exit;
       return $this->db->query($sql)->result_array();
    }	
    function get_custDthStatus($id) 
    {
       $sql="select  IF(enq_status = 0, 'blue', IF(enq_status = 1, 'yellow', IF(enq_status = 2, 'green', 'stable')))  as color,IF(enq_status = 0, 'Open', IF(enq_status = 1, 'In Follow Up', IF(enq_status = 2, 'Closed', '')))  as status, `enq_description`, date_format(ces.date_add,'%d-%m-%Y') as date_add
       FROM cust_enquiry_status ces 
       Where id_enquiry=".$id;   
     // print_r($sql);exit;
       return $this->db->query($sql)->result_array();
    }
   // User DTH listing And Status //hh   
    // For Store locator listing
    function branch_details()
	{
	    $branches = array();
		$sql = " Select  b.id_branch, b.name, b.email,address1, address2, b.phone, b.mobile,logo,map_url,insta_link,fb_link,
        b.cusromercare, b.pincode, b.metal_rate_type,
        ct.name as city,s.name as state,cy.name as country
				from branch b
					left join country cy on (b.id_country=cy.id_country)
					left join state s on (b.id_state=s.id_state)
					left join city ct on (b.id_city=ct.id_city)
        where b.active=1 and (show_to_all = 1 or show_to_all = 2)";
        //print_r($sql);exit;
		$result = $this->db->query($sql);	
		if($result->num_rows() > 0)
		{
		    $branches = $result->result_array();
		    foreach($branches as $key=>$val){ 
    			$file = self::BRN_IMG_PATH.'/'.$val['logo']; 
    			$img_path = ($val['logo'] != null ? (file_exists($file)? $file : null ):null);
    			$branches[$key]['img'] =  $img_path;
		    }  
		}
		return $branches;
	}
	function insert_sch_enquiry($data)
    {
		$status = $this->db->insert(self::SCH_ENQ,$data);
		if($status)
		{
		    	return array('status' => $status, 'insertID' => $this->db->insert_id(),'message'=>'Enquiry Submitted Successfully');
		}
        else{
            return array('status' => 'false', 'message'=>'Unable to Proceed your Request');
        }	
	}
	function getGold22ct($is_branchwise_rate,$id_branch){
		if($is_branchwise_rate == 1 && $id_branch!='' && $id_branch!=0)
		{
			$sql = "SELECT m.goldrate_22ct FROM metal_rates m 
			LEFT JOIN branch_rate br on br.id_metalrate=m.id_metalrates
				  ".($id_branch!='' ?" WHERE br.id_branch=".$id_branch."" :'')." ORDER by br.id_metalrate desc LIMIT 1";
				  //print_r($sql);exit;
		}
		else{
			$sql = "SELECT m.goldrate_22ct FROM metal_rates m 
			WHERE m.id_metalrates=( SELECT max(m.id_metalrates) FROM metal_rates m )";
			//print_r($sql);exit;
		}
		$data = $this->db->query($sql); 
		if($data->num_rows() > 0){ 
			return $data->row()->goldrate_22ct;
		}else{
			return 0;
		}
	}
	function updFixedRate($data,$id_sch_ac){
		$this->db->where('id_scheme_account',$id_sch_ac); 
		$status = $this->db->update("scheme_account",$data);	
		//print_r($this->db->last_query());exit;
		return $status;//($this->db->affected_rows() >0 ?TRUE:FALSE);
	}
	function getMyGifts($id_cus,$mobile) 
    {
       $sql="SELECT gc.id_gift_card,code,amount,DATE_FORMAT(valid_from,'%d-%m-%Y') as valid_from,DATE_FORMAT(valid_to,'%d %M %Y') as valid_to,
       trans_to_mobile, gct.message as description,if(ifnull(trans_from,0) > 0 ,1,0) as is_shared, if(purchased_by=".$id_cus.",1,0) as purchased, purchased_by,
       trans_from,
       if(redeem_type = 1,'Redeem At Store','') as redeem_at
       FROM `gift_card` gc 
       LEFT JOIN gift_card_trans gct on (gct.id_gift_card = gc.id_gift_card) 
       WHERE gct.trans_to_mobile = ".$mobile." or (gc.purchased_by=".$id_cus." and trans_to_mobile is NULL)"; 
       //print_r($sql);exit;
       return $this->db->query($sql)->result_array();
    }
    function getGiftedCards($id_cus,$mobile) 
    {
       $sql="SELECT gc.id_gift_card,code,amount,DATE_FORMAT(valid_from,'%d-%m-%Y') as valid_from,DATE_FORMAT(valid_to,'%d %M %Y') as valid_to,
       trans_to_mobile,trans_to_email,gct.message as description,if(purchased_by=".$id_cus.",1,0) as purchased,
       if(redeem_type = 1,'Redeem At Store','') as redeem_at
       FROM `gift_card` gc 
       LEFT JOIN gift_card_trans gct on (gct.id_gift_card = gc.id_gift_card) 
       WHERE gct.trans_from=".$id_cus; 
       return $this->db->query($sql)->result_array();
    }
	function getGiftcardstatus($id_cus)
	{
		$result=$this->db->query("SELECT amount,payment_mode,payment_status from gift_card_payment where id_customer = ".$id_cus);
		foreach($result->result_array() as $row)
				{
					$records[] = array('amount'	=> 	$row['amount'],
										'payment_mode'	=>$row['payment_mode'],
										'payment_status' 		=> $row['payment_status']);
					};
		return $records;
			//print_r($this->db->last_query($sql)->res
	}
	function checkCusKYC($id_cus){
		$sql = $this->db->query("SELECT kyc_type from kyc where status=2 and id_customer = ".$id_cus);
		if($sql->num_rows()> 0){
		    $res = $sql->result_array();
		    $bank = FALSE;
		    $anyoneKYC = FALSE;
		    foreach($res as $r){
		        if($r['kyc_type'] == 1){
		            $bank = TRUE;
		        }else if($r['kyc_type'] == 2 || $r['kyc_type'] == 3 || $r['kyc_type'] == 4){
		            $anyoneKYC = TRUE;   
		        }
		       // echo $bank;
		    }
	        if($bank == TRUE && $anyoneKYC == TRUE){
	            $this->db->where('id_customer',$id_cus); 
    			$data = array("kyc_status" => 1);
    			$status = $this->db->update("customer",$data);	
	        }else{
	            $status = FALSE;
	        }
			if($status){
				return 1; 
			}else{
				return 0; 				
			}	
		}else{
			return 0;
		}
	}
	function get_terms_and_conditions()
	{
	    $sql="select * from general";
	    return $this->db->query($sql)->result_array();
	}	 	 
     function getCusBranch($id_customer){
	    $sql="select id_branch from customer where id_customer=".$id_customer;
	    $result = $this->db->query($sql);
	    if($result->num_rows() == 1){
	        return $result->row()->id_branch;
	    }else{
	        return NULL;
	    }
	}
	function getMatchingVillage($char)
	{
	    $sql = "Select * from village where village_name Like '$char%' ";
	    //print_r($sql);exit;
	    return	$result = $this->db->query($sql)->result_array();		
	}
	function isBranchWiseReg(){
	    $sql = $this->db->query("Select cs.is_branchwise_cus_reg,cs.branch_settings from chit_settings cs");
	    $row = $sql->row();
		if($row->branch_settings == 1 && $row->is_branchwise_cus_reg == 1) {
		    return true;
		}else{ 
		    return false; 
		}
	}
	function isRateFixed($id_sch_ac)
	{
	    $sql = $this->db->query("Select firstPayment_amt,fixed_wgt from scheme_account where id_scheme_account=".$id_sch_ac);
	    $res = array (
	    			"status" 			=> $sql->row()->fixed_wgt > 0 ? 1 :0,
	    			"firstPayment_amt"  => $sql->row()->firstPayment_amt
	    			);
		return $res;	
	}
	
	function get_ratesByJoin($start_date)
	{
	    $today = date('Y-m-d H:i:s');
	    $sql = ("SELECT mjdmagoldrate_22ct,goldrate_22ct,goldrate_24ct,silverrate_1gm,silverrate_1kg,mjdmasilverrate_1gm,platinum_1g,
                Date_format(updatetime,'%d-%m%-%Y %h:%i %p')as updatetime FROM `metal_rates` WHERE date(add_date) BETWEEN '".$start_date."' AND '".$today."' ORDER BY goldrate_22ct ASC LIMIT 1");
		return $this->db->query($sql)->row_array();
	}
	
	
	function getCusData($id)
	{
	    $sql = "Select * from customer where id_customer = ".$id;
	    return	$result = $this->db->query($sql)->row_array();		
	}
}
?>
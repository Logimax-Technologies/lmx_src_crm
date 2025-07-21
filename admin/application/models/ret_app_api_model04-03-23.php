<?php

if( ! defined('BASEPATH')) exit('No direct script access allowed');

class ret_app_api_model extends CI_Model

{ 

    const EMP_IMG_PATH = 'assets/img/employee';

    const CUS_IMG_PATH = 'assets/img/customer';

    const RETCAT_IMG_PATH = 'assets/img/ret_category';

    const RETPRO_IMG_PATH = 'assets/img/ret_product';

    const RETDES_IMG_PATH = 'assets/img/designs';

	function __construct()

    {      

        parent::__construct(); 

		

	} 

	

	// General Functions

    public function insertData($data,$table)

    {

    	$insert_flag = 0;

		$insert_flag = $this->db->insert($table,$data);

		return ($insert_flag == 1 ? $this->db->insert_id(): 0);

	}

	public function updateData($data,$id_field,$id_value,$table)

    {    

	    $edit_flag = 0;

	    $this->db->where($id_field,$id_value);

		$edit_flag = $this->db->update($table,$data);

		return ($edit_flag==1?$id_value:0);

	}	 

	public function deleteData($id_field,$id_value,$table)

    {

        $this->db->where($id_field, $id_value);

        $status= $this->db->delete($table); 

		return $status;

	}

	 

	function select_otp($otp)

	{

		$this->db->select('*');

		$this->db->where('otp_code',$otp);

		$status = $this->db->get("otp"); 

		return $status->row_array();

	}

	

	function otp_update($data,$id)

	{

		$this->db->where('id_otp',$id);

		$status = $this->db->update("otp",$data);

		return $status;

	}

	

	public function get_emp_by_username($name)

    {

		$this->db->select('id_employee,emp_code,date_of_birth,emp_code,id_profile,username,id_branch,login_branches');

		$this->db->where('username', $name); 

		$list_data=$this->db->get("employee"); 

		return $list_data->row_array();

	} 

	

	function loginOTP_exp()

	{

		 $sql="Select loginOTP_exp from chit_settings where id_chit_settings = 1";

		 return $this->db->query($sql)->row()->loginOTP_exp;

	}

	

	//validate user login

	function isValidLogin($username,$passwd)

	{

		$record = array('is_valid' => FALSE);

		$sql="

			Select 

				e.emp_code,e.username,login_branches,b.name as branch_name,p.req_otplogin,e.id_profile,e.id_employee,e.firstname,e.lastname,e.mobile,e.email,e.id_branch,e.image 

			From employee e 

				Left join branch b on b.id_branch=e.id_branch 

				Left join profile p on p.id_profile=e.id_profile 

			where e.active=1 and e.username='".$username."' and e.passwd='".$passwd."'"; 

		$result = $this->db->query($sql);

		if($result->num_rows() > 0)

		{

			$row = $result->row_array(); 

			$file = self::EMP_IMG_PATH.'/'.$row['id_employee'].'/employee.jpg';

			$img_path = ($row['image'] != null ? (file_exists($file)? $file : null ):null);

			$record = array('is_valid' => TRUE,'id_employee' => $row['id_employee'],'emp_code' => $row['emp_code'],'username' => $row['username'],'mobile' => $row['mobile'],'login_branches' => $row['login_branches'],'branch_name' => $row['branch_name'], 'email' => $row['email'],'id_profile' => $row['id_profile'],'req_otplogin' => $row['req_otplogin'],'id_branch' => $row['id_branch'], 'lastname' =>ucfirst( $row['lastname']),'firstname' => ucfirst($row['firstname']), 'image' => $img_path);

		    return $record;	

		}

	    		

	}

	

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

  

	function company_details()

	{

		$sql = " Select  cs.maintenance_text,cs.maintenance_mode,cs.mob_code as call_prefix,c.id_company,c.whatsapp_no,c.company_name,c.short_code,c.pincode,c.mobile,c.phone,c.email,c.website,c.address1,c.address2,c.id_country,c.id_state,c.id_city,ct.name as city,s.name as state,cy.name as country,cs.currency_symbol,cs.currency_name,c.phone1,c.mobile1,cs.allow_notification

				from company c

					join chit_settings cs

					left join country cy on (c.id_country=cy.id_country)

					left join state s on (c.id_state=s.id_state)

					left join city ct on (c.id_city=ct.id_city) ";

		$result = $this->db->query($sql);	

		return $result->row_array();

	}

	

	/*function get_currency($id_branch)

	{

		$sql = " Select c.company_name,cs.currency_symbol,cs.currency_name,cs.is_branchwise_cus_reg,cs.branch_settings,'&#8377;' as curr_symb_html

		from company c

		join chit_settings cs ";

		

		$data=$this->get_chit_settings();

		

		if($data['is_branchwise_rate']==1 &&$id_branch!='' && $id_branch!=0)

		{

			$sql1="SELECT  m.market_gold_18ct,m.goldrate_18ct,m.mjdmagoldrate_22ct,m.goldrate_22ct,m.goldrate_24ct,m.silverrate_1gm,m.silverrate_1kg,m.mjdmasilverrate_1gm,

				Date_format(m.updatetime,'%d-%m%-%Y %h:%i %p')as updatetime   

				FROM metal_rates m 

				LEFT JOIN branch_rate br on br.id_metalrate=m.id_metalrates

				  ".($id_branch!='' ?" WHERE br.id_branch=".$id_branch."" :'')." ORDER by br.id_metalrate desc LIMIT 1";

		}

		else{

    		$sql1="SELECT  m.market_gold_18ct,m.goldrate_18ct,m.mjdmagoldrate_22ct,m.goldrate_22ct,m.goldrate_24ct,m.silverrate_1gm,m.silverrate_1kg,m.mjdmasilverrate_1gm,

    				Date_format(m.updatetime,'%d-%m%-%Y %h:%i %p')as updatetime   FROM metal_rates m 

    				WHERE m.id_metalrates=( SELECT max(m.id_metalrates) FROM metal_rates m )";

		}

		

		$data = $this->db->query($sql);	

		$result['currency']=$data->row_array();

		$rate = $this->db->query($sql1);	

		

		$result['metal_rates']=$rate->row_array();

		return $result;

	}

	

	function get_chit_settings()

	{

		$sql="select * from chit_settings";

		$result = $this->db->query($sql);	

		return $result->row_array();

	}*/

	

	/** Customer functions  **/

    function insert_customer($data)

    {

		  $status = $this->db->insert("customer",$data['info']);        

		  $insertID = $this->db->insert_id();

			if($insertID){

					$data['address']['id_customer']=$insertID;

					$res=$this->db->insert("address",$data['address']);

					if($res){						

						$id_address=$this->db->insert_id();

						$address = array('id_address' => $id_address);

						$this->db->where('id_customer',$insertID); 

						$this->db->update("customer",$address);

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

	function wallet_accno_generator() 

	{

		$resultset = $this->db->query("SELECT c.wallet_account_type FROM chit_settings c");	

	     if($resultset->num_rows() == 1){

		  return array('wallet_account_type'=>$resultset->row()->wallet_account_type);

	    }	

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

		LEFT JOIN  inter_walTransDetail_tmp_1 iwd on iwd.id_inter_wallet_trans = iwt.id_inter_waltrans_tmp

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

		LEFT JOIN  inter_walTransDetail_tmp iwd on iwd.id_inter_wallet_trans = iwt.id_inter_waltrans_tmp

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

	

	function get_payment_details($id_customer,$id_branch)

	{  		

	

		$filename = base_url().'api/rate.txt'; 

	    $data = file_get_contents($filename);

		$result['metal_rates'] = (array) json_decode($data);

		

	    $schemeAcc = array();

	   

		$sql="Select 

			    sg.group_code as scheme_group_code, UNIX_TIMESTAMP(Date_Format(sg.start_date,'%Y-%m-%d')) as group_start_date,  UNIX_TIMESTAMP(Date_Format(sg.end_date,'%Y-%m-%d')) as  group_end_date,  cs.has_lucky_draw,

                s.allowSecondPay,s.free_payment,cs.firstPayamt_payable,sa.firstPayment_amt,sa.is_registered,

			    s.gst_type,s.gst,sa.id_scheme_account,

			    IF(s.discount=1,s.firstPayDisc_value,0.00) as discount_val,s.firstPayDisc_by,s.firstPayDisc,sa.is_new,

			    s.id_scheme,br.id_branch, br.short_name, br.name as branch_name, 

			    c.id_customer,s.min_amount,s.max_amount,s.pay_duration,s.discount_type,s.discount_installment,s.discount,

			   sa.scheme_acc_number as chit_number,

			    IFNULL(sa.account_name,if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname))) as account_name,

			    c.mobile,

			    s.scheme_type,

			    s.fix_weight,

			    s.code,

			    IFNULL(s.min_chance,0) as min_chance,

			    IFNULL(s.max_chance,0) as max_chance,

			    Format(IFNULL(s.max_weight,0),3) as max_weight, IF(s.max_weight=s.min_weight,'1','0') as wgt_type,

			    Format(IFNULL(s.min_weight,0),3) as min_weight,s.wgt_convert,

			    Date_Format(sa.start_date,'%d-%m-%Y') as start_date,				

			    IF(s.scheme_type=0 OR s.scheme_type=2,s.amount,IF(s.scheme_type=1 ,s.max_weight,if(s.scheme_type=3,s.min_amount,0))) as payable,				

				round(IF(s.scheme_type=0 OR s.scheme_type=2,s.amount,IF(s.scheme_type=1 ,s.max_weight,

				if(s.scheme_type=3 && s.max_amount!='',s.max_amount,0)))) as max_amount,				

				(SELECT m.goldrate_22ct FROM metal_rates m  order by id_metalrates Desc LIMIT 1)as metal_rate,

			    s.total_installments,				

				s.total_installments,IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),1,0) as  previous_paid,					

				IFNULL(IF(sa.is_opening=1,IFNULL(sa.paid_installments,0)+ IFNULL(if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues)),0), if(s.scheme_type = 1 and s.min_weight != s.max_weight or s.scheme_type=3 , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues))) ,0)

 as paid_installments,cs.branch_settings,

  

IFNULL(IF(sa.is_opening=1,IFNULL(balance_amount,0)+IFNULL(SUM(p.payment_amount * p.no_of_dues),0),IFNULL(SUM(p.payment_amount * p.no_of_dues),0)) ,0)

  as total_paid_amount,

FORMAT(sum(if(p.gst > 0,if((p.gst_type = 1),0,p.payment_amount-(p.payment_amount*(100/(100+p.gst)))),0)),0) as paid_gst,

  

IFNULL(IF(sa.is_opening=1,IFNULL(balance_weight,0)+IFNULL(SUM(p.metal_weight),0),IFNULL(SUM(p.metal_weight),0)),0.000)

 as total_paid_weight,  

  if((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) > s.total_installments,   (s.total_installments - if(sa.is_opening = 1,(count(DISTINCT((Date_Format(p.date_payment,'%Y%m'))))+sa.paid_installments),count(DISTINCT((Date_Format(p.date_payment,'%Y%m')))))),ifnull(((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m')))+1) - IF(sa.is_opening=1,IFNULL(sa.paid_installments,0)+ IFNULL(if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues)),0), if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues))),if((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) > s.total_installments,s.total_installments,(PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m')))))) as totalunpaid,     

   IFNULL(if(Date_Format(max(p.date_add),'%Y%m') = Date_Format(curdate(),'%Y%m'), (select SUM(ip.no_of_dues) from payment ip where Date_Format(ip.date_add,'%Y%m') = Date_Format(curdate(),'%Y%m') and sa.id_scheme_account = ip.id_scheme_account),IF(sa.is_opening=1, if(Date_Format(sa.last_paid_date,'%Y%m') = Date_Format(curdate(),'%Y%m'), 1,0),0)),0) as currentmonthpaycount,  

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

					IFNULL(PERIOD_DIFF(Date_Format(curdate(),'%Y%m'),Date_Format(max(p.date_add),'%Y%m')),IF(sa.is_opening=1,PERIOD_DIFF(Date_Format(curdate(),'%Y%m'),Date_Format(sa.last_paid_date,'%Y%m')),0)) as last_paid_duration,

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

				sa.disable_payment,s.charge,s.charge_type,s.charge_head,p.payment_status,

				cs.currency_name,

				cs.currency_symbol

			From scheme_account sa

			Left Join scheme s On (sa.id_scheme=s.id_scheme)

			

			Left Join branch br  On (br.id_branch=sa.id_branch)

			Left Join scheme_group sg On (sa.group_code = sg.group_code )

			Left Join payment p On (sa.id_scheme_account=p.id_scheme_account and (p.payment_status=1 or p.payment_status=2))

			Left Join customer c On (sa.id_customer=c.id_customer and c.active=1)

			Left Join

			(	Select

				  sa.id_scheme_account,

				  COUNT(Distinct Date_Format(p.date_add,'%Y%m')) as paid_installment,

				  COUNT(Date_Format(p.date_add,'%Y%m')) as chances,

				  SUM(p.payment_amount) as total_amount,

				  SUM(p.metal_weight) as total_weight

				From payment p

				Left Join scheme_account sa on(p.id_scheme_account=sa.id_scheme_account and sa.active=1 and sa.is_closed=0)

				Where (p.payment_status=1 or p.payment_status=2) and  Date_Format(Current_Date(),'%Y%m')=Date_Format(p.date_add,'%Y%m')

				Group By sa.id_scheme_account

			) cp On (sa.id_scheme_account=cp.id_scheme_account)	

			

			

			left join(Select sa.id_scheme_account, COUNT(Distinct Date_Format(p.date_add,'%d%m')) as paid_installment,

					COUNT(Date_Format(p.date_add,'%d%m')) as chance,

					SUM(p.payment_amount) as total_amount,

					SUM(p.metal_weight) as total_weight

					From payment p

					Left Join scheme_account sa on(p.id_scheme_account=sa.id_scheme_account and sa.active=1 and sa.is_closed=0)

					Where  (p.payment_status=2 or p.payment_status=1) and  Date_Format(Current_Date(),'%d%m')=Date_Format(p.date_add,'%d%m')

					Group By sa.id_scheme_account)sp on(sa.id_scheme_account=sp.id_scheme_account)

			 Left Join postdate_payment pp On (sa.id_scheme_account=pp.id_scheme_account and (pp.payment_status=2 or pp.payment_status=7) and (Date_Format(pp.date_payment,'%Y%m')=Date_Format(curdate(),'%Y%m')))

				JOIN chit_settings cs 

		Where sa.active=1 and sa.is_closed = 0 and c.id_customer=".$id_customer."   ".($id_branch > 0 ? 'and sa.id_branch='.$id_branch :'')."

			Group By sa.id_scheme_account";

			

		

		$records = $this->db->query($sql);

		

		if($records->num_rows()>0)

		{

			foreach($records->result() as $record)

			{

				

				$allowed_due = 0;

				$due_type = '';

				$checkDues = TRUE;

				$allowSecondPay = FALSE;

				

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

				

				if($checkDues)

				{

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

			}

				$schemeAcc[] = array(

										'gst_type'					=> $record->gst_type,	

										'pay_duration' 		    	=> $record->pay_duration,

										'branch_settings' 		    => $record->branch_settings,

										'min_chance' 		        => $record->min_chance,

										'max_chance' 		        => $record->max_chance,

										'min_amount' 		        => $record->min_amount,

										'firstPayment_amt' 		    => $record->firstPayment_amt,

										'firstPayamt_payable' 		=> $record->firstPayamt_payable,

										'multiply_value' 		    => 500,

										'max_amount'                => round(($record->scheme_type==3 && $record->max_amount!=0 && $record->max_amount!=''? ((($record->firstPayamt_payable==1) && ($record->paid_installments>0))|| ($record->is_registered==1)?$record->firstPayment_amt :($record->max_amount - str_replace(',', '',$record->current_total_amount))):

										($record->scheme_type==3 && $record->max_weight!=0 && $record->max_weight!=''? (($record->max_weight - $record->current_total_weight)*$record->metal_rate) : $record->payable))),

										'metal_rate'                => $record->metal_rate,

										'gst' 						=> $record->gst,

										'paid_gst' 					=> $record->paid_gst,

										'id_branch' 				=> $record->id_branch,

										'short_name' 				=> $record->short_name,

										'branch_name' 				=> $record->branch_name,

										'currentmonthpaycount' 		=> $record->currentmonthpaycount,

										'totalunpaid' 				=> $record->totalunpaid,

										'id_scheme_account' 		=> $record->id_scheme_account,

										'max_wgt_rate' 				=> ($record->is_flexible_wgt == 1?($record->max_weight - $record->current_total_weight):$record->max_weight) * $result['metal_rates']['goldrate_22ct'],

										'charge_head' 				=> $record->charge_head,

										'charge_type' 				=> $record->charge_type,

										'charge' 					=> $record->charge,

										'chit_number' 				=> $record->chit_number,

										'account_name' 				=> $record->account_name,

										'start_date' 				=> $record->start_date,

										'mobile' 					=> $record->mobile,

										'is_flexible_wgt' 	    	=> $record->is_flexible_wgt,

										'currency_symbol' 			=> $record->currency_symbol,

										'payable' => (($record->scheme_type==3 && $record->max_amount!=0  && $record->max_amount!=''?((($record->firstPayamt_payable==1)&&($record->paid_installments>0)||($record->is_registered==1))?round($record->firstPayment_amt) :round($record->max_amount-str_replace(',', '',$record->current_total_amount))):($record->scheme_type==3 && ($record->max_weight!=0 || $record->max_weight!='')? round(($record->max_weight - $record->current_total_weight)*$record->metal_rate) : $record->payable))),							

										'code' 						=> ($record->has_lucky_draw == 1 ?  $record->scheme_group_code : $record->code),

										'scheme_type' 				=> $record->scheme_type,

										'total_installments'		=> $record->total_installments,

										'paid_installments' 		=> $record->paid_installments,

										'total_paid_amount' 		=> $record->total_paid_amount,

										'total_paid_weight' 		=> $record->total_paid_weight,

										'current_total_amount' 		=> $record->current_total_amount,

										'current_paid_installments'	=> $record->current_paid_installments,

										'current_chances_used' 		=> $record->current_chances_used,

										'current_chances_pay'       => $record->current_chances_pay,

										'eligible_weight' 		    => ($record->max_weight - $record->current_total_weight),

										'allow_unpaid_months' 		=> $record->allow_unpaid_months,

										'last_paid_duration' 		=> $record->last_paid_duration,

										'last_paid_date' 			=> $record->last_paid_date,

										'last_paid_month' 			=> ($record->last_paid_month!='' || $record->last_paid_month!=NULL ? $record->last_paid_month : 0),

										'is_pan_required' 			=> $record->is_pan_required,	

										'wgt_convert' 			    => $record->wgt_convert, 

										'last_transaction'  	    => $this->getLastTransaction($record->id_scheme_account),

										'isPaymentExist' 			=> $this->isPaymentExist($record->id_scheme_account),

										'isPendingStatExist' 		=> $this->isPendingStatExist($record->id_scheme_account),

										'max_weight' 				=> $record->max_weight,

										'current_total_weight' 		=> $record->current_total_weight,

										'previous_amount_eligible' 	=> $record->previous_amount_eligible,

										'cur_month_pdc' 			=> $record->cur_month_pdc,

										'allow_pay'  => ($checkDues? ($allowSecondPay == FALSE ? ($record->scheme_type==3 && $record->paid_installments <= $record->total_installments && (($record-> current_total_amount < $record-> max_amount || $record-> current_total_weight < $record-> max_weight)) && $record->current_chances_pay < $record->max_chance ?'Y':($record->disable_payment != 1 && ($record->payment_status !=2) ? ($record->cur_month_pdc < 1 ? ($record->paid_installments <= $record->total_installments ?($record->is_flexible_wgt?($record->current_total_weight >= $record->max_weight || $record->current_chances_used >= $record->max_chance ?'N':'Y'):($record->paid_installments <  $record->total_installments ?($record->allow_unpaid == 1  && $record->totalunpaid >0 && ($record->currentmonthpaycount-1) < $record->allow_unpaid_months ?'Y':($record->allow_advance == 1 && $record->advance_months > 0 && ($record->currentmonthpaycount -1) < $record->advance_months ?'Y':($record->currentmonthpaycount == 0 ? 'Y': 'N'))):'N')):'N'):'N'):'N')):'Y' ):'N'),	

										'allowed_dues'  			=>($record->is_flexible_wgt == 1 && $record->scheme_type==3? 1:$allowed_due),

										'allowPayDisc'      		=> ($record->discount == 1 ? ($record->discount_type == 0? 'All': $record->discount_installment ) : 0),

										'firstPayDisc' 				=> $record->firstPayDisc,

										'firstPayDisc_by' 			=> $record->firstPayDisc_by,

										'discount_val' 				=> $record->discount_val,

									 	'due_type' 					=> ($record->is_flexible_wgt == 1 ? 'ND':$due_type),

										'max_allowed_limit'  		=>($record->is_flexible_wgt == 1 ? 1:$allowed_due),

										'sel_due'  					=>1,   //default selected due

										'pdc_payments'  			=>($record->cur_month_pdc > 0 ? $this->get_postdated_payment($record->id_scheme_account) : NULL) 

									);				

			}			

		        

				  return array('chits' => $schemeAcc);

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

	

		//Checking the customer mobile already registered

	function isMobileExists($mobile)

	{

		$this->db->select('mobile');

		$this->db->where('mobile', $mobile);

        

		$customer= $this->db->get("customer");	  

		if($customer->num_rows()>0)

		{

			return TRUE;

		}			

	}

	function clientEmail($id) 

	{

	$resultset = $this->db->query("select email from customer where email='".$id."'");

		return ($resultset->num_rows() > 0 ? TRUE : FALSE);	

		

	}

	function get_customerByMobile($mobile,$emp_branch,$br_settings)

	{

		$record = array();

		$sql="Select c.id_customer,c.firstname,c.lastname,c.notification,c.mobile,c.email,c.id_branch,c.cus_img From customer c where c.mobile=".$mobile;

		/*if($br_settings == 1){

			if($emp_branch != NULL){

				$sql = $sql." and c.id_branch=".$emp_branch;

			}

		}*/

		$result = $this->db->query($sql);

		if($result->num_rows() > 0)

		{

				$row = $result->row_array(); 

				$file = self::CUS_IMG_PATH.'/'.$row['id_customer'].'/customer.jpg';

				$img_path = ($row['cus_img'] != null ? (file_exists($file)? $file : null ):null);

				$record = array('mobile' => $row['mobile'],'id_customer' => $row['id_customer'], 'email' => $row['email'],'id_branch' => $row['id_branch'], 'lastname' =>ucfirst( $row['lastname']),'firstname' => ucfirst($row['firstname']), 'cus_img' => $img_path);

		}

		return $record;

	} 

	

	function cusByMobileBranchWise($mobile,$emp_branch,$br_settings)

	{

		$record = array();

		$sql="Select c.id_customer,c.firstname,c.lastname,c.notification,c.mobile,c.email,c.id_branch,c.cus_img From customer c where c.mobile=".$mobile;

		if($br_settings == 1){

			if($emp_branch != NULL){

				$sql = $sql." and c.id_branch=".$emp_branch;

			}

		}

		$result = $this->db->query($sql);

		if($result->num_rows() > 0)

		{

				$row = $result->row_array(); 

				$file = self::CUS_IMG_PATH.'/'.$row['id_customer'].'/customer.jpg';

				$img_path = ($row['cus_img'] != null ? (file_exists($file)? $file : null ):null);

				$record = array('mobile' => $row['mobile'],'id_customer' => $row['id_customer'], 'email' => $row['email'],'id_branch' => $row['id_branch'], 'lastname' =>ucfirst( $row['lastname']),'firstname' => ucfirst($row['firstname']), 'cus_img' => $img_path);

		}

		return $record;

	}  

	

	function getWalletPaymentContent($id_payment){

	    $sql="Select

				  p.id_payment,iwa.available_points,sa.id_branch as branch,sa.id_scheme_account,cs.schemeacc_no_set,sa.id_scheme,cs.receipt_no_set,cs.scheme_wise_receipt,sa.scheme_acc_number,

				  ifnull(iwa.mobile,0) as isAvail,c.email,c.mobile,redeemed_amount,actual_trans_amt,cs.allow_referral,cs.walletIntegration,c.id_customer,cs.wallet_points,cs.wallet_amt_per_points,cs.wallet_balance_type

				From payment p

				Join chit_settings cs

				Left Join scheme_account sa on (p.id_scheme_account=sa.id_scheme_account)

				Left Join customer c on (c.id_customer=sa.id_customer)

				LEFT JOIN inter_wallet_account iwa on iwa.mobile=c.mobile

				Where p.id_payment='".$id_payment."'";

	      return $this->db->query($sql)->row_array();

	}

	

	function getPayGenData($id_payment)

	{

		$sql = "Select p.id_payment,sa.id_scheme_account,sa.scheme_acc_number,sa.id_scheme,cs.schemeacc_no_set,cs.receipt_no_set,cs.scheme_wise_receipt,p.ref_trans_id,cs.edit_custom_entry_date,cs.custom_entry_date

				 From payment p

				 left join scheme_account sa on sa.id_scheme_account=p.id_scheme_account

				 join chit_settings cs

				 Where p.id_payment=".$id_payment;

				

		return $this->db->query($sql)->row_array();	

	}

	

	function get_paymenthistory($mobile,$id_employee)

	{

		$records = array();

		$query_scheme = $this->db->query("select id_payment, DATE_FORMAT(date_payment,'%d-%m-%Y') AS date_payment, metal_rate, payment_amount, metal_weight,pay.receipt_no,pay.add_charges,if(pay.payment_type = 'Payu checkout',(payment_amount+ifnull(add_charges,0.00)), payment_amount) as total_amt,sch.charge_head,pay.gst,pay.gst_type,br.id_branch, br.short_name, br.name as branch_name,cs.branch_settings,IFNULL(sa.account_name,'-') as account_name,sa.id_scheme_account,pay.id_employee,

		(select name from branch b where b.id_branch =cus.id_branch) as cus_branch_name,

									 if(payment_mode='CC','Credit Card',if(payment_mode='NB','Net Banking', 

									  if(payment_mode='CD','Cheque or DD',if(payment_mode='CO','Cash Pick Up',

									  if(payment_mode='OP','Other',if(payment_mode='DC','Debit Card', if(payment_mode='FP','Enrollment Offer',if(payment_mode='CSH','Cash',payment_mode)) )))))) as payment_mode,IFNULL(id_transaction,'-') as id_transaction, if(payment_status = 1, 'Success',if(payment_status = 2, 'Yet to Approve',if(payment_status = 5, 'Returned',if(payment_status = 6, 'Refund',if(payment_status = 7, 'Pending',if(payment_status = 3, 'Failed',if(payment_status = 4, 'Cancelled','')))))))

									  as payment_status ,sch.code , IFNULL(sa.scheme_acc_number,'Not Allocated') as scheme_acc_number ,ref_no AS client_id, scheme_name,'&#8377;' as curr_symb_html,cs.currency_symbol,pay.payment_type,

										if(scheme_type = 0,'Amount Scheme',IF(scheme_type=1,'Weight Scheme','Amt to Wgt scheme')) as scheme_type,date(pay.entry_date) as entry_date,

										

										sa.group_code as scheme_group_code,cs.has_lucky_draw

										FROM payment as pay

										left join scheme_account AS sa on sa.id_scheme_account = pay.id_scheme_account

										Left Join branch br  On (pay.id_branch=br.id_branch)

										left join scheme as sch on sch.id_scheme = sa.id_scheme

										left join customer as cus on  cus.id_customer = sa.id_customer

										left join payment_mode pm on pay.payment_mode=pm.short_code

										join chit_settings cs

										WHERE cus.mobile=".$mobile." ORDER By id_payment DESC"); 

			if($query_scheme->num_rows() > 0)

			{

				foreach($query_scheme->result() as $row)

				{

					

			/*Add GST GST Amount = ( Original Cost * GST% ) / 100 Net Price = Original Cost + GST Amount

			Remove GST GST Amount = Original Cost - ( Original Cost * ( 100 / ( 100 + GST% ) ) ) Net Price = Original Cost - GST Amount */

			

			$paid_gst = 0.00;

			$add_gst = 0.00;

			$ins_no = "";

			$allow_print = 0;

			

			if($row->payment_status == 'Yet to Approve' || $row->payment_status == 'Success'){	

				$ins_no = $this->getInsNo($row->id_payment,$row->id_scheme_account);

				if($row->id_employee == $id_employee && $row->entry_date == date('Y-m-d')){

					$allow_print = 1;

				}				

			} 		

				

			if($row->gst > 0){				

				if($row->gst_type == 1){				

					$paid_gst = $row->payment_amount*($row->gst/100);	

					$add_gst = $paid_gst;			

				}

				else{

					$paid_gst = $row->payment_amount-($row->payment_amount*(100/(100+$row->gst)));	

				}

			}

					$records[] = array('allow_print' => $allow_print,'paid_due' => $ins_no,'cus_branch_name' => $row->cus_branch_name,'curr_symb_html' => $row->curr_symb_html,'ac_name' => $row->account_name,'id_payment' => $row->id_payment,'date_payment' => $row->date_payment,'receipt_no' => $row->receipt_no,'metal_rate' => $row->metal_rate, 'payment_amount' => number_format($row->payment_amount),'metal_weight' => $row->metal_weight,'payment_mode' => $row->payment_mode,'id_branch' => $row->id_branch,'short_name' => $row->short_name,'branch_name' => $row->branch_name,	'branch_settings' => $row->branch_settings,'id_transaction' => $row->id_transaction,'payment_status' => $row->payment_status,'scheme_acc_number' => ($row->has_lucky_draw == 1 ? $row->scheme_group_code.' '.$row->scheme_acc_number : $row->code.' '.$row->scheme_acc_number),'client_id' => $row->client_id,'scheme_name' => $row->scheme_name, 'scheme_type' => $row->scheme_type, 'currency_symbol' => $row->currency_symbol, 'add_charges' => $row->add_charges, 'payment_type' => ($row->payment_type == 'CSH'?'Cash':$row->payment_type), 'total_amt' => number_format(($row->total_amt+$add_gst),'0','.',''), 'charge_head' => $row->charge_head, 'gst' => $row->gst, 'gst_type' => $row->gst_type,'paid_gst'=>number_format($paid_gst,'2','.',''));

				}

			}

		return $records;

	}

	

	function getInsNo($id_payment,$schId){

		$sql = $this->db->query("select IF(sa.is_opening=1,IFNULL(sa.paid_installments,0)+ COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')),COUNT(Distinct Date_Format(pay.date_payment,'%Y%m'))) as installment 

			from payment pay 

			left join scheme_account sa on sa.id_scheme_account=pay.id_scheme_account

			where pay.id_scheme_account=".$schId." and id_payment<=".$id_payment." group by pay.id_scheme_account");//echo $this->db->last_query();exit;

		return $sql->row('installment');

	}

	

	function get_branch($emp_branch,$id_profile)

	{		

		if($id_profile == 1 || $id_profile == 2 ){

			$sql = "SELECT id_branch,b.name FROM branch b  where  active=1";		

		}else{

			if($emp_branch == NULL){

				$sql = "SELECT id_branch,b.name FROM branch b  where (show_to_all = 1 or show_to_all = 2) and active=1";

			}else{

				$sql = "SELECT id_branch,b.name FROM branch b  where (show_to_all = 1 or show_to_all = 2) and active=1 and id_branch=".$emp_branch;

			} 	

		}

		

		$branch = $this->db->query($sql)->result_array();		

		return $branch;		

	} 

	

	function getChitSettings(){

		$sql = $this->db->query("

		SELECT 

			id_chit_settings,allow_join_multiple,allow_join_unpaid,currency_name,currency_symbol,mob_code,mob_no_len,maintenance_mode,maintenance_text,branch_settings,branchWiseLogin,is_branchwise_cus_reg,is_branchwise_rate,login_branch,'&#8377;' as curr_symb_html

		FROM chit_settings");

		return $sql->row_array();	 

	}

	

	function currencyAndSettings($id_branch)

	{ 

		$settings = $this->getChitSettings(); 

		$result['settings'] = $settings; 

		if($settings['is_branchwise_rate'] == 1) // Branchwise Rate

		{

		    if($id_branch!='' && $id_branch!=0)  

		    {

                $rateSQL = "SELECT  b.name as name,m.mjdmagoldrate_22ct,m.goldrate_22ct,m.goldrate_24ct,
                m.goldrate_18ct, 
                m.silverrate_1gm,m.silverrate_1kg,m.mjdmasilverrate_1gm,platinum_1g,

                Date_format(m.updatetime,'%d-%m%-%Y %h:%i %p')as updatetime   

                FROM metal_rates m 

	                LEFT JOIN branch_rate br on br.id_metalrate=m.id_metalrates

	                LEFT JOIN branch b on b.id_branch=br.id_branch

                ".($id_branch!='' ?" WHERE br.id_branch=".$id_branch."" :'')." ORDER by br.id_metalrate desc LIMIT 1"; 

		    }

		    else

		    {

		        $rateSQL = "SELECT  

		        	b.name as name,m.mjdmagoldrate_22ct,m.goldrate_22ct,m.goldrate_24ct,m.silverrate_1gm,m.silverrate_1kg,
		        	m.goldrate_18ct, 
		        	m.mjdmasilverrate_1gm,platinum_1g, Date_format(m.updatetime,'%d-%m%-%Y %h:%i %p')as updatetime   

                FROM metal_rates m 

	                LEFT JOIN branch_rate br on br.id_metalrate=m.id_metalrates

	                LEFT JOIN branch b on b.id_branch=br.id_branch

                WHERE br.id_branch=1 ORDER by br.id_metalrate desc LIMIT 1";  

		    }  

		}

		else{ // Common Rate

			$rateSQL = "SELECT  

					m.mjdmagoldrate_22ct,m.goldrate_22ct,m.goldrate_24ct,m.silverrate_1gm,m.silverrate_1kg,
					
					m.goldrate_18ct, 
					m.mjdmasilverrate_1gm,platinum_1g, 

					Date_format(m.updatetime,'%d-%m%-%Y %h:%i %p')as updatetime   

				FROM metal_rates m 

				ORDER BY id_metalrates DESC LIMIT 1";

		}  

		$result['metal_rates'] = $this->db->query($rateSQL)->row_array();

		

		/*$data = $this->db->query($sql);	 

		$result['currency']=$data->row_array();*/  

		

		if($result['metal_rates']['silverrate_1gm'] == 0)

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

		

		$sql_ret_sett = "SELECT name,value,description from ret_settings"; 

		$ret_settings = $this->db->query($sql_ret_sett)->result_array();

		foreach($ret_settings as $r){

		    $result['ret_settings'][$r['name']] = $r['value'];

		}

		

		return $result;

	}

	function getBranches($type){

		if($type == "list"){

			$sql = $this->db->query("SELECT b.is_ho,b.name,b.id_branch FROM branch b Where active = 1");	

		}else{

			

		}

		return $sql->result_array();

	} 

	

	function getBranchEmployees($id_branch){ 

		$result = [];

		$data = $this->db->query("SELECT 

									e.id_employee,

									CONCAT(CONCAT(e.emp_code,'-',e.firstname),' ',e.lastname)as emp_name,

									s.disc_limit_type,s.disc_limit,e.login_branches,

									s.allow_branch_transfer,cs.login_branch,

									IF(s.allowed_old_met_pur = 1,'1-Gold,2-Silver',IF(s.allowed_old_met_pur = 2,'1-Gold',IF(s.allowed_old_met_pur = 3,'2-Silver','')) ) as allowed_old_met_pur

								FROM employee e

									LEFT JOIN employee_settings s on s.id_employee = e.id_employee

									JOIN chit_settings cs");

		$employees = $data->result_array();

		if(sizeof($employees) > 0){

			if($id_branch == 0 || $id_branch == '' || $employees[0]['login_branch'] == 0){

				return $employees;

			}else{

				foreach($employees as $emp){

					if($emp['login_branches'] == 0 || $emp['login_branches'] == NULL){

						$result[] = array( 

									"id_employee" 			=> $emp['id_employee'],

									"emp_name" 				=> $emp['emp_name'],

									"disc_limit_type"		=> $emp['disc_limit_type'],

									"disc_limit" 			=> $emp['disc_limit'],

									"allowed_old_met_pur"	=> $emp['allowed_old_met_pur'],

									"allow_branch_transfer" => $emp['allow_branch_transfer']

								);

					}else{

						$login_branches = explode(',',$emp['login_branches']);

						foreach($login_branches as $b){

							if($b == $id_branch){

								$result[] = array( 

										"id_employee" 			=> $emp['id_employee'],

										"emp_name" 				=> $emp['emp_name'],

										"disc_limit_type"		=> $emp['disc_limit_type'],

										"disc_limit" 			=> $emp['disc_limit'],

										"allowed_old_met_pur"	=> $emp['allowed_old_met_pur'],

										"allow_branch_transfer" => $emp['allow_branch_transfer']

									);

							}

						} 

					}

					

				}

				return $result;

			}

		}else{

			return $result;

		} 

	} 

	

function getAvailableCustomers($SearchTxt){



		$data = $this->db->query("SELECT c.id_customer, concat(c.firstname,'-',c.mobile) as label,c.id_village,v.village_name,is_vip,firstname,mobile,cus_type,

			(select count(sa.id_scheme_account) from scheme_account sa where sa.id_customer=c.id_customer) as accounts

			FROM customer c

			left join village v on v.id_village=c.id_village

			WHERE username like '%".$SearchTxt."%' OR mobile like '%".$SearchTxt."%' OR firstname like '%".$SearchTxt."%'"); 

		return $data->result_array();

	}

	

	function getUserMenus($id_profile){ 

        $menus = array ( 

        			array("id" => 1, "parent_id" => 0, "title" => "Home", "icon" => "home", "component" => "HomePage", "show"  => "1", "show_in_dash"  => "0"),

        			//array("id" => 2, "parent_id" => 0, "title" => "Estimation", "icon" => "calculator", "component" => "", "show"  => "1"),

        			array("id" => 3, "parent_id" => 0, "title" => "Create Estimation", "icon" => "calculator", "component" => "EstiPage", "page" => "create", "show"  => "1", "show_in_dash"  => "1"),

        			array("id" => 4, "parent_id" => 0, "title" => "Print Estimation", "icon" => "print", "component" => "EstiPage", "page" => "print", "show"  => "1", "show_in_dash"  => "1"),

        			array("id" => 5, "parent_id" => 0, "title" => "Create Customer", "icon" => "person-add", "component" => "CusRegisterPage", "show"  => "1", "show_in_dash"  => "1"),

        			array("id" => 6, "parent_id" => 0, "title" => "Category", "icon" => "basket", "component" => "CategoryPage", "show"  => "0", "show_in_dash"  => "0"), //Disabled for ams

        			array("id" => 7, "parent_id" => 0, "title" => "E-Catalog", "icon" => "flower", "component" => "Ecatalog", "show"  => "0", "show_in_dash"  => "0"), //Disabled for ams

        			array("id" => 8, "parent_id" => 0, "title" => "Wishlist", "icon" => "heart", "component" => "WishlistPage", "show"  => "0", "show_in_dash"  => "0"), //Disabled for ams

        			array("id" => 9, "parent_id" => 0, "title" => "Cart", "icon" => "cart", "component" => "CartPage", "show"  => 0, "show_in_dash"  => "0"), //Disabled for ams,

	                array("id" => 12, "parent_id" => 0, "title" => "Estimation List", "icon" => "list", "component" => "EstimationlistPage", "show"  => 1, "show_in_dash"  => "0"),
	                
	                array("id" => 10, "parent_id" => 0, "title" => "Stock Entry", "icon" => "paper", "component" => "StockCodeEntryPage", "show"  => 0, "show_in_dash"  => "0"),

        		);

        

	    if($id_profile <= 2){

        	$menus[] = array("id" => 11, "parent_id" => 0, "title" => "Edit Tag", "icon" => "create", "component" => "EdittagPage", "show"  => 0, "show_in_dash"  => "0");

        			

	    }

	    

        $menus[] = array("id" => 12, "parent_id" => 0, "title" => "Sign Out", "icon" => "log-out", "component" => "logout", "show"  => "1", "show_in_dash"  => "0");

        	//	echo "<pre>";print_r($menus);exit;

		return $menus;  

	}

	  	 

	  	 

	function getVillageData($id_village)

	{

		$sql="select * from village ".($id_village!='' ?"where id_village=".$id_village."" :'')."";

		if($id_village!='')

		{

			return $this->db->query($sql)->row_array();

		}

		else

		{

			return $this->db->query($sql)->result_array();

		}

	}

	

	function get_country()

	{

		$this->db->select('*');	

	    return $this->db->get('country')->result_array();	

	}

	

	function get_state($id_country)

	{

		$this->db->select('*');

		$this->db->where('id_country',$id_country);

		return $this->db->get('state')->result_array();

	}	

	

	function get_city($id_state)

	{

		$this->db->select('*');

		$this->db->where('id_state',$id_state);

		return $this->db->get('city')->result_array();

	}

	

	function getAllTaxGroupItems(){

		$taxGroupData = $this->db->query("SELECT tax_id, tax_code,

						tax_percentage,tgi_calculation, tgi_type,tgi_tgrpcode

						FROM ret_taxgroupitems as grpitems 

						LEFT JOIN ret_taxmaster as tax ON tax.tax_id = grpitems.tgi_taxcode");

		return $taxGroupData->result_array();

	}

	

	function getCategories($type,$last_id)

	{ 

		$sql  = $this->db->query("SELECT c.id_ret_category ,c.name,c.cat_code,m.metal,c.image 

		FROM ret_category as c 

		left join metal m on m.id_metal=c.id_metal".($type == 'active' ? ' where c.status = 1'.($last_id > 0? ' and c.id_ret_category >'.$last_id : '' ) : ($last_id > 0? ' where c.id_ret_category >'.$last_id : '' ) )." ".($last_id >= 0 ? 'ORDER BY c.id_ret_category ASC LIMIT 5' : '' ));  

		$result = $sql->result_array(); 

		foreach($result as $key => $val){

			$file = self::RETCAT_IMG_PATH.'/'.$val['image'];

			$result[$key]['image'] = ($val['image'] != null ? (file_exists($file)? $file : null ):null); 

		}		

	    return $result; 

	}

	

	function getProducts($type,$id_category,$last_id)

	{ 

		$sql  = "SELECT p.pro_id,p.cat_id,c.name as category,p.product_name, p.product_name as label,p.product_short_code,p.product_status,p.image,

		        if(p.metal_type = 1,'goldrate_22ct','silverrate_1gm') as metal_type,

		        mt.tgrp_id as tax_group_id,tax.tax_percentage,tax.tgi_calculation,p.calculation_based_on 

		        FROM ret_product_master p

			left join ret_category c on c.id_ret_category=p.cat_id 

			LEFT JOIN metal as mt ON mt.id_metal = p.metal_type

            LEFT JOIN (select i.tgi_taxcode,i.tgi_tgrpcode,

			m.tax_percentage as tax_percentage,

			i.tgi_calculation as tgi_calculation

			FROM ret_taxgroupitems i

			LEFT JOIN ret_taxmaster m on m.tax_id=i.tgi_taxcode) as tax on tax.tgi_tgrpcode=mt.tgrp_id

			";

		if($type == 'active'){

			$sql = $sql. " where product_status = 1";

			if($id_category != '' ){

				 $sql = $sql. ' and cat_id = '.$id_category;

			}

			if($last_id > 0){

				 $sql = $sql. ' and pro_id > '.$last_id;

			}

		}else{

				 $sql = $sql. " ".($id_category != '' ? ' Where cat_id = '.$id_category.' '.($last_id > 0? ' and pro_id >'.$last_id : '' ) : ($last_id > 0? ' where pro_id >'.$last_id : '' ) );

		}

		$sql = $sql." ".($last_id >= 0 ? ' ORDER BY pro_id ASC LIMIT 5' : '' ); 

		$result = $this->db->query($sql)->result_array(); 

		//echo $this->db->last_query();exit;

		foreach($result as $key => $val){

			$file = self::RETPRO_IMG_PATH.'/'.$val['image'];

			$result[$key]['image'] = ($val['image'] != null ? (file_exists($file)? $file : null ):null); 

		}		

	    return $result; 

	}

	

	function getSubDesigns($type,$id_product,$design_no, $last_id)

	{ 

		$sql  = "SELECT sdes.id_sub_design,sub_design_code,sub_design_name, sub_design_name as label,pro.product_name
        
        FROM ret_sub_design_mapping p 
        
        LEFT JOIN ret_product_master pro ON pro.pro_id=p.id_product
        
        LEFT JOIN ret_design_master des ON des.design_no=p.id_design 
		
		LEFT JOIN ret_sub_design_master as sdes ON sdes.id_sub_design = p.id_sub_design ";

		if($type == 'active'){

			$sql = $sql. " where sdes.status = 1";

			if($id_product != '' ){

				 $sql = $sql. ' and p.id_product = '.$id_product;

			}
			
			if($design_no != '' ){

				 $sql = $sql. ' and p.id_design = '.$design_no;

			}

			if($last_id > 0){

				 $sql = $sql. ' and sdes.id_sub_design > '.$last_id;

			}

		}else{

				 $sql = $sql. " ".($id_product != '' ? ' Where p.id_product = '.$id_product.' '.($last_id > 0? ' and sdes.id_sub_design >'.$last_id : '' ) : ($last_id > 0? ' where sdes.id_sub_design >'.$last_id : '' ) );

		}

		$sql = $sql." ".($last_id >= 0 ? ' ORDER BY sdes.id_sub_design ASC LIMIT 5' : '' ); 

		$result = $this->db->query($sql)->result_array(); 

		foreach($result as $key => $val){

			$file = self::RETDES_IMG_PATH.'/'.$val['image'];

			$result[$key]['image'] = ($val['image'] != null ? (file_exists($file)? $file : null ):null); 

		}		

	    return $result; 

	}

    function getDesigns($type,$id_product,$last_id)

	{ 

		$sql  = "SELECT design_no,design_code,design_name, design_name as label,i.image,pro.product_name
        
            FROM ret_product_mapping p 
            
            LEFT JOIN ret_product_master pro ON pro.pro_id=p.pro_id
            
            LEFT JOIN ret_design_master des ON des.design_no=p.id_design
            
            left join ret_nontag_item nt on nt.design=des.design_no 
    		
    		left join ret_design_images i on i.id_design = des.design_no and is_default = 1";

		if($type == 'active'){

			$sql = $sql. " where des.design_status = 1";

			if($id_product != '' ){

				 $sql = $sql. ' and p.pro_id = '.$id_product;

			}

			if($last_id > 0){

				 $sql = $sql. ' and des.design_no > '.$last_id;

			}

		}else{

				 $sql = $sql. " ".($id_product != '' ? ' Where p.pro_id = '.$id_product.' '.($last_id > 0? ' and des.design_no >'.$last_id : '' ) : ($last_id > 0? ' where des.design_no >'.$last_id : '' ) );

		}

		$sql = $sql." ".($last_id >= 0 ? ' ORDER BY des.design_no ASC LIMIT 5' : '' ); 

		$result = $this->db->query($sql)->result_array(); 

		foreach($result as $key => $val){

			$file = self::RETDES_IMG_PATH.'/'.$val['image'];

			$result[$key]['image'] = ($val['image'] != null ? (file_exists($file)? $file : null ):null); 

		}		

	    return $result; 

	}
	

	function getTaggingBySearch($SearchTxt,$searchField,$branch)

    {
        $data = $this->db->query("SELECT tag.tag_id,

        tag_code, tag_datetime, tag.tag_type, tag_lot_id,

        design_id, cost_center, tag.purity, tag.size, uom, piece, IFNULL(tag.less_wt,0) as less_wt, tag.net_wt, tag.gross_wt,

        tag.calculation_based_on, retail_max_wastage_percent,tag_mc_value as mc_value,tag_mc_type as mc_type,

        halmarking,tag.sell_rate as sales_value, tag.tag_status, product_name, product_short_code, tag.product_id,

        pur.purity as purname,lot_inw.lot_received_at,

        ifnull(tag_stn_detail.stn_amount,if(tag.id_orderdetails!='',ord.stn_amt,0)) as stone_price,IFNULL(tag_stn_detail.certification_cost,0) as certification_cost,

        pro.sales_mode,tag.item_rate,tax.tax_percentage,tax.tgi_calculation,

        if(pro.metal_type = 1,'Gold','Silver') as metal_name,

        r.rate_field as metal_type,

        r.market_rate_field as market_metal_type,
        

        tag.current_branch,

        mt.tgrp_id as tax_group_id,IFNULL(tag.id_orderdetails,'') as id_orderdetails,

        lot_inw.order_no, des.design_name, '0' as is_partial, IFNULL(rtc.charge_value,0) AS charge_value

        FROM ret_taging as tag

        Left join ret_lot_inwards_detail lot_det ON tag.id_lot_inward_detail = lot_det.id_lot_inward_detail

        LEFT JOIN ret_lot_inwards as lot_inw ON lot_inw.lot_no = lot_det.lot_no

        LEFT JOIN ret_product_master as pro ON pro.pro_id = tag.product_id 

        LEFT JOIN ret_design_master des on des.design_no=tag.design_id 

        LEFT JOIN ret_purity as pur ON pur.id_purity = tag.purity

        left join ret_category c on c.id_ret_category=pro.cat_id

        left join metal mt on mt.id_metal=c.id_metal  
        
        LEFT JOIN ret_metal_purity_rate r on r.id_metal=c.id_metal and r.id_purity=tag.purity 

        left join customerorderdetails ord on ord.id_orderdetails=tag.id_orderdetails

		LEFT JOIN (select i.tgi_taxcode,i.tgi_tgrpcode,

		GROUP_CONCAT(m.tax_percentage) as tax_percentage,

		GROUP_CONCAT(i.tgi_calculation) as tgi_calculation

		FROM ret_taxgroupitems i

		LEFT JOIN ret_taxmaster m on m.tax_id=i.tgi_taxcode) as tax on tax.tgi_tgrpcode=mt.tgrp_id

		LEFT JOIN (SELECT tag_id, SUM(IFNULL(charge_value,0)) AS charge_value FROM ret_taging_charges GROUP BY tag_id) AS rtc ON rtc.tag_id = tag.tag_id

        LEFT JOIN (SELECT tag_id,sum(amount) as stn_amount,sum(wt) as stn_wt,sum(certification_cost) as certification_cost

        FROM `ret_taging_stone` 

        GROUP by tag_id) as tag_stn_detail ON tag_stn_detail.tag_id = tag.tag_id

        WHERE tag.current_branch=".$branch." AND tag.".$searchField." = '".$SearchTxt."'"); 

        $rows = $data->row_array();

		if(count($rows) > 0) {
			$rows = array_merge($rows, array("charges" => $this->get_charges($rows['tag_id'])));
		}

		return $rows;
    }

	function get_charges($tag_id)
	{
		$sql = $this->db->query("SELECT rtc.tag_charge_id, rtc.tag_id, rtc.charge_id, rtc.charge_value, c.code_charge, c.tag_display FROM ret_taging_charges AS rtc LEFT JOIN ret_charges AS c ON rtc.charge_id = c.id_charge  WHERE tag_id=".$tag_id);  

	    return $sql->result_array();
	}

	function get_estimation_charges($est_id)
	{
		$sql = $this->db->query("SELECT rtc.id_est_charge, rtc.est_item_id, rtc.id_charge, rtc.amount, c.code_charge, c.tag_display FROM ret_estimation_other_charges AS rtc LEFT JOIN ret_charges AS c ON rtc.id_charge = c.id_charge  WHERE est_item_id=".$est_id);  

	    return $sql->result_array();
	}

    function getNonTagItems($id_branch) // Non tag stock products

    {

        $result = array( "products" => [], "designs" => []);

        $products = $this->db->query("SELECT 

	        id_nontag_item, nt.branch, nt.product, nt.design, nt.no_of_piece as piece, nt.gross_wt, nt.net_wt, nt.less_wt,	        

	        pro_id, concat(product_name,'-',product_short_code) as product_name,

	        concat(design_name,'-',design_code) as design_name,

	        d.wastage_type, other_materials, has_stone,sales_mode,

	        has_hook, has_screw, has_fixed_price,

	        has_size, less_stone_wt, 

	        if(pro.metal_type = 1,'Gold','Silver') as metal_name,

	        if(pro.metal_type = 1,'goldrate_22ct','silverrate_1gm') as metal_type,

	        if(pro.metal_type = 1,'mjdmagoldrate_22ct','mjdmasilverrate_1gm') as market_metal_type,

	        calculation_based_on,

	        mt.id_metal,mt.tgrp_id as tax_group_id

	        FROM ret_nontag_item as nt 

	            left join ret_product_master pro on nt.product=pro.pro_id

	            left join ret_design_master d on d.design_no=nt.design

	            left join ret_category c on c.id_ret_category=pro.cat_id

	            left join metal mt on mt.id_metal=c.id_metal

			WHERE nt.branch =".$id_branch);

        $data = $products->result_array(); 
        //echo $this->db->last_query();exit;
        foreach($data as $d){ 

			array_push($result['products'],array( "pro_id" => $d['pro_id'], "product_name" => $d['product_name']));

			array_push($result['designs'],array( "design_no" => $d['design'], "pro_id" => $d['pro_id'], "design_name" => $d['design_name']));

		}

		$result['nt_items'] = $data;

        return $result;

    }

    

    function getHomeStock($type,$SearchTxt,$searchField,$branch)

    { 

    	$data = $this->db->query("SELECT p.tag_id,

        tag.tag_code, tag_datetime,mt.tgrp_id as tax_group_id,

        p.blc_gross_wt as gross_wt,p.blc_less_wt as less_wt,p.blc_net_wt as net_wt,

        concat(product_name,'-',product_short_code) as product_name,pur.purity as purname,prod.pro_id,

        prod.calculation_based_on,tag.design_id,tag.purity,

        tag.size,tag.piece,tag.tag_mc_type as mc_type,tag.tag_mc_value as mc_value,tag.retail_max_wastage_percent,

        tag.item_rate,concat(des.design_name,'-',des.design_code) as design_name,

        if(prod.metal_type = 1,'Gold','Silver') as metal_name,

        if(prod.metal_type = 1,'goldrate_22ct','silverrate_1gm') as metal_type,

        if(prod.metal_type = 1,'mjdmagoldrate_22ct','mjdmasilverrate_1gm') as market_metal_type

		from ret_partlysold p 

		LEFT JOIN ret_taging tag on p.tag_id=tag.tag_id

		LEFT JOIN ret_product_master prod on prod.pro_id = p.product

		LEFT JOIN ret_purity as pur ON pur.id_purity = tag.purity

	    left join ret_category c on c.id_ret_category=prod.cat_id

	    LEFT JOIN ret_design_master as des ON des.design_no = tag.design_id

	    left join metal mt on mt.id_metal=c.id_metal 

		WHERE tag.current_branch=".$branch." ".($type == 'all' ? "" : " AND tag.".$searchField." LIKE '%".$SearchTxt."%'")."

		and p.status=1"); 

		if($type == 'all'){

			return $data->result_array();

		}else{

			return $data->row_array();

		}

        

    }

    

    function getEstiID($esti_no,$esti_date,$id_branch)

   	{

        $sql = $this->db->query("SELECT estimation_id from ret_estimation where id_branch=".$id_branch." and esti_no=".$esti_no." and date(estimation_datetime)='".$esti_date."'");  

        if( $sql->num_rows() > 0 ){

			return $sql->row()->estimation_id;

		}else{

			return 0;

		}

    }

    function getBranchDayClosingData($id_branch)

   	{

        $sql = $this->db->query("SELECT id_branch,is_day_closed,entry_date from ret_day_closing where id_branch=".$id_branch);  

        return $sql->row_array();

    }

    function getOldMetalType()

	{ 

		$sql  = "SELECT id_metal,id_metal_type,metal_type FROM ret_old_metal_type";

		return  $this->db->query($sql)->result_array(); 

	}

    

    function generateEstiNo($date,$id_branch)

	{

	  $sql = "SELECT max(esti_no) as lasEstiNo FROM ret_estimation WHERE date(estimation_datetime)='".$date."' ".($id_branch!='' ? " and id_branch=".$id_branch."" :'')." ORDER BY estimation_id DESC "; 

	  $lastno =  $this->db->query($sql)->row()->lasEstiNo; 

	  if($lastno != NULL && $lastno != '')

		{

		  	$number = (int) $lastno;

		  	$number++;

			$code_number=str_pad($number, 4, '0', STR_PAD_LEFT); 

    		return $code_number;

		}

		else

		{

			$code_number=str_pad('1', 4, '0', STR_PAD_LEFT);;

			return $code_number;

		}

	} 

    

    function getStones($type){

		$data = $this->db->query("SELECT stone_id, stone_name,

		stone_code, stone_type, st.uom_id, is_certificate_req, is_4c_req,

		uom_name, uom_short_code 

		FROM ret_stone as st 

		LEFT JOIN ret_uom as uom ON uom.uom_id = st.uom_id 

		".($type == 1 ? 'WHERE stone_status =1' : '')

		);

		return $data->result_array();

	}

	

	function prof_wise_loginotp_exp()

	{

		 $sql="Select prof_wise_loginotp_exp from chit_settings where id_chit_settings = 1"; 

		 return $this->db->query($sql)->row()->prof_wise_loginotp_exp;

	}

	

	function get_dash_esti_sale($from_date,$to_date, $id_emp, $id_branch)

	{

		$result = array('created' => 0 , 'created_amt' => 0 , 'created_wgt' => 0, 'sold' => 0 , 'sold_amt' => 0 , 'sold_wgt' => 0 );

		$sql_created = $this->db->query("SELECT 

											count(estimation_id) AS created, 

											IFNULL(sum(item_cost),0) AS created_amt, 

											IFNULL(sum(net_wt),0) AS created_wgt

										FROM ret_estimation_items AS estitm 

											LEFT JOIN ret_estimation as est ON estitm.esti_id = est.estimation_id 

											LEFT JOIN (select id_profile,id_employee from employee) as e ON e.id_employee = est.created_by 

										WHERE ( date(est.estimation_datetime) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') ".($id_emp > 0 && 'e.id_profile' > 3 ? ' AND created_by = '.$id_emp : '').' '.($id_branch > 0 ? ' AND id_branch = '.$id_branch : ''));

		if($sql_created->num_rows() > 0){

			$result['created'] = $sql_created->row()->created;

			$result['created_amt'] = $sql_created->row()->created_amt;

			$result['created_wgt'] = $sql_created->row()->created_wgt;

		}		

										

		$sql_sold = $this->db->query("SELECT 

										count(estimation_id) AS sold, 

										IFNULL(sum(item_cost),0) AS sold_amt, 

										IFNULL(sum(net_wt),0) AS sold_wgt

									FROM ret_estimation_items AS estitm 

										LEFT JOIN ret_estimation as est ON estitm.esti_id = est.estimation_id 

										LEFT JOIN (select id_profile,id_employee from employee) as e ON e.id_employee = est.created_by 

									WHERE estitm.purchase_status = 1 AND (date(est.estimation_datetime) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') ".($id_emp > 0 && 'e.id_profile' > 3 ? ' AND created_by = '.$id_emp : '').' '.($id_branch > 0 ? ' AND id_branch = '.$id_branch : ''));

		//echo $this->db->last_query();exit;

		if($sql_sold->num_rows() > 0){

			$result['sold'] = $sql_sold->row()->created;

			$result['sold_amt'] = $sql_sold->row()->sold_amt;

			$result['sold_wgt'] = $sql_sold->row()->sold_wgt;

		}								

		return $result;										

	}

	

	function get_dash_esti_old_metal($from_date, $to_date, $id_emp, $id_branch)

	{

		$result = array('created' => 0 , 'created_amt' => 0 , 'created_wgt' => 0, 'purchased' => 0 , 'purch_amt' => 0 , 'purch_wgt' => 0 );

		$sql_created = $this->db->query("SELECT 

											count(estimation_id) AS created, 

											IFNULL(sum(amount),0) AS created_amt, 

											IFNULL(sum(net_wt),0) AS created_wgt

										FROM ret_estimation_old_metal_sale_details AS esti_om

											LEFT JOIN ret_estimation as est  ON esti_om.est_id = est.estimation_id 

											LEFT JOIN (select id_profile,id_employee from employee) as e ON e.id_employee = est.created_by 

										WHERE ( date(est.estimation_datetime) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') ".($id_emp > 0 && 'e.id_profile' > 3 ? ' AND created_by = '.$id_emp : '').' '.($id_branch > 0 ? ' AND id_branch = '.$id_branch : ''));

	//	echo $this->db->last_query();exit;

		if($sql_created->num_rows() > 0){

			$result['created'] = $sql_created->row()->created;

			$result['created_amt'] = $sql_created->row()->created_amt;

			$result['created_wgt'] = $sql_created->row()->created_wgt;

		}		

										

		$sql_purch = $this->db->query("SELECT 

										count(estimation_id) AS purchased, 

										IFNULL(sum(amount),0) AS purch_amt, 

										IFNULL(sum(net_wt),0) AS purch_wgt

									FROM ret_estimation_old_metal_sale_details AS esti_om 

										LEFT JOIN ret_estimation as est ON esti_om.est_id = est.estimation_id 

										LEFT JOIN (select id_profile,id_employee from employee) as e ON e.id_employee = est.created_by 

									WHERE esti_om.purchase_status = 1 AND (date(est.estimation_datetime) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') ".($id_emp > 0 && 'e.id_profile' > 3 ? ' AND created_by = '.$id_emp : '').' '.($id_branch > 0 ? ' AND id_branch = '.$id_branch : ''));

		if($sql_purch->num_rows() > 0){

			$result['purchased'] = $sql_purch->row()->purchased;

			$result['purch_amt'] = $sql_purch->row()->purch_amt;

			$result['purch_wgt'] = $sql_purch->row()->purch_wgt;

		}								

		return $result;	

	}

	function isCusExist($cusmobile)

	{

	    $sql = $this->db->query("SELECT id_customer FROM customer WHERE mobile='".$cusmobile."'");

	    //echo "SELECT id_customer FROM customer WHERE mobile='".$cusmobile."'"

		if($sql->num_rows() == 0){

			return array("status" => FALSE, "message" => "Customer already exist");

		}else{

			return array("status" => TRUE, "message" => "Given mobile number already exist");

		}

	}

	function getAllPurities($id_product){

		if($id_product != ''){

			 $sql = $this->db->query("SELECT p.id_purity, purity, purity as label, mpur.rate_field, mpur.market_rate_field

						FROM ret_product_master prod   

						LEFT JOIN ret_metal_cat_purity cat_pur ON cat_pur.id_category = prod.cat_id

						LEFT JOIN ret_purity p ON p.id_purity = cat_pur.id_purity 
						
						LEFT JOIN ret_category as cat ON cat.id_ret_category = cat_pur.id_category 
                        
                        LEFT JOIN ret_metal_purity_rate as mpur ON mpur.id_metal = cat.id_metal AND mpur.id_purity = cat_pur.id_purity

						where prod.pro_id=".$id_product." group by p.id_purity");

		}else{

			 $sql = $this->db->query("SELECT pur.id_purity, pur.purity, purity as label , mpur.rate_field, mpur.market_rate_field

						FROM ret_purity as pur
						
						LEFT JOIN ret_metal_purity_rate as mpur ON mpur.id_purity = pur.id_purity

						WHERE status=1");

		}
		

	   

		return $sql->result_array();

	}

    function getCurrentDayEstimationsByBranch($branchId="", $empId){
	    $dcData=$this->getBranchDayClosingData($branchId);
        if($branchId != "" && $empId != ""){
            $estquery = $this->db->query("SELECT estimation_id, esti_no, esti_for, date_format(estimation_datetime, '%d-%m-%Y %H:%i:%s') as estimatedon, 
                                    cus_id, has_converted_order, 
                                    total_cost, est.id_branch, cus.firstname, cus.mobile  
                                    FROM ret_estimation as est 
                                    LEFT JOIN customer as cus ON cus.id_customer = est.cus_id 
                                    WHERE est.id_branch = '".$branchId."' AND  est.created_by = '".$empId."' AND  date(estimation_datetime) = '".$dcData['entry_date']."' ORDER BY estimation_id DESC");
        }else{
            $branch_filter  = $branchId != '' ? "est.id_branch = '".$branchId."' AND " : '';
            $emp_filter     =  $empId !='' ? "est.created_by = '".$empId."' AND " : '';
             $estquery = $this->db->query("SELECT estimation_id, esti_no, esti_for, date_format(estimation_datetime, '%d-%m-%Y %H:%i:%s') as estimatedon, 
                                    cus_id, has_converted_order, 
                                    total_cost, est.id_branch, cus.firstname, cus.mobile, 
                                    (SELECT sum(gross_wt) as wt FROM ret_estimation_items WHERE esti_id = estimation_id) as totwt 
                                    FROM ret_estimation as est 
                                    LEFT JOIN customer as cus ON cus.id_customer = est.cus_id 
                                    WHERE ".$branch_filter.$emp_filter."  date(estimation_datetime) = '".$dcData['entry_date']."' ORDER BY estimation_id DESC");
        }
        //echo $this->db->last_query();exit;
        return $estquery->result_array();
    }
    
    function get_retCharges()
    {
        $cquery = $this->db->query("SELECT id_charge, code_charge, name_charge, value_charge FROM ret_charges");
        return $cquery->result_array();
    }
    
    function testimportTagData(){
        $ab = 0;
        $lot_grs_wt = 0;
        $totgrswt = 0;
        $tottgrswt = 0;
	    $lotquery = $this->db->query("SELECT `K ID` as karid, catid, ct.Purity, pur.id_purity, sum(ifnull(`Gross Wt`, 0)) as grs_wt   
	                               FROM skn_gold_ornaments_22_ct ct 
	                               LEFT JOIN ret_purity as pur ON pur.purity = ct.Purity  
	                               GROUP BY catid, Purity, `K ID`");
	    foreach($lotquery->result() as $prelot){
	        $insert_lot_data = array("lot_received_at"  => 1,
	                                "gold_smith"        => $prelot->karid,
	                                "lot_date"          => date('Y-m-d H:i:s'),
	                                "id_category"       => $prelot->catid,
	                                "id_purity"         => $prelot->id_purity,
	                                "created_branch"    => 1,
	                                "created_on"        => date('Y-m-d H:i:s'),
	                                "lot_from"          => 3
	                                );
	                                
	       $lot_grs_wt += $prelot->grs_wt;                         
	       $lot_id = 201;
	       if(!empty($lot_id)){
	           $lot_pro_query = $this->db->query("SELECT `K ID` as karid, `Product Name` as productname, sum(Pieces) as pcs, sum(ifnull(`Gross Wt`, 0)) as gross_wt, 
	                            sum(`Net Wt`) as net_wt, pr.pro_id , ct.catid, ct.Purity 
	                            FROM skn_gold_ornaments_22_ct as ct 
	                            LEFT JOIN ret_product_master as pr ON pr.product_name = ct.`Product Name` 
	                            WHERE `K ID` = '".$insert_lot_data['gold_smith']."' AND ct.Purity = '".$prelot->Purity."' AND ct.catid = '".$prelot->catid."'
	                            GROUP BY `Product Name`");
	           //echo $this->db->last_query();
	           //echo "<br />";
	           foreach($lot_pro_query->result() as $prelotdet){
	               $insert_lot_det_data = array(
	                                "lot_no"            => $lot_id,
	                                "lot_product"       => $prelotdet->pro_id,
	                                "design_for"        => 2,
	                                "no_of_piece"       => $prelotdet->pcs,
	                                "gross_wt"          => $prelotdet->gross_wt,
	                                "gross_wt_uom"      => 1,
	                                "net_wt"            => $prelotdet->net_wt,
	                                "net_wt_uom"        => 1,
	                                "less_wt_uom"       => 1,
	                                "current_branch"    => 1,
	                                "tag_status"        => 1
	                                );
	                                $totgrswt += $prelotdet->gross_wt;
	                $lot_det_id = 202;
	                if(!empty($lot_det_id)){
	                    $tag_query = $this->db->query("SELECT `K ID`, `Product Name` , Pieces as pcs, ifnull(`Gross Wt`,0) as gross_wt, 
	                                `Net Wt` as net_wt, pr.pro_id, cat.cat_code as cat_code, 
	                                pr.product_short_code as product_short_code, des.design_no as design_no, 
	                                sdes.id_sub_design as id_sub_design, `Less Wt` as lesswt, `Wastage Per` as wastage, 
	                                if(`MC Type` = 'Per Gram', 2, 1) as mctype, `MC Value` as mcval, 
	                                br.id_branch as branchid, `MFR Code` as manufacture_code, 
	                                `Style Code` as style_code, `Tag Number` as oldtagno, 
	                                `Certification No` as cert_no, `Purchase Cost` as tag_purchase_cost,
	                                `Tag Date` as tagdate, `HUID 1` as hu_id, `HUID 2` as hu_id2, 
	                                pr.calculation_based_on as calculation_based_on, `Sales Value` as sell_rate  
	                                FROM skn_gold_ornaments_22_ct as ct 
	                                LEFT JOIN ret_category as cat ON cat.id_ret_category = '".$prelotdet->catid."' 
	                                LEFT JOIN ret_product_master as pr ON pr.product_name = ct.`Product Name` AND pr.cat_id = '".$prelotdet->catid."'  
	                                
	                                LEFT JOIN ret_design_master as des ON des.design_name = ct.`Design Name` 
	                                LEFT JOIN ret_sub_design_master as sdes ON sdes.sub_design_name = ct.`Sub Design Name` 
	                                LEFT JOIN branch as br ON br.name = ct.`Branch` 
	                                WHERE ct.`K ID` = '".$prelotdet->karid."' AND ct.catid = '".$prelotdet->catid."' 
	                                AND ct.Purity = '".$prelotdet->Purity."' 
	                                AND `Product Name` = '". $prelotdet->productname."'");
	                   foreach($tag_query->result() as $tagdet){
	                       ++$ab;
	                       $tottgrswt += $tagdet->gross_wt;
	                       
	                       if(empty($tagdet->pro_id)){
	                           echo $this->db->last_query();
	                           echo "<br />";
	                       }
	                       
	                   }
	                }
	           }
	           
	       }
	    }
	    echo json_encode(array("status" => TRUE, "message" => "Uploaded successfully", "lot_grs_wt" => $lot_grs_wt,  "lotdetgrswt" => $totgrswt, "ab" => $ab, "taggrswt" => $tottgrswt));
	    exit;
	    //return array("status" => TRUE, "message" => "Uploaded successfully");
	   
	}
	
	
	function importTagData(){
	    $lotquery = $this->db->query("SELECT `K ID` as karid, catid, ct.Purity, pur.id_purity, sum(ifnull(`Gross Wt`, 0)) as grs_wt, ct.Branch    
	                               FROM ret_import_tag_details ct 
	                               LEFT JOIN ret_purity as pur ON pur.purity = ct.Purity 
	                               WHERE updatestatus = 0 
	                               GROUP BY catid, Purity, `K ID`, `Branch`");
	    foreach($lotquery->result() as $prelot){
	        $insert_lot_data = array("lot_received_at"  => 1,
	                                "gold_smith"        => $prelot->karid,
	                                "lot_date"          => date('Y-m-d H:i:s'),
	                                "id_category"       => $prelot->catid,
	                                "id_purity"         => $prelot->id_purity,
	                                "created_branch"    => 1,
	                                "created_on"        => date('Y-m-d H:i:s'),
	                                "lot_from"          => 3
	                                );
	       $lot_id = $this->insertData($insert_lot_data, "ret_lot_inwards");
	       if(!empty($lot_id)){
	           $lot_pro_query = $this->db->query("SELECT `K ID` as karid, `Product Name` as productname, sum(Pieces) as pcs, sum(ifnull(`Gross Wt`, 0)) as gross_wt, 
	                            sum(`Net Wt`) as net_wt, pr.pro_id , ct.catid, ct.Purity, ct.Branch  
	                            FROM ret_import_tag_details as ct 
	                            LEFT JOIN ret_product_master as pr ON pr.product_name = ct.`Product Name` 
	                            WHERE updatestatus = 0 AND ct.`Branch` = '".$prelot->Branch."' AND `K ID` = '".$insert_lot_data['gold_smith']."' AND ct.Purity = '".$prelot->Purity."' AND ct.catid = '".$prelot->catid."'
	                            GROUP BY `Product Name`");
	           foreach($lot_pro_query->result() as $prelotdet){
	               $insert_lot_det_data = array(
	                                "lot_no"            => $lot_id,
	                                "lot_product"       => $prelotdet->pro_id,
	                                "design_for"        => 2,
	                                "no_of_piece"       => $prelotdet->pcs,
	                                "gross_wt"          => $prelotdet->gross_wt,
	                                "gross_wt_uom"      => 1,
	                                "net_wt"            => $prelotdet->net_wt,
	                                "net_wt_uom"        => 1,
	                                "less_wt_uom"       => 1,
	                                "current_branch"    => 1,
	                                "tag_status"        => 1
	                                );
	                $lot_det_id = $this->insertData($insert_lot_det_data, "ret_lot_inwards_detail");
	                if(!empty($lot_det_id)){
	                    $tag_query = $this->db->query("SELECT ct.id as imptagid, `K ID`, `Product Name` , Pieces as pcs, ifnull(`Gross Wt`,0) as gross_wt, 
	                                `Net Wt` as net_wt, pr.pro_id, cat.cat_code as cat_code, 
	                                pr.product_short_code as product_short_code, des.design_no as design_no, 
	                                sdes.id_sub_design as id_sub_design, `Less Wt` as lesswt, `Wastage Per` as wastage, 
	                                if(`MC Type` = 'Per Gram', 2, 1) as mctype, `MC Value` as mcval, 
	                                br.id_branch as branchid, `MFR Code` as manufacture_code, 
	                                `Style Code` as style_code, `Tag Number` as oldtagno, 
	                                `Certification No` as cert_no, `Purchase Cost` as tag_purchase_cost,
	                                `Tag Date` as tagdate, `HUID 1` as hu_id, `HUID 2` as hu_id2, 
	                                pr.calculation_based_on as calculation_based_on, `Sales Value` as sell_rate, 
									calltype 
	                                FROM ret_import_tag_details as ct 
	                                LEFT JOIN ret_category as cat ON cat.id_ret_category = '".$prelotdet->catid."' 
	                                LEFT JOIN ret_product_master as pr ON pr.product_name = ct.`Product Name` AND pr.cat_id = '".$prelotdet->catid."'  
	                                -- LEFT JOIN ret_product_master as pr ON pr.pro_id = '".$prelotdet->pro_id."'   
	                                LEFT JOIN ret_design_master as des ON des.design_name = ct.`Design Name` 
	                                LEFT JOIN ret_sub_design_master as sdes ON sdes.sub_design_name = ct.`Sub Design Name` 
	                                LEFT JOIN branch as br ON br.name = ct.`Branch` 
	                                WHERE updatestatus = 0 AND ct.`Branch` = '".$prelotdet->Branch."' AND ct.`K ID` = '".$prelotdet->karid."' AND ct.catid = '".$prelotdet->catid."' 
	                                AND ct.Purity = '".$prelotdet->Purity."' 
	                                AND `Product Name` = '". $prelotdet->productname."'");
	                   foreach($tag_query->result() as $tagdet){
	                        
	                        $catcode    = $tagdet->cat_code;

							$curr_year  = date("y");

	 					    $tagCode    = $this->getlastTagCode($tagdet->pro_id); 

	 					    $tag_no     = $this->generateTagCode($tagCode);

							$tag_code   = $catcode.$curr_year.$tagdet->product_short_code.'-'.$tag_no;
							
							/*if($tag_code == 'SS22EAR-00001'){
							    echo $this->db->last_query();
							    echo "<br />";
							    echo "SELECT ct.id as imptagid, `K ID`, `Product Name` , Pieces as pcs, ifnull(`Gross Wt`,0) as gross_wt, 
	                                `Net Wt` as net_wt, pr.pro_id, cat.cat_code as cat_code, 
	                                pr.product_short_code as product_short_code, des.design_no as design_no, 
	                                sdes.id_sub_design as id_sub_design, `Less Wt` as lesswt, `Wastage Per` as wastage, 
	                                if(`MC Type` = 'Per Gram', 2, 1) as mctype, `MC Value` as mcval, 
	                                br.id_branch as branchid, `MFR Code` as manufacture_code, 
	                                `Style Code` as style_code, `Tag Number` as oldtagno, 
	                                `Certification No` as cert_no, `Purchase Cost` as tag_purchase_cost,
	                                `Tag Date` as tagdate, `HUID 1` as hu_id, `HUID 2` as hu_id2, 
	                                pr.calculation_based_on as calculation_based_on, `Sales Value` as sell_rate  
	                                FROM ret_import_tag_details as ct 
	                                LEFT JOIN ret_category as cat ON cat.id_ret_category = '".$prelotdet->catid."' 
	                                LEFT JOIN ret_product_master as pr ON pr.product_name = ct.`Product Name` AND pr.cat_id = '".$prelotdet->catid."'  
	                                LEFT JOIN ret_design_master as des ON des.design_name = ct.`Design Name` 
	                                LEFT JOIN ret_sub_design_master as sdes ON sdes.sub_design_name = ct.`Sub Design Name` 
	                                LEFT JOIN branch as br ON br.name = ct.`Branch` 
	                                WHERE updatestatus = 0  AND ct.`K ID` = '".$prelotdet->karid."' AND ct.catid = '".$prelotdet->catid."' 
	                                AND ct.Purity = '".$prelotdet->Purity."' 
	                                AND `Product Name` = '". $prelotdet->productname."'";
				
							}*/
							
							$tag_datetime = date("Y-m-d H:i:s");
							
							$arrayTag = array(
								'tag_code' 			=> $tag_code,
								'current_branch' 	=> $tagdet->branchid, 
								'id_branch' 		=> 1, 
								'cost_center' 		=> $tagdet->branchid, 
								'tag_lot_id' 		=> $lot_id,  
								'product_id' 		=> $tagdet->pro_id, 
								'design_id' 		=> $tagdet->design_no, 
								'id_sub_design' 	=> $tagdet->id_sub_design, 
								'design_for' 		=> 0,//$addData['design_for'][$key], 
								'purity' 			=> $prelot->id_purity, 
								'id_orderdetails'   => NULL,
								'id_lot_inward_detail' => $lot_det_id, 
								'size' 				=> NULL, 
								'piece' 			=> $tagdet->pcs, 
								'gross_wt' 			=> $tagdet->gross_wt, 
								'less_wt' 			=> !empty($tagdet->lesswt) ? $tagdet->lesswt : NULL, 
								'net_wt' 			=> $tagdet->net_wt, 
								'calculation_based_on' => $tagdet->calculation_based_on,
								'retail_max_wastage_percent' => !empty($tagdet->wastage) ? $tagdet->wastage : 0,
								'tag_mc_type' 		=> $tagdet->mctype, 
								'tag_mc_value' 	    => !empty($tagdet->mcval) ? $tagdet->mcval : 0, 
								'sell_rate' 		=> $tagdet->sell_rate, 
								'item_rate' 		=> $tagdet->sell_rate,
								'sales_value' 		=> $tagdet->sell_rate,
								'hu_id'             => $tagdet->hu_id, 
								'hu_id2'            => $tagdet->hu_id2,  
								'image'				=> NULL,
								'tag_datetime'	  	=> $tag_datetime,
								'old_tag_id'        => $tagdet->oldtagno,
								'old_tag_date'      => date('Y-m-d', strtotime($tagdet->tagdate)),
								'created_time'	  	=> date("Y-m-d H:i:s"),
								'created_by'      	=> 1,
								'ref_no'      		=> NULL,
								"cert_no"			=> $tagdet->cert_no, 
								"cert_img"			=> NULL, 
								'manufacture_code' 	=> $tagdet->manufacture_code,
								'style_code' 		=> $tagdet->style_code,
								'remarks' 			=> $tagdet->oldtagno,
								'tag_purchase_cost'	=> $tagdet->tag_purchase_cost,
								'tag_type' 			=> 0,
								'is_tag_imported'   => 1,
								'uom'               => 1,
								'stone_calculation_based_on' => $tagdet->calltype == 'WEIGHT' ? 1 : 2
							);
							$tag_id = $this->insertData($arrayTag, 'ret_taging');
							
							$log_data = array(
	 					    	'tag_id'	  =>$tag_id,
	 					    	'date'		  =>$tag_datetime,
	 					    	'status'	  =>0,
	 					    	'from_branch' =>NULL,
	 					    	'to_branch'	  => $tagdet->branchid,
	 					    	'created_on'  =>date("Y-m-d H:i:s"),
								'created_by'  =>1,
								'issuspensestock' => 0
	 					    );
	 					    $this->insertData($log_data, 'ret_taging_status_log');
	 					    
	 					    
	 					    $this->updateData(array("updatestatus" => 1), 'id', $tagdet->imptagid, 'ret_import_tag_details');
	                       
	                   }
	                }
	           }
	           
	       }
	    }
	    
	    return array("status" => TRUE, "message" => "Uploaded successfully");
	   
	}
	
	function importCustomerLedgerBalance(){
	    $customer_query = $this->db->query("SELECT `Customer`, `cusId`, `Mobile`, `ClosingAmt`, `OldRef`, `Remarks` GROUP BY `Mobile`");
	    foreach($customer_query->result() as $customer){
	        
	    }
	}
    function importTagCharges()
	{
	    $charge_query = $this->db->query("SELECT chid, tag_id, tag_code, old_tag_id, ch.id_charge as charge_id, chr.`Charge Value` as charge_value 
	                                    FROM `ret_import_charges` as chr 
	                                    LEFT JOIN ret_taging as tag ON tag.old_tag_id = chr.`Tag Number` 
	                                    LEFT JOIN ret_charges as ch ON ch.name_charge = chr.`Charge Name`
	                                    WHERE chstatus = 0");
	   foreach($charge_query->result() as $charge){
	       $charge_data=array(
							'tag_id'                =>$charge->tag_id,
							'charge_id'             =>$charge->charge_id,
							'charge_value'          =>$charge->charge_value,
							);
			$this->insertData($charge_data,'ret_taging_charges');
			$this->updateData(array("chstatus" => 1), 'chid', $charge->chid, 'ret_import_charges');
	   }
	   return array("status" => TRUE, "message" => "Uploaded successfully");
	}
	
	function importTagStones()
	{
	    $stone_query = $this->db->query("SELECT stiid, tag_id, tag_code, old_tag_id, st.stone_id, ist.`Pieces` as pieces, 
	                                    `Weight` as wt, uom.uom_id, ist.`Amount` as amount, 
	                                    if(LOWER(ist.`Less Wt`) = 'yes', 1 , 0) as is_apply_in_lwt, 
	                                    if(LOWER(ist.`CAL TYPE`) = 'wt', 1, 2)  as stone_cal_type 
	                                    FROM `ret_import_stone_details` as ist  
	                                    LEFT JOIN ret_taging as tag ON tag.old_tag_id = ist.`Tag Number` 
	                                    LEFT JOIN ret_stone as st ON st.stone_id = ist.`Stone ID` 
	                                    LEFT JOIN ret_uom as uom ON LOWER(uom.uom_name) = LOWER(ist.`Unit`) 
	                                    WHERE ststatus = 0");
	    foreach($stone_query->result() as $stone){
	        $stone_data = array(
							'tag_id'                =>$stone->tag_id,
							'pieces'                =>$stone->pieces,
							'wt'                    =>$stone->wt,
							'stone_id'              =>$stone->stone_id,
							'uom_id'                =>$stone->uom_id,
							'amount'                =>$stone->amount,
							'is_apply_in_lwt'       =>$stone->is_apply_in_lwt,
							);
			$stoneInsert = $this->insertData($stone_data,'ret_taging_stone');
			$this->updateData(array("ststatus" => 1), 'stiid', $stone->stiid, 'ret_import_stone_details');
	   }
	   return array("status" => TRUE, "message" => "Uploaded successfully");
	}
	function getlastTagCode($product_id)

    {

        $sql=$this->db->query("SELECT t.tag_id,t.tag_code FROM ret_taging t where t.tag_code is not null AND product_id = ".$product_id." AND tag_year = (RIGHT(YEAR(CURDATE()),2)) ORDER by tag_id DESC LIMIT 1");

		return $sql->row()->tag_code;

	} 
	
	
	
	//Import customer balance
	
	function get_retWallet_details($id_customer)
	{
		$data=$this->db->query("SELECT id_ret_wallet,id_customer FROM ret_wallet w where w.id_customer=".$id_customer."");
		if($data->num_rows()>0)
		{
			return array('status'=>TRUE,'id_ret_wallet'=>$data->row('id_ret_wallet'));
		}else{
			return array('status'=>FALSE,'id_ret_wallet'=>'');
		}
	}
	
		//Update Wallet Account
	function updateWalletData($data,$arith)
	{ 
		$sql = "UPDATE ret_wallet SET amount=(amount".$arith." ".$data['amount']."),weight=(weight".$arith." ".$data['weight']."),updated_time='".date("Y-m-d H:i:s")."' WHERE id_customer=".$data['id_customer'];  
		$status = $this->db->query($sql);
		return $status;
	}
	
	function bill_no_generate($id_branch)
	{
		$lastno = $this->get_max_bill_no($id_branch);
		if($lastno!=NULL)
		{
			$number = (int) $lastno;
			$number++;
			$code_number = str_pad($number, 5, '0', STR_PAD_LEFT);			
			return $code_number;
		}
		else
		{
			$code_number = str_pad('1', 5, '0', STR_PAD_LEFT);
			return $code_number;
		}
	}
	function get_max_bill_no($id_branch)
    {
        $fin_year = $this->get_FinancialYear();
		$sql = "SELECT max(bill_no) as lastBill_no FROM ret_issue_receipt where fin_year_code=".$fin_year['fin_year_code']." ".($id_branch!='' && $id_branch>0 ? " and id_branch=".$id_branch."" :'')." ORDER BY id_issue_receipt DESC";
		return $this->db->query($sql)->row()->lastBill_no;	
	}
	
	function get_FinancialYear()
	{
		$sql=$this->db->query("SELECT fin_year_code From ret_financial_year where fin_status=1");
		return $sql->row_array();
	}
	
	//Update Wallet Account
	
	public function __encrypt($str)
	{
		return base64_encode($str);
	}
	
	function getCustomerDetails($mobile,$firstname)
	{
	    $customer_id='';
	    $sql=$this->db->query("SELECT * FROM customer WHERE mobile='".$mobile."'");
	    if($sql->num_rows() == 1)
	    {
	        $customer_id=$sql->row()->id_customer;
	    }else
	    {
	        $insertData=array(
	                         'mobile'=>$mobile,
	                         'username'=>$mobile,
	                         'firstname'=>$firstname,
	                         'passwd'=>$this->__encrypt($data['mobile']),
	                         );
	        $customer_id=$this->insertData($insertData,'customer');   
	    }
	    
	    return $customer_id;
	}
	
	function importCustomerBalance(){
	   $responseData=array();
	   $total_import_Data=0;
	   $custrans = $this->db->query("SELECT rowid,Customer,Mobile, ClosingAmt, OldRef, Remarks, branchid, cusId FROM customeroutstanding t1 
	                                WHERE LENGTH(t1.Mobile) = 10  AND cusupdatestatus = 0");
	   //print_r($this->db->last_query());exit;
	   if($custrans->num_rows() > 0){
	       $this->load->model("ret_estimation_model");
	       $this->load->model('admin_settings_model');
		   $model = "ret_estimation_model";
	       foreach($custrans->result() as $row)
	       {
	       
	       $cusId=$this->getCustomerDetails($row->Mobile,$row->Customer);
	       
	   if($cusId!='')
    	{
    			    
	       //if (str_contains($row->Remarks,'ADVANCE')) { //Update this into Payment Receipt
	       
	        $bill_no    = $this->bill_no_generate($row->branchid);
    	    $dCData     = $this->admin_settings_model->getBranchDayClosingData($row->branchid);
    		$bill_date  = ($dCData['entry_date'] == date("Y-m-d") ? date("Y-m-d H:i:s") : $dCData['entry_date']);
    		$fin_year   = $this->get_FinancialYear();
	       
	        if (strpos($row->Remarks, 'ADVANCE') !== false) {//Update this into Payment Receipt
	              
    			
				
	            $insData=array(
					'type'			=>2,
					'amount'		=>$row->ClosingAmt,
					'weight'		=>0,
					'bill_no'		=>$bill_no,
					'receipt_type'	=>4,
					'id_branch'		=> $row->branchid,
					'id_customer'	=>$cusId,
					//'refno'	        =>$row->OldRef,
					'receipt_as'	=>1,
					'store_receipt_as'=>1,
					'narration'	 	=>'Existing Balance Amount',
					'created_on' 	=> date("Y-m-d H:i:s"),
					'bill_date' 	=> $bill_date,
					'fin_year_code' => $fin_year['fin_year_code'],
				);
				//echo "<pre>"; print_r($insData);exit;
				$this->db->trans_begin();
				$insId = $this->insertData($insData,'ret_issue_receipt');
				//print_r($this->db->last_query());exit;
				if($insId)
	 			{
	 			    $total_import_Data=($total_import_Data+1);
	 			    
	 			    $updStatus=$this->updateData(array('cusupdatestatus'=>1),"rowid","$row->rowid","customeroutstanding");
	 			    
                    $wallet=$this->get_retWallet_details($cusId);
                    if($wallet['status'])
                    {
                        $this->updateWalletData(array('amount'=>$row->ClosingAmt,'weight'=>0,'id_customer'=>$cusId),'+');
                        $insWallet=array(
                        'id_ret_wallet'		=>$wallet['id_ret_wallet'],
                        'id_issue_receipt'	=>$insId,
                        'amount'			=>$row->ClosingAmt,
                        'weight'			=>0,
                        'transaction_type'	=>0,
                        'created_on' 		=> date("Y-m-d H:i:s"),
                        'remarks'	 		=>'Existing Balance Advace Amount'
                        );
                        $this->insertData($insWallet,'ret_wallet_transcation');
                    }
                    else
                    {
                        $wallet_acc=array(
                            'id_customer'   =>$cusId,
                            'amount'        =>$row->ClosingAmt,
                            'weight'        =>0,
                            'created_time'  =>date("Y-m-d H:i:s")
                        );
                        $insWalletAcc= $this->insertData($wallet_acc,'ret_wallet');
                        if($insWalletAcc)
                        {
                            $insWallet=array(
                            'id_ret_wallet'	    =>$insWalletAcc,
                            'id_issue_receipt'	=>$insId,
                            'amount'			=>$row->ClosingAmt,
                            'weight'			=>0,
                            'transaction_type'	=>0,
                            'created_on' 		=> date("Y-m-d H:i:s"),
                            'remarks'	 		=>'Existing Balance Advace Amount'
                            );
                            $this->insertData($insWallet,'ret_wallet_transcation');
                        }
                    }
	 			}
	        }
	        else if (strpos($row->Remarks, 'CREDIT SALES') !== false) { //Update this into Payment Issue
	            $insData=array(
				    'bill_date' 	=> $bill_date,
					'fin_year_code' => $fin_year['fin_year_code'],
				    'bill_no'	    => $bill_no,
					'type'          => 1,
					'id_branch'     => $row->branchid,
					'amount'        => $row->ClosingAmt,
					'issue_to'      => 2,
					'issue_type'    => 4,
					'id_customer'   => $cusId,
					//'refno'	        =>$row->OldRef,
					'created_on'    => date("Y-m-d H:i:s"),
					);
					
				$this->db->trans_begin();
				$insId = $this->insertData($insData,'ret_issue_receipt');
				
				if($insId)
	 			{
	 			    $total_import_Data=($total_import_Data+1);
	 			    
	 			    $updStatus=$this->updateData(array('cusupdatestatus'=>1),"rowid","$row->rowid","customeroutstanding");
	 			    
                    $wallet=$this->get_retWallet_details($cusId);
                    if($wallet['status'])
                    {
                        $this->updateWalletData(array('amount'=>$row->ClosingAmt,'weight'=>0,'id_customer'=>$cusId),'-');
                        $insWallet=array(
                        'id_ret_wallet'		=>$wallet['id_ret_wallet'],
                        'id_issue_receipt'	=>$insId,
                        'amount'			=>$row->ClosingAmt,
                        'weight'			=>0,
                        'transaction_type'	=>0,
                        'created_on' 		=> date("Y-m-d H:i:s"),
                        'remarks'	 		=>'Existing Balance Credit Sales Amount'
                        );
                        $this->insertData($insWallet,'ret_wallet_transcation');
                    }
                    else
                    {
                        $wallet_acc=array(
                            'id_customer'   =>$cusId,
                            'amount'        =>$row->ClosingAmt,
                            'weight'        =>0,
                            'created_time'  =>date("Y-m-d H:i:s")
                        );
                        $insWalletAcc= $this->insertData($wallet_acc,'ret_wallet');
                        if($insWalletAcc)
                        {
                            $insWallet=array(
                            'id_ret_wallet'	    =>$insWalletAcc,
                            'id_issue_receipt'	=>$insId,
                            'amount'			=>$row->ClosingAmt,
                            'weight'			=>0,
                            'transaction_type'	=>0,
                            'created_on' 		=> date("Y-m-d H:i:s"),
                            'remarks'	 		=>'Existing Balance Credit Sales Amount'
                            );
                            $this->insertData($insWallet,'ret_wallet_transcation');
                        }
                    }
	 			}
				
	        }
	       }
	   }
	       if($this->db->trans_status()==TRUE)
	       {
	           $this->db->trans_commit();
	           $responseData=array('status'=>'TRUE','message'=>$total_import_Data.' '.' Records Imported Successfully..');
	       }
	       else
	       {
	           $this->db->trans_rollback();
	           $responseData=array('status'=>'FLASE','message'=>'Unable to Import','last_query'=>$this->db->last_query());
	       }
	   }
	   else
	   {
	       $responseData=array('status'=>'FLASE','message'=>'No Records Found..');
	   }
	   
	   return $responseData;
	}
	
	//Generate Tag Code
	function generateTagCode($lastTagCode){
	    $tagCode        = $lastTagCode; // Saple : 1-A12345 or 1-12345
        $code_det       = explode('-',$tagCode);
        $alpha_char		='';
        // Split Alphabet and Serial number
        if(preg_match('/[A-Z]+\K/',$code_det[1]))
        {
        	$str_split = preg_split('/[A-Z]+\K/',$code_det[1]);
        	$tag_number=$str_split[1];
        	$alpha_char=$str_split[0];
        }else{
        	$tag_number=$code_det[1];
        }
        //  Increment Number
        if($tag_number!=NULL && $tag_number!='' && $tag_number!=99999)
        {
        	$number = (int) $tag_number;
        	$number++;
        	$code_number=str_pad($number, 5, '0', STR_PAD_LEFT);
        }
        else
        {
        	$code_number=str_pad('1', 5, '0', STR_PAD_LEFT);
        }
        //  Increment Alphabet if reached 99999
        if($tag_number==99999)
        {
        	if($alpha_char == '')
        	{
        		$alpha_char='A';
        	}else{
        		$alpha_char=++$alpha_char;
        	}
        	
        } 
        return $alpha_char.''.$code_number;
	}
	
	//Generate Tag Code
	
	
	//customer import
    function importCustomer()
	{
	   $responseData=array();
	   $total_import_Data=0;
	   $sql=$this->db->query("SELECT * FROM `customer_import` WHERE import_status=0 AND LENGTH(mobile)=10");
	   
	   $importData=$sql->result_array();
	   
	   if(sizeof($importData)>0)
	   {
	       foreach($importData as $cus)
	       {
	           if($cus['mobile']!='')
	           {
	               $cusExists=$this->db->query("SELECT * FROM `customer` where mobile='".$cus['mobile']."'");
	               //print_r($cusExists->num_rows());exit;
	               if($cusExists->num_rows()==0)
	               {
	                    $insData=array(
                        'firstname'    =>($cus['firstname']!='' ? $cus['firstname']:NULL),
                        'mobile'       =>$cus['mobile'],
                        'pan'          =>($cus['pan']!='' ? $cus['pan']:NULL),
                        'gst_number'   =>($cus['pan']!='' ? $cus['pan']:NULL),
                        'username'      =>$cus['mobile'],
	                    'passwd'       =>$this->__encrypt($cus['mobile']),
                        'date_add'     =>date("Y-m-d H:i:s"),
                        );
                        $this->db->trans_begin();
                        $cusId=$this->insertData($insData,'customer');
                        if($cusId)
                        {
                            
                            $this->updateData(array("import_status" => 1), 'id_cus_import',$cus['id_cus_import'], 'customer_import');
                            
                            if($cus['address1']!='' || $cus['address2']!='' || $cus['city']!='' || $cus['pincode']!='' )
                            {
                                $id_city='';
                                if($cus['city']!='')
                                {
                                    $city_details=$this->get_city_details($cus['city']);
                                    if($city_details['id_city']!='')
                                    {
                                        $id_city    =$city_details['id_city'];
                                    }
                                    
                                }
                                
                                $addressData=array(
                                              'address1'    =>($cus['address1']!='' ? $cus['address1']:NULL),
                                              'address2'    =>($cus['address2']!='' ? $cus['address2']:NULL),
                                              'address3'    =>($cus['address3']!='' ? $cus['address3']:NULL),
                                              'pincode'     =>($cus['pincode']!='' ? $cus['pincode']:NULL),
                                              'id_customer' =>$cusId,
                                              'id_state'    =>35,
                                              'id_country'  =>101,
                                              'id_city'     =>($id_city!='' ? $id_city :NULL),
                                              );
                                $this->insertData($addressData,'address');
                            }
                            $total_import_Data+=$total_import_Data+1;
                        }
	               }
	      /*         else   //    CUSTOMER ALREADY EXISTS
	               {
	                   $cusADDExists=$this->db->query("SELECT * FROM `address` where id_customer='".$cusExists->row()->id_customer."'"); // CHECK ADDRESS EXISTS
	                   
	                  
                        if($cus['address1']!='' || $cus['address2']!='' || $cus['city']!='' || $cus['pincode']!='' )
                        {
                            $id_city='';
                            if($cus['city']!='')
                            {
                                $city_details=$this->get_city_details($cus['city']);
                                if($city_details['id_city']!='')
                                {
                                    $id_city    =$city_details['id_city'];
                                }
                                
                            }
                            
                            $addressData=array(
                                          'address1'    =>($cus['address1']!='' ? $cus['address1']:NULL),
                                          'address2'    =>($cus['address2']!='' ? $cus['address2']:NULL),
                                          'address3'    =>($cus['address3']!='' ? $cus['address3']:NULL),
                                          'pincode'     =>($cus['pincode']!='' ? $cus['pincode']:NULL),
                                          'id_customer' =>$cusExists->row()->id_customer,
                                          'id_state'    =>35,
                                          'id_country'  =>101,
                                          'id_city'     =>($id_city!='' ? $id_city :NULL),
                                          );
                                          
                            if($cusADDExists->num_rows()==0)
                            {
                                $this->insertData($addressData,'address');
                                //print_r($this->db->last_query());exit;
                            }
                            else
                            {
                                $this->updateData($addressData, 'id_address',$cusADDExists->row()->id_address, 'address');
                            }
                        }
                            
	               }*/
	           }
	       }
	       
	       if($this->db->trans_status()==TRUE)
	       {
	           $this->db->trans_commit();
	           $responseData=array('status'=>'TRUE','message'=>$total_import_Data.' '.' Records Imported Successfully..');
	       }
	       else
	       {
	           $this->db->trans_rollback();
	           $responseData=array('status'=>'FLASE','message'=>'Unable to Import','last_query'=>$this->db->last_query());
	       }
	       
	   }
	   else
	   {
	       $responseData=array('status'=>'FLASE','message'=>'No Records Found..');
	   }
	   
	   return $responseData;
	}
	
	
	function get_city_details($city_name)
	{
	    $sql=$this->db->query("SELECT * FROM `city` WHERE name like '%".$city_name."%'");
	    //print_r($this->db->last_query());exit;
	    return $sql->row_array();
	}
	//customer import
	

}
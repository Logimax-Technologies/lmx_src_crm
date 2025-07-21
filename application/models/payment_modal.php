<?php
class Payment_modal extends CI_Model
{
    public function updData($data, $id_field, $id_value, $table)
    {
        $edit_flag = 0;
        $this->db->where($id_field, $id_value);
        $edit_flag = $this->db->update($table, $data);
        return ($edit_flag == 1 ? $id_value : 0);
    }
    function generate_receipt_no($id_scheme, $branch)
    {
        $rcpt_no = "";
        $rcpt = $this->get_receipt_no($id_scheme, $branch);
        if ($rcpt != NULL) {
            if ($this->config->item('receipTcode') != '') {          // based on the config settings to removed comp shortcode front of recp num //HH
                $temp = explode($this->comp['short_code'], $rcpt);
                if (isset($temp)) {
                    $number = (int) $temp[1];
                    $number++;
                    $rcpt_no = $this->comp['short_code'] . str_pad($number, 7, '0', STR_PAD_LEFT);
                }
            } else {
                $number = (int) $rcpt;
                $number++;
                //$rcpt_no = str_pad($number, 7, '0', STR_PAD_LEFT);
                $rcpt_no = $number;
            }
        } else {
            if ($this->config->item('receipTcode') != '') {
                $rcpt_no = $this->comp['short_code'] . "000001";
            } else {
                $rcpt_no = "000001";
            }
        }
        return $rcpt_no;
    }
    function get_payment_gateway()
    {
        $sql = "SELECT
			      id_gateway,pg_settings_id,
			      `key`,
			      salt,pg_name,
			      api_url,param_1,m_code,
			      if(type=0,'Demo','Real') as type,
			      is_default
			 FROM gateway
			 WHERE is_default=1";
        return $this->db->query($sql)->result_array();
    }
    function get_selectedScheme()
    {
        $records = array();
        $is_redirect = 0;
        $query_scheme = $this->db->query('SELECT 
												sa.id_scheme_account as id_scheme_account, 
												ref_no as client_id,if(sa.scheme_acc_number !="",CONCAT(s.code," ",sa.scheme_acc_number),"") as scheme_account,
												s.code as scheme_code,
												s.id_scheme as id_scheme,
												scheme_name, 
												scheme_type, 
												description,
												amount,
												total_installments, 
												min_weight, IFNULL(max_weight,0) AS max_weight, 
												min_chance,
												IFNULL(max_chance,0) AS max_chance, 
												interest_by, 
												interest_value, 
												payment_amount, 
												payment_status,
												date_payment, 
												id_payment,
												metal_rate,
												IFNULL(metal_weight,0) AS metal_weight,
												payment_mode,
												is_pan_required,
												pan, 
												totalpay.paidIns+if(is_opening=1,IFNULL(paid_installments,0),0)  AS  totalPaidInstall, 
												total_installments ,
												is_opening, 
												if(is_opening=1,if(DATE_FORMAT(NOW(),"%Y%m") = DATE_FORMAT(last_paid_date,"%Y%m"),IFNULL(last_paid_weight,0),0),0) AS last_paid_weight, 
												if(is_opening=1,if(DATE_FORMAT(NOW(),"%Y%m") = DATE_FORMAT(last_paid_date,"%Y%m"),IFNULL(last_paid_chances,0),0),0) AS last_paid_chances, 
												if(is_opening=1, if(DATE_FORMAT(now(),"%Y%m") > DATE_FORMAT(last_paid_date,"%Y%m"),0,1),0) AS lastPaidDate_amount 
												FROM scheme_account as sa 
												LEFT JOIN scheme as s ON s.id_scheme = sa.id_scheme 
												LEFT JOIN customer as cus ON cus.id_customer = sa.id_customer 
												LEFT JOIN (SELECT id_scheme_account,COUNT(DISTINCT(DATE_FORMAT(date_payment,"%Y%m"))) AS paidIns from payment WHERE payment_status = 1 GROUP BY id_scheme_account) AS totalpay ON totalpay.id_scheme_account = sa.id_scheme_account 
												LEFT JOIN payment as p ON p.id_scheme_account = sa.id_scheme_account AND DATE_FORMAT(date_payment,"%Y%m") = DATE_FORMAT(CURDATE(),"%Y%m") AND  payment_status = 1 
												WHERE 
												mobile="' . $this->session->userdata('username') . '" AND 
												is_closed != 1');
        if ($query_scheme->num_rows() > 0) {
            $metal_weight = 0;
            $chances = 0;
            foreach ($query_scheme->result() as $row) {
                if ($row->id_payment != NULL && $row->scheme_type == 1) {
                    $metal_weight = $metal_weight + $row->metal_weight;
                    $chances = $chances + 1;
                }
                $records[] = array(
                    'id_scheme_account' => $row->id_scheme_account,
                    'scheme_code' => $row->scheme_code,
                    'scheme_account' => $row->scheme_account,
                    'id_scheme' => $row->id_scheme,
                    'scheme_name' => $row->scheme_name,
                    'description' => $row->description,
                    'scheme_type' => $row->scheme_type,
                    'amount' => $row->amount,
                    'total_installments' => $row->total_installments,
                    'min_weight' => $row->min_weight,
                    'max_weight' => $row->max_weight,
                    'min_chance' => $row->min_chance,
                    'max_chance' => $row->max_chance,
                    'interest_by' => $row->interest_by,
                    'interest_value' => $row->interest_value,
                    'payment_amount' => $row->payment_amount,
                    'payment_status' => $row->payment_status,
                    'date_payment' => $row->date_payment,
                    'id_payment' => $row->id_payment,
                    'metal_rate' => $row->metal_rate,
                    'metal_weight' => $row->metal_weight,
                    'payment_mode' => $row->payment_mode,
                    'is_pan_required' => $row->is_pan_required,
                    'pan' => $row->pan,
                    'totalPaidInstall' => $row->totalPaidInstall,
                    'total_installments' => $row->total_installments,
                    'lastPaidDate_amount' => $row->lastPaidDate_amount,
                    'is_opening' => $row->is_opening,
                    'last_paid_weight' => $row->last_paid_weight,
                    'last_paid_chances' => $row->last_paid_chances,
                    'paid_weight' => $metal_weight,
                    'paid_chance' => $chances,
                    'isPaymentExist' => $this->isPaymentExist($row->id_scheme_account)
                );
                if ($row->is_pan_required == 1 ? $row->pan == '' : false) {
                    $is_redirect = 1;
                }
            }
        }
        $weight = array();
        $query_weight = $this->db->query('SELECT * FROM  weight');
        if ($query_weight->num_rows() > 0) {
            foreach ($query_weight->result() as $row) {
                $weight[] = array('id_weight' => $row->id_weight, 'weight' => $row->weight);
            }
        }
        $query_scheme->free_result();
        $bank = array();
        $query_bank = $this->db->query('SELECT bank_name, bank_acc_name,bank_acc_number, bank_branch, bank_ifsc FROM company');
        if ($query_bank->num_rows() > 0) {
            foreach ($query_bank->result() as $row) {
                $bank[] = array('bank_name' => $row->bank_name, 'bank_acc_number' => $row->bank_acc_number, 'bank_acc_name' => $row->bank_acc_name, 'bank_branch' => $row->bank_branch, 'bank_ifsc' => $row->bank_ifsc);
            }
        }
        $query_bank->free_result();
        $customer = array();
        $query_customer = $this->db->query("SELECT firstname,lastname,email,address1,pincode FROM  customer AS cus LEFT JOIN address AS addr ON cus.id_customer = addr.id_customer  WHERE mobile='" . $this->session->userdata('username') . "'");
        if ($query_customer->num_rows() > 0) {
            foreach ($query_customer->result() as $row) {
                $customer[] = array('firstname' => $row->firstname, 'lastname' => $row->lastname, 'email' => $row->email, 'address1' => $row->address1, 'pincode' => $row->pincode);
            }
        }
        //$isPaymentExist = $this->isPaymentExist(); 
        return array('scheme' => $records, 'weight' => $weight, 'bank' => $bank, 'customer' => $customer, 'is_redirect' => $is_redirect);
    }
    function addPayment($insertData)
    {
        $return_data = array();
        if ($this->db->insert('payment', $insertData)) {
            $insertID = $this->db->insert_id();
            //	print_r($this->db->last_query());exit;	
            $status = array("status" => true, "insertID" => $insertID);
        } else {
            $status = array("status" => false, "insertID" => '');
        }
        return $status;
    }
    function add_GA_Payment($insertData)
    {
        $return_data = array();
        if ($this->db->insert('general_advance_payment', $insertData)) {
            $insertID = $this->db->insert_id();
            //	print_r($this->db->last_query());exit;	
            $status = array("status" => true, "insertID" => $insertID);
        } else {
            $status = array("status" => false, "insertID" => '');
        }
        return $status;
    }
    function addPendingPayment($insertData)
    {
        $return_data = array();
        if ($this->db->insert('pending_payment', $insertData)) {
            $insertID = $this->db->insert_id();
            $status = array("status" => true, "insertID" => $insertID);
        } else {
            $status = array("status" => false, "insertID" => '');
        }
        return $status;
    }
    function getPaymentByTxnid($txnid)
    {
        $this->db->select('*');
        $this->db->where('id_transaction', $txnid);
        $r = $this->db->get('payment');
        return $r->row_array();
    }
    function getPaymentByID($id_payment)
    {
        $this->db->select('*');
        $this->db->where('id_payment', $id_payment);
        $r = $this->db->get('payment');
        return $r->row_array();
    }
    //update gateway response
    function updateGatewayResponse($data, $txnid)
    {
        $this->db->where('ref_trans_id', $txnid);
        $status = $this->db->update('payment', $data);
        /*print_r($data);
        echo ($this->db->update('payment',$data));
        echo $status;echo 'status';exit;
        */
        $result = array(
            'status' => $status,
            'id_payment' => $this->get_lastUpdateID($txnid)
        );
        return $result;
    }
    function updatePendingPayment($data, $txnid)
    {
        $this->db->where('ref_trans_id', $txnid);
        $status = $this->db->update('pending_payment', $data);
        $result = array(
            'status' => $status,
            'id_payment' => $this->get_pending_lastUpdateID($txnid)
        );
        return $result;
    }
    function getPendingPayment($txnid)
    {
        $this->db->select('*');
        $this->db->where('id_transaction', $txnid);
        $this->db->where('processed', 0);
        $result = $this->db->get('pending_payment');
        return $result->row_array();
    }
    function get_lastUpdateID($txnid)
    {
        $this->db->select('id_payment');
        $this->db->where('ref_trans_id', $txnid);
        $payid = $this->db->get('payment');
        return $payid->row()->id_payment;
    }
    function get_pending_lastUpdateID($txnid)
    {
        $this->db->select('id_payment');
        $this->db->where('id_transaction', $txnid);
        $payid = $this->db->get('pending_payment');
        return $payid->row()->id_payment;
    }
    function get_schemeByChit($id_scheme_account)
    {
		$sql = "select s.is_digi,s.interest,
		s.id_scheme,s.code,s.scheme_type,s.amount,s.gst,s.gst_type,s.wgt_convert,s.flexible_sch_type,metal_wgt_decimal,metal_wgt_roundoff,id_metal,scheme_acc_number,sync_scheme_code,s.one_time_premium,s.rate_select,s.rate_fix_by,
		IFNULL(IF(sa.is_opening=1,IFNULL(sa.paid_installments,0)+ IFNULL(if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues)),0), if(s.scheme_type = 1 and s.min_weight != s.max_weight or (s.scheme_type=3 and s.payment_chances=1) , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues))) ,0)as paid_installments
		from scheme s
		left join scheme_account sa on sa.id_scheme=s.id_scheme
		LEFT JOIN payment p on (p.id_scheme_account = sa.id_scheme_account)
		join chit_settings cs
		where sa.id_scheme_account='$id_scheme_account'";
        $result = $this->db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->row_array();
        }
    }
    function get_invoiceData($payment_no)
    {
        $ac_no_label = $this->config->item('default_acno_label');
        $showGCodeInAcNo = $this->config->item('showGCodeInAcNo');
        $records = array();
        $query_invoice = $this->db->query("SELECT pay.id_scheme_account as id_scheme_account,sch_acc.account_name as account_name, if(cs.has_lucky_draw=1,concat(ifnull(sch_acc.group_code,''),'  ',ifnull(sch_acc.scheme_acc_number,'Not Allocated')),concat(if('.$showGCodeInAcNo=1.',sch.code,''),'  ',IFNULL(sch_acc.scheme_acc_number,'$ac_no_label')))as scheme_acc_number,pay.id_branch, DATE_FORMAT(pay.date_payment,'%d-%m-%Y') as date_payment, sch.scheme_name as scheme_name, pay.payment_amount as payment_amount,cus.firstname as firstname, cus.lastname as lastname, addr.address1 as address1,addr.address2 as address2,addr.address3 as address3,ct.name as city,addr.pincode,email,cus.mobile,if(payment_mode='CC','Credit Card',if(payment_mode='NB','Net Banking',if(payment_mode='CD','Cheque or DD',if(payment_mode='CO','Cash Pick Up',if(payment_mode='FP','Free Payment',pm.mode_name))))) as payment_mode,id_transaction,payment_ref_number,cs.receipt_no_set,if((c.receipt_no_set= 1 && pay.payment_status =1 && pay.receipt_no is null ),pay.receipt_no,if((c.receipt_no_set=1 && pay.payment_status =1 && pay.receipt_no!=''),pay.receipt_no,pay.receipt_no)) as receipt_no,bank_name,bank_acc_no,bank_branch,ifnull(metal_weight,'-') as metal_weight,metal_rate,scheme_type,IF(sch_acc.is_opening=1,IFNULL(sch_acc.paid_installments,0)+ COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')),
COUNT(Distinct Date_Format(pay.date_payment,'%Y%m'))) as installment,lg.paid_ins as paid_installments,
		DATE_FORMAT(Date_add(date_payment,Interval 1 month),'%b %Y') as next_due,cs.currency_name,cs.currency_symbol,pay.id_transaction as trans_id, pay.due_type,sch.code,ifnull(pay.add_charges,0.00) as add_charges,pay.payment_type,sch.charge_head,sch.id_scheme,pay.gst,pay.gst_type,pay.date_add,addr.id_state,s.name as state,con.name as country,avg_calc_ins,avg_payable,gst_amount,flexible_sch_type,DATE_FORMAT(sch_acc.maturity_date,'%d-%m-%Y') as maturity_date
							FROM payment as pay
							JOIN  chit_settings c
							LEFT JOIN scheme_account sch_acc ON sch_acc.id_scheme_account = pay.id_scheme_account
							LEFT JOIN scheme sch ON sch.id_scheme = sch_acc.id_scheme
							LEFT JOIN customer as cus ON cus.id_customer = sch_acc.id_customer
							LEFT JOIN address as addr ON addr.id_customer = cus.id_customer 
							LEFT JOIN city as ct ON addr.id_city = ct.id_city 
							LEFT JOIN state as s ON addr.id_state = s.id_state 
							LEFT JOIN country as con ON addr.id_country = con.id_country 
							LEFT JOIN payment_mode pm ON (pay.payment_mode = pm.short_code)
							 left join ( select 
				 		   sch.id_scheme_account ,
						   IFNULL(IF(sch.is_opening=1,IFNULL(sch.paid_installments,0)+ IFNULL(if(sc.scheme_type = 1 and sc.min_weight != sc.max_weight ,COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')), sum(pay.no_of_dues)),0),if(sc.scheme_type = 1 and sc.min_weight != sc.max_weight or (sc.scheme_type=3 AND sc.firstPayamt_as_payamt = 0), COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')), sum(pay.no_of_dues))) ,0) as paid_ins						   
						 From payment pay
						   Left Join scheme_account sch on(pay.id_scheme_account=sch.id_scheme_account and sch.active=1 and sch.is_closed=0)
						   Left Join scheme sc on(sc.id_scheme=sch.id_scheme) 
						   Where (pay.payment_status=2 or pay.payment_status=1) 
						   Group By sch.id_scheme_account) lg on (sch_acc.id_scheme_account=lg.id_scheme_account )
							join chit_settings cs
							WHERE pay.payment_status=1 AND id_payment = '" . $payment_no . "' AND mobile='" . $this->session->userdata('username') . "'");
        //echo $this->db->last_query();exit;
        if ($query_invoice->num_rows() > 0) {
            foreach ($query_invoice->result() as $row) {
                $records[] = array('id_scheme_account' => $row->id_scheme_account, 'currency_symbol' => $row->currency_symbol, 'currency_name' => $row->currency_name, 'scheme_acc_number' => $row->scheme_acc_number, 'date_payment' => $row->date_payment, 'scheme_name' => $row->scheme_name, 'payment_amount' => $row->payment_amount, 'firstname' => $row->firstname, 'lastname' => $row->lastname, 'id_payment' => $payment_no, 'address1' => $row->address1, 'address2' => $row->address2, 'address3' => $row->address3, 'city' => $row->city, 'pincode' => $row->pincode, 'email' => $row->email, 'mobile' => $row->mobile, 'payment_mode' => $row->payment_mode, 'id_transaction' => $row->id_transaction, 'payment_ref_number' => $row->payment_ref_number, 'receipt_no' => $row->receipt_no, 'receipt_no_set' => $row->receipt_no_set, 'bank_name' => $row->bank_name, 'bank_acc_no' => $row->bank_acc_no, 'bank_branch' => $row->bank_branch, 'id_branch' => $row->id_branch, 'metal_weight' => $row->metal_weight, 'metal_rate' => $row->metal_rate, 'scheme_type' => $row->scheme_type, 'installment' => $row->installment, 'next_due' => $row->next_due, 'trans_id' => $row->trans_id, 'account_name' => $row->account_name, 'due_type' => $row->due_type, 'code' => $row->code, 'add_charges' => $row->add_charges, 'charge_head' => $row->charge_head, 'payment_type' => $row->payment_type, 'id_scheme' => $row->id_scheme, 'gst_type' => $row->gst_type, 'gst' => $row->gst, 'date_add' => $row->date_add, 'id_state' => $row->id_state, 'state' => $row->state, 'country' => $row->country, 'avg_calc_ins' => $row->avg_calc_ins, 'avg_payable' => $row->avg_payable, 'paid_installments' => $row->paid_installments, 'maturity_date' => $row->maturity_date, 'flexible_sch_type' => $row->flexible_sch_type, 'gst_amount' => $row->gst_amount, );
            }
        }
        return $records;
    }
    function get_invoiceDataM($payment_no)
    {
        $records = array();
        $query_invoice = $this->db->query("SELECT pay.id_scheme_account as id_scheme_account, if(cs.has_lucky_draw=1,concat(ifnull(sch_acc.group_code,''),'',ifnull(sch_acc.scheme_acc_number,'Not allocated')),concat(ifnull(sch.code,''),' ',ifnull(sch_acc.scheme_acc_number,'Not allocated')))as scheme_acc_number, DATE_FORMAT(pay.date_payment,'%d-%m-%Y') as date_payment, sch.scheme_name as scheme_name, pay.payment_amount as payment_amount,cus.firstname as firstname, cus.lastname as lastname, addr.address1 as address1,addr.address2 as address2,addr.address3 as address3,ct.name as city,addr.pincode,email,cus.mobile,if(payment_mode='CC','Credit Card',if(payment_mode='NB','Net Banking',if(payment_mode='CD','Cheque or DD',if(payment_mode='CO','Cash Pick Up',pm.mode_name)))) as payment_mode,id_transaction,payment_ref_number,psm.payment_status,psm.id_status_msg,pay.receipt_no,bank_name,bank_acc_no,bank_branch,ifnull(metal_weight,'-') as metal_weight,metal_rate,scheme_type,COUNT(pay.id_payment) as installment,DATE_FORMAT(Date_add(date_payment,Interval 1 month),'%d-%m-%Y') as next_due,cs.currency_name,cs.currency_symbol,sch.code,ifnull(pay.add_charges,0.00) as add_charges,pay.payment_type,sch.charge_head
							FROM payment as pay
							LEFT JOIN scheme_account sch_acc ON sch_acc.id_scheme_account = pay.id_scheme_account
							LEFT JOIN scheme sch ON sch.id_scheme = sch_acc.id_scheme
							LEFT JOIN customer as cus ON cus.id_customer = sch_acc.id_customer
							LEFT JOIN address as addr ON addr.id_customer = cus.id_customer 
							LEFT JOIN city as ct ON addr.id_city = ct.id_city 
							LEFT JOIN payment_mode pm ON (pay.payment_mode = pm.short_code)
							LEFT JOIN payment_status_message psm ON (pay.payment_status = psm.id_status_msg)
							JOIN chit_settings cs
							WHERE id_payment = '" . $payment_no . "'");
        if ($query_invoice->num_rows() > 0) {
            foreach ($query_invoice->result() as $row) {
                $records[] = array('id_scheme_account' => $row->id_scheme_account, 'scheme_acc_number' => $row->scheme_acc_number, 'date_payment' => $row->date_payment, 'scheme_name' => $row->scheme_name, 'payment_amount' => $row->payment_amount, 'firstname' => $row->firstname, 'lastname' => $row->lastname, 'id_payment' => $payment_no, 'address1' => $row->address1, 'address2' => $row->address2, 'address3' => $row->address3, 'city' => $row->city, 'pincode' => $row->pincode, 'email' => $row->email, 'mobile' => $row->mobile, 'payment_mode' => $row->payment_mode, 'trans_id' => $row->id_transaction, 'payment_ref_number' => $row->payment_ref_number, 'payment_status' => $row->payment_status, 'id_payment_status' => $row->id_status_msg, 'receipt_no' => $row->receipt_no, 'bank_name' => $row->bank_name, 'bank_acc_no' => $row->bank_acc_no, 'bank_branch' => $row->bank_branch, 'metal_weight' => $row->metal_weight, 'metal_rate' => $row->metal_rate, 'scheme_type' => $row->scheme_type, 'installment' => $row->installment, 'next_due' => $row->next_due, 'currency_symbol' => $row->currency_symbol, 'currency_name' => $row->currency_name, 'code' => $row->code, 'add_charges' => $row->add_charges, 'charge_head' => $row->charge_head, 'payment_type' => $row->payment_type);
            }
        }
        return $records;
    }
    // closed a/c payment entries removed in payment History page//HH
    function get_paymenthistory($branchWiseLogin)
    {
        $records = array();
        if ($branchWiseLogin == 1) {
            $query_scheme = $this->db->query("select sch.code,sg.group_code as scheme_group_code, UNIX_TIMESTAMP(Date_Format(sg.start_date,'%Y-%m-%d')) as group_start_date,  UNIX_TIMESTAMP(Date_Format(sg.end_date,'%Y-%m-%d')) as  group_end_date,  cs.has_lucky_draw, id_payment, DATE_FORMAT(date_payment,'%d-%m-%Y') AS date_payment, metal_rate, metal_weight,pay.receipt_no,DATE_FORMAT(pay.date_add,'%d-%m-%Y') as paid_date,ifnull(pay.add_charges,0.00) as add_charges,pay.due_type,if(pay.payment_mode = NULL,pm.short_code,pay.payment_mode)as payment_mode,pay.id_branch,id_transaction,psm.id_status_msg,psm.payment_status, sa.scheme_acc_number ,ref_no AS client_id, scheme_name,sch.code,pay.payment_type,pay.gst,pay.gst_type,
		sch.charge_head,br.name as branch_name,br.id_branch,if(scheme_type = 0,'Amount Scheme','Weight Scheme') as scheme_type,cs.currency_name,cs.currency_symbol,psm.color,(CASE WHEN (pay.due_type='A' or pay.due_type='P') and pay.payment_status!=1 THEN pay.`act_amount` ELSE pay.payment_amount END) as  payment_amount,sch.is_lucky_draw as is_lucky_draw FROM payment as pay
		left join scheme_account AS sa on sa.id_scheme_account = pay.id_scheme_account
		Left Join scheme_group sg On (sa.group_code = sg.group_code )
		Left Join branch br On (pay.id_branch=br.id_branch)
		left join scheme as sch on sch.id_scheme = sa.id_scheme
		left join customer as cus on  cus.id_customer = sa.id_customer
		join chit_settings cs
		LEFT Join payment_status_message psm ON (pay.payment_status=psm.id_status_msg)
		Left Join payment_mode pm On (pay.payment_mode=pm.short_code)
		WHERE is_closed=0 and cus.mobile='" . $this->session->userdata('username') . "' and sa.active=1");
        } else {
            $query_scheme = $this->db->query("select sch.code,sg.group_code as scheme_group_code, UNIX_TIMESTAMP(Date_Format(sg.start_date,'%Y-%m-%d')) as group_start_date,  UNIX_TIMESTAMP(Date_Format(sg.end_date,'%Y-%m-%d')) as  group_end_date,  cs.has_lucky_draw, id_payment, DATE_FORMAT(date_payment,'%d-%m-%Y') AS date_payment, metal_rate, metal_weight,pay.receipt_no,DATE_FORMAT(pay.date_add,'%d-%m-%Y') as paid_date,ifnull(pay.add_charges,0.00) as add_charges,pay.due_type,if(pay.payment_mode = NULL,pm.short_code,pay.payment_mode)as payment_mode,pay.id_branch,id_transaction,psm.id_status_msg,psm.payment_status, sa.scheme_acc_number ,ref_no AS client_id, scheme_name,sch.code,pay.payment_type,pay.gst,pay.gst_type,
		sch.charge_head,br.name as branch_name,br.id_branch,if(scheme_type = 0,'Amount Scheme','Weight Scheme') as scheme_type,cs.currency_name,cs.currency_symbol,psm.color,(CASE WHEN (pay.due_type='A' or pay.due_type='P') and pay.payment_status!=1 THEN pay.`act_amount` ELSE pay.payment_amount END) as  payment_amount,sch.is_lucky_draw as is_lucky_draw  FROM payment as pay
		left join scheme_account AS sa on sa.id_scheme_account = pay.id_scheme_account
		Left Join scheme_group sg On (sa.group_code = sg.group_code )
		Left Join branch br On (pay.id_branch=br.id_branch)
		left join scheme as sch on sch.id_scheme = sa.id_scheme
		left join customer as cus on  cus.id_customer = sa.id_customer
		join chit_settings cs
		LEFT Join payment_status_message psm ON (pay.payment_status=psm.id_status_msg)
		Left Join payment_mode pm On (pay.payment_mode=pm.short_code)
		WHERE is_closed=0 and cus.mobile='" . $this->session->userdata('username') . "'and sa.active=1 ");
        }
        if ($query_scheme->num_rows() > 0) {
            foreach ($query_scheme->result() as $row) {
                $records[] = array('scheme_group_code' => $row->scheme_group_code, 'code' => $row->code, 'has_lucky_draw' => $row->has_lucky_draw, 'is_lucky_draw' => $row->is_lucky_draw, 'id_payment' => $row->id_payment, 'currency_name' => $row->currency_name, 'currency_symbol' => $row->currency_symbol, 'date_payment' => $row->date_payment, 'receipt_no' => $row->receipt_no, 'metal_rate' => $row->metal_rate, 'branch_name' => $row->branch_name, 'id_branch' => $row->id_branch, 'payment_amount' => $row->payment_amount, 'metal_weight' => $row->metal_weight, 'payment_mode' => $row->payment_mode, 'id_transaction' => $row->id_transaction, 'id_pay_status' => $row->id_status_msg, 'payment_status' => $row->payment_status, 'scheme_acc_number' => $row->scheme_acc_number, 'client_id' => $row->client_id, 'scheme_name' => $row->scheme_name, 'scheme_type' => $row->scheme_type, 'color' => $row->color, 'paid_date' => $row->paid_date, 'code' => $row->code, 'add_charges' => $row->add_charges, 'charge_head' => $row->charge_head, 'payment_type' => $row->payment_type, 'due_type' => $row->due_type, 'gst' => $row->gst, 'gst_type' => $row->gst_type);
            }
            ;
        }
        return $records;
    }
    function calc_charges($amt)
    {
        $charge = array();
        $query_charge = $this->db->query('SELECT c.id_charges as id_charges,payment_mode,code, charge_type, IFNULL(charges_value,0) AS charges_value, service_tax FROM  charges AS c
										LEFT JOIN
										(SELECT id_range, id_charges, lower_limit, upper_limit,charge_type, charges_value FROM charges_range WHERE
										' . $amt . ' between lower_limit AND ifnull(upper_limit,10000000))
										AS cr ON cr.id_charges = c.id_charges  WHERE active = 1 Order By payment_mode');
        if ($query_charge->num_rows() > 0) {
            foreach ($query_charge->result() as $row) {
                $charge[] = array('id_charges' => $row->id_charges, 'payment_mode' => $row->payment_mode, 'code' => $row->code, 'charge_type' => $row->charge_type, 'charges_value' => $row->charges_value, 'service_tax' => $row->service_tax);
            }
            $query_charge->free_result();
        }
        echo json_encode($charge);
    }
    //to check whether customer has payment entry
    function isPaymentExist($id_scheme_account)
    {
        $sql = "Select
					  sa.id_scheme_account,c.mobile
				From payment p
				Left Join scheme_account sa On (p.id_scheme_account = sa.id_scheme_account)
				Left Join customer c on (sa.id_customer = c.id_customer)
				Where (p.payment_status = 1) And sa.id_scheme_account= '" . $id_scheme_account . "' ";
        $records = $this->db->query($sql);
        if ($records->num_rows() > 0) {
            return TRUE;
        }
    }
    //get last paid entry
    function getLastTransaction($id_scheme_account)
    {
        $sql = " Select * from payment
 			   Where 
 			        id_payment=(select max(id_payment) from payment where id_scheme_account='$id_scheme_account') 
 			      and id_scheme_account='$id_scheme_account' and payment_status<=2";
        return $this->db->query($sql)->row_array();
    }
    function get_payment_details($mobile)
    {
        $ac_no_label = $this->config->item('default_acno_label');
        $schemeAcc = array();
        $sql = "Select s.allow_unpaid_in,s.allow_advance_in,sa.ref_no,s.set_as_min_from,s.set_as_max_from,s.no_of_dues as dues_count,s.flx_denomintion,s.rate_fix_by,s.rate_select,
	   cs.auto_debit_allow_app_pay,sa.auto_debit_status,s.auto_debit_plan_type,s.id_metal,s.one_time_premium,s.is_enquiry,
	   maturity_type,s.gst,s.gst_type,
	    PERIOD_DIFF(Date_Format(CURRENT_DATE(),'%Y%m'),Date_Format(sa.date_add,'%Y%m')) as current_pay_installemnt,
	   if((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) > s.total_installments, 
        (s.total_installments - COUNT(payment_amount)), 
        ifnull((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) - SUM(p.no_of_dues),if((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) > s.total_installments,s.total_installments,(PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m')))))) 
        as missed_ins,s.avg_calc_ins,sa.avg_payable,
	    PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m')) as months_from_startdate,PERIOD_DIFF(Date_Format(sa.maturity_date,'%Y%m'),Date_Format(curdate(),'%Y%m')) as tot_ins,
        sg.group_code as scheme_group_code, UNIX_TIMESTAMP(Date_Format(sg.start_date,'%Y-%m-%d')) as group_start_date,UNIX_TIMESTAMP(Date_Format(sg.end_date,'%Y-%m-%d')) as  group_end_date,  cs.has_lucky_draw,cs.allow_wallet,cs.useWalletForChit,
        sa.id_scheme_account,s.allowSecondPay,s.free_payment,sa.firstPayment_amt,sa.firstpayment_wgt,s.firstPayamt_maxpayable,sa.is_registered,firstPayamt_as_payamt,s.flexible_sch_type,sa.id_branch as sch_join_branch,
        s.id_scheme,s.maturity_days,s.get_amt_in_schjoin,
        c.id_customer,
        c.firstname,c.lastname,c.email,
        ad.address1,ad.address2,ad.address3,
        cy.name as country,st.name as state,ct.name as city,ad.pincode,
        sa.scheme_acc_number as chit_number,
        IFNULL(sa.account_name,if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname))) as account_name,c.kyc_status,
        c.mobile,s.max_amount, s.min_amount,s.pay_duration,
        s.scheme_type, s.scheme_name,
        s.code,
        IFNULL(s.min_chance,0) as min_chance,
        IFNULL(s.max_chance,0) as max_chance,
        Format(IFNULL(s.max_weight,0),3) as max_weight,
        Format(IFNULL(s.min_weight,0),3) as min_weight,
        sa.start_date,sa.maturity_date,
        IF(s.scheme_type=0 OR s.scheme_type=2,s.amount,IF(s.scheme_type=1 ,s.max_weight,if(s.scheme_type=3,s.min_amount,0))) as payable,
        s.total_installments,
    	IF(s.scheme_type=1,s.max_weight,s.amount) as payable,s.total_installments,IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),1,0) as  previous_paid,		
    	IFNULL(IF(sa.is_opening=1,IFNULL(sa.paid_installments,0)+ IFNULL(if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues)),0), if(s.scheme_type = 1 and s.min_weight != s.max_weight or (s.scheme_type=3 and s.payment_chances=1) , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues))) ,0)as paid_installments,
        IFNULL(IF(sa.is_opening=1,IFNULL(balance_amount,0)+IFNULL(SUM(p.payment_amount * p.no_of_dues),0),IFNULL(SUM(p.payment_amount * p.no_of_dues),0)) ,0)
        as total_paid_amount,
        IFNULL(IF(sa.is_opening=1,IFNULL(balance_weight,0)+IFNULL(SUM(p.metal_weight),0),IFNULL(SUM(p.metal_weight),0)),0.000)
        as total_paid_weight,
        if((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) > s.total_installments, 
        (s.total_installments - COUNT(payment_amount)), 
        ifnull((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) - SUM(p.no_of_dues),if((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) > s.total_installments,s.total_installments,(PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m')))))) 
        as totalunpaid_1, 
        if((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) > s.total_installments,   (s.total_installments - if(sa.is_opening = 1,(COUNT(payment_amount)+sa.paid_installments),COUNT(payment_amount))),ifnull(((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m')))+1) - IF(sa.is_opening=1,IFNULL(sa.paid_installments,0)+ IFNULL(if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues)),0), if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues))),if((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) > s.total_installments,s.total_installments,(PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m')))))) as totalunpaid,   
        IFNULL(if(Date_Format(max(p.date_payment),'%Y%m') = Date_Format(curdate(),'%Y%m'), (select SUM(ip.no_of_dues) from payment ip where Date_Format(ip.date_payment,'%Y%m') = Date_Format(curdate(),'%Y%m') and sa.id_scheme_account = ip.id_scheme_account),IF(sa.is_opening=1, if(Date_Format(sa.last_paid_date,'%Y%m') = Date_Format(curdate(),'%Y%m'), 1,0),0)),0) as currentmonthpaycount, 
        (select SUM(pay.no_of_dues) from payment pay where pay.id_scheme_account= sa.id_scheme_account and pay.due_type='AD' and (pay.payment_status=1 or pay.payment_status=2)) as currentmonth_adv_paycount,
        (select SUM(pay.no_of_dues) from payment pay where pay.id_scheme_account= sa.id_scheme_account and pay.due_type='PD' and (pay.payment_status=1 or pay.payment_status=2)) as currentmonth_pend_paycount,
        IF(s.scheme_type =1 and s.max_weight!=s.min_weight,true,false) as is_flexible_wgt,p.payment_status,
        round(IFNULL(cp.total_amount,0)) as  current_total_amount,
        Format(IFNULL(cp.total_weight,0) + IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),(sa.last_paid_weight),0) ,3) as  current_total_weight,
        IFNULL(cp.paid_installment,0)       as  current_paid_installments,
        IFNULL(cp.chances,0) + IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),(sa.last_paid_chances),0) as  current_chances_used,
        if(s.scheme_type=3 && s.pay_duration=0 ,IFNULL(sp.chance,0) + IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),(sa.last_paid_chances),0),IFNULL(cp.chances,0) + IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),(sa.last_paid_chances),0)) as  current_chances_pay,
        s.is_pan_required,
        IF(sa.is_opening = 1 and s.scheme_type = 0,IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),false,true),true) AS previous_amount_eligible,
        count(pp.id_scheme_account) as cur_month_pdc,
        s.allow_unpaid,
        if(s.allow_unpaid=1,s.unpaid_months,0) as allow_unpaid_months,
        s.allow_advance,
        if(s.allow_advance=1,s.advance_months,0) as advance_months,
        IFNULL(Date_Format(max(p.date_payment),'%d-%m-%Y'),IFNULL(IF(sa.is_opening=1,Date_Format(sa.last_paid_date,'%d-%m-%Y'),'')  ,0)) as last_paid_date,
        IFNULL(PERIOD_DIFF(Date_Format(curdate(),'%Y%m'),Date_Format(max(p.date_payment),'%Y%m')),IF(sa.is_opening=1,PERIOD_DIFF(Date_Format(curdate(),'%Y%m'),Date_Format(sa.last_paid_date,'%Y%m')),0)) as last_paid_duration,
        sa.disable_payment,cs.currency_name,cs.currency_symbol,br.name as branch_name,br.id_branch,s.disable_sch_payment,sa.disable_pay_reason,
		cs.metal_wgt_decimal, cs.metal_wgt_roundoff
        From scheme_account sa
            Left Join scheme s On (sa.id_scheme=s.id_scheme)
            Left Join branch br On (sa.id_branch=br.id_branch)
            Left Join scheme_group sg On (sa.group_code = sg.group_code )
            Left Join payment p On (sa.id_scheme_account=p.id_scheme_account and (p.payment_status=1 or p.payment_status=2 or p.payment_status=8))
            Left Join customer c On (sa.id_customer=c.id_customer and c.active=1)			
            LEFT JOIN address ad  ON (c.id_customer = ad.id_customer)
            LEFT JOIN country cy  ON (ad.id_country = cy.id_country)
            LEFT JOIN state st on (ad.id_state=st.id_state)
            LEFT JOIN city ct  ON (ad.id_city = ct.id_city)			
            LEFT JOIN
            (	Select
                  sa.id_scheme_account,
                  COUNT(Distinct Date_Format(p.date_payment,'%Y%m')) as paid_installment,
                  COUNT(Date_Format(p.date_payment,'%Y%m')) as chances,
                  SUM(p.payment_amount) as total_amount,
                   SUM(p.metal_weight) as total_weight
                From payment p
                    Left Join scheme_account sa on(p.id_scheme_account=sa.id_scheme_account and sa.active=1 and sa.is_closed=0)
                    Where (p.payment_status=1 or p.payment_status=2) and  Date_Format(Current_Date(),'%Y%m')=Date_Format(p.date_payment,'%Y%m')
                Group By sa.id_scheme_account
            ) cp On (sa.id_scheme_account=cp.id_scheme_account)
            LEFT JOIN 
            (   Select sa.id_scheme_account, COUNT(Distinct Date_Format(p.date_payment,'%d%m')) as paid_installment,
                    COUNT(Date_Format(p.date_payment,'%d%m')) as chance,
                    SUM(p.payment_amount) as total_amount,
                    SUM(p.metal_weight) as total_weight
                From payment p
                    Left Join scheme_account sa on(p.id_scheme_account=sa.id_scheme_account and sa.active=1 and sa.is_closed=0)
                    Where  (p.payment_status=2 or p.payment_status=1) and  Date_Format(Current_Date(),'%d%m')=Date_Format(p.date_payment,'%d%m')
                Group By sa.id_scheme_account
            )sp on(sa.id_scheme_account=sp.id_scheme_account)
            LEFT JOIN postdate_payment pp On (sa.id_scheme_account=pp.id_scheme_account and (pp.payment_status=2 or pp.payment_status=7) and (Date_Format(pp.date_payment,'%Y%m')=Date_Format(curdate(),'%Y%m')))
        join chit_settings cs
        Where sa.active=1 and sa.is_closed = 0  and c.mobile='$mobile' and s.is_enquiry=0
        Group By sa.id_scheme_account";
        $records = $this->db->query($sql);
        if ($records->num_rows() > 0) {
            foreach ($records->result() as $record) {
                $current_installments = ($record->current_paid_installments == 0 ? $record->paid_installments + 1 : $record->paid_installments);
                $current_amt_min = '';
                if (($record->get_amt_in_schjoin == 1 && $record->firstPayment_amt > 0) && ($record->set_as_min_from > 0 || $record->set_as_max_from > 0)) {
                    if ($record->paid_installments > 1 && $record->firstPayment_amt != 0) {
                        if ($record->current_total_amount < $record->firstPayment_amt) {
                            $current_amt_min = 'Y';
                        } else {
                            $current_amt_min = 'N';
                        }
                    }
                    if ($current_installments >= $record->set_as_min_from) {
                        if ($record->paid_installments == 0) {
                            $record->min_amount = $record->firstPayment_amt;
                        } else if ($record->paid_installments > 1 && $record->paid_installments <= $record->set_as_max_from) {
                            $record->min_amount = $record->min_amount;
                        }
                    }
                    if ($current_installments >= $record->set_as_max_from) {
                        $record->min_amount = $record->flx_denomintion;
                        $record->max_amount = $record->firstPayment_amt;
                    }
                }
                // Calculate max payable [Applicable only for No advance, No pending enabled schemes]
                if ((($record->scheme_type == 1 && $record->is_flexible_wgt == 1) || $record->scheme_type == 3) && $record->avg_calc_ins > 0) {
                    $current_installments = ($record->current_paid_installments == 0 ? $record->paid_installments + 1 : $record->paid_installments);
                    // Current Installment == Average calc installment
                    if (($current_installments - 1 == $record->avg_calc_ins || $record->avg_payable > 0) && $record->avg_calc_ins > 0) {
                        if ($record->avg_payable > 0) { // Already Average calculated, just set the value
                            if ($record->scheme_type == 1 && $record->is_flexible_wgt == 1) { // Weight - Flexible weight scheme
                                // Set max payable
                            } else if ($record->scheme_type == 3) {
                                if ($record->flexible_sch_type == 2) { // Flexible - Amount to weight [amount based]
                                    // Set max payable
                                    $record->max_amount = $record->avg_payable;
                                } elseif ($record->flexible_sch_type == 3) { // Flexible - Amount to weight [weight based]
                                    $record->max_weight = $record->avg_payable;
                                } elseif ($record->flexible_sch_type == 1) { // Flexible - Amount to weight [weight based]
                                    $record->max_amount = $record->avg_payable;
                                }
                            }
                        } else { // Calculate Average , set the value and updte it in schemme_account table
                            $paid_sql = $this->db->query("SELECT date(date_payment) as date_payment,sum(metal_weight) as paid_wgt,sum(payment_amount) as paid_amt FROM `payment` WHERE ( payment_status=1 or payment_status=2 ) and id_scheme_account=" . $record->id_scheme_account . " GROUP BY YEAR(date_payment), MONTH(date_payment)");
                            $paid_wgt = 0;
                            $paid_amt = 0;
                            $paidByMonth = $paid_sql->result_array();
                            foreach ($paidByMonth as $p) {
                                $paid_wgt = $paid_wgt + $p['paid_wgt'];
                                $paid_amt = $paid_amt + $p['paid_amt'];
                            }
                            if ($record->scheme_type == 1 && $record->is_flexible_wgt == 1) { // Weight - Flexible weight scheme
                                // Set max payable
                            } else if ($record->scheme_type == 3) {
                                if ($record->flexible_sch_type == 2) { // Flexible - Amount to weight [amount based]
                                    // Set max payable
                                    $avg_payable = $paid_amt / $record->avg_calc_ins;
                                    $record->max_amount = $avg_payable;
                                } elseif ($record->flexible_sch_type == 3) { // Flexible - Amount to weight [weight based]
                                    $avg_payable = number_format($paid_wgt / $record->avg_calc_ins, 3);
                                    $record->max_weight = $avg_payable;
                                } elseif ($record->flexible_sch_type == 1) { // Flexible - Amount Based
                                    $avg_payable = $paid_amt / $record->avg_calc_ins;
                                    $record->max_amount = $avg_payable;
                                }
                            }
                            $updData = array("avg_payable" => $avg_payable, "date_upd" => date("Y-m-d"));
                            $this->db->where('id_scheme_account', $record->id_scheme_account);
                            $this->db->update("scheme_account", $updData);
                        }
                    }
                    /*	else if($current_installments > $record->avg_calc_ins){ // Current Installment > Average calc installment
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
                $metal_rates = $this->getMetalRate($record->sch_join_branch);//For branchwise rate
                if ($record->has_lucky_draw == 1) {
                    if ($record->group_start_date == NULL && $record->paid_installments > 1) { // block 2nd payment if scheme_group_code is not updated 
                        $checkDues = FALSE;
                    } else if ($record->group_start_date != NULL) { // block before start date and payment after end date 
                        if ($record->group_end_date >= time() && $record->group_start_date <= time()) {
                            $checkDues = TRUE;
                        } else {
                            $checkDues = FALSE;
                        }
                    }
                }
                if ($record->maturity_days != null) {
                    $current_date = date("Y-m-d");
                    $maturity_date = $record->maturity_date;
                    if (strtotime($current_date) <= strtotime($maturity_date)) {
                        $checkDues = TRUE;
                        if ($record->maturity_type == 2) { // 1 - Flexible[Can pay installments and close], 2 - Fixed Maturity, 3 - Fixed Flexible[Increase maturity if has Default]
                            if (($record->missed_ins + $record->paid_installments) <= $record->total_installments) {
                                $checkDues = TRUE;
                            } else {
                                $checkDues = FALSE;
                            }
                        }
                    } else {
                        $checkDues = FALSE;
                    }
                }
                // Update Maturity Date in scheme_account table if maturity date is flexible
                if ($record->maturity_type == 3) {  // 1 - Flexible[Can pay installments and close], 2 - Fixed Maturity, 3 - Fixed Flexible[Increase maturity if has Default]
                    $paid_sql = $this->db->query("SELECT due_month,due_year FROM `payment` WHERE ( payment_status=1 or payment_status=2 ) and id_scheme_account=" . $record->id_scheme_account . " GROUP BY due_month, due_year order by due_year,due_month");
                    $paidByMonth = $paid_sql->result_array();
                    $skipped_months = 0;
                    for ($i = 0; $i >= 0; $i++) {
                        /*
                        $date = date('Y-m-d', strtotime("+".$i." months", strtotime($record->start_date)));
                        $Ym = date('Y-m', strtotime("+".$i." months", strtotime($record->start_date)));
                        */
                        $Ym = date('Y-m', $this->add_months_to_date($i, $record->start_date));
                        if ($Ym != date("Y-m")) {
                            $isPaid = $this->isPaid($paidByMonth, $Ym);
                            $skipped_months = $skipped_months + ($isPaid ? 0 : 1);
                            //echo $Ym."--".date("Y-m")."--".$skipped_months."<br/>";
                        } else if ($Ym == date("Y-m")) { // Quit Loop
                            $i = -2;
                        }
                    }
                    $maturity = date('Y-m-d', strtotime("+" . ($record->total_installments + $skipped_months) . " months", strtotime($record->start_date)));
                    /*$no_of_months = ( $record->total_installments+$skipped_months );
                    $maturity = date('Y-m-d', $this->add_months_to_date($no_of_months,$record->start_date));*/
                    if ($record->maturity_date != $maturity) {
                        $updData = array("maturity_date" => $maturity, "date_upd" => date("Y-m-d"));
                        $this->db->where('id_scheme_account', $record->id_scheme_account);
                        $this->db->update("scheme_account", $updData);
                    }
                }
                if ($checkDues) {
                    if ($record->maturity_type != 2) {  // 1 - Flexible[Can pay installments and close], 2 - Fixed Maturity, 3 - Fixed Flexible[Increase maturity if has Default] 
                        $proceed = ($record->scheme_type == 3 ? ($record->paid_installments == 0 ? FALSE : TRUE) : ($record->paid_installments > 0 || $record->totalunpaid > 0));
                        if ($proceed) {
                            if ($record->currentmonthpaycount == 0) {  // current month not paid (allowed pending due + current due)
                                if ($record->allow_unpaid == 1) {
                                    if ($record->allow_unpaid_months > 0 && ($record->total_installments - $record->paid_installments) >= $record->allow_unpaid_months && $record->totalunpaid > 0) {
                                        if (($record->total_installments - $record->paid_installments) == $record->allow_unpaid_months) {
                                            $allowed_due = ($record->totalunpaid < $record->allow_unpaid_months ? $record->totalunpaid : $record->allow_unpaid_months);
                                            $due_type = 'PD'; //  pending
                                        } else {
                                            $allowed_due = ($record->totalunpaid < $record->allow_unpaid_months ? $record->totalunpaid : $record->allow_unpaid_months) + 1;
                                            $due_type = 'PN'; // normal and pending
                                        }
                                    } else {
                                        $allowed_due = 1;
                                        $due_type = 'ND'; // normal due
                                    }
                                } else {
                                    // current month not paid (allowed advance due + current due)
                                    if ($record->allow_advance == 1) { // check allow advance
                                        if ($record->advance_months > 0 && $record->advance_months <= ($record->total_installments - $record->paid_installments)) {
                                            if (($record->total_installments - $record->paid_installments) == $record->advance_months) {
                                                $allowed_due = $record->advance_months;
                                                $due_type = 'AN'; // advance and normal
                                            } else {
                                                $allowed_due = $record->advance_months + 1;
                                                $due_type = 'AN'; // advance and normal
                                            }
                                        } else {
                                            $allowed_due = 1;
                                            $due_type = 'ND'; // normal due
                                        }
                                    } else {
                                        $allowed_due = 1;
                                        $due_type = 'ND'; // normal due
                                    }
                                }
                            } else { 	//current month paid
                                if ($record->free_payment == 1 && $record->allowSecondPay == 1 && $record->paid_installments == 1) {
                                    $allowed_due = 1;
                                    $due_type = 'AD'; // adv due
                                    $allowSecondPay = TRUE;
                                } else {
                                    if ($record->allow_unpaid == 1 && $record->allow_unpaid_months > 0 && $record->totalunpaid > 0 && ($record->currentmonthpaycount - 1) < $record->allow_unpaid_months) {
                                        // can pay previous pending dues if attempts available 
                                        if ($record->totalunpaid > $record->allow_unpaid_months) {
                                            $allowed_due = $record->allow_unpaid_months;
                                            $due_type = 'PD'; // pending due
                                        } else {
                                            $allowed_due = $record->totalunpaid;
                                            $due_type = 'PD'; // pending due
                                        }
                                    } else {  // check allow advance
                                        if (($record->allow_advance == 1) && ($record->advance_months > 0) && (($record->currentmonth_adv_paycount) < $record->advance_months)) {
                                            if (($record->advance_months + $record->paid_installments) <= $record->total_installments) {
                                                $allowed_due = ($record->advance_months - ($record->currentmonth_adv_paycount));
                                                $due_type = 'AD'; // advance due
                                            } else {
                                                $allowed_due = ($record->total_installments - $record->paid_installments);
                                                $due_type = 'AD'; // advance due
                                            }
                                        } else { // have to check
                                            $allowed_due = 0;
                                            $due_type = ''; // normal due
                                        }
                                    }
                                }
                            }
                        } else {  // check allow advance and add due with currect month (allowed advance due + current due)
                            if ($record->scheme_type == 3) { // Donot allow advance/pending on first due payment [Flexible scheme]
                                $allowed_due = 1;
                                $due_type = 'ND'; // normal due
                            } else {
                                if ($record->allow_advance == 1) { // check allow advance
                                    if ($record->advance_months > 0 && $record->advance_months <= ($record->total_installments - $record->paid_installments)) {
                                        if (($record->total_installments - $record->paid_installments) == $record->advance_months) {
                                            $allowed_due = $record->advance_months;
                                            $due_type = 'AN'; // advance and normal
                                        } else {
                                            $allowed_due = $record->advance_months + 1;
                                            $due_type = 'AN'; // advance and normal
                                        }
                                    } else {
                                        $allowed_due = 1;
                                        $due_type = 'ND'; // normal due
                                    }
                                } else {
                                    $allowed_due = 1;
                                    $due_type = 'ND'; // normal due
                                }
                            }
                        }
                    } else { // Fixed Maturity Date
                        if ($record->maturity_date != NULL && $record->maturity_date != '') // Jewelone
                        {
                            $due = $record->tot_ins - $record->months_from_startdate; // months_from_startdate -> No. of months from start date
                            if ($record->advance_months > $due) {
                                $allow_due = $record->advance_months;
                            } else {
                                $allow_due = $due;
                            }
                        }
                        if (!empty($record->maturity_days) && $record->allow_unpaid == 0) // ** Advance Only. No Pending allowed. ** //
                        {
                            if ($record->advance_months > 0) {
                                if ($record->current_paid_installments == 0)  // Current month not Paid (Current+Advance)
                                {
                                    $allowed_due = $record->total_installments - $record->current_pay_installemnt;
                                    $due_type = 'AN';
                                } else // Current month Paid (Advance)
                                {
                                    $allowed_due = $record->total_installments - ($record->current_pay_installemnt + $record->current_paid_installments);
                                    $due_type = 'AD';
                                }
                            }
                        }
                    }
                }
                $dates = date('d-m-Y');
                //golden promise scheme    
                if ($record->set_as_min_from > 0 && $record->set_as_max_from > 0 && $record->paid_installments > 0) {
                    if ($record->dues_count > 0 && $record->paid_installments >= $record->set_as_min_from && $record->paid_installments <= $record->set_as_max_from) {
                        $res = $this->db->query("select p.payment_amount,sa.id_scheme_account from payment p 
    			                 left join scheme_account sa on sa.id_scheme_account = p.id_scheme_account
    			                 where p.payment_status=1 and p.id_scheme_account = '" . $record->id_scheme_account . "' order by id_payment asc limit 1");
                        $payamount = $res->row_array();
                        if ($payamount['payment_amount'] > 0) {
                            //$record->min_amount = $record->dues_count * $payamount['payment_amount'];
                            $record->max_amount = $record->dues_count * $payamount['payment_amount'];
                            $record->min_amount = $payamount['payment_amount'];
                            if ($record->currentmonthpaycount != 0) {
                                if ($record->paid_installments > 0 && $record->currentmonthpaycount == 1) {
                                    $record->current_total_amount = 0;
                                } else {
                                    $month_first_day = date('Y-m-01');
                                    $res1 = $this->db->query("select SUM(p.payment_amount) as payment_amount,sa.id_scheme_account from payment p 
        			                        left join scheme_account sa on sa.id_scheme_account = p.id_scheme_account
        			                        where p.payment_status=1 and p.due_type != 'ND' and date(p.date_payment) between '" . $month_first_day . "' and '" . $record->pay_date . "' and p.id_scheme_account = '" . $record->id_scheme_account . "' ");
                                    //echo $this->db->last_query();exit;
                                    $amt = $res1->row_array();
                                    $record->current_total_amount = $amt['payment_amount'];
                                }
                            } else {
                                $record->min_amount = $record->min_amount;
                                $record->max_amount = $record->dues_count * $payamount['payment_amount'];
                                $record->current_total_amount = 0;
                            }
                        } else {
                            $record->min_amount = $record->min_amount;
                            $record->max_amount = $record->max_amount;
                        }
                    }
                }
                // Allow Pay
                if ($record->scheme_type == 3) {
                    if ($record->one_time_premium == 0) {
                        $allow_pay = ($record->disable_payment != 1 && $record->payment_status != 2 && $record->paid_installments <= $record->total_installments ? ($record->flexible_sch_type == 3 || $record->flexible_sch_type == 4 ? ($record->current_total_weight >= $record->max_weight || $record->current_chances_used >= $record->max_chance ? ($record->paid_installments < $record->total_installments ? ($record->allow_unpaid == 1 && $record->totalunpaid > 0 && ($record->currentmonthpaycount - 1) < $record->allow_unpaid_months ? 'Y' : ($record->allow_advance == 1 && $record->advance_months > 0 && ($record->currentmonthpaycount - 1) < $record->advance_months ? 'Y' : ($record->currentmonthpaycount == 0 ? 'Y' : 'N'))) : 'N') : 'Y') : ($record->current_total_amount >= $record->max_amount || ($record->firstPayment_amt > 0 && $record->current_total_amount >= $record->firstPayment_amt) || ($record->current_chances_used >= $record->max_chance && $record->max_chance > 0) ? ($record->paid_installments < $record->total_installments ? ($record->allow_unpaid == 1 && $record->totalunpaid > 0 && ($record->currentmonthpaycount - 1) < $record->allow_unpaid_months ? 'Y' : ($record->allow_advance == 1 && $record->advance_months > 0 && ($record->currentmonthpaycount - 1) < $record->advance_months ? 'Y' : ($record->currentmonthpaycount == 0 || $record->current_chances_used < $record->max_chance ? 'Y' : 'N'))) : 'N') : 'Y')) : 'N');
                        // old 1 //$allow_pay  = ($record->disable_payment != 1 && $record->payment_status !=2  && $record->paid_installments <= $record->total_installments ?   ($record->flexible_sch_type == 3 || $record->flexible_sch_type == 4 ? ($record->current_total_weight >= $record->max_weight || $record->current_chances_used >= $record->max_chance ?($record->paid_installments <  $record->total_installments ?($record->allow_unpaid == 1  && $record->totalunpaid >0 && ($record->currentmonthpaycount-1) < $record->allow_unpaid_months ?'Y':($record->allow_advance == 1 && $record->advance_months > 0 && ($record->currentmonthpaycount -1) < $record->advance_months ?'Y':($record->currentmonthpaycount == 0 ? 'Y': 'N'))):'N'):'Y') : ($record->current_total_amount >= $record->max_amount || ($record->firstPayment_amt >0 && $record->current_total_amount >= $record->firstPayment_amt) || $record->current_chances_used >= $record->max_chance ?($record->paid_installments <  $record->total_installments ?($record->allow_unpaid == 1  && $record->totalunpaid >0 && ($record->currentmonthpaycount-1) < $record->allow_unpaid_months ?'Y':($record->allow_advance == 1 && $record->advance_months > 0 && ($record->currentmonthpaycount -1) < $record->advance_months ?'Y':($record->currentmonthpaycount == 0 ? 'Y': 'N'))):'N'):'Y')):'N');
                    } else {
                        $allow_pay = ($record->disable_payment != 1 && $record->payment_status != 2 && $record->paid_installments == 0 && $record->is_enquiry == 0 ? ($record->flexible_sch_type == 1 || $record->flexible_sch_type == 4 || $record->flexible_sch_type == 5 ? ($record->current_total_amount >= $record->max_amount && $record->current_chances_used >= $record->max_chance ? 'N' : 'Y') : 'N') : 'N');
                    }
                } else {
                    $allow_pay = ($record->disable_payment != 1 && ($record->payment_status != 2) ? ($record->cur_month_pdc < 1 ? ($record->paid_installments <= $record->total_installments ? ($record->is_flexible_wgt ? ($record->current_total_weight >= $record->max_weight || $record->current_chances_used >= $record->max_chance ? 'N' : 'Y') : ($record->paid_installments < $record->total_installments ? ($record->allow_unpaid == 1 && $record->totalunpaid > 0 && ($record->currentmonthpaycount - 1) < $record->allow_unpaid_months ? 'Y' : ($record->allow_advance == 1 && $record->advance_months > 0 && ($record->currentmonthpaycount - 1) < $record->advance_months ? 'Y' : ($record->currentmonthpaycount == 0 ? 'Y' : 'N'))) : 'N')) : 'N') : 'N') : 'N');
                }
                //echo $record->disable_payment;exit;
                // Check subscription settings if payment allowed
                if ($allow_pay == 'Y' && $record->auto_debit_status > 0) {
                    // auto_debit_allow_app_pay =>	1 - Allow app payment 
                    if ($record->auto_debit_allow_app_pay == 0) {  //0 - Block app payment
                        $allow_pay = 'N';
                    } else if ($record->auto_debit_allow_app_pay == 2) {//2-Allow app payment when subscription status is not ACTIVE
                        if ($record->auto_debit_status == 3) { //Active
                            $allow_pay = 'N';
                        }
                    }
                }
                $rate_field = NULL;
                $metalRate = NULL;
                $sql = $this->db->query("SELECT rate_field,market_rate_field FROM `ret_metal_purity_rate` where id_metal=" . $record->id_metal . " and id_purity=1");
                if ($sql->num_rows() == 1) {
                    $metalfields = $sql->row_array();
                    $rate_field = $metalfields['rate_field'];
                    $metalRate = ($rate_field == null ? null : $metal_rates[$rate_field]);
                }
                $last_transaction = $this->getLastTransaction($record->id_scheme_account);
                /*if($record->ref_no == null || $record->ref_no == ''){
                    $allow_pay = 'N';
                }*/
                $show = $record->one_time_premium == 1 ? ($allow_pay == 'N' ? false : true) : true;
                $allowExtraPayinWeb = ($record->allow_unpaid_in == 0 || $record->allow_unpaid_in == 4 || $record->allow_advance_in == 0 || $record->allow_advance_in == 4) ? TRUE : FALSE;
                $maxamount = round(($record->scheme_type == 3 && $record->max_amount != 0 && $record->max_amount != '' ? (($record->firstPayamt_maxpayable == 1 || $record->firstPayamt_as_payamt == 1) && ($record->paid_installments > 0) ? $record->firstPayment_amt : ($record->max_amount - str_replace(',', '', $record->current_total_amount))) : ($record->scheme_type == 3 && $record->max_weight != 0 && $record->max_weight != '' ? (($record->firstpayment_wgt > 0 ? $record->firstpayment_wgt : ($record->max_weight - $record->current_total_weight)) * $metalRate) : $record->max_amount)));
                if ($maxamount == 0 && $record->scheme_type == 3 && $record->set_as_min_from > 0 && $record->set_as_min_from > 0) {
                    $allow_pay = 'N';
                }
                if ($show) {
                    $schemeAcc[] = array(
                        'rate_field' => $rate_field,
                        'id_metal' => $record->id_metal,
                        'gst' => $record->gst,
                        'metal_wgt_decimal' => $record->metal_wgt_decimal,
                        'metal_wgt_roundoff' => $record->metal_wgt_roundoff,
                        'gst_type' => $record->gst_type,
                        'avg_payable' => $record->avg_payable,
                        'totalunpaid' => $record->totalunpaid,
                        'missed_ins' => $record->missed_ins,
                        'kyc_status' => $record->kyc_status,     //With out KYC user should not pay any due submit button Redirect to Kyc Form//hh
                        'totalunpaid_1' => $record->totalunpaid_1,
                        'scheme_group_code' => $record->scheme_group_code,
                        'flexible_sch_type' => $record->flexible_sch_type,
                        'has_lucky_draw' => $record->has_lucky_draw,
                        'id_scheme_account' => $record->id_scheme_account,
                        'allow_wallet' => $record->allow_wallet,
                        'useWalletForChit' => $record->useWalletForChit,
                        'branch_name' => $record->branch_name,
                        'id_branch' => $record->id_branch,
                        'email' => $record->email,
                        'city' => $record->city,
                        'state' => $record->state,
                        'country' => $record->country,
                        'phone' => $record->mobile,
                        'address1' => $record->address1,
                        'address2' => $record->address2,
                        'zipcode' => $record->pincode,
                        'firstname' => $record->firstname,
                        'lastname' => $record->lastname,
                        'chit_number' => $record->chit_number,
                        'account_name' => $record->account_name,
                        'previous_paid' => $record->previous_paid,
                        'mobile' => $record->mobile,
                        'firstPayment_amt' => $record->firstPayment_amt,
                        'firstpayment_wgt' => $record->firstpayment_wgt,
                        'payable' => ($record->get_amt_in_schjoin == 1 && $record->firstPayamt_as_payamt == 1 ? $record->firstPayment_amt : $record->payable),
                        'code' => $record->code,
                        'scheme_type' => $record->scheme_type,
                        'scheme_name' => $record->scheme_name,
                        'disable_pay_reason' => $record->disable_pay_reason,
                        'disable_payment' => $record->disable_payment,
                        'paid_installments' => $record->paid_installments,
                        'total_installments' => $record->total_installments,
                        'total_paid_amount' => $record->total_paid_amount,
                        'total_paid_weight' => $record->total_paid_weight,
                        'current_total_amount' => $record->current_total_amount,
                        'current_paid_installments' => $record->current_paid_installments,
                        'current_chances_used' => $record->current_chances_used,
                        'last_paid_date' => $record->last_paid_date,
                        'current_date' => $dates,
                        'pay_duration' => $record->pay_duration,
                        'min_chance' => $record->min_chance,
                        'max_chance' => $record->max_chance,
                        'metal_rate' => $metalRate,
                        'firstPayamt_as_payamt' => $record->firstPayamt_as_payamt,
                        'get_amt_in_schjoin' => $record->get_amt_in_schjoin,
                        /*'min_amount'            => round(($record->scheme_type==3 && $record->min_amount!=0 && $record->min_amount!='' ? (($record->firstPayamt_maxpayable==1 ||$record->firstPayamt_as_payamt==1 ) && $record->firstPayment_amt > 0 ? $record->firstPayment_amt : ($record->max_amount - str_replace(',', '',$record->current_total_amount) > $record->min_amount ? $record->min_amount : $record->max_amount - str_replace(',', '',$record->current_total_amount)) ):($record->min_weight!=0 && $record->min_weight!=''? (($record->firstpayment_wgt > 0 ? $record->firstpayment_wgt : $record->min_weight)*$metal_rates['goldrate_22ct']) : $record->min_amount))), 
                        'max_amount'            => round(($record->scheme_type==3 && $record->max_amount!=0 && $record->max_amount!='' ? (($record->firstPayamt_maxpayable==1 ||$record->firstPayamt_as_payamt==1 )&&($record->paid_installments>0 ||$record->get_amt_in_schjoin==1) ?  $record->firstPayment_amt:($record->max_amount - str_replace(',', '',$record->current_total_amount))): ($record->scheme_type==3 && $record->max_weight!=0 && $record->max_weight!=''? (($record->max_weight - $record->current_total_weight)*$metal_rates['goldrate_22ct']) : $record->max_amount))),*/
                        'min_amount' => round(($record->scheme_type == 3 && $record->min_amount != 0 && $record->min_amount != '' ? (($record->firstPayamt_maxpayable == 1 || $record->firstPayamt_as_payamt == 1) && $record->firstPayment_amt > 0 ? $record->firstPayment_amt : ($record->max_amount - str_replace(',', '', $record->current_total_amount) > $record->min_amount ? $record->min_amount : $record->max_amount - str_replace(',', '', $record->current_total_amount))) : ($record->scheme_type == 3 && $record->min_weight != 0 && $record->min_weight != '' ? (($record->min_weight) * $metalRate) : $record->min_amount))),
                        'max_amount' => round(($record->scheme_type == 3 && $record->max_amount != 0 && $record->max_amount != '' ? (($record->firstPayamt_maxpayable == 1 || $record->firstPayamt_as_payamt == 1) && ($record->paid_installments > 0) ? $record->firstPayment_amt : ($record->max_amount - str_replace(',', '', $record->current_total_amount))) : ($record->scheme_type == 3 && $record->max_weight != 0 && $record->max_weight != '' ? (($record->firstpayment_wgt > 0 ? $record->firstpayment_wgt : ($record->max_weight - $record->current_total_weight)) * $metalRate) : $record->max_amount))),
                        'maxamount' => $maxamount,
                        //'maxamount'             => round(($record->scheme_type==3 && $record->max_amount!=0 && $record->max_amount!='' ? (($record->firstPayamt_maxpayable==1 ||$record->firstPayamt_as_payamt==1 )&&($record->paid_installments>0 ) ?  $record->firstPayment_amt:($record->max_amount - str_replace(',', '',$record->current_total_amount))): ($record->scheme_type==3 && $record->max_weight!=0 && $record->max_weight!=''? (($record->firstpayment_wgt > 0 ? $record->firstpayment_wgt : ($record->max_weight - $record->current_total_weight) )*$metalRate) : $record->max_amount))),
                        'current_chances_pay' => $record->current_chances_pay,
                        'currency_name' => $record->currency_name,
                        'currency_symbol' => $record->currency_symbol,
                        'is_pan_required' => $record->is_pan_required,
                        'last_transaction' => $last_transaction,
                        'isPaymentExist' => $this->isPaymentExist($record->id_scheme_account),
                        'max_weight' => $record->max_weight,
                        'current_total_weight' => $record->current_total_weight,
                        'previous_amount_eligible' => $record->previous_amount_eligible,
                        'cur_month_pdc' => $record->cur_month_pdc,
                        'is_flexible_wgt' => $record->is_flexible_wgt,
                        'allow_pay' => $allow_pay,
                        'allowed_dues' => ($record->is_flexible_wgt == 1 ? 1 : ($allowExtraPayinWeb ? $allowed_due : 1)),
                        'set_as_min_from' => $record->set_as_min_from,
                        'set_as_max_from' => $record->set_as_max_from,
                        //'allowed_dues'  			=>($record->is_flexible_wgt == 1 ? 1:$allowed_due),
                        //'allowed_dues'          => ($record->maturity_date==NULL ? $allowed_due : $allow_due) ,
                        'due_type' => ($record->is_flexible_wgt == 1 ? 'ND' : $due_type),
                        'is_amt_min' => $current_amt_min,
                        'pdc_payments' => ($record->cur_month_pdc > 0 ? $this->get_postdated_payment($record->id_scheme_account) : NULL),
                        'auto_debit_status_msg' => ($record->auto_debit_plan_type == 0 ? '-' : ($record->auto_debit_status == 1 ? 'Initialized' : ($record->auto_debit_status == 2 ? 'Bank Verification Pending' : ($record->auto_debit_status == 3 ? 'Subscribed' : ($record->auto_debit_status == 4 ? 'On Hold' : ($record->auto_debit_status == 5 ? 'Cancelled' : ($record->auto_debit_status == 5 ? 'Completed' : 'Not Subscribed'))))))),
                    );
                    /*if($record->firstPayment_amt > 0  ){
                        echo "<pre>";print_r($record); 
                        $minimum = (($record->firstPayamt_maxpayable==1 ||$record->firstPayamt_as_payamt==1 ) && $record->firstPayment_amt > 0 ? $record->firstPayment_amt : ($record->max_amount - str_replace(',', '',$record->current_total_amount) > $record->min_amount ? $record->min_amount : $record->max_amount - str_replace(',', '',$record->current_total_amount)) );
                        echo $minimum."<br/>";
                    }*/
                    //echo "<pre>"	;print_r($schemeAcc);exit; 
                }
            }
            return array('chits' => $schemeAcc);
        }
    }
    function isPaid($paidByMonth, $Ym)
    {
        foreach ($paidByMonth as $p) {
            if ($Ym == $p['due_year'] . "-" . str_pad($p['due_month'], 2, '0', STR_PAD_LEFT)) {
                return true;
            }
        }
        return false;
    }
    function add_months_to_date($no_of_months, $date)
    {
        $year_month = Date("Y-m", strtotime($date));
        $year_month_incremented = Date("Y-m", strtotime($year_month . " +" . $no_of_months . " Month "));
        $month_end_dt = strtotime('last day of this month', strtotime($year_month_incremented));
        return $month_end_dt;
    }
    function get_postdated_payment($id_scheme_account)
    {
        $sql = "Select
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
			       And pp.id_scheme_account='" . $id_scheme_account . "'";
        return $this->db->query($sql)->row_array();
    }
    function get_paymentContent($id_scheme_account)
    {
        $schemeAcc = array();
        $sql = "Select s.allow_unpaid_in,s.allow_advance_in,s.set_as_min_from,s.set_as_max_from,s.get_amt_in_schjoin,s.rate_fix_by,s.rate_select,
					s.id_metal,s.one_time_premium,s.is_enquiry,reference_no,s.sync_scheme_code,sa.scheme_acc_number,
					sg.group_code as scheme_group_code, UNIX_TIMESTAMP(Date_Format(sg.start_date,'%Y-%m-%d')) as group_start_date,  UNIX_TIMESTAMP(Date_Format(sg.end_date,'%Y-%m-%d')) as  group_end_date, s.is_lucky_draw,cs.has_lucky_draw,
		            maturity_type,date_format(CURRENT_DATE(),'%m') as cur_month,if((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) > s.total_installments, 
                    (s.total_installments - COUNT(payment_amount)), 
                    ifnull((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) - SUM(p.no_of_dues),if((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) > s.total_installments,s.total_installments,(PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m')))))) 
                    as missed_ins, sa.avg_payable,s.avg_calc_ins,
            	    PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m')) as months_from_startdate,PERIOD_DIFF(Date_Format(sa.maturity_date,'%Y%m'),Date_Format(curdate(),'%Y%m')) as tot_ins,
 					s.flx_denomintion,s.gst_type,s.gst,s.amount,cs.branchWiseLogin,s.discount_type,s.discount_installment,s.discount as discount_set,sa.maturity_date as maturity_date,
					s.setlmnt_type,IF(s.discount=1,s.firstPayDisc_value,0.00) as discount,s.firstPayDisc_by,s.firstPayDisc,s.charge_head,s.charge_type,s.charge,sa.is_new,s.flexible_sch_type,
					sa.id_scheme_account,s.id_scheme,s.firstPayamt_maxpayable,sa.firstPayment_amt,sa.firstpayment_wgt,sa.is_registered,firstPayamt_as_payamt,s.get_amt_in_schjoin,
					c.id_customer,c.id_branch as cus_reg_branch,sa.id_branch as sch_join_branch,
					IF(sa.scheme_acc_number !='',CONCAT(s.code,' ',sa.scheme_acc_number),'') as chit_number,
					IFNULL(sa.account_name,if(c.lastname is null,c.firstname,concat(c.firstname,' ',c.lastname))) as account_name,s.free_payment,
					s.scheme_name,s.min_amount,s.max_amount,
					s.scheme_type,s.wgt_convert,					
					s.code,s.min_amount,s.max_amount,s.pay_duration,
					IFNULL(s.min_chance,0) as min_chance,
					IFNULL(s.max_chance,0) as max_chance,
					Format(IFNULL(s.max_weight,0),3) as max_weight,
					Format(IFNULL(s.min_weight,0),3) as min_weight,
					Date_Format(sa.start_date,'%d-%m-%Y') as start_date,
					IF(s.scheme_type=1,s.max_weight,s.amount) as payable,s.total_installments,IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),1,0) as  previous_paid,					
					IFNULL(IF(sa.is_opening=1,IFNULL(sa.paid_installments,0)+ IFNULL(if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues)),0), if(s.scheme_type = 1 and s.min_weight != s.max_weight or (s.scheme_type=3 and s.payment_chances=1) , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues))) ,0)as paid_installments,  
					IFNULL(IF(sa.is_opening=1,IFNULL(balance_amount,0)+IFNULL(SUM(p.payment_amount * p.no_of_dues),0),IFNULL(SUM(p.payment_amount * p.no_of_dues),0)) ,0)as total_paid_amount,
                    IFNULL(IF(sa.is_opening=1,IFNULL(balance_weight,0)+IFNULL(SUM(p.metal_weight),0),IFNULL(SUM(p.metal_weight),0)),0.000)
                     as total_paid_weight,
                      if((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) > s.total_installments,   (s.total_installments - if(sa.is_opening = 1,(count(DISTINCT((Date_Format(p.date_payment,'%Y%m'))))+sa.paid_installments),count(DISTINCT((Date_Format(p.date_payment,'%Y%m')))))),ifnull(((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m')))+1) - IF(sa.is_opening=1,IFNULL(sa.paid_installments,0)+ IFNULL(if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues)),0), if(s.scheme_type = 1 and s.min_weight != s.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues))),if((PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m'))) > s.total_installments,s.total_installments,(PERIOD_DIFF(Date_Format(curdate(),'%Y%m'), Date_Format(sa.start_date,'%Y%m')))))) as totalunpaid,  
                    IFNULL(if(Date_Format(max(p.date_payment),'%Y%m') = Date_Format(curdate(),'%Y%m'), (select SUM(ip.no_of_dues) from payment ip where Date_Format(ip.date_payment,'%Y%m') = Date_Format(curdate(),'%Y%m') and sa.id_scheme_account = p.id_scheme_account),IF(sa.is_opening=1, if(Date_Format(sa.last_paid_date,'%Y%m') = Date_Format(curdate(),'%Y%m'), 1,0),0)),0) as currentmonthpaycount,
                      (select SUM(pay.no_of_dues) from payment pay where pay.id_scheme_account= sa.id_scheme_account and pay.due_type='AD' and (pay.payment_status=1 or pay.payment_status=2)) as currentmonth_adv_paycount,
                      (select SUM(pay.no_of_dues) from payment pay where pay.id_scheme_account= sa.id_scheme_account and pay.due_type='PD' and (pay.payment_status=1 or pay.payment_status=2)) as currentmonth_pend_paycount,
                    IF(s.scheme_type =1 and s.max_weight!=s.min_weight,true,false) as is_flexible_wgt,p.payment_status,
					if(scheme_type=3,IFNULL(cp.total_amount,0),Format(IFNULL(cp.total_amount,0),2)) as current_total_amount,
					Format(IFNULL(cp.total_weight,0) + IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),(sa.last_paid_weight),0) ,3) as  current_total_weight,
					IFNULL(cp.paid_installment,0)       as  current_paid_installments,
					IFNULL(cp.chances,0) + IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),(sa.last_paid_chances),0) as  current_chances_used,
					if(s.scheme_type=3 && s.pay_duration=0 ,IFNULL(sp.chance,0) + IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),(sa.last_paid_chances),0),IFNULL(cp.chances,0) + IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),(sa.last_paid_chances),0)) as  current_chances_pay,
					(SELECT PERIOD_DIFF(Date_Format(CURRENT_DATE(),'%Y%m'),Date_Format(sa.start_date,'%Y%m')) FROM payment p 
                    left join scheme_account sa on sa.id_scheme_account=p.id_scheme_account
                    WHERE p.id_scheme_account='$id_scheme_account' order by p.id_payment DESC limit 1) as current_pay_installemnt,
					s.is_pan_required,
					IF(sa.is_opening = 1 and s.scheme_type = 0,
					IF(Date_Format(Current_Date(),'%Y%m')=Date_Format(sa.last_paid_date,'%Y%m'),false,true),
					true) AS previous_amount_eligible,
					count(pp.id_scheme_account) as cur_month_pdc,
					IFNULL(Date_Format(max(p.date_payment),'%d-%m-%Y'),IFNULL(IF(sa.is_opening=1,Date_Format(sa.last_paid_date,'%d-%m-%Y'),'')  ,0))                 as last_paid_date,
					IFNULL(PERIOD_DIFF(Date_Format(curdate(),'%Y%m'),Date_Format(max(p.date_payment),'%Y%m')),IF(sa.is_opening=1,PERIOD_DIFF(Date_Format(curdate(),'%Y%m'),Date_Format(sa.last_paid_date,'%Y%m')),0)) as last_paid_duration,
					sa.disable_payment,s.disable_sch_payment,
					s.allow_unpaid,
					if(s.allow_unpaid=1,s.unpaid_weight_limit,0) as unpaid_weight_limit,
    				if(s.allow_unpaid=1,s.unpaid_months,0) as allow_unpaid_months,
    				s.allow_advance,
    				if(s.allow_advance=1,s.advance_months,0) as advance_months,
					if(s.allow_advance=1,s.advance_weight_limit,0) as advance_weight_limit,
					s.allow_preclose,cs.gst_setting,
					if(s.allow_preclose=1,s.preclose_months,0) as preclose_months,
					if(s.allow_preclose=1,s.preclose_benefits,0) as preclose_benefits,
					cs.currency_symbol,sa.id_branch as ac_branch,sa.id_branch,
					cs.metal_wgt_decimal, cs.metal_wgt_roundoff
				From scheme_account sa
				join chit_settings cs
				Left Join scheme s On (sa.id_scheme=s.id_scheme)
				Left Join scheme_group sg On (sa.id_scheme=sg.id_scheme and sa.group_code = sg.group_code)
				Left Join payment p On (sa.id_scheme_account=p.id_scheme_account and (p.payment_status=2 or p.payment_status=1))
				Left Join customer c On (sa.id_customer=c.id_customer and c.active=1)
				Left Join
					(	Select
						  sa.id_scheme_account,
						  COUNT(Date_Format(p.date_payment,'%Y%m')) as paid_installment,
						  COUNT(Date_Format(p.date_payment,'%Y%m')) as chances,
						  SUM(p.payment_amount) as total_amount,
						  SUM(p.metal_weight) as total_weight
						From payment p
						Left Join scheme_account sa on(p.id_scheme_account=sa.id_scheme_account and sa.active=1 and sa.is_closed=0)
						Where  (p.payment_status=2 or p.payment_status=1) and  Date_Format(Current_Date(),'%Y%m')=Date_Format(p.date_payment,'%Y%m')
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
				Where sa.active=1 and sa.is_closed = 0 and sa.id_scheme_account='$id_scheme_account'
				Group By sa.id_scheme_account";
        $records = $this->db->query($sql);
        if ($records->num_rows() > 0) {
            $record = $records->row();
            $current_installments = ($record->current_paid_installments == 0 ? $record->paid_installments + 1 : $record->paid_installments);
            if (($record->get_amt_in_schjoin == 1 && $record->firstPayment_amt > 0) && ($record->set_as_min_from > 0 || $record->set_as_max_from > 0)) {
                if ($record->paid_installments > 1 && $record->firstPayment_amt != 0) {
                    if ($record->current_total_amount < $record->firstPayment_amt) {
                        $current_amt_min = 'Y';
                    } else {
                        $current_amt_min = 'N';
                    }
                }
                if ($current_installments >= $record->set_as_min_from) {
                    if ($record->paid_installments == 0) {
                        $record->min_amount = $record->firstPayment_amt;
                    } else if ($record->paid_installments > 1 && $record->paid_installments <= $record->set_as_max_from) {
                        $record->min_amount = $record->min_amount;
                    }
                }
                if ($current_installments >= $record->set_as_max_from) {
                    $record->min_amount = $record->flx_denomintion;
                    $record->max_amount = $record->firstPayment_amt;
                }
            }
            // Calculate max payable [Applicable only for No advance, No pending enabled schemes]
            if ((($record->scheme_type == 1 && $record->is_flexible_wgt == 1) || $record->scheme_type == 3) && $record->avg_calc_ins > 0) {
                $current_installments = ($record->current_paid_installments == 0 ? $record->paid_installments + 1 : $record->paid_installments);
                // Current Installment == Average calc installment
                if (($current_installments == $record->avg_calc_ins || $record->avg_payable > 0) && $record->avg_calc_ins > 0) {
                    if ($record->avg_payable > 0) { // Already Average calculated, just set the value
                        if ($record->scheme_type == 1 && $record->is_flexible_wgt == 1) { // Weight - Flexible weight scheme
                            // Set max payable
                        } else if ($record->scheme_type == 3) {
                            if ($record->flexible_sch_type == 2) { // Flexible - Amount to weight [amount based]
                                // Set max payable
                                $record->max_amount = $record->avg_payable;
                            } elseif ($record->flexible_sch_type == 3) { // Flexible - Amount to weight [weight based]
                                $record->max_weight = $record->avg_payable;
                            } elseif ($record->flexible_sch_type == 1) { // Flexible - Amount to weight [weight based]
                                $record->max_amount = $record->avg_payable;
                            }
                        }
                    } else { // Calculate Average , set the value and updte it in schemme_account table
                        $paid_sql = $this->db->query("SELECT date(date_payment) as date_payment,sum(metal_weight) as paid_wgt,sum(payment_amount) as paid_amt FROM `payment` WHERE ( payment_status=1 or payment_status=2 ) and id_scheme_account=" . $record->id_scheme_account . " GROUP BY YEAR(date_payment), MONTH(date_payment)");
                        $paid_wgt = 0;
                        $paid_amt = 0;
                        $paidByMonth = $paid_sql->result_array();
                        foreach ($paidByMonth as $p) {
                            $paid_wgt = $paid_wgt + $p['paid_wgt'];
                            $paid_amt = $paid_amt + $p['paid_amt'];
                        }
                        if ($record->scheme_type == 1 && $record->is_flexible_wgt == 1) { // Weight - Flexible weight scheme
                            // Set max payable
                        } else if ($record->scheme_type == 3) {
                            if ($record->flexible_sch_type == 2) { // Flexible - Amount to weight [amount based]
                                // Set max payable
                                $avg_payable = $paid_amt / $record->avg_calc_ins;
                                $record->max_amount = $avg_payable;
                            } elseif ($record->flexible_sch_type == 3) { // Flexible - Amount to weight [weight based]
                                $avg_payable = number_format($paid_wgt / $record->avg_calc_ins, 3);
                                $record->max_weight = $avg_payable;
                            } elseif ($record->flexible_sch_type == 1) { // Flexible - Amount Based
                                $avg_payable = $paid_amt / $record->avg_calc_ins;
                                $record->max_amount = $avg_payable;
                            }
                        }
                        $updData = array("avg_payable" => $avg_payable, "date_upd" => date("Y-m-d"));
                        $this->db->where('id_scheme_account', $record->id_scheme_account);
                        $this->db->update("scheme_account", $updData);
                    }
                } else if ($current_installments > $record->avg_calc_ins) { // Current Installment > Average calc installment
                    if ($record->scheme_type == 1 && $record->is_flexible_wgt == 1) { // Weight - Flexible weight scheme
                        // Set max payable
                    } else if ($record->scheme_type == 3) {
                        if ($record->flexible_sch_type == 2) { // Flexible - Amount to weight [amount based]
                            // Set max payable
                        } elseif ($record->flexible_sch_type == 3) { // Flexible - Amount to weight [weight based]
                            $record->max_weight = $record->avg_payable;
                        }
                    }
                }
            }
            $allowed_due = 0;
            $due_type = '';
            $checkDues = TRUE;
            $allowSecondPay = FALSE;
            $metal_rates = $this->getMetalRate($record->sch_join_branch);//For branchwise rate
            if ($record->has_lucky_draw == 1) {
                if ($record->group_start_date == NULL && $record->paid_installments > 1) { // block 2nd payment if scheme_group_code is not updated 
                    $checkDues = FALSE;
                } else if ($record->group_start_date != NULL) { // block before start date and payment after end date 
                    if ($record->group_end_date >= time() && $record->group_start_date <= time()) {
                        $checkDues = TRUE;
                    } else {
                        $checkDues = FALSE;
                    }
                }
            }
            $allowed_due = 0;
            $due_type = '';
            $metal_rates = $this->getMetalRate($record->sch_join_branch);//For branchwise rate
            //$metal_rates=$this->getMetalRate($record->id_branch);//For branchwise rate hh
            if ($record->maturity_type != 2) {  // 1 - Flexible[Can pay installments and close], 2 - Fixed Maturity, 3 - Fixed Flexible[Increase maturity if has Default] 
                $proceed = ($record->scheme_type == 3 ? ($record->paid_installments == 0 ? FALSE : TRUE) : ($record->paid_installments > 0 || $record->totalunpaid > 0));
                if ($proceed) {
                    if ($record->currentmonthpaycount == 0) {  // current month not paid (allowed pending due + current due)
                        if ($record->allow_unpaid == 1) {
                            if ($record->allow_unpaid_months > 0 && ($record->total_installments - $record->paid_installments) >= $record->allow_unpaid_months && $record->totalunpaid > 0) {
                                if (($record->total_installments - $record->paid_installments) == $record->allow_unpaid_months) {
                                    $allowed_due = ($record->totalunpaid < $record->allow_unpaid_months ? $record->totalunpaid : $record->allow_unpaid_months);
                                    $due_type = 'PD'; //  pending
                                } else {
                                    $allowed_due = ($record->totalunpaid < $record->allow_unpaid_months ? $record->totalunpaid : $record->allow_unpaid_months) + 1;
                                    $due_type = 'PN'; // normal and pending
                                }
                            } else {
                                $allowed_due = 1;
                                $due_type = 'ND'; // normal due
                            }
                        } else {
                            // current month not paid (allowed advance due + current due)
                            if ($record->allow_advance == 1) { // check allow advance
                                if ($record->advance_months > 0 && $record->advance_months <= ($record->total_installments - $record->paid_installments)) {
                                    if (($record->total_installments - $record->paid_installments) == $record->advance_months) {
                                        $allowed_due = $record->advance_months;
                                        $due_type = 'AN'; // advance and normal
                                    } else {
                                        $allowed_due = $record->advance_months + 1;
                                        $due_type = 'AN'; // advance and normal
                                    }
                                } else {
                                    $allowed_due = 1;
                                    $due_type = 'ND'; // normal due
                                }
                            } else {
                                $allowed_due = 1;
                                $due_type = 'ND'; // normal due
                            }
                        }
                    } else { 	//current month paid
                        if ($record->free_payment == 1 && $record->allowSecondPay == 1 && $record->paid_installments == 1) {
                            $allowed_due = 1;
                            $due_type = 'AD'; // adv due
                            $allowSecondPay = TRUE;
                        } else {
                            if ($record->allow_unpaid == 1 && $record->allow_unpaid_months > 0 && $record->totalunpaid > 0 && ($record->currentmonthpaycount - 1) < $record->allow_unpaid_months) {
                                // can pay previous pending dues if attempts available 
                                if ($record->totalunpaid > $record->allow_unpaid_months) {
                                    $allowed_due = $record->allow_unpaid_months;
                                    $due_type = 'PD'; // pending due
                                } else {
                                    $allowed_due = $record->totalunpaid;
                                    $due_type = 'PD'; // pending due
                                }
                            } else {  // check allow advance
                                if (($record->allow_advance == 1) && ($record->advance_months > 0) && (($record->currentmonth_adv_paycount) < $record->advance_months)) {
                                    if (($record->advance_months + $record->paid_installments) <= $record->total_installments) {
                                        $allowed_due = ($record->advance_months - ($record->currentmonth_adv_paycount));
                                        $due_type = 'AD'; // advance due
                                    } else {
                                        $allowed_due = ($record->total_installments - $record->paid_installments);
                                        $due_type = 'AD'; // advance due
                                    }
                                } else { // have to check
                                    $allowed_due = 0;
                                    $due_type = ''; // normal due
                                }
                            }
                        }
                    }
                } else {  // check allow advance and add due with currect month (allowed advance due + current due)
                    if ($record->scheme_type == 3) { // Donot allow advance/pending on first due payment [Flexible scheme]
                        $allowed_due = 1;
                        $due_type = 'ND'; // normal due
                    } else {
                        if ($record->allow_advance == 1) { // check allow advance
                            if ($record->advance_months > 0 && $record->advance_months <= ($record->total_installments - $record->paid_installments)) {
                                if (($record->total_installments - $record->paid_installments) == $record->advance_months) {
                                    $allowed_due = $record->advance_months;
                                    $due_type = 'AN'; // advance and normal
                                } else {
                                    $allowed_due = $record->advance_months + 1;
                                    $due_type = 'AN'; // advance and normal
                                }
                            } else {
                                $allowed_due = 1;
                                $due_type = 'ND'; // normal due
                            }
                        } else {
                            $allowed_due = 1;
                            $due_type = 'ND'; // normal due
                        }
                    }
                }
            } else { // Fixed Maturity Date
                if ($record->maturity_date != NULL && $record->maturity_date != '') // Jewelone
                {
                    $due = $record->tot_ins - $record->months_from_startdate; // months_from_startdate -> No. of months from start date
                    if ($record->advance_months > $due) {
                        $allow_due = $record->advance_months;
                    } else {
                        $allow_due = $due;
                    }
                }
                if (!empty($record->maturity_days) && $record->allow_unpaid == 0) // ** Advance Only. No Pending allowed. ** //
                {
                    if ($record->advance_months > 0) {
                        if ($record->current_paid_installments == 0)  // Current month not Paid (Current+Advance)
                        {
                            $allowed_due = $record->total_installments - $record->current_pay_installemnt;
                            $due_type = 'AN';
                        } else // Current month Paid (Advance)
                        {
                            $allowed_due = $record->total_installments - ($record->current_pay_installemnt + $record->current_paid_installments);
                            $due_type = 'AD';
                        }
                    }
                }
            }
            // Allow Pay
            if ($record->scheme_type == 3) {
                if ($record->one_time_premium == 0) {
                    $allow_pay = ($record->disable_payment != 1 && $record->payment_status != 2 && $record->paid_installments <= $record->total_installments ? ($record->flexible_sch_type == 3 || $record->flexible_sch_type == 4 ? ($record->current_total_weight >= $record->max_weight || $record->current_chances_used >= $record->max_chance ? ($record->paid_installments < $record->total_installments ? ($record->allow_unpaid == 1 && $record->totalunpaid > 0 && ($record->currentmonthpaycount - 1) < $record->allow_unpaid_months ? 'Y' : ($record->allow_advance == 1 && $record->advance_months > 0 && ($record->currentmonthpaycount - 1) < $record->advance_months ? 'Y' : ($record->currentmonthpaycount == 0 ? 'Y' : 'N'))) : 'N') : 'Y') : ($record->current_total_amount >= $record->max_amount || ($record->firstPayment_amt > 0 && $record->current_total_amount >= $record->firstPayment_amt) || ($record->current_chances_used >= $record->max_chance && $record->max_chance > 0) ? ($record->paid_installments < $record->total_installments ? ($record->allow_unpaid == 1 && $record->totalunpaid > 0 && ($record->currentmonthpaycount - 1) < $record->allow_unpaid_months ? 'Y' : ($record->allow_advance == 1 && $record->advance_months > 0 && ($record->currentmonthpaycount - 1) < $record->advance_months ? 'Y' : ($record->currentmonthpaycount == 0 || $record->current_chances_used < $record->max_chance ? 'Y' : 'N'))) : 'N') : 'Y')) : 'N');
                    // old 1 //$allow_pay  = ($record->disable_payment != 1 && $record->payment_status !=2  && $record->paid_installments <= $record->total_installments ?   ($record->flexible_sch_type == 3 || $record->flexible_sch_type == 4 ? ($record->current_total_weight >= $record->max_weight || $record->current_chances_used >= $record->max_chance ?($record->paid_installments <  $record->total_installments ?($record->allow_unpaid == 1  && $record->totalunpaid >0 && ($record->currentmonthpaycount-1) < $record->allow_unpaid_months ?'Y':($record->allow_advance == 1 && $record->advance_months > 0 && ($record->currentmonthpaycount -1) < $record->advance_months ?'Y':($record->currentmonthpaycount == 0 ? 'Y': 'N'))):'N'):'Y') : ($record->current_total_amount >= $record->max_amount || ($record->firstPayment_amt >0 && $record->current_total_amount >= $record->firstPayment_amt) || $record->current_chances_used >= $record->max_chance ?($record->paid_installments <  $record->total_installments ?($record->allow_unpaid == 1  && $record->totalunpaid >0 && ($record->currentmonthpaycount-1) < $record->allow_unpaid_months ?'Y':($record->allow_advance == 1 && $record->advance_months > 0 && ($record->currentmonthpaycount -1) < $record->advance_months ?'Y':($record->currentmonthpaycount == 0 ? 'Y': 'N'))):'N'):'Y')):'N');
                } else {
                    $allow_pay = ($record->disable_payment != 1 && $record->payment_status != 2 && $record->paid_installments == 0 && $record->is_enquiry == 0 ? ($record->flexible_sch_type == 1 || $record->flexible_sch_type == 4 || $record->flexible_sch_type == 5 ? ($record->current_total_amount >= $record->max_amount || $record->current_chances_used >= $record->max_chance ? 'N' : 'Y') : 'N') : 'N');
                }
            } else {
                $allow_pay = ($record->disable_payment != 1 && ($record->payment_status != 2) ? ($record->cur_month_pdc < 1 ? ($record->paid_installments <= $record->total_installments ? ($record->is_flexible_wgt ? ($record->current_total_weight >= $record->max_weight || $record->current_chances_used >= $record->max_chance ? 'N' : 'Y') : ($record->paid_installments < $record->total_installments ? ($record->allow_unpaid == 1 && $record->totalunpaid > 0 && ($record->currentmonthpaycount - 1) < $record->allow_unpaid_months ? 'Y' : ($record->allow_advance == 1 && $record->advance_months > 0 && ($record->currentmonthpaycount - 1) < $record->advance_months ? 'Y' : ($record->currentmonthpaycount == 0 ? 'Y' : 'N'))) : 'N')) : 'N') : 'N') : 'N');
            }
            $rate_field = NULL;
            $metalRate = NULL;
            $sql = $this->db->query("SELECT rate_field,market_rate_field FROM `ret_metal_purity_rate` where id_metal=" . $record->id_metal . " and id_purity=1");
            if ($sql->num_rows() == 1) {
                $metalfields = $sql->row_array();
                $rate_field = $metalfields['rate_field'];
                $metalRate = ($rate_field == null ? null : $metal_rates[$rate_field]);
            }
            $allowExtraPayinWeb = ($record->allow_unpaid_in == 0 || $record->allow_unpaid_in == 4 || $record->allow_advance_in == 0 || $record->allow_advance_in == 4) ? TRUE : FALSE;
            $schemeAcc = array(
                'scheme_acc_number' => $record->scheme_acc_number,
                'sync_scheme_code' => $record->sync_scheme_code,
                'reference_no' => $record->reference_no,
                'one_time_premium' => $record->one_time_premium,
                'is_enquiry' => $record->is_enquiry,
                'rate_field' => $rate_field,
                'id_metal' => $record->id_metal,
                'ac_branch' => $record->ac_branch,
                'metal_wgt_decimal' => $record->metal_wgt_decimal,
                'metal_wgt_roundoff' => $record->metal_wgt_roundoff,
                'missed_ins' => $record->missed_ins,
                'current_pay_installemnt' => $record->current_pay_installemnt,
                'currentmonth_adv_paycount' => $record->currentmonth_adv_paycount,
                'advance_months' => $record->advance_months,
                'gst_type' => $record->gst_type,
                'flx_denomintion' => $record->flx_denomintion,
                'flexible_sch_type' => $record->flexible_sch_type,
                'gst_setting' => $record->gst_setting,
                'gst' => $record->gst,
                'totalunpaid' => $record->totalunpaid,
                'id_scheme_account' => $record->id_scheme_account,
                'discount_set' => $record->discount_set,
                'discount' => $record->discount,
                'discount_installment' => $record->discount_installment,
                'discount_type' => $record->discount_type,
                'firstPayamt_maxpayable' => $record->firstPayamt_maxpayable,
                'firstPayamt_as_payamt' => $record->firstPayamt_as_payamt,
                'firstPayment_amt' => $record->firstPayment_amt,
                'firstpayment_wgt' => $record->firstpayment_wgt,
                'get_amt_in_schjoin' => $record->get_amt_in_schjoin,
                'is_registered' => $record->is_registered,
                'id_branch' => $record->id_branch,
                'cus_reg_branch' => $record->cus_reg_branch,
                'branchWiseLogin' => $record->branchWiseLogin,
                'start_date' => $record->start_date,
                'min_amount' => round(($record->scheme_type == 3 && $record->min_amount != 0 && $record->min_amount != '' ? (($record->firstPayamt_maxpayable == 1 || $record->firstPayamt_as_payamt == 1) && $record->firstPayment_amt > 0 ? $record->firstPayment_amt : ($record->max_amount - str_replace(',', '', $record->current_total_amount) > $record->min_amount ? $record->min_amount : $record->max_amount - str_replace(',', '', $record->current_total_amount))) : ($record->min_weight != 0 && $record->min_weight != '' ? (($record->firstpayment_wgt > 0 ? $record->firstpayment_wgt : $record->min_weight) * $metalRate) : $record->min_amount))),
                'max_amount' => round(($record->scheme_type == 3 && $record->max_amount != 0 && $record->max_amount != '' ? (($record->firstPayamt_maxpayable == 1 || $record->firstPayamt_as_payamt == 1) && ($record->paid_installments > 0) ? $record->firstPayment_amt : ($record->max_amount - str_replace(',', '', $record->current_total_amount))) : ($record->scheme_type == 3 && $record->max_weight != 0 && $record->max_weight != '' ? (($record->firstpayment_wgt > 0 ? $record->firstpayment_wgt : ($record->max_weight - $record->current_total_weight)) * $metalRate) : $record->max_amount))),
                'max_chance' => $record->max_chance,
                'min_chance' => $record->min_chance,
                'metal_rate' => $metalRate,
                'current_chances_pay' => $record->current_chances_pay,
                'pay_duration' => $record->pay_duration,
                'chit_number' => $record->chit_number,
                'previous_paid' => $record->previous_paid,
                'account_name' => $record->account_name,
                'firstPayDisc' => $record->firstPayDisc,
                'firstPayDisc_by' => $record->firstPayDisc_by,
                'discount' => $record->discount,
                'payable' => ($record->firstPayamt_as_payamt == 1 && $record->scheme_type == 3 && $record->flexible_sch_type == 2 && ($record->paid_installments > 0 || $record->get_amt_in_schjoin == 1) ? $record->firstPayment_amt : $record->payable),
                'scheme_name' => $record->scheme_name,
                'code' => $record->code,
                'scheme_type' => $record->scheme_type,
                'setlmnt_type' => $record->setlmnt_type,
                'min_weight' => $record->min_weight,
                'max_weight' => $record->max_weight,
                'wgt_convert' => $record->wgt_convert,
                'total_installments' => $record->total_installments,
                'paid_installments' => $record->paid_installments,
                'total_paid_amount' => $record->total_paid_amount,
                'total_paid_weight' => $record->total_paid_weight,
                'current_total_amount' => $record->current_total_amount,
                'current_paid_installments' => $record->current_paid_installments,
                'current_chances_used' => $record->current_chances_used,
                'current_total_weight' => $record->current_total_weight,
                'currency_symbol' => $record->currency_symbol,
                'last_paid_date' => $record->last_paid_date,
                'last_paid_duration' => $record->last_paid_duration,
                'is_pan_required' => $record->is_pan_required,
                'last_transaction' => $this->getLastTransaction($record->id_scheme_account),
                'isPaymentExist' => $this->isPaymentExist($record->id_scheme_account),
                'previous_amount_eligible' => $record->previous_amount_eligible,
                'cur_month_pdc' => $record->cur_month_pdc,
                'charge_head' => $record->charge_head,
                'charge_type' => $record->charge_type,
                'charge' => $record->charge,
                'allow_pay' => $allow_pay,
                'set_as_min_from' => $record->set_as_min_from,
                'set_as_max_from' => $record->set_as_max_from,
                'rate_fix_by' => $record->rate_fix_by,
                'rate_select' => $record->rate_select,
                //'allowed_dues'  			=> ($record->is_flexible_wgt == 1 ? 1:$allowed_due),
                'allowed_dues' => ($record->maturity_date == NULL ? ($allowExtraPayinWeb ? $allowed_due : 1) : $allow_due),
                //'allowed_dues'  			=>($record->maturity_date==NULL ?$allowed_due : $allow_due),
                'due_type' => ($record->is_flexible_wgt == 1 ? 'ND' : $due_type),
                'is_amt_min' => $current_amt_min,
                'allowed_weight' => ($record->paid_installments > 0 ? $record->unpaid_weight_limit : $record->advance_weight_limit),
                'pdc_payments' => ($record->cur_month_pdc > 0 ? $this->get_postdated_payment($record->id_scheme_account) : NULL),
                'allowPayDisc' => ($record->is_new == 'Y' ? ($record->scheme_type == 0 ? ($record->discount_set == 1 ? 1 : 0) : ($record->current_chances_used == 0 && $record->discount_set == 1 ? 1 : 0)) : 0),
                //	'allowPayDisc'     => ($record->is_new=='Y'?($record->scheme_type==0?($record->discount_type==0 ?1 :($record->paid_installments==0&&discount_installment==1?1:($record->discount_installment==($record->paid_installments+1) ?1 :0))):($record->current_chances_used==0 && $record->paid_installments==0 && $record->discount_installment==1 ? 1: 0 )):0)
            );
            $cSql = "SELECT 
			    		 c.firstname,c.lastname,c.email,c.mobile,
					     ad.address1,ad.address2,ad.address3,
					     cy.name as country,st.name as state,ct.name as city,ad.pincode
				FROM  customer c
				LEFT JOIN address ad  ON (c.id_customer = ad.id_customer)
				LEFT JOIN country cy  ON (ad.id_country = cy.id_country)
				LEFT JOIN state st on (ad.id_state=st.id_state)
				LEFT JOIN city ct  ON (ad.id_city = ct.id_city)
				LEFT JOIN scheme_account sa on (c.id_customer=sa.id_customer)
				WHERE sa.id_scheme_account=" . $id_scheme_account;
            $customer = $this->db->query($cSql)->row_array();
            $weight = array();
            $query_weight = $this->db->query('SELECT * FROM  weight WHERE active=1');
            if ($query_weight->num_rows() > 0) {
                foreach ($query_weight->result() as $row) {
                    $weight[] = array('id_weight' => $row->id_weight, 'weight' => $row->weight);
                }
            }
            // matal_ratelist branch 
            //$metal_rates = $this->getMetalRate($record->id_branch);//
            $metal_rates = $this->getMetalRate($record->sch_join_branch);//For branchwise rate 
            //matal_ratelist branch 
            // walletbalance branch 
            $wb = $this->wallet_balance();
            $walletbalance = array('redeem_percent' => $wb['redeem_percent'], 'wal_balance' => floor($wb['wal_balance']));
            //walletbalance branch 	
            return array('chit' => $schemeAcc, 'customer' => $customer, 'weights' => $weight, 'metal_rates' => $metal_rates, 'walletbalance' => $walletbalance);
        }
    }
    function getMetalRate($id_branch)
    {
        /* $filename = base_url().'api/rate.txt'; 	
        $data = file_get_contents($filename);
        $metalrates = (array) json_decode($data);	    
        return $metalrates; */
        $data = $this->get_settings();
        if ($data['is_branchwise_rate'] == 1 && $id_branch != '' && $id_branch != NULL) {
            $sql = "select * from metal_rates m
	   		left join branch_rate br on m.id_metalrates=br.id_metalrate 
	   		where br.id_branch=" . $id_branch . " order by  br.id_metalrate desc limit 1";
            // echo $sql;exit;
        } else if ($data['is_branchwise_rate'] == 1) {
            $sql = "select * from metal_rates 
			left join branch_rate br on br.id_metalrate=metal_rates.id_metalrates 
			where br.status=1";
        } else {
            $sql = "select * from metal_rates m
			left join branch_rate br on br.id_metalrate=m.id_metalrates order by m.id_metalrates desc limit 1";
        }
        $result = $this->db->query($sql);
        //print_r($sql);exit;
        return $result->row_array();
    }
    function get_wallet_accounts()
    {
        $sql = "Select
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
					  (SUM(CASE WHEN wt.transaction_type=0 THEN wt.`value` ELSE 0 END) -   SUM(CASE WHEN wt.transaction_type=1 THEN wt.`value` ELSE 0 END)) as balance
				From wallet_account wa
					Left Join customer c on (wa.id_customer=c.id_customer)
					Left Join employee e on (wa.id_employee=e.id_employee)
					Left Join wallet_transaction wt on (wa.id_wallet_account=wt.id_wallet_account)
					Where c.id_customer='" . $this->session->userdata('cus_id') . "'
					Group By wa.id_wallet_account";
        $result = $this->db->query($sql);
        return $result->result_array();
    }
    function get_wallet_transactions()
    {
        $sql = "Select
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
				  wa.active,cs.wallet_amt_per_points,cs.wallet_balance_type
,cs.wallet_points
			From wallet_transaction wt
			Left Join wallet_account wa on (wt.id_wallet_account=wa.id_wallet_account)
			Left Join customer c on (wa.id_customer=c.id_customer)
			Left Join employee e on (wa.id_employee=e.id_employee)
            join chit_settings cs
			Where c.id_customer ='" . $this->session->userdata('cus_id') . "'";
        $result = $this->db->query($sql);
        return $result->result_array();
    }
    function get_pdcs()
    {
        $sql = "Select count(pp.id_post_payment) as pdc
				From postdate_payment pp
				Left Join scheme_account sa On (pp.id_scheme_account=sa.id_scheme_account)
				Where (pp.payment_status=7 or pp.payment_status=2) and sa.id_customer='" . $this->session->userdata('cus_id') . "'";
        return $this->db->query($sql)->row()->pdc;
    }
    function get_pdc_report()
    {
        $sql = "SELECT
				      sa.scheme_acc_number,s.code,
				      Date_Format(pp.date_payment,'%d-%m-%Y') as date_payment,
				      IF(pp.pay_mode='CHQ',pp.cheque_no,'') as cheque_no,
				      pb.bank_name as payee_bank,
				      pp.payee_branch,
				      pp.amount,
				      psm.payment_status
				FROM postdate_payment pp
				Left Join scheme_account sa On(pp.id_scheme_account=sa.id_scheme_account)
				Left Join scheme s On (sa.id_scheme=s.id_scheme)
				Left Join customer c On(sa.id_customer=c.id_customer)
				Left Join bank pb on (pp.payee_bank=pb.id_bank)
				Left Join payment_status_message psm on (pp.payment_status=psm.id_status_msg)
				Where (pp.payment_status=2 or pp.payment_status=7) and sa.id_customer='" . $this->session->userdata('cus_id') . "'
				Group by sa.id_scheme_account,pp.date_payment";
        $result = $this->db->query($sql);
        return $result->result_array();
    }
    function checkPanNo($id)
    {
        $sql = "SELECT 
				s.id_scheme_account, s.id_customer, s.paid_installments,sch.is_pan_required as is_pan_required,c.pan as pan,
		IFNULL(IF(s.is_opening=1,IFNULL(s.paid_installments,0)+ IFNULL(if(sch.scheme_type = 1 and sch.min_weight != sch.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues)),0), if(sch.scheme_type = 1 and sch.min_weight != sch.max_weight , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues))) ,0)
  as paid_installments,
			FROM scheme_account s
 			LEFT JOIN payment p on (p.id_scheme_account=s.id_scheme_account)
 			LEFT JOIN scheme sch on (sch.id_scheme=s.id_scheme)
 			LEFT JOIN customer c on (c.id_customer=s.id_customer)
			WHERE p.payment_status=1 and s.id_scheme_account='" . $id . "'";
        $result = $this->db->query($sql);
        $res = $result->row_array();
        if ($result->num_rows() > 0) {
            if ($res['is_pan_required'] == 1 && $res['paid_installments'] > 0) {
                echo $res['pan'] == null ? 1 : 0;
            }
        } else {
            echo 0;//not required
        }
    }
    //for mail data
    function get_paymenMailData($payment_no)
    {
        $records = array();
        $query_invoice = $this->db->query("SELECT sch.is_lucky_draw,pay.id_scheme_account as id_scheme_account,sch_acc.account_name as account_name,allow_referral, if(cs.has_lucky_draw=1,concat(ifnull(sch_acc.group_code,''),'',ifnull(sch_acc.scheme_acc_number,'Not allocated')),concat(ifnull(sch.code,''),' ',ifnull(sch_acc.scheme_acc_number,'Not allocated'))) as scheme_acc_number, DATE_FORMAT(pay.date_payment,'%d-%m-%Y') as date_payment, sch.scheme_name as scheme_name, pay.payment_amount as payment_amount,cus.firstname as firstname, cus.lastname as lastname, addr.address1 as address1,addr.address2 as address2,addr.address3 as address3,ct.name as city,addr.pincode,email,cus.mobile,if(payment_mode='CC','Credit Card',if(payment_mode='NB','Net Banking',if(payment_mode='CD','Cheque or DD',if(payment_mode='CO','Cash Pick Up',pm.mode_name)))) as payment_mode,id_transaction,payment_ref_number,pay.receipt_no,bank_name,bank_acc_no,bank_branch,ifnull(metal_weight,'-') as metal_weight,metal_rate,scheme_type,IF(sch_acc.is_opening=1,IFNULL(sch_acc.paid_installments,0)+ COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')),COUNT(Distinct Date_Format(pay.date_payment,'%Y%m'))) as installment,DATE_FORMAT(Date_add(date_payment,Interval 1 month),'%b %Y') as next_due,cs.currency_name,cs.currency_symbol,pay.id_transaction as trans_id,psm.payment_status,psm.id_status_msg,sch.code,sch.code as group_code,cs.schemeacc_no_set,sch.id_scheme,cs.receipt_no_set,sch_acc.scheme_acc_number as ac_no,pay.id_branch,max_members,one_time_premium
							FROM payment as pay
							LEFT JOIN scheme_account sch_acc ON sch_acc.id_scheme_account = pay.id_scheme_account
							LEFT JOIN scheme sch ON sch.id_scheme = sch_acc.id_scheme
							LEFT JOIN customer as cus ON cus.id_customer = sch_acc.id_customer
							LEFT JOIN address as addr ON addr.id_customer = cus.id_customer 
							LEFT JOIN city as ct ON addr.id_city = ct.id_city 
							LEFT JOIN payment_mode pm ON (pay.payment_mode = pm.short_code)
							LEFT JOIN payment_status_message psm ON (pay.payment_status = psm.id_status_msg)
							join chit_settings cs
							WHERE  pay.id_payment = '" . $payment_no . "'");
        //print_r($this->db->last_query());exit;
        if ($query_invoice->num_rows() > 0) {
            foreach ($query_invoice->result() as $row) {
                $records[] = array('allow_referral' => $row->allow_referral, 'id_scheme_account' => $row->id_scheme_account, 'currency_symbol' => $row->currency_symbol, 'currency_name' => $row->currency_name, 'scheme_acc_number' => $row->scheme_acc_number, 'date_payment' => $row->date_payment, 'scheme_name' => $row->scheme_name, 'payment_amount' => $row->payment_amount, 'firstname' => $row->firstname, 'lastname' => $row->lastname, 'id_payment' => $payment_no, 'address1' => $row->address1, 'address2' => $row->address2, 'address3' => $row->address3, 'city' => $row->city, 'pincode' => $row->pincode, 'email' => $row->email, 'mobile' => $row->mobile, 'payment_mode' => $row->payment_mode, 'id_transaction' => $row->id_transaction, 'payment_ref_number' => $row->payment_ref_number, 'receipt_no' => $row->receipt_no, 'bank_name' => $row->bank_name, 'bank_acc_no' => $row->bank_acc_no, 'bank_branch' => $row->bank_branch, 'metal_weight' => $row->metal_weight, 'metal_rate' => $row->metal_rate, 'scheme_type' => $row->scheme_type, 'installment' => $row->installment, 'next_due' => $row->next_due, 'trans_id' => $row->trans_id, 'account_name' => $row->account_name, 'payment_status' => $row->payment_status, 'id_payment_status' => $row->id_status_msg, 'code' => $row->code, 'schemeacc_no_set' => $row->schemeacc_no_set, 'id_scheme' => $row->id_scheme, 'receipt_no_set' => $row->receipt_no_set, 'ac_no' => $row->ac_no, 'is_lucky_draw' => $row->is_lucky_draw, 'max_members' => $row->max_members, 'id_branch' => $row->id_branch, 'one_time_premium' => $row->one_time_premium);
            }
        }
        return $records;
    }
    public function get_gstSplitupData($id, $date_add)
    {
        //NOTE : type with NULL value is GST
        $sql = "SELECT splitup_name,percentage,type FROM gst_splitup_detail WHERE status=1 and type is not null and `id_scheme` =" . $id;
        $data = $this->db->query($sql);
        return $data->result_array();
    }
    public function get_customer($mobile)
    {
        $query_customer = $this->db->query("SELECT cus.id_customer,cus.nominee_name,cus.nominee_mobile, cus.nominee_relationship,reference_no,cus.id_employee,cus.last_sync_time,
		cus.nominee_address1,cus.nominee_address2,cus.id_customer,cus.mobile,cus.id_branch,cs.branchWiseLogin,cs.is_branchwise_cus_reg,cs.pg_email,
	  cs.branch_settings,firstname,lastname,email,address1,pincode,reference_no FROM  customer AS cus 
	  LEFT JOIN address AS addr ON cus.id_customer = addr.id_customer join chit_settings cs WHERE mobile='" . $mobile . "'");
        return $query_customer->row_array();
    }
    //Scheme registered branch show in payment table//hh
    public function get_schjoinbranch($id_scheme_account)
    {
        $query_cus = $this->db->query("SELECT sa.id_branch as sch_join_branch FROM scheme_account sa Left Join payment p on(sa.id_branch=p.id_branch) WHERE sa.id_scheme_account='" . $id_scheme_account . "'");
        //print_r($this->db->last_query());exit;
        return $query_cus->row_array();
    }
    //Scheme registered branch show in payment table//hh
// metalrate branch wise
    public function metal_rate_type($id)
    {
        $this->db->select('metal_rate_type');
        $this->db->where('id_branch', $id);
        $result = $this->db->get('branch');
        return $result->row_array();
    }
    public function metal_rates_list($id, $emp_id)
    {
        $sql = "SELECT m.id_metalrates, m.updatetime, m.goldrate_22ct, m.goldrate_24ct,
						m.silverrate_1gm, m.silverrate_1kg, if(m.id_employee=0,'MJDMA',concat(e.firstname,' ',e.lastname)) as employee,
						 br.status,if(m.id_employee=0,'1','0') as metal_rate_type
						FROM metal_rates m
						Left Join employee e on (m.id_employee=e.id_employee)
						Left Join branch_rate br on (m.id_metalrates= br.id_metalrate)
						left join branch b on (br.id_branch=b.id_branch)
						 where br.id_branch=" . $id . " and br.status=1 
						 " . ($emp_id == null ? ' and m.id_employee=' . $emp_id : 'and  b.metal_rate_type=0') . "
						 group by m.id_metalrates Order By m.id_metalrates Desc";
        $r = $this->db->query($sql);
        return $r->result_array();
    }
    function max_metalrate_list($id, $emp_id)
    {
        $sql = " select max(m.id_metalrates) as max_id 
						 FROM metal_rates m
						Left Join employee e on (m.id_employee=e.id_employee)
						Left Join branch_rate br on (m.id_metalrates= br.id_metalrate)
						left join branch b on (br.id_branch=b.id_branch)
						 where br.id_branch=" . $id . " and br.status=1 
						 " . ($emp_id == null ? ' and m.id_employee=' . $emp_id : 'and  b.metal_rate_type=0') . "
						 group by m.id_metalrates Order By m.id_metalrates Desc";
        return $this->db->query($sql)->row('max_id');
    }
    // metalrate branch wise
    function wallet_balance()
    {
        $data = array();
        $sql = "Select
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
			 cs.wallet_amt_per_points,cs.wallet_balance_type,cs.wallet_points,
             (SUM(CASE WHEN wt.transaction_type=0 THEN wt.`value` ELSE 0 END) -   SUM(CASE WHEN wt.transaction_type=1 THEN wt.`value` ELSE 0 END)) as balance
            From wallet_account wa
            Left Join customer c on (wa.id_customer=c.id_customer)
            Left Join employee e on (wa.id_employee=e.id_employee)
            Left Join wallet_transaction wt on (wa.id_wallet_account=wt.id_wallet_account)
            join chit_settings cs
            where wa.id_wallet_account is not null and c.id_customer =" . $this->session->userdata('cus_id');
        $result = $this->db->query($sql);
        if ($result->num_rows() > 0) {
            $sql1 = "SELECT w.redeem_percent FROM wallet_category_settings w where active=1 and w.id_category=" . $this->config->item('wallet_cat_id');
            $record = $this->db->query($sql1);
            if ($record->num_rows() > 0) {
                $balance = ($result->row()->wallet_balance_type == 1 ? (($result->row()->balance / $result->row()->wallet_points) * $result->row()->wallet_amt_per_points) : $result->row()->balance);
                //$data=(($result->row()->balance*$recor($result->row()->balance/$result->row()->wallet_points)*$result->row()->wallet_amt_per_points))d->row()->redeem_percent)/100);
                $data = array('redeem_percent' => $record->row()->redeem_percent, 'wal_balance' => $balance, 'wallet_balance_type' => $result->row()->wallet_balance_type, 'wallet_points' => $result->row()->wallet_points, 'wallet_amt_per_points' => $result->row()->wallet_amt_per_points);
            }
        }
        return $data;
    }
    function wallet_settingDB($id, $wallet_array)
    {
        $status = $this->db->insert('wallet_transaction', $wallet_array);
        return array('status' => $status, 'insertID' => ($status == TRUE ? $this->db->insert_id() : ''));
    }
    /*  KVP -- Wallet Module Starts  */
    function getWcategorySettings($cat_code = "")
    {
        if ($cat_code == "") {
            $sql = $this->db->query("SELECT id_wcat_settings,`value`,`point`,`id_category`,`redeem_percent`,ws.`active`,`code`,ws.`active` FROM wallet_category_settings ws 
        		LEFT JOIN wallet_category wc on  wc.id_wallet_category = ws.id_category and wc.active=1
        WHERE ws.active=1");
            return $sql->result_array();
        } else {
            $sql = $this->db->query("SELECT id_wcat_settings,`value`,`point`,`id_category`,`redeem_percent`,ws.`active`,`code`,ws.`active` FROM wallet_category_settings ws 
        		LEFT JOIN wallet_category wc on  wc.id_wallet_category = ws.id_category and wc.active=1
        WHERE ws.active=1 and wc.code='" . $cat_code . "'");
            return $sql->row_array();
        }
        //  echo $this->db->last_query();
    }
    function insertData($data, $table)
    {
        $status = $this->db->insert($table, $data);
        return array('status' => $status, 'insertID' => ($status == TRUE ? $this->db->insert_id() : ''));
    }
    function updateData($data, $tran, $table)
    {
        $this->db->where('bill_no', $tran['bill_no']);
        if ($tran['id_branch'] == '') {
            $this->db->where('id_branch', null);
        } else {
            $this->db->where('id_branch', $tran['id_branch']);
        }
        $status = $this->db->update($table, $data);
        return $status;
    }
    function updateTransDetailData($data, $id)
    {
        $this->db->where('id_inter_waltransdetail', $id);
        $status = $this->db->update('inter_wallet_trans_detail', $data);
        return $status;
    }
    function updInterWalletAcc($data)
    {
        $this->db->where('mobile', $data['mobile']);
        $status = $this->db->update('inter_wallet_account', array('available_points' => $data['available_points'], 'last_update' => date('Y-m-d H:i:s')));
        return $status;
    }
    function updwallet($trans, $mobile)
    {
        $sql = $this->db->query("select c.id_customer,id_wallet_account from customer  c left join wallet_account wa on wa.id_customer = c.id_customer where mobile=" . $mobile);
        if ($sql->num_rows() > 0) {
            $id_wallet_ac = $sql->row('id_wallet_account');
            // print_r($trans);exit;
            $data = array(
                'id_wallet_account' => $id_wallet_ac,
                'date_transaction' => date('Y-m-d H:i:s'),
                'transaction_type' => ($trans['trans_type'] == 1 ? 0 : 1),
                'value' => $trans['trans_points'],
                'description' => $trans['remark'],
            );
            $status = $this->db->insert('wallet_transaction', $data);
            //var_dump($status);exit; 
            return $status;
        } else {
            return TRUE;
        }
    }
    function getInterWalletCustomer($mobile)
    {
        $sql = $this->db->query("SELECT * FROM  inter_wallet_account WHERE mobile=" . $mobile);
        if ($sql->num_rows() > 0) {
            return array('status' => true, 'data' => $sql->row_array());
        } else {
            return array('status' => false, 'data' => '');
        }
    }
    function getWalletPaymentContent($txnid)
    {
        $sql = "Select
				  p.id_payment,iwa.available_points,s.code as group_code,s.sync_scheme_code,cs.gent_clientid,s.is_lucky_draw,cs.scheme_wise_acc_no,sa.id_branch as branch,sa.id_scheme_account,cs.schemeacc_no_set,sa.id_scheme,cs.receipt_no_set,cs.scheme_wise_receipt,sa.scheme_acc_number,p.id_branch,s.is_lucky_draw, s.max_members, s.code,
				  ifnull(iwa.mobile,0) as isAvail,c.mobile,redeemed_amount,actual_trans_amt,cs.allow_referral,cs.walletIntegration,c.id_customer,cs.wallet_points,cs.wallet_amt_per_points,cs.wallet_balance_type,s.agent_refferal,s.agent_credit_type,p.payment_amount
				From payment p
				Join chit_settings cs
				Left Join scheme_account sa on (p.id_scheme_account=sa.id_scheme_account)
				LEFT JOIN scheme s ON s.id_scheme = sa.id_scheme
				Left Join customer c on (c.id_customer=sa.id_customer)
				LEFT JOIN inter_wallet_account iwa on iwa.mobile=c.mobile
				Where p.ref_trans_id='" . $txnid . "'";
        return $this->db->query($sql)->row_array();
    }
    function getSyncWalletData($id_branch)
    {
        $sql = "SELECT * FROM  inter_sync_wallet WHERE branch_" . $id_branch . "= 0";
        return $this->db->query($sql)->result_array();
    }
    function getSyncWalletByMobile($mobile)
    {
        $sql = "SELECT * FROM  inter_sync_wallet WHERE mobile=" . $mobile;
        return $this->db->query($sql)->row_array();
    }
    function updateSyncWal($data)
    {
        $this->db->where('mobile', $data['mobile']);
        $status = $this->db->update('inter_sync_wallet', $data);
        return $status;
    }
    /*  KVP -- Wallet Module ends  */
    //payment gateway//
    function get_paymentgateway()
    {
        $record = array();
        $sql = "Select active,creditCard, debitCard,description,id_pg_settings,is_primary_gateway,netBanking,pg_code,pg_icon,pg_name,saveCard,sort from payment_gateway where active=1 order by sort asc";
        $result = $this->db->query($sql);
        if ($result->num_rows() > 0) {
            foreach ($result->result_array() as $row) {
                $file = base_url() . 'admin/assets/img/gateway/' . $row['pg_icon'];
                $img_path = ($row['pg_icon'] != null ? (file_exists('admin/assets/img/gateway/' . $row['pg_icon']) ? $file : null) : null);
                $record[] = array('pg_name' => $row['pg_name'], 'pg_code' => $row['pg_code'], 'netBanking' => $row['netBanking'], 'is_primary_gateway' => $row['is_primary_gateway'], 'active' => $row['active'], 'description' => $row['description'], 'id_pg_settings' => $row['id_pg_settings'], 'saveCard' => $row['saveCard'], 'creditCard' => $row['creditCard'], 'debitCard' => $row['debitCard'], 'pg_icon' => $img_path);
            }
        }
        return array('gateway' => $record);
    }
    //paymet gateway
    function paymentDB($id = "")
    {
        $sql = "Select
			      		s.code,IFNULL(sa.group_code,'')as scheme_group_code,cs.has_lucky_draw,allow_referral,s.id_scheme,s.is_lucky_draw,cs.scheme_wise_acc_no,
						  p.id_payment,s.gst,s.gst_type,is_point_credited,iwa.available_points,sa.id_branch as branch,
						  ifnull(iwa.mobile,0) as isAvail,c.mobile,redeemed_amount,actual_trans_amt,
						  p.id_scheme_account,
						IFNULL(sa.scheme_acc_number,'Not Allocated') as scheme_acc_number,
						  sa.account_name,p.receipt_no,
						  p.id_transaction,
						  p.payu_id,p.due_type,
						  p.id_post_payment,p.act_amount,if(p.payment_type='Payu Checkout' and (p.due_type='A' or p.due_type='P') and p.payment_status!=1,'Y','N' ) as showPaid,
						  p.id_drawee,
						  da.account_no as drawee_acc_no,
						  da.account_name as drawee_account_name,
						  Date_format(date_payment,'%d-%m-%Y') as date_payment,
						  p.payment_type,p.no_of_dues,
						  p.payment_mode,
						  p.payment_amount,
						  p.add_charges,(select charges FROM postdate_payment WHERE id_post_payment=p.id_post_payment) as charges,
						  p.metal_rate,
						  p.metal_weight,
						  p.cheque_date,
						  p.cheque_no,
						  p.bank_acc_no,
						  p.bank_name,
						  p.bank_branch,
						  p.bank_IFSC,
						  p.card_no,
						  p.card_holder,
						  p.cvv,
						  p.exp_date,
						  p.payment_ref_number,
						  p.payment_status as id_payment_status,
						  p.remark,
						  psm.payment_status as payment_status,
						  psm.color as status_color,
						  cs.receipt_no_set,cs.scheme_wise_receipt,cs.schemeacc_no_set,IFNULL(sa.scheme_acc_number,'') as acc_no,
						   p.remark,if((cs.receipt_no_set= 1 && p.payment_status =1 ),p.receipt_no,if((cs.receipt_no_set= 0 && p.payment_status =1),p.receipt_no,'')) as receipt_no
						From payment p
						Join chit_settings cs
						Left Join scheme_account sa on (p.id_scheme_account=sa.id_scheme_account)
						Left Join scheme s on (s.id_scheme=sa.id_scheme)
						Left Join drawee_account da on (p.id_drawee=p.id_drawee)
						Left Join payment_mode pm on (p.payment_mode=pm.id_mode)
						Left Join payment_status_message psm On (p.payment_status=psm.id_status_msg)
Left Join customer c on (c.id_customer=sa.id_customer)
						LEFT JOIN inter_wallet_account iwa on iwa.mobile=c.mobile
						Where p.id_payment=" . $id;
        return $this->db->query($sql)->row_array();
    }
    function account_number_generator($id_scheme, $branch, $ac_group_code)
    {
        $lastno = $this->get_schAccount_no($id_scheme, $branch, $ac_group_code);
		$number = 0;
        if ($lastno != NULL) {
            $number = (int) $lastno;
            $number++;
            //$schAc_number=str_pad($number, 5, '0', STR_PAD_LEFT);
            $schAc_number = $number;
        } else {
			$number++;
			$schAc_number = $number;
        }
		// 		print_r($schAc_number);exit;
        return $schAc_number;
    }
    function get_schAccount_no($id_scheme, $branch, $ac_group_code)
    {
        /* 
            scheme_wise_acc_no settings done by HH
            0 - Common,
            1 - Common with branch wise,
            2 - Scheme-wise,
            3 - Scheme-wise with branch wise
            For value 2,3 if lucky draw is enabled in schemes means have to generate group wise account number
        */
        $data = $this->get_settings();
        $id_company = 0;//$this->session->userdata('id_company');
        $company_settings = $data['company_settings'];//$this->session->userdata('company_settings');
        //group wise for lucky draw scheme....
        $sql_lucky = $this->db->query("SELECT is_lucky_draw,max_members FROM scheme WHERE id_scheme=" . $id_scheme);
        $luckyDraw = $sql_lucky->row_array();
        if ($data['branch_settings'] == 1) { // Branch Enabled
            if ($luckyDraw['is_lucky_draw'] == 1 && $luckyDraw['max_members'] > 0) {
                $sqlGrp = $this->db->query("SELECT group_code FROM scheme_group WHERE status = 1 and id_branch = " . $branch . "  and id_scheme=" . $id_scheme);
                $grpCode = $sqlGrp->row()->group_code;
            } else {
                $grpCode = "";
            }
            if ($data['scheme_wise_acc_no'] == 1 && $branch > 0) { // 1 - Common with branch wise,
                $sql = "SELECT max(sa.scheme_acc_number) as lastSchAcc_no FROM scheme_account sa 
                 left join customer c on c.id_customer= sa.id_customer
                 WHERE sa.id_branch=" . $branch . " 
                 " . ($grpCode != null && $grpCode != '' ? "AND sa.group_code='" . $grpCode . "'" : '') . "
                 " . ($id_company != 0 && $company_settings == 1 ? "and c.id_company=" . $id_company . "" : '') . " ORDER BY id_scheme_account DESC ";
            } else if ($data['scheme_wise_acc_no'] == 2) { // 2 - Scheme-wise
                $sql = "SELECT max(sa.scheme_acc_number) as lastSchAcc_no FROM scheme_account sa 
                left join customer c on c.id_customer= sa.id_customer
                WHERE sa.id_scheme=" . $id_scheme . " 
                " . ($grpCode != null && $grpCode != '' ? "AND sa.group_code='" . $grpCode . "'" : '') . "
                " . ($id_company != 0 && $company_settings == 1 ? "and c.id_company=" . $id_company . "" : '') . " ORDER BY id_scheme_account DESC ";
                //print_r($sql);exit;
            } else if ($data['scheme_wise_acc_no'] == 3) { // 3 - Scheme-wise with branch wise
                $sql = "SELECT max(sa.scheme_acc_number) as lastSchAcc_no FROM scheme_account sa 
                left join customer c on c.id_customer= sa.id_customer
                WHERE sa.id_scheme=" . $id_scheme . " AND sa.id_branch=" . $branch . " 
                " . ($grpCode != null && $grpCode != '' ? "AND sa.group_code='" . $grpCode . "'" : '') . "
                " . ($id_company != 0 && $company_settings == 1 ? "and c.id_company=" . $id_company . "" : '') . " ORDER BY id_scheme_account DESC ";
                //print_r($sql);exit;
            } else if ($data['scheme_wise_acc_no'] == 4) {
                $res = $this->db->query("SELECT date(fin_year_from) as fin_date FROM `ret_financial_year` where fin_status = 1");
                $financial_year = $res->row()->fin_date;
                $sql = "SELECT max(sa.scheme_acc_number) as lastSchAcc_no FROM scheme_account sa 
                left join customer c on c.id_customer= sa.id_customer
                WHERE date(start_date) BETWEEN '" . $financial_year . "' AND DATE(CURDATE())
                " . ($grpCode != null && $grpCode != '' ? "AND sa.group_code='" . $grpCode . "'" : '') . "
                " . ($id_company != 0 && $company_settings == 1 ? " AND c.id_company=" . $id_company . "" : '') . " ORDER BY id_scheme_account DESC ";
            } else if ($data['scheme_wise_acc_no'] == 5) // financial year with scheme wise
            {
                $res = $this->db->query("SELECT date(fin_year_from) as fin_date FROM `ret_financial_year` where fin_status = 1");
                $financial_year = $res->row()->fin_date;
                $sql = "SELECT max(sa.scheme_acc_number) as lastSchAcc_no FROM scheme_account sa 
                left join customer c on c.id_customer= sa.id_customer
                WHERE sa.id_scheme=" . $id_scheme . " and date(sa.start_date) BETWEEN '" . $financial_year . "' AND DATE(CURDATE())
                " . ($grpCode != null && $grpCode != '' ? "AND sa.group_code='" . $grpCode . "'" : '') . "
                " . ($id_company != 0 && $company_settings == 1 ? " AND c.id_company=" . $id_company . "" : '') . " ORDER BY id_scheme_account DESC ";
            } else { // If other cases fails,generate common account number
                $sql = "SELECT max(sa.scheme_acc_number) as lastSchAcc_no FROM scheme_account sa 
                left join customer c on c.id_customer= sa.id_customer
                " . ($grpCode != null && $grpCode != '' ? "WHERE sa.group_code='" . $grpCode . "'" : '') . "
                " . ($id_company != 0 && $company_settings == 1 && $grpCode != null && $grpCode != '' ? "AND c.id_company=" . $id_company . "" : "") . "
                ORDER BY id_scheme_account DESC ";
            }
        } else {
            if ($luckyDraw['is_lucky_draw'] == 1 && $luckyDraw['max_members'] > 0) {
                $sqlGrp = $this->db->query("SELECT group_code FROM scheme_group WHERE status = 1 and id_branch = " . $branch . "  and id_scheme=" . $id_scheme);
                $grpCode = $sqlGrp->row()->group_code;
            } else {
                $grpCode = "";
            }
            if ($data['scheme_wise_acc_no'] == 0) { // 0 - Common
                $sql = "SELECT max(sa.scheme_acc_number) as lastSchAcc_no FROM scheme_account sa 
                left join customer c on c.id_customer= sa.id_customer
                " . ($grpCode != null && $grpCode != '' ? "WHERE sa.group_code='" . $grpCode . "'" : '') . "
                " . ($id_company != 0 && $company_settings == 1 && $grpCode != null && $grpCode != '' ? "AND c.id_company=" . $id_company . "" : "WHERE c.id_company=" . $id_company) . "
                ORDER BY id_scheme_account DESC ";
            } else if ($data['scheme_wise_acc_no'] == 2) { // 2 - Scheme-wise
                $sql = "SELECT max(sa.scheme_acc_number) as lastSchAcc_no FROM scheme_account sa 
                left join customer c on c.id_customer= sa.id_customer
                WHERE sa.id_scheme=" . $id_scheme . " 
                " . ($grpCode != null && $grpCode != '' ? "and sa.group_code='" . $grpCode . "'" : '') . "
                " . ($id_company != 0 && $company_settings == 1 ? "and c.id_company=" . $id_company . "" : '') . " ORDER BY id_scheme_account DESC ";
            } else if ($data['scheme_wise_acc_no'] == 4) {
                $res = $this->db->query("SELECT date(fin_year_from) as fin_date FROM `ret_financial_year` where fin_status = 1");
                $financial_year = $res->row()->fin_date;
                $sql = "SELECT max(sa.scheme_acc_number) as lastSchAcc_no FROM scheme_account sa 
                left join customer c on c.id_customer= sa.id_customer
                WHERE date(start_date) BETWEEN '" . $financial_year . "' AND DATE(CURDATE())
                 " . ($grpCode != null && $grpCode != '' ? "and sa.group_code='" . $grpCode . "'" : '') . "
                " . ($id_company != 0 && $company_settings == 1 ? " AND c.id_company=" . $id_company . "" : '') . " ORDER BY id_scheme_account DESC ";
            } else if ($data['scheme_wise_acc_no'] == 5) // financial year with scheme wise
            {
                $res = $this->db->query("SELECT date(fin_year_from) as fin_date FROM `ret_financial_year` where fin_status = 1");
                $financial_year = $res->row()->fin_date;
                $sql = "SELECT max(sa.scheme_acc_number) as lastSchAcc_no FROM scheme_account sa 
                left join customer c on c.id_customer= sa.id_customer
                WHERE sa.id_scheme=" . $id_scheme . " and date(sa.start_date) BETWEEN '" . $financial_year . "' AND DATE(CURDATE())
                 " . ($grpCode != null && $grpCode != '' ? "and sa.group_code='" . $grpCode . "'" : '') . "
                " . ($id_company != 0 && $company_settings == 1 ? " AND c.id_company=" . $id_company . "" : '') . " ORDER BY id_scheme_account DESC ";
            } else { // If other cases fails,generate common account number
                $sql = "SELECT max(sa.scheme_acc_number) as lastSchAcc_no FROM scheme_account sa 
                left join customer c on c.id_customer= sa.id_customer
                " . ($grpCode != null && $grpCode != '' ? "WHERE sa.group_code='" . $grpCode . "'" : '') . "
                " . ($id_company != 0 && $company_settings == 1 && $grpCode != null && $grpCode != '' ? "AND c.id_company=" . $id_company . "" : "WHERE c.id_company=" . $id_company) . "
                ORDER BY id_scheme_account DESC ";
            }
        }
        // print_r($sql);exit;
        return $this->db->query($sql)->row()->lastSchAcc_no;
    }
    /*function get_schAccount_no($id_scheme,$branch,$ac_group_code)    
    {
        $data = $this->get_settings(); 
        if($data['branch_settings']==1){ // Branch Enabled
            if($data['scheme_wise_acc_no'] == 1 && $branch > 0){ // 1 - Common with branch wise,
                 $sql = "SELECT max(sa.scheme_acc_number) as lastSchAcc_no FROM scheme_account sa WHERE sa.id_branch=".$branch." ORDER BY id_scheme_account DESC ";
            }
            else if($data['scheme_wise_acc_no'] == 2){ // 2 - Scheme-wise
                $sql = "SELECT max(sa.scheme_acc_number) as lastSchAcc_no FROM scheme_account sa WHERE sa.id_scheme=".$id_scheme." ".($ac_group_code != ''  && $ac_group_code != null ? " AND sa.group_code='".$ac_group_code."'" : '' )." ORDER BY id_scheme_account DESC "; 
            //print_r($sql);exit;
            }
            else if($data['scheme_wise_acc_no'] == 3){ // 3 - Scheme-wise with branch wise
                $sql = "SELECT max(sa.scheme_acc_number) as lastSchAcc_no FROM scheme_account sa WHERE sa.id_scheme=".$id_scheme." AND sa.id_branch=".$branch." ".($ac_group_code != ''  && $ac_group_code != null ? " AND sa.group_code='".$ac_group_code."'" : '' )." ORDER BY id_scheme_account DESC "; 
            //print_r($sql);exit;
            }
            else{ // If other cases fails,generate common account number
                $sql = "SELECT max(sa.scheme_acc_number) as lastSchAcc_no FROM scheme_account sa ORDER BY id_scheme_account DESC ";
            }
        }else{
            if($data['scheme_wise_acc_no'] == 0){ // 0 - Common
                $sql = "SELECT max(sa.scheme_acc_number) as lastSchAcc_no FROM scheme_account sa ORDER BY id_scheme_account DESC ";
            }
            else if($data['scheme_wise_acc_no'] == 2){ // 2 - Scheme-wise
                $sql = "SELECT max(sa.scheme_acc_number) as lastSchAcc_no FROM scheme_account sa WHERE sa.id_scheme=".$id_scheme." ".($ac_group_code != ''  && $ac_group_code != null ? " AND sa.group_code='".$ac_group_code."'" : '' )."ORDER BY id_scheme_account DESC ";
            }
            else{ // If other cases fails,generate common account number
                $sql = "SELECT max(sa.scheme_acc_number) as lastSchAcc_no FROM scheme_account sa ORDER BY id_scheme_account DESC ";
            } 
        } 
        return $this->db->query($sql)->row()->lastSchAcc_no;		
    }*/
    /* function get_schAccount_no($id_scheme,$branch,$ac_group_code)    
     {
         $data = $this->get_settings(); 
         if($data['branch_settings']==1){ // Branch Enabled
             if($data['scheme_wise_acc_no'] == 1 && $branch > 0){ // 1 - Common with branch wise,
                  $sql = "SELECT max(sa.scheme_acc_number) as lastSchAcc_no FROM scheme_account sa WHERE sa.id_branch=".$branch." ORDER BY scheme_acc_number desc limit 0,1  ";
             }
             else if($data['scheme_wise_acc_no'] == 2){ // 2 - Scheme-wise
                 $sql = "SELECT max(sa.scheme_acc_number) as lastSchAcc_no FROM scheme_account sa WHERE sa.id_scheme=".$id_scheme." ".($ac_group_code != ''  && $ac_group_code != null ? " AND sa.group_code='".$ac_group_code."'" : '' )." group by sa.id_scheme "; 
             //print_r($sql);exit;
             }
             else if($data['scheme_wise_acc_no'] == 3){ // 3 - Scheme-wise with branch wise
                 $sql = "SELECT max(sa.scheme_acc_number) as lastSchAcc_no FROM scheme_account sa WHERE sa.id_scheme=".$id_scheme." AND sa.id_branch=".$branch." ".($ac_group_code != ''  && $ac_group_code != null ? " AND sa.group_code='".$ac_group_code."'" : '' )." group by sa.id_scheme,sa.id_branch "; 
             //print_r($sql);exit;
             }
              else if($data['scheme_wise_acc_no'] == 4)
             {
                 $res = $this->db->query("SELECT date(fin_year_from) as fin_date FROM `ret_financial_year` where fin_status = 1");
                 $financial_year = $res->row()->fin_date;
                 $sql = "SELECT max(sa.scheme_acc_number) as lastSchAcc_no FROM scheme_account sa 
                 left join customer c on c.id_customer= sa.id_customer
                 WHERE date(start_date) BETWEEN '".$financial_year."' AND DATE(CURDATE()) ORDER BY id_scheme_account DESC ";
             }
             else if($data['scheme_wise_acc_no'] == 5) // financial year with scheme wise
             {
                 $res = $this->db->query("SELECT date(fin_year_from) as fin_date FROM `ret_financial_year` where fin_status = 1");
                 $financial_year = $res->row()->fin_date;
                 $sql = "SELECT max(sa.scheme_acc_number) as lastSchAcc_no FROM scheme_account sa 
                 left join customer c on c.id_customer= sa.id_customer
                 WHERE sa.id_scheme=".$id_scheme." and date(sa.start_date) BETWEEN '".$financial_year."' AND DATE(CURDATE()) ORDER BY id_scheme_account DESC ";
             }
             else{ // If other cases fails,generate common account number
                 $sql = "SELECT max(sa.scheme_acc_number) as lastSchAcc_no FROM scheme_account sa ORDER BY scheme_acc_number desc limit 0,1 ";
             }
         }else{
             if($data['scheme_wise_acc_no'] == 0){ // 0 - Common
                 $sql = "SELECT max(sa.scheme_acc_number) as lastSchAcc_no FROM scheme_account sa ORDER BY scheme_acc_number desc limit 0,1 ";
             }
             else if($data['scheme_wise_acc_no'] == 2){ // 2 - Scheme-wise
                 $sql = "SELECT max(sa.scheme_acc_number) as lastSchAcc_no FROM scheme_account sa WHERE sa.id_scheme=".$id_scheme." ".($ac_group_code != ''  && $ac_group_code != null ? " AND sa.group_code='".$ac_group_code."'" : '' )." group by sa.id_scheme ";
             }
              else if($data['scheme_wise_acc_no'] == 4)
             {
                 $res = $this->db->query("SELECT date(fin_year_from) as fin_date FROM `ret_financial_year` where fin_status = 1");
                 $financial_year = $res->row()->fin_date;
                 $sql = "SELECT max(sa.scheme_acc_number) as lastSchAcc_no FROM scheme_account sa 
                 left join customer c on c.id_customer= sa.id_customer
                 WHERE date(start_date) BETWEEN '".$financial_year."' AND DATE(CURDATE()) ORDER BY id_scheme_account DESC ";
             }
              else if($data['scheme_wise_acc_no'] == 5) // financial year with scheme wise
             {
                 $res = $this->db->query("SELECT date(fin_year_from) as fin_date FROM `ret_financial_year` where fin_status = 1");
                 $financial_year = $res->row()->fin_date;
                 $sql = "SELECT max(sa.scheme_acc_number) as lastSchAcc_no FROM scheme_account sa 
                 left join customer c on c.id_customer= sa.id_customer
                 WHERE sa.id_scheme=".$id_scheme." and date(sa.start_date) BETWEEN '".$financial_year."' AND DATE(CURDATE()) ORDER BY id_scheme_account DESC ";
             }
             else{ // If other cases fails,generate common account number
                 $sql = "SELECT max(sa.scheme_acc_number) as lastSchAcc_no FROM scheme_account sa ORDER BY scheme_acc_number desc limit 0,1 ";
             } 
         } 
         return $this->db->query($sql)->row()->lastSchAcc_no;		
     } */
    function update_account($data, $id)
    {
        $this->db->where('id_scheme_account', $id);
        $status = $this->db->update('scheme_account', $data);
        return $status;
    }
    function update_receipt($id, $data)
    {
        $this->db->where('id_payment', $id);
        $status = $this->db->update('payment', $data);
        return $status;
    }
    /*	 function get_receipt_no($id_scheme,$branch)
      {
          $sql = "Select max(p.receipt_no) as receipt_no 
                  From payment p
                  left join scheme_account sa on sa.id_scheme_account=p.id_scheme_account
                  left join scheme s on s.id_scheme=sa.id_scheme
                  Where p.payment_status=1 ".($id_scheme!='' ?"and s.id_scheme=".$id_scheme."":'')." ";
          return $this->db->query($sql)->row()->receipt_no;		
      }*/
    function get_receipt_no($id_scheme = '', $branch = '')  // branch wise Receipt Num generaTION  based on the settings//HH
    {
        /* 
            scheme_wise_receipt settings done by HH
            1 - Common,
            2 - branch wise, 
            3 - Scheme-wise, 
            4 - Scheme-wise with branch wise
        */
        $data = $this->get_settings();
        $sql = '';
        if ($data['scheme_wise_receipt'] == 5 || $data['scheme_wise_receipt'] == 6 || $data['scheme_wise_receipt'] == 7) {
            $res = $this->db->query("SELECT date(fin_year_from) as fin_date,fin_year_code FROM `ret_financial_year` where fin_status = 1");
            $financial_year = $res->row()->fin_year_code;
            $financial_date = $res->row()->fin_date;
        }
        //echo "<pre>";print_r($data);
        if ($data['branch_settings'] == 1) { // Branch Enabled
            if ($data['scheme_wise_receipt'] == 2 && $branch > 0) {  // 2 - branch wise Receipt number
                $sql = "Select max(p.receipt_no) as receipt_no
    				From payment p
    				Where  p.id_branch=" . $branch . " group by id_branch ";
            } else if ($data['scheme_wise_receipt'] == 3) { // 3 - Scheme-wise Receipt number
                $sql = "Select max(p.receipt_no) as receipt_no
    				From payment p
        				left join scheme_account sa on sa.id_scheme_account=p.id_scheme_account
    				Where   sa.id_scheme=" . $id_scheme . " group by sa.id_scheme";
            } else if ($data['scheme_wise_receipt'] == 4) { // 4 - Scheme-wise with branch wise Receipt number
                $sql = "Select max(p.receipt_no) as receipt_no
    				From payment p
        				left join scheme_account sa on sa.id_scheme_account=p.id_scheme_account
    				Where   sa.id_scheme=" . $id_scheme . " AND p.id_branch=" . $branch . "  group by sa.id_scheme,p.id_branch ";
            } else if ($data['scheme_wise_receipt'] == 5) {// financial year wise
                /*$res = $this->db->query("SELECT date(fin_year_from) as fin_date FROM `ret_financial_year` where fin_status = 1");
                $financial_year = $res->row()->fin_date;*/
                $sql = "Select p.receipt_no
    				From payment p
    				left join scheme_account sa on sa.id_scheme_account=p.id_scheme_account
        			left join customer c on c.id_customer= sa.id_customer
    				Where   date(date_payment) BETWEEN '" . $financial_date . "' AND DATE(CURDATE())  order by receipt_no desc limit 0,1 ";
            } else if ($data['scheme_wise_receipt'] == 6) { // 6 - financial year  with Scheme-wise & branch wise Receipt number
                /*$res = $this->db->query("SELECT date(fin_year_from) as fin_date FROM `ret_financial_year` where fin_status = 1");
               $financial_year = $res->row()->fin_date;*/
                $sql = "Select max(p.receipt_no) as receipt_no
    				From payment p
        				left join scheme_account sa on sa.id_scheme_account=p.id_scheme_account
        				left join customer c on c.id_customer= sa.id_customer
        				left join scheme s on s.id_scheme=sa.id_scheme
    				Where  date(date_payment) BETWEEN '" . $financial_date . "' AND DATE(CURDATE())  and sa.id_scheme=" . $id_scheme . " AND p.id_branch=" . $branch . "
    				" . ($id_company != 0 && $company_settings == 1 ? "and c.id_company=" . $id_company . "" : '') . " group by sa.id_scheme,p.id_branch ";
            } else if ($data['scheme_wise_receipt'] == 7) { // 7 - financial year with branch wise Receipt number
                $sql = "Select max(receipt_no) as receipt_no
    				From payment
        				left join scheme_account  on scheme_account.id_scheme_account=payment.id_scheme_account
        				left join customer on customer.id_customer= scheme_account.id_customer
    				Where  date(date_payment) BETWEEN '" . $financial_date . "' AND DATE(CURDATE())  AND payment.id_branch=" . $branch . "
    				" . ($id_company != 0 && $company_settings == 1 ? "and customer.id_company=" . $id_company . "" : '') . " group by payment.receipt_year,payment.id_branch order by receipt_no desc limit 0,1";
            } else  // If other cases fails,generate 1 - common Receipt number
            {
                $sql = "Select receipt_no
    				From payment p
    				Where   1 order by receipt_no desc limit 0,1 ";
            }
        } else {
            if ($data['scheme_wise_receipt'] == 3) {   // 3 - Scheme-wise Receipt number
                $sql = "Select max(p.receipt_no) as receipt_no
    				From payment p
        				left join scheme_account sa on sa.id_scheme_account=p.id_scheme_account
    				Where  sa.id_scheme=" . $id_scheme . " group by sa.id_scheme ";
            } else if ($data['scheme_wise_receipt'] == 5) {// financial year wise
                /*$res = $this->db->query("SELECT date(fin_year_from) as fin_date FROM `ret_financial_year` where fin_status = 1");
                $financial_year = $res->row()->fin_date;*/
                $sql = "Select p.receipt_no
    				From payment p
    				left join scheme_account sa on sa.id_scheme_account=p.id_scheme_account
        			left join customer c on c.id_customer= sa.id_customer
    				Where  date(date_payment) BETWEEN '" . $financial_date . "' AND DATE(CURDATE()) order by receipt_no desc limit 0,1 ";
            } else  // If other cases fails,generate 1 - common Receipt number
            {
                $sql = "Select receipt_no
    				From payment p
    				Where 1 order by receipt_no desc limit 0,1 ";
            }
        }
        //print_r($sql);exit;
        return $this->db->query($sql)->row()->receipt_no;
    }
    function get_company()
    {
        $sql = " Select  c.id_company,c.company_name,c.gst_number,c.short_code,c.pincode,c.mobile,c.phone,c.email,c.website,c.address1,c.address2,c.id_country,c.id_state,c.id_city,ct.name as city,s.name as state,cy.name as country,cs.currency_symbol,cs.currency_name,cs.mob_code,cs.mob_no_len,c.mail_server,c.mail_password,c.send_through,c.mobile1,c.phone1
	  from company c
					join chit_settings cs
					left join country cy on (c.id_country=cy.id_country)
					left join state s on (c.id_state=s.id_state)
					left join city ct on (c.id_city=ct.id_city)";
        $result = $this->db->query($sql);	//print_r($result->row_array());exit;
        return $result->row_array();
    }
    // For referral credit
    function get_refdata($id_scheme_account)
    {
        $sql = ("SELECT sa.referal_code,sa.id_scheme_account,s.ref_benifitadd_ins,sa.id_customer,is_refferal_by,
					IFNULL(IF(sa.is_opening=1,IFNULL(sa.paid_installments,0)+ IFNULL(if(s.scheme_type = 1 and s.min_weight != s.max_weight ,
					COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues)),0),
					if(s.scheme_type = 1 and s.min_weight != s.max_weight or s.scheme_type=3 , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues))) ,0)
					as paid_installments,s.ref_benifitadd_ins_type
					     FROM scheme_account sa
					    left join scheme s on (sa.id_scheme=s.id_scheme)
					    left join payment p on (sa.id_scheme_account=p.id_scheme_account) 
						where sa.id_scheme_account=" . $id_scheme_account . " and  p.payment_status=1 group by sa.id_scheme_account");
        $result = $this->db->query($sql)->row_array();
        return $result;
    }
    function get_agent_refdata($id_scheme_account, $pay_id)
    {
        $sql = ("SELECT sa.agent_code,sa.id_agent,sa.id_customer as cus_loyal_cus_id,is_refferal_by,sa.id_scheme,p.payment_amount,
					IFNULL(IF(sa.is_opening=1,IFNULL(sa.paid_installments,0)+ IFNULL(if(s.scheme_type = 1 and s.min_weight != s.max_weight ,
					COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues)),0),
					if(s.scheme_type = 1 and s.min_weight != s.max_weight or s.scheme_type=3 , COUNT(Distinct Date_Format(p.date_payment,'%Y%m')), sum(p.no_of_dues))) ,0)
					as paid_installments
					     FROM scheme_account sa
					    left join scheme s on (s.id_scheme=sa.id_scheme)
					    left join payment p on (sa.id_scheme_account=p.id_scheme_account) 
						where sa.id_scheme_account=" . $id_scheme_account . "  and  p.payment_status=1 group by sa.id_scheme_account");
        //	print_r($sql);exit;
        $result = $this->db->query($sql)->row_array();
        return $result;
    }
    function get_agentBenefits($id_scheme, $amount, $ins)
    {
        $current_ins = $ins;
        $sql = $this->db->query("SELECT if(ab.benefit_type=0,((" . $amount . "*ab.benefit_value)/100),ab.benefit_value) as cash_point FROM `scheme_agent_benefit` ab  
         WHERE ab.id_scheme = " . $id_scheme . " AND ab.installment_from >= " . $current_ins . " AND ab.installment_to <=" . $current_ins . " ");
        if ($sql->num_rows > 0) {
            return $sql->row_array();
        } else
            return 0;
    }
    function updateAgentCash($id_agent, $new_point)
    {
        $status = $this->db->query("UPDATE agent set cash_reward = (cash_reward+" . $new_point . ") where id_agent=" . $id_agent);
        return $status;
    }
    function get_ischkrefamtadd($id_scheme_account)
    {
        $record = $this->db->query("SELECT sa.id_scheme,sa.id_customer,sa.is_refferal_by,chit.schrefbenifit_secadd,
		 chit.empbenefitscrt_type,chit.cusbenefitscrt_type,sa.referal_code 
		 FROM scheme_account sa
 		 join chit_settings chit
		 where sa.id_scheme_account=" . $id_scheme_account . "");
        if ($record->num_rows() > 0) {
            if ($record->row()->is_refferal_by != null) {
                if (
                    $record->row()->is_refferal_by == 0 && $record->row()->cusbenefitscrt_type == 0 && ($record->row()->schrefbenifit_secadd == 0 ||
                        $record->row()->schrefbenifit_secadd == 1)
                ) {
                    $status = $this->get_chkrefschcus_joincount($record->row()->id_customer);
                    if ($status) {
                        return true;
                    }
                } else if (
                    $record->row()->is_refferal_by == 0 && $record->row()->cusbenefitscrt_type == 1 &&
                    $record->row()->schrefbenifit_secadd == 1
                ) {
                    $status = $this->get_chkrefschrefcode_joincount($record->row()->id_customer, $record->row()->id_scheme, $record->row()->referal_code);
                    if ($status) {
                        return true;
                    }
                } else if (
                    $record->row()->is_refferal_by == 0 && $record->row()->cusbenefitscrt_type == 1 &&
                    $record->row()->schrefbenifit_secadd == 0
                ) {
                    return true;
                } else if (
                    $record->row()->is_refferal_by == 1 && $record->row()->empbenefitscrt_type == 0 && ($record->row()->schrefbenifit_secadd == 0 ||
                        $record->row()->schrefbenifit_secadd == 1)
                ) {
                    $status = $this->get_chkrefschcus_joincount($record->row()->id_customer);
                    if ($status) {
                        return true;
                    }
                } else if (
                    $record->row()->is_refferal_by == 1 && $record->row()->empbenefitscrt_type == 1 &&
                    $record->row()->schrefbenifit_secadd == 1
                ) {
                    $status = $this->get_chkrefschrefcode_joincount($record->row()->id_customer, $record->row()->id_scheme, $record->row()->referal_code);
                    if ($status) {
                        return true;
                    }
                } else if (
                    $record->row()->is_refferal_by == 1 && $record->row()->empbenefitscrt_type == 1 &&
                    $record->row()->schrefbenifit_secadd == 0
                ) {
                    return true;
                }
            }
            return false;
        }
    }
    public function get_settings()
    {
        $sql = "select * from chit_settings";
        $result = $this->db->query($sql);
        return $result->row_array();
    }
    function get_empreferrals_datas($id_scheme_account)
    {
        $data = $this->get_settings();
        $sql = ("SELECT sa.id_scheme_account, sa.id_scheme,
				s.code,if(cus.lastname is null,cus.firstname,concat(cus.firstname,'',cus.lastname)) as cusname, s.scheme_name,
				FORMAT(if(chit.empplan_type=1,if(ws.type=0,ws.value,((s.amount*ws.value)/100)),
				if(s.emp_refferal=1 && chit.empplan_type=0,s.emp_refferal_value,'')),0) as referal_value,
				sa.is_refferal_by,sa.referal_code,chit.empplan_type,chit.cusbenefitscrt_type,
				chit.empbenefitscrt_type,chit.schrefbenifit_secadd,
				s.emp_refferal,s.emp_refferal_value,ref.name,
				ref.mobile,ref.emp_code,ref.idemployee as idemployee,ref.id_wallet_account					
				FROM scheme_account sa
				left join scheme s on (sa.id_scheme =s.id_scheme)
				left join(SELECT w.id_employee,w.id_wallet_account,if(emp.lastname is null,emp.firstname,concat(emp.firstname,'',emp.lastname)) as name,w.idemployee,
				emp.mobile,emp.emp_code
				FROM employee emp
				left join wallet_account w on (emp.id_employee=w.idemployee and w.active=1)
				) ref on " . ($data['emp_ref_by'] == 1 ? " ref.mobile= sa.referal_code" : "ref.emp_code= sa.referal_code") . " 					
				join wallet_settings ws
				join chit_settings chit
				left join customer cus on (cus.id_customer=sa.id_customer)
				where sa.id_scheme_account=" . $id_scheme_account . "");
        //	print_r($sql);exit;
        $result = $this->db->query($sql)->row_array();
        return $result;
    }
    function get_cusreferrals_datas($id_scheme_account)
    {
        $sql = ("SELECT sa.id_scheme_account, sa.id_scheme,
					s.code,if(cus.lastname is null,cus.firstname,concat(cus.firstname,'',cus.lastname)) as cusname, s.scheme_name,FORMAT(if(chit.cusplan_type=1,if(ws.type=0,ws.value,((s.amount*ws.value)/100)),
				    if(s.cus_refferal=1 && chit.cusplan_type=0,s.cus_refferal_value,'')),0) as referal_value,
					sa.is_refferal_by,sa.referal_code,chit.cusplan_type,chit.schrefbenifit_secadd,
		            chit.empbenefitscrt_type,chit.cusbenefitscrt_type,
					s.cus_refferal,s.cus_refferal_value,ref.name,
					ref.mobile,ref.id_customer as id_customer,ref.id_wallet_account					
					FROM scheme_account sa
					left join scheme s on (sa.id_scheme =s.id_scheme)
					left join(SELECT w.id_customer,w.id_wallet_account,if(c.lastname is null,c.firstname,concat(c.firstname,'',c.lastname)) as name,
					c.mobile, c.cus_ref_code
					FROM customer c
					left join wallet_account w on (c.id_customer=w.id_customer and w.active=1)
					) ref on ref.mobile= sa.referal_code					
					join wallet_settings ws
					join chit_settings chit
					left join customer cus on (cus.id_customer=sa.id_customer)
					where sa.id_scheme_account=" . $id_scheme_account . " and ws.active=1 and ws.id_wallet=1");
        $result = $this->db->query($sql)->row_array();
        return $result;
    }
    function wallet_transactionDB($data)
    {
        $status = $this->db->insert('wallet_transaction', $data);
        return $status;
    }
    public function update_customer_only($data, $id)
    {
        $edit_flag = 0;
        $this->db->where('id_customer', $id);
        $cus_info = $this->db->update('customer', $data);
        return $cus_info;
    }
    public function get_branchWiseLogin()
    {
        $sql = "select cs.branchWiseLogin from chit_settings cs";
        $records = $this->db->query($sql)->row()->branchWiseLogin;
        return $records;
    }
    function get_chkrefschcus_joincount($id_customer)
    {
        $sql = "SELECT sa.id_scheme_account,count(p.id_payment)as payment,sa.id_scheme,
	         sa.is_refferal_by,c.is_refbenefit_crt_cus,c.is_refbenefit_crt_emp
	        FROM  scheme_account sa			
				left join scheme s on(s.id_scheme=sa.id_scheme)
				left join customer c on(sa.id_customer=c.id_customer)
				Left Join payment p On (sa.id_scheme_account=p.id_scheme_account and p.payment_status=1)
			where sa.id_customer=" . $id_customer . "";
        $records = $this->db->query($sql);
        if ($records->num_rows() > 0) {
            if ($records->row()->is_refferal_by == 0 && $records->row()->payment >= 0 && $records->row()->is_refbenefit_crt_cus == 1) {
                return true;
            } else if ($records->row()->is_refferal_by == 1 && $records->row()->payment >= 0 && $records->row()->is_refbenefit_crt_emp == 1) {
                return true;
            }
        }
        return false;
    }
    function get_chkrefschrefcode_joincount($id_customer, $id_scheme, $referalcode)
    {
        $sql = "SELECT count(sa.id_scheme_account)as scheme_account,sa.id_scheme,sa.id_customer,
	         sa.is_refferal_by,c.is_refbenefit_crt
	        FROM  scheme_account sa			
				left join scheme s on(s.id_scheme=sa.id_scheme)
				left join customer c on(sa.id_customer=c.id_customer)
				Left Join payment p On (sa.id_scheme_account=p.id_scheme_account and p.payment_status=1)
			where sa.id_customer=" . $id_customer . " and sa.id_scheme=" . $id_scheme . " and sa.referal_code=" . $referalcode . "";
        //	echo $sql;
        $records = $this->db->query($sql);
        if ($records->num_rows() > 0) {
            $record = $records->row();
            if ($record->is_refferal_by == 0 && $record->scheme_account <= 1) {
                return true;
            }
            if ($record->is_refferal_by == 1 && $record->scheme_account <= 1) {
                return true;
            }
            return false;
        }
    }
    function getpayment_amount($id_payment)
    {
        $sql = "SELECT id_payment,actual_trans_amt from payment where id_payment=" . $id_payment;
        //	echo $sql;
        $records = $this->db->query($sql);
        return $records->row_array();
    }
    function updPayModeBRefTranID($data, $ref_tran_id)
    {
        $this->db->where('ref_trans_id', $ref_tran_id);
        $status = $this->db->update('payment', $data);
        return $status;
    }
    function getPayIds($txnid)
    {
        $sql = "Select  p.due_type,(select e.id_employee from employee e where e.emp_code = sa.referal_code and sa.referal_code != '' and sa.referal_code is not null) as ref_emp_id,sa.id_customer,firstPayment_amt,s.code as group_code,s.sync_scheme_code,sa.id_branch as branch,cs.scheme_wise_acc_no,cs.gent_clientid,firstPayamt_as_payamt,s.firstPayamt_maxpayable,p.id_payment,sa.id_scheme_account,sa.scheme_acc_number,sa.id_scheme,cs.schemeacc_no_set,cs.receipt_no_set,cs.scheme_wise_receipt,p.ref_trans_id,cs.edit_custom_entry_date,sa.custom_entry_date,p.payment_amount,flexible_sch_type,p.id_branch,s.is_lucky_draw, s.max_members, s.code,s.one_time_premium,p.id_transaction,p.offline_tran_uniqueid,b.warehouse,
	p.payment_ref_number,p.gst_type,IFNULL(p.gst_amount,0) as gst_amount,IFNULL(p.discountAmt,0) as discountAmt,IFNULL(p.actual_trans_amt,0) as actual_trans_amt,
	p.payment_type,p.payment_mode,cs.allow_referral,s.agent_refferal,s.agent_credit_type,s.emp_refferal,sa.referal_code,s.firstPayment_as_wgt,sa.firstpayment_wgt,p.metal_weight,p.redeemed_amount,wa.id_wallet_account,p.date_payment 
			 From payment p
			 left join scheme_account sa on sa.id_scheme_account=p.id_scheme_account
			 LEFT JOIN scheme s ON s.id_scheme = sa.id_scheme
			 LEFT JOIN branch b ON b.id_branch = p.id_branch
			 LEFT JOIN wallet_account wa ON wa.id_customer=sa.id_customer
			 join chit_settings cs
			 Where p.ref_trans_id='" . $txnid . "'";
        $pay = $this->db->query($sql)->result_array();
        $sql1 = "Select p.due_type, (select e.id_employee from employee e where e.emp_code = sa.referal_code and sa.referal_code != '' and sa.referal_code is not null) as ref_emp_id,sa.id_customer,firstPayment_amt,s.code as group_code,s.sync_scheme_code,sa.id_branch as branch,cs.scheme_wise_acc_no,cs.gent_clientid,firstPayamt_as_payamt,s.firstPayamt_maxpayable,p.id_adv_payment,sa.id_scheme_account,sa.scheme_acc_number,sa.id_scheme,cs.schemeacc_no_set,cs.receipt_no_set,cs.scheme_wise_receipt,p.ref_trans_id,cs.edit_custom_entry_date,sa.custom_entry_date,p.payment_amount,flexible_sch_type,p.id_branch,s.is_lucky_draw, s.max_members, s.code,s.one_time_premium,p.id_transaction,b.warehouse,
	p.payment_ref_number,p.gst_type,IFNULL(p.gst_amount,0) as gst_amount,IFNULL(p.discountAmt,0) as discountAmt,IFNULL(p.actual_trans_amt,0) as actual_trans_amt,
	p.payment_type,p.payment_mode,cs.allow_referral,s.agent_refferal,s.agent_credit_type,s.emp_refferal,sa.referal_code,p.date_payment 
			 From general_advance_payment p
			 left join scheme_account sa on sa.id_scheme_account=p.id_scheme_account
			 LEFT JOIN scheme s ON s.id_scheme = sa.id_scheme
			 LEFT JOIN branch b ON b.id_branch = p.id_branch
			 join chit_settings cs
			 Where p.ref_trans_id='" . $txnid . "'";
        $g_adv = $this->db->query($sql1)->result_array();
        if (sizeof($pay) > 0) {
            return $pay;
        } elseif (sizeof($g_adv) > 0) {
            return $g_adv;
        } else {
            return [];
        }
    }
    function getBranchGateways($branch_id)
    {
        //$sql="SELECT * from gateway_branchwise where is_default=1 and id_branch=".$branch_id."";
        $sql = "SELECT id_pg,id_branch,pg_name,pg_code,param_1,param_2,param_3,param_4,api_url,type,pg_icon,pg_icon,saveCard,saveCard,debitCard,netBanking,creditCard,date_add,is_primary_gateway,description,active from gateway where active=1 and is_default=1 " . ($branch_id != '' && ($this->session->userdata('cost_center') == 1 || $this->session->userdata('cost_center') == 3) ? "and id_branch=" . $branch_id . "" : '') . " order by sort  DESC";
        $result = $this->db->query($sql);
        //print_r($this->db->last_query());exit;
        if ($result->num_rows() > 0) {
            foreach ($result->result_array() as $row) {
                $file = base_url() . 'admin/assets/img/gateway/' . $row['pg_icon'];
                $img_path = ($row['pg_icon'] != null ? (file_exists('admin/assets/img/gateway/' . $row['pg_icon']) ? $file : null) : null);
                $record[] = array('pg_name' => $row['pg_name'], 'pg_code' => $row['pg_code'], 'netBanking' => $row['netBanking'], 'is_primary_gateway' => $row['is_primary_gateway'], 'active' => $row['active'], 'description' => $row['description'], 'id_pg' => $row['id_pg'], 'saveCard' => $row['saveCard'], 'creditCard' => $row['creditCard'], 'debitCard' => $row['debitCard'], 'id_branch' => $row['id_branch'], 'pg_icon' => $img_path);
            }
        }
        return $record;
    }
    function getBranchGatewayData($branch_id, $pg_id)
    {
        $sql = "SELECT param_1,param_2,param_3,param_4,type,pg_code,id_pg,id_branch,api_url from gateway where  id_pg=" . $pg_id . " " . ($branch_id != '' && ($this->session->userdata('cost_center') == 1) ? "and id_branch=" . $branch_id . "" : '') . "";
        //	print_r($sql);exit;
        $result = $this->db->query($sql)->row_array();
        return $result;
    }
    function getGateway($pg_id)
    {
        $sql = "SELECT param_1,param_2,param_3,param_4,type,pg_code,pg_name from gateway where  id_pg=" . $pg_id;
        $result = $this->db->query($sql)->row_array();
        return $result;
    }
    function firstPayamt_payable()
    {
        $sql = "Select c.firstPayamt_payable FROM chit_settings c where c.id_chit_settings = 1";
        return $this->db->query($sql)->row()->firstPayamt_payable;
    }
    function firstPayamt_as_payamt()
    {
        $sql = "Select c.firstPayamt_as_payamt FROM chit_settings c where c.id_chit_settings = 1";
        return $this->db->query($sql)->row()->firstPayamt_as_payamt;
    }
    function get_ajax_giftdetails($customerid)
    {
        $result = $this->db->query("select amount,payment_mode,payment_status from gift_card_payment where id_customer = " . $customerid . "");
        foreach ($result->result_array() as $row) {
            $records[] = array(
                'amount' => $row['amount'],
                'payment_mode' => $row['payment_mode'],
                'payment_status' => $row['payment_status']
            );
        }
        ;
        return $records;
    }
    function generateDueDate($id_scheme_account, $dueType)
    {
        if ($dueType == 'ND') {
            $sql = $this->db->query("select due_month,due_year from payment where payment_status <= 2 and due_month=" . date('m') . " AND due_year=" . date('Y') . " AND id_scheme_account=" . $id_scheme_account);
            if ($sql->num_rows() == 0) {
                return array("due_month" => date('m'), "due_year" => date('Y'));
            } else {
                $last_paid_sql = $this->db->query("select due_month,due_year from payment where payment_status <= 2 and due_month is not null and id_scheme_account=" . $id_scheme_account . " Order By id_payment DESC LIMIT 1");
                $last_m = $last_paid_sql->row_array();
                $d = $last_m['due_year'] . "-" . $last_m['due_month'] . "-01";
                $month = date('m', strtotime("+1 months", strtotime($d)));
                $due_year = date('Y', strtotime("+1 months", strtotime($d)));
                return array("due_month" => $month, "due_year" => $due_year);
            }
        } else if ($dueType == 'AD') {
            $last_paid_sql = $this->db->query("select due_month,due_year from payment where payment_status <= 2 and due_month is not null and id_scheme_account=" . $id_scheme_account . " Order By id_payment DESC LIMIT 1");
            $last_m = $last_paid_sql->row_array();
            $d = $last_m['due_year'] . "-" . $last_m['due_month'] . "-01";
            $month = date('m', strtotime("+1 months", strtotime($d)));
            $due_year = date('Y', strtotime("+1 months", strtotime($d)));
            return array("due_month" => $month, "due_year" => $due_year);
        } else {
            return array("due_month" => date('m'), "due_year" => date('Y'));
        }
    }
    function checkDueDate($payData)
    {
        foreach ($payData as $r) {
        }
    }
    function get_current_month_payment($id_scheme_account)
    {
        $sql = "SELECT * from payment p
             WHERE date_format(p.date_payment,'%m')=date_format(CURRENT_DATE(),'%m') and p.id_scheme_account=" . $id_scheme_account . " Order By p.id_payment DESC";
        $result = $this->db->query($sql)->row_array();
        return $result;
    }
    function getStatusByTxnId($ref_id)
    {
        $sql = "SELECT payment_status from payment p WHERE ref_trans_id='" . $ref_id . "'";
        $result = $this->db->query($sql)->row_array();
        return $result;
    }
    //acc no & clientid upd to cus reg tab//HH
    function update_cusreg($data, $id)
    {
        $this->db->where('id_scheme_account', $id);
        $status = $this->db->update('customer_reg', array('scheme_ac_no' => $data['scheme_acc_number'], 'clientid' => $data['ref_no']));
        return $status;
    }
    function update_trans($data, $id)
    {
        $this->db->where('id_scheme_account', $id);
        $status = $this->db->update('transaction', array('client_id' => $data['ref_no']));
        return $status;
    }
    function generateClientID($cliData)
    {
        $clientID = 'LMX/' . $cliData['id_branch'] . '/' . $cliData['sync_scheme_code'] . '/' . $cliData['ac_no'];
        return $clientID;
    }
    // Lucky Draw scheme group data
    function updateGroupCode($payData)
    {
        $max_members = $payData['max_members'];
        $id_scheme = $payData['id_scheme'];
        $id_branch = $payData['id_branch'];
        $id_scheme_account = $payData['id_scheme_account'];
        $group_code = $payData['code']; // Scheme Master Code
        // scheme_group - status => 0 - Upcoming, 1 - Active, 2 - Reached Limit, 3 - Group closed
        // Get active group
        $sql_1 = $this->db->query("SELECT id_scheme_group,group_code_param_1,group_code_param_2,group_code,group_code_suffix FROM `scheme_group` where " . ($id_branch > 0 ? ' id_branch = ' . $id_branch . ' and' : '') . " status = 1 and id_scheme = " . $id_scheme);
        if ($sql_1->num_rows() > 0) {
            $active_group = $sql_1->row_array();
            // Get group members count
            $sql_2 = $this->db->query("SELECT count(id_scheme_account) as accounts FROM `scheme_account` where" . ($id_branch > 0 ? ' id_branch = ' . $id_branch . ' and' : '') . " id_scheme = " . $id_scheme . " and group_code = '" . $active_group['group_code'] . "' and scheme_acc_number is not null and scheme_acc_number != '' ");
            $accounts = $sql_2->row()->accounts;
            if ($accounts >= $max_members) { // Reached group limit, update status of current group and create new scheme group
                // Update current group status as 2
                $updData = array(
                    "status" => 2,  // Reached Limit
                    "last_update" => date("Y-m-d H:i:s")
                );
                $this->db->where('id_scheme_group', $active_group['id_scheme_group']);
                $upd_status = $this->db->update("scheme_group", $updData);
                // Create new scheme group
                $group_code_suffix = ++$active_group['group_code_suffix'];
                $group_code_param_1 = $active_group['group_code_param_1'];
                $group_code_param_2 = $active_group['group_code_param_2'];
                $group_code = $group_code_param_1 . '' . $group_code_param_2 . '' . $group_code_suffix;
                $insData = array(
                    "id_scheme" => $id_scheme,
                    "id_branch" => $id_branch,
                    "group_code" => $group_code,
                    "group_code_param_1" => $group_code_param_1,
                    "group_code_param_2" => $group_code_param_2,
                    "group_code_suffix" => $group_code_suffix,
                    "status" => 1,
                    "date_add" => date("Y-m-d H:i:s")
                );
                $ins = $this->insertData($insData, "scheme_group");
            } else if ($accounts < $max_members) {
                $id_scheme_group = $active_group['id_scheme_group'];
                $group_code = $active_group['group_code'];
            }
        } else { 			// Create new scheme group 
            $insData = array(
                "id_scheme" => $id_scheme,
                "id_branch" => $id_branch,
                "group_code" => $group_code . '1',
                "group_code_param_1" => $group_code,
                "group_code_param_2" => NULL,
                "group_code_suffix" => 1,
                "status" => 1,
                "date_add" => date("Y-m-d H:i:s")
            );
            $group_code = $group_code . '1';
            $ins = $this->insertData($insData, "scheme_group");
        }
        // Update group code in scheme account table
        if (strlen($group_code) > 0) {
            $updAccData = array(
                "group_code" => $group_code,
                "date_upd" => date("Y-m-d H:i:s")
            );
            $this->db->where('id_scheme_account', $id_scheme_account);
            $status = $this->db->update("scheme_account", $updAccData);
            //echo $this->db->last_query();//exit;
            return array("status" => $status, "group_code" => $group_code);
        } else {
            return array("status" => FALSE, "group_code" => NULL);
        }
    }
    function branchesData()
    {
        $sql = "SELECT id_branch,name,short_name FROM branch b  where show_to_all != 3 order by sort ";
        $branch = $this->db->query($sql)->result_array();
        return $branch;
    }
    function getRateFields($id_metal)
    {
        $sql = $this->db->query("SELECT rate_field,market_rate_field FROM `ret_metal_purity_rate` where id_metal=" . $id_metal . " and id_purity=1");
        return $sql->row_array();
    }
    function get_payment_history($id_metal)
    {
        $payhistory = $this->db->query("select sch.code,sg.group_code as scheme_group_code, UNIX_TIMESTAMP(Date_Format(sg.start_date,'%Y-%m-%d')) as group_start_date,  UNIX_TIMESTAMP(Date_Format(sg.end_date,'%Y-%m-%d')) as  group_end_date,  cs.has_lucky_draw, id_payment, DATE_FORMAT(date_payment,'%d-%m-%Y') AS date_payment, metal_rate, metal_weight,pay.receipt_no,DATE_FORMAT(pay.date_add,'%d-%m-%Y') as paid_date,ifnull(pay.add_charges,0.00) as add_charges,pay.due_type,if(pay.payment_mode = NULL,pm.short_code,pay.payment_mode)as payment_mode,pay.id_branch,id_transaction,psm.id_status_msg as id_pay_status,psm.payment_status, sa.scheme_acc_number ,ref_no AS client_id, scheme_name,sch.code,pay.payment_type,pay.gst,pay.gst_type,
		sch.charge_head,br.name as branch_name,br.id_branch,if(scheme_type = 0,'Amount Scheme','Weight Scheme') as scheme_type,cs.currency_name,cs.currency_symbol,psm.color,(CASE WHEN (pay.due_type='A' or pay.due_type='P') and pay.payment_status!=1 THEN pay.`act_amount` ELSE pay.payment_amount END) as  payment_amount,sch.is_lucky_draw as is_lucky_draw FROM payment as pay
		left join scheme_account AS sa on sa.id_scheme_account = pay.id_scheme_account
		Left Join scheme_group sg On (sa.group_code = sg.group_code )
		Left Join branch br On (pay.id_branch=br.id_branch)
		left join scheme as sch on sch.id_scheme = sa.id_scheme
		left join customer as cus on  cus.id_customer = sa.id_customer
		join chit_settings cs
		left join metal m on(m.id_metal=sch.id_metal)
		LEFT Join payment_status_message psm ON (pay.payment_status=psm.id_status_msg)
		Left Join payment_mode pm On (pay.payment_mode=pm.short_code)
		WHERE cus.mobile='" . $this->session->userdata('username') . "' and sa.active=1 " . ($id_metal != '' && $id_metal != 0 ? "and sch.id_metal=" . $id_metal . " " : '') . "");
        //print_r($this->db->last_query());exit;
        return $payhistory->result_array();
    }
    function insert_agent_transaction($data)
    {
        $status = $this->db->insert('ly_customer_loyalty_transaction', $data);
        return $status;
    }
    /*Coded by Haritha - 13 09-2022
    Employee and Agent Incentive Functions */
    function get_Incentivedata($id_scheme, $id_scheme_account, $type, $id_payment)
    {
        $sql = $this->db->query("SELECT * FROM scheme_incentive_settings where credit_to = " . $type . " and id_scheme=" . $id_scheme);
        $data = array();
        if ($sql->num_rows() > 0) {
            $sql1 = $this->db->query("SELECT sa.agent_code,sa.id_agent,sa.id_customer as cus_loyal_cus_id,is_refferal_by,sa.id_scheme,p.payment_amount,p.due_type,
					(select IFNULL(IF(sa.is_opening=1,IFNULL(sa.paid_installments,0)+ IFNULL(if(s.scheme_type = 1 and s.min_weight != s.max_weight, COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')), sum(pay.no_of_dues)),0), 
					if(s.scheme_type = 1 and s.min_weight != s.max_weight or (s.scheme_type=3 AND s.firstPayamt_as_payamt = 0), COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')), sum(pay.no_of_dues))) ,0) 
					from payment pay where pay.payment_status=1 and pay.id_scheme_account=sa.id_scheme_account group by pay.id_scheme_account) as paid_installments,date(p.date_payment) as payment_date
					     FROM scheme_account sa
					    left join scheme s on (s.id_scheme=sa.id_scheme)
					    left join payment p on (sa.id_scheme_account=p.id_scheme_account) 
						where sa.id_scheme_account=" . $id_scheme_account . "  and  p.payment_status=1 and p.id_payment=" . $id_payment . " group by sa.id_scheme_account");
            $acc_data = $sql1->row_array();
            //echo $this->db->last_query();exit;
            $ref_data = $sql->result_array();
            $credit_remark = '';
            if ($acc_data['payment_amount'] != '' && $acc_data['payment_amount'] > 0) {
                foreach ($ref_data as $ref) {
                    //benefit in sch join - type1 (applicable for Agent and Employee)
                    if (($ref['credit_for'] == 0 || $ref['credit_for'] == 1) && ($acc_data['due_type'] == 'ND') && ($ref['from_range'] <= $acc_data['paid_installments'] && $ref['to_range'] >= $acc_data['paid_installments'])) {
                        //calc % and amt based on settings
                        $cash_point = $this->calcIncentiveAmt($ref['credit_type'], $acc_data['payment_amount'], $ref['credit_value']);
                        $credit_remark = 'New Scheme Join';
                        $data[] = array('referal_amount' => $cash_point, 'credit_for' => $ref['credit_for'], 'id_customer' => $acc_data['cus_loyal_cus_id'], 'credit_remark' => $credit_remark, 'id_payment' => $id_payment);
                    } else if ($ref['credit_for'] == 3) {//credit benefit based on no of days - only for Agent credit in Collection App
                        $month_first_day = date('Y-m-01');
                        $no_of_days = $this->dateDiff($month_first_day, $acc_data['payment_date']);
                        if ($no_of_days == 0) {
                            $no_of_days = 1;
                        } else {
                            $no_of_days = $no_of_days;
                        }
                        if ($ref['from_range'] <= $no_of_days && $ref['to_range'] >= $no_of_days) {
                            //calc % and amt based on settings
                            $cash_point = $this->calcIncentiveAmt($ref['credit_type'], $acc_data['payment_amount'], $ref['credit_value']);
                            $credit_remark = 'Credits between date ' . $ref['from_range'] . ' to ' . $ref['to_range'];
                            $data[] = array('referal_amount' => $cash_point, 'credit_for' => $ref['credit_for'], 'id_customer' => $acc_data['cus_loyal_cus_id'], 'credit_remark' => $credit_remark, 'id_payment' => $id_payment);
                        }
                    } else if ($ref['credit_for'] == 2) // date wise credits ( Sunday Collection benefits only for Collection App)
                    {
                        $nameOfDay = date('l', strtotime($acc_data['payment_date']));
                        if ($nameOfDay == $ref['from_range'] || $nameOfDay == $ref['to_range']) {
                            $cash_point = $this->calcIncentiveAmt($ref['credit_type'], $acc_data['payment_amount'], $ref['credit_value']);
                            $credit_remark = $nameOfDay . ' Collection';
                            $data[] = array('referal_amount' => $cash_point, 'credit_for' => $ref['credit_for'], 'id_customer' => $acc_data['cus_loyal_cus_id'], 'credit_remark' => $credit_remark, 'id_payment' => $id_payment);
                        }
                    }
                }
                return $data;
            } else {
                return $data;
            }
        } else {
            return $data;
        }
    }
    function dateDiff($date1, $date2)
    {
        $date1_ts = strtotime($date1);
        $date2_ts = strtotime($date2);
        $diff = $date2_ts - $date1_ts;
        return round($diff / 86400);
    }
    function calcIncentiveAmt($type, $pay_amt, $ref_val)
    {
        $cash_point = 0;
        if ($type == 1) {
            $cash_point = ($pay_amt * $ref_val) / 100;
        } else {
            $cash_point = $ref_val;
        }
        return $cash_point;
    }
    function checkReferalExist($id_payment, $id_sch_acc)
    {
        $sql = $this->db->query("SELECT id_scheme_account,id_payment from ly_customer_loyalty_transaction where id_scheme_account = " . $id_sch_acc . " and id_payment=" . $id_payment);
        if ($sql->num_rows() > 0) {
            return 1;
        } else {
            return 0;
        }
    }
    function checkCreditTransExist($id_scheme_account, $id_payment)
    {
        $r = "SELECT id_sch_ac,id_payment from wallet_transaction where id_sch_ac = " . $id_scheme_account . " and id_payment=" . $id_payment;
        $sql = $this->db->query($r);
        if ($sql->num_rows() > 0) {
            return 1;
        } else {
            return 0;
        }
    }
    function get_empRefExist_datas($id_scheme_account)
    {
        $sql = $this->db->query("SELECT referal_code,is_refferal_by from scheme_account where referal_code != '' and is_refferal_by = 0 and id_scheme_account=" . $id_scheme_account);
        if ($sql->num_rows() > 0) {
            $data = $sql->row_array();
            $referEmpData = $this->db->query("SELECT wa.id_customer,wa.id_wallet_account,mobile,c.firstname as cusname from customer c LEFT JOIN wallet_account wa ON wa.id_customer=c.id_customer where mobile ='" . trim($data['referal_code']) . "'");
            //$cus_id = $referEmpData->row_array();
            //$sql1 = $this->db->query("SELECT referal_code,id_scheme_account,id_customer from scheme_account where id_customer=".$cus_id);
            if ($referEmpData->num_rows() > 0) {
                return $referEmpData->row_array();
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }
    function get_referral_code($id_scheme_account)
    {
        $sql = "SELECT s.is_refferal_by, s.referal_code,s.id_customer FROM scheme_account s where s.id_scheme_account=" . $id_scheme_account . "";
        return $this->db->query($sql)->row_array();
    }
    function get_financialYear()
    {
        $res = $this->db->query("SELECT fin_year_code FROM `ret_financial_year` where fin_status = 1");
        $financial_year = $res->row()->fin_year_code;
        return $financial_year;
    }
    function get_empWallet_datas($id_employee, $id_payment)
    {
        $sql = $this->db->query("SELECT wa.id_wallet_account,c.firstname as cusname from payment p
	    left join wallet_account wa on wa.idemployee=p.id_employee
	    left join scheme_account sa on sa.id_scheme_account=p.id_scheme_account
	    left join customer c on c.id_customer=sa.id_customer where p.id_payment =" . $id_payment . " and p.id_employee =" . $id_employee);
        return $sql->row_array();
    }
    /*ends */
    function get_entrydate($id)
    {
        $sql = "SELECT entry_date as custom_entry_date,cs.edit_custom_entry_date FROM ret_day_closing 
		join chit_settings cs 
		" . ($id != '' && $id > 0 ? "where id_branch=" . $id . " " : '') . "";
        return $this->db->query($sql)->row_array();
    }
    //RHR schemes : by 30 days payment cycle with advance , pending flow.. also has benefits for pre closed and one month bonus maturity closed ....20-09-2023 #AB....
    function get_scheme_details($id_scheme_account)
    {
        $sql = $this->db->query("SELECT s.total_installments,s.grace_days,s.installment_cycle,s.ins_days_duration,
		(SELECT MIN(date(p.date_payment)) FROM payment p WHERE p.payment_status = 1 and p.id_scheme_account = sa.id_scheme_account) as first_payment_date,	
	    s.scheme_name,c.firstname as cus_name,IFNULL(c.lastname,'') as lastname,c.mobile,sa.account_name,concat(s.code,'-',sa.scheme_acc_number) as scheme_acc_number,sa.firstPayment_amt,sa.received_wgt,sa.fixed_metal_rate,sa.fixed_wgt,sa.fixed_rate_on,sa.maturity_date,s.otp_price_fix_type,s.one_time_premium,
	    date_format(sa.start_date,'%d-%m-%Y') as start_date,date_format(sa.fixed_rate_on,'%d-%m-%Y') as fixed_rate_on,IFNULL(s.description,'') as description,
	    s.emp_refferal,s.emp_incentive_closing,s.ref_benifitadd_ins_type,s.ref_benifitadd_ins,sa.id_employee,s.firstPayamt_as_payamt,
	    s.emp_refferal_value
        FROM scheme_account sa 
        LEFT JOIN scheme s ON s.id_scheme=sa.id_scheme
        LEFT JOIN customer c ON c.id_customer=sa.id_customer
        WHERE sa.id_scheme_account=" . $id_scheme_account . "");
        //print_r($this->db->last_query());exit;
        return $sql->row_array();
    }
    function get_due_date_old($due_type, $date_payment, $id_scheme_account)
    {
        $result = [];
        $where = '';
        $sch = $this->get_scheme_details($id_scheme_account);
        $first_payment_date = (!empty($sch['first_payment_date']) ? $sch['first_payment_date'] : $date_payment);
        if ($sch['installment_cycle'] == 2) {  //by days duration cycle
            $c_wh = "and  dt.due_date_from NOT IN (SELECT p.due_date from payment p where p.payment_status = 1 and p.due_date is not null and p.id_scheme_account = sa.id_scheme_account) limit 1";
            if ($due_type == 'ND' || $due_type == '') {
                $where = "and '" . $date_payment . "' BETWEEN dt.due_date_from and dt.due_date_to " . $c_wh . " ";
            } else if ($due_type == 'AD') {
                $where = "and dt.due_date_from >= '" . $date_payment . "'  " . $c_wh . " ";
            } else if ($due_type == 'PD') {
                $where = "and dt.due_date_from <= '" . $date_payment . "' " . $c_wh . " ";
            } else if ($due_type == 'allow_pay') {
                $where = "and  dt.due_date_from NOT IN (SELECT p.due_date from payment p where p.payment_status = 1 and p.due_date is not null and p.id_scheme_account = sa.id_scheme_account)";
            }
            $sql = "SELECT 		if(
				dt.due_date_to <= CURDATE() ,
				'PD',
				if( CURDATE() BETWEEN dt.due_date_from AND dt.due_date_to  , 
					'ND',
					if(
                       dt.due_date_to >= CURDATE()
                        ,'AD','-'
                    )
				) 
			) as due_type,
			dt.installment,dt.due_date_from,dt.due_date_to,dt.grace_date,
			if((('" . $due_type . "' = 'ND' OR '" . $due_type . "' = '') AND '" . $date_payment . "' BETWEEN dt.due_date_from and dt.grace_date) OR ('" . $due_type . "' = 'AD')  , '0','1') as is_limit_exceed	
			FROM scheme_account sa
			JOIN (SELECT @sno := @sno + 1 as installment,
				@due_Date_from := if(@sno = 1, '" . $first_payment_date . "',date_add(@pay_date ,INTERVAL " . $sch['ins_days_duration'] . " day )) as due_date_from,
				@due_Date_to := if(@sno = 1, date_add('" . $first_payment_date . "',INTERVAL " . $sch['ins_days_duration'] . " day ),date_add(@due_Date_from,INTERVAL " . $sch['ins_days_duration'] . " day )) as due_date_to,  
				@grace_date := if(@sno = 1, date_add('" . $first_payment_date . "',INTERVAL " . $sch['grace_days'] . " day ),date_add(@due_Date_from,INTERVAL " . $sch['grace_days'] . " day )) as grace_date,
				@pay_date := if(@sno = 1,'" . $first_payment_date . "',@due_Date_from) as due_pay_date
				FROM payment
				join (SELECT @pay_date := if(@sno = 1,'" . $first_payment_date . "',@due_Date_from), @sno := 0 ) as t
				limit " . $sch['total_installments'] . "
			) as dt
			WHERE  sa.id_scheme_account = " . $id_scheme_account . "  " . $where . " ";
            $pay = $this->db->query($sql)->result_array();
            if ($due_type == 'allow_pay') {
                foreach ($pay as $p) {
                    $grouped_dues[$p['due_type']][] = $p;
                }
                foreach ($grouped_dues as $key => $gd) {
                    $result[] = array('due_name' => $key, 'dues_count' => sizeof($gd));
                }
            } else {
                $result = $pay;
            }
        }
        return $result;
    }
    function get_due_date($due_type, $date_payment, $id_scheme_account)
    {
        $result = [];
        $where = '';
        $sch = $this->get_scheme_details($id_scheme_account);
        $now = date('Y-m-d');
        $first_payment_date = (!empty($sch['first_payment_date']) ? $sch['first_payment_date'] : $date_payment);
        $c_wh = "and  dt.due_date_from NOT IN (SELECT p.due_date from payment p where p.payment_status = 1 and p.due_date is not null and p.id_scheme_account = sa.id_scheme_account) limit 1";
        if ($due_type == 'ND' || $due_type == '') {
            $where = "and  date('" . $date_payment . "') BETWEEN dt.due_date_from and dt.due_date_to " . $c_wh . " ";
        } else if ($due_type == 'AD') {
            $where = "and dt.due_date_from >= date('" . $date_payment . "')  " . $c_wh . " ";
        } else if ($due_type == 'PD') {
            $where = "and dt.due_date_from <= date('" . $date_payment . "') " . $c_wh . " ";
        } else if ($due_type == 'allow_pay') {
            $where = "and  dt.due_date_from NOT IN (SELECT p.due_date from payment p where p.payment_status = 1 and p.due_date is not null and p.id_scheme_account = sa.id_scheme_account)";
        } else if ($due_type == 'MND') {
            $where = "and '" . $date_payment . "' BETWEEN dt.due_date_from AND dt.due_date_to ";
        } else if ($due_type == 'current_range') {
            $where = "and  date('" . $date_payment . "') BETWEEN dt.due_date_from and dt.due_date_to";
        } else {
            $where = $c_wh;
        }
        if ($sch['installment_cycle'] == 2) {  //by days duration cycle
            $days_duration = $sch['ins_days_duration'] - 1;
            $grace_days = $sch['grace_days'] - 1;
            $sql = "SELECT 		if(
    			dt.due_date_to < '" . $date_payment . "' ,
    			'PD',
    			if( '" . $date_payment . "' BETWEEN dt.due_date_from AND dt.due_date_to  , 
    				'ND',
    				if(
                       dt.due_date_to >= '" . $date_payment . "'
                        ,'AD','-'
                    )
    			) 
    		) as due_type,
    		dt.installment,dt.due_date_from,dt.due_date_to,dt.grace_date,
    		if((('" . $due_type . "' = 'ND' OR '" . $due_type . "' = '') AND date('" . $date_payment . "') BETWEEN dt.due_date_from and dt.grace_date) OR ('" . $due_type . "' = 'AD')  , '0','1') as is_limit_exceed	
    		FROM scheme_account sa
    		JOIN (SELECT @sno := @sno + 1 as installment,
    			@due_Date_from := if(@sno = 1, '" . $first_payment_date . "',date_add(@pay_date ,INTERVAL " . $sch['ins_days_duration'] . " day )) as due_date_from,
    			@due_Date_to := if(@sno = 1, date_add('" . $first_payment_date . "',INTERVAL " . $days_duration . " day ),date_add(@due_Date_from,INTERVAL " . $days_duration . " day )) as due_date_to,  
    			@grace_date := if(@sno = 1, date_add('" . $first_payment_date . "',INTERVAL " . $grace_days . " day ),date_add(@due_Date_from,INTERVAL " . $grace_days . " day )) as grace_date,
    			@pay_date := if(@sno = 1,'" . $first_payment_date . "',@due_Date_from) as due_pay_date
    			FROM access
    			join (SELECT @pay_date := if(@sno = 1,'" . $first_payment_date . "',@due_Date_from), @sno := 0 ) as t
    			limit " . $sch['total_installments'] . "
    		) as dt
    		WHERE  sa.id_scheme_account = " . $id_scheme_account . "  " . $where . " ";
            $pay = $this->db->query($sql)->result_array();
        }
        //daily payment cycle
        else if ($sch['installment_cycle'] == 1) {
            $sql = "SELECT 		if(
        		dt.due_date_to < '" . $date_payment . "' ,
        		'PD',
        		if( '" . $date_payment . "' = dt.due_date_from  , 
        			'ND',
        			if(
                       dt.due_date_to > '" . $date_payment . "'
                        ,'AD','-'
                    )
        		) 
        	) as due_type,
        	dt.installment,dt.due_date_from,dt.due_date_to
        	FROM scheme_account sa
        	JOIN (SELECT @sno := @sno + 1 as installment,
        		@due_Date_from := if(@sno = 1, '" . $first_payment_date . "',date_add(@pay_date ,INTERVAL 1 day )) as due_date_from,
        		@due_Date_to := @due_Date_from  as due_date_to,  
        		@pay_date := if(@sno = 1,'" . $first_payment_date . "',@due_Date_from) as due_pay_date
        		FROM access
        		join (SELECT @pay_date := if(@sno = 1,'" . $first_payment_date . "',@due_Date_from), @sno := 0 ) as t
        		limit " . $sch['total_installments'] . "
        	) as dt
        	WHERE  sa.id_scheme_account = " . $id_scheme_account . "  " . $where . " ";
            $pay = $this->db->query($sql)->result_array();
        }
        //monthly cycle    
        else if ($sch['installment_cycle'] == 0) {
            $sql = "SELECT 		if(
            			dt.due_date_to < '" . $date_payment . "' ,
            			'PD',
            			if( '" . $date_payment . "' BETWEEN dt.due_date_from AND dt.due_date_to  , 
            				'ND',
            				if(
                               dt.due_date_to >= '" . $date_payment . "'
                                ,'AD','-'
                            )
            			) 
            		) as due_type,
                    dt.installment,
                    dt.due_date_from,
                    dt.due_date_to 
                    FROM scheme_account sa 
                    JOIN (SELECT @sno := @sno + 1 as installment, 
                          @due_Date_from := if(@sno = 1, date_format('" . $first_payment_date . "','%Y-%m-01'),date_add(@pay_date ,INTERVAL 1 month )) as due_date_from, 
                          @due_Date_to := LAST_DAY(@due_Date_from) as due_date_to, 
                          @pay_date := if(@sno = 1,date_format('" . $first_payment_date . "','%Y-%m-01'),@due_Date_from) as due_pay_date 
                          FROM access 
                          join (SELECT @pay_date := if(@sno = 1,date_format('" . $first_payment_date . "','%Y-%m-01'),@due_Date_from), @sno := 0 ) as t limit " . $sch['total_installments'] . " 
                         ) as dt 
                    WHERE sa.id_scheme_account = " . $id_scheme_account . "  " . $where . " ";
            $pay = $this->db->query($sql)->result_array();
        }
        if ($due_type == 'allow_pay') {
            foreach ($pay as $p) {
                $grouped_dues[$p['due_type']][] = $p;
            }
            foreach ($grouped_dues as $key => $gd) {
                $result[] = array('due_name' => $key, 'dues_count' => sizeof($gd));
            }
        } else {
            $result = $pay;
        }
        return $result;
    }
    //RHR schemes ends
    function updateAtData($data, $id_field, $id_value, $table)
    {
        $edit_flag = 0;
        $this->db->where($id_field, $id_value);
        $status = $this->db->update($table, $data);
        return ($edit_flag == 1 ? 1 : 0);
    }
    function getPaidInsData($id_scheme_account)
    {
        $date = date('Y-m-d');
        $acc = $this->db->query("select sa.id_scheme_account,sa.start_date,s.total_installments,s.installment_cycle, s.ins_days_duration,sa.maturity_date,sa.lapse_date,
	                        IF(sa.is_opening=1,
                              IFNULL((sa.paid_installments + COUNT(Distinct Date_Format(pay.date_payment,'%Y%m'))),0),
                               if(s.installment_cycle = 0,
                                  IFNULL(IF(s.min_chance=s.max_chance,COUNT(pay.id_payment),COUNT(Distinct Date_Format(pay.date_payment,'%Y%m'))),0),
                                  if(s.installment_cycle = 1,
                                	  IFNULL(IF(s.min_chance=s.max_chance,COUNT(pay.id_payment),COUNT(Distinct Date_Format(pay.date_payment,'%Y%m%d'))),0),
                                	  if(s.installment_cycle = 2,
                                		IFNULL(COUNT( Date_Format(pay.due_date,'%Y%m')),0),
                                		IFNULL(COUNT(Distinct Date_Format(pay.date_payment,'%Y%m')),0)
                                	  )
                                  )
                               )
                            ) as old_paid_installments,
                            IFNULL(COUNT(Distinct pay.installment),0) as paid_installments,
                            if(s.installment_cycle = 0,
                               PERIOD_DIFF(date_format('" . $date . "','%Y%m'),date_format(sa.start_date,'%Y%m'))+1,
                               if(s.installment_cycle = 1,
                                  DATEDIFF(date_format('" . $date . "','%Y%m%d'),date_format(sa.start_date,'%Y%m%d'))+1,
                                  if(s.installment_cycle = 2,
                                  CEIL((DATEDIFF(date_format('" . $date . "','%Y%m%d'),date_format(sa.start_date,'%Y%m%d'))+1) /s.ins_days_duration),'-'
                                    )
                                 )
                              ) 
                              as ins_till_date
                    from payment pay 
					left join scheme_account  sa on sa.id_scheme_account = pay.id_scheme_account
					left join scheme s on s.id_scheme = sa.id_scheme
                    where pay.payment_status=1 and sa.id_scheme_account = " . $id_scheme_account . "
                    group by sa.id_scheme_account")->row_array();
        return $acc;
    }
    function getSchemeData($id_sch_acc)
    {
        $sql = $this->db->query("SELECT s.maturity_type,s.maturity_days,s.interest,s.apply_benefit_by_chart,sa.id_scheme,
            Date_format(sa.start_date,'%Y-%m%-%d') as start_date,sa.maturity_date,
            			date_add(Date_format(sa.start_date,'%Y-%m%-%d'),INTERVAL s.maturity_days DAY) as calc_maturity_date,
            			s.installment_cycle,s.total_installments,s.ins_days_duration
            from scheme_account sa
            left join scheme s on s.id_scheme=sa.id_scheme
            where sa.id_scheme_account=" . $id_sch_acc);
        return $sql->row_array();
    }
    function insertBatchData($data, $table)
    {
        $insert_flag = 0;
        $insert_flag = $this->db->insert_batch($table, $data);
        if ($this->db->affected_rows() > 0) {
            return 1;
        } else {
            return 0;
        }
    }
    /** Maturity Type : 4
     * Fixed Flexible by lapse 
     * Calculate lapse date & maturity date for first payment and store against scheme account
     * Revise the maturity date on pending dues that account has 
     * Extend the maturity date based on installment cycle
     * First calculation : add total installments
     * Further payments : add if pending dues available
     * Installment cycle : 0 (Monthly cycle) => Added no of pending dues as MONTHS
     * 		EX: 2 PD means; +2 MONTHS
     * Installment cycle : 1 (Daily cycle) => Added no of pending dues as DAYS
     * 		EX: 2 PD means; +2 DAYS
     * Installment cycle : 2 (Days duration pay) => Added no of pending dues as DAYS
     * 		EX: 2 PD & Days(Installment Cycle) = 7 days means; 
     * 		(2PD * 7) = +14 DAYS
     * calculate_maturityLapse_Date() - to store maturity & lapse date first time and maturity date revisal... 
     * getPaidInsData() - to get paid ins & must be paid till date based on installment cycle 
     * Coded On : 20-08-2024; Coded By: Abi
     */
    function calculate_maturityLapse_Date($id_scheme_account)
    {
        $unpaid = 0;
        $status = TRUE;
        //Get scheme settings by id_scheme_account...
        $sch = $this->getSchemeData($id_scheme_account);
        //Get paid installments..
        $acc = $this->getPaidInsData($id_scheme_account);
        $start_date = $acc['start_date'];
        $paid_ins = $acc['paid_installments'];
        $must_paid = $acc['ins_till_date'];
        //total ins to be added for lapse...
        if ($sch['installment_cycle'] == 0) {
            $ld_ins = $sch['total_installments'];  //ex: for monthly scheme; 11 months
            $period = 'months';
        } else if ($sch['installment_cycle'] == 1) {
            $ld_ins = $sch['total_installments'];   //ex: for daily scheme; 180 days
            $period = 'days';
        } else if ($sch['installment_cycle'] == 2) {
            $ld_ins = $sch['total_installments'] * $sch['ins_days_duration'];   //ex: for weekly scheme; 52 * 7 = 364 days ie., 52 weeks
            $period = 'days';
        }
        //calculate unpaid ins...
        if ($must_paid >= $sch['total_installments']) {
            $unpaid = ($sch['total_installments'] - $paid_ins);
        } else if ($must_paid < $sch['total_installments']) {
            if ($must_paid > $paid_ins) {
                $unpaid = ($must_paid - $paid_ins);
            }
        }
        //update dates...
        if ($paid_ins > 0 && $unpaid > 0) {
            if ($sch['installment_cycle'] == 0) {
                $md_ins = $unpaid + $sch['total_installments'];
                $period = 'months';     //ex: for monthly scheme; 2 unpaid months
            } else if ($acc['installment_cycle'] == 1) {
                $md_ins = $unpaid + $sch['total_installments'];
                $period = 'days';       //ex: for days scheme; 2 unpaid days
            } else if ($acc['installment_cycle'] == 2) {
                $md_ins = ($unpaid + $sch['total_installments']) * $acc['ins_days_duration'];
                $period = 'days';       //ex: for weekly scheme; 2 unpaid weeks 2*7 = 14days
            }
            if ($md_ins > 0) {
                if ($period == 'days') {
                    $revised_maturityDate = date('Y-m-d', strtotime($start_date . '+' . $md_ins . ' ' . $period));
                } else {
                    $revised_maturityDate = date('Y-m-01', strtotime($start_date . '+' . $md_ins . ' ' . $period));
                }
                $updAcc = array('maturity_date' => $revised_maturityDate);
                $status = $this->updateAtData($updAcc, 'id_scheme_account', $id_scheme_account, 'scheme_account');
            }
        } else {
            if ($period == 'days') {
                $maturity_date = date('Y-m-d', strtotime($start_date . ' + ' . $ld_ins . ' ' . $period));
            } else {
                $maturity_date = date('Y-m-01', strtotime($start_date . ' + ' . $ld_ins . ' ' . $period));
            }
            $updAcc = array(
                'maturity_date' => $maturity_date,
                'lapse_date' => $maturity_date
            );
            $status = $this->updateAtData($updAcc, 'id_scheme_account', $id_scheme_account, 'scheme_account');
        }
        //update paid installments against account...
        //$updAcc['total_paid_ins'] = $acc['paid_installments'];// echo '<pre>';print_r($updAcc);exit;
        return $status;
    }
}
?>
<?php
if( ! defined('BASEPATH')) exit('No direct script access allowed');
class ret_dashboard_model extends CI_Model
{
	function __construct()
    {
        parent::__construct();
    }

    function get_estimation($from_date,$to_date)
    {
	    		$sql=$this->db->query("select count(est.estimation_id) as estimation  
	    							 from ret_estimation est
	    					          where(date(est.created_time) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')");
	    		 return $sql->row_array();
    }
	function get_dashboard_estimation($from_date,$to_date,$id_branch)
	{
		$sql = $this->db->query("SELECT (SELECT count(estimation_id) AS totestimation 
										FROM ret_estimation as est WHERE date(est.estimation_datetime) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."'  ".($id_branch!='' && $id_branch>0 ? " and est.id_branch=".$id_branch."" :'').") as created, 
										(select count(*) from (SELECT count(estimation_id) AS totestimation 
										FROM ret_estimation as est 
										LEFT JOIN ret_estimation_items AS estitm ON estitm.esti_id = est.estimation_id 
										WHERE estitm.purchase_status = 1 AND date(est.estimation_datetime) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."' ".($id_branch!='' && $id_branch>0 ? " and est.id_branch=".$id_branch."" :'')." GROUP BY esti_id) as sold) as sold, 
										(select count(*) from (SELECT count(estimation_id) AS totestimation 
										FROM ret_estimation as est 
										LEFT JOIN ret_estimation_items AS estitm ON estitm.esti_id = est.estimation_id 
										WHERE estitm.purchase_status = 0 AND date(est.estimation_datetime) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."' ".($id_branch!='' && $id_branch>0 ? " and est.id_branch=".$id_branch."" :'')." GROUP BY esti_id) as unsold) as unsold");
		// print_r($this->db->last_query());exit;
		return $sql->row_array();										
	}
	function get_dashboard_estimation_details($from_date,$to_date,$id_branch)
	{
		$sql = $this->db->query("SELECT estimation_id, esti_no, br.name as branchname, est.id_branch as branchid, 
		if(esti.purchase_status = 1, 'Sold', if(esti.purchase_status = 0, 'Unbilled', if(esti.purchase_status = 2, 'Returned', 'In Process'))) as purchase_status,
		cus.firstname as cusname, 
		cus.mobile as cusmobile, total_cost as estamount 
		FROM ret_estimation as est 
		LEFT JOIN ret_estimation_items as esti ON esti.esti_id = est.estimation_id
		LEFT JOIN branch as br ON br.id_branch = est.id_branch
		LEFT JOIN customer as cus ON cus.id_customer = est.cus_id 
		WHERE date(est.estimation_datetime) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."'
		".($id_branch!='' && $id_branch>0 ? " and est.id_branch=".$id_branch."" :'')."
		GROUP BY estimation_id");
		return $sql->result_array();
	}
	/*function get_dashboard_billings($from_date,$to_date,$id_branch)
	{
		$sql = $this->db->query("SELECT count(*) as bills, ifnull(sum(tot_bill_amount),0) as billamount FROM ret_billing as bill WHERE date(bill.bill_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."' and bill.bill_status=1 ".($id_branch!='' && $id_branch>0 ? " and bill.id_branch=".$id_branch."" :'')." ");
		return $sql->row_array();
	}*/
	
	function get_dashboard_billings($from_date,$to_date,$id_branch)
	{
		   $sql = $this->db->query("SELECT IFNULL(sum(bill_det.net_wt),0) as gold_wt from ret_billing as bill
           left JOIN ret_bill_details  as bill_det on(bill_det.bill_id=bill.bill_id)
           left join ret_product_master as pro on(pro.pro_id=bill_det.product_id)
           left join ret_category as cat on(cat.id_ret_category=pro.cat_id)
           left join metal as m on(m.id_metal=cat.id_metal)
           left join branch b on (b.id_branch=bill.id_branch)
           where bill.bill_status=1 and m.id_metal=1  ".($id_branch!='' && $id_branch>0 ? " and bill.id_branch=".$id_branch."" :'')."
           and date(bill.bill_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."'");
			// print_r($this->db->last_query());exit;
		   return $sql->row_array();
	}
	function get_dashboard_billings_sl_wt($from_date,$to_date,$id_branch)
	{
		   $sql = $this->db->query("SELECT IFNULL(sum(bill_det.net_wt),0) as silver_wt from ret_billing as bill
           left JOIN ret_bill_details  as bill_det on(bill_det.bill_id=bill.bill_id)
           left join ret_product_master as pro on(pro.pro_id=bill_det.product_id)
           left join ret_category as cat on(cat.id_ret_category=pro.cat_id)
           left join metal as m on(m.id_metal=cat.id_metal)
           left join branch b on (b.id_branch=bill.id_branch)
           where bill.bill_status=1 and m.id_metal=2  ".($id_branch!='' && $id_branch>0 ? " and bill.id_branch=".$id_branch."" :'')."
           and date(bill.bill_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."'");
			// print_r($this->db->last_query());exit;
		   return $sql->row_array();
	}
	function get_dashboard_billings_mrp($from_date,$to_date,$id_branch)
	{
		   $sql = $this->db->query("SELECT IFNULL(sum(bill_det.item_cost),0) as mrp from ret_billing as bill
         left JOIN ret_bill_details  as bill_det on(bill_det.bill_id=bill.bill_id)
         left join ret_product_master as pro on(pro.pro_id=bill_det.product_id)
         left join ret_category as cat on(cat.id_ret_category=pro.cat_id)
         left join metal as m on(m.id_metal=cat.id_metal)
         left join branch b on (b.id_branch=bill.id_branch)
         where bill.bill_status=1 and pro.sales_mode=1  ".($id_branch!='' && $id_branch>0 ? " and bill.id_branch=".$id_branch."" :'')."
         and date(bill.bill_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."'");
		   return $sql->row_array();
	}

	function get_dashboard_greentag_det($from_date,$to_date,$id_branch)
    {
        $sql=$this->db->query("SELECT IFNULL(sum(tag.gross_wt),0) as tot_sales_wt,IFNULL(count(d.quantity),0) as tot_piece
        FROM ret_bill_details d
        LEFT JOIN ret_billing b ON b.bill_id=d.bill_id
        LEFT JOIN ret_taging tag ON tag.tag_id=d.tag_id
        WHERE tag.tag_status=1 AND tag.tag_mark=1
        and date(b.bill_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."'
        ".($id_branch!='' && $id_branch>0 ? " and b.id_branch=".$id_branch."" :'')."");
        
          return $sql->row_array();
    }
	
	function get_dashboard_old_metal_purchase($from_date, $to_date,$id_branch)
	{
		$sql = $this->db->query("SELECT metal_type, if(metal_type = 1, 'Gold', if(metal_type = 2, 'Silver', '')) as type, 
		ifnull(sum(net_wt),0) as weight, ifnull(sum(rate),0) totalpaid FROM ret_bill_old_metal_sale_details oldmp 
		LEFT JOIN ret_billing as bill ON bill.bill_id = oldmp.bill_id 
		WHERE date(bill.bill_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."' and bill.bill_status=1 ".($id_branch!='' && $id_branch>0 ? " and bill.id_branch=".$id_branch."" :'')." GROUP BY metal_type");
		
		return $sql->result_array();
	}
	function get_dashboard_credit_sales($from_date, $to_date,$id_branch)
	{
		$sql = $this->db->query("SELECT count(*) as tot_credit_bill, ifnull(sum(tot_bill_amount),0) as tot_bill_amt, 
								(SELECT ifnull(sum(amount),0) as creditreceived from ret_issue_receipt as issrec 
								  WHERE issrec.receipt_type = 2 AND date(issrec.bill_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."' 
								  ".($id_branch!='' && $id_branch>0 ? " and issrec.id_branch=".$id_branch."" :'')."
								) as creditreceived 
								FROM ret_billing as bill 
								WHERE bill_type = 8 AND date(bill.bill_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."'
								and bill.bill_status=1 ".($id_branch!='' && $id_branch>0 ? " and bill.id_branch=".$id_branch."" :'')." ");
		// print_r($this->db->last_query());exit;
		return $sql->row_array();
	}
	function get_dashboard_gift_vouchers($from_date, $to_date,$id_branch)
	{
		$sql = $this->db->query("SELECT (SELECT ifnull(sum(amount),0) FROM gift_card as gc WHERE status = 0 AND date(gc.date_add) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."' ".($id_branch!='' && $id_branch>0 ? " and gc.id_branch=".$id_branch."" :'').")as tot_issued,
										(SELECT ifnull(sum(amount),0) FROM gift_card as gc WHERE status = 2 AND date(gc.date_add) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."' ".($id_branch!='' && $id_branch>0 ? " and gc.id_branch=".$id_branch."" :'').") as tot_utlized, 
										(SELECT ifnull(sum(amount),0) FROM gift_card as gc WHERE free_card = 2 AND date(gc.date_add) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."' ".($id_branch!='' && $id_branch>0 ? " and gc.id_branch=".$id_branch."" :'').") as tot_sold");
		return $sql->row_array();
	}

	function get_dashboard_bills_clasfications($from_date, $to_date,$id_branch)
	{
		$bills_clasification = array();
		$sql = $this->db->query("SELECT count(newcus.bills) as totalnewcusbill, ifnull(sum(newcus.total_sale_amt),0) as newcusbillsale, ifnull(sum(newcus.total_sale_wt),0) as newcisbillsalewt FROM (SELECT bill_cus_id, count(*) as bills, sum(tot_bill_amount) as total_sale_amt, sum(gross_wt) as total_sale_wt 
		FROM ret_billing bil
		LEFT JOIN ret_bill_details as bill_det ON bill_det.bill_id = bil.bill_id 
		WHERE date(bill_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."' AND NOT EXISTS(SELECT bill_cus_id FROM ret_billing WHERE date(bill_date) < '".date('Y-m-d',strtotime($from_date))."' GROUP by bill_cus_id) 
		GROUP by bill_cus_id) as newcus");
        
		$newcusbills = $sql->row_array();
		
		$oldcussql = $this->db->query("SELECT count(oldcus.bills) as totaloldcusbill, ifnull(sum(oldcus.total_sale_amt),0) as oldcusbillsale, ifnull(sum(oldcus.total_sale_wt),0) as oldcusbillsalewt FROM (SELECT bill_cus_id, count(*) as bills, sum(tot_bill_amount) as total_sale_amt, sum(gross_wt) as total_sale_wt 
		FROM ret_billing bil
		LEFT JOIN ret_bill_details as bill_det ON bill_det.bill_id = bil.bill_id 
		WHERE date(bill_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."' AND NOT EXISTS(SELECT bill_cus_id FROM ret_billing WHERE date(bill_date) < '".date('Y-m-d',strtotime($from_date))."' GROUP by bill_cus_id) 
		GROUP by bill_cus_id) as oldcus");
		$oldcusbills = $oldcussql->row_array();
		$bills_clasification = array('totalnewcusbill' => $newcusbills['totalnewcusbill'], 'newcusbillsale' => $newcusbills['newcusbillsale'], 'newcisbillsalewt' => $newcusbills['newcisbillsalewt'], 'totaloldcusbill' => $oldcusbills['totaloldcusbill'], 'oldcusbillsale' => $oldcusbills['oldcusbillsale'], 'oldcusbillsalewt' => $oldcusbills['oldcusbillsalewt']);
		return $bills_clasification;

	}
	function get_dashboard_approval_pendings($from_date, $to_date,$id_branch)
	{
		$sql = $this->db->query("SELECT 
		                        (SELECT ifnull(sum(pieces),0)from ret_branch_transfer where status=2 and status!=4 ".($id_branch!='' && $id_branch>0 ?  " and transfer_to_branch=".$id_branch."" :'').") as downloadpending,
		                        (SELECT ifnull(sum(pieces),0)from ret_branch_transfer where status=1 and status!=4 ".($id_branch!='' && $id_branch>0 ?  " and transfer_to_branch=".$id_branch."" :'')." ) as approvalpending
								");
								//print_r($this->db->last_query());exit;
		return $sql->row_array();
	}
	function get_dashboard_lot_tag_details($from_date, $to_date, $id_branch)
	{
		$sql = $this->db->query("SELECT
		(SELECT ifnull(SUM(d.no_of_piece),0) FROM ret_lot_inwards l left JOIN ret_lot_inwards_detail d on d.lot_no=l.lot_no WHERE date(l.lot_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."' ".($id_branch!='' && $id_branch>0 ?  " and l.created_branch=".$id_branch."" :'').") as lot_pcs,
		(SELECT ifnull(SUM(d.gross_wt),0) FROM ret_lot_inwards l left JOIN ret_lot_inwards_detail d on d.lot_no=l.lot_no WHERE date(l.lot_date) BETWEEN  '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."' ".($id_branch!='' && $id_branch>0 ?  " and l.created_branch=".$id_branch."" :'').") as lot_wt,
		(SELECT ifnull(SUM(tag.piece),0)  FROM ret_taging tag WHERE tag.tag_status!=2 AND date(tag.tag_datetime) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."' ".($id_branch!='' && $id_branch>0 ?  " and tag.id_branch=".$id_branch."" :'').") as tagged_pcs,
		(SELECT ifnull(SUM(tag.gross_wt),0)  FROM ret_taging tag WHERE tag.tag_status!=2 AND date(tag.tag_datetime) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."' ".($id_branch!='' && $id_branch>0 ?  " and tag.id_branch=".$id_branch."" :'').") as tagged_wt");
		// print_r($this->db->last_query());exit;
		return $sql->row_array();
	}
	function get_dashboard_orders_details($from_date, $to_date,$id_branch)
	{
		$sql = $this->db->query("SELECT 
								(SELECT count(*) as orderplaced from order_cart as oc WHERE orderstatus = 1 AND date(order_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."' ".($id_branch!='' && $id_branch>0 ?  " and oc.id_branch=".$id_branch."" :'')." ) as orderplaced,
								(SELECT count(*) as orderreceived from order_cart as oc WHERE ortertype = 2 AND orderstatus = 0 AND orderclosed = 0 AND date(order_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."' ".($id_branch!='' && $id_branch>0 ?  " and oc.id_branch=".$id_branch."" :'').") as orderreceived,
								(SELECT count(*) as cart from order_cart as oc WHERE orderstatus = 0 AND date(order_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."' ".($id_branch!='' && $id_branch>0 ?  " and oc.id_branch=".$id_branch."" :'').") as ordersincart");
		//print_r($this->db->last_query());exit;
		return $sql->row_array();
	}
	function get_dashboard_customer_details($from_date, $to_date)
	{
		$sql = $this->db->query("SELECT id_customer, firstname, cus.mobile as mobile, ifnull(cus_img, '') as cus_img, br.name as branchname, cus.id_branch as branchid,
		profile_complete, if(added_by = 1, 'Admin', if(added_by = 2, 'Mobile' , 'Web')) as jointhrough 
		FROM customer as cus LEFT JOIN branch as br ON br.id_branch = cus.id_branch ORDER BY id_customer DESC LIMIT 10");
		return $sql->result_array();
	}
	function get_dashboard_bills_details($from_date, $to_date,$id_branch)
	{
		$sql = $this->db->query("SELECT br.name as branchname, bill.id_branch as branchid, bill_no, cus.firstname as cusname, 
		cus.mobile as cusmobile, tot_bill_amount as billamount,
		(CASE 
			WHEN bill_type = 1 THEN 'Sales' 
			WHEN bill_type = 2 THEN 'Sales & Purchase' 
			WHEN bill_type = 3 THEN 'Sales & Return' 
			WHEN bill_type = 4 THEN 'Purchase' 
			WHEN bill_type = 5 THEN 'Order Advance' 
			WHEN bill_type = 6 THEN 'Advance' 
			WHEN bill_type = 7 THEN 'Sales Return' 
			WHEN bill_type = 8 THEN 'Credit Bill Payment' 
			WHEN bill_type = 9 THEN 'Order Delivery' 
			ELSE 'Chit Pre Close' 
		END) as billtype
		FROM ret_billing as bill 
		LEFT JOIN branch as br ON br.id_branch = bill.id_branch
		LEFT JOIN customer as cus ON cus.id_customer = bill.bill_cus_id
		WHERE date(bill.bill_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."'
		".($id_branch!='' && $id_branch>0 ?  " and bill.id_branch=".$id_branch."" :'')." ");
		return $sql->result_array();
	}
	function get_dashboard_virturaltag_details($from_date, $to_date,$id_branch)
    {
        $sql = $this->db->query("SELECT 
                                        homesale.gross_wt as homesale_wt, 
                                        homesale.item_cost as homesale_cost, 
                                        homesale.pcs as homesale_pcs, 
                                        tagsplit.gross_wt as tagsplit_wt, 
                                        tagsplit.item_cost as tagsplit_cost, 
                                        tagsplit.pcs as tagsplit_pcs 
                                        FROM (SELECT ifnull(SUM(d.gross_wt),0) as gross_wt, 
                                              ifnull(SUM(d.item_cost),0) as item_cost,
                                              ifnull(SUM(d.piece),0) as pcs 
                                              FROM ret_billing b 
                                              LEFT JOIN ret_bill_details d ON d.bill_id=b.bill_id 
                                              LEFT JOIN ret_estimation_items e ON e.est_item_id=d.esti_item_id 
                                              WHERE d.esti_item_id IS NOT null AND d.tag_id is null 
                                              AND e.item_type =2 and b.bill_status =1 AND date(b.bill_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."'
                                              ".($id_branch!='' && $id_branch>0 ?  " and b.id_branch=".$id_branch."" :'').") as homesale,
                                            (SELECT ifnull(SUM(tag.gross_wt - d.gross_wt),0) as gross_wt, 
                                                ifnull(SUM(d.item_cost),0) as item_cost, 
                                                ifnull(SUM(d.piece),0) as pcs 
                                                FROM ret_billing b 
                                                LEFT JOIN ret_bill_details d ON d.bill_id = b.bill_id 
                                                LEFT JOIN ret_estimation_items e ON e.est_item_id=d.esti_item_id 
                                                LEFT JOIN ret_estimation est ON est.estimation_id=e.esti_id 
                                                LEFT JOIN ret_taging tag on tag.tag_id=d.tag_id 
                                                WHERE d.tag_id IS NOT null AND d.is_partial_sale=1 and 
                                                b.bill_status = 1 AND date(b.bill_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."'
                                                ".($id_branch!='' && $id_branch>0 ?  " and b.id_branch=".$id_branch."" :'').") as tagsplit");
                                                // print_r($this->db->last_query());exit;
        return $sql->row_array();
    }
	function get_dashboard_cash_abstarct_details($from_date, $to_date,$id_branch){

		$from 	= date('Y-m-d',strtotime($from_date));
		$to 	= date('Y-m-d',strtotime($to_date));  
		$d1 	= date_create($from);
		$d2 	= date_create($to);
		$FromDt = date_format($d1,"Y-m-d");
		$ToDt 	= date_format($d2,"Y-m-d");
		
		
		$return_data = array("item_details" => array(), "voucher_details" => array(), "chit_details" => array(),"return_details"=>array(),'payment_details'=>array(),'advance_detals'=>array(),'branch_transfer_details'=>array(),'due_details'=>array(),'credit_details'=>array(),'metal_rates'=>array(),"old_matel_details"=>array(),"advance_adjusted"=>array(),"wallet_adjusted"=>array(),"general_adv_details"=>array(),"general_pay"=>array(),"order_adj"=>array(),"home_bill"=>array(),"partly_sale"=>array(),"bill_det"=>array());
		
		//SALES DETAILS
		$items_query = $this->db->query("SELECT ifnull(sum(d.piece),0) as piece,
											ifnull(sum(d.net_wt),0) as net_wt,
											ifnull(sum(d.item_cost),0) as item_cost,
											ifnull(sum(d.item_total_tax),0) as item_total_tax,
											ifnull(sum(d.bill_discount),0) as bill_discount 
											From ret_billing b
											Left JOIN ret_bill_details d on d.bill_id=b.bill_id 
											WHERE  b.bill_id is not null and b.bill_status=1 
											".($FromDt!= '' && $ToDt!='' ? 'and (date(b.bill_date) BETWEEN "'.$FromDt.'" AND "'.$ToDt.'")' : '')." 
											and  d.bill_det_id !='' and (b.bill_type=1 OR b.bill_type=2 OR b.bill_type=3 OR b.bill_type=4 OR b.bill_type=9) 
											".($id_branch!='' && $id_branch!=0 ? " and b.id_branch=".$id_branch."" :'')."
										");
	
		$return_data['item_details'] = $items_query->row_array();
			
		//PURCHASE DETAILS
		$old_metal_query=$this->db->query("SELECT ifnull(sum(s.gross_wt),0) as gross_wt, ifnull(sum(s.dust_wt),0) as dust_wt,
											ifnull(sum(s.stone_wt),0) as stone_wt, ifnull(sum(s.rate),0) as amount,
											ifnull(sum(s.net_wt),0) as net_wt, count(s.old_metal_sale_id) as tot_pur 
											FROM ret_bill_old_metal_sale_details s
											LEFT JOIN ret_billing b on b.bill_id=s.bill_id 
											WHERE b.bill_id is not null and b.bill_status = 1 
											".($FromDt!= '' && $ToDt!='' ? 'and (date(b.bill_date) BETWEEN "'.$FromDt.'" AND "'.$ToDt.'")' : '')." 
											".($id_branch!='' && $id_branch!=0 ? " and b.id_branch=".$id_branch."" :'')." 
										");
		$return_data['old_matel_details'] = $old_metal_query->row_array();
		

		//SALES RETURN
		$return_details=$this->db->query("SELECT ifnull(sum(d.net_wt),0) as net_wt, ifnull(sum(d.item_cost),0) as item_cost, 
										  ifnull(sum(d.item_total_tax),0) as item_total_tax, 
										  ifnull(sum(d.bill_discount),0) as bill_discount,
										  ifnull(sum(d.piece),0) as piece 
										  FROM ret_billing b 
										  LEFT JOIN ret_bill_return_details r ON r.bill_id=b.bill_id 
										  LEFT JOIN ret_bill_details d ON d.bill_det_id=r.ret_bill_det_id 
										  WHERE d.bill_det_id IS NOT null and b.bill_status = 1 
										  ".($id_branch!='' && $id_branch!=0 ? " and b.id_branch=".$id_branch."" :'')." 
										  ".($FromDt!= '' && $ToDt!='' ? 'and (date(b.bill_date) BETWEEN "'.$FromDt.'" AND "'.$ToDt.'")' : '')." 
										");
		$return_data['return_details'] = $return_details->row_array();

	//PAYMENT DETAILS
	$payment_details = $this->db->query("SELECT ifnull(sum(p.payment_amount),0) as payment_amount, p.payment_mode 
	FROM ret_billing_payment p
	LEFT JOIN ret_billing b on b.bill_id=p.bill_id
	where b.bill_id is not null and b.bill_status=1 ".($FromDt!= '' && $ToDt!='' ? ' and (date(b.bill_date) BETWEEN "'.$FromDt.'" AND "'.$ToDt.'")' : '')." 
	and b.bill_type!=6  ".($id_branch!='' && $id_branch!=0 ? " and b.id_branch=".$id_branch."" :'')." GROUP BY payment_mode");
	$return_data['payment_details'] = $payment_details->result_array();
	//PAYMENT DETAILS
	
	//CHIT ADJ
	$chit_details=$this->db->query("SELECT ifnull(sum(utilized_amt),0) as utilized_amt 
		from ret_billing_chit_utilization chit 
		left JOIN ret_billing b on b.bill_id = chit.bill_id 
		where b.bill_id is not null and b.bill_status=1 ".($FromDt!= '' && $ToDt!='' ? ' and (date(b.bill_date) BETWEEN "'.$FromDt.'" AND "'.$ToDt.'")' : '')." ".($id_branch!='' && $id_branch!=0 ? " and b.id_branch=".$id_branch."" :'')."");
	$return_data['chit_details'] = $chit_details->row_array();
	//CHIT ADJ

	//ORDER ADVANCE DETAILS
	$advance_detals = $this->db->query("SELECT ifnull(sum(a.received_amount),0) as orderamount, ifnull(sum(a.received_weight * a.rate_per_gram),0) as orderweight 
										FROM ret_billing_advance a 
										LEFT JOIN ret_billing b ON b.bill_id=a.bill_id 
										WHERE b.bill_id is not null and b.bill_status = 1 
										".($FromDt!= '' && $ToDt!='' ? 'and (date(b.bill_date) BETWEEN "'.$FromDt.'" AND "'.$ToDt.'")' : '')." 
										".($id_branch!='' && $id_branch!=0 ? " and b.id_branch=".$id_branch."" :'')." 
									");
	$return_data['advance_detals'] = $advance_detals->row_array();
	//ORDER ADVANCE DETAILS

	//VOUCHER UTILIZED
	$voucher_details=$this->db->query("SELECT ifnull(sum(g.gift_voucher_amt),0) as gift_voucher_amt 
		From ret_billing_gift_voucher_details g
		LEFT JOIN ret_billing b on b.bill_id=g.bill_id
		where  b.bill_id is not null and b.bill_status = 1 ".($FromDt!= '' && $ToDt!='' ? ' and (date(b.bill_date) BETWEEN "'.$FromDt.'" AND "'.$ToDt.'")' : '')." ".($id_branch!='' && $id_branch!=0 ? " and b.id_branch=".$id_branch."" :'')."");
	$return_data['voucher_details'] = $voucher_details->row_array();
	//VOUCHER UTILIZED

	//BT DETAILS
	$branch_transfer_details=$this->db->query("SELECT IFNULL(sum(b.pieces),0) as pieces,IFNULL(sum(b.grs_wt),0)as gross_wt,IFNULL(sum(b.net_wt),0)as net_wt
		From ret_branch_transfer b
		where ".($FromDt!= '' && $ToDt!='' ? '(date(b.created_time) BETWEEN "'.$FromDt.'" AND "'.$ToDt.'")' : '')." and  b.status=2 ".($id_branch!='' && $id_branch!=0 ? " and b.transfer_to_branch=".$id_branch."" :'')."");
	$return_data['branch_transfer_details'] = $branch_transfer_details->row_array();
	//BT DETAILS

	//CREDIT ISSUED
	$due_details=$this->db->query("SELECT ifnull(sum(b.tot_bill_amount-b.tot_amt_received),0) as due_amt 
		From ret_billing b
		WHERE b.bill_id is not null and b.bill_status=1 and b.is_credit=1 ".($FromDt!= '' && $ToDt!='' ? ' and (date(b.bill_date) BETWEEN "'.$FromDt.'" AND "'.$ToDt.'")' : '')." ".($id_branch!='' && $id_branch!=0 ? " and b.id_branch=".$id_branch."" :'')."");
	$return_data['due_details'] = $due_details->row_array();
	//CREDIT ISSUED

   //CREDIT RECEIVED
	$credit_details=$this->db->query("SELECT ifnull(sum(b.tot_amt_received),0) as tot_amt_received 
	 From ret_billing b
	 where  b.bill_id is not null and b.bill_status=1 and b.bill_type=8 ".($FromDt!= '' && $ToDt!='' ? ' and (date(b.bill_date) BETWEEN "'.$FromDt.'" AND "'.$ToDt.'")' : '')."
	 ".($id_branch!='' && $id_branch!=0 ? " and b.id_branch=".$id_branch."" :'')." ");
	$return_data['credit_details']=$credit_details->row_array();
	//CREDIT RECEIVED

	//GENERAL ADV ADJ
	$advance_adjusted=$this->db->query("SELECT IFNULL(sum(r.amount),0) as adj_amt
		FROM ret_billing bill
		LEFT JOIN ret_wallet_transcation r on r.bill_no=bill.bill_id
		where r.bill_no is not null
		and bill.bill_status=1  and r.transaction_type =1 
		".($FromDt!= '' && $ToDt!='' ? ' and (date(bill.bill_date) BETWEEN "'.$FromDt.'" AND "'.$ToDt.'")' : '')." 
		".($id_branch!='' && $id_branch!=0 ? " and bill.id_branch=".$id_branch."" :'')."
		");
	$return_data['advance_adjusted'] = $advance_adjusted->row_array();
	//GENERAL ADV ADJ
	
	
	//ORDER ADV ADJ
	$order_adj=$this->db->query("SELECT ifnull(sum(a.received_amount),0) as received_amount,ifnull(sum(a.received_weight * a.rate_per_gram),0) received_weight 
	FROM ret_billing b
	LEFT JOIN ret_billing_advance a ON a.adjusted_bill_id=b.bill_id
	WHERE a.is_adavnce_adjusted=1 and b.bill_status = 1 
	".($id_branch!='' && $id_branch!=0 ? " and b.id_branch=".$id_branch."" :'')."
	 ".($FromDt!= '' && $ToDt!='' ? ' and (date(b.bill_date) BETWEEN "'.$FromDt.'" AND "'.$ToDt.'")' : '')."  
	");
	$return_data['order_adj'] = $order_adj->row_array();
	//ORDER ADV ADJ
	
	//GENERAL ADV RECEIVED
	$general_adv_details=$this->db->query("SELECT ifnull(sum(r.amount),0) as amount, ifnull(sum((r.weight*r.rate_per_gram)),0) as weight_amt 
	FROM ret_issue_receipt r 
	WHERE r.type=2
	".($id_branch!='' && $id_branch!=0 ? " and r.id_branch=".$id_branch."" :'')."
	".($FromDt!= '' && $ToDt!='' ? ' and (date(r.bill_date) BETWEEN "'.$FromDt.'" AND "'.$ToDt.'")' : '')."  
	");
	$return_data['general_adv_details'] = $general_adv_details->row_array();
	//GENERAL ADV RECEIVED 
	 
	 
	$general_pay=$this->db->query("SELECT ifnull(sum(p.payment_amount),0) as payment_amount, 
									p.payment_mode 
									FROM ret_issue_receipt r 
									LEFT JOIN ret_issue_rcpt_payment p ON p.id_issue_rcpt=r.id_issue_receipt 
									WHERE r.type = 2 
									".($id_branch!='' && $id_branch!=0 ? " and r.id_branch=".$id_branch."" :'')." 
									".($FromDt!= '' && $ToDt!='' ? ' and (date(r.bill_date) BETWEEN "'.$FromDt.'" AND "'.$ToDt.'")' : '')." 
									group by payment_mode
								");
	$return_data['general_pay'] = $general_pay->result_array();
	
	
	
	//HOME BILL DETAILS
	$home_bill=$this->db->query("SELECT ifnull(SUM(d.net_wt),0) as net_wt,ifnull(SUM(d.gross_wt),0) as gross_wt,ifnull(SUM(d.item_cost),0) as item_cost,ifnull(SUM(d.piece),0) as pcs 
									FROM ret_billing b 
									LEFT JOIN ret_bill_details d ON d.bill_id=b.bill_id
									LEFT JOIN ret_estimation_items e ON e.est_item_id=d.esti_item_id
									LEFT JOIN ret_estimation est ON est.estimation_id=e.esti_id
									LEFT JOIN ret_product_master p ON p.pro_id=d.product_id
									WHERE d.esti_item_id IS NOT null AND d.tag_id is null 
									AND e.item_type=2 and b.bill_status=1 
									".($id_branch!='' && $id_branch!=0 ? " and b.id_branch=".$id_branch."" :'')." 
									".($FromDt!= '' && $ToDt!='' ? ' and (date(b.bill_date) 
									BETWEEN "'.$FromDt.'" AND "'.$ToDt.'")' : '')."
								");
	$return_data['home_bill']=$home_bill->row_array();
	//HOME BILL DETAILS
	
	
	//PARTLY SALE
	$partly_sale=$this->db->query("SELECT ifnull(SUM(d.net_wt),0) as net_wt,ifnull(SUM(tag.gross_wt-d.gross_wt),0) as gross_wt,ifnull(SUM(d.item_cost),0) as item_cost,ifnull(SUM(d.piece),0) as pcs 
									FROM ret_billing b 
									LEFT JOIN ret_bill_details d ON d.bill_id=b.bill_id
									LEFT JOIN ret_estimation_items e ON e.est_item_id=d.esti_item_id
									LEFT JOIN ret_estimation est ON est.estimation_id=e.esti_id
									LEFT JOIN ret_product_master p ON p.pro_id=d.product_id
									LEFT JOIN ret_taging tag on tag.tag_id=d.tag_id
									WHERE d.tag_id IS NOT null AND d.is_partial_sale=1 and b.bill_status=1
									".($id_branch!='' && $id_branch!=0 ? " and b.id_branch=".$id_branch."" :'')."
									".($FromDt!= '' && $ToDt!='' ? ' and (date(b.bill_date) BETWEEN "'.$FromDt.'" AND "'.$ToDt.'")' : '')."  
								");
	$return_data['partly_sale']=$partly_sale->row_array();
	//PARTLY SALE   
	
	//general_bill_details
	$bill_det=$this->db->query("SELECT IFNULL(SUM(b.round_off_amt),0) as round_off_amt,IFNULL(SUM(b.handling_charges),0) as handling_charges
	FROM ret_billing b
	WHERE b.bill_status = 1 
	".($id_branch!='' && $id_branch!=0 ? " and b.id_branch=".$id_branch."" :'')."
	".($FromDt!= '' && $ToDt!='' ? ' and (date(b.bill_date) BETWEEN "'.$FromDt.'" AND "'.$ToDt.'")' : '')." ");
	$return_data['bill_det'] = $bill_det->row_array();
	//general_bill_details
	
		
			 
	$metal_rates=$this->db->query("SELECT * 
	FROM metal_rates m
	WHERE date(m.updatetime)=".$FromDt."");
	$return_data['metal_rates']	= $metal_rates->row_array();

	
	$cachabstaract_data = array();
	$cachabstaract_data['sales_amount'] 					= (float)number_format($return_data['item_details']['item_cost']-$return_data['item_details']['item_total_tax'],2,'.','');
	$cachabstaract_data['sales_total_tax_amount'] 			= $return_data['item_details']['item_total_tax'];
	$cachabstaract_data['sales_return'] 					= number_format($return_data['return_details']['item_cost']-$return_data['return_details']['item_total_tax'],2,'.','');
	$cachabstaract_data['sales_return_total_tax_amount'] 	= number_format($return_data['return_details']['item_total_tax'],2,'.','');
	$cachabstaract_data['purchase_amount'] 					= number_format($return_data['old_matel_details']['amount'],2,'.','');
	$cachabstaract_data['advance_receipt'] 					= number_format($return_data['general_adv_details']['amount'] + $return_data['general_adv_details']['weight_amt'] + $return_data['advance_detals']['orderamount']+ $return_data['advance_detals']['orderweight'],2,'.','');
	$cachabstaract_data['credit_sale'] 						= number_format($return_data['credit_details']['tot_amt_received'],2,'.','');
	$cachabstaract_data['credit_receipt'] 					= number_format($return_data['due_details']['due_amt'],2,'.','');
	$cachabstaract_data['handling_charge'] 					= number_format($return_data['bill_det']['handling_charges'],2,'.','');
	$cachabstaract_data['trans_total'] 						= number_format($cachabstaract_data['sales_amount'] + $cachabstaract_data['sales_total_tax_amount'] - $cachabstaract_data['sales_return'] - $cachabstaract_data['sales_return_total_tax_amount'] - $cachabstaract_data['purchase_amount'] + $cachabstaract_data['advance_receipt'] + $cachabstaract_data['credit_sale'] - $cachabstaract_data['credit_receipt'] + $cachabstaract_data['handling_charge'],2,'.','');

	$received_pay_mode = array("cash" => 0, "cc" => 0, "nb" => 0, "dc" => 0, "chq" => 0);
	foreach($return_data['payment_details'] as $payrow => $payval){
		if(strtolower($payval['payment_mode']) === 'cash' ){
			$received_pay_mode['cash'] = $payval['payment_amount'];
		}else if(strtolower($payval['payment_mode']) === 'cc' ){
			$received_pay_mode['cc'] = $payval['payment_amount'];
		}else if(strtolower($payval['payment_mode']) === 'dc' ){
			$received_pay_mode['dc'] = $payval['payment_amount'];
		}else if(strtolower($payval['payment_mode']) === 'nb' ){
			$received_pay_mode['nb'] = $payval['payment_amount'];
		}else if(strtolower($payval['payment_mode']) === 'chq' ){
			$received_pay_mode['chq'] = $payval['payment_amount'];
		}
	}
	foreach($return_data['general_pay'] as $gpayrow => $gpayval){
		if(strtolower($gpayval['payment_mode']) === 'cash' ){
			$received_pay_mode['cash'] = $received_pay_mode['cash'] + $gpayval['payment_amount'];
		}else if(strtolower($gpayval['payment_mode']) === 'cc' ){
			$received_pay_mode['cc'] = $received_pay_mode['cc'] + $gpayval['payment_amount'];
		}else if(strtolower($gpayval['payment_mode']) === 'dc' ){
			$received_pay_mode['dc'] = $received_pay_mode['dc'] + $gpayval['payment_amount'];
		}else if(strtolower($gpayval['payment_mode']) === 'nb' ){
			$received_pay_mode['nb'] = $received_pay_mode['nb'] + $gpayval['payment_amount'];
		}else if(strtolower($gpayval['payment_mode']) === 'chq' ){
			$received_pay_mode['chq'] = $received_pay_mode['chq'] + $gpayval['payment_amount'];
		}
	}
	$cachabstaract_data['cash'] 					= $received_pay_mode['cash'];
	$cachabstaract_data['chq'] 						= $received_pay_mode['chq'];
	$cachabstaract_data['card'] 					= $received_pay_mode['cc'] + $received_pay_mode['dc'];
	$cachabstaract_data['nb'] 						= $received_pay_mode['nb'];
	$cachabstaract_data['advadj'] 					= $return_data['advance_adjusted']['adj_amt'];
	$cachabstaract_data['chituti'] 					= $return_data['chit_details']['utilized_amt'];
	$cachabstaract_data['handlingcharge'] 			= 0;
	$cachabstaract_data['orderadj'] 				= $return_data['order_adj']['received_amount'] + $return_data['order_adj']['received_weight'];
	$cachabstaract_data['giftvoucher'] 				= $return_data['voucher_details']['gift_voucher_amt'];
	$cachabstaract_data['roundoff'] 				= (float)number_format($return_data['bill_det']['round_off_amt'],2,'.','');
	$cachabstaract_data['paymodes_total']			= number_format(($cachabstaract_data['cash'] + $cachabstaract_data['chq'] + $cachabstaract_data['card'] + $cachabstaract_data['nb'] + $cachabstaract_data['advadj'] + $cachabstaract_data['chituti'] + $cachabstaract_data['handlingcharge'] + $cachabstaract_data['orderadj'] + $cachabstaract_data['giftvoucher'] + $cachabstaract_data['roundoff'] ),2,'.','');

	return $cachabstaract_data;
}



    function get_estimation_details($from_date,$to_date,$id_branch)
    {

		$sql=$this->db->query("SELECT est.estimation_id,est.discount,est.total_cost,
			est.gift_voucher_amt,est.cus_id,st.stone_price,mat.mat_price,item.item_cost,sales.sales_amt,est.cus_id,concat(c.firstname,' ',c.lastname) as cus_name,c.chit_amt,
			sales.sales_wt,item.pur_wt,item.item_type
		FROM ret_estimation est
		left JOIN (select if(i.item_type=0,'Tag',if(i.item_type=1,'Catalog','Custom')) as item_type,i.esti_id,SUM(i.item_cost) as item_cost,SUM(i.gross_wt)as pur_wt FROM ret_estimation_items i GROUP by i.esti_id) as item on item.esti_id=est.estimation_id
		LEFT JOIN (select s.est_id,SUM(s.price) as stone_price FROM ret_estimation_item_stones s GROUP by s.est_id)as st ON st.est_id=est.estimation_id
		LEFT JOIN (SELECT m.est_id,SUM(m.price) as mat_price FROM ret_estimation_item_other_materials m GROUP by m.est_id) as mat on mat.est_id=est.estimation_id
		LEFT JOIN (SELECT sa.est_id,SUM(sa.amount) as sales_amt,SUM(sa.gross_wt) as sales_wt FROM ret_estimation_old_metal_sale_details sa GROUP BY sa.est_id) as sales on sales.est_id=est.estimation_id
		LEFT JOIN(SELECT chit.est_id,SUM(chit.utl_amount) as chit_amt FROM ret_est_chit_utilization chit GROUP by chit.est_id) as c on c.est_id=est.estimation_id
		LEFT JOIN (SELECT v.est_id,SUM(v.gift_voucher_amt) as gift_voucher_amt FROM ret_est_gift_voucher_details v GROUP by v.est_id) as vo on vo.est_id=est.estimation_id
		left join customer c on c.id_customer=est.cus_id
		where  (date(est.created_time) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') ".($id_branch!='' && $id_branch!=0 ? " and est.id_branch=".$id_branch."" :'')." ");
    	//print_r($this->db->last_query());exit;
    	return $sql->result_array();

    }
    
    function lot_branchwise_data($id_branch,$from_date,$to_date)
	{
					$sql=("SELECT l.lot_no,COUNT(l.lot_received_at) as lots,SUM(l.net_wt) as net_weight,SUM(l.gross_wt) as grs_wt,b.name as name,
					b.id_branch,l.created_on from ret_lot_inwards l
					left join branch b on b.id_branch=l.lot_received_at
					where l.lot_received_at=".$id_branch);
					
					if($from_date!='')
						{
						$sql = $sql.( ' and (date(l.created_on) BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")');
						}
					$data = $this->db->query($sql);
					$res['count']=$data->row_array();
					return $res;
	}
    function get_lot_data($type,$from_date,$to_date)
	{
		switch($type)
		{
			case 'get_total_gross_wt':
					$sql = "SELECT SUM(gross_wt) as total_gt,created_on FROM ret_lot_inwards";
					if($from_date!='')
						{
						$sql = $sql.( ' where (date(created_on) BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")');
						}
					$res = $this->db->query($sql);
					return $res->row('total_gt');
				break; 
			case 'get_total_net_wt':
					$sql = "SELECT SUM(net_wt) as total_net_wt,created_on FROM ret_lot_inwards";
					if($from_date!='')
						{
						$sql = $sql.( ' where (date(created_on) BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")');
						}
					$total = $this->db->query($sql);
					return $total->row('total_net_wt');
				break;
		}
	}
	function tag_branchwise_data($id_branch,$from_date,$to_date)
	{
					$sql=("SELECT t.tag_id,SUM(t.net_wt) as nt,SUM(t.gross_wt) as gt,COUNT(t.id_branch) as tags,b.name as branch,t.created_time from ret_taging t
					left join branch b on b.id_branch=t.id_branch
					where t.id_branch=".$id_branch);
					if($from_date!='')
						{
						$sql = $sql.( ' and (date(t.created_time) BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")');
						}
					$data = $this->db->query($sql);
					$tag['total'] = $data->row_array();
					return $tag;
	}
	function get_tag_data($type,$from_date,$to_date)
	{
		switch($type)
		{
			case 'get_total_gross_wt':
					$total_gt = "SELECT SUM(gross_wt) as total_gt,created_time FROM ret_taging";
					if($from_date!='')
						{
						$total_gt = $total_gt.( ' where (date(created_time) BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")');
						}
					$result = $this->db->query($total_gt);
					return $result->row('total_gt');
				break;
			case 'get_total_net_wt':
					$total_nt = "SELECT SUM(net_wt) as total_net_wt,created_time FROM ret_taging";
					if($from_date!='')
						{
						$total_nt = $total_nt.( ' where (date(created_time) BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")');
						}
					$res = $this->db->query($total_nt);
					return $res->row('total_net_wt');
				break;
		}
	}
	
	function get_billing($from_date,$to_date)
	{
		$sql=$this->db->query("select count(b.bill_id) as billing  
	    							 from ret_billing b
	    					          where(date(b.created_time) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')");
	    		 return $sql->row_array();
	}
	function allBranches(){
	    $sql="Select b.id_branch as id_branch, b.name as branch_name from branch b";
		$r=$this->db->query($sql);
		return $r->result_array();
	}
	function get_order_data($id_branch,$from_date,$to_date)
	{
		$catalog="SELECT COUNT(c.ortertype) as catalog,c.branch_id,b.id_branch as id_branch,b.name as branch,c.order_date from customerorderdetails  c
		left join branch b on b.id_branch=c.branch_id
		where c.ortertype=1 and c.branch_id=".$id_branch;
		if($from_date!='')
						{
						$catalog = $catalog.( ' and (date(c.order_date) BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")');
						}
		$ct =$this->db->query($catalog);	
		$res['catalog'] = $ct->row_array();
		$ct->free_result();
		
	
		$cus="SELECT c.id_orderdetails,COUNT(c.ortertype) as custom,c.branch_id,b.name as branch,b.id_branch as id_branch,c.order_date from customerorderdetails  c
		left join branch b on b.id_branch=c.branch_id
		where c.ortertype=2 and c.branch_id=".$id_branch;
		if($from_date!='')
						{
						$cus = $cus.( ' and (date(c.order_date) BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")');
						}
		$cus =$this->db->query($cus);	
		$res['cus'] = $cus->row_array();
		$cus->free_result();
		
		$sql= "SELECT c.id_orderdetails,COUNT(c.ortertype) as repair,c.branch_id,b.name as branch,b.id_branch as id_branch,c.order_date from customerorderdetails  c
		left join branch b on b.id_branch=c.branch_id
		where c.ortertype=3 and c.branch_id=".$id_branch;
		if($from_date!='')
						{
						$sql = $sql.( ' and (date(c.order_date) BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")');
						}
		$data =$this->db->query($sql);	
		$res['repair'] = $data->row_array();
		$data->free_result();
		return $res;
		
	}
	function total_order($type="")
	{
		switch($type)
		{
		case 'tot_catalog':
		$sql = $this->db->query("SELECT COUNT(ortertype) as tot_catalog FROM customerorderdetails where ortertype=1");
		return $sql->row('tot_catalog');
		break;
	
	    case 'tot_custom':
		$sql = $this->db->query("SELECT COUNT(ortertype) as tot_custom FROM customerorderdetails where ortertype=2");
		return $sql->row('tot_custom');
		break;
		
	    case 'tot_repair':
		$sql = $this->db->query("SELECT COUNT(ortertype) as tot_repair FROM customerorderdetails where ortertype=3");
		return $sql->row('tot_repair');
		break;
		}
	}
	
	function get_saleBill($id_branch,$from_date,$to_date)
	{
		$sql=$this->db->query("select count(IFNULL(b.bill_id,0)) as billing,br.name as branch_name
		from ret_billing b
		LEFT join branch br on br.id_branch=b.id_branch
		where (b.bill_type=1 or b.bill_type=2 or b.bill_type=3 or b.bill_type=7) and (date(b.created_time) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')
		".($id_branch!='' ? " and b.id_branch=".$id_branch."" :'')."");
		return $sql->row_array();
	}

	function metal_bill_details()
	{
		$data=$this->db->query("SELECT m.metal,m.metal_code,count(d.bill_id) as billing,sum(d.item_cost) as sale_amount,b.id_branch,br.name as branch_name
			FROM ret_billing b
			LEFT JOIN ret_bill_details d on d.bill_id=b.bill_id
			LEFT JOIN ret_product_master p on p.pro_id=d.product_id
			LEFT JOIN ret_category c on c.id_ret_category=p.cat_id
			LEFT JOIN metal m on m.id_metal=c.id_metal
			LEFT JOIN branch br on br.id_branch=b.id_branch
			GROUP by br.id_branch,m.id_metal");
			return $data->result_array();
	}
	
	
	
   function karigar_orders($filterBy)
   {
       $result=0;
       $id_branch=$this->input->post('id_branch');
       $sql="Select count(o.id_customerorder) as total_orders
       From customerorderdetails o
       LEFT JOIN customerorder c on c.id_customerorder=o.id_customerorder
       WHERE c.order_for=1 ".($id_branch!='' ?  " and c.order_from=".$id_branch."":'')."";
       switch($filterBy)
       {
           case 'TM': //Tomorrow Delivery
           $sql=$sql." AND date(o.smith_due_date) = CURDATE() + INTERVAL 1 DAY";  
           break;
           case 'T': //Today Delivered
           $sql=$sql." AND date(o.delivered_date) = CURDATE() AND o.orderstatus=5";  
           break;
case 'TODDY_PENDING': //Today Yet To Delivery
           $sql=$sql." AND date(o.smith_due_date) = CURDATE() AND o.orderstatus<=3";  
           break;
           case 'OVER_DUE': //Over Due Ordes
           $sql=$sql." AND date(o.smith_due_date) < CURDATE() AND o.orderstatus=3";  
           break;
           case 'WIP': //Work in Progress Ordes
           $sql=$sql." AND o.orderstatus=3";
           break;
       }
       $r=$this->db->query($sql);
       if($r->num_rows()>0)
       {
           $result= $r->row('total_orders');
       }
       return $result;
   }
    
    
  /* function customer_orders($filterBy)
   {
       $result=0;
       $id_branch=$this->input->post('id_branch');
       $sql="Select count(o.id_customerorder) as total_orders
       From customerorderdetails o
       LEFT JOIN customerorder c on c.id_customerorder=o.id_customerorder
       WHERE c.order_for=2 ".($id_branch!='' ?  " and c.order_from=".$id_branch."":'')." ";
       switch($filterBy)
       {
           case 'TM': //Tomorrow Ready for Delivery
           $sql=$sql." AND date(o.cus_due_date) = CURDATE() + INTERVAL 1 DAY AND o.orderstatus=4";  
           break;
		   case 'TMRW_PENDING': //Tomorrow Delivery Pending
           $sql=$sql." AND date(o.cus_due_date) = CURDATE() + INTERVAL 1 DAY AND o.orderstatus<4";  
           break;
           case 'T': //Today Delivered
           $sql=$sql." AND date(o.delivered_date) = CURDATE() AND o.orderstatus=5 ";  
           break;
		   case 'TODDY_PENDING': //Today Yet To Delivery
           $sql=$sql." AND date(o.cus_due_date) = CURDATE() AND o.orderstatus<=3";  
           break;
           case 'OVER_DUE': //Over Due Ordes
           $sql=$sql." AND date(o.cus_due_date) < CURDATE() AND o.orderstatus=3 ";  
           break;
           case 'WIP': //Work in Progress Ordes
           $sql=$sql." AND o.orderstatus=3";  
           break;
       }
       $r=$this->db->query($sql);
       if($r->num_rows()>0)
       {
           $result= $r->row('total_orders');
       }
       return $result;
   }*/
   
   
   function customer_orders($from_date,$to_date,$id_branch)
    {
        $sql=$this->db->query("SELECT 
        
        IFNULL((recd_piece.recd_pcs),0) as received_piece,IFNULL((allocated.allo_pcs),0) as allocated_piece,
        IFNULL((pending.pen_pcs),0) as pending_piece,IFNULL((ready.rdy_pcs),0) as ready_piece,
        IFNULL((delivery.dely_pcs),0) as delivery_piece
        
        FROM customerorder c 
        
        LEFT JOIN branch b ON b.id_branch=c.order_from
        
        LEFT JOIN customerorderdetails d on d.id_customerorder=c.id_customerorder
        
        LEFT JOIN(SELECT SUM(d.totalitems) as recd_pcs,c.order_from
                 FROM customerorder c 
                 LEFT JOIN customerorderdetails d ON d.id_customerorder=c.id_customerorder
                 WHERE (date(c.order_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') ".($id_branch!='' && $id_branch!=0 ? " and c.order_from=".$id_branch."" :'')."
                 and c.order_for='2' and d.orderstatus = 0) as recd_piece ON recd_piece.order_from=c.order_from
                 
        LEFT JOIN(SELECT SUM(d.totalitems) as allo_pcs,c.order_from
                 FROM customerorder c 
                 LEFT JOIN customerorderdetails d ON d.id_customerorder=c.id_customerorder
                 WHERE (date(c.order_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') ".($id_branch!='' && $id_branch!=0 ? " and c.order_from=".$id_branch."" :'')."
                 and c.order_for='2' and d.orderstatus = 3 ) as allocated ON allocated.order_from=c.order_from
                 
        LEFT JOIN(SELECT SUM(d.totalitems) as pen_pcs,c.order_from
                 FROM customerorder c 
                 LEFT JOIN customerorderdetails d ON d.id_customerorder=c.id_customerorder
                 WHERE (date(c.order_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') ".($id_branch!='' && $id_branch!=0 ? " and c.order_from=".$id_branch."" :'')."
                 and c.order_for='2' and d.orderstatus <= 2 ) as pending ON pending.order_from=c.order_from
                 
        LEFT JOIN(SELECT SUM(d.totalitems) as rdy_pcs,c.order_from
                 FROM customerorder c 
                 LEFT JOIN customerorderdetails d ON d.id_customerorder=c.id_customerorder
                 WHERE (date(c.order_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') ".($id_branch!='' && $id_branch!=0 ? " and c.order_from=".$id_branch."" :'')."
                 and c.order_for='2' and d.orderstatus = 4 ) as ready ON ready.order_from=c.order_from
                 
        
        LEFT JOIN(SELECT SUM(d.totalitems) as dely_pcs,c.order_from
                 FROM customerorder c 
                 LEFT JOIN customerorderdetails d ON d.id_customerorder=c.id_customerorder
                 WHERE (date(c.order_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') ".($id_branch!='' && $id_branch!=0 ? " and c.order_from=".$id_branch."" :'')."
                 and c.order_for='2' and d.orderstatus = 5 ) as delivery ON delivery.order_from=c.order_from     
                 
         
        WHERE c.order_from is not null 
		and (date(c.order_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') ".($id_branch!='' && $id_branch!=0 ? " and c.order_from=".$id_branch."" :'')."
		".($id_branch!='' && $id_branch!=0 ? " and c.order_from=".$id_branch."" :'')."");
        // print_r($this->db->last_query());exit;
        return $sql->row_array();
    }
	
	function StockMetalWise($id_branch)
	{
		$sql=$this->db->query("SELECT SUM(tag.piece) as available_pcs,SUM(tag.gross_wt) as total_gwt,SUM(tag.net_wt) as total_nwt,m.metal
		FROM ret_taging tag 
		LEFT JOIN ret_product_master p ON p.pro_id=tag.product_id
		LEFT JOIN ret_category c ON c.id_ret_category=p.cat_id
		LEFT JOIN metal m ON m.id_metal=c.id_metal
		WHERE tag.tag_status=0 ".($id_branch!='' ? " and tag.current_branch=".$id_branch."" :'')."
		GROUP by m.id_metal");
		
		return $sql->result_array();
	}
	
	function AvailableStockDetails($from_date,$to_date,$id_branch)
	{
		$op_date= date('Y-m-d',(strtotime('-1 day',strtotime($from_date))));
		
		$sql=$this->db->query("SELECT (SELECT IFNULL(SUM(s.closing_pcs),0) FROM ret_stock_balance s
		left join ret_product_master as pro on pro.pro_id=s.id_product
		left join ret_category as cat on cat.id_ret_category=pro.cat_id
		left join metal as m on m.id_metal=cat.id_metal
		WHERE date(s.date)='".$op_date."' ".($id_branch!='' && $id_branch>0 ? " and s.id_branch=".$id_branch."" :'')." and m.id_metal=1) as g_opening_pcs,
		
		(SELECT IFNULL(SUM(s.closing_gwt),0) FROM ret_stock_balance s
		left join ret_product_master as pro on pro.pro_id=s.id_product
		left join ret_category as cat on cat.id_ret_category=pro.cat_id
		left join metal as m on m.id_metal=cat.id_metal
		WHERE date(s.date)='".$op_date."' ".($id_branch!='' && $id_branch>0 ? " and s.id_branch=".$id_branch."" :'')." and m.id_metal=1) as g_opening_gwt,

		(SELECT IFNULL(SUM(s.closing_nwt),0) FROM ret_stock_balance s
		left join ret_product_master as pro on pro.pro_id=s.id_product
    	left join ret_category as cat on cat.id_ret_category=pro.cat_id
   		left join metal as m on m.id_metal=cat.id_metal
		WHERE date(s.date)='".$op_date."' ".($id_branch!='' && $id_branch>0 ? " and s.id_branch=".$id_branch."" :'')." and m.id_metal=1) as g_opening_nwt,

		(SELECT IFNULL(SUM(d.piece),0) FROM ret_bill_details d 
		LEFT JOIN ret_billing b ON b.bill_id=d.bill_id 
		left join ret_product_master as pro on pro.pro_id=d.product_id
    	left join ret_category as cat on cat.id_ret_category=pro.cat_id
    	left join metal as m on m.id_metal=cat.id_metal
		WHERE b.bill_status=1 and m.id_metal=1 and date(b.bill_date)='".$from_date."' ".($id_branch!='' && $id_branch>0  ? " and b.id_branch=".$id_branch."" :'').") as g_tot_sales_pcs,

		( SELECT IFNULL(SUM(d.gross_wt),0) FROM ret_bill_details d 
    	LEFT JOIN ret_billing b ON b.bill_id=d.bill_id 
    	left join ret_product_master as pro on pro.pro_id=d.product_id
    	left join ret_category as cat on cat.id_ret_category=pro.cat_id
    	left join metal as m on m.id_metal=cat.id_metal
    	WHERE d.bill_det_id is NOT null and m.id_metal=1 and b.bill_status=1 and date(b.bill_date)='".$from_date."' ".($id_branch!='' && $id_branch>0 ? " and b.id_branch=".$id_branch."" :'').") as g_tot_sales_gwt,
		
		(SELECT IFNULL(SUM(d.net_wt),0) FROM ret_bill_details d 
		LEFT JOIN ret_billing b ON b.bill_id=d.bill_id 
		left join ret_product_master as pro on pro.pro_id=d.product_id
		left join ret_category as cat on cat.id_ret_category=pro.cat_id
		left join metal as m on m.id_metal=cat.id_metal
		WHERE b.bill_status=1 and m.id_metal=1 and date(b.bill_date)='".$from_date."' ".($id_branch!='' && $id_branch>0 ? " and b.id_branch=".$id_branch."" :'').") as g_tot_sales_nwt,
		
		(SELECT IFNULL(SUM(tag.piece),0) FROM ret_taging_status_log l 
		LEFT JOIN ret_taging tag on tag.tag_id=l.tag_id 
		left join ret_product_master as pro on pro.pro_id=tag.product_id
		left join ret_category as cat on cat.id_ret_category=pro.cat_id
		left join metal as m on m.id_metal=cat.id_metal
		WHERE date(l.date)='".$from_date."' and l.status=0 ".($id_branch!='' && $id_branch>0 ? " and l.to_branch=".$id_branch."" :'')." and m.id_metal=1 ) as g_inward_pcs,
			
		(SELECT IFNULL(SUM(tag.net_wt),0) FROM ret_taging tag 
		left join ret_product_master as pro on pro.pro_id=tag.product_id
		left join ret_category as cat on cat.id_ret_category=pro.cat_id
		left join metal as m on m.id_metal=cat.id_metal
		WHERE date(tag.tag_datetime)='".$from_date."' ".($id_branch!='' && $id_branch>0 ? " and tag.current_branch=".$id_branch."" :'')." and m.id_metal=1 ) as g_inward_nwt,
			
		(SELECT IFNULL(SUM(tag.gross_wt),0) FROM ret_taging tag 
		left join ret_product_master as pro on pro.pro_id=tag.product_id
		left join ret_category as cat on cat.id_ret_category=pro.cat_id
		left join metal as m on m.id_metal=cat.id_metal
		WHERE date(tag.tag_datetime)='".$from_date."' ".($id_branch!='' && $id_branch>0 ? " and tag.current_branch=".$id_branch."" :'')." and m.id_metal=1) as g_inward_gwt ,

		(SELECT IFNULL(SUM(tag.piece),0) FROM ret_taging_status_log l 
		LEFT JOIN ret_taging tag ON tag.tag_id=l.tag_id 
		left join ret_product_master as pro on pro.pro_id=tag.product_id
		left join ret_category as cat on cat.id_ret_category=pro.cat_id
		left join metal as m on m.id_metal=cat.id_metal
		WHERE date(l.date)='".$from_date."' and (l.status=2 or l.status=3 or l.status=5 or l.status=4) ".($id_branch!='' && $id_branch>0 ? " and l.from_branch=".$id_branch."" :'')." and m.id_metal=1) as g_br_out_pcs,

		(SELECT IFNULL(SUM(tag.gross_wt),0) FROM ret_taging_status_log l 
		LEFT JOIN ret_taging tag ON tag.tag_id=l.tag_id 
		left join ret_product_master as pro on pro.pro_id=tag.product_id
		left join ret_category as cat on cat.id_ret_category=pro.cat_id
		left join metal as m on m.id_metal=cat.id_metal
		WHERE date(l.date)='".$from_date."' and (l.status=2 or l.status=3 or l.status=5 or l.status=4) ".($id_branch!='' && $id_branch>0 ? " and l.from_branch=".$id_branch."" :'')." and m.id_metal=1) as g_br_out_gwt,
			
		(SELECT IFNULL(SUM(tag.net_wt),0) FROM ret_taging_status_log l 
		LEFT JOIN ret_taging tag ON tag.tag_id=l.tag_id 
		left join ret_product_master as pro on pro.pro_id=tag.product_id
		left join ret_category as cat on cat.id_ret_category=pro.cat_id
		left join metal as m on m.id_metal=cat.id_metal
		WHERE date(l.date)='".$from_date."' and (l.status=2 or l.status=3 or l.status=5 or l.status=4) ".($id_branch!='' && $id_branch>0 ? " and l.from_branch=".$id_branch."" :'')." and m.id_metal=1) as g_br_out_nwt ");
		 
		// print_r($this->db->last_query());exit;
		$data=$sql->row_array();
		$return_data['g_opening_pcs']=$data['g_opening_pcs'];
		$return_data['g_opening_gwt']=$data['g_opening_gwt'];
		$return_data['g_opening_nwt']=$data['g_opening_nwt'];
		$return_data['g_tot_sales_pcs']=$data['g_tot_sales_pcs'];
		$return_data['g_tot_sales_gwt']=$data['g_tot_sales_gwt'];
		$return_data['g_tot_sales_nwt']=$data['g_tot_sales_nwt'];
		$return_data['g_inward_pcs']=$data['g_inward_pcs'];
		$return_data['g_inward_gwt']=$data['g_inward_gwt'];
		$return_data['g_br_out_pcs']=$data['g_br_out_pcs'];
		$return_data['g_br_out_gwt']=$data['g_br_out_gwt'];
		$return_data['g_available_pcs']=$data['g_opening_pcs']+$data['g_inward_pcs']-$data['g_tot_sales_pcs']-$data['g_br_out_pcs'];
		$return_data['g_available_gwt']=$data['g_opening_gwt']+$data['g_inward_gwt']-$data['g_tot_sales_gwt']-$data['g_br_out_gwt'];
		$return_data['g_available_nwt']=$data['g_opening_nwt']+$data['g_inward_nwt']-$data['g_tot_sales_nwt']-$data['g_br_out_nwt'];
		
		return $return_data;
	}
	

	function Available_SilverStockDetails($from_date,$to_date,$id_branch){

		$op_date= date('Y-m-d',(strtotime('-1 day',strtotime($from_date))));
		
		$sql=$this->db->query("SELECT (SELECT IFNULL(SUM(s.closing_pcs),0) FROM ret_stock_balance s
		left join ret_product_master as pro on pro.pro_id=s.id_product
		left join ret_category as cat on cat.id_ret_category=pro.cat_id
		left join metal as m on m.id_metal=cat.id_metal
		WHERE date(s.date)='".$op_date."' ".($id_branch!='' && $id_branch>0 ? " and s.id_branch=".$id_branch."" :'')." and m.id_metal=2) as s_opening_pcs,
		
		(SELECT IFNULL(SUM(s.closing_gwt),0) FROM ret_stock_balance s
		left join ret_product_master as pro on pro.pro_id=s.id_product
		left join ret_category as cat on cat.id_ret_category=pro.cat_id
		left join metal as m on m.id_metal=cat.id_metal
		WHERE date(s.date)='".$op_date."' ".($id_branch!='' && $id_branch>0 ? " and s.id_branch=".$id_branch."" :'')." and m.id_metal=2) as s_opening_gwt,

		(SELECT IFNULL(SUM(s.closing_nwt),0) FROM ret_stock_balance s
		left join ret_product_master as pro on pro.pro_id=s.id_product
    	left join ret_category as cat on cat.id_ret_category=pro.cat_id
   		left join metal as m on m.id_metal=cat.id_metal
		WHERE date(s.date)='".$op_date."' ".($id_branch!='' && $id_branch>0 ? " and s.id_branch=".$id_branch."" :'')." and m.id_metal=2) as s_opening_nwt,

		(SELECT IFNULL(SUM(d.piece),0) FROM ret_bill_details d 
		LEFT JOIN ret_billing b ON b.bill_id=d.bill_id 
		left join ret_product_master as pro on pro.pro_id=d.product_id
    	left join ret_category as cat on cat.id_ret_category=pro.cat_id
    	left join metal as m on m.id_metal=cat.id_metal
		WHERE b.bill_status=1 and m.id_metal=2 and date(b.bill_date)='".$from_date."' ".($id_branch!='' && $id_branch>0  ? " and b.id_branch=".$id_branch."" :'').") as s_tot_sales_pcs,

		( SELECT IFNULL(SUM(d.gross_wt),0) FROM ret_bill_details d 
    	LEFT JOIN ret_billing b ON b.bill_id=d.bill_id 
    	left join ret_product_master as pro on pro.pro_id=d.product_id
    	left join ret_category as cat on cat.id_ret_category=pro.cat_id
    	left join metal as m on m.id_metal=cat.id_metal
    	WHERE d.bill_det_id is NOT null and m.id_metal=2 and b.bill_status=1 and date(b.bill_date)='".$from_date."' ".($id_branch!='' && $id_branch>0 ? " and b.id_branch=".$id_branch."" :'').") as s_tot_sales_gwt,
		
		(SELECT IFNULL(SUM(d.net_wt),0) FROM ret_bill_details d 
		LEFT JOIN ret_billing b ON b.bill_id=d.bill_id 
		left join ret_product_master as pro on pro.pro_id=d.product_id
		left join ret_category as cat on cat.id_ret_category=pro.cat_id
		left join metal as m on m.id_metal=cat.id_metal
		WHERE b.bill_status=1 and m.id_metal=2 and date(b.bill_date)='".$from_date."' ".($id_branch!='' && $id_branch>0 ? " and b.id_branch=".$id_branch."" :'').") as s_tot_sales_nwt,
		
		(SELECT IFNULL(SUM(tag.piece),0) FROM ret_taging_status_log l 
		LEFT JOIN ret_taging tag on tag.tag_id=l.tag_id 
		left join ret_product_master as pro on pro.pro_id=tag.product_id
		left join ret_category as cat on cat.id_ret_category=pro.cat_id
		left join metal as m on m.id_metal=cat.id_metal
		WHERE date(l.date)='".$from_date."' and l.status=0 ".($id_branch!='' && $id_branch>0 ? " and l.to_branch=".$id_branch."" :'')." and m.id_metal=2 ) as s_inward_pcs,
			
		(SELECT IFNULL(SUM(tag.net_wt),0) FROM ret_taging tag 
		left join ret_product_master as pro on pro.pro_id=tag.product_id
		left join ret_category as cat on cat.id_ret_category=pro.cat_id
		left join metal as m on m.id_metal=cat.id_metal
		WHERE date(tag.tag_datetime)='".$from_date."' ".($id_branch!='' && $id_branch>0 ? " and tag.current_branch=".$id_branch."" :'')." and m.id_metal=2 ) as s_inward_nwt,
			
		(SELECT IFNULL(SUM(tag.gross_wt),0) FROM ret_taging tag 
		left join ret_product_master as pro on pro.pro_id=tag.product_id
		left join ret_category as cat on cat.id_ret_category=pro.cat_id
		left join metal as m on m.id_metal=cat.id_metal
		WHERE date(tag.tag_datetime)='".$from_date."' ".($id_branch!='' && $id_branch>0 ? " and tag.current_branch=".$id_branch."" :'')." and m.id_metal=2 ) as s_inward_gwt ,

		(SELECT IFNULL(SUM(tag.piece),0) FROM ret_taging_status_log l 
		LEFT JOIN ret_taging tag ON tag.tag_id=l.tag_id 
		left join ret_product_master as pro on pro.pro_id=tag.product_id
		left join ret_category as cat on cat.id_ret_category=pro.cat_id
		left join metal as m on m.id_metal=cat.id_metal
		WHERE date(l.date)='".$from_date."' and (l.status=2 or l.status=3 or l.status=5 or l.status=4) ".($id_branch!='' && $id_branch>0 ? " and l.from_branch=".$id_branch."" :'')." and m.id_metal=2 ) as s_br_out_pcs,

		(SELECT IFNULL(SUM(tag.gross_wt),0) FROM ret_taging_status_log l 
		LEFT JOIN ret_taging tag ON tag.tag_id=l.tag_id 
		left join ret_product_master as pro on pro.pro_id=tag.product_id
		left join ret_category as cat on cat.id_ret_category=pro.cat_id
		left join metal as m on m.id_metal=cat.id_metal
		WHERE date(l.date)='".$from_date."' and (l.status=2 or l.status=3 or l.status=5 or l.status=4) ".($id_branch!='' && $id_branch>0 ? " and l.from_branch=".$id_branch."" :'')." and m.id_metal= 2) as s_br_out_gwt,
			
		(SELECT IFNULL(SUM(tag.net_wt),0) FROM ret_taging_status_log l 
		LEFT JOIN ret_taging tag ON tag.tag_id=l.tag_id 
		left join ret_product_master as pro on pro.pro_id=tag.product_id
		left join ret_category as cat on cat.id_ret_category=pro.cat_id
		left join metal as m on m.id_metal=cat.id_metal
		WHERE date(l.date)='".$from_date."' and (l.status=2 or l.status=3 or l.status=5 or l.status=4) ".($id_branch!='' && $id_branch>0 ? " and l.from_branch=".$id_branch."" :'')." and m.id_metal=2 ) as s_br_out_nwt ");
		 
		// print_r($this->db->last_query());exit;
		$data=$sql->row_array();
		$return_data['s_opening_gwt']=$data['s_opening_gwt'];
		$return_data['s_opening_pcs']=$data['s_opening_pcs'];
		$return_data['s_opening_nwt']=$data['s_opening_nwt'];
		$return_data['s_tot_sales_pcs']=$data['s_tot_sales_pcs'];
		$return_data['s_tot_sales_gwt']=$data['s_tot_sales_gwt'];
		$return_data['s_tot_sales_nwt']=$data['s_tot_sales_nwt'];
		$return_data['s_inward_pcs']=$data['s_inward_pcs'];
		$return_data['s_inward_gwt']=$data['s_inward_gwt'];
		$return_data['s_br_out_pcs']=$data['s_br_out_pcs'];
		$return_data['s_br_out_gwt']=$data['s_br_out_gwt'];
		$return_data['s_available_pcs']=$data['s_opening_pcs']+$data['s_inward_pcs']-$data['s_tot_sales_pcs']-$data['s_br_out_pcs'];
		$return_data['s_available_gwt']=$data['s_opening_gwt']+$data['s_inward_gwt']-$data['s_tot_sales_gwt']-$data['s_br_out_gwt'];
		$return_data['s_available_nwt']=$data['s_opening_nwt']+$data['s_inward_nwt']-$data['s_tot_sales_nwt']-$data['s_br_out_nwt'];
		
		return $return_data;
	}
	
	function getReorderItems($id_branch)
	{
		$result=array();
		
		$data=$this->db->query("SELECT s.min_pcs,s.max_pcs,concat(IFNULL(sz.value,''),'',sz.name) as size,p.product_name,d.design_name,s.id_product as product_id,s.id_design as design_id,
		b.name as branch_name,s.id_branch,concat(wt.value,m.uom_name) as weight_name,wt.from_weight,wt.to_weight,s.id_wt_range,p.cat_id as id_category
		FROM ret_reorder_settings s
		LEFT JOIN ret_product_master p ON p.pro_id=s.id_product
		LEFT  JOIN ret_design_master d ON d.design_no=s.id_design
		LEFT JOIN ret_size sz on sz.id_size=d.id_size
		LEFT JOIN branch b on b.id_branch=s.id_branch
		LEFT JOIN ret_weight wt on wt.id_weight=s.id_wt_range
		LEFT JOIN ret_uom m on m.uom_id=wt.id_uom
		where s.id_product is not null and s.id_design is not null and s.id_wt_range is not null
		".($id_branch!='' && $id_branch>0 ? " and s.id_branch=".$id_branch."":'')."
		order by p.pro_id ASC");

		$items = $data->result_array();
		foreach($items as $row)
		{
			$available_pcs=$this->getTagging($row['product_id'],$row['design_id'],$row['id_branch'],$row['from_weight'],$row['to_weight']);
			$isAlreadyinCart=$this->get_cart_items($row['product_id'],$row['design_id'],$row['id_wt_range'],$row['id_branch']);
		   
			if($row['min_pcs']>$available_pcs && $isAlreadyinCart==0)
			{
					$result[]=array(
					'product_name'  =>$row['product_name'],
					'design_name'   =>$row['design_name'],
					'product_id'    =>$row['product_id'],
					'design_id'     =>$row['design_id'],
					'branch_name'   =>$row['branch_name'],
					'id_branch'     =>$row['id_branch'],
					'weight_name'   =>$row['weight_name'],
					'min_pcs'       =>$row['min_pcs'],
					'max_pcs'       =>$row['max_pcs'],
					'from_weight'   =>$row['from_weight'],
					'to_weight'     =>$row['to_weight'],
					'id_wt_range'   =>$row['id_wt_range'],
					'size'          =>$row['size'],
					'id_category'   =>$row['id_category'],
					'is_cart'		=>$isAlreadyinCart,
					'available_pcs' =>$available_pcs
				);
			}
		}
		return $result;
	}


	function getTagging($id_product,$design_id,$id_branch,$from_weight,$to_weight)
	{
			$sql=$this->db->query("SELECT IFNULL(SUM(t.piece),0) as tot_pcs,sum(t.net_wt) as net_wt,SUM(t.gross_wt) as gross_wt
			FROM ret_taging t
			WHERE t.tag_status=0 ".($id_branch!='' ? " AND t.current_branch=".$id_branch." " :'')."  AND t.product_id=".$id_product." AND t.design_id=".$design_id." and net_wt BETWEEN '".$from_weight."' AND '".$to_weight."'");
			return $sql->row()->tot_pcs;
	}

	function get_cart_items($id_product,$design_no,$id_wt_range,$id_branch)
	{
		$sql=$this->db->query("SELECT * FROM order_cart o WHERE o.orderstatus=0 AND o.id_product=".$id_product." AND o.design_no=".$design_no." AND o.id_wt_range=".$id_wt_range." ".($id_branch!='' ? " AND o.id_branch=".$id_branch." " :'')." ");
		return $sql->num_rows();
	}

	function get_saleBillRecords($id_branch,$from_date,$to_date)
	{
		
		$sql=$this->db->query("select category.name as category_name,b.name as branch_name,sum(bill_detalis.net_wt) as sold_weight,
		(select count(IFNULL(tag.tag_mark,0)) from ret_taging as tag where tag.tag_mark='1'
		group by product.cat_id,billing.id_branch order by bill_detalis.bill_det_id desc) as green_tag
		from ret_bill_details as bill_detalis
		Left Join ret_billing as billing on billing.bill_id = bill_detalis.bill_id
		Left Join ret_taging as tag on tag.tag_id = bill_detalis.tag_id
		Left Join ret_product_master as product on product.pro_id = bill_detalis.product_id
		Left Join ret_design_master as design on design.design_no = bill_detalis.design_id
		Left Join ret_category as category on category.id_ret_category = product.cat_id
		Left Join branch as b on b.id_branch = billing.id_branch
		where bill_detalis.status='1' and (date(billing.created_time) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')
		".($id_branch!='' ? " and billing.id_branch=".$id_branch."" :'')." Group By product.cat_id,billing.id_branch order by bill_detalis.bill_det_id desc");
		$result                      = $sql->result_array(); 
		$categorywise_sales_records  = array();
		 foreach($result as $r){
			   $categorywise_sales_records[] = $r;
			}
		return $categorywise_sales_records;
	}
	
	function get_paymentBillRecords($id_branch,$from_date,$to_date)
	{
		$sql=$this->db->query("SELECT b.name as branch_name,payment.payment_mode as payment_mode,round(if(payment.payment_amount>0,payment.payment_amount,'0'),2) as amount
		FROM ret_billing as billing
		Left Join ret_billing_payment as payment on payment.bill_id = billing.bill_id
		Left Join branch as b on b.id_branch = billing.id_branch
		where payment_status='1' and (date(billing.bill_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') ".($id_branch!='' ? " and billing.id_branch=".$id_branch."" :'')." Group By b.id_branch,payment.payment_mode order by billing.bill_id desc");
		$result                      = $sql->result_array(); 
		$paymentwise_sales_records  = array();
		 foreach($result as $r){
			   $paymentwise_sales_records[] = $r;
			}
		
		return $paymentwise_sales_records;
	}
	
	function get_metalBillRecords($id_branch,$from_date,$to_date)
	{
		$data  = $this->db->query("SELECT m.metal,m.metal_code,count(d.bill_id) as billing,sum(d.item_cost) as sale_amount,b.id_branch,br.name as branch_name,sum(d.net_wt) as sold_weight
			FROM ret_billing b
			LEFT JOIN ret_bill_details d on d.bill_id=b.bill_id
			LEFT JOIN ret_product_master p on p.pro_id=d.product_id
			LEFT JOIN ret_category c on c.id_ret_category=p.cat_id
			LEFT JOIN metal m on m.id_metal=c.id_metal
			LEFT JOIN branch br on br.id_branch=b.id_branch
			where d.status='1' and (date(b.bill_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')
		    ".($id_branch!='' ? " and b.id_branch=".$id_branch."" :'')." 
			GROUP by br.id_branch,m.id_metal");
			
			$result                      = $data->result_array(); 
			$metalwise_sales_records     = array();
			 foreach($result as $r){
				   $metalwise_sales_records[] = $r;
				}
		     return $metalwise_sales_records;
			
	}
	
	
	//Credit Tab
	
	function get_CreditDetils($from_date,$to_date,$id_branch)
	{
            $sql=$this->db->query("SELECT IFNULL(c.credit_amt,0) as due_amt,IFNULL(cc.collection_amt,0) as collection_amt,br.name as branch_name
            FROM ret_billing b
            LEFT JOIN branch br ON br.id_branch=b.id_branch
            LEFT JOIN( SELECT b.id_branch,IFNULL(SUM(b.tot_bill_amount-b.tot_amt_received),0) as credit_amt
                        FROM ret_billing b
                        LEFT JOIN branch br ON br.id_branch=b.id_branch
                        where (date(b.bill_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') AND b.bill_status=1 and b.is_credit=1
                        ".($id_branch!='' ? " and b.id_branch=".$id_branch."" :'')." 
                        GROUP by b.id_branch) as c on c.id_branch=b.id_branch
            LEFT JOIN(SELECT b.id_branch,IFNULL(b.tot_amt_received,0) as collection_amt
                        FROM ret_billing b
                        LEFT JOIN branch br ON br.id_branch=b.id_branch
                        where (date(b.bill_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') AND b.bill_status=1 and b.bill_type=8
                        ".($id_branch!='' ? " and b.id_branch=".$id_branch."" :'')." 
                        GROUP by b.id_branch) as cc on cc.id_branch=b.id_branch
                        where (date(b.bill_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')
                        ".($id_branch!='' ? " and b.id_branch=".$id_branch."" :'')." 
            GROUP by b.id_branch");
        return $sql->result_array();
	}
	
	
	function get_due_pending_details()
    {
	    $reurn_data=array();
		$sql=$this->db->query("SELECT sum(b.tot_bill_amount-b.tot_amt_received-ifnull(c.tot_amt_received,0)) as pending_amt,br.name as branch_name,b.id_branch
        FROM ret_billing b
        LEFT JOIN branch br on br.id_branch=b.id_branch
        LEFT JOIN(SELECT b.tot_amt_received,b.ref_bill_id
                 FROM ret_billing b
                 WHERE b.bill_type=8) as c on c.ref_bill_id=b.bill_id
        WHERE b.bill_status=1 AND b.credit_status=2 and b.bill_type!=8 AND b.is_credit=1
        GROUP by b.id_branch");
        $credit_details=$sql->result_array();
        foreach($credit_details as $credit)
        {
            $reurn_data[]=array(
                                'pending_amt'=>$credit['pending_amt'],
                                'branch_name'=>$credit['branch_name'],
                                'over_due_amt'=>$this->over_due_pending($credit['id_branch']),
                               );
        }
        return $reurn_data;
    }
    
    function over_due_pending($id_branch)
    {
        $pending_amt=0;
        $sql=$this->db->query("SELECT sum(b.tot_bill_amount-b.tot_amt_received-ifnull(c.tot_amt_received,0)) as pending_amt,TIMESTAMPDIFF(month,(b.bill_date), current_date()),b.id_branch,b.bill_id
        FROM ret_billing b
        LEFT JOIN branch br on br.id_branch=b.id_branch
        LEFT JOIN(SELECT b.tot_amt_received,b.ref_bill_id
                 FROM ret_billing b
                 WHERE b.bill_type=8) as c on c.ref_bill_id=b.bill_id
        WHERE b.bill_status=1 AND b.credit_status=2 and b.bill_type!=8 AND b.is_credit=1 AND TIMESTAMPDIFF(month,(b.bill_date), current_date())>=3
        ".($id_branch!='' ? " and b.id_branch=".$id_branch."" :'')."");
        $pending_amt=($sql->row('pending_amt')!=null ? $sql->row('pending_amt'):0);
        return $pending_amt;
    }

	//Credit Tab
	
	
	//Stock and Branch Trasnfer
	
	function get_stock_category_details($FromDt,$ToDt,$id_branch)
	{
	    $return_data=array();
	    $op_date= date('Y-m-d',(strtotime('-1 day',strtotime($FromDt))));
		//print_r($op_date);exit;
		$sql = $this->db->query("SELECT IFNULL(b.name,'') as branch_name,c.name as category_name,
		IFNULL(blc.gross_wt,0) as op_blc_gwt,IFNULL(blc.net_wt,0) as op_blc_nwt,IFNULL(blc.piece,0) as op_blc_pcs,
		IFNULL(INW.gross_wt,0) as inw_gwt,IFNULL(INW.net_wt,0) as inw_nwt,IFNULL(INW.piece,0) as inw_pcs,
		IFNULL(s.gross_wt,0) as sold_gwt,IFNULL(s.net_wt,0) as sold_nwt,IFNULL(s.piece,0) as sold_pcs,
		IFNULL(br_out.gross_wt,0) as br_out_gwt,IFNULL(br_out.net_wt,0) as br_out_nwt,IFNULL(br_out.piece,0) as br_out_pcs
		
		FROM ret_taging t
		LEFT JOIN ret_product_master p on p.pro_id=t.product_id
		LEFT JOIN branch b on b.id_branch=t.current_branch
		left join ret_category c on c.id_ret_category=p.cat_id
		left join metal m on m.id_metal=c.id_metal
		
		LEFT JOIN (SELECT  p.cat_id,SUM(s.closing_gwt) as gross_wt,SUM(s.closing_nwt) as net_wt,SUM(s.closing_pcs) as piece,s.date,s.id_branch,b.name as branch_name
        FROM ret_stock_balance s
        LEFT JOIN branch b ON b.id_branch=s.id_branch
        LEFT JOIN ret_product_master p ON p.pro_id=s.id_product
        WHERE s.id_product is NOT null AND date(s.date)='$op_date'
         ".($id_branch!='' ? " and s.id_branch=".$id_branch."" :'')."
        GROUP by p.cat_id,s.id_branch) blc on blc.cat_id=p.cat_id
		
		LEFT JOIN (SELECT prod.cat_id,tag.tag_id,tag.product_id,sum(tag.gross_wt) as gross_wt,SUM(tag.net_wt) as net_wt,SUM(tag.piece) as piece
        FROM ret_taging tag
        LEFT JOIN ret_taging_status_log l on l.tag_id=tag.tag_id and l.status=0
        LEFT JOIN ret_product_master prod on prod.pro_id=tag.product_id
        WHERE (date(l.date) BETWEEN '$FromDt' AND '$ToDt') And l.status=0
        ".($id_branch!='' ? " and l.to_branch=".$id_branch."" :'')."
        GROUP by prod.cat_id,tag.current_branch) INW on INW.cat_id=p.cat_id
		
		LEFT JOIN (SELECT b.tag_id,sum(tag.gross_wt) as gross_wt,SUM(tag.net_wt) as net_wt,SUM(tag.piece) as piece,b.product_id,prod.cat_id
		FROM ret_taging tag
		LEFT JOIN ret_bill_details b on b.tag_id=tag.tag_id
		lEFT JOIN ret_billing bill on bill.bill_id=b.bill_id
		LEFT JOIN ret_product_master prod on prod.pro_id=b.product_id
		WHERE  bill.bill_status=1 and date(bill.bill_date) BETWEEN '$FromDt' AND '$ToDt'  AND b.product_id=prod.pro_id
		".($id_branch!='' ? " and bill.id_branch=".$id_branch." " :'')." 
		GROUP by prod.cat_id,bill.id_branch) s ON s.cat_id=p.cat_id
		
		LEFT JOIN (
		SELECT tag.tag_id,tag.product_id,sum(tag.gross_wt) as gross_wt,SUM(tag.net_wt) as net_wt,SUM(tag.piece) as piece,prod.cat_id
        FROM ret_taging tag
        LEFT JOIN ret_taging_status_log l on l.tag_id=tag.tag_id and (l.status=2 or l.status=3 or l.status=5 or l.status=4)
        LEFT JOIN ret_product_master prod on prod.pro_id=tag.product_id
        WHERE (date(l.date) BETWEEN '$FromDt' AND '$ToDt')  and (l.status=2 or l.status=3 or l.status=5 or l.status=4)
        ".($id_branch!='' ? " and l.from_branch=".$id_branch."" :'')."
        GROUP by prod.cat_id,tag.current_branch) br_out on br_out.cat_id=p.cat_id
		where t.tag_id is not null  ".($id_branch!='' ? " and t.current_branch=".$id_branch."" :'')."
	    GROUP by p.cat_id,t.current_branch");
	    $data=$sql->result_array();
	    foreach($data as $items)
	    {
	        $return_data[]=array(
	                             'branch_name'      =>$items['branch_name'],
	                             'category_name'    =>$items['category_name'],
	                             'opening_pcs'      =>$items['op_blc_pcs'],
	                             'opening_gwt'      =>$items['op_blc_gwt'],
	                             'opening_nwt'      =>$items['op_blc_nwt'],
	                             'tot_sales_pcs'    =>$items['sold_pcs'],
	                             'tot_sales_gwt'    =>$items['sold_gwt'],
	                             'tot_sales_nwt'    =>$items['sold_nwt'],
	                             'inw_pcs'          =>$items['inw_pcs'],
	                             'inw_gwt'          =>$items['inw_gwt'],
	                             'inw_nwt'          =>$items['inw_nwt'],
	                             'available_pcs'    =>$items['op_blc_pcs']+$items['inw_pcs']-$items['sold_pcs']-$items['br_out_pcs'],
	                             'available_gwt'    =>number_format($items['op_blc_gwt']+$items['inw_gwt']-$items['sold_gwt']-$items['br_out_gwt'],3,'.',''),
	                             'available_gwt'    =>number_format($items['op_blc_nwt']+$items['inw_nwt']-$items['sold_nwt']-$items['br_out_nwt'],3,'.',''),
	                            );
	    }
	   return $return_data;
	}
	
	
	function get_branch_transfer_details()
	{
	    $sql=$this->db->query("SELECT br.name as branch_name,IFNULL(a.tot_pcs,0) as yet_to_approve_pcs,IFNULL(i.tot_pcs,0) as intransit_pcs
        FROM ret_branch_transfer b 
        LEFT JOIN branch br ON br.id_branch=b.transfer_to_branch
        LEFT JOIN branch tb ON tb.id_branch=b.transfer_to_branch
        LEFT JOIN(SELECT b.transfer_to_branch,b.branch_transfer_id,SUM(b.pieces) as tot_pcs
        		 FROM ret_branch_transfer b 
                 LEFT JOIN branch br on br.id_branch=b.transfer_to_branch
                 WHERE b.status=1 GROUP by b.transfer_to_branch) as a on a.transfer_to_branch=b.transfer_to_branch
        LEFT JOIN(SELECT b.transfer_to_branch,b.branch_transfer_id,SUM(b.pieces) as tot_pcs
        		 FROM ret_branch_transfer b 
                 LEFT JOIN branch br on br.id_branch=b.transfer_to_branch
                 WHERE b.status=2 GROUP by b.transfer_to_branch) as i on i.transfer_to_branch=b.transfer_to_branch
        GROUP by b.transfer_to_branch");
        
        return $sql->result_array();
	}
	
	//Stock and Branch Trasnfer
	
	
	function get_new_customer($from_date,$to_date,$id_branch)
	{
	    $sql=$this->db->query("SELECT COUNT(c.id_customer) as customer,if(c.added_by=0,'Web',if(c.added_by=2,'Admin','Mobile App')) as added_by,IFNULL(v.village_name,'') as village_zone,
        IFNULL(b.name,'') as branch_name
        FROM customer c 
        LEFT JOIN village v on v.id_village=c.id_village
        LEFT JOIN branch b ON b.id_branch=c.id_branch
        WHERE (date(c.date_add) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')
        GROUP by c.id_village,c.added_by");         
        return $sql->result_array();
	}
	
	
	
	//Order Management
	
	function get_customer_order_details($from_date,$to_date)
	{
	    $sql=$this->db->query("SELECT concat(cus.firstname,'-',cus.mobile) as cus_name,p.product_name,d.totalitems,d.weight,b.name as branch_name,m.order_status
        FROM customerorder c
        LEFT JOIN customerorderdetails d on d.id_customerorder=c.id_customerorder
        LEFT JOIN ret_product_master p ON p.pro_id=d.id_product
        LEFT JOIN customer cus ON cus.id_customer=c.order_to
        LEFT JOIN branch b ON b.id_branch=c.order_from
        LEFT JOIN order_status_message m on m.id_order_msg=d.orderstatus
        WHERE c.order_for=2 and (date(c.order_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') ");
        //print_r($this->db->last_query());exit;
         return $sql->result_array();
	}
	
function get_order_details($from_date,$to_date,$order_for,$id_branch)
	{
	    $sql=$this->db->query("SELECT b.name as branch_name,c.order_from,
	    
	    
	    IFNULL(today_recd.received_pcs,0) as today_received_pcs,IFNULL(today_recd.received_wt,0) as today_received_wt,
	    
	    IFNULL(a.allocation_pending_pcs,0) as allocation_pending_pcs,IFNULL(a.allocation_pending_wt,0) as allocation_pending_wt,
	    
	    IFNULL(alloc_done.allocation_done_pcs,0) as allocation_done_pcs,IFNULL(alloc_done.allocation_done_wt,0) as allocation_done_wt,
	    
	    IFNULL(kar_pend.karigar_pending_pcs,0) as karigar_pending_pcs,IFNULL(kar_pend.karigar_pending_wt,0) as karigar_pending_wt,
	    
	    IFNULL(karigar_over_due.over_due_pcs,0) as karigar_over_due_pcs,IFNULL(karigar_over_due.over_due_wt,0) as karigar_over_due_wt,
	    
	    IFNULL(karigar_delivered.delivered_pcs,0) as karigar_delivered_pcs,IFNULL(karigar_delivered.delivered_wt,0) as karigar_delivered_wt,
	    
	    IFNULL(cus_del.delivery_ready_pcs,0) as cus_delivery_ready_pcs,IFNULL(cus_del.delivery_ready_wt,0) as cus_delivery_ready_wt,
	    
	    IFNULL(del.delivered_pcs,0) as cus_delivered_pcs,IFNULL(del.delivered_wt,0) as cus_delivered_wt,
	    
	    IFNULL(cus_over_due.over_due_pcs,0) as cus_over_due_pcs,IFNULL(cus_over_due.over_due_wt,0) as cus_over_due_wt,
	    
	    IFNULL(cus_pending.pending_pcs,0) as cus_pending_pcs,IFNULL(cus_pending.pending_wt,0) as cus_pending_wt
	    
	    
        FROM customerorder c 
        
        LEFT JOIN branch b ON b.id_branch=c.order_from
        
        LEFT JOIN customerorderdetails d on d.id_customerorder=c.id_customerorder
        
        LEFT JOIN(SELECT c.id_customerorder,SUM(d.totalitems) as received_pcs,SUM(d.weight) as received_wt,c.order_from
                 FROM customerorder c 
                 LEFT JOIN customerorderdetails d ON d.id_customerorder=c.id_customerorder
                 WHERE  (date(c.order_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') 
                 and c.order_for='$order_for'  GROUP by c.order_from) as today_recd ON today_recd.order_from=c.order_from
                 
         LEFT JOIN(SELECT c.id_customerorder,SUM(d.totalitems) as allocation_pending_pcs,SUM(d.weight) as allocation_pending_wt,c.order_from
                 FROM customerorder c 
                 LEFT JOIN customerorderdetails d ON d.id_customerorder=c.id_customerorder
                 WHERE  (date(c.order_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') 
                 and c.order_for='$order_for' and d.orderstatus<=0 GROUP by c.order_from) as a ON a.order_from=c.order_from
                 
        LEFT JOIN(SELECT c.id_customerorder,SUM(d.totalitems) as allocation_done_pcs,SUM(d.weight) as allocation_done_wt,c.order_from
                 FROM customerorder c 
                 LEFT JOIN customerorderdetails d ON d.id_customerorder=c.id_customerorder
                 WHERE  (date(c.order_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')
                 and c.order_for='$order_for' and d.orderstatus=3 GROUP by c.order_from) as alloc_done ON alloc_done.order_from=c.order_from
        
        LEFT JOIN(SELECT c.id_customerorder,SUM(d.totalitems) as karigar_pending_pcs,c.order_from,SUM(d.weight) as karigar_pending_wt
                 FROM customerorder c 
                 LEFT JOIN customerorderdetails d ON d.id_customerorder=c.id_customerorder
                 LEFT JOIN joborder j on j.id_order=d.id_orderdetails
                 WHERE (date(j.deliverydate) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')
                 and c.order_for='$order_for' and j.id is not null GROUP by c.order_from) as kar_pend ON kar_pend.order_from=c.order_from
        
        LEFT JOIN(SELECT c.id_customerorder,SUM(d.totalitems) as over_due_pcs,c.order_from,SUM(d.weight) as over_due_wt
             FROM customerorder c 
             LEFT JOIN customerorderdetails d ON d.id_customerorder=c.id_customerorder
              LEFT JOIN joborder j on j.id_order=d.id_orderdetails
             WHERE date(d.smith_due_date)<CURRENT_DATE() and d.orderstatus=3 and c.order_for='$order_for' GROUP by c.order_from) as karigar_over_due ON karigar_over_due.order_from=c.order_from
        
        LEFT JOIN(SELECT c.id_customerorder,SUM(d.totalitems) as delivered_pcs,c.order_from,SUM(d.weight) as delivered_wt
             FROM customerorder c 
             LEFT JOIN customerorderdetails d ON d.id_customerorder=c.id_customerorder
              LEFT JOIN joborder j on j.id_order=d.id_orderdetails
             WHERE (date(j.deliveredon) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') and j.orderstatus=4 and c.order_for='$order_for' GROUP by c.order_from) as karigar_delivered ON karigar_delivered.order_from=c.order_from
        
       
        LEFT JOIN(SELECT c.id_customerorder,SUM(d.totalitems) as order_plced_pcs,c.order_from,SUM(d.weight) as order_placed_wt
                 FROM customerorder c 
                 LEFT JOIN customerorderdetails d ON d.id_customerorder=c.id_customerorder
                 LEFT JOIN joborder j on j.id_order=d.id_orderdetails
                 WHERE (date(c.order_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')
                 and c.order_for='$order_for' and j.id is not null and (d.orderstatus=1 or d.orderstatus=2) GROUP by c.order_from) as p ON p.order_from=c.order_from
        
         LEFT JOIN(SELECT c.id_customerorder,SUM(d.totalitems) as delivery_ready_pcs,c.order_from,SUM(d.weight) as delivery_ready_wt
                 FROM customerorder c 
                 LEFT JOIN customerorderdetails d ON d.id_customerorder=c.id_customerorder
                 WHERE (date(d.deliverydate) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') and 
                 c.order_for='$order_for' and d.orderstatus=4 GROUP by c.order_from) as cus_del ON cus_del.order_from=c.order_from
                 
                  
        LEFT JOIN(SELECT c.id_customerorder,SUM(d.totalitems) as delivered_pcs,c.order_from,SUM(d.weight) as delivered_wt
             FROM customerorder c 
             LEFT JOIN customerorderdetails d ON d.id_customerorder=c.id_customerorder
             WHERE (date(d.delivered_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')
             and c.order_for='$order_for' and d.orderstatus=5 GROUP by c.order_from) as del ON del.order_from=c.order_from
        
        LEFT JOIN(SELECT c.id_customerorder,SUM(d.totalitems) as pending_pcs,c.order_from,SUM(d.weight) as pending_wt
             FROM customerorder c 
             LEFT JOIN customerorderdetails d ON d.id_customerorder=c.id_customerorder
              LEFT JOIN joborder j on j.id_order=d.id_orderdetails
             WHERE date(d.cus_due_date)=CURRENT_DATE() and d.orderstatus<=3 and c.order_for='$order_for' GROUP by c.order_from) as cus_pending ON cus_pending.order_from=c.order_from
        
        
         LEFT JOIN(SELECT c.id_customerorder,SUM(d.totalitems) as over_due_pcs,c.order_from,SUM(d.weight) as over_due_wt
             FROM customerorder c 
             LEFT JOIN customerorderdetails d ON d.id_customerorder=c.id_customerorder
              LEFT JOIN joborder j on j.id_order=d.id_orderdetails
             WHERE date(d.cus_due_date)<CURRENT_DATE() and d.orderstatus<=3 and c.order_for='$order_for' GROUP by c.order_from) as cus_over_due ON cus_over_due.order_from=c.order_from
        
        
        where c.order_from is not null
        GROUP by c.order_from");
        
        
        //print_r($this->db->last_query());exit;
        
        return $sql->result_array();
	}
	
	//Order Management

}
?>
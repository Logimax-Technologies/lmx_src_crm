<?php

if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ret_other_inventory_model extends CI_Model

{

    

    const TABLE_NAME 	= "ret_other_inventory_item"; 

    

	function __construct()

    {

        parent::__construct();

    }

    // General Functions

    public function insertData($data,$table)

    {

    	$insert_flag = 0;

		$insert_flag = $this->db->insert($table, $data);

		return ($insert_flag == 1 ? $this->db->insert_id(): 0);

	}

	public function insertBatchData($data,$table)

    {

    	$insert_flag = 0;

		$insert_flag = $this->db->insert_batch($table, $data);

		if ($this->db->affected_rows() > 0){

			return TRUE;

		}else{

			return FALSE;

		}

	}

	public function updateBatchData($data,$table,$id_field,$id_value)

    {

    	$insert_flag = 0;

		$this->db->where($id_field,$id_value);

		$updat=$this->db->update_batch($table,$data);

		print_r($this->db->last_query());exit;

		if ($this->db->affected_rows() > 0){

			return TRUE;

		}else{

			return FALSE;

		}

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

	

	function get_currentBranchName($branch_id){

		$branch_name = "";

		$branch_query = $this->db->query("SELECT id_branch, name FROM branch WHERE id_branch = $branch_id");

		if($branch_query->num_rows() > 0){

			$branch_name = $branch_query->row()->name;

		}

		return $branch_name;

	}

	

	function get_headOffice()

	{

		$data=$this->db->query("SELECT b.is_ho,b.id_branch,name FROM branch b where b.is_ho=1");

		return $data->row_array();

	}

	

	function getBranchDayClosingData($id_branch)

    {

        $sql = $this->db->query("SELECT id_branch,is_day_closed,entry_date from ret_day_closing where id_branch=".$id_branch);  

        return $sql->row_array();

    }

	

	function generatePurNo()

	{

	    $lastno = NULL;

	    $sql = "SELECT MAX(otr_inven_pur_order_ref) as lastorder_no

					FROM ret_other_inventory_purchase o

					ORDER BY otr_inven_pur_id DESC 

					LIMIT 1"; 

			$result = $this->db->query($sql);

			if( $result->num_rows() > 0){

				$lastno = $result->row()->lastorder_no;				

			} 

			

	    if($lastno != NULL)

		{ 

		    //$max_num = explode("-",$lastno);

            $number = (int) $lastno;

            $number++;

            $order_number = str_pad($number, 5, '0', STR_PAD_LEFT);	

		}

		else

		{

           $order_number = str_pad('1', 5, '0', STR_PAD_LEFT);

		}

		

		return $order_number;

	}

	

	function generateItemRefNo()

	{

	    $lastno = NULL;

	    $sql = "SELECT MAX(item_ref_no) as item_ref_no

					FROM ret_other_inventory_purchase_items_details o

					ORDER BY pur_item_detail_id DESC 

					LIMIT 1"; 

			$result = $this->db->query($sql);

			if( $result->num_rows() > 0){

				$lastno = $result->row()->item_ref_no;				

			} 

			

	    if($lastno != NULL)

		{ 

		    //$max_num = explode("-",$lastno);

            $number = (int) $lastno;

            $number++;

            $order_number = str_pad($number, 5, '0', STR_PAD_LEFT);	

		}

		else

		{

           $order_number = str_pad('1', 5, '0', STR_PAD_LEFT);

		}

		

		return $order_number;

	}

	

	

	

	function ajax_get_other_inventory()

    {

		$id_other_item = $this->db->query("SELECT i.id_other_item,i.name,i.short_code,i.purchase_id_uom,stock_id_uom,i.sku_id,i.item_for,IFNULL(i.item_image,'') as image,u.uom_name,i.name as item_name  

		FROM ret_other_inventory_item i

		Left join ret_uom u on u.uom_id= i.purchase_id_uom

		ORDER BY id_other_item desc");

		return $id_other_item->result_array();

	}





    function get_other_inventory_records($id_other_item)

    {

		$id_other_item = $this->db->query("select id_other_item,name,short_code,sku_id,purchase_id_uom,item_for,item_image,issue_preference,id_inv_size

		from ret_other_inventory_item  

		where  id_other_item=".$id_other_item);

		return $id_other_item->row_array();

	}

	

	function get_inv_item_reorder_details($id_other_item)

	{

	    $sql=$this->db->query("SELECT s.id_branch,s.id_other_item,s.min_pcs,s.max_pcs,b.name as branch_name

        FROM ret_other_inventory_reorder_settings s 

        LEFT JOIN branch b ON b.id_branch=s.id_branch

        where s.id_other_item=".$id_other_item."");

        return $sql->result_array();

	}



    public function update_other_inventory($data,$id,$id_field,$table)

    {    

	    $edit_flag = 0;

	    $this->db->where($id_field,$id);

		$edit_flag = $this->db->update($table,$data);

		return ($edit_flag==1?$id:0);

	}



    function getActiveskuid($SearchTxt,$searchField)

    {

		$data = $this->db->query("SELECT ot.id_other_item as value,ot.sku_id as label,name,ot.purchase_id_uom , ot.item_hsn_code,ot.issue_preference,

		IF(ot.purchase_id_uom ='1','GM','CARAT') as uom_name FROM ret_other_inventory_item ot

		WHERE ot.item_hsn_code is NULL AND  ot.".$searchField." LIKE '%".$SearchTxt."%'");

		//print_r($this->db->last_query());exit;

		return $data->result_array();

	}



    

    

    

    function ajax_getotheritem()

    {

		$id_other_item_type = $this->db->query("SELECT * FROM ret_other_inventory_item_type ORDER BY id_other_item_type desc");

		return $id_other_item_type->result_array();

	}

    

    function get_inventory_category()

    {

        $id_other_item_type = $this->db->query("SELECT * FROM ret_other_inventory_item_type ORDER BY id_other_item_type desc");

		return $id_other_item_type->result_array();

    }

    

    function get_InventoryCategory($id_other_item_type)

    {

        $id_other_item_type = $this->db->query("SELECT t.id_other_item_type,t.qrcode,i.issue_preference

        FROM ret_other_inventory_item i 

        LEFT JOIN ret_other_inventory_item_type t ON t.id_other_item_type=i.item_for

        WHERE i.id_other_item=".$id_other_item_type."");

        //print_r($this->db->last_query());exit;

		return $id_other_item_type->row_array();

    }

    

    function get_other_item_records($id_other_item_type)

    {

		$id_other_item_type = $this->db->query("select id_other_item_type,name,outward_type,asbillable,expirydatevalidate,reorderlevel 

		from ret_other_inventory_item_type  

		where  id_other_item_type=".$id_other_item_type);

		//print_r($this->db->last_query());exit;

		return $id_other_item_type->row_array();

	}

	

    public function update_otheritem($data,$id)

    {    	

    	$edit_flag = 0;

    	$this->db->where('id_other_item_type',$id); 

		$edit_flag = $this->db->update('ret_other_inventory_item_type',$data);

		//print_r($this->db->last_query());exit;

		return ($edit_flag==1?$id:0);

	}

			 



    function getActiveItemname()

    {

		$data = $this->db->query("SELECT id_other_item_type,name FROM `ret_other_inventory_item_type` WHERE status = 1");

		return $data->result_array();

	}

	

	

	//purchase entry

	function get_other_inventory_item()

	{

	    $sql=$this->db->query("SELECT i.id_other_item,i.name

        FROM ret_other_inventory_item i");

        return $sql->result_array();

	}

	

	function get_supplier()

	{

	    $sql=$this->db->query("SELECT id_karigar,firstname as karigar_name FROM `ret_karigar` WHERE karigar_for=4");

	    return $sql->result_array();

	}

	

	function ajax_getPurchaseEntrylist($data)

	{

	    $sql=$this->db->query("SELECT p.otr_inven_pur_id,k.firstname as supplier_name,date_format(p.entry_date,'%d-%m-%Y') as entry_date,date_format(p.supplier_bill_date,'%d-%m-%Y') as supplier_bill_date,IFNULL(p.supplier_order_ref_no,'') as supplier_order_ref_no,IFNULL(p.otr_inven_pur_order_ref,'') as pur_order_ref_no,

	    IFNULL(pur.tot_pcs,0) as tot_pcs,IFNULL(pur.tot_amount,0) as tot_amount

        FROM ret_other_inventory_purchase p 

        LEFT JOIN ret_karigar k ON k.id_karigar=p.otr_inven_pur_supplier

        LEFT JOIN(SELECT i.otr_inven_pur_id,IFNULL(SUM(i.inv_pur_itm_qty),0) as tot_pcs,IFNULL(SUM(i.inv_pur_itm_total),0) as tot_amount

        FROM ret_other_inventory_purchase_items i 

        GROUP by i.otr_inven_pur_id) as pur ON pur.otr_inven_pur_id=p.otr_inven_pur_id

        where p.otr_inven_pur_id IS NOT NULL

        ".($data['from_date'] != '' && $data['to_date']!='' ? ' and date(p.entry_date) BETWEEN "'.date('Y-m-d',strtotime($data['from_date'])).'" AND "'.date('Y-m-d',strtotime($data['to_date'])).'"' : '')." "); 

        return $sql->result_array();

	}

	//purchase entry

	

	

	//stock details

	function other_inventory_stock($data)

    {

        $day_closing=$this->getBranchDayClosingData($data['id_branch']);

    	$stock_detail = array();

    	

           

		$date=($day_closing['is_day_closed']==1 ? $day_closing['entry_date']:date("Y-m-d"));

		

		if(( date('Y-m-d',strtotime($data['from_date'])) !=$date) && (date('Y-m-d',strtotime($data['from_date']))!=$date))

		{

			$sql = $this->db->query("SELECT s.id_other_item,i.name,

            IFNULL(s.op_blc_pcs,0) as op_blc_pcs,IFNULL(s.op_blc_amt,0) as op_blc_amt,

            IFNULL(s.inw_pcs,0) as inw_pcs,IFNULL(s.inw_amt,0) as inw_amount,

            IFNULL(s.out_pcs,0) as out_pcs,IFNULL(s.out_amt,0) as out_amount

            FROM ret_other_inventory_stock s 

            LEFT JOIN ret_other_inventory_item i ON i.id_other_item=s.id_other_item

            where date(s.date) BETWEEN  '".date('Y-m-d',strtotime($data['from_date']))."' AND '".date('Y-m-d',strtotime($data['to_date']))."' 

            ".($data['id_branch']!='' ? " and s.id_branch=".$data['id_branch']."" :'')."

            ".($data['id_other_item']!='' ? " and s.id_other_item=".$data['id_other_item']."" :'')."

            order by s.id_other_item ASC");

            $result = $sql->result_array();

					

		}

		else{

		$op_date= date('Y-m-d',(strtotime('-1 day',strtotime($data['from_date']))));

		

		$sql = $this->db->query("SELECT i.id_other_item,i.name,

		IFNULL(blc.piece,0) as op_blc_pcs,IFNULL(blc.closing_amt,0) as op_blc_amt,

		IFNULL(inw.inw_pcs,0) as inw_pcs,IFNULL(inw.inw_amount,0) as inw_amount,

		IFNULL(br_out.out_pcs,0) as out_pcs,IFNULL(br_out.out_amount,0) as out_amount

        FROM ret_other_inventory_item i

        

        LEFT JOIN (SELECT s.id_other_item as id_other_item,s.closing_pcs as piece,s.date,s.closing_amt

        FROM ret_other_inventory_stock s

        WHERE s.id_other_item is NOT null AND date(s.date)='$op_date'

        ".($data['id_branch']!='' ? " and s.id_branch=".$data['id_branch']."" :'')."

        GROUP by s.id_other_item) blc on blc.id_other_item=i.id_other_item

        

		LEFT JOIN (

		SELECT l.item_id,IFNULL(SUM(l.no_of_pieces),0) as inw_pcs,IFNULL(SUM(l.amount),0) as inw_amount

        FROM ret_other_inventory_purchase_items_log l 

		WHERE (date(l.date) BETWEEN '".date('Y-m-d',strtotime($data['from_date']))."' AND '".date('Y-m-d',strtotime($data['to_date']))."') AND l.status=0

		".($data['id_branch']!='' ? " and l.to_branch=".$data['id_branch']."" :'')."

		GROUP by l.item_id) inw ON inw.item_id=i.id_other_item

		

		LEFT JOIN (

		SELECT l.item_id,IFNULL(SUM(l.no_of_pieces),0) as out_pcs,IFNULL(SUM(l.amount),0) as out_amount

        FROM ret_other_inventory_purchase_items_log l 

		WHERE (date(l.date) BETWEEN '".date('Y-m-d',strtotime($data['from_date']))."' AND '".date('Y-m-d',strtotime($data['to_date']))."') AND (l.status=1 or l.status=4 or l.status=3)

		".($data['id_branch']!='' ? " and l.from_branch=".$data['id_branch']."" :'')."

		GROUP by l.item_id) br_out ON br_out.item_id=i.id_other_item

		

		where i.id_other_item is not null  

		".($data['id_other_item']!='' ? " and i.id_other_item=".$data['id_other_item']."" :'')." 

		GROUP by i.id_other_item");

		}

	    //print_r($this->db->last_query());exit;

    	$result = $sql->result_array();

    	foreach($result as $items)

    	{

    	    $stock_detail[]=array(

    	                          'id_other_item'   =>$items['id_other_item'],

    	                          'item_name'       =>$items['name'],

    	                          'op_blc_pcs'      =>$items['op_blc_pcs'],

    	                          'op_blc_amt'      =>$items['op_blc_amt'],

    	                          'inw_pcs'         =>$items['inw_pcs'],

    	                          'inw_amount'      =>$items['inw_amount'],

    	                          'out_pcs'         =>$items['out_pcs'],

    	                          'out_amount'      =>$items['out_amount'],

    	                          'closing_pcs'     =>($items['op_blc_pcs']+$items['inw_pcs']-$items['out_pcs']),

    	                          'closing_amt'     =>number_format($items['op_blc_amt']+$items['inw_amount']-$items['out_amount'],3,'.',''),

    	                         );

    	}

    	return $stock_detail;

    }

    

	//stock details

    

    function get_invnetory_item($data)

    {

        $responseData=array();

        $sql=$this->db->query("SELECT i.name as item_name,IFNULL(d.tot_pcs,0) as tot_pcs,i.id_other_item,IFNULL(i.item_image,'') as item_image,i.sku_id

        FROM ret_other_inventory_item_type t 

        LEFT JOIN ret_other_inventory_item i ON i.item_for=t.id_other_item_type

        LEFT JOIN (SELECT d.other_invnetory_item_id,IFNULL(SUM(d.piece),0) as tot_pcs

        FROM ret_other_inventory_purchase_items_details d

        WHERE d.status=0 

        ".($data['id_branch']!='' ? " and d.current_branch=".$data['id_branch']."" :'')." 

        GROUP by d.other_invnetory_item_id) as d on d.other_invnetory_item_id=i.id_other_item

        WHERE t.outward_type=1

        having tot_pcs>0");

        //print_r($this->db->last_query());exit;

        $result= $sql->result_array();

        foreach($result as $items)

        {

            $responseData[]=array(

                                 'item_name'    =>$items['item_name'],

                                 'tot_pcs'      =>$items['tot_pcs'],

                                 'id_other_item'=>$items['id_other_item'],

                                 'item_image'   =>$items['item_image'],

                                 'sku_id'       =>$items['sku_id'],

                                 );

        }

        return $responseData;

    }

    

    

    

    function get_customer()

    {

        $sql=$this->db->query("SELECT cus.id_customer,concat(cus.firstname,'-',cus.mobile) as cus_name

        FROM customer cus 

        WHERE cus.active=1");

        return $sql->result_array();

    }

    

    

    function get_other_inventory_purchase_items_details($id_other_item,$id_branch,$issue_preference,$total_pcs)

    {

        $sql=$this->db->query("SELECT * FROM `ret_other_inventory_purchase_items_details` 

        WHERE other_invnetory_item_id=".$id_other_item." AND current_branch=".$id_branch." AND status=0

        ".($issue_preference==1 ? 'order by pur_item_detail_id ASC' :'order by pur_item_detail_id DESC')."

        LIMIT ".$total_pcs."");

        //print_r($this->db->last_query());exit;

        return $sql->result_array();

    }

	

	function get_OtherInventoryIssueDetails($data)

	{

	    $sql=$this->db->query("SELECT i.id_inventory_issue,date_format(i.issue_date,'%d-%m-%Y') as issue_date,br.name as branch_name,i.no_of_pieces,IFNULL(s.tot_amount,0) as approx_amt,IFNULL(i.remarks,'') as remarks,

        cus.firstname as cus_name,emp.firstname as given_by,t.name as item_name,IFNULL(bill.bill_no,'') as bill_no,IFNULL(i.bill_id,'') as bill_id

        FROM ret_other_invnetory_issue i 

        LEFT JOIN branch br ON br.id_branch=i.id_branch

        LEFT JOIN employee emp ON emp.id_employee=i.created_by

        LEFT JOIN ret_other_inventory_item t ON t.id_other_item=i.id_other_item

        LEFT JOIN ret_billing bill ON bill.bill_id=i.bill_id

        LEFT JOIN customer cus ON cus.id_customer=bill.bill_cus_id

        LEFT JOIN(SELECT d.id_inventory_issue,IFNULL(SUM(d.amount),0) as tot_amount

        FROM ret_other_inventory_purchase_items_details d

        GROUP by d.id_inventory_issue) as s ON s.id_inventory_issue=i.id_inventory_issue

        where ".($data['from_date'] != '' && $data['to_date']!='' ? ' date(i.issue_date) BETWEEN "'.date('Y-m-d',strtotime($data['from_date'])).'" AND "'.date('Y-m-d',strtotime($data['to_date'])).'"' : '')."

        ".($data['id_branch']!='' && $data['id_branch']!=0 ? " and i.id_branch=".$data['id_branch']."" :'')."");

        return $sql->result_array();

	}

	

	public function skuid_available($sku_id,$id_other_item="")

    {

        $this->db->select('sku_id');

        $this->db->where('sku_id', $sku_id);

        $status=$this->db->get('ret_other_inventory_item');

        if($status->num_rows()>0)

        {

            return TRUE;

        }

    }

	

	

	

	//Size Master

	function ajax_getOtherInventorySizeList($data)

	{

	    $sql=$this->db->query("SELECT * FROM `ret_other_inventory_size`");

	    return $sql->result_array();

	}

	

	function get_packaging_size($id)

	{

	    $sql=$this->db->query("SELECT * FROM `ret_other_inventory_size` where id_inv_size=".$id."");

	    //print_r($this->db->last_query());exit;

	    return $sql->row_array();

	}

	

	function get_ActivePackagingItemSize()

	{

	    $sql=$this->db->query("SELECT * FROM `ret_other_inventory_size` where status=1");

	    //print_r($this->db->last_query());exit;

	    return $sql->result_array();

	}

	//Size Master

	

	

	function get_bill_details($data)

	{

	    $dcData=$this->getBranchDayClosingData($data['id_branch']);

	    $sql=$this->db->query("SELECT b.bill_id,concat(b.bill_no,'-',cus.mobile) as cus_bill_no

        FROM ret_billing b 

        LEFT JOIN customer cus ON cus.id_customer=b.bill_cus_id

        WHERE b.bill_status=1 and b.id_branch=".$data['id_branch']." and date(b.bill_date)='".$dcData['entry_date']."'");

        //print_r($this->db->last_query());exit;

        return $sql->result_array();

	}

	

	

	//Available Stock Details

	function get_AvailableStockDetails($data)

	{

	    $sql=$this->db->query("SELECT i.name as item_name,i.sku_id,s.size_name,SUM(d.piece) as tot_pcs,SUM(d.amount) as tot_amount,br.name as branch_name,d.other_invnetory_item_id

        FROM ret_other_inventory_purchase_items_details d 

        LEFT JOIN ret_other_inventory_item i ON i.id_other_item=d.other_invnetory_item_id

        LEFT JOIN ret_other_inventory_size s ON s.id_inv_size=i.id_inv_size

        LEFT JOIN branch br ON br.id_branch=d.current_branch

        WHERE d.status=0 

        ".($data['id_branch']!='' && $data['id_branch']!=0 ? " and d.current_branch=".$data['id_branch']."" :'')."

        ".($data['id_inv_size']!='' ? " and s.id_inv_size=".$data['id_size']."" :'')."

        ".($data['id_other_item']!='' ? " and d.other_invnetory_item_id=".$data['id_other_item']."" :'')."

        GROUP by d.current_branch,d.other_invnetory_item_id");

        return $sql->result_array();

	}

	//Available Stock Details

	

	//Product Mapping

	function get_ActiveProduct()

	{

	    $sql=$this->db->query("SELECT p.pro_id FROM ret_product_master p WHERE p.product_status=1");

	    return $sql->result_array();

	}

	

	function check_other_inv_products_maping($id_product,$inv_des_otheritemid)

	{

		$sql=$this->db->query("SELECT * FROM `ret_other_inventory_product_link` WHERE inv_pro_id=".$id_product." AND inv_des_otheritemid=".$inv_des_otheritemid." ");

		//print_r($this->db->last_query());exit;

		if($sql->num_rows() == 0){

			return TRUE;

		}else{

			return FALSE;

		}

	}

	

	function get_item_mapping_details($data)

	{

	    $sql=$this->db->query("SELECT p.inv_des_id,pro.pro_id,pro.product_name,i.id_other_item,i.name as item_name,s.size_name

        FROM ret_other_inventory_product_link p 

        LEFT JOIN ret_product_master pro ON pro.pro_id=p.inv_pro_id

        LEFT JOIN ret_other_inventory_item i ON i.id_other_item=p.inv_des_otheritemid

        LEFT JOIN ret_other_inventory_size s ON s.id_inv_size=i.id_inv_size

        where p.inv_des_id IS NOT NULL 

        ".($data['id_product']!='' ? " and p.inv_pro_id=".$data['id_product']."" :'')."

        ".($data['id_other_item']!='' ? " and p.inv_des_otheritemid=".$data['id_other_item']."" :'')."

        ");

        return $sql->result_array();

	}

	

	

	function get_productMappedDetails($id_branch)

	{

	    $responseData=array();

	    $sql=$this->db->query("SELECT p.pro_id FROM ret_product_master p WHERE p.product_status=1");

	    $result= $sql->result_array();

	    foreach($result as $items)

	    {

	        $responseData[]=array(

	                              'pro_id'=>$items['pro_id'],

	                              'item_details'=>$this->get_product_linked_items($items['pro_id'],$id_branch),

	                             );

	    }

	    return $responseData;

	}

	

	

	function get_product_linked_items($pro_id,$id_branch)

	{

	    $sql=$this->db->query("SELECT i.id_other_item,i.name as item_name,IFNULL(i.item_image,'') as item_image,IFNULL(d.tot_pcs,0) as tot_pcs,i.sku_id

        FROM ret_other_inventory_product_link l 

        LEFT JOIN ret_other_inventory_item i ON i.id_other_item=l.inv_des_otheritemid

        LEFT JOIN (

            SELECT d.other_invnetory_item_id,IFNULL(SUM(d.piece),0) as tot_pcs

            FROM ret_other_inventory_purchase_items_details d

            WHERE d.status=0 AND d.current_branch=".$id_branch."

            GROUP by d.other_invnetory_item_id

        ) as d on d.other_invnetory_item_id=i.id_other_item

        where inv_pro_id=".$pro_id." GROUP by i.id_other_item");

        //print_r($this->db->last_query());exit;

	    return $sql->result_array();

	}

	

	//Product Mapping

	

	

	//Reorder Report

	function get_reorder_report($data)

	{

        $sql=$this->db->query("SELECT s.id_branch,s.id_other_item,s.min_pcs,s.max_pcs,br.name as branch_name,i.name as item_name,IFNULL(d.tot_pcs,0) as available_pcs

        FROM ret_other_inventory_reorder_settings s 

        LEFT JOIN ret_other_inventory_item i ON i.id_other_item=s.id_other_item

        LEFT JOIN branch br ON br.id_branch=s.id_branch

        LEFT JOIN(SELECT d.other_invnetory_item_id,SUM(d.piece) as tot_pcs,d.current_branch

        FROM ret_other_inventory_purchase_items_details d 

        WHERE d.status=0 ".($data['id_branch']!='' ? " and d.current_branch=".$data['id_branch']."" :'')."

        GROUP by d.current_branch,d.other_invnetory_item_id) as d ON d.other_invnetory_item_id=s.id_other_item AND d.current_branch=s.id_branch

        WHERE s.id_inv_reorder_settings IS NOT NULL

        ".($data['id_branch']!='' ? " and s.id_branch=".$data['id_branch']."" :'')."");

        return $sql->result_array();

	}

	//Reorder Report

	

}

?>
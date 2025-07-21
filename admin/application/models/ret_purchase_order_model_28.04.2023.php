<?php
if( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ret_purchase_order_model extends CI_Model
{
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
			return $this->db->insert_id();
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
	public function updateMultipleWhereData($data,$where,$table)
    {    
	    $edit_flag = 0;
	    $this->db->where($where); 
		$edit_flag = $this->db->update($table,$data);
		return ($edit_flag==1?1:0);
	}
	public function deleteData($id_field,$id_value,$table)
    {
        $this->db->where($id_field, $id_value);
        $status= $this->db->delete($table); 
		return $status;
	}
	
	
	function get_FinancialYear()
	{
		$sql=$this->db->query("SELECT fin_year_code From ret_financial_year where fin_status=1");
		return $sql->row_array();
	}
	
	function generatePurNo()
	{
	    $lastno = NULL;
	    $sql = "SELECT MAX(pur_no) as lastorder_no
					FROM customerorder o
					ORDER BY id_customerorder DESC 
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
	
	
	function generatePaymentNo()
	{
	    $lastno = NULL;
	    $sql = "SELECT MAX(pay_refno) as lastorder_no
					FROM ret_po_payment p
					ORDER BY pay_id DESC 
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
	
	function generatePurRefOrderNo($is_suspense_stock,$gst_bill_type="")
	{
	    $fin_year = $this->get_FinancialYear();
	    
	    $lastno = NULL;
	    //$sql = "SELECT (po_ref_no) as lastorder_no FROM ret_purchase_order p where fin_year_code=".$fin_year['fin_year_code']." and p.is_suspense_stock=".$is_suspense_stock."  
	    $sql = "SELECT (po_ref_no) as lastorder_no FROM ret_purchase_order p where p.is_suspense_stock=".$is_suspense_stock."  
	    ORDER BY po_id DESC LIMIT 1"; 
			//print_r($sql);exit;
			$result = $this->db->query($sql);
			if( $result->num_rows() > 0){
				$lastno = $result->row()->lastorder_no;				
			} 
				
	    if($lastno != NULL)
		{ 
		    $max_num = explode("-",$lastno);
            $number = (int) $max_num[1];
            $number++;
            $order_number = str_pad($number, 5, '0', STR_PAD_LEFT);	
		}
		else
		{
           $order_number = str_pad('1', 5, '0', STR_PAD_LEFT);
		}
	
		return $order_number;
	}
	
	
	
	function get_ActiveWeightRange($data)
	{
	    $sql=$this->db->query("SELECT w.id_weight,concat(w.value,'',m.uom_name) as name,w.from_weight,w.to_weight,w.value
        FROM ret_weight w 
        LEFT JOIN ret_uom m ON m.uom_id=w.id_uom
        WHERE w.id_weight IS NOT NULL AND w.id_product=".$data['id_product']."");
        return $sql->result_array();
	}
	
	
	
	
	function get_purchase_order_Details($data)
	{
        $sql=$this->db->query("SELECT c.id_customerorder,IFNULL(c.order_pcs,0) as order_pcs,IFNULL(c.order_approx_wt,0) as order_approx_wt,IFNULL(c.delivered_wt,0) as delivered_wt,IFNULL(c.delivered_qty,0) as delivered_qty,k.firstname as karigar_name,
        date_format(c.order_date,'%d-%m-%Y') as order_date,c.pur_no,IFNULL(k.contactno1,'') as mobile,
        if(c.order_for=1,'Stock Order','Customer Order') as order_for,cus.order_no,br.name as cus_order_branch,m.order_status as order_status_msg,m.color,c.order_status
        FROM customerorder c 
        LEFT JOIN ret_karigar k ON k.id_karigar=c.id_karigar
        LEFT JOIN customerorder cus ON cus.id_customerorder=c.cus_ord_ref
        LEFT JOIN branch br ON br.id_branch=cus.order_from
        LEFT JOIN order_status_message m ON m.id_order_msg=c.order_status
        WHERE c.pur_no IS NOT NULL and c.order_type=1
        ".($data['from_date']!='' && $data['to_date']!=''  ? " and (date(c.order_date) BETWEEN '".date('Y-m-d',strtotime($data['from_date']))."' AND '".date('Y-m-d',strtotime($data['to_date']))."')" :'')."
        Order by c.id_customerorder DESC");
        //print_r($this->db->last_query());exit;
        return $sql->result_array();
	}
	
     function get_karigar_order_details($id_customerorder)
	{
	    $returnData = array();
	    $sql=$this->db->query("SELECT (d.totalitems) as tot_items,concat(w.value,'',u.uom_name) as weight_range,k.firstname as karigar_name,IFNULL(k.email,'') as email,
	    c.company_name, p.product_name,des.design_name,concat(s.value,' ',s.name) as size,IFNULL(k.contactno1,'') as mobile,
	    cus.order_no,date_format(cus.order_date,'%d-%m-%Y') as order_date, emp.firstname as emp_name,
	    subDes.sub_design_name,cus.pur_no, date_format(d.smith_due_date, '%d-%m-%Y') as smith_due_date,IFNULL(d.description,'') as description,
	    IFNULL(w.value,0) as approx_wt,cus.id_karigar as id_vendor,
	    cus.id_karigar,cus.order_for,IFNULL(d.weight,'') as weight,d.id_orderdetails,IFNULL(ord.id_orderdetails,'') as cus_ord_detail_id,ord.orderno
        FROM customerorder cus 
        JOIN company c 
        LEFT JOIN customerorderdetails d ON d.id_customerorder=cus.id_customerorder
        LEFT JOIN ret_karigar k ON k.id_karigar=cus.id_karigar
        LEFT JOIN ret_product_master p ON p.pro_id=d.id_product
        LEFT JOIN ret_design_master des ON des.design_no=d.design_no
        LEFT JOIN ret_sub_design_master subDes ON subDes.id_sub_design=d.id_sub_design 
        LEFT JOIN ret_size s ON s.id_size=d.size LEFT JOIN ret_weight w ON w.id_weight=d.weight 
        LEFT JOIN ret_uom u ON u.uom_id=w.id_uom 
        LEFT JOIN employee emp on emp.id_employee=cus.order_taken_by 
        
        LEFT JOIN customerorder cusord ON cusord.id_customerorder = cus.cus_ord_ref
        LEFT JOIN customerorderdetails ord ON ord.id_customerorder = cusord.id_customerorder

        WHERE cus.pur_no IS NOT NULL  AND cus.id_customerorder=".$id_customerorder."
        GROUP by d.id_orderdetails
        Order by d.id_product,d.design_no,d.id_sub_design,w.value");
        //print_r($this->db->last_query());exit;
        $result = $sql->result_array();
        foreach($result as $items)
        {
            if($items['cus_ord_detail_id']!='')
            {
                $items['images'] = $this->get_order_images($items['cus_ord_detail_id']);
            }
            
            $returnData[]=$items;
        }
        return $returnData;
	}
	
	function get_order_images($id_orderdetails)
    {
        $sql = $this->db->query("SELECT  c.image,d.orderno
        FROM customer_order_image c 
        LEFT JOIN customerorderdetails d ON d.id_orderdetails = c.id_orderdetails
        where c.id_orderdetails=".$id_orderdetails."");
        return $sql->result_array();
    }
	
	function get_karigar_order_products($id_customerorder)
	{
	    $responseData=array();
	    $sql=$this->db->query("SELECT p.product_name,des.design_name,subDes.sub_design_name,d.id_product,d.design_no,d.id_sub_design,
	    IFNULL(d.description,'') as description,IFNULL(c.cus_ord_ref,'') as cus_ord_ref
        FROM customerorder c 
        LEFT JOIN customerorderdetails d ON d.id_customerorder=c.id_customerorder
        LEFT JOIN ret_product_master p ON p.pro_id=d.id_product
        LEFT JOIN ret_design_master des ON des.design_no=d.design_no
        LEFT JOIN ret_sub_design_master subDes ON subDes.id_sub_design=d.id_sub_design
        WHERE c.pur_no IS NOT NULL AND c.id_customerorder=".$id_customerorder."
        GROUP by d.id_product,d.design_no,d.id_sub_design");
        $result = $sql->result_array();
        foreach($result as $items)
        {
            $weight_details=[];
            $size_details=[];
            $tot_items=[];
            $item_details = $this->get_karigar_order_product_details($id_customerorder,$items['id_product'],$items['design_no'],$items['id_sub_design']);
            if($items['cus_ord_ref']=='')
            {
                $image_details = $this->get_karigar_order_detail_images($id_customerorder,$items['id_product'],$items['design_no'],$items['id_sub_design']);
            }else
            {
                $image_details = $this->get_customer_order_detail_images($items['cus_ord_ref'],$items['id_product'],$items['design_no'],$items['id_sub_design']);
            }
            
            foreach($item_details as $itemDet)
            {
                $weight_details[]=array('weight_range'=>$itemDet['weight_range'],'approx_wt'=>$itemDet['approx_wt'],'tot_items'=>$itemDet['tot_items']);
                $size_details[]=array('size'=>$itemDet['size']);
                $tot_items[]=array('tot_items'=>$itemDet['tot_items']);
            }
            $responseData[]=array(
                                 'product_name'     =>$items['product_name'],
                                 'design_name'      =>$items['design_name'],
                                 'sub_design_name'  =>$items['sub_design_name'],
                                 'description'      =>$items['description'],
                                 //'item_details'     =>$this->get_karigar_order_product_details($id_customerorder,$items['id_product'],$items['design_no'],$items['id_sub_design']),
                                 'weight_details'   =>$weight_details,
                                 'size_details'     =>$size_details,
                                 'pcs_details'      =>$tot_items,
                                 'img_details'      =>$image_details,
                                 );
        }
        
        return $responseData;
	}
	
	
	function get_karigar_order_product_details($id_customerorder,$id_product,$id_design,$id_sub_design)
	{
	    $sql=$this->db->query("SELECT p.product_name,des.design_name,subDes.sub_design_name,(d.totalitems) as tot_items,concat(w.value,'',u.uom_name) as weight_range,
	    concat(s.value,' ',s.name) as size,IFNULL(w.value,0) as approx_wt,IFNULL(d.description,'') as description
        FROM customerorder c 
        LEFT JOIN customerorderdetails d ON d.id_customerorder=c.id_customerorder
        LEFT JOIN ret_product_master p ON p.pro_id=d.id_product
        LEFT JOIN ret_design_master des ON des.design_no=d.design_no
        LEFT JOIN ret_sub_design_master subDes ON subDes.id_sub_design=d.id_sub_design
        LEFT JOIN ret_size s ON s.id_size=d.size
        LEFT JOIN ret_weight w ON w.id_weight=d.id_weight_range
        LEFT JOIN ret_uom u ON u.uom_id=w.id_uom
        WHERE c.pur_no IS NOT NULL AND c.id_customerorder=".$id_customerorder." 
        ".($id_product!='' ? " and d.id_product=".$id_product."" :'')." 
        ".($id_design!='' ? " and d.design_no=".$id_design."" :'')." 
        ".($id_sub_design!='' ? " and d.id_sub_design=".$id_sub_design."" :'')." 

        Order by w.id_weight");
        //print_r($this->db->last_query());exit;
        return $sql->result_array();
	}
	
	function get_karigar_order_detail_images($id_customerorder,$id_product,$id_design,$id_sub_design)
	{
	    $sql=$this->db->query("SELECT img.image as image_name,img.id_orderdetails
        FROM customer_order_image img
        LEFT JOIN customerorderdetails d ON d.id_orderdetails=img.id_orderdetails
        LEFT JOIN customerorder cus ON cus.id_customerorder=d.id_customerorder
        WHERE cus.pur_no IS NOT NULL  AND cus.id_customerorder=".$id_customerorder."
        ".($id_product!='' ? " and d.id_product=".$id_product."" :'')." 
        ".($id_design!='' ? " and d.design_no=".$id_design."" :'')." 
        ".($id_sub_design!='' ? " and d.id_sub_design=".$id_sub_design."" :'')." 
        ");
        //print_r($this->db->last_query());exit;
        return $sql->result_array();
	}
	
	function get_customer_order_detail_images($id_customerorder,$id_product,$id_design,$id_sub_design)
	{
	    $sql=$this->db->query("SELECT img.image as image_name,img.id_orderdetails
        FROM customer_order_image img
        LEFT JOIN customerorderdetails d ON d.id_orderdetails=img.id_orderdetails
        LEFT JOIN customerorder cus ON cus.id_customerorder=d.id_customerorder
        WHERE cus.id_customerorder=".$id_customerorder."
        ".($id_product!='' ? " and d.id_product=".$id_product."" :'')." 
        ".($id_design!='' ? " and d.design_no=".$id_design."" :'')." 
        ".($id_sub_design!='' ? " and d.id_sub_design=".$id_sub_design."" :'')." 
        ");
        //print_r($this->db->last_query());exit;
        return $sql->result_array();
	}
	
	
	
	function get_KarigarOrders($data)
	{
	    $sql=$this->db->query("SELECT c.pur_no,d.id_orderdetails,p.product_name,des.design_name,m.sub_design_name,d.totalitems
        FROM customerorder c 
        LEFT JOIN customerorderdetails d ON d.id_customerorder=c.id_customerorder
        LEFT JOIN joborder j ON j.id_order=d.id_orderdetails
        LEFT JOIN ret_product_master p ON p.pro_id=d.id_product
        LEFT JOIN ret_design_master des ON des.design_no=d.design_no
        LEFT JOIN ret_sub_design_master m ON m.id_sub_design=d.id_sub_design
        WHERE d.orderstatus=3
        ".($data['id_karigar']!='' ? " and j.id_vendor=".$data['id_karigar']."" :'')."");
        
        return $sql->result_array();
	}
	
	
	function get_karigar_details($id_karigar)
	{
	    $sql=$this->db->query("SELECT k.id_karigar,concat(k.firstname,' ',IFNULL(k.lastname,'')) as karigar_name,k.contactno1 as mobile,k.code_karigar,IFNULL(k.address1,'') as address1,IFNULL(k.address2,'') as address2,IFNULL(k.address3,'') as address3,IFNULL(cy.name,'') as country_name,IFNULL(st.name,'') as state_name,IFNULL(ct.name,'') as city_name,
	    IFNULL(k.company,'') as company_name,IFNULL(k.address1,'') as address1,IFNULL(k.address2,'') as address2,IFNULL(k.address3,'') as address3,
	    cy.name as country_name,st.name as state_name,ct.name as city_name, IFNULL(k.gst_number,'') as gst_number, k.karigar_type, is_tcs, tcs_tax, is_tds, tds_tax,
	    k.id_country,k.id_state,k.id_city,k.pan_no,k.pincode,k.karigar_calc_type
        FROM ret_karigar k
        LEFT JOIN country cy ON cy.id_country=k.id_country
        LEFT JOIN state st ON st.id_state=k.id_state
        LEFT JOIN city ct ON ct.id_city=k.id_city
        where k.id_karigar=".$id_karigar."");
        //print_r($this->db->last_query());exit;
        return $sql->row_array();
	}
	
	function get_pur_order_details($data)
	{
	    $sql=$this->db->query("SELECT d.id_orderdetails,(d.totalitems) as tot_items,concat(w.value,'',u.uom_name) as weight_range,k.firstname as karigar_name,IFNULL(k.email,'') as email,
	    p.product_name,des.design_name,concat(s.value,' ',s.name) as size,IFNULL(k.contactno1,'') as mobile,cus.pur_no,date_format(cus.order_date,'%d-%m-%Y') as order_date,
	    emp.firstname as emp_name,subDes.sub_design_name,m.order_status as order_status_msg,d.orderstatus,m.color,IFNULL(d.delivered_qty,0) as delivered_qty,date_format(d.delivered_date,'%d-%m-%Y') as delivered_date,
	    IFNULL(d.delivered_wt,0) as delivered_wt,
	    if(cus.order_type=1,'Stock Order',if(cus.order_type=2,'Customer Order',if(cus.order_type=3,'Customer Repair',if(cus.order_type=4,'Stock Repair','')))) as order_type,
	    if(cus.order_for=1,'Branch',if(cus.order_for=2,'Customer','Repair')) as order_for,IFNULL(ordRef.order_no,'') as cus_order_no
        FROM customerorderdetails d 
        LEFT JOIN customerorder cus ON cus.id_customerorder=d.id_customerorder
        LEFT JOIN customerorder ordRef on ordRef.id_customerorder=cus.cus_ord_ref
        LEFT JOIN ret_product_master p ON p.pro_id=d.id_product
        LEFT JOIN ret_design_master des ON des.design_no=d.design_no
        LEFT JOIN ret_sub_design_master subDes ON subDes.id_sub_design=d.id_sub_design
        LEFT JOIN ret_size s ON s.id_size=d.size
        LEFT JOIN ret_weight w ON w.id_weight=d.id_weight_range
        LEFT JOIN joborder j ON j.id_order=d.id_orderdetails
        LEFT JOIN ret_karigar k ON k.id_karigar=cus.id_karigar
        LEFT JOIN employee emp ON emp.id_employee=cus.order_taken_by
        LEFT JOIN order_status_message m ON m.id_order_msg=d.orderstatus
        LEFT JOIN ret_uom u ON u.uom_id=w.id_uom
        WHERE cus.pur_no is NOT NULL
        ".($data['from_date']!='' && $data['to_date']!='' && $data['date_group_by']==1 ? " and (date(cus.order_date) BETWEEN '".date('Y-m-d',strtotime($data['from_date']))."' AND '".date('Y-m-d',strtotime($data['to_date']))."')" :'')."
        ".($data['from_date']!='' && $data['to_date']!='' && $data['date_group_by']==2 ? " and (date(d.delivered_date) BETWEEN '".date('Y-m-d',strtotime($data['from_date']))."' AND '".date('Y-m-d',strtotime($data['to_date']))."')" :'')."
        ".($data['id_karigar']!='' ? " and j.id_vendor=".$data['id_karigar']."" :'')."
        ".($data['report_type']!='' ? " and cus.order_for=".$data['report_type']."" :'')."
        ");
        //print_r($this->db->last_query());exit;
        return $sql->result_array();
	}
	
	
	
	function get_customer_order_pending_details($data)
	{
	    $returnData=array();
	    $sql=$this->db->query("SELECT c.id_customerorder,c.order_no,c.order_type
        FROM customerorder c 
        LEFT JOIN customerorderdetails d ON d.id_customerorder=c.id_customerorder
        LEFT JOIN customerorder cusOrd ON cusOrd.id_customerorder = c.cus_ord_ref
        LEFT JOIN customerorderdetails cusOrdet ON cusOrdet.id_customerorder=cusOrd.id_customerorder
        LEFT JOIN joborder j ON j.id=cusOrdet.id_orderdetails
        WHERE d.orderstatus<3 and c.order_type=2
        GROUP by d.id_customerorder");
        $result= $sql->result_array();
        foreach($result as $items)
        {
            $returnData[]=array(
                              'id_customerorder'=>$items['id_customerorder'],
                              'order_type'      =>$items['order_type'],
                              'order_no'        =>$items['order_no'],
                              'item_details'    =>$this->get_customer_order_item_details($items['id_customerorder']),
                              'order_details'   =>$this->get_customer_order_details($items['id_customerorder']),
                             );
        }
        return $returnData;
	}
	
	
	function get_stock_repair_order($data)
	{
	    $returnData=array();
	    $sql=$this->db->query("SELECT c.id_customerorder,c.order_no,c.order_type
        FROM customerorder c 
        LEFT JOIN customerorderdetails d ON d.id_customerorder=c.id_customerorder
        LEFT JOIN joborder j ON j.id=d.id_orderdetails
        WHERE d.orderstatus<=3 and (c.order_type=4 or c.order_type=3)
        ".($data['id_branch']!='' ? " and d.current_branch=".$data['id_branch']."" :'')."
        GROUP by d.id_customerorder");
        $result= $sql->result_array();
        foreach($result as $items)
        {
            $returnData[]=array(
                              'id_customerorder'=>$items['id_customerorder'],
                              'order_type'      =>$items['order_type'],
                              'order_no'        =>$items['order_no'],
                              'item_details'    =>$this->get_customer_order_item_details($items['id_customerorder']),
                              'order_details'   =>$this->get_stock_repair_order_details($items['id_customerorder'],$data),
                             );
        }
        return $returnData;
	}
	
	function get_stock_repair_order_details($id_customerorder,$data)
	{
	    $sql=$this->db->query("SELECT d.id_orderdetails,d.id_product,IFNULL(d.design_no,'') as design_no,IFNULL(d.id_sub_design,'') as id_sub_design,IFNULL(SUM(d.weight),0) as weight,IFNULL(SUM(d.totalitems),0) as totalitems,
	    p.product_name,IFNULL(des.design_name,'') as design_name,IFNULL(s.sub_design_name,'') as sub_design_name,IFNULL(SUM(d.pure_wt),0) as pure_wt
        FROM customerorder c 
        LEFT JOIN customerorderdetails d ON d.id_customerorder=c.id_customerorder
        LEFT JOIN ret_product_master p ON p.pro_id=d.id_product
        LEFT JOIN ret_design_master des ON des.design_no=d.design_no
        LEFT JOIN ret_sub_design_master s ON s.id_sub_design=d.id_sub_design
        WHERE c.id_customerorder=".$id_customerorder."
        ".($data['id_branch']!='' ? " and d.current_branch=".$data['id_branch']."" :'')."
        group by d.id_product,d.design_no,d.id_sub_design");
        return $sql->result_array();
	}
	
	function get_customer_order_details($id_customerorder)
	{
	    $sql=$this->db->query("SELECT d.id_orderdetails,d.id_product,IFNULL(d.design_no,'') as design_no,IFNULL(d.id_sub_design,'') as id_sub_design,IFNULL(SUM(d.weight),0) as weight,IFNULL(SUM(d.totalitems),0) as totalitems,
	    p.product_name,IFNULL(des.design_name,'') as design_name,IFNULL(s.sub_design_name,'') as sub_design_name,IFNULL(sz.id_size,'') as id_size,IFNULL(concat(sz.value,' ',sz.name),'') as size
        FROM customerorder c 
        LEFT JOIN customerorderdetails d ON d.id_customerorder=c.id_customerorder
        LEFT JOIN ret_product_master p ON p.pro_id=d.id_product
        LEFT JOIN ret_design_master des ON des.design_no=d.design_no
        LEFT JOIN ret_sub_design_master s ON s.id_sub_design=d.id_sub_design
        LEFT JOIN ret_size sz ON sz.id_size=d.size
        WHERE c.id_customerorder=".$id_customerorder." and d.orderstatus=0
        group by d.id_product,d.design_no,d.id_sub_design,d.size");
        return $sql->result_array();
	}
	
	function get_customer_order_item_details($id_customerorder)
	{
	    $sql=$this->db->query("SELECT d.id_orderdetails,(d.totalitems) as tot_items,concat(w.value,'',u.uom_name) as weight_range,k.firstname as karigar_name,IFNULL(k.email,'') as email,c.company_name,
	    p.product_name,des.design_name,concat(s.value,' ',s.name) as size,IFNULL(k.contactno1,'') as mobile,cus.order_no,date_format(cus.order_date,'%d-%m-%Y') as order_date,
	    emp.firstname as emp_name,subDes.sub_design_name,cus.pur_no, date_format(d.smith_due_date, '%d-%m-%Y') as smith_due_date,
	    (SELECT image_name from ret_sub_design_mapping_images as img where is_default=1 and img.id_sub_design_mapping=m.id_sub_design_mapping) as default_image,
	    IFNULL(d.description,'') as description,m.id_sub_design_mapping,IFNULL(w.value,0) as approx_wt,j.id_vendor,d.id_product,d.id_customerorder,IFNULL(s.id_size,'') as id_size
        FROM customerorder cus 
        JOIN company c
        LEFT JOIN customerorderdetails d ON d.id_customerorder=cus.id_customerorder
        LEFT JOIN joborder j ON j.id_order=d.id_orderdetails
        LEFT JOIN ret_karigar k ON k.id_karigar=j.id_vendor
        LEFT JOIN ret_product_master p ON p.pro_id=d.id_product
        LEFT JOIN ret_design_master des ON des.design_no=d.design_no
        LEFT JOIN ret_sub_design_master subDes ON subDes.id_sub_design=d.id_sub_design
        LEFT JOIN ret_size s ON s.id_size=d.size
        LEFT JOIN ret_weight w ON w.id_weight=d.weight
        LEFT JOIN ret_uom u ON u.uom_id=w.id_uom
        LEFT JOIN employee emp on emp.id_employee=cus.order_taken_by
        LEFT JOIN ret_sub_design_mapping m ON m.id_product=(d.id_product AND d.design_no AND d.id_sub_design)
        WHERE cus.order_no IS NOT NULL  AND cus.id_customerorder=".$id_customerorder."
        group by d.id_orderdetails");
        //print_r($this->db->last_query());exit;
        return $sql->result_array();
	}
	
	
	/*function get_purchase_entry_details()
	{
	    $sql=$this->db->query("SELECT p.po_id,p.po_ref_no,date_format(p.po_date,'%d-%m-%Y') as po_date,k.firstname as karigar,p.ewaybillno,
	    if(p.po_type=1,'Oranments',if(p.po_type=2,'Bullion Purchase','Stone')) as po_type,ord.gross_wt,ord.tot_pcs,ord.tot_lwt,ord.tot_nwt,
	    c.name as category_name,pur.purity,(IFNULL(p.tot_purchase_amt,0)) as tot_purchase_amt,p.tot_purchase_wt
        FROM ret_purchase_order p 
        LEFT JOIN ret_karigar k ON k.id_karigar=p.po_karigar_id
        LEFT JOIN ret_category c on c.id_ret_category=p.id_category
        LEFT JOIN ret_purity pur on pur.id_purity=p.id_purity
        LEFT JOIN (SELECT d.po_item_po_id,IFNULL(SUM(d.gross_wt),0) as gross_wt,IFNULL(SUM(d.no_of_pcs),0) as tot_pcs,IFNULL(SUM(d.less_wt),0) as tot_lwt,IFNULL(SUM(d.net_wt),0) as tot_nwt
        FROM ret_purchase_order_items d 
        GROUP by d.po_item_po_id) as ord ON ord.po_item_po_id=p.po_id 
        WHERE p.pur_approval_type = 0");
        return $sql->result_array();
	}*/
	
	function get_purchase_entry_details($from_date,$to_date)
	{
	    $sql=$this->db->query("SELECT ifnull(grn.grn_ref_no, '') as grn_ref_no, p.po_grn_id, p.po_id,p.po_ref_no,date_format(p.po_date,'%d-%m-%Y') as po_date,k.firstname as karigar,p.ewaybillno,
	    if(p.po_type=1,'Oranments',if(p.po_type=2,'Bullion Purchase','Stone')) as po_type,ord.gross_wt,ord.tot_pcs,ord.tot_lwt,ord.tot_nwt,
	    ord.catname as category_name,ord.purity,(IFNULL(p.tot_purchase_amt,0)) as tot_purchase_amt,p.tot_purchase_wt,p.purchase_type,
	    if(p.is_approved=1,'Approved','Yet to Approved') as is_approved
        FROM ret_purchase_order p 
        LEFT JOIN ret_grn_entry as grn ON grn.grn_id = p.po_grn_id 
        LEFT JOIN ret_karigar k ON k.id_karigar=p.po_karigar_id 
        LEFT JOIN (SELECT group_concat(DISTINCT(c.name)) as catname, group_concat(DISTINCT(pur.purity)) as purity, 
            d.po_item_po_id,IFNULL(SUM(d.gross_wt),0) as gross_wt,IFNULL(SUM(d.no_of_pcs),0) as tot_pcs,
            IFNULL(SUM(d.less_wt),0) as tot_lwt,IFNULL(SUM(d.net_wt),0) as tot_nwt 
            FROM ret_purchase_order_items d 
            LEFT JOIN ret_category c on c.id_ret_category=d.po_item_cat_id 
            LEFT JOIN ret_purity pur on pur.id_purity=d.id_purity 
            GROUP by d.po_item_po_id) as ord ON ord.po_item_po_id=p.po_id 
        WHERE p.pur_approval_type = 0 AND is_suspense_stock != 1 
        AND (date(p.po_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')
        ORDER BY p.po_id DESC");
        return $sql->result_array();
	}
	
	function get_approval_purchase_entry_details()
	{
	    $sql=$this->db->query("SELECT p.po_id,p.po_ref_no,date_format(p.po_date,'%d-%m-%Y') as po_date,k.firstname as karigar,p.ewaybillno,
	    if(p.po_type=1,'Oranments',if(p.po_type=2,'Metal','Stone')) as po_type,ord.gross_wt,ord.tot_pcs,ord.tot_lwt,ord.tot_nwt,
	    c.name as category_name,pur.purity,p.total_payable_amt,p.total_payable_wt
        FROM ret_purchase_order p 
        LEFT JOIN ret_karigar k ON k.id_karigar=p.po_karigar_id
        LEFT JOIN ret_category c on c.id_ret_category=p.id_category
        LEFT JOIN ret_purity pur on pur.id_purity=p.id_purity
        LEFT JOIN (SELECT d.po_item_po_id,is_suspense_stock,IFNULL(SUM(d.gross_wt),0) as gross_wt,IFNULL(SUM(d.no_of_pcs),0) as tot_pcs,IFNULL(SUM(d.less_wt),0) as tot_lwt,IFNULL(SUM(d.net_wt),0) as tot_nwt
        FROM ret_purchase_order_items d 
        GROUP by d.po_item_po_id) as ord ON ord.po_item_po_id=p.po_id 
        WHERE ord.is_suspense_stock = 1");
        //echo $this->db->last_query();exit;
        return $sql->result_array();
	}
	
	
	function get_qc_receipt_items($data)
	{
	    $returnData = array();
	    $sql = $this->db->query("SELECT d.id_qc_issue_details,p.qc_process_id,i.po_item_id,pro.product_name,des.design_name,subDes.sub_design_name,d.issue_pcs,d.issue_gwt,d.issue_lwt,d.issue_nwt
        FROM ret_po_qc_issue_process p 
        LEFT JOIN ret_po_qc_issue_details d ON d.qc_process_id = p.qc_process_id
        LEFT JOIN ret_purchase_order_items i ON i.po_item_id = d.po_item_id
        LEFT JOIN ret_product_master pro ON pro.pro_id = i.po_item_pro_id
        LEFT JOIN ret_design_master des ON des.design_no = i.po_item_des_id
        LEFT JOIN ret_sub_design_master subDes ON subDes.id_sub_design = i.po_item_sub_des_id
        WHERE p.qc_process_id = '".$data['qc_ref_no']."'
        GROUP BY d.id_qc_issue_details");
        $result = $sql->result_array();
        foreach($result as $items){
            $items['stone_details'] = $this->get_qc_issue_stone_details($items['id_qc_issue_details']);
            $returnData[]=$items;
        }
        return $returnData;
	}
	
	function get_qc_issue_stone_details($id_qc_issue_details)
	{
	    $sql = $this->db->query("SELECT s.ret_qc_issue_stn_id,t.id_stone_type,s.stone_pcs,s.stone_wt,po_stn.po_stone_uom,
	    stn.stone_name,'0' as po_stone_rejected_pcs,'0' as po_stone_rejected_wt,s.stone_pcs as po_stone_accepted_pcs,s.stone_wt as po_stone_accepted_wt
        FROM ret_po_qc_issue_stone_details s 
        LEFT JOIN ret_po_stone_items po_stn ON po_stn.po_st_id = s.po_st_id
        LEFT JOIN ret_stone stn ON stn.stone_id = po_stn.po_stone_id
        LEFT JOIN ret_stone_type t ON t.id_stone_type = stn.stone_type
        LEFT JOIN ret_uom m ON m.uom_id = stn.uom_id
        WHERE s.id_qc_issue_details = ".$id_qc_issue_details."");
        return $sql->result_array();
	}
	
	function get_purchase_issue_entry_items($data)
	{
	    $returnData=array();
	    $sql=$this->db->query("SELECT i.po_item_id,p.po_id,cat.name as category_name,pro.product_name,d.design_name,s.sub_design_name,
        i.no_of_pcs,i.gross_wt,i.mc_type,i.net_wt,IFNULL(i.mc_value,0) as mc_value,IFNULL(i.item_wastage,0) as item_wastage,IFNULL(i.less_wt,0) as less_wt
        FROM ret_purchase_order p
        LEFT JOIN ret_purchase_order_items i ON i.po_item_po_id=p.po_id
        LEFT JOIN ret_product_master pro ON pro.pro_id=i.po_item_pro_id
        LEFT JOIN ret_design_master d ON d.design_no=i.po_item_des_id
        LEFT JOIN ret_sub_design_master s ON s.id_sub_design=i.po_item_sub_des_id
        LEFT JOIN ret_category cat ON cat.id_ret_category=pro.cat_id
        WHERE i.status=1
        and p.po_id='".$data['po_id']."'");
        //print_r($this->db->last_query());exit;
        $result=$sql->result_array();
        foreach($result as $items)
        {
           $stn_amount=0;
           $stoneDetails=$this->pourchase_entry_stone_details($items['po_item_id']);
           foreach($stoneDetails as $stones)
           {
               $stn_amount+=$stones['po_stone_amount'];
           }
           $returnData[]=array(
                                'po_item_id'        =>$items['po_item_id'],
                                'po_id'             =>$items['po_id'],
                                'category_name'     =>$items['category_name'],
                                'product_name'      =>$items['product_name'],
                                'design_name'       =>$items['design_name'],
                                'sub_design_name'   =>$items['sub_design_name'],
                                'no_of_pcs'         =>$items['no_of_pcs'],
                                'gross_wt'          =>$items['gross_wt'],
                                'less_wt'           =>$items['less_wt'],
                                'net_wt'            =>$items['net_wt'],
                                'mc_type'           =>$items['mc_type'],
                                'mc_value'          =>$items['mc_value'],
                                'item_wastage'      =>$items['item_wastage'],
                                'stn_amount'       =>$stn_amount,
                                'stone_details'     =>$stoneDetails
                              ); 
        }
        return $returnData;
	}
	
	function pourchase_entry_stone_details($po_item_id)
	{
	    $sql=$this->db->query("SELECT s.po_st_id,s.po_item_id,s.po_stone_pcs,s.po_stone_wt,s.po_stone_amount,st.stone_name,m.uom_name,s.po_stone_rate as stone_rate,
	    s.po_stone_rejected_pcs,s.po_stone_rejected_wt,s.po_stone_accepted_pcs,s.po_stone_accepted_wt
        FROM ret_po_stone_items s 
        LEFT JOIN ret_stone st ON st.stone_id=s.po_stone_id
        LEFT JOIN ret_uom m ON m.uom_id=s.po_stone_uom
        WHERE s.po_item_id=".$po_item_id."");
	    return $sql->result_array();
	}
	
	
	function get_status_details()
	{
	    $returnData=array();
	    $sql=$this->db->query("SELECT i.po_item_id,p.po_id,cat.name as category_name,pro.product_name,d.design_name,s.sub_design_name,
        i.no_of_pcs,i.gross_wt,i.mc_type,i.net_wt,IFNULL(i.mc_value,0) as mc_value,IFNULL(i.item_wastage,0) as item_wastage,IFNULL(i.less_wt,0) as less_wt,
        p.po_ref_no,IFNULL(i.qc_passed_pcs,0) as qc_passed_pcs,IFNULL(i.qc_passed_gwt,0) as qc_passed_gwt,IFNULL(i.qc_passed_nwt,0) as qc_passed_nwt,
        k.firstname as karigar,i.is_halmarked
        FROM ret_purchase_order p
        LEFT JOIN ret_purchase_order_items i ON i.po_item_po_id=p.po_id
        LEFT JOIN ret_product_master pro ON pro.pro_id=i.po_item_po_id
        LEFT JOIN ret_design_master d ON d.design_no=i.po_item_des_id
        LEFT JOIN ret_sub_design_master s ON s.id_sub_design=i.po_item_sub_des_id
        LEFT JOIN ret_category cat ON cat.id_ret_category=pro.cat_id
        LEFT JOIN ret_karigar k ON k.id_karigar=p.po_karigar_id
        WHERE i.status=2");
        $result=$sql->result_array();
        return $result;
	}
	
	function get_qc_ref_no()
	{
	    $lastno = NULL;
	    $sql = "SELECT (ref_no) as last_no FROM ret_po_qc_issue_process p ORDER BY qc_process_id DESC LIMIT 1"; 
		$result = $this->db->query($sql);
			if( $result->num_rows() > 0){
				$lastno = $result->row()->last_no;				
			} 
			
	    if($lastno != NULL)
		{ 
		    //$max_num = explode("-",$lastno);
            $number = (int) $lastno;
            $number++;
            $ref_no = str_pad($number, 5, '0', STR_PAD_LEFT);	
		}
		else
		{
           $ref_no = str_pad('1', 5, '0', STR_PAD_LEFT);
		}
		
		return $ref_no;
	}
	
	function get_qc_issue_details()
	{
	    $sql = $this->db->query("SELECT p.qc_process_id,p.ref_no
        FROM ret_po_qc_issue_process p 
        LEFT JOIN ret_po_qc_issue_details d ON d.qc_process_id = p.qc_process_id
        WHERE d.status = 0 AND p.ref_no!=''
        GROUP BY d.qc_process_id
        ORDER BY p.qc_process_id DESC");
        return $sql->result_array();
	}
	
	
	function purchase_issue()
	{
	    $returnData=array();
	    /*$sql=$this->db->query("SELECT p.po_ref_no,p.po_id
        FROM ret_purchase_order_items i 
        LEFT JOIN ret_purchase_order p ON p.po_id=i.po_item_po_id
        WHERE p.po_id IS NOT NULL and i.status=0 GROUP by i.po_item_po_id");*/
        
        $sql = $this->db->query("SELECT p.po_ref_no,p.po_id,IFNULL(SUM(i.no_of_pcs),0) as no_of_pcs,q.qcissued_pcs,(IFNULL(SUM(i.no_of_pcs),0)-IFNULL(q.qcissued_pcs,0)) as blc_pcs
        FROM ret_purchase_order_items i 
        LEFT JOIN ret_purchase_order p ON p.po_id=i.po_item_po_id
        LEFT JOIN (SELECT IFNULL(SUM(d.issue_pcs),0) as qcissued_pcs,r.po_item_po_id
        FROM ret_po_qc_issue_details d 
        LEFT JOIN ret_po_qc_issue_process qc ON qc.qc_process_id = d.qc_process_id
        LEFT JOIN ret_purchase_order_items r ON r.po_item_id = d.po_item_id
        where r.is_halmarked = 1
        GROUP BY r.po_item_po_id) as q ON q.po_item_po_id = i.po_item_po_id
        WHERE p.po_id IS NOT NULL and p.is_approved = 1
        GROUP by i.po_item_po_id
        HAVING blc_pcs > 0");
        $result=$sql->result_array();
        foreach($result as $items)
        {
            $returnData[]=array(
                               'po_ref_no'  =>$items['po_ref_no'],
                               'po_id'      =>$items['po_id'],
                               //'item_details'=>$this->get_purchase_item_details($items['po_id']),
                               );
        } 
        return $returnData;
	}
	
	function purchase_receipt_orders()
	{
	    $returnData=array();
	    $sql=$this->db->query("SELECT p.po_ref_no,p.po_id,IFNULL(SUM(i.no_of_pcs),0) as no_of_pcs,q.qcissued_pcs,(IFNULL(SUM(i.no_of_pcs),0)-IFNULL(q.qcissued_pcs,0)) as blc_pcs
        FROM ret_purchase_order_items i 
        LEFT JOIN ret_purchase_order p ON p.po_id=i.po_item_po_id
        LEFT JOIN (SELECT IFNULL(SUM(d.issue_pcs),0) as qcissued_pcs,r.po_item_po_id
        FROM ret_po_qc_issue_details d 
        LEFT JOIN ret_po_qc_issue_process qc ON qc.qc_process_id = d.qc_process_id
        LEFT JOIN ret_purchase_order_items r ON r.po_item_id = d.po_item_id
        GROUP BY d.po_item_id) as q ON q.po_item_po_id = i.po_item_po_id
        WHERE p.po_id IS NOT NULL
        GROUP by i.po_item_po_id
        HAVING qcissued_pcs > 0");
        $result=$sql->result_array();
        foreach($result as $items)
        {
            $returnData[]=array(
                               'po_ref_no'  =>$items['po_ref_no'],
                               'po_id'      =>$items['po_id'],
                               );
        } 
        return $returnData;
	}
	
	function get_purchase_item_details($po_id)
	{
	    $sql=$this->db->query("SELECT i.po_item_id,p.po_id,cat.name as category_name,pro.product_name,d.design_name,s.sub_design_name,
        (IFNULL(i.no_of_pcs,0)-IFNULL(q.qcissued_pcs,0)) as no_of_pcs,(IFNULL(i.gross_wt,0)-IFNULL(q.qcissued_gwt,0)) as gross_wt,i.mc_type,(i.net_wt-IFNULL(q.qcissued_nwt,0)) as net_wt,IFNULL(i.mc_value,0) as mc_value,IFNULL(i.item_wastage,0) as item_wastage,IFNULL(i.less_wt,0) as less_wt,
        p.po_ref_no,k.firstname as karigar_name,IFNULL(q.qcissued_pcs,0) as qcissued_pcs,(IFNULL(i.no_of_pcs,0)-IFNULL(q.qcissued_pcs,0)) as blc_pcs
        FROM ret_purchase_order p
        LEFT JOIN ret_purchase_order_items i ON i.po_item_po_id=p.po_id
        LEFT JOIN ret_product_master pro ON pro.pro_id=i.po_item_pro_id
        LEFT JOIN ret_design_master d ON d.design_no=i.po_item_des_id
        LEFT JOIN ret_sub_design_master s ON s.id_sub_design=i.po_item_sub_des_id
        LEFT JOIN ret_category cat ON cat.id_ret_category=pro.cat_id
        LEFT JOIN ret_karigar k ON k.id_karigar=p.po_karigar_id
        LEFT JOIN (SELECT IFNULL(SUM(qd.issue_pcs),0) as qcissued_pcs,IFNULL(SUM(qd.issue_gwt),0) as qcissued_gwt,IFNULL(SUM(qd.issue_nwt),0) as qcissued_nwt,
        qd.po_item_id
        FROM ret_po_qc_issue_details qd 
        LEFT JOIN ret_po_qc_issue_process qc ON qc.qc_process_id = qd.qc_process_id
        LEFT JOIN ret_purchase_order_items r ON r.po_item_id = qd.po_item_id
        GROUP BY qd.po_item_id) as q ON q.po_item_id = i.po_item_id
        WHERE p.po_id='".$po_id."'
        HAVING blc_pcs > 0");
        //print_r($this->db->last_query());exit;
        $result =  $sql->result_array();
        foreach($result as $items){
            $items['stone_details'] = $this->get_qc_stone_details($items['po_item_id']);
            $returnData[]=$items;
        }
        return $returnData;
	}
	
	function get_qc_stone_details($po_item_id)
	{
	    $sql=$this->db->query("SELECT po_stn.po_st_id,po_stn.po_stone_id,po_stn.is_apply_in_lwt,po_stn.po_stone_wt,

        po_stn.po_stone_uom,po_stn.po_stone_calc_based_on,po_stn.po_stone_rate,po_stn.po_stone_amount,s.stone_type,s.stone_name,uom.uom_short_code,
        
        IFNULL(stndet.stone_pcs,0) as stone_pcs,IFNULL(stndet.stone_wt,0) as stone_wt,
        
        (po_stn.po_stone_pcs-IFNULL(stndet.stone_pcs,0)) as po_stone_pcs,(po_stone_wt-IFNULL(stndet.stone_wt,0)) as po_stone_wt
        
        FROM ret_po_stone_items po_stn 

        LEFT JOIN ret_stone s ON s.stone_id = po_stn.po_stone_id
        
        LEFT JOIN ret_uom uom on uom.uom_id=po_stn.po_stone_uom
        
        LEFT JOIN(SELECT IFNULL(SUM(qc_stn.stone_pcs),0) as stone_pcs,IFNULL(SUM(qc_stn.stone_wt),0) as stone_wt,qc_stn.po_st_id
        FROM ret_po_qc_issue_stone_details qc_stn 
        GROUP BY qc_stn.po_st_id) as stndet ON stndet.po_st_id = po_stn.po_st_id
        
        WHERE po_stn.po_item_id=".$po_item_id."
        having po_stone_pcs > 0");
	    
        return $sql->result_array();
	}
	
	
	function get_purchase_qc_details($data)
	{
	    $returnData = [];
        $sql=$this->db->query("SELECT p.qc_process_id,p.ref_no,p.issue_pcs,p.issue_gross_wt,p.issue_less_wt,p.issue_net_wt,e.firstname as empname,
        date_format(p.created_at,'%d-%m-%Y') as issue_date,IFNULL(qc_det.passed_gwt,0) as recd_gross_wt,IFNULL(qc_det.passed_lwt,0) as recd_lwt,
        IFNULL(qc_det.passed_nwt,0) as recd_nwt,p.ref_no
        FROM ret_po_qc_issue_process p 
        LEFT JOIN employee e ON e.id_employee = p.qc_id_vendor
        LEFT JOIN (SELECT IFNULL(SUM(d.passed_pcs),0) as passed_pcs,IFNULL(SUM(d.passed_gwt),0) as passed_gwt,
            IFNULL(SUM(d.passed_nwt),0) as passed_nwt,d.qc_process_id,IFNULL(SUM(d.passed_lwt),0) as passed_lwt
            FROM ret_po_qc_issue_details d 
        GROUP BY d.qc_process_id) as qc_det ON qc_det.qc_process_id = p.qc_process_id
        Where (date(p.created_at) BETWEEN '".date('Y-m-d',strtotime($data['from_date']))."' AND '".date('Y-m-d',strtotime($data['to_date']))."')
        ORDER BY p.qc_process_id DESC");
        //print_r($this->db->last_query());exit;
        $result = $sql->result_array();
        foreach($result as $val){
            $val['item_details'] = $this->getqc_item_details($val['qc_process_id']);
            $returnData[]=$val;
        }
        return $returnData;
	}
	
	function getqc_item_details($qc_process_id)
	{
	    $sql = $this->db->query("SELECT pro.product_name,des.design_name,subDes.sub_design_name,d.passed_pcs,d.passed_gwt,d.passed_lwt,d.passed_nwt,
	    pr.po_ref_no
        FROM ret_po_qc_issue_details d 
        LEFT JOIN ret_po_qc_issue_process p ON p.qc_process_id = d.qc_process_id
        LEFT JOIN ret_purchase_order_items r ON r.po_item_id = d.po_item_id
        LEFT JOIN ret_product_master pro ON pro.pro_id = r.po_item_pro_id
        LEFT JOIN ret_design_master des ON des.design_no = r.po_item_des_id
        LEFT JOIN ret_sub_design_master subDes ON subDes.id_sub_design = r.po_item_sub_des_id
        LEFT JOIN ret_purchase_order pr ON pr.po_id = r.po_item_po_id
        WHERE d.qc_process_id = ".$qc_process_id."");
        return $sql->result_array();
	}
	
	//Halmarking Issue / Receipt
	
	function generate_HalmarkingRefNo()
	{
	    $lastno = NULL;
	    $sql = "SELECT MAX(hm_ref_no) as lastorder_no
					FROM ret_po_halmark_process o
					ORDER BY hm_process_id DESC 
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
	
	function get_pending_halmarking_items()
	{
	    $returnData=array();
	    $sql=$this->db->query("SELECT p.po_ref_no,p.po_id
        FROM ret_purchase_order_items i 
        LEFT JOIN ret_purchase_order p ON p.po_id=i.po_item_po_id
        WHERE p.po_id IS NOT NULL and p.is_approved = 1 and i.is_halmarked=0 and i.is_lot_created=0 GROUP by i.po_item_po_id");
        $result=$sql->result_array();
        foreach($result as $items)
        {
            $returnData[]=array(
                               'po_ref_no'  =>$items['po_ref_no'],
                               'po_id'      =>$items['po_id'],
                               'item_details'=>$this->get_halmarking_items($items['po_id']),
                               );
        } 
        return $returnData;
	}
	
	function get_halmarking_items($po_id)
	{
	    $sql=$this->db->query("SELECT i.po_item_id,p.po_id,cat.name as category_name,pro.product_name,d.design_name,s.sub_design_name,
        i.no_of_pcs as no_of_pcs,i.gross_wt as gross_wt,i.mc_type,i.net_wt as net_wt,IFNULL(i.mc_value,0) as mc_value,IFNULL(i.item_wastage,0) as item_wastage,IFNULL(i.less_wt,0) as less_wt,
        p.po_ref_no,k.firstname as karigar_name
        FROM ret_purchase_order p
        LEFT JOIN ret_purchase_order_items i ON i.po_item_po_id=p.po_id
        LEFT JOIN ret_product_master pro ON pro.pro_id=i.po_item_pro_id
        LEFT JOIN ret_design_master d ON d.design_no=i.po_item_des_id
        LEFT JOIN ret_sub_design_master s ON s.id_sub_design=i.po_item_sub_des_id
        LEFT JOIN ret_category cat ON cat.id_ret_category=pro.cat_id
        LEFT JOIN ret_karigar k ON k.id_karigar=p.po_karigar_id
        WHERE i.is_halmarked=0 and i.is_lot_created = 0 and p.is_approved = 1
        and p.po_id='".$po_id."'");
        //print_r($this->db->last_query());exit;
        return $sql->result_array();
	}
	
	function get_halmarking_details()
	{
	    $sql=$this->db->query("SELECT h.hm_ref_no,h.hm_process_pcs,h.hm_process_pcs,IFNULL(h.hm_process_gwt,0) as hm_process_gwt,IFNULL(h.hm_process_lwt,0) as hm_process_lwt,
	    IFNULL(h.hm_process_nwt,0) as hm_process_nwt,
	    k.firstname as karigar_name,date_format(h.hm_process_created_at,'%d-%m-%Y') as issue_date,
	    if(h.status=1,'Issued','Completed') as hm_status,h.status,IFNULL(h.total_hm_charges,0) as total_hm_charges
        FROM ret_po_halmark_process h 
        LEFT JOIN ret_karigar k ON k.id_karigar=h.hm_vendor_id");
        return $sql->result_array();
	}
	
	function get_halmarking_issue_orders()
	{
	    $returnData=array();
	    $sql=$this->db->query("SELECT p.hm_process_id,p.hm_ref_no
        FROM ret_po_halmark_process p 
        WHERE status=1");
	    $result=$sql->result_array();
	    foreach($result as $items)
	    {
	        $returnData[]=array(
	                            'hm_process_id' =>$items['hm_process_id'],
	                            'hm_ref_no'     =>$items['hm_ref_no'],
	                            'item_details'  =>$this->get_halmarking_issue_order_details($items['hm_process_id']),
	                            );
	    }
	    return $returnData;
	}
	
	function get_halmarking_issue_order_details($hm_process_id)
	{
	    $sql=$this->db->query("SELECT pro.product_name,des.design_name,subDes.sub_design_name,p.no_of_pcs as pcs,p.gross_wt as gross_wt,p.less_wt as less_wt,p.net_wt as net_wt,
	    s.hm_process_id,p.po_item_id
        FROM ret_purchase_order_items p 
        LEFT JOIN ret_po_hm_process_details d ON d.hm_po_item_id=p.po_item_id
        LEFT JOIN ret_po_halmark_process s ON s.hm_process_id=d.hm_issue_id
        LEFT JOIN ret_product_master pro ON pro.pro_id=p.po_item_pro_id
        LEFT JOIN ret_design_master des ON des.design_no=p.po_item_des_id
        LEFT JOIN ret_sub_design_master subDes ON subDes.id_sub_design=p.po_item_sub_des_id
        WHERE s.status=1 and s.hm_process_id=".$hm_process_id."");
        $result=$sql->result_array();
        foreach($result as $items)
        {
            $stoneDetails=$this->pourchase_entry_stone_details($items['po_item_id']);
             $returnData[]=array(
                                'design_name'       =>$items['design_name'],
                                'gross_wt'          =>$items['gross_wt'],
                                'hm_process_id'     =>$items['hm_process_id'],
                                'less_wt'           =>$items['less_wt'],
                                'net_wt'            =>$items['net_wt'],
                                'pcs'               =>$items['pcs'],
                                'po_item_id'        =>$items['po_item_id'],
                                'product_name'      =>$items['product_name'],
                                'sub_design_name'   =>$items['sub_design_name'],
                                'stone_details'     =>$stoneDetails,
                                );
        }
        return $returnData;
	}
	
	//Halmarking Issue / Receipt
	
	//check qc status
	function get_purchase_order_qc_details($po_id)
	{
	    $sql=$this->db->query("SELECT d.status
        FROM ret_po_qc_issue_details d 
        LEFT JOIN ret_purchase_order_items i ON i.po_item_id=d.po_item_id
        WHERE i.po_item_po_id=".$po_id." AND i.is_halmarked=1 ");
        return $sql->result_array();
	}
	//check qc status
	
	
	//Lot Generate
	function get_purchase_order($po_id)
	{
	    $sql=$this->db->query("SELECT p.po_id,p.po_karigar_id,p.id_category,p.id_purity
        FROM ret_purchase_order p 
        WHERE p.po_id=".$po_id."");
        return $sql->row_array();
	}
	
	function get_purchase_by_category($po_id)
	{
	    $sql=$this->db->query("SELECT p.po_id,p.po_karigar_id,i.po_item_cat_id as id_category,i.id_purity,pr.stock_type
        FROM ret_purchase_order_items i 
        LEFT JOIN ret_purchase_order p ON p.po_id = i.po_item_po_id
        LEFT JOIN ret_product_master pr ON pr.pro_id = i.po_item_pro_id
        WHERE p.po_id = ".$po_id."
        GROUP BY i.po_item_cat_id,i.id_purity,pr.stock_type");
        return $sql->result_array();
	}
	
	function get_halmarking_purchase_order($hm_process_id)
	{
	    $sql=$this->db->query("SELECT o.po_id,o.po_karigar_id,o.id_category,o.id_purity
        FROM ret_po_hm_process_details d 
        LEFT JOIN ret_po_halmark_process p ON p.hm_process_id=d.hm_issue_id
        LEFT JOIN ret_purchase_order_items i ON i.po_item_id=d.hm_po_item_id
        LEFT JOIN ret_purchase_order o ON o.po_id=i.po_item_po_id
        WHERE p.hm_process_id=".$hm_process_id." GROUP by p.hm_process_id");
        return $sql->row_array();
	}
	
	function get_purOrders($po_ref_no)
	{
	    $sql=$this->db->query("SELECT p.po_id,p.po_karigar_id,p.id_category,p.id_purity
        FROM ret_purchase_order p 
        WHERE p.po_ref_no='".$po_ref_no."'");
        return $sql->row_array();
	}
	
	function get_purchase_orders_by_product($po_id,$id_category,$id_purity,$stock_type)
	{
	    $sql=$this->db->query("SELECT i.po_item_pro_id as id_product,d.passed_pcs,d.passed_gwt,d.passed_lwt,d.passed_nwt
        FROM ret_purchase_order_items i
        LEFT JOIN ret_po_qc_issue_details d ON d.po_item_id=i.po_item_id
        LEFT JOIN ret_product_master pro ON pro.pro_id = i.po_item_pro_id
        WHERE i.po_item_po_id=".$po_id." AND d.status=1 AND i.is_halmarked=1 and d.is_lot_created=0
        ".($id_category!='' ? " and i.po_item_cat_id=".$id_category."" :'')."
        ".($stock_type!='' ? " and pro.stock_type=".$stock_type."" :'')."
        ".($id_purity!='' ? " and i.id_purity=".$id_purity."" :'')."
        GROUP by i.po_item_pro_id,pro.stock_type,i.id_purity");
        //print_r($this->db->last_query());exit;
        return $sql->result_array();
	}
	
	function get_po_purchase_items($po_id,$id_category)
	{
	    $sql=$this->db->query("SELECT i.po_item_id,IFNULL(SUM(i.no_of_pcs-IFNULL(i.qc_failed_pcs,0)-IFNULL(i.hm_rejected_pcs,0)),0) as total_pcs, 
        IFNULL(SUM(IFNULL(i.gross_wt,0)-IFNULL(i.qc_failed_gwt,0)-IFNULL(i.hm_rejected_gwt,0)),0) as total_gwt,
        IFNULL(SUM(IFNULL(i.less_wt,0)-IFNULL(i.qc_failed_lwt,0)-IFNULL(i.hm_rejected_lwt,0)),0) as total_lwt,
        IFNULL(SUM(IFNULL(i.net_wt,0)-IFNULL(i.qc_failed_nwt,0)-IFNULL(i.hm_rejected_nwt,0)),0) as total_nwt,
        i.mc_type,IFNULL(i.mc_value,0) as mc_value,IFNULL(i.item_wastage,0) as item_wastage, i.item_pure_wt as item_pure_wt,i.is_rate_fixed,IFNULL(i.fix_rate_per_grm,0) as fix_rate_per_grm,
        mt.tgrp_id,tax.tax_percentage
        FROM ret_purchase_order_items i
        LEFT JOIN ret_po_qc_issue_details d ON d.po_item_id=i.po_item_id
        LEFT JOIN ret_product_master p ON p.pro_id=i.po_item_pro_id
        LEFT JOIN ret_category cat ON cat.id_ret_category=p.cat_id
        LEFT JOIN metal mt ON mt.id_metal=cat.id_metal
        LEFT JOIN (select i.tgi_taxcode,i.tgi_tgrpcode,
	    (m.tax_percentage) as tax_percentage,(i.tgi_calculation) as tgi_calculation
		FROM ret_taxgroupitems i
		LEFT JOIN ret_taxmaster m on m.tax_id=i.tgi_taxcode) as tax on tax.tgi_tgrpcode=mt.tgrp_id
        WHERE i.po_item_po_id=".$po_id." AND d.status=1 AND i.is_halmarked=1 and d.is_lot_created=0
        ".($id_category!='' ? " and i.po_item_cat_id=".$id_category."" :'')."
        GROUP by i.po_item_id,p.stock_type");
        return $sql->result_array();
	}
	
	function get_purchase_order_items($po_id,$id_product)
	{
	    $sql=$this->db->query("SELECT i.po_item_id,IFNULL(SUM(i.no_of_pcs-IFNULL(i.qc_failed_pcs,0)-IFNULL(i.hm_rejected_pcs,0)),0) as total_pcs, 
        IFNULL(SUM(IFNULL(i.gross_wt,0)-IFNULL(i.qc_failed_gwt,0)-IFNULL(i.hm_rejected_gwt,0)),0) as total_gwt,
        IFNULL(SUM(IFNULL(i.less_wt,0)-IFNULL(i.qc_failed_lwt,0)-IFNULL(i.hm_rejected_lwt,0)),0) as total_lwt,
        IFNULL(SUM(IFNULL(i.net_wt,0)-IFNULL(i.qc_failed_nwt,0)-IFNULL(i.hm_rejected_nwt,0)),0) as total_nwt,
        i.mc_type,IFNULL(i.mc_value,0) as mc_value,IFNULL(i.item_wastage,0) as item_wastage, i.item_pure_wt as item_pure_wt,i.is_rate_fixed,IFNULL(i.fix_rate_per_grm,0) as fix_rate_per_grm,
        mt.tgrp_id,i.po_item_des_id,i.po_item_sub_des_id,i.po_item_pro_id
        FROM ret_purchase_order_items i
        LEFT JOIN ret_po_qc_issue_details d ON d.po_item_id=i.po_item_id
        LEFT JOIN ret_product_master p ON p.pro_id=i.po_item_pro_id
        LEFT JOIN ret_category cat ON cat.id_ret_category=p.cat_id
        LEFT JOIN metal mt ON mt.id_metal=cat.id_metal
        WHERE i.po_item_po_id=".$po_id." AND d.status=1
        ".($id_product!='' ? " and i.po_item_pro_id=".$id_product."" :'')."
        GROUP by i.po_item_des_id,i.po_item_sub_des_id");
        return $sql->result_array();
	}
	
	function check_purchase_halmarking_details($po_id)
	{
	    $sql=$this->db->query("SELECT i.po_item_id,IFNULL(SUM(i.no_of_pcs-IFNULL(i.qc_failed_pcs,0)-IFNULL(i.hm_rejected_pcs,0)),0) as total_pcs, 
        IFNULL(SUM(IFNULL(i.gross_wt,0)-IFNULL(i.qc_failed_gwt,0)-IFNULL(i.hm_rejected_gwt,0)),0) as total_gwt,
        IFNULL(SUM(IFNULL(i.less_wt,0)-IFNULL(i.qc_failed_lwt,0)-IFNULL(i.hm_rejected_lwt,0)),0) as total_lwt,
        IFNULL(SUM(IFNULL(i.net_wt,0)-IFNULL(i.qc_failed_nwt,0)-IFNULL(i.hm_rejected_nwt,0)),0) as total_nwt,
        i.mc_type,IFNULL(i.mc_value,0) as mc_value,IFNULL(i.item_wastage,0) as item_wastage, i.item_pure_wt as item_pure_wt,i.is_rate_fixed,IFNULL(i.fix_rate_per_grm,0) as fix_rate_per_grm,
        mt.tgrp_id,tax.tax_percentage
        FROM ret_purchase_order_items i
        LEFT JOIN ret_po_qc_issue_details d ON d.po_item_id=i.po_item_id
        LEFT JOIN ret_product_master p ON p.pro_id=i.po_item_pro_id
        LEFT JOIN ret_category cat ON cat.id_ret_category=p.cat_id
        LEFT JOIN metal mt ON mt.id_metal=cat.id_metal
        LEFT JOIN (select i.tgi_taxcode,i.tgi_tgrpcode,
	    (m.tax_percentage) as tax_percentage,(i.tgi_calculation) as tgi_calculation
		FROM ret_taxgroupitems i
		LEFT JOIN ret_taxmaster m on m.tax_id=i.tgi_taxcode) as tax on tax.tgi_tgrpcode=mt.tgrp_id
        WHERE i.po_item_po_id=".$po_id." and d.status=1 AND i.status=4 AND i.is_halmarked=1 and d.is_lot_created=0
        GROUP by i.po_item_id");
        //print_r($this->db->last_query());exit;
        return $sql->result_array();
	}
	
	function get_purchase_order_item_search($po_item_id)
	{
	    $sql=$this->db->query("SELECT i.po_item_po_id,i.po_item_id,IFNULL(SUM(i.no_of_pcs-IFNULL(i.qc_failed_pcs,0)-IFNULL(i.hm_rejected_pcs,0)),0) as total_pcs, 
        IFNULL(SUM(IFNULL(i.gross_wt,0)-IFNULL(i.qc_failed_gwt,0)-IFNULL(i.hm_rejected_gwt,0)),0) as total_gwt,
        IFNULL(SUM(IFNULL(i.less_wt,0)-IFNULL(i.qc_failed_lwt,0)-IFNULL(i.hm_rejected_lwt,0)),0) as total_lwt,
        IFNULL(SUM(IFNULL(i.net_wt,0)-IFNULL(i.qc_failed_nwt,0)-IFNULL(i.hm_rejected_nwt,0)),0) as total_nwt,
        i.mc_type,IFNULL(i.mc_value,0) as mc_value,IFNULL(i.item_wastage,0) as item_wastage, i.item_pure_wt as item_pure_wt,i.is_rate_fixed,IFNULL(i.fix_rate_per_grm,0) as fix_rate_per_grm,
        mt.tgrp_id,tax.tax_percentage,i.purchase_touch
        FROM ret_purchase_order_items i
        LEFT JOIN ret_po_qc_issue_details d ON d.po_item_id=i.po_item_id
        LEFT JOIN ret_product_master p ON p.pro_id=i.po_item_pro_id
        LEFT JOIN ret_category cat ON cat.id_ret_category=p.cat_id
        LEFT JOIN metal mt ON mt.id_metal=cat.id_metal
        LEFT JOIN (select i.tgi_taxcode,i.tgi_tgrpcode,
	    (m.tax_percentage) as tax_percentage,(i.tgi_calculation) as tgi_calculation
		FROM ret_taxgroupitems i
		LEFT JOIN ret_taxmaster m on m.tax_id=i.tgi_taxcode) as tax on tax.tgi_tgrpcode=mt.tgrp_id
        WHERE i.po_item_id=".$po_item_id." AND i.is_halmarked=1
        GROUP by i.po_item_id");
        return $sql->row_array();
	}
	
	
	function get_pur_order_item_details($po_item_id)
	{
	    $sql=$this->db->query("SELECT i.po_item_po_id,i.po_item_id,IFNULL(SUM(i.no_of_pcs-IFNULL(i.qc_failed_pcs,0)-IFNULL(i.hm_rejected_pcs,0)),0) as total_pcs, 
        IFNULL(SUM(IFNULL(i.gross_wt,0)-IFNULL(i.qc_failed_gwt,0)-IFNULL(i.hm_rejected_gwt,0)),0) as total_gwt,
        IFNULL(SUM(IFNULL(i.less_wt,0)-IFNULL(i.qc_failed_lwt,0)-IFNULL(i.hm_rejected_lwt,0)),0) as total_lwt,
        IFNULL(SUM(IFNULL(i.net_wt,0)-IFNULL(i.qc_failed_nwt,0)-IFNULL(i.hm_rejected_nwt,0)),0) as total_nwt,
        i.mc_type,IFNULL(i.mc_value,0) as mc_value,IFNULL(i.item_wastage,0) as item_wastage, i.item_pure_wt as item_pure_wt,i.is_rate_fixed,IFNULL(i.fix_rate_per_grm,0) as fix_rate_per_grm,
        mt.tgrp_id,tax.tax_percentage,i.purchase_touch
        FROM ret_purchase_order_items i
        LEFT JOIN ret_po_qc_issue_details d ON d.po_item_id=i.po_item_id
        LEFT JOIN ret_product_master p ON p.pro_id=i.po_item_pro_id
        LEFT JOIN ret_category cat ON cat.id_ret_category=p.cat_id
        LEFT JOIN metal mt ON mt.id_metal=cat.id_metal
        LEFT JOIN (select i.tgi_taxcode,i.tgi_tgrpcode,
	    (m.tax_percentage) as tax_percentage,(i.tgi_calculation) as tgi_calculation
		FROM ret_taxgroupitems i
		LEFT JOIN ret_taxmaster m on m.tax_id=i.tgi_taxcode) as tax on tax.tgi_tgrpcode=mt.tgrp_id
        WHERE i.po_item_id=".$po_item_id."
        GROUP by i.po_item_id");
        return $sql->row_array();
	}
	
	function get_ret_settings($settings)
	{
		$data=$this->db->query("SELECT value FROM ret_settings where name='".$settings."'");
		return $data->row()->value;
	}
	function get_headOffice()
	{
		$data=$this->db->query("SELECT b.is_ho,b.id_branch,name FROM branch b where b.is_ho=1");
		return $data->row_array();
	}
	
	function update_po_paymentData($data,$arith){ 
		$sql = "UPDATE ret_purchase_order SET total_payable_amt=(total_payable_amt".$arith." ".$data['total_payable_amt']."),total_payable_wt=(total_payable_wt".$arith." ".$data['total_payable_wt'].")
		WHERE po_id=".$data['po_id'];  
		//print_r($sql);exit;
		$status = $this->db->query($sql);
		return $status;
	}
	
	function get_purchase_order_hm_details($hm_process_id)
	{
	    $sql=$this->db->query("SELECT i.status
        FROM ret_po_hm_process_details d 
        LEFT JOIN ret_po_halmark_process p ON p.hm_process_id=d.hm_issue_id
        LEFT JOIN ret_purchase_order_items i ON i.po_item_id=d.hm_po_item_id
        LEFT JOIN ret_po_qc_issue_details qc ON qc.po_item_id=i.po_item_id
        WHERE qc.status=1 AND i.is_lot_created=0 AND i.status=4
        and p.hm_process_id=".$hm_process_id."");
        return $sql->result_array();
	}
	
	//Lot Generate
	
	//Purchase Payment Details
	function get_supplier_pay_details($data)
	{
        /*$sql = $this->db->query("SELECT l.debit,l.credit,l.debit,l.credit,IFNULL(SUM(l.debit),0)-IFNULL(SUM(l.credit),0) as balance_amount
        FROM ret_view_grn_pay_ledger l 
        WHERE l.sup_id IS NOT NULL
        ".($data['id_karigar']!='' ? " and l.sup_id=".$data['id_karigar']."" :'')."
        Having balance_amount > 0");*/
        
       /* if(empty($data['id_metal'])){
            $sql = $this->db->query("SELECT 
                                        	ifnull(SUM(COALESCE(CASE WHEN (trans_type = 2) THEN ifnull(trans_amount,0) END,0)) - 
                                            SUM(COALESCE(CASE WHEN (trans_type = 1) THEN ifnull(trans_amount,0) END,0)),0) as balance_amount, 
                                        	ifnull(SUM(COALESCE(CASE WHEN (trans_rec_type = 1 AND trans_type = 2) THEN ifnull(purewt,0) END,0)) - 
                                            SUM(COALESCE(CASE WHEN (trans_rec_type = 1 AND trans_type = 1) THEN ifnull(purewt,0) END,0)),0) balance_purewt
                                        FROM view_supplier_transactions l 
                                        WHERE l.customer_id IS NOT NULL
                                                ".($data['id_karigar']!='' ? " and l.customer_id=".$data['id_karigar']."" :'')."");
        }else{
             $sql = $this->db->query("SELECT 
                                        	ifnull(SUM(COALESCE(CASE WHEN (trans_type = 2) THEN ifnull(trans_amount,0) END,0)) - 
                                            SUM(COALESCE(CASE WHEN (trans_type = 1) THEN ifnull(trans_amount,0) END,0)),0) as balance_amount, 
                                        	ifnull(SUM(COALESCE(CASE WHEN (trans_rec_type = 1 AND trans_type = 2) THEN ifnull(purewt,0) END,0)) - 
                                            SUM(COALESCE(CASE WHEN (trans_rec_type = 1 AND trans_type = 1) THEN ifnull(purewt,0) END,0)),0) balance_purewt 
                                        FROM view_supplier_transactions l 
                                        WHERE l.customer_id IS NOT NULL
                                                ".($data['id_karigar']!='' ? " and l.customer_id=".$data['id_karigar']."" :'')."
                                                ".($data['id_metal']!='' ? " and l.catid=".$data['id_metal']."" :'')."
                                                ");
        }*/
        
        $sql = $this->db->query("SELECT 
                                        	ifnull(SUM(COALESCE(CASE WHEN (trans_type = 2 AND trans_screen_id != 6) THEN ifnull(trans_amount,0) END,0)) - 
                                            SUM(COALESCE(CASE WHEN (trans_type = 1) THEN ifnull(trans_amount,0) 
                                                WHEN (trans_type = 2 AND trans_screen_id = 6) THEN ifnull(trans_amount,0) END,0)),0) as balance_amount, 
                                        	ifnull(SUM(COALESCE(CASE WHEN (trans_rec_type = 1 AND trans_type = 2) THEN ifnull(purewt,0) END,0)) - 
                                            SUM(COALESCE(CASE WHEN (trans_rec_type = 1 AND trans_type = 1) THEN ifnull(purewt,0) END,0)),0) balance_purewt
                                        FROM view_supplier_transactions l 
                                        WHERE l.customer_id IS NOT NULL
                                                ".($data['id_karigar']!='' ? " and l.customer_id=".$data['id_karigar']."" :'')."");
        
        //echo $this->db->last_query();exit;
        return $sql->row_array();
	}
	
	function purchase_payment_details($data)
	{
	    $returnData=array('item_details'=>[]);
	    $sql=$this->db->query("SELECT i.po_item_id,IFNULL(SUM(i.no_of_pcs-IFNULL(i.qc_failed_pcs,0)-IFNULL(i.hm_rejected_pcs,0)),0) as total_pcs, 
        IFNULL(SUM(IFNULL(i.gross_wt,0)-IFNULL(i.qc_failed_gwt,0)-IFNULL(i.hm_rejected_gwt,0)),0) as total_gwt,
        IFNULL(SUM(IFNULL(i.less_wt,0)-IFNULL(i.qc_failed_lwt,0)-IFNULL(i.hm_rejected_lwt,0)),0) as total_lwt,
        IFNULL(SUM(IFNULL(i.net_wt,0)-IFNULL(i.qc_failed_nwt,0)-IFNULL(i.hm_rejected_nwt,0)),0) as total_nwt,
        i.mc_type,IFNULL(i.mc_value,0) as mc_value,pro.product_name,des.design_name,subDes.sub_design_name,
        cat.name as category_name,pur.purity,i.item_pure_wt,i.item_wastage,p.total_payable_amt,k.firstname as karigar_name,IFNULL(k.contactno1,'') as mobile
        FROM ret_purchase_order_items i
        LEFT JOIN ret_purchase_order p ON p.po_id=i.po_item_po_id
        LEFT JOIN ret_karigar k ON k.id_karigar=p.po_karigar_id
        LEFT JOIN ret_product_master pro ON pro.pro_id=i.po_item_pro_id
        LEFT JOIN ret_design_master des ON des.design_no=i.po_item_des_id
        LEFT JOIN ret_sub_design_master subDes ON  subDes.id_sub_design=i.po_item_sub_des_id
        LEFT JOIN ret_category cat ON cat.id_ret_category=p.id_category
        LEFT JOIN ret_purity pur ON pur.id_purity=p.id_purity
        WHERE p.po_ref_no='".$data['po_ref_no']."'
        GROUP by i.po_item_id");
        $itemDetails=$sql->result_array();
        foreach($itemDetails as $item)
        {
            $returnData['item_details'][]=array(
                    'total_payable_amt' =>$item['total_payable_amt'],
                    'category_name'     =>$item['category_name'],
                    'design_name'       =>$item['design_name'],
                    'item_pure_wt'      =>$item['item_pure_wt'],
                    'item_wastage'      =>$item['item_wastage'],
                    'mc_type'           =>$item['mc_type'],
                    'mc_value'          =>$item['mc_value'],
                    'po_item_id'        =>$item['po_item_id'],
                    'product_name'      =>$item['product_name'],
                    'purity'            =>$item['purity'],
                    'sub_design_name'   =>$item['sub_design_name'],
                    'total_gwt'         =>$item['total_gwt'],
                    'total_lwt'         =>$item['total_lwt'],
                    'total_nwt'         =>$item['total_nwt'],
                    'total_pcs'         =>$item['total_pcs'],
                    'karigar_name'      =>$item['karigar_name'],
                    'mobile'            =>$item['mobile'],
                    'rate_fixing_det'   =>$this->item_rate_fixing_details($item['po_item_id']),
                    );
        }
        
        
        $payQuery=$this->db->query("SELECT p.po_id,IFNULL(csh.tot_pay_amt,0) as tot_pay_amt,IFNULL(wt.tot_pay_wt,0) as tot_pay_wt
        FROM ret_purchase_order p 
        LEFT JOIN(SELECT pay.pay_po_id,IFNULL(SUM(pay.pay_amt),0) as tot_pay_amt
                 FROM ret_po_payment pay
                 LEFT JOIN ret_purchase_order p on p.po_id=pay.pay_po_id
                 WHERE p.po_ref_no='".$data['po_ref_no']."' AND pay.type=1) as csh ON csh.pay_po_id=p.po_id
        LEFT JOIN(SELECT pay.pay_po_id,IFNULL(SUM(pay.pay_wt),0) as tot_pay_wt
                 FROM ret_po_payment pay
                 LEFT JOIN ret_purchase_order p on p.po_id=pay.pay_po_id
                 WHERE p.po_ref_no='".$data['po_ref_no']."' AND pay.type=2) as wt ON wt.pay_po_id=p.po_id
        WHERE p.po_ref_no='".$data['po_ref_no']."'");
        $returnData['pay_details']= $payQuery->row_array();
        
        $payHistory=$this->db->query("SELECT pay.pay_id,date_format(pay.pay_create_on,'%d-%m-%Y') as pay_date,pay.pay_refno,IFNULL(SUM(pay.pay_amt),0) as tot_cash_pay,IFNULL(SUM(pay.pay_wt),0) as tot_pay_wt,
        k.firstname as karigar_name,p.po_ref_no
        FROM ret_po_payment pay 
        LEFT JOIN ret_purchase_order p ON p.po_id=pay.pay_po_id
        LEFT JOIN ret_karigar k ON k.id_karigar=p.po_karigar_id
        WHERE p.po_ref_no='".$data['po_ref_no']."'
        GROUP by pay.pay_po_id,pay.pay_refno");
        $returnData['pay_history']= $payHistory->result_array();
        
        return $returnData;
	}
	
	
	function item_rate_fixing_details($po_item_id)
	{
	    $rateFixing=$this->db->query("SELECT IFNULL(r.rate_fix_wt,0) as rate_fix_wt,IFNULL(r.rate_fix_rate,0) as rate_fix_rate,r.rate_fix_id,IFNULL(r.rate_fix_amt,0) as rate_fix_amt,r.rate_fix_type,
	    r.total_tax_amount,r.total_amount,date_format(r.rate_fix_created_on,'%d-%m-%Y') as rate_fix_created_on
        FROM ret_po_rate_fix r
        LEFT JOIN ret_purchase_order_items i ON i.po_item_id=r.rate_fix_po_item_id 
        LEFT JOIN ret_purchase_order pur ON pur.po_id=i.po_item_po_id
        LEFT JOIN ret_product_master p ON p.pro_id=i.po_item_pro_id
        LEFT JOIN employee emp ON emp.id_employee=r.rate_fix_create_by
        WHERE i.po_item_id=".$po_item_id."");
        //print_r($this->db->last_query());exit;
        return $rateFixing->result_array();
	}
	
	function get_PO_Ratefix_List($from_date,$to_date)
	{
	    $sql=$this->db->query("SELECT rate_fix_id, po_ref_no, date_format(rate_fix_created_on, '%d-%m-%Y %H:%i:%s') as ratefixon, rate_fix_wt, 
	                            rate_fix_rate, total_amount, kr.firstname as karigar  
	                            FROM ret_po_rate_fix as rf 
	                            LEFT JOIN ret_purchase_order as po ON po.po_id = rf.rate_fix_po_item_id 
	                            LEFT JOIN ret_karigar as kr ON kr.id_karigar = po.po_karigar_id 
	                            WHERE (date(rf.rate_fix_created_on) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')");
	                            
	   //echo $this->db->last_query();exit;
        return $sql->result_array();
	}
	
	function get_PurchasePaymentList($from_date,$to_date)
	{
	    $sql=$this->db->query("SELECT pay.pay_id,date_format(pay.pay_create_on,'%d-%m-%Y') as pay_date,pay.pay_refno,IFNULL(SUM(pay.pay_amt),0) as tot_cash_pay,IFNULL(SUM(pay.pay_wt),0) as tot_pay_wt,
        k.firstname as karigar_name,p.po_ref_no
        FROM ret_po_payment pay 
        LEFT JOIN ret_purchase_order p ON p.po_id=pay.pay_po_id
        LEFT JOIN ret_karigar k ON k.id_karigar=p.po_karigar_id
        GROUP by pay.pay_po_id,pay.pay_refno");
        return $sql->result_array();
	}
	
	function get_PurchaseSupplierPaymentList($from_date,$to_date)
	{
	    $sql= $this->db->query("SELECT pay.pay_id,date_format(pay.pay_create_on,'%d-%m-%Y') as pay_date,pay.pay_refno,
                        		IFNULL(SUM(pay.pay_amt),0) as pay_amt,
                                k.firstname as karigar_name,if(bill_type = 1, 'Purchase', 'Advance') as bill_type,
                                if(pay.pay_status=1,'Success','Cancelled') as status,pay.pay_status
                                FROM ret_po_payment pay  
                                LEFT JOIN ret_karigar k ON k.id_karigar = pay.pay_sup_id 
                                WHERE (date(pay.pay_create_on) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') 
                                GROUP by pay.pay_id ");
        return $sql->result_array();
	}
	
	
	
	//Purchase Payment Details
	
	
	function getAvailableOrders($SearchTxt, $supplierId){

		$data = $this->db->query("SELECT pur_no as label, id_customerorder as value FROM customerorder WHERE pur_no LIKE '%".$SearchTxt."%'");

		return $data->result_array();

	}
	
	
	//Rate Fixing
	function get_rate_fixing_items($data)
	{
	    $sql=$this->db->query("SELECT i.po_item_id,pro.product_name,des.design_name,subDes.sub_design_name,
        cat.name as category_name,pur.purity,i.item_pure_wt,i.item_wastage,p.po_ref_no,'0' as fixed_wt,(i.item_pure_wt-IFNULL(paid.paid_wt,0)) as balance_wt
        FROM ret_purchase_order_items i
        LEFT JOIN ret_purchase_order p ON p.po_id=i.po_item_po_id
        LEFT JOIN ret_karigar k ON k.id_karigar=p.po_karigar_id
        LEFT JOIN ret_product_master pro ON pro.pro_id=i.po_item_pro_id
        LEFT JOIN ret_design_master des ON des.design_no=i.po_item_des_id
        LEFT JOIN ret_sub_design_master subDes ON  subDes.id_sub_design=i.po_item_sub_des_id
        LEFT JOIN ret_category cat ON cat.id_ret_category=i.po_item_cat_id
        LEFT JOIN ret_purity pur ON pur.id_purity=p.id_purity
        LEFT JOIN(SELECT IFNULL(SUM(f.rate_fix_wt),0) as paid_wt,f.rate_fix_po_item_id
        FROM ret_po_rate_fix f 
        GROUP by f.rate_fix_po_item_id) as paid ON paid.rate_fix_po_item_id=i.po_item_id
        WHERE i.is_rate_fixed=0 AND p.po_ref_no='".$data['po_ref_no']."'
        Having balance_wt>0");
        //print_r($this->db->last_query());exit;
        return $sql->result_array();
	}
	
	function get_bill_details($SearchTxt,$bill_cus_id){
		$data = $this->db->query("SELECT b.bill_id as value,b.bill_no as label,b.tot_bill_amount
        FROM ret_billing b 
        WHERE b.bill_no LIKE '%".$SearchTxt."%' and b.bill_status=1 AND (b.bill_type=1 OR b.bill_type=2 OR b.bill_type=3)
        ".($bill_cus_id!='' ? " and b.bill_cus_id=".$bill_cus_id."" :'')."
        ");
        //print_r($this->db->last_query());exit;
		return $data->result_array();
	}
	
	function get_retWallet_details($id_karigar)
	{
		$data=$this->db->query("SELECT id_wallet,id_karigar FROM ret_karigar_wallet w where w.id_karigar=".$id_karigar."");
		if($data->num_rows()>0)
		{
			return array('status'=>TRUE,'id_wallet'=>$data->row('id_wallet'));
		}else{
			return array('status'=>FALSE,'id_wallet'=>'');
		}
	}
	
	function get_supplier_advance_details($data)
	{
	    $data=$this->db->query("SELECT * FROM `ret_karigar_wallet` where id_karigar=".$data['id_karigar']."");
	    return $data->row_array();
	}
	
	//Update Wallet Account
	function updateWalletData($data,$arith)
	{ 
		$sql = "UPDATE ret_karigar_wallet SET amount=(amount".$arith." ".$data['amount'].") WHERE id_wallet=".$data['id_wallet'];  
		$status = $this->db->query($sql);
		return $status;
	}
	//Update Wallet Account
	
	//Rate Fixing
	
		//Purchase Item return
	function getRejectedPos($data)
	{
		/*$sql = $this->db->query("SELECT po.po_id as value, poitm.po_item_id, po.po_ref_no as label, poitm.po_returned_pcs,g.grn_type
								
                                FROM ret_purchase_order as po 
								
                                LEFT JOIN ret_purchase_order_items poitm ON po.po_id = poitm.po_item_po_id

                                LEFT JOIN ret_po_qc_issue_details po_qc ON po_qc.po_item_id = poitm.po_item_id
								
                                LEFT JOIN ret_purchase_return_items r ON r.pur_ret_po_item_id = poitm.po_item_id
                                
                                LEFT JOIN ret_purchase_return ret ON ret.pur_return_id = r.pur_ret_id AND ret.bill_status = 1
                                
                                LEFT JOIN ret_grn_entry g ON g.grn_id = po.po_grn_id
								
                                WHERE 
                                ".($data['purchase_type']==0 ? " po_qc.failed_pcs > 0 AND po_qc.failed_gwt > 0" :" po_qc.passed_pcs > 0 AND po_qc.passed_gwt > 0")."								
                                ".($data['id_karigar']!='' && $data['purchase_type']==0 ? " and po.po_karigar_id=".$data['id_karigar']."" :'')."
                                AND ret.pur_return_id IS NULL
                                GROUP BY po.po_id order by  po.po_id asc ");*/
        
        $sql = $this->db->query("SELECT p.po_id as value,p.po_ref_no as label,g.grn_type
        FROM ret_po_qc_issue_details d
        LEFT JOIN ret_po_qc_issue_process q ON q.qc_process_id = d.qc_process_id
        LEFT JOIN ret_purchase_order_items i ON i.po_item_id = d.po_item_id
        LEFT JOIN ret_purchase_order p ON p.po_id = i.po_item_po_id
        LEFT JOIN ret_grn_entry g ON g.grn_id = p.po_grn_id
        LEFT JOIN ret_purchase_return_items r ON r.id_qc_issue_details = d.id_qc_issue_details
        LEFT JOIN ret_purchase_return ret ON ret.pur_return_id = r.pur_ret_id and ret.bill_status = 1
        where 
        ".($data['purchase_type']==0 ? " d.failed_pcs > 0 AND d.failed_gwt > 0" :" d.passed_pcs > 0 AND d.passed_gwt > 0")."
        ".($data['id_karigar']!='' && $data['purchase_type']==0 ? " and p.po_karigar_id=".$data['id_karigar']."" :'')."
        AND ret.pur_return_id IS NULL
        GROUP BY p.po_id order by p.po_id DESC");
								
		return $sql->result_array();
		
	}
	
	function getRejectedItemsByPoId($poid,$purchase_type)
	{
	    $purchaseaitems = array();
		$sql = $this->db->query("SELECT po_qc.id_qc_issue_details,po.po_id, poitm.po_item_id, po.po_ref_no, poitm.no_of_pcs as purchasedpcs, 
						poitm.gross_wt as purchasedwt,
						".($purchase_type==0 ? "po_qc.failed_pcs" :"po_qc.passed_pcs")." as qcfaildpcs, ".($purchase_type==0 ? "po_qc.failed_gwt" :"po_qc.passed_gwt")." as qcfaildwt, 
						cat.name as catname, pro.product_name as product_name, des.design_name as design_name, 
						subDes.sub_design_name, sup.firstname as karigar, cat.id_ret_category as cat_id, po.po_karigar_id as karigar_idyy, 
						poitm.qc_failed_pcs as piece, poitm.qc_failed_gwt as weight,sup.firstname as supplier, cat.tgrp_id,
                        pro.pro_id,des.design_no as des_id,subDes.id_sub_design,poitm.item_wastage as wast_per,
                        poitm.purchase_touch as pur_touch,poitm.mc_type,poitm.mc_value,sup.karigar_calc_type,g.grn_type,poitm.tax_group,poitm.tax_type,poitm.pure_wt_calc_type,
                        poitm.calculation_based_on,pro.purchase_mode
                        FROM ret_po_qc_issue_details po_qc
						LEFT JOIN ret_purchase_order_items poitm ON poitm.po_item_id = po_qc.po_item_id
						LEFT JOIN ret_purchase_order as po ON po.po_id = poitm.po_item_po_id 
						LEFT JOIN ret_category as cat ON cat.id_ret_category = poitm.po_item_cat_id 
						LEFT JOIN ret_product_master as pro ON pro.pro_id = poitm.po_item_pro_id 
						LEFT JOIN ret_design_master as des ON des.design_no = poitm.po_item_des_id 
						LEFT JOIN ret_sub_design_master subDes ON subDes.id_sub_design=poitm.po_item_sub_des_id 
						LEFT JOIN ret_karigar as sup ON sup.id_karigar = po.po_karigar_id
						LEFT JOIN ret_grn_entry g on g.grn_id = po.po_grn_id
						LEFT JOIN ret_purchase_return_items r ON r.id_qc_issue_details = po_qc.id_qc_issue_details
						LEFT JOIN ret_purchase_return ret ON ret.pur_return_id = r.pur_ret_id and ret.bill_status = 1
						WHERE ".($purchase_type==0 ? " po_qc.failed_pcs > 0 AND po_qc.failed_gwt > 0" :" po_qc.passed_pcs > 0 AND po_qc.passed_gwt > 0")."
						AND ret.pur_return_id IS NULL
						AND poitm.po_item_po_id = '".$poid."'");
		//print_r($this->db->last_query());exit;
        $result = $sql->result_array();
        foreach($result as $val){
            $val['stone_details'] = $this->get_qc_stone_rejected_details($val['id_qc_issue_details'],$purchase_type);
            $purchaseaitems[]=$val;
        }
		return array('purchaseaitems' => $purchaseaitems, 'purchasestones' => $this->get_purchaseItemStones($poid), 'purchaseothermetals' => $this->get_purchaseItemOtherMetal($poid));
	}
	
	function get_qc_stone_rejected_details($id_qc_issue_details,$purchase_type){
	    $sql = $this->db->query("SELECT
	    ".($purchase_type==0 ? 's.qc_rejected_pcs' :'s.qc_passed_pcs')." as qc_rejected_pcs,
	    ".($purchase_type==0 ? 's.qc_rejected_wt' :'s.qc_passed_wt')." as qc_rejected_wt,
	    st.stone_name,m.uom_name,m.uom_id,
	    i.is_apply_in_lwt,i.po_stone_id as stone_id,i.po_stone_calc_based_on,st.stone_type
        FROM ret_po_qc_issue_stone_details s
        LEFT JOIN ret_po_stone_items i ON i.po_st_id = s.po_st_id
        LEFT JOIN ret_stone st ON st.stone_id = i.po_stone_id
        LEFT JOIN ret_uom m ON m.uom_id = i.po_stone_uom
        WHERE s.id_qc_issue_details = ".$id_qc_issue_details."
        HAVING qc_rejected_pcs > 0");
        return $sql->result_array();
	}

	
	function get_purchaseItemStones($poId)
	{
	    $stonequery = $this->db->query("SELECT po_stone_id as stone_id, sum(po_stone_pcs) as pieces, sum(po_stone_wt) as stonewt 
	                                    FROM ret_po_stone_items as rets
	                                    LEFT JOIN ret_stone as st ON st.stone_id = rets.po_stone_id 
                                        WHERE st.stone_type = 1 AND rets.po_item_id = '".$poid."' 
                                        GROUP BY rets.po_stone_id");
        return $stonequery->result_array();
	}
	
	function get_purchaseItemOtherMetal($poId)
	{
	    $stonequery = $this->db->query("SELECT po_item_metal as item_metal, sum(po_other_item_pcs) as pieces, sum(po_other_item_gross_weight) as grosswt 
	                                    FROM ret_po_other_item as rets
                                        WHERE rets.po_item_id = '".$poid."' 
                                        GROUP BY po_item_metal
                                        ");
        return $stonequery->result_array();
	}
	
	
	function getRejectedItemsBySupId($supid)
	{
	    $purchaseitems      = array();
	    $purchasestones     = array();
	    $purchaseothermetals= array();
		$sql = $this->db->query("SELECT po.po_id, poitm.po_item_id, po.po_ref_no, poitm.no_of_pcs as purchasedpcs, 
						poitm.gross_wt as purchasedwt,
						poitm.qc_failed_pcs as qcfaildpcs, poitm.qc_failed_gwt as qcfaildwt, 
						cat.name as catname, pro.product_name as product_name, des.design_name as design_name, 
						subDes.sub_design_name, sup.firstname as karigar , cat.id_ret_category as cat_id, po.po_karigar_id as karigar_id , 
						poitm.qc_failed_pcs as piece , poitm.qc_failed_gwt as weight,sup.firstname as supplier, cat.tgrp_id
						FROM ret_purchase_order_items poitm 
						LEFT JOIN ret_purchase_order as po ON po.po_id = poitm.po_item_po_id 
						LEFT JOIN ret_category as cat ON cat.id_ret_category = poitm.po_item_cat_id 
						LEFT JOIN ret_product_master as pro ON pro.pro_id = poitm.po_item_pro_id 
						LEFT JOIN ret_design_master as des ON des.design_no = poitm.po_item_des_id 
						LEFT JOIN ret_sub_design_master subDes ON subDes.id_sub_design=poitm.po_item_sub_des_id 
						LEFT JOIN ret_karigar as sup ON sup.id_karigar = po.po_karigar_id 
						WHERE (poitm.qc_failed_pcs - poitm.po_returned_pcs) > 0 AND (qc_failed_gwt - poitm.po_returned_wt) > 0 
						AND po.po_karigar_id = '".$supid."'");
		//print_r($this->db->last_query());exit;
		//return $sql->result_array();
		$purchaseitems = $sql->result_array();
		foreach($purchaseitems as $pkey => $pval){
		    $purchasestones[] = $this->get_purchaseItemStones($pval['po_id']);
		    $purchaseothermetals[] = $this->get_purchaseItemOtherMetal($pval['po_id']);
		}
		return array('purchaseaitems' => $purchaseitems, 'purchasestones' => $purchasestones, 'purchaseothermetals' => $purchaseothermetals);
	}
	function getReturnedRequestList()
	{
		/*$sql = $this->db->query("SELECT po.po_id,  poitm.po_item_id, po.po_ref_no, poitm.no_of_pcs as purchasedpcs, 
							poitm.gross_wt as purchasedwt,
							date_format(pur_ret_created_on, '%d-%m-%Y') as returndate, 
							poitm.qc_failed_pcs as qcfaildpcs, poitm.qc_failed_gwt as qcfaildwt, 
							cat.name as categoryname, pro.product_name as product_name, des.design_name as design_name, 
							subDes.sub_design_name, sup.firstname as karigar 
							FROM ret_purchase_return_items as pureitm 
							LEFT JOIN ret_purchase_return as purret ON purret.pur_return_id = pureitm.pur_ret_id
							LEFT JOIN ret_purchase_order_items poitm ON pureitm.pur_ret_po_item_id = poitm.po_item_id
							LEFT JOIN ret_purchase_order as po ON po.po_id = poitm.po_item_po_id 
							LEFT JOIN ret_category as cat ON cat.id_ret_category = poitm.po_item_cat_id 
							LEFT JOIN ret_product_master as pro ON pro.pro_id = poitm.po_item_pro_id 
							LEFT JOIN ret_design_master as des ON des.design_no = poitm.po_item_des_id 
							LEFT JOIN ret_sub_design_master subDes ON subDes.id_sub_design=poitm.po_item_sub_des_id 
							LEFT JOIN ret_karigar as sup ON sup.id_karigar = po.po_karigar_id ORDER BY pur_ret_itm_id desc");*/
							
            $sql=$this->db->query("SELECT r.pur_return_id,r.pur_ret_ref_no,date_format(r.pur_ret_created_on,'%d-%m-%Y') as date_add,IFNULL(r.pur_ret_remark,'') as reason,
            (IFNULL(po_item.ret_pcs,0)) as ret_pcs,IFNULL(po_item.ret_wt,0) as ret_wt,
            k.firstname as karigar_name,r.bill_status,if(r.bill_status=1,'Success','Cancelled') as pur_ret_status
            FROM ret_purchase_return r
            LEFT JOIN ret_karigar k ON k.id_karigar=r.pur_ret_supplier_id
            
            
            LEFT JOIN (SELECT poitm.pur_ret_id,IFNULL(SUM(poitm.pur_ret_pcs),0) as ret_pcs,IFNULL(SUM(poitm.pur_ret_nwt),0) as ret_wt
            FROM ret_purchase_return_items poitm 
            GROUP BY poitm.pur_ret_id) as po_item ON po_item.pur_ret_id = r.pur_return_id
            
            where pur_return_id IS NOT NULL");
            return $sql->result_array();
	}
	
	
	function get_return_non_tag_details($pur_ret_id)
	{
	    $sql = $this->db->query("SELECT * FROM `ret_purchase_return_items` WHERE pur_ret_id = ".$pur_ret_id." AND return_item_type =3");
	    return $sql->result_array();
	}
	
	function get_return_tag_details($pur_ret_id)
	{
	    $sql = $this->db->query("SELECT * FROM `ret_purchase_return_items` WHERE pur_ret_id = ".$pur_ret_id." and tag_id IS NOT NULL");
	    return $sql->result_array();
	}
	
	function getReturnReceipt($id)
	{
	    $sql=$this->db->query("SELECT r.pur_return_id,r.pur_ret_ref_no,date_format(r.pur_ret_created_on,'%d-%m-%Y') as date_add,IFNULL(r.pur_ret_remark,'') as reason,IFNULL(item.ret_pcs,0) as ret_pcs,IFNULL(item.ret_wt,0) as ret_wt,
        k.firstname as karigar_name,k.contactno1 as mobile,IFNULL(k.address1,'') as address1,
        IFNULL(k.address2,'') as address2,IFNULL(k.address3,'') as address3,
        IFNULL(cy.name,'') as city_name,IFNULL(ct.name,'') as country_name,IFNULL(st.name,'') as state_name,
        IFNULL(k.pincode,'') as pincode,k.gst_number,k.pan_no,k.email,k.contactno1 as supplier_mobile,r.pur_ret_round_off,
        r.pur_ret_other_charges,r.pur_ret_tds_percent,r.pur_ret_tds_value,r.pur_ret_other_charges_tds_percent,r.pur_ret_other_charges_tds_value,
        r.pur_ret_tcs_percent,r.pur_ret_tcs_value,r.return_total_cost,r.pur_ret_discount,e.firstname as emp_name,if(r.purchase_type=0,'PURCHASE RETURN','JOB RECEIPT ISSUE') as purchase_type
        FROM ret_purchase_return r
        LEFT JOIN ret_karigar k ON k.id_karigar=r.pur_ret_supplier_id
        LEFT JOIN country ct ON ct.id_country=k.id_country
        LEFT JOIN state st ON st.id_state=k.id_state
        LEFT JOIN city cy ON cy.id_city=k.id_city
        LEFT JOIN employee e On e.id_employee = r.pur_ret_created_by
        LEFT JOIN (SELECT i.pur_ret_id,IFNULL(SUM(i.pur_ret_pcs),0) as ret_pcs,IFNULL(SUM(i.pur_ret_gwt),0) as ret_wt
        FROM ret_purchase_return_items i 
        GROUP by i.pur_ret_id) as item ON item.pur_ret_id=r.pur_return_id
        where pur_return_id IS NOT NULL and pur_return_id=".$id."");
        return $sql->row_array();
	}
	
	
	function getReturnReceiptDetails($id)
    {
        $sql = $this->db->query("SELECT p.product_name as product_name, pureitm.pur_ret_pcs as pur_ret_pcs, pureitm.pur_ret_nwt as pur_ret_nwt,pureitm.pur_ret_gwt as pur_ret_gwt,cat.hsn_code,pureitm.pur_ret_debit_note_amt as pur_ret_item_cost,pureitm.total_cgst_cost as  pur_ret_cgst,pureitm.total_sgst_cost as pur_ret_sgst,pureitm.total_igst_cost as pur_ret_igst,
        pureitm.pur_ret_rate as pur_ret_rate,pureitm.pur_ret_wastage,pureitm.pur_ret_pur_wt,pureitm.pur_ret_purchase_touch,IFNULL(pureitm.total_total_tax,0) as pur_ret_tax_value
	                          
	                            FROM ret_purchase_return_items as pureitm 
	                            LEFT JOIN ret_purchase_return as purret ON purret.pur_return_id = pureitm.pur_ret_id 
	                            LEFT JOIN ret_product_master as p ON p.pro_id = pureitm.id_product 
                                LEFT JOIN ret_category as cat ON cat.id_ret_category = p.cat_id
	                            where purret.pur_return_id=" . $id . "");

        return $sql->result_array();
    }
	
	function getReturnReceiptGSTDetails($id)
    {
        $sql = $this->db->query("SELECT pureitm.pur_ret_tax_percent,IFNULL(SUM(total_total_tax),0) as pur_ret_tax_value,
	                            IFNULL(SUM(total_cgst_cost),0) as cgst_cost,IFNULL(SUM(total_sgst_cost),0) as sgst_cost,IFNULL(SUM(total_igst_cost),0) as igst_cost
	                            FROM ret_purchase_return_items as pureitm 
	                            LEFT JOIN ret_purchase_return as purret ON purret.pur_return_id = pureitm.pur_ret_id 
	                            where purret.pur_return_id=" . $id . " GROUP BY pur_ret_tax_percent");
        return $sql->result_array();
    }
	
	function get_purchase_ret_charge_details($pur_ret_id)
	{
	    $sql = $this->db->query("SELECT ch.name_charge,c.pur_ret_charge_value,c.pur_ret_charge_tax_value
        FROM ret_purchase_return_other_charges c 
        LEFT JOIN ret_charges ch ON ch.id_charge = c.pur_ret_charge_id
        WHERE c.pur_ret_id =".$pur_ret_id."");
        return $sql->result_array();
	}
	
	function get_purchase_ret_charge_gst_details($pur_ret_id)
	{
	    $sql = $this->db->query("SELECT IFNULL(SUM(c.cgst_cost),0) as cgst_cost,IFNULL(SUM(c.sgst_cost),0) as sgst_cost,
	    IFNULL(SUM(c.igst_cost),0) as igst_cost,c.pur_ret_charge_tax
        FROM ret_purchase_return_other_charges c 
        LEFT JOIN ret_charges ch ON ch.id_charge = c.pur_ret_charge_id
        WHERE c.pur_ret_id =".$pur_ret_id."
        GROUP BY c.pur_ret_charge_tax");
        return $sql->result_array();
	}
	
	
	function pur_ret_refno($PurType)
	{
		$lastno = $this->get_max_pur_ret_refno($PurType);
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
	function get_max_pur_ret_refno($PurType)
    {
		$sql = "SELECT pur_ret_ref_no FROM `ret_purchase_return` where purchase_type = ".$PurType." ORDER BY pur_return_id DESC";
		return $this->db->query($sql)->row()->pur_ret_ref_no;	
	}
	
	function get_tag_details($tag_id)
	{
	    $sql=$this->db->query("SELECT * FROM `ret_taging` WHERE tag_id=".$tag_id."");
	    return $sql->row_array();
	}
	
	
	//Purchase Item return
	
	
	
	//order description
	function get_order_description()
	{
	    $sql=$this->db->query("SELECT * FROM `ret_purchase_order_description`");
	    return $sql->result_array();
	}
	
	function get_orderdescription($id="")
	{
	    $sql=$this->db->query("SELECT * FROM `ret_purchase_order_description` where id_order_des=".$id."");
	    return $sql->row_array();
	}
	
	//order description
	
	
	//mail function
	public function send_email($send_mail_from,$email_to,$email_subject,$email_message,$email_cc="",$email_bcc="",$attachment) {
	     $r = array();
	     $sql="SELECT * FROM company";
	     $r = $this->db->query($sql)->result_array();
		 $config = array();
                $config['useragent']     = "CodeIgniter";
                $config['mailpath']      = "/usr/bin/sendmail"; // or "/usr/sbin/sendmail"
                $config['protocol']      = "smtp";
                $config['smtp_host']     = "localhost";
                $config['smtp_port']     = "25";
                $config['mailtype']		 = 'html';
                $config['charset'] 		 = 'utf-8';
                $config['newline'] 		 = "\r\n";
                $config['wordwrap']		 = TRUE;
                $this->load->library('email');
                $this->email->initialize($config);
                $this->email->from($send_mail_from, $r[0]['company_name']);
                $this->email->to($email_to);
				if($email_cc!="")
				{
					$this->email->cc($email_cc); 
				}
     			if($email_bcc!="")
				{
                   $this->email->bcc($email_bcc); 
			    }
			    if($attachment!="")
    			{
    			    $this->email->attach($attachment); 
    			}
            $this->email->subject($email_subject);
            $this->email->message($email_message);   
            //$this->email->message($this->load->view('email/'.$type.'-html', $data, TRUE)); 
            $send= $this->email->send();
            if($send)
            {
                return true;
            }else
            {
                echo $this->email->print_debugger();
            }
	}
	//mail function
	
	
	function get_karigar_pending_orders($data)
	{
	    $sql=$this->db->query("SELECT c.id_customerorder,c.pur_no,k.firstname as karigar_name
        FROM customerorder c 
        LEFT JOIN customerorderdetails d ON d.id_customerorder=c.id_customerorder
        LEFT JOIN joborder j ON j.id_order=d.id_orderdetails
        LEFT JOIN ret_karigar k ON k.id_karigar=j.id_vendor
        WHERE c.order_for=1 AND j.id_vendor IS NOT NULL AND d.orderstatus=3 AND c.pur_no IS NOT NULL
        ".($data['id_karigar']!='' && $data['id_karigar']!=null ? " and j.id_vendor=".$data['id_karigar']."" :'')."
        GROUP by c.id_customerorder");
        return $sql->result_array();
	}
	
	
		function get_karigar_pending_order_details($data)
	{
	    $sql=$this->db->query("SELECT (d.totalitems) as tot_items,concat(w.value,'',u.uom_name) as weight_range,k.firstname as karigar_name,IFNULL(k.email,'') as email,c.company_name,
	    p.product_name,des.design_name,IFNULL(concat(s.value,' ',s.name),'') as size,IFNULL(k.contactno1,'') as mobile,cus.order_no,date_format(cus.order_date,'%d-%m-%Y') as order_date,
	    emp.firstname as emp_name,subDes.sub_design_name,cus.pur_no, date_format(d.smith_due_date, '%d-%m-%Y') as smith_due_date,
	    IFNULL(d.description,'') as description,d.id_orderdetails,j.id as id_joborder,IFNULL(d.delivered_qty,0) as delivered_pcs
        FROM customerorder cus 
        JOIN company c
        LEFT JOIN customerorderdetails d ON d.id_customerorder=cus.id_customerorder
        LEFT JOIN joborder j ON j.id_order=d.id_orderdetails
        LEFT JOIN ret_karigar k ON k.id_karigar=j.id_vendor
        LEFT JOIN ret_product_master p ON p.pro_id=d.id_product
        LEFT JOIN ret_design_master des ON des.design_no=d.design_no
        LEFT JOIN ret_sub_design_master subDes ON subDes.id_sub_design=d.id_sub_design
        LEFT JOIN ret_size s ON s.id_size=d.size
        LEFT JOIN ret_weight w ON w.id_weight=d.weight
        LEFT JOIN ret_uom u ON u.uom_id=w.id_uom
        LEFT JOIN employee emp on emp.id_employee=cus.order_taken_by
        WHERE cus.order_for=1  AND cus.id_customerorder=".$data['id_customerorder']." and d.orderstatus=3
        ");
        //print_r($this->db->last_query());exit;
        return $sql->result_array();
	}
	
	function update_partial_order_delivery($data,$arith){ 
		$sql = "UPDATE customerorderdetails SET delivered_qty=(delivered_qty".$arith." ".$data['delivered_qty']."),delivered_wt=(delivered_wt".$arith." ".$data['delivered_wt']."),is_partial_delivery=".$data['is_partial_delivery']."
		WHERE id_orderdetails=".$data['id_orderdetails'];  
		//print_r($sql);exit;
		$status = $this->db->query($sql);
		return $status;
	}
	
	function update_order_delivery($data,$arith){ 
		$sql = "UPDATE customerorderdetails SET delivered_qty=(delivered_qty".$arith." ".$data['delivered_qty']."),delivered_wt=(delivered_wt".$arith." ".$data['delivered_wt'].")
		WHERE id_orderdetails=".$data['id_orderdetails'];  
		//print_r($sql);exit;
		$status = $this->db->query($sql);
		return $status;
	}
    
    
    //Metal Issue 
    function get_metal_issue_ref_no()
	{
	    $lastno = NULL;
	    $sql = "SELECT MAX(met_issue_ref_id) as lastorder_no
					FROM ret_karigar_metal_issue i
					ORDER BY met_issue_id DESC 
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
	
	function getKarigarMetalIssueList()
	{
	    $sql=$this->db->query("SELECT i.met_issue_id,k.firstname as karigar_name,date_format(i.met_issue_date,'%d-%m-%Y') as issue_date,i.met_issue_ref_id,IFNULL(d.metal_wt,0) as metal_wt,IFNULL(d.issue_metal_pur_wt,0) as issue_metal_pur_wt,
	    IFNULL(c.pur_no,'-') as pur_no,if(i.bill_status=1,'Success','Cancelled') as bill_status
        FROM ret_karigar_metal_issue i 
        LEFT JOIN ret_karigar k ON k.id_karigar=i.met_issue_karid
        LEFT JOIN customerorder c ON c.id_customerorder=i.id_order
        LEFT JOIN(SELECT SUM(d.issue_metal_wt) as metal_wt,d.issue_met_parent_id,IFNULL(SUM(d.issue_metal_pur_wt),0) as issue_metal_pur_wt
        FROM ret_karigar_metal_issue_details d
        GROUP by d.issue_met_parent_id) as d ON d.issue_met_parent_id=i.met_issue_id
        GROUP by i.met_issue_id");
        return $sql->result_array();
	}
	
	function get_available_stock_details($data)
	{
	    /*$sql=$this->db->query("SELECT p.cat_id,s.purity,s.type,s.id_product,IFNULL(SUM(s.pieces),0) as pieces,IFNULL(SUM(s.net_wt),0) as net_wt,IFNULL(SUM(s.gross_wt),0) as gross_wt,
        p.product_name
        FROM ret_purchase_item_stock_summary s 
        LEFT JOIN ret_product_master p ON p.pro_id=s.id_product
        WHERE s.id_stock_summary IS NOT NULL
        GROUP by s.type=1,s.id_product,s.purity
        HAVING purity>0");*/
        
        $sql = $this->db->query("SELECT nt.no_of_piece,nt.gross_wt,nt.net_wt,nt.product as id_product,p.product_name,nt.design,nt.id_sub_design,
        c.name as category_name,c.id_ret_category as cat_id,c.tgrp_id
        FROM ret_nontag_item nt 
        LEFT JOIN ret_product_master p ON p.pro_id = nt.product
        LEFT JOIN ret_category c ON c.id_ret_category = p.cat_id
        LEFT JOIN branch br ON br.id_branch = nt.branch
        WHERE br.is_ho = 1
        ".($data['id_product']!='' ? "and nt.product = ".$data['id_product']."" :'')."
        ");
        
        return $sql->result_array();
	}
	
	function get_metal_issue_purity($purity)
	{
	    $sql=$this->db->query("SELECT * FROM ret_purity WHERE purity='".$purity."'");
	    return $sql->row_array();
	}
	
	function getMetalIssue($id)
	{
	    $sql=$this->db->query("SELECT m.met_issue_id,m.met_issue_ref_id,date_format(m.met_issue_date,'%d-%m-%Y') as issue_date,
        IFNULL(k.firstname,'') as karigar_name,k.contactno1 as mobile,IFNULL(k.address1,'') as address1,
        IFNULL(k.address2,'') as address2,IFNULL(k.address3,'') as address3,
        IFNULL(cy.name,'') as city_name,IFNULL(ct.name,'') as country_name,IFNULL(st.name,'') as state_name,
        IFNULL(k.pincode,'') as pincode
        FROM ret_karigar_metal_issue m 
        LEFT JOIN ret_karigar k ON k.id_karigar=m.met_issue_karid
        LEFT JOIN country ct ON ct.id_country=k.id_country
        LEFT JOIN state st ON st.id_state=k.id_state
        LEFT JOIN city cy ON cy.id_city=k.id_city
        WHERE m.met_issue_id=".$id."");
        return $sql->row_array();
	}
	
	function getMetalIssueDetails($id)
	{
	    $sql=$this->db->query("SELECT IFNULL(d.issue_metal_wt,0) as issue_wt,IFNULL(d.issue_metal_pur_wt,0) as issue_pure_wt,
        c.name as category_name,c.hsn_code,p.purity,pro.product_name,mt.metal
        FROM ret_karigar_metal_issue_details d
        LEFT JOIN ret_category c ON c.id_ret_category=d.issue_cat_id
        LEFT JOIN ret_purity p ON p.id_purity=d.issue_pur_id
        LEFT JOIN ret_product_master pro ON pro.pro_id=d.issu_met_pro_id
        LEFT JOIN metal mt ON mt.id_metal=d.issue_metal
        WHERE d.issue_met_parent_id=".$id."
        ORDER BY d.issu_met_pro_id");
        //print_r($this->db->last_query());exit;
        return $sql->result_array();
	}
	
	function checkNonTagItemExist($data){
		$r = array("status" => FALSE);
		
		$id_design = (isset($data['design']) ? ($data['design']!='' ? $data['design'] :'') :'');
		$id_sub_design = (isset($data['id_sub_design']) ? ($data['id_sub_design']!='' ? $data['id_sub_design'] :'') :'');
        $sql = "SELECT id_nontag_item FROM ret_nontag_item WHERE 
        product=".$data['id_product']." 
        ".($id_design!='' ? " and design=".$id_design."" :'')."
        ".($id_sub_design!='' ? " and id_sub_design=".$id_sub_design."" :'')."
        AND branch=".$data['id_branch']; 
        //print_r($sql);exit;
        $res = $this->db->query($sql);
		if($res->num_rows() > 0){
			$r = array("status" => TRUE, "id_nontag_item" => $res->row()->id_nontag_item); 
		}else{
			$r = array("status" => FALSE, "id_nontag_item" => ""); 
		} 
		return $r;
	}
	
	
	function updateNTData($data,$arith){ 
		$sql = "UPDATE ret_nontag_item SET no_of_piece=(IFNULL(no_of_piece,0)".$arith." ".$data['no_of_piece']."),gross_wt=(gross_wt".$arith." ".$data['gross_wt']."),net_wt=(net_wt".$arith." ".$data['net_wt']."),updated_by=".$data['updated_by'].",updated_on='".$data['updated_on']."' WHERE id_nontag_item=".$data['id_nontag_item'];  
		$status = $this->db->query($sql);
		return $status;
	}
	
	function get_KarigarMetal_issue($metal_issue_id)
    {
        $sql = $this->db->query("SELECT isd.issu_met_pro_id as pro_id,isd.issue_metal_wt as weight
        FROM ret_karigar_metal_issue_details isd
        WHERE isd.issue_met_parent_id=".$metal_issue_id."");
        return $sql->result_array();
    }
	
    //Metal Issue 
    
    
     //Purchase Item Stock Summary
     
    function get_purity_details($id_purity)
    {
        $sql=$this->db->query("SELECT * FROM ret_purity WHERE id_purity=".$id_purity."");
        return $sql->row_array();
    }
    
    function checkPurchaseItemStockExist($data)
    {
		$r = array("status" => FALSE);
        $sql = "SELECT * FROM `ret_purchase_item_stock_summary`  WHERE id_stock_summary IS NOT NULL
        ".($data['id_product']!='' ? " and id_product=".$data['id_product']."" :'')." 
        ".($data['id_branch']!='' ?  " and id_branch=".$data['id_branch']."" :'')."  
        ".($data['purity']!='' ?  " and purity=".$data['purity']."" :'')."  
        "; 		
        $res = $this->db->query($sql);
        
		if($res->num_rows() > 0){
			$r = array("status" => TRUE,'id_stock_summary'=>$res->row()->id_stock_summary); 
		}else{
			$r = array("status" => FALSE); 
		} 
		return $r;
	}
	
	function get_return_bill_details($bill_detail_id)
	{
	    $sql=$this->db->query("SELECT * FROM ret_bill_details d WHERE d.bill_det_id=".$bill_detail_id."");
	    return $sql->row_array();
	}
	
	
	function updatePurItemData($id_stock_summary,$data,$arith){ 
		$sql = "UPDATE ret_purchase_item_stock_summary SET pieces=(pieces".$arith." ".$data['pieces']."),gross_wt=(gross_wt".$arith." ".$data['gross_wt']."),less_wt=(less_wt".$arith." ".$data['less_wt']."),net_wt=(net_wt".$arith." ".$data['net_wt']."),updated_by=".$data['updated_by'].",updated_on='".$data['updated_on']."' 
		WHERE id_stock_summary=".$id_stock_summary." ";  
		$status = $this->db->query($sql);
		return $status;
	}
	
    //Purchase Item Stock Summary
    
    
    
    function get_purchase_order_pending_details($data)
    {
        $returnData=array();
        if($data['po_id']!='')
        {
            $sql=$this->db->query("SELECT c.id_customerorder,c.pur_no,c.order_type,IFNULL(cusRef.order_type,'') as ref_order_type
            FROM customerorderdetails d 
            LEFT JOIN customerorder c ON c.id_customerorder=d.id_customerorder
            LEFT JOIN customerorder cusRef on cusRef.id_customerorder=c.cus_ord_ref
            WHERE  (c.order_type=1 or c.order_type=3 or c.order_type=4) ".($data['id_karigar']!='' ? " and c.id_karigar=".$data['id_karigar']."" :'')."
            GROUP BY d.id_customerorder");
            $result= $sql->result_array();
            
        }
        else
        {

            $sql=$this->db->query("SELECT c.id_customerorder,c.pur_no,c.order_type,IFNULL(cusRef.order_type,'') as ref_order_type
            FROM customerorderdetails d 
            LEFT JOIN customerorder c ON c.id_customerorder=d.id_customerorder
            LEFT JOIN customerorder cusRef on cusRef.id_customerorder=c.cus_ord_ref
            WHERE c.order_status<=3 AND (c.order_type=1 or c.order_type=3 or c.order_type=4) ".($data['id_karigar']!='' ? " and c.id_karigar=".$data['id_karigar']."" :'')."
            GROUP BY d.id_customerorder");
            $result= $sql->result_array();
        }
        foreach($result as $items)
        {
            $returnData[]=array(
                                'id_customerorder'  =>$items['id_customerorder'], 
                                'pur_no'            =>$items['pur_no'], 
                                'order_type'        =>$items['order_type'], 
                                'ref_order_type'    =>$items['ref_order_type'], 
                                'order_details'     =>$this->get_purchase_cus_order_details($items['id_customerorder']),
                            );
        }
        return $returnData;
    }
    
    /*function get_purchase_cus_order_details($id_customerorder)
    {
        $sql=$this->db->query("SELECT p.product_name,des.design_name,s.sub_design_name,d.id_customerorder,d.id_product,d.design_no,
        d.id_sub_design,d.weight,d.totalitems,d.pure_wt
        FROM customerorderdetails d 
        LEFT JOIN customerorder c ON c.id_customerorder=d.id_customerorder
        LEFT JOIN ret_product_master p ON p.pro_id=d.id_product
        LEFT JOIN ret_design_master des ON des.design_no=d.design_no
        LEFT JOIN ret_sub_design_master s ON s.id_sub_design=d.id_sub_design
        where d.id_customerorder=".$id_customerorder."");
        //print_r($this->db->last_query());exit;
        return $sql->result_array();
    }*/
    
    function get_purchase_cus_order_details($id_customerorder)
    {
        $sql=$this->db->query("SELECT p.product_name,des.design_name,s.sub_design_name,d.id_customerorder,d.id_product,d.design_no,
        d.id_sub_design,ifnull(sum(d.weight),0) as weight,ifnull(sum(d.totalitems),0) as totalitems,ifnull(sum(d.pure_wt),0) as pure_wt, p.cat_id as cat_id, 
        ifnull(wt.weight_description, sum(d.weight)) as weight_description, cat.name as catname, 
        ifnull((SELECT sum(no_of_pcs) FROM ret_purchase_order_items WHERE po_order_no = d.id_customerorder AND 
                po_item_pro_id = d.id_product AND po_item_des_id = d.design_no AND  po_item_sub_des_id = d.id_sub_design),0) as receivedpcs ,
        ifnull((SELECT sum(gross_wt) FROM ret_purchase_order_items WHERE po_order_no = d.id_customerorder AND 
                po_item_pro_id = d.id_product AND po_item_des_id = d.design_no AND  po_item_sub_des_id = d.id_sub_design),0) as receivedwt 
        FROM customerorderdetails d 
        LEFT JOIN customerorder c ON c.id_customerorder=d.id_customerorder 
        LEFT JOIN ret_product_master p ON p.pro_id=d.id_product 
        LEFT JOIN ret_category cat ON cat.id_ret_category = p.cat_id 
        LEFT JOIN ret_design_master des ON des.design_no=d.design_no
        LEFT JOIN ret_sub_design_master s ON s.id_sub_design=d.id_sub_design 
        LEFT JOIN ret_weight as wt ON wt.id_weight = d.id_weight_range 
        where d.id_customerorder=".$id_customerorder." GROUP BY d.id_product, d.design_no, d.id_sub_design, id_weight_range");
        //print_r($this->db->last_query());exit;
        return $sql->result_array();
    }
    
    function get_OrderProducts($data)
    {
        $sql=$this->db->query("SELECT p.pro_id,p.product_name,p.purchase_mode,p.tax_type
        FROM customerorder c 
        LEFT JOIN customerorderdetails d ON d.id_customerorder=c.id_customerorder
        LEFT JOIN ret_product_master p ON p.pro_id=d.id_product
        WHERE c.id_customerorder=".$data['id_customerorder']." 
        ".($data['id_ret_category']!='' ? " and p.cat_id=".$data['id_ret_category']."" :'')."
        GROUP BY d.id_product");
       // print_r($this->db->last_query());exit;
        return $sql->result_array();
    }
    
    function get_OrderProductsDesign($data)
    {
        $sql=$this->db->query("SELECT des.design_no,des.design_name
        FROM customerorder c 
        LEFT JOIN customerorderdetails d ON d.id_customerorder=c.id_customerorder
        LEFT JOIN ret_design_master des ON des.design_no=d.design_no
        WHERE c.id_customerorder=".$data['id_customerorder']." 
        ".($data['id_product']!='' ? " and d.id_product=".$data['id_product']."" :'')."
        GROUP BY d.design_no");
        return $sql->result_array();
    }
    
    function get_OrderSubDesigns($data)
    {
        $sql=$this->db->query("SELECT des.id_sub_design,des.sub_design_name
        FROM customerorder c 
        LEFT JOIN customerorderdetails d ON d.id_customerorder=c.id_customerorder
        LEFT JOIN ret_sub_design_master des ON des.id_sub_design=d.id_sub_design
        WHERE c.id_customerorder=".$data['id_customerorder']." 
        ".($data['id_product']!='' ? " and d.id_product=".$data['id_product']."" :'')."
        ".($data['design_no']!='' ? " and d.design_no=".$data['design_no']."" :'')."
        GROUP BY d.id_sub_design");
        return $sql->result_array();
    }
    
    function get_pur_order_det($id_customerorder)
    {
        $sql=$this->db->query("SELECT IFNULL(c.delivered_qty,0) as delivered_qty,IFNULL(c.order_pcs,0) as order_pcs, IFNULL(c.order_approx_wt,0) as order_approx_wt, order_type, IFNULL(cus_ord_ref,0) as cus_ord_ref FROM customerorder c  WHERE c.id_customerorder=".$id_customerorder."");
        return $sql->row_array();
    }
    
    /*function get_cus_order_details($id_cus_order, $id_product){
        $sql = $this->db->query("SELECT id_orderdetails FROM `customerorderdetails` WHERE id_customerorder = '".$id_cus_order."' AND id_product = '".$id_product."' ");
        return $sql->row_array();
    }*/
    
    function get_cus_order_details($id_cus_order, $id_product, $design_no = "", $id_sub_design = "" ){
        $sql = $this->db->query("SELECT id_orderdetails FROM `customerorderdetails` 
                                    WHERE id_customerorder = '".$id_cus_order."' AND id_product = '".$id_product."' 
                                    ".($design_no !='' ? " and design_no = ".$design_no."" :'')." 
                                    ".($id_sub_design!='' ? " and id_sub_design=".$id_sub_design."" :'')." 
                                    ");
        return $sql->row_array();
    }
    
    function updatePurOrderStatus($data,$arith){
		$sql = "UPDATE customerorder SET delivered_qty=(delivered_qty".$arith." ".$data['delivered_qty']."),delivered_wt=(delivered_wt".$arith." ".$data['delivered_wt'].") WHERE id_customerorder=".$data['id_customerorder'];  
		//print_r($sql);exit;
		$status = $this->db->query($sql);
		return $status;
	}
	
	function updatePurOrderDetailStatus($data, $updateId, $arith){
		$sql = "UPDATE customerorderdetails SET delivered_qty=(delivered_qty".$arith." ".$data['delivered_qty']."),delivered_wt=(delivered_wt".$arith." ".$data['delivered_wt']."), 
		      orderstatus = if(totalitems <= delivered_qty ".$arith." ".$data['delivered_qty'].")  WHERE id_orderdetails=".$updateId;  
		//print_r($sql);exit;
		$status = $this->db->query($sql);
		return $status;
	}
	
	function get_karigarPendingPos($karigar){
        $sql    = $this->db->query("SELECT po.po_id, po.po_ref_no, k.firstname as karigar, podet.no_of_pcs as receivedpcs, 
                            podet.gross_wt as received_gwt, ifnull(podet.qc_passed_gwt, 0) as qc_passed_gwt, ifnull(podet.qc_passed_pcs,0) as passedpcs, 
                            ifnull(podet.item_cost,0) as item_cost ,
                            ifnull(payment.pay_amt, 0) paidamt, ifnull(podet.item_cost,0) - ifnull(payment.pay_amt, 0) as balanceamt  
                            FROM ret_purchase_order as po 
                            LEFT JOIN
                            (SELECT po_item_po_id as poitem_id, sum(no_of_pcs) as no_of_pcs, sum(gross_wt) as gross_wt,
                            sum(less_wt) as less_wt, sum(net_wt) as net_wt, sum(qc_failed_pcs) as qc_failed_pcs,
                            sum(qc_failed_gwt) as qc_failed_gwt, sum(qc_failed_nwt) as qc_failed_nwt,
                            sum(qc_failed_lwt) as qc_failed_lwt,
                            sum(qc_passed_gwt) as qc_passed_gwt,
                            sum(qc_passed_lwt) as qc_passed_lwt,
                            sum(qc_passed_pcs) as qc_passed_pcs,
                            sum(qc_passed_nwt) as qc_passed_nwt,
                            sum(po_returned_pcs) as po_returned_pcs,
                            sum(po_returned_wt) as po_returned_wt ,
                             sum(item_cost) as item_cost
                             FROM ret_purchase_order_items as puitm 
                             LEFT JOIN ret_purchase_order as pu ON pu.po_id = puitm.po_item_po_id 
                             WHERE pu.po_karigar_id ='".$karigar."' 
                             GROUP BY po_item_po_id 
                            ) as podet ON podet.poitem_id = po.po_id 
                            LEFT JOIN (SELECT pay_po_id,pay_po_ref_id, 
                            sum(payd.pay_po_adj_amount) as pay_amt
                            FROM ret_po_payment as paymet 
                            LEFT JOIN ret_supplier_pay_po_details as payd ON payd.po_pay_id = paymet.pay_id 
                            GROUP BY pay_po_ref_id) as payment ON payment.pay_po_ref_id = po.po_id 
                            LEFT JOIN ret_karigar k ON k.id_karigar=po.po_karigar_id 
                            WHERE po.po_karigar_id ='".$karigar."' AND (ifnull(podet.item_cost,0) - ifnull(payment.pay_amt, 0)) > 0 ORDER BY po.po_id ASC");
        //echo $this->db->last_query();exit;
        $response_data = $sql->result_array();

        return $response_data;
        
	}
	
	function get_karigarPosPaidHistory($karigar){
	    $sql    = $this->db->query("SELECT pay.pay_id, date_format(pay.pay_create_on,'%d-%m-%Y') as pay_date, pay.pay_refno, 
	                            IFNULL(pay.pay_amt,0) as tot_cash_pay, IFNULL(pay.pay_wt,0) as tot_pay_wt, 
	                            ifnull(paydet.po_ref_no, '-') as po_ref_no , if(bill_type = 1, 'Purchase', 'Advance') as billtype, payamt  
	                            FROM ret_po_payment pay 
	                            LEFT JOIN(SELECT po_pay_id, sum(pay_po_adj_amount) as payamt, group_concat(p.po_ref_no) as  po_ref_no
	                                        FROM ret_supplier_pay_po_details as payd
	                                        LEFT JOIN ret_purchase_order p ON p.po_id = payd.pay_po_ref_id GROUP BY po_pay_id) as paydet ON paydet.po_pay_id = pay.pay_id  
	                            WHERE pay.pay_sup_id='".$karigar."' ORDER BY pay.pay_id DESC LIMIT 10");
        $response_data = $sql->result_array();
        //echo $this->db->last_query();exit;
        return $response_data;
	}
	
	function get_po_paid_detail($payid){
	    $return_data = array();
	    $payment_sql = $this->db->query("SELECT pay.pay_id, date_format(pay.pay_create_on,'%d-%m-%Y') as pay_date, pay.pay_refno, 
	                            IFNULL(pay.pay_amt,0) as tot_cash_pay,
	                             k.firstname as karigar, bill_type, 
	                             pay.pay_sup_id 
	                             FROM ret_po_payment pay 
	                              LEFT JOIN ret_karigar k ON k.id_karigar=pay.pay_sup_id 
	                            WHERE pay_id = '".$payid."'");
	   $return_data['paydetails'] = $payment_sql->row_array();
	   if($return_data['paydetails']['bill_type'] == 1){
	       $po_query = $this->db->query("SELECT po_pay_id, pay_po_adj_amount as payamt, p.po_ref_no as  po_ref_no
	                                        FROM ret_supplier_pay_po_details as payd
	                                        LEFT JOIN ret_purchase_order p ON p.po_id = payd.pay_po_ref_id 
	                                   WHERE po_pay_id = '".$payid."'");
	        $return_data['pay_po_details'] = $po_query->result_array();  
	   }
	   $paymode_query = $this->db->query("SELECT id_pay_details, pay_id, type, pay_mode, payment_amount, ref_no, bill_id FROM ret_po_payment_detail WHERE pay_id = '".$payid."'");
	   $return_data['pay_mode_details'] = $paymode_query->result_array();  
	   
	   return $return_data;
	   
	}
	
	
	function getPurchaseOrderDet($po_id)
	{
	    $sql=$this->db->query("SELECT p.po_id,p.purchase_type,p.po_type,p.is_suspense_stock,p.po_karigar_id,p.purchase_order_no,
        p.po_supplier_ref_no,p.po_ref_date,p.ewaybillno,p.despatch_through,p.id_category,p.id_purity,p.po_irnno,
        p.po_grn_id,g.grn_ref_no,k.firstname as supplier_name,IFNULL(k.contactno1,'') as mobile,IFNULL(k.gst_number,'') as gst_number,
        IFNULL(k.pan_no,'') as pan_no,cy.name as supplier_country_name,st.name as supplier_state_name,ct.name as supplier_city_name,
        IFNULL(k.address1,'') as supplier_address1,IFNULL(k.address2,'') as supplier_address2,
        IFNULL(k.address3,'') as supplier_address3,IFNULL(k.pincode,'') as supplier_pincode,st.state_code as supplier_state_code,p.po_ref_no,date_format(po_date,'%d-%m-%Y') as po_date,
        e.firstname as emp_name,if(g.grn_type=1,'GST PURCHASE',if(g.grn_type=2,'JOB RECEIPT','CHARGES')) as grn_type
        FROM ret_purchase_order p
        LEFT JOIN ret_grn_entry g on g.grn_id = p.po_grn_id
        LEFT JOIN ret_karigar k on k.id_karigar = p.po_karigar_id
        LEFT JOIN country cy ON cy.id_country = k.id_country
        LEFT JOIN state st ON st.id_state = k.id_state
        LEFT JOIN city ct ON ct.id_city = k.id_city
        LEFT JOIN employee e ON e.id_employee = p.created_by
        WHERE p.po_id=".$po_id."");
        return $sql->row_array();
	}
	
	function getPurchaseOrderItemDet($po_id)
	{
	    $returnData=array();
	    $sql=$this->db->query("SELECT p.po_item_id,p.po_item_cat_id,p.po_item_pro_id,p.po_item_des_id,p.po_item_sub_des_id,
        cat.name as category_name,pro.product_name,des.design_name,subDes.sub_design_name,
        p.po_purchase_mode,p.no_of_pcs,p.gross_wt,p.net_wt,p.cal_type,p.less_wt,p.item_pure_wt,
        p.purchase_touch,p.item_wastage,p.mc_type,p.mc_value,IFNULL(stn.stn_amt,0) as stn_amt,
        IFNULL(other_mt.oth_metal_amt,0) as oth_metal_amt,p.item_cost,p.fix_rate_per_grm,p.is_suspense_stock,p.is_halmarked,p.is_rate_fixed,
        p.po_order_no,p.id_purity,pur.purity as purity_name,ifnull(dia_stn.stn_wt,0) as dia_wt,pro.purchase_mode,p.total_tax,p.calculation_based_on
        FROM ret_purchase_order_items p 
        LEFT JOIN ret_purchase_order ord ON ord.po_id=p.po_item_po_id
        LEFT JOIN ret_category cat ON cat.id_ret_category=p.po_item_cat_id
        LEFT JOIN ret_purity pur ON pur.id_purity=p.id_purity
        LEFT JOIN ret_product_master pro ON pro.pro_id=p.po_item_pro_id
        LEFT JOIN ret_design_master des ON des.design_no=p.po_item_des_id
        LEFT JOIN ret_sub_design_master subDes ON subDes.id_sub_design=p.po_item_sub_des_id
        LEFT JOIN(SELECT s.po_item_id,SUM(s.po_stone_amount) as stn_amt
                 FROM ret_po_stone_items s
                 GROUP by s.po_item_id) as stn ON stn.po_item_id=p.po_item_id
        LEFT JOIN(SELECT i.po_item_id,SUM(i.po_other_item_amount) as oth_metal_amt
                 FROM ret_po_other_item i 
                 GROUP by i.po_item_id) as other_mt ON other_mt.po_item_id=p.po_item_id
        LEFT JOIN(SELECT po_stn.po_item_id,SUM(po_stn.po_stone_wt) as stn_wt 
            FROM ret_po_stone_items po_stn 
            LEFT JOIN ret_stone s ON s.stone_id=po_stn.po_stone_id
            LEFT JOIN ret_uom uom ON uom.uom_id=s.uom_id
            WHERE  uom.uom_short_code='CT'
            GROUP by po_stn.po_item_id) as dia_stn ON dia_stn.po_item_id=p.po_item_id
        WHERE p.po_item_po_id=".$po_id." and p.status=0 ");
        
        $result= $sql->result_array();
        foreach($result as $items)
        {
            $items['stn_details']=$this->getPurchaseStoneDetails($items['po_item_id']);                
            $items['other_metal_details']=$this->getPurchaseOtherMetalDetails($items['po_item_id']);                
            $items['other_charge_details']=$this->getPurchaseOtherChargeDetails($items['po_item_id']);                
            $returnData[]=$items;
        }
        
        return $returnData;
	}
	
	function getPurchaseOtherChargeDetails($po_item_id)
	{
	    $sql = $this->db->query("SELECT c.pur_othr_charge_value,c.calc_type,ch.name_charge,c.total_charge_value
        FROM ret_purchase_other_charges c
        LEFT JOIN ret_charges ch ON ch.id_charge = c.pur_othr_charge_id
        WHERE c.pur_po_item_id = ".$po_item_id." ");
        return $sql->result_array();
	}
	
	function get_purchase_order_item_det($po_id)
	{
	    $returnData=array();
	    $sql=$this->db->query("SELECT p.po_item_id,p.po_item_cat_id,p.po_item_pro_id,p.po_item_des_id,p.po_item_sub_des_id,
        cat.name as category_name,pro.product_name,des.design_name,subDes.sub_design_name,
        p.po_purchase_mode,p.no_of_pcs,p.gross_wt,p.net_wt,p.cal_type,p.less_wt,p.item_pure_wt,
        p.purchase_touch,p.item_wastage,p.mc_type,p.mc_value,IFNULL(stn.stn_amt,0) as stn_amt,
        IFNULL(other_mt.oth_metal_amt,0) as oth_metal_amt,p.item_cost,p.fix_rate_per_grm,p.is_suspense_stock,p.is_halmarked,p.is_rate_fixed,
        p.po_order_no,p.id_purity,pur.purity as purity_name,ifnull(dia_stn.stn_wt,0) as dia_wt,pro.purchase_mode,p.total_cgst,p.total_sgst,p.total_igst,p.tax_percentage,
        IFNULL(p.total_tax,0) as total_tax,IFNULL(p.remark,'') as remark
        FROM ret_purchase_order_items p 
        LEFT JOIN ret_purchase_order ord ON ord.po_id=p.po_item_po_id
        LEFT JOIN ret_category cat ON cat.id_ret_category=p.po_item_cat_id
        LEFT JOIN ret_purity pur ON pur.id_purity=p.id_purity
        LEFT JOIN ret_product_master pro ON pro.pro_id=p.po_item_pro_id
        LEFT JOIN ret_design_master des ON des.design_no=p.po_item_des_id
        LEFT JOIN ret_sub_design_master subDes ON subDes.id_sub_design=p.po_item_sub_des_id
        LEFT JOIN(SELECT s.po_item_id,SUM(s.po_stone_amount) as stn_amt
                 FROM ret_po_stone_items s
                 GROUP by s.po_item_id) as stn ON stn.po_item_id=p.po_item_id
        LEFT JOIN(SELECT i.po_item_id,SUM(i.po_other_item_amount) as oth_metal_amt
                 FROM ret_po_other_item i 
                 GROUP by i.po_item_id) as other_mt ON other_mt.po_item_id=p.po_item_id
        LEFT JOIN(SELECT po_stn.po_item_id,SUM(po_stn.po_stone_wt) as stn_wt 
            FROM ret_po_stone_items po_stn 
            LEFT JOIN ret_stone s ON s.stone_id=po_stn.po_stone_id
            LEFT JOIN ret_uom uom ON uom.uom_id=s.uom_id
            WHERE  uom.uom_short_code='CT'
            GROUP by po_stn.po_item_id) as dia_stn ON dia_stn.po_item_id=p.po_item_id
        WHERE p.po_item_po_id=".$po_id."");
        
        $result= $sql->result_array();
        foreach($result as $items)
        {
            $items['stn_details']=$this->getPurchaseStoneDetails($items['po_item_id']);                
            $items['other_metal_details']=$this->getPurchaseOtherMetalDetails($items['po_item_id']);
            $items['other_charge_details']=$this->getPurchaseOtherChargeDetails($items['po_item_id']); 
            $returnData[]=$items;
        }
        
        return $returnData;
	}
	
	function getPurchaseStoneDetails($po_item_id)
	{
	    $sql=$this->db->query("SELECT po_stn.po_st_id,po_stn.po_stone_id,po_stn.is_apply_in_lwt,po_stn.po_stone_pcs,po_stn.po_stone_wt,

        po_stn.po_stone_uom,po_stn.po_stone_calc_based_on,po_stn.po_stone_rate,po_stn.po_stone_amount,s.stone_type,s.stone_name,uom.uom_short_code
        
        FROM ret_po_stone_items po_stn 

        LEFT JOIN ret_stone s ON s.stone_id = po_stn.po_stone_id
        
        LEFT JOIN ret_uom uom on uom.uom_id=po_stn.po_stone_uom
        
        WHERE po_stn.po_item_id=".$po_item_id."");
	    
        return $sql->result_array();
	}
	
	function getPurchaseOtherMetalDetails($po_item_id)
	{
	    $sql=$this->db->query("SELECT * FROM ret_po_other_item WHERE po_item_id=".$po_item_id."");
	    return $sql->result_array();
	}
	
	function get_empty_record()
	{
	    $ho = $this->get_headOffice();
	    $supplier_bill_entry_calc_type = $this->get_ret_settings('supplier_bill_entry_calc');
	    $return_data=array(
	           'purchase_type'      =>2,
	           'po_type'            =>1,
	           'po_date'            =>NULL,
	           'po_karigar_id'      =>NULL,
	           'po_ref_no'          =>NULL,
	           'is_suspense_stock'  =>0,
	           'po_supplier_ref_no' =>NULL,
	           'po_ref_date'        =>NULL,
	           'ewaybillno'         =>NULL,
	           'po_irnno'           =>NULL,
	           'despatch_through'   =>2,
	           'id_category'        =>'',
	           'id_purity'          =>'',
	           'po_ref_date'        =>NULL,
	           'id_branch'          =>$ho['id_branch'],
	           'po_grn_id'          => NULL,
	           'supplier_bill_entry_calc' => $supplier_bill_entry_calc_type,
	           
	          );
	    return $return_data;
	          
	}
	
	function generate_grn_refno($grn_type)
	{
	    $lastno = NULL;
	    $sql = "SELECT (grn_ref_no) as last_grn_ref_no FROM ret_grn_entry Where grn_type=".$grn_type." ORDER BY grn_id DESC LIMIT 1";
			$result = $this->db->query($sql);
			if( $result->num_rows() > 0){
				$lastno = $result->row()->last_grn_ref_no;				
			} 
			
	    if($lastno != NULL)
		{ 
		    $code_det       = explode('-', $lastno);
            $number = (int) $code_det[1];
            $number++;
            $grn_ref_no = str_pad($number, 5, '0', STR_PAD_LEFT);	
		}
		else
		{
           $grn_ref_no = str_pad('1', 5, '0', STR_PAD_LEFT);
		}
		
		if($grn_type == 1){
		    return "PU-".$grn_ref_no;
		}else if($grn_type == 2){
		    return "PM-".$grn_ref_no;
		}else if($grn_type == 3){
		    return "PC-".$grn_ref_no;
		}
	}
	
	function ajax_getGrnentrydetails($from_date,$to_date)
	{
	    $sql = $this->db->query("SELECT grn.grn_id,grn.grn_karigar_id,date_format(grn.grn_date,'%d-%m-%Y') as grn_date,
        k.firstname as karigar_name,grn.grn_ref_no,grn.grn_irnno,grn.grn_ewaybillno,k.contactno1 as mobile,
        grn.grn_purchase_amt,if(grn.grn_bill_status=1,'Success','Cancelled') as billstatus,grn.grn_bill_status,
        IFNULL(grn.grn_supplier_ref_no,'') as grn_supplier_ref_no,IFNULL(date_format(grn.grn_ref_date,'%d-%m-%Y'),'-') as grn_ref_date
        FROM ret_grn_entry grn
        LEFT JOIN ret_karigar k ON k.id_karigar = grn.grn_karigar_id 
        WHERE (date(grn.grn_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')");
        return $sql->result_array();
	}
	
	function get_grn_details($id)
	{
	    $retrnData = array();
	    $sql = $this->db->query("SELECT grn.grn_id,grn.grn_karigar_id,date_format(grn.grn_date,'%d-%m-%Y') as grn_date, grn.grn_type, 
        k.firstname as karigar_name,grn.grn_ref_no,grn.grn_irnno,grn.grn_ewaybillno,k.contactno1 as mobile,
        grn.grn_purchase_amt,if(grn.grn_bill_status=1,'Success','Cancelled') as billstatus,grn.grn_bill_status,
        cy.name as supplier_country_name,st.name as supplier_state_name,ct.name as supplier_city_name,
        IFNULL(k.address1,'') as supplier_address1,IFNULL(k.address2,'') as supplier_address2,
        IFNULL(k.address3,'') as supplier_address3,IFNULL(k.pincode,'') as supplier_pincode,k.gst_number,k.email,k.contactno1 as supplier_mobile,
        IFNULL(grn.grn_tcs_percent,0) as grn_tcs_percent,IFNULL(grn.grn_tcs_value,0) as grn_tcs_value,IFNULL(grn.grn_pay_tds_percent,0) as grn_pay_tds_percent,
        IFNULL(grn.grn_pay_tds_value,0) as grn_pay_tds_value,IFNULL(grn.grn_round_off,0) as grn_round_off,IFNULL(grn.grn_supplier_ref_no,'') as grn_supplier_ref_no,
        IFNULL(date_format(grn.grn_ref_date,'%Y-%m-%d'),'') as grn_ref_date,IFNULL(grn.grn_other_charges_tds_percent,0) as grn_other_charges_tds_percent,
        IFNULL(grn.grn_other_charges_tds_value,0) as grn_other_charges_tds_value,grn.grn_despatch_through,IFNULL(k.pan_no,'') as pan_no,grn.grn_discount
        FROM ret_grn_entry grn
        LEFT JOIN ret_karigar k ON k.id_karigar = grn.grn_karigar_id
        LEFT JOIN country cy ON cy.id_country = k.id_country
        LEFT JOIN state st ON st.id_state = k.id_state
        LEFT JOIN city ct ON ct.id_city = k.id_city
        where grn.grn_id =".$id."");
        $retrnData = $sql->row_array();
        
        $item_gst_details = $this->get_grn_gst_details($retrnData['grn_id']);
        $charges_gst_details = $this->get_grn_charge_gst_details($retrnData['grn_id']);
        
        $retrnData['item_details'] = $this->get_grn_item_details($retrnData['grn_id']);
        $retrnData['charge_details'] = $this->get_grn_charge_details($retrnData['grn_id']);
        $retrnData['gst_details'] = $item_gst_details;
        $retrnData['charge_gst_details'] = $charges_gst_details;

        return $retrnData;
	}
	
	function get_grn_item_details($grn_id)
	{
	    $returnData = [];
	    $sql = $this->db->query("SELECT i.grn_item_id,i.grn_gross_wt,i.grn_no_of_pcs,i.grn_rate_per_grm,i.grn_net_wt,
	    i.grn_item_cost,i.grn_item_gst_rate,i.grn_item_cgst,i.grn_item_sgst,i.grn_item_igst,
        c.name as category_name,i.grn_item_grn_id,c.hsn_code,i.grn_item_gst_value, ifnull(i.grn_wastage , 0) as grn_wastage ,i.itemratecaltype,
        c.id_ret_category as cat_id
        FROM ret_grn_items i 
        LEFT JOIN ret_category c ON c.id_ret_category = i.grn_item_cat_id
        where i.grn_item_grn_id=".$grn_id."");
        $result = $sql->result_array();
        foreach($result as $items)
        {
            $items['stone_details'] = $this->get_grn_stone_details($items['grn_item_id']);
            $items['othermetal_details'] = $this->getGRNOthermetalDetails($items['grn_item_id']);
            $returnData[]=$items;
        }
        return $returnData;
	}
	

	function get_grn_stone_details($grn_item_id)
    {
    	    $sql = $this->db->query("SELECT st.is_apply_in_lwt,s.stone_type,st.stone_id,st.pieces,st.pieces,st.wt,uom.uom_id,
            uom.uom_name,s.stone_name,st.amount, st.rate_per_gram,st.stone_cal_type
            FROM ret_grn_item_stone st 
            LEFT JOIN ret_stone s ON s.stone_id = st.stone_id
            LEFT JOIN ret_uom uom on uom.uom_id=s.uom_id
            WHERE st.grn_item_id = ".$grn_item_id."");
            return $sql->result_array();
    }
	
	
	function get_grn_gst_details($grn_id)
    {
        $sql = $this->db->query("SELECT IFNULL(SUM(i.grn_item_cgst),0) as cgst_cost,IFNULL(SUM(i.grn_item_sgst),0) as sgst_cost,
        IFNULL(SUM(i.grn_item_igst),0) as igst_cost,i.grn_item_gst_value
        FROM ret_grn_items i 
        where i.grn_item_grn_id=".$grn_id."
        GROUP BY i.grn_item_gst_value");
        //print_r($this->db->last_query());exit;
        return $sql->result_array();
    }
	
	function get_grn_charge_details($grn_id)
	{
	    $sql = $this->db->query("SELECT ch.name_charge,c.grn_charge_value,c.char_with_tax,c.char_tax,c.cgst_cost,c.sgst_cost,c.igst_cost,
	    c.grn_charge_id
        FROM ret_grn_other_charges c 
        LEFT JOIN ret_charges ch ON ch.id_charge = c.grn_charge_id
        WHERE c.grn_id =".$grn_id."");
        return $sql->result_array();
	}
	
	function get_grn_charge_gst_details($grn_id)
	{
	    $sql = $this->db->query("SELECT IFNULL((c.char_tax),0) as grn_item_gst_value,
	    IFNULL(SUM(c.cgst_cost),0) as cgst_cost,IFNULL(SUM(c.sgst_cost),0) as sgst_cost,IFNULL(SUM(c.igst_cost),0) as igst_cost,c.grn_charge_id
        FROM ret_grn_other_charges c 
        LEFT JOIN ret_charges ch ON ch.id_charge = c.grn_charge_id
        WHERE c.grn_id =".$grn_id."
        GROUP BY c.char_tax");
        return $sql->result_array();
	}
	
	function getActiveGRNsDetails($data)
    {
        $grnentries = array();
        if($data['po_id']!='')
        {
            $sql = $this->db->query("SELECT grn.grn_id, grn_ref_no, grn_type, grn_karigar_id, grn_supplier_ref_no, grn_ref_date, grn_ewaybillno, 
            grn_despatch_through, grn_irnno, grn_other_charges, grn_discount, grn_purchase_wt, grn_purchase_amt, 
            grn_pay_tds_percent, grn_pay_tds_value, grn_other_charges_tds_percent, grn_other_charges_tds_value, 
            grn_tcs_percent, grn_tcs_value, grn_round_off,IFNULL(ch.charges_amount,0) as charges_amount,IFNULL(ch.charges_tax,0) as charges_tax
            FROM `ret_grn_entry` as grn
            LEFT JOIN (SELECT c.grn_id,IFNULL(SUM(c.grn_charge_value),0) as charges_amount,
                        IFNULL(SUM(c.total_tax),0) as charges_tax
                        FROM ret_grn_other_charges c 
                        GROUP BY c.grn_id) as ch ON ch.grn_id = grn.grn_id
            WHERE grn_bill_status = 1 AND grn.grn_type != 3 ");
            //print_r($this->db->last_query());exit;
        }
        else
        {
            $sql = $this->db->query("SELECT grn.grn_id, grn_ref_no, grn_type, grn_karigar_id, grn_supplier_ref_no, grn_ref_date, grn_ewaybillno, 
            grn_despatch_through, grn_irnno, grn_other_charges, grn_discount, grn_purchase_wt, grn_purchase_amt, 
            grn_pay_tds_percent, grn_pay_tds_value, grn_other_charges_tds_percent, grn_other_charges_tds_value, 
            grn_tcs_percent, grn_tcs_value, grn_round_off,IFNULL(ch.charges_amount,0) as charges_amount,IFNULL(ch.charges_tax,0) as charges_tax
            FROM `ret_grn_entry` as grn
            LEFT JOIN (SELECT c.grn_id,IFNULL(SUM(c.grn_charge_value),0) as charges_amount,
                        IFNULL(SUM(c.total_tax),0) as charges_tax
                        FROM ret_grn_other_charges c 
                        GROUP BY c.grn_id) as ch ON ch.grn_id = grn.grn_id
            WHERE grn_bill_status = 1 AND grn.grn_type != 3 AND grn.grn_id NOT IN (SELECT ifnull(po_grn_id, '') FROM ret_purchase_order)");
        }
        return $sql->result_array();
    }
	
	function getGRNsCatDetailsbyGRNId($postdata)
	{
	    $responsedata = array();
	    $grncatdetails = $this->db->query("SELECT grn.grn_id , grn_item_id, grn_item_grn_id, grn_item_is_order, grn_item_cat_id, grn_gross_wt, grn_less_wt, grn_net_wt, 
	                                        grn_no_of_pcs, grn_rate_per_grm, itemratecaltype, grn_item_cost, grn_item_gst_rate, grn_item_gst_value, 
	                                        cat.name as catname, cat.cat_type 
	                                        FROM ret_grn_entry as grn 
	                                        LEFT JOIN `ret_grn_items` as grnitm ON grnitm.grn_item_grn_id =  grn.grn_id 
	                                        LEFT JOIN ret_category as cat ON cat.id_ret_category = grnitm.grn_item_cat_id 
	                                        WHERE grn.grn_id = '".$postdata['grnId']."' GROUP BY grnitm.grn_item_id");
	    foreach($grncatdetails->result() as $row){
	        $responsedata[] = array(
	                            "grn_item_id"   => $row->grn_item_id,
	                            "grn_id"        => $row->grn_item_grn_id,
	                            "is_order"      => $row->grn_item_is_order,
	                            "cat_id"        => $row->grn_item_cat_id,
	                            "gross_wt"      => $row->grn_gross_wt,
	                            "grn_less_wt"   => $row->grn_less_wt,
	                            "grn_net_wt"    => $row->grn_net_wt,
	                            "no_of_pcs"     => $row->grn_no_of_pcs,
	                            "cat_type"      => $row->cat_type,
	                            "rate_per_grm"  => $row->grn_rate_per_grm,
	                            "cal_type"      => $row->itemratecaltype,
	                            "item_cost"     => $row->grn_item_cost,
	                            "gst_rate"      => $row->grn_item_gst_rate,
	                            "item_value"    => $row->grn_item_gst_value,
	                            "catname"       => $row->catname,
	                            "stone_detail"  => $this->getGRNCategoryStonedetails($row->grn_item_id), 
	                            "om_detail"     => $this->getGRNOthermetalDetails($row->grn_item_id), 
	                            "charge_detail" => $this->getGRNOtherchargeDetails($row->grn_id), 
	                       );
	    } 
	    return $responsedata;
	}
	function getGRNCategoryStonedetails($grncatId)
	{
	    $stonequery = $this->db->query("SELECT grnst.grn_item_id, grnst.stone_id, grnst.pieces, grnst.wt, st.uom_id, grnst.rate_per_gram, grnst.amount, 
	                                    grnst.is_apply_in_lwt, grnst.stone_cal_type,
	                                    st.stone_type, st.stone_name 
	                                    FROM `ret_grn_item_stone` as grnst 
	                                    LEFT JOIN ret_stone as st ON st.stone_id = grnst.stone_id 
	                                    WHERE grnst.grn_item_id = '".$grncatId."'");
	   return $stonequery->result_array();
	}
	function getGRNOthermetalDetails($grncatId)
	{
	    $othermetalquery = $this->db->query("SELECT grnom.grn_other_itm_id,  grnom.grn_itms_id, grnom.grn_other_itm_metal_id, grnom.grn_other_itm_pur_id, 
	                                        grnom.grn_other_itm_grs_weight, grnom.grn_other_itm_wastage, grnom.grn_other_itm_mc, grnom.grn_other_itm_uom, 
	                                        grnom.grn_other_itm_cal_type, grnom.grn_other_itm_pcs, grnom.grn_other_itm_rate, grnom.grn_other_itm_amount, 
	                                        cat.name as omcatname, cat.id_ret_category as omcatid 
	                                        FROM `ret_grn_other_metals` as grnom 
	                                        LEFT JOIN ret_category as cat ON cat.id_ret_category = grnom.grn_other_itm_metal_id 
	                                        WHERE grnom.grn_itms_id = '".$grncatId."'");
	    return $othermetalquery->result_array();
	}
	
	function getGRNOtherchargeDetails($grnId)
	{
	    $otherchargequery = $this->db->query("SELECT grncrg.grn_other_charge_id, grncrg.grn_charge_id, grncrg.grn_id,  grncrg.grn_charge_value, 
	                                        grncrg.char_with_tax, grncrg.char_tax, 
	                                        grncrg.total_tax, chr.code_charge, chr.name_charge 
	                                        FROM `ret_grn_other_charges` as grncrg 
	                                        LEFT JOIN ret_charges as chr ON chr.id_charge = grncrg.grn_charge_id 
	                                        WHERE grncrg.grn_id = '".$grncatId."'");
	    return $otherchargequery->result_array();
	}
	
	
	function get_rate_fixing_po_no($data)
	{
            $sql = $this->db->query("SELECT p.po_ref_no,p.po_id,p.po_karigar_id,p.tot_purchase_wt,
            IFNULL(ratefix.fixed_wt,0) as total_fixed_wt,
            date_format(p.po_date,'%d-%m-%Y') as podate,IFNULL(po_ret.pur_ret_pur_wt,0) as ret_pure_wt
            FROM ret_purchase_order p
            LEFT JOIN(SELECT IFNULL(SUM(f.rate_fix_wt),0) as fixed_wt,f.rate_fix_po_item_id
            FROM ret_po_rate_fix f 
            GROUP BY f.rate_fix_po_item_id) as ratefix ON ratefix.rate_fix_po_item_id = p.po_id
            LEFT JOIN(SELECT IFNULL(SUM(r.pur_ret_pur_wt),0) as pur_ret_pur_wt,p.po_id
                FROM ret_purchase_return_items r 
                LEFT JOIN ret_purchase_return ret ON ret.pur_return_id = r.pur_ret_id
                LEFT JOIN ret_purchase_order_items itm ON itm.po_item_id = r.pur_ret_po_item_id
                LEFT JOIN ret_purchase_order p ON p.po_id = itm.po_item_po_id
                WHERE ret.bill_status = 1
            GROUP BY p.po_id) as po_ret ON po_ret.po_id = p.po_id
            WHERE p.isratefixed = 0 AND p.tot_purchase_wt > 0 
            ".($data['id_karigar']!='' ? " AND p.po_karigar_id=".$data['id_karigar']."" :'')."
            HAVING tot_purchase_wt > total_fixed_wt ");
            $result = $sql->result_array();
        return $result;
	}
	
	
	function get_purchase_order_status($data)
	{
	    $sql = $this->db->query("SELECT c.id_customerorder,c.pur_no,d.id_product,d.design_no,d.id_sub_design,IFNULL(SUM(d.totalitems),0) as orderpcs,
        p.product_name,des.design_name,subDes.sub_design_name,IFNULL(rcd.recd_pcs,0) as received_pcs,IFNULL(rcd.recd_gross_wt,0) as received_weight,IFNULL(rcd.po_ref_no,'') as po_ref_no,IFNULL(cusOrd.order_no,'') as cus_ord_ref,k.firstname as karigar_name,
        date_format(c.order_date,'%d-%m-%Y') as orderdate,rcd.po_item_po_id,cusOrd.id_customerorder as cusOrdid, date_format(d.smith_due_date,'%d-%m-%Y') as due_date
        FROM customerorderdetails d 
        LEFT JOIN customerorder c ON c.id_customerorder = d.id_customerorder
        LEFT JOIN ret_product_master p ON p.pro_id = d.id_product
        LEFT JOIN ret_design_master des ON des.design_no = d.design_no
        LEFT JOIN ret_sub_design_master subDes ON subDes.id_sub_design = d.id_sub_design
        LEFT JOIN customerorder cusOrd ON cusOrd.id_customerorder = c.cus_ord_ref
        LEFT JOIN ret_karigar k ON k.id_karigar = c.id_karigar
        LEFT JOIN(SELECT i.po_item_po_id,GROUP_CONCAT(po.po_ref_no) as po_ref_no,i.po_order_no,IFNULL(SUM(i.no_of_pcs),0) as recd_pcs,IFNULL(SUM(i.gross_wt),0) as recd_gross_wt,i.po_item_pro_id,i.po_item_des_id,i.po_item_sub_des_id
        FROM ret_purchase_order_items i
        LEFT JOIN ret_purchase_order po ON po.po_id = i.po_item_po_id
        GROUP BY i.po_item_pro_id,i.po_item_des_id,i.po_item_sub_des_id,i.po_order_no) as rcd ON rcd.po_order_no = d.id_customerorder AND rcd.po_item_pro_id = d.id_product AND rcd.po_item_des_id = d.design_no AND rcd.po_item_sub_des_id = d.id_sub_design
        WHERE c.order_type = 1 AND c.pur_no IS NOT NULL AND d.id_product IS NOT NULL AND d.design_no IS NOT NULL and d.id_sub_design IS NOT NULL
        ".($data['id_karigar']!='' ? " AND c.id_karigar=".$data['id_karigar']."" :'')."
        ".($data['id_product']!='' ? " and d.id_product=".$data['id_product']."" :'')."
        ".($data['id_design']!='' ? " and d.design_no=".$data['id_design']."" :'')."
        ".($data['id_sub_design']!='' ? " and d.id_sub_design=".$data['id_sub_design']."" :'')."
        ".($data['from_date']!='' && $data['to_date']!='' ? " and (date(c.order_date) BETWEEN '".date('Y-m-d',strtotime($data['from_date']))."' AND '".date('Y-m-d',strtotime($data['to_date']))."')" :'')."
         ".($data['order_for']!='' ? "and c.order_for=".$data['order_for']."":'')."

        GROUP BY d.id_customerorder,d.id_product,d.design_no,d.id_sub_design");
        
        return $sql->result_array();
	}
	
	
	
	function get_po_payment($id)
    {
        $po_data=array();

        $sql= $this->db->query("SELECT p.pay_id,p.pay_sup_id,
        
        p.pay_refno,p.pay_amt,p.pay_narration,p.pay_status,p.bill_type,

        kar.firstname as karigar,IFNULL(SUM(pl.debit),0)-IFNULL(SUM(pl.credit),0) as balance_amount
        
        FROM ret_po_payment p
                
        LEFT JOIN ret_karigar kar on kar.id_karigar=p.pay_sup_id

        LEFT JOIN ret_view_grn_pay_ledger pl on pl.sup_id=p.pay_sup_id
        
        WHERE p.pay_id=".$id."");

        //print_r($this->db->last_query());exit;

        $po_pay = $sql->result_array();

        foreach($po_pay as $val)
        {
            $po_data[]=array(
                'pay_id' => $val['pay_id'],

                'pay_sup_id' => $val['pay_sup_id'],

                'bill_type' => $val['bill_type'],

                'pay_amt' => $val['pay_amt'],

                'pay_refno' => $val['pay_refno'],

                'pay_narration'  => $val['pay_narration'],

                'kargiar_name'   => $val['karigar'],

                'balance_amount'  => $val['balance_amount'],

                'po_payment_details' => $this->get_po_paymentDetails($val['pay_id']),



            );

        }

        return $po_data;
        
    }


    


   function get_po_paymentDetails($pay_id)
    {
        $sql=$this->db->query("SELECT pd.id_pay_details,pd.pay_id,pd.type,
        
        pd.pay_mode,pd.payment_amount,
        
        pd.ref_no,pd.id_bank,date_format(pd.ref_date,'%Y-%m-%d') as payment_date

        FROM ret_po_payment_detail pd
        
        Where pd.pay_id=".$pay_id."");

        //print_r($this->db->last_query());exit;


        return $sql->result_array();
    }
    
    
    
    function get_approval_stock_tags($data)
    {
        $sql = $this->db->query("SELECT r.po_id,r.po_ref_no,r.po_karigar_id,date_format(r.po_date,'%d-%m-%Y') as podate,
        t.tag_id,t.tag_code,t.piece,t.gross_wt,t.net_wt,IFNULL(cusor.pur_no,'') as order_no,IFNULL(cusor.order_status,'') as order_status,m.order_status as statusmessage,br.name as current_branchname,sup.firstname as suppliername,
        t.tag_status,t.is_approval_stock_converted,IFNULL(date_format(t.app_stk_converted_date,'%d-%m-%Y'),'') as converted_date
        FROM ret_purchase_order r
        LEFT JOIN ret_lot_inwards l ON l.po_id = r.po_id
        LEFT JOIN ret_taging t ON t.tag_lot_id = l.lot_no
        LEFT JOIN ret_product_master p ON p.pro_id = t.product_id
        LEFT JOIN ret_category c ON c.id_ret_category = p.cat_id
        LEFT JOIN customerorderdetails as ordet ON ordet.approval_tagid = t.tag_id 
        LEFT JOIN customerorder as cusor ON cusor.id_customerorder = ordet.id_customerorder 
        LEFT JOIN order_status_message m ON m.id_order_msg=cusor.order_status
        LEFT JOIN ret_karigar as sup ON sup.id_karigar = r.po_karigar_id
        LEFT JOIN branch as br ON br.id_branch = t.current_branch
        WHERE r.is_suspense_stock =1 AND t.tag_type = 1 AND t.tag_status = 0
        ".($data['order_status']==0 ? " AND (cusor.order_status = ".$data['order_status']." OR cusor.order_status IS NULL)" :" AND cusor.order_status = ".$data['order_status']."")." 
        ".($data['bill_no']!='' ? " and r.po_ref_no='".$data['bill_no']."'" :'')." 
        ");
        return $sql->result_array();
    }
    
    function get_approval_tag_details($tag_id)
    {
        $sql = $this->db->query("SELECT * FROM ret_taging WHERE tag_id = '".$tag_id."' ");
        return $sql->row_array();
    }
    
    
    function  get_purchaseReturn_item_details($pur_ret_id)
    
    {
        $tag_details=$this->db->query("SELECT pur_itms.pur_ret_itm_id,pur_itms.pur_ret_pcs as pcs,pur_itms.return_item_type as type,
        
        pur_itms.pur_ret_gwt as grs_wt,tag.tag_code,tag.product_id,p.product_name,pty.purity
        
        FROM ret_taging tag  
        
        LEFT JOIN ret_purchase_return_items pur_itms on pur_itms.tag_id=tag.tag_id
        
        LEFT JOIN ret_product_master p on p.pro_id=tag.product_id
        LEFT JOIN ret_purity pty ON pty.id_purity = tag.purity
        WHERE pur_itms.tag_id is not null 
    
        and pur_itms.return_item_type=2
        
        and pur_itms.pur_ret_id=".$pur_ret_id."");
    
        $result['tag_details'] = $tag_details->result_array();
    
    
    
        $non_tag_details=$this->db->query("SELECT pur_itm.pur_ret_itm_id ,pur_itm.pur_ret_id,pur_itm.return_item_type as type,                
        
        pur_itm.pur_ret_pcs as pcs,pur_itm.pur_ret_gwt as grs_wt,pro.product_name,'' as purity
        
        FROM ret_purchase_return_items pur_itm
        
        LEFT JOIN ret_product_master pro on pro.pro_id=pur_itm.id_product
        
        WHERE pur_itm.pur_ret_id=".$pur_ret_id." and pur_itm.return_item_type=3 ");
    
        $result['nt_details'] = $non_tag_details->result_array();
    
    
        $po_details=$this->db->query("SELECT pr_itms.pur_ret_itm_id,po_itms.po_item_pro_id,
        
        pr_itms.pur_ret_pcs as pcs,pr_itms.pur_ret_gwt as grs_wt,p.product_name,pr_itms.pur_ret_pcs,
        
        pr_itms.pur_ret_gwt as gross_wt,pr_itms.pur_ret_nwt as net_wt,IFNULL(pr_itms.pur_ret_lwt,0) as less_wt,IFNULL(pr_itms.pur_ret_pur_wt,0) as pure_wt,
        
        IFNULL(pr_itms.pur_ret_wastage,0) as wastage,IFNULL(pr_itms.pur_ret_mc_value,0) as mc_value,pty.purity
        
        FROM ret_purchase_order_items po_itms
        
        LEFT JOIN ret_purchase_return_items pr_itms on pr_itms.pur_ret_po_item_id=po_itms.po_item_id
        
        LEFT JOIN ret_product_master p on p.pro_id=po_itms.po_item_pro_id
        LEFT JOIN ret_purity pty ON pty.id_purity = po_itms.id_purity
        WHERE pr_itms.pur_ret_po_item_id is not null 
    
        and pr_itms.return_item_type=1
        
        and pr_itms.pur_ret_id=".$pur_ret_id."");
    
        $result['po_details'] = $po_details->result_array();
    
    
        return $result;
    
    
    }
    
    
    //lot generate
    function get_qc_receipt_lot()
    {
        $sql = $this->db->query("SELECT pr.po_ref_no,pr.po_id
        FROM ret_po_qc_issue_details d 
        LEFT JOIN ret_po_qc_issue_process p ON p.qc_process_id = d.qc_process_id
        LEFT JOIN ret_purchase_order_items r ON r.po_item_id = d.po_item_id
        LEFT JOIN ret_purchase_order pr ON pr.po_id = r.po_item_po_id
        WHERE d.status = 1 AND d.is_lot_created = 0 AND r.is_halmarked = 1
        GROUP BY pr.po_id");
        return $sql->result_array();
    }
    
    function get_lot_generate_item_details($data)
    {
        
        $returnData=[];
        $sql = $this->db->query("SELECT d.id_qc_issue_details,pr.po_ref_no,pr.po_id,pro.product_name,IFNULL(SUM(d.passed_pcs),0) as pcs,IFNULL(SUM(d.passed_gwt),0) as gross_wt,IFNULL(SUM(d.passed_nwt),0) as net_wt,pur.purity,pro.cat_id,c.name as cat_name,
        if(pro.stock_type=1,'Tagged Items','Non Tagged Items') as stock_type,IFNULL(SUM(d.passed_lwt),0) as less_wt,pro.stock_type as stktype,r.id_purity,des.design_name,subDes.sub_design_name,pro.pro_id,r.po_item_des_id,r.po_item_sub_des_id
        FROM ret_po_qc_issue_details d 
        LEFT JOIN ret_po_qc_issue_process p ON p.qc_process_id = d.qc_process_id
        LEFT JOIN ret_purchase_order_items r ON r.po_item_id = d.po_item_id
        LEFT JOIN ret_purchase_order pr ON pr.po_id = r.po_item_po_id
        left join ret_product_master pro ON pro.pro_id = r.po_item_pro_id
        LEFT JOIN ret_design_master des ON des.design_no = r.po_item_des_id
        LEFT JOIN ret_sub_design_master subDes ON subDes.id_sub_design = r.po_item_sub_des_id
        LEFT JOIN ret_category c ON c.id_ret_category = pro.cat_id
        LEFT JOIN ret_purity pur ON pur.id_purity = r.id_purity
        WHERE d.status = 1 AND d.is_lot_created = 0
        ".($data['po_id']!='' ? " and pr.po_id=".$data['po_id']."" :'')."
        GROUP BY d.id_qc_issue_details");
        $result = $sql->result_array();
       // print_r($this->db->last_query());exit;
        foreach($result as $val){
            $val['stone_details'] = $this->get_qc_stone_accepted_details($val['id_qc_issue_details']);
            $returnData[]=$val;
        }
        return $returnData;
    }
    
    function get_qc_stone_accepted_details($id_qc_issue_details){
        $sql = $this->db->query("SELECT s.qc_passed_pcs,s.qc_passed_wt,st.stone_name,m.uom_name,m.uom_id,s.po_st_id as stone_id
        FROM ret_po_qc_issue_stone_details s
        LEFT JOIN ret_po_stone_items i ON i.po_st_id = s.po_st_id
        LEFT JOIN ret_stone st ON st.stone_id = i.po_stone_id
        LEFT JOIN ret_uom m ON m.uom_id = i.po_stone_uom
        WHERE s.id_qc_issue_details = ".$id_qc_issue_details."
        HAVING s.qc_passed_pcs > 0");
        return $sql->result_array();
    }
    
    	function checkLotItemExist($data)
    	{
    		$r = array("status" => FALSE);
    		$sql = "SELECT id_lot_inward_detail FROM ret_lot_inwards_detail WHERE lot_product=".$data['id_product']." and lot_no=".$data['lot_no']; 
            $res = $this->db->query($sql);
    		if($res->num_rows() > 0){
    			$r = array("status" => TRUE, "id_lot_inward_detail" => $res->row()->id_lot_inward_detail); 
    		}else{
    			$r = array("status" => FALSE, "id_lot_inward_detail" => ""); 
    		} 
    		return $r;
    	}
	
	
	function update_lot_data($data,$arith){ 
		$sql = "UPDATE ret_lot_inwards_detail SET no_of_piece=(IFNULL(no_of_piece,0)".$arith." ".$data['no_of_piece']."),gross_wt=(gross_wt".$arith." ".$data['gross_wt']."),net_wt=(net_wt".$arith." ".$data['net_wt']."),less_wt=(less_wt".$arith." ".$data['less_wt'].") WHERE id_lot_inward_detail=".$data['id_lot_inward_detail'];  
		$status = $this->db->query($sql);
		return $status;
	}
    
    
    function get_po_details($po_id)
    {
        $sql = $this->db->query("SELECT * FROM ret_purchase_order WHERE po_id =".$po_id."");
        return $sql->row_array();
    }
    
    
  
    //lot generate
    
    function get_supplier_rate_cut_details($data)
    {

        if($data['dt_range'] != ''){
            $dateRange = explode('-',$_POST['dt_range']);
            $from = str_replace('/','-',$dateRange[0]);
            $to = str_replace('/','-',$dateRange[1]);  
            $d1 = date_create($from);
            $d2 = date_create($to);
            $FromDt = date_format($d1,"Y-m-d");
            $ToDt = date_format($d2,"Y-m-d");
            }

        $data= $this->db->query("SELECT date_format(src.date_add,'%d-%m-%Y') as date_add,src.id_supplier_rate_cut,b.name as branch_name,k.firstname as firstname, 
            IF(src.rate_cut_type=1,'AMOUNT TO WEIGHT','WEIGHT TO AMOUNT') as rate_cut_type,
            c.metal as cat_name,src.amount as amount,src.rate_per_gram,src.weight,src.status
        FROM ret_supplier_rate_cut src
        LEFT JOIN branch b ON b.id_branch = src.id_branch
        LEFT JOIN ret_karigar k ON k.id_karigar = src.id_karigar
        LEFT JOIN metal c ON c.id_metal =  src.id_metal
        WHERE id_supplier_rate_cut IS NOT NULL   
        ".($data['id_branch']!='' ? " and id_branch=".$data['id_branch']."" :'')." 
         ".($FromDt != '' && $ToDt != '' ? ' and date(src.date_add) BETWEEN "'.$FromDt.'" AND "'.$ToDt.'"' : '')."
         Order by src.id_supplier_rate_cut DESC");

    
        return $data->result_array();

    }
    
    
    function get_headoffice_valut_report($data)
    {
        $sql = $this->db->query("SELECT p.po_ref_no,date_format(p.po_date,'%d-%m-%Y') as podate,k.firstname as karigar_name,cat.name as category_name,pro.product_name,
        IFNULL(SUM(itm.gross_wt),0) as inw_gwt,IFNULL(SUM(itm.net_wt),0) as inw_nwt,IFNULL(SUM(itm.no_of_pcs),0) as inw_pcs,
        IFNULL((stndet.po_stone_wt),0) as inw_stn_wt,
        IFNULL(ret.pur_ret_pcs,0) as pur_ret_pcs,IFNULL(ret.pur_ret_gwt,0) as pur_ret_gwt,IFNULL(ret.pur_ret_nwt,0) as pur_ret_nwt,IFNULL(ret_stn.ret_stone_wt,0) as ret_stone_wt,IFNULL(ret_stn.ret_stone_pcs,0) as ret_stone_pcs,
        IFNULL(lot.lot_pcs,0) as lot_pcs,IFNULL(lot.gross_wt,0) as lot_gwt,IFNULL(lot.net_wt,0) as lot_nwt,IFNULL(lot_stn.stone_pcs,0) as lot_stn_pcs,IFNULL(lot_stn.stone_wt,0) as lot_stn_wt,
        IFNULL(tagdet.tag_pcs,0) as tag_pcs,IFNULL(tagdet.tag_gwt,0) as tag_gwt,IFNULL(tagdet.tag_lwt,0) as tag_lwt,IFNULL(tagdet.tag_nwt,0) as tag_nwt,IFNULL(tag_stn.stn_wt,0) as tag_stn_wt,
        (IFNULL(SUM(itm.gross_wt),0)-(IFNULL(ret.pur_ret_gwt,0)-(IFNULL(lot.gross_wt,0))-(IFNULL(tagdet.tag_gwt,0)))) as blc_gwt,
        (IFNULL(SUM(itm.net_wt),0)-(IFNULL(ret.pur_ret_nwt,0))-(IFNULL(lot.net_wt,0)-(IFNULL(tagdet.tag_nwt,0)))) as blc_nwt,
        (IFNULL(SUM(itm.no_of_pcs),0)-IFNULL(ret.pur_ret_pcs,0)-IFNULL(lot.lot_pcs,0)-IFNULL(tagdet.tag_pcs,0)) as blc_pcs
        FROM ret_purchase_order p
        LEFT JOIN ret_grn_entry g ON g.grn_id = p.po_grn_id
        LEFT JOIN ret_karigar k ON k.id_karigar = p.po_karigar_id
        LEFT JOIN ret_purchase_order_items itm ON itm.po_item_po_id = p.po_id
        LEFT JOIN ret_product_master pro ON pro.pro_id = itm.po_item_pro_id
        LEFT JOIN ret_category cat ON cat.id_ret_category = itm.po_item_cat_id
        
        LEFT JOIN(SELECT IFNULL(SUM(s.po_stone_wt),0) as po_stone_wt,stn_itm.po_item_po_id,stn_itm.po_item_pro_id
            FROM ret_po_stone_items s
            LEFT JOIN ret_stone stn ON stn.stone_id = s.po_stone_id
            LEFT JOIN ret_purchase_order_items stn_itm ON stn_itm.po_item_id = s.po_item_id
            LEFT JOIN ret_uom m on m.uom_id = s.po_stone_uom
            WHERE stn.stone_type = 1
            GROUP BY stn_itm.po_item_po_id,stn_itm.po_item_pro_id) as stndet ON stndet.po_item_po_id = p.po_id AND stndet.po_item_pro_id = itm.po_item_pro_id
            
        LEFT JOIN(SELECT itm.po_item_po_id,itm.po_item_pro_id,IFNULL(SUM(r.pur_ret_pcs),0) as pur_ret_pcs,
        IFNULL(SUM(r.pur_ret_gwt),0) as pur_ret_gwt,IFNULL(SUM(r.pur_ret_nwt),0) as pur_ret_nwt
        FROM ret_purchase_return_items r
        LEFT JOIN ret_purchase_order_items itm ON itm.po_item_id = r.pur_ret_po_item_id
        LEFT JOIN ret_purchase_order po ON po.po_id = itm.po_item_po_id
        LEFT JOIN ret_purchase_return rtm ON rtm.pur_return_id = r.pur_ret_id
        WHERE rtm.bill_status = 1
        GROUP BY itm.po_item_po_id,itm.po_item_pro_id) as ret ON ret.po_item_po_id = p.po_id AND ret.po_item_pro_id = itm.po_item_pro_id
        
        LEFT JOIN (SELECT IFNULL(SUM(s.ret_stone_wt),0) as ret_stone_wt,IFNULL(SUM(s.ret_stone_pcs),0) as ret_stone_pcs,r.id_product,p.po_id
        FROM ret_purchase_return_stone_items s 
        LEFT JOIN ret_purchase_return_items r ON r.pur_ret_itm_id = s.pur_ret_return_id
        LEFT JOIN ret_purchase_return ret ON ret.pur_return_id = r.pur_ret_id
        LEFT JOIN ret_purchase_order_items itm ON itm.po_item_id = r.pur_ret_po_item_id
        LEFT JOIN ret_purchase_order p ON p.po_id = itm.po_item_po_id
        LEFT JOIN ret_stone st ON st.stone_id = s.ret_stone_id
        WHERE ret.bill_status = 1 AND st.stone_type = 1
        GROUP BY r.id_product,p.po_id ) as ret_stn ON ret_stn.po_id = p.po_id AND ret_stn.id_product = itm.po_item_pro_id
        
        LEFT JOIN(SELECT IFNULL(SUM(d.no_of_piece),0) as lot_pcs,IFNULL(SUM(d.gross_wt),0) as gross_wt,IFNULL(SUM(d.net_wt),0) as net_wt,d.lot_product,l.po_id
        FROM ret_lot_inwards l
        LEFT JOIN ret_lot_inwards_detail d ON d.lot_no = l.lot_no
        GROUP BY d.lot_product,l.po_id) as lot ON lot.lot_product = itm.po_item_pro_id AND lot.po_id = p.po_id
        
        LEFT JOIN (SELECT IFNULL(SUM(d.stone_pcs),0) as stone_pcs,IFNULL(SUM(d.stone_wt),0) as stone_wt,det.lot_product,p.po_id
        FROM ret_lot_inwards_stone_detail d 
        LEFT JOIN ret_lot_inwards_detail det ON det.id_lot_inward_detail = d.id_lot_inward_detail
        LEFT JOIN ret_lot_inwards l ON l.lot_no = det.lot_no
        LEFT JOIN ret_purchase_order p ON p.po_id = l.po_id
        LEFT JOIN ret_stone st ON st.stone_id = d.stone_id
        WHERE p.bill_status = 1 AND st.stone_type = 1
        GROUP BY det.lot_product,p.po_id) as lot_stn ON lot_stn.lot_product = itm.po_item_pro_id AND lot_stn.po_id = p.po_id
        
        LEFT JOIN(SELECT tag.product_id,l.po_id,IFNULL(SUM(tag.piece),0) as tag_pcs,IFNULL(SUM(tag.gross_wt),0) as tag_gwt,
        IFNULL(SUM(tag.less_wt),0) as tag_lwt,IFNULL(SUM(tag.net_wt),0) as tag_nwt
        FROM ret_lot_inwards_detail d
        LEFT JOIN ret_lot_inwards l ON l.lot_no = d.lot_no
        LEFT JOIN ret_taging tag ON tag.tag_lot_id = l.lot_no
        GROUP BY tag.product_id,l.po_id) as tagdet ON tagdet.product_id = itm.po_item_pro_id = tagdet.po_id = p.po_id
        
        LEFT JOIN(SELECT IFNULL(SUM(ts.wt),0) as stn_wt,IFNULL(SUM(ts.pieces),0) as stn_pcs,t.product_id,p.po_id
        FROM ret_taging_stone ts 
        LEFT JOIN ret_taging t ON t.tag_id = ts.tag_id
        LEFT JOIN ret_lot_inwards l ON l.lot_no = t.tag_lot_id
        LEFT JOIN ret_purchase_order p ON p.po_id = l.po_id
        LEFT JOIN ret_stone st ON st.stone_id = ts.stone_id
        WHERE p.bill_status = 1 AND st.stone_type = 1
        GROUP BY t.product_id,p.po_id) as tag_stn ON tag_stn.product_id = itm.po_item_pro_id AND tag_stn.po_id = p.po_id
        
        WHERE p.bill_status = 1
        ".($data['from_date'] != '' && $data['to_date']!='' ? ' and date(p.po_date) BETWEEN "'.date('Y-m-d',strtotime($data['from_date'])).'" AND "'.date('Y-m-d',strtotime($data['to_date'])).'"' : '')." 
        ".($data['id_karigar']!='' && $data['id_karigar']!=0 ? " and p.po_karigar_id=".$data['id_karigar']."" :'')."
        ".($data['grn_type']!=     0 ? " and g.grn_type=".$data['grn_type']."" :'')."
        GROUP BY p.po_id,itm.po_item_pro_id
        ORDER BY p.po_id DESC");
        //print_r($this->db->last_query());exit;
        $response_data = $sql->result_array();
    	foreach($response_data as $key => $val){
    	    $return_data[$val['po_ref_no']][] =   $val;  
    	}
    	foreach($return_data as $key => $grndata){
    	    foreach($grndata as $gkey => $gval){
            	if($gkey > 0){
            	    $return_data[$key][$gkey]["po_ref_no"] = "";
            	}   
        	}
    	}
    	//echo "<pre>";print_r($return_data);exit;
    	return $return_data;
    }
    
    
    
    function get_lot_and_tag_wise_report($data)
    {
        $sql = $this->db->query("SELECT pr.product_name as product, kr.firstname as karigarname,  lotdet.lot_no as lotno, date_format(lot.lot_date, '%d-%m-%Y') as lotdate, lotdet.no_of_piece as lotpcs, 
        lotdet.gross_wt as lotgrswt, lotdet.net_wt as lotnetwt, ifnull(podia.stwt,0) as lotdiawt, ifnull(podia.stpcs,0) as lotdiapcs, 
        ifnull(postn.stwt,0) as lotstwt, ifnull(postn.stpcs,0) as lotstpcs, ifnull(lottag.taggrswt, 0) as taggrswt, ifnull(lottag.tagnetwt, 0) as tagnetwt, 
        ifnull(lottag.tagdiawt,0) as tagdiawt, ifnull(lottag.tagdiapcs,0) as tagdiapcs, ifnull(lottag.tagstnwt,0) as tagstwt, ifnull(lottag.tagstpcs,0) as tagstpcs,
        (lotdet.no_of_piece-ifnull(lottag.tagstpcs,0)) as blc_pcs,(lotdet.gross_wt-ifnull(lottag.taggrswt, 0)) as blc_gwt,(lotdet.net_wt-ifnull(lottag.tagnetwt,0)) as blc_nwt
        FROM ret_lot_inwards_detail as lotdet 
        LEFT JOIN ret_product_master as pr ON pr.pro_id = lotdet.lot_product 
        LEFT JOIN ret_lot_inwards as lot ON lot.lot_no = lotdet.lot_no 
        LEFT JOIN ret_karigar as kr ON kr.id_karigar = lot.gold_smith 
        LEFT JOIN ret_purchase_order_items as poitm ON poitm.po_item_po_id = lot.po_id  AND poitm.po_item_cat_id = lot.id_category AND poitm.po_item_pro_id = lotdet.lot_product
        LEFT JOIN ret_purchase_order as po ON po.po_id = poitm.po_item_po_id 
        LEFT JOIN (SELECT po_item_id, sum(po_stone_wt) as stwt, sum(po_stone_pcs) as stpcs, sum(po_stone_amount) as stamount 
        FROM ret_po_stone_items as po  
        LEFT JOIN ret_stone as st ON st.stone_id = po.po_stone_id 
        WHERE st.stone_type = 1  
        GROUP BY po.po_item_id) as podia ON podia.po_item_id = poitm.po_item_id 
        
        LEFT JOIN (SELECT po_item_id, sum(po_stone_wt) as stwt, sum(po_stone_pcs) as stpcs, sum(po_stone_amount) as stamount 
        FROM ret_po_stone_items as po  
        LEFT JOIN ret_stone as st ON st.stone_id = po.po_stone_id 
        WHERE st.stone_type != 1  
        GROUP BY po.po_item_id) as postn ON postn.po_item_id = poitm.po_item_id 
        
        LEFT JOIN(SELECT tag.id_lot_inward_detail as lotinwid, sum(tag.gross_wt) as taggrswt, 
        sum(tag.net_wt) as tagnetwt,  ifnull(tagdia.stn_wt,0) as tagdiawt, 
        ifnull(tagdia.stn_pcs,0) as tagdiapcs, 
        ifnull(tagst.stn_wt,0) as tagstnwt, ifnull(tagst.stn_pcs,0) as tagstpcs 
        FROM ret_taging as tag 
        LEFT JOIN (SELECT ts.tag_id, ifnull(round(sum(ts.wt), 4),0) as stn_wt, 
        ifnull(SUM(ts.pieces),0) as stn_pcs 
        FROM ret_taging_stone as ts 
        LEFT JOIN ret_stone as st ON st.stone_id = ts.stone_id 
        WHERE st.stone_type = 1 
        GROUP by ts.tag_id) tagdia on tagdia.tag_id = tag.tag_id 
        LEFT JOIN (SELECT ts.tag_id, ifnull(round(sum(ts.wt), 4),0) as stn_wt, 
        ifnull(SUM(ts.pieces),0) as stn_pcs 
        FROM ret_taging_stone as ts 
        LEFT JOIN ret_stone as st ON st.stone_id = ts.stone_id 
        WHERE st.stone_type = 1 
        GROUP by ts.tag_id) tagst on tagst.tag_id = tag.tag_id 
        GROUP BY tag.id_lot_inward_detail) as lottag ON lottag.lotinwid = lotdet.id_lot_inward_detail 
        WHERE lot.po_id IS NOT NULL AND lot.stock_type = 1 
        GROUP BY lotdet.id_lot_inward_detail, lotdet.lot_product");
        
        $response_data = $sql->result_array();
    	foreach($response_data as $key => $val){
    	    $return_data[$val['lotno']][] =   $val;  
    	}
    	foreach($return_data as $key => $grndata){
    	    foreach($grndata as $gkey => $gval){
            	if($gkey > 0){
            	    $return_data[$key][$gkey]["lotno"] = "";
            	}   
        	}
    	}
    	echo "<pre>";print_r($return_data);exit;
    	return $return_data;
    }
}
?>
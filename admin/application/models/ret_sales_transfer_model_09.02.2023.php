<?php
if( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ret_sales_transfer_model extends CI_Model
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
	public function updateData($data, $id_field, $id_value, $table)
    {    
	    $edit_flag = 0;
	    $this->db->where($id_field, $id_value);
		$edit_flag = $this->db->update($table,$data);
		return ($edit_flag==1?$id_value:0);
	}	 
	public function deleteData($id_field,$id_value,$table)
    {
        $this->db->where($id_field, $id_value);
        $status= $this->db->delete($table); 
		return $status;
	}
	
	function get_FinancialYear()
	{
		$sql=$this->db->query("SELECT fin_year_code,fin_status,fin_year_name From ret_financial_year");
		return $sql->result_array();
	}
	
	function get_category_details($id_ret_category)
	{
	    $sql = $this->db->query("SELECT c.id_ret_category,c.id_metal,c.name as category_name,mt.metal_code
        FROM ret_category c 
        LEFT JOIN metal mt ON mt.id_metal = c.id_metal where c.id_ret_category=".$id_ret_category."");
	    return $sql->row_array();
	}

	function get_sales_transfer_tag_details($data)
	{
			$sql = $this->db->query("SELECT SUM(t.gross_wt) as gross_wt,cat.name as category_name,p.pro_id,SUM(t.piece) as piece,p.cat_id,
			cat.name as category_name
			FROM  ret_taging t 
			Left join ret_lot_inwards l on t.tag_lot_id=l.lot_no
			LEFT JOIN ret_product_master p ON p.pro_id=t.product_id
			LEFT JOIN ret_category cat ON cat.id_ret_category=p.cat_id	
			Left join ret_design_master d on d.design_no=t.design_id	
			WHERE t.tag_status=0 AND cat.id_ret_category IS NOT NULL AND t.current_branch=".$data['from_brn']." 
			".($data['lotno'] != '' ? ' and t.tag_lot_id='.$data['lotno']: '')." 
			".($data['design_id'] != '' ? ' and design_id='.$data['design_id']: '')." 
			".($data['prodId'] != '' ? ' and t.product_id='.$data['prodId']: '')."
			".($data['tag_code']!='' ? " AND t.tag_code='".$data['tag_code']."'" :'')."
			".($data['cat_id']!='' ? " AND p.cat_id='".$data['cat_id']."'" :'')."
			group by cat.id_ret_category");
			//print_r($this->db->last_query());exit;
			return $sql->result_array();
	}
	
	function get_category_tag_details($cat_id,$id_branch)
    {
        $sql=$this->db->query("SELECT t.tag_id,t.product_id,t.design_id,(t.gross_wt),t.net_wt,t.less_wt,t.calculation_based_on,t.purity,t.piece
        FROM `ret_taging` t
        LEFT JOIN ret_product_master pro ON pro.pro_id=t.product_id
        LEFT JOIN ret_category cat ON cat.id_ret_category=pro.cat_id      
        WHERE t.tag_status=0  AND cat.id_ret_category IS NOT NULL and t.current_branch=".$id_branch."
        ".($cat_id!='' ? " AND pro.cat_id='".$cat_id."'" :'')."  GROUP by t.tag_id");
        //print_r($this->db->last_query());exit;
        return $sql->result_array();
    }
    
    function get_sales_trans_approval_tag($data)
	{
        $sql = $this->db->query("SELECT  b.bill_no,b.bill_id,IFNULL(SUM(dt.piece),0) as piece,IFNULL(SUM(dt.gross_wt),0) as gross_wt,
        date_format(b.bill_date,'%d-%m-%Y') as bill_date
        FROM ret_billing b 
        LEFT JOIN ret_bill_details dt ON dt.bill_id=b.bill_id
        LEFT JOIN ret_taging t ON t.tag_id=dt.tag_id	
        WHERE t.tag_status=4 and b.fin_year_code='".$data['fin_year_code']."' and b.bill_no='".$data['bill_no']."' and b.from_branch=".$data['from_brn']." and b.bill_status=1 and b.to_branch=".$data['to_brn']."
        GROUP by b.bill_id");
        //echo $this->db->last_query();exit;
        return $sql->result_array();
	}	
    
    function get_branch_details($id_branch)
    {
        $sql = $this->db->query("SELECT * FROM branch where id_branch = ".$id_branch."");
        return $sql->row_array();
    }
    
    function getSalesTrans_Tag($bill_id)
	{
	    $sql=$this->db->query("SELECT d.tag_id
        FROM ret_billing b 
        LEFT JOIN ret_bill_details d ON d.bill_id=b.bill_id
        LEFT JOIN ret_taging t ON t.tag_id=d.tag_id
        WHERE t.tag_status=4 AND b.bill_id=".$bill_id."");
        return $sql->result_array();
	}
	
	
	function get_sales_return_trans_req_tag($data)
	{
	    if($data['is_aganist_bill']==1)
	    {
	        $sql = $this->db->query("SELECT 
            SUM(t.gross_wt) as gross_wt,SUM(dt.item_cost) as item_cost,b.bill_id,b.bill_no,SUM(t.piece) as piece,cat.name as category_name,cat.id_ret_category as cat_id,date_format(b.bill_date,'%d-%m-%Y') as bill_date
            FROM ret_billing b 
            LEFT JOIN ret_bill_details dt ON dt.bill_id=b.bill_id
            LEFT JOIN ret_taging t ON t.tag_id=dt.tag_id
            LEFT JOIN ret_product_master pro on pro.pro_id=t.product_id
            LEFT JOIN ret_category cat ON cat.id_ret_category=pro.cat_id
            WHERE (t.tag_status=0) and b.bill_status=1 AND b.fin_year_code='".$data['fin_year_code']."' and b.id_branch=".$data['to_brn']." and t.current_branch = ".$data['from_brn']."
            ".($data['bill_no']!='' ? "AND b.bill_no='".$data['bill_no']."'" :'')."
            group by pro.cat_id");
	    }else
	    {
	        $sql = $this->db->query("SELECT 
            dt.bill_det_id,b.bill_id,dt.tag_id,t.tag_code,(t.gross_wt) as gross_wt,(dt.item_cost) as item_cost,b.bill_id,b.bill_no,(t.piece) as piece,cat.name as category_name,cat.id_ret_category as cat_id,date_format(b.bill_date,'%d-%m-%Y') as bill_date
            FROM ret_billing b 
            LEFT JOIN ret_bill_details dt ON dt.bill_id=b.bill_id
            LEFT JOIN ret_taging t ON t.tag_id=dt.tag_id
            Left join ret_lot_inwards l on t.tag_lot_id=l.lot_no
            LEFT JOIN ret_product_master p ON p.pro_id=t.product_id
            LEFT JOIN ret_product_master pro on pro.pro_id=p.parent_id
            LEFT JOIN ret_category cat ON cat.id_ret_category=pro.cat_id	
            Left join ret_design_master d on d.design_no=t.design_id
            WHERE (t.tag_status=6) and b.bill_status=1  and t.current_branch = ".$data['from_brn']."
            group by dt.tag_id");
	    }
        
        //echo $this->db->last_query();exit;
        return $sql->result_array();
	}
	
	
	function get_sales_return_req_tag_details($cat_id,$id_branch,$bill_id,$from_branch)
	{
	    $sql=$this->db->query("SELECT 
        dt.bill_det_id,dt.bill_id,dt.tag_id,t.tag_status,dt.status,dt.item_cost
        FROM ret_billing b
        LEFT JOIN ret_bill_details dt ON dt.bill_id=b.bill_id 
        LEFT JOIN ret_taging t ON t.tag_id=dt.tag_id       
        LEFT JOIN ret_product_master pro on pro.pro_id=t.product_id      
        LEFT JOIN ret_category cat ON cat.id_ret_category=pro.cat_id
        WHERE (t.tag_status=0) and t.current_branch = ".$from_branch." and b.bill_status=1 and b.id_branch=".$id_branch."
        ".($bill_id!='' ? " AND b.bill_id=".$bill_id."" :'')."
        ".($cat_id!='' ? " AND cat.id_ret_category='".$cat_id."'" :'')."
        ");
        //print_r($this->db->last_query());exit;
        return $sql->result_array();
	}
	
	function getBillId($to_brn,$sales_bill_no,$fin_year_code)
	{
		$sql = $this->db->query("SELECT bill_id from ret_billing 
		WHERE id_branch=".$to_brn." AND bill_no='".$sales_bill_no."' and 
		fin_year_code=".$fin_year_code." " );
	// print_r($this->db->last_query());exit;
	return $sql->row()->bill_id;
	}
	
	
	function get_sales_return_trans_approval_tag($data)	
	{        $sql = $this->db->query("SELECT         
            SUM(t.gross_wt) as gross_wt,SUM(dt.item_cost) as item_cost,b.bill_id,b.bill_no,
            SUM(t.piece) as piece,cat.name as category_name,cat.id_ret_category as cat_id,date_format(b.bill_date,'%d-%m-%Y') as bill_date  
            
            FROM ret_billing b
            LEFT JOIN ret_bill_return_details r ON r.bill_id=b.bill_id
            LEFT JOIN ret_bill_details dt ON dt.bill_det_id=r.ret_bill_det_id 
            LEFT JOIN ret_taging t ON t.tag_id=dt.tag_id        
            LEFT JOIN ret_product_master pro on pro.pro_id=t.product_id     
            LEFT JOIN ret_category cat ON cat.id_ret_category=pro.cat_id  
            WHERE b.bill_id IS NOT NULL AND t.tag_id IS NOT NULL AND (t.tag_status=4 or t.tag_status=6) and b.bill_status=1 AND b.fin_year_code='".$data['fin_year_code']."' 
            AND t.current_branch = ".$data['from_brn']." 
            and b.id_branch=".$data['from_brn']."       
            ".($data['bill_no']!='' ? " AND bill_no='".$data['bill_no']."'" :'')."       
            HAVING gross_wt > 0");      
	// echo $this->db->last_query();exit;   
	return $sql->result_array();	
	}
	
    
    function get_sales_return_tag_details($cat_id,$id_branch,$bill_id)
	{
	    $sql=$this->db->query("SELECT 
        dt.bill_det_id,dt.bill_id,dt.tag_id,t.tag_status
        FROM ret_billing b 
        LEFT JOIN ret_bill_return_details r ON r.bill_id=b.bill_id
        LEFT JOIN ret_bill_details dt ON dt.bill_det_id=r.ret_bill_det_id 
        LEFT JOIN ret_taging t ON t.tag_id=dt.tag_id       
        LEFT JOIN ret_product_master pro on pro.pro_id=t.product_id      
        LEFT JOIN ret_category cat ON cat.id_ret_category=pro.cat_id  
        Left join ret_design_master d on d.design_no=t.design_id
        WHERE (t.tag_status=4 OR t.tag_status=6) and t.current_branch = ".$id_branch." and b.bill_status=1  and b.id_branch=".$id_branch."
        ".($bill_id!='' ? " AND b.bill_id=".$bill_id."" :'')."
        ".($cat_id!='' ? " AND pro.cat_id='".$cat_id."'" :'')."
        ");
        //print_r($this->db->last_query());exit;
        return $sql->result_array();
	}
	
	
}
?>
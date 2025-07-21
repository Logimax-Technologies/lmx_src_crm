<?php
if( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ret_stock_issue_model extends CI_Model
{
	
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
	
	function get_FinancialYear()
	{
		$sql=$this->db->query("SELECT fin_year_code From ret_financial_year where fin_status=1");
		return $sql->row_array();
	}
	
	
	function generateIssueNo()
	{
	    $lastno = NULL;
	    $sql = "SELECT MAX(issue_no) as last_issue_no FROM ret_stock_issue  ORDER BY id_stock_issue DESC LIMIT 1"; 
			$result = $this->db->query($sql);
			if( $result->num_rows() > 0){
				$lastno = $result->row()->last_issue_no;				
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
	
	
	function ajax_getStockIssueList()
	{
	    $sql=$this->db->query("SELECT i.id_stock_issue,i.issue_no,if(i.issue_type=1,'Repair',if(i.issue_type=2,'Marketing','Photoshooting')) as issue_type,emp.firstname as emp_name,date_format(i.issue_date,'%d-%m-%Y') as issue_date,br.name as branch_name,
	    IFNULL(c.order_no,'') as order_no,if(c.order_type=3,'Customer Repair',if(c.order_type=4,'Stock Repair','')) as repair_type,if(i.status=0,'Approval Pending',if(i.status=1,'Issued',if(i.status=2,'Rejected','Received'))) as issue_status,i.status
        FROM ret_stock_issue i 
        LEFT JOIN branch br ON br.id_branch=i.id_branch
        LEFT JOIN employee emp ON emp.id_employee=i.created_by
        LEFT JOIN customerorder c on c.id_stock_issue=i.id_stock_issue");
        return $sql->result_array();
	}
	
	
	function get_IssueItems($id)
	{
	    $sql=$this->db->query("SELECT i.id_branch,i.id_stock_issue,i.issue_no,i.issue_type as issue_type,emp.firstname as emp_name,date_format(i.issue_date,'%d-%m-%Y') as issue_date,br.name as branch_name,i.repair_type,
	    IFNULL(i.remarks,'') as remarks
        FROM ret_stock_issue i 
        LEFT JOIN branch br ON br.id_branch=i.id_branch
        LEFT JOIN employee emp ON emp.id_employee=i.created_by
        where i.id_stock_issue=".$id."");
        return $sql->row_array();
	}
	
	function get_issue_item_details($id,$issue_type,$repair_type)
	{
	    //$issue_type =>1-Repair 2 - Marketing 3 - Photoshooting
	    //$repair_type =>1-Stock Repair 2 - Customer Repair
	    
	   
	        $sql=$this->db->query("SELECT IFNULL(SUM(tag.piece),0) as total_items,IFNULL(SUM(tag.net_wt),0) as weight,i.id_stock_issue,p.product_name,des.design_name,s.sub_design_name
            FROM ret_stock_issue i 
            LEFT JOIN ret_stock_issue_detail d ON d.id_stock_issue=i.id_stock_issue
            LEFT  JOIN ret_taging tag ON tag.tag_id=d.tag_id
            LEFT JOIN ret_product_master p ON p.pro_id=tag.product_id
            LEFT JOIN ret_design_master des ON des.design_no=tag.design_id
            LEFT JOIN ret_sub_design_master s ON s.id_sub_design=tag.id_sub_design
            WHERE i.id_stock_issue=".$id."
            GROUP BY tag.product_id,tag.design_id,tag.id_sub_design");
            //print_r($this->db->last_query());exit;
            return $sql->result_array();

	}
	
	
	
	function get_tag_scan_details($data)
	{
	    $return_data=array();
	    $tag_code=$this->input->post('tag_code');
        $old_tag_code=$this->input->post('old_tag_code');

        $data = $this->db->query("SELECT tag.tag_id as value, tag_code as label, tag.tag_type, tag_lot_id, design_id, cost_center, 
                            tag.purity, tag.size, uom, piece, tag.less_wt,IFNULL(tag.net_wt,0) as net_wt,IFNULL(tag.gross_wt,0) as gross_wt, tag.calculation_based_on, 
                            retail_max_wastage_percent,tag_mc_value,tag_mc_type, halmarking, sales_value, tag.tag_status, 
                             product_name, product_short_code, c.id_ret_category as catid, c.name as catname, 
                            tag.product_id as lot_product, pur.purity as purname,lot_inw.lot_received_at, 
                            tag.tag_id,pro.sales_mode,tag.item_rate,tag.current_branch,
                            des.design_name,tag.tag_mark, tag.id_sub_design as subdesignid, sdes.sub_design_name as sub_design_name,
                            IFNULL(old_tag_id,'-')as old_label,IFNULL(tag.id_section,'') as id_section
                            
                            FROM ret_taging as tag
                            Left join ret_lot_inwards_detail lot_det ON tag.id_lot_inward_detail = lot_det.id_lot_inward_detail 
                            LEFT JOIN ret_lot_inwards as lot_inw ON lot_inw.lot_no = lot_det.lot_no 
                            LEFT JOIN ret_product_master as pro ON pro.pro_id = tag.product_id 
                            LEFT JOIN ret_design_master des on des.design_no=tag.design_id 
                            left join ret_sub_design_master sdes on sdes.id_sub_design=tag.id_sub_design
                            LEFT JOIN ret_purity as pur ON pur.id_purity = tag.purity 
                            left join ret_category c on c.id_ret_category=pro.cat_id 
                            left join metal mt on mt.id_metal=c.id_metal 
                            LEFT JOIN ret_stock_issue_detail as si ON si.tag_id = tag.tag_id 
                            WHERE tag.tag_status = 0 and tag.id_orderdetails is NULL AND (si.status = 2 OR si.status = 3 OR si.tag_id IS NULL) 
                            and ".($old_tag_code!='' ? " old_tag_id='".$old_tag_code."'" : ($tag_code!='' ? "tag_code='".$tag_code."'" :'') )."  ".($data['id_branch'] !='' ? " and tag.current_branch = ".$data['id_branch']."" :'')."");
        //print_r($this->db->last_query());exit;
        $tagging= $data->result_array();
        foreach($tagging as $tag)
        {
            $return_data[]=array(
                                 'current_branch'               =>$tag['current_branch'],
                                 'catid'                        => $tag['catid'],
                                 'catname'                      => $tag['catname'],
                                 'design_id'                    =>$tag['design_id'],
                                 'design_name'                  =>$tag['design_name'],
                                 'sub_design_name'              =>$tag['sub_design_name'],
                                 'subdesignid'                  => $tag['subdesignid'],
                                 'gross_wt'                     =>$tag['gross_wt'],
                                 'item_rate'                    =>$tag['item_rate'],
                                 'label'                        =>$tag['label'],
                                 'old_label'                    =>$tag['old_label'],
                                 'less_wt'                      =>$tag['less_wt'],
                                 'lot_product'                  =>$tag['lot_product'],
                                 'lot_received_at'              =>$tag['lot_received_at'],
                                 'net_wt'                       =>$tag['net_wt'],
                                 'piece'                        =>$tag['piece'],
                                 'product_name'                 => $tag['product_name'],
                                 'product_short_code'           =>$tag['product_short_code'],
                                 'purity'                       =>$tag['purity'],
                                 'purname'                      =>$tag['purname'],
                                 'sales_value'                  =>$tag['sales_value'],
                                 'size'                         =>$tag['size'],
                                 'tag_id'                       =>$tag['tag_id'],
                                 'tag_lot_id'                   =>$tag['tag_lot_id'],
                                 'tag_mc_type'                  =>$tag['tag_mc_type'],
                                 'tag_mc_value'                 =>$tag['tag_mc_value'],
                                 'tag_status'                   =>$tag['tag_status'],
                                 'value'                        =>$tag['value'],
                                 'id_section'                   =>$tag['id_section'],
                                 'wastage_percent'              =>$tag['retail_max_wastage_percent']
                                );
        }
        return $return_data;
	}
	
	function get_receipt_tag_scan_details($data)
	{
	    $return_data=array();
        $data = $this->db->query("SELECT tag.tag_id as value, tag_code as label, tag.tag_type, tag_lot_id, design_id, cost_center, 
                            tag.purity, tag.size, uom, piece, tag.less_wt, tag.net_wt, tag.gross_wt, tag.calculation_based_on, 
                            retail_max_wastage_percent,tag_mc_value,tag_mc_type, halmarking, sales_value, tag.tag_status, 
                             product_name, product_short_code, c.id_ret_category as catid, c.name as catname, 
                            tag.product_id as lot_product, pur.purity as purname,lot_inw.lot_received_at, 
                            tag.tag_id,pro.sales_mode,tag.item_rate,tag.current_branch,
                            des.design_name,tag.tag_mark, tag.id_sub_design as subdesignid, sdes.sub_design_name as sub_design_name,
                            IFNULL(tag.id_section,'') as id_section
                            FROM ret_taging as tag 
                            Left join ret_lot_inwards_detail lot_det ON tag.id_lot_inward_detail = lot_det.id_lot_inward_detail 
                            LEFT JOIN ret_lot_inwards as lot_inw ON lot_inw.lot_no = lot_det.lot_no 
                            LEFT JOIN ret_product_master as pro ON pro.pro_id = tag.product_id 
                            LEFT JOIN ret_design_master des on des.design_no=tag.design_id 
                            left join ret_sub_design_master sdes on sdes.id_sub_design=tag.id_sub_design
                            LEFT JOIN ret_purity as pur ON pur.id_purity = tag.purity 
                            left join ret_category c on c.id_ret_category=pro.cat_id 
                            left join metal mt on mt.id_metal=c.id_metal 
                            LEFT JOIN ret_stock_issue_detail as si ON si.tag_id = tag.tag_id 
                            WHERE tag.tag_status = 7 and tag.id_orderdetails is NULL 
                            and tag.tag_code='".$data['tag_code']."'  ");
        //print_r($this->db->last_query());exit;
        $tagging= $data->result_array();
        foreach($tagging as $tag)
        {
            $return_data[]=array(
                                 'current_branch'               =>$tag['current_branch'],
                                 'catid'                        => $tag['catid'],
                                 'catname'                      => $tag['catname'],
                                 'design_id'                    =>$tag['design_id'],
                                 'design_name'                  =>$tag['design_name'],
                                 'sub_design_name'              =>$tag['sub_design_name'],
                                 'subdesignid'                  => $tag['subdesignid'],
                                 'gross_wt'                     =>$tag['gross_wt'],
                                 'item_rate'                    =>$tag['item_rate'],
                                 'label'                        =>$tag['label'],
                                 'less_wt'                      =>$tag['less_wt'],
                                 'lot_product'                  =>$tag['lot_product'],
                                 'lot_received_at'              =>$tag['lot_received_at'],
                                 'net_wt'                       =>$tag['net_wt'],
                                 'piece'                        =>$tag['piece'],
                                 'product_name'                 => $tag['product_name'],
                                 'product_short_code'           =>$tag['product_short_code'],
                                 'purity'                       =>$tag['purity'],
                                 'purname'                      =>$tag['purname'],
                                 'sales_value'                  =>$tag['sales_value'],
                                 'size'                         =>$tag['size'],
                                 'tag_id'                       =>$tag['tag_id'],
                                 'tag_lot_id'                   =>$tag['tag_lot_id'],
                                 'tag_mc_type'                  =>$tag['tag_mc_type'],
                                 'tag_mc_value'                 =>$tag['tag_mc_value'],
                                 'tag_status'                   =>$tag['tag_status'],
                                 'value'                        =>$tag['value'],
                                 'id_section'                   =>$tag['id_section'],
                                );
        }
        return $return_data;
	}
	
	
	function get_stock_issue_type()
	{
	    $sql=$this->db->query("SELECT * FROM `ret_stock_issue_types` WHERE status=1");
	    return $sql->result_array();
	}
	
	function stock_issue_type_detail($id)
	{
	    $sql=$this->db->query("SELECT * FROM `ret_stock_issue_types` WHERE id_stock_issue_type=".$id."");
	    return $sql->row_array();
	}
	
	
	function get_StockIssuedItems()
	{
	    $returnData=array();
	    $sql=$this->db->query("SELECT id_stock_issue , issue_no FROM `ret_stock_issue` WHERE status=1");
	    $result= $sql->result_array();
	    foreach($result as $items)
	    {
	        $tag_details=$this->stock_issue_tags($items['id_stock_issue']);
	        if(sizeof($tag_details))
	        {
	            $returnData[]=array(
                    'id_stock_issue'=>$items['id_stock_issue'],
                    'issue_no'      =>$items['issue_no'],
                    'tag_details'   =>$tag_details,
                );
	        }
	        
	    }
	    return $returnData;
	}
	
	
	function stock_issue_tags($id_stock_issue)
	{
	    $sql=$this->db->query("SELECT t.tag_id,t.tag_code
        FROM ret_stock_issue_detail d 
        LEFT JOIN ret_taging t ON t.tag_id=d.tag_id
        WHERE d.status=1 and id_stock_issue=".$id_stock_issue."");
	    return $sql->result_array();
	}
	
	function getTagDetails($tag_id)
	{
	    $sql = $this->db->query("select IFNULL(t.id_section,'') as id_section FROM ret_taging t where t.tag_id=".$tag_id."");
	    return $sql->row_array();
	}

}
?>
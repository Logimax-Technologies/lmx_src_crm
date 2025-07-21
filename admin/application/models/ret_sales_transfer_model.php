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

	

	function get_metal_details($id_metal)

	{

	    $sql = $this->db->query("SELECT * FROM metal where id_metal=".$id_metal."");

	    return $sql->row_array();

	}



	function get_sales_transfer_tag_details($data)

	{

			$sql = $this->db->query("SELECT IFNULL(t.gross_wt,0) as gross_wt,IFNULL(t.net_wt,0) as net_wt,IFNULL(t.less_wt,0) as less_wt,cat.name as category_name,p.pro_id,(t.piece) as piece,p.cat_id,

			cat.name as category_name,t.tag_id,t.tag_code,mt.metal_code,cat.id_metal,t.product_id,t.design_id,t.id_sub_design,t.calculation_based_on,t.purity,p.cat_id

			FROM  ret_taging t 

			Left join ret_lot_inwards l on t.tag_lot_id=l.lot_no

			LEFT JOIN ret_product_master p ON p.pro_id=t.product_id

			LEFT JOIN ret_category cat ON cat.id_ret_category=p.cat_id	

			LEFT JOIN metal mt ON mt.id_metal = cat.id_metal

			Left join ret_design_master d on d.design_no=t.design_id	

			WHERE t.tag_status=0  AND t.current_branch=".$data['from_brn']." 

			".($data['lotno'] != '' ? ' and t.tag_lot_id='.$data['lotno']: '')." 

			".($data['design_id'] != '' ? ' and design_id='.$data['design_id']: '')." 

			".($data['prodId'] != '' ? ' and t.product_id='.$data['prodId']: '')."

			".($data['tag_code']!='' ? " AND t.tag_code='".$data['tag_code']."'" :'')."

			".($data['old_tag_code']!='' ? " AND t.old_tag_id='".$data['old_tag_code']."'" :'')."

			".($data['cat_id']!='' ? " AND p.cat_id='".$data['cat_id']."'" :'')."

			".($data['id_metal']!='' ? " AND cat.id_metal='".$data['id_metal']."'" :'')."

			");

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

        $retrun_data = array();

        $sql = $this->db->query("SELECT  b.sales_ref_no bill_no,b.bill_id,IFNULL(SUM(dt.piece),0) as piece,IFNULL(SUM(dt.gross_wt),0) as gross_wt,

        date_format(b.bill_date,'%d-%m-%Y') as bill_date,dt.tag_id

        FROM ret_billing b 

        LEFT JOIN ret_bill_details dt ON dt.bill_id=b.bill_id

        LEFT JOIN ret_taging t ON t.tag_id=dt.tag_id	

        WHERE t.tag_status=4 and b.fin_year_code='".$data['fin_year_code']."' and b.sales_ref_no='".$data['bill_no']."' and b.from_branch=".$data['from_brn']." and b.bill_status=1 and b.to_branch=".$data['to_brn']."

        GROUP by b.bill_id");

        //echo $this->db->last_query();exit;

        $result = $sql->result_array();



        foreach($result as $r)

        {

            $return_data[]=array(

                'bill_no'   => $r['bill_no'],

                'bill_id'   => $r['bill_id'],

                'piece'     => $r['piece'],

                'gross_wt'  => $r['gross_wt'],

                'bill_date' => $r['bill_date'],

                'bill_tags' => $this->get_billed_details($r['bill_id'])

            );

        }



        return $return_data;

	}	



    function get_billed_details($bill_id)

    {

        $sql = $this->db->query("SELECT dt.tag_id,t.tag_code,t.tag_status,dt.piece 

        

        FROM ret_bill_details dt 



        LEFT JOIN ret_taging t on t.tag_id=dt.tag_id

        

        where dt.bill_id=".$bill_id."");



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

            SUM(t.gross_wt) as gross_wt,SUM(dt.item_cost) as item_cost,b.bill_id,b.sales_ref_no bill_no,SUM(t.piece) as piece,cat.name as category_name,cat.id_ret_category as cat_id,date_format(b.bill_date,'%d-%m-%Y') as bill_date

            FROM ret_billing b 

            LEFT JOIN ret_bill_details dt ON dt.bill_id=b.bill_id

            LEFT JOIN ret_taging t ON t.tag_id=dt.tag_id

            LEFT JOIN ret_product_master pro on pro.pro_id=t.product_id

            LEFT JOIN ret_category cat ON cat.id_ret_category=pro.cat_id

            WHERE (t.tag_status=0) and b.bill_status=1 AND b.fin_year_code='".$data['fin_year_code']."' and b.id_branch=".$data['to_brn']." and t.current_branch = ".$data['from_brn']."

            ".($data['bill_no']!='' ? "AND b.sales_ref_no='".$data['bill_no']."'" :'')."

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

            LEFT JOIN ret_category cat ON cat.id_ret_category=p.cat_id	

            Left join ret_design_master d on d.design_no=t.design_id

            WHERE (t.tag_status=6) and b.bill_status=1  and t.current_branch = ".$data['from_brn']."

            group by dt.tag_id");

	    }

        

    //    echo $this->db->last_query();exit;

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

	{    

        $return_data=array();    

            $sql = $this->db->query("SELECT         

            SUM(t.gross_wt) as gross_wt,SUM(dt.item_cost) as item_cost,b.bill_id,b.bill_no,

            SUM(t.piece) as piece,cat.name as category_name,cat.id_ret_category as cat_id,date_format(b.bill_date,'%d-%m-%Y') as bill_date,

            b.ref_bill_id 

            

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

	//echo $this->db->last_query();exit;   

	$result = $sql->result_array();	

    foreach($result as $r)

    {

        $return_data[]=array(

            "bill_id"  => $r['bill_id'],

            "bill_no"  => $r['bill_no'],

            "bill_date" => $r['bill_date'],

            "gross_wt" => $r['gross_wt'],

            "piece"    => $r['piece'],

            "item_cost" => $r['item_cost'],

            "cat_id"   => $r['cat_id'],

            "category_name" => $r['category_name'],

            'ref_bill_id'  => $r['ref_bill_id'],

            "ret_bill_tags" => $this->get_billed_details($r['ref_bill_id'])



        );

    }

    return $return_data;

}



/*function get_ret_billed_tags($bill_id)

{



}*/

	

    

    function get_sales_return_tag_details($cat_id,$id_branch,$bill_id)

	{

	    $sql=$this->db->query("SELECT 

        dt.bill_det_id,dt.bill_id,dt.tag_id,t.tag_status,IFNULL(t.id_section,'') as id_section

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

	



    function getSettigsByName($name)

    {		 

		$branch = $this->db->query("SELECT value FROM ret_settings b Where name='".$name."'");

		return $branch->row('value');



	}

	



    function fetchTagsByFilter_scan($data)

    {

        $sql=$this->db->query("SELECT  b.bill_no,b.bill_id,IFNULL(dt.piece,0) as piece,IFNULL(dt.gross_wt,0) as gross_wt,

        

        date_format(b.bill_date,'%d-%m-%Y') as bill_date,dt.tag_id,t.tag_code,pro.product_name

        

        FROM ret_billing b 

        

        LEFT JOIN ret_bill_details dt ON dt.bill_id=b.bill_id

        

        LEFT JOIN ret_taging t ON t.tag_id=dt.tag_id	



        LEFT JOIN ret_product_master pro on pro.pro_id=t.product_id

        

        WHERE t.tag_status=4 

        ".($data['tag_code']!='' ? " and t.tag_code='".$data['tag_code']."'" :'')."

        ".($data['old_tag_code']!='' ? " and t.old_tag_id='".$data['old_tag_code']."'" :'')."

        

        and b.fin_year_code='".$data['fin_year_code']."' and b.bill_no='".$data['bill_no']."' and b.from_branch=".$data['from_brn']." and b.

        

        bill_status=1 and b.to_branch=".$data['to_brn']."");



       //print_r($this->db->last_query());exit;



        return $sql->result_array();

    }





    function get_TagBilledPcs($bill_id)

    {

        $sql = $this->db->query("SELECT IFNULL(SUM(dt.piece),0) as piece

        

        FROM ret_bill_details dt

        

        LEFT JOIN ret_taging t on t.tag_id=dt.tag_id

        

        WHERE tag_status=0 and dt.bill_id=".$bill_id."

        

        GROUP BY dt.bill_id");



        return $sql->row('piece');





    }





    function fetchReturnTagsByFilter_scan($data)

    {

        $sql=$this->db->query("SELECT dt.bill_det_id,b.bill_id,dt.tag_id,b.ref_bill_id,

        

        t.tag_status,t.tag_code,pro.product_name,IFNULL(t.piece,0) as piece,IFNULL(t.gross_wt,0) as gross_wt

        

        FROM ret_billing b 

        

        LEFT JOIN ret_bill_return_details r ON r.bill_id=b.bill_id

        

        LEFT JOIN ret_bill_details dt ON dt.bill_det_id=r.ret_bill_det_id 

        

        LEFT JOIN ret_taging t ON t.tag_id=dt.tag_id       

        

        LEFT JOIN ret_product_master pro on pro.pro_id=t.product_id      

        

        LEFT JOIN ret_category cat ON cat.id_ret_category=pro.cat_id  

        

        Left join ret_design_master d on d.design_no=t.design_id

        

        WHERE (t.tag_status=4 OR t.tag_status=6) and t.current_branch=".$data['from_brn']."

        

        and b.bill_status=1 and b.id_branch=".$data['from_brn']." and t.tag_code='".$data['tag_code']."'

        

        and b.bill_no='".$data['bill_no']."'");



        //print_r($this->db->last_query());exit;



        return $sql->result_array();

    }

}

?>
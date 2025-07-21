<?php
if( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ret_metal_process_model extends CI_Model
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
    
                return TRUE;
    
            }else{
    
                return FALSE;
    
            }
    
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
        
    //Process Master
    function ajax_getMetalProcess()
    {
        $sql=$this->db->query("SELECT * FROM `ret_old_metal_process_master`");
        return $sql->result_array();
    }
    
    function getMetalProcess($id_metal_process)
    {
        $sql=$this->db->query("SELECT * FROM `ret_old_metal_process_master` where id_metal_process=".$id_metal_process."");
        return $sql->row_array();
    }
    
    //Process Master
        
    function code_number_generator()
    {
    	$lastno = $this->get_last_code_no();
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
    
    function get_last_code_no()
    {
		$sql = "SELECT max(pocket_no) as pocket_no FROM ret_old_metal_pocket ORDER by id_metal_pocket DESC";
		return $this->db->query($sql)->row()->pocket_no;	
	}
    
    function get_old_metal_type()
    {
        $sql=$this->db->query("SELECT * FROM ret_old_metal_type");
        return $sql->result_array();
    }

    /*function get_metal_stock_list($data)
    {
        $return_data=array();
        
        $sql=$this->db->query("SELECT est.id_old_metal_type,SUM(s.gross_wt) as gross_wt,SUM(s.wast_wt) as wast_wt,t.metal_type,SUM(IFNULL(s.gross_wt,0)-IFNULL(s.dust_wt,0)-IFNULL(s.stone_wt,0)) as pure_wt, SUM(s.old_metal_rate) as old_metal_rate,SUM(s.rate) as rate,COUNT(DISTINCT s.old_metal_sale_id) as tot_count,IFNULL(SUM(s.dust_wt),0) as dust_wt,IFNULL(SUM(s.stone_wt),0) as stone_wt,IFNULL(SUM(s.net_wt),0) as net_wt 
        FROM ret_bill_old_metal_sale_details s
        LEFT JOIN ret_estimation_old_metal_sale_details est ON est.old_metal_sale_id=s.esti_old_metal_sale_id
        LEFT JOIN ret_estimation e ON e.estimation_id=est.est_id
        LEFT JOIN ret_old_metal_type t ON t.id_metal_type=est.id_old_metal_type
        LEFT JOIN ret_billing b ON b.bill_id=s.bill_id 
        WHERE b.bill_status=1 and s.is_pocketed=0 and (date(b.bill_date) BETWEEN '".date('Y-m-d',strtotime($data['from_date']))."' AND '".date('Y-m-d',strtotime($data['to_date']))."')  and s.current_branch=".$data['id_branch']."
        ".($data['id_metal']!='' ? " and s.metal_type=".$data['id_metal']."" :'')."");
        //print_r($this->db->last_query());exit;
        $result=$sql->result_array();
        foreach($result as $items)
        {   
            $purity_per=0;
            $bill_details=$this->get_old_metal_details($data['from_date'],$data['to_date'],$data['id_branch'],$data['id_metal']);
            foreach($bill_details as $bill)
            {   
                $purity_per+=$bill['purity_per'];
            }
            $return_data[]=array(
                'gross_wt'          =>$items['gross_wt'],
                'stone_wt'          =>$items['stone_wt'],
                'dust_wt'           =>$items['dust_wt'],
                'net_wt'            =>$items['net_wt'],
                'metal_type'        =>'Old Metal',
                'wast_wt'           =>$items['wast_wt'],
                'pure_wt'           =>$items['pure_wt'],
                'old_metal_rate'    =>$items['old_metal_rate'],
                'rate'              =>$items['rate'],
                'total_count'       =>$items['tot_count'],
                'type'              =>'old_metal',
                'purity_per'        =>number_format(($purity_per/sizeof($bill_details)),2,'.',''),
                'bill_det'          =>$bill_details,
            );
        }

        return $return_data;
    }*/
    
    
    function get_headOffice()
	{
		$data=$this->db->query("SELECT b.is_ho,b.id_branch,name FROM branch b where b.is_ho=1");
		return $data->row_array();
	}
	
    function get_metal_stock_list($data)
	{
		$return_Data=[];

        $sql=$this->db->query("SELECT t.metal_type,s.metal_type
        FROM ret_billing b 
        LEFT JOIN ret_bill_old_metal_sale_details s ON s.bill_id=b.bill_id
        LEFT JOIN ret_estimation_old_metal_sale_details est ON est.old_metal_sale_id=s.esti_old_metal_sale_id
        LEFT JOIN ret_old_metal_type t ON t.id_metal_type=est.id_old_metal_type
        WHERE b.bill_status=1  AND s.is_transferred=1 AND s.is_pocketed=0
        and (date(b.bill_date) BETWEEN '".date('Y-m-d',strtotime($data['from_date']))."' AND '".date('Y-m-d',strtotime($data['to_date']))."')
        ".($data['id_metal']!='' ? " and s.metal_type=".$data['id_metal']."" :'')."
        ".($data['id_branch']!='' ? " and s.current_branch=".$data['id_branch']."" :'')."
        GROUP by s.metal_type");
        //print_r($this->db->last_query());exit;
		$result=$sql->result_array();
	    foreach($result as $items)
	    {
	        $billDetails=$this->old_metal_bill_details($data['from_date'],$data['to_date'],$data['id_branch'],$items['metal_type'],$data['from_branch']);
	        $purity_per=0;
	        $gross_wt=0;
	        $nwt=0;
	        $rate=0;
	        foreach($billDetails as $bill)
	        {
	            $gross_wt+=$bill['gross_wt'];
	            $nwt+=$bill['net_wt'];
	            $rate+=$bill['rate'];
	            $purity_per+=number_format(($bill['purity']),2,'.','');
	        }
	        
            $return_Data[]=array(
            'metal_type'        =>'OLD METAL ITEMS',
            'type'              =>'old_metal',
            'gross_wt'			=>number_format($gross_wt,3,'.',''),
            'net_wt'			=>number_format($nwt,3,'.',''),
            'rate'				=>number_format($rate,2,'.',''),
            'purity_per'        =>number_format($purity_per/sizeof($billDetails),2,'.',''),
            'bill_det'			=>$billDetails,
            );
	    }
	    
	    
	    $sales_ret=$this->db->query("SELECT mt.id_metal
        FROM ret_taging tag 
        LEFT JOIN ret_bill_details d ON d.tag_id=tag.tag_id
        LEFT JOIN ret_billing b ON b.bill_id=d.bill_id
        LEFT JOIN ret_product_master p ON p.pro_id=d.product_id
        LEFT JOIN ret_category cat ON cat.id_ret_category=p.cat_id
        LEFT JOIN metal mt ON mt.id_metal=cat.id_metal
        WHERE b.bill_status=1 AND tag.tag_status=6 and (tag.tag_process=0 or tag.tag_process=2) and tag.is_pocketed=0
        and (date(b.bill_date) BETWEEN '".date('Y-m-d',strtotime($data['from_date']))."' AND '".date('Y-m-d',strtotime($data['to_date']))."')
        ".($data['id_metal']!='' ? " and mt.id_metal=".$data['id_metal']."" :'')."
        ".($data['id_branch']!='' ? " and tag.current_branch=".$data['id_branch']."" :'')."
        GROUP by mt.id_metal");
        //print_r($this->db->last_query());exit;
        $sales_ret_result=$sales_ret->result_array();
        foreach($sales_ret_result as $val)
	    {
	        $SalesRetDetails=$this->get_sales_ret_details($data['from_date'],$data['to_date'],$data['id_branch'],$val['id_metal']);
	        $purity_per=0;
	        $gross_wt=0;
	        $nwt=0;
	        $rate=0;
	        foreach($SalesRetDetails as $sales)
	        {
	            $gross_wt+=$sales['gross_wt'];
	            $nwt+=$sales['net_wt'];
	            $rate+=$sales['item_cost'];
	            $purity_per+=$sales['purity_per'];
	        }
	        
	        $return_Data[]=array(
            'metal_type'        =>'SALES RETURN ITEMS',
            'type'              =>'sales_return',
            'gross_wt'			=>number_format($gross_wt,3,'.',''),
            'net_wt'			=>number_format($nwt,3,'.',''),
            'rate'				=>number_format($rate,2,'.',''),
            'purity_per'        =>number_format($purity_per/sizeof($SalesRetDetails),2,'.',''),
            'bill_det'			=>$SalesRetDetails,
            );
	    }
	    
		return $return_Data;
	}
    
    function get_partly_sale_details($from_date,$to_date,$id_branch,$id_metal)
    {
        $sql=$this->db->query("SELECT (IFNULL(tag.gross_wt,0)-IFNULL(t.sold_gross_wt,0)) as gross_wt,'0' as amount,cat.id_metal,mt.metal as metal_name,
        DATE_FORMAT(bill.bill_date,'%d-%m-%Y') as bill_date,bill.bill_no,bill.bill_id,'0' as is_checked,d.tag_id as trans_id,
        (IFNULL(tag.net_wt,0)-IFNULL(t.sold_net_wt,0)) as net_wt,
        if(mt.id_metal=1,'partly_sale_gold','partly_sale_silver') as item_type,'3' as transfer_items
        FROM ret_bill_details d 
        LEFT JOIN ret_taging tag ON tag.tag_id=d.tag_id
        LEFT JOIN ret_billing bill ON bill.bill_id=d.bill_id
        LEFT JOIN ret_product_master p ON p.pro_id=d.product_id
        LEFT JOIN ret_category cat ON cat.id_ret_category=p.cat_id
        LEFT JOIN metal mt ON mt.id_metal=cat.id_metal
        LEFT JOIN (SELECT IFNULL(s.sold_gross_wt,0) as sold_gross_wt,IFNULL(s.sold_net_wt,0) as sold_net_wt,s.tag_id
                  FROM ret_partlysold s 
                  LEFT JOIN ret_taging tag ON tag.tag_id=s.tag_id
                  LEFT JOIN ret_bill_details d ON d.bill_det_id=s.sold_bill_det_id
                  LEFT JOIN ret_billing b ON b.bill_id=d.bill_id
                  LEFT JOIN ret_product_master p ON p.pro_id=d.product_id
        		  LEFT JOIN ret_category cat ON cat.id_ret_category=p.cat_id
                  WHERE b.bill_status=1
                  ".($id_branch!='' ?  " and b.id_branch=".$id_branch."" :'')."
		          and (date(b.bill_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') 
                  ) as t ON t.tag_id=d.tag_id
        WHERE bill.bill_status=1 AND d.is_partial_sale=1 AND tag.tag_status=1 and tag.trans_to_acc_stock=0 and mt.id_metal=".$id_metal."
        ".($id_branch!='' ?  " and bill.id_branch=".$id_branch."" :'')."
		and (date(bill.bill_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') 
        ");
		//print_r($this->db->last_query());exit;
		return $sql->result_array();
    }
    
    
    function get_sales_ret_details($from_date,$to_date,$id_branch,$id_metal)
    {
        $returnData=[];
        $sql=$this->db->query("SELECT IFNULL((t.gross_wt),0) as gross_wt,IFNULL((t.net_wt),0) as net_wt,'0' as is_checked,t.tag_id,
        t.tag_id as trans_id,pur.purity,(d.item_cost-d.item_total_tax) as item_cost,d.rate_per_grm,DATE_FORMAT(b.bill_date,'%d-%m-%Y') as bill_date,b.bill_no,b.bill_id
        FROM ret_billing b 
        LEFT JOIN ret_bill_details d ON d.bill_id=b.bill_id
        LEFT JOIN ret_taging t ON t.tag_id=d.tag_id
        LEFT JOIN ret_product_master p ON p.pro_id=t.product_id
        LEFT JOIN ret_category cat ON cat.id_ret_category=p.cat_id
        LEFT JOIN metal mt ON mt.id_metal=cat.id_metal
        LEFT JOIN ret_purity pur ON pur.id_purity=t.purity
        WHERE t.tag_status=6 AND b.bill_status=1 and (t.tag_process=0 or t.tag_process=2) and t.is_pocketed=0 
        and mt.id_metal=".$id_metal."
        ".($id_branch!='' ?  " and t.current_branch=".$id_branch."" :'')."
		and (date(b.bill_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') ");
	    //print_r($this->db->last_query());exit;
		$result= $sql->result_array();
		foreach($result as $items)
		{
		    $returnData[]=array(
		                       'gross_wt'       =>$items['gross_wt'],
		                       'net_wt'         =>$items['net_wt'],
		                       'is_checked'     =>$items['is_checked'],
		                       'trans_id'       =>$items['trans_id'],
		                       'purity_per'     =>$items['purity'],
		                       'item_cost'      =>$items['item_cost'],
		                       'rate_per_grm'   =>$items['rate_per_grm'],
		                       'type'           =>'sales_return_items',
		                       'bill_date'      =>$items['bill_date'],
                               'bill_no'        =>$items['bill_no'],
                               'bill_id'        =>$items['bill_id'],
                               'tag_id'         =>$items['tag_id'],
                               'pocket_type'    =>2,
		                       );
		}
		return $returnData;
    }
    
	function old_metal_bill_details($from_date,$to_date,$id_branch,$metal_type,$from_branch)
	{
	    $returnData=array();
        $sql=$this->db->query("SELECT s.old_metal_sale_id as trans_id,s.gross_wt as gross_wt,s.net_wt as net_wt,s.rate as amount,est.id_old_metal_type,
        t.metal_type,DATE_FORMAT(b.bill_date,'%d-%m-%Y') as bill_date,b.bill_no,b.bill_id,'0' as is_checked,s.old_metal_sale_id,
        if(s.metal_type=1,'old_metal_gold','old_metal_silver') as item_type,'1' as transfer_items,(IFNULL(s.gross_wt,0)-IFNULL(s.dust_wt,0)-IFNULL(s.stone_wt,0)) as pure_wt,s.old_metal_rate,
        s.rate as rate,IFNULL(s.dust_wt,0) as dust_wt,IFNULL(s.stone_wt,0) as stone_wt,IFNULL(s.wast_wt,0) as wast_wt,IFNULL(s.purity,0) as purity
        FROM ret_billing b 
        LEFT JOIN ret_bill_old_metal_sale_details s ON s.bill_id=b.bill_id
        LEFT JOIN ret_estimation_old_metal_sale_details est ON est.old_metal_sale_id=s.esti_old_metal_sale_id
        LEFT JOIN ret_old_metal_type t ON t.id_metal_type=est.id_old_metal_type
        WHERE b.bill_status=1 AND s.old_metal_sale_id IS NOT null  AND s.is_transferred=1 AND s.is_pocketed=0  
        ".($id_branch!='' ?  " and s.current_branch=".$id_branch."" :'')."
        ".($metal_type!='' ?  " and s.metal_type=".$metal_type."" :'')."
        and (date(b.bill_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') ");
        //print_r($this->db->last_query());exit;
        $result=$sql->result_array();
        foreach($result as $items)
        {
            $returnData[]=array(
                               'trans_id'           =>$items['trans_id'],
                               'gross_wt'           =>$items['gross_wt'],
                               'net_wt'             =>$items['net_wt'],
                               'amount'             =>$items['amount'],
                               'id_old_metal_type'  =>$items['id_old_metal_type'],
                               'metal_type'         =>$items['metal_type'],
                               'bill_date'          =>$items['bill_date'],
                               'bill_no'            =>$items['bill_no'],
                               'bill_id'            =>$items['bill_id'],
                               'is_checked'         =>$items['is_checked'],
                               'old_metal_sale_id'  =>$items['old_metal_sale_id'],
                               'item_type'          =>$items['item_type'],
                               'transfer_items'     =>$items['transfer_items'],
                               'pure_wt'            =>$items['pure_wt'],
                               'old_metal_rate'     =>$items['old_metal_rate'],
                               'rate'               =>$items['rate'],
                               'item_cost'          =>$items['rate'],
                               'dust_wt'            =>$items['dust_wt'],
                               'stone_wt'           =>$items['stone_wt'],
                               'wast_wt'            =>$items['wast_wt'],
                               'purity'             =>$items['purity'],
                               'type'               =>'old_metal_items',
                               'pocket_type'        =>1,
                               'purity_per'         =>number_format((($items['purity'])),2,'.',''),
                               );
        }
        return $returnData;
	}
    
    

    function get_old_metal_details($from_date,$to_date,$id_branch,$id_metal)
    {
        $return_data=array();
        
        $sql=$this->db->query("SELECT s.old_metal_sale_id,est.id_old_metal_type,IFNULL(s.gross_wt,0) as gross_wt,IFNULL(s.wast_wt,0) as wast_wt,t.metal_type,(IFNULL(s.gross_wt,0)-IFNULL(s.dust_wt,0)-IFNULL(s.stone_wt,0)) as pure_wt,
       s.old_metal_rate,s.rate as rate,IFNULL(s.dust_wt,0) as dust_wt,IFNULL(s.stone_wt,0) as stone_wt,IFNULL(s.net_wt,0) as net_wt,
       DATE_FORMAT(b.bill_date,'%d-%m-%Y') as bill_date,b.bill_no,b.bill_id,s.esti_old_metal_sale_id
        FROM ret_purchase_items_log l 
        LEFT JOIN ret_bill_old_metal_sale_details s ON s.old_metal_sale_id=l.old_metal_sale_id
        LEFT JOIN ret_estimation_old_metal_sale_details est ON est.old_metal_sale_id=s.esti_old_metal_sale_id
        LEFT JOIN ret_estimation e ON e.estimation_id=est.est_id
        LEFT JOIN ret_old_metal_type t ON t.id_metal_type=est.id_old_metal_type
        LEFT JOIN ret_billing b ON b.bill_id=s.bill_id
        WHERE b.bill_status=1 and s.is_pocketed=0 and (date(l.date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')  and l.to_branch=".$id_branch." AND l.item_type=1
         ".($id_metal!='' ? " and s.metal_type=".$id_metal."" :'')."
        GROUP by s.esti_old_metal_sale_id");
        //print_r($this->db->last_query());exit;
        
        /*$sql=$this->db->query("SELECT s.old_metal_sale_id,est.id_old_metal_type,IFNULL(s.gross_wt,0) as gross_wt,IFNULL(s.wast_wt,0) as wast_wt,t.metal_type,(IFNULL(s.gross_wt,0)-IFNULL(s.dust_wt,0)-IFNULL(s.stone_wt,0)) as pure_wt,
       s.old_metal_rate,s.rate as rate,IFNULL(s.dust_wt,0) as dust_wt,IFNULL(s.stone_wt,0) as stone_wt,IFNULL(s.net_wt,0) as net_wt,
       DATE_FORMAT(b.bill_date,'%d-%m-%Y') as bill_date,b.bill_no,b.bill_id,s.esti_old_metal_sale_id
        FROM ret_bill_old_metal_sale_details s
        LEFT JOIN ret_estimation_old_metal_sale_details est ON est.old_metal_sale_id=s.esti_old_metal_sale_id
        LEFT JOIN ret_estimation e ON e.estimation_id=est.est_id
        LEFT JOIN ret_old_metal_type t ON t.id_metal_type=est.id_old_metal_type
        LEFT JOIN ret_billing b ON b.bill_id=s.bill_id
        WHERE s.is_transferred=1 and b.bill_status=1 and (date(b.bill_date) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."') and s.is_pocketed=0
        ".($id_old_metal_type!='' ? " and est.id_old_metal_type=".$id_old_metal_type."" :'')."
        ".($id_branch!='' && $id_branch>0 ? " and s.current_branch=".$id_branch."" :'')."");*/
        
        $result=$sql->result_array();
        foreach($result as $items)
        {
            $return_data[]=array(
                'id_old_metal_type' =>$items['id_old_metal_type'],
                'old_metal_sale_id' =>$items['old_metal_sale_id'],
                'gross_wt'          =>$items['gross_wt'],
                'stone_wt'          =>$items['stone_wt'],
                'dust_wt'           =>$items['dust_wt'],
                'net_wt'            =>$items['net_wt'],
                'metal_type'        =>$items['metal_type'],
                'wast_wt'           =>$items['wast_wt'],
                'pure_wt'           =>$items['pure_wt'],
                'old_metal_rate'    =>$items['old_metal_rate'],
                'rate'              =>$items['rate'],
                'bill_date'         =>$items['bill_date'],
                'bill_no'           =>$items['bill_no'],
                'bill_id'           =>$items['bill_id'],
                'is_checked'        =>0,
                'purity_per'        =>number_format((($items['rate']/$items['pure_wt'])/($items['old_metal_rate'])*100),2,'.',''),
            );
        }
        return $return_data;
        
    }
    
    function get_pocket_list($data)
    {
        $sql=$this->db->query("SELECT * FROM `ret_old_metal_pocket` WHERE (date(date) BETWEEN '".date('Y-m-d',strtotime($data['from_date']))."' AND '".date('Y-m-d',strtotime($data['to_date']))."') ");
        //print_r($this->db->last_query());exit;
        return $sql->result_array();
    }
    
    function get_pocket_details()
    {
        $sql=$this->db->query("SELECT * FROM `ret_old_metal_pocket` where status=0");
        return $sql->result_array();
    }
    
    
    function generate_process_number($process_for,$id_metal_process)
	{
		$lastno = $this->get_last_process_number($process_for,$id_metal_process);
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

	function get_last_process_number($process_for,$id_metal_process)
    {
		$sql = "SELECT process_no FROM ret_old_metal_process where process_for=".$process_for." and id_metal_process=".$id_metal_process." ORDER BY id_old_metal_process DESC LIMIT 1";
		return $this->db->query($sql)->row()->process_no;	
	}
	
	function ajax_get_metal_process()
	{
	    $sql=$this->db->query("SELECT p.id_old_metal_process,p.process_no,if(p.process_for=1,'ISSUE','RECEIPT') as process_for,m.process_name,k.firstname as karigar_name,date_format(p.created_on,'%d-%m-%Y') as date_add,emp.firstname as emp_name
        FROM ret_old_metal_process p 
        LEFT JOIN ret_old_metal_process_master m ON m.id_metal_process=p.id_metal_process
        LEFT JOIN ret_karigar k ON k.id_karigar=p.id_karigar
        LEFT JOIN employee emp ON emp.id_employee=p.created_by
        order by p.id_old_metal_process DESC");
        return $sql->result_array();
	}
	
	
	function get_metal_process($id_old_metal_process)
	{
	    $sql=$this->db->query("SELECT p.id_old_metal_process,p.id_metal_process,p.process_no,p.process_for,if(p.process_for=1,'ISSUE','RECEIPT') as process,m.process_name,k.firstname as karigar_name,date_format(p.created_on,'%d-%m-%Y') as date_add,emp.firstname as emp_name,
        cy.name as country_name,st.name as state_name,ct.name as city_name,k.pincode,IFNULL(k.address1,'') as address1,IFNULL(k.address2,'') as address2,
        IFNULL(k.address3,'') as address3
        FROM ret_old_metal_process p
        LEFT JOIN ret_old_metal_process_master m ON m.id_metal_process=p.id_metal_process 
        LEFT JOIN ret_karigar k ON k.id_karigar=p.id_karigar 
        LEFT JOIN country cy ON cy.id_country=k.id_country
        LEFT JOIN state st ON st.id_state=k.id_state
        LEFT JOIN city ct ON ct.id_city=k.id_city
        LEFT JOIN employee emp ON emp.id_employee=p.created_by 
        where id_old_metal_process=".$id_old_metal_process."");
        //print_r($this->db->last_query());exit;
        return $sql->row_array();
	}
	
	function get_melting_issue_details($id_old_metal_process)
	{
	    $sql=$this->db->query("SELECT p.pocket_no,p.gross_wt,p.net_wt,p.avg_purity,p.amount
        FROM ret_old_metal_melting_details d 
        LEFT JOIN ret_old_metal_melting m ON m.id_melting=d.id_melting
        LEFT JOIN ret_old_metal_pocket p ON p.id_metal_pocket=d.id_pocket
        WHERE m.id_old_metal_process=".$id_old_metal_process."");
        //print_r($this->db->last_query());exit;
        return $sql->result_array();
	}
	
	
	function get_melting_receipt_details($id_old_metal_process)
	{
	    $sql=$this->db->query("SELECT p.process_no,m.gross_wt,m.net_wt,d.received_wt,m.received_less_wt,m.receipt_charges
        FROM ret_old_metal_melting m 
        LEFT JOIN ret_old_metal_process p ON p.id_old_metal_process=m.id_old_metal_process
        LEFT JOIN (SELECT IFNULL(SUM(d.received_wt),0) as received_wt,d.id_melting
        FROM ret_old_metal_melting_recd_details d 
        GROUP by d.id_melting) as d ON d.id_melting=m.id_melting
        WHERE m.id_old_metal_process_receipt=".$id_old_metal_process."");
        //print_r($this->db->last_query());exit;
        return $sql->result_array();
	}
	
	function get_old_metal_process_payment($id_old_metal_process)
	{
	    $sql=$this->db->query("SELECT * FROM `ret_old_metal_process_payment` WHERE id_old_metal_process=".$id_old_metal_process."");
	    return $sql->result_array();
	}
	
	function getPocketingDetails($id_metal_pocket)
	{
	    $sql=$this->db->query("SELECT * FROM `ret_old_metal_pocket_details` WHERE id_metal_pocket=".$id_metal_pocket."");
	    return $sql->result_array();
	}
	
	function get_branch_details()
	{
	    $sql=$this->db->query("SELECT b.id_branch,d.entry_date
        FROM ret_day_closing d 
        LEFT JOIN branch b ON b.id_branch=d.id_branch
        WHERE b.is_ho=1");
	    return $sql->row_array();
	}
    
    
    function get_KarigarMeltingIssueDetilas($data)
    {
   
        $sql=$this->db->query("SELECT p.process_no,m.gross_wt,m.net_wt,m.amount,m.purity,p.id_old_metal_process,m.id_melting
        FROM ret_old_metal_melting m 
        LEFT JOIN ret_old_metal_process p ON p.id_old_metal_process=m.id_old_metal_process
        WHERE p.id_karigar=".$data['id_karigar']." AND m.melting_status=0");
        return $sql->result_array();
    }
    
    
    //Testing Issue
    function get_melting_details()
    {
        $sql=$this->db->query("SELECT m.id_melting,m.net_wt,m.amount,m.purity,d.received_wt,c.name as category_name,p.process_no,p.id_old_metal_process,d.id_melting_recd
        FROM ret_old_metal_melting m 
        LEFT JOIN ret_old_metal_melting_recd_details d on d.id_melting=m.id_melting
        LEFT JOIN ret_old_metal_process p ON p.id_old_metal_process=m.id_old_metal_process
        LEFT JOIN ret_category c ON c.id_ret_category=d.received_category
        WHERE d.melting_status=1");
        return $sql->result_array();
    }
    
    
    function get_testing_issue_details($id_old_metal_process)
    {
        $sql=$this->db->query("SELECT t.net_wt,t.purity,t.amount,p.process_no,d.received_category,cat.name as category_name
        FROM ret_old_metal_process p 
        LEFT JOIN ret_old_metal_testing t ON t.id_old_metal_process=p.id_old_metal_process 
        LEFT JOIN ret_old_metal_melting_recd_details d ON d.id_melting_recd=t.id_melting_recd
        LEFT JOIN ret_category cat ON cat.id_ret_category=d.received_category
        WHERE p.id_old_metal_process=".$id_old_metal_process."");
        //print_r($this->db->last_query());exit;
        return $sql->result_array();
    }
    
    function get_TestingReceiptAcknowladgement($id_old_metal_process)
    {
        $sql=$this->db->query("SELECT p.process_no,t.net_wt,t.amount,t.purity,t.received_wt,t.production_loss,t.received_purity,t.receipt_charges
        FROM ret_old_metal_testing t 
        LEFT JOIN ret_old_metal_process p ON p.id_old_metal_process=t.id_old_metal_process_receipt
        WHERE t.id_old_metal_process_receipt=".$id_old_metal_process."");
        return $sql->result_array();
    }
    
    function get_testing_receipt_details($data)
    {
        $sql=$this->db->query("SELECT t.id_melting_recd,t.net_wt,t.amount,t.purity,c.name as category_name,p.process_no,m.id_melting,t.id_metal_testing,IFNULL(d.received_category,'') as received_category,
        IFNULL(d.id_product,'') as id_product
        FROM ret_old_metal_testing t
        LEFT JOIN ret_old_metal_melting_recd_details d on d.id_melting_recd=t.id_melting_recd
        LEFT JOIN ret_old_metal_melting m on m.id_melting=d.id_melting
        LEFT JOIN ret_old_metal_process p ON p.id_old_metal_process=t.id_old_metal_process
        LEFT JOIN ret_category c ON c.id_ret_category=d.received_category
        WHERE d.melting_status=2 AND p.id_karigar=".$data['id_karigar']."");
        return $sql->result_array();
    }
    
    function check_purity_stock_details($purity,$id_ret_category,$id_product,$id_branch)
    {
        $sql=$this->db->query("SELECT * FROM `ret_purchase_item_stock_summary` WHERE id_branch=".$id_branch." 
        AND id_ret_category=".$id_ret_category." 
        AND id_product=".$id_product." 
        AND purity=".$purity."");
        
        $res = $this->db->query($sql);
        
		if($res->num_rows() > 0){
			$r = array("status" => TRUE,'id_stock_summary'=>$res->row()->id_stock_summary); 
		}else{
			$r = array("status" => FALSE); 
		} 
		return $r;
		
    }
    
    
    
    function updateStockItemData($id_stock_summary,$data,$arith){ 
		$sql = "UPDATE ret_purchase_item_stock_summary SET gross_wt=(gross_wt".$arith." ".$data['gross_wt']."),net_wt=(net_wt".$arith." ".$data['gross_wt']."),updated_by=".$data['updated_by'].",updated_on='".$data['updated_on']."' 
		WHERE id_stock_summary=".$id_stock_summary."  ";  
		$status = $this->db->query($sql);
		return $status;
	}
    
    //Testing Issue
    
    
    //refining issue
    function get_RefiningIssueDetails()
    {
        $sql=$this->db->query("SELECT p.process_no,t.received_wt,t.received_purity as purity,t.amount,c.name as category_name,m.id_melting,t.id_metal_testing,t.id_melting_recd
        
        FROM ret_old_metal_testing t
        LEFT JOIN ret_old_metal_melting_recd_details d on d.id_melting_recd=t.id_melting_recd
        LEFT JOIN ret_old_metal_melting m on m.id_melting=d.id_melting
        LEFT JOIN ret_category c ON c.id_ret_category=d.received_category
        LEFT JOIN ret_old_metal_process p ON p.id_old_metal_process=m.id_old_metal_process
        WHERE d.melting_status=3 and t.id_melting_recd IS NOT NULL");
        return $sql->result_array();
    }
    
    function get_RefiningReceiptDetails($data)
    {
        $sql=$this->db->query("SELECT p.process_no,c.name as category_name,t.received_wt as net_wt,t.received_purity as purity,p.id_old_metal_process,t.amount,
        m.id_melting,r.id_metal_refining,d.id_melting_recd
        FROM ret_old_metal_refining r 
        LEFT JOIN ret_old_metal_testing t ON t.id_metal_testing=r.id_metal_testing
        LEFT JOIN ret_old_metal_melting_recd_details d ON d.id_melting_recd=t.id_melting_recd
        LEFT JOIN ret_old_metal_melting m ON m.id_melting=d.id_melting
        LEFT JOIN ret_category c ON c.id_ret_category=d.received_category
        LEFT JOIN ret_old_metal_process p ON p.id_old_metal_process=r.id_old_metal_process
        WHERE p.process_for=1 AND d.melting_status=4 AND p.id_karigar=".$data['id_karigar']."");
        //print_r($this->db->last_query());exit;
         return $sql->result_array();
    }
    
    function get_RefiningIssueAcknowladgement($id_old_metal_process)
    {
        $sql=$this->db->query("SELECT p.process_no,c.name as category_name,t.received_wt,t.received_purity,p.id_old_metal_process,t.received_purity as purity,t.amount
        FROM ret_old_metal_process p 
        LEFT JOIN ret_old_metal_refining r ON r.id_old_metal_process=p.id_old_metal_process
        LEFT JOIN ret_old_metal_testing t ON t.id_metal_testing=r.id_metal_testing
        LEFT JOIN ret_old_metal_melting m ON m.id_melting=r.id_melting
        LEFT JOIN ret_category c ON c.id_ret_category=m.received_category
        WHERE p.process_for=1 AND p.id_metal_process=3
        and p.id_old_metal_process=".$id_old_metal_process."");
       // print_r($this->db->last_query());exit;
        return $sql->result_array();
        
    }
    
    function get_refiningReceiptAcknowladgement($id_old_metal_process)
    {
        $sql=$this->db->query("SELECT p.process_no,t.received_wt,t.received_purity,IFNULL(d.received_wt,0) as received_wt,
        cat.name as category_name
        FROM ret_old_metal_process p 
        LEFT JOIN ret_old_metal_refining r ON r.id_old_metal_process_receipt=p.id_old_metal_process
        LEFT JOIN ret_old_metal_testing t ON t.id_metal_testing=r.id_metal_testing
        LEFT JOIN ret_old_metal_melting_recd_details m on m.id_melting_recd=t.id_melting_recd
        LEFT JOIN ret_category cat ON cat.id_ret_category=m.received_category
        LEFT JOIN (SELECT IFNULL(SUM(d.received_wt),0) as received_wt,d.id_metal_refining
                  FROM ret_old_metal_refining_details d
                  GROUP by d.id_metal_refining) as d ON d.id_metal_refining=r.id_metal_refining
      WHERE p.id_old_metal_process=".$id_old_metal_process."");
      //print_r($this->db->last_query());exit;
      return $sql->result_array();
    }
    
    function checkPurchaseItemStockExist($id_product,$id_branch,$purity)
    {
        $sql=$this->db->query("SELECT * FROM `ret_purchase_item_stock_summary` WHERE id_branch=".$id_branch." AND id_product=".$id_product." AND purity=".$purity ."");
        if($sql->num_rows() > 0)
        {
            return TRUE;
        }else
        {
            return FALSE;
        }
    }
    
    function updatePurItemData($id_stock_summary,$data,$arith){ 
		$sql = "UPDATE ret_purchase_item_stock_summary SET gross_wt=(gross_wt".$arith." ".$data['gross_wt']."),net_wt=(net_wt".$arith." ".$data['gross_wt']."),updated_by=".$data['updated_by'].",updated_on='".$data['updated_on']."' WHERE id_stock_summary=".$id_stock_summary." ";  
		$status = $this->db->query($sql);
		return $status;
	}
    
    //refining issue
    
    
    
    //Polishing
    function get_PolishingReceiptDetails($data)
    {
        $sql=$this->db->query("SELECT p.process_no,r.gross_wt,r.net_wt,k.firstname as karigar_name,r.amount
        FROM ret_old_metal_process p 
        LEFT JOIN ret_old_metal_polishing r ON r.id_old_metal_process=p.id_old_metal_process
        LEFT JOIN ret_karigar k ON k.id_karigar=p.id_karigar
        WHERE p.process_for=1 AND r.status=0 and p.id_karigar=".$data['id_karigar']."");
        //print_r($this->db->last_query());exit;
         return $sql->result_array();
    }
    //Polishing
    
    function get_ActiveCategoryPurity()
    {
        $sql=$this->db->query("SELECT p.purity,p.id_purity,c.id_category
        FROM ret_metal_cat_purity c 
        LEFT JOIN ret_purity p ON p.id_purity=c.id_purity
        WHERE c.id_category IS NOT NULL AND p.id_purity IS NOT NULL");
        return $sql->result_array();
    }
    
    
    function getCategoryDetails($id_ret_category)
    {
        $sql=$this->db->query("SELECT * FROM ret_category WHERE id_ret_category=".$id_ret_category."");
        return $sql->row_array();
    }
    
    
    
    function checkStoneItemStockExist($id_category,$id_product,$id_branch)
    {
        $sql=$this->db->query("SELECT * FROM `ret_purchase_item_stock_summary` WHERE id_branch=".$id_branch." AND id_product=".$id_product." and id_ret_category=".$id_category." ");
        if($sql->num_rows() > 0)
        {
            return TRUE;
        }else
        {
            return FALSE;
        }
    }
    
    function updateStoneItemData($data,$arith){ 
		$sql = "UPDATE ret_purchase_item_stock_summary SET gross_wt=(gross_wt".$arith." ".$data['gross_wt']."),net_wt=(net_wt".$arith." ".$data['gross_wt']."),updated_by=".$data['updated_by'].",updated_on='".$data['updated_on']."' WHERE id_ret_category=".$data['id_ret_category']." and id_branch=".$data['id_product']." and id_product=".$data['id_product']."  ";  
		$status = $this->db->query($sql);
		return $status;
	}
	
	
	//Reports
	function get_metal_process_reports($data)
	{
	    $returnData=array();
	    $sql=$this->db->query("SELECT p.process_no,m.process_name,p.id_old_metal_process,p.process_for,m.process_code,k.firstname as karigar_name,date_format(p.date_add,'%d-%m-%Y') as issue_date
        FROM ret_old_metal_process p 
        LEFT JOIN ret_karigar k ON k.id_karigar=p.id_karigar
        LEFT JOIN ret_old_metal_process_master m ON m.id_metal_process=p.id_metal_process
        WHERE p.process_for=1
        AND (date(p.date_add) BETWEEN '".date('Y-m-d',strtotime($data['from_date']))."' AND '".date('Y-m-d',strtotime($data['to_date']))."')
        ".($data['id_karigar']!='' ? " and p.id_karigar=".$data['id_karigar']."" :'')."
        ".($data['id_metal_process']!='' ? " and p.id_metal_process=".$data['id_metal_process']."" :'')."
        ");
        
        $result=$sql->result_array();
        foreach($result as $items)
        {
            $process_details=[];
            if($items['process_code']=='MELTING')
            {
                $process_details = $this->get_melting_process_details($items['id_old_metal_process']);
            }
            else if($items['process_code']=='TESTING')
            {
                $process_details = $this->get_testing_process_details($items['id_old_metal_process']);
            }
            else if($items['process_code']=='REFINING')
            {
                $process_details = $this->get_refining_process_details($items['id_old_metal_process']);
            }
            $returnData[]=array(
                               'id_old_metal_process'   =>$items['id_old_metal_process'],
                               'issue_date'             =>$items['issue_date'],
                               'karigar_name'           =>$items['karigar_name'],
                               'process_no'             =>$items['process_no'],
                               'process_name'           =>$items['process_name'],
                               'process_for'            =>$items['process_for'],
                               'process_code'           =>$items['process_code'],
                               'issue_wt'               =>$process_details['issue_nwt'],
                               'received_wt'            =>$process_details['received_wt'],
                               'process_status'         =>$process_details['process_status'],
                               );
        }
        
        return $returnData;
	}
	
	function get_refining_process_details($id_old_metal_process)
	{
	    $sql=$this->db->query("SELECT t.received_wt as issue_nwt,if(r.refining_status=0,'Refining Issue','Refining Completed') as process_status,IFNULL(recd.received_wt,0) as received_wt
        FROM ret_old_metal_process p 
        LEFT JOIN ret_old_metal_refining r ON r.id_old_metal_process=p.id_old_metal_process
        LEFT JOIN ret_old_metal_testing t ON t.id_metal_testing=r.id_metal_testing
        LEFT JOIN(SELECT d.id_metal_refining,IFNULL(SUM(d.received_wt),0) as received_wt
        FROM ret_old_metal_refining_details d 
        GROUP by d.id_metal_refining) as recd ON recd.id_metal_refining=r.id_metal_refining");
        return $sql->row_array();
	}
	
	
	function get_testing_process_details($id_old_metal_process)
	{
	    $sql=$this->db->query("SELECT t.net_wt as issue_nwt,t.received_wt,IF(t.testing_status=0,'Testing Issue','Testing Completed') as process_status
        FROM ret_old_metal_process p
        LEFT JOIN ret_old_metal_testing t ON t.id_old_metal_process=p.id_old_metal_process 
        WHERE t.id_old_metal_process=".$id_old_metal_process."");
        return $sql->row_array();
	}
	
	
	function get_melting_process_details($id_old_metal_process)
    {
        $sql=$this->db->query("SELECT m.id_old_metal_process,m.gross_wt as issue_gwt,m.net_wt as issue_nwt,IFNULL(recd.received_wt,0) as received_wt,if(m.melting_status=0,'Melting Issue','Melting Completed') as process_status
        FROM ret_old_metal_melting m 
        LEFT JOIN(SELECT d.id_melting,IFNULL(SUM(d.received_wt),0) as received_wt,
        if(d.melting_status=0,'Melting Issue',if(d.melting_status=1,'Melting Completed',if(d.melting_status=2,'Testing Issue',if(d.melting_status=3,'Testing Completed',if(d.melting_status=4,'Refifing Issue',if(d.melting_status=5,'Refining Completed','')))))) as process_status
        FROM ret_old_metal_melting_recd_details d 
        GROUP by d.id_melting) as recd ON recd.id_melting=m.id_melting
        WHERE m.id_old_metal_process=".$id_old_metal_process."
        ");
        return $sql->row_array();
    }
	
	//Reports
	
	
	
}
?>
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
}
?>
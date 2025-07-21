<?php
if( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ret_lot_model extends CI_Model
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
	
	// Lot inward Functions	
	function ajax_getLotList($received_at,$from_date,$to_date)
    {
		$sql = ("SELECT i.lot_no,date_format(i.lot_date,'%d-%m-%Y') as lot_date,i.lot_type,i.lot_received_at,i.gold_smith,i.order_no,i.created_on,
		if(cat_code = '' or cat_code is null ,c.name ,CONCAT(cat_code,' - ',c.name) ) as category,b.name as received_branch
			FROM ret_lot_inwards i
			LEFT JOIN ret_category c on c.id_ret_category = i.id_category
			LEFT JOIN branch b on i.lot_received_at = b.id_branch");
		if($from_date!='' && ($received_at != '' && $received_at > 0))
		{
			$sql = $sql.( ' where i.lot_received_at = '.$received_at.' and (date(i.created_on) BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")');
		}
		else if(($received_at != '' && $received_at > 0) != '' || $from_date != '')
		{
			$sql = $sql." where ".($received_at != '' && $received_at > 0  ? ('i.lot_received_at ='.$received_at) : ($from_date != '' ? ' date(i.created_on) BETWEEN '.date('Y-m-d',strtotime($from_date)).' AND '.date('Y-m-d',strtotime($to_date)) : (''))) ;
		} 
		$r = $this->db->query($sql);
		$result['lots'] = $r->result_array();
		$r->free_result();
		
		$sql_uom  = $this->db->query("SELECT uom_id,uom_short_code FROM ret_uom");
		$result['uom'] = $sql_uom->result_array();
		return $result;
	}
	
	function empty_record_inward()
    {
    	$ho = NULL;
    	$settings = $this->get_ret_settings('lot_recv_branch');
    	if($settings == 1){ // HO Only
			$ho = $this->get_headOffice();
		} 
    	
		$data=array(
			'lot_no'				=> NULL,
			'lot_date'				=> date('d-m-Y'),
			'lot_type'				=> NULL,
			'lot_received_at'		=> $ho['id_branch'],
			'rcvd_branch_name'		=> $ho['name'],
			'lot_receive_settings'	=> $settings, 
			'gold_smith'			=> NULL,
			'order_no'				=> NULL,
			'lot_product'			=> NULL,
			'lot_id_design'			=> NULL,
			'no_of_piece'			=> NULL,
			'stock_type'			=> 1,
			'gross_wt'				=> NULL,
			'gross_wt_uom'			=> NULL,
			'net_wt'				=> NULL,
			'net_wt_uom'			=> NULL,
			'less_wt'				=> NULL,
			'less_wt_uom'			=> NULL,
			'precious_stone'		=> 0,
			'semi_precious_stone'	=> 0,
			'normal_stone'			=> 0,
			'precious_st_pcs'		=> NULL,
			'precious_st_wt'		=> NULL,
			'precious_st_uom'		=> NULL,
			'semi_precious_st_pcs'	=> NULL,
			'semi_precious_st_wt'	=> NULL,
			'semi_precious_wt_uom'	=> NULL,
			'normal_st_pcs'			=> NULL,
			'normal_st_wt'			=> NULL,
			'normal_st_wt'			=> NULL,
			'wastage_percentage'	=> NULL,
			'making_charge'			=> NULL,
			'mc_type'				=> 1, 
			'narration'				=> NULL,
			'lot_images'			=> NULL,
			'id_category'			=> NULL,
			'id_purity'				=> NULL,
		);
		return $data;
	}
	
	function get_lotInward($id)
    {
    	$ho = NULL;
    	
		$data = $this->db->query("SELECT i.stock_type,i.id_category,i.id_purity,i.lot_no,date_format(i.lot_date,'%d-%m-%Y') as lot_date,i.lot_type,i.lot_received_at,i.gold_smith,i.order_no,b.name as rcvd_branch_name,i.created_by,i.lot_images,i.narration
		FROM ret_lot_inwards i
		LEFT JOIN branch b on b.id_branch = i.lot_received_at
		WHERE i.lot_no=".$id);
		//print_r($this->db->last_query());exit;
		return $data->row_array();
	}

	function get_lotInward_detail($id)
    {
    	$ho = NULL;
    	
		$data = $this->db->query("SELECT id.lot_id_design,i.id_category,i.id_purity,i.lot_no,date_format(i.lot_date,'%d-%m-%Y') as lot_date,i.lot_type,i.lot_received_at,i.gold_smith,i.order_no,id.lot_product,id.no_of_piece,id.stock_type,id.gross_wt,id.net_wt,id.less_wt,id.precious_stone,id.semi_precious_stone,id.normal_stone,id.precious_st_pcs,id.precious_st_wt,id.semi_precious_st_pcs,id.semi_precious_st_wt,id.normal_st_pcs,id.normal_st_wt,id.wastage_percentage,id.making_charge,id.mc_type,i.narration,id.normal_st_certif,id.precious_st_certif,id.semiprecious_st_certif,id.normal_st_wt_uom,id.semi_precious_st_uom,id.precious_st_uom,id.net_wt_uom,id.gross_wt_uom,id.less_wt_uom,
		if(p.product_short_code = '' or p.product_short_code is null ,p.product_name ,CONCAT(p.product_short_code,' - ',p.product_name) ) as pro_name, p.product_short_code,
		o.orderno,i.lot_images,b.name as rcvd_branch_name,i.created_by,
		if(design_code = '' or design_code is null ,design_name ,CONCAT(design_code,' - ',design_name) ) as design,d.design_code,if(i.lot_type=1,'Normal Order',if(i.lot_type=2,'Customer Order','Repair Order')) as lt_type_select,c.name as category,pur.purity,concat(k.firstname,'',k.lastname) as lt_gold_smith,
		id.less_wt as lot_lwt,id.net_wt as lot_nwt,id.no_of_piece as lot_pcs
		FROM ret_lot_inwards i
		LEFT JOIN ret_lot_inwards_detail id on id.lot_no=i.lot_no
			LEFT JOIN ret_product_master p on id.lot_product = p.pro_id 
			LEFT JOIN ret_design_master d on d.design_no = id.lot_id_design
			LEFT JOIN customerorderdetails o on i.order_no = o.id_orderdetails 
			LEFT JOIN branch b on b.id_branch = i.lot_received_at
			LEFT JOIN ret_category c on c.id_ret_category=i.id_category
			LEFT JOIN ret_purity pur on pur.id_purity=i.id_purity
			LEFT JOIN ret_karigar k on k.id_karigar=i.gold_smith
		WHERE i.lot_no=".$id);
		//print_r($this->db->last_query());exit;
		return $data->result_array();
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
	
	
	
	  

}
?>
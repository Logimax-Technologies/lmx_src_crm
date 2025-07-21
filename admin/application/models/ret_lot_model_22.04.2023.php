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
/*	function ajax_getLotList($received_at,$from_date,$to_date)
    {
		$sql = ("SELECT i.lot_no,date_format(i.lot_date,'%d-%m-%Y') as lot_date,i.lot_type,i.lot_received_at,i.gold_smith,i.order_no,i.created_on,
		if(cat_code = '' or cat_code is null ,c.name ,CONCAT(cat_code,' - ',c.name) ) as category,b.name as received_branch,
		if(stock_type = 1, 'Tagged','Non-Tagged') as stock_type,k.firstname as karigar_name
			FROM ret_lot_inwards i
			LEFT JOIN ret_category c on c.id_ret_category = i.id_category
			LEFT JOIN ret_karigar k on k.id_karigar=i.gold_smith
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
	}*/
	
	
	function ajax_getLotList($from_date,$to_date)
    {
        $return_data=array();
		$sql = $this->db->query("SELECT SUM(lot.no_of_piece) as tot_pcs,SUM(lot.gross_wt) as gross_wt,SUM(lot.net_wt) as net_wt,
		p.product_name,i.lot_no,k.firstname as karigar_name,date_format(i.lot_date,'%d-%m-%Y') as lot_date,
		IFNULL(i.po_id,'') as po_id,IFNULL(if(i.lot_from=2,pur.po_ref_no,if(i.lot_from=5,r.process_no,'')),'') as pur_ref_no,
		if(i.lot_from=2,'Supplier Entry',if(i.lot_from=3,'From Import',if(i.lot_from=4,'From Tag Process',if(i.lot_from=5,'From Old Metal Process',if(i.lot_from=6,'From Retaggnig','Manual Lot'))))) as lotFrom,i.lot_from
		FROM ret_lot_inwards i
		LEFT JOIN ret_lot_inwards_detail lot on lot.lot_no=i.lot_no
		LEFT JOIN ret_product_master p ON p.pro_id=lot.lot_product
		LEFT JOIN ret_karigar k ON k.id_karigar=i.gold_smith
		LEFT JOIN ret_purchase_order pur ON pur.po_id=i.po_id
		LEFT JOIN ret_old_metal_process r ON r.id_old_metal_process = i.id_metal_process
		where (date(i.created_on) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')
		GROUP by lot.lot_no order by i.lot_no DESC");
		//print_r($this->db->last_query());exit;
	    $lot_det=$sql->result_array();
	    foreach($lot_det as $lot)
	    {
	        $return_data[]=array(
	                            'tot_pcs'      =>$lot['tot_pcs'],
	                            'gross_wt'     =>$lot['gross_wt'],
	                            'net_wt'       =>$lot['net_wt'],
	                            'product_name' =>$lot['product_name'],
	                            'lot_no'       =>$lot['lot_no'],
	                            'lot_date'     =>$lot['lot_date'],
	                            'karigar_name' =>$lot['karigar_name'],
	                            'pur_ref_no'   =>$lot['pur_ref_no'],
	                            'lotFrom'      =>$lot['lotFrom'],
	                            'lot_from'     =>$lot['lot_from'],
	                            'tag_det'      =>$this->getTaggedDetails($lot['lot_no']),
	                            'branch_wise'   =>$this->get_tagged_branchwise_details($lot['lot_no']) 
	                            );
	    }
		return $return_data;
	}
	
	function getTaggedDetails($lot_no)
	{
		$design_wise=$this->db->query("SELECT IFNULL(SUM(tag.piece),0)as tot_pcs,IFNULL(SUM(tag.gross_wt),0) as gross_wt,IFNULL(SUM(tag.net_wt),0) tot_nwt,
		p.product_name,d.design_name,pur.purity,tag.design_id,b.name as branch_name,sum(tag.sales_value) as sales_value,p.sales_mode,tag.current_branch as current_branch
		FROM ret_taging tag
		LEFT JOIN ret_product_master p ON p.pro_id=tag.product_id
		LEFT JOIN ret_design_master d on d.design_no=tag.design_id
		LEFT JOIN ret_purity pur ON pur.id_purity=tag.purity
		LEFT JOIN branch b on b.id_branch=tag.current_branch
		WHERE tag.tag_lot_id=".$lot_no."  and (tag.tag_status!=2 and tag.tag_status!=5 and tag.tag_status!=3)");
		$data=$design_wise->result_array();
		//print_r($this->db->last_query());exit;
		return $data;
	}
	
	function get_tagged_branchwise_details($lot_no)
	{
		$sql=$this->db->query("SELECT SUM(tag.piece) as tot_pcs,SUM(tag.gross_wt) as gross_wt,SUM(tag.net_wt) tot_nwt,
		p.product_name,d.design_name,pur.purity,tag.design_id,b.name as branch_name,sum(tag.sales_value) as sales_value,p.sales_mode,tag.current_branch as current_branch,tag.tag_lot_id
		FROM ret_taging tag
		LEFT JOIN ret_product_master p ON p.pro_id=tag.product_id
		LEFT JOIN ret_design_master d on d.design_no=tag.design_id
		LEFT JOIN ret_purity pur ON pur.id_purity=tag.purity
		LEFT JOIN branch b on b.id_branch=tag.current_branch
		WHERE tag.tag_lot_id=".$lot_no."  and (tag.tag_status!=2 and tag.tag_status!=5 and tag.tag_status!=3)
		GROUP by tag.current_branch");
		$data=$sql->result_array();
		return $data;
	}
	
	function empty_record_inward()
    {
    	$ho = NULL;
    	$settings = $this->get_ret_settings('lot_recv_branch');
    	$loggedinbranch = $this->session->userdata('id_branch');
    	
    	if($settings == 1 && empty($loggedinbranch)){ // HO Only
			$ho = $this->get_branchName("HO");
		}else{
		   $ho = $this->get_branchName($loggedinbranch); 
		   $settings = 2;
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
			'buy_rate'				=> NULL,
			'sell_rate'				=> NULL,
			
		);
		/*echo "<pre>";
		print_r($data);
		echo "</pre>";exit;*/
		return $data;
	}
	function get_lotInward($id)
    {
    	$ho = NULL;
		$data = $this->db->query("SELECT i.stock_type,i.id_category,i.id_purity,i.lot_no,date_format(i.lot_date,'%d-%m-%Y') as lot_date,i.lot_type,i.lot_received_at,i.gold_smith,i.order_no,b.name as rcvd_branch_name,i.created_by,i.lot_images,i.narration,
		i.product_division
		FROM ret_lot_inwards i
		LEFT JOIN branch b on b.id_branch = i.lot_received_at
		WHERE i.lot_no=".$id);
		//print_r($this->db->last_query());exit;
		return $data->row_array();
	}
    function get_lotInward_detail($id)
    {
    	$ho = NULL;
		$data = $this->db->query("SELECT id.lot_id_design,i.id_category,i.id_purity,i.lot_no,date_format(i.lot_date,'%d-%m-%Y') as lot_date,i.lot_type,i.lot_received_at,i.gold_smith,ifnull(i.order_no,'-') as order_no,id.lot_product,id.no_of_piece,if(i.stock_type = 2,'Non-Tagged','Tagged') as stock_type,id.gross_wt,id.net_wt,id.less_wt,id.precious_stone,id.semi_precious_stone,id.normal_stone,id.precious_st_pcs,id.precious_st_wt,id.semi_precious_st_pcs,id.semi_precious_st_wt,id.normal_st_pcs,id.normal_st_wt,id.wastage_percentage,id.making_charge,id.mc_type,i.narration,id.normal_st_certif,id.precious_st_certif,id.semiprecious_st_certif,id.normal_st_wt_uom,id.semi_precious_st_uom,id.precious_st_uom,id.net_wt_uom,id.gross_wt_uom,id.less_wt_uom,id_lot_inward_detail,
		if(p.product_short_code = '' or p.product_short_code is null ,p.product_name ,CONCAT(p.product_name,' - ',p.product_short_code) ) as pro_name, p.product_short_code,
		o.orderno,i.lot_images,b.name as rcvd_branch_name,i.created_by,
		if(design_code = '' or design_code is null ,design_name ,CONCAT(design_name,' - ',design_code) ) as design,d.design_code,if(i.lot_type=1,'Normal Order',if(i.lot_type=2,'Customer Order','Repair Order')) as lt_type_select,c.name as category,pur.purity,k.firstname as lt_gold_smith,
		id.less_wt as lot_lwt,id.net_wt as lot_nwt,id.no_of_piece as lot_pcs,buy_rate,sell_rate,sales_mode,id.size,id.design_for,calculation_based_on
		FROM ret_lot_inwards i
		LEFT JOIN ret_lot_inwards_detail id on id.lot_no=i.lot_no
			LEFT JOIN ret_product_master p on id.lot_product = p.pro_id 
			LEFT JOIN ret_design_master d on d.design_no = id.lot_id_design
			LEFT JOIN customerorderdetails o on i.order_no = o.id_orderdetails 
			LEFT JOIN branch b on b.id_branch = i.lot_received_at
			LEFT JOIN ret_category c on c.id_ret_category=i.id_category
			LEFT JOIN ret_purity pur on pur.id_purity=i.id_purity
			LEFT JOIN ret_karigar k on k.id_karigar=i.gold_smith
		WHERE id.tag_status=0 and i.lot_no=".$id);
		//print_r($this->db->last_query());exit;
		return $data->result_array();
	}
	function get_ret_settings($settings)
	{
		$data=$this->db->query("SELECT value FROM ret_settings where name='".$settings."'");
		return $data->row()->value;
	}
	function get_branchName($branch)
	{
	    if($branch == "HO"){
		    $data   =   $this->db->query("SELECT b.id_branch, b.is_ho, b.id_branch, name FROM branch b where b.is_ho = 1");
	    }else{
	        $data   =   $this->db->query("SELECT b.id_branch, b.is_ho, b.id_branch, b.name FROM branch b where b.id_branch = $branch");
	    }
		return $data->row_array();
	}
	function lotInward_detail($id)
    {
    	$ho = NULL;
		$data = $this->db->query("SELECT id.lot_id_design,i.id_category,i.id_purity,i.lot_no,date_format(i.lot_date,'%d-%m-%Y') as lot_date,i.lot_type,i.lot_received_at,i.gold_smith,ifnull(i.order_no,'-') as order_no,id.lot_product,id.no_of_piece,if(i.stock_type = 2,'Non-Tagged','Tagged') as stock_type,id.gross_wt,id.net_wt,id.less_wt,id.precious_stone,id.semi_precious_stone,id.normal_stone,id.precious_st_pcs,id.precious_st_wt,id.semi_precious_st_pcs,id.semi_precious_st_wt,id.normal_st_pcs,id.normal_st_wt,id.wastage_percentage,id.making_charge,id.mc_type,i.narration,id.normal_st_certif,id.precious_st_certif,id.semiprecious_st_certif,id.normal_st_wt_uom,id.semi_precious_st_uom,id.precious_st_uom,id.net_wt_uom,id.gross_wt_uom,id.less_wt_uom,id_lot_inward_detail,
		if(p.product_short_code = '' or p.product_short_code is null ,p.product_name ,CONCAT(p.product_name,' - ',p.product_short_code) ) as pro_name, p.product_short_code,
		o.orderno,i.lot_images,b.name as rcvd_branch_name,i.created_by,
		if(design_code = '' or design_code is null ,design_name ,CONCAT(design_name,' - ',design_code) ) as design,d.design_code,if(i.lot_type=1,'Normal Order',if(i.lot_type=2,'Customer Order','Repair Order')) as lt_type_select,c.name as category,pur.purity,k.firstname as lt_gold_smith,
		id.less_wt as lot_lwt,id.net_wt as lot_nwt,id.no_of_piece as lot_pcs,buy_rate,sell_rate,concat(e.firstname,'-',e.emp_code) as emp_name
		FROM ret_lot_inwards i
		LEFT JOIN ret_lot_inwards_detail id on id.lot_no=i.lot_no
			LEFT JOIN ret_product_master p on id.lot_product = p.pro_id 
			LEFT JOIN ret_design_master d on d.design_no = id.lot_id_design
			LEFT JOIN customerorderdetails o on i.order_no = o.id_orderdetails 
			LEFT JOIN branch b on b.id_branch = i.lot_received_at
			LEFT JOIN ret_category c on c.id_ret_category=i.id_category
			LEFT JOIN ret_purity pur on pur.id_purity=i.id_purity
			LEFT JOIN ret_karigar k on k.id_karigar=i.gold_smith
			LEFT JOIN employee e on e.id_employee=i.created_by
		WHERE i.lot_no=".$id);
		//print_r($this->db->last_query());exit;
		return $data->result_array();
	}
	
	function get_lot_details($lot_no)
	{
		
		/*$product_wise=$this->db->query("SELECT SUM(lot.no_of_piece) as tot_pcs,SUM(lot.gross_wt) as gross_wt,SUM(lot.net_wt) as net_wt,
		p.product_name,pur.purity
		FROM ret_lot_inwards i
		LEFT JOIN ret_lot_inwards_detail lot on lot.lot_no=i.lot_no
		LEFT JOIN ret_product_master p ON p.pro_id=lot.lot_product
		LEFT JOIN ret_purity pur ON pur.id_purity=i.id_purity
		where i.lot_no=".$lot_no."
		GROUP by lot.lot_product");
		$data['product_wise']=$product_wise->result_array();*/

		$design_wise=$this->db->query("SELECT SUM(lot.no_of_piece) as tot_pcs,SUM(lot.gross_wt) as gross_wt,SUM(lot.net_wt) as net_wt,
		p.product_name,pur.purity,d.design_name,IFNULL(SUM(lot.less_wt),0) as less_wt
		FROM ret_lot_inwards i
		LEFT JOIN ret_lot_inwards_detail lot on lot.lot_no=i.lot_no
		LEFT JOIN ret_product_master p ON p.pro_id=lot.lot_product
		LEFT JOIN ret_design_master d ON d.design_no=lot.lot_id_design
		LEFT JOIN ret_purity pur ON pur.id_purity=i.id_purity
		where i.lot_no=".$lot_no." 
		GROUP by lot.lot_product");
		$data['design_wise']=$design_wise->result_array();

		//print_r($this->db->last_query());exit;
		return $data;
	}
	
	function get_lot_tag_details($lot_no)
	{
	    $data=array();
		$design_wise=$this->db->query("SELECT SUM(tag.piece) as tot_pcs,SUM(tag.gross_wt) as gross_wt,SUM(tag.net_wt) tot_nwt,IFNULL(SUM(tag.less_wt),0) tot_less_wt,
		p.product_name,d.design_name,pur.purity,tag.design_id,b.name as branch_name,sum(tag.sales_value) as sales_value,p.sales_mode,tag.current_branch,IFNULL(stn.dia_wt,0) as dia_wt
		FROM ret_taging tag
		LEFT JOIN ret_product_master p ON p.pro_id=tag.product_id
		LEFT JOIN ret_design_master d on d.design_no=tag.design_id
		LEFT JOIN ret_purity pur ON pur.id_purity=tag.purity
		LEFT JOIN branch b on b.id_branch=tag.current_branch
		
        LEFT JOIN(SELECT st.tag_id,IFNULL(SUM(st.wt),0) as dia_wt,t.product_id
          FROM ret_taging_stone st 
          LEFT JOIN ret_taging t ON t.tag_id=st.tag_id 
          LEFT JOIN ret_stone s ON s.stone_id=st.stone_id
          WHERE s.stone_type=1 AND t.tag_lot_id=".$lot_no." GROUP by t.current_branch,t.product_id) as stn ON stn.product_id=tag.product_id
        
		WHERE tag.tag_lot_id=".$lot_no." and (tag.tag_status!=5 and tag.tag_status!=2 and tag.tag_status!=3)
		GROUP by tag.current_branch,tag.product_id");
		
		$design=$design_wise->result_array();
		foreach($design as $items)
		{
		    $data['design_wise'][$items['branch_name']][] = $items; 
		
		}
		return $data;
	}
	

	
	function get_tag_details($lot_no)
	{
	
		$design_wise=$this->db->query("SELECT SUM(lot.no_of_piece) as tot_pcs,SUM(lot.gross_wt) as gross_wt,SUM(lot.net_wt) as net_wt,
		p.product_name,pur.purity,d.design_name
		FROM ret_lot_inwards i
		LEFT JOIN ret_lot_inwards_detail lot on lot.lot_no=i.lot_no
		LEFT JOIN ret_product_master p ON p.pro_id=lot.lot_product
		LEFT JOIN ret_design_master d ON d.design_no=lot.lot_id_design
		LEFT JOIN ret_purity pur ON pur.id_purity=i.id_purity
		where i.lot_no=".$lot_no."
		GROUP by lot.lot_product,lot.lot_id_design");
		$data['design_wise']=$design_wise->result_array();

		//print_r($this->db->last_query());exit;
		return $data;
	}
	
	function get_branch_summary($tag_lot_id,$id_branch)
	{
		$sql = $this->db->query("SELECT sum(tag.piece) as piece,sum(tag.gross_wt) as gross_wt,sum(tag.net_wt) as net_wt,p.product_name,d.design_name,
		        IFNULL(SUM(tag.sales_value),0) as tot_sales_value
				FROM ret_taging as tag 
				LEFT JOIN ret_product_master p ON p.pro_id=tag.product_id
		        LEFT JOIN ret_design_master d ON d.design_no=tag.design_id
				left join branch b on b.id_branch=tag.current_branch
				WHERE tag_lot_id='".$tag_lot_id."' and (tag.tag_status!=2 and tag.tag_status!=5 and tag.tag_status!=3)
				".($id_branch!='' ? " and tag.current_branch=".$id_branch."" :'')."
				group by tag.product_id");
			//print_r($this->db->last_query());exit;
		return $sql->result_array();
	}
	
	
	function get_tagdetails_by_lot($tag_lot_id,$id_branch)
	{
	    $data=array();
		$sql = $this->db->query("SELECT tag.tag_id,tag.tag_code,tag.counter,
				date_format(tag.tag_datetime,'%d-%m-%Y') as tag_datetime, 
				tag.tag_type, tag.tag_lot_id, tag.cost_center, 
				tag.purity, tag.size, tag.uom, tag.piece, tag.less_wt, tag.net_wt, tag.gross_wt, 
				tag.calculation_based_on, tag.retail_max_wastage_percent,
				tag.tag_mc_type, tag.tag_mc_value,
				tag.retail_max_mc, tag.halmarking, tag.sales_value,tag.image,
				tag.current_branch, tag.current_counter,
			ifnull(design_code,'') as design_code ,tag.created_by,des.design_name,b.name,
				d.id_lot_inward_detail,d.lot_product,d.lot_id_design,pro.product_name,
				b.name as branch_name,subDes.sub_design_name
				FROM ret_taging as tag 
				LEFT JOIN ret_design_master as des ON des.design_no = tag.design_id 
				LEFT join ret_lot_inwards_detail d on d.id_lot_inward_detail=tag.id_lot_inward_detail
			    LEFT JOIN ret_product_master pro on pro.pro_id=tag.product_id
			    LEFT JOIN ret_sub_design_master subDes ON subDes.id_sub_design=tag.id_sub_design
				left join branch b on b.id_branch=tag.current_branch
				WHERE tag_lot_id='".$tag_lot_id."'  and (tag.tag_status!=2 and tag.tag_status!=5 and tag.tag_status!=3)
				".($id_branch!='' ? " and tag.current_branch=".$id_branch."" :'')."
				order by tag.current_branch ASC");
			//print_r($this->db->last_query());exit;
		$design= $sql->result_array();
		foreach($design as $items)
		{
		    $data['design_wise'][$items['product_name']][] = $items; 
		
		}
		return $data;
	}

	function getOrderNos($SearchTxt)
	{
            $data = $this->db->query("SELECT concat(c.order_no,'-',b.short_name) as label,c.id_customerorder as value,c.order_no as order_no,
            c.order_from
			FROM customerorder c
			LEFT JOIN branch b on b.id_branch=c.order_from
			WHERE order_no like '%".$SearchTxt."%'");	
			return $data->result_array();
	}

	function get_order_details($orderno,$id_karigar,$id_branch)
	{
		$sql=$this->db->query("SELECT c.order_no,SUM(d.wast_percent) as wastage_per,SUM(d.mc) as max_mc,SUM(d.stn_amt) as stone_amt,SUM(d.weight) as tot_net_wt,SUM(d.size) as tot_size,SUM(d.totalitems) as tot_pcs,d.id_product,d.design_no,
		p.product_name,des.design_name,p.sales_mode,p.calculation_based_on,d.mc as making_charge,d.id_mc_type
			FROM customerorder c
			LEFT JOIN customerorderdetails d on d.id_customerorder=c.id_customerorder
			LEFT JOIN ret_product_master p on p.pro_id=d.id_product
			LEFT JOIN ret_design_master des on des.design_no=d.design_no
			LEFT JOIN joborder j on j.id_order=d.id_orderdetails
			WHERE c.order_no='".$orderno."' and j.id_vendor=".$id_karigar."
			".($id_branch!='' ? " and c.order_from=".$id_branch."" :'')."
			GROUP by d.id_product,d.design_no,j.id_vendor");
			
		return $sql->result_array();
	}
	
	function get_karigar_list($order_no)
	{
		$return_data=array("karigar"=>array(),"category"=>array(),"purity"=>array());
		$sql=$this->db->query("SELECT k.id_karigar,k.firstname as karigar,k.code_karigar as code
		FROM customerorder c
		LEFT JOIN customerorderdetails ord on ord.id_customerorder=c.id_customerorder
		LEFT JOIN joborder j on j.id_order=ord.id_orderdetails
		LEFT JOIN ret_karigar k on k.id_karigar=j.id_vendor
		WHERE j.id_order is NOT null and c.order_no='".$order_no."'
		GROUP by j.id_vendor");
		$return_data['karigar']=$sql->result_array();

		$category=$this->db->query("SELECT cat.id_ret_category,cat.name,cat.id_metal,cat.image,cat.description
		FROM customerorder c
		LEFT JOIN customerorderdetails ord on ord.id_customerorder=c.id_customerorder
		LEFT JOIN ret_product_master p ON p.pro_id=ord.id_product
		LEFT JOIN ret_category cat on cat.id_ret_category=p.cat_id
		where c.order_no='".$order_no."'
		GROUP by cat.id_ret_category");
		$return_data['category']=$category->result_array();

		$purity=$this->db->query("SELECT p.id_purity,p.purity
		FROM customerorder c
		LEFT JOIN customerorderdetails ord on ord.id_customerorder=c.id_customerorder
		LEFT JOIN ret_purity p on p.id_purity=ord.id_purity
		where c.order_no='".$order_no."'
		GROUP by ord.id_purity");
		$return_data['purity']=$purity->result_array();

		return $return_data;
	}
	
	function getProductBySearch($SearchTxt,$category,$stock_type)
    {
        $data=array();
        if($SearchTxt!='')
        {
            $products = $this->db->query("SELECT pro_id as value,
            concat(product_name,'-',product_short_code) as label,
            wastage_type, other_materials, has_stone,sales_mode,
            has_hook, has_screw, has_fixed_price,
            has_size, less_stone_wt, no_of_pieces,metal_type, calculation_based_on,group_concat(m.tax_percentage) as tax_percentage,GROUP_CONCAT(i.tgi_calculation) as tgi_calculation,
            mt.id_metal,mt.tgrp_id as tax_group_id
            FROM ret_product_master as pro
	            left join ret_category c on c.id_ret_category=pro.cat_id
	            left join metal mt on mt.id_metal=c.id_metal
	            left join ret_taxgroupitems i on i.tgi_tgrpcode=mt.tgrp_id
	            left join ret_taxmaster m on m.tax_id=i.tgi_taxcode
			WHERE (product_short_code LIKE '%".$SearchTxt."%' OR product_name LIKE '%".$SearchTxt."%') 
			".(empty($category) ? ' ' : 'AND pro.cat_id ='.$category)."
			".($stock_type!='' ? " and pro.stock_type=".$stock_type."" :'')."
			GROUP BY pro.pro_id");
			// print_r($this->db->last_query());exit;
            $data=$products->result_array();
        }
        return $data;
    }
    
    function checkNonTagItemExist($data){
		$r = array("status" => FALSE);
        $sql = "SELECT id_nontag_item 
        FROM ret_nontag_item 
        WHERE product =".$data['id_product']." 
        ".($data['id_design']!='' ? " and design=".$data['id_design']."" :'')."
        ".($data['id_sub_design']!='' ? " and id_sub_design=".$data['id_sub_design']."" :'')."
        AND branch=".$data['id_branch']; 		
        $res = $this->db->query($sql);
		
		if($res->num_rows() > 0){
			$r = array("status" => TRUE, "id_nontag_item" => $res->row()->id_nontag_item); 
		}else{
			$r = array("status" => FALSE, "id_nontag_item" => ""); 
		} 
		return $r;
	}
	
	function updateNTData($data,$arith){ 
		$sql = "UPDATE ret_nontag_item SET no_of_piece=(no_of_piece".$arith." ".$data['no_of_piece']."),gross_wt=(gross_wt".$arith." ".$data['gross_wt']."),net_wt=(net_wt".$arith." ".$data['net_wt']."),updated_by=".$data['updated_by'].",updated_on='".$data['updated_on']."' WHERE id_nontag_item=".$data['id_nontag_item'];  
		$status = $this->db->query($sql);
		return $status;
	}
	
	function getProductDivision() {
		$prod_division = $this->db->query("select * FROM ret_product_division where status=1");
		return $prod_division->result_array();
	}


	function getLotNoForMerge($data)
	{
		$sql = $this->db->query("SELECT l.lot_no,l.id_category,lt.id_lot_inward_detail,
		lt.lot_product,pro.product_name,
		sum(lt.no_of_piece) as no_of_piece,sum(lt.gross_wt) as gross_wt,sum(lt.net_wt) as net_wt,
		dia_stn.stone_id as dia_stn_id,ifnull(dia_stn.stn_pcs,0) as dia_pcs,ifnull(dia_stn.stn_wt,0) as dia_wt,
		nrml_stn.stone_id as nrml_stn_id,ifnull(nrml_stn.stn_pcs,0) as stn_pcs,ifnull(nrml_stn.stn_wt,0) as stn_wt,
		lt_tag.tag_id
		FROM ret_lot_inwards l
		LEFT JOIN ret_lot_inwards_detail lt on lt.lot_no = l.lot_no
		LEFT JOIN ret_product_master pro on pro.pro_id = lt.lot_product
		
		LEFT JOIN(SELECT lot_stn.stone_id,lot_stn.uom_id,lot_stn.id_lot_inward_detail,
				 sum(lot_stn.stone_pcs) as stn_pcs,sum(lot_stn.stone_wt) as stn_wt
				 FROM ret_lot_inwards_stone_detail lot_stn
				 LEFT JOIN ret_uom u on u.uom_id = lot_stn.uom_id
				 WHERE lot_stn.uom_id=6
		GROUP BY lot_stn.id_lot_inward_detail) as dia_stn on dia_stn.id_lot_inward_detail = lt.id_lot_inward_detail

		LEFT JOIN(SELECT lot_stn.stone_id,lot_stn.uom_id,lot_stn.id_lot_inward_detail,
				 sum(lot_stn.stone_pcs) as stn_pcs,sum(lot_stn.stone_wt) as stn_wt
				 FROM ret_lot_inwards_stone_detail lot_stn
				 LEFT JOIN ret_uom u on u.uom_id = lot_stn.uom_id
				 WHERE lot_stn.uom_id=1
		GROUP BY lot_stn.id_lot_inward_detail) as nrml_stn on nrml_stn.id_lot_inward_detail = lt.id_lot_inward_detail

		LEFT JOIN (SELECT tag.tag_lot_id,tag.tag_id FROM ret_taging tag 
				   LEFT JOIN ret_lot_inwards_detail lt_inw on lt_inw.lot_no = tag.tag_lot_id) as lt_tag on lt_tag.tag_lot_id = l.lot_no

		LEFT JOIN (SELECT lm.id_lot_inward_detail FROM ret_lot_merge lm 
		           LEFT JOIN ret_lot_inwards_detail lt on lt.id_lot_inward_detail = lm.id_lot_inward_detail) as lt_m on lt_m.id_lot_inward_detail = lt.id_lot_inward_detail

		LEFT JOIN (SELECT lm.lot_no FROM ret_lot_merge lm 
		           LEFT JOIN ret_lot_inwards lt on lt.lot_no = lm.lot_no) as lm_lot on lm_lot.lot_no = l.lot_no

		WHERE lt.lot_product is not null 
		and lt_tag.tag_lot_id is null and lt_m.id_lot_inward_detail is null 
		and lm_lot.lot_no is null
		and l.lot_no = ".$data['lot_no']." and l.stock_type = ".$data['stock_type']."
		GROUP BY lt.lot_product");
		//print_r($this->db->last_query());exit;
		$lot_det = $sql->result_array();

		foreach($lot_det as $itm)
		{	
			$lot_merge[] = array(

				'lot_no'                  =>  $itm['lot_no'],
				'id_lot_inward_detail'    =>  $itm['id_lot_inward_detail'],
				'lot_product'             =>  $itm['lot_product'],
				'product_name'            =>  $itm['product_name'],
				'no_of_piece'             =>  $itm['no_of_piece'],
				'gross_wt'                =>  $itm['gross_wt'],
				'net_wt'                  =>  $itm['net_wt'],
				'dia_pcs'                 =>  $itm['dia_pcs'],
				'dia_wt'                  =>  $itm['dia_wt'],
				'stn_pcs'                 =>  $itm['stn_pcs'],
				'stn_wt'                  =>  $itm['stn_wt'],
				'stone_details'           =>  $this->get_stone_details_lot($itm['id_lot_inward_detail']),
			);

		}

		return $lot_merge;
	}

	function get_stone_details_lot($id_lot_inward_detail)
	{
		$sql = $this->db->query("SELECT lot_stn.stone_id,lot_stn.uom_id,lot_stn.id_lot_inward_detail,
		lot_stn.stone_pcs as stn_pcs,lot_stn.stone_wt as stn_wt,stn.stone_name,u.uom_short_code
		FROM ret_lot_inwards_stone_detail lot_stn
		LEFT JOIN ret_uom u on u.uom_id = lot_stn.uom_id
		LEFT JOIN ret_stone stn on stn.stone_id = lot_stn.stone_id
		WHERE lot_stn.id_lot_inward_detail =".$id_lot_inward_detail."");
		return $sql->result_array();
	}


	function get_ActiveProduct($data)
	{
	    $sql = $this->db->query("SELECT * FROM `ret_product_master` WHERE 
		product_status = 1 
		".($data['id_category']!='' && $data['id_category']>0 ? "and cat_id = ".$data['id_category']."":"")."");
		//print_r($this->db->last_query());exit;
	    return $sql->result_array();
	}


	function getLotNoForSplit($data)
	{
		$lot_split = array();

		$sql = $this->db->query("SELECT lt.lot_no,ltd.id_lot_inward_detail,lt.stock_type,cat.id_ret_category,
		
		ltd.lot_id_purity,ltd.lot_product,cat.name as category,pur.purity,pro.product_name,

		ltd.no_of_piece as lot_pcs,ltd.gross_wt as lot_wt,
		 
		(ltd.no_of_piece - ifnull(ls.split_pcs,0)) as bal_piece,
		
		(ltd.gross_wt - ifnull(ls.split_grs_wt,0)) as bal_gross_wt,
		
		ifnull(sum(lot_stn.stone_wt),0) as less_wt
		
		FROM ret_lot_inwards lt

		LEFT JOIN ret_lot_inwards_detail ltd on ltd.lot_no = lt.lot_no

		LEFT JOIN ret_product_master pro on pro.pro_id = ltd.lot_product

		LEFT JOIN ret_category cat on cat.id_ret_category = pro.cat_id

		LEFT JOIN ret_purity pur on pur.id_purity = ltd.lot_id_purity

		LEFT JOIN (SELECT lsd.id_lot_inward_detail,sum(lsd.split_pcs) as split_pcs,sum(lsd.split_grs_wt) as split_grs_wt
                  FROM ret_lot_split_details lsd 
                  GROUP by lsd.id_lot_inward_detail) as ls on ls.id_lot_inward_detail = ltd.id_lot_inward_detail

		LEFT JOIN ret_lot_inwards_stone_detail lot_stn on lot_stn.id_lot_inward_detail = ltd.id_lot_inward_detail

		LEFT JOIN (SELECT tag.tag_lot_id,tag.tag_id FROM ret_taging tag 
		LEFT JOIN ret_lot_inwards_detail lt_inw on lt_inw.lot_no = tag.tag_lot_id) as lt_tag on lt_tag.tag_lot_id = lt.lot_no

		WHERE lt_tag.tag_lot_id is null 
		
		and lt.lot_no = ".$data['lot_no']."
		
		GROUP BY ltd.id_lot_inward_detail
		
		Having bal_piece > 0");

		//print_r($this->db->last_query());exit;

		$data = $sql->result_array();

		foreach($data as $val)
		{
			$lot_split[] = array(

				'lot_no'                 =>  $val['lot_no'],

				'id_lot_inward_detail'   =>  $val['id_lot_inward_detail'],

				'id_category'            =>  $val['id_ret_category'],

				'id_purity'              =>  $val['lot_id_purity'],

				'lot_product'            =>  $val['lot_product'],

				'category'              =>  $val['category'],

				'purity'                =>  $val['purity'],

				'product_name'          =>  $val['product_name'],

				'lot_pcs'               =>  $val['lot_pcs'],

				'lot_wt'                =>  $val['lot_wt'],

				'bal_piece'             =>  $val['bal_piece'],

				'bal_gross_wt'          =>  $val['bal_gross_wt'],

				'less_wt'              =>  $val['less_wt'],

				'stone_details'         =>  $this->get_stone_details_for_lotSplit($val['id_lot_inward_detail']),

			);
		}

		return $lot_split;
	}

	function get_stone_details_for_lotSplit($id_lot_inward_detail)
	{
		$sql = $this->db->query("SELECT lot_stn.id_stn_detail,lot_stn.stone_id,
		
		lot_stn.uom_id,lot_stn.id_lot_inward_detail,
		
		(lot_stn.stone_pcs - ifnull(split_stn.split_stn_pcs,0)) as stn_pcs,
		
		(lot_stn.stone_wt - ifnull(split_stn.split_stn_wt,0)) as stn_wt,
		
		stn.stone_name,u.uom_short_code
		
		FROM ret_lot_inwards_stone_detail lot_stn
		
		LEFT JOIN ret_uom u on u.uom_id = lot_stn.uom_id
		
		LEFT JOIN ret_stone stn on stn.stone_id = lot_stn.stone_id

		LEFT JOIN (SELECT ls.id_stn_detail,sum(ls.stone_pcs) as split_stn_pcs,sum(ls.stone_wt) as split_stn_wt
		FROM ret_lot_split_stone_details ls
		GROUP by ls.id_stn_detail) as split_stn on split_stn.id_stn_detail = lot_stn.id_stn_detail
		
		WHERE lot_stn.id_lot_inward_detail =".$id_lot_inward_detail."
		HAVING stn_pcs > 0");
		//print_r($this->db->last_query());exit;
		return $sql->result_array();
	}
    

}
?>
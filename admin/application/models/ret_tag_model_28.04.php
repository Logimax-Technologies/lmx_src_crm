<?php
if( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ret_tag_model extends CI_Model
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
	
	
	function ajax_getTaggingList($from_date,$to_date)
    { 
		 $sql = ("SELECT tag.tag_id,tag_code,
					date_format(tag_datetime,'%d-%m-%Y') as tag_date,
					tag_type, tag_lot_id, design_id, cost_center, purity,
					size, uom, piece, tag.less_wt, tag.net_wt, tag.gross_wt,
					calculation_based_on, retail_max_wastage_percent,
					tag_mc_type,tag_mc_value, retail_max_mc, halmarking,
					sales_value, tag.current_branch,
					current_counter, id_branch,tag.created_time,uom.uom_name, uom.uom_short_code, 
					tag_name
				from ret_taging as tag
					LEFT JOIN ret_lot_inwards as lot ON lot.lot_no = tag.tag_lot_id
					LEFT JOIN ret_tag_type_master as tag_type ON tag_type.tag_id = tag.tag_type
					LEFT JOIN ret_uom as uom ON uom.uom_id = lot.gross_wt_uom
				");
		if($from_date!='')
		{
			$sql = $sql." where ".('(date(tag.created_time)BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")');
		}
		$sql = $sql." ORDER BY tag.tag_id desc";
		$result = $this->db->query($sql);
		return $result->result_array();
	}
	function get_entry_records($tag_id)
	{
		$sql = $this->db->query("SELECT tag_id, tag_code, counter,
				date_format(tag_datetime,'%d-%m-%Y') as tag_datetime, 
				tag_type, tag_lot_id, design_id, cost_center, 
				purity, size, uom, piece, less_wt, net_wt, gross_wt, 
				calculation_based_on, retail_max_wastage_percent,
				tag_mc_type, tag_mc_value,
				retail_max_mc, halmarking, sales_value, 
				current_branch, current_counter, id_branch,
				ifnull(design_code,'') as design_code,created_by
				FROM ret_taging as tag 
				LEFT JOIN ret_design_master as des ON des.design_no = tag.design_id 
				WHERE tag_id='".$tag_id."'");
		return $sql->result_array()[0];
	}
	function get_empty_record()
    {
		
		/* $emptyquery = $this->db->query("SELECT `COLUMN_NAME` 
									FROM `INFORMATION_SCHEMA`.`COLUMNS` 
									WHERE `TABLE_SCHEMA`='lmxretail' 
									AND `TABLE_NAME`='ret_taging'"); 
		foreach($emptyquery->result() as $row){
			
			$emptydata[$row->COLUMN_NAME] = NULL;
		}							
									
		
		*/
		//$emptyquery = $this->db->query("SELECT COLUMNS FROM ret_taging");
		//$emptyquery = $this->db->list_fields('ret_taging');
		$settings = $this->get_ret_settings('lot_recv_branch');
    	if($settings == 1){ // HO Only
			$ho = $this->get_headOffice();
		}
		$emptyquery = $this->db->field_data('ret_taging');
		$emptydata = array();
		foreach ($emptyquery as $field)
		{
			$emptydata[$field->name] = $field->default;
		}
		$emptydata['tag_datetime'] = date('d-m-Y');
		$emptydata['design_code']  = '';
		$emptydata['current_branch']  = $ho['id_branch'];
		$emptydata['lot_recv_branch']  = $settings;
		return $emptydata;
	}
	
	function get_lotInward($id)
    {
		$data = $this->db->query("SELECT lot_no,lot_date,lot_type,lot_received_at,gold_smith,order_no,lot_product,lot_sub_product,no_of_piece,no_of_tags,metal,gross_wt,net_wt,precious_stone,semi_precious_stone,normal_stone,precious_st_pcs,precious_st_wt,semi_precious_st_pcs,semi_precious_st_wt,normal_st_pcs,normal_st_wt,wastage_percentage,making_charge,making_per_grm,touch,rate_per_grm,narration,normal_stn_certificate,precious_stn_certificate,semiprecious_stn_certificate FROM ret_lot_inwards WHERE lot_no=".$id);
		return $data->result_array();
	}
	function getAvailabletags()
	{
		$sql = $this->db->query("SELECT * FROM ret_tag_type_master where tag_status = 1");
		return $sql->result_array();
	}
	function getUOMDetails()
	{
		$sql = $this->db->query("SELECT * FROM ret_uom where uom_status = 1");
		return $sql->result_array();
	}
	function getAvailablePurities()
	{
		$sql = $this->db->query("SELECT * FROM ret_uom where uom_status = 1");
		return $sql->result_array();
	}
	function getAvailableLots()
	{
		/* $lot_data = $this->db->query("SELECT lot_in.lot_no, lot_in.lot_type, 
					pro.hsn_code as hsn_code, pro.product_short_code as product_short_code, pro.product_name as product_name, 
					subpro.sub_pro_name as sub_pro_name, subpro.sub_pro_code as sub_pro_code 
					FROM ret_lot_inwards as lot_in 
					LEFT JOIN (SELECT pro_id, hsn_code, product_short_code, product_name FROM ret_product_master WHERE product_status = 1) as pro ON pro.pro_id = lot_in.lot_product 
					LEFT JOIN (SELECT sub_pro_id, sub_pro_name, sub_pro_code FROM ret_sub_product_master WHERE sub_pro_status = 1) as subpro ON subpro.sub_pro_id = lot_in.lot_sub_product 
					WHERE tag_status = 0"); */
		$lot_data = $this->db->query("SELECT lot_received_at,lot_in.lot_no, lot_in.lot_type, uom.uom_name, uom.uom_short_code,
					pro.hsn_code as hsn_code, 
					if(product_short_code = '' or product_short_code is null ,product_name ,CONCAT(product_short_code,' - ',product_name) ) as product_name,
                    ifnull(pro.product_short_code,'') as product_short_code,
					IFNULL(d.design,'-') as design,
					IFNULL(cat.metal,'-') as metal,
					IFNULL(cat.category,'-') as category,cat.tax_percentage,cat.tgi_calculation,
					lot_in.wastage_percentage,if(lot_in.mc_type = 1,'per gram','per piece') as mc_type,lot_in.making_charge,tgrp_name,purity,date_format(lot_in.lot_date,'%d-%m-%Y') as lot_date,
					(ifnull(lot_in.gross_wt,0) - ifnull(tag_det.gross_wt,0)) as gross_wt, 
					(ifnull(lot_in.no_of_piece,0) -
					ifnull(tag_det.rettag_piece, 0)) as rettag_piece, 
					ifnull( tag_det.uom_code,'-') as tag_gross_uom, 
					(ifnull(lot_in.precious_st_pcs,0) - ifnull(tag_det.pre_stn_pieces,0)) as pre_stn_pieces, 
					(ifnull(lot_in.precious_st_wt,0) - ifnull(tag_det.pre_stn_wt,0)) as pre_stn_wt,
					ifnull(tag_det.pre_st_uom,'-') as pre_st_uom, 
					(ifnull(lot_in.semi_precious_st_pcs,0) -  ifnull(tag_det.non_pre_stn_pieces,0)) as  non_pre_stn_pieces, 
					(ifnull(lot_in.semi_precious_st_wt,0) - ifnull(tag_det.non_pre_stn_wt,0)) as non_pre_stn_wt,
					ifnull(tag_det.non_pre_st_uom,'-') as non_pre_st_uom,
					(ifnull(lot_in.normal_st_pcs,0) - ifnull(tag_det.stn_pieces,0)) as stn_pieces, (ifnull(lot_in.normal_st_wt,0) - ifnull(tag_det.stn_wt,0)) as stn_wt, ifnull(tag_det.nor_stn_uom,'-') as nor_stn_uom  
					FROM ret_lot_inwards as lot_in 
					LEFT JOIN ret_uom as uom ON uom.uom_id = lot_in.gross_wt_uom 
					LEFT JOIN (SELECT pro_id, hsn_code, product_short_code, product_name FROM ret_product_master WHERE product_status = 1) as pro ON pro.pro_id = lot_in.lot_product 
					LEFT JOIN (SELECT design_no,if(design_code = '' or design_code is null ,design_name ,CONCAT(design_code,' - ',design_name) ) as design FROM `ret_design_master` WHERE design_status = 1) d on d.design_no = lot_in.lot_id_design
					LEFT JOIN (
						SELECT id_ret_category,if(cat_code = '' or cat_code is null ,c.name ,CONCAT(cat_code,' - ',c.name) ) as category,metal,m.tgrp_id,tgrp_name,
						group_concat(tm.tax_percentage) as tax_percentage,GROUP_CONCAT(i.tgi_calculation) as tgi_calculation
							FROM `ret_category` c
						left join ret_lot_inwards as lot on lot.id_category=c.id_ret_category
						LEFT JOIN metal m on m.id_metal = c.id_metal
						LEFT JOIN ret_taxgroupmaster tg on tg.tgrp_id = m.tgrp_id
						left join ret_taxgroupitems i on i.tgi_tgrpcode=tg.tgrp_id
                        left join ret_taxmaster tm on tm.tax_id=i.tgi_taxcode
                        where c.id_ret_category=lot.id_category 	group by c.id_ret_category
					 ) cat on cat.id_ret_category = lot_in.id_category
					LEFT JOIN ret_purity pur on pur.id_purity = lot_in.id_purity
					LEFT JOIN (SELECT tag_lot_id, sum(rettag.gross_wt) as gross_wt, sum(rettag.piece) as rettag_piece,
					ifnull(taguom.uom_short_code, '-') as uom_code,
					sum(ifnull(tag_pre_st.pre_stn_pieces,0)) as pre_stn_pieces, tag_pre_st.pre_stn_wt as pre_stn_wt,tag_pre_st.pre_st_uom as pre_st_uom, 
					non_pre_tag_st.non_pre_stn_pieces as non_pre_stn_pieces, non_pre_tag_st.non_pre_stn_wt as non_pre_stn_wt,
					non_pre_tag_st.non_pre_st_uom as non_pre_st_uom, 
					tag_st.stn_pieces as stn_pieces, tag_st.stn_wt as stn_wt, 
					tag_st.nor_stn_uom as nor_stn_uom  
					FROM ret_taging as rettag 
					LEFT JOIN ret_uom as taguom ON taguom.uom_id = rettag.uom 
					LEFT JOIN (SELECT ret_tg_pre_st.tag_id as pre_tag_id, ifnull(sum(pieces),0) as pre_stn_pieces, 
					ifnull(sum(wt),0) as pre_stn_wt, 
					ret_pre_st_uom.uom_short_code as pre_st_uom					
					FROM ret_taging_stone as ret_tg_pre_st 
					LEFT JOIN ret_stone as ret_pre_st ON ret_tg_pre_st.stone_id =  ret_pre_st.stone_id  
					LEFT JOIN ret_uom as ret_pre_st_uom ON ret_pre_st_uom.uom_id = ret_pre_st.uom_id
					WHERE ret_pre_st.stone_type = 1) as tag_pre_st ON tag_pre_st.pre_tag_id = rettag.tag_id 
					LEFT JOIN (SELECT ret_tg_non_pre_st.tag_id as non_tag_id, ifnull(sum(pieces),0) as non_pre_stn_pieces, 
					ifnull(sum(wt),0) as non_pre_stn_wt, ret_non_pre_st_uom.uom_short_code as non_pre_st_uom 
					FROM ret_taging_stone as ret_tg_non_pre_st 
					LEFT JOIN ret_stone as non_ret_st ON ret_tg_non_pre_st.stone_id =  non_ret_st.stone_id  
					LEFT JOIN ret_uom as ret_non_pre_st_uom ON ret_non_pre_st_uom.uom_id = ret_non_pre_st_uom.uom_id
					WHERE non_ret_st.stone_type = 2) as non_pre_tag_st ON non_pre_tag_st.non_tag_id = rettag.tag_id 
					LEFT JOIN (SELECT ret_tg_st.tag_id as non_tag_id, ifnull(sum(pieces),0) as stn_pieces, 
					ifnull(sum(wt),0) as stn_wt, ret_st_uom.uom_short_code as nor_stn_uom 
					FROM ret_taging_stone as ret_tg_st 
					LEFT JOIN ret_stone as ret_st ON ret_tg_st.stone_id =  ret_st.stone_id  
					LEFT JOIN ret_uom as ret_st_uom ON ret_st_uom.uom_id = ret_st_uom.uom_id
					WHERE ret_st.stone_type = 3) as tag_st ON tag_st.non_tag_id = rettag.tag_id 
					group by tag_lot_id) as tag_det ON tag_det.tag_lot_id = lot_in.lot_no 
					WHERE lot_in.stock_type=1 and tag_status = 0");
					//echo $this->db->_error_message();exit;
		return $lot_data->result_array();
	}
	
	function getAvailableDesigns($SearchTxt){
		$data = $this->db->query("SELECT design_code as label, design_no as value FROM ret_design_master WHERE design_code like '%".$SearchTxt."%' ");
		return $data->result_array();
	}
	function getDesignDetails($design_id){
		$data = $this->db->query("SELECT * FROM ret_design_master WHERE design_no ='".$design_id."'");
		return $data->result_array();
	}
	
	function getDesignPurityByDesignId($design_id){
		$data = $this->db->query("SELECT des_pur_id, pur_id, 
				purity FROM ret_design_purity 
				LEFT JOIN ret_purity ON id_purity = pur_id 
				WHERE design_id='".$design_id."'");
		return $data->result_array();
	}
	function getDesignSizesByDesignId($design_id){
		$data = $this->db->query("SELECT design_size_id,
				size, uom_name, uom_short_code, 
				uom.uom_id as uom_id 
				FROM ret_design_sizes as retdes 
				LEFT JOIN ret_uom as uom ON uom.uom_id = retdes.uom_id 
				WHERE design_id='".$design_id."'");
		return $data->result_array();
	}
	function getAvailableStones(){
		$data = $this->db->query("SELECT stone_id, stone_name,
		stone_code, stone_type, st.uom_id, is_certificate_req, is_4c_req,
		uom_name, uom_short_code 
		FROM ret_stone as st 
		LEFT JOIN ret_uom as uom ON uom.uom_id = st.uom_id 
		WHERE stone_status =1");
		return $data->result_array();
	}
	function getDesignStonesByDesignId($design_id){
		$data = $this->db->query("SELECT des_stone_id, stone_pcs,
				stone_name, desst.stone_id,
				stone_code, uom_name, uom_short_code, uom.uom_id   
				FROM ret_design_stone as desst 
				LEFT JOIN ret_stone as st ON st.stone_id = desst.stone_id 
				LEFT JOIN ret_uom as uom ON uom.uom_id = st.uom_id 
				WHERE design_id ='".$design_id."'");
		return $data->result_array();
	}
	function getTagStoneByTagId($tag_id){
		$data = $this->db->query("SELECT tag_stone_id, pieces,
				wt, amount, tagst.stone_id,
				stone_code, stone_name, uom_name, uom_short_code, uom.uom_id   
				FROM ret_taging_stone as tagst 
				LEFT JOIN ret_stone as st ON st.stone_id = tagst.stone_id 
				LEFT JOIN ret_uom as uom ON uom.uom_id = tagst.uom_id 
				WHERE tagst.tag_id ='".$tag_id."'");
		return $data->result_array();
	}
	function getTagMaterialByTagId($tag_id){
		$data = $this->db->query("SELECT tag_id, tagmat.material_id,
				wt, price,  material_name,
				material_code, mat.uom_id,  
				uom_name, uom_short_code 
				FROM ret_taging_other_materials as tagmat 
				LEFT JOIN ret_material as mat ON mat.material_id = tagmat.material_id 
				LEFT JOIN ret_uom as uom ON uom.uom_id = mat.uom_id 
				WHERE tagmat.tag_id ='".$tag_id."'");
		return $data->result_array();
	}
	function getDesignMaterialsByDesignId($design_id){
		$data = $this->db->query("SELECT des_oth_mat.material_id,
				uom_name, uom_short_code, uom.uom_id,
				material_name, material_code 
				FROM ret_design_other_materials as des_oth_mat 
				LEFT JOIN ret_material as mat ON mat.material_id = des_oth_mat.material_id 
				LEFT JOIN ret_uom as uom ON uom.uom_id = mat.uom_id 
				WHERE design_id ='".$design_id."'");
		return $data->result_array();
	}
	function getAvailableMaterials(){
		$data = $this->db->query("SELECT material_id, material_name,
				material_code, mat.uom_id,  
				uom_name, uom_short_code 
				FROM ret_material as mat 
				LEFT JOIN ret_uom as uom ON uom.uom_id = mat.uom_id 
				WHERE material_status = 1");
		return $data->result_array();
	}
	function getAvailableTaxGroups(){
		$taxGroupData = $this->db->query("SELECT tgrp_id, tgrp_name 
						FROM ret_taxgroupmaster 
						WHERE tgrp_status = 1 AND NOW() >= effective_date");
		return $taxGroupData->result_array();
	}
	function getAvailableTaxGroupItems($taxgroupid){
		$taxGroupData = $this->db->query("SELECT tax_id, tax_code,
						tax_percentage,tgi_calculation 
						FROM ret_taxgroupitems as grpitems 
						LEFT JOIN ret_taxmaster as tax ON tax.tax_id = grpitems.tgi_taxcode 
						WHERE tgi_tgrpcode = ".$taxgroupid);
		return $taxGroupData->result_array();
	}

	function code_number_generator()
	{
	  $lastno=$this->get_last_code_no();
	  if($lastno!=NULL)
		{
		  	$number = (int) $lastno;
		  	$number++;
			$code_number=str_pad($number, 5, '0', STR_PAD_LEFT);;
			
    		return $code_number;
		}
		else
		{
				$code_number=str_pad('1', 5, '0', STR_PAD_LEFT);;
    			return $code_number;
		}
	}
	
	function get_financialyear_by_status()
    {
		$sql = $this->db->query(" SELECT f.fin_id,f.fin_code,f.fin_year_code,date_format(f.fin_year_from,'%Y-%m-%d')as fin_year_from,date_format(f.fin_year_to,'%Y-%m-%d')as fin_year_to,f.fin_status,f.fin_year_code,date_format(f.created_on,'%Y-%m-%d')as created_on
		    FROM ret_financial_year f
			where f.fin_status=1");
		return $sql->row_array();
	}

	 function get_last_code_no()
    {
		$sql = "SELECT max(tag_code) as lastAcc_no FROM ret_taging  ORDER BY tag_id DESC ";
		return $this->db->query($sql)->row()->lastAcc_no;	
	} 

	//Bulk edit
	 function get_tag_numbr($tag_lot_id,$id_branch,$tag_id)
	 {
	 	$sql="select r.tag_id as label
	 		  from ret_taging r
	 		  left join ret_lot_inwards l on l.lot_no=r.tag_lot_id
	 		  where r.tag_id like '%".$tag_id."' 
	 		  ".($tag_lot_id!='' ? " and r.tag_lot_id=".$tag_lot_id."" :'')." 
	 		  ".($id_branch!='' ? " and l.lot_received_at=".$id_branch."" :'')."";
	 
	 	//print_r($sql);exit;
	 	return $this->db->query($sql)->result_array();
	 }

	  function get_prod_by_tagno($tag_lot_id,$prod_name)
	 {
	 	$sql="select p.product_name as label,l.lot_product as value
	 		  from ret_product_master p
	 		  left join ret_lot_inwards l on l.lot_product=p.pro_id
	 		  where p.product_name like '%".$prod_name."%'
	 		  ".($tag_lot_id!='' ? " and l.lot_no=".$tag_lot_id."":'')." group by l.lot_product";
	 	//print_r($sql);exit;
	 	return $this->db->query($sql)->result_array();
	 }

	 function get_tag_details()
	 {
	 		$tag_lot_id=$this->input->post('tag_lot_id');
			$id_branch=$this->input->post('id_branch');
			$tag_id=$this->input->post('tag_id');
			$lot_product=$this->input->post('lot_product');
			$from_weight=$this->input->post('from_weight');
			$to_weight=$this->input->post('to_weight');

			$sql="select r.tag_lot_id,d.design_name,d.design_code,r.tag_id,date_format(r.tag_datetime,'%d-%m-%Y')as tag_datetime,r.gross_wt,r.net_wt,r.tag_type,t.tag_name,r.gross_wt,r.net_wt,r.less_wt,r.calculation_based_on,r.tag_mc_type,tag_mc_value,retail_max_mc,r.retail_max_wastage_percent
			from ret_lot_inwards l
			left join ret_taging r on r.tag_lot_id=l.lot_no
			left join ret_product_master p on p.pro_id=l.lot_product
			left join ret_design_master d on d.design_no=r.design_id
			left join ret_tag_type_master t on t.tag_id=r.tag_type
			where r.tag_lot_id=".$tag_lot_id." 
				".($id_branch!='' ?" and l.lot_received_at=".$id_branch." ":'' )."
				".($tag_id!='' ? " and r.tag_id=".$tag_id."" :'')."
				".($lot_product!='' ? " and l.lot_product=".$lot_product."" :'')."
				".($from_weight!='' ?" and r.gross_wt>=".$from_weight."" :'')."
				".($to_weight!='' ? " and r.gross_wt<=".$to_weight."" :'')."";
        //print_r($sql);exit;
			return $this->db->query($sql)->result_array();
	 }

	 function get_branchwise_rate($id_branch)
	{
		$is_branchwise_rate=$this->session->userdata('is_branchwise_rate');
		if($id_branch!='' && $id_branch!=0 && $is_branchwise_rate==1)
		{
		    $sql="SELECT  b.name as name,m.mjdmagoldrate_22ct,m.goldrate_22ct,m.goldrate_24ct,m.silverrate_1gm,m.silverrate_1kg,m.mjdmasilverrate_1gm,platinum_1g,
		    Date_format(m.updatetime,'%d-%m%-%Y %h:%i %p')as updatetime   
		    FROM metal_rates m 
		    LEFT JOIN branch_rate br on br.id_metalrate=m.id_metalrates
		    left join branch b on b.id_branch=br.id_branch
		    ".($id_branch!='' ?" WHERE br.id_branch=".$id_branch."" :'')." ORDER by br.id_metalrate desc LIMIT 1";
		}
		else
		{
		    $sql="SELECT  b.name as name,m.mjdmagoldrate_22ct,m.goldrate_22ct,m.goldrate_24ct,m.silverrate_1gm,m.silverrate_1kg,m.mjdmasilverrate_1gm,platinum_1g,
		    Date_format(m.updatetime,'%d-%m%-%Y %h:%i %p')as updatetime   
		    FROM metal_rates m 
		    LEFT JOIN branch_rate br on br.id_metalrate=m.id_metalrates
		    left join branch b on b.id_branch=br.id_branch
		    WHERE br.id_branch=1 ORDER by br.id_metalrate desc LIMIT 1";
		}
		return $this->db->query($sql)->row_array();
	}

	function get_tagged_details($lot_id)
	{
			$sql=$this->db->query("select sum(r.piece) as tagged_pieces,l.no_of_piece 
									from ret_taging r
									left join  ret_lot_inwards l on l.lot_no=r.tag_lot_id
									where r.tag_lot_id=".$lot_id."");
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
}
?>
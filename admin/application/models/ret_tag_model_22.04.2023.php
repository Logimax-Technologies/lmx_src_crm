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
	
	public function updateRecord($data,$where_array,$table)
    {    
	    $edit_flag = 0;
	    $this->db->where($where_array);
		$edit_flag = $this->db->update($table,$data);
		//echo $this->db->last_query();exit;
		return $edit_flag;
	}
	public function deleteData($id_field,$id_value,$table)
    {
        $this->db->where($id_field, $id_value);
        $status= $this->db->delete($table); 
		return $status;
	}
	
	function getLotRefNo($tag_lot_id)
	{
	    $sql=$this->db->query("SELECT IFNULL(tag.ref_no,0) as ref_no FROM ret_taging tag WHERE tag.tag_lot_id=".$tag_lot_id." and created_by=".$this->session->userdata('uid')."");
	    if($sql->num_rows()==0)
	    {
	        $ref_no=1;
	    }else{
	         $ref_no=$sql->row()->ref_no;
	    }
	    return $ref_no;
	}
	
	function ajax_getTaggingList($from_date,$to_date,$tag_lot_id,$filter,$id_employee)
    { 
		 $sql = ("SELECT tag.tag_id,tag_code,
					date_format(tag_datetime,'%d-%m-%Y') as tag_date,
					tag.tag_type, tag_lot_id, design_id, cost_center, purity,
					IFNULL(size,'') as size, uom, piece, tag.less_wt, tag.net_wt, tag.gross_wt,
					tag.calculation_based_on,tag.retail_max_wastage_percent,
					tag.tag_mc_type,tag.tag_mc_value,tag.retail_max_mc, halmarking,
					tag.sales_value, tag.current_branch,
					tag.current_counter,tag.id_branch,tag.created_time,p.product_name,
					tag.product_id,k.code_karigar,lot.gold_smith,c.short_code,tag.tot_print_taken,
					fb.name as from_branch,tb.name as to_branch,
					concat(tag.ref_no,'-',e.emp_code) as ref_no,
					cat_type,
					IF(cat_type = 1, 'Ornament', IF(cat_type = 2, 'Bullion', IF(cat_type = 3, 'Stone', IF(cat_type = 4, 'Alloy','')))) AS category_type,
					uom_gross_wt,
					ru.uom_short_code,
					tag.stone_calculation_based_on,
					IF(tag.stone_calculation_based_on = 1, 'Weight based', IF(tag.stone_calculation_based_on = 2, 'Pcs based', '')) AS stone_calc,
					(select image as images FROM ret_taging_images  where tag_id=tag.tag_id and is_default='1') as tag_default_image
				from ret_taging as tag
				LEFT JOIN ret_uom AS ru ON ru.uom_id = tag.uom_gross_wt
				join company c
					LEFT JOIN ret_lot_inwards as lot ON lot.lot_no = tag.tag_lot_id
					LEFT JOIN ret_tag_type_master as tag_type ON tag_type.tag_id = tag.tag_type
					LEFT JOIN ret_product_master p on p.pro_id=tag.product_id
					LEFT JOIN ret_karigar k on k.id_karigar=lot.gold_smith
					left join employee e on e.id_employee=tag.created_by
					Left join branch fb on fb.id_branch = tag.current_branch			
					Left join branch tb on tb.id_branch = ".($filter['toBranch']!='' ? $filter['toBranch'] :0)."	
				where tag.tag_id is not null
				".($tag_lot_id!='' ? " and tag_lot_id=".$tag_lot_id :"")."
				".($id_employee!='' ? " and tag.created_by=".$id_employee :"")." 
				".($filter['ref_no']!='' ? " and ref_no='".$filter['ref_no']."'" :""));
		  
		if($from_date!='')
		{
			$sql = $sql." and ".('(date(tag.created_time)BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")');
		}
		$sql = $sql." ORDER BY tag.tag_id DESC";
		//print_r($sql);exit;
		$result = $this->db->query($sql);
		return $result->result_array();
	}
	function get_entry_records($tag_id)
	{
		$sql = $this->db->query("SELECT tag_id, tag_code, counter,
				date_format(tag_datetime,'%d-%m-%Y') as tag_datetime,sell_rate,item_rate,
				tag_type, tag_lot_id,id_lot_inward_detail, design_id, cost_center, 
				purity, size, uom, piece, less_wt, net_wt, gross_wt, 
				calculation_based_on, retail_max_wastage_percent,
				tag_mc_type, tag_mc_value,
				retail_max_mc, halmarking, sales_value,image,
				current_branch, current_counter,
				ifnull(design_code,'') as design_code ,created_by,des.design_name,b.name,tag.tot_print_taken,tag.id_section
				FROM ret_taging as tag 
				LEFT JOIN ret_design_master as des ON des.design_no = tag.design_id 
				left join branch b on b.id_branch=tag.current_branch
				WHERE tag_id='".$tag_id."'");
			//print_r($this->db->last_query());exit;
		return $sql->result_array()[0];
	}
	function get_entry_records_by_lot($tag_lot_id)
	{
		$sql = $this->db->query("SELECT tag.tag_id,tag.tag_code,tag.counter,
				date_format(tag.tag_datetime,'%d-%m-%Y') as tag_datetime, 
			tag.tag_type,tag.tag_lot_id,tag.id_lot_inward_detail,tag.design_id,tag.cost_center, 
				tag.purity, tag.size, tag.uom,tag.piece, tag.less_wt, tag.net_wt, tag.gross_wt, 
				tag.calculation_based_on, tag.retail_max_wastage_percent,
				tag.tag_mc_type, tag.tag_mc_value,
				tag.retail_max_mc, tag.halmarking, tag.sales_value,tag.image,
				tag.current_branch, tag.current_counter,
				ifnull(des.design_code,'') as design_code ,tag.created_by,des.design_name,b.name,
				p.product_name,tag.product_id,k.code_karigar,c.short_code,tag.tot_print_taken
				FROM ret_taging as tag
				join company c 
				LEFT JOIN ret_design_master as des ON des.design_no = tag.design_id
				LEFT JOIN ret_product_master p on p.pro_id=tag.product_id
				LEFT JOIN ret_lot_inwards l on l.lot_no=tag.tag_lot_id
				LEFT JOIN ret_karigar k on k.id_karigar=l.gold_smith
				left join branch b on b.id_branch=tag.current_branch
				WHERE tag_lot_id='".$tag_lot_id."'");
			//print_r($this->db->last_query());exit;
		return $sql->result_array();
	}
	function get_stone_details($tag_id)
	{
		$sql =$this->db->query("SELECT * from ret_taging_stone WHERE tag_id='".$tag_id."'");
		return $sql->result_array();
	}
	function get_empty_record()
    {
		$settings = $this->get_ret_settings('lot_recv_branch');
		$weight_per = $this->get_ret_settings('weight_per');
		$allow_tag_pcs = $this->get_ret_settings('allow_tag_pcs');
		
		$is_section_req = $this->get_ret_settings('is_section_required');
    	if($settings == 1){ // HO Only
			$ho = $this->get_headOffice();
		}
		else{
			$ho['id_branch']='';
		}
		$emptyquery = $this->db->field_data('ret_taging');
		$emptydata = array();
		foreach ($emptyquery as $field)
		{
			$emptydata[$field->name] = $field->default;
		}
		$emptydata['tag_datetime'] = date('d-m-Y');
		$emptydata['design_code']  = '';
		$emptydata['id_branch']  = $ho['id_branch'];
		$emptydata['current_branch']  = $ho['id_branch'];
		$emptydata['lot_recv_branch']  = $settings;
		$emptydata['weight_per']  = $weight_per;
		$emptydata['allow_tag_pcs']  = $allow_tag_pcs;
		
		$emptydata['is_section_req'] = $is_section_req;
		
		$emptydata['financial_year'] = $this->GetFinancialYear();
		return $emptydata;
	}
	
	function GetFinancialYear()
	{
		$sql=$this->db->query("SELECT fin_year_code,fin_status,fin_year_name From ret_financial_year");
		return $sql->result_array();
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
	/*function getAvailableLots()
	{
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
						SELECT id_ret_category,if(cat_code = '' or cat_code is null ,c.name ,CONCAT(cat_code,' - ',c.name) ) as category,metal,c.tgrp_id,tgrp_name,
						group_concat(tm.tax_percentage) as tax_percentage,GROUP_CONCAT(i.tgi_calculation) as tgi_calculation
							FROM `ret_category` c
						left join ret_lot_inwards as lot on lot.id_category=c.id_ret_category
						LEFT JOIN metal m on m.id_metal = c.id_metal
						LEFT JOIN ret_taxgroupmaster tg on tg.tgrp_id = c.tgrp_id
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
	}*/
	function getAvailableDesigns($SearchTxt){
		$data = $this->db->query("SELECT concat(design_name,'-',design_code) as label, design_no as value FROM ret_design_master WHERE design_code LIKE '%".$SearchTxt."%' OR design_name LIKE '%".$SearchTxt."%'");
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
    
    
     function getChargesDetails()
    {
        $sql=$this->db->query("SELECT * FROM `ret_charges`");
        return $sql->result_array();
    }
	function getTagCharges($tag_id, $tag_display = '')
	{
		$where = "";
		if($tag_display != '')
			$where = $where." AND tag_display = ".$tag_display;
		$sql = $this->db->query("SELECT rtc.tag_charge_id, rtc.tag_id, rtc.charge_id, rtc.charge_value, c.name_charge, c.code_charge, c.tag_display FROM ret_taging_charges AS rtc LEFT JOIN ret_charges AS c ON rtc.charge_id = c.id_charge  WHERE tag_id='".$tag_id."' ".$where);  
	    return $sql->result_array();
	}
	
	
	function getAvailableStoneTypes(){
		$data = $this->db->query("SELECT * FROM `ret_stone_type` WHERE status=1");
		return $data->result_array();
	}
	
	function get_ActiveUOM(){
		$data = $this->db->query("SELECT * FROM ret_uom WHERE uom_status=1");
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
	  $tag_code=$this->get_last_code_no();
	  $code=explode("-",$tag_code);
	  $lastno=$code[1];
	  if($lastno!=NULL && $lastno!='')
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
	
	
	function getlastTagCode($product_id)
    {
        $sql=$this->db->query("SELECT t.tag_id,t.tag_code FROM ret_taging t where t.tag_code is not null AND product_id = ".$product_id." AND tag_year = (RIGHT(YEAR(CURDATE()),2)) ORDER by tag_id DESC LIMIT 1");
		return $sql->row()->tag_code;
	} 
	
	//Bulk edit
	 function get_tag_numbr($tag_lot_id,$id_branch,$tag_id)
	 {
	     
	    if(strlen($tag_id)<5)
	    {
	        $tag_id=str_pad($tag_id, 5, '0', STR_PAD_LEFT);;
	    }
	    
	    
	 	$sql="select r.tag_code as label,r.tag_id as value
	 		  from ret_taging r
	 		  left join ret_lot_inwards l on l.lot_no=r.tag_lot_id
	 		  where r.tag_code like '%".$tag_id."' and r.tag_status!=1 and r.tag_status!=2 and r.tag_status!=3 and r.tag_status!=5
	 		  ".($id_branch!='' ? " and r.current_branch=".$id_branch."" :'')."";
	 	    //print_r($sql);exit;
	 	return $this->db->query($sql)->result_array();
	 }
	  function get_prod_by_tagno($tag_lot_id,$prod_name)
	 {
	 	$sql="select concat(p.product_name,'-',p.product_short_code) as label,p.pro_id as value
	 		  from ret_product_master p
	 		  left join ret_lot_inwards_detail d on d.lot_product=p.pro_id
	 		  where p.product_name like '%".$prod_name."%'
	 		  ".($tag_lot_id!='' ? " and d.lot_no=".$tag_lot_id."":'')." group by d.lot_product";
	 	return $this->db->query($sql)->result_array();
	 }
	 function get_tag_details()
	 {
			$id_branch      =$this->input->post('id_branch');
			
			$tag_id         =$this->input->post('tag_id');
			
			$tag_code         =$this->input->post('tag_code');
			
			$lot_product    =$this->input->post('lot_product');
			
			$from_weight    =$this->input->post('from_weight');
			
			$to_weight      =$this->input->post('to_weight');
			
			$mc_value       =$this->input->post('mc_value');
			
			$making_per     =$this->input->post('making_per');
			
			$id_design     =$this->input->post('id_design');
			
			$id_sub_design     =$this->input->post('id_sub_design');
			
			$id_mc_type     =$this->input->post('id_mc_type');
			
			$to_days     =$this->input->post('to_days');
			
			$from_days     =$this->input->post('from_days');
			
			$bulk_edit_options =$this->input->post('bulk_edit_options');
			
			$is_mrp = 0;
			 if($bulk_edit_options == 6) {
				$is_mrp = 1;
			}
			
			$sql="select t.tag_id,t.tag_code,t.gross_wt,IFNULL(t.less_wt,'-') as less_wt,t.net_wt,t.calculation_based_on,
			t.retail_max_wastage_percent,t.tag_mc_type,t.tag_mc_value,if(t.calculation_based_on=3,t.sell_rate,t.sales_value) as sales_value,t.piece,t.tag_lot_id,t.current_branch,
			concat(d.design_name,'-',design_code) as design_name,
			DATE_FORMAT(t.tag_datetime,'%d-%m-%Y')as tag_datetime,
			concat(p.product_name,'-',p.product_short_code) as product_name,IFNULL(s.stone_price,0) as stone_price,
			cat.tgrp_id as tax_group_id,IF(t.tag_mc_type=2,'Per Gram','Per Piece') as mc_type,IFNULL(s.sub_design_name,'-') as sub_design_name, IFNULL(rtc.charge_value, 0) AS charge_value,
			
			DATEDIFF(date(now()),if(t.old_tag_id!='' , date(t.old_tag_date),date(t.tag_datetime))) AS stock_age,IFNULL(t.old_tag_id,'') as old_tag_id,
			IFNULL(date_format(t.old_tag_date,'%d-%m-%Y'),'') as old_tag_date,
			(select image as images FROM ret_taging_images  where tag_id=t.tag_id and is_default='1') as tag_default_image
			from ret_taging t
			left join ret_design_master d on d.design_no=t.design_id
			left join ret_product_master p on p.pro_id=t.product_id
			left join ret_sub_design_master s on s.id_sub_design=t.id_sub_design
			left JOIN ret_lot_inwards_detail id on id.id_lot_inward_detail=t.id_lot_inward_detail
			left join ret_category cat on cat.id_ret_category=p.cat_id
		    left join metal m on m.id_metal=cat.id_metal
			left join(select st.tag_id,sum(st.amount) as stone_price from ret_taging_stone st) s on s.tag_id=t.tag_id
			LEFT JOIN (SELECT tag_id, SUM(IFNULL(charge_value,0)) AS charge_value FROM ret_taging_charges GROUP BY tag_id) AS rtc ON rtc.tag_id = t.tag_id
			where t.tag_id is not null and t.tag_status=0
			".($is_mrp==1 ? " and p.sales_mode=1" :" ")."
			".($id_branch!='' && $id_branch!=0 ?" and t.current_branch=".$id_branch." ":'' )."
			".($tag_code!='' ? " and t.tag_code='".$tag_code."'" :'')."
			".($tag_id!='' ? " and t.tag_id=".$tag_id."" :'')."
			".($lot_product!='' ? " and id.lot_product=".$lot_product."" :'')."
			".($from_weight!='' ?" and t.gross_wt>=".$from_weight."" :'')."
			".($to_weight!='' ? " and t.gross_wt<=".$to_weight."" :'')."
			".($mc_value!='' ? " and t.tag_mc_value=".$mc_value."" :'')."
			".($making_per!='' ? " and t.retail_max_wastage_percent=".$making_per."" :'')."
			".($id_design!='' ? " and t.design_id=".$id_design."" :'')."
			".($id_sub_design!='' ? " and t.id_sub_design=".$id_sub_design."" :'')."
			".($id_mc_type!='' ? " and t.tag_mc_type=".$id_mc_type."" :'')."
			".($from_days!='' && $to_days!='' ? " HAVING stock_age BETWEEN ".$from_days." AND ".$to_days." " :'')."
			
			";
			//echo $sql;exit;
			$tag_details=$this->db->query($sql)->result_array();
			foreach($tag_details as $tag)
			{
			    $data[]=array(
			                'calculation_based_on'      =>$tag['calculation_based_on'],
			                'design_name'               =>$tag['design_name'],
			                'sub_design_name'           =>$tag['sub_design_name'],
			                'product_name'              =>$tag['product_name'],
			                'gross_wt'                  =>$tag['gross_wt'],
			                'less_wt'                   =>$tag['less_wt'],
			                'net_wt'                    =>$tag['net_wt'],
			                'piece'                     =>$tag['piece'],
			                'retail_max_wastage_percent'=>$tag['retail_max_wastage_percent'],
			                'sales_value'               =>$tag['sales_value'],
			                'stone_price'               =>$tag['stone_price'],
			                'tag_code'                  =>$tag['tag_code'],
			                'tag_datetime'              =>$tag['tag_datetime'],
			                'tag_id'                    =>$tag['tag_id'],
			                'tag_lot_id'                =>$tag['tag_lot_id'],
			                'tag_mc_type'               =>$tag['tag_mc_type'],
			                'tag_mc_value'              =>$tag['tag_mc_value'],
			                'tax_group_id'              =>$tag['tax_group_id'],
			                'mc_type'                   =>$tag['mc_type'],
							'charge_value'				=>$tag['charge_value'],
							'stock_age'				    =>$tag['stock_age'],
							'old_tag_id'				=>$tag['old_tag_id'],
							'old_tag_date'				=>$tag['old_tag_date'],
			                'metal_rate'                =>$this->get_branchwise_rate($tag['current_branch']),
							'charges'                	=>$this->getTagCharges($tag['tag_id']),
							'attributes'             	=>$this->getTagAttributes($tag['tag_id']),
							'tag_default_image'         =>$tag['tag_default_image'],
			               );
			}
			return $data;
	 }
	 
	 function get_all_purities_for_product($tag_code, $product_id) {
		if($tag_code != "") {
			$sql = "SELECT 
						rp.id_purity, 
						rp.purity
					FROM `ret_taging` tag
					LEFT JOIN ret_product_master pro ON pro.pro_id = tag.product_id
					LEFT JOIN ret_metal_cat_purity mcp ON mcp.id_category = pro.cat_id
					LEFT JOIN ret_purity rp ON rp.id_purity = mcp.id_purity
					WHERE tag_code = '".$tag_code."'";
		} else {
			$sql = "SELECT
						rp.id_purity, 
						rp.purity
					FROM ret_product_master pro
					LEFT JOIN ret_metal_cat_purity mcp ON mcp.id_category = pro.cat_id
					LEFT JOIN ret_purity rp ON rp.id_purity = mcp.id_purity
					WHERE pro.pro_id = '".$product_id."'";
		}
		$sql = $this->db->query($sql);
		$result = $sql->result_array();
		return $result;
	}
	function check_is_mrp($tag_id) {
		$sql = "SELECT 
						*
					FROM `ret_taging` tag
					LEFT JOIN ret_product_master pro ON pro.pro_id = tag.product_id
					WHERE tag_id = '".$tag_id."' AND pro.sales_mode = 1";
		$sql = $this->db->query($sql);
		$result = $sql->result_array();
		if(count($result) > 0) {
			return true;
		} else {
			return false;
		}
	}
	 function get_branchwise_rate($id_branch)
	{
	    	
		$is_branchwise_rate=$this->session->userdata('is_branchwise_rate');
		if($id_branch!='' && $id_branch!=0 && $is_branchwise_rate==1)
		{
		    $sql="SELECT  b.name as name,m.mjdmagoldrate_22ct,m.goldrate_22ct,m.goldrate_24ct,m.silverrate_1gm,m.silverrate_1kg,m.mjdmasilverrate_1gm,platinum_1g,
		    Date_format(m.updatetime,'%d-%m%-%Y %h:%i %p')as updatetime,b.enable_gift_voucher, m.goldrate_18ct 
		    FROM metal_rates m 
		    LEFT JOIN branch_rate br on br.id_metalrate=m.id_metalrates
		    left join branch b on b.id_branch=br.id_branch
		    WHERE status=1 ".($id_branch!='' ?" and br.id_branch=".$id_branch."" :'')." ORDER by br.id_metalrate desc LIMIT 1";
		}
		else
		{
		   $sql="SELECT  m.mjdmagoldrate_22ct,m.goldrate_22ct,m.goldrate_24ct,m.silverrate_1gm,m.silverrate_1kg,m.mjdmasilverrate_1gm,platinum_1g,
		    Date_format(m.updatetime,'%d-%m%-%Y %h:%i %p')as updatetime , m.goldrate_18ct   
		    FROM metal_rates m 
		    ORDER by m.id_metalrates desc LIMIT 1";
		}
		return $this->db->query($sql)->row_array();
	}
	function get_tagged_details($id_lot_inward_detail)
	{
			$sql=$this->db->query("SELECT SUM(t.piece) as tagged_pieces,(SELECT SUM(d.no_of_piece) 
							FROM ret_lot_inwards_detail d WHERE d.id_lot_inward_detail=".$id_lot_inward_detail.") as total_pieces
							FROM ret_taging t 
							WHERE t.id_lot_inward_detail=".$id_lot_inward_detail."");
			//print_r($this->db->last_query());exit;
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
	
	function get_branchName($branch)
	{
	    if($branch == "HO"){
		    $data   =   $this->db->query("SELECT b.id_branch, b.is_ho, b.id_branch, name FROM branch b where b.is_ho = 1");
	    }else{
	        $data   =   $this->db->query("SELECT b.id_branch, b.is_ho, b.id_branch, b.name FROM branch b where b.id_branch = $branch");
	    }
		return $data->row_array();
	}
	function getAvailableLots()
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
		
		$lot_branch = $ho['id_branch'];
		$lot_data=$this->db->query("SELECT lot_in.lot_no, date_format(lot_in.lot_date,'%Y-%m-%d')as lot_date, 
        ifnull(lot_in.id_category,lot.lot_id_category) as id_category,ifnull(lot_in.id_purity,lot.lot_id_purity) as id_purity,   
        ifnull(c.name,lot.category_name) as category_name,ifnull(p.purity,lot.purity_name) as purity_name,
        (lot.total_gross_wt-ifnull(tag.tag_gross_wt,0)) as lot_bal_wt,
        (lot.total_no_of_piece-ifnull(tag.tag_piece,0)) as lot_bal_pcs,
        (lot.total_net_wt-ifnull(tag.tag_net_wt,0)) as lot_tag_net_wt,
        ifnull(m.metal,lot.metal) as metal,lot_in.lot_received_at,lot.total_no_of_piece,tag.tag_piece, kr.firstname AS karigar_name,lot_from 
        from  ret_lot_inwards lot_in
        LEFT JOIN ret_karigar kr on kr.id_karigar=lot_in.gold_smith
        left join ret_category c on c.id_ret_category=lot_in.id_category
        left join metal m on m.id_metal=c.id_metal
        left join ret_purity p on p.id_purity=lot_in.id_purity
        LEFT JOIN (
                    SELECT t.tag_lot_id,
                    sum(ifnull(t.gross_wt,0)) as  tag_gross_wt,
                    sum(ifnull(t.piece,0)) as  tag_piece,
                    sum(ifnull(t.net_wt,0)) as  tag_net_wt
                    from ret_taging t
                    where t.tag_status!=2 and t.tag_status!=5
                    group by t.tag_lot_id
                ) tag on tag.tag_lot_id = lot_in.lot_no
        LEFT JOIN (
                    SELECT d.lot_no,d.lot_id_category,d.lot_id_purity,
                    c.name as category_name,p.purity as purity_name,m.metal,
                    sum(ifnull(d.gross_wt,0)) as  total_gross_wt,
                    sum(ifnull(d.net_wt,0)) as  total_net_wt,
                    sum(ifnull(d.no_of_piece,0)) as  total_no_of_piece
                    from ret_lot_inwards_detail d
                    left join ret_category c on c.id_ret_category=d.lot_id_category
                    left join metal m on m.id_metal=c.id_metal
                    left join ret_purity p on p.id_purity=d.lot_id_purity
                    group by d.lot_no
                ) lot on lot.lot_no = lot_in.lot_no
        LEFT JOIN (
                    SELECT lt_det.lot_no,lm.id_lot_inward_detail
                    FROM ret_lot_merge lm 
                    LEFT JOIN ret_lot_inwards_detail lt_det on  lt_det.id_lot_inward_detail = lm.id_lot_inward_detail
                    ) as lt_merg on lt_merg.lot_no = lot_in.lot_no
        WHERE lot_in.stock_type = 1 and lt_merg.lot_no is null
        AND lot_in.lot_received_at =  $lot_branch order by lot_in.lot_no DESC");
        
//	echo $this->db->last_query();exit;
		$data['lot_inward']=$lot_data->result_array();
		return $data;
	}
	function get_lot_products($lot_no,$SearchTxt)
	{
		$data=array();
		$sql=$this->db->query("SELECT d.lot_product as lot_product,d.lot_no,d.gross_wt as gross_wt,
		                    d.no_of_piece as no_of_piece,ifnull(d.precious_st_wt,0) as precious_st_wt,
		                    ifnull(d.precious_st_pcs,0) as precious_st_pcs,ifnull(d.semi_precious_st_wt,0) as semi_precious_st_wt,
		                    ifnull(d.semi_precious_st_pcs,0) as semi_precious_st_pcs,
		                    ifnull(d.normal_st_wt,0) as normal_st_wt,ifnull(d.normal_st_pcs,0) as normal_st_pcs,
							CONCAT(p.product_name,'-',p.product_short_code) as product_name,p.hsn_code,IFNULL(i.order_no,'') as order_no,
							
							ifnull(puritm.is_suspense_stock,0)  as is_suspense_stock, c.cat_type,i.product_division
							 from ret_lot_inwards_detail d
							 LEFT JOIN ret_lot_inwards i on i.lot_no=d.lot_no
							 LEFT JOIN ret_product_master p on p.pro_id=d.lot_product 
							 LEFT JOIN ret_category c on c.id_ret_category=p.cat_id 
							 
							 LEFT JOIN ret_purchase_order as puritm ON puritm.po_id = i.po_id 
							 where d.lot_no=".$lot_no."  group by d.lot_product");
		//echo $this->db->last_query();exit;
		$data=$sql->result_array();
		return $data;
	}
	function get_lot_designs($lot_no,$lot_product,$searchTxt)
	{
		$data=array();
		$sql=$this->db->query("SELECT d.lot_id_design as lot_id_design,d.lot_no,CONCAT(des.design_name,'-',des.design_code) as design_name
							 ,des.design_no
							 from ret_lot_inwards_detail d
							 LEFT JOIN ret_design_master des on des.design_no=d.lot_id_design
							 where d.lot_no=".$lot_no." and d.lot_product=".$lot_product." group by d.lot_id_design");
		//echo $this->db->last_query();exit;
		$data=$sql->result_array();
		return $data;
	}
	function get_tax_percentage($lot_no)
	{
		$sql=$this->db->query("SELECT m.tax_name,group_concat(m.tax_percentage) as tax_percentage,GROUP_CONCAT(i.tgi_calculation) as tgi_calculation 
		FROM ret_lot_inwards lot
		left join ret_category c on c.id_ret_category=lot.id_category
		left join metal mt on mt.id_metal=c.id_metal
		left join ret_taxgroupitems i on i.tgi_tgrpcode=mt.tgrp_id
		left join ret_taxmaster m on m.tax_id=i.tgi_taxcode
		WHERE  lot.lot_no=".$lot_no."");
		$data=$sql->row_array();
		return $data;
	}
	function get_lot_inward_details($lot_no, $lot_product, $lot_id_design)
	{
	    $data['lot_inward_detail']=array();
	    
        $sql= $this->db->query("SELECT d.lot_no,d.id_lot_inward_detail,d.lot_product,d.lot_id_design,d.no_of_piece,
        des.design_name,p.product_name,p.hsn_code,p.product_short_code,p.calculation_based_on,
        d.gross_wt_uom,d.net_wt_uom,d.less_wt,d.less_wt_uom,d.sell_rate,
        ifnull(d.wastage_percentage,0) as wastage_percentage,d.mc_type,d.making_charge,d.precious_st_pcs,
        d.precious_st_wt,d.precious_st_uom,d.semi_precious_st_pcs,
        d.semi_precious_st_wt,d.normal_st_pcs,d.normal_st_wt,d.gross_wt,d.net_wt,d.design_for,
        IFNULL(d.normal_st_certif,'') as normal_st_certif,IFNULL(d.precious_st_certif,'') as precious_st_certif,
        IFNULL(d.semiprecious_st_certif,'') as semiprecious_st_certif,m.id_metal,c.cat_code,p.sales_mode,p.tgrp_id as tax_group_id,l.id_purity,c.is_multimetal,p.has_size,p.tax_type
        FROM ret_lot_inwards_detail d
        LEFT JOIN ret_lot_inwards l on l.lot_no=d.lot_no
        LEFT JOIN ret_product_master p on p.pro_id=d.lot_product
        LEFT JOIN ret_design_master des on des.design_no=d.lot_id_design
        LEFT JOIN ret_category c on c.id_ret_category=p.cat_id
        LEFT JOIN metal m on m.id_metal=c.id_metal
        where d.tag_status=0 and d.lot_no=".$lot_no."
        ".($lot_product!='' ? " and d.lot_product=".$lot_product."" : '')."
        ".($lot_id_design!='' ? " and d.lot_id_design=".$lot_id_design."" : '')."
        ");
        
			//echo $this->db->last_query();
			$lot_inward_detail=$sql->result_array();
			foreach($lot_inward_detail as $lot)
			{
				$data['lot_inward_detail'][]=array(
				                            'sales_mode'            =>$lot['sales_mode'],
				                            'id_metal'              =>$lot['id_metal'],
											'design_for'            =>$lot['design_for'],
											'design_name'           =>$lot['design_name'],
											'calculation_based_on'  =>$lot['calculation_based_on'],
											'sell_rate'             =>$lot['sell_rate'],
											'gross_wt'              =>$lot['gross_wt'],
											'gross_wt_uom'          =>$lot['gross_wt_uom'],
											'hsn_code'              =>$lot['hsn_code'],
											'id_lot_inward_detail'  =>$lot['id_lot_inward_detail'],
											'less_wt'               =>$lot['less_wt'],
											'less_wt_uom'           =>$lot['less_wt_uom'],
											'lot_id_design'         =>$lot['lot_id_design'],
											'lot_no'                =>$lot['lot_no'],
											'lot_product'           =>$lot['lot_product'],
											'making_charge'         =>$lot['making_charge'],
											'mc_type'               =>$lot['mc_type'],
											'net_wt'                =>$lot['net_wt'],
											'net_wt_uom'            =>$lot['net_wt_uom'],
											'no_of_piece'           =>$lot['no_of_piece'],
											'normal_st_pcs'         =>$lot['normal_st_pcs'],
											'normal_st_wt'          =>$lot['normal_st_wt'],
											'precious_st_pcs'       =>$lot['precious_st_pcs'],
											'precious_st_uom'       =>$lot['precious_st_uom'],
											'precious_st_wt'        =>$lot['precious_st_wt'],
											'product_name'          =>$lot['product_name'],
											'product_short_code'    =>$lot['product_short_code'],
											'semi_precious_st_pcs'  =>$lot['semi_precious_st_pcs'],
											'semi_precious_st_wt'   =>$lot['semi_precious_st_wt'],
											'wastage_percentage'    =>$lot['wastage_percentage'],
											'semiprecious_st_certif'=>$lot['semiprecious_st_certif'],
											'precious_st_certif'    =>$lot['precious_st_certif'],
											'normal_st_certif'      =>$lot['normal_st_certif'],
											'cat_code'              =>$lot['cat_code'],
											'tax_group_id'          =>$lot['tax_group_id'],
											'id_purity'             =>$lot['id_purity'],
											'is_multimetal'			=>$lot['is_multimetal'],
											'has_size'              =>$lot['has_size'],
											'tax_type'              =>$lot['tax_type'],
											'lot_blc'               =>$this->get_balance_details($lot['id_lot_inward_detail'],''),
											'size_details'          =>$this->get_Activesize($lot['lot_product']),
											);
				//$data['lot_inward_detail']['blc_details']=$this->get_balance_details($lot['id_lot_inward_detail'],$lot['lot_id_design']);
			}
			$data['tax_percentage']=$this->get_tax_percentage($lot_no);
			return $data;
	}
	function get_balance_details($id_lot_inward_detail,$lot_id_design)
	{
		$sql= $this->db->query("SELECT d.lot_no,
					(lot.total_gross_wt-ifnull(tag.tag_gross_wt,0)) as lot_bal_wt,
					tag.tag_gross_wt as tag_gross_wt,
					(lot.total_no_of_piece-ifnull(tag.tag_piece,0)) as lot_bal_pcs,
					(lot.total_net_wt-ifnull(tag.tag_net_wt,0)) as lot_tag_net_wt
					FROM ret_lot_inwards_detail d
					LEFT JOIN ret_product_master p on p.pro_id=d.lot_product
					LEFT JOIN ret_design_master des on des.design_no=d.lot_id_design
					LEFT JOIN (
						SELECT t.tag_lot_id,
						sum(ifnull(t.gross_wt,0)) as  tag_gross_wt,
						sum(ifnull(t.piece,0)) as  tag_piece,
						sum(ifnull(t.net_wt,0)) as  tag_net_wt
						from ret_taging t
						where t.tag_status!=2 and t.id_lot_inward_detail=".$id_lot_inward_detail."
						group by t.id_lot_inward_detail
					) tag on tag.tag_lot_id=d.lot_no
					LEFT JOIN (
						SELECT d.lot_no,
						sum(ifnull(d.gross_wt,0)) as  total_gross_wt,
						sum(ifnull(d.net_wt,0)) as  total_net_wt,
						sum(ifnull(d.no_of_piece,0)) as  total_no_of_piece
						from ret_lot_inwards_detail d
						where d.id_lot_inward_detail=".$id_lot_inward_detail."
						group by d.lot_no
					) lot on lot.lot_no=d.lot_no
					where d.id_lot_inward_detail=".$id_lot_inward_detail." ");
			//echo $this->db->last_query();exit;
		$data=$sql->row_array();
		return $data;
	}
	function get_tagging_details($from_date,$to_date)
	{
	    $sql= $this->db->query("SELECT IFNULL(t.tag_lot_id,'') as tag_lot_id,sum(t.gross_wt) as gross_wt,sum(ifnull(t.less_wt,0)) as less_wt,sum(t.net_wt) as net_wt,
	    sum(t.piece) as piece,date_format(t.tag_datetime,'%d-%m-%y') as tag_date,t.tot_print_taken
	    From ret_taging t
	    where (t.tag_status!=2)".($from_date!='' && $to_date!='' ? " and (date(t.tag_datetime) BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."')":'')."
	    group by t.tag_lot_id");
	    return $sql->result_array();
	}
	
	 function get_duplicate_tag($data)
	 {
	 	$sql=$this->db->query("SELECT tag.tag_id,tag_code,
					date_format(tag_datetime,'%d-%m-%Y') as tag_date,
					tag.tag_type, tag_lot_id, design_id, cost_center, purity,
					size, uom, piece, tag.less_wt, tag.net_wt, tag.gross_wt,
					tag.calculation_based_on,tag.retail_max_wastage_percent,
					tag.tag_mc_type,tag.tag_mc_value,tag.retail_max_mc, halmarking, tag.sell_rate,
					tag.sales_value, tag.current_branch,
					tag.current_counter,tag.id_branch,tag.created_time,p.product_name,d.design_name,
					tag.product_id,k.code_karigar,lot.gold_smith,c.short_code,tag.tot_print_taken,IFNULL(s.sub_design_name,'') as sub_design_name
				from ret_taging as tag
				join company c
					LEFT JOIN ret_lot_inwards as lot ON lot.lot_no = tag.tag_lot_id
					LEFT JOIN ret_tag_type_master as tag_type ON tag_type.tag_id = tag.tag_type
					LEFT JOIN ret_product_master p on p.pro_id=tag.product_id
					LEFT JOIN ret_design_master d on d.design_no=tag.design_id
					
					LEFT JOIN ret_sub_design_master s on s.id_sub_design=tag.id_sub_design
					LEFT JOIN ret_karigar k on k.id_karigar=lot.gold_smith
				where tag.tag_id is not null and tag.tag_status!=2 and tag.tag_status!=5 and tag.tag_status!=1 and tag.tag_status!=3 
				".($data['tag_lot_id']!='' ? " and tag.tag_lot_id=".$data['tag_lot_id']."" :'')."
				".($data['id_branch']>0 ? " and tag.current_branch=".$data['id_branch']."" :'')."
				".($data['id_product']!='' ? " and tag.product_id=".$data['id_product']."" :'')."
				".($data['des_select']!='' ? " and tag.design_id=".$data['des_select']."" :'')."
				".($data['tag_id']!='' ? " and tag.tag_id=".$data['tag_id']."" :'')."
				".($data['from_weight']!='' ?" and tag.gross_wt>=".$data['from_weight']."" :'')."
			    ".($data['to_weight']!='' ? " and tag.gross_wt<=".$data['to_weight']."" :'')."
				
				");
				//print_r($this->db->last_query());exit;
	 	return $sql->result_array();
	 }
	 
	    function getTagDetails($tag_id)
        {
        	$sql = $this->db->query("SELECT tag.tag_id,tag.tag_code,tag.counter,
        	date_format(tag_datetime,'%d-%m-%Y') as tag_datetime,sell_rate,item_rate,
        	tag.tag_type, tag_lot_id,id_lot_inward_detail, design_id, cost_center, 
        	purity, (s.value) as size, uom, piece,tag.less_wt,tag.net_wt, gross_wt, 
        	tag.calculation_based_on,tag.retail_max_wastage_percent,
        	tag.tag_mc_type,tag.tag_mc_value,b.short_name as brnc_scode,
        	retail_max_mc, halmarking, sales_value,tag.image,
        	tag.current_branch, current_counter, p.metal_type,  
        	ifnull(design_code,'') as design_code,tag.created_by,des.design_name,b.name,
        	p.product_name,tag.product_id,k.code_karigar,c.short_code,tag.tot_print_taken,
        	p.sales_mode,p.product_short_code,tag.tag_mark,des.mc_cal_type,ifnull(tag_stn_detail.stn_amount,'0') as stn_amount,
        	ifnull(tag_stn_detail.stn_wt,'0') as stn_wt, ifnull(tag_stn_detail.uom_name, '') as stuom, ifnull(tag_stn_detail.uom_short_code, '') as stuom_short_code,
        	ifnull(tag.hu_id, '-') as hu_id, 
        	IFNULL(sub_des.sub_design_name,'') as sub_design_name, IFNULL(tag.id_sub_design,'') as id_sub_design,
        	IFNULL(tag_dia_detail.dia_amount,'0') as dia_amount, 
        	IFNULL(tag_dia_detail.dia_wt,'0') as dia_wt, 
        	IFNULL(tag_dia_detail.dia_uom_name, '') as dia_uom_name,
        	IFNULL(tag_dia_detail.dia_uom_short_code, '') as dia_uom_short_code,pd.div_value AS product_division, IFNULL(tag_stn_detail.stn_pieces,0) as stn_pieces, IFNULL(tag_dia_detail.dia_pieces,0) as dia_pieces,tag_stn_detail.stone_code as stone_code,tag_stn_detail.rate_per_gram as stn_rate_per_gram,
        	IFNULL(cusOrd.order_no,'') as order_no
        	FROM ret_taging as tag 
        	join company c
        	LEFT JOIN ret_design_master as des ON des.design_no = tag.design_id
        	LEFT JOIN ret_sub_design_master sub_des on sub_des.id_sub_design=tag.id_sub_design
        	LEFT JOIN ret_product_master p on p.pro_id=tag.product_id 
        	left join branch b on b.id_branch=tag.current_branch
        	LEFT JOIN ret_lot_inwards d on d.lot_no=tag.tag_lot_id
        	left JOIN ret_karigar k on k.id_karigar=d.gold_smith
        	LEFT JOIN ret_size s on s.id_size=tag.size
        	LEFT JOIN ret_product_division pd on pd.id_pro_division=tag.product_division
        	LEFT JOIN (SELECT retst.rate_per_gram,tag_id,sum(amount) as stn_amount,sum(wt) as stn_wt, uom_name, uom_short_code, SUM(pieces) AS stn_pieces,rtstn.stone_code as stone_code
        	FROM `ret_taging_stone` as retst
        	LEFT JOIN ret_uom as stuom ON stuom.uom_id = retst.uom_id 
			LEFT JOIN ret_stone as rtstn ON rtstn.stone_id = retst.stone_id
        	GROUP by tag_id) as tag_stn_detail ON tag_stn_detail.tag_id = tag.tag_id
        	LEFT JOIN (SELECT tag_id, sum(IFNULL(amount,0)) as dia_amount, sum(IFNULL(wt,0)) as dia_wt, uom_name AS dia_uom_name, uom_short_code AS dia_uom_short_code, SUM(pieces) AS dia_pieces
        	FROM `ret_taging_stone` as retst
        	LEFT JOIN ret_uom as stuom ON stuom.uom_id = retst.uom_id 
        	LEFT JOIN ret_stone AS st ON st.stone_id = retst.stone_id
        	WHERE st.stone_type = 1
        	GROUP by tag_id) as tag_dia_detail ON tag_dia_detail.tag_id = tag.tag_id
			LEFT JOIN customerorderdetails ord ON ord.id_orderdetails = tag.id_orderdetails
        	LEFT JOIN customerorder cusOrd ON cusOrd.id_customerorder = ord.id_customerorder
        	WHERE tag.tag_id='".$tag_id."'");
        	$result =  $sql->result_array();
			foreach($result as $items)
			{
				$items['stone_details']=$this->get_stone_detail($tag_id);
				$returnData=$items;
			}
			return $returnData;
        }
        
        function get_stone_Detail($tag_id){
			
				$sql =$this->db->query("SELECT u.uom_short_code,t.wt as stn_wt,t.rate_per_gram as stn_rpg,t.amount as stn_amount,s.stone_code from ret_taging_stone t
				LEFT JOIN ret_stone s ON  s.stone_id = t.stone_id
				LEFT JOIN ret_uom u On u.uom_id = t.uom_id
				WHERE t.tag_id='".$tag_id."'");
				return $sql->result_array();
		}
        
        /*function get_reTagDetails($id_process)
        {
        	$sql = $this->db->query("SELECT tag.tag_id,tag.tag_code,tag.counter,
        	date_format(tag_datetime,'%d-%m-%Y') as tag_datetime,sell_rate,item_rate,
        	tag.tag_type, tag_lot_id,id_lot_inward_detail, design_id, cost_center, 
        	purity, (s.value) as size, uom, piece, less_wt,tag.net_wt, gross_wt, 
        	tag.calculation_based_on,tag.retail_max_wastage_percent,
        	tag.tag_mc_type,tag.tag_mc_value,
        	retail_max_mc, halmarking, sales_value,tag.image,
        	current_branch, current_counter, p.metal_type,  
        	ifnull(design_code,'') as design_code,tag.created_by,des.design_name,b.name,
        	p.product_name,tag.product_id,k.code_karigar,c.short_code,tag.tot_print_taken,
        	p.sales_mode,p.product_short_code,tag.tag_mark,des.mc_cal_type,ifnull(tag_stn_detail.stn_amount,'0') as stn_amount,
        	ifnull(tag_stn_detail.stn_wt,'0') as stn_wt, ifnull(tag_stn_detail.uom_name, '') as stuom, ifnull(tag_stn_detail.uom_short_code, '') as stuom_short_code,
        	ifnull(tag.hu_id, '-') as hu_id, 
        	IFNULL(sub_des.sub_design_name,'') as sub_design_name, IFNULL(tag.id_sub_design,'') as id_sub_design,
        	IFNULL(tag_dia_detail.dia_amount,'0') as dia_amount, 
        	IFNULL(tag_dia_detail.dia_wt,'0') as dia_wt, 
        	IFNULL(tag_dia_detail.dia_uom_name, '') as dia_uom_name,
        	IFNULL(tag_dia_detail.dia_uom_short_code, '') as dia_uom_short_code
        	FROM ret_taging as tag 
        	join company c
        	LEFT JOIN ret_acc_stock_process_details stk on stk.ref_no=tag.tag_id
        	LEFT JOIN ret_design_master as des ON des.design_no = tag.design_id
        	LEFT JOIN ret_sub_design_master sub_des on sub_des.id_sub_design=tag.id_sub_design
        	LEFT JOIN ret_product_master p on p.pro_id=tag.product_id 
        	left join branch b on b.id_branch=tag.current_branch
        	LEFT JOIN ret_lot_inwards d on d.lot_no=tag.tag_lot_id
        	left JOIN ret_karigar k on k.id_karigar=d.gold_smith
        	LEFT JOIN ret_size s on s.id_size=tag.size
        	LEFT JOIN (SELECT tag_id,sum(amount) as stn_amount,sum(wt) as stn_wt, uom_name, uom_short_code
        	FROM `ret_taging_stone` as retst
        	LEFT JOIN ret_uom as stuom ON stuom.uom_id = retst.uom_id 
        	GROUP by tag_id) as tag_stn_detail ON tag_stn_detail.tag_id = tag.tag_id
        	LEFT JOIN (SELECT tag_id, sum(IFNULL(amount,0)) as dia_amount, sum(IFNULL(wt,0)) as dia_wt, uom_name AS dia_uom_name, uom_short_code AS dia_uom_short_code
        	FROM `ret_taging_stone` as retst
        	LEFT JOIN ret_uom as stuom ON stuom.uom_id = retst.uom_id 
        	LEFT JOIN ret_stone AS st ON st.stone_id = retst.stone_id
        	WHERE st.stone_type = 1
        	GROUP by tag_id) as tag_dia_detail ON tag_dia_detail.tag_id = tag.tag_id
        	WHERE stk.id_process='".$id_process."'");
        	//print_r($this->db->last_query());exit;
        	return $sql->result_array();
        }*/
        
        function get_reTagDetails($id_process)
        {
        	$sql = $this->db->query("SELECT tag.tag_id,tag.tag_code,tag.counter,
        	date_format(tag_datetime,'%d-%m-%Y') as tag_datetime,sell_rate,item_rate,
        	tag.tag_type, tag_lot_id,id_lot_inward_detail, design_id, cost_center, 
        	purity, (s.value) as size, uom,tag.piece,tag.less_wt,tag.net_wt,tag.gross_wt, 
        	tag.calculation_based_on,tag.retail_max_wastage_percent,
        	tag.tag_mc_type,tag.tag_mc_value,
        	retail_max_mc, halmarking, sales_value,tag.image,
        	tag.current_branch, current_counter, p.metal_type,  
        	ifnull(design_code,'') as design_code,tag.created_by,des.design_name,b.name,
        	p.product_name,tag.product_id,k.code_karigar,c.short_code,tag.tot_print_taken,
        	p.sales_mode,p.product_short_code,tag.tag_mark,des.mc_cal_type,ifnull(tag_stn_detail.stn_amount,'0') as stn_amount,
        	ifnull(tag_stn_detail.stn_wt,'0') as stn_wt, ifnull(tag_stn_detail.uom_name, '') as stuom, ifnull(tag_stn_detail.uom_short_code, '') as stuom_short_code,
        	ifnull(tag.hu_id, '-') as hu_id, 
        	IFNULL(sub_des.sub_design_name,'') as sub_design_name, IFNULL(tag.id_sub_design,'') as id_sub_design,
        	IFNULL(tag_dia_detail.dia_amount,'0') as dia_amount, 
        	IFNULL(tag_dia_detail.dia_wt,'0') as dia_wt, 
        	IFNULL(tag_dia_detail.dia_uom_name, '') as dia_uom_name,
        	IFNULL(tag_dia_detail.dia_uom_short_code, '') as dia_uom_short_code, IFNULL(tag_stn_detail.stn_pieces,0) as stn_pieces, IFNULL(tag_dia_detail.dia_pieces,0) as dia_pieces,
        	IFNULL(cusOrd.order_no,'') as order_no
        	FROM ret_taging as tag 
        	join company c
        	LEFT JOIN ret_acc_stock_process_details stk on stk.ref_no=tag.ref_tag_id
        	LEFT JOIN ret_design_master as des ON des.design_no = tag.design_id
        	LEFT JOIN ret_sub_design_master sub_des on sub_des.id_sub_design=tag.id_sub_design
        	LEFT JOIN ret_product_master p on p.pro_id=tag.product_id 
        	left join branch b on b.id_branch=tag.current_branch
        	LEFT JOIN ret_lot_inwards d on d.lot_no=tag.tag_lot_id
        	left JOIN ret_karigar k on k.id_karigar=d.gold_smith
        	LEFT JOIN ret_size s on s.id_size=tag.size
        	LEFT JOIN (SELECT tag_id,sum(amount) as stn_amount,sum(wt) as stn_wt, uom_name, uom_short_code, SUM(pieces) AS stn_pieces
        	FROM `ret_taging_stone` as retst
        	LEFT JOIN ret_uom as stuom ON stuom.uom_id = retst.uom_id 
        	GROUP by tag_id) as tag_stn_detail ON tag_stn_detail.tag_id = tag.tag_id
        	LEFT JOIN (SELECT tag_id, sum(IFNULL(amount,0)) as dia_amount, sum(IFNULL(wt,0)) as dia_wt, uom_name AS dia_uom_name, uom_short_code AS dia_uom_short_code, SUM(pieces) AS dia_pieces
        	FROM `ret_taging_stone` as retst
        	LEFT JOIN ret_uom as stuom ON stuom.uom_id = retst.uom_id 
        	LEFT JOIN ret_stone AS st ON st.stone_id = retst.stone_id
        	WHERE st.stone_type = 1
        	GROUP by tag_id) as tag_dia_detail ON tag_dia_detail.tag_id = tag.tag_id
        	LEFT JOIN customerorderdetails ord ON ord.id_orderdetails = tag.id_orderdetails
        	LEFT JOIN customerorder cusOrd ON cusOrd.id_customerorder = ord.id_customerorder
        	WHERE stk.id_process='".$id_process."'");
        	//print_r($this->db->last_query());exit;
        	return $sql->result_array();
        }
	function getTagDetailsby_lot($lot_id)
	{
		$sql = $this->db->query("SELECT tag.tag_id,tag.tag_code,tag.counter,
				date_format(tag_datetime,'%d-%m-%Y') as tag_datetime,sell_rate,item_rate,
				tag.tag_type, tag_lot_id,id_lot_inward_detail, design_id, cost_center, 
				purity, (s.value) as size, uom, piece, less_wt,tag.net_wt, gross_wt, 
				tag.calculation_based_on,tag.retail_max_wastage_percent,
				tag.tag_mc_type,tag.tag_mc_value,
				retail_max_mc, halmarking, sales_value,tag.image,
				tag.current_branch, current_counter,
				ifnull(design_code,'') as design_code,tag.created_by,des.design_name,b.name,
				p.product_name,tag.product_id,k.code_karigar,c.short_code,tag.tot_print_taken,
				p.sales_mode,p.product_short_code,tag.tag_mark,
				ifnull(tag_stn_detail.stn_amount,'0') as stn_amount,
				ifnull(tag_stn_detail.stn_wt,'0') as stn_wt, ifnull(tag_stn_detail.uom_name, '') as stuom, ifnull(tag_stn_detail.uom_short_code, '') as stuom_short_code,
				ifnull(tag.hu_id, '-') as hu_id  , p.metal_type, 
				IFNULL(sub_des.sub_design_name,'') as sub_design_name, IFNULL(tag.id_sub_design,'') as id_sub_design,
				IFNULL(tag_dia_detail.dia_amount,'0') as dia_amount, 
				IFNULL(tag_dia_detail.dia_wt,'0') as dia_wt, 
				IFNULL(tag_dia_detail.dia_uom_name, '') as dia_uom_name,
				IFNULL(tag_dia_detail.dia_uom_short_code, '') as dia_uom_short_code,pd.div_value AS product_division, IFNULL(tag_stn_detail.stn_pieces,0) as stn_pieces, IFNULL(tag_dia_detail.dia_pieces,0) as dia_pieces,
				IFNULL(cusOrd.order_no,'') as order_no
				
				FROM ret_taging as tag 
				join company c
				LEFT JOIN ret_design_master as des ON des.design_no = tag.design_id
				LEFT JOIN ret_sub_design_master sub_des on sub_des.id_sub_design=tag.id_sub_design
				LEFT JOIN ret_product_master p on p.pro_id=tag.product_id
				left join branch b on b.id_branch=tag.current_branch
				LEFT JOIN ret_lot_inwards d on d.lot_no=tag.tag_lot_id
				left JOIN ret_karigar k on k.id_karigar=d.gold_smith
				LEFT JOIN ret_size s on s.id_size=tag.size
				
				LEFT JOIN ret_product_division pd on pd.id_pro_division=tag.product_division
				LEFT JOIN (SELECT tag_id,sum(amount) as stn_amount,sum(wt) as stn_wt, uom_name, uom_short_code, SUM(pieces) AS stn_pieces
                FROM `ret_taging_stone` 
				LEFT JOIN ret_uom as stuom ON stuom.uom_id = retst.uom_id 
                GROUP by tag_id) as tag_stn_detail ON tag_stn_detail.tag_id = tag.tag_id
				LEFT JOIN (SELECT tag_id, sum(IFNULL(amount,0)) as dia_amount, sum(IFNULL(wt,0)) as dia_wt, uom_name AS dia_uom_name, uom_short_code AS dia_uom_short_code, SUM(pieces) AS dia_pieces
                FROM `ret_taging_stone` as retst
                LEFT JOIN ret_uom as stuom ON stuom.uom_id = retst.uom_id 
                
                LEFT JOIN ret_stone AS st ON st.stone_id = retst.stone_id
                
                WHERE st.stone_type = 1
                GROUP by tag_id) as tag_dia_detail ON tag_dia_detail.tag_id = tag.tag_id
                LEFT JOIN customerorderdetails ord ON ord.id_orderdetails = tag.id_orderdetails
        	    LEFT JOIN customerorder cusOrd ON cusOrd.id_customerorder = ord.id_customerorder
				WHERE tag_lot_id='".$lot_id."' and tag.tot_print_taken=0 ORDER BY tag.tag_id DESC");
			//print_r($this->db->last_query());exit;
		return $sql->result_array();
	}
    
    	function getTagByRefNo($ref_no)
	{
		$sql = $this->db->query("SELECT tag.tag_id,tag.tag_code,tag.counter,
				date_format(tag_datetime,'%d-%m-%Y') as tag_datetime,sell_rate,item_rate,
				tag.tag_type, tag_lot_id,id_lot_inward_detail, design_id, cost_center, 
				purity, (s.value) as size, uom, piece,tag.less_wt,tag.net_wt, gross_wt, 
				tag.calculation_based_on,tag.retail_max_wastage_percent,
				tag.tag_mc_type,tag.tag_mc_value,
				retail_max_mc, halmarking, sales_value,tag.image,
				tag.current_branch, current_counter,
				ifnull(design_code,'') as design_code,tag.created_by,des.design_name,b.name,
				p.product_name,tag.product_id,k.code_karigar,c.short_code,tag.tot_print_taken,
				p.sales_mode,p.product_short_code,tag.tag_mark,
					ifnull(tag_stn_detail.stn_amount,'0') as stn_amount,
				ifnull(tag_stn_detail.stn_wt,'0') as stn_wt, ifnull(tag_stn_detail.uom_name, '') as stuom, ifnull(tag_stn_detail.uom_short_code, '') as stuom_short_code,
				ifnull(tag.hu_id, '-') as hu_id , p.metal_type,
				IFNULL(sub_des.sub_design_name,'') as sub_design_name, IFNULL(tag.id_sub_design,'') as id_sub_design,
				IFNULL(tag_dia_detail.dia_amount,'0') as dia_amount,
				IFNULL(tag_dia_detail.dia_wt,'0') as dia_wt,
				IFNULL(tag_dia_detail.dia_uom_name, '') as dia_uom_name,
				IFNULL(tag_dia_detail.dia_uom_short_code, '') as dia_uom_short_code,pd.div_value AS product_division, IFNULL(tag_stn_detail.stn_pieces,0) as stn_pieces, IFNULL(tag_dia_detail.dia_pieces,0) as dia_pieces
				FROM ret_taging as tag  
				join company c
				LEFT JOIN ret_design_master as des ON des.design_no = tag.design_id
				LEFT JOIN ret_sub_design_master sub_des on sub_des.id_sub_design=tag.id_sub_design
				LEFT JOIN ret_product_master p on p.pro_id=tag.product_id
				left join branch b on b.id_branch=tag.current_branch
				LEFT JOIN ret_lot_inwards d on d.lot_no=tag.tag_lot_id
				left JOIN ret_karigar k on k.id_karigar=d.gold_smith
				LEFT JOIN ret_size s on s.id_size=tag.size
				LEFT JOIN ret_product_division pd on pd.id_pro_division=tag.product_division
				LEFT JOIN (SELECT tag_id,sum(amount) as stn_amount,sum(wt) as stn_wt, uom_name, uom_short_code, SUM(pieces) AS stn_pieces
                FROM `ret_taging_stone` as retst
                LEFT JOIN ret_uom as stuom ON stuom.uom_id = retst.uom_id 
                GROUP by tag_id) as tag_stn_detail ON tag_stn_detail.tag_id = tag.tag_id
				
				LEFT JOIN (SELECT tag_id, sum(IFNULL(amount,0)) as dia_amount, sum(IFNULL(wt,0)) as dia_wt, uom_name AS dia_uom_name, uom_short_code AS dia_uom_short_code, SUM(pieces) AS dia_pieces
                FROM `ret_taging_stone` as retst
                LEFT JOIN ret_uom as stuom ON stuom.uom_id = retst.uom_id 
                
                LEFT JOIN ret_stone AS st ON st.stone_id = retst.stone_id
                
                WHERE st.stone_type = 1
                GROUP by tag_id) as tag_dia_detail ON tag_dia_detail.tag_id = tag.tag_id
                LEFT JOIN customerorderdetails ord ON ord.id_orderdetails = tag.id_orderdetails
        	    LEFT JOIN customerorder cusOrd ON cusOrd.id_customerorder = ord.id_customerorder
				WHERE ref_no='".$ref_no."'");
		//print_r($this->db->last_query());exit;
		return $sql->result_array();
	}
	
	function get_scanned_details($tag_id)
	{
		$sql=$this->db->query("SELECT s.tag_id,s.id_branch
			From ret_tag_scanned s
			where s.tag_id=".$tag_id."");
		if($sql->num_rows()==0)
		{
			return TRUE;
		}else{
			return FALSE;
		}
		
	}
	function get_order_details($lot_no)
	{
		$sql=$this->db->query("SELECT cus.id_orderdetails,c.order_no,cus.id_product,cus.design_no,cus.wast_percent as wastage_percentage,cus.id_mc_type as mc_type,cus.mc as making_charge,cus.stn_amt,p.product_name,p.product_short_code,des.design_name,d.id_lot_inward_detail,i.lot_no,p.sales_mode,p.calculation_based_on,d.design_for,
		d.no_of_piece as no_of_piece,cat.tgrp_id as tax_group_id,d.gross_wt as gross_wt,d.net_wt as net_wt,j.id_vendor
		FROM customerorderdetails cus 
		LEFT JOIN customerorder c on c.id_customerorder=cus.id_customerorder
        LEFT JOIN joborder j on j.id_order=cus.id_orderdetails
		LEFT JOIN ret_product_master p on p.pro_id=cus.id_product
		LEFT JOIN ret_design_master des on des.design_no=cus.design_no
		LEFT JOIN ret_lot_inwards i on i.order_no=c.order_no
		LEFT join ret_lot_inwards_detail d on d.lot_no=i.lot_no
		left join ret_category cat on cat.id_ret_category=p.cat_id
		left join metal m on m.id_metal=cat.id_metal
		WHERE i.lot_no=".$lot_no." and d.tag_status=0 AND j.id_vendor=i.gold_smith");
		//print_r($this->db->last_query());exit;
		return $sql->result_array();
	}
	
    function get_tag_marking($data)
	{
		if($data['dt_range'] != ''){
			$dateRange = explode('-',$data['dt_range']); 
			$d1 = date_create($dateRange[0]);
			$d2 = date_create($dateRange[1]);
			$FromDt = date_format($d1,"Y-m-d"); 
			$ToDt = date_format($d2,"Y-m-d"); 
		}
		$sql=$this->db->query("SELECT tag.tag_code,IFNULL(tag.gross_wt,0) as gross_wt,IFNULL(tag.net_wt,0) as net_wt,tag.tag_id,
			tag.sales_value,tag.current_branch,b.name as branch_name,if(tag.tag_mark=1,'Green Tag','Normal Tag') as tag_mark,p.product_name,
			date_format(tag.tag_datetime,'%d-%m-%Y') as tag_date,IF(tag.tag_status=0,'On Sale',if(tag.tag_status=1,'Sold Out',IF(tag.tag_status=2,'Deleted',IF(tag.tag_status=3,'Other Issue',if(tag.tag_status=4,'In Transit','Deleted For Stock'))))) as tag_status,
			tag.tag_status as status
			FROM ret_taging tag
			LEFT JOIN ret_product_master p on p.pro_id=tag.product_id
			LEFT JOIN branch b on b.id_branch=tag.current_branch
			where tag.tag_id is not null and tag.tag_status=0
			".($data['id_branch'] != '' && $data['id_branch'] != 0 ? " and tag.current_branch=".$data['id_branch']."": '')."
			".($data['id_product'] != '' && $data['id_product'] != 0 ? " and tag.product_id=".$data['id_product']."": '')."
			".($data['filter_by'] == 1 ? " and tag.tag_mark=".$data['filter_by']."": " and tag.tag_mark=".$data['filter_by']."")."
			order by tag.tag_id DESC");
		//print_r($this->db->last_query());exit;
		return $sql->result_array();
	}
	
	function getBranchDayClosingData($id_branch)
    {
	    $sql = $this->db->query("SELECT id_branch,is_day_closed,entry_date from ret_day_closing where id_branch=".$id_branch);  
	    return $sql->row_array();
	}
	
	function getEstTaglist($data)
	{
	    if($data['id_branch']!='' && $data['id_branch']>0)
        {
            $dcData=$this->getBranchDayClosingData($data['id_branch']);
        }
	    $sql=$this->db->query("SELECT tag.tag_code,IFNULL(tag.gross_wt,0) as gross_wt,IFNULL(tag.net_wt,0) as net_wt,tag.tag_id,
			tag.sales_value,tag.current_branch,b.name as branch_name,
			date_format(tag.tag_datetime,'%d-%m-%Y') as tag_date,p.product_name,des.design_name,tag.tag_lot_id,tag.design_id,IFNULL(tag.size,'') as size,
			concat(s.value,'-',s.name) as size_name,IF(tag.tag_status=0,'On Sale',if(tag.tag_status=1,'Sold Out',IF(tag.tag_status=2,'Deleted',IF(tag.tag_status=3,'Other Issue',if(tag.tag_status=4,'In Transit','Deleted For Stock'))))) as tag_status,
			tag.tag_status as status,if(tag.tag_mark=1,'Green Tag','Normal Tag') as tag_mark
        FROM ret_estimation_items est
        LEFT JOIN ret_estimation e on e.estimation_id=est.esti_id
        LEFT JOIN ret_taging tag ON tag.tag_id=est.tag_id
        LEFT JOIN ret_product_master p on p.pro_id=tag.product_id
        LEFT JOIN ret_design_master des on des.design_no=tag.design_id
        LEFT JOIN branch b on b.id_branch=tag.current_branch
        LEFT JOIN ret_size s on s.id_size=tag.size
        WHERE tag.tag_status=0
        ".($data['id_branch'] != '' && $data['id_branch'] != 0 ? " and e.id_branch=".$data['id_branch']."": '')."
        ".($data['id_branch'] != '' && $data['id_branch'] != 0 ? " and date(e.estimation_datetime)='".$dcData['entry_date']."'": '')."
        ".($data['est_no'] != ''  ? " and e.esti_no=".$data['est_no']."": '')."
       ");
       //print_r($this->db->last_query());exit;
       return $sql->result_array();
	}
	
	function get_tag_edit_det($data)
	{
	    if($data['est_no']!='')
	    {
	        if($data['id_branch']!='' && $data['id_branch']>0)
            {
                $dcData=$this->getBranchDayClosingData($data['id_branch']);
            }
	        $sql=$this->db->query("SELECT tag.tag_code,IFNULL(tag.gross_wt,0) as gross_wt,IFNULL(tag.net_wt,0) as net_wt,tag.tag_id,
			tag.sales_value,tag.current_branch,b.name as branch_name,tag.tag_mark,
			date_format(tag.tag_datetime,'%d-%m-%Y') as tag_date,p.product_name,des.design_name,tag.tag_lot_id,tag.design_id,IFNULL(tag.size,'') as size,
			IFNULL(concat(s.value,'-',s.name),'') as size_name,IFNULL(d.sub_design_name,'') as sub_design_name,IFNULL(tag.id_sub_design,'') as id_sub_design,
			IFNULL(tag.old_tag_id,'-') as old_tag_id
    		FROM ret_estimation_items est
            LEFT JOIN ret_estimation e on e.estimation_id=est.esti_id
            LEFT JOIN ret_taging tag ON tag.tag_id=est.tag_id
			LEFT JOIN ret_product_master p on p.pro_id=tag.product_id
			LEFT JOIN ret_design_master des on des.design_no=tag.design_id
			LEFT JOIN branch b on b.id_branch=tag.current_branch
			LEFT JOIN ret_size s on s.id_size=tag.size
			LEFT JOIN ret_sub_design_master d on d.id_sub_design=tag.id_sub_design
			where tag.tag_id is not null and tag.tag_status=0
			".($data['id_branch'] != '' && $data['id_branch'] != 0 ? " and e.id_branch=".$data['id_branch']."": '')."
			".($data['id_design'] != '' && $data['id_design'] != 0 ? " and tag.design_id=".$data['id_design']."": '')."
			".($data['id_sub_design'] != '' && $data['id_sub_design'] != 0 ? " and tag.id_sub_design=".$data['id_sub_design']."": '')."
			".($data['id_branch'] != '' && $data['id_branch'] != 0 ? " and date(e.estimation_datetime)='".$dcData['entry_date']."'": '')."
			".($data['est_no'] != '' ? " and e.esti_no=".$data['est_no']."": '')."
			");
	    }
	    else
	    {
	        $sql=$this->db->query("SELECT tag.tag_code,IFNULL(tag.gross_wt,0) as gross_wt,IFNULL(tag.net_wt,0) as net_wt,tag.tag_id,
			tag.sales_value,tag.current_branch,b.name as branch_name,tag.tag_mark,
			date_format(tag.tag_datetime,'%d-%m-%Y') as tag_date,p.product_name,des.design_name,tag.tag_lot_id,tag.design_id,IFNULL(tag.size,'') as size,
			IFNULL(concat(s.value,'-',s.name),'') as size_name,IFNULL(d.sub_design_name,'') as sub_design_name,IFNULL(tag.id_sub_design,'') as id_sub_design,
			IFNULL(tag.old_tag_id,'-') as old_tag_id
			FROM ret_taging tag
			LEFT JOIN ret_product_master p on p.pro_id=tag.product_id
			LEFT JOIN ret_design_master des on des.design_no=tag.design_id
			LEFT JOIN branch b on b.id_branch=tag.current_branch
			LEFT JOIN ret_size s on s.id_size=tag.size
			LEFT JOIN ret_sub_design_master d on d.id_sub_design=tag.id_sub_design
			where tag.tag_id is not null and tag.tag_status=0
			".($data['lot_id'] != ''  ? " and tag.tag_lot_id=".$data['lot_id']."": '')."
			".($data['id_branch'] != '' && $data['id_branch'] != 0 ? " and tag.current_branch=".$data['id_branch']."": '')."
			".($data['id_product'] != '' ? " and tag.product_id=".$data['id_product']."": '')."
			".($data['id_design'] != '' && $data['id_design'] != 0 ? " and tag.design_id=".$data['id_design']."": '')."
			".($data['id_sub_design'] != '' && $data['id_sub_design'] != 0 ? " and tag.id_sub_design=".$data['id_sub_design']."": '')."
			".($data['tag_code'] != '' ? " and tag.tag_code='".$data['tag_code']."'": '')."
			order by tag.tag_id DESC");
	    }
		return $sql->result_array();
	}
	
	function get_employee()
	{
	    $id_branch=$this->session->userdata('id_branch');
		$data=$this->db->query("select e.id_branch,e.id_employee,CONCAT(CONCAT(e.emp_code,'-',e.firstname),' ',e.lastname)as firstname,s.disc_limit_type,s.disc_limit,s.allowed_old_met_pur,s.allow_branch_transfer
			from employee e
			LEFT JOIN employee_settings s on s.id_employee=e.id_employee
			".($id_branch!='' && $id_branch? " where e.login_branches=".$id_branch."":'')."");
		//	print_r($this->db->last_query());exit;
		return $data->result_array();
	}
	
	function get_ActiveSize($id_product)
	{
	    $sql=$this->db->query("select * from ret_size where active=1 ".($id_product!='' ? " and id_product=".$id_product."" :'')."");
	    return $sql->result_array();
	}
	
	
	//Order link
	function getTaggingBySearch($SearchTxt,$branch)
    {
        $data = $this->db->query("SELECT tag.tag_id as value,tag.tag_code,
        tag_code as label, tag_datetime, tag.tag_type, tag_lot_id,
        tag.design_id, cost_center, tag.purity, tag.size, uom, piece, tag.less_wt, tag.net_wt, tag.gross_wt,
        tag.calculation_based_on, retail_max_wastage_percent,tag_mc_value,tag_mc_type,
        halmarking, sales_value, tag.tag_status, product_name, product_short_code, tag.product_id as lot_product,
        pur.purity as purname,lot_inw.lot_received_at,
        ifnull(tag_stn_detail.stn_amount,if(tag.id_orderdetails!='',ord.stn_amt,0)) as stone_price,IFNULL(tag_stn_detail.certification_cost,0) as certification_cost,
        tag.tag_id,pro.sales_mode,tag.item_rate,tax.tax_percentage,tax.tgi_calculation,pro.metal_type,tag.current_branch,
        mt.tgrp_id as tax_group_id,IFNULL(tag.id_orderdetails,'') as id_orderdetails,
        IFNULL(lot_inw.order_no,'') as order_no,des.design_name,IFNULL(s.sub_design_name,'-') as sub_design_name,tag.id_sub_design
        FROM ret_taging as tag
        Left join ret_lot_inwards_detail lot_det ON tag.id_lot_inward_detail = lot_det.id_lot_inward_detail
        LEFT JOIN ret_lot_inwards as lot_inw ON lot_inw.lot_no = lot_det.lot_no
        LEFT JOIN ret_product_master as pro ON pro.pro_id = tag.product_id
        LEFT JOIN ret_purity as pur ON pur.id_purity = tag.purity
        left join ret_category c on c.id_ret_category=pro.cat_id
        left join metal mt on mt.id_metal=c.id_metal 
        LEFT JOIN ret_design_master as des ON des.design_no = tag.design_id 
        left join ret_sub_design_master s on s.id_sub_design=tag.id_sub_design
        left join customerorderdetails ord on ord.id_orderdetails=tag.id_orderdetails
		LEFT JOIN (select i.tgi_taxcode,i.tgi_tgrpcode,
		GROUP_CONCAT(m.tax_percentage) as tax_percentage,
		GROUP_CONCAT(i.tgi_calculation) as tgi_calculation
		FROM ret_taxgroupitems i
		LEFT JOIN ret_taxmaster m on m.tax_id=i.tgi_taxcode) as tax on tax.tgi_tgrpcode=mt.tgrp_id
        LEFT JOIN (SELECT tag_id,sum(amount) as stn_amount,sum(wt) as stn_wt,sum(certification_cost) as certification_cost
        FROM `ret_taging_stone` 
        GROUP by tag_id) as tag_stn_detail ON tag_stn_detail.tag_id = tag.tag_id
        
         LEFT JOIN(SELECT d.tag_id
                 FROM ret_tag_collection_mapping_details d 
                 LEFT JOIN ret_tag_collection_mapping tagmap ON tagmap.id_tag_mapping=d.id_tag_mapping
                 WHERE tagmap.status=0) as coll ON coll.tag_id=tag.tag_id
                 
        WHERE tag.tag_status=0 AND coll.tag_id IS NULL and tag.tag_code  LIKE '%".$SearchTxt."%' ".($branch!='' ? " and tag.current_branch=".$branch."" :'')."");
        //print_r($this->db->last_query());exit;
        return $data->result_array();
    }
    
    function getOrdersBySearch($SearchTxt,$branch,$id_product,$id_design,$fin_year_code)
    {
        $sql=$this->db->query("SELECT c.id_customerorder as value,d.id_product,d.id_customerorder,c.order_no as label
        FROM customerorder c 
        LEFT JOIN customerorderdetails d ON d.id_customerorder=c.id_customerorder
        LEFT JOIN ret_product_master p ON p.pro_id=d.id_product
        WHERE d.orderstatus>=0 and c.fin_year_code='".$fin_year_code."' and c.order_no LIKE '$SearchTxt'  and d.id_product=".$id_product." and d.design_no=".$id_design."");
        //print_r($this->db->last_query());exit;
        return $sql->result_array();
    }
    
    function getOrderDetailBySearch($id_customerorder,$id_product,$id_design)
    {
        $sql=$this->db->query("SELECT d.id_orderdetails,d.id_product,d.weight
        FROM customerorderdetails d
        where d.id_product=".$id_product." and d.design_no=".$id_design." and d.id_customerorder=".$id_customerorder."");
        return $sql->result_array();
    }
	//Order link
    function validate_huid($sku_id)    
	{        
	  $sql=$this->db->query("SELECT tag_id FROM ret_taging where hu_id='$sku_id'"); 
	  return $sql->result_array();    
	}
    
    
    function get_active_design_products($data)
	{
	    if(empty($data['id_lot_no']) || $data['lot_from'] == 1 || $data['lot_from'] == 3 | $data['lot_from'] == 5 ){
    	    $sql=$this->db->query("SELECT des.design_no, des.design_name 
                FROM ret_product_mapping p 
                LEFT JOIN ret_product_master pro ON pro.pro_id = p.pro_id
                LEFT JOIN ret_design_master des ON des.design_no=p.id_design
                where des.design_no is not null ".($data['id_product']!='' ? " and p.pro_id=".$data['id_product']."" :'')."
                ");
	    }else{ // Get design list from PO entry
	        $sql = $this->db->query("SELECT des.design_no, des.design_name 
	                        FROM ret_lot_inwards as lot 
	                        LEFT JOIN ret_purchase_order as po ON po.po_id = lot.po_id 
	                        LEFT JOIN ret_purchase_order_items as poitm ON poitm.po_item_po_id  = po.po_id  AND poitm.po_item_pro_id = '".$data['id_product']."' 
	                        LEFT JOIN ret_product_mapping as p ON p.pro_id = poitm.po_item_pro_id AND p.id_design = poitm.po_item_des_id 
	                        LEFT JOIN ret_design_master des ON des.design_no=p.id_design 
	                        WHERE lot.lot_no = '".$data['id_lot_no']."'
	                        group by des.design_no");
	    }
        //print_r($this->db->last_query());exit;
        return $sql->result_array();
	}
	
	
	function get_ActiveSubDesingns($data)
	{
	    if(empty($data['id_lot_no']) || $data['lot_from'] == 1 || $data['lot_from'] == 3 || $data['lot_from'] == 5 ){
    	    $sql = $this->db->query("SELECT d.id_sub_design,d.sub_design_name 
        	        FROM ret_sub_design_mapping s 
        	        LEFT JOIN ret_sub_design_master d ON d.id_sub_design = s.id_sub_design 
        	        WHERE s.id_sub_design is NOT NULL 
        	        ".($data['design_no'] != '' ? " and s.id_design=".$data['design_no']."" :'')." 
        	        ".($data['id_product'] != '' ? " and s.id_product=".$data['id_product']."" :'')."
             ");
	    }else{
	        $sql = $this->db->query("SELECT d.id_sub_design,d.sub_design_name 
	                        FROM ret_lot_inwards as lot 
	                        LEFT JOIN ret_purchase_order as po ON po.po_id = lot.po_id 
	                        LEFT JOIN ret_purchase_order_items as poitm ON poitm.po_item_po_id  = po.po_id  AND poitm.po_item_pro_id = '".$data['id_product']."' AND poitm.po_item_des_id = '".$data['design_no']."' 
	                        LEFT JOIN ret_sub_design_mapping as s ON s.id_product = poitm.po_item_pro_id AND s.id_design = poitm.po_item_des_id AND s.id_sub_design = poitm.po_item_sub_des_id 
	                        LEFT JOIN ret_sub_design_master d ON d.id_sub_design = s.id_sub_design
	                        WHERE lot.lot_no = '".$data['id_lot_no']."'
	                        group by d.id_sub_design");
	    }
	    //echo $this->db->last_query();exit;
        return $sql->result_array();
	}
	
	
	function get_wastage_settings_details()
	{
	    $return_data=array();
	    $sql=$this->db->query("SELECT m.id_sub_design_mapping,m.id_product,m.id_design,m.id_sub_design,m.mc_cal_type,m.mc_cal_value,m.wastage_type,m.wastag_value, IFNULL(m.mc_min,0) AS mc_min, IFNULL(m.wastag_min,0) AS wastag_min, IFNULL(m.margin_mrp,0) AS margin_mrp
        FROM ret_sub_design_mapping m ");
        $result=$sql->result_array();
        foreach($result as $items)
        {
            $return_data[]=array(
                'id_sub_design_mapping' =>$items['id_sub_design_mapping'],
                'id_product'            =>$items['id_product'],
                'id_design'             =>$items['id_design'],
                'id_sub_design'         =>$items['id_sub_design'],
                'mc_cal_type'           =>$items['mc_cal_type'],
                'mc_cal_value'          =>$items['mc_cal_value'],
                'wastage_type'          =>$items['wastage_type'],
                'wastag_value'          =>$items['wastag_value'],
				'mc_min'          		=>$items['mc_min'],
				'wastag_min'          	=>$items['wastag_min'],
				'margin_mrp'          	=>$items['margin_mrp'],
                'weight_range_det'      =>$this->get_weight_range_details($items['id_sub_design_mapping']),
                );
        }
        return $return_data;
	}
	
	function get_weight_range_details($id_sub_design_mapping)
	{
	    $sql=$this->db->query("SELECT * FROM `ret_design_weight_range_wc` WHERE id_sub_design_mapping=".$id_sub_design_mapping."");
	    return $sql->result_array();
	}
	//Tag attributes
	function getTagAttributes($tag_id, $attr_id = 0) {
		$where = "";
		if($attr_id > 0)
		{
			$where = " AND rta.attr_id = ".$attr_id;
		}
		$sql = "SELECT
					rta.attr_tag_id,
					rta.id_tagging, 
					rta.attr_id, 
					rta.attr_val_id,
					ra.attr_name,
					rav.attr_val
				FROM ret_tagging_attributes AS rta
				LEFT JOIN ret_attribute AS ra ON ra.attr_id = rta.attr_id
				LEFT JOIN ret_attribute_values AS rav ON rav.attr_val_id = rta.attr_val_id
				WHERE id_tagging=".$tag_id.$where;
		$res = $this->db->query($sql);
		return $res->result_array();
	}
	function get_category_from_productid($product_id)
	{
		$sql = "SELECT
					rc.id_ret_category,
					rc.name,
					rc.cat_code,rpm.product_short_code
				FROM ret_product_master AS rpm
				LEFT JOIN ret_category AS rc ON rc.id_ret_category = rpm.cat_id
				WHERE rpm.pro_id = ".$product_id;
		$res = $this->db->query($sql);
		return $res->row_array();
	}
	function get_tag_details_by_tag_id($tag_id)
	{
		$sql = "SELECT
					*
				FROM ret_taging
				WHERE tag_id = ".$tag_id;
		$res = $this->db->query($sql);
		return $res->row_array();
	}
	
	function getPoDetailsforPC($data)
	{
	    $sql = $this->db->query("SELECT  poitm.po_item_po_id, poitm.po_item_id, gross_wt, less_wt, net_wt, IFNULL(item_wastage,0) as item_wastage, purchase_touch, no_of_pcs, mc_type, IFNULL(mc_value,0) as mc_value, is_rate_fixed, fix_rate_per_grm, item_cost 
	                        FROM ret_lot_inwards as lot 
	                        LEFT JOIN ret_purchase_order as po ON po.po_id = lot.po_id 
	                        LEFT JOIN ret_purchase_order_items as poitm ON poitm.po_item_po_id  = po.po_id  AND poitm.po_item_pro_id = '".$data['product_id']."' AND poitm.po_item_des_id = '".$data['design_id']."' AND poitm.po_item_sub_des_id = '".$data['subdesign_id']."'  
	                        WHERE lot.lot_no = '".$data['lot_no']."'");
	   $po_items_details = $sql->result_array();
	   foreach($po_items_details as $key => $poval ){
	        $po_items_details[$key]['stonedetail'] = $this->get_po_item_stone_details($poval['po_item_id']);    
	   }
	   	return $po_items_details;
	}
	
	function get_po_item_stone_details($po_item_id){
	    $sql = $this->db->query("SELECT po_item_id, po_stone_id, po_stone_pcs, po_stone_wt, po_stone_uom, po_stone_calc_based_on, 
	                            po_stone_rate, po_stone_amount   
	                            FROM `ret_po_stone_items` WHERE po_item_id =  '".$po_item_id."'");
	   return $sql->result_array();
	    
	}
	// Get metal rate based on metal and purity
	function get_rate_from_metal_and_purity($metal_id, $purity_id)
	{
		$rate = 0;
		$sql = "SELECT 
					rmpr.rate_field
				FROM ret_metal_purity_rate AS rmpr
				WHERE rmpr.id_metal = ".$metal_id." AND rmpr.id_purity = ".$purity_id;
		$sql = $this->db->query($sql);
		$result = $sql->result_array();
		if(count($result) == 1) {
			$fieldName = $result[0]['rate_field'];			
			$sql = "SELECT 
					".$fieldName."
				FROM metal_rates
				WHERE add_date = (SELECT MAX(add_date) FROM metal_rates)";
			$sql = $this->db->query($sql);
			if($sql) {
				$result = $sql->result_array();
				if(count($result) == 1) {
					$rate = $result[0]['goldrate_22ct'];
				}
			}
		}
		return $rate;
	}
	
	
	
	/*function get_retagging_details($data)
	{
        $sql=$this->db->query("SELECT tag.tag_id,tag.tag_code,tag.tag_type,tag.product_id,tag.design_id,tag.design_for,tag.purity,tag.size,
        d.gross_wt,d.net_wt,des.design_name,pur.purity,date_format(b.bill_date,'%d-%m-%Y') as bill_date,b.bill_no,b.bill_id,tag.sales_value, p.product_short_code,p.product_name,
        IFNULL(tag.	retail_max_wastage_percent,'') as retail_max_wastage_percent,tag.tag_mc_type,IFNULL(tag.tag_mc_value,0) as tag_mc_value,tag.id_sub_design,
        IFNULL(tag.sales_value,0) as sales_value,IFNULL(tag.sell_rate,0) as sell_rate,
        br.name as branch_name,date_format(b.bill_date,'%d-%m-%Y') as bill_date,m.metal_code,b.sales_ref_no
        FROM ret_taging tag
        LEFT JOIN ret_product_master p ON p.pro_id=tag.product_id 
        LEFT JOIN ret_design_master des ON des.design_no=tag.design_id
        LEFT JOIN ret_purity pur ON pur.id_purity=tag.purity 
        LEFT JOIN ret_bill_details d ON d.tag_id=tag.tag_id 
        LEFT JOIN ret_billing b ON b.bill_id=d.bill_id
        LEFT JOIN ret_category c on c.id_ret_category=p.cat_id
        LEFT JOIN metal m on m.id_metal=c.id_metal 
        LEFT JOIN branch br on br.id_branch=b.id_branch
        WHERE tag.tag_status=6 AND tag.tag_process=0
        ".($data['id_branch']!='' && $data['id_branch']>0 ? " and tag.current_branch=".$data['id_branch']."" :'' )."
        group by tag.tag_id");
        //print_r($this->db->last_query());exit;
        return $sql->result_array();
	}*/
	
	function get_retagging_details($data)
	{
	    $returnData=[];
        $tagged_sql=$this->db->query("SELECT tag.tag_id,tag.tag_code,tag.tag_type,tag.product_id,tag.design_id,tag.design_for,tag.purity,tag.size,
        d.gross_wt,d.net_wt,des.design_name,pur.purity,date_format(b.bill_date,'%d-%m-%Y') as bill_date,b.bill_no,b.bill_id,tag.sales_value, p.product_short_code,p.product_name,
        IFNULL(tag.	retail_max_wastage_percent,'') as retail_max_wastage_percent,tag.tag_mc_type,IFNULL(tag.tag_mc_value,0) as tag_mc_value,tag.id_sub_design,
        IFNULL(tag.sales_value,0) as sales_value,IFNULL(tag.sell_rate,0) as sell_rate,
        br.name as branch_name,date_format(b.bill_date,'%d-%m-%Y') as bill_date,m.metal_code,b.sales_ref_no,p.stock_type,tag.piece,
        IFNULL(retag.gross_wt,0) as retagged_wt,tag.gross_wt as tag_weight,p.sales_mode,tag.trans_to_acc_stock,tag.tag_process
        FROM ret_taging tag
        LEFT JOIN ret_product_master p ON p.pro_id=tag.product_id 
        LEFT JOIN ret_design_master des ON des.design_no=tag.design_id
        LEFT JOIN ret_purity pur ON pur.id_purity=tag.purity 
        LEFT JOIN ret_bill_details d ON d.tag_id=tag.tag_id 
        LEFT JOIN ret_billing b ON b.bill_id=d.bill_id
        LEFT JOIN ret_category c on c.id_ret_category=p.cat_id
        LEFT JOIN metal m on m.id_metal=c.id_metal 
        LEFT JOIN branch br on br.id_branch=b.id_branch
        
        LEFT JOIN(SELECT r.ref_no,IFNULL(SUM(r.gross_wt),0) as gross_wt
        FROM ret_acc_stock_process_details r
        LEFT JOIN ret_acc_stock_process a ON a.id_process = r.id_process
        Where a.type = 1
        GROUP by r.ref_no) as retag ON retag.ref_no = tag.tag_id
                 
        WHERE tag.tag_status=6
        ".($data['id_branch']!='' && $data['id_branch']>0 ? " and tag.current_branch=".$data['id_branch']."" :'' )."
        group by tag.tag_id ");
        //print_r($this->db->last_query());exit;
        $tagged_item_result =  $tagged_sql->result_array();
        foreach($tagged_item_result as $items)
        {
            if(($items['sales_mode']==1) && ($items['trans_to_acc_stock']==1  && $items['tag_process']==0) )
		    {
		        $tagged_items[]=$items;
		    }
		    else if(($items['sales_mode']==2) && ($items['gross_wt']>$items['retagged_wt']))
		    {
		        $tagged_items[]=$items;
		    }
        }
        
        
        $returnData = $tagged_items;
        
        return $returnData;
	}
	
	function get_non_tag_details($data)
	{
	    $returnData=[];
        $sql =$this->db->query("SELECT d.bill_det_id,d.product_id,d.design_id,
        d.gross_wt,d.net_wt,des.design_name,pur.purity,date_format(b.bill_date,'%d-%m-%Y') as bill_date,b.bill_no,b.bill_id,d.item_cost, p.product_short_code,p.product_name,
        br.name as branch_name,date_format(b.bill_date,'%d-%m-%Y') as bill_date,m.metal_code,b.sales_ref_no,p.stock_type,d.piece
        FROM ret_bill_details d 
        LEFT JOIN ret_product_master p ON p.pro_id=d.product_id 
        LEFT JOIN ret_design_master des ON des.design_no=d.design_id
        LEFT JOIN ret_purity pur ON pur.id_purity=d.purity 
        LEFT JOIN ret_billing b ON b.bill_id=d.bill_id
        LEFT JOIN ret_category c on c.id_ret_category=p.cat_id
        LEFT JOIN metal m on m.id_metal=c.id_metal 
        LEFT JOIN branch br on br.id_branch=b.id_branch
        
        LEFT JOIN(SELECT r.ref_no,IFNULL(SUM(r.gross_wt),0) as gross_wt
        FROM ret_acc_stock_process_details r
        LEFT JOIN ret_acc_stock_process a ON a.id_process = r.id_process
        Where a.type = 5
        GROUP by r.ref_no) as retag ON retag.ref_no = d.bill_det_id
                 
        WHERE d.current_branch = ".$data['id_branch']." AND d.transferred_to_acc_stock  = 1 AND d.acc_stock_process IS NULL
        group by d.bill_det_id ");
        //print_r($this->db->last_query());exit;
        return   $sql->result_array();
	}
	
	
	function get_partly_sale_details($data)
	{
	    $sql=$this->db->query("SELECT tag.gross_wt,IFNULL(sld.sold_gwt,0) as sold_gwt,(tag.gross_wt-IFNULL(sld.sold_gwt,0)) as blc_gwt,tag.tag_id,tag.tag_code,
        p.product_name,IFNULL(s.sub_design_name,'-') as sub_design_name,des.design_name,
        sld.branch_name,sld.bill_date,sld.sales_ref_no,m.metal_code,sld.bill_id,IFNULL(retag.gross_wt,0) as retagged_wt
        FROM ret_taging tag 
        LEFT JOIN ret_product_master p ON p.pro_id=tag.product_id
        LEFT JOIN ret_design_master des ON des.design_no=tag.design_id
        left join ret_sub_design_master s on s.id_sub_design=tag.id_sub_design
        LEFT JOIN ret_category c on c.id_ret_category=p.cat_id
        LEFT JOIN metal m on m.id_metal=c.id_metal
        LEFT JOIN(SELECT SUM(s.sold_gross_wt) as sold_gwt,s.tag_id,
        br.name as branch_name,date_format(b.bill_date,'%d-%m-%Y') as bill_date,b.sales_ref_no,
        b.bill_id
        FROM ret_partlysold s
        LEFT JOIN ret_taging t ON t.tag_id=s.tag_id
        LEFT JOIN ret_bill_details d ON d.bill_det_id=s.sold_bill_det_id
        LEFT JOIN ret_billing b ON b.bill_id=d.bill_id
        LEFT JOIN branch br on br.id_branch=b.id_branch
        WHERE b.bill_status=1 AND t.is_partial=1
        GROUP by s.tag_id) as sld ON sld.tag_id=tag.tag_id
        
        LEFT JOIN(SELECT r.ref_no,IFNULL(SUM(r.gross_wt),0) as gross_wt
        FROM ret_acc_stock_process_details r
        LEFT JOIN ret_acc_stock_process a ON a.id_process = r.id_process
        Where a.type = 3
        GROUP by r.ref_no) as retag ON retag.ref_no = tag.tag_id
                 
        WHERE tag.is_partial=1 ".($data['id_branch']!='' && $data['id_branch']>0 ? " and tag.current_branch=".$data['id_branch']."" :'' )."
        HAVING gross_wt>(retagged_wt+sold_gwt)
        ORDER by sld.bill_id DESC");
        //print_r($this->db->last_query());exit;
        return $sql->result_array();
	}
	
	
	function get_old_metal_details($data)
	{
	    $sql=$this->db->query("SELECT s.old_metal_sale_id,b.bill_no,s.gross_wt,s.net_wt,c.old_metal_cat,e.amount,s.purity,DATE_FORMAT(b.bill_date,'%d-%m-%Y') as bill_date,
	    br.name as branch_name,b.bill_id,b.pur_ref_no,m.metal_code
        FROM ret_bill_old_metal_sale_details s 
        LEFT JOIN ret_estimation_old_metal_sale_details e ON e.old_metal_sale_id=s.esti_old_metal_sale_id
        LEFT JOIN ret_billing b ON b.bill_id=s.bill_id
        LEFT JOIN branch br on br.id_branch=b.id_branch
        LEFT JOIN ret_old_metal_category c ON c.id_old_metal_cat=e.id_old_metal_category
        LEFT JOIN metal m on m.id_metal = c.id_metal
        WHERE s.old_metal_sale_id IS NOT NULL AND b.bill_status=1 AND s.is_pocketed=0 and s.is_transferred=1
        ".($data['id_branch']!='' && $data['id_branch']>0 ? " and s.current_branch=".$data['id_branch']."" :'' )." 
        ORDER BY s.old_metal_sale_id");
        return $sql->result_array();
	}
	
	function get_tagging_det($tag_id)
	{
	    $sql=$this->db->query("SELECT * FROM `ret_taging` WHERE tag_id=".$tag_id." and tag_process=0 and tag_status=6");
	    return $sql->row_array();
	}
	
	function DuplicateRecord($table, $primary_key_field,$where_val,$where_field) 
	{ 
        $this->db->where($where_field, $where_val); 
        $query = $this->db->get($table); 
        return $query->result_array();
    }
    
    function getProductDivision() {
		$prod_division = $this->db->query("select * FROM ret_product_division where status=1");
		return $prod_division->result_array();
	}
	
    
    function checkNonTagItemExist($data){
		$r = array("status" => FALSE);
		
        $sql = "SELECT id_nontag_item FROM ret_nontag_item WHERE product=".$data['id_product']." 
        ".($data['id_design']!='' ? " and design=".$data['id_design']."" :'')."  
        ".($data['id_sub_design']!='' ? " and id_sub_design=".$data['id_sub_design']."" :'')." 
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
	/*function updateNTData($data,$arith){ 
		$sql = "UPDATE ret_nontag_item SET gross_wt=(gross_wt".$arith." ".$data['gross_wt']."),net_wt=(net_wt".$arith." ".$data['net_wt']."),updated_by=".$data['updated_by'].",updated_on='".$data['updated_on']."' WHERE id_nontag_item=".$data['id_nontag_item'];  
		$status = $this->db->query($sql);
		return $status;
	}*/
	
	function updateNTData($data,$arith){ 
		$sql = "UPDATE ret_nontag_item SET no_of_piece=(IFNULL(no_of_piece,0)".$arith." ".$data['gross_wt']."),gross_wt=(gross_wt".$arith." ".$data['gross_wt']."),net_wt=(net_wt".$arith." ".$data['net_wt']."),updated_by=".$data['updated_by'].",updated_on='".$data['updated_on']."' WHERE id_nontag_item=".$data['id_nontag_item'];  
		$status = $this->db->query($sql);
		return $status;
	}
	
	
	function checkPurchaseItemsStockExist($id_product,$id_branch,$purity)
	{
	    $sql=$this->db->query("SELECT * FROM `ret_purchase_item_stock_summary` WHERE type=4 AND id_product=".$id_product." AND id_branch=".$id_branch." and purity='".$purity."'");
	    //print_r($this->db->last_query());exit;
	    $res = $this->db->query($sql);
	    if($res->num_rows() > 0){
			$r = array("status" => TRUE,'id_stock_summary'=>$res->row()->id_stock_summary); 
		}else{
			$r = array("status" => FALSE); 
		} 
		return $r;
	}
	
	function get_product_details($id_product)
	{
	    $sql=$this->db->query("SELECT * FROM ret_product_master WHERE pro_id=".$id_product."");
	    return $sql->row_array();
	}
	
	function updateOldMetalStockData($id_stock_summary,$data,$arith){ 
		$sql = "UPDATE ret_purchase_item_stock_summary SET gross_wt=(gross_wt".$arith." ".$data['gross_wt']."),net_wt=(net_wt".$arith." ".$data['net_wt']."),updated_by=".$data['updated_by'].",updated_on='".$data['updated_on']."' 
		WHERE id_stock_summary=".$id_stock_summary."";  
		$status = $this->db->query($sql);
		return $status;
	}
	
	function get_stock_process_details($data)
	{
	    $sql=$this->db->query("SELECT p.id_process,b.name as branch_name,if(p.type=1,'Sales Return',if(p.type=3,'Partly Sale',if(p.type=4,'Old Metal',''))) as type,
        if(p.process_for=1,'Re-Tag',if(p.process_for=4,'Non Tag',if(p.process_for=3,'Other Issue',if(p.process_for=5,'Accounts Stock','')))) as process_for,date_format(p.date_add,'%d-%m-%Y') as date_add
        FROM ret_acc_stock_process p 
        LEFT JOIN branch b ON b.id_branch=p.id_branch
        ORDER by p.id_process DESC");
        return $sql->result_array();
	}
    
    
    
    //collection tag mapping
    
    function get_ActiveCollection()
	{
	    $sql=$this->db->query("SELECT * FROM ret_collection_master WHERE status=1");
	    return $sql->result_array();
	}
	
	function generateRefNo()
	{
		$lastno = $this->getLastCollectionDet();
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
	function getLastCollectionDet()
    {
		$sql = "SELECT max(ref_no) as ref_no FROM `ret_tag_collection_mapping` ORDER BY id_tag_mapping DESC";
		return $this->db->query($sql)->row()->ref_no;	
	}
	
	
	function get_collection_mapping_list()
	{
	    $returnData=array();
	    $sql=$this->db->query("SELECT m.id_tag_mapping,date_format(m.date_add,'%d-%M-%Y') as date_add,m.ref_no,coll.collection_name,m.total_pcs,m.total_gwt,
	    m.total_nwt,if(m.status=0,'On Sale',if(m.status=1,'Sold','Cancelled')) as coll_status,m.status,IFNULL(bill.bill_no,'-') as bill_no,IFNULL(bill.bill_id,'') as bill_id
        FROM ret_tag_collection_mapping m 
        LEFT JOIN ret_collection_master coll ON coll.id_collection=m.id_collection_master
        LEFT JOIN ret_billing bill ON bill.bill_id=m.sold_bill
        WHERE m.id_tag_mapping IS NOT NULL");
        $result = $sql->result_array();
        foreach($result as $items)
        {
            $items['tag_details']=$this->get_collection_mapping_det($items['id_tag_mapping']);
            $returnData[]=$items;
        }
        
        return $returnData;
	}
	
	
	function get_collection_mapping_det($id_tag_mapping)
	{
	    $sql=$this->db->query("SELECT tag.tag_code,IFNULL(tag.old_tag_id,'') as old_tag,br.name as branch_name,
	    pro.product_name,des.design_name,subDes.sub_design_name,tag.piece,tag.gross_wt,tag.net_wt,d.tag_id
        FROM ret_tag_collection_mapping_details d  
        LEFT JOIN ret_taging tag ON tag.tag_id=d.tag_id
        LEFT JOIN ret_product_master pro ON pro.pro_id=tag.product_id 
        LEFT JOIN ret_design_master des ON des.design_no=tag.design_id
        LEFT JOIN ret_sub_design_master subDes ON subDes.id_sub_design=tag.id_sub_design
        LEFT JOIN branch br ON br.id_branch=tag.current_branch
        WHERE d.id_tag_mapping=".$id_tag_mapping."");
        //print_r($this->db->last_query());exit;
        return $sql->result_array();
	}
	
    //collection tag mapping
    
    function get_order_linked_tags($SearchTxt,$branch)
	{
		//print_r($SearchTxt);exit;
        $sql=$this->db->query("SELECT  cod.id_orderdetails,cod.id_customerorder,co.order_no,t.tag_code,t.tag_lot_id,p.product_short_code,p.product_name,des.design_name,t.gross_wt,t.net_wt,IF(cod.orderstatus = 0, 'Pending',IF(cod.orderstatus = 1, 'Process',IF(cod.orderstatus = 2, 'Confirm',IF(cod.orderstatus = 3, 'Work in progress',IF(cod.orderstatus = 4, 'Delivery Ready',IF(cod.orderstatus = 5, 'Delivered',IF(cod.orderstatus = 6, 'Canceled','Closed'))))))) as order_status,weight,t.tag_id as value,
		t.tag_code as label,sub.sub_design_name,t.tag_id
           FROM `customerorderdetails` cod 
			LEFT JOIN customerorder co on co.id_customerorder = cod.id_customerorder
			LEFT JOIN ret_taging t ON t.id_orderdetails=cod.id_orderdetails
			LEFT JOIN ret_product_master p ON p.pro_id=t.product_id
			LEFT JOIN ret_design_master des ON des.design_no=t.design_id
			LEFT JOIN ret_sub_design_master sub ON sub.id_sub_design=t.id_sub_design
		  WHERE cod.orderstatus = 4 and t.tag_code LIKE '%".$SearchTxt."%' ".($branch!='' ? " and t.current_branch=".$branch."" :'')."");
		 // print_r($this->db->last_query());exit;
        return $sql->result_array();
	}
    
    
    function get_img_by_id($tag_id)
    {
         $sql=$this->db->query("SELECT image,is_default FROM `ret_taging_images` WHERE tag_id=".$tag_id."");
        return $sql->result_array();
    }
	public function deleteData_bulk_img($tag_id)
    {
        $sql=$this->db->query("delete FROM `ret_taging_images` WHERE tag_id=".$tag_id."");
        return $sql;
    }
	function checkImageAvail($tag_id)
    {
        $sql=$this->db->query("SELECT * FROM `ret_taging_images` WHERE is_default=1 and tag_id=".$tag_id."");
        if($sql->num_rows()==0)
	    {
	        return TRUE;
	    }else{
	         return FALSE;
	    }
    }
	function check_isDefault_img($tag_id)
    {
         $sql=$this->db->query("SELECT image,is_default FROM `ret_taging_images` WHERE is_default=1 and tag_id= ". $tag_id);
        return $sql->num_rows();
    }
    
    
    function get_CustomerOrdersDetails($data)
    {
        $sql = $this->db->query("SELECT c.order_no,d.id_orderdetails,p.product_name,des.design_name,subDes.sub_design_name,d.weight,
        d.id_product,d.design_no,d.id_sub_design
        FROM customerorderdetails d 
        LEFT JOIN customerorder c ON c.id_customerorder = d.id_customerorder
        LEFT JOIN ret_product_master p ON p.pro_id = d.id_product
        LEFT JOIN ret_design_master des ON des.design_no = d.design_no
        LEFT JOIN ret_sub_design_master subDes ON subDes.id_sub_design = d.id_sub_design
        WHERE d.orderstatus = 3 AND c.order_type = 2 AND d.id_customerorder = ".$data['id_customerorder']." ");
        //print_r($this->db->last_query());exit;
        return $sql->result_array();
    }
    
    function get_CustomerOrder($data)
    {
        $sql=$this->db->query("SELECT c.id_customerorder,c.order_no
        FROM customerorderdetails d 
        LEFT JOIN customerorder c ON c.id_customerorder = d.id_customerorder
        LEFT JOIN ret_product_master p ON p.pro_id = d.id_product
        LEFT JOIN ret_design_master des ON des.design_no = d.design_no
        LEFT JOIN ret_sub_design_master subDes ON subDes.id_sub_design = d.id_sub_design
        WHERE d.orderstatus = 3 AND c.order_type = 2  
        ".($data['id_branch']!='' ? " and c.order_from=".$data['id_branch']."" :'')."
        ".($data['fin_year_code']!='' ? " and c.fin_year_code=".$data['fin_year_code']."" :'')."
        group by d.id_customerorder");
        //print_r($this->db->last_query());exit;
        return $sql->result_array();
    }
    
    function get_mc_va_limit($id_product, $id_design, $id_sub_design) {
		$id_sub_design_mapping = 0;
        $mc_min = 0;
        
        $wastag_min = 0;
		$margin_mrp = 0;
		$wastage_type = 0;
		$weight_range = array();
        
        $sql = "SELECT
					id_sub_design_mapping,
					wastage_type,
                    IFNULL(mc_min,0) AS mc_min, 
                    IFNULL(wastag_min ,0) AS wastag_min,
					IFNULL(margin_mrp ,0) AS margin_mrp
                FROM ret_sub_design_mapping 
                WHERE id_product = ".$id_product." AND id_design=".$id_design." AND id_sub_design = ".$id_sub_design;
        //echo $sql;exit;
		
        $query_details = $this->db->query($sql);
        if ($query_details->num_rows() > 0)
        {
            $row = $query_details->row_array(); 
			$id_sub_design_mapping = $row['id_sub_design_mapping'];
			$wastage_type = $row['wastage_type'];
            $mc_min = $row['mc_min'];
            $wastag_min = $row['wastag_min'];
			$margin_mrp = $row['margin_mrp'];
			$sql = "SELECT
						IFNULL(mcrg_min,0) AS mc_min, 
						IFNULL(wc_min,0) AS wastag_min
					FROM ret_design_weight_range_wc 
					WHERE id_sub_design_mapping = ".$id_sub_design_mapping;
			$query_details = $this->db->query($sql);
			if ($query_details->num_rows() > 0) {
				$weight_range = $query_details->row_array();
			}
        }
        $result_arr = array("id_sub_design_mapping" => $id_sub_design_mapping, "wastage_type" => $wastage_type, "mc_min" => $mc_min, "wastag_min" => $wastag_min, "margin_mrp" => $margin_mrp, 'weight_range' => $weight_range);
		return $result_arr;
	}
	function get_old_tag($old_tag_id) {
       
        $sql=$this->db->query("SELECT tag_code,old_tag_id FROM ret_taging WHERE old_tag_id='$old_tag_id'");
        return $sql->result_array();
    }
    
}
?>
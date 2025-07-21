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

					concat(tag.ref_no,'-',e.emp_code) as ref_no

				from ret_taging as tag

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

				ifnull(design_code,'') as design_code ,created_by,des.design_name,b.name,tag.tot_print_taken

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

		$sql = $this->db->query("SELECT rtc.tag_charge_id, rtc.tag_id, rtc.charge_id, rtc.charge_value, c.code_charge, c.tag_display FROM ret_taging_charges AS rtc LEFT JOIN ret_charges AS c ON rtc.charge_id = c.id_charge  WHERE tag_id='".$tag_id."' ".$where);  

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

	

	

	function getlastTagCode()

    {

        $sql=$this->db->query("SELECT t.tag_id,t.tag_code FROM ret_taging t where t.tag_code is not null ORDER by tag_id DESC LIMIT 1");

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

			

			$lot_product    =$this->input->post('lot_product');

			

			$from_weight    =$this->input->post('from_weight');

			

			$to_weight      =$this->input->post('to_weight');

			

			$mc_value       =$this->input->post('mc_value');

			

			$making_per     =$this->input->post('making_per');

			

			$id_design     =$this->input->post('id_design');
			
			$id_sub_design     =$this->input->post('id_sub_design');

			

			$id_mc_type     =$this->input->post('id_mc_type');

			

			$sql="select t.tag_id,t.tag_code,t.gross_wt,IFNULL(t.less_wt,'-') as less_wt,t.net_wt,t.calculation_based_on,

			t.retail_max_wastage_percent,t.tag_mc_type,t.tag_mc_value,t.sales_value,t.piece,t.tag_lot_id,t.current_branch,

			concat(d.design_name,'-',design_code) as design_name,

			DATE_FORMAT(t.tag_datetime,'%d-%m-%Y')as tag_datetime,

			concat(p.product_name,'-',p.product_short_code) as product_name,IFNULL(s.stone_price,0) as stone_price,

			m.tgrp_id as tax_group_id,IF(t.tag_mc_type=1,'Per Gram','Per Piece') as mc_type,IFNULL(s.sub_design_name,'-') as sub_design_name

			from ret_taging t

			left join ret_design_master d on d.design_no=t.design_id

			left join ret_product_master p on p.pro_id=t.product_id
			
			left join ret_sub_design_master s on s.id_sub_design=t.id_sub_design

			left JOIN ret_lot_inwards_detail id on id.id_lot_inward_detail=t.id_lot_inward_detail

			left join ret_category cat on cat.id_ret_category=p.cat_id

		    left join metal m on m.id_metal=cat.id_metal

			left join(select st.tag_id,sum(st.amount) as stone_price from ret_taging_stone st) s on s.tag_id=t.tag_id

			where t.tag_id is not null and t.tag_status=0

			

			".($id_branch!='' && $id_branch!=0 ?" and t.current_branch=".$id_branch." ":'' )."

			

			".($tag_id!='' ? " and t.tag_id=".$tag_id."" :'')."

			

			".($lot_product!='' ? " and id.lot_product=".$lot_product."" :'')."

			

			".($from_weight!='' ?" and t.gross_wt>=".$from_weight."" :'')."

			

			".($to_weight!='' ? " and t.gross_wt<=".$to_weight."" :'')."

			

			".($mc_value!='' ? " and t.tag_mc_value=".$mc_value."" :'')."

			

			".($making_per!='' ? " and t.retail_max_wastage_percent=".$making_per."" :'')."

			

			".($id_design!='' ? " and t.design_id=".$id_design."" :'')."
			
			".($id_sub_design!='' ? " and t.id_sub_design=".$id_sub_design."" :'')."

			

			".($id_mc_type!='' ? " and t.tag_mc_type=".$id_mc_type."" :'')."

			

			";

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

			                'metal_rate'                =>$this->get_branchwise_rate($tag['current_branch'])

			               );

			}

			return $data;

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

	function getAvailableLots()

	{

		$lot_data=$this->db->query("SELECT lot_in.lot_no,date_format(lot_in.lot_date,'%Y-%m-%d')as lot_date,lot_in.id_category,lot_in.id_purity,c.name as category_name,p.purity as purity_name,

			(lot.total_gross_wt-ifnull(tag.tag_gross_wt,0)) as lot_bal_wt,

			(lot.total_no_of_piece-ifnull(tag.tag_piece,0)) as lot_bal_pcs,

			(lot.total_net_wt-ifnull(tag.tag_net_wt,0)) as lot_tag_net_wt,

			m.metal,lot_in.lot_received_at,lot.total_no_of_piece,tag.tag_piece

			from  ret_lot_inwards lot_in

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

					) tag on tag.tag_lot_id=lot_in.lot_no

			LEFT JOIN (

						SELECT d.lot_no,

						sum(ifnull(d.gross_wt,0)) as  total_gross_wt,

						sum(ifnull(d.net_wt,0)) as  total_net_wt,

						sum(ifnull(d.no_of_piece,0)) as  total_no_of_piece

						from ret_lot_inwards_detail d

						group by d.lot_no

					) lot on lot.lot_no=lot_in.lot_no

            WHERE lot_in.stock_type=1 order by lot_in.lot_no DESC");

	//echo $this->db->last_query();exit;

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

							CONCAT(p.product_name,'-',p.product_short_code) as product_name,p.hsn_code,IFNULL(i.order_no,'') as order_no

							 from ret_lot_inwards_detail d

							 LEFT JOIN ret_lot_inwards i on i.lot_no=d.lot_no

							 LEFT JOIN ret_product_master p on p.pro_id=d.lot_product

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

	function get_lot_inward_details($lot_no,$lot_product,$lot_id_design)

	{

	    $data['lot_inward_detail']=array();

	    

        $sql= $this->db->query("SELECT d.lot_no,d.id_lot_inward_detail,d.lot_product,d.lot_id_design,d.no_of_piece,

        des.design_name,p.product_name,p.hsn_code,p.product_short_code,p.calculation_based_on,

        d.gross_wt_uom,d.net_wt_uom,d.less_wt,d.less_wt_uom,d.sell_rate,

        ifnull(d.wastage_percentage,0) as wastage_percentage,d.mc_type,d.making_charge,d.precious_st_pcs,

        d.precious_st_wt,d.precious_st_uom,d.semi_precious_st_pcs,

        d.semi_precious_st_wt,d.normal_st_pcs,d.normal_st_wt,d.gross_wt,d.net_wt,d.design_for,

        IFNULL(d.normal_st_certif,'') as normal_st_certif,IFNULL(d.precious_st_certif,'') as precious_st_certif,

        IFNULL(d.semiprecious_st_certif,'') as semiprecious_st_certif,m.id_metal,c.cat_code,p.sales_mode,m.tgrp_id as tax_group_id,l.id_purity

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

	    $sql= $this->db->query("SELECT t.tag_lot_id,sum(t.gross_wt) as gross_wt,sum(ifnull(t.less_wt,0)) as less_wt,sum(t.net_wt) as net_wt,

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

				purity, (s.value) as size, uom, piece, less_wt,tag.net_wt, gross_wt, 

				tag.calculation_based_on,tag.retail_max_wastage_percent,

				tag.tag_mc_type,tag.tag_mc_value,

				retail_max_mc, halmarking, sales_value,tag.image,

				current_branch, current_counter, p.metal_type,  

				ifnull(design_code,'') as design_code,tag.created_by,des.design_name,b.name,

				p.product_name,tag.product_id,k.code_karigar,c.short_code,tag.tot_print_taken,

				p.sales_mode,p.product_short_code,tag.tag_mark,des.mc_cal_type,ifnull(tag_stn_detail.stn_amount,'0') as stn_amount,

				ifnull(tag_stn_detail.stn_wt,'0') as stn_wt, ifnull(tag_stn_detail.uom_name, '') as stuom, ifnull(tag_stn_detail.uom_short_code, '') as stuom_short_code,
				ifnull(tag.hu_id, '-') as hu_id 

				FROM ret_taging as tag 

				join company c

				LEFT JOIN ret_design_master as des ON des.design_no = tag.design_id 

				LEFT JOIN ret_product_master p on p.pro_id=tag.product_id 

				left join branch b on b.id_branch=tag.current_branch

				LEFT JOIN ret_lot_inwards d on d.lot_no=tag.tag_lot_id

				left JOIN ret_karigar k on k.id_karigar=d.gold_smith

				LEFT JOIN ret_size s on s.id_size=tag.size

                LEFT JOIN (SELECT tag_id,sum(amount) as stn_amount,sum(wt) as stn_wt, uom_name, uom_short_code

                FROM `ret_taging_stone` as retst

                LEFT JOIN ret_uom as stuom ON stuom.uom_id = retst.uom_id 

                GROUP by tag_id) as tag_stn_detail ON tag_stn_detail.tag_id = tag.tag_id

				WHERE tag.tag_id='".$tag_id."'");

			//print_r($this->db->last_query());exi.t;

		return $sql->result_array()[0];

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

				current_branch, current_counter,

				ifnull(design_code,'') as design_code,tag.created_by,des.design_name,b.name,

				p.product_name,tag.product_id,k.code_karigar,c.short_code,tag.tot_print_taken,

				p.sales_mode,p.product_short_code,tag.tag_mark,

				ifnull(tag_stn_detail.stn_amount,'0') as stn_amount,

				ifnull(tag_stn_detail.stn_wt,'0') as stn_wt, ifnull(tag_stn_detail.uom_name, '') as stuom, ifnull(tag_stn_detail.uom_short_code, '') as stuom_short_code,
				ifnull(tag.hu_id, '-') as hu_id  , p.metal_type

				FROM ret_taging as tag 

				join company c

				LEFT JOIN ret_design_master as des ON des.design_no = tag.design_id 

				LEFT JOIN ret_product_master p on p.pro_id=tag.product_id

				left join branch b on b.id_branch=tag.current_branch

				LEFT JOIN ret_lot_inwards d on d.lot_no=tag.tag_lot_id

				left JOIN ret_karigar k on k.id_karigar=d.gold_smith

				LEFT JOIN ret_size s on s.id_size=tag.size

				LEFT JOIN (SELECT tag_id,sum(amount) as stn_amount,sum(wt) as stn_wt, uom_name, uom_short_code

                FROM `ret_taging_stone` 

				LEFT JOIN ret_uom as stuom ON stuom.uom_id = retst.uom_id 

                GROUP by tag_id) as tag_stn_detail ON tag_stn_detail.tag_id = tag.tag_id

				WHERE tag_lot_id='".$lot_id."' and tag.tot_print_taken=0 ORDER BY tag.tag_id DESC");

			//print_r($this->db->last_query());exit;

		return $sql->result_array();

	}
    
    	function getTagByRefNo($ref_no)
	{
		$sql = $this->db->query("SELECT tag.tag_id,tag.tag_code,tag.counter,
				date_format(tag_datetime,'%d-%m-%Y') as tag_datetime,sell_rate,item_rate,
				tag.tag_type, tag_lot_id,id_lot_inward_detail, design_id, cost_center, 
				purity, (s.value) as size, uom, piece, less_wt,tag.net_wt, gross_wt, 
				tag.calculation_based_on,tag.retail_max_wastage_percent,
				tag.tag_mc_type,tag.tag_mc_value,
				retail_max_mc, halmarking, sales_value,tag.image,
				current_branch, current_counter,
				ifnull(design_code,'') as design_code,tag.created_by,des.design_name,b.name,
				p.product_name,tag.product_id,k.code_karigar,c.short_code,tag.tot_print_taken,
				p.sales_mode,p.product_short_code,tag.tag_mark,
					ifnull(tag_stn_detail.stn_amount,'0') as stn_amount,

				ifnull(tag_stn_detail.stn_wt,'0') as stn_wt, ifnull(tag_stn_detail.uom_name, '') as stuom, ifnull(tag_stn_detail.uom_short_code, '') as stuom_short_code,
				ifnull(tag.hu_id, '-') as hu_id , p.metal_type   
				FROM ret_taging as tag  
				join company c
				LEFT JOIN ret_design_master as des ON des.design_no = tag.design_id 
				LEFT JOIN ret_product_master p on p.pro_id=tag.product_id
				left join branch b on b.id_branch=tag.current_branch
				LEFT JOIN ret_lot_inwards d on d.lot_no=tag.tag_lot_id
				left JOIN ret_karigar k on k.id_karigar=d.gold_smith
				LEFT JOIN ret_size s on s.id_size=tag.size 
				LEFT JOIN (SELECT tag_id,sum(amount) as stn_amount,sum(wt) as stn_wt, uom_name, uom_short_code

                FROM `ret_taging_stone` as retst

                LEFT JOIN ret_uom as stuom ON stuom.uom_id = retst.uom_id 

                GROUP by tag_id) as tag_stn_detail ON tag_stn_detail.tag_id = tag.tag_id 
               
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

		d.no_of_piece as no_of_piece,m.tgrp_id as tax_group_id,d.gross_wt as gross_wt,d.net_wt as net_wt,j.id_vendor

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
			IFNULL(concat(s.value,'-',s.name),'') as size_name,IFNULL(d.sub_design_name,'') as sub_design_name,IFNULL(tag.id_sub_design,'') as id_sub_design
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
			".($data['id_branch'] != '' && $data['id_branch'] != 0 ? " and date(e.estimation_datetime)='".$dcData['entry_date']."'": '')."
			".($data['est_no'] != '' ? " and e.esti_no=".$data['est_no']."": '')."
			");
	    }
	    else
	    {
	        $sql=$this->db->query("SELECT tag.tag_code,IFNULL(tag.gross_wt,0) as gross_wt,IFNULL(tag.net_wt,0) as net_wt,tag.tag_id,
			tag.sales_value,tag.current_branch,b.name as branch_name,tag.tag_mark,
			date_format(tag.tag_datetime,'%d-%m-%Y') as tag_date,p.product_name,des.design_name,tag.tag_lot_id,tag.design_id,IFNULL(tag.size,'') as size,
			IFNULL(concat(s.value,'-',s.name),'') as size_name,IFNULL(d.sub_design_name,'') as sub_design_name,IFNULL(tag.id_sub_design,'') as id_sub_design
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



        IFNULL(lot_inw.order_no,'') as order_no,des.design_name,IFNULL(s.sub_design_name,'-') as sub_design_name



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



        WHERE tag.tag_status=0 and tag.tag_code  LIKE '%".$SearchTxt."%' ".($branch!='' ? " and tag.current_branch=".$branch."" :'')."");



        //print_r($this->db->last_query());exit;



        return $data->result_array();



    }

    

    function getOrdersBySearch($SearchTxt,$branch,$id_product,$id_design)

    {

        $sql=$this->db->query("SELECT c.id_customerorder as value,d.id_product,d.id_customerorder,c.order_no as label

        FROM customerorder c 

        LEFT JOIN customerorderdetails d ON d.id_customerorder=c.id_customerorder

        LEFT JOIN ret_product_master p ON p.pro_id=d.id_product

        WHERE d.orderstatus=3 and c.order_no LIKE '$SearchTxt' ".($branch!='' ? " and order_from=".$branch."" :'')." and d.id_product=".$id_product." and d.design_no=".$id_design."");

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
	    $sql=$this->db->query("
	    SELECT des.design_no,des.design_name
        FROM ret_product_mapping p 
        LEFT JOIN ret_product_master pro ON pro.pro_id=p.pro_id
        LEFT JOIN ret_design_master des ON des.design_no=p.id_design
        where des.design_no is not null ".($data['id_product']!='' ? " and p.pro_id=".$data['id_product']."" :'')."
        ");
        //print_r($this->db->last_query());exit;
        return $sql->result_array();
	}
	
	
	function get_ActiveSubDesingns($data)
	{
	    $sql=$this->db->query("SELECT d.id_sub_design,d.sub_design_name
        FROM ret_sub_design_mapping s 
        LEFT JOIN ret_sub_design_master d ON d.id_sub_design=s.id_sub_design
        WHERE s.id_sub_design is NOT NULL
         ".($data['design_no']!='' ? " and s.id_design=".$data['design_no']."" :'')."
         ".($data['id_product']!='' ? " and s.id_product=".$data['id_product']."" :'')."
         ");
        return $sql->result_array();
	}
	
	
	function get_wastage_settings_details()
	{
	    $return_data=array();
	    $sql=$this->db->query("SELECT m.id_sub_design_mapping,m.id_product,m.id_design,m.id_sub_design,m.mc_cal_type,m.mc_cal_value,m.wastage_type,m.wastag_value
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
	

}

?>
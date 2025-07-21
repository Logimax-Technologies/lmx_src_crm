<?php
if( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ret_billing_model extends CI_Model
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
	
	
	function ajax_getBillingList()
    {
		$sql = $this->db->query("SELECT bill_id, bill_no, 
				date_format(bill_date, '%d-%m-%Y %H:%i') as bill_date, 
				firstname, tot_bill_amt 
				FROM ret_billing as bill 
				LEFT JOIN customer as cus ON cus.id_customer = bill.bill_cus_id 
				ORDER BY bill.bill_id desc");
		return $sql->result_array();
	}
	function get_entry_records($est_id)
	{
		$sql = $this->db->query("SELECT estimation_id, 
				concat(firstname, '-', mobile) as cus_name,  	
				date_format(estimation_datetime, '%d-%m-%Y %H:%i:%s') as estimation_datetime, 
				cus_id, created_by, date_format(created_time, '%d-%m-%Y %H:%i:%s') as created_time,
				has_converted_order, discount, gift_voucher_amt, total_cost, est.id_branch 
				FROM ret_estimation as est 
				LEFT JOIN customer as cus ON cus.id_customer = est.cus_id 
				WHERE est.estimation_id ='".$est_id."'");
		return $sql->result_array()[0];
	}
	function getOtherEstimateItemsDetails($est_id)
	{
		$return_data = array("item_details" => array(), "old_matel_details" => array(), "stone_details" => array(), "other_material_details" => array(), "voucher_details" => array(), "chit_details" => array());
		
		$items_query = $this->db->query("SELECT est_item_id, esti_id, item_type, 
					   est.product_id, tag_id, design_id, quantity, est.purity as purid, size, uom, piece, 
                       est.less_wt, est.net_wt, est.gross_wt, 
					   est.calculation_based_on, est.wastage_percent, est.mc_per_grm, 
					   item_cost, product_short_code, product_name, 
                       design_code, design_name, pur.purity as purname
					   FROM ret_estimation_items as est 
                       LEFT JOIN ret_product_master as pro ON pro.pro_id = est.product_id 
                       LEFT JOIN ret_design_master as des ON des.design_no = est.design_id 
                       LEFT JOIN ret_purity as pur ON pur.id_purity = est.purity 
					   WHERE est.esti_id ='".$est_id."'");
		$return_data["item_details"] = $items_query->result_array();
		
		$old_matel_query = $this->db->query("SELECT old_metal_sale_id, est_id, 
						   id_category, type, item_type, gross_wt, 
						   round((gross_wt - stone_wt),3) as ls_wt,
                           if(type = 1, 'Melting', 'Retag') as reusetype,
                           if(item_type = 1, 'Ornament', if(item_type = 2, 'Coin', if(item_type = 3, 'Bar',''))) as receiveditem,
						   stone_wt, dust_wt, est_old.purity as purid, wastage_percent,
						   wastage_wt, rate_per_gram, amount, 
						   pur.purity as purname, met.metal 
						   FROM ret_estimation_old_metal_sale_details as est_old 
						   LEFT JOIN ret_purity as pur ON pur.id_purity = est_old.purity 
						   LEFT JOIN metal as met ON met.id_metal = est_old.id_category 
						   WHERE est_old.est_id = '".$est_id."'");
		$return_data["old_matel_details"] = $old_matel_query->result_array();
		
		$est_stone_query = $this->db->query("SELECT est_item_stone_id, 
						   est_id, est_st.stone_id, pieces, wt, price, 
                           stone_name, stone_code, uom_name, uom_short_code 
						   FROM ret_estimation_item_stones as est_st 
                           LEFT JOIN ret_stone as st ON st.stone_id = est_st.stone_id 
                           LEFT JOIN ret_uom as um ON um.uom_id = st.uom_id 
						   WHERE est_st.est_id = '".$est_id."'");
		$return_data["stone_details"] = $est_stone_query->result_array();
		
		$est_material_query = $this->db->query("SELECT est_other_material_id,
							  est_id, est_mt.material_id, wt, price ,
							  material_name, material_code, 
							  uom_name, uom_short_code 
							  FROM ret_estimation_item_other_materials as est_mt 
							  LEFT JOIN ret_material as mat ON mat.material_id = est_mt.material_id 
							  LEFT JOIN ret_uom as um ON um.uom_id = mat.uom_id 
							  WHERE est_mt.est_id = '".$est_id."'");
		$return_data["other_material_details"] = $est_material_query->result_array();
		$est_voucher_query = $this->db->query("SELECT gift_voucher_id, est_id,
							voucher_no, gift_voucher_details, gift_voucher_amt 
						   FROM ret_est_gift_voucher_details as est_vouch 
						   WHERE est_vouch.est_id  = '".$est_id."'");
		$return_data["voucher_details"] = $est_voucher_query->result_array();
		
		$est_chit_query = $this->db->query("SELECT chit_ut_id, est_id, 
						   scheme_account_id, utl_amount 
						   FROM ret_est_chit_utilization as est_chit  
						   WHERE est_chit.est_id = '".$est_id."'");
		$return_data["chit_details"] = $est_chit_query->result_array();
		return $return_data;
	}
	function get_empty_record()
    {
		$emptyquery = $this->db->field_data('ret_billing');
		$emptydata = array();
		foreach ($emptyquery as $field)
		{
			$emptydata[$field->name] = $field->default;
		}
		$emptydata['bill_date'] = date('d-m-Y H:i:s');
		$emptydata['cus_name'] 			  = '';
		return $emptydata;
	}
	function createNewCustomer($cusname, $cusmobile, $branch)
	{
		$customer_check_query = $this->db->query("SELECT * FROM customer WHERE mobile='".$cusmobile."'");
		if($customer_check_query->num_rows() == 0){
			$insert_data = array("firstname" => $cusname, "id_branch" => $branch, "mobile" => $cusmobile, "username" => $cusmobile, "passwd" => $cusmobile);
			$cus_insert_id = $this->insertData($insert_data, "customer");
			if(!empty($cus_insert_id)){
				$insert_data["id_customer"] = $cus_insert_id;
				return array("success" => TRUE, "message" => "Customer details added successfully", "response" => $insert_data);
			}else{
				return array("success" => FALSE, "message" => "Could not add customer, please try again", "response" => array());
			}
		}else{
			return array("success" => FALSE, "message" => "Given mobile number already exist", "response" => $customer_check_query->row());
		}
	}
	function getEstimationDetails($cusId, $estId){
		$return_data = array("item_details" => array(), "old_matel_details" => array(), "stone_details" => array(), "other_material_details" => array(), "voucher_details" => array(), "chit_details" => array());
		$items_query = $this->db->query("SELECT est_itms.est_item_id, esti_id, item_type, 
					   ifnull(est_itms.product_id, '') as product_id, ifnull(est_itms.tag_id, '') as tag_id, 
					   ifnull(design_id, '') as design_id, quantity, ifnull(pro.hsn_code,'') as hsn_code, 
					   est_itms.purity as purid, size, uom, piece, 
                       ifnull(est_itms.less_wt,'') as less_wt, est_itms.net_wt, est_itms.gross_wt, 
					   est_itms.calculation_based_on, est_itms.wastage_percent, est_itms.mc_value, est_itms.mc_type, 
					   item_cost, ifnull(product_short_code, '-') as product_short_code, 
					   ifnull(product_name, '-') as product_name, est_itms.is_partial,est_itms.discount,
                       ifnull(design_code, '-') as design_code, 
					   ifnull(design_name, '') as design_name, pur.purity as purname, 
					   mt.tgrp_id as tax_group_id , tgrp_name, ifnull(pro.metal_type,'') as metal_type, 
					   ifnull(des.fixed_rate,0) as fixed_rate,
					   if(est_itms.tag_id != null,stn_price,stn_amount) as stone_price,
					   if(est_itms.tag_id != null,stn_wgt,stn_wt) as stn_wgt,
					   if(est_itms.tag_id != null,othermat_amount,other_mat_price) as othermat_amount,
					   if(est_itms.tag_id != null,othermat_wt,other_mat_wgt) as othermat_wt
					   FROM ret_estimation as est 
					   LEFT JOIN ret_estimation_items as est_itms ON est_itms.esti_id = est.estimation_id
					   LEFT JOIN (SELECT est_item_id,sum(price) as stn_price,sum(wt) as stn_wgt FROM `ret_estimation_item_stones` GROUP by est_item_id) as stn_detail ON stn_detail.est_item_id = est_itms.est_item_id
					   LEFT JOIN (SELECT est_item_id,sum(price) as other_mat_price,sum(price) as other_mat_wgt FROM `ret_estimation_item_other_materials` GROUP by est_item_id) as est_oth_mat ON est_oth_mat.est_item_id = est_itms.est_item_id
					   LEFT JOIN (SELECT tag_id,sum(amount) as stn_amount,sum(wt) as stn_wt FROM `ret_taging_stone` GROUP by tag_id) as tag_stn_detail ON tag_stn_detail.tag_id = est_itms.tag_id
					   LEFT JOIN (SELECT tag_id,sum(price) as othermat_amount,sum(wt) as othermat_wt FROM `ret_taging_other_materials` GROUP by tag_id) as tag_other_mat ON tag_other_mat.tag_id = est_itms.tag_id
                       LEFT JOIN ret_product_master as pro ON pro.pro_id = est_itms.product_id
					   LEFT JOIN ret_category c on c.id_ret_category=pro.cat_id
					   LEFT JOIN metal mt on mt.id_metal=c.id_metal 
                       LEFT JOIN ret_design_master as des ON des.design_no = est_itms.design_id 
                       LEFT JOIN ret_purity as pur ON pur.id_purity = est_itms.purity 
                       LEFT JOIN ret_taxgroupmaster as txgrp ON txgrp.tgrp_id = mt.tgrp_id 
					   WHERE est.estimation_id ='".$estId."' AND cus_id ='".$cusId."' AND est_itms.est_item_id IS NOT NULL");
					     
		$return_data["item_details"] = $items_query->result_array();
		$old_matel_query = $this->db->query("SELECT old_metal_sale_id, est_id, 
						   id_category, type, item_type, gross_wt, 
						   ifnull(dust_wt,0.000) as dust_wt,ifnull(stone_wt,0.000) as stone_wt,
						   round((ifnull(dust_wt,0.000) - ifnull(stone_wt,0.000)),3) as less_wt,purpose,
                           if(type = 1, 'Melting', 'Retag') as reusetype,
                           if(item_type = 1, 'Ornament', if(item_type = 2, 'Coin', if(item_type = 3, 'Bar',''))) as receiveditem, est_old.purity as purid, wastage_percent,
						   wastage_wt, rate_per_gram, amount, 
						   pur.purity as purname, met.metal 
						   FROM ret_estimation as est 
						   LEFT JOIN ret_estimation_old_metal_sale_details as est_old ON est_old.est_id = est.estimation_id
						   LEFT JOIN ret_purity as pur ON pur.id_purity = est_old.purity 
						   LEFT JOIN metal as met ON met.id_metal = est_old.id_category 
						   WHERE est.estimation_id ='".$estId."' AND cus_id ='".$cusId."' AND old_metal_sale_id IS NOT NULL");
		$return_data["old_matel_details"] = $old_matel_query->result_array();
		$est_voucher_query = $this->db->query("SELECT gift_voucher_id, est_id,
							voucher_no, gift_voucher_details, est_vouch.gift_voucher_amt 
						   FROM ret_estimation as est 
						   LEFT JOIN ret_est_gift_voucher_details as est_vouch ON est_vouch.est_id = est.estimation_id
						   WHERE est.estimation_id ='".$estId."' AND cus_id ='".$cusId."' AND voucher_no IS NOT NULL");
		$return_data["voucher_details"] = $est_voucher_query->result_array();
		
		$est_chit_query = $this->db->query("SELECT chit_ut_id, est_id, 
						   scheme_account_id, utl_amount 
						   FROM ret_estimation as est 
						   LEFT JOIN ret_est_chit_utilization as est_chit ON est_chit.est_id = est.estimation_id  
						   WHERE est.estimation_id ='".$estId."' AND cus_id ='".$cusId."' AND scheme_account_id IS NOT NULL");
		$return_data["chit_details"] = $est_chit_query->result_array();
		return $return_data;
	} 
	
	function getAllTaxgroupItems(){
		$return_data = array();
		$taxitems = $this->db->query("SELECT tgi_tgrpcode, tgrp_name, tgi_calculation, tgi_type, tax_percentage 
									FROM ret_taxgroupitems as tx_grp_itm 
									LEFT JOIN ret_taxgroupmaster as grp ON grp.tgrp_id = tx_grp_itm.tgi_tgrpcode 
									LEFT JOIN ret_taxmaster as tx ON tx.tax_id = tx_grp_itm.tgi_taxcode");
		if($taxitems->num_rows() > 0){
			$return_data = $taxitems->result_array();
		}
		return $return_data;
	}
	function getAvailableCustomers($SearchTxt){
		$data = $this->db->query("SELECT id_customer as value, concat(firstname,'-',username) as label, reference_no, id_branch, id_village, title, initials, lastname, firstname, date_of_birth, date_of_wed, gender, id_address, id_employee, email, mobile, phone, nominee_name, nominee_relationship, nominee_mobile, cus_img, pan, pan_proof, ispan_req, voterid, voterid_proof, rationcard, rationcard_proof, comments, username, passwd, profile_complete, active, is_new, date_add, custom_entry_date, date_upd, added_by, notification, gst_number, cus_ref_code, is_refbenefit_crt_cus, emp_ref_code, is_refbenefit_crt_emp, religion, kyc_status FROM customer WHERE username like '%".$SearchTxt."%' OR mobile like '%".$SearchTxt."%' OR firstname like '%".$SearchTxt."%'");
		return $data->result_array();
	}
	function getTaggingBySearch($SearchTxt){
		$data = $this->db->query("SELECT tag.tag_id, 
				tag_code, tag_datetime, tag.tag_type, tag_lot_id, ifnull(pro.hsn_code,'') as hsn_code, 
				ifnull(tag.design_id,'') as design_id, cost_center, tag.purity, size, uom, piece, tag.less_wt, tag.net_wt, tag.gross_wt, ifnull(tag.less_wt,0) as less_wt,
				tag.calculation_based_on, retail_max_wastage_percent, tag_mc_type,retail_max_mc,tag_mc_value,
				halmarking, sales_value, mt.tgrp_id, tag.tag_status, product_name, product_short_code, lot_product, pur.purity as purname, ifnull(pro.metal_type,'') as metal_type,
				tgrp_name, ifnull(design_code, '-') as design_code, 
				ifnull(design_name, '') as design_name,
				stn_amount,stn_wt,othermat_amount,othermat_wt 				
				FROM ret_taging as tag 
				Left join ret_lot_inwards_detail ld on tag.id_lot_inward_detail = ld.id_lot_inward_detail					LEFT JOIN ret_product_master as pro ON pro.pro_id = ld.lot_product  
                LEFT JOIN ret_category c on c.id_ret_category=pro.cat_id
				LEFT JOIN metal mt on mt.id_metal=c.id_metal 
				LEFT JOIN ret_design_master as des ON des.design_no = tag.design_id 
				LEFT JOIN ret_taxgroupmaster as txgrp ON txgrp.tgrp_id = mt.tgrp_id 
				LEFT JOIN ret_purity as pur ON pur.id_purity = tag.purity 
				LEFT JOIN (SELECT tag_id,sum(amount) as stn_amount,sum(wt) as stn_wt FROM `ret_taging_stone` GROUP by tag_id) as tag_stn_detail ON tag_stn_detail.tag_id = tag.tag_id
				LEFT JOIN (SELECT tag_id,sum(price) as othermat_amount,sum(wt) as othermat_wt FROM `ret_taging_other_materials` GROUP by tag_id) as tag_other_mat ON tag_other_mat.tag_id = tag.tag_id
				WHERE tag.tag_id = '".$SearchTxt."'");
		return $data->result_array();
	}
	function getProductBySearch($SearchTxt){
		$data = $this->db->query("SELECT pro_id as value, 
				product_short_code as label, product_name,
				wastage_type, other_materials, has_stone, 
				has_hook, has_screw, has_fixed_price,
				has_size, less_stone_wt, no_of_pieces, calculation_based_on   
				FROM ret_product_master as pro 
				WHERE product_short_code LIKE '%".$SearchTxt."%' OR product_name LIKE '%".$SearchTxt."%'");
		return $data->result_array();
	}
	function getProductDesignBySearch($SearchTxt, $procode){
		$where = empty($procode) ? "WHERE " : "WHERE product_id =$procode AND ";
		$data = $this->db->query("SELECT design_no as value, 
				design_code as label, design_name,
				min_length, max_length, min_width, max_width,
				min_dia, max_dia,
				min_weight, max_weight, fixed_rate 
				FROM ret_design_master as des 
				".$where." design_code LIKE '%".$SearchTxt."%'");
		return $data->result_array();
	}
	function getMetalTypes(){
		$query = $this->db->query("SELECT id_metal, metal FROM metal");
		return $query->result_array();
	}
	function getUOMDetails()
	{
		$sql = $this->db->query("SELECT * FROM ret_uom where uom_status = 1");
		return $sql->result_array();
	}
	function get_currentBranchName($branch_id){
		$branch_name = "";
		$branch_query = $this->db->query("SELECT id_branch, name FROM branch WHERE id_branch = $branch_id");
		if($branch_query->num_rows() > 0){
			$branch_name = $branch_query->row()->name;
		}
		return $branch_name;
	}
	function get_currentBranches($record_id){
		$record_id = ($record_id==NULL) ? -1 : $record_id;
		$strData="<option value='' ";
		$strData.=$record_id==-1 ? "selected='selected'" : "" ;
		$strData.=">- SELECT -</option>";
		$resultset=$this->db->query("SELECT id_branch, name FROM branch WHERE active = 1 ORDER BY name");
		foreach ($resultset->result() as $row)
		{
		   $strData.= "<option value='".$row->id_branch."' ";
		   $strData.=($record_id==$row->id_branch) ? "selected='selected'" : "" ;
		   $strData.=">".$row->name."</option>";
		}
		$resultset->free_result();
		return $strData;
	}
	
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
		$sql = "SELECT max(bill_no) as lastBill_no FROM ret_taging  ORDER BY bill_id DESC ";
		return $this->db->query($sql)->row()->lastBill_no;	
	} 
}
?>
<?php
if( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ret_supp_catalog_model extends CI_Model
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

	function get_empty_record() {

		$record['id_supp_catalogue'] = "";
		
		$record['ctl_datetime'] = "";
		
		$record['product_id'] = "";

		$record['cat_id'] = "";
		
		$record['design_id'] = "";
		
		$record['id_sub_design'] = "";
		
		$record['design_code'] = "";
		
		$record['image'] = "";
		
		$record['weightRange'] = array();

		return $record;
	}

	function ajax_getSupplierCatList($id= "") {

        $return_data=array();

		$where = "";

		if($id > 0) {

			$where = $where." AND sc.id_supp_catalogue =".$id;

		}

		$sql = "SELECT

					sc.id_supp_catalogue,

					sc.ctl_datetime,

					sc.product_id,

					sc.design_id,

					sc.id_sub_design,

					sc.design_code,

					sc.image,

					pm.product_name,

					pm.cat_id,

					dm.design_name,

					sdm.sub_design_name,

					sc.status,

					IF(sc.status = 1, 'Active', 'InActive') AS supp_cat_status

				FROM ret_supp_catalogue sc

				LEFT JOIN ret_product_master pm ON pm.pro_id = sc.product_id

				LEFT JOIN ret_design_master dm ON dm.design_no = sc.design_id

				LEFT JOIN ret_sub_design_master sdm ON sdm.id_sub_design = sc.id_sub_design
				
				WHERE 1 ".$where;

				//echo $sql;exit;

		$sql = $this->db->query($sql);

		// echo $this->db->last_query();exit;
		
		$result = $sql->result_array();

		foreach($result as $data) {

			$data['weightRange'] = $this->get_suppWeightRangeData($data['id_supp_catalogue']);

			$return_data[] = $data;

		}
		
		return $return_data;
	}

	function get_suppWeightRangeData($id_supp_catalogue = "") {

		$where = "";

		if($id_supp_catalogue > 0) {

			$where = $where." AND cw.id_supp_catalogue = ".$id_supp_catalogue;

		}

		$weight_sql = $this->db->query("SELECT
											
											cw.id_catalogue_weight,

											cw.id_supp_catalogue,
											
											cw.weight, 

											cw.purity AS id_purity,

											cw.size AS id_size,

											cw.wastage,

											cw.display_va,

											cw.mc_value,

											cw.mc_type,

											cw.display_mc,

											cw.delivery_duration AS smith_due_date,

											cw.display_duration,

											cw.calculation_based_on,

											cw.karigar,

											w.id_weight,
											
											w.weight_description,
											
											w.from_weight,
											
											w.to_weight,

											w.id_product,

											w.id_design
			
										FROM ret_supp_catalogue_weight AS cw
										
										LEFT JOIN ret_weight AS w ON w.id_weight = cw.weight
										
										WHERE 1 ".$where);

		$weights = $weight_sql->result_array();

		$weight_sql->free_result();

		return $weights;

	}
	
}
?>
<?php

if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ret_estimation_model extends CI_Model

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

	

	function get_ret_settings($name)

	{

		$sql = "SELECT 

				`name`,

				`value`

				FROM ret_settings WHERE name = '".$name."'";

		$sql = $this->db->query($sql);  

	    return $sql->row_array();

	}

	

	function get_employee_settings($emp_id)

	{

		$query = "SELECT allow_manual_rate,min_gold_tol,max_gold_tol,min_silver_tol,max_silver_tol

				 from employee_settings

				 where id_employee =".$emp_id;

		$sql = $this->db->query($query);

		return $sql->row_array();

	}

	

	function get_village()

    {

		$sql="Select *From village where status=1";

		return $data = $this->db->query($sql)->result_array();			

	}

	

	

	function get_FinancialYear()

	{

		$sql=$this->db->query("SELECT fin_year_code From ret_financial_year where fin_status=1");

		return $sql->row_array();

	}

	

	function getEstTags($tag_id)

	{

	    $sql=$this->db->query("SELECT * FROM ret_taging WHERE tag_id=".$tag_id."");

	    return $sql->row_array();

	}

	

	function get_customer($id_customer)

    {

		$sql=$this->db->query("SELECT c.id_customer,c.cus_type,c.gst_number,c.firstname,c.mobile,c.email,a.id_country,a.id_state,a.id_city, a.address1,a.address2,a.address3,a.pincode,

		IFNULL(c.pan,'') as pan_no,IFNULL(c.aadharid,'') as aadharid,IFNULL(c.gst_number,'') as gst_number,IFNULL(c.id_village,'') as id_village

		FROM customer c 

		LEFT JOIN  address a on a.id_customer =c.id_customer

		WHERE c.id_customer=".$id_customer);

		return $sql ->row_array();			

	}

	function getNonTagLots($SearchTxt,$id_branch)

	{

	    $data = $this->db->query("SELECT 

                            	    l.lot_no as value,l.lot_no as label 

                            	  FROM ret_lot_inwards l 

                            	    LEFT JOIN ret_lot_inwards_detail lt_det on lt_det.lot_no = l.lot_no

                            	    LEFT JOIN ret_branch_transfer bt on bt.id_lot_inward_detail = lt_det.id_lot_inward_detail

                            	  WHERE transfer_item_type = 2 AND transfer_to_branch=".$id_branch." AND stock_type=2 AND l.lot_no LIKE '%".$SearchTxt."%'"

	                            );

	                           // echo $this->db->last_query();

	     return $data->result_array();

	}

	function ajax_getEstimationList($id_branch,$from_date,$to_date)

    {

        $return_data=array();

        $uid=$this->session->userdata('uid');

        if($id_branch!='' && $id_branch>0)

        {

            $data=$this->getBranchDayClosingData($id_branch);

        }

		$sql = $this->db->query("SELECT estimation_id, esti_no,cus.mobile,

				date_format(estimation_datetime, '%d-%m-%Y %H:%i') as estimation_datetime, 

				total_cost, if(esti_for = 1,'Customer','Branch Transfer') as esti_for,IFNULL(bill.bill_id,old_bill.bill_id) as bill_id,IFNULL(bill.bill_no,old_bill.bill_no) as bill_no,

				IFNULL(pro.product_name,'') as product_name,cus.firstname,e.firstname as emp_name,e.emp_code

				

				FROM ret_estimation as est 

				LEFT JOIN customer as cus ON cus.id_customer = est.cus_id 

				LEFT JOIN employee e on e.id_employee=est.created_by

				LEFT JOIN (SELECT e.esti_id,b.bill_id,b.bill_no

                FROM ret_estimation_items e 

                LEFT JOIN ret_bill_details d ON d.esti_item_id=e.est_item_id

                LEFT JOIN ret_billing b ON b.bill_id=d.bill_id

                WHERE b.bill_status=1

                GROUP by b.bill_id) as bill ON bill.esti_id=est.estimation_id

                

                LEFT JOIN (SELECT GROUP_CONCAT(concat(m.metal),'-',p.product_name)  as product_name,e.esti_id

                FROM ret_estimation_items e 

                LEFT JOIN ret_product_master p ON p.pro_id=e.product_id

                LEFT JOIN ret_category c ON c.id_ret_category=p.cat_id

                LEFT JOIN metal m ON m.id_metal=c.id_metal

                GROUP by e.esti_id) as pro ON pro.esti_id=est.estimation_id

                

                LEFT JOIN (SELECT s.est_id,b.bill_id,b.bill_no

                FROM ret_estimation_old_metal_sale_details s

                LEFT JOIN ret_bill_old_metal_sale_details old ON old.esti_old_metal_sale_id=s.old_metal_sale_id

                LEFT JOIN ret_billing b ON b.bill_id=old.bill_id

                WHERE b.bill_status=1

                GROUP by b.bill_id) as old_bill ON old_bill.est_id=est.estimation_id

                

          

           

				where ".($from_date=='' || $to_date=='' ? (($id_branch==0 || $id_branch=='') ? " date(estimation_datetime) = '".date("Y-m-d")."' " : " date(estimation_datetime) = '".$data['entry_date']."' "):'')." 

				

				 ".($from_date != '' && $to_date != '' ? 'date(estimation_datetime) BETWEEN "'.$from_date.'" AND "'.$to_date.'"' : '')."

				

				".($id_branch!=0 && $id_branch!='' ? " and est.id_branch=".$id_branch."" :'')."

				ORDER BY est.estimation_id desc "); 

			//print_r($this->db->last_query());exit;

		$result= $sql->result_array();

		foreach($result as $item)

		{

            $old_metal=$this->get_oldmetalDetails($item['estimation_id']);

            if($old_metal>0)

            {

                $item['item_type']='Sales and Purchase';

            }

            else

            {

                $item['item_type']='Sales';

            }

		     $return_data[]=$item;

		}

		return $return_data;

	}

	

	function get_oldmetalDetails($estim_id)

    {

        $sql=$this->db->query("SELECT est_id from ret_estimation_old_metal_sale_details where est_id=".$estim_id);

        return $sql->num_rows();

    }

	function getBranchDayClosingData($id_branch)

    {

	    $sql = $this->db->query("SELECT id_branch,is_day_closed,entry_date from ret_day_closing where id_branch=".$id_branch);  

	    return $sql->row_array();

	}

	function get_charges($tag_id)

	{

		$sql = $this->db->query("SELECT rtc.tag_charge_id, rtc.tag_id, rtc.charge_id, rtc.charge_value, c.code_charge, c.tag_display FROM ret_taging_charges AS rtc LEFT JOIN ret_charges AS c ON rtc.charge_id = c.id_charge  WHERE tag_id=".$tag_id);  

	    return $sql->result_array();

	}

	function get_entry_records($est_id)

	{

		$sql = $this->db->query("SELECT estimation_id,esti_no,

				concat(cus.firstname, '-',cus.mobile) as cus_name,  esti_for,	

				date_format(estimation_datetime, '%d-%m-%Y %H:%i:%s') as estimation_datetime, 

				

				date_format(estimation_datetime, '%d-%m-%Y') as est_date, 

				cus_id, created_by, date_format(created_time, '%d-%m-%Y %H:%i:%s') as created_time,

				has_converted_order, discount, gift_voucher_amt, total_cost, est.id_branch,cus.mobile,IFNULL(v.village_name,'') as village_name,

				

				cus.firstname as customer_name,e.emp_code,b.short_name ,e.id_employee,e.firstname as emp_name,IFNULL(est.goldrate_22ct,0) as goldrate_22ct,
				IFNULL(est.silverrate_1gm,0) as silverrate_1gm

				FROM ret_estimation as est 

				LEFT JOIN customer as cus ON cus.id_customer = est.cus_id 

				LEFT JOIN village v on v.id_village=cus.id_village

				

				LEFT JOIN employee e on e.id_employee=est.created_by

				

				LEFT JOIN branch b on b.id_branch=est.id_branch

				WHERE est.estimation_id ='".$est_id."'");

				//print_r($this->db->last_query());exit;

		return $sql->result_array()[0];

	}

	function getOtherEstimateItemsDetails($est_id)

	{

		$return_data = array("item_details" => array(), "old_matel_details" => array(), "stone_details" => array(), "other_material_details" => array(), "voucher_details" => array(), "chit_details" => array(),"advance_details"=>array());

		$items_query = $this->db->query("SELECT est_item_id, esti_id, item_type, 

					   est.product_id, est.tag_id, est.design_id, est.purity as purid, est.size, est.uom, est.piece, 

                       IFNULL(est.less_wt,0) as less_wt, est.net_wt, est.gross_wt, 

					   est.calculation_based_on, est.wastage_percent, est.mc_value, 

					   item_cost, product_short_code,product_name as product_name, 

                       concat(design_name,'-',design_code) as design_name, design_name as design, design_code, pur.purity as purname,

                       tax.tax_percentage,tax.tgi_calculation,est.discount,e.created_by,s.disc_limit,s.disc_limit_type,e.id_branch,est.mc_type,est.is_non_tag,est.is_partial,est.lot_no,pro.sales_mode,tag.item_rate,

                       est.item_total_tax,est.market_rate_cost,tag.net_wt as tag_net_wt,txgrp.tgrp_name,tag.tag_code,IFNULL(est.id_orderdetails,'') as id_orderdetails, rtc.charge_value,

                       

                       sub.sub_design_name,mt.metal as metal_name,IFNULL(est.est_rate_per_grm,0) as est_rate_per_grm

					   FROM ret_estimation_items as est 

					   LEFT join ret_estimation e on e.estimation_id=est.esti_id

                       LEFT JOIN ret_product_master as pro ON pro.pro_id = est.product_id 

                       LEFT JOIN ret_design_master as des ON des.design_no = est.design_id 

                       

                       LEFT JOIN ret_sub_design_master as sub ON sub.id_sub_design = est.id_sub_design

                       LEFT JOIN ret_purity as pur ON pur.id_purity = est.purity

                       LEFT JOIN ret_category c on c.id_ret_category=pro.cat_id

					   LEFT JOIN metal mt on mt.id_metal=pro.metal_type

					   LEFT JOIN ret_taxgroupmaster as txgrp ON txgrp.tgrp_id = pro.tgrp_id

					   left JOIN employee_settings s on s.id_employee=e.created_by

					   LEFT JOIN ret_taging as tag ON tag.tag_id = est.tag_id

					   LEFT JOIN (SELECT tag_id, SUM(IFNULL(charge_value,0)) AS charge_value FROM ret_taging_charges GROUP BY tag_id) AS rtc ON rtc.tag_id = tag.tag_id

						LEFT JOIN (select i.tgi_taxcode,i.tgi_tgrpcode,

						m.tax_percentage as tax_percentage,

						i.tgi_calculation as tgi_calculation

						FROM ret_taxgroupitems i

						LEFT JOIN ret_taxmaster m on m.tax_id=i.tgi_taxcode) as tax on tax.tgi_tgrpcode=pro.tgrp_id

					    WHERE est.esti_id ='".$est_id."'

					    GROUP by est.est_item_id");

		//print_r($this->db->last_query());exit;

		$item_details = $items_query->result_array();		

		foreach ($item_details as $items) {

			$return_data["item_details"][]=array(

							'item_rate'			=>$items['item_rate'],

							'sales_mode'		=>$items['sales_mode'],

							'est_item_id'		=>$items['est_item_id'],

							'item_type'			=>$items['item_type'],

							'esti_id'			=>$items['esti_id'],

							'product_id'		=>$items['product_id'],

							'tag_id'			=>$items['tag_id'],

							'tag_code'			=>$items['tag_code'],

							'design_id'			=>$items['design_id'],

							'purid'				=>$items['purid'],

							'size'				=>$items['size'],

							'uom'				=>$items['uom'],

							'piece'				=>$items['piece'],

							'less_wt'			=>$items['less_wt'],

							'net_wt'			=>$items['net_wt'],

							'gross_wt'			=>$items['gross_wt'],

							'calculation_based_on'=>$items['calculation_based_on'],

							'wastage_percent'	=>$items['wastage_percent'],

							'mc_value'		=>$items['mc_value'],

							'item_cost'			=>$items['item_cost'],

							'product_short_code'=>$items['product_short_code'],

							'product_name'		=>$items['product_name'],

							'design_code'		=>$items['design_code'],

							'design_name'		=>$items['design_name'],

							

							'sub_design_name'   =>$items['sub_design_name'],

							'design'			=>$items['design'],

							'purname'			=>$items['purname'],

							'tax_percentage'	=>$items['tax_percentage'],

							'tgi_calculation'	=>$items['tgi_calculation'],

							'discount'			=>$items['discount'],

							'disc_limit'		=>$items['disc_limit'],

							'disc_limit_type'	=>$items['disc_limit_type'],

							'id_branch'			=>$items['id_branch'],

							'mc_type'			=>$items['mc_type'],

							'is_non_tag'		=>$items['is_non_tag'],

							'is_partial'		=>$items['is_partial'],

							'lot_no'		    =>$items['lot_no'],

							'item_total_tax'    =>$items['item_total_tax'],

							'tag_net_wt'    	=>$items['tag_net_wt'],

							'market_rate_cost'  =>$items['market_rate_cost'],

							'tgrp_name'  		=>$items['tgrp_name'],

							'metal_name'  		=>$items['metal_name'],

							'id_orderdetails'  	=>$items['id_orderdetails'],

							'est_rate_per_grm'  =>$items['est_rate_per_grm'],

						    //'stone_details'		=>($items['tag_id']=='' ? $this->get_stone_details($items['est_item_id']):$this->get_tag_stone_details($items['tag_id'])),

						     'stone_details'		=>  $this->get_stone_details($items['est_item_id']),

						    'other_metal_details'=>$this->get_est_other_metal_details($items['est_item_id']),

						    'material_details'	=>$this->get_other_material_details($items['est_item_id']),

							'charge_value'		=> $items['charge_value'],

							'charges'			=> $this->get_other_estcharges($items['est_item_id'])

					   );

		}

		$old_matel_query = $this->db->query("SELECT est_old.old_metal_sale_id, est_id, 

						   id_category, type, item_type, gross_wt,net_wt,

						   round((gross_wt - stone_wt),3) as ls_wt,

                           if(type = 1, 'Melting', 'Retag') as reusetype,

                           if(item_type = 1, 'Ornament', if(item_type = 2, 'Coin', if(item_type = 3, 'Bar',''))) as receiveditem,

						   IFNULL(stone_wt,0) as stone_wt, IFNULL(dust_wt,0) as dust_wt , est_old.purity as purid, wastage_percent,

						   wastage_wt, rate_per_gram, amount, 

						   pur.purity as purname, met.metal ,est_old.purpose,est_old.id_old_metal_type, est_old.id_old_metal_category, oldM.metal_type AS old_metal_type

						   FROM ret_estimation_old_metal_sale_details as est_old 

						   LEFT JOIN ret_purity as pur ON pur.id_purity = est_old.purity 

						   LEFT JOIN  ret_old_metal_type AS oldM ON oldM.id_metal_type = est_old.id_old_metal_type

						   LEFT JOIN metal as met ON met.id_metal = est_old.id_category 

						   WHERE est_old.est_id = '".$est_id."'");

		$old_matel_detail= $old_matel_query->result_array();

		foreach ($old_matel_detail as $data) {

			$return_data["old_matel_details"][]=array(

								'old_metal_sale_id'=>$data['old_metal_sale_id'],

								'id_old_metal_type'=>$data['id_old_metal_type'],

								'id_old_metal_category'=>$data['id_old_metal_category'],

								'old_metal_type'=>$data['old_metal_type'],

								'est_id'=>$data['est_id'],

								'id_category'=>$data['id_category'],

								'type'=>$data['type'],

								'item_type'=>$data['item_type'],

								'gross_wt'=>$data['gross_wt'],

								'ls_wt'=>$data['ls_wt'],

								'net_wt'=>$data['net_wt'],

								'stone_wt'=>$data['stone_wt'],

								'reusetype'=>$data['reusetype'],

								'receiveditem'=>$data['receiveditem'],

								'dust_wt'=>$data['dust_wt'],

								'purid'=>$data['purid'],

								'wastage_percent'=>$data['wastage_percent'],

								'wastage_wt'=>$data['wastage_wt'],

								'rate_per_gram'=>$data['rate_per_gram'],

								'amount'=>$data['amount'],

								'purname'=>$data['purname'],

								'metal'=>$data['metal'],

								'purpose'=>$data['purpose'],

								

								'goldrate_24ct'=>($this->getOldMetalRate($data['id_old_metal_type'])),

								'stone_details'		 =>$this->get_old_metal_stone_details($data['old_metal_sale_id']),

								'old_metal_types'	=>	$this->get_old_metal_type($data['id_category']),

								'old_metal_category'	=>	$this->get_old_metal_category($data['id_old_metal_type'])

						);

		}

		$est_stone_query = $this->db->query("SELECT est_item_stone_id, 

						   est_id, est_st.stone_id, pieces, wt, price, 

                           stone_name, stone_code, uom_name, uom_short_code 

						   FROM ret_estimation_item_stones as est_st 

                           LEFT JOIN ret_stone as st ON st.stone_id = est_st.stone_id 

                           LEFT JOIN ret_uom as um ON um.uom_id = st.uom_id 

						   WHERE est_st.est_id = '".$est_id."'");

		$return_data["stone_details"] = $est_stone_query->result_array();

		$est_material_query = $this->db->query("SELECT est_other_material_id,est_item_id,

							  est_id, est_mt.material_id, wt, price ,

							  material_name, material_code, 

							  uom_name, uom_short_code 

							  FROM ret_estimation_item_other_materials as est_mt 

							  LEFT JOIN ret_material as mat ON mat.material_id = est_mt.material_id 

							  LEFT JOIN ret_uom as um ON um.uom_id = mat.uom_id 

							  WHERE est_mt.est_id = '".$est_id."'");

		$other_material = $est_material_query->result_array();

		foreach($other_material as $materials)

		{

			//echo "<pre>"; print_r($materials);exit;

 			$return_data['other_material_details'][]=array(

								'est_other_material_id'=>$materials['est_other_material_id'],

								'est_id'			   =>$materials['est_id'],

								'material_id'		   =>$materials['material_id'],

								'wt'				   =>$materials['wt'],

								'price'				   =>$materials['price'],

								'material_name'	       =>$materials['material_name'],

								'material_code'		   =>$materials['material_code'],

								'uom_name'		       =>$materials['uom_name'],

								'uom_short_code'	   =>$materials['uom_short_code'],

								'est_item_id'	       =>$materials['est_item_id'],

								'stone_details'		 =>$this->get_stone_details($materials['est_item_id']),

								'material_details'		 =>$this->get_other_material_details($materials['est_item_id'])

								);

		}

		$est_voucher_query = $this->db->query("SELECT gift_voucher_id, est_id,

							voucher_no, gift_voucher_details, gift_voucher_amt 

						   FROM ret_est_gift_voucher_details as est_vouch 

						   WHERE est_vouch.est_id  = '".$est_id."'");

		$return_data["voucher_details"] = $est_voucher_query->result_array();

        $est_chit_query = $this->db->query("SELECT chit_ut_id, est_id,scheme_account_id, utl_amount,concat(s.code,'',sa.scheme_acc_number) as scheme_acc_number,s.scheme_type,sa.closing_balance,
        
        IFNULL(est_chit.closing_weight,0) as closing_weight,IFNULL(est_chit.wastage_per,0) as wastage_per,IFNULL(est_chit.savings_in_wastage,0) as savings_in_wastage,
        
        IFNULL(est_chit.mc_value,0) as mc_value,IFNULL(est_chit.savings_in_making_charge,0) as savings_in_making_charge,sa.additional_benefits,sa.closing_add_chgs

        FROM ret_est_chit_utilization as est_chit

        LEFT JOIN scheme_account sa ON sa.id_scheme_account=est_chit.scheme_account_id

        LEFT JOIN scheme s ON s.id_scheme=sa.id_scheme  

        WHERE est_chit.est_id = '".$est_id."'");

		$return_data["chit_details"] = $est_chit_query->result_array();

		

	/*	$advance_details=$this->db->query("SELECT a.order_no,a.advance_amount as paid_advance,(a.advance_weight*a.rate_per_gram) as paid_weight,s.metal_type,a.store_as,a.advance_type,a.rate_calc,a.rate_per_gram,a.bill_id,a.bill_adv_id

            FROM ret_billing_advance a 

            LEFT JOIN ret_billing b ON b.bill_id=a.bill_id

            LEFT JOIN ret_estimation_items e ON e.order_no=a.order_no

            LEFT JOIN customerorderdetails d on d.id_orderdetails=e.id_orderdetails

            LEFT JOIN ret_bill_old_metal_sale_details s on s.old_metal_sale_id=a.old_metal_sale_id

            LEFT JOIN ret_estimation est ON est.estimation_id=e.esti_id

            where est.estimation_id ='".$est_id."' and b.bill_status=1

            AND b.id_branch=est.id_branch

            GROUP BY a.bill_adv_id");*/

            

        $advance_details=$this->db->query("SELECT a.order_no,a.advance_amount as paid_advance,(a.advance_weight*a.rate_per_gram) as paid_weight,s.metal_type,a.store_as,a.advance_type,a.rate_calc,a.rate_per_gram,a.bill_id,a.bill_adv_id,

        date_format(b.bill_date,'%d-%m-%Y') as bill_date

            FROM ret_estimation_items e 

            LEFT JOIN customerorderdetails d ON d.id_orderdetails=e.id_orderdetails

            LEFT JOIN customerorder c ON c.id_customerorder=d.id_customerorder

            LEFT JOIN ret_billing_advance a ON a.id_customerorder=c.id_customerorder

            LEFT JOIN ret_billing b on b.bill_id=a.bill_id

            LEFT JOIN ret_bill_old_metal_sale_details s on s.old_metal_sale_id=a.old_metal_sale_id

            LEFT JOIN ret_estimation est ON est.estimation_id=e.esti_id

            where est.estimation_id ='".$est_id."' and b.bill_status=1

            AND b.id_branch=est.id_branch

            GROUP BY a.bill_adv_id");

           // print_r($this->db->last_query());exit;

        $return_data["advance_details"] = $advance_details->result_array();

		return $return_data;

	}

	

	

	function getOldMetalRate($id_old_metal_type)

    {

        $sql=$this->db->query("SELECT r.rate as goldrate_24ct

        FROM ret_old_metal_type t 

        LEFT JOIN ret_old_metal_rate r ON r.id_metal=t.id_metal

        WHERE t.id_metal_type=".$id_old_metal_type."

        ORDER by id_old_metal_rate DESC LIMIT 1");

       

        return $sql->row()->goldrate_24ct;

    }

	function get_old_metal_stone_details($est_old_metal_sale_id)

	{

			$est_stone_query=$this->db->query("SELECT est_old_metal_stone_id,est_old_metal_sale_id, 

						   est_st.stone_id, pieces, wt, price, 

                           stone_name, stone_code, uom_name, uom_short_code 

						   FROM ret_esti_old_metal_stone_details as est_st 

                           LEFT JOIN ret_stone as st ON st.stone_id = est_st.stone_id 

                           LEFT JOIN ret_uom as um ON um.uom_id = st.uom_id 

						   WHERE est_st.est_old_metal_sale_id = '".$est_old_metal_sale_id."'");

		//	print_r($this->db->last_query());exit;

			return $est_stone_query->result_array();

	}

	function get_stone_details($est_item_id)

	{

			$est_stone_query=$this->db->query("SELECT est_item_stone_id,est_item_id, 

						   est_st.stone_id, pieces, wt, price, rate_per_gram,

                           stone_name, stone_code, uom_name, uom_short_code 

						   FROM ret_estimation_item_stones as est_st 

                           LEFT JOIN ret_stone as st ON st.stone_id = est_st.stone_id 

                           LEFT JOIN ret_uom as um ON um.uom_id = st.uom_id 

						   WHERE est_st.est_item_id = '".$est_item_id."'");

			return $est_stone_query->result_array();

	}

	

	function get_est_other_metal_details($est_item_id)

	{

            $sql =$this->db->query("SELECT m.tag_other_itm_grs_weight,m.tag_other_itm_wastage,m.tag_other_itm_mc,m.tag_other_itm_rate,m.tag_other_itm_pcs,m.tag_other_itm_amount,mt.metal

            FROM ret_est_other_metals m 

            LEFT JOIN metal mt ON mt.id_metal = m.tag_other_itm_metal_id WHERE m.est_item_id = '".$est_item_id."'");

			return $sql->result_array();

	}

	function get_tag_stone_details($tag_id)

	{

		$tag_stone_query=$this->db->query("SELECT tag_stone_id,tag_id, pieces, wt,amount,stone_id,amount as price,IFNULL(certification_cost,0) as certification_cost,

		s.is_apply_in_lwt,s.stone_cal_type,s.rate_per_gram

		FROM ret_taging_stone as s 

		WHERE s.tag_id = '".$tag_id."'");

		return $tag_stone_query->result_array();

	}

	function get_other_material_details($est_item_id)

	{

			$est_stone_query=$this->db->query("SELECT est_other_material_id,

							  est_id, est_mt.material_id, wt, price ,

							  material_name, material_code, 

							  uom_name, uom_short_code 

							  FROM ret_estimation_item_other_materials as est_mt 

							  LEFT JOIN ret_material as mat ON mat.material_id = est_mt.material_id 

							  LEFT JOIN ret_uom as um ON um.uom_id = mat.uom_id 

							  WHERE est_mt.est_item_id = '".$est_item_id."'");

			return $est_stone_query->result_array();

	}

	function get_empty_record()

    {

		$emptyquery = $this->db->field_data('ret_estimation');

		$emptydata = array();

		foreach ($emptyquery as $field)

		{

			$emptydata[$field->name] = $field->default;

		}

		$emptydata['estimation_datetime'] = date('d-m-Y H:i:s');

		$emptydata['cus_name'] 			  = '';

        

        $emptydata['financial_year'] = $this->GetFinancialYear();

        	

		//$emptydata['design_code']  = '';

		return $emptydata;

	}

	

	function GetFinancialYear()

	{

		$sql=$this->db->query("SELECT fin_year_code,fin_status,fin_year_name From ret_financial_year");

		return $sql->result_array();

	}

	

	public function encrypt($str)

	{

		return base64_encode($str);		

	}

	function createNewCustomer($cusname,$cusmobile,$mail,$branch,$id_village,$cus_type,$id_country,$id_state,$id_city,$address1,$address2,$address3,$pincode,$gst_number)

	{

       

		$customer_check_query = $this->db->query("SELECT * FROM customer WHERE mobile='".$cusmobile."'");

		if($customer_check_query->num_rows() == 0){

			$insert_data          = array("firstname" => strtoupper($cusname), "id_branch" => $branch, "mobile" => $cusmobile, "username" => $cusmobile,"email" =>$mail, "passwd" => $this->encrypt($cusmobile),"id_village"=>$id_village,'cus_type'=>$cus_type,'gst_number'=>$gst_number,'date_add'=>date("Y-m-d H:i:s"));

			$cus_insert_id = $this->insertData($insert_data, "customer");

			$insert_address_data  = array("id_customer" => $cus_insert_id, "address1"=>$address1,"address2"=>$address2,"address3"=>$address3,"pincode"=>$pincode,"id_country"=>$id_country,"id_state"=>$id_state,"id_city"=>$id_city);

			$cus_insert_address=$this->insertData($insert_address_data, "address");

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

	

	function updateCustomer($id_customer,$cusname,$cusmobile,$mail,$branch,$cus_type,$id_country,$id_state,$id_city,$address1,$address2,$address3,$pincode,$gst_number)

	{

	

           $update_Data = array("firstname" => strtoupper($cusname), "id_branch" => $branch, "mobile" => $cusmobile, "username" => $cusmobile, "email"=>$mail,"passwd" => $this->encrypt($cusmobile),'cus_type'=>$cus_type,'gst_number'=>$gst_number,'date_add'=>date("Y-m-d H:i:s"));

		   $cus_insert_id=$this->updateData($update_Data,'id_customer',$id_customer, 'customer');

		  // print_r($this->db->last_query());exit;

           $update_address  = array("address1"=>$address1,"address2"=>$address2,"address3"=>$address3,"pincode"=>$pincode,"id_country"=>$id_country,"id_state"=>$id_state,"id_city"=>$id_city);

		   $cus_insert_address=$this->updateData($update_address , 'id_customer',$id_customer,"address");

		 // print_r($this->db->last_query());exit;

			if(!empty($cus_insert_id)){

				$update_Data["id_customer"] = $id_customer;

				$update_Data["mobile"] = $cusmobile;

				

				return array("success" => TRUE, "message" => "Customer details updated successfully", "response" => $update_Data);

			}

	}

	

	/*function getAvailableCustomers($SearchTxt,$esti_for,$billing_for){

        

        if($billing_for=='' || $billing_for!=3)

        {

            $data = $this->db->query("SELECT c.id_customer as value, concat(c.firstname,'-',c.mobile) as label,c.id_village,v.village_name,if(c.is_vip=1,'Yes','No') as vip,

            (select count(sa.id_scheme_account) from scheme_account sa where sa.id_customer=c.id_customer) as accounts,

            IFNULL(a.id_country,'') as id_country,IFNULL(a.id_state,'') as id_state,IFNULL(a.id_city,'') as id_city

            FROM customer c

            LEFT JOIN address a on a.id_customer=c.id_customer

            left join village v on v.id_village=c.id_village

            WHERE (username like '%".$SearchTxt."%' OR mobile like '%".$SearchTxt."%' OR firstname like '%".$SearchTxt."%')

            ".($esti_for==3 ? " and c.cus_type=2" :'')."");

        }

        else if($billing_for==3)

        {

            $data=$this->db->query("SELECT concat(k.firstname,'-',k.contactno1)  as label,k.id_karigar as value,k.address1,k.id_country,k.id_state,k.id_city

            FROM ret_karigar k

            WHERE k.firstname like '%".$SearchTxt."%' OR k.contactno1 like '%".$SearchTxt."%' ");

            //print_r($this->db->last_query());exit;

        }

		 

		return $data->result_array();

	}*/

	

	

	function getAvailableCustomers($SearchTxt,$esti_for,$billing_for)

	{

        if($billing_for=='' || $billing_for!=3)

        {

            $data = $this->db->query("SELECT c.id_customer as value, concat(c.firstname,'-',c.mobile) as label,c.id_village,v.village_name,if(c.is_vip=1,'Yes','No') as vip,

            (select count(sa.id_scheme_account) from scheme_account sa where sa.id_customer=c.id_customer) as accounts,

            IFNULL(a.id_country,'') as id_country,IFNULL(a.id_state,'') as id_state,IFNULL(a.id_city,'') as id_city

            FROM customer c

            LEFT JOIN address a on a.id_customer=c.id_customer

            left join village v on v.id_village=c.id_village

            WHERE (mobile like '%".$SearchTxt."%' OR firstname like '%".$SearchTxt."%')

            ".($esti_for==1  ? " and c.cus_type =1 " : ($esti_for==3 ? " and c.cus_type = 2 " :'') )." GROUP by c.id_customer");

            //print_r($this->db->last_query());exit;

        }

        else if($billing_for==3)

        {

            $data=$this->db->query("SELECT concat(k.firstname,'-',k.contactno1)  as label,k.id_karigar as value,k.address1,k.id_country,k.id_state,k.id_city

            FROM ret_karigar k

            WHERE k.firstname like '%".$SearchTxt."%' OR k.contactno1 like '%".$SearchTxt."%' GROUP by k.id_karigar");

            //print_r($this->db->last_query());exit;

        }

		return $data->result_array();

	}

     /*function getTaggingBySearch($SearchTxt,$branch)

    {

        $data = $this->db->query("SELECT tag.tag_id as value,

        tag.tag_id as label, tag_code, tag_datetime, tag.tag_type, tag_lot_id,

        design_id, cost_center, tag.purity, tag.size, uom, piece, tag.less_wt, tag.net_wt, tag.gross_wt,

        tag.calculation_based_on, retail_max_wastage_percent,tag_mc_value,tag_mc_type,

        halmarking, sales_value, tag.tag_status, product_name, product_short_code, lot_product, pur.purity as purname,lot_inw.lot_received_at

        ,group_concat(m.tax_percentage) as tax_percentage,GROUP_CONCAT(i.tgi_calculation) as tgi_calculation,

        ifnull(tag_stn_detail.stn_amount,0) as stone_price,tag.tag_id,pro.sales_mode,tag.item_rate

        FROM ret_taging as tag

        Left join ret_lot_inwards_detail lot_det ON tag.id_lot_inward_detail = lot_det.id_lot_inward_detail

        LEFT JOIN ret_lot_inwards as lot_inw ON lot_inw.lot_no = lot_det.lot_no

        LEFT JOIN ret_product_master as pro ON pro.pro_id = lot_det.lot_product

        LEFT JOIN ret_purity as pur ON pur.id_purity = tag.purity

        left join ret_category c on c.id_ret_category=pro.cat_id

        left join metal mt on mt.id_metal=c.id_metal 

        left join ret_taxgroupitems i on i.tgi_tgrpcode=mt.tgrp_id

        left join ret_taxmaster m on m.tax_id=i.tgi_taxcode

        LEFT JOIN (SELECT tag_id,sum(amount) as stn_amount,sum(wt) as stn_wt FROM `ret_taging_stone` GROUP by tag_id) as tag_stn_detail ON tag_stn_detail.tag_id = tag.tag_id

        WHERE tag.current_branch=".$branch." AND tag.tag_id LIKE '%".$SearchTxt."%'");

       // print_r($this->db->last_query());exit;

        return $data->result_array();

    }*/

     function getTaggingBySearch($SearchTxt,$searchField,$branch)

    {

        $data = $this->db->query("SELECT tag.tag_id as value,

        tag_code as label, tag_datetime, tag.tag_type, tag_lot_id,

        design_id, cost_center, tag.purity, IFNULL(tag.size,'') as size, uom, piece, IFNULL(tag.less_wt,0) as less_wt,IFNULL(tag.net_wt,0) as net_wt,IFNULL(tag.gross_wt,0) as gross_wt,

        tag.calculation_based_on, retail_max_wastage_percent,tag_mc_value,tag_mc_type,

        halmarking,tag.sell_rate as sales_value, tag.tag_status, product_name, product_short_code, tag.product_id as lot_product,

        pur.purity as purname,lot_inw.lot_received_at,

        ifnull(tag_stn_detail.stn_amount,if(tag.id_orderdetails!='',ord.stn_amt,0)) as stone_price,IFNULL(tag_stn_detail.certification_cost,0) as certification_cost,

        tag.tag_id,pro.sales_mode,tag.item_rate,tax.tax_percentage,tax.tgi_calculation,pro.metal_type,tag.current_branch,

        pro.tgrp_id as tax_group_id,IFNULL(tag.id_orderdetails,'') as id_orderdetails,

        IFNULL(lot_inw.order_no,'') as order_no,des.design_name,tag.tag_mark,r.rate_field,r.market_rate_field, rtc.charge_value,sub.sub_design_name,tag.id_sub_design,

        

        IFNULL(coll.id_tag_mapping,'') as id_tag_mapping,IFNULL(coll.id_mapping_details,'') as id_mapping_details,

        IFNULL(tagOtherMetal.tag_other_itm_amount,0) as tag_other_itm_amount,c.scheme_closure_benefit

        

        FROM ret_taging as tag

        Left join ret_lot_inwards_detail lot_det ON tag.id_lot_inward_detail = lot_det.id_lot_inward_detail

        LEFT JOIN ret_lot_inwards as lot_inw ON lot_inw.lot_no = lot_det.lot_no

        LEFT JOIN ret_product_master as pro ON pro.pro_id = tag.product_id

        LEFT JOIN ret_design_master des on des.design_no=tag.design_id

        left join ret_sub_design_master sub  on sub.id_sub_design=tag.id_sub_design

        LEFT JOIN ret_purity as pur ON pur.id_purity = tag.purity

        left join ret_category c on c.id_ret_category=pro.cat_id

        left join metal mt on mt.id_metal=c.id_metal 

        left join customerorderdetails ord on ord.id_orderdetails=tag.id_orderdetails

        LEFT JOIN ret_metal_purity_rate r on r.id_metal=c.id_metal and r.id_purity=tag.purity 

		LEFT JOIN (select i.tgi_taxcode,i.tgi_tgrpcode,

		GROUP_CONCAT(m.tax_percentage) as tax_percentage,

		GROUP_CONCAT(i.tgi_calculation) as tgi_calculation

		FROM ret_taxgroupitems i

		LEFT JOIN ret_taxmaster m on m.tax_id=i.tgi_taxcode) as tax on tax.tgi_tgrpcode=pro.tgrp_id

        LEFT JOIN (SELECT tag_id,sum(amount) as stn_amount,sum(wt) as stn_wt,sum(certification_cost) as certification_cost

        FROM `ret_taging_stone` WHERE is_apply_in_lwt = 1 

        GROUP by tag_id) as tag_stn_detail ON tag_stn_detail.tag_id = tag.tag_id

		LEFT JOIN (SELECT tag_id, SUM(IFNULL(charge_value,0)) AS charge_value FROM ret_taging_charges GROUP BY tag_id) AS rtc ON rtc.tag_id = tag.tag_id

		LEFT JOIN (SELECT tag_other_itm_tag_id, SUM(IFNULL(tag_other_itm_amount,0)) AS tag_other_itm_amount FROM ret_tag_other_metals GROUP BY tag_other_itm_tag_id) AS tagOtherMetal ON tagOtherMetal.tag_other_itm_tag_id = tag.tag_id

		

		LEFT JOIN(SELECT d.tag_id,d.id_tag_mapping,(d.tag_id) as total_set_collection,d.id_mapping_details

                 FROM ret_tag_collection_mapping_details d 

                 LEFT JOIN ret_tag_collection_mapping tagmap ON tagmap.id_tag_mapping=d.id_tag_mapping

                 WHERE tagmap.status=0

                 ) as coll ON coll.tag_id=tag.tag_id

        WHERE tag.tag_status=0 and tag.id_orderdetails is NULL AND tag.current_branch=".$branch." AND tag.".$searchField." LIKE '%".$SearchTxt."%'");

        //print_r($this->db->last_query());exit;

        $returndata = $data->result_array();

        

        foreach($returndata as $rkey => $rval){

            if($rval['id_tag_mapping']!='')

            {

                $collectionDetails   = $this->getCollectionDetails($rval['id_tag_mapping']);

                $returndata[$rkey]['collection_details'] =$collectionDetails;

                $returndata[$rkey]['total_set_collection'] =sizeof($collectionDetails);

            }

            $returndata[$rkey]['stone_details'] = $this->getTagStoneDetails($rval['value']); 

            $returndata[$rkey]['other_metal_details'] = $this->get_other_metal_details($rval['value']); 

            $returndata[$rkey]['mc_va_limit'] = $this->get_mc_va_limit($rval['lot_product'], $rval['design_id'], $rval['id_sub_design']);

        }

        //return $data->result_array();

        

        return $returndata;

    }

    

    

    

    function getTaggingSearchByCollection($data)

    {

        $data = $this->db->query("SELECT tag.tag_id as value,

        tag_code as label, tag_datetime, tag.tag_type, tag_lot_id,

        design_id, cost_center, tag.purity, IFNULL(tag.size,'') as size, uom, piece, IFNULL(tag.less_wt,0) as less_wt,IFNULL(tag.net_wt,0) as net_wt,IFNULL(tag.gross_wt,0) as gross_wt,

        tag.calculation_based_on, retail_max_wastage_percent,tag_mc_value,tag_mc_type,

        halmarking,tag.sell_rate as sales_value, tag.tag_status, product_name, product_short_code, tag.product_id as lot_product,

        pur.purity as purname,lot_inw.lot_received_at,

        ifnull(tag_stn_detail.stn_amount,if(tag.id_orderdetails!='',ord.stn_amt,0)) as stone_price,IFNULL(tag_stn_detail.certification_cost,0) as certification_cost,

        tag.tag_id,pro.sales_mode,tag.item_rate,tax.tax_percentage,tax.tgi_calculation,pro.metal_type,tag.current_branch,

        pro.tgrp_id as tax_group_id,IFNULL(tag.id_orderdetails,'') as id_orderdetails,

        IFNULL(lot_inw.order_no,'') as order_no,des.design_name,tag.tag_mark,r.rate_field,r.market_rate_field, rtc.charge_value,sub.sub_design_name,tag.id_sub_design,

        IFNULL(coll.id_tag_mapping,'') as id_tag_mapping,IFNULL(d.id_mapping_details,'') as id_mapping_details,

        IFNULL(tagOtherMetal.tag_other_itm_amount,0) as tag_other_itm_amount,c.scheme_closure_benefit

        FROM ret_tag_collection_mapping_details d

        LEFT JOIN ret_taging as tag on tag.tag_id=d.tag_id

        Left join ret_lot_inwards_detail lot_det ON tag.id_lot_inward_detail = lot_det.id_lot_inward_detail

        LEFT JOIN ret_lot_inwards as lot_inw ON lot_inw.lot_no = lot_det.lot_no

        LEFT JOIN ret_product_master as pro ON pro.pro_id = tag.product_id

        LEFT JOIN ret_design_master des on des.design_no=tag.design_id

        left join ret_sub_design_master sub  on sub.id_sub_design=tag.id_sub_design

        LEFT JOIN ret_purity as pur ON pur.id_purity = tag.purity

        left join ret_category c on c.id_ret_category=pro.cat_id

        left join metal mt on mt.id_metal=c.id_metal 

        left join customerorderdetails ord on ord.id_orderdetails=tag.id_orderdetails

        LEFT JOIN ret_metal_purity_rate r on r.id_metal=c.id_metal and r.id_purity=tag.purity 

		LEFT JOIN (select i.tgi_taxcode,i.tgi_tgrpcode,

		GROUP_CONCAT(m.tax_percentage) as tax_percentage,

		GROUP_CONCAT(i.tgi_calculation) as tgi_calculation

		FROM ret_taxgroupitems i

		LEFT JOIN ret_taxmaster m on m.tax_id=i.tgi_taxcode) as tax on tax.tgi_tgrpcode=pro.tgrp_id

        LEFT JOIN (SELECT tag_id,sum(amount) as stn_amount,sum(wt) as stn_wt,sum(certification_cost) as certification_cost

        FROM `ret_taging_stone` WHERE is_apply_in_lwt = 1 

        GROUP by tag_id) as tag_stn_detail ON tag_stn_detail.tag_id = tag.tag_id

		LEFT JOIN (SELECT tag_id, SUM(IFNULL(charge_value,0)) AS charge_value FROM ret_taging_charges GROUP BY tag_id) AS rtc ON rtc.tag_id = tag.tag_id

		LEFT JOIN (SELECT tag_other_itm_tag_id, SUM(IFNULL(tag_other_itm_amount,0)) AS tag_other_itm_amount FROM ret_tag_other_metals GROUP BY tag_other_itm_tag_id) AS tagOtherMetal ON tagOtherMetal.tag_other_itm_tag_id = tag.tag_id

		LEFT JOIN(SELECT d.tag_id,d.id_tag_mapping,(d.tag_id) as total_set_collection

                 FROM ret_tag_collection_mapping_details d 

                 LEFT JOIN ret_tag_collection_mapping tagmap ON tagmap.id_tag_mapping=d.id_tag_mapping

                 WHERE tagmap.status=0

                 ) as coll ON coll.tag_id=tag.tag_id

        WHERE tag.tag_status=0 and tag.id_orderdetails is NULL AND tag.current_branch=".$data['id_branch']." and d.id_tag_mapping=".$data['id_tag_mapping']."");

        //print_r($this->db->last_query());exit;

        $returndata = $data->result_array();

        foreach($returndata as $rkey => $rval){

            $returndata[$rkey]['stone_details'] = $this->getTagStoneDetails($rval['value']); 

            $returndata[$rkey]['other_metal_details'] = $this->get_other_metal_details($rval['value']); 

        }

        return $returndata;

    }

    

    function getCollectionDetails($id_tag_mapping)

    {

        $sql=$this->db->query("SELECT t.tag_id,t.tag_code

        FROM ret_tag_collection_mapping_details d 

        LEFT JOIN ret_taging t ON t.tag_id=d.tag_id

        WHERE d.id_tag_mapping=".$id_tag_mapping."");

        

        return $sql->result_array();

    }

    

    function getTaggingScanBySearch($SearchTxt,$searchField,$branch)

    {

        $data = $this->db->query("SELECT tag.tag_id as value,

        tag_code as label, tag_datetime, tag.tag_type, tag_lot_id,

        design_id, cost_center, tag.purity, IFNULL(tag.size,'') as size, uom, piece, IFNULL(tag.less_wt,0) as less_wt,IFNULL(tag.net_wt,0) as net_wt,IFNULL(tag.gross_wt,0) as gross_wt,

        tag.calculation_based_on, retail_max_wastage_percent,tag_mc_value,tag_mc_type,

        halmarking,tag.sell_rate as sales_value, tag.tag_status, product_name, product_short_code, tag.product_id as lot_product,

        pur.purity as purname,lot_inw.lot_received_at,

        ifnull(tag_stn_detail.stn_amount,if(tag.id_orderdetails!='',ord.stn_amt,0)) as stone_price,IFNULL(tag_stn_detail.certification_cost,0) as certification_cost,

        tag.tag_id,pro.sales_mode,tag.item_rate,tax.tax_percentage,tax.tgi_calculation,pro.metal_type,tag.current_branch,

        pro.tgrp_id as tax_group_id,IFNULL(tag.id_orderdetails,'') as id_orderdetails,

        IFNULL(lot_inw.order_no,'') as order_no,des.design_name,tag.tag_mark,r.rate_field,r.market_rate_field, rtc.charge_value,sub.sub_design_name,tag.id_sub_design,

        

        IFNULL(coll.id_tag_mapping,'') as id_tag_mapping,IFNULL(coll.id_mapping_details,'') as id_mapping_details,IFNULL(tagOtherMetal.tag_other_itm_amount,0) as tag_other_itm_amount,c.scheme_closure_benefit

        FROM ret_taging as tag

        Left join ret_lot_inwards_detail lot_det ON tag.id_lot_inward_detail = lot_det.id_lot_inward_detail

        LEFT JOIN ret_lot_inwards as lot_inw ON lot_inw.lot_no = lot_det.lot_no

        LEFT JOIN ret_product_master as pro ON pro.pro_id = tag.product_id

        

        LEFT JOIN ret_design_master des on des.design_no=tag.design_id

        

        left join ret_sub_design_master sub  on sub.id_sub_design=tag.id_sub_design

        LEFT JOIN ret_purity as pur ON pur.id_purity = tag.purity

        left join ret_category c on c.id_ret_category=pro.cat_id

        left join metal mt on mt.id_metal=c.id_metal 

        left join customerorderdetails ord on ord.id_orderdetails=tag.id_orderdetails

        

        LEFT JOIN ret_metal_purity_rate r on r.id_metal=c.id_metal and r.id_purity=tag.purity 

		LEFT JOIN (select i.tgi_taxcode,i.tgi_tgrpcode,

		GROUP_CONCAT(m.tax_percentage) as tax_percentage,

		GROUP_CONCAT(i.tgi_calculation) as tgi_calculation

		FROM ret_taxgroupitems i

		LEFT JOIN ret_taxmaster m on m.tax_id=i.tgi_taxcode) as tax on tax.tgi_tgrpcode=pro.tgrp_id

        LEFT JOIN (SELECT tag_id,sum(amount) as stn_amount,sum(wt) as stn_wt,sum(certification_cost) as certification_cost

        FROM `ret_taging_stone` WHERE is_apply_in_lwt = 1 

        GROUP by tag_id) as tag_stn_detail ON tag_stn_detail.tag_id = tag.tag_id

		LEFT JOIN (SELECT tag_id, SUM(IFNULL(charge_value,0)) AS charge_value FROM ret_taging_charges GROUP BY tag_id) AS rtc ON rtc.tag_id = tag.tag_id

		LEFT JOIN (SELECT tag_other_itm_tag_id, SUM(IFNULL(tag_other_itm_amount,0)) AS tag_other_itm_amount FROM ret_tag_other_metals GROUP BY tag_other_itm_tag_id) AS tagOtherMetal ON tagOtherMetal.tag_other_itm_tag_id = tag.tag_id



		LEFT JOIN(SELECT d.tag_id,d.id_tag_mapping,(d.tag_id) as total_set_collection,d.id_mapping_details

                 FROM ret_tag_collection_mapping_details d 

                 LEFT JOIN ret_tag_collection_mapping tagmap ON tagmap.id_tag_mapping=d.id_tag_mapping

                 WHERE tagmap.status=0

                 ) as coll ON coll.tag_id=tag.tag_id

        WHERE tag.tag_status=0 and tag.id_orderdetails is NULL AND tag.current_branch=".$branch." AND tag.".$searchField." ='".$SearchTxt."' ");

        //print_r($this->db->last_query());exit;

        $returndata = $data->result_array();

        foreach($returndata as $rkey => $rval){

            if($rval['id_tag_mapping']!='')

            {

                $collectionDetails   = $this->getCollectionDetails($rval['id_tag_mapping']);

                $returndata[$rkey]['collection_details'] =$collectionDetails;

                $returndata[$rkey]['total_set_collection'] =sizeof($collectionDetails);

            }

            $returndata[$rkey]['stone_details'] = $this->getTagStoneDetails($rval['value']); 

            $returndata[$rkey]['mc_va_limit'] = $this->get_mc_va_limit($rval['lot_product'], $rval['design_id'], $rval['id_sub_design']); 

            $returndata[$rkey]['other_metal_details'] = $this->get_other_metal_details($rval['value']); 

        }

        return $returndata;

    }

    

    function getTagStoneDetails($tagid)

    {

         $data = $this->db->query("SELECT ts.tag_id, ts.stone_id, ts.pieces, ts.wt, ts.uom_id, rate_per_gram, amount, is_apply_in_lwt , st.stone_type , stone_cal_type  

        FROM `ret_taging_stone` as ts 

        

        LEFT JOIN ret_stone as st ON st.stone_id = ts.stone_id 

        

        where tag_id = $tagid");

        

        return $data->result_array();

    }

    

    function get_other_metal_details($tagid)

    {

         $data = $this->db->query("Select * from ret_tag_other_metals where tag_other_itm_tag_id = $tagid");

        return $data->result_array();

    }

    

    function getProductBySearch($SearchTxt,$category,$is_non_tag,$id_branch)

    {

        $data=array();

        if($SearchTxt!='')

        {

            $products = $this->db->query("SELECT pro_id as value,

            concat(product_name,'-',product_short_code) as label,

            wastage_type, other_materials, has_stone,sales_mode,

            has_hook, has_screw, has_fixed_price,

            has_size, less_stone_wt, no_of_pieces,metal_type, calculation_based_on,group_concat(m.tax_percentage) as tax_percentage,GROUP_CONCAT(i.tgi_calculation) as tgi_calculation,

            mt.id_metal,pro.tgrp_id as tax_group_id,

            ifnull(nt.gross_wt,0) as gross_wt,IFNULL(nt.no_of_piece,0) as no_of_piece,c.scheme_closure_benefit

            FROM ret_product_master as pro

	            left join ret_category c on c.id_ret_category=pro.cat_id

	            left join metal mt on mt.id_metal=c.id_metal

	            left join ret_taxgroupitems i on i.tgi_tgrpcode=pro.tgrp_id

	            left join ret_taxmaster m on m.tax_id=i.tgi_taxcode

	            

	            left join ret_nontag_item nt on nt.product=pro.pro_id

			WHERE (product_short_code LIKE '%".$SearchTxt."%' OR product_name LIKE '%".$SearchTxt."%') ".(empty($category) ? ' ' : 'AND pro.cat_id ='.$category)." 

			".($id_branch!='' && $is_non_tag==1 ? " and nt.branch=".$id_branch."" :'')."

			GROUP BY pro.pro_id");

			//print_r($this->db->last_query());exit;

            $data=$products->result_array();

        }

        return $data;

    }

    

    

    

      function getCustomProductBySearch($SearchTxt,$category)

    {

        $data=array();

        if($SearchTxt!='')

        {

            $products = $this->db->query("SELECT pro_id as value,

            concat(product_name,'-',product_short_code) as label,

            wastage_type, other_materials, has_stone,sales_mode,

            has_hook, has_screw, has_fixed_price,

            has_size, less_stone_wt, no_of_pieces,metal_type, calculation_based_on,group_concat(m.tax_percentage) as tax_percentage,GROUP_CONCAT(i.tgi_calculation) as tgi_calculation,

            mt.id_metal,pro.tgrp_id as tax_group_id,c.scheme_closure_benefit

            FROM ret_product_master as pro

	            left join ret_category c on c.id_ret_category=pro.cat_id

	            left join metal mt on mt.id_metal=c.id_metal

	            left join ret_taxgroupitems i on i.tgi_tgrpcode=pro.tgrp_id

	            left join ret_taxmaster m on m.tax_id=i.tgi_taxcode

			WHERE (product_short_code LIKE '%".$SearchTxt."%' OR product_name LIKE '%".$SearchTxt."%') ".(empty($category) ? ' ' : 'AND pro.cat_id ='.$category)." GROUP BY pro.pro_id");

			// print_r($this->db->last_query());exit;

            $data=$products->result_array();

        }

        return $data;

    }

    

    /*function getProductDesignBySearch($SearchTxt, $procode,$id_branch)

    { 

        $data = $this->db->query("SELECT design_no as value,

        concat(design_name,'-',design_code) as label,

        min_length, max_length, min_width, max_width,

        min_dia, max_dia,

        min_weight, max_weight, fixed_rate,ifnull(nt.gross_wt,0) as gross_wt,IFNULL(nt.no_of_piece,0) as no_of_piece

        FROM ret_design_master as des

        left join ret_nontag_item nt on nt.design=des.design_no

        WHERE ( design_code LIKE '%".$SearchTxt."%' OR design_name LIKE '%".$SearchTxt."%' )

        

        ".($id_branch!='' ? " and nt.branch=".$id_branch."" :'')."

        

        ".(empty($procode) ? ' ' : 'AND product_id ='.$procode) );  

        return $data->result_array();

    }*/

    

    

    /*function getProductDesignBySearch($SearchTxt, $procode,$id_branch)

    { 

        $data = $this->db->query("SELECT design_no as value,

        concat(design_name,'-',design_code) as label,

        min_length, max_length, min_width, max_width,

        min_dia, max_dia,

        min_weight, max_weight, fixed_rate

        FROM ret_design_master as des

        WHERE ( design_code LIKE '%".$SearchTxt."%' OR design_name LIKE '%".$SearchTxt."%' )

        

        ".(empty($procode) ? ' ' : 'AND product_id ='.$procode) );  

    

    print_r($this->db->last_query());exit;

    

        return $data->result_array();

    }

    */

    

     function getProductDesignBySearch($SearchTxt, $procode,$id_branch,$is_non_tag)

    { 

        $data = $this->db->query("SELECT design_no as value,

        concat(design_name,'-',design_code) as label,

        min_length, max_length, min_width, max_width,

        min_dia, max_dia,

        min_weight, max_weight, fixed_rate,ifnull(nt.gross_wt,0) as gross_wt,IFNULL(nt.no_of_piece,0) as no_of_piece

        

        FROM ret_product_mapping p 

        

        LEFT JOIN ret_product_master pro ON pro.pro_id=p.pro_id

        

        LEFT JOIN ret_design_master des ON des.design_no=p.id_design

        

        left join ret_nontag_item nt on nt.design=des.design_no

        WHERE ( design_code LIKE '%".$SearchTxt."%' OR design_name LIKE '%".$SearchTxt."%' )

        

         ".($id_branch!='' && $is_non_tag==1 ? " and nt.branch=".$id_branch."" :'')."

        

        ".(empty($procode) ? ' ' : 'AND pro.pro_id ='.$procode)." 

        GROUP BY design_no"); 

        

       // print_r($this->db->last_query());exit;

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

	function get_old_metal_rate($id_metal)

	{

		$sql=$this->db->query("SELECT * from ret_old_metal_rate where id_metal=".$id_metal." and status=1");

		return $sql->row_array();

	}

	function get_all_old_metal_rates($id_metal = 0)

	{

		$where = "";

		if($id_metal > 0) {

			$where = " AND id_metal=".$id_metal;

		}

		$sql=$this->db->query("SELECT id_old_metal_rate, id_metal, IF(id_metal = 1 , 'Old Gold', 'Old Silver') AS metal, id_purity, id_branch, rate  from ret_old_metal_rate where 1 ".$where." and status=1");

		return $sql->result_array();

	}

	//chit account

	function get_closed_accounts($SearchTxt){

		$data = $this->db->query("SELECT  sa.id_scheme_account as value,sa.scheme_acc_number as label,sa.closing_balance,sa.is_closed,s.scheme_type

				from scheme_account sa

				left join scheme s on s.id_scheme=sa.id_scheme

				WHERE sa.id_scheme_account LIKE '%".$SearchTxt."%' and sa.is_closed=1");

		//echo $this->db->last_query();exit;

		return $data->result_array();

	}

	function get_employee($id_branch)

	{

		$result = [];

        $data = $this->db->query("SELECT 

        e.id_employee,

        CONCAT(CONCAT(e.emp_code,'-',e.firstname),' ',e.lastname)as emp_name,

        s.disc_limit_type,s.disc_limit,s.allowed_old_met_pur,e.login_branches,

        s.allow_branch_transfer,cs.login_branch

        FROM employee e

        LEFT JOIN employee_settings s on s.id_employee = e.id_employee

        JOIN chit_settings cs

        WHERE e.active=1");

		$employees = $data->result_array();

		if(sizeof($employees) > 0){

			if($id_branch == 0 || $id_branch == '' || $employees[0]['login_branch'] == 0){

				return $employees;

			}else{

				foreach($employees as $emp){

					if($emp['login_branches'] == 0 || $emp['login_branches'] == NULL){

						$result[] = array( 

									"id_employee" 			=> $emp['id_employee'],

									"emp_name" 				=> $emp['emp_name'],

									"disc_limit_type"		=> $emp['disc_limit_type'],

									"disc_limit" 			=> $emp['disc_limit'],

									"allowed_old_met_pur"	=> $emp['allowed_old_met_pur'],

									"allow_branch_transfer" => $emp['allow_branch_transfer']

								);

					}else{

						$login_branches = explode(',',$emp['login_branches']);

						foreach($login_branches as $b){

							if($b == $id_branch){

								$result[] = array( 

										"id_employee" 			=> $emp['id_employee'],

										"emp_name" 				=> $emp['emp_name'],

										"disc_limit_type"		=> $emp['disc_limit_type'],

										"disc_limit" 			=> $emp['disc_limit'],

										"allowed_old_met_pur"	=> $emp['allowed_old_met_pur'],

										"allow_branch_transfer" => $emp['allow_branch_transfer']

									);

							}

						} 

					}

					

				}

				return $result;

			}

		}else{

			return $result;

		} 

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

		    $sql="SELECT  m.mjdmagoldrate_22ct,m.goldrate_22ct,m.goldrate_24ct,m.silverrate_1gm,m.silverrate_1kg,m.mjdmasilverrate_1gm,platinum_1g,

		    Date_format(m.updatetime,'%d-%m%-%Y %h:%i %p')as updatetime   

		    FROM metal_rates m 

		    ORDER by m.id_metalrates desc LIMIT 1";

		}

		return $this->db->query($sql)->row_array();

	}

	

	function generateEstiNo($date,$id_branch)

	{

	  $sql = "SELECT max(esti_no) as lasEstiNo FROM ret_estimation WHERE date(estimation_datetime)='".$date."' ".($id_branch!='' ? " and id_branch=".$id_branch."" :'')." ORDER BY estimation_id DESC"; 

	  $lastno =  $this->db->query($sql)->row()->lasEstiNo; 

	  if($lastno != NULL && $lastno != '')

		{

		  	$number = (int) $lastno;

		  	$number++;

			$code_number=str_pad($number, 4, '0', STR_PAD_LEFT); 

    		return $code_number;

		}

		else

		{

			$code_number=str_pad('1', 4, '0', STR_PAD_LEFT);;

			return $code_number;

		}

	}

	

	/*function getOrderBySearch($SearchTxt,$branch)

    {

        $data = $this->db->query("SELECT cus.order_no as label,cus.order_no as value,e.id_product,e.design_no,e.wast_percent,product_name,

        e.mc,e.stn_amt,e.weight,e.size,e.size,e.totalitems,e.rate,des.design_name,

        pur.purity as purname,tax.tax_percentage,tax.tgi_calculation,

        pro.metal_type,mt.tgrp_id as tax_group_id,e.id_purity,e.stn_amt

        FROM customerorder cus

        left join customerorderdetails as e On e.id_customerorder=cus.id_customerorder

        LEFT JOIN ret_product_master as pro ON pro.pro_id = e.id_product

         LEFT JOIN ret_design_master as des ON des.design_no = e.design_no 

        LEFT JOIN ret_purity as pur ON pur.id_purity = e.id_purity

        left join ret_category c on c.id_ret_category=pro.cat_id

        left join metal mt on mt.id_metal=c.id_metal 

		LEFT JOIN (select i.tgi_taxcode,i.tgi_tgrpcode,

		GROUP_CONCAT(m.tax_percentage) as tax_percentage,

		GROUP_CONCAT(i.tgi_calculation) as tgi_calculation

		FROM ret_taxgroupitems i

		LEFT JOIN ret_taxmaster m on m.tax_id=i.tgi_taxcode) as tax on tax.tgi_tgrpcode=mt.tgrp_id

        WHERE cus.order_no LIKE '%".$SearchTxt."%' ".($branch!='' ? " and cus.order_from=".$branch."" :'')."

        and cus.est_id is null");

       //print_r($this->db->last_query());exit;

        return $data->result_array();

    }

	*/

	function getOrderBySearch($SearchTxt,$branch,$fin_year_code)

    {

    	$return_data=array();

        $data = $this->db->query("SELECT tag.tag_id as value,tag.tag_id, tag_code as label, tag_datetime, tag.tag_type, tag_lot_id,tag.tag_code as label, design_id, cost_center, tag.purity,IFNULL(tag.size,'') as size, uom, piece, 

        IFNULL(tag.less_wt,'') as less_wt, tag.net_wt, tag.gross_wt, tag.calculation_based_on, retail_max_wastage_percent,tag_mc_value,tag_mc_type, halmarking, sales_value, tag.tag_status,

        product_name, product_short_code,tag.product_id, pur.purity as purname,tag.id_orderdetails,mt.id_metal,p.tgrp_id as tax_group_id,tag.tag_mc_type,c.order_no,r.rate_field,r.market_rate_field,tag_stn_detail.stn_amount as stn_amt,

        d.design_name, rtc.charge_value,sub.sub_design_name,tag.id_sub_design,concat(cust.firstname,'-',cust.mobile) as customer,cust.id_customer,

        IFNULL(tagOtherMetal.tag_other_itm_amount,0) as tag_other_itm_amount

        FROM ret_taging tag

        LEFT JOIN customerorderdetails cus ON cus.id_orderdetails=tag.id_orderdetails

        LEFT JOIN customerorder c ON c.id_customerorder=cus.id_customerorder

        LEFT JOIN customer cust on cust.id_customer=c.order_to

        LEFT JOIN ret_product_master p on p.pro_id=tag.product_id 

        LEFT JOIN ret_design_master d on d.design_no=tag.design_id

        left join ret_sub_design_master sub  on sub.id_sub_design=tag.id_sub_design

        LEFT JOIN ret_category cat ON cat.id_ret_category=p.cat_id

        LEFT JOIN metal mt on mt.id_metal=cat.id_metal 

        LEFT JOIN ret_purity as pur ON pur.id_purity = tag.purity

        LEFT JOIN ret_metal_purity_rate r on r.id_metal=cat.id_metal and r.id_purity=tag.purity

        LEFT JOIN (SELECT tag_id,sum(amount) as stn_amount,sum(wt) as stn_wt,sum(certification_cost) as certification_cost

        FROM `ret_taging_stone` 

        GROUP by tag_id) as tag_stn_detail ON tag_stn_detail.tag_id = tag.tag_id

		LEFT JOIN (SELECT tag_id, SUM(IFNULL(charge_value,0)) AS charge_value FROM ret_taging_charges GROUP BY tag_id) AS rtc ON rtc.tag_id = tag.tag_id

		LEFT JOIN (SELECT tag_other_itm_tag_id, SUM(IFNULL(tag_other_itm_amount,0)) AS tag_other_itm_amount FROM ret_tag_other_metals GROUP BY tag_other_itm_tag_id) AS tagOtherMetal ON tagOtherMetal.tag_other_itm_tag_id = tag.tag_id

		

		WHERE c.order_no='".$SearchTxt."' and cus.orderstatus=4 and tag.tag_status=0

		and c.fin_year_code=".$fin_year_code."

		".($branch!='' ? " and c.order_from=".$branch."" :'')." 

		".($branch!='' ? " and tag.current_branch=".$branch."" :'')." 

		AND cus.id_orderdetails is NOT null");

        //print_r($this->db->last_query());exit;

        $return_data=$data->result_array();

        

        foreach($return_data as $rkey => $rval){

            $return_data[$rkey]['stone_details'] = $this->getTagStoneDetails($rval['value']); 

            $return_data[$rkey]['other_metal_details'] = $this->get_other_metal_details($rval['value']); 

            $return_data[$rkey]['mc_va_limit']   = $this->get_mc_va_limit($rval['product_id'], $rval['design_id'], $rval['id_sub_design']);

        }

        

        return $return_data;

    }

    

     function advance_details_order_no($orderno,$id_branch,$fin_year_code)

	{

	    $return_data=array();

	    if($orderno!=null && $orderno!='')

	    {

	       $advance=$this->db->query("SELECT a.order_no,a.advance_amount as paid_advance, a.advance_weight as paid_weight,s.metal_type,a.store_as,a.advance_type,a.rate_calc,a.rate_per_gram

            FROM customerorder c 

            LEFT JOIN ret_billing_advance a ON a.id_customerorder=c.id_customerorder

            LEFT JOIN ret_billing b ON b.bill_id=a.bill_id

            LEFT JOIN ret_bill_old_metal_sale_details s ON s.old_metal_sale_id=a.old_metal_sale_id

			where c.fin_year_code=".$fin_year_code." and a.is_adavnce_adjusted=0 and b.bill_status=1 and a.order_no='".$orderno."'

			".($id_branch!='' ? " and b.id_branch=".$id_branch."" :'')."");

	        //print_r($this->db->last_query());exit;

			$return_data=$advance->result_array();

			

			

			

	    }

	    return $return_data;

	}

    function get_order_details($order_no,$id_branch,$fin_year)

    {

    	$sql=$this->db->query("SELECT sum(od.totalitems) as tot_pcs,c.order_no

		FROM customerorder c

		LEFT JOIN customerorderdetails od ON od.id_customerorder=c.id_customerorder

		WHERE c.fin_year_code=".$fin_year." and od.orderstatus=4 and c.order_no='".$order_no."' ".($id_branch!='' ? "and c.order_from=".$id_branch."" :'')." ");

		//print_r($this->db->last_query());exit;

		return $sql->row()->tot_pcs;

    }

    function order_details($order_no)

    {

    	$sql=$this->db->query("SELECT sum(od.totalitems) as tot_pcs,c.order_no

		FROM customerorder c

		LEFT JOIN customerorderdetails od ON od.id_customerorder=c.id_customerorder

		WHERE c.order_no='".$order_no."'");

		//print_r($this->db->last_query());exit;

		return $sql->row_array();

    }

    

    

    function get_tag_details($tag_id)

    {

    	$sql=$this->db->query("SELECT 

    		IFNULL(SUM(tag.gross_wt),0) as tot_gwt,

    		IFNULL(SUM(tag.net_wt),0) as tot_nwt,

    		IFNULL(SUM(tag.less_wt),0) as tot_less_wt,

    		IFNULL(SUM(s.sold_gross_wt),0) as tot_sold_gwt,

    		IFNULL(SUM(s.sold_less_wt),0) as tot_sold_lwt,

    		IFNULL(SUM(s.sold_net_wt),0) as tot_sold_nwt

		FROM ret_taging tag

		LEFT JOIN ret_partlysold s on s.tag_id=tag.tag_id

		where tag.tag_id=".$tag_id."");

		//print_r($this->db->last_query());exit;

		return $sql->row_array();

    }

    function get_partial_details()

    {

    	$data = $this->db->query("SELECT tag.tag_id as value,

        tag_code as label, tag_datetime, tag.tag_type, tag_lot_id,

        design_id, cost_center, tag.purity, tag.size, uom, piece, tag.less_wt, tag.net_wt, tag.gross_wt,

        tag.calculation_based_on, retail_max_wastage_percent,tag_mc_value,tag_mc_type,

        halmarking, sales_value, tag.tag_status, product_name, product_short_code, lot_product,

        pur.purity as purname,lot_inw.lot_received_at,

        ifnull(tag_stn_detail.stn_amount,if(tag.id_orderdetails!='',ord.stn_amt,0)) as stone_price,IFNULL(tag_stn_detail.certification_cost,0) as certification_cost,

        tag.tag_id,pro.sales_mode,tag.item_rate,tax.tax_percentage,tax.tgi_calculation,pro.metal_type,tag.current_branch,

        pro.tgrp_id as tax_group_id,IFNULL(tag.id_orderdetails,'') as id_orderdetails,

        IFNULL(lot_inw.order_no,'') as order_no

        FROM ret_taging as tag

        Left join ret_lot_inwards_detail lot_det ON tag.id_lot_inward_detail = lot_det.id_lot_inward_detail

        LEFT JOIN ret_lot_inwards as lot_inw ON lot_inw.lot_no = lot_det.lot_no

        LEFT JOIN ret_product_master as pro ON pro.pro_id = lot_det.lot_product

        LEFT JOIN ret_purity as pur ON pur.id_purity = tag.purity

        left join ret_category c on c.id_ret_category=pro.cat_id

        left join metal mt on mt.id_metal=c.id_metal 

        left join customerorderdetails ord on ord.id_orderdetails=tag.id_orderdetails

        LEFT JOIN (SELECT tag_id,sum(amount) as stn_amount,sum(wt) as stn_wt,sum(certification_cost) as certification_cost

        FROM `ret_taging_stone` 

        GROUP by tag_id) as tag_stn_detail ON tag_stn_detail.tag_id = tag.tag_id

        WHERE tag.current_branch=".$branch." AND tag.".$searchField." LIKE '%".$SearchTxt."%'");

    }

     function getPartialTagSearch($SearchTxt,$searchField,$branch)

    {

		$data=$this->db->query("SELECT tag.tag_id as value,

        tag.tag_code as label, tag_datetime,pro.tgrp_id as tax_group_id,

        p.blc_gross_wt as gross_wt,p.blc_less_wt as less_wt,p.blc_net_wt as net_wt,

        prod.product_name,prod.product_short_code,pur.purity as purname,prod.metal_type,

        prod.calculation_based_on,tag.product_id,tag.design_id,tag.purity,

        tag.size,tag.piece,tag.tag_mc_type,tag.tag_mc_value,tag.retail_max_wastage_percent,

        tag.item_rate,tag.current_branch,des.design_name,tag.design_id, 

        

        r.rate_field,r.market_rate_field,sub.sub_design_name, tag.id_sub_design

		from ret_taging tag

		LEFT JOIN ret_partlysold p on p.tag_id = tag.tag_id

		LEFT JOIN ret_product_master prod on prod.pro_id = p.product

		LEFT JOIN ret_purity as pur ON pur.id_purity = tag.purity

	    left join ret_category c on c.id_ret_category=prod.cat_id

	    LEFT JOIN ret_design_master as des ON des.design_no = tag.design_id

	    

	    left join ret_sub_design_master sub  on sub.id_sub_design=tag.id_sub_design

	    left join metal mt on mt.id_metal=c.id_metal 

	    

	    LEFT JOIN ret_metal_purity_rate r on r.id_metal=c.id_metal and r.id_purity=tag.purity

		WHERE tag.current_branch=".$branch." AND tag.".$searchField." LIKE '%".$SearchTxt."%'

		and p.status=1");

        /*$data = $this->db->query("SELECT tag.tag_id as value,

        tag_code as label, tag_datetime, tag.tag_type, tag_lot_id,

        design_id, cost_center, tag.purity, tag.size, uom, piece, tag.less_wt, tag.net_wt, tag.gross_wt,

        tag.calculation_based_on, retail_max_wastage_percent,tag_mc_value,tag_mc_type,

        halmarking, sales_value, tag.tag_status, product_name, product_short_code, lot_product,

        pur.purity as purname,lot_inw.lot_received_at,

        ifnull(tag_stn_detail.stn_amount,if(tag.id_orderdetails!='',ord.stn_amt,0)) as stone_price,IFNULL(tag_stn_detail.certification_cost,0) as certification_cost,

        tag.tag_id,pro.sales_mode,tag.item_rate,tax.tax_percentage,tax.tgi_calculation,pro.metal_type,tag.current_branch,

        mt.tgrp_id as tax_group_id,IFNULL(tag.id_orderdetails,'') as id_orderdetails,

        IFNULL(lot_inw.order_no,'') as order_no

        FROM ret_taging as tag

        LEFT JOIN ret_partlysold t on t.tag_id=tag.tag_id

        Left join ret_lot_inwards_detail lot_det ON tag.id_lot_inward_detail = lot_det.id_lot_inward_detail

        LEFT JOIN ret_lot_inwards as lot_inw ON lot_inw.lot_no = lot_det.lot_no

        LEFT JOIN ret_product_master as pro ON pro.pro_id = lot_det.lot_product

        LEFT JOIN ret_purity as pur ON pur.id_purity = tag.purity

        left join ret_category c on c.id_ret_category=pro.cat_id

        left join metal mt on mt.id_metal=c.id_metal 

        left join customerorderdetails ord on ord.id_orderdetails=tag.id_orderdetails

		LEFT JOIN (select i.tgi_taxcode,i.tgi_tgrpcode,

		GROUP_CONCAT(m.tax_percentage) as tax_percentage,

		GROUP_CONCAT(i.tgi_calculation) as tgi_calculation

		FROM ret_taxgroupitems i

		LEFT JOIN ret_taxmaster m on m.tax_id=i.tgi_taxcode) as tax on tax.tgi_tgrpcode=mt.tgrp_id

        LEFT JOIN (SELECT tag_id,sum(amount) as stn_amount,sum(wt) as stn_wt,sum(certification_cost) as certification_cost

        FROM `ret_taging_stone` 

        GROUP by tag_id) as tag_stn_detail ON tag_stn_detail.tag_id = tag.tag_id

        WHERE tag.current_branch=".$branch." AND t.".$searchField." LIKE '%".$SearchTxt."%'");*/

       //print_r($this->db->last_query());exit;

        $returnarray =  $data->result_array();

        foreach($returnarray as $rkey => $rval){

            $returnarray[$rkey]['stone_details'] = $this->get_partly_home_bill_stones($rval['value']);

            $returnarray[$rkey]['mc_va_limit']      = $this->get_mc_va_limit($rval['product_id'], $rval['design_id'], $rval['id_sub_design']);

        }

        return $returnarray;

    }

    

    

    function get_partly_home_bill_stones($tag_id)

    {

        $sql = $this->db->query("SELECT ts.is_apply_in_lwt, ts.stone_id, ts.uom_id, ts.rate_per_gram, st.stone_type, ts.stone_cal_type, (ts.pieces - sum((SELECT ifnull(eis.pieces,0) 

                                FROM ret_estimation_item_stones as eis 

                                LEFT JOIN ret_estimation_items as esi ON esi.est_item_id = eis.est_item_id 

                                WHERE purchase_status = 1 AND esi.tag_id = ts.tag_id and eis.stone_id = ts.stone_id))) as pieces , 

                                (ts.wt - sum((SELECT ifnull(eis.wt,0) 

                                FROM ret_estimation_item_stones as eis 

                                LEFT JOIN ret_estimation_items as esi ON esi.est_item_id = eis.est_item_id 

                                WHERE purchase_status = 1 AND esi.tag_id = ts.tag_id and eis.stone_id = ts.stone_id))) as wt, 

                                (ts.amount - sum((SELECT ifnull(eis.price,0) 

                                FROM ret_estimation_item_stones as eis 

                                LEFT JOIN ret_estimation_items as esi ON esi.est_item_id = eis.est_item_id 

                                WHERE purchase_status = 1 AND esi.tag_id = ts.tag_id and eis.stone_id = ts.stone_id))) as amount 

                                FROM `ret_taging_stone` as ts 

                                LEFT JOIN ret_stone as st ON st.stone_id =  ts.stone_id 

                                WHERE ts.tag_id = '".$tag_id."' GROUP BY stone_id");

        return $sql->result_array();

    }

    

    function get_old_metal_type($id_metal)

    {

        $sql=$this->db->query("SELECT metal_type,id_metal_type FROM ret_old_metal_type where id_metal=".$id_metal);

        return $sql->result_array();

    }

	function get_old_metal_category()

    {

        $sql=$this->db->query("SELECT id_old_metal_cat,id_metal,id_old_metal_type,old_metal_cat,old_metal_perc  FROM ret_old_metal_category");

        return $sql->result_array();

    }

	function get_metal_purity_rate($data)

    {

        $sql=$this->db->query("SELECT * FROM `ret_metal_purity_rate` where id_metal=".$data['id_metal']." and id_purity=".$data['id_purity']."");

        return $sql->row_array();

    }

    

    //CUstomer Purchase Details

    function getCustomerDet($id_customer)

	{

	    $return_data=array();

		$data=$this->db->query("SELECT c.firstname,c.id_customer,c.is_vip,c.mobile,v.village_name,b.name as branch_name,IFNULL(esti.tot_est,0) as estimation_no,

        IFNULL(bill_tot.bill_count,0) as bill_count,IFNULL(tot_acc.tot_acc,0) as tot_account,IFNULL(iactive.tot_acc,0)as inactive_acount,

        IFNULL(tot_gold.gold_wt,0) as gold_wt,IFNULL(tot_silver.silver_wt,0) as silver_wt,IFNULL(closed_chit.closed_count,0) as closed_count,IFNULL(tot_payment.pay_amount,0) as tot_amount,

        IFNULL(fixed_rate.item_cost,0) as tot_fixed_rate,IFNULL(active_acc.tot_acc,0) as active_acc,IFNULL(DATE_FORMAT(max(bill.bill_date),'%d-%m-%Y'),'-') as last_billdate,z.name as zone_name

        FROM customer c
        
        LEFT JOIN ret_billing bill ON bill.bill_cus_id = c.id_customer

        left join branch b on (b.id_branch=c.id_branch)

        LEFT JOIN village v on (v.id_village=c.id_village)

        LEFT JOIN village_zone z on (z.id_zone=v.id_zone)


        left join (select count(est.estimation_id) as tot_est,est.cus_id from ret_estimation est

                   left join customer as c on c.id_customer=est.cus_id where cus_id=".$id_customer.")as esti on esti.cus_id=c.id_customer

       

        left join (select COUNT(bill.bill_id) as bill_count,bill.bill_cus_id from ret_billing as bill

            left join customer as c on c.id_customer=bill.bill_cus_id

            where bill.bill_status=1 and c.id_customer=".$id_customer."

            ) as bill_tot on bill_tot.bill_cus_id=c.id_customer

                   

        left join(select count(sa.id_scheme_account) as tot_acc,sa.id_customer,c.mobile from scheme_account sa

            left join customer c on (c.id_customer=sa.id_customer)

            where sa.scheme_acc_number is not null and c.id_customer=".$id_customer.") as tot_acc on tot_acc.id_customer=c.id_customer

           

        left join(select count(sa.id_scheme_account) as tot_acc,sa.id_customer,c.mobile from scheme_account sa

            left join customer c on (c.id_customer=sa.id_customer)

            where sa.scheme_acc_number is not null and sa.is_closed=0 and c.id_customer=".$id_customer.") as active_acc on active_acc.id_customer=c.id_customer

           

        left join(select count(sa.scheme_acc_number) as closed_count,sa.id_customer,c.mobile from scheme_account sa

            left join customer c on (c.id_customer=sa.id_customer)

            where sa.is_closed=1 and c.id_customer=".$id_customer.") as closed_chit on closed_chit.id_customer=c.id_customer

        

        left join (SELECT COUNT(sa.id_scheme_account) as tot_acc,

            TIMESTAMPDIFF(month, max(p.date_add), current_date()) as month_ago,sa.id_customer

            FROM scheme_account sa

            LEFT JOIN payment p ON p.id_scheme_account=sa.id_scheme_account

            LEFT JOIN scheme s ON s.id_scheme=sa.id_scheme

            LEFT JOIN customer cus ON cus.id_customer=sa.id_customer

            WHERE sa.is_closed=0

            and cus.id_customer=".$id_customer." HAVING month_ago>3) as iactive on iactive.id_customer=c.id_customer

        left join(select sum(bill_det.net_wt) as gold_wt,c.id_customer from ret_billing as bill

            left JOIN ret_bill_details  as bill_det on(bill_det.bill_id=bill.bill_id)

            left join ret_product_master as pro on(pro.pro_id=bill_det.product_id)

            left join ret_category as cat on(cat.id_ret_category=pro.cat_id)

            left join metal as m on(m.id_metal=cat.id_metal)

            left join customer as c on(c.id_customer=bill.bill_cus_id)

            left join branch b on (b.id_branch=c.id_branch)

            LEFT join village v on (v.id_village=c.id_village)

            LEFT JOIN village_zone z on (z.id_zone=v.id_zone)

            where bill.bill_status=1 and m.id_metal=1 

            and c.id_customer=".$id_customer."

             ) as tot_gold on tot_gold.id_customer=c.id_customer

           

        left join(SELECT sum(bill_det.net_wt) as silver_wt,c.id_customer from ret_billing as bill

            left JOIN ret_bill_details  as bill_det on(bill_det.bill_id=bill.bill_id)

            left join ret_product_master as pro on(pro.pro_id=bill_det.product_id)

            left join ret_category as cat on(cat.id_ret_category=pro.cat_id)

            left join metal as m on(m.id_metal=cat.id_metal)

            left join customer as c on(c.id_customer=bill.bill_cus_id)

            left join branch b on (b.id_branch=c.id_branch)

            LEFT join village v on (v.id_village=c.id_village)

            LEFT JOIN village_zone z on (z.id_zone=v.id_zone)

            where bill.bill_status=1 and m.id_metal=2 and c.id_customer=".$id_customer."

            ) as tot_silver on tot_silver.id_customer=c.id_customer

           

         left join(SELECT sum(bill_det.item_cost) as item_cost,c.id_customer from ret_billing as bill

            left JOIN ret_bill_details  as bill_det on(bill_det.bill_id=bill.bill_id)

            left join ret_product_master as pro on(pro.pro_id=bill_det.product_id)

            left join ret_category as cat on(cat.id_ret_category=pro.cat_id)

            left join metal as m on(m.id_metal=cat.id_metal)

            left join customer as c on(c.id_customer=bill.bill_cus_id)

            left join branch b on (b.id_branch=c.id_branch)

            LEFT join village v on (v.id_village=c.id_village)

            LEFT JOIN village_zone z on (z.id_zone=v.id_zone)

            where bill.bill_status=1 and pro.sales_mode=1 and c.id_customer=".$id_customer."

        	) as fixed_rate on fixed_rate.id_customer=c.id_customer

           

        left join(select sum(p.payment_amount) as pay_amount,c.id_customer,c.mobile from customer as c

            left join scheme_account as sa on(sa.id_customer=c.id_customer)

            left join payment as p on (p.id_scheme_account=sa.id_scheme_account)

            where c.id_customer=".$id_customer.") as tot_payment on tot_payment.id_customer=c.id_customer 

            where c.id_customer IS NOT NULL

            and c.id_customer=".$id_customer."");

			// print_r($this->db->last_query());exit;

		

		$return_data['cus_details']=$data->result_array();

		

		$sql=$this->db->query("SELECT b.bill_id,b.bill_no,br.name as branch_name,IFNULL(g_wt.net_wt,0) as gold_wt,IFNULL(s_wt.net_wt,0) as silver_wt,IFNULL(fixed_rate.item_cost,0) as mrp_amount,DATE_FORMAT(b.bill_date,'%d-%m-%Y') as bill_date,b.tot_bill_amount,if(b.bill_status=1,'Success','Cancelled') as bill_status

        FROM ret_billing b 

        LEFT JOIN customer c ON c.id_customer=b.bill_cus_id

        LEFT JOIN ret_bill_details d ON d.bill_id=b.bill_id

		LEFT JOIN branch br on br.id_branch=b.id_branch

        

        left JOIN (select d.net_wt,b.bill_id from ret_billing b 

                  left join customer c ON c.id_customer=b.bill_cus_id

                  left JOIN ret_bill_details d ON d.bill_id=b.bill_id

                  left join ret_product_master as pro on pro.pro_id=d.product_id

            	  left join ret_category as cat on cat.id_ret_category=pro.cat_id

            	  left join metal as m on m.id_metal=cat.id_metal WHERE b.bill_status=1

                  and m.id_metal=1 and d.bill_det_id is NOT NULL and c.id_customer=".$id_customer.") as g_wt 

                  ON g_wt.bill_id=b.bill_id

                  

          left JOIN (select d.net_wt,b.bill_id from ret_billing b 

                  left join customer c ON c.id_customer=b.bill_cus_id

                  left JOIN ret_bill_details d ON d.bill_id=b.bill_id

                  left join ret_product_master as pro on pro.pro_id=d.product_id

            	  left join ret_category as cat on cat.id_ret_category=pro.cat_id

            	  left join metal as m on m.id_metal=cat.id_metal WHERE b.bill_status=1

                  and m.id_metal=2 and d.bill_det_id is NOT NULL and c.id_customer=".$id_customer.") as s_wt 

                  ON s_wt.bill_id=b.bill_id

                  

          left JOIN (select d.item_cost,b.bill_id from ret_billing b 

                  left join customer c ON c.id_customer=b.bill_cus_id

                  left JOIN ret_bill_details d ON d.bill_id=b.bill_id

                  left join ret_product_master as pro on pro.pro_id=d.product_id

            	  left join ret_category as cat on cat.id_ret_category=pro.cat_id

            	  left join metal as m on m.id_metal=cat.id_metal WHERE b.bill_status=1

                  and pro.sales_mode=1 and d.bill_det_id is NOT NULL and c.id_customer=".$id_customer.") as fixed_rate

                  ON fixed_rate.bill_id=b.bill_id

        

        WHERE c.id_customer=".$id_customer." 

        GROUP by b.bill_id ORDER BY b.bill_date DESC LIMIT 5");

        

        $return_data['bill_details']=$sql->result_array();

        

        return $return_data;

    

	}

    //CUstomer Purchase Details

    

    

    function get_other_estcharges($est_item_id)

	{

		$sql = $this->db->query("

		SELECT e.id_charge,e.amount,s.code_charge

        FROM ret_estimation_other_charges e 

        LEFT JOIN ret_charges s on s.id_charge=e.id_charge

	    WHERE e.est_item_id=".$est_item_id."");  

	    return $sql->result_array();

	}

	

	

	function getProductSubDesignBySearch($SearchTxt, $procode,$id_branch)

    {

        $sql=$this->db->query("SELECT p.id_sub_design as value,p.sub_design_name as label

        FROM ret_sub_design_master p 

        where p.sub_design_name LIKE '%".$SearchTxt."%'");

        return $sql->result_array();

    }

    function get_mc_va_limit($id_product, $id_design, $id_sub_design) {

        $mc_min = 0;

        

        $wastag_min = 0;

        

        $sql = "SELECT

                    IFNULL(mc_min,0) AS mc_min, 

                    IFNULL(wastag_min ,0) AS wastag_min

                FROM ret_sub_design_mapping 

                WHERE id_product = ".$id_product." AND id_design=".$id_design." AND id_sub_design = ".$id_sub_design;

        //echo $sql;exit;

		

        $query_details = $this->db->query($sql);

        if ($query_details->num_rows() > 0)

        {

            $row = $query_details->row_array(); 

            $mc_min = $row['mc_min'];

            $wastag_min = $row['wastag_min'];

        }

        $result_arr = array("mc_min" => $mc_min, "wastag_min" => $wastag_min);

		return $result_arr;

	}

    

    

    function get_purity_rate()

    {

        $sql=$this->db->query("SELECT * FROM `ret_metal_purity_rate`");

        return $sql->result_array();

    }

    function getCompanyDetails($id_branch)

	{

		if($id_branch=='')

		{

			$sql = $this->db->query("Select  c.id_company,c.company_name,c.gst_number,c.short_code,c.pincode,c.mobile,c.whatsapp_no,c.phone,c.email,c.website,

			c.address1,c.address2,c.id_country,c.id_state,c.id_city,ct.name as city,s.name as state,cy.name as country,cs.currency_symbol,

			cs.currency_name,cs.mob_code,cs.mob_no_len,c.mail_server,c.mail_password,c.send_through,c.mobile1,

			c.phone1,c.smtp_user,c.smtp_pass,c.smtp_host,c.server_type,cs.login_branch,

			s.state_code 

			from company c

			join chit_settings cs

			left join country cy on (c.id_country=cy.id_country)

			left join state s on (c.id_state=s.id_state)

			left join city ct on (c.id_city=ct.id_city)");

		}

		else

		{

			$sql=$this->db->query("select b.name,b.address1,b.address2,c.company_name,

				cy.name as country,ct.name as city,s.name as state,b.pincode,s.id_state,s.state_code, c.gst_number 

				from branch b

				join company c

				left join country cy on (b.id_country=cy.id_country)

				left join state s on (b.id_state=s.id_state)

				left join city ct on (b.id_city=ct.id_city)

				where b.id_branch=".$id_branch."");

		}

		$result = $sql->row_array();

		return $result;

	}
	
	function get_ActiveProduct()
	{
	    $sql = $this->db->query("SELECT * FROM `ret_product_master` WHERE product_status = 1");
	    return $sql->result_array();
	}

    

}

?>
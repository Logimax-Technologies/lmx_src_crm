<?php
if( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ret_catalog_model extends CI_Model
{
	
	function __construct()
    {
        parent::__construct();
    }
      
  /*Ret category functions here */ 	
    function empty_record_category()
    {
		$data=array(
			'id_ret_category'	=> NULL,
			'name'				=> NULL,
			'description'		=> NULL,
			'image'				=> NULL,
			'status'			=> 1
		);
		return $data;
	}
	
	// Default functions
	public function insertData($data,$table)
    {
    	$insert_flag = 0;
		$insert_flag = $this->db->insert($table,$data);
	//	print_r($this->db->last_query());exit;
		return ($insert_flag == 1 ? $this->db->insert_id(): 0);
	}
	
	public function updateData($data,$id_field,$id_value,$table)
    {    
	    $edit_flag = 0;
	    $this->db->where($id_field,$id_value);
		$edit_flag = $this->db->update($table,$data);
		//print_r($this->db->last_query());exit;
		return ($edit_flag==1?$id_value:0);
	}	 
	public function deleteData($id_field,$id_value,$table)
    {
        $this->db->where($id_field, $id_value);
        $status= $this->db->delete($table); 
		//print_r($this->db->last_query());exit;
		return $status;
	} 
 
   	
	/*Purity functions here */ 	
  
   	 function ajax_getPurity()
    {
		$id_purity = $this->db->query("SELECT * FROM ret_purity ORDER BY id_purity desc");
		return $id_purity->result_array();
	}

	 function get_purity($id_purity)
    {
		$id_purity = $this->db->query("select * from ret_purity where id_purity=".$id_purity);
		return $id_purity->row_array();
	}
	
    public function insert_purity($data)
    {
    	$insert_flag = 0;
		$insert_flag = $this->db->insert('ret_purity',$data);
		return ($insert_flag == 1 ? $this->db->insert_id(): 0);
	}
	
	public function update_purity($data,$id)
    {    	
    	$edit_flag = 0;
    	$this->db->where('id_purity',$id); 
		$edit_flag = $this->db->update('ret_purity',$data);
		return ($edit_flag==1?$id:0);
	}
			 
	public function delete_purity($id)
    {
        $this->db->where('id_purity', $id);
        $status= $this->db->delete('ret_purity'); 
		return $status;
	} 
	/*End of purity functions*/
	
	/*color functions here */ 	
  
   	 function ajax_getcolor()
    {
		$id_color = $this->db->query("SELECT * FROM ret_color ORDER BY id_color desc");
		return $id_color->result_array();
	}
 	
	 function get_color($id_color)
    {
		$id_color = $this->db->query("select * FROM ret_color where id_color=".$id_color);
		return $id_color->row_array();
	}
	
    public function insert_color($data)
    {
    	$insert_flag = 0;
		$insert_flag = $this->db->insert('ret_color',$data);
		return ($insert_flag == 1 ? $this->db->insert_id(): 0);
	}
	
	public function update_color($data,$id)
    {    	
    	$edit_flag = 0;
    	$this->db->where('id_color',$id); 
		$edit_flag = $this->db->update('ret_color',$data);
		return ($edit_flag==1?$id:0);
	}
			 
	public function delete_color($id)
    {
        $this->db->where('id_color', $id);
        $status= $this->db->delete('ret_color'); 
		return $status;
	} 
	/*End of color functions*/
	/*cut functions here */ 	
  
   	 function ajax_getcut()
    {
		$id_cut = $this->db->query("SELECT * FROM ret_cut ORDER BY id_cut desc");
		return $id_cut->result_array();
	}

	 function get_cut($id_cut)
    {
		$id_cut = $this->db->query("select * FROM ret_cut where id_cut=".$id_cut);
		return $id_cut->row_array();
	}
	
    public function insert_cut($data)
    {
    	$insert_flag = 0;
		$insert_flag = $this->db->insert('ret_cut',$data);
		return ($insert_flag == 1 ? $this->db->insert_id(): 0);
	}
	
	public function update_cut($data,$id)
    {    	
    	$edit_flag = 0;
    	$this->db->where('id_cut',$id); 
		$edit_flag = $this->db->update('ret_cut',$data);
		return ($edit_flag==1?$id:0);
	}
			 
	public function delete_cut($id)
    {
        $this->db->where('id_cut', $id);
        $status= $this->db->delete('ret_cut'); 
		return $status;
	} 
	/*End of cut functions*/
	/*clarity functions here */ 	
  
   	 function ajax_getclarity()
    {
		$id_clarity = $this->db->query("SELECT * FROM ret_clarity ORDER BY id_clarity desc");
		return $id_clarity->result_array();
	}

	 function get_clarity($id_clarity)
    {
		$id_clarity = $this->db->query("select * FROM ret_clarity where id_clarity=".$id_clarity);
		return $id_clarity->row_array();
	}
	
    public function insert_clarity($data)
    {
    	$insert_flag = 0;
		$insert_flag = $this->db->insert('ret_clarity',$data);
		return ($insert_flag == 1 ? $this->db->insert_id(): 0);
	}
	
	public function update_clarity($data,$id)
    {    	
    	$edit_flag = 0;
    	$this->db->where('id_clarity',$id); 
		$edit_flag = $this->db->update('ret_clarity',$data);
		return ($edit_flag==1?$id:0);
	}
			 
	public function delete_clarity($id)
    {
        $this->db->where('id_clarity', $id);
        $status= $this->db->delete('ret_clarity'); 
		return $status;
	} 
	/*End of clarity functions*/
	
 /*product functions here */ 	
    function empty_record_product()
    {
		$data=array(
			'id_product'			=> NULL,
			'id_subcategory'		=> NULL,
			'id_metal'				=> NULL,
			'name'					=> NULL,
			'description'			=> NULL,
			'code'					=> NULL,
			'qty'					=> 1,
			'qty_type'				=> 1,
			'allow_customization'	=> 0,
			'certification'			=> NULL,
			'min_size'				=> NULL,
			'max_size'				=> NULL,
			'gold_value'			=> NULL,
			'wastage'				=> 0.00,
			'min_wastage'			=> NULL,
			'max_wastage'			=> NULL,
			'tax'					=> 0.00,
			'stone_charges'			=> 0.00,
			'other_charges'			=> 0.00,
			'making_charges'		=> 0.00,
			'allowed_order_qty'		=> 1,
			'certif_charges'		=> 0.00,
			'product_stone'			=> 2,
			'length'				=> NULL,
			'width'					=> NULL,
			'height'				=> NULL,
			'total_stones'			=> NULL,
			'stone_name'			=> NULL,
			'default_img'			=> NULL,
			'certif_img'			=> NULL,
			'can_view_by'			=> 1,
			'show_rate'				=> 1,
			'status'				=> 1,
			'product_for'			=> 0
		);
		return $data;
	}
 
   	 function ajax_getProduct()
    {
		$product = $this->db->query("SELECT p.`id_product`, p.`id_subcategory`, p.`name`, p.`status`, p.`code`, p.`allow_customization`, p.`product_stone`,c.name as category_name,sc.name as subcategory_name
		 FROM product p
		left join sub_category sc on (p.id_subcategory=sc.id_subcategory)
		left join category c on (c.id_ret_category=sc.id_ret_category)");
		return $product->result_array();
	}

	 function get_product($id_product)
    {
		$product = $this->db->query("SELECT p.allowed_order_qty,p.qty_type,p.can_view_by,p.length,p.width,p.height,p.stone_name,p.`id_product`, p.`id_subcategory`, p.`name`, p.`description`, p.`status`, p.`code`, p.`qty`, p.`date_add`, p.`date_update`, p.`allow_customization`, p.`certification`, p.`min_size`, p.`max_size`, p.`gold_value`, p.`wastage`, p.`tax`, p.`stone_charges`, p.`making_charges`, p.`other_charges`, p.`product_stone`, p.`certif_charges`,p.`total_stones`, p.`id_employee`, p.`id_metal`,c.`id_ret_category`, c.`name` as category_name,s.`id_subcategory`, s.`name` as subcategory,p.show_rate,p.certif_img,(SELECT image from product_images as img where is_default=1 and img.id_product=p.id_product) as default_img,p.product_for,metal_type
 		FROM product p 
    	 left join sub_category s on (p.id_subcategory=s.id_subcategory)
		 left join category c on (c.id_ret_category=s.id_ret_category)
		 where p.id_product=".$id_product);
		return $product->row_array();
	}
	
	// get product images except defalt image
	function get_prodimage($id)
    {
		$product = $this->db->query("select image,is_default from product_images where is_default=0 and id_product=".$id);
		return $product->result_array('image');
	}
	
	// get product images except defalt image
	function get_defaultProdimage($id)
    {
		$product = $this->db->query("select image,is_default from product_images where is_default=1 and id_product=".$id);
		return $product->result_array('image');
	}
	
	function delete_prodimage($file)
    {
		 $this->db->where('image', $file);
        $status= $this->db->delete('product_images'); 
		return $status;
	}
	
	function insertProdImage($data){
		$insert_flag = $this->db->insert('product_images',$data);
		return ($insert_flag == 1 ? $this->db->insert_id(): 0);
	}
	
	public function deleteProdImage($id)
    {
    	 $this->db->where('id_image', $id);
        $status= $this->db->delete('product_images'); 
		return $status;
	} 
	
    public function insert_product($data)
    {
    	$insert_flag = 0;
    	
		$insert_flag = $this->db->insert('product',$data);
		return ($insert_flag == 1 ? $this->db->insert_id(): 0);
	}
	
	 public function insert_product_detail($data)
    {
    	$insert_flag = 0;
    	
		$insert_flag = $this->db->insert('product_details',$data);
		return ($insert_flag == 1 ? $this->db->insert_id(): 0);
	}
	
	public function update_product($data,$id)
    {    	
    	$edit_flag = 0;
    	$this->db->where('id_product',$id); 
		$edit_flag = $this->db->update('product',$data);
		return ($edit_flag==1?$id:0);
	}
			 
	public function delete_product($id)
    {
    	$this->db->where('id_product', $id);
        $img= $this->db->delete('product_images');
        if($img)
        {
		  $this->db->where('id_product', $id);
         $child= $this->db->delete('product_details');
		}
        if($child)
        {
		  $this->db->where('id_product', $id);
           $status= $this->db->delete('product'); 
		}
		
		return $status;
	}
	public function delete_prodDetail($id)
    {
        $this->db->where('id_product', $id);
        $status= $this->db->delete('product_details'); 
		return $status;
	} 
	/*End of product functions*/
	
	function getActiveMetals()
    {
		$data = $this->db->query("SELECT * FROM metal");
		return $data->result_array();
	}
	function getActivePurity()
    {
		$data = $this->db->query("SELECT * FROM ret_purity WHERE status = 1");
		return $data->result_array();
	}
	function getActiveCut()
    {
		$data = $this->db->query("SELECT * FROM ret_cut WHERE status = 1");
		return $data->result_array();
	}
	function getActiveClarity()
    {
		$data = $this->db->query("SELECT * FROM ret_clarity WHERE status = 1");
		return $data->result_array();
	}
	function getmetalInfo($id)
    {
		$data = $this->db->query("SELECT p.`metal_color`, p.`purity`, p.`id_metal_details`, p.`max_metal_weight`, p.`min_metal_weight`, p.`type` FROM product_details p WHERE type=0 and id_product =".$id);
		return $data->result_array();
	}
	function getdiamondInfo($id)
    {
		$data = $this->db->query("SELECT p.`type`, p.`diamond_color`, p.`carat`, p.`clarity`, p.`cut`, p.`id_metal_details` FROM product_details p WHERE type=1 and id_product =".$id);
		return $data->result_array();
	}
	 
	function getActivecolor()
    {
		$id_color = $this->db->query("SELECT * FROM ret_color WHERE status = 1");
		return $id_color->result_array();
	}
	function getActiveSubctg()
    {
		$data = $this->db->query("SELECT s.`id_subcategory`, s.`id_ret_category`, s.`name`, s.`status`,
      (select name from category where id_ret_category = s.id_ret_category) as category
     FROM sub_category s WHERE status = 1");
		return $data->result_array();
	}
	
	
	
	// Floor Master
	function ajax_getfloor($from_date,$to_date,$id_branch)
    {
		$data =array();
		$sql = "SELECT f.floor_id,b.id_branch,b.name as branch_name,f.floor_name,f.floor_short_code,
		f.created_on,f.floor_status FROM ret_branch_floor as f 
		left join branch b on b.id_branch = f.branch_id";

		if(($id_branch!='' && $id_branch > 0) && $from_date!='')
		{
			$sql = $sql." where b.id_branch =" .$id_branch. ' and (date(f.created_on)
		BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")';
		}
		elseif(($id_branch!='' && $id_branch > 0 ) || $from_date!='')
		{ 
			$sql = $sql." where ".($id_branch != '' && $id_branch > 0  ? ('b.id_branch ='.$id_branch) : ($from_date != '' ? ' date(f.created_on) BETWEEN '.date('Y-m-d',strtotime($from_date)).' AND '.date('Y-m-d',strtotime($to_date)) : (''))) ;
		}  
		$result=$this->db->query($sql);
		$data = $result->result_array();
		return $data;
	}

	function get_floor($floor_id)
    {
		$floor_id = $this->db->query("select * from ret_branch_floor where floor_id=".$floor_id);
		return $floor_id->row_array();
	}
	
	function getActiveFloors()
    {
		$data = $this->db->query("SELECT * FROM ret_branch_floor WHERE floor_status = 1");
		return $data->result_array();
	}
  	
	// Counter Master
	function ajax_getcounter($branch="",$floor="",$from_date,$to_date)
    {
		$data = array();
		$sql = "SELECT * FROM ret_branch_floor_counter as c
		left join ret_branch_floor f on f.floor_id=c.floor_id
		left join branch r on r.id_branch=f.branch_id";

		if($floor!='' && $branch!='' && $from_date!='')
		{
			$sql = $sql." where r.id_branch =" .$branch. ' and c.floor_id = '.$floor. ' and (date(c.created_on) BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")';
		}
		elseif($floor!='' && ($branch!='' && $branch > 0))
		{
			$sql = $sql." where r.id_branch =" .$branch. " and c.floor_id = ".$floor;
		}
		elseif($floor!='' && $from_date!='')
		{
			$sql = $sql." where c.floor_id =" .$floor. ' and (date(c.created_on) BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")';
		}
		elseif(($branch!='' && $branch > 0) && $from_date!='')
		{ 
			$sql = $sql." where r.id_branch =" .$branch. ' and (date(c.created_on) BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")';
		}	 
		elseif($floor!='' || ($branch!='' && $branch > 0) || $from_date!= ''){   
			$sql = $sql." where ".($floor != '' ? ('c.floor_id ='.$floor) : ($branch!='' && $branch > 0 ? 'branch_id ='.$branch: ($from_date != '' ? ' date(c.created_on) BETWEEN '.date('Y-m-d',strtotime($from_date)).' AND '.date('Y-m-d',strtotime($to_date)) : ('')))) ;
		} 
		$result = $this->db->query($sql);
		$data = $result->result_array();
		return $data;
	}

	function get_counter($counter_id)
    {
		$counter_id = $this->db->query("select * from ret_branch_floor_counter where counter_id=".$counter_id);
		return $counter_id->row_array();
	}
	
	function getActiveCounters()
    {
        $id_branch=(isset($_POST['id_branch']) ? ($_POST['id_branch']!='' ?$_POST['id_branch'] :''):'');
		$data = $this->db->query("SELECT c.counter_id,c.floor_id,c.counter_name,c.counter_short_code,c.system_fp_id,c.counter_status,c.sort
        FROM ret_branch_floor_counter c 
        LEFT JOIN ret_branch_floor f ON f.floor_id=c.floor_id
        WHERE c.counter_status=1
        ".($id_branch!='' ? " and f.branch_id=".$id_branch."" :'')."");
		return $data->result_array();
	}
	
	/** 
	*	Master :: Counter -- Starts
	*/	
	function ajax_get_makingtype($from_date,$to_date)
	{
		$data = array();
		$sql = ("SELECT mak_id,mak_name,mak_short_code,mak_status,
		created_on FROM ret_making_type");
		if($from_date!='')
		{
		$sql = $sql.( ' where (date(created_on) BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")');
		}
		$result = $this->db->query($sql);
		$data = $result->result_array();
		return $data;
	}

	function get_make_type($mak_id)
	{
		$counter_id = $this->db->query("select * from ret_making_type where mak_id=".$mak_id);
		return $counter_id->row_array();
	}
	/** 
	*	Master :: Counter -- Ends
	*/
	
	/** 
	*	Master :: Theme -- Ends
	*/
	function ajax_gettheme($from_date,$to_date)
    {	
		$data = array();
		$sql  = ("SELECT id_theme,theme_name,theme_code,
		theme_desc,theme_status,created_on FROM ret_theme");
		if($from_date!='')
		{
			$sql = $sql." where".('(date(created_on)
			BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")');
		}
		$result=$this->db->query($sql);
		$data = $result->result_array();
		return $data;
			
	}
	function get_theme($id_theme)
    {
		$id_theme = $this->db->query("select * from ret_theme where id_theme=".$id_theme);
		return $id_theme->row_array();
	}
	function getActiveTheme()
   {
       $data = $this->db->query("SELECT id_theme,theme_name,theme_desc,IFNULL(theme_code,'') as code FROM `ret_theme` WHERE theme_status = 1");
       return $data->result_array();
   }
	/** 
	*	Master :: Theme -- Ends
	*/
	
	/** 
	*	Master :: Material -- Ends
	*/
	function ajax_getmaterial($from_date,$to_date)
    {	
		$data = array();
		$sql  = ("SELECT material_id,material_name,material_code,material_status,
		created_on FROM ret_material");
		if($from_date!='')
		{
			$sql = $sql." where".('(date(created_on)
			BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")');
		}
		$result=$this->db->query($sql);
		$data = $result->result_array();
		return $data;
			
	}
	function get_material($material_id)
    {
		$result = $this->db->query("select * from ret_material where material_id=".$material_id);
		return $result->row_array();
	}
	function get_material_lst()
    {
		$data = $this->db->query("SELECT Distinct m.material_id,m.material_name FROM ret_material m left join ret_material_rate mtr on m.material_id = mtr.material_id WHERE mtr.material_id is NULL and m.material_status=1");
		return $data->result_array();
	}
	
 	function ajax_getmtrrate($from_date,$to_date,$material_id)
    {
		$data = array();
		$sql  ="SELECT m.material_id,m.material_name,mtr.mat_rate_id,mtr.mat_rate,Date_Format(mtr.effective_date,'%d-%m-%Y') as effective_date,mtr.created_on FROM ret_material_rate mtr 
		left join ret_material m on m.material_id = mtr.material_id";
		if(($material_id!='' && $material_id > 0) && $from_date!='')
		{
			$sql = $sql." where status=1 and mtr.material_id =" .$material_id. ' and (date(mtr.created_on)
			BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")';
		}
		elseif(($material_id!='' && $material_id > 0 ) || $from_date!='')
		{
			$sql = $sql." where status=1".($material_id != '' && $material_id > 0  ? ('and mtr.material_id ='.$material_id) : ($from_date != '' ? ' date(mtr.created_on) BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'"' : (''))) ;
		}
		else
		{
			$sql=$sql." where status=1";
		} 
		$result=$this->db->query($sql);
		$data = $result->result_array();
		return $data;
		
	}
	
	function get_materialrate($mat_rate_id)
    {
		$mat_rate_id = $this->db->query("select material_id,mat_rate_id,mat_rate,Date_Format(effective_date,'%d-%m-%Y') as effective_date from ret_material_rate where mat_rate_id=".$mat_rate_id);
		return $mat_rate_id->row_array();
	}
	function getActiveMaterial()
   {
		$data = $this->db->query("SELECT material_id,material_name,IFNULL(material_code,'') as code FROM `ret_material` WHERE material_status = 1");
		return $data->result_array();
   }
	/** 
	*	Master :: Material -- Ends
	*/
	
	/** 
	*	Master :: Product -- Starts
	*/
	function getActiveProducts(){
		$result = $this->db->query("select pro_id,product_name as name,IFNULL(product_short_code,'') as code,weight_range_based from ret_product_master where product_status =1");
		return $result->result_array();
	}
	
	function getActiveSearchProd($SearchTxt){ // For autocomplete 
		$result = $this->db->query("select pro_id as value,if(product_short_code = '' or product_short_code is null ,product_name ,CONCAT(product_short_code,' - ',product_name) ) as label from ret_product_master where product_status=1 and (product_name like '%".$SearchTxt."%' or product_short_code like '%".$SearchTxt."%')");
		return $result->result_array();
	}
	function ajax_get_TaxProd($pro_id,$tax_group_id,$product_status,$from_date,$to_date)
    {	
		$data = array();
		$sql  = ("SELECT pr.pro_id,tx.tgrp_id,tx.tgrp_name,pr.product_name,pr.product_status,pr.created_time,pr.tax_group_id,pr.product_short_code 
		FROM ret_product_master as pr 
		left join ret_taxgroupmaster tx on tx.tgrp_id = pr.tax_group_id");
		if($pro_id!='' && $tax_group_id!='' && $from_date!='' && $product_status!='')
		{
			$sql = $sql." where tx.tgrp_id =" .$tax_group_id. ' and pr.pro_id = '.$pro_id. ' and pr.product_status='.$product_status.'(date(pr.created_time) BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")';
		}
		elseif($pro_id!='' && $tax_group_id!='' && $from_date!='')
		{
			$sql = $sql." where tx.tgrp_id =" .$tax_group_id. ' and pr.pro_id = '.$pro_id. ' and (date(pr.created_time) BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")';
		}
		elseif($pro_id!='' && $tax_group_id!='' && $product_status!='')
		{
			$sql = $sql." where tx.tgrp_id =" .$tax_group_id. ' and pr.pro_id = '.$pro_id. ' and pr.product_status='.$product_status;
		}
		elseif($pro_id!='' || ($tax_group_id!='' && $tax_group_id > 0) || $from_date!= ''){   
			$sql = $sql." where ".($pro_id != '' ? ('pr.pro_id ='.$pro_id) : ($tax_group_id!='' && $tax_group_id > 0 ? 'tgrp_id ='.$tax_group_id: ($from_date != '' ? ' date(pr.created_time) BETWEEN '.date('Y-m-d',strtotime($from_date)).' AND '.date('Y-m-d',strtotime($to_date)) : ('')))) ;
		} 
		elseif($pro_id!='' && ($tax_group_id!='' && $tax_group_id > 0))
		{
			$sql = $sql." where tx.tgrp_id =" .$tax_group_id. " and pr.pro_id = ".$pro_id;
		}
		elseif($product_status!='' && ($tax_group_id!='' && $tax_group_id > 0))
		{
			$sql = $sql." where tx.tgrp_id =" .$tax_group_id. " and pr.product_status = ".$product_status;
		}
		elseif($product_status!='' && ($pro_id!='' && $pro_id > 0))
		{
			$sql = $sql." where pr.pro_id =" .$pro_id. " and pr.product_status = ".$product_status;
		}
		elseif($pro_id!='' && $from_date!='')
		{
			$sql = $sql." where pr.pro_id =" .$pro_id. ' and (date(pr.created_time) BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")';
		}
		elseif($product_status!='' && $from_date!='')
		{
			$sql = $sql." where pr.product_status =" .$product_status. ' and (date(pr.created_time) BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")';
		}
		elseif(($tax_group_id!='' && $tax_group_id > 0) && $from_date!='')
		{ 
			$sql = $sql." where tx.tgrp_id=" .$tax_group_id. ' and (date(pr.created_time) BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")';
		}
		
		elseif($product_status!='' || ($tax_group_id!='' && $tax_group_id > 0) || $from_date!= ''){   
			$sql = $sql." where ".($product_status != '' ? ('pr.product_status ='.$product_status) : ($tax_group_id!='' && $tax_group_id > 0 ? 'tgrp_id ='.$tax_group_id: ($from_date != '' ? ' date(pr.created_time) BETWEEN '.date('Y-m-d',strtotime($from_date)).' AND '.date('Y-m-d',strtotime($to_date)) : ('')))) ;
		} 		
		elseif($pro_id!='' || $product_status!='' ||($tax_group_id!='' && $tax_group_id > 0)|| $from_date!= ''){   
			$sql = $sql." where ".($pro_id != '' ? ('pr.pro_id ='.$pro_id) : ($product_status != '' ?('pr.product_status='.$product_status): ($tax_group_id!='' && $tax_group_id > 0 ? 'tgrp_id ='.$tax_group_id: ($from_date != '' ? ' date(pr.created_time) BETWEEN '.date('Y-m-d',strtotime($from_date)).' AND '.date('Y-m-d',strtotime($to_date)) : (''))))) ;
		}
		
		//echo $sql;exit;
		$result=$this->db->query($sql);
		$data=$result->result_array();
		return $data;		
	}
	/** 
	*	Master :: Product -- Ends
	*/
	
	/** 
	*	Master :: Product -- Starts
	*/
	function getActiveSubProducts(){
		$result = $this->db->query("select sub_pro_id as value,sub_pro_name as name,IFNULL(sub_pro_code,'') as code from ret_sub_product_master where sub_pro_status=1");
		return $result->result_array();
	}
	
	function getActiveSearchSubProd($SearchTxt,$id_prod){
		if(!empty($id_prod)){
			$result = $this->db->query("select sp.sub_pro_id as value,if(sub_pro_code = '' or sub_pro_code is null ,sub_pro_name ,CONCAT(sub_pro_code,' - ',sub_pro_name) ) as label 
		from ret_sub_product_master sp
		LEFT JOIN ret_product_sub_product psp on sp.sub_pro_id = psp.sub_pro_id
		where psp.pro_id=".$id_prod." and sub_pro_status=1 and (sub_pro_name like '%".$SearchTxt."%' or sub_pro_code like '%".$SearchTxt."%')");
		}else{
			$result = $this->db->query("select sp.sub_pro_id as value,if(sub_pro_code = '' or sub_pro_code is null ,sub_pro_name ,CONCAT(sub_pro_code,' - ',sub_pro_name) ) as label 
		from ret_sub_product_master sp 
		where sub_pro_status=1 and (sub_pro_name like '%".$SearchTxt."%' or sub_pro_code like '%".$SearchTxt."%')");
		}
		
		
		
		return $result->result_array();
	}
	/** 
	*	Master :: Product -- Ends
	*/
	
	/** 
	*	Master :: Stone -- Starts
	*/
	function getActiveStone()
    {
		$data = $this->db->query("SELECT stone_id,stone_name,IFNULL(stone_code,'') as code FROM `ret_stone` WHERE stone_status = 1");
		return $data->result_array();
	}

	function ajax_get_stone($from_date,$to_date)
	{
		$data = array();
		$sql = ("SELECT s.stone_id,s.uom_id,s.stone_name,s.stone_code,s.stone_status,s.created_on,u.uom_name FROM ret_stone s
				left join ret_uom u on u.uom_id=s.uom_id");

		if($from_date!='')
		{
			$sql = $sql.( ' where (date(s.created_on) BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")');
		}
		$result = $this->db->query($sql);
		$data = $result->result_array();
		return $data;
	}

	function get_stone($stone_id)
	{
		$counter_id = $this->db->query("select * from ret_stone where stone_id=".$stone_id);
		return $counter_id->row_array();
	}
	/** 
	*	Master :: Stone -- Ends
	*/
	
	/** 
	*	Master :: UOM -- Starts
	*/
	function ajax_getUOM($from_date,$to_date)
    {	
		$data = array();
		$sql  = ("SELECT uom_id,uom_name,uom_short_code,uom_status,created_on FROM ret_uom");
		if($from_date!='')
		{
			$sql = $sql." where".('(date(created_on)
			BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")');
		}
		$result=$this->db->query($sql);
		$data = $result->result_array();
		return $data;
			
	}
	function get_uom($uom_id)
    {
		$uom_id = $this->db->query("select uom_id,uom_name,uom_short_code,uom_status from ret_uom where uom_id=".$uom_id);
		return $uom_id->row_array();
	}
	function getActiveUOM()
    {
		$data = $this->db->query("SELECT uom_id,uom_name,IFNULL(uom_short_code,'') as code,is_default FROM `ret_uom` WHERE uom_status = 1");
		return $data->result_array();
	}
	/** 
	*	Master :: UOM -- Ends
	*/
	
	/** 
	*	Master :: Category -- Starts
	*/
	function get_catByMetal($id)
	{
		$sql = $this->db->query("SELECT c.id_metal,id_ret_category,c.name as name from ret_category c 
		left join metal m on m.id_metal=c.id_metal
		where c.id_metal=".$id);
		return $sql->result_array();
	}
	function ajax_getcategory($from_date,$to_date)
    {	
		$data = array();
		$sql  = ("SELECT c.id_ret_category ,c.name,m.id_metal,c.cat_code,m.metal,c.status,c.image,c.created_on FROM ret_category as c left join metal m on m.id_metal=c.id_metal");
		if($from_date!='')
		{
			$sql = $sql." where".('(date(c.created_on)
			BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")');
		}
		$result=$this->db->query($sql);
		$data = $result->result_array();
		return $data;
			
	}
	function get_ret_category($id_category)
    {
		$id_category  = $this->db->query("select * from ret_category where id_ret_category =".$id_category);
		return $id_category->row_array();
	}
	function get_category_purity($id)
	{
	   $list_purity=[];
	   $id1=$this->db->query("select id_category,id_purity from ret_metal_cat_purity where id_category=".$id);
      for($i=0;$i<count($id1->result_array());$i++)
      {
        array_push($list_purity,
        $id1->result_array()[$i]['id_purity']);
      }
       return $list_purity;
	} 

	function getActiveCategorymtr()
    {
		$data = $this->db->query("SELECT id_ret_category,name,description,id_metal,image FROM `ret_category` WHERE status = 1");
		return $data->result_array();
	}
	function getCatPurity($id_cat)
    {
		$data = $this->db->query("SELECT cp.id_purity,purity FROM `ret_metal_cat_purity` cp
			LEFT JOIN ret_purity p on p.id_purity = cp.id_purity
		 WHERE id_category = ".$id_cat);
		return $data->result_array();
	}
	/** 
	*	Master :: Category -- Ends
	*/
	
	/** 
	*	Master :: Karigar -- Starts
	*/
	
	function getActiveKarigar()
	{
		$data = $this->db->query("SELECT id_karigar,CONCAT(firstname,' ',IFNULL(lastname,'')) as karigar,IFNULL(code_karigar,'') as code FROM `ret_karigar` WHERE status_karigar = 1");
		return $data->result_array();
	}
	function ajax_getkarigar($from_date,$to_date)
	{
		$data = array();
			$sql = ("SELECT id_karigar,firstname,urname,contactno1,status_karigar,createdon FROM ret_karigar");

			if($from_date!='')
			{
			$sql = $sql.( ' where (date(createdon) BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")');
			}
		$result = $this->db->query($sql);
		$data = $result->result_array();
		return $data;
	}
	function get_karigar($id_karigar)
	{
		$id = $this->db->query("select * from ret_karigar where id_karigar=".$id_karigar);
		return $id->row_array();
	}

	function get_karigar_wastages($id_karigar)
	{
		$id = $this->db->query("select * from ret_karikar_items_wastage where id_karikar=".$id_karigar." ORDER BY id_karikar_wast ASC");
		return $id->result_array();
	}

	public function mobile_available($mobile)
	{
		$this->db->select('contactno1');
		$this->db->where('contactno1', $mobile);
		$status=$this->db->get('ret_karigar');
		if($status->num_rows()>0)
		{
			$return_data=array('status'=>false,'message'=>'Mobile Number Already Exists');
		}else{
			$return_data=array('status'=>true,'message'=>'');
		}
		return $return_data;
	}	


	public function email_available($email)
	{
		$this->db->select('email');
		$this->db->where('email', $email);
		$status=$this->db->get('ret_karigar');
		if($status->num_rows()>0)
		{
			$return_data=array('status'=>false,'message'=>'Email Already Exists');
		}else{
			$return_data=array('status'=>true,'message'=>'');
		}
		return $return_data;
	}	
	/** 
	*	Master :: Karigar -- Ends
	*/
	
	/** 
	*	Master :: Tag Type -- Starts
	*/ 
	function ajax_gettag($from_date,$to_date)
    {	
		$data = array();
		$sql  = ("SELECT tag_id,tag_name,tag_status,created_time FROM ret_tag_type_master");
		if($from_date!='')
		{
			$sql = $sql." where".('(date(created_time)
			BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")');
		}
		$result=$this->db->query($sql);
		$data = $result->result_array();
		return $data;
			
	}
	function get_tag($tag_id)
    {
		$tag_id = $this->db->query("select tag_id,tag_name,tag_status from ret_tag_type_master where tag_id=".$tag_id);
		return $tag_id->row_array();
	}
	function getActivetag()
    {
		$data = $this->db->query("SELECT tag_id,tag_name FROM `ret_tag_type_master` WHERE tag_status = 1");
		return $data->result_array();
	}
	/** 
	*	Master :: Tag Type -- Ends
	*/ 
	
	/** 
	*	Master :: Design -- Starts
	*/ 
	function getSearchDesign($SearchTxt,$id_prod)
    {
		$result = $this->db->query("SELECT design_no as value,if(design_code = '' or design_code is null ,design_name ,CONCAT(design_code,' - ',design_name) ) as label from ret_design_master where design_status=1 and product_id=".$id_prod." and (design_name like '%".$SearchTxt."%' or design_code like '%".$SearchTxt."%')");
		return $result->result_array();
	}
	function genDesignShortCode()
	{
		$sql = $this->db->query("SELECT max(bill_no) as lastBill_no FROM ret_billing");
		$lastCode = $sql->row()->design_code;
		if($sql->num_rows() == 0){
			return 1;
		}else{
			return $lastCode+1;
		}
	}
	function get_empty_design()
	{
		$data=array(
						'product_id'           => NULL,
						'design_id'            => NULL,
						'design_no'            => NULL,
		                'default_img'          => NULL,
						'design_status'        => 1,
						'image'                => NULL,
						'id_image'             => NULL,
						'design_code'		   => NULL,
						'design_name'		   => NULL,
						'theme'		           => NULL,
						'hook_type'		       => NULL,
						'mc_cal_type'          => 1,
						'mc_cal_value'         => NULL,
						'wastage_type'         => 1,
						'wastag_value'         => NULL,
						'screw_type'		   => NULL,
						'design_for'		   => 2,
						'min_length'		   => NULL,
						'max_length'		   => NULL,
						'min_width'            => NULL,
						'max_width'            => NULL,
						'min_dia'		       => NULL,
						'max_dia'		       => NULL,
						'min_weight'		   => NULL,
						'max_weight'		   => NULL,
						'fixed_rate'		   => NULL,
						'usage_type'           => 1,
						'created_time'         => NULL,
						'id_size'               => NULL,
						'create_by'            => $this->session->userdata('uid'));
		return $data;
	}
	function getkarigar()
	{
		$data=array(
		'karigar_id'           => NULL );
		return $data;
	}
		function getpurity()
	{
		$data=array(
		 'pur_id'               => NULL );
		return $data;
	}
		function getmaterial()
	{
		$data=array(
		'material_id'          => NULL );
		return $data;
	}
		function getstone()
	{
		$data=array(
		'stone_id'           => NULL );
		return $data;
	}
		function getsize()
	{
		$data=array(
		'uom_id'                 => NULL );
		return $data;
	}
	function ajax_get_design($from_date,$to_date,$id_product)
	{
		$data = array();
			$sql = "SELECT design_no,design_code,design_name,d.created_time,design_status
			        from ret_design_master d
			        where d.design_no IS NOT NULL";
		//print_r($sql);exit;
		$result = $this->db->query($sql);
		$data = $result->result_array();
		return $data;
	}
	
	
	function ajax_get_retmaster(){
	    $returndata = array();
	    $catsql         = $this->db->query("SELECT id_ret_category, id_metal, name, hsn_code, cat_code from ret_category");
	    $prosql         = $this->db->query("SELECT pro_id, cat_id, stock_type, sales_mode, product_short_code, product_name  FROM `ret_product_master`");
	    $designsql      = $this->db->query("SELECT design_no, design_code, design_name, product_id, mc_cal_type, ifnull(mc_cal_value,0) as mc_cal_value, ifnull(wastag_value,0) as wastag_value from ret_design_master");
	    $catresult      = $catsql->result_array();
	    $proresult      = $prosql->result_array();
	    $designresult   = $designsql->result_array();
	    
	    foreach($catresult as $ckey=>$catrow){
	        $cat_pro_design = $catrow;
	        $product_row = array();
	        foreach($proresult as $prorow){
	            $pro_des_row = array();
	            if($prorow['cat_id'] == $catrow['id_ret_category']){
	                $pro_des_row = $prorow;
	                foreach($designresult as $desrow){
	                    if($desrow['product_id'] == $prorow['pro_id']){
	                        $pro_des_row['design'][] = $desrow;
	                    }
	                }
	            }
	            
	            if(!empty($pro_des_row)){
	                $product_row[] = $pro_des_row;
	            }
	        }
	        $catresult[$ckey]['prodata'] = $product_row; 
	    }
	    return $catresult;
	}
	function get_designimage($id)
    {
		$product = $this->db->query("select id_design, image,is_default,id_image from ret_design_images where id_design=".$id);
		return $product->result_array('image');
	}
	function get_ret_design($id)
	{
			$id = $this->db->query("select * from ret_design_master where design_no=".$id);
		    return $id->row_array();
	}
	function get_design_karigar($id)
	{
		$list_karigar=[];
        $this->db->select('karigar_id');
        $this->db->where('design_id',$id);  
        $result=$this->db->get('ret_design_karigars');
      for($i=0;$i<count($result->result_array());$i++)
      {
        array_push($list_karigar,
        $result->result_array()[$i]['karigar_id']);
      }
       return $list_karigar;
	}
	function get_design_material($id)
	{
		$list_material=[];
        $this->db->select('material_id');
        $this->db->where('design_id',$id);  
        $result=$this->db->get('ret_design_other_materials');
      for($i=0;$i<count($result->result_array());$i++)
      {
        array_push($list_material,
        $result->result_array()[$i]['material_id']);
      }
       return $list_material;
	}
	function get_design_purity($id)
	{
	   $list_purity=[];
        $this->db->select('pur_id');
        $this->db->where('design_id',$id);  
        $result=$this->db->get('ret_design_purity');
      for($i=0;$i<count($result->result_array());$i++)
      {
        array_push($list_purity,
        $result->result_array()[$i]['pur_id']);
      }
       return $list_purity;
	}
	function get_design_size($id)
	{
		$id = $this->db->query("select * from ret_design_sizes where design_id=".$id);
		return $id->row_array();
	}
	function get_productData($id)
	{
		$sql="SELECT pro_id,has_screw,has_hook,has_stone,has_size from ret_product_master where pro_id=".$id;
   	    $result = $this->db->query($sql);
		return $result->row_array();
	}
	function delete_designimage($file)
    {
		$this->db->where('image',$file);
        $status= $this->db->delete('ret_design_images');
		return $status;
	}
	
	/** 
	*	Master :: Design -- Ends
	*/
	
	/** 
	*	Master :: Tax -- Starts
	*/ 
	function ajax_gettax($from_date,$to_date)
    {	
		$data = array();
		$sql  = ("SELECT tax_id,tax_name,tax_code,tax_percentage,branch_code,tax_status,created_on FROM ret_taxmaster");
		if($from_date!='')
		{
			$sql = $sql." where".('(date(created_on)
			BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")');
		}
		$result=$this->db->query($sql);
		$data = $result->result_array();
		return $data;
			
	}
	function get_tax($tax_id)
    {
		$tax_id = $this->db->query("select tax_name,tax_code,tax_percentage,tax_status from ret_taxmaster where tax_id=".$tax_id);
		return $tax_id->row_array();
	}
	function getActivetax()
    {
		$data = $this->db->query("SELECT tax_id,tax_name,tax_code,tax_percentage FROM `ret_taxmaster` WHERE tax_status = 1");
		return $data->result_array();
	}
	function get_empty_tgrp()
	{
		$data=array(
			'tgrp_id'			    => NULL,
			'tgrp_name'			    => NULL,
			'tgrp_status'			=> 1,

		);
		return $data;
	}

 	function ajax_gettgrp($from_date,$to_date)
    {	
		$data = array();
		$sql  = ("SELECT tgrp_id,tgrp_name,tgrp_status,created_time FROM ret_taxgroupmaster");
		if($from_date!='')
		{
			$sql = $sql." where".('(date(created_time)
			BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")');
		}
		$result=$this->db->query($sql);
		$data=$result->result_array();
		return $data;
			
	}
	function get_tgrp($tgrp_id)
    {
		$tgrp_id = $this->db->query("select * from ret_taxgroupmaster where tgrp_id=".$tgrp_id);
		return $tgrp_id->row_array();
	}
	function getActivetgrp()
    {
		$data = $this->db->query("SELECT * FROM `ret_taxgroupmaster`");
		return $data->result_array();
	}
	function get_empty_tgi()
	{
	 $data=array(
			'tgi_sno'			=> NULL,
			'tgi_tgrpcode'	    => NULL,
			'tgi_taxcode'	    => NULL,
			'tgi_calculation'   => NULL,
			'tgi_type'          => 1,
		);
		return $data;
	}
	function get_tgi($tgi_sno)
    {
		$tgi_sno = $this->db->query("SELECT * FROM `ret_taxgroupmaster` WHERE tgi_sno =".$tgi_sno);
		return $tgi_sno->result_array();
	}
	
	/** 
	*	Master :: Tax -- Ends
	*/ 
	
	/** 
	*	Master :: Product -- Starts
	*/ 
	function ajax_get_retProduct($from_date,$to_date)
	{
		$data = array();
			$sql = ("SELECT p.pro_id,p.cat_id,p.product_name,p.product_short_code,c.name,p.created_time,p.product_status,p.image as image,r.section_name
			FROM ret_product_master p
			left join ret_category c on c.id_ret_category=p.cat_id
			left join ret_section r on p.id_section=r.id_section");
             //print_r($sql);exit;
			if($from_date!='')
			{
			$sql = $sql.( ' where (date(p.created_time) BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")');
			}
		$result = $this->db->query($sql);
		$data = $result->result_array();
		return $data;
	} 
	function genProdShortCode()
	{
		$sql = $this->db->query("SELECT max(product_short_code) as product_short_code  from ret_product_master");
		$lastCode = $sql->row()->product_short_code;
		if($sql->num_rows() == 0){
			return 1;
		}else{
			return $lastCode+1;
		}
	}
	
	function get_ret_product($id_product)
	{
		$id = $this->db->query("select * from ret_product_master where pro_id=".$id_product);
		return $id->row_array();
	}
	function getActiveProduct(){
	    
		$result = $this->db->query("select * from ret_product_master where product_status=1 ".($_POST['id_category']!='' ? " and cat_id=".$_POST['id_category']."" :'')."");
		return $result->result_array();
	}
	public function getProd_empty_record()
    {
		$data=array(
		'pro_id'           		=> NULL,
		'cat_id'           		=> NULL,
		'id_section'           	=> NULL,
		'hsn_code'             	=> NULL,
		'stock_type'           => 1,
		'sales_mode'   		   => 2,
		'wastage_type'   	   => 2,
		'min_wastage'			=> NULL,
		'max_wastage'			=> NULL,
		'other_materials'      => 0,
		'has_stone'		       => 0,
		'has_hook'		       => 0,
		'has_screw'		       => 0,
		'has_fixed_price'	   => 0,
		'metal_type'		   => NULL,
		'product_short_code'   => NULL,
		'product_name'		   => NULL,
		'has_size'		       => 0,
		'less_stone_wt'		   => 0,
		'tag_split'		       => 0,
		'tag_merge'		       => 0,
		'tag_type'		       => 0,
		'other_charges'		   => 0,
		'net_wt'		       => 0,
		'stock_report'		   => 0,
		'central_exces_duty'   => 0,
		'no_of_pieces'		   => NULL,
		'rfid_required'		   => 0,
		'rfid_in_stock'		   => 0,
		'hallmark'		       => 0,
		'counter'		       => 1,
		'stone_board_rate_cal' => 0,
		'calculation_based_on' => 2,
		'sales_markup'		   => 0,
		'max_markup_per_for_rateitems'  => NULL,
		'no_of_tags_to_print'   => NULL,
		'tax_group_id'       	=>NULL,
		'product_status'       	=> 1,
		'created_time'          => NULL,
		'create_by'            	=> $this->session->userdata('uid'),
		'image'                 =>NULL);
		 
		return $data;
	}

	/** 
	*	Master :: Product -- Ends
	*/ 
	
	/** 
	*	Master :: Sub Product -- Starts
	*/ 
	
	function ajax_get_retSubProduct($from_date,$to_date)
	{
		$data = array();
			$sql = ("SELECT s.sub_pro_id,s.sub_pro_name,s.sub_pro_code,s.sub_pro_status,s.created_time 
			from ret_sub_product_master s");

			if($from_date!='')
			{
			$sql = $sql.( ' where (date(s.created_time) BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")');
			}
		$result = $this->db->query($sql);
		$data = $result->result_array();
		return $data;
	}
	function get_ret_subproduct($id)
	{
		$id = $this->db->query("select * from ret_sub_product_master where sub_pro_id=".$id);
		return $id->row_array();
	}
	/** 
	*	Master :: Sub Product -- Ends
	*/ 
	
	/** 
	*	Master :: Metal -- Starts
	*/ 
	function ajax_getmetal($from_date,$to_date)
    {	
		$data = array();
		$sql  = ("SELECT m.id_metal,m.metal,m.tgrp_id,tgrp_name,m.metal_code,m.metal_status,m.created_on FROM metal m
					left join ret_taxgroupmaster tg on tg.tgrp_id = m.tgrp_id");
		if($from_date!='')
		{
			$sql = $sql." where".('(date(m.created_on)
			BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")');
		}
		$result=$this->db->query($sql);
		$data = $result->result_array();
		return $data;
			
	}
	function get_metals($id_metal)
    {
		$id_metal = $this->db->query("select id_metal,tgrp_id,metal,metal_code,metal_status from metal where id_metal=".$id_metal);
		return $id_metal->row_array();
	}
	
	function getActivemetal()
    {
		$data = $this->db->query("SELECT id_metal,metal,IFNULL(metal_code,'') as code FROM `metal` WHERE metal_status = 1");
		return $data->result_array();
	}
	/** 
	*	Master :: Metal -- Ends
	*/
	
	
	
	/** 
	*	Master :: Screw -- Starts
	*/
	function ajax_getscrew($from_date,$to_date)
    {	
		$data = array();
		$sql  = ("SELECT screw_id,screw_name,screw_short_code,screw_status,created_on FROM ret_screw_type");
		if($from_date!='')
		{
			$sql = $sql." where".('(date(created_on)
			BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")');
		}
		$result=$this->db->query($sql);
		$data = $result->result_array();
		return $data;
			
	}
	function get_screw($screw_id)
    {
		$screw_id = $this->db->query("select screw_id,screw_name,screw_short_code,screw_status from ret_screw_type where screw_id=".$screw_id);
		return $screw_id->row_array();
	}
	function getActivescrew()
    {
		$data = $this->db->query("SELECT screw_id,screw_name,IFNULL(screw_short_code,'') as code FROM `ret_screw_type` WHERE screw_status = 1");
		return $data->result_array();
	}
	/** 
	*	Master :: Screw -- Ends
	*/
	
	/** 
	*	Master :: Hook -- Starts
	*/
	function ajax_gethook($from_date,$to_date)
    {	
		$data = array();
		$sql  = ("SELECT hook_id,hook_name,hook_short_code,hook_status,created_on FROM ret_hook_type");
		if($from_date!='')
		{
			$sql = $sql." where".('(date(created_on)
			BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")');
		}
		$result=$this->db->query($sql);
		$data = $result->result_array();
		return $data;
			
	}
	function get_hook($hook_id)
    {
		$hook_id = $this->db->query("select hook_id,hook_name,hook_short_code,hook_status from ret_hook_type where hook_id=".$hook_id);
		return $hook_id->row_array();
	}
	function getActivehook()
    {
		$data = $this->db->query("SELECT hook_id,hook_name,IFNULL(hook_short_code,'') as code FROM `ret_hook_type` WHERE hook_status = 1");
		return $data->result_array();
	}
	/** 
	*	Master :: Hook -- Ends
	*/
	
	/** 
	*	Master :: Financial Year -- Starts
	*/
	function fincnce_empty_record()

    {

	

		$emptyquery = $this->db->field_data('ret_financial_year');

		$emptydata = array();

		foreach ($emptyquery as $field)

		{

			$emptydata[$field->name] = $field->default;

		}

		$emptydata['tag_datetime'] = date('d-m-Y');

		$emptydata['design_code']  = '';

		return $emptydata;

	}



	function ajax_get_financial_year_List()
    {
        $sql = $this->db->query(" SELECT f.fin_id,f.fin_year_name as fin_code,f.fin_year_code,CONCAT(date_format(f.fin_year_from,'%Y-%m-%d'),'  / ',date_format(f.fin_year_to,'%Y-%m-%d')) as fin_year,f.fin_status,f.fin_year_code,date_format(f.created_on,'%Y-%m-%d')as created_on
        FROM ret_financial_year f
        ORDER BY f.fin_id desc");
        return $sql->result_array();
	}

    function get_financialyear_by_status()
    {
        $sql = $this->db->query(" SELECT f.fin_id,f.fin_year_name as fin_code,f.fin_year_code,date_format(f.fin_year_from,'%Y-%m-%d')as fin_year_from,date_format(f.fin_year_to,'%Y-%m-%d')as fin_year_to,f.fin_status,f.fin_year_code,date_format(f.created_on,'%Y-%m-%d')as created_on
        FROM ret_financial_year f
        where f.fin_status=1");
        return $sql->row_array();
    }

    function get_finance_entry_records($id)
    {
        $sql = $this->db->query("SELECT f.fin_id,f.fin_year_name as fin_code,date_format(f.fin_year_from,'%Y-%m-%d')as fin_year_from,date_format(f.fin_year_to,'%Y-%m-%d')as fin_year_to,f.fin_status,f.fin_year_code
        FROM ret_financial_year as f 
        WHERE fin_id='".$id."'");
        return $sql->result_array()[0];
    }

	public function update_financialData($data,$id,$id_field,$table)
    {    
	    $edit_flag = 0;
	    $this->db->where($id_field,$id);
		$edit_flag = $this->db->update($table,$data);
		return ($edit_flag==1?$id:0);
	}

	public function update_financialstatus($insId)
    {    
	   $status = $this->db->query("UPDATE ret_financial_year SET fin_status='0' WHERE fin_id<".$insId);
	   return $status;
	}
    
    public function setFinancialYearStatus()
    {    
	   $status = $this->db->query("UPDATE ret_financial_year SET fin_status='0' ");
	   return $status;
	}



	/** 
	*	Master :: Financial Year -- Ends
	*/

	//Old Metal Rate
	public function get_old_metal_rate($id_metal,$id_branch)
	{
			$data=$this->db->query("select m.metal,o.id_old_metal_rate,m.metal,m.metal_code,o.id_metal,o.rate,date_format(o.created_on,'%d-%m-%Y')as created_on,p.purity,o.id_purity
								   from ret_old_metal_rate o
								   left join metal m on m.id_metal=o.id_metal
								   left join ret_purity p on p.id_purity=o.id_purity
								  where o.status=1 ".($id_metal!='' ? " and o.id_metal=".$id_metal."" :'')."
								  ".($id_branch!='' ? " and o.id_branch=".$id_branch."":'')." order by o.id_old_metal_rate desc");
			// /print_r($this->db->last_query());exit;
			return $data->result_array();
	}	
	//Old Metal Rate
	
	function weight_details($id_product,$id_design)
	{
	    $weights=$this->db->query("SELECT w.value,w.id_weight,w.from_weight,w.to_weight,w.id_product,p.product_name,
	                w.id_design,concat(w.value,u.uom_name) as weight_range
                	 from ret_weight w
                	 LEFT join ret_product_master p on p.pro_id=w.id_product
                	 LEFT JOIN ret_uom u on u.uom_id=w.id_uom
                	 where w.id_product=".$id_product." ".($id_design!='' ? " and w.id_design=".$id_design."" :'')." ");
                	 //print_r($this->db->last_query());exit;
   	    return $weights->result_array(); 
	}
	
  function get_weight($id)
   {
        $weights=$this->db->query("SELECT w.id_uom,concat(w.value,u.uom_name) as value,w.id_weight,w.from_weight,w.to_weight,w.id_product,IFNULL(w.id_design,'') as id_design,p.product_name,IFNULL(d.design_name,'-') as design_name,w.value as weight_value
        from ret_weight w
        LEFT join ret_product_master p on p.pro_id=w.id_product
        LEFT join ret_design_master d on d.design_no=w.id_design
        LEFT JOIN ret_uom u on u.uom_id=w.id_uom
        where w.id_weight=".$id."");
   	    return $weights->row_array();
   }
   function ajax_get_weights($id_product,$id_design)
   {
     $wt_based_on=$this->get_product_details($id_product);
     if($wt_based_on==1)
     {
          $weights=$this->db->query("SELECT concat(w.value,u.uom_name) as value,w.id_weight,w.from_weight,w.to_weight,w.id_product,p.product_name,IFNULL(d.design_name,'-') as design_name
                	 from ret_weight w
                	 LEFT join ret_product_master p on p.pro_id=w.id_product
                	 LEFT join ret_design_master d on d.design_no=w.id_design
                	 LEFT JOIN ret_uom u on u.uom_id=w.id_uom
                	 where w.id_weight is not null ".($id_product!='' ? " and w.id_product=".$id_product."" :'')."
                	 ".($id_design!='' ? " and w.id_design=".$id_design."" :'')."
                	 ");
     }else{
          $weights=$this->db->query("SELECT concat(w.value,u.uom_name) as value,w.id_weight,w.from_weight,w.to_weight,w.id_product,p.product_name,IFNULL(d.design_name,'-') as design_name
                	 from ret_weight w
                	 LEFT join ret_product_master p on p.pro_id=w.id_product
                	 LEFT join ret_design_master d on d.design_no=w.id_design
                	 LEFT JOIN ret_uom u on u.uom_id=w.id_uom
                	 where w.id_weight is not null ".($id_product!='' ? " and w.id_product=".$id_product."" :'')."
                	 ");
     }
	
    //print_r($this->db->last_query());exit;
   	 return $weights->result_array();   	  
   }  
   
   function get_product_details($id_product)
   {
       $sql = "SELECT p.weight_range_based from ret_product_master p where p.product_status=1 ".($id_product!='' ? " and p.pro_id=".$id_product."" :'')." ";
		return $this->db->query($sql)->row()->weight_range_based;	
   }
   
   function ajax_getreorder_settings($id,$id_product,$id_design,$id_wt_range)
   {
   		$data=$this->db->query("SELECT s.id_reorder_settings,s.id_branch,s.id_product,s.id_design,concat(ret_s.value,' ',ret_s.name) as size,s.id_wt_range as wt_range,s.min_pcs,s.max_pcs,b.name as branch_name,
   		d.design_name,p.product_name,concat(w.value,m.uom_name) as wt_name,ret_s.id_size
   			from ret_reorder_settings s
   			LEFT join branch b on b.id_branch=s.id_branch
   			LEFT join ret_product_master p on p.pro_id=s.id_product
   			LEFT join ret_design_master d on d.design_no=s.id_design
   			LEFT join ret_weight w on w.id_weight=s.id_wt_range
   			LEFT JOIN ret_size ret_s on ret_s.id_size=s.size
   			LEFT JOIN ret_uom m on m.uom_id=w.id_uom
   			where s.id_reorder_settings is not null ".($id!='' ? " and s.id_reorder_settings=".$id."" :'')."
   			".($id_product!='' ? " and s.id_product=".$id_product."" :'')." 
   			".($id_design!='' ? " and s.id_design=".$id_design."" :'')." 
   			".($id_wt_range!='' ? " and s.id_wt_range=".$id_wt_range."" :'')." 
   			ORDER by s.id_branch");
   		//print_r($this->db->last_query());exit;
   		if($id!='')
   		{
   			return $data->row_array();
   		}else
   		{
   			return $data->result_array();
   		}
   }
   
   
   /*Delivery functions here */ 	
  
   	 function ajax_getDelivery()
    {
		$id_sale_delivery = $this->db->query("SELECT * FROM ret_sale_delivery ORDER BY id_sale_delivery desc");
		return $id_sale_delivery->result_array();
	}

	 function get_delivery($id_sale_delivery)
    {
		$id_sale_delivery = $this->db->query("select * from ret_sale_delivery where id_sale_delivery=".$id_sale_delivery);
		return $id_sale_delivery->row_array();
	}
	
    public function insert_delivery($data)
    {
    	$insert_flag = 0;
		$insert_flag = $this->db->insert('ret_sale_delivery',$data);
		return ($insert_flag == 1 ? $this->db->insert_id(): 0);
	}
	
	public function update_delivery($data,$id)
    {    	
    	$edit_flag = 0;
    	$this->db->where('id_sale_delivery',$id); 
		$edit_flag = $this->db->update('ret_sale_delivery',$data);
		return ($edit_flag==1?$id:0);
	}
	
	public function update_location()
	{
	    $sql="update ret_sale_delivery set is_default=0";
	    $data=$this->db->query($sql);
	    return true;
	}
			 
	public function delete_delivery($id)
    {
        $this->db->where('id_sale_delivery', $id);
        $status= $this->db->delete('ret_sale_delivery'); 
		return $status;
	} 
	/*End of delivery functions*/
	
	/*Delivery functions here */ 	
  
   	 function get_Activesize($id_product)
   	 {
   	  	$weights=$this->db->query("SELECT concat(s.value,' ',s.name) as name,id_size from ret_size s where active=1".($id_product!='' ? " and id_product=".$id_product."" :'')." ");
       	 return $weights->result_array();
   	 }

    
    
   	 /*Size functions here */ 	
  
   	 function ajax_getsize()
    {
		$size = $this->db->query("SELECT s.id_size,s.name,s.value,s.active,s.id_product,p.product_name
		FROM ret_size s
		LEFT JOIN ret_product_master p on p.pro_id=s.id_product
		ORDER BY id_size desc");
		//print_r($this->db->last_query());exit;
		return $size->result_array();
	}

	 function get_size($id_size)
    {
		$id_size = $this->db->query("select * from ret_size where id_size=".$id_size);
		return $id_size->row_array();
	}
	
    public function insert_size($data)
    {
    	$insert_flag = 0;
		$insert_flag = $this->db->insert('ret_size',$data);
		return ($insert_flag == 1 ? $this->db->insert_id(): 0);
	}
	
	public function update_size($data,$id)
    {    	
    	$edit_flag = 0;
    	$this->db->where('id_size',$id); 
		$edit_flag = $this->db->update('ret_size',$data);
		return ($edit_flag==1?$id:0);
	}
	
		 
	public function delete_size($id)
    {
        $this->db->where('id_size', $id);
        $status= $this->db->delete('ret_size'); 
		return $status;
	} 


	/*End of size functions*/

        //Old Metal Type 
   
	function ajax_getOldMetalType()
	{
		$sql=$this->db->query("SELECT m.metal,t.id_metal_type,t.metal_type
		from ret_old_metal_type t
		LEFT JOIN metal m on m.id_metal=t.id_metal");
		return $sql->result_array();
		
	}
	
	function get_old_metal_type($id_metal_type)
	{
	    $sql=$this->db->query("SELECT * from ret_old_metal_type where id_metal_type=".$id_metal_type);
	    return $sql->row_array();
	}

    //Old Metal Type 


	//Old Metal Category 

	function ajax_getOldMetalCategory()
	{
		$sql=$this->db->query("SELECT mc.id_old_metal_cat, mc.id_metal, mc.id_old_metal_type, mc.old_metal_cat, mc.old_metal_perc, t.id_metal_type,t.metal_type, m.metal
		FROM ret_old_metal_category as mc 
		LEFT JOIN ret_old_metal_type t on t.id_metal_type = mc.id_old_metal_type
		LEFT JOIN metal m on m.id_metal=t.id_metal");
		return $sql->result_array();
	}

	function get_old_metal_category($id_old_metal_cat)
	{
		$sql=$this->db->query("SELECT * from ret_old_metal_category where id_old_metal_cat =".$id_old_metal_cat);
		return $sql->row_array();
	}


	//Old Metal Category 
    
    //OLd Metal Rate
    public function ajax_GetOldMetalRate()
	{
        $data=$this->db->query("select m.metal,o.id_old_metal_rate,m.metal,m.metal_code,o.id_metal,o.rate,date_format(o.created_on,'%d-%m-%Y')as date_add,
        e.firstname as emp_created,if(o.status=1,'Active','Inactive') as status
        from ret_old_metal_rate o
        left join metal m on m.id_metal=o.id_metal
        left join employee e on e.id_employee=o.created_by
        order by o.id_old_metal_rate desc");
			return $data->result_array();
	}
	
	function getOldMetalRate($id_old_metal_rate)
	{
	    $sql=$this->db->query("SELECT * from ret_old_metal_rate where id_old_metal_rate=".$id_old_metal_rate."");
	    return $sql->row_array();
	}
    //OLd Metal Rate
    
    
    
    //Section Master
    
       	 /*Size functions here */ 	
  
   	function ajax_getSection()
    {
		$size = $this->db->query("SELECT * FROM `ret_section` ORDER by id_section DESC");
		return $size->result_array();
	}

	 function get_sections($id_section)
    {
		$id_size = $this->db->query("SELECT * FROM `ret_section` where id_section=".$id_section);
		return $id_size->row_array();
	}
	
 

	/*End of size functions*/
	
    //Section Master
    
    
    //Section Master
    function get_section()
    {
        $sql=$this->db->query("select id_section,section_name FROM ret_section where STATUS=1");
        return $sql->result_array();
    }
    
    //Section Master
    
     //feedback master
	function ajax_getFeedback()
	{
		$feedback=$this->db->query("SELECT * FROM `customer_feedback_master` ORDER by id_feedback DESC");
		return $feedback->result_array();
	}

	function get_feedback($id_feedback)
	{
		$id=$this->db->query("SELECT * FROM `customer_feedback_master` where id_feedback=".$id_feedback);
		
		return $id->row_array();
	}
	 //feedback master
	
	function check_transaction($tableName, $fieldName, $fieldValue) 
	{
		$tableName = trim($tableName);
		$fieldName = trim($fieldName);
		$fieldValue = trim($fieldValue);
		if($tableName != "" && $fieldName != "" && $fieldValue != "")
		{
			$sql = $this->db->query("SELECT * FROM ".$tableName." WHERE ".$fieldName." = '".$fieldValue."'");
			if($sql->num_rows() > 0){
				return 0;
			}else{
				return 1;
			}
		}
		else
		{
			return 0;
		}
	}

	// Charges Functions :: STARTS
	public function get_charges_list()
    {
    	$sql="SELECT id_charge, code_charge, name_charge, description_charge, value_charge, created_on, created_by from ret_charges";
		$users=$this->db->query($sql);
		return $users->result_array();
	}
	public function get_charges_list_date($from_date, $to_date)
    {
		      date_default_timezone_set('Asia/Kolkata');
			  $first='00:00:00';
			  $last='23:59:59';
    	      $sql="SELECT id_charge, code_charge, name_charge, description_charge, value_charge, created_on, created_by from ret_charges
		      where
		      created_on BETWEEN '".date('Y-m-d H:i:s',strtotime($to_date.$first))."' AND '".date('Y-m-d H:i:s',strtotime($from_date.$last))."'";
			  $users=$this->db->query($sql);
			  return $users->result_array();
	}
	public function get_charges_list_edit($id_charge)
    {
    	$sql="SELECT id_charge, code_charge, name_charge, description_charge, value_charge, tag_display, created_on, created_by from ret_charges where id_charge = $id_charge";
			   	 $users=$this->db->query($sql);
				 return $users->result_array();
	}
	// Charges Functions :: ENDS
	
	
	 //ret sub design master
	public function get_empty_subproduct()
    {
		$data=array(
		'id_sub_design'=> NULL,
		'sub_design_name'=> NULL,
		'status'=> 1,
	    );
		 
		return $data;
	}
	
	  function get_sub_design($id_sub_design)
    {
		$id_sub_design = $this->db->query("SELECT * FROM `ret_sub_design_master` where id_sub_design=".$id_sub_design);
		return $id_sub_design->row_array();
	}
	
	function ajax_getSubDesign($data)
    {
		$sql = $this->db->query("SELECT d.sub_design_name,d.sub_design_code,d.status,d.id_sub_design
        FROM ret_sub_design_master d 
        WHERE d.id_sub_design is NOT NULL
        ");
       // print_r($this->db->last_query());exit;
		return $sql->result_array();
	}
	
	function ret_sub_design_mapping_images($id_sub_design_mapping)
	{
	    $sql=$this->db->query("SELECT * FROM `ret_sub_design_mapping_images` WHERE id_sub_design_mapping=".$id_sub_design_mapping."");
	    return $sql->result_array();
	}
	
	function get_karigar_mapping_products($id_sub_design_mapping)
	{
	    $sql=$this->db->query("SELECT k.firstname as karigar_name,IFNULL(k.contactno1,'') as mobile,IFNULL(k.code_karigar,'') as code
        FROM ret_karigar_products m 
        LEFT JOIN ret_karigar k ON k.id_karigar=m.id_karigar
        WHERE m.id_sub_design_mapping=".$id_sub_design_mapping."");
        //print_r($this->db->last_query());exit;
        return $sql->result_array();
	}
	
    function get_sub_design_mapping($id_sub_design_mapping)
	{
	    $sql=$this->db->query("SELECT * FROM `ret_sub_design_mapping` WHERE id_sub_design_mapping=".$id_sub_design_mapping."");
	    return $sql->row_array();
	}
	
	
	function get_karigar_products($id_sub_design_mapping)
	{
	   $karigar_list=[];
	   $id1=$this->db->query("select id_karigar from ret_karigar_products where id_sub_design_mapping=".$id_sub_design_mapping);
      for($i=0;$i<count($id1->result_array());$i++)
      {
        array_push($karigar_list,
        $id1->result_array()[$i]['id_karigar']);
      }
       return $karigar_list;
	}
	

	
	function genSubDesignShortCode()
	{
		$sql = $this->db->query("SELECT max(sub_design_code) as sub_design_code  from ret_sub_design_master");
		$lastCode = $sql->row()->sub_design_code;
		if($sql->num_rows() == 0){
			return 1;
		}else{
			return $lastCode+1;
		}
	}
	  
	 function getActiveSubDesigns()
	 {
	       $data = $this->db->query("SELECT id_sub_design,sub_design_name FROM `ret_sub_design_master` WHERE status = 1");
		   return $data->result_array();
	 }
	 
	 function get_ActiveDesign()
	 {
	     $sql=$this->db->query("SELECT * FROM ret_design_master WHERE design_status=1");
	     return $sql->result_array();
	 }
	 
	 
	function check_subdesign_maping($id_sub_design,$design_no)
	{
		$sql=$this->db->query("SELECT * FROM `ret_sub_design_mapping` WHERE design_no=".$design_no." AND id_sub_design=".$id_sub_design."");
		if($sql->num_rows() == 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	
	function check_design_products_maping($id_product,$design_no)
	{
		$sql=$this->db->query("SELECT * FROM `ret_product_mapping` WHERE pro_id=".$id_product." AND id_design=".$design_no." ");
		if($sql->num_rows() == 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	function check_sub_design_mapping($id_product,$design_no,$id_sub_design)
	{
		$sql=$this->db->query("SELECT * FROM `ret_sub_design_mapping` WHERE id_product=".$id_product." AND id_design=".$design_no." and id_sub_design=".$id_sub_design."");
		if($sql->num_rows() == 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	function get_ProductDesign($data)
	{
	    $sql=$this->db->query("SELECT des.design_no,des.design_name
        FROM ret_product_mapping p 
        LEFT JOIN ret_design_master des ON des.design_no=p.id_design
        where p.id_design IS NOT NULL
        ".($data['id_product']!='' ? " and p.pro_id=".$data['id_product']."" :'')."
        group by des.design_no");
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
        group by des.design_no");
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
         group by  d.id_sub_design");
        return $sql->result_array();
	}

	function ajax_subDesignMapingDetails($data)
	{
	    $returnData=array();
	    $sql=$this->db->query("SELECT m.id_sub_design_mapping,d.sub_design_name,p.product_name,des.design_name,
	    (SELECT image_name from ret_sub_design_mapping_images as img where is_default=1 and img.id_sub_design_mapping=m.id_sub_design_mapping) as default_image
	    FROM ret_sub_design_mapping m 
        LEFT JOIN ret_product_master p ON p.pro_id=m.id_product
        LEFT JOIN ret_design_master des ON des.design_no=m.id_design
        LEFT JOIN ret_sub_design_master d ON d.id_sub_design=m.id_sub_design
        where id_sub_design_mapping is NOT NULL
        ".($data['id_product']!='' ? " and m.id_product=".$data['id_product']."" :'')."
        ".($data['id_design']!='' ? " and m.id_design=".$data['id_design']."" :'')."
        ".($data['id_sub_design']!='' ? " and m.id_sub_design=".$data['id_sub_design']."" :'')."
        ");
        $result= $sql->result_array();
        foreach($result as $items)
        {
            $returnData[]=array(
                              'id_sub_design_mapping'=>$items['id_sub_design_mapping'],
                              'default_image'        =>$items['default_image'],
                              'sub_design_name'      =>$items['sub_design_name'],
                              'product_name'         =>$items['product_name'],
                              'design_name'          =>$items['design_name'],
                              'img_details'          =>$this->ret_sub_design_mapping_images($items['id_sub_design_mapping']),
                              'karigar_details'      =>$this->get_karigar_mapping_products($items['id_sub_design_mapping']),
                             );
        }
        return $returnData;
	}
	
	function ajax_ProductMapingDetails($data)
    {
        $sql=$this->db->query("SELECT m.mapping_id,p.product_name,des.design_name
        FROM ret_product_mapping m 
        LEFT JOIN ret_product_master p ON p.pro_id=m.pro_id
        LEFT JOIN ret_design_master des ON des.design_no=m.id_design
        where m.mapping_id is NOT NULL
        ".($data['id_product']!='' ? " and m.pro_id=".$data['id_product']."" :'')."
        ".($data['id_design']!='' ? " and m.id_design=".$data['id_design']."" :'')."
        ");
        //print_r($this->db->last_query());exit;
        return $sql->result_array();
    }
    
    
    function getSubDesignName($SearchTxt){

		$data = $this->db->query("SELECT s.id_sub_design as value,s.sub_design_name as label
                                  FROM ret_sub_design_master s 
			                      WHERE s.sub_design_name like '%".$SearchTxt."%'"); 
		return $data->result_array();
	}
	
	function getDesignName($SearchTxt){

		$data = $this->db->query("SELECT des.design_name as label,des.design_no as value
                                  FROM ret_design_master des
			                      WHERE des.design_name like '%".$SearchTxt."%'"); 
		return $data->result_array();
	}

	function get_DesignSettingsDetails($data)
	{
	    $sql=$this->db->query("SELECT m.id_sub_design_mapping,cat.name as category_name,p.product_name,des.design_name,s.sub_design_name,IFNULL(m.mc_cal_type,'-') as mc_cal_type,IFNULL(m.mc_cal_value,'-') as mc_cal_value,IFNULL(m.wastage_type,'') as wastage_type,IFNULL(m.wastag_value,'-') as wastag_value, IFNULL(ret_attr.total_attr, 0) AS total_attr
        FROM ret_sub_design_mapping m 
        LEFT JOIN ret_product_master p ON p.pro_id=m.id_product
        LEFT JOIN ret_design_master des ON des.design_no=m.id_design
        LEFT JOIN ret_sub_design_master s ON s.id_sub_design=m.id_sub_design
        LEFT JOIN ret_category cat ON cat.id_ret_category=p.cat_id
		LEFT JOIN (SELECT COUNT(*) as total_attr, id_sub_design_mapping FROM ret_design_attributes GROUP BY id_sub_design_mapping) AS ret_attr ON ret_attr.id_sub_design_mapping = m.id_sub_design_mapping
        WHERE m.id_sub_design_mapping IS NOT NULL
        ".($data['id_design']!='' ? " and m.id_design=".$data['id_design']."" :'')."
         ".($data['id_product']!='' ? " and m.id_product=".$data['id_product']."" :'')."
         ".($data['id_sub_design']!='' ? " and m.id_sub_design=".$data['id_sub_design']."" :'')."
         ".($data['id_mc_type']!='' && $data['id_mc_type']>0 ? " and m.mc_cal_type=".$data['id_mc_type']."" :'')."
         ".($data['mc_value']!='' ? " and m.mc_cal_value=".$data['mc_value']."" :'')."
         ".($data['wast_per']!='' ? " and m.wastag_value=".$data['wast_per']."" :'')."
        ");
        return $sql->result_array();
	}
	
	
	function get_wastage_details($id)
	{
	    $sql=$this->db->query("SELECT * FROM `ret_design_weight_range_wc` WHERE id_sub_design_mapping=".$id."");
	    return $sql->result_array();
	}

	

	//Sub Design and Design Mapping

	//Attribute

	function ajax_getattribute($from_date,$to_date)
	{
		$data = array();

		$sql = "SELECT attr_id, attr_name, attr_status, created_on, created_by, updated_on, updated_by FROM ret_attribute";

		if($from_date != '') {

			$sql = $sql.( ' where (date(created_on) BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")');

		}

		$result = $this->db->query($sql);

		$data = $result->result_array();

		return $data;
	}

	function get_empty_attribute()
	{
		$data['attr_name'] = "";
		$data['attr_status'] = 1;

		return $data;
	}

	function getActiveAttribute()
	{
		$data = array();

		$sql = "SELECT attr_id, attr_name, attr_status, created_on, created_by, updated_on, updated_by FROM ret_attribute WHERE attr_status = 1";

		$result = $this->db->query($sql);

		$data = $result->result_array();

		return $data;
	}

	function get_attribute($attr_id)
	{
		$sql = "SELECT  attr_id, attr_name, attr_status, created_on, created_by, updated_on, updated_by FROM ret_attribute WHERE attr_id =".$attr_id;
		$res = $this->db->query($sql);
		return $res->row_array();
	}

	function get_attribute_values($attr_id)
	{
		$sql = "SELECT attr_val_id, attr_id, attr_val FROM ret_attribute_values WHERE attr_id=".$attr_id." ORDER BY attr_val_id ASC";
		$res = $this->db->query($sql);
		return $res->result_array();
	}

	function get_attribute_with_values($attr_id = 0, $status = '')
	{
		$where = "";

		if($status == 0 || $status == 1)
		{
			$where = $where." AND attr_status = ".$status;
		}

		if($attr_id > 0)
		{
			$where = $where." AND attr_id = ".$attr_id;
		}

		$returnData = array();

		$sql = "SELECT attr_id, attr_name, attr_status, created_on, created_by, updated_on, updated_by FROM ret_attribute WHERE 1 ".$where;

		$result = $this->db->query($sql);

		$data = $result->result_array();

		foreach($data as $items)
        {
            $returnData[]=array(
                              'attr_id'		 =>	$items['attr_id'],
                              'attr_name'    =>	$items['attr_name'],
                              'attr_status'  =>	$items['attr_status'],
                              'created_on'   =>	$items['created_on'],
                              'created_by'   =>	$items['created_by'],
							  'updated_on'   =>	$items['updated_on'],
                              'updated_by'   =>	$items['updated_by'],
                              'attr_values'  =>	$this->get_attribute_values($items['attr_id'])
                             );
        }

		return $returnData;
	}

	//Attributes

	//Design Attributes
	function get_design_attr_values($design_id, $attr_id = 0)
	{
		$where = "";
		if($attr_id > 0)
		{
			$where = " AND rda.attr_id = ".$attr_id;
		}
		$sql = "SELECT
					rda.attr_des_id,
					rda.id_sub_design_mapping, 
					rda.attr_id, 
					rda.attr_val_id,
					ra.attr_name,
					rav.attr_val
				FROM ret_design_attributes AS rda
				LEFT JOIN ret_attribute AS ra ON ra.attr_id = rda.attr_id
				LEFT JOIN ret_attribute_values AS rav ON rav.attr_val_id = rda.attr_val_id
				WHERE id_sub_design_mapping=".$design_id.$where;

		$res = $this->db->query($sql);

		return $res->result_array();
	}

	function get_attributes_from_subdesign($product_id, $design_id, $subdesign_id)
	{
		$sql = "SELECT
					rsdm.id_sub_design_mapping,
					IFNULL(rda.attr_des_id,0) AS attr_des_id,
					IFNULL(rda.attr_id,0) AS attr_id,
					IFNULL(rda.attr_val_id,0) AS attr_val_id,
					ra.attr_name,
					rav.attr_val
				FROM ret_sub_design_mapping AS rsdm
				LEFT JOIN ret_design_attributes AS rda ON rda.id_sub_design_mapping = rsdm.id_sub_design_mapping
				LEFT JOIN ret_attribute AS ra ON ra.attr_id = rda.attr_id
				LEFT JOIN ret_attribute_values AS rav ON rav.attr_val_id = rda.attr_val_id
				WHERE rsdm.id_product = ".$product_id." AND rsdm.id_design = ".$design_id." AND rsdm.id_sub_design = ".$subdesign_id;

		$res = $this->db->query($sql);

		return $res->result_array();
	}

	function get_ActiveProducts($data)
	{
	    $sql=$this->db->query("SELECT p.pro_id,p.product_name
        FROM ret_product_master p 
        WHERE p.pro_id IS NOT NULL AND p.product_status=1
        ".($data['id_ret_category']!='' ? " and p.cat_id=".$data['id_ret_category']."" :'')."");
        return $sql->result_array();
	}
	
	function get_MetalCategory($data)
    {
		$data = $this->db->query("SELECT id_ret_category,name,description,id_metal,image FROM `ret_category` WHERE status = 1 ".($data['id_metal']!='' ? " and id_metal=".$data['id_metal']."" :'')." ");
		return $data->result_array();
	}

}
?>
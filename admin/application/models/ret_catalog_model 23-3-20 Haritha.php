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
 
   	
	/*Purity functions here */ 	
  
   	 function ajax_getPurity()
    {
		$id_purity = $this->db->query("SELECT * FROM ret_purity");
		return $id_purity->result_array();
	}

	 function get_purity($id_purity)
    {
		$id_purity = $this->db->query("select * from purity where id_purity=".$id_purity);
		return $id_purity->row_array();
	}
	
    public function insert_purity($data)
    {
    	$insert_flag = 0;
		$insert_flag = $this->db->insert('purity',$data);
		return ($insert_flag == 1 ? $this->db->insert_id(): 0);
	}
	
	public function update_purity($data,$id)
    {    	
    	$edit_flag = 0;
    	$this->db->where('id_purity',$id); 
		$edit_flag = $this->db->update('purity',$data);
		return ($edit_flag==1?$id:0);
	}
			 
	public function delete_purity($id)
    {
        $this->db->where('id_purity', $id);
        $status= $this->db->delete('purity'); 
		return $status;
	} 
	/*End of purity functions*/
	
	/*color functions here */ 	
  
   	 function ajax_getcolor()
    {
		$id_color = $this->db->query("SELECT * FROM ret_color");
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
		$insert_flag = $this->db->insert('color',$data);
		return ($insert_flag == 1 ? $this->db->insert_id(): 0);
	}
	
	public function update_color($data,$id)
    {    	
    	$edit_flag = 0;
    	$this->db->where('id_color',$id); 
		$edit_flag = $this->db->update('color',$data);
		return ($edit_flag==1?$id:0);
	}
			 
	public function delete_color($id)
    {
        $this->db->where('id_color', $id);
        $status= $this->db->delete('color'); 
		return $status;
	} 
	/*End of color functions*/
	/*cut functions here */ 	
  
   	 function ajax_getcut()
    {
		$id_cut = $this->db->query("SELECT * FROM ret_cut");
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
		$insert_flag = $this->db->insert('cut',$data);
		return ($insert_flag == 1 ? $this->db->insert_id(): 0);
	}
	
	public function update_cut($data,$id)
    {    	
    	$edit_flag = 0;
    	$this->db->where('id_cut',$id); 
		$edit_flag = $this->db->update('cut',$data);
		return ($edit_flag==1?$id:0);
	}
			 
	public function delete_cut($id)
    {
        $this->db->where('id_cut', $id);
        $status= $this->db->delete('cut'); 
		return $status;
	} 
	/*End of cut functions*/
	/*clarity functions here */ 	
  
   	 function ajax_getclarity()
    {
		$id_clarity = $this->db->query("SELECT * FROM ret_clarity");
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
		$insert_flag = $this->db->insert('clarity',$data);
		return ($insert_flag == 1 ? $this->db->insert_id(): 0);
	}
	
	public function update_clarity($data,$id)
    {    	
    	$edit_flag = 0;
    	$this->db->where('id_clarity',$id); 
		$edit_flag = $this->db->update('clarity',$data);
		return ($edit_flag==1?$id:0);
	}
			 
	public function delete_clarity($id)
    {
        $this->db->where('id_clarity', $id);
        $status= $this->db->delete('clarity'); 
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
		$product = $this->db->query("SELECT p.allowed_order_qty,p.qty_type,p.can_view_by,p.length,p.width,p.height,p.stone_name,p.`id_product`, p.`id_subcategory`, p.`name`, p.`description`, p.`status`, p.`code`, p.`qty`, p.`date_add`, p.`date_update`, p.`allow_customization`, p.`certification`, p.`min_size`, p.`max_size`, p.`gold_value`, p.`wastage`, p.`tax`, p.`stone_charges`, p.`making_charges`, p.`other_charges`, p.`product_stone`, p.`certif_charges`,p.`total_stones`, p.`id_employee`, p.`id_metal`,c.`id_ret_category`, c.`name` as category_name,s.`id_subcategory`, s.`name` as subcategory,p.show_rate,p.certif_img,(SELECT image from product_images as img where is_default=1 and img.id_product=p.id_product) as default_img,p.product_for
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
		$sql = "SELECT f.floor_id,b.id_branch,b.name as branch_name,f.floor_name,f.floor_short_code,f.created_on,f.floor_status FROM ret_branch_floor as f left join branch b on b.id_branch = f.branch_id";

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
		left join branch r on r.id_branch=c.floor_id";

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
		$data = $this->db->query("SELECT * FROM ret_branch_floor_counter WHERE counter_status = 1");
		return $data->result_array();
	}
	
	/** 
	*	Master :: Counter -- Starts
	*/	
	function ajax_get_makingtype($from_date,$to_date)
	{
		$data = array();
		$sql = ("SELECT mak_id,mak_name,mak_short_code,mak_status,created_on FROM ret_making_type");

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
		$sql  = ("SELECT id_theme,theme_name,theme_code,theme_desc,theme_status,created_on FROM ret_theme");
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
	/** 
	*	Master :: Theme -- Ends
	*/
	
	/** 
	*	Master :: Material -- Ends
	*/
	function ajax_getmaterial($from_date,$to_date)
    {	
		$data = array();
		$sql  = ("SELECT material_id,material_name,material_code,material_status,created_on FROM ret_material");
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
	function get_Activemtrrate()
    {
		$data = $this->db->query("SELECT material_id,material_name FROM ret_material");
		return $data->result_array();
	}
	
 	function ajax_getmtrrate($from_date,$to_date,$material_id)
    {
		$data = array();
		$sql  ="SELECT m.material_id,m.material_name,mtr.mat_rate_id,mtr.mat_rate,Date_Format(mtr.effective_date,'%d-%m-%Y') as effective_date,mtr.created_on FROM ret_material_rate mtr 
		left join ret_material m on m.material_id = mtr.material_id";
		if(($material_id!='' && $material_id > 0) && $from_date!='')
		{
			$sql = $sql." where mtr.material_id =" .$material_id. ' and (date(mtr.created_on)
			BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")';
		}
		elseif(($material_id!='' && $material_id > 0 ) || $from_date!='')
		{
			$sql = $sql." where ".($material_id != '' && $material_id > 0  ? ('mtr.material_id ='.$material_id) : ($from_date != '' ? ' date(mtr.created_on) BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'"' : (''))) ;
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
	/** 
	*	Master :: Material -- Ends
	*/
	
	/** 
	*	Master :: Product -- Starts
	*/
	function getActiveProducts(){
		$result = $this->db->query("select pro_id,product_name as name,IFNULL(product_short_code,'') as code from ret_product_master where product_status =1");
		return $result->result_array();
	}
	
	function getActiveSearchProd($SearchTxt){ // For autocomplete 
		$result = $this->db->query("select pro_id as value,if(product_short_code = '' or product_short_code is null ,product_name ,CONCAT(product_short_code,' - ',product_name) ) as label from ret_product_master where product_status=1 and (product_name like '%".$SearchTxt."%' or product_short_code like '%".$SearchTxt."%')");
		return $result->result_array();
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
		$data = $this->db->query("SELECT uom_id,uom_name,IFNULL(uom_short_code,'') as code FROM `ret_uom` WHERE uom_status = 1");
		return $data->result_array();
	}
	/** 
	*	Master :: UOM -- Ends
	*/
	
	/** 
	*	Master :: Category -- Starts
	*/
	function ajax_getcategory($from_date,$to_date)
    {	
		$data = array();
		$sql  = ("SELECT id_ret_category,name,description,making_charge,status,image FROM ret_category");
		if($from_date!='')
		{
			$sql = $sql." where".('(date(created_on)
			BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")');
		}
		$result=$this->db->query($sql);
		$data = $result->result_array();
		return $data;
			
	}
	function get_ret_category($id_ret_category)
    {
		$id_ret_category = $this->db->query("select id_ret_category,name,description,making_charge,status,image from ret_category where id_ret_category=".$id_ret_category);
		return $id_ret_category->row_array();
	}
	function getActiveCategorymtr()
    {
		$data = $this->db->query("SELECT id_ret_category,name,description,making_charge,image FROM `ret_category` WHERE status = 1");
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
		$data = $this->db->query("SELECT id_karigar,CONCAT(firstname,' ',lastname) as karigar,IFNULL(code_karigar,'') as code FROM `ret_karigar` WHERE status_karigar = 1");
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
	function get_empty_design()
	{
		$data=array(
						'product_id'           => NULL,
						'design_id'            => NULL,
						'design_no'            => NULL,
		                'default_img'          => NULL,
						'design_status'        => NULL,
						'image'                => NULL,
						'id_image'             => NULL,
						'design_code'		   => NULL,
						'design_name'		   => NULL,
						'theme'		           => NULL,
						'hook_type'		       => NULL,
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
	function ajax_get_design($from_date,$to_date)
	{
		$data = array();
			$sql = ("SELECT design_no,design_code,design_name,created_time,design_status from ret_design_master");

			if($from_date!='')
			{
			$sql = $sql.( ' where (date(created_time) BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")');
			}
		$result = $this->db->query($sql);
		$data = $result->result_array();
		return $data;
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
		$sql = $this->db->query("select pro_id,product_name,has_hook,has_screw from ret_product_master 
		where has_hook =1 or has_screw=1 and pro_id=".$id);
		return $sql->result_array();
	}
	function delete_designimage($file)
    {
		 $this->db->where('image', $file);
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
	
		function getcalculate($id_tgi)
    {
		$id_tgi = $this->db->query("SELECT * FROM ret_taxgroupitems WHERE tgi_sno=".$id_tgi);
		return $id_tgi->result_array();
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
			$sql = ("SELECT p.pro_id,p.cat_id,p.product_name,p.product_short_code,c.name,p.created_time,p.product_status FROM ret_product_master p
			left join ret_category c on c.id_ret_category=p.cat_id");

			if($from_date!='')
			{
			$sql = $sql.( ' where (date(p.created_time) BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")');
			}
		$result = $this->db->query($sql);
		$data = $result->result_array();
		return $data;
	} 
	function get_ret_product($id_product)
	{
		$id = $this->db->query("select * from ret_product_master where pro_id=".$id_product);
		return $id->row_array();
	}
	function getActiveProduct(){
		$result = $this->db->query("select * from ret_product_master where product_status=1");
		return $result->result_array();
	}
	public function getProd_empty_record()
    {
		$data=array(
		'pro_id'           		=> NULL,
		'cat_id'           		=> NULL,
		'hsn_code'             	=> NULL,
		'stock_type'           => 1,
		'sales_mode'   		   => 1,
		'wastage_type'   	   => 1,
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
		'calculation_based_on' => 0,
		'sales_markup'		   => 0,
		'max_markup_per_for_rateitems'  => NULL,
		'no_of_tags_to_print'   => NULL,
		'tax_group_id'       	=>NULL,
		'product_status'       	=> 1,
		'created_time'          => NULL,
		'create_by'            	=> $this->session->userdata('uid') );
		 
		return $data;
	}

	/** 
	*	Master :: Product -- Ends
	*/ 
	
	/** 
	*	Master :: Sub Product -- Starts
	*/ 
	public function get_empty_subproduct()
    {
 		$data=array( 	
						'sub_pro_id'	       => NULL,
						'less_tax'	           => 0,
						'wastage_billing'      => 0,
						'stock_type'           => 1,
						'sales_mode'   		   => 1,
						'wastage_type'   	   => 1,
						'other_materials'      => 0,
						'has_stone'		       => 0,
						'metal_type'		   => 0,
						'sub_pro_code'         => NULL,
						'sub_pro_name'		   => NULL,
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
						'calculation_based_on' => NULL,
						'tax_group_id'		       =>NULL,
						'sub_pro_status'		       => 1,
						'created_time'            => NULL,
						'create_by'            => $this->session->userdata('uid') );
					   
		return $data;
	}
	function ajax_get_retSubProduct($from_date,$to_date)
	{
		$data = array();
			$sql = ("SELECT s.sub_pro_id,s.sub_pro_name,s.sub_pro_code,s.sub_pro_status,s.created_time from ret_sub_product_master s");

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
		$sql  = ("SELECT id_metal,metal,metal_code,metal_status,created_on FROM metal");
		if($from_date!='')
		{
			$sql = $sql." where".('(date(created_on)
			BETWEEN "'.date('Y-m-d',strtotime($from_date)).'" AND "'.date('Y-m-d',strtotime($to_date)).'")');
		}
		$result=$this->db->query($sql);
		$data = $result->result_array();
		return $data;
			
	}
	function get_metals($id_metal)
    {
		$id_metal = $this->db->query("select id_metal,metal,metal_code,metal_status from metal where id_metal=".$id_metal);
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
		$sql = $this->db->query(" SELECT f.fin_id,f.fin_code,f.fin_year_code,date_format(f.fin_year_from,'%Y-%m-%d')as fin_year_from,date_format(f.fin_year_to,'%Y-%m-%d')as fin_year_to,f.fin_status,f.fin_year_code,date_format(f.created_on,'%Y-%m-%d')as created_on
		    FROM ret_financial_year f
				ORDER BY f.fin_id desc");
		return $sql->result_array();
	}
	function get_financialyear_by_status()
    {
		$sql = $this->db->query(" SELECT f.fin_id,f.fin_code,f.fin_year_code,date_format(f.fin_year_from,'%Y-%m-%d')as fin_year_from,date_format(f.fin_year_to,'%Y-%m-%d')as fin_year_to,f.fin_status,f.fin_year_code,date_format(f.created_on,'%Y-%m-%d')as created_on
		    FROM ret_financial_year f
			where f.fin_status=1");
		return $sql->row_array();
	}
	function get_finance_entry_records($id)
	{
		$sql = $this->db->query("SELECT f.fin_id,f.fin_code,date_format(f.fin_year_from,'%Y-%m-%d')as fin_year_from,date_format(f.fin_year_to,'%Y-%m-%d')as fin_year_to,f.fin_status,f.fin_year_code
				FROM ret_financial_year as f 
				WHERE fin_id='".$id."'");
		//print_r($this->db->last_query());exit;
		return $sql->result_array()[0];
	}

	public function update_financialData($data,$id,$id_field,$table)
    {    
	    $edit_flag = 0;
	    $this->db->where($id_field,$id);
		$edit_flag = $this->db->update($table,$data);
		return ($edit_flag==1?$id:0);
	}
	/** 
	*	Master :: Financial Year -- Ends
	*/
}
?>
var path =  url_params();
var ctrl_page 		= path.route.split('/');
var lot_details 	= [];
var tax_details 	= [];
var tag_stones 		= [];
var tag_materials 	= [];
var tag_design_stones 		= [];
var tag_design_materials 	= [];
var stone_details 	= [];
var stones 			= [];
var stone_types 			= [];
var uom_details 			= [];
var pre_img_files=[];
var pre_img_resource=[];
var dublicate_tag_details =[];
var pro_designs = [];
$(document).ready(function() {
    
    $('.dateRangePicker').daterangepicker({ 
	    //startDate:  moment().subtract(6, 'days'), 
	    endDate: moment(), 
	});
	
	 var path =  url_params();
	 $('#status').bootstrapSwitch();	
	    $(window).scroll(function() {    // this will work when your window scrolled.
		var height = $(window).scrollTop();  //getting the scrolling height of window
		if(height  > 300) {
			$(".stickyBlk").css({"position": "fixed"});
		} else{
			$(".stickyBlk").css({"position": "static"});
		}
	});				
     prod_info = [];	
	 switch(ctrl_page[1])
	 {
	 	case 'tagging':
				 switch(ctrl_page[2]){				 	
				 	case 'list':				 	
				 			get_tagging_list();
				 			get_stones();
				 		break;
				 	case 'add':				 	
				 			get_received_lots();
							//get_tag_types();
							//get_tag_taxgroups();
							get_tag_stones();
							get_tag_materials();
							//get_employee();
							get_stones();
							get_stone_types();
							get_ActiveUOM();
							get_taxgroup_items();
							$("#tag_lt_prod").on("keyup",function(e){ 
								var prod = $("#tag_lt_prod").val(); 
								if(prod.length == 3) { 
									get_lot_products(prod);
				                }
							});
							$("#tag_lt_design").on("keyup",function(e){ 
								var des = $("#tag_lt_design").val(); 
								if(des.length == 3) { 
									get_lot_designs(des);
				                }
							});

							$("#des_select").select2(
							{
								placeholder:"Select Design",
								allowClear: true		    
							});

				 		break;
				 	case 'edit':
				 			get_received_lots();
							//get_tag_types();
							//get_tag_taxgroups();
							get_tag_stones();
							get_tag_materials();
							//get_employee();
							$("#tag_lt_prod").on("keyup",function(e){ 
								var prod = $("#tag_lt_prod").val(); 
								if(prod.length == 3) { 
									getSearchProd(prod);
				                }
							});
						//	calculateSaleValue();
				 			//load_tag_stone_list_on_edit();
				 			//load_tag_materials_list_on_edit();
							break;
				 	case 'bulk_edit':      //coaded by karthik
				 		 //get_received_lots();
				 		 $("#tag_no").on('keyup',function(e) {  
								var tag_no = this.value; 
									get_tag_number(tag_no); 
							}); 
				 		 	$("#prod_name").on("keyup",function(e){ 
								var prod = $("#prod_name").val(); 
								if(prod.length == 3) { 
									getSearchProd(prod);
				                }
							});
							
							$("#des_name").on("keyup",function(e){ 
								var des = $("#des_name").val(); 
								if(des.length == 3) { 
									getSearchDes(des);
				                }
							});
							
							$('#mc_type').select2();
							$('#mc_type').select2({placeholder:'Select MC type',allowClear: true});
							$('#mc_type').select2("val",($('#id_mc_type').val()!='' && $('#id_mc_type').val()>0?$('#id_mc_type').val():''));
							$("#wastage_percent, #mc_value").on('keyup', function(e){
								var  retail_max_wastage_percent=$('#wastage_percent').val();
								var  tag_mc_value=$('#mc_value').val();
								var  design_id=$('#design_id').val();
						
							});
				 		break;
    				 	case 'duplicate_print':
    						$("#tag_no").on('keyup',function(e) {  
    						var tag_no = this.value; 
    						get_tag_number(tag_no); 
    						});
    						get_received_lots();
    				 	 	get_ActiveProduct();
    				 	 break;
    				 	 case 'tag_mark':
    				 	 	//set_tag_marking();
    				 	 	get_ActiveProduct();
    				 	 break;
    				 	 case 'tag_edit':
    				 	    get_tag_edit_lots();
    				 	 	get_ActiveProduct();
    				 	 break;
				 }
	 		break; 
	} 
	
	if(ctrl_page[1]=='get_tag_detail_list')
	{
	    lot_tag_detail('','');
	    get_branchwise_emp();
	}
	
	// BTtagData
	if(ctrl_page[1]=='bt_tag_list')
	{
	    printBTtagData();
	}
	
	$('#tag-dt-btn').daterangepicker(
	{
	 ranges: {
		'Today'  : [moment(), moment()],
		'Yesterday'  : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
		'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
		'Last 30 Days': [moment().subtract(29, 'days'), moment()],
		'This Month'  : [moment().startOf('month'), moment().endOf('month')],
		'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
		 },
		 startDate: moment().subtract(29, 'days'),
		 endDate: moment()
		},
		function (start, end) {
		$('#tag_date1').text(moment().startOf('month').format('YYYY-MM-DD'));
		$('#tag_date2').text(moment().endOf('month').format('YYYY-MM-DD'));
		get_tagging_list(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
		 }
	);
	/* Lot Received At */
	$('#lt_rcvd_branch_sel').on('change',function(e){
		if(this.value != '')
		{
			$("#id_branch").val(this.value);
		}
		else
		{
			$("#id_branch").val('');			
		}
	});
	/* Get Lot inward ids*/
	/* $('#tag_lot_id').on('change',function(e){
		if( $('#tag_lot_id').length > 3){
			get_received_lots();
		}		
	}); */
	$('#tag_datetime').datepicker({ dateFormat: 'yyyy-mm-dd' });
	$("#tag_lot_received_id,#lot_id").select2({			    
	 	placeholder: "Select Lot ID",			    
	 	allowClear: true		    
 	});
	$("#select_tag_type").select2({			    
	 	placeholder: "Select Tag Type",			    
	 	allowClear: true		    
 	});
	$("#select_tax_group_id").select2({			    
	 	placeholder: "Select Tax Group",			    
	 	allowClear: true		    
 	});
	$("#select_tag_type").on('change', function(e){
		if(this.value!='')
		{
			$("#tag_type").val(this.value);
		}else{
			$("#tag_type").val('');
		}
	});
	
	$("#des_select").on('change', function(e){
		if(this.value !='')
		{
		    var prodesId = this.value;
		    console.log(prodesId);
		    $.each(pro_designs, function (key, item) {
		        if(item.design_no == prodesId){
		            if(item.sales_mode == 2){
		                $('#tag_wast_perc').val(item.wastag_value);
		                $('#tag_id_mc_type').val(item.mc_cal_type);
		                $('#tag_mc_value').val(item.mc_cal_value);
		                $('#tag_wast_perc').attr("disabled", true);
                        $('#tag_id_mc_type').attr("disabled", true);
                        $('#tag_mc_value').attr("disabled", true);
                        $('#tag_sell_rate').attr("disabled", true);
		            }else{
		                $('#tag_sell_rate').attr("disabled", false);
		                 $('#tag_wast_perc').attr("disabled", true);
                        $('#tag_id_mc_type').attr("disabled", true);
                        $('#tag_mc_value').attr("disabled", true);
		            }
		        }
		    });
		}
		if(ctrl_page[1]=='tagging' && ctrl_page[2]=='add')
		{
		    calculateTagFormSaleValue();
		}
	});
	
	
	$("#select_tax_group_id").on('change', function(e){
		if(this.value != '')
		{
			$("#tax_group_id").val(this.value);
		}else{
			$("#tax_group_id").val('');
		}
	});
	$('#tag_lot_received_id').on('change', function(e){
		$('#des_select option').remove();
		$('#tag_lt_prod option').remove();
		//$('#reset_tag_form').trigger('click');
		if(this.value != '')
		{
			var selected_lot_no = this.value;
			$('#tag_lot_id').val(selected_lot_no);
			$.each(lot_details.lot_inward, function (key, item) {
				if(selected_lot_no == item.lot_no)
				{ 
					$('#lt_category').html(item.category_name); 
					$('#lt_metal').html(item.metal+' - '+item.purity_name); 
					$('#lt_wast').html(item.wastage_percentage+' %'); 
					$('#lt_mc').html(item.making_charge+' '+item.mc_type); 
					$('#lt_tax_group').html(item.tgrp_name); 
					$('#lt_id_tax_group').html(item.tgrp_id); 
					$('#tax_percentage').val(item.tax_percentage); 
					$('#tgi_calculation').val(item.tgi_calculation); 
					$('#purity').val(item.id_purity); 
					if(ctrl_page[2]!='bulk_edit')
					{
					   get_metal_rates_by_branch(item.lot_received_at);
					}
				}
			});
			get_lot_products(selected_lot_no);
			if(ctrl_page[2]=='bulk_edit')
			{
				var  id_mc_type=$('#id_mc_type').val();
				var  id_branch=$('#id_branch').val();
				$("#tag_id").val(''); 
				$("#tag_no").val(''); 
				$("#prod_name" ).val(''); 
				$('#lot_product').val('');
			}//coaded by karthik
		}else{
			$('#lt_product').html("-");
			$('#lt_prod_code').val(""); 
			$('#lt_category').html("-"); 
			$('#lt_design').html("-"); 
			$('#lt_purity').html("-"); 
			$('#lt_metal').html("-"); 
			$('#lt_wast').html("-"); 
			$('#lt_mc').html("-"); 
			$('#lt_tax_group').html("-"); 
			$('#lt_id_tax_group').val(""); 
			$('#lt_date').html("-"); 
			$('#get_tag_details').prop('disabled',true);
		}
	});
	$("#select_tax_group_id").on('change', function(e){
		if(this.value != ''){
			var taxgroupid = this.value;
			my_Date = new Date();
			$.ajax({
				url: base_url+'index.php/admin_ret_tagging/getAvailableTaxGroupItems/?nocache=' + my_Date.getUTCSeconds(),             
				dataType: "json", 
				method: "POST", 
				data: { 'taxGroupId': taxgroupid }, 
				success: function ( data ) { 
					console.log(data);
					tax_details = data;
					calculateSaleValue();
				}
			 });
		}
	});
	/* Design No. - Starts */	
	$("#tag_design_no").on("keyup",function(e){ 
		var design = $("#tag_design_no").val();
		if(design.length >= 3) { 
			getSearchDesignNo(design,$("#tag_design_no").val());
			/* $("#create_stn_details").prop('disabled', true);
			$("#create_material_details").prop('disabled', true); */
		}else if($("#design_id").val() == "" || $("#tag_design_no").val() == ""){
			/* $("#create_stn_details").prop('disabled', false);
			$("#create_material_details").prop('disabled', false); */
		}
	}); 
	/* Ends - Design No. */
	$("input[name='product[product_stone]']").on('change',function(){	
		if ($("input[name='product[product_stone]'][value='2']").prop("checked")){
			$('#tot_stone_diamond').attr('disabled','disabled');
			$('#stone_name').prop("disabled",'disabled');
			  $("#diamond_block").hide();
		}else{
			$('#tot_stone_diamond').prop("disabled", false);
			 $("#diamond_block").show();
			 if ($("input[name='product[product_stone]'][value='0']").prop("checked")){
				$('#stone_name').prop("disabled",'disabled');
			 }
			 else{ 	
				$('#stone_name').prop("disabled", false);
			 }
		}
	});
	$("#create_stn_details").on('click',function(){
		if(validateStoneDetailRow()){
			var temp_tag_stones = tag_stones;
			if($("#design_id").val() != ""){
				temp_tag_stones = tag_design_stones;
			}
			var html = "";
			var row_id = $('#tagging_stone_details tbody tr').length;
			var select_op = '<select class="form-control select_stn_det" id="tagstone_'+row_id+'" name="tagstone[stone_id][]"><option value=""> - Select Stone - </option>';
			var selected_stones = [];
			var op_length = 0;
			$('#tagging_stone_details > tbody  > tr').each(function(index, tr) {
				selected_stones.push({ "st_id" : $(this).find('td:first   .select_stn_det').val()});
			});
			$.each(tag_stones, function(key, item){
				var $exist_flag = false;
				$.each(selected_stones, function(stkey, stval){
					if(stval.st_id == item.stone_id){
						$exist_flag = true;
					}
				});
				if(!$exist_flag){
					select_op += '<option value="'+item.stone_id+'">'+item.stone_name+'</option>';
					op_length++;
				}
			});
			select_op += '</select>';
			if(op_length > 0){
				html += '<tr><td>'+select_op+'</td><td><div class="input-group"> <input class="form-control tagstone_pcs" type="number" step="any" name="tagstone[pcs][]" value="" required /></div></td><td><div class="input-group"><input type="number" class="form-control tagstone_wt" step="any" name="tagstone[weight][]" value="" required /></div></td><td><input type="hidden" name="tagstone[uom_id][]" value="" /><div class="stn_uom"></div></td><td><div class="input-group"> <input class="form-control tagstone_amt" type="number" step="any" name="tagstone[amount][]" value="" required /></div></td></tr>';
				$('#tagging_stone_details tbody').append(html);
			}else{
				alert("There is no more stone details are available");
			}
		}else{
			alert("Please fill required fields");
		}
	});
	$("#create_material_details").on('click',function(){
		if(validateMaterialDetailRow()){
			var html = "";
			var row_id = $('#tagging_material_details tbody tr').length;
			var select_op = '<select class="form-control select_mat_det" id="tagmat_'+row_id+'" name="tagmaterials[material_id][]"><option value=""> - Select Material - </option>';
			var selected_materials = [];
			var op_length = 0;
			$('#tagging_material_details > tbody  > tr').each(function(index, tr) {
				selected_materials.push({ "mat_id" : $(this).find('td:first .select_mat_det').val()});
			});
			$.each(tag_materials, function(key, item){
				var $exist_flag = false;
				$.each(selected_materials, function(stkey, stval){
					if(stval.mat_id == item.material_id){
						$exist_flag = true;
					}
				});
				if(!$exist_flag){
					select_op += '<option value="'+item.material_id+'">'+item.material_name+'</option>';
					op_length++;
				}
			});
			select_op += '</select>';
			if(op_length > 0){
				html += '<tr><td>'+select_op+'</td><td><div class="input-group"> <input class="form-control tagmat_wt" type="number" step="any" name="tagmaterials[weight]" value="" required /></div></td><td><input type="hidden" name="tagmaterials[uom_id]" value="" /><div class="stn_uom"></div></td><td><div class="input-group"> <input class="form-control tagmat_amt" type="number" step="any" name="tagmaterials[amount][]" value="" required /></div></td></tr>';
				$('#tagging_material_details tbody').append(html);
			}else{
				alert("There is no more materials details are available");
			}
		}else{
			alert("Please fill required fields");
		}
	});
	$(document).on('change',".select_stn_det", function(){
		//alert(this.value);
		var selectId = this.value;
		//var row = $(this).parent().parent();
		var row = $(this).closest('tr'); 
		if(selectId != ""){
			$.each(tag_stones, function(key, item){
				if(item.stone_id == selectId){
					row.find('td:eq(3) .stn_uom').html(item.uom_short_code); 
					//row.find('td eq(3) .stn_uom').html(item.uom_short_code);
					$(row).find("td:eq(3) input[type='hidden']").val(item.uom_id);
				}
			});
		}else{
			row.find('td:eq(3) .stn_uom').html(""); 
			$(row).find("td:eq(3) input[type='hidden']").val("");
		}
		console.log(row);
	}); 
	$(document).on('change',".select_mat_det", function(){
		var selectedId = this.value;
		var row = $(this).closest('tr'); 
		if(selectedId != ""){
			$.each(tag_materials, function(key, item){
				if(item.material_id == selectedId){
					row.find('td:eq(2) .stn_uom').html(item.uom_short_code); 
					$(row).find("td:eq(2) input[type='hidden']").val(item.uom_id);
				}
			});
		}else{
			row.find('td:eq(2) .stn_uom').html(""); 
			$(row).find("td:eq(2) input[type='hidden']").val("");
		}
		console.log(row);
	});  
// add new metal information 
	$("#add_metal_info").on('click',function(){
		var html = "";
		var a = $("#m_increment").val();
		var i = ++a;
		$("#m_increment").val(i);
			html+="<tr id='detail"+i+"'><td>"+i+"</td><td><div class='col-sm-2'>Min wgt</div><div class='col-sm-4'><input type='text' class='form-control' placeholder='min metal weight' name='detail["+i+"][min_metal_weight]' required='true'/></div><div class='col-sm-2'>Max wgt</div><div class='col-sm-4'><input type='text' class='form-control' placeholder='max metal weight' name='detail["+i+"][max_metal_weight]' required='true'/></div></td>"+"<td><select name='detail["+i+"][id_color]' style='width:100%;' id='color"+i+"' class='form-control'></select></td>"+"<td><select style='width:100%;' name='detail["+i+"][id_purity]' id='purity"+i+"' class='form-control'></select></td>"+"<td><button type='button' class='btn btn-danger' onclick='m_remove("+i+")'><i class='fa fa-trash'></i></button></td>";
					$(".overlay").css('display','block');
					$.ajax({
						type: 'GET',
						url: base_url+'index.php/get/active_color',
						dataType:'json',
						success:function(data){
							console.log(data);
						  var id_color =  $('#id_color').val();
						   $.each(data.color, function (key, item) {
							   		$('#color'+i+'').append(
										$("<option></option>")
										  .attr("value", item.id_color)
										  .text(item.color)
									);
							});
								$('#color'+i+'').select2({
							    placeholder: "Select color",
							    allowClear: true
							});
							 $('#color'+i+'').select2("val",(id_color!='' && id_color>0?id_color:''));
							 $(".overlay").css("display", "none");	
						}
					});
					$(".overlay").css('display','block');
					$.ajax({
						type: 'GET',
						url: base_url+'index.php/get/active_purity',
						dataType:'json',
						success:function(data){
							console.log(data);
						  var id_purity =  $('#id_purity').val();
						   $.each(data.purity, function (key, item) {
							   		$('#purity'+i+'').append(
										$("<option></option>")
										  .attr("value", item.id_purity)
										  .text(item.purity)
									);
							});
							$('#purity'+i+'').select2({
							    placeholder: "Select purity",
							    allowClear: true
							});
							$('#purity'+i+'').select2("val",(id_purity!='' && id_purity>0?id_purity:''));
							 $(".overlay").css("display", "none");	
						}
					});
					 $('#prod_metal_detail tbody').append(html);
	});
//add new diamond information	
	$("#add_diamond_info").on('click',function(){
		var html = "";
		var a = $("#d_increment").val();
		var i = ++a;
		$("#d_increment").val(i);
			html+="<tr id='d_detail"+i+"'><td>"+i+"</td>"+"<td><select name='d_detail["+i+"][id_cut]' style='width:100%;' id='id_cut"+i+"' class='form-control'></select></td>"+"<td><select style='width:100%;' name='d_detail["+i+"][id_dia_color]' id='id_dia_color"+i+"' class='form-control'></select></td>"+"<td><select style='width:100%;' name='d_detail["+i+"][id_clarity]' id='id_clarity"+i+"' class='form-control'></select></td>"+"<td><select style='width:100%;' name='d_detail["+i+"][id_carat]' id='id_carat"+i+"' class='form-control'></select></td>"+"<td><button type='button' class='btn btn-danger' onclick='dia_remove("+i+")'><i class='fa fa-trash'></i></button></td>";
					$(".overlay").css('display','block');
					$.ajax({
						type: 'GET',
						url: base_url+'index.php/get/active_color',
						dataType:'json',
						success:function(data){
						// load diamnd carat options
						   $.each(data.color, function (key, item) {
							   		$('#id_dia_color'+i+'').append(
										$("<option></option>")
										  .attr("value", item.id_color)
										  .text(item.color)
									);
							});
								$('#id_dia_color'+i+'').select2({
							    placeholder: "Select color",
							    allowClear: true
							});
							 $('#id_dia_color'+i+'').select2("val","");
							 $(".overlay").css("display", "none");	
							}
					});
					$(".overlay").css('display','block');
					$.ajax({
						type: 'GET',
						url: base_url+'index.php/get/active_masters',
						dataType:'json',
						success:function(data){
						// load diamnd cut options
						   $.each(data.cut, function (key, item) {
							   		$('#id_cut'+i+'').append(
										$("<option></option>")
										  .attr("value", item.id_cut)
										  .text(item.cut)
									);
							});
							$('#id_cut'+i+'').select2({
							    placeholder: "Select Cut",
							    allowClear: true
							});
							 $('#id_cut'+i+'').select2("val","");
						// load diamnd clarity options
						   $.each(data.clarity, function (key, item) {
							   		$('#id_clarity'+i+'').append(
										$("<option></option>")
										  .attr("value", item.id_clarity)
										  .text(item.clarity)
									);
							});
							$('#id_clarity'+i+'').select2({
							    placeholder: "Select clarity",
							    allowClear: true
							});
							$('#id_clarity'+i+'').select2("val","");
						// load diamnd carat options
						   $.each(data.carat, function (key, item) {
							   		$('#id_carat'+i+'').append(
										$("<option></option>")
										  .attr("value", item.id_carat)
										  .text(item.carat)
									);
							});
							$('#id_carat'+i+'').select2({
							    placeholder: "Select carat",
							    allowClear: true
							});
							 $('#id_carat'+i+'').select2("val","");
							 $(".overlay").css("display", "none");	
						}
					});
					 $('#prod_dia_detail tbody').append(html);
	});
});
// to load metal info list
function prodInfo_list()
{
	my_Date = new Date();
	$("div.overlay").css("display", "block"); 
	var id_prod = $('#id_product').val();
		$.ajax({
				  type: 'GET',
				  url:  base_url+'index.php/get/metal_info_list/'+id_prod+'?nocache='+ my_Date.getUTCSeconds(),
				  dataType: 'json',
				  success: function(data) {
				  	prod_info = data;
				  	var html = "";
				  	var dhtml = "";
				  	//load metal information
				  	 $.each(data.prod_mInfo, function (key, item) {
						var a = $("#m_increment").val();
						var i = ++a;
						$("#m_increment").val(i);
					   html += "<tr id='detail"+i+"'><td>"+i+"</td><td><div class='col-sm-2'>Min wgt</div><div class='col-sm-4'><input type='text' class='form-control' placeholder='metal weight' name='detail["+i+"][min_metal_weight]' value='"+item.min_metal_weight+"' required='true'/></div><div class='col-sm-2'>Max wgt</div><div class='col-sm-4'><input type='text' class='form-control' placeholder='metal weight' name='detail["+i+"][max_metal_weight]' value='"+item.max_metal_weight+"' required='true'/></div></td>"+"<td><select name='detail["+i+"][id_color]' style='width:100%;' class='form-control' id='color_select"+i+"'></td>"+"<td><select style='width:100%;' name='detail["+i+"][id_purity]' id='purity_select"+i+"' class='form-control'></select></td>"+"<td><button type='button' class='btn btn-danger' onclick='m_remove("+i+","+item.id_metal_details+")'><i class='fa fa-trash'></i></button></td>";	  
						 var m_id = 'color_select'+i;
						 get_color_options(m_id,item.metal_color);
					 });
					     $('#prod_metal_detail tbody').append(html); 
					   //load diamond information  
					      $.each(data.prod_dInfo, function (key, item) {
							var a = $("#d_increment").val();
							var i = ++a;
							$("#d_increment").val(i);
						   dhtml += "<tr id='d_detail"+i+"'><td>"+i+"</td><td><select name='d_detail["+i+"][id_cut]' style='width:100%;' class='form-control' id='cut_select"+i+"'></td>"+"<td><select name='d_detail["+i+"][id_dia_color]' style='width:100%;' class='form-control' id='dcolor_select"+i+"'></td>"+"<td><select name='d_detail["+i+"][id_clarity]' style='width:100%;' class='form-control' id='clarity_select"+i+"'></td>"+"<td><select style='width:100%;' name='d_detail["+i+"][id_carat]' id='carat_select"+i+"' class='form-control'></select></td>"+"<td><button type='button' class='btn btn-danger' onclick='dia_remove("+i+","+item.id_metal_details+")'><i class='fa fa-trash'></i></button></td>";	  
							var d_id = 'dcolor_select'+i; 
							 get_color_options(d_id,item.diamond_color);
							 get_cut_options('cut_select'+i,item.cut);
							 get_clarity_options('clarity_select'+i,item.clarity);
							 get_carat_options('carat_select'+i,item.carat);
						 });
					     $('#prod_dia_detail tbody').append(dhtml);  
				  },
			  	  error:function(error)  
				  {
					 $("div.overlay").css("display", "none"); 
				  }	 
	        });	
}
	function dia_remove(i,id = ""){
		var	rowId= "d_detail"+i;
		$('#'+rowId+'').remove();
		if(id){
			deleteProdDetail(id);
		}
	}
	function m_remove(i,id = ""){
		var	rowId= "detail"+i;
		$('#'+rowId+'').remove();
		if(id){
			deleteProdDetail(id);
		}
	}
	function deleteProdDetail(id){
		my_Date = new Date();
			$("div.overlay").css("display", "block"); 
			$.ajax({
				 url:base_url+"index.php/product/delect_prodDetail/"+id+"?nocache=" + my_Date.getUTCSeconds(),
				 type:"POST",
				 success:function(data){
				 			//	window.location.reload();
			   			 $("div.overlay").css("display", "none"); 
					  },
					  error:function(error)  
					  {
					  	alert('error');
						 $("div.overlay").css("display", "none"); 
					  }	 
			      });
		 }
	function remove_img(file) {
		var id =  $('#id_product').val();
				$("div.overlay").css("display", "block"); 
			$.ajax({
				   url:base_url+"index.php/admin_catalog/remove_img/"+file+"/"+id,
				   type : "POST",
				   success : function(result) {
				   	$("div.overlay").css("display", "none"); 
					  window.location.reload();
				   },
				   error : function(error){
					$("div.overlay").css("display", "none"); 
				   }
				});
			}
function get_tagging_list(from_date="",to_date="")
{
	my_Date = new Date();
	$("div.overlay").css("display", "block"); 	
	$.ajax({
		 url:base_url+"index.php/admin_ret_tagging/get_tagging_details?nocache=" + my_Date.getUTCSeconds(),
		 dataType:"JSON",
		 type:"POST",
		 data:{'from_date':from_date,'to_date':to_date},
		 success:function(data){
   			set_tag_list(data);
   			 $("div.overlay").css("display", "none"); 
		  },
		  error:function(error)  
		  {
			 $("div.overlay").css("display", "none"); 
		  }	 
	});
}
function set_tag_list(data)
{
    $("div.overlay").css("display", "none"); 
    var oTable = $('#tag_list').DataTable();
    oTable.clear().draw();
   	 if (data!= null && data.length > 0)
	 {
	        oTable = $('#tag_list').dataTable({
			"bDestroy": true,
			"bInfo": true,
			"bFilter": true, 
			"order": [[ 0, "desc" ]],
			"bSort": true,
			"dom": 'lBfrtip',
			"buttons" : ['excel','print'],
			 "lengthMenu": [ [ 10, 25, 50, -1], [10, 25, 50, "All"] ],
			"tableTools": { "buttons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },
			"aaData": data,
			"aoColumns": [
			           { "mDataProp": function ( row, type, val, meta ) {
					                	var url = base_url+'index.php/admin_ret_tagging/get_tag_detail_list/'+row.tag_lot_id;
					                	action = '<a href="'+url+'" target="_blank">'+row.tag_lot_id+'</a>';
					                	return action;
					                	}
					   },
						{ "mDataProp": "tag_date" },		
						{ "mDataProp": "gross_wt" },	
						{ "mDataProp": "net_wt" },	
						{ "mDataProp": "less_wt" },	
						{ "mDataProp": "piece" },
						
						] 
		});
    }
}
function lot_tag_detail(from_date="",to_date="")
{
	my_Date = new Date();
    var tag_lot_id=ctrl_page[2];
	$("div.overlay").css("display", "block"); 	
	$.ajax({
		 url:base_url+"index.php/admin_ret_tagging/lot_tag_detail?nocache=" + my_Date.getUTCSeconds(),
		 dataType:"JSON",
		 type:"POST",
		 data:{'from_date':from_date,'to_date':to_date,'tag_lot_id':tag_lot_id,'id_employee':$('#id_employee').val()},
		 success:function(data){
   			set_tagging_list(data);
   			 $("div.overlay").css("display", "none"); 
		  },
		  error:function(error)  
		  {
			 $("div.overlay").css("display", "none"); 
		  }	 
	});
}

$('#select_all').click(function(event) {
	  $("tbody tr td input[type='checkbox']").prop('checked', $(this).prop('checked'));
      event.stopPropagation();
});

$('#print_all').on('click',function(){
	window.open(base_url+'index.php/admin_ret_tagging/tagging/generate_all_qrcode?lot_id='+ctrl_page[2],'_blank');
	window.location.reload();
});

$("#tag_print").click(function(){
	if($("input[name='tag_id[]']:checked").val())
	{
		var selected = [];
		var tag_id='';
		$("#tagging_list tbody tr").each(function(index, value){
		if($(value).find("input[name='tag_id[]']:checked").is(":checked"))
		{
		tag_id+= $(value).find(".tag_id").val()+',';
		transData = { 
		'tag_id': $(value).find(".tag_id").val(),
		}
		}
		});
		req_data = tag_id;
		tagging_print(req_data);
	}
	else{
		alert('Please Select Atleast One Tag');
	}
});

function tagging_print(req_data)
{
	//var tag = JSON.stringify(req_data);
	window.open(base_url+'index.php/admin_ret_tagging/tagging/generate_barcode?tag='+req_data,'_blank');
	window.location.reload();
}

function set_tagging_list(data)	
{
   $("div.overlay").css("display", "none"); 
   var tagging = data.list;
   var access = data.access;
   var oTable = $('#tagging_list').DataTable();
   $("#total_tagging").text(tagging.length);
    if(access.add == '0')
	 {
		$('#add_product').attr('disabled','disabled');
	 }
	 oTable.clear().draw();
   	 if (tagging!= null && tagging.length > 0)
	 {
	 	oTable = $('#tagging_list').dataTable({
			"bDestroy": true,
			"bInfo": true,
			"bFilter": true, 
			"order": [[ 0, "desc" ]],
			"bSort": true,
			"dom": 'lBfrtip',
			 "lengthMenu": [ [ 10, 25, 50, -1], [10, 25, 50, "All"] ],
			"buttons" : ['excel','print'],
			"tableTools": { "buttons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },
			"aaData": tagging,
			"aoColumns": [
			           { "mDataProp": function ( row, type, val, meta ){ 
		                	chekbox='<input type="checkbox" class="tag_id" name="tag_id[]" value="'+row.tag_id+'"/><input type="hidden" class="net_wt" value="'+row.net_wt+'"><input type="hidden" class="product_name" value="'+row.product_name+'"><input type="hidden" class="size" value="'+row.size+'"><input type="hidden" class="short_code" value="'+row.short_code+'"><input type="hidden" class="product_id" value="'+row.product_id+'"><input type="hidden" class="tag_code" value="'+row.tag_code+'"><input type="hidden" class="code_karigar" value="'+row.code_karigar+'"><input type="hidden" class="tot_print_taken" value="'+row.tot_print_taken+'">' 
		                	if(row.tot_print_taken==0)
		                	{
		                		return chekbox+" "+row.tag_id;
		                	}else{
		                		return row.tag_id;
		                	}
		                }},
						{ "mDataProp": "tag_code" },		
						{ "mDataProp": "tag_date" },		
						{ "mDataProp": "tag_lot_id" },	
						{ "mDataProp": function ( row, type, val, meta ) {
								var unit = row.uom_short_code;
								//return (row.gross_wt ? row.gross_wt+' '+unit : '');
								return (row.gross_wt);
							}
						},
						{ "mDataProp": function ( row, type, val, meta ) {
								var unit = row.uom_short_code;
								//return (row.net_wt ? row.net_wt+' '+unit : '');
								return (row.net_wt);
							}
						},
						{ "mDataProp": function ( row, type, val, meta ) {
								var unit = row.uom_short_code;
								//return (row.less_wt ? row.less_wt+' '+unit : '');
								return (row.less_wt);
							}
						},	
						{ "mDataProp": "piece" },
						{ "mDataProp": "ref_no" },	
						{ "mDataProp": function ( row, type, val, meta ) {
							 id= row.tag_id;
							 edit_url=(access.edit=='1' ? base_url+'index.php/admin_ret_tagging/tagging/edit/'+id : '#' );
							 delete_url=(access.delete=='1' ? base_url+'index.php/admin_ret_tagging/tagging/delete/'+id : '#' );
							 delete_confirm= (access.delete=='1' ?'#confirm-delete':'');
							 print_url=(access.edit=='1' ? base_url+'index.php/admin_ret_tagging/tagging/generate_barcode/'+id : '#' );
							 action_content='<a href="#" class="btn btn-danger btn-del" data-href='+delete_url+' data-toggle="modal" data-target="#confirm-delete" ><i class="fa fa-trash"></i></a><a  href="'+print_url+'" target="_blank" class="btn btn-info btn-print" ><i class="fa fa-print" ></i></a>';
							return action_content;
						}
					}] 
		});	
	}  
}
function get_metal()
{
	$(".overlay").css('display','block');
	$.ajax({
		type: 'GET',
		url: base_url+'index.php/get/active_metals',
		dataType:'json',
		success:function(data){
		  var id_metal =  $('#id_metal').val();
		  console.log(data);
		   $.each(data, function (key, item) {
			   		$('#metal_select').append(
						$("<option></option>")
						  .attr("value", item.id_metal)
						  .text(item.metal)
					);
			});
			$("#metal_select").select2({
			    placeholder: "Select metal",
			    allowClear: true
			});
			$("#metal_select").select2("val",(id_metal!='' && id_metal>0?id_metal:''));
			 $(".overlay").css("display", "none");	
		}
	});
}
 //on selecting subcategory
   $('#metal_select').select2()
        .on("change", function(e) {
          if(this.value!='')
          {
          	 $("#id_metal").val(this.value);
		  }
   });
function get_received_lots(){
	$.ajax({		
	 	type: 'GET',		
	 	url : base_url + 'index.php/admin_ret_tagging/get_lot_ids',		
	 	dataType : 'json',		
	 	success  : function(data){
			lot_details = data;
		 	var id =  $('#tag_lot_id').val();			 	
		 	$.each(data.lot_inward, function (key, item) {				  			   		
	    	    if(item.lot_bal_pcs>0)
    		 	{
    		 		$("#tag_lot_received_id").append(						
    	    	 	$("<option></option>")						
    	    	 	.attr("value", item.lot_no)
    	    	 	.text(item.lot_no)						  					
    	    	 	);
    		 	}	 
	     	});
	     	$.each(data.lot_inward, function (key, item) {				  			   		
    		 		$("#lot_id").append(						
    	    	 	$("<option></option>")						
    	    	 	.attr("value", item.lot_no)
    	    	 	.text(item.lot_no)						  					
    	    	 	);
	     	});
	     	console.log(id);
	     	$("#tag_lot_received_id").select2("val",(id!='' && id>0?id:''));
	     	if($("#lot_id").length)
	     	{
	     	    $("#lot_id").select2("val",'');	
	     	}
	     	$(".overlay").css("display", "none");			
	 	}	
	}); 
}
function get_tag_types(){
	$.ajax({		
	 	type: 'GET',		
	 	url : base_url + 'index.php/admin_ret_tagging/get_tag_types',		
	 	dataType : 'json',		
	 	success  : function(data){
		 	var id =  $('#tag_type').val();			 	
		 	$.each(data, function (key, item) {				  			   		
	    	 	$("#select_tag_type").append(						
	    	 	$("<option></option>")						
	    	 	.attr("value", item.tag_id)
	    	 	.text(item.tag_name)						  					
	    	 	);	 
	     	});						
	     	$("#select_tag_type").select2("val",(id !='' && id > 0 ? id : ''));	 
	     	$(".overlay").css("display", "none");			
	 	}	
	}); 
}
function get_tag_taxgroups(){
	$.ajax({		
	 	type: 'GET',		
	 	url : base_url + 'index.php/admin_ret_tagging/getAvailableTaxGroups',		
	 	dataType : 'json',		
	 	success  : function(data){
		 	var id =  $('#tax_group_id').val();			 	
		 	$.each(data, function (key, item) {		   		
	    	 	$("#select_tax_group_id").append(						
	    	 	$("<option></option>")						
	    	 	.attr("value", item.tgrp_id)
	    	 	.text(item.tgrp_name)						  					
	    	 	);	 
	     	});
	     	$("#select_tax_group_id").select2("val",(id !='' && id > 0 ? id : ''));	 
	     	$(".overlay").css("display", "none");			
	 	}	
	}); 
}
function get_tag_stones(){
	$.ajax({		
	 	type: 'GET',		
	 	url : base_url + 'index.php/admin_ret_tagging/getStoneItems',
	 	dataType : 'json',		
	 	success  : function(data){
		 	tag_stones = data;	
			if(ctrl_page[2] == "edit"){
				load_tag_stone_list_on_edit();
			}
			console.log("tag_stones:", tag_stones);
	 	}	
	}); 
}
function get_tag_materials(){
	$.ajax({		
	 	type: 'GET',		
	 	url : base_url + 'index.php/admin_ret_tagging/getAvailableMaterials',
	 	dataType : 'json',		
	 	success  : function(data){
		 	tag_materials = data;
			if(ctrl_page[2] == "edit"){
				load_tag_material_list_on_edit();
			}
			console.log("tag_materials", tag_materials);
	 	}	
	}); 
}
function getSearchDesignNo(searchTxt){
	my_Date = new Date();
	$.ajax({
        url: base_url+'index.php/admin_ret_tagging/getDesignNosBySearch/?nocache=' + my_Date.getUTCSeconds(),             
        dataType: "json", 
        method: "POST", 
        data: {'searchTxt': searchTxt}, 
        success: function (data) { 
			$( "#tag_design_no" ).autocomplete(
			{
				source: data,
				select: function(e, i)
				{ 
					e.preventDefault();
					$("#tag_design_no").val(i.item.label); 
					$("#design_id").val(i.item.value); 
					if(ctrl_page[2]!='bulk_edit')
					{
						getDesignStoneDetails(i.item.value);
						getDesignMaterialsByDesignId(i.item.value);
					}
					if(ctrl_page[2]=='bulk_edit')
					{
						var  retail_max_wastage_percent=$('#wastage_percent').val();
						var  tag_mc_value=$('#mc_value').val();
						var  design_id=$('#design_id').val();
		
					}
				},
				change: function (event, ui) {
					if (ui.item === null) {
						$(this).val('');
						$('#tag_design_no').val('');
						$("#design_id").val(""); 
						if($("#design_id").val() == "" || $("#tag_design_no").val() == ""){
							/* $("#create_stn_details").prop('disabled', false);
							$("#create_material_details").prop('disabled', false); */
						}
					}
			    },
				response: function(e, i) {
		            // ui.content is the array that's about to be sent to the response callback.
		            if(searchTxt != ""){
						if (i.content.length === 0) {
						   $("#designAlert").html('<p style="color:red">Enter a valid design code</p>');
						   //$("#tag_design_no" ).val(""); 
						   if($("#design_id").val() == "" || $("#tag_design_no").val() == ""){
								/* $("#create_stn_details").prop('disabled', false);
								$("#create_material_details").prop('disabled', false); */
							}
						}else{
						   $("#designAlert").html('');
						   /* $("#create_stn_details").prop('disabled', true);
						   $("#create_material_details").prop('disabled', true); */
						} 
					}else{
						/* $("#create_stn_details").prop('disabled', false);
						$("#create_material_details").prop('disabled', false); */
					}
		        },
				 minLength: 3,
			});
        }
     });
}
function getDesignStoneDetails(designId)
{
	my_Date = new Date();
	$.ajax({
        url: base_url+'index.php/admin_ret_tagging/getDesignStonesByDesignId/?nocache=' + my_Date.getUTCSeconds(),             
        dataType: "json", 
        method: "POST", 
        data: { 'designId': designId }, 
        success: function ( data ) { 
			tag_design_stones = data;
			/* console.log(data);
			var html = '';
			$.each(data, function(key, item){
				html += '<tr><td><input type="hidden" name="tagstone[stone_id][]" value="'+item.stone_id+'" />'+item.stone_name+'</td><td><div class="input-group"> <input class="form-control" type="number" step="any" name="tagstone[pcs][]" value="'+item.stone_pcs+'" required /></div></td><td><div class="input-group"><input type="number" class="form-control" step="any" name="tagstone[weight][]" value="" required /></div></td><td><input type="hidden" name="tagstone[uom_id][]" value="'+item.uom_id+'" />'+item.uom_short_code+'</td><td><div class="input-group"> <input class="form-control" type="number" step="any" name="tagstone[amount][]" value="" required /></div></td></tr>';
			});
			$('#tagging_stone_details tbody').append(html); */
        }
     });
}
function getDesignMaterialsByDesignId(designId)
{
	my_Date = new Date();
	$.ajax({
        url: base_url+'index.php/admin_ret_tagging/getDesignMaterialsByDesignId/?nocache=' + my_Date.getUTCSeconds(),             
        dataType: "json", 
        method: "POST", 
        data: { 'designId': designId }, 
        success: function ( data ) { 
			tag_design_materials = data;
			/* console.log(data);
			var html = '';
			$.each(data, function(key, item){
				html += '<tr><td><input type="hidden" name="tagmaterials[material_id][]" value="'+item.material_id+'" required />'+item.material_name+'</td><td><div class="input-group"> <input class="form-control" type="number" step="any" name="tagmaterials[weight][]" value="" required /></div></td><td><input type="hidden" name="tagmaterials[uom_id][]" value="'+item.uom_id+'" />'+item.uom_short_code+'</td><td><div class="input-group"> <input class="form-control" type="number" step="any" name="tagmaterials[amount][]" value="" required /></div></td><td></td></tr>';
			});
			$('#tagging_material_details tbody').append(html); */
        }
     });
}
function load_tag_stone_list_on_edit()
{
	my_Date = new Date();
	$.ajax({
        url: base_url+'index.php/admin_ret_tagging/getTagStoneByTagId/?nocache=' + my_Date.getUTCSeconds(),             
        dataType: "json", 
        method: "POST", 
        data: { 'tagId': $("#tag_id").val() }, 
        success: function ( data ) { 
			console.log(data);
			var html = '';
			$.each(data, function(ekey, eitem){
				var select_op = '<select class="form-control select_stn_det" id="tagstone_'+(ekey+1)+'" name="tagstone[stone_id][]"><option value=""> - Select Stone - </option>';
			var selected_stones = [];
			var op_length = 0;
			$('#tagging_stone_details > tbody  > tr').each(function(index, tr) {
				selected_stones.push({ "st_id" : $(this).find('td:first   .select_stn_det').val()});
			});
			$.each(tag_stones, function(key, item){
				var $exist_flag = false;
				$.each(selected_stones, function(stkey, stval){
					if(stval.st_id == item.stone_id){
						$exist_flag = true;
					}
				});
				if(!$exist_flag){
					var selected = "";
					if(item.stone_id == eitem.stone_id){
						selected = "selected='selected'";
					}
					select_op += '<option value="'+item.stone_id+'" '+selected+'>'+item.stone_name+'</option>';
					op_length++;
				}
			});
			select_op += '</select>';
			html += '<tr><td>'+select_op+'</td><td><div class="input-group"> <input class="form-control tagstone_pcs" type="number" step="any" name="tagstone[pcs][]" value="'+eitem.pieces+'" required /></div></td><td><div class="input-group"><input type="number" class="form-control tagstone_wt" step="any" name="tagstone[weight][]" value="'+eitem.wt+'" required /></div></td><td><input type="hidden" name="tagstone[uom_id][]" value="'+eitem.uom_id+'" /><div class="stn_uom">'+eitem.uom_name+'</div></td><td><div class="input-group"> <input class="form-control tagstone_amt" type="number" step="any" name="tagstone[amount][]" value="'+eitem.amount+'" required /></div></td></tr>';
				$('#tagging_stone_details tbody').append(html);
			});
        }
     });
}
function load_tag_material_list_on_edit()
{
	my_Date = new Date();
	$.ajax({
        url: base_url+'index.php/admin_ret_tagging/getTagMaterialByTagId/?nocache=' + my_Date.getUTCSeconds(),             
        dataType: "json", 
        method: "POST", 
        data: { 'tagId': $("#tag_id").val() }, 
        success: function ( data ) { 
			console.log(data);
			$.each(data, function(ekey, eitem){
				var html = "";
				var select_op = '<select class="form-control select_mat_det" id="tagmat_'+(ekey+1)+'" name="tagmaterials[material_id][]"><option value=""> - Select Material - </option>';
			var selected_materials = [];
			var op_length = 0;
			$('#tagging_material_details > tbody  > tr').each(function(index, tr) {
				selected_materials.push({ "mat_id" : $(this).find('td:first .select_mat_det').val()});
			});
			$.each(tag_materials, function(key, item){
				var $exist_flag = false;
				$.each(selected_materials, function(stkey, stval){
					if(stval.mat_id == item.material_id){
						$exist_flag = true;
					}
				});
				if(!$exist_flag){
					var selected = "";
					if(item.material_id == eitem.material_id){
						selected = "selected='selected'";
					}
					select_op += '<option value="'+item.material_id+'" '+selected+'>'+item.material_name+'</option>';
					op_length++;
				}
			});
			select_op += '</select>';
				html += '<tr><td>'+select_op+'</td><td><div class="input-group"> <input class="form-control tagmat_wt" type="number" step="any" name="tagmaterials[weight]" value="'+eitem.wt+'" required /></div></td><td><input type="hidden" name="tagmaterials[uom_id]" value="'+eitem.uom_id+'" /><div class="stn_uom">'+eitem.uom_name+'</div></td><td><div class="input-group"> <input class="form-control tagmat_amt" type="number" step="any" name="tagmaterials[amount][]" value="'+eitem.price+'" required /></div></td></tr>';
				$('#tagging_material_details tbody').append(html);
			});
        }
     });
}
function validateStoneDetailRow(){
	var st_validate = true;
	$('#tagging_stone_details > tbody  > tr').each(function(index, tr) {
		if($(this).find('td:first .select_stn_det').val() == "" || $(this).find('td:eq(4) div:eq(0) .tagstone_amt').val() == "" || $(this).find('td:eq(1) div:eq(0) .tagstone_pcs').val() == "" || $(this).find('td:eq(2) div:eq(0) .tagstone_wt').val() == "" ){
			st_validate = false;
		}
	});
	return st_validate;
}
function validateMaterialDetailRow(){
	var mt_validate = true;
	$('#tagging_material_details > tbody  > tr').each(function(index, tr) {
		if($(this).find('td:first .select_mat_det').val() == "" || $(this).find('td:eq(1) div:eq(0) .tagmat_wt').val() == "" || $(this).find('td:eq(3) div:eq(0) .tagmat_amt').val() == "" ){
			mt_validate = false;
		}
	});
	return mt_validate;
}
//bulk edit
$('#branch_select').on('change',function(){
	if(ctrl_page[2]=='tag_mark')
	{
		if(this.value!='')
		{
			$('#id_branch').val(this.value);
		}else{
			$('#id_branch').val('');
		}
	}
	
	else{
		$("#tag_id").val(''); 
		$("#tag_no").val(''); 
		if(this.value!='')
		{
				$('#id_branch').val(this.value);
		}
		else
		{
			$('#id_branch').val();
		}
		if(this.value!='' && $('#id_mc_type').val()!='')
		{
			$('#get_tag_details').prop('disabled',false);
		}
		else
		{
			$('#get_tag_details').prop('disabled',true);
		}
	}
	
}); 
$('#mc_type').on('change',function(){
if(this.value!='')
{
	$('#id_mc_type').val(this.value);
}
else
{
	$('#id_mc_type').val('');
}
if($('#id_branch').val()!='' && $('#id_mc_type').val()!='')
	{
		$('#get_tag_details').prop('disabled',false);
	}
	else
	{
		$('#get_tag_details').prop('disabled',true);
	}
});
function get_tag_number(tag_id)
{
	my_Date = new Date();
	var tag_lot_id=$('#tag_lot_id').val();
	var id_branch=$('#id_branch').val();
	$.ajax({
        url: base_url+'index.php/admin_ret_tagging/get_tag_number?nocache=' + my_Date.getUTCSeconds(),             
        dataType: "json", 
        method: "POST", 
        data:{'tag_lot_id':tag_lot_id,'id_branch':id_branch,'tag_id':tag_id},
        success: function (datas) { 
			$( "#tag_no" ).autocomplete(
			{
				source: datas,
				select: function(e, i)
				{ 
					e.preventDefault();
					console.log(i);
					$("#tag_id").val(i.item.value); 
					$("#designAlert").html('');
				},
				change: function (event, ui) {
					if(ui.item==null)
					{
							$("#tag_id").val(''); 
							$("#designAlert").html('');
					}
			    },
				response: function(e, i) {
					if(i.content.length==0)
					{
					   $("#designAlert").html('<p style="color:red">Enter a valid Tag No</p>')
					}
		            // ui.content is the array that's about to be sent to the response callback.
		        },
			});
        }
     });
}
function getSearchProd(searchTxt){
	var tag_lot_id=$('#tag_lot_id').val();
	var id_branch=$('#id_branch').val();
	var tag_id=$('#tag_id').val();
	my_Date = new Date();
	$.ajax({
        url: base_url+'index.php/admin_ret_tagging/get_prod_by_tagno?nocache=' + my_Date.getUTCSeconds(),             
        dataType: "json", 
        method: "POST", 
        data: {'prod_name':searchTxt,'tag_lot_id':tag_lot_id,'id_branch':id_branch,'tag_id':tag_id}, 
        success: function (data) { 
			$( "#prod_name" ).autocomplete(
			{
				source: data,
				select: function(e, i)
				{ 
					e.preventDefault();
					$("#prod_name" ).val(i.item.label); 
					$('#lot_product').val(i.item.value);
				},
				response: function(e, i) {
		            // ui.content is the array that's about to be sent to the response callback.
		            console.log(i);
		            if (i.content.length === 0) {
		               $("#prodAlert").html('<p style="color:red">Enter a valid Product</p>'); 
		            }else{
						$("#prodAlert").html('');
					} 
		        },
				 minLength: 0,
			});
        }
     });
}



function getSearchDes(searchTxt){
	my_Date = new Date();
	$.ajax({
        url: base_url+'index.php/admin_ret_tagging/getDesignNosBySearch?nocache=' + my_Date.getUTCSeconds(),             
        dataType: "json", 
        method: "GET",
        success: function (data) { 
			$( "#des_name" ).autocomplete(
			{
				source: data,
				select: function(e, i)
				{ 
					e.preventDefault();
					$("#des_name" ).val(i.item.label); 
					$("#id_design" ).val(i.item.value); 
				},
				response: function(e, i) {
		            // ui.content is the array that's about to be sent to the response callback.
		            console.log(i);
		            if (i.content.length === 0) {
		               $("#desdAlert").html('<p style="color:red">Enter a valid Design</p>'); 
		            }else{
						$("#desdAlert").html('');
					} 
		        },
				 minLength: 0,
			});
        }
     });
}


$('#get_tag_details').on('click',function(){
     $("div.overlay").css("display", "block"); 
	var tag_lot_id=$('#tag_lot_id').val();
	var id_branch=$('#id_branch').val();
	var tag_id=$('#tag_id').val();
	var lot_product=$('#lot_product').val();
	var from_weight=$('#from_weight').val();
	var to_weight=$('#to_weight').val();
	var id_mc_type=$('#id_mc_type').val();
	var mc_value=$('#old_mc_value').val();
	var making_per=$('#old_mc_per').val();
	var making_per=$('#old_mc_per').val();
	var id_design=$('#id_design').val();
	
	my_Date = new Date();
	$.ajax({
        url: base_url+'index.php/admin_ret_tagging/get_tag_details?nocache=' + my_Date.getUTCSeconds(),             
        dataType: "json", 
        method: "POST", 
        data: {'id_branch':id_branch,'tag_id':tag_id,'id_mc_type':id_mc_type,'lot_product':lot_product,'from_weight':from_weight,'to_weight':to_weight,'mc_value':mc_value,'making_per':making_per,'id_design':id_design}, 
        success: function (data) { 
			set_tagedit_list(data);
			$('#editable_block').css('display','block');
			$("div.overlay").css("display", "none"); 
        }
     });
});
$('#select_all').click(function(event) {
		$("tbody tr td input[type='checkbox']").prop('checked', $(this).prop('checked'));
      event.stopPropagation();
    });
function set_tagedit_list(data)
{
   var oTable = $('#tagging_list').DataTable();
	 oTable.clear().draw();
	 	oTable = $('#tagging_list').dataTable({
			"bDestroy": true,
			"bInfo": true,
			"order": [[ 0, "desc" ]],
			"bFilter": true, 
			"bSort": true,
			"dom": 'lBfrtip',
			"buttons" : ['excel','print'],
			"lengthMenu": [ [ 10, 25, 50, -1], [10, 25, 50, "All"] ],
			"tableTools": { "buttons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },
			"aaData": data,
			"aoColumns": [ { "mDataProp": function ( row, type, val, meta ){ 
		                	chekbox='<input type="checkbox" class="tag_id" name="tag_id[]" value="'+row.tag_id+'"/><input type="hidden" class="tag_mc_value" value="'+row.tag_mc_value+'"/><input type="hidden" class="retail_max_wastage_percent" value="'+row.retail_max_wastage_percent+'"/><input type="hidden" class="calculation_based_on" value="'+row.calculation_based_on+'"><input type="hidden" class="gross_wt" value="'+row.gross_wt+'"/><input type="hidden" class="net_wt" value="'+row.net_wt+'"><input type="hidden" class="less_wt" value="'+row.less_wt+'"/><input type="hidden" class="no_of_piece" value="'+row.piece+'"/><input type="hidden" class="id_mc_type" value="'+row.tag_mc_type+'"/><input type="hidden" class="stone_price" value="'+row.stone_price+'"><input type="hidden" class="tgi_calculation" value="'+row.tax_group_id+'"><input type="hidden" class="sell_rate" value="'+row.sales_value+'"><input type="hidden" class="metal_rate" value="'+row.metal_rate['goldrate_22ct']+'">' 
		                	return chekbox+" "+row.tag_id;
		                }},
					    { "mDataProp": "tag_code" },
					    { "mDataProp": "product_name" },
						{ "mDataProp": "design_name" },		
						{ "mDataProp": "tag_datetime" },		
						{ "mDataProp": "gross_wt" },
						{ "mDataProp": "less_wt" },		
						{ "mDataProp": "net_wt" },	
						{ "mDataProp": "retail_max_wastage_percent" },	
						{ "mDataProp": "mc_type" },	
						{ "mDataProp": "tag_mc_value" },
						{ "mDataProp": "sales_value" },
						] 
		});	
}
$('#bulk_edit').on('click',function(){
    if($("input[name='tag_id[]']:checked").val())
    {
	    $("#bulk_edit").prop('disabled',true);
	    $(".overlay").css('display','none');
	    var selected = [];
	    var allow_update=true;
		$("#tagging_list tbody tr").each(function(index, value){
		if($(value).find("input[name='tag_id[]']:checked").is(":checked"))
		{
            var disc_limit_type=$('#disc_limit_type').val();
            var total_price = 0;
            var base_value_price = 0;
            var arrived_value_price = 0;
            var base_value_tax = 0;
            var arrived_value_tax = 0;
            var base_rate_tax = 0;
            var arrived_rate_tax = 0;
            var total_tax_per = 0;
            var total_tax_rate = 0;
	var gross_wt            =  (($(value).find(".gross_wt").val() == '')  ? 0 : $(value).find(".gross_wt").val());
	
	var less_wt             =  (($(value).find(".less_wt").val() == '')  ? 0 : $(value).find(".less_wt").val());
	
	var net_wt              =  (($(value).find(".net_wt").val() == '')  ? 0 : $(value).find(".net_wt").val());
	
	var id_mc_type          =  (($(value).find(".id_mc_type").val() == '')  ? 0 : $(value).find(".id_mc_type").val());
	
	var no_of_piece         =  (($(value).find(".no_of_piece").val() == '')  ? 0 : $(value).find(".no_of_piece").val());
	
	var stone_price         =  $(value).find(".stone_price").val();
	
	var calculation_type    =  $(value).find(".calculation_based_on").val();
	
	var rate_per_grm        =  $(value).find(".metal_rate").val();
	
	var sell_rate           =  $(value).find(".sell_rate").val();
	
	var wastage_percent     =  $(value).find(".retail_max_wastage_percent").val();
	
	var tag_mc_value     =  $(value).find(".tag_mc_value").val();
	
	var retail_max_mc        = $('#mc_value').val();
	
	var edit_wast_per          = $('#wastage_percent').val();
	
	var edit_mc_type         = $('#update_mc_type').val();
	
	var mc_type              = (edit_mc_type!='' && edit_mc_type!=null && edit_mc_type!=undefined ? edit_mc_type:id_mc_type);
	
	var tot_wastage              = (edit_wast_per!='' && edit_wast_per!=null && edit_wast_per!=undefined ? edit_wast_per:wastage_percent);
	
	var mc_value              = (retail_max_mc!='' && retail_max_mc!=null && retail_max_mc!=undefined ? retail_max_mc:tag_mc_value);
	
    	if(calculation_type == 0){ 
    		var wast_wgt      = parseFloat(parseFloat(gross_wt) * parseFloat(tot_wastage/100)).toFixed(3);
    		if(mc_type != 3){
        		var making_charge       =  parseFloat(mc_type == 2 ? parseFloat(mc_value * gross_wt ) : parseFloat(mc_value * no_of_piece));
        		// Metal Rate + Stone + OM + Wastage + MC
        		rate_with_mc = parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(making_charge)+parseFloat(stone_price));
    		}else{
    		    var making_charge  = parseFloat((parseFloat(gross_wt) * parseFloat(rate_per_grm)) * parseFloat(mc_value/100)).toFixed(3);
    		    rate_with_mc       =  parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(making_charge)+parseFloat(stone_price));
    		}
    	}
    	else if(calculation_type == 1){
    		var wast_wgt      = parseFloat(parseFloat(net_wt) * parseFloat(tot_wastage/100)).toFixed(3);
    		if(mc_type != 3){
        		var making_charge       =  parseFloat(mc_type == 2 ? parseFloat(mc_value * net_wt ) : parseFloat(mc_value * no_of_piece));
        		// Metal Rate + Stone + OM + Wastage + MC
        		rate_with_mc = parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(making_charge)+parseFloat(stone_price));
    		}else{
    		    var making_charge  = parseFloat((parseFloat(net_wt) * parseFloat(rate_per_grm)) * parseFloat(mc_value/100)).toFixed(3);
    		    rate_with_mc       =  parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(making_charge)+parseFloat(stone_price));
    		}
    	}
    	else if(calculation_type == 2){ 
    		var wast_wgt      = parseFloat(parseFloat(net_wt) * parseFloat(tot_wastage/100)).toFixed(3);
    		if(mc_type != 3){
        		var making_charge       =  parseFloat(mc_type == 2 ? parseFloat(mc_value * gross_wt ) : parseFloat(mc_value * no_of_piece));
        		// Metal Rate + Stone + OM + Wastage + MC
        	    rate_with_mc = parseFloat((parseFloat(rate_per_grm) * (parseFloat(wast_wgt) + parseFloat(net_wt)))+ parseFloat(making_charge))+parseFloat(stone_price);
    		}else{
    		    var making_charge  = parseFloat((parseFloat(gross_wt) * parseFloat(rate_per_grm)) * parseFloat(mc_value/100)).toFixed(3);
    		    rate_with_mc = parseFloat((parseFloat(rate_per_grm) * (parseFloat(wast_wgt) + parseFloat(net_wt)))+ parseFloat(making_charge))+parseFloat(stone_price);
    		}
    	}
    	else if(calculation_type == 3 || calculation_type == 4){ 
    	    	var sell_rate  = (isNaN(sell_rate) || sell_rate == '')  ? 0 : sell_rate;
        		var adjusted_item_rate  = (isNaN($('.adjusted_item_rate').val()) || $('.adjusted_item_rate').val() == '')  ? 0 : curRow.find('.adjusted_item_rate').val();
        	    caculated_item_rate = (parseFloat(sell_rate)*parseFloat(net_wt))*parseFloat(no_of_piece);
        	    $("#caculated_item_rate").val(caculated_item_rate); 
        	    rate_with_mc = parseFloat(parseFloat(adjusted_item_rate) > 0 ? parseFloat(adjusted_item_rate) : caculated_item_rate ); 
    	}

	
/*	console.log('Calculation : '+calculation_type);
	console.log('Wastage : '+wast_wgt);
	console.log('Total Wastage : '+tot_wastage);
	console.log('MC : '+mc_type);
	console.log('Rate with MC : '+rate_with_mc);
	console.log(' MC TYPE : '+mc_type);
	console.log(' Rate Per Gram : '+rate_per_grm);
	console.log(' Total Price : '+total_price);*/
			//calculate sale value
			transData = { 
			'tag_id'   		:  $(value).find(".tag_id").val(),
			'sales_value'   :  rate_with_mc,
			'tot_wastage'   :  wast_wgt,
			'retail_max_wastage_percent'   :  tot_wastage,
			'tag_mc_value'  :  mc_value,
			'id_mc_type'    :  mc_type
			}
			selected.push(transData);
		}
		})
		req_data = selected;
		if(allow_update)
		{
				update_tagging_data(req_data);
		}
	}
	else
	{
	alert('Please Select Tag');
	$("#bulk_edit").prop('disabled',false);
	}
});
function update_tagging_data(req_data)
{
	console.log(req_data);
	my_Date = new Date();
	var design_id = $('#design_id').val();
	var tag_otp = $('#tag_otp').val();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_tagging/update_tagging_data?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),
			 data:  {'req_data':req_data,'tag_otp':tag_otp},
			 type:"POST",
			 dataType: "json", 
			 async:false,
			 	  success:function(data){
			 	  	if(data.status)
			 	  	{
			 	  			alert(data.msg);
			 	    		window.location.reload();
			 	  	}
			 	  	else
			 	  	{
			 	  		alert(data.msg);
			 	  		$('#bulk_edit').prop('disabled',false);
			 	  		$('#tag_otp').val('');
			 	  	}

				  },
				  error:function(error)  
				  {
					 $("div.overlay").css("display", "none"); 
				  }	 
		  });
}
function get_metal_rates_by_branch(id_branch)
{
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_tagging/get_metal_rates_by_branch?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),
			 data:  {'id_branch':id_branch},
			 type:"POST",
			 dataType: "json", 
			 async:false,
			 	  success:function(data){
			 	  	$('.tag-per-grm-sale-value').html(data.goldrate_22ct);
			 	  	$('#metal_rate').val(data.goldrate_22ct);
			 	  	$('#silverrate_1gm').val(data.silverrate_1gm);
			 	  	setTimeout(function(){
			 	  	  //  calculateSaleValue();
			 	  	},1000);
				  },
				  error:function(error)  
				  {
					 $("div.overlay").css("display", "none"); 
				  }	 
		  });
}
$('#otp_submit').on('click',function(){
    if($("input[name='tag_id[]']:checked").val())
    {
            $(".overlay").css('display','block'); 
            $('#otp_submit').prop('disabled',true);
            	my_Date = new Date();
            	$.ajax({
            			 url:base_url+ "index.php/admin_ret_tagging/admin_approval?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),
            			 type:"POST",
            			 dataType: "json", 
            			 async:false,
            			 	  success:function(data){
            			 	  	if(data.status)
            			 	  	{
            			 	  	    if(data.sms_req==1)
            			 	  	    {
            			 	  	        $('#otp_modal').modal('show');
            			 	  	    }else{
            			 	  	        
            			 	  	        $("#bulk_edit").trigger('click');
            			 	  	    }
            			 	  	}
            			 	  	else
            			 	  	{
            			 	  		$('#tag_otp').prop('disabled',true);
            			 	  	    alert(data.msg);
            			 	  	}
            			 	
            				  },
            				  error:function(error)  
            				  {
            					 $(".overlay").css('display',"none"); 
            				  }	 
            		  });
    }else{
        alert('Please Select Any One Tag');
    }
});

$('#close_modal').on('click',function(){
    $('#otp_modal').modal('toggle');
    $('#otp_submit').prop('disabled',false);
});
$('#tag_otp').on('keyup',function(){
	$("#otp_alert").html('');
	if(this.value.length>6)
	{
		$("#otp_alert").html('<p style="color:red">Please Enter Valid Number</p>');
		$('#bulk_edit').prop('disabled',true);
	}
	else
	{
		$('#bulk_edit').prop('disabled',false);
	}
});
$('#resendotp').on('click',function(){
$(".overlay").css('display','block'); 
$('#otp_submit').prop('disabled',true);
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_tagging/resendotp?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),
			 type:"POST",
			 dataType: "json", 
			 async:false,
			 	  success:function(data){
			 	    alert(data.msg);  
				  },
				  error:function(error)  
				  {
					 $(".overlay").css('display',"none"); 
				  }	 
		  });
});
//bulk edit
$(document).on('keyup',".tagstone_amt",function(){
    calculateSaleValue();    
});
//Employee Filter
	function get_employee(id_branch)
	{
		my_Date = new Date();
		$.ajax({ 
		url:base_url+ "index.php/admin_ret_estimation/get_employee?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),
        data: {'id_branch':id_branch},
        type:"POST",
        dataType:"JSON",
        success:function(data)
        {
           var id_employee=$('#id_employee').val();
           emp_details=data;
           $.each(data, function (key, item) {					  				  			   		
                	 	$("#emp_select").append(						
                	 	$("<option></option>")						
                	 	.attr("value", item.id_employee)						  						  
                	 	.text(item.firstname)						  					
                	 	);			   											
                 	});						
             	$("#emp_select").select2({			    
            	 	placeholder: "Select Employee",			    
            	 	allowClear: true		    
             	});					
         	    $("#emp_select").select2("val",(id_employee!='' && id_employee>0?id_employee:''));	 
         	    $(".overlay").css("display", "none");	
        },
        error:function(error)  
        {	
        } 
    	});
	}
	$('#emp_select').on('change',function(){
		if(this.value!='')
		{
			$('#id_employee').val(this.value);
			if(ctrl_page[1]=='get_tag_detail_list')
			{
			    lot_tag_detail();
			}
		}
		else
		{
			$('#id_employee').val('');
		}
	});
//Employee Filter
//Branch filter
$("#branch_select,#current_branch").on('change',function(){
	if(this.id == "branch_select"){
		if(this.value!='')
		{
			$('#id_branch').val(this.value);
		}
		else
		{
			$('#id_branch').val('');
		}
	}else{
		if(this.value!='')
		{
			$('#current_branch_id').val(this.value);
		}
		else
		{
			$('#current_branch_id').val('');
		}
	}
	
});
//Branch filter
//Lot Products
function get_lot_products(searchTxt)
{
	
	var prod_details=[];
	var tag_lot_id=$('#tag_lot_id').val();
	$('#tag_lt_prod').html('');
	$('#tag_lt_design').html('');
	my_Date = new Date();
		$.ajax({ 
		url:base_url+ "index.php/admin_ret_tagging/get_lot_products?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),
        data: {'lot_no':tag_lot_id,'searchTxt':searchTxt},
        type:"POST",
        dataType:"JSON",
        success:function(data)
        {	
       		if(data[0].order_no=='')
       		{
       				$(".overlay").css("display", "block");	
       				$('#tag_lt_prod').prop('disabled',false);
					$('#tag_lt_design').prop('disabled',false);
					var tag_lt_prodId=$('#tag_lt_prodId').val();
					prod_details=data;
					var gross_wt=0;
					var gorss_pcs=0;
					var precious_st_wt=0;
					var precious_st_pcs=0;
					var semi_precious_st_wt=0;
					var semi_precious_st_pcs=0;
					var normal_st_wt=0;
					var normal_st_pcs=0;
					$.each(data, function (key, item) {
					    gross_wt +=parseFloat(item.gross_wt);
					    gorss_pcs += parseFloat(item.no_of_piece);
					    precious_st_wt +=parseFloat(item.precious_st_wt);
					    precious_st_pcs +=parseFloat(item.precious_st_pcs);
					    semi_precious_st_wt +=parseFloat(item.semi_precious_st_wt);
					    semi_precious_st_pcs +=parseFloat(item.semi_precious_st_pcs);
					    normal_st_wt +=parseFloat(item.normal_st_wt);
					    normal_st_pcs +=parseFloat(item.normal_st_pcs);
					$("#tag_lt_prod").append(						
					$("<option></option>")						
					.attr("value", item.lot_product)						  						  
					.text(item.product_name)						  					
					);			   											
					});						
					$("#tag_lt_prod").select2({			    
					placeholder: "Select Product",			    
					allowClear: true		    
					});					
					$("#tag_lt_prod").select2("val",(tag_lt_prodId!='' && tag_lt_prodId>0?tag_lt_prodId:''));	
					$(".overlay").css("display", "none");
					$('#lot_bal_wt').val(gross_wt);
					$('#lot_bal_pcs').val(gorss_pcs);
					$('#lot_bal_prec_wt').val(precious_st_wt);
					$('#lot_bal_prec_pcs').val(precious_st_pcs);
					$('#lot_bal_semi_pre_wt').val(semi_precious_st_wt);
					$('#lot_bal_semi_pre_pcs').val(semi_precious_st_pcs);
					$('#lot_bal_normal_wt').val(normal_st_wt);
					$('#lot_bal_normal_pcs').val(normal_st_pcs);
		}
			else
			{
				$(".overlay").css("display", "block");	
				my_Date = new Date();
				$.ajax({
				url: base_url+'index.php/admin_ret_tagging/get_order_details?nocache=' + my_Date.getUTCSeconds(),             
				dataType: "json", 
				method: "POST", 
				data: {'lot_no': tag_lot_id}, 
				success: function (data) {
				if(data)
				{
				$('#tag_lt_prod').prop('disabled',true);
				$('#tag_lt_design').prop('disabled',true);
				//$('#add_more_tag').prop('disabled',true);
				var row='';
				var gross_wt=0;
				var lot_pcs=0;
				var id_orderdetails='';
				row_exist=false;
				var total_row=$('#lt_item_list tbody > tr').length;
					$.each(data,function(key,item){
						$('#lt_item_list> tbody  > tr').each(function(index, tr) { 
								if($(this).find('.id_orderdetails').val() == item.id_orderdetails){
									row_exist = true;
									alert('Tag Already Exists');
									return false;
								}
								});
						});
						if(!row_exist)
						{
						$.each(data,function(key,item){
							gross_wt+=parseFloat(item.gross_wt);
							lot_pcs+=parseFloat(item.no_of_piece);
								row += '<tr id='+item.id_lot_inward_detail+' class='+total_row+'>' 
								+'<td width="5%">'+item.lot_no+'<input type="hidden" name="lt_item[lot_no][]" value="'+item.lot_no+'" class="lot_no" /><input type="hidden" name="lt_item[id_lot_inward_detail][]" id="id_lot_inward_detail" value="'+item.id_lot_inward_detail+'" class="id_lot_inward_detail"><input type="hidden" name="lt_item[sales_mode][]" id="sales_mode" value="'+item.sales_mode+'" class="sales_mode"><input type="hidden" name="lt_item[id_orderdetails][]" class="id_orderdetails" value="'+item.id_orderdetails+'"><input type="hidden" class="stn_amt" value="'+item.stn_amt+'"></td>'
								+'<td width="10%">'+item.product_name+'<input type="hidden" name="lt_item[lot_product][]" value="'+item.id_product+'" class="lot_product" /><input type="hidden" name="lt_item[product_short_code][]" value="'+item.product_short_code+'" class="product_short_code" /></td>'
								+'<td width="10%">'+item.design_name+'<input type="hidden" name="lt_item[lot_id_design][]" value="'+item.design_no+'" class="lot_id_design" /></td>'
								+'<td width="10%">'+(item.design_for==1 ? 'Men' :(item.design_for==2 ? 'Female':'Unisex'))+'<input type="hidden" name="lt_item[design_for][]" value="'+item.design_for+'" class="design_for" /></td>'
								+'<td width="10%"><select class="calculation_based_on" name="lt_item[calculation_based_on][]"><option value="0" '+(item.calculation_based_on == 0 ? "selected":"")+' '+(item.calculation_based_on >= 3 ? "disabled":"")+'>Mc & Wast On Gross</option><option value="1" '+(item.calculation_based_on == 1 ? "selected":"")+' '+(item.calculation_based_on >= 3 ? "disabled":"")+'>Mc & Wast On Net</option><option value="2" '+(item.calculation_based_on == 2 ? "selected":"")+' '+(item.calculation_based_on >= 3 ? "disabled":"")+'>Mc on Gross,Wast On Net</option><option value="3" '+(item.calculation_based_on == 3 ? "selected":"")+' '+(item.calculation_based_on == 4 ? "disabled":"")+'>Fixed Rate</option><option value="4" '+(item.calculation_based_on == 4 ? "selected":"")+' '+(item.calculation_based_on == 3 ? "disabled":"")+'>Fixed Rate based on Weight</option></select></td>'
								+'<td width="5%"><input type="number" step="any" name="lt_item[no_of_piece][]"   class="no_of_piece" value="1" style="width:80px;" read/><span class="blc_pcs"></span><input type="hidden" disabled class="act_blc_pcs" value="'+item.no_of_piece+'" style="width:80px;" readonly></td>'
								+'<td width="10%"><input type="number" step="any" name="lt_item[gross_wt][]"   class="gross_wt" style="width:80px;" value="'+item.gross_wt+'"/></span><input type="hidden" class="act_gross_blc" value="'+item.gross_wt+'"><input type="hidden" class="gross_wt_blc" value="'+item.gross_wt+'"></td>'
								+'<td width="10%"><input type="number" step="any" name="lt_item[less_wt][]"  class="less_wt" style="width:80px;" readonly/></td>'
								+'<td width="10%"><input type="number" step="any" name="lt_item[net_wt][]"  class="net_wt" value="'+item.net_wt+'" style="width:80px;" readonly/></td>'
								+'<td width="5%"><input type="text" name="lt_item[wastage_percentage][]" value="'+item.wastage_percentage+'" class="order_wastage_percentage" style="width:80px;"/></td>'
								+'<td><select class="id_mc_type" value='+item.mc_type+'><option value="1">Per Gram</option><option value="2" selected>Per Piece</option></select><input type="hidden" value="'+item.mc_type+'" name="lt_item[id_mc_type][]" class="id_mc_type"></td>'
								+'<td width="10%"><input type="number" step="any" name="lt_item[making_charge][]"  class="order_making_charge" value="'+item.making_charge+'" style="width:80px;"/></td>'
								+'<td width="10%"><input type="number" step="any" name="lt_item[sell_rate][]"  class="order_sell_rate" value="'+item.sell_rate+'" style="width:80px;" '+(item.calculation_based_on < 3 ? "readonly":"")+'/>'+(item.calculation_based_on == 3 ? "per piece":(item.calculation_based_on == 4 ? "per gram":""))+'</td>'
								+'<td width="10%"><input type="number" step="any" name="lt_item[size][]"  class="order_size" style="width:70px;"/></td>'
								+'<td><a href="#" onClick="create_new_empty_est_cus_stone_item($(this).closest(\'tr\'));" class="btn btn-default btn-sm"><i class="fa fa-plus"></i></a></td>'
								+'<td><a href="#" onClick="update_image_upload($(this).closest(\'tr\'));" class="btn btn-default btn-sm"><i class="fa fa-plus"></i></a></td>'
								+'<td width="5%"><input type="number" step="any" name="lt_item[caculated_item_rate][]"  class="order_caculated_item_rate" value="'+item.caculated_item_rate+'" style="width:70px;" readonly/></td>'
								+'<td width="5%"><input type="number" step="any" name="lt_item[adjusted_item_rate][]"  class="order_adjusted_item_rate" value="'+item.adjusted_item_rate+'" style="width:80px;" '+(item.calculation_based_on < 3 ? "readonly":"")+'/></td>'
								+'<td width="10%"><input type="number" name="lt_item[sale_value][]"  class="sale_value" readonly style="width:80px;" /><input type="hidden" class="tax_group_id" value="'+item.tax_group_id+'"><input type="hidden" class="stone_details" name="lt_item[stone_details][]"><input type="hidden" class="stone_price" name="lt_item[stone_price][]"><input type="hidden" class="price" name="lt_item[price][]"><input type="hidden" class="tag_img" name="lt_item[tag_img][]"><input type="hidden" class="normal_st_certif" value="'+item.normal_st_certif+'"><input type="hidden" class="semiprecious_st_certif" value="'+item.semiprecious_st_certif+'"><input type="hidden" class="precious_st_certif" value="'+item.precious_st_certif+'"></td>'
								+'<td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'
								+'</tr>';
						});
						}
						console.log(lot_pcs);
						console.log(gross_wt);
					$('#lot_bal_wt').val(gross_wt);
					$('#lot_bal_pcs').val(lot_pcs);
					$('#lt_item_list tbody').append(row);
					calculateOrderTagSaleValue();
					$(".overlay").css("display", "none");
				}
				else
				{
				$('#errorMsg').css("display","block");
				$('#errorMsg').html(data.message);
				}
				}
				});
			}
        },
        error:function(error)  
        {	
        } 
    	});
}

$(document).on('keyup', '.gross_wt,.less_wt,.order_making_charge,.order_wastage_percentage,.no_of_piece', function(e){
		var row = $(this).closest('tr'); 
		var gross_wt = parseFloat((isNaN(row.find('.gross_wt').val()) || row.find('.gross_wt').val() == '')  ? 0 : row.find('.gross_wt').val()).toFixed(3);
		var less_wt  = (isNaN(row.find('.less_wt').val()) || row.find('.less_wt').val() == '')  ? 0 : row.find('.less_wt').val();
		var net_wt = parseFloat(parseFloat(gross_wt) - parseFloat(less_wt)).toFixed(3);
		row.find('.net_wt').val(net_wt); 
		calculateOrderTagSaleValue();  
});	

$(document).on('change','.calculation_based_on',function(){
    var row = $(this).closest('tr'); 
    row.find('.calc_type').val(this.value);
});

$(document).on('change','.mc_type',function(){
    var row = $(this).closest('tr'); 
    row.find('.making_type').val(this.value);
});


$('#tag_lt_prod').on('change',function(){
		if(this.value!='')
		{
			$('#tag_lt_prodId').val(this.value);
			$('#tag_lt_designId').val('');
			get_Activedesign(this.value);

			var tag_lot_id=$('#tag_lot_id').val();
			var tag_lt_prodId=$('#tag_lt_prodId').val();
		
			if(tag_lot_id!='' && tag_lt_prodId!='')
			{
			      get_lot_inwards_detail(tag_lot_id,tag_lt_prodId,'')
			}
		}
		else
		{
			$('#tag_lt_prodId').val('');
		}

	});
//Lot Products
//Lot designs
function get_lot_designs(searchTxt)
{
	$('#tag_lt_design').html('');
	var lot_no=$('#tag_lot_id').val();
	var lot_product=$('#tag_lt_prodId').val();
	my_Date = new Date();
		$.ajax({ 
		url:base_url+ "index.php/admin_ret_tagging/get_lot_designs?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),
        data: {'lot_no':lot_no,'lot_product':lot_product,'searchTxt':searchTxt},
        type:"POST",
        dataType:"JSON",
        success:function(data)
        {	
			/*$( "#tag_lt_design" ).autocomplete(
			{
				source: data,
				select: function(e, i)
				{ 
					e.preventDefault();
					$("#tag_lt_design" ).val(i.item.label); 
					$("#tag_lt_designId" ).val(i.item.value); 
				},
				response: function(e, i) {
		            // ui.content is the array that's about to be sent to the response callback.
		            console.log(i);
		            if (i.content.length === 0) {
		               $("#prodAlert").html('<p style="color:red">Enter a valid Design</p>');
		               //$('#lt_product').val('');
		            }else{
						$("#prodAlert").html('');
					} 
		        },
				 minLength: 0,
			});*/
			var tag_lt_designId=$('#tag_lt_designId').val();
			$.each(data, function (key, item) {					  				  			   		
			$("#tag_lt_design").append(						
			$("<option></option>")						
			.attr("value", item.lot_id_design)						  						  
			.text(item.design_name)						  					
			);			   											
			});						
			$("#tag_lt_design").select2({			    
			placeholder: "Select Design",			    
			allowClear: true		    
			});					
			$("#tag_lt_design").select2("val",(tag_lt_designId!='' && tag_lt_designId>0?tag_lt_designId:''));	
        },
        error:function(error)  
        {	
        } 
    	})
}
$('#tag_lt_design').on('change',function(){
		if(this.value!='')
		{
			$('#tag_lt_designId').val(this.value);
			$('#designAlert').html('');
		}
		else
		{
			$('#tag_lt_designId').val('');
		}
	});
//Lot designs


$('#add_more_tag').on('click',function(){
	var tag_lot_id=$('#tag_lot_id').val();
	var tag_lt_prodId=$('#tag_lt_prodId').val();
	//var tag_lt_designId=$('#tag_lt_designId').val();
	if(tag_lt_prodId=='')
	{
		$('#productAlert').html('Please Select Product');
	}else{
		$('#productAlert').html('');
	}
/*	if(tag_lt_designId=='')
	{
		$('#designAlert').html('Please Select Design');
	}else{
		$('#designAlert').html('');
	}*/
	if(tag_lot_id!='' && tag_lt_prodId!='')
	{
	    if(validateTagDetailRow())
	    {
	        	get_lot_inwards_detail(tag_lot_id,tag_lt_prodId,'')
	    }
	
	}
});


$('#tag_submit').on('click',function(){
    $('#tag_submit').prop('disabled', true);
    if(validateTagDetailRow())
    {
        $('#tag_form').submit();
    }else{
       return false;
       $('#tag_submit').prop('disabled', false);
    }
});

function validateTagDetailRow(){
	var row_validate = true;
	$('#lt_item_list > tbody  > tr').each(function(index, tr) {
	    if($(this).find('.sales_mode').val()==1)
	    {
	        if($(this).find('.no_of_piece').val() == "" && $(this).find('.lot_id_design').val() == "")
	        {
	            row_validate = false;
	            alert('Please Fill Required Fields');
	             $('#tag_submit').prop('disabled', false);
	        }
	    }
	    else if($(this).find('.sales_mode').val()!=1)
	    {
    	    if($(this).find('.gross_wt').val() == "" || $(this).find('.no_of_piece').val() == "" || $(this).find('.lot_id_design').val() == ""){
    			row_validate = false;
    			alert('Please Fill Required Fields');
    			 $('#tag_submit').prop('disabled', false);
    		}
	    }
		
	});
	return row_validate;
}

$(document).on('keyup',	".cus_design", function(e){ 
		//var row = $(this).parent().parent();
		var row = $(this).closest('tr'); 
		var lot_product = row.find(".lot_product").val();
		var design = row.find(".cus_design").val();
		getSearchCusDesign(lot_product,design, row);
	});
	
function getSearchCusDesign(lot_product,searchTxt, curRow){
	my_Date = new Date();
	$.ajax({
        url: base_url+'index.php/admin_ret_estimation/getProductDesignBySearch/?nocache=' + my_Date.getUTCSeconds(),             
        dataType: "json", 
        method: "POST", 
        data: { 'searchTxt': searchTxt, 'ProCode' : lot_product}, 
        success: function (data) {
			$( ".cus_design" ).autocomplete(
			{
				source: data,
				select: function(e, i)
				{ 
					e.preventDefault();
					curRow.find('.cus_design').val(i.item.label);
					curRow.find('.lot_id_design').val(i.item.value);
				},
				change: function (event, ui) {
					if (ui.item === null) {
					}else{
					}
			    },
				response: function(e, i) {
		            // ui.content is the array that's about to be sent to the response callback.
		            if(searchTxt != ""){
						if (i.content.length !== 0) {
						   //console.log("content : ", i.content);
						}
					}else{
						curRow.find('.cus_design').val("");
						curRow.find('.lot_id_design').val("");
					}
		        },
				 minLength: 1,
			});
        }
     });
}

function get_lot_inwards_detail(lot_no,lot_product,lot_id_design)
{
	//$(".overlay").css("display", "block");	
	my_Date = new Date();
		$.ajax({ 
		url:base_url+ "index.php/admin_ret_tagging/get_lot_inward_details?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),
        data: {'lot_no':lot_no,'lot_product':lot_product,'lot_id_design':lot_id_design},
        type:"POST",
        dataType:"JSON",
        success:function(data)
        {
        	if(data.lot_inward_detail.length>0)
        	{
					$('#lt_tax_group').html(data.tax_percentage.tax_name);
					$('#tax_percentage').val(data.tax_percentage.tax_percentage);
					$('#tgi_calculation').val(data.tax_percentage.tgi_calculation);
					
        		var row = "";
        		var i=1;
        		var allow_row_create=false;
        		$.each(data.lot_inward_detail, function (key, item) {
        		    
        		    $('#id_metal').val(item.id_metal);
        		    $('#tag_id_lot_inward_detail').val(item.id_lot_inward_detail);
        		    $('#calculation_based_on').val(item.calculation_based_on);
        		    $('#tag_calculation_based_on').val(item.calculation_based_on);
        		    $('#tag_design_for').val(item.design_for);
        		    $('#tag_lot_no').val(item.lot_no);
        		    $('#tax_group_id').val(item.tax_group_id);
        		    $('#tag_sales_mode').val(item.sales_mode);
        		    $('#id_purity').val(item.id_purity);
        		    $('#tag_product_short_code').val(item.product_short_code);

        		    
					var curr_used_gross = 0;
					var curr_used_pcs = 0;
					var lot_bal_wt=0;
					var blc_lot_wt=0;
					var blc_gross_wt=0;
					var act_gross_blc=0;
					var weight_per=$('#weight_per').val();
					var sales_mode=item.sales_mode;
					
					var size_sel = "<option value=''>- Select Size-</option>";
					$.each(item.size_details, function (mkey, mitem) {
                		size_sel += "<option  value='"+mitem.id_size+"'>"+mitem.value+'-'+mitem.name+"</option>";
                	});	
					
					$('#lot_bal_wt').val(item['lot_blc'].lot_bal_wt);
					$('#lot_bal_pcs').val(item['lot_blc'].lot_bal_pcs);
					
					$('#lt_item_list> tbody  > tr').each(function(index, tr) {
						if($(this).find('.id_lot_inward_detail').val() == item.id_lot_inward_detail)
						{ 
							curr_used_gross+=parseFloat(($(this).find('.gross_wt').val()=='' ?0 :$(this).find('.gross_wt').val()));
							curr_used_pcs+=parseFloat(($(this).find('.no_of_piece').val()=='' ?0 :$(this).find('.no_of_piece').val()));							
							act_gross_blc =parseFloat(($(this).find('.act_gross_blc').val()=='' ?0 :$(this).find('.act_gross_blc').val()));							
						}
					});
				
		
        		}); 
        	}
         	$(".overlay").css("display", "none");
        },
        error:function(error)  
        {	
        } 
    	});
}
//Gross Weight
$(document).on('keyup', '.gross_wt,.less_wt,.making_charge,.wastage_percentage,.sell_rate,.adjusted_item_rate,.no_of_piece', function(e){
		var row = $(this).closest('tr'); 
		var gross_wt = parseFloat((isNaN(row.find('.gross_wt').val()) || row.find('.gross_wt').val() == '')  ? 0 : row.find('.gross_wt').val()).toFixed(3);
		var less_wt  = (isNaN(row.find('.less_wt').val()) || row.find('.less_wt').val() == '')  ? 0 : row.find('.less_wt').val();
		var net_wt = parseFloat(parseFloat(gross_wt) - parseFloat(less_wt)).toFixed(3);
		row.find('.net_wt').val(net_wt); 
		if(this.className == "no_of_piece"){
			if(row.find('.calculation_based_on').val() >= 3 ){
				calculateTagSaleValue(row);
			}
		}else{
			calculateTagSaleValue(row);
		}  
	});	
/*$(document).on('change','.gross_wt',function(e){
	var curr_used_gross = 0;
	var act_gross_blc = 0;
	var row = $(this).closest('tr'); 
	var trid = $(this).closest('tr').attr('id'); // table row ID 
	if(row.find('.calculation_based_on').val() != 3 ){
		$('#lt_item_list> tbody  > tr').each(function(index, tr) {
			var id_lot_inward_detail=$(this).find('.id_lot_inward_detail').val();
			if(id_lot_inward_detail==trid)
			{
				act_gross_blc = $(this).find('.act_gross_blc').val(); 
				curr_used_gross+=parseFloat(($(this).find('.gross_wt').val()=='' ?0 :$(this).find('.gross_wt').val()));
			}
		});
		if(act_gross_blc < curr_used_gross)
		{
			row.find('.gross_wt').val('');
			row.find('.gross_wt').focus();
			alert("Entered gross weight greater than available weight.");
		}else{
			row.find('.blc_gross').html(act_gross_blc-curr_used_gross);
		}
	}
});*/
$(document).on('change','.gross_wt',function(e){
	var curr_used_gross = 0;
	var act_gross_blc = 0;
	var blc_gross = 0;
	var gross_wt_blc = 0;
	var row = $(this).closest('tr'); 
	var trid = $(this).closest('tr').attr('id'); // table row ID 
	if(row.find('.calculation_based_on').val() != 3 ){
		$('#lt_item_list> tbody  > tr').each(function(index, tr) {
			var id_lot_inward_detail=$(this).find('.id_lot_inward_detail').val();
			if(id_lot_inward_detail==trid)
			{
				act_gross_blc = parseFloat($(this).find('.act_gross_blc').val()); 
				blc_gross 	  = $(this).find('.act_gross_blc').html(); 
				gross_wt_blc  = $(this).find('.gross_wt_blc').val(); 
				curr_used_gross+=parseFloat(($(this).find('.gross_wt').val()=='' ?0 :$(this).find('.gross_wt').val()));
			}
		});
	    console.log(act_gross_blc);
	    console.log(curr_used_gross);
		if(parseFloat(act_gross_blc) < parseFloat(curr_used_gross))
		{
			row.find('.gross_wt').val('');
			row.find('.gross_wt').focus();
			row.find('.net_wt').val('');
			alert("Entered gross weight greater than available weight.");
		}
		else{
			if(act_gross_blc<=curr_used_gross)
			{
				row.find('.blc_gross').html(0);
			}else{
				row.find('.blc_gross').html(parseFloat(gross_wt_blc-curr_used_gross).toFixed(3));
			}
		}
	}
});
// Piece
$(document).on('change','.no_of_piece',function(e){
	var curr_used_pcs = 0;
	var act_blc_pcs = 0;
	var row = $(this).closest('tr'); 
	var trid = $(this).closest('tr').attr('id'); // table row ID 
	$('#lt_item_list> tbody  > tr').each(function(index, tr) {
		var id_lot_inward_detail=$(this).find('.id_lot_inward_detail').val();
		if(id_lot_inward_detail==trid)
		{
			act_blc_pcs = $(this).find('.act_blc_pcs').val(); 
			curr_used_pcs+=parseFloat(($(this).find('.no_of_piece').val()=='' ?0 :$(this).find('.no_of_piece').val()));
		}
	});
	if(act_blc_pcs < curr_used_pcs)
	{
		row.find('.no_of_piece').val('');
		row.find('.no_of_piece').focus();
		alert("Entered pieces greater than available pieces.");
	}else{
		row.find('.blc_pcs').html(act_blc_pcs-curr_used_pcs);
	}
});
//pieces

$(document).on('change', '.calculation_based_on', function(e){
	var row = $(this).closest('tr');
	calculateTagSaleValue(row);
});
$(document).on('change','.mc_type ',function(e){
		var row = $(this).closest('tr');
		if(this.value!='')
		{
			row.find('.id_mc_type').val(this.value);
		}
		else
		{
			row.find('.id_mc_type').val(this.value);
		}
		calculateTagSaleValue(row); 
	});
function calculateTagSaleValue(row){
    
    $('#lt_item_list > tbody tr').each(function(idx, row){
    curRow = $(this);   
   
	var disc_limit_type=$('#disc_limit_type').val();
	var total_price = 0;
	var base_value_price = 0;
	var arrived_value_price = 0;
	var base_value_tax = 0;
	var arrived_value_tax = 0;
	var base_rate_tax = 0;
	var arrived_rate_tax = 0;
	var total_tax_per = 0;
	var total_tax_rate = 0;
	var rate_with_mc = 0;
	//curRow.find('td:eq(1) .cat_design').val(i.item.label);
	//(isNaN(row.find('td:eq(6) .cat_gwt').val()) || row.find('td:eq(6) .cat_gwt').val() == '')  ? 0 : row.find('td:eq(6) .cat_gwt').val();
	var gross_wt = (isNaN(curRow.find('.gross_wt').val()) || curRow.find('.gross_wt').val() == '')  ? 0 : curRow.find('.gross_wt').val();
	var less_wt  = (isNaN(curRow.find('.less_wt').val()) || curRow.find('.less_wt').val() == '')  ? 0 : curRow.find('.less_wt').val();
	var stone_price  = (isNaN(curRow.find('.stone_price').val()) || curRow.find('.stone_price').val() == '')  ? 0 : curRow.find('.stone_price').val();
	var material_price  = (isNaN(curRow.find('.material_price').val()) || curRow.find('.material_price').val() == '')  ? 0 : curRow.find('.material_price').val();
	var certification_price  = (isNaN(curRow.find('.price').val()) || curRow.find('.price').val() == '')  ? 0 : curRow.find('.price').val();
	var net_wt = (isNaN(curRow.find('.net_wt').val()) || curRow.find('.net_wt').val() == '')  ? 0 : curRow.find('.net_wt').val();
	var calculation_type = (isNaN(curRow.find('.calculation_based_on').val()) || curRow.find('.calculation_based_on').val() == '')  ? 0 : curRow.find('.calculation_based_on').val();
	var metal_type = (isNaN(curRow.find('.id_metal').val()) || curRow.find('.id_metal').val() == '')  ? 1 : curRow.find('.id_metal').val();
	var sales_mode = (isNaN(curRow.find('.sales_mode').val()) || curRow.find('.sales_mode').val() == '')  ? 1 : curRow.find('.sales_mode').val();
    if(metal_type==1)
	{
		var rate_per_grm = $('#metal_rate').val();//Gold
	}else{
		var rate_per_grm = $('#silverrate_1gm').val();//Silver
	}	
	var tgi_calculation_type=$('#tgi_calculation').val().split(",");
	var tax_percentage=$('#tax_percentage').val().split(",");
	var retail_max_mc = (isNaN(curRow.find('.making_charge').val()) || curRow.find('.making_charge').val() == '')  ? 0 : curRow.find('.making_charge').val();
	var tot_wastage   = (isNaN(curRow.find('.wastage_percentage').val()) || curRow.find('.wastage_percentage').val() == '')  ? 0 : curRow.find('.wastage_percentage').val();
	var no_of_piece   = (isNaN(curRow.find('.no_of_piece').val()) || curRow.find('.no_of_piece').val() == '')  ? 0 : curRow.find('.no_of_piece').val(); 
	/** 
	*	Amount calculation based on settings (without discount and tax )
	*   0 - Wastage on Gross weight And MC on Gross weight 
	*   1 - Wastage on Net weight And MC on Net weight
	*   2 - Wastage On Netwt And MC On Grwt
	*   rate_with_mc = Metal Rate + Stone + OM + Wastage + MC
	*/
	if(calculation_type == 0){ 
		var wast_wgt      = parseFloat(parseFloat(gross_wt) * parseFloat(tot_wastage/100)).toFixed(3);
		var mc_type       =  parseFloat(curRow.find('.id_mc_type').val() == 1 ? parseFloat(retail_max_mc * gross_wt ) : parseFloat(retail_max_mc * curRow.find('.no_of_piece').val()));
		// Metal Rate + Stone + OM + Wastage + MC
		rate_with_mc = parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(material_price)+parseFloat(certification_price));
	}
	else if(calculation_type == 1){
		var wast_wgt      = parseFloat(parseFloat(net_wt) * parseFloat(tot_wastage/100)).toFixed(3);
		var mc_type       =  parseFloat(curRow.find('.id_mc_type').val() == 1 ? parseFloat(retail_max_mc * net_wt ) : parseFloat(retail_max_mc * curRow.find('.no_of_piece').val()));
		// Metal Rate + Stone + OM + Wastage + MC
		rate_with_mc = parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(material_price)+parseFloat(certification_price));
	}
	else if(calculation_type == 2){ 
		var wast_wgt      = parseFloat(parseFloat(net_wt) * parseFloat(tot_wastage/100)).toFixed(3);
		var mc_type       =  parseFloat(curRow.find('.id_mc_type').val() == 1 ? parseFloat(retail_max_mc * gross_wt ) : parseFloat(retail_max_mc * curRow.find('.no_of_piece').val()));
		// Metal Rate + Stone + OM + Wastage + MC
	    rate_with_mc = parseFloat((parseFloat(rate_per_grm) * (parseFloat(wast_wgt) + parseFloat(net_wt)))+ parseFloat(mc_type))+parseFloat(stone_price)+parseFloat(material_price)+parseFloat(certification_price);
	}	
	else if(calculation_type == 3){
		var sell_rate  = (isNaN(curRow.find('.sell_rate').val()) || curRow.find('.sell_rate').val() == '')  ? 0 : curRow.find('.sell_rate').val();
		var adjusted_item_rate  = (isNaN(curRow.find('.adjusted_item_rate').val()) || curRow.find('.adjusted_item_rate').val() == '')  ? 0 : curRow.find('.adjusted_item_rate').val();
	    caculated_item_rate = parseFloat(parseFloat(sell_rate)*parseFloat(no_of_piece));
	    curRow.find('.caculated_item_rate').val(caculated_item_rate);
	    rate_with_mc = parseFloat(parseFloat(adjusted_item_rate) > 0 ? parseFloat(adjusted_item_rate) : caculated_item_rate ); 
	}
	else if(calculation_type == 4){
		var sell_rate  = (isNaN(curRow.find('.sell_rate').val()) || curRow.find('.sell_rate').val() == '')  ? 0 : curRow.find('.sell_rate').val();
		var adjusted_item_rate  = (isNaN(curRow.find('.adjusted_item_rate').val()) || curRow.find('.adjusted_item_rate').val() == '')  ? 0 : curRow.find('.adjusted_item_rate').val();
	    caculated_item_rate = parseFloat((parseFloat(sell_rate)*parseFloat(net_wt))*parseFloat(no_of_piece));
	    curRow.find('.caculated_item_rate').val(caculated_item_rate); 
	    rate_with_mc = parseFloat(parseFloat(adjusted_item_rate) > 0 ? parseFloat(adjusted_item_rate) : caculated_item_rate ); 
	}	
	console.log('Calculation : '+calculation_type);
	console.log('Wastage : '+wast_wgt);
	console.log('Total Wastage : '+tot_wastage);
	console.log('MC : '+mc_type);
	console.log('Rate with MC : '+rate_with_mc);
	console.log(' MC TYPE : '+mc_type);
	console.log(' Rate Per Gram : '+rate_per_grm);
	// Tax Calculation
	if(sales_mode==2)
	{
	 $.each(tgi_calculation_type,function(ckey,citem)
	{
		if(citem==1) //Base value
		{
			$.each(tax_percentage,function(key,item){
				if(ckey==key)
				{
					base_value_tax+=(parseFloat(item));
				}
			});
			base_rate_tax	 = parseFloat(rate_with_mc*parseFloat(( base_value_tax / 100)));
			base_value_price = parseFloat(rate_with_mc +base_rate_tax).toFixed(2);
		}
		if(citem==2) //arrived value
		{
				$.each(tax_percentage,function(key,item){
				if(ckey==key)
				{
					arrived_value_tax+=(parseFloat(item));
				}
				});
			arrived_rate_tax    = parseFloat(parseFloat(base_value_price)*parseFloat(( arrived_value_tax / 100)));
			arrived_value_price = parseFloat(parseFloat(base_value_price)+arrived_rate_tax).toFixed(2);
		}
	});
	total_tax_rate=parseFloat(parseFloat(base_rate_tax)+parseFloat(arrived_rate_tax)).toFixed(2);
	total_tax_per=parseFloat(parseFloat(base_value_tax)+parseFloat(arrived_value_tax)).toFixed(2);
	total_price=parseFloat(arrived_value_price!=0 ? arrived_value_price:base_value_price).toFixed(2);
    }else
    {
        total_price=rate_with_mc;
    }
	curRow.find('.sale_value').val(Math.round(total_price));
	console.log('Amount : '+total_price);
	console.log('Tax Rate : '+total_tax_rate);
	console.log('Arrived value : '+arrived_value_price);
	console.log('*************************');
    });
}


function calculateOrderTagSaleValue(){
	$('#lt_item_list > tbody tr').each(function(idx, row){
	curRow = $(this);
	var disc_limit_type=$('#disc_limit_type').val();
	var total_price = 0;
	var base_value_price = 0;
	var arrived_value_price = 0;
	var base_value_tax = 0;
	var arrived_value_tax = 0;
	var base_rate_tax = 0;
	var arrived_rate_tax = 0;
	var total_tax_per = 0;
	var total_tax_rate = 0;
	var rate_with_mc = 0;
	//curRow.find('td:eq(1) .cat_design').val(i.item.label);
	//(isNaN(row.find('td:eq(6) .cat_gwt').val()) || row.find('td:eq(6) .cat_gwt').val() == '')  ? 0 : row.find('td:eq(6) .cat_gwt').val();
	var gross_wt = (isNaN(curRow.find('.gross_wt').val()) || curRow.find('.gross_wt').val() == '')  ? 0 : curRow.find('.gross_wt').val();
	var less_wt  = (isNaN(curRow.find('.less_wt').val()) || curRow.find('.less_wt').val() == '')  ? 0 : curRow.find('.less_wt').val();
	var stone_price  = (isNaN(curRow.find('.stone_price').val()) || curRow.find('.stone_price').val() == '')  ? 0 : curRow.find('.stone_price').val();
	var material_price  = (isNaN(curRow.find('.material_price').val()) || curRow.find('.material_price').val() == '')  ? 0 : curRow.find('.material_price').val();
	var certification_price  = (isNaN(curRow.find('.price').val()) || curRow.find('.price').val() == '')  ? 0 : curRow.find('.price').val();
	var net_wt = (isNaN(curRow.find('.net_wt').val()) || curRow.find('.net_wt').val() == '')  ? 0 : curRow.find('.net_wt').val();
	var calculation_type = (isNaN(curRow.find('.calculation_based_on').val()) || curRow.find('.calculation_based_on').val() == '')  ? 0 : curRow.find('.calculation_based_on').val();
	var metal_type = (isNaN(curRow.find('.id_metal').val()) || curRow.find('.id_metal').val() == '')  ? 1 : curRow.find('.id_metal').val();
	var sales_mode = (isNaN(curRow.find('.sales_mode').val()) || curRow.find('.sales_mode').val() == '')  ? 1 : curRow.find('.sales_mode').val();
	var stn_amt = (isNaN(curRow.find('.stn_amt').val()) || curRow.find('.stn_amt').val() == '')  ? 0 : curRow.find('.stn_amt').val();
    if(metal_type==1)
	{
		var rate_per_grm = $('#metal_rate').val();//Gold
	}else{
		var rate_per_grm = $('#silverrate_1gm').val();//Silver
	}	
	var tgi_calculation_type=$('#tgi_calculation').val().split(",");
	var tax_percentage=$('#tax_percentage').val().split(",");
	var retail_max_mc = (isNaN(curRow.find('.order_making_charge').val()) || curRow.find('.order_making_charge').val() == '')  ? 0 : curRow.find('.order_making_charge').val();
	var tot_wastage   = (isNaN(curRow.find('.order_wastage_percentage').val()) || curRow.find('.order_wastage_percentage').val() == '')  ? 0 : curRow.find('.order_wastage_percentage').val();
	var no_of_piece   = (isNaN(curRow.find('.no_of_piece').val()) || curRow.find('.no_of_piece').val() == '')  ? 0 : curRow.find('.no_of_piece').val(); 
	var tax_group = curRow.find('.tax_group_id').val();
	var mc_type = curRow.find('.id_mc_type').val();

	/** 
	*	Amount calculation based on settings (without discount and tax )
	*   0 - Wastage on Gross weight And MC on Gross weight 
	*   1 - Wastage on Net weight And MC on Net weight
	*   2 - Wastage On Netwt And MC On Grwt
	*   rate_with_mc = Metal Rate + Stone + OM + Wastage + MC
	*/
	var wast_wgt = parseFloat(parseFloat(net_wt) * parseFloat(tot_wastage/100)).toFixed(3);
	
	var retail_max_mc       =  parseFloat(curRow.find('.id_mc_type').val() == 1 ? parseFloat(retail_max_mc * net_wt ) : parseFloat(retail_max_mc * curRow.find('.no_of_piece').val()));
	rate_with_mc = parseFloat(parseFloat(parseFloat(rate_per_grm) * (parseFloat(wast_wgt) + parseFloat(net_wt))) +parseFloat(retail_max_mc)+parseFloat(stn_amt)).toFixed(2);  
	//console.log('Calculation : '+calculation_type);
	console.log('retail_max_mc : '+retail_max_mc);
	console.log('Wastage : '+wast_wgt);
	console.log('Total Wastage : '+tot_wastage);
	console.log('Rate with MC : '+rate_with_mc);
	console.log(' Rate Per Gram : '+rate_per_grm);
	// Tax Calculation
	if(sales_mode==2)
	{

   	var base_value_tax=parseFloat(calculate_base_value_tax(rate_with_mc,tax_group)).toFixed(2);
	var base_value_amt=parseFloat(parseFloat(rate_with_mc)+parseFloat(base_value_tax)).toFixed(2);
	var arrived_value_tax=parseFloat(calculate_arrived_value_tax(base_value_amt,tax_group)).toFixed(2);
	var arrived_value_amt=parseFloat(parseFloat(base_value_amt)+parseFloat(arrived_value_tax)).toFixed(2);
	var total_tax_rate=parseFloat(parseFloat(base_value_tax)+parseFloat(arrived_value_tax)).toFixed(2);

	total_price=parseFloat(parseFloat(rate_with_mc)+parseFloat(total_tax_rate)).toFixed(2);



    }else
    {
        total_price=rate_with_mc;
    }
	curRow.find('.sale_value').val(Math.round(total_price));
	console.log('Amount : '+total_price);
	console.log('Tax Rate : '+total_tax_rate);
	console.log('Arrived value : '+arrived_value_price);
	console.log('*************************');
	});
}

function get_taxgroup_items(){
	my_Date = new Date();
	$.ajax({
		url: base_url+'index.php/admin_ret_billing/getAllTaxgroupItems/?nocache=' + my_Date.getUTCSeconds(),   
		dataType: "json", 
		method: "GET", 
		success: function ( data ) { 
			tax_details = data;
			console.log("tax_details", tax_details);
		}
	 });
}

function calculate_base_value_tax(taxcallrate, taxgroup){
	var totaltax = 0;
	console.log(tax_details);
	$.each(tax_details, function(idx, taxitem){
		if(taxitem.tgi_tgrpcode == taxgroup){
			if(taxitem.tgi_calculation == 1){
				if(taxitem.tgi_type == 1){
					totaltax += parseFloat(taxcallrate)	* (parseFloat(taxitem.tax_percentage)/100);
				}else{
					totaltax -= parseFloat(taxcallrate)	* (parseFloat(taxitem.tax_percentage)/100);
				}
			}
		}
	});
	return totaltax;
}
function calculate_arrived_value_tax(taxcallrate, taxgroup){
	var totaltax = 0;
	console.log(tax_details);
	$.each(tax_details, function(idx, taxitem){
		if(taxitem.tgi_tgrpcode == taxgroup){
			if(taxitem.tgi_calculation == 2){
				if(taxitem.tgi_type == 1){
					totaltax += parseFloat(taxcallrate)	* (parseFloat(taxitem.tax_percentage)/100);
				}else{
					totaltax -= parseFloat(taxcallrate)	* (parseFloat(taxitem.tax_percentage)/100);
				}
			}
		}
	});
	return totaltax;
}

//custom

$(document).on('change',".stones_type",function(){
   var row = $(this).closest('tr');
   var stone_type=this.value;
   row.find('.stone_id').html('');
   	var stones_list = "<option value=''>-Select Stone-</option>";
   $.each(stones, function (pkey, pitem) {
        if(pitem.stone_type==stone_type)
        {
            stones_list += "<option value='"+pitem.stone_id+"'>"+pitem.stone_name+"</option>";
        }
	});
	 row.find('.stone_id').append(stones_list);
});


function create_new_stone_row()
{
    	
    		var stones_list = "<option value=''>-Select Stone-</option>";
			var stones_type = "<option value=''>-Stone Type-</option>";
			var uom_list = "<option value=''>-Stone Type-</option>";
			$.each(stones, function (pkey, pitem) {
				stones_list += "<option value='"+pitem.stone_id+"'>"+pitem.stone_name+"</option>";
			});
			
			$.each(stone_types, function (pkey, pitem) {
				stones_type += "<option value='"+pitem.id_stone_type+"'>"+pitem.stone_type+"</option>";
			});
			
			$.each(uom_details, function (pkey, pitem) {
				uom_list += "<option value='"+pitem.uom_id+"'>"+pitem.uom_name+"</option>";
			});
			
			var row='';
			
                row += '<tr>'
                	+'<td><input class="show_in_lwt" type="checkbox"name="est_stones_item[show_in_lwt][]" value="1" checked></td>'
                	+'<td><select class="stones_type form-control" name="est_stones_item[stones_type][]">'+stones_type+'</select></td>'
					+'<td><select class="stone_id form-control" name="est_stones_item[stone_id][]"></select></td>'
					+'<td><input type="number" class="stone_pcs form-control" name="est_stones_item[stone_pcs][]" value="" style="width: 100%;"/></td>'
					+'<td><div class="input-group"><input class="stone_wt form-control" type="number" name="est_stones_item[stone_wt][]" value="" style="width:100%;"/><span class="input-group-btn" style="width: 120px;"><select class="stone_uom_id form-control" name="est_stones_item[uom_id][]">'+uom_list+'</select></span></div></td>'
					+'<td><input type="number" class="stone_rate form-control" name="est_stones_item[stone_rate][]" value=""  style="width:80%;"/></td>'
					+'<td><input type="number" class="stone_price form-control" name="est_stones_item[stone_price][]" value=""  style="width:100%;" readonly/></td>'
					+'<td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';
			$('#cus_stoneModal .modal-body').find('#estimation_stone_cus_item_details tbody').append(row);
					
}

function create_new_empty_est_cus_stone_item(curRow,id)
{
	if(curRow!=undefined)
	{
		$('#custom_active_id').val(curRow.closest('tr').attr('class'));
		$('#stone_active_row').val(curRow.closest('tr').attr('class'));
	}
	var row='';
	var catRow=$('#custom_active_id').val();
	var active_row=$('#stone_active_row').val();
	    
		var row_st_details=$('.'+catRow).find('.stone_details').val();
		console.log(row_st_details);
		if(row_st_details.length>0)
		{
			var stone_details=JSON.parse(row_st_details);
			console.log(stone_details);
			$.each(stone_details, function (pkey, pitem) {
	 			var stones_list='';
	 			var stones_type_list='';
	 			var uom_list='';
	 			var html='';
				$.each(stones, function (pkey, item) 
				{
					var selected = "";
					if(item.stone_id == pitem.stone_id)
					{
						selected = "selected='selected'";
					}
					stones_list += "<option value='"+item.stone_id+"' "+selected+">"+item.stone_name+"</option>";
				});	
				
				
				$.each(stone_types, function (pkey, item) {
				    var st_type_selected = "";
				    if(item.id_stone_type == pitem.stones_type)
					{
						st_type_selected = "selected='selected'";
					}
				    stones_type_list += "<option value='"+item.id_stone_type+"' "+st_type_selected+">"+item.stone_type+"</option>";
    			});
    			
    			$.each(uom_details, function (pkey, item) {
    			     var uom_selected = "";
    			    if(item.uom_id == pitem.stone_uom_id)
					{
						uom_selected = "selected='selected'";
					}
    				uom_list += "<option value='"+item.uom_id+"' "+uom_selected+">"+item.uom_name+"</option>";
    			});
			    
			   
				row += '<tr>'
                	+'<td><input class="show_in_lwt" type="checkbox"name="est_stones_item[show_in_lwt][]" value="'+(pitem.show_in_lwt==1 ? 1:0)+'" '+(pitem.show_in_lwt==1 ? 'checked' :'')+' ></td>'
                	+'<td><select class="stones_type form-control" name="est_stones_item[stones_type][]" >'+stones_type_list+'</select></td>'
					+'<td><select class="stone_id form-control" name="est_stones_item[stone_id][]" >'+stones_list+'</select></td>'
					+'<td><input type="number" class="stone_pcs form-control" name="est_stones_item[stone_pcs][]" value="'+pitem['stone_pcs']+'" style="width: 100%;"/></td>'
					+'<td><div class="input-group"><input class="stone_wt form-control" type="number" name="est_stones_item[stone_wt][]" value="'+pitem['stone_wt']+'" style="width:100%;"/><span class="input-group-btn" style="width: 120px;"><select class="stone_uom_id form-control" name="est_stones_item[uom_id][]" >'+uom_list+'</select></span></div></td>'
					+'<td><input type="number" class="stone_rate form-control" name="est_stones_item[stone_rate][]" value="'+pitem['stone_rate']+'"  style="width:80%;"/></td>'
					+'<td><input type="number" class="stone_price form-control" name="est_stones_item[stone_price][]" value="'+pitem['stone_price']+'"  style="width:100%;" readonly/></td>'
					+'<td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';
					
              			
			});
		}
		else
		{
			var stones_list = "<option value=''>-Select Stone-</option>";
			var stones_type = "<option value=''>-Stone Type-</option>";
			var uom_list = "<option value=''>-Stone Type-</option>";
			$.each(stones, function (pkey, pitem) {
				stones_list += "<option value='"+pitem.stone_id+"'>"+pitem.stone_name+"</option>";
			});
			
			$.each(stone_types, function (pkey, pitem) {
				stones_type += "<option value='"+pitem.id_stone_type+"'>"+pitem.stone_type+"</option>";
			});
			
			$.each(uom_details, function (pkey, pitem) {
				uom_list += "<option value='"+pitem.uom_id+"'>"+pitem.uom_name+"</option>";
			});
			
                row += '<tr id="'+active_row+'">'
                	+'<td><input class="show_in_lwt" type="checkbox"name="est_stones_item[show_in_lwt][]" value="1" checked></td>'
                	+'<td><select class="stones_type form-control" name="est_stones_item[stones_type][]">'+stones_type+'</select></td>'
					+'<td><select class="stone_id form-control" name="est_stones_item[stone_id][]"></select></td>'
					+'<td><input type="number" class="stone_pcs form-control" name="est_stones_item[stone_pcs][]" value="" style="width: 100%;"/></td>'
					+'<td><div class="input-group"><input class="stone_wt form-control" type="number" name="est_stones_item[stone_wt][]" value="" style="width:100%;"/><span class="input-group-btn" style="width: 120px;"><select class="stone_uom_id form-control" name="est_stones_item[uom_id][]">'+uom_list+'</select></span></div></td>'
					+'<td><input type="number" class="stone_rate form-control" name="est_stones_item[stone_rate][]" value=""  style="width:80%;"/></td>'
					+'<td><input type="number" class="stone_price form-control" name="est_stones_item[stone_price][]" value=""  style="width:100%;" readonly/></td>'
					+'<td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';
		}
	$('#cus_stoneModal .modal-body').find('#estimation_stone_cus_item_details tbody').append(row);
	$('#cus_stoneModal').modal('show');
}
$('#cus_stoneModal .modal-body #create_stone_item_details').on('click', function(){
if(validateStoneCusItemDetailRow()){
			create_new_stone_row();
		}else{
			alert("Please fill required fields");
		}
});
$('#cus_stoneModal  #close_stone_details').on('click', function(){
	$('#cus_stoneModal .modal-body').find('#estimation_stone_cus_item_details tbody').empty();
});
function validateStoneCusItemDetailRow(){
	var row_validate = true;
	$('#cus_stoneModal .modal-body #estimation_stone_cus_item_details> tbody  > tr').each(function(index, tr) {
		if($(this).find('.stone_id').val() == "" || $(this).find('.stone_pcs').val() == "" || $(this).find('.stone_wt').val() == "" || $(this).find('.stone_rate').val() == "" || $(this).find('.stone_price').val() == "" || $(this).find('.stone_uom_id').val() == "" ){
			row_validate = false;
		}
	});
	return row_validate;
}

$(document).on('keyup',".stone_pcs,.stone_wt,.stone_rate",function(){
    calculate_stone_amount();
});


function calculate_stone_amount()
{
     $('#estimation_stone_cus_item_details > tbody tr').each(function(idx, row){
         curRow = $(this);   
         var stone_amt=0;
         var stone_pcs  = (isNaN(curRow.find('.stone_pcs').val()) || curRow.find('.stone_pcs').val() == '')  ? 0 : curRow.find('.stone_pcs').val();
         var stone_wt  = (isNaN(curRow.find('.stone_wt').val()) || curRow.find('.stone_wt').val() == '')  ? 0 : curRow.find('.stone_wt').val();
         var stone_rate  = (isNaN(curRow.find('.stone_rate').val()) || curRow.find('.stone_rate').val() == '')  ? 0 : curRow.find('.stone_rate').val();
         stone_amt = parseFloat(parseFloat(stone_wt)*parseFloat(stone_rate)).toFixed(2);
         curRow.find('.stone_price').val(stone_amt);
     });
}

$(document).on('change',".show_in_lwt",function(){
    if($(this).is(":checked"))
    {
        $(this).closest('tr').find('.show_in_lwt').val(1);
    }else{
        $(this).closest('tr').find('.show_in_lwt').val(0);
    }
});

$('#cus_stoneModal  #update_stone_details').on('click', function(){
	if(validateStoneCusItemDetailRow())
	{
	var stone_details=[];
	var stone_price=0;
	var certification_price=0;
	$('#cus_stoneModal .modal-body #estimation_stone_cus_item_details> tbody  > tr').each(function(index, tr) {
		stone_price+=parseFloat($(this).find('.stone_price').val());
    		stone_details.push({
    		            'show_in_lwt'       : $(this).find('.show_in_lwt').val(),
    		            'stone_id'          : $(this).find('.stone_id').val(),
    		            'stones_type'       : $(this).find('.stones_type').val(),
    		            'stone_pcs'         : $(this).find('.stone_pcs').val(),
    		            'stone_wt'          : $(this).find('.stone_wt').val(),
    		            'stone_price'       : $(this).find('.stone_price').val(),
    		            'stone_rate'        : $(this).find('.stone_rate').val(),
    		            'stone_type'        : $(this).find('.stone_type').val(),
    		            'stone_uom_id'      : $(this).find('.stone_uom_id').val()
    		});
	});
	console.log(stone_details);
	$('#cus_stoneModal').modal('toggle');
	var catRow=$('#custom_active_id').val();
	$('.'+catRow).find('.stone_details').val(JSON.stringify(stone_details));
   	$('.'+catRow).find('.stone_price').val(stone_price);
   	$('.'+catRow).find('.price').val(certification_price);
	var row = $('.'+catRow).closest('tr');
	calculateTagPreviewSaleValue();
   $('#cus_stoneModal .modal-body').find('#estimation_stone_cus_item_details tbody').empty();
}
else
{
	alert('Please Fill The Required Details');
}
});

$(document).on('change','.stone_id',function(e){
	var stone_id=this.value;
	var stone_type='';
	var catRow=$('#custom_active_id').val();
	var row_st_details=$('.'+catRow).find('.stone_details').val();
	$.each(stones, function (pkey, item) 
	{
		if(item.stone_id==stone_id)
		{
			stone_type = item.stone_type;
		}
	});	
	var row = $(this).closest('tr'); 
	row.find('.stone_type').val(stone_type);
	if(stone_id!='')
	{
		$('#update_stone_details').prop('disabled',false);
	} 
	else
	{
		$('#update_stone_details').prop('disabled',true);
	}
});

$(document).on('change',".is_certification",function()
{
	var catRow=$('#custom_active_id').val();
	var row_st_details=$('.'+catRow).find('.stone_details').val();
	if($(this).is(":checked"))
	{
		$(this).closest('tr').find('.is_certification').val(1);
		$(this).closest('tr').find('.price').prop('disabled',false);
		var stone_type=$(this).closest('tr').find('.stone_type').val();
		if(stone_type==1)
		{
			var image=$('.'+catRow).find('.precious_st_certif').val();
		}
		else if(stone_type==2)
		{
			var image=$('.'+catRow).find('.semiprecious_st_certif').val();
		}
		else if(stone_type==3)
		{
			var image=$('.'+catRow).find('.normal_st_certif').val();
		}
	    if(image!='' && image!=undefined)
		{
			var img_src=image.split('#');
			var html='';
			$.each(img_src,function(key,item){
				if(item!='')
				{
					var tag_lot_received_id=$('.'+catRow).find('.lot_no').val();
					var src=base_url+'/assets/img/lot/'+tag_lot_received_id+'/certificates/'+item;
					html+= "<div class='col-md-6'><input type='checkbox' class='img_select' name='image' value="+item+">&nbsp;select<img class='thumbnail' src='" + src + "'" +
					"style='width:70px;height:70px;'/></div>";  		
				}					
			});
		}
		$(this).closest('tr').find('td:eq(6)').append(html);
	}
	else
	{
		var image='';
		$(this).closest('tr').find('td:eq(6)').html('');
		$(this).closest('tr').find('.is_certification').val(0);
		$(this).closest('tr').find('.price').prop('disabled',true);
		//$(this).closest('tr').find('.lwt').prop('disabled',true);
	}
});

function get_stones(stone_type=""){
    stones=[];
	$.ajax({		
	 	type: 'POST',		
	 	url : base_url + 'index.php/admin_ret_tagging/getStoneItems',
	 	dataType : 'json',
	 	data:{'stone_type':stone_type},
	 	success  : function(data){
		 	stones = data;
		 	console.log(stones);
	 	}	
	}); 
}


function get_stone_types(){
	$.ajax({		
	 	type: 'GET',		
	 	url : base_url + 'index.php/admin_ret_tagging/getStoneTypes',
	 	dataType : 'json',		
	 	success  : function(data){
		 	stone_types = data;
	 	}	
	}); 
}


function get_ActiveUOM(){
	$.ajax({		
	 	type: 'GET',		
	 	url : base_url + 'index.php/admin_ret_tagging/get_ActiveUOM',
	 	dataType : 'json',		
	 	success  : function(data){
		 	uom_details = data;
	 	}	
	}); 
}


//Image Upload
function update_image_upload(curRow,id)
{
	if(curRow!=undefined)
	{
		$('#custom_active_id').val(curRow.closest('tr').attr('class'));
	}
	$('#imageModal').modal('show');
}
$("#pre_images").on('change',function(){  
		validateCertifImg(this.id);		
	});
function validateCertifImg(type)
 {
 	if(type == 'pre_images'){
		preview = 'uploadArea_p_stn';
	}
	var files = event.target.files; 
	var html_1="";  
	 for (var i = 0; i < files.length; i++) 
	 {
        var file = files[i];
        if(file.size> 1048576)
	 	{
	 		 alert('File size cannot be greater than 1 MB');
	 		 files[i] = "";
	 		 return false; 
	 	}
	 	else
	 	{
	 		var fileName =file.name;
			var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
			ext = ext.toLowerCase();
			if(ext != "jpg" && ext != "png" && ext != "jpeg")
			{
				alert("Upload JPG or PNG Images only");
				files[i] = ""; 
			}
			else
			{						
				var reader = new FileReader();
				reader.onload = function (event) {
					if(type == 'pre_images'){ 
						pre_img_resource.push({'src':event.target.result,'name':fileName});
						pre_img_files.push(file); 
					}					
				}
				if (file)
				{
					reader.readAsDataURL(file);
				}
				/*else
				{
					preview.prop('src',''); 
				}*/
			}
	 	}
    } 
	setTimeout(function(){
		var resource = [];	
		$('#'+preview+' div').remove();	
		if(type == 'pre_images'){  
			resource = pre_img_resource;
		}
		$.each(resource,function(key,item){
		   if(item)
		   {  
		   		var div = document.createElement("div");
				div.setAttribute('class','col-md-4'); 
				div.setAttribute('id',+key); 
				param = {"key":key,"preview":preview,"stone_type":type};
				//div.innerHTML+= "<a onclick='remove_stn_img('"+key+"','"+preview+"','"+type+"')'><i class='fa fa-trash'></i>Delete</a><img class='thumbnail' src='" + item.src + "'" +
				div.innerHTML+= "<a onclick='remove_stn_img("+JSON.stringify(param)+")'><i class='fa fa-trash'></i>Delete</a><img class='thumbnail' src='" + item.src + "'" +
				"style='width: 100px;height: 100px;'/>";  
				$('#'+preview).append(div);
		   }
		   $('#lot_img_upload').css('display','');
		});
	},1000);  
}
 function remove_stn_img(param)
 {
 		$('#'+param.preview+' #'+param.key).remove();
 		if(param.stone_type == 'pre_images'){  
			pre_img_resource.splice(param.key,1);
			if(ctrl_page[2] == 'edit')
			remove_img(file,'certificates','precious_st_certif',id,imgs);
			console.log(pre_img_resource);
		}
 }
 $('#imageModal  #update_img').on('click', function(){
	$('#imageModal').modal('toggle');
	var catRow=$('#custom_active_id').val();
	$('.'+catRow).find('.tag_img').val(JSON.stringify(pre_img_resource));
});
 //Image Upload
function remove_tag_img(param)
 {
 		$('#'+param.key).remove();
		pre_img_resource.splice(param.key,1);
 }
 //Update Tag list
 function update_tagging_details(id)
 {
	my_Date = new Date();
		$.ajax({ 
		url:base_url+ "index.php/admin_ret_tagging/tagging/edit/"+id+"?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),
        data: {'tag_id':id},
        type:"POST",
        dataType:"JSON",
        success:function(data)
        {	
			$("#tagEditModal").modal({
			backdrop: 'static',
			keyboard: false
			});
			var row='';
        	var tagging=data.tagging;
        	var tag_balance=data.tag_balance;
        	var tax_percentage=data.tax_percentage;
        	var metal_rate=data.metal_rate;
        	var stone_details=data.stone_details;
        	var img_source =data.tagging.img_source;
        	if(img_source.length>0)
        	{
        		$.each(img_source,function(ckey,citem)
				{
					pre_img_resource.push({'src':citem.src,'name':citem});
					$('#img_source').val(JSON.stringify(pre_img_resource));
					var div = document.createElement("div");
					div.setAttribute('class','col-md-4'); 
					div.setAttribute('id',+ckey); 
					param = {"key":ckey,"preview":preview};
					div.innerHTML+= "<a><i onclick='remove_tag_img("+JSON.stringify(param)+")' class='fa fa-trash'></i>Delete</a><img class='thumbnail' src='" +citem.src+ "'" +
					"style='width: 100px;height: 100px;'/>";  
					$('#preview').append(div);
				});
        	}
        	$('#tag_id').val(tagging.tag_id);
        	$('#branch').html(tagging.name);
        	$('#lt_design_id').html(tagging.design_name);
        	$('#lot_no').html(tagging.tag_lot_id);
        	$('#tag_code').html(tagging.tag_code);
        	$('#net_wt').val(tagging.net_wt);
        	$('#gross_wt').val(tagging.gross_wt);
        	$('#cur_gross_wt').val(tagging.cur_gross_wt);
        	$('#less_wt').val(tagging.less_wt);
        	$('#piece').val(tagging.piece);
        	$('#cur_pieces').val(tagging.piece);
        	$('#size').val(tagging.size);
        	$('#sales_value').val(tagging.sales_value);
        	$('#lot_bal_wt').html(tag_balance.lot_bal_wt);
        	$('#lot_bal_pcs').html(tag_balance.lot_bal_pcs);
        	$('#tax_percentage').val(tax_percentage.tax_percentage);
        	$('#tgi_calculation').val(tax_percentage.tgi_calculation);
        	$('#wastage_percentage').val(tagging.retail_max_wastage_percent);
        	$('#making_charge').val(tagging.tag_mc_value);
        	$('#metal_rate').val(metal_rate.goldrate_22ct);
        	$('#tag_mc_type').val(tagging.tag_mc_type);
        	$('#sell_rate').val(tagging.sell_rate);
        	if(tagging.calculation_based_on == 3){
				$('#sell_rate_type').html("Per Piece");
			}
			else if(tagging.calculation_based_on == 4){
				$('#sell_rate_type').html("Per Gram");
			} 
        	$('#adjusted_item_rate').val(tagging.adjusted_item_rate);
        	if(tagging.calculation_based_on==0)
        	{
        		$('#type0').prop('checked',true)
        	}
        	else if(tagging.calculation_based_on==1)
        	{
        		$('#type1').prop('checked',true)
        	}
        	else if(tagging.calculation_based_on==2)
        	{
        		$('#type2').prop('checked',true)
        	}
        	else if(tagging.calculation_based_on==3)
        	{
        		$('#type3').prop('checked',true)
        	}
        	else if(tagging.calculation_based_on==4)
        	{
        		$('#type4').prop('checked',true)
        	}
        	else
        	{
        		$('#type2').prop('checked',true)
        	}
        	if(stone_details.length>0)
        	{
					$.each(stone_details, function (pkey, pitem) {
					var stones_list='';
					$.each(stones, function (pkey, item) 
					{
						var selected = "";
						if(item.stone_id == pitem.stone_id)
						{
						selected = "selected='selected'";
						}
						stones_list += "<option value='"+pitem.stone_id+"' "+selected+">"+item.stone_name+"</option>";
					});	
					row+='<tr><td><select class="stone_id" name="est_stones_item[stone_id][]">'+stones_list+'</select></td><td><input type="number" class="stone_pcs" name="est_stones_item[stone_pcs][]" value="'+pitem['pieces']+'" style="width:80px;"/></td><td><input class="stone_wt" type="number" name="est_stones_item[stone_wt][]" value="'+pitem['wt']+'" style="width:80px;"/></td><td><input type="number" class="stone_price" name="est_stones_item[stone_price][]" value="'+pitem['amount']+'" style="width:80px;"/></td><td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';
					});
        			$('#tagEditModal .modal-body').find('#tagging_stone_details tbody').append(row);
        	}
        	calculateTagEditSaleValue();
			$(".overlay").css("display", "none");	
        },
        error:function(error)  
        {	
        } 
    	});
 }
  $('#tagEditModal #close').click(function (e) {
  	     $('#tagEditModal .modal-body').find('#tagging_stone_details tbody').empty();
  	     $('#tagEditModal .modal-body').find('#preview').empty();
  	     $('#tagEditModal').modal('toggle');
 });
 $('#gross_wt').on('change',function(){
		var lot_bal_wt=parseInt($('#lot_bal_wt').html()); 
		var cur_gross_wt=$('#cur_gross_wt').val();
		var gross_wt=parseFloat($('#gross_wt').val()).toFixed(4);
		if(lot_bal_wt==0)
		{
			if(gross_wt>cur_gross_wt)
			{
				$('#gross_wt').val('');
				$('#gross_wt').focus();
			}
		}else if(lot_bal_wt<gross_wt)
		{
			$('#gross_wt').val('');
			$('#gross_wt').focus();
		}
	});
 $('#gross_wt, #less_wt,#making_charge,#wastage_percentage,#sell_rate,#adjusted_item_rate').on('keyup', function(e){
		var gross_wt = (isNaN($('#gross_wt').val()) || $('#gross_wt').val() == '')  ? 0 : $('#gross_wt').val();
		var less_wt  = (isNaN($('#less_wt').val()) || $('#less_wt').val() == '')  ? 0 : $('#less_wt').val();
		var net_wt = parseFloat(gross_wt) - parseFloat(less_wt);
		$('#net_wt').val(net_wt);
		calculateTagEditSaleValue();
	});
 $('#piece').on('change',function(){
	var pieces=$('#piece').val();
	var cur_pieces=$('#cur_pieces').val();
	var lot_bal_pcs=$('#lot_bal_pcs').html();
	if(lot_bal_pcs==0)
	{
		if(pieces>cur_pieces)
		{
			$('#piece').val('');
			$('#piece').focus();
		}
	}else if(lot_bal_pcs<pieces)
	{
		$('#piece').val('');
		$('#piece').focus();
	}
});
$("#tag_mc_value,#piece").on('keyup', function(e){
	calculateTagEditSaleValue();
});
$('input[type=radio][name="calculation_based_on"]').change(function() {
	calculateTagEditSaleValue();
});
$('#tag_mc_type').change(function() {
	calculateTagEditSaleValue();
});
function calculateTagEditSaleValue(){
	var total_price = 0;
	var base_value_price = 0;
	var arrived_value_price = 0;
	var base_value_tax = 0;
	var arrived_value_tax = 0;
	var base_rate_tax = 0;
	var arrived_rate_tax = 0;
	var total_tax_per = 0;
	var total_tax_rate = 0;
	var stone_price=0;
	var material_price  = 0;
	var rate_with_mc  = 0;
	var gross_wt = (isNaN($('#gross_wt').val()) || $('#gross_wt').val() == '')  ? 0 : $('#gross_wt').val();
	var less_wt  = (isNaN($('#less_wt').val()) || $('#less_wt').val() == '')  ? 0 : $('#less_wt').val();
	var net_wt = (isNaN($('#net_wt').val()) || $('#net_wt').val() == '')  ? 0 : $('#net_wt').val();
	var no_of_piece = (isNaN($(".piece").val()) || $('#piece').val() == '')  ? 0 : $('#piece').val();
	var calculation_type =$('input[name="calculation_based_on"]:checked').val();
	var rate_per_grm = $('#metal_rate').val();
	var tgi_calculation_type=$('#tgi_calculation').val().split(",");
	var tax_percentage=$('#tax_percentage').val().split(",");
	var retail_max_mc = (isNaN($('#making_charge').val()) || $('#making_charge').val() == '')  ? 0 : $('#making_charge').val();
	var tot_wastage   = (isNaN($('#wastage_percentage').val()) || $('#wastage_percentage').val() == '')  ? 0 : $('#wastage_percentage').val();
	$('#tagging_stone_details> tbody  > tr').each(function(index, tr) {
		stone_price+=(isNaN(parseFloat($(this).find('.stone_price').val())) ?0:parseFloat($(this).find('.stone_price').val()))
	}); 
	/** 
	*	Amount calculation based on settings (without discount and tax )
	*   0 - Wastage on Gross weight And MC on Gross weight 
	*   1 - Wastage on Net weight And MC on Net weight
	*   2 - Wastage On Netwt And MC On Grwt
	*   rate_with_mc = Metal Rate + Stone + OM + Wastage + MC
	*/
	if(calculation_type == 0){ 
		var wast_wgt      = parseFloat(parseFloat(gross_wt) * parseFloat(tot_wastage/100)).toFixed(3);
		var mc_type       =  parseFloat($('#tag_mc_type').val() == 1 ? parseFloat(retail_max_mc * gross_wt ) : parseFloat(retail_max_mc * $('#piece').val()));
		// Metal Rate + Stone + OM + Wastage + MC
		rate_with_mc = parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(material_price));
	}
	else if(calculation_type == 1){
		var wast_wgt      = parseFloat(parseFloat(net_wt) * parseFloat(tot_wastage/100)).toFixed(3);
		var mc_type       =  parseFloat($('#tag_mc_type').val() == 1 ? parseFloat(retail_max_mc * net_wt ) : parseFloat(retail_max_mc * $('#piece').val()));
		// Metal Rate + Stone + OM + Wastage + MC
		rate_with_mc = parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(material_price));
	}
	else if(calculation_type == 2){ 
		var wast_wgt      = parseFloat(parseFloat(net_wt) * parseFloat(tot_wastage/100)).toFixed(3);
		var mc_type       =  parseFloat($('#tag_mc_type').val() == 1 ? parseFloat(retail_max_mc * gross_wt ) : parseFloat(retail_max_mc * $('#piece').val()));
		// Metal Rate + Stone + OM + Wastage + MC
	    rate_with_mc = parseFloat((parseFloat(rate_per_grm) * (parseFloat(wast_wgt) + parseFloat(net_wt)))+ parseFloat(mc_type))+parseFloat(stone_price)+parseFloat(material_price);
	}
	else if(calculation_type == 3){
		var sell_rate  = (isNaN($('.sell_rate').val()) || $('.sell_rate').val() == '')  ? 0 : $('.sell_rate').val();
		var adjusted_item_rate  = (isNaN($('.adjusted_item_rate').val()) || $('.adjusted_item_rate').val() == '')  ? 0 : $('.adjusted_item_rate').val();
	    caculated_item_rate = parseFloat(parseFloat(sell_rate)*parseFloat(no_of_piece));
	    $("#caculated_item_rate").val(caculated_item_rate);
	    rate_with_mc = parseFloat(parseFloat(adjusted_item_rate) > 0 ? parseFloat(adjusted_item_rate) : caculated_item_rate ); 
	}
	else if(calculation_type == 4){
		var sell_rate  = (isNaN($('.sell_rate').val()) || $('.sell_rate').val() == '')  ? 0 : $('.sell_rate').val();
		var adjusted_item_rate  = (isNaN($('.adjusted_item_rate').val()) || $('.adjusted_item_rate').val() == '')  ? 0 : curRow.find('.adjusted_item_rate').val();
	    caculated_item_rate = (parseFloat(sell_rate)*parseFloat(net_wt))*parseFloat(no_of_piece);
	    $("#caculated_item_rate").val(caculated_item_rate); 
	    rate_with_mc = parseFloat(parseFloat(adjusted_item_rate) > 0 ? parseFloat(adjusted_item_rate) : caculated_item_rate ); 
	}	 
	/*console.log('Calculation : '+calculation_type);
	console.log('Wastage : '+wast_wgt);
	console.log('Total Wastage : '+tot_wastage);
	console.log('MC : '+mc_type);
	console.log('Rate with MC : '+rate_with_mc);
	console.log(' MC TYPE : '+mc_type);
	console.log(' Rate Per Gram : '+rate_per_grm);*/
	// Tax Calculation
	$.each(tgi_calculation_type,function(ckey,citem)
	{
		if(citem==1) //Base value
		{
			$.each(tax_percentage,function(key,item){
				if(ckey==key)
				{
					base_value_tax+=(parseFloat(item));
				}
			});
			base_rate_tax	 = parseFloat(rate_with_mc*parseFloat(( base_value_tax / 100)));
			base_value_price = parseFloat(rate_with_mc +base_rate_tax).toFixed(2);
		}
		if(citem==2) //arrived value
		{
				$.each(tax_percentage,function(key,item){
				if(ckey==key)
				{
					arrived_value_tax+=(parseFloat(item));
				}
				});
			arrived_rate_tax    = parseFloat(parseFloat(base_value_price)*parseFloat(( arrived_value_tax / 100)));
			arrived_value_price = parseFloat(parseFloat(base_value_price)+arrived_rate_tax).toFixed(2);
		}
	});
	total_tax_rate=parseFloat(parseFloat(base_rate_tax)+parseFloat(arrived_rate_tax)).toFixed(2);
	total_tax_per=parseFloat(parseFloat(base_value_tax)+parseFloat(arrived_value_tax)).toFixed(2);
	total_price=parseFloat(arrived_value_price!=0 ? arrived_value_price:base_value_price).toFixed(2);
	$('#sales_value').val(Math.round(total_price));
	/*console.log('Amount : '+total_price);
	console.log('Tax Rate : '+total_tax_rate);
	console.log('Arrived value : '+arrived_value_price);
	console.log('*************************');*/
}
$('#update_tag').on('click',function(){
	$('#img_source').val(JSON.stringify(pre_img_resource));
	var tagging=$('#tagging').serialize();
	my_Date = new Date();
		$.ajax({ 
		url:base_url+ "index.php/admin_ret_tagging/updateTag?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),
		data:tagging,        
		type:"POST",
        dataType:"JSON",
        success:function(data)
        {	
        	$('#tagEditModal').modal('toggle');
        	//alert(data.msg);
        	window.location.reload();
            $('#tagEditModal .modal-body').find('#tagging_stone_details tbody').empty();
            $('#tagEditModal .modal-body').find('#preview').empty();
        	//get_tagging_list();
			$(".overlay").css("display", "none");	
        },
        error:function(error)  
        {	
        } 
    	});
});
$('#tagEditModal .modal-body #add_stone_details').on('click', function(){
if(validateStoneItemDetailRow()){
			create_new_empty_est_cat_stone_item();
		}else{
			alert("Please fill required fields");
		}
});
function validateStoneItemDetailRow(){
	var row_validate = true;
	$('#tagEditModal .modal-body #tagging_stone_details> tbody  > tr').each(function(index, tr) {
		if($(this).find('td:first .stone_id').val() == "" || $(this).find('td:eq(1) .stone_pcs').val() == "" || $(this).find('td:eq(2) .stone_wt').val() == "" ){
			row_validate = false;
		}
	});
	return row_validate;
}
function create_new_empty_tag_stone_item(){
    create_new_empty_stone_item();
    $('#cus_stoneModal').modal('show');
}
function create_new_empty_stone_item()
{
	var stones_list = "<option value=''> -Select Stone- </option>";
			$.each(stones, function (pkey, pitem) {
				stones_list += "<option value='"+pitem.stone_id+"'>"+pitem.stone_name+"</option>";
			});
			var row_cls = $('#estimation_stone_cus_item_details tbody tr').length;
                row = '<tr id="'+$('#estimation_stone_cus_item_details tbody tr').length+'" class="st_'+$('#estimation_stone_cus_item_details tbody tr').length+'">'
					+'<td><select class="stone_id form-control" name="est_stones_item[stone_id][]">'+stones_list+'</select><input type="hidden" class="stone_type" value=""></td>'
					+'<td><input type="number form-control" class="stone_pcs" name="est_stones_item[stone_pcs][]" value="" style="width: 60px;"/></td>'
					+'<td><input class="stone_wt form-control" type="number" name="est_stones_item[stone_wt][]" value="" style="width: 60px;"/></td>'
					+'<td><input type="number form-control" class="stone_price" name="est_stones_item[stone_price][]" value=""  style="width:60px;"/></td>'
					+'<td><input type="checkbox" class="is_certification" name="est_stones_item[is_certification][]" value=""  /></td>'
					+'<td><input type="number form-control" class="price" name="est_stones_item[price][]" value=""  style="width: 60px;"/></td>'
					+'<td style="width:100%;"></td>'
					+'<td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';
	$('#cus_stoneModal .modal-body').find('#estimation_stone_cus_item_details tbody').append(row);
	$('#custom_active_id').val("st_"+row_cls);
}
function create_new_empty_est_cat_stone_item()
{
			var row='';
			var stones_list = "<option value=''>-Select Stone-</option>";
			$.each(stones, function (pkey, pitem) {
				stones_list += "<option value='"+pitem.stone_id+"'>"+pitem.stone_name+"</option>";
			});
			row += '<tr><td><select class="stone_id" name="est_stones_item[stone_id][]">'+stones_list+'</select></td><td><input type="number" class="stone_pcs" name="est_stones_item[stone_pcs][]" value="" style="width:80px;"/></td><td><input class="stone_wt" type="number" name="est_stones_item[stone_wt][]" value="" style="width:80px;"/></td><td><input type="number" class="stone_price" name="est_stones_item[stone_price][]" value=""  style="width:80px;"/></td><td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';
	$('#tagEditModal .modal-body').find('#tagging_stone_details tbody').append(row);
}
$(document).on('keyup','.stone_wt,.stone_price,.stone_pcs',function(e){
		calculateTagEditSaleValue();
});
$(document).on('click','.btn-del',function(e){
		//calculateTagEditSaleValue();
});
 //Update Tag list
 
 //duplicate print
function get_ActiveProduct()
{

	$('#prod_select option').remove();  
	$("div.overlay").css("display", "block"); 
	$.ajax({
	type: 'GET',
	url: base_url+'index.php/admin_ret_reports/get_ActiveProduct',
	dataType:'json',
	success:function(data){
		var id =  $("#id_design").val();
		$.each(data, function (key, item) {   
		    $("#prod_select").append(
		    $("<option></option>")
		    .attr("value", item.pro_id)    
		    .text(item.product_name)  
		    );
		});
		   
		$("#prod_select").select2(
		{
			placeholder:"Select Product",
			allowClear: true		    
		});
		    $("#prod_select").select2("val",(id!='' && id>0?id:''));
		}
	});
	$("div.overlay").css("display", "none"); 
}


function get_ActiveSize()
{
	$('#select_size option').remove();  
	$("div.overlay").css("display", "block"); 
	$.ajax({
	type: 'POST',
	data :{'id_product':$('#prod_select').val()},
	url: base_url+'index.php/admin_ret_tagging/get_ActiveSize',
	dataType:'json',
	success:function(data){
		var id =  $("#id_size").val();
		$.each(data, function (key, item) {   
		    $("#select_size").append(
		    $("<option></option>")
		    .attr("value", item.id_size)    
		    .text(item.value+'-'+item.name)  
		    );
		});
		   
		$("#select_size").select2(
		{
			placeholder:"Select Size",
			allowClear: true		    
		});
		    $("#select_size").select2("val",(id!='' && id>0?id:''));
		}
	});
	$("div.overlay").css("display", "none"); 
}

$('#prod_select').on('change',function(){
	if(this.value!='')
	{
		get_Activedesign(this.value);
		if(ctrl_page[2]=='tag_edit')
		{
		    get_ActiveSize();
		}
	}else{
		get_Activedesign('');
	}
	

	
});

function get_Activedesign(id_product)
{

	$('#des_select option').remove();
	$.ajax({
	type: 'POST',
	url: base_url+'index.php/admin_ret_reports/get_Activedesign',
	data :{'id_product':id_product},
	dataType:'json',
	success:function(data){
	    pro_designs = data;
		var id =  $("#id_design").val();
		$.each(data, function (key, item) {   
		    $("#des_select").append(
		    $("<option></option>")
		    .attr("value", item.design_no)    
		    .text(item.design_name)  
		    );
		});
		   
		$("#des_select").select2(
		{
			placeholder:"Select Design",
			allowClear: true		    
		});
		    
		    if(ctrl_page[2]=='duplicate_print')
		    {
		        $("#des_select").select2("val",'');
		    }else{
		        $("#des_select").select2("val",(id!='' && id>0?id:''));
		    }
		}
	});
}

$('#get_duplicate_tag').on('click',function(){
     $("div.overlay").css("display", "block"); 
	var tag_lot_id=$('#lot_id').val();
	var from_weight=$('#from_weight').val();
	var to_weight=$('#to_weight').val();
		my_Date = new Date();
			$.ajax({
				url: base_url+'index.php/admin_ret_tagging/get_duplicate_tag/?nocache=' + my_Date.getUTCSeconds(),             
				dataType: "json", 
				method: "POST", 
				data: {'tag_lot_id':tag_lot_id,'id_branch':$('#id_branch').val(),'tag_id':$('#tag_id').val(),'id_product':$('#prod_select').val(),'des_select':$('#des_select').val(),'from_weight':from_weight,'to_weight':to_weight}, 
				success: function ( data ) { 
				   $.each(data,function(key,item){
				        dublicate_tag_details.push({'tag_id':item.tag_id,'tag_code':item.tag_code,'tag_lot_id':item.tag_lot_id,'product_name':item.product_name,'design_name':item.design_name,'gross_wt':item.gross_wt,'net_wt':item.net_wt});
				    });
					set_duplicate_tag_print();
				}
			 });
	
	$("div.overlay").css("display", "none"); 
});

$('#add_tag').on('click',function(){
    $("div.overlay").css("display", "block"); 
		my_Date = new Date();
			$.ajax({
				url: base_url+'index.php/admin_ret_tagging/get_duplicate_tag/?nocache=' + my_Date.getUTCSeconds(),             
				dataType: "json", 
				method: "POST", 
				data: {'id_branch':$('#id_branch').val(),'tag_id':$('#tag_id').val(),'id_product':$('#prod_select').val(),'des_select':$('#des_select').val()}, 
				success: function ( data ) { 
				   $.each(data,function(key,item){
				        dublicate_tag_details.push({'tag_id':item.tag_id,'tag_code':item.tag_code,'tag_lot_id':item.tag_lot_id,'product_name':item.product_name,'design_name':item.design_name,'gross_wt':item.gross_wt,'net_wt':item.net_wt});
				    });
					set_duplicate_tag_print();
				}
			 });
	
	$("div.overlay").css("display", "none"); 
});

function set_duplicate_tag_print()	
{
    var rowExist = false;
    var row ='';
    $.each(dublicate_tag_details,function(key,item){
    $('#tagging_list > tbody tr').each(function(bidx, trow){
        tag_det = $(this);
    
        if(tag_det.find('.tag_id').val() != '')
        {
            if(item.tag_id==tag_det.find('.tag_id').val())
            {
                rowExist = true;
            }
        }
    });
        if(!rowExist)
        {
             row = '<tr id="'+key+'">'
                +'<td><input type="checkbox" class="tag_id" name="tag_id[]" value="'+item.tag_id+'"/>'+item.tag_id+'</td>'
                +'<td>'+item.tag_code+'</td>'
                +'<td>'+item.tag_lot_id+'</td>'
                +'<td>'+item.product_name+'</td>'
                +'<td>'+item.design_name+'</td>'
                +'<td>'+item.gross_wt+'</td>'
                +'<td>'+item.net_wt+'</td>'
                $('#tagging_list  tbody').append(row); 
        }
    });

    dublicate_tag_details=[];
}

$('#duplicate_print').on('click',function(){
	if($("input[name='tag_id[]']:checked").val())
	{
		send_otp();
	}else{
		alert('Please Select Atleast Any One Tag');
	}
});

$('#tagResend').on('click',function(){
	send_otp();
});

function send_otp()
{
	$('#tagResend').css('display','none');
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_tagging/send_tag_otp?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),
			 type:"POST",
			 dataType: "json", 
			 async:false,
			 	  success:function(data){
			 	  	if(data.status)
			 	  	{
			 	  		if(data.status)
			 	  		{
			 	  		if(data.sms_req==1)
			 	  		    {
                                $('#otp_modal').modal('show');
                                setTimeout(function() {
                                $('#tagResend').css('display','block');
                                },60000);
			 	  		    }
			 	  		    else
			 	  		    {
    	 	  		            var tag_id='';
		 	  		        	$("#tagging_list tbody tr").each(function(index, value){
                    				if($(value).find("input[name='tag_id[]']:checked").is(":checked"))
                    				{
                        			    tag_id+= $(value).find(".tag_id").val()+',';
                    				}
                				});
                				req_data = tag_id;
                				tagging_print(req_data);
			 	  		    }
			 	  		}else{
			 	  			alert(data.msg);
			 	  		}
			 	  	}
				  },
				  error:function(error)  
				  {
					 $(".overlay").css('display',"none"); 
				  }	 
		  });
}

$('#verify_otp').on('click',function(){
    if(this.value!='')
    {
        my_Date = new Date();
    	$.ajax({
    		 url:base_url+ "index.php/admin_ret_tagging/verify_otp?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),
    		 type:"POST",
    		 data :{'otp':$('#otp').val()},
    		 dataType: "json", 
    	 	  success:function(data)
    	 	  {
    	 	  	if(data.status)
    	 	  	{
    	 	  		var selected = [];
    	 	  		var tag_id='';
    				$("#tagging_list tbody tr").each(function(index, value){
    				if($(value).find("input[name='tag_id[]']:checked").is(":checked"))
    				{
    			    tag_id+= $(value).find(".tag_id").val()+',';
    				transData = { 
    				'tag_id': $(value).find(".tag_id").val(),
    				'net_wt': $(value).find(".net_wt").val(),
    				'product_name': $(value).find(".product_name").val(),
    				'size': $(value).find(".size").val(),
    				'tag_code': $(value).find(".tag_code").val(),
    				'product_id': $(value).find(".product_id").val(),
    				'short_code': $(value).find(".short_code").val(),
    				'code_karigar': $(value).find(".code_karigar").val(),
    				'tot_print_taken': $(value).find(".tot_print_taken").val(),
    				}
    				selected.push(transData);	
    				}
    				});
    				req_data = tag_id;
    				tagging_print(req_data);
    	 	  	}else{
    	 	  		$('#otp').val('');
    	 	  		alert(data.msg);
    	 	  	}
    		  },
    		  });
    }else{
        alert('Please Enter The OTP');
    }
});

//duplicate print
function printBTtagData() {  
	if(ctrl_page[2] != ''){
		window.open( base_url+'index.php/admin_ret_brntransfer/branch_transfer/print/'+ctrl_page[2]+'/1'+'/'+'1','_blank');		
	}
	//window.location.replace( base_url+'index.php/admin_ret_tagging/tagging/list'); 
}

   	/*$('#tag_id').blur(function(e){
		var row = $(this).closest('tr'); 
		var input =this.value;	
		var tag_id=input.split('/')[0];
		var print_taken=input.split('/')[1];
		console.log($(this));
		setTimeout(function () {
			get_tag_scan_details(tag_id,print_taken,row);
		}, 100);
	});*/

   $(document).bind('paste',"#tag_id", function(e){
		var row = $(this).closest('tr'); 
	    console.log($(this));
		var input = $(this).val();
		var tag_id=input.split('/')[0];
		var print_taken=input.split('/')[1];
		setTimeout(function () {
			$('#tag_id').trigger('blur');
			get_tag_scan_details();
		}, 100);
		
	});

function get_tag_scan_details()
{
    var input=$('#tag_id').val();
    var tag_id=input.split('/')[0];
    var print_taken=input.split('/')[1];
    my_Date = new Date();
    $.ajax({
        url: base_url+'index.php/admin_ret_tagging/get_tag_scan_details/?nocache=' + my_Date.getUTCSeconds(),             
        dataType: "json", 
        method: "POST", 
        data: {'tag_id': tag_id, 'print_taken':print_taken}, 
        success: function (data) {
            if(data.status)
            {
                set_tag_scanned_list(data.tag_details);
            }else{
                $('#tag_id').val('');
                alert(data.msg);
                $("div.overlay").css("display", "none"); 
            }
        }
    });
}

function set_tag_scanned_list(tag_details)
{
	$("div.overlay").css("display", "block"); 
	var row='';
	row='<tr><td>'+tag_details.tag_code+'</td><td>'+tag_details.tag_lot_id+'</td><td>'+tag_details.gross_wt+'</td><td>'+tag_details.net_wt+'</td><td>'+(tag_details.less_wt!=null ? tag_details.less_wt:'-')+'</td></tr>'
	$('#tagging_scan_list tbody').append(row);
	$("div.overlay").css("display", "none"); 
}


$('#tag_mark_search').on('click',function(){
	set_tag_marking();
});

function set_tag_marking()
{
	$("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
		url: base_url+"index.php/admin_ret_tagging/get_tag_marking/ajax?nocache=" + my_Date.getUTCSeconds(),
		type:"POST",
		data:{'id_branch':$("#id_branch").val(),'id_product':$("#prod_select").val(),'est_no':$('#est_no').val(),'filter_by':$('#filter_by').val()},
		dataType: 'json',
		cache:false,
		success:function(data){
			if(data.status)
			{
			    var list=data.data;
    			 var oTable = $('#tagging_list').DataTable();
    			 oTable.clear().draw();
    			 if (list!= null && list.length > 0)
    			 {  	
    				oTable = $('#tagging_list').dataTable({
    						"bDestroy": true,
    						"bInfo": true,
    						"bFilter": true,
    						"bSort": true,
    						"order": [[ 0, "desc" ]],				
    						"aaData"  : list,
    						"lengthMenu": [[-1, 25, 50, 100, 250], ["All" ,25, 50, 100, 10]],
    						"aoColumns": [
    					    { "mDataProp": function ( row, type, val, meta )
                            { 
                                 if(row.status==0)
                                 {
                                     return '<input type="checkbox" class="tag_id" name="tag_id[]" value="'+row.tag_id+'"/>'+row.tag_code;
                                 }else{
                                    return row.tag_code;
                                 }
                                    
                            }},
    						{ "mDataProp": "product_name" },
    						{ "mDataProp": "tag_date" },
    						{ "mDataProp": "gross_wt" },
    						{ "mDataProp": "net_wt" },
    						{ "mDataProp": function ( row, type, val, meta )
							{ 
							    if(row.status==1)
							    {
							        return '<span class="badge bg-green">'+row.tag_status+'</span>';
							    }else
							    {
							        return '<span class="badge bg-red">'+row.tag_status+'</span>';
							    }
    		                }},
    						{ "mDataProp": "tag_mark" },
    					
    						]
    					});			  	 	
    				}
		    }
		    else{
		        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+data.message});
		    }
			$("div.overlay").css("display", "none"); 
		},
		 error:function(error)  
		  {
			 $("div.overlay").css("display", "none"); 
		  }	
	});
}


$('#tag_edit_filter').on('click',function(){
    get_tag_edit_det();
});

$("input[name='upd_status_btn']:radio").change(function(){
    if($("input[name='tag_id[]']:checked").val())
        {
            $('#set_green_tag').prop('disabled',true);
            var selected = [];
            var approve=false;
            $("#tagging_list tbody tr").each(function(index, value)
            {
                if($(value).find("input[name='tag_id[]']:checked").is(":checked"))
                {
                    transData = { 
                    'tag_id'            : $(value).find(".tag_id").val(),
                    "req_status"        : $("input[name='upd_status_btn']:checked").val(),
                    }
                    selected.push(transData);	
                }
            })
            req_data = selected;
            update_green_tag(req_data);
        }else{
            $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select Any One Tag..'});
        }
});


function update_green_tag(req_data)
{
    my_Date = new Date();
    $("div.overlay").css("display", "block"); 
    $.ajax({
    url:base_url+ "index.php/admin_ret_reports/update_green_tag?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),
    data:  {'req_data':req_data},
    type:"POST",
    async:false,
    success:function(data){
    $('#set_green_tag').prop('disabled',false);
    location.reload(false);
    $("div.overlay").css("display", "none"); 
    },
    error:function(error)  
    {
    console.log(error);
    $("div.overlay").css("display", "none"); 
    }	 
    });
}


function get_tag_edit_det()
{
    if($('#prod_select').val()!='' && $('#prod_select').val()!=null && $('#prod_select').val()!=undefined)
    {
    	$("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
		url: base_url+"index.php/admin_ret_tagging/get_tag_edit_det/ajax?nocache=" + my_Date.getUTCSeconds(),
		type:"POST",
		data:{'lot_id' :$("#tag_edit_lot").val(),'id_branch':$("#branch_select").val(),'id_product':$('#prod_select').val()},
		dataType: 'json',
		cache:false,
		success:function(data){
			
			 $("div.overlay").css("display","add_newstone");
			 var oTable = $('#tagging_list').DataTable();
			 oTable.clear().draw();
			 if (data!= null && data.length > 0)
			 {  	
				oTable = $('#tagging_list').dataTable({
						"bDestroy": true,
						"bInfo": true,
						"bFilter": true,
						"bSort": true,
						"order": [[ 0, "desc" ]],
						"lengthMenu": [ [ 10, 25, 50, -1], [10, 25, 50, "All"] ],
						"aaData"  : data,
						"aoColumns": [
						{ "mDataProp": function ( row, type, val, meta ){ 
		                	chekbox='<input type="checkbox" class="tag_id" name="tag_id[]" value="'+row.tag_id+'"/><input type="hidden" class="design_id" name="design_id[]" value="'+row.design_id+'"/><input type="hidden" class="size" name="size[]" value="'+row.size+'"/>' 
		                	return chekbox+" "+row.tag_id;
		                }},	
						{ "mDataProp": "tag_code" },
						{ "mDataProp": "tag_lot_id" },
						{ "mDataProp": "product_name" },
						{ "mDataProp": "design_name" },
						{ "mDataProp": "gross_wt" },
						{ "mDataProp": "net_wt" },
						{ "mDataProp": "size_name" },
						]
					});			  	 	
				}
				$("div.overlay").css("display", "none"); 
		},
		 error:function(error)  
		  {
			 $("div.overlay").css("display", "none"); 
		  }	
	});
    }else{
        alert('Please Select Product');
    }
}

function get_tag_edit_lots(){
	$.ajax({		
	 	type: 'GET',		
	 	url : base_url + 'index.php/admin_ret_tagging/get_lot_ids',		
	 	dataType : 'json',		
	 	success  : function(data){
		 	var id =  $('#tag_lot_id').val();
		 	console.log(id);
	     	$.each(data.lot_inward, function (key, item) {				  			   		
    		 		$("#tag_edit_lot").append(						
    	    	 	$("<option></option>")						
    	    	 	.attr("value", item.lot_no)
    	    	 	.text(item.lot_no)						  					
    	    	 	);
	     	});
	     	$("#tag_edit_lot").select2({			    
        	 	placeholder: "Select Lot No",			    
        	 	allowClear: true		    
         	});
	     	$("#tag_edit_lot").select2("val",(id!='' && id>0?id:''));	 
	     	$(".overlay").css("display", "none");			
	 	}	
	}); 
}



     $("#update_tag_edit").on('click',function(){
        if($("input[name='tag_id[]']:checked").val())
        {
            if($('#prod_select').val()!='' && $('#prod_select').val()!=null && $('#prod_select').val()!=undefined)
            {
               
                    var selected = [];
                    var approve=false;
                    $("#tagging_list tbody tr").each(function(index, value)
                    {
                    if($(value).find("input[name='tag_id[]']:checked").is(":checked"))
                    {
                    transData = { 
                    'tag_id'   : $(value).find(".tag_id").val(),
                    'id_design': $(value).find(".design_id").val(),
                    'id_size'  : $(value).find(".size").val(),
                    }
                    selected.push(transData);	
                    }
                    })
                    req_data = selected;
                    console.log(req_data);
                    update_tag(req_data);

            }else{
                alert('Please Select Product');
                return false;
            }
            
           
        }else{
            alert('Please Select Any One Tag.');
        }
    });
    
    function update_tag(data="")
    {
        my_Date = new Date();
        $("div.overlay").css("display", "block"); 
        $.ajax({
        url:base_url+ "index.php/admin_ret_tagging/update_tag?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),
        data:  {'id_design':$('#des_select').val(),'id_size':$('#select_size').val(),'req_data':data},
        type:"POST",
        async:false,
        success:function(data){
        location.reload(false);
        $("div.overlay").css("display", "none"); 
        },
        error:function(error)  
        {
        console.log(error);
        $("div.overlay").css("display", "none"); 
        }	 
        });
    }
    
    function get_branchwise_emp()
	{
		my_Date = new Date();
		$.ajax({ 
		url:base_url+ "index.php/admin_ret_tagging/get_employee?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),
        type:"GET",
        dataType:"JSON",
        success:function(data)
        {
           var id_employee=$('#id_employee').val();
           emp_details=data;
           $.each(data, function (key, item) {					  				  			   		
                	 	$("#emp_select").append(						
                	 	$("<option></option>")						
                	 	.attr("value", item.id_employee)						  						  
                	 	.text(item.firstname)						  					
                	 	);			   											
                 	});						
             	$("#emp_select").select2({			    
            	 	placeholder: "Select Employee",			    
            	 	allowClear: true		    
             	});					
         	    $("#emp_select").select2("val",(id_employee!='' && id_employee>0?id_employee:''));	 
         	    $(".overlay").css("display", "none");	
        },
        error:function(error)  
        {	
        } 
    	});
	}
	
	
$("#add_multiple_tag").on('click', function () {
    var rows = $('#row_value').val();
    console.log(rows);
    var lastRow;
    for (var i = 0; i < rows; i++) {
        lastRow = $('#lt_item_list tr').last().clone();
        console.log(lastRow.find('.lot_id_design').val());
        set_multiple_rows($('#tag_lot_id').val(),$('#tag_lt_prodId').val(),lastRow.find('.gross_wt').val(),lastRow.find('.lot_id_design').val(),lastRow.find('.cus_design ').val(),lastRow.find('.wastage_percentage').val(),lastRow.find('.making_charge').val(),lastRow.find('.sale_value').val(),lastRow.find('.net_wt').val(),lastRow.find('.less_wt').val(),lastRow.find('.making_charge').val(),lastRow.find('.wastage_percentage').val(),lastRow.find('.calc_type').val(),lastRow.find('.making_type').val(),lastRow.find('.sell_rate').val());
        //$('#lt_item_list tr').last().after(lastRow);
    }
});


function set_multiple_rows(lot_no,lot_product,gross_wt,id_design,des_name,wastage_percentage,making_charge,sale_value,net_wt,less_wt,making_charge,wastage_percentage,calculation_based_on,mc_type,sell_rate)
{
	$(".overlay").css("display", "block");
	console.log(id_design);
	console.log(des_name);
	my_Date = new Date();
		$.ajax({ 
		url:base_url+ "index.php/admin_ret_tagging/get_lot_inward_details?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),
        data: {'lot_no':lot_no,'lot_product':lot_product,'lot_id_design':''},
        type:"POST",
        dataType:"JSON",
        success:function(data)
        {
        	if(data.lot_inward_detail.length>0)
        	{
        		//	$('.lot_bal_wt').val(data.lot_inward_detail[0].lot_bal_wt);
					//$('.lot_bal_pcs').val(data.lot_inward_detail[0].lot_bal_pcs);
					$('#lt_tax_group').html(data.tax_percentage.tax_name);
					$('#tax_percentage').val(data.tax_percentage.tax_percentage);
					$('#tgi_calculation').val(data.tax_percentage.tgi_calculation);
        		var row = "";
        		var i=1;
        		var allow_row_create=false;
        		$.each(data.lot_inward_detail, function (key, item) {
					var curr_used_gross = 0;
					var curr_used_pcs = 0;
					var lot_bal_wt=0;
					var blc_lot_wt=0;
					var blc_gross_wt=0;
					var act_gross_blc=0;
					var weight_per=$('#weight_per').val();
					var sales_mode=item.sales_mode;
					
					var size_sel = "<option value=''>- Select Size-</option>";
					$.each(item.size_details, function (mkey, mitem) {
                		size_sel += "<option  value='"+mitem.id_size+"'>"+mitem.value+'-'+mitem.name+"</option>";
                	});	
					
					$('#lot_bal_wt').val(item['lot_blc'].lot_bal_wt);
					$('#lot_bal_pcs').val(item['lot_blc'].lot_bal_pcs);
					var total_row=$('#lt_item_list tbody > tr').length;
					$('#lt_item_list> tbody  > tr').each(function(index, tr) {
						if($(this).find('.id_lot_inward_detail').val() == item.id_lot_inward_detail)
						{ 
							curr_used_gross+=parseFloat(($(this).find('.gross_wt').val()=='' ?0 :$(this).find('.gross_wt').val()));
							curr_used_pcs+=parseFloat(($(this).find('.no_of_piece').val()=='' ?0 :$(this).find('.no_of_piece').val()));							
							act_gross_blc =parseFloat(($(this).find('.act_gross_blc').val()=='' ?0 :$(this).find('.act_gross_blc').val()));							
						}
					});
					if(weight_per>0)
					{
						lot_bal_wt=parseFloat(((item['lot_blc'].lot_bal_wt*weight_per)/100)+parseFloat(item['lot_blc'].lot_bal_wt));
						//lot_bal_wt=parseFloat(lot_bal_wt-curr_used_gross).toFixed(3);
						blc_lot_wt=parseFloat(item['lot_blc'].lot_bal_wt-curr_used_gross);
					}else
					{
						lot_bal_wt=item['lot_blc'].lot_bal_wt;
						blc_lot_wt=parseFloat(item['lot_blc'].lot_bal_wt-curr_used_gross);
					}
					var blc_pcs=item['lot_blc'].lot_bal_pcs - curr_used_pcs;
					blc_gross=parseFloat(act_gross_blc-curr_used_gross);

				if(item.sales_mode!=1)
					{
						if(curr_used_gross==0)
						{
							allow_row_create=true;
						}
						else if(blc_gross>0 && blc_pcs>0)
						{
							allow_row_create=true;
						}else{
							allow_row_create=false;
						}
					}
					else if(item.sales_mode=1 && blc_pcs>0)
					{
						allow_row_create=true;
					}else{
						allow_row_create=false;
					}
				console.log('blc_gross:'+blc_gross);
				console.log('blc_pcs:'+blc_pcs);
				console.log('sales_mode:'+item.sales_mode);
				console.log('*******');
				if(allow_row_create)
				{
					 row += '<tr id='+item.id_lot_inward_detail+' class='+total_row+'>' 
           				 +'<td width="5%">'+item.lot_no+'<input type="hidden" name="lt_item[lot_no][]" value="'+item.lot_no+'" class="lot_no" /><input type="hidden" name="lt_item[id_lot_inward_detail][]" id="id_lot_inward_detail" value="'+item.id_lot_inward_detail+'" class="id_lot_inward_detail"><input type="hidden" name="lt_item[sales_mode][]" id="sales_mode" value="'+sales_mode+'" class="sales_mode"></td>'
           				 +'<td width="10%">'+item.product_name+'<input type="hidden" name="lt_item[lot_product][]" value="'+item.lot_product+'" class="lot_product" /><input type="hidden" name="lt_item[product_short_code][]" value="'+item.product_short_code+'" class="product_short_code" /><input type="hidden" name="lt_item[id_metal][]" value="'+item.id_metal+'" class="id_metal" /></td>'
           				 +'<td width="10%"><input type="text" class="cus_design"  value="'+des_name+'" required style="width:150;"/><input type="hidden" class="lot_id_design" name="lt_item[lot_id_design][]" value="'+id_design+'"></td>'
           				 +'<td width="10%">'+(item.design_for==1 ? 'Men' :(item.design_for==2 ? 'Female':'Unisex'))+'<input type="hidden" name="lt_item[design_for][]" value="'+item.design_for+'" class="design_for" /></td>'
           				 +'<td width="10%"><input type="hidden" name="" value="'+calculation_based_on+'" class="calc_type" /><select class="calculation_based_on" name="lt_item[calculation_based_on][]"><option value="0" '+(calculation_based_on == 0 ? "selected":"")+' '+(calculation_based_on >= 3 ? "disabled":"")+'>Mc & Wast On Gross</option><option value="1" '+(calculation_based_on == 1 ? "selected":"")+' '+(calculation_based_on >= 3 ? "disabled":"")+'>Mc & Wast On Net</option><option value="2" '+(calculation_based_on == 2 ? "selected":"")+' '+(calculation_based_on >= 3 ? "disabled":"")+'>Mc on Gross,Wast On Net</option><option value="3" '+(calculation_based_on == 3 ? "selected":"")+' '+(calculation_based_on == 4 ? "disabled":"")+'>Fixed Rate</option><option value="4" '+(calculation_based_on == 4 ? "selected":"")+' '+(calculation_based_on == 3 ? "disabled":"")+'>Fixed Rate based on Weight</option></select></td>'
           				 +'<td width="5%"><input type="number" step="any" name="lt_item[no_of_piece][]"   class="no_of_piece" value="1" style="width:80px;"/>Blc :<span class="blc_pcs"> '+(item['lot_blc'].lot_bal_pcs - curr_used_pcs)+'</span><input type="hidden" disabled class="act_blc_pcs" value="'+item['lot_blc'].lot_bal_pcs+'" style="width:80px;"></td>'
           				 +'<td width="10%"><input type="number" step="any" name="lt_item[gross_wt][]"   class="gross_wt" value="'+gross_wt+'" style="width:80px;" '+(item.calculation_based_on == 3 ? "readonly":"")+'/>Blc:<span class="blc_gross"> '+parseFloat(item['lot_blc'].lot_bal_wt - curr_used_gross-gross_wt).toFixed(3)+'</span><input type="hidden" class="act_gross_blc" value="'+lot_bal_wt+'"><input type="hidden" class="gross_wt_blc" value="'+item['lot_blc'].lot_bal_wt+'"></td>'
           				 +'<td width="10%"><input type="number" step="any" name="lt_item[less_wt][]"  class="less_wt" value="'+less_wt+'" style="width:80px;" '+(item.calculation_based_on == 3 ? "readonly":"")+'/></td>'
           				 +'<td width="10%"><input type="number" step="any" name="lt_item[net_wt][]"  class="net_wt" value="'+net_wt+'" style="width:80px;" readonly/></td>'
           				 +'<td width="5%"><input type="text" name="lt_item[wastage_percentage][]" value="'+wastage_percentage+'" class="wastage_percentage" style="width:80px;"/></td>'
           				 +'<td><input type="hidden"  value="'+mc_type+'" class="making_type" /><select class="mc_type"><option value="1" '+(mc_type == 1 ? "selected":"")+'>Per Gram</option><option value="2" '+(mc_type == 2 ? "selected":"")+'>Per Piece</option></select><input type="hidden" value="'+mc_type+'" name="lt_item[id_mc_type][]" class="id_mc_type"></td>'
           				 +'<td width="10%"><input type="number" step="any" name="lt_item[making_charge][]"  class="making_charge" value="'+making_charge+'" style="width:80px;"/></td>'
           				 +'<td width="10%"><input type="number" step="any" name="lt_item[sell_rate][]"  class="sell_rate" value="'+sell_rate+'" style="width:80px;" '+(item.calculation_based_on < 3 ? "readonly":"")+'/>'+(item.calculation_based_on == 3 ? "per piece":(item.calculation_based_on == 4 ? "per gram":""))+'</td>'
           				 +'<td width="10%"><select class="size"  name=lt_item[size][]>'+size_sel+'</select></td>'
           				 +'<td><a href="#" onClick="create_new_empty_est_cus_stone_item($(this).closest(\'tr\'));" class="btn btn-default btn-sm"><i class="fa fa-plus"></i></a></td>'
           				 +'<td><a href="#" onClick="update_image_upload($(this).closest(\'tr\'));" class="btn btn-default btn-sm"><i class="fa fa-plus"></i></a></td>'
           				 +'<td width="5%"><input type="number" step="any" name="lt_item[caculated_item_rate][]"  class="caculated_item_rate" value="" style="width:70px;" readonly/></td>'
           				 +'<td width="5%"><input type="number" step="any" name="lt_item[adjusted_item_rate][]"  class="adjusted_item_rate" value="'+item.adjusted_item_rate+'" style="width:80px;" '+(item.calculation_based_on < 3 ? "readonly":"")+'/></td>'
           				 +'<td width="10%"><input type="number" name="lt_item[sale_value][]"  class="sale_value" VALUE="'+sale_value+'" readonly style="width:80px;" /><input type="hidden" class="stone_details" name="lt_item[stone_details][]"><input type="hidden" class="stone_price" name="lt_item[stone_price][]"><input type="hidden" class="price" name="lt_item[price][]"><input type="hidden" class="tag_img" name="lt_item[tag_img][]"><input type="hidden" class="normal_st_certif" value="'+item.normal_st_certif+'"><input type="hidden" class="semiprecious_st_certif" value="'+item.semiprecious_st_certif+'"><input type="hidden" class="precious_st_certif" value="'+item.precious_st_certif+'"></td>'
           				 +'<td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'
            		+'</tr>';
					$('#lt_item_list tbody').append(row);
				} 
        		if(item.calculation_based_on == 3){
					var tr = $('#lt_item_list tbody tr:eq('+total_row+')');  
        			calculateTagSaleValue(tr);
				} 	
        		i++;
				
        		}); 
        	}
         	
        },
        error:function(error)  
        {	
        } 
    	});
    	
    	$(".overlay").css("display", "none");
}

//Order Link

$('#est_tag_name').on('keyup', function(e){ 
    getSearchTags(this.value);
});


function getSearchTags(searchTxt){
	my_Date = new Date();
	$.ajax({
        url: base_url+'index.php/admin_ret_tagging/getTaggingBySearch/?nocache=' + my_Date.getUTCSeconds(),             
        dataType: "json", 
        method: "POST", 
        data: {'searchTxt': searchTxt,'id_branch': $("#branch_select").val()}, 
        success: function (data) {
			cur_search_tags = data;
			$.each(data, function(key, item){
				$('#tagging_list > tbody tr').each(function(idx, row){
					if(item != undefined){
						if($(this).find('.tag_id').val() == item.value){
							data.splice(key, 1);
						}
					}
				});
			});
			$(".est_tag_name").autocomplete(
			{
				source: data,
				select: function(e, i)
				{ 
					e.preventDefault(); 
					var curRowItem = i.item;
			        create_new_empty_tag_row(curRowItem);
					$('.est_tag_name').val(i.item.label);
					
				},
				change: function (event, ui) {
						
			    },
				response: function(e, i) {
		            // ui.content is the array that's about to be sent to the response callback.
		        },
				 minLength: 1,
			});
        }
     });
}


function create_new_empty_tag_row(data)
{
	var row = "";
       row += '<tr>'
			+'<td><input type="checkbox" class="tag_id" name="tag_id[]" value="'+data.tag_id+'">'+data.tag_id+'<input type="hidden" class="tag_id" value="'+data.tag_id+'"></td>'
			+'<td>'+data.tag_code+'</td>'
			+'<td>'+data.tag_lot_id+'</td>'
			+'<td>'+data.product_name+'<input type="hidden" class="id_product" value="'+data.lot_product+'"></td>'
			+'<td>'+data.design_name+'<input type="hidden" class="id_design" value="'+data.design_id+'" ></td>'
			+'<td>'+data.gross_wt+'</td>'
			+'<td>'+data.net_wt+'</td>'
			+'<td><input type="text" class="order_search"  required><input type="hidden" class="id_orderdetails" value=""></td>'
			+'<td><select class="form-control order_det" ></select></td>'
			+'<td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger"><i class="fa fa-trash"></i></a></td>'
			+'</tr>';
	$('#tagging_list tbody').append(row);
	$('#tagging_list > tbody').find('.order_search').focus();
	$('#est_tag_name').val('');
}



$(document).on('keyup',	".order_search", function(e){ 
		var row = $(this).closest('tr');
		var tagData = this.value;
		var type  = "";
		var searchTxt  = "";
		getSearchOrders(this.value,row);
}); 


function getSearchOrders(searchTxt,curRow){
	my_Date = new Date();
	$.ajax({
        url: base_url+'index.php/admin_ret_tagging/getOrdersBySearch/?nocache=' + my_Date.getUTCSeconds(),             
        dataType: "json", 
        method: "POST", 
        data: {'searchTxt': searchTxt,'id_branch': $("#branch_select").val(),'id_product':curRow.find('.id_product').val(),'id_design':curRow.find('.id_design').val()}, 
        success: function (data) {
			cur_search_tags = data;
			$.each(data, function(key, item){
				$('#tagging_list > tbody tr').each(function(idx, row){
					if(item != undefined){
						if($(this).find('.id_customerorder').val() == item.value){
							data.splice(key, 1);
						}
					}
				});
			});
			$(".order_search").autocomplete(
			{
				source: data,
				select: function(e, i)
				{ 
					e.preventDefault(); 
					var curRowItem = i.item;
					curRow.find('.order_search').val(i.item.label);
					
					get_order_details(i.item.value,curRow);
					
				},
				change: function (event, ui) {
						
			    },
				response: function(e, i) {
		            // ui.content is the array that's about to be sent to the response callback.
		        },
				 minLength: 4,
			});
        }
     });
}



function get_order_details(id_customerorder,curRow)
{
    $(".overlay").css("display", "none");
    my_Date = new Date();
		$.ajax({ 
		url:base_url+ "index.php/admin_ret_tagging/getOrderDetailBySearch?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),
        type:"POST",
        data:{'id_branch':$('#branch_select').val(),'id_customerorder':id_customerorder,'id_product':curRow.find('.id_product').val(),'id_design':curRow.find('.id_design').val()},
        dataType:"JSON",
        success:function(data)
        {
           $.each(data, function (key, item) {					  				  			   		
                	 	curRow.find(".order_det").append(						
                	 	$("<option></option>")						
                	 	.attr("value", item.id_orderdetails)						  						  
                	 	.text(item.weight)						  					
                	 	);			   											
                 	});						
         	    $(".overlay").css("display", "none");	
        },
        error:function(error)  
        {	
            $(".overlay").css("display", "none");
        } 
    	});
        $(".overlay").css("display", "none");
}


 $("#tag_link_submit").on('click',function(){
     
        if($("input[name='tag_id[]']:checked").val())
        {
            var allow_submit=true;
            $("#tagging_list tbody tr").each(function(index, value)
            {
              if($(value).find(".order_det").val()=='' || $(value).find(".order_det").val()==null || $(value).find(".order_det").val()==undefined)
              {
                  allow_submit=false;
                  alert('Please Fill The Required Fields..');
              }
            });
                if(allow_submit)
                {
                    $('#tag_link_submit').prop('disabled',true);
                    var selected = [];
                    var approve=false;
                    $("#tagging_list tbody tr").each(function(index, value)
                    {
                    if($(value).find("input[name='tag_id[]']:checked").is(":checked"))
                    {
                    transData = { 
                    'tag_id'            : $(value).find(".tag_id").val(),
                    'id_orderdetails'   : $(value).find(".order_det").val(),
                    }
                    selected.push(transData);	
                    }
                    })
                    req_data = selected;
                    console.log(req_data);
                    update_order_link(req_data);
                }else{
                    $('#tag_link_submit').prop('disabled',false);
                }
                    
        }else{
            alert('Please Select Any One Tag.');
        }
    });
    
    
    function update_order_link(data="")
    {
        my_Date = new Date();
        $("div.overlay").css("display", "block"); 
        $.ajax({
        url:base_url+ "index.php/admin_ret_tagging/update_order_link?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),
        data:  {'req_data':data},
        type:"POST",
        async:false,
        success:function(data){
        $('#tag_link_submit').prop('disabled',false);
        location.reload(false);
        $("div.overlay").css("display", "none"); 
        },
        error:function(error)  
        {
        console.log(error);
        $("div.overlay").css("display", "none"); 
        }	 
        });
    }
    
    
//Order Link


//New Tag Form changes

$("#reset_tag_form").on('click',function(e){
	$("#tag_lot_parent_prod").val("");
	//$("#tag_id_lot_inward_detail").val("");
	$("#tag_lot_no").val($("#tag_lot_received_id").val());
	$("#tag_id_collection").val("");
	$("#tag_lot_design").val("");
	$("#tag_size").val("");
	$("#tag_pcs").val(1);
	$("#tag_act_blc_pcs").val("");
	$("#tag_blc_pcs").val("");
	$("#tag_gwt").val("");
	$("#tag_act_gross_blc").val("");
	$("#tag_blc_gross").val("");
	$("#tag_lwt").val("");
	$("#tag_nwt").val("");
	$("#tag_wast_perc").val(0);
	$("#tag_mc_value").val("");
	$("#tag_id_mc_type").val("");
	$("#tag_mc_type").val("");
	$("#tag_sell_rate").val("");
	$("#tag_buy_rate").val("");
	$("#tag_buy_rate_type").val("");
	$("#tag_sale_value").val("");
	$("#tags_images").val("");
	$("#tag_tax_type").val("");
	$("#tag_stone_details").val("");
	$("#tag_blc_pcs_disp").html("Blc : 0");
	$("#tag_blc_gross_disp").html("Blc : 0");
	$("#buy_rate_type").html("");
	$("#tag_img").attr('data-img',"");
	$("#tag_img_copy").val("");
	$("#tag_img_default").val("");
	$('#des_select').select2("val",'');
});


$(document).on('input keyup', '#tag_pcs,#tag_gwt,#tag_lwt,#tag_wast_perc,#tag_mc_value,#tag_id_mc_type,#tag_calculation_based_on,#tag_sell_rate', function(e){
	var gross_wt = parseFloat((isNaN($('#tag_gwt').val()) || $('#tag_gwt').val() == '')  ? 0 : $('#tag_gwt').val()).toFixed(3);
	if(gross_wt == 0)
	{
		$('#tag_lwt').val(0);
	}
	var less_wt  = (isNaN($('#tag_lwt').val()) || $('#tag_lwt').val() == '')  ? 0 : $('#tag_lwt').val();
	var net_wt = parseFloat(parseFloat(gross_wt) - parseFloat(less_wt)).toFixed(3);
	$('#tag_nwt').val(net_wt);
	if($("#tag_calculation_based_on").val() == 2){
		$("#tag_sell_rate").prop("disabled",true);
		//$("#tag_id_mc_type").prop("disabled",false);
		//$("#tag_id_mc_type").val(1);
		//$("#tag_mc_value").prop("disabled",false);
		$("#buy_rate_type").html("Per Gram");
	}else{
		$("#tag_sell_rate").prop("disabled",false);
		//$("#tag_id_mc_type").prop("disabled",true);
		//$("#tag_mc_value").prop("disabled",true);
		//$("#tag_id_mc_type").val("");
		$("#buy_rate_type").html("");
	} 
	
	if(this.id == 'tag_pcs') {
		var tag_blc_pcs = parseFloat($("#tag_blc_pcs").val());
		var tag_input_pcs = $("#tag_pcs").val() == '' ? 0 : $("#tag_pcs").val(); 
		if(tag_blc_pcs >0){
			if(tag_input_pcs > tag_blc_pcs){
				$("#tag_pcs").val(tag_blc_pcs);
				$("#tag_blc_pcs_disp").html("Blc : 0");
			}else if(tag_input_pcs < 0){
				var blc_pc = tag_blc_pcs-1;
				$("#tag_pcs").val(1);
				$("#tag_blc_pcs_disp").html("Blc : "+blc_pc);
			}
			else{
				var blc_pc = tag_blc_pcs-tag_input_pcs;
				$("#tag_blc_pcs_disp").html("Blc : "+blc_pc);
			}
		}
	}
	else if(this.id == 'tag_gwt') {
		var tag_blc_wgt = parseFloat($("#tag_blc_gross").val());
		var tag_input_wgt = $("#tag_gwt").val() == '' ? 0 : parseFloat($("#tag_gwt").val());
		if(tag_blc_wgt >0){
			if(tag_input_wgt > tag_blc_wgt){
				$("#tag_gwt").val(tag_blc_wgt);
				$("#tag_blc_gross_disp").html("Blc : 0");
			}else if(tag_input_wgt < 0){
				var blc_wt = tag_blc_wgt-1;
				$("#tag_gwt").val(1);
				$("#tag_blc_gross_disp").html("Blc : "+blc_wt);
			}
			else{
				var blc_wt = tag_blc_wgt-tag_input_wgt;
				$("#tag_blc_gross_disp").html("Blc : "+blc_wt);
			}
		}
	}
	calculateTagFormSaleValue();
});	


function calculateTagFormSaleValue(row)
{ 
    // stone_price, stone_wt, certification_price
	var total_price 		= 0;
	var base_value_price 	= 0;
	var arrived_value_price = 0;
	var base_value_tax 		= 0;
	var arrived_value_tax 	= 0;
	var base_rate_tax 		= 0;
	var arrived_rate_tax 	= 0;
	var total_tax_per 		= 0;
	var total_tax_rate 		= 0;
	var rate_with_mc 		= 0;
	var material_price  	= 0; // Not worked
	var stone_price  		= 0; // Not worked
	var stone_wt  			= 0; // Not worked
	var certification_price = 0; // Not worked
	var tot_stone_wt        =0;
	
	var stone_details=$('#tag_stone_details').val();
	if(stone_details!='')
	{
        var st_details=JSON.parse(stone_details);
        if(st_details.length>0)
        {
             $.each(st_details, function (pkey, pitem) {
                 $.each(uom_details,function(key,item){
                     if(item.uom_id==pitem.stone_uom_id)
                     {
                         if(pitem.show_in_lwt==1)
                         {
                             if((item.uom_short_code=='CT') && (item.divided_by_value!=null && item.divided_by_value!='')) //For Carat Need to convert into gram
                             {
                                 stone_wt=parseFloat(parseFloat(pitem.stone_wt)/parseFloat(item.divided_by_value));
                             }else{
                                 stone_wt=pitem.stone_wt;
                             }
                         }
                         tot_stone_wt+=parseFloat(stone_wt);
                         stone_price+=parseFloat(pitem.stone_price);
                     }
                 });
                 
                
             });
        }
    }
    $('#tag_lwt').val(tot_stone_wt);
	var gross_wt 			= (isNaN($('#tag_gwt').val()) || $('#tag_gwt').val() == '')  ? 0 : $('#tag_gwt').val();
	var less_wt  			= (isNaN($('#tag_lwt').val()) || $('#tag_lwt').val() == '')  ? 0 : $('#tag_lwt').val();
	var net_wt 				= parseFloat(gross_wt)-parseFloat(less_wt);
	var calculation_type 	= (isNaN($('#calculation_based_on').val()) || $('#calculation_based_on').val() == '')  ? 0 : $('#calculation_based_on').val();
	var metal_type 			= (isNaN($('#id_metal').val()) || $('#id_metal').val() == '')  ? 1 : $('#id_metal').val();
	var sales_mode 			= (isNaN($('#tag_sales_mode').val()) || $('#tag_sales_mode').val() == '')  ? 1 : $('#tag_sales_mode').val();
	var tax_type 			= (isNaN($('#tag_tax_type').val()) || $('#tag_tax_type').val() == '')  ? 1 : $('#tag_tax_type').val();
	
	
	var tax_group 			= (isNaN($('#tax_group_id').val()) || $('#tax_group_id').val() == '')  ? 1 : $('#tax_group_id').val();
	
	
	
	/*var stone_price  		= (isNaN($('.stone_price').val()) || $('.stone_price').val() == '')  ? 0 : $('.stone_price').val();
	var stone_wt  			= (isNaN($('.stone_wt').val()) || $('.stone_wt').val() == '')  ? 0 : $('.stone_wt').val();
	var certification_price = (isNaN($('.certification_price').val()) || $('.certification_price').val() == '')  ? 0 : $('.certification_price').val();*/
  	$('#tag_nwt').val(parseFloat(net_wt).toFixed(3));
  	$('#tag_lwt').val(parseFloat(stone_wt).toFixed(3));
  	
    if(metal_type==1)
	{
		var rate_per_grm = $('#metal_rate').val();//Gold
	}else{
		var rate_per_grm = $('#silverrate_1gm').val();//Silver
	}
	
	
	var tgi_calculation_type    = $('#tgi_calculation').val().split(",");
	var tax_percentage          = $('#tax_percentage').val().split(",");
	var retail_max_mc           = (isNaN($('#tag_mc_value').val()) || $('#tag_mc_value').val() == '')  ? 0 : $('#tag_mc_value').val();
	var tot_wastage             = (isNaN($('#tag_wast_perc').val()) || $('#tag_wast_perc').val() == '')  ? 0 : $('#tag_wast_perc').val();
	var no_of_piece             = (isNaN($('#tag_pcs').val()) || $('#tag_pcs').val() == '')  ? 0 : $('#tag_pcs').val(); 
	/** 
	*	Amount calculation based on settings (without discount and tax )
	*   0 - Wastage on Gross weight And MC on Gross weight 
	*   1 - Wastage on Net weight And MC on Net weight
	*   2 - Wastage On Netwt And MC On Grwt
	*   rate_with_mc = Metal Rate + Stone + OM + Wastage + MC
	*/
	if(calculation_type == 0){ 
		var wast_wgt      = parseFloat(parseFloat(gross_wt) * parseFloat(tot_wastage/100)).toFixed(3);
		if($('#tag_id_mc_type').val() != 3){
    		var mc_type       =  parseFloat($('#tag_id_mc_type').val() == 2 ? parseFloat(retail_max_mc * gross_wt ) : parseFloat(retail_max_mc * $('#tag_pcs').val()));
    		// Metal Rate + Stone + OM + Wastage + MC
    		rate_with_mc = parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(material_price)+parseFloat(certification_price));
		}else{
		    
		    var mc_type       =  parseFloat((parseFloat(gross_wt) * parseFloat(rate_per_grm)) * parseFloat(retail_max_mc/100)).toFixed(3);
    		// Metal Rate + Stone + OM + Wastage + MC
    		rate_with_mc = parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(material_price)+parseFloat(certification_price));
		}
	}
	else if(calculation_type == 1){
		var wast_wgt      = parseFloat(parseFloat(net_wt) * parseFloat(tot_wastage/100)).toFixed(3);
		if($('#tag_id_mc_type').val() != 3){
    		var mc_type       =  parseFloat($('#tag_id_mc_type').val() == 2 ? parseFloat(retail_max_mc * net_wt ) : parseFloat(retail_max_mc * $('#tag_pcs').val()));
    		// Metal Rate + Stone + OM + Wastage + MC
    		rate_with_mc = parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(material_price)+parseFloat(certification_price));
		}else{
		    var mc_type       =   parseFloat((parseFloat(net_wt) * parseFloat(rate_per_grm)) * parseFloat(retail_max_mc/100)).toFixed(3);
    		// Metal Rate + Stone + OM + Wastage + MC
    		rate_with_mc = parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(material_price)+parseFloat(certification_price));
		}
	}
	else if(calculation_type == 2){ 
		var wast_wgt      = parseFloat(parseFloat(net_wt) * parseFloat(tot_wastage/100)).toFixed(3);
		if($('#tag_id_mc_type').val() != 3){
    		var mc_type       =  parseFloat($('#tag_id_mc_type').val() == 2 ? parseFloat(retail_max_mc * gross_wt ) : parseFloat(retail_max_mc * $('#tag_pcs').val()));
    		// Metal Rate + Stone + OM + Wastage + MC
    	    rate_with_mc = parseFloat((parseFloat(rate_per_grm) * (parseFloat(wast_wgt) + parseFloat(net_wt)))+ parseFloat(mc_type))+parseFloat(stone_price)+parseFloat(material_price)+parseFloat(certification_price);
		}else{
		    var mc_type      = parseFloat((parseFloat(gross_wt) * parseFloat(rate_per_grm)) * parseFloat(retail_max_mc/100)).toFixed(3);
    		// Metal Rate + Stone + OM + Wastage + MC
    	    rate_with_mc = parseFloat((parseFloat(rate_per_grm) * (parseFloat(wast_wgt) + parseFloat(net_wt)))+ parseFloat(mc_type))+parseFloat(stone_price)+parseFloat(material_price)+parseFloat(certification_price);
		}
	}	
	else if(calculation_type == 3){
		var sell_rate  = (isNaN($('#tag_sell_rate').val()) || $('#tag_sell_rate').val() == '')  ? 0 : $('#tag_sell_rate').val();
		var adjusted_item_rate  = 0;
	    caculated_item_rate = parseFloat(sell_rate);
	    $('.caculated_item_rate').val(caculated_item_rate);
	    rate_with_mc = parseFloat(parseFloat(adjusted_item_rate) > 0 ? parseFloat(adjusted_item_rate) : caculated_item_rate ); 
	}
	else if(calculation_type == 4){
		var sell_rate  = (isNaN($('#tag_sell_rate').val()) || $('#tag_sell_rate').val() == '')  ? 0 : $('#tag_sell_rate').val();
		var adjusted_item_rate  = 0;
	    caculated_item_rate = parseFloat((parseFloat(sell_rate)*parseFloat(net_wt))*parseFloat(no_of_piece));
	    $('.caculated_item_rate').val(caculated_item_rate); 
	    rate_with_mc = parseFloat(parseFloat(adjusted_item_rate) > 0 ? parseFloat(adjusted_item_rate) : caculated_item_rate ); 
	}
	
	console.log('Calculation : '+calculation_type);
	console.log('Wastage : '+wast_wgt);
	console.log('Total Wastage : '+tot_wastage);
	console.log('MC : '+mc_type);
	console.log('Rate with MC : '+rate_with_mc);
	console.log(' MC TYPE : '+mc_type);
	console.log(' Rate Per Gram : '+rate_per_grm);
	console.log('Gross Wt : '+gross_wt);
	// Tax Calculation

    if(tax_details.length > 0){
    		var base_value_tax	= parseFloat(calculate_base_value_tax(rate_with_mc,tax_group)).toFixed(2);
    		var base_value_amt	= parseFloat(parseFloat(rate_with_mc)+parseFloat(base_value_tax)).toFixed(2);
    		var arrived_value_tax= parseFloat(calculate_arrived_value_tax(base_value_amt,tax_group)).toFixed(2);
    		var arrived_value_amt= parseFloat(parseFloat(base_value_amt)+parseFloat(arrived_value_tax)).toFixed(2);
    		total_tax_rate	= parseFloat(parseFloat(base_value_tax)+parseFloat(arrived_value_tax)).toFixed(2);
    } 
    total_price = parseFloat(parseFloat(rate_with_mc)+parseFloat(total_tax_rate)).toFixed(2);
	
	$('#tag_sale_value').val(Math.round(parseFloat(total_price)).toFixed(2));
	console.log('Amount : '+total_price);
	console.log('Tax Rate : '+total_tax_rate);
	console.log('Arrived value : '+arrived_value_price);
	console.log('*************************');
}


function calculate_base_value_tax(taxcallrate, taxgroup){
	var totaltax = 0; 
	console.log(tax_details);
	$.each(tax_details, function(idx, taxitem){
		if(taxitem.tgi_tgrpcode == taxgroup){
			if(taxitem.tgi_calculation == 1){
				console.log(1);
				if(taxitem.tgi_type == 1){
					totaltax += parseFloat(taxcallrate)	* (parseFloat(taxitem.tax_percentage)/100);
				}else{
					totaltax -= parseFloat(taxcallrate)	* (parseFloat(taxitem.tax_percentage)/100);
				}
			}
		}
	});
	return totaltax;
}
function calculate_arrived_value_tax(taxcallrate, taxgroup){
	var totaltax = 0; 
	$.each(tax_details, function(idx, taxitem){
		if(taxitem.tgi_tgrpcode == taxgroup){
			if(taxitem.tgi_calculation == 2){
				if(taxitem.tgi_type == 1){
					totaltax += parseFloat(taxcallrate)	* (parseFloat(taxitem.tax_percentage)/100);
				}else{
					totaltax -= parseFloat(taxcallrate)	* (parseFloat(taxitem.tax_percentage)/100);
				}
			}
		}
	});
	return totaltax;
}

function validateTagDetail(){
	var valid = true;
		
	    if($('#tag_sales_mode').val()==1) // Fixed
	    {
	    	var MRP = ($("#tag_sell_rate").val()=='' ? 0 :parseFloat($("#tag_sell_rate").val()));
	    	var purchaseRate = parseFloat($("#tag_buy_rate").val());
			if(MRP==0){
				$.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Enter The MRP value'});
				 valid = false;
			}
	    }
	    else if($('#tag_sales_mode').val()==2) // Flexible
	    {
	        if($('#tag_lot_received_id').val()=='' || $('#tag_lot_received_id').val()==null){
    	        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Select Lot'});
    			valid = false;
    		}
    		else if($('#tag_lt_prod').val()=='' || $('#tag_lt_prod').val()==null){
    	        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Select Product'});
    			valid = false;
    		}
    		else if($('#des_select').val()=='' || $('#des_select').val()==null){
    	        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Select Design'});
    			valid = false;
    		}
    		else if(isInValid($('#tag_pcs').val())){
    	        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Enter The Piece'});
    			valid = false;
    		}
    		else if(isInValid($('#tag_gwt').val())){
    	        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Enter The Gross Wt'});
    			valid = false;
    		}
    		else if($("#tag_mc_value").val() == '' ||  $("#tag_mc_value").val() == 0){
				$.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Enter MC'});
				valid = false;
			}
	    }
	    
	return valid;
}

function isInValid(value){
	return (value == "" || value == undefined || value == 0 ? true : false);
}


$("#addTagToPreview").on('click',function(e){
	if(validateTagDetail()){
		set_tag_to_preview();
		$("#addTagToPreview,#addTagToPreviewAndCopy").css('display',"inline");
		$("#updateTagInPreview").css('display',"none");
	}
});

function set_tag_to_preview()
{
    var row = "";
	var total_row = $('#lt_item_tag_preview tbody > tr').length;
	row +='<tr id='+$('#tag_id_lot_inward_detail').val()+' class='+(total_row+1)+'>' 
	                +'<td>'+$('#tag_lot_received_id').val()
	                    +'<input type="hidden" class="lot_no" name="lt_item[lot_no][]" value="'+$('#tag_lot_received_id').val()+'">'
	                    +'<input type="hidden" class="id_lot_inward_detail" name="lt_item[id_lot_inward_detail][]" value="'+$('#tag_id_lot_inward_detail').val()+'">'
	                    +'<input type="hidden" class="lot_product" name="lt_item[lot_product][]" value="'+$('#tag_lt_prod').val()+'">'
	                    +'<input type="hidden" class="lot_id_design" name="lt_item[lot_id_design][]" value="'+$('#des_select').val()+'">'
	                    +'<input type="hidden" class="design_for" name="lt_item[design_for][]" value="'+$('#tag_design_for').val()+'">'
	                    +'<input type="hidden" class="purity" name="lt_item[purity][]" value="'+$('#id_purity').val()+'">'
	                    +'<input type="hidden" class="size" name="lt_item[size][]" value="'+$('#tag_size').val()+'">'
	                    +'<input type="hidden" class="no_of_piece" name="lt_item[no_of_piece][]" value="'+$('#tag_pcs').val()+'">'
	                    +'<input type="hidden" class="gross_wt" name="lt_item[gross_wt][]" value="'+$('#tag_gwt').val()+'">'
	                    +'<input type="hidden" class="less_wt" name="lt_item[less_wt][]" value="'+$('#tag_lwt').val()+'">'
	                    +'<input type="hidden" class="net_wt" name="lt_item[net_wt][]" value="'+$('#tag_nwt').val()+'">'
	                    +'<input type="hidden" class="calculation_based_on" name="lt_item[calculation_based_on][]" value="'+$('#calculation_based_on').val()+'">'
	                    +'<input type="hidden" class="wastage_percentage" name="lt_item[wastage_percentage][]" value="'+$('#tag_wast_perc').val()+'">'
	                    +'<input type="hidden" class="id_mc_type" name="lt_item[id_mc_type][]" value="'+$('#tag_id_mc_type').val()+'">'
	                    +'<input type="hidden" class="making_charge" name="lt_item[making_charge][]" value="'+$('#tag_mc_value').val()+'">'
	                    +'<input type="hidden" class="sell_rate" name="lt_item[sell_rate][]" value="'+$('#tag_sell_rate').val()+'">'
	                    +'<input type="hidden" class="sale_value" name="lt_item[sale_value][]" value="'+$('#tag_sale_value').val()+'">'
	                    +'<input type="hidden" class="tag_product_short_code" name="lt_item[product_short_code][]" value="'+$('#tag_product_short_code').val()+'">'
	                    +'<input type="hidden" class="id_metal" name="lt_item[id_metal][]" value="'+$('#id_metal').val()+'">'
	                    +'<input type="hidden" class="tax_group_id" name="lt_item[tax_group_id][]" value="'+$('#tax_group_id').val()+'">'
	                    +'<input type="hidden" class="tag_sales_mode" name="lt_item[tag_sales_mode][]" value="'+$('#tag_sales_mode').val()+'">'
	                    +'<input type="hidden" class="tag_tax_type" name="lt_item[tag_tax_type][]" value="'+$('#tag_tax_type').val()+'">'
	                +'</td>'
	                +'<td>'+$("#tag_lt_prod option:selected").text()+'</td>'
	                +'<td>'+$("#des_select option:selected").text()+'</td>'
	                +'<td>'+$("#tag_design_for option:selected").text()+'</td>'
	                +'<td>'+$("#tag_calculation_based_on option:selected").text()+'</td>'
	                +'<td>'+$("#tag_size option:selected").text()+'</td>'
	                +'<td>'+$("#tag_pcs").val()+'</td>'
	                +'<td>'+$("#tag_gwt").val()+'</td>'
	                +'<td><span class="tag_preview_lwt">'+$("#tag_lwt").val()+'</span></td>'
	                +'<td><span class="tag_preview_nwt">'+$("#tag_nwt").val()+'</span></td>'
	                +'<td>'+$("#tag_wast_perc").val()+'</td>'
	                +'<td>'+$("#tag_id_mc_type option:selected").text()+'</td>'
	                +'<td>'+$("#tag_mc_value").val()+'</td>'
	                +'<td><a href="#" onClick="create_new_empty_est_cus_stone_item($(this).closest(\'tr\'));" class="btn btn-default btn-sm"><i class="fa fa-plus"></i></a><input type="hidden" class="stone_details" name="lt_item[stone_details][]" value='+$('#tag_stone_details').val()+'><input type="hidden" class="stone_price" name="lt_item[stone_price][]"><input type="hidden" class="normal_st_certif" value=""><input type="hidden" class="semiprecious_st_certif" value=""><input type="hidden" class="precious_st_certif" value=""></td>'
	                +'<td><span class="tag_preview_sell_rate">'+$("#tag_sell_rate").val()+'</span></td>'
	                +'<td><span class="tag_preview_sale_value">'+$("#tag_sale_value").val()+'</span></td>'
	                +'<td><div style="display: flex;"><span id="items_add_'+total_row+'"><a href="#" onClick="edit_tag($(this).closest(\'tr\'));" class="btn-del label label-primary" style="padding:5px;" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a><a href="#" onClick="remove_row($(this).closest(\'tr\'));" class="btn-del label label-danger" style="padding:5px;" data-toggle="tooltip" title="Delete"><i class="fa fa-trash"></i></a></span></div></td>'
	       +'</tr>';
	       if($('#lt_item_tag_preview > tbody  > tr').length>0)
        	{
        	    $('#lt_item_tag_preview > tbody > tr:first').before(row);
        	}else{
        	    $('#lt_item_tag_preview tbody').append(row);
        	}
        	set_tag_preview_class();
        	//calculateTagPreviewSaleValue();
        	$('#reset_tag_form').trigger('click');
}

function set_tag_preview_class()
{
     var total_row = $('#lt_item_tag_preview tbody > tr').length;
     $('#lt_item_tag_preview > tbody tr').each(function(idx, row){
          $(this).removeClass();
          $(this).closest('tr').addClass(idx.toString());
     });
}


function edit_tag(curRow){
    var item=curRow;
   
    $('#tag_lot_id').val(item.find('.lot_no').val());
    $('#tag_lt_prodId').val(item.find('.lot_product').val());
    $('#id_design').val(item.find('.lot_id_design').val());
    $('#tag_id_lot_inward_detail').val(item.find('.id_lot_inward_detail').val());
    $('#calculation_based_on').val(item.find('.calculation_based_on').val());
    $('#id_metal').val(item.find('.id_metal').val());
    $('#tax_group_id').val(item.find('.tax_group_id').val());
    $('#tag_lot_no').val(item.find('.lot_no').val());
    $('#tag_sales_mode').val(item.find('.tag_sales_mode').val());
    $('#id_purity').val(item.find('.purity').val());
    $('#tag_product_short_code').val(item.find('.tag_product_short_code').val());
    $('#des_select').select2("val",item.find('.lot_id_design').val());
    $('#tag_pcs').val(item.find('.no_of_piece').val());
    $('#tag_gwt').val(item.find('.gross_wt').val());
    $('#tag_lwt').val(item.find('.less_wt').val());
    $('#tag_nwt').val(item.find('.net_wt').val());
    $('#tag_calculation_based_on').val(item.find('.calculation_based_on').val());
    $('#tag_sell_rate').val(item.find('.sell_rate').val());
    $('#tag_mc_value').val(item.find('.making_charge').val());
    $('#tag_id_mc_type').val(item.find('.id_mc_type').val());
    $('#tag_wast_perc').val(item.find('.wastage_percentage').val());
    $('#tag_tax_type').val(item.find('.tag_tax_type').val());
    $('#tag_stone_details').val(item.find('.stone_details').val());
	setTimeout(function(){
        calculateTagFormSaleValue();
   	},1500);
   
     get_received_lots();
        get_lot_products();
    curRow.remove();
    set_tag_preview_class();
}

//New Tag Form changes



//calculate tag preview sale

function calculateTagPreviewSaleValue()
{ 
    $('#lt_item_tag_preview > tbody tr').each(function(idx, row){
         curRow = $(this);
            // stone_price, stone_wt, certification_price
        	var total_price 		= 0;
        	var base_value_price 	= 0;
        	var arrived_value_price = 0;
        	var base_value_tax 		= 0;
        	var arrived_value_tax 	= 0;
        	var base_rate_tax 		= 0;
        	var arrived_rate_tax 	= 0;
        	var total_tax_per 		= 0;
        	var total_tax_rate 		= 0;
        	var rate_with_mc 		= 0;
        	var material_price  	= 0; // Not worked
        	var stone_price  		= 0; // Not worked
        	var tot_stone_wt  			= 0; // Not worked
        	var certification_price = 0; // Not worked
            var stone_wt=0;
            
            
            var stone_details=curRow.find('.stone_details').val();
            if(stone_details!='')
            {
                var st_details=JSON.parse(stone_details);
                if(st_details.length>0)
                {
                     $.each(st_details, function (pkey, pitem) {
                         $.each(uom_details,function(key,item){
                             if(item.uom_id==pitem.stone_uom_id)
                             {
                                 if(pitem.show_in_lwt==1)
                                 {
                                     if((item.uom_short_code=='CT') && (item.divided_by_value!=null && item.divided_by_value!='')) //For Carat Need to convert into gram
                                     {
                                         stone_wt=parseFloat(parseFloat(pitem.stone_wt)/parseFloat(item.divided_by_value));
                                     }else{
                                         stone_wt=pitem.stone_wt;
                                     }
                                     tot_stone_wt+=parseFloat(stone_wt);
                                 }
                                 
                                 stone_price+=parseFloat(pitem.stone_price);
                             }
                         });
                         
                        
                     });
                }
            }
            curRow.find('.less_wt').val(parseFloat(tot_stone_wt).toFixed(3));
            
        	var gross_wt 			= (isNaN(curRow.find('.gross_wt').val()) || curRow.find('.gross_wt').val() == '')  ? 0 : curRow.find('.gross_wt').val();
        	var less_wt  			= (isNaN(curRow.find('.less_wt').val()) || curRow.find('.less_wt').val() == '')  ? 0 : curRow.find('.less_wt').val();
        	var net_wt 				= parseFloat(gross_wt)-parseFloat(less_wt);
        	var calculation_type 	= (isNaN(curRow.find('.calculation_based_on').val()) || curRow.find('.calculation_based_on').val() == '')  ? 0 : curRow.find('.calculation_based_on').val();
        	var metal_type 			= (isNaN(curRow.find('.id_metal').val()) || curRow.find('.id_metal').val() == '')  ? 1 : curRow.find('.id_metal').val();
        	var sales_mode 			= (isNaN(curRow.find('.tag_sales_mode').val()) || curRow.find('.tag_sales_mode').val() == '')  ? 1 : curRow.find('.tag_sales_mode').val();
        	var tax_type 			= (isNaN(curRow.find('.tag_tax_type').val()) || curRow.find('.tag_tax_type').val() == '')  ? 1 : curRow.find('.tag_tax_type').val();
        	
        	
        	var tax_group 			= (isNaN(curRow.find('.tax_group_id').val()) || curRow.find('.tax_group_id').val()=='')  ? 1 : curRow.find('.tax_group_id').val();
        	
        	
        	
        	/*var stone_price  		= (isNaN($('.stone_price').val()) || $('.stone_price').val() == '')  ? 0 : $('.stone_price').val();
        	var stone_wt  			= (isNaN($('.stone_wt').val()) || $('.stone_wt').val() == '')  ? 0 : $('.stone_wt').val();
        	var certification_price = (isNaN($('.certification_price').val()) || $('.certification_price').val() == '')  ? 0 : $('.certification_price').val();*/
          	curRow.find('.net_wt').val(parseFloat(net_wt).toFixed(3))
          	
            if(metal_type==1)
        	{
        		var rate_per_grm = $('#metal_rate').val();//Gold
        	}else{
        		var rate_per_grm = $('#silverrate_1gm').val();//Silver
        	}
        	
        	
            var retail_max_mc           = (isNaN(curRow.find('.making_charge').val()) || curRow.find('.making_charge').val() == '')  ? 0 : curRow.find('.making_charge').val();
        	var tot_wastage             = (isNaN(curRow.find('.wastage_percentage').val()) || curRow.find('.wastage_percentage').val() == '')  ? 0 : curRow.find('.wastage_percentage').val();
        	var no_of_piece             = (isNaN(curRow.find('.no_of_piece').val()) || curRow.find('.no_of_piece').val() == '')  ? 0 : curRow.find('.no_of_piece').val(); 
        	/** 
        	*	Amount calculation based on settings (without discount and tax )
        	*   0 - Wastage on Gross weight And MC on Gross weight 
        	*   1 - Wastage on Net weight And MC on Net weight
        	*   2 - Wastage On Netwt And MC On Grwt
        	*   rate_with_mc = Metal Rate + Stone + OM + Wastage + MC
        	*/
        	if(calculation_type == 0){ 
        		var wast_wgt      = parseFloat(parseFloat(gross_wt) * parseFloat(tot_wastage/100)).toFixed(3);
        		if(curRow.find('.id_mc_type').val() != 3){
            		var mc_type       =  parseFloat(curRow.find('.id_mc_type').val() == 2 ? parseFloat(retail_max_mc * gross_wt ) : parseFloat(retail_max_mc * no_of_piece));
            		// Metal Rate + Stone + OM + Wastage + MC
            		rate_with_mc = parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(material_price)+parseFloat(certification_price));
        		}else{
        		    
        		    var mc_type       =  parseFloat((parseFloat(gross_wt) * parseFloat(rate_per_grm)) * parseFloat(retail_max_mc/100)).toFixed(3);
            		// Metal Rate + Stone + OM + Wastage + MC
            		rate_with_mc = parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(material_price)+parseFloat(certification_price));
        		}
        	}
        	else if(calculation_type == 1){
        		var wast_wgt      = parseFloat(parseFloat(net_wt) * parseFloat(tot_wastage/100)).toFixed(3);
        		if(curRow.find('.id_mc_type').val() != 3){
            		var mc_type       =  parseFloat(curRow.find('.id_mc_type').val() == 2 ? parseFloat(retail_max_mc * net_wt ) : parseFloat(retail_max_mc * no_of_piece));
            		// Metal Rate + Stone + OM + Wastage + MC
            		rate_with_mc = parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(material_price)+parseFloat(certification_price));
        		}else{
        		    var mc_type       =   parseFloat((parseFloat(net_wt) * parseFloat(rate_per_grm)) * parseFloat(retail_max_mc/100)).toFixed(3);
            		// Metal Rate + Stone + OM + Wastage + MC
            		rate_with_mc = parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(material_price)+parseFloat(certification_price));
        		}
        	}
        	else if(calculation_type == 2){ 
        		var wast_wgt      = parseFloat(parseFloat(net_wt) * parseFloat(tot_wastage/100)).toFixed(3);
        		if(curRow.find('.id_mc_type').val() != 3){
            		var mc_type       =  parseFloat(curRow.find('.id_mc_type').val() == 2 ? parseFloat(retail_max_mc * gross_wt ) : parseFloat(retail_max_mc * no_of_piece));
            		// Metal Rate + Stone + OM + Wastage + MC
            	    rate_with_mc = parseFloat((parseFloat(rate_per_grm) * (parseFloat(wast_wgt) + parseFloat(net_wt)))+ parseFloat(mc_type))+parseFloat(stone_price)+parseFloat(material_price)+parseFloat(certification_price);
        		}else{
        		    var mc_type      = parseFloat((parseFloat(gross_wt) * parseFloat(rate_per_grm)) * parseFloat(retail_max_mc/100)).toFixed(3);
            		// Metal Rate + Stone + OM + Wastage + MC
            	    rate_with_mc = parseFloat((parseFloat(rate_per_grm) * (parseFloat(wast_wgt) + parseFloat(net_wt)))+ parseFloat(mc_type))+parseFloat(stone_price)+parseFloat(material_price)+parseFloat(certification_price);
        		}
        	}	
        	else if(calculation_type == 3){
        		var sell_rate  = (isNaN(curRow.find('.sell_rate').val()) || curRow.find('.sell_rate').val() == '')  ? 0 : curRow.find('.sell_rate').val();
        		var adjusted_item_rate  = 0;
        	    caculated_item_rate = parseFloat(sell_rate);
        	    $('.caculated_item_rate').val(caculated_item_rate);
        	    rate_with_mc = parseFloat(parseFloat(adjusted_item_rate) > 0 ? parseFloat(adjusted_item_rate) : caculated_item_rate ); 
        	}
        	else if(calculation_type == 4){
        		var sell_rate  = (isNaN(curRow.find('.sell_rate').val()) || curRow.find('.sell_rate').val() == '')  ? 0 : curRow.find('.sell_rate').val();
        		var adjusted_item_rate  = 0;
        	    caculated_item_rate = parseFloat((parseFloat(sell_rate)*parseFloat(net_wt))*parseFloat(no_of_piece));
        	    $('.caculated_item_rate').val(caculated_item_rate); 
        	    rate_with_mc = parseFloat(parseFloat(adjusted_item_rate) > 0 ? parseFloat(adjusted_item_rate) : caculated_item_rate ); 
        	}
        	
        	console.log('Calculation : '+calculation_type);
        	console.log('Wastage : '+wast_wgt);
        	console.log('Total Wastage : '+tot_wastage);
        	console.log('MC : '+mc_type);
        	console.log('Rate with MC : '+rate_with_mc);
        	console.log(' MC TYPE : '+mc_type);
        	console.log(' Rate Per Gram : '+rate_per_grm);
        	console.log('Gross Wt : '+gross_wt);
        	// Tax Calculation
        
            if(tax_details.length > 0){
            		var base_value_tax	= parseFloat(calculate_base_value_tax(rate_with_mc,tax_group)).toFixed(2);
            		var base_value_amt	= parseFloat(parseFloat(rate_with_mc)+parseFloat(base_value_tax)).toFixed(2);
            		var arrived_value_tax= parseFloat(calculate_arrived_value_tax(base_value_amt,tax_group)).toFixed(2);
            		var arrived_value_amt= parseFloat(parseFloat(base_value_amt)+parseFloat(arrived_value_tax)).toFixed(2);
            		total_tax_rate	= parseFloat(parseFloat(base_value_tax)+parseFloat(arrived_value_tax)).toFixed(2);
            } 
            total_price = parseFloat(parseFloat(rate_with_mc)+parseFloat(total_tax_rate)).toFixed(2);
        	
        	curRow.find('.sale_value').val(Math.round(parseFloat(total_price)).toFixed(2));
        	curRow.find('.tag_preview_nwt').html((parseFloat(net_wt)).toFixed(3));
        	curRow.find('.tag_preview_lwt').html((parseFloat(less_wt)).toFixed(3));
        	curRow.find('.tag_preview_sale_value').html((parseFloat(total_price)).toFixed(2));
        	console.log('Amount : '+total_price);
        	console.log('Tax Rate : '+total_tax_rate);
        	console.log('Arrived value : '+arrived_value_price);
        	console.log('*************************');
    });
}


// Remove Rows

function remove_row(curRow, askConfirm = 1)
{
	if(ctrl_page[2]!='edit')
	{
		if(askConfirm == 1){
			var answer = confirm("Are you sure want to remove this Item Records?")
			if(answer){
		     	 curRow.remove();
				 // Check Lot Items Validations		
					var last_item_id  = $('#lt_item_list > tbody tr:last').attr('class');
					if($('#lt_item_list > tbody tr').length >= 1) {
					    $("#items_add_"+last_item_id).css('display','block');
						setTimeout(function(){ 
						  $('#lt_item_list > tbody tr:last td:last').find('a:first').focus(); 
						}, 600); 
				    }
			}
		}else{
			curRow.remove();
		 	// Check Lot Items Validations		
			var last_item_id  = $('#lt_item_list > tbody tr:last').attr('class');
			if($('#lt_item_list > tbody tr').length >= 1) {
			    $("#items_add_"+last_item_id).css('display','block');
				setTimeout(function(){ 
				  $('#lt_item_list > tbody tr:last td:last').find('a:first').focus(); 
				}, 600); 
		    }
		    auto_added_tagging_datas();
		}
		
	}
}

//calculate tag preview sale

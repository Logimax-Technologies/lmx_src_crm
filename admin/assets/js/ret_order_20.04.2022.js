var path =  url_params();
var ctrl_page = path.route.split('/');
var img_resource=[];
var total_files=[];
var tax_details=[];
var pre_img_files=[];
var pre_img_resource=[];
var cat_product_details = [];
$(document).ready(function() {
 
	 var path =  url_params();
	 $('#status').bootstrapSwitch();
	 $('.date').datepicker({ dateFormat: 'yyyy-mm-dd', })
	 $('body').addClass("sidebar-collapse");	
     
     switch(ctrl_page[1])
	 {
	 	case 'order':
				 switch(ctrl_page[2]){				 	
				 	case 'list':				 	
				 			setOrderList();
				 		break;
				 	case 'edit':    
				 			setEstiData(ctrl_page[3]);
						break;
				 	case 'add': 
				 			get_metal_rates_by_branch();
							$(document).on('keyup', ".product", function(e) {  
								var prod = this.value;  
								if(prod.length >= 3) {
									console.log(prod.length);  
									var row = $(this).closest('tr');
									getSearchProd(prod,this.id,row);
				                }
							});
							
							$(document).on('keyup', ".design", function(e) {  
								var val = this.value;
								if(val.length >= 3) { 
									getSearchDesign(val,this.id);
				                }
							}); 
							$(document).on('keyup', "#esti_no", function(e) {  
								var val = this.value; 
									searchEsti(val); 
							});
							
							$(document).on('keyup', ".sub_design", function(e) {  
							    curRow = $(this);
								var val = this.value;
								if(val.length >= 1) { 
									getSearchSubDesign(val,this.id,curRow);
				                }
							});
							
							if($('#description').length > 0)
							{
							 	CKEDITOR.replace('description');
							} 
							
				 		break;
				 	case 'repair_add':
					    get_all_karigar();
					    get_all_employee();
					    get_all_master_data();
					    break;
				 }
			case 'neworders':

					//get_new_orderlist();
					get_all_karigar();
					get_all_branches();
					var date = new Date();
					var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 6, 1); 
					var from_date =  firstDay.getFullYear()+'-'+(firstDay.getMonth()+1)+'-'+firstDay.getDate();
					var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
					$('#new_list1').text(from_date);
					$('#new_list2').text(to_date);
					get_new_orderlist(from_date,to_date);
					$('#account_list1').text(moment().startOf('month').format('YYYY-MM-DD'));
					$('#account_list2').text(moment().endOf('month').format('YYYY-MM-DD'));	
					$('#account-dt-btn').daterangepicker(
						{
							ranges: {
							'Today': [moment(), moment()],
							'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
							'Last 7 Days': [moment().subtract(6, 'days'), moment()],
							'Last 30 Days': [moment().subtract(29, 'days'), moment()],
							'This Month': [moment().startOf('month'), moment().endOf('month')],
							'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
							},
							startDate: moment().subtract(29, 'days'),
							endDate: moment()
						},
						function (start, end) 
						{
							$('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
							get_new_orderlist(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'))			 
							$('#new_list1').text(start.format('YYYY-MM-DD'));
							$('#new_list2').text(end.format('YYYY-MM-DD')); 
						}
						);
	 		break;
	 		
	 		case 'stock_issue':
	 		    switch(ctrl_page[2]){				 	
				 	case 'list':	
				 	    set_repair_order_list();
				 	break;
				 	case 'add':
				 	    get_all_karigar();
					    get_all_employee();
					    get_all_master_data();
					    $('#issue_type').select2();
					    $('#repair_type').select2();
				        if($('#description').length > 0)
						{
						 	CKEDITOR.replace('description');
						} 
						get_StockIssueItems();	
							
				 	break;
	 		    }
	 		break;
	 		
	 		case 'repair_order':
	 		    switch(ctrl_page[2]){				 	
				 	case 'list':	
				 	    set_repair_order_list();
				 	break;
				 	case 'repair_order_status':				 	
				 			setRepairOrderStatus();
				 	break;
	 		    }
	 		break;
	 		
	 		case 'cart':
	 		    get_ActiveKaigar();
	 		    get_order_cart();
	 		    get_ActiveProduct();
	 		    get_Activedesign('');
	 		    get_weight_range('');
	 		       $(document).on('keyup', ".karigar_search", function(e) {  
					var karigar = this.value;  
					if(karigar.length >= 3) {
						var row = $(this).closest('tr');
						getSearchKarigar(karigar,row);
	                }
				});
				if(ctrl_page[2]=='cart_status')
				{
                    set_cart_status();
				}
	 		break;
	}
	 
	$('.order_from').select2().on('change', function() { 
		if(this.value!='')
		{
			$('#id_branch').val(this.value);
			get_metal_rates_by_branch(this.value);
			calculate_orderSale_value();
			
		}
		else
		{
			$('#id_branch').val('');
		}
	});
	
	$('#order_to_br').on('change',function(e){ 
		$('#id_order_to_br').val(this.value);
	})  
	
	$(document).on('change',".purity", function(){ 
		$('#id_'+this.id).val(this.value);
	})
/*	$(document).on('change',".order_type", function(){  
		if(this.value == 2 || this.value == 3 ){
			var i = this.id;
			if(! $('#1_img'+i).length){
				var newRow = $("<tr>"); 
				html2 = "<td colspan='3'><textarea name='o_item["+i+"][smith_remainder_date]' rows='3' cols='50'></textarea></td><td colspan='3'> <input type='file' name='o_item["+i+"][img_1]' /><img src='' alt='Upload Sample 1' class='img-thumbnail uploadImg' id='1_img"+i+"' alt='Product Sample' width='150' height='75'></td><td colspan='3'><input type='file' name='o_item["+i+"][img_2]' /><img src='' alt='Upload Sample 2' class='img-thumbnail uploadImg' id='2_img"+i+"' alt='Product Sample' width='150' height='75'> </td>"; 
		      	newRow.append(html2);
		      	newRow.insertAfter($(this).parents().closest('tr'));
	      	}
		}
      	 
	})*/

	$(document).on('change',".order_type", function(){  

		if(this.value == 2 || this.value == 3 ){
			var i = this.id;
			console.log($('#1_img'+i).length);
			if(! $('#1_img'+i).length){

				var newRow = $("<tr id='"+i+"' class='imgrow"+i+"'>"); 
				html2 = "<td colspan='3'><textarea name='o_item["+i+"][smith_remainder_date]' rows='3' cols='50'></textarea></td><td colspan='12'><input type='hidden' id='image_name_"+i+"' name='o_item["+i+"][image]' value=''> <input type='file' id='img"+i+"' class='img1' name='o_item["+i+"][img_1][]' multiple /><div alt='Upload Sample 1' class='col-md-12' id='1_img"+i+"'><input type='hidden' name='o_item["+i+"][remove_files]' id='files"+i+"'></div><button id='img_upload' type='button' class='btn btn-success pull-right'><i class='fa fa-plus'></i>Upload</button></td>"; 
		      	newRow.append(html2);
		      	newRow.insertAfter($(this).parents().closest('tr'));
	      	}
		}
		else
		{
			id =  $(this).closest("tr").next('tr').attr('id');
			var	rowid= "imgrow"+id;
			$('.'+rowid+'').remove();
		}

      	 
	})
	
	$('input[type=radio][name="order[order_for]"]').change(function() {
		var order_for = $("input[name='order[order_for]']:checked").val();
		if(order_for == 2){ // Customer
			$('.brn').css('display','none');
			$('.cus').css('display','block');
		}
		else if(order_for == 1){ // Branch
			$('.brn').css('display','block');
			$('.cus').css('display','none');
		}
	});
	
	/* Customer search. - Start */	
	$("#cus_name").on("keyup",function(e){ 
		var customer = $("#cus_name").val();
		if(customer.length >= 2) { 
			getSearchCustomers(customer);
		}
	}); 
	/* Ends - Customer search. */
	 
//Image validation

$(".uploadImg").on('change',function() {
	validateImage(this);	
});

$("#create_order").on('click',function(e) {
	e.preventDefault();
	if(validateOrderDetailRow())
	{
    	var order_for = $("input[name='order[order_for]']:checked").val();
    	if(($('#cus_id').val() == null ||$('#cus_id').val() == '') && order_for == 2){
    		$("#customerAlert").html('<p style="color:red">Enter a valid customer name / mobile</p>');
    		return false;
    	}
    	else if(($('#order_to_br').val() == null || $('#order_to_br').val() == '' )&& order_for == 1){
    		alert("Select Order For Branch");
    		return false;
    	}
    	else if($('.order_from').val() == null || $('.order_from').val() == ''){
    		alert("Select Order Branch");
    		return false;
    	}
    	else{
                create_customer_order();
    	}
    }else{
        alert('Please Fill The Required Fields..');
    }
});

function create_customer_order()
{
    $("div.overlay").css("display", "block"); 
		$('#create_stock_order').prop('disabled',true);
		var form_data=$('#order_submit').serialize();
			var url=base_url+ "index.php/admin_ret_order/order/save?nocache=" + my_Date.getUTCSeconds();
		    $.ajax({ 
		        url:url,
		        data: form_data,
		        type:"POST",
		        dataType:"JSON",
		        success:function(data){
		            if(data.id_customerorder!='' && data.order_for==1)
		            {
		                window.open(base_url+'index.php/admin_ret_order/vendor_acknowladgement/'+data.id_customerorder,'_blank');
		            }
		             location.href=base_url+'index.php/admin_ret_order/order/list';
					$("div.overlay").css("display", "none"); 
		        },
		        error:function(error)  
		        {	
		            $("div.overlay").css("display", "none"); 
		        } 
		    });
		$('#create_stock_order').prop('disabled',false);
}

 
$(document).on('change',".img1", function(){ 
	id =  $(this).closest("tr").attr('id');
	$('#cur_id').val(id);
	item_validateImage();
});

$(document).on('change',".category", function(){ 
    
    var row = $(this).closest('tr'); 
    row.find('.product option').remove();
    row.find('.design option').remove();
    row.find('.sub_design option').remove();
    row.find('.size option').remove();
    if(this.value != ''){
        get_cat_purity(row,this.value);
        get_ActiveCusorderProduct(row,this.value);
        var CatData = filterByCatId('id_ret_category',this.value);
        if(CatData){
            row.find(".metal_type").val(CatData.id_metal);
            row.find(".tax_group").val(CatData.tgrp_id);
            getTaxGroupDetail(row,CatData.tgrp_id);
        }
    } 
    
});


function get_ActiveCusorderProduct(curRow,id_category)
{ 
    
    $(".overlay").css('display','block');
    $.ajax({
        type: 'POST',
        data: {'id_category' : id_category},
        url: base_url+'index.php/admin_ret_order/get_ActiveProducts',
        dataType:'json',
        success:function(data){

            get_products=data;
            curRow.find(".product option").remove();
            $('.product').append(
                $("<option></option>")
                .attr("value", "")    
                .text('-Choose-')  
            );
           $.each(data, function (key, item) {   
                curRow.find(".product").append(
                    $("<option></option>")
                    .attr("value", item.pro_id)    
                    .text(item.product_name)  
                );
            });         
            $(".product").select2({
                placeholder: "product",
                allowClear: true
            });
            curRow.find(".pro_id").val(curRow.find(".product").val());
            $(".overlay").css("display", "none");   
            
        }
    });
}

$(document).on('change',".product",function(){
    var row = $(this).closest('tr');
    row.find('.design option').remove();
    
    if(this.value!='')
    {
        get_product_size(row,this.value);
        get_ActiveDesigns(row,this.value);
    }
});


function get_ActiveDesigns(curRow)
{
    

    $(".overlay").css('display','block');
    $.ajax({
        type: 'POST',
        url: base_url+'index.php/admin_ret_catalog/get_active_design_products',
        dataType:'json',
        data:{'id_product':curRow.find('.product').val()},
        success:function(data){

            curRow.find(".design option").remove();
            $('.design').append(
                $("<option></option>")
                .attr("value", "")    
                .text('-Choose-')  
            );
           $.each(data, function (key, item) {   
                curRow.find(".design").append(
                    $("<option></option>")
                    .attr("value", item.design_no)    
                    .text(item.design_name)  
                );
            });         
            $(".design").select2({
                placeholder: "Design",
                allowClear: true
            });
            curRow.find(".design_no").val(curRow.find(".design").val());
            $(".overlay").css("display", "none");   
            
           
        }
    });
}


$(document).on('change',".design",function(){

    var row = $(this).closest('tr');
    row.find('.sub_design option').remove();
    if(this.value!='')
    {
        get_ActiveSubDesigns(row,this.value);
    }
});


function get_ActiveSubDesigns(curRow,id_design)
{
    
    $(".overlay").css('display','block');
    $.ajax({
        type: 'POST',
        url: base_url+'index.php/admin_ret_catalog/get_ActiveSubDesigns',
        dataType:'json',
        data:{'id_product':curRow.find('.product').val(),'design_no':curRow.find('.design').val()},
        success:function(data){
            curRow.find(".sub_design option").remove();
            $('.sub_design').append(
                $("<option></option>")
                .attr("value", "")    
                .text('-Choose-')  
            );
           $.each(data, function (key, item) {   
                curRow.find(".sub_design").append(
                    $("<option></option>")
                    .attr("value", item.id_sub_design)    
                    .text(item.sub_design_name)  
                );
            });         
            $(".sub_design").select2({
                placeholder: "Sub Design",
                allowClear: true
            });
            curRow.find(".id_sub_design").val(curRow.find(".sub_design").val());
            $(".overlay").css("display", "none");   
           
        }
    });
}

	
function filterByCatId( prop, value){
    var filtered = [];
    for(var i = 0; i < CategorysArr.length; i++){ 
        var obj = CategorysArr[i]; 
        for(var key in obj){ 
        	if(key == prop && obj[key] == value){
				return obj;
			} 
        }
    }   
}
$(document).on('keyup change',".order_from,.category,.weight,.wast_percent,.mc,.stn_amt,.mc_type", function(){ 
	calculate_orderSale_value();
});

function calculate_orderSale_value()
{
     $('#item_detail > tbody tr').each(function(idx, row){
        var row = $(this);
        if(row.find('.metal_type').val() == 1){
        rate_per_grm = (isNaN($('.per-grm-sale-value').html()) || $('.per-grm-sale-value').html() == '')  ? 0 : parseFloat($('.per-grm-sale-value').html());
        }else{
        rate_per_grm = (isNaN($('.silver_per-grm-sale-value').html()) || $('.silver_per-grm-sale-value').html() == '')  ? 0 : parseFloat($('.silver_per-grm-sale-value').html());
        }
        var weight = (isNaN(row.find('.weight').val()) || row.find('.weight').val() == '')? 0 :row.find('.weight').val();
        var stn_amt = (isNaN(row.find('.stn_amt').val()) || row.find('.stn_amt').val() == '')? 0 : row.find('.stn_amt').val();
        var retail_max_mc = (isNaN(row.find('.mc').val()) || row.find('.mc').val() == '')? 0 : row.find('.mc').val();
        var wast_percent = (isNaN(row.find('.wast_percent').val()) || row.find('.wast_percent').val() == '')? 0 : row.find('.wast_percent').val();
        var wast_wgt = (isNaN(row.find('.wast_wgt').val()) || row.find('.wast_wgt').val() == '')? 0 : row.find('.wast_wgt').val();
        var tax_group = (isNaN(row.find('.tax_group').val()) || row.find('.tax_group').val() == '')? 0 : row.find('.tax_group').val();
        var cls = $(this).attr("class");
        var no_of_pcs =row.find('.qty').val();
        
        /*if(cls == 'form-control wast_percent'){
        var wast_wgt = parseFloat(parseFloat(weight) * parseFloat(wast_percent/100)).toFixed(3);
        row.find('.wast_wgt').val(wast_wgt);
        }
        else if(cls == 'form-control wast_wgt'){
        var wast_percent = parseFloat(( parseFloat(wast_wgt)*100)/parseFloat(weight)).toFixed(3);
        row.find('.wast_percent').val(wast_percent);
        } */  
        
        
        var mc       =  parseFloat(row.find('.mc_type').val() == 1 ? parseFloat(retail_max_mc * weight ) : parseFloat(retail_max_mc * no_of_pcs));
        var wast_wgt = parseFloat(parseFloat(weight) * parseFloat(wast_percent/100)).toFixed(3);
        row.find('.wast_wgt').val(wast_wgt);
        
        //var wast_percent = parseFloat(( parseFloat(wast_wgt)*100)/parseFloat(weight)).toFixed(3);
        //row.find('.wast_percent').val(wast_percent);
        
        console.log("weight "+weight);
        console.log("stn_amt "+stn_amt);
        console.log("mc "+mc);
        console.log("wast_wgt "+wast_wgt);
        console.log("tax_group "+tax_group);
        taxable = parseFloat(parseFloat(parseFloat(rate_per_grm) * (parseFloat(wast_wgt) + parseFloat(weight))) +parseFloat(mc)+parseFloat(stn_amt)).toFixed(2);  
        row.find('.taxable').val(taxable);
        var total_tax_rate = 0;
        if(tax_details.length > 0){
        // Tax Calculation
        var base_value_tax	= parseFloat(calculate_base_value_tax(taxable,tax_group)).toFixed(2);
        var base_value_amt	= parseFloat(parseFloat(taxable)+parseFloat(base_value_tax)).toFixed(2);
        var arrived_value_tax= parseFloat(calculate_arrived_value_tax(base_value_amt,tax_group)).toFixed(2);
        var arrived_value_amt= parseFloat(parseFloat(base_value_amt)+parseFloat(arrived_value_tax)).toFixed(2);
        total_tax_rate	= parseFloat(parseFloat(base_value_tax)+parseFloat(arrived_value_tax)).toFixed(2);
        } 
        row.find('.tax').val(total_tax_rate); 
        console.log("rate_per_grm "+rate_per_grm);
        console.log("Taxable "+taxable);
        console.log("base_value_tax "+base_value_tax);
        console.log("Tax Rate"+total_tax_rate);
        console.log("-------- ");
        row.find('.order_amt').val(parseFloat(parseFloat(taxable)+parseFloat(total_tax_rate)).toFixed(2));
        });
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

 	Date.prototype.toInputFormat = function() {
       var yyyy = this.getFullYear().toString();
       var mm = (this.getMonth()+1).toString();
       var dd  = this.getDate().toString();
       return (dd[1]?dd:"0"+dd[0]) + "-" + (mm[1]?mm:"0"+mm[0]) + "-" + yyyy; // padding
    };
    
    $('#issue_type').on('change',function()
    {
        $('#stockrepair_item_detail > tbody').empty();
        $('#tagissue_item_detail > tbody').empty();
        var ordertype = this.value;
        if(ordertype == 1){
            $('#repair_item_type').css("display", "block");
            $('.issuerepair').css("display", "block");
            $('.issueothers').css("display", "none");
        }else{
            $('.issueothers').css("display", "block");
            $('.issuerepair').css("display", "none");
            $('#repair_item_type').css("display", "none");
        }
    });
    
    $('#repair_type').on('change',function(){
        var repairtype = this.value;
        if(repairtype == 1){
            $('.stock_repair_det').css("display", "block");
            $('.customer_repair_det').css("display", "none");
        }else{
            $('.customer_repair_det').css("display", "block");
            $('.stock_repair_det').css("display", "none");
        }
    });

   
    
   
    
    function create_new_empty_repair_order_row()
    {
    	var html = "";
		var a = $("#cus_i_increment").val();
		var i = ++a;
		$("#cus_i_increment").val(i); 
		var cus_due_date=$('#cus_due_date').val();
		var smith_due_date=$('#smith_due_date').val();
		var smith_rem_date=$('#smith_remainder_date').val();
		var collections_required=$('#collections_required').val();
		var subproduct_required=$('#subproduct_required').val();
		
		
			html+="<tr id='st_detail"+i+"' class='"+i+"'>"+
			"<td><input type='text' id='o_tag_code_"+i+"' name='o_item["+i+"][tag_code]'class='tag_code' required='true' /> <input type='hidden' id='o_item_repair_tag_id_"+i+"' name='o_item["+i+"][tag_id]'class='repair_tag_id'  />"+
			"<td><span class='tag_id_cat'></span><input type='hidden' id='o_item_id_cat_"+i+"' name='o_item["+i+"][id_cat]'class='id_cat' required='true' /></td>"+
			"<td><span class='tag_purity'></span><input type='hidden' name='o_item["+i+"][pure_wt]' class='pure_wt' required='true' /></td>"+
			"<td><span class='tag_id_prod'></span><input type='hidden' id='o_item_id_prod_"+i+"' name='o_item["+i+"][id_product]'class='id_product' required='true' /><input type='hidden' name='o_item["+i+"][orter_type]' id='ortertype"+i+"' value='3' required='true'/><input type='hidden' name='o_item["+i+"][id_purity]' id='id_purity"+i+"' value='1' required='true'/></td>"+
			"<td><span class='tag_id_des'></span><input type='hidden' id='o_item_id_des_"+i+"' name='o_item["+i+"][id_des]' class='id_des' required='true' /></td>"+
			"<td><span class='tag_id_sub_des'></span><input type='hidden' id='o_item_id_sub_des_"+i+"' name='o_item["+i+"][id_sub_des]' class='id_sub_des' required='true' /></td>"+
			"<td><input type='number' class='form-control gweight' name='o_item["+i+"][gweight]' id='gweight_"+i+"' autocomplete='off' readonly /></td>"+
			"<td><input type='number' class='form-control nweight' name='o_item["+i+"][nweight]' id='nweight_"+i+"' autocomplete='off' readonly /></td>"+
			"<td><input type='number' class='form-control qty' placeholder='Pcs' name='o_item["+i+"][totalitems]' required='true'/></td>"+
			'<td><a href="#" onClick="update_image_upload($(this).closest(\'tr\'));" class="btn btn-default btn-sm"><i class="fa fa-plus"></i></a><input type="hidden" class="order_img" name="o_item['+i+'][order_img]""></td>'+
			'<td><a href="#" onClick="update_order_description($(this).closest(\'tr\'));" class="btn btn-default btn-sm"><i class="fa fa-plus"></i></a><input type="hidden" class="order_des" name="o_item['+i+'][description]"></td>'+
			"<td><input class='form-control datemask date cus_due_dt' data-date-format='dd-mm-yyyy' name='o_item["+i+"][cus_due_date]' value="+cus_due_date+" type='text' required='true' placeholder='Cus Due Date' readonly />"+
			'<td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'
			"</tr>";  
			$('#stockrepair_item_detail tbody').append(html);
			
			$('.date').datepicker({ dateFormat: 'yyyy-mm-dd', })
		
            $('#stockrepair_item_detail > tbody').find('.category').focus();
    }
    
    
	

});

// add new metal information 
	$("#add_order_item").on('click',function(){
		var order_for = $("input[name='order[order_for]']:checked").val();
		if(($('#cus_id').val() == null ||$('#cus_id').val() == '') && order_for == 2){
			$("#customerAlert").html('<p style="color:red">Enter a valid customer name / mobile</p>');
			return false;
		}
		else if(($('#order_to_br').val() == null || $('#order_to_br').val() == '' )&& order_for == 1){
			alert("Select Order For Branch");
			return false;
		}
		else if($('.order_from').val() == null || $('.order_from').val() == ''){
			alert("Select Order Branch");
			return false;
		}
		else{ 
		        if(validateOrderDetailRow())
    		    {
    		        create_new_empty_cus_order_row();
    		    }
    		    else{
                    alert('Please Fill The Required Fields..');
                }
		        
			}
	});
    
    function validateOrderDetailRow()
    {
        var validate = true;
    	$('#item_detail > tbody  > tr').each(function(index, tr) {
    		if($(this).find('.id_product').val() == "" || $(this).find('.id_design').val() == "" || $(this).find('.sub_design').val() == "" || $(this).find('.weight').val() == ""  || $(this).find('.qty').val() == "" || $(this).find('.cus_due_day').val() == "" ){
    			validate = false;
    		}
    	});
    	return validate;
    }

    function create_new_empty_cus_order_row()
    {
        	var html = "";
        	var html = "";
			var a = $("#i_increment").val();
			var i = ++a;
			$("#i_increment").val(i); 
			var cus_due_date=$('#cus_due_date').val();
			var smith_due_date=$('#smith_due_date').val();
			var smith_rem_date=$('#smith_remainder_date').val();
			var collections_required=$('#collections_required').val();
			var subproduct_required=$('#subproduct_required').val(); 
			
				html+="<tr id='detail"+i+"' class='"+i+"'>"+
                "<td><select class='category' class='form-control ' name='o_item["+i+"][category]' id='category"+i+"' required='true'  style='width: 150px;'/><input type='hidden' name='o_item["+i+"][orter_type]' id='"+i+"' value='2' class='form-control order_type'><input type='hidden' name='o_item["+i+"][id_category]' id='id_category"+i+"' required='true'/><input type='hidden' class='metal_type' name='o_item["+i+"][id_metal]' id='id_metal"+i+"' required='true'/><input type='hidden' class='tax_group' name='o_item["+i+"][tax_group]' id='tax_group"+i+"' required='true'/></td>"+
                "<td><select class='purity'   class='form-control' style='width: 100px;' name='o_item["+i+"][purity]' id='purity"+i+"' required='true'/></td>"+
                "<td><select class='product'  class='form-control' name='o_item["+i+"][product]' id='product"+i+"' required='true' style='width: 150px;' /><input type='hidden' name='o_item["+i+"][id_product]' id='id_product"+i+"' required='true'/></td>"+
                "<td><select class='design'    class='form-control' name='o_item["+i+"][design]' id='design"+i+"'  required='true' style='width: 150px;' /></td>"+
                "<td><select class='sub_design' class='form-control' name='o_item["+i+"][sub_design]' id='sub_design"+i+"' required='true' style='width: 150px;' /></td>"+
                "<td><input type='text' class='form-control weight' name='o_item["+i+"][weight]' placeholder='Enter The Weight'  required id='weight_"+i+"' autocomplete='off' style='width: 100px;' /></td>"+
                "<td><select class=size  class='form-control ' placeholder='Size' name='o_item["+i+"][size]' required='true' style='width: 100px;'/></td>"+
                "<td><input type='number' class='form-control qty' placeholder='Pcs' name='o_item["+i+"][totalitems]' required='true' style='width: 70px;'/></td>"+
                '<td><input type="number" class="form-control cus_due_day" style="width: 100px"; name="o_item['+i+'][cus_due_day]" ></td>'+
                "<td><input type='number' class='form-control wast_percent' placeholder='Wast %' name='o_item["+i+"][wast_percent]' required='true' style='width: 70px;'/></td>"+
                "<td><input type='number' class='form-control wast_wgt' placeholder='Wast Weight' name='o_item["+i+"][wast_wgt]' required='true' style='width: 100px;' /></td>"+
                "<td><select class='mc_type' class='form-control' name='o_item["+i+"][id_mc_type]' style='width: 100px;'><option value='1'>Gram</option><option value='2' selected>Piece</option></select></td>"+
                "<td><input type='number' step='any' class='form-control mc' placeholder='MC Amount' name='o_item["+i+"][mc]' style='width: 100px;'/></td>"+
                "<td><input type='number' step='any' class='form-control stn_amt' placeholder='Amount' name='o_item["+i+"][stn_amt]' step='any' style='width: 100px;'/></td>"+
                "<td><input type='text' class='form-control order_amt' placeholder='Amount' name='o_item["+i+"][rate]' required='true' readonly='true' style='width: 100px;'/></td>"+
                '<td><a href="#" onClick="update_image_upload($(this).closest(\'tr\'));" class="btn btn-default btn-sm"><i class="fa fa-plus"></i></a><input type="hidden" class="order_img" name="o_item['+i+'][order_img]""></td>'+
                '<td><a href="#" onClick="update_order_description($(this).closest(\'tr\'));" class="btn btn-default btn-sm"><i class="fa fa-plus"></i></a><input type="hidden" class="order_des" name="o_item['+i+'][description]"></td>'+
                "</td>"+
                '<td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'
                "</tr>";  
                
				$('#item_detail tbody').append(html);
				
				$('.date').datepicker({ dateFormat: 'yyyy-mm-dd', })
				// Category
				$('#category'+i).append(
				    $("<option></option>")
				    .attr("value", "")    
				    .text('-Choose-')  
				);
				
				$.each(CategorysArr, function (key, item) {  
            
                    $('#category'+i).append(
                    $("<option></option>")
                    .attr("value", item.id_ret_category)    
                    .text(item.name)  
                    );
                });
                $('#item_detail > tbody').find('.category').select2();
                $('#item_detail > tbody').find('.purity').select2();
                $('#item_detail > tbody').find('.product').select2();
                $('#item_detail > tbody').find('.design').select2();
                $('#item_detail > tbody').find('.sub_design').select2();
                $('#item_detail > tbody').find('.size').select2();
                
                $('#item_detail > tbody').find('.category').select2({
                    placeholder: "category",
                    allowClear: true
                });
                
                $('#item_detail > tbody').find('.purity').select2({
                    placeholder: "Purity",
                    allowClear: true
                });
                
                $('#item_detail > tbody').find('.product').select2({
                    placeholder: "Product",
                    allowClear: true
                });
                
                $('#item_detail > tbody').find('.design').select2({
                    placeholder: "Design",
                    allowClear: true
                });
                
                $('#item_detail > tbody').find('.sub_design').select2({
                    placeholder: "Sub Design",
                    allowClear: true
                });
                
                $('#item_detail > tbody').find('.size').select2({
                    placeholder: "Size",
                    allowClear: true
                });

                $('#item_detail > tbody').find('.mc_type').select2();
                $('#item_detail > tbody').find('.mc_type').select2({
                    placeholder: "MC Type",
                    allowClear: true
                });
                
                $('#item_detail > tbody').find('.category').focus();
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
		deleteOrderItem(id);
	}
}

function deleteOrderItem(id){
	my_Date = new Date();
	$("div.overlay").css("display", "block"); 
	$.ajax({
		 url:base_url+"index.php/admin_ret_order/"+id+"?nocache=" + my_Date.getUTCSeconds(),
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

function validateImage()
 {
			
		switch(arguments[0].id){
			case 'category_img':
					var preview = $('#category_img_preview');
					break;
			case 'sub_category_img':
					var preview = $('#sub_category_img_preview');
					break;
			case 'default_prod_img':
					var preview = $('#default_img_preview');
					break;
			default:
			console.log(arguments[0].id);
					var preview = $('#'+arguments[0].id+'_Preview');
					break;
		}

		if(arguments[0].files[0].size > 1048576)
		{
		  alert('File size cannot be greater than 1 MB');
		  arguments[0].value = "";
		  preview.css('display','none');
		}

		else
		{
			var fileName =arguments[0].value;
			var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
			ext = ext.toLowerCase();
			if(ext != "jpg" && ext != "png" && ext != "jpeg")
			{
				alert("Upload JPG or PNG Images only");
				arguments[0].value = "";
				preview.css('display','none');
			}
			else
			{
				var file    = arguments[0].files[0];
				var reader  = new FileReader();
				  reader.onloadend = function () {
					preview.prop('src',reader.result);
				  }					
				  if (file)
				  {
				 	reader.readAsDataURL(file);
					preview.css('display','');
				  }
				  else
				  {
				  	preview.prop('src','');
					preview.css('display','none');
				  }
			}
		}
}
 
 

function getSearchProd(searchTxt,inputId,curRow){ 
	my_Date = new Date();
	$.ajax({
        url: base_url+'index.php/admin_ret_estimation/getProductBySearch/?nocache=' + my_Date.getUTCSeconds(),             
        dataType: "json", 
        method: "POST", 
        data: {'searchTxt': searchTxt,'cat_id' : curRow.find(".category").val()}, 
        success: function (data) { 
			$( ".product" ).autocomplete(
			{
				source: data,
				select: function(e, i)
				{ 
					e.preventDefault();
					$("#o_item_id_"+inputId ).val(i.item.value); 
					$("#"+inputId ).val(i.item.label);
					get_product_size(i.item.value,curRow);
				},
				response: function(e, i) {
		            // ui.content is the array that's about to be sent to the response callback.
		            /*console.log(i);
		            if (i.content.length === 0) {
		               $("#prodAlert").html('<p style="color:red">Enter a valid Product</p>');
		               $('#lt_product').val('');
		            }else{
						$("#prodAlert").html('');
					} */
		        },
				 minLength: 0,
			});
        }
     });
}

function get_product_size(curRow)
{
    my_Date = new Date();
    $.ajax({
        url: base_url+'index.php/admin_ret_order/get_product_size/?nocache=' + my_Date.getUTCSeconds(),             
        dataType: "json", 
        method: "POST", 
        data: {'id_product': curRow.find('.product').val()}, 
        success: function (data) { 

            curRow.find(".size option").remove();
            $('.size').append(
                $("<option></option>")
                .attr("value", "")    
                .text('-Choose-')  
            );
           $.each(data, function (key, item) {   
                curRow.find(".size").append(
                    $("<option></option>")
                    .attr("value", item.id_size)    
                    .text(item.value+'-'+item.name)  
                );
            });         
            $(".size").select2({
                placeholder: "Size",
                allowClear: true
            });
            curRow.find(".id_size").val(curRow.find(".size").val());
            $(".overlay").css("display", "none");   
            
            
        }
     });
}


function getSearchDesign(searchTxt,inputId){
	var str = inputId.split("_");  // Sample : prod_1 => split and pass the id
	var prod_id = $("#o_item_id_prod_"+str[1]).val();

	my_Date = new Date();
	$.ajax({
        url: base_url+'index.php/admin_ret_order/get_ActiveDesingns/?nocache=' + my_Date.getUTCSeconds(),             
        dataType: "json", 
        method: "POST", 
        data: {'searchTxt':searchTxt, 'product_id':prod_id}, 
        success: function (data) { 
			$( ".design" ).autocomplete(
			{
				source: data,
				select: function(e, i)
				{ 
					e.preventDefault();
					$("#o_item_id_"+inputId ).val(i.item.value); 
					$("#"+inputId ).val(i.item.label);  
					// Design sizes
					$.ajax({
				        url: base_url+'index.php/admin_ret_catalog/get_Activesize/?nocache=' + my_Date.getUTCSeconds(),             
				        dataType: "json", 
				        method: "POST", 
				        data: {'id_product': prod_id}, 
				        success: function (data) { 
							 
				        }
				    });
				    /*// Design purities
					$.ajax({
				        url: base_url+'index.php/admin_ret_catalog/design/d_purities/?nocache=' + my_Date.getUTCSeconds(),             
				        dataType: "json", 
				        method: "POST", 
				        data: {'design_no': i.item.value}, 
				        success: function (data) { 
							 
				        }
				    });*/
				},
				response: function(e, i) {
		            // ui.content is the array that's about to be sent to the response callback.
		            /*console.log(i);
		            if (i.content.length === 0) {
		               $("#prodAlert").html('<p style="color:red">Enter a valid Design</p>');
		               $('#lt_product').val('');
		            }else{
						$("#prodAlert").html('');
					} */
		        },
				 minLength: 0,
			});
        }
     });
}


function get_product_list()
{
	my_Date = new Date();
	 $("div.overlay").css("display", "block"); 
	$.ajax({
			 url:base_url+"index.php/product/ajax?nocache=" + my_Date.getUTCSeconds(),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			   			set_product_list(data);
			   			 $("div.overlay").css("display", "none"); 
					  },
					  error:function(error)  
					  {
						 $("div.overlay").css("display", "none"); 
					  }	 
			      });
}

function set_product_list(data)	
{
   var product = data.product;
   var access = data.access;
   var oTable = $('#product_list').DataTable();
   $("#total_product").text(product.length);
    if(access.add == '0')
	 {
		$('#add_product').attr('disabled','disabled');
	 }
	 oTable.clear().draw();
   	 if (product!= null && product.length > 0)
	 {
	 	oTable = $('#product_list').dataTable({
				                "bDestroy": true,
				                "bInfo": true,
				                "bFilter": true,
				                "bSort": true,
				                "aaData": product,
				                "order": [[ 0, "desc" ]],
				                "aoColumns": [  { "mDataProp": "id_product" },			                
								                { "mDataProp": "category_name" },					                
								                { "mDataProp": "subcategory_name" },					                
								                { "mDataProp": "name" },					                
								                { "mDataProp": function ( row, type, val, meta ){
					                	    		status_url = base_url+"index.php/product/status/"+(row.status==1?0:1)+"/"+row.id_product; 
							                		return "<a href='"+status_url+"'><i class='fa "+(row.status==1?'fa-check':'fa-remove')+"' style='color:"+(row.status==1?'green':'red')+"'></i></a>"
						                			}
								                },
			 									{ "mDataProp": function ( row, type, val, meta ) {
								                	 id= row.id_product
								                	 edit_url=(access.edit=='1' ? base_url+'index.php/product/edit/'+id : '#' );
								                	 delete_url=(access.delete=='1' ? base_url+'index.php/product/delete/'+id : '#' );
								                	 delete_confirm= (access.delete=='1' ?'#confirm-delete':'');
								                	  action_content='<a href="'+edit_url+'" class="btn btn-primary btn-edit"><i class="fa fa-edit" ></i> Edit</a><a href="#" class="btn btn-danger btn-del" data-href='+delete_url+' data-toggle="modal" data-target="#confirm-delete" ><i class="fa fa-trash"></i> Delete</a>';
								                	return action_content;
							                	}
							                 }] 



				            });	

		 }  

}

function searchEsti(searchTxt){
	my_Date = new Date();
	$.ajax({
        url: base_url+'index.php/admin_ret_order/estimation/getEstiBySearch/?nocache=' + my_Date.getUTCSeconds(),             
        dataType: "json", 
        method: "POST", 
        data: {'searchTxt':searchTxt}, 
        success: function (data) { 
			$( "#esti_no" ).autocomplete(
			{
				source: data,
				select: function(e, i)
				{ 
					e.preventDefault();
					$("#esti_no" ).val(i.item.value);   
						my_Date = new Date();
						$.ajax({
					        url: base_url+'index.php/admin_ret_order/estimation/getEstiDetails/?nocache=' + my_Date.getUTCSeconds(),             
					        dataType: "json", 
					        method: "POST", 
					        data: {'esti_id':i.item.value}, 
					        success: function (data) {  
					        	$("#item_detail tbody tr").empty();
					        	$("#aw_detail tbody tr").empty();
					        	
								$("#i_increment").val(0);
								var a = 0;
						        $.each(data.esti_items, function (key, item) {
						        	var html_1 = "";
									var i = ++a;
									$("#i_increment").val(i); 
									
									html_1="<tr id='detail"+i+"'><td>"+i+"</td><td><select style='width:100%;' name='o_item["+i+"][orter_type]' id='"+i+"' class='form-control order_type'><option value='1' "+(item.item_type == 1?'selected':'')+">Catalog order</option><option value='2'  "+(item.item_type == 2?'selected':'')+">Customer order</option><option value='3'  "+(item.item_type == 3?'selected':'')+">Repair order</option><option value='4'  "+(item.item_type == 4?'selected':'')+">Catalog Admin order</option></select></td><td><input type='text' class='form-control product' placeholder='Product Name' name='o_item["+i+"][product]' required='true' value='"+item.product_name+"' id='prod_"+i+"'/><input value='"+item.product_id+"' type='hidden' id='o_item_id_prod_"+i+"' name='o_item["+i+"][id_product]' required='true'/></td>"+"<td><input type='text' class='form-control design' placeholder='Design Name' value='"+item.itemname+"' id='dsgn_"+i+"' name='o_item["+i+"][design]' "+(item.item_type != 2?'required':'')+"/><input type='hidden' id='o_item_id_dsgn_"+i+"' value='"+item.design_no+"' name='o_item["+i+"][design_no]' /></td>"+"<td><input value='"+item.net_wt+"''  type='text' class='form-control' placeholder='Weight' name='o_item["+i+"][weight]' required='true'/></td>"+"<td><input value='"+item.piece+"' type='text' class='form-control' placeholder='Pcs' name='o_item["+i+"][totalitems]' required='true'/></td>"+"<td><input type='text' class='form-control'  value='"+item.size+"'  placeholder='Size' name='o_item["+i+"][size]' required='true'/></td>"+"<td><input type='text' class='form-control purity1' name='o_item["+i+"][purity]' id='purity"+i+"' value='"+item.purity+"' required='true'/><input value='"+item.id_purity+"' type='hidden' name='o_item["+i+"][id_purity]' id='id_purity"+i+"' required='true'/> </td>"+"<td><input  value='"+item.item_cost+"'  type='text' class='form-control' placeholder='Amount' name='o_item["+i+"][rate]' required='true' readonly='true'/></td><td><input class='form-control datemask date' data-date-format='dd-mm-yyyy' name='o_item["+i+"][smith_remainder_date]' type='text' required='true' placeholder='Smith Remainder Date'   readonly /></td><td><input class='form-control datemask date' data-date-format='dd-mm-yyyy' name='o_item["+i+"][smith_due_date]' type='text' required='true' placeholder='Smith Due Date'   readonly /></td><td><input class='form-control datemask date' data-date-format='dd-mm-yyyy' name='o_item["+i+"][cus_due_date]' type='text' required='true' placeholder='Cus Due Date' readonly /></td>"+"<td><button type='button' class='btn btn-danger' onclick='m_remove("+i+")'><i class='fa fa-trash'></i></button></td>";  						 
						 			$('#item_detail tbody').append(html_1); 
						 			$('.date').datepicker({ dateFormat: 'yyyy-mm-dd', })
						 		});
						 		
						        // ADVANCE WEIGHT
						 		var tot_amt = 0;
					 			var html_3 = "";
					 			var a = 0;
						 		$.each(data.esti_old_gold, function (key, item) {
						 			var html_2 = "";
									$("#aw_increment").val(0); 
									var i = ++a;
									$("#aw_increment").val(i);  
									html_2+="<tr id='detail"+i+"'><td>"+i+"</td><td>"+item.category+"</td><td>"+item.purpose+"</td><td>"+item.gross_wt+"</td><td>"+item.stone_wt+"</td><td>"+item.dust_wt+"</td><td>"+item.net_wt+"</td><td>"+item.wastage_percent+"</td><td>"+item.wastage_wt+"</td><td>"+item.rate_per_gram+"</td><td>"+item.amount+"</td></tr>";  
						 			$('#aw_detail tbody').append(html_2);
						 			tot_amt = parseFloat(tot_amt)+parseFloat(item.amount);
						        })
						        if(tot_amt > 0){
									html_3+="<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>Total Amt : </td><td>"+tot_amt+"</td></tr>";  
						 			$('#aw_detail tbody').append(html_3);
								} 
								// EST DETAIL  
						 			$('#est_date').text(data.esti.esti_date); 
						 			$('#tot_cost').text(data.esti.total_cost); 
						 			$('#g_voucher').text(data.esti.gift_voucher_amt); 
						 			$('#disc').text(data.esti.discount);  
						 			$('#cus').text(data.esti.customer);  
						 			$('#mobile').text(data.esti.mobile);  
						 			$('#id_customer').val(data.esti.id_customer);  
					        }
					     }); 
					
				},
				response: function(e, i) {
		            // ui.content is the array that's about to be sent to the response callback.
		            console.log(i);
		            /*if (i.content.length === 0) {
		               $("#subprodAlert").html('<p style="color:red">Enter a valid Sub Product</p>');
		               //$('#lt_product').val('');
		            }else{
						$("#subprodAlert").html('');
					}*/ 
		        },
				 minLength: 0,
			});
        }
     });
}


function setOrderList(from_date='',to_date='')
{
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_order/order?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'from_date':from_date,'to_date':to_date}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){	 
				 var order 	= data.orders;
				 var access		= data.access;	
				 $('#total_count').text(order.length);
		
			 	var oTable = $('#order_list').DataTable();
				 oTable.clear().draw();
				  
				 if (order!= null && order.length > 0)
				 {  	
					oTable = $('#order_list').dataTable({
						"bDestroy": true,
		                "bInfo": true,
		                "bFilter": true,
		                "scrollX":'100%',
		                "bSort": true,
		                "dom": 'lBfrtip',
		                "order": [[ 0, "desc" ]],
		                "buttons" : ['excel','print'],
				        "tableTools": { "buttons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },
						"aaData": order,
						"aoColumns": [	{ "mDataProp": "id_customerorder" },
										{ "mDataProp": "order_no" },
										{ "mDataProp": "order_for" },
										{ "mDataProp": "order_to" },
//										{ "mDataProp": "est_no" }, 
										{ "mDataProp": "order_date" },  
										{ "mDataProp": "order_items" },  
										{ "mDataProp": function ( row, type, val, meta ) {
											 id= row.id_customerorder;
											  edit_url=(access.edit=='1' ? base_url+'index.php/admin_ret_order/order/edit/'+id : '#' );
											  delete_url=(access.delete=='1' ? base_url+'index.php/admin_ret_order/order/delete/'+id : '#' );
											 delete_confirm= (access.delete=='1' ?'#confirm-delete':'');
											 action_content='<a href='+edit_url+' class="btn btn-primary btn-edit" id="edit" role="button" data-toggle="modal" data-id='+id+'><i class="fa fa-edit" ></i></a> <a href="#" class="btn btn-danger btn-del" data-href='+delete_url+' data-toggle="modal" data-target="#confirm-delete" ><i class="fa fa-trash"></i></a>'
											 return action_content;
											 }	
										}
									 ]
						});			  	 	
					}
			  },
			  error:function(error)  
			  {
				 $("div.overlay").css("display", "none"); 
			  }	 
	      });
}

function setEstiData(id){
	my_Date = new Date();
	$.ajax({
        url: base_url+'index.php/admin_ret_order/order/estiData/'+id+'?nocache=' + my_Date.getUTCSeconds(),             
        dataType: "json", 
        method: "POST",   
        success: function (data) {  
        	$("#item_detail tbody tr").empty();
        	$("#aw_detail tbody tr").empty();
        	$("#i_increment").val(0);
			var a = 0;
	        $.each(data.order_det, function (key, item) {
	        	var html_1 = "";				
				var i = ++a;
				$("#i_increment").val(i); 
				var id_orderdetails=item.id_orderdetails;
				html_1+="<tr id='detail"+i+"'><td>"+i+"<input type='hidden' id='id_orderdetails"+i+"' value='"+item.id_orderdetails+"' name='o_item["+i+"][id_orderdetails]'></td><td><select style='width:100%;' name='o_item["+i+"][orter_type]' id='"+i+"' class='form-control order_type'><option value='1' "+(item.ortertype == 1?'selected':'')+">Catalog order</option><option value='2'  "+(item.ortertype == 2?'selected':'')+">Customer order</option><option value='3'  "+(item.ortertype == 3?'selected':'')+">Repair order</option><option value='4'  "+(item.ortertype == 4?'selected':'')+">Catalog Admin order</option></select></td><td><input type='text' class='form-control product' placeholder='Product Name' name='o_item["+i+"][product]' required='true' value='"+item.product_name+"' id='prod_"+i+"'/><input value="+item.id_product+" type='hidden' id='o_item_id_prod_"+i+"' name='o_item["+i+"][id_product]' required='true'/></td>"+"<td><input type='text' class='form-control design' placeholder='Design Name' id='dsgn_"+i+"' value='"+item.itemname+"' name='o_item["+i+"][design]' "+(item.ortertype != 2?'required':'')+"/><input type='hidden' id='o_item_id_dsgn_"+i+"' value='"+item.design_no+"' name='o_item["+i+"][design_no]'/></td>"+"<td><input value='"+item.weight+"'  type='text' class='form-control' placeholder='Weight' name='o_item["+i+"][weight]' required='true'/></td>"+"<td><input value='"+item.totalitems+"' type='text' class='form-control' placeholder='Pcs' name='o_item["+i+"][totalitems]' required='true'/></td>"+"<td><input type='text' class='form-control'  value="+item.size+"  placeholder='Size' name='o_item["+i+"][size]' required='true'/></td>"+"<td><input type='text' class='form-control' name='o_item["+i+"][purity]' id='purity"+i+"' value='"+item.purity+"' required='true'/><input value='"+item.id_purity+"'' type='hidden' name='o_item["+i+"][id_purity]' id='id_purity"+i+"' required='true'/> </td>"+"<td><input  value='"+item.rate+"''  type='text' class='form-control' placeholder='Amount' name='o_item["+i+"][rate]' required='true' readonly='true'/></td><td><input class='form-control datemask date' data-date-format='dd-mm-yyyy' name='o_item["+i+"][smith_remainder_date]' value='"+item.smith_remainder_date+"'  type='text' required='true' placeholder='Smith Remainder Date'   readonly /></td><td><input class='form-control datemask date' data-date-format='dd-mm-yyyy' name='o_item["+i+"][smith_due_date]' type='text' required='true' placeholder='Smith Due Date' value='"+item.smith_due_date+"' readonly /></td><td><input class='form-control datemask date' data-date-format='dd-mm-yyyy' value='"+item.cus_due_date+"'  name='o_item["+i+"][cus_due_date]' type='text' required='true' placeholder='Cus Due Date' readonly /></td>"+"<td><button type='button' class='btn btn-danger' onclick='m_remove("+i+")' "+(item.orderstatus==0 ?'' :'disabled')+"><i class='fa fa-trash'></i></button></td>";  						 
	 			$('#item_detail tbody').append(html_1); 
	 			$('.date').datepicker({ dateFormat: 'yyyy-mm-dd', })

	 			//Image
				if(item.ortertype==2 || item.ortertype==3)
	 			{
	 				var newRow = $("<tr id='"+i+"' class='imgrow"+i+"'>"); 
					html2 = "<td colspan='3'><textarea name='o_item["+i+"][smith_remainder_date]' rows='3' cols='50'></textarea></td><td colspan='12'><input type='hidden' id='image_name_"+i+"' name='o_item["+i+"][image]' value=''> <input type='file' id='img"+i+"' class='img1' name='o_item["+i+"][img_1][]' multiple /><div alt='Upload Sample 1' class='col-md-12' id='1_img"+i+"'></div><input type='hidden' name='o_item["+i+"][remove_files]' id='files"+i+"'><button id='img_upload' type='button' class='btn btn-success pull-right'><i class='fa fa-plus'></i>Upload</button></td>"; 
					newRow.append(html2);
					newRow.insertAfter($('#detail'+i).closest('tr'));
		 			$.each(item.image,function(key, item){
		 			var preview=$('#1_img'+i);
					var div = document.createElement("div");
						div.setAttribute('class','col-md-3'); 
						div.setAttribute('id',+a+'_id'+key); 
						var name='"'+item.img_name+'"';
						div.innerHTML+= "<a onclick='remove_images("+id_orderdetails+","+name+")'><i class='fa fa-trash'></i></a><img class='thumbnail' src='" + item.src + "'" +
						"style='width: 100px;height: 100px;'/>";  
						preview.append(div);
		 			});
	 			}


	 		});
	 		
	        // ADVANCE WEIGHT
	 		var tot_amt = 0;
	 			var html_3 = "";
	 			$("#aw_increment").val(0);
				var a = 0;
	 		$.each(data.esti_old_gold, function (key, item) {
	 			var html_2 = "";
				
				var i = ++a;
				$("#aw_increment").val(i);  
				html_2+="<tr id='detail"+i+"'><td>"+i+"</td><td>"+item.category+"</td><td>"+item.purpose+"</td><td>"+item.gross_wt+"</td><td>"+item.stone_wt+"</td><td>"+item.dust_wt+"</td><td>"+item.net_wt+"</td><td>"+item.wastage_percent+"</td><td>"+item.wastage_wt+"</td><td>"+item.rate_per_gram+"</td><td>"+item.amount+"</td></tr>";  
	 			$('#aw_detail tbody').append(html_2);
	 			tot_amt = parseFloat(tot_amt)+parseFloat(item.amount);
	        })
	        if(tot_amt > 0){
				html_3+="<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>Total Amt : </td><td>"+tot_amt+"</td></tr>";  
	 			$('#aw_detail tbody').append(html_3);
			} 
			// EST DETAIL  
	 			$('#est_date').text(data.esti.esti_date); 
	 			$('#tot_cost').text(data.esti.total_cost); 
	 			$('#g_voucher').text(data.esti.gift_voucher_amt); 
	 			$('#disc').text(data.esti.discount);  
	 			$('#cus').text(data.esti.customer);  
	 			$('#mobile').text(data.esti.mobile);  
	 			$('#id_customer').val(data.esti.id_customer);  
        }
     }); 
}

function get_all_karigar()
{
	$.ajax({
	type: 'GET',
	url: base_url+'index.php/admin_ret_catalog/karigar/active_list',
	dataType:'json',
	success:function(data){
	    if($('#repair_assign_karigar').length == 0){
    		var id =  $("#karigar").val();
    		var filter_karigar =  $("#filter_karigar").val();
    		$.each(data, function (key, item) {   
    		    $("#karigar_sel,#karigar_filter").append(
    		    $("<option></option>")
    		    .attr("value", item.id_karigar)    
    		    .text(item.karigar)  
    		    );
    		}); 
    		$("#karigar_sel").select2(
    		{
    			placeholder:"Assign To Karigar",
    			closeOnSelect: true		    
    		});
    		$("#karigar_filter").select2(
    		{
    			placeholder:"Karigar Filter",
    			closeOnSelect: true		    
    		});
    		    $("#karigar_sel").select2("val",(id!='' && id>0?id:''));
    		    $("#karigar_filter").select2("val",(filter_karigar!='' && filter_karigar>0?filter_karigar:''));
    		    $(".overlay").css("display", "none");
    		    
	    }
		    if($('#repair_assign_karigar').length > 0){
		        $.each(data, function (key, item) {   
        		    $("#repair_assign_karigar").append(
        		    $("<option></option>")
        		    .attr("value", item.id_karigar)    
        		    .text(item.karigar)  
        		    );
        		}); 
        		$("#repair_assign_karigar").select2(
        		{
        			placeholder:"Select Karigar",
        			closeOnSelect: true		    
        		});
        		$("#repair_assign_karigar").select2("val",'');
		    }
		    
		}
	});
}

function get_all_employee()

	{

	    $('#issue_employee option').remove();

		my_Date = new Date();

		$.ajax({ 

		url:base_url+ "index.php/admin_ret_estimation/get_employee?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),

        data: {'id_branch' : $('#branch_select').val()},

        type:"POST",

        dataType:"JSON",

        success:function(data)

        {

           emp_details = data;

           $.each(data, function (key, item) {					  				  			   		

                	 	$("#issue_employee").append(						

                	 	$("<option></option>")						

                	 	.attr("value", item.id_employee)						  						  

                	 	.text(item.emp_name)						  					

                	 	);			   											

                 	});						

             	$("#issue_employee").select2({			    

            	 	placeholder: "Select Employee",			    

            	 	allowClear: true		    

             	});					

         	    //$("#issue_employee").select2("val",(id_employee!='' && id_employee>0?id_employee:''));	 

         	    $(".overlay").css("display", "none");	

        },

        error:function(error)  

        {	

        } 

    	});

	}


function get_all_master_data()
{
	$.ajax({
    	type: 'GET',
    	url: base_url+'index.php/admin_ret_order/active_cat_product_list',
    	dataType:'json',
    	success:function(data){
		    console.log(data);
		    cat_product_details = data;
		}
	});
}

$('#karigar_sel').on('change',function(e){
		if(this.value!='')
			{	
				$('#karigar').val(this.value);
			}
			else
			{
				$('#karigar').val('');
			}
});

$('#karigar_filter').on('change',function(e){
		if(this.value!='')
			{	
				$('#filter_karigar').val(this.value);
				 var from_date = $('#new_list1').text();
				 var to_date  = $('#new_list2').text();
				 var id_branch=$('#filter_branch').val();
				get_new_orderlist(from_date,to_date,id_branch,this.value);
			}
			else
			{
				$('#filter_karigar').val('');
			}
});

$('#branch_filter').on('change',function(e){
		if(this.value!='')
			{	
				$('#filter_branch').val(this.value);
				var from_date = $('#new_list1').text();
				 var to_date  = $('#new_list2').text();
				 var id_karigar=$('#filter_karigar').val();
				get_new_orderlist(from_date,to_date,this.value,id_karigar);
			}
			else
			{
				$('#filter_branch').val('');
			}
});

$('#select_all').click(function(event) {
	$("tbody tr td input[type='checkbox']").prop('checked', $(this).prop('checked'));
	event.stopPropagation();
});


function get_new_orderlist(from_date,to_date,id_branch,id_karigar)
{
    var id_branch=($('#filter_branch').val()!='' && $('#filter_branch').val()!=null ? $('#filter_branch').val():$('#id_branch').val()) 
	$(".overlay").css("display", "block");
		my_Date = new Date();
		$.ajax({
			 url: base_url+'index.php/admin_ret_order/ajax_get_neworder?nocache=' + my_Date.getUTCSeconds(),             
	        dataType: "json", 
	        method: "POST", 
	       data: ( {'from_date':from_date,'to_date':to_date,'id_branch':id_branch,'id_karigar':id_karigar}),
	        success: function (data)
	        {
	        	set_new_orderlist(data);
	        	$(".overlay").css("display", "none");
	        }
		});
}

function set_new_orderlist(order)
{


		var oTable = $('#neworder_list').DataTable();
		oTable.clear().draw();

		if (order.length > 0)
		{  	
			oTable = $('#neworder_list').dataTable({
				"bDestroy": true,
				"bInfo": true,
				"bFilter": true,
				"scrollX":'100%',
				"bSort": false,
				"dom": 'lBfrtip',
				 "buttons" : ['excel','print'],
				"tableTools": { "buttons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },
				"aaData": order,
				"aoColumns": [
				
				{ "mDataProp": function ( row, type, val, meta )
				{ 
				    if(row.cus_ord_status<=3)
				    {
				        chekbox='<input type="checkbox" class="id_orderdetails" name="id_orderdetails[]" value="'+row.id_orderdetails+'"/> ' 
		                return chekbox+" "+row.id_orderdetails+'<input type="hidden" class="id_product" value="'+row.id_product+'"><input type="hidden" class="id_category" value="'+row.id_category+'"><input type="hidden" class="product_name" value="'+row.product_name+'">';
				    }else{
		               return row.id_orderdetails+'<input type="hidden" class="id_product" value="'+row.id_product+'"><input type="hidden" class="id_category" value="'+row.id_category+'"><input type="hidden" class="product_name" value="'+row.product_name+'">';
				    }
		                	
		         }},
                { "mDataProp": function ( row, type, val, meta ){ 
                    id= row.id_orderdetails;
                    edit_target=("#imageModal_new");
                    content='<a href="#" class="btn btn-success btn-sm" id = "img_upload_order" value=0 data-toggle="modal" data-id='+id+'  data-target='+edit_target+'><i class="fa fa-plus"></i></a>';	
                    return content;
                }},
                { "mDataProp": function ( row, type, val, meta ){ 
                    id= row.id_orderdetails;
                    edit_target=("#order_des_new");
                    content='<a href="#" class="btn btn-default btn-sm" value=0 data-toggle="modal" data-id='+id+'  data-target='+edit_target+'><i class="fa fa-plus"></i></a>';	
                    return content;
                }},
				{ "mDataProp": "orderno" },
				{ "mDataProp": function(row,type,val,meta)
                    {
                        if(row.cus_ord_status<=3)
                        {
                            return '<input class="form-control smith_due_dt" data-date-format="dd-mm-yyyy" name="o_item["+i+"][smith_due_date]" value="'+row.smith_due_date+'" type="text" placeholder="Smith Due Date" style="width: 100px;"/><input type="hidden" class="order_date" value="'+row.order_date+'">';
                        }else
                        {
                            return row.smith_due_date;
                        }
                    }
                },
				{ "mDataProp": "orter_type" },
				{ "mDataProp": "cus_name" },
				{ "mDataProp": "emp_name" }, 
				{ "mDataProp": "product_name" },  
				{ "mDataProp": "design_name" },  
				{ "mDataProp": "sub_design_name" },  
				{ "mDataProp": "totalitems" },  
				{ "mDataProp": "weight" },  
				{ "mDataProp": "size" },  
				{ "mDataProp": function(row,type,val,meta)
	                	{return "<span class='label bg-"+row.color+"'>"+row.orderstatus+"</span>";	}
	                
	               },
				{ "mDataProp": "customer_ref_no" },  
				{ "mDataProp": "order_date" },  
				{ "mDataProp": "cus_due_date" },  
				{ "mDataProp": "branch_name" },  
				{ "mDataProp": "karigar_name" },
				{ "mDataProp": function ( row, type, val, meta ) {
					id= row.id_orderdetails;
					view_confirm= '#confirm-view';
					action_content='<a  class="btn btn-primary btn-edit" id="edit" data-toggle="modal" data-id='+id+' ><i class="fa fa-eye" ></i></a>'
					return action_content;
					}	
				}
				
				]
			});			  	 	
		}
		$('.smith_due_dt').datepicker({ dateFormat: 'yyyy-mm-dd'});
}


$("#order_des_new").on("hidden.bs.modal", function(){

 CKEDITOR.instances.description_new.destroy();

});
$(document).on('click', "#neworder_list a.btn-success", function(e) {
		e.preventDefault();
		id=$(this).data('id');
	    $("#edit-id").val(id);
		get_order_img(id);
});
$(document).on('click', "#neworder_list a.btn-default", function(e) {
		e.preventDefault();
		id=$(this).data('id');
	    $("#edit-id").val(id);
		get_order_des(id);
});	
$(document).on('click', "#neworder_list a.btn-edit", function(e) {
		e.preventDefault();
		id=$(this).data('id');
	    $("#edit-id").val(id); 
	   	 get_order_details(id);
});
$(document).on('click', "#neworder_list img.order_img", function(e) {
		e.preventDefault();
		id=$(this).data('id');
	    $("#edit-id").val(id); 
	    get_order_image(id);
});
function get_order_img(id)
{
	$('#order_images').html('');
	img_order_id = id;
	$.ajax({
        data: ( {'id_orderdetails':img_order_id}),
			  url:base_url+ "index.php/admin_ret_order/get_img_by_id?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),
			  dataType:"JSON",
			  type:"POST",
			  success:function(data){

				$.each(data,function(key,img){
					var preview = $('#order_images');
					div = document.createElement("div");
					div.setAttribute('class','col-md-3 images'); 
					div.setAttribute('id','order_img_'+[key]); 
					div.innerHTML+="<div class='form-group'><div class='image-input image-input-outline' id='kt_image_'><div class='image-input-wrapper'><a onclick='remove_order_images_new("+img.id_orderdetails+","+img.img_name+","+key+")'><i class='fa fa-trash'></i>Delete</a><img class='thumbnail' src='" + img.src + "'" + "style='width: 115px;height: 115px;'/></div></div>";
					preview.append(div); 
				});
			  },
			  error:function(error)
				{
					$("div.overlay").css("display", "none");
				}
		  });
}
$("#update_img_new").on('click',function()
{
	var final_file = [];
	var retrive_file = []; 
    let image_details=localStorage['img_details'];
	if(image_details)
	 {
	   img_final = JSON.parse(image_details);
	 }
	 localStorage.removeItem("img_details");
	   $('#imageModal_new').modal('toggle');
	   if(image_details == '' || image_details == null)
		{
			for (i = 0; i < retrive_img.length; i++)
			   {
                  retrive_file.push(retrive_img[i]);
			   }
	          $.ajax({
              data: ( {'id_orderdetails':img_order_id,'image':retrive_file}),
			  url:base_url+ "index.php/admin_ret_order/insert_retrive_img?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),
			  dataType:"JSON",
			  type:"POST",
			  success:function(data){
			  },
			  error:function(error)
				{
					$("div.overlay").css("display", "none");
				}
		  });
        }
		else if(image_details != '' || image_details != null)
		{
             for (i = 0; i < img_final.length; i++)
			 {
                final_file.push(img_final[i].src);
			 }
	         $.ajax({
             data: ( {'id_orderdetails':img_order_id,'image':final_file}),
			 url:base_url+ "index.php/admin_ret_order/update_order_image?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
				  if(data.status)
				  {
					$.toaster({ priority : 'success', title : 'Warning!', message : ''+"</br>"+data.message});
				  }else{
					$.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+data.message});
				  }
			 },
			 error:function(error)
				{
					$("div.overlay").css("display", "none");
				}
		  });
		}
		else if((image_details != '' || image_details != null) && (retrive_img != null || retrive_img != ''))
		{
			for (i = 0; i < retrive_img.length; i++)
			    {
                      retrive_file.push(retrive_img[i]);
					  }
					  for (i = 0; i < img_final.length; i++)
					  {
                      final_file.push(img_final[i].src);
					  }
					  $.ajax({
              data: ( {'id_orderdetails':img_order_id,'new_image':final_file,'retrive_image':retrive_file}),
			  url:base_url+ "index.php/admin_ret_order/update_and_retrive_order_image?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),
			  dataType:"JSON",
			  type:"POST",
			  success:function(data){
				  
			  },
			  error:function(error)
				{
					$("div.overlay").css("display", "none");
				}
		  });
		}	
});
function insert_retrive_img(retrive_file)
{
	$.ajax({
        data: ( {'id_orderdetails':img_order_id,'image':retrive_file}),
			  url:base_url+ "index.php/admin_ret_order/insert_retrive_img?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),
			  dataType:"JSON",
			  type:"POST",
			  success:function(data){
				  
			  },
			  error:function(error)
				{
					$("div.overlay").css("display", "none");
				}
		  });
}
function remove_order_images_new(id_orderdetails,img_name,key)
{
    console.log(img_name);
	my_Date = new Date();
	$.ajax({
		url:base_url+ "index.php/admin_ret_order/delete_order_img?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),
		dataType:"JSON",
		data: ({'image':img_name,'id_orderdetails':id_orderdetails}),
		type:"POST",
		success:function(data){
			if(data.status)
			{
				$('#order_img_'+key).remove();
			  $.toaster({ priority : 'success', title : 'Warning!', message : ''+"</br>"+data.message});
			}else{
			  $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+data.message});
			}
		},
		error:function(error)
		{
			$("div.overlay").css("display", "none");
		}
	});
}
function get_order_des(id)
{
	des_order_id = id;
	$.ajax({
        data: ( {'id_orderdetails':des_order_id}),
			  url:base_url+ "index.php/admin_ret_order/get_dec_by_id?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),
			  dataType:"JSON",
			  type:"POST",
			  success:function(data){
				  description = data[0].description;
				  CKEDITOR.replace('description_new');
		          CKEDITOR.instances.description_new.setData(description);
			  },
			  error:function(error)
				{
					$("div.overlay").css("display", "none");
				}
		  });
}
$("#add_desc_new").on('click',function()
{
	$('#order_des_new').modal('toggle');
	description=  CKEDITOR.instances.description_new.getData();
	my_Date = new Date();
    var form_data = new FormData();  
    form_data.append('description', description);
	form_data.append('id_orderdetails', des_order_id);
	$.ajax({
		data:form_data,
		url: base_url+"index.php/admin_ret_order/update_order_des?nocache=" + my_Date.getUTCSeconds(),
		type:"POST",
        dataType:'json',
        cache : false,
		enctype: 'multipart/form-data',
		contentType : false,
		processData : false,
		success:function(data){
		    if(data.status)
			{
			  $.toaster({ priority : 'success', title : 'Warning!', message : ''+"</br>"+data.message});
			}else{
			  $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+data.message});
			}
        }
	});
});	


$(document).on('change', ".smith_due_dt", function(e) {
    	var row = $(this).closest('tr');
        var order_date=dateToTimeStamp(row.find('.order_date').val());
        var karigar_due_date=dateToTimeStamp(this.value);
        if(karigar_due_date<order_date)
        {
            row.find('.smith_due_dt').val('');
            $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select Valid Date..'});

        }
        
});

function dateToTimeStamp(date)
{
    new_date=date.split("-");
    new_date = new_date[1]+"/"+new_date[0]+"/"+new_date[2];
    time_stamp=new Date(new_date).getTime();
    
    return time_stamp;
}

$(document).on('click', "#neworder_list a.btn-edit", function(e) {
		e.preventDefault();
		id=$(this).data('id');
	    $("#edit-id").val(id); 
	   	 get_order_details(id);
	});

$(document).on('click', "#neworder_list img.order_img", function(e) {
		e.preventDefault();
		id=$(this).data('id');
	    $("#edit-id").val(id); 
	    get_order_image(id);
	});


$("input[name='upd_status_btn']:radio").change(function()
{
	if($("input[name='id_orderdetails[]']:checked").val())
	{
		var selected = [];
		var approve=false;
		$("#neworder_list tbody tr").each(function(index, value){
			if($(value).find("input[name='id_orderdetails[]']:checked").is(":checked"))
			{
				transData = {
				 'id_orderdetails'   : $(value).find(".id_orderdetails").val(),
				 'id_category'   : $(value).find(".id_category").val(),
				 'smith_due_dt'   : $(value).find(".smith_due_dt").val(),
				}
				selected.push(transData);	
			}
			
		});
		var id_vendor=$('#karigar').val();
		var id_branch=$('#id_branch').val();
		req_status = $("input[name='upd_status_btn']:checked").val();
		req_data = selected;
		if(id_vendor!='')
		{
				update_request_data(req_status,req_data,id_vendor,id_branch);
		}
		else if(req_status==2)
		{
				update_request_data(req_status,req_data,'','');
		}
		else
		{
			alert('Please Select Karigar and Branch');
			$('input[name=upd_status_btn]').removeAttr('checked');
		}
		
	}

	else
	{
		alert('Please Select valid Karigar/Branch');
		 $('input[name=upd_status_btn]').removeAttr('checked');
	}
});

function update_request_data(req_status,req_data,id_vendor,id_branch)
{
    	$(".overlay").css("display", "block");
		my_Date = new Date();
		$.ajax({
		url: base_url+'index.php/admin_ret_order/assign_customer_order?nocache=' + my_Date.getUTCSeconds(),             
		method: "post", 
		async:false,
		dataType:"json",
		data: ( {'req_status':req_status,'req_data':req_data,'id_vendor':id_vendor,'id_branch':id_branch}),
		success: function (data)
		{
		        if(req_status==1)
		        {
		            console.log(data.id_customerorder);
		            //window.open( base_url+'index.php/admin_ret_order/get_karigar_acknowladgement/?id_order='+data.id_customerorder,'_blank');
		        }
				window.location.reload()
		}
		});
}

function get_all_branches()
{
		my_Date = new Date();
		$.ajax({
		url: base_url+'index.php/admin_ret_order/get_all_branch?nocache=' + my_Date.getUTCSeconds(),             
		method: "get", 
		dataType:"json",
		success: function (data)
		{
				var id=$("#select_branch").val();
				var filter_branch=$("#filter_branch").val();
					$.each(data, function (key, item) {   
					$("#select_branch").append(
					$("<option></option>")
					.attr("value", item.id_branch)    
					.text(item.branch_name)  
					);
					$("#branch_filter").append(
					$("<option></option>")
					.attr("value", item.id_branch)    
					.text(item.branch_name)  
					);
					});
				
				$("#select_branch").select2(
				{
					placeholder:"Assign To Branch",
					closeOnSelect: true		    
				});
				$("#branch_filter").select2(
				{
					placeholder:"Branch Filter",
					closeOnSelect: true		    
				});
				    $("#select_branch").select2("val",(id!='' && id>0?id:''));
				    $("#branch_filter").select2("val",(filter_branch!='' && filter_branch>0?filter_branch:''));
				    $(".overlay").css("display", "none");
		}
		});
}

$('#select_branch').on('change',function(e){
		if(this.value!='')
			{	
				$('#id_branch').val(this.value);
			}
			else
			{
				$('#id_branch').val('');
			}
});

function get_order_details(id)
{
		my_Date = new Date();
		$.ajax({
		url: base_url+'index.php/admin_ret_order/get_ordersby_id/'+id+'/?nocache=' + my_Date.getUTCSeconds(),             
		method: "get", 
		dataType:"json",
		async:false,
		success: function (data)
		{
				$('#id_orderdetails').val(data.id_orderdetails);
				var content='<div class="col-md-6"><label>Order No &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label> &nbsp;'+data.orderno+'</div><div class="col-md-6"><label>Customer Name &nbsp;: </label> &nbsp;'+data.cus_name+'</div><br><div class="col-md-6"><label>Mobile&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label> &nbsp;'+data.mobile+'</div><div class="col-md-6"><label>Karigar Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label> &nbsp;'+data.karigar_name+'</div><div class="col-md-6"><label>Type &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label> &nbsp;'+data.orter_type+'</div><br><div class="col-md-6"><label>Product Name &nbsp;&nbsp;&nbsp;&nbsp;: </label> &nbsp;'+data.product_name+'</div><br><br><div class="col-md-6"><label>OrderDate &nbsp;&nbsp;&nbsp;&nbsp;: </label> &nbsp;'+data.order_date+'</div><div class="col-md-6"><label>Product Code &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label> &nbsp;'+data.product_short_code+'</div><br><div class="col-md-6"><label>Weight &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label> &nbsp;'+data.weight+'</div><div class="col-md-6"><label>Size &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label> &nbsp;'+data.size+'</div><br><div class="col-md-6"><label>Pcs &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label> &nbsp;'+data.totalitems+'</div><div class="col-md-6"><label>Status &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;</label> '+data.orderstatus+'</div></br><div class="col-md-12"><label>Purity &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :</label>&nbsp;'+data.purity+'</div></br><div class="col-md-12"> <textarea id="reject_reason"  placeholder="Enter Reject Reason" style="width: 46%; "/></div>';
				$('#confirm-view .modal-body').html(content);
				$('#confirm-view').modal('show');
		}
		});
}

function get_order_image(id)
{
		my_Date = new Date();
		$.ajax({
		url: base_url+'index.php/admin_ret_order/get_ordersby_id/'+id+'/?nocache=' + my_Date.getUTCSeconds(),             
		method: "get", 
		dataType:"json",
		async:false,
		success: function (data)
		{

				$('#imagePreview').empty();
				for (var i = 0; i < data.order_image.length; i++) {
				$("#imagePreview").append($('<img>', {src: data.order_image[i],style:"width:100px;"},));
				}
				$('#image-view').modal('show');
		}
		});
}

$('#reason_submit').on('click',function(){
	
	var reject_reason=$('#reject_reason').val();
	var id_orderdetails=$('#id_orderdetails').val();
	my_Date = new Date();
		$.ajax({
		url: base_url+'index.php/admin_ret_order/updatereject_reason/?nocache=' + my_Date.getUTCSeconds(),             
		method: "post",
		data:{'id_orderdetails':id_orderdetails,'reject_reason':reject_reason}, 
		dataType:"json",
		async:false,
		success: function (data)
		{
			window.location.reload()
		}
		});

});


function item_validateImage()
 {
		var files = event.target.files;
		var a = $('#cur_id').val();
		var preview=$('#1_img'+a);
		var html_1="";

		
		
		 for (var i = 0; i < files.length; i++) 
		 {

                var file = files[i];
                total_files.push(file);

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
					    var id=i;
						reader.onload = function (event) {

						img_resource.push({'src':event.target.result,'name':fileName});
						}
						if (file)
						{
						reader.readAsDataURL(file);
						}
						else
						{
						preview.prop('src','');
						
						}
					}

			 	}
                 
            }
	setTimeout(function(){
		console.log(img_resource);
		$.each(img_resource,function(key,item){
			   if(item)
			   {
			   		var div = document.createElement("div");
					div.setAttribute('class','col-md-3'); 
					div.setAttribute('id',+a+'_id'+key); 
					div.innerHTML+= "<a onclick='img_remove("+key+")'><i class='fa fa-trash'></i></a><img class='thumbnail' src='" + item.src + "'" +
					"style='width: 100px;height: 100px;'/>";  
					preview.append(div);
			   }
		});

	},3000);  

}
 
 function img_remove(id)
 {
 		var a = $('#cur_id').val();
 		$('#'+a+'_id'+id).remove();
		const index = total_files.indexOf(img_resource[id]);
		total_files.splice(index,1);
 }

function remove_images(id="",file="")
{
		var a = $('#cur_id').val();
 		$('#'+a+'_id'+id).remove();
 		$("div.overlay").css("display", "block"); 
		$.ajax({
			   url:base_url+"index.php/admin_ret_order/remove_img",
			   type : "POST",
			   data : {'file':file,'id':id},
			   success : function(result) {
			   	$("div.overlay").css("display", "none"); 
				 window.location.reload();
			   },
			   error : function(error){
				$("div.overlay").css("display", "none"); 
			   }

			});
}


 $(document).on('click',"#img_upload", function(){ 
	var formData = new FormData();
	var current=$('#cur_id').val();
	for(var i = 0;i<total_files.length;i++){
        formData.append("file[]", total_files[i]);
    }
	var my_Date = new Date();
	$.ajax({
        url: base_url+'index.php/admin_ret_order/upload_orderimg/?nocache=' + my_Date.getUTCSeconds(),             
        dataType: "json", 
        method: "POST", 
         cache:false,
            contentType: false,
            processData: false,
        data:formData, 
        success: function (data) { 
			total_files=[];
		
			$('#image_name_'+current).val(data.name);

		
        }
     });
});
function get_metal_rates_by_branch()
{
	var id_branch = $('#branch_select').val();
	my_Date = new Date();
	$.ajax({
		url:base_url+ "index.php/admin_ret_tagging/get_metal_rates_by_branch?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),
		data:  {'id_branch':id_branch},
		type:"POST",
		dataType: "json",
		async:false,
		success:function(data){
		    if(data.goldrate_22ct>0)
		    {
		        $('.per-grm-sale-value').html(data.goldrate_22ct);
			    $('.silver_per-grm-sale-value').html(data.silverrate_1gm);
		    }else{
		        $('.per-grm-sale-value').html(0);
			    $('.silver_per-grm-sale-value').html(0);
		    }
			
			
		},
		error:function(error)  
		{
			$("div.overlay").css("display", "none");
		}
	});
}
function getSearchCustomers(searchTxt){
	my_Date = new Date();
	$.ajax({
        url: base_url+'index.php/admin_ret_estimation/getCustomersBySearch/?nocache=' + my_Date.getUTCSeconds(),             
        dataType: "json", 
        method: "POST", 
        data: {'searchTxt': searchTxt}, 
        success: function (data) {
			$( "#cus_name" ).autocomplete(
			{
				source: data,
				select: function(e, i)
				{ 
					e.preventDefault();
					$("#cus_name").val(i.item.label);
					$("#cus_id").val(i.item.value);
					$("#cus_village").html(i.item.village_name);
					$("#chit_cus").html((i.item.accounts==0 ?'No' :'Yes'));
					$("#vip_cus").html(i.item.vip);
					$("#cus_state").val(i.item.cus_state);
					$("#cmp_state").val(i.item.cmp_state);
				},
				change: function (event, ui) {
					if (ui.item === null) {
						$(this).val('');
						$('#cus_name').val('');
						$("#cus_id").val("");
						$("#cus_village").html("");
						$("#chit_cus").html("");
						$("#vip_cus").html("");
					}
			    },
				response: function(e, i) {
		            // ui.content is the array that's about to be sent to the response callback.
		            if(searchTxt != ""){
						if (i.content.length === 0) {
						   $("#customerAlert").html('<p style="color:red">Enter a valid customer name / mobile</p>');
						}else{
						   $("#customerAlert").html('');
						} 
					}else{
					}
		        },
				 minLength: 3,
			});
        }
     });
}
function get_cat_purity(curRow,id_category)
{ 
	$(".overlay").css('display','block');
	$.ajax({
		type: 'POST',
		url: base_url+'index.php/admin_ret_catalog/category/cat_purity',
		dataType:'json',
		data: {
			'id_category' : id_category
		},
		success:function(data){
		   curRow.find(".purity option").remove();
		   $('.purity').append(
			    $("<option></option>")
			    .attr("value", "")    
			    .text('-Choose-')  
			);
		   $.each(data, function (key, item) {   
				curRow.find(".purity").append(
					$("<option></option>")
					.attr("value", item.id_purity)    
					.text(item.purity)  
				);
			});			
			/*curRow.find(".purity").select2({    
				placeholder: "Select Purity",    
				allowClear: true    
			});*/
			curRow.find(".id_purity").val(curRow.find(".purity").val());
			$(".overlay").css("display", "none");	
		}
	});
}
			
function getTaxGroupDetail(curRow,tgrp_id)
{
	$(".overlay").css('display','block');
	$.ajax({
		type: 'POST',
		url: base_url+'index.php/admin_ret_order/taxGroupItems',
		dataType:'json',
		data: {
			'tgrp_id' : tgrp_id
		},
		success:function(data){
		   tax_details = data;    	
		}
	});
}


$('#cus_mobile').on('keyup',function(){
	   if(this.value.length>10)
	   {
	       $('#cus_mobile').val('');
	       $('#cus_mobile').focus();
	   }
	   else{
	        $('#cus_mobile').prop('disabled',false);
	   }
	});
	
$("#add_newcutomer").on('click', function(){
		if($('#cus_first_name').val() != "")
		{
			$(".cus_first_name").html("");
			if($('#cus_mobile').val() != "")
			{
				$(".cus_mobile").html("");
					add_cutomer($('#cus_first_name').val(),$('#cus_mobile').val(),$('#id_village').val(),$('#cus_type:checked').val());
					$('#cus_first_name').val('');
					$('#cus_mobile').val('');
			}else{
				$(".cus_mobile").html("Please enter customer mobile");
			}
		}else{
			$(".cus_first_name").html("Please enter customer first name");
		}
});

function add_cutomer(cus_name, cus_mobile,id_village,cus_type){ //, cus_address
	my_Date = new Date();
	$.ajax({
        url: base_url+'index.php/admin_ret_estimation/createNewCustomer/?nocache=' + my_Date.getUTCSeconds(),             
        dataType: "json", 
        method: "POST", 
        data: {'cusName': cus_name, 'cusMobile' : cus_mobile, 'cusBranch' : $('#id_branch').val(),'id_village':id_village,'cus_type':cus_type}, //Need to update login branch id here from session
        success: function (data) { 
			if(data.success == true){
				$('#confirm-add').modal('toggle');
				$("#cus_name").val(data.response.firstname + " - " + data.response.mobile);
				$("#cus_id").val(data.response.id_customer);
			}else{
				alert(data.message);
			}
        }
     });
}

$('#add_new_customer').on('click',function(e){
		//get_village_list();
});


function get_village_list()
	{
	    $('#sel_village option').remove();
	    $('#ed_sel_village option').remove();
		my_Date = new Date();
		$.ajax({ 
		url:base_url+ "index.php/admin_ret_estimation/ajax_get_village?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),
        type:"GET",
        dataType:"JSON",
        success:function(data)
        {
        	var id_village=$('#id_village').val();
        	var ed_id_village=$('#ed_id_village').val();
           $.each(data, function (key, item) {					  				  			   		
                	 	$("#sel_village,#ed_sel_village").append(						
                	 	$("<option></option>")						
                	 	.attr("value", item.id_village)						  						  
                	 	.text(item.village_name)						  					
                	 	);			   											
                 	});						
             	$("#sel_village,#ed_sel_village").select2({			    
            	 	placeholder: "Select Village",			    
            	 	allowClear: true		    
             	});	
             	console.log(id_village);
             	if(id_village!='' && id_village!=null && id_village!=undefined)
             	{
             	    $("#sel_village").select2("val",(id_village!='' && id_village>0?id_village:''));
             	}
             	
             	if(ed_id_village!='')
             	{
             	    console.log(ed_id_village);
             	    $("#ed_sel_village").select2("val",(ed_id_village!='' && ed_id_village!=null ? ed_id_village:''));
             	}
         	    	 
         	    $(".overlay").css("display", "none");	
        },
        error:function(error)  
        {	
        } 
    	});
	}
	
	$("#search_bill_no").on('click', function(){

    	getBillDetails($('#filter_bill_no').val());
    	
    });
    
    $(document).on('change', '#branch_select', function(e)
    {
        get_all_employee();
    });
    
    $(document).on('keyup',	".tag_code", function(e){ 
        	var row = $(this).closest('tr');
        	var tagData = this.value;
        	var type  = "";
        	var searchTxt  = "";
        	if(tagData != ""){
        		//Tab Not Active
        	   // $('ul#tabs > li').not('.active').addClass('disabled disabledTab');
        		var istagId = (tagData.search("/") > 0 ? true : false);
        		var isTagCode = (tagData.search("-") > 0 ? true : false);
        		if(istagId){
        			
        			var tId   = tagData.split("/"); 
        			searchTxt = (tId.length >= 2 ? tId[0] : ""); 
        			type  = "tag_id";
        		}
        		else if(isTagCode){  
        			searchTxt = this.value; 
        			type  = "tag_code";
        		} 
        		if(searchTxt != ""){
        			if($("#branch_settings").val() == 1){
        				if($("#id_branch").val() != ""){ 
        					getSearchTags(searchTxt, type, row);
        				}else{
        					alert("Select Branch");
        					$(this).val("");
        				}
        			}else{
        				getSearchTags(searchTxt, type, row);
        			}
        		}
        	}
        	else
        	{
        		//Tab Not Active
        	   if($(row).find('td:eq(1) .pro_id').val() !="") {
        			$(row).find('td:eq(0) .est_tag_id').val("");
        	   }
        	} 			
    });

    
    function getSearchTags(searchTxt, searchField, curRow){
    	my_Date = new Date();
    	$.ajax({
            url: base_url+'index.php/admin_ret_order/getIssueTaggingBySearch/?nocache=' + my_Date.getUTCSeconds(),             
            dataType: "json", 
            method: "POST", 
            data: {'searchTxt': searchTxt, 'searchField': searchField, 'id_branch': $("#branch_select").val()}, 
            success: function (data) {
    			cur_search_tags = data;
    			var ordertype = $('#issue_type').val();  
                if(ordertype == 1){
        			$.each(data, function(key, item){
        				$('#stockrepair_item_detail > tbody tr').each(function(idx, row){
        					if(item != undefined){
        						if($(this).find('.repair_tag_id').val() == item.value){
        							data.splice(key, 1);
        						}
        					}
        				});
        			});
        			$(".tag_code").autocomplete(
        			{
        				source: data,
        				select: function(e, i)
        				{ 
        					e.preventDefault(); 
        					var curRowItem = i.item;
        					
        					var purewt = parseFloat((parseFloat(curRowItem.net_wt) * (parseFloat(curRowItem.purname))) / 100).toFixed(3);
		
		
        					curRow.find('.tag_code').val(i.item.label);
        				    curRow.find('.repair_tag_id').val(i.item.value); 
        					curRow.find('.tag_id_cat').html(curRowItem.catname);
        					curRow.find('.id_cat').val(i.item.catid); 
        					curRow.find('.tag_id_prod').html(curRowItem.product_name);
        					curRow.find('.id_product').val(i.item.lot_product); 
        					curRow.find('.tag_id_des').html(curRowItem.design_name);
        					curRow.find('.id_des').val(i.item.design_id); 
        					curRow.find('.tag_id_sub_des').html(curRowItem.sub_design_name);
        					curRow.find('.id_sub_des').val(i.item.subdesignid); 
        					curRow.find('.gweight').val(curRowItem.gross_wt);
        					curRow.find('.nweight').val(curRowItem.net_wt);
        					curRow.find('.qty').val(curRowItem.piece);
        					curRow.find('.tag_purity').html(curRowItem.purname);
        					curRow.find('.pure_wt').val(purewt);
        					
        				   $("#repaid_order_items").trigger('click');
        				},
        				change: function (event, ui) {
        					if (ui.item === null) {
        						/* $(this).val('');
        						curRow.find('td:eq(0) .est_tag_name').val("");
        						curRow.find('td:eq(0) .est_tag_id').val(""); */
        					}else{
        						//$('#estimation_tag_details > tbody').find('tr:last td:eq(0) .est_tag_name').focus();
        					}
        			    },
        				response: function(e, i) {
        		            // ui.content is the array that's about to be sent to the response callback.
        		            if(searchTxt != ""){
        						if (i.content.length !== 0) {
        						   //console.log("content : ", i.content);
        						}
        					}else{
        						curRow.find('.tag_code').val("");
        						curRow.find('.repair_tag_id').val("");
        					}
        		        },
        				 minLength: 1,
        			});
                }else{
        			$.each(data, function(key, item){
        				$('#tagissue_item_detail > tbody tr').each(function(idx, row){
        					if(item != undefined){
        						if($(this).find('.issue_tag_id').val() == item.value){
        							data.splice(key, 1);
        						}
        					}
        				});
        			});
        			$(".tag_code").autocomplete(
        			{
        				source: data,
        				select: function(e, i)
        				{ 
        					e.preventDefault(); 
        					var curRowItem = i.item; 
        					curRow.find('.tag_code').val(i.item.label);
        				    curRow.find('.issue_tag_id').val(i.item.value); 
        					curRow.find('.tag_id_cat').html(curRowItem.catname);
        					curRow.find('.id_cat').val(i.item.catid); 
        					curRow.find('.tag_id_prod').html(curRowItem.product_name);
        					curRow.find('.id_product').val(i.item.lot_product); 
        					curRow.find('.tag_id_des').html(curRowItem.design_name);
        					curRow.find('.id_des').val(i.item.design_id); 
        					curRow.find('.tag_id_sub_des').html(curRowItem.sub_design_name);
        					curRow.find('.id_sub_des').val(i.item.subdesignid); 
        					curRow.find('.gweight').val(curRowItem.gross_wt);
        					curRow.find('.nweight').val(curRowItem.net_wt);
        					curRow.find('.qty').val(curRowItem.piece);
        				   $("#add_issue_items").trigger('click');
        				},
        				change: function (event, ui) {
        					if (ui.item === null) {
        						/* $(this).val('');
        						curRow.find('td:eq(0) .est_tag_name').val("");
        						curRow.find('td:eq(0) .est_tag_id').val(""); */
        					}else{
        						//$('#estimation_tag_details > tbody').find('tr:last td:eq(0) .est_tag_name').focus();
        					}
        			    },
        				response: function(e, i) {
        		            // ui.content is the array that's about to be sent to the response callback.
        		            if(searchTxt != ""){
        						if (i.content.length !== 0) {
        						   //console.log("content : ", i.content);
        						}
        					}else{
        						curRow.find('.tag_code').val("");
        						curRow.find('.repair_tag_id').val("");
        					}
        		        },
        				 minLength: 1,
        			});
                }
            }
         });
    }
    
    
    $(document).on('change', '.cat_select', function(e)
    {
    	if($(this).closest('tr').find('option:selected'))
    	{
    		$(this).closest('tr').find('.pro_select').val(null).trigger('change');
    	    var row = $(this).closest('tr'); 
    	    var pro_append = "<option value=''>- Select Product-</option>";
    	    $(this).closest('tr').find('.pro_select option').remove();
    		var cat_select = row.find('.cat_select').val();
    		
    		if(cat_select != '')
    		{
                $.each(cat_product_details, function (catkey, catval) {
                    if(catval.id_ret_category == cat_select){
    					$.each(catval.products, function (mkey, mitem) {
    					    pro_append += "<option value='"+mitem.pro_id+"'>"+mitem.product_name+"</option>";
                        });
    			        row.find('.pro_select').append(pro_append);
					}
                });
    	    }
    	}
    });

function getBillDetails(billNo){
	$("#search_bill_no").val("");
	my_Date = new Date();
	$.ajax({
        url: base_url+'index.php/admin_ret_order/get_bill_details/?nocache=' + my_Date.getUTCSeconds(),             
        dataType: "json", 
        method: "POST", 
        data: {'bill_no' : billNo,'id_branch':$('#branch_select').val()},
        success: function (data) { 
            if(data.length>0)
            {
        	$('#cus_name').val(data[0].cus_name);
        	$('#cus_id').val(data[0].id_customer);
        	var subproduct_required=$('#subproduct_required').val(); 
			$('#bill_items_tbl_for_return tbody').empty();
					$.each(data, function (estkey, estval) {
						var row = '<tr>'
									+'<td>'+(estval.status == 2 ? '<span style="color:red">Returned</span>':'<input type="checkbox" class="select_est_details" value="1" />')+'<input type="hidden" class="bill_id" value="'+estval.bill_id+'" /><input type="hidden" class="bill_det_id" value="'+estval.bill_det_id+'" /><input type="hidden" class="bill_det_id" value="'+estval.bill_det_id+'" /><input type="hidden" class="est_id" value="'+estval.esti_id+'" /><input type="hidden" class="est_itm_id" value="'+estval.esti_item_id+'" /><input type="hidden" class="category_name" value="'+estval.category_name+'"><input type="hidden" class="cat_id" value="'+estval.cat_id+'"><input type="hidden" class="collection_name" value="'+estval.collection_name+'" /><input type="hidden" class="id_collection" value="'+estval.id_collection+'" /><input type="hidden" class="collection_name" value="'+estval.collection_name+'"></td>'
									+'<td><span class="est_product_name">'+(subproduct_required==1 ? estval.parent_prods_name+'-'+estval.product_name :estval.product_name)+'</span><input class="est_product_id" type="hidden" value="'+estval.product_id+'" /></td>'
									+'<td><span class="est_design_code">'+estval.design_name+'</span><input type="hidden" class="est_design_id" value="'+estval.design_id+'"  /></td>'
									+'<td><span class="est_piece">'+estval.piece+'</span><input type="hidden" class="est_pcs" value="'+estval.piece+'"  /></td>'
									+'<td><span class="est_purname">'+estval.pur_name+'</span><input type="hidden" class="est_purid" value="'+estval.id_purity+'"  /></td>'
									+'<td><span class="est_size">'+estval.size+'</span><input type="hidden" class="est_size_val" value="'+estval.size+'"  /></td>'
									+'<td><span class="est_gross_wt">'+estval.gross_wt+'</span><input type="hidden" class="est_gross_val" value="'+estval.gross_wt+'"  /></td>'
									+'<td><span class="est_less_wt">'+estval.less_wt+'</span><input type="hidden" class="est_less_val" value="'+estval.less_wt+'"  /></td>'
									+'<td><span class="est_net_wt">'+estval.net_wt+'</span><input type="hidden" class="est_net_val" value="'+estval.net_wt+'"  /></td>'
									+'<td><span class="est_item_cost">'+estval.item_cost+'</span><input type="hidden" class="est_material_price" value="'+estval.othermat_amount+'"  /><input type="hidden" class="est_stone_price" value="'+estval.stone_price+'"  /><input type="hidden" class="est_item_cost_val" value="'+estval.item_cost+'"  /></td>'
									+'</tr>';
						$('#bill_items_tbl_for_return tbody').append(row);
					});
					$('#bill_items_for_return').show();
					$('#BillModal').modal('show');
            }else{
                 $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'No Recoed Found'});
            }
        }
     });
}

$('#update_bill_details').on('click', function(){
	$('#bill_items_tbl_for_return > tbody tr').each(function(idx, row){
		sold_items_row = $(this);
		var collections_required=$('#collections_required').val();
		var subproduct_required=$('#subproduct_required').val(); 
		var cus_due_date=$('#cus_due_date').val();
		var smith_due_date=$('#smith_due_date').val();
		var smith_rem_date=$('#smith_remainder_date').val();	
		var rowExist = false;	
		var html = '';
		if(sold_items_row.find('td:first .select_est_details').is(':checked') )
		{
			$('#repair_item_detail > tbody tr').each(function(bidx, brow)
			{
				return_items_row = $(this);
				if(sold_items_row.find('.bill_det_id').val() == return_items_row.find('.bill_det_id').val())
				{
					rowExist = true;
				}
			});
			if(!rowExist)
			{
				if(sold_items_row.find('.select_est_details').is(':checked'))
				{
					var a = $("#i_increment").val();
					var i = ++a;
					$("#i_increment").val(i); 
					html+="<tr id='detail"+i+"' class='"+i+"'>"+
					"<td>"+sold_items_row.find('.category_name').val()+"<input type='hidden' name='o_item["+i+"][id_category]' id='id_category"+i+"' value="+sold_items_row.find('.cat_id').val()+" required='true'/><input type='hidden' class='bill_det_id' name='o_item["+i+"][bill_det_id]' id='bill_det_id"+i+"' value="+sold_items_row.find('.bill_det_id').val()+" /><input type='hidden' name='o_item["+i+"][orter_type]' id='ortertype"+i+"' value='3' required='true'/></td>"+
				     (collections_required==1 ? "<td>"+sold_items_row.find('.collection_name').val()+"</td>":'')+
					"<td>"+sold_items_row.find('.est_purname').html()+" <input type='hidden' class='id_purity' name='o_item["+i+"][id_purity]' id='id_purity"+i+"' value="+sold_items_row.find('.est_purid').val()+" /></td>"+
					"<td>"+sold_items_row.find('.est_product_name').html()+"<input type='hidden' id='o_item_id_prod_"+i+"' name='o_item["+i+"][id_product]'class='id_product' required='true' value="+sold_items_row.find('.est_product_id').val()+" /></td>"+
					"<td>"+sold_items_row.find('.est_design_code').html()+"<input type='hidden' id='o_item_id_dsgn_"+i+"' name='o_item["+i+"][design_no]' class='id_design' value="+sold_items_row.find('.est_design_id').val()+" /></td>"+
					"<td><input type='number' class='form-control weight' placeholder='Enter Net Weight'  required id='weight_"+i+"' autocomplete='off'   name='o_item["+i+"][weight]' value="+sold_items_row.find('.est_net_val').val()+" /></td>"+
					"<td><input type='number' class='form-control qty' placeholder='Pcs' name='o_item["+i+"][totalitems]' value='1' required='true'/></td>"+
					'<td><a href="#" onClick="update_image_upload($(this).closest(\'tr\'));" class="btn btn-default btn-sm"><i class="fa fa-plus"></i></a><input type="hidden" class="order_img" name="o_item['+i+'][order_img]""></td>'+
					'<td><a href="#" onClick="update_order_description($(this).closest(\'tr\'));" class="btn btn-default btn-sm"><i class="fa fa-plus"></i></a><input type="hidden" class="order_des" name="o_item['+i+'][description]"></td>'+
					"<td><input class='form-control datemask date cus_due_dt' data-date-format='dd-mm-yyyy' name='o_item["+i+"][cus_due_date]' value="+cus_due_date+" type='text' required='true' placeholder='Cus Due Date' readonly />"+
					"<td><input class='form-control datemask date smith_due_dt' data-date-format='dd-mm-yyyy' name='o_item["+i+"][smith_due_date]' type='text' value="+smith_due_date+" required='true' placeholder='Smith Due Date' readonly style='width: 100px;'/></td>"+
					"<td><input class='form-control datemask date smith_rem_dt' data-date-format='dd-mm-yyyy' name='o_item["+i+"][smith_remainder_date]' type='text' value="+smith_rem_date+" required='true' placeholder='Smith Reminder Date'   readonly style='width: 100px;'/>"+
					"</td>"+
					'<td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'
					"</tr>";  
				}
				
			}
			$('#repair_item_detail tbody').append(html);
		}
	});
	$('#BillModal').modal('toggle');
});

	
	
	$('#ed_sel_village').on('change',function(){
		if(this.value!='')
		{
			$('#ed_id_village').val(this.value);
		}
		else
		{
			$('#ed_id_village').val('');
		}
	});
	
	$('#sel_village').on('change',function(){
		if(this.value!='')
		{
			$('#id_village').val(this.value);
		}
		else
		{
			$('#id_village').val('');
		}
	});
	
	
	
	$('#ed_cus_mobile').on('keyup',function(){
	   if(this.value.length>10)
	   {
	       $('#ed_cus_mobile').val('');
	       $('#ed_cus_mobile').focus();
	   }
	   else{
	        $('#ed_cus_mobile').prop('disabled',false);
	   }
	});
	
	$("#update_cutomer").on('click', function(){
		if($('#ed_cus_first_name').val() != "")
		{
			$(".ed_cus_first_name").html("");
			if($('#ed_cus_mobile').val() != "")
			{
				$(".ed_cus_mobile").html("");
				var cus_type= $("input[name='ed_cus[cus_type]']:checked").val();
					update_cutomer($('#ed_cus_first_name').val(),$('#ed_cus_mobile').val(),$('#ed_id_village').val(),cus_type);
					$('#ed_cus_first_name').val('');
					$('#ed_cus_mobile').val('');
			}else{
				$(".ed_cus_mobile").html("Please enter customer mobile");
			}
		}else{
			$(".ed_cus_first_name").html("Please enter customer first name");
		}
	});
	
	function update_cutomer(cus_name, cus_mobile,id_village,cus_type)
	{ //, cus_address
	console.log(cus_type);
	my_Date = new Date();
	$.ajax({
        url: base_url+'index.php/admin_ret_estimation/updateCustomer/?nocache=' + my_Date.getUTCSeconds(),             
        dataType: "json", 
        method: "POST", 
        data: {'id_customer':$("#cus_id").val(),'cusName': cus_name, 'cusMobile' : cus_mobile, 'cusBranch' : 1,'id_village':id_village,'cus_type':cus_type}, //Need to update login branch id here from session
        success: function (data) { 
			if(data.success == true){
				$('#confirm-edit').modal('toggle');
				$("#est_cus_name").val(data.response.firstname + " - " + data.response.mobile);
				$("#cus_id").val(data.response.id_customer);
			}else{
				alert(data.message);
			}
        }
     });
}

$('#edit_customer').on('click',function(){
   if($('#cus_id').val()!='' && $('#cus_id').val()!=undefined)
   {
       get_customer();
   }
});

function get_customer()
{
    	
   my_Date = new Date();
	$.ajax({
		type:"POST",
		url: base_url+"index.php/admin_ret_estimation/get_customer?nocache=" + my_Date.getUTCSeconds(),
		cache:false,		
		dataType:"JSON",
		data:{'id_customer' : $('#cus_id').val()},
		success:function(data){
			console.log(data);
			$('#ed_id_village').val(data.id_village);
			$('#ed_cus_first_name').val(data.firstname);
			$('#ed_cus_mobile').val(data.mobile);
			if(data.cus_type==1)
			{
			    $('#ed_cus_type1').attr('checked', true);
			}else{
			    $('#ed_cus_type2').attr('checked', true);
			}
			
		    get_village_list();
		    $('#confirm-edit').modal('show');
		}
	});
}



//Image Upload
function update_image_upload(curRow,id)
{
	$('#uploadArea_p_stn').empty();
	if(curRow!=undefined)
	{
		var preview = 'uploadArea_p_stn';
		var order_img=curRow.find('.order_img').val();
		if(order_img!='')
		{
			var img_details=JSON.parse(order_img);
			$.each(img_details,function(key,item){
			   if(item)
			   {  
			   		var div = document.createElement("div");
					div.setAttribute('class','col-md-4'); 
					div.setAttribute('id',+key); 
					param = {"key":key,"preview":preview,"stone_type":"order_images"};
					div.innerHTML+= "<a onclick='remove_stn_img("+JSON.stringify(param)+")'><i class='fa fa-trash'></i>Delete</a><img class='thumbnail' src='" + item.src + "'" +
					"style='width: 100px;height: 100px;'/>";  
					$('#'+preview).append(div);
			   }
			   $('#lot_img_upload').css('display','');
			});
		}		
		
		
		$('#cus_i_increment').val(curRow.closest('tr').attr('class'));
	}
	$('#imageModal').modal('show');
}
$("#order_images").on('change',function(){ 
		if(this.value!='')
		{
			validateCertifImg(this.id);		
		} 
	});
function validateCertifImg(type)
 {
 	if(type == 'order_images'){
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
					if(type == 'order_images')
					{ 
						pre_img_resource.push({'src':event.target.result,'name':fileName});
						pre_img_files.push(file); 
					}					
				}
				if (file)
				{
					reader.readAsDataURL(file);
				}
				
			}
	 	}
    } 
	setTimeout(function(){
		var resource = [];	
		$('#'+preview+' div').remove();	
		if(type == 'order_images'){  
			resource = pre_img_resource;
		}
			console.log(resource); 

		$.each(resource,function(key,item){
		   if(item)
		   {  
		   		var div = document.createElement("div");
				div.setAttribute('class','col-md-4'); 
				div.setAttribute('id',+key); 
				param = {"key":key,"preview":preview,"stone_type":type};
				div.innerHTML+= "<a onclick='remove_stn_img("+JSON.stringify(param)+")'><i class='fa fa-trash'></i>Delete</a><img class='thumbnail' src='" + item.src + "'" +
				"style='width: 100px;height: 100px;'/>";  
				$('#'+preview).append(div);
		   }
		   $('#lot_img_upload').css('display','');
		});
	},1000);
	pre_img_resource=[]; 
}


 function remove_stn_img(param)
 {
 		console.log(param);
 		$('#'+param.preview+' #'+param.key).remove();
 		if(param.stone_type == 'order_images')
 		{  
			pre_img_resource.splice(param.key,1);
			console.log(pre_img_resource);
		}
 }
 $('#imageModal  #update_img').on('click', function(){
	$('#imageModal').modal('toggle');
	var curRow=$("#cus_i_increment").val();
	$('.'+curRow).find('.order_img').val(JSON.stringify(pre_img_resource));
});


function update_order_description(curRow,id)
{
	$('#cus_i_increment').val(curRow.closest('tr').attr('class'));
	$('#description').val('');
	$('#order_des').modal('show');
}


$(document).on('click',".add_order_desc", function(){ 
	var curRow=$("#cus_i_increment").val();
	var content=CKEDITOR.instances.description.getData();
	$('.'+curRow).find('.order_des').val(content);
	$('#order_des').modal('toggle');
});


function getSearchSubDesign(searchTxt,inputId,curRow){
	var str = inputId.split("_");  // Sample : prod_1 => split and pass the id
    var prod_id = $("#o_item_id_prod_"+str[3]).val();
	var design_id = $("#o_item_id_dsgn_"+str[3]).val();
	console.log(str);
	my_Date = new Date();
	$.ajax({
        url: base_url+'index.php/admin_ret_order/get_ActiveSubDesingns/?nocache=' + my_Date.getUTCSeconds(),             
        dataType: "json", 
        method: "POST", 
        data: {'searchTxt':searchTxt, 'product_id':prod_id,'design_no':design_id}, 
        success: function (data) { 
			$( ".sub_design" ).autocomplete(
			{
				source: data,
				select: function(e, i)
				{ 
					e.preventDefault();
					console.log(inputId);
					$("#o_item_"+inputId).val(i.item.value); 
					$("#"+inputId ).val(i.item.label); 
				},
				response: function(e, i) {
		            // ui.content is the array that's about to be sent to the response callback.
		            /*console.log(i);
		            if (i.content.length === 0) {
		               $("#prodAlert").html('<p style="color:red">Enter a valid Design</p>');
		               $('#lt_product').val('');
		            }else{
						$("#prodAlert").html('');
					} */
		        },
				 minLength: 0,
			});
        }
     });
}


$('#stock_issue_submit').on('click',function(){
    var ordertype = $('#issue_type').val();  
    var allow_submit=true;
   if($('#branch_select').val()=='' || $('#branch_select').val()==null)
   {
       $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select Branch..'});
       allow_submit=false;
   }
   else if(ordertype==1)// REPAIR
   {
       var repair_type = $('#repair_type').val();  
       if(repair_type==1)
       {
           if($('#stockrepair_item_detail > tbody  > tr').length==0)
           {
                $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'No Records to Update..'});
                allow_submit=false;
           }
       }
       else if(repair_type==2)
       {
           if($('#custrepair_item_detail > tbody  > tr').length==0)
           {
                $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'No Records to Update..'});
                allow_submit=false;
           }
       }
   }
   else if(ordertype==2 || ordertype==3)
   {
       if($('#issue_employee').val()=='' || $('#issue_employee').val()==null)
       {
           $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select Employee..'});
           allow_submit=false;
       }
       else if($('#tagissue_item_detail > tbody  > tr').length==0)
       {
            $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'No Records to Update..'});
            allow_submit=false;
       }
   }
   if(allow_submit)
   {
        var form_data=$('#stock_issue_form').serialize();
		$('#stock_issue_submit').prop('disabled',true);
		var url=base_url+ "index.php/admin_ret_order/stock_issue/save?nocache=" + my_Date.getUTCSeconds();
	    $.ajax({ 
	        url:url,
	        data: form_data,
	        type:"POST",
	        dataType:"JSON",
	        success:function(data){
				if(data.status)
				{
				    $("div.overlay").css("display", "none"); 
				    $.toaster({ priority : 'success', title : 'Warning!', message : ''+"</br>"+data.message});
				    window.open( base_url+'index.php/admin_ret_order/stock_issue/issue_print/'+data['id_stock_issue'],'_blank');
				    location.href=base_url+'index.php/admin_ret_order/stock_issue/list';
				}
				else
				{
				    window.location.reload();
				    $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+data.message});
				    $("div.overlay").css("display", "none"); 
				}
				
	        },
	        error:function(error)  
	        {	
	        $("div.overlay").css("display", "none"); 
	        } 
	    });
   }
       
});



function set_stock_issue_list()
{
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_order/stock_issue?nocache=" + my_Date.getUTCSeconds(),
			 data:{},
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){	 
				 var list 	= data.list;
				 var access		= data.access;	
				 $('#total_count').text(list.length);
		
			 	var oTable = $('#issue_list').DataTable();
				 oTable.clear().draw();
				  
				 if (list!= null && list.length > 0)
				 {  	
					oTable = $('#issue_list').dataTable({
						"bDestroy": true,
		                "bInfo": true,
		                "bFilter": true,
		                "scrollX":'100%',
		                "bSort": true,
		                "dom": 'lBfrtip',
		                "order": [[ 0, "desc" ]],
		                "buttons" : ['excel','print'],
				        "tableTools": { "buttons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },
						"aaData": list,
						"aoColumns": [	{ "mDataProp": "id_stock_issue" },
										{ "mDataProp": "issue_no" },
										{ "mDataProp": function ( row, type, val, meta ) {
                                            if(row.status==0 || row.status==2)
                                            {
                                                return "<span class='label bg-red'>"+row.issue_status+"</span>";
                                            }else if(row.status==1)
                                            {
                                                return "<span class='label bg-orange'>"+row.issue_status+"</span>";
                                            }
                                            else if(row.status==3)
                                            {
                                                return "<span class='label bg-green'>"+row.issue_status+"</span>";
                                            }
                                        }},
										{ "mDataProp": "branch_name" },
										{ "mDataProp": "issue_date" },
										{ "mDataProp": "issue_type" },  
										{ "mDataProp": "emp_name" }, 
										{ "mDataProp": "order_no" }, 
										{ "mDataProp": "repair_type" }, 
										{ "mDataProp": function ( row, type, val, meta ) {
                                            id= row.id_stock_issue;
                                            print_url=base_url+'index.php/admin_ret_order/stock_issue/issue_print/'+id;
                                            action_content='<a href="'+print_url+'" target="_blank" class="btn btn-info btn-print" data-toggle="tooltip" ><i class="fa fa-print" ></i></a>';
                                            return action_content;
                                            }
                                        }
										
									 ]
						});			  	 	
					}
			  },
			  error:function(error)  
			  {
				 $("div.overlay").css("display", "none"); 
			  }	 
	      });
}


$('#repair_tag_search').on('click',function(){
    var tag_code=$('#repair_tag_code').val();
    tag_search=true;
     $('#stockrepair_item_detail > tbody tr').each(function(idx, row){
         curRow = $(this);
         if(curRow.find('.tag_code').val()==tag_code)
         {
             $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Tag Already Exists.'});
             tag_search=false;
             return false;
         }
     });
     if(tag_search)
     {
          get_tag_details(tag_code);
     }
   
});




$("input[name='order[issue_receipt_type]']:radio").change(function()
    {
        $('#stockrepair_item_detail > tbody').empty();
        $('#tagissue_item_detail > tbody').empty();
        var ordertype = $("input[name='order[issue_receipt_type]']:checked").val();  
        if(ordertype == 1){
            $('.type_issue').css("display", "block");
            $('.type_receipt').css("display", "none");
        }else{
            $('.type_issue').css("display", "none");
            $('.type_receipt').css("display", "block");
        }
    });
    
    
function get_StockIssueItems()
{
        $('#select_issue_no option').remove();
		my_Date = new Date();
		$.ajax({ 
		url:base_url+ "index.php/admin_ret_order/get_StockIssueItems?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),
        type:"GET",
        dataType:"JSON",
        success:function(data)
        {
        	var id=$('#select_issue_no').val();
            $.each(data, function (key, item) {					  				  			   		
                $("#select_issue_no").append(						
                $("<option></option>")						
                .attr("value", item.id_village)						  						  
                .text(item.village_name)						  					
                );			   											
            });	
            
             	$("#select_issue_no").select2({			    
            	 	placeholder: "Select Issue No",			    
            	 	allowClear: true		    
             	});	
             	
             	if(id!='' && id!=null && id!=undefined)
             	{
             	    $("#select_issue_no").select2("val",(id_village!='' && id_village>0?id_village:''));
             	}
	 
         	    $(".overlay").css("display", "none");	
        },
        error:function(error)  
        {	
        } 
    	});
}



//Repair  Order

$("input[name='order[order_type]']:radio").change(function()
{
    $('#stockrepair_item_detail > tbody').empty();
    $('#cusrepair_item_detail > tbody').empty();
    var ordertype = $("input[name='order[order_type]']:checked").val();  
    if(ordertype == 3){
        $('.cus_repair').css("display", "block");
        $('.stock_repair').css("display", "none");
    }else{
        $('.cus_repair').css("display", "none");
        $('.stock_repair').css("display", "block");
    }
});


$('#add_new_customer_repair').on('click',function(e){
	   $('#confirm-add').modal('toggle');
		get_village_list();
		$("#myModalLabel").text('Add Customer');
		
		$("#add_newcutomer").text('Add');
		$("#cus_first_name").val('');
		$("#cus_mobile").val('');
		$("#id_village").val('');	
		$("#sel_village option").remove();	
		$("#id_customer").val('');	
		$('#id_country').val('');
		$('#id_state').val('');
		$('#state option').remove();
		$('#id_city').val('');
		$('#city option').remove();
		$("#address1").val('');
		$("#address2").val('');
		$("#address3").val('');
		$("#pincode").val('');
		$("#cus_email").val('');
		
		 $("#country").select2({
                            placeholder: "Enter Country",
                            allowClear: true
                         });	
            
				 	    $("#state").select2({
                            placeholder: "Enter State",
                            allowClear: true
                        });	
                        
                        $("#city").select2({
                            placeholder: "Enter City",
                            allowClear: true
                        });	
           get_country();
   });
   
   function get_country()
        {
            $('#country option').remove();
            $.ajax({
                type: 'GET',
                url:  base_url+'index.php/settings/company/getcountry',
                dataType: 'json',
                success: function(country) {
                    var id_country=$('#id_country').val();
                    $.each(country, function (key, country) 
                    {
                        $('#country').append(
                        $("<option></option>")
                        .attr("value", country.id)
                        .text(country.name)
                        );
                    });
                    
                    $("#country").select2({
                    placeholder: "Enter Country",
                    allowClear: true
                    });	
        	
                    $("#country").select2("val", (id_country!=null && id_country!=''? id_country:''));
                },
                error:function(error)  
                {
                
                 }
            });
        }
	$('#country').on('change',function(){
            if(this.value)
            {
                get_state(this.value);
            }
        });
        
        $('#state').on('change',function(){
             if(this.value)
             {
                 get_city(this.value);
             }
            
        });
        
        
        function get_state(id)
        {
            $('#state option').remove();
            $.ajax({
                type: 'POST',
                data:{'id_country':id },
                url:  base_url+'index.php/settings/company/getstate',
                dataType: 'json',
                success: function(state) {
                var id_state=$('#id_state').val();
                $.each(state, function (key, state) {
                    $('#state').append(
                    $("<option></option>")
                    .attr("value", state.id)
                    .text(state.name)
                    );
                });
                
                 $("#state").select2({
                    placeholder: "Enter State",
                    allowClear: true
                });	
                    
                $("#state").select2("val", (id_state!=null && id_state!=''? id_state:''));
                },
                error:function(error)  
                {
        
                }
            });
        }
        
        
        function get_city(id)
        {  
            $('#city option').remove();
            $.ajax({
                type: 'POST',
                data:{'id_state':id },
                url:  base_url+'index.php/settings/company/getcity',
                dataType: 'json',
                success: function(city) {
                var id_city=$('#id_city').val();
                $.each(city, function (key, city) {
                    $('#city').append(
                    $("<option></option>")
                    .attr("value", city.id)
                    .text(city.name)
                    );
                });
                $("#city").select2("val", (id_city!=null? id_city :''));
                },
                error:function(error)  
                {
                    
                }
            });
    }	
	
	
	$('#add_newcutomer_repair').click(function(event) {
	
	if($('#cus_first_name').val() == '')
	{
		$.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Enter the Firstname..'});
		return false;
	}
	else if($('#cus_mobile').val() == '' || $('#cus_mobile').val() == null)
	{
		$.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Enter the Mobile Number..'});
		return false;
	}else if($('#country').val() == '' || $('#country').val() == null)
	{
		$.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Select the Country..'});
		return false;
	}
	else if($('#state').val() == '' || $('#state').val() == null)
	{
		$.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Select the State..'});
		return false;
	}else if($('#city').val() == '' || $('#city').val() == null)
	{
		$.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Select the City..'});
		return false;
	}else if($('#address1').val() == '')
	{
		$.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Enter the Address..'});
		return false;
	}else if($('#pin_code_add').val() == '')
	{
		$.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Enter the Pincode..'});
		return false;
	}
		
		add_cutomer_repair($('#cus_first_name').val(),$('#cus_mobile').val(),$('#id_village').val(),$('#cus_type:checked').val(),$('#gst_no').val());
					$('#cus_first_name').val('');
					$('#cus_mobile').val('');
});
function add_cutomer_repair(cus_name, cus_mobile,id_village,cus_type,gst_no){ //, cus_address
	my_Date = new Date();
	$.ajax({
        url: base_url+'index.php/admin_ret_billing/createNewCustomer/?nocache=' + my_Date.getUTCSeconds(),             
        dataType: "json", 
        method: "POST", 
        data: {'cusName': cus_name, 'cusMobile' : cus_mobile, 'cusBranch' : $('#id_branch').val(),'id_village':id_village,'cus_type':cus_type,'gst_no':gst_no,'id_country':$('#country').val(),'id_state':$('#state').val(),'id_city':$('#city').val(),'address1':$('#address1').val(),'address2':$('#address2').val(),'address3':$('#address3').val(),'pincode':$('#pin_code_add').val(),'mail':$('#cus_email').val()}, //Need to update login branch id here from session
        success: function (data) { 
			if(data.success == true){
				$('#confirm-add').modal('toggle');
				$("#cus_name").val(data.response.firstname + " - " + data.response.mobile);
				$("#cus_id").val(data.response.id_customer);
				// Loyalty module
			
				$.toaster({ priority : 'success', title : 'Success!', message : ''+"</br>"+'Customer Created SuccessFully.'});
				
				// ./Loyalty module
			}else{
				$.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+data.message});
			}
        }
     });
}

function create_new_empty_cus_repair_order_row()
{
	var trHtml='';
	trHtml+='<tr>'
	        +'<td><input type="text" class="form-control cus_product" value="" name="order_item[prod_name][]" autocomplete="off"><input type="hidden" class="form-control id_product" name="order_item[id_product][]"></td>'
	        +'<td><input type="number" class="form-control weight" value="" name="order_item[weight][]"></td>'
	        +'<td><input type="number" class="form-control pcs" name="order_item[piece][]"></td>'
	        +'<td><input type="number" class="form-control cus_due_days" name="order_item[cus_due_days][]"></td>'
	        +'<td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'
	        +'</tr>';
	  if($('#custrepair_item_detail > tbody  > tr').length>0)
    	{
    	    $('#custrepair_item_detail > tbody > tr:first').before(trHtml);
    	}else{
    	    $('#custrepair_item_detail tbody').append(trHtml);
    	}
	
}

$(document).on('keyup', ".cus_product", function(e) {  
    curRow = $(this).closest('tr'); 
    var product = curRow.find(".cus_product").val();
    getSearchProducts(product,curRow);
});


function getSearchProducts(searchTxt, curRow){
    if(searchTxt.length>=3)
    {
	my_Date = new Date();
	$.ajax({
        url: base_url+'index.php/admin_ret_estimation/getProductBySearch/?nocache=' + my_Date.getUTCSeconds(),             
        dataType: "json", 
        method: "POST", 
        data: {'searchTxt': searchTxt,'is_non_tag':''}, 
        success: function (data) {
			$(".cus_product").autocomplete(
			{
				source: data,
				select: function(e, i)
				{ 
					e.preventDefault();
					curRow.find('.cus_product').val(i.item.label);
					curRow.find('.id_product').val(i.item.value);
					console.log(curRow.find('.cus_product').val());
				},
				change: function (event, ui) {
					
				
			    },
				response: function(e, i) {
		            // ui.content is the array that's about to be sent to the response callback.
		            if(searchTxt != "")
		            {
						if (i.content.length !== 0) 
						{
						   console.log("content : ", i.content);
						}
					}else{
						curRow.find('.cus_product').val("");
						curRow.find('.id_product').val("");
					}
		        },
				 minLength: 3,
			});
        }
     });
    }
}

$('#repaid_order_items').on('click',function(){
    if(validateRepairOrderDetailRow())
    {
        $('#create_order').prop('disabled',false);
        var repairtype = $("input[name='order[order_type]']:checked").val();  
        if(repairtype == 4){
            create_new_empty_repair_order_row();
        }else{
            create_new_empty_cus_repair_order_row();    
        }
        
    }else{
     	  $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Fill The Required FIelds..'});
         $('#create_order').prop('disabled',true);
     }
});





function validateRepairOrderDetailRow()
{
    var validate = true;
    var repairtype = $('#repair_type').val();
    if(repairtype == 4){
        $('#stockrepair_item_detail > tbody  > tr').each(function(index, tr) {
    		if($(this).find('.tag_code').val() == "" || $(this).find('.pro_select').val() == "" || $(this).find('.qty').val() == "" || $(this).find('.grsweight').val() == ""  ){
    			validate = false;
    		}
    	});
    }else{
    	$('#custrepair_item_detail > tbody  > tr').each(function(index, tr) {
    		if($(this).find('.id_product').val() == "" || $(this).find('.pcs').val() == "" || $(this).find('.weight').val() == ""  ){
    			validate = false;
    		}
    	});
    }
	return validate;
}


$('#create_repair_order').on('click',function(){
    if(validateRepairOrderDetailRow())
    {
        $("div.overlay").css("display", "block"); 
		$('#create_repair_order').prop('disabled',true);
		var form_data=$('#order_submit').serialize();
			var url=base_url+ "index.php/admin_ret_order/repair_order/save?nocache=" + my_Date.getUTCSeconds();
		    $.ajax({ 
		        url:url,
		        data: form_data,
		        type:"POST",
		        dataType:"JSON",
		        success:function(data){
		           if(data.status)
		           {
		               window.location.replace(base_url+'index.php/admin_ret_order/repair_order/list');
		           }
		           else
		           {
		               
		           }
		             
					$("div.overlay").css("display", "none"); 
		        },
		        error:function(error)  
		        {	
		            $("div.overlay").css("display", "none"); 
		        } 
		    });
		$('#create_repair_order').prop('disabled',false);
    }
});



function set_repair_order_list()
{
     $("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_order/repair_order/ajax?nocache=" + my_Date.getUTCSeconds(),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){	 
				var list=data.orders;
			 	var oTable = $('#repair_order_list').DataTable();
				oTable.clear().draw();
				  
				 if (list!= null && list.length > 0)
				 {  	
					oTable = $('#repair_order_list').dataTable({
						"bDestroy": true,
		                "bInfo": true,
		                "bFilter": true,
		                "scrollX":'100%',
		                "bSort": true,
		                "dom": 'lBfrtip',
		                "order": [[ 0, "desc" ]],
						"aaData": list,
						"aoColumns": [	
										{ "mDataProp": "id_customerorder" },
									    { "mDataProp": "order_no" },
									    { "mDataProp": "branch_name" },
									    { "mDataProp": "order_date" },
									    { "mDataProp": "cus_name" },
									    { "mDataProp": "order_pcs" },
									    { "mDataProp": "order_approx_wt" },
									    {"mDataProp": function (row,type,val,meta){
											id=row.id_customerorder;
											order_no=row.order_no;
											detailed_url=base_url+"index.php/admin_ret_order/repair_order_acknowladgement/"+id;
											action_content='<a href="'+detailed_url+'" target="_blank" class="btn btn-primary btn-print" data-toggle="tooltip" title="Detailed Print"><i class="fa fa-print" ></i></a>';
											return action_content;
										}}
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
}


$(document).on('keyup','.completed_weight', function(e){
		var row = $(this).closest('tr'); 
		if(!(row.find('.id_orderdetails').is(':checked')))
		{
			row.find('.id_orderdetails').prop('checked',true);
		}
});

$("#repair_order_status").on('click',function()
{
	 $("div.overlay").css("display", "block"); 
	if($("input[name='id_orderdetails[]']:checked").val())
	{
		var selected = [];
		var approve=false;
		$("#repair_order_list tbody tr").each(function(index, value){
			if($(value).find("input[name='id_orderdetails[]']:checked").is(":checked"))
			{
				transData = {
				 'id_orderdetails'   : $(value).find(".id_orderdetails").val(),
				 'completed_weight'  : $(value).find(".completed_weight").val(),
				 'final_amount'  	 : $(value).find(".final_amount").val(),
				}
				selected.push(transData);	
			}
			
		});
		req_data = selected;
		update_repair_order_status(req_data);
	}
	else
	{
		 $("div.overlay").css("display", "none"); 
		 $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select Order'});
	}
});

function update_repair_order_status(req_data)
{
	my_Date = new Date();
	$.ajax({
	url: base_url+'index.php/admin_ret_order/repair_order_status?nocache=' + my_Date.getUTCSeconds(),             
	method: "post", 
	async:false,
	data: ( {'req_data':req_data}),
	success: function (data)
	{
			window.location.reload()
	}
	});
}

function setRepairOrderStatus()
{
     $("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_order/order/repair_order_list?nocache=" + my_Date.getUTCSeconds(),
			 dataType:"JSON",
			 type:"POST",
			 data:{'id_branch':$('#branch_filter').val()},
			 success:function(data){	 
				
			 	var oTable = $('#repair_order_list').DataTable();
				oTable.clear().draw();
				  
				 if (data!= null && data.length > 0)
				 {  	
					oTable = $('#repair_order_list').dataTable({
						"bDestroy": true,
		                "bInfo": true,
		                "bFilter": true,
		                "scrollX":'100%',
		                "bSort": true,
		                "dom": 'lBfrtip',
		                "order": [[ 0, "desc" ]],
		                "buttons" : ['excel','print'],
				        "tableTools": { "buttons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },
						"aaData": data,
						"aoColumns": [	
										{ "mDataProp": function ( row, type, val, meta ){ 
											if(row.orderstatus>0 && row.orderstatus<=3)
											{
												chekbox='<input type="checkbox" class="id_orderdetails" name="id_orderdetails[]" value="'+row.id_orderdetails+'"/> ' 
												return chekbox+" "+row.id_orderdetails+'';
											}else{
												return row.id_orderdetails;
											}
										
										}},
										 { "mDataProp": "from_branch" },
										 { "mDataProp": "branch_name" },
									    { "mDataProp": "orderno" },
									   
									    { "mDataProp": "cus_name" },
									    { "mDataProp": "emp_name" },
									    { "mDataProp": "product_name" },
									    { "mDataProp": "design_name" },
										{ "mDataProp": function (row, type, val, meta) { 
										console.log(row.order_img);
										if(row.order_img != '')
										{
										var rep_order_image =  base_url+'assets/img/repair_order';
										order_image =  row.order_img.split('#');
										
										var type = rep_order_image+'/'+order_image[0];
										//return '<img src='+type+' width="60" height="65">';
										return '<img src='+type+' width="50" height="55"><br><a  class="btn btn-secondary stk_img_status"  id="edit" data-toggle="modal" data-id='+order_image+'><i class="fa fa-eye" ></i></a>';
										}else{
											
											return '-';
										}
									   },
									},
									    { "mDataProp": "weight" },
									    { "mDataProp": function ( row, type, val, meta ){ 
										        return row.completed_weight;
										}},
										{ "mDataProp": function ( row, type, val, meta ){ 
										    	    return row.amount;
										}},
										{ "mDataProp": function(row,type,val,meta)
							                {return "<span class='badge bg-"+row.color+"'>"+row.order_status+"</span>";	}
							            },
									    { "mDataProp": "order_date" },
									    { "mDataProp": "karigar_name" },
									    {"mDataProp": function (row,type,val,meta){
											action_url= base_url+"index.php/admin_ret_order/repair_acknowledgement/"+row.id_customerorder;
											return "<a href='"+action_url+"' target='_blank' class='btn btn-info btn-print' data-toggle='tooltip' title='Order Print'><i class='fa fa-print'></i></a>"  ;
											
										}}
					                	
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
}


$('#issue_tag_search').on('click',function(){
    var tag_code=$('#issue_tag_code').val();
    tag_search=true;
     $('#tagissue_item_detail > tbody tr').each(function(idx, row){
         curRow = $(this);
         console.log(curRow.find('.tag_code').val());
         console.log(tag_code);
         if(curRow.find('.tag_code').val()==tag_code)
         {
             $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Tag Already Exists.'});
             tag_search=false;
             return false;
         }
     });
     if(tag_search)
     {
          get_tag_details(tag_code);
     }
   
});


function get_tag_details(tag_code)
{
    var issue_type = $('#issue_type').val();  
    my_Date = new Date();
	$.ajax({
		type:"POST",
		url: base_url+"index.php/admin_ret_stock_issue/get_tag_scan_details?nocache=" + my_Date.getUTCSeconds(),
		cache:false,		
		dataType:"JSON",
		data:{'tag_code' : tag_code,'id_branch': $("#branch_select").val()},
		success:function(data){
		    if(data.length>0)
		    {
		        var html = "";
        	    $.each(data,function(key,items){
        	        var purewt = parseFloat((parseFloat(items.net_wt) * (parseFloat(items.purname) + parseFloat(items.wastage_percent))) / 100).toFixed(3);
    		        html+='<tr>'
    		                +'<td><input type="hidden" class="tag_id" name="order_item[tag_id][]" value="'+items.value+'"><input type="hidden" class="tag_code" name="tag_code[]" value="'+items.label+'">'+items.label+'</td>'
    		                +'<td>'+items.catname+'</td>'
    		                +'<td>'+items.purname+'</td>'
    		                +'<td><input type="hidden" class="id_product" name="order_item[id_product][]" value="'+items.lot_product+'">'+items.product_name+'</td>'
    		                +'<td><input type="hidden" class="id_design" name="order_item[id_design][]" value="'+items.design_id+'">'+items.design_name+'</td>'
    		                +'<td><input type="hidden" class="id_sub_design" name="order_item[id_sub_design][]" value="'+items.subdesignid+'">'+items.sub_design_name+'</td>'
    		                +'<td><input type="hidden" class="piece" name="order_item[piece][]" value="'+items.piece+'">'+items.piece+'</td>'
    		                +'<td><input type="hidden" class="purewt" name="order_item[purewt][]" value="'+purewt+'"><input type="hidden" class="gross_wt" name="order_item[weight][]" value="'+items.gross_wt+'">'+items.gross_wt+'</td>'
    		                +'<td>'+items.net_wt+'</td>'
    		                +'<td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'
    		               +'</tr>';
    		        });
	            if($('#tagissue_item_detail > tbody  > tr').length>0)
            	{
            	    $('#tagissue_item_detail > tbody > tr:first').before(html);
            	}else{
            	    $('#tagissue_item_detail tbody').append(html);
            	}
            	calculate_tag_issue_details();
            	$('#issue_tag_code').val('');
            	$('#issue_tag_code').focus();
		    }
		    else
		    {
		        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'No Records Found.'});
		    }
		}
	});
}


function calculate_tag_issue_details()
{
    var total_pcs=0;
    var total_gwt=0;
    $('#tagissue_item_detail > tbody tr').each(function(idx, row){
         curRow = $(this);
         total_pcs+=parseFloat(curRow.find('.piece').val());
         total_gwt+=parseFloat(curRow.find('.gross_wt').val());
    });
    
    $('.total_pieces').html(total_pcs);
    $('.total_gross_wt').html(parseFloat(total_gwt).toFixed(3));
}


//Repair  Order



//Order Cart

$(document).on('change',".totalitems", function(e){ 
		var row = $(this).closest('tr'); 
		
		var order_pcs   =row.find('.order_pcs').val();
		var max_pcs     =row.find('.max_pcs').val();
		var totalitems     =row.find('.totalitems').val();
		
		if(parseFloat(totalitems)>parseFloat(max_pcs))
		{
		    alert('Entered Pieces Are Greater than the Available Pieces..');
		    row.find('.totalitems').val(order_pcs);
		}
	});

function getSearchKarigar(searchTxt,curRow){ 
	my_Date = new Date();
	$.ajax({
        url: base_url+'index.php/admin_ret_order/karigar_search/?nocache=' + my_Date.getUTCSeconds(),             
        dataType: "json", 
        method: "POST", 
        data: {'searchTxt': searchTxt}, 
        success: function (data) { 
			$(".karigar_search").autocomplete(
			{
				source: data,
				select: function(e, i)
				{ 
					e.preventDefault();
					curRow.find(".karigar_search").val(i.item.label); 
					curRow.find(".id_karigar").val(i.item.value); 
				
				},
				change: function (event, ui) {
					if (ui.item === null) {
						curRow.find(".karigar_search").val(''); 
						curRow.find(".id_karigar").val(''); 
					}
			    },
				
				response: function(e, i) {
		            // ui.content is the array that's about to be sent to the response callback.
		            /*console.log(i);
		            if (i.content.length === 0) {
		               $("#prodAlert").html('<p style="color:red">Enter a valid Product</p>');
		               $('#lt_product').val('');
		            }else{
						$("#prodAlert").html('');
					} */
		        },
				 minLength: 0,
			});
        }
     });
}

function get_order_cart()
{
	$(".overlay").css("display", "block");
		my_Date = new Date();
		$.ajax({
			 url: base_url+'index.php/admin_ret_order/cart?nocache=' + my_Date.getUTCSeconds(),             
	        dataType: "json", 
	        method: "POST", 
	        data :{'id_product':$('#prod_select').val(),'id_design':$('#des_select').val(),'id_wt_range':$('#wt_select').val()},
	        success: function (data)
	        {
	        	get_order_cart_list(data.orders);
	        	$(".overlay").css("display", "none");
	        }
		});
}

function get_order_cart_list(order)
{
	var row='';
	$("#cart_list > tbody > tr").remove();  	
	$('#cart_list').dataTable().fnClearTable();
	$('#cart_list').dataTable().fnDestroy();
	$.each(order,function(key,item){
		row += '<tr>'
		+'<td><input type="checkbox" class="id_cart_order" name="cart[id_cart_order][]" value="'+item.id_cart_order+'"/>'+item.id_cart_order+'<input type="hidden" class="ortertype" name="cart[ortertype][]" value='+item.ortertype+' /><input type="hidden" class="smith_remainder_date" name="cart[smith_remainder_date][]" value='+item.smith_remainder_date+' /><input type="hidden" class="smith_due_date" name="cart[smith_due_date][]" value='+item.smith_due_date+' /><input type="hidden" class="id_purity" name="cart[id_purity][]" value='+item.id_purity+' /><input type="hidden" class="id_branch" name="cart[id_branch][]" value='+item.id_branch+' /></td>'
		+'<td><span>'+item.order_date+'</div><input type="hidden" class="order_date" name="cart[order_date][]" value='+item.order_date+' /></td>'
		+'<td><span>'+item.product_name+'</span><input type="hidden" class="id_product" name="cart[id_product][]" value='+item.id_product+' /></td>'
		+'<td><span>'+item.design_name+'</span><input type="hidden" class="design_no" name="cart[design_no][]" value='+item.design_no+' /></td>'
		+'<td><span>'+item.sub_design_name+'</span><input type="hidden" class="id_sub_design" name="cart[id_sub_design][]" value='+item.id_sub_design+' /></td>'
		+'<td><input type="text" class="form-control karigar_search" value="'+item.karigar_name+'" placeholder="Search Karigar"><input type="hidden" class="id_karigar" name="cart[id_karigar][]" value='+item.id_karigar+' /></td>'
		+'<td><span>'+item.weight_range+'</span><input type="hidden" class="weight_range_value" name="cart[weight_range_value][]" value='+item.weight_range_value+' /><input type="hidden" class="id_wt_range" name="cart[id_wt_range][]" value='+item.id_wt_range+' /></td>'
		+'<td><input type="number" class="form-control totalitems" name="cart[totalitems][]" value='+item.totalitems+' /><input type="hidden" class="max_pcs" value='+item.max_pcs+' /><input type="hidden" class="order_pcs" value='+item.totalitems+' /></td>'
		+'<td><input class="form-control smith_due_dt" data-date-format="dd-mm-yyyy" name=cart[smith_due_date][]" value="" type="text" placeholder="Smith Due Date" style="width: 100px;"/></td>'
		+'<td><span>'+item.size_name+'</span><input type="hidden" class="size" name="cart[size][]" value='+item.id_size+'></td>'
		+'<td><span>'+item.emp_name+'</span></td>'
		+'</tr>';
		});
	
	$('#cart_list tbody').append(row);
		if ( ! $.fn.DataTable.isDataTable( '#cart_list' ) ) 
		{ 
			oTable = $('#cart_list').dataTable({ 
			"bSort": true, 
			"bInfo": true, 
			"scrollX":'100%',  
			"dom": 'lBfrtip',
			"paging":false,
			"buttons": [
			{
				extend: 'print',
				footer: true,
				title: '',
				customize: function ( win ) {
				$(win.document.body).find( 'table' )
				.addClass( 'compact' )
				.css( 'font-size', 'inherit' );
				},
			},
			{
				extend:'excel',
				footer: true,
			}
			], 
			});
		} 
		$('.smith_due_dt').datepicker({ dateFormat: 'yyyy-mm-dd'});
}

$("input[name='order_status_btn']:radio").change(function(){
    if($('#select_karigar').val()!='' && $('#select_karigar').val()!=null)
    {
         if($("input[name='cart[id_cart_order][]']:checked").val())
         {
               $(".overlay").css("display", "block");
    			var selected = [];
    			var allow_submit=true;
    			var deleteids_arr = [];
    			$("#cart_list tbody tr").each(function(index, value)
    			{
        			if($(value).find("input:checkbox[class=id_cart_order]:checked").is(":checked"))
        			{
        			    if($(value).find(".smith_due_dt").val()!='')
        			    {
        			        transData = { 
                    			'id_cart_order' : $(value).find(".id_cart_order").val(),
                    			'id_branch'		: $(value).find(".id_branch").val(),
                    			'totalitems'    : $(value).find(".totalitems").val(),
                    			'weight_range_value' : $(value).find(".weight_range_value").val(),
                    			'id_product'    : $(value).find(".id_product").val(),
                    			'design_no'     : $(value).find(".design_no").val(),
                    			'id_sub_design' : $(value).find(".id_sub_design").val(),
                    			'id_wt_range'   : $(value).find(".id_wt_range").val(),
                    			'id_karigar'    : $(value).find(".id_karigar").val(),
                    			'size'   	    : $(value).find(".size").val(),
                    			'smith_due_dt'  : $(value).find(".smith_due_dt").val(),
                			}
                			selected.push(transData);
        			    }
            			else
            			{
            			    $(".overlay").css("display", "none");
            			    $('input[name=order_status_btn]').removeAttr('checked');
                            $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select Due Date'});
            			    allow_submit=false;
            			}
        			}
    			});
    			if(allow_submit)
    			{
    			    req_status = $("input[name='order_status_btn']:checked").val();
    			    req_data = selected;
    			    order_place(req_status,req_data);
    			}
         }
         else
         {
             $('input[name=order_status_btn]').removeAttr('checked');
             $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select Any One Item'});
         }
    }
    else
    {
        $('input[name=order_status_btn]').removeAttr('checked');
        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select Karigar'});
    }
});
    
function order_place(req_status,data)
{
        my_Date = new Date();
        $(".overlay").css("display", "block");
        $.ajax({
        url:base_url+ "index.php/admin_ret_order/cart/order_place?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),
        data:  {'status':req_status,'req_data':data},
        type:"POST",
        dataType: "json", 
        async:false,
        success:function(data){
        location.reload(true);
        $(".overlay").css("display", "none");
        },
        error:function(error)  
        {
        console.log(error);
        $(".overlay").css("display", "none");
        }	 
        });
 }

function get_ActiveProduct()
{

	$('#prod_select option').remove();
	$.ajax({
	type: 'GET',
	url: base_url+'index.php/admin_ret_reports/get_ActiveProduct',
	dataType:'json',
	success:function(data){
		var id =  $("#prod_select").val();
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
}

$('#prod_select').on('change',function(){
    if(this.value!='')
    {
        if(ctrl_page[1]=='cart')
        {
             get_Activedesign(this.value);
             get_weight_range(this.value);
        }
    }else{
    	get_order_cart();
    }
    
});

$('#des_select').on('change',function(){
    if(this.value!='')
    {
        if(ctrl_page[1]=='cart')
        {
             get_order_cart();
        }
       
    }
    else{
    	get_order_cart();
    }
});

$("#wt_select").on('change',function(){
    if(this.value!='')
    {
        get_order_cart();
    }else{
        get_order_cart();
    }
});

function get_Activedesign(id_product)
{
	 $("div.overlay").css("display", "block"); 
	$('#des_select option').remove();
	$.ajax({
	type: 'POST',
	url: base_url+'index.php/admin_ret_reports/get_Activedesign',
	dataType:'json',
	data :{'id_product':id_product},
	success:function(data){
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
		    $("#des_select").select2("val",(id!='' && id>0?id:''));
		    $("div.overlay").css("display", "none"); 
		}
	});
	 
}

function get_weight_range(id_product)
{
	$('#wt_select option').remove();
	$.ajax({
	type: 'POST',
	url: base_url+'index.php/admin_ret_reports/get_weight_range',
	dataType:'json',
	data:{'id_product':id_product},
	success:function(data){
		var id =  $("#wt_range").val();
		$.each(data, function (key, item) {   
		    $("#wt_select").append(
		    $("<option></option>")
		    .attr("value", item.id_weight)    
		    .text(item.name)  
		    );
		});
		   
		$("#wt_select").select2(
		{
			placeholder:"Weight Range",
			allowClear: true		    
		});
		    $("#wt_select").select2("val",(id!='' && id>0?id:''));
		}
	});
}


function get_purchase_order_list()
{
    $("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_order/order/purchase_order?nocache=" + my_Date.getUTCSeconds(),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			 	var list=data;
				var oTable = $('#order_list').DataTable();
				oTable.clear().draw();				  
				if (list!= null && list.length > 0)
				{  	
					oTable = $('#order_list').dataTable({
                    "bDestroy": true,
                    "bInfo": true,
                    "bFilter": true,
                    "bSort": true,
                    "order": [[ 0, "desc" ]],
                    "dom": 'lBfrtip',
                    "buttons" : ['excel','print'],
                    "tableTools": { "buttons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },
                    "aaData": list,
                    "aoColumns": [
                    { "mDataProp": "id_customerorder" },
                    { "mDataProp": "pur_no" },
                    { "mDataProp": "order_date" },
                    { "mDataProp": function ( row, type, val, meta ){
                        return '<span class="badge bg-'+row.color+'">'+row.order_status_msg+'</span>';
        			},
        			},
                    { "mDataProp": "karigar_name" },
                    { "mDataProp": "mobile" },
                    { "mDataProp": "order_pcs" },

                    
                    
                    { "mDataProp": function ( row, type, val, meta ) {
                    return parseFloat(row.delivered_qty);
                    }
                    },
                  
                    
                    { "mDataProp": function ( row, type, val, meta ) {
                    id= row.id_customerorder;
                    print_url=base_url+'index.php/admin_ret_order/get_karigar_receipt/'+id;
                    action_content='<a href="#" onclick="send_karigar_sms('+id+','+row.mobile+')"  class="btn btn-success" data-toggle="tooltip" title="Send WhatsApp / Email"><i class="fa fa-whatsapp" aria-hidden="true"></i><a href="'+print_url+'" target="_blank" class="btn btn-info btn-print" data-toggle="tooltip" title="Vendor acknowledgement "><i class="fa fa-print" ></i></a>';
                    return action_content;
                    }
                    },
                    
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
}

function get_ActiveKaigar()
{
	$.ajax({
	type: 'GET',
	url: base_url+'index.php/admin_ret_catalog/karigar/active_list',
	dataType:'json',
	success:function(data){
	    var id=$('#select_karigar').val();
		$.each(data, function (key, item) {   
		    $("#select_karigar").append(
		    $("<option></option>")
		    .attr("value", item.id_karigar)    
		    .text(item.karigar)  
		    );
		}); 
		$("#select_karigar").select2(
		{
			placeholder:"Select Karigar",
			 allowClear: true	    
		});
		
		if($("#select_karigar").length)
		{
		    $("#select_karigar").select2("val",(id!='' && id>0?id:''));
		}
		    $(".overlay").css("display", "none");
		}
	});
}


function set_cart_status()
{
    $("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_order/cart/order_status?nocache=" + my_Date.getUTCSeconds(),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			 	var list=data;
				var oTable = $('#order_list').DataTable();
				oTable.clear().draw();				  
				if (list!= null && list.length > 0)
				{  	
					oTable = $('#order_list').dataTable({
                    "bDestroy": true,
                    "bInfo": true,
                    "bFilter": true,
                    "bSort": true,
                    "order": [[ 0, "desc" ]],
                    "dom": 'lBfrtip',
                    "buttons" : ['excel','print'],
                    "tableTools": { "buttons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },
                    "aaData": list,
                    "aoColumns": [
                    { "mDataProp": "id_cart_order" },
                    { "mDataProp": "pur_no" },
                    { "mDataProp": "date_add" },
                    { "mDataProp": "product_name" },
                    { "mDataProp": "design_name" },
                    { "mDataProp": "sub_design_name" },
                    { "mDataProp": "cart_status" },
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
}



//Order Cart

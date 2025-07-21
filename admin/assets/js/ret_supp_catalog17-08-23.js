var path =  url_params();

var ctrl_page 		= path.route.split('/');

let weight_range = [];

let purities = [];

let sizes = [];

let karigars = [];

$(document).ready(function() {

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

	switch(ctrl_page[1]) {

		case 'supplier_catalog':

		switch(ctrl_page[2]) {

			case 'list':				 	

				get_supplier_catalog_list();

			break;

			case 'edit':

				get_weight();

				get_cat_purity();

				get_product_size();

				get_all_karigar();

				$.when(get_weight(), get_cat_purity(), get_product_size(), get_all_karigar()).then(update_weightRange);

			break;

		}
	}
	
});

$(document).ready(function() {

	$(document).on("keyup", "#product_name",function() {
		
		if(this.value.length > 2) {

			getSearchProd(this.value);

		}

	});

	$(document).on("keyup", "#design_name",function() {
		
		if(this.value.length > 2) {

			getSearchDesign(this.value);

		}

	});

	$(document).on("keyup", "#subdesign_name",function() {
		
		if(this.value.length > 2) {

			getSearchSubDesign(this.value);

		}

	});

	$(document).on("click", "#create_catlog_weight",function() {

		create_catlog_weight();

	});

});

function get_supplier_catalog_list() {
	my_Date = new Date();
	$("div.overlay").css("display", "block"); 
	$.ajax({
		url:base_url+"index.php/admin_ret_supp_catalog/supplier_catalog/ajax?nocache=" + my_Date.getUTCSeconds(),
		dataType:"JSON",
		type:"POST",
		success:function(data) {
			set_supplier_catalog_list(data);
			$("div.overlay").css("display", "none"); 
		},
		error:function(error)  {
			$("div.overlay").css("display", "none"); 
		}	 
	});
}

function set_supplier_catalog_list(data)	{
   	$("div.overlay").css("display", "none"); 
   	var list = data.list;
   	var access = data.access;
   	var oTable = $('#supp_cat_list').DataTable();
   	$("#total_count").text(list.length);
    if(access.add == '0')
	{
		$('#add_supp_cat').attr('disabled','disabled');
	}
	oTable.clear().draw();
	if (list!= null && list.length > 0)
	{
	 	oTable = $('#supp_cat_list').dataTable({
			"bDestroy": true,
			"bInfo": true,
			"bFilter": true, 
			"bSort": true,
			"order": [[ 0, "desc" ]],
			"dom": 'lBfrtip',
			"buttons": [
				{
				  extend: 'print',
				  footer: true,
				  title: "Supplier Catalogue List",
				},
				{
				   extend:'excel',
				   footer: true,
				   title: "Supplier Catalogue List",
				}
			],
			"aaData": list,
			"aoColumns": [{ "mDataProp": "id_supp_catalogue" },
						{ "mDataProp": "ctl_datetime" },
						{ "mDataProp": "product_name" },
						{ "mDataProp": "design_name" },
						{ "mDataProp": "sub_design_name" },
						{ "mDataProp": "design_code" },
						{ "mDataProp": "supp_cat_status" },
						{ "mDataProp": function ( row, type, val, meta ) {

							id	= row.id_supp_catalogue;

							let edit_url		=	(access.add=='1' ? base_url+'index.php/admin_ret_supp_catalog/supplier_catalog/edit/'+id : '#' );

							let delete_url		=	(access.add=='1' ? base_url+'index.php/admin_ret_supp_catalog/supplier_catalog/delete/'+id : '#' );

							action_content 	= 	'<a href="'+edit_url+'" class="btn btn-success edit_supp_cat" data-toggle="modal" ><i class="fa fa-edit"></i></a> &nbsp; <a href="#" class="btn btn-danger btn-del" data-href='+delete_url+' data-toggle="modal"  data-target="#confirm-delete"><i class="fa fa-trash"></i></a>';

							return action_content;
						}
					}] 
		});	
	}
}

function getSearchProd(searchTxt){ 
	
	my_Date = new Date();
	
	$.ajax({
    
		url: base_url+'index.php/admin_ret_estimation/getProductBySearch/?nocache=' + my_Date.getUTCSeconds(),             
    
		dataType: "json", 
    
		method: "POST", 
    
		data: {'searchTxt': searchTxt}, 
    
		success: function (data) { 
	
			$( "#product_name" ).autocomplete({

				source: data,

				select: function(e, i) {

					e.preventDefault();

					$("#product_id").val(i.item.value); 

					$("#product_name").val(i.item.label);

					$("#cat_id").val(i.item.cat_id);

					$("#design_id" ).val(""); 
				
					$("#design_name").val("");  

					$("#subdesign_id").val("");

					$("#subdesign_name").val("");

					$("#weight_details tbody").empty();

				},

				response: function(e, i) {

		        },

				minLength: 0,

			});

		}

	});
}

function getSearchDesign(searchTxt){

	var prod_id = $("#product_id").val();

	my_Date = new Date();

	$.ajax({

        url: base_url+'index.php/admin_ret_order/get_ActiveDesingns/?nocache=' + my_Date.getUTCSeconds(),             

		dataType: "json", 

		method: "POST", 

		data: {'searchTxt':searchTxt, 'product_id':prod_id}, 

		success: function (data) { 

			$( ".design_name" ).autocomplete({

				source: data,

				select: function(e, i) {

					e.preventDefault();

					$("#design_id" ).val(i.item.value); 
				
					$("#design_name").val(i.item.label);  

					$("#subdesign_id").val("");

					$("#subdesign_name").val("");

					$("#weight_details tbody").empty();

				},
				
				response: function(e, i) {
		        
				},
				
				minLength: 0,
			
			});
        }
    });
}

function getSearchSubDesign(searchTxt) {

    var prod_id = $("#product_id").val();

	var design_id = $("#design_id").val();

	my_Date = new Date();

	$.ajax({
 
		url: base_url+'index.php/admin_ret_order/get_ActiveSubDesingns/?nocache=' + my_Date.getUTCSeconds(),             
    
		dataType: "json", 
    
		method: "POST", 
    
		data: {'searchTxt':searchTxt, 'product_id':prod_id,'design_no':design_id}, 
    
		success: function (data) { 
	
			$( ".subdesign_name" ).autocomplete({
				
				source: data,
			
				select: function(e, i) {

					e.preventDefault();

					$("#subdesign_id").val(i.item.value);

					$("#subdesign_name").val(i.item.label);

					$("#weight_details tbody").empty();

					get_weight();

					get_cat_purity();

					get_product_size();

					get_all_karigar();

				},

				response: function(e, i) {

		        },

				minLength: 0,
			});

		}

	});

}

function create_catlog_weight(data = {}) {

	console.log("data",data);

	var _validate = validate_catalog_weight();

	if(_validate) {

		let rowId = 1;

		let rowCount = $("#weight_details tbody tr");

		if(rowCount.length > 0) {

			rowId = parseInt($("#weight_details tbody tr:last").find('.rowId').val()) + 1;

		}

		let weight = data.weight === undefined ? "" : data.weight;

		let purity = data.id_purity === undefined ? "" : data.id_purity;

		let purityArr = purity.split(',');

		let size = data.id_size === undefined ? "" : data.id_size;

		let sizeArr = size.split(',');

		let mc_type = data.mc_type === undefined ? 2 : data.mc_type;

		let mc_value = data.mc_value === undefined ? "" : data.mc_value;

		let display_mc = data.display_mc === undefined ? 1 : data.display_mc;

		let wastage = data.wastage === undefined ? "" : data.wastage;

		let display_va = data.display_va === undefined ? 1 : data.display_va;

		let delivery_duration = data.smith_due_date === undefined ? "" : data.smith_due_date;

		let display_duration = data.display_duration === undefined ? 1 : data.display_duration;

		let karigar = data.karigar === undefined ? "" : data.karigar;

		let karigarArr = karigar.split(',');

		let weightRow = "";

		weightRow+="<tr class='rowWeight'>"+

			"<td><select class='form-control weight' name='item[weight]["+rowId+"]' required /></td>"+

			"<td><select class='form-control purity' name='item[purity]["+rowId+"]' required multiple /><input type='hidden' name='item[id_purity]["+rowId+"]' class='id_purity' value='"+purity+"' required/></td>"+

			"<td><select class='form-control size' name='item[size]["+rowId+"]' required multiple /><input type='hidden' name='item[id_size]["+rowId+"]' class='id_size' value='"+size+"' required/></td>"+

			"<td><select class='form-control mc_type' name='item[mc_type]["+rowId+"]' required><option value='1'>Piece</option><option value='2' selected>Gram</option></select></td>"+

			"<td><input type='number' step='any' class='form-control mc_value' name='item[mc_value]["+rowId+"]' autocomplete='off' value='"+mc_value+"' required /></td>"+

			"<td><input type='radio' class='display_mc_yes' name='item[display_mc]["+rowId+"]' value='1' checked /> Yes <input type='radio' class='display_mc_no' name='item[display_mc]["+rowId+"]' value='0' /> No </td>"+

			"<td><input type='number' step='any' class='form-control wastage' name='item[wastage]["+rowId+"]' autocomplete='off' value='"+wastage+"' required /></td>"+

			"<td><input type='radio' class='display_va_yes' name='item[display_va]["+rowId+"]' value='1' checked /> Yes <input type='radio' class='display_va_no' name='item[display_va]["+rowId+"]' value='0' /> No </td>"+

			"<td><input type='number' class='form-control delivery_duration' name='item[delivery_duration]["+rowId+"]' autocomplete='off' value='"+delivery_duration+"' required /></td>"+

			"<td><input type='radio' class='display_duration_yes' name='item[display_duration]["+rowId+"]' value='1' checked /> Yes <input type='radio' class='display_duration_no' name='item[display_duration]["+rowId+"]' value='0' /> No </td>"+

			"<td><select class='form-control karigar' name='item[karigar]["+rowId+"]' required multiple /><input type='hidden' name='item[id_karigar]["+rowId+"]' class='id_karigar' value='"+karigar+"' required/></td>"+

			'<td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a><input type="hidden" class="rowId" value="'+rowId+'" required /></td>'+

			"</tr>";


			$('#weight_details tbody').append(weightRow);

		
		let curRow = $("#weight_details tbody tr:last");

		curRow.find(".mc_type").val(mc_type);

		display_mc == 1 ? curRow.find(".display_mc_yes").attr("checked",true) : curRow.find(".display_mc_no").attr("checked",true);

		display_va == 1 ? curRow.find(".display_va_yes").attr("checked",true) : curRow.find(".display_va_no").attr("checked",true);

		display_duration == 1 ? curRow.find(".display_duration_yes").attr("checked",true) : curRow.find(".display_duration_no").attr("checked",true);

		/*curRow.find(".weight").select2();
		
		curRow.find(".purity").select2();

		curRow.find(".size").select2();

		curRow.find(".karigar").select2();*/

		load_weight(curRow, weight);

		load_purity(curRow, purityArr);

		load_product_size(curRow, sizeArr);

		load_karigar(curRow, karigarArr);

	}

}

function validate_catalog_weight() {

	let product_id = $("#product_id").val();

	let design_id = $("#design_id" ).val();
		
	let subdesign_id = $("#subdesign_id").val();

	if(!(product_id > 0)) {
		
		$.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Product Required!'});
			
		return false;

	} else if(!(design_id > 0)) {

		$.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Design Required!'});
			
		return false;

	} else if(!(subdesign_id > 0)) {

		$.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Sub Design Required!'});
			
		return false;

	}

	let weight_details = $('#weight_details tbody tr');

	if(weight_details.length > 0) {

		let validateRows = true;

		$.each(weight_details, function(key,value) {

			let weight = $(value).find('.weight').val();

			let purity = $(value).find('.purity').val();

			let size = $(value).find('.size').val();

			let mc_type = $(value).find('.mc_type').val();

			let mc_value = $(value).find('.mc_value').val();

			let wastage = $(value).find('.wastage').val();

			let karigar = $(value).find('.karigar').val();

			let rowId = $(value).find('.rowId').val();

			console.log({"weight":weight,"purity":purity,"size":size,"mc_type":mc_type,"mc_value":mc_value,"wastage":wastage,"karigar":karigar,"rowId":rowId});

			console.log(size?.length);

			if(!(weight > 0)) {

				$.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Weight Required!'});

				validateRows = false;
			
				return false;

			} else if(!(purity?.length > 0)) {

				$.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Purity Required!'});

				validateRows = false;
			
				return false;

			} else if(!(size?.length > 0)) {

				$.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Size Required!'});

				validateRows = false;
			
				return false;

			} else if(!(mc_type > 0)) {

				$.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'MC Type Required!'});

				validateRows = false;
			
				return false;

			} else if(!(mc_value > 0)) {

				$.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'MC Value Required!'});

				validateRows = false;
			
				return false;

			}  else if(!(wastage > 0)) {

				$.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Wastage Required!'});

				validateRows = false;
			
				return false;

			} else if(!(karigar?.length > 0)) {

				$.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Karigar Required!'});

				validateRows = false;
			
				return false;

			} else if(!(rowId > 0)) {

				$.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Required fields are missing'});

				validateRows = false;

				return false;

			}

		});

		if(!validateRows) {

			return false;

		} else {

			return true;

		}

	} else {

		return true;

	}

}

function get_weight() {

	$(".overlay").css('display','block');

	weight_range = [];

	let id_product = $("#product_id").val();

	let id_design = $("#design_id" ).val();

	let id_sub_design = $("#subdesign_id").val();

	return $.ajax({

			type: 'POST',

			url: base_url+'index.php/admin_ret_catalog/get_weight_range_details',

			dataType:'json',

			data: {

				'id_product' : id_product,

				'id_design' : id_design,

				'id_sub_design' : id_sub_design
			},

			success:function(data){

				console.log("weight_range",data);

				weight_range = data;

				$(".overlay").css("display", "none");
			}
		});

}

function get_cat_purity() { 

	$(".overlay").css('display','block');

	let id_category = $("#cat_id").val();

	return $.ajax({

			type: 'POST',

			url: base_url+'index.php/admin_ret_catalog/category/cat_purity',

			dataType:'json',

			data: {

				'id_category' : id_category
			},

			success:function(data) {

				console.log("purity",data);

				purities = data;
				
				$(".overlay").css("display", "none");
			}
		});
}

function get_product_size() {

	$(".overlay").css("display", "block");
	
    my_Date = new Date();

	let id_product = $("#product_id").val();

	return $.ajax({

			url: base_url+'index.php/admin_ret_order/get_product_size/?nocache=' + my_Date.getUTCSeconds(),             

			dataType: "json", 

			method: "POST", 

			data: {'id_product': id_product}, 

			success: function (data) { 

				console.log("Size",data);

				sizes = data;

				$(".overlay").css("display", "none");

			}

		});
}

function get_all_karigar() {

	$(".overlay").css("display", "block");

	return $.ajax({

			type: 'GET',

			url: base_url+'index.php/admin_ret_catalog/karigar/active_list',

			dataType:'json',

			success:function(data){

				console.log("karigar",data);

				karigars = data;

				$(".overlay").css("display", "none");

			}

		});

}

function load_weight(curRow, id) {

	let weightRange = weight_range;

	curRow.find(".weight option").remove();

	curRow.find(".weight").append(

		$("<option></option>")

		.attr("value", "")    

		.text('-Choose-')  

	);

	$.each(weightRange, function (key, item) {   

		curRow.find(".weight").append(

			$("<option></option>")

			.attr("value", item.id_weight)    

			.text(item.weight_range)  
		);
	
	});
	
	if(id != "" && id > 0) {

		curRow.find(".weight").val(id);

	}

}

function load_purity(curRow, id) {

	let purity = purities;

	curRow.find(".purity option").remove();

	curRow.find('.purity').append(

		$("<option></option>")

		.attr("value", "")    

		.text('-Choose-')  

	);

	$.each(purity, function (key, item) {   

		curRow.find(".purity").append(

			$("<option></option>")

			.attr("value", item.id_purity)    

			.text(item.purity)  
		);
	
	});	

	if(id.length > 0) {

		curRow.find(".purity").val(id);

	}
	
}

function load_product_size(curRow, id) {

	let size = sizes;

	curRow.find(".size option").remove();

	curRow.find('.size').append(

		$("<option></option>")

		.attr("value", "")    

		.text('-Choose-')  

	);

	$.each(size, function (key, item) {   

		curRow.find(".size").append(

			$("<option></option>")

			.attr("value", item.id_size)    

			.text(item.value+' '+item.name)  

		);

	});			

	if(id.length > 0) {

		curRow.find(".size").val(id);

	}

}

function load_karigar(curRow, id) {

	let karigar = karigars;

	curRow.find(".karigar option").remove();

	curRow.find('.karigar').append(

		$("<option></option>")

		.attr("value", "")    

		.text('-Choose-')  

	);

	$.each(karigar, function (key, item) {   

		$(".karigar").append(

			$("<option></option>")

			.attr("value", item.id_karigar)    

			.text(item.karigar)  

		);

	});

	if(id.length > 0) {

		curRow.find(".karigar").val(id);

	}

}

$(document).on("change",".purity", function() {

	let purityString = "";

	let purities = $(this).val();

	if(purities?.length > 0) {

		purityString = purities.join(',');

	}

	$(this).closest("tr").find(".id_purity").val(purityString);

});

$(document).on("change",".size", function() {

	let sizeString = "";

	let sizes = $(this).val();

	if(sizes?.length > 0) {

		sizeString = sizes.join(',');

	}

	$(this).closest("tr").find(".id_size").val(sizeString);

});

$(document).on("change",".karigar", function() {

	let karigarString = "";

	let karigars = $(this).val();

	if(karigars?.length > 0) {

		karigarString = karigars.join(',');

	}

	$(this).closest("tr").find(".id_karigar").val(karigarString);

});

function validate_image() {

	if(arguments[0].files[0].size > 1048576) {

		$.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'File size cannot be greater than 1 MB'});

		arguments[0].value = "";

		$('#img_preview').attr('src',  base_url+"assets/img/no_image.png");
	
	} else {

		var fileName =arguments[0].value;

		var ext = fileName.substring(fileName.lastIndexOf('.') + 1);

		ext = ext.toLowerCase();

		if(ext != "jpg" && ext != "png" && ext != "jpeg") {

			$.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Upload JPG or PNG Images only'});

			arguments[0].value = "";

			$('#img_preview').attr('src', base_url+"assets/img/no_image.png");

		} else {

			const file = arguments[0].files[0];

			console.log(file);
			
			if (file) {

				let reader = new FileReader();

				reader.onload = function(event){

					console.log(event.target.result);
					
					$('#img_preview').attr('src', event.target.result);
				}
				
				reader.readAsDataURL(file);
			}

		}
	}
}

function update_weightRange() {

	console.log("weightRange data",weightRange);

	let wrange = $.parseJSON(weightRange);

	$.each(wrange, function(key, value) {

		create_catlog_weight(value);

	});

}
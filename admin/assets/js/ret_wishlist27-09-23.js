var path =  url_params();

var ctrl_page 		= path.route.split('/');

var ENQ_IMG_PATH  = 'assets/img/enquiry/';

$(document).ready(function() {

	$('.followup_date').datepicker({ 
	
		format: 'dd-mm-yyyy'
	
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

	console.log(ctrl_page[1]);
	console.log(ctrl_page[2]);

	switch(ctrl_page[1]) {

	 	case 'wishlist':

			switch(ctrl_page[2]) {

				case 'list':				 	

					get_wishlist_list();

					$('#wishlist_list1').text(moment().startOf('month').format('YYYY-MM-DD'));

					$('#wishlist_list2').text(moment().endOf('month').format('YYYY-MM-DD'));	

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

					function (start, end) {

					$('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

					get_wishlist_list(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'))			 

					$('#wishlist_list1').text(start.format('YYYY-MM-DD'));

					$('#wishlist_list2').text(end.format('YYYY-MM-DD')); 

					}

					);   

				break;

			case 'supplier':				 	

				get_supp_wishlist_list();

				$('#suppWishlist_list1').text(moment().startOf('month').format('YYYY-MM-DD'));

				$('#suppWishlist_list2').text(moment().endOf('month').format('YYYY-MM-DD'));	

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

				function (start, end) {

				$('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

				get_supp_wishlist_list(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'))			 

				$('#suppWishlist_list1').text(start.format('YYYY-MM-DD'));

				$('#suppWishlist_list2').text(end.format('YYYY-MM-DD')); 

				}

				);   

			break;

		case 'factsheet':				 	

			get_factsheet_list();

			$('#factsheet_list1').text(moment().startOf('month').format('YYYY-MM-DD'));

			$('#factsheet_list2').text(moment().endOf('month').format('YYYY-MM-DD'));	

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

			function (start, end) {

			$('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

			get_factsheet_list(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'))			 

			$('#factsheet_list1').text(start.format('YYYY-MM-DD'));

			$('#factsheet_list2').text(end.format('YYYY-MM-DD')); 

			}

			);   

		break;

		case 'enquiry':

			get_enquiry_list();

			$('#enquiry_list1').text(moment().startOf('month').format('YYYY-MM-DD'));

			$('#enquiry_list2').text(moment().endOf('month').format('YYYY-MM-DD'));	

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

			function (start, end) {

			$('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

			get_enquiry_list(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'))			 

			$('#enquiry_list1').text(start.format('YYYY-MM-DD'));

			$('#enquiry_list2').text(end.format('YYYY-MM-DD')); 

			}

			);   

			break;

		}

	}
	
});

function get_wishlist_list(from_date,to_date) {

	my_Date = new Date();

	$("div.overlay").css("display", "block"); 

	$.ajax({

		url:base_url+"index.php/admin_ret_wishlist/wishlist/ajax_getwishlist?nocache=" + my_Date.getUTCSeconds(),

		dataType:"JSON",

		type:"POST",

		data:{'type': 1, 'from_date':from_date,'to_date':to_date},

		success:function(data){

			$("div.overlay").css("display", "none"); 

			var data_list = data.list;

			var access = data.access;

			var oTable = $('#wishlist_list').DataTable();

			$("#total_data").text(data_list.length);

			oTable.clear().draw();

			if (data_list!= null && data_list.length > 0) {

				oTable = $('#wishlist_list').dataTable({

					"bDestroy": true,

					"bInfo": true,

					"bFilter": true, 

					"bSort": true,

					"order": [[ 0, "desc" ]],

					"dom": 'lBfrtip',

					"buttons" : ['excel','print'],

					"tableTools": { "buttons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },

					"aaData": data_list,

					"aoColumns": [

								{ "mDataProp": "created_date" },

								{ "mDataProp": "tag_code" },

								{ "mDataProp": "cus_name" },		

								{ "mDataProp": "mobile" },		

								{ "mDataProp": "area" },		

								{ "mDataProp": "due_days" },		

								{ "mDataProp": "product_name" },		

								{ "mDataProp": "design_name" },		

								{ "mDataProp": "sub_design_name" },		

								{ "mDataProp": "description" },		

								{ "mDataProp": function ( row, type, val, meta ){

									let jsonString = "";

									if((row.img_arr).length > 0) {

										jsonString = JSON.stringify(row.img_arr);

										return "<button class='btn btn-primary btn-edit btn_wishlist_img'>View Images</button><input type='hidden' class='images' value='"+jsonString+"' />";

									} else {

										return "";

									}

								},

								},		

								{ "mDataProp": "emp_name" },	
								
								{ "mDataProp": "status_desc" },	

								{ "mDataProp": "remarks" },

								{ "mDataProp": "close_date" },

								{ "mDataProp": function ( row, type, val, meta ) {

									if(row.status == 1) {

										let id = row.id_wishlist;

										let edit_target_followup = (access.edit=='0'?"":"#confirm-followup");

										let action_content = '<a href="#" class="btn btn-primary btn-followup" id="edit" role="button" data-toggle="modal" data-id='+id+'  data-target='+edit_target_followup+'><i class="fa fa-edit" ></i> Follow Up</a>'

										return action_content;

									} else {

										return "";

									}

								}

							   	},

							    { "mDataProp": function ( row, type, val, meta ) {

									if(row.followup_data.length > 0) {

										return '<span class="drill-val"><i class="fa fa-chevron-circle-down text-teal"></i></span>';

									} else {
										
										return '';

									}

							   	}

								},

							] 

				});	

				var anOpen =[]; 

				$(document).on('click',"#wishlist_list .drill-val", function() { 

					var nTr = this.parentNode.parentNode;

					var i = $.inArray( nTr, anOpen );

					if ( i === -1 ) { 

						$('.drill-val', this).html('<i class="fa fa-chevron-circle-up text-teal"></i>'); 

						oTable.fnOpen( nTr, fnFormatRowDetails(oTable, nTr), 'details' );

						anOpen.push( nTr ); 

					}

					else { 

						$('.drill-val', this).html('<i class="fa fa-chevron-circle-down text-teal"></i>');

						oTable.fnClose( nTr );

						anOpen.splice( i, 1 );

					}

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

function get_supp_wishlist_list(from_date,to_date) {

	my_Date = new Date();

	$("div.overlay").css("display", "block"); 

	$.ajax({

		url:base_url+"index.php/admin_ret_wishlist/wishlist/ajax_getwishlist?nocache=" + my_Date.getUTCSeconds(),

		dataType:"JSON",

		type:"POST",

		data:{'type': 2, 'from_date':from_date,'to_date':to_date},

		success:function(data){

			$("div.overlay").css("display", "none"); 

			var data_list = data.list;

			var access = data.access;

			var oTable = $('#supp_wishlist_list').DataTable();

			$("#total_data").text(data_list.length);

			oTable.clear().draw();

			if (data_list!= null && data_list.length > 0) {

				oTable = $('#supp_wishlist_list').dataTable({

					"bDestroy": true,

					"bInfo": true,

					"bFilter": true, 

					"bSort": true,

					"order": [[ 0, "desc" ]],

					"dom": 'lBfrtip',

					"buttons" : ['excel','print'],

					"tableTools": { "buttons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },

					"aaData": data_list,

					"aoColumns": [

								{ "mDataProp": "created_date" },

								{ "mDataProp": "id_supp_catalogue" },

								{ "mDataProp": "cus_name" },		

								{ "mDataProp": "mobile" },

								{ "mDataProp": "product_name" },		

								{ "mDataProp": "design_name" },		

								{ "mDataProp": "sub_design_name" },

								{ "mDataProp": "emp_name" },	
								
								{ "mDataProp": "status_desc" },	

								{ "mDataProp": "remarks" },

								{ "mDataProp": "close_date" },

								{ "mDataProp": function ( row, type, val, meta ) {

									if(row.status == 1) {

										let id = row.id_wishlist;

										let edit_target_followup = (access.edit=='0'?"":"#confirm-followup");

										let action_content = '<a href="#" class="btn btn-primary btn-followup" id="edit" role="button" data-toggle="modal" data-id='+id+'  data-target='+edit_target_followup+'><i class="fa fa-edit" ></i> Follow Up</a>'

										return action_content;

									} else {

										return "";

									}

								}

							   	},

							    { "mDataProp": function ( row, type, val, meta ) {

									if(row.followup_data.length > 0) {

										return '<span class="drill-val"><i class="fa fa-chevron-circle-down text-teal"></i></span>';

									} else {
										
										return '';

									}

							   	}

								},

							] 

				});	

				var anOpen =[]; 

				$(document).on('click',"#supp_wishlist_list .drill-val", function() { 

					var nTr = this.parentNode.parentNode;

					var i = $.inArray( nTr, anOpen );

					if ( i === -1 ) { 

						$('.drill-val', this).html('<i class="fa fa-chevron-circle-up text-teal"></i>'); 

						oTable.fnOpen( nTr, fnFormatRowDetails(oTable, nTr), 'details' );

						anOpen.push( nTr ); 

					}

					else { 

						$('.drill-val', this).html('<i class="fa fa-chevron-circle-down text-teal"></i>');

						oTable.fnClose( nTr );

						anOpen.splice( i, 1 );

					}

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


function get_factsheet_list(from_date,to_date) {

	my_Date = new Date();

	$("div.overlay").css("display", "block"); 

	$.ajax({

		url:base_url+"index.php/admin_ret_wishlist/wishlist/ajax_getwishlist?nocache=" + my_Date.getUTCSeconds(),

		dataType:"JSON",

		type:"POST",

		data:{'type': 3, 'from_date':from_date,'to_date':to_date},

		success:function(data){

			$("div.overlay").css("display", "none"); 

			var data_list = data.list;

			var access = data.access;

			var oTable = $('#factsheet_list').DataTable();

			$("#total_data").text(data_list.length);

			oTable.clear().draw();

			if (data_list!= null && data_list.length > 0) {

				oTable = $('#factsheet_list').dataTable({

					"bDestroy": true,

					"bInfo": true,

					"bFilter": true, 

					"bSort": true,

					"order": [[ 0, "desc" ]],

					"dom": 'lBfrtip',

					"buttons" : ['excel','print'],

					"tableTools": { "buttons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },

					"aaData": data_list,

					"aoColumns": [

								{ "mDataProp": "created_date" },

								{ "mDataProp": "esti_id" },

								{ "mDataProp": "cus_name" },		

								{ "mDataProp": "mobile" },		

								{ "mDataProp": "area" },		

								{ "mDataProp": "weight" },		

								{ "mDataProp": "reasons_for_leaving" },		

								{ "mDataProp": "product_name" },	

								{ "mDataProp": "design_name" },	

								{ "mDataProp": "sub_design_name" },

								{ "mDataProp": function ( row, type, val, meta ){

									let jsonString = "";

									if((row.img_arr).length > 0) {

										jsonString = JSON.stringify(row.img_arr);

										return "<button class='btn btn-primary btn-edit btn_factsheet_img'>View Images</button><input type='hidden' class='images' value='"+jsonString+"' />";

									} else {

										return "";

									}

								},

								},		

								{ "mDataProp": "emp_name" },	
								
								{ "mDataProp": "status_desc" },	

								{ "mDataProp": "remarks" },

								{ "mDataProp": "close_date" },

								{ "mDataProp": function ( row, type, val, meta ) {

									if(row.status == 1) {

										let id = row.id_wishlist;

										let edit_target_followup = (access.edit=='0'?"":"#confirm-followup");

										let action_content = '<a href="#" class="btn btn-primary btn-followup" id="edit" role="button" data-toggle="modal" data-id='+id+'  data-target='+edit_target_followup+'><i class="fa fa-edit" ></i> Follow Up</a>'

										return action_content;

									} else {

										return "";

									}

								}

							   	},

							    { "mDataProp": function ( row, type, val, meta ) {

									if(row.followup_data.length > 0) {

										return '<span class="drill-val"><i class="fa fa-chevron-circle-down text-teal"></i></span>';

									} else {
										
										return '';

									}

							   	}

								},

							] 

				});	

				var anOpen =[]; 

				$(document).on('click',"#factsheet_list .drill-val", function() { 

					var nTr = this.parentNode.parentNode;

					var i = $.inArray( nTr, anOpen );

					if ( i === -1 ) { 

						$('.drill-val', this).html('<i class="fa fa-chevron-circle-up text-teal"></i>'); 

						oTable.fnOpen( nTr, fnFormatRowDetails(oTable, nTr), 'details' );

						anOpen.push( nTr ); 

					}

					else { 

						$('.drill-val', this).html('<i class="fa fa-chevron-circle-down text-teal"></i>');

						oTable.fnClose( nTr );

						anOpen.splice( i, 1 );

					}

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

function get_enquiry_list(from_date,to_date) {

	my_Date = new Date();

	$("div.overlay").css("display", "block"); 

	$.ajax({

		url:base_url+"index.php/admin_ret_wishlist/wishlist/ajax_getwishlist?nocache=" + my_Date.getUTCSeconds(),

		dataType:"JSON",

		type:"POST",

		data:{'type': 4, 'from_date':from_date,'to_date':to_date},

		success:function(data){

			$("div.overlay").css("display", "none"); 

			var data_list = data.list;

			var access = data.access;

			var oTable = $('#enquiry_list').DataTable();

			$("#total_data").text(data_list.length);

			oTable.clear().draw();

			if (data_list!= null && data_list.length > 0) {

				oTable = $('#enquiry_list').dataTable({

					"bDestroy": true,

					"bInfo": true,

					"bFilter": true, 

					"bSort": true,

					"order": [[ 0, "desc" ]],

					"dom": 'lBfrtip',

					"buttons" : ['excel','print'],

					"tableTools": { "buttons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },

					"aaData": data_list,

					"aoColumns": [

								{ "mDataProp": "created_date" },

								{ "mDataProp": "cus_name" },		

								{ "mDataProp": "mobile" },		

								{ "mDataProp": "area" },		

								{ "mDataProp": "enq_product" },		

								{ "mDataProp": "weight" },		

								{ "mDataProp": "price_range" },		

								{ "mDataProp": "description" },		

								{ "mDataProp": function ( row, type, val, meta ){

									let jsonString = "";

									if((row.img_arr).length > 0) {

										jsonString = JSON.stringify(row.img_arr);

										return "<button class='btn btn-primary btn-edit btn_enq_img'>View Images</button><input type='hidden' class='enq_images' value='"+jsonString+"' />";

									} else {

										return "";

									}

								},

								},		

								{ "mDataProp": "emp_name" },	
								
								{ "mDataProp": "status_desc" },	

								{ "mDataProp": "remarks" },

								{ "mDataProp": "close_date" },

								{ "mDataProp": function ( row, type, val, meta ) {

									if(row.status == 1) {

										let id = row.id_wishlist;

										let edit_target_followup = (access.edit=='0'?"":"#confirm-followup");

										let action_content = '<a href="#" class="btn btn-primary btn-followup" id="edit" role="button" data-toggle="modal" data-id='+id+'  data-target='+edit_target_followup+'><i class="fa fa-edit" ></i> Follow Up</a>'

										return action_content;

									} else {

										return "";

									}

								}

							   	},

							    { "mDataProp": function ( row, type, val, meta ) {

									if(row.followup_data.length > 0) {

										return '<span class="drill-val"><i class="fa fa-chevron-circle-down text-teal"></i></span>';

									} else {
										
										return '';

									}

							   	}

								},

							] 

				});	

				var anOpen =[]; 

				$(document).on('click',"#enquiry_list .drill-val", function() { 

					var nTr = this.parentNode.parentNode;

					var i = $.inArray( nTr, anOpen );

					if ( i === -1 ) { 

						$('.drill-val', this).html('<i class="fa fa-chevron-circle-up text-teal"></i>'); 

						oTable.fnOpen( nTr, fnFormatRowDetails(oTable, nTr), 'details' );

						anOpen.push( nTr ); 

					}

					else { 

						$('.drill-val', this).html('<i class="fa fa-chevron-circle-down text-teal"></i>');

						oTable.fnClose( nTr );

						anOpen.splice( i, 1 );

					}

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


$(document).on("click",".btn_wishlist_img" ,function() {

	$("#wishlist_img").html("");

	let images = $(this).closest("tr").find(".images").val();

	let imgArr = $.parseJSON(images);

	$.each(imgArr, function(key, item) {

		let img = "<div class='col-md-12 imgDiv'><img src='"+item+"' /></div>";

		console.log(img);

		$("#wishlist_img").append(img);

	});

	$("#images-modal").modal('show')

});

$(document).on("click",".btn_factsheet_items" ,function() {

	$("#factsheet_items #items_table tbody").empty();

	let items = $(this).closest("tr").find(".factsheet_items").val();

	let itemsArr = $.parseJSON(items);

	$.each(itemsArr, function(key, item) {

		let trdata = "<tr><td>"+item.product_name+"</td><td>"+item.design_name+"</td><td>"+item.sub_design_name+"</td></tr>";

		$("#factsheet_items #items_table tbody").append(trdata);

	});

	$("#factsheet-items-modal").modal('show')

});

$(document).on("click",".btn_factsheet_img" ,function() {

	$("#factsheet_img").html("");

	let images = $(this).closest("tr").find(".images").val();

	let imgArr = $.parseJSON(images);

	console.log(images);

	console.log(base_url+ENQ_IMG_PATH);

	$.each(imgArr, function(key, item) {

		let url = item;

		let img = "<div class='col-md-12 imgDiv'><img src='"+url+"' /></div>";

		console.log(img);

		$("#factsheet_img").append(img);

	});

	$("#images-modal").modal('show')

});

$(document).on("click",".btn_enq_img" ,function() {

	$("#enquiry_img").html("");

	let images = $(this).closest("tr").find(".enq_images").val();

	let imgArr = $.parseJSON(images);

	$.each(imgArr, function(key, item) {

		let url = item;

		let img = "<div class='col-md-12 imgDiv'><img src='"+url+"' /></div>";

		console.log(img);

		$("#enquiry_img").append(img);

	});

	$("#images-modal").modal('show');

});

$(document).on("click",".btn-followup", function() {

	let id = $(this).attr("data-id");

	if(id > 0) {

		$("#date_text").html("FollowUp Date");

		$("#remarks_text").html("Remarks");
		
		$("#follow_up").prop("checked", true);

		$("#followup_date").val("");

		$("#followup_remarks").val("");

		$("#id_wishlist_enq").val(id);

		$("#followup-modal").modal('show');

	} else {

		$.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>Required field is missing!"});

	}

});


$('input[type=radio][name="followup_type"]').change(function() {

	console.log("followup_type", $(this).val());

	let followup_type = $(this).val();

	if(followup_type == 1) {
		
		$("#date_text").html("FollowUp Date");

		$("#remarks_text").html("Remarks");

	} else if(followup_type == 2) {
		
		$("#date_text").html("Order Conversion Date");

		$("#remarks_text").html("Remarks");

	} else {

		$("#date_text").html("Date");

		$("#remarks_text").html("Reason (If not converted)");

	}

});

function fnFormatRowDetails( oTable, nTr )  {

  var oData = oTable.fnGetData( nTr );

  var _details = oData.followup_data; 

  var _detailsLength = _details.length;

  var rowDetail = '';

  if(_detailsLength > 0) {

	var _table = 

		'<div class="innerDetails">'+

		'<table class="table table-responsive table-bordered text-center table-sm">'+ 

			'<tr class="bg-teal">'+

			'<th>FollowUp Date</th>'+ 

			'<th>Remarks</th>'+

			'<th>Employee</th>'+

			'</tr>';

		var _details = oData.followup_data; 

	$.each(_details, function (idx, val) {

		_table += 

			'<tr class="prod_det_btn">'+

			'<td>'+val.followup_date+'</td>'+

			'<td>'+val.followup_remarks+'</td>'+

			'<td>'+val.emp_name+'</td>'+

			'</tr>'; 

	}); 

	rowDetail = _table+'</table></div>';

  }

  return rowDetail;

}
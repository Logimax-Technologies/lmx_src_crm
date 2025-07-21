var path =  url_params();
var ctrl_page = path.route.split('/');
var img_resource=[];
var pre_img_files=[];
var pre_img_resource=[];
var sp_img_files=[];
var sp_img_resource=[];
var n_img_files=[];
var n_img_resource=[];
var uom_details=[];
var total_files=[];
var lot_cat_details=[];
$(document).ready(function() {
	 var path =  url_params();
	 $('#status').bootstrapSwitch();					
     prod_info = [];	
     
     $(document).on('keypress',"input[type='number']",function (event){
         if ((event.which != 46 || $(this).val().indexOf('.') != -1) &&
    	  ((event.which < 48 || event.which > 57) &&
    		(event.which != 0 && event.which != 8))) {
    	  event.preventDefault();
    	}
     });
     
     $(document).on('keydown',"input[type='number']",function (event){
         if(event.which == 40 || event.which == 38 || event.which == 37 || event.which == 39 )
         {
             event.preventDefault();
         }
     });
     
	 switch(ctrl_page[1])
	 {
	 	case 'lot_inward':
				 switch(ctrl_page[2]){	
				    

				 	case 'list':				 	
				 		    var date = new Date();
                            var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 6, 1); 
                            var from_date =  firstDay.getFullYear()+'-'+(firstDay.getMonth()+1)+'-'+firstDay.getDate();
                            var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
                            $('#lt_date1').empty();
                            $('#lt_date2').empty();
				 			get_lotInward_list(from_date,to_date);
				 		break;
				 	case 'edit': 
				 			console.log(lot_preview_item);
				 			set_lot_preview(lot_preview_item);
				 			get_category(); 
				 			get_karigar();
				 			get_employee();	
				 			getActiveUOM();	
							$("#lt_product").on("keyup",function(e){ 
								var prod = $("#lt_product").val(); 
								if(prod.length == 3) { 
									getSearchProd(prod);
				                }
							});
							/*$("#design").on("keyup",function(e){ 
								var sub_prod = $("#design").val();
								if(sub_prod.length == 3) { 
									getSearchSubProd(sub_prod,$("#lt_product").val());
				                }
							});*/ 
							$("#design").on("keyup",function(e){ 
								var design = $("#design").val(); 
								if(design.length >= 2 || design.length <= 5) { 
									getSearchDesign(design,$("#lt_product_id").val());
						        }
							});
							$("#lt_order_no").on("keyup",function(e){ 
								var order = $("#lt_order_no").val();
								if(order.length >=2) { 
									getSearchOrderNo(order,$("#lt_order_no").val());
				                }
							}); 
						break;
				 	case 'add':
				 	        get_ActiveProduct();
				 			get_category(); 
				 			get_karigar();
				 			get_employee();
				 			getActiveUOM();				
							$("#lt_product").on("keyup",function(e){ 
								var prod = $("#lt_product").val(); 
								if(prod.length == 3) { 
									getSearchProd(prod);
				                }
							});
							$("#design").on("keyup",function(e){ 
								var design = $("#design").val(); 
								if(design.length >= 2 || design.length <= 5) { 
									getSearchDesign(design,$("#lt_product_id").val());
						        }
							});
							$("#lt_order_no").on("keyup",function(e){ 
								var order = $("#lt_order_no").val();
								if(order.length >= 2) { 
									getSearchOrderNo(order,$("#lt_order_no").val());
				                }
							}); 
				 		break;
				 }
	 		break; 
			case 'lot_merge':
				$('#gold_smith').select2(
				{
					placeholder:'Select Gold Smith',
					allowClear: true
				});
				get_karigar();
				getActiveUOM();
				get_category(); 
			break;
			case 'lot_split':		
				$('#lot_employee').select2(
				{
					placeholder:'Select Employee',
					allowClear: true
				});
				getActiveUOM();
				get_employee();
			break;	
	}
	$('#save_all').on('click',function(){
	    if(validateItemDetailRow()){
            $('#lot_form').submit();
            var id_category=$('#id_category').val();
            var id_purity=$('#id_purity').val();
            var id_product_division=$('#id_product_division').val();
            window.location.href= base_url+'index.php/admin_ret_lot/lot_inward/list';
	    }else{
			alert("Please fill required fields..");
			return false;
		}
	
	})
	$('#ltInward-dt-btn').daterangepicker(
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
			$('#lt_date1').text(start.format('YYYY-MM-DD'));
			$('#lt_date2').text(end.format('YYYY-MM-DD'));		
		    get_lotInward_list(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));            
		}
	);
	// Filter
	$('#rcvd_branch').on('change',function(e){
		get_lotInward_list($('#lt_date1').text(),$('#lt_date2').text())
	});
	$('#lt_date').datepicker({ dateFormat: 'yyyy-mm-dd', })
	/*Net Weight Calculation*/
	$("#lt_less_wt").on('keyup blur',function(e){
		if($("#lt_net_wt").val() < 0){
			$("#lt_net_wt").css('color','red');
		}else{
			$("#lt_net_wt").css('color','#555');
		}
	})
	$('input[type=radio][name="inward[stock_type]"]').change(function() {
	    $('#lt_item_list > tbody').empty();
		if(this.value == 1){
			$("#purity").prop('required',true);		
			$("#category").prop('required',true);		
			$(".tagged").css('display','inline');		
		}
		else if(this.value == 2){
			$("#purity").prop('required',false);			
			$("#category").prop('required',false);		
			$(".tagged").css('display','none');			
		}
		if(ctrl_page[1]=='lot_merge')
		{
			$('#lot_det > tbody').empty();
			$('#lot_search_list > tbody').empty();
			calculateLotTotal();
			TotalLotMerge();
		}
	});
	$('input[type=radio][name="search_order_by"]').change(function() {
		if(this.value == 1){ 	
			$(".byCus").css('display','block');
			$(".byOrderno").css('display','none');		
		}
		else if(this.value == 2){ 	
			$(".byOrderno").css('display','block');	
			$(".byCus").css('display','none');		
		}
	});
	$("#cus_name").on("keyup",function(e){ 
		var customer = $("#cus_name").val();
		if(customer.length >= 3) { 
			getSearchCustomers(customer);
		}
	});
	/*$("#lt_gross_wt,#lt_less_wt").on('keyup blur',function(e){
		var gross = $("#lt_gross_wt").val();
		var less = $("#lt_less_wt").val();
		var net = 0;
		if(less){
			if(gross){
				net = (gross > 0 ? gross-less : 0);
				$("#lt_net_wt").val(net);
				console.log(net);
			}
		}else if(gross){
			if(less){
				net = (less > 0 ? gross-less : 0);
				$("#lt_net_wt").val(net);
				console.log(net);
			}
		}		
		if(net < 0){
			$("#lt_net_wt").css('color','red');
		}else{
			$("#lt_net_wt").css('color','#555');
		}
	})*/
	/* Lot Received At */
	$('#lt_rcvd_branch_sel').on('change',function(e){
		if(this.value!='')
		{
			$("#id_branch").val(this.value);
		}
		else
		{
			$("#id_branch").val('');			
		}
	});
	/* Gold Smith - Starts */	
	/*$('#lt_type_select').on('change',function(e){
		if( $('#lt_gold_smith > option').length == 0){
			get_karigar();
		}		
	});*/	
	$("#lt_gold_smith").select2({			    
	 	placeholder: "Select Gold Smith",			    
	 	allowClear: true		    
 	});
	$('#lt_gold_smith').on('change',function(e){
		if(this.value!='')
		{
			$("#lt_gold_smith_id").val(this.value);
		}
		else
		{
			$("#lt_gold_smith_id").val('');			
		}
	});
	/* Ends - Gold Smith */  
	//Dynamic image upload
	$("#uploadImg_p_stn").on('click',function(){ // For Precious Stone
		$('#uploadArea_p_stn').css('display','block');
	});
	$("#uploadImg_sp_stn").on('click',function(){ // For Semi-Precious Stone
		$('#uploadArea_sp_stn').css('display','block');
	});
	$("#uploadImg_n_stn").on('click',function(){ // For Normal Stone
 		$('#uploadArea_n_stn').css('display','block');	
	});  
	$("#pre_images,#semi_pre_imgs,#norm_pre_imgs").on('change',function(){  
		validateCertifImg(this.id,$('#row_id').val());		
	});
// Enable/Disable Stone fields
$('#precious_stone').on('change',function() {
	if ($('#precious_stone').is(":checked")){
		$('#precious_stone').val(1);
		$('#precious_st_pcs').attr('disabled',false);
		$('#precious_st_wt').attr('disabled',false);
		$('#uploadImg_p_stn').attr('disabled',false);
	}else{
		$('#precious_stone').val(0);
		$('#precious_st_pcs').attr('disabled',true);
		$('#precious_st_wt').attr('disabled',true);
		$('#uploadImg_p_stn').attr('disabled',true);
	}
});
$('#semi_precious_stn').on('change',function() {
	if ($('#semi_precious_stn').is(":checked")){
		$('#semi_precious_stn').val(1);
		$('#semi_precious_st_pcs').attr('disabled',false);
		$('#semi_precious_st_wt').attr('disabled',false);
		$('#uploadImg_sp_stn').attr('disabled',false);
	}else{
		$('#semi_precious_stn').val(0);
		$('#semi_precious_st_pcs').attr('disabled',true);
		$('#semi_precious_st_wt').attr('disabled',true);
		$('#uploadImg_sp_stn').attr('disabled',true);
	}
});
$('#normal_stn').on('change',function() {
	if ($('#normal_stn').is(":checked")){
		$('#normal_stn').val(1);
		$('#normal_st_pcs').attr('disabled',false);
		$('#normal_st_wt').attr('disabled',false);
		$('#uploadImg_n_stn').attr('disabled',false);
	}else{
		$('#normal_stn').val(0);
		$('#normal_st_pcs').attr('disabled',true);
		$('#normal_st_wt').attr('disabled',true);
		$('#uploadImg_n_stn').attr('disabled',true);
	}
});
//on selecting category
$('#category').select2().on("change", function(e) {
	var id_category= $("#id_category").val();
	var lt_order_no= $("#lt_order_no").val();
	var pro_id=$('#lt_item_list > tbody').find('.pro_id').val();
	var lot_details_count = $('#lt_item_list tbody tr').length; 
	if(lot_details_count > 0)
	{
		if(this.value!='')
		{
			var selected_cat = this.value; 
			if(id_category != selected_cat && id_category != '')
			{
				proceed = confirm("This Category will be applied to all Added Items. Do you want to proceed ?"); 
				$("#id_category").val(this.value);
				$('#id_purity').val('');
				$('#purity option').remove();
				if(lt_order_no=='')
				{
					get_cat_purity(); 
				}	
				
			}
			else
			{
				$(this).val(id_category);
				$("#id_category").val(id_category);
				if(lt_order_no=='')
				{
					get_cat_purity(); 
				}	
			}
		}else
		{
			$("#id_category").val('');
			$("#id_purity").val('');
		}
	}
	else
	{
			if(this.value!='')
			{
				$("#id_category").val(this.value);
				$('#purity option').remove();	
				$('#id_purity').val('');
				if(lt_order_no=='')
				{
					get_cat_purity(); 
				}	
			}
			else
			{
				$("#id_category").val('');
				$("#id_purity").val('');
			}
	}
});
$('#cancel').on('click',function(){
			//	$("#id_category").val('');
			$('#purity option').remove();
			$("#Userconfirm").modal('toggle');
		});
//on selecting purity
$('#purity').select2().on("change", function(e) {
	var id_purity= $("#id_purity").val();
	var pro_id=$('#lt_item_list > tbody').find('.pro_id').val();
	if(pro_id!=undefined && pro_id!='')
	{
		if(this.value!='')
		{
				var value=this.value;
				if(($("#id_purity").val())!=this.value)
				{
						proceed = confirm("This Purity Will Change in Your Added Items..?");
						if(this.value!='')
					      {
					      	 $("#id_purity").val(this.value);
						  }
						  else{
						  	 $("#id_purity").val('');
						  }
				}		
		}
		else
		{
			$(this).val(id_purity);
			$("#id_purity").val(id_purity);
		}
	}
	else
	{
		if(this.value!='')
	      {
	      	 $("#id_purity").val(this.value);
		  }
		  else{
		  	 $("#id_purity").val('');
		  }
	}
});
$('#lot_images').on('change',function(){
		item_validateImage();		
});
$("#lot_order_no").select2({
    placeholder: "Select Order No",
    allowClear: true
});
//on selecting order no
$('#lot_order_no').select2()
    .on("change", function(e) {
      if(this.value!='')
      {
      	 $("#lt_order_id").val(this.value);
	  }
});
$(document).on('click',"#lot_img_upload", function(){ 
 	$('#lot_img_upload').prop('disabled',true);
	var formData = new FormData();
	var current=$('#cur_id').val();
	for(var i = 0;i<total_files.length;i++){
        formData.append("file[]", total_files[i]);
    }
	var my_Date = new Date();
	$.ajax({
        url: base_url+'index.php/admin_ret_lot/upload_lotimg/?nocache=' + my_Date.getUTCSeconds(),             
        dataType: "json", 
        method: "POST", 
         cache:false,
            contentType: false,
            processData: false,
        data:formData, 
        success: function (data) { 
        	$('#lot_img_upload').prop('disabled',false);
			total_files=[];
			$('#image_name').val(data.name);
        }
     });
});
});
	function remove_img(file,folder,field,id,imgs ) { 
		$("div.overlay").css("display", "block"); 
		$.ajax({
			   url:base_url+"index.php/admin_ret_lot/remove_img",
			   type : "POST",
			   data : {'file':file,'folder':folder,'field':field,'id':id,'imgs':imgs},
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
function get_lotInward_list(from_date,to_date)
{
	my_Date = new Date();
	$("div.overlay").css("display", "block"); 
	$.ajax({
		 url:base_url+"index.php/admin_ret_lot/lot_inward/ajax?nocache=" + my_Date.getUTCSeconds(),
		 dataType:"JSON",
		 type:"POST",
		 data:{'from_date':from_date,'to_date':to_date},
		 success:function(data){
   			set_lotInward_list(data);
   			 $("div.overlay").css("display", "none"); 
		  },
		  error:function(error)  
		  {
			 $("div.overlay").css("display", "none"); 
		  }	 
	});
}
function findUOM(data, uom_id){
  for(var x in data){
    if(data[x].uom_id && data[x].uom_id.split(",").indexOf(uom_id.toString())!=-1) return data[x].uom_short_code;
  }  
  return " ";  
}
function getActiveUOM()
{
	my_Date = new Date();
	$.ajax({
		 url:base_url+"index.php/admin_ret_catalog/uom/active_uom?nocache=" + my_Date.getUTCSeconds(),
		 dataType:"JSON",
		 type:"POST",
		 success:function(data){
		 		uom_details=data;
   			 $("div.overlay").css("display", "none"); 
		  },
		  error:function(error)  
		  {
			 $("div.overlay").css("display", "none"); 
		  }	 
	});
}

function lot_branch_copy(lot_details)
{
	if(lot_details.length>0)
	{
		console.log(lot_details);
		$.each(lot_details,function(key,item){
			window.open( base_url+'index.php/admin_ret_lot/branch_acknowladgement/2/'+item.lot_no+'/'+item.id_branch,'_blank');
		});
		//window.location.reload();
	}

}

$("#br_copy").click(function(){
		if($("input[name='lot_no[]']:checked").val())
		{
			var selected = [];
			$("input[name='lot_no[]']:checked").each(function() {
				
				$("#lot_inward_list tbody tr").each(function(index, value){
						if($(value).find("input[name='lot_no[]']:checked").is(":checked")){
							transData = { 
								'lot_no'  : $(value).find(".lot_no").val(),
								'id_branch'  : $('#filter_branch').val(),
							}							
							selected.push(transData);	
				 		}
					})
			});
			lot_details=selected;
			lot_branch_copy(lot_details);
		}else{
			alert('Please Select Any One Lot');
		}
   });
   
function set_lotInward_list(data)	
{
	$('body').addClass("sidebar-collapse");
    $("div.overlay").css("display", "none"); 
    var list = data.list;
    //var uom = data.list.uom;
    var access = data.access;
    var oTable = $('#lot_inward_list').DataTable();
    $("#total_product").text(list.length);
    if(access.add == '0')
	{
		$('#add_lot').attr('disabled','disabled');
	}
	 oTable.clear().draw();
   	 if (list!= null && list.length > 0)
	 {
	     
	 	oTable = $('#lot_inward_list').dataTable({
	                "bDestroy": true,
	                "bInfo": true,
	                "bFilter": true, 
	                "bSort": true,
	                "dom": 'lBfrtip',
	                 "order": [[ 0, "desc" ]],
	                "lengthMenu": [ [ 10, 25, 50, -1], [10, 25, 50, "All"] ],
	                "buttons" : ['excel','print'],
			        "tableTools": { "buttons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },
	                "aaData": list,
	                "aoColumns": [
	                            { "mDataProp": function ( row, type, val, meta ){
								chekbox='<input type="checkbox" name="lot_no[]" value="'+row.lot_no+'"  /><input type="hidden" class="lot_no" value="'+row.lot_no+'"  />' 
								return chekbox+" "+row.lot_no;
								}
								},
				                { "mDataProp": "lot_date" },		
				                { "mDataProp": "lotFrom" },		
				                { "mDataProp": "pur_ref_no" },		
				                { "mDataProp": "karigar_name" },		
				                { "mDataProp": "tot_pcs" },		
				                { "mDataProp": "gross_wt" },		
				                { "mDataProp": function ( row, type, val, meta ) 
				                    {
				                    var total_pcs=0;
				                	var tag_details=row.tag_det;
				                	    $.each(tag_details,function(key,items){
				                	        total_pcs+=parseFloat(items.tot_pcs);
				                	    });
				                	    return total_pcs;
				                	}
				                },
				                { "mDataProp": function ( row, type, val, meta ) 
				                    {
				                	 var tag_details=row.tag_det;
				                	 var total_gross_wt=0;
				                	    $.each(tag_details,function(key,items){
				                	        total_gross_wt+=parseFloat(items.gross_wt);
				                	    });
				                	    return parseFloat(total_gross_wt).toFixed(3);
				                	}
				                },
				                {
                                        "mDataProp": null,
                                        "sClass": "control center", 
                                        "sDefaultContent":  '<span class="drill-val"><i class="fa fa-chevron-circle-down text-teal"></i></span>'
                                },
				                { "mDataProp": function ( row, type, val, meta )
				                    {
				                        var total_pcs=0;
    				                	var tag_details=row.tag_det;
				                	    $.each(tag_details,function(key,items){
				                	        total_pcs+=parseFloat(items.tot_pcs);
				                	    });
				                        return parseFloat(row.tot_pcs-total_pcs);
				                    }
				                },
				                { "mDataProp": function ( row, type, val, meta )
				                    {
				                        var total_gross_wt=0;
				                        var tag_details=row.tag_det;
				                	    $.each(tag_details,function(key,items){
				                	        total_gross_wt+=parseFloat(items.gross_wt);
				                	    });
				                        return parseFloat(row.gross_wt-total_gross_wt).toFixed(3);
				                    }
				                },
				                
								{ "mDataProp": function ( row, type, val, meta ) {
				                	 id= row.lot_no
				                	 edit_url=(access.edit=='1' ? base_url+'index.php/admin_ret_lot/lot_inward/edit/'+id : '#' );
				                	 vendor_url=base_url+'index.php/admin_ret_lot/vendor_acknowladgement/1/'+id;
				                	 ho_url=base_url+'index.php/admin_ret_lot/lot_acknowladgement/2/'+id;
				                	 branch_url=base_url+'index.php/admin_ret_lot/branch_acknowladgement/2/'+id;
				                	 delete_url=(access.delete=='1' ? base_url+'index.php/admin_ret_lot/lot_inward/delete/'+id : '#' );
				                	 delete_confirm= (access.delete=='1' ?'#confirm-delete':'');
				                	 action_content=(row.lot_from!=5 ? '<a href="'+edit_url+'" class="btn btn-primary btn-edit" ><i class="fa fa-edit" ></i></a>' :'')+'<a href="'+vendor_url+'" target="_blank" class="btn btn-secondary btn-print" data-toggle="tooltip" title="Vendor Copy"><i class="fa fa-print" ></i></a><a href="'+ho_url+'" target="_blank" class="btn btn-info btn-print" data-toggle="tooltip" title="Office Copy"><i class="fa fa-print" ></i></a><a href="#" class="btn btn-danger btn-del" data-href='+delete_url+' data-toggle="modal" data-target="#confirm-delete" ><i class="fa fa-trash"></i></a>';
				                	return action_content;
			                	}
				            }] 
	            });	
	            var anOpen =[]; 
            		$(document).on('click',"#lot_inward_list .control", function(){ 
            		   var nTr = this.parentNode;
            		   var i = $.inArray( nTr, anOpen );
            		 
            		   if ( i === -1 ) { 
            				$('.drill-val', this).html('<i class="fa fa-chevron-circle-up text-teal"></i>'); 
            				oTable.fnOpen( nTr, fnFormatRowTagDetails(oTable, nTr), 'details' );
            				anOpen.push( nTr ); 
            		    }
            		    else { 
            				$('.drill-val', this).html('<i class="fa fa-chevron-circle-down text-teal"></i>');
            				oTable.fnClose( nTr );
            				anOpen.splice( i, 1 );
            		    }
            		} );
		 }  
}

function fnFormatRowTagDetails( oTable, nTr )
{
  var oData = oTable.fnGetData( nTr );
  var rowDetail = '';
  var prodTable = 
     '<div class="innerDetails">'+
      '<table class="table table-responsive table-bordered text-center table-sm">'+ 
        '<tr class="bg-teal">'+
        '<th>S.No</th>'+ 
        '<th>Branch</th>'+
        '<th>Tot Pcs</th>'+
        '<th>Tot Gwt</th>'+
        '<th>Action</th>'+
        '</tr>';
    var tag_details = oData.branch_wise; 
  $.each(tag_details, function (idx, val) {
      branch_summary_url=base_url+'index.php/admin_ret_lot/branch_acknowladgement/1/'+val.tag_lot_id+'/'+val.current_branch;
      branch_url=base_url+'index.php/admin_ret_lot/branch_acknowladgement/2/'+val.tag_lot_id+'/'+val.current_branch;
  	prodTable += 
        '<tr class="prod_det_btn">'+
        '<td>'+parseFloat(idx+1)+'</td>'+
        '<td>'+val.branch_name+'</td>'+
        '<td>'+val.tot_pcs+'</td>'+
        '<td>'+val.gross_wt+'</td>'+
        '<td><a href="'+branch_url+'" target="_blank" class="btn btn-info btn-print" data-toggle="tooltip" title="Branch Copy Detailed"><i class="fa fa-print" ></i></a><a href="'+branch_summary_url+'" target="_blank" class="btn btn-secondary btn-print" data-toggle="tooltip" title="Branch Copy Summary"><i class="fa fa-print" ></i></a></td>'+
        '</tr>'; 
  }); 
  rowDetail = prodTable+'</table></div>';
  return rowDetail;
}
function get_category()
{
	$(".overlay").css('display','block');
	$.ajax({
		type: 'POST',
		url: base_url+'index.php/admin_ret_catalog/category/active_category',
		dataType:'json',
		success:function(data){
			lot_cat_details = data;
		  var id_category =  $('#id_category').val();
		   $.each(data, function (key, item) {
			   		$('#category').append(
						$("<option></option>")
						  .attr("value", item.id_ret_category)
						  .text(item.name)
					);
			});
			$("#category").select2({
			    placeholder: "Select Category",
			    allowClear: true
			});
			$("#category").select2("val",(id_category!='' && id_category>0?id_category:''));
			 $(".overlay").css("display", "none");	
		}
	});
}
function get_cat_purity()
{
	$(".overlay").css('display','block');
	$.ajax({
		type: 'POST',
		url: base_url+'index.php/admin_ret_catalog/category/cat_purity',
		dataType:'json',
		data: {
			'id_category' :$('#id_category').val()
		},
		success:function(data){
		  var id_purity =  $('#id_purity').val();
		   $.each(data, function (key, item) {
			   		$('#purity').append(
						$("<option></option>")
						  .attr("value", item.id_purity)
						  .text(item.purity)
					);
			});
			$("#purity").select2({
			    placeholder: "Select Purity",
			    allowClear: true
			});
			$("#purity").select2("val",(id_purity!='' && id_purity>0?id_purity:''));
			 $(".overlay").css("display", "none");	
		}
	});
}
function get_karigar(){	 
	$.ajax({		
	 	type: 'GET',		
	 	url: base_url+'index.php/admin_ret_catalog/karigar/active_list',		
	 	dataType:'json',		
	 	success:function(data){				 
		 	var id =  $('#lt_gold_smith_id').val();			 	
		 	$.each(data, function (key, item) {			  				  			   		
	    	 	$("#lt_gold_smith,#gold_smith").append(						
	    	 	$("<option></option>")						
	    	 	.attr("value", item.id_karigar)						  						  
	    	 	.text(item.karigar+' '+ item.code)						  					
	    	 	);	 
	     	});						
	     	$("#lt_gold_smith,#gold_smith").select2("val",(id!='' && id>0?id:''));	 
	     	$(".overlay").css("display", "none");			
	 	}	
	}); 
} 
function getSearchProd(searchTxt){
	my_Date = new Date();
	$.ajax({
        url: base_url+'index.php/admin_ret_catalog/product/active_prodBySearch/?nocache=' + my_Date.getUTCSeconds(),             
        dataType: "json", 
        method: "POST", 
        data: {'searchTxt':searchTxt}, 
        success: function (data) { 
			$( "#lt_product" ).autocomplete(
			{
				source: data,
				select: function(e, i)
				{ 
					e.preventDefault();
					$("#lt_product" ).val(i.item.label); 
					$("#lt_product_id" ).val(i.item.value); 
					$("#lot_id_design" ).val(''); 
					$("#design" ).val('');
				},
				response: function(e, i) {
		            // ui.content is the array that's about to be sent to the response callback.
		            console.log(i);
		            if (i.content.length === 0) {
		               $("#prodAlert").html('<p style="color:red">Enter a valid Product</p>');
		               //$('#lt_product').val('');
		            }else{
						$("#prodAlert").html('');
					} 
		        },
				 minLength: 0,
			});
        }
     });
}
/*function getSearchSubProd(searchTxt,prodId){
	my_Date = new Date();
	$.ajax({
        url: base_url+'index.php/admin_ret_catalog/sub_product/active_subprodBySearch/?nocache=' + my_Date.getUTCSeconds(),             
        dataType: "json", 
        method: "POST", 
        data: {'searchTxt':searchTxt,'prodId':prodId}, 
        success: function (data) { 
			$( "#design" ).autocomplete(
			{
				source: data,
				select: function(e, i)
				{ 
					e.preventDefault();
					$("#design" ).val(i.item.label); 
					$("#lot_id_design" ).val(i.item.value);  
				},
				response: function(e, i) {
		            // ui.content is the array that's about to be sent to the response callback.
		            console.log(i);
		            if (i.content.length === 0) {
		               $("#subprodAlert").html('<p style="color:red">Enter a valid Sub Product</p>');
		               //$('#lt_product').val('');
		            }else{
						$("#subprodAlert").html('');
					} 
		        },
				 minLength: 0,
			});
        }
     });
}*/
function getSearchDesign(searchTxt,prodId){
	my_Date = new Date();
	$.ajax({
        url: base_url+'index.php/admin_ret_brntransfer/branch_transfer/getDesignByFilter/?nocache=' + my_Date.getUTCSeconds(),             
        dataType: "json", 
        method: "POST", 
        data: {'searchTxt':searchTxt,'prodId':prodId}, 
        success: function (data) { 
			$( "#design" ).autocomplete(
			{
				source: data,
				select: function(e, i)
				{ 
					e.preventDefault();
					$("#design" ).val(i.item.label); 
					$("#lot_id_design" ).val(i.item.value);  
				},
				response: function(e, i) {
		            // ui.content is the array that's about to be sent to the response callback.
		            console.log(i); 
		        },
				 minLength: 0,
			});
        }
     });
}
function getSearchOrderNo(searchTxt){
	my_Date = new Date();
	$.ajax({
        url: base_url+'index.php/admin_ret_lot/getOrderNosBySearch/?nocache=' + my_Date.getUTCSeconds(),             
        dataType: "json", 
        method: "POST", 
        data: {'searchTxt':searchTxt}, 
        success: function (data) { 
			$( "#lt_order_no" ).autocomplete(
			{
				source: data,
				select: function(e, i)
				{ 
					e.preventDefault();
					$("#lt_order_no" ).val(i.item.label); 
					$("#lt_order_id" ).val(i.item.order_no); 
					$("#order_from" ).val(i.item.order_from); 
					get_karigar_by_order(i.item.order_no)
					//$("#lt_order_id" ).val(i.item.label);  
				},
				response: function(e, i) {
		            // ui.content is the array that's about to be sent to the response callback.
		            console.log(i);
		            if (i.content.length === 0) {
		               $("#orderAlert").html('<p style="color:red">Enter a valid Order No</p>');
		               //$('#lt_product').val('');
		            }else{
						$("#orderAlert").html('');
					} 
		        },
				 minLength: 0,
			});
        }
     });
}


function item_validateImage()
 {
 		$('#lot_img').empty();
		var files = event.target.files;
		//var a = $('#cur_id').val();
		var preview=$('#lot_img');
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
					div.setAttribute('class','col-md-2'); 
					div.setAttribute('id','img_'+key); 
					div.innerHTML+= "<a onclick='img_remove("+key+")'><i class='fa fa-trash'></i></a><img class='thumbnail' src='" + item.src + "'" +
					"style='width: 100px;height: 100px;'/>";  
					preview.append(div);
			   }
			   $('#lot_img_upload').css('display','');
		});
	},3000);  
}
 function img_remove(id)
 {
 		$('#img_'+id).remove();
		const index = total_files.indexOf(img_resource[id]);
		total_files.splice(index,1);
		console.log(total_files);
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
					$("#cus_village").html(i.item.village_name);
					$("#chit_cus").html((i.item.accounts==0 ?'No' :'Yes'));
					$("#vip_cus").html(i.item.vip); 
					getOrdersByCus(i.item.value);
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
function getOrdersByCus(idcus)
{
	$(".overlay").css('display','block');
	$.ajax({
		type: 'POST',
		url: base_url+'index.php/admin_ret_order/order/getOrderByCus',
		dataType:'json',
		data: {
			'id_customer' : idcus
		},
		success:function(data){ 
		   $.each(data, function (key, item) {
			   		$('#lot_order_no').append(
						$("<option></option>")
						  .attr("value", item.id_orderdetails)
						  .text(item.orderno)
					);
			});
			//$("#lot_order_no").select2("val",'');
			$(".overlay").css("display", "none");	
		}
	});
}
//Employee Filter
	function get_employee()
	{
		my_Date = new Date();
		$.ajax({ 
		url:base_url+ "index.php/admin_ret_estimation/get_employee?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),
        type:"POST",
        dataType:"JSON",
        success:function(data)
        {
           var id_employee=$('#id_employee').val();
           emp_details=data;
           $.each(data, function (key, item) {					  				  			   		
                	 	$("#select_emp").append(						
                	 	$("<option></option>")						
                	 	.attr("value", item.id_employee)						  						  
                	 	.text(item.emp_name)						  					
                	 	);			   											
                 	});						
             	$("#select_emp").select2({			    
            	 	placeholder: "Select Employee",			    
            	 	allowClear: true		    
             	});					
         	    $("#select_emp").select2("val",(id_employee!='' && id_employee>0?id_employee:''));	 
         	    $(".overlay").css("display", "none");	
        },
        error:function(error)  
        {	
        } 
    	});
	}
	$('#select_emp').on('change',function(){
		if(this.value!='')
		{
			$('#id_employee').val(this.value);
		}
		else
		{
			$('#id_employee').val('');
		}
	});
//Product search
$(document).on('keyup',	".lot_product", function(e){ 
	var row = $(this).closest('tr');
	var product = row.find(".lot_product").val(); 
	getSearchProducts(product, row);
}); 
//Design search
$(document).on('keyup',	".design", function(e){ 
	var row = $(this).closest('tr'); 
	var design = row.find(".design").val()
	getSearchDesigns(design, row);
});  
$(document).on('keyup',	'.gross_wt, .lot_lwt', function(e){ 
	var row = $(this).closest('tr'); 
	var gross_wt = (isNaN(row.find('.gross_wt').val()) || row.find('.gross_wt').val()=='' ?0 :row.find('.gross_wt').val());
	var lot_lwt	 = (isNaN(row.find('.lot_lwt').val()) ||  row.find('.lot_lwt').val()=='' ?0 :row.find('.lot_lwt').val());
	var net_wt   =  parseFloat(parseFloat(gross_wt)-parseFloat(lot_lwt)).toFixed(3);
	row.find('.lot_nwt').val(net_wt);
}); 
$(document).on('change','.mc_type ', function(e){ 
	var row = $(this).closest('tr'); 
	if(this.value!='')
	{
			row.find('.id_mc_type').val(this.value);
	}
	else
	{
		row.find('.id_mc_type').val(1);
	}
}); 
$(document).on('change','.sel_design_for ', function(e){ 
	var row = $(this).closest('tr'); 
	if(this.value!='')
	{
			row.find('.design_for').val(this.value);
	}
	else
	{
		row.find('.design_for').val(1);
	}
});
function create_new_empty_lot_row()
{
	var orderno=$('#lt_order_id').val();
	var id_karigar=$('#lt_gold_smith_id').val();
	var stock_type       = $("input[name='inward[stock_type]']:checked").val();
    if(ctrl_page[2]=='edit')
	{
		var row_id = $('#lt_item_list tbody tr').length;
		$('#curRow').val(row_id);
	}
	var row = "";
	if(orderno=='')
	{
		var a = $("#curRow").val();
		var i = ++a;
		$("#curRow").val(i);
		var uom='';
		$.each(uom_details,function(key,item){
			uom += "<option value='"+item.uom_id+"'>"+item.code+"</option>";
		});
		row += '<tr id='+i+'>'
					/*+'<td><input type="text" class="lot_product" name="inward_item['+i+'][product]" value="" required style="width:80px;" autocomplete="off"/><input type="hidden" class="pro_id" name="inward_item['+i+'][lot_product]" value="" /><input type="hidden" class="sales_mode" name="inward_item['+i+'][sales_mode]" value="" /><input type="hidden" class="calculation_based_on" name="inward_item['+i+'][calculation_based_on]" value="" /<input type="hidden" class="id_lot_inward_detail" id="id_lot_inward_detail" value=""></td>'
					+'<td><input type="text" class="design" name="inward_item['+i+'][design]" value="" style="width:80px;" autocomplete="off" '+(stock_type==1 ? "readonly" : '')+'/><input type="hidden" class="des_id" name="inward_item['+i+'][lot_id_design]" value="" /></td>'
					*/
					+'<td><select class="lot_product" name="inward_item['+i+'][product]" value="" placeholder="Search Product" style="width:150px;"><input type="hidden" class="pro_id" name="inward_item['+i+'][lot_product]" value="" /><input type="hidden" class="sales_mode" name="inward_item['+i+'][sales_mode]" value="" /><input type="hidden" class="calculation_based_on" name="inward_item['+i+'][calculation_based_on]" value="" /<input type="hidden" class="id_lot_inward_detail" id="id_lot_inward_detail" value=""></td>'
					+'<td><select class="design" name="inward_item['+i+'][design]"'+(stock_type == 1? 'disabled' : '')+' value="" placeholder="Search Design" style="width:150px;"><input type="hidden" class="des_id" name="inward_item['+i+'][lot_id_design]"  value=""/></td>'
					+'<td><select class="lot_sub_design" name="est_catalog[sub_design][]" '+(stock_type == 1? 'disabled' : '')+' value="" placeholder="Search SubDesign" required style="width:150px;"></select><input type="hidden" class="lot_id_sub_design" name="inward_item['+i+'][id_sub_design]"value=""/></td>'
					+'<td><select class="sel_design_for" style="width:80px;" autocomplete="off"><option value="1">Male</option><option value="2">Female</option><option value="3">Unisex</option></select><input type="hidden" class="design_for" name="inward_item['+i+'][design_for]" value="1"></td>'
					+'<td><input type="number" step="any" value="" name="inward_item['+i+'][pcs]" class="lot_pcs" style="width:60px;"></td>'
					+'<td><div class="input-group"><input type="number" step="any" value="" name="inward_item['+i+'][gross_wt]" class="gross_wt" style="width:80px;" autocomplete="off"><span class="input-group-addon input-sm no-padding" style="background-color: transparent;height: 12px;font-size: 16px;border: none;"><select class="gross_wt_uom" name="inward_item['+i+'][gross_wt_uom]">'+uom+'</select></span></div></td>'
					+'<td><div class="input-group"><input type="number" step="any" value="" name="inward_item['+i+'][less_wt]" class="lot_lwt" style="width:80px;" autocomplete="off"><span class="input-group-addon input-sm no-padding" style="background-color: transparent;height: 12px;font-size: 16px;border: none;"><select class="less_wt_uom" name="inward_item['+i+'][less_wt_uom]">'+uom+'</select></span></div></td>'
					+'<td><div class="input-group"><input type="number" step="any" value="" name="inward_item['+i+'][net_wt]" class="lot_nwt" style="width:80px;" autocomplete="off"><span class="input-group-addon input-sm no-padding" style="background-color: transparent;height: 12px;font-size: 16px;border: none;"><select class="net_wt_uom" name="inward_item['+i+'][net_wt_uom]">'+uom+'</select></span></div></td>'
					+'<td><input type="number" step="any" class="wastage_percentage" name="inward_item['+i+'][wastage_percentage]" style="width:60px;"></td>'
					+'<td><div class="input-group"><input type="number" step="any" class="making_charge" name="inward_item['+i+'][making_charge]" style="width:70px;"><span class="input-group-addon input-sm no-padding" style="background-color: transparent;height: 12px;border: none;"><select class="form-control mc_type" style="width:80px;height: 29px;" name="inward_item['+i+'][id_mc_type]" autocomplete="off"><option value="1">Gram</option><option value="2" selected>Piece</option></select></span></div><input type="hidden" value=""  class="id_mc_type"></td>'
					+'<td><input type="number" step="any" class="buy_rate" name="inward_item['+i+'][buy_rate]" style="width:80px;" autocomplete="off"><span class="buy_rt_type"></span></td>'
					+'<td><input type="number" step="any" class="sell_rate" name="inward_item['+i+'][sell_rate]" style="width:80px;" autocomplete="off"><span class="sell_rt_type"></span></td>'
					+'<td><div class="input-group"><input type="number" step="any" class="size" name="inward_item['+i+'][size]" style="width:70px;"><span class="input-group-addon input-sm no-padding" style="background-color: transparent;height: 12px;border: none;"></span></div></td>'
					+'<td><a href="#" onClick="show_stone_modal($(this).closest(\'tr\'),'+i+');" class="btn btn-default btn-sm"><i class="fa fa-plus-circle"></i></a><input type="hidden" class="precious_stone" name="inward_item['+i+'][precious_stone]"/><input type="hidden" class="precious_stone_pcs" name="inward_item['+i+'][precious_st_pcs]"/><input type="hidden" class="precious_stone_wt" name="inward_item['+i+'][precious_st_wt]"><input type="hidden" class="p_stn_certif_uploaded" name="inward_item['+i+'][p_stn_certif_uploaded]"><input type="hidden" class="precious_st_certif" name="inward_item['+i+'][precious_st_certif]"><input type="hidden" class="semi_precious_stn" name="inward_item['+i+'][semi_precious_stn]"/><input type="hidden" class="semi_precious_st_pcs" name="inward_item['+i+'][semi_precious_st_pcs]"/><input type="hidden" class="semi_precious_st_wt" name="inward_item['+i+'][semi_precious_st_wt]"><input type="hidden" class="sp_stn_certif_uploaded" name="inward_item['+i+'][sp_stn_certif_uploaded]"><input type="hidden" class="semiprecious_st_certif" name="inward_item['+i+'][semiprecious_st_certif]"><input type="hidden" class="normal_stn" name="inward_item['+i+'][normal_stn]"/><input type="hidden" class="normal_st_pcs" name="inward_item['+i+'][normal_st_pcs]"/><input type="hidden" class="normal_st_wt" name="inward_item['+i+'][normal_st_wt]"><input type="hidden" class="normal_st_certif" name="inward_item['+i+'][normal_st_certif]"><input type="hidden" class="n_stn_certif_uploaded" name="inward_item['+i+'][n_stn_certif_uploaded]"><input type="hidden" name="inward_item['+i+'][nor_wt_uom]" class="nor_wt_uom"><input type="hidden" name="inward_item['+i+'][semi_wt_uom]" class="semi_wt_uom"><input type="hidden" name="inward_item['+i+'][pre_wt_uom]" class="pre_wt_uom"></td>'
					+'<td><a href="#" onClick="remove_row($(this).closest(\'tr\'));" class="btn btn-danger"><i class="fa fa-trash"></i></a></td>'
					+'</tr>';
		$('#lt_item_list tbody').append(row);
		$('#lt_item_list > tbody').find('tr:last td:eq(0) .cat_product').focus();
		/*$('#lt_item_list > tbody').find('.mc_type').select2();
		$('#lt_item_list > tbody').find('.mc_type').select2({
		    placeholder: "Charge Type",
		    allowClear: true
		});
		$('#lt_item_list > tbody').find('.id_mc_type').val(2); //1-Gram,2-Pices
		$('#lt_item_list > tbody').find('.mc_type ').val(2); //1-Gram,2-Pices*/
		var id_mc_type=$('#lt_item_list > tbody').find('.id_mc_type').val();
		$('.lot_product').append(
    		$("<option></option>")
    		.attr("value", "")    
    		.text('-Choose-')  
		);
		
		$.each(lot_product_details, function (key, item){ 
    	   	if(item.stock_type == 1 && stock_type == 1)
    		{
    			$('.lot_product').append(
    			$("<option></option>")
    			.attr("value", item.pro_id)    
    			.text(item.product_name)  
    			);
    	    }
    		else if(item.stock_type == 2 && stock_type == 2)	
    		{ 
    			$('.lot_product').append(
    				$("<option></option>")
    				.attr("value", item.pro_id)    
    				.text(item.product_name) 
    			); 
    		}
		});
		
		$('#lt_item_list > tbody').find('.lot_product').select2();
		$('#lt_item_list > tbody').find('.design').select2();
		$('#lt_item_list > tbody').find('.lot_sub_design').select2();
		
		$('#lt_item_list > tbody').find('.lot_product').select2({
		    placeholder: "Product",
		    allowClear: true
		});
		
		$('#lt_item_list > tbody').find('.design').select2({
		    placeholder: "Design",
		    allowClear: true
		});
		
		$('#lt_item_list > tbody').find('.lot_sub_design').select2({
		    placeholder: "Sub Design",
		    allowClear: true
		});
		$('#lt_item_list > tbody').find('tr:last td:eq(0) .lot_product').focus();
	}
	else{
		my_Date = new Date();
		$.ajax({
		url: base_url+'index.php/admin_ret_lot/get_order_details?nocache=' + my_Date.getUTCSeconds(),             
		dataType: "json", 
		method: "POST", 
		data: { 'orderno': orderno,'id_karigar':id_karigar,'id_branch':$('#order_from').val()}, 
		success: function (data) {
				if(data)
				{
					$.each(data,function(i,list){
					var a = $("#curRow").val();
					var i = ++a;
					$("#curRow").val(i);
					var uom='';
					$.each(uom_details,function(key,item){
					uom += "<option value='"+item.uom_id+"'>"+item.code+"</option>";
					});
					row = '<tr id='+i+'>'
					+'<td><input type="text" class="lot_product" name="inward_item['+i+'][product]" value='+list.product_name+' readonly style="width:80px;" autocomplete="off"/><input type="hidden" class="pro_id" name="inward_item['+i+'][lot_product]" value='+list.id_product+' /><input type="hidden" class="sales_mode" name="inward_item['+i+'][sales_mode]" value='+list.sales_mode+' /><input type="hidden" class="calculation_based_on" name="inward_item['+i+'][calculation_based_on]" value='+list.calculation_based_on+' /<input type="hidden" class="id_lot_inward_detail" id="id_lot_inward_detail" value=""><input type="hidden" class="order_no"  value='+list.order_no+' /></td>'
					+'<td><input type="text" class="design" name="inward_item['+i+'][design]" value='+list.design_name+' readonly style="width:80px;" autocomplete="off"/><input type="hidden" class="des_id" name="inward_item['+i+'][lot_id_design]"  value='+list.design_no+' /></td>'
					+'<td><select class="sel_design_for" style="width:80px;" autocomplete="off"><option value="1">Male</option><option value="2">Female</option><option value="3">Unisex</option></select><input type="hidden" class="design_for" name="inward_item['+i+'][design_for]" value="1"></td>'
					+'<td><input type="number" step="any" value='+list.tot_pcs+' name="inward_item['+i+'][pcs]" class="lot_pcs" style="width:60px;"></td>'
					+'<td><div class="input-group"><input type="number" step="any" value='+list.tot_net_wt+' name="inward_item['+i+'][gross_wt]" class="gross_wt" style="width:80px;" autocomplete="off"><span class="input-group-addon input-sm no-padding" style="background-color: transparent;height: 12px;font-size: 16px;border: none;"><select class="gross_wt_uom" name="inward_item['+i+'][gross_wt_uom]">'+uom+'</select></span></div></td>'
					+'<td><div class="input-group"><input type="number" step="any" value="" name="inward_item['+i+'][less_wt]" class="lot_lwt" style="width:80px;" autocomplete="off"><span class="input-group-addon input-sm no-padding" style="background-color: transparent;height: 12px;font-size: 16px;border: none;"><select class="less_wt_uom" name="inward_item['+i+'][less_wt_uom]">'+uom+'</select></span></div></td>'
					+'<td><div class="input-group"><input type="number" step="any" value='+list.tot_net_wt+' name="inward_item['+i+'][net_wt]" class="lot_nwt" style="width:80px;" autocomplete="off"><span class="input-group-addon input-sm no-padding" style="background-color: transparent;height: 12px;font-size: 16px;border: none;"><select class="net_wt_uom" name="inward_item['+i+'][net_wt_uom]">'+uom+'</select></span></div></td>'
					+'<td><input type="number" step="any" class="wastage_percentage" name="inward_item['+i+'][wastage_percentage]" value='+list.wastage_per+' style="width:60px;"></td>'
					+'<td><div class="input-group"><input type="number" step="any" class="making_charge" name="inward_item['+i+'][making_charge]" style="width:70px;"><span class="input-group-addon input-sm no-padding" style="background-color: transparent;height: 12px;border: none;"><select class="form-control mc_type" style="width:80px;height: 29px;" name="inward_item['+i+'][id_mc_type]" autocomplete="off"><option value="1">Gram</option><option value="2" selected>Piece</option></select></span></div><input type="hidden" value=""  class="id_mc_type"></td>'
					+'<td><input type="number" step="any" class="buy_rate" name="inward_item['+i+'][buy_rate]" style="width:80px;" autocomplete="off"><span class="buy_rt_type"></span></td>'
					+'<td><input type="number" step="any" class="sell_rate" name="inward_item['+i+'][sell_rate]" style="width:80px;" autocomplete="off"><span class="sell_rt_type"></span></td>'
					+'<td><div class="input-group"><input type="number" step="any" class="size" name="inward_item['+i+'][size]" value='+list.tot_size+' style="width:70px;"><span class="input-group-addon input-sm no-padding" style="background-color: transparent;height: 12px;border: none;"></span></div></td>'
					+'<td><a href="#" onClick="show_stone_modal($(this).closest(\'tr\'),'+i+');" class="btn btn-default btn-sm"><i class="fa fa-plus-circle"></i></a><input type="hidden" class="precious_stone" name="inward_item['+i+'][precious_stone]"/><input type="hidden" class="precious_stone_pcs" name="inward_item['+i+'][precious_st_pcs]"/><input type="hidden" class="precious_stone_wt" name="inward_item['+i+'][precious_st_wt]"><input type="hidden" class="p_stn_certif_uploaded" name="inward_item['+i+'][p_stn_certif_uploaded]"><input type="hidden" class="precious_st_certif" name="inward_item['+i+'][precious_st_certif]"><input type="hidden" class="semi_precious_stn" name="inward_item['+i+'][semi_precious_stn]"/><input type="hidden" class="semi_precious_st_pcs" name="inward_item['+i+'][semi_precious_st_pcs]"/><input type="hidden" class="semi_precious_st_wt" name="inward_item['+i+'][semi_precious_st_wt]"><input type="hidden" class="sp_stn_certif_uploaded" name="inward_item['+i+'][sp_stn_certif_uploaded]"><input type="hidden" class="semiprecious_st_certif" name="inward_item['+i+'][semiprecious_st_certif]"><input type="hidden" class="normal_stn" name="inward_item['+i+'][normal_stn]"/><input type="hidden" class="normal_st_pcs" name="inward_item['+i+'][normal_st_pcs]"/><input type="hidden" class="normal_st_wt" name="inward_item['+i+'][normal_st_wt]"><input type="hidden" class="normal_st_certif" name="inward_item['+i+'][normal_st_certif]"><input type="hidden" class="n_stn_certif_uploaded" name="inward_item['+i+'][n_stn_certif_uploaded]"><input type="hidden" name="inward_item['+i+'][nor_wt_uom]" class="nor_wt_uom"><input type="hidden" name="inward_item['+i+'][semi_wt_uom]" class="semi_wt_uom"><input type="hidden" name="inward_item['+i+'][pre_wt_uom]" class="pre_wt_uom"></td>'
					+'<td><a href="#" onClick="remove_row($(this).closest(\'tr\'));" class="btn btn-danger"><i class="fa fa-trash"></i></a></td>'
					+'</tr>';
					$('#lt_item_list tbody').append(row);
					$('#lt_item_list > tbody').find('tr:last td:eq(0) .cat_product').focus();
					
					});
				}
				else
				{
					$('#errorMsg').css("display","block");
					$('#errorMsg').html(data.message);
				}
		}
		});
	}
}

function get_ActiveProduct() 
{
	$.ajax({
		url: base_url + 'index.php/admin_ret_estimation/get_ActiveProduct',
		data: ({ 'id_category': ''}),
		dataType: "JSON",
		type: "POST",
		success: function (data) {
			lot_product_details  = data;
		}
	});
}			

$(document).on('change','.lot_product',function()
{
	if(this.value!='')
	{
		var row = $(this).closest('tr');
		row.find('.pro_id').val(this.value);
		getActiveDesigns(row,this.value);
	}
});
$(document).on('change','.design',function()
{
	if(this.value!='')
	{
		var row = $(this).closest('tr');
		row.find('.des_id').val(this.value);
		get_ActivelotSubDesigns(row,this.value);
	}
});

$(document).on('change','.lot_sub_design',function()
{
	if(this.value)
	{
		var row = $(this).closest('tr');
		row.find('.lot_id_sub_design').val(this.value);
	}
});


function getActiveDesigns(curRow,pro_id)
{
	curRow.find('.design option').remove();
	my_Date = new Date();
	$.ajax({
		url: base_url+'index.php/admin_ret_catalog/get_active_design_products',           
        dataType: "json", 
        method: "POST", 
        data: {'id_product':pro_id}, 
		success: function (data) 
		{
			var design_id = curRow.find('.cus_des_id').val()
			$.each(data, function (key, item) {   
				$(curRow.find('.design')).append(
				$("<option></option>")
				.attr("value", item.design_no)    
				.text(item.design_name)  
				);
			});
			$(curRow.find('.design')).select2(
			{
				placeholder:"Select Design",
				allowClear: true		    
			});
			curRow.find('.design').select2("val",(design_id!=''&& design_id>0 ? design_id :""));
		}
	});
}

function get_ActivelotSubDesigns(curRow,des_id)
{
	curRow.find('.lot_sub_design option').remove();
	var pro_id = curRow.find('.lot_product').val();
	my_Date = new Date();
	$.ajax({
		url: base_url+'index.php/admin_ret_catalog/get_ActiveSubDesigns',           
        dataType: "json", 
        method: "POST", 
        data: {'id_product':pro_id,'design_no':des_id}, 
		success: function (data) 
		{
			var sub_design_id = curRow.find('.lot_id_sub_design').val()
			$.each(data, function (key, item) {   
				curRow.find('.lot_sub_design').append(
				$("<option></option>")
				.attr("value", item.id_sub_design)    
				.text(item.sub_design_name)  
				);
			});
			$(curRow.find('.lot_sub_design')).select2(
			{
				placeholder:"Select Sub Design",
				allowClear: true		    
			});

			curRow.find('.lot_sub_design').select2("val",(sub_design_id!=''&& sub_design_id>0 ? sub_design_id :""));

		}
	});

}

function remove_row(curRow)
{
	if(ctrl_page[2]!='edit')
	{
		curRow.remove();
		get_lot_preview();
	}
	else if(ctrl_page[1]=='lot_merge')
	{
		curRow.remove();
		TotalLotMerge();
	}
	else
	{
			$("#lot_inwards_detail").modal({
			backdrop: 'static',
			keyboard: false
			});
			var id_lot_inward_detail =  curRow.find('.id_lot_inward_detail').val();
			$('#delete_confirm').on('click',function(){
					my_Date = new Date();
					$.ajax({
					url: base_url+'index.php/admin_ret_lot/lot_inwards_detail/?nocache=' + my_Date.getUTCSeconds(),             
					dataType: "json", 
					method: "POST", 
					data: { 'id_lot_inward_detail': id_lot_inward_detail}, 
					success: function (data) {
							if(data.status==true)
							{
								$('#successMsg').css("display","block");
								$('#successMsg').html(data.message);
								curRow.remove();
								get_lot_preview();
							}
							else
							{
								$('#errorMsg').css("display","block");
								$('#errorMsg').html(data.message);
							}
							setTimeout(function(){
								$('#lot_inwards_detail').modal('toggle');
							},800);
					}
					});
			});	

	}
}
/*$('#delete_confirm').on('click',function(){
		my_Date = new Date();
		$.ajax({
		url: base_url+'index.php/admin_ret_lot/lot_inwards_detail/?nocache=' + my_Date.getUTCSeconds(),             
		dataType: "json", 
		method: "POST", 
		data: { 'id_lot_inward_detail': id_lot_inward_detail}, 
		success: function (data) {
		}
		});
});*/
$('#add_lot_item').on('click',function(){ 
	if($("#id_category").val() != "" && $("#id_purity").val() != "" && $("#lt_gold_smith_id").val() != ""){
		if(validateItemDetailRow()){
			create_new_empty_lot_row();
		}else{
			alert("Please fill required fields in current row");
			return false;
		}
	}else{
		alert("Please fill required fields");
	}
});
function validateItemDetailRow(){
	var row_validate = true;
	var stock_type=$('#stock_type:checked').val();
	$('#lt_item_list > tbody  > tr').each(function(index, tr) {
		// 1 - Fixed Rate, 2 - Fixed Rate based on Weight, 3 - Metal Rate
		if($(this).find('.sales_mode').val() == 1 ){ 
			if($(this).find('.calculation_based_on').val() == 3){
				if($(this).find('.pro_id').val() == ""  || $(this).find('.lot_pcs').val() == "" || $(this).find('.sell_rate').val() == ""){
					row_validate = false;
				}
			}
			else if($(this).find('.calculation_based_on').val() == 4){ 
				if($(this).find('.pro_id').val() == ""  || $(this).find('.lot_pcs').val() == "" || $(this).find('.sell_rate').val() == "" ||  $(this).find('.gross_wt').val() == ""){
					row_validate = false;
				}
			}
		}
		else if($(this).find('.sales_mode').val() == 2){
			if($(this).find('.pro_id').val() == ""  || $(this).find('.wastage_percentage').val() == "" || $(this).find('.lot_pcs').val() == "" || $(this).find('.gross_wt').val() == "" || $(this).find('.lot_nwt').val() == ""){
				row_validate = false;
			}
		}
		else{
			if($(this).find('.pro_id').val() == ""  || $(this).find('.wastage_percentage').val() == "" || $(this).find('.lot_pcs').val() == "" || $(this).find('.gross_wt').val() == "" || $(this).find('.lot_nwt').val() == ""){
				row_validate = false;
			}
		}
		if(($(this).find('.design').val()=="" || $(this).find('.design').val()==null || $(this).find('.lot_sub_design').val()=="" || $(this).find('.lot_sub_design').val()==null)&&(stock_type==2))
		{
		    row_validate = false;
		}
	});
	
	return row_validate;
} 
function b64toBlob(b64Data, contentType, sliceSize) {
        contentType = contentType || '';
        sliceSize = sliceSize || 512;
        var byteCharacters = atob(b64Data);
        var byteArrays = [];
        for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
            var slice = byteCharacters.slice(offset, offset + sliceSize);
            var byteNumbers = new Array(slice.length);
            for (var i = 0; i < slice.length; i++) {
                byteNumbers[i] = slice.charCodeAt(i);
            }
            var byteArray = new Uint8Array(byteNumbers);
            byteArrays.push(byteArray);
        }
      var blob = new Blob(byteArrays, {type: contentType});
      return blob;
}
function validateCertifImg(type,row_id)
 {
 	if(type == 'pre_images'){
		preview = 'uploadArea_p_stn';
	}
	else if(type == 'semi_pre_imgs'){
		preview = 'uploadArea_sp_stn';
	}
	else if(type == 'norm_pre_imgs'){
		preview = 'uploadArea_n_stn';
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
						pre_img_resource.push({'row_id':row_id,'src':event.target.result,'name':fileName});
						pre_img_files.push(file); 
						/*// Split the base64 string in data and contentType
						var ImageURL = event.target.result;
						var block = ImageURL.split(";");
						// Get the content type of the image
						var contentType = block[0].split(":")[1];// In this case "image/gif"
						// get the real base64 content of the file
						var realData = block[1].split(",")[1];// In this case "R0lGODlhPQBEAPeoAJosM...."
						// Convert it to a blob to upload
						var blob = b64toBlob(realData, contentType);
						// Create a FormData and append the file with "image" as parameter name
						var form = document.getElementById("lot_form");
						var formDataToUpload = new FormData(form);
						formDataToUpload.append("pre_image_test", blob);*/
					}
					else if(type == 'semi_pre_imgs'){ 
						sp_img_resource.push({'row_id':row_id,'src':event.target.result,'name':fileName});
						sp_img_files.push(file);  
					}
					else if(type == 'norm_pre_imgs'){ 
						n_img_resource.push({'row_id':row_id,'src':event.target.result,'name':fileName});
						n_img_files.push(file);  
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
		else if(type == 'semi_pre_imgs'){ 
			$('#pre_img div').remove();
			resource = sp_img_resource;
		}
		else if(type == 'norm_pre_imgs'){ 
			$('#pre_img div').remove();
			resource = n_img_resource; 
		}
		$.each(resource,function(key,item){
		   if(item.row_id == $("#row_id").val())
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
		if(ctrl_page[2] == 'edit' && param.preview == "uploadedArea_p_stn")
		remove_img(file,'certificates','precious_st_certif',id,imgs);
		console.log(pre_img_resource);
	}
	else if(param.stone_type == 'semi_pre_imgs'){ 
		$('#pre_img div').remove();
		sp_img_resource.splice(param.key,1);
		if(ctrl_page[2] == 'edit' && param.preview == "uploadedArea_p_stn")
		remove_img(file,'certificates','semiprecious_st_certif',id,imgs);
	}
	else if(param.stone_type == 'norm_pre_imgs'){ 
		$('#pre_img div').remove();
		n_img_resource.splice(param.key,1); 
		if(ctrl_page[2] == 'edit' && param.preview == "uploadedArea_p_stn")
		remove_img(file,'certificates','normal_st_certif',id,imgs);
	}  
	/*const index = pre_img_files.indexOf(pre_img_resource[id]);
	pre_img_files.splice(index,1);*/
 } 
 $('#update_stone_details').on('click',function(){
		// PRECIOUS
		var precious_stone 		= $('#precious_stone').val();
		var precious_st_pcs 	= $('#precious_st_pcs').val();
 		var precious_st_wt 		= $('#precious_st_wt').val();
 		var pre_wt_uom 			= $('#pre_wt_uom').val(); 
 		var curRow 				= $('#row_id').val();  
 		console.log('curRow : '+curRow);
 		console.log('precious_stone: '+precious_stone);
 		console.log('precious_st_pcs: '+precious_st_pcs);
 		console.log('precious_st_wt: '+precious_st_wt);
 		console.log('pre_img_resource: '+pre_img_resource);
  		var p_img_data			= []; 
 		$.each(pre_img_resource,function(key,item){ 
		   if(item.row_id == curRow)
		   {
		   		p_img_data.push(item);
		   }
		});  
		$('#'+curRow).find('.precious_st_certif').val(JSON.stringify(p_img_data)); 
	   	$('#'+curRow).find('.precious_stone_pcs').val(precious_st_pcs);
	   	$('#'+curRow).find('.precious_stone_wt').val(precious_st_wt);
	   	$('#'+curRow).find('.precious_stone').val(precious_stone);
	   	$('#'+curRow).find('.pre_wt_uom').val(pre_wt_uom);
	   	// SEMI PRECIOUS
	   	var semi_precious_stn 		= $('#semi_precious_stn').val();
		var semi_precious_st_pcs	= $('#semi_precious_st_pcs').val();
 		var semi_precious_st_wt		= $('#semi_precious_st_wt').val();
 		var semi_wt_uom				= $('#semi_wt_uom').val();
		var sp_img_data 			= [];
 		$.each(sp_img_resource,function(key,item){
		   if(item.row_id == curRow)
		   {
		   		sp_img_data.push(item);
		   }
		})		
		$('#'+curRow).find('.semiprecious_st_certif').val(JSON.stringify(sp_img_data));
	   	$('#'+curRow).find('.semi_precious_st_pcs').val(semi_precious_st_pcs);
	   	$('#'+curRow).find('.semi_precious_st_wt').val(semi_precious_st_wt);
	   	$('#'+curRow).find('.semi_precious_stn').val(semi_precious_stn);
	   	$('#'+curRow).find('.semi_wt_uom').val(semi_wt_uom);
	   	//Normal
	   	var normal_stn 		= $('#normal_stn').val();
		var normal_st_pcs	= $('#normal_st_pcs').val();
 		var normal_st_wt	= $('#normal_st_wt').val();
 		var nor_wt_uom		= $('#nor_wt_uom').val();
		var n_img_data 		= [];
 		$.each(n_img_resource,function(key,item){
		   if(item.row_id == curRow)
		   {
		   		n_img_data.push(item);
		   }
		}) 
		$('#'+curRow).find('.normal_st_certif').val(JSON.stringify(n_img_data));
	   	$('#'+curRow).find('.normal_st_pcs').val(normal_st_pcs);
	   	$('#'+curRow).find('.normal_st_wt').val(normal_st_wt);
	   	$('#'+curRow).find('.normal_stn').val(normal_stn);
	   	$('#'+curRow).find('.nor_wt_uom').val(nor_wt_uom);
	   	$('#stoneModal').modal('hide');
 });
function getSearchProducts(searchTxt, curRow){
    if(searchTxt.length>=2)
    {
        var stock_type=$('#stock_type:checked').val();
	my_Date = new Date();
	$.ajax({
        url: base_url+'index.php/admin_ret_lot/getProductBySearch/?nocache=' + my_Date.getUTCSeconds(),             
        dataType: "json", 
        method: "POST", 
        data: {'searchTxt': searchTxt,'cat_id' : $("#id_category").val(),'stock_type':stock_type}, 
        success: function (data) {
			$( ".lot_product" ).autocomplete(
			{
				source: data,
				select: function(e, i)
				{ 
					e.preventDefault();
					//var tax_percentage=[]; 
					curRow.find('.lot_product').val(i.item.label);
					curRow.find('.pro_id').val(i.item.value);
					curRow.find('.sales_mode').val(i.item.sales_mode);
					curRow.find('.calculation_based_on').val(i.item.calculation_based_on);
					if(i.item.sales_mode == 1){
						if(i.item.calculation_based_on == 3){
							curRow.find('.buy_rt_type').html("Per Piece");
							curRow.find('.sell_rt_type').html("Per Piece");
						}else if(i.item.calculation_based_on == 4){
							curRow.find('.buy_rt_type').html("Per Gram");
							curRow.find('.sell_rt_type').html("Per Gram");
						}
					}
					else{
						curRow.find('.buy_rt_type').html("");
						curRow.find('.sell_rt_type').html("");
					}
					curRow.find('.design').val("");
    				curRow.find('.des_id').val("");
					$('#lt_item_list > tbody').find('.design').focus();
				},
				change: function (event, ui) {
					if (ui.item === null) {
						console.log(1);
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
						curRow.find('td:eq(0) .lot_product').val("");
						curRow.find('td:eq(0) .pro_id').val("");
					}
		        },
				 minLength: 2,
			});
        }
     });
    }
}
function getSearchDesigns(searchTxt, curRow){
	console.log(curRow.find('.pro_id').val());
	my_Date = new Date();
	$.ajax({
        url: base_url+'index.php/admin_ret_estimation/getProductDesignBySearch/?nocache=' + my_Date.getUTCSeconds(),             
        dataType: "json", 
        method: "POST", 
        data: {'searchTxt': searchTxt,'ProCode' :curRow.find('.pro_id').val() }, 
        success: function (data) {
			$( ".design" ).autocomplete(
			{
				source: data,
				select: function(e, i)
				{ 
					e.preventDefault();
					var exist = false;
					$('#lt_item_list> tbody  > tr').each(function(index, tr) { 
					    if($(this).find('td:first .pro_id').val() == curRow.find('.pro_id').val() && $(this).find('td:eq(1) .des_id').val() == i.item.value){
                			exist = true;
                		}
					})
					if(!exist){
    					curRow.find('.design').val(i.item.label);
    					curRow.find('.des_id').val(i.item.value);
    					$('#lt_item_list > tbody').find('tr:last td:eq(2) .cat_qty').focus();
					}else{
					    alert("Already Same Product and design Exist.");
					    curRow.find('.design').val("");
					    curRow.find('.des_id').val("");
					}
				},
				change: function (event, ui) {
					if (ui.item === null) {
					    curRow.find('.design').val("");
					    curRow.find('.des_id').val("");
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
						curRow.find('.design').val("");
						curRow.find('.des_id').val("");
					}
		        },
				 minLength: 1,
			});
        }
     });
}
$('#add_more_lot').on('click',function(e){
	get_lot_preview();
});
function get_lot_preview()
{
	$("div.overlay").css("display", "block");
	var lot_preview_item=[]; 
	$('#lt_item_list> tbody  > tr').each(function(index, tr) {
		var lt_type_select=$('#lt_type_select').val();
		var lt_gold_smith_id=$('#lt_gold_smith_id').val();
		var stock_type=$('#stock_type:checked').val();
		var order_no=$('#lt_order_no').val();
	    var purity=($('#purity').select2('data')[0]!=undefined ? $('#purity').select2('data')[0].text:'-');
		var category=($('#category').select2('data')[0]!=undefined ? $('#category').select2('data')[0].text:'-');
		var lt_gold_smith=($('#lt_gold_smith').select2('data')[0]!= null ? $('#lt_gold_smith').select2('data')[0].text:'-');
		var gross_wt=parseFloat($(this).find('.gross_wt').val()).toFixed(3);
		var lot_lwt=parseFloat(isNaN($(this).find('.lot_lwt').val()) || $(this).find('.lot_lwt').val()=='' ? 0:$(this).find('.lot_lwt').val()).toFixed(3);
		var lot_nwt=parseFloat($(this).find('.lot_nwt').val()).toFixed(3);
		var precious_st_wt=$(this).find('.precious_stone_wt').val();
		var semi_precious_st_wt=$(this).find('.semi_precious_st_wt').val();
		var normal_st_wt=$(this).find('.normal_st_wt').val();
		var lot_pcs=$(this).find('.lot_pcs').val();
		lot_preview_item.push({
			'lt_type_select':(lt_type_select==1 ? 'Normal':(lt_type_select==2 ? 'Custom' :'Repair')),
			'category'			:category,
			'purity'			:purity,
			'stock_type'		:(stock_type==1 ?'Tagged' :'Non-Tagged'),
			'lt_gold_smith'		:lt_gold_smith,
			'order_no'			:order_no,
			'lot_pcs'			:lot_pcs,
			'gross_wt'			:gross_wt,
			'lot_lwt'			:lot_lwt,
			'lot_nwt'			:lot_nwt,
			'precious_st_wt'	:precious_st_wt,
			'semi_precious_st_wt':semi_precious_st_wt,
			'normal_st_wt'		 :normal_st_wt,
			});
	});
	console.log(lot_preview_item);
	set_lot_preview(lot_preview_item);
	$("div.overlay").css("display", "none"); 
}
function set_lot_preview(data)
{
        	var oTable = $('#lt_preview').DataTable();
        	oTable.clear().draw();
        	oTable = $('#lt_preview').dataTable({
        	"bDestroy": true,
        	"bInfo": true,
        	"bFilter": true,
        	"bSort": true,
        	"aaData": data,
        	"order": [[ 0, "desc" ]],
        	"aoColumns": [
        	{ "mDataProp": "lt_type_select" },
        	{ "mDataProp": "category" },				                
        	{ "mDataProp": "purity" },				                
        	{ "mDataProp": "stock_type" },				                
        	{ "mDataProp": "lt_gold_smith" },				                
        	{ "mDataProp": "order_no" },				                
        	{ "mDataProp": "lot_pcs" },				                
        	{ "mDataProp": "gross_wt" },				                
        	{ "mDataProp": "lot_lwt" },				                
        	{ "mDataProp": "lot_nwt" },				                
        	{ "mDataProp": "precious_st_wt" },				                
        	{ "mDataProp": "semi_precious_st_wt"},				                
        	{ "mDataProp": "normal_st_wt"},				                
        	],
        		"footerCallback": function( row, data, start, end, display )
        		{ 
            		if(data.length>0){
            			var api = this.api(), data;
            
            			for( var i=0; i<=data.length-1;i++){
            
            				var intVal = function ( i ) {
            					return typeof i === 'string' ?
            					i.replace(/[\$,]/g, '')*1 :
            					typeof i === 'number' ?
            					i : 0;
            				};	
            				
            			
            				tot_pcs = api
            				.column(6)
            				.data()
            				.reduce( function (a, b) {
            					return intVal(a) + intVal(b);
            				}, 0 );
            				$(api.column(6).footer()).html(parseFloat(tot_pcs).toFixed(2));	 
            				
            				tot_gwt = api
            				.column(7)
            				.data()
            				.reduce( function (a, b) {
            					return intVal(a) + intVal(b);
            				}, 0 );
            				$(api.column(7).footer()).html(parseFloat(tot_gwt).toFixed(2));	 
            				
            				tot_lwt = api
            				.column(8)
            				.data()
            				.reduce( function (a, b) {
            					return intVal(a) + intVal(b);
            				}, 0 );
            				$(api.column(8).footer()).html(parseFloat(tot_lwt).toFixed(2));	 
            				
            				tot_nwt = api
            				.column(9)
            				.data()
            				.reduce( function (a, b) {
            					return intVal(a) + intVal(b);
            				}, 0 );
            				$(api.column(9).footer()).html(parseFloat(tot_nwt).toFixed(2));	 
            				 
            				
            		} 
            		}else{
            			 var api = this.api(), data; 
            			 $(api.column(6).footer()).html('');
            			 $(api.column(7).footer()).html('');
            			 $(api.column(8).footer()).html('');
            			 $(api.column(9).footer()).html('');
            		}
            	}
        	});	
}
function clearInputs(){
	$("#precious_stone,#semi_precious_stn,#normal_stn").prop("checked", false);		
	$("#precious_stone,#semi_precious_stn,#normal_stn").val(0);
	$("#precious_stone_pcs,#semi_precious_st_pcs,#normal_st_pcs").attr("disabled",true);
	$("#precious_stone_pcs,#semi_precious_st_pcs,#normal_st_pcs").val();
	$("#precious_stone_wt,#semi_precious_st_wt,#normal_st_wt").attr("disabled",true);
	$("#precious_stone_wt,#semi_precious_st_wt,#normal_st_wt").val("");
	$("#uploadImg_p_stn,#uploadImg_sp_stn,#uploadImg_n_stn").attr("disabled",true);	
	$("#pre_images,#semi_pre_imgs,#norm_pre_imgs").val("");      
	$("#precious_st_certif,#semiprecious_st_certif,#normal_st_certif").val("");	
	$("#uploadedArea_p_stn,#uploadedArea_sp_stn,#uploadedArea_n_st").css("dispay","none");
	$("#uploadedArea_p_stn div,#uploadedArea_sp_stn div,#uploadedArea_n_stn div").remove();
	$("#uploadArea_p_stn,#uploadArea_sp_stn,#uploadArea_n_stn").css("dispay","none");
	$("#uploadArea_p_stn div,#uploadArea_sp_stn div,#uploadArea_n_stn div").remove();
}
function show_stone_modal(curRow,id)
{
	clearInputs();
	var img_url = base_url+'/assets/img/lot/'+ctrl_page[3]+'/certificates';
	$('#row_id').val(id); 
	var precious_stone 	= curRow.find('.precious_stone').val();
	var precious_st_pcs = curRow.find('.precious_stone_pcs').val();
	var precious_st_wt 	= curRow.find('.precious_stone_wt').val();
	var pre_wt_uom 		= curRow.find('.pre_wt_uom').val();
	var p_stn_certif_uploaded= curRow.find('.p_stn_certif_uploaded').val();
	var semi_precious_stn 		= curRow.find('.semi_precious_stn').val(); 
	var semi_precious_st_pcs	= curRow.find('.semi_precious_st_pcs').val();
	var semi_precious_st_wt		= curRow.find('.semi_precious_st_wt').val();
	var semi_wt_uom				= curRow.find('.semi_wt_uom').val();
	var sp_stn_certif_uploaded	= curRow.find('.sp_stn_certif_uploaded').val();
	var normal_stn 		= curRow.find('.normal_stn').val(); 
	var normal_st_pcs	= curRow.find('.normal_st_pcs').val();
	var normal_st_wt	= curRow.find('.normal_st_wt').val();
	var nor_wt_uom		= curRow.find('.nor_wt_uom').val(); 
	var n_stn_certif_uploaded= curRow.find('.n_stn_certif_uploaded').val(); 
	$('#stoneModal').modal('show');
	$('#precious_st_wt').val(precious_st_wt);
	$('#semi_precious_st_wt').val(semi_precious_st_wt);
	$('#normal_st_wt').val(normal_st_wt);
	$('#precious_st_pcs').val(precious_st_pcs);
	$('#semi_precious_st_pcs').val(semi_precious_st_pcs);
	$('#normal_st_pcs').val(normal_st_pcs);
	if(precious_stone == 1){
		$("#precious_stone").prop("checked", true);
		$("#precious_stone").val(1); 
		$("#uploadImg_p_stn").prop("disabled",false); 
		$("#precious_stone_pcs").attr("disabled",false);
		$("#precious_stone_wt").attr("disabled",false);
		var p_imgs = [];
		if(p_stn_certif_uploaded != ''){
			var p_imgs = p_stn_certif_uploaded.split('#');		
			if(p_imgs.length > 0){
				$('#uploadedArea_p_stn').css('display','block');
			}
		}
		$.each(p_imgs,function(key,item){
		   if(item)
		   {  
		   		var src = img_url+'/'+item;
				var div = document.createElement("div");
				div.setAttribute('class','col-md-4'); 
				div.setAttribute('id',+key); 
				param = {"key":key,"preview":"uploadedArea_p_stn","stone_type":"pre_images"}; 
				div.innerHTML+= "<a onclick='remove_stn_img("+JSON.stringify(param)+")'><i class='fa fa-trash'></i>Delete</a><img class='thumbnail' src='" + src + "'" +
				"style='width: 100px;height: 100px;'/>";  
				$('#uploadedArea_p_stn').append(div);
		   }
		   $('#lot_img_upload').css('display','');
		});
		$.each(pre_img_resource,function(key,item){
		   if(item.row_id == id)
		   {   
		   		var div = document.createElement("div");
				div.setAttribute('class','col-md-4'); 
				div.setAttribute('id',+key); 
				param = {"key":key,"preview":"uploadArea_p_stn","stone_type":"pre_images"};
				div.innerHTML+= "<a onclick='remove_stn_img("+JSON.stringify(param)+")'><i class='fa fa-trash'></i>Delete</a><img class='thumbnail' src='" + item.src + "'" +
				"style='width: 100px;height: 100px;'/>";  
				$('#uploadArea_p_stn').append(div);
		   }
		   $('#lot_img_upload').css('display','');
		});
	}
	if(semi_precious_stn == 1){
		$("#semi_precious_stn").val(1); 
		$("#semi_precious_stn").prop("checked", true); 
		$("#uploadImg_sp_stn").prop("disabled",false);
		$("#semi_precious_st_pcs,#semi_precious_st_wt").attr("disabled",false);
		var sp_imgs = []
		if(sp_stn_certif_uploaded != ''){
			var sp_imgs = sp_stn_certif_uploaded.split('#');
			if(sp_imgs.length > 0){
				$('#uploadedArea_sp_stn').css('display','block');
			}
		} 
		$.each(sp_imgs,function(key,item){
		   if(item)
		   {  
		   		var src = img_url+'/'+item;
				var div = document.createElement("div");
				div.setAttribute('class','col-md-4'); 
				div.setAttribute('id',+key); 
				param = {"key":key,"preview":"uploadedArea_sp_stn","stone_type":"semi_pre_imgs"}; 
				div.innerHTML+= "<a onclick='remove_stn_img("+JSON.stringify(param)+")'><i class='fa fa-trash'></i>Delete</a><img class='thumbnail' src='" + src + "'" +
				"style='width: 100px;height: 100px;'/>";  
				$('#uploadedArea_sp_stn').append(div);
		   }
		   $('#lot_img_upload').css('display','');
		});
		$.each(sp_img_resource,function(key,item){
		   if(item.row_id == id)
		   {  
		   		var div = document.createElement("div");
				div.setAttribute('class','col-md-4'); 
				div.setAttribute('id',+key); 
				param = {"key":key,"preview":"uploadArea_sp_stn","stone_type":"semi_pre_images"};
				//div.innerHTML+= "<a onclick='remove_stn_img('"+key+"','"+preview+"','"+type+"')'><i class='fa fa-trash'></i>Delete</a><img class='thumbnail' src='" + item.src + "'" +
				div.innerHTML+= "<a onclick='remove_stn_img("+JSON.stringify(param)+")'><i class='fa fa-trash'></i>Delete</a><img class='thumbnail' src='" + item.src + "'" +
				"style='width: 100px;height: 100px;'/>";  
				$('#uploadArea_sp_stn').append(div);
		   }
		   $('#lot_img_upload').css('display','');
		});
	}
	if(normal_stn == 1){
		$("#normal_stn").val(1);
		$("#normal_stn").prop("checked", true);
		$("#uploadImg_n_stn").prop("disabled",false);
		$("#normal_st_pcs,#normal_st_wt").attr("disabled",false);
		var n_imgs = [];
		if(n_stn_certif_uploaded != ''){
			var n_imgs = n_stn_certif_uploaded.split('#');
			if(n_imgs.length > 0){
				$('#uploadedArea_n_stn').css('display','block');
			}
		}		 
		$.each(n_imgs,function(key,item){
		   if(item)
		   {  
		   		var src = img_url+'/'+item;
				var div = document.createElement("div");
				div.setAttribute('class','col-md-4'); 
				div.setAttribute('id',+key); 
				param = {"key":key,"preview":"uploadedArea_n_stn","stone_type":"norm_pre_imgs"}; 
				div.innerHTML+= "<a onclick='remove_stn_img("+JSON.stringify(param)+")'><i class='fa fa-trash'></i>Delete</a><img class='thumbnail' src='" + src + "'" +
				"style='width: 100px;height: 100px;'/>";  
				$('#uploadedArea_n_stn').append(div);
		   }
		   $('#lot_img_upload').css('display','');
		});
		$.each(n_img_resource,function(key,item){
		   if(item.row_id == id)
		   {  
		   		var div = document.createElement("div");
				div.setAttribute('class','col-md-4'); 
				div.setAttribute('id',+key); 
				param = {"key":key,"preview":"uploadArea_n_stn","stone_type":"norm_pre_imgs"};
				//div.innerHTML+= "<a onclick='remove_stn_img('"+key+"','"+preview+"','"+type+"')'><i class='fa fa-trash'></i>Delete</a><img class='thumbnail' src='" + item.src + "'" +
				div.innerHTML+= "<a onclick='remove_stn_img("+JSON.stringify(param)+")'><i class='fa fa-trash'></i>Delete</a><img class='thumbnail' src='" + item.src + "'" +
				"style='width: 100px;height: 100px;'/>";  
				$('#uploadArea_n_stn').append(div);
		   }
		   $('#lot_img_upload').css('display','');
		});
	}
}


function get_karigar_by_order(order_no){	 
	$.ajax({		
	 	type: 'POST',		
	 	url: base_url+'index.php/admin_ret_lot/get_karigar_list',		
	 	dataType:'json',
	 	data :{'order_no':order_no},		
	 	success:function(data){	
	 		$('#lt_gold_smith option').remove();			 
	 		$('#category option').remove();			 
	 		$('#purity option').remove();			 
		 	

		 	if(data.karigar.length==1)
		 	{
		 		$('#lt_gold_smith_id').val(data.karigar[0]['id_karigar']);
		 	}
		 	if(data.category.length==1)
		 	{
		 		$('#id_category').val(data.category[0]['id_ret_category']);
		 	}
		 	if(data.purity.length==1)
		 	{
		 		$('#id_purity').val(data.purity[0]['id_purity']);
		 	}

		 	var id =  $('#lt_gold_smith_id').val();	
		 	var id_purity =  $('#id_purity').val();
		 	var id_category =  $('#id_category').val();

		 	$.each(data.karigar, function (key, item) {			  				  			   		
	    	 	$("#lt_gold_smith").append(						
	    	 	$("<option></option>")						
	    	 	.attr("value", item.id_karigar)						  						  
	    	 	.text(item.karigar+' '+ item.code)						  					
	    	 	);	 
	     	});						
	     	$("#lt_gold_smith").select2("val",(id!='' && id>0?id:''));	

			$.each(data.category, function (key, item) {
				$('#category').append(
				$("<option></option>")
				.attr("value", item.id_ret_category)
				.text(item.name)
				);
			});
			$("#category").select2("val",(id_category!='' && id_category>0?id_category:''));

	      
			$.each(data.purity, function (key, item) {
				$('#purity').append(
				$("<option></option>")
				.attr("value", item.id_purity)
				.text(item.purity)
				);
			});
			$("#purity").select2({
			    placeholder: "Select Purity",
			    allowClear: true
			});
			$("#purity").select2("val",(id_purity!='' && id_purity>0?id_purity:''));	

	     	$(".overlay").css("display", "none");			
	 	}	
	}); 
} 


/*Lot Merge Module 
-- Created By vijay --
-- Created On 14/04/23 --*/


$('.lot_no_search').on('click',function()
{
	if($('#lot_no').val()=='')
	{
		$.toaster({ priority : 'danger', title : 'Warning!', message : "Please Enter Lot No..."});
	}
	else
	{
		getLotNoForMerge();
	}
	$('#lot_search_list > tbody').empty();
	TotalLotMerge();
});

function getLotNoForMerge()
{
	my_Date = new Date();
		$.ajax({ 
		url:base_url+ "index.php/admin_ret_lot/lot_merge/getLotNos",
        type:"POST",
        dataType:"JSON",
		data:{'lot_no':$('#lot_no').val(),'stock_type':$('#stock_type:checked').val()},
        success:function(data)
        {
			if(data!='')
			{
				var trHtml='';
				var a = $("#curRow").val();
				var i = ++a;
				$("#curRow").val(i);
				rowExist=false;
				
				$('#lot_det > tbody tr').each(function(bidx, brow){
					lot_id = $(this);
					if(lot_id.find('.lot_id').val() != '')
					{
						if( data[0].lot_no == lot_id.find('.lot_id').val()){
							rowExist = true;
						$.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Lot Already Exists..'});
						} 
					}
				});

				

				if(!rowExist)
				{
					
					$.each(data,function(key,item)
					{
						var stone_lot_details = [];

						$.each(item.stone_details,function(key,stn)
						{
							stone_lot_details.push({"stone_id":stn.stone_id,"uom_id":stn.uom_id,
							"stn_pcs":stn.stn_pcs,"stn_wt":stn.stn_wt});
						});
									
						trHtml +='<tr id='+i+'>'
							+'<td class="l_lot_no">'+item.lot_no+'<input type="hidden" class="lot_id" name="lot_merge['+i+'][lot_id]" value="'+item.lot_no+'"></td>'
							+'<td class="l_lot_no">'+item.id_lot_inward_detail+'<input type="hidden" class="lot_det_id" name="lot_merge['+i+'][lot_det_id]" value="'+item.id_lot_inward_detail+'"></td>'
							+'<td class="l_lot_pro">'+item.product_name+'</td>'
							+'<td class="l_lot_pcs">'+item.no_of_piece+'</td>'
							+'<td class="l_lot_wt">'+item.gross_wt+'</td>'
							+'<td class="l_lot_stnpcs">'+item.stn_pcs+'<input type="hidden" class="stone_details" name="lot_merge['+i+'][stone_details]" value='+JSON.stringify(stone_lot_details)+'></td>'
							+'<td class="l_lot_stnwt">'+item.stn_wt+'</td>'
							+'<td class="l_lot_diapcs">'+item.dia_pcs+'</td>'
							+'<td class="l_lot_diawt">'+item.dia_wt+'</td>'
							+'<td><a href="#" onClick="remove_lot_row($(this).closest(\'tr\'));" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'
						+'</tr>';
					});
					$('#lot_det tbody').append(trHtml);
				}
			}
			else
			{
				$.toaster({ priority : 'danger', title : 'Warning!', message : "No Records Found..."});
			}
			$('#lot_no').val('');
			calculateLotTotal();
		}
	});
}

function remove_lot_row(curRow)
{	
	curRow.remove();
	calculateLotTotal();
}

function calculateLotTotal()
{
	var tot_lot_pcs = 0;
	var tot_lot_wgt = 0;

	var tot_stn_pcs = 0;
	var tot_stn_wt = 0;

	var tot_dia_pcs = 0;
	var tot_dia_wt = 0;
	
	$("#lot_det tbody tr").each(function (index, value) 
	{
		var row = $(this).closest('tr');
		tot_lot_pcs = tot_lot_pcs + parseFloat(row.find('.l_lot_pcs').html());
		tot_lot_wgt = tot_lot_wgt + parseFloat(row.find('.l_lot_wt').html());
		
		tot_stn_pcs = tot_stn_pcs + parseFloat(row.find('.l_lot_stnpcs').html());
		tot_stn_wt = tot_stn_wt + parseFloat(row.find('.l_lot_stnwt').html());

		tot_dia_pcs = tot_dia_pcs + parseFloat(row.find('.l_lot_diapcs').html());
		tot_dia_wt = tot_dia_wt + parseFloat(row.find('.l_lot_diawt').html());
	});

	$('.tot_lot_pcs').html(tot_lot_pcs);
	$('.tot_lot_wgt').html(parseFloat(tot_lot_wgt).toFixed(3));

	$('.tot_stn_pcs').html(tot_stn_pcs);
	$('.tot_stn_wt').html(parseFloat(tot_stn_wt).toFixed(3));

	$('.tot_dia_pcs').html(tot_dia_pcs);
	$('.tot_dia_wt').html(parseFloat(tot_dia_wt).toFixed(3));
}

$('#gold_smith').on('change',function()
{
	if(this.value!='')
	{
		$('#id_gold_smith').val(this.value);
	}
	else
	{
		$('#id_gold_smith').val('');
	}
});


$(document).on('keyup','.lm_pcs,.gross_wt',function()
{
	TotalLotMerge();
});

function TotalLotMerge()
{
	var lot_pcs = 0;
	var lot_wgt = 0;
	$("#lot_search_list tbody tr").each(function (index, value) 
	{
		var row = $(this).closest('tr');
		lot_pcs = lot_pcs + (isNaN(row.find('.lm_pcs').val()) ? 0 :parseFloat(row.find('.lm_pcs').val()));
		lot_wgt = lot_wgt + (isNaN(parseFloat(row.find('.gross_wt').val())) ? 0 :parseFloat(row.find('.gross_wt').val()));
		console.log(lot_wgt);
	});

	$('.lot_tot_pcs').html(lot_pcs);
	$('.lot_tot_gwt').html(parseFloat(lot_wgt).toFixed(3));
}


$(document).on('change','.lm_cat',function()
{
	var row = $(this).closest('tr');
	if(this.value!='')
	{
		row.find('.lm_cat_id').val(this.value);
		get_ProductsForlot(row,this.value);
		get_purityForlot(row,this.value);
	}

})

function get_ProductsForlot(curRow,cat_id)
{
	var stock_type = $('#stock_type:checked').val();
	$.ajax({
		url: base_url + 'index.php/admin_ret_lot/get_ActiveProduct',
		data: ({ 'id_category':cat_id}),
		dataType: "JSON",
		type: "POST",
		success: function (data) 
		{
			curRow.find('.lm_pro option').remove();
			var pro_id = curRow.find('.lm_pro_id').val()
			$.each(data, function (key, item) 
			{
				if(item.stock_type == 1 && stock_type == 1)
				{
					$(curRow.find('.lm_pro')).append(
					$("<option></option>")
					.attr("value", item.pro_id)    
					.text(item.product_name)  
					);
				}
				else if(item.stock_type == 2 && stock_type == 2)	
    			{   
					$(curRow.find('.lm_pro')).append(
					$("<option></option>")
					.attr("value", item.pro_id)    
					.text(item.product_name)  
					);
				}
				
			});
			$(curRow.find('.lm_pro')).select2(
			{
				placeholder:"Select Product",
				allowClear: true		    
			});
			curRow.find('.lm_pro').select2("val",(pro_id!=''&& pro_id>0 ? pro_id :""));

		}
	});
}



function get_purityForlot(curRow,cat_id)
{
	curRow.find('.lm_purity option').remove();
	$.ajax({
		type: 'POST',
		url: base_url+'index.php/admin_ret_catalog/category/cat_purity',
		dataType:'json',
		data: {
			'id_category' :cat_id
		},
		success:function(data){
		  var id_purity =  curRow.find('.lm_id_purity').val();
		   $.each(data, function (key, item) {
			   		curRow.find('.lm_purity').append(
						$("<option></option>")
						  .attr("value", item.id_purity)
						  .text(item.purity)
					);
			});
			curRow.find('.lm_purity').select2({
			    placeholder: "Select Purity",
			    allowClear: true
			});
			curRow.find('.lm_purity').select2("val",(id_purity!='' && id_purity>0?id_purity:''));
			 $(".overlay").css("display", "none");	
		}
	});
}

$(document).on('change','.lm_pro',function()
{
	if(this.value!='')
	{
		var row = $(this).closest('tr');
		row.find('.lm_pro_id').val(this.value);
		get_ActiveDesignsForLot(row,this.value);
	}
});

$(document).on('change','.lm_des',function()
{
	if(this.value!='')
	{
		var row = $(this).closest('tr');
		row.find('.lm_des_id').val(this.value);
	}
});


function get_ActiveDesignsForLot(curRow,pro_id)
{
	curRow.find('.lm_des option').remove();
	my_Date = new Date();
	$.ajax({
		url: base_url+'index.php/admin_ret_catalog/get_active_design_products',           
        dataType: "json", 
        method: "POST", 
        data: {'id_product':pro_id}, 
		success: function (data) 
		{
			var design_id = curRow.find('.lm_des_id').val()
			$.each(data, function (key, item) {   
				$(curRow.find('.lm_des')).append(
				$("<option></option>")
				.attr("value", item.design_no)    
				.text(item.design_name)  
				);
			});
			$(curRow.find('.lm_des')).select2(
			{
				placeholder:"Select Design",
				allowClear: true		    
			});
			curRow.find('.lm_des').select2("val",(design_id!=''&& design_id>0 ? design_id :""));
		}
	});
}

$('#lot_merge_submit').on('click',function()
{
	if(ValidateTotLots())
	{
		if($("#id_gold_smith").val() != "")
		{
			if(validateLotMergeRow())
			{
				$('#lot_merge_form').submit();
				window.location.href= base_url+'index.php/admin_ret_lot/lot_inward/list';
				window.location.reload();
			}
			else
			{
				$.toaster({ priority : 'danger', title : 'Warning!', message : "Please fill All Required Fields..."});

			}			
		}
		else
		{
			$.toaster({ priority : 'danger', title : 'Warning!', message : "Please Select Karigar..."});
		}		
	}
	else
	{
		$.toaster({ priority : 'danger', title : 'Warning!', message : "Total Pcs & Total wgt Must Be equal..."});
	}
});

function ValidateTotLots()
{
	var totalvalidate = false;
	var total_lotted_pcs = $('.tot_lot_pcs').html();
	var total_lotted_wgt = $('.tot_lot_wgt').html();
	var tot_lotNew_pcs = $('.lot_tot_pcs').html();
	var tot_lotNew_wgt = $('.lot_tot_gwt').html();

	if((total_lotted_pcs == tot_lotNew_pcs) && (total_lotted_wgt == tot_lotNew_wgt))
	{
		totalvalidate = true;
	}
	console.log('total_lotted_pcs',total_lotted_pcs);
	console.log('total_lotted_wgt',total_lotted_wgt);
	console.log('------------------');
	console.log('tot_lotNew_pcs',tot_lotNew_pcs);
	console.log('tot_lotNew_wgt',tot_lotNew_wgt);
	return totalvalidate;
}

$('#add_lot_merge').on('click',function()
{
	if($('.tot_lot_pcs').html()!='' && $('.tot_lot_wgt').html()!='')
	{
		create_new_lot_merge_row();
	}
	else
	{
		$.toaster({ priority : 'danger', title : 'Warning!', message : "No lot Details founded to merge..."});
	}
});


function validateLotMergeRow()
{
	var row_validate = true;
	$('#lot_search_list > tbody > tr').each(function(index, tr) 
	{		 		
		if($(this).find('.lm_pro').val() == "" || $(this).find('.lm_pro').val() ==null 
		|| $(this).find('.lm_purity').val() == "" || $(this).find('.lm_purity').val() == null
		|| $(this).find('.lm_pcs').val() == "" || $(this).find('.gross_wt').val()=="")
		{
			row_validate = false;
		}

	});	
	return row_validate;
} 



function create_new_lot_merge_row()
{
	$('#lot_search_list tbody').empty();
	var row = "";
	var stock_type = $('#stock_type:checked').val();
	var a = $("#curRow").val();
	var i = ++a;
	$("#curRow").val(i);
	var uom='';

	var net_wt = parseFloat(parseFloat($('.tot_lot_wgt').html()) - (parseFloat($('.tot_stn_wt').html()) + parseFloat($('.tot_dia_wt').html())));

	console.log('net_wt',net_wt);
	
	$.each(uom_details,function(key,item){
		uom += "<option value='"+item.uom_id+"'>"+item.code+"</option>";
	});
	row += '<tr id='+i+'>'
		
		+'<td><select class="lm_cat" name="merge_item['+i+'][lm_cat]" value="" placeholder="Search Category" style="width:150px;"><input type="hidden" class="cat_id" name="merge_item['+i+'][lm_cat_id]" value="" /><input type="hidden" class="sales_mode" name="merge_item['+i+'][sales_mode]" value="" /><input type="hidden" class="calculation_based_on" name="merge_item['+i+'][calculation_based_on]" value="" /<input type="hidden" class="id_lot_inward_detail" id="id_lot_inward_detail" value=""></td>'

		+'<td><select class="lm_pro" name="merge_item['+i+'][lm_pro]" value="" placeholder="Search Product" style="width:150px;"><input type="hidden" class="lm_pro_id" name="merge_item['+i+'][lm_pro_id]" value="" /><input type="hidden" class="sales_mode" name="merge_item['+i+'][sales_mode]" value="" /><input type="hidden" class="calculation_based_on" name="merge_item['+i+'][calculation_based_on]" value="" /<input type="hidden" class="id_lot_inward_detail" id="id_lot_inward_detail" value=""></td>'

		+'<td><select class="lm_purity" name="merge_item['+i+'][lm_purity]" value="" placeholder="Search Purity" style="width:150px;"><input type="hidden" class="lm_id_purity" name="merge_item['+i+'][lm_id_purity]" value="" /></td>'

		+'<td><input type="number" step="any" value="'+$('.tot_lot_pcs').html()+'" name="merge_item['+i+'][pcs]" class="lm_pcs" style="width:60px;" readonly></td>'

		+'<td><div class="input-group"><input type="number" step="any"  name="merge_item['+i+'][gross_wt]" value="'+$('.tot_lot_wgt').html()+'" class="gross_wt" style="width:80px;" readonly><span class="input-group-addon input-sm no-padding" style="background-color: transparent;height: 12px;font-size: 16px;border: none;"><select class="gross_wt_uom" name="merge_item['+i+'][gross_wt_uom]">'+uom+'</select></span></div></td>'
	
		+'<td><input type="number" step="any"  name="merge_item['+i+'][stn_pcs]" value="'+$('.tot_stn_pcs').html()+'" class="lm_stn_pcs" style="width:60px;" readonly></td>'

		+'<td><div class="input-group"><input type="number" step="any" value="'+$('.tot_stn_wt').html()+'" name="merge_item['+i+'][stn_wt]" class="stn_wt" style="width:80px;" readonly></div></td>'

		+'<td><input type="number" step="any" value="'+$('.tot_dia_pcs').html()+'" name="merge_item['+i+'][dia_pcs]" class="lm_dia_pcs" style="width:60px;" readonly></td>'

		+'<td><div class="input-group"><input type="number" step="any" value="'+$('.tot_dia_wt').html()+'" name="merge_item['+i+'][dia_wt]" class="dia_wt" style="width:80px;" readonly></div></td>'

		+'<td><div class="input-group"><input type="number" step="any" value="'+net_wt+'" name="merge_item['+i+'][net_wt]" class="lot_nwt" style="width:80px;" readonly><span class="input-group-addon input-sm no-padding" style="background-color: transparent;height: 12px;font-size: 16px;border: none;"><select class="net_wt_uom" name="merge_item['+i+'][net_wt_uom]">'+uom+'</select></span></div></td>'	

	+'</tr>';

	$('#lot_search_list tbody').append(row);

	$('.lm_cat').append(
		$("<option></option>")
		.attr("value", "")    
		.text('-Choose-')  
	);
	
	$.each(lot_cat_details, function (key, item)
	{
		$('.lm_cat').append(
		$("<option></option>")
		.attr("value", item.id_ret_category)    
		.text(item.name)  
		);
	}); 

	$('#lot_search_list > tbody').find('.lm_cat').select2();
	$('#lot_search_list > tbody').find('.lm_pro').select2();
	$('#lot_search_list > tbody').find('.lm_des').select2();
	$('#lot_search_list > tbody').find('.lm_purity').select2();
		
	$('#lot_search_list > tbody').find('.lm_cat').select2({
		placeholder: "Category",
		allowClear: true
	});
	
	$('#lot_search_list > tbody').find('.lm_pro').select2({
		placeholder: "Product",
		allowClear: true
	});
	
	$('#lot_search_list > tbody').find('.lm_des').select2({
		placeholder: "Design",
		allowClear: true
	});

	$('#lot_search_list > tbody').find('.lm_purity').select2({
		placeholder: "Purity",
		allowClear: true
	});
	$('#lot_search_list > tbody').find('tr:last td:eq(0) .lm_cat').focus();

	TotalLotMerge();
}

/*Lot Merge Module 
-- Created By vijay --
-- Created On 14/04/23 --*/


/*Lot Split Module
-- Created By vijay --
-- Created On 14/04/23 --*/


$('.lot_split_search').on('click',function()
{
	get_LotNos_for_split();
})


function get_LotNos_for_split()
{
	$.ajax({
		type: 'POST',
		url: base_url+'index.php/admin_ret_lot/lot_split/lotNosForsplit',
		dataType:'json',
		data: {
			'lot_no' :$('#lot_no').val()
		},
		success:function(data)
		{	
			if(data!='')
			{
				var row = "";
				var stock_type = $('#stock_type:checked').val();
				var a = $("#curRow").val();
				var i = ++a;
				$("#curRow").val(i);
				rowExist=false;
				
				$('#lot_split_list > tbody tr').each(function(bidx, brow){
					lot_id = $(this);
					if(lot_id.find('.lot_no').val() != '')
					{
						if( data[0].lot_no == lot_id.find('.lot_no').val()){
							rowExist = true;
						$.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Lot Already Exists..'});
						} 
					}
				});
				if(!rowExist)
				{

					$.each(data,function(key,val)
					{
						var stone_lot_details = [];
						var stone_wgt = 0;

						$.each(val.stone_details,function(key,stn)
						{
							stone_wgt += parseFloat(stn.stn_wt);
							stone_lot_details.push({	
								"id_stn_detail":stn.id_stn_detail,
								"stone_name"    :stn.stone_name,
								"uom_name"      :stn.uom_short_code,
								"stone_id"      :stn.stone_id,
								"uom_id"        :stn.uom_id,
							    "stn_pcs"       :stn.stn_pcs,
								"stn_wt"        :stn.stn_wt
							});
						});

						row += '<tr id='+i+'>'

							+'<td>'+val.lot_no+'<input type="hidden" class="lot_no" name="split_item['+i+'][lot_no]" value="'+val.lot_no+'"></td>'

							+'<td>'+val.id_lot_inward_detail+'<input type="hidden" class="id_lot_inward_detail" name="split_item['+i+'][id_lot_inward_detail]" value="'+val.id_lot_inward_detail+'"></td>'

							+'<td class="cat_name">'+val.category+'<input type="hidden" class="cat_id" name="split_item['+i+'][cat_id]" value="'+val.id_category+'"></td>'

							+'<td class="pro_name">'+val.product_name+'<input type="hidden" class="pro_id" name="split_item['+i+'][pro_id]" value="'+val.lot_product+'"></td>'

							+'<td class="purity">'+val.purity+'<input type="hidden" class="id_purity" name="split_item['+i+'][id_purity]" value="'+val.id_purity+'"></td>'

							+'<td><input type="number" step="any"  name="split_item['+i+'][lot_pcs]" value="'+val.bal_piece+'" class="form-control lot_pcs" style="width:100px;" readonly></td>'

							+'<td><input type="number" step="any"  name="split_item['+i+'][lot_wt]" value="'+val.bal_gross_wt+'" class="form-control lot_wt" style="width:100px;" readonly></td>'

							+'<td><input type="number" step="any"  name="split_item['+i+'][split_pcs]" value="" class="form-control split_pcs" style="width:100px;"></td>'

							+'<td><input type="number" step="any"  name="split_item['+i+'][split_wt]" value="" class="form-control split_wt" style="width:100px;"></td>'

							+'<td><div class="form-group" style="width: 100px;"><div class="input-group "><input type="number" class="form-control custom-inp less_wt" name="split_item['+i+'][less_wt]" value="'+stone_wgt+'" style="width:100px;" step="any" readonly><span class="input-group-addon input-sm add_tag_lwt" onClick="create_new_empty_stone_row($(this).closest(\'tr\'));">+</span></div><input type="hidden" class="stone_details" name="split_item['+i+'][stone_details]" value='+JSON.stringify(stone_lot_details)+'></div></td>'

						+'</tr>';
					});
				}

				$('#lot_split_list tbody').append(row);
			}
			else
			{
				$.toaster({ priority : 'danger', title : 'Warning!', message : "No Records Found..."});
			}

			$('#lot_no').val("");
		}
	});
}

$(document).on('change','.split_pcs',function()
{
	var row = $(this).closest('tr');

	var tot_lot_pcs = parseFloat(row.find('.lot_pcs').val());

	if(this.value > tot_lot_pcs)
	{
		row.find('.split_pcs').val('');
		$.toaster({ priority : 'danger', title : 'Warning!', message : "Split Pcs must be lesser than actually Pcs..."});
	}
	else
	{
		row.find('.split_pcs').val(this.value);
	}
})

$(document).on('change','.split_wt',function()
{
	var row = $(this).closest('tr');

	var tot_lot_wt = parseFloat(row.find('.lot_wt').val());

	if(this.value > tot_lot_wt)
	{
		row.find('.split_wt').val('');
		$.toaster({ priority : 'danger', title : 'Warning!', message : "Split Wgt must be lesser than actually Wgt..."});
	}
	else
	{
		row.find('.split_wt').val(this.value);
	}
})

function create_new_empty_stone_row(curRow)
{
	$('#lotsplit_stone_details tbody').empty();
	console.log(curRow.find('.stone_details').val());
	if(curRow!=undefined)
	{
		$('#lot_active_id').val(curRow.closest('tr').attr('id'));
	}

	var lotRow=$('#lot_active_id').val();

	var row_st_details=$('#'+lotRow).find('.stone_details').val();

	var row = "";

	if(row_st_details !='' && row_st_details != '[]' && curRow != undefined)
	{
		var stone_details   = JSON.parse(row_st_details);

		console.log(stone_details);

		$.each(stone_details, function (pkey, pitem) 
		{
			row += '<tr>'

			+'<td>'+pitem.stone_name+'<input type="hidden" class="id_stn_detail" value="'+pitem.id_stn_detail+'"><input type="hidden" class="stone_name" name="lot_stones_item[stone_name][]" value="'+pitem.stone_name+'"><input type="hidden" class="stone_id" name="lot_stones_item[stone_id][]" value="'+pitem.stone_id+'"></td>'

			+'<td>'+pitem.uom_name+'<input type="hidden" class="uom_name" name="lot_stones_item[uom_name][]" value="'+pitem.uom_name+'"><input type="hidden" class="uom_id" name="lot_stones_item[uom_id][]" value="'+pitem.uom_id+'"></td>'

			+'<td><input type="number" class="lot_stone_pcs form-control" name="lot_stones_item[lot_stone_pcs][]" value="'+pitem['stn_pcs']+'" style="width: 100%;" readonly/></td>'

			+'<td><input type="number" class="lot_stone_wt form-control" name="lot_stones_item[lot_stone_wt][]" value="'+pitem['stn_wt']+'" style="width: 100%;" readonly/></td>'

			+'<td><input type="number" class="split_stone_pcs form-control" name="lot_stones_item[split_stone_pcs][]" value="'+(isNaN(pitem['split_stone_pcs']) ? 0 : pitem['split_stone_pcs'])+'" style="width: 100%;"/></td>'


			+'<td><input type="number" class="split_stone_wt form-control" name="lot_stones_item[split_stone_wt][]" value="'+(isNaN(pitem['split_stone_wt']) ? 0 : pitem['split_stone_wt'])+'" style="width: 100%;"/></td>'
		});
		
	}
	$('#stoneModal .modal-body').find('#lotsplit_stone_details tbody').append(row);
	$('#stoneModal').modal('show');
}

$(document).on('change',".split_stone_pcs",function()
{
	curRow = $(this).closest('tr');
	if(this.value > curRow.find('.lot_stone_pcs').val())
	{
		curRow.find('.split_stone_pcs').val("");
	}
	else
	{
		curRow.find('.split_stone_pcs').val(this.value);
	}
	
});


$(document).on('change',".split_stone_wt",function()
{
	
	curRow = $(this).closest('tr');
	if(this.value > parseFloat(curRow.find('.lot_stone_wt').val()))
	{
		curRow.find('.split_stone_wt').val("");
	}
	else
	{
		curRow.find('.split_stone_wt').val(this.value);
	}
	
});

$('#lot_split_submit').on('click',function()
{
	if($('#id_employee').val()!="")
	{
		if(validateLotSplitRow())
		{
			$('#lot_split_form').submit();
			window.location.reload();
		}
		else
		{
			$.toaster({ priority : 'danger', title : 'Warning!', message : "Please Fill All Required Fields..."});
		}		
	}
	else
	{
		$.toaster({ priority : 'danger', title : 'Warning!', message : "Please Select Employee..."});
	}
	
});

function validateLotSplitRow()
{
	var row_validate = true;
	$('#lot_split_list > tbody > tr').each(function(index, tr) 
	{		 		
		if($(this).find('.split_pcs').val() == "" || $(this).find('.split_wt').val() =="")
		{
			row_validate = false;
		}

	});	
	return row_validate;
}

$('#stoneModal  #update_lot_stone_details').on('click', function()
{
	var stone_details=[];
	
	$('#stoneModal .modal-body #lotsplit_stone_details > tbody  > tr').each(function(index, tr) 
	{
		stone_details.push({

			'id_stn_detail'     : $(this).find('.id_stn_detail').val(),

			'stone_id'          : $(this).find('.stone_id').val(),

			'uom_id'            : $(this).find('.uom_id').val(),

			'stone_name'        : $(this).find('.stone_name').val(),

			'uom_name'          : $(this).find('.uom_name').val(),

			'stn_pcs'           : $(this).find('.lot_stone_pcs').val(),

			'stn_wt'            : $(this).find('.lot_stone_wt').val(),

			'split_stone_pcs'   : $(this).find('.split_stone_pcs').val(),

			'split_stone_wt'    : $(this).find('.split_stone_wt').val(),


		});

	});

	console.log(stone_details);

	$('#stoneModal').modal('toggle');

	var catRow=$('#lot_active_id').val();

	$('#'+catRow).find('.stone_details').val(JSON.stringify(stone_details));


});


/*Lot Split Module*/
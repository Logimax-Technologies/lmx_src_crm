var path =  url_params();
var ctrl_page = path.route.split('/');
var oldMetalDetail=[];
var pocketDetails=[];
var PolishpocketDetails=[];
var metal_process=[];
var category_details=[];
var prod_details=[];
var purity_details=[];
$(document).ready(function() 
{   
     
    $(document).on('input[type="number"]').on('keypress',function(e){
        if (e.keyCode == 45)
        {
            e.preventDefault();
            return false;
        }  
     });
     
	switch(ctrl_page[1])
	{
	    
	    case 'metal_process':
	         switch(ctrl_page[2])
	         {
	              case 'list':
	                  get_metal_process();
	              break;
	         }
	    break;
	    
		case 'metal_pocket':
              
              switch(ctrl_page[2])
              {
                    case 'add':
                        var date = new Date();
            		    var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 0, 1); 
            			var from_date=(firstDay.getDate()+"-"+(firstDay.getMonth() + 1)+"-"+firstDay.getFullYear());
            			var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
        		        $('#rpt_payments1').html(from_date);
                        $('#rpt_payments2').html(to_date);
        			  		$('#rpt_payment_date').daterangepicker(
        		            {
        		              ranges: {
        		                'Today': [moment(), moment()],
        		                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        		                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        		                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        		                'This Month': [moment().startOf('month'), moment().endOf('month')],
        		                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        		              },
        		              startDate: moment().subtract(0, 'days'),
        		              endDate: moment()
        		            },
        		          function (start, end) {
        		          $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        		      
        						$('#rpt_payments1').text(start.format('YYYY-MM-DD'));
        						$('#rpt_payments2').text(end.format('YYYY-MM-DD'));		            
        		          }
        			    ); 
                        get_ActiveMetals();
                        get_old_metal_type();
                        
                        //get_metal_stock_list();
                    break;
                    
                    case 'list':
                        var date = new Date();
            		    var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 0, 1); 
            			var from_date=(firstDay.getDate()+"-"+(firstDay.getMonth() + 1)+"-"+firstDay.getFullYear());
            			var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
        		        $('#rpt_payments1').html(from_date);
                        $('#rpt_payments2').html(to_date);
                        get_pocket_list();
        			  		$('#date_range_picker').daterangepicker(
        		            {
        		              ranges: {
        		                'Today': [moment(), moment()],
        		                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        		                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        		                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        		                'This Month': [moment().startOf('month'), moment().endOf('month')],
        		                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        		              },
        		              startDate: moment().subtract(0, 'days'),
        		              endDate: moment()
        		            },
        		          function (start, end) {
        		          $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        						$('#rpt_payments1').text(start.format('YYYY-MM-DD'));
        						$('#rpt_payments2').text(end.format('YYYY-MM-DD'));	
        						get_pocket_list();
        		          }
        			    );
                    break;
              }
	 	break;
	 	
	 	case 'metal_process_issue':
	 	     switch(ctrl_page[2])
	 	     {
	 	          case 'add':
	 	               
	 	              get_ActiveMetalProcess();
	 	              get_ActiveKarigars();
	 	              get_ActiveCategory();
	 	              get_pocket_details();
	 	              get_polish_pocket_details();
	 	              get_ActiveProduct();
	 	              get_ActiveCategoryPurity();
	 	              get_category(); 
	 	          break;
	 	          case 'list':
	                  get_old_metal_process();
	              break;
	 	     }
	 	break;
	 	
	 	case 'metal_process_receipt':
	 	     switch(ctrl_page[2])
	 	     {
	 	          case 'add':
	 	               
	 	              get_ActiveKarigars();
	 	              get_pocket_details();
	 	              
	 	          break;
	 	     }
	 	break;
	 	
	 	case 'process_report':
	 	    get_ActiveMetalProcess();
	 	    get_ActiveKarigars();
	 	    
            var date = new Date();
            var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 30, 1); 
            var from_date=(firstDay.getDate()+"-"+(firstDay.getMonth() + 1)+"-"+firstDay.getFullYear());
            var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
            $('#rpt_payments1').html(from_date);
            $('#rpt_payments2').html(to_date);
            if(ctrl_page[2]=='list')
            {
                get_metal_process_report();
            }else
            {
                 get_metal_process_detailed_report();
            }
            
            $('#rpt_payment_date').daterangepicker(
            {
            ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            startDate: moment().subtract(0, 'days'),
            endDate: moment()
            },
            function (start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            $('#rpt_payments1').text(start.format('YYYY-MM-DD'));
            $('#rpt_payments2').text(end.format('YYYY-MM-DD'));	
            get_pocket_list();
            }
            );
        			    
	 	    
	 	break;
	}
	
	
	    if($('#process_type:checked').val()==1)
        {
            $('.pocketing_details').css("display","block");
            $('.refining_details').css("display","none");
            $('.testing_details').css("display","none");
        }
        else if($('#process_type:checked').val()==2)
        {
            $('.pocketing_details').css("display","none");
            $('.refining_details').css("display","none");
            $('.testing_details').css("display","block");
        }
        else if($('#process_type:checked').val()==3)
        {
            $('.pocketing_details').css("display","none");
            $('.testing_details').css("display","none");
            $('.refining_details').css("display","block");
        }

});

$('#metal_search').on('click',function(){
    if($('#metal').val()=='' || $('#metal').val()==null)
    {
         $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select Metal'});
    }else{
         get_metal_stock_list();
    }
});

function get_old_metal_type()
{
    $('#old_metal_type option').remove();
    $.ajax({
        type: 'GET',
        url: base_url+'index.php/admin_ret_reports/get_old_metal_type',
        dataType:'json',
        success:function(data){
        var id_karigar =  $("#karigar").val();
        $("#old_metal_type").select2(
        {
            placeholder:"Select Category",
            allowClear: true		    
        });
        $.each(data,function (key, item) {
            $('#old_metal_type').append(
            $("<option></option>")
            .attr("value", item.id_metal_type)
            .text(item.metal_type)
            );
        }); 
            $("#old_metal_type").select2("val",'');	 
        }
    });
}


function get_ActiveProduct()
{
	$.ajax({
	type: 'GET',
	url: base_url+'index.php/admin_ret_reports/get_ActiveProduct',
	dataType:'json',
	success:function(data){
            prod_details=data;
		}
	});
}

function get_ActiveCategoryPurity()
{
	$.ajax({
	type: 'GET',
	url: base_url+'index.php/admin_ret_metal_process/get_ActiveCategoryPurity',
	dataType:'json',
	success:function(data){
            purity_details=data;
		}
	});
}

//Process Master
function get_metal_process()
{
    my_Date = new Date();
	$.ajax({
		url: base_url+"index.php/admin_ret_metal_process/metal_process/ajax?nocache=" + my_Date.getUTCSeconds(),
		type:"POST",
		dataType:"JSON",
		cache:false,
		success:function(data){
		    var list=data.list;
		    var access=data.access;
			var oTable = $('#process_list').DataTable();
			oTable.clear().draw();
			
			if (list!= null && list.length > 0)
			{  	
				oTable = $('#process_list').dataTable({
					"bDestroy": true,
					"bDestroy": true,
					"bInfo": true,
					"bFilter": true,
					"bSort": false,
					"order": [[ 0, "desc" ]],
					"aaData"  : list,
					"aoColumns": [	
									{ "mDataProp": "id_metal_process" },
									{ "mDataProp": "process_name" },
									{ "mDataProp": function ( row, type, val, meta ){
                                		active_url =base_url+"index.php/admin_customer/customer_status/"+(row.status==1?0:1)+"/"+row.id_metal_process; 
                                		return "<a href='"+active_url+"'><i class='fa "+(row.status==1?'fa-check':'fa-remove')+"' style='color:"+(row.status==1?'green':'red')+"'></i></a>"
                                		}
                            		},
									{ "mDataProp": function ( row, type, val, meta ) {
                            		id= row.id_metal_process;
                            		edit_url=(access.edit=='1' ? base_url+'index.php/admin_ret_metal_process/metal_process/edit/'+id : '#' );
                            		action_content='<div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button><ul class="dropdown-menu">'+
                            		'<li><a href="'+edit_url+'" class="btn-edit"><i class="fa fa-edit" ></i> Edit</a></li></ul></div>';
                            		return action_content;
                            		}
                            		}
								  ],
				});

			}
		}
	});
}
//Process Master


//Pocketing Entry

function get_pocket_list()
{
    my_Date = new Date();
	$.ajax({
		url: base_url+"index.php/admin_ret_metal_process/metal_pocket/ajax?nocache=" + my_Date.getUTCSeconds(),
		data:{'from_date':$('#rpt_payments1').html(),'to_date' :$('#rpt_payments2').html()},
		type:"POST",
		dataType:"JSON",
		cache:false,
		success:function(data){
		    var list=data.list;
			var oTable = $('#pocket_list').DataTable();
			oTable.clear().draw();
			
			if (list!= null && list.length > 0)
			{  	
				oTable = $('#pocket_list').dataTable({
					"bDestroy": true,
					"bDestroy": true,
					"bInfo": true,
					"bFilter": true,
					"bSort": false,
					"order": [[ 0, "desc" ]],
					"aaData"  : list,
					"aoColumns": [	
									{ "mDataProp": "pocket_no" },
									{ "mDataProp": "date" },
									{ "mDataProp": "piece" },
									{ "mDataProp": "gross_wt" },
									{ "mDataProp": "net_wt" },
									{ "mDataProp": "avg_purity" },
								  ],
				});

			}
		}
	});
}

$('#old_metal_select_all').click(function(event) {
	$("#metal_list tbody tr td input[type='checkbox']").prop('checked', $(this).prop('checked'));
	event.stopPropagation();
	if($(this).is(":checked"))
	{
			$.each(oldMetalDetail,function(key,items){
			  oldMetalDetail[key].is_checked=1;
			});
	}else{
		$.each(oldMetalDetail,function(key,items){
			  oldMetalDetail[key].is_checked=0;
			});
	}
		calculate_old_metal();
});




function calculate_old_metal()
{
  
  var total_pcs=0;
  var total_gwt=0;
  var total_nwt=0;
  var total_amt=0;
  var total_dust=0;
  var total_stone=0;
  var total_purity=0;
  var total_wastage=0;
  var total_final_wt=0;
  var total_length=0;
  var total_item_purity=0;
  $.each(oldMetalDetail,function(key,items){
	  if(items.is_checked==1)
	  {
		  total_pcs+=parseFloat(items.piece);
		  total_gwt+=parseFloat(items.gross_wt);
		  total_nwt+=parseFloat(items.net_wt);
		  total_dust+=parseFloat(items.dust_wt);
		  total_stone+=parseFloat(items.stone_wt);
		  total_final_wt+=parseFloat(items.pure_wt);
		  total_purity+=parseFloat(items.purity_per*items.piece);
		  total_wastage+=parseFloat(items.wast_wt);
		  total_amt+=parseFloat(items.item_cost);
		  total_item_purity+=parseFloat(items.purity*items.piece);
		  total_length++;
	  }
  });
  
  $(".total_pcs").val(parseFloat(total_pcs).toFixed(0));
  $(".total_gross_wt").val(parseFloat(total_gwt).toFixed(3));
  $(".total_stone_wt").val(parseFloat(total_stone).toFixed(3));
  $(".total_dust_wt").val(parseFloat(total_dust).toFixed(3));
  $(".total_final_wt").val(parseFloat(total_final_wt).toFixed(3));
  $(".total_wastage_wt").val(parseFloat(total_wastage).toFixed(3));
  $(".total_net_wt").val(parseFloat(total_nwt).toFixed(3));
  $(".total_amount").val(parseFloat(total_amt).toFixed(3));
  $(".total_item_purity").val(parseFloat(total_item_purity).toFixed(3));
  if(total_length>0)
  {
	$(".avg_purity_per").val(parseFloat(total_purity/total_pcs).toFixed(2));
  }else{
	$(".avg_purity_per").val(0);
  }
  
}

function get_metal_stock_list()
{
    $("div.overlay").css("display", "block"); 
	oldMetalDetail=[];
    my_Date = new Date();
	$.ajax({
		url: base_url+"index.php/admin_ret_metal_process/metal_pocket/metal_list?nocache=" + my_Date.getUTCSeconds(),
		data:{'from_date':$('#rpt_payments1').html(),'to_date' :$('#rpt_payments2').html(),'id_branch':$('#branch_select').val(),'id_metal':$('#metal').val(),'from_branch':$('.branch_filter').val()},
		type:"POST",
		dataType:"JSON",
		cache:false,
		success:function(data){
		    var list=data;
			var oTable = $('#metal_list').DataTable();
			oTable.clear().draw();
			
			if (list!= null && list.length > 0)
			{  	

				$.each(list,function(key,val){
					$.each(val.bill_det,function(k,items){
						oldMetalDetail.push(items);
					});
				});

				oTable = $('#metal_list').dataTable({
					"bDestroy": true,
					"bDestroy": true,
					"bInfo": true,
					"bFilter": true,
					"bSort": false,
					"order": [[ 0, "desc" ]],
					"aaData"  : list,
					"aoColumns": [	
									{ "mDataProp": function ( row, type, val, meta )
									{ 
										return '<input type="checkbox" class="'+row.type+'" name="type[]" value="'+row.type+'"/>'+row.metal_type;
									}},
									{ "mDataProp": "piece" },
									{ "mDataProp": "gross_wt" },
									{ "mDataProp": "net_wt" },
									{ "mDataProp": "purity_per" },
									{ "mDataProp": "rate" },
									{
										"mDataProp": null,
										"sClass": "control center", 
										"sDefaultContent":  '<span class="drill-val"><i class="fa fa-chevron-circle-down text-teal"></i></span>'
									},
								  ],
				});

				var anOpen =[]; 
				$(document).on('click',"#metal_list .control", function(){ 
					var nTr = this.parentNode;
					var i = $.inArray( nTr, anOpen );
					
					if ( i === -1 ) { 
						$('.drill-val', this).html('<i class="fa fa-chevron-circle-up text-teal"></i>'); 
						oTable.fnOpen( nTr, FormatRowBillDetails(oTable, nTr), 'details' );
						anOpen.push( nTr ); 
					}
					else { 
						$('.drill-val', this).html('<i class="fa fa-chevron-circle-down text-teal"></i>');
						oTable.fnClose( nTr );
						anOpen.splice( i, 1 );
					}
				} );

				calculate_old_metal();
			}
			$("div.overlay").css("display", "none"); 
		}
	});
}

function FormatRowBillDetails( oTable, nTr )
{
  var oData = oTable.fnGetData( nTr );
  var rowDetail = '';
  var prodTable = 
     '<div class="innerDetails">'+
      '<table class="table table-responsive table-bordered text-center table-sm">'+ 
        '<tr class="bg-teal">'+
        '<th>S.No</th>'+ 
        '<th>Bill Date</th>'+
        '<th>Bill No</th>'+
        '<th>Category</th>'+
        '<th>Pcs</th>'+
        '<th>G.Wgt</th>'+
		'<th>Net Wgt</th>'+
		'<th>Purity %</th>'+
        '<th>Amount</th>'+
        '</tr>';
    var bill_det = oData.bill_det; 
	var total_gwt=0;
    var total_nwt=0;
    var total_amt=0;
    var total_pcs=0;
  $.each(bill_det, function (idx, val) {
	var is_checked=0;
	$.each(oldMetalDetail,function(key,items){
		if(val.old_metal_sale_id==items.old_metal_sale_id)
		{
			is_checked=items.is_checked;
			total_pcs+=parseFloat(items.piece);
			total_gwt+=parseFloat(items.gross_wt);
			total_nwt+=parseFloat(items.net_wt);
			total_amt+=parseFloat(items.item_cost);
		}
	});
  	prodTable += 
        '<tr class="prod_det_btn">'+
        '<td><input type="checkbox" class="'+val.type+'" name="old_metal_sale_id[]" value="'+val.trans_id+'" '+(is_checked==1 ? 'checked' :'')+' >'+val.trans_id+'</td>'+
        '<td>'+val.bill_date+'</td>'+
        '<td>'+val.bill_no+'</td>'+
        '<td>'+val.old_metal_cat+'</td>'+
        '<td>'+val.piece+'</td>'+
        '<td>'+val.gross_wt+'</td>'+
        '<td>'+val.net_wt+'</td>'+
		'<td>'+val.purity_per+'</td>'+
		'<td>'+val.item_cost+'</td>'+
        '</tr>'; 
  }); 
  rowDetail = prodTable+'</table></div>';
  return rowDetail;
}


$(document).on('change',".old_metal", function(){
    if($(this).is(":checked"))
    {
        $.each(oldMetalDetail,function(key,items){
                if(items.type=='old_metal_items')
                {
                    console.log(oldMetalDetail);
                    oldMetalDetail[key].is_checked=1;
                }
                
        });
    }
    else
    {
        console.log(oldMetalDetail);
        $.each(oldMetalDetail,function(key,items){
                if(items.type=='old_metal_items')
                {
                    oldMetalDetail[key].is_checked=0;
                }
        });
    }
    
    calculate_old_metal();
});


$(document).on('change',".old_metal_items", function(){
    var trans_id=this.value;
    if($(this).is(":checked"))
    {
        $.each(oldMetalDetail,function(key,items){
                if(trans_id==items.old_metal_sale_id)
                {
                    oldMetalDetail[key].is_checked=1;
                }
                
        });
    }
    else
    {
        $.each(oldMetalDetail,function(key,items){
                if(trans_id==items.old_metal_sale_id)
                {
                    oldMetalDetail[key].is_checked=0;
                }
        });
    }
    calculate_old_metal();
});


$(document).on('change',".sales_return", function(){
    if($(this).is(":checked"))
    {
        $.each(oldMetalDetail,function(key,items){
                if(items.type=='sales_return_items')
                {
                    console.log(oldMetalDetail);
                    oldMetalDetail[key].is_checked=1;
                }
                
        });
    }
    else
    {
        console.log(oldMetalDetail);
        $.each(oldMetalDetail,function(key,items){
                if(items.type=='sales_return_items')
                {
                    oldMetalDetail[key].is_checked=0;
                }
        });
    }
    
    calculate_old_metal();
});


$(document).on('change',".sales_return_items", function(){
    var trans_id=this.value;
    if($(this).is(":checked"))
    {
        $.each(oldMetalDetail,function(key,items){
                if(trans_id==items.trans_id)
                {
                    oldMetalDetail[key].is_checked=1;
                }
                
        });
    }
    else
    {
        $.each(oldMetalDetail,function(key,items){
                if(trans_id==items.trans_id)
                {
                    oldMetalDetail[key].is_checked=0;
                }
        });
    }
    calculate_old_metal();
});


$('#pocket_save').on('click',function(){
/*	if($("input[name='id_old_metal_type[]']:checked").val())
        {*/
            $('#pocket_save').prop('disabled',true);
            var selected = [];
            console.log(oldMetalDetail);
			$.each(oldMetalDetail,function(key,items){
				if(items.is_checked==1)
				{
					selected.push(items);
				}
				
			});
          
            req_data = selected;
            if(selected.length>0)
            {
                pocket_save(req_data);
            }else
            {
                $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select Any One Items..'});
            }
            
        /*}else{
            $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select Any One Category..'});
        }*/
});

function pocket_save(req_data)
{
	$(".overlay").css("display", "block");	 
	my_Date = new Date();
	$.ajax({		
	 	type: 'POST',		
		url: base_url+"index.php/admin_ret_metal_process/metal_pocket/save?nocache=" + my_Date.getUTCSeconds(),
	 	dataType : 'json',		
	 	data : {'req_data':req_data,'total_gross_wt':$(".total_gross_wt").val(),'total_pcs':$(".total_pcs").val(),'total_stone_wt':$(".total_stone_wt").val(),'total_dust_wt':$(".total_dust_wt").val(),'total_final_wt':$(".total_final_wt").val(),'total_wastage_wt':$(".total_wastage_wt").val(),'total_net_wt':$(".total_net_wt").val(),'total_item_purity':$(".total_item_purity").val(),'avg_purity_per':$(".avg_purity_per").val(),'total_amount':$(".total_amount").val()},
	 	success:function(data)
		 {
			 if(data.status)
			 {
				$.toaster({ priority : 'success', title : 'Warning!', message : ''+"</br>"+data.message});
			 }else{
				$.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+data.message});
			 }
			 get_metal_stock_list();
			 calculate_old_metal();
	     	$(".overlay").css("display", "none");
	     	$('#pocket_save').prop('disabled',false);
	 	 }	
	});
}

//Pocketing Entry




//Metal Process

$("input[name='process[process_type]']:radio").on('change',function(){
     $('#pocket_details tbody').empty();
     $('#melting_receipt tbody').empty();
    if(this.value==1)
    {
        $('.pocketing_details').css("display","block");
        $('.refining_details').css("display","none");
        $('.testing_details').css("display","none");
    }
    else if(this.value==2)
    {
        $('.pocketing_details').css("display","none");
        $('.refining_details').css("display","none");
        $('.testing_details').css("display","block");
    }
    else if(this.value==3)
    {
        $('.pocketing_details').css("display","none");
        $('.refining_details').css("display","block");
        $('.testing_details').css("display","none");
    }
});

function get_ActiveKarigars()
{
	$.ajax({
		type: 'GET',
		url: base_url+'index.php/admin_ret_catalog/karigar/active_list',
		dataType:'json',
		success:function(data){
			var id =  $("#karigar").val();
		
		   $.each(data,function (key, item) {
			   		$('#karigar').append(
						$("<option></option>")
						  .attr("value", item.id_karigar)
						  .text(item.karigar)
					);
			}); 
			
			$("#karigar").select2(
			{
				placeholder:"Select Vendor",
				allowClear: true		    
			});  
			
			  $('#karigar').select2("val",(id!='' && id>0?id:''));
		}
	});

}

function get_ActiveCategory()
{
    $.ajax({
	type: 'GET',
	url: base_url+'index.php/admin_ret_catalog/category/active_category',
	dataType:'json',
	success:function(data){
            category_details=data;
		}
	});
}


function get_ActiveMetalProcess()
{
	$.ajax({
		type: 'GET',
		url: base_url+'index.php/admin_ret_metal_process/get_ActiveMetalProcess',
		dataType:'json',
		success:function(data){
		    metal_process=data;
			var id =  $("#select_process").val();
		
		   $.each(data,function (key, item) {
			   		$('#select_process').append(
						$("<option></option>")
						  .attr("value", item.id_metal_process)
						  .text(item.process_name)
					);
			}); 
			
			$("#select_process").select2(
			{
				placeholder:"Select Process",
				allowClear: true		    
			});  
			
			  $('#select_process').select2("val",(id!='' && id>0?id:''));
		}
	});

}

function get_pocket_details()
{
    pocketDetails=[];
    $('#select_pocket option').remove();
    $.ajax({
        type: 'GET',
        url: base_url+'index.php/admin_ret_metal_process/get_pocket_details',
        dataType:'json',
        success:function(data){
        pocketDetails=data;
        var id_karigar =  $("#select_pocket").val();
        $("#select_pocket").select2(
        {
            placeholder:"Select Pocket",
            allowClear: true		    
        });
        $.each(data,function (key, item) {
            $('#select_pocket').append(
            $("<option></option>")
            .attr("value", item.id_metal_pocket)
            .text(item.pocket_no)
            );
        }); 
            
            if($("#select_pocket").length)
            {
                $("#select_pocket").select2("val",'');
            }
        }
    });
}

function get_polish_pocket_details()
{
    PolishpocketDetails=[];
    $('#select_polish_pocket option').remove();
    $.ajax({
        type: 'GET',
        url: base_url+'index.php/admin_ret_metal_process/get_polish_pocket_details',
        dataType:'json',
        success:function(data){
        PolishpocketDetails=data;
        var id_karigar =  $("#select_polish_pocket").val();
        $("#select_polish_pocket").select2(
        {
            placeholder:"Select Pocket",
            allowClear: true		    
        });
        $.each(data,function (key, item) {
            $('#select_polish_pocket').append(
            $("<option></option>")
            .attr("value", item.id_metal_pocket)
            .text(item.pocket_no)
            );
        }); 
            

            
            if($("#select_polish_pocket").length)
            {
                $("#select_polish_pocket").select2("val",'');
            }
            	 
        }
    });
}

$('#select_pocket').on('change',function(){
    var id_metal_pocket=this.value;
    if(this.value!='')
    {
        var rowExist = false;
        if($('#pocket_details tbody tr').length>0)
        {
            	$('#pocket_details > tbody tr').each(function(bidx, brow){ 
            	    curRow = $(this);
            	    if(curRow.find('.id_metal_pocket').val()==id_metal_pocket)
            	    {
            	        rowExist = true;
            	    }
            	});
        }
       
    	if(rowExist)
    	{
    	    $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Pocket Already Added..'});
    	     $("#select_pocket").select2("val",'');	 
    	}
    	else
    	{
    	    get_PocketingDetails(this.value);
            $("#select_pocket").select2("val",'');	 
    	}
    }
});

$('#select_polish_pocket').on('change',function(){
    var id_metal_pocket=this.value;
    if(this.value!='')
    {
        var rowExist = false;
        if($('#polish_pocket_details tbody tr').length>0)
        {
            	$('#polish_pocket_details > tbody tr').each(function(bidx, brow){ 
            	    curRow = $(this);
            	    if(curRow.find('.id_metal_pocket').val()==id_metal_pocket)
            	    {
            	        rowExist = true;
            	    }
            	});
        }
       
    	if(rowExist)
    	{
    	    $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Pocket Already Added..'});
    	     $("#select_polish_pocket").select2("val",'');	 
    	}
    	else
    	{
    	    get_PolishPocketingDetails(this.value);
            $("#select_polish_pocket").select2("val",'');	 
    	}
    }
});

function get_PocketingDetails(id_metal_pocket)
{
    $('#pocket_details tfoot >tr').empty();
    var trHtml='';
   
    if(id_metal_pocket!='')
    {
        $.each(pocketDetails,function(key,items){
            if(items.id_metal_pocket==id_metal_pocket)
            {
               var gross_wt = parseFloat(parseFloat(items.gross_wt)-parseFloat(items.issue_gwt)).toFixed(3);
               var net_wt = parseFloat(parseFloat(items.net_wt)-parseFloat(items.issue_nwt)).toFixed(3);
               var piece = parseFloat(parseFloat(items.piece)-parseFloat(items.issue_pcs)).toFixed(3);
               var bal_purity  = parseFloat(parseFloat(parseFloat(items.tot_purity)-parseFloat(items.issue_purity))/parseFloat(piece)).toFixed(3);
                trHtml+='<tr>'
                            +'<td><input type="hidden" name="pocket[id_metal_pocket][]" class="id_metal_pocket" value="'+items.id_metal_pocket+'">'+items.pocket_no+'</td>'
                            +'<td><input type="hidden" name="pocket[piece][]" class="piece" value="'+piece+'">'+piece+'</td>'
                            +'<td><input type="hidden" name="pocket[gross_wt][]" class="gross_wt" value="'+gross_wt+'">'+gross_wt+'</td>'
                            +'<td><input type="hidden" name="pocket[net_wt][]" class="net_wt" value="'+net_wt+'">'+net_wt+'</td>'
                            +'<td><input type="hidden" name="pocket[avg_purity][]" class="avg_purity" value="'+bal_purity+'">'+bal_purity+'</td>'
                            +'<td><input type="hidden" name="pocket[amount][]" class="amount" value="'+items.amount+'">'+items.amount+'</td>'
                            +'<td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'
                        +'</tr>';
            }
        });
        
        $('#pocket_details tbody').append(trHtml);
        calculate_pocketing_details();
    }
}

function get_PolishPocketingDetails(id_metal_pocket)
{
    $('#polish_pocket_details tfoot >tr').empty();
    var trHtml='';
   
    if(id_metal_pocket!='')
    {
        $.each(PolishpocketDetails,function(k,val){
            $.each(val.pocket_details,function(key,items){
                    if(items.id_metal_pocket==id_metal_pocket)
                    {
                        console.log('tot_purity:'+items.tot_purity);
                        console.log('total_issue_purity:'+items.total_issue_purity);
                       var pcs         = parseFloat(items.piece-items.issue_pcs).toFixed(3);
                       var gross_wt    = parseFloat(items.gross_wt-items.issue_gwt).toFixed(3);
                       var net_wt      = parseFloat(items.net_wt-items.issue_nwt).toFixed(3);
                       var bal_purity  = parseFloat(parseFloat(parseFloat(items.tot_purity)-parseFloat(items.total_issue_purity))).toFixed(3);
                       var bal_avg_purity  = parseFloat(parseFloat(parseFloat(items.tot_purity)-parseFloat(items.total_issue_purity))/parseFloat(pcs)).toFixed(3);
                        trHtml+='<tr>'
                                    +'<td><input type="hidden" name="pocket[id_metal_pocket][]" class="id_metal_pocket" value="'+items.id_metal_pocket+'">'+items.pocket_no+'</td>'
                                    +'<td><input type="hidden" name="pocket[id_metal_type][]" class="id_metal_type" value="'+items.id_metal_type+'">'+items.old_metal_type+'</td>'
                                    +'<td><input type="hidden" name="pocket[piece][]" class="piece" value="'+pcs+'">'+pcs+'</td>'
                                    +'<td><input type="hidden" name="pocket[gross_wt][]" class="gross_wt" value="'+gross_wt+'">'+gross_wt+'</td>'
                                    +'<td><input type="hidden" name="pocket[net_wt][]" class="net_wt" value="'+net_wt+'">'+net_wt+'</td>'
                                    +'<td><input type="hidden" name="pocket[tot_purity][]" class="tot_purity" value="'+bal_purity+'"><input type="hidden" name="pocket[avg_purity][]" class="avg_purity" value="'+bal_avg_purity+'">'+bal_avg_purity+'</td>'
                                    +'<td><input type="hidden" name="pocket[amount][]" class="amount" value="'+items.amount+'">'+items.amount+'</td>'
                                    +'<td style="width: 85px;" ><input type="number"   name="pocket[issue_pcs][]" class="form-control issue_pcs" value="" ></td>'
                                    +'<td style="width: 100px;"><input type="number"  name="pocket[issue_gwt][]" class="form-control issue_gwt" value="" ></td>'
                                    +'<td style="width: 100px;"><input type="number" name="pocket[issue_nwt][]" class="form-control issue_nwt" value="" ></td>'
                                    +'<td style="width: 100px;"><input type="number"  name="pocket[issue_purity][]" class="form-control issue_purity" value="" max="100"></td>'
                                    +'<td style="width: 100px;"><input type="number"  name="pocket[blc_avg_purity][]" class="form-control blc_avg_purity" value="" readonly></td>'
                                    +'<td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'
                                +'</tr>';
                    }
            });
           
        });
        
        $('#polish_pocket_details tbody').append(trHtml);
        calculate_polishing_pocketing_details();
    }
}

$(document).on('keyup',".issue_purity", function(){
    var row = $(this).closest('tr'); 
    var issue_purity = row.find('.issue_purity').val();
    if(parseFloat(issue_purity)>100)
    {
        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Enter The Vaild Weight..'});
        row.find('.issue_purity').focus();
        row.find('.issue_purity').val('');
    }
    calculate_polishing_pocketing_details();
});

$(document).on('keyup',".issue_gwt", function(){
    var row = $(this).closest('tr'); 
    var gross_wt = row.find('.gross_wt').val();
    var issue_gwt = row.find('.issue_gwt').val();
    if(parseFloat(gross_wt)<parseFloat(issue_gwt))
    {
        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Enter The Vaild Weight..'});
        row.find('.issue_gwt').focus();
        row.find('.issue_gwt').val(gross_wt);
    }
    calculate_polishing_pocketing_details();
});


$(document).on('keyup',".issue_nwt", function(){
    var row = $(this).closest('tr'); 
    var net_wt = row.find('.net_wt').val();
    var issue_nwt = row.find('.issue_nwt').val();
    if(parseFloat(net_wt)<parseFloat(issue_nwt))
    {
        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Enter The Vaild Weight..'});
        row.find('.issue_nwt').focus();
        row.find('.issue_nwt').val(net_wt);
    }
    calculate_polishing_pocketing_details();
});


$(document).on('keyup',".issue_pcs,.issue_purity", function(){
    var row = $(this).closest('tr'); 
    var piece = row.find('.piece').val();
    var issue_pcs = row.find('.issue_pcs').val();
    if(parseFloat(piece)<parseFloat(issue_pcs))
    {
        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Enter The Vaild Pcs'});
        row.find('.issue_pcs').focus();
        row.find('.issue_pcs').val(piece);
    }
    calculate_polishing_pocketing_details();
});

function calculate_polishing_pocketing_details()
{
    $('#polish_pocket_details tfoot').empty();
    var trHtml='';
    var tot_gwt=0;
    var tot_nwt=0;
    var tot_amt=0;
    var tot_pcs=0;
    var tot_purity=0;
    var total_avg_purity=0;
    var total_isssue_pcs = 0;
    var total_isssue_gwt = 0;
    var total_isssue_nwt = 0;
    var total_pocket_avg_purity = 0;
    var total_balance_pocket_pcs = 0;
    var avg_purity = 0;
    if( $('#polish_pocket_details > tbody tr').length>0)
    {
        $('#polish_pocket_details > tbody tr').each(function(idx, row){
            curRow = $(this);
            var blc_avg_purity = 0;
            tot_pcs+=parseFloat(curRow.find('.piece').val());
            tot_gwt+=parseFloat(curRow.find('.gross_wt').val());
            tot_nwt+=parseFloat(curRow.find('.net_wt').val());
            tot_purity+=parseFloat(curRow.find('.avg_purity').val());
            tot_amt+=parseFloat(curRow.find('.amount').val());
            total_isssue_pcs+=parseFloat(curRow.find('.issue_pcs').val()!='' ? curRow.find('.issue_pcs').val():0);
            total_isssue_gwt+=parseFloat(curRow.find('.issue_gwt').val()!='' ? curRow.find('.issue_gwt').val():0);
            total_isssue_nwt+=parseFloat(curRow.find('.issue_nwt').val()!='' ? curRow.find('.issue_nwt').val():0);
            
            total_pocket_pcs    = (curRow.find('.piece').val()!='' ? curRow.find('.piece').val():0);
            issue_pcs           = (curRow.find('.issue_pcs').val()!='' ? curRow.find('.issue_pcs').val():0);
            total_purity        = (curRow.find('.tot_purity').val()!='' ? curRow.find('.tot_purity').val():0);
            issue_purity        = (curRow.find('.issue_purity').val()!='' ? curRow.find('.issue_purity').val():0);
            
            total_issue_purity  = parseFloat(issue_purity)*parseFloat(issue_pcs).toFixed(2);
            
            var balance_pocket_pur = parseFloat(total_purity)-parseFloat(total_issue_purity);
            
            var balance_pocket_pcs = parseFloat(total_pocket_pcs)-parseFloat(issue_pcs);
            
            total_balance_pocket_pcs+= parseFloat(total_pocket_pcs)-parseFloat(issue_pcs);
            
            var pocket_avg_purity = parseFloat(balance_pocket_pur);
            
            total_pocket_avg_purity+=parseFloat(balance_pocket_pur);
            
            
            if(balance_pocket_pcs>0)
            {
                curRow.find('.blc_avg_purity').val(parseFloat(balance_pocket_pur/balance_pocket_pcs).toFixed(2));
            }else
            {
                curRow.find('.blc_avg_purity').val(0);
            }
            
            
        });
        if(total_balance_pocket_pcs>0)
        {
            avg_purity=parseFloat(parseFloat(total_pocket_avg_purity)/total_balance_pocket_pcs).toFixed(2);
        }
        
        console.log('avg_purity:'+avg_purity);
    }
   
     trHtml+='<tr style="font-weight:bold;">'
                +'<td colspan="7">TOTAL</td>'
                +'<td>'+parseFloat(total_isssue_pcs).toFixed(0)+'<input type="hidden" name="process[piece]" value="'+parseFloat(total_isssue_pcs).toFixed(0)+'"></td>'
                +'<td>'+parseFloat(total_isssue_gwt).toFixed(3)+'<input type="hidden" name="process[gross_wt]" value="'+parseFloat(total_isssue_gwt).toFixed(3)+'"></td>'
                +'<td>'+parseFloat(total_isssue_nwt).toFixed(3)+'<input type="hidden" name="process[net_wt]" value="'+parseFloat(total_isssue_nwt).toFixed(3)+'"></td>'
                +'<td></td>'
                +'<td>'+parseFloat(avg_purity).toFixed(3)+'<input type="hidden" name="process[total_issue_purity]" value="'+parseFloat(total_issue_purity).toFixed(3)+'"><input type="hidden" name="process[purity]" value="'+parseFloat(avg_purity).toFixed(3)+'"></td>'
                +'<td></td>'
            +'</tr>';
    $('#polish_pocket_details tfoot').append(trHtml);
}

function calculate_pocketing_details()
{
    var trHtml='';
    var tot_pcs=0;
    var tot_gwt=0;
    var tot_nwt=0;
    var tot_amt=0;
    var tot_purity=0;
    var avg_purity=0;
    if( $('#pocket_details > tbody tr').length>0)
    {
        $('#pocket_details > tbody tr').each(function(idx, row){
            curRow = $(this);
            tot_pcs+=parseFloat(curRow.find('.piece').val());
            tot_gwt+=parseFloat(curRow.find('.gross_wt').val());
            tot_nwt+=parseFloat(curRow.find('.net_wt').val());
            tot_purity+=parseFloat(curRow.find('.avg_purity').val());
            tot_amt+=parseFloat(curRow.find('.amount').val());
        });
        avg_purity=parseFloat(tot_purity/$('#pocket_details > tbody tr').length);
    }
   
     trHtml+='<tr style="font-weight:bold;">'
                +'<td>TOTAL</td>'
                +'<td>'+parseFloat(tot_pcs).toFixed(0)+'<input type="hidden" name="process[piece]" value="'+parseFloat(tot_pcs).toFixed(3)+'"></td>'
                +'<td>'+parseFloat(tot_gwt).toFixed(3)+'<input type="hidden" name="process[gross_wt]" value="'+parseFloat(tot_gwt).toFixed(3)+'"></td>'
                +'<td>'+parseFloat(tot_nwt).toFixed(3)+'<input type="hidden" name="process[net_wt]" value="'+parseFloat(tot_nwt).toFixed(3)+'"></td>'
                +'<td>'+parseFloat(avg_purity).toFixed(3)+'<input type="hidden" name="process[purity]" value="'+parseFloat(avg_purity).toFixed(2)+'"></td>'
                +'<td>'+parseFloat(tot_amt).toFixed(3)+'<input type="hidden" name="process[amount]" value="'+parseFloat(tot_amt).toFixed(3)+'"></td>'
                +'<td></td>'
            +'</tr>';
    $('#pocket_details tfoot').append(trHtml);
}


$('#issue_submit').on('click',function(){
    var process_for = $("input[name='process[process_for]']:checked").val();//1-Issue,2-Receipt
    var process_type = $('#select_process').val();
    var id_karigar   = $('#karigar').val();
    var allow_submit=false;
    if(id_karigar!='' && id_karigar!=null)
    {
        if(process_type==1) // melting
        {
            if(process_for==1) //isssue
            {
                 if($('#pocket_details tbody tr').length==0)
                 {
                     allow_submit=false;
                     $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select The Pocket..'});
                 }else
                 {
                     allow_submit=true;
                 }
            }
            else if(process_for==2) // receipt
            {
                if($("input[name='receipt[is_melting_select_row][]']:checked").val())
                {
                    if(!validateMeltingReceiptRow())
                    {
                        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Fill The Required Fields..'});
                    }
                    else
                    {
                        allow_submit=true;
                    }
                }
                else
                {
                    $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select Any One Record.'});
                }
            }
        }
        else if(process_type==2) //Testing
        {
            if(process_for==1) //isssue
            {
                 if($('#testing_process_details tbody tr').length==0)
                 {
                     allow_submit=false;
                     $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select The Pocket..'});
                 }
                 else
                 {
                     allow_submit=true;
                 }
                 /*else
                 {
                     allow_submit=false;
                     $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Fill The Required Fields..'});
                 }*/
            }else if(process_for==2)//receipt
            {
               if(!$("input[name='testing_receipt_melting[]']:checked").val())
               {
                        allow_submit=false;
                        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select Any One Item..'});
               }
               else if(!validateTestingReceiptRow())
               {
                   $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Fill The Required Fields..'});
               }
                else
                {
                    allow_submit=true;
                }
            }
        }
        else if(process_type==3) //Refining
        {
            if(process_for==1) //isssue
            {
                 if($('#refining_issue_details tbody tr').length==0)
                 {
                     allow_submit=false;
                     $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select The Process No..'});
                 }
                 else
                 {
                     allow_submit=true;
                 }
                
            }else if(process_for==2)//receipt
            {
                if(!validateRefiningReceiptRow())
               {
                   $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Fill The Required Fields..'});
               }
                else
                {
                    allow_submit=true;
                }
            }
        }
        else if(process_type==4) //Polishing
        {
            if(process_for==1) //isssue
            {
                 if($('#polish_pocket_details tbody tr').length==0)
                 {
                     allow_submit=false;
                     $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select The Process No..'});
                 }
                 else  if(!valiDatePolishingIssue())
                 {
                     allow_submit=false;
                     $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Fill The Required Fields..'});
                 }
                 else
                 {
                     allow_submit=true;
                 }
                
            }else if(process_for==2)//receipt
            {
               if(!$("input[name='polishing_receipt_select[]']:checked").val())
               {
                        allow_submit=false;
                        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select Any One Item..'});
               }else if(!validatePolishingReceiptRow())
               {
                   allow_submit=false;
                   $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Fill The Required Fields..'});
               }
               else
               {
                   allow_submit=true;
               }
            }
        }
        if(allow_submit)
        {   
                my_Date = new Date();
                $("div.overlay").css("display", "block");
                 $('#issue_submit').prop('disabled',true);
                 var form_data=$('#process_issue').serialize();
                    $.ajax({ 
        		        url:base_url+ "index.php/admin_ret_metal_process/metal_process_issue/save?nocache=" + my_Date.getUTCSeconds(),
        		        data: form_data,
        		        type:"POST",
        		        dataType:"JSON",
        		        success:function(data){
        					if(data.status)
        					{
        					    
        					}
        					window.location.reload();
        					$("div.overlay").css("display", "none"); 
        		        },
        		        error:function(error)  
        		        {	
        		        $("div.overlay").css("display", "none"); 
        		        } 
        		    });
        }
    }
    else{
        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select The Karigar..'});
    }
});


function valiDatePolishingIssue(){
	var row_validate = true;
	$('#estimation_tag_details > tbody  > tr').each(function(index, tr) {
		if($(this).find('.issue_pcs').val() == "" || $(this).find('.issue_pcs').val() == 0 || $(this).find('.issue_gwt').val() == "" || $(this).find('.issue_gwt').val() == 0 || $(this).find('.issue_nwt').val() == "" || $(this).find('.issue_purity').val() == "" || $(this).find('.issue_purity').val() == 0 ){
			row_validate = false;
		}
	});
	return row_validate;
}

function get_ActiveMetals()
{
	$.ajax({
		type: 'GET',
		url: base_url+'index.php/admin_ret_catalog/ret_product/active_metal',
		dataType:'json',
		success:function(data){
			var id =  $("#metal").val();
			
		    $.each(data,function (key, item) {
			   		$('#metal').append(
						$("<option></option>")
						  .attr("value", item.id_metal)
						  .text(item.metal)
					);
			});
			$("#metal").select2(
			{
				placeholder:"Select Metal",
				allowClear: true		    
			}); 
			
			if($("#metal").length)
			{
			    $("#metal").select2("val","");
			}
		}
	});
}



$('#process_filter').on('click',function(){
    
    var process_for = $("input[name='process[process_for]']:checked").val();//1-Issue,2-Receipt
    
    $('.melting_issue').css("display","none");
    $('.melting_receipt').css("display","none");
    $('#receipt_det').css("display","none");
    
    $('.melting_issue').css("display","none");
    $('.melting_receipt').css("display","none");
    $('#receipt_det').css("display","none");
    
    $('.testing_issue').css("display","none");
    $('#receipt_det').css("display","none");
    $('.testing_receipt').css("display","none");
    
     $('.testing_issue').css("display","none");
    $('#receipt_det').css("display","none");
    $('.testing_receipt').css("display","none");
    
    $('.refining_issue').css("display","none");
    $('#receipt_det').css("display","none");
    $('.refining_receipt').css("display","none");
    
    $('.refining_issue').css("display","none");
    $('#receipt_det').css("display","none");
    $('.polishing_receipt').css("display","none");
    
    $('.polish_issue').css("display","none");
    $('.melting_receipt').css("display","none");
            
    if($('#select_process').val()=='' || $('#select_process').val()==null)
    {
        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select Process Type'});
    }else if(($('#karigar').val()=='' || $('#karigar').val()==null) && (process_for==2))
    {
        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select Karigar'});
    }else
    {
        var process_code='';
        var process_for = $("input[name='process[process_for]']:checked").val();//1-Issue,2-Receipt
        $.each(metal_process,function(key,items){
            if(items.id_metal_process==$('#select_process').val())
            {
                process_code=items.process_code;
            }
        });
    }
    if(process_code=='MELTING')
    {
        if(process_for==1) // issue
        {
            $('.melting_issue').css("display","block");
            $('.melting_receipt').css("display","none");
            $('#receipt_det').css("display","none");
        }
        else if(process_for==2) // Receipt
        {
            $('.melting_issue').css("display","none");
            $('.melting_receipt').css("display","block");
            $('#receipt_det').css("display","block");
            get_melting_process_details();
        }
    }
    
    if(process_code=='TESTING')
    {
        if(process_for==1) // issue
        {
            $('.testing_issue').css("display","block");
            $('#receipt_det').css("display","none");
            $('.testing_receipt').css("display","none");
            
            get_testing_issue_details();
        }
        else if(process_for==2) // Receipt
        {
            $('.testing_issue').css("display","none");
            $('#receipt_det').css("display","block");
            $('.testing_receipt').css("display","block");
            
            get_testing_receipt_details();
        }
    }
    
    
    if(process_code=='REFINING')
    {
        if(process_for==1) // issue
        {
            $('.refining_issue').css("display","block");
            $('#receipt_det').css("display","none");
            $('.refining_receipt').css("display","none");
            
            get_RefiningIssueDetails();
        }
        else if(process_for==2) // Receipt
        {
            $('.refining_issue').css("display","none");
            $('#receipt_det').css("display","block");
            $('.refining_receipt').css("display","block");
            
            get_refining_receipt_details();
        }
    }
    
     if(process_code=='POLISHING')
    {
        if(process_for==1) // issue
        {
            $('.polish_issue').css("display","block");
            $('#receipt_det').css("display","none");
            $('.polishing_receipt').css("display","none");
            
            //get_RefiningIssueDetails();
        }
        else if(process_for==2) // Receipt
        {
            $('.polish_issue').css("display","none");
            $('#receipt_det').css("display","block");
            $('.polishing_receipt').css("display","block");
            
            get_polishing_receipt_details();
        }
    }
    
});


function get_old_metal_process()
{
    my_Date = new Date();
	$.ajax({
		url: base_url+"index.php/admin_ret_metal_process/metal_process_issue/ajax?nocache=" + my_Date.getUTCSeconds(),
		type:"POST",
		dataType:"JSON",
		cache:false,
		success:function(data){
		    var list=data.list;
		    var access=data.access;
			var oTable = $('#process_list').DataTable();
			oTable.clear().draw();
			
			if (list!= null && list.length > 0)
			{  	
				oTable = $('#process_list').dataTable({
					"bDestroy": true,
					"bDestroy": true,
					"bInfo": true,
					"bFilter": true,
					"bSort": false,
					"order": [[ 0, "desc" ]],
					"aaData"  : list,
					"aoColumns": [	
									{ "mDataProp": "id_old_metal_process" },
									{ "mDataProp": "process_no" },
									{ "mDataProp": "date_add" },
									{ "mDataProp": "process_for" },
									{ "mDataProp": "process_name" },
									{ "mDataProp": "karigar_name" },
									{ "mDataProp": "emp_name" },
									{ "mDataProp": function ( row, type, val, meta ) {
                                    id= row.id_old_metal_process;
                                    print_url=base_url+'index.php/admin_ret_metal_process/process_acknowladgement/'+id;
                                    action_content='<a href="'+print_url+'" target="_blank" class="btn btn-info btn-print" data-toggle="tooltip" title="Acknowladgement"><i class="fa fa-print" ></i></a>';
                                    return action_content;
                                    }
                                    }
								  ],
				});

			}
		}
	});
}

//Metal Process


//Melting Receipt
function get_melting_process_details()
{
    $('#melting_receipt tbody').empty();
    $("div.overlay").css("display", "block"); 
    my_Date = new Date();
	$.ajax({
		url: base_url+"index.php/admin_ret_metal_process/get_KarigarMeltingIssueDetilas?nocache=" + my_Date.getUTCSeconds(),
		type:"POST",
		data:{'id_karigar':$('#karigar').val()},
		dataType:"JSON",
		cache:false,
		success:function(data){
		    if(data.length>0)
		    {
		        trHtml='';
		        var category = "<option value=''>- Select Category-</option>";
		        $.each(category_details, function (mkey, mitem) {
            		category += "<option value='"+mitem.id_ret_category+"'>"+mitem.name+"</option>";
            	});	
	
		        $.each(data,function(key,items){
                    trHtml+='<tr id="'+parseFloat(key+1)+'" >'
                    +'<td><input type="checkbox" class="id_melting" name="receipt[is_melting_select_row][]" ><input type="hidden" class="is_melting_select" name="receipt[is_melting_select][]" value="0"><input type="hidden" name="receipt[id_melting][]" value="'+items.id_melting+'"><input type="hidden" name="receipt[id_old_metal_process][]" value="'+items.id_old_metal_process+'">'+items.process_no+'</td>'
                    +'<td><input type="hidden" name="receipt[gross_wt][]" class="gross_wt" value="'+items.gross_wt+'">'+items.gross_wt+'</td>'
                    +'<td><input type="hidden" name="receipt[net_wt][]" class="net_wt" value="'+items.net_wt+'">'+items.net_wt+'</td>'
                    +'<td><input type="hidden" name="receipt[avg_purity][]" class="avg_purity" value="'+items.purity+'">'+items.purity+'</td>'
                    +'<td><input type="hidden" name="receipt[amount][]" class="amount" value="'+items.amount+'" readonly>'+items.amount+'</td>'
                    /*+'<td><select class="id_ret_category" name="receipt[id_ret_category][]" value="">'+category+'</select></td>'*/
                    +'<td><a href="#" class="btn btn-success btn-sm add_category" id = "add_category"  data-toggle="modal"><i class="fa fa-plus"></i></a><input type="hidden" class="cat_details" name="receipt[category_details][]" value=""></td>'
                    +'<td><input type="number" name="receipt[recd_gwt][]" class="form-control recd_gwt" value="" readonly></td>'
                    +'<td><input type="number" name="receipt[received_less_wt][]" class="form-control received_less_wt" readonly></td>'
                    +'<td><input type="number" name="receipt[received_amount][]" class="form-control received_amount"value="'+items.amount+'"></td>'
                    +'<td><input type="number" name="receipt[charge][]" class="form-control charge" value=""></td>'
                    +'<td><input type="text" name="receipt[ref_no][]" class="form-control ref_no" value=""></td>'
                    +'<td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'
                    +'</tr>';
                });
                $('#melting_receipt tbody').append(trHtml);
                $('#melting_receipt > tbody').find('.id_ret_category').select2();
                $("div.overlay").css("display", "none"); 
		    }else
		    {
		        $("div.overlay").css("display", "none"); 
		        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'No Record Found..'});
		    }
		}
	});
}

$(document).on('keyup','.recd_gwt', function(e){
    var row = $(this).closest('tr');
    var net_wt = row.find('.net_wt').val();
    var recd_gwt = row.find('.recd_gwt').val();
    if(parseFloat(net_wt)<parseFloat(recd_gwt))
    {
        row.find('.gross_wt').val(0);
        row.find('.received_less_wt').val(0);
        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Enter The Valid Weight..'});
    }
    else
    {
        row.find('.received_less_wt').val(parseFloat(parseFloat(net_wt)-parseFloat(recd_gwt)).toFixed(3));
    }
});    

function validateMeltingReceiptRow(){
	var row_validate = true;
	$('#melting_receipt > tbody  > tr').each(function(index, tr) {
	    if($(this).find('.id_melting').is(":checked"))
        {
            var category_details=[];
            if($(this).find('.cat_details').val()!='')
            {
               category_details=JSON.parse($(this).find('.cat_details').val());
            }
                if(category_details.length ==0|| $(this).find('.recd_gwt').val()=='' || $(this).find('.charge').val()=='' ){
    			row_validate = false;
		    }
        }
	});
	return row_validate;
}


$(document).on('change',".id_melting",function(){
    if($(this).is(":checked"))
    {
        $(this).closest('tr').find('.is_melting_select').val(1);
    }else{
        $(this).closest('tr').find('.is_melting_select').val(0);
    }
});
//Melting Receipt


//receipt payment

$(document).on('keyup','.cash_amt,.net_bank_amt', function(e){
    calculate_receipt_payment();
});

function calculate_receipt_payment()
{
    
    var total_amount=0;
    var cash_amt=0;
    var net_bank_amt=0;
    cash_amt = (isNaN($('.cash_amt').val()) || $('.cash_amt').val()=='' ? 0:$('.cash_amt').val());
    net_bank_amt = (isNaN($('.net_bank_amt').val()) || $('.net_bank_amt').val()=='' ? 0:$('.net_bank_amt').val());
    total_amount=parseFloat(cash_amt)+parseFloat(net_bank_amt);
    $('.total_amount').html(parseFloat(total_amount).toFixed(2));
}

//receipt payment




//Testing Issue
function get_testing_issue_details()
{
    $('#testing_process_details tbody').empty();
    $("div.overlay").css("display", "block"); 
    my_Date = new Date();
	$.ajax({
		url: base_url+"index.php/admin_ret_metal_process/get_testing_issue_details?nocache=" + my_Date.getUTCSeconds(),
		type:"POST",
		data:{'id_karigar':$('#karigar').val()},
		dataType:"JSON",
		cache:false,
		success:function(data){
		    if(data.length>0)
		    {
		        trHtml='';
		        $.each(data,function(key,items){
                    trHtml+='<tr>'
                    +'<td><input type="checkbox" class="id_melting"><input type="hidden" class="is_melting_select" name="testing_issue[is_melting_select][]" value="0"><input type="hidden" name="testing_issue[id_melting_recd][]" value="'+items.id_melting_recd+'"><input type="hidden" name="testing_issue[weight][]" value="'+items.received_wt+'"><input type="hidden" name="testing_issue[purity][]" value="'+items.purity+'"><input type="hidden" name="testing_issue[amount][]" value="'+items.amount+'">'+items.process_no+'</td>'
                    +'<td>'+items.category_name+'</td>'
                    +'<td>'+items.net_wt+'</td>'
                    +'<td>'+items.received_wt+'</td>'
                    +'<td>'+items.purity+'</td>'
                    +'<td>'+items.amount+'</td>'
                    +'<td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'
                    +'</tr>';
                });
                $('#testing_process_details tbody').append(trHtml);
                $("div.overlay").css("display", "none"); 
		    }else
		    {
		        $("div.overlay").css("display", "none"); 
		        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'No Record Found..'});
		    }
		}
	});
}


function get_testing_receipt_details()
{
    $('#testing_receipt tbody').empty();
    $("div.overlay").css("display", "block"); 
    my_Date = new Date();
	$.ajax({
		url: base_url+"index.php/admin_ret_metal_process/get_testing_receipt_details?nocache=" + my_Date.getUTCSeconds(),
		type:"POST",
		data:{'id_karigar':$('#karigar').val()},
		dataType:"JSON",
		cache:false,
		success:function(data){
		    if(data.length>0)
		    {
		        trHtml='';
		        $.each(data,function(key,items){
                    trHtml+='<tr>'
                    +'<td><input type="checkbox" name="testing_receipt_melting[]" class="testing_receipt_melting"><input type="hidden" class="is_melting_select" name="testing_receipt[is_melting_select][]" value="0"><input type="hidden" name="testing_receipt[id_metal_testing][]" value="'+items.id_metal_testing+'"><input type="hidden" name="testing_receipt[id_melting_recd][]" value="'+items.id_melting_recd+'"><input type="hidden" name="testing_receipt[received_category][]" value="'+items.received_category+'"><input type="hidden" name="testing_receipt[id_product][]" value="'+items.id_product+'">'+items.process_no+'</td>'
                    +'<td>'+items.category_name+'</td>'
                    +'<td><input type="hidden" class="form-control actual_wt" value="'+items.net_wt+'" >'+items.net_wt+'</td>'
                    +'<td>'+items.purity+'</td>'
                    +'<td>'+items.amount+'</td>'
                    +'<td><input type="number" name="testing_receipt[recd_gwt][]" class="form-control recd_gross_wt" value="" ></td>'
                    +'<td><input type="number" name="testing_receipt[received_less_wt][]" class="form-control received_less_wt" readonly></td>'
                    +'<td><input type="number" name="testing_receipt[received_purity][]" class="form-control received_purity" value=""></td>'
                    +'<td><input type="number" name="testing_receipt[received_amount][]" class="form-control received_amount" value="'+items.amount+'" readonly ></td>'
                    +'<td><input type="number" name="testing_receipt[receipt_charges][]" class="form-control receipt_charges"></td>'
                    +'<td><input type="text" name="testing_receipt[receipt_ref_no][]" class="form-control receipt_ref_no"></td>'
                    +'<td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'
                    +'</tr>';
                });
                $('#testing_receipt tbody').append(trHtml);
                $("div.overlay").css("display", "none"); 
		    }else
		    {
		        $("div.overlay").css("display", "none"); 
		        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'No Record Found..'});
		    }
		}
	});
}

$(document).on('keyup','.recd_gross_wt', function(e){
    var row = $(this).closest('tr');
    var actual_wt = row.find('.actual_wt').val();
    var recd_gross_wt = row.find('.recd_gross_wt').val();
    if(parseFloat(actual_wt)<parseFloat(recd_gross_wt))
    {
        row.find('.recd_gross_wt').val('');
        row.find('.received_less_wt').val(0);
        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Enter The Valid Weight..'});
    }
    else
    {
        row.find('.received_less_wt').val(parseFloat(parseFloat(actual_wt)-parseFloat(recd_gross_wt)).toFixed(3));
    }
});    

function validateTestingReceiptRow(){
	var row_validate = true;
	$('#testing_receipt > tbody  > tr').each(function(index, tr) {
	    if($(this).find('.testing_receipt_melting').is(":checked"))
        {
            if($(this).find('.recd_gross_wt').val()=='' || $(this).find('.recd_gross_wt').val()==0 || $(this).find('.received_purity').val()=='' || $(this).find('.received_purity').val()==0 || $(this).find('.receipt_charges').val()=='' ){
			row_validate = false;
		    }
        }
	});
	return row_validate;
}

function validateRefiningReceiptRow(){
	var row_validate = true;
	$('#refining_receipt > tbody  > tr').each(function(index, tr) {
	    if($(this).find('.testing_receipt_melting').is(":checked"))
        {
            if($(this).find('.recd_gross_wt').val()=='' || $(this).find('.recd_gross_wt').val()==0  || $(this).find('.receipt_charges').val()=='' ){
			row_validate = false;
		    }
        }
	});
	return row_validate;
}




function validateTestingIssueRow(){
    
    row_validate = false;
	$('#testing_receipt > tbody  > tr').each(function(index, tr) {
	    if($(this).find('.is_melting_select').val()==1)
        {
			row_validate = true;
			return row_validate;
        }
	});

}



$(document).on('change',".testing_receipt_melting",function(){
    if($(this).is(":checked"))
    {
        $(this).closest('tr').find('.is_melting_select').val(1);
    }else{
        $(this).closest('tr').find('.is_melting_select').val(0);
    }
});

//Testing Issue




//Refining issue
function get_RefiningIssueDetails()
{
    $('#refining_issue_details tbody').empty();
    $("div.overlay").css("display", "block"); 
    my_Date = new Date();
	$.ajax({
		url: base_url+"index.php/admin_ret_metal_process/get_RefiningIssueDetails?nocache=" + my_Date.getUTCSeconds(),
		type:"POST",
		data:{'id_karigar':$('#karigar').val()},
		dataType:"JSON",
		cache:false,
		success:function(data){
		    if(data.length>0)
		    {
		        trHtml='';
		        $.each(data,function(key,items){
                    trHtml+='<tr>'
                    +'<td><input type="checkbox" class="id_melting"><input type="hidden" class="is_melting_select" name="refining_issue[is_melting_select][]" value="0"><input type="hidden" name="refining_issue[id_melting_recd][]" value="'+items.id_melting_recd+'"><input type="hidden" name="refining_issue[id_metal_testing][]" value="'+items.id_metal_testing+'"><input type="hidden" name="refining_issue[weight][]" value="'+items.received_wt+'">'+items.process_no+'</td>'
                    +'<td>'+items.category_name+'</td>'
                    +'<td>'+items.received_wt+'</td>'
                    +'<td>'+items.purity+'</td>'
                    +'<td>'+items.amount+'</td>'
                    +'<td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'
                    +'</tr>';
                });
                $('#refining_issue_details tbody').append(trHtml);
                $("div.overlay").css("display", "none"); 
		    }else
		    {
		        $("div.overlay").css("display", "none"); 
		        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'No Record Found..'});
		    }
		}
	});
}



function get_refining_receipt_details()
{
    $('#refining_receipt tbody').empty();
    $("div.overlay").css("display", "block"); 
    my_Date = new Date();
	$.ajax({
		url: base_url+"index.php/admin_ret_metal_process/get_RefiningReceiptDetails?nocache=" + my_Date.getUTCSeconds(),
		type:"POST",
		data:{'id_karigar':$('#karigar').val()},
		dataType:"JSON",
		cache:false,
		success:function(data){
		    if(data.length>0)
		    {
		        trHtml='';
		        var category = "<option value=''>- Select Category-</option>";
		        $.each(category_details, function (mkey, mitem) {
            		category += "<option value='"+mitem.id_ret_category+"'>"+mitem.name+"</option>";
            	});	
	
	
		        $.each(data,function(key,items){
                    trHtml+='<tr id="'+parseFloat(key+1)+'">'
                    +'<td><input type="checkbox" class="testing_receipt_melting"><input type="hidden" class="is_melting_select" name="refining_receipt[is_melting_select][]" value="0"><input type="hidden" name="refining_receipt[id_metal_refining][]" value="'+items.id_metal_refining+'"><input type="hidden" name="refining_receipt[id_melting_recd][]" value="'+items.id_melting_recd+'">'+items.process_no+'</td>'
                    +'<td>'+items.category_name+'</td>'
                    +'<td><input type="hidden" class="form-control actual_wt" value="'+items.net_wt+'" >'+items.net_wt+'</td>'
                    +'<td>'+items.purity+'</td>'
                    +'<td>'+items.amount+'</td>'
                    +'<td><a href="#" class="btn btn-success btn-sm add_refining_receipt_category" id = "add_category"  data-toggle="modal"><i class="fa fa-plus"></i></a><input type="hidden" class="cat_details" name="refining_receipt[category_details][]"></td>'
                    +'<td><input type="number" name="refining_receipt[recd_gwt][]" class="form-control recd_gross_wt" value="" readonly></td>'
                    +'<td><input type="number" name="refining_receipt[received_less_wt][]" class="form-control received_less_wt" readonly></td>'
                    +'<td><input type="number" name="refining_receipt[receipt_charges][]" class="form-control receipt_charges"></td>'
                    +'<td><input type="text" name="refining_receipt[receipt_ref_no][]" class="form-control receipt_ref_no"></td>'
                    +'<td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'
                    +'</tr>';
                });
                $('#refining_receipt tbody').append(trHtml);
                $('#refining_receipt > tbody').find('.id_ret_category').select2();
                $("div.overlay").css("display", "none"); 
		    }else
		    {
		        $("div.overlay").css("display", "none"); 
		        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'No Record Found..'});
		    }
		}
	});
}




$(document).on('click', ".add_category", function(e) {
	//$('#category_modal').modal('toggle');
	var curRow=$(this).closest('tr');
	create_new_category_row(curRow);
});


$(document).on('change',".id_ret_category",function(){
   var curRow=$(this).closest('tr');
   if(this.value!='')
   {    
       id_category=this.value;
       var product = "<option value=''>- Select Product-</option>";
       $.each(prod_details,function(key,items){
           if(items.cat_id==id_category)
           {
            	product += "<option value='"+items.pro_id+"'>"+items.product_name+"</option>";
           }
       });
       curRow.find('.id_product').html(product);
       curRow.find('.id_product').select2();
   }
});

$('#add_new_category').on('click',function(){
    trHtml='';
    var category = "<option value=''>- Select Category-</option>";
    var product = "<option value=''>- Select Category-</option>";
    $.each(category_details, function (mkey, mitem) {
    category += "<option value='"+mitem.id_ret_category+"'>"+mitem.name+"</option>";
    });	
    trHtml+='<tr>'
    +'<td><select class="id_ret_category" name="receipt[id_ret_category][]" value="">'+category+'</select></td>'
    +'<td><select class="id_product" name="receipt[id_product][]" value="">'+product+'</select></td>'
    +'<td><input type="number" name="refining_receipt[recd_gwt][]" class="form-control recd_gross_wt" value="" ></td>'
    +'<td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'
    +'</tr>';
    $('#category_row tbody').append(trHtml);
    $('#category_row > tbody').find('.id_ret_category').select2();
});


function create_new_category_row(curRow)
{
    $('#category_modal').modal('toggle');
    $('#category_row tbody').empty();
    if(curRow!='' && curRow!=undefined)
    {
       trHtml='';
        console.log(curRow.find('.actual_wt').val());
        $('#active_id').val(curRow.closest('tr').attr('id'));
        var cat_details=curRow.find('.cat_details').val();
        
        
        if(cat_details!='')
        {
            let categories=JSON.parse(cat_details);
            $.each(categories,function(key,items){
                var category = "<option value=''>- Select Category-</option>";
                var product = "<option value=''>- Select Product-</option>";
                $.each(category_details, function (mkey, mitem) {
                     var cat_slelected="";
                      if(items.id_ret_category==mitem.id_ret_category)
                      {
                          cat_slelected="selected";
                      }
                      category += "<option value='"+mitem.id_ret_category+"' "+cat_slelected+" >"+mitem.name+"</option>";
                });	
                
                $.each(prod_details,function(key,pitems){
                   var prod_slelected="";
                   if(pitems.pro_id==items.id_product)
                   {
                    	prod_slelected="selected";
                   }
                   product += "<option value='"+pitems.pro_id+"' "+prod_slelected+">"+pitems.product_name+"</option>";
               });
           
                trHtml+='<tr>'
                    +'<td><select class="id_ret_category" name="receipt[id_ret_category][]" value="">'+category+'</select></td>'
                    +'<td><select class="id_product" name="receipt[id_product][]" value="">'+product+'</select></td>'
                    +'<td><input type="number" name="refining_receipt[recd_gwt][]" class="form-control recd_gross_wt" value="'+items.recd_gross_wt+'" ></td>'
                    +'<td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'
                +'</tr>';
            });
        }
        else
        {
            var category = "<option value=''>- Select Category-</option>";
            var product = "<option value=''>- Select Category-</option>";
             $.each(category_details, function (mkey, mitem) {
                category += "<option value='"+mitem.id_ret_category+"'>"+mitem.name+"</option>";
            });	
            trHtml+='<tr>'
                    +'<td><select class="id_ret_category" name="receipt[id_ret_category][]" value="">'+category+'</select></td>'
                    +'<td><select class="id_product" name="receipt[id_product][]" value="">'+product+'</select></td>'
                    +'<td><input type="number" name="refining_receipt[recd_gwt][]" class="form-control recd_gross_wt" value="" ></td>'
                    +'<td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'
                +'</tr>';
        }
        
    	$('#category_row tbody').append(trHtml);
        $('#category_row > tbody').find('.id_ret_category').select2();
    }
}


$('#category_modal  #update_category').on('click', function(){
	var cat_details=[];
	var recd_gross_wt=0;
	var catRow=$('#active_id').val();
	var gross_wt = 	$('#'+catRow).find('.gross_wt').val();
	
	$('#category_modal .modal-body #category_row> tbody  > tr').each(function(index, tr) {
	    recd_gross_wt+=parseFloat($(this).find('.recd_gross_wt').val());
		cat_details.push({'id_ret_category' : $(this).find('.id_ret_category').val(),'recd_gross_wt' :$(this).find('.recd_gross_wt').val(),'id_product' :$(this).find('.id_product').val()});
	});
	
	
    if(parseFloat(gross_wt)<parseFloat(recd_gross_wt))
    {
        $('#'+catRow).find('.recd_gwt').val(0);
        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Enter The Valid Weight..'});
    }
    else
    {
            $('#'+catRow).find('.recd_gwt').val(parseFloat(recd_gross_wt).toFixed(3));
            $('#'+catRow).find('.received_less_wt').val(parseFloat(parseFloat(gross_wt)-parseFloat(recd_gross_wt)).toFixed(3));
        	$('#category_modal').modal('toggle');
        	$('#'+catRow).find('.cat_details').val(cat_details.length>0 ? JSON.stringify(cat_details):'');
        	$('#'+catRow).find('.recd_gwt').val(parseFloat(recd_gross_wt).toFixed(3));
        	$('#category_modal .modal-body').find('#category_row tbody').empty();
    }

});

//Refining issue


//Polishing receipt

function get_polishing_receipt_details()
{
    $('#polishing_receipt tbody').empty();
    $("div.overlay").css("display", "block"); 
    my_Date = new Date();
	$.ajax({
		url: base_url+"index.php/admin_ret_metal_process/get_PolishingReceiptDetails?nocache=" + my_Date.getUTCSeconds(),
		type:"POST",
		data:{'id_karigar':$('#karigar').val()},
		dataType:"JSON",
		cache:false,
		success:function(data){
		    if(data.length>0)
		    {
		       var trHtml='';
		        $.each(data,function(key,items){
                    trHtml+='<tr id="'+parseFloat(key+1)+'">'
                    +'<td><input type="checkbox" name="polishing_receipt_select[]" class="id_polishing"><input type="hidden" class="is_polishing_select" name="polishing_receipt[is_polishing_select][]" value="0"><input type="hidden" name="polishing_receipt[id_polishing_details][]" value="'+items.id_polishing_details+'"><input type="hidden" name="polishing_receipt[id_old_metal_type][]" value="'+items.id_old_metal_type+'"><input type="hidden" name="polishing_receipt[id_polishing][]" value="'+items.id_polishing+'">'+items.process_no+'</td>'
                    +'<td>'+items.old_metal_type+'</td>'
                    +'<td><input type="hidden" class="form-control gross_wt" value="'+items.gross_wt+'" ><input type="hidden" class="form-control no_of_piece" name="polishing_receipt[no_of_piece][]" value="'+items.no_of_piece+'" >'+items.gross_wt+'</td>'
                    +'<td><input type="hidden" class="form-control actual_wt" value="'+items.net_wt+'" >'+items.net_wt+'</td>'
                    +'<td><a href="#" class="btn btn-success btn-sm add_polishing_receipt_category" id = "add_polishing_receipt_category"  data-toggle="modal"><i class="fa fa-plus"></i></a><input type="hidden" class="cat_details" name="polishing_receipt[category_details][]" value=""></td>'
                    +'<td><input type="number" name="polishing_receipt[recd_pcs][]" class="form-control recd_pcs" value="" readonly ></td>'
                    +'<td><input type="number" name="polishing_receipt[recd_gwt][]" class="form-control recd_gross_wt" value="" readonly ></td>'
                    +'<td><input type="number" name="polishing_receipt[recd_nwt][]" class="form-control recd_nwt" value="" readonly ></td>'
                    +'<td style="display:none;"><input type="number" name="polishing_receipt[received_less_wt][]" class="form-control received_less_wt"  readonly></td>'
                    +'<td><input type="number" name="polishing_receipt[receipt_charges][]" class="form-control receipt_charges"></td>'
                    +'<td><input type="text" name="polishing_receipt[receipt_ref_no][]" class="form-control receipt_ref_no"></td>'
                    +'<td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'
                    +'</tr>';
                });
                $('#polishing_receipt tbody').append(trHtml);
                $('#polishing_receipt > tbody').find('.id_ret_category').select2();
                $("div.overlay").css("display", "none"); 
		    }else
		    {
		        $("div.overlay").css("display", "none"); 
		        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'No Record Found..'});
		    }
		}
	});
}


$(document).on('click', ".add_polishing_receipt_category", function(e) {
	//$('#category_modal').modal('toggle');
	var curRow=$(this).closest('tr');
	create_new_polishing_category_row(curRow);
});

$(document).on('change',".refining_ret_category",function(){
   var curRow=$(this).closest('tr');
   if(this.value!='')
   {    
       id_category=this.value;
       var product = "<option value=''>- Select Product-</option>";
       var purity = "<option value=''>- Select Purity-</option>";
       $.each(prod_details,function(key,items){
           if(items.cat_id==id_category)
           {
            	product += "<option value='"+items.pro_id+"'>"+items.product_name+"</option>";
           }
       });
       
       $.each(purity_details,function(key,items){
           if(items.id_category==id_category)
           {
            	purity += "<option value='"+items.id_purity+"'>"+items.purity+"</option>";
           }
       });
       
       curRow.find('.id_product').html(product);
       curRow.find('.purity').html(purity);
       curRow.find('.id_product').select2();
       curRow.find('.purity').select2();
   }
});



function create_new_polishing_category_row(curRow)
{
    $('#polishing_category_modal').modal('toggle');
    $('#category_row tbody').empty();
    if(curRow!='' && curRow!=undefined)
    {
       trHtml='';
        console.log(curRow.find('.actual_wt').val());
        $('#active_id').val(curRow.closest('tr').attr('id'));
        var cat_details=curRow.find('.cat_details').val();
        
        
        if(cat_details!='')
        {
            let categories=JSON.parse(cat_details);
            $.each(categories,function(key,items){
                var category = "<option value=''>- Select Category-</option>";
                var product = "<option value=''>- Select Product-</option>";
                var purity = "<option value=''>- Select Purity-</option>";
                $.each(category_details, function (mkey, mitem) {
                     var cat_slelected="";
                      if(items.id_ret_category==mitem.id_ret_category)
                      {
                          cat_slelected="selected";
                      }
                      category += "<option value='"+mitem.id_ret_category+"' "+cat_slelected+" >"+mitem.name+"</option>";
                });	
                
                $.each(prod_details,function(key,pitems){
                   var prod_slelected="";
                   if(pitems.pro_id==items.id_product)
                   {
                    	prod_slelected="selected";
                   }
                   product += "<option value='"+pitems.pro_id+"' "+prod_slelected+">"+pitems.product_name+"</option>";
               });
               
                    $.each(purity_details,function(key,pitems){
                       var purity_slelected="";
                       if(pitems.id_category==items.id_ret_category)
                       {
                           if(pitems.id_purity==items.id_purity)
                           {
                            	purity_slelected="selected";
                           }
                           purity += "<option value='"+pitems.id_purity+"' "+purity_slelected+">"+pitems.purity+"</option>";
                       }
                      
                   });
               
               
           
                trHtml+='<tr>'
                    +'<td><input type="checkbox" class="is_non_tag" name="receipt[is_non_tag][]"  '+(items.is_non_tag==1 ? "checked value='1' " : "value='0'" )+' ></td>'
                    +'<td><select class="polishing_ret_category" name="receipt[id_ret_category][]" value="">'+category+'</select></td>'
                    +'<td><select class="id_product" name="receipt[id_product][]" value="">'+product+'</select></td>'
                    +'<td><select class="purity" name="receipt[purity][]" value="">'+purity+'</td>'
                    +'<td><input type="number" name="refining_receipt[recd_pcs][]" class="form-control recd_pcs" value="'+items.recd_pcs+'" ></td>'
                    +'<td><input type="number" name="refining_receipt[recd_gwt][]" class="form-control recd_gross_wt" value="'+items.recd_gross_wt+'" ></td>'
                    +'<td><input type="number" name="refining_receipt[recd_nwt][]" class="form-control recd_nwt" value="'+items.recd_nwt+'" ></td>'
                    +'<td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'
                +'</tr>';
            });
        }
        else
        {
            var category = "<option value=''>- Select Category-</option>";
            var product = "<option value=''>- Select Product-</option>";
            var purity = "<option value=''>- Select Purity-</option>";
             $.each(category_details, function (mkey, mitem) {
                category += "<option value='"+mitem.id_ret_category+"'>"+mitem.name+"</option>";
            });	
            trHtml+='<tr>'
                    +'<td><input type="checkbox" class="is_non_tag" name="receipt[is_non_tag][]" value="0"></td>'
                    +'<td><select class="polishing_ret_category" name="receipt[id_ret_category][]" value="">'+category+'</select></td>'
                    +'<td><select class="id_product" name="receipt[id_product][]" value="">'+product+'</select></td>'
                    +'<td><select class="purity" name="receipt[purity][]" value="">'+purity+'</td>'
                    +'<td><input type="number" name="refining_receipt[recd_pcs][]" class="form-control recd_pcs" value="" ></td>'
                    +'<td><input type="number" name="refining_receipt[recd_gwt][]" class="form-control recd_gross_wt" value="" ></td>'
                    +'<td><input type="number" name="refining_receipt[recd_nwt][]" class="form-control recd_nwt" value="" ></td>'
                    +'<td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'
                +'</tr>';
        }
        
    	$('#category_row tbody').append(trHtml);
        $('#category_row > tbody').find('.polishing_ret_category').select2();
    }
}

$(document).on('change',".is_non_tag",function()
{
	if($(this).is(":checked"))
	{
	    	$(this).closest('tr').find('.is_non_tag').val(1);
	}else{
	    $(this).closest('tr').find('.is_non_tag').val(0);
	}
});
	

$(document).on('change',".polishing_ret_category",function(){
   var curRow=$(this).closest('tr');
   
   if(this.value!='')
   {    
       id_category=this.value;
       var product = "<option value=''>- Select Product-</option>";
       var purity = "<option value=''>- Select Purity-</option>";
       is_non_tag = curRow.find('.is_non_tag').val();
       console.log(is_non_tag);
       $.each(prod_details,function(key,items){
           if(items.cat_id==id_category)
           {
                if(is_non_tag == 1)
                {
                    if(items.stock_type == 2)
                    {
                        product += "<option value='"+items.pro_id+"'>"+items.product_name+"</option>";
                    }
                }else
                {
                    product += "<option value='"+items.pro_id+"'>"+items.product_name+"</option>";
                }
            	
           }
       });
       
       $.each(purity_details,function(key,items){
           if(items.id_category==id_category)
           {
            	purity += "<option value='"+items.id_purity+"'>"+items.purity+"</option>";
           }
       });
       
       curRow.find('.id_product').html(product);
       curRow.find('.purity').html(purity);
       curRow.find('.id_product').select2();
       curRow.find('.purity').select2();
   }
});


$('#polishing_category_modal  #update_polishing_category').on('click', function(){
     if(validatePolishingReceiptCategoryRow())
     {
    	var cat_details=[];
    
    	var recd_gross_wt=0;
    	var recd_nwt=0;
    	var recd_pcs=0;
    	var catRow=$('#active_id').val();
    	var gross_wt = 	$('#'+catRow).find('.gross_wt').val();
    	var actual_wt = 	$('#'+catRow).find('.actual_wt').val();
    	
    	$('#polishing_category_modal .modal-body #category_row> tbody  > tr').each(function(index, tr) {
    	    recd_gross_wt+=parseFloat($(this).find('.recd_gross_wt').val());
    	    recd_nwt+=parseFloat($(this).find('.recd_nwt').val());
    	    recd_pcs+=parseFloat($(this).find('.recd_pcs').val());
    		cat_details.push({'is_non_tag':$(this).find('.is_non_tag').val(),'id_ret_category' : $(this).find('.polishing_ret_category').val(),'recd_pcs':$(this).find('.recd_pcs').val(),'recd_gross_wt' :$(this).find('.recd_gross_wt').val(),'recd_nwt' :$(this).find('.recd_nwt').val(),'id_product' :$(this).find('.id_product').val(),'id_purity' :$(this).find('.purity ').val(),'purity' :$(this).find('.purity option:selected').text()});
    	});
    	
    	if(parseFloat(parseFloat(gross_wt).toFixed(3)) < parseFloat(parseFloat(recd_gross_wt).toFixed(3)))
        {
            $('#'+catRow).find('.recd_gwt').val(0);
            $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Enter The Valid Gross Weight..'});
        }
        else if(parseFloat(parseFloat(actual_wt).toFixed(3))<parseFloat(parseFloat(recd_nwt).toFixed(3)))
        {
            $('#'+catRow).find('.recd_gwt').val(0);
            $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Enter The Valid Net Weight..'});
        }
        else
        {
            $('#'+catRow).find('.recd_pcs').val(parseFloat(recd_pcs).toFixed(0));
            $('#'+catRow).find('.recd_gross_wt').val(parseFloat(recd_gross_wt).toFixed(3));
            $('#'+catRow).find('.recd_nwt').val(parseFloat(recd_nwt).toFixed(3));
            $('#'+catRow).find('.received_less_wt').val(parseFloat(parseFloat(actual_wt)-parseFloat(recd_gross_wt)).toFixed(3));
            $('#polishing_category_modal').modal('toggle');
    	    $('#'+catRow).find('.cat_details').val(cat_details.length>0 ? JSON.stringify(cat_details):'');
    	    $('#polishing_category_modal .modal-body').find('#category_row tbody').empty();
        }
    }
	else
	{
	    $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Fill The Required Fields..'});
	}
});

$('#add_new_polishing_category').on('click',function(){
    if(validatePolishingReceiptCategoryRow())
    {
        trHtml='';
        var category = "<option value=''>- Select Category-</option>";
        var product = "<option value=''>- Select Product-</option>";
        var purity = "<option value=''>- Select Purity-</option>";
        var category_exists = false;
        $.each(category_details, function (mkey, mitem) {
            category += "<option value='"+mitem.id_ret_category+"'>"+mitem.name+"</option>";
        });	
        trHtml+='<tr>'
        +'<td><input type="checkbox" class="is_non_tag" name="receipt[is_non_tag][]" value="0"></td>'
        +'<td><select class="polishing_ret_category" name="receipt[id_ret_category][]" value="">'+category+'</select></td>'
        +'<td><select class="id_product" name="receipt[id_product][]" value="">'+product+'</select></td>'
        +'<td><select class="purity" name="receipt[purity][]" value="">'+purity+'</td>'
        +'<td><input type="number" name="refining_receipt[recd_pcs][]" class="form-control recd_pcs" value="" ></td>'
        +'<td><input type="number" name="refining_receipt[recd_gwt][]" class="form-control recd_gross_wt" value="" ></td>'
        +'<td><input type="number" name="refining_receipt[recd_nwt][]" class="form-control recd_nwt" value="" ></td>'
        +'<td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'
        +'</tr>';
        $('#polishing_category_modal .modal-body #category_row tbody').append(trHtml);
        $('#polishing_category_modal .modal-body #category_row > tbody').find('.polishing_ret_category').select2();
    }
    else
    {
        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Fill The Required Fields..'});
    }
});

function validatePolishingReceiptCategoryRow(){
	var row_validate = true;
	$('#polishing_category_modal .modal-body #category_row > tbody  > tr').each(function(index, tr) {
            if($(this).find('.polishing_ret_category').val()=='' || $(this).find('.polishing_ret_category').val()==null || $(this).find('.purity').val()==null || $(this).find('.purity').val()=='' || $(this).find('.recd_gross_wt').val()==0 || $(this).find('.recd_nwt').val()==0  || $(this).find('.recd_pcs').val()==0 ){
			row_validate = false;
		    }
	});
	return row_validate;
}


function get_category()
{
	$(".overlay").css('display','block');
	$('#select_category option').remove();
	$.ajax({
		type: 'POST',
		url: base_url+'index.php/admin_ret_catalog/category/active_category',
		dataType:'json',
		success:function(data){
		  var id_category =  $('#select_category').val();
		   $.each(data, function (key, item) {
			   		$('#select_category').append(
						$("<option></option>")
						  .attr("value", item.id_ret_category)
						  .text(item.name)
					);
			});
			$("#select_category").select2({
			    placeholder: "Select Category",
			    allowClear: true
			});
			$("#select_category").select2("val",(id_category!='' && id_category>0?id_category:''));
			 $(".overlay").css("display", "none");	
		}
	});
}

$('#select_category').select2().on("change", function(e) {
    if(this.value!='')
    {
        get_cat_purity(); 
        get_category_product(); 
    }
});

function get_category_product()
{
	$('#prod_select option').remove();  
	$("div.overlay").css("display", "block"); 
	$.ajax({
	type: 'POST',
	url: base_url+'index.php/admin_ret_reports/get_ActiveProduct',
	dataType:'json',
	data: {
		'id_category' :$('#select_category').val()
	},
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
		if($("#prod_select").length)
		{
		    $("#prod_select").select2("val",(id!='' && id>0?id:''));
		}
		    
		}
	});
	$("div.overlay").css("display", "none"); 
}

function get_cat_purity()
{
	$(".overlay").css('display','block');
	$('#select_purity option').remove();
	$.ajax({
		type: 'POST',
		url: base_url+'index.php/admin_ret_catalog/category/cat_purity',
		dataType:'json',
		data: {
			'id_category' :$('#select_category').val()
		},
		success:function(data){
		  var id_purity =  $('#id_purity').val();
		   $.each(data, function (key, item) {
			   		$('#select_purity').append(
						$("<option></option>")
						  .attr("value", item.id_purity)
						  .text(item.purity)
					);
			});
			$("#select_purity").select2({
			    placeholder: "Select Purity",
			    allowClear: true
			});
			$("#select_purity").select2("val",(id_purity!='' && id_purity>0?id_purity:''));
			 $(".overlay").css("display", "none");	
		}
	});
}

function validatePolishingReceiptRow(){
	var row_validate = true;
	$('#polishing_receipt > tbody  > tr').each(function(index, tr) {
	    if($(this).find('.id_polishing').is(":checked"))
        {
            if($(this).find('.recd_gross_wt').val()=='' || $(this).find('.recd_gross_wt').val()==0  || $(this).find('.receipt_charges').val()=='' ){
			row_validate = false;
		    }
        }
	});
	return row_validate;
}

$(document).on('change',".id_polishing",function(){
    if($(this).is(":checked"))
    {
        $(this).closest('tr').find('.is_polishing_select').val(1);
    }else{
        $(this).closest('tr').find('.is_polishing_select').val(0);
    }
});

//Polishing receipt


$(document).on('click', ".add_refining_receipt_category", function(e) {
	//$('#category_modal').modal('toggle');
	var curRow=$(this).closest('tr');
	create_new_refining_category_row(curRow);
});

$(document).on('change',".refining_ret_category",function(){
   var curRow=$(this).closest('tr');
   if(this.value!='')
   {    
       id_category=this.value;
       var product = "<option value=''>- Select Product-</option>";
       var purity = "<option value=''>- Select Purity-</option>";
       $.each(prod_details,function(key,items){
           if(items.cat_id==id_category)
           {
            	product += "<option value='"+items.pro_id+"'>"+items.product_name+"</option>";
           }
       });
       
       $.each(purity_details,function(key,items){
           if(items.id_category==id_category)
           {
            	purity += "<option value='"+items.id_purity+"'>"+items.purity+"</option>";
           }
       });
       
       curRow.find('.id_product').html(product);
       curRow.find('.purity').html(purity);
       curRow.find('.id_product').select2();
       curRow.find('.purity').select2();
   }
});



function create_new_refining_category_row(curRow)
{
    $('#refining_category_modal').modal('toggle');
    $('#category_row tbody').empty();
    if(curRow!='' && curRow!=undefined)
    {
       trHtml='';
        console.log(curRow.find('.actual_wt').val());
        $('#active_id').val(curRow.closest('tr').attr('id'));
        var cat_details=curRow.find('.cat_details').val();
        
        
        if(cat_details!='')
        {
            let categories=JSON.parse(cat_details);
            $.each(categories,function(key,items){
                var category = "<option value=''>- Select Category-</option>";
                var product = "<option value=''>- Select Product-</option>";
                var purity = "<option value=''>- Select Purity-</option>";
                $.each(category_details, function (mkey, mitem) {
                     var cat_slelected="";
                      if(items.id_ret_category==mitem.id_ret_category)
                      {
                          cat_slelected="selected";
                      }
                      category += "<option value='"+mitem.id_ret_category+"' "+cat_slelected+" >"+mitem.name+"</option>";
                });	
                
                $.each(prod_details,function(key,pitems){
                   var prod_slelected="";
                   if(pitems.pro_id==items.id_product)
                   {
                    	prod_slelected="selected";
                   }
                   product += "<option value='"+pitems.pro_id+"' "+prod_slelected+">"+pitems.product_name+"</option>";
               });
               
                    $.each(purity_details,function(key,pitems){
                       var purity_slelected="";
                       if(pitems.id_category==items.id_ret_category)
                       {
                           if(pitems.id_purity==items.id_purity)
                           {
                            	purity_slelected="selected";
                           }
                           purity += "<option value='"+pitems.id_purity+"' "+purity_slelected+">"+pitems.purity+"</option>";
                       }
                      
                   });
               
               
           
                trHtml+='<tr>'
                    +'<td><select class="refining_ret_category" name="receipt[id_ret_category][]" value="">'+category+'</select></td>'
                    +'<td><select class="id_product" name="receipt[id_product][]" value="">'+product+'</select></td>'
                    +'<td><select class="purity" name="receipt[purity][]" value="">'+purity+'</td>'
                    +'<td><input type="number" name="refining_receipt[recd_gwt][]" class="form-control recd_gross_wt" value="'+items.recd_gross_wt+'" ></td>'
                    +'<td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'
                +'</tr>';
            });
        }
        else
        {
            var category = "<option value=''>- Select Category-</option>";
            var product = "<option value=''>- Select Product-</option>";
            var purity = "<option value=''>- Select Purity-</option>";
             $.each(category_details, function (mkey, mitem) {
                category += "<option value='"+mitem.id_ret_category+"'>"+mitem.name+"</option>";
            });	
            trHtml+='<tr>'
                    +'<td><select class="refining_ret_category" name="receipt[id_ret_category][]" value="">'+category+'</select></td>'
                    +'<td><select class="id_product" name="receipt[id_product][]" value="">'+product+'</select></td>'
                    +'<td><select class="purity" name="receipt[purity][]" value="">'+purity+'</td>'
                    +'<td><input type="number" name="refining_receipt[recd_gwt][]" class="form-control recd_gross_wt" value="" ></td>'
                    +'<td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'
                +'</tr>';
        }
        
    	$('#category_row tbody').append(trHtml);
        $('#category_row > tbody').find('.id_ret_category').select2();
    }
}

$('#refining_category_modal  #update_refining_category').on('click', function(){
	var cat_details=[];

	var recd_gross_wt=0;
	var catRow=$('#active_id').val();
	var actual_wt = 	$('#'+catRow).find('.actual_wt').val();
	
	$('#refining_category_modal .modal-body #category_row> tbody  > tr').each(function(index, tr) {
	    recd_gross_wt+=parseFloat($(this).find('.recd_gross_wt').val());
		cat_details.push({'id_ret_category' : $(this).find('.refining_ret_category').val(),'recd_gross_wt' :$(this).find('.recd_gross_wt').val(),'id_product' :$(this).find('.id_product').val(),'id_purity' :$(this).find('.purity ').val(),'purity' :$(this).find('.purity option:selected').text()});
	});
	
	if(parseFloat(actual_wt)<parseFloat(recd_gross_wt))
    {
        $('#'+catRow).find('.recd_gwt').val(0);
        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Enter The Valid Weight..'});
    }
    else
    {
        $('#'+catRow).find('.recd_gross_wt').val(parseFloat(recd_gross_wt).toFixed(3));
        $('#'+catRow).find('.received_less_wt').val(parseFloat(parseFloat(actual_wt)-parseFloat(recd_gross_wt)).toFixed(3));
        $('#refining_category_modal').modal('toggle');
	    $('#'+catRow).find('.cat_details').val(cat_details.length>0 ? JSON.stringify(cat_details):'');
	    $('#refining_category_modal .modal-body').find('#category_row tbody').empty();
    }
    
	
});

$('#add_new_ref_category').on('click',function(){
    trHtml='';
    var category = "<option value=''>- Select Category-</option>";
    var product = "<option value=''>- Select Product-</option>";
    var purity = "<option value=''>- Select Purity-</option>";
    $.each(category_details, function (mkey, mitem) {
    category += "<option value='"+mitem.id_ret_category+"'>"+mitem.name+"</option>";
    });	
    trHtml+='<tr>'
    +'<td><select class="refining_ret_category" name="receipt[id_ret_category][]" value="">'+category+'</select></td>'
    +'<td><select class="id_product" name="receipt[id_product][]" value="">'+product+'</select></td>'
    +'<td><select class="purity" name="receipt[purity][]" value="">'+purity+'</td>'
    +'<td><input type="number" name="refining_receipt[recd_gwt][]" class="form-control recd_gross_wt" value="" ></td>'
    +'<td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'
    +'</tr>';
    $('#refining_category_modal .modal-body #category_row tbody').append(trHtml);
    $('#refining_category_modal .modal-body #category_row > tbody').find('.refining_ret_category').select2();
});

$('#metal_process_search').on('click',function(){
    get_metal_process_report();
});


function get_metal_process_report()
{
    $("div.overlay").css("display", "block"); 
    my_Date = new Date();
	$.ajax({
		url: base_url+"index.php/admin_ret_metal_process/process_report/ajax?nocache=" + my_Date.getUTCSeconds(),
		type:"POST",
		data:{'from_date':$('#rpt_payments1').html(),'to_date':$('#rpt_payments2').html(),'id_karigar':$('#karigar').val(),'id_metal_process':$('#select_process').val()},
		dataType:"JSON",
		cache:false,
		success:function(data){
		    var list=data.list;
		    var access=data.access;
			var oTable = $('#process_details').DataTable();
			oTable.clear().draw();
			
			if (list!= null && list.length > 0)
			{  	
				oTable = $('#process_details').dataTable({
					"bDestroy": true,
					"bDestroy": true,
					"bInfo": true,
					"bFilter": true,
					"bSort": true,
					"order": [[ 0, "desc" ]],
					"aaData"  : list,
					"aoColumns": [	
									{ "mDataProp": "id_old_metal_process" },
									{ "mDataProp": "process_name" },
									{ "mDataProp": "process_no" },
									{ "mDataProp": "issue_date" },
									{ "mDataProp": "karigar_name" },
									{ "mDataProp": "issue_wt" },
									{ "mDataProp": "process_status" },
									{ "mDataProp": "received_wt" },
								  ],
    							    "footerCallback": function( row, data, start, end, display )
            						{ 
    									if(list.length>0){
    										var api = this.api(), data;
    
    										for( var i=0; i<=data.length-1;i++){
    
    											var intVal = function ( i ) {
    												return typeof i === 'string' ?
    												i.replace(/[\$,]/g, '')*1 :
    												typeof i === 'number' ?
    												i : 0;
    											};	
    											
    											$(api.column(0).footer() ).html('Total');	
    
    											issue_wgt = api
    											.column(5)
    											.data()
    											.reduce( function (a, b) {
    												return intVal(a) + intVal(b);
    											}, 0 );
    											$(api.column(5).footer()).html(parseFloat(issue_wgt).toFixed(3));
    											
    											recd_wgt = api
    											.column(7)
    											.data()
    											.reduce( function (a, b) {
    												return intVal(a) + intVal(b);
    											}, 0 );
    											$(api.column(7).footer()).html(parseFloat(recd_wgt).toFixed(3));
    											
    									} 
    									}else{
    										 var api = this.api(), data; 
    										 $(api.column(5).footer()).html('');
    									}
        							} 
				});

			}
			$("div.overlay").css("display", "none"); 
		}
	});
}

$('#metal_process_detailed_search').on('click',function(){
    get_metal_process_detailed_report();
});

function get_metal_process_detailed_report()
{
    $("div.overlay").css("display", "block"); 
    my_Date = new Date();
	$.ajax({
		url: base_url+"index.php/admin_ret_metal_process/process_report/detail_porcess_report?nocache=" + my_Date.getUTCSeconds(),
		type:"POST",
		data:{'from_date':$('#rpt_payments1').html(),'to_date':$('#rpt_payments2').html(),'id_karigar':$('#karigar').val(),'id_metal_process':$('#select_process').val()},
		dataType:"JSON",
		cache:false,
		success:function(data){
		    var list=data;
		    
			var oTable = $('#process_details').DataTable();
			oTable.clear().draw();
			
			if (list!= null && list.length > 0)
			{  	
				oTable = $('#process_details').dataTable({
					"bDestroy": true,
					"bDestroy": true,
					"bInfo": true,
					"bFilter": true,
					"bSort": true,
					"order": [[ 0, "desc" ]],
					"aaData"  : list,
					"aoColumns": [	
									{ "mDataProp": "pocket_no" },
									{
                                        "mDataProp": null,
                                        "sClass": "control center", 
                                        "sDefaultContent":  '<span class="drill-val"><i class="fa fa-chevron-circle-down text-teal"></i></span>'
                                    },
									{ "mDataProp": "date_add" },
									{ "mDataProp": "piece" },
									{ "mDataProp": "gross_wt" },
									{ "mDataProp": "net_wt" },
								  ],
    					    
				});
				
				var anOpen =[]; 
                    		$(document).on('click',"#process_details .control", function(){ 
                    		   var nTr = this.parentNode;
                    		   var i = $.inArray( nTr, anOpen );
                    		 
                    		   if ( i === -1 ) { 
                    				$('.drill-val', this).html('<i class="fa fa-chevron-circle-up text-teal"></i>'); 
                    				oTable.fnOpen( nTr, fnFormatRowProcessDetails(oTable, nTr), 'details' );
                    				anOpen.push( nTr ); 
                    		    }
                    		    else { 
                    				$('.drill-val', this).html('<i class="fa fa-chevron-circle-down text-teal"></i>');
                    				oTable.fnClose( nTr );
                    				anOpen.splice( i, 1 );
                    		    }
                    		} );

			}
			$("div.overlay").css("display", "none"); 
		}
	});
}


function fnFormatRowProcessDetails( oTable, nTr )
{
  var oData = oTable.fnGetData( nTr );
  var rowDetail = '';
  var prodTable = 
     '<div class="innerDetails">'+
      '<table class="table table-responsive table-bordered text-center table-sm">'+ 
        '<tr class="bg-teal">'+
            '<th colspan="4"></th>'+ 
            '<th colspan="3">ISSUE</th>'+
            '<th colspan="3">RECEIVED</th>'+
        '</tr>'+
        '<tr>'+
            '<th colspan="1"></th>'+ 
            '<th>PROCESS</th>'+ 
            '<th>PROCESS NO</th>'+ 
            '<th>DATE</th>'+ 
            '<th>PCS</th>'+
            '<th>GWT</th>'+
            '<th>NWT</th>'+
            '<th>PCS</th>'+
            '<th>GWT</th>'+
            '<th>NWT</th>'+
        '</tr>';
        
    var polish_details = oData.polish_details; 
    var melting_details = oData.melting_details;
    var testing_details = oData.testing_details;
    var s_no = 1;
      $.each(melting_details, function (idx, val) {
      	 if(val.process_no!='')
      	 {
             print_url=base_url+'index.php/admin_ret_metal_process/process_acknowladgement/'+val.id_old_metal_process;
      	     prodTable += 
            '<tr class="prod_det_btn">'+
            '<td>'+parseFloat(s_no)+'</td>'+
            '<td>'+val.process_name+'</td>'+
            '<td><a href='+print_url+' target="_blank">'+val.process_no+'</a></td>'+
            '<td>'+val.date_add+'</td>'+
            '<td>'+val.issue_pcs+'</td>'+
            '<td>'+val.issue_gwt+'</td>'+
            '<td>'+val.issue_nwt+'</td>'+
            '<td></td>'+
            '<td>'+val.received_wt+'</td>'+
            '<td>'+val.received_wt+'</td>'+
            '</tr>';
      	 }
      s_no++;
      });
      
      $.each(polish_details, function (idx, val) {
      	 if(val.process_no!='')
      	 {
      	     print_url=base_url+'index.php/admin_ret_metal_process/process_acknowladgement/'+val.id_old_metal_process;
      	     prodTable += 
            '<tr class="prod_det_btn">'+
            '<td>'+parseFloat(s_no)+'</td>'+
            '<td>'+val.process_name+'</td>'+
            '<td><a href='+print_url+' target="_blank">'+val.process_no+'</a></td>'+
            '<td>'+val.date_add+'</td>'+
            '<td>'+val.issue_pcs+'</td>'+
            '<td>'+val.issue_gwt+'</td>'+
            '<td>'+val.issue_nwt+'</td>'+
            '<td>'+val.received_pcs+'</td>'+
            '<td>'+val.received_gwt+'</td>'+
            '<td>'+val.received_nwt+'</td>'+
            '</tr>';
      	 }
      s_no++;
      }); 
      
       $.each(testing_details, function (idx, val) {
      	 if(val.process_no!='')
      	 {
      	     print_url=base_url+'index.php/admin_ret_metal_process/process_acknowladgement/'+val.id_old_metal_process;
      	     prodTable += 
            '<tr class="prod_det_btn">'+
            '<td>'+parseFloat(s_no)+'</td>'+
            '<td>'+val.process_name+'</td>'+
            '<td><a href='+print_url+' target="_blank">'+val.process_no+'</a></td>'+
            '<td>'+val.date_add+'</td>'+
            '<td></td>'+
            '<td></td>'+
            '<td>'+val.issue_nwt+'</td>'+
            '<td></td>'+
            '<td></td>'+
            '<td>'+val.received_wt+'</td>'+
            '</tr>';
      	 }
      s_no++;
      });
      
  rowDetail = prodTable+'</table></div>';
  return rowDetail;
}

var path =  url_params();
var ctrl_page = path.route.split('/');
var img_resource=[];
var total_files=[];
var tax_details=[];
var pre_img_files=[];
var pre_img_resource=[];
var cat_product_details = [];
var stockIssueDetails = [];
$(document).ready(function() {
 
	 var path =  url_params();
	 $('#status').bootstrapSwitch();
	 $('.date').datepicker({ dateFormat: 'yyyy-mm-dd', })
	 $('body').addClass("sidebar-collapse");	
     
     switch(ctrl_page[1])
	 {
	 	case 'stock_issue':
	 		    switch(ctrl_page[2]){				 	
				 	case 'list':	
				 	    set_stock_issue_list();
				 	break;
				 	case 'add':
					    get_all_employee();
					    get_stock_issue_type();
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
	 		
	 		
	}
	
});

function get_stock_issue_type()
{
    $.ajax({
	type: 'GET',
	url: base_url+'index.php/admin_ret_stock_issue/get_stock_issue_type',
	dataType:'json',
	success:function(data){
	    
	       $.each(data, function (key, item) {   
    		    $("#issue_type").append(
    		    $("<option></option>")
    		    .attr("value", item.id_stock_issue_type)    
    		    .text(item.name)  
    		    );
    		}); 
    		$("#issue_type").select2(
    		{
    			placeholder:"Select Issue Type",
    			closeOnSelect: true		    
    		});
    		$("#issue_type").select2("val",'');

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






$('#stock_issue_submit').on('click',function(){
    var issue_type = $('#issue_type').val();  
    var issue_receipt_type = $("input[name='order[issue_receipt_type]']:checked").val();  
    var allow_submit=true;
   if($('#branch_select').val()=='' || $('#branch_select').val()==null)
   {
       $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select Branch..'});
       allow_submit=false;
   }
   else if((issue_type=='' || issue_type==null) && (issue_receipt_type==1))
   {
       $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select Issue Type..'});
       allow_submit=false;
   }
   else if(($('#issue_employee').val()=='' || $('#issue_employee').val()==null) && (issue_receipt_type==1 ))
   {
       $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select Employee..'});
       allow_submit=false;
   }
   else if(($('#tagissue_item_detail > tbody  > tr').length==0) && (issue_receipt_type==1 ))
   {
        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'No Records to Update..'});
        allow_submit=false;
   }
   else if(($('#tag_receipt_item_detail > tbody  > tr').length==0) && (issue_receipt_type==2 ))
   {
        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'No Records to Update..'});
        allow_submit=false;
   }
   if(allow_submit)
   {
        var form_data=$('#stock_issue_form').serialize();
		$('#stock_issue_submit').prop('disabled',true);
		var url=base_url+ "index.php/admin_ret_stock_issue/stock_issue/save?nocache=" + my_Date.getUTCSeconds();
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
				    var issue_receipt_type = $("input[name='order[issue_receipt_type]']:checked").val();  
				    if(issue_receipt_type==1)
				    {
				        window.open( base_url+'index.php/admin_ret_stock_issue/stock_issue/issue_print/'+data['id_stock_issue'],'_blank');
				        
				    }
				    location.href=base_url+'index.php/admin_ret_stock_issue/stock_issue/list';
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
			 url:base_url+ "index.php/admin_ret_stock_issue/stock_issue?nocache=" + my_Date.getUTCSeconds(),
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
									
										{ "mDataProp": function ( row, type, val, meta ) {
                                            id= row.id_stock_issue;
                                            print_url=base_url+'index.php/admin_ret_stock_issue/stock_issue/issue_print/'+id;
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
    		        html+='<tr>'
    		                +'<td><input type="hidden" class="tag_id" name="tag_id[]" value="'+items.value+'"><input type="hidden" class="tag_code" name="tag_code[]" value="'+items.label+'">'+items.label+'</td>'
    		                +'<td>'+items.catname+'</td>'
    		                +'<td>'+items.purname+'</td>'
    		                +'<td>'+items.product_name+'</td>'
    		                +'<td>'+items.design_name+'</td>'
    		                +'<td>'+items.sub_design_name+'</td>'
    		                +'<td><input type="hidden" class="piece" value="'+items.piece+'">'+items.piece+'</td>'
    		                +'<td><input type="hidden" class="gross_wt" value="'+items.gross_wt+'">'+items.gross_wt+'</td>'
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
		url:base_url+ "index.php/admin_ret_stock_issue/get_StockIssuedItems?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),
        type:"GET",
        dataType:"JSON",
        success:function(data)
        {   
        	    var id=$('#select_issue_no').val();
        	    stockIssueDetails=data;    
                $.each(data, function (key, item) {					  				  			   		
                    $("#select_issue_no").append(						
                    $("<option></option>")						
                    .attr("value", item.id_stock_issue)						  						  
                    .text(item.issue_no)						  					
                    );			   											
                });	
            
             	$("#select_issue_no").select2({			    
            	 	placeholder: "Select Issue No",			    
            	 	allowClear: true		    
             	});	
             	
             
             	$("#select_issue_no").select2("val","");

         	    $(".overlay").css("display", "none");	
        },
        error:function(error)  
        {	
        } 
    	});
}



$('#receipt_tag_search').on('click',function(){
    var allow_search=false;
    var tag_code=$('#receipt_tag_code').val();
    if($('#select_issue_no').val()!='' && $('#select_issue_no').val()!=null)
    {
        if(tag_code!='')
        {
            $.each(stockIssueDetails,function(k,items){
                if(items.id_stock_issue==$('#select_issue_no').val())
                {
                    $.each(items.tag_details,function(key,tags){
                       if(tags.tag_code==tag_code)
                       {
                           allow_search=true;
                       }
                    });
                }
            });
            if(allow_search)
            {
                tag_search=true;
                $('#tag_receipt_item_detail > tbody tr').each(function(idx, row){
                    curRow = $(this);
                    if(curRow.find('.tag_code').val()==tag_code)
                    {
                        $('#receipt_tag_code').val('');
                        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Tag Already Exists.'});
                        tag_search=false;
                        return false;
                    }
                });
                if(tag_search)
                {
                    get_receipt_tag_details(tag_code);
                }
            }
            else
            {
                $('#receipt_tag_code').val('');
                $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Invalid Tag Code.'});
            }
        }
        else
        {
             $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Enter The Tag Code.'});
        }
    }
    else
    {
        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select Issue No.'});
    }
});


function get_receipt_tag_details(tag_code)
{
    var issue_type = $('#issue_type').val();  
    my_Date = new Date();
	$.ajax({
		type:"POST",
		url: base_url+"index.php/admin_ret_stock_issue/get_receipt_tag_scan_details?nocache=" + my_Date.getUTCSeconds(),
		cache:false,		
		dataType:"JSON",
		data:{'tag_code' : tag_code,'id_branch': $("#branch_select").val()},
		success:function(data){
		    if(data.length>0)
		    {
		        var html = "";
        	    $.each(data,function(key,items){
    		        html+='<tr>'
    		                +'<td><input type="hidden" class="tag_id" name="tag_id[]" value="'+items.value+'"><input type="hidden" class="tag_code" name="tag_code[]" value="'+items.label+'">'+items.label+'</td>'
    		                +'<td>'+items.catname+'</td>'
    		                +'<td>'+items.purname+'</td>'
    		                +'<td>'+items.product_name+'</td>'
    		                +'<td>'+items.design_name+'</td>'
    		                +'<td>'+items.sub_design_name+'</td>'
    		                +'<td><input type="hidden" class="piece" value="'+items.piece+'">'+items.piece+'</td>'
    		                +'<td><input type="hidden" class="gross_wt" value="'+items.gross_wt+'">'+items.gross_wt+'</td>'
    		                +'<td>'+items.net_wt+'</td>'
    		                +'<td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'
    		               +'</tr>';
    		        });
	            if($('#tag_receipt_item_detail > tbody  > tr').length>0)
            	{
            	    $('#tag_receipt_item_detail > tbody > tr:first').before(html);
            	}else{
            	    $('#tag_receipt_item_detail tbody').append(html);
            	}
            	calculate_tag_receipt_details();
            	$('#receipt_tag_code').val('');
            	$('#receipt_tag_code').focus();
		    }
		    else
		    {
		        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'No Records Found.'});
		    }
		}
	});
}


function calculate_tag_receipt_details()
{
    var total_pcs=0;
    var total_gwt=0;
    $('#tag_receipt_item_detail > tbody tr').each(function(idx, row){
         curRow = $(this);
         total_pcs+=parseFloat(curRow.find('.piece').val());
         total_gwt+=parseFloat(curRow.find('.gross_wt').val());
    });
    $('.receipt_total_pieces').html(total_pcs);
    $('.receipt_total_gross_wt').html(parseFloat(total_gwt).toFixed(3));
}
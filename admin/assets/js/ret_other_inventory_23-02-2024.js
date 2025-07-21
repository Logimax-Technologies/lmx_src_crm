var path =  url_params();

var other_inventory_item = [];

var branch_details = [];

var ctrl_page = path.route.split('/');



$(document).ready(function() {

	

     $('.date').datepicker({ dateFormat: 'yyyy-mm-dd', })

     switch(ctrl_page[1])

	 {

	 	case 'inventory_category':

				 switch(ctrl_page[2]){				 	

				 	case 'list':				 	

				 			get_item_category_list();

				 	break;

				 	}

		 break;

		 case 'other_inventory':

		 		switch(ctrl_page[2]){

		 			case 'add':

		 				get_item_size_list();

		 				get_uom_list();

		 				get_itemfor_list();

		 				get_branch_details();

		 			break;

		 			case 'edit':

		 				get_item_size_list();

		 				get_uom_list();

		 				get_itemfor_list();

		 			break;

		 			case 'list':

		 				set_other_inventory();

		 			break;

		 		}

		 break;

		 

		 case 'purchase_entry':

		     switch(ctrl_page[2])

		     {

		        case 'add':

		            get_other_inventory_item();

		            get_supplier();

		        break;

		        case 'list':

		            

    		        var date = new Date();

                    var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 0, 1);

                    var from_date=(firstDay.getDate()+"-"+(firstDay.getMonth() + 1)+"-"+firstDay.getFullYear());

                    var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());

                    $('#from_date').html(from_date);

                    $('#to_date').html(to_date);

                    get_other_inventory_purchase_items();

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

                    startDate: moment().subtract(6, 'days'),

                    endDate: moment()

                    },

                    function(start, end)

                    {

                        $('#date_range_picker').html(start.format('D/M/YYYY') + ' - ' + end.format('D/M/YYYY'));

                        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

                        $('#from_date').text(start.format('DD-MM-YYYY'));

                        $('#to_date').text(end.format('DD-MM-YYYY'));  

                    }

                    );

                

		            

		        break;

		     }

		     

		 break;

		 

		 case 'stock_details':

		     switch(ctrl_page[2])

		     {

		        

		        case 'list':

		            

    		        var date = new Date();

                    var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 0, 1);

                    var from_date=(firstDay.getDate()+"-"+(firstDay.getMonth() + 1)+"-"+firstDay.getFullYear());

                    var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());

                    $('#from_date').html(from_date);

                    $('#to_date').html(to_date);

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

                    startDate: moment().subtract(6, 'days'),

                    endDate: moment()

                    },

                    function(start, end)

                    {

                        $('#date_range_picker').html(start.format('D/M/YYYY') + ' - ' + end.format('D/M/YYYY'));

                        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

                        $('#from_date').text(start.format('DD-MM-YYYY'));

                        $('#to_date').text(end.format('DD-MM-YYYY'));  

                    }

                    );

                

		            

		        break;

		     }

		     

		 break;

		 

		 case 'issue_item':

		     

		     

	            var date = new Date();

                var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 0, 1);

                var from_date=(firstDay.getDate()+"-"+(firstDay.getMonth() + 1)+"-"+firstDay.getFullYear());

                var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());

                $('#from_date').html(from_date);

                $('#to_date').html(to_date);

                get_other_inventory_item_issue_details();

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

                startDate: moment().subtract(6, 'days'),

                endDate: moment()

                },

                function(start, end)

                {

                    $('#date_range_picker').html(start.format('D/M/YYYY') + ' - ' + end.format('D/M/YYYY'));

                    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

                    $('#from_date').text(start.format('DD-MM-YYYY'));

                    $('#to_date').text(end.format('DD-MM-YYYY'));  

                }

                );

                    

		     if($('#id_branch').val()!='')

		     {

		         get_invnetory_item();

		         get_bill_details();

		     }

		 break;

		 

		 case 'item_size':

		     set_packing_item_size_list();

		 break;

		 

		 case 'available_stock':

		     get_invnetory_item_list();

		     get_item_size_list();

		 break;

		 

		 case 'product_mapping':

		     get_other_inventory_item();

		     get_ActiveProduct();

		     get_product_mapping_details();

		 break;

		 

		 case 'reorder_report':

		     reorder_report();

		 break;

	}

	

});





function get_item_category_list()

{

    $("div.overlay").css("display", "block"); 

	my_Date = new Date();

	$.ajax({

			 url:base_url+ "index.php/admin_ret_other_inventory/inventory_category/ajax?nocache=" + my_Date.getUTCSeconds(),

			 dataType:"JSON",

			 type:"POST",

			 success:function(data){

			 	var access=data.access;

			 	var list=data.list;

				var oTable = $('#item_list').DataTable();

				oTable.clear().draw();				  

				if (list!= null && list.length > 0)

				{  	

					oTable = $('#item_list').dataTable({

                    "bDestroy": true,

                    "bInfo": true,

                    "bFilter": true,

                    "bSort": true,

                    "order": [[ 0, "desc" ]],

                    "aaData": list,

                    "aoColumns": [	{ "mDataProp": "id_other_item_type" },

                    { "mDataProp": "name" },

                    { "mDataProp": function ( row, type, val, meta ){

                    id=row.id_other_item_type

                    if(row.asbillable == 1)

                    {

                    return 'Cost';

                    }

                    else

                    {

                    return 'Free';

                    }

                    }

                    },

                    { "mDataProp": function ( row, type, val, meta ){

                    id=row.id_other_item_type

                    if(row.expirydatevalidate == 1)

                    {

                    return 'Having';

                    }

                    else

                    {

                    return 'No Validity';

                    }

                    }

                    },

                    { "mDataProp": function ( row, type, val, meta ){

                    status_url = base_url+"index.php/admin_ret_other_inventory/otheritem_status/"+(row.status==1?0:1)+"/"+row.id_other_item_type; 

                    return "<a href='"+status_url+"'><i class='fa "+(row.status==1?'fa-check':'fa-remove')+"' style='color:"+(row.status==1?'green':'red')+"'></i></a>"

                    }

                    },

                    { "mDataProp": function ( row, type, val, meta ) {

                    id= row.id_other_item_type

                    edit_url=(access.edit=='1' ? base_url+'index.php/admin_ret_other_inventory/inventory_category/edit/'+id : '#' );

                    delete_url=(access.delete=='1' ? base_url+'index.php/admin_ret_other_inventory/inventory_category/delete/'+id : '#' );

                    delete_confirm= (access.delete=='1' ?'#confirm-delete':'');

                    action_content='<a href="'+edit_url+'" class="btn btn-primary btn-edit"><i class="fa fa-edit" ></i></a><a href="#" class="btn btn-danger btn-del" data-href='+delete_url+' data-toggle="modal" data-target="#confirm-delete" ><i class="fa fa-trash"></i></a>';

                    return action_content;

                    }

                    }]

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





//Other Inventory Item



$("#other_item_img").change( function(){

    event.preventDefault(); 

    validateImage(this);

});



function validateImage()

{



    var preview = $('#other_item_img_preview');

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

        if(ext != "jpg" && ext != "png" && ext != "jpeg" && ext != "svg")

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



function set_other_inventory()

{

    my_Date = new Date();

    $.ajax(

    {

    url:base_url+ "index.php/admin_ret_other_inventory/other_inventory/ajax?nocache=" + my_Date.getUTCSeconds(),

    dataType:"JSON",

    type:"POST",

    success:function(data){

    //console.log(data);

    var item 	= data.list;

    var access		= data.access;

    console.log(item);	

    if(access.add == '0')

    { 

    $('#add_details').attr('disabled','disabled');

    }

    var oTable = $('#other_item').DataTable();

    oTable.clear().draw();

    if (item!= null && item.length > 0)

    {  	

        oTable = $('#other_item').dataTable({

        "bDestroy": true,

        "bInfo": true,

        "bFilter": true,

        "bSort": true,

        "order": [[ 0, "desc" ]],

        "aaData": item,

        "aoColumns": [	{ "mDataProp": "id_other_item" },

        {"mDataProp": "name" },

        {"mDataProp": "sku_id" },

        { "mDataProp": function ( row, type, val, meta ) {

            if(row.image!='')

            {

                var img_src=base_url+'assets/img/other_inventory/'+row.sku_id+'/'+row.image;

                return '<a href="'+img_src+'" target="_blank"><img class="img_src" src="'+img_src+'" width="30" height="30"></a>';

            }

            else

            {

                var img_src=base_url+'assets/img/no_image.png';

                return '<img class="img_src" src="'+img_src+'" width="30" height="30">';

            }

        }

        },

        { "mDataProp": function ( row, type, val, meta ) {

            id= row.id_other_item

            edit_url=(access.edit=='1' ? base_url+'index.php/admin_ret_other_inventory/other_inventory/edit/'+id : '#' );

            delete_url=(access.delete=='1' ? base_url+'index.php/admin_ret_other_inventory/other_inventory/delete/'+id : '#' );

            delete_confirm= (access.delete=='1' ?'#confirm-delete':'');

            print_url=(access.edit=='1' ? base_url+'index.php/admin_ret_other_inventory/other_inventory/print_qrcode/'+id : '#' );

            action_content='<a href="'+edit_url+'" class="btn btn-primary btn-edit"><i class="fa fa-edit" ></i></a><a href="#" class="btn btn-danger btn-del" data-href='+delete_url+' data-toggle="modal" data-target="#confirm-delete" ><i class="fa fa-trash"></i></a><a href="'+print_url+'"target="_blank"  class="btn btn-info btn-print"><i class="fa fa-print" ></i></a>';

            return action_content;

        }

        }]

        });			  	 	

    }

    },

    error:function(error)  

    {

    $("div.overlay").css("display", "none"); 

    }	 

    });

}





if($('#sku_id').length>0)

{

    $('#sku_id').on('blur onchange',function(){

    if(this.value.length > 0)

    {

        check_skuid_avail(this.value);

    }

    else

    {

        $(this).val();

        $(this).attr('placeholder', 'Enter sku id');

        $(this).focus();

    }

    });

}



function check_skuid_avail(sku_id)

{ 

    $("div.overlay").css("display", "block");

    $.ajax({

        type: 'POST',

        data:{'sku_id':sku_id},

        url:  base_url+'index.php/admin_ret_other_inventory/check_sku_id',

        dataType: 'json',

        success: function(avail) {

            if(avail==1)

            {

                $('#sku_id').val('');

                $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>Id already exists"});

            }

            $("div.overlay").css("display", "none");  

        },

        error:function(error)  

        {

        $("div.overlay").css("display", "none"); 

        }

    });	

}



//Other Inventory Item



function get_itemfor_list()

{

    $.ajax({

        type: 'GET',

        url: base_url+'index.php/admin_ret_other_inventory/get_inventory_category',

        dataType:'json',

        success:function(data)

        {

        var id =  $("#item_for").val();

        $.each(data, function (key, item) 

        {      

            $("#itemfor").append(

            $("<option></option>")

            .attr("value",item.id_other_item_type)    

            .text(item.name)  

            );

        });

        

        $("#itemfor").select2(

		{

    		placeholder:"Select Category",

    		allowClear: true    

		});

		

        $("#itemfor").select2("val",(id!='' && id>0?id:''));

        $(".overlay").css("display", "none");

        }

    });

}





function get_uom_list()

{

    $.ajax({

    type: 'GET',

    url: base_url+'index.php/admin_ret_catalog/uom/active_uom',

    dataType:'json',

    success:function(data){

    var id =  $("#id_uom").val();

    $.each(data, function (key, item) {      

        $("#select_uom").append(

        $("<option></option>")

        .attr("value",item.uom_id)    

        .text(item.uom_name)  

        );

    });

    

    $("#select_uom").select2(

	{

		placeholder:"Select UOM",

		allowClear: true    

	});

		

    $("#select_uom").select2("val",(id!='' && id>0?id:''));

    }

    });

}



function get_item_size_list()

{

    $.ajax({

    type: 'GET',

    url: base_url+'index.php/admin_ret_other_inventory/item_size/get_ActivePackagingItemSize',

    dataType:'json',

    success:function(data){

    var id =  $("#id_inv_size").val();

    $.each(data, function (key, item) {      

        $("#select_size").append(

        $("<option></option>")

        .attr("value",item.id_inv_size)    

        .text(item.size_name)  

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

}





//Purchase Entry

function get_other_inventory_item()

{

    $.ajax({

    type: 'GET',

    url: base_url+'index.php/admin_ret_other_inventory/get_other_inventory_item',

    dataType:'json',

    success:function(data){

    var id =  $("#select_item").val();

    $.each(data, function (key, item) {      

        $("#select_item,#item_filter").append(

        $("<option></option>")

        .attr("value",item.id_other_item)    

        .text(item.name)  

        );

    });

    

    $("#select_item,#item_filter").select2(

	{

		placeholder:"Select Item",

		allowClear: true    

	});

		

    $("#select_item").select2("val",(id!='' && id>0?id:''));

    

    if($('#item_filter').length)

    {

        $("#item_filter").select2("val","");

    }

    

    }

    });

}





function get_supplier()

{

    $.ajax({

    type: 'GET',

    url: base_url+'index.php/admin_ret_other_inventory/get_supplier',

    dataType:'json',

    success:function(data){

    var id =  $("#select_karigar").val();

    $.each(data, function (key, item) {      

        $("#select_karigar").append(

        $("<option></option>")

        .attr("value",item.id_karigar)    

        .text(item.karigar_name)  

        );

    });

    

    $("#select_karigar").select2(

	{

		placeholder:"Select Supplier",

		allowClear: true    

	});

		

        $("#select_karigar").select2("val",(id!='' && id>0?id:''));

    }

    });

}



$('#buy_quantity,#buy_rate').on('keyup',function(){

    calculate_item_cost();

});



function calculate_item_cost()

{

    var buy_quantity    = (isNaN($('#buy_quantity').val()) || $('#buy_quantity').val()=='' ?0 :$('#buy_quantity').val());

    var buy_rate        = (isNaN($('#buy_rate').val()) || $('#buy_rate').val()=='' ?0 :$('#buy_rate').val());

    var buy_amount      = parseFloat(parseFloat(buy_quantity)*parseFloat(buy_rate)).toFixed(2);

    $('#buy_amount').val(buy_amount);

}





$('#add_item_info').on('click',function(){

    

    var allow_submit=true;

    if($('#select_karigar').val()=='' || $('#select_karigar').val()==null)

    {

        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>Please Select Supplier.."});

        allow_submit=false;

    }

    else if($('#select_item').val()=='' || $('#select_item').val()==null)

    {

        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>Please Select Item.."});

        allow_submit=false;

    }

    else if($('#buy_quantity').val()=='' || $('#buy_quantity').val()==0)

    {

        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>Please Enter The Pieces.."});

        allow_submit=false;

    }

    else if($('#buy_rate').val()=='' || $('#buy_rate').val()==0)

    {

        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>Please Enter The Buying Rate.."});

        allow_submit=false;

    }

    

    if($('#pur_details > tbody').length>0)

    {

        $('#pur_details > tbody tr').each(function(idx, row){

			curRow = $(this);

			if(curRow.find('.item_id').val()==$('#select_item').val())

			{

			    allow_submit=false;

			    $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>Item Already Added.."});

			    return true;

			}

        });

    }

    

    if(allow_submit)

    {

        var trHtml='';

            trHtml+='<tr>'

                    +'<td><input type="hidden" class="item_id" name="order_items[itemid][]" value="'+$('#select_item').val()+'">'+$('#select_item option:selected').text()+'</td>'

                    +'<td><input type="hidden" class="quantity" name="order_items[quantity][]" value="'+$('#buy_quantity').val()+'">'+$('#buy_quantity').val()+'</td>'

                    +'<td><input type="hidden" class="rate" name="order_items[rate][]" value="'+$('#buy_rate').val()+'">'+$('#buy_rate').val()+'</td>'

                    +'<td><input type="hidden" class="amount" name="order_items[amount][]" value="'+$('#buy_amount').val()+'">'+$('#buy_amount').val()+'</td>'

                    +'<td><a href="#" onClick="remove_row($(this).closest(\'tr\'));" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'

                    +'</tr>';

            if($('#pur_details > tbody  > tr').length>0)

        	{

        	    $('#pur_details > tbody > tr:first').before(trHtml);

        	}else{

        	    $('#pur_details tbody').append(trHtml);

        	}

        	reset_order_items();

    }

});



function remove_row(curRow)

{

	curRow.remove();

}





function reset_order_items()

{

    $('#select_item').select2("val","");

    $('#buy_quantity').val("");

    $('#buy_rate').val("");

    $('#buy_amount').val("");

}



$('#inventory_submit').on('click',function(){

    $("div.overlay").css("display", "block"); 

    if($('#pur_details > tbody  > tr').length>0)

    {

        var form_data=$('#inventory_entry').serialize();

    	$('#inventory_submit').prop('disabled',true);

    	my_Date = new Date();

    	var url=base_url+ "index.php/admin_ret_other_inventory/purchase_entry/save?nocache=" + my_Date.getUTCSeconds();

        $.ajax({ 

            url:url,

            data: form_data,

            type:"POST",

            dataType:"JSON",

            success:function(data){

    			if(data.status)

    			{

    			    $.toaster({ priority : 'success', title : 'Warning!', message : ''+"</br>"+data.message});

    			    $("div.overlay").css("display", "none"); 

    				//window.location.reload();

    				window.location.href=base_url+'index.php/admin_ret_other_inventory/purchase_entry/list';

    			}

    			else

    			{

    			    $('#inventory_submit').prop('disabled',false);

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

    else

    {

        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>No Record Found"});

    }

});



$('#purchase_item_search').on('click',function(){

    get_other_inventory_purchase_items();

});



function get_other_inventory_purchase_items()

{

    $("div.overlay").css("display", "block"); 

    my_Date = new Date();

    $.ajax(

    {

    url:base_url+ "index.php/admin_ret_other_inventory/purchase_entry/ajax?nocache=" + my_Date.getUTCSeconds(),

    dataType:"JSON",

    type:"POST",

    data:{"from_date":$('#from_date').html(),"to_date":$('#to_date').html()},

    success:function(data){

        //console.log(data);

        var item 	= data.list;

        var access		= data.access;

        console.log(item);	

        if(access.add == '0')

        { 

        $('#add_pur_details').attr('disabled','disabled');

        }

        var oTable = $('#other_item_pur').DataTable();

        oTable.clear().draw();

        if (item!= null && item.length > 0)

        {  	

            oTable = $('#other_item_pur').dataTable({

            "bDestroy": true,

            "bInfo": true,

            "bFilter": true,

            "bSort": true,

            "order": [[ 0, "desc" ]],

            "aaData": item,

            "aoColumns": [	

            { "mDataProp": "otr_inven_pur_id" },

            {"mDataProp": "supplier_name" },

            {"mDataProp": "entry_date" },

            {"mDataProp": "pur_order_ref_no" },

            {"mDataProp": "supplier_order_ref_no" },

            {"mDataProp": "supplier_bill_date" },

            {"mDataProp": "tot_pcs" },

            {"mDataProp": "tot_amount" },

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



//Purchase Entry







//Stock Details



$('#stock_details_search').on('click',function(){

    get_other_inventory_stock_details();

});



function get_other_inventory_stock_details()

{

    $("div.overlay").css("display", "block"); 

    my_Date = new Date();

    $.ajax(

    {

    url:base_url+ "index.php/admin_ret_other_inventory/stock_details/ajax?nocache=" + my_Date.getUTCSeconds(),

    dataType:"JSON",

    type:"POST",

    data:{"from_date":$('#from_date').html(),"to_date":$('#to_date').html(),'id_branch':($('#branch_filter').val()!='' && $('#branch_filter').val()!=undefined ? $('#branch_filter').val(): $("#branch_select").val()),"id_other_item":""},

    success:function(data){



        var oTable = $('#stock_details').DataTable();

        oTable.clear().draw();

        if (data!= null && data.length > 0)

        {  	

            oTable = $('#stock_details').dataTable({

            "bDestroy": true,

            "bInfo": true,

            "bFilter": true,

            "bSort": true,

            "order": [[ 0, "desc" ]],

            "aaData": data,

            "aoColumns": [	

            { "mDataProp": "item_name" },

            {"mDataProp": "op_blc_pcs" },

            {"mDataProp": "op_blc_amt" },

            {"mDataProp": "inw_pcs" },

            {"mDataProp": "inw_amount" },

            {"mDataProp": "out_pcs" },

            {"mDataProp": "out_amount" },

            {"mDataProp": "closing_pcs" },

            {"mDataProp": "closing_amt" },

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

						

						$(api.column(0).footer() ).html('Total');	



						op_blc_pcs = api

						.column(1)

						.data()

						.reduce( function (a, b) {

							return intVal(a) + intVal(b);

						}, 0 );

						

						$(api.column(1).footer()).html(parseFloat(op_blc_pcs).toFixed(0));

						

						op_blc_amt = api

						.column(2)

						.data()

						.reduce( function (a, b) {

							return intVal(a) + intVal(b);

						}, 0 );

						

						$(api.column(2).footer()).html(parseFloat(op_blc_amt).toFixed(2));

						

						inw_pcs = api

						.column(3)

						.data()

						.reduce( function (a, b) {

							return intVal(a) + intVal(b);

						}, 0 );

						

						$(api.column(3).footer()).html(parseFloat(inw_pcs).toFixed(0));

						

						

						inw_amt = api

						.column(4)

						.data()

						.reduce( function (a, b) {

							return intVal(a) + intVal(b);

						}, 0 );

						

						$(api.column(4).footer()).html(parseFloat(inw_amt).toFixed(2));

						

						

						out_pcs = api

						.column(5)

						.data()

						.reduce( function (a, b) {

							return intVal(a) + intVal(b);

						}, 0 );

						

						$(api.column(5).footer()).html(parseFloat(out_pcs).toFixed(0));

						

						out_amt = api

						.column(6)

						.data()

						.reduce( function (a, b) {

							return intVal(a) + intVal(b);

						}, 0 );

						

						$(api.column(6).footer()).html(parseFloat(out_amt).toFixed(2));

						

						

						cls_pcs = api

						.column(7)

						.data()

						.reduce( function (a, b) {

							return intVal(a) + intVal(b);

						}, 0 );

						

						$(api.column(7).footer()).html(parseFloat(cls_pcs).toFixed(0));

						

						

						cls_amt = api

						.column(8)

						.data()

						.reduce( function (a, b) {

							return intVal(a) + intVal(b);

						}, 0 );

						

						$(api.column(8).footer()).html(parseFloat(cls_amt).toFixed(2));

						

						

				} 

				}else{

					 var api = this.api(), data; 

					 $(api.column(5).footer()).html('');

				}

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





//Stock Details





//Item Issue

$('#branch_select').on('change',function(){

   if(this.value!='' && this.value!=null && this.value!=0)

   {

       $('#id_branch').val(this.value);

       if(ctrl_page[1]!='available_stock')

       {

           get_invnetory_item();

           get_bill_details();

       }

   }

});



function get_invnetory_item()

{

    $('#select_item option').remove();

    $.ajax({

    type: 'POST',

    url: base_url+'index.php/admin_ret_other_inventory/get_invnetory_item',

    dataType:'json',

    data:{"id_branch":$('#id_branch').val()},

    success:function(data){

    other_inventory_item=data;

    var id =  $("#select_item").val();

    $.each(data, function (key, item) {      

        $("#select_item").append(

        $("<option></option>")

        .attr("value",item.id_other_item)    

        .text(item.item_name)  

        );

    });

    

    $("#select_item").select2(

	{

		placeholder:"Select Item",

		allowClear: true    

	});

		

    $("#select_item").select2("val",(id!='' && id>0?id:''));

    }

    });

}





function get_invnetory_item_list()

{

    $('#select_item option').remove();

    $.ajax({

    type: 'POST',

    url: base_url+'index.php/admin_ret_other_inventory/other_inventory',

    dataType:'json',

    success:function(data){

    other_inventory_item=data.list;

    var id =  $("#select_item").val();

    $.each(other_inventory_item, function (key, item) {      

        $("#select_item").append(

        $("<option></option>")

        .attr("value",item.id_other_item)    

        .text(item.item_name)  

        );

    });

    

    $("#select_item").select2(

	{

		placeholder:"Select Item",

		allowClear: true    

	});

		

    $("#select_item").select2("val",(id!='' && id>0?id:''));

    }

    });

}





function get_bill_details()

{

    $('#select_bill_no option').remove();

    $.ajax({

    type: 'POST',

    url: base_url+'index.php/admin_ret_other_inventory/get_bill_details',

    dataType:'json',

    data:{"id_branch":$('#id_branch').val()},

    success:function(data){

    var id =  $("#select_bill_no").val();

    $.each(data, function (key, item) {      

        $("#select_bill_no").append(

        $("<option></option>")

        .attr("value",item.bill_id)    

        .text(item.cus_bill_no)  

        );

    });

    

    $("#select_bill_no").select2(

	{

		placeholder:"Select Bill No",

		allowClear: true    

	});

		

    $("#select_bill_no").select2("val",(id!='' && id>0?id:''));

    }

    });

}



function get_customer()

{

    $('#select_item option').remove();

    $.ajax({

    type: 'POST',

    url: base_url+'index.php/admin_ret_other_inventory/get_customer',

    dataType:'json',

    data:{"id_branch":$('#branch_select').val()},

    success:function(data){

    var id =  $("#id_uom").val();

    $.each(data, function (key, item) {      

        $("#select_customer").append(

        $("<option></option>")

        .attr("value",item.id_customer)    

        .text(item.cus_name)  

        );

    });

    

    $("#select_customer").select2(

	{

		placeholder:"Select Customer",

		allowClear: true    

	});

		

    $("#select_customer").select2("val",(id!='' && id>0?id:''));

    }

    });

}



$('#item_issue').on('click',function(){

   if($('#id_branch').val()=='' || $('#id_branch').val()==null)

   {

       $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>Please Select Branch.."});

   }

   else if($('#select_item').val()=='' || $('#select_item').val()==null)

   {

       $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>Please Select Branch.."});

   }

   else if($('#remarks').val()=='')

   {

       $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>Please Enter The Remarks.."});

   }

   else

   {

       inventory_item_issue();

   }

});



function inventory_item_issue()

{

    $("div.overlay").css("display", "block"); 

    var form_data=$('#inventory_issue').serialize();

	$('#item_issue').prop('disabled',true);

	my_Date = new Date();

	var url=base_url+ "index.php/admin_ret_other_inventory/issue_item/save?nocache=" + my_Date.getUTCSeconds();

    $.ajax({ 

        url:url,

        data: form_data,

        type:"POST",

        dataType:"JSON",

        success:function(data){

			if(data.status)

			{

			    $.toaster({ priority : 'success', title : 'Warning!', message : ''+"</br>"+data.message});

			    $("div.overlay").css("display", "none"); 

				$('#item_issue').prop('disabled',false);

				window.location.reload();

			}

			else

			{

			    $('#item_issue').prop('disabled',false);

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



$('#issue_total_pcs').on('keyup',function(){

    $.each(other_inventory_item,function(key,items){

        if(items.id_other_item==$('#select_item').val())

        {

            var available_pcs = items.tot_pcs;

            var item_total_pcs=$('#issue_total_pcs').val();

            if(parseFloat(available_pcs)<item_total_pcs)

            {

                $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>Available Pieces is "+items.tot_pcs});

                $('#issue_total_pcs').val("");

            }

        }

    });

});



$('#search_issue_item').on('click',function(){

    get_other_inventory_item_issue_details();

});





function get_other_inventory_item_issue_details()

{

    $("div.overlay").css("display", "block"); 

    my_Date = new Date();

    $.ajax(

    {

    url:base_url+ "index.php/admin_ret_other_inventory/issue_item/ajax?nocache=" + my_Date.getUTCSeconds(),

    dataType:"JSON",

    type:"POST",

    data:{"from_date":$('#from_date').html(),"to_date":$('#to_date').html(),'id_branch':($('#branch_filter').val()!='' && $('#branch_filter').val()!=undefined ? $('#branch_filter').val(): $(".branch_filter").val()),"id_other_item":""},

    success:function(data){



        var oTable = $('#issue_list').DataTable();

        oTable.clear().draw();

        if (data!= null && data.length > 0)

        {  	

            oTable = $('#issue_list').dataTable({

            "bDestroy": true,

            "bInfo": true,

            "bFilter": true,

            "bSort": true,

            "order": [[ 0, "desc" ]],

            "aaData": data,

            "aoColumns": [	

            { "mDataProp": "id_inventory_issue" },

            {"mDataProp": "branch_name" },

            {"mDataProp": "item_name" },

            {"mDataProp": "issue_date" },

            { "mDataProp": function ( row, type, val, meta ){

			    var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+row.bill_id;

				return '<a href='+url+' target="_blank">'+row.bill_no+'</a>';

			},

			},

            {"mDataProp": "cus_name" },

            {"mDataProp": "no_of_pieces" },

            {"mDataProp": "approx_amt" },

            {"mDataProp": "given_by" },

            {"mDataProp": "remarks" },

            ],

            "footerCallback": function( row, data, start, end, display )

			{ 

				if(data.length>0){

					var api = this.api(), data;



					for( var i=0; i<=data.length-1;i++)

					{



						var intVal = function ( i ) {

							return typeof i === 'string' ?

							i.replace(/[\$,]/g, '')*1 :

							typeof i === 'number' ?

							i : 0;

						};	

						

						$(api.column(0).footer() ).html('Total');	



						total_pcs = api

						.column(6)

						.data()

						.reduce( function (a, b) {

							return intVal(a) + intVal(b);

						}, 0 );

						

						$(api.column(6).footer()).html(parseFloat(total_pcs).toFixed(0));

						

						total_amt = api

						.column(7)

						.data()

						.reduce( function (a, b) {

							return intVal(a) + intVal(b);

						}, 0 );

						

						$(api.column(7).footer()).html(parseFloat(total_amt).toFixed(2));

				    } 

    				}else{

    					 var api = this.api(), data; 

    					 $(api.column(6).footer()).html('');

    					 $(api.column(7).footer()).html('');

    					 $(api.column(8).footer()).html('');

    				}

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



//Item Issue







//size master

$('#add_new_item_size').on('click',function(){

    if($('#size_name').val()=='' || $('#size_name').val()==null)

    {

        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>Please Enter The Size.."});

        allow_submit=false;

    }

    else

    {

        $('#add_new_item_size').prop('disabled',true);

        my_Date = new Date();

        $.ajax(

        {

        url:base_url+ "index.php/admin_ret_other_inventory/item_size/save?nocache=" + my_Date.getUTCSeconds(),

        dataType:"JSON",

        type:"POST",

        data:{"size_name":$('#size_name').val()},

        success:function(data){

                

                if(data.status)

                {

                     $('#size_name').val('');

                     $.toaster({ priority : 'success', title : 'Warning!', message : ''+"</br>"+data.message});

                     $('#add_new_item_size').prop('disabled',false);

                     set_packing_item_size_list();

                }

                else

                {

                     $('#size_name').val('');

                     $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+data.message});

                     $('#add_new_item_size').prop('disabled',false);

                }

        },

        error:function(error)  

        {

            $("div.overlay").css("display", "none"); 

        }	 

        });

        }

});



$('#add_item_size').on('click',function(){

    if($('#size_name').val()=='' || $('#size_name').val()==null)

    {

        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>Please Enter The Size.."});

        allow_submit=false;

    }

    else

    {

        $('#add_item_size').prop('disabled',true);

        my_Date = new Date();

        $.ajax(

        {

        url:base_url+ "index.php/admin_ret_other_inventory/item_size/save?nocache=" + my_Date.getUTCSeconds(),

        dataType:"JSON",

        type:"POST",

        data:{"size_name":$('#size_name').val()},

        success:function(data){

                

                if(data.status)

                {

                     $('#confirm-add').modal('toggle');

                     $('#size_name').val('');

                     $.toaster({ priority : 'success', title : 'Warning!', message : ''+"</br>"+data.message});

                     $('#add_item_size').prop('disabled',false);

                     set_packing_item_size_list();

                }

                else

                {

                     $('#size_name').val('');

                     $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+data.message});

                     $('#add_item_size').prop('disabled',false);

                }

        },

        error:function(error)  

        {

            $("div.overlay").css("display", "none"); 

        }	 

        });

        }

});



function set_packing_item_size_list()

{

    $("div.overlay").css("display", "block"); 

    my_Date = new Date();

    $.ajax(

    {

    url:base_url+ "index.php/admin_ret_other_inventory/item_size/ajax?nocache=" + my_Date.getUTCSeconds(),

    dataType:"JSON",

    type:"GET",

    success:function(data){

        var list=data.list

        var access=data.access

        var oTable = $('#size_list').DataTable();

        oTable.clear().draw();

        if (list!= null && list.length > 0)

        {  	

            oTable = $('#size_list').dataTable({

            "bDestroy": true,

            "bInfo": true,

            "bFilter": true,

            "bSort": true,

            "order": [[ 0, "desc" ]],

            "aaData": list,

            "aoColumns": [	

                { "mDataProp": "id_inv_size" },

                {"mDataProp": "size_name" },

                { "mDataProp": function ( row, type, val, meta ){

                    active_url =base_url+"index.php/admin_ret_other_inventory/packaging_item_size_status/"+(row.status==1?0:1)+"/"+row.id_inv_size; 

                    return "<a href='"+active_url+"'><i class='fa "+(row.status==1?'fa-check':'fa-remove')+"' style='color:"+(row.status==1?'green':'red')+"'></i></a>"

                    }

                },

                { "mDataProp": function ( row, type, val, meta ) {

                    id= row.id_inv_size

                    edit_target=(access.edit=='0'?"":"#confirm-edit");

                    delete_url=(access.delete=='1' ? base_url+'index.php/admin_ret_other_inventory/item_size/delete/'+id : '#' );

                    delete_confirm= (access.delete=='1' ?'#confirm-delete':'');

                    action_content='<a href="#" class="btn btn-primary btn-edit" id="edit" role="button" data-toggle="modal" data-id='+id+'  data-target='+edit_target+'><i class="fa fa-edit" ></i></a> <a href="#" class="btn btn-danger btn-del" data-href='+delete_url+' data-toggle="modal" data-target="#confirm-delete" ><i class="fa fa-trash"></i></a>';

                    return action_content;

                }

                }

            ],

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





$(document).on('click', "#size_list a.btn-edit", function(e) {

    $("#id_inv_size").val('');

    e.preventDefault();

    id=$(this).data('id');

    get_packaging_size(id);

    $("#edit-id").val(id);  

});	





function get_packaging_size(id)

{

   my_Date = new Date();

	$.ajax({

		type:"GET",

		url: base_url+"index.php/admin_ret_other_inventory/item_size/edit/"+id+"?nocache=" + my_Date.getUTCSeconds(),

		cache:false,		

		dataType:"JSON",

		success:function(data){

		    $('#ed_size_name').val(data.size_name);

		    $('#id_inv_size').val(data.id_inv_size);

		}

	});

}





$('#update_size').on('click',function(){

    if($('#ed_size_name').val()=='' || $('#ed_size_name').val()==null)

    {

        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>Please Enter The Size.."});

        allow_submit=false;

    }

    else

    {

        $('#update_size').prop('disabled',true);

        my_Date = new Date();

        $.ajax(

        {

        url:base_url+ "index.php/admin_ret_other_inventory/item_size/update?nocache=" + my_Date.getUTCSeconds(),

        dataType:"JSON",

        type:"POST",

        data:{"size_name":$('#ed_size_name').val(),'id_inv_size':$("#id_inv_size").val()},

        success:function(data){

                

                if(data.status)

                {

                     $('#confirm-edit').modal('toggle');

                     $('#ed_size_name').val('');

                     $.toaster({ priority : 'success', title : 'Warning!', message : ''+"</br>"+data.message});

                     $('#update_size').prop('disabled',false);

                     set_packing_item_size_list();

                }

                else

                {

                     $('#ed_size_name').val('');

                     $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+data.message});

                     $('#update_size').prop('disabled',false);

                }

        },

        error:function(error)  

        {

            $("div.overlay").css("display", "none"); 

        }	 

        });

        }

});





//size master







//Available Stock 

$('#avail_stock_details_search').on('click',function(){

    available_stock_details();

});



function available_stock_details()

{

    $("div.overlay").css("display", "block"); 

    my_Date = new Date();

    $.ajax(

    {

    url:base_url+ "index.php/admin_ret_other_inventory/available_stock/ajax?nocache=" + my_Date.getUTCSeconds(),

    dataType:"JSON",

    type:"POST",

    data:{'id_branch':($('#branch_filter').val()!='' && $('#branch_filter').val()!=undefined ? $('#branch_filter').val(): $("#branch_select").val()),"id_size":$('#select_size').val(),'id_other_item':$('#select_item').val()},

    success:function(data){



        var oTable = $('#stock_details').DataTable();

        oTable.clear().draw();

        if (data!= null && data.length > 0)

        {  	

            oTable = $('#stock_details').dataTable({

            "bDestroy": true,

            "bInfo": true,

            "bFilter": true,

            "bSort": true,

            "order": [[ 0, "desc" ]],

            "aaData": data,

            "aoColumns": [	

            { "mDataProp": "branch_name" },

            {"mDataProp": "item_name" },

            {"mDataProp": "size_name" },

            {"mDataProp": "tot_pcs" },

            {"mDataProp": "tot_amount" },

            ],

            "footerCallback": function( row, data, start, end, display )

			{ 

				if(data.length>0){

					var api = this.api(), data;



					for( var i=0; i<=data.length-1;i++)

					{



						var intVal = function ( i ) {

							return typeof i === 'string' ?

							i.replace(/[\$,]/g, '')*1 :

							typeof i === 'number' ?

							i : 0;

						};	

						

						$(api.column(0).footer() ).html('Total');	



						total_pcs = api

						.column(3)

						.data()

						.reduce( function (a, b) {

							return intVal(a) + intVal(b);

						}, 0 );

						

						$(api.column(3).footer()).html(parseFloat(total_pcs).toFixed(0));

						

						total_amt = api

						.column(4)

						.data()

						.reduce( function (a, b) {

							return intVal(a) + intVal(b);

						}, 0 );

						

						$(api.column(4).footer()).html(parseFloat(total_amt).toFixed(2));

				    } 

    				}else{

    					 var api = this.api(), data; 

    					 $(api.column(3).footer()).html('');

    					 $(api.column(4).footer()).html('');

    				}

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



//Available Stock 





//Product Mapping

function get_ActiveProduct()

{

    my_Date = new Date();

    $.ajax({

    type: 'GET',

    url: base_url+"index.php/admin_ret_catalog/ret_product/active_list/?nocache=" + my_Date.getUTCSeconds(),

    dataType:'json',

    success:function(data){

    var id =  $("#select_product").val();

    

    $("#select_product").append($("<option></option>").attr("value",0).text("All"));

        

    $.each(data, function (key, item) {      

        $("#select_product,#prod_filter").append(

        $("<option></option>")

        .attr("value",item.pro_id)    

        .text(item.product_name)  

        );

    });

    

    $("#select_product,#prod_filter").select2(

	{

		placeholder:"Select Product",

		allowClear: false    

	});

		

        $("#select_product").select2("val",(id!='' && id>0?id:''));

        

        if($('#prod_filter').length)

        {

            $("#prod_filter").select2("val","");

        }

    }

    });

}





$('#update_product_mapping').on('click',function(){  

	if($('#select_product').val()=='' || $('#select_product').val()==null)

	{

		$.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select Product..'});

		$("#update_product_mapping").prop('disabled',false);

	}else if($('#select_item').val()=='' || $('#select_item').val()==null)

	{

		$.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select Item..'});

		$("#update_product_mapping").prop('disabled',false);

	}

	else

	{

		update_product_mapping();

	}

});

function update_product_mapping()

{

	my_Date = new Date();

	$.ajax({

			 url:base_url+ "index.php/admin_ret_other_inventory/update_product_mapping?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),

			 data:  {'id_product':$('#select_product').val(),'id_other_item':$('#select_item').val()},

			 type:"POST",

			 dataType: "json", 

			 async:false,

			 	  success:function(data){

			 	  	if(data.status)

			 	  	{

		 					$.toaster({ priority : 'success', title : 'Warning!', message : ''+"</br>"+data.msg});

			 	    		get_product_mapping_details();

			 	  	}

			 	  	else

			 	  	{

			 	  		$.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+data.msg});

			 	  		

			 	  	}

			 	  	$('#select_item').select2("val","");

			 	  	$('#select_product').select2("val","");

			 	  	$('#update_product_mapping').prop('disabled',false);

				  },

				  error:function(error)  

				  {

					 $("div.overlay").css("display", "none"); 

				  }	 

		  });

}



$('#search_design_maping').on('click',function(){

    get_product_mapping_details();

});



function get_product_mapping_details()

{

	$("div.overlay").css("display", "block"); 

	my_Date = new Date();

	$.ajax({

		url: base_url+"index.php/admin_ret_other_inventory/product_mapping?nocache=" + my_Date.getUTCSeconds(),

		type:"POST",

		data:{'id_product':$('#prod_filter').val(),'id_other_item':$('#item_filter').val()},

		dataType: 'json',

		cache:false,

		success:function(data){

		   		var list 	= data.list;

				var access	    = data.access;	

				

    			 var oTable = $('#mapping_list').DataTable();

    			 oTable.clear().draw();

    			 if (list!= null && list.length > 0)

    			 {  	

    				oTable = $('#mapping_list').dataTable({

    						"bDestroy": true,

    						"bInfo": true,

    						"bFilter": true,

    						"bSort": true,

    						"order": [[ 0, "desc" ]],				

    						"aaData"  : list,

    						"aoColumns": [

    					    { "mDataProp": function ( row, type, val, meta ){ 

    	                	chekbox='<input type="checkbox" class="inv_des_id" name="inv_des_id[]" value="'+row.inv_des_id+'"/>' 

    	                	return chekbox+" "+row.inv_des_id;

    		                }},	

    						{ "mDataProp": "product_name" },

    						{ "mDataProp": "item_name" },

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

}



 $("#delete_product_mapping").on('click',function(){

        if($("input[name='inv_des_id[]']:checked").val())

        {

                var selected = [];

                var approve=false;

                $("#mapping_list tbody tr").each(function(index, value)

                {

                if($(value).find("input[name='inv_des_id[]']:checked").is(":checked"))

                {

                transData = { 

                    'inv_des_id'   : $(value).find(".inv_des_id").val(),

                }

                selected.push(transData);	

                }

                })

                req_data = selected;

                delete_product_mapping(req_data);

        }

        else

        {

            $.toaster({ priority : 'success', title : 'Warning!', message : ''+"</br>Please Select Item"});

        }

    });

    

    function delete_product_mapping(data="")

    {

        my_Date = new Date();

        $("div.overlay").css("display", "block"); 

        $.ajax({

        url:base_url+ "index.php/admin_ret_other_inventory/delete_product_mapping?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),

        data:  {'req_data':data},

        type:"POST",

        async:false,

        dataType:'json',

        success:function(data){

            if(data.status)

            {

                $.toaster({ priority : 'success', title : 'Warning!', message : ''+"</br>"+data.msg});

            }

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



//Product Mapping



$('#tab_tot_summary').on('click',function(){

    if(ctrl_page[2]!='edit')

    {

         set_item_reorder();

    }

});



function get_branch_details()

{

    $.ajax({		

		type: 'GET',		

		url: base_url+'index.php/branch/branchname_list',		

		dataType:'json',		

		success:function(data){	

			branch_details=data;

		}	

	}); 

}



function set_item_reorder()

{

    var row='';

   $('#total_items tbody ').empty();

   $.each(branch_details.branch,function(key,item){

    	row+='<tr>'

    	+'<td>'+item.name+'<input type="hidden" class="form-control id_branch"  name="pieces['+key+'][id_branch]" value='+item.id_branch+'></td>'

    	+'<td><input type="number" class="form-control min_pcs" name="pieces['+key+'][min_pcs]" value="" placeholder="Enter Min Pcs"></td>'

    	+'<td><input type="number" class="form-control max_pcs" name="pieces['+key+'][max_pcs]"  value="" placeholder="Enter Max Pcs"></td>'

    	+'</tr>';

    });

   $('#total_items tbody ').append(row);

}



$('#reorder_details_search').on('click',function(){

    reorder_report();

});



function reorder_report()

{

	$("div.overlay").css("display", "block"); 

	my_Date = new Date();

	$.ajax({

		url: base_url+"index.php/admin_ret_other_inventory/reorder_report?nocache=" + my_Date.getUTCSeconds(),

		type:"POST",

		data:{'id_branch':$('#branch_select').val()},

		dataType: 'json',

		cache:false,

		success:function(data){

		   		var list 	= data.list;

				var access	    = data.access;	

				

    			 var oTable = $('#reorder_details').DataTable();

    			 oTable.clear().draw();

    			 if (list!= null && list.length > 0)

    			 {  	

    				oTable = $('#reorder_details').dataTable({

    						"bDestroy": true,

    						"bInfo": true,

    						"bFilter": true,

    						"bSort": true,

    						"order": [[ 0, "desc" ]],				

    						"aaData"  : list,

    						"aoColumns": [

    						{ "mDataProp": "branch_name" },

    						{ "mDataProp": "item_name" },

    						{ "mDataProp": "min_pcs" },

    						{ "mDataProp": "max_pcs" },

    						{ "mDataProp": function ( row, type, val, meta ){ 

    	                	    if(row.available_pcs>row.max_pcs)

    	                	    {

    	                	         return '<span class="badge bg-green">'+row.available_pcs+'</span>';

    	                	    }

    	                	    else if(row.available_pcs<row.min_pcs)

    	                	    {

    	                	        return '<span class="badge bg-red">'+row.available_pcs+'</span>';

    	                	    }

    	                	    else

    	                	    {

    	                	        return row.available_pcs;

    	                	    }

    		                }},

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
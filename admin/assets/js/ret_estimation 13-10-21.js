var path =  url_params();

var ctrl_page 		= path.route.split('/');

var lot_details 	= [];

var tax_details 	= [];

var purities 		= [];

var cur_search_tags	= [];

var matel_types 	= [];

var stones 			= [];

var other_charges_details = [];

var materials 		= [];

var stone_details 	= [];

var material_details 	= [];

var emp_details 	= [];

var order_adv_details  	= [];

var cur_cat_Row;

$(document).ready(function() {

	var path =  url_params();

	$('#status').bootstrapSwitch();

    prod_info = [];	
	get_all_old_metal_rates();
	get_old_metal_categories();
    $(window).scroll(function() {    // this will work when your window scrolled.

		var height = $(window).scrollTop();  //getting the scrolling height of window

		if(height  > 300) {

			$(".stickyBlk").css({"position": "fixed"});

		} else{

			$(".stickyBlk").css({"position": "static"});

		}

	}); 

	switch(ctrl_page[1])

	{

	 	case 'estimation':

				switch(ctrl_page[2]){				 	

				 	case 'list':				 	

				 			get_estimation_list();

				 			$('#est_list1').text(moment().startOf('month').format('YYYY-MM-DD'));

                            $('#est_list2').text(moment().endOf('month').format('YYYY-MM-DD'));	

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

                            get_estimation_list(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'))			 

                            $('#est_list1').text(start.format('YYYY-MM-DD'));

                            $('#est_list2').text(end.format('YYYY-MM-DD')); 

                            }

                            );   

                            

				 			$('#branch_select').on('change',function(){

				 			    get_estimation_list();

				 			});

				 		break;

				 	case 'edit':

				 			var id_branch=$('#id_branch').val();

				 			get_employee(id_branch);

				 			calculate_purchase_details();

				 			calculate_sales_details();	

							break;

				 	case 'add':

				 	    

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

				 	    

				 	    get_taxgroup_items();

				 		hide_page_open_details();

				 		var id_branch=$('#id_branch').val();

				 		get_employee(id_branch);	

				 		break;

				}

	 		break; 

	}

	if(ctrl_page[2] != "list"){		

		//get_customer_list();

		get_tag_purities();

		//get_tag_matels();

		get_stones();
		
		getOtherChargesDetails();

		get_materials();

	}

	$('input[type=radio][name="estimation[esti_for]"]').change(function(){ 

		if(this.value == 1){ 

			$("#cus_req").css('display','inline');

			$("#select_catalog_details").attr('disabled',false);

			$("#select_custom_details").attr('disabled',false);

			$("#select_oldmatel_details").attr('disabled',false);

			$('#add_new_customer').prop('disabled',false);

		    $('#edit_customer').prop('disabled',false);

		}

		else if(this.value == 2){ 

			$("#cus_req").css('display','none');

			$("#select_catalog_details").attr('disabled',true);

			$("#select_custom_details").attr('disabled',true);

			$("#select_oldmatel_details").attr('disabled',true);

			$('#add_new_customer').prop('disabled',false);

		    $('#edit_customer').prop('disabled',false);

		}else if(this.value == 3)

		{

		    $('#add_new_customer').prop('disabled',true);

		    $('#edit_customer').prop('disabled',true);

		}

	});

	$('#add_new_customer').on('click',function(e){

		get_village_list();

		get_country();

	});

	

	$('#select_order_details').change(function() {

        if(this.checked) {

            $(".order_details").show();

			if($('#estimation_order_details tbody tr').length == 0){

				create_new_empty_est_order_row();//Create new empty est empty row

			}

        }else{

			$(".order_details").hide();

		}

    });

    

    $(document).on('keyup',	".orderno", function(e){ 



		var orderno = this.value;					//Coaded by karthik



		var row = $(this).closest('tr'); 



		if($("#branch_settings").val() == 1){



			if($("#id_branch").val() != ""){



				getSearchOrders(orderno, row);



			}else{



				alert("Select Branch");



			}



		}else{



			getSearchOrders(orderno, row);



		} 



	});

	

	function getSearchOrders(searchTxt, curRow){

	my_Date = new Date();

	$.ajax({

        url: base_url+'index.php/admin_ret_estimation/getOrderBySearch/?nocache=' + my_Date.getUTCSeconds(),             

        dataType: "json", 

        method: "POST", 

        data: {'searchTxt': searchTxt, 'id_branch': $("#id_branch").val()}, 

        success: function (data) {

			cur_search_tags = data;

			$.each(data, function(key, item){

				$('#estimation_order_details > tbody tr').each(function(idx, row){

					if(item != undefined){

						if($(this).find('.orderno').val() == item.value){

							data.splice(key, 1);

						}

					}

				});

			});

			$( ".orderno" ).autocomplete(

			{

				source: data,

				select: function(e, i)

				{ 

					e.preventDefault(); 

					var curRowItem = i.item; 

					if(curRowItem.sales_mode == 2){ // 1 - Fixed Rate, 2 - Flexible

						get_metal_rates_by_branch(i.item.lot_received_at);

					}

					

					curRow.find('.orderno').val(i.item.label);

				    curRow.find('.order_id').val(i.item.value); 

					curRow.find('.prodct_name').html(curRowItem.product_name);

					curRow.find('.id_product').val(curRowItem.id_product);

					curRow.find('.design_no').val(curRowItem.design_no);

					curRow.find('.design_name').html(curRowItem.design_name);

					curRow.find('.purity').html(curRowItem.purname);

					curRow.find('.id_purity').val(curRowItem.id_purity);

					curRow.find('.sizes').html(curRowItem.size);

					curRow.find('.size').val(curRowItem.size);

					curRow.find('.pieces').html(curRowItem.totalitems);

					curRow.find('.totalitems').val(curRowItem.totalitems);

					curRow.find('.weight').val(curRowItem.weight);

					curRow.find('.net_weight').val(curRowItem.weight);

					curRow.find('.wast_percent').html(curRowItem.wast_percent);

					curRow.find(".wastage_max_per").val(curRowItem.wast_percent);

					curRow.find('.mc').html(curRowItem.mc);

					curRow.find('.mc_value').val(curRowItem.mc);

					curRow.find('.cost').html(curRowItem.rate);

					curRow.find(".tax_percentage").val(curRowItem.tax_percentage);

					curRow.find(".tgi_calculation").val(curRowItem.tgi_calculation);

					curRow.find(".metal_type").val(curRowItem.metal_type);

					curRow.find(".tax_group").val(curRowItem.tax_group_id);

					curRow.find(".stn_amt").val(curRowItem.stn_amt);

					calculate_order_SaleValue();

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

						curRow.find('.order_no').val("");

						curRow.find('.order_id').val("");

					}

		        },

				 minLength: 1,

			});

        }

     });

}

	

	

    $('#select_tag_details').change(function() {

        if(this.checked) 

        {

            $(".tag_details").show();

            if($('#estimation_tag_details tbody tr').length == 0){

            create_new_empty_est_tag_row();//Create new empty est empty row

            }

        }

        else

        {

			$(".tag_details").hide();

		}

    });

    

	$('#create_tag_details').on('click', function(){

		if(validateTagDetailRow()){

		        create_new_empty_est_tag_row();

		}else{

			alert("Please fill required fields");

		}

	});

	

	$('#create_order_details').on('click', function(){

		if(validateOrderDetailRow()){

		        create_new_empty_est_order_row();

		}else{

			alert("Please fill required fields");

		}

	});

	

	/*$('#est_tag_scan').on('focus',function(){

	      get_tag_data();

	});*/

	

	$('#tag_search').on('click',function(){

	     get_tag_data();

	});

	

	function get_tag_data()

	{

	    $(".overlay").css('display','block');

	 	var tagData = $('#est_tag_scan').val();

		var type  = "";

		var searchTxt  = "";

		var tag_search=false;

		console.log(tagData); 

		if(tagData != ""){

			var istagId = (tagData.search("/") > 0 ? true : false);

			var isTagCode = (tagData.search("-") > 0 ? true : false);

			if(istagId){

				var tId   = tagData.split("/"); 

				searchTxt = (tId.length >= 2 ? tId[0] : ""); 

				type  = "tag_id";

			}

			else if(isTagCode){  

				searchTxt = $('#est_tag_scan').val(); 

				type  = "tag_code";

			} 

			if(searchTxt != ""){

				if($("#branch_settings").val() == 1){

					if($("#id_branch").val() != ""){

					    tag_search=true;

					}else{

						tag_search=false;

						$('#est_tag_scan').val("");

					}

				}else{

					tag_search=true;

				}

			}

		} 

	    if(type=="tag_code")

	    {

	        tag_search=false;

	    }

	    

	    if(tag_search)

	    {

	            my_Date = new Date();

                $.ajax({

                type: 'POST',

        	    url: base_url+'index.php/admin_ret_estimation/getTaggingBySearch/?nocache=' + my_Date.getUTCSeconds(),     

                dataType:'json',

                data: {'searchTxt': searchTxt, 'searchField': type, 'id_branch': $("#id_branch").val()}, 

                success:function(data){

                    if(data!=null && data.length>0)

                    {   

                        if($('#estimation_tag_details >tbody > tr').length>0)

                        {

                            $('#estimation_tag_details > tbody tr').each(function(idx, row)

                            {

                                if(searchTxt != '')

                                {

                                    

                                    if($(this).find('td:first .est_tag_id').val() != data[0].tag_id)

                                    {

                                         var row = "";

                                         row += '<tr>'

                                        +'<td><input class="est_tag_name" type="text" name="est_tag[tag_name][]" value='+data[0].label+' placeholder="Enter tag code" required autocomplete="off"/><input class="est_tag_id" type="hidden" name="est_tag[tag_id][]" value='+data[0].tag_id+' placeholder="Enter tag code" required /><input class="id_orderdetails" type="hidden" name="est_tag[id_orderdetails][]" value="'+data[0].id_orderdetails+'" /><input class="orderno" type="hidden" name="est_tag[orderno][]" value="'+data[0].order_no+'"/><input class="rate_field" type="hidden"  value="'+data[0].rate_field+'"/><input class="market_rate_field" type="hidden"  value="'+data[0].market_rate_field+'"/></td>'

                                        +'<td><input type="checkbox" class="partial"><input type="hidden" class="is_partial"  name="est_tag[is_partial][]"></td>'

                                        +'<td><div class="prodct_name">'+data[0].product_name+'</div><input type="hidden" class="pro_id" name="est_tag[pro_id][]" value='+data[0].lot_product+' /><input type="hidden" class="metal_type" value='+data[0].metal_type+'></td>'

                                        +'<td><div class="design_name">'+data[0].design_name+'</div><input type="hidden" class="design_id" name="est_tag[design_id][]" value='+data[0].design_id+' /></td>'

                                       	+'<td><div class="order_no"></td>'

                                        +'<td><div class="purity">'+data[0].purity+'</div><input type="hidden" class="purity" name="est_tag[purity][]" value='+data[0].purity+' /></td>'

                                        +'<td><div class="sizes">'+data[0].size+'</div><input type="hidden" class="size" name="est_tag[size][]"  value="'+data[0].size+'" /></td>'

                                        +'<td><div class="pieces">'+data[0].piece+'</div><input type="hidden" class="piece" name="est_tag[piece][]" value='+data[0].piece+' /></td>'

                                        +'<td><input type="text" class="gwt" name="est_tag[gwt][]" step="any" value='+data[0].gross_wt+' disabled/><input type="hidden" class="cur_gwt" name="est_tag[cur_gwt][]" value='+data[0].gross_wt+' /><input type="hidden" class="act_gwt" value='+data[0].gross_wt+' /></td>'

                                        +'<td><input type="text" class="lwt" name="est_tag[lwt][]" step="any" value='+data[0].less_wt+' disabled/></td>'

                                        +'<td><div class="nwt">'+data[0].net_wt+'</div><input type="hidden" class="tot_nwt" name="est_tag[nwt][]" value='+data[0].net_wt+' /></td>'

                                        +'<td><div class="wastage">'+data[0].retail_max_wastage_percent+'</div><input type="hidden" name="est_tag[wastage][]" class="wastage_max_per" value='+data[0].retail_max_wastage_percent+' /></td>'

                                        +'<td><div class="mc">'+data[0].tag_mc_value+'</div></td>'

                                        +'<td><div class="cost">'+data[0].sales_value+'</div><input class="sales_value" type="hidden" name="est_tag[cost][]" value='+data[0].sales_value+' /><input class="tag_item_rate" type="hidden" name="est_tag[item_rate][]" value='+data[0].item_rate+' /><input class="caltype" type="hidden" name="est_tag[caltype][]" value='+data[0].calculation_based_on+' /><input class="tgi_calculation" type="hidden" name="est_tag[tgi_calculation][]" value='+data[0].tgi_calculation+' /><input type="hidden" class="tax_group_id" value="'+data[0].tax_group_id+'" /><input class="tax_percentage" type="hidden" name="est_tag[tax_percentage][]" value='+data[0].tax_percentage+' /><input class="stone_price" type="hidden" name="est_tag[stone_price][]" value='+data[0].stone_price+' /><input class="certification_price" type="hidden" name="est_tag[certification_price][]" value='+data[0].certification_cost+' /><input class="id_mc_type" type="hidden" name="est_tag[id_mc_type][]" value='+data[0].tag_mc_type+' /><input class="mc_value" type="hidden" name="est_tag[mc][]" value='+data[0].tag_mc_value+' /><input class="act_mc_value" type="hidden" value='+data[0].tag_mc_value+' /><input class="tax_price" type="hidden" name="est_tag[tax_price][]" value="" /><input type="hidden" class="market_rate_cost" name="est_tag[market_rate_cost][]"><input type="hidden" class="market_rate_tax" name="est_tag[market_rate_tax][]"></td>'

                                        +'<td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'

                                        +'</tr>';

                                        $('#estimation_tag_details tbody').append(row);

                		                calculatetag_SaleValue();

                		                

                                    }

                                }

                            }); 

                        }else{

                            var row = "";

                                        row += '<tr>'

                                        +'<td><input class="est_tag_name" type="text" name="est_tag[tag_name][]" value='+data[0].label+' placeholder="Enter tag code" required autocomplete="off"/><input class="est_tag_id" type="hidden" name="est_tag[tag_id][]" value='+data[0].tag_id+' placeholder="Enter tag code" required /><input class="id_orderdetails" type="hidden" name="est_tag[id_orderdetails][]" value="'+data[0].id_orderdetails+'" /><input class="orderno" type="hidden" name="est_tag[orderno][]" value="'+data[0].order_no+'"/><input class="rate_field" type="hidden"  value="'+data[0].rate_field+'"/><input class="market_rate_field" type="hidden"  value="'+data[0].market_rate_field+'"/></td>'

                                        +'<td><input type="checkbox" class="partial"><input type="hidden" class="is_partial"  name="est_tag[is_partial][]"></td>'

                                        +'<td><div class="prodct_name">'+data[0].product_name+'</div><input type="hidden" class="pro_id" name="est_tag[pro_id][]" value='+data[0].lot_product+' /><input type="hidden" class="metal_type" value='+data[0].metal_type+'></td>'

                                        +'<td><div class="design_name">'+data[0].design_name+'</div><input type="hidden" class="design_id" name="est_tag[design_id][]" value='+data[0].design_id+' /></td>'

                                       	+'<td><div class="order_no"></td>'

                                        +'<td><div class="purity">'+data[0].purity+'</div><input type="hidden" class="purity" name="est_tag[purity][]" value='+data[0].purity+' /></td>'

                                        +'<td><div class="sizes">'+data[0].size+'</div><input type="hidden" class="size" name="est_tag[size][]" value="'+data[0].size+'" /></td>'

                                        +'<td><div class="pieces">'+data[0].piece+'</div><input type="hidden" class="piece" name="est_tag[piece][]" value='+data[0].piece+' /></td>'

                                        +'<td><input type="text" class="gwt" name="est_tag[gwt][]" step="any" value='+data[0].gross_wt+' disabled/><input type="hidden" class="cur_gwt" name="est_tag[cur_gwt][]" value='+data[0].gross_wt+' /><input type="hidden" class="act_gwt" value='+data[0].gross_wt+' /></td>'

                                        +'<td><input type="text" class="lwt" name="est_tag[lwt][]" step="any" value='+data[0].less_wt+' disabled/></td>'

                                        +'<td><div class="nwt">'+data[0].net_wt+'</div><input type="hidden" class="tot_nwt" name="est_tag[nwt][]" value='+data[0].net_wt+' /></td>'

                                        +'<td><div class="wastage">'+data[0].retail_max_wastage_percent+'</div><input type="hidden" name="est_tag[wastage][]" class="wastage_max_per" value='+data[0].retail_max_wastage_percent+' /></td>'

                                        +'<td><div class="mc">'+data[0].tag_mc_value+'</div></td>'

                                        +'<td><div class="cost">'+data[0].sales_value+'</div><input class="sales_value" type="hidden" name="est_tag[cost][]" value='+data[0].sales_value+' /><input class="tag_item_rate" type="hidden" name="est_tag[item_rate][]" value='+data[0].item_rate+' /><input class="caltype" type="hidden" name="est_tag[caltype][]" value='+data[0].calculation_based_on+' /><input class="tgi_calculation" type="hidden" name="est_tag[tgi_calculation][]" value='+data[0].tgi_calculation+' /><input type="hidden" class="tax_group_id" value="'+data[0].tax_group_id+'" /><input class="tax_percentage" type="hidden" name="est_tag[tax_percentage][]" value='+data[0].tax_percentage+' /><input class="stone_price" type="hidden" name="est_tag[stone_price][]" value='+data[0].stone_price+' /><input class="certification_price" type="hidden" name="est_tag[certification_price][]" value='+data[0].certification_cost+' /><input class="id_mc_type" type="hidden" name="est_tag[id_mc_type][]" value='+data[0].tag_mc_type+' /><input class="mc_value" type="hidden" name="est_tag[mc][]" value='+data[0].tag_mc_value+' /><input class="act_mc_value" type="hidden" value='+data[0].tag_mc_value+' /><input class="tax_price" type="hidden" name="est_tag[tax_price][]" value="" /><input type="hidden" class="market_rate_cost" name="est_tag[market_rate_cost][]"><input type="hidden" class="market_rate_tax" name="est_tag[market_rate_tax][]"></td>'

                                        +'<td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'

                                        +'</tr>';

                                        $('#estimation_tag_details tbody').append(row);

                		                calculatetag_SaleValue();

                        }

                        

                        

                    }

                    $('#est_tag_scan').val('');

                }

                });

	    }

	   $(".overlay").css('display','none');

	}

	

	$('#select_catalog_details').change(function() {

        if(this.checked) {

			if($('#estimation_catalog_details tbody tr').length == 0){ 

				var id_branch=$('#id_branch').val();

				if(id_branch!='')

				{

					 $(".catalog_details").show();

					create_new_empty_est_catalog_row();

				}

				else

				{

					alert('Please Select Branch');

					$('#select_catalog_details').prop('checked',false);

				}

			}

        }else{

			$(".catalog_details").hide();

		}

    });

	$('#create_catalog_details').on('click', function(){

		if(validateCatalogDetailRow()){

			create_new_empty_est_catalog_row();

		}else{

				$.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Fill The Required Fields..'});

		}

	});

	$('#create_custom_details').on('click', function(){

		if(validateCustomDetailRow()){

			create_new_empty_est_custom_row();

		}else{

			$.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Fill The Required Fields..'});

		}

	});

	$('#select_custom_details').change(function() {

        if(this.checked) {

			if($('#estimation_custom_details tbody tr').length == 0){

				var id_branch=$('#id_branch').val();

				if(id_branch!='')

				{

					$(".custom_details").show();

					create_new_empty_est_custom_row();

				}else{

					alert('Please Select Branch');

					$('#select_custom_details').prop('checked',false);

				}

			}

        }else{

			$(".custom_details").hide();

		}

    });

	$('#select_oldmatel_details').change(function() {

        if(this.checked)

        {

			if($('#estimation_old_matel_details tbody tr').length == 0)

			{

				var id_employee=$('#id_employee').val();

				var id_branch=$('#id_branch').val();

				if(id_employee!='' && id_branch!='')

				{

					$(".old_matel_details").show();

					create_new_empty_est_oldmatel_row();

				}

				else

				{

					alert('Please Select Branch and Employee');

					$('#select_oldmatel_details').prop('checked',false);

				}

			}

        }

        else

        {

			$(".old_matel_details").hide();

		}

    });

	$('#create_old_matel_details').on('click', function(){

		if(validateOldMatelDetailRow()){

			create_new_empty_est_oldmatel_row();

		}else{

			alert("Please fill required fields");

		}

	});

	$('#select_has_stn_details').change(function() {

        if(this.checked) {

            $(".stone_details").show();

			if($('#estimation_stone_details tbody tr').length == 0){

				create_new_empty_est_stone_row();

			}

        }else{

			$(".stone_details").hide();

		}

    });

	$('#create_stone_details').on('click', function(){

		if(validateStoneDetailRow()){

			create_new_empty_est_stone_row();

		}else{

			alert("Please fill required fields");

		}

	});

	$('#select_materials_details').change(function() {

        if(this.checked) {

            $(".material_details").show();

			if($('#estimation_material_details tbody tr').length == 0){

				create_new_empty_est_material_row();

			}

        }else{

			$(".material_details").hide();

		}

    });

	$('#create_material_details').on('click', function(){

		if(validateMaterialDetailRow()){

			create_new_empty_est_material_row();

		}else{

			alert("Please fill required fields");

		}

	});

	$('#select_voucher_details').change(function() {

        if(this.checked) {

            $(".gift_voucher_details").show();

			if($('#estimation_gift_voucher_details tbody tr').length == 0){

				create_new_empty_est_voucher_row();

			}

        }else{

			$(".gift_voucher_details").hide();

		}

    });

	$('#create_gift_voucher_details').on('click', function(){

		if(validateVoucherDetailRow()){

			create_new_empty_est_voucher_row();

		}else{

			alert("Please fill required fields");

		}

	});

	$('#select_chit_details').change(function() {

        if(this.checked) {

            $(".chit_details").show();

			if($('#estimation_chit_details tbody tr').length == 0){

				create_new_empty_est_chit_row();

			}

        }else{

			$(".chit_details").hide();

		}

    });

	$('#create_chit_details').on('click', function(){

		if(validateChitDetailRow()){

			create_new_empty_est_chit_row();

		}else{

			alert("Please fill required fields");

		}

	});

	

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

					add_cutomer($('#cus_first_name').val(),$('#cus_mobile').val(),$('#village_select').val(),$('#cus_type:checked').val(),$('#country').val(),$('#state').val(),$('#city').val());

					$('#cus_first_name').val('');

					$('#cus_mobile').val('');

			}else{

				$(".cus_mobile").html("Please enter customer mobile");

			}

		}else{

			$(".cus_first_name").html("Please enter customer first name");

		}

	});

	/* Customer search. - Start */	

	$("#est_cus_name").on("keyup",function(e){ 

		var customer = $("#est_cus_name").val();

		if(customer.length >= 1) { 

			getSearchCustomers(customer);

		}

	}); 

	/* Ends - Customer search. */

	$(document).on('keyup',	"#estimation_tag_details input[type='text'], #estimation_catalog_details input[type='text'], #estimation_custom_details input[type='text'], #estimation_stone_details input[type='number'], #estimation_material_details input[type='text']", function(e){

		calculate_purchase_details();

	});

	$(document).on('change',"#estimation_tag_details input[type='text'], #estimation_catalog_details input[type='text'], #estimation_custom_details input[type='text'], #estimation_stone_details input[type='number'], #estimation_material_details input[type='text']", function(e){

		calculate_purchase_details();

	});

	$(document).on('change', "#estimation_old_matel_details input[type='text'], #estimation_gift_voucher_details input[type='text'], #estimation_chit_details input[type='text'], .summary_discount_amt, .old_item_type", function(e) {

		calculate_sales_details();

	});

	$(document).on('change',".purpose",function(e){

		var row = $(this).closest('tr');

		if(this.value!='')

		{

			row.find('.id_purpose').val(this.value);

		}

		else

		{

			row.find('.id_purpose').val(2);

		}

	});

	/* Chit number Search */

	$(document).on('keyup','.scheme_account_id',function(){

		var searchTxt=this.value;

		var row = $(this).closest('tr'); 

		get_scheme_acc_number(searchTxt,row);

	});

	/* Chit number Search*/

	/* Tag id search. - Start */

	



	

	$(document).bind('paste',".est_tag_name", function(e){

		var row = $(this).closest('tr');

		var tagData = $(this).val();

		var type  = "";

		var searchTxt  = "";

		console.log(tagData); 

		if(tagData != ""){

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

	});



	$(document).on('keyup',	".est_tag_name", function(e){ 

	    console.log(2);

		var row = $(this).closest('tr');

		var tagData = this.value;

		var type  = "";

		var searchTxt  = "";

		if(tagData != ""){

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

	}); 

	

		$(document).on('keyup',	".cus_tag_name", function(e){ 

		var row = $(this).closest('tr');

		var tagData = this.value;

		var type  = "";

		var searchTxt  = "";

		if(tagData != ""){

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

						getCusSearchTags(searchTxt, type, row);

					}else{

						alert("Select Branch");

						$(this).val("");

					}

				}else{

					getCusSearchTags(searchTxt, type, row);

				}

			}

		} 

	});

	

	/* Ends - tag id search. */

	/* Product id search. - Start */

	$(document).on('keyup',	".cat_product", function(e){ 

		var row = $(this).closest('tr'); 

		var product = row.find(".cat_product").val();

		 if(product.length>=2)

		 {

		     getSearchProducts(product, row);

		 }else{

		     row.find(".cat_pro_id").val('');

		 }

		

	}); 

	



	/* Ends - product id search. */

	/* Design id search. - Start */

	$(document).on('keyup',	".cat_design", function(e){ 

		//var row = $(this).parent().parent();

		var row = $(this).closest('tr'); 

		var design = row.find(".cat_design").val();

		

		if(design.length>=2)

		 {

		    getSearchDesign(design, row);

		 }else{

		     row.find(".cat_des_id").val('');

		 }

		

	}); 

	

	$(document).on('keyup',	".cus_design", function(e){ 

		//var row = $(this).parent().parent();

		var row = $(this).closest('tr'); 

		var design = row.find(".cus_design").val();

		getSearchCusDesign(design, row);

	}); 

	

	/* Ends - design id search. */

	//Catalog Details

	$(document).on('keyup',	".lot_no", function(e){ 

		var lot_no = this.value;		

		var row = $(this).closest('tr'); 

		if(lot_no.length > 0){

		    getSearch_lot(lot_no, row);

		}

	});

	$(document).on('keyup', '.cat_gwt, .cat_lwt, .cat_wastage, .cat_mcm ,.cat_pcs ,.cat_mc', function(e){

		var row = $(this).closest('tr'); 

		var id_purity=row.find('.cat_purity').val();

	    if(id_purity=='')

	    {

	       $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select Purity..'});

	    }

	    else

	    {

	        var gross_wt = (isNaN(row.find('.cat_gwt').val()) || row.find('.cat_gwt').val() == '')  ? 0 : row.find('.cat_gwt').val();

    		var less_wt  = (isNaN(row.find('.cat_lwt').val()) || row.find('.cat_lwt').val() == '')  ? 0 : row.find('.cat_lwt').val();

    		var net_wt = parseFloat(parseFloat(gross_wt) - parseFloat(less_wt)).toFixed(3);

    		row.find('.cat_nwt').val(net_wt);

    		calculateSaleValue();

	    }

		

	});	

	$(document).on('keyup','.cat_dis',function(e){

		var row = $(this).closest('tr');

		var disc_limit=parseFloat($('#disc_limit').val());

		if(parseFloat(this.value)>disc_limit)

		{

			row.find('.cat_dis').val('');

			row.find('.cat_dis').focus();

			alert('Your Maximum Discount Limit is'+disc_limit);

		}

		calculateSaleValue(); 

	});

	$(document).on('change','.mc_type',function(e){

		var row = $(this).closest('tr');

		if(this.value!='')

		{

			row.find('.id_mc_type').val(this.value);

		}

		else

		{

			row.find('.id_mc_type').val(this.value);

		}

		calculateSaleValue(); 

	});

	$(document).on('keypress', '.cat_mc, .cat_amt', function(e){

		if(e.which === 13)

		{

			e.preventDefault();

			if(validateCatalogDetailRow()){

				create_new_empty_est_catalog_row();

			}else{

				alert("Please fill required fields");

			}

		}

	});

	// Piece

$(document).on('change','.cat_pcs',function(e){

	var curr_used_pcs = 0;

	var tot_blc_pcs = 0;

	var row = $(this).closest('tr'); 

	var cat_des_id = $(this).closest('tr').find('.cat_des_id').val(); 

	var is_non_tag = $(this).closest('tr').find('.is_non_tag').val(); 

	if(is_non_tag==1)

	{

		$('#estimation_catalog_details> tbody  > tr').each(function(index, tr) {

			var id_design=$(this).find('.cat_des_id').val();

			if(id_design==cat_des_id)

			{

				tot_blc_pcs = $(this).find('.tot_blc_pcs').val(); 

				curr_used_pcs =parseFloat(($(this).find('.cat_pcs').val()=='' ?0 :$(this).find('.cat_pcs').val()));

			}

		});

		if(tot_blc_pcs < curr_used_pcs)

		{

			row.find('.cat_pcs').val(1);

			row.find('.cat_pcs').focus();

			alert("Entered pieces greater than available pieces.");

		}else{

			row.find('.blc_pcs').html(tot_blc_pcs-curr_used_pcs);

		}

	}

});

//pieces

//gross wt

$(document).on('change','.cat_gwt',function(e){

	var curr_used_gwt = 0;

	var tot_blc_gwt = 0;

	var row = $(this).closest('tr'); 

	var cat_des_id = $(this).closest('tr').find('.cat_des_id').val(); 

	var is_non_tag = $(this).closest('tr').find('.is_non_tag').val(); 

	if(is_non_tag==1)

	{

		$('#estimation_catalog_details> tbody  > tr').each(function(index, tr) {

			var id_design=$(this).find('.cat_des_id').val();

			var is_non_tag=$(this).find('.is_non_tag').val();

			if((id_design==cat_des_id) && (is_non_tag==1))

			{

				tot_blc_gwt = $(this).find('.tot_blc_gwt').val(); 

				curr_used_gwt=parseFloat(($(this).find('.cat_gwt').val()=='' ?0 :$(this).find('.cat_gwt').val()));

			}

		});

		if(tot_blc_gwt < curr_used_gwt)

		{

			row.find('.cat_gwt').val('');

			row.find('.cat_nwt').val('');

			row.find('.cat_gwt').focus();

			alert("Entered weight greater than available weight.");

		}else{

			row.find('.blc_gwt').html(parseFloat(tot_blc_gwt-curr_used_gwt).toFixed(3));

		}

	}

});

//gross wt

	//Catalog Details

	/* custom Product id search. - Start */

	$(document).on('keyup',	".cus_product", function(e){ 

		var row = $(this).closest('tr'); 

		var product = row.find(".cus_product").val();

		getSearchCustomProducts(product, row);

	}); 

	/* Ends - product id search. */

	$(document).on('keyup', '.cus_gwt, .cus_lwt, .cus_wastage, .cus_mc', function(e){

		var row = $(this).closest('tr'); 

		var gross_wt = (isNaN(row.find('.cus_gwt').val()) || row.find('.cus_gwt').val() == '')  ? 0 : row.find('.cus_gwt').val();

		var less_wt  = (isNaN(row.find('.cus_lwt').val()) || row.find('.cus_lwt').val() == '')  ? 0 : row.find('.cus_lwt').val();

		var net_wt = parseFloat(parseFloat(gross_wt) - parseFloat(less_wt)).toFixed(3);

		row.find('.cus_nwt').val(net_wt);

		calculateCustomItemSaleValue();

	});	

	$(document).on('keyup','.cus_dis',function(e){

		var row = $(this).closest('tr');

		var disc_limit=parseFloat($('#disc_limit').val());

		if(parseFloat(this.value)>disc_limit)

		{

			row.find('.cus_dis').val('');

			row.find('.cus_dis').focus();

			alert('Your Maximum Discount Limit is'+disc_limit);

		}

		calculateCustomItemSaleValue(); 

	});

	$(document).on('change','.cus_mc_type ',function(e){

		var row = $(this).closest('tr');

		if(this.value!='')

		{

			row.find('.id_mc_type').val(this.value);

		}

		else

		{

			row.find('.id_mc_type').val(this.value);

		}

		calculateCustomItemSaleValue(); 

	});

	$(document).on('keypress', '.cus_mc, .cus_amt', function(e){

		if(e.which === 13)

		{

			e.preventDefault();

			if(validateCustomDetailRow()){

				create_new_empty_est_custom_row();

			}else{

				alert("Please fill required fields");

			}

		}

	});

	//Old Gold

	$(document).on('change', '.old_rate', function(e){

	   var row = $(this).closest('tr'); 

	   calculateOldMatelItemSaleValue(row);

	});

	$(document).on('change', '.old_gwt, .old_dwt,.old_swt', function(e){

		var row = $(this).closest('tr'); 

		var gross_wt = (isNaN(row.find('.old_gwt').val()) || row.find('.old_gwt').val() == '')  ? 0 : row.find('.old_gwt').val();

		var dust_wt  = (isNaN(row.find('.old_dwt').val()) || row.find('.old_dwt').val() == '')  ? 0 : row.find('.old_dwt').val();

		var stone_wt  = (isNaN(row.find('.old_swt').val()) || row.find('.old_swt').val() == '')  ? 0 : row.find('.old_swt').val();

		var old_wastage  = (isNaN(row.find('.old_wastage').val()) || row.find('.old_wastage').val() == '')  ? 0 : row.find('.old_wastage').val();

		var wastage_wt  = 0;

		var other_stone_wt  = (isNaN(row.find('.stone_wt').val()) || row.find('.stone_wt').val() == '')  ? 0 : row.find('.stone_wt').val();

		var net_wt = parseFloat(parseFloat(gross_wt) -(parseFloat(dust_wt)+parseFloat(stone_wt)+parseFloat(other_stone_wt)+parseFloat(wastage_wt))).toFixed(3);

		wastage_wt = parseFloat((net_wt * (old_wastage / 100))).toFixed(3);

		net_wt = parseFloat(parseFloat(gross_wt) -parseFloat(dust_wt)-parseFloat(stone_wt)-parseFloat(other_stone_wt)-parseFloat(wastage_wt)).toFixed(3);

		row.find('.old_wastage_wt').val(wastage_wt);

		row.find('.old_nwt').val(net_wt);

		calculateOldMatelItemSaleValue(row);

	});

	

		$(document).on('change', '.old_wastage', function(e){

		var row = $(this).closest('tr'); 

		var gross_wt = (isNaN(row.find('.old_gwt').val()) || row.find('.old_gwt').val() == '')  ? 0 : row.find('.old_gwt').val();

		var dust_wt  = (isNaN(row.find('.old_dwt').val()) || row.find('.old_dwt').val() == '')  ? 0 : row.find('.old_dwt').val();

		var stone_wt  = (isNaN(row.find('.old_swt').val()) || row.find('.old_swt').val() == '')  ? 0 : row.find('.old_swt').val();

		var old_wastage  = (isNaN(row.find('.old_wastage').val()) || row.find('.old_wastage').val() == '')  ? 0 : row.find('.old_wastage').val();

		var wastage_wt  = 0;

		var other_stone_wt  = (isNaN(row.find('.stone_wt').val()) || row.find('.stone_wt').val() == '')  ? 0 : row.find('.stone_wt').val();

		var net_wt = parseFloat(parseFloat(gross_wt) -(parseFloat(dust_wt)+parseFloat(stone_wt)+parseFloat(other_stone_wt)+parseFloat(wastage_wt))).toFixed(3);

		wastage_wt = parseFloat((net_wt * (old_wastage / 100))).toFixed(3);

		net_wt = parseFloat(parseFloat(gross_wt) -parseFloat(dust_wt)-parseFloat(stone_wt)-parseFloat(other_stone_wt)-parseFloat(wastage_wt)).toFixed(3);

		row.find('.old_wastage_wt').val(wastage_wt);

		row.find('.old_nwt').val(net_wt);

		calculateOldMatelItemSaleValue(row);

	});

	

	$(document).on('change', '.old_wastage_wt', function(e){

		var row = $(this).closest('tr'); 

		var gross_wt = (isNaN(row.find('.old_gwt').val()) || row.find('.old_gwt').val() == '')  ? 0 : row.find('.old_gwt').val();

		var dust_wt  = (isNaN(row.find('.old_dwt').val()) || row.find('.old_dwt').val() == '')  ? 0 : row.find('.old_dwt').val();

		var stone_wt  = (isNaN(row.find('.old_swt').val()) || row.find('.old_swt').val() == '')  ? 0 : row.find('.old_swt').val();

		var old_wastage_wt  = 0;

		var other_stone_wt  = (isNaN(row.find('.stone_wt').val()) || row.find('.stone_wt').val() == '')  ? 0 : row.find('.stone_wt').val();

		var old_wastage  = (isNaN(row.find('.old_wastage').val()) || row.find('.old_wastage').val() == '')  ? 0 : row.find('.old_wastage').val();

		var old_nwt  = (isNaN(row.find('.old_nwt').val()) || row.find('.old_nwt').val() == '')  ? 0 : row.find('.old_nwt').val();

    	var net_wt = parseFloat(parseFloat(gross_wt) -(parseFloat(dust_wt)+parseFloat(stone_wt)+parseFloat(other_stone_wt)+parseFloat(old_wastage_wt))).toFixed(3);

        var wsatage_per =((parseFloat(old_wastage_wt)*100)/parseFloat(row.find('.old_nwt').val()));

        row.find('.old_nwt').val(net_wt);

        row.find('.old_wastage').val(wsatage_per);

		calculateOldMatelItemSaleValue(row);

	});

	$(document).on('keypress', '.old_use_type, .old_amount', function(e){

		if(e.which === 13)

		{

			e.preventDefault();

			if(validateOldMatelDetailRow()){

				create_new_empty_est_old_row();

			}else{

				alert("Please fill required fields");

			}

		}

	});

	$(document).on('keypress', '.stone_price', function(e){

		if(e.which === 13)

		{

			e.preventDefault();

			if(validateStoneDetailRow()){

				create_new_empty_est_stone_row();

			}else{

				alert("Please fill required fields");

			}

		}

	});

	$(document).on('keypress', '.material_price', function(e){

		if(e.which === 13)

		{

			e.preventDefault();

			if(validateMaterialDetailRow()){

				create_new_empty_est_material_row();

			}else{

				alert("Please fill required fields");

			}

		}

	});

    //Old gold	

	$(document).on('keypress', '.gift_voucher_amt', function(e){

		if(e.which === 13)

		{

			e.preventDefault();

			if(validateVoucherDetailRow()){

				create_new_empty_est_voucher_row();

			}else{

				alert("Please fill required fields");

			}

		}

	});

	$(document).on('keypress', '.chit_amt', function(e){

		if(e.which === 13)

		{

			e.preventDefault();

			if(validateChitDetailRow()){

				create_new_empty_est_chit_row();

			}else{

				alert("Please fill required fields");

			}

		}

	});

	old_metal_rate = [];
	old_metal_category = [];

	function get_all_old_metal_rates()
	{
		my_Date = new Date();
		$.ajax({ 
			url:base_url+ "index.php/admin_ret_estimation/get_all_old_metal_rates?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),
			type:"POST",
			dataType:"JSON",
			success:function(data)
			{
				old_metal_rate = data;
				//row.find('.old_rate').val(data.rate);
			},
			error:function(error)  
			{	
			} 
		});
	}

	function get_old_metal_categories()
	{
		my_Date = new Date();
		$.ajax({ 
			url:base_url+ "index.php/admin_ret_estimation/get_old_metal_category?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),
			type:"POST",
			dataType:"JSON",
			success:function(data)
			{
				old_metal_category = data;
				//row.find('.old_rate').val(data.rate);
			},
			error:function(error)  
			{	
			} 
		});
	}

	$(document).on('change', '.old_id_category', function(e)
	{
		var row = $(this).closest('tr'); 
		row.find('.old_metal_type option:gt(0)').remove();
		row.find('.old_metal_category option:gt(0)').remove();
		row.find('.old_rate').val("");
		if(this.value > 0)
			get_old_metal_type(this.value,row);
		
	});
	
	function get_old_metal_type(id_metal,curRow)
	{
	    $(".overlay").css('display','block');
    	$.ajax({
    		type: 'POST',
    		url: base_url+'index.php/admin_ret_estimation/get_old_metal_type',
    		dataType:'json',
    		data:{'id_metal':id_metal},
    		success:function(data){
    		    var metal_type="";
    		  	$.each(data, function (pkey, pitem) {
            		metal_type += "<option value='"+pitem.id_metal_type+"'>"+pitem.metal_type+"</option>";
            	});	
	            curRow.find('.old_metal_type').append(metal_type);
    			$(".overlay").css('display','none');
    		}
    	});
	}
	
	$(document).on('change', '.old_metal_type', function(e)
	{  
		var row = $(this).closest('tr'); 
		row.find('.old_metal_category option:gt(0)').remove();
		row.find('.old_rate').val("");
		var id_old_metal = this.value;
		if(id_old_metal > 0) {
			var metal_cat="";
			$.each(old_metal_category, function (pkey, pitem) {
				if(pitem.id_old_metal_type == id_old_metal) {
					metal_cat += "<option value='"+pitem.id_old_metal_cat+"'>"+pitem.old_metal_cat+"</option>";
				}
			});	

			row.find('.old_metal_category').append(metal_cat);
		}
	});

	$(document).on('change', '.old_metal_category', function(e)
	{
		var row = $(this).closest('tr'); 
		row.find('.old_rate').val("");
		var id_old_metal_cat = this.value;
		var id_old_metal = row.find('.old_metal_type').val();
		var id_metal = row.find('.old_id_category').val();
		if(id_old_metal_cat > 0 && id_old_metal > 0 && id_metal > 0) {
			let metal_perc = 0;
			let metal_rate = 0;
			$.each(old_metal_category, function(catkey, catitem){
				if(catitem.id_old_metal_type == id_old_metal) {
					if(catitem.id_old_metal_cat == id_old_metal_cat) {
						metal_perc = catitem.old_metal_perc;
						return;
					}
				}
			});

			$.each(old_metal_rate, function(orkey, oritem){
				if(oritem.id_metal == id_metal) {
					metal_rate = oritem.rate;
				}
			});

			metal_rate = metal_rate - (metal_rate * metal_perc/100);
			row.find('.old_rate').val(metal_rate);
		}
	});

	$('#branch_select').on('change',function(){

		if(this.value!='')

		{

			$('#id_branch').val(this.value);

			if(ctrl_page[2]!='list')

			{

			    get_metal_rates_by_branch(this.value);

			    get_employee(this.value);

			}

		}

		else

		{

			$('#id_branch').val('');

		}

	});

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

	/*$('#estimation_datetime').datetimepicker(

	{ 

		format: 'dd-mm-yyyy H:m:s'

	});*/

	$('#gross_wt, #less_wt').on('keyup', function(e){

		var gross_wt = (isNaN($('#gross_wt').val()) || $('#gross_wt').val() == '')  ? 0 : $('#gross_wt').val();

		var less_wt  = (isNaN($('#less_wt').val()) || $('#less_wt').val() == '')  ? 0 : $('#less_wt').val();

		var net_wt = parseFloat(gross_wt) - parseFloat(less_wt);

		$('#net_wt').val(net_wt);

		calculateWastage();

		calculateSaleValue();

	});	

	$('input[type=checkbox][name="tagging[calculation_based_on]"]').change(function() {

		calculateWastage();

		calculateSaleValue();

	});

	$("#est_cus_id").select2({			    

	 	placeholder: "Select Customer",			    

	 	allowClear: true		    

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

					tax_details = data;

					calculateSaleValue();

				}

			 });

		}

	});

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

	$("#_create_stn_details").on('click',function(){

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

	}); 

	$('#emp_select').on('change',function(){

		if(this.value!='')

		{

			$('#id_employee').val(this.value);

			 $.each(emp_details, function (key, item){

			 var id_employee=$('#id_employee').val();

			 	if(item.id_employee==id_employee)

			 	{

			 		$('#disc_limit_type').val(item.disc_limit_type);

			 		$('#disc_limit').val(item.disc_limit);

			 		$('#allowed_old_met_pur').val(item.allowed_old_met_pur);

			 		if(item.allow_branch_transfer==1)

			 		{

			 			$('#type1,#type2,#type3').prop('disabled',false);

			 		}else{

			 			$('#type1,#type2').prop('disabled',true);

			 		}

			 	}

			 });

		}

		else

		{

			$('#id_employee').val('');

			$('#disc_limit_type').val('');

			$('#disc_limit').val('');

			$('#allowed_old_met_pur').val('');

		}

	});

	//Employee Filter

	$("#est_print").click(function() {

	    var form_validate=false;

		var esti_for = $("input[name='estimation[esti_for]']:checked").val();

		var ask_cus_data = (esti_for == 1 ? ($('#cus_id').val() == '' ? false:true): true);

		var ask_branch = ($('#id_branch').val() == '' ? false : true); 

		var ask_emp_data = ($('#id_employee').val() == '' ? false : true); 

		if(!ask_branch){

			alert('Please Select Branch');

		}

		else if(!ask_emp_data){

			alert('Please Select Employee');

		}

		else if(!ask_cus_data){

			alert('Please Select Customer');

		}

		

		if($('#select_tag_details').is(":checked"))

		{

		    if($('#estimation_tag_details').length>=0)

		    {

		         if(validateTagDetailRow())

		         {

		             form_validate=true;

		         }else{

		             form_validate=false;

		             $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Fill The All Required Fields in Tag Detail Row..'});

		         }

		    }else{

		        form_validate=false;

		        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Fill The Tagging Details..'});

		        alert('Please Fill The Tagging Details..');

		    }

		}

		

		if($('#select_catalog_details').is(":checked"))

		{

		    if($('#estimation_catalog_details').length>=0)

		    {

		         if(validateCatalogDetailRow())

		         {

		             form_validate=true;

		         }else{

		             form_validate=false;

		             $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Fill The All Required Fields in Catalog Bill Detail Row..'});

		         }

		    }else{

		        form_validate=false;

		        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Fill The Catalog Details..'});

		    }

		}

		

		if($('#select_custom_details').is(":checked"))

		{

		    if($('#estimation_custom_details').length>=0)

		    {

		         if(validateCatalogDetailRow())

		         {

		             form_validate=true;

		         }else{

		             form_validate=false;

		             $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Fill The All Required Fields in Home Bill Detail Row..'});

		         }

		    }else{

		        form_validate=false;

		        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Fill The Home Bill Details..'});

		    }

		}

		

		if($('#select_oldmatel_details').is(":checked"))

		{

		    if($('#estimation_old_matel_details').length>=0)

		    {

		         if(validateOldMatelDetailRow())

		         {

		             form_validate=true;

		         }else{

		             form_validate=false;

		             $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Fill The All Required Fields in Old Metal Row..'});

		         }

		    }else{

		        form_validate=false;

		        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Fill The All Required Fields in Old Metal Row..'});

		    }

		}

		

	    if(ask_cus_data && ask_branch && ask_emp_data && form_validate)

		{

			my_Date = new Date();

		    $("div.overlay").css("display", "block"); 

		    $("#pay_print").attr("disabled", true); 

		    $("#pay_save").attr("disabled", true); 

			var post_data=$('#est_form').serialize();

			if(ctrl_page[2]=='add')

			{

				var url=base_url+ "index.php/admin_ret_estimation/estimation/save?nocache=" + my_Date.getUTCSeconds();

			}

			else

			{

				var url=base_url+'index.php/admin_ret_estimation/estimation/update/'+ctrl_page[3]+'?nocache=' + my_Date.getUTCSeconds();

			}

		    $.ajax({ 

		        url:url,

		        data: post_data,

		        type:"POST",

		        dataType:"JSON",

		        success:function(data){

		            $("#est_print").attr("disabled", false); 

		            if(data.type==2)

		            {

				    	window.location.href= base_url+'index.php/admin_ret_estimation/estimation/list';

		            }

		            else

		            {

		                if(data.status)

		            	{

		                    window.open( base_url+'index.php/admin_ret_estimation/generate_invoice/'+data['id'],'_blank');

		                    //window.open( base_url+'index.php/admin_ret_estimation/generate_brief_copy/'+data['id'],'_blank');

		            	}

		                window.location.href= base_url+'index.php/admin_ret_estimation/estimation/list';

		            }

					$("div.overlay").css("display", "none"); 

		        },

		        error:function(error)  

		        {	

		        $("#est_print").attr("disabled", false); 	

		        $("div.overlay").css("display", "none"); 

		        } 

		    });

		}

	});

});

function deleteEstimation(id){

	my_Date = new Date();

	$("div.overlay").css("display", "block"); 

	$.ajax({

		url:base_url+"index.php/admin_ret_estimation/estimation/delete/"+id+"?nocache=" + my_Date.getUTCSeconds(),

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

function add_cutomer(cus_name, cus_mobile,id_village,cus_type,id_country,id_state,id_city)

{ //, cus_address

	my_Date = new Date();

	$.ajax({

        url: base_url+'index.php/admin_ret_estimation/createNewCustomer/?nocache=' + my_Date.getUTCSeconds(),             

        dataType: "json", 

        method: "POST", 

        data: {'cusName': cus_name, 'cusMobile' : cus_mobile, 'cusBranch' : $('#id_branch').val(),'id_village':id_village,'cus_type':cus_type,'id_country':id_country,'id_state':id_state,'id_city':id_city}, //Need to update login branch id here from session

        success: function (data) { 

			if(data.success == true){

				$('#confirm-add').modal('toggle');

				$("#est_cus_name").val(data.response.firstname + " - " + data.response.mobile);

				$("#cus_id").val(data.response.id_customer);

			}else{

				alert(data.message);

			}

        }

     });

}

function getSearchCustomers(searchTxt){

    var esti_for = $("input[name='estimation[esti_for]']:checked").val();

	my_Date = new Date();

	$.ajax({

        url: base_url+'index.php/admin_ret_estimation/getCustomersBySearch/?nocache=' + my_Date.getUTCSeconds(),             

        dataType: "json", 

        method: "POST", 

        data: {'searchTxt': searchTxt,'esti_for':esti_for}, 

        success: function (data) {

			$( "#est_cus_name" ).autocomplete(

			{

				source: data,

				select: function(e, i)

				{ 

					e.preventDefault();

					$("#est_cus_name").val(i.item.label);

					$("#cus_id").val(i.item.value);

					$("#cus_village").html(i.item.village_name);

				    $('#id_country').val(i.item.id_country);

				    $('#id_city').val(i.item.id_city);

				    $('#id_state').val(i.item.id_state);

					$("#cus_info").append(i.item.vip == 'Yes' ? "<span class='label bg-orange'><i class='fa fa-fw fa-star'></i> V I P</span>":"");

					$("#cus_info").append(i.item.accounts > 0 ? "&nbsp;<span class='label label-info'>Chit Customer</span>":"");	

					

					customer_detail_modal(i.item.value); // Customer Purchase and Account Details

				},

				change: function (event, ui) {

					if (ui.item === null) {

						$(this).val('');

						$('#est_cus_name').val('');

						$("#cus_id").val("");

						$("#cus_village").html("");

						$("#cus_info").html("");

						/*$("#chit_cus").html("");

						$("#vip_cus").html("");*/

					}

			    },

				response: function(e, i) {

		            // ui.content is the array that's about to be sent to the response callback.

		            if(searchTxt != ""){

						if (i.content.length === 0) {

						    var mobile=$('#est_cus_name').val();

						    if(mobile.length==10)

						    {

						        create_customer(searchTxt);

						         $("#customerAlert").html('');

						    }else{

						        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Enter a valid customer name / mobile'});

						    }

						}

						else{

						   $("#customerAlert").html('');

						} 

					}else{

					}

		        },

				 minLength: 1,

			});

        }

     });

}



function create_customer(mobile_no)

{

   

    $('#confirm-add').modal('show');

     get_village_list();

    $('#cus_mobile').val(mobile_no);

}



function getSearchTags(searchTxt, searchField, curRow){

	my_Date = new Date();

	$.ajax({

        url: base_url+'index.php/admin_ret_estimation/getTaggingBySearch/?nocache=' + my_Date.getUTCSeconds(),             

        dataType: "json", 

        method: "POST", 

        data: {'searchTxt': searchTxt, 'searchField': searchField, 'id_branch': $("#id_branch").val()}, 

        success: function (data) {

			cur_search_tags = data;

			$.each(data, function(key, item){

				$('#estimation_tag_details > tbody tr').each(function(idx, row){

					if(item != undefined){

						if($(this).find('.est_tag_id').val() == item.value){

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

					if(curRowItem.sales_mode == 2){ // 1 - Fixed Rate, 2 - Flexible

						get_metal_rates_by_branch(i.item.current_branch);

					}

					

					if(curRowItem.tag_mark==1)

					{

					    $(".est_tag_name").css("background-color","#43e143");

					}

					

					curRow.find('.est_tag_name').val(i.item.label);

				    curRow.find('.est_tag_id').val(i.item.value); 

					curRow.find('.is_partial').val(0);

					curRow.find('.prodct_name').html(curRowItem.product_name);

					curRow.find('.design_name').html(curRowItem.design_name);

					curRow.find('.design_id').val(curRowItem.design_id);

					curRow.find('.pro_id').val(curRowItem.lot_product);

					curRow.find('.purity').html(curRowItem.purname);

					curRow.find('.purity').val(curRowItem.purity);

					curRow.find('.sizes').html(curRowItem.size);

					curRow.find('.size').val(curRowItem.size);

					curRow.find('.pieces').html(curRowItem.piece);

					curRow.find('.piece').val(curRowItem.piece);

					curRow.find('.gwt').val(curRowItem.gross_wt);

					curRow.find('.cur_gwt').val(curRowItem.gross_wt);

					curRow.find('.lwt').val(curRowItem.less_wt);

					curRow.find('.nwt').html(curRowItem.net_wt);

					curRow.find('.tot_nwt').val(curRowItem.net_wt);

					curRow.find('.wastage').html(curRowItem.retail_max_wastage_percent);

					curRow.find(".wastage_max_per").val(curRowItem.retail_max_wastage_percent);

					curRow.find('.mc').html(curRowItem.tag_mc_value);

					curRow.find('.mc_value').val(curRowItem.tag_mc_value);

					curRow.find('.cost').html(curRowItem.sales_value);

					curRow.find(".sales_value").val(curRowItem.sales_value);

					curRow.find(".act_sales_value").val(curRowItem.sales_value);

					curRow.find(".caltype").val(curRowItem.calculation_based_on); 

					curRow.find(".tax_percentage").val(curRowItem.tax_percentage);

					curRow.find(".tgi_calculation").val(curRowItem.tgi_calculation);

					curRow.find(".stone_price").val(curRowItem.stone_price);

				    curRow.find(".certification_price").val(curRowItem.certification_cost);

					curRow.find(".id_mc_type").val(curRowItem.tag_mc_type);

					curRow.find(".tag_item_rate").val(curRowItem.item_rate);

					curRow.find(".metal_type").val(curRowItem.metal_type);

					curRow.find(".tax_group_id").val(curRowItem.tax_group_id);

					curRow.find(".act_gwt").val(curRowItem.gross_wt);

					curRow.find(".act_mc_value").val(curRowItem.tag_mc_value);

					curRow.find(".id_orderdetails").val(curRowItem.id_orderdetails);

					curRow.find(".order_no").html(curRowItem.order_no);

					

					curRow.find(".rate_field").val(curRowItem.rate_field);

					curRow.find(".market_rate_field").val(curRowItem.market_rate_field);

					

					if(curRowItem.calculation_based_on == 3 || curRowItem.calculation_based_on == 4){

						curRow.find(".partial").prop("disabled",true); 

					}else{

						curRow.find(".partial").prop("disabled",false); 

					}

					/*if(curRowItem.order_no!='')

					{

						get_order_items(curRowItem.order_no);

						

					}*/

					calculatetag_SaleValue();

					/*if(validateTagDetailRow()){

						create_new_empty_est_tag_row();

						$('#estimation_tag_details > tbody').find('tr:last td:eq(0) .est_tag_name').focus();

					}*/

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

						curRow.find('.est_tag_name').val("");

						curRow.find('.est_tag_id').val("");

					}

		        },

				 minLength: 1,

			});

        }

     });

}

function getSearch_lot(searchTxt, curRow){

	my_Date = new Date();

	$.ajax({

        url: base_url+'index.php/admin_ret_estimation/getNonTagLots/?nocache=' + my_Date.getUTCSeconds(),             

        dataType: "json", 

        method: "POST", 

        data: {'searchTxt': searchTxt, 'id_branch': $("#id_branch").val()}, 

        success: function (data) {

			$( ".lot_no" ).autocomplete(

			{

				source: data,

				select: function(e, i)

				{ 

					e.preventDefault();

					curRow.find('.lot_no').val(i.item.value);

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

						curRow.find('.lot_no').val("");

					}

		        },

				 minLength: 1,

			});

        }

     });

}



$(document).on('change', '.cat_purity', function(e){

	   var row = $(this).closest('tr'); 

	   get_search_catalog_metal_rates(row);

	});

	

function get_search_catalog_metal_rates(curRow)

{

    var id_purity=curRow.find('.cat_purity').val();

    var id_metal=curRow.find('.metal_type').val();

    if(id_purity=='')

    {

       $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select Purity..'});

        curRow.find('.cat_rate_field').val('');

        curRow.find('.cat_market_rate_field').val('');

    }

    else if(id_metal=='')

    {

        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Invalid Metal..'});

        curRow.find('.cat_rate_field').val('');

        curRow.find('.cat_market_rate_field').val('');

    }

    else

    {

        $("div.overlay").css("display", "block"); 

        my_Date = new Date();

        $.ajax({

        url: base_url+'index.php/admin_ret_estimation/get_metal_purity_rate?nocache=' + my_Date.getUTCSeconds(),             

        dataType: "json", 

        method: "POST", 

        data: {'id_purity': id_purity,'id_metal':id_metal}, 

        success: function (data) {

             $("div.overlay").css("display", "none"); 

            curRow.find('.cat_rate_field').val(data.rate_field);

            curRow.find('.cat_market_rate_field').val(data.market_rate_field);

            calculateSaleValue();

        }

        });

    }

}

	

function getSearchProducts(searchTxt, curRow){

    if(searchTxt.length>=2)

    {

	my_Date = new Date();

	$.ajax({

        url: base_url+'index.php/admin_ret_estimation/getProductBySearch/?nocache=' + my_Date.getUTCSeconds(),             

        dataType: "json", 

        method: "POST", 

        data: {'searchTxt': searchTxt,'is_non_tag':curRow.find('.is_non_tag').val()}, 

        success: function (data) {

			$( ".cat_product" ).autocomplete(

			{

				source: data,

				select: function(e, i)

				{ 

					e.preventDefault();

					//var tax_percentage=[];

					curRow.find('.cat_product').val(i.item.label);

					curRow.find('.cat_pro_id').val(i.item.value);

					curRow.find('.tax_percentage').val(i.item.tax_percentage);

					curRow.find('.tgi_calculation').val(i.item.tgi_calculation);

					curRow.find('.tax_group_id').val(i.item.tax_group_id);

					curRow.find('.cat_pcs').val(i.item.no_of_pieces);

					curRow.find('.cat_calculation_based_on').val(i.item.calculation_based_on);

					curRow.find('.metal_type').val(i.item.id_metal);

					curRow.find('.cat_design').val("");

					curRow.find('.cat_des_id').val("");

					var curRowItem = i.item;

					console.log(curRowItem);

					$('#estimation_catalog_details > tbody').find('.cat_design').focus();

				},

				change: function (event, ui) {

					if(curRow.find('.cat_pro_id').val()=='') 

					{

					    alert('Please Enter Valid Product..');

					    curRow.find('.cat_product').focus();

					    curRow.find('.cat_product').val('');

					}

				

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

						curRow.find('.cat_product').val("");

						curRow.find('.cat_pro_id').val("");

    					curRow.find('.tax_percentage').val("");

    					curRow.find('.tgi_calculation').val("");

    					curRow.find('.cat_pcs').val("");

    					curRow.find('.cat_calculation_based_on').val("");

    					curRow.find('.metal_type').val("");

					}

		        },

				 minLength: 2,

			});

        }

     });

    }

}



	$(document).on('change', '.cus_purity', function(e){

	   var row = $(this).closest('tr'); 

	   get_search_custom_metal_rates(row);

	});

	

	function get_search_custom_metal_rates(curRow)

	{

	    var id_purity=curRow.find('.cus_purity').val();

	    var id_metal=curRow.find('.metal_type').val();

	    if(id_purity=='')

	    {

	       $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select Purity..'});

	    }

	    else if(id_metal=='')

	    {

	        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Invalid Metal..'});

	    }

	    else

	    {

	        $("div.overlay").css("display", "block"); 

            my_Date = new Date();

            $.ajax({

            url: base_url+'index.php/admin_ret_estimation/get_metal_purity_rate?nocache=' + my_Date.getUTCSeconds(),             

            dataType: "json", 

            method: "POST", 

            data: {'id_purity': id_purity,'id_metal':id_metal}, 

            success: function (data) {

                 $("div.overlay").css("display", "none"); 

                curRow.find('.cus_rate_field').val(data.rate_field);

                curRow.find('.cus_market_rate_field').val(data.market_rate_field);

                calculateCustomItemSaleValue();

            }

            });

	    }

	   

	}

	

function getSearchCustomProducts(searchTxt, curRow){

    if(searchTxt.length>=2)

    {

	my_Date = new Date();

	$.ajax({

        url: base_url+'index.php/admin_ret_estimation/getCustomProductBySearch/?nocache=' + my_Date.getUTCSeconds(),             

        dataType: "json", 

        method: "POST", 

        data: {'searchTxt': searchTxt}, 

        success: function (data) {

			$( ".cus_product" ).autocomplete(

			{

				source: data,

				select: function(e, i)

				{ 

					e.preventDefault();

					curRow.find('.cus_product').val(i.item.label);

					curRow.find('.cus_product_id').val(i.item.value);

					curRow.find('.tax_percentage').val(i.item.tax_percentage);

					curRow.find('.tgi_calculation').val(i.item.tgi_calculation);

					curRow.find('.cus_pcs').val(i.item.no_of_pieces);

					curRow.find('.cus_calculation_based_on').val(i.item.calculation_based_on);

					curRow.find('.metal_type').val(i.item.metal_type);

					curRow.find('.tax_group_id').val(i.item.tax_group_id);

					var curRowItem = i.item;

					$('#estimation_custom_details > tbody').find('tr:last td:eq(1) .cus_qty').focus();

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

						curRow.find('td:eq(0) .cus_product').val("");

						curRow.find('td:eq(0) .cus_product_id').val("");

						curRow.find('.tax_percentage').val("");

					    curRow.find('.tgi_calculation').val("");

					    curRow.find('.metal_type').val("");

					}

		        },

				 minLength: 1,

			});

        }

     });

    }

}

function getSearchDesign(searchTxt, curRow){

	my_Date = new Date();

	$.ajax({

        url: base_url+'index.php/admin_ret_estimation/getProductDesignBySearch/?nocache=' + my_Date.getUTCSeconds(),             

        dataType: "json", 

        method: "POST", 

        data: { 'searchTxt': searchTxt, 'ProCode' : curRow.find('.cat_pro_id').val(),'id_branch':$('#id_branch').val()}, 

        success: function (data) {

			$( ".cat_design" ).autocomplete(

			{

				source: data,

				select: function(e, i)

				{ 

					e.preventDefault();

					curRow.find('.cat_design').val(i.item.label);

					curRow.find('.cat_des_id').val(i.item.value);

					var curRowItem = i.item;

					var curr_used_gross = 0;

					var curr_used_pcs = 0;

					if(curRow.find('.is_non_tag').val()==1)

					{

						$('#estimation_catalog_details> tbody  > tr').each(function(index, tr) {

							if($(this).find('.cat_des_id').val()==i.item.value && ($(this).find('.is_non_tag').val()==1))

							{ 

								curr_used_gross+=parseFloat(($(this).find('.cat_gwt').val()=='' ?0 :$(this).find('.cat_gwt').val()));

								curr_used_pcs+=parseFloat(($(this).find('.cat_pcs').val()=='' ?0 :$(this).find('.cat_pcs').val()));							

							}

						});

							curRow.find('.tot_blc_pcs').val(i.item.no_of_piece-curr_used_pcs);

							curRow.find('.tot_blc_gwt').val(i.item.gross_wt-curr_used_gross);

							curRow.find('.blc_pcs').html(i.item.no_of_piece-curr_used_pcs);

							curRow.find('.blc_gwt').html(i.item.gross_wt-curr_used_gross);

					}

					$('#estimation_catalog_details > tbody').find('tr:last td:eq(2) .cat_qty').focus();

				},

				change: function (event, ui) 

				{

				    if(curRow.find('.cat_des_id').val()=='') 

					{

					    alert('Please Enter Valid Design..');

					    curRow.find('.cat_design').focus();

					    curRow.find('.cat_design').val('');

					}

			    },

				response: function(e, i) {

		            // ui.content is the array that's about to be sent to the response callback.

		            if(searchTxt != ""){

						if (i.content.length !== 0) {

						   //console.log("content : ", i.content);

						}

					}else{

						curRow.find('.cat_design').val("");

						curRow.find('.cat_des_id').val("");

					}

		        },

				 minLength: 1,

			});

        }

     });

}



function getSearchCusDesign(searchTxt, curRow){

	my_Date = new Date();

	$.ajax({

        url: base_url+'index.php/admin_ret_estimation/getProductDesignBySearch/?nocache=' + my_Date.getUTCSeconds(),             

        dataType: "json", 

        method: "POST", 

        data: { 'searchTxt': searchTxt, 'ProCode' : curRow.find('.cus_product_id').val(),'id_branch':$('#id_branch').val()}, 

        success: function (data) {

			$( ".cus_design" ).autocomplete(

			{

				source: data,

				select: function(e, i)

				{ 

					e.preventDefault();

					curRow.find('.cus_design').val(i.item.label);

					curRow.find('.cus_des_id').val(i.item.value);

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

						curRow.find('.cus_des_id').val("");

					}

		        },

				 minLength: 1,

			});

        }

     });

}



function hide_page_open_details()

{

	$(".tag_details").hide();

	$(".catalog_details").hide();

	$(".custom_details").hide();

	$(".old_matel_details").hide();

	$(".stone_details").hide();

	$(".material_details").hide();

	$(".gift_voucher_details").hide();

	$(".chit_details").hide();

}

function create_new_empty_est_tag_row()

{

	var row = "";

       row += '<tr>'

			+'<td><input class="est_tag_name" type="text" name="est_tag[tag_name][]" value="" placeholder="Enter tag code" required autocomplete="off"/><input class="est_tag_id" type="hidden" name="est_tag[tag_id][]" value="" placeholder="Enter tag code" required /><input class="id_orderdetails" type="hidden" name="est_tag[id_orderdetails][]" value=""/><input class="orderno" type="hidden" name="est_tag[orderno][]" value=""/><input class="rate_field" type="hidden"  value=""/><input class="market_rate_field" type="hidden"  value=""/></td>'

			+'<td><input type="checkbox" class="partial"><input type="hidden" class="is_partial"  name="est_tag[is_partial][]"></td>'

			+'<td><div class="prodct_name"></div><input type="hidden" class="pro_id" name="est_tag[pro_id][]" value="" /><input type="hidden" class="metal_type"></td>'

			+'<td><div class="design_name"></div><input type="hidden" class="design_id" name="est_tag[design_id][]" value="" /></td>'

			+'<td><div class="order_no"></td>'

			+'<td><div class="purity"></div><input type="hidden" class="purity" name="est_tag[purity][]" value="" /></td>'

			+'<td><div class="sizes"></div><input type="hidden" class="size" name="est_tag[size][]" value="" /></td>'

			+'<td><div class="pieces"></div><input type="hidden" class="piece" name="est_tag[piece][]" value="" /></td>'

			+'<td><input type="text" class="gwt" name="est_tag[gwt][]" value="" step="any" disabled/><input type="hidden" class="cur_gwt" name="est_tag[cur_gwt][]" value=""/><input type="hidden" class="act_gwt" value=""/></td>'

			+'<td><input type="text" class="lwt"  value="" step="any" disabled/><input type="hidden" class="lwt" name="est_tag[lwt][]" value=""/></td>'

			+'<td><div class="nwt"></div><input type="hidden" class="tot_nwt" name="est_tag[nwt][]" value="" /></td>'

			+'<td><div class="wastage"></div><input type="hidden" name="est_tag[wastage][]" class="wastage_max_per" value="" /></td>'

			+'<td><div class="mc"></div></td>'

			+'<td><div class="cost"></div><input class="sales_value" type="hidden" name="est_tag[cost][]" value="" /><input class="tag_item_rate" type="hidden" name="est_tag[item_rate][]" value="" /><input class="caltype" type="hidden" name="est_tag[caltype][]" value="" /><input class="tgi_calculation" type="hidden" name="est_tag[tgi_calculation][]" value="" /><input class="tax_percentage" type="hidden" name="est_tag[tax_percentage][]" value="" /><input class="tax_group_id" type="hidden" name="est_tag[tax_percentage][]" value="" /><input class="stone_price" type="hidden" name="est_tag[stone_price][]" value="" /><input class="certification_price" type="hidden" name="est_tag[certification_price][]" value="" /><input class="id_mc_type" type="hidden" name="est_tag[id_mc_type][]" value="" /><input class="mc_value" type="hidden"   value="" /><input class="act_mc_value"  name="est_tag[mc][]" type="hidden" value="" /><input class="tax_price" type="hidden" name="est_tag[tax_price][]" value="" /><input type="hidden" class="market_rate_cost" name="est_tag[market_rate_cost][]"><input type="hidden" class="act_sales_value" ><input type="hidden" class="market_rate_tax" name="est_tag[market_rate_tax][]"></td><td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'

			+'</tr>';

	if($('#estimation_tag_details > tbody  > tr').length>0)

	{

	    $('#estimation_tag_details > tbody > tr:first').before(row);

	}else{

	    $('#estimation_tag_details tbody').append(row);

	}



}	

function create_new_empty_est_order_row()

{

	var row = "";

       row += '<tr>'

			+'<td><input class="orderno" type="text"  value="" placeholder="Enter Order No" required autocomplete="off"/><input class="order_id" type="hidden" name="order[orderno][]" value="" placeholder="Enter Order No" required /></td>'

			+'<td><div class="prodct_name"></div><input type="hidden" class="id_product" name="order[id_product][]" value="" /><input type="hidden" class="metal_type"></td>'

			+'<td><div class="design_name"></div><input type="hidden" class="design_no" name="order[design_no][]" value="" /></td>'

			+'<td><div class="purity"></div><input type="hidden" class="id_purity" name="order[id_purity][]" value="" /></td>'

			+'<td><div class="sizes"></div><input type="hidden" class="size" name="order[size][]" value="" /></td>'

			+'<td><div class="pieces"></div><input type="hidden" class="totalitems" name="order[totalitems][]" value="" /></td>'

			+'<td><input type="text" class="weight" name="order[gwt][]" value="" disabled/><input type="hidden" class="weight" name="order[weight][]" value=""/></td>'

			+'<td><div class="wast_percent"></div><input type="hidden" name="order[wast_percent][]" class="wastage_max_per" value="" /></td>'

			+'<td><div class="mc"></div><input type="hidden" name="order[mc][]" class="mc_value" value="" /></td>'

			+'<td><div class="cost"></div><input type="hidden" class="stn_amt"><input type="hidden" class="item_cost" name="order[item_cost][]" ><input class="tgi_calculation" type="hidden" name="order[tgi_calculation][]" value="" /><input class="tax_percentage" type="hidden" name="order[tax_percentage][]" value="" /><input class="tax_price" type="hidden" name="order[tax_price][]" value="" /><input class="market_rate_tax" type="hidden" name="order[market_rate_tax][]" value="" /><input class="market_rate_cost" type="hidden" name="order[market_rate_cost][]" value="" /><input class="tax_group" type="hidden"  value="" /></td><td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'

			+'</tr>';

	$('#estimation_order_details tbody').append(row);

}	

function create_new_empty_est_catalog_row()

{

	var purity = "<option value=''>-Select Purity-</option>";

	$.each(purities, function (pkey, pitem) {

		purity += "<option value='"+pitem.id_purity+"'>"+pitem.purity+"</option>";

	});	

	var row = "";

	var a = $("#catRow").val();

	var i = ++a;

	$("#catRow").val(i);

	row += '<tr id='+i+'>'

					+'<td><input type="checkbox" class="non_tag"><input type="hidden" class="is_non_tag" name="est_catalog[is_non_tag][]"><input type="hidden" class="cat_rate_field" name="est_catalog[rate_field][]"><input type="hidden" class="cat_market_rate_field" name="est_catalog[market_rate_field][]"></td>'

					+'<td><input type="text" class="form-control cat_product" name="est_catalog[product][]" value="" placeholder="Search Product" autocomplete="off" required style="width:80px;"/><input type="hidden" class="cat_pro_id" name="est_catalog[pro_id][]" value="" /><input type="hidden" class="tax_percentage" value=""><input type="hidden" class="tax_group_id" value=""><input type="hidden" class="tgi_calculation" value=""><input type="hidden" class="metal_type" value=""></td>'

					+'<td><input type="text" class="form-control cat_design" name="est_catalog[design][]" value="" placeholder="Search Design" autocomplete="off" required style="width:80px;"/><input type="hidden" class="cat_des_id" name="est_catalog[des_id][]" value="" /></td>'

					+'<td><select class="form-control cat_purity" name="est_catalog[purity][]" style="width:100px;">'+purity+'</select></td>'

					+'<td><input type="number" class="form-control cat_size" name="est_catalog[size][]" value="" style="width:80px;" placeholder="Enter Size"/></td>'

					+'<td><input type="number" class="form-control cat_pcs" name="est_catalog[pcs][]" value="1" autocomplete="off" style="width:80px;"  placeholder="Enter Pcs"/>Stock :<span class="blc_pcs" ></span><input type="hidden" class="tot_blc_pcs"></td>'

					+'<td><input type="number"  class="form-control cat_gwt" name="est_catalog[gwt][]" value="" autocomplete="off" style="width:80px;  placeholder="Enter GWT""/><span class="blc_gwt" ></span><input type="hidden" class="tot_blc_gwt"></td>'

					+'<td><input type="number" class="form-control cat_lwt" name="est_catalog[lwt][]" value="" autocomplete="off" style="width:80px;"  placeholder="Enter LWT"/></td>'

					+'<td><input type="number" class="form-control cat_nwt" name="est_catalog[nwt][]" value="" autocomplete="off" readonly style="width:80px;"></td>'

					+'<td><select class="form-control mc_type" style="width:80px;"><option value="1">Gram</option><option value="2">Piece</option></select><input type="hidden" value="1" name="est_catalog[id_mc_type][]" class="id_mc_type"></td>'

					+'<td><input type="number"  class="form-control cat_mc" name="est_catalog[mc][]" value="" style="width:80px;"  placeholder="Enter MC"/></td>'

					+'<td><input type="number" class="form-control cat_wastage" name="est_catalog[wastage][]" value="" style="width:80px;"  placeholder="Enter V.A in %"/></td>'

					+'<td><input type="number" class="form-control cat_taxable_amt" name="est_catalog[taxable_amt][]" readonly style="width:100px;"></td>'

					+'<td><input type="number"  class="form-control cat_tax_per" name="est_catalog[tax_per][]" value="" readonly style="width:80px;"/></td>'

					+'<td><input type="number"  class="form-control cat_tax_price" name="est_catalog[tax_price][]" value="" readonly style="width:80px;"/></td>'

					+'<td><a href="#" onClick="create_new_empty_est_cat_stone_item($(this).closest(\'tr\'));" class="btn btn-success"><i class="fa fa-plus"></i></a></td>'

					+'<td><input type="number" class="form-control cat_amt" name="est_catalog[amount][]" value="" readonly style="width:100px;" /><input type="hidden" class="cat_calculation_based_on" name="est_catalog[calculation_based_on][]" value="" /><input type="hidden" id="stone_details" class="stone_details" name="est_catalog[stone_details][]"><input type="hidden" id="material_details" class="material_details" name="est_catalog[material_details][]"><input type="hidden" value="0" class="stone_price" id="stone_price"><input type="hidden" value="0" class="material_price" id="material_price"><input type="hidden" class="market_rate_cost" name="est_catalog[market_rate_cost][]"><input type="hidden" class="market_rate_tax" name="est_catalog[market_rate_tax][]"></td>'

					+'<td><a href="#" onClick="removeCat_row($(this).closest(\'tr\'));" class="btn btn-danger"><i class="fa fa-trash"></i></a></td>'

				+'</tr>';

	$('#estimation_catalog_details tbody').append(row);

	$('#estimation_catalog_details > tbody').find('tr:last td:eq(0) .cat_product').focus();

	var disc_limit=$('#disc_limit').val();

	if(disc_limit=='')

	{

		$('#estimation_catalog_details > tbody').find('.cat_dis').attr('disabled','disabled');

	}

}	

function create_new_empty_est_custom_row()

{

	var purity = "<option value=''>-Select Purity-</option>";

	$.each(purities, function (pkey, pitem) {

		purity += "<option value='"+pitem.id_purity+"'>"+pitem.purity+"</option>";

	});	

	var row = "";

	var a = $("#cusRow").val();

	var i = ++a;

	$("#cusRow").val(i);

	row += '<tr id=cus'+i+'>'

	        +'<td><input class="form-control cus_tag_name" type="text" name="est_custom[tag_name][]" value="" placeholder="Enter tag code" required autocomplete="off" style="width:80px"/><input class="est_tag_id" type="hidden" name="est_custom[tag_id][]" value="" placeholder="Enter tag code" required /><input type="hidden" class="is_partial"  name="est_custom[is_partial][]" value="0"><input type="hidden" class="cus_rate_field"  name="est_custom[rate_field][]" ><input type="hidden" class="cus_market_rate_field"  name="est_custom[market_rate_field][]" ></td>'

			+'<td><input type="text" name="est_custom[product][]" value="" class="form-control cus_product" placeholder="Search Product" required style="width:80px;" autocomplete="off"/><input class="cus_product_id" type="hidden" name="est_custom[pro_id][]" value="" /><input type="hidden" class="tax_group_id" value=""><input type="hidden" class="tax_percentage" value=""><input type="hidden" class="tgi_calculation" value=""><input type="hidden" class="metal_type" value=""></td>'

		    +'<td><input type="text" class="form-control cus_design" name="est_custom[design][]" value="" placeholder="Search Design" required style="width:80px;" autocomplete="off"/><input type="hidden" class="cus_des_id" name="est_custom[des_id][]" value="" /></td>'

			+'<td><select class="form-control cus_purity" name="est_custom[purity][]" style="width:80px;">'+purity+'</select></td>'

			+'<td><input type="number" class="form-control cus_size" name="est_custom[size][]" placeholder="Enter Size" value="" style="width:80px;"/></td>'

			+'<td><input class="form-control cus_pcs" type="number" name="est_custom[pcs][]" placeholder="Enter Pcs" value="1" style="width:80px;"/></td>'

			+'<td><input type="number" class="form-control cus_gwt" name="est_custom[gwt][]"  placeholder="Enter GWT"style="width:80px;"/></td>'

			+'<td><input class="form-control cus_lwt" type="number" name="est_custom[lwt][]"  placeholder="Enter NWT" style="width:80px;"/></td>'

			+'<td><input type="number" class="form-control cus_nwt" name="est_custom[nwt][]"  readonly style="width:80px;"/></td>'

			+'<td><select class="form-control cus_mc_type" style="width:80px;"><option value="1">Gram</option><option value="2">Piece</option></select><input type="hidden" value="1" name="est_custom[id_mc_type][]" class="id_mc_type"></td>'

			+'<td><input type="number" class="form-control cus_mc" name="est_custom[mc][]" value="" placeholder="MC" style="width:80px;"/></td>'

			+'<td><input class="form-control cus_wastage" type="number" name="est_custom[wastage][]" placeholder="VA in %" value="" style="width:80px;"/></td>'
			
			+'<td><a href="#" onClick="create_new_empty_est_cus_charges_item($(this).closest(\'tr\'));" class="btn btn-success"><i class="fa fa-plus"></i></a><input type="hidden" class="charges_details" name="est_custom[charges_details][]"><input type="hidden" class="value_charge" name="est_custom[value_charge][]"></td>'

			+'<td><input type="number" class="form-control cus_taxable_amt" name="est_custom[taxable_amt][]" readonly="" value="" style="width:80px;"></td>'

			+'<td><input type="number" class="form-control cus_tax_price" name="est_custom[tax_price][]" value="" readonly="" style="width:80px;"></td>'

			+'<td><a href="#" onClick="create_new_empty_est_cus_stone_item($(this).closest(\'tr\'));" class="btn btn-success"><i class="fa fa-plus"></i></a></td>'

			+'<td><input class="form-control cus_amount" type="number" name="est_custom[amount][]" value="" readonly style="width:100px;"/><input type="hidden" class="cus_calculation_based_on" name="est_custom[calculation_based_on][]" value="" /><input type="hidden" id="stone_details" class="stone_details" name="est_custom[stone_details][]"><input type="hidden" id="material_details" class="material_details" name="est_custom[material_details][]"><input type="hidden" value="0" class="stone_price" id="stone_price"><input type="hidden" value="0" class="material_price" id="material_price"><input type="hidden" class="market_rate_cost" name="est_custom[market_rate_cost][]"><input type="hidden" class="market_rate_tax" name="est_custom[market_rate_tax][]"></td>'

			+'<td><a href="#" onClick="removeCat_row($(this).closest(\'tr\'));" class="btn btn-danger"><i class="fa fa-trash"></i></a></td>'

			+'</tr>';

	$('#estimation_custom_details tbody').append(row);

	$('#estimation_custom_details > tbody').find('tr:last td:eq(0) .cus_product').focus();

	var disc_limit=$('#disc_limit').val();

	if(disc_limit=='')

	{

		$('#estimation_custom_details > tbody').find('.cus_dis').attr('disabled','disabled');

	}

}	

function create_new_empty_est_oldmatel_row()

{

	var purity = "<option value=''>-Select Purity-</option>";

	$.each(purities, function (pkey, pitem) {

		purity += "<option value='"+pitem.id_purity+"'>"+pitem.purity+"</option>";

	});	

	var matelTupes = "<option value=''>- Select Metal-</option>";

	var allowed_old_met_pur=$('#allowed_old_met_pur').val();

	if(allowed_old_met_pur==1) //All Metal

	{

		matel_types=[{'id_metal':1,'metal':'Gold'},{'id_metal':2,'metal':'Silver'}];

	}

	else if(allowed_old_met_pur==2)//Only Gold

	{

		matel_types=[{'id_metal':1,'metal':'Gold'}];

	}

	else if(allowed_old_met_pur==3)//Only Silver

	{

		matel_types=[{'id_metal':2,'metal':'Silver'}];

	}

	if($('#estimation_catalog_details > tbody  > tr').length>0 || $('#estimation_custom_details > tbody  > tr').length>0 )

	{

		matel_types=[{'id_metal':2,'metal':'Silver'}];

	}

	$.each(matel_types, function (mkey, mitem) {

		matelTupes += "<option value='"+mitem.id_metal+"'>"+mitem.metal+"</option>";

	});	

	

	var metal_types = "<option value=''>- Select Metal Type-</option>";

	var old_metal_cat = "<option value=''>- Select -</option>";

	

	var row = "";

	var a = $("#oldMRow").val();

	var i = ++a;

	$("#oldMRow").val(i);	

	row += '<tr id=oldM'+i+'>'

				+'<td><select class="old_id_category"  name="est_oldmatel[id_category][]" value="">'+matelTupes+'</select></td>'
	            
				+'<td><select class="old_metal_type"  name="est_oldmatel[id_old_metal_type][]" value="">'+metal_types+'</select></td>'
				
				+'<td><select class="old_metal_category"  name="est_oldmatel[id_old_metal_category][]" value="">'+old_metal_cat+'</select></td>'

	            +'<td><input type="number" class="old_gwt" name="est_oldmatel[gwt][]" value="" style="width: 64px;"/></td>'

	            +'<td><input class="old_dwt" type="number" name="est_oldmatel[dwt][]" value="" style="width: 64px;"/></td>'

	            +'<td><input type="number" class="old_swt" name="est_oldmatel[swt][]" value="" style="width: 64px;"/></td>'

	            +'<td><input class="old_wastage" type="number" name="est_oldmatel[wastage][]" step="any" value="" style="width: 64px;"/></td>'

	            +'<td><input class="old_wastage_wt" type="number" name="est_oldmatel[wastage_wt][]" step="any" value="" style="width: 64px;"/></td>'

	            +'<td><input type="number" class="old_nwt" name="est_oldmatel[nwt][]" value="" readonly style="width: 64px;"/></td>'

	            +'<td><input type="number" class="old_rate" name="est_oldmatel[rate][]" value="" style="width: 64px;"/></td>'

	            +'<td><a href="#" onClick="create_new_empty_est_old_metal_stone($(this).closest(\'tr\'));" class="btn btn-success"><i class="fa fa-plus"></i></a></td>'

	            +'<td><select class="purpose"><option value="1">Cash</option><option value="2" selected>Exchange</option></select><input type="hidden" value="" name="est_oldmatel[id_purpose][]" class="id_purpose"></td>'

	            +'<td><input class="old_amount" type="number" name="est_oldmatel[amount][]" value="" /><input type="hidden" id="stone_details" class="stone_details" name="est_oldmatel[stone_details][]" value=""><input type="hidden" value="0" class="stone_price" id="stone_price"><input type="hidden" id="stone_wt" class="stone_wt" value="0"></td>'

	            +'<td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'

	       +'</tr>';

	$('#estimation_old_matel_details tbody').append(row);

	$('#estimation_old_matel_details > tbody').find('.old_id_category').focus();

	$('#estimation_old_matel_details > tbody').find('.purpose').select2();

	$('#estimation_old_matel_details > tbody').find('.old_id_category').select2();

	$('#estimation_old_matel_details > tbody').find('.purpose').select2({

	    placeholder: "Purpose",

	    allowClear: true

	});

/*	$('#estimation_old_matel_details > tbody').find('.old_id_category').select2({

	    placeholder: "Metal",

	    allowClear: true

	});*/

	

	var id_purpose=2;

	var old_id_category=$('#estimation_old_matel_details > tbody').find('.old_id_category').val();

	$('#estimation_old_matel_details > tbody').find('.purpose').select2("val",(id_purpose!='' && id_purpose>0?id_purpose:''));

	//$('#estimation_old_matel_details > tbody').find('.old_id_category').select2("val","");

}

function create_new_empty_est_stone_row()

{

	var stones_list = "<option value=''>-Select Stone-</option>";

	$.each(stones, function (pkey, pitem) {

		stones_list += "<option value='"+pitem.stone_id+"'>"+pitem.stone_name+"</option>";

	});	

	var row = "";

	row += '<tr><td><select class="stone_id" name="est_stones[stone_id][]">'+stones_list+'</select></td><td><input type="number" class="stone_pcs" name="est_stones[stone_pcs][]" value="" /></td><td><input class="stone_wt" type="number" name="est_stones[stone_wt][]" value="" /></td><td><input type="number" class="stone_price" name="est_stones[stone_price][]" value=""  /></td><td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';

	$('#estimation_stone_details tbody').append(row);

	$('#estimation_stone_details > tbody').find('tr:last td:eq(0) .stone_id').focus();

}

function create_new_empty_est_material_row()

{

	var material_list = "<option value=''> - Select Material - </option>";

	$.each(materials, function (pkey, pitem) {

		material_list += "<option value='"+pitem.material_id+"'>"+pitem.material_name+"</option>";

	});	

	var row = "";

	row += '<tr><td><select class="material_id" name="est_materials[material_id][]">'+material_list+'</select></td><td><input class="material_wt" type="number" name="est_materials[material_wt][]" value="" /></td><td><input type="number" class="material_price" name="est_materials[material_price][]" value=""  /></td><td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';

	$('#estimation_material_details tbody').append(row);

	$('#estimation_material_details > tbody').find('tr:last td:eq(0) .material_id').focus();

}	

function create_new_empty_est_voucher_row()

{

	var row = "";

	row += '<tr><td><input class="voucher_no" type="number" name="gift_voucher[voucher_no][]" value="" /></td><td></td><td><input type="number" class="gift_voucher_amt" name="gift_voucher[gift_voucher_amt][]" value=""  /></td><td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';

	$('#estimation_gift_voucher_details tbody').append(row);

	$('#estimation_gift_voucher_details > tbody').find('tr:last td:eq(0) .voucher_no').focus();

}

function create_new_empty_est_chit_row()

{

	var row = "";

	row += '<tr><td><input class="scheme_account_id" type="number" name="chit_uti[scheme_account_id][]" value="" /><input type="hidden" name="chit_uti[id_scheme_account][]" class="id_scheme_account" id="id_scheme_account"></td><td><input type="number" class="chit_amt" name="chit_uti[chit_amt][]" value=""  readonly /></td><td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';

	$('#estimation_chit_details tbody').append(row);

	$('#estimation_chit_details > tbody').find('tr:last td:eq(0) .scheme_account_id').focus();

}	

function validateTagDetailRow(){

	var row_validate = true;

	$('#estimation_tag_details > tbody  > tr').each(function(index, tr) {

		if($(this).find('td:first .est_tag_name').val() == ""){

			row_validate = false;

		}

	});

	return row_validate;

}



function validateOrderDetailRow(){

	var row_validate = true;

	$('#estimation_order_details > tbody  > tr').each(function(index, tr) {

		if($(this).find('td:first .orderno').val() == ""){

			row_validate = false;

		}

	});

	return row_validate;

}



function validateCatalogDetailRow(){

	var row_validate = true;

	$('#estimation_catalog_details > tbody  > tr').each(function(index, tr) {

		if($(this).find('.cat_pro_id').val() == "" || $(this).find('.cat_pcs').val() == "" || $(this).find('.cat_des_id').val() == "" || $(this).find('.cat_gwt').val() == "" || $(this).find('.cat_purity').val() == ""){

			row_validate = false;

		}

	});

	return row_validate;

}

function validateCustomDetailRow(){

	var row_validate = true;

	$('#estimation_custom_details > tbody  > tr').each(function(index, tr) {

		if($(this).find('.cus_product').val() == "" || $(this).find('.cus_gwt').val() == "" || $(this).find('.cus_purity').val() == "" || $(this).find('.cus_pcs').val() == "" ){

			row_validate = false;

		}

	});

	return row_validate;

}

function validateOldMatelDetailRow(){

	var row_validate = true;

	$('#estimation_old_matel_details > tbody  > tr').each(function(index, tr) {

		if($(this).find('.old_item_type').val() == "" || $(this).find('.old_id_category').val() == "" || $(this).find('.old_gwt').val() == "" || $(this).find('.old_metal_type').val() == "" ){

			row_validate = false;

		}

	});

	return row_validate;

}

function validateStoneDetailRow(){

	var row_validate = true;

	$('#estimation_stone_details > tbody  > tr').each(function(index, tr) {

		if($(this).find('td:first .stone_id').val() == "" || $(this).find('td:eq(1) .stone_pcs').val() == "" || $(this).find('td:eq(2) .stone_wt').val() == "" ){

			row_validate = false;

		}

	});

	return row_validate;

}

function validateMaterialDetailRow(){

	var row_validate = true;

	$('#estimation_material_details > tbody  > tr').each(function(index, tr) {

		if($(this).find('td:first .material_id').val() == "" || $(this).find('td:eq(1) .material_wt').val() == ""){

			row_validate = false;

		}

	});

	return row_validate;

}

function validateVoucherDetailRow(){

	var row_validate = true;

	$('#estimation_gift_voucher_details > tbody  > tr').each(function(index, tr) {

		if($(this).find('td:first .voucher_no').val() == "" || $(this).find('td:eq(2) .gift_voucher_amt').val() == ""){

			row_validate = false;

		}

	});

	return row_validate;

}

function validateChitDetailRow(){

	var row_validate = true;

	$('#estimation_chit_details > tbody  > tr').each(function(index, tr) {

		if($(this).find('td:first .scheme_account_id').val() == "" || $(this).find('td:eq(1) .chit_amt').val() == ""){

			row_validate = false;

		}

	});

	return row_validate;

}

function calculateSaleValue(){

    

    $('#estimation_catalog_details > tbody tr').each(function(idx, row)

    {

         curRow = $(this);

    	var disc_limit_type=$('#disc_limit_type').val();

    	var total_price = 0;

    	var total_tax_rate=0;

    	var market_total_tax_rate=0;

    	var total_tax_per = 0;

    

    	var base_value_tax = 0;

    	var arrived_value_tax = 0;

    	var base_value_amt=0;

    	var arrived_value_amt=0;

    	

    	var market_base_value_tax = 0;

    	var market_arrived_value_tax = 0;

    	var market_base_value_amt=0;

    	var market_arrived_value_amt=0;

    	

    	

    	var gross_wt = (isNaN(curRow.find('.cat_gwt').val()) || curRow.find('.cat_gwt').val() == '')  ? 0 : curRow.find('.cat_gwt').val();

    	var less_wt  = (isNaN(curRow.find('.cat_lwt').val()) || curRow.find('.cat_lwt').val() == '')  ? 0 : curRow.find('.cat_lwt').val();

    	var stone_price  = (isNaN(curRow.find('.stone_price').val()) || curRow.find('.stone_price').val() == '')  ? 0 : curRow.find('.stone_price').val();

    	var material_price  = (isNaN(curRow.find('.material_price').val()) || curRow.find('.material_price').val() == '')  ? 0 : curRow.find('.material_price').val();

    	var net_wt = (isNaN(curRow.find('.cat_nwt').val()) || curRow.find('.cat_nwt').val() == '')  ? 0 : curRow.find('.cat_nwt').val();

    	var calculation_type = (isNaN(curRow.find('.cat_calculation_based_on').val()) || curRow.find('.cat_calculation_based_on').val() == '')  ? 0 : curRow.find('.cat_calculation_based_on').val();

    	var discount = (isNaN(curRow.find('.cat_dis').val()) || curRow.find('.cat_dis').val() == '')  ? 0 : curRow.find('.cat_dis').val();

        var metal_type = (isNaN(curRow.find('.metal_type').val()) || curRow.find('.metal_type').val() == '')  ? 1 : curRow.find('.metal_type').val();

        var tax_group = curRow.find('.tax_group_id').val();

    

    

        rate_field=curRow.find('.cat_rate_field').val();

    

    	market_rate_field=curRow.find('.cat_market_rate_field').val();

    

    	if(rate_field!='')

    	{

    		rate_per_grm = (isNaN($('.'+rate_field).html()) ||$('.'+rate_field).html() == '')  ? 0 : $('.'+rate_field).html();

    	}

    

    	if(market_rate_field!='')

    	{

    		market_rate_per_grm = (isNaN($('.'+market_rate_field).html()) ||$('.'+market_rate_field).html() == '')  ? 0 : parseFloat($('.'+market_rate_field).html());

    	}

    	var retail_max_mc = (isNaN(curRow.find('.cat_mc').val()) || curRow.find('.cat_mc').val() == '')  ? 0 : curRow.find('.cat_mc').val();

    	var tot_wastage   = (isNaN(curRow.find('.cat_wastage').val()) || curRow.find('.cat_wastage').val() == '')  ? 0 : curRow.find('.cat_wastage').val();

    	/** 

    	*	Amount calculation based on settings (without discount and tax )

    	*   0 - Wastage on Gross weight And MC on Gross weight 

    	*   1 - Wastage on Net weight And MC on Net weight

    	*   2 - Wastage On Netwt And MC On Grwt

    	*   rate_with_mc = Metal Rate + Stone + OM + Wastage + MC

    	*/

    	if(calculation_type == 0){ 

    		var wast_wgt      = parseFloat(parseFloat(gross_wt) * parseFloat(tot_wastage/100)).toFixed(3);

    		var mc_type       =  parseFloat(curRow.find('.id_mc_type').val() == 1 ? parseFloat(retail_max_mc * gross_wt ) : parseFloat(retail_max_mc * curRow.find('.cat_pcs').val()));

    		// Metal Rate + Stone + OM + Wastage + MC

    		rate_with_mc = parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(material_price)).toFixed(3);

    		market_rate_with_mc = parseFloat(parseFloat(market_rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(material_price));

    	}

    	else if(calculation_type == 1){

    		var wast_wgt      = parseFloat(parseFloat(net_wt) * parseFloat(tot_wastage/100)).toFixed(3);

    		var mc_type       =  parseFloat(curRow.find('.id_mc_type').val() == 1 ? parseFloat(retail_max_mc * net_wt ) : parseFloat(retail_max_mc * curRow.find('.cat_pcs').val()));

    		// Metal Rate + Stone + OM + Wastage + MC

    		rate_with_mc = parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(material_price));

    		market_rate_with_mc = parseFloat(parseFloat(market_rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(material_price));

    	}

    	else if(calculation_type == 2){ 

    		var wast_wgt      = parseFloat(parseFloat(net_wt) * parseFloat(tot_wastage/100)).toFixed(3);

    		var mc_type       =  parseFloat(curRow.find('.id_mc_type').val() == 1 ? parseFloat(retail_max_mc * gross_wt ) : parseFloat(retail_max_mc * curRow.find('.cat_pcs').val()));

    		// Metal Rate + Stone + OM + Wastage + MC

    	    rate_with_mc = parseFloat((parseFloat(rate_per_grm) * (parseFloat(wast_wgt) + parseFloat(net_wt)))+ parseFloat(mc_type))+parseFloat(stone_price)+parseFloat(material_price);

    	    market_rate_with_mc = parseFloat((parseFloat(market_rate_per_grm) * (parseFloat(wast_wgt) + parseFloat(net_wt)))+ parseFloat(mc_type))+parseFloat(stone_price)+parseFloat(material_price);

    	}	

    	console.log('Calculation : '+calculation_type);

    	console.log('Wastage : '+wast_wgt);

    	console.log('MC : '+mc_type);

    	console.log('Rate with MC : '+rate_with_mc);

    	// Discount calculation based on employee eligiblity

    	if(disc_limit_type!='')

    	{

    		if(disc_limit_type==1)

    		{

    			rate_with_mc = parseFloat(rate_with_mc-discount);

    			market_rate_with_mc = parseFloat(market_rate_with_mc-discount);

    		}

    		else

    		{

    			rate_with_mc = parseFloat(rate_with_mc+parseFloat(rate_with_mc*parseFloat(discount/100)))

    			market_rate_with_mc = parseFloat(market_rate_with_mc+parseFloat(market_rate_with_mc*parseFloat(discount/100)))

    		}

    	}

    	console.log('Rate with MC + Discount : '+rate_with_mc);

    	// Tax Calculation

    	if(tax_details.length > 0){

    		// Tax Calculation

    		var base_value_tax	= parseFloat(calculate_base_value_tax(rate_with_mc,tax_group)).toFixed(2);

    		var base_value_amt	= parseFloat(parseFloat(rate_with_mc)+parseFloat(base_value_tax)).toFixed(2);

    		var arrived_value_tax= parseFloat(calculate_arrived_value_tax(base_value_amt,tax_group)).toFixed(2);

    		var arrived_value_amt= parseFloat(parseFloat(base_value_amt)+parseFloat(arrived_value_tax)).toFixed(2);

    		total_tax_rate	= parseFloat(parseFloat(base_value_tax)+parseFloat(arrived_value_tax)).toFixed(2);

    		

    		var market_base_value_tax	= parseFloat(calculate_base_value_tax(market_rate_with_mc,tax_group)).toFixed(2);

    		var market_base_value_amt	= parseFloat(parseFloat(market_rate_with_mc)+parseFloat(base_value_tax)).toFixed(2);

    		var market_arrived_value_tax= parseFloat(calculate_arrived_value_tax(base_value_amt,tax_group)).toFixed(2);

    		var market_arrived_value_amt= parseFloat(parseFloat(base_value_amt)+parseFloat(arrived_value_tax)).toFixed(2);

    		market_total_tax_rate	= parseFloat(parseFloat(base_value_tax)+parseFloat(arrived_value_tax)).toFixed(2);

    		

    	} 

    	

        total_price=parseFloat(parseFloat(rate_with_mc)+parseFloat(total_tax_rate)).toFixed(2);

      

        market_total_price=parseFloat(parseFloat(market_rate_with_mc)+parseFloat(market_total_tax_rate)).toFixed(2);

        

    	total_tax_per=parseFloat(parseFloat(base_value_tax)+parseFloat(arrived_value_tax)).toFixed(2);

    	

    	curRow.find('.cat_tax_price').val(total_tax_rate);

    	

    	curRow.find('.cat_amt').val((total_price));

    	

    	curRow.find('.market_rate_tax').val(market_total_tax_rate);

    	

    	curRow.find('.market_rate_cost').val((market_total_price));

    	

    	curRow.find('.cat_taxable_amt').val(parseFloat(rate_with_mc).toFixed(2));

    	

    	console.log('Taxable Amount : '+rate_with_mc);

    	

    	console.log('Amount : '+total_price);

    	

    	console.log('*************************');

    });

	calculate_purchase_details();

	calculate_sales_details();

}

function calculateCustomItemSaleValue(curRow)

{



    $('#estimation_custom_details > tbody tr').each(function(idx, row)

    {

        curRow = $(this);

        

        var disc_limit_type=$('#disc_limit_type').val();

        

    	var total_price = 0;

    	

    	var arrived_rate_tax = 0;

    	

    	var total_tax_per = 0;

    	

    	var total_tax_rate=0;

    	var market_total_tax_rate=0;

    	

    	var base_value_tax = 0;

    	var arrived_value_tax = 0;

    	var base_value_amt=0;

    	var arrived_value_amt=0;

    	

    	var market_base_value_tax = 0;

    	var market_arrived_value_tax = 0;

    	var market_base_value_amt=0;

    	var market_arrived_value_amt=0;

    	

    	var gross_wt = (isNaN(curRow.find('.cus_gwt').val()) || curRow.find('.cus_gwt').val() == '')  ? 0 : curRow.find('.cus_gwt').val();

    	var less_wt  = (isNaN(curRow.find('.cus_lwt').val()) || curRow.find('.cus_lwt').val() == '')  ? 0 : curRow.find('.cus_lwt').val();

    	var net_wt  = (isNaN(curRow.find('.cus_nwt').val()) || curRow.find('.cus_nwt').val() == '')  ? 0 : curRow.find('.cus_nwt').val();

    	var stone_price  = (isNaN(curRow.find('.stone_price').val()) || curRow.find('.stone_price').val() == '')  ? 0 : curRow.find('.stone_price').val();

    	var material_price  = (isNaN(curRow.find('.material_price').val()) || curRow.find('.material_price').val() == '')  ? 0 : curRow.find('.material_price').val();

    	var calculation_type = (isNaN(curRow.find('.cus_calculation_based_on').val()) || curRow.find('.cus_calculation_based_on').val() == '')  ? 0 : curRow.find('.cus_calculation_based_on').val();

    	var discount = (isNaN(curRow.find('.cus_dis').val()) || curRow.find('.cus_dis').val() == '')  ? 0 : curRow.find('.cus_dis').val();

    	var mjdmagoldrate_22ct = (isNaN($('.mjdmagoldrate_22ct').html()) || $('.mjdmagoldrate_22ct').html() == '')  ? 0 : parseFloat($('.mjdmagoldrate_22ct').html());

        var metal_type = (isNaN(curRow.find('.metal_type').val()) || curRow.find('.metal_type').val() == '')  ? 1 : curRow.find('.metal_type').val();

    	var tax_group = curRow.find('.tax_group_id').val();

    

    	

        rate_field=curRow.find('.cus_rate_field').val();

    

    	market_rate_field=curRow.find('.cus_market_rate_field').val();

    

    	if(rate_field!='')

    	{

    		rate_per_grm = (isNaN($('.'+rate_field).html()) ||$('.'+rate_field).html() == '')  ? 0 : $('.'+rate_field).html();

    	}

    

    	if(market_rate_field!='')

    	{

    		market_rate_per_grm = (isNaN($('.'+market_rate_field).html()) ||$('.'+market_rate_field).html() == '')  ? 0 : parseFloat($('.'+market_rate_field).html());

    	}

    	

    	var retail_max_mc = (isNaN(curRow.find('.cus_mc').val()) || curRow.find('.cus_mc').val() == '')  ? 0 : curRow.find('.cus_mc').val();

    	var tot_wastage = (isNaN(curRow.find('.cus_wastage').val()) || curRow.find('.cus_wastage').val() == '')  ? 0 : curRow.find('.cus_wastage').val();

    	if(calculation_type == 0){ 

    		var wast_wgt      = parseFloat(parseFloat(gross_wt) * parseFloat(tot_wastage/100)).toFixed(3);

    		var mc_type       =  parseFloat(curRow.find('.id_mc_type').val() == 1 ? parseFloat(retail_max_mc * gross_wt ) : parseFloat(retail_max_mc * curRow.find('.cus_pcs').val()));

    		// Metal Rate + Stone + OM + Wastage + MC

    		rate_with_mc = parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(material_price));

    		market_rate_with_mc = parseFloat(parseFloat(mjdmagoldrate_22ct * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(material_price));

    	}

    	else if(calculation_type == 1){

    		var wast_wgt      = parseFloat(parseFloat(net_wt) * parseFloat(tot_wastage/100)).toFixed(3);

    		var mc_type       =  parseFloat(curRow.find('.id_mc_type').val() == 1 ? parseFloat(retail_max_mc * net_wt ) : parseFloat(retail_max_mc * curRow.find('.cus_pcs').val()));

    		// Metal Rate + Stone + OM + Wastage + MC

    		rate_with_mc = parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(material_price));

    		market_rate_with_mc = parseFloat(parseFloat(mjdmagoldrate_22ct * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(material_price));

    	}

    	else if(calculation_type == 2){ 

    		var wast_wgt      = parseFloat(parseFloat(net_wt) * parseFloat(tot_wastage/100)).toFixed(3);

    		var mc_type       =  parseFloat(curRow.find('.id_mc_type').val() == 1 ? parseFloat(retail_max_mc * gross_wt ) : parseFloat(retail_max_mc * curRow.find('.cus_pcs').val()));

    		// Metal Rate + Stone + OM + Wastage + MC

    	    rate_with_mc = parseFloat((parseFloat(rate_per_grm) * (parseFloat(wast_wgt) + parseFloat(net_wt)))+ parseFloat(mc_type))+parseFloat(stone_price)+parseFloat(material_price);

    	    market_rate_with_mc = parseFloat((parseFloat(mjdmagoldrate_22ct) * (parseFloat(wast_wgt) + parseFloat(net_wt)))+ parseFloat(mc_type))+parseFloat(stone_price)+parseFloat(material_price);

    	}

    	if(disc_limit_type!='')

    	{

    		if(disc_limit_type==1)

    		{

    			rate_with_mc=parseFloat(rate_with_mc-discount);

    			market_rate_with_mc=parseFloat(market_rate_with_mc-discount);

    		}

    		else

    		{

    			rate_with_mc=parseFloat(rate_with_mc+parseFloat(rate_with_mc*parseFloat(discount/100)))

    			market_rate_with_mc=parseFloat(market_rate_with_mc+parseFloat(market_rate_with_mc*parseFloat(discount/100)))

    		}

    	}

    	if(tax_details.length > 0){

    		// Tax Calculation

    		var base_value_tax	= parseFloat(calculate_base_value_tax(rate_with_mc,tax_group)).toFixed(2);

    		var base_value_amt	= parseFloat(parseFloat(rate_with_mc)+parseFloat(base_value_tax)).toFixed(2);

    		var arrived_value_tax= parseFloat(calculate_arrived_value_tax(base_value_amt,tax_group)).toFixed(2);

    		var arrived_value_amt= parseFloat(parseFloat(base_value_amt)+parseFloat(arrived_value_tax)).toFixed(2);

    		total_tax_rate	= parseFloat(parseFloat(base_value_tax)+parseFloat(arrived_value_tax)).toFixed(2);

    		

    		var market_base_value_tax	= parseFloat(calculate_base_value_tax(market_rate_with_mc,tax_group)).toFixed(2);

    		var market_base_value_amt	= parseFloat(parseFloat(market_rate_with_mc)+parseFloat(base_value_tax)).toFixed(2);

    		var market_arrived_value_tax= parseFloat(calculate_arrived_value_tax(base_value_amt,tax_group)).toFixed(2);

    		var market_arrived_value_amt= parseFloat(parseFloat(base_value_amt)+parseFloat(arrived_value_tax)).toFixed(2);

    		market_total_tax_rate	= parseFloat(parseFloat(base_value_tax)+parseFloat(arrived_value_tax)).toFixed(2);

    		

    	} 

    	

        total_price=parseFloat(parseFloat(rate_with_mc)+parseFloat(total_tax_rate)).toFixed(2);

      

        market_total_price=parseFloat(parseFloat(market_rate_with_mc)+parseFloat(market_total_tax_rate)).toFixed(2);

      

    	total_tax_per=parseFloat(parseFloat(base_value_tax)+parseFloat(arrived_value_tax)).toFixed(2);

    	

    	curRow.find('.market_rate_tax').val(market_total_tax_rate);

    	curRow.find('.market_rate_cost').val(market_total_price);

    	curRow.find('.cus_tax_price').val(total_tax_rate);

    	//curRow.find('.cus_tax_per').val(total_tax_per);

    	curRow.find('.cus_taxable_amt').val(parseFloat(rate_with_mc).toFixed(2));

    	curRow.find('.cus_amount').val((total_price));

    	console.log('Calculation : '+calculation_type);

    	console.log('Wastage : '+wast_wgt);

    	console.log('MC : '+mc_type);

    	console.log('Rate with MC : '+rate_with_mc);

    	console.log('Rate per Gram : '+rate_per_grm);

    });

	calculate_purchase_details();

	calculate_sales_details();

}

function calculateOldMatelItemSaleValue(curRow){

	var gross_wt = (isNaN(curRow.find('.old_gwt').val()) || curRow.find('.old_gwt').val() == '')  ? 0 : curRow.find('.old_gwt').val();

	var dust_wt  = (isNaN(curRow.find('.old_dwt').val()) || curRow.find('.old_dwt').val() == '')  ? 0 : curRow.find('.old_dwt').val();

	var stone_wt  = (isNaN(curRow.find('.old_swt').val()) || curRow.find('.old_swt').val() == '')  ? 0 : curRow.find('.old_swt').val();

	var other_stone_wt  = (isNaN(curRow.find('.stone_wt').val()) || curRow.find('.stone_wt').val() == '')  ? 0 : curRow.find('.stone_wt').val();

	var other_stone_price  = (isNaN(curRow.find('.stone_price').val()) || curRow.find('.stone_price').val() == '')  ? 0 : curRow.find('.stone_price').val();

	var total_price = 0;

	var tax_rate = 0;

	var rate_per_grm = (isNaN(curRow.find('.old_rate').val()) || curRow.find('.old_rate').val() == '')  ? 0 : curRow.find('.old_rate').val();

	var cal_weight = (isNaN(curRow.find('.old_wastage_wt').val()) || curRow.find('.old_wastage_wt').val() == '')  ? 0 : curRow.find('.old_wastage_wt').val();

	var wastage = (isNaN(curRow.find('.old_wastage').val()) || curRow.find('.old_wastage').val() == '')  ? 0 : curRow.find('.old_wastage').val();

	var net_wt = (isNaN(curRow.find('.old_nwt').val()) || curRow.find('.old_nwt').val() == '')  ? 0 : curRow.find('.old_nwt').val();

	net_weight = parseFloat(parseFloat(gross_wt) -parseFloat(dust_wt)-parseFloat(stone_wt)-parseFloat(other_stone_wt)-parseFloat(cal_weight)).toFixed(3);

    curRow.find('.old_nwt').val(net_weight);

	total_price =parseFloat(parseFloat(net_wt)*parseFloat(rate_per_grm));

	total_price =Math.round(parseFloat(total_price)+parseFloat(other_stone_price));

	$(".summary_sale_amt").html(total_price);

	curRow.find('.old_amount').val(parseFloat(total_price).toFixed(2));

	calculate_purchase_details();

	calculate_sales_details();

		console.log('total_price:'+total_price);

		console.log('rate_per_grm:'+rate_per_grm);

		console.log('tot_wastage:'+wastage);

		console.log('cal_weight:'+cal_weight);

		console.log('net_wt:'+net_wt);

		console.log('other_stone_wt:'+other_stone_wt);

		console.log('--------------');

}

function get_stones(){

	$.ajax({		

	 	type: 'GET',		

	 	url : base_url + 'index.php/admin_ret_tagging/getStoneItems',

	 	dataType : 'json',		

	 	success  : function(data){

		 	stones = data;

	 	}	

	}); 

}

function getOtherChargesDetails(){

	$.ajax({		

	 	type: 'GET',		

	 	url : base_url + 'index.php/admin_ret_tagging/getOtherCharges',

	 	dataType : 'json',		

	 	success  : function(data){

		 	other_charges_details = data;

	 	}	

	}); 

}

function get_materials(){

	$.ajax({		

	 	type: 'GET',		

	 	url : base_url + 'index.php/admin_ret_tagging/getAvailableMaterials',

	 	dataType : 'json',		

	 	success  : function(data){

		 	materials = data;

	 	}	

	}); 

}

function calculate_purchase_details(){

	var purchase_weight = 0;

	var purchase_rate 	= 0;

	var stone_rate 	= 0;

	var material_rate 	= 0;

	var purchase_piece 	= 0;

    $('#estimation_tag_details > tbody  > tr').each(function(index, tr) {

		if($(this).find('.est_tag_name').val() != "" && $(this).find('.sales_value').val() != ""){

			purchase_weight += parseFloat((isNaN($(this).find('.gwt').val()) || $(this).find('.gwt').val() == '')  ? 0 : $(this).find('.gwt').val());

			purchase_rate += parseFloat((isNaN($(this).find('.sales_value').val()) || $(this).find('.sales_value').val() == '')  ? 0 : $(this).find('.sales_value').val());

			purchase_piece+= parseFloat((isNaN($(this).find('.pieces').html()) || $(this).find('.pieces').html()=='') ? 0 : $(this).find('.pieces').html());

		}

	});

	

	$('#estimation_order_details > tbody  > tr').each(function(index, tr) {

		if($(this).find('.order_id').val() != ""){

			purchase_weight += parseFloat((isNaN($(this).find('.weight').val()) || $(this).find('.weight').val() == '')  ? 0 : $(this).find('.weight').val());

			purchase_rate += parseFloat((isNaN($(this).find('.item_cost').val()) || $(this).find('.item_cost').val() == '')  ? 0 : $(this).find('.item_cost').val());

		}

	});

	

	$('#estimation_catalog_details > tbody  > tr').each(function(index, tr) {

		if($(this).find('td:first .cat_product').val() != "" && $(this).find('.cat_gwt').val() != "" && $(this).find('.cat_amt').val() != "")

		{

			purchase_weight += parseFloat((isNaN($(this).find('.cat_gwt').val()) || $(this).find('.cat_gwt').val() == '')  ? 0 : $(this).find('.cat_gwt').val());

			purchase_rate += parseFloat((isNaN($(this).find('.cat_amt').val()) || $(this).find('.cat_amt').val() == '')  ? 0 : $(this).find('.cat_amt').val());

			var id_mc_type = $(this).find('.id_mc_type').val();

			//$(this).find('.mc_type ').select2();

			/*$(this).find('.mc_type ').select2({

			    placeholder: "Type",

			    allowClear: true

			});*/

			//$(this).find('.mc_type ').select2("val",(id_mc_type!='' && id_mc_type>0?id_mc_type:''));

		}

	});

	$('#estimation_custom_details > tbody  > tr').each(function(index, tr) {

		if($(this).find('td:first .cus_product_id').val() != "" && $(this).find('.cus_gwt').val() != "" && $(this).find('.cus_amount').val() != ""){

			purchase_weight += parseFloat((isNaN($(this).find('.cus_gwt').val()) || $(this).find('.cus_gwt').val() == '')  ? 0 : $(this).find('.cus_gwt').val());

			purchase_rate += parseFloat((isNaN($(this).find('.cus_amount').val()) || $(this).find('.cus_amount').val() == '')  ? 0 : $(this).find('.cus_amount').val());

			var id_mc_type = $(this).find('.id_mc_type').val();

			/*$(this).find('.cus_mc_type ').select2();

			$(this).find('.cus_mc_type ').select2({

			    placeholder: "Type",

			    allowClear: true

			});

			$(this).find('.cus_mc_type ').select2("val",(id_mc_type!='' && id_mc_type>0?id_mc_type:''));*/

		}

	});

	/*$('#estimation_stone_details > tbody  > tr').each(function(index, tr) {

		if($(this).find('td:first .stone_id').val() != "" && $(this).find('td:eq(1) .stone_pcs').val() != "" && $(this).find('td:eq(3) .stone_price').val() != ""){

			stone_rate += parseFloat((isNaN($(this).find('td:eq(3) .stone_price').val()) || $(this).find('td:eq(3) .stone_price').val() == '')  ? 0 : $(this).find('td:eq(3) .stone_price').val());

		}

	});*/

	$('#estimation_material_details > tbody  > tr').each(function(index, tr) {

		if($(this).find('td:first .material_id').val() != "" && $(this).find('td:eq(2) .material_price').val() != ""){

			material_rate += parseFloat((isNaN($(this).find('td:eq(2) .material_price').val()) || $(this).find('td:eq(2) .material_price').val() == '')  ? 0 : $(this).find('td:eq(2) .material_price').val());

		}

	});

	//$(".summary_pur_weight").html(parseFloat(purchase_weight).toFixed(3));

	$(".summary_pur_weight").html(parseFloat(purchase_weight).toFixed(3) + "/" + purchase_piece);

	$(".summary_pur_amt").html(parseFloat(purchase_rate).toFixed(2));

	$(".summary_stone_amt").html(stone_rate);

	$(".summary_material_amt").html(material_rate);

	calculateFinalCost();

}

function calculate_sales_details(){

	var sale_weight = 0;

	var sale_rate 	= 0;

	var gift_voucher = 0;

	var chit_amt 	= 0;

	$('#estimation_old_matel_details > tbody  > tr').each(function(index, tr) {

		if($(this).find('td:first .old_item_type').val() != "" && $(this).find('.old_gwt').val() != "" && $(this).find('.old_nwt').val() != "" && $(this).find('.old_amount').val() != ""){

			sale_weight += parseFloat((isNaN($(this).find('.old_gwt').val()) || $(this).find('.old_gwt').val() == '')  ? 0 : $(this).find('.old_gwt').val());

			sale_rate += parseFloat((isNaN($(this).find('.old_amount').val()) || $(this).find('.old_amount').val() == '')  ? 0 : $(this).find('.old_amount').val());

			

		}

	});

	$('#estimation_gift_voucher_details > tbody  > tr').each(function(index, tr) {

		if($(this).find('td:first .voucher_no').val() != "" && $(this).find('td:eq(2) .gift_voucher_amt').val() != ""){

			gift_voucher += parseFloat((isNaN($(this).find('td:eq(2) .gift_voucher_amt').val()) || $(this).find('td:eq(2) .gift_voucher_amt').val() == '')  ? 0 : $(this).find('td:eq(2) .gift_voucher_amt').val());

		}

	});

	$('#estimation_chit_details > tbody  > tr').each(function(index, tr) {

		if($(this).find('td:first .id_scheme_account').val() != "" && $(this).find('td:eq(1) .chit_amt').val() != ""){

			chit_amt += parseFloat((isNaN($(this).find('td:eq(1) .chit_amt').val()) || $(this).find('td:eq(1) .chit_amt').val() == '')  ? 0 : $(this).find('td:eq(1) .chit_amt').val());

		}

	});

	$(".summary_sale_weight").html(parseFloat(sale_weight).toFixed(3));

	$(".summary_sale_amt").html(parseFloat(sale_rate).toFixed(2));

//	$(".summary_gift_voucher_amt").html(gift_voucher);

	//$(".summary_gift_amt").val(gift_voucher);

	//$(".summary_chit_amt").html(chit_amt);

	calculateFinalCost();

}

function calculateFinalCost(){

	var purchase_amt 	= 0;

	var sales_amt 	 	= 0;

	var stone_amt 	 	= 0;

	var material_amt 	= 0;

	var gift_voucher_amt= 0;

	var chit_amt 		= 0;

	var discount 		= 0;

	var adv_paid_amt    =0;

	purchase_amt		= parseFloat((isNaN($('.summary_pur_amt').html()) || $('.summary_pur_amt').html() == '')  ? 0 : $('.summary_pur_amt').html());

	sales_amt			= parseFloat((isNaN($('.summary_sale_amt').html()) || $('.summary_sale_amt').html() == '')  ? 0 : $('.summary_sale_amt').html());

	stone_amt			= parseFloat((isNaN($('.summary_stone_amt').html()) || $('.summary_stone_amt').html() == '')  ? 0 : $('.summary_stone_amt').html());

	adv_paid_amt 	    = ($('.summary_adv_paid_amt').html()!='' || isNaN($('.summary_adv_paid_amt').html()) ? $('.summary_adv_paid_amt').html() :0);

	

	tot_purchase 	= parseFloat(purchase_amt + stone_amt + material_amt);

	tot_sale 		= parseFloat(sales_amt + gift_voucher_amt + chit_amt);

	tot_cost 		= parseFloat(tot_purchase - tot_sale - discount -adv_paid_amt).toFixed(2)

	$(".total_cost").val(tot_cost);

}

function get_estimation_list(from_date,to_date)

{

	my_Date = new Date();

	$("div.overlay").css("display", "block"); 

	$.ajax({

		 url:base_url+"index.php/admin_ret_estimation/estimation/ajax?nocache=" + my_Date.getUTCSeconds(),

		 dataType:"JSON",

		 type:"POST",

		 data:{'id_branch':(($('#branch_select').val()!='' && $('#branch_select').val()!='' && $('#branch_select').val()!=undefined) ? $('#branch_select').val():$('#branch_filter').val()),'from_date':from_date,'to_date':to_date},

		 success:function(data){

   			set_estimation_list(data);

   			 $("div.overlay").css("display", "none"); 

		  },

		  error:function(error)  

		  {

			 $("div.overlay").css("display", "none"); 

		  }	 

	});

}

/*function set_estimation_list(data)	

{

   $("div.overlay").css("display", "none"); 

   var estimation = data.list;

   var access = data.access;

   var oTable = $('#estimation_list').DataTable();

   $("#total_estimation").text(estimation.length);

    if(access.add == '0')

	 {

		$('#add_estimation').attr('disabled','disabled');

	 }

	 oTable.clear().draw();

   	 if (estimation!= null && estimation.length > 0)

	 {

	 	oTable = $('#estimation_list').dataTable({

			"bDestroy": true,

			"bInfo": true,

			"bFilter": true, 

			"bSort": true,

			"order": [[ 0, "desc" ]],

			"dom": 'lBfrtip',

			"buttons" : ['excel','print'],

			"tableTools": { "buttons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },

			"aaData": estimation,

			"aoColumns": [{ "mDataProp": "estimation_id" },

						{ "mDataProp": "estimation_datetime" },		

						{ "mDataProp": "firstname" },		

						{ "mDataProp": "esti_for" },		

						{ "mDataProp": "total_cost" },		

						{ "mDataProp": "item_type" },		

						{ "mDataProp": function ( row, type, val, meta ) {

							 id= row.estimation_id

							 edit_url=(access.edit=='1' ? base_url+'index.php/admin_ret_estimation/estimation/edit/'+id : '#' );

							 print_url=base_url+'index.php/admin_ret_estimation/generate_invoice/'+id;

							 delete_url=(access.delete=='1' ? base_url+'index.php/admin_ret_estimation/estimation/delete/'+id : '#' );

							 delete_confirm= (access.delete=='1' ?'#confirm-delete':'');

							 action_content='<a href="'+edit_url+'" class="btn btn-primary btn-edit"><i class="fa fa-edit" ></i></a><a href="#" class="btn btn-danger btn-del" data-href='+delete_url+' data-toggle="modal" data-target="#confirm-delete" ><i class="fa fa-trash"></i></a><a href="'+print_url+'" target="_blank"  class="btn btn-info btn-print" data-toggle="tooltip" title="Customer Copy"><i class="fa fa-print" ></i></a>';

							return action_content;

						}

					}] 

		});	

	}

}*/

function set_estimation_list(data)	

{

   $("div.overlay").css("display", "none"); 

   var estimation = data.list;

   var access = data.access;

   var oTable = $('#estimation_list').DataTable();

   $("#total_estimation").text(estimation.length);

    if(access.add == '0')

	 {

		$('#add_estimation').attr('disabled','disabled');

	 }

	 oTable.clear().draw();

   	 if (estimation!= null && estimation.length > 0)

	 {

	 	oTable = $('#estimation_list').dataTable({

			"bDestroy": true,

			"bInfo": true,

			"bFilter": true, 

			"bSort": true,

			"order": [[ 0, "desc" ]],

			"dom": 'lBfrtip',

			"buttons" : ['excel','print'],

			"tableTools": { "buttons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },

			"aaData": estimation,

			"aoColumns": [{ "mDataProp": "esti_no" },

						{ "mDataProp": "estimation_datetime" },		

						{ "mDataProp": function ( row, type, val, meta ){

							return row.firstname;

						},

						},

						{ "mDataProp": "mobile" },		

						{ "mDataProp": "total_cost" },

						{ "mDataProp": function ( row, type, val, meta ){

						    if($('#id_branch').val()==0 || $('#id_branch').val()=='')

						    {

						         return row.product_name;

						    }else{

						        return '-';

						    }

                            

						},

						},

						{ "mDataProp": function ( row, type, val, meta ){

						    if(row.bill_no!=null && ($('#id_branch').val()==0 || $('#id_branch').val()=='' ))

						    {

						        var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+row.bill_id;

                                return '<a href='+url+' target="_blank">'+row.bill_no+'</a>';

						    }else{

						        return '-';

						    }

                            

						},

						},

						{ "mDataProp": function ( row, type, val, meta ) {

							 id= row.estimation_id

							 edit_url=(access.edit=='1' ? base_url+'index.php/admin_ret_estimation/estimation/edit/'+id : '#' );

							 print_url=base_url+'index.php/admin_ret_estimation/generate_invoice/'+id;

							 brief_copy=base_url+'index.php/admin_ret_estimation/generate_brief_copy/'+id;

							 delete_url=(access.delete=='1' ? base_url+'index.php/admin_ret_estimation/estimation/delete/'+id : '#' );

							 delete_confirm= (access.delete=='1' ?'#confirm-delete':'');

							 action_content='<a href="'+edit_url+'" class="btn btn-primary btn-edit"><i class="fa fa-edit" ></i></a><a href="'+print_url+'" target="_blank" class="btn btn-info btn-print" data-toggle="tooltip" title="Customer Copy"><i class="fa fa-print" ></i></a><a href="#" class="btn btn-danger btn-del" data-href='+delete_url+' data-toggle="modal" data-target="#confirm-delete" ><i class="fa fa-trash"></i></a>';

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

		 	$.each(data, function (key, item) {				  			   		

	    	 	$("#tag_lot_received_id").append(						

	    	 	$("<option></option>")						

	    	 	.attr("value", item.lot_no)

	    	 	.text(item.lot_no)						  					

	    	 	);	 

	     	});

	     	$("#tag_lot_received_id").select2("val",(id!='' && id>0?id:''));	 

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

function get_tag_purities(){

	$.ajax({		

	 	type: 'GET',		

	 	url : base_url + 'index.php/admin_ret_catalog/purity/active_purities',		

	 	dataType : 'json',		

	 	success  : function(data){

		 	purities = data;

	 	}	

	}); 

}

function get_tag_matels(){

	$.ajax({		

	 	type: 'GET',		

	 	url : base_url + 'index.php/admin_ret_estimation/getMetalTypes',		

	 	dataType : 'json',		

	 	success  : function(data){

		 	matel_types = data;

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

function getDesignPurityDetails(designId)

{

	my_Date = new Date();

	$.ajax({

        url: base_url+'index.php/admin_ret_tagging/getDesignPurityByDesignId/?nocache=' + my_Date.getUTCSeconds(),             

        dataType: "json", 

        method: "POST", 

        data: {'designId' : designId}, 

        success: function (data) { 

			var id =  $('#purity').val();

			if(data.length > 0){

				$("#select_purity").html('');

				$.each(data, function (key, item) {		   		

					$("#select_purity").append(						

					$("<option></option>")						

					.attr("value", item.des_pur_id)

					.text(item.purity)				  					

					);	 

				});		

			}else{

				$("#select_purity").html('');

			}				

	     	$("#select_purity").select2("val",(id !='' && id > 0 ? id : ''));	 

	     	$(".overlay").css("display", "none");	

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

			var html = '';

			$.each(data, function(key, item){

				html += '<tr><td><input type="hidden" name="tagmaterials[material_id][]" value="'+item.material_id+'" required />'+item.material_name+'</td><td><div class="input-group"> <input class="form-control" type="number" step="any" name="tagmaterials[weight][]" value="" required /></div></td><td><input type="hidden" name="tagmaterials[uom_id][]" value="'+item.uom_id+'" />'+item.uom_short_code+'</td><td><div class="input-group"> <input class="form-control" type="number" step="any" name="tagmaterials[amount][]" value="" required /></div></td><td></td></tr>';

			});

			$('#tagging_material_details tbody').append(html);

        }

     });

}

//26.03.2020

$(document).on('change',".partial",function()

{

	if($(this).is(":checked"))

	{

	    var act_mc_value=$(this).closest('tr').find('.act_mc_value').val();

		$(this).closest('tr').find('.is_partial').val(1);

		$(this).closest('tr').find('.gwt').prop('disabled',false);

		$(this).closest('tr').find('.lwt').prop('disabled',false);

		$(this).closest('tr').find('.mc_value').val(act_mc_value);

	}

	else

	{

		var act_gwt=$(this).closest('tr').find('.act_gwt').val();

		var act_mc_value=$(this).closest('tr').find('.act_mc_value').val();

		var less_wt=$(this).closest('tr').find('.lwt').val();

		$(this).closest('tr').find('.is_partial').val(0);

		$(this).closest('tr').find('.gwt').prop('disabled',true);

		$(this).closest('tr').find('.lwt').prop('disabled',true);

		$(this).closest('tr').find('.gwt').val(act_gwt);

		$(this).closest('tr').find('.cur_gwt').val(act_gwt);

		$(this).closest('tr').find('.mc_value').val(act_mc_value);

		$(this).closest('tr').find('.mc').html(act_mc_value);

		$(this).closest('tr').find('.nwt').html(parseFloat(act_gwt-less_wt).toFixed(3));

		$(this).closest('tr').find('.tot_nwt').val(parseFloat(act_gwt-less_wt).toFixed(3));

	}

	calculatetag_SaleValue();

});

$(document).on('change',".non_tag",function()

{

	if($(this).is(":checked"))

	{

		$(this).closest('tr').find('.is_non_tag').val(1);

		$(this).closest('tr').find('.lot_no').prop('disabled',false);

	}

	else

	{

		$(this).closest('tr').find('.is_non_tag').val(0);

		$(this).closest('tr').find('.lot_no').prop('disabled',true);

	}

});

$(document).on('change',".gwt",function()

{ 

		var act_gwt=parseFloat($(this).closest('tr').find('.act_gwt').val());

		var gwt=parseFloat($(this).closest('tr').find('.gwt').val());

		



		if(act_gwt>gwt)

		{

			var less_wt=$(this).closest('tr').find('.lwt').val();

			var net_wt=parseFloat(gwt-(isNaN(less_wt) ? 0 : less_wt)).toFixed(4);

			$(this).closest('tr').find('.nwt').html(net_wt);

			$(this).closest('tr').find(".tot_nwt").val(net_wt);

			$(this).closest('tr').find(".cur_gwt").val(gwt);

			var row = $(this).closest('tr'); 

			

		}

		else{

			alert('Maximum Gross weight Exceed');

			var less_wt=$(this).closest('tr').find('.lwt').val();

			var net_wt=parseFloat(gwt-(isNaN(less_wt) ? 0 : less_wt)).toFixed(4);

			$(this).closest('tr').find('.nwt').html(net_wt);

			$(this).closest('tr').find(".cur_gwt").val(parseFloat(act_gwt).toFixed(3));

			$(this).closest('tr').find(".gwt").val(parseFloat(act_gwt).toFixed(3));

			$(this).closest('tr').find(".gwt").focus();

		}

		calculatetag_SaleValue();

});

$(document).on('change',".lwt",function()

{ 

	var gwt=$(this).closest('tr').find('.gwt').val();

	var less_wt=parseInt($(this).closest('tr').find('.lwt').val());

	if(less_wt<gwt)

	{

			var net_wt=parseFloat(gwt-less_wt).toFixed(4);

			$(this).closest('tr').find('.nwt').html(net_wt);

			$(this).closest('tr').find(".tot_nwt").val(net_wt);

			var row = $(this).closest('tr'); 

			calculatetag_SaleValue();

	}

	else

	{

		$(this).closest('tr').find('.lwt').focus();

		$(this).closest('tr').find('.lwt').val('');

	}

});

function calculatetag_SaleValue(){

    $('#estimation_tag_details > tbody tr').each(function(idx, row){

    curRow = $(this);

    if(curRow.find('.est_tag_id').val()!='')

    {

    var mc_type =0;

    var wast_wgt=0;

    var rate_with_mc=0;

    var market_rate_with_mc=0;

    

	var total_price = 0;

	var stone_price = 0;

	var material_price = 0;

	var tax_rate = 0;

	

	var total_tax_rate=0;

	var market_total_tax_rate=0;

	

	var base_value_tax = 0;

	var arrived_value_tax = 0;

	var base_value_amt=0;

	var arrived_value_amt=0;

	

	var market_base_value_tax = 0;

	var market_arrived_value_tax = 0;

	var market_base_value_amt=0;

	var market_arrived_value_amt=0;

	

	var gross_wt = (isNaN(curRow.find('.gwt').val()) || curRow.find('.gwt').val() == '')  ? 0 : curRow.find('.gwt').val();

	var less_wt =  (isNaN(curRow.find('.lwt').val()) || curRow.find('.lwt').val() == '')  ? 0 : curRow.find('.lwt').val();

	var net_wt =   (isNaN(curRow.find('.tot_nwt').val()) || curRow.find('.tot_nwt').val() == '')  ? 0 : parseFloat(curRow.find('.tot_nwt').val()).toFixed(2);

	var calculation_type =   (isNaN(curRow.find('.caltype').val()) || curRow.find('.caltype').val() == '')  ? 0 : curRow.find('.caltype').val();

	var piece =   (isNaN(curRow.find('.piece').val()) || curRow.find('.piece').val() == '')  ? 1 : curRow.find('.piece').val();

    var metal_type = (isNaN(curRow.find('.metal_type').val()) || curRow.find('.metal_type').val() == '')  ? 1 : curRow.find('.metal_type').val();

    var tax_group = curRow.find('.tax_group_id').val();





    rate_field=curRow.find('.rate_field').val();



	market_rate_field=curRow.find('.market_rate_field').val();



	if(rate_field!='')

	{

		rate_per_grm = (isNaN($('.'+rate_field).html()) ||$('.'+rate_field).html() == '')  ? 0 : $('.'+rate_field).html();

	}



	if(market_rate_field!='')

	{

		market_rate_per_grm = (isNaN($('.'+market_rate_field).html()) ||$('.'+market_rate_field).html() == '')  ? 0 : parseFloat($('.'+market_rate_field).html());

	}

	

    /*	if(metal_type==1)

	{

		var rate_per_grm = (isNaN($('.per-grm-sale-value').html()) || $('.per-grm-sale-value').html() == '')  ? 0 : parseFloat($('.per-grm-sale-value').html());

	}else{

		var rate_per_grm = (isNaN($('.silver_per-grm-sale-value').html()) || $('.silver_per-grm-sale-value').html() == '')  ? 0 : parseFloat($('.silver_per-grm-sale-value').html());

	}

	

	

	if(metal_type==1)

	{

		var market_rate_per_grm = (isNaN($('.mjdmagoldrate_22ct').html()) || $('.mjdmagoldrate_22ct').html() == '')  ? 0 : parseFloat($('.mjdmagoldrate_22ct').html());

	}else{

		var market_rate_per_grm = (isNaN($('.mjdmasilverrate_1gm').html()) || $('.mjdmasilverrate_1gm').html() == '')  ? 0 : parseFloat($('.mjdmasilverrate_1gm').html());

	}*/

	

	

	var tot_wastage   = (isNaN(curRow.find('.wastage_max_per').val()) || curRow.find('.wastage_max_per').val() == '')  ? 0 : curRow.find('.wastage_max_per').val();

	var retail_max_mc = (isNaN(curRow.find('.act_mc_value').val()) || curRow.find('.act_mc_value').val() == '')  ? 0 : curRow.find('.act_mc_value').val();

	var stone_price = (isNaN(curRow.find('.stone_price').val()) || curRow.find('.stone_price').val() == '')  ? 0 : curRow.find('.stone_price').val();

	var certification_price = (isNaN(curRow.find('.certification_price').val()) || curRow.find('.certification_price').val() == '')  ? 0 : curRow.find('.certification_price').val();

	/** 

	*	Amount calculation based on settings (without discount and tax )

	*   0 - Wastage on Gross weight And MC on Gross weight 

	*   1 - Wastage on Net weight And MC on Net weight

	*   2 - Wastage On Netwt And MC On Grwt

	*   rate_with_mc = Metal Rate + Stone + OM + Wastage + MC

	*/

	console.log('gross_wt:'+gross_wt);

	console.log('net_wt:'+net_wt);

	console.log('retail_max_mc:'+retail_max_mc);

	if(calculation_type == 0){ 

		wast_wgt      = parseFloat(parseFloat(gross_wt) * parseFloat(tot_wastage/100)).toFixed(3);

		if(curRow.find('.id_mc_type').val() != 3){

    		mc_type       =  parseFloat(curRow.find('.id_mc_type').val() == 2 ? parseFloat(retail_max_mc * gross_wt ) : parseFloat(retail_max_mc * piece));

    		// Metal Rate + Stone + OM + Wastage + MC

    		rate_with_mc = parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(certification_price));

    		market_rate_with_mc = parseFloat(parseFloat(market_rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(certification_price));

		}else{
            mc_type=retail_max_mc;
            
		    rate_with_mc = parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(((parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))))*(mc_type/100)))+parseFloat(stone_price)+parseFloat(certification_price));

    		market_rate_with_mc = parseFloat(parseFloat(market_rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(((parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))))*(mc_type/100)))+parseFloat(stone_price)+parseFloat(certification_price));

		}

	}

	else if(calculation_type == 1){

		var wast_wgt      = parseFloat(parseFloat(net_wt) * parseFloat(tot_wastage/100)).toFixed(3);

		if(curRow.find('.id_mc_type').val() != 3){

    		var mc_type       =  parseFloat(curRow.find('.id_mc_type').val() == 2 ? parseFloat(retail_max_mc * net_wt ) : parseFloat(retail_max_mc * piece));

    		// Metal Rate + Stone + OM + Wastage + MC

    		rate_with_mc = parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(certification_price));

    		market_rate_with_mc = parseFloat(parseFloat(market_rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(certification_price));

		}else{
            
             mc_type=retail_max_mc;
             
		    rate_with_mc = parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(((parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))))*(mc_type/100)))+parseFloat(stone_price)+parseFloat(certification_price));

    		market_rate_with_mc = parseFloat(parseFloat(market_rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(((parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))))*(mc_type/100)))+parseFloat(stone_price)+parseFloat(certification_price));

		}

	}

	else if(calculation_type == 2){ 

		var wast_wgt      = parseFloat(parseFloat(net_wt) * parseFloat(tot_wastage/100)).toFixed(3);

		if(curRow.find('.id_mc_type').val() != 3){

    		var mc_type       =  parseFloat(curRow.find('.id_mc_type').val() == 2 ? parseFloat(retail_max_mc * gross_wt ) : parseFloat(retail_max_mc * piece));

    		// Metal Rate + Stone + OM + Wastage + MC

    	    rate_with_mc = parseFloat((parseFloat(rate_per_grm) * (parseFloat(wast_wgt) + parseFloat(net_wt)))+ parseFloat(mc_type))+parseFloat(stone_price)+parseFloat(certification_price);

    	    market_rate_with_mc = parseFloat((parseFloat(market_rate_per_grm) * (parseFloat(wast_wgt) + parseFloat(net_wt)))+ parseFloat(mc_type))+parseFloat(stone_price)+parseFloat(certification_price);

		}else{
             mc_type=retail_max_mc;
             
		    rate_with_mc = parseFloat((parseFloat(rate_per_grm) * (parseFloat(wast_wgt) + parseFloat(net_wt)))+ parseFloat(((parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))))*(mc_type/100)))+parseFloat(stone_price)+parseFloat(certification_price));

    	    market_rate_with_mc = parseFloat((parseFloat(market_rate_per_grm) * (parseFloat(wast_wgt) + parseFloat(net_wt)))+ parseFloat(((parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))))*(mc_type/100)))+parseFloat(stone_price)+parseFloat(certification_price));

		}

	} 

	else if(calculation_type == 3 || calculation_type == 4){ 

		rate_with_mc  = parseFloat((isNaN(curRow.find('.act_sales_value').val()) || curRow.find('.act_sales_value').val() == '')  ? 0 : curRow.find('.act_sales_value').val()); 

		market_rate_with_mc  = parseFloat((isNaN(curRow.find('.act_sales_value').val()) || curRow.find('.act_sales_value').val() == '')  ? 0 : curRow.find('.act_sales_value').val()); 

	} 

	

	if(tax_details.length > 0){

		// Tax Calculation

		var base_value_tax	= parseFloat(calculate_base_value_tax(rate_with_mc,tax_group)).toFixed(2);

		var base_value_amt	= parseFloat(parseFloat(rate_with_mc)+parseFloat(base_value_tax)).toFixed(2);

		var arrived_value_tax= parseFloat(calculate_arrived_value_tax(base_value_amt,tax_group)).toFixed(2);

		var arrived_value_amt= parseFloat(parseFloat(base_value_amt)+parseFloat(arrived_value_tax)).toFixed(2);

		total_tax_rate	= parseFloat(parseFloat(base_value_tax)+parseFloat(arrived_value_tax)).toFixed(2);

		

		var market_base_value_tax	= parseFloat(calculate_base_value_tax(market_rate_with_mc,tax_group)).toFixed(2);

		var market_base_value_amt	= parseFloat(parseFloat(market_rate_with_mc)+parseFloat(base_value_tax)).toFixed(2);

		var market_arrived_value_tax= parseFloat(calculate_arrived_value_tax(base_value_amt,tax_group)).toFixed(2);

		var market_arrived_value_amt= parseFloat(parseFloat(base_value_amt)+parseFloat(arrived_value_tax)).toFixed(2);

		market_total_tax_rate	= parseFloat(parseFloat(base_value_tax)+parseFloat(arrived_value_tax)).toFixed(2);

		

	} 

	

  total_price=parseFloat(parseFloat(rate_with_mc)+parseFloat(total_tax_rate)).toFixed(2);

  

  market_total_price=parseFloat(parseFloat(market_rate_with_mc)+parseFloat(market_total_tax_rate)).toFixed(2);

  

  curRow.find('.tax_price').val(total_tax_rate);

  curRow.find('.market_rate_tax').val(market_total_tax_rate);

  curRow.find('.market_rate_cost').val(market_total_price);

  curRow.find('.cost').html(total_price);

  curRow.find('.mc').html(parseFloat(mc_type).toFixed(2));

  curRow.find('.mc_value').val(parseFloat(mc_type).toFixed(2));

  curRow.find('.sales_value').val(total_price);

  console.log('Total Price :'+total_price);

  console.log('rate_with_mc :'+rate_with_mc);

  console.log('wast_wgt :'+wast_wgt);

  console.log('tot_wastage :'+tot_wastage);

  console.log('calculation_type :'+calculation_type);

  console.log('gross_wt :'+gross_wt);

  console.log('gross_wt :'+gross_wt);

  console.log('stone_price :'+stone_price);

  console.log('mc_type :'+mc_type);

  console.log('rate_per_grm :'+rate_per_grm);

  console.log('retail_max_mc :'+retail_max_mc);

  console.log('total_tax_rate :'+total_tax_rate);

  console.log('---------------');

    }

    });



 calculate_purchase_details();

 calculate_sales_details();

}



function calculateOrderTag()

{

    var adv_paid_amt=0;

    var adv_paid_wt=0;

    var total_weight=0;

    var balance_weight=0;

    var balance_pay_amt=0;

    var average_rate=0;

    $.each(order_adv_details,function(key,items){

        if(items.store_as==2) //Stored as Weight

        {

            adv_paid_amt+=parseFloat(items.paid_weight)*parseFloat(items.rate_per_gram); // Convert Weight into Amount

            adv_paid_wt+=parseFloat(items.paid_weight);

        }else if(items.store_as==1) //Stored as Amount

        {

            adv_paid_amt+=parseFloat(items.paid_advance);

            adv_paid_wt+=parseFloat(items.paid_advance)/parseFloat(items.rate_per_gram); // Convert Amount into Amount

        }

    });

    

    adv_paid_wt=parseFloat(adv_paid_wt).toFixed(3);

     $('#estimation_tag_details > tbody tr').each(function(idx, row){

         curRow = $(this);

         

        var gross_wt = (isNaN(curRow.find('.gwt').val()) || curRow.find('.gwt').val() == '')  ? 0 : curRow.find('.gwt').val();

    	var less_wt =  (isNaN(curRow.find('.lwt').val()) || curRow.find('.lwt').val() == '')  ? 0 : curRow.find('.lwt').val();

    	var stone_wt =  (isNaN(curRow.find('.stone_wt').val()) || curRow.find('.stone_wt').val() == '')  ? 0 : curRow.find('.stone_wt').val();

    	var net_wt = parseFloat(gross_wt)-parseFloat(less_wt);

    	var tot_wastage   = (isNaN(curRow.find('.wastage_max_per').val()) || curRow.find('.wastage_max_per').val() == '')  ? 0 : curRow.find('.wastage_max_per').val();  

    	var calculation_type =   (isNaN(curRow.find('.caltype').val()) || curRow.find('.caltype').val() == '')  ? 0 : curRow.find('.caltype').val();

    	    	/** 

            	*	Amount calculation based on settings (without discount and tax )

            	*   0 - Wastage on Gross weight And MC on Gross weight 

            	*   1 - Wastage on Net weight And MC on Net weight

            	*   2 - Wastage On Netwt And MC On Grwt

            	*   rate_with_mc = Metal Rate + Stone + OM + Wastage + MC

            	*/

	

            if(calculation_type == 0){ 

        		total_weight+= parseFloat((parseFloat(gross_wt) * parseFloat(tot_wastage/100))+parseFloat(gross_wt));

        	}

        	else if(calculation_type == 1){

        		total_weight+= parseFloat(parseFloat(net_wt) * parseFloat(tot_wastage/100)+parseFloat(net_wt));

        	}

        	else if(calculation_type == 2){ 

        		total_weight+=parseFloat(parseFloat(net_wt) * parseFloat(tot_wastage/100)+parseFloat(net_wt))

        	} 

     });

    

    balance_weight=parseFloat(parseFloat(total_weight)-parseFloat(adv_paid_wt)).toFixed(3);

    

    if(adv_paid_wt<total_weight)

    {

        var balance_pay_amt=parseFloat(parseFloat($('.goldrate_22ct').html())*parseFloat(balance_weight)).toFixed(2);

        

    }

    

    average_rate=parseFloat(parseFloat(parseFloat(adv_paid_amt)+parseFloat(balance_pay_amt))/parseFloat(total_weight)).toFixed(2);

    

    console.log('adv_paid_amt:'+adv_paid_amt);

    console.log('adv_paid_wt:'+adv_paid_wt);

    console.log('total_weight:'+total_weight);

    console.log('balance_weight:'+balance_weight);

    console.log('average_rate:'+average_rate);

    console.log('balance_pay_amt:'+balance_pay_amt);

    

    $('#estimation_tag_details > tbody tr').each(function(idx, row){

    curRow = $(this);

    

    var mc_type =0;

    var wast_wgt=0;

    var rate_with_mc=0;

    var market_rate_with_mc=0;

    

	var total_price = 0;

	var stone_price = 0;

	var material_price = 0;

	var tax_rate = 0;

	

	var total_tax_rate=0;

	var market_total_tax_rate=0;

	

	var base_value_tax = 0;

	var arrived_value_tax = 0;

	var base_value_amt=0;

	var arrived_value_amt=0;

	

	var market_base_value_tax = 0;

	var market_arrived_value_tax = 0;

	var market_base_value_amt=0;

	var market_arrived_value_amt=0;



	var rate_per_grm=0;

	var market_rate_per_grm=0;

	

	var gross_wt = (isNaN(curRow.find('.gwt').val()) || curRow.find('.gwt').val() == '')  ? 0 : curRow.find('.gwt').val();

	var less_wt =  (isNaN(curRow.find('.lwt').val()) || curRow.find('.lwt').val() == '')  ? 0 : curRow.find('.lwt').val();

	var stone_wt =  (isNaN(curRow.find('.stone_wt').val()) || curRow.find('.stone_wt').val() == '')  ? 0 : curRow.find('.stone_wt').val();

	var net_wt = parseFloat(gross_wt)-parseFloat(less_wt);

	var calculation_type =   (isNaN(curRow.find('.caltype').val()) || curRow.find('.caltype').val() == '')  ? 0 : curRow.find('.caltype').val();

	var piece =   (isNaN(curRow.find('.piece').val()) || curRow.find('.piece').val() == '')  ? 1 : curRow.find('.piece').val();

    var metal_type = (isNaN(curRow.find('.metal_type').val()) || curRow.find('.metal_type').val() == '')  ? 1 : curRow.find('.metal_type').val();

    var tax_group = curRow.find('.tax_group_id').val();



/*	if(metal_type==1)

	{

		var rate_per_grm = (isNaN($('.per-grm-sale-value').html()) || $('.per-grm-sale-value').html() == '')  ? 0 : parseFloat($('.per-grm-sale-value').html());

	}else{

		var rate_per_grm = (isNaN($('.silver_per-grm-sale-value').html()) || $('.silver_per-grm-sale-value').html() == '')  ? 0 : parseFloat($('.silver_per-grm-sale-value').html());

	}*/

	

	rate_field=curRow.find('.rate_field').val();



	market_rate_field=curRow.find('.market_rate_field').val();



	if(rate_field!='')

	{

		//rate_per_grm = (isNaN($('.'+rate_field).html()) ||$('.'+rate_field).html() == '')  ? 0 : $('.'+rate_field).html();

		rate_per_grm = average_rate;

	}



	if(market_rate_field!='')

	{

	    market_rate_per_grm = average_rate

		//market_rate_per_grm = (isNaN($('.'+market_rate_field).html()) ||$('.'+market_rate_field).html() == '')  ? 0 : parseFloat($('.'+market_rate_field).html());

	}





	

	/*if(metal_type==1)

	{

		var market_rate_per_grm = (isNaN($('.mjdmagoldrate_22ct').html()) || $('.mjdmagoldrate_22ct').html() == '')  ? 0 : parseFloat($('.mjdmagoldrate_22ct').html());

	}else{

		var market_rate_per_grm = (isNaN($('.mjdmasilverrate_1gm').html()) || $('.mjdmasilverrate_1gm').html() == '')  ? 0 : parseFloat($('.mjdmasilverrate_1gm').html());

	}*/

	

	var tot_wastage   = (isNaN(curRow.find('.wastage_max_per').val()) || curRow.find('.wastage_max_per').val() == '')  ? 0 : curRow.find('.wastage_max_per').val();

	var retail_max_mc = (isNaN(curRow.find('.act_mc_value').val()) || curRow.find('.act_mc_value').val() == '')  ? 0 : curRow.find('.act_mc_value').val();

	var stone_price = (isNaN(curRow.find('.stone_price').val()) || curRow.find('.stone_price').val() == '')  ? 0 : curRow.find('.stone_price').val();

	var certification_price = (isNaN(curRow.find('.certification_price').val()) || curRow.find('.certification_price').val() == '')  ? 0 : curRow.find('.certification_price').val();

	/** 

	*	Amount calculation based on settings (without discount and tax )

	*   0 - Wastage on Gross weight And MC on Gross weight 

	*   1 - Wastage on Net weight And MC on Net weight

	*   2 - Wastage On Netwt And MC On Grwt

	*   rate_with_mc = Metal Rate + Stone + OM + Wastage + MC

	*/

	console.log('gross_wt:'+gross_wt);

	console.log('net_wt:'+net_wt);

	console.log('retail_max_mc:'+retail_max_mc);

	if(calculation_type == 0){ 

		wast_wgt      = parseFloat(parseFloat(gross_wt) * parseFloat(tot_wastage/100)).toFixed(3);

		mc_type       =  parseFloat(curRow.find('.id_mc_type').val() == 1 ? parseFloat(retail_max_mc * gross_wt ) : parseFloat(retail_max_mc * piece));

		// Metal Rate + Stone + OM + Wastage + MC

		rate_with_mc = parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(certification_price));

		market_rate_with_mc = parseFloat(parseFloat(market_rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(certification_price));

	}

	else if(calculation_type == 1){

		var wast_wgt      = parseFloat(parseFloat(net_wt) * parseFloat(tot_wastage/100)).toFixed(3);

		var mc_type       =  parseFloat(curRow.find('.id_mc_type').val() == 1 ? parseFloat(retail_max_mc * net_wt ) : parseFloat(retail_max_mc * piece));

		// Metal Rate + Stone + OM + Wastage + MC

		rate_with_mc = parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(certification_price));

		market_rate_with_mc = parseFloat(parseFloat(market_rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(certification_price));

	}

	else if(calculation_type == 2){ 

		var wast_wgt      = parseFloat(parseFloat(net_wt) * parseFloat(tot_wastage/100)).toFixed(3);

		var mc_type       =  parseFloat(curRow.find('.id_mc_type').val() == 1 ? parseFloat(retail_max_mc * gross_wt ) : parseFloat(retail_max_mc * piece));

		// Metal Rate + Stone + OM + Wastage + MC

	    rate_with_mc = parseFloat((parseFloat(rate_per_grm) * (parseFloat(wast_wgt) + parseFloat(net_wt)))+ parseFloat(mc_type))+parseFloat(stone_price)+parseFloat(certification_price);

	    market_rate_with_mc = parseFloat((parseFloat(market_rate_per_grm) * (parseFloat(wast_wgt) + parseFloat(net_wt)))+ parseFloat(mc_type))+parseFloat(stone_price)+parseFloat(certification_price);

	} 

	else if(calculation_type == 3 || calculation_type == 4){ 

		rate_with_mc  = parseFloat((isNaN(curRow.find('.act_sales_value').val()) || curRow.find('.act_sales_value').val() == '')  ? 0 : curRow.find('.act_sales_value').val()); 

		market_rate_with_mc  = parseFloat((isNaN(curRow.find('.act_sales_value').val()) || curRow.find('.act_sales_value').val() == '')  ? 0 : curRow.find('.act_sales_value').val()); 

	} 

	

	if(tax_details.length > 0){

		// Tax Calculation

		var base_value_tax	= parseFloat(calculate_base_value_tax(rate_with_mc,tax_group)).toFixed(2);

		var base_value_amt	= parseFloat(parseFloat(rate_with_mc)+parseFloat(base_value_tax)).toFixed(2);

		var arrived_value_tax= parseFloat(calculate_arrived_value_tax(base_value_amt,tax_group)).toFixed(2);

		var arrived_value_amt= parseFloat(parseFloat(base_value_amt)+parseFloat(arrived_value_tax)).toFixed(2);

		total_tax_rate	= parseFloat(parseFloat(base_value_tax)+parseFloat(arrived_value_tax)).toFixed(2);

		

		var market_base_value_tax	= parseFloat(calculate_base_value_tax(market_rate_with_mc,tax_group)).toFixed(2);

		var market_base_value_amt	= parseFloat(parseFloat(market_rate_with_mc)+parseFloat(base_value_tax)).toFixed(2);

		var market_arrived_value_tax= parseFloat(calculate_arrived_value_tax(base_value_amt,tax_group)).toFixed(2);

		var market_arrived_value_amt= parseFloat(parseFloat(base_value_amt)+parseFloat(arrived_value_tax)).toFixed(2);

		market_total_tax_rate	= parseFloat(parseFloat(base_value_tax)+parseFloat(arrived_value_tax)).toFixed(2);

		

	} 

	

  total_price=parseFloat(parseFloat(rate_with_mc)+parseFloat(total_tax_rate)).toFixed(2);

  

  market_total_price=parseFloat(parseFloat(market_rate_with_mc)+parseFloat(market_total_tax_rate)).toFixed(2);

  

  curRow.find('.tax_price').val(total_tax_rate);

  curRow.find('.market_rate_tax').val(market_total_tax_rate);

  curRow.find('.market_rate_cost').val(market_total_price);

  curRow.find('.cost').html(total_price);

  curRow.find('.mc').html(parseFloat(mc_type).toFixed(2));

  curRow.find('.mc_value').val(parseFloat(mc_type).toFixed(2));

  curRow.find('.sales_value').val(total_price);

  console.log('Total Price :'+total_price);

  console.log('rate_with_mc :'+rate_with_mc);

  console.log('wast_wgt :'+wast_wgt);

  console.log('tot_wastage :'+tot_wastage);

  console.log('calculation_type :'+calculation_type);

  console.log('gross_wt :'+gross_wt);

  console.log('gross_wt :'+gross_wt);

  console.log('stone_price :'+stone_price);

  console.log('mc_type :'+mc_type);

  console.log('rate_per_grm :'+rate_per_grm);

  console.log('retail_max_mc :'+retail_max_mc);

  console.log('total_tax_rate :'+total_tax_rate);

  console.log('---------------');

    });



 calculate_purchase_details();

 calculate_sales_details();

}



function calculate_order_SaleValue(){

    $('#estimation_order_details > tbody tr').each(function(idx, row){

    curRow = $(this);

	var total_price = 0;

	var market_total_price = 0;

	var material_price = 0;

	var tax_rate = 0;

	var base_value_tax = 0;

	var arrived_value_price = 0;

	var base_value_price = 0;

	var arrived_value_tax = 0;

	var total_tax_rate=0;

	var market_base_rate_tax=0;

	var market_base_value_price=0;

	var market_arrived_rate_tax=0;

	var market_arrived_value_price=0;

	var market_total_tax_rate=0;

	var arrived_rate_tax=0;

	var market_taxable=0;

	

	var weight = (isNaN(curRow.find('.weight').val()) || curRow.find('.weight').val() == '')  ? 0 : curRow.find('.weight').val();

	

	var piece =   (isNaN(curRow.find('.piece').val()) || curRow.find('.piece').val() == '')  ? 1 : curRow.find('.piece').val();

	

    var metal_type = (isNaN(curRow.find('.metal_type').val()) || curRow.find('.metal_type').val() == '')  ? 1 : curRow.find('.metal_type').val();

    

	if(metal_type==1)

	{

		var rate_per_grm = (isNaN($('.per-grm-sale-value').html()) || $('.per-grm-sale-value').html() == '')  ? 0 : parseFloat($('.per-grm-sale-value').html());

	}else{

		var rate_per_grm = (isNaN($('.silver_per-grm-sale-value').html()) || $('.silver_per-grm-sale-value').html() == '')  ? 0 : parseFloat($('.silver_per-grm-sale-value').html());

	}

	if(metal_type==1)

	{

		var market_rate_per_grm = (isNaN($('.mjdmagoldrate_22ct').html()) || $('.mjdmagoldrate_22ct').html() == '')  ? 0 : parseFloat($('.mjdmagoldrate_22ct').html());

	}else{

		var market_rate_per_grm = (isNaN($('.mjdmasilverrate_1gm').html()) || $('.mjdmasilverrate_1gm').html() == '')  ? 0 : parseFloat($('.mjdmasilverrate_1gm').html());

	}

	var tot_wastage   = (isNaN(curRow.find('.wastage_max_per').val()) || curRow.find('.wastage_max_per').val() == '')  ? 0 : curRow.find('.wastage_max_per').val();

	

	var retail_max_mc = (isNaN(curRow.find('.mc_value').val()) || curRow.find('.mc_value').val() == '')  ? 0 : curRow.find('.mc_value').val();

	

	var stn_amt = (isNaN(curRow.find('.stn_amt').val()) || curRow.find('.stn_amt').val() == '')  ? 0 : curRow.find('.stn_amt').val();

	

	var tax_group = curRow.find('.tax_group').val();

	

	var tgi_calculation_type=curRow.find('.tgi_calculation').val().split(",");



	var tax_percentage=curRow.find('.tax_percentage').val().split(",");

	/** 

	*	Amount calculation based on settings (without discount and tax )

	*   0 - Wastage on Gross weight And MC on Gross weight 

	*   1 - Wastage on Net weight And MC on Net weight

	*   2 - Wastage On Netwt And MC On Grwt

	*   rate_with_mc = Metal Rate + Stone + OM + Wastage + MC

	*/

	var wast_wgt = parseFloat(parseFloat(weight) * parseFloat(tot_wastage/100)).toFixed(3);

	

	taxable = parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(weight))) +parseFloat(retail_max_mc)+parseFloat(stn_amt));  

	

	market_taxable = parseFloat(parseFloat(market_rate_per_grm * (parseFloat(wast_wgt) + parseFloat(weight))) +parseFloat(retail_max_mc)+parseFloat(stn_amt));  

	//sale value

    if(tax_details.length > 0){

		// Tax Calculation

		var base_value_tax	= parseFloat(calculate_base_value_tax(taxable,tax_group)).toFixed(2);

		var base_value_amt	= parseFloat(parseFloat(taxable)+parseFloat(base_value_tax)).toFixed(2);

		var arrived_value_tax= parseFloat(calculate_arrived_value_tax(base_value_amt,tax_group)).toFixed(2);

		var arrived_value_amt= parseFloat(parseFloat(base_value_amt)+parseFloat(arrived_value_tax)).toFixed(2);

		total_tax_rate	= parseFloat(parseFloat(base_value_tax)+parseFloat(arrived_value_tax)).toFixed(2);

		

		var market_base_value_tax	= parseFloat(calculate_base_value_tax(market_taxable,tax_group)).toFixed(2);

		var market_base_value_amt	= parseFloat(parseFloat(market_taxable)+parseFloat(market_base_value_tax)).toFixed(2);

		var market_arrived_value_tax= parseFloat(calculate_arrived_value_tax(market_base_value_amt,tax_group)).toFixed(2);

		var market_arrived_value_amt= parseFloat(parseFloat(market_base_value_amt)+parseFloat(market_arrived_value_tax)).toFixed(2);

		market_total_tax_rate	= parseFloat(parseFloat(market_base_value_tax)+parseFloat(market_arrived_value_tax)).toFixed(2);

	

	} 



  total_price=parseFloat(parseFloat(taxable)+parseFloat(total_tax_rate)).toFixed(2);



  market_total_price=parseFloat(parseFloat(market_taxable)+parseFloat(market_total_tax_rate)).toFixed(2);

    

  curRow.find('.tax_price').val(total_tax_rate);

  

  curRow.find('.market_rate_cost').val(market_total_price);



  curRow.find('.market_rate_tax').val(market_total_tax_rate);

  

  curRow.find('.cost').html(total_price);

  

  curRow.find('.item_cost').val(total_price);

  

  curRow.find('.sales_value').val(total_price);

  

  console.log('Total Price :'+total_price);

  

  console.log('wast_wgt :'+wast_wgt);

  

  console.log('tot_wastage :'+tot_wastage);

  

  console.log('rate_per_grm :'+rate_per_grm);

  

  console.log('---------------');

    });

    

    calculate_purchase_details();

    calculate_sales_details();

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

			 	  	$('.per-grm-sale-value').html(data.goldrate_22ct);

			 	  	$('.silver_per-grm-sale-value').html(data.silverrate_1gm);

			 	  	$('.mjdmagoldrate_22ct').html(data.mjdmagoldrate_22ct);

			 	  	$('.mjdmasilverrate_1gm').html(data.mjdmasilverrate_1gm);

			 	  	

			 	  	$('.goldrate_18ct').html(data.goldrate_18ct);

			 	  	$('.goldrate_22ct').html(data.goldrate_22ct);

			 	  	$('.silverrate_1gm').html(data.silverrate_1gm);

			 	  	

				  },

				  error:function(error)  

				  {

					 $("div.overlay").css("display", "none"); 

				  }	 

		  });

}

function validateChitDetailRow(){

	var row_validate = true;

	$('#estimation_chit_details > tbody  > tr').each(function(index, tr) {

		if($(this).find('td:first .scheme_account_id').val() == ""){

			row_validate = false;

		}

	});

	return row_validate;

}

function get_scheme_acc_number(searchTxt,curRow){

	my_Date = new Date();

	$.ajax({

        url: base_url+'index.php/admin_ret_estimation/get_scheme_accounts/?nocache=' + my_Date.getUTCSeconds(),             

        dataType: "json", 

        method: "POST", 

        data: {'searchTxt': searchTxt}, 

        success: function (data) {

        		$.each(data, function(key, item){

				$('#estimation_chit_details > tbody tr').each(function(idx, row){

					if(item != undefined){

						if($(this).find('td:first .id_scheme_account').val() == item.value){

							data.splice(key, 1);

						}

					}

				});

			});

			$( ".scheme_account_id" ).autocomplete(

			{

				source: data,

				select: function(e, i)

				{ 

					e.preventDefault();

					curRow.find('td:eq(0) .scheme_account_id').val(i.item.label);

					curRow.find('td:eq(0) .id_scheme_account').val(i.item.value);

					var curRowItem = i.item;

					curRow.find('td:eq(1) .chit_amt').val(curRowItem.closing_balance);

					calculate_sales_details();

				},

				change: function (event, ui) {

					if (ui.item === null) {

						//$(this).val('');

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

function removeCat_row(curRow)

{

	curRow.remove();

	calculate_sales_details();

	calculate_purchase_details();

}

//Catalog stone and material

function create_new_empty_est_cat_stone_item(curRow,id)

{

	if(curRow!=undefined)

	{

		$('#active_id').val(curRow.closest('tr').attr('id'));

	}

	var row = "";

	if(id!=undefined)

	{

			my_Date = new Date();

			$.ajax({

					 url:base_url+ "index.php/admin_ret_estimation/get_stone_details?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),

					 data:  {'est_item_id':id},

					 type:"POST",

					 dataType: "json", 

					 async:false,

					 success:function(data){

					 	if(data.length>0)

					 	{

					 		$.each(data, function (pkey, pitem) {

					 			var stones_list='';

								$.each(materials, function (pkey, item) 

								{

									var selected = "";

									if(item.stone_id == pitem.stone_id)

									{

										selected = "selected='selected'";

									}

									stones_list += "<option value='"+pitem.stone_id+"'>"+pitem.stone_name+"</option>";

								});	

								row+='<tr><td><select class="stone_id" name="est_stones_item[stone_id][]">'+stones_list+'</select><input type="hidden" class="stone_id" name="est_stones[stone_id][]" value="'+pitem['stone_id']+'" /></td><td><input type="number" class="stone_pcs" name="est_stones[stone_pcs][]" value="'+pitem['pieces']+'" /></td><td><input class="stone_wt" type="number" name="est_stones[stone_wt][]" value="'+pitem['wt']+'" /></td><td><input type="number" class="stone_price" name="est_stones[stone_price][]" value="'+pitem['price']+'" /></td><td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';

							});	

					 	}

					 	else

					 	{

					 		var stones_list = "<option value=''>-Select Stone-</option>";

					 		$.each(stones, function (pkey, pitem) {

								stones_list += "<option value='"+pitem.stone_id+"'>"+pitem.stone_name+"</option>";

							});

							row += '<tr><td><select class="stone_id" name="est_stones_item[stone_id][]">'+stones_list+'</select></td><td><input type="number" class="stone_pcs" name="est_stones_item[stone_pcs][]" value="" /></td><td><input class="stone_wt" type="number" name="est_stones_item[stone_wt][]" value="" /></td><td><input type="number" class="stone_price" name="est_stones_item[stone_price][]" value=""  /></td><td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';

					 	}

					  },

				  });

	}

	else

	{

		var catRow=$('#active_id').val();

		var row_st_details=$('#'+catRow).find('.stone_details').val();

		if(row_st_details!='')

		{

			var stone_details=JSON.parse(row_st_details);

			$.each(stone_details, function (pkey, pitem) {

	 			var stones_list='';

				$.each(stones, function (pkey, item) 

				{

					var selected = "";

					if(item.stone_id == pitem.stone_id)

					{

						selected = "selected='selected'";

					}

					stones_list += "<option value='"+pitem.stone_id+"'>"+item.stone_name+"</option>";

				});	

				row+='<tr><td><select class="stone_id" name="est_stones_item[stone_id][]">'+stones_list+'</select><input type="hidden" class="stone_id" name="est_stones[stone_id][]" value="'+pitem['stone_id']+'" /></td><td><input type="number" class="stone_pcs" name="est_stones[stone_pcs][]" value="'+pitem['stone_pcs']+'" /></td><td><input class="stone_wt" type="number" name="est_stones[stone_wt][]" value="'+pitem['stone_wt']+'" /></td><td><input type="number" class="stone_price" name="est_stones[stone_price][]" value="'+pitem['stone_price']+'" /></td><td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';

			});

		}

		else

		{

			var stones_list = "<option value=''>-Select Stone-</option>";

			$.each(stones, function (pkey, pitem) {

				stones_list += "<option value='"+pitem.stone_id+"'>"+pitem.stone_name+"</option>";

			});

			row += '<tr><td><select class="stone_id" name="est_stones_item[stone_id][]">'+stones_list+'</select></td><td><input type="number" class="stone_pcs" name="est_stones_item[stone_pcs][]" value="" /></td><td><input class="stone_wt" type="number" name="est_stones_item[stone_wt][]" value="" /></td><td><input type="number" class="stone_price" name="est_stones_item[stone_price][]" value=""  /></td><td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';

			}

		}

	$('#stoneModal .modal-body').find('#estimation_stone_item_details tbody').append(row);

	$('#stoneModal').modal('show');

}

$('#stoneModal .modal-body #create_stone_item_details').on('click', function(){

if(validateStoneItemDetailRow()){

			create_new_empty_est_cat_stone_item();

		}else{

			alert("Please fill required fields");

		}

});

$('#stoneModal  #close_stone_details').on('click', function(){

	$('#stoneModal .modal-body').find('#estimation_stone_item_details tbody').empty();

});

function validateStoneItemDetailRow(){

	var row_validate = true;

	$('#stoneModal .modal-body #estimation_stone_item_details> tbody  > tr').each(function(index, tr) {

		if($(this).find('td:first .stone_id').val() == "" || $(this).find('td:eq(1) .stone_pcs').val() == "" || $(this).find('td:eq(2) .stone_wt').val() == "" ){

			row_validate = false;

		}

	});

	return row_validate;

}

$('#stoneModal  #update_stone_details').on('click', function(){

	var stone_details=[];

	var stone_price=0;

	$('#stoneModal .modal-body #estimation_stone_item_details> tbody  > tr').each(function(index, tr) {

		stone_price+=parseFloat($(this).find('td:eq(3) .stone_price').val());

		stone_details.push({'stone_id' : $(this).find('td:first .stone_id').val(),'stone_pcs' :$(this).find('td:eq(1) .stone_pcs').val(),'stone_wt':$(this).find('td:eq(2) .stone_wt').val(),'stone_price':$(this).find('td:eq(3) .stone_price').val()});

	});

	$('#stoneModal').modal('toggle');

	var catRow=$('#active_id').val();

	$('#'+catRow).find('.stone_details').val(stone_details.length>0 ? JSON.stringify(stone_details):'');

   	$('#'+catRow).find('.stone_price').val(stone_price);

   	var row = $('#'+catRow).closest('tr');

   	calculateSaleValue(row);

	$('#stoneModal .modal-body').find('#estimation_stone_item_details tbody').empty();

});

//other materials

function create_new_empty_est_cat_other_material(curRow,id)

{

	if(curRow!=undefined)

	{

		$('#active_id').val(curRow.closest('tr').attr('id'));

	}

	var row = "";

	console.log(id);

	if(id!=undefined)

	{

			my_Date = new Date();

			$.ajax({

					 url:base_url+ "index.php/admin_ret_estimation/get_other_material_details?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),

					 data:  {'est_item_id':id},

					 type:"POST",

					 dataType: "json", 

					 async:false,

					 success:function(data){

					 	if(data.length>0)

					 	{

					 		$.each(data, function (pkey, pitem) 

					 		{

						 		var material_list ="";

								$.each(materials, function (pkey, item) 

								{

									var selected = "";

									if(item.material_id == pitem.material_id)

									{

										selected = "selected='selected'";

									}

									material_list += "<option value='"+item.material_id+"' "+selected+">"+item.material_name+"</option>";

								});	

								row+='<tr><td><select class="material_id" name="est_materials[material_id][]">'+material_list+'</select><input type="hidden" class="material_id" name="est_materials[material_id][]" value="'+pitem['material_id']+'" /></td><td><input class="material_wt" type="number" name="est_materials[material_wt][]" value="'+pitem['wt']+'" /></td><td><input type="number" class="material_price" name="est_materials[material_price][]" value="'+pitem['price']+'" /></td><td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';

							});	

					 	}

					 	else

					 	{

					 		var material_list = "<option value=''> - Select Material - </option>";

							$.each(materials, function (pkey, pitem) {

								material_list += "<option value='"+pitem.material_id+"'>"+pitem.material_name+"</option>";

							});	

							row += '<tr><td><select class="material_id" name="est_materials[material_id][]">'+material_list+'</select></td><td><input class="material_wt" type="number" name="est_materials[material_wt][]" value="" /></td><td><input type="number" class="material_price" name="est_materials[material_price][]" value=""  /></td><td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';

					 	}

					  },

				  });

	}

	else

	{

		var catRow=$('#active_id').val();

		var row_mt_details=$('#'+catRow).find('td:eq(13) .material_details').val();

		if(row_mt_details!='')

		{

			var material_details=JSON.parse(row_mt_details);

			$.each(material_details, function (pkey, pitem) {

	 			var material_list ="";

				$.each(materials, function (pkey, item) 

				{

					var selected = "";

					if(item.material_id == pitem.material_id)

					{

						selected = "selected='selected'";

					}

					material_list += "<option value='"+item.material_id+"' "+selected+">"+item.material_name+"</option>";

				});	

				row+='<tr><td><select class="material_id" name="est_materials[material_id][]">'+material_list+'</select><input type="hidden" class="material_id" name="est_materials[material_id][]" value="'+pitem['material_id']+'" /></td><td><input class="material_wt" type="number" name="est_materials[material_wt][]" value="'+pitem['material_wt']+'" /></td><td><input type="number" class="material_price" name="est_materials[material_price][]" value="'+pitem['material_price']+'" /></td><td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';

			});

		}

		else

		{

			var material_list = "<option value=''> - Select Material - </option>";

			$.each(materials, function (pkey, pitem) {

				material_list += "<option value='"+pitem.material_id+"'>"+pitem.material_name+"</option>";

			});	

			row += '<tr><td><select class="material_id" name="est_materials[material_id][]">'+material_list+'</select></td><td><input class="material_wt" type="number" name="est_materials[material_wt][]" value="" /></td><td><input type="number" class="material_price" name="est_materials[material_price][]" value=""  /></td><td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';

		}

	}

	$('#other_material_modal .modal-body').find('#estimation_other_material_details tbody').append(row);

	$('#other_material_modal').modal('show');

}

$('#other_material_modal .modal-body #create_material_item_details').on('click', function(){

if(validateMaterialItemDetailRow()){

			create_new_empty_est_cat_other_material();

		}else{

			alert("Please fill required fields");

		}

});

function validateMaterialItemDetailRow(){

	var row_validate = true;

	$('#other_material_modal .modal-body #estimation_other_material_details> tbody  > tr').each(function(index, tr) {

		if($(this).find('td:first .material_id').val() == "" || $(this).find('td:eq(1) .material_wt').val() == ""){

			row_validate = false;

		}

	});

	return row_validate;

}

$('#other_material_modal  #close_material_details').on('click', function(){

	$('#other_material_modal .modal-body').find('#estimation_other_material_details tbody').empty();

});

$('#other_material_modal  #update_material_details').on('click', function(){

	var material_details=[];

	var material_price=0;

	$('#other_material_modal .modal-body #estimation_other_material_details> tbody  > tr').each(function(index, tr) {

		material_price+=parseFloat($(this).find('td:eq(2) .material_price').val());

		material_details.push({'material_id' : $(this).find('td:first .material_id').val(),'material_wt' :$(this).find('td:eq(1) .material_wt').val(),'material_price':$(this).find('td:eq(2) .material_price').val()});

	});

	$('#other_material_modal').modal('toggle');

	var catRow=$('#active_id').val();

	$('#'+catRow).find('td:eq(13) .material_details').val(material_details.length>0 ? JSON.stringify(material_details):'');

   	$('#'+catRow).find('td:eq(13) .material_price').val(material_price);

	var row = $('#'+catRow).closest('tr');

   	calculateSaleValue(row);

	$('#other_material_modal .modal-body').find('#estimation_other_material_details tbody').empty();

});

//other materials

//catalog

//custom

function create_new_empty_est_cus_stone_item(curRow,id)

{

	if(curRow!=undefined)

	{

		$('#custom_active_id').val(curRow.closest('tr').attr('id'));

	}

	var row = "";

	if(id!=undefined)

	{

			my_Date = new Date();

			$.ajax({

					 url:base_url+ "index.php/admin_ret_estimation/get_stone_details?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),

					 data:  {'est_item_id':id},

					 type:"POST",

					 dataType: "json", 

					 async:false,

					 success:function(data){

					 	if(data.length>0)

					 	{

					 		$.each(data, function (pkey, pitem) {

					 			var stones_list='';

								$.each(materials, function (pkey, item) 

								{

									var selected = "";

									if(item.stone_id == pitem.stone_id)

									{

										selected = "selected='selected'";

									}

									stones_list += "<option value='"+pitem.stone_id+"'>"+pitem.stone_name+"</option>";

								});	

								row+='<tr><td><select class="stone_id" name="est_stones_item[stone_id][]">'+stones_list+'</select><input type="hidden" class="stone_id" name="est_stones[stone_id][]" value="'+pitem['stone_id']+'" /></td><td><input type="number" class="stone_pcs" name="est_stones[stone_pcs][]" value="'+pitem['pieces']+'" /></td><td><input class="stone_wt" type="number" name="est_stones[stone_wt][]" value="'+pitem['wt']+'" /></td><td><input type="number" class="stone_price" name="est_stones[stone_price][]" value="'+pitem['price']+'" /></td><td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';

							});	

					 	}

					 	else

					 	{

					 		var stones_list = "<option value=''>-Select Stone-</option>";

					 		$.each(stones, function (pkey, pitem) {

								stones_list += "<option value='"+pitem.stone_id+"'>"+pitem.stone_name+"</option>";

							});

							row += '<tr><td><select class="stone_id" name="est_stones_item[stone_id][]">'+stones_list+'</select></td><td><input type="number" class="stone_pcs" name="est_stones_item[stone_pcs][]" value="" /></td><td><input class="stone_wt" type="number" name="est_stones_item[stone_wt][]" value="" /></td><td><input type="number" class="stone_price" name="est_stones_item[stone_price][]" value=""  /></td><td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';

					 	}

					  },

				  });

	}

	else

	{

		var catRow=$('#custom_active_id').val();

		var row_st_details=$('#'+catRow).find('.stone_details').val();

		if(row_st_details!='')

		{

			var stone_details=JSON.parse(row_st_details);

			$.each(stone_details, function (pkey, pitem) {

	 			var stones_list='';

				$.each(stones, function (pkey, item) 

				{

					var selected = "";

					if(item.stone_id == pitem.stone_id)

					{

						selected = "selected='selected'";

					}

					stones_list += "<option value='"+pitem.stone_id+"'>"+item.stone_name+"</option>";

				});	

				row+='<tr><td><select class="stone_id" name="est_stones_item[stone_id][]">'+stones_list+'</select><input type="hidden" class="stone_id" name="est_stones[stone_id][]" value="'+pitem['stone_id']+'" /></td><td><input type="number" class="stone_pcs" name="est_stones[stone_pcs][]" value="'+pitem['stone_pcs']+'" /></td><td><input class="stone_wt" type="number" name="est_stones[stone_wt][]" value="'+pitem['stone_wt']+'" /></td><td><input type="number" class="stone_price" name="est_stones[stone_price][]" value="'+pitem['stone_price']+'" /></td><td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';

			});

		}

		else

		{

			var stones_list = "<option value=''>-Select Stone-</option>";

			$.each(stones, function (pkey, pitem) {

				stones_list += "<option value='"+pitem.stone_id+"'>"+pitem.stone_name+"</option>";

			});

			row += '<tr><td><select class="stone_id" name="est_stones_item[stone_id][]">'+stones_list+'</select></td><td><input type="number" class="stone_pcs" name="est_stones_item[stone_pcs][]" value="" /></td><td><input class="stone_wt" type="number" name="est_stones_item[stone_wt][]" value="" /></td><td><input type="number" class="stone_price" name="est_stones_item[stone_price][]" value=""  /></td><td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';

		}

	}

	$('#cus_stoneModal .modal-body').find('#estimation_stone_cus_item_details tbody').append(row);

	$('#cus_stoneModal').modal('show');

}

$('#cus_stoneModal .modal-body #create_stone_item_details').on('click', function(){

if(validateStoneCusItemDetailRow()){

			create_new_empty_est_cus_stone_item();

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

		if($(this).find('td:first .stone_id').val() == "" || $(this).find('td:eq(1) .stone_pcs').val() == "" || $(this).find('td:eq(2) .stone_wt').val() == "" ){

			row_validate = false;

		}

	});

	return row_validate;

}

$('#cus_stoneModal  #update_stone_details').on('click', function(){

	var stone_details=[];

	var stone_price=0;

	$('#cus_stoneModal .modal-body #estimation_stone_cus_item_details> tbody  > tr').each(function(index, tr) {

		stone_price+=parseFloat($(this).find('td:eq(3) .stone_price').val());

		stone_details.push({'stone_id' : $(this).find('td:first .stone_id').val(),'stone_pcs' :$(this).find('td:eq(1) .stone_pcs').val(),'stone_wt':$(this).find('td:eq(2) .stone_wt').val(),'stone_price':$(this).find('td:eq(3) .stone_price').val()});

	});

	$('#cus_stoneModal').modal('toggle');

	var catRow=$('#custom_active_id').val();

	$('#'+catRow).find('.stone_details').val(JSON.stringify(stone_details));

   	$('#'+catRow).find('.stone_price').val(stone_price);

	var row = $('#'+catRow).closest('tr');

	calculateCustomItemSaleValue();

	$('#cus_stoneModal .modal-body').find('#estimation_stone_cus_item_details tbody').empty();

});

//other materials


//customer charges

function create_new_empty_est_cus_charges_item(curRow,id)
{
	if(curRow!=undefined)
	{
		$('#custom_active_id').val(curRow.closest('tr').attr('id'));
	}
	var row = "";
	console.log(id);
	if(id!=undefined)
	{
			var charge_list = "<option value=''>-Select Charge Type-</option>";
			$.each(other_charges_details, function (pkey, pitem) {
				charge_list += "<option value='"+pitem.id_charge+"'>"+pitem.name_charge+"</option>";
			});
			row += '<tr><td><select class="id_charge" name="est_stones_item[id_charge][]">'+charge_list+'</select></td><td><input type="number" class="value_charge" name="est_stones_item[value_charge][]" value="" /></td></tr>';
		
	}
	else
	{
		var catRow=$('#custom_active_id').val();
		var row_charges_details_details=$('#'+catRow).find('.charges_details').val();
		if(row_charges_details_details!='')
		{
			var charges_details=JSON.parse(row_charges_details_details);
			$.each(charges_details, function (pkey, pitem) {
	 			var charge_list='';
				$.each(charges_details, function (pkey, item) 
				{
					var selected = "";
					if(item.id_charge == pitem.id_charge)
					{
						selected = "selected='selected'";
					}
					charge_list += "<option value='"+pitem.id_charge+"'>"+item.name_charge+"</option>";
				});	
				row+='<tr><td><select class="id_charge" name="est_stones_item[id_charge][]">'+charge_list+'</select></td><td><input class="stone_wt" type="number" name="est_stones[stone_wt][]" value="'+pitem['stone_wt']+'" /></td><td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';
			});
		}
		else
		{
			var charge_list = "<option value=''>-Select Charge Type-</option>";
			$.each(other_charges_details, function (pkey, pitem) {
				charge_list += "<option value='"+pitem.id_charge+"'>"+pitem.name_charge+"</option>";
			});
			row += '<tr><td><select class="id_charge" name="est_stones_item[id_charge][]">'+charge_list+'</select></td><td><input type="number" class="value_charge" name="est_stones_item[value_charge][]" value="" /></td><td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';
		}
	}
	$('#cus_other_charges_modal .modal-body').find('#estimation_other_charge_cus_item_details tbody').append(row);
	$('#cus_other_charges_modal').modal('show');
}

$(document).on('change',".id_charge", function(e){
    var id_charge=this.value;
    var row = $(this).closest('tr'); 
	$.each(other_charges_details, function (pkey, pitem) {
	    if(id_charge==pitem.id_charge)
	    {
	        row.find('.value_charge').val(pitem.value_charge);;
	    }
	});
});

$('#cus_other_charges_modal .modal-body #add_new_charge').on('click', function(){
if(validatecusOtherChargeDetailRow()){
			create_new_empty_est_cus_charges_item();
		}else{
			alert("Please fill required fields");
		}
});

function validatecusOtherChargeDetailRow(){
	var row_validate = true;
	$('#cus_other_charges_modal .modal-body #estimation_other_charge_cus_item_details> tbody  > tr').each(function(index, tr) {
		if($(this).find('.id_charge').val() == "" || $(this).find('.value_charge').val() == 0 || $(this).find('.value_charge').val() == '' ){
			row_validate = false;
		}
	});
	return row_validate;
}

$('#cus_other_charges_modal  #update_charge_details').on('click', function(){
	var charge_details=[];
	var value_charge=0;
	$('#cus_other_charges_modal .modal-body #estimation_other_charge_cus_item_details> tbody  > tr').each(function(index, tr) {
		value_charge+=parseFloat($(this).find('.value_charge').val());
		charge_details.push({'value_charge' : $(this).find('.value_charge').val(),'id_charge' :$(this).find('.id_charge').val()});
	});
	$('#cus_other_charges_modal').modal('toggle');
	var catRow=$('#custom_active_id').val();
	$('#'+catRow).find('.charges_details').val(charge_details.length>0 ? JSON.stringify(charge_details):'');
   	$('#'+catRow).find('.value_charge').val(value_charge);
   	var row = $('#'+catRow).closest('tr');
   	calculateSaleValue(row);
	$('#cus_other_charges_modal .modal-body').find('#estimation_other_charge_cus_item_details tbody').empty();
});

//customer charges

function create_new_empty_est_cus_other_material(curRow,id)

{

	if(curRow!=undefined)

	{

		$('#custom_active_id').val(curRow.closest('tr').attr('id'));

	}

	var row = "";

	if(id!=undefined)

	{

			my_Date = new Date();

			$.ajax({

					 url:base_url+ "index.php/admin_ret_estimation/get_other_material_details?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),

					 data:  {'est_item_id':id},

					 type:"POST",

					 dataType: "json", 

					 async:false,

					 success:function(data){

					 	if(data.length>0)

					 	{

					 		$.each(data, function (pkey, pitem) 

					 		{

						 		var material_list ="";

								$.each(materials, function (pkey, item) 

								{

									var selected = "";

									if(item.material_id == pitem.material_id)

									{

										selected = "selected='selected'";

									}

									material_list += "<option value='"+item.material_id+"' "+selected+">"+item.material_name+"</option>";

								});	

								row+='<tr><td><select class="material_id" name="est_materials[material_id][]">'+material_list+'</select><input type="hidden" class="material_id" name="est_materials[material_id][]" value="'+pitem['material_id']+'" /></td><td><input class="material_wt" type="number" name="est_materials[material_wt][]" value="'+pitem['wt']+'" /></td><td><input type="number" class="material_price" name="est_materials[material_price][]" value="'+pitem['price']+'" /></td><td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';

							});	

					 	}

					 	else

					 	{

					 		var material_list = "<option value=''> - Select Material - </option>";

							$.each(materials, function (pkey, pitem) {

								material_list += "<option value='"+pitem.material_id+"'>"+pitem.material_name+"</option>";

							});	

							row += '<tr><td><select class="material_id" name="est_materials[material_id][]">'+material_list+'</select></td><td><input class="material_wt" type="number" name="est_materials[material_wt][]" value="" /></td><td><input type="number" class="material_price" name="est_materials[material_price][]" value=""  /></td><td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';

					 	}

					  },

				  });

	}

	else

	{

		var catRow=$('#custom_active_id').val();

		var row_mt_details=$('#'+catRow).find('td:eq(12) .material_details').val();

		if(row_mt_details!='')

		{

			var material_details=JSON.parse(row_mt_details);

			$.each(material_details, function (pkey, pitem) {

	 			var material_list ="";

				$.each(materials, function (pkey, item) 

				{

					var selected = "";

					if(item.material_id == pitem.material_id)

					{

						selected = "selected='selected'";

					}

					material_list += "<option value='"+item.material_id+"' "+selected+">"+item.material_name+"</option>";

				});	

				row+='<tr><td><select class="material_id" name="est_materials[material_id][]">'+material_list+'</select><input type="hidden" class="material_id" name="est_materials[material_id][]" value="'+pitem['material_id']+'" /></td><td><input class="material_wt" type="number" name="est_materials[material_wt][]" value="'+pitem['material_wt']+'" /></td><td><input type="number" class="material_price" name="est_materials[material_price][]" value="'+pitem['material_price']+'" /></td><td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';

			});

		}

		else

		{

				var material_list = "<option value=''> - Select Material - </option>";

				$.each(materials, function (pkey, pitem) {

					material_list += "<option value='"+pitem.material_id+"'>"+pitem.material_name+"</option>";

				});	

				row += '<tr><td><select class="material_id" name="est_materials[material_id][]">'+material_list+'</select></td><td><input class="material_wt" type="number" name="est_materials[material_wt][]" value="" /></td><td><input type="number" class="material_price" name="est_materials[material_price][]" value=""  /></td><td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';

		}

	}

	$('#cus_other_material_modal .modal-body').find('#estimation_other_cus_material_details tbody').append(row);

	$('#cus_other_material_modal').modal('show');

}

$('#cus_other_material_modal .modal-body #create_material_item_details').on('click', function(){

if(validateMaterialCusItemDetailRow()){

			create_new_empty_est_cus_other_material();

		}else{

			alert("Please fill required fields");

		}

});

function validateMaterialCusItemDetailRow(){

	var row_validate = true;

	$('#cus_other_material_modal .modal-body #estimation_other_cus_material_details> tbody  > tr').each(function(index, tr) {

		if($(this).find('td:first .material_id').val() == "" || $(this).find('td:eq(1) .material_wt').val() == ""){

			row_validate = false;

		}

	});

	return row_validate;

}

$('#cus_other_material_modal  #close_material_details').on('click', function(){

	$('#cus_other_material_modal .modal-body').find('#estimation_other_cus_material_details tbody').empty();

});

$('#cus_other_material_modal  #update_material_details').on('click', function(){

	var material_details=[];

	var material_price=0;

	$('#cus_other_material_modal .modal-body #estimation_other_cus_material_details> tbody  > tr').each(function(index, tr) {

		material_price+=parseFloat($(this).find('td:eq(2) .material_price').val());

		material_details.push({'material_id' : $(this).find('td:first .material_id').val(),'material_wt' :$(this).find('td:eq(1) .material_wt').val(),'material_price':$(this).find('td:eq(2) .material_price').val()});

	});

	$('#cus_other_material_modal').modal('toggle');

	var catRow=$('#custom_active_id').val();

	$('#'+catRow).find('td:eq(12) .material_details').val(JSON.stringify(material_details));

   	$('#'+catRow).find('td:eq(12) .material_price').val(material_price);

   	var row = $('#'+catRow).closest('tr');

	calculateCustomItemSaleValue();

	$('#cus_other_material_modal .modal-body').find('#estimation_other_cus_material_details tbody').empty();

});

//other materials

//custom

//old metal start

function create_new_empty_est_old_metal_stone(curRow,id)

{

	if(curRow!=undefined)

	{

		$('#old_metal_active_id').val(curRow.closest('tr').attr('id'));

	}

	var row = "";

	if(id!=undefined)

	{

			my_Date = new Date();

			$.ajax({

					 url:base_url+ "index.php/admin_ret_estimation/get_old_metal_stone_details?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),

					 data:  {'est_old_metal_sale_id':id},

					 type:"POST",

					 dataType: "json", 

					 async:false,

					 success:function(data){

					 	if(data.length>0)

					 	{

					 		$.each(data, function (pkey, pitem) {

					 			var stones_list='';

								$.each(materials, function (pkey, item) 

								{

									var selected = "";

									if(item.stone_id == pitem.stone_id)

									{

										selected = "selected='selected'";

									}

									stones_list += "<option value='"+pitem.stone_id+"'>"+pitem.stone_name+"</option>";

								});	

								row+='<tr><td><select class="stone_id" name="est_stones_item[stone_id][]">'+stones_list+'</select><input type="hidden" class="stone_id" name="est_stones[stone_id][]" value="'+pitem['stone_id']+'" /></td><td><input type="number" class="stone_pcs" name="est_stones[stone_pcs][]" value="'+pitem['pieces']+'" /></td><td><input class="stone_wt" type="number" name="est_stones[stone_wt][]" value="'+pitem['wt']+'" /></td><td><input type="number" class="stone_price" name="est_stones[stone_price][]" value="'+pitem['price']+'" /></td><td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';

							});	

					 	}

					 	else

					 	{

					 		var stones_list = "<option value=''>-Select Stone-</option>";

					 		$.each(stones, function (pkey, pitem) {

								stones_list += "<option value='"+pitem.stone_id+"'>"+pitem.stone_name+"</option>";

							});

							row += '<tr><td><select class="stone_id" name="est_stones_item[stone_id][]">'+stones_list+'</select></td><td><input type="number" class="stone_pcs" name="est_stones_item[stone_pcs][]" value="" /></td><td><input class="stone_wt" type="number" name="est_stones_item[stone_wt][]" value="" /></td><td><input type="number" class="stone_price" name="est_stones_item[stone_price][]" value=""  /></td><td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';

					 	}

					  },

				  });

	}

	else

	{

		var catRow=$('#old_metal_active_id').val();

		var row_st_details=$('#'+catRow).find('.stone_details').val();

		if(row_st_details.length>0)

		{

			var stone_details=JSON.parse(row_st_details);

			$.each(stone_details, function (pkey, pitem) {

					 			var stones_list='';

								$.each(stones, function (pkey, item) 

								{

									var selected = "";

									if(item.stone_id == pitem.stone_id)

									{

										selected = "selected='selected'";

									}

									stones_list += "<option value='"+pitem.stone_id+"'>"+item.stone_name+"</option>";

								});	

								row+='<tr><td><select class="stone_id" name="est_stones_item[stone_id][]">'+stones_list+'</select><input type="hidden" class="stone_id" name="est_stones[stone_id][]" value="'+pitem['stone_id']+'" /></td><td><input type="number" class="stone_pcs" name="est_stones[stone_pcs][]" value="'+pitem['stone_pcs']+'" /></td><td><input class="stone_wt" type="number" name="est_stones[stone_wt][]" value="'+pitem['stone_wt']+'" /></td><td><input type="number" class="stone_price" name="est_stones[stone_price][]" value="'+pitem['stone_price']+'" /></td><td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';

							});

		}

		else

		{

			var stones_list = "<option value=''>-Select Stone-</option>";

			$.each(stones, function (pkey, pitem) {

				stones_list += "<option value='"+pitem.stone_id+"'>"+pitem.stone_name+"</option>";

			});

			row += '<tr><td><select class="stone_id" name="est_stones_item[stone_id][]">'+stones_list+'</select></td><td><input type="number" class="stone_pcs" name="est_stones_item[stone_pcs][]" value="" /></td><td><input class="stone_wt" type="number" name="est_stones_item[stone_wt][]" value="" /></td><td><input type="number" class="stone_price" name="est_stones_item[stone_price][]" value=""  /></td><td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';			

		}

	}

	$('#old_stoneModal .modal-body').find('#estimation_stone_old_metal_details tbody').append(row);

	$('#old_stoneModal').modal('show');

}

$('#old_stoneModal .modal-body #create_stone_old').on('click', function(){

if(validateStoneoldMetalDetailRow()){

			create_empty_est_old_metal_stone();

		}else{

			alert("Please fill required fields");

		}

});

function create_empty_est_old_metal_stone(curRow,id)

{

	if(curRow!=undefined)

	{

		$('#old_metal_active_id').val(curRow.closest('tr').attr('id'));

	}

	var row = "";	

	var catRow=$('#old_metal_active_id').val();

	var stones_list = "<option value=''>-Select Stone-</option>";

	$.each(stones, function (pkey, pitem) {

	stones_list += "<option value='"+pitem.stone_id+"'>"+pitem.stone_name+"</option>";

	});

	row += '<tr><td><select class="stone_id" name="est_stones_item[stone_id][]">'+stones_list+'</select></td><td><input type="number" class="stone_pcs" name="est_stones_item[stone_pcs][]" value="" /></td><td><input class="stone_wt" type="number" name="est_stones_item[stone_wt][]" value="" /></td><td><input type="number" class="stone_price" name="est_stones_item[stone_price][]" value=""  /></td><td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';			

	$('#old_stoneModal .modal-body').find('#estimation_stone_old_metal_details tbody').append(row);

	$('#old_stoneModal').modal('show');

}

$('#old_stoneModal  #close_stone_details').on('click', function(){

	$('#old_stoneModal .modal-body').find('#estimation_stone_old_metal_details tbody').empty();

});

function validateStoneoldMetalDetailRow(){

	var row_validate = true;

	$('#old_stoneModal .modal-body #estimation_stone_old_metal_details> tbody  > tr').each(function(index, tr) {

		if($(this).find('td:first .stone_id').val() == "" || $(this).find('td:eq(1) .stone_pcs').val() == "" || $(this).find('td:eq(2) .stone_wt').val() == "" ){

			row_validate = false;

		}

	});

	return row_validate;

}

$('#old_stoneModal  #update_stone_details').on('click', function(){

	var stone_details=[];

	var stone_price=0;

	var stone_wt=0;

	$('#old_stoneModal .modal-body #estimation_stone_old_metal_details> tbody  > tr').each(function(index, tr) {

		stone_id=$(this).find('td:first .stone_id').val();

		stone_price+=parseFloat($(this).find('td:eq(3) .stone_price').val());

		stone_wt+=parseFloat($(this).find('td:eq(2) .stone_wt').val());

		stone_details.push({'stone_id' : $(this).find('td:first .stone_id').val(),'stone_pcs' :$(this).find('td:eq(1) .stone_pcs').val(),'stone_wt':$(this).find('td:eq(2) .stone_wt').val(),'stone_price':$(this).find('td:eq(3) .stone_price').val()});

	});

	if(stone_id!='')

	{

		$('#old_stoneModal').modal('toggle');

		var catRow=$('#old_metal_active_id').val();

		var row = $('#'+catRow).closest('tr');

		$('#'+catRow).find('.stone_details').val(JSON.stringify(stone_details));

	   	$('#'+catRow).find('.stone_price').val(stone_price);

	   	$('#'+catRow).find('.stone_wt').val(stone_wt);

	   	calculateOldMatelItemSaleValue(row);

		$('#old_stoneModal .modal-body').find('#estimation_stone_old_metal_details tbody').empty();

	}

	else

	{

		alert('Please Select Stone');

	}

});

function get_old_metal_rate(id_metal,row)

{

	my_Date = new Date();

	$.ajax({ 

		url:base_url+ "index.php/admin_ret_estimation/get_old_metal_rate?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),

        data: {'id_metal':id_metal},

        type:"POST",

        dataType:"JSON",

        success:function(data)

        {

           row.find('.old_rate').val(data.rate);

        },

        error:function(error)  

        {	

        } 

    });

}

//old metal

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

                	 	$("#sel_village,#ed_sel_village,#village_select").append(						

                	 	$("<option></option>")						

                	 	.attr("value", item.id_village)						  						  

                	 	.text(item.village_name)						  					

                	 	);			   											

                 	});						

             	$("#sel_village,#ed_sel_village,#village_select").select2({			    

            	 	placeholder: "Select Area",			    

            	 	allowClear: true		    

             	});	

             	$('#village_select').select2("val",'');

             	

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

	

//Employee Filter

	function get_employee(id_branch)

	{

	    $('#emp_select option').remove();

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

                	 	.text(item.emp_name)						  					

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



$('#order_search').on('click',function(){

	var order_no=$('#est_order').val();

	getTagOrder_details(order_no);

});



function get_order_items(order_no)

{

	  my_Date = new Date();

                $.ajax({

                type: 'POST',

        	    url: base_url+'index.php/admin_ret_estimation/get_order_details/?nocache=' + my_Date.getUTCSeconds(),     

                dataType:'json',

                 data: {'searchTxt': order_no,'id_branch':$('#id_branch').val()}, 

                success:function(data){

					if(data.order_no!=null && data.tot_pcs>0)

					{

						var tagged_pcs=0;

						$('#estimation_tag_details > tbody tr').each(function(bidx, brow){ 

							tag_row = $(this);    

							if( data.order_no == tag_row.find('.order_no').html()){

								console.log(tag_row.find('.piece').val());

								tagged_pcs +=parseFloat(tag_row.find('.piece').val());

							} 

						});

						if(tagged_pcs!=data.tot_pcs)

						{

							$('#est_print').prop('disabled',true);

							alert('Please Select All Order Items');

						}else{

							$('#est_print').prop('disabled',false);

						}

					}

                }

                });

}



function getTagOrder_details(order_no)

{

	$('#searchEstiOrderAlert').html('');

	  my_Date = new Date();

                $.ajax({

                type: 'POST',

        	    url: base_url+'index.php/admin_ret_estimation/getOrderBySearch/?nocache=' + my_Date.getUTCSeconds(),     

                dataType:'json',

                 data: {'searchTxt': order_no, 'id_branch': $("#id_branch").val(),'fin_year':$('#fin_year').val()}, 

                success:function(data){

					if(data.responseData!=null && data.responseData.length>0)

					{   

						var row = "";

						var rowExist=false;

						

						var paid_advance=0;



				        var paid_weight=0;

				

				        var wt_amt=0;

				        

				        order_adv_details=data.adv_details;

				        

						if(data.adv_details.length>0)

						{

						   $.each(data.adv_details,function(key,item){

						       

							   	paid_advance +=parseFloat(item.paid_advance);



					            paid_weight +=parseFloat(item.paid_weight);

					

					            wt_amt +=parseFloat(item.paid_weight*item.rate_per_gram);

					            

							}); 

						}

						

						$('.summary_adv_paid_amt').html(parseFloat(paid_advance)+parseFloat(wt_amt));

						

						$('.summary_adv_paid_weight').html(parseFloat(paid_weight).toFixed(3));

						

						$.each(data.responseData,function(key,item){						

							$('#estimation_tag_details > tbody tr').each(function(bidx, brow){ 

								tag_row = $(this);    

								if( item.tag_id == tag_row.find('.est_tag_id').val()){

								rowExist = true;

								alert('Tag Already Exists');

								return false;

								} 

							});



							if(!rowExist)

								{

								row += '<tr>'

								+'<td><input class="est_tag_name" type="text" name="est_tag[tag_name][]" value='+item.label+' placeholder="Enter tag code" required autocomplete="off"/><input class="est_tag_id" type="hidden" name="est_tag[tag_id][]" value='+item.tag_id+' placeholder="Enter tag code" required /><input class="id_orderdetails" type="hidden" name="est_tag[id_orderdetails][]" value="'+item.id_orderdetails+'"/><input class="orderno" type="hidden" name="est_tag[orderno][]" value="'+item.order_no+'"/><input class="rate_field" type="hidden"  value="'+item.rate_field+'"/><input class="market_rate_field" type="hidden"  value="'+item.market_rate_field+'"/></td>'

								+'<td><input type="checkbox" class="partial"><input type="hidden" class="is_partial"  name="est_tag[is_partial][]"></td>'

								+'<td><div class="prodct_name">'+item.product_name+'</div><input type="hidden" class="pro_id" name="est_tag[pro_id][]" value='+item.product_id+' /><input type="hidden" class="metal_type" value='+item.id_metal+'></td>'

								+'<td><div class="design_name">'+item.design_name+'</div><input type="hidden" class="design_id" name="est_tag[design_id][]" value='+item.design_id+' /></td>'

								+'<td><div class="order_no">'+order_no+'</td>'

								+'<td><div class="purity">'+item.purname+'</div><input type="hidden" class="purity" name="est_tag[purity][]" value='+item.purity+' /></td>'

								+'<td><div class="sizes">'+item.size+'</div><input type="hidden" class="size" name="est_tag[size][]" value="'+item.size+'"/></td>'

								+'<td><div class="pieces">'+item.piece+'</div><input type="hidden" class="piece" name="est_tag[piece][]" value='+item.piece+' /></td>'

								+'<td><input type="text" class="gwt" name="est_tag[gwt][]" step="any" value='+item.gross_wt+' disabled/><input type="hidden" class="cur_gwt" name="est_tag[cur_gwt][]" value="'+item.gross_wt+'"/><input type="hidden" class="act_gwt" value="'+item.gross_wt+'"/></td>'

								+'<td><input type="text" class="lwt" name="est_tag[lwt][]" step="any" value='+item.less_wt+'></td>'

								+'<td><div class="nwt">'+item.net_wt+'</div><input type="hidden" class="tot_nwt" name="est_tag[nwt][]" value='+item.net_wt+' /></td>'

								+'<td><div class="wastage">'+item.retail_max_wastage_percent+'</div><input type="hidden" name="est_tag[wastage][]" class="wastage_max_per" value='+item.retail_max_wastage_percent+' /></td>'

								+'<td><div class="mc">'+item.tag_mc_value+'</div></td>'

								+'<td><div class="cost">'+item.sales_value+'</div><input class="sales_value" type="hidden" name="est_tag[cost][]" value="'+item.sales_value+'" /><input class="tag_item_rate" type="hidden" name="est_tag[item_rate][]" value="" /><input class="caltype" type="hidden" name="est_tag[caltype][]" value="'+item.calculation_based_on+'" /><input class="tgi_calculation" type="hidden" name="est_tag[tgi_calculation][]" value="" /><input class="tax_percentage" type="hidden" name="est_tag[tax_percentage][]" value="" /><input class="tax_group_id" type="hidden" name="est_tag[tax_percentage][]" value="'+item.tax_group_id+'" /><input class="stone_price" type="hidden" name="est_tag[stone_price][]" value="'+item.stn_amt+'" /><input class="certification_price" type="hidden" name="est_tag[certification_price][]" value="" /><input class="id_mc_type" type="hidden" name="est_tag[id_mc_type][]" value="'+item.tag_mc_type+'" /><input class="mc_value" type="hidden" name="est_tag[mc][]" value="'+item.tag_mc_value+'" /><input class="act_mc_value" type="hidden" value="'+item.tag_mc_value+'" /><input class="tax_price" type="hidden" name="est_tag[tax_price][]" value="" /><input type="hidden" class="market_rate_cost" name="est_tag[market_rate_cost][]"><input type="hidden" class="market_rate_tax" name="est_tag[market_rate_tax][]"></td>'

								+'<td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'

								+'</tr>'; 

								}

						}); 

						$('#estimation_tag_details tbody').append(row);

						calculateOrderTag();

					}else{

						alert(data.message);

					}

                }

                });

}





function getCusSearchTags(searchTxt, searchField, curRow){

	my_Date = new Date();

	$.ajax({

        url: base_url+'index.php/admin_ret_estimation/getPartialTagSearch/?nocache=' + my_Date.getUTCSeconds(),             

        dataType: "json", 

        method: "POST", 

        data: {'searchTxt': searchTxt, 'searchField': searchField, 'id_branch': $("#id_branch").val()}, 

        success: function (data) {

			cur_search_tags = data;

			$.each(data, function(key, item){

				$('#estimation_custom_details > tbody tr').each(function(idx, row){

					if(item != undefined){

						if($(this).find('.est_tag_id').val() == item.value){

							data.splice(key, 1);

						}

					}

				});

			});

			$(".cus_tag_name").autocomplete(

			{

				source: data,

				select: function(e, i)

				{ 

					e.preventDefault(); 

					var curRowItem = i.item; 

					if(curRowItem.sales_mode == 2){ // 1 - Fixed Rate, 2 - Flexible

						get_metal_rates_by_branch(i.item.current_branch);

					}

					curRow.find('.cus_tag_name').val(i.item.label);

				    curRow.find('.est_tag_id').val(i.item.value); 

				    curRow.find('.is_partial').val(1); 

					curRow.find('.cus_product').val(curRowItem.product_name);

					curRow.find('.cus_product_id').val(curRowItem.product_id);

					curRow.find('.cus_design').val(curRowItem.design_name);

					curRow.find('.cus_des_id').val(curRowItem.design_id);

					curRow.find('.cus_purity').val(curRowItem.purity);

					curRow.find('.cus_size').val(curRowItem.size);

					curRow.find('.size').val(curRowItem.size);

					curRow.find('.cus_pcs').val(curRowItem.piece);

					curRow.find('.cus_gwt').val(curRowItem.gross_wt);

					curRow.find('.cur_gwt').val(curRowItem.gross_wt);

					curRow.find('.cus_lwt').val(curRowItem.less_wt);

					curRow.find('.cus_nwt').val(curRowItem.net_wt);

					curRow.find('.cus_mc').val(curRowItem.tag_mc_value);

					curRow.find('.cus_mc_type').val(curRowItem.tag_mc_type);

					curRow.find('.id_mc_type').val(curRowItem.tag_mc_type);

					curRow.find('.cus_wastage').val(curRowItem.retail_max_wastage_percent);

					curRow.find(".cus_calculation_based_on").val(curRowItem.calculation_based_on); 

					curRow.find(".metal_type").val(curRowItem.metal_type);

					curRow.find(".tax_group_id").val(curRowItem.tax_group_id);

					curRow.find(".act_gwt").val(curRowItem.gross_wt);

					curRow.find(".act_mc_value").val(curRowItem.tag_mc_value);

					curRow.find(".id_orderdetails").val(curRowItem.id_orderdetails);

					curRow.find(".order_no").html(curRowItem.order_no);

					if(curRowItem.calculation_based_on == 3 || curRowItem.calculation_based_on == 4){

						curRow.find(".cus_partial").prop("disabled",true); 

					}else{

						curRow.find(".cus_partial").prop("disabled",false); 

					}

					

					calculateCustomItemSaleValue();

					/*if(validateTagDetailRow()){

						create_new_empty_est_tag_row();

						$('#estimation_tag_details > tbody').find('tr:last td:eq(0) .est_tag_name').focus();

					}*/

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

						curRow.find('.est_tag_name').val("");

						curRow.find('.est_tag_id').val("");

					}

		        },

				 minLength: 1,

			});

        }

     });

}





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







function get_country()

{

    $('#country option').remove();

    $.ajax({

        type: 'GET',

        url:  base_url+'index.php/settings/company/getcountry',

        dataType: 'json',

        success: function(country) {

            

            $.each(country, function (key, country) 

            {

                if(country.is_default==1)

                {

                    $('#id_country').val(country.id);

                }

                $('#country').append(

                $("<option></option>")

                .attr("value", country.id)

                .text(country.name)

                );

            });

            var id_country=$('#id_country').val();

            $("#country").select2({

            placeholder: "Enter Country",

            allowClear: true

            });	

	        

            get_state($('#id_country').val());

        },

        error:function(error)  

        {

        

         }

    });

}





$('#country').on('change',function(){

    $('#id_country').val(this.value);

    get_state(this.value);

});



$('#state').on('change',function(){

      get_city(this.value);

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

       

        $.each(state, function (key, state) {

            

             if(state.is_default==1)

            {

                $('#id_state').val(state.id);

            }

                

            $('#state').append(

            $("<option></option>")

            .attr("value", state.id)

            .text(state.name)

            );

        });

         var id_state=$('#id_state').val();

         $("#state").select2({

            placeholder: "Enter State",

            allowClear: true

        });	

            

         get_city($('#id_state').val());

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





$('#add_new_customer').on('click',function(){

    $('#confirm-add').modal('show');

});





//Customer Purchase and Accounts



function customer_detail_modal(id_customer)

{

	

	my_Date = new Date();

	$.ajax({

		url: base_url+'index.php/admin_ret_estimation/getCustomerDet/?nocache='+ my_Date.getUTCSeconds(),

		dataType:"json",

		method: "POST",

		data:{'id_customer':id_customer},

		success: function(data){

			

			$('#cus_pop').html(""); 

			$('#customer-popup').modal('show');

			

			$.each(data.cus_details,function(key,item){

				var cusRow="<div class='row'>"+

			"<div class='col-md-12 col-md-offset-1'>"+

				"<div class='row'>"+

					"<div class='col-md-4'>"+

						"<div class='form-group'>"+

							"<label>Customer name</label><br>"

							+ item.firstname +

						"<br></div>"+

						"<div class='form-group'>"+

							"<label>Rating</label><br>"+

							(item.is_vip == 0 ? "-" : "<span class='label bg-orange'><i class='fa fa-fw fa-star'></i> V I P</span>")						

						+"<br></div>"+

						"<div class='form-group'>"+

							"<label>Total Accounts</label><br><span class='badge bg-green'>"

							+ item.tot_account +

						"</span></div>"+

					"</div>"+

					"<div class='col-md-4'>"+

						"<div class='form-group'>"+

							"<label>Active Accounts</label><br><span class='badge bg-green'>"

							+ item.active_acc +

						"</span><br></div>"+

						"<div class='form-group'>"+

							"<label>Closed Accounts</label><br><span class='badge bg-green'>"

							+ item.closed_count +

						"</span><br></div>"+

						"<div class='form-group'>"+

							"<label>Inactive Accounts</label><br><span class='badge bg-green'>"

							+ item.inactive_acount +

						"</span><br></div>"+

					"</div>"+

					"<div class='col-md-4'>"+

						"<div class='form-group'>"+

							"<label>Gold (Grams)</label><br>"

							+ item.gold_wt +

						"<br></div>"+

						"<div class='form-group'>"+

							"<label>Silver (Grams)</label><br>"

							+ item.silver_wt +

						"<br></div>"+

						"<div class='form-group'>"+

							"<label>MRP (Rs)</label><br>"

							+ item.tot_fixed_rate +

						"<br></div>"+

					"</div>"+

			"</div>"+

			

		  "</div>";

		   $("#cus_pop").append(cusRow);

			});

			

			var tablehead="<div class='table-responsive'>"+

				"<table id='bill_list' class='table table-bordered table-striped text-center'>"+

				   "<thead>"+

					 "<tr>"+

					 "<th>Bill Date</th>"+

					   "<th>Bill No</th>"+

					   "<th>Branch</th>"+

					   "<th>Gold Wt</th>"+

					   "<th>Silver Wt</th>"+

					   "<th>MRP Amt</th>"+

					   "<th>Bill Amt</th>"+

					 "</tr>"+

				"</thead>"+

				"<tbody>";

			$.each(data.bill_details,function(key,item){

                tablehead+=

                "<tr>"+

                "<td>"+item.bill_date+"</td>"+

                "<td>"+item.bill_no+"</td>"+

                "<td>"+item.branch_name+"</td>"+

                "<td>"+item.gold_wt+"</td>"+

                "<td>"+item.silver_wt+"</td>"+

                "<td>"+item.mrp_amount+"</td>"+

                "<td>"+item.tot_bill_amount+"</td>"+

                "</tr>";

			});

			tablehead+="</tbody></table></div>";

			

			$('#cus_bill_details').html(tablehead);

			

		}

	});

}



function set_bill_data(id_cus){

	my_Date = new Date();

	$.ajax({

		url: base_url+'index.php/admin_ret_estimation/getCustomerBill/?nocache='+ my_Date.getUTCSeconds(),

		dataType:"json",

		method: "POST",

		data:{'id_cus':id_cus},

		success: function(data){

			

				var tablehead="<div class='table-responsive'>"+

				"<table id='bill_list' class='table table-bordered table-striped text-center'>"+

				   "<thead>"+

					 "<tr>"+

					 "<th>Bill Date</th>"+

					   "<th>Bill No</th>"+

					   "<th>Branch</th>"+

					   "<th>Gold Wt</th>"+

					   "<th>Silver Wt</th>"+

					   "<th>MRP Amt</th>"+

					   "<th>Bill Amt</th>"+

					 "</tr>"+

				"</thead>"+

				"<tbody>";

				$('#cus_pop').append(tablehead);

				

					 $.each(data,function(key,item){ 

					var curRow=

						"<tr>"+

						"<td>"+item.bill_date+"</td>"+

						"<td>"+item.bill_no+"</td>"+

						"<td>"+item.branch_name+"</td>"+

						"<td>"+item.gold_wt+"</td>"+

						"<td>"+item.silver_wt+"</td>"+

						"<td>"+item.mrp_amount+"</td>"+

						"<td>"+item.tot_bill_amount+"</td>"+

						"</tr>";

						

						$('#cus_pop tbody').append(curRow);

			})



			$('#cus_pop').append("</tbody></table></div>");

		}

	});

}



$( "#city_need" ).change(function() {

        if(this.checked){

            $('#city').prop('disabled',false);

        }

        if(!this.checked){

            $('#city').prop('disabled',true);

			$('#city').html("");

        }

        get_city($('#id_state').val());

});



//Customer Purchase and Accounts
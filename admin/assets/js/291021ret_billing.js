var path =  url_params();

var ctrl_page 		= path.route.split('/');

var lot_details 	= [];

var tax_details 	= [];

var purities 		= [];

var cur_search_tags	= [];

var matel_types 	= [];

var stones 			= [];

var materials 		= [];

var chit_details 	 =[];

var giftVoucher_details 	 =[];

var card_payment 	 =[];

var adv_adj_details 	 =[];

var img_resource=[];

var total_files=[];

var redeem_sales_amt=0;

var order_adv_details=[];

$(document).ready(function() {

    

    console.log('mac_id:'+localStorage.getItem("mac_id"));

    $('.dateRangePicker').daterangepicker({ 

	    format: 'DD/MM/YYYY',

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

	switch(ctrl_page[1])

	{

	 	case 'billing':

				switch(ctrl_page[2]){				 	

				 	case 'list':				 	

				 			get_billing_list();

				 		break;

				 	case 'edit':

				 	if($('#bill_type_sales:checked').val()==1)

					{

						$('.search_esti').css('display','block');

						$('.search_tag').css('display','block');

						$('.search_order').css('display','block');

						$('.search_bill').css('display','none');

						$('.sale_details').css('display','block');

						$('.return_details').css('display','none');

						$('.purchase_details').css('display','none');

						$('.order_adv_details').css('display','none');

					}

					else if($('#bill_type_sales:checked').val()==2)

					{

						$('.search_esti').css('display','block');

						$('.search_tag').css('display','block');

						$('.search_order').css('display','block');

						$('.search_bill').css('display','none');

						$('.sale_details').css('display','block');

						$('.return_details').css('display','none');

						$('.purchase_details').css('display','block');

						$('.order_adv_details').css('display','none');

					}

					else if($('#bill_type_sales:checked').val()==3)

					{

						$('.search_esti').css('display','block');

						$('.search_tag').css('display','block');

						$('.search_order').css('display','block');

						$('.search_bill').css('display','block');

						$('.sale_details').css('display','block');

						$('.return_details').css('display','block');

						$('.purchase_details').css('display','block');

						$('.order_adv_details').css('display','none');

					}

					else if($('#bill_type_sales:checked').val()==4)

					{

						$('.search_esti').css('display','block');

						$('.search_tag').css('display','none');

						$('.search_order').css('display','none');

						$('.search_bill').css('display','none');

						$('.sale_details').css('display','none');

						$('.return_details').css('display','none');

						$('.purchase_details').css('display','block');

						$('.order_adv_details').css('display','none');

					}

					else if($('#bill_type_sales:checked').val()==5)

					{

						$('.search_esti').css('display','block');

						$('.search_tag').css('display','none');

						$('.search_order').css('display','block');

						$('.search_bill').css('display','none');

						$('.sale_details').css('display','none');

						$('.return_details').css('display','none');

						$('.purchase_details').css('display','none');

						$('.order_adv_details').css('display','block');

					}

					else if($('#bill_type_sales:checked').val()==6)

					{

			        	$(".search_bill").css("display","none");

			        	$(".search_order").css("display","none");

						$(".search_esti").css("display","none");

						$(".search_tag").css("display","none");

						$('.sale_details').css('display','none');

						$('.return_details').css('display','none');

						$('.purchase_details').css('display','none');

						$('.order_adv_details').css('display','none');

						$('.total_summary_details').css('display','none');

					}

					else if($('#bill_type_sales:checked').val()==7)

					{

						$(".search_bill").css("display","block");

			        	$(".search_order").css("display","none");

						$(".search_esti").css("display","none");

						$(".search_tag").css("display","none");

						$('.sale_details').css('display','none');

						$('.return_details').css('display','block');

						$('.purchase_details').css('display','none');

						$('.order_adv_details').css('display','none');

						$('.total_summary_details').css('display','block');

						$('.from_date').css('display','block');

					}

					break;

				 	case 'add':

				 	    

				 	    get_delivery_details();

				 		hide_page_open_details();

				 		$('#payment_list1').empty();

						$('#payment_list2').empty();

						$('#payment_list1').text(moment().startOf('month').format('YYYY-MM-DD'));

						$('#payment_list2').text(moment().endOf('month').format('YYYY-MM-DD'));	

						$('#payment-dt-btn').daterangepicker(

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

						if($('#id_branch').val() != "")

						{

							if($('#bill_cus_id').val()!='')

							{

								getBillDetails_DateFilter(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'))

							}

							else

							{

								alert('Select Customer');

							}

						}

						else

						{

							alert('Select Branch');

						}

						$('#payment_list1').text(start.format('YYYY-MM-DD'));

						$('#payment_list2').text(end.format('YYYY-MM-DD')); 

						}

						); 

				 		break;

				}

				case 'receipt':

					switch(ctrl_page[2]){				 	

				 	case 'list':

				 		set_receipt_list();

				 	break;

				 	}

				break;

				case 'issue':

					switch(ctrl_page[2]){				 	

				 	case 'list':

				 		set_issue_list();

				 	break;

				 	}

				break;

	 		break; 

	}

	get_taxgroup_items();

	get_tag_purities();

	get_tag_matels();

	get_stones();

	get_materials();

	

	$('#bill_search').click(function(event) {

		get_billing_list();

	});

	

	$('#pay_submit').on('click',function(){

	    $("div.overlay").css("display", "block"); 

	    var is_counter_req=$('#is_counter_req').val();

	    var counter_id=$('#counter_id').val();

		var mac_id = localStorage.getItem("mac_id");

		var allow_submit=true;

		

		if(is_counter_req==1)

		{

		     if(counter_id=='' || counter_id==null ||  counter_id==undefined)

		    {

		        allow_submit=false;

		        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>Unable to Set The MAC Address For Your System.Please Contact Your Admin.."});

		        

		    }else{

		        allow_submit=true;

		    }

		}

		

		if(($('#bill_cus_id').val()!='') && allow_submit==true)

		{

		    var form_data=$('#bill_pay').serialize();

			$('#pay_submit').prop('disabled',true);

			var url=base_url+ "index.php/admin_ret_billing/billing/save?nocache=" + my_Date.getUTCSeconds();

		    $.ajax({ 

		        url:url,

		        data: form_data,

		        type:"POST",

		        dataType:"JSON",

		        success:function(data){

					if(data.status)

					{

					    $("div.overlay").css("display", "none"); 

						window.open( base_url+'index.php/admin_ret_billing/billing_invoice/'+data['id'],'_blank');

					}

					window.location.reload();

					$("div.overlay").css("display", "none"); 

		        },

		        error:function(error)  

		        {	

		        $("div.overlay").css("display", "none"); 

		        } 

		    });

		}else{

			$('#pay_submit').prop('disabled',false);

			$("div.overlay").css("display", "none"); 

		}		

	});

	if($('#id_branch').val() != ""){

		get_metal_rates_by_branch();

		get_branch_details();

	}

	if(ctrl_page[2]='edit' && ($('#bill_type_sales:checked').val()!=6))

	{

		setTimeout(function(){

			calculateSaleBillRowTotal();

			calculatePurchaseBillRowTotal();

			calculate_salesReturn_details();

		},1000);

	}

	$('#add_new_customer').on('click',function(e){

		get_village_list();

	});

	$('#select_Allsale').click(function(event) {

	$("#est_items_to_sale_convertion tbody tr td .select_est_details").prop('checked', $(this).prop('checked'));

	var item_pcs=0;

		var item_gwt=0;

		var total_pcs=$('#blc_pcs').html();

		var total_gwt=$('#blc_gwt').html();

		$('#est_items_to_sale_convertion > tbody tr').each(function(idx, row){

			curRow = $(this);

			if(curRow.find('.select_est_details').is(':checked'))

			{

				if(curRow.find('.is_non_tag').val()==1)

				{	

					item_pcs+= parseFloat(curRow.find('.est_pcs').val());

					item_gwt+=parseFloat(curRow.find('.est_gross_val').val());

				}

			}

		});

		if(item_pcs<=total_pcs || item_gwt<=total_gwt)

		{

			$('#update_estimation_to_bill').attr('disabled',false);

		}else{

			$('#update_estimation_to_bill').attr('disabled',true);	

		}

		$('#tot_pcs').html(item_pcs);

		$('#tot_wt').html(item_gwt);

	event.stopPropagation();

	});

	$('#select_Allpur').click(function(event) {

	$("#est_olditems_to_sale_convertion tbody tr td input[type='checkbox']").prop('checked', $(this).prop('checked'));

	event.stopPropagation();

	});

	$('#pan_no').on('change',function(){

			if($("#pan_no").val() != ""){

				var regexp = /^[a-zA-Z]{5}\d{4}[a-zA-Z]{1}$/;

				if(!regexp.test($("#pan_no").val()))

		    	{

		    		 $("#pan_no").val("");

		    		 alert("Not a valid PAN No.");

		    		 $("#pan_no").focus();

		    	}

			}else{

				alert("Enter valid PAN No.");

			}

			calculatePaymentCost();

		});

	//Village

	function get_village_list()

	{

		my_Date = new Date();

		$.ajax({ 

		url:base_url+ "index.php/admin_ret_estimation/ajax_get_village?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),

        type:"GET",

        dataType:"JSON",

        success:function(data)

        {

           var id_village=$('#id_village').val();

           $.each(data, function (key, item) {					  				  			   		

                	 	$("#sel_village").append(						

                	 	$("<option></option>")						

                	 	.attr("value", item.id_village)						  						  

                	 	.text(item.village_name)						  					

                	 	);			   											

                 	});						

             	$("#sel_village").select2({			    

            	 	placeholder: "Select Village",			    

            	 	allowClear: true		    

             	});					

         	    $("#sel_village").select2("val",(id_village!='' && id_village>0?id_village:''));	 

         	    $(".overlay").css("display", "none");	

        },

        error:function(error)  

        {	

        } 

    	});

	}

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

	//Village

	if($('#credit_due_date').length>0)

	{

		$('#credit_due_date').datepicker({

               format: 'yyyy-mm-dd'

                })

            .on('changeDate', function(ev){

            $(this).datepicker('hide');

            });

	}

	 $("input[name='billing[is_credit]']:radio").on('change',function(){

		   if($(this).val()==0)

		   {

		   		$('#credit_due_date').prop('disabled',true);

		   }

		   else

		   {

		   		$('#credit_due_date').prop('disabled',false);

		   }

	});

	

	$("input[name='billing[sale_store_as]']:radio").on('change',function(){

	 	if(this.value==1)

	 	{

	 		$('#rate_calc1').prop('disabled',true);

	 		$('#rate_calc2').prop('disabled',true);

	 	}else{

	 		$('#rate_calc1').prop('disabled',false);

	 		$('#rate_calc2').prop('disabled',false);

	 	}

	 });

	 

	 

	$("input[name='billing[bill_type]']:radio").on('change',function(){

		$(".search_bill").css("display","none");

		$(".advance_amt").css("display","none");

		$(".search_order").css("display","block");

		$(".search_esti").css("display","block");

		$(".search_tag").css("display","block");

		$(".search_order").css("display","none");

		

		$('#card_detail_modal').attr('disabled',false);

        $('#adv_adj_modal').attr('disabled',false);

        $('#cheque_modal').attr('disabled',false);

        $('#cheque_modal').attr('disabled',false);

        $('#is_credit_no').attr('disabled',false);

        $('#is_credit_yes').attr('disabled',false);

        //$('#gift_voucher_modal').attr('disabled',false);

        $('#net_banking_modal').attr('disabled',false);

		

        if(this.value == 1) { // Sales

            $(".sale_details").show();

            $(".date_filter").css("display","none");

            $(".total_summary_details").css("display","block");

            $(".summary_adv_details").css("display","none");

            //$('#gift_voucher_modal').attr('disabled',false);

			if($('#billing_sale_details tbody tr').length == 0){

				create_new_empty_bill_sale_row();//Create new empty bill sale empty row

			}

			$(".purchase_details,.return_details,.order_adv_details,.custom_details,.old_matel_details,.advance_amt").hide();  

			

			$("#credit_discount").css("display","none");

        	$("#sale_discount").css("display","block");

        }

        else if(this.value == 2) { // Sales & Purchase

            $(".sale_details,.purchase_details").show();

            $(".date_filter").css("display","none");

            $(".summary_adv_details").css("display","none");

            $(".total_summary_details").css("display","block");

            //$('#gift_voucher_modal').attr('disabled',false);

            $(".custom_details,.order_adv_details,.return_details,.old_matel_details,.old_matel_details,.stone_details,.material_details,.advance_amt").hide();  

			if($('#billing_sale_details tbody tr').length == 0){

				create_new_empty_bill_sale_row();//Create new empty bill sale empty row

				create_new_empty_est_catalog_row();

			}

			$("#credit_discount").css("display","none");

        	$("#sale_discount").css("display","block");

        }

        else if(this.value == 3) { // Sales & Return

        	$(".search_bill").css("display","block");

        	$(".date_filter").css("display","none");

            $(".sale_details,.return_details").show();

            $(".summary_adv_details").css("display","none");

            $(".total_summary_details").css("display","block");

            $(".purchase_details").show();

            $(".order_adv_details,.custom_details,.advance_amt").hide(); 

            //$('#gift_voucher_modal').attr('disabled',false);

			if($('#billing_sale_details tbody tr').length == 0){

				create_new_empty_bill_sale_row();//Create new empty bill sale empty row

			}

			$("#credit_discount").css("display","none");

        	$("#sale_discount").css("display","block");

        }

        else if(this.value == 4) { // Purchase

        	$(".search_order").css("display","none");

        	 $(".total_summary_details").css("display","block");

        	$(".search_tag").css("display","none");

        	$(".date_filter").css("display","none");

            $(".purchase_details").show();

            $(".summary_adv_details").css("display","none");

            $('#gift_voucher_modal').attr('disabled',true);

            $(".sale_details,.order_adv_details,.return_details,.custom_details,.advance_amt").hide();  

			if($('#billing_sale_details tbody tr').length == 0){

				create_new_empty_bill_sale_row();//Create new empty bill sale empty row

			}

			$("#credit_discount").css("display","none");

        	$("#sale_discount").css("display","block");

        }

        else if(this.value == 5) { // Order Advance 

            $('.receive_amount').prop('readonly',true);

            $(".purchase_details").show();

            $(".order_adv_details").show(); 

            $(".search_tag").css("display","none");

            $(".date_filter").css("display","none");

            $(".total_summary_details").css("display","none");

            

             $('#gift_voucher_modal').attr('disabled',true);

             $(".search_order").css("display","block");

            $(".purchase_details,.sale_details,.return_details,.custom_details,.advance_amt").hide();  

			if($('#billing_sale_details tbody tr').length == 0){

				create_new_empty_bill_sale_row();//Create new empty bill sale empty row

			}

			$("#credit_discount").css("display","none");

        	$("#sale_discount").css("display","block");

        }

        else if(this.value == 6) { // Advance 

        	$(".advance_amt").css("display","block");

        	 $(".total_summary_details").css("display","block");

        	$(".search_bill").css("display","none");

        	$(".search_order").css("display","none");

			$(".search_esti").css("display","none");

			$(".search_tag").css("display","none");

			$(".date_filter").css("display","none");

            $(".purchase_details").show();

            $(".summary_adv_details").css("display","none");

            $('#gift_voucher_modal').attr('disabled',true);

            $(".total_summary_details,.purchase_details,.order_adv_details,.sale_details,.return_details,.custom_details").hide();  

			if($('#billing_sale_details tbody tr').length == 0){

				create_new_empty_bill_sale_row();//Create new empty bill sale empty row

			}

			$("#credit_discount").css("display","none");

        	$("#sale_discount").css("display","block");

        }

        else if(this.value == 7) { // Sales Return

        	$(".search_bill").css("display","block");

        	$(".total_summary_details").css("display","block");

        	$(".date_filter").css("display","block");

        	$(".search_order").css("display","none");

        	$(".search_esti").css("display","none");

        	$(".search_tag").css("display","none");

            $(".purchase_details").show();

            $(".summary_adv_details").css("display","none");

            $('#gift_voucher_modal').attr('disabled',true);

            $(".purchase_details,.order_adv_details,.sale_details,.return_details,.custom_details,.advance_amt").hide();  

			if($('#billing_sale_details tbody tr').length == 0){

				create_new_empty_bill_sale_row();//Create new empty bill sale empty row

			}

			$("#credit_discount").css("display","none");

        	$("#sale_discount").css("display","block");

        }else if(this.value == 8)

        {

            $(".search_bill").css("display","block");

        	$(".search_order").css("display","none");

        	$(".search_esti").css("display","block");

        	$(".search_tag").css("display","none");

            $(".total_summary_details").css("display","block");

        	$(".date_filter").css("display","none");

        	$(".purchase_details").show();

        	$(".summary_adv_details").css("display","none");

        	$('#gift_voucher_modal').attr('disabled',true);

            $(".order_adv_details,.sale_details,.return_details,.custom_details,.advance_amt").hide(); 

            $("#credit_discount").css("display","block");

        	$("#sale_discount").css("display","none");

        }

        else if(this.value == 9)

        {

           $(".search_esti").css("display","block");

            $(".search_tag").css("display","none");

            $(".search_bill").css("display","none");

            $(".search_order").css("display","none");

            $(".total_summary_details").css("display","block");

            $(".sale_details").show();

            $("#credit_discount").css("display","none");

        	$("#sale_discount").css("display","block");

        }else if(this.value == 10)

        {

            $(".search_esti").css("display","none");

            $(".search_tag").css("display","none");

            $(".search_bill").css("display","none");

            $(".search_order").css("display","none");

            $(".total_summary_details").css("display","none");

            $('#card_detail_modal').attr('disabled',true);

            $('#card_detail_modal').attr('disabled',true);

            $('#adv_adj_modal').attr('disabled',true);

            $('#cheque_modal').attr('disabled',true);

            $('#cheque_modal').attr('disabled',true);

            $('#is_credit_no').attr('disabled',true);

            $('#is_credit_yes').attr('disabled',true);

            $('#gift_voucher_modal').attr('disabled',true);

            $('#net_banking_modal').attr('disabled',true);

            $("#credit_discount").css("display","none");

        	$("#sale_discount").css("display","block");

        }

        else{ 

			$(".sale_details,.purchase_details,.custom_details,.advance_amt").hide();  

		}

    });

    

    

	$('#create_sale_details').on('click', function(){

		if(validateSaleDetailRow()){

			create_new_empty_bill_sale_row();

		}else{

			alert("Please fill required fields");

		}

	}); 

	$('#create_purchase_details').on('click', function(){

		if(validateCatalogDetailRow()){

			create_new_empty_est_catalog_row();

		}else{

			alert("Please fill required fields");

		}

	});

	$("#search_est_no").on('click', function(){

		var bill_type = $("input[name='billing[bill_type]']:checked").val();

		if($('#filter_est_no').val() != "" && $('#id_branch').val() != "" && bill_type != ""){

		    if(bill_type==5 && $('#filter_order_no').val()!='')

		    {

		        $(".summary_adv_details").css("display","block");

		        getEstimationDetails($('#filter_est_no').val(),'', bill_type);

		    }

		    if(bill_type!=5)

		    {

		        getEstimationDetails($('#filter_est_no').val(),'', bill_type);

		    }

		    

			

		}else{

			if($('#id_branch').val() == ""){

				$("#branchAlert").append("<span>Choose Branch</span>");

				$('#branchAlert span').delay(10000).fadeOut(500); 

				$('#id_branch').focus();

			}else if($('#filter_est_no').val() == ""){

				$("#searchEstiAlert").append("<span>Enter Estimation No.</span>");

				$('#searchEstiAlert span').delay(10000).fadeOut(500); 

				$('#filter_order_no').focus();

			}

		}

	});

	

	

	$('#update_estimation_to_bill').on('click', function(){

		var bill_type = $("input[name='billing[bill_type]']:checked").val(); 

		$('#est_items_to_sale_convertion > tbody tr').each(function(idx, row){

			est_sale_row = $(this);

			var rowExist = false;

			if(est_sale_row.find('td:first .select_est_details').is(':checked') ){

				if(bill_type != 5 ){

					$(".sale_details").show();

					$('#billing_sale_details > tbody tr').each(function(bidx, brow){

						bill_sale_row = $(this);

						// CHECK DUPLICATES - TAG

						if(bill_sale_row.find('.sale_tag_id').val() != ''){

							if( est_sale_row.find('.est_tag_id').val() == bill_sale_row.find('.sale_tag_id').val()){

								rowExist = true; 

								/*console.log("Tag ID - "+bidx+" : From Modal"+est_sale_row.find('td:first .est_tag_id').val()+" From Bill"+bill_sale_row.find('td:eq(15) .sale_tag_id').val());

								console.log(rowExist);*/

							} 

						}

						// CHECK DUPLICATES - ESTIMATION ITEM

						if(bill_sale_row.find('td:first .is_est_details').val() == 1 )

						{ 

							if(est_sale_row.find('td:first .est_itm_id').val() == bill_sale_row.find('td:first .est_itm_id').val()){

								rowExist = true;

								/*console.log("Esti ID - "+bidx+" : From Modal"+est_sale_row.find('td:first .est_itm_id').val()+" From Bill"+est_sale_row.find('td:first .est_itm_id').val());

								console.log(rowExist);*/

							}

						}

					});

					console.log(rowExist);

					if(!rowExist){

						var row_st_details=est_sale_row.find('.est_item_stone_dt').val();

						var stone_details=JSON.parse(row_st_details);

						var tot_length=$('#billing_sale_details tbody tr').length;

						if(est_sale_row.find('.select_est_partial').is(':checked')){

							var row = '<tr id="'+tot_length+'">'

								+'<td><span>'+est_sale_row.find('td:first .est_hsn').val()+'</span><input type="hidden" class="sale_pro_hsn" name="sale[hsn]" value="'+est_sale_row.find('td:first .est_hsn').val()+'" /><input type="hidden" class="sale_type" name="sale[sourcetype][]" value="1" /><input type="hidden" class="sale_item_type" name="sale[itemtype][]" value="'+est_sale_row.find('td:first .est_item_type').val()+'" /><input type="hidden" class="is_est_details" value="1" name="sale[is_est_details][]" /><input type="hidden" class="est_itm_id" name="sale[est_itm_id][]" value="'+est_sale_row.find('td:first .est_itm_id').val()+'" /><input type="hidden" class="sale_cal_type" name="sale[calltype][]" value="'+est_sale_row.find('td:first .est_cal_type').val()+'" /><input type="hidden" class="sale_metal_type" value="'+est_sale_row.find('td:first .est_metal_type').val()+'" /><input type="hidden" class="sale_purity" value="'+est_sale_row.find('.est_purid').val()+'"  name="sale[purity][]" /><input type="hidden" class="sale_size" value="'+est_sale_row.find('.est_size_val').val()+'"  name="sale[size][]" /><input type="hidden" class="is_partial" value="'+est_sale_row.find('.select_est_partial').val()+'"  name="sale[is_partial][]" /><input type="hidden" class="total_tax" name="sale[item_total_tax][]"><input type="hidden" class="sale_uom" value="'+est_sale_row.find('td:eq(0) .est_uom').val()+'"  name="sale[uom][]" /><input type="hidden" class="min_wastage" value="'+est_sale_row.find('.min_wastage').val()+'"  name="sale[min_wastage][]" /><input type="hidden" class="max_wastage" value="'+est_sale_row.find('.max_wastage').val()+'"  name="sale[max_wastage][]" /><input type="hidden" class="wastage_dis" value="0" /><input type="hidden" class="stock_type" name="sale[stock_type][]" value="'+est_sale_row.find('.stock_type').val()+'" /><input type="hidden" class="is_non_tag" name="sale[is_non_tag][]" value="'+est_sale_row.find('.is_non_tag').val()+'" /><input type="hidden" name="sale[id_orderdetails][]" class="id_orderdetails" value="'+est_sale_row.find('.id_orderdetails').val()+'"></td>'

								+'<td><span>'+est_sale_row.find('.est_product_name').html()+'</span><input class="sale_product_id" type="hidden" name="sale[product][]" value="'+est_sale_row.find('.est_product_id').val()+'" /></td>'

								+'<td><span>'+est_sale_row.find('.est_design_code').html()+'</span><input type="hidden" class="sale_design_id" name="sale[design][]" value="'+est_sale_row.find('.est_design_id').val()+'" /></td>'

								+'<td><span>'+est_sale_row.find('.est_piece').html()+'</span><input type="hidden" class="sale_pcs" name="sale[pcs][]" value="'+est_sale_row.find('.est_pcs').val()+'"  /></td>'

								+'<td><input type="number" class="sale_gwt" value="'+est_sale_row.find('.est_gross_wt').html()+'" style="width:80px;"><input type="hidden" class="bill_gross_val" name="sale[gross][]" value="'+est_sale_row.find('.est_gross_val').val()+'" /></td>'

								+'<td><input type="number" class="sale_lwt" value="'+est_sale_row.find('.est_less_wt').html()+'" style="width:80px;"><input type="hidden" class="bill_less_val" name="sale[less][]" value="'+est_sale_row.find('.est_less_val').val()+'" /></td>'

								+'<td><input type="number" class="sale_nwt" value="'+est_sale_row.find('.est_net_wt').html()+'" style="width:80px;"><input type="hidden" class="bill_net_val" name="sale[net][]" value="'+est_sale_row.find('.est_net_val').val()+'" /></td>'

								+'<td><span class="est_wastage">'+est_sale_row.find('.est_wastage').html()+'</span><input type="hidden" class="bill_wastage" name="sale[wastage][]" value="'+est_sale_row.find('.est_wastage_percent').val()+'" /><input type="hidden" class="bill_wastage_per" name="sale[wastage][]" value="'+est_sale_row.find('.est_wastage_percent').val()+'" /></td>'

							    +'<td><span class="est_wastage_wt">'+est_sale_row.find('.est_wastage_wt').html()+'</span><input type="hidden" class="bill_wastage_wt" value="'+est_sale_row.find('.est_wastage_wt').html()+'"></td>'

								+'<td><span class="making_charge">'+est_sale_row.find('.est_mc').html()+'</span><input type="hidden" class="mc_dis" value="0"><input type="hidden" class="bill_mctype" name="sale[bill_mctype][]" value="'+est_sale_row.find('.est_mc_type').val()+'" /><input type="hidden" class="bill_mc" name="sale[mc][]" value="'+est_sale_row.find('.est_mc_value').val()+'" /><input type="hidden" class="discount" name="sale[adjusted_dis][]" value="" /><input type="hidden" class="bill_mc_value" value="'+est_sale_row.find('.est_mc_value').val()+'" /></td>'

								+'<td><input type="number" class="bill_discount" name="sale[discount][]"  step="any" readonly/></td>'

								+'<td><span class="bill_taxable_amt"></span></td>'

								+'<td><span>'+est_sale_row.find('.est_tgrp_name').html()+'</span><input type="hidden" class="sale_tax_group" name="sale[taxgroup][]" value="'+est_sale_row.find('.est_tax_id').val()+'" /></td>'

								+'<td></td>'

								+'<td>'+(stone_details.length>0 ? '<a href="#" onClick="create_new_empty_bill_sales_stone_item($(this).closest(\'tr\'));" class="btn btn-success"><i class="fa fa-plus"></i></a>' :'-')+'<input type="hidden" class="stone_details" value='+est_sale_row.find('.est_item_stone_dt').val()+' name="sale[stone_details][]"/><input type="hidden" class="bill_stone_price" value="'+est_sale_row.find('.est_stone_price').val()+'" /><input type="hidden" class="certification_cost" value="'+est_sale_row.find('.est_certification_price').val()+'" /><input type="hidden" class="bill_material_price" value="'+est_sale_row.find('.est_material_price').val()+'"/></td>'

								+'<td><input type="number" class="bill_amount" name="sale[billamount][]" value="" step="any" readonly /><input type="hidden" class="per_grm_amount" name="sale[per_grm][]" value="" step="any" /></td>'

								+'<td>Yes</td>'

								+'<td><span>'+est_sale_row.find('td:first .est_tag_id').val()+'</span><input type="hidden" class="sale_tag_id" name="sale[tag][]" value="'+est_sale_row.find('td:first .est_tag_id').val()+'" /><input type="hidden" name="sale[total_cgst][]" class="sale_cgst"/><input type="hidden" class="sale_sgst" name="sale[total_sgst][]"/><input type="hidden" class="sale_igst" name="sale[total_igst][]"/></td>'

								+'<td>-</td>'

								+'<td><span>'+est_sale_row.find('td:first .est_itm_id').val()+'</span><input type="hidden" class="sale_est_itm_id" name="sale[estid][]" value="'+est_sale_row.find('td:first .est_itm_id').val()+'" /></td>'

								+'<td><a href="#" onClick="remove_row($(this).closest(\'tr\'));" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'

								+'</tr>';

						}else{  

							var row = '<tr id="'+tot_length+'">'

								+'<td><span>'+est_sale_row.find('td:first .est_hsn').val()+'</span><input type="hidden" name="sale[id_orderdetails][]" class="id_orderdetails" value="'+est_sale_row.find('.id_orderdetails').val()+'"><input type="hidden" class="sale_pro_hsn" name="sale[hsn]" value="'+est_sale_row.find('td:first .est_hsn').val()+'" /><input type="hidden" class="sale_type" name="sale[sourcetype][]" value="1" /><input type="hidden" class="sale_item_type" name="sale[itemtype][]" value="'+est_sale_row.find('td:first .est_item_type').val()+'" /><input type="hidden" class="is_est_details" value="1" name="sale[is_est_details][]" /><input type="hidden" class="est_itm_id" name="sale[est_itm_id][]" value="'+est_sale_row.find('td:first .est_itm_id').val()+'" /><input type="hidden" class="sale_cal_type" name="sale[calltype][]" value="'+est_sale_row.find('td:first .est_cal_type').val()+'" /><input type="hidden" class="sale_metal_type" value="'+est_sale_row.find('td:first .est_metal_type').val()+'" /><input type="hidden" class="sale_purity" value="'+est_sale_row.find('.est_purid').val()+'"  name="sale[purity][]" /><input type="hidden" class="sale_size" value="'+est_sale_row.find('.est_size_val').val()+'"  name="sale[size][]" /><input type="hidden" class="sale_uom" value="'+est_sale_row.find('td:eq(0) .est_uom').val()+'"  name="sale[uom][]" /><input type="hidden" class="total_tax" name="sale[item_total_tax][]"><input type="hidden" class="is_partial" value="0"  name="sale[is_partial][]" /><input type="hidden" class="min_wastage" value="'+est_sale_row.find('.min_wastage').val()+'"  name="sale[min_wastage][]" /><input type="hidden" class="max_wastage" value="'+est_sale_row.find('.max_wastage').val()+'"  name="sale[max_wastage][]" /><input type="hidden" class="stock_type" name="sale[stock_type][]" value="'+est_sale_row.find('.stock_type').val()+'" /><input type="hidden" class="is_non_tag" name="sale[is_non_tag][]" value="'+est_sale_row.find('.is_non_tag').val()+'" /></td>'

								+'<td><span>'+est_sale_row.find('.est_product_name').html()+'</span><input class="sale_product_id" type="hidden" name="sale[product][]" value="'+est_sale_row.find('.est_product_id').val()+'" /></td>'

								+'<td><span>'+est_sale_row.find('.est_design_code').html()+'</span><input type="hidden" class="sale_design_id" name="sale[design][]" value="'+est_sale_row.find('.est_design_id').val()+'" /></td>'

								+'<td><span>'+est_sale_row.find('.est_piece').html()+'</span><input type="hidden" class="sale_pcs" name="sale[pcs][]" value="'+est_sale_row.find('.est_pcs').val()+'"  /></td>'

								+'<td><span>'+est_sale_row.find('.est_gross_wt').html()+'</span><input type="hidden" class="bill_gross_val" name="sale[gross][]" value="'+est_sale_row.find('.est_gross_val').val()+'" /></td>'

								+'<td><span>'+est_sale_row.find('.est_less_wt').html()+'</span><input type="hidden" class="bill_less_val" name="sale[less][]" value="'+est_sale_row.find('.est_less_val').val()+'" /></td>'

								+'<td><span>'+est_sale_row.find('.est_net_wt').html()+'</span><input type="hidden" class="bill_net_val" name="sale[net][]" value="'+est_sale_row.find('.est_net_val').val()+'" /></td>'

								+'<td><span class="est_wastage">'+est_sale_row.find('.est_wastage').html()+'</span><input type="hidden" class="bill_wastage" name="sale[wastage][]" value="'+est_sale_row.find('.est_wastage_percent').val()+'" /><input type="hidden" class="bill_wastage_per" name="sale[wastage][]" value="'+est_sale_row.find('.est_wastage_percent').val()+'" /></td>'

								+'<td><span class="est_wastage_wt">'+est_sale_row.find('.est_wastage_wt').html()+'</span><input type="hidden" class="bill_wastage_wt" value="'+est_sale_row.find('.est_wastage_wt').html()+'"></td>'

								+'<td><span class="making_charge">'+est_sale_row.find('.est_mc').html()+'</span><input type="hidden" class="mc_dis" value="0"><input type="hidden" class="bill_mctype" name="sale[bill_mctype][]" value="'+est_sale_row.find('.est_mc_type').val()+'" /><input type="hidden" class="bill_mc" name="sale[mc][]" value="'+est_sale_row.find('.est_mc_value').val()+'" /><input type="hidden" class="discount"  name="sale[adjusted_dis][]" value="" /><input type="hidden" class="bill_mc_value" value="'+est_sale_row.find('.est_mc_value').val()+'" /></td>'

								+'<td><input type="number" class="bill_discount" name="sale[discount][]" value="" step="any" readonly/></td>'

								+'<td><span class="bill_taxable_amt"></span></td>'

								+'<td><span>'+est_sale_row.find('.est_tgrp_name').html()+'</span><input type="hidden" class="sale_tax_group" name="sale[taxgroup][]" value="'+est_sale_row.find('.est_tax_id').val()+'" /></td>'

								+'<td></td>'

								+'<td>'+(stone_details.length>0 ?'<a href="#" onClick="create_new_empty_bill_sales_stone_item($(this).closest(\'tr\'));" class="btn btn-success"><i class="fa fa-plus"></i></a>' :'-')+'<input type="hidden" class="stone_details" value='+est_sale_row.find('.est_item_stone_dt').val()+' name="sale[stone_details][]"/><input type="hidden" class="bill_stone_price" value="'+est_sale_row.find('.est_stone_price').val()+'" /><input type="hidden" class="certification_cost" value="'+est_sale_row.find('.est_certification_price').val()+'" /><input type="hidden" class="est_old_stone_val" value="'+est_sale_row.find('.est_old_stone_val').val()+'" /><input type="hidden" class="est_old_dust_val" value="'+est_sale_row.find('.est_old_dust_val').val()+'" /><input type="hidden" class="bill_material_price" value="'+est_sale_row.find('.est_material_price').val()+'"/></td>'

								+'<td><input type="number" class="bill_amount" name="sale[billamount][]" value="" step="any" readonly /><input type="hidden" class="per_grm_amount" name="sale[per_grm][]" value="" step="any" /></td>'

								+'<td>No</td>'

								+'<td><span>'+est_sale_row.find('td:first .est_tag_id').val()+'</span><input type="hidden" class="sale_tag_id" name="sale[tag][]" value="'+est_sale_row.find('td:first .est_tag_id').val()+'" /><input type="hidden" name="sale[total_cgst][]" class="sale_cgst"/><input type="hidden" class="sale_sgst" name="sale[total_sgst][]"/><input type="hidden" class="sale_igst" name="sale[total_igst][]"/></td>'

								+'<td>-</td>'

								+'<td><span>'+est_sale_row.find('td:first .est_itm_id').val()+'</span><input type="hidden" class="sale_est_itm_id" name="sale[estid][]" value="'+est_sale_row.find('td:first .est_itm_id').val()+'" /></td>'

								+'<td><a href="#" onClick="remove_row($(this).closest(\'tr\'));" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'

								+'</tr>';

						}

						$('#billing_sale_details tbody').append(row);

					}

				}else{

					updateEstiOrderdetailsInBill(est_sale_row);

				}

			}

		});

		

		

		$('#est_olditems_to_sale_convertion > tbody tr').each(function(idx, row){

			old_est_sale_row = $(this);

			var rowExist = false;

			if(old_est_sale_row.find('td:first .select_est_old_itm_details').is(':checked') ){

				//$('#bill_type_purchase').prop('checked', true);

				$(".purchase_details").show();

				$('#purchase_item_details > tbody tr').each(function(bidx, brow){

					bill_pur_row = $(this);

					if(bill_pur_row.find('.is_est_details').val() == 1 ){ //1 -> From estimation

						if(old_est_sale_row.find('.est_old_itm_id').val() == bill_pur_row.find('.est_itm_id').val()){

							rowExist = true;

						}

					}

				});

				/*<td><span>'+old_est_sale_row.find('td:eq(3) .est_old_item_pur').html()+'</span><input type="hidden" class="pur_pur_id" name="purchase[purity][]" value="'+old_est_sale_row.find('td:eq(3) .est_old_item_purid').val()+'" /></td>*/

				if(!rowExist){

					var tot_length=$('#purchase_item_details tbody tr').length;

					var row = '<tr id="'+tot_length+'">'

									+'<td><span>'+(old_est_sale_row.find('.est_purpose').val()== 1 ? "Cash" : "Exchange")+'</span></td>'

									+'<td><span>'+old_est_sale_row.find('td:eq(2) .est_old_item_metal').html()+' - '+old_est_sale_row.find('td:eq(1) .est_purpose').val()+'</span><input type="hidden" class="is_est_details" value="1" name="purchase[is_est_details][]" /><input type="hidden" class="est_old_itm_id" value="'+old_est_sale_row.find('td:first .est_old_itm_id').val()+'" name="purchase[est_old_itm_id][]" /><input type="hidden" name="purchase[est_itm_id][]" class="est_itm_id" value="'+old_est_sale_row.find('td:first .est_old_itm_id').val()+'" /><input type="hidden" class="item_type" name="purchase[itemtype][]" value="2" /><input type="hidden" class="pur_metal_type" value="'+old_est_sale_row.find('td:eq(2) .est_old_item_cat_id').val()+'" name="purchase[metal_type][]" /></td>'

									+'<td>-</td>'

									+'<td><input type="number" class="pur_pcs" name="purchase[pcs][]" value="1" /></td>'

									+'<td><span>'+old_est_sale_row.find('.est_old_itm_gross_wt').html()+'</span><input type="hidden" class="pur_gross_val" name="purchase[gross][]" value="'+old_est_sale_row.find('.est_old_gross_val').val()+'" /></td>'

									+'<td><span>'+old_est_sale_row.find('.est_old_item_less_wt').val()+'</span><input type="hidden" class="pur_less_val" name="purchase[less][]" value="'+old_est_sale_row.find('.est_old_item_less_wt').val()+'" /></td>'

									+'<td><span>'+old_est_sale_row.find('.est_old_net_wt').html()+'</span><input type="hidden" class="pur_net_val" name="purchase[net][]" value="'+old_est_sale_row.find('.est_old_net_wt').html()+'" /><input type="hidden" class="est_old_dust_val" name="purchase[dust_wt][]" value="'+old_est_sale_row.find('.est_old_dust_val').val()+'" /><input type="hidden" class="est_old_stone_val" value="'+old_est_sale_row.find('.est_old_stone_val').val()+'"  name="purchase[stone_wt][]"/></td>'

									+'<td><span>'+old_est_sale_row.find('.est_old_wastage').html()+'</span><input type="hidden" class="pur_wastage" name="purchase[wastage][]" value="'+old_est_sale_row.find('.est_old_wastage_percent').val()+'" /></td>'

									+'<td><span>'+old_est_sale_row.find('.est_old_wastage_wt').html()+'</span><input type="hidden" class="pur_wastage_wt" name="purchase[wastage_wt][]" value="'+old_est_sale_row.find('.est_old_wastage_wt').html()+'" /></td>'

									+'<td><input type="number" class="pur_discount" name="purchase[discount][]" value="'+old_est_sale_row.find('.est_old_discount').val()+'" disabled/></td>'

									+'<td><a href="#" onClick="create_new_empty_bill_purchase_stone_item($(this).closest(\'tr\'));" class="btn btn-success"><i class="fa fa-plus"></i></a><input type="hidden" class="stone_details" value='+old_est_sale_row.find('.est_item_stone_dt').val()+' name="purchase[stone_details][]"/><input type="hidden" class="other_stone_price" value="'+old_est_sale_row.find('.other_stone_price').val()+'" /><input type="hidden" class="other_stone_wt" value="'+old_est_sale_row.find('.other_stone_wt').val()+'" /><input type="hidden" class="bill_material_price" value="'+old_est_sale_row.find('.est_material_price').val()+'"/></td>'

									+'<td><input type="number" class="bill_amount" name="purchase[billamount][]" value="'+old_est_sale_row.find('.est_old_item_amount_val').val()+'" step="any" readonly /><input type="hidden" class="bill_rate_per_grm" name="purchase[rate_per_grm][]" value="'+old_est_sale_row.find('.est_old_rate_per_gram_val').val()+'" step="any" readonly /></td>'

									+'<td><span>'+old_est_sale_row.find('td:first .est_id').val()+'</span><input type="hidden" class="pur_est_id" name="purchase[estid][]" value="'+old_est_sale_row.find('td:first .est_id').val()+'" /></td>'

									+'<td><a href="#" onClick="remove_row($(this).closest(\'tr\'));" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'

									+'</tr>';

					$('#purchase_item_details tbody').append(row);

				}

			}

		});

		if(bill_type != 5 ){

			calculateSaleBillRowTotal();

		}else{

			calculateOrderAdvBillRowTotal();

		}		

		$('#estimation-popup').modal('toggle');

	});

	$('#id_branch').change(function() {

		if($('#id_branch').val() != ""){

		    get_branch_details();

			get_metal_rates_by_branch();

		}

	});

	$(document).on('keyup',	".est_discount", function(e){ 

		calculateEsttoSaleConvertion();

	});

	$(document).on('keyup',	".bill_discount", function(e){ 

		calculateSaleBillRowTotal();

	});

	$(document).on('keyup',	".sale_gwt,.sale_lwt", function(e){ 

		var row = $(this).closest('tr'); 

		var gross_wt = (isNaN(row.find('.sale_gwt').val()) || row.find('.sale_gwt').val() == '')  ? 0 : row.find('.sale_gwt').val();

		var less_wt  = (isNaN(row.find('.sale_lwt').val()) || row.find('.sale_lwt').val() == '')  ? 0 : row.find('.sale_lwt').val();

		var net_wt = parseFloat(parseFloat(gross_wt) - parseFloat(less_wt)).toFixed(3);

		row.find('.bill_gross_val').val(gross_wt);

		row.find('.bill_less_val').val(less_wt);

		row.find('.bill_net_val').val(net_wt);

		row.find('.sale_nwt').val(net_wt);

		calculateSaleBillRowTotal();

	});

	$(document).on('keyup',	".est_old_discount", function(e){ 

		calculateOldEsttoSaleConvertion();

	});

	$(document).on('keyup',	".pur_discount", function(e){ 

		calculatePurchaseBillRowTotal();

	});

	/* Tag id search. - Start */

	$("#search_tag_no").on('click', function(){ 

		if($('#filter_tag_no').val() != "" != "" && $('#id_branch').val() != ""){

			getSearchTags($('#filter_tag_no').val());

		}else{

			if($('#filter_tag_no').val() == ""){

				alert("Please enter Tag number");

				$('#filter_tag_no').focus();

			}else if($('#id_branch').val() == ""){

				alert("Please select branch");

				$('#id_branch').focus();

			}

		}

	}); 

	/* Ends - tag id search. */

	/* Order No Search */

	$("#search_order_no").on('click', function(){

		var bill_type = $("input[name='billing[bill_type]']:checked").val();

		if($('#filter_order_no').val() != "" && $('#id_branch').val() != "" && bill_type != ""){ 

		    $('#order_no').val($('#filter_order_no').val());

		    $(".summary_adv_details").css("display","block");

			getEstimationDetails('',$('#filter_order_no').val(), bill_type);

		}else{ 

		    if($('#id_branch').val() == ""){

				alert("Please select branch");

				$("#branchAlert").append("<span>Choose Branch</span>");

				$('#branchAlert span').delay(10000).fadeOut(500); 

				$('#id_branch').focus();

			}else if($('#filter_order_no').val() == ""){

				$("#searchOrderNoAlert").append("<span>Enter Order Number</span>");

				$('#searchOrderNoAlert span').delay(10000).fadeOut(500); 

				$('#filter_order_no').focus();

			}

		}

	});

	/* Bill No events - STARTS */

	$("#search_bill_no").on('click', function(){

		var bill_type = $("input[name='billing[bill_type]']:checked").val();

		if($('#filter_bill_no').val() != "" && $('#id_branch').val() != "" && bill_type != ""){

		    if(bill_type==7 || bill_type==3)

		    {

		       getBillDetails($('#filter_bill_no').val(), bill_type);

		    }else

		    {

		        getCreditBillDetails($('#filter_bill_no').val(), bill_type);

		    }

		}else{ 

		    if($('#id_branch').val() == ""){

				alert("Please select branch");

				$("#branchAlert").append("<span>Choose Branch</span>");

				$('#branchAlert span').delay(10000).fadeOut(500); 

				$('#id_branch').focus();

			}else if($('#filter_bill_no').val() == ""){

				$("#searchBillAlert").append("<span>Enter Order Number</span>");

				$('#searchBillAlert span').delay(10000).fadeOut(500); 

				$('#filter_bill_no').focus();

			}

		}

	});

	$('#update_bill_return').on('click', function(){

		var bill_type = $("input[name='billing[bill_type]']:checked").val(); 

		$('#bill_items_tbl_for_return > tbody tr').each(function(i, row){

			sold_items_row = $(this);

			var rowExist = false;

			var idx=$('#sale_return_details > tbody tr').length;

			idx=idx++;

			if(sold_items_row.find('td:first .select_est_details').is(':checked') ){

				$(".return_details").show();

				$('#sale_return_details > tbody tr').each(function(bidx, brow){

					return_items_row = $(this);

					// CHECK DUPLICATES - TAG

					if(return_items_row.find('td:eq(15) .sale_tag_id').val() != ''){

						if( sold_items_row.find('td:first .est_tag_id').val() == return_items_row.find('td:eq(15) .sale_tag_id').val()){

							rowExist = true; 

							/*console.log("Tag ID - "+bidx+" : From Modal"+sold_items_row.find('td:first .est_tag_id').val()+" From Bill"+return_items_row.find('td:eq(15) .sale_tag_id').val());

							console.log(rowExist);*/

						} 

					}

					// CHECK DUPLICATES - ESTIMATION ITEM

					if(return_items_row.find('td:first .is_est_details').val() == 1 )

					{ 

						if(sold_items_row.find('td:first .est_itm_id').val() == return_items_row.find('td:first .est_itm_id').val()){

							rowExist = true;

							/*console.log("Esti ID - "+bidx+" : From Modal"+sold_items_row.find('td:first .est_itm_id').val()+" From Bill"+sold_items_row.find('td:first .est_itm_id').val());

							console.log(rowExist);*/

						}

					}

				});

				if(!rowExist){

				   

					if(sold_items_row.find('td:last .select_est_partial').is(':checked')){

						var row = '<tr><td><span>'+sold_items_row.find('td:first .est_hsn').val()+'</span><input type="hidden" class="bill_id" name="sales_return['+idx+'][bill_id]" value="'+sold_items_row.find('td:first .bill_id').val()+'" /><input type="hidden" class="bill_det_id" name="sales_return['+idx+'][bill_det_id]" value="'+sold_items_row.find('td:first .bill_det_id').val()+'" /><input type="hidden" class="sale_pro_hsn" name="sales_return['+idx+'][hsn]" value="'+sold_items_row.find('td:first .est_hsn').val()+'" /><input type="hidden" class="sale_type" name="sales_return['+idx+'][sourcetype]" value="1" /><input type="hidden" class="sale_item_type" name="sales_return['+idx+'][itemtype]" value="'+sold_items_row.find('td:first .est_item_type').val()+'" /><input type="hidden" class="is_est_details" value="1" name="sales_return['+idx+'][is_est_details]" /><input type="hidden" class="est_itm_id" name="sales_return['+idx+'][est_itm_id]" value="'+sold_items_row.find('td:first .est_itm_id').val()+'" /><input type="hidden" class="sale_cal_type" name="sales_return['+idx+'][calltype]" value="'+sold_items_row.find('td:first .est_cal_type').val()+'" /><input type="hidden" class="sale_metal_type" value="'+sold_items_row.find('td:first .est_metal_type').val()+'" /><input type="hidden" class="sale_purity" value="'+sold_items_row.find('td:eq(4) .est_purid').val()+'"  name="sales_return['+idx+'][purity]" /><input type="hidden" class="sale_size" value="'+sold_items_row.find('td:eq(5) .est_size_val').val()+'"  name="sales_return['+idx+'][size]" /><input type="hidden" class="sale_uom" value="'+sold_items_row.find('td:eq(0) .est_uom').val()+'"  name="sales_return['+idx+'][uom]" /></td><td><span>'+sold_items_row.find('td:eq(1) .est_product_name').html()+'</span><input class="sale_product_id" type="hidden" name="sales_return['+idx+'][product]" value="'+sold_items_row.find('td:eq(1) .est_product_id').val()+'" /></td><td><span>'+sold_items_row.find('td:eq(2) .est_design_code').html()+'</span><input type="hidden" class="sale_design_id" name="sales_return['+idx+'][design]" value="'+sold_items_row.find('td:eq(2) .est_design_id').val()+'" /></td><td><input type="number" class="sale_pcs" name="sales_return['+idx+'][pcs]" value="'+sold_items_row.find('td:eq(3) .est_pcs').val()+'"  /></td><td><span>'+sold_items_row.find('td:eq(6) .est_gross_wt').html()+'</span><input type="hidden" class="bill_gross_val" name="sales_return['+idx+'][gross]" value="'+sold_items_row.find('td:eq(6) .est_gross_val').val()+'" /></td><td><span>'+sold_items_row.find('td:eq(7) .est_less_wt').html()+'</span><input type="hidden" class="bill_less_val" name="sales_return['+idx+'][less]" value="'+sold_items_row.find('td:eq(7) .est_less_val').val()+'" /></td><td><span>'+sold_items_row.find('td:eq(8) .est_net_wt').html()+'</span><input type="hidden" class="bill_net_val" name="sales_return['+idx+'][net]" value="'+sold_items_row.find('td:eq(8) .est_net_val').val()+'" /></td><td><span>'+sold_items_row.find('td:eq(9) .est_wastage').html()+'</span><input type="hidden" class="bill_wastage" name="sales_return['+idx+'][wastage]" value="'+sold_items_row.find('td:eq(9) .est_wastage_percent').val()+'" /></td><td><span>'+sold_items_row.find('td:eq(10) .est_mc').html()+'</span><input type="hidden" class="bill_mctype" name="sales_return['+idx+'][bill_mctype]" value="'+sold_items_row.find('td:eq(10) .est_mc_type').val()+'" /><input type="hidden" class="bill_mc" name="sales_return['+idx+'][mc]" value="'+sold_items_row.find('td:eq(10) .est_mc_value').val()+'" /></td><td><input type="hidden" class="bill_discount" name="sales_return['+idx+'][discount]" value="'+sold_items_row.find('td:eq(11) .est_discount').val()+'"  />'+sold_items_row.find('td:eq(11) .est_discount').val()+'</td><td></td><td><span>'+sold_items_row.find('td:eq(12) .est_tgrp_name').html()+'</span><input type="hidden" class="sale_tax_group" name="sales_return['+idx+'][taxgroup]" value="'+sold_items_row.find('td:eq(12) .est_tax_id').val()+'" /></td><td><span>'+sold_items_row.find('td:eq(12) .est_tax_val').val()+'</span></td><td><input type="hidden" class="bill_stone_price" value="'+sold_items_row.find('.est_stone_price').val()+'" /><input type="hidden" class="bill_material_price" value="'+sold_items_row.find('.est_material_price').val()+'"/><input type="number" class="bill_amount" name="sales_return['+idx+'][billamount]" value="'+sold_items_row.find('.est_item_cost_val').val()+'" step="any" readonly style="width: 100px;"/><input type="hidden" class="per_grm_amount" name="sales_return['+idx+'][per_grm]" value="" step="any" /></td><td><input type="number" class="sale_ret_disc_amt" name="sales_return['+idx+'][sale_ret_disc_amt]" value="" step="any" style="width: 100px;"/></td><td><input type="number" class="sale_ret_amt" name="sales_return['+idx+'][sale_ret_amt]" value="'+sold_items_row.find('.est_item_cost_val').val()+'" step="any" readonly style="width: 100px;" readonly/></td><td>Yes</td><td><span>'+sold_items_row.find('td:first .est_tag_id').val()+'</span><input type="hidden" class="sale_tag_id" name="sales_return['+idx+'][tag]" value="'+sold_items_row.find('td:first .est_tag_id').val()+'" /></td><td>-</td><td><span>'+sold_items_row.find('td:first .est_id').val()+'</span><input type="hidden" class="sale_est_itm_id" name="sales_return['+idx+'][estid]" value="'+sold_items_row.find('td:first .est_itm_id').val()+'" /></td>><td><a href="#" onClick="remove_row($(this).closest(\'tr\'));" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';

					}else{  

						var row = '<tr><td><span>'+sold_items_row.find('td:first .est_hsn').val()+'</span><input type="hidden" class="bill_id" name="sales_return['+idx+'][bill_id]" value="'+sold_items_row.find('td:first .bill_id').val()+'" /><input type="hidden" class="bill_det_id" name="sales_return['+idx+'][bill_det_id]" value="'+sold_items_row.find('td:first .bill_det_id').val()+'" /><input type="hidden" class="sale_pro_hsn" name="sales_return['+idx+'][hsn]" value="'+sold_items_row.find('td:first .est_hsn').val()+'" /><input type="hidden" class="sale_type" name="sales_return['+idx+'][sourcetype]" value="1" /><input type="hidden" class="sale_item_type" name="sales_return['+idx+'][itemtype]" value="'+sold_items_row.find('td:first .est_item_type').val()+'" /><input type="hidden" class="is_est_details" value="1" name="sales_return['+idx+'][is_est_details]" /><input type="hidden" class="est_itm_id" name="sales_return['+idx+'][est_itm_id]" value="'+sold_items_row.find('td:first .est_itm_id').val()+'" /><input type="hidden" class="sale_cal_type" name="sales_return['+idx+'][calltype]" value="'+sold_items_row.find('td:first .est_cal_type').val()+'" /><input type="hidden" class="sale_metal_type" value="'+sold_items_row.find('td:first .est_metal_type').val()+'" /><input type="hidden" class="sale_purity" value="'+sold_items_row.find('td:eq(4) .est_purid').val()+'"  name="sales_return['+idx+'][purity]" /><input type="hidden" class="sale_size" value="'+sold_items_row.find('td:eq(5) .est_size_val').val()+'"  name="sales_return['+idx+'][size]" /><input type="hidden" class="sale_uom" value="'+sold_items_row.find('td:eq(0) .est_uom').val()+'"  name="sales_return['+idx+'][uom]" /></td><td><span>'+sold_items_row.find('td:eq(1) .est_product_name').html()+'</span><input class="sale_product_id" type="hidden" name="sales_return['+idx+'][product]" value="'+sold_items_row.find('td:eq(1) .est_product_id').val()+'" /></td><td><span>'+sold_items_row.find('td:eq(2) .est_design_code').html()+'</span><input type="hidden" class="sale_design_id" name="sales_return['+idx+'][design]" value="'+sold_items_row.find('td:eq(2) .est_design_id').val()+'" /></td><td><span>'+sold_items_row.find('td:eq(3) .est_piece').html()+'</span><input type="hidden" class="sale_pcs" name="sales_return['+idx+'][pcs]" value="'+sold_items_row.find('td:eq(3) .est_pcs').val()+'"  /></td><td><span>'+sold_items_row.find('td:eq(6) .est_gross_wt').html()+'</span><input type="hidden" class="bill_gross_val" name="sales_return['+idx+'][gross]" value="'+sold_items_row.find('td:eq(6) .est_gross_val').val()+'" /></td><td><span>'+sold_items_row.find('td:eq(7) .est_less_wt').html()+'</span><input type="hidden" class="bill_less_val" name="sales_return['+idx+'][less]" value="'+sold_items_row.find('td:eq(7) .est_less_val').val()+'" /></td><td><span>'+sold_items_row.find('td:eq(8) .est_net_wt').html()+'</span><input type="hidden" class="bill_net_val" name="sales_return['+idx+'][net]" value="'+sold_items_row.find('td:eq(8) .est_net_val').val()+'" /></td><td><span>'+sold_items_row.find('td:eq(9) .est_wastage').html()+'</span><input type="hidden" class="bill_wastage" name="sales_return['+idx+'][wastage]" value="'+sold_items_row.find('td:eq(9) .est_wastage_percent').val()+'" /></td><td><span>'+sold_items_row.find('td:eq(10) .est_mc').html()+'</span><input type="hidden" class="bill_mctype" name="sales_return['+idx+'][bill_mctype]" value="'+sold_items_row.find('td:eq(10) .est_mc_type').val()+'" /><input type="hidden" class="bill_mc" name="sales_return['+idx+'][mc]" value="'+sold_items_row.find('td:eq(10) .est_mc_value').val()+'" /></td><td><input type="hidden" class="bill_discount" name="sales_return['+idx+'][discount]" value="'+sold_items_row.find('td:eq(11) .est_discount').val()+'"  />'+sold_items_row.find('td:eq(11) .est_discount').val()+'</td><td></td><td><span>'+sold_items_row.find('td:eq(12) .est_tgrp_name').html()+'</span><input type="hidden" class="sale_tax_group" name="sales_return['+idx+'][taxgroup]" value="'+sold_items_row.find('td:eq(12) .est_tax_id').val()+'" /></td><td><span>'+sold_items_row.find('td:eq(12) .est_tax_val').val()+'</span></td><td><input type="hidden" class="bill_stone_price" value="'+sold_items_row.find('.est_stone_price').val()+'" /><input type="hidden" class="bill_material_price" value="'+sold_items_row.find('.est_material_price').val()+'"/><input type="number" class="bill_amount" name="sales_return['+idx+'][billamount]" value="'+sold_items_row.find('.est_item_cost_val').val()+'" step="any" readonly style="width: 100px;"/><input type="hidden" class="per_grm_amount" name="sales_return['+idx+'][per_grm]" value="" step="any" /></td><td><input type="number" class="sale_ret_disc_amt" name="sales_return['+idx+'][sale_ret_disc_amt]" value="" step="any" style="width: 100px;"/></td><td><input type="number" class="sale_ret_amt" name="sales_return['+idx+'][sale_ret_amt]" value="'+sold_items_row.find('.est_item_cost_val').val()+'" step="any" readonly style="width: 100px;"/></td><td>No</td><td><span>'+sold_items_row.find('td:first .est_tag_id').val()+'</span><input type="hidden" class="sale_tag_id" name="sales_return['+idx+'][tag]" value="'+sold_items_row.find('td:first .est_tag_id').val()+'" /></td><td>-</td><td><span>'+sold_items_row.find('td:first .est_itm_id').val()+'</span><input type="hidden" class="sale_est_itm_id" name="sales_return['+idx+'][estid]" value="'+sold_items_row.find('td:first .est_itm_id').val()+'" /></td><td><a href="#" onClick="remove_row($(this).closest(\'tr\'));" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';

					}

					$('#sale_return_details tbody').append(row);

					 

				}

			}

		});

		calculate_salesReturn_details();

		$('#bill-detail-popup').modal('toggle');

	});

	/*Bill No events - ENDS*/

	$('#create_custom_details').on('click', function(){

		if(validateCustomDetailRow()){

			create_new_empty_est_custom_row();

		}else{

			alert("Please fill required fields");

		}

	});

	$('#select_custom_details').change(function() {

        if(this.checked) {

            $(".custom_details").show();

			if($('#estimation_custom_details tbody tr').length == 0){

				create_new_empty_est_custom_row();

			}

        }else{

			$(".custom_details").hide();

		}

    });

	$('#select_oldmatel_details').change(function() {

        if(this.checked) {

            $(".old_matel_details").show();

			if($('#estimation_old_matel_details tbody tr').length == 0){

				create_new_empty_est_oldmatel_row();

			}

        }else{

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

				/* if($('#cus_address').length > 0)

				{

					$(".cus_address").html(""); */

					//,$('#cus_address').val()

					add_cutomer($('#cus_first_name').val(),$('#cus_mobile').val(),$('#id_village').val(),$('#cus_type:checked').val(),$('#gst_no').val());

					$('#cus_first_name').val('');

					$('#cus_mobile').val('');

					//$('#cus_address').val('');

				/* }else{

					$(".cus_address").html("Please enter customer address");

				} */

			}else{

				$(".cus_mobile").html("Please enter customer mobile");

			}

		}else{

			$(".cus_first_name").html("Please enter customer first name");

		}

	});

	/* Customer search. - Start */	

	$("#bill_cus_name").on("keyup",function(e){ 

		var customer = $("#bill_cus_name").val();

		if(customer.length >= 2) { 

			getSearchCustomers(customer);

		}

	}); 

	/* Ends - Customer search. */

	$(document).on('keyup',	"#estimation_sale_details input[type='text'], #purchase_purchase_details input[type='text'], #estimation_custom_details input[type='text'], #estimation_stone_details input[type='text'], #estimation_material_details input[type='text']", function(e){

		calculate_purchase_details();

	});

	$(document).on('change',"#estimation_sale_details input[type='text'], #purchase_purchase_details input[type='text'], #estimation_custom_details input[type='text'], #estimation_stone_details input[type='text'], #estimation_material_details input[type='text']", function(e){

		calculate_purchase_details();

	});

	$(document).on('keyup',	".old_gwt, .old_lwt, .old_wastage, .gift_voucher_amt, .voucher_no, .scheme_account_id, .chit_amt, #estimation_old_matel_details input[type='text'], #gift_voucher_details input[type='text'], #estimation_chit_details input[type='text']", function(e) {

		calculate_sales_details();

	});

	$(document).on('change', "#estimation_old_matel_details input[type='text'], #gift_voucher_details input[type='text'], #estimation_chit_details input[type='text'], .old_item_type, .old_id_category", function(e) {

		calculate_sales_details();

	});

	/* Product id search. - Start */

	$(document).on('keyup',	".cat_product", function(e){ 

		var product = $(".cat_product").val();

		//var row = $(this).parent().parent();

		var row = $(this).closest('tr'); 

		getSearchProducts(product, row);

	}); 

	/* Ends - product id search. */

	/* Design id search. - Start */

	$(document).on('keyup',	".cat_design", function(e){ 

		var design = $(".cat_design").val();

		//var row = $(this).parent().parent();

		var row = $(this).closest('tr'); 

		getSearchDesign(design, row);

	}); 

	/* Ends - design id search. */

	$(document).on('keyup', '.cat_gwt, .cat_lwt, .cat_wastage, .cat_mc', function(e){

		var row = $(this).closest('tr'); 

		var gross_wt = (isNaN(row.find('td:eq(6) .cat_gwt').val()) || row.find('td:eq(6) .cat_gwt').val() == '')  ? 0 : row.find('td:eq(6) .cat_gwt').val();

		var less_wt  = (isNaN(row.find('td:eq(7) .cat_lwt').val()) || row.find('td:eq(7) .cat_lwt').val() == '')  ? 0 : row.find('td:eq(7) .cat_lwt').val();

		var net_wt = parseFloat(gross_wt) - parseFloat(less_wt);

		row.find('td:eq(8) .cat_nwt').val(net_wt);

		calculateSaleValue(row);

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

	/* custom Product id search. - Start */

	$(document).on('keyup',	".cus_product", function(e){ 

		var product = $(".cus_product").val();

		//var row = $(this).parent().parent();

		var row = $(this).closest('tr'); 

		getSearchCustomProducts(product, row);

	}); 

	/* Ends - product id search. */

	$(document).on('keyup', '.cus_gwt, .cus_lwt, .cus_wastage, .cus_mc', function(e){

		var row = $(this).closest('tr'); 

		var gross_wt = (isNaN(row.find('td:eq(5) .cus_gwt').val()) || row.find('td:eq(5) .cus_gwt').val() == '')  ? 0 : row.find('td:eq(5) .cus_gwt').val();

		var less_wt  = (isNaN(row.find('td:eq(6) .cus_lwt').val()) || row.find('td:eq(6) .cus_lwt').val() == '')  ? 0 : row.find('td:eq(6) .cus_lwt').val();

		var net_wt = parseFloat(gross_wt) - parseFloat(less_wt);

		row.find('td:eq(7) .cus_nwt').val(net_wt);

		calculateCustomItemSaleValue(row);

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

	$(document).on('keyup', '.old_gwt, .old_lwt, .old_wastage', function(e){

		var row = $(this).closest('tr'); 

		var gross_wt = (isNaN(row.find('td:eq(3) .old_gwt').val()) || row.find('td:eq(3) .old_gwt').val() == '')  ? 0 : row.find('td:eq(3) .old_gwt').val();

		var less_wt  = (isNaN(row.find('td:eq(4) .old_lwt').val()) || row.find('td:eq(4) .old_lwt').val() == '')  ? 0 : row.find('td:eq(4) .old_lwt').val();

		var net_wt = parseFloat(gross_wt) - parseFloat(less_wt);

		row.find('td:eq(5) .old_nwt').val(net_wt);

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

	$(document).on('change', '.old_id_category', function(e){

		var row = $(this).closest('tr'); 

		var matelType = row.find('td:eq(1) .old_id_category').val();

		var rate_per_grm = 0;

		if(matelType == 1){

			rate_per_grm = (isNaN($('.per-grm-sale-value').html()) || $('.per-grm-sale-value').html() == '')  ? 0 : parseFloat($('.per-grm-sale-value').html());

		}else if(matelType == 2){

			rate_per_grm = (isNaN($('.silver_per-grm-sale-value').html()) || $('.silver_per-grm-sale-value').html() == '')  ? 0 : parseFloat($('.silver_per-grm-sale-value').html());

		}

		row.find('td:eq(7) .old_rate').val(rate_per_grm);

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

	$('#billing_datetime').datetimepicker(

	{ 

		format: 'dd-mm-yyyy H:m:s'

	});

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

});

function show_chargesdetails(e, row) {
	e.preventDefault();
	console.log(row);
	$('#charge_items_popup .modal-body').find('#billing_charges_details tbody tr').remove();
	var charges_details = $(row).find('.charges_details').val();
	var charges_details = JSON.parse(charges_details);
	let td_charges = "";
	$.each(charges_details,function(key,charge_item) {
		console.log(charge_item);
		td_charges += "<tr><td>"+charge_item.code_charge+"</td><td>"+charge_item.charge_value+"</td></tr>";
	});

	console.log("td_charges",td_charges);

	$('#billing_charges_details tbody').append(td_charges);

	$('#charge_items_popup').modal('show');
}

function getEstimationDetails(estId, orderNo, billType){

	$('#searchEstiAlert').html('');

	//$("div.overlay").css("display", "block"); 

	my_Date = new Date();

	$.ajax({

        url: base_url+'index.php/admin_ret_billing/getEstimationDetails/?nocache='+my_Date.getUTCSeconds(),             

        dataType: "json", 

        method: "POST", 

        data: {'estId' : estId, 'order_no' : orderNo, 'billType' : billType, 'id_branch' : $("#id_branch").val(),'fin_year':$('#order_fin_year_select').val()}, //Need to update login branch id here from session

        success: function (data) { 

			if(data.success == true){

			    

			    var billing_for = $("input[name='billing[billing_for]']:checked").val();

			    

				//$("div.overlay").css("display", "none"); 

				

				//Check Order Advance Exists

			    var advance_details=data.responsedata.advance_details;

			    

				var paid_advance=0;



				var paid_weight=0;

				

				var wt_amt=0;



				var rate_per_grm=$('.per-grm-sale-value').html();

				

				$.each(advance_details,function(key,item){

					

					paid_advance +=parseFloat(item.paid_advance);



					paid_weight +=parseFloat(item.paid_weight);

					

					wt_amt +=parseFloat(item.paid_weight*item.rate_per_gram);



				});

                

                order_adv_details=advance_details;

                

				$('.summary_adv_paid_amt').html(parseFloat(paid_advance)+parseFloat(wt_amt));



				$('.summary_adv_paid_wt').html(parseFloat(paid_advance)+parseFloat(paid_weight));



				$('.adv_paid_wt').html(paid_weight);

				    

				$('#ord_adv_adj_details').val(advance_details.length>0 ? JSON.stringify(advance_details):'');

				

				//Check Order Advance Exists

				

				// ESTIMATION SALE ITEMS

				if(billType!=5)

				{

					//$('#estimation-popup').modal('toggle');

				}

				if(data.responsedata.item_details.length > 0){

				    var rowExist = false;

					var available_gross_wt=0;

					var available_pieces=0;

					var total_wt=0;

					$('#est_items_to_sale_convertion tbody').empty();

					

                    if(billing_for==1)

                    {

                        $('#bill_cus_name').val(data.responsedata.item_details[0].cus_name);

                        $('#bill_cus_id').val(data.responsedata.item_details[0].id_customer);

                        $('#cus_village').html(data.responsedata.item_details[0].village_name);

                        $("#cus_info").append(data.responsedata.item_details[0].vip == 'Yes' ? "<span class='label bg-orange'><i class='fa fa-fw fa-star'></i> V I P</span>":"");

                        $("#cus_info").append(data.responsedata.item_details[0].accounts > 0 ? "&nbsp;<span class='label label-info'>Chit Customer</span>":"");

                    }

                    

					$.each(data.responsedata.item_details, function (estkey, estval) {

					    total_wt+=parseFloat(estval.gross_wt);

					    if(estval.is_non_tag==1)

    					{

    						available_pieces+=parseFloat(estval.available_pieces);

    						available_gross_wt+=parseFloat(estval.available_gross_wt);

    					}

					});

					

					$.each(data.responsedata.item_details, function (estkey, estval) {

					var stone_details=[];

					var stone_price=0;

					    if(estval.is_non_tag==1)

    					{

    						available_pieces+=parseFloat(estval.available_pieces);

    						available_gross_wt+=parseFloat(estval.available_gross_wt);

    					}

						if(estval.tag_id=='')

						{
                        
							$.each(estval.stone_details,function(key,item){
							    
							    stone_price+=parseFloat(item.amount);

								stone_details.push({'stone_id' : item.stone_id,'stone_pcs' :item.pieces,'stone_wt':item.wt,'stone_price':item.amount,'certification_cost':item.certification_cost});

							});

						}

						else

						{

							$.each(estval.stone_details,function(key,item){

							    stone_price+=parseFloat(item.amount);

								stone_details.push({'stone_id' : item.stone_id,'stone_pcs' :item.pieces,'stone_wt':item.wt,'stone_price':item.amount,'certification_cost':item.certification_cost});

							});

						}

						$('#billing_sale_details > tbody tr').each(function(bidx, brow){

						bill_sale_row = $(this);

						// CHECK DUPLICATES - TAG

						var bill_type       = $("input[name='billing[bill_type]']:checked").val();

						if(bill_type!=9)

						{

						    if(bill_sale_row.find('.sale_tag_id').val() != '')

    						{

        						 if(bill_sale_row.find('.sale_tag_id').val() != '')

        						{

        							if( $('#filter_tag_no').val() == bill_sale_row.find('.sale_tag_id').val()){

        								rowExist = true; 

        								$('#searchEstiAlert').html('Tag No Already Exists');

        							} 

        						}

    						}

						}

						

						

						// CHECK DUPLICATES - ESTIMATION ITEM

    					if(bill_sale_row.find('.sale_est_itm_id').val()!='')

						{

						    if(estval.est_item_id== bill_sale_row.find('.sale_est_itm_id').val())

						    {

    						rowExist = true;

    						$('#searchEstiAlert').html('Est No Already Exists');

    					    }

    					    

						}

						

					});

					

					if(estval.is_non_tag==1)

    					{

    						if(available_gross_wt<total_wt)

    						{

    						    rowExist = true;

    						    alert('No Available Stock');

    						}

    					}

					    if(!rowExist)

					    {

					        var row = '<tr id="'+estkey+'">'

                            +'<td><span>'+estval.hsn_code+'</span><input type="hidden" name="sale[order_no][]" class="order_no" value="'+estval.order_no+'"><input type="hidden" name="sale[id_orderdetails][]" class="id_orderdetails" value="'+estval.id_orderdetails+'"><input type="hidden" name="sale[id_customerorder][]" class="id_customerorder" value="'+estval.id_customerorder+'"><input type="hidden" class="sale_pro_hsn" name="sale[hsn]" value="'+estval.hsn_code+'" /><input type="hidden" class="sale_type" name="sale[sourcetype][]" value="1" /><input type="hidden" class="sale_item_type" name="sale[itemtype][]" value="'+estval.item_type+'" /><input type="hidden" class="is_est_details" value="1" name="sale[is_est_details][]" /><input type="hidden" class="est_itm_id" name="sale[est_itm_id][]" value="'+estval.est_item_id+'" /><input type="hidden" class="esti_no" name="sale[esti_no][]" value="'+estval.esti_no+'" /><input type="hidden" class="sale_cal_type" name="sale[calltype][]" value="'+estval.calculation_based_on+'" /><input type="hidden" class="sale_metal_type" value="'+estval.metal_type+'" /><input type="hidden" class="sale_purity" value=""  name="sale[purity][]" /><input type="hidden" class="sale_size" value="'+estval.size+'"  name="sale[size][]" /><input type="hidden" class="sale_uom" value="'+estval.uom+'"  name="sale[uom][]" /><input type="hidden" class="total_tax" name="sale[item_total_tax][]"><input type="hidden" class="is_partial" value="'+estval.is_partial+'"  name="sale[is_partial][]" /><input type="hidden" class="min_wastage" value="'+estval.min_wastage+'"  name="sale[min_wastage][]" /><input type="hidden" class="max_wastage" value="'+estval.max_wastage+'"  name="sale[max_wastage][]" /><input type="hidden" class="stock_type" name="sale[stock_type][]" value="'+estval.stock_type+'" /><input type="hidden" class="is_non_tag" name="sale[is_non_tag][]" value="'+estval.is_non_tag+'" /><input type="hidden" class="rate_field"  value="'+estval.rate_field+'" /></td>'

                            +'<td><span>'+estval.product_name+'</span><input class="sale_product_id" type="hidden" name="sale[product][]" value="'+estval.product_id+'" /></td>'

                            +'<td><span>'+estval.design_name+'</span><input type="hidden" class="sale_design_id" name="sale[design][]" value="'+estval.design_id+'" /></td>'

                            +'<td><span>'+estval.piece+'</span><input type="hidden" class="sale_pcs" name="sale[pcs][]" value="'+estval.piece+'"  /></td>'

                            +'<td><span>'+estval.gross_wt+'</span><input type="hidden" class="bill_gross_val" name="sale[gross][]" value="'+estval.gross_wt+'" /></td>'

                            +'<td><span>'+estval.less_wt+'</span><input type="hidden" class="bill_less_val" name="sale[less][]" value="'+estval.less_wt+'" /></td>'

                            +'<td><span class="bill_sale_net_wt"></span><input type="hidden" class="bill_net_val" name="sale[net][]" value="'+estval.net_wt+'" /></td>'

                            +'<td><span class="est_wastage">'+estval.wastage_percent+'</span><input type="hidden" class="bill_wastage" value="'+estval.wastage_percent+'" /><input type="hidden" class="bill_wastage_per" name="sale[wastage][]" value="'+estval.wastage_percent+'" /></td>'

                            +'<td><span class="est_wastage_wt"></span><input type="hidden" class="bill_wastage_wt" value=""></td>'

                            +'<td><span class="making_charge">'+estval.mc_value+'</span><input type="hidden" class="mc_dis" value="0"><input type="hidden" class="bill_mctype" name="sale[bill_mctype][]" value="'+estval.mc_type+'" /><input type="hidden" class="bill_mc" name="sale[mc][]" value="'+estval.mc_value+'" /><input type="hidden" class="discount"  name="sale[adjusted_dis][]" value="" /><input type="hidden" class="bill_mc_value" value="'+estval.mc_value+'" /></td>'

                            +'<td><input type="number" class="bill_discount" name="sale[discount][]" value="" step="any" readonly/></td>'

                            +'<td><span class="bill_taxable_amt"></span></td>'

                            +'<td><span>'+estval.tgrp_name+'</span><input type="hidden" class="sale_tax_group" name="sale[taxgroup][]" value="'+estval.tax_group_id+'" /></td>'

                            +'<td class="tax_amt"></td>'

							+'<td class="total_charges">'+estval.charge_value+(estval.charges.length>0 ? ' <a href="#" onClick="show_chargesdetails(event, $(this).closest(\'tr\'));" class="btn btn-success btn-sm"><i class="fa fa-edit"></i></a>' :'-')+'<input type="hidden" value='+(JSON.stringify(estval.charges))+' class="charges_details" /><input type="hidden" class="charge_value" value="'+estval.charge_value+'" /></td>'

                            +'<td>'+(stone_details.length>0 ? '<a href="#" onClick="create_new_empty_bill_sales_stone_item($(this).closest(\'tr\'));" class="btn btn-success"><i class="fa fa-plus"></i></a>' :'-')+'<input type="hidden" class="certification_cost" value="'+estval.certification_cost+'" /><input type="hidden" class="est_old_stone_val" value="" /><input type="hidden" class="est_old_dust_val" value="" /><input type="hidden" class="bill_material_price" value="'+estval.othermat_amount+'"/><input type="hidden" value='+(JSON.stringify(stone_details))+' class="stone_details" /></td>'

                            +'<td><input type="hidden" class="bill_stone_price" value="'+stone_price+'" /><input type="number" class="bill_amount" name="sale[billamount][]" value="" step="any" readonly /><input type="hidden" class="per_grm_amount" name="sale[per_grm][]" value="" step="any" /></td>'

                            +'<td>'+(estval.is_partial==1 ?'YES' :'NO')+'</td>'

                            +'<td><span>'+estval.tag_id+'</span><input type="hidden" class="sale_tag_id" name="sale[tag][]" value="'+estval.tag_id+'" /><input type="hidden" name="sale[total_cgst][]" class="sale_cgst"/><input type="hidden" class="sale_sgst" name="sale[total_sgst][]"/><input type="hidden" class="sale_igst" name="sale[total_igst][]"/></td>'

                            +'<td>'+estval.order_no+'</td>'

                            +'<td><span>'+estval.esti_no+'</span><input type="hidden" class="sale_est_itm_id" name="sale[estid][]" value="'+estval.est_item_id+'" /><input type="hidden" class="tag_sales_value"  value="'+estval.item_cost+'" /><input type="hidden" class="gift_applicable"  value="'+estval.gift_applicable+'" /></td>'

                            +'<td><a href="#" onClick="remove_row($(this).closest(\'tr\'));" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'

                            +'</tr>';

					    }

                        $('#billing_sale_details tbody').append(row);    

					});

					

					if(billType==9) //Order Delivery

					{

					    calculateOrderSaleBillRowTotal();

					}else{

					    calculateSaleBillRowTotal();

					}

				}

				

				if(billType==5 && (data.responsedata.order_details).length>0)

				{

					$('#billing_order_adv_details tbody').empty();

					if(billing_for==1)

					{

					    $('#bill_cus_name').val(data.responsedata.order_details[0].cus_name);

					    $('#bill_cus_id').val(data.responsedata.order_details[0].order_to);

					    updateorderAdvance_sale_Bill(data.responsedata.order_details);

					}

				}

				

				// ESTIMATION PURCHASE ITEMS

				if(data.responsedata.old_matel_details.length > 0){

					if(billType!=5)

					{

					    var rowExist = false;

						$('#est_olditems_to_sale_convertion tbody').empty();

						

						if(billing_for==1)

						{

						    $('#bill_cus_name').val(data.responsedata.old_matel_details[0].cus_name);

				        	$('#bill_cus_id').val(data.responsedata.old_matel_details[0].id_customer);

				    	    $('#cus_village').html(data.responsedata.old_matel_details[0].village_name);

				    	    $("#cus_info").append(data.responsedata.old_matel_details[0].vip == 'Yes' ? "<span class='label bg-orange'><i class='fa fa-fw fa-star'></i> V I P</span>":"");

						    $("#cus_info").append(data.responsedata.old_matel_details[0].accounts > 0 ? "&nbsp;<span class='label label-info'>Chit Customer</span>":"");

						}

						

						$.each(data.responsedata.old_matel_details, function (estkey, estval) {

						var stone_details=[];

						var other_stone_wt=0;

						var other_stone_price=0;

						$.each(estval.stone_details,function(key,item){

							stone_details.push({'est_old_metal_stone_id':item.est_old_metal_stone_id,'stone_id' : item.stone_id,'stone_pcs':item.pieces,'stone_wt':item.wt,'stone_price':item.price});

							other_stone_wt+=parseFloat(item.wt);

							other_stone_price+=parseFloat(item.price);

						});

						

						

				

						

    				        $('#purchase_item_details > tbody tr').each(function(bidx, brow){

				                bill_pur_row = $(this);

				                if(estval.old_metal_sale_id == bill_pur_row.find('.est_old_itm_id').val())

				                {

				                    rowExist = true;

				                }

				            });

				            if(!rowExist)

    						{

    						    var row = '<tr id="'+estkey+'">'

    							+'<td><span>'+(estval.purpose== 1 ? "Cash" : "Exchange")+'</span></td>'

    							+'<td><span>'+estval.metal+' - '+estval.purpose+'</span><input type="hidden" class="is_est_details" value="1" name="purchase[is_est_details][]" /><input type="hidden" class="est_old_itm_id" value="'+estval.old_metal_sale_id+'" name="purchase[est_old_itm_id][]" /><input type="hidden" name="purchase[est_itm_id][]" class="est_itm_id" value="'+estval.old_metal_sale_id+'" /><input type="hidden" class="item_type" name="purchase[itemtype][]" value="2" /><input type="hidden" class="pur_metal_type" value="'+estval.id_category+'" name="purchase[metal_type][]" /></td>'

    							+'<td>-</td>'

    							+'<td><input type="number" class="pur_pcs" name="purchase[pcs][]" value="1" /></td>'

    							+'<td><span>'+estval.gross_wt+'</span><input type="hidden" class="pur_gross_val" name="purchase[gross][]" value="'+estval.gross_wt+'" /></td>'

    							+'<td><span>'+estval.less_wt+'</span><input type="hidden" class="pur_less_val" name="purchase[less][]" value="'+estval.less_wt+'" /></td>'

    							+'<td><span>'+estval.net_wt+'</span><input type="hidden" class="pur_net_val" name="purchase[net][]" value="'+estval.net_wt+'" /><input type="hidden" class="est_old_dust_val" name="purchase[dust_wt][]" value="'+estval.dust_wt+'" /><input type="hidden" class="est_old_stone_val" value="'+estval.stone_wt+'"  name="purchase[stone_wt][]"/></td>'

    							+'<td><span>'+estval.wastage_percent+'</span><input type="hidden" class="pur_wastage" name="purchase[wastage][]" value="'+estval.wastage_percent+'" /></td>'

    							+'<td><span>'+estval.wastage_wt+'</span><input type="hidden" class="pur_wastage_wt" name="purchase[wastage_wt][]" value="'+estval.wastage_wt+'" /></td>'

    							+'<td><input type="number" class="pur_discount" name="purchase[discount][]" value="" disabled/></td>'

    							+'<td><a href="#" onClick="create_new_empty_bill_purchase_stone_item($(this).closest(\'tr\'));" class="btn btn-success"><i class="fa fa-plus"></i></a><input type="hidden" class="stone_details" value='+(JSON.stringify(stone_details))+' name="purchase[stone_details][]"/><input type="hidden" class="other_stone_price" value="'+other_stone_price+'" /><input type="hidden" class="other_stone_wt" value="'+other_stone_wt+'" /><input type="hidden" class="bill_material_price" value=""/></td>'

    							+'<td><input type="number" class="bill_amount" name="purchase[billamount][]" value="'+estval.amount+'" step="any" readonly /><input type="hidden" class="bill_rate_per_grm" name="purchase[rate_per_grm][]" value="'+estval.rate_per_gram+'" step="any" readonly /></td>'

    							+'<td><span>'+estval.esti_no+'</span><input type="hidden" class="pur_est_id" name="purchase[estid][]" value="'+estval.est_id+'" /><input type="hidden" class="pur_esti_no" name="purchase[esti_no][]" value="'+estval.esti_no+'" /></td>'

    							+'<td><a href="#" onClick="remove_row($(this).closest(\'tr\'));" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'

    							+'</tr>';

    						}

    							$('#purchase_item_details tbody').append(row);  

						});

					  

						calculateSaleBillRowTotal();

					}

					else

					{

						//$('#est_olditems_to_sale_convertion_tbl').show();

						//$('#purchase_item_details tbody').empty();

						updateorderAdvance_purchase_Bill(data.responsedata.old_matel_details);

					}

				}

				else{

					$('#est_olditems_to_sale_convertion tbody').empty();

					$('#est_olditems_to_sale_convertion_tbl').hide();

				}

			}else{

				//$("div.overlay").css("display", "none"); 

				$.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+data.message});

			}

        }

     });

}

$(document).on('change',".select_est_details",function()

{

		var item_pcs=0;

		var item_gwt=0;

		var total_pcs=$('#blc_pcs').html();

		var total_gwt=$('#blc_gwt').html();

		$('#est_items_to_sale_convertion > tbody tr').each(function(idx, row){

			curRow = $(this);

			if(curRow.find('.select_est_details').is(':checked'))

			{

				if(curRow.find('.is_non_tag').val()==1)

				{	

					item_pcs+= parseFloat(curRow.find('.est_pcs').val());

					item_gwt+=parseFloat(curRow.find('.est_gross_val').val());

				}

			}

		});

		if(item_pcs<=total_pcs || item_gwt<=total_gwt)

		{

			$('#update_estimation_to_bill').attr('disabled',false);

		}else{

			$('#update_estimation_to_bill').attr('disabled',true);	

		}

		$('#tot_pcs').html(item_pcs);

		$('#tot_wt').html(item_gwt);

});

function calculateEsttoSaleConvertion(){

	$('#est_items_to_sale_convertion > tbody tr').each(function(idx, row){

		curRow = $(this);

		var gross_wt = (isNaN(curRow.find('.est_gross_val').val()) || curRow.find('.est_gross_val').val() == '')  ? 0 : curRow.find('.est_gross_val').val();

		var less_wt  = (isNaN(curRow.find('.est_less_val').val()) || curRow.find('.est_less_val').val() == '')  ? 0 : curRow.find('.est_less_val').val();

		var net_wt = parseFloat(gross_wt) - parseFloat(less_wt);

		var calculation_type = (isNaN(curRow.find('.est_cal_type').val()) || curRow.find('.est_cal_type').val() == '')  ? 0 : curRow.find('.est_cal_type').val();

		var stone_price  = (isNaN(curRow.find('.est_stone_price').val()) || curRow.find('.est_stone_price').val() == '')  ? 0 : curRow.find('.est_stone_price').val(); 

		

		var certification_price  = (isNaN(curRow.find('.est_certification_price').val()) || curRow.find('.est_certification_price').val() == '')  ? 0 : curRow.find('.est_certification_price').val(); 

		var order_no  = (isNaN(curRow.find('.order_no').val()) || curRow.find('.order_no').val() == '')  ? '' : curRow.find('.order_no').val(); 

		

		var material_price  = (isNaN(curRow.find('.est_material_price').val()) || curRow.find('.est_material_price').val() == '')  ? 0 : curRow.find('.est_material_price').val();

		var total_price = 0;

		var rate_per_grm = 0;

		if(curRow.find('.est_metal_type').val() == 1){

		  rate_per_grm = (isNaN($('.per-grm-sale-value').html()) || $('.per-grm-sale-value').html() == '')  ? 0 : parseFloat($('.per-grm-sale-value').html());

		}else{

			 rate_per_grm = (isNaN($('.silver_per-grm-sale-value').html()) || $('.silver_per-grm-sale-value').html() == '')  ? 0 : parseFloat($('.silver_per-grm-sale-value').html());

		}

		var inclusive_tax_rate = 0;

		var total_tax = 0;

		var rate_with_mc = 0;

		var tax_group = curRow.find('.est_tax_id').val();

		var discount = (isNaN(curRow.find('.est_discount').val()) || curRow.find('.est_discount').val() == '')  ? 0 : curRow.find('.est_discount').val();

		var retail_max_mc = (isNaN(curRow.find('.est_mc_value').val()) || curRow.find('.est_mc_value').val() == '')  ? 0 : curRow.find('.est_mc_value').val();

		var tot_wastage = (isNaN(curRow.find('.est_wastage_percent').val()) || curRow.find('.est_wastage_percent').val() == '')  ? 0 : curRow.find('.est_wastage_percent').val();

		if(calculation_type == 0){ 

			var wast_wgt      = parseFloat(parseFloat(gross_wt) * parseFloat(tot_wastage/100)).toFixed(3);

			if(curRow.find('.est_mc_type').val() != 3){

    			var mc_type       =  parseFloat(curRow.find('.est_mc_type').val() == 2 ? parseFloat(retail_max_mc * gross_wt ) : parseFloat(retail_max_mc * curRow.find('.est_pcs').val()));

    			// Metal Rate + Stone + OM + Wastage + MC

    			rate_with_mc = parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(certification_price));

			}else{

			    var mc_type       =  parseFloat(parseFloat(retail_max_mc * gross_wt ) * curRow.find('.est_pcs').val());

    			// Metal Rate + Stone + OM + Wastage + MC

    			rate_with_mc = parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(certification_price));

			

			}

		}

		else if(calculation_type == 1){

			var wast_wgt      = parseFloat(parseFloat(net_wt) * parseFloat(tot_wastage/100)).toFixed(3);

			if(curRow.find('.est_mc_type').val() != 3){

    			var mc_type       =  parseFloat(curRow.find('.est_mc_type').val() == 2 ? parseFloat(retail_max_mc * net_wt ) : parseFloat(retail_max_mc * curRow.find('.est_pcs').val()));

    			// Metal Rate + Stone + OM + Wastage + MC

    			rate_with_mc = parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(certification_price));

			}else{

			    var mc_type       =  parseFloat(parseFloat(retail_max_mc * net_wt ) * curRow.find('.est_pcs').val());

    			rate_with_mc = parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(certification_price));

			}

		}

		else if(calculation_type == 2){ 

			var wast_wgt      = parseFloat(parseFloat(net_wt) * parseFloat(tot_wastage/100)).toFixed(3);

			if(curRow.find('.est_mc_type').val() != 3){

    			var mc_type       =  parseFloat(curRow.find('.est_mc_type').val() == 2 ? parseFloat(retail_max_mc * gross_wt ) : parseFloat(retail_max_mc * curRow.find('.est_pcs').val()));

    			// Metal Rate + Stone + OM + Wastage + MC

    

    		    rate_with_mc = parseFloat((parseFloat(rate_per_grm) * (parseFloat(wast_wgt) + parseFloat(net_wt)))+ parseFloat(mc_type))+parseFloat(stone_price)+parseFloat(certification_price);

			}else{

			    var mc_type       =  parseFloat(parseFloat(retail_max_mc * gross_wt ) * curRow.find('.est_pcs').val());

			     rate_with_mc = parseFloat((parseFloat(rate_per_grm) * (parseFloat(wast_wgt) + parseFloat(net_wt)))+ parseFloat(mc_type))+parseFloat(stone_price)+parseFloat(certification_price);

			}

		}

			console.log(parseFloat(parseFloat(rate_per_grm) * (parseFloat(wast_wgt) + parseFloat(net_wt)))+parseFloat(mc_type));

		/*if(calculation_type == 0){

			rate_with_mc = parseFloat((parseFloat(rate_per_grm * gross_wt) + parseFloat(retail_max_mc * gross_wt)) - discount);

		}else if(calculation_type == 1){

			rate_with_mc = parseFloat((parseFloat(rate_per_grm * net_wt) + parseFloat(retail_max_mc * net_wt)) -discount );

		}else if(calculation_type == 2){

			rate_with_mc = parseFloat((((parseFloat(rate_per_grm) * parseFloat(net_wt) + parseFloat(tot_wastage * net_wt))) + parseFloat(retail_max_mc * net_wt)) - discount);

		}*/

		rate_with_mc = (rate_with_mc - discount);

		total_tax = getTaxValueForItem(rate_with_mc, tax_group);

		inclusive_tax_rate = parseFloat(rate_with_mc + parseFloat(total_tax)).toFixed(2);

		curRow.find('td:eq(14)').html(parseFloat(total_tax).toFixed(2));

		curRow.find('.est_item_cost').html(parseFloat(inclusive_tax_rate).toFixed(2));

		curRow.find('.est_item_cost_val').val(inclusive_tax_rate);

		curRow.find('.est_wastage_wt').html(wast_wgt);

		console.log('Total Price :'+inclusive_tax_rate);

	  console.log('rate_with_mc :'+rate_with_mc);

	  console.log('wast_wgt :'+wast_wgt);

	  console.log('tot_wastage :'+tot_wastage);

	  console.log('calculation_type :'+calculation_type);

	  console.log('net_wt :'+net_wt);

	  console.log('mc_type :'+mc_type);

	  console.log('rate_per_grm :'+rate_per_grm);

	  console.log('retail_max_mc :'+retail_max_mc);

	  console.log('---------------');

	});

}

function calculateOldEsttoSaleConvertion(){

	$('#est_olditems_to_sale_convertion > tbody tr').each(function(idx, row){

		curRow = $(this);

		var gross_wt = (isNaN(curRow.find('.est_old_gross_val').val()) || curRow.find('.est_old_gross_val').val() == '')  ? 0 : curRow.find('.est_old_gross_val').val();

		var less_wt  = (isNaN(curRow.find('.est_old_less_val').val()) || curRow.find('.est_old_less_val').val() == '')  ? 0 : curRow.find('.est_old_less_val').val();

		var dust_wt  = (isNaN(curRow.find('.est_old_dust_val').val()) || curRow.find('.est_old_dust_val').val() == '')  ? 0 : curRow.find('.est_old_dust_val').val();

		var stone_wt  = (isNaN(curRow.find('.est_old_stone_val').val()) || curRow.find('.est_old_stone_val').val() == '')  ? 0 : curRow.find('.est_old_stone_val').val();

		var other_stone_wt  = (isNaN(curRow.find('.other_stone_wt').val()) || curRow.find('.other_stone_wt').val() == '')  ? 0 : curRow.find('.other_stone_wt').val();

		var other_stone_price  = (isNaN(curRow.find('.other_stone_price').val()) || curRow.find('.other_stone_price').val() == '')  ? 0 : curRow.find('.other_stone_price').val();

		var other_stone_price  = (isNaN(curRow.find('.other_stone_price').val()) || curRow.find('.other_stone_price').val() == '')  ? 0 : curRow.find('.other_stone_price').val();

		var other_stone_price  = (isNaN(curRow.find('.other_stone_price').val()) || curRow.find('.other_stone_price').val() == '')  ? 0 : curRow.find('.other_stone_price').val();

		var cal_weight  = (isNaN(curRow.find('.est_old_wastage_val').val()) || curRow.find('.est_old_wastage_val').val() == '')  ? 0 : curRow.find('.est_old_wastage_val').val();

	    var net_wt = (parseFloat(gross_wt) -parseFloat(dust_wt)-parseFloat(stone_wt)-parseFloat(other_stone_wt)-parseFloat(cal_weight)).toFixed(3);

        curRow.find('.est_old_net_wt').html(net_wt);

		var total_price = 0;

		var rate_per_grm = 0;

		rate_per_grm = curRow.find('.est_old_rate_per_gram_val').val();

		var discount = (isNaN(curRow.find('.est_old_discount').val()) || curRow.find('.est_old_discount').val() == '')  ? 0 : curRow.find('.est_old_discount').val();

		var tot_wastage = (isNaN(curRow.find('.est_old_wastage_percent').val()) || curRow.find('.est_old_wastage_percent').val() == '')  ? 0 : curRow.find('.est_old_wastage_percent').val();

//		cal_weight = parseFloat((net_wt * (tot_wastage / 100))).toFixed(3);

		total_price = parseFloat((parseFloat(rate_per_grm) * (parseFloat(net_wt))) - discount);

		total_price=parseFloat(parseFloat(total_price)+parseFloat(other_stone_price));

		curRow.find('.est_old_amount').html(Math.round(parseFloat(total_price).toFixed(2)));

		curRow.find('.est_old_item_amount_val').val((Math.round(parseFloat(total_price).toFixed(2))));

		console.log('total_price:'+total_price);

		console.log('rate_per_grm:'+rate_per_grm);

		console.log('discount:'+discount);

		console.log('tot_wastage:'+tot_wastage);

		console.log('cal_weight:'+cal_weight);

		console.log('other_stone_price:'+other_stone_price);

		console.log('net_wt:'+net_wt);

		console.log('gross_wt:'+gross_wt);

		console.log('dust_wt:'+dust_wt);

		console.log('stone_wt:'+stone_wt);

		console.log('stone_wt:'+stone_wt);1

		console.log('other_stone_wt:'+other_stone_wt);

		console.log('-----------------');

	});

}

$('#disc_apply').on('click',function(){

    

        var bill_type  = $("input[name='billing[bill_type]']:checked").val();

        if(bill_type==9) //Order Delivery

	     {

	         calculateOrderSaleBillRowTotal();

	     }else{

	         calculateSaleBillRowTotal();

	     }

});



function gift_voucher_redeem(bill_id,utilize_for,gift_type,sale_value,credit_value,voucher_type,id_set_gift_voucher,id_gift_voucher)

{

    

    var bill_type       = $("input[name='billing[bill_type]']:checked").val();

    var allow_redeem    =false;

    var sales_weight     =0;

    var sales_amount     =0;

    if(id_set_gift_voucher!='')

    {

        if(utilize_for==0)

        {

            allow_redeem=true;

        }

        else if(utilize_for==1 || utilize_for==2)

        {

            $('#billing_sale_details > tbody tr').each(function(idx, row){

                 curRow = $(this);

                 if(curRow.find('.sale_metal_type').val() == utilize_for)

            	{

            	    redeem_sales_amt += parseFloat((isNaN($(this).find('.bill_amount').val()) || $(this).find('.bill_amount').val() == '')  ? 0 : $(this).find('.bill_amount').val());

            	    allow_redeem=true;

            	    return true;

            	}

             });

        }

        else if(utilize_for==3)

        {

              my_Date = new Date();

                $.ajax({

                url:base_url+ "index.php/admin_ret_billing/GiftRedeemProduct?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),

                data:  {'id_set_gift_voucher':id_set_gift_voucher},

                type:"POST",

                dataType: "json",

                async:false,

                success:function(data)

                    {

                        

                        $('#billing_sale_details > tbody tr').each(function(idx, row){

                            curRow = $(this);

                             $.each(data,function(key,item){

                                 if(item.utilize==1)

                                {

                                    if(curRow.find('.sale_product_id').val()==item.id_product)

                                    {

                                        redeem_sales_amt += parseFloat((isNaN(curRow.find('.bill_amount').val()) || curRow.find('.bill_amount').val() == '')  ? 0 : curRow.find('.bill_amount').val());

                                        allow_redeem=true;

                                        return true;

                                    }

                                }

                             });

                        });

                   

                    },

                });

        }

    }

    else

    {

        if(utilize_for==0)

        {

            allow_redeem=true;

        }

        else if(utilize_for==1 || utilize_for==2)

        {

             $('#billing_sale_details > tbody tr').each(function(idx, row){

                 curRow = $(this);

                 if(curRow.find('.sale_metal_type').val() == utilize_for)

            	{

            	    redeem_sales_amt += parseFloat((isNaN($(this).find('.bill_amount').val()) || $(this).find('.bill_amount').val() == '')  ? 0 : $(this).find('.bill_amount').val());

            	    allow_redeem=true;

            	    return true;

            	}

             });

        }

        else if(utilize_for==3)

        {

              my_Date = new Date();

                $.ajax({

                url:base_url+ "index.php/admin_ret_billing/GeneralGiftRedeemProduct?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),

                data:  {'id_gift_voucher':id_gift_voucher},

                type:"POST",

                dataType: "json",

                async:false,

                success:function(data)

                    {

                        $('#billing_sale_details > tbody tr').each(function(idx, row){

                            curRow = $(this);

                             $.each(data,function(key,item){

                                 if(item.utilize==1)

                                {

                                    if(curRow.find('.sale_product_id').val()==item.id_product)

                                    {

                                        redeem_sales_amt += parseFloat((isNaN($(this).find('.bill_amount').val()) || $(this).find('.bill_amount').val() == '')  ? 0 : $(this).find('.bill_amount').val());

                                        allow_redeem=true;

                                        return true;

                                    }

                                }

                             });

                        });

                   

                    },

                });

        }

        if(allow_redeem)

        {

                 $('#billing_sale_details > tbody  > tr').each(function(index, tr) {

                		if($(this).find('.bill_gross_val').val() != "" && $(this).find('.bill_amount').val() != "" )

                		{

                			sales_weight += parseFloat((isNaN($(this).find('.bill_net_val').val()) || $(this).find('.bill_net_val').val() == '')  ? 0 : $(this).find('.bill_net_val').val());

                			sales_amount += parseFloat((isNaN($(this).find('.bill_amount').val()) || $(this).find('.bill_amount').val() == '')  ? 0 : $(this).find('.bill_amount').val());

                		}

                	});

                

                    if((parseFloat(sale_value)<=parseFloat(sales_amount)) && (voucher_type==1))

                    {

                        allow_redeem=true;

                    }

                    else if((voucher_type==2) && (sale_value<=parseFloat(sales_weight)))

                    {

                        allow_redeem=true;

                    }

                    else

                    {

                        allow_redeem=false;

                        if(voucher_type==1)

                        {

                            alert('Minimum Sale Amount is Rs.'+sale_value);

                        }else{

                            alert('Minimum Sale Weight is Rs.'+sale_value);

                        }

                        

                    }

        }

    }

    return allow_redeem;

}





function check_gift_vocuher_issue()

{

    var gift_type               =$('#gift_type').val();

    var utilize_for             =$('#utilize_for').val();

    var issue_for               =$('#issue_for').val();

    var bill_value              =$('#bill_value').val();

    var credit_value            =$('#credit_value').val();

    var id_set_gift_voucher     =$('#id_set_gift_voucher').val();

    var validate_date           =$('#validate_date').val();

    var calc_type               =$('#calc_type').val();

    var allow_issue             =false;

    

    var sale_weight=0;

    var sales_amt=0;

    

    /*

       1.Issue For  0-All Metal,1-Gold,2-Silver,3-Product Based.

       2.utilize_for 0-All,1-Gold,2-Silver,3-Product Based

       3.Gift type 1-Amt to Amt,2-Amt to Weight,3-Weight to Amt,4-Weight to Amt

       4.calc_type 1-Flat,2-Each

       5.bill_value-Minimum Sales Amount

       6.credit_value-Gift Issue Amount.

    */

    

    

    if(issue_for==0) //All Metal

    {

         $('#billing_sale_details > tbody tr').each(function(idx, row){

            curRow = $(this);

                sale_weight += parseFloat((isNaN($(this).find('.bill_net_val').val()) || $(this).find('.bill_net_val').val() == '')  ? 0 : $(this).find('.bill_net_val').val());

                sales_amt += parseFloat((isNaN($(this).find('.bill_amount').val()) || $(this).find('.bill_amount').val() == '')  ? 0 : $(this).find('.bill_amount').val());

        });

        

         if(gift_type==1)

        {

            if(bill_value<=sales_amt)

            {

                $('.gift_row').css('display','block');

    	        $('.summary_gift_voucher').css('display','block');

    	        if(calc_type==1)

    	        {

    	            var gift_amt=credit_value;

    	        }else{

    	            var gift_amt=parseFloat(parseFloat(sales_amt/bill_value)*credit_value);

    	        }

    	        $('#gift_voucher_amt').val(Math.round(gift_amt));

    	        $('.summary_gift_voucher').html('Rs.'+Math.round(gift_amt)+'  Valid Till '+validate_date);

            }else

    	    {

    	         $('#gift_voucher_amt').val(0);

    	         $('.summary_gift_voucher').html('');

    	         $('.gift_row').css('display','none');

                 $('.summary_gift_voucher').css('display','none');

    	    }

        }

        else if(gift_type==2)

        {

            if(bill_value<=sales_amt)

            {

                 $('.gift_row').css('display','block');

            	    $('.summary_gift_voucher').css('display','block');

            	     if(calc_type==1)

            	     {

            	         gift_amt=credit_value;

            	     }

            	     else if(calc_type==2)

            	     {

            	         var gift_amt=parseFloat(parseFloat(sales_amt/bill_value)*credit_value);

            	     }

            	     $('#gift_voucher_amt').val(Math.round(gift_amt));

    	             $('.summary_gift_voucher').html(Math.round(gift_amt)+' Gram For '+(utilize_for==1 ? 'Gold' :'Silver')+' Valid Till '+validate_date);

            }

            else

            {

                 $('#gift_voucher_amt').val(0);

    	         $('.summary_gift_voucher').html('');

    	         $('.gift_row').css('display','none');

                 $('.summary_gift_voucher').css('display','none');

            }



        }

        else if(gift_type==3)

        {

    	    if(bill_value<=sale_weight)

    	    {

    	        var gift_voucher_amt=parseFloat(credit_value*sale_weight);

    	        $('.gift_row').css('display','block');

    	        $('.summary_gift_voucher').css('display','block');

    	        $('#gift_voucher_amt').val(Math.round(gift_voucher_amt));

    	        $('.summary_gift_voucher').html('Rs.'+Math.round(gift_voucher_amt)+'  Valid Till '+validate_date);

    	    }

    	    else

    	    {

    	         $('.gift_row').css('display','none');

                 $('.summary_gift_voucher').css('display','none');

                 $('#gift_voucher_amt').val(0);

    	         $('.summary_gift_voucher').html('');

    	    }

        }

        else if(gift_type==4)

        {

             if(bill_value<=sale_weight)

            {

                 $('.gift_row').css('display','block');

            	 $('.summary_gift_voucher').css('display','block');

            	     if(calc_type==1)

            	     {

            	         gift_amt=credit_value;

            	     }

            	     else if(calc_type==2)

            	     {

            	         var gift_amt=parseFloat(parseFloat(sale_weight/bill_value)*credit_value);

            	     }

            	     $('#gift_voucher_amt').val(Math.round(gift_amt));

    	             $('.summary_gift_voucher').html(Math.round(gift_amt)+' Gram For '+(utilize_for==1 ? 'Gold' :'Silver')+' Valid Till '+validate_date);

            }

            else

            {

                 $('#gift_voucher_amt').val(0);

    	         $('.summary_gift_voucher').html('');

    	         $('.gift_row').css('display','none');

                 $('.summary_gift_voucher').css('display','none');

            }

        }

        

    }

    else if(issue_for==1) //For Gold

    {

        $('#billing_sale_details > tbody tr').each(function(idx, row){

            curRow = $(this);

            if(curRow.find('.sale_metal_type').val()==1)

            {

                sale_weight += parseFloat((isNaN($(this).find('.bill_net_val').val()) || $(this).find('.bill_net_val').val() == '')  ? 0 : $(this).find('.bill_net_val').val());

                sales_amt += parseFloat((isNaN($(this).find('.bill_amount').val()) || $(this).find('.bill_amount').val() == '')  ? 0 : $(this).find('.bill_amount').val());

            }

        });

        

        if(gift_type==1)

        {

            if(bill_value<=sales_amt)

            {

                $('.gift_row').css('display','block');

    	        $('.summary_gift_voucher').css('display','block');

    	        if(calc_type==1)

    	        {

    	            var gift_amt=credit_value;

    	        }else{

    	            var gift_amt=parseFloat(parseFloat(sales_amt/bill_value)*credit_value);

    	        }

    	        $('#gift_voucher_amt').val(Math.round(gift_amt));

    	        $('.summary_gift_voucher').html('Rs.'+Math.round(gift_amt)+'  Valid Till '+validate_date);

            }else

    	    {

    	         $('.gift_row').css('display','none');

                 $('.summary_gift_voucher').css('display','none');

                 $('#gift_voucher_amt').val(0);

    	         $('.summary_gift_voucher').html('');

    	    }

        }

        else if(gift_type==2)

        {

            if(bill_value<=sales_amt)

            {

                 $('.gift_row').css('display','block');

            	 $('.summary_gift_voucher').css('display','block');

            	 

            	     if(calc_type==1)

            	     {

            	         gift_amt=credit_value;

            	     }

            	     else if(calc_type==2)

            	     {

            	         var gift_amt=parseFloat(parseFloat(sales_amt/bill_value)*credit_value);

            	     }

            	     $('#gift_voucher_amt').val(Math.round(gift_amt));

    	             $('.summary_gift_voucher').html(Math.round(gift_amt)+' Gram For Gold '+(utilize_for==1 ? 'Gold' :'Silver')+' Valid Till '+validate_date);

            }

            else

            {

                 $('#gift_voucher_amt').val(0);

    	         $('.summary_gift_voucher').html('');

    	         $('.gift_row').css('display','none');

                 $('.summary_gift_voucher').css('display','none');

            }

        }

        else if(gift_type==3)

        {

    	    if(bill_value<=sale_weight)

    	    {

    	             if(calc_type==1)

            	     {

            	         gift_amt=credit_value;

            	     }

            	     else if(calc_type==2)

            	     {

            	         var gift_amt=parseFloat(sale_weight*credit_value).toFixed(3);

            	     }

               

    	        $('.gift_row').css('display','block');

    	        $('.summary_gift_voucher').css('display','block');

    	        $('#gift_voucher_amt').val(Math.round(gift_amt));

    	        $('.summary_gift_voucher').html('Rs.'+Math.round(gift_amt)+'  Valid Till '+validate_date);

    	    }

    	    else

    	    {

    	        $('.gift_row').css('display','none');

                $('.summary_gift_voucher').css('display','none');

                $('#gift_voucher_amt').val(0);

    	        $('.summary_gift_voucher').html('');

    	    }

        }

        else if(gift_type==4)

        {

              if(bill_value<=sale_weight)

                {

                     $('.gift_row').css('display','block');

                	 $('.summary_gift_voucher').css('display','block');

                	     if(calc_type==1)

                	     {

                	         gift_amt=credit_value;

                	     }

                	     else if(calc_type==2)

                	     {

                	         var gift_amt=parseFloat(parseFloat(sale_weight/bill_value)*credit_value);

                	     }

                	     $('#gift_voucher_amt').val(Math.round(gift_amt));

        	             $('.summary_gift_voucher').html(Math.round(gift_amt)+' Gram For '+(utilize_for==1 ? 'Gold' :'Silver')+' Valid Till '+validate_date);

                }

                else

                {

                     $('#gift_voucher_amt').val(0);

        	         $('.summary_gift_voucher').html('');

        	         $('.gift_row').css('display','none');

                     $('.summary_gift_voucher').css('display','none');

                }

        }

    }

    else if(issue_for==2) //For Silver

    {

         $('#billing_sale_details > tbody tr').each(function(idx, row){

            curRow = $(this);

           if(curRow.find('.sale_metal_type').val()==2)

            {

                sale_weight += parseFloat((isNaN($(this).find('.bill_net_val').val()) || $(this).find('.bill_net_val').val() == '')  ? 0 : $(this).find('.bill_net_val').val());

                sales_amt += parseFloat((isNaN($(this).find('.bill_amount').val()) || $(this).find('.bill_amount').val() == '')  ? 0 : $(this).find('.bill_amount').val());

            }

        });

        

        if(gift_type==1)

        {

            if(bill_value<=sales_amt)

            {

                $('.gift_row').css('display','block');

    	        $('.summary_gift_voucher').css('display','block');

    	        if(calc_type==1)

    	        {

    	            var gift_amt=credit_value;

    	        }else{

    	            var gift_amt=parseFloat(parseFloat(sales_amt/bill_value)*credit_value);

    	        }

    	        $('#gift_voucher_amt').val(Math.round(gift_amt));

    	        $('.summary_gift_voucher').html('Rs.'+Math.round(gift_amt)+'  Valid Till '+validate_date);

            }else

    	    {

    	         $('.gift_row').css('display','none');

                 $('.summary_gift_voucher').css('display','none');

                 $('#gift_voucher_amt').val(0);

    	         $('.summary_gift_voucher').html('');

    	    }

        }

        else if(gift_type==2)

        {

            if(bill_value<=sales_amt)

            {

                 $('.gift_row').css('display','block');

            	    $('.summary_gift_voucher').css('display','block');

            	     if(calc_type==1)

            	     {

            	         gift_amt=credit_value;

            	     }

            	     else if(calc_type==2)

            	     {

            	         var gift_amt=parseFloat(parseFloat(sales_amt/bill_value)*credit_value);

            	     }

            	     $('#gift_voucher_amt').val(Math.round(gift_amt));

    	             $('.summary_gift_voucher').html(Math.round(gift_amt)+' Gram For '+(utilize_for==1 ? 'Gold' :'Silver')+' Valid Till '+validate_date);

            }

            else

            {

                 $('#gift_voucher_amt').val(0);

    	         $('.summary_gift_voucher').html('');

    	         $('.gift_row').css('display','none');

                 $('.summary_gift_voucher').css('display','none');

            }

        }

        else if(gift_type==3)

        {

    	    if(bill_value<=sale_weight)

    	    {

    	        var gift_voucher_amt=parseFloat(credit_value*sale_weight);

    	        $('.gift_row').css('display','block');

    	        $('.summary_gift_voucher').css('display','block');

    	        $('#gift_voucher_amt').val(Math.round(gift_voucher_amt));

    	        $('.summary_gift_voucher').html('Rs.'+Math.round(gift_voucher_amt)+'  Valid Till '+validate_date);

    	    }

    	    else

    	    {

    	        $('.gift_row').css('display','none');

                $('.summary_gift_voucher').css('display','none');

                $('#gift_voucher_amt').val(0);

    	        $('.summary_gift_voucher').html('');

    	    }

        }

        else if(gift_type==4)

        {

             if(bill_value<=sale_weight)

                {

                     $('.gift_row').css('display','block');

                	 $('.summary_gift_voucher').css('display','block');

                	     if(calc_type==1)

                	     {

                	         gift_amt=credit_value;

                	     }

                	     else if(calc_type==2)

                	     {

                	         var gift_amt=parseFloat(parseFloat(sale_weight/bill_value)*credit_value);

                	     }

                	     $('#gift_voucher_amt').val(Math.round(gift_amt));

        	             $('.summary_gift_voucher').html(Math.round(gift_amt)+' Gram For '+(utilize_for==1 ? 'Gold' :'Silver')+' Valid Till '+validate_date);

                }

                else

                {

                     $('#gift_voucher_amt').val(0);

        	         $('.summary_gift_voucher').html('');

        	         $('.gift_row').css('display','none');

                     $('.summary_gift_voucher').css('display','none');

                }

        }

    }

    else if(issue_for==3) //For Products

    {

            my_Date = new Date();

            $.ajax({

            url:base_url+ "index.php/admin_ret_billing/getGiftProducts?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),

            data:  {'id_set_gift_voucher':id_set_gift_voucher},

            type:"POST",

            dataType: "json",

            async:false,

            success:function(data)

                {

                var allow_issue=false;

                $.each(data,function(key,item){

                    $('#billing_sale_details > tbody tr').each(function(idx, row){

                        curRow = $(this);

                        if(curRow.find('.sale_product_id').val()==item.id_product && item.issue==1)

                        {

                            allow_issue=true;

                            return true;

                        }

                    });

                });

                if(allow_issue)

                {

                   

                     $.each(data,function(key,item){

                         if( $('#billing_sale_details > tbody tr').length>0)

                         {

                           $('#billing_sale_details > tbody tr').each(function(idx, row){

                               curRow = $(this);

                               if(curRow.find('.sale_product_id').val()==item.id_product && item.issue==1)

                               {

                                    sale_weight += parseFloat((isNaN($(this).find('.bill_net_val').val()) || $(this).find('.bill_net_val').val() == '')  ? 0 : $(this).find('.bill_net_val').val());

                                    sales_amt += parseFloat((isNaN($(this).find('.bill_amount').val()) || $(this).find('.bill_amount').val() == '')  ? 0 : $(this).find('.bill_amount').val());

                                   

                                    if(gift_type==1)

                                    {

                                        if(bill_value<=sales_amt)

                                        {

                                            $('.gift_row').css('display','block');

                                	        $('.summary_gift_voucher').css('display','block');

                                	        if(calc_type==1)

                                	        {

                                	            var gift_amt=credit_value;

                                	        }else{

                                	            var gift_amt=parseFloat(parseFloat(sales_amt/bill_value)*credit_value);

                                	        }

                                	        $('#gift_voucher_amt').val(Math.round(gift_amt));

                                	        $('.summary_gift_voucher').html('Rs.'+Math.round(gift_amt)+'  Valid Till '+validate_date);

                                        }

                                        else

                                	    {

                                	         $('.gift_row').css('display','none');

                                             $('.summary_gift_voucher').css('display','none');

                                	    }

                                    }

                                    else if(gift_type==3)

                                    {

                                	    if(bill_value<=sale_weight)

                                	    {

                                	        if(calc_type==1)

                                	        {

                                	            var gift_voucher_amt=credit_value;

                                	        }

                                	        else{

                                	            var gift_voucher_amt=parseFloat(sale_weight*credit_value);

                                	        }

                                	        $('.gift_row').css('display','block');

                                	        $('.summary_gift_voucher').css('display','block');

                                	        $('#gift_voucher_amt').val(Math.round(gift_voucher_amt));

                                	        $('.summary_gift_voucher').html('Rs.'+Math.round(gift_voucher_amt)+'  Valid Till '+validate_date);

                                	    }

                                	    else

                                	    {

                                            $('.gift_row').css('display','none');

                                            $('.summary_gift_voucher').css('display','none');

                                            $('#gift_voucher_amt').val(0);

                                            $('.summary_gift_voucher').html('');

                                	    }

                                    }

                                    else if(gift_type==4)

                                    {

                                         if(bill_value<=sale_weight)

                                            {

                                                 $('.gift_row').css('display','block');

                                            	 $('.summary_gift_voucher').css('display','block');

                                            	     if(calc_type==1)

                                            	     {

                                            	         gift_amt=credit_value;

                                            	     }

                                            	     else if(calc_type==2)

                                            	     {

                                            	         var gift_amt=parseFloat(parseFloat(sale_weight/bill_value)*credit_value);

                                            	     }

                                            	     $('#gift_voucher_amt').val(Math.round(gift_amt));

                                    	             $('.summary_gift_voucher').html(Math.round(gift_amt)+' Gram For '+(utilize_for==1 ? 'Gold' :'Silver')+' Valid Till '+validate_date);

                                            }

                                            else

                                            {

                                                 $('#gift_voucher_amt').val(0);

                                    	         $('.summary_gift_voucher').html('');

                                    	         $('.gift_row').css('display','none');

                                                 $('.summary_gift_voucher').css('display','none');

                                            }

                                    }

                                    

                               }

                           });

                           }else{

                                $('.gift_row').css('display','none');

                                $('.summary_gift_voucher').css('display','none');

                                $('#gift_voucher_amt').val(0);

                                $('.summary_gift_voucher').html('');

                           }

                       });

                    }else{

                                $('.gift_row').css('display','none');

                                $('.summary_gift_voucher').css('display','none');

                                $('#gift_voucher_amt').val(0);

                                $('.summary_gift_voucher').html('');

                    }

                },

            });

    }



    

}



function calculateSaleBillRowTotal()

{

    

	$('#billing_sale_details > tbody tr').each(function(idx, row){

	    

	    $('#gift_voucher_modal').attr('disabled',false);

	    

		curRow = $(this);

		var gross_wt = (isNaN(curRow.find('.bill_gross_val').val()) || curRow.find('.bill_gross_val').val() == '')  ? 0 : curRow.find('.bill_gross_val').val();

		var less_wt  = (isNaN(curRow.find('.bill_less_val').val()) || curRow.find('.bill_less_val').val() == '')  ? 0 : curRow.find('.bill_less_val').val();

		var net_wt = parseFloat(gross_wt) - parseFloat(less_wt);

		var calculation_type = (isNaN(curRow.find('.sale_cal_type').val()) || curRow.find('.sale_cal_type').val() == '')  ? 0 : curRow.find('.sale_cal_type').val();

		var stone_price  = (isNaN(curRow.find('.bill_stone_price').val()) || curRow.find('.bill_stone_price').val() == '')  ? 0 : curRow.find('.bill_stone_price').val(); 

    

        var certification_price  = (isNaN(curRow.find('.certification_cost').val()) || curRow.find('.certification_cost').val() == '')  ? 0 : curRow.find('.certification_cost').val(); 

		var material_price  = (isNaN(curRow.find('.bill_material_price').val()) || curRow.find('.bill_material_price').val() == '')  ? 0 : curRow.find('.bill_material_price').val();

		var total_price = 0;

		var rate_per_grm = 0;

		var base_value_amt=0;

		var arrived_value_amt=0;

		var arrived_value_tax=0;

		var base_value_tax=0;

		var total_tax_rate=0;

		var cus_state=$('#cus_state').val();

		var cmp_state=$('#cmp_state').val();

		var cgst=0;

		var igst=0;

		var sgst=0;

		

	/*	if(curRow.find('.sale_metal_type').val() == 1){

		  rate_per_grm = (isNaN($('.per-grm-sale-value').html()) || $('.per-grm-sale-value').html() == '')  ? 0 : parseFloat($('.per-grm-sale-value').html());

		}else{

			 rate_per_grm = (isNaN($('.silver_per-grm-sale-value').html()) || $('.silver_per-grm-sale-value').html() == '')  ? 0 : parseFloat($('.silver_per-grm-sale-value').html());

		}*/

		

		rate_field=curRow.find('.rate_field').val();

		var rate_per_grm = (isNaN($('#'+rate_field).val()) ||$('#'+rate_field).val() == '')  ? 0 : parseFloat($('#'+rate_field).val());

		

		

		var inclusive_tax_rate = 0;

		var total_tax = 0;

		var discount=0;

		var disc_type = curRow.find('.disc_type').val();

		var disc_amt = $('#summary_discount_amt').val();

		var total_sales_amt = $('.sale_amt_with_tax').html();

		var tax_group = curRow.find('.sale_tax_group').val();

		var retail_max_mc = (isNaN(curRow.find('.bill_mc').val()) || curRow.find('.bill_mc').val() == '')  ? 0 : curRow.find('.bill_mc').val();

		var tot_wastage = (isNaN(curRow.find('.bill_wastage').val()) || curRow.find('.bill_wastage').val() == '')  ? 0 : curRow.find('.bill_wastage').val();

		if(calculation_type == 0){ 

			var wast_wgt      = parseFloat(parseFloat(gross_wt) * parseFloat(tot_wastage/100)).toFixed(3);

			if(curRow.find('.bill_mctype').val() != 3){

    			var mc_type       =  parseFloat(curRow.find('.bill_mctype').val() == 2 ? parseFloat(retail_max_mc * gross_wt ) : parseFloat(retail_max_mc * curRow.find('.sale_pcs').val()));

    			// Metal Rate + Stone + OM + Wastage + MC

    			rate_with_mc = parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(material_price)+parseFloat(certification_price));

			}else{

			    var mc_type       =  parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt)) * (retail_max_mc/100));

    			// Metal Rate + Stone + OM + Wastage + MC

    			rate_with_mc = parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(material_price)+parseFloat(certification_price));

			}

		}

		else if(calculation_type == 1){

			var wast_wgt      = parseFloat(parseFloat(net_wt) * parseFloat(tot_wastage/100)).toFixed(3);

			if(curRow.find('.bill_mctype').val() != 3){

    			var mc_type       =  parseFloat(curRow.find('.bill_mctype').val() == 2 ? parseFloat(retail_max_mc * net_wt ) : parseFloat(retail_max_mc * curRow.find('.sale_pcs').val()));

    			// Metal Rate + Stone + OM + Wastage + MC

    			rate_with_mc = parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(material_price)+parseFloat(certification_price));

			}else{

			    var mc_type       =  parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt)) * (retail_max_mc/100));

    			// Metal Rate + Stone + OM + Wastage + MC

    			rate_with_mc = parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(material_price)+parseFloat(certification_price));

			}

		}

		else if(calculation_type == 2){ 

			//var wast_wgt      = parseFloat(parseFloat(net_wt) * parseFloat(tot_wastage/100)).toFixed(3);

			var wast_wgt      = parseFloat(parseFloat(net_wt) * parseFloat(tot_wastage/100)).toFixed(3);

			if(curRow.find('.bill_mctype').val() != 3){

    			var mc_type       =  parseFloat(curRow.find('.bill_mctype').val() == 2 ? parseFloat(retail_max_mc * gross_wt ) : parseFloat(retail_max_mc * curRow.find('.sale_pcs').val()));

    			// Metal Rate + Stone + OM + Wastage + MC

    		    rate_with_mc = parseFloat(((parseFloat(rate_per_grm) * (parseFloat(wast_wgt) + parseFloat(net_wt)))+ parseFloat(mc_type))+parseFloat(stone_price)+parseFloat(material_price)+parseFloat(certification_price)).toFixed(2);

			}else{

			    var mc_type       =  parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt)) * (retail_max_mc/100));

    			// Metal Rate + Stone + OM + Wastage + MC

    		    rate_with_mc = parseFloat(((parseFloat(rate_per_grm) * (parseFloat(wast_wgt) + parseFloat(net_wt)))+ parseFloat(mc_type))+parseFloat(stone_price)+parseFloat(material_price)+parseFloat(certification_price)).toFixed(2);

			

			}

		}

		

        else if(calculation_type == 3 || calculation_type == 4){ 

        

            rate_with_mc  = parseFloat((isNaN(curRow.find('.tag_sales_value').val()) || curRow.find('.tag_sales_value').val() == '')  ? 0 : curRow.find('.tag_sales_value').val()); 

        

        } 


		let charge_value = isNaN(parseFloat(curRow.find('.charge_value').val())) ? 0 : curRow.find('.charge_value').val();

		rate_with_mc = parseFloat(rate_with_mc) + parseFloat(charge_value);
	
        if(disc_amt>0)

        {

        	

	        	var disc_per=parseFloat((disc_amt/total_sales_amt)*100);

	        	var  discount=parseFloat((rate_with_mc*disc_per)/100);

	       

	       

	        	rate_with_mc=parseFloat(rate_with_mc-discount).toFixed(2);

	        if(disc_type==2)

	        {

	        	var wastage_amt=parseFloat(wast_wgt*rate_per_grm);

	        	var mc_wast_amt=parseFloat(wastage_amt+mc_type);

	        	var disc_per=parseFloat((discount/mc_wast_amt)*100);

	        	var wast_disc =parseFloat((wastage_amt*disc_per)*100);

	        	var mc_disc =parseFloat((mc_type*disc_per)*100);

	        }

        }

        

        console.log('disc_per:'+disc_per);

        console.log('wast_disc:'+wast_disc);

        console.log('mc_disc:'+mc_disc);

        console.log('discount:'+discount);

       

	

		var base_value_tax=parseFloat(calculate_base_value_tax(rate_with_mc,tax_group)).toFixed(2);

		var base_value_amt=parseFloat(parseFloat(rate_with_mc)+parseFloat(base_value_tax)).toFixed(2);

		var arrived_value_tax=parseFloat(calculate_arrived_value_tax(base_value_amt,tax_group)).toFixed(2);

		var arrived_value_amt=parseFloat(parseFloat(base_value_amt)+parseFloat(arrived_value_tax)).toFixed(2);

		

		var total_tax_rate=parseFloat(parseFloat(base_value_tax)+parseFloat(arrived_value_tax)).toFixed(2);

		//total_tax = getTaxValueForItem(rate_with_mc, tax_group);

		inclusive_tax_rate = arrived_value_amt;

		if(cus_state==cmp_state)

		{

			cgst=parseFloat(parseFloat(total_tax_rate)/2).toFixed(3);

			sgst=parseFloat(parseFloat(total_tax_rate)/2).toFixed(3);

		}else{

			igst=total_tax_rate;

		}

		curRow.find('.bill_taxable_amt').html(parseFloat(rate_with_mc).toFixed(2));

		curRow.find('td:eq(13)').html(parseFloat(total_tax_rate).toFixed(2));

		curRow.find('.total_tax').val(parseFloat(total_tax_rate).toFixed(2));

		curRow.find('.bill_amount').val(inclusive_tax_rate);

		curRow.find('.sale_cgst').val(cgst);

		curRow.find('.sale_sgst').val(sgst);

		curRow.find('.sale_igst').val(igst);

		curRow.find('.bill_discount').val(parseFloat(discount).toFixed(2));

		curRow.find('.per_grm_amount').val(rate_per_grm);

		curRow.find('.bill_wastage_wt').val(wast_wgt);

		curRow.find('.est_wastage_wt').html(wast_wgt);

		curRow.find('.making_charge').html(mc_type);
		
		curRow.find('.bill_sale_net_wt').html(net_wt);

		console.log('calculation_type:'+calculation_type);

		console.log('rate_with_mc:'+rate_with_mc);

		console.log('wast_wgt:'+wast_wgt);

		console.log('mc:'+curRow.find('.bill_mctype').val());

		

		console.log('mc_type:'+mc_type);

		console.log('retail_max_mc:'+retail_max_mc);

		console.log('inclusive_tax_rate:'+inclusive_tax_rate);

		console.log('total_tax:'+total_tax);

		console.log('tax_group:'+tax_group);

		

		console.log('rate_per_grm:'+rate_per_grm);

		console.log('------------');

	});



	calculate_sales_details();  //Calculate Sales Details

	calculate_purchase_details();	//Calculate Purchase Details

}





function calculateOrderSaleBillRowTotal()

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

        }

        else if(items.store_as==1) //Stored as Amount

        {

            adv_paid_amt+=parseFloat(items.paid_advance);

            adv_paid_wt+=parseFloat(items.paid_advance)/parseFloat(items.rate_per_gram); // Convert Amount into Amount

        }

    });

     adv_paid_wt=parseFloat(adv_paid_wt).toFixed(3);

     $('#billing_sale_details > tbody tr').each(function(idx, row){

        curRow = $(this);

        var gross_wt = (isNaN(curRow.find('.bill_gross_val').val()) || curRow.find('.bill_gross_val').val() == '')  ? 0 : curRow.find('.bill_gross_val').val();

		var less_wt  = (isNaN(curRow.find('.bill_less_val').val()) || curRow.find('.bill_less_val').val() == '')  ? 0 : curRow.find('.bill_less_val').val();

		var net_wt = parseFloat(gross_wt) - parseFloat(less_wt);

		var calculation_type = (isNaN(curRow.find('.sale_cal_type').val()) || curRow.find('.sale_cal_type').val() == '')  ? 0 : curRow.find('.sale_cal_type').val();

		var tot_wastage = (isNaN(curRow.find('.bill_wastage').val()) || curRow.find('.bill_wastage').val() == '')  ? 0 : curRow.find('.bill_wastage').val();

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

        var balance_pay_amt=parseFloat(parseFloat($('#goldrate_22ct').val())*parseFloat(balance_weight)).toFixed(2);



    }

    

     average_rate=parseFloat(parseFloat(parseFloat(adv_paid_amt)+parseFloat(balance_pay_amt))/parseFloat(total_weight)).toFixed(2);

    

    console.log('adv_paid_amt:'+adv_paid_amt);

    console.log('adv_paid_wt:'+adv_paid_wt);

    console.log('total_weight:'+total_weight);

    console.log('balance_weight:'+balance_weight);

    console.log('average_rate:'+average_rate);

    console.log('balance_pay_amt:'+balance_pay_amt);

    

	$('#billing_sale_details > tbody tr').each(function(idx, row){

	    

	    $('#gift_voucher_modal').attr('disabled',false);

	    

		curRow = $(this);

		var gross_wt = (isNaN(curRow.find('.bill_gross_val').val()) || curRow.find('.bill_gross_val').val() == '')  ? 0 : curRow.find('.bill_gross_val').val();

		var less_wt  = (isNaN(curRow.find('.bill_less_val').val()) || curRow.find('.bill_less_val').val() == '')  ? 0 : curRow.find('.bill_less_val').val();

		var net_wt = parseFloat(parseFloat(gross_wt) - parseFloat(less_wt)).toFixed(3);

		var calculation_type = (isNaN(curRow.find('.sale_cal_type').val()) || curRow.find('.sale_cal_type').val() == '')  ? 0 : curRow.find('.sale_cal_type').val();

		var stone_price  = (isNaN(curRow.find('.bill_stone_price').val()) || curRow.find('.bill_stone_price').val() == '')  ? 0 : curRow.find('.bill_stone_price').val(); 

		var tax_type  = (calculation_type == 3 ? 1 : 2 ); 

    

        var certification_price  = (isNaN(curRow.find('.certification_cost').val()) || curRow.find('.certification_cost').val() == '')  ? 0 : curRow.find('.certification_cost').val(); 

		var material_price  = (isNaN(curRow.find('.bill_material_price').val()) || curRow.find('.bill_material_price').val() == '')  ? 0 : curRow.find('.bill_material_price').val();

		var total_price = 0;

		var rate_per_grm = 0;

		var base_value_amt=0;

		var arrived_value_amt=0;

		var arrived_value_tax=0;

		var base_value_tax=0;

		var total_tax_rate=0;

		var cus_state=$('#cus_state').val();

		var cmp_state=$('#cmp_state').val();

		var cgst=0;

		var igst=0;

		var sgst=0;

		

		/*if(curRow.find('.sale_metal_type').val() == 1){

		  rate_per_grm = (isNaN($('.per-grm-sale-value').html()) || $('.per-grm-sale-value').html() == '')  ? 0 : parseFloat($('.per-grm-sale-value').html());

		}else{

			 rate_per_grm = (isNaN($('.silver_per-grm-sale-value').html()) || $('.silver_per-grm-sale-value').html() == '')  ? 0 : parseFloat($('.silver_per-grm-sale-value').html());

		}*/

		

		//rate_field=curRow.find('.rate_field').val();

		

		//var rate_per_grm = (isNaN($('#'+rate_field).val()) ||$('#'+rate_field).val() == '')  ? 0 : parseFloat($('#'+rate_field).val());

		

		var rate_per_grm = average_rate;



		

		var inclusive_tax_rate = 0;

		var total_tax = 0;

		var discount=0;

		var disc_type = curRow.find('.disc_type').val();

		var disc_amt = $('#summary_discount_amt').val();

		var total_sales_amt = $('.sale_amt_with_tax').html();

		var tax_group = curRow.find('.sale_tax_group').val();

		var retail_max_mc = (isNaN(curRow.find('.bill_mc').val()) || curRow.find('.bill_mc').val() == '')  ? 0 : curRow.find('.bill_mc').val();

		var tot_wastage = (isNaN(curRow.find('.bill_wastage').val()) || curRow.find('.bill_wastage').val() == '')  ? 0 : curRow.find('.bill_wastage').val();

		if(calculation_type == 0){ 

			var wast_wgt      = parseFloat(parseFloat(gross_wt) * parseFloat(tot_wastage/100)).toFixed(3);

			if(curRow.find('.bill_mctype').val() != 3){

    			var mc_type       =  parseFloat(curRow.find('.bill_mctype').val() == 2 ? parseFloat(retail_max_mc * gross_wt ) : parseFloat(retail_max_mc * curRow.find('.sale_pcs').val()));

    			// Metal Rate + Stone + OM + Wastage + MC

    			rate_with_mc = parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(material_price)+parseFloat(certification_price));

			}else{

			    var mc_type       =  parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt)) * (retail_max_mc/100));

    			// Metal Rate + Stone + OM + Wastage + MC

    			rate_with_mc = parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(material_price)+parseFloat(certification_price));

			

			}

		}

		else if(calculation_type == 1){

			var wast_wgt      = parseFloat(parseFloat(net_wt) * parseFloat(tot_wastage/100)).toFixed(3);

			if(curRow.find('.bill_mctype').val() != 3){

    			var mc_type       =  parseFloat(curRow.find('.bill_mctype').val() == 2 ? parseFloat(retail_max_mc * net_wt ) : parseFloat(retail_max_mc * curRow.find('.sale_pcs').val()));

    			// Metal Rate + Stone + OM + Wastage + MC

    			rate_with_mc = parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(material_price)+parseFloat(certification_price));

			}else{

			    var mc_type       =  parseFloat(parseFloat(retail_max_mc * net_wt ) * curRow.find('.sale_pcs').val());

    			// Metal Rate + Stone + OM + Wastage + MC

    			rate_with_mc = parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(material_price)+parseFloat(certification_price));

			}

		}

		else if(calculation_type == 2){ 

			//var wast_wgt      = parseFloat(parseFloat(net_wt) * parseFloat(tot_wastage/100)).toFixed(3);

			var wast_wgt      = parseFloat(parseFloat(net_wt) * parseFloat(tot_wastage/100)).toFixed(3);

			if(curRow.find('.bill_mctype').val() != 3){

    			var mc_type       =  parseFloat(curRow.find('.bill_mctype').val() == 2 ? parseFloat(retail_max_mc * gross_wt ) : parseFloat(retail_max_mc * curRow.find('.sale_pcs').val()));

    			// Metal Rate + Stone + OM + Wastage + MC

    		    rate_with_mc = parseFloat(((parseFloat(rate_per_grm) * (parseFloat(wast_wgt) + parseFloat(net_wt)))+ parseFloat(mc_type))+parseFloat(stone_price)+parseFloat(material_price)).toFixed(2);

			}else{

			    var mc_type       =  parseFloat(parseFloat(retail_max_mc * gross_wt )  * curRow.find('.sale_pcs').val());

    			// Metal Rate + Stone + OM + Wastage + MC

    		    rate_with_mc = parseFloat(((parseFloat(rate_per_grm) * (parseFloat(wast_wgt) + parseFloat(net_wt)))+ parseFloat(mc_type))+parseFloat(stone_price)+parseFloat(material_price)).toFixed(2);

			}

		}

		

        else if(calculation_type == 3 || calculation_type == 4){ 

        

            rate_with_mc  = parseFloat((isNaN(curRow.find('.tag_sales_value').val()) || curRow.find('.tag_sales_value').val() == '')  ? 0 : curRow.find('.tag_sales_value').val()); 

        

        } 


		let charge_value = isNaN(parseFloat(curRow.find('.charge_value').val())) ? 0 : curRow.find('.charge_value').val();

		rate_with_mc = parseFloat(rate_with_mc) + parseFloat(charge_value);


        if(disc_amt>0)

        {

        	

	        	var disc_per=parseFloat((disc_amt/parseFloat(total_sales_amt+adv_paid_amt))*100);

	        	var  discount=parseFloat((rate_with_mc*disc_per)/100);

	       

	       

	        	rate_with_mc=parseFloat(rate_with_mc-discount).toFixed(2);

	        if(disc_type==2)

	        {

	        	var wastage_amt=parseFloat(wast_wgt*rate_per_grm);

	        	var mc_wast_amt=parseFloat(wastage_amt+mc_type);

	        	var disc_per=parseFloat((discount/mc_wast_amt)*100);

	        	var wast_disc =parseFloat((wastage_amt*disc_per)*100);

	        	var mc_disc =parseFloat((mc_type*disc_per)*100);

	        }

        }

        

        console.log('disc_per:'+disc_per);

        console.log('wast_disc:'+wast_disc);

        console.log('mc_disc:'+mc_disc);

        console.log('discount:'+discount);

       

		if(tax_type == 1){ // GST Inclusive

			curRow.find('.bill_amount').val(parseFloat(rate_with_mc).toFixed(2));

			var total_tax_rate = 0;

			var total_tax_rate = parseFloat(calculate_inclusiveGST(rate_with_mc,tax_group)).toFixed(2);

			if(cus_state==cmp_state)

			{

				cgst=parseFloat(parseFloat(total_tax_rate)/2).toFixed(3);

				sgst=parseFloat(parseFloat(total_tax_rate)/2).toFixed(3);

			}else{

				igst=total_tax_rate;

			} 

			console.log(total_tax_rate);

			curRow.find('.tax_amt').html(parseFloat(total_tax_rate).toFixed(2));

			curRow.find('.total_tax').val(parseFloat(total_tax_rate).toFixed(2));

			curRow.find('.bill_amount').val(parseFloat(rate_with_mc).toFixed(2));

			curRow.find('.sale_cgst').val(cgst);

			curRow.find('.sale_sgst').val(sgst);

			curRow.find('.sale_igst').val(igst);

		}else{

			var total_tax_rate = 0;

			var base_value_tax=parseFloat(calculate_base_value_tax(rate_with_mc,tax_group)).toFixed(2);

			var base_value_amt=parseFloat(parseFloat(rate_with_mc)+parseFloat(base_value_tax)).toFixed(2);

			var arrived_value_tax=parseFloat(calculate_arrived_value_tax(base_value_amt,tax_group)).toFixed(2);

			var arrived_value_amt=parseFloat(parseFloat(base_value_amt)+parseFloat(arrived_value_tax)).toFixed(2);

			

			var total_tax_rate=parseFloat(parseFloat(base_value_tax)+parseFloat(arrived_value_tax)).toFixed(2);

			//total_tax = getTaxValueForItem(rate_with_mc, tax_group);

			inclusive_tax_rate = arrived_value_amt;

			if(cus_state==cmp_state)

			{

				cgst=parseFloat(parseFloat(total_tax_rate)/2).toFixed(3);

				sgst=parseFloat(parseFloat(total_tax_rate)/2).toFixed(3);

			}else{

				igst=total_tax_rate;

			}	

			curRow.find('.tax_amt').html(parseFloat(total_tax_rate).toFixed(2));

			curRow.find('.total_tax').val(parseFloat(total_tax_rate).toFixed(2));

			curRow.find('.bill_amount').val(inclusive_tax_rate);

			curRow.find('.sale_cgst').val(cgst);

			curRow.find('.sale_sgst').val(sgst);

			curRow.find('.sale_igst').val(igst);

		}

		curRow.find('.tax_type').val(tax_type);

		curRow.find('.bill_taxable_amt').html(parseFloat(rate_with_mc).toFixed(2));

		curRow.find('.bill_discount').val(parseFloat(discount).toFixed(2));

		curRow.find('.per_grm_amount').val(rate_per_grm);

		curRow.find('.bill_wastage_wt').val(wast_wgt);

		curRow.find('.est_wastage_wt').html(wast_wgt);

		console.log('calculation_type:'+calculation_type);

		console.log('rate_with_mc:'+rate_with_mc);

		console.log('wast_wgt:'+wast_wgt);

		console.log('mc:'+curRow.find('.bill_mctype').val());

		

		console.log('mc_type:'+mc_type);

		console.log('retail_max_mc:'+retail_max_mc);

		console.log('inclusive_tax_rate:'+inclusive_tax_rate);

		console.log('total_tax:'+total_tax);

		console.log('tax_group:'+tax_group);

		console.log('bill_stone_price:'+stone_price);

		console.log('material_price:'+material_price);

		console.log('certification_price:'+certification_price);

		

		console.log('rate_per_grm:'+rate_per_grm);

		console.log('------------');

	});



	calculate_sales_details();  //Calculate Sales Details

	calculate_purchase_details();	//Calculate Purchase Details

}



$('#apply_disc').on('click',function(e){

    e.preventDefault();

   var discount=($('#summary_discount_amt').val()!='' ? $('#summary_discount_amt').val() :0);

    // Reset bill_mc with actual value before calculation

    $('#billing_sale_details > tbody  > tr').each(function(idx, tr) { 

        var curRow = $(this);

        var bill_mc_value=curRow.find('.bill_mc_value').val();

        curRow.find('.discount').val(0);

        curRow.find('.bill_discount').val(0);

         var bill_wastage_per=curRow.find('.bill_wastage_per').val();

        var bill_wastage_wt=curRow.find('.bill_wastage_wt').val();

        curRow.find('.est_wastage_wt').html(bill_wastage_wt);

        curRow.find('.bill_wastage').val(bill_wastage_per);

        curRow.find('.est_wastage').html(bill_wastage_per);

        curRow.find('.bill_mc').val(bill_mc_value);

        var mc_type=curRow.find('.bill_mctype').val();

        curRow.find('.mc_dis').val(0);

        curRow.find('.making_charge').html(mc_type==1 ? bill_mc_value+''+ 'Per Gram':bill_mc_value+''+ 'Per Piece');	

    }) 

    $('#billing_sale_details > tbody  > tr').each(function(index, tr) {

        var curRow = $(this);

        var gross_wt = (isNaN(curRow.find('.bill_gross_val').val()) || curRow.find('.bill_gross_val').val() == '')  ? 0 : curRow.find('.bill_gross_val').val();

		var less_wt  = (isNaN(curRow.find('.bill_less_val').val()) || curRow.find('.bill_less_val').val() == '')  ? 0 : curRow.find('.bill_less_val').val();

		var net_wt = parseFloat(gross_wt) - parseFloat(less_wt);

        var retail_mc=curRow.find('.bill_mc').val();

        var bill_mc_value=parseFloat(curRow.find('.bill_mc_value').val()).toFixed(2);

        var mc_type=curRow.find('.bill_mctype').val();

        var mc_dis=curRow.find('.mc_dis').val();

        var sale_est_itm_id=curRow.find('.sale_est_itm_id').val();

        var calculation_type = (isNaN(curRow.find('.sale_cal_type').val()) || curRow.find('.sale_cal_type').val() == '')  ? 0 : curRow.find('.sale_cal_type').val();

        var discount_used=0;

        discount_used=checkDiscount(); 

        

        if(discount>0 && (discount_used<discount))

        {       

        	if(curRow.find('.bill_mc').val() > 0){ // Having Balance in MC Value

				retail_mc =parseFloat(discount_used>0 ? (parseFloat(discount)-parseFloat(discount_used)):discount);

					if(calculation_type == 0)

    				{

    				    var retail_max_mc       =  parseFloat(curRow.find('.bill_mctype').val() == 1 ? ((parseFloat(bill_mc_value * gross_wt)-parseFloat(retail_mc))/gross_wt) : parseFloat(bill_mc_value-retail_mc)).toFixed(2);

    				}else if(calculation_type == 1)

    				{

    				    var retail_max_mc       =  parseFloat(curRow.find('.bill_mctype').val() == 1 ? ((parseFloat(bill_mc_value * net_wt)-parseFloat(retail_mc))/net_wt) : parseFloat(bill_mc_value-retail_mc)).toFixed(2);

    				}else if(calculation_type == 2)

    				{

    				    var retail_max_mc       =  parseFloat(curRow.find('.bill_mctype').val() == 1 ? ((parseFloat(bill_mc_value * gross_wt)-parseFloat(retail_mc))/gross_wt) : parseFloat(bill_mc_value-retail_mc)).toFixed(2);

    				}

				if(retail_mc < bill_mc_value)

				{   // Discount value less than Available MC

                    

					curRow.find('.bill_mc').val(retail_max_mc);

	                curRow.find('.making_charge').html(mc_type==1 ? retail_max_mc+''+ 'Per Gram':retail_max_mc+''+ 'Per Piece');

	                curRow.find('.mc_dis').val(1);

	                curRow.find('.discount').val(parseFloat(retail_mc));

	                curRow.find('.bill_discount').val(parseFloat(retail_mc));

	                return false;

				}

				else{ // Discount value greater than Available MC 

					discount_used = parseFloat(discount_used)+parseFloat(curRow.find('.bill_mc').val()); // Update Discount Used

					curRow.find('.bill_mc').val(0);

					var new_mc = curRow.find('.bill_mc').val();

	                curRow.find('.making_charge').html(mc_type==1 ? new_mc+''+ 'Per Gram':new_mc+''+ 'Per Piece');

	                curRow.find('.discount').val(bill_mc_value);

	                curRow.find('.bill_discount').val(bill_mc_value);

	                curRow.find('.mc_dis').val(1);

	                //return false;

				}

			}else{

				 curRow.find('.making_charge').html(mc_type==1 ? 0+''+ 'Per Gram':0+''+ 'Per Piece');

				 curRow.find('.bal_mc').val(0);

				 console.log("No Blc MC "+sale_est_itm_id+" :");

				 //return false;

			}  

        }

        else

        {

            curRow.find('.bill_mc').val(bill_mc_value);

            curRow.find('.mc_dis').val(0);    

            curRow.find('.bal_mc').val(0);

            curRow.find('.making_charge').html(mc_type==1 ? bill_mc_value+''+ 'Per Gram':bill_mc_value+''+ 'Per Piece');

        }

    });

    var discount_used=checkDiscount();

    var discount_wastage=parseFloat(discount)-parseFloat(discount_used);

    console.log(discount_used);

    console.log(discount_wastage);

    if(discount_wastage>0)

    {

     $('#billing_sale_details > tbody  > tr').each(function(idx, tr) { 

         if(discount_used>0)

         {

                var curRow = $(this); 

                var mc_dis=curRow.find('.mc_dis').val();

                var discount=curRow.find('.discount').val();

                var gross_wt = (isNaN(curRow.find('.bill_gross_val').val()) || curRow.find('.bill_gross_val').val() == '')  ? 0 : curRow.find('.bill_gross_val').val();

                var less_wt  = (isNaN(curRow.find('.bill_less_val').val()) || curRow.find('.bill_less_val').val() == '')  ? 0 : curRow.find('.bill_less_val').val();

                

                var net_wt  = (isNaN(curRow.find('.bill_net_val').val()) || curRow.find('.bill_net_val').val() == '')  ? 0 : curRow.find('.bill_net_val').val();

                var retail_mc=curRow.find('.bill_mc').val();

                var calculation_type = (isNaN(curRow.find('.sale_cal_type').val()) || curRow.find('.sale_cal_type').val() == '')  ? 0 : curRow.find('.sale_cal_type').val();

                var tot_wastage = (isNaN(curRow.find('.bill_wastage').val()) || curRow.find('.bill_wastage').val() == '')  ? 0 : curRow.find('.bill_wastage').val();

                var wastage_wt = (isNaN(curRow.find('.est_wastage_wt').html()) || curRow.find('.est_wastage_wt').html() == '')  ? 0 : curRow.find('.est_wastage_wt').html();

                if(curRow.find('td:eq(0) .sale_metal_type').val() == 1){

                var rate_per_grm = (isNaN($('.per-grm-sale-value').html()) || $('.per-grm-sale-value').html() == '')  ? 0 : parseFloat($('.per-grm-sale-value').html());

                }else{

                var rate_per_grm = (isNaN($('.silver_per-grm-sale-value').html()) || $('.silver_per-grm-sale-value').html() == '')  ? 0 : parseFloat($('.silver_per-grm-sale-value').html());

                }

                var wastage_amount  = parseFloat(parseFloat(wastage_wt*rate_per_grm)-(discount_wastage));

                var wast_wgt        = parseFloat(wastage_amount/rate_per_grm);

                

                if(calculation_type == 0)

                {

                    var wast_per  = parseFloat((wast_wgt/gross_wt)*100);

                }

                else if(calculation_type == 1)

                {

                    var wast_per        = parseFloat((wast_wgt/net_wt)*100);

                }

                else if(calculation_type == 2){

                    var wast_per        = parseFloat((wast_wgt/net_wt)*100);

                }

                

                discount_used       = discount_wastage-wast_per;

                console.log('discount_used'+discount_used);

                curRow.find('.bill_wastage').val(wast_per);

                curRow.find('.est_wastage').html(wast_per);

                curRow.find('.est_wastage_wt').html(wast_wgt);

                curRow.find('.discount').val(parseFloat(discount)+parseFloat(discount_wastage));

                curRow.find('.bill_discount').val(parseFloat(discount)+parseFloat(discount_wastage));

                console.log('Discount Wastage'+discount_wastage);

                console.log('wastage_amount'+wastage_amount);

                console.log('wast_wgt'+wast_wgt);

                console.log('wast_per'+wast_per);   

         }

     });

    }

    calculateSaleBillRowTotal();

});

function checkDiscount()

{

    var discount=0;

    var bill_mc=0;

    var bal_mc=0;

    var total_mc_value=0;

    $('#billing_sale_details > tbody  > tr').each(function(index, tr) {

        var curRow = $(this);

        bill_mc+=parseFloat(parseFloat(curRow.find('.discount').val()));

        total_mc_value+=parseFloat(curRow.find('.bill_mc_value').val());

    });

    discount=parseFloat(total_mc_value)-parseFloat(bill_mc);

    return bill_mc;

}

function calculatePurchaseBillRowTotal(){

	$('#purchase_item_details > tbody tr').each(function(idx, row){

		curRow = $(this);

		curRow = $(this);

		var gross_wt = (isNaN(curRow.find('.pur_gross_val').val()) || curRow.find('.pur_gross_val').val() == '')  ? 0 : curRow.find('.pur_gross_val').val();

		var less_wt  = (isNaN(curRow.find('.pur_less_val').val()) || curRow.find('.pur_less_val').val() == '')  ? 0 : curRow.find('.pur_less_val').val();

		var dust_wt  = (isNaN(curRow.find('.est_old_dust_val').val()) || curRow.find('.est_old_dust_val').val() == '')  ? 0 : curRow.find('.est_old_dust_val').val();

		var stone_wt  = (isNaN(curRow.find('.est_old_stone_val').val()) || curRow.find('.est_old_stone_val').val() == '')  ? 0 : curRow.find('.est_old_stone_val').val();

		var other_stone_wt  = (isNaN(curRow.find('.other_stone_wt').val()) || curRow.find('.other_stone_wt').val() == '')  ? 0 : curRow.find('.other_stone_wt').val();

		var other_stone_price  = (isNaN(curRow.find('.other_stone_price').val()) || curRow.find('.other_stone_price').val() == '')  ? 0 : curRow.find('.other_stone_price').val();

		var rate_per_grm  = (isNaN(curRow.find('.bill_rate_per_grm').val()) || curRow.find('.bill_rate_per_grm').val() == '')  ? 0 : curRow.find('.bill_rate_per_grm').val();

		var net_wt = (parseFloat(gross_wt) - (parseFloat(dust_wt)+parseFloat(stone_wt)+parseFloat(other_stone_wt))).toFixed(3);

		var total_price = 0;

		//var rate_per_grm = 0;

	/*	if(curRow.find('.pur_metal_type').val() == 1){

		  rate_per_grm = (isNaN($('.per-grm-sale-value').html()) || $('.per-grm-sale-value').html() == '')  ? 0 : parseFloat($('.per-grm-sale-value').html());

		}else{

			 rate_per_grm = (isNaN($('.silver_per-grm-sale-value').html()) || $('.silver_per-grm-sale-value').html() == '')  ? 0 : parseFloat($('.silver_per-grm-sale-value').html());

		}	*/	

		var discount = (isNaN(curRow.find('.pur_discount').val()) || curRow.find('.pur_discount').val() == '')  ? 0 : curRow.find('.pur_discount').val();

		var tot_wastage = (isNaN(curRow.find('.pur_wastage').val()) || curRow.find('.pur_wastage').val() == '')  ? 0 : curRow.find('.pur_wastage').val();

		cal_weight = parseFloat((net_wt * (tot_wastage / 100))).toFixed(3);

		total_price = parseFloat(Math.round(parseFloat(rate_per_grm) * (parseFloat(net_wt) - parseFloat(cal_weight)) - discount));

		total_price=parseFloat(parseFloat(total_price)+parseFloat(other_stone_price)).toFixed(2);

		curRow.find('.bill_amount').val(total_price);

		

		curRow.find('.wastage_wt').html(cal_weight);

		curRow.find('.pur_wastage_wt').val(cal_weight);

		curRow.find('.bill_rate_per_grm').val(rate_per_grm);

		console.log('Purchase Bill Items');

		console.log('total_price:'+total_price);

		console.log('rate_per_grm:'+rate_per_grm);

		console.log('discount:'+discount);

		console.log('tot_wastage:'+tot_wastage);

		console.log('cal_weight:'+cal_weight);

		console.log('net_wt:'+net_wt);

		console.log('other_stone_wt:'+other_stone_wt);

		console.log('--------------');

	});

	calculate_order_adv_purchase_details();

	//calculate_sales_details();

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

function getTaxValueForItem(taxcallrate, taxgroup){

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

function add_cutomer(cus_name, cus_mobile,id_village,cus_type,gst_no){ //, cus_address

	my_Date = new Date();

	$.ajax({

        url: base_url+'index.php/admin_ret_billing/createNewCustomer/?nocache=' + my_Date.getUTCSeconds(),             

        dataType: "json", 

        method: "POST", 

        data: {'cusName': cus_name, 'cusMobile' : cus_mobile, 'cusBranch' : $('#id_branch').val(),'id_village':id_village,'cus_type':cus_type,'gst_no':gst_no}, //Need to update login branch id here from session

        success: function (data) { 

			if(data.success == true){

				$('#confirm-add').modal('toggle');

				$("#bill_cus_name").val(data.response.firstname + " - " + data.response.mobile);

				$("#bill_cus_id").val(data.response.id_customer);

			}else{

				alert(data.message);

			}

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

			$( "#bill_cus_name" ).autocomplete(

			{

				source: data,

				select: function(e, i)

				{ 

					e.preventDefault();

					$("#bill_cus_name").val(i.item.label);

					$("#bill_cus_id").val(i.item.value);

					$("#cus_village").html(i.item.village_name); 					

					$("#cus_info").append(i.item.vip == 'Yes' ? "<span class='label bg-orange'><i class='fa fa-fw fa-star'></i> V I P</span>":"");

					$("#cus_info").append(i.item.accounts > 0 ? "&nbsp;<span class='label label-info'>Chit Customer</span>":"");					

					$("#cus_state").val(i.item.cus_state);

					$("#cmp_state").val(i.item.cmp_state);

					

					//TCS Calculation

					var billing_for = $("input[name='billing[billing_for]']:checked").val();

					if(billing_for==2)

					{

					    getCompanyPurchaseAmount(i.item.value);

					}

				},

				change: function (event, ui) {

					if (ui.item === null) {

						$(this).val('');

						$('#bill_cus_name').val('');

						$("#bill_cus_id").val("");

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

function getSearchTags(tagId){

	my_Date = new Date();

	$.ajax({

        url: base_url+'index.php/admin_ret_billing/getTaggingBySearch/?nocache=' + my_Date.getUTCSeconds(),             

        dataType: "json", 

        method: "POST", 

        data: {'tagId': tagId,'id_branch':$('#id_branch').val()}, 

        success: function (data) {

        	$("#searchTagAlert span").remove();

			if(data.length == 0){

				$("#searchTagAlert").append("<span>No record found for given Tag no</span>");

				$('#searchTagAlert span').delay(10000).fadeOut(500); 

			}

			else if(data[0].tag_status != 0){

				if(data[0].tag_status == 1){

					$("#searchTagAlert").append("<span>Tag Already sold.</span>");

					$('#searchTagAlert span').delay(10000).fadeOut(500); 

				}else{

					$("#searchTagAlert").append("<span>Tag was deleted.</span>");

					$('#searchTagAlert span').delay(10000).fadeOut(500); 

				}

			}else{

				var rowExist = false;

				//$('#bill_type_sales').prop('checked', true);

				$(".sale_details").show();

				$('#billing_sale_details > tbody tr').each(function(bidx, brow){ 

					bill_sale_row = $(this);    

						if( data[0].tag_id == bill_sale_row.find('.sale_tag_id').val()){

							rowExist = true;

							$("#searchTagAlert").append("<span>TagNo already added in this bill.</span>");

							$('#searchTagAlert span').delay(10000).fadeOut(500);

						} 

				});

				if(!rowExist){

					var stone_details=[];

					

					var stone_price=0;

					var certification_cost=0;

					var tot_length=$('#billing_sale_details tbody tr').length;

					var rate_per_grm = (isNaN($('.per-grm-sale-value').html()) || $('.per-grm-sale-value').html() == '')  ? 0 : parseFloat($('.per-grm-sale-value').html());

					/*if(data[0].advance_details.length>0)

					{

							var paid_advance=0;



							var paid_weight=0;

				

						$.each(data[0].advance_details,function(key,item){

							

								if(item.store_as==1)

								{

									paid_advance +=parseFloat(item.paid_advance);

								}

								else

								{

								    paid_weight +=parseFloat(item.paid_weight);

									

									paid_advance +=parseFloat(item.paid_weight*rate_per_grm);

								}

							

						});



						$('.summary_adv_paid_wt').html(paid_weight);



						$('.summary_adv_paid_amt').html(parseFloat(paid_advance).toFixed(2));

					

					}*/



					$.each(data[0].stone_details,function(key,item){

					    

					   stone_price+=parseFloat(item.amount);

						certification_cost+=parseFloat(item.certification_cost);

						stone_details.push({'stone_id' : item.stone_id,'stone_pcs' :item.pieces,'stone_wt':item.wt,'stone_price':item.amount,'certification_cost':item.certification_cost});

					});

					var row = '<tr id="'+tot_length+'">'

								+'<td><span>'+data[0].hsn_code+'</span><input type="hidden" class="sale_pro_hsn" name="sale[hsn]" value="'+data[0].hsn_code+'" /><input type="hidden" class="sale_type" name="sale[sourcetype][]" value="1" /><input type="hidden" class="sale_item_type" name="sale[itemtype][]" value="0" /><input type="hidden" class="is_est_details" name="sale[is_est_details][]" value="2" /><input type="hidden" class="est_itm_id" value="'+data[0].tag_id+'" /><input type="hidden" class="sale_cal_type" value="'+data[0].calculation_based_on+'" name="sale[calltype][]" /><input type="hidden" class="sale_metal_type" value="'+data[0].metal_type+'" /><input type="hidden" class="sale_purity" value="'+data[0].purname+'"  name="sale[purity][]" /><input type="hidden" class="sale_size" value="'+data[0].size+'"  name="sale[size][]" /><input type="hidden" class="sale_uom" value="'+data[0].uom+'"  name="sale[uom][]" /><input type="hidden" class="total_tax" name="sale[total_tax][]"><input type="hidden" class="stock_type" value=""  /><input type="hidden" class="is_non_tag" name="sale[is_non_tag][]" value="" /></td>'

								+'<td><span>'+data[0].product_short_code+'</span><input class="sale_product_id" type="hidden" name="sale[product][]" value="'+data[0].lot_product+'" /><input type="hidden" class="disc_type" value="'+data[0].disc_type+'"></td>'

								+'<td><span>'+data[0].design_code+'</span><input type="hidden" class="sale_design_id" name="sale[design][]" value="'+data[0].design_id+'" /></td>'

								+'<td><span>'+data[0].piece+'</span><input type="hidden" class="sale_pcs" name="sale[pcs][]" value="'+data[0].piece+'" /></td>'

								+'<td><span>'+data[0].gross_wt+'</span><input type="hidden" class="bill_gross_val" name="sale[gross][]" value="'+data[0].gross_wt+'" /></td>'

								+'<td><span>'+data[0].less_wt+'</span><input type="hidden" class="bill_less_val" name="sale[less][]" value="'+data[0].less_wt+'" /></td>'

								+'<td><span>'+data[0].net_wt+'</span><input type="hidden" class="bill_net_val" name="sale[net][]" value="'+data[0].net_wt+'" /></td>'

								+'<td><span class="est_wastage">'+data[0].retail_max_wastage_percent+'</span><input type="hidden" class="bill_wastage" name="sale[wastage][]" value="'+data[0].retail_max_wastage_percent+'" /><input type="hidden" class="bill_wastage_per" name="sale[wastage][]" value="'+data[0].retail_max_wastage_percent+'" /></td>'

								+'<td><span class="est_wastage_wt"></span><input type="hidden" class="bill_wastage_wt" value=""></td>'

								+'<td><span class="making_charge">'+data[0].tag_mc_value+' '+(data[0].tag_mc_type == 1 ? ' per gm' : ' per pc')+'</span><input type="hidden" class="bill_mc" name="sale[mc][]" value="'+data[0].tag_mc_value+'" /><input type="hidden" class="bill_mctype" value="'+data[0].tag_mc_type+'" name="sale[bill_mctype][]"/><input type="hidden" class="discount" name="sale[adjusted_dis][]" value="" /><input type="hidden" class="bill_mc_value" value="'+data[0].tag_mc_value+'" /></td>'

								+'<td><input type="number" class="bill_discount" name="sale[discount][]" value="0" step="any" /></td>'

								+'<td><span class="bill_taxable_amt"></span></td>'

								+'<td><span>'+data[0].tgrp_name+'</span><input type="hidden" class="sale_tax_group" name="sale[taxgroup][]" value="'+data[0].tgrp_id+'" /><input type="hidden" name="sale[total_cgst][]" class="sale_cgst"/><input type="hidden" class="sale_sgst" name="sale[total_sgst][]"/><input type="hidden" class="sale_igst" name="sale[total_igst][]"/></td>'

								+'<td></td>'

								+'<td><a href="#" onClick="create_new_empty_bill_sales_stone_item($(this).closest(\'tr\'));" class="btn btn-success"><i class="fa fa-plus"></i></a><input type="hidden" value="'+stone_price+'" class="bill_stone_price" /><input type="hidden" value="'+certification_cost+'" class="certification_cost" /><input type="hidden" value="" class="bill_material_price" /><input type="hidden" value='+(JSON.stringify(stone_details))+' name="sale[stone_details][]" class="stone_details" /><input type="hidden" class="total_tax" name="sale[item_total_tax][]"></td>'

								+'<td><input type="number" class="bill_amount" name="sale[billamount][]" value="" step="any" readonly /><input type="hidden" class="per_grm_amount" name="sale[per_grm][]" value="" step="any" /><input type="hidden" class="tag_sales_value" value="'+data[0].sales_value+'" step="any" /></td>'

								+'<td>No</td>'

								+'<td><span>'+data[0].tag_id+'</span><input type="hidden" class="sale_tag_id" name="sale[tag][]" value="'+data[0].tag_id+'" /></td>'

								+'<td>'+data[0].order_no+'<input type="hidden" class="sale_order_no" name="sale[order_no][]" value="'+data[0].order_no+'" /></td>'

								+'<td>-</td>'

								+'<td><a href="#" onClick="remove_row($(this).closest(\'tr\'));" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'

								+'</tr>';

					$('#billing_sale_details tbody').append(row);

					calculateSaleBillRowTotal();

				}

			}

        }

     });

}

function getSearchProducts(searchTxt, curRow){

	my_Date = new Date();

	$.ajax({

        url: base_url+'index.php/admin_ret_estimation/getProductBySearch/?nocache=' + my_Date.getUTCSeconds(),             

        dataType: "json", 

        method: "POST", 

        data: {'searchTxt': searchTxt}, 

        success: function (data) {

			$( ".cat_product" ).autocomplete(

			{

				source: data,

				select: function(e, i)

				{ 

					e.preventDefault();

					curRow.find('td:eq(0) .cat_product').val(i.item.label);

					curRow.find('td:eq(0) .cat_pro_id').val(i.item.value);

					curRow.find('td:eq(5) .cat_pcs').val(i.item.no_of_pieces);

					curRow.find('td:eq(11) .cat_calculation_based_on').val(i.item.calculation_based_on);

					var curRowItem = i.item;

					$('#purchase_purchase_details > tbody').find('tr:last td:eq(1) .cat_design').focus();

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

						curRow.find('td:eq(0) .cat_product').val("");

						curRow.find('td:eq(0) .cat_pro_id').val("");

					}

		        },

				 minLength: 1,

			});

        }

     });

}

function getSearchCustomProducts(searchTxt, curRow){

	my_Date = new Date();

	$.ajax({

        url: base_url+'index.php/admin_ret_estimation/getProductBySearch/?nocache=' + my_Date.getUTCSeconds(),             

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

					curRow.find('td:eq(0) .cus_product').val(i.item.label);

					curRow.find('td:eq(0) .cus_product_id').val(i.item.value);

					curRow.find('td:eq(4) .cus_pcs').val(i.item.no_of_pieces);

					curRow.find('td:eq(10) .cus_calculation_based_on').val(i.item.calculation_based_on);

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

					}

		        },

				 minLength: 1,

			});

        }

     });

}

function getSearchDesign(searchTxt, curRow){

	my_Date = new Date();

	$.ajax({

        url: base_url+'index.php/admin_ret_estimation/getProductDesignBySearch/?nocache=' + my_Date.getUTCSeconds(),             

        dataType: "json", 

        method: "POST", 

        data: { 'searchTxt': searchTxt, 'ProCode' : curRow.find('td:eq(0) .cat_pro_id').val() }, 

        success: function (data) {

			$( ".cat_design" ).autocomplete(

			{

				source: data,

				select: function(e, i)

				{ 

					e.preventDefault();

					curRow.find('td:eq(1) .cat_design').val(i.item.label);

					curRow.find('td:eq(1) .cat_des_id').val(i.item.value);

					var curRowItem = i.item;

					$('#purchase_purchase_details > tbody').find('tr:last td:eq(2) .cat_qty').focus();

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

						curRow.find('td:eq(0) .cat_product').val("");

						curRow.find('td:eq(0) .cat_pro_id').val("");

					}

		        },

				 minLength: 1,

			});

        }

     });

}

function hide_page_open_details()

{

	$(".sale_details").hide();

	$(".purchase_details").hide();

	$(".order_adv_details").hide();

	$(".custom_details").hide();

	$(".old_matel_details").hide();

	$(".stone_details").hide();

	$(".material_details").hide(); 

	$(".search_bill").css("display","none");

	$(".search_order").css("display","none");

	$(".search_esti").css("display","none");

	$(".search_tag").css("display","none");

	$(".advance_amt").css("display","none");

	$(".return_details").css("display","none");

	$(".date_filter").css("display","none");

}

function create_new_empty_bill_sale_row()

{

	var row = "";

	row += '<tr>'

			+'<td><input class="est_tag_name" type="text" name="est_tag[tag_name][]" value="" placeholder="Enter tag no" required /><input class="est_tag_id" type="hidden" name="est_tag[tag_id][]" value="" placeholder="Enter tag no" required /></td>'

			+'<td><div class="prodct_name"></div><input type="hidden" class="pro_id" name="est_tag[pro_id][]" value="" /></td>'

			+'<td><div class="qty"></div><input type="hidden" name="est_tag[qty][]" value="" /></td>'

			+'<td><div class="purity"></div><input type="hidden" name="est_tag[purity][]" value="" /></td>'

			+'<td><div class="size"></div><input type="hidden" name="est_tag[size][]" value="" /></td>'

			+'<td><div class="piece"></div><input type="hidden" name="est_tag[piece][]" value="" /></td>'

			+'<td><div class="gwt"></div><input type="hidden" name="est_tag[gwt][]" value="" /></td>'

			+'<td><div class="lwt"></div><input type="hidden" name="est_tag[lwt][]" value="" /></td>'

			+'<td><div class="nwt"></div><input type="hidden" name="est_tag[nwt][]" value="" /></td>'

			+'<td><div class="wastage"></div><input type="hidden" name="est_tag[wastage][]" value="" /></td>'

			+'<td><div class="mc"></div><input type="hidden" name="est_tag[mc][]" value="" class="mc_id" /></td>'

			+'<td><div class="cost"></div><input class="sales_value" type="hidden" name="est_tag[cost][]" value="" /><input class="caltype" type="hidden" name="est_tag[caltype][]" value="" /></td>'

			+'<td><a href="#" onClick="remove_row($(this).closest(\'tr\'));" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'

			+'</tr>';

	$('#estimation_sale_details tbody').append(row);

	//$('#estimation_sale_details > tbody').find('tr:last td:eq(0) .est_tag_name').focus();

}	

function create_new_empty_est_catalog_row()

{

	var purity = "<option value=''>-Select Purity-</option>";

	$.each(purities, function (pkey, pitem) {

		purity += "<option value='"+pitem.id_purity+"'>"+pitem.purity+"</option>";

	});	

	var row = "";

	row += '<tr><td><input type="text" class="cat_product" name="est_catalog[product][]" value="" required /><input type="hidden" class="cat_pro_id" name="est_catalog[pro_id][]" value="" /></td><td><input type="text" class="cat_design" name="est_catalog[design][]" value="" required /><input type="hidden" class="cat_des_id" name="est_catalog[des_id][]" value="" /></td><td><input type="number" class="cat_qty" name="est_catalog[qty][]" value="" /></td><td><select class="cat_purity" name="est_catalog[purity][]">'+purity+'</select></td><td><input type="number" class="cat_size" name="est_catalog[size][]" value="" /></td><td><input type="number" class="cat_pcs" name="est_catalog[pcs][]" value="" readonly /></td><td><input type="number"  class="cat_gwt" name="est_catalog[gwt][]" value="" /></td><td><input type="number" class="cat_lwt" name="est_catalog[lwt][]" value="" /></td><td><input type="number" class="cat_nwt" name="est_catalog[nwt][]" value="" readonly /></td><td><input type="number" class="cat_wastage" name="est_catalog[wastage][]" value="" /></td><td><input type="number"  class="cat_mc" name="est_catalog[mc][]" value="" /></td><td><input type="number" class="cat_amt" name="est_catalog[amount][]" value="" readonly /><input type="hidden" class="cat_calculation_based_on" name="est_catalog[calculation_based_on][]" value="" /></td><td><a href="#" onClick="remove_row($(this).closest(\'tr\'));" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';

	$('#purchase_purchase_details tbody').append(row);

	$('#purchase_purchase_details > tbody').find('tr:last td:eq(0) .cat_product').focus();

}	

function create_new_empty_est_custom_row()

{

	var purity = "<option value=''>-Select Purity-</option>";

	$.each(purities, function (pkey, pitem) {

		purity += "<option value='"+pitem.id_purity+"'>"+pitem.purity+"</option>";

	});	

	var row = "";

	row += '<tr><td><input type="text" name="est_custom[product][]" value="" class="cus_product" required /><input class="cus_product_id" type="hidden" name="est_custom[pro_id][]" value="" /></td><td><input class="cus_qty" type="number" name="est_custom[qty][]" value="" /></td><td><select class="cus_purity" name="est_custom[purity][]">'+purity+'</select></td><td><input type="number" class="cus_size" name="est_custom[size][]" value="" /></td><td><input class="cus_pcs" type="number" name="est_custom[pcs][]" value="" /></td><td><input type="number" class="cus_gwt" name="est_custom[gwt][]" value="" /></td><td><input class="cus_lwt" type="number" name="est_custom[lwt][]" value="" /></td><td><input type="number" class="cus_nwt" name="est_custom[nwt][]" value="" readonly /></td><td><input class="cus_wastage" type="number" name="est_custom[wastage][]" value="" /></td><td><input type="number" class="cus_mc" name="est_custom[mc][]" value="" /></td><td><input class="cus_amount" type="number" name="est_custom[amount][]" value="" readonly /><input type="hidden" class="cus_calculation_based_on" name="est_custom[calculation_based_on][]" value="" /></td><td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';

	$('#estimation_custom_details tbody').append(row);

	$('#estimation_custom_details > tbody').find('tr:last td:eq(0) .cus_product').focus();

}	

function create_new_empty_est_oldmatel_row()

{

	var purity = "<option value=''>-Select Purity-</option>";

	$.each(purities, function (pkey, pitem) {

		purity += "<option value='"+pitem.id_purity+"'>"+pitem.purity+"</option>";

	});	

	var matelTupes = "<option value=''>- Select Matel-</option>";

	$.each(matel_types, function (mkey, mitem) {

		matelTupes += "<option value='"+mitem.id_metal+"'>"+mitem.metal+"</option>";

	});	

	var row = "";

	row += '<tr><td><select class="old_item_type" name="est_oldmatel[item_type][]"><option value=""> -Select Type- </option><option value="1"> Ornament </option><option value="2">Coin </option><option value="3"> Bar </option></select></td><td><select class="old_id_category"  name="est_oldmatel[id_category][]">'+matelTupes+'</select></td><td><select class="old_purity"  name="est_oldmatel[purity][]">'+purity+'</select></td><td><input type="number" class="old_gwt" name="est_oldmatel[gwt][]" value="" /></td><td><input class="old_lwt" type="number" name="est_oldmatel[lwt][]" value="" /></td><td><input type="number" class="old_nwt" name="est_oldmatel[nwt][]" value="" readonly /></td><td><input class="old_wastage" type="number" name="est_oldmatel[wastage][]" value="" /></td><td><input type="number" class="old_rate" name="est_oldmatel[rate][]" value="" /></td><td><input class="old_amount" type="number" name="est_oldmatel[amount][]" value="" /></td><td><select class="old_use_type" name="est_oldmatel[use_type][]"><option value="1"> Melting </option><option value="2"> Re-Tag</option></select></td><td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';

	$('#estimation_old_matel_details tbody').append(row);

	$('#estimation_old_matel_details > tbody').find('tr:last td:eq(0) .old_item_type').focus();

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

function validateSaleDetailRow(){

	var row_validate = true;

	$('#estimation_sale_details > tbody  > tr').each(function(index, tr) {

		if($(this).find('td:first .est_tag_name').val() == ""){

			row_validate = false;

		}

	});

	return row_validate;

}	

function validateCatalogDetailRow(){

	var row_validate = true;

	$('#purchase_purchase_details > tbody  > tr').each(function(index, tr) {

		if($(this).find('td:first .cat_product').val() == "" || $(this).find('td:eq(1) .cat_design').val() == "" || $(this).find('td:eq(2) .cat_qty').val() == "" || $(this).find('td:eq(5) .cat_size').val() == "" || $(this).find('td:eq(6) .cat_gwt').val() == "" || $(this).find('td:eq(3) .cat_purity').val() == ""){

			row_validate = false;

		}

	});

	return row_validate;

}

function validateCustomDetailRow(){

	var row_validate = true;

	$('#estimation_custom_details > tbody  > tr').each(function(index, tr) {

		if($(this).find('td:first .cus_product').val() == "" || $(this).find('td:eq(1) .cus_qty').val() == "" || $(this).find('td:eq(4) .cus_size').val() == "" || $(this).find('td:eq(5) .cus_gwt').val() == "" || $(this).find('td:eq(2) .cus_purity').val() == ""){

			row_validate = false;

		}

	});

	return row_validate;

}

function validateOldMatelDetailRow(){

	var row_validate = true;

	$('#estimation_old_matel_details > tbody  > tr').each(function(index, tr) {

		if($(this).find('td:first .old_item_type').val() == "" || $(this).find('td:eq(1) .old_id_category').val() == "" || $(this).find('td:eq(3) .old_gwt').val() == "" ){

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

function calculateSaleValue(curRow){

	//curRow.find('td:eq(1) .cat_design').val(i.item.label);

	//(isNaN(row.find('td:eq(6) .cat_gwt').val()) || row.find('td:eq(6) .cat_gwt').val() == '')  ? 0 : row.find('td:eq(6) .cat_gwt').val();

	var gross_wt = (isNaN(curRow.find('td:eq(6) .cat_gwt').val()) || curRow.find('td:eq(6) .cat_gwt').val() == '')  ? 0 : curRow.find('td:eq(6) .cat_gwt').val();

	var less_wt  = (isNaN(curRow.find('td:eq(7) .cat_lwt').val()) || curRow.find('td:eq(7) .cat_lwt').val() == '')  ? 0 : curRow.find('td:eq(7) .cat_lwt').val();

	var net_wt = parseFloat(gross_wt) - parseFloat(less_wt);

	var calculation_type = (isNaN(curRow.find('td:eq(11) .cat_calculation_based_on').val()) || curRow.find('td:eq(11) .cat_calculation_based_on').val() == '')  ? 0 : curRow.find('td:eq(11) .cat_calculation_based_on').val();

	var total_price = 0;

	var rate_per_grm = (isNaN($('.per-grm-sale-value').html()) || $('.per-grm-sale-value').html() == '')  ? 0 : parseFloat($('.per-grm-sale-value').html());

	var tax_rate = 0;

	/* if(tax_details.length > 0){

		tax_rate = tax_details[0].tax_percentage;

	} */

	var retail_max_mc = (isNaN(curRow.find('td:eq(10) .cat_mc').val()) || curRow.find('td:eq(10) .cat_mc').val() == '')  ? 0 : curRow.find('td:eq(10) .cat_mc').val();

	var tot_wastage = (isNaN(curRow.find('td:eq(9) .cat_wastage').val()) || curRow.find('td:eq(9) .cat_wastage').val() == '')  ? 0 : curRow.find('td:eq(9) .cat_wastage').val();

	if(calculation_type == 0){

		rate_with_mc = parseFloat(parseFloat(rate_per_grm * gross_wt) + parseFloat(retail_max_mc * gross_wt));

		total_price = parseFloat(rate_with_mc + (rate_with_mc*parseFloat(( tax_rate / 100)))).toFixed(2);

	}else if(calculation_type == 1){

		rate_with_mc = parseFloat(parseFloat(rate_per_grm * net_wt) + parseFloat(retail_max_mc * net_wt));

		rate_tax = parseFloat(rate_with_mc * parseFloat(( tax_rate / 100)));

		total_price = parseFloat(rate_with_mc + rate_tax).toFixed(2);

	}else if(calculation_type == 2){

		rate_with_mc = parseFloat(((parseFloat(rate_per_grm) * parseFloat(net_wt) + parseFloat(tot_wastage * net_wt))) + parseFloat(retail_max_mc * net_wt));

		total_price = parseFloat(rate_with_mc + (rate_with_mc*parseFloat(( tax_rate / 100)))).toFixed(2);

	}

	//$(".tag-sale-value").html(total_price);

	curRow.find('td:eq(11) .cat_amt').val(total_price);

	calculate_purchase_details();

	calculate_sales_details();

}

function calculateCustomItemSaleValue(curRow){

	//curRow.find('td:eq(1) .cat_design').val(i.item.label);

	//(isNaN(row.find('td:eq(6) .cat_gwt').val()) || row.find('td:eq(6) .cat_gwt').val() == '')  ? 0 : row.find('td:eq(6) .cat_gwt').val();

	var gross_wt = (isNaN(curRow.find('.cus_gwt').val()) || curRow.find('.cus_gwt').val() == '')  ? 0 : curRow.find('.cus_gwt').val();

	var less_wt  = (isNaN(curRow.find('.cus_lwt').val()) || curRow.find('.cus_lwt').val() == '')  ? 0 : curRow.find('.cus_lwt').val();

	var net_wt = parseFloat(gross_wt) - parseFloat(less_wt);

	var calculation_type = (isNaN(curRow.find('.cus_calculation_based_on').val()) || curRow.find('.cus_calculation_based_on').val() == '')  ? 0 : curRow.find('.cus_calculation_based_on').val();

	var total_price = 0;

	var rate_per_grm = (isNaN($('.per-grm-sale-value').html()) || $('.per-grm-sale-value').html() == '')  ? 0 : parseFloat($('.per-grm-sale-value').html());

	var tax_rate = 0;

	/* if(tax_details.length > 0){

		tax_rate = tax_details[0].tax_percentage;

	} */

	var retail_max_mc = (isNaN(curRow.find('.cus_mc').val()) || curRow.find('.cus_mc').val() == '')  ? 0 : curRow.find('.cus_mc').val();

	var tot_wastage = (isNaN(curRow.find('.cus_wastage').val()) || curRow.find('.cus_wastage').val() == '')  ? 0 : curRow.find('.cus_wastage').val();

	if(calculation_type == 0){

		rate_with_mc = parseFloat(parseFloat(rate_per_grm * gross_wt) + parseFloat(retail_max_mc * gross_wt));

		total_price = parseFloat(rate_with_mc + (rate_with_mc*parseFloat(( tax_rate / 100)))).toFixed(2);

	}else if(calculation_type == 1){

		rate_with_mc = parseFloat(parseFloat(rate_per_grm * net_wt) + parseFloat(retail_max_mc * net_wt));

		rate_tax = parseFloat(rate_with_mc * parseFloat(( tax_rate / 100)));

		total_price = parseFloat(rate_with_mc + rate_tax).toFixed(2);

	}else if(calculation_type == 2){

		rate_with_mc = parseFloat(((parseFloat(rate_per_grm) * parseFloat(net_wt) + parseFloat(tot_wastage * net_wt))) + parseFloat(retail_max_mc * net_wt));

		total_price = parseFloat(rate_with_mc + (rate_with_mc*parseFloat(( tax_rate / 100)))).toFixed(2);

	}

	//$(".tag-sale-value").html(total_price);

	curRow.find('.cus_amount').val(total_price);

	calculate_purchase_details();

	calculate_sales_details();

}

function calculateOldMatelItemSaleValue(curRow){

	//curRow.find('td:eq(1) .cat_design').val(i.item.label);

	//(isNaN(row.find('td:eq(6) .cat_gwt').val()) || row.find('td:eq(6) .cat_gwt').val() == '')  ? 0 : row.find('td:eq(6) .cat_gwt').val();

	var gross_wt = (isNaN(curRow.find('td:eq(3) .old_gwt').val()) || curRow.find('td:eq(3) .old_gwt').val() == '')  ? 0 : curRow.find('td:eq(3) .old_gwt').val();

	var less_wt  = (isNaN(curRow.find('td:eq(4) .old_lwt').val()) || curRow.find('td:eq(4) .old_lwt').val() == '')  ? 0 : curRow.find('td:eq(4) .old_lwt').val();

	var net_wt = parseFloat(gross_wt) - parseFloat(less_wt);

	var total_price = 0;

	//var rate_per_grm = (isNaN($('.per-grm-sale-value').html()) || $('.per-grm-sale-value').html() == '')  ? 0 : parseFloat($('.per-grm-sale-value').html());

	var rate_per_grm = (isNaN(curRow.find('td:eq(7) .old_rate').val()) || curRow.find('td:eq(7) .old_rate').val() == '')  ? 0 : curRow.find('td:eq(7) .old_rate').val();

	var tax_rate = 0;

	/* if(tax_details.length > 0){

		tax_rate = tax_details[0].tax_percentage;

	} */

	var wastage = (isNaN(curRow.find('td:eq(6) .old_wastage').val()) || curRow.find('td:eq(6) .old_wastage').val() == '')  ? 0 : curRow.find('td:eq(6) .old_wastage').val();

	cal_weight = parseFloat(net_wt - (net_wt * (wastage / 100)));

	total_price = parseFloat(rate_per_grm) * parseFloat(cal_weight) ;

	//$(".tag-sale-value").html(total_price);

	curRow.find('td:eq(8) .old_amount').val(parseFloat(total_price).toFixed(2));

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

function remove_row(curRow)

	{

		curRow.remove();

		calculate_purchase_details();

		calculate_sales_details();

		calculate_salesReturn_details();

	}

function remove_orderAdv_row(curRow)

	{

		curRow.remove();

		calculate_orderAdv_purchase_details();

	    calculate_orderAdv_sales_details();

	}

function calculate_sales_details(){

	var sales_weight = 0;

	var sales_amt 	= 0;

	var discount_amt 	= 0;

	var tax_group_id='';

	var base_value_amt=0;

	var arrived_value_amt=0;

	var total_tax_rate=0;

	var cgst=0;

	var sgst=0;

	var igst=0;

	var cus_state=$('#cus_state').val();

	var cmp_state=$('#cmp_state').val();

	

    var tcs_tax_amt=0;

	var tot_purchase_amt=$('#tot_purchase_amt').val();

	var tcs_min_bill_amt=$('#tcs_min_bill_amt').val();

	var tcs_tax_per=$('#tcs_tax_per').val();

	var is_tcs_required=$('#is_tcs_required').val();

	

	$('#billing_sale_details > tbody  > tr').each(function(index, tr) {

		if($(this).find('.bill_gross_val').val() != "" && $(this).find('.gwt').html() != "" && $(this).find('.sales_value').val() != ""){

			sales_weight += parseFloat((isNaN($(this).find('.bill_net_val').val()) || $(this).find('.bill_net_val').val() == '')  ? 0 : $(this).find('.bill_net_val').val());

			sales_amt += parseFloat((isNaN($(this).find('.bill_taxable_amt').html()) || $(this).find('.bill_taxable_amt').html() == '')  ? 0 : $(this).find('.bill_taxable_amt').html());

			discount_amt += parseFloat((isNaN($(this).find('.bill_discount').val()) || $(this).find('.bill_discount').val() == '')  ? 0 : $(this).find('.bill_discount').val());

			

			cgst += parseFloat((isNaN($(this).find('.sale_cgst').val()) || $(this).find('.sale_cgst').val() == '')  ? 0 : $(this).find('.sale_cgst').val());

			sgst += parseFloat((isNaN($(this).find('.sale_sgst').val()) || $(this).find('.sale_sgst').val() == '')  ? 0 : $(this).find('.sale_sgst').val());

			igst += parseFloat((isNaN($(this).find('.sale_igst').val()) || $(this).find('.sale_igst').val() == '')  ? 0 : $(this).find('.sale_igst').val());

			

			tax_group_id = $(this).find('.sale_tax_group').val();

		}

	});

	
	cgst = parseFloat(cgst).toFixed(2);
	sgst = parseFloat(sgst).toFixed(2);
	igst = parseFloat(igst).toFixed(2);
	

	$(".sales_cgst").html(cgst);

	$(".sales_sgst").html(sgst);

	$(".sales_igst").html(igst);

	

	var total_sales_amt=parseFloat(parseFloat(sales_amt)+parseFloat(cgst)+parseFloat(sgst)+parseFloat(igst)).toFixed(2);

	

	if((parseFloat(tot_purchase_amt)+parseFloat(total_sales_amt)>=tcs_min_bill_amt) && is_tcs_required==1 ) //tot_purchase_amt - Customer Total Purchase Amount ,tcs_min_bill_amt- Minimum Purchase Amount

	{

	    tcs_tax_amt=parseFloat(parseFloat(parseFloat(total_sales_amt)*parseFloat(tcs_tax_per))/100).toFixed(2);

	}

	

	$(".tcs_tax_amt").html(tcs_tax_amt);

	$('#tcs_total_tax_amount').val(tcs_tax_amt);

	$(".sale_amt_with_tax").html(total_sales_amt);

	$(".summary_sale_weight").html(parseFloat(sales_weight).toFixed(3));

	$(".summary_sale_amt").html(parseFloat(sales_amt).toFixed(2));

	

	check_gift_vocuher_issue();

	calculateFinalCost();

}

function calculate_purchase_details(){

	var pur_weight = 0;

	var pur_rate 	= 0;

	var gift_voucher = 0;

	var chit_amt 	= 0;

	$('#purchase_item_details > tbody  > tr').each(function(index, tr) {

		if($(this).find('.pur_gross_val').val() != "" && $(this).find('.bill_amount').val() != ""){

			pur_weight += parseFloat((isNaN($(this).find('.pur_net_val').val()) || $(this).find('.pur_net_val').val() == '')  ? 0 : $(this).find('.pur_net_val').val());

			pur_rate += parseFloat((isNaN($(this).find('.bill_amount').val()) || $(this).find('.bill_amount').val() == '')  ? 0 : $(this).find('.bill_amount').val());

		}

	});

	$(".summary_pur_weight").html(parseFloat(pur_weight).toFixed(3));

	$(".summary_pur_amt").html(parseFloat(pur_rate).toFixed(2));

	calculateFinalCost();

}



function calculate_order_adv_purchase_details(){

	var purchase_weight = 0;

	var purchase_rate 	= 0;

	var discount_amt 	= 0;

	var adv_paid_amt	=0;

	$('#purchase_item_details > tbody  > tr').each(function(index, tr) {

		if($(this).find('.pur_gross_val').val() != "" &&  $(this).find('.bill_amount').val() != ""){

			purchase_weight += parseFloat((isNaN($(this).find('.pur_net_val').val()) || $(this).find('.pur_net_val').val() == '')  ? 0 : $(this).find('.pur_net_val').val());

			purchase_rate += parseFloat((isNaN($(this).find('.bill_amount').val()) || $(this).find('.bill_amount').val() == '')  ? 0 : $(this).find('.bill_amount').val());

		}

	});

	$(".summary_pur_weight").html(purchase_weight);

	$(".summary_pur_amt").html(purchase_rate);

	$(".summary_discount_amt").val(discount_amt);

	$(".adv_rcd_wt").html(purchase_weight);

	//calculateFinalCost();

}



function calculate_order_advance_sale_details()

{

	var wast_wgt = 0;

	var net_wt 	= 0;

	var tot_adv_wt=0;

	var max_amt=0;

	var rate_per_grm=$('.per-grm-sale-value').html();

	var adv_paid_wt=(isNaN($('.adv_paid_wt').html()) ? 0:$('.adv_paid_wt').html());

	$('#billing_order_adv_details > tbody  > tr').each(function(index, tr) {

		if($(this).find('.bill_net_val').val() != "" ){

			console.log($(this).find('.bill_net_val').val());

			wast_wgt += parseFloat((isNaN($(this).find('.wast_wgt').val()) || $(this).find('.wast_wgt').val() == '')  ? 0 : $(this).find('.wast_wgt').val());

			net_wt += parseFloat((isNaN($(this).find('.bill_net_val').val()) || $(this).find('.bill_net_val').val() == '')  ? 0 : $(this).find('.bill_net_val').val());

		}

	});

	console.log('adv_paid_wt'+adv_paid_wt);

	console.log('net_wt'+net_wt);

	console.log('wast_wgt'+wast_wgt);

	tot_adv_wt	= parseFloat((net_wt)-adv_paid_wt).toFixed(3);

	$('.max_wt').val(tot_adv_wt);

	$('.adv_blc_wt').html(tot_adv_wt);

}



$('.adv_amt').on('keyup',function(e){

    var adv_amt=(isNaN(this.value) || this.value=='' ? 0 :this.value);

	/*var max_wt=$('.max_wt').val();

	var rate_per_grm=$('.per-grm-sale-value').html();

	var adv_amt=(isNaN(this.value) || this.value=='' ? 0 :this.value);

	max_amt=parseFloat(parseFloat(max_wt)*parseFloat(rate_per_grm));

	if(max_amt<adv_amt)

	{

		alert('Maximum Advance Exceed');

		$('.receive_amount').val('');

		$('.adv_amt').val('');

		$('.adv_amt').focus();

	}else{

	    	$('.receive_amount').val(adv_amt);

	}*/

	

	

		$('.receive_amount').val(adv_amt);

});





function calculate_orderAdv_sales_details(){

	var sales_weight = 0;

	var sales_amt 	= 0;

	var discount_amt 	= 0;

	var tax_group_id='';

	var base_value_amt=0;

	var arrived_value_amt=0;

	var total_tax_rate=0;

	var cgst=0;

	var sgst=0;

	var igst=0;

	var cus_state=$('#cus_state').val();

	var cmp_state=$('#cmp_state').val();

	$('#billing_order_adv_details > tbody  > tr').each(function(index, tr) {

		if($(this).find('.bill_gross_val').val() != "" && $(this).find('.bill_amount').val() != ""){

			sales_weight += parseFloat((isNaN($(this).find('.bill_net_val').val()) || $(this).find('.bill_net_val').val() == '')  ? 0 : $(this).find('.bill_net_val').val());

			sales_amt += parseFloat((isNaN($(this).find('.bill_taxable_amt').html()) || $(this).find('.bill_taxable_amt').html() == '')  ? 0 : $(this).find('.bill_taxable_amt').html());

			discount_amt += parseFloat((isNaN($(this).find('.bill_discount').val()) || $(this).find('.bill_discount').val() == '')  ? 0 : $(this).find('.bill_discount').val());

			cgst += parseFloat((isNaN($(this).find('.sale_cgst').val()) || $(this).find('.sale_cgst').val() == '')  ? 0 : $(this).find('.sale_cgst').val());

			sgst += parseFloat((isNaN($(this).find('.sale_sgst').val()) || $(this).find('.sale_sgst').val() == '')  ? 0 : $(this).find('.sale_sgst').val());

			igst += parseFloat((isNaN($(this).find('.sale_igst').val()) || $(this).find('.sale_igst').val() == '')  ? 0 : $(this).find('.sale_igst').val());

		

		}

	});

	cgst = parseFloat(cgst).toFixed(2);
	sgst = parseFloat(sgst).toFixed(2);
	igst = parseFloat(igst).toFixed(2);

	$(".sales_cgst").html(cgst);

	$(".sales_sgst").html(sgst);

	$(".sales_igst").html(igst);

	

	var total_sales_amt=parseFloat(parseFloat(sales_amt)+parseFloat(cgst)+parseFloat(sgst)+parseFloat(igst)).toFixed(2);

	

	$(".sale_amt_with_tax").html(total_sales_amt);

	$(".sale_amt_with_tax").html(total_sales_amt);

	$(".summary_sale_weight").html(parseFloat(sales_weight).toFixed(3));

	$(".summary_sale_amt").html(parseFloat(sales_amt).toFixed(2));

	//	$(".summary_discount_amt").val(parseFloat(discount_amt).toFixed(2));

	console.log('total_sales_amt :'+total_sales_amt);

	calculateFinalCost();

}

function calculate_orderAdv_purchase_details(){

	var purchase_weight = 0;

	var purchase_rate 	= 0;

	var discount_amt 	= 0;

	var adv_paid_amt	=0;

	$('#purchase_item_details > tbody  > tr').each(function(index, tr) {

		if($(this).find('.pur_gross_val').val() != "" &&  $(this).find('.bill_amount').val() != ""){

			purchase_weight += parseFloat((isNaN($(this).find('.pur_net_val').val()) || $(this).find('.pur_net_val').val() == '')  ? 0 : $(this).find('.pur_net_val').val());

			purchase_rate += parseFloat((isNaN($(this).find('.bill_amount').val()) || $(this).find('.bill_amount').val() == '')  ? 0 : $(this).find('.bill_amount').val());

		}

	});

	$(".summary_pur_weight").html(purchase_weight);

	$(".summary_pur_amt").html(purchase_rate);

	$(".summary_discount_amt").val(discount_amt);

	calculateFinalCost();

}



$('.handling_charges').on('keyup',function(){

    calculateFinalCost();

});



$('.credit_discount_amt').on('keyup',function(){

    calculateFinalCost();

});



function calculateFinalCost(){

    var bill_type       = $("input[name='billing[bill_type]']:checked").val();

	var purchase_amt 	= 0;

	var sales_amt 	 	= 0;

	var discount 		= 0;

	var adv_paid_amt 	= 0;

	var sale_return 	= 0;

	var handling_charges =0;

	var credit_amt      =0;

	var credit_discount =0;

	var tcs_tax_amt = 0;

	

	purchase_amt		= parseFloat((isNaN($('.summary_pur_amt').html()) || $('.summary_pur_amt').html() == '')  ? 0 : $('.summary_pur_amt').html()).toFixed(2);

	sales_amt			= parseFloat((isNaN($('.sale_amt_with_tax').html()) || $('.sale_amt_with_tax').html() == '')  ? 0 : $('.sale_amt_with_tax').html()).toFixed(2);

	discount			= parseFloat((isNaN($('.summary_discount_amt').val()) || $('.summary_discount_amt').val() == '')  ? 0 : $('.summary_discount_amt').val()).toFixed(2);

	adv_paid_amt		= parseFloat((isNaN($('.summary_adv_paid_amt').html()) || $('.summary_adv_paid_amt').html() == '')  ? 0 : $('.summary_adv_paid_amt').html()).toFixed(2);

	sale_return			= parseFloat((isNaN($('.summary_sale_ret_amt').html()) || $('.summary_sale_ret_amt').html() == '')  ? 0 : $('.summary_sale_ret_amt').html()).toFixed(2);

	handling_charges    = ($('.handling_charges').val()!='' ?$('.handling_charges').val() :0);

	

    tcs_tax_amt		= parseFloat((isNaN($('.tcs_tax_amt').html()) || $('.tcs_tax_amt').html() == '')  ? 0 : $('.tcs_tax_amt').html()).toFixed(2);

    

    credit_amt			= parseFloat((isNaN($('.summary_credit_amt').html()) || $('.summary_credit_amt').html() == '')  ? 0 : $('.summary_credit_amt').html()).toFixed(2);

	credit_discount		= parseFloat((isNaN($('.credit_discount_amt').val()) || $('.credit_discount_amt').val() == '')  ? 0 : $('.credit_discount_amt').val()).toFixed(2);

	

	

	tot_cost 		     = parseFloat(parseFloat(sales_amt)+parseFloat(tcs_tax_amt)+parseFloat(credit_amt)-parseFloat(purchase_amt)-parseFloat(adv_paid_amt)-parseFloat(sale_return)+parseFloat(handling_charges)-parseFloat(credit_discount)).toFixed(2);

	

	if(bill_type!=5)

	{

	   round_of_val         =tot_cost;

	    tot_cost 			= parseFloat(Math.round(tot_cost));

	    round_of_amt        = parseFloat(tot_cost-round_of_val).toFixed(2);

	    $('#round_off').val(round_of_amt<0.50 ? round_of_amt : round_of_amt);

	    $('.summary_round_off').html(((tot_cost)<(round_of_val) ? round_of_amt : round_of_amt));

	}

	

	$(".total_cost").val(tot_cost);

	$('#total_payment_amount').val(tot_cost);

	if(tot_cost < 0)

	{

		$(".pay_to_cus").val(tot_cost* -1); // Make positive and add

		$(".receive_amount").val(0);

		$(".receive_amount").prop('readonly',true);

	}

	else

	{

		$(".pay_to_cus").val(0);

		$(".receive_amount").val(tot_cost);

		$(".receive_amount").prop('readonly',false);

	}

	if(sales_amt>=$('#min_pan_amt').val() && $('#is_pan_required').val()==1)

	{

		$('#pan_no').prop('disabled',false);

		$('#pan_images').prop('disabled',false);

	}

	if($(".pay_to_cus").val()>=10000)

        {

            alert('The Total Return Amount is '+$(".pay_to_cus").val());

        }

	calculatePaymentCost();

}



function checkGiftAvailability()

{

    var sale_weight              =0;

    var eligible_weight          =0;

    var gift_amt                 =0;

    var per_gram_amt             =parseFloat($('#per_gram_amt').val());

    var min_wt_gram              =parseFloat($('#min_wt_gram').val());

    var gold_rate                =$('.per-grm-sale-value').html();

    var silverrate_1gm           =$('.silver_per-grm-sale-value').html();

    var validate_date            =$('#validate_date').val();

    var utilized_gift_amt        =($('#tot_voucher_amt').html()!='' ? $('#tot_voucher_amt').html():0);

    var tot_chit_amt             =($('#tot_chit_amt').html()!='' ? $('#tot_chit_amt').html():0);

    if(tot_chit_amt==0)

    {

        $('#billing_sale_details > tbody  > tr').each(function(index, tr) {

    		if($(this).find('.bill_gross_val').val() != "" && $(this).find('.bill_amount').val() != "" && $(this).find('.gift_applicable').val()==1 && $(this).find('.sale_metal_type').val() == 1)

    		{

    			sale_weight += parseFloat((isNaN($(this).find('.bill_net_val').val()) || $(this).find('.bill_net_val').val() == '')  ? 0 : $(this).find('.bill_net_val').val());

    		}

    	});

    	eligible_weight=sale_weight;

    	eligible_weight=parseFloat(eligible_weight)

    	

    	if(min_wt_gram<=eligible_weight)

    	{

    	    $('.gift_details').css('display','block');

    	    if(utilized_gift_amt>0)

    	    {

    	        eligible_weight=parseFloat(parseFloat(eligible_weight)-parseFloat(utilized_gift_amt/gold_rate)-parseFloat(tot_chit_amt/gold_rate)).toFixed(3);

    	    }

    	      //gift_amt=parseFloat(eligible_weight*silverrate_1gm);

    	    

    	     gift_amt=parseFloat(Math.round(eligible_weight*per_gram_amt));

    	    

    	    console.log(gift_amt);

    	}

    

    	$('#gift_voucher_amt').val(Math.round(gift_amt));

    	$('.summary_gift_voucher').html('Rs.'+Math.round(gift_amt)+'  Valid Till '+validate_date);

    	console.log('min_wt_gram :'+min_wt_gram);

    	console.log('eligible_wt :'+eligible_weight);

    }else{

        	$('#gift_voucher_amt').val(0);

    	    $('.summary_gift_voucher').html('');

    }

}



$('#total_cost').on('keyup',function(){

    var final_price=parseFloat($('#total_payment_amount').val()).toFixed(2);

    var total_cost=parseFloat($('.total_cost').val()).toFixed(2);

    var total_discount=($('#total_discount').val()!='' ? $('#total_discount').val():0);

    var total_sales_amount=($('.sale_amt_with_tax').html()!='' ? $('.sale_amt_with_tax').html():0);

    if(final_price<total_cost)

    {

        $('#paymentAlert').html('The Final Bill Amount is '+final_price);

        $('.total_cost').val(final_price);

        $('.summary_discount_amt').val(total_discount);

    }

    else

    {

        var discount=parseFloat(parseFloat(final_price)-parseFloat(total_cost)).toFixed(2);

        $('.summary_discount_amt').val(discount);

        $('#paymentAlert').html('');

    }

        if(total_cost < 0)

    	{

    		$(".pay_to_cus").val(($('.total_cost').val())* -1); // Make positive and add

    		$(".receive_amount").val(0);

    	}

    	else

    	{

    		$(".pay_to_cus").val(0);

    		$(".receive_amount").val($('.total_cost').val());

    	}

    	if(total_sales_amount>=200000)

    	{

    		$('#pan_no').prop('disabled',false);

    	}

    	$('.bal_amount').html(($('.total_cost').val()));

});

/*$('#summary_discount_amt').on('keyup',function(e){

    e.preventDefault();

    var total_discount=$('#total_discount').val();

    var summary_discount_amt=($('#summary_discount_amt').val()!='' ? $('#summary_discount_amt').val():0);

    var total_payment_amount=$('#total_payment_amount').val();

    var final_price=$('#total_payment_amount').val();

        if(total_payment_amount<summary_discount_amt)

        {

            $('#paymentAlert').html('The Final Bill Amount is '+total_payment_amount);

            $('#summary_discount_amt').val(summary_discount_amt);

            $('.total_cost').val(total_payment_amount);

        }

        else

        {

            var final_price=parseFloat(parseFloat(total_payment_amount)-parseFloat(summary_discount_amt)).toFixed(2);

            $('.total_cost').val(final_price);

            $('#paymentAlert').html('');

        }

        if(final_price < 0)

    	{

    		$(".pay_to_cus").val(final_price* -1); // Make positive and add

    		$(".receive_amount").val(0);

    	}

    	else

    	{

    		$(".pay_to_cus").val(0);

    		$(".receive_amount").val(final_price);

    	}

    	if(final_price>=200000)

    	{

    		$('#pan_no').prop('disabled',false);

    	}

        $('.bal_amount').html(final_price);

});*/

/*$('#summary_discount_amt').on('keyup',function(e){

    e.preventDefault();

   

});*/



$('.receive_amount').on('change',function(){

    var is_credit=$("input[name='billing[is_credit]']:checked").val();

    if(parseFloat($('#total_cost').val())!=parseFloat($('.receive_amount').val()))

    {

        $('#is_credit_yes').prop('checked', true);

        $('#credit_due_date').prop('disabled', false);

    }

    else

    {

        $('#credit_due_date').prop('disabled', true);

        $('#is_credit_no').prop('checked', true);

    }

    if($('#credit_due_date').val()=='')

    {

        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>Please Select Due Date"});

    }

    calculatePaymentCost();

});



$('#make_pay_cash,.receive_amount').on('keyup',function(){

    

    

	calculatePaymentCost();

});

function calculatePaymentCost()

{

	

	var chit_amt        =0;

	var voucher_amt     =0;

	var total_amount    =0;

	var bal_amount      =0;

	var wallet_blc      =0;

	var receive_amount  =($('.receive_amount').val()!='' ? $('.receive_amount').val():0);

	var pay_to_cus      =parseFloat($('.pay_to_cus').val()!='' ? $('.pay_to_cus').val():0);

	var make_pay_cash   =($('#make_pay_cash').val()!='' ? $('#make_pay_cash').val():0);

	var cc              =($('.CC').html()!='' ? $('.CC').html():0);

	var dc              =($('.DC').html()!='' ? $('.DC').html():0);

	var chq             =($('.CHQ').html()!='' ? $('.CHQ').html():0);

	var NB              =($('.NB').html()!='' ? $('.NB').html():0);

	var tot_chit_amt    =($('#tot_chit_amt').html()!='' ? $('#tot_chit_amt').html():0);

	var tot_adv_adj     =($('#tot_adv_adj').html()!='' ? $('#tot_adv_adj').html():0);

	var tot_voucher_amt =($('#tot_voucher_amt').html()!='' ? $('#tot_voucher_amt').html():0);

	var is_credit       =$("input[name='billing[is_credit]']:checked").val();

	var bill_type       = $("input[name='billing[bill_type]']:checked").val();

	

	if(tot_chit_amt>0)

    {

        $('#gift_voucher_modal').attr('disabled',true);

        $('#giftVoucher_details').val('');

        $('#tot_voucher_amt').html('');

        $('#gift_voucher_amt').val(0);

    	$('.summary_gift_voucher').html('');

    }else{

         $('#gift_voucher_modal').attr('disabled',false);

    }

  

    if(parseFloat(receive_amount)<parseFloat(tot_voucher_amt))

    {

        $('#gift_voucher_modal').attr('disabled',true);

        $('#giftVoucher_details').val('');

        $('#tot_voucher_amt').html(0);

        tot_voucher_amt=0;

    }

            

	if(bill_type!=10)

	{

    	if(adv_adj_details.length>0)

    	{

    		wallet_blc=adv_adj_details[0].wallet_blc;

    	}

    	if(receive_amount>0)

    	{

    		total_amount=parseFloat(parseFloat(make_pay_cash)+parseFloat(tot_chit_amt)+parseFloat(tot_voucher_amt)+parseFloat(cc)+parseFloat(dc)+parseFloat(tot_adv_adj)+parseFloat(chq)+parseFloat(NB)).toFixed(2);

    	}

    	else if(pay_to_cus>0)

    	{

    		total_amount=parseFloat(parseFloat(make_pay_cash)+parseFloat(NB)).toFixed(2);

    	}

    	else

    	{

    		total_amount=parseFloat(parseFloat(make_pay_cash)+parseFloat(tot_chit_amt)+parseFloat(tot_voucher_amt)+parseFloat(cc)+parseFloat(dc)+parseFloat(tot_adv_adj)).toFixed(2);

    	}

    	bal_amount=parseFloat(parseFloat(receive_amount)-parseFloat(total_amount)).toFixed(2);

    	$('.sum_of_amt').html(total_amount);

    	$('.bal_amount').html(bal_amount);

    	if(bal_amount==0 && (make_pay_cash>0 || NB>0 || chq>0 || tot_chit_amt>0 || tot_adv_adj>0 || tot_voucher_amt>0 || cc>0 || dc>0))

    	{	

    		if(total_amount>=parseFloat($('#min_pan_amt').val()))

    		{

    			if($('#is_pan_required').val()==1 && ($('#pan_no').val()==''))

    			{

    				$('#pay_submit').prop('disabled',true);

    			}

    			else{

    				$('#pay_submit').prop('disabled',false);	

    			}

    		}

    		else{

    			$('#pay_submit').prop('disabled',false);

    		}

    	}

    	else if(($('#total_cost').val()==0) && bill_type!=5)

    	{

    	    if(receive_amount==0)

    	    {

    	        	$('#pay_submit').prop('disabled',false);

    	    }

    	

    	}

    	else if(pay_to_cus>0)

    	{

    	   

    		if(make_pay_cash<10000)

    		{

    			$('.bal_amount').html(parseFloat(pay_to_cus-total_amount).toFixed(2));

    			if(pay_to_cus==total_amount)

    			{

    				$('#pay_submit').prop('disabled',false);

    			}else{

    				$('#pay_submit').prop('disabled',true);

    			}

    		}else{

    			alert('Maximum Cash Return Amount is Rs.10000');

    			$('#make_pay_cash').val(0);

    			$('#make_pay_cash').focus();

    			$('.sum_of_amt').html(0);

    			$('.bal_amount').html(0);

    		}

    		 $('#giftVoucher_details').val('');

             $('#tot_voucher_amt').html('');

    	}

    	else if(is_credit==1)

    	{

    	    console.log(total_amount);

    		if(receive_amount==total_amount)

    	    {

    	        $('#pay_submit').prop('disabled',false);

    	    }else{

    	        $('#pay_submit').prop('disabled',true);

    	    }

    	}

    	else if(wallet_blc>0)

    	{

    		$('#pay_submit').prop('disabled',false);

    	}else{

    		$('#pay_submit').prop('disabled',true);

    	}

    	

        if((bill_type==1 || bill_type==2 || bill_type==3))

        {

           

                //check_gift_vocuher_issue();

        }

        else

        {

            $('.gift_details').css('display','none');

    	}

    }

    else if(bill_type==10)

    {

         $('.pay_to_cus').val(tot_chit_amt);

        

        total_amount=parseFloat(parseFloat(make_pay_cash)+parseFloat(NB)).toFixed(2);

        

        

        	if(make_pay_cash<10000)

    		{

    			$('.bal_amount').html(parseFloat(pay_to_cus-total_amount).toFixed(2));

    			if(pay_to_cus==total_amount)

    			{

    				$('#pay_submit').prop('disabled',false);

    			}else{

    				$('#pay_submit').prop('disabled',true);

    			}

    		}else{

    			alert('Maximum Cash Return Amount is Rs.10000');

    			$('#make_pay_cash').val(0);

    			$('#make_pay_cash').focus();

    			$('.sum_of_amt').html(0);

    			$('.bal_amount').html(0);

    		}

    		

        if(total_amount==$('.pay_to_cus').val())

        {

            $('#pay_submit').prop('disabled',false);

        }else{

            $('#pay_submit').prop('disabled',true);

        }

    }

	

}

function get_billing_list()

{

	my_Date = new Date();

	$("div.overlay").css("display", "block"); 

	$.ajax({

		 url:base_url+"index.php/admin_ret_billing/billing/ajax?nocache=" + my_Date.getUTCSeconds(),

		 dataType:"JSON",

		 data:{'dt_range' :$("#dt_range").val(),'bill_no':$('#filter_bill_no').val(),'id_branch':($('#branch_filter').val()!='' && $('#branch_filter').val()!=undefined ? $('#branch_filter').val(): $("#branch_select").val())},

		 type:"POST",

		 success:function(data){

			console.log("List", data);

   			set_billing_list(data);

   			$("div.overlay").css("display", "none"); 

		  },

		  error:function(error)  

		  {

			 $("div.overlay").css("display", "none"); 

		  }	 

	});

}

$(document).on('click', "a.cancell-bill", function(e) {

      e.preventDefault();

       var link=$(this).data('href');        

      $('#confirm-billcancell').find('.btn-confirm').attr('href',link);

}); 

   

function set_billing_list(data)

{

    $("div.overlay").css("display", "none");

    var billing = data.list;

    var access = data.access;

    var oTable = $('#billing_list').DataTable();

    $("#total_billing").text(billing.length);

    if(access.add == '0')

    {

         $('#add_billing').attr('disabled','disabled');

    }

    oTable.clear().draw();

    if (billing!= null && billing.length > 0)

    {

        oTable = $('#billing_list').dataTable({

        "bDestroy": true,

        "bInfo": true,

        "bFilter": true,

        "bSort": true,

        "order": [[ 0, "desc" ]],

        "dom": 'lBfrtip',

        "buttons" : ['excel','print'],

        "tableTools": { "buttons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },

        "aaData": billing,

        "aoColumns": [{ "mDataProp": "bill_id" },

        { "mDataProp": "bill_date" },

        { "mDataProp": "branch_name" },

        { "mDataProp": "bill_no" },

        { "mDataProp": "customer" },

        { "mDataProp": "mobile" },

        { "mDataProp": "bill_type" },

        { "mDataProp": "tot_bill_amt" },

        { "mDataProp": "bill_status" },

        { "mDataProp": function ( row, type, val, meta ) {

        id= row.bill_id;

        edit_url=(access.edit=='1' ? base_url+'index.php/admin_ret_billing/billing/edit/'+id : '#' );

        print_url=base_url+'index.php/admin_ret_billing/billing_invoice/'+id;

        delete_url=(access.delete=='1' ? base_url+'index.php/admin_ret_billing/billing/delete/'+id : '#' );

        billcancel_url=(access.edit=='1' ? base_url+'index.php/admin_ret_billing/billing/cancell/'+id+'/'+row.bill_no : '#' );

        /*<a href="'+edit_url+'" class="btn btn-primary btn-edit"><i class="fa fa-edit" ></i></a>

        <a href="#" class="btn btn-danger btn-del" data-href='+delete_url+' data-toggle="modal" data-target="#confirm-delete" ><i class="fa fa-trash"></i></a>*/

        action_content='<a href="'+print_url+'" target="_blank" class="btn btn-info btn-print" data-toggle="tooltip" title="Billing Receipt"><i class="fa fa-print" ></i></a>'+(row.allow_cancel==1 && access.edit=='1'?'<button class="btn btn-warning" onclick="confirm_delete('+id+')"><i class="fa fa-close" ></i></button>' :'');

        

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

function get_metal_rates_by_branch()

{

	var id_branch = $('#id_branch').val();

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

			$('#goldrate_22ct').val(data.goldrate_22ct);

			$('#silverrate_1gm').val(data.silverrate_1gm);

			$('#goldrate_18ct').val(data.goldrate_18ct);

			$('#goldrate_24ct').val(data.goldrate_24ct);

		},

		error:function(error)  

		{

			$("div.overlay").css("display", "none");

		}

	});

}



function get_branch_details()

{

	var id_branch = $('#id_branch').val();

	my_Date = new Date();

	$.ajax({

		url:base_url+ "index.php/admin_ret_billing/get_branch_details?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),

		data:  {'id_branch':id_branch},

		type:"POST",

		dataType: "json",

		async:false,

		success:function(data){

		    if(data!='')

		    {

		        $('.gift_row').css('display','block');

    	        $('.summary_gift_voucher').css('display','block');

		        $('#enable_gift_voucher').val(data.enable_gift_voucher);

    		    $('#gift_type').val(data.gift_type);

    		    $('#utilize_for').val(data.utilize_for);

    		    $('#utilize_for').val(data.utilize_for);

    		    $('#bill_value').val(data.sale_value);

    		    $('#credit_value').val(data.credit_value);

    		    $('#validate_date').val(data.validate_date);

    		    $('#validity_days').val(data.validity_days);

    		    $('#id_set_gift_voucher').val(data.id_set_gift_voucher);

    		    $('#issue_for').val(data.metal);

    		    $('#calc_type').val(data.calc_type);

		    }else{

		        $('.gift_row').css('display','none');

    	        $('.summary_gift_voucher').css('display','none');

		    }

		    

		},

		error:function(error)  

		{

			$("div.overlay").css("display", "none");

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

/**

* Order Advance functions

* Starts

*/

function updateEstiOrderdetailsInBill(est_order_row){

	rowExist = false;

	$(".order_adv_details").show();

	$('#billing_order_adv_details > tbody tr').each(function(bidx, brow){ 

		bill_sale_row = $(this);

		// CHECK DUPLICATES - ESTIMATION ITEM [ORDER]

		if(bill_sale_row.find('td:first .is_est_details').val() == 1 ){

			if(est_order_row.find('td:first .est_itm_id').val() == bill_sale_row.find('td:first .est_itm_id').val()){

				rowExist = true;

			}

		}

	});

	if(!rowExist){

		if(est_order_row.find('td:last .select_est_partial').is(':checked')){

			var row = '<tr><td><span>'+est_order_row.find('td:first .est_hsn').val()+'</span><input type="hidden" class="sale_pro_hsn" name="order[hsn]" value="'+est_order_row.find('td:first .est_hsn').val()+'" /><input type="hidden" class="sale_type" name="order[sourcetype][]" value="1" /><input type="hidden" class="sale_item_type" name="order[itemtype][]" value="'+est_order_row.find('td:first .est_item_type').val()+'" /><input type="hidden" class="is_est_details" value="1" name="order[is_est_details][]" /><input type="hidden" class="est_itm_id" name="order[est_itm_id][]" value="'+est_order_row.find('td:first .est_itm_id').val()+'" /><input type="hidden" class="sale_cal_type" name="order[calltype][]" value="'+est_order_row.find('td:first .est_cal_type').val()+'" /><input type="hidden" class="sale_metal_type" value="'+est_order_row.find('td:first .est_metal_type').val()+'" /><input type="hidden" class="sale_purity" value="'+est_order_row.find('td:eq(4) .est_purid').val()+'"  name="order[purity][]" /><input type="hidden" class="sale_size" value="'+est_order_row.find('td:eq(5) .est_size_val').val()+'"  name="order[size][]" /><input type="hidden" class="sale_uom" value="'+est_order_row.find('td:eq(0) .est_uom').val()+'"  name="order[uom][]" /><input type="hidden" class="total_tax" name="order[total_tax][]"></td><td><span>'+est_order_row.find('td:eq(1) .est_product_name').html()+'</span><input class="sale_product_id" type="hidden" name="order[product][]" value="'+est_order_row.find('td:eq(1) .est_product_id').val()+'" /></td><td><span>'+est_order_row.find('td:eq(2) .est_design_code').html()+'</span><input type="hidden" class="sale_design_id" name="order[design][]" value="'+est_order_row.find('td:eq(2) .est_design_id').val()+'" /></td><td><input type="number" class="sale_pcs" name="order[pcs][]" value="'+est_order_row.find('td:eq(3) .est_pcs').val()+'"  /></td><td><span>'+est_order_row.find('td:eq(6) .est_gross_wt').html()+'</span><input type="hidden" class="bill_gross_val" name="order[gross][]" value="'+est_order_row.find('td:eq(6) .est_gross_val').val()+'" /></td><td><span>'+est_order_row.find('td:eq(7) .est_less_wt').html()+'</span><input type="hidden" class="bill_less_val" name="order[less][]" value="'+est_order_row.find('td:eq(7) .est_less_val').val()+'" /></td><td><span>'+est_order_row.find('td:eq(8) .est_net_wt').html()+'</span><input type="hidden" class="bill_net_val" name="order[net][]" value="'+est_order_row.find('td:eq(8) .est_net_val').val()+'" /></td><td><span>'+est_order_row.find('td:eq(9) .est_wastage').html()+'</span><input type="hidden" class="bill_wastage" name="order[wastage][]" value="'+est_order_row.find('td:eq(9) .est_wastage_percent').val()+'" /></td><td><span>'+est_order_row.find('td:eq(10) .est_mc').html()+'</span><input type="hidden" class="bill_mctype" name="order[bill_mctype][]" value="'+est_order_row.find('td:eq(10) .est_mc_type').val()+'" /><input type="hidden" class="bill_mc" name="order[mc][]" value="'+est_order_row.find('td:eq(10) .est_mc_value').val()+'" /></td><input type="number" class="bill_discount" name="order[discount][]" value="'+est_order_row.find('td:eq(11) .est_discount').val()+'" step="any" /></td><td></td><td><span>'+est_order_row.find('td:eq(12) .est_tgrp_name').html()+'</span><input type="hidden" class="sale_tax_group" name="order[taxgroup][]" value="'+est_order_row.find('td:eq(12) .est_tax_id').val()+'" /></td><td></td><td><input type="hidden" class="bill_stone_price" value="'+est_order_row.find('.est_stone_price').val()+'" /><input type="hidden" class="bill_material_price" value="'+est_order_row.find('.est_material_price').val()+'"/><input type="number" class="bill_amount" name="order[billamount][]" value="" step="any" readonly /><input type="hidden" class="per_grm_amount" name="order[per_grm][]" value="" step="any" /></td><td>Yes</td><td><span>'+est_order_row.find('td:first .order_no').val()+'</span><input type="hidden" class="order_no" name="order[order_no][]" value="'+est_order_row.find('td:first .order_no').val()+'" /></td><td><span>'+est_order_row.find('td:first .est_id').val()+'</span><input type="hidden" class="sale_est_itm_id" name="order[estid][]" value="'+est_order_row.find('td:first .est_itm_id').val()+'" /></td><td><a href="#" onClick="remove_row($(this).closest(\'tr\'));" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';

		}else{  

			var row = '<tr><td><span>'+est_order_row.find('td:first .est_hsn').val()+'</span><input type="hidden" class="sale_pro_hsn" name="order[hsn]" value="'+est_order_row.find('td:first .est_hsn').val()+'" /><input type="hidden" class="sale_type" name="order[sourcetype][]" value="1" /><input type="hidden" class="sale_item_type" name="order[itemtype][]" value="'+est_order_row.find('td:first .est_item_type').val()+'" /><input type="hidden" class="is_est_details" value="1" name="order[is_est_details][]" /><input type="hidden" class="est_itm_id" name="order[est_itm_id][]" value="'+est_order_row.find('td:first .est_itm_id').val()+'" /><input type="hidden" class="sale_cal_type" name="order[calltype][]" value="'+est_order_row.find('td:first .est_cal_type').val()+'" /><input type="hidden" class="sale_metal_type" value="'+est_order_row.find('td:first .est_metal_type').val()+'" /><input type="hidden" class="sale_purity" value="'+est_order_row.find('td:eq(4) .est_purid').val()+'"  name="order[purity][]" /><input type="hidden" class="sale_size" value="'+est_order_row.find('td:eq(5) .est_size_val').val()+'"  name="order[size][]" /><input type="hidden" class="sale_uom" value="'+est_order_row.find('td:eq(0) .est_uom').val()+'"  name="order[uom][]" /><input type="hidden" class="total_tax" name="order[total_tax][]"></td><td><span>'+est_order_row.find('td:eq(1) .est_product_name').html()+'</span><input class="sale_product_id" type="hidden" name="order[product][]" value="'+est_order_row.find('td:eq(1) .est_product_id').val()+'" /></td><td><span>'+est_order_row.find('td:eq(2) .est_design_code').html()+'</span><input type="hidden" class="sale_design_id" name="order[design][]" value="'+est_order_row.find('td:eq(2) .est_design_id').val()+'" /></td><td><span>'+est_order_row.find('td:eq(3) .est_piece').html()+'</span><input type="hidden" class="sale_pcs" name="order[pcs][]" value="'+est_order_row.find('td:eq(3) .est_pcs').val()+'"  /></td><td><span>'+est_order_row.find('td:eq(6) .est_gross_wt').html()+'</span><input type="hidden" class="bill_gross_val" name="order[gross][]" value="'+est_order_row.find('td:eq(6) .est_gross_val').val()+'" /></td><td><span>'+est_order_row.find('td:eq(7) .est_less_wt').html()+'</span><input type="hidden" class="bill_less_val" name="order[less][]" value="'+est_order_row.find('td:eq(7) .est_less_val').val()+'" /></td><td><span>'+est_order_row.find('td:eq(8) .est_net_wt').html()+'</span><input type="hidden" class="bill_net_val" name="order[net][]" value="'+est_order_row.find('td:eq(8) .est_net_val').val()+'" /></td><td><span>'+est_order_row.find('td:eq(9) .est_wastage').html()+'</span><input type="hidden" class="bill_wastage" name="order[wastage][]" value="'+est_order_row.find('td:eq(9) .est_wastage_percent').val()+'" /></td><td><span>'+est_order_row.find('td:eq(10) .est_mc').html()+'</span><input type="hidden" class="bill_mctype" name="order[bill_mctype][]" value="'+est_order_row.find('td:eq(10) .est_mc_type').val()+'" /><input type="hidden" class="bill_mc" name="order[mc][]" value="'+est_order_row.find('td:eq(10) .est_mc_value').val()+'" /></td><td><input type="number" class="bill_discount" name="order[discount][]" value="'+est_order_row.find('td:eq(11) .est_discount').val()+'" step="any" /></td><td></td><td><span>'+est_order_row.find('td:eq(12) .est_tgrp_name').html()+'</span><input type="hidden" class="sale_tax_group" name="order[taxgroup][]" value="'+est_order_row.find('td:eq(12) .est_tax_id').val()+'" /></td><td></td><td><input type="hidden" class="bill_stone_price" value="'+est_order_row.find('.est_stone_price').val()+'" /><input type="hidden" class="bill_material_price" value="'+est_order_row.find('.est_material_price').val()+'"/><input type="number" class="bill_amount" name="order[billamount][]" value="" step="any" readonly /><input type="hidden" class="per_grm_amount" name="order[per_grm][]" value="" step="any" /></td><td><span>'+est_order_row.find('td:first .order_no').val()+'</span><input type="hidden" class="order_no" name="order[order_no][]" value="'+est_order_row.find('td:first .order_no').val()+'" /></td></td><td><span>'+est_order_row.find('td:first .est_id').val()+'</span><input type="hidden" class="sale_est_itm_id" name="order[estid][]" value="'+est_order_row.find('td:first .est_itm_id').val()+'" /></td><td><a href="#" onClick="remove_row($(this).closest(\'tr\'));" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';

		}

		$('#billing_order_adv_details tbody').append(row);

	} 

}

function updateorderAdvance_sale_Bill(data)

{

    console.log(data);

	rowExist = false;

	var row="";

	$(".order_adv_details").show();

		$.each(data,function(key,item){

		        $('#id_customerorder').val(item.id_customerorder);

		        $('#filter_order_no').val(item.order_no);

			row+= '<tr id='+key+'>'

					+'<td><span>'+item.hsn_code+'</span><input type="hidden" class="sale_pro_hsn" name="order[hsn]" value="'+item.hsn_code+'" /><input type="hidden" class="sale_type" name="order[sourcetype][]" value="1" /><input type="hidden" class="is_est_details" value="1" name="order[is_est_details][]" /><input type="hidden" class="sale_cal_type" name="order[calltype][]" value="'+item.calculation_based_on+'" /><input type="hidden" class="sale_metal_type" value="'+item.metal_type+'" /><input type="hidden" class="sale_purity" value="'+item.id_purity+'"  name="order[purity][]" /><input type="hidden" class="sale_size" value="'+item.size+'"  name="order[size][]" /><input type="hidden" class="total_tax" name="order[total_tax][]" /></td>'

					+'<td><span>'+item.product_name+'</span><input class="sale_product_id" type="hidden" name="order[product][]" value="'+item.id_product+'" /></td>'

					+'<td><span>'+item.design_name+'</span><input type="hidden" class="sale_design_id" name="order[design][]" value="'+item.design_no+'" /></td>'

					+'<td><span>'+item.totalitems+'</span><input type="hidden" class="sale_pcs" name="order[pcs][]" value="'+item.totalitems+'"  /></td>'

					+'<td><span>'+item.gross_wt+'</span><input type="hidden" class="bill_gross_val" name="order[gross][]" value="'+item.gross_wt+'" /></td>'

					+'<td><span></span><input type="hidden" class="bill_less_val" name="order[less][]" value="" /></td>'

					+'<td><span>'+item.net_wt+'</span><input type="hidden" class="bill_net_val" name="order[net][]" value="'+item.net_wt+'" /></td>'

					+'<td><span>'+item.wast_percent+'</span><input type="hidden" class="bill_wastage" name="order[wastage][]" value="'+item.wast_percent+'" /><input type="hidden" class="wast_wgt"></td>'

					+'<td><span>'+item.mc+'</span><input type="hidden" class="bill_mctype" name="order[bill_mctype][]" value="" /><input type="hidden" class="bill_mc" name="order[mc][]" value="'+item.mc+'" /></td>'

					+'<td><span>'+item.tgrp_name+'</span><input type="hidden" class="sale_tax_group" name="order[taxgroup][]" value="'+item.tax_group_id+'" /><input type="hidden" class="sale_cgst" name="order[cgst][]" value="" /><input type="hidden" class="sale_sgst" name="order[sgst][]" value="" /><input type="hidden" class="sale_igst" name="order[igst][]" value="" /></td>'

					+'<td><span>'+item.order_no+'</span><input type="hidden" class="order_no" name="order[order_no][]" value="'+item.order_no+'" /></td>'

					+'<td><a href="#" onClick="remove_orderAdv_row($(this).closest(\'tr\'));" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'

					+'</tr>';

		});

		$('#billing_order_adv_details tbody').append(row);

		calculateOrderAdvBillRowTotal();

}

function updateorderAdvance_purchase_Bill(data)

{

	var row="";

	$(".purchase_details").show();

	$.each(data,function(key,old_est_sale_row){

            var stone_details=[];

            var other_stone_wt=0;

            var other_stone_price=0;

            $.each(old_est_sale_row.stone_details,function(key,item){

                stone_details.push({'est_old_metal_stone_id':item.est_old_metal_stone_id,'stone_id' : item.stone_id,'stone_pcs':item.pieces,'stone_wt':item.wt,'stone_price':item.price});

                other_stone_wt+=parseFloat(item.wt);

                other_stone_price+=parseFloat(item.price);

            });

			row += '<tr id='+key+'>'

				      +'<td><span>'+(old_est_sale_row.purpose== 1 ? "Cash" : "Exchange")+'</span></td>'

					  +'<td><span>'+old_est_sale_row.old_metal_sale_id+' - '+(old_est_sale_row.purpose == 1 ? "Cash" : "Exchange")+'</span><input type="hidden" class="is_est_details" value="1" name="purchase[is_est_details][]" /><input type="hidden" name="purchase[est_old_itm_id][]" class="est_itm_id" value="'+old_est_sale_row.old_metal_sale_id+'" /><input type="hidden" class="item_type" name="purchase[itemtype][]" value="2" /><input type="hidden" class="pur_metal_type" value="'+old_est_sale_row.id_category+'" name="purchase[metal_type][]" /></td>'

					  +'<td>-</td>'

					  +'<td><input type="number" class="pur_pcs" name="purchase[pcs][]" value="1" /></td>'

					  +'<td><span>'+old_est_sale_row.gross_wt+'</span><input type="hidden" class="pur_gross_val" name="purchase[gross][]" value="'+old_est_sale_row.gross_wt+'" /></td>'

					  +'<td><span>'+old_est_sale_row.less_wt+'</span><input type="hidden" class="pur_less_val" name="purchase[less][]" value="'+old_est_sale_row.less_wt+'" /></td>'

					  +'<td><span>'+old_est_sale_row.net_wt+'</span><input type="hidden" class="pur_net_val" name="purchase[net][]" value="'+old_est_sale_row.net_wt+'" /><input type="hidden" class="pur_stone_wt_val" name="purchase[stone_wt][]" value="'+old_est_sale_row.stone_wt+'" /><input type="hidden" class="pur_dust_wt_val" name="purchase[dust_wt][]" value="'+old_est_sale_row.dust_wt+'" /></td>'

					  +'<td><span>'+old_est_sale_row.wastage_percent+'</span><input type="hidden" class="pur_wastage" name="purchase[wastage][]" value="'+old_est_sale_row.wastage_percent+'" /></td>'

					  +'<td><span class="wastage_wt"></span><input type="hidden" class="pur_wastage_wt" name="purchase[wastage_wt][]" value="" /></td>'

					  +'<td><input type="number" class="pur_discount" name="purchase[discount][]" value="" /></td>'

					  +'<td><a href="#" onClick="create_new_empty_bill_purchase_stone_item($(this).closest(\'tr\'));" class="btn btn-success"><i class="fa fa-plus"></i></a><input type="hidden" class="stone_details" value='+(JSON.stringify(stone_details))+' name="purchase[stone_details][]"/><input type="hidden" class="other_stone_price" value="'+other_stone_price+'" /><input type="hidden" class="other_stone_wt" value="'+other_stone_wt+'" /><input type="hidden" class="est_old_dust_val" value="'+old_est_sale_row.dust_wt+'" /><input type="hidden" class="est_old_stone_val" value="'+old_est_sale_row.stone_wt+'" /><input type="hidden" class="bill_material_price" value="0"/></td>'

					  +'<td><input type="number" class="bill_amount" name="purchase[billamount][]" value="'+old_est_sale_row.amount+'" step="any" readonly /><input type="hidden" class="bill_rate_per_grm" name="purchase[rate_per_grm][]" value="'+old_est_sale_row.rate_per_gram+'" step="any" readonly /></td>'

					  +'<td><span>'+old_est_sale_row.est_id+'</span><input type="hidden" class="pur_est_id" name="purchase[estid]" value="'+old_est_sale_row.est_id+'" /></td>'

					  +'<td><a href="#" onClick="remove_orderAdv_row($(this).closest(\'tr\'));" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'

					  +'</tr>';

		});

	$('#purchase_item_details tbody').append(row);

	calculatePurchaseBillRowTotal();

}

function calculateOrderAdvBillRowTotal(){  

	$('#billing_order_adv_details > tbody tr').each(function(idx, row){

		curRow = $(this);

		var paid_wt = (isNaN($('.summary_adv_paid_wt').html()) || $('.summary_adv_paid_wt').html() == '')  ? 0 : $('.summary_adv_paid_wt').html();

		var gross_wt = (isNaN(curRow.find('td:eq(4) .bill_gross_val').val()) || curRow.find('td:eq(4) .bill_gross_val').val() == '')  ? 0 : curRow.find('td:eq(4) .bill_gross_val').val();

		var less_wt  = (isNaN(curRow.find('td:eq(5) .bill_less_val').val()) || curRow.find('td:eq(5) .bill_less_val').val() == '')  ? 0 : curRow.find('td:eq(5) .bill_less_val').val();

		var net_wt = parseFloat(gross_wt) - parseFloat(less_wt)-parseFloat(paid_wt);

		var calculation_type = (isNaN(curRow.find('td:eq(0) .sale_cal_type').val()) || curRow.find('td:eq(0) .sale_cal_type').val() == '')  ? 0 : curRow.find('td:eq(0) .sale_cal_type').val();

		var stone_price  = (isNaN(curRow.find('.bill_stn_price').val()) || curRow.find('.bill_stn_price').val() == '')  ? 0 : curRow.find('.bill_stn_price').val(); 

		var material_price  = (isNaN(curRow.find('.bill_material_price').val()) || curRow.find('.bill_material_price').val() == '')  ? 0 : curRow.find('.bill_material_price').val();

		var total_price = 0;

		var rate_per_grm = 0;

		var base_value_amt=0;

		var arrived_value_amt=0;

		var arrived_value_tax=0;

		var base_value_tax=0;

		var total_tax_rate=0;

		var cus_state=$('#cus_state').val();

		var cmp_state=$('#cmp_state').val();

		var cgst=0;

		var igst=0;

		if(curRow.find('td:eq(0) .sale_metal_type').val() == 1){

		  rate_per_grm = (isNaN($('.per-grm-sale-value').html()) || $('.per-grm-sale-value').html() == '')  ? 0 : parseFloat($('.per-grm-sale-value').html());

		}else{

			 rate_per_grm = (isNaN($('.silver_per-grm-sale-value').html()) || $('.silver_per-grm-sale-value').html() == '')  ? 0 : parseFloat($('.silver_per-grm-sale-value').html());

		}

		var inclusive_tax_rate = 0;

		var total_tax = 0;

		var tax_group = curRow.find('td:eq(11) .sale_tax_group').val();

		var discount = (isNaN(curRow.find('td:eq(9) .bill_discount').val()) || curRow.find('td:eq(9) .bill_discount').val() == '')  ? 0 : curRow.find('td:eq(9) .bill_discount').val();

		var retail_max_mc = (isNaN(curRow.find('td:eq(8) .bill_mc').val()) || curRow.find('td:eq(8) .bill_mc').val() == '')  ? 0 : curRow.find('td:eq(8) .bill_mc').val();

		var tot_wastage = (isNaN(curRow.find('td:eq(7) .bill_wastage').val()) || curRow.find('td:eq(7) .bill_wastage').val() == '')  ? 0 : curRow.find('td:eq(7) .bill_wastage').val();

		if(calculation_type == 0){ 

			var wast_wgt      = parseFloat(parseFloat(gross_wt) * parseFloat(tot_wastage/100)).toFixed(3);

			if(curRow.find('.bill_mctype').val() != 3 ){

    			var mc_type       =  parseFloat(curRow.find('.bill_mctype').val() == 2 ? parseFloat(retail_max_mc * gross_wt ) : parseFloat(retail_max_mc * curRow.find('.sale_pcs').val()));

    			// Metal Rate + Stone + OM + Wastage + MC

    			rate_with_mc = parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(material_price));

			}else{

			    var mc_type       =  parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt)) * (retail_max_mc/100));

    			// Metal Rate + Stone + OM + Wastage + MC

    			rate_with_mc = parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(material_price));

			}

		}

		else if(calculation_type == 1){

			var wast_wgt      = parseFloat(parseFloat(net_wt) * parseFloat(tot_wastage/100)).toFixed(3);

			if(curRow.find('.bill_mctype').val() != 3 ){

    			var mc_type       =  parseFloat(curRow.find('.bill_mctype').val() == 2 ? parseFloat(retail_max_mc * net_wt ) : parseFloat(retail_max_mc * curRow.find('.sale_pcs').val()));

    			// Metal Rate + Stone + OM + Wastage + MC

    			rate_with_mc = parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(material_price));

			}else{

			    var mc_type       =  parseFloat(parseFloat(retail_max_mc * net_wt )  * curRow.find('.sale_pcs').val());

    			// Metal Rate + Stone + OM + Wastage + MC

    			rate_with_mc = parseFloat(parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt))) + parseFloat(mc_type)+parseFloat(stone_price)+parseFloat(material_price));

			}

		}

		else if(calculation_type == 2){ 

			var wast_wgt      = parseFloat(parseFloat(net_wt) * parseFloat(tot_wastage/100)).toFixed(3);

			if(curRow.find('.bill_mctype').val() != 3 ){

    			var mc_type       =  parseFloat(curRow.find('.bill_mctype').val() == 2 ? parseFloat(retail_max_mc * gross_wt ) : parseFloat(retail_max_mc * curRow.find('.sale_pcs').val()));

    			// Metal Rate + Stone + OM + Wastage + MC

    		    rate_with_mc = parseFloat((parseFloat(rate_per_grm) * (parseFloat(wast_wgt) + parseFloat(net_wt)))+ parseFloat(mc_type))+parseFloat(stone_price)+parseFloat(material_price);

    		    //console.log(rate_per_grm+' '+wast_wgt+' '+net_wt+' '+mc_type+' '+stone_price+' '+material_price);

			}else{

			    var mc_type       =  parseFloat(rate_per_grm * (parseFloat(wast_wgt) + parseFloat(net_wt)) * (retail_max_mc/100));

    			// Metal Rate + Stone + OM + Wastage + MC

    		    rate_with_mc = parseFloat((parseFloat(rate_per_grm) * (parseFloat(wast_wgt) + parseFloat(net_wt)))+ parseFloat(mc_type))+parseFloat(stone_price)+parseFloat(material_price);

			}

		}

		

		else if(calculation_type == 3 || calculation_type == 4){ 

        

            rate_with_mc  = parseFloat((isNaN(curRow.find('.bill_amount').val()) || curRow.find('.bill_amount').val() == '')  ? 0 : curRow.find('.bill_amount').val()); 

        

        } 

		/*if(calculation_type == 0){

			rate_with_mc = parseFloat((parseFloat(rate_per_grm * gross_wt) + parseFloat(retail_max_mc * gross_wt)) - discount);

		}else if(calculation_type == 1){

			rate_with_mc = parseFloat((parseFloat(rate_per_grm * net_wt) + parseFloat(retail_max_mc * net_wt)) - discount );

		}else if(calculation_type == 2){

			rate_with_mc = parseFloat((((parseFloat(rate_per_grm) * parseFloat(net_wt) + parseFloat(tot_wastage * net_wt))) + parseFloat(retail_max_mc * net_wt)) - discount);

		}*/

		rate_with_mc = rate_with_mc - discount;

		//total_tax = getTaxValueForItem(rate_with_mc, tax_group);

		//inclusive_tax_rate = parseFloat(rate_with_mc + parseFloat(total_tax)).toFixed(2);

		

		var base_value_tax=parseFloat(calculate_base_value_tax(rate_with_mc,tax_group)).toFixed(2);

		var base_value_amt=parseFloat(parseFloat(rate_with_mc)+parseFloat(base_value_tax)).toFixed(2);

		var arrived_value_tax=parseFloat(calculate_arrived_value_tax(base_value_amt,tax_group)).toFixed(2);

		var arrived_value_amt=parseFloat(parseFloat(base_value_amt)+parseFloat(arrived_value_tax)).toFixed(2);

		

		var total_tax_rate=parseFloat(parseFloat(base_value_tax)+parseFloat(arrived_value_tax)).toFixed(2);

		//total_tax = getTaxValueForItem(rate_with_mc, tax_group);

		inclusive_tax_rate = arrived_value_amt;

		if(cus_state==cmp_state)

		{

			cgst=parseFloat(parseFloat(total_tax_rate)/2).toFixed(3);

			sgst=parseFloat(parseFloat(total_tax_rate)/2).toFixed(3);

		}else{

			igst=total_tax_rate;

		}

		curRow.find('.wast_wgt').val(wast_wgt);

		console.log('calculation_type:'+calculation_type);

		console.log('rate_with_mc:'+rate_with_mc);

		console.log('wast_wgt:'+wast_wgt);

		console.log('mc_type:'+mc_type);

		console.log('retail_max_mc:'+retail_max_mc);

		console.log('inclusive_tax_rate:'+inclusive_tax_rate);

		console.log('total_tax:'+total_tax);

		console.log('tax_group:'+tax_group);

		

		console.log('rate_per_grm:'+rate_per_grm);

		console.log('------------');

	});

	calculate_order_advance_sale_details();

	//calculate_orderAdv_purchase_details();

	//calculate_orderAdv_sales_details();

}

//Chit Amount Starts

$(document).on('keyup','.scheme_account', function(e){

		var row = $(this).closest('tr'); 

		var acc_no = row.find(".scheme_account").val();

		getSearchAcc(acc_no, row);

});

function getSearchAcc(searchTxt, curRow){

	my_Date = new Date();

	var bill_cus_id=$('#bill_cus_id').val();

	$.ajax({

        url: base_url+'index.php/admin_ret_billing/get_scheme_accounts/?nocache=' + my_Date.getUTCSeconds(),             

        dataType: "json", 

        method: "POST", 

        data: {'searchTxt': searchTxt,'bill_cus_id':bill_cus_id}, 

        success: function (data) {

        	$.each(data, function(key, item){

				$('#estimation_chit_details > tbody tr').each(function(idx, row){

					if(item != undefined){

						if($(this).find('.scheme_account_id').val() == item.label){

							data.splice(key, 1);

						}

					}

				});

			});

			$( ".scheme_account" ).autocomplete(

			{

			    appendTo: "#chit-confirm-add",

				source: data,

				select: function(e, i)

				{ 

					e.preventDefault();

					var amount=0;

					var rate_per_gram=$('.per-grm-sale-value').html();

					/*if(i.item.scheme_type==1 || i.item.scheme_type==2)

					{

					    amount=parseFloat(parseFloat(i.item.closing_balance)*parseFloat(rate_per_gram)).toFixed(2);

					}

					else

					{

					    amount=i.item.closing_balance;

					}*/

					

					amount=parseFloat(i.item.closing_amount-i.item.closing_add_chgs).toFixed(2);

			    	if(i.item.scheme_type==0)

					{

					    if(i.item.total_installments!=i.item.paid_installments)

					    {

					        amount=parseFloat(amount-parseFloat(i.item.firstPayDisc_value*i.item.paid_installments));

					    }

					    

					}

					curRow.find(".scheme_account").val(i.item.label);

					curRow.find(".scheme_account_id").val(i.item.value);

					curRow.find(".sch").html(i.item.scheme_name);

					curRow.find(".chit_amount").html(amount);

					curRow.find(".chit_amt").val(amount);

					$("#mobile").val(i.item.mobile);

					calculateChit_Amount();

				},

				change: function (event, ui) {

					if (ui.item === null) {

						$(this).val('');

						curRow.find('.scheme_account').html('');

						curRow.find(".scheme_account_id").val("");

						curRow.find(".sch").html("");

						curRow.find(".chit_amount").html("");

						curRow.find(".chit_amt").val("");

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

				 minLength: 1,

			});

        }

     });

}

$('#add_newchit_util').on('click',function(){

		if(validateChitDetailRow()){

			$('#payment_modes > tbody >tr').each(function(bidx, brow){

				bill_chit_row = $(this);

				bill_chit_row.find('#tot_chit_amt').html($('.total_amount').html());

				bill_chit_row.find('#chit_details').val(chit_details.length>0 ? JSON.stringify(chit_details):'');

			});

			$('#chit-confirm-add').modal('toggle');

			calculatePaymentCost();

		}else{

			alert("Please fill required fields");

		}

});

$('#create_chit_details').on('click', function(){

		$("#chitUtilAlert span").remove();

		if(validateChitDetailRow()){

			create_new_empty_est_chit_row();

		}else{

			$("#chitUtilAlert").append("<span>Please fill all fields in current row.</span>");

			$('#chitUtilAlert span').delay(20000).fadeOut(500);

		}

	});

function validateChitDetailRow(){

	var row_validate = true;

	$('#chit-confirm-add .modal-body #estimation_chit_details > tbody  > tr').each(function(index, tr) {

		if($(this).find('.scheme_account_id').val() == "" || $(this).find('.chit_amt').val() == ""){

			row_validate = false;

		}

	});

	return row_validate;

}

function create_new_empty_est_chit_row()

{

	var row = "";

	row += '<tr><td><input class="scheme_account" type="number" style="width: 100px;"/><input type="hidden" class="scheme_account_id" name="chit_uti[scheme_account_id][]"></td><td><span class="sch"></span></td><td><span class="chit_amount"></span><input type="hidden" class="chit_amt" name="chit_uti[chit_amt][]"></td><td><a href="#" onClick="removeChit_row($(this).closest(\'tr\'));" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';

	$('#estimation_chit_details tbody').append(row);

	$('#estimation_chit_details > tbody').find('tr:last td:eq(0) .scheme_account_id').focus();

}

function removeChit_row(curRow)

{

	curRow.remove();

	calculateChit_Amount();

}

function calculateChit_Amount()

{

	var total_amount=0;

	chit_details=[];

	$('#chit-confirm-add .modal-body #estimation_chit_details > tbody  > tr').each(function(index, tr) {

		if($(this).find('.chit_amt').val() != ""){

		total_amount+=parseFloat($(this).find('.chit_amt').val());

		chit_details.push({'scheme_account_id':$(this).find('.scheme_account_id').val(),'chit_amt':$(this).find('.chit_amt').val()});

		}

	});

	$('.total_amount').html(parseFloat(total_amount).toFixed(2));

}

$('#send_otp').on('click',function(){

    my_Date = new Date();

    var mobile=$('#mobile').val();

    var send_resend=$('#send_resend').val();

	$.ajax({

			 url:base_url+ "index.php/admin_ret_billing/sendotp?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),

			 type:"POST",

			 data:{'mobile':mobile,'send_resend':send_resend},

			 dataType: "json", 

			 async:false,

			 	  success:function(data){

			 	  	if(data.status)

			 	  	{

			 	  	    $('#send_otp').prop('disabled',true);

			 	  		$('#user_otp').prop('disabled',false);

			 	  	    $("#otp_alert").html('<p style="color:green">'+data.msg+'</p>');

			 	  	}

			 	  	else

			 	  	{

			 	  	    $('#send_otp').prop('disabled',false);

			 	  		$('#user_otp').prop('disabled',true);

			 	  		$("#otp_alert").html('<p style="color:red">'+data.msg+'</p>');

			 	  	}

			 	  	setTimeout(function() {

						$('#send_otp').prop('disabled',false);

						$("#send_otp").attr('value', 'Resend OTP');

						$("#send_otp").html('Resend OTP');

						$('#send_resend').val(1);

					},60000);

                    setTimeout(function() {

                     $('#otp_alert').html('');

                    },3000);

				  },

		  });

})

$('#user_otp').on('keyup',function(){

    var user_otp=$('#user_otp').val();

    if(user_otp.length==6)

    {

        $('#user_otp').prop('disabled',true);

        my_Date = new Date();

        var mobile=$('#mobile').val();

    	$.ajax({

			 url:base_url+ "index.php/admin_ret_billing/update_otp?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),

			 type:"POST",

			 data:{'user_otp':user_otp},

			 dataType: "json", 

			 async:false,

			 	  success:function(data){

			 	  	if(data.status)

			 	  	{

			 	  	    $('#send_otp').prop('disabled',true);

			 	  		$('.modal-footer').css('display','block');

			 	  	    $("#otp_alert").html('<p style="color:green">'+data.msg+'</p>');

			 	  	    	setTimeout(function() {

            					$('#send_otp').prop('disabled',false);

            					$("#send_otp").attr('value', 'Send OTP');

            					$("#send_otp").html('Send OTP');

            					$('#send_resend').val(0);

            				},60000);

			 	  	}

			 	  	else

			 	  	{

			 	  	    $('#user_otp').val('');

			 	  	    $('#send_otp').prop('disabled',false);

			 	  		$('#user_otp').prop('disabled',false);

			 	  		$("#otp_alert").html('<p style="color:red">'+data.msg+'</p>');

			 	  	}

                    setTimeout(function() {

                     $('#otp_alert').html('');

                    },3000);

				  },

		  });

    }

    else

    {

        $('#user_otp').prop(false);

    }

})

//Chit Amount Ends

//gift voucher Starts



$(document).on('keyup','.voucher_no', function(e){

		var row = $(this).closest('tr'); 

		var voucher_no = row.find(".voucher_no").val();

		if(voucher_no.length>=3)

		{

		    getVoucherDetails(voucher_no, row);

		}

		

});

function getVoucherDetails(searchTxt, curRow){

	my_Date = new Date();

	var bill_cus_id=$('#bill_cus_id').val();

	$.ajax({

        url: base_url+'index.php/admin_ret_billing/getVoucherDetails/?nocache=' + my_Date.getUTCSeconds(),             

        dataType: "json", 

        method: "POST", 

        data: {'searchTxt': searchTxt,'bill_cus_id':bill_cus_id,'id_branch':$('#id_branch').val()}, 

        success: function (data) {

            if(data.status)

            {

                $.each(data.responseData, function(key, item){

    				$('#gift_voucher_details > tbody tr').each(function(idx, row){

    					if(item != undefined){

    						if($(this).find('.voucher_no').val() == item.label){

    							data.responseData.splice(key, 1);

    						}

    					}

    				});

    			});

    			$( ".voucher_no" ).autocomplete(

    			{

    			    appendTo: "#gv-confirm-add",

    				source: data.responseData,

    				select: function(e, i)

    				{ 

    					e.preventDefault();

    					var allow_redeem=false;

    					if(i.item.status==3)

    					{

    					    alert('Voucher Expired..');

    					    allow_redeem=false;

    					}

    					else if(i.item.status==2)

    					{

    					    alert('Already Voucher Redeemed..');

    					    allow_redeem=false;

    					}

    					else if(i.item.status==5)

    					{

    					    alert('Voucher Cancelled..');

    					    allow_redeem=false;

    					}

    					else if(i.item.id_branch!='')

    					{

    					    if(i.item.id_branch!=$("#id_branch").val())

    					    {

    					        alert('Invalid Branch..');

    					        allow_redeem=false;

    					    }else{

    					        allow_redeem=true;

    					    }

    					}else{

    					    allow_redeem=true;

    					}

    					

    				    if(allow_redeem)

    				    {   

    				            var gift_voucher_amt=0;

    				        	var redeem_details=i.item.gift_redeem_det;

    				        	if(i.item.free_card!=2)

    				        	{

    				        	   redeem_status=gift_voucher_redeem(i.item.bill_id,redeem_details.utilize_for,redeem_details.gift_type,redeem_details.sale_value,i.item.amount,redeem_details.voucher_type,redeem_details.id_set_gift_voucher,redeem_details.id_gift_voucher); 

    				        	}else{

    				        	    redeem_status=true;

    				        	}

            					

            				   

            					if(redeem_status)

            					{

            					    curRow.find(".voucher_no").val(i.item.label);

                					curRow.find(".id_gift_card").val(i.item.id_gift_card);

                					

                					if(redeem_details.voucher_type==2)

                					{

                					    if(redeem_details.utilize_for==0 || redeem_details.utilize_for==1)

                					    {

                					        gift_voucher_amt=parseFloat(i.item.amount*$('.per-grm-sale-value').html()).toFixed(3);

                					    }else{

                					        gift_voucher_amt=parseFloat(i.item.amount*$('.silver_per-grm-sale-value').html()).toFixed(3);

                					    }

                					}

                					else if(i.item.id_set_gift_voucher!='')

                					{

                					    if(redeem_details.gift_type==2 || redeem_details.gift_type==4)

                					    {

                					        if(redeem_details.utilize_for==1)

                					        {

                					            

                					           gift_voucher_amt=parseFloat(i.item.weight*$('.per-grm-sale-value').html()).toFixed(3);

                					        }else{

                					            gift_voucher_amt=parseFloat(i.item.weight*$('.silver_per-grm-sale-value').html()).toFixed(3);

                					        }

                					    }else{

                					         gift_voucher_amt=i.item.amount;

                					    }

                					   

                					   if(parseFloat(gift_voucher_amt)>parseFloat(redeem_sales_amt))

                    					{

                    					     curRow.find(".voucher_no").val('');

                					         curRow.find(".gift_voucher_amt").val('');

                    					     alert('Your Purchase Amount is Less Than the Voucher Amount..');

                    					     redeem_sales_amt=0;

                    					}else{

                    					      curRow.find(".gift_voucher_amt").val(gift_voucher_amt);

                    					      redeem_sales_amt=0;

                    					}

                					}

                					else

                					{

                					   gift_voucher_amt=i.item.amount;

                					    

                					     if(parseFloat(redeem_details.sale_value)> parseFloat($('.receive_amount').val()))

                					    {

                					        curRow.find(".voucher_no").val('');

                					        curRow.find(".gift_voucher_amt").val('');

                    					    alert('Your Purchase Amount is Less Than the Voucher Amount..');

                					    }else{

                					         curRow.find(".gift_voucher_amt").val(gift_voucher_amt);

                					    }

                					}



            					}else{

            					    curRow.find(".voucher_no").val('');

            					    curRow.find(".gift_voucher_amt").val('');

            					}

    				    }else{

    				          curRow.find(".voucher_no").val('');

            				  curRow.find(".gift_voucher_amt").val('');

    				    }

    				    calculateGiftVoucher_Amount();

    				    

    				},

    				change: function (event, ui) {

    					if (ui.item === null) {

    						$(this).val('');

    						curRow.find(".voucher_no").val("");

    						curRow.find(".id_gift_card").val("");

    					    curRow.find(".gift_voucher_amt").val("");

    					}

    			    },

    				response: function(e, i) {

    		            // ui.content is the array that's about to be sent to the response callback.

    		            if(searchTxt != ""){

    						if (i.content.length === 0) {

    						   $("#customerAlert").html('<p style="color:red">Enter a valid Voucher No</p>');

    						}else{

    						   $("#customerAlert").html('');

    						} 

    					}else{

    					}

    		        },

    				 minLength: 1,

    			});

            }else{

                curRow.find(".voucher_no").val("");

    			curRow.find(".id_gift_card").val("");

    		    curRow.find(".gift_voucher_amt").val("");

                alert(data.message);

            }

        }

     });

}



function validateVoucherDetailRow(){

	var row_validate = true;

	$('#gift_voucher_details > tbody  > tr').each(function(index, tr) {

		if($(this).find('td:first .voucher_no').val() == "" || $(this).find('td:eq(2) .gift_voucher_amt').val() == ""){

			row_validate = false;

		}

	});

	return row_validate;

}

function create_new_empty_est_voucher_row()

{

	var row = "";

	row += '<tr><td><input class="voucher_no" type="text" style="width: 100px;" name="gift_voucher[voucher_no][]" value="" /><input type="hidden" class="id_gift_card"></td><td><input type="number" style="width: 100px;" class="gift_voucher_amt" name="gift_voucher[gift_voucher_amt][]" value=""  readonly/></td><td><a href="#" onClick="removeGift_voucher($(this).closest(\'tr\'));" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';

	$('#gift_voucher_details tbody').append(row);

	$('#gift_voucher_details > tbody').find('tr:last td:eq(0) .voucher_no').focus();

}

$('#create_gift_voucher_details').on('click', function(){

		$("#voucherAlert span").remove();

		if(validateVoucherDetailRow()){

			create_new_empty_est_voucher_row();

		}else{

			$("#voucherAlert").append("<span>Please fill all fields in current row.</span>");

			$('#voucherAlert span').delay(20000).fadeOut(500);

		}

	});

$('#add_newvoucher').on('click',function(){

		if(validateVoucherDetailRow()){

			$('#payment_modes > tbody >tr').each(function(bidx, brow){

				bill_gift_voucher = $(this);

				bill_gift_voucher.find('#tot_voucher_amt').html($('.gift_total_amount').html());

				bill_gift_voucher.find('#giftVoucher_details').val(giftVoucher_details.length>0 ? JSON.stringify(giftVoucher_details):'');

			});

			$('#gv-confirm-add').modal('toggle');

			calculatePaymentCost();

		}else{

			alert("Please fill required fields");

		}

});

$(document).on('keyup', '.gift_voucher_amt', function(e){

		if(e.which === 13)

		{

			e.preventDefault();

			if(validateVoucherDetailRow()){

				create_new_empty_est_voucher_row();

			}else{

				alert("Please fill required fields");

			}

		}

		calculateGiftVoucher_Amount();

	});

function removeGift_voucher(curRow)

{

	curRow.remove();

	calculateGiftVoucher_Amount();

}

function calculateGiftVoucher_Amount()

{

	var total_amount=0;

	giftVoucher_details=[];

	$('#gv-confirm-add .modal-body #gift_voucher_details > tbody  > tr').each(function(index, tr) {

				if($(this).find('.gift_voucher_amt').val() != ""){

					total_amount+=parseFloat($(this).find('.gift_voucher_amt').val());

					giftVoucher_details.push({'voucher_no':$(this).find('.voucher_no').val(),'gift_voucher_amt':$(this).find('.gift_voucher_amt').val(),'id_gift_card':$(this).find('.id_gift_card').val()});

				}

		});

		$('.gift_total_amount').html(parseFloat(total_amount).toFixed(2));

}

//gift voucher Ends

//Credit card starts

$('#new_card').on('click', function(){

	$("#cardPayAlert span").remove();

	if(validateCardDetailRow()){

		create_new_empty_cardpay_row();

	}else{

		$("#cardPayAlert").append("<span>Please fill all fields in current row.</span>");

		$('#cardPayAlert span').delay(20000).fadeOut(500);

	}

});

function validateCardDetailRow(){

	var row_validate = true;

	$('#card_details > tbody  > tr').each(function(index, tr) {

		if($(this).find('.card_name').val() == "" || $(this).find('.card_type').val() == "" || $(this).find('.card_no').val() == "" || $(this).find('.card_amt').val() == ""){

			row_validate = false;

		}

	});

	return row_validate;

}

function create_new_empty_cardpay_row()

{

	var row = "";

	row += '<tr>'

				+'<td><select name="card_details[card_name][]" class="card_name"><option value="1">RuPay</option><option value="2">VISA</option><option value="3">Mastro</option><option value="4">Master</option></select></td>'

				+'<td><select name="card_details[card_type][]" class="card_type"><option value="1">CC</option><option value="2">DC</option></select></td>'

				+'<td><input type="number" step="any" class="card_no" name="card_details[card_no][]"/></td>'

				+'<td><input type="number" step="any" class="card_amt" name="card_details[card_amt][]"/></td>' 

				+'<td><a href="#" onClick="removeCC_row($(this).closest(\'tr\'));" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>' 

			+'</tr>';

	$('#card_details tbody').append(row);

}

$(document).on('keyup', '.card_amt', function(e){

		if(e.which === 13)

		{

			e.preventDefault();

			if(validateCardDetailRow()){

				create_new_empty_cardpay_row();

			}else{

				alert("Please fill required fields");

			}

		}

		calculate_creditCard_Amount();

	});

function removeCC_row(curRow)

{

	curRow.remove();

	calculate_creditCard_Amount();

}

function calculate_creditCard_Amount()

{

	var total_amount=0;

	var cc_amount=0;

	var dc_amount=0;

	card_payment=[];

	$('#card-detail-modal .modal-body #card_details > tbody  > tr').each(function(index, tr) {

				if($(this).find('.card_amt').val() != ""){

					if($(this).find('.card_type').val()==1)

					{

						cc_amount+=parseFloat($(this).find('.card_amt').val());

					}

					else if($(this).find('.card_type').val()==2)

					{

						dc_amount+=parseFloat($(this).find('.card_amt').val());

					}

					card_payment.push({'card_name':$(this).find('.card_name').val(),'card_type':$(this).find('.card_type').val(),'card_no':$(this).find('.card_no').val(),'card_amt':$(this).find('.card_amt').val()});

				}

		});

		$('.cc_total_amt').html(parseFloat(cc_amount).toFixed(2));

		$('.dc_total_amt').html(parseFloat(dc_amount).toFixed(2));

		$('.cc_total_amount').html(parseFloat(parseFloat(cc_amount)+parseFloat(dc_amount)).toFixed(2));

}

$('#add_newcc').on('click',function(){

		if(validateCardDetailRow()){

			$('#payment_modes > tbody >tr').each(function(bidx, brow){

				bill_card_pay_row = $(this);

				bill_card_pay_row.find('.CC').html($('.cc_total_amt').html());

				bill_card_pay_row.find('.DC').html($('.dc_total_amt').html());

				bill_card_pay_row.find('#card_payment').val(card_payment.length>0 ? JSON.stringify(card_payment):'');

			});

			$('#card-detail-modal').modal('toggle');

			calculatePaymentCost();

		}else{

			alert("Please fill required fields");

		}

});

//Credit card ends

//Chque starts

$('#new_chq').on('click', function(){

	$("#chqPayAlert span").remove();

	if(validateChqDetailRow()){

		create_new_empty_chqpay_row();

	}else{

		$("#chqPayAlert").append("<span>Please fill all fields in current row.</span>");

		$('#chqPayAlert span').delay(20000).fadeOut(500);

	}

});

function validateChqDetailRow(){

	var row_validate = true;

	$('#chq_details > tbody  > tr').each(function(index, tr) {

		if($(this).find('.bank_name').val() == "" || $(this).find('.bank_branch').val() == "" || $(this).find('.cheque_no').val() == "" || $(this).find('.payment_amount').val() == ""){

			row_validate = false;

		}

	});

	return row_validate;

}

function create_new_empty_chqpay_row()

{

	var row = "";

	row += '<tr>'

				+'<td><input class="cheque_date" data-date-format="dd-mm-yyyy hh:mm:ss" name="cheque_details[cheque_date][]" type="text" placeholder="Cheque Date" /></td>'

				+'<td><input name="cheque_details[bank_name][]" type="text" class="bank_name"></td>'

				+'<td><input name="cheque_details[bank_branch][]" type="text" class="bank_branch"></td>'

				+'<td><input type="number" step="any" class="cheque_no" name="cheque_details[cheque_no][]"/></td>' 

				+'<td><input type="text" step="any" class="bank_IFSC" name="cheque_details[bank_IFSC][]"/></td>'

				+'<td><input type="number" step="any" class="payment_amount" name="cheque_details[payment_amount][]"/></td>' 

				+'<td><a href="#" onClick="removeChq_row($(this).closest(\'tr\'));" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'

			+'</tr>';

	$('#chq_details tbody').append(row);

	$('#chq_details > tbody').find('tr:last .cheque_date').focus();

}

$(document).on('keyup', '.payment_amount', function(e){

		if(e.which === 13)

		{

			e.preventDefault();

			if(validateChqDetailRow()){

				create_new_empty_chqpay_row();

			}else{

				alert("Please fill required fields");

			}

		}

		calculate_chq_Amount();

	});

function removeChq_row(curRow)

{

	curRow.remove();

	calculate_chq_Amount();

}

function calculate_chq_Amount()

{

	var total_amount=0;

	var chq_amount=0;

	chq_payment=[];

	$('#cheque-detail-modal .modal-body #chq_details > tbody  > tr').each(function(index, tr) {

				if($(this).find('.payment_amount').val() != ""){

				    chq_amount+=parseFloat($(this).find('.payment_amount').val());

					chq_payment.push({'cheque_date':$(this).find('.cheque_date').val(),'cheque_no':$(this).find('.cheque_no').val(),'bank_branch':$(this).find('.bank_branch').val(),'bank_name':$(this).find('.bank_name').val(),'payment_amount':$(this).find('.payment_amount').val()});

				}

		});

		$('.chq_total_amount').html(parseFloat(chq_amount).toFixed(2));

}

$('#add_newchq').on('click',function(){

		if(validateChqDetailRow()){

			$('#payment_modes > tbody >tr').each(function(bidx, brow){

				bill_card_pay_row = $(this);

				bill_card_pay_row.find('.CHQ').html($('.chq_total_amount').html());

				bill_card_pay_row.find('#chq_payment').val(chq_payment.length>0 ? JSON.stringify(chq_payment):'');

			});

			$('#cheque-detail-modal').modal('toggle');

			calculatePaymentCost();

		}else{

			alert("Please fill required fields");

		}

});

$(document).on('focus', '.cheque_date', function(e){

        var row = $(this).closest('tr');

		row.find('.cheque_date').datetimepicker(

    	{ 

    		format: 'dd-mm-yyyy H:m:s'

    	});

	});

//Cheque ends

//Net banking starts

$('#new_net_bank').on('click', function(){

	$("#NetBankAlert span").remove();

	if(validateNBDetailRow()){

		create_new_empty_net_banking_row();

	}else{

		$("#NetBankAlert").append("<span>Please fill all fields in current row.</span>");

		$('#NetBankAlert span').delay(20000).fadeOut(500);

	}

});

function validateNBDetailRow(){

	var row_validate = true;

	$('#net_bank_details > tbody  > tr').each(function(index, tr) {

		if($(this).find('.nb_type').val() == "" || $(this).find('.ref_no').val() == "" || $(this).find('.amount').val() == ""){

			row_validate = false;

		}

	});

	return row_validate;

}

function create_new_empty_net_banking_row()

{

	var row = "";

	row += '<tr>'

			+'<td><select name="nb_details[nb_type][]" class="nb_type"><option value=1>RTGS</option><option value=2>IMPS</option><option value=3>UPI</option></select></td>'

			+'<td><input type="number" step="any" class="text" name="nb_details[ref_no][]"/></td>'

			+'<td><input type="number" step="any" class="amount" name="nb_details[amount][]"/></td>'

			+'<td><a href="#" onClick="removeNb_row($(this).closest(\'tr\'));" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'

			+'</tr>';

	$('#net_bank_details tbody').append(row);

	$('#net_bank_details > tbody').find('tr:last .cheque_date').focus();

}

$(document).on('keyup', '.amount', function(e){

		if(e.which === 13)

		{

			e.preventDefault();

			if(validateNBDetailRow()){

				create_new_empty_net_banking_row();

			}else{

				alert("Please fill required fields");

			}

		}

		calculate_NB_Amount();

	});

function removeNb_row(curRow)

{

	curRow.remove();

	calculate_NB_Amount();

}

function calculate_NB_Amount()

{

	var total_amount=0;

	var nb_amount=0;

	nb_payment=[];

	$('#net_banking_modal .modal-body #net_bank_details > tbody  > tr').each(function(index, tr) {

				if($(this).find('.amount').val() != ""){

				    nb_amount+=parseFloat($(this).find('.amount').val());

					nb_payment.push({'nb_type':$(this).find('.nb_type').val(),'ref_no':$(this).find('.ref_no').val(),'amount':$(this).find('.amount').val()});

				}

		});

		$('.nb_total_amount').html(parseFloat(nb_amount).toFixed(2));

}

$('#add_newnb').on('click',function(){

		if(validateNBDetailRow()){

			$('#payment_modes > tbody >tr').each(function(bidx, brow){

				bill_card_pay_row = $(this);

				bill_card_pay_row.find('.NB').html($('.nb_total_amount').html());

				bill_card_pay_row.find('#nb_payment').val(nb_payment.length>0 ? JSON.stringify(nb_payment):'');

			});

			$('#net_banking_modal').modal('toggle');

			calculatePaymentCost();

		}else{

			alert("Please fill required fields");

		}

});

//Net banking ends

//Advance starts

function get_advance_details()

{

	$('#bill_adv_adj > tbody').empty();

	var bill_cus_id=$('#bill_cus_id').val();

	my_Date = new Date();

	$.ajax({

        url: base_url+'index.php/admin_ret_billing/get_advance_details/?nocache=' + my_Date.getUTCSeconds(),             

        dataType: "json", 

        method: "POST", 

        data: {'bill_cus_id':bill_cus_id}, 

        success: function (data) {

            if(data.id_ret_wallet>0)

            {

            $('#adv-adj-confirm-add').modal('show');

        	var row="";

        	var html='';

        	var metal_rate=$('.per-grm-sale-value').html();

        	var weight_amt=parseFloat(data.weight*data.rate_per_gram);

        	$('#id_ret_wallet').val(data.id_ret_wallet);

        	html='<tr>'

        			+'<td><input type="checkbox" class="wallet_amt"  name="adv_adj[amount]"></td>'

        			+'<td>Amount</td>'

        			+'<td class="adj_amount" name="adv_adj[adj_amount]">'+(data.amount!=undefined ?data.amount :'')+'</td>'

        			+'<td class="bill_amt"></td>'

        		 +'</tr>'

        		 +'<tr>'

        			+'<td><input type="checkbox" class="wallet_wt"  name="adv_adj[weight]"></td>'

        			+'<td>Weight</td>'

        			+'<td>'+(data.weight!=undefined ?data.weight+'g @'+weight_amt :'')+'<input type="hidden" class="weight_amt" value='+weight_amt+'><input type="hidden" class="weight" value='+data.weight+'><input type="hidden" class="rate_per_gram" value='+data.rate_per_gram+'></td>'

        			+'<td class="bill_wt"></td>'

        		 +'</tr>';

        		 $('#bill_adv_adj > tbody').append(html);

  	        }else

  	        {

  	            alert('Your Wallet Amount is 0');

  	        }

	        }

     });

}

$(document).on('change',".wallet_amt,.wallet_wt", function(e){ 

	var total_amount=0;

	var wallet_wt=0;

	var weight_amt=0;

	var excess_amt=0;

	var rate_per_gram=0;

	adv_adj_details=[];

	var metal_rate=$('.per-grm-sale-value').html();

	var total_bill_amt=$('.receive_amount').val();

	var wallet_blc=0;

	$('#tot_bill_amt').html('');

	$('#excess_amt').html('');

	var store_receipt_as=$("input:radio[name='store_receipt_as']:checked").val();

  	$('#bill_adv_adj > tbody >tr').each(function(bidx, brow){

		adv_adj_row = $(this);

		if($(adv_adj_row).find("input[name='adv_adj[amount]']:checked").is(":checked")){

			total_amount=parseFloat($(this).find('.adj_amount').html());

		}

		if($(adv_adj_row).find("input[name='adv_adj[weight]']:checked").is(":checked")){

			weight_amt=parseFloat($(this).find('.weight_amt').val());

			wallet_wt=parseFloat($(this).find('.weight').val());

			rate_per_gram=parseFloat($(this).find('.rate_per_gram').val());

		}

	});

	$('.bill_amt').html(parseFloat(total_amount).toFixed(2));

	$('.bill_wt').html(parseFloat(weight_amt).toFixed(2));

	$('.tot_bill_amt').html(parseFloat(total_bill_amt).toFixed(2));

	$('.adv_adj_amt').html(Math.round(parseFloat(total_amount+weight_amt).toFixed(2)));

	$('.tot_adj_amt').html(parseFloat(total_amount+weight_amt).toFixed(2));

	if(total_bill_amt<(weight_amt+total_amount))

	{

		 excess_amt=parseFloat((total_bill_amt-(total_amount+weight_amt))*-1).toFixed(2);

	}

	$('.excess_amt').html(excess_amt);

	$('.adjusted_amt').html(parseFloat($('.adv_adj_amt').html()-excess_amt).toFixed(2));

	if(store_receipt_as==1)

	{

		wallet_blc=excess_amt;

	}else{

		wallet_blc=parseFloat(excess_amt/metal_rate).toFixed(4);

	}

	adv_adj_details.push({'adjusted_amt':$('.adjusted_amt').html(),'wallet_amt':total_amount,'wallet_wt':wallet_wt,'wallet_blc':wallet_blc,'store_receipt_as':store_receipt_as,'id_ret_wallet':$('#id_ret_wallet').val(),'rate_per_gram':rate_per_gram});

	console.log(adv_adj_details);

});

$('input[type=radio][name="receipt[receipt_as]"]').change(function(){

	if(this.value==1)

	{

		$('#esti_no').prop('disabled',true);

	}else{

		$('#esti_no').prop('disabled',false);

	}

});

$('input[type=radio][name="store_receipt_as"]').change(function() {

	var metal_rate=$('.per-grm-sale-value').html();

	if(adv_adj_details.length>0)

	{

		adv_adj_details[0].store_receipt_as=this.value;

		if(this.value==1)

		{

			adv_adj_details[0].wallet_blc=$('.excess_amt').html();

		}else{

			adv_adj_details[0].wallet_blc=parseFloat($('.excess_amt').html()/metal_rate).toFixed(4);

		}

	}

	console.log(adv_adj_details);

});

$('#add_adv_adj').on('click',function(e){

	$('#payment_modes > tbody >tr').each(function(bidx, brow){

		$(this).find('#tot_adv_adj').html($('.adv_adj_amt').html());

		$(this).find('#adv_adj_details').val(adv_adj_details.length>0 ? JSON.stringify(adv_adj_details):'');

	});

	$('#adv-adj-confirm-add').modal('toggle');

	$('#adv-adj-confirm-add .modal-body').find('#bill_adv_adj tbody').empty();

	$('#adv-adj-confirm-add .modal-body').find('#bill_adv_adj tfoot .adv_adj_amt').html('');

	calculatePaymentCost();

});

$('#close_add_adj').on('click',function(e){

    $('#adv-adj-confirm-add .modal-body').find('#bill_adv_adj tbody').empty();

});

function getCreditBillDetails(billNo, billType){

	$("#ret_bill_id").val("");

	my_Date = new Date();

	$.ajax({

        url: base_url+'index.php/admin_ret_billing/getCreditBillDetails/?nocache=' + my_Date.getUTCSeconds(),             

        dataType: "json", 

        method: "POST", 

        data: {'billNo' : billNo, 'billType' : billType, 'id_branch' : $("#id_branch").val(),'fin_year':$('#bill_fin_year_select').val()},

        success: function (data) { 

			if(data.success == true){

				var bill_details=data.responsedata.bill_details;

				

				var blc_amt=parseFloat(bill_details.tot_bill_amount)-(parseFloat(bill_details.tot_amt_received)+parseFloat(bill_details.credit_pay_amount));

				$('.summary_credit_amt').html(blc_amt);

				

				//$('.receive_amount').val(blc_amt);

				//$('#total_cost').val(blc_amt);

				

				$('#bill_cus_name').val(bill_details.cus_name);

				$('#bill_cus_id').val(bill_details.id_customer);

				$('#cus_village').html(bill_details.village_name);

			    /*$('#chit_cus').html(bill_details.accounts>0 ? 'Yes':'No');

			    $('#vip_cus').html(bill_details.vip);*/

			    $("#cus_info").append(bill_details.vip == 'Yes' ? "<span class='label bg-orange'><i class='fa fa-fw fa-star'></i> V I P</span>":"");

				$("#cus_info").append(bill_details.accounts > 0 ? "&nbsp;<span class='label label-info'>Chit Customer</span>":"");		

			    $('#ret_bill_id').val(bill_details.bill_id);

			    calculateFinalCost();

			}else{

				 $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+data.message});

			}

        }

     });

}

//Advance ends

function getBillDetails(billNo, billType){

	$("#ret_bill_id").val("");

	my_Date = new Date();

	$.ajax({

        url: base_url+'index.php/admin_ret_billing/getBillDetails/?nocache=' + my_Date.getUTCSeconds(),             

        dataType: "json", 

        method: "POST", 

        data: {'billNo' : billNo, 'billType' : billType, 'id_branch' : $("#id_branch").val(),'fin_year':$('#bill_fin_year_select').val()},

        success: function (data) { 

			if(data.success == true){

				$('#bill-detail-popup').modal('toggle');

				// BILL SOLD ITEMS

				if(data.responsedata.item_details.length > 0){

					$('#bill_items_tbl_for_return tbody').empty();

					$("#ret_bill_id").val(data.responsedata.item_details[0].bill_id);

					$.each(data.responsedata.item_details, function (estkey, estval) {

						var row = '<tr>'

									+'<td>'+(estval.status == 2 ? '<span style="color:red">Returned</span>':'<input type="checkbox" class="select_est_details" value="1" />')+'<input type="hidden" class="bill_id" value="'+estval.bill_id+'" /><input type="hidden" class="bill_det_id" value="'+estval.bill_det_id+'" /><input type="hidden" class="bill_det_id" value="'+estval.bill_det_id+'" /><input type="hidden" class="est_id" value="'+estval.esti_id+'" /><input type="hidden" class="order_no" value="'+estval.order_no+'" /><input type="hidden" class="est_itm_id" value="'+estval.esti_item_id+'" /><input type="hidden" class="est_tag_id" value="'+estval.tag_id+'" /><input type="hidden" class="est_hsn" value="'+estval.hsn_code+'" /><input type="hidden" class="est_item_type" value="'+estval.item_type+'" /><input type="hidden" class="est_cal_type" value="'+estval.calculation_based_on+'" /><input type="hidden" class="est_metal_type" value="'+estval.metal_type+'" /><input type="hidden" class="est_uom" value="'+estval.uom+'"  /></td>'

									+'<td><span class="est_product_name">'+estval.product_name+'</span><input class="est_product_id" type="hidden" value="'+estval.product_id+'" /></td>'

									+'<td><span class="est_design_code">'+estval.design_name+'</span><input type="hidden" class="est_design_id" value="'+estval.design_id+'"  /></td>'

									+'<td><span class="est_piece">'+estval.piece+'</span><input type="hidden" class="est_pcs" value="'+estval.piece+'"  /></td>'

									+'<td><span class="est_purname">'+estval.purname+'</span><input type="hidden" class="est_purid" value="'+estval.purname+'"  /></td>'

									+'<td><span class="est_size">'+estval.size+'</span><input type="hidden" class="est_size_val" value="'+estval.size+'"  /></td>'

									+'<td><span class="est_gross_wt">'+estval.gross_wt+'</span><input type="hidden" class="est_gross_val" value="'+estval.gross_wt+'"  /></td>'

									+'<td><span class="est_less_wt">'+estval.less_wt+'</span><input type="hidden" class="est_less_val" value="'+estval.less_wt+'"  /></td>'

									+'<td><span class="est_net_wt">'+estval.net_wt+'</span><input type="hidden" class="est_net_val" value="'+estval.net_wt+'"  /></td>'

									+'<td><span class="est_wastage">'+estval.wastage_percent+'</span><input type="hidden" class="est_wastage_percent" value="'+estval.wastage_percent+'"  /></td>'

									+'<td><span class="est_mc">'+estval.mc_value+' '+(estval.mc_type == 1 ? ' per gm' : ' per pc')+'</span><input type="hidden" class="est_mc_value" value="'+estval.mc_value+'"  /><input type="hidden" class="est_mc_type" value="'+estval.mc_type+'"  /></td>'

									+'<td><input type="hidden" class="est_discount" value="'+estval.discount+'"/>'+estval.discount+'</td>'

									+'<td><span class="est_tgrp_name">'+estval.tgrp_name+'</span><input type="hidden" class="est_tax_id" value="'+estval.tax_group_id+'" /><input type="hidden" class="est_tax_val" value="'+estval.item_total_tax+'" /></td><td>'+estval.item_total_tax+'</td>'

									+'<td><span class="est_item_cost">'+estval.item_cost+'</span><input type="hidden" class="est_material_price" value="'+estval.othermat_amount+'"  /><input type="hidden" class="est_stone_price" value="'+estval.stone_price+'"  /><input type="hidden" class="est_item_cost_val" value="'+estval.item_cost+'"  /></td>'

									+'<td>'+(estval.is_partial == 1 ?"<input type='checkbox' class='select_est_partial' value='1' />" : '-' )+'</td>'

									+'<td><span class="est_tag_no">'+estval.tag_id+'</span></td>'

									+'</tr>';

						$('#bill_items_tbl_for_return tbody').append(row);

					});

					$('#bill_items_for_return').show();

					calculateEsttoSaleConvertion();

				}

				else{ 

					$('#bill_items_tbl_for_return tbody').empty();

					if(billType != 4 && billType != 6){ 

						$('#bill_items_for_return').show();

					}else{

						$('#bill_items_for_return').hide();

					} 

				}

				// BILL PURCHASED ITEMS

				if(data.responsedata.old_matel_details.length > 0){

					$('#bill_old_items_purchased_tbl tbody').empty();

					$.each(data.responsedata.old_matel_details, function (estkey, estval) {

						/*<td><span class="est_old_item_pur">'+estval.purname+'</span><input type="hidden" class="est_old_item_purid" value="'+estval.purid+'"  /></td>*/ 

						var row = '<tr>'

									+'<td>'+estval.est_id+'/td>'

									+'<td><span>'+(estval.purpose == 1 ? "Cash" : "Exchange")+'</span><input type="hidden" class="est_purpose" value="'+estval.purpose+'" /></td>'

									+'<td><span class="est_old_item_metal">'+estval.metal+'</span><input type="hidden" class="est_old_item_cat_id" value="'+estval.id_category+'"  /></td>'

									+'<td><span class="est_old_itm_gross_wt">'+estval.gross_wt+'</span><input type="hidden" class="est_old_gross_val" value="'+estval.gross_wt+'"  /></td>'

									+'<td><span class="esti_old_dust_wt">'+estval.dust_wt+'</span><input type="hidden" class="est_old_dust_val" value="'+estval.dust_wt+'"  /><input type="hidden" class="est_old_item_less_wt" value="'+estval.less_wt+'"  /></td>'

									+'<td><span class="esti_old_stn_wt">'+estval.stone_wt+'</span><input type="hidden" class="est_old_stone_val" value="'+estval.stone_wt+'"  /></td>'

									+'<td><span class="est_old_net_wt"></span></td>'

									+'<td><span class="est_old_wastage">'+parseFloat(estval.wastage_percent)+'</span><input type="hidden" class="est_old_wastage_percent" value="'+parseFloat(estval.wastage_percent)+'"  /></td>'

									+'<td><span class="est_old_rate_per_gram">'+parseFloat(estval.rate_per_gram)+'</span><input type="hidden" class="est_old_rate_per_gram_val" value="'+parseFloat(estval.rate_per_gram)+'"  /></td>'

									+'<td><input type="number" class="est_old_discount" value="" step="any" /></td>'

									+'<td><span class="est_old_amount">'+estval.amount+'</span><input type="hidden" class="est_old_item_amount_val" value="'+estval.amount+'"  /></td>'

									+'</tr>';

						$('#bill_old_items_purchased_tbl tbody').append(row);

					});

					$('#bill_old_items_purchased').show();

					calculateOldEsttoSaleConvertion();

				}else{

					$('#bill_old_items_purchased_tbl tbody').empty();

					$('#bill_old_items_purchased').hide();

				}

			}else{

				alert(data.message);

			}

        }

     });

}

function calculate_salesReturn_details(){

	var saleRet_weight = 0;

	var saleRet_rate 	= 0; 

	$('#sale_return_details > tbody  > tr').each(function(index, tr) {

		if($(this).find('td:eq(4) .bill_gross_val').val() != "" && $(this).find('td:eq(6) .gwt').html() != "" && $(this).find('td:eq(11) .sales_value').val() != ""){

			saleRet_weight += parseFloat((isNaN($(this).find('td:eq(6) input[type="hidden"]').val()) || $(this).find('td:eq(6) input[type="hidden"]').val() == '')  ? 0 : $(this).find('td:eq(6) input[type="hidden"]').val());

			saleRet_rate += parseFloat((isNaN($(this).find('td:eq(15) .sale_ret_amt').val()) || $(this).find('td:eq(15) .sale_ret_amt').val() == '')  ? 0 : $(this).find('td:eq(15) .sale_ret_amt').val()); 

			

			cgst += parseFloat((isNaN($(this).find('.sale_tax_cgst').val()) || $(this).find('.sale_tax_cgst').val() == '')  ? 0 : $(this).find('.sale_tax_cgst').val()); 

			sgst += parseFloat((isNaN($(this).find('.sale_tax_sgst').val()) || $(this).find('.sale_tax_sgst').val() == '')  ? 0 : $(this).find('.sale_tax_sgst').val()); 

			igst += parseFloat((isNaN($(this).find('.sale_tax_igst').val()) || $(this).find('.sale_tax_igst').val() == '')  ? 0 : $(this).find('.sale_tax_igst').val()); 

		}

	});

	$(".summary_sale_ret_weight").html(saleRet_weight);

	$(".summary_sale_ret_amt").html(saleRet_rate); 

	calculateFinalCost();

}

function create_new_empty_bill_sales_stone_item(curRow,id)

{

	if(curRow!=undefined)

	{

		$('#active_id').val(curRow.closest('tr').attr('id'));

	}

	var row = "";

	var catRow=$('#active_id').val();

	var row_st_details=$('#'+catRow).find('.stone_details').val();

	var is_partial=$('#'+catRow).find('.is_partial').val();

	var stone_details=JSON.parse(row_st_details);

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

		disabled = "disabled='disabled'";

		}

		stones_list += "<option value='"+pitem.stone_id+"'>"+item.stone_name+"</option>";

		});	

		if(is_partial==1)

		{

			row+='<tr><td><select class="stone_id" name="est_stones_item[stone_id][]" '+disabled+'>'+stones_list+'</select><input type="hidden" class="stone_id" name="est_stones[stone_id][]" value="'+pitem['stone_id']+'" /></td><td><input type="number" class="stone_pcs" name="est_stones[stone_pcs][]" value="'+pitem['stone_pcs']+'" /></td><td><input class="stone_wt" type="number" name="est_stones[stone_wt][]" value="'+pitem['stone_wt']+'" /></td><td><input type="number" class="stone_price" name="est_stones[stone_price][]" value="'+pitem['stone_price']+'" /></td><input type="number" class="certification_cost" name="est_stones[certification_cost][]" value="'+pitem['certification_cost']+'"  style="width:50px;" disabled></td><td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';

		}

		else

		{

			row+='<tr><td><select class="stone_id" name="est_stones_item[stone_id][]" '+disabled+'>'+stones_list+'</select><input type="hidden" class="stone_id" name="est_stones[stone_id][]" value="'+pitem['stone_id']+'" disabled/></td><td><input type="number" class="stone_pcs" name="est_stones[stone_pcs][]" value="'+pitem['stone_pcs']+'" disabled/></td><td><input class="stone_wt" type="number" name="est_stones[stone_wt][]" value="'+pitem['stone_wt']+'" disabled/></td><td><input type="number" class="stone_price" name="est_stones[stone_price][]" value="'+pitem['stone_price']+'" disabled></td><td><input type="number" class="certification_cost" name="est_stones[certification_cost][]" value="'+pitem['certification_cost']+'"  style="width:50px;" disabled></td><td>-</td></tr>';

		}

		});

	}

	$('#stoneModal .modal-body').find('#estimation_stone_item_details tbody').append(row);

	$('#stoneModal').modal('show');

}

$('#stoneModal .modal-body #create_stone_item_details').on('click', function(){

if(validateStoneItemDetailRow()){

			create_new_empty_stone_item();

		}else{

			alert("Please fill required fields");

		}

});

$('#stoneModal  #close_stone_details').on('click', function(){

	$('#stoneModal .modal-body').find('#estimation_stone_item_details tbody').empty();

});

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

   	$('#'+catRow).find('.bill_stone_price').val(stone_price);

   	var row = $('#'+catRow).closest('tr');

	calculateSaleBillRowTotal();

	$('#stoneModal .modal-body').find('#estimation_stone_item_details tbody').empty();

});

//Purchase Stone 

function create_new_empty_bill_purchase_stone_item(curRow,id)

{

	if(curRow!=undefined)

	{

		$('#pur_active_id').val(curRow.closest('tr').attr('id'));

	}

	var row = "";

	var catRow=$('#pur_active_id').val();

	var row_st_details=$('#'+catRow).find('.stone_details').val();

	var stone_details=JSON.parse(row_st_details);

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

		disabled = "disabled='disabled'";

		}

		stones_list += "<option value='"+pitem.stone_id+"'>"+item.stone_name+"</option>";

		});	

		row+='<tr><td><select class="stone_id" name="est_stones_item[stone_id][]" '+disabled+'>'+stones_list+'</select><input type="hidden" class="stone_id" name="est_stones[stone_id][]" value="'+pitem['stone_id']+'" disabled/></td><td><input type="number" class="stone_pcs" name="est_stones[stone_pcs][]" value="'+pitem['stone_pcs']+'" disabled/></td><td><input class="stone_wt" type="number" name="est_stones[stone_wt][]" value="'+pitem['stone_wt']+'" disabled/></td><td><input type="number" class="stone_price" name="est_stones[stone_price][]" value="'+pitem['stone_price']+'" disabled></td><td>-</td></tr>';

		});

	}

	$('#PurstoneModal .modal-body').find('#estimation_pur_stone_item_details tbody').append(row);

	$('#PurstoneModal').modal('show');

}

$('#PurstoneModal  #close_pur_stone_details').on('click', function(){

	$('#PurstoneModal .modal-body').find('#estimation_pur_stone_item_details tbody').empty();

});

//Purchase Stone 

//PAN Card Image

$('#pan_images').on('change',function(){

		item_validateImage();		

});

function item_validateImage()

 {

		var files = event.target.files;

		//var a = $('#cur_id').val();

		var preview=$('#pan_preview');

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

		var pan_img= []; 

		$.each(img_resource,function(key,item){

			   if(item)

			   {

			   		var div = document.createElement("div");

					div.setAttribute('class','col-md-4'); 

					div.setAttribute('id','img_'+key); 

					div.innerHTML+= "<a onclick='img_remove("+key+")'><i class='fa fa-trash'></i></a><img class='thumbnail' src='" + item.src + "'" +

					"style='width: 100px;height: 100px;'/>";  

					preview.append(div);

					pan_img.push(item);

			   }

			   $('#lot_img_upload').css('display','');

		});

		$('#panimg').val(JSON.stringify(pan_img));

	},3000);  

}

 function img_remove(id)

 {

 		var pan_img= []; 

 		$('#img_'+id).remove();

		const index = total_files.indexOf(img_resource[id]);

		total_files.splice(index,1);

		img_resource.splice(index,1);

		$.each(img_resource,function(key,item){

			pan_img.push(item);

		});

		$('#panimg').val(JSON.stringify(pan_img));

 }

//PAN Card Image

function getBillDetails_DateFilter(from_date, to_date){

	var bill_type = $("input[name='billing[bill_type]']:checked").val();

	my_Date = new Date();

	$.ajax({

        url: base_url+'index.php/admin_ret_billing/getBillingDetails/?nocache=' + my_Date.getUTCSeconds(),             

        dataType: "json", 

        method: "POST", 

        data: {'from_date' : from_date, 'to_date' : to_date,'id_branch':$('#id_branch').val(),'bill_cus_id':$('#bill_cus_id').val(),'bill_type':bill_type},

        success: function (data) { 

			if(data.success == true){

				$("#billno_select option").remove();

				$('#billno-detail-popup').modal('toggle');

				var html='';

				var bill_no=[];

				var filter_billno=$('#filter_Billno').val();

				$.each(data.responsedata, function (estkey, bill) {

					bill_no.push({'bill_no':bill.bill_no});

						$('#billno_select').append(

						$("<option></option>")

						.attr("value", bill.bill_no)

						.text(bill.bill_no)

						);

					//html+='<div class="col-md-3"><input type="radio" name="bill_no" id="bill_no'+estkey+'" class="bill_no" value="'+bill.bill_no+'"/> '+bill.bill_no+'-Rs.'+bill.tot_bill_amount+'</div>';

				});

				$("#billno_select").select2({

				placeholder: "Enter Bill No",

				allowClear: true

				});	

				$("#billno_select").select2("val", filter_billno!='' ? filter_billno:'');

			}else{

				alert(data.message);

			}

        }

     });

}

$('#billno_select').change(function() {

	if(this.value!='')

	{

		var bill_type = $("input[name='billing[bill_type]']:checked").val();

		var data = $("#billno_select").select2('data');		

		selectedValue = $(this).val(); 		

		$('#filter_Billno').val(selectedValue);

		var bill_type = $("input[name='billing[bill_type]']:checked").val();

		if(bill_type==7)

		{

			get_return_Bill_details($('#filter_Billno').val(),bill_type);

		}

	}else{

		$('#filter_Billno').val('');

	}

});

 function get_return_Bill_details(billNo, billType){

	$("#ret_bill_id").val("");

	my_Date = new Date();

	$.ajax({

        url: base_url+'index.php/admin_ret_billing/get_return_Bill_details/?nocache=' + my_Date.getUTCSeconds(),             

        dataType: "json", 

        method: "POST", 

        data: {'billNo' : billNo, 'billType' : billType, 'id_branch' : $("#id_branch").val()},

        success: function (data) { 

			if(data.success == true){

				// BILL SOLD ITEMS

				//$('#billno-detail-popup').modal('toggle');

				if(data.responsedata.item_details.length > 0){

					$('#bill_items_for_return tbody').empty();

					//$("#ret_bill_id").val(data.responsedata.item_details[0].bill_id);

					$.each(data.responsedata.item_details, function (estkey, estval) {

						var row = '<tr>'

									+'<td>'+(estval.status == 2 ? '<span style="color:red">Returned</span>':'<input type="checkbox" class="select_est_details" value="1" />')+'<input type="hidden" class="bill_id" value="'+estval.bill_id+'" /><input type="hidden" class="bill_det_id" value="'+estval.bill_det_id+'" /><input type="hidden" class="est_id" value="'+estval.esti_id+'" /><input type="hidden" class="order_no" value="'+estval.order_no+'" /><input type="hidden" class="est_itm_id" value="'+estval.esti_item_id+'" /><input type="hidden" class="est_tag_id" value="'+estval.tag_id+'" /><input type="hidden" class="est_hsn" value="'+estval.hsn_code+'" /><input type="hidden" class="est_item_type" value="'+estval.item_type+'" /><input type="hidden" class="est_cal_type" value="'+estval.calculation_based_on+'" /><input type="hidden" class="est_metal_type" value="'+estval.metal_type+'" /><input type="hidden" class="est_uom" value="'+estval.uom+'"  /></td>'

									+'<td><span class="est_product_name">'+estval.product_short_code+'</span><input class="est_product_id" type="hidden" value="'+estval.product_id+'" /></td>'

									+'<td><span class="est_design_code">'+estval.design_code+'</span><input type="hidden" class="est_design_id" value="'+estval.design_id+'"  /></td>'

									+'<td><span class="est_piece">'+estval.piece+'</span><input type="hidden" class="est_pcs" value="'+estval.piece+'"  /></td>'

									+'<td><span class="est_purname">'+estval.purname+'</span><input type="hidden" class="est_purid" value="'+estval.purname+'"  /></td>'

									+'<td><span class="est_size">'+estval.size+'</span><input type="hidden" class="est_size_val" value="'+estval.size+'"  /></td>'

									+'<td><span class="est_gross_wt">'+estval.gross_wt+'</span><input type="hidden" class="est_gross_val" value="'+estval.gross_wt+'"  /></td>'

									+'<td><span class="est_less_wt">'+estval.less_wt+'</span><input type="hidden" class="est_less_val" value="'+estval.less_wt+'"  /></td>'

									+'<td><span class="est_net_wt">'+estval.net_wt+'</span><input type="hidden" class="est_net_val" value="'+estval.net_wt+'"  /></td>'

									+'<td><span class="est_wastage">'+estval.wastage_percent+'</span><input type="hidden" class="est_wastage_percent" value="'+estval.wastage_percent+'"  /></td>'

									+'<td><span class="est_mc">'+estval.mc_value+' '+(estval.mc_type == 1 ? ' per gm' : ' per pc')+'</span><input type="hidden" class="est_mc_value" value="'+estval.mc_value+'"  /><input type="hidden" class="est_mc_type" value="'+estval.mc_type+'"  /></td>'

									+'<td><input type="hidden" class="est_discount" value="'+estval.discount+'"/>'+estval.discount+'</td>'

									+'<td><span class="est_tgrp_name">'+estval.tgrp_name+'</span><input type="hidden" class="est_tax_id" value="'+estval.tax_group_id+'" /><input type="hidden" class="est_tax_val" value="'+estval.item_total_tax+'" /><input type="hidden" class="est_tax_cgst" value="'+estval.total_cgst+'" /><input type="hidden" class="est_tax_igst" value="'+estval.total_igst+'" /><input type="hidden" class="est_tax_sgst" value="'+estval.total_sgst+'" /></td>'

									+'<td>'+estval.item_total_tax+'</td>'

									+'<td><span class="est_item_cost">'+estval.item_cost+'</span><input type="hidden" class="est_material_price" value="'+estval.othermat_amount+'"  /><input type="hidden" class="est_stone_price" value="'+estval.stone_price+'"  /><input type="hidden" class="est_item_cost_val" value="'+estval.item_cost+'"  /><input type="hidden" class="bill_amt_without_tax" value="'+parseFloat(estval.item_cost-estval.item_total_tax).toFixed(2)+'"  /></td>'

									+'<td>'+(estval.is_partial == 1 ?"<input type='checkbox' class='select_est_partial' value='1' />" : '-' )+'</td>'

									+'<td><span class="est_tag_no">'+estval.tag_id+'</span></td>'

									+'</tr>';

						$('#bill_items_for_return tbody').append(row);

					});

					$('#bill_items_return').show();

					calculateEsttoSaleConvertion();

				}

				else{ 

					$('#bill_items_tbl_for_return tbody').empty();

					if(billType != 4 && billType != 6){ 

						$('#bill_items_return').show();

					}else{

						$('#bill_items_return').hide();

					} 

				}

				// BILL PURCHASED ITEMS

				if(data.responsedata.old_matel_details.length > 0){

					$('#bill_old_items_purchased_tbl tbody').empty();

					$.each(data.responsedata.old_matel_details, function (estkey, estval) {

						/*<td><span class="est_old_item_pur">'+estval.purname+'</span><input type="hidden" class="est_old_item_purid" value="'+estval.purid+'"  /></td>*/ 

						var row = '<tr>'

									+'<td>'+estval.est_id+'/td>'

									+'<td><span>'+(estval.purpose == 1 ? "Cash" : "Exchange")+'</span><input type="hidden" class="est_purpose" value="'+estval.purpose+'" /></td>'

									+'<td><span class="est_old_item_metal">'+estval.metal+'</span><input type="hidden" class="est_old_item_cat_id" value="'+estval.id_category+'"  /></td>'

									+'<td><span class="est_old_itm_gross_wt">'+estval.gross_wt+'</span><input type="hidden" class="est_old_gross_val" value="'+estval.gross_wt+'"  /></td>'

									+'<td><span class="esti_old_dust_wt">'+estval.dust_wt+'</span><input type="hidden" class="est_old_dust_val" value="'+estval.dust_wt+'"  /><input type="hidden" class="est_old_item_less_wt" value="'+estval.less_wt+'"  /></td>'

									+'<td><span class="esti_old_stn_wt">'+estval.stone_wt+'</span><input type="hidden" class="est_old_stone_val" value="'+estval.stone_wt+'"  /></td>'

									+'<td><span class="est_old_net_wt"></span></td>'

									+'<td><span class="est_old_wastage">'+parseFloat(estval.wastage_percent)+'</span><input type="hidden" class="est_old_wastage_percent" value="'+parseFloat(estval.wastage_percent)+'"  /></td>'

									+'<td><span class="est_old_rate_per_gram">'+parseFloat(estval.rate_per_gram)+'</span><input type="hidden" class="est_old_rate_per_gram_val" value="'+parseFloat(estval.rate_per_gram)+'"  /></td>'

									+'<td><input type="number" class="est_old_discount" value="" step="any" /></td>'

									+'<td><span class="est_old_amount">'+estval.amount+'</span><input type="hidden" class="est_old_item_amount_val" value="'+estval.amount+'"  /></td>'

									+'</tr>';

						$('#bill_old_items_purchased_tbl tbody').append(row);

					});

					$('#bill_old_items_purchased').show();

					calculateOldEsttoSaleConvertion();

				}else{

					$('#bill_old_items_purchased_tbl tbody').empty();

					$('#bill_old_items_purchased').hide();

				}

			}else{

				alert(data.message);

			}

        }

     });

}

$('#update_billreturn').on('click', function(){

		var bill_type = $("input[name='billing[bill_type]']:checked").val(); 

		if(bill_type==8)

		{

			getCreditBillDetails($('#filter_Billno').val(),bill_type);

		}

		else

		{

		$('#bill_items_for_return > tbody tr').each(function(idx, row){

			sold_items_row = $(this);

			var rowExist = false;

			if(sold_items_row.find('td:first .select_est_details').is(':checked') ){

				$(".return_details").show();

				$('#sale_return_details > tbody tr').each(function(bidx, brow){

					return_items_row = $(this);

					// CHECK DUPLICATES - TAG

					if(sold_items_row.find('.est_tag_id').val() != ''){

						if( sold_items_row.find('.est_tag_id').val() == return_items_row.find('.sale_tag_id').val()){

							rowExist = true; 

							/*console.log("Tag ID - "+bidx+" : From Modal"+sold_items_row.find('td:first .est_tag_id').val()+" From Bill"+return_items_row.find('td:eq(15) .sale_tag_id').val());

							console.log(rowExist);*/

						} 

					}

					// CHECK DUPLICATES - ESTIMATION ITEM

					if(return_items_row.find('td:first .is_est_details').val() == 1 )

					{ 

						if(sold_items_row.find('.est_itm_id').val()!='' && sold_items_row.find('.est_itm_id').val()!=null)

						{

						if(sold_items_row.find('.est_itm_id').val() == return_items_row.find('.est_itm_id').val()){

							rowExist = true;

							/*console.log("Esti ID - "+bidx+" : From Modal"+sold_items_row.find('td:first .est_itm_id').val()+" From Bill"+sold_items_row.find('td:first .est_itm_id').val());

							console.log(rowExist);*/

						}

						}

					}

				});

				console.log(rowExist);

				if(!rowExist){

					if(sold_items_row.find('td:last .select_est_partial').is(':checked')){

						var row = '<tr>'

									+'<td><span>'+sold_items_row.find('td:first .est_hsn').val()+'</span><input type="hidden" class="bill_id" name="sales_return['+idx+'][bill_id]" value="'+sold_items_row.find('td:first .bill_id').val()+'" /><input type="hidden" class="bill_det_id" name="sales_return['+idx+'][bill_det_id]" value="'+sold_items_row.find('td:first .bill_det_id').val()+'" /><input type="hidden" class="sale_pro_hsn" name="sales_return['+idx+'][hsn]" value="'+sold_items_row.find('td:first .est_hsn').val()+'" /><input type="hidden" class="sale_type" name="sales_return['+idx+'][sourcetype]" value="1" /><input type="hidden" class="sale_item_type" name="sales_return['+idx+'][itemtype]" value="'+sold_items_row.find('td:first .est_item_type').val()+'" /><input type="hidden" class="is_est_details" value="1" name="sales_return['+idx+'][is_est_details]" /><input type="hidden" class="est_itm_id" name="sales_return['+idx+'][est_itm_id]" value="'+sold_items_row.find('td:first .est_itm_id').val()+'" /><input type="hidden" class="sale_cal_type" name="sales_return['+idx+'][calltype]" value="'+sold_items_row.find('td:first .est_cal_type').val()+'" /><input type="hidden" class="sale_metal_type" value="'+sold_items_row.find('td:first .est_metal_type').val()+'" /><input type="hidden" class="sale_purity" value="'+sold_items_row.find('td:eq(4) .est_purid').val()+'"  name="sales_return['+idx+'][purity]" /><input type="hidden" class="sale_size" value="'+sold_items_row.find('td:eq(5) .est_size_val').val()+'"  name="sales_return['+idx+'][size]" /><input type="hidden" class="sale_uom" value="'+sold_items_row.find('td:eq(0) .est_uom').val()+'"  name="sales_return['+idx+'][uom]" /></td>'

									+'<td><span>'+sold_items_row.find('td:eq(1) .est_product_name').html()+'</span><input class="sale_product_id" type="hidden" name="sales_return['+idx+'][product]" value="'+sold_items_row.find('td:eq(1) .est_product_id').val()+'" /></td>'

									+'<td><span>'+sold_items_row.find('td:eq(2) .est_design_code').html()+'</span><input type="hidden" class="sale_design_id" name="sales_return['+idx+'][design]" value="'+sold_items_row.find('td:eq(2) .est_design_id').val()+'" /></td>'

									+'<td><input type="number" class="sale_pcs" name="sales_return['+idx+'][pcs]" value="'+sold_items_row.find('td:eq(3) .est_pcs').val()+'"  /></td>'

									+'<td><span>'+sold_items_row.find('td:eq(6) .est_gross_wt').html()+'</span><input type="hidden" class="bill_gross_val" name="sales_return['+idx+'][gross]" value="'+sold_items_row.find('td:eq(6) .est_gross_val').val()+'" /></td>'

									+'<td><span>'+sold_items_row.find('td:eq(7) .est_less_wt').html()+'</span><input type="hidden" class="bill_less_val" name="sales_return['+idx+'][less]" value="'+sold_items_row.find('td:eq(7) .est_less_val').val()+'" /></td>'

									+'<td><span>'+sold_items_row.find('td:eq(8) .est_net_wt').html()+'</span><input type="hidden" class="bill_net_val" name="sales_return['+idx+'][net]" value="'+sold_items_row.find('td:eq(8) .est_net_val').val()+'" /></td>'

									+'<td><span>'+sold_items_row.find('td:eq(9) .est_wastage').html()+'</span><input type="hidden" class="bill_wastage" name="sales_return['+idx+'][wastage]" value="'+sold_items_row.find('td:eq(9) .est_wastage_percent').val()+'" /></td>'

									+'<td><span>'+sold_items_row.find('td:eq(10) .est_mc').html()+'</span><input type="hidden" class="bill_mctype" name="sales_return['+idx+'][bill_mctype]" value="'+sold_items_row.find('td:eq(10) .est_mc_type').val()+'" /><input type="hidden" class="bill_mc" name="sales_return['+idx+'][mc]" value="'+sold_items_row.find('td:eq(10) .est_mc_value').val()+'" /></td>'

									+'<td><input type="hidden" class="bill_discount" name="sales_return['+idx+'][discount]" value="'+sold_items_row.find('td:eq(11) .est_discount').val()+'"  />'+sold_items_row.find('td:eq(11) .est_discount').val()+'</td>'

									+'<td><span class="ret_bill_amount">'+sold_items_row.find('.bill_amt_without_tax').val()+'</span></td>'

									+'<td><span>'+sold_items_row.find('td:eq(12) .est_tgrp_name').html()+'</span><input type="hidden" class="sale_tax_group" name="sales_return['+idx+'][taxgroup]" value="'+sold_items_row.find('td:eq(12) .est_tax_id').val()+'" /><input type="hidden" class="sale_tax_cgst" name="sales_return['+idx+'][cgst]" value="'+sold_items_row.find('.est_tax_cgst').val()+'" /><input type="hidden" class="sale_tax_sgst" name="sales_return['+idx+'][sgst]" value="'+sold_items_row.find('.est_tax_sgst').val()+'" /><input type="hidden" class="sale_tax_igst" name="sales_return['+idx+'][igst]" value="'+sold_items_row.find('.est_tax_igst').val()+'" /></td>'

									+'<td><span>'+sold_items_row.find('td:eq(12) .est_tax_val').val()+'</span></td>'

									+'<td><input type="hidden" class="bill_stone_price" value="'+sold_items_row.find('.est_stone_price').val()+'" /><input type="hidden" class="bill_material_price" value="'+sold_items_row.find('.est_material_price').val()+'"/><input type="number" class="bill_amount" name="sales_return['+idx+'][billamount]" value="'+sold_items_row.find('.est_item_cost_val').val()+'" step="any" readonly style="width: 100px;"/><input type="hidden" class="per_grm_amount" name="sales_return['+idx+'][per_grm]" value="" step="any" /></td>'

									+'<td><input type="number" class="sale_ret_disc_amt" name="sales_return['+idx+'][sale_ret_disc_amt]" value="" step="any" style="width: 100px;"/></td>'

									+'<td><input type="number" class="sale_ret_amt" name="sales_return['+idx+'][sale_ret_amt]" value="'+sold_items_row.find('.est_item_cost_val').val()+'" step="any" readonly style="width: 100px;" readonly/></td>'

									+'<td>Yes</td>'

									+'<td><span>'+sold_items_row.find('td:first .est_tag_id').val()+'</span><input type="hidden" class="sale_tag_id" name="sales_return['+idx+'][tag]" value="'+sold_items_row.find('td:first .est_tag_id').val()+'" /></td>'

									+'<td>-</td>'

									+'<td><span>'+sold_items_row.find('td:first .est_id').val()+'</span><input type="hidden" class="sale_est_itm_id" name="sales_return['+idx+'][estid]" value="'+sold_items_row.find('td:first .est_itm_id').val()+'" /></td>'

									+'<td><a href="#" onClick="remove_row($(this).closest(\'tr\'));" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'

									+'</tr>';

					}else{  

						var row = '<tr>'

									+'<td><span>'+sold_items_row.find('td:first .est_hsn').val()+'</span><input type="hidden" class="bill_id" name="sales_return['+idx+'][bill_id]" value="'+sold_items_row.find('td:first .bill_id').val()+'" /><input type="hidden" class="bill_det_id" name="sales_return['+idx+'][bill_det_id]" value="'+sold_items_row.find('td:first .bill_det_id').val()+'" /><input type="hidden" class="sale_pro_hsn" name="sales_return['+idx+'][hsn]" value="'+sold_items_row.find('td:first .est_hsn').val()+'" /><input type="hidden" class="sale_type" name="sales_return['+idx+'][sourcetype]" value="1" /><input type="hidden" class="sale_item_type" name="sales_return['+idx+'][itemtype]" value="'+sold_items_row.find('td:first .est_item_type').val()+'" /><input type="hidden" class="is_est_details" value="1" name="sales_return['+idx+'][is_est_details]" /><input type="hidden" class="est_itm_id" name="sales_return['+idx+'][est_itm_id]" value="'+sold_items_row.find('td:first .est_itm_id').val()+'" /><input type="hidden" class="sale_cal_type" name="sales_return['+idx+'][calltype]" value="'+sold_items_row.find('td:first .est_cal_type').val()+'" /><input type="hidden" class="sale_metal_type" value="'+sold_items_row.find('td:first .est_metal_type').val()+'" /><input type="hidden" class="sale_purity" value="'+sold_items_row.find('td:eq(4) .est_purid').val()+'"  name="sales_return['+idx+'][purity]" /><input type="hidden" class="sale_size" value="'+sold_items_row.find('td:eq(5) .est_size_val').val()+'"  name="sales_return['+idx+'][size]" /><input type="hidden" class="sale_uom" value="'+sold_items_row.find('td:eq(0) .est_uom').val()+'"  name="sales_return['+idx+'][uom]" /></td>'

									+'<td><span>'+sold_items_row.find('td:eq(1) .est_product_name').html()+'</span><input class="sale_product_id" type="hidden" name="sales_return['+idx+'][product]" value="'+sold_items_row.find('td:eq(1) .est_product_id').val()+'" /></td>'

									+'<td><span>'+sold_items_row.find('td:eq(2) .est_design_code').html()+'</span><input type="hidden" class="sale_design_id" name="sales_return['+idx+'][design]" value="'+sold_items_row.find('td:eq(2) .est_design_id').val()+'" /></td>'

									+'<td><span>'+sold_items_row.find('td:eq(3) .est_piece').html()+'</span><input type="hidden" class="sale_pcs" name="sales_return['+idx+'][pcs]" value="'+sold_items_row.find('td:eq(3) .est_pcs').val()+'"  /></td>'

									+'<td><span>'+sold_items_row.find('td:eq(6) .est_gross_wt').html()+'</span><input type="hidden" class="bill_gross_val" name="sales_return['+idx+'][gross]" value="'+sold_items_row.find('td:eq(6) .est_gross_val').val()+'" /></td>'

									+'<td><span>'+sold_items_row.find('td:eq(7) .est_less_wt').html()+'</span><input type="hidden" class="bill_less_val" name="sales_return['+idx+'][less]" value="'+sold_items_row.find('td:eq(7) .est_less_val').val()+'" /></td>'

									+'<td><span>'+sold_items_row.find('td:eq(8) .est_net_wt').html()+'</span><input type="hidden" class="bill_net_val" name="sales_return['+idx+'][net]" value="'+sold_items_row.find('td:eq(8) .est_net_val').val()+'" /></td>'

									+'<td><span>'+sold_items_row.find('td:eq(9) .est_wastage').html()+'</span><input type="hidden" class="bill_wastage" name="sales_return['+idx+'][wastage]" value="'+sold_items_row.find('td:eq(9) .est_wastage_percent').val()+'" /></td>'

									+'<td><span>'+sold_items_row.find('td:eq(10) .est_mc').html()+'</span><input type="hidden" class="bill_mctype" name="sales_return['+idx+'][bill_mctype]" value="'+sold_items_row.find('td:eq(10) .est_mc_type').val()+'" /><input type="hidden" class="bill_mc" name="sales_return['+idx+'][mc]" value="'+sold_items_row.find('td:eq(10) .est_mc_value').val()+'" /></td>'

									+'<td><input type="hidden" class="bill_discount" name="sales_return['+idx+'][discount]" value="'+sold_items_row.find('td:eq(11) .est_discount').val()+'"  />'+sold_items_row.find('td:eq(11) .est_discount').val()+'</td>'

									+'<td><span class="ret_bill_amount">'+sold_items_row.find('.bill_amt_without_tax').val()+'</span></td>'

									+'<td><span>'+sold_items_row.find('td:eq(12) .est_tgrp_name').html()+'</span><input type="hidden" class="sale_tax_group" name="sales_return['+idx+'][taxgroup]" value="'+sold_items_row.find('td:eq(12) .est_tax_id').val()+'" /><input type="hidden" class="sale_tax_cgst" name="sales_return['+idx+'][cgst]" value="'+sold_items_row.find('.est_tax_cgst').val()+'" /><input type="hidden" class="sale_tax_sgst" name="sales_return['+idx+'][sgst]" value="'+sold_items_row.find('.est_tax_sgst').val()+'" /><input type="hidden" class="sale_tax_igst" name="sales_return['+idx+'][igst]" value="'+sold_items_row.find('.est_tax_igst').val()+'" /></td>'

									+'<td><span>'+sold_items_row.find('td:eq(12) .est_tax_val').val()+'</span></td>'

									+'<td><input type="hidden" class="bill_stone_price" value="'+sold_items_row.find('.est_stone_price').val()+'" /><input type="hidden" class="bill_material_price" value="'+sold_items_row.find('.est_material_price').val()+'"/><input type="number" class="bill_amount" name="sales_return['+idx+'][billamount]" value="'+sold_items_row.find('.est_item_cost_val').val()+'" step="any" readonly style="width: 100px;"/><input type="hidden" class="per_grm_amount" name="sales_return['+idx+'][per_grm]" value="" step="any" /></td>'

									+'<td><input type="number" class="sale_ret_disc_amt" name="sales_return['+idx+'][sale_ret_disc_amt]" value="" step="any" style="width: 100px;"/></td>'

									+'<td><input type="number" class="sale_ret_amt" name="sales_return['+idx+'][sale_ret_amt]" value="'+sold_items_row.find('.est_item_cost_val').val()+'" step="any" readonly style="width: 100px;"/></td>'

									+'<td>No</td>'

									+'<td><span>'+sold_items_row.find('td:first .est_tag_id').val()+'</span><input type="hidden" class="sale_tag_id" name="sales_return['+idx+'][tag]" value="'+sold_items_row.find('td:first .est_tag_id').val()+'" /></td>'

									+'<td>-</td>'

									+'<td><span>'+sold_items_row.find('td:first .est_itm_id').val()+'</span><input type="hidden" class="sale_est_itm_id" name="sales_return['+idx+'][estid]" value="'+sold_items_row.find('td:first .est_itm_id').val()+'" /></td>'

									+'<td><a href="#" onClick="remove_row($(this).closest(\'tr\'));" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'

									+'</tr>';

					}

					$('#sale_return_details tbody').append(row);

				}

			}

		});

		calculate_salesReturn_details();

		}

		$('#billno-detail-popup').modal('toggle');

	});

	

function set_issue_list()

{

	my_Date = new Date();

	$("div.overlay").css("display", "block"); 

	$.ajax({

		 url:base_url+"index.php/admin_ret_billing/issue/ajax?nocache=" + my_Date.getUTCSeconds(),

		 dataType:"JSON",

		 data:{'bill_no':$('#filter_bill_no').val()},

		 type:"POST",

		 success:function(data){

			console.log("List", data);

   			$("div.overlay").css("display", "none"); 

   			var list = data.list;

				var oTable = $('#receipt_list').DataTable();

				oTable.clear().draw();

				if (list!= null && list.length > 0)

				{  	

					 oTable = $('#receipt_list').dataTable({

						"bDestroy": true,

		                "bInfo": true,

		                "bFilter": true,

						"order": [[ 0, "desc" ]],

		                "scrollX":'100%', 

		                "bSort": true, 

		                "dom": 'lBfrtip',

						"aaData": list,

						"aoColumns": [	{ "mDataProp": "id_issue_receipt" },

										{ "mDataProp": "type" },

										{ "mDataProp": "date_add" },

										{ "mDataProp": "barrower_name" },

										{ "mDataProp": "amount" },

										{ "mDataProp": "status" },

										

										{ "mDataProp": function ( row, type, val, meta ) {

									        id= row.id_issue_receipt;

									        print_url=base_url+'index.php/admin_ret_billing/issue/issue_print/'+id;

									        action_content='<a href="'+print_url+'" target="_blank" class="btn btn-info btn-print" data-toggle="tooltip" title="Billing Receipt"><i class="fa fa-print" ></i></a>';

									        return action_content;

									        }

									     }

									],

					 });			  	 	

				}

		  },

		  error:function(error)  

		  {

			 $("div.overlay").css("display", "none"); 

		  }	 

	});

}

// issue 

	$("#name").on("keyup",function(e){ 

		var customer = $("#name").val();

		if(customer.length >= 2) { 

			getSearchCustomer(customer);

		}

	});

	$('input[type=radio][name="issue[issue_to]"]').change(function() {

		$("#name").val('');

		$("#mobile").val('');

		$("#id_employee").val('');

		$("#id_customer").val('');

		if($(this).val()==3)

		{

			$('#issue_type1').prop('checked',true);

			$("#name").prop('disabled',true);

			$("#mobile").prop('readonly',false);

			$("#acc_head").prop('disabled',false);

			get_account_head();

		}else{

			$('#issue_type2').prop('checked',true);

			$("#name").prop('disabled',false);

			$("#mobile").prop('readonly',true);

			$("#acc_head").prop('disabled',true);

		}	

	});

	function getSearchCustomer(searchTxt){

	$("#customerAlert").html('');

	var issue_to=$("input:radio[name='issue[issue_to]']:checked").val();

	var issue_type=$("input:radio[name='issue[issue_type]']:checked").val();

	var id_branch=$("#branch_select").val();

	my_Date = new Date();

	$.ajax({

        url: base_url+'index.php/admin_ret_billing/get_borrower/?nocache=' + my_Date.getUTCSeconds(),             

        dataType: "json", 

        method: "POST", 

        data: {'searchTxt': searchTxt,'id_branch':id_branch,'issue_to':issue_to,'issue_type':issue_type}, 

        success: function (data) {

			$( "#name" ).autocomplete(

			{

				source: data,

				select: function(e, i)

				{ 

					e.preventDefault();

					$("#name").val(i.item.label);

					$("#mobile").val(i.item.mobile);

					$("#barrower_name").val(i.item.barrower_name);

					if(issue_to==1)

					{

						$("#id_employee").val(i.item.value);

					}else{

						$("#id_customer").val(i.item.value);

						$("#id_ret_wallet").val(i.item.id_ret_wallet);

						if(issue_type==3)

						{

						    if(i.item.wallet_det['amount']>0)

						    {

						         $('#issue_amount').prop('disabled',false);

						         $('#issue_amount').val(i.item.wallet_det['amount']);

						         $('.pay_to_cus').val(i.item.wallet_det['amount']);

						    }else{

						          alert('Your Wallet Amount is 0.');

						          $('#issue_amount').prop('disabled',true);

						    }

						   

						}else{

						    $('#issue_amount').val('');

						    $('.pay_to_cus').val('');

						}

					}

				},

				change: function (event, ui) {

					if (ui.item === null) {

						$(this).val('');

						$('#name').val('');

						$("#id_employee").val("");

						$("#id_customer").html("");

					}

			    },

				response: function(e, i) {

		            // ui.content is the array that's about to be sent to the response callback.

		            if(searchTxt != ""){

						if (i.content.length === 0) {

						   $("#customerAlert").html('<p style="color:red">Enter a valid  name / mobile</p>');

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

	function get_account_head()

	{

		my_Date = new Date();

		$.ajax({ 

		url:base_url+ "index.php/admin_ret_billing/get_account_head?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),

        type:"GET",

        dataType:"JSON",

        success:function(data)

        {

        	var id_acc_head=$('#id_acc_head').val();

           $.each(data, function (key, item) {					  				  			   		

                	 	$("#acc_head").append(						

                	 	$("<option></option>")						

                	 	.attr("value", item.id_acc_head)						  						  

                	 	.text(item.name)						  					

                	 	);			   											

                 	});						

             	$("#acc_head").select2({			    

            	 	placeholder: "Select Account Head",			    

            	 	allowClear: true		    

             	});					

         	    $("#acc_head").select2("val",(id_acc_head!='' && id_acc_head>0?id_acc_head:''));	 

         	    $(".overlay").css("display", "none");	

        },

        error:function(error)  

        {	

        } 

    	});

	}

    

    $('#acc_head').on('change',function(){

        if(this.value!='')

        {

            $('#id_acc_head').val(this.value);

        }else{

            $('#id_acc_head').val('');

        }

    })

	$('#issue_amount').on('keyup',function(e){

		$(".pay_to_cus").val(parseFloat(this.value).toFixed(2));

		calculateIssueAmount();

	});

	$('#cash_pay').on('keyup',function(e){

		calculateIssueAmount();

	});

	function calculateIssueAmount()

	{

		var issue_amount=$('.pay_to_cus').val();

		console.log($('#cash_pay').val());

		var cash_pay=($('#cash_pay').val()=='' ? 0 :$('#cash_pay').val());

		var cc=($('.CC').html()!='' ? $('.CC').html():0);

		var dc=($('.DC').html()!='' ? $('.DC').html():0);

		var chq=($('.CHQ').html()!='' ? $('.CHQ').html():0);

		var NB=($('.NB').html()!='' ? $('.NB').html():0);

		var final_price=0;

		final_price=parseFloat(parseFloat(cash_pay)+parseFloat(NB)+parseFloat(cc)+parseFloat(dc)+parseFloat(chq)).toFixed(2);

		$('.total_issue_amt').html(parseFloat(final_price).toFixed(2));

		if(final_price==issue_amount)

		{	

			$('#save_issue').prop('disabled',false);

		}else{

			$('#save_issue').prop('disabled',true);

		}

	}

// issue 

$('#add_issue_card').on('click',function(){

		if(validateCardDetailRow()){

			$('#payment_modes > tbody >tr').each(function(bidx, brow){

				bill_card_pay_row = $(this);

				bill_card_pay_row.find('.CC').html($('.cc_total_amt').html());

				bill_card_pay_row.find('.DC').html($('.dc_total_amt').html());

				bill_card_pay_row.find('#card_payment').val(card_payment.length>0 ? JSON.stringify(card_payment):'');

			});

			$('#card-detail-modal').modal('toggle');

			calculateIssueAmount();

		}else{

			alert("Please fill required fields");

		}

});

$('#save_issue_chq').on('click',function(){

		if(validateChqDetailRow()){

			$('#payment_modes > tbody >tr').each(function(bidx, brow){

				bill_card_pay_row = $(this);

				bill_card_pay_row.find('.CHQ').html($('.chq_total_amount').html());

				bill_card_pay_row.find('#chq_payment').val(chq_payment.length>0 ? JSON.stringify(chq_payment):'');

			});

			$('#cheque-detail-modal').modal('toggle');

			calculateIssueAmount();

		}else{

			alert("Please fill required fields");

		}

});

$('#save_issue_net_banking').on('click',function(){

		if(validateNBDetailRow()){

			$('#payment_modes > tbody >tr').each(function(bidx, brow){

				bill_card_pay_row = $(this);

				bill_card_pay_row.find('.NB').html($('.nb_total_amount').html());

				bill_card_pay_row.find('#nb_payment').val(nb_payment.length>0 ? JSON.stringify(nb_payment):'');

			});

			$('#net_banking_modal').modal('toggle');

			calculateIssueAmount();

		}else{

			alert("Please fill required fields");

		}

});

//receipt

 $("input[name='receipt[receipt_type]']:radio").on('change',function(){

	   if($(this).val()==1)

	   {

	   		$('#receipt_for').prop('disabled',false);

	   		$('#amount').prop('disabled',true);

	   		$('#esti_no').prop('disabled',true);

	   		$('#name').prop('readonly',true);

	   }

	   else

	   {

    	   	$('#receipt_for').prop('disabled',true);

    	   	$('#amount').prop('disabled',false);

    	   	//$('#esti_no').prop('disabled',false);

    	   	$('#name').prop('readonly',false);

	   }

	});

function set_receipt_list()

{

	my_Date = new Date();

	$("div.overlay").css("display", "block"); 

	$.ajax({

		 url:base_url+"index.php/admin_ret_billing/receipt/ajax?nocache=" + my_Date.getUTCSeconds(),

		 dataType:"JSON",

		 data:{'bill_no':$('#filter_bill_no').val()},

		 type:"POST",

		 success:function(data){

			console.log("List", data);

   			$("div.overlay").css("display", "none"); 

   			var list = data.list;

				var oTable = $('#receipt_list').DataTable();

				oTable.clear().draw();

				if (list!= null && list.length > 0)

				{  	

					 oTable = $('#receipt_list').dataTable({

						"bDestroy": true,

		                "bInfo": true,

		                "bFilter": true,

						"order": [[ 0, "desc" ]],

		                "scrollX":'100%', 

		                "bSort": true, 

		                "dom": 'lBfrtip',

						"aaData": list,

						"aoColumns": [	{ "mDataProp": "id_issue_receipt" },

										{ "mDataProp": "type" },

										{ "mDataProp": "date_add" },

										{ "mDataProp": "cus_name" },

										{ "mDataProp": "amount" },

										{ "mDataProp": "weight" },

										{ "mDataProp": "bill_status" },

										

										{ "mDataProp": function ( row, type, val, meta ) {

									        id= row.id_issue_receipt;

									        print_url=base_url+'index.php/admin_ret_billing/receipt/receipt_print/'+id;

									        action_content='<a href="'+print_url+'" target="_blank" class="btn btn-info btn-print" data-toggle="tooltip" ><i class="fa fa-print" ></i></a>';

									        return action_content;

									        }

									     }

									],

					 });			  	 	

				}

		  },

		  error:function(error)  

		  {

			 $("div.overlay").css("display", "none"); 

		  }	 

	});

}

$("#receipt_for").on("keyup",function(e){ 

		var credit_no = $("#receipt_for").val();

		$("#creditAlert").html('');

		if(credit_no.length >= 1) { 

			get_creditBill(credit_no);

		}

	});

	function get_creditBill(searchTxt){

	var id_branch=$("#branch_select").val();

	my_Date = new Date();

	$.ajax({

        url: base_url+'index.php/admin_ret_billing/receipt/credit_bill/?nocache=' + my_Date.getUTCSeconds(),             

        dataType: "json", 

        method: "POST", 

        data: {'searchTxt': searchTxt,'id_branch':id_branch}, 

        success: function (data) {

			$("#receipt_for").autocomplete(

			{

				source: data,

				select: function(e, i)

				{ 

					e.preventDefault();

					$("#receipt_for").val(i.item.label);

					$("#receipt_for").val(i.item.value);

					$('.receive_amount').val(parseFloat(i.item.amount)-parseFloat(i.item.paid_amount))	;

					$('#due_amount').val(i.item.amount)	;

					$('#paid_amount').val(i.item.paid_amount)	;

				},

				change: function (event, ui) {

					if (ui.item === null) {

						$(this).val('');

						$('#name').val('');

						$("#id_employee").val("");

						$("#id_customer").html("");

					}

			    },

				response: function(e, i) {

		            // ui.content is the array that's about to be sent to the response callback.

		            if(searchTxt != ""){

						if (i.content.length === 0) {

						   $("#creditAlert").html('<p style="color:red">Enter a Valid Credit No</p>');

						}else{

						   $("#creditAlert").html('');

						} 

					}else{

					}

		        },

				 minLength: 1,

			});

        }

     });

}

$("#est_search").on('click', function(){

		$('#searchEstiAlert').html('');

		$('#branchAlert').html('');

		if($('#branch_select').val()==null)

		{

			$('#branchAlert').html('Please Select Branch');

		}

		else if($('#esti_no').val() != "" )

		{

			$('#branchAlert').html('');

			getEstDetails($('#esti_no').val());	

		}

});

function getEstDetails(esti_no)

{

	my_Date = new Date();

	$.ajax({

        url: base_url+'index.php/admin_ret_billing/getEstimationDetails/?nocache='+my_Date.getUTCSeconds(),             

        dataType: "json", 

        method: "POST", 

        data: {'estId' : esti_no,'billType':4,'id_branch':$('#branch_select').val(),'order_no':''}, //Need to update login branch id here from session

        success: function (data) { 

			if(data.success == true){

	

				// ESTIMATION PURCHASE ITEMS

				if(data.responsedata.old_matel_details.length > 0){

						var rowExist=false;

						$('#purchase_item_details tbody').empty();

						$('#name').val(data.responsedata.old_matel_details[0].cus_name);

				    	$('#id_customer').val(data.responsedata.old_matel_details[0].id_customer);

				    	

						$.each(data.responsedata.old_matel_details, function (estkey, estval) {

						$('#purchase_item_details > tbody tr').each(function(bidx, brow){

						bill_pur_row = $(this);

						if(estval.old_metal_sale_id == bill_pur_row.find('.old_metal_sale_id').val()){

						rowExist = true;

						}

						});

						if(!rowExist)

						{

						var stone_details=[];

						var other_stone_wt=0;

						var other_stone_price=0;

						$.each(estval.stone_details,function(key,item){

							stone_details.push({'est_old_metal_stone_id':item.est_old_metal_stone_id,'stone_id' : item.stone_id,'stone_pcs':item.pieces,'stone_wt':item.wt,'stone_price':item.price});

							other_stone_wt+=parseFloat(item.wt);

							other_stone_price+=parseFloat(item.price);

						});

						var row = '<tr>'

						+'<td><span>'+(estval.purpose == 1 ? "Cash" : "Exchange")+'</span><input type="hidden" class="est_purpose" name="purchase[purpose][]" value="'+estval.purpose+'" /><input type="hidden" class="esti_detail_id" name="purchase[esti_detail_id][]" value="'+estval.old_metal_sale_id+'" /><input type="hidden" class="est_id_metal" name="purchase[id_metal][]" value="'+estval.id_metal+'" /><input type="hidden" class="est_item_type" name="purchase[item_type][]" value="'+estval.item_type+'" /></td>'

						+'<td><span class="est_old_item_metal">'+estval.metal+'</span><input type="hidden" class="est_old_item_cat_id" name="purchase[id_category][]" value="'+estval.id_category+'"  /></td>'

						+'<td><span class="est_old_itm_gross_wt">'+estval.gross_wt+'</span><input type="hidden" class="est_old_gross_val" name="purchase[gross_wt][]" value="'+estval.gross_wt+'"  /></td>'

						+'<td><span class="esti_old_dust_wt">'+estval.dust_wt+'</span><input type="hidden" class="est_old_dust_val" value="'+estval.dust_wt+'" name="purchase[dust_wt][]" /><input type="hidden" class="est_old_item_less_wt" value="'+estval.less_wt+'"  name="purchase[less_wt][]" /></td>'

						+'<td><span class="esti_old_stn_wt">'+estval.stone_wt+'</span><input type="hidden" class="est_old_stone_val" name="purchase[stone_wt][]" value="'+estval.stone_wt+'"  /><input type="hidden" class="other_stone_wt" value="'+other_stone_wt+'"  name="purchase[other_stone_wt][]"/><input type="hidden" class="other_stone_price" value="'+other_stone_price+'"   name="purchase[other_stone_price][]" /></td>'

						+'<td><span class="est_old_net_wt">'+estval.net_wt+'</span><input type="hidden" class="est_old_net_val" value="'+estval.net_wt+'"   name="purchase[net_wt][]"/></td>'

						+'<td><span class="est_old_wastage">'+parseFloat(estval.wastage_percent)+'</span><input type="hidden" class="est_old_wastage_percent" name="purchase[wastage_percent][]" value="'+parseFloat(estval.wastage_percent)+'"  /></td>'

						+'<td><span class="est_old_wastage_wt">'+parseFloat(estval.wastage_wt)+'</span><input type="hidden" class="est_old_wastage_val" value="'+parseFloat(estval.wastage_wt)+'" name="purchase[wastage_wt][]" /></td>'

						+'<td><span class="est_old_rate_per_gram">'+parseFloat(estval.rate_per_gram)+'</span><input type="hidden" class="est_old_rate_per_gram_val" value="'+parseFloat(estval.rate_per_gram)+'"  name="purchase[rate_per_gram][]" /></td>'

						+'<td><span class="est_old_amount">'+estval.amount+'</span><input type="hidden" class="est_old_item_amount_val" value="'+estval.amount+'" name="purchase[amount][]" /><input type="hidden" value='+(JSON.stringify(stone_details))+' class="est_item_stone_dt" /><input type="hidden" class="est_stone_price" value="'+estval.stone_price+'"  /></td>'

						+'<td><span class="est_old_est_id">'+parseFloat(estval.est_id)+'</span><input type="hidden" class="est_old_est_id_val" value="'+parseFloat(estval.est_id)+'"  /></td>'

						+'<td><a href="#" onClick="remove_receipt($(this).closest(\'tr\'));" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'

						+'</tr>';

						$('#purchase_item_details tbody').append(row);

						}

						});

						calculate_receipt_item_price();

				}

				else{

					$('#purchase_item_details tbody').empty();

				}

			}

			else{

				//$("div.overlay").css("display", "none"); 

				$('#searchEstiAlert').html(data.message);

			}

        }

     });

}

function remove_receipt(curRow)

{

	curRow.remove();

	calculate_receipt_item_price();

}

$('.cash_pay ,.receive_amount').on('keyup',function(){

	calculate_ReceiptAmount();

});

$('#amount').on('keyup',function(){

	$('.receive_amount').val(this.value);

	calculate_ReceiptAmount();

});

function calculate_receipt_item_price()

{

	var item_price=0;

	var net_wt=0;

	$('#purchase_item_details > tbody tr').each(function(idx, row){

		curRow = $(this);

	    item_price+=parseFloat(curRow.find('.est_old_item_amount_val').val());

	    net_wt+=parseFloat(curRow.find('.est_old_net_val').val());

	});

	var total_amount=parseFloat(item_price);

	$('#amount').val(total_amount);

	$('.receive_amount').val(total_amount);

	$('#weight').val(net_wt);

	calculate_ReceiptAmount();

}

function calculate_ReceiptAmount()

{

	var receipt_type=$("input:radio[name='receipt[receipt_type]']:checked").val();

	var receive_amount=parseFloat($('.receive_amount').val());

	var cc=($('.CC').html()!='' ? $('.CC').html():0);

	var dc=($('.DC').html()!='' ? $('.DC').html():0);

	var chq=($('.CHQ').html()!='' ? $('.CHQ').html():0);

	var NB=($('.NB').html()!='' ? $('.NB').html():0);

	var cash=($('#make_pay_cash').val()!='' ? $('#make_pay_cash').val():0);

	var pay_amount=parseFloat(cash)+parseFloat(cc)+parseFloat(dc)+parseFloat(chq)+parseFloat(NB);

	$('.receipt_total_amount').html(pay_amount);

	$('.receipt_bal_amount').html(parseFloat(parseFloat(receive_amount)-parseFloat(pay_amount)).toFixed(2));

	if($('#is_pan_required').val()==1 && ($('#min_pan_amt').val()<=receive_amount))

	{

		$('#pan_no').prop('disabled',false);

		$('#pan_images').prop('disabled',false);

	}else{

		$('#pan_no').prop('disabled',true);

		$('#pan_images').prop('disabled',true);

	}

	if($('.receipt_bal_amount').html()==0)

	{

		if($('#min_pan_amt').val()<=receive_amount)

		{

				if($('#is_pan_required').val()==1 && ($('#pan_no').val()==''))

				{

					$('#save_receipt').prop('disabled',true);

				}

				else{

					$('#save_receipt').prop('disabled',false);	

				}

		}else{

				$('#save_receipt').prop('disabled',false);		

		}

		

	}

}



$('#save_receipt').on('click',function(){

	var form_data=$('#receipt_billing').serialize();

	console.log(form_data);

	if($('#id_customer').val()!='')

	{

		$('#save_receipt').prop('disabled',true);

		var url=base_url+ "index.php/admin_ret_billing/receipt/save?nocache=" + my_Date.getUTCSeconds();

	    $.ajax({ 

	        url:url,

	        data: form_data,

	        type:"POST",

	        dataType:"JSON",

	        success:function(data){

				if(data.status)

				{

					window.open( base_url+'index.php/admin_ret_billing/receipt/receipt_print/'+data['id'],'_blank');

				}

				window.location.reload();

				$("div.overlay").css("display", "none"); 

	        },

	        error:function(error)  

	        {	

	        $("div.overlay").css("display", "none"); 

	        } 

	    });

	}else{

		$('#save_receipt').prop('disabled',false);	

	}		

});



$('#add_card').on('click',function(){

		if(validateCardDetailRow()){

			$('#payment_modes > tbody >tr').each(function(bidx, brow){

				bill_card_pay_row = $(this);

				bill_card_pay_row.find('.CC').html($('.cc_total_amt').html());

				bill_card_pay_row.find('.DC').html($('.dc_total_amt').html());

				bill_card_pay_row.find('#card_payment').val(card_payment.length>0 ? JSON.stringify(card_payment):'');

			});

			$('#card-detail-modal').modal('toggle');

			calculate_ReceiptAmount();

		}else{

			alert("Please fill required fields");

		}

});

$('#save_chq').on('click',function(){

		if(validateChqDetailRow()){

			$('#payment_modes > tbody >tr').each(function(bidx, brow){

				bill_card_pay_row = $(this);

				bill_card_pay_row.find('.CHQ').html($('.chq_total_amount').html());

				bill_card_pay_row.find('#chq_payment').val(chq_payment.length>0 ? JSON.stringify(chq_payment):'');

			});

			$('#cheque-detail-modal').modal('toggle');

			calculate_ReceiptAmount();

		}else{

			alert("Please fill required fields");

		}

});

$('#save_net_banking').on('click',function(){

		if(validateNBDetailRow()){

			$('#payment_modes > tbody >tr').each(function(bidx, brow){

				bill_card_pay_row = $(this);

				bill_card_pay_row.find('.NB').html($('.nb_total_amount').html());

				bill_card_pay_row.find('#nb_payment').val(nb_payment.length>0 ? JSON.stringify(nb_payment):'');

			});

			$('#net_banking_modal').modal('toggle');

			calculate_ReceiptAmount();

		}else{

			alert("Please fill required fields");

		}

});

//Net banking

//receipt

function get_delivery_details()

{

	my_Date = new Date();

	$.ajax({

		type: 'GET',

		url:base_url+ "index.php/admin_ret_catalog/ret_delivery/ajax?nocache=" + my_Date.getUTCSeconds(),

		dataType:'json',

		success:function(data){

			var id =  $("#select_delivery").val();

			var delivery=data.delivery;

		    $.each(delivery,function (key, item) {

		    	if(item.is_default==1)

		    	{

		    		id=item.id_sale_delivery;

		    	}

			   		$('#select_delivery').append(

						$("<option></option>")

						  .attr("value", item.id_sale_delivery)

						  .text(item.name)

					);

			});

			$("#select_delivery").select2(

			{

				placeholder:"Select Delivery",

				allowClear: true		    

			});  

			$("#select_delivery").select2("val",(id!='' && id>0?id:''));	 

		}

	});

}





function confirm_delete(bill_id)

{



	$('#bill_id').val(bill_id);

	$('#confirm-billcancell').modal('show');

}





$('#cancel_remark').on('keypress',function(){

	if(this.value.length>6)

	{

		$('#cancell_delete').prop('disabled',false);

	}else{

		$('#cancell_delete').prop('disabled',true);

	}

});



$('#cancell_delete').on('click',function(){

	my_Date = new Date();

	$.ajax({

		type: 'POST',

		url:base_url+ "index.php/admin_ret_billing/cancel_bill/ajax?nocache=" + my_Date.getUTCSeconds(),

		dataType:'json',

		data:{'remarks':$('#cancel_remark').val(),'bill_id':$('#bill_id').val()},

		success:function(data){

			

		    window.location.reload();

		}

	});

});





$("input[name='billing[chit_refund]']:checkbox").on("change",function(){

		var bal_amount=$('.bal_amount').html();

		if($("input[name='billing[chit_refund]']:checked").is(":checked"))

		{

		    $('.pay_to_cus').val(parseFloat(bal_amount*(-1)));

		    $('#make_pay_cash').val(parseFloat(bal_amount*(-1)));

		    $('#pay_submit').prop('disabled',false);

		}

		else

		{

		    $('.pay_to_cus').val(0);

		    $('#make_pay_cash').val(0);

		    $('#pay_submit').prop('disabled',true);

		}

	});

	

	

	

//Business Customers



$("input[name='billing[billing_for]']:radio").on('change',function(){

    $('#bill_cus_id').val('');

    $('#bill_cus_name').val('');

    $('#id_cmp_emp').val('');

    $('#bill_emp_name').val('');

    if(this.value==1)

    {

        $('#emp_user').css("display",'none');

    }else{

         $('#emp_user').css("display",'block');

    }

});



$("#bill_emp_name").on("keyup",function(e){ 

	var customer = $("#bill_emp_name").val();

	var bill_cus_id = $("#bill_cus_id").val();

	if(bill_cus_id!='')

	{

	    if(customer.length >= 2) 

    	{ 

    		getSearchCompanyUsers(customer);

    	}

	}else{

	    $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select Company Name'});

	}

	

}); 







function getSearchCompanyUsers(searchTxt){

	my_Date = new Date();

	$.ajax({

        url: base_url+'index.php/admin_ret_billing/getSearchCompanyUsers/?nocache=' + my_Date.getUTCSeconds(),             

        dataType: "json", 

        method: "POST", 

       data: {'searchTxt': searchTxt,'id_customer':$("#bill_cus_id").val()}, 

        success: function (data) {

			$( "#bill_emp_name" ).autocomplete(

			{

				source: data,

				select: function(e, i)

				{ 

					e.preventDefault();

					$("#bill_emp_name").val(i.item.label);

					$("#id_cmp_emp").val(i.item.value);

				},

				change: function (event, ui) {

					if (ui.item === null) {

						$(this).val('');

						$('#bill_emp_name').val('');

					}

			    },

				response: function(e, i) {

		            // ui.content is the array that's about to be sent to the response callback.

		            if(searchTxt != ""){

						if (i.content.length === 0) {

						   $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Enter a valid customer name / mobile..'});

						}

					}else{

					}

		        },

				 minLength: 3,

			});

        }

     });

}





$('#add_cmp_emp').on('click',function(){

    if($('#bill_cus_id').val()!='')

    {

        $('#emp_add').modal('show');

    }

    else

    {

         $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select Company Name'});

    }

});



$("#add_newemployee").on('click', function(){

	if($('#emp_firstname').val() != "")

	{

		if($('#emp_mobile').val() != "")

		{

    		add_company_user();

    		$('#emp_firstname').val('');

    		$('#emp_mobile').val('');

		}

		else

		{

			$.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Enter The Mobile Number'});

		}

	}

	else

	{

		$.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Enter The Name'});

	}

});



function add_company_user(){

	my_Date = new Date();

	$.ajax({

        url: base_url+'index.php/admin_ret_billing/addNewCompanyUsers/?nocache=' + my_Date.getUTCSeconds(),             

        dataType: "json", 

        method: "POST", 

        data: {'emp_name': $('#emp_firstname').val(), 'mobile' : $('#emp_mobile').val(),"id_customer":$('#bill_cus_id').val()},

        success: function (data) { 

			if(data.success == true){

				$('#emp_add').modal('toggle');

				$("#bill_emp_name").val(data.response.firstname + " - " + data.response.mobile);

				$("#id_cmp_emp").val(data.response.id_cmp_emp);

			}else{

			    $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+data.message});

			}

        }

     });

}



//For Tcs calc



function getCompanyPurchaseAmount(id_customer)

{

    my_Date = new Date();

	$.ajax({

        url: base_url+'index.php/admin_ret_billing/getCompanyPurchaseAmount/?nocache=' + my_Date.getUTCSeconds(),             

        dataType: "json", 

        method: "POST", 

        data: {'id_customer': id_customer},

        success: function (data) { 

		    $('#tot_purchase_amt').val(data.tot_purchase_amt);

        }

     });

}





//Business Customers
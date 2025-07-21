var path =  url_params();

var ctrl_page 		= path.route.split('/');

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

	 	case 'sales_transfer':

	        get_metal_rates_by_branch();

	        get_ActiveCategory();

	        get_ActiveMetals();

	        getBTBranches();

	        get_received_lots();

	    break;

	}

	

	$("#lotno").select2({			    

	 	placeholder: "Select Lot",			    

	 	allowClear: true		    

 	});

 	

 	jQuery(".product").on("input", function(){  

 		var id = this.id; 

		var prod = $("#"+id).val();  

		if(prod.length >= 3) {  

			getSearchProd(prod);

        }

	});

	

	$("#design").on("keyup",function(e){ 

		var design = $("#design").val(); 

		if(design.length == 2) { 

			getSearchDesign(design);

        }

	}); 

      

	

});



function getSearchProd(searchTxt){

	my_Date = new Date();

	$.ajax({

        url: base_url+'index.php/admin_ret_catalog/product/active_prodBySearch/?nocache=' + my_Date.getUTCSeconds(),             

        dataType: "json", 

        method: "POST", 

        data: {'searchTxt':searchTxt}, 

        success: function (data) { 

			$( ".product" ).autocomplete(

			{

				source: data,

				select: function(e, i)

				{ 

					e.preventDefault();

					$(".product" ).val(i.item.label); 

					$("#id_product" ).val(i.item.value); 

				},

				response: function(e, i) {

		            // ui.content is the array that's about to be sent to the response callback.

		            if (i.content.length === 0) {

 		               $("#prodAlert").html('<p style="color:red">Enter a valid Product</p>');

		               $('#id_product').val('');

		            }else{

 						$("#prodAlert").html('');

					} 

		        },

				 minLength: 0,

			});

        }

     });

}

function getSearchDesign(searchTxt){

	my_Date = new Date();

	$.ajax({

        url: base_url+'index.php/admin_ret_brntransfer/branch_transfer/getDesignByFilter/?nocache=' + my_Date.getUTCSeconds(),             

        dataType: "json", 

        method: "POST", 

        data: {'searchTxt':searchTxt,'prodId':$("#id_product").val()}, 

        success: function (data) { 

			$( "#design" ).autocomplete(

			{

				source: data,

				select: function(e, i)

				{ 

					e.preventDefault();

					$("#design" ).val(i.item.label); 

					$("#id_design" ).val(i.item.value);  

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



	$(document).on('change',"#select_metal, .from_branch", function(){

		get_received_lots();

	});



function get_received_lots(){

	//trans_type =  $("input[name='transfer_item_type']:checked").val();

	trans_type =  1;

	from_brn = $("#from_brn").val();

	to_brn = $("#to_brn").val();

	if(from_brn != '' && trans_type != ''){

		$.ajax({		

		 	type: 'POST',		

		 	url : base_url + 'index.php/admin_ret_brntransfer/branch_transfer/getLotsByBranch',		

		 	dataType : 'json',		

		 	data : {'from_branch': from_brn,'to_branch': to_brn,'trans_type' : trans_type, 'page' : 'sales_transfer'},

		 	success  : function(data){

				lotDetail = data;

			 	var id =  $('#lotno').val();	

			 	$("#lotno option").remove();

			 	$("#lotno").append(						

		    	 	$("<option></option>")						

		    	 	.attr("value", "")

		    	 	.text("")						  					

	    	 	);			 	

			 	$.each(data, function (key, item) {				  			   		

		    	 	$("#lotno").append(						

		    	 	$("<option></option>")						

		    	 	.attr("value", item.lot_no)

		    	 	.text(item.lot_no)						  					

		    	 	);	 

		     	});						

		     	$("#lotno").select2("val",(id!='' && id>0?id:''));	 

		     	$(".overlay").css("display", "none");			

		 	}	

		}); 	

	}

} 





function getBTBranches(){	 

 	$.ajax({		

     	type: 'GET',		

     	url: base_url+'index.php/admin_ret_brntransfer/bt_get_branches',		

     	dataType:'json',		

     	success:function(data){	

     		var id_branch = "";	

     		branchArr = data;

     		$(".from_branch,.to_branch").select2({			    

        	 	placeholder: "Select Branch",			    

        	 	allowClear: true		    

         	});		  

    	  	$.each(data, function (key, item) { 

    	  	    

    	  		if(loggedInBranch > 0)

                { 

                    if(loggedInBranch == item.id_branch)

                    {

                        var loggedGst = item.gst_number;

                        console.log('loggedGst',loggedGst);

                    }

	    	  		if(ctrl_page[1] == 'sales_transfer'){

	    	  		    

	    	  		    if(ctrl_page[2]=='add')

	    	  		    {

	    	  		        var sales_transfer_item_type =  $("input[name='sales_transfer_item_type']:checked").val(); 

	    	  		    }else

	    	  		    {

	    	  		        var sales_transfer_item_type =  $("input[name='sales_ret_transfer_item_type']:checked").val(); 

	    	  		    }

	    	  		    

	    	  			from_brn = (sales_transfer_item_type == 1 ? (loggedInBranch > 0 ? loggedInBranch : $("#filter_from_brn").val()):$("#filter_from_brn").val());

			            to_brn = (sales_transfer_item_type == 2 ? (loggedInBranch > 0 ? loggedInBranch : $("#filtr_to_brn").val()):$("#filtr_to_brn").val());

						if(sales_transfer_item_type == 1){ // Transit Approval

                           

							if(loggedInBranch != item.id_branch && (loggedGst==item.gst_number)){

					    	 	 $(".to_branch,.to_branch").append(						

					        	 	$("<option></option>")						

					        	 	.attr("value", item.id_branch)						  						  

					        	 	.attr("data-gst_number", item.gst_number)						  						  

					        	 	.text(item.name )						  					

					    	 	 ); 

						 	 }else{ 

            		    	    $(".from_branch").attr("disabled",true);

                			 	$(".from_branch").append(						

                		        	 	$("<option></option>")						

                		        	 	.attr("value", item.id_branch)

                		        	 	.attr("data-gst_number", item.gst_number)

                		        	 	.text(item.name )						  					

                		    	 ); 

            			 	    $(".from_branch").select2("val",from_brn);

            			 	 }

						 	 $(".to_branch").select2('val','');

						 	 /*if(loggedInBranch == item.id_branch){			        	 	

					    	 	 $(".app_frm_brn").css("display","none");  

						 	} */

						}else{ // Stock Download 

				        	 if(loggedInBranch != item.id_branch){			        	 	

				        	 	 $(".from_branch").append(						

					        	 	$("<option></option>")						

					        	 	.attr("value", item.id_branch)

					        	 	.attr("data-gst_number", item.gst_number)

					        	 	.text(item.name )						  					

				        	 	 ); 

			        	 	 }else{ 

                			 	 $(".to_branch").append(						

                		        	 	$("<option></option>")						

                		        	 	.attr("value", item.id_branch)

                		        	 	.attr("data-gst_number", item.gst_number)

                		        	 	.text(item.name )						  					

                		    	 );

            			 	    $(".to_branch").attr("disabled",true); 

            			 	    $(".to_branch").select2("val",to_brn);

			        	 	 }

			        	 	 /*if(loggedInBranch == item.id_branch){

			        	 	 	$(".app_to_brn").css("display","none");   

			        	 	 }  */			        	 	 

						}

					}else{

						if(loggedInBranch != item.id_branch){		  				  			   		

			        	 	$(".from_branch").append(						

				        	 	$("<option></option>")						

				        	 	.attr("value", item.id_branch)

				        	 	.attr("data-gst_number", item.gst_number)

				        	 	.text(item.name )						  					

			        	 	);	

			        	 }	

			        	 if(loggedInBranch != item.id_branch){			        	 	

			        	 	 $(".to_branch,.to_branch").append(						

				        	 	$("<option></option>")						

				        	 	.attr("value", item.id_branch)

				        	 	.attr("data-gst_number", item.gst_number)

				        	 	.text(item.name )						  					

			        	 	 ); 

		        	 	 } 

		        	 	 $(".from_branch,.to_branch").select2("val","");   

					}

					

				}else{

					$(".from_branch,.to_branch").append(						

		        	 	$("<option></option>")						

		        	 	.attr("value", item.id_branch)

		        	 	.attr("data-gst_number", item.gst_number)

		        	 	.text(item.name )						  					

	        	 	);			

         			$(".from_branch,.to_branch").select2("val","");   

				} 										

         	}); 

         //	$(".from_branch").select2("val",id_branch);    

     	}	

    }); 

}





$(".from_branch,#filter_from_brn").on('change', function(e){

		var isDisabled = $(".to_branch,.to_branch ").prop('disabled');  

		if(this.value != '' && !isDisabled){ 

			$("#to_brn option,.to_branch option").remove();

			$("#to_brn,.to_branch").val(null).trigger('change');

			

			var gst_number = $('.from_branch option:selected').attr('data-gst_number');

			console.log('gst_number:'+gst_number);

		 	$(".from_branch option,#filter_from_brn option").each(function()

			{  	

			    var to_brch_gst_number = $(this).attr('data-gst_number');

				if(($(this).val() != $(".from_branch,#filter_from_brn").val()) && (gst_number!=to_brch_gst_number))

				{   			   		

		    	 	$("#to_brn,.to_branch").append(						

		    	 	$("<option></option>")						

		    	 	.attr("value", $(this).val())

		    	 	.text($(this).text())						  					

		    	 	.attr("data-gst_number",to_brch_gst_number)						  					

		    	 	);	  

				}	

				$("#to_brn,.to_branch").val('');		    	

			});	

			/*if(ctrl_page[2] == 'add'){

				get_received_lots();

			}*/		 

				

		}

	});



//SALES TRANSFER

	

	

	

	

	$('input[type=radio][name="sales_transfer_item_type"]').change(function() {

	    $('.sales_trans').css("display","none");

	    $('.sales_trans_download').css("display","none");

        $('.sales_trans_download_search').css('display','none');

        $('.remark').css("display","none");

	    if(this.value==1)      //sales transfer

	    {

	        $('.sales_trans').css("display","block");

			$('.sales_trans_bill_no').css("display","none");

	        $('.sales_trans_tag_no').css("display","block");

	        // $('.sales_trans_calc_type').css("display","block");

            $('#tag_scan_code').css("display","none");

            $('.sales_submit').css('display','block');

            $('#bill_dwnload_list').css('display','none');

            $('#bill_approval_list_by_scan').css('display','none');

            $('.remark').css("display","block");

	        

	    }else if(this.value==2) //sales retrun transfer

	    {

            $('.sales_trans_download_search').css('display','block');

	        $('.sales_trans_bill_no').css("display","block");

	        $('.sales_trans_tag_no').css("display","none");

	        $('.sales_trans_calc_type').css("display","none");

            var sales_trans_dnload = $('#sales_trans_dnload').val();

            if(sales_trans_dnload==2)

            {

                //$('#tag_scan_code').css("display","block");

                $('.sales_submit').css('display','none');

                //$('.sales_trans_download').css("display","none");



            }

            else

            {

                //$('#tag_scan_code').css("display","none");

                $('.sales_submit').css('display','block');

                $('.sales_trans_download').css("display","block");



            }

	    }

	    

	    $(".from_branch").attr("disabled",false);

	    $(".to_branch").attr("disabled",false); 

	    $(".from_branch option,.to_branch option").remove();

	    $.each(branchArr, function (key, item) { 

    	  	    

    	  		if(loggedInBranch > 0){ 



                    if(loggedInBranch == item.id_branch)

                    {

                        var loggedGst = item.gst_number;

                        console.log('loggedGst',loggedGst);

                    }



	    	  		if(ctrl_page[1] == 'sales_transfer'){

	    	  			if(ctrl_page[2]=='add')

	    	  		    {

	    	  		        var sales_transfer_item_type =  $("input[name='sales_transfer_item_type']:checked").val(); 

	    	  		    }else

	    	  		    {

	    	  		        var sales_transfer_item_type =  $("input[name='sales_ret_transfer_item_type']:checked").val(); 

	    	  		    }

	    	  			from_brn = (sales_transfer_item_type == 1 ? (loggedInBranch > 0 ? loggedInBranch : $("#filter_from_brn").val()):$("#filter_from_brn").val());

			            to_brn = (sales_transfer_item_type == 2 ? (loggedInBranch > 0 ? loggedInBranch : $("#filtr_to_brn").val()):$("#filtr_to_brn").val());

						if(sales_transfer_item_type == 1){ // Transit Approval  

							if(loggedInBranch != item.id_branch && loggedGst==item.gst_number){

					    	 	 $(".to_branch,.to_branch").append(						

					        	 	$("<option></option>")						

					        	 	.attr("value", item.id_branch)						  						  

					        	 	.attr("data-gst_number", item.gst_number)						  						  

					        	 	.text(item.name )						  					

					    	 	 ); 

						 	 }else{ 

            		    	    $(".from_branch").attr("disabled",true);

                			 	$(".from_branch").append(						

                		        	 	$("<option></option>")						

                		        	 	.attr("value", item.id_branch)

                		        	 	.attr("data-gst_number", item.gst_number)

                		        	 	.text(item.name )						  					

                		    	 ); 

            			 	    $(".from_branch").select2("val",from_brn);

            			 	 }

						 	 $(".to_branch").select2('val','');

						 	 /*if(loggedInBranch == item.id_branch){			        	 	

					    	 	 $(".app_frm_brn").css("display","none");  

						 	} */

						}else{ // Stock Download 

				        	 if(loggedInBranch != item.id_branch){			        	 	

				        	 	 $(".from_branch").append(						

					        	 	$("<option></option>")						

					        	 	.attr("value", item.id_branch)

					        	 	.attr("data-gst_number", item.gst_number)

					        	 	.text(item.name )						  					

				        	 	 ); 

			        	 	 }else{ 

                			 	 $(".to_branch").append(						

                		        	 	$("<option></option>")						

                		        	 	.attr("value", item.id_branch)

                		        	 	.attr("data-gst_number", item.gst_number)

                		        	 	.text(item.name )						  					

                		    	 );

            			 	    $(".to_branch").attr("disabled",true); 

            			 	    $(".to_branch").select2("val",to_brn);

			        	 	 }

			        	 	 $(".from_branch").select2('val','');

			        	 	 /*if(loggedInBranch == item.id_branch){

			        	 	 	$(".app_to_brn").css("display","none");   

			        	 	 }  */			        	 	 

						}

					}else{

						if(loggedInBranch != item.id_branch){		  				  			   		

			        	 	$(".from_branch").append(						

				        	 	$("<option></option>")						

				        	 	.attr("value", item.id_branch)

				        	 	.attr("data-gst_number", item.gst_number)

				        	 	.text(item.name )						  					

			        	 	);	

			        	 }	

			        	 if(loggedInBranch != item.id_branch){			        	 	

			        	 	 $(".to_branch,.to_branch").append(						

				        	 	$("<option></option>")						

				        	 	.attr("value", item.id_branch)

				        	 	.attr("data-gst_number", item.gst_number)

				        	 	.text(item.name )						  					

			        	 	 ); 

		        	 	 } 

		        	 	 $(".from_branch,.to_branch").select2("val","");   

					}

					

				}else{

					$(".from_branch,.to_branch").append(						

		        	 	$("<option></option>")						

		        	 	.attr("value", item.id_branch)

		        	 	.attr("data-gst_number", item.gst_number)

		        	 	.text(item.name )						  					

	        	 	);			

         			$(".from_branch,.to_branch").select2("val","");   

				} 										

         	});

         	

	});





	$('input[type=radio][name="sales_ret_transfer_item_type"]').change(function() {

	    $('.sales_trans').css("display","none");

	    $('.sales_trans_download').css("display","none");

	    $('#aganist_bill_yes').prop('disabled',false);

	    $('#aganist_bill_no').prop('disabled',false);

        $('.sales_trans_download_search').css('display','none');

        $('.remark').css('display','none');

	    if(this.value==1)      //sales ret request

	    {

	        $('.sales_trans').css("display","block");

			$('.sales_trans_bill_no').css("display","block");

            $('#ret_bill_approval_list_by_scan').css("display","none");

            $('#ret_tag_scan_code').css("display","none");

            $('.sales_submit').css('display','block');

            $('#ret_bill_dwnload_list').css('display','none');

            $('.remark').css('display','block');

	        

	    }else if(this.value==2) //sales retrun download

	    {

	        $('.sales_trans_download_search').css("display","block");

	        $('.sales_trans_bill_no').css("display","block");

	        $('.sales_trans_tag_no').css("display","none");

	        $('.sales_trans_calc_type').css("display","none");

	        $('#aganist_bill_yes').prop('disabled',true);

	        $('#aganist_bill_no').prop('disabled',true);



            var sales_ret_trans_dnload = $('#sales_ret_trans_dnload').val();

            if(sales_ret_trans_dnload==2)

            {

                $('.sales_submit').css('display','none');



            }

            else

            {

                $('.sales_submit').css('display','block');

                $('.sales_trans_download').css("display","block");



            }

	    }

	    

	    $(".from_branch").attr("disabled",false);

	    $(".to_branch").attr("disabled",false); 

	    $(".from_branch option,.to_branch option").remove();

	    $.each(branchArr, function (key, item) { 

    	  	    

    	  		if(loggedInBranch > 0){ 

                    if(loggedInBranch == item.id_branch)

                    {

                        var loggedGst = item.gst_number;

                        console.log('loggedGst',loggedGst);

                    }

	    	  		if(ctrl_page[1] == 'sales_transfer'){

	    	  			

	    	  		    var sales_transfer_item_type =  $("input[name='sales_ret_transfer_item_type']:checked").val(); 

	    	  			from_brn = (sales_transfer_item_type == 1 ? (loggedInBranch > 0 ? loggedInBranch : $("#filter_from_brn").val()):$("#filter_from_brn").val());

			            to_brn = (sales_transfer_item_type == 2 ? (loggedInBranch > 0 ? loggedInBranch : $("#filtr_to_brn").val()):$("#filtr_to_brn").val());

						if(sales_transfer_item_type == 1){ // Transit Approval  

							if(loggedInBranch != item.id_branch && (loggedGst==item.gst_number)){

					    	 	 $(".to_branch,.to_branch").append(						

					        	 	$("<option></option>")						

					        	 	.attr("value", item.id_branch)						  						  

					        	 	.attr("data-gst_number", item.gst_number)						  						  

					        	 	.text(item.name )						  					

					    	 	 ); 

						 	 }else{ 

            		    	    $(".from_branch").attr("disabled",true);

                			 	$(".from_branch").append(						

                		        	 	$("<option></option>")						

                		        	 	.attr("value", item.id_branch)

                		        	 	.attr("data-gst_number", item.gst_number)

                		        	 	.text(item.name )						  					

                		    	 ); 

            			 	    $(".from_branch").select2("val",from_brn);

            			 	 }

						 	 $(".to_branch").select2('val','');

						 	 /*if(loggedInBranch == item.id_branch){			        	 	

					    	 	 $(".app_frm_brn").css("display","none");  

						 	} */

						}else{ // Stock Download 

				        	 if(loggedInBranch != item.id_branch){			        	 	

				        	 	 $(".from_branch").append(						

					        	 	$("<option></option>")						

					        	 	.attr("value", item.id_branch)

					        	 	.attr("data-gst_number", item.gst_number)

					        	 	.text(item.name )						  					

				        	 	 ); 

			        	 	 }else{ 

                			 	 $(".to_branch").append(						

                		        	 	$("<option></option>")						

                		        	 	.attr("value", item.id_branch)

                		        	 	.attr("data-gst_number", item.gst_number)

                		        	 	.text(item.name )						  					

                		    	 );

            			 	    $(".to_branch").attr("disabled",true); 

            			 	    $(".to_branch").select2("val",to_brn);

			        	 	 }

			        	 	 $(".from_branch").select2('val','');

			        	 	 /*if(loggedInBranch == item.id_branch){

			        	 	 	$(".app_to_brn").css("display","none");   

			        	 	 }  */			        	 	 

						}

					}else{

						if(loggedInBranch != item.id_branch){		  				  			   		

			        	 	$(".from_branch").append(						

				        	 	$("<option></option>")						

				        	 	.attr("value", item.id_branch)

				        	 	.attr("data-gst_number", item.gst_number)

				        	 	.text(item.name )						  					

			        	 	);	

			        	 }	

			        	 if(loggedInBranch != item.id_branch){			        	 	

			        	 	 $(".to_branch,.to_branch").append(						

				        	 	$("<option></option>")						

				        	 	.attr("value", item.id_branch)

				        	 	.attr("data-gst_number", item.gst_number)

				        	 	.text(item.name )						  					

			        	 	 ); 

		        	 	 } 

		        	 	 $(".from_branch,.to_branch").select2("val","");   

					}

					

				}else{

					$(".from_branch,.to_branch").append(						

		        	 	$("<option></option>")						

		        	 	.attr("value", item.id_branch)

		        	 	.attr("data-gst_number", item.gst_number)

		        	 	.text(item.name )						  					

	        	 	);			

         			$(".from_branch,.to_branch").select2("val","");   

				} 										

         	});

         	

	});

	

	$('input[type=radio][name="aganist_bill"]').change(function() {

	    var trans_type =  $("input[name='sales_ret_transfer_item_type']:checked").val();

	    $('.bill_no').css("display","none");

	    if(this.value==1)      //sales ret request

	    {

	        $('.bill_no').css("display","block");



	    }

	});

	

	

$('.sales_transfer_search').on('click',function(){

            if($('#from_brn').val()=='' || $('#from_brn').val()==null)

            {

                $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select From Branch..'});

            }

            else if($('#to_brn').val()=='' || $('#to_brn').val()==null)

            {

                $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select To Branch..'});

            }

            /*else if($('#select_category').val()=='' || $('#select_category').val()==null)

            {

                $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select The Category..'});

            }*/

            /*else if($('#tag_no').val()=='' && $('#old_tag_no').val()=='')

            {

                $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Enter The Tag Code.'});

            }*/

            else if($('#rate_per_gram').val()=='' && $('#rate_per_gram').val()==0)

            {

                $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Enter The Rate.'});

            }

            else

            {

				get_sales_transfer_tag_list();

            }

	});

	



	$('.sales_ret_transfer_search').on('click',function(){

	        var is_aganist_bill =  $("input[name='aganist_bill']:checked").val();

            if($('#from_brn').val()=='' || $('#from_brn').val()==null)

            {

                $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select From Branch..'});

            }

            else if($('#to_brn').val()=='' || $('#to_brn').val()==null)

            {

                $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select To Branch..'});

            }

            else if($('#bill_no').val()=='' && is_aganist_bill==1)

            {

                $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Enter The Bill No'});

            }

            else

            {

                get_sales_return_transfer_tag_list();

            }

	});



	$('.sales_ret_transfer_approval_search').on('click',function(){

	    //trans_type =  $("input[name='sales_transfer_item_type']:checked").val();

            if($('#from_brn').val()=='' || $('#from_brn').val()==null)

            {

                $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select From Branch..'});

            }

            else if($('#to_brn').val()=='' || $('#to_brn').val()==null)

            {

                $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select To Branch..'});

            }

            /*else if($('#pur_rate').val()=='')

            {

                $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Enter The Purchase Rate..'});

            }*/

            /*else if($('#bt_code').val()=='' && $('#tag_no').val()=='')

            {

                $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Enter The Branch Transfer Code or Tag Code'});

            }*/

            else

            {

                get_sales_return_approval_list();

            }

	});





	function get_sales_return_approval_list()

	{

		my_Date = new Date();

        $.ajax({

        url:base_url+ "index.php/admin_ret_sales_transfer/sales_transfer/sales_return_trans_approval_tag?nocache=" + my_Date.getUTCSeconds(),

        data: {'bill_no':$("#bill_no").val(),'from_brn':$("#from_brn").val(),'to_brn':$("#to_brn").val(),'fin_year_code':$('#fin_year_code').val()},

        dataType:"JSON",

        type:"POST",

        cache:false,

        success:function(data){

        $(".overlay").css("display", "none");

                var searchResList = data;

                $('#total').text(data.length);

              

                if (data!= null && data.length > 0)

                {   

                    var sales_ret_trans_dnload = $('#sales_ret_trans_dnload').val();



                    if(sales_ret_trans_dnload==1)

                    {



                        $('#bt_search_download_list  > tbody').empty();

                        $.each(data, function (key, val) {

                        html='';

                        rowExist=false;

                            /*$('#bt_search_download_list > tbody tr').each(function(bidx, brow){

                                    bt_tagid = $(this);

                                    if( val.bill_id == bt_tagid.find('.bill_id').val())

                                    {

                                        rowExist = true;

                                        //$.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Tag Already Exists..'});

                                    }

                                        

                                });*/

                            if(!rowExist){

                              

                                    html = 

                                    '<tr>'+

                                    '<td><input type="checkbox" name="bill_id[]" class="bill_id" value='+val.bill_id+'>'+val.bill_no+'</td>'+

                                    '<td>'+val.bill_date+'</td>'+

                                    '<td>'+val.piece+'</td>'+

                                    '<td>'+val.gross_wt+'</td>'+

                                    '<td><a href="#"onClick="remove_sales_trans_row($(this).closest(\'tr\'));" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'

                                    '</tr>';

                                   

                                    if($('#bt_search_download_list  > tbody > tr').length > 0 )

                                    {

                                        $('#bt_search_download_list > tbody > tr:first').before(html);

                                    }

                                    else

                                    {

                                        $('#bt_search_download_list > tbody').append(html);

                                    }

                            }

                        });



                    }

                    else

                    {

                        ret_bill_download_by_scan(data);

                    }



                    $(".sales_ret_transfer_approval_search ").prop('disabled', true);

                }   

                else

                {

                    $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'No Records Found..'});

					$('#bill_no').val('');

					$("#bill_no").focus();



                    $(".sales_ret_transfer_approval_search ").prop('disabled', false);

                }

            },

            error:function(error)  

            {

            $("div.overlay").css("display", "none");

            }

        });

	}

	

	$('.sales_transfer_approval_search').on('click',function(){

	    trans_type =  $("input[name='sales_transfer_approval_item_type']:checked").val();

	    

	        if($('#from_brn').val()=='' || $('#from_brn').val()==null)

	        {

	            $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select From Branch..'});

	        }

	        else if($('#to_brn').val()=='' || $('#to_brn').val()==null)

	        {

	            $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select To Branch..'});

	        }

	        else if($('#bill_no').val()=='')

	        {

	            $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Enter The Bill No..'});

	        }

	        else

	        {

	            get_sales_transfer_approval_list();

	        }

	});

	

	

	function get_sales_transfer_approval_list()

	{

	    my_Date = new Date();

        $.ajax({

        url:base_url+ "index.php/admin_ret_sales_transfer/sales_transfer/sales_trans_approval_tag?nocache=" + my_Date.getUTCSeconds(),

        data: {'bill_no':$("#bill_no").val(),'from_brn':$("#from_brn").val(),'to_brn':$("#to_brn").val(),'fin_year_code':$('#fin_year_code').val()},

        dataType:"JSON",

        type:"POST",

        cache:false,

        success:function(data){

        $(".overlay").css("display", "none");

                var searchResList = data;

                $('#total').text(data.length);

              

                if (data!= null && data.length > 0)

                {   var sales_trans_dnload = $('#sales_trans_dnload').val();



                    if(sales_trans_dnload==1)

                    {



                    $('#bt_search_download_list  > tbody').empty();

                    $.each(data, function (key, val) {

                    html='';

                    rowExist=false;

                           $('#bt_search_download_list > tbody tr').each(function(bidx, brow){

                                bt_tagid = $(this);

                                if( val.bill_id == bt_tagid.find('.bill_id').val())

                                {

                                    rowExist = true;

                                    //$.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Tag Already Exists..'});

                                }

                                     

                            });

                            if(!rowExist){

                              

                                    html = 

                                    '<tr>'+

                                    '<td><input type="checkbox" name="bill_id[]" class="bill_id" value='+val.bill_id+'>'+val.bill_no+'</td>'+

                                    '<td>'+val.bill_date+'</td>'+

                                    '<td>'+val.piece+'</td>'+

                                    '<td>'+val.gross_wt+'</td>'+

                                    '<td><a href="#"onClick="remove_sales_trans_row($(this).closest(\'tr\'));" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'

                                    '</tr>';

                                   

                                    if($('#bt_search_download_list  > tbody > tr').length > 0 )

                                    {

                                        $('#bt_search_download_list > tbody > tr:first').before(html);

                                    }

                                    else

                                    {

                                        $('#bt_search_download_list > tbody').append(html);

                                    }

                            }

                     });

                    }

                    else

                    {

                        bill_download_by_scan(data);



                    }



                    $(".sales_transfer_approval_search").prop('disabled', true);

                }   

                else

                {

                    $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'No Records Found..'});

					$('#bill_no').val('');

					$("#bill_no").focus();

                    $(".sales_transfer_approval_search").prop('disabled', false);

                }

            },

            error:function(error)  

            {

            $("div.overlay").css("display", "none");

            }

        });

	}

	

	

	function get_sales_return_transfer_tag_list()

    {

        var is_aganist_bill =  $("input[name='aganist_bill']:checked").val();

        my_Date = new Date();

        $.ajax({

        url:base_url+ "index.php/admin_ret_sales_transfer/sales_transfer/sales_return_trans_tag?nocache=" + my_Date.getUTCSeconds(),

        data: {'is_aganist_bill':is_aganist_bill,'bt_code':$("#bt_code").val(),'tag_code':$("#tag_no").val(),'old_tag_code':$("#old_tag_no").val(),'from_brn':$("#from_brn").val(),'to_brn':$("#to_brn").val(),'cat_id':$('#select_category').val(),'bill_no':$('#bill_no').val(),'fin_year_code':$('#fin_year_code').val()},

        dataType:"JSON",

        type:"POST",

        cache:false,

        success:function(data){

        $(".overlay").css("display", "none");

                var searchResList = data;

                $('#total').text(data.length);

              

                if (data!= null && data.length > 0)

                {   

                    if(is_aganist_bill == 1)

                    {

                        $.each(data, function (key, val) {

                        html='';

                        rowExist=false;

                               $('#bt_search_list > tbody tr').each(function(bidx, brow){

                                    bt_tagid = $(this);

                                    if( val.cat_id == bt_tagid.find('.cat_id').val())

                                    {

                                        rowExist = true;

                                        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Category Already Exists..'});

                                    }

                                         

                                });

                                if(!rowExist){

                                   

                                      html = 

                                        '<tr>'+

                                        '<td><input type="checkbox" name="cat_id[]" class="cat_id" value='+val.cat_id+'>'+val.category_name+'</td>'+

                                        '<td>'+val.gross_wt+'</td>'+

                                        '<td><input type="hidden" name="total_amt[]" class="item_cost" value='+val.item_cost+'>'+val.item_cost+'</td>'+

                                        '<td><a href="#"onClick="remove_sales_trans_row($(this).closest(\'tr\'));" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'

                                        '</tr>';

                                       

                                        if($('#bt_search_list  > tbody > tr').length > 0 )

                                        {

                                            $('#bt_search_list > tbody > tr:first').before(html);

                                        }

                                        else

                                        {

                                            $('#bt_search_list > tbody').append(html);

                                        }

                                }

                         });

                    }

                    else

                    {

                        $.each(data, function (key, val) {

                        html='';

                        rowExist=false;

                               $('#bt_search_list > tbody tr').each(function(bidx, brow){

                                    bt_tagid = $(this);

                                    if( val.bill_det_id == bt_tagid.find('.bill_det_id').val())

                                    {

                                        rowExist = true;

                                        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Tag No Already Exists..'});

                                    }

                                         

                                });

                                if(!rowExist){

                                   

                                      html = 

                                        '<tr>'+

                                        '<td><input type="checkbox" name="bill_det_id[]" class="bill_det_id" value='+val.bill_det_id+'><input type="hidden" name="bill_id[]" class="bill_id" value='+val.bill_id+'><input type="hidden" name="tag_id[]" class="tag_id" value='+val.tag_id+'>'+val.tag_code+'</td>'+

                                        '<td>'+val.gross_wt+'</td>'+

                                        '<td><input type="hidden" name="total_amt[]" class="item_cost" value='+val.item_cost+'>'+val.item_cost+'</td>'+

                                        '<td><a href="#"onClick="remove_sales_trans_row($(this).closest(\'tr\'));" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'

                                        '</tr>';

                                       

                                        if($('#bt_search_list  > tbody > tr').length > 0 )

                                        {

                                            $('#bt_search_list > tbody > tr:first').before(html);

                                        }

                                        else

                                        {

                                            $('#bt_search_list > tbody').append(html);

                                        }

                                }

                         });

                    }

                    $('#tag_no').val('');

					$("#tag_no").focus();

                }   

                else

                {

                    $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'No Records Found..'});

					$('#tag_no').val('');

					$("#tag_no").focus();

                }

            },

            

            error:function(error)  

            {

            $("div.overlay").css("display", "none");

            }

        });

    

    }    

	

	function get_sales_transfer_tag_list()

    {

        my_Date = new Date();

        $.ajax({

        url:base_url+ "index.php/admin_ret_sales_transfer/sales_transfer/sales_trans_tag?nocache=" + my_Date.getUTCSeconds(),

        data: {'bt_code':$("#bt_code").val(),'tag_code':$("#tag_no").val(),'old_tag_code':$("#old_tag_no").val(),'from_brn':$("#from_brn").val(),'cat_id':$('#select_category').val(), 'design_id':$("#id_design").val(),'prodId':$("#id_product").val(),'lotno':$("#lotno").val(), 'tag_no':$("#tag_no").val(),'id_metal':$('#select_metal').val()},

        

        dataType:"JSON",

        type:"POST",

        cache:false,

        success:function(data){

        $(".overlay").css("display", "none");

                var searchResList = data;

                $('#total').text(data.length);

              

                if (data!= null && data.length > 0)

                {   

                    $.each(data, function (key, val) {

                    html='';

                    rowExist=false;

                           $('#bt_search_list > tbody tr').each(function(bidx, brow){

                                bt_tagid = $(this);

                                if( val.tag_id == bt_tagid.find('.tag_id').val())

                                {

                                    rowExist = true;

                                    $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Tag No Already Exists..'});

                                }

                            });

                            if(!rowExist){

                                let from_branch_country='';

                                let from_branch_state='';

                                

                                let to_branch_country='';

                                let to_branch_state='';

                                

                                var tax_amount=0;

                                var cgst_amt = 0;

                                var sgst_amt = 0;

                                var igst_amt = 0;

                                

                                 $.each(branchArr, function (key, item) { 

									if($("#from_brn").val()==item.id_branch)

									{

									from_branch_country=item.id_country;

									from_branch_state=item.id_state;

									}

									

									if($("#to_brn").val()==item.id_branch)

									{

									to_branch_country=item.id_country;

									to_branch_state=item.id_state;

									}

                                     

                                 });



                                    html = 

                                    '<tr>'+

                                    '<td><input type="checkbox" name="tag_id[]" class="tag_id" value='+val.tag_id+'><input type="hidden" name="piece[]" class="piece" value='+val.piece+'><input type="hidden" name="gross_wt[]" class="gross_wt" value='+val.gross_wt+'>'+val.tag_code+'</td>'+

                                    '<td>'+val.category_name+'</td>'+

                                    '<td>'+val.piece+'</td>'+

                                    '<td>'+val.gross_wt+'</td>'+

                                    '<td>'+val.less_wt+'<input type="hidden" name="less_wt[]" class="less_wt" value='+val.less_wt+'><input type="hidden" name="product_id[]" class="product_id" value='+val.product_id+'><input type="hidden" name="design_id[]" class="design_id" value='+val.design_id+'><input type="hidden" name="id_sub_design[]" class="id_sub_design" value='+val.id_sub_design+'></td>'+

                                    '<td>'+val.net_wt+'<input type="hidden" name="net_wt[]" class="net_wt" value='+val.net_wt+'><input type="hidden" name="metal_code[]" class="metal_code" value='+val.metal_code+'><input type="hidden" name="id_metal[]" class="id_metal" value='+val.id_metal+'><input type="hidden" name="calculation_based_on[]" class="calculation_based_on" value='+val.calculation_based_on+'><input type="hidden" name="purity[]" class="purity" value='+val.purity+'></td>'+

                                    '<td><Select class="form-control calc_type"><option value="1">Per Gram</option><option value="2">Per Piece</option></select></td>'+

                                    '<td><input type="number"  class="form-control pur_cost" value="'+$('#rate_per_gram').val()+'"></td>'+

                                    '<td><input type="hidden" name="total_amt[]" class="item_cost" value=""><span class="total_cost"></span></td>'+

                                    '<td><a href="#"onClick="remove_sales_trans_row($(this).closest(\'tr\'));" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'

                                    '</tr>';

                                   

                                    if($('#bt_search_list  > tbody > tr').length > 0 )

                                    {

                                        $('#bt_search_list > tbody > tr:first').before(html);

                                    }

                                    else

                                    {

                                        $('#bt_search_list > tbody').append(html);

                                    }

                                    calculate_sales_trans_details();

                            }

                     });

                    $('#tag_no').val('');

                    $('#old_tag_no').val('');

					$("#tag_no").focus();

                }   

                else

                {

                    $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'No Records Found..'});

					$('#tag_no').val('');

					$('#old_tag_no').val('');

					$("#tag_no").focus();

                }

            },

            

            error:function(error)  

            {

            $("div.overlay").css("display", "none");

            }

        });

    

    }



    

    $(document).on('change','.calc_type',function(){

        calculateSaleBillRowTotal();

    });



	$(document).on('keyup','.pur_cost',function(){

		calculateSaleBillRowTotal();

	});

    

    function calculateSaleBillRowTotal()

    {

        $('#bt_search_list > tbody tr').each(function(idx, row){

		    curRow = $(this);

		    

		    var taxable_amt=0;

		    var tax_amount =0;

			// var tot_amount = 0;

		    

		    var piece       = curRow.find('.piece').val();

		    var gross_wt    = curRow.find('.gross_wt').val();

		    var calc_type   = curRow.find('.calc_type').val();

		    var pur_cost    = (curRow.find('.pur_cost').val()!='' ? curRow.find('.pur_cost').val():0);

		    

		    if(calc_type==1)

			{

			    taxable_amt  = parseFloat(parseFloat(gross_wt)*parseFloat(pur_cost)).toFixed(2);

			}

			else{

			    taxable_amt  = parseFloat(parseFloat(piece)*parseFloat(pur_cost)).toFixed(2);

			}

			

			tax_amount       = parseFloat((taxable_amt)*3/100).toFixed(2);



			tot_amount = parseFloat(taxable_amt) + parseFloat(tax_amount);

			

			curRow.find('.item_cost').val(parseFloat(tot_amount).toFixed(2));

			curRow.find('.total_cost').html(parseFloat(tot_amount).toFixed(2));

		    

        });

    }

    

    

    function remove_sales_trans_row(curRow)

    {

    	curRow.remove();

    	calculate_sales_trans_details();

    }



    function calculate_sales_trans_details()

    {

        calculateSaleBillRowTotal();

        var total_pcs = 0;

        var tot_gross_wt = 0;

        var tot_lwt = 0;

        var tot_nwt = 0;

        var total_cost = 0;

         $('#bt_search_list > tbody tr').each(function(bidx, brow){

            bt_tagid = $(this);

            total_pcs += parseFloat(bt_tagid.find('.piece').val());

            tot_gross_wt += parseFloat(bt_tagid.find('.gross_wt').val());

            tot_lwt += parseFloat(bt_tagid.find('.less_wt').val());

            tot_nwt += parseFloat(bt_tagid.find('.net_wt').val());

            total_cost += parseFloat(bt_tagid.find('.item_cost').val());

         });

         $('.tot_bt_pcs').html(parseFloat(total_pcs));

         $('.tot_bt_gross_wt').html(parseFloat(tot_gross_wt).toFixed(3));

         $('.tot_lwt').html(parseFloat(tot_lwt).toFixed(3));

         $('.tot_nwt').html(parseFloat(tot_nwt).toFixed(3));

         $('.total_item_cost').html(parseFloat(total_cost).toFixed(2));

         

    }

    



function validateSalesRequestRow()

{

    var validate = true;

	$('#bt_search_list > tbody  > tr').each(function(index, tr) {

	    if($(this).find("input[name='tag_id[]']:checked").is(":checked"))

	    {

	        if($(this).find('.pur_cost').val() == "" || $(this).find('.pur_cost').val() == 0 || $(this).find('.cat_id').val() ==""){

    			validate = false;

    		}

	    }

		

	});

	return validate;

}



	

$('#sales_trans_submit').on('click',function(){

     $('#sales_trans_submit').prop('disabled',true);

    trans_type =  $("input[name='sales_transfer_item_type']:checked").val();

    

    if(trans_type==1) // Sales Transfer Request

    {

        if(validateSalesRequestRow())

        {

            if($("input[name='tag_id[]']:checked").val())

            {

                var selected = [];

                var approve=false;

                var item_cost=0;

                $("#bt_search_list tbody tr").each(function(index, value)

                {

                if($(value).find("input[name='tag_id[]']:checked").is(":checked"))

                {

                    item_cost+=parseFloat($(value).find(".item_cost").val());

                    transData = { 

                    'tag_id'                : $(value).find(".tag_id").val(),

                    'piece'                 : $(value).find(".piece").val(),

                    'gross_wt'              : $(value).find(".gross_wt").val(),

                    'less_wt'               : $(value).find(".less_wt").val(),

                    'net_wt'                : $(value).find(".net_wt").val(),

                    'metal_code'            : $(value).find(".metal_code").val(),

                    'product_id'            : $(value).find(".product_id").val(),

                    'design_id'             : $(value).find(".design_id").val(),

                    'id_sub_design'         : $(value).find(".id_sub_design").val(),

                    'calculation_based_on'  : $(value).find(".calculation_based_on").val(),

                    'purity'                : $(value).find(".purity").val(),

                    'id_metal'              : $(value).find(".id_metal").val(),

                    'item_cost'             : $(value).find(".item_cost").val(),

                    'calc_type'             : $(value).find(".calc_type").val(),

                    'rate_per_grm'          : $(value).find(".pur_cost").val(),

                    'bt_code'          		: $(value).find(".bt_code").val(),

                    }

                    selected.push(transData);	

                }

                })

                req_data = selected;

                $("div.overlay").css("display", "block"); 

                create_sales_transfer(req_data,item_cost);

            }

            else

            {

                $("div.overlay").css("display", "none"); 

                $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select The Category..'});

                 $('#sales_trans_submit').prop('disabled',false);

            }

        }

        else

        {

            $("div.overlay").css("display", "none"); 

            $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Enter The Purchase Cost..'});

             $('#sales_trans_submit').prop('disabled',false);

        }

    }

    else if(trans_type==2)

    {

        if($("input[name='bill_id[]']:checked").val())

        {

            var selected = [];

            var approve=false;

            $("#bt_search_download_list tbody tr").each(function(index, value)

            {

                if($(value).find("input[name='bill_id[]']:checked").is(":checked"))

                {

                    transData = { 

                    'bill_id'                : $(value).find(".bill_id").val(),

                    }

                    selected.push(transData);

                }

            });

            req_data = selected;

            update_sales_transfer_request(req_data);

        }

        else

        {

            $("div.overlay").css("display", "none"); 

             $('#sales_trans_submit').prop('disabled',false);

            alert('Please Select Any One Bill.');

        }

    }

});



function create_sales_transfer(req_data,item_cost)

{

   

    my_Date = new Date();

    

    $.ajax({

    url:base_url+ "index.php/admin_ret_sales_transfer/create_sales_transfer?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),

    data:  {'from_brn':$('#from_brn').val(),'to_brn':$('#to_brn').val(),'req_data':req_data,'tot_bill_amount':item_cost,'form_secret':$('#form_secret').val(),'id_metal':$('#select_metal').val(),'remark':$('#remark').val()},

    type:"POST",

    async:false,

    dataType: "json",

    success:function(data){

       	if(data.status)

		{

		    

		    $.toaster({ priority : 'success', title : 'Warning!', message : ''+"</br>"+data.message});

			window.open( base_url+'index.php/admin_ret_billing/billing_invoice/'+data.id,'_blank');

			$('#sales_trans_submit').prop('disabled',false);

			window.location.reload();

		}

		else

		{

		    $("div.overlay").css("display", "none"); 

		    $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+data.message});

		    $('#sales_trans_submit').prop('disabled',false);

		}

		

    },

    error:function(error)  

    {

        console.log(error);

        $("div.overlay").css("display", "none"); 

    }	 

    });

}





function get_metal_rates_by_branch()

{

	var id_branch = '';

	my_Date = new Date();

	$.ajax({

		url:base_url+ "index.php/admin_ret_tagging/get_metal_rates_by_branch?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),

		data:  {'id_branch':id_branch},

		type:"POST",

		dataType: "json",

		async:false,

		success:function(data){

			rate_details=data;

		},

		error:function(error)  

		{

			$("div.overlay").css("display", "none");

		}

	});

}









function update_sales_transfer_request(req_data)

{

    $('#sales_ret_trans_submit').prop('disabled',true);

    my_Date = new Date();

    $("div.overlay").css("display", "block"); 

    $.ajax({

    url:base_url+ "index.php/admin_ret_sales_transfer/update_sales_transfer_request?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),

    data:  {'from_brn':$('#from_brn').val(),'to_brn':$('#to_brn').val(),'req_data':req_data,'bill_no':$('#bill_no').val(),'form_secret':$('#form_secret').val()},

    type:"POST",

    async:false,

    dataType: "json",

    success:function(data){

       	if(data.status)

		{

		    $("div.overlay").css("display", "none"); 

		    $.toaster({ priority : 'success', title : 'Warning!', message : ''+"</br>"+data.message});

		    $('#sales_ret_trans_submit').prop('disabled',false);

		}

		else

		{

		    $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+data.message});

		    $('#sales_ret_trans_submit').prop('disabled',false);

		}

		

		window.location.reload();

        $("div.overlay").css("display", "none"); 

    },

    error:function(error)  

    {

        console.log(error);

        $("div.overlay").css("display", "none"); 

    }	 

    });

}









	function get_sales_return_branch_trasnfer()

	

	{

	    my_Date = new Date();

        $.ajax({

        url:base_url+ "index.php/admin_ret_brntransfer/sales_transfer/sales_return_trans_tag?nocache=" + my_Date.getUTCSeconds(),

        data: {'tag_code':$("#tag_code").val(),'from_brn':$("#from_brn").val(),'to_brn':$("#to_brn").val()},

        dataType:"JSON",

        type:"POST",

        cache:false,

        success:function(data){

        $(".overlay").css("display", "none");

                var searchResList = data;

                $('#total').text(data.length);

              

                if (data!= null && data.length > 0)

                {   

                    

                    

                    

                     $.each(data, function (key, val) {

                    html='';

                    rowExist=false;

                           $('#bt_search_list > tbody tr').each(function(bidx, brow){

                                bt_tagid = $(this);

                                if( val.tag_id == bt_tagid.find('.tag_id').val())

                                {

                                    rowExist = true;

                                    //$.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Tag Already Exists..'});

                                }

                                     

                            });

                            if(!rowExist){

                                let from_branch_country='';

                                let from_branch_state='';

                                

                                let to_branch_country='';

                                let to_branch_state='';

                                

                                var tax_amount=0;

                                var cgst_amt = 0;

                                var sgst_amt = 0;

                                var igst_amt = 0;

                                

                                 $.each(branchArr, function (key, item) { 

                                     if($("#from_brn").val()==item.id_branch)

                                     {

                                        from_branch_country=item.id_country;

                                        from_branch_state=item.id_state;

                                     }

                                     

                                     if($("#to_brn").val()==item.id_branch)

                                     {

                                        to_branch_country=item.id_country;

                                        to_branch_state=item.id_state;

                                     }

                                     

                                 });

                                var taxable_amt  = parseFloat(parseFloat(val.gross_wt)*parseFloat(rate_details.silverrate_1gm)).toFixed(2);

                                if(from_branch_country==to_branch_country)

                                {

                                    tax_amount       = parseFloat((taxable_amt)*3/100).toFixed(2);

                                    if(from_branch_state==to_branch_state)

                                    {

                                        cgst_amt=parseFloat(parseFloat(tax_amount)/2).toFixed(2);

                                        sgst_amt=parseFloat(parseFloat(tax_amount)/2).toFixed(2);

                                    }else{

                                        igst_amt=tax_amount;

                                    }

                                }

                                

                                var total_amt    = parseFloat(parseFloat(taxable_amt)+parseFloat(tax_amount)).toFixed(2);

                                    html = 

                                    '<tr>'+

                                    '<td><input type="checkbox" name="tag_id[]" class="tag_id" value='+val.tag_id+'></td>'+

                                    '<td><input type="hidden" name="tag_code[]" class="tag_code" value='+val.tag_code+'>'+val.tag_code+'</td>'+

                                    '<td><input type="hidden" name="id_lot_inward_detail[]" class="id_lot_inward_detail" value='+val.lot_no+'></input>'+val.lot_no+'</td>'+

                                    '<td><input type="hidden" name="product[]" class="product" value='+val.product+'>'+val.product+'</td>'+

                                    '<td><input type="hidden" name="design[]" class="design" value='+val.design+'>'+val.design+'</td>'+

                                    '<td><input type="hidden" name="tag_datetime[]" class="tag_datetime" value='+val.tag_datetime+'>'+val.tag_datetime+'</td>'+

                                    '<td><input type="hidden" name="piece[]" class="piece" value='+val.piece+'>'+val.piece+'</td>'+

                                    '<td><input type="hidden" name="gross_wt[]" class="gross_wt" value='+val.gross_wt+'>'+val.gross_wt+'</td>'+

                                    '<td><input type="hidden" name="net_wgt[]" class="net_wgt" value='+val.net_wt+'>'+val.net_wt+'</td>'+

                                    '<td><input type="hidden" name="rate_per_grm[]" class="rate_per_grm" value='+rate_details.silverrate_1gm+'><input type="hidden" name="igst_amt[]" class="igst_amt" value='+igst_amt+'><input type="hidden" name="sgst_amt[]" class="sgst_amt" value='+sgst_amt+'><input type="hidden" name="purity[]" class="purity" value='+val.purity+'><input type="hidden" name="product_id[]" class="product_id" value='+val.product_id+'><input type="hidden" name="design_id[]" class="design_id" value='+val.design_id+'><input type="hidden" name="calculation_based_on[]" class="calculation_based_on" value='+val.calculation_based_on+'><input type="hidden" name="item_cost[]" class="item_cost" value='+total_amt+'><input type="hidden" name="tax_amount[]" class="tax_amount" value='+tax_amount+'><input type="hidden" name="cgst_amt[]" class="cgst_amt" value='+cgst_amt+'>'+total_amt+'</td>'+

                                    '<td><a href="#"onClick="remove_sales_trans_row($(this).closest(\'tr\'));" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>'

                                    '</tr>';

                                   

                                    if($('#bt_search_list  > tbody > tr').length > 0 )

                                    {

                                        $('#bt_search_list > tbody > tr:first').before(html);

                                    }

                                    else

                                    {

                                        $('#bt_search_list > tbody').append(html);

                                    }

                                    console.log('rate_per_gram:'+rate_details.silverrate_1gm);

                                    console.log('taxable_amt:'+taxable_amt);

                                    console.log('tax_amount:'+tax_amount);

                                    console.log('total_amt:'+total_amt);

                                    

                            }

                     });

                    

                    calculate_sales_trans_details();

                    

                    

                }   

                else

                {

                    $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'No Records Found..'});

					$('#tag_no').val('');

					$("#tag_no").focus();

                }

            },

            error:function(error)  

            {

            $("div.overlay").css("display", "none");

            }

        });

	}





$('#sales_return_trans_select_all').click(function(event) {

	$("#bt_search_download_list tbody tr td input[type='checkbox']").prop('checked', $(this).prop('checked'));

});



$('#select_all').click(function(event) {

	$("#bt_search_list tbody tr td input[type='checkbox']").prop('checked', $(this).prop('checked'));

});





function get_ActiveMetals()

{

	$.ajax({

		type: 'GET',

		url: base_url+'index.php/admin_ret_catalog/ret_product/active_metal',

		dataType:'json',

		success:function(data){

			var id =  $("#select_metal").val();

		    $.each(data,function (key, item) {

			   		$('#select_metal').append(

						$("<option></option>")

						  .attr("value", item.id_metal)

						  .text(item.metal)

					);

			});

			$("#select_metal").select2(

			{

				placeholder:"Select Metal",

				allowClear: true		    

			});  	

		}

	});

}



$('#select_metal').on('change',function(){

   if(ctrl_page[1]=='sales_transfer' && ctrl_page[2]=='add')

   {

       $('#bt_search_list > tbody').empty();

   }

});



function get_ActiveCategory()

{

    $.ajax({

	type: 'GET',

	url: base_url+'index.php/admin_ret_catalog/category/active_category',

	dataType:'json',

	success:function(data){

		var id =  $("#select_category").val();

		$.each(data, function (key, item) {   

		    $("#select_category").append(

		    $("<option></option>")

		    .attr("value", item.id_ret_category)    

		    .text(item.name)  

		    );

		});

		$("#select_category").select2(

		{

			placeholder:"Select Category",

			allowClear: true		    

		});





		    $("#select_category").select2("val",(id!='' && id>0?id:''));

		    $(".overlay").css("display", "none");

		}

	});

}



$("#select_calculation_type").select2(

	{

		placeholder:"Select Calculation Type",

		allowClear: true		    

	});





//SALES TRANSFER







$('#sales_ret_trans_submit').on('click',function(){

      $("div.overlay").css("display", "block"); 

    trans_type =  $("input[name='sales_ret_transfer_item_type']:checked").val();

    $('#sales_ret_trans_submit').prop('disabled',true);

    if(trans_type==1) // Sales ret Transfer Request

    {

        var is_aganist_bill =  $("input[name='aganist_bill']:checked").val();

        if(is_aganist_bill ==1)

        {

             if($("input[name='cat_id[]']:checked").val())

            {

                var selected = [];

                var approve=false;

                var item_cost=0;

                $("#bt_search_list tbody tr").each(function(index, value)

                {

                if($(value).find("input[name='cat_id[]']:checked").is(":checked"))

                {

                    item_cost+=parseFloat($(value).find(".item_cost").val());

                    transData = { 

                    'cat_id'                : $(value).find(".cat_id").val(),

                    'item_cost'             : $(value).find(".item_cost").val(),

                    'rate_per_grm'          : $(value).find(".rate_per_grm").val(),

                    }

                    selected.push(transData);	

                }

                })

                req_data = selected;

                create_sales_ret_transfer(req_data,item_cost);

            }

            else

            {

                $('#sales_ret_trans_submit').prop('disabled',false);

                alert('Please Select Any One Tag.');

                  $("div.overlay").css("display", "none"); 

            }

        }

        else if(is_aganist_bill ==0)

        {

            if($("input[name='bill_det_id[]']:checked").val())

            {

                var selected = [];

                var approve=false;

                var item_cost=0;

                $("#bt_search_list tbody tr").each(function(index, value)

                {

                if($(value).find("input[name='bill_det_id[]']:checked").is(":checked"))

                {

                    item_cost+=parseFloat($(value).find(".item_cost").val());

                    transData = { 

                    'bill_det_id'           : $(value).find(".bill_det_id").val(),

                    'bill_id'               : $(value).find(".bill_id").val(),

                    'tag_id'                : $(value).find(".tag_id").val(),

                    'item_cost'             : $(value).find(".item_cost").val(),

                    }

                    selected.push(transData);	

                }

                })

                req_data = selected;

                create_sales_ret_transfer(req_data,item_cost);

            }

            else

            {

                $('#sales_ret_trans_submit').prop('disabled',false);

                alert('Please Select Any One Tag.');

                $("div.overlay").css("display", "none"); 

            }

        }

    }

    else if(trans_type==2)

    {

        if($("input[name='bill_id[]']:checked").val())

        {

            var selected = [];

            var approve=false;

            $("#bt_search_download_list tbody tr").each(function(index, value)

            {

                if($(value).find("input[name='bill_id[]']:checked").is(":checked"))

                {

                    transData = { 

                    'bill_id'                : $(value).find(".bill_id").val(),

                    }

                    selected.push(transData);	

                }

            });

            req_data = selected;

            update_sales_ret_transfer_request(req_data);

        }

        else

        {

            $('#sales_ret_trans_submit').prop('disabled',false);

            alert('Please Select Any One Bill.');

            $("div.overlay").css("display", "none"); 

        }

    }

});





function create_sales_ret_transfer(req_data,item_cost)

{

    var is_aganist_bill =  $("input[name='aganist_bill']:checked").val();

    if(is_aganist_bill==1)

    {

        var baseUrl = base_url+ "index.php/admin_ret_sales_transfer/create_sales_ret_transfer?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours();

    

    }else

    {

        var baseUrl = base_url+ "index.php/admin_ret_sales_transfer/create_cus_sales_ret_transfer?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours();

    

    }

    my_Date = new Date();

    $.ajax({

    url:baseUrl,

    data:  {'from_brn':$('#from_brn').val(),'to_brn':$('#to_brn').val(),'req_data':req_data,'tot_bill_amount':item_cost,'bill_no':$('#bill_no').val(),'fin_year_code':$('#fin_year_code').val(),'form_secret':$('#form_secret').val()},

    type:"POST",

    async:false,

    dataType: "json",

    success:function(data){

       	if(data.status)

		{

		    $("div.overlay").css("display", "none"); 

		    $.toaster({ priority : 'success', title : 'Warning!', message : ''+"</br>"+data.message});

			window.open( base_url+'index.php/admin_ret_billing/billing_invoice/'+data.id,'_blank');

			window.location.reload();

		}

		else

		{

		    $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+data.message});

		}

		

        $("div.overlay").css("display", "none"); 

    },

    error:function(error)  

    {

        console.log(error);

        $("div.overlay").css("display", "none"); 

    }	 

    });

}





function update_sales_ret_transfer_request(req_data)

{

    my_Date = new Date();

    $("div.overlay").css("display", "block"); 

    $.ajax({

    url:base_url+ "index.php/admin_ret_sales_transfer/update_sales_ret_transfer?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),

    data:  {'from_brn':$('#from_brn').val(),'to_brn':$('#to_brn').val(),'req_data':req_data,'bill_no':$('#bill_no').val(),'fin_year_code':$('#fin_year_code').val(),'form_secret':$('#form_secret').val()},

    type:"POST",

    async:false,

    dataType: "json",

    success:function(data){

       	if(data.status)

		{

		    $("div.overlay").css("display", "none"); 

		    $.toaster({ priority : 'success', title : 'Warning!', message : ''+"</br>"+data.message});

		}

		else

		{

		    $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+data.message});

		}

		window.location.reload();

        $("div.overlay").css("display", "none"); 

    },

    error:function(error)  

    {

        console.log(error);

        $("div.overlay").css("display", "none"); 

    }	 

    });

}







/*Sales Transfer Tag Scan*/



function bill_download_by_scan(data)

{

    console.log(data);

    console.log((data.length > 0));

    if(data.length > 0)

    {

        $('#bill_approval_list_by_scan').css('display','table');



        $("#tag_scan_code").css("display", "block");



        $("#scan_tag_no").focus();	



        scan_dwload_data = data[0].bill_tags;

        var $actual_pcs = 0;

        $.each(scan_dwload_data, function (key, item) 

        {

            $actual_pcs += parseFloat(item.piece);

            console.log('act_pcs',$actual_pcs);

            $('#actual_pcs_dnload').val( $actual_pcs);

        });



        var html='';



        html+='<tr>'+

            '<td>'+data[0].bill_no+'</td>'+

            '<td>'+data[0].bill_date+'</td>'+

            '<td>'+data[0].piece+'</td>'+

            '<td>'+parseFloat(data[0].gross_wt).toFixed(3)+'</td>'+

        '</tr>';

        $('#bill_approval_list_by_scan > tbody').append(html);



    }

}



$(document).on('keyup', '#scan_tag_no', function(e) {



    if(e.which==13)

    {

        console.log(scan_dwload_data);



        var scanned_tags_list = localStorage.getItem("scanned_tags");



        var stored_tags = JSON.parse(scanned_tags_list);



        console.log(stored_tags);



        if(Array.isArray(stored_tags))

		{

            $.each(stored_tags, function (key, item) {

                if($("#scan_tag_no").val() == item)

                {

                    $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Tag Already Scanned..'});

                    $("#scan_tag_no").val("");

                    $("#scan_tag_no").focus();

                }

            });

		}



        getscan_TagSearchList($("#scan_tag_no").val(),'');

        $("#scan_tag_no").val("");

        $("#scan_tag_no").focus();

    }

    

});



$(document).on('keyup', '#old_tag_code', function(e) {

   

    if(e.which==13)

    {

        console.log(scan_dwload_data);



        var scanned_tags_list = localStorage.getItem("scanned_tags");



        var stored_tags = JSON.parse(scanned_tags_list);



        console.log(stored_tags);



        if(Array.isArray(stored_tags))

		{

            $.each(stored_tags, function (key, item) {

                if($("#old_tag_code").val() == item)

                {

                    $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Tag Already Scanned..'});

                    $("#old_tag_code").val("");

                    $("#old_tag_code").focus();

                }

            });

		}



       getscan_TagSearchList('',$("#old_tag_code").val());

       $("#old_tag_code").val("");

        $("#old_tag_code").focus();

    }

    

});





function getscan_TagSearchList(tag_code,old_tag_code)

{

    if($('#scan_tag_no').val()=='' && $('#old_tag_code').val()=='')

    {

        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Enter The Tag Code..'});

    }

    else

    {

        $('#bill_dwnload_list').css('display','block');

        $.ajax({

            url:base_url+ "index.php/admin_ret_sales_transfer/sales_transfer/getTagsByFilter?nocache=" + my_Date.getUTCSeconds(),

            data: {'bill_no':$("#bill_no").val(),'from_brn':$("#from_brn").val(),'to_brn':$("#to_brn").val(),'fin_year_code':$('#fin_year_code').val(),'tag_code':$('#scan_tag_no').val(),'old_tag_code':$('#old_tag_code').val()},

            dataType:"JSON",

            type:"POST",

            cache:false,

            success:function(data)

            {

                console.log(data)

                if(data.length == 0)

    			{

    				 $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Tag Already download..'});

    			}

                else

                {

                    if(data!=null && data.length>0)

                    {

                        html='';

                        rowExist=false;

    

                        $('#bill_dwnload_list > tbody tr').each(function(bidx, brow)

                        {

                            bill_tagid = $(this);

                            if(bill_tagid.find('.tag_code').val() != '')

                            {

                                if( tag_code == bill_tagid.find('.tag_code').val())

                                {

                                    rowExist = true;

                                    $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Tag Already download..'});

                                } 

    

                            }

                        });

    

                        if(!rowExist)

                        {

                            $.each(data,function(key,val)

                            {

                                html+='<tr>'+

                                   

                                    '<td><input type="hidden" name="tag_code[]" class="tag_id" value='+val.tag_id+'>'+val.tag_code+'</td>'+

                                    '<td><input type="hidden" name="product[]" class="product" value='+val.design+'>'+val.product_name+'</td>'+

                                    '<td><input type="hidden" name="piece[]" class="piece" value='+val.piece+'>'+val.piece+'</td>'+

                                    '<td><input type="hidden" name="gross_wt[]" class="gross_wt" value='+val.gross_wt+'>'+val.gross_wt+'</td>'+

                                 

                                '</tr>'; 

                                

                                $.ajax({

                                    type: 'POST',		

    								url : base_url + 'index.php/admin_ret_sales_transfer/update_TagScan',

                                    dataType : 'json',	

                                    data : {'bill_id':val.bill_id,'tag_code':val.tag_code,'tag_id':val.tag_id,

                                    'from_brn':$("#from_brn").val(),'to_brn':$("#to_brn").val(),'actual_pcs':$('#actual_pcs_dnload').val()},

                                    success  : function(data)

                                    {

                                        if(data != '')

    								  {

    									 if(data == 'completed')

    									 {

    										localStorage.clear();

    										$.toaster({ priority : 'success', title : 'Success!', message : ''+"</br>"+'This Bill Downloaded Successfully..'});

    										$("#scan_tag_no").prop('disabled', true);

    										$("#old_tag_code").prop('disabled', true);

    										$("#scan_tag_no").blur(); 

    										window.location.reload(); 

    										

    									 }else

    									 {

    										 

    										    //scanned_tags.push(val.tag_code);

    											//localStorage.setItem('scanned_tags', JSON.stringify(scanned_tags));

    											

    											dnloaded_tags = localStorage.getItem("scanned_tags");

    										  if (Array.isArray(JSON.parse(dnloaded_tags))) {

    											

    											dnloaded_tags_new = JSON.parse(dnloaded_tags).push(val.tag_code);

    											

    											localStorage.setItem("scanned_tags", JSON.stringify(dnloaded_tags_new));

    										  }else {

    											 

    											scanned_tags.push(val.tag_code);

    											localStorage.setItem('scanned_tags', JSON.stringify(scanned_tags));

    										   

    										  }

    											

    										 $("#scan_tag_no").focus();

    										$.toaster({ priority : 'success', title : 'Success!', message : ''+"</br>"+'Tag Downloaded Successfully..'});

    									 }

    								  }

                                     else

                                        {

                                            $("#scan_tag_no").focus();

                                            $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Unable to download this tag..'});

                                        }

    

                                    }

                                })

    

                                if($('#bill_dwnload_list  > tbody > tr').length > 0 )

                                {

                                $('#bill_dwnload_list > tbody > tr:first').before(html);

                                }

                                else

                                {

                                $('#bill_dwnload_list > tbody').append(html);

                                }

    

                            })

    

                        }

                    }

                }

            }

        });

    }

}



/*Sales Transfer Tag Scan*/





/*Sales Return Transfer Tag Scan*/



function ret_bill_download_by_scan(data)

{

    console.log(data);



    if(data.length>0)

    {

        $('#ret_bill_approval_list_by_scan').css('display','table');



        $("#ret_tag_scan_code").css("display", "block");



        $("#ret_scan_tag_no").focus();	



        ret_scan_dwload_data = data[0].ret_bill_tags;



        var actual_ret_pcs = 0;



        $.each(ret_scan_dwload_data,function(key,item)

        {

            actual_ret_pcs+=parseFloat(item.piece);



            $('#ret_actual_pcs_dnload').val(actual_ret_pcs);



        })



        var html=''



        html+='<tr>'+

            '<td>'+data[0].bill_no+'</td>'+

            '<td>'+data[0].bill_date+'</td>'+

            '<td>'+data[0].piece+'</td>'+

            '<td>'+parseFloat(data[0].gross_wt).toFixed(3)+'</td>'+

        '</tr>';



        $('#ret_bill_approval_list_by_scan > tbody').append(html);



    }



}







$("#ret_scan_tag_no").keypress(function(e) 

{

    if(e.which==13)

    {

        console.log(ret_scan_dwload_data);



        var scanned_tags_list = localStorage.getItem("scanned_tags");



        var stored_tags = JSON.parse(scanned_tags_list);



        console.log(stored_tags);



        if(Array.isArray(stored_tags))

		{

            $.each(stored_tags, function (key, item) {

                if($("#ret_scan_tag_no").val() == item)

                {

                    $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Tag Already Scanned..'});

                    $("#ret_scan_tag_no").val("");

                    $("#ret_scan_tag_no").focus();

                }

            });

		}



        $.each(ret_scan_dwload_data, function (key, item) 

        {

			console.log(item.tag_code);

            if ($("#ret_scan_tag_no").val() == item.tag_code)

            {

                get_retscan_TagSearchList($("#ret_scan_tag_no").val());

                $("#ret_scan_tag_no").val("");

                $("#ret_scan_tag_no").focus();

            }

	    });

    }

    

});







function get_retscan_TagSearchList(tag_code)

{

    console.log(tag_code);



    $('#ret_bill_dwnload_list').css('display','block');



    $.ajax({

        url:base_url+ "index.php/admin_ret_sales_transfer/sales_transfer/getReturnTagsByFilter?nocache=" + my_Date.getUTCSeconds(),

        data: {'bill_no':$("#bill_no").val(),'from_brn':$("#from_brn").val(),'to_brn':$("#to_brn").val(),'fin_year_code':$('#fin_year_code').val(),'tag_code':$('#ret_scan_tag_no').val()},

        dataType:"JSON",

        type:"POST",

        cache:false,

        success:function(data)

        {

            console.log(data)

            if(data.length == 0)

			{

				 $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Tag Already download..'});

			}

            else

            {

                if(data!=null && data.length>0)

                {

                    html='';

                    rowExist=false;



                    $('#ret_bill_dwnload_list > tbody tr').each(function(bidx, brow)

                    {

                        ret_bill_tagid = $(this);

                        if(ret_bill_tagid.find('.ret_tag_code').val() != '')

                        {

                            if( tag_code == ret_bill_tagid.find('.ret_tag_code').val())

                            {

                                rowExist = true;

                                $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Tag Already download..'});

                            } 



                        }

                    });



                    if(!rowExist)

                    {

                        $.each(data,function(key,val)

                        {

                            html+='<tr>'+

                               

                                '<td><input type="hidden" name="tag_code[]" class="tag_id" value='+val.tag_id+'>'+val.tag_code+'</td>'+

                                '<td><input type="hidden" name="product[]" class="product" value='+val.product+'>'+val.product_name+'</td>'+

                                '<td><input type="hidden" name="piece[]" class="piece" value='+val.piece+'>'+val.piece+'</td>'+

                                '<td><input type="hidden" name="gross_wt[]" class="gross_wt" value='+val.gross_wt+'>'+val.gross_wt+'</td>'+

                             

                            '</tr>'; 

                            

                            $.ajax({

                                type: 'POST',		

								url : base_url + 'index.php/admin_ret_sales_transfer/update_ret_TagScan',

                                dataType : 'json',	

                                data : {'bill_id':val.bill_id,'tag_code':val.tag_code,'tag_id':val.tag_id,'ref_bill_id':val.ref_bill_id,

                                'from_brn':$("#from_brn").val(),'to_brn':$("#to_brn").val(),'actual_pcs':$('#ret_actual_pcs_dnload').val()},

                                success  : function(data)

                                {

                                    if(data != '')

								  {

									 if(data == 'completed')

									 {

										localStorage.clear();

										$.toaster({ priority : 'success', title : 'Success!', message : ''+"</br>"+'This Bill Downloaded Successfully..'});

										$("#ret_scan_tag_no").prop('disabled', true);

										$("#ret_scan_tag_no").blur(); 

										window.location.reload(); 

										

									 }else

									 {

										 

										    //scanned_tags.push(val.tag_code);

											//localStorage.setItem('scanned_tags', JSON.stringify(scanned_tags));

											

											dnloaded_tags = localStorage.getItem("scanned_tags");

										  if (Array.isArray(JSON.parse(dnloaded_tags))) {

											

											dnloaded_tags_new = JSON.parse(dnloaded_tags).push(val.tag_code);

											

											localStorage.setItem("scanned_tags", JSON.stringify(dnloaded_tags_new));

										  }else {

											 

											scanned_tags.push(val.tag_code);

											localStorage.setItem('scanned_tags', JSON.stringify(scanned_tags));

										   

										  }

											

										 $("#scan_tag_no").focus();

										$.toaster({ priority : 'success', title : 'Success!', message : ''+"</br>"+'Tag Downloaded Successfully..'});

									 }

								  }

                                 else

                                    {

                                        $("#scan_tag_no").focus();

                                        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Unable to download this tag..'});

                                    }



                                }

                            })



                            if($('#ret_bill_dwnload_list  > tbody > tr').length > 0 )

                            {

                            $('#ret_bill_dwnload_list > tbody > tr:first').before(html);

                            }

                            else

                            {

                            $('#ret_bill_dwnload_list > tbody').append(html);

                            }



                        })



                    }

                }

            }

        }

    })

}









/*Sales Return Transfer Tag Scan*/




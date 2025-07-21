//general setings Functions here

var path =  url_params();

var ctrl_page = path.route.split('/');

console.log(ctrl_page);

 $(document).ready(function(){ 
     if(ctrl_page[1]!='access')
     {
         get_branchnames();
     }
     
	 set_modules_list();
     set_paymentgateway_list();
     if(ctrl_page['1']=='module')
     {
          set_modules_list();
     }
     if(ctrl_page['1']=='payment')
     {
         get_offrate_list();
     }
      
	 //get_branchname()
     if(ctrl_page['1']=='payment')
     {
         set_paymentgateway_list();
     } 
	 
	 if(ctrl_page['1']=='terms_and_conditions')
	 {
	     get_terms_and_conditions();
	     if($('#content').length > 0)

         {
        
         	CKEDITOR.replace('content');
        
         }
	     
	    
	 }

	 if(ctrl_page['1']=='retail_setting')
     {
          set_retail_settings_list();
     }
	 

	 //Offline Rate List Admin with date picker//HH 
	$('#offrate_list1').empty();
            $('#offrate_list2').empty();
            $('#offrate_list1').text(moment().startOf('month').format('YYYY-MM-DD'));
             $('#offrate_list2').text(moment().endOf('month').format('YYYY-MM-DD'));

			$('#offrate-dt-btn').daterangepicker(
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

		             
						$('#offrate_list1').text(start.format('YYYY-MM-DD'));
						$('#offrate_list2').text(end.format('YYYY-MM-DD'));
                           var id_branch=$("#branch_select").val();
           					
                       get_offrate_list(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'),id_branch)
		          }

	        );
	  //Offline Rate List Admin with date picker//HH
	 
	 $("input[name='metal_type']").click(function () {
        if ($("#metal_type_partial").is(":checked")) {
            $("#partial_silverate").removeAttr("disabled");
            $("#partial_goldrate").removeAttr("disabled");
            $("#partial_silverate").focus();
            $("#partial_goldrate").focus();
        } else {
			$('#partial_silverate').val(0);  
			$('#partial_goldrate').val(0);
            $("#partial_silverate").attr("disabled", "disabled");
            $("#partial_goldrate").attr("disabled", "disabled");
        }
    });
	
    $("input[name='ed_metal_type']").click(function () {
        if ($("#rate_partial").is(":checked")) {
            $("#ed_partial_silverate").removeAttr("disabled");
            $("#ed_partial_goldrate").removeAttr("disabled");
            $("#ed_partial_silverate").focus();
            $("#ed_partial_goldrate").focus();
        } else {
			$('#ed_partial_goldrate').val(0);  
			$('#ed_partial_silverate').val(0);
            $("#ed_partial_silverate").attr("disabled", "disabled");
            $("#ed_partial_goldrate").attr("disabled", "disabled");
        }
    }); 
    
	  $('body').on('focus',"#entry_date", function(){
		$('#entry_date').datetimepicker({ format: 'yyyy-mm-dd',
		});
		});
	 var edit_custom_entry_date= $("#edit_custom_entry_date").is(':checked') ? 1 : 0;
	if(edit_custom_entry_date==0)
		{
			 $('#entry_date').attr('disabled',true);
		}
		else
		{
			$('#entry_date').attr('disabled',false);
		}
	 
	$("input[name='general[edit_custom_entry_date]']:checkbox").on('change',function(){
		var edit_custom_entry_date= $("#edit_custom_entry_date").is(':checked') ? 1 : 0;
		if(edit_custom_entry_date==0)
		{
			 $('#entry_date').attr('disabled',true);
			  $('#entry_date').val('');
		}
		else
		{
		    $('#entry_date').val('');
			$('#entry_date').attr('disabled',false);
		}
	 });
	 
     if($('#is_branchwise_cus_reg').val()==1  )
       {
       	get_branchname();
       }
		//mail settings
	if($('#send_through:checked').val()==0)
	{	
      
		 $("input[name='mail[server_type]']:radio").attr('disabled',true);
		 $('#smtp_pass').attr('disabled',true);
		 $('#smtp_host').attr('disabled',true);
		 $('#smtp_user	').attr('disabled',true);
		
	}
	if($('#server_type:checked').val()==1)
	{
		
		 $('#smtp_pass').attr('required',true);
		 $('#smtp_host').attr('required',true);
		 $('#smtp_user	').attr('required',true);
		
	}
		 $("input[name='mail[server_type]']:radio").on('change',function(){
		   if($(this).val()==1)
		   {
		   		
			   	$('#smtp_pass').attr('required',true);
				 $('#smtp_host').attr('required',true);
				 $('#smtp_user	').attr('required',true);
			 
		   }
		   else
		   {
			 	$('#smtp_pass').attr('required',false);
				 $('#smtp_host').attr('required',false);
				 $('#smtp_user	').attr('required',false);

		   }
		   
	});
	 $("input[name='mail[send_through]']:radio").on('change',function(){
		   if($(this).val()==0)
		   {
		   		
		 $("input[name='mail[server_type]']:radio").attr('disabled',true);
			  $('#smtp_pass').attr('disabled',true);
		 $('#smtp_host').attr('disabled',true);
		 $('#smtp_user	').attr('disabled',true);
			 
		   }
		   else
		   {
		 $("input[name='mail[server_type]']:radio").attr('disabled',false);
			 $('#smtp_pass').attr('disabled',false);
		 $('#smtp_host').attr('disabled',false);
		 $('#smtp_user	').attr('disabled',false);

		   }
		   
	});
	//mail settings
	
	

	$("#update_clsfy").on('click',function(){

		var clsfy = {

        classification_name:  $("#ed_clsfy").val(),

        description:  CKEDITOR.instances.description1.getData(),

        	file : $("#edit_sch_clsfy_img")[0].files[0]


        };
        console.log(clsfy);
        var id=$("#edit-id").val();			  

		update_classification(clsfy,id);

	});
	
	$("#sch_clsfy_img,#edit_sch_clsfy_img").change( function(e){
    	e.preventDefault(); 
    	validate_Image(this);
	});	
 
 
	
  if($('#allow_referral:checked').val()==1)
  {
  		 $('#sch_benefit').prop('disabled',false);
		 $('#walllet_benefit').prop('disabled',false);
  }
  else
  {
 		$('#sch_benefit').prop('disabled',true);
		 $('#walllet_benefit').prop('disabled',true);
  }
 




 $("input[name='general[allow_referral]']:radio").on('change',function(){
		   if($(this).val()==1)
		   {
		   		
			  $('#sch_benefit').prop('disabled',false);
			  $('#walllet_benefit').prop('disabled',false);
		   }
		   else
		   {
			$('#sch_benefit').prop('disabled',true);
			 $('#walllet_benefit').prop('disabled',true);  
		   }
		   
	});
	
	if($('#wallet_balance_type:checked').val()==0)
  {		
  			
  		 $('#wallet_points').prop('disabled',true);
		 $('#wallet_amt_per_points').prop('disabled',true);
  }
  else
  {	
  		
 		$('#wallet_points').prop('disabled',false);
		 $('#wallet_amt_per_points').prop('disabled',false);
  }
 




 $("input[name='general[wallet_balance_type]']:radio").on('change',function(){
		   if($(this).val()==1)
		   {
		   		
			 $('#wallet_points').prop('disabled',false);
			 $('#wallet_amt_per_points').prop('disabled',false);
		   }
		   else
		   {
			$('#wallet_points').prop('disabled',true);
		 $('#wallet_amt_per_points').prop('disabled',true);
		   }
		   
	});
	
	
	$("input[name='general[isOTPRegForPayment]']:checkbox").on("change",function(){
		
		if($("input[name='general[isOTPRegForPayment]']:checked").is(":checked")){
		
		$("#payOTP_exp").prop("disabled",false);
		
		}else{
				$("#payOTP_exp").prop("disabled",true);		
			}
	});

	if($("input[name='general[isOTPRegForPayment]']:checked").is(":checked")){
		
		$("#payOTP_exp").prop("disabled",false);
		
		}else{
				$("#payOTP_exp").prop("disabled",true);		
			}

	$("input[name='general[isOTPReqToLogin]']:checkbox").on("change",function(){
		
		if($("input[name='general[isOTPReqToLogin]']:checked").is(":checked")){
		
		$("#loginOTP_exp").prop("disabled",false);
		
		}else{
				$("#loginOTP_exp").prop("disabled",true);		
			}
	});

	if($("input[name='general[isOTPReqToLogin]']:checked").is(":checked")){
		
		$("#loginOTP_exp").prop("disabled",false);
		
		}else{
				$("#loginOTP_exp").prop("disabled",true);		
			}
	
	
	
	
	// promotion credit enable 
	
	
	if($('#enable_promot').is(':checked'))
		   {
			  $('#enable_promot1').val(1);
			  $('#create_promotion').prop('disabled',true);
		   }
		   else{
			   $('#enable_promot1').val(0);
			    $('#create_promotion').prop('disabled',true);
		   }
	
	
	$(".enable_promotion").on('change',function(){
	 
		   if($('#enable_promot').is(':checked'))
		   {
			  $('#enable_promot1').val(1);
			  $('#create_promotion').prop('disabled',false);
		   }
		   else{
			   $('#enable_promot1').val(0);
			   $('#create_promotion').prop('disabled',true);
		   }
		   
	});
	
// promotion credit enable 	


// discount amount settings //

$("input[name='general[enableGoldrateDisc]']:checkbox").on("change",function(){
		
		if($("input[name='general[enableGoldrateDisc]']:checked").is(":checked")){
		
		$("#goldDiscAmt").prop("disabled",false);
		
		}else{
				$("#goldDiscAmt").prop("disabled",true);		
			}
	});

	if($("input[name='general[enableGoldrateDisc]']:checked").is(":checked")){
		
		$("#goldDiscAmt").prop("disabled",false);
		
		}else{
				$("#goldDiscAmt").prop("disabled",true);		
			}

$("input[name='general[enableGoldrateDisc_18k]']:checkbox").on("change",function(){
		
		if($("input[name='general[enableGoldrateDisc_18k]']:checked").is(":checked")){
		
		$("#goldDiscAmt_18k").prop("disabled",false);
		
		}else{
				$("#goldDiscAmt_18k").prop("disabled",true);		
			}
	});

	if($("input[name='general[enableGoldrateDisc_18k]']:checked").is(":checked")){
		
		$("#goldDiscAmt_18k").prop("disabled",false);
		
		}else{
				$("#goldDiscAmt_18k").prop("disabled",true);		
			}

// otp credit enable 
	
	
	if($('#enable_otpsms').is(':checked'))
		   {
			  $('#enable_otp1').val(1);
			  $('#credit_sms').prop('disabled',true);
		   }
		   else{
			   $('#enable_otp1').val(0);
			    $('#credit_sms').prop('disabled',true);
		   }
	
	
	$(".enable_otp").on('change',function(){
	 
		   if($('#enable_otpsms').is(':checked'))
		   {
			  $('#enable_otp1').val(1);
			  $('#credit_sms').prop('disabled',false);
		   }
		   else{
			   $('#enable_otp1').val(0);
			   $('#credit_sms').prop('disabled',true);
		   }
		   
	});
	
// otp credit enable 	
	
	if(ctrl_page[1]=='village')
	{
	    get_village_list();
	}
	
	
	switch(ctrl_page[0])

	{

		case 'branch':

		         set_branch_table()

			break;
			
		 case 'settings':
			
			
			if( ctrl_page[1]=='rate' && ctrl_page[2]=='add' )

		   { 
			// get_branchname();

		   }    

			break;	
			
			
	}
	
	
	
	
	
	$("#update_card_branch").on('click',function(){
		
		var card=$("#edcard_brand").val();			  
		var code=$("#edshort_code").val();	
		var card_type=$("#edcard_type").val();		  
		var id=$("#edit-id").val();			  
		update_cardbrand(card_type,card,code,id);
	});
	
	
	
	
	$("input[name='edcardtype']:radio").on('change',function(){
		   if($(this).val()==1)
		   {
			  $('#edcard_type').val(1);
			 
			   
		   }
		   else{
			   $('#edcard_type').val(2);
		   }
		   
	});
	
	
	
	
	$("#add_cardbrand").on('click',function(){
		
		if($('#card_brand').length>0)
		{
			add_card_brand($('#card_type').val(),$('#card_brand').val(),$('#short_code').val());
			$('#card_brand').val('');
		}
		
	});
	
	
	
	

 		$('#m_active').bootstrapSwitch();

 	

		val_ckeck();

		$('#allow_advance_payment').click( function(){

			val_ckeck();

		});

		

		$('#pre_closer').click( function(){

			val_ckeck();

		});

		

		$('#allow_pending_due').click( function(){

			val_ckeck();

		});

		

		$('#m_save').click( function(){

			get_maintenance();

		});

		

		$("#offer_img,#new_arrivals_img").change( function(){

		event.preventDefault();

		validateImage(this);

		}); 

		

		 $('#mobile').on('blur onchange',function(){



   	   		if(this.value.length != mob_no_len)



		   	   {



		   	   	 $(this).val('');



			   	 $(this).attr('placeholder', 'Enter valid mobile number');

			   	 

			   	 /*$(this).focus();*/



			   }



			}); 

        //console.log(mob_no_len);	

		$('#mobile1').on('blur onchange',function(){



	   	   if(this.value.length != mob_no_len)



		   	   {



		   	   	 $(this).val('');



			   	 $(this).attr('placeholder', 'Enter valid mobile number');

			   	 

			   	 /*$(this).focus();*/



			   }





			}); 
			
				if($('#country').length>0)

					{

							get_country();	

					}	



					if($('#countryCurr').length>0)

					{

							get_countryCurr();	

					}





				$('#country').on('change', function() {

						get_state(this.value);

					}); 

					

					 $('#state').on('change', function() {

						get_city(this.value);

					});	
			
			
			
			
			
			
			

		

		//For editor



		 if($('#description').length > 0)



		 {



		 	CKEDITOR.replace('description');



		 }

		 

		if($('#description1').length > 0)



		 {



		 	CKEDITOR.replace('description1');



		 }


        	if($('#description3').length > 0)

		 {


		 	CKEDITOR.replace('description3');



		 }



        

		

 });

 

 
//GG
	$('#start_date').change(function() {
		
	var datep = $('#start_date').val();
	var d = new Date();
	var currentDate = new Date(d),
         month = '' + (d.getMonth() + 1),
         day = '' + d.getDate(),
         year = d.getFullYear();

	var output =   (month<10 ? '0' : '') + month + '/' +(day<10 ? '0' : '') + day+ '/' +year ;

		if(Date.parse(datep)-Date.parse(output)<0)
		{
		   alert("Selected date is in the past");
		   $("#start_date").val('');
		}  
});
	
//GG
	



  function validateImage()

   {

   	 	 var height=($(this).height());
      	  var width=($(this).width());

	if(arguments[0].id == 'offer_img')

      {

		 var preview = $('#offer_img_preview');

	  }

	  else

      {

		 var preview = $('#new_arrivals_img_preview');

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

			if(ext != "jpg" && ext != "png" && ext != "jpeg" && ext != "svg")

			{

				alert("Upload JPG or PNG Images only");

				arguments[0].value = "";

				preview.css('display','none');

			}
			
			/*if(width>960 && height>525)
			{

				alert("Width and height should be less than  960 * 525 ");

				arguments[0].value = "";

				preview.css('display','none');
			}*/


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



			



 

 function val_ckeck()

	{

			

		if(!($('#allow_advance_payment').is(':checked'))){

			

			$('#Allow_Payment_wt,#Allow_Payment_amt').attr("disabled", "disabled");

			}

			else{

			

			$('#Allow_Payment_wt,#Allow_Payment_amt').removeAttr("disabled", "false");

			}

			

		if(!($('#pre_closer').is(':checked'))){

			

			$('#ins_pending').attr("disabled","disabled");

			$('#benefits').bootstrapSwitch('disabled',true);

			

			}

			else{

			

			$('#ins_pending').removeAttr("disabled", "false");

			$('#benefits').bootstrapSwitch('disabled',false);

			}

			

		if(!($('#allow_pending_due').is(':checked'))){

			

			$('#allow_pending_wgt,#allow_pending_amt').attr("disabled", "disabled");

			}

			else{

			

			$('#allow_pending_wgt,#allow_pending_amt').removeAttr("disabled", "false");

			}

			

}

//select all customers in imported list        

$('#sel_imported_all').click(function(event) {							

	$("tbody tr td input[type='checkbox']").prop('checked', $(this).prop('checked'));

    event.stopPropagation();					     

});

  	

//send SMS to selected customers in imported list

   $("#sendSMS").click(function(){

   

	   	 var data = { 'account_id[]' : []};

		$("input[name='account_id[]']:checked").each(function() {

		  data['account_id[]'].push($(this).val());

		});

		 $("div.overlay").css("display", "block"); 

				$.ajax({

					      type: "POST",

						  url: base_url+"index.php/settings/import/send_login",

						  data: data,

						  sync:false,

						  success: function(data){

						 

						  	 $("div.overlay").css("display", "none"); 

							  	 $('#alert_msg').html(data);

							  	 $(".alert").css("display","block"); 

												 

						 }				 

			   });

   }); 

   //send Email to selected customers in imported list

   $("#sendEmail").click(function(){

   

	   	 var data = { 'account_id[]' : []};

		$("input[name='account_id[]']:checked").each(function() {

		  data['account_id[]'].push($(this).val());

		});

		 $("div.overlay").css("display", "block"); 

				$.ajax({

					      type: "POST",

						  url: base_url+"index.php/settings/import/send_login_email",

						  data: data,

						  sync:false,

						  success: function(data){

						 

						  	 $("div.overlay").css("display", "none"); 

							  	 $('#alert_msg').html(data);

							  	 $(".alert").css("display","block"); 

							 

						 }				 

			   });

   }); 

   

   //initialize Plugin

      $(function () {

      	//form menu item

      	 $(".test").select2({

		 	 placeholder: 'Enter name',

		    //Does the user have to enter any data before sending the ajax request

		    minimumInputLength: 0,            

		    allowClear: true,

		    ajax: {

		        //How long the user has to pause their typing before sending the next request

		        quietMillis: 150,

		        //The url of the json service

		        url: base_url+"index.php/settings/menu/parent_dropdown",

		        dataType: 'jsonp',

		        //Our search term and what page we are on

		        data: function (term, page) {

		            return {

		                pageSize:50,

		                pageNum: page,

		                searchTerm: term

		            };

		        },

		        results: function (data, page) {

		            //Used to determine whether or not there are more results available,

		            //and if requests for more data should be sent in the infinite scrolling

		            var more = (page * pageSize) < data.Total; 

		            return { data: data};        }

		      }	

		 });

      	

      });

	

$(document).ready(function() {

//global variables

menus= []; //for menu

var pathArray = window.location.pathname.split( 'php/' );

var ctrl_page = pathArray[1].split('/');



//load request based on url

switch(ctrl_page[1])

{
    
    case 'profession':

		set_profession_table();
		
		break;

	case 'access':

		set_permission_view();		

	break;

	case 'bank':

		set_bank_table();

	break;
	
	case 'gift':

		set_gift_table();

	break;

	case 'dept':

		set_dept_table();

	break;

	case 'offers':

		set_offers_table();

	break;

	case 'new_arrivals':

		set_new_arrivals_table();
		
			$("#new_arrival_submit").on("click",function(e){
           // e.preventDefault();
          //  if($("#new_arrival_type").is(':checked') || $("#gift_artical").is(':checked'))
            if(($.trim($("#new_arrival_type").val()) != ''  || $.trim($("#gift_artical").val()) != '')  && $.trim($("#name").val()) == '' || $.trim($("#product_code").val()) == '' || $.trim($("#price").val()) == '' || $.trim($("#product_description").val()) == '' || $.trim($("#new_arrivals_content").val()) == '' || $.trim($("#expiry_date").val()) == '' || $.trim($("#new_arrivals_img").val()) == '')
            {
                //$('#new_arrival_form').submit();
                 alert('Please Fill The Required Fields..');
            }
           
            else{
               // alert('Please Fill The Required Fields..');
		       // return  false;
		       $('#new_arrival_form').submit();
            }
           
        });

	break;

	case 'design':

		set_design_table();

	break;

	case 'drawee':

	

	   if(ctrl_page[2] == 'add' || ctrl_page[2] == 'edit')

	   {

	   	  load_bank();

	   }

	    if(ctrl_page[2]=='list')

	   {	   

	   	  set_drawee_table();	   	 

	   }

		

	break;

	case 'general':

	   

	   if(ctrl_page[2] == 'edit')

	  {

	  	 $('.sel_block').prop('checked', false);

	  	  $('.sel_block').prop('disabled',true);

	   

	  	$("input[type='radio'][name='clear_data']").change(function(){

	  		

			    var selected = (this.value==1? true :false);

			    $('.sel_block').prop('disabled',selected);

			    

			 

			    if(selected==true)

			    {

					  $('.sel_block').prop('checked', false);

				}

			    

			  

			

	  	});

	  	

/*-- coded by ARVK --*/  	

	//Limit settings

	  		if($('#limit_cust').is(':checked')){

				$('#cust_max_count').prop('disabled',false);

			}else{

				$('#cust_max_count').prop('disabled',true);

			}

			

	  		if($('#limit_sch').is(':checked')){

				$('#sch_max_count').prop('disabled',false);

			}else{

				$('#sch_max_count').prop('disabled',true);

			}

			

			if($('#limit_branch').is(':checked')){

				$('#branch_max_count').prop('disabled',false);

			}else{

				$('#branch_max_count').prop('disabled',true);

			}

			

			if($('#limit_sch_acc').is(':checked')){

				$('#sch_acc_max_count').prop('disabled',false);

			}else{

				$('#sch_acc_max_count').prop('disabled',true);

			}

			

			$('#limit_cust').on("click", function(e) {

          		if($('#limit_cust').is(':checked'))

		  		{

			 	 	$('#cust_max_count').prop('disabled',false);

				}else{

					$('#cust_max_count').prop('disabled',true);

					$('#cust_max_count').val(0);

				}

		  

        	});

        	

	  		$('#limit_sch').on("click", function(e) {

          		if($('#limit_sch').is(':checked'))

		  		{

			 	 	$('#sch_max_count').prop('disabled',false);

				}else{

					$('#sch_max_count').prop('disabled',true);

					$('#sch_max_count').val(0);

				}

			});

	  		

	  		$('#limit_branch').on("click", function(e) {

          		if($('#limit_branch').is(':checked'))

		  		{

			 	 	$('#branch_max_count').prop('disabled',false);

				}else{

					$('#branch_max_count').prop('disabled',true);

					$('#branch_max_count').val(0);

				}

		  

        	});

	  		

	  		$('#limit_sch_acc').on("click", function(e) {

          		if($('#limit_sch_acc').is(':checked'))

		  		{

			 	 	$('#sch_acc_max_count').prop('disabled',false);

				}else{

					$('#sch_acc_max_count').prop('disabled',true);

					$('#sch_acc_max_count').val(0);

				}

		  

        	});

        	

        	

    		

/*-- / coded by ARVK--*/ 

	  	

	  	//Clearing database

	  	$('#clr_proceed').click(function(){

	  		var mode = $("#tab_5 input[type='radio'][name='clear_data']:checked").val();

	  	

	  		if(mode==1)

	  		{

				  $('#confirm-truncate').modal('show');

			}

			else

			{ 

				if ($("input[type='checkbox'][name='clr_db']:checked").length > 0){

		  		   $('#confirm-truncate').modal('show');

		  		   

		  		}

		  		else

		  		{  

		  		    $('#clr_alert').addClass('alert-danger');

					$('#clr_alert span').html('Select atleast one option.');

					$('#clr_alert').css('display','block');

				}

			}

	  	

	  	});

	  	

		

	    $(document).on("click", "#confirm-truncate #confirm_clear", function(event){

	  	$('.modal.in').modal().hide(); 

	  	$('.modal-backdrop').remove();

	  	   my_Date = new Date();	

	  	   var mode = $("#tab_5 input[type='radio'][name='clear_data']:checked").val();

		   var selected = [];

			$('#tab_5 input[name="clr_db"]:checked').each(function() {

			    selected.push($(this).val());

			});

			

			$.ajax({

			  type: 'POST',

			  data:{'mode':mode,'selected':selected },

			  url:  base_url+'index.php/settings/clear/database?nocache=' + my_Date.getUTCSeconds(),

			  cache:false,

			  success: function(data) {

			  	      $('#clr_alert').addClass('alert-success');

					$('#clr_alert span').html(data);

					$('#clr_alert').css('display','block');

			  	}

		  });

		});

	 

	     //backup database

		 $('#db_backup').click(function(){

			 	load_db_list(); 

		 });

	  } 

	 break;

	case 'rate':

	

	  if(ctrl_page[2]=='list')

	   {

			load_metalrate_list();

	   }

	   	else

	   	{

			$("input[type='text']").focus(function() { $(this).select(); } );

		}

	    break;

	case 'menu':

		set_menu_table();

		load_parent_menu();

	break;		

	case 'payment_charges':

		set_charges_table();

	break;

	case 'paymode':

		set_paymode_table();

	break;

	case 'profile':

		set_profile_table();

	break;

	case 'weight':

		set_weight_table();

	break;
	case 'cardbrand':

		set_cardbrand_table();

	break;

	case 'classification':

		set_classification_table();

	break;

	case 'import':

		 if(ctrl_page[2]=='list')

		   {

			get_import_list();

		   }
		 else if(ctrl_page[2]=='customer_list')
		   {
		       get_customer_list();
		   }

	break;

	

}









excel_format(0);
customer_excel_format(0);




	$('#import_type').bootstrapSwitch();		

	$('#send_sms').bootstrapSwitch();		



	

	

	

	

	



	

	if($('#import_type').length>0)

	{

		$('#import_type').prop('checked', false);

		

		$('#import_type').on('switchChange.bootstrapSwitch', function(event, state) {

 	  		excel_format(state);

 	  	});	

		

	}

		

	

	

	

	//$(".btn-edit").on('click',function(e){
	$(document).on('click', "#cardbrand_list a.btn-edit", function(e)
	{
		

			$("#edcard_brand").val('');
			$("#edshort_code").val('');
			e.preventDefault();
			id=$(this).data('id');
			
			get_cardbrand(id);
			$("#edit-id").val(id);  
	});	


	$(document).on('click', "#weight_list a.btn-edit", function(e) {

		$("#ed_weight").val('');

		e.preventDefault();

		id=$(this).data('id');

	    get_weight(id);

	    $("#edit-id").val(id);  

	});	

	

	$(document).on('click', "#sch_clsfy_list a.btn-edit", function(e) {

		$("#ed_clsfy").val('');

		e.preventDefault();

		id=$(this).data('id');

	    get_classification(id);

	    $("#edit-id").val(id);  

	});	

	

	$(document).on('click', "#dept_list  a.btn-edit", function(e) {

	

		$("#ed_dept").val('');

		e.preventDefault();

		id=$(this).data('id');

	    get_dept(id);

	    $("#edit-id").val(id);  

	});	

	

	

	

	$(document).on('click', "#design_list  a.btn-edit", function(e) {

	

		$("#ed_design").val('');

		e.preventDefault();

		id=$(this).data('id');

	    get_design(id);

	    $("#edit-id").val(id);  

	});

	

    //update weight	

	$("#update_weight").on('click',function(){

		

		var weight=parseFloat($("#ed_weight").val()).toFixed(3);			  

		var id=$("#edit-id").val();			  

		update_weight(weight,id);

	});

	

	//update classification	

	$("#update_clsfy").on('click',function(){

		

		var clsfy = {

        classification_name:  $("#ed_clsfy").val(),

        description:  CKEDITOR.instances.description1.getData()

        };

        var id=$("#edit-id").val();			  

		update_classification(clsfy,id);

	});

	

	//update dept

	$("#update_dept").on('click',function(){

		

		var dept=$("#ed_dept").val()			  

		var id=$("#edit-id").val();	

       if(dept)

       {

		   update_dept(dept,id);

	   }		   		

	});	

	

	

	//update design

	$("#update_design").on('click',function(){

		

		var design=$("#ed_design").val()			  

		var id=$("#edit-id").val();	

       if(dept)

       {

		   update_design(design,id);

	   }		   		

	});



	



	

	

$( "#parent" ).on( "autocompleteselect", function(event, ui) {

		var flag = false;

		$.each(menus, function(key, value) {   

		 if((value.label).toLowerCase() == (ui.item.label).toLowerCase())

		 {

			  flag = true;

			  $("#id_parent").val(value.id_menu);

	

			  return false;

		 }

		});

		if(flag == false)

		{

			$("#parent").val("");

			$("#id_parent").val("");

		}

	});



	

	

__set_export_view()

	

__set_import_list()

});



//set import list

function __set_import_list()

{

	

	$('#select_all').click(function(event) {

		$("tbody tr td input[type='checkbox']").prop('checked', $(this).prop('checked'));

      event.stopPropagation();

    });

  /*  $("tbody tr td input[type='checkbox']").click(function(event) {

		$("#select_all").prop('checked', $(this).prop('checked'));

      event.stopPropagation();

    }); */

}



//set export form

function __set_export_view()

{

	var export_data=null;

 

 	$("input[name=pay_status][value=0]").prop('checked', true);

	$( "#is_to_date" ).prop( "checked", false );

	 $("#to_date").prop("disabled",true);



	//required to date for export

 	if($('#is_to_date').length>0)

	{

		$('#is_to_date').change(function(){			

		   $("#to_date").prop("disabled", !$(this).is(':checked'));

		});

	}	

	

	 $("#export").on('click',function(){

	 	 	  my_Date = new Date();

	 	  var status	=$("input:radio[name='pay_status']:checked").val();

	 	  var from_date	=$("#from_date").val();

	 	  var to_date	=null;

	 	  

	 	  if($("#is_to_date").is(':checked'))

	 	  {

		  	 to_date	=	$("#to_date").val();

		  }

	 

	 	 

		$.ajax({

			  type: 'POST',

			  cache:false,

			  data:{'status':status,'from':from_date,'to':to_date },

			  url:  base_url+'index.php/settings/export_to_excel?nocache=' + my_Date.getUTCSeconds(),

			  cache:false,

			  success: function(path) {

			  	   window.open(base_url+path,'_blank' );

			  	}

		  });	

	 });	

	 

	 $("#get_account").on('click',function(){

	 	  my_Date = new Date();

	 	  var status	=$("input:radio[name='filter_by']:checked").val();

	 	  var from_date	=$("#from_date").val();

	 	  var to_date	=null;

	 	  

	 	  if($("#is_to_date").is(':checked'))

	 	  {

		  	 to_date	=	$("#to_date").val();

		  }

		  alert(from_date+" to "+to_date);

		  

		  	$.ajax({

		  type: 'POST',

		  data:{'status':status,'from':from_date,'to':to_date },

		  url:  base_url+'index.php/settings/export/get_account/?nocache=' + my_Date.getUTCSeconds(),

		  dataType: 'json',

		   cache:false,

		  success: function(data) {

		  	  export_data=data;

		  	  var trHTML = '';

		  	  

	        $.each(data, function (i, item) {

	            trHTML += '<tr><td>' + item.id_payment 

	                      + '</td><td>' + (item.name!=null? item.name : "-") 

	                      + '</td><td>' + (item.ref_no!=null?item.ref_no:"-") 

	                      + '</td><td>' + (item.code!=null?item.code:"-")

	                      + '</td><td>' + (item.mobile!=null?item.mobile:"-")

	                      + '</td><td>' + (item.date_payment!=null?item.date_payment:"-")

	                      + '</td><td>' + (item.payment_amount!=null?item.payment_amount:"-")

	                      + '</td><td>' + (item.payment_mode!=null?item.payment_mode:"-")

	                      + '</td><td>' + (item.bank_acc_no!=null?item.bank_acc_no:"-")

	                      + '</td><td>' + (item.bank_name!=null?item.bank_name:"-")

	                      + '</td><td>' + (item.bank_branch!=null?item.bank_branch:"-")

	                      + '</td><td>' + (item.bank_IFSC!=null?item.bank_IFSC:"-")

	                      + '</td><td>' + (item.bank_charges!=null?item.bank_charges:"-")

	                      + '</td><td>' + (item.trans_id!=null?item.trans_id:"-")

	                      + '</td><td>' + (item.payment_status!=null?item.payment_status:"-")	                     

	                      + '</td></tr>';

	        });

        		

        	$('#account_content').html(trHTML);

		  	

		  	  

		  	}

		  	

		  });	

		  

	 });	

	 	

	 	$("#get_record").on('click',function(){

	 	  my_Date = new Date();

	 	  var status	=$("input:radio[name='pay_status']:checked").val();

	 	  var from_date	=$("#from_date").val();

	 	  var to_date	=null;

	 	  

	 	  if($("#is_to_date").is(':checked'))

	 	  {

		  	 to_date	=	$("#to_date").val();

		  }

	 

	 	 

		$.ajax({

		  type: 'POST',

		  data:{'status':status,'from':from_date,'to':to_date },

		  url:  base_url+'index.php/settings/export_list/?nocache=' + my_Date.getUTCSeconds(),

		  dataType: 'json',

		   cache:false,

		  success: function(data) {

		  	  export_data=data;

		  	  var trHTML = '';

		  	  

	        $.each(data, function (i, item) {

	            trHTML += '<tr><td>' + item.id_payment 

	                      + '</td><td>' + (item.name!=null? item.name : "-") 

	                      + '</td><td>' + (item.ref_no!=null?item.ref_no:"-") 

	                      + '</td><td>' + (item.code!=null?item.code:"-")

	                      + '</td><td>' + (item.mobile!=null?item.mobile:"-")

	                      + '</td><td>' + (item.date_payment!=null?item.date_payment:"-")

	                      + '</td><td>' + (item.payment_amount!=null?item.payment_amount:"-")

	                      + '</td><td>' + (item.payment_mode!=null?item.payment_mode:"-")

	                      + '</td><td>' + (item.bank_acc_no!=null?item.bank_acc_no:"-")

	                      + '</td><td>' + (item.bank_name!=null?item.bank_name:"-")

	                      + '</td><td>' + (item.bank_branch!=null?item.bank_branch:"-")

	                      + '</td><td>' + (item.bank_IFSC!=null?item.bank_IFSC:"-")

	                      + '</td><td>' + (item.bank_charges!=null?item.bank_charges:"-")

	                      + '</td><td>' + (item.trans_id!=null?item.trans_id:"-")

	                      + '</td><td>' + (item.payment_status!=null?item.payment_status:"-")	                     

	                      + '</td></tr>';

	        });

        		

        	$('#payment_content').html(trHTML);

		  	

		  	  

		  	}

		  	

		  });	

	 });

}

/*-- Coded by ARVK --*/



//to load countries list in country settings

function get_countryCurr()

{

	$('.overlay').css('display','block');

    my_Date = new Date();

	$.ajax({

	  type: 'GET',

	  url:  base_url+'index.php/settings/company/getcountry?nocache=' + my_Date.getUTCSeconds(),

	  dataType: 'json',

	   cache:false,

	  success: function(country) {

	  //	console.log(country);

		$.each(country, function (key, country) {

				  	

		  

			$('#countryCurr').append(

				$("<option></option>")

				  .attr("value", country.id)

				  .text(country.name)

				  

			);

			

		});

		

		$("#countryCurr").select2({

				  placeholder: "Enter Country",

				    allowClear: true

				});	

		$("#countryCurr").select2("val", ($('#countryCurrval').val()!=null?$('#countryCurrval').val():''));



		

		}

	});

	$('.overlay').css('display','none');

}





//to get currency code, mobile code and mob no length

 $('#countryCurr').select2().on("change", function(e)

{

	 if(this.value!='')

	 {

      	$("#countryCurrval").val(this.value);

		my_Date = new Date();

		$.ajax({

		  type: 'GET',

		  url:  base_url+'index.php/settings/country/getcurrency/'+this.value+'?nocache=' + my_Date.getUTCSeconds(),

		  dataType: 'json',

		   cache:false,

		  success: function(currency) {

					//console.log(currency);

					$("#currency_name").val(currency.currency_name);

					$("#currency_code").val(currency.currency_code);

					$("#mob_code").val(currency.mob_code);

					$("#mob_no_len").val(currency.mob_no_len);

				

			}

		});

	}

});

 

 



/*-- / Coded by ARVK --*/



//to get countries

function get_country()

{

    my_Date = new Date();

	$.ajax({

	  type: 'GET',

	  url:  base_url+'index.php/settings/company/getcountry?nocache='+ my_Date.getUTCSeconds(),

	  dataType: 'json',

	   cache:false,

	  success: function(country) {

	  //	console.log(country);

		$.each(country, function (key, country) {

				  	

		  

			$('#country').append(

				$("<option></option>")

				  .attr("value", country.id)

				  .text(country.name)

				  

			);

			

		});

		

			var selectid=$('#countryval').val();

			if(selectid!=null)

			{

				$('#country').val(selectid);

				get_state(selectid);

			}

			

		}

	});

}



//to get state details based on country selection

function get_state(id)

{



	my_Date = new Date();

	$('#state option').remove();

	$.ajax({

	  type: 'POST',

	   data:{'id_country':id },

	  url:  base_url+'index.php/settings/company/getstate/?nocache='+ my_Date.getUTCSeconds(),

	  cache:false,

	  dataType: 'json',

	  success: function(state) {

	  	

		$.each(state, function (key, state) {			  	

		  

			$('#state').append(

				$("<option></option>")

				  .attr("value", state.id)

				  .text(state.name)

			);

		});

		

		var selectid=$('#stateval').val();

		if(selectid!=null)

		{

			$('#state').val(selectid);

			

		    get_city(selectid);

	    }

		

		

	  }

	});

}



//to get city based on state selection

function get_city(id)

{  	

	my_Date = new Date();

   $('#city option').remove();

	my_Date = new Date();

	$.ajax({

	  type: 'POST',

	  data:{'id_state':id },

	  url:  base_url+"index.php/settings/company/getcity?nocache=" + my_Date.getUTCSeconds(),

	  	cache:false,

	  dataType: 'json',

	  success: function(city) {

	  

		$.each(city, function (key, city) {

				  	

		  

			$('#city').append(

				$("<option></option>")

				  .attr("value", city.id)

				  .text(city.name)

			);

		});

	

		var selectid=$('#cityval').val();

		if(selectid!=null)

		{

			$('#city').val(selectid);

			

		   

	    }

	  }

	});



}



//to add weight 

function add_weight(weight)

{

	my_Date = new Date();

    var wt=weight;

	$.ajax({

		data:{"weight":wt},

		url: base_url+"index.php/settings/weight/add?nocache=" + my_Date.getUTCSeconds(),

		type:"POST",

		cache:false,

		success:function(data){

			

			$('#weight').val('');

			 msg='<div class = "alert alert-success"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Success!</strong>Weight added successfully.</div>';

			$('#chit_alert').html(msg);

			location.reload(true);

			

			

		}

			

		

		

	});

}



//to add classification 

function add_classification(clsfy)
{
	var classification_name=clsfy.classification_name;
	var description=clsfy.description;
	var file=clsfy.file;
		
	my_Date = new Date();

    var form_data = new FormData();  
   
    form_data.append('file', file);
    form_data.append('classification_name', classification_name);
    form_data.append('description', description);
    
	$.ajax({
		data:form_data,
		url: base_url+"index.php/settings/classification/add?nocache=" + my_Date.getUTCSeconds(),
		type:"POST",
        dataType:'json',
        cache : false,
		enctype: 'multipart/form-data',
		contentType : false,
		processData : false,
		success:function(data){
		 location.reload(true);
		}
	});
}







//to load bank

function load_bank()

{

	my_Date = new Date();

	$.ajax({

		type: 'GET',

		url:base_url+"index.php/settings/bank/ajax_list?nocache=" + my_Date.getUTCSeconds(),

		dataType:'json',

		success:function(data){

			//console.log(data);

			$.each(data.data, function (key, bank) {

				

				

		

				$('#bank_dropdown').append(

					$("<option></option>")

					  .attr("value", bank.id_bank)

					  .text(bank.bank_name)

					  

				);

				

			});

			

			$("#bank_dropdown").select2({

			  placeholder: "Select Bank",

			    allowClear: true

			});		

			

			$("#bank_dropdown").select2("val", ($('#id_bank').val()!=null?$('#id_bank').val():''));

					

		

		

		  

		

		

		}

	});

}



//bank dropdown event 

 $('#bank_dropdown').select2()

        .on("change", function(e) {

          if(this.value!='')

          {

          	 $("#id_bank").val(this.value);

		  	

		  }

   });





//to load drawee list

function set_drawee_table()

{

	my_Date = new Date();

	$.ajax({

			 url:base_url+ "index.php/settings/drawee/ajax?nocache=" + my_Date.getUTCSeconds(),

			 dataType:"JSON",

			 type:"POST",

			 success:function(data){

				 var drawee 	= data.data;

				 var access		= data.access;	

				 $('#total_drawee').text(drawee.length);

				  if(access.add == '0')

				 { 

			 			

					$('#add_drawee').attr('disabled','disabled');

					

				 }

				 

			 var oTable = $('#drawee_list').DataTable();

				 oTable.clear().draw();

						 if (drawee!= null && drawee.length > 0)

							{  	

							oTable = $('#drawee_list').dataTable({

									"bDestroy": true,

									"bInfo": true,

									"bFilter": true,

									"bSort": true,

									"aaData": drawee,

									"aoColumns": [	{ "mDataProp": "id_drawee" },

													{ "mDataProp": "account_no" },

													{ "mDataProp": "account_name" },

													{ "mDataProp": "bank_name" },

													{ "mDataProp": "branch" },

													{ "mDataProp": "ifsc_code" },

													{ "mDataProp": function ( row, type, val, meta ) {

														 id= row.id_drawee;

                	 									 edit_url=(access.edit=='1'?base_url+'index.php/settings/drawee/edit/'+id:'#');

														 delete_url=(access.delete=='1' ? base_url+'index.php/settings/drawee/delete/'+id : '#' );

														 delete_confirm= (access.delete=='1' ?'#confirm-delete':'');

														  action_content='<div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button><ul class="dropdown-menu">'+

															'<li><a href="'+edit_url+'" class="btn-edit"><i class="fa fa-edit" ></i> Edit</a></li>'+

															'<li><a href="#" class=" btn-del" data-href='+delete_url+' data-toggle="modal" data-target="#confirm-delete" ><i class="fa fa-trash"></i> Delete</a></li>';

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



//to load payment mode list

function set_paymode_table()

{	 $("div.overlay").css("display", "block"); 

	my_Date = new Date();

	$.ajax({

			 url:base_url+ "index.php/settings/paymode/ajax?nocache=" + my_Date.getUTCSeconds(),

			 dataType:"JSON",

			 type:"POST",

			 success:function(data){

				 var paymode 	= data.data;

				 var access		= data.access;	

				 $('#total_paymode').text(paymode.length);

				  if(access.add == '0')

				 { 

			 			

					$('#add_paymode').attr('disabled','disabled');

					

				 }

				 

			 var oTable = $('#paymode_list').DataTable();

				 oTable.clear().draw();

						 if (paymode!= null && paymode.length > 0)

							{  	

							oTable = $('#paymode_list').dataTable({

									"bDestroy": true,

									"bInfo": true,

									"bFilter": true,

									"bSort": true,

									"aaData": paymode,

									"aoColumns": [	{ "mDataProp": "id_mode" },

													{ "mDataProp": "mode_name" },

													{ "mDataProp": "short_code" },

													{ "mDataProp": function ( row, type, val, meta ) {

														 id= row.id_mode;

                	 									 edit_url=(access.edit=='1'?base_url+'index.php/settings/paymode/edit/'+id:'#');

														 delete_url=(access.delete=='1' ? base_url+'index.php/settings/paymode/delete/'+id : '#' );

														 delete_confirm= (access.delete=='1' ?'#confirm-delete':'');

														  action_content='<div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button><ul class="dropdown-menu">'+

															'<li><a href="'+edit_url+'" class="btn-edit"><i class="fa fa-edit" ></i> Edit</a></li>'+

															'<li><a href="#" class=" btn-del" data-href='+delete_url+' data-toggle="modal" data-target="#confirm-delete" ><i class="fa fa-trash"></i> Delete</a></li>';

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





//to load profile list

function set_profile_table()

{

	my_Date = new Date();

	$.ajax({

			 url:base_url+ "index.php/settings/profile/ajax_list?nocache=" + my_Date.getUTCSeconds(),

			 dataType:"JSON",

			 type:"POST",

			 success:function(data){

				 var profile 	= data.profile;

				 var access		= data.access;	

				 $('#total_profiles').text(profile.length);

				  if(access.add == '0')

				 { 

			 			

					$('#add_profile').attr('disabled','disabled');

					

				 }

				 

			 var oTable = $('#profile_list').DataTable();

				 oTable.clear().draw();

						 if (profile!= null && profile.length > 0)

							{  	

							oTable = $('#profile_list').dataTable({

									"bDestroy": true,

									"bInfo": true,

									"bFilter": true,

									"bSort": true,

									"aaData": profile,

									"aoColumns": [	{ "mDataProp": "id_profile" },

													{ "mDataProp": "profile_name" },

													{ "mDataProp": function ( row, type, val, meta ) {

														 id= row.id_profile;

                	 									 edit_url=(access.edit=='1'?base_url+'index.php/settings/profile/edit/'+id:'#');

														 delete_url=(access.delete=='1' ? base_url+'index.php/settings/profile/delete/'+id : '#' );

														 delete_confirm= (access.delete=='1' ?'#confirm-delete':'');

														  action_content='<div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button><ul class="dropdown-menu">'+

															'<li><a href="'+edit_url+'" class="btn-edit"><i class="fa fa-edit" ></i> Edit</a></li>'+

															'<li><a href="#" class=" btn-del" data-href='+delete_url+' data-toggle="modal" data-target="#confirm-delete" ><i class="fa fa-trash"></i> Delete</a></li>';

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



//to load values to table

function set_menu_table()

{

	my_Date = new Date();

	var oTable = $('#menu_list').dataTable({

	            	"aProcessing": true,

	 				"aServerSide": true,

					"ajax": base_url+"index.php/settings/menu/ajax_list?nocache=" + my_Date.getUTCSeconds(),

					"columns": [

				                { "data": "id_menu" },

				                { "data": "label" },

				                { "data": "link" },

				                { "data": "parentname" },

				                { "data": "submenus" },

				                { "data": "sort" },

				                { "data":  function ( row, type, val, meta ){

				                	menu_status= row.active;

				                	status_content=(menu_status==1?'Active':'Disabled');

				                	return status_content;

				                }},

				                { "data": function ( row, type, val, meta ) {

				                	 id= row.id_menu;

				                	 action_content="<div class='btn-group inline' ><a href='"+base_url+"index.php/settings/menu/edit/"+id+"' class='btn btn-primary btn-edit' role='button' data-toggle='modal' data-id="+id+" ><i class='fa fa-edit' ></i> Edit</a> <a href='#' class='btn btn-danger btn-del' data-href="+base_url+"index.php/settings/menu/delete/"+id+" data-toggle='modal' data-target='#confirm-delete' ><i class='fa fa-trash'></i> Delete</a></div>";

				                	return action_content;

				                	}

				               

				            }]

        });

}



//to load parent menu Dropdown()

function load_parent_menu()

{

	my_Date = new Date();

	$.ajax({

		type: 'GET',

		url:base_url+"index.php/settings/menu/ajax_list?nocache=" + my_Date.getUTCSeconds(),

		dataType:'json',

		success:function(data){

			//console.log(data);

			$.each(data.data, function (key, menu) {

				

				

		

				$('#parent').append(

					$("<option></option>")

					  .attr("value", menu.id_menu)

					  .text(menu.label)

					  

				);

				

			});

			

			$("#parent").select2({

			  placeholder: "Select parent menu",

			    allowClear: true

			});		

			

			$("#parent").select2("val", ($('#id_parent').val()!=null?$('#id_parent').val():''));

					

		

		

		  

		

		

		}

	});

}



//to load values to table

function set_weight_table()

{

	my_Date = new Date();

	$.ajax({

			 url:base_url+ "index.php/settings/weight_list?nocache=" + my_Date.getUTCSeconds(),

			 dataType:"JSON",

			 type:"POST",

			 success:function(data){

				 var weight 	= data.data;

				 var access		= data.access;	

				 $('#total_weights').text(weight.length);

				 if(access.add == '0')

				 { 

			 			

					$('#add_wt').attr('disabled','disabled');

					

				 }

				 else{

					 $("#add_weight").on('click',function(){

		

						if($('#weight').length>0)

						{

							add_weight($('#weight').val());

							$('#weight').val('');

						}

		

						});

				 }

				

			 var oTable = $('#weight_list').DataTable();

				 oTable.clear().draw();

						 if (weight!= null && weight.length > 0)

							{  	

							oTable = $('#weight_list').dataTable({

									"bDestroy": true,

									"bInfo": true,

									"bFilter": true,

									"bSort": true,

									"aaData": weight,

									"aoColumns": [	{ "mDataProp": "id_weight" },

													{ "mDataProp": "weight" },

													{ "mDataProp": function ( row, type, val, meta ) {

														 id= row.id_weight;

														 edit_target=(access.edit=='0'?"":"#confirm-edit");

														 delete_url=(access.delete=='1' ? base_url+'index.php/settings/weight/delete/'+id : '#' );

														 delete_confirm= (access.delete=='1' ?'#confirm-delete':'');

														 action_content='<a href="#" class="btn btn-primary btn-edit" id="edit" role="button" data-toggle="modal" data-id='+id+'  data-target='+edit_target+'><i class="fa fa-edit" ></i> Edit</a> <a href="#" class="btn btn-danger btn-del" data-href='+delete_url+' data-toggle="modal" data-target="#confirm-delete" ><i class="fa fa-trash"></i> Delete</a>'

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

//to load values to classification table

function set_classification_table()

{

	my_Date = new Date();

	$.ajax({

			 url:base_url+ "index.php/settings/classification_list?nocache=" + my_Date.getUTCSeconds(),

			 dataType:"JSON",

			 type:"POST",

			 success:function(data){

				 var clsfy 		= data.data;

				 var access		= data.access;	

				 $('#total_classifications').text(clsfy.length);

				 if(access.add == '0')

				 { 

			 			

					$('#add_cls').attr('disabled','disabled');

					

				 }

				 else{

					 $("#add_clsfy").on('click',function(){

		

						if($('#clsfy').length>0)

						{

							var clsfy = {

						        classification_name:  $("#clsfy").val(),

						        description:  CKEDITOR.instances.description1.getData(),

						      	file : $("#sch_clsfy_img")[0].files[0]

						        };	


							add_classification(clsfy);

							$('#clsfy').val('');

							CKEDITOR.instances.description.setData('');

						}

		

						});

				 }

				

			 var oTable = $('#sch_clsfy_list').DataTable();

				 oTable.clear().draw();

						 if (clsfy!= null && clsfy.length > 0)

							{  	

							oTable = $('#sch_clsfy_list').dataTable({

									"bDestroy": true,

									"bInfo": true,

									"bFilter": true,

									"bSort": true,

									"aaData": clsfy,

									"aoColumns": [	{ "mDataProp": "id_classification" },

													{ "mDataProp": "classification_name" },
													
													{ "mDataProp": function ( row, type, val, meta ) {
													    if(row.logo != null){
													        return "<img src='"+row.logo+"' style='width: 75px;height: 50px;' />";
													    }else{
													        return "-";
													    }
													}},

													{ "mDataProp": function ( row, type, val, meta ) {

														 id= row.id_classification;

														 edit_target=(access.edit=='0'?"":"#confirm-edit");

														 delete_url=(access.delete=='1' ? base_url+'index.php/settings/classification/delete/'+id : '#' );

														 delete_confirm= (access.delete=='1' ?'#confirm-delete':'');

														 action_content='<a href="#" class="btn btn-primary btn-edit" id="edit" role="button" data-toggle="modal" data-id='+id+'  data-target='+edit_target+'><i class="fa fa-edit" ></i> Edit</a> <a href="#" class="btn btn-danger btn-del" data-href='+delete_url+' data-toggle="modal" data-target="#confirm-delete" ><i class="fa fa-trash"></i> Delete</a>'

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





//to load values to table

function set_dept_table()

{

	my_Date = new Date();

	$.ajax({

			 url:base_url+ "index.php/settings/dept_list?nocache=" + my_Date.getUTCSeconds(),

			 dataType:"JSON",

			 type:"POST",

			 success:function(data){

				 var dept 	= data.data;

				 var access		= data.access;	

				 $('#total_depts').text(dept.length);

				  if(access.add == '0')

				 { 

			 			

					$('#add_dpt').attr('disabled','disabled');

					

				 }

				 else{

					$("#add_dept").on('click',function(){

					if($('#department').val())

					{			

						add_dept($('#department').val());

						$('#department').val('');

					}

		

					});	

	

				 }

			 var oTable = $('#dept_list').DataTable();

				 oTable.clear().draw();

						 if (dept!= null && dept.length > 0)

							{  	

							oTable = $('#dept_list').dataTable({

									"bDestroy": true,

									"bInfo": true,

									"bFilter": true,

									"bSort": true,

									"aaData": dept,

									"aoColumns": [	{ "mDataProp": "id_dept" },

													{ "mDataProp": "name" },

													{ "mDataProp": function ( row, type, val, meta ) {

														 id= row.id_dept;

                	 									 edit_target=(access.edit=='0'?"":"#confirm-edit");

														 delete_url=(access.delete=='1' ? base_url+'index.php/settings/dept/delete/'+id : '#' );

														 delete_confirm= (access.delete=='1' ?'#confirm-delete':'');

														 action_content='<a href="#" class="btn btn-primary btn-edit" id="edit" role="button" data-toggle="modal" data-id='+id+'  data-target='+edit_target+'><i class="fa fa-edit" ></i> Edit</a> <a href="#" class="btn btn-danger btn-del" data-href='+delete_url+' data-toggle="modal" data-target="#confirm-delete" ><i class="fa fa-trash"></i> Delete</a>'

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



//to load values to table

function set_offers_table()
{
	my_Date = new Date();
	$.ajax({
	 url:base_url+ "index.php/settings/offers_list?nocache=" + my_Date,
	 dataType:"JSON",
	 type:"POST",
	 success:function(data){
		var offersBanners 	= data.data.offersBanners;
		var popup 	= data.data.popup;
		var access		= data.access;	
		$('#total_offers').text(offersBanners.length);
		$('#total_popup').text(popup.length);
		if(access.add == '0')
		{ 
			$('#popup_list').attr('disabled','disabled');
		}
		// Popup
		var oTable1 = $('#popup_list').DataTable();
		oTable1.clear().draw();
		 if (popup!= null && popup.length > 0)
		 {  	
			oTable1 = $('#popup_list').dataTable({
					"bDestroy": true,
					"bInfo": true,
					"bFilter": true,
					"bSort": true,
					"aaData": popup,
					"aoColumns": [	{ "mDataProp": "id_offer" },
									 { "mDataProp": function ( row, type, val, meta ) {
										 action_content='<img src="'+row.offer_img_path+'" width="80px" heigth="100px">' 
										 return action_content;
									 }},
									 { "mDataProp": "name"},
									 { "mDataProp": function ( row, type, val, meta ) {
										 var id= row.id_offer;
								 		 edit_url	=(access.edit=='1' ? base_url+'index.php/settings/offers/edit/'+id : '#' );
										 delete_url=(access.delete=='1' ? base_url+'index.php/settings/offers/delete/'+id : '#' );
										 delete_confirm= (access.delete=='1' ?'#confirm-delete':'');
										 action_content='<a href="'+edit_url+'" class="btn btn-primary btn-edit"><i class="fa fa-edit" ></i> Edit</a> <a href="#" class="btn btn-danger btn-del" data-href='+delete_url+' data-toggle="modal" data-target="#confirm-delete" ><i class="fa fa-trash"></i> Delete</a>'
										 return action_content;
										 }
									   
									 }]
				});			  	 	
			}
		// Offer / Banner
		var oTable = $('#offer_list').DataTable();
		oTable.clear().draw();
		 if (offersBanners!= null && offersBanners.length > 0)
		 {  	
			oTable = $('#offer_list').dataTable({
					"bDestroy": true,
					"bInfo": true,
					"bFilter": true,
					"bSort": true,
					"aaData": offersBanners,
					"aoColumns": [	{ "mDataProp": "id_offer" },
									 { "mDataProp": function ( row, type, val, meta ) { 
										 return (row.type == 0 ? 'Offer':'Banner');
									 }},
									 { "mDataProp": function ( row, type, val, meta ) {
									 action_content='<img src="'+row.offer_img_path+'" width="80px" heigth="100px">' 
									 return action_content;
									 }},
									 { "mDataProp": "name"},
									{ "mDataProp": function ( row, type, val, meta ) {
										var id= row.id_offer;
								 edit_url	=(access.edit=='1' ? base_url+'index.php/settings/offers/edit/'+id : '#' );
										 delete_url=(access.delete=='1' ? base_url+'index.php/settings/offers/delete/'+id : '#' );
										 delete_confirm= (access.delete=='1' ?'#confirm-delete':'');
										 action_content='<a href="'+edit_url+'" class="btn btn-primary btn-edit"><i class="fa fa-edit" ></i> Edit</a> <a href="#" class="btn btn-danger btn-del" data-href='+delete_url+' data-toggle="modal" data-target="#confirm-delete" ><i class="fa fa-trash"></i> Delete</a>'
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



//to load values to table

function set_new_arrivals_table()

{

	my_Date = new Date();

	$.ajax({

			 url:base_url+ "index.php/settings/new_arrivals_list?nocache=" + my_Date,

			 dataType:"JSON",

			 type:"POST",

			 cache:false,

			 success:function(data){

				 var arrivals 	= data.data;

				 var access		= data.access;	

				 $('#total_new_arrivals').text(arrivals.length);

				  if(access.add == '0')

				 { 

			 			

					$('#add_offer').attr('disabled','disabled');

					

				 }

			 var oTable = $('#new_arrivals_list').DataTable();

				 oTable.clear().draw();

						 if (arrivals!= null && arrivals.length > 0)

							{  	

							oTable = $('#new_arrivals_list').dataTable({

									"bDestroy": true,

									"bInfo": true,

									"bFilter": true,

									"bSort": true,

									"aaData": arrivals,

									"aoColumns": [	{ "mDataProp": "id_new_arrivals" },

													{ "mDataProp": "product_code" },

													 { "mDataProp": function ( row, type, val, meta ) {

													 action_content='<img src="'+row.new_arrivals_img_path+'" width="80px" heigth="100px">' 

													 return action_content;

													 }},

													 { "mDataProp": "name"},

													 { "mDataProp": "price" },

													{ "mDataProp": function ( row, type, val, meta ) {

														var id= row.id_new_arrivals;

                	 									 edit_url	=(access.edit=='1' ? base_url+'index.php/settings/new_arrivals/edit/'+id : '#' );

														 delete_url=(access.delete=='1' ? base_url+'index.php/settings/new_arrivals/delete/'+id : '#' );

														 delete_confirm= (access.delete=='1' ?'#confirm-delete':'');

														 action_content='<a href="'+edit_url+'" class="btn btn-primary btn-edit"><i class="fa fa-edit" ></i> Edit</a> <a href="#" class="btn btn-danger btn-del" data-href='+delete_url+' data-toggle="modal" data-target="#confirm-delete" ><i class="fa fa-trash"></i> Delete</a>'

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



//to load values to table



function set_bank_table()

{

	my_Date = new Date();

	$.ajax({

			 url:base_url+ "index.php/settings/bank/ajax?nocache=" + my_Date.getUTCSeconds(),

			 dataType:"JSON",

			 type:"POST",

			 success:function(data){

				 var bank 	= data.data;

				 var access		= data.access;	

				 $('#total_banks').text(bank.length);

				  if(access.add == '0')

				 { 

			 			

					$('#add_bnk').attr('disabled','disabled');

					

				 }

				 

			 var oTable = $('#bank_list').DataTable();

				 oTable.clear().draw();

						 if (bank!= null && bank.length > 0)

							{  	

							oTable = $('#bank_list').dataTable({

									"bDestroy": true,

									"bInfo": true,

									"bFilter": true,

									"bSort": true,

									"aaData": bank,

									"aoColumns": [	{ "mDataProp": "id_bank" },

													{ "mDataProp": "bank_name" },

													{ "mDataProp": "short_code" },

													{ "mDataProp": function ( row, type, val, meta ) {

														 id= row.id_bank;

                	 									 edit_url=(access.edit=='1'?base_url+'index.php/settings/bank/edit/'+id:'#');

														 delete_url=(access.delete=='1' ? base_url+'index.php/settings/bank/delete/'+id : '#' );

														 delete_confirm= (access.delete=='1' ?'#confirm-delete':'');

														  action_content='<div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button><ul class="dropdown-menu">'+

															'<li><a href="'+edit_url+'" class="btn-edit"><i class="fa fa-edit" ></i> Edit</a></li>'+

															'<li><a href="#" class=" btn-del" data-href='+delete_url+' data-toggle="modal" data-target="#confirm-delete" ><i class="fa fa-trash"></i> Delete</a></li>';

														

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


function set_gift_table()

{

	my_Date = new Date();

	$.ajax({

			 url:base_url+ "index.php/settings/gift/ajax?nocache=" + my_Date.getUTCSeconds(),

			 dataType:"JSON",

			 type:"POST",

			 success:function(data){

				 var gift 	= data.data;

				 var access		= data.access;	

				 $('#total_banks').text(gift.length);

				  if(access.add == '0')

				 { 

			 			

					$('#add_bnk').attr('disabled','disabled');

					

				 }

				 

			 var oTable = $('#gift_list').DataTable();

				 oTable.clear().draw();

						 if (gift!= null && gift.length > 0)

							{  	

							oTable = $('#gift_list').dataTable({

									"bDestroy": true,

									"bInfo": true,

									"bFilter": true,

									"bSort": true,

									"aaData": gift,

									"aoColumns": [	{ "mDataProp": "id_gift" },

													{ "mDataProp": "gift_name" },

													{ "mDataProp": "status" },

													{ "mDataProp": function ( row, type, val, meta ) {

														 id= row.id_gift;

                	 									 edit_url=(access.edit=='1'?base_url+'index.php/settings/gift/edit/'+id:'#');

														 delete_url=(access.delete=='1' ? base_url+'index.php/settings/gift/delete/'+id : '#' );

														 delete_confirm= (access.delete=='1' ?'#confirm-delete':'');

														  action_content='<div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button><ul class="dropdown-menu">'+

															'<li><a href="#" class="btn-edit" data-href='+edit_url+' data-toggle="modal" data-target="#gift_form" onclick="set_gift_form('+id+')"><i class="fa fa-edit" ></i> Edit</a></li>'+

															'<li><a href="#" class=" btn-del" data-href='+delete_url+' data-toggle="modal" data-target="#confirm-delete" ><i class="fa fa-trash"></i> Delete</a></li>';

														

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

function set_gift_form(id=""){
    
    if(id == ''){
        $('#form_type').val('ADD');
    }else{
       
       
       	$.ajax({

			 url:base_url+ "index.php/admin_settings/get_gift_name_byId?nocache=" + my_Date.getUTCSeconds(),

			 dataType:"JSON",

			 type:"POST",
			 
			 data:{"id":id},

			 success:function(data){

			     $('#id_gift_modal').val(id); 
			     
			     $('#gift_name_modal').val(data.gift_name); 
			     
			     $('#form_type').val('EDIT');
			 }
       	    
       	    
       	});
       
    }
}

	$("#gift_modal_submit").on('click',function(){
        
        
        
        var id = $('#id_gift_modal').val(); 
			     
		var name = $('#gift_name_modal').val(); 
			     
		var form_type = $('#form_type').val();
		
		if(name != ''){	     
        if(form_type == 'ADD'){
            
            $.ajax({
    
    			 url:base_url+ "index.php/admin_settings/add_gift",
    
    			 dataType:"JSON",
    
    			 type:"POST",
    			 
    			 data:{"gift_name":name},
    
    			 success:function(data){
    
    			     $.toaster({ priority : 'success', title : 'Warning!', message : ''+"</br>Gift added successfully..."});
    			     
    			     window.location.href = base_url+ "index.php/settings/gift/list"; 
    			 }
           	    
           	    
           	});
       	
        }else{
            $.ajax({
    
    			 url:base_url+ "index.php/admin_settings/update_gift",
    
    			 dataType:"JSON",
    
    			 type:"POST",
    			 
    			 data:{"id":id,"gift_name":name},
    
    			 success:function(data){
    
    			     $.toaster({ priority : 'success', title : 'Warning!', message : ''+"</br>Gift updated successfully..."})
    			     
    			     window.location.href = base_url+ "index.php/settings/gift/list"; 
    			 }
           	    
           	    
           	});
        }
		}else{
		   $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>Fill the gift name..."}) 
		}
	});
				
				

//to load design values to table

function set_design_table()

{

	my_Date = new Date();

	$.ajax({

			 url:base_url+ "index.php/settings/design_list?nocache=" + my_Date.getUTCSeconds(),

			 dataType:"JSON",

			 type:"POST",

			 success:function(data){

				 var design 	= data.data;

				 var access		= data.access;	

				 $('#total_designations').text(design.length);

				  if(access.add == '0')

				 { 

			 			

					$('#add_designation').attr('disabled','disabled');

					

				 }

				 else{

					$("#add_design").on('click',function(){

					if($('#designation').val())

					{			

						add_design($('#designation').val());

						$('#designation').val('');

					}

					

				});

	

				 }

			 var oTable = $('#design_list').DataTable();

				 oTable.clear().draw();

						 if (design!= null && design.length > 0)

							{  	

							oTable = $('#design_list').dataTable({

									"bDestroy": true,

									"bInfo": true,

									"bFilter": true,

									"bSort": true,

									"aaData": design,

									"aoColumns": [	{ "mDataProp": "id_design" },

													{ "mDataProp": "name" },

													{ "mDataProp": function ( row, type, val, meta ) {

														 id= row.id_design;

                	 									 edit_target=(access.edit=='0'?"":"#confirm-edit");

														 delete_url=(access.delete=='1' ? base_url+'index.php/settings/design/delete/'+id : '#' );

														 delete_confirm= (access.delete=='1' ?'#confirm-delete':'');

														 action_content='<a href="#" class="btn btn-primary btn-edit" id="edit" role="button" data-toggle="modal" data-id='+id+'  data-target='+edit_target+'><i class="fa fa-edit" ></i> Edit</a> <a href="#" class="btn btn-danger btn-del" data-href='+delete_url+' data-toggle="modal" data-target="#confirm-delete" ><i class="fa fa-trash"></i> Delete</a>'

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







//update weight

function update_weight(weight,id)

{

	my_Date = new Date();

    

	$.ajax({

		data:{"weight":weight},

		url: base_url+"index.php/settings/weight/update/"+id+"?nocache=" + my_Date.getUTCSeconds(),

		type:"POST",	

		cache: false,

		success:function(){

			console.log(get_weight(id));

			window.location.reload(true);

			//set_table();

		}

			

		

		

	});

}



//get weight by id

function get_weight(id)

{

   my_Date = new Date();

	$.ajax({

		type:"GET",

		url: base_url+"index.php/settings/weight/edit/"+id+"?nocache=" + my_Date.getUTCSeconds(),

		cache:false,		

		dataType:"JSON",

		success:function(data){

			wt=data.weight;

		    $('#ed_weight').val(wt);

		   // console.log(wt);

		   

		

		}

			

		

		

	});

}





//update Classification

function update_classification(clsfy,id)

{
	my_Date = new Date();
	var classification_name=clsfy.classification_name;
	var description=clsfy.description;
	var file=clsfy.file;
	 
	my_Date = new Date();
    var form_data = new FormData();  
    form_data.append('file', file);
    form_data.append('classification_name', classification_name);
    form_data.append('description', description);
		$.ajax({
		data:form_data,
		url: base_url+"index.php/settings/classification/update/"+id+"?nocache=" + my_Date.getUTCSeconds(),
		type:"POST",
        dataType:'json',
        cache : false,
		enctype: 'multipart/form-data',
		contentType : false,
		processData : false,
		success:function(){ 
		//	  window.location.reload(true); Old 05-12-2022
		
		window.location.reload(true); //New 05-12-2022
		}
	});
}



//get weight by id

function get_classification(id)

{

   my_Date = new Date();

	$.ajax({

		type:"GET",

		url: base_url+"index.php/settings/classification/edit/"+id+"?nocache=" + my_Date.getUTCSeconds(),

		cache:false,		

		dataType:"JSON",

		success:function(data){

    		classification_name = data.classification_name;
			description = data.description;
			logo = data.logo;
		    $('#ed_clsfy').val(classification_name);
		    if(logo!="" && logo!=null)
		    {
		    	 var img=base_url+"assets/img/sch_classify/"+logo;
		    	 $("#edit_sch_clsfy_img_preview").attr('src',img);
		    }
		    else
		    {
		    	var img=base_url+"assets/img/no_image.png";
		    	$("#edit_sch_clsfy_img_preview").attr('src',img);
		    }
		   CKEDITOR.instances.description1.setData(description);
		}

			

		

		

	});

}



//get dept by id

function get_dept(id)

{

   my_Date = new Date();

	$.ajax({

		type:"GET",

		url: base_url+"index.php/settings/dept/edit/"+id+"?nocache=" + my_Date.getUTCSeconds(),

		cache:false,		

		dataType:"JSON",

		success:function(data){

			dept=data.name;

		    $('#ed_dept').val(dept);

		   // console.log(wt);

		}

			

		

		

	});

}



//get design by id

function get_design(id)

{

   my_Date = new Date();

	$.ajax({

		type:"GET",

		url: base_url+"index.php/settings/design/edit/"+id+"?nocache=" + my_Date.getUTCSeconds(),

		cache:false,		

		dataType:"JSON",

		success:function(data){

			dept=data.name;

		    $('#ed_design').val(dept);

		   // console.log(wt);

		}

			

		

		

	});

}



//to add dept 

function add_dept(dept)

{

	my_Date = new Date();

    

	$.ajax({

		data:{"dept":dept},

		url: base_url+"index.php/settings/dept/add?nocache=" + my_Date.getUTCSeconds(),

		type:"POST",

		cache:false,

		success:function(data){

				location.reload(true);

		}

			

		

		

	});

}



//update dept

function update_dept(dept,id)

{

	my_Date = new Date();

    

	$.ajax({

		data:{"dept":dept},

		url: base_url+"index.php/settings/dept/update/"+id+"?nocache=" + my_Date.getUTCSeconds(),

		type:"POST",

		

		cache: false,

		success:function(){

			window.location.reload(true);

			

		}

			

		

		

	});

}





//to add design

function add_design(design)

{

	my_Date = new Date();

    

	$.ajax({

		data:{"design":design},

		url: base_url+"index.php/settings/design/add?nocache=" + my_Date.getUTCSeconds(),

		type:"POST",

		cache:false,		

		success:function(data){

			//$('#department').val('');

			window.location.reload(true);

		}

			

		

		

	});

}





//update design

function update_design(design,id)

{

	my_Date = new Date();

    

	$.ajax({

		data:{"design":design},

		url: base_url+"index.php/settings/design/update/"+id+"?nocache=" + my_Date.getUTCSeconds(),

		type:"POST",

		

		cache: false,

		success:function(){

			//console.log(get_weight(id));

			window.location.reload(true);

			//set_table();

		}

			

		

		

	});

}



function excel_format(type)

{

	var xl_format;

	var account="<table class='table table-bordered'>";

		account+="<tr><td>First Name</td><td>Last Name</td><td>Mobile</td><td>Email</td><td>Pan</td><td>Ref No</td><td>A/c Name</td><td>Scheme Code</td><td>Start Date</td><td>No of Paid Installments</td><td>Total Paid Amount</td><td>Total Paid Weight</td><td>Last Paid Weight</td><td>Last Paid Chance</td><td>Last Paid Date</td><td>Is New Customer</td></tr></table>";

	xl_format=account;

	$('#excel_format').html(xl_format);

}

function customer_excel_format(type)

{

	var xl_format;

	var account="<table class='table table-bordered'>";

		account+="<tr><td>First Name</td><td>Last Name</td><td>Mobile</td><td>Email</td><td>Pan</td><td>Address1</td><td>Address2</td><td>Address3</td><td>Pincode</td><td>City</td><td>State</td><td>Country</td><td>Nominee Name</td><td>Nominee Relationship</td><td>Nominee Mobile</td><td>Nominee Address1</td><td>Nominee Address2</td><td>Is New</td><td>Branch Id</td></tr></table>";

	xl_format=account;

	$('#customer_excel_format').html(xl_format);

}

/*******  Permission tab **********/



$('#project_status').find(".tab-access").on("change","[name='row_access']", function(e) {  
        var curRow = $(this).closest('tr');
   if($(this).is(":checked"))
    {
curRow.find('.access_view').prop('checked', true);
curRow.find('.access_add').prop('checked', true);
curRow.find('.access_edit').prop('checked', true);
curRow.find('.access_delete').prop('checked', true);

    }else{
curRow.find('.access_view').prop('checked', false);
curRow.find('.access_add').prop('checked', false);
curRow.find('.access_edit').prop('checked', false);
curRow.find('.access_delete').prop('checked', false);
    }
get_tab_values();
});

$('#project_status').find(".tab-access").on("change",".access_view,.access_add,.access_edit,.access_delete", function(e) {
    var curRow = $(this).closest('tr');
if($(this).is(":checked"))
    {
curRow.find('.access_row').prop('checked', true);
    }else{
curRow.find('.access_row').prop('checked', false);
$(".selectall_access").attr("checked", false);
    }
get_tab_values();
});
$('#project_status').find(".tab-access").on("change","[name='selectall_view'],[name='selectall_add'],[name='selectall_edit'],[name='selectall_delete']", function(e) {
    var curRow = $(this).closest('tr');
if($(this).is(":checked"))
    {
//curRow.find('.access_row').prop('checked', true);
    }else{
curRow.find('.access_row').prop('checked', false);
$(".selectall_access").attr("checked", false);
$('#project_status').find(".tab-content .active .access_row").prop('checked', $(this).prop('checked'));
    }
get_tab_values();
});
//Select row all

$('#project_status').find(".tab-access").on("change","[name='selectall_row']", function(e) {  
      $('#project_status').find("tr input:checkbox").prop('checked', $(this).prop('checked'));
get_tab_values();
});

//view all

$('#project_status').find(".tab-access").on("change","[name='selectall_view']", function(e) {  

    $('#project_status').find(".tab-content .active .access_view").prop('checked', $(this).prop('checked'));
   
get_tab_values();
});

//add all

$('#project_status').find(".tab-access").on("change","[name='selectall_add']", function(e) {  

   $('#project_status').find(".tab-content .active .access_add").prop('checked', $(this).prop('checked'));  

 get_tab_values();

});



//edit all

$('#project_status').find(".tab-access").on("change","[name='selectall_edit']", function(e) {  

   $('#project_status').find(".tab-content .active .access_edit").prop('checked', $(this).prop('checked'));

   get_tab_values();

});


//delete all

$('#project_status').find(".tab-access").on("change","[name='selectall_delete']", function(e) {  

   $('#project_status').find(".tab-content .active .access_delete").prop('checked', $(this).prop('checked'));

    get_tab_values();

});


//check box other than select all

$('#project_status').find(".tab-access").on("change", "[type='checkbox']:not(.selectall_access)", function() {

var table_data = [];

var values = {};

var row = $(this).closest("tr");  

var tds = row.find("td input");    

  $.each(tds, function() {              

   

     if($(this).attr('type') == 'checkbox')

 {

  values[$(this).attr('name')] =($(this).is(':checked')?$(this).val():0);

 }

 else

 {

  values[$(this).attr('name')]=$(this).val();

 }

});



table_data.push(values);
// post_access(table_data);

});

$('#retail').find(".tab-access").on("change","[name='row_access_dash']", function(e) {  
        var curRow = $(this).closest('tr');
   if($(this).is(":checked"))
    {
curRow.find('.access_view').prop('checked', true);
curRow.find('.access_add').prop('checked', true);
curRow.find('.access_edit').prop('checked', true);
curRow.find('.access_delete').prop('checked', true);

    }else{
curRow.find('.access_view').prop('checked', false);
curRow.find('.access_add').prop('checked', false);
curRow.find('.access_edit').prop('checked', false);
curRow.find('.access_delete').prop('checked', false);
    }
get_dashboardtab_values();
});

$('#retail').find(".tab-access").on("change",".access_view,.access_add,.access_edit,.access_delete", function(e) {
    var curRow = $(this).closest('tr');
if($(this).is(":checked"))
    {
curRow.find('.access_row_dash').prop('checked', true);
    }else{
curRow.find('.access_row_dash').prop('checked', false);
$(".selectall_dashboardaccess").attr("checked", false);
    }
get_dashboardtab_values();
});
$('#retail').find(".tab-access").on("change","[name='selectall_dashboardview'],[name='selectall_dashboardadd'],[name='selectall_dashboardedit'],[name='selectall_dashboarddelete']", function(e) {
    var curRow = $(this).closest('tr');
if($(this).is(":checked"))
    {
//curRow.find('.access_row').prop('checked', true);
    }else{
curRow.find('.access_row_dash').prop('checked', false);
$(".selectall_dashboardaccess").attr("checked", false);
$('#retail').find(".tab-content .active .access_row_dash").prop('checked', $(this).prop('checked'));
    }
get_dashboardtab_values();
});
//Select row all

$('#retail').find(".tab-access").on("change","[name='selectall_dashboardrow']", function(e) {  
      $('#retail').find("tr input:checkbox").prop('checked', $(this).prop('checked'));
get_dashboardtab_values();
});

//view all

$('#retail').find(".tab-access").on("change","[name='selectall_dashboardview']", function(e) {  

   $('#retail').find(".tab-content .active .access_view").prop('checked', $(this).prop('checked'));
   
   get_dashboardtab_values();  

});



//add all

$('#retail').find(".tab-access").on("change","[name='selectall_dashboardadd']", function(e) {  

   $('#retail').find(".tab-content .active .access_add").prop('checked', $(this).prop('checked'));  

   get_dashboardtab_values();

});



//edit all

$('#retail').find(".tab-access").on("change","[name='selectall_dashboardedit']", function(e) {  

   $('#retail').find(".tab-content .active .access_edit").prop('checked', $(this).prop('checked'));

   get_dashboardtab_values();

});



//delete all

$('#retail').find(".tab-access").on("change","[name='selectall_dashboarddelete']", function(e) {  

   $('#retail').find(".tab-content .active .access_delete").prop('checked', $(this).prop('checked'));

   get_dashboardtab_values();

});


$('#retail').find(".tab-access").on("change", "[type='checkbox']:not(.selectall_dashboardaccess)", function() {
    var table_data = [];
    
    var values = {};
    
    var row = $(this).closest("tr");  
    
    var tds = row.find("td input");    
    
      $.each(tds, function() {              
    
       
    
         if($(this).attr('type') == 'checkbox')
    
     {
    
      values[$(this).attr('name')] =($(this).is(':checked')?$(this).val():0);
    
     }
    
     else
    
     {
    
      values[$(this).attr('name')]=$(this).val();
    
     }
    
    });
    
    table_data.push(values);
    
    //post_dashbaordaccess(table_data);

});



//click tab event

$(".tab-access").on("click","ul.nav.nav-pills li a", function(e) {    

  var id_profile = $(this).data("id");

  generate_access_table(id_profile)

});







function set_permission_view()

{

    $("div.overlay").css("display", "block");
    
    my_Date = new Date();
    
    $.ajax({
    
    url: base_url+"index.php/settings/profile/ajax_list?nocache=" + my_Date.getUTCSeconds(),
    
    type:"GET",
    
    dataType:"JSON",
    
    cache: false,
    
    success:function(data){
    
    //set_table();
    
    var profile   = "";
    
    var content   = "";
    
    var dashboard_profile = "";
    
    var dashboard_content = "";
    
    var id_profile="";
    
    $.each(data.profile, function(i, item) {
    
    profile+="<li "+(i=='0' ?'class=active':'')+"><a href='#tab_"+item.id_profile+"' data-id='"+item.id_profile+"' data-toggle='pill'>"+item.profile_name+"</a></li>";
    
    content+="<div class='tab-pane "+(i=='0'?'active':'')+"' id='tab_"+item.id_profile+"'><p></p></div>";
    
    // Dashboard Records
    dashboard_profile+="<li "+(i=='0' ?'class=active':'')+"><a href='#tab_dash"+item.id_profile+"' data-id='"+item.id_profile+"' data-toggle='pill'>"+item.profile_name+"</a></li>";
    
    dashboard_content+="<div class='tab-pane "+(i=='0'?'active':'')+"' id='tab_dash"+item.id_profile+"'><p></p></div>";
    
    if(i==0)
    
    {
    
    id_profile=item.id_profile;
    
    }
    });
    $('#project_status').find('.tab-access ul').append(profile);
    
    $('#project_status').find('.tab-access .tab-content').append(content);
    
    //Dashboard Menu Records
    
    $('#retail').find('.tab-access ul').append(dashboard_profile);
    
    $('#retail').find('.tab-access .tab-content').append(dashboard_content);
    
            generate_access_table(id_profile);
    
    $("div.overlay").css("display", "none");
    
      }
    
    
    
    });

}



function generate_access_table(id_profile)
{
    var access_table           = "";
    
    var dashboard_access_table = "";
    
    $("div.overlay").css("display", "block");
    
    my_Date = new Date();
    
    $.ajax({
    
    url: base_url+"index.php/settings/access/ajax_list/"+id_profile+"?nocache=" + my_Date.getUTCSeconds(),
    
    type:"GET",
    
    dataType:"JSON",
    
    cache: false,
    
    success:function(data){
    access_table ="<table class='table access-table' id='access_"+id_profile+"'><theader><tr><th>Menu</th>"+
                        "<th><div class='checkbox'><label><input type='checkbox' class='selectall_access' name='selectall_row' value='1'> Select All</label></div></th>"+
    
       "<th><div class='checkbox'><label><input type='checkbox' class='selectall_access' name='selectall_view' value='1'> View</label></div></th>"+
    
       "<th><div class='checkbox'><label><input type='checkbox' class='selectall_access' name='selectall_add' value='1'> Add</label></div></th>"+
    
       "<th><div class='checkbox'><label><input type='checkbox' class='selectall_access' name='selectall_edit' value='1'> Edit</label></div></th>"+
    
       "<th><div class='checkbox'><label><input type='checkbox' class='selectall_access' name='selectall_delete' value='1'> Delete</label></div></th>"+
    
       "</tr></theader><tbody>";
          $.each(data.menu, function(i, item) {
    access_table+="<tr class="+(item.submenus>0? 'parent_menu' : '')+"><td>"+item.label+"<input type='hidden' name='id_profile' value='"+id_profile+"'  /><input type='hidden' name='id_menu' value='"+item.id_menu+"'  /></td>"+
    
    "<td><input type='checkbox' class='access_row' name='row_access'  value='1' "+(item.view=='1'&&item.add=='1'&&item.edit=='1'&&item.delete=='1'? 'checked="checked"':'')+"'></td>"+
    
    "<td><input type='checkbox' class='access_view' name='view'  value='1' "+(item.view=='1'? 'checked="checked"':'')+"'></td>"+
    
    "<td><input type='checkbox' class='access_add' name='add'   value='1' "+(item.add=='1'? 'checked="checked"':'')+"'></td>"+
    
    "<td><input type='checkbox' class='access_edit' name='edit' value='1' "+(item.edit=='1'? 'checked="checked"':'')+"'></td>"+
    
    "<td><input type='checkbox' class='access_delete' name='delete' value='1' "+(item.delete=='1'? 'checked="checked"':'')+"'></td>"+
    
    "</tr>";
    
    });
        access_table += "</tbody></table>";
    $('#project_status').find('.tab-access .tab-content #tab_'+id_profile+" ").html(access_table);
    
    // DashBoard Menu Access
    dashboard_access_table = "<table class='table dashboard-access-table' id='dashboard_access_"+id_profile+"'><theader><tr><th>Menu</th>"+
                        "<th><div class='checkbox'><label><input type='checkbox' class='selectall_dashboardaccess' name='selectall_dashboardrow' value='1'> Select All</label></div></th>"+
    
       "<th><div class='checkbox'><label><input type='checkbox' class='selectall_dashboardaccess' name='selectall_dashboardview' value='1'> View</label></div></th>"+
    
       "<th><div class='checkbox'><label><input type='checkbox' class='selectall_dashboardaccess' name='selectall_dashboardadd' value='1'> Add</label></div></th>"+
    
       "<th><div class='checkbox'><label><input type='checkbox' class='selectall_dashboardaccess' name='selectall_dashboardedit' value='1'> Edit</label></div></th>"+
    
       "<th><div class='checkbox'><label><input type='checkbox' class='selectall_dashboardaccess' name='selectall_dashboarddelete' value='1'> Delete</label></div></th>"+
    
       "</tr></theader><tbody>";
    
          $.each(data.dashboard_menu, function(i, item) {
    dashboard_access_table+="<tr class="+(item.submenus>0? 'parent_menu' : '')+"><td>"+item.label+"<input type='hidden' name='id_profile' value='"+id_profile+"'  /><input type='hidden' name='id_menu' value='"+item.id_menu+"'  /></td>"+
    
    "<td><input type='checkbox' class='access_row_dash' name='row_access_dash'  value='1' "+(item.view=='1'&&item.add=='1'&&item.edit=='1'&&item.delete=='1'? 'checked="checked"':'')+"'></td>"+
    
    "<td><input type='checkbox' class='access_view' name='view'  value='1' "+(item.view=='1'? 'checked="checked"':'')+"'></td>"+
    
    "<td><input type='checkbox' class='access_add' name='add'   value='1' "+(item.add=='1'? 'checked="checked"':'')+"'></td>"+
    
    "<td><input type='checkbox' class='access_edit' name='edit' value='1' "+(item.edit=='1'? 'checked="checked"':'')+"'></td>"+
    
    "<td><input type='checkbox' class='access_delete' name='delete' value='1' "+(item.delete=='1'? 'checked="checked"':'')+"'></td>"+
    
    "</tr>";
    
    });
    dashboard_access_table += "</tbody></table>";
    $('#retail').find('.tab-access .tab-content #tab_dash'+id_profile+" ").html(dashboard_access_table);
    
    
    //return access_table;
    
    
    
    
    $("div.overlay").css("display", "none");
    
    
    
    }
    
    });


}






//Get all selected values


function get_tab_values()

{
   
    var table_data = [];
    
        var values = {};
    
        $("#project_status").find(".tab-content .active table > tbody > tr").each(function(i){
    
            values = new Object;
    
            $('td input', this).each(function(){
    
             if($(this).attr('type') == 'checkbox')
    
     {
    
      values[$(this).attr('name')] =($(this).is(':checked')?$(this).val():0);
    
     }
    
     else
    
     {
    
      values[$(this).attr('name')]=$(this).val();
    
     }
    
               
    
            });
    
            table_data.push(values);
    
        });
    
         
    
         //removes the first elemet
    
         table_data.shift();
         post_access(table_data);

}

function get_dashboardtab_values()

{

    var table_data = [];
    
        var values = {};
    
        $("#retail").find(".tab-content .active table > tbody > tr").each(function(i){
    
            values = new Object;
    
            $('td input', this).each(function(){
    
             if($(this).attr('type') == 'checkbox')
    
     {
    
      values[$(this).attr('name')] =($(this).is(':checked')?$(this).val():0);
    
     }
    
     else
    
     {
    
      values[$(this).attr('name')]=$(this).val();
    
     }
    
               
    
            });
    
            table_data.push(values);
    
        });
    
         //removes the first elemet
    
         table_data.shift();
    
         post_dashbaordaccess(table_data);

}



//post access values

function post_access(table_data)

{
    $("div.overlay").css("display", "block");
    
    var postData ={'access_data':JSON.stringify(table_data)};
    
    var my_Date = new Date();
    
    $.ajax({
    
    url : base_url+"index.php/settings/access/add",
    
    type : "POST",
    
    data : postData,
    
    //dataType: 'json',
    
    success: function(result)
    
    {
      $("div.overlay").css("display", "none");
                     $.toaster({ priority : result.class, title : result.class, message : ''+"</br>"+"Permission updated successfully."});
      /*$('#access-alert').delay(500).fadeIn('normal', function() {
    
       $(this).find("p").html(result);
    
       $(this).addClass("alert-success ");
    
         $(this).delay(1000).fadeOut();
    
    });*/
    
    },
    
    error:function(error)
    
    {
    
    console.log(error);
    
    $("div.overlay").css("display", "none");
    
    $('#access-alert').delay(500).fadeIn('normal', function() {
    
       $(this).find("p").html("Unable to proceed request");
    
       $(this).addClass("alert-danger ");
    
         $(this).delay(2500).fadeOut();
    
    });
    
    }
    
    });

}


//post access values

function post_dashbaordaccess(table_data)

{

    $("div.overlay").css("display", "block");
    
    var postData ={'access_data':JSON.stringify(table_data)};
    
    var my_Date = new Date();
    
    $.ajax({
    
    url : base_url+"index.php/settings/access/dashboard_add",
    
    type : "POST",
    
    data : postData,
    
    //dataType: 'json',
    
    success: function(result)
    {
      $("div.overlay").css("display", "none");
    $.toaster({ priority : result.class, title : result.class, message : ''+"</br>"+"Permission updated successfully."});
    
      /*$('#access-alert').delay(500).fadeIn('normal', function() {
    
       $(this).find("p").html(result);
    
       $(this).addClass("alert-success ");
    
         $(this).delay(1000).fadeOut();
    
    });*/
    
    },
    
    error:function(error)
    
    {
    
    console.log(error);
    
    $("div.overlay").css("display", "none");
    
    $('#access-alert').delay(500).fadeIn('normal', function() {
    
       $(this).find("p").html("Unable to proceed request");
    
       $(this).addClass("alert-danger ");
    
         $(this).delay(2500).fadeOut();
    
    });
    
    }
    
    });

}



/******* End Permission tab **********/

function load_metalrate_list()

{
	
	my_Date = new Date();

	$("div.overlay").css("display", "block"); 

		var oTable = $('#metalrate_list').DataTable();

		$.ajax({

				  type: 'GET',

				  url:  base_url+'index.php/settings/rate/ajax_list?nocache='+ my_Date.getUTCSeconds(),

				  dataType: 'json',

				  success: function(data) {
					  
					  var access=data.access;

						

						$('#total_metals').text(data.rates.length);

						 if(access.add == '0')

						 {

							$('#add_metal').attr('disabled','disabled');

						 }

			          var max_id = data.max_id;

				       oTable = $('#metalrate_list').dataTable({

				                "bDestroy": true,

				                "bInfo": true,

				                "bFilter": true,

				                "bSort": true,

				                "aaSorting": [[ 0, "desc" ]], 

				                "aaData": data.rates,

				                "aoColumns": [

							                    { "mDataProp": "id_metalrates" },

							                    { "mDataProp": "updatetime" },
							                    { "mDataProp": "mjdmagoldrate_22ct" },
												
							         { "mDataProp": "mjdmasilverrate_1gm" },
							         
							         { "mDataProp": "market_gold_18ct" },
							                     
							                     
							                     
							                    { "mDataProp": "goldrate_18ct" },

								                { "mDataProp": "goldrate_22ct" },

								                { "mDataProp": "goldrate_24ct" },

								                { "mDataProp": "silverrate_1gm" },

								                { "mDataProp": "silverrate_1kg" },	
								                
								                { "mDataProp": "platinum_1g" },

								                { "mDataProp": "employee" },		

								                { "mDataProp": function ( row, type, val, meta ) {

							                	 id         = row.id_metalrates;

												  edit_url   = (access.edit=='1'?base_url+'index.php/settings/rate/edit/'+id:"#");

							                	 

							                	 action_content='<div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button><ul class="dropdown-menu">'+

							    '<li><a href="'+edit_url+'" class="btn-edit"><i class="fa fa-edit" ></i> Edit</a></li></ul></div>';

							                	return (id==max_id?action_content:'-');

							                	}

							               

							            }

								             ]

					            });

					                

					    $("div.overlay").css("display", "none");           

				  },

			  	  error:function(error)  

				  {
					  
					$("div.overlay").css("display", "none"); 

				  }	 

	        });	

}



function set_charges_table()

{

	my_Date = new Date();

	var oTable = $('#charges_list').dataTable({

            	"aProcessing": true,

 				"aServerSide": true,

				"ajax": base_url+"/index.php/settings/payment_charges/ajax_list?nocache=" + my_Date.getUTCSeconds(),

				"columns": [

                { "data": "id_charges" },

                { "data": "payment_mode" },   

                { "data": "code" },   

                { "data": "service_tax" },

                { "data": function(row,type,val,metal){

								status = row.active;

								return (status == 1 ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">Disabled</span>');

							} 

				

				},

                { "data": function ( row, type, val, meta ) {

                	

                	 id= row.id_charges;

                	 action_content='<a class="btn btn-primary" role="button" href="'+base_url+'index.php/settings/payment_charges/edit/'+id+'"><i class="fa fa-edit" ></i> Edit</a> <a href="#" class="btn btn-danger btn-del" data-href='+base_url+"index.php/settings/payment_charges/delete/"+id+' data-toggle="modal" data-target="#confirm-delete" ><i class="fa fa-trash"></i> Delete</a>'

                	return action_content;

                	}

               

            }

              ]

        });

}



function add_charges_row(obj,e)

{

	e.preventDefault();

	var flag = validate_row(obj);

	if(flag == true)

	{

		$("#charges_range tbody tr").eq($(obj).parent().parent().index()).find('td').eq(4).html("");



		var row = $('<tr><td><input type="number"  name="charges[range][lower_limit][]" step="any" value=""/></td><td><input name="charges[range][upper_limit][]" type="number" step="any" value="" /></td><td><select name="charges[range][charge_type][]" id="charge_type"><option value="0"> % </option><option value="1">Value</option></select></td><td><input type="number" step="any" name="charges[range][charges_value][]" id="charges_value" value=""/></td><td><button type="submit" class="btn btn-success btn-sm" onclick="add_charges_row(this,event)">Add</button> <button type="submit" class="btn btn-danger  btn-sm" onclick="del_charges_row(this,event)">Delete</button></td></tr>');

		

		$("#charges_range tbody").append(row);

	}

}



function del_charges_row(obj,e)

{

	e.preventDefault();		 	

	var answer = confirm("Are you sure want to remove this row?")

	if (answer){

		$(obj).parent().parent().remove();

		var trIndex = parseFloat($("#charges_range tbody tr").length)-1;

		console.log($("#charges_range tbody tr").length);

		if($("#charges_range tbody tr").length == 1)

		{

			$("#charges_range tbody tr").eq(trIndex).find('td').eq(4).html('<button type="submit" class="btn btn-success btn-sm" onclick="add_charges_row(this,event)">Add</button>')

		}

		else

		{

			$("#charges_range tbody tr").eq(trIndex).find('td').eq(4).html('<button type="submit" class="btn btn-success btn-sm" onclick="add_charges_row(this,event)">Add</button> <button type="submit" class="btn btn-danger  btn-sm" onclick="del_charges_row(this,event)">Delete</button>')	

		}

	}

}



function validate_row(x)

{

	return true;	

}



$('#btn-backup').click(function(){

	 $("div.overlay").css("display", "block"); 

	 document.location =base_url+'index.php/settings/backup/database';

     load_db_list()

});


$('#btn-walletbackup').click(function(){

	 $("div.overlay").css("display", "block"); 

	 document.location =base_url+'index.php/admin_settings/interWalletAcc_backup';

     load_db_list()

});


function load_db_list()

{

	$("div.overlay").css("display", "block"); 

		var oTable = $('#db_list').DataTable();

		$.ajax({

				  type: 'GET',

				  url:  base_url+'index.php/settings/backup/database/list',

				  dataType: 'json',

				  success: function(data) {

					

				       oTable = $('#db_list').dataTable({

				                "bDestroy": true,

				                "bInfo": true,

				                "bFilter": true,

				                "bSort": true,

				                "aaSorting": [[ 0, "desc" ]], 

				                "aaData": data,

				                "aoColumns": [

							                    { "mDataProp": "id_dbbackup" },

								                { "mDataProp": "backup_date" },

								                { "mDataProp": "employee" },

								                { "mDataProp": "filename" }

								             ]

					            });

					                

					    $("div.overlay").css("display", "none");           

				  },

			  	  error:function(error)  

				  {

					 $("div.overlay").css("display", "none"); 

				  }	 

	        });	

}





//To load imported records list 

function get_import_list()

{

	my_Date = new Date();

	var lower = $('#lower').val();

	var upper = $('#upper').val();

	console.log(lower);

	console.log(upper);

	$.ajax({

			  url:base_url+ "index.php/settings/import/ajax_list/"+lower+"/"+upper+"?nocache=" + my_Date.getUTCSeconds(),

			 data:{'lower':lower,'upper':upper},

			 dataType:"JSON",

			 type:"POST",

			 success:function(data){

			 	

			   			set_import_list(data);

					  },

					  error:function(error)  

					  {

						 $("div.overlay").css("display", "none"); 

					  }	 

		  });

}







function set_import_list(data)

{

	

	 var imported = data.imported;

	 var oTable = $('#imported_list').DataTable();

	     oTable.clear().draw();

			  	 if (imported!= null && imported.length > 0)

			  	  {  	

					  	oTable = $('#imported_list').dataTable({

				                "bDestroy": true,

				                "bInfo": true,

				                "bFilter": true,

				                "bSort": true,

				                "aaData": imported,

				                "aoColumns": [{ "mDataProp": function(row,type,val,meta){ 

				                     return "<label class='checkbox-inline'><input type='checkbox' name='account_id[]' value='"+row.id_customer+"'/> " + row.id_customer + "</label>"

				                   }				                    

				                },

					                { "mDataProp": "name" },

					                { "mDataProp": "mobile" },

					                { "mDataProp": "email" }]

				            });			  	 	

					  	 }	

}

function get_cardbrand(id)
		{
			   my_Date = new Date();
				$.ajax({
						type:"GET",
						url: base_url+"index.php/settings/cardbrand/edit/"+id+"?nocache=" + my_Date.getUTCSeconds(),
						cache:false,		
						dataType:"JSON",
						success:function(data){
							wt=data.card_brand;
							
							sc=data.short_code;
							ct=data.card_type;
							
							$('#edcard_brand').val(wt);
							$('#edshort_code').val(sc);
							$('#edcardtype').val(ct);
							
							
						if(ct == 1)
						{
							
							$("#ed_cc").prop("checked", true);
							$("#ed_dc").prop("checked", false);
						}
						else
						{
							
							$("#ed_cc").prop("checked", false);
							$("#ed_dc").prop("checked", true);
						}
					}
				});
		}



function update_cardbrand(card_type,cardbrand,short_code,id)
{
	my_Date = new Date();
    
	$.ajax({
		data:{"card_type":card_type,"card_brand":cardbrand,"short_code":short_code},
		url: base_url+"index.php/settings/cardbrand/update/"+id+"?nocache=" + my_Date.getUTCSeconds(),
		type:"POST",
		
		cache: false,
		success:function(){
			window.location.reload(true);
			//set_table();
		}
	});
}


function set_cardbrand_table()
{
	my_Date = new Date();
	var oTable = $('#cardbrand_list').dataTable({
            	"aProcessing": true,
 				"aServerSide": true,
				"ajax": base_url+"index.php/settings/cardbrand_list?nocache=" + my_Date.getUTCSeconds(),
				"columns": [
                { "data": "id_card_brand" },
                { "data": "card_type" },
                { "data": "card_brand" },
                { "data": "short_code" },
                { "data": function ( row, type, val, meta ) {
                	 id= row.id_card_brand;
                	 action_content='<a href="#" class="btn btn-primary btn-edit" role="button" data-toggle="modal" data-id='+id+'  data-target="#confirm-edit"><i class="fa fa-edit" ></i> Edit</a> <a href="#" class="btn btn-danger btn-del" data-href='+base_url+"index.php/settings/cardbrand/delete/"+id+' data-toggle="modal" data-target="#confirm-delete" ><i class="fa fa-trash"></i> Delete</a>'
                	return action_content;
                	}
               
            }
              ]
        });
}



function add_card_brand(card_type,brand,code)
{
	my_Date = new Date();
	$.ajax({
		data:{"card_type":card_type,"card_brand":brand,"short_code":code},
		url: base_url+"index.php/settings/cardbrand/add?nocache=" + my_Date.getUTCSeconds(),
		type:"POST",
		cache:false,
		success:function(data){
			$('#weight').val('');
			location.reload(true);
		}
	});
}

//branch settings
	
	 $(document).on("click", ".branch_setting", function(event){
	
     	$('#branch_save').hide();
		
	   });
	
$('#branch_enable').click(function(){			
		
	  	var branch_change = $("#tab_branch input[type='radio'][name='general[branch_settings]']:checked").val();
		if(branch_change!='')
		{
			  $('#confirm-changebranch').modal('show');
			  
			 
			  
			
		}
		else
		{ 
				$('#clr_alert').addClass('alert-danger');
				$('#clr_alert span').html('Select atleast one option.');
				$('#clr_alert').css('display','block');
			
		}
	  	
});
		
	
  $(document).on("click", "#confirm_change", function(event){
	  	 
		 $('.modal.in').modal().hide(); 
	  	 $('.modal-backdrop').remove();
	  	 $('#branch_save').show();	 
		//alert('Click Save Option Change Your Branch Settings');
  });
	
	
//branch settings	

 
// branch add



$('#ed_countrys').on('change', function() {

 	    get_states(this.value);
		$('#ed_states').clear();
		 
			
	});
	
	$('#ed_states').on('change', function() {
 	    get_citys(this.value);
		
	
	});
	
	
	


//branch work
//add & upd branch with image//HH

$(document).on('click', "#branch_list  a.btn-edit", function(e) {
	
			$("#ed_branch").val('');
			
			e.preventDefault();
			id=$(this).data('id');
		    get_branch(id);
			
			get_country();
		    $("#edit-id").val(id); 
			
		});


		$("#add_branch").on('click',function(){
			
			if($('#branch').val())
			{	
                var metal_type = $("input[name='metal_type']:checked").val();
        		var show_to_all = $("input[name='show_to_all']:checked").val();
        		var is_ho=$("input[name='is_ho']:checked").val();
        		var file=$("#branch_img")[0].files[0];
        		var metal_type = $("input[name='metal_type']:checked").val();
        		var show_to_all = $("input[name='show_to_all']:checked").val();
        		var file=$("#branch_img")[0].files[0];
        		add_branch($('#branch').val(),is_ho,$('#map_url').val(),$('#short_name').val(),$('#address1').val(),$('#address2').val(),$('#country').val(),$('#state').val(),$('#city').val(),$('#pincode').val(),$('#phone').val(),$('#mobile').val(),$('#cusromercare').val(),metal_type,$('#day_close').val(),show_to_all,$('#partial_goldrate').val(),$('#partial_silverate').val(),file);
			    $('#branch').val('');
				$('#branch_img').val('');
				$('#is_ho').val('');
				$('#map_url').val('');
				$('#short_name').val('');
				$('#show_to_all').val('');
				$('#metal_type').val('');
				$('#day_close').val('');
				$('#partial_goldrate').val('');
				$('#partial_silverate').val('');
				
			}
		});
		
		
		$("#update_branch").on('click',function(){
		    	
		   	var file=$("#edit_sch_branch_img")[0].files[0];
			var branch=$("#ed_branch").val();		
		
			var map_url=$("#ed_map_url").val();	
			//var short_name=$("#ed_short_name").val()	
			var short_name=$("#ed_short_name").val();			  
			var address1=$("#ed_address1").val();		  
			var address2=$("#ed_address2").val();			  
			var country=$("#ed_countrys").val()	;	  
			var state=$("#ed_states").val()	;		  
			var city=$("#ed_citys").val();			  
			var pincode=$("#ed_pincode").val();		  
			var phone=$("#ed_phone").val();				
			var mobile=$("#ed_mobile").val();
			var ed_day_close=$("#ed_day_close").val();
			var is_ho=$("input[name='ed_is_ho']:checked").val();
			var metal_rate_type = $("input[name='ed_metal_type']:checked").val();
			var show_to_all = $("input[name='ed_show_to_all']:checked").val();
			var ed_enable_gift_voucher = $("input[name='ed_enable_gift_voucher']:checked").val();	
			var cusromercare=$("#ed_customer_care").val()	;			
			var partial_goldrate_diff=$("#ed_partial_goldrate").val()	;			
			var partial_silverrate_diff=$("#ed_partial_silverate").val();
			var id=$("#edit-id").val()	;
			//console.log(metal_rate_type);
	       		if(branch)
			       {
					  update_branch(branch,map_url,is_ho,ed_day_close,short_name,address1,address2,country,state,city,pincode,phone,mobile,cusromercare,metal_rate_type,show_to_all,partial_goldrate_diff,partial_silverrate_diff,ed_enable_gift_voucher,file);
				   }	   		
		}); 





function set_branch_table()
{
	my_Date = new Date();
	var oTable = $('#branch_list').dataTable({
            	"aProcessing": true,
 				"aServerSide": true,
				"ajax": base_url+"index.php/branch/branch_list?nocache=" + my_Date.getUTCSeconds(),
				"columns": [
                { "data": "id_branch" },
                { "data": "name" },
                { "data": "short_name" },
                { "mDataProp": function ( row, type, val, meta ){
					                	    active_url =base_url+"index.php/branch/branch_stat/"+row.id_branch+"/"+(row.active==1?0:1); 
					                		return "<a href='"+active_url+"'><i class='fa "+(row.active==1?'fa-check':'fa-remove')+"' style='color:"+(row.active==1?'green':'red')+"'></i></a>"
					                	}
					                },
                { "data": function ( row, type, val, meta ) {
                	 id= row.id_branch;
                	 action_content='<a href="#" class="btn btn-primary btn-edit" role="button" data-toggle="modal" data-id='+id+'  data-target="#confirm-edit"><i class="fa fa-edit" ></i> Edit</a> ';
                	return action_content;
                	}
               
            }
              ]
        });
}

function add_branch(branch,is_ho,map_url,short_name,address1,address2,country,state,city,pincode,phone,mobile,cusromercare,metal_type,day_close,show_to_all,partial_goldrate_diff,partial_silverrate_diff,file)
{
  
	my_Date = new Date();
    	var file=file;
    var data = new FormData();  
	var form_data = new FormData();  
    form_data.append('file', file);
    form_data.append('branch', branch);
	form_data.append('is_ho', is_ho);
    form_data.append('map_url', map_url);
    form_data.append('short_name', short_name);
    form_data.append('address1', address1);
    form_data.append('address2', address2);
    form_data.append('country', country);
    form_data.append('state', state);
    form_data.append('city', city);
    form_data.append('pincode', pincode);
    form_data.append('phone', phone);
    form_data.append('mobile', mobile);
    form_data.append('cusromercare', cusromercare);
    form_data.append('metal_type', metal_type);
	form_data.append('day_close',day_close);
    form_data.append('show_to_all',show_to_all);
    form_data.append('partial_goldrate_diff', partial_goldrate_diff);
    form_data.append('partial_silverrate_diff', partial_silverrate_diff);
    form_data.append('partial_silverrate_diff', partial_silverrate_diff);
    form_data.append('gst_number',$('#gst_number').val());
    
	$.ajax({
	    data:form_data,
		url: base_url+"index.php/branch/branch_name/add?nocache=" + my_Date.getUTCSeconds(),
		type:"POST",
		dataType:'json',
        cache : false,
		enctype: 'multipart/form-data',
		contentType : false,
		processData : false,
		cache:false,
		success:function(data){
			location.reload(true);
		}
	});
}




function get_branch(id)
{
	
   my_Date = new Date();
	$.ajax({
		type:"GET",
		url: base_url+"index.php/branch/branch_name/edit/"+id+"?nocache=" + my_Date.getUTCSeconds(),
		cache:false,		
		dataType:"JSON",
		success:function(data){
			
			branch=data.name;
	        var x = data.is_ho;
			if(x == 1)
			{
				$('input:radio[name="ed_is_ho"][value="1"]').attr('checked',true);
			}else if(x == 0)
			{
				$('input:radio[name="ed_is_ho"][value="0"]').attr('checked',true);
			}
			
			if(data.enable_gift_voucher == 1)
			{
				$('#ed_enable_gift_voucher_yes').attr('checked',true);
			}else
			{
				$('#ed_enable_gift_voucher_no').attr('checked',true);
			}
			
			 $('#ed_day_close').val(data.day_close);
		   	 $('#edit_sch_branch_img').val(data.edit_sch_branch_img);
		   	 $('#ed_branch').val(branch);
		   	 $('#ed_short_name').val(data.short_name);
		     $('#ed_map_url').val(data.map_url);
		     $('#ed_address1').val(data.address1);
			 $('#ed_address2').val(data.address2);
			 $('#ed_pincode').val(data.pincode);
			 $('#ed_phone').val(data.phone);
			 $('#ed_mobile').val(data.mobile);
			 $('#ed_customer_care').val(data.cusromercare);
			 $('#ed_stae').val(data.id_state); 
			 $('#ed_country').val(data.id_country);
			 $('#ed_city').val(data.id_city);
			 $('#ed_metal_type').val(data.metal_rate_type);
			 $('#ed_show_to_all').val(data.show_to_all);
			 $('#ed_partial_goldrate').val(data.partial_goldrate_diff);
			 $('#ed_partial_silverate').val(data.partial_silverrate_diff);
			 $('#ed_gst_number').val(data.gst_number);
			 
			  if(data.metal_rate_type != 2){
			     $("#ed_partial_silverate").attr("disabled", "disabled");
                $("#ed_partial_goldrate").attr("disabled", "disabled");
			 }
			
			$("input[name=ed_show_to_all][value=" + data.show_to_all + "]").attr('checked', true);
				logo = data.logo;
		   // $('#ed_clsfy').val(classification_name);
		    if(logo!="" && logo!=null)
		    {
		    	 var img=base_url+"assets/img/branch/"+logo;
		    	 $("#edit_sch_branch_img_preview").attr('src',img);
		    }
		    else
		    {
		    	var img=base_url+"assets/img/no_image.png";
		    	$("#edit_sch_branch_img_preview").attr('src',img);
		    }
			
			
			if(data.metal_rate_type==0)
			{
				$("#rate_manual").prop('checked',true);				
			}
			else if(data.metal_rate_type==1){
				$("#rate_auto").prop('checked',true);				
			} 
			else{
				$("#rate_partial").prop('checked',true);
			}  
				edit_country();			 
		}
			
		
		
	});
}

//add & upd branch with image//HH

function edit_country()
{	
    my_Date = new Date();
	$.ajax({
	  type: 'GET',
	  url:  base_url+'index.php/settings/company/getcountry?nocache=' + my_Date.getUTCSeconds(),
	  dataType: 'json',
	   cache:false,
	  success: function(country) {
	  //	console.log(country);
		$.each(country, function (key, country) {
				  	
		  
			$('#ed_countrys').append(
				$("<option></option>")
				  .attr("value", country.id)
				  .text(country.name)
				  
			);
			
		});
		
			var selectid=$('#ed_country').val();
			
			if(selectid!=null)
			{
				$('#ed_countrys').val(selectid);
				
				get_states(selectid);
			}
			
		}
	});
}



function get_states(id)
{

	my_Date = new Date();
	$('#ed_states option').remove();
	$.ajax({
	  type: 'POST',
	   data:{'id_country':id },
	  url:  base_url+'index.php/settings/company/getstate/?nocache=' + my_Date.getUTCSeconds(),
	  cache:false,
	  dataType: 'json',
	  success: function(state) {
	  	
		$.each(state, function (key, state) {			  	
		  
			$('#ed_states').append(
				$("<option></option>")
				  .attr("value", state.id)
				  .text(state.name)
			);
		});
		
		var selectid=$('#ed_stae').val();
		if(selectid!=null)
		{
			$('#ed_states').val(selectid);
			
		    get_citys(selectid);
	    }
		
		
	  }
	});
}


function get_citys(id)
{  	
	my_Date = new Date();
   $('#ed_citys option').remove();
	my_Date = new Date();
	$.ajax({
	  type: 'POST',
	  data:{'id_state':id },
	  url:  base_url+"index.php/settings/company/getcity?nocache=" + my_Date.getUTCSeconds(),
	  	cache:false,
	  dataType: 'json',
	  success: function(city) {
	  
		$.each(city, function (key, city) {
				  	
		  
			$('#ed_citys').append(
				$("<option></option>")
				  .attr("value", city.id)
				  .text(city.name)
			);
		});
	
		var selectid=$('#ed_city').val();
		if(selectid!=null)
		{
			$('#ed_citys').val(selectid);
			
		   
	    }
	  }
	});

}


function update_branch(branch,map_url,is_ho,ed_day_close,short_name,address1,address2,country,state,city,pincode,phone,mobile,cusromercare,metal_type,show_to_all,partial_goldrate_diff,partial_silverrate_diff,ed_enable_gift_voucher,file)
{
	
	my_Date = new Date();
    	var file=file;
    var data = new FormData();  
	var form_data = new FormData();  
    form_data.append('file', file);
    form_data.append('branch', branch);
	form_data.append('is_ho', is_ho);
    form_data.append('map_url', map_url);
    form_data.append('short_name', short_name);
    form_data.append('address1', address1);
    form_data.append('address2', address2);
    form_data.append('country', country);
    form_data.append('state', state);
    form_data.append('city', city);
    form_data.append('pincode', pincode);
    form_data.append('phone', phone);
    form_data.append('mobile', mobile);
    form_data.append('cusromercare', cusromercare);
	form_data.append('day_close',ed_day_close);
    form_data.append('metal_type', metal_type);
    form_data.append('show_to_all',show_to_all);
    form_data.append('partial_goldrate_diff', partial_goldrate_diff);
    form_data.append('partial_silverrate_diff', partial_silverrate_diff);
    form_data.append('partial_silverrate_diff', partial_silverrate_diff);
    form_data.append('ed_enable_gift_voucher',ed_enable_gift_voucher);
    form_data.append('gst_number',$("#ed_gst_number").val());
    
	$.ajax({
	    data:form_data,
		url: base_url+"index.php/branch/branch_name/update/"+id+"?nocache=" + my_Date.getUTCSeconds(),
			type:"POST",
        dataType:'json',
        cache : false,
		enctype: 'multipart/form-data',
		contentType : false,
		processData : false,
		success:function(data){ 
		location.reload(true);
			
		}
	});
}
// end branch add
//add & upd branch with image//HH



// metal_rate list branch 






$('#branch_select').select2().on("change", function(e) {

					if((ctrl_page[2]=='list' && this.value!=''))
					{	 
						var id_branch   = $(this).val();
						barnch_metalratelist(id_branch);
					}
					
					if(((ctrl_page[2]=='add' || ctrl_page[2]=='edit') && this.value!=''))  // based on the branch settings to showed Offer & New Arrivals page branch filter//HH
					{	 
						var id_branch   = $(this).val();						
						$('#id_branch').val(id_branch);
						
					}
						
								
			
		  
			});






function get_branchnames()
{ 
	$(".overlay").css('display','block');
	$.ajax({
		type: 'GET',
		url: base_url+'index.php/branch/branchname_list',
		dataType:'json',
		success:function(data){
	
		 var scheme_val =  $('#id_branch').val();
		 	console.log(scheme_val);
		   $.each(data.branch, function (key, item) {					  	
			  
			   		$('#branch_select').append(
						$("<option></option>")
						.attr("value", item.id_branch)						  
						  .text(item.name )
						  
					);			   				
				
			});
			
			$("#branch_select").select2({
			    placeholder: "Select branch name",
			    allowClear: true
		    });
				
		 $("#branch_select").select2("val",(scheme_val!='' && scheme_val>0?scheme_val:''));
			 $(".overlay").css("display", "none");	
		}
	});
}




  function barnch_metalratelist(id)

{

	my_Date = new Date();

	$("div.overlay").css("display", "block"); 

		var oTable = $('#metalrate_list').DataTable();

		$.ajax({

				 type: 'GET',

				 url:  base_url+'index.php/branch/metal_rate/ajax_list/'+id,
				 
				  dataType: 'json',

				  success: function(data) {
					  
					  var access=data.access;

						

						$('#total_metals').text(data.rates.length);

						 if(access.add == '0')

						 {

							$('#add_metal').attr('disabled','disabled');

						 }

			          var max_id = data.max_id;

				       oTable = $('#metalrate_list').dataTable({

				                "bDestroy": true,

				                "bInfo": true,

				                "bFilter": true,

				                "bSort": true,

				                "aaSorting": [[ 0, "desc" ]], 

				                "aaData": data.rates,

				                "aoColumns": [

							                    { "mDataProp": "id_metalrates" },

							                    { "mDataProp": "updatetime" },

								                { "mDataProp": "goldrate_22ct" },

								                { "mDataProp": "goldrate_24ct" },

								                { "mDataProp": "silverrate_1gm" },

								                { "mDataProp": "silverrate_1kg" },		

								                { "mDataProp": "employee" },		

								                { "mDataProp": function ( row, type, val, meta ) {

							                	 id         = row.id_metalrates;

												  edit_url   = (access.edit=='1'?base_url+'index.php/settings/rate/edit/'+id:"#");

							                	 

							                	 action_content='<div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button><ul class="dropdown-menu">'+

							    '<li><a href="'+edit_url+'" class="btn-edit"><i class="fa fa-edit" ></i> Edit</a></li></ul></div>';

							                	return (id==max_id?action_content:'-');

							                	}

							               

							            }

								             ]

					            });

					                

					    $("div.overlay").css("display", "none");           

				  },

			  	  error:function(error)  

				  {

					 $("div.overlay").css("display", "none"); 

				  }	 

	        });	

}


// metal_rate list

    function validate_Image()
   	{

		  var height=($(this).height());
      	  var width=($(this).width());
       	 if(arguments[0].id == 'edit_sch_clsfy_img')
          {
    		 var preview = $('#edit_sch_clsfy_img_preview');
    	  }
    	  else if(arguments[0].id == 'sch_clsfy_img')
    	  {
    	  	 var preview = $('#sch_clsfy_img_preview');
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
			if(ext != "jpg" && ext != "png" && ext != "jpeg" && ext != "svg")
			{
				alert("Upload JPG or PNG Images only");
				arguments[0].value = "";
				preview.css('display','none');
			}
			/*if(width>960 && height>525)
			{
				alert("Width and height should be less than  960 * 525 ");
				arguments[0].value = "";
				preview.css('display','none');
			}*/
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
  
  //payment gateway//

$("input[name='gateway_active']:radio").on('change',function(){
		   if($(this).val()==1)
		   {
			   $('#gateway_active').val(1);
		   }
		   else{
			   $('#gateway_active').val(0);
		   }
		   
	});

	$("input[name='save_card']:radio").on('change',function(){
		   if($(this).val()==1)
		   {
			   $('#save_card').val(1);
		   }
		   else{
			   $('#save_card').val(0);
		   }
		   
	});
	$("input[name='debit_card']:radio").on('change',function(){
		   if($(this).val()==1)
		   {
			   $('#debit_card').val(1);
		   }
		   else{
			   $('#debit_card').val(0);
		   }
		   
	});
	$("input[name='credit_card']:radio").on('change',function(){
		   if($(this).val()==1)
		   {
			   $('#credit_card').val(1);
		   }
		   else{
			   $('#credit_card').val(0);
		   }
		   
	});
	$("input[name='netbanking']:radio").on('change',function(){
		   if($(this).val()==1)
		   {
			   $('#netbanking').val(1);
		   }
		   else{
			   $('#netbanking').val(0);
		   }
		   
	});
	$("input[name='ed_gateway']:radio").on('change',function(){
		   if($(this).val()==1)
		   {
			   $('#ed_gateway').val(1);
		   }
		   else{
			   $('#ed_gateway').val(0);
		   }
		   
	});
	$("input[name='ed_save_card']:radio").on('change',function(){
		   if($(this).val()==1)
		   {
			   $('#ed_save_card').val(1);
		   }
		   else{
			   $('#ed_save_card').val(0);
		   }
		   
	});
	$("input[name='ed_debit_card']:radio").on('change',function(){
		   if($(this).val()==1)
		   {
			   $('#ed_debit_card').val(1);
		   }
		   else{
			   $('#ed_debit_card').val(0);
		   }
		   
	});
	$("input[name='ed_credit_card']:radio").on('change',function(){
		   if($(this).val()==1)
		   {
			   $('#ed_credit_card').val(1);
		   }
		   else{
			   $('#ed_credit_card').val(0);
		   }
		   
	});
	$("input[name='ed_netbanking']:radio").on('change',function(){
		   if($(this).val()==1)
		   {
			   $('#ed_netbanking').val(1);
		   }
		   else{
			   $('#ed_netbanking').val(0);
		   }
		   
	});

	$("input[name='cardtype']:radio").on('change',function(){
		   if($(this).val()==1)
		   {
			   $('#card_type').val(1);
		   }
		   else{
			   $('#card_type').val(2);
		   }
		   
	});
	
	$("input[name='edcardtype']:radio").on('change',function(){
		   if($(this).val()==1)
		   {
			   $('#edcard_type').val(1);
			   
		   }
		   else{
			   $('#edcard_type').val(2);
		   }
		   
	});

	$("#add_paymentgateway").on('click',function(){
		
			var file = $("#pay_gateway_img")[0].files[0];
			add_payment_gateway($('#gateway_name').val(),$('#gateway_code').val(),$('#gateway_active').val(),$('#save_card').val(),$('#debit_card').val(),$('#credit_card').val(),$('#netbanking').val(),file,$("#gateway_description").val()); 
	});


	$("#update_payment_gateway").on('click',function(){
  	
  		// var file = $("#edit_pay_gateway_img")[0].files[0];
  		var file = $("#edit_pay_gateway_img")[0].files[0];
  		
		var id=$("#edit-id").val();			  
		update_payment_gateway($("#ed_pg_name").val(),$("#ed_code").val(),$("#ed_gateway").val(),$("#ed_save_card").val(),$("#ed_credit_card").val(),$("#ed_debit_card").val(),$("#ed_netbanking").val(),$("#edit-id").val(),file,$("#ed_gateway_description").val());
	});

	$(document).on('click', "#paymentgateway_list a.btn-edit", function(e) {
		$("#ed_pg_name").val('');
		$("#ed_code").val('');
		e.preventDefault();
		id=$(this).data('id');
		
	    get_paymentgateway(id);
	    $("#edit-id").val(id);  
	});	

	$("#pay_gateway_img").change( function(){

		event.preventDefault();

		console.log(this);

		validate_cls_Image(this);

		});
	
		$("#edit_pay_gateway_img").change( function(){

		event.preventDefault();

		console.log(this);

		validate_cls_Image(this);

		});

  	function validate_cls_Image()
   	
   	{

  
   	 if(arguments[0].id == 'edit_pay_gateway_img')

      {
      		
		 var preview = $('#edit_paymentgateway_img_preview');

	  }
	  else if(arguments[0].id == 'pay_gateway_img')
	  {
	  	 var preview = $('#pay_gateway_img_preview');
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
	
function add_payment_gateway(gateway_name,code,gateway_active,savecard,debit_card,credit_card,netbanking,file,description)
{
	
	my_Date = new Date();
	console.log(file);
	 var form_data = new FormData();  
   		
    form_data.append('gateway_name', gateway_name);
    form_data.append('code', code);
    form_data.append('gateway_active', gateway_active);
    form_data.append('savecard', savecard);
    form_data.append('debit_card', debit_card);
    form_data.append('creditcard', credit_card);
    form_data.append('netbanking', netbanking);
    form_data.append('file', file);
    form_data.append('description', description);
	console.log(form_data);
	$.ajax({
		data:form_data,
		url: base_url+"index.php/settings/payment_gateway/add?nocache=" + my_Date.getUTCSeconds(),
		type:"POST",
        dataType:'json',
        cache : false,
		enctype: 'multipart/form-data',
		contentType : false,
		processData : false,
		success:function(data){
			
			  location.reload(true);
		}
	});
}
/*function set_paymentgateway_list()
{
	my_Date = new Date();
	var oTable = $('#paymentgateway_list').dataTable({
            	"aProcessing": true,
 				
				"ajax": base_url+"index.php/settings/payment_gateway_list?nocache=" + my_Date.getUTCSeconds(),
				"columns": [
                { "data": "id_pg_settings" },
                { "data": "pg_name" },
                { "mDataProp": function ( row, type, val, meta ) {
							           id   = row.pg_icon;
							           if(row.pg_icon!=""&& row.pg_icon!=null)
							           {
							           	img=base_url+"assets/img/gateway/"+row.pg_icon;
							           }
							           else
							           {
							           	 img=base_url+"assets/img/no_image.png";
							           }
							          
							           action_content='<a href="#"><img src= '+img+' width=50px;" height="50px;"></a>';
							               return action_content;
				  }}, 
                { "data": "active" },
                
                { "data": function ( row, type, val, meta ) {
                	 id= row.id_pg_settings;
                	 action_content='<a href="#" class="btn btn-primary btn-edit" role="button" data-toggle="modal" data-id='+id+'  data-target="#confirm-edit"><i class="fa fa-edit" ></i> Edit</a> <a href="#" class="btn btn-danger btn-del" data-href='+base_url+"index.php/settings/paymentgateway/delete/"+id+' data-toggle="modal" data-target="#confirm-delete" ><i class="fa fa-trash"></i> Delete</a>'
                	return action_content;
                	}
               
            }
              ]
        });
}*/

function set_paymentgateway_list(id_branch='')
{
	
	
	my_Date = new Date();	
		$.ajax({
		data:{'id_branch':id_branch},
		url: base_url+"index.php/settings/payment_gateway_list?nocache=" + my_Date.getUTCSeconds(),
		type:"POST",
        dataType:'json',
        cache : false,
		success:function(data){
		
			set_gateway_list(data);
		}
	});
}

function set_gateway_list(data)	
{
   var customer = data;
   var oTable = $('#paymentgateway_list').DataTable();
	 oTable.clear().draw();
	 	oTable = $('#paymentgateway_list').dataTable({
				                "bDestroy": true,
				                "bSort": true,				          
				                "dom": 'T<"clear">lfrtip',
				               "tableTools": { "aButtons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } }] },
				                "aaData": data,
				               "aoColumns": [
					                { "mDataProp": "id_pg" },
					                { "mDataProp": "pg_name" },
					                { "mDataProp": "type" },
					                   { "mDataProp": function ( row, type, val, meta ) {
							           id   = row.pg_icon;
							           if(row.pg_icon!=""&& row.pg_icon!=null)
							           {
							           	img=base_url+"assets/img/gateway/"+row.pg_icon;
							           }
							           else
							           {
							           	 img=base_url+"assets/img/no_image.png";
							           }
							          
							           action_content='<a href="#"><img src= '+img+' width=50px;" height="50px;"></a>';
							               return action_content;
				  					}}, 
					                { "mDataProp": "active" },
						   			{ "data": function ( row, type, val, meta ) {
						                	 id= row.id_pg;
						                	 action_content='<a href="#" class="btn btn-primary btn-edit" role="button" data-toggle="modal" data-id='+id+'  data-target="#confirm-edit"><i class="fa fa-edit" ></i> Edit</a> <a href="#" class="btn btn-danger btn-del" data-href='+base_url+"index.php/settings/paymentgateway/delete/"+id+' data-toggle="modal" data-target="#confirm-delete" ><i class="fa fa-trash"></i> Delete</a>'
						                	return action_content;
						                	}
						               
						            },					              
					            ] 
				            });	

}

function update_payment_gateway(pg_name,code,ed_gateway,savecard,creditcard,debitcard,netbanking,id,file,description)
{
	
	
	my_Date = new Date();	
	var form_data=new FormData();
	var id_branch=$("#id_branch").val();    

	form_data.append('gateway_name', pg_name);
    form_data.append('code', code);
    form_data.append('active', ed_gateway);
    form_data.append('savecard', savecard);
    form_data.append('debit_card', debitcard);
    form_data.append('creditcard', creditcard);
    form_data.append('netbanking', netbanking);
    form_data.append('file', file);
    form_data.append('id', id);
    form_data.append('description', description);
	form_data.append('id_branch', id_branch);

    console.log(form_data);
	$.ajax({
		data:form_data,
		url: base_url+"index.php/settings/paymentgateway/update/"+id+"?nocache=" + my_Date.getUTCSeconds(),
		type:"POST",
        dataType:'json',
        cache : false,
		enctype: 'multipart/form-data',
		contentType : false,
		processData : false,
		
		cache: false,
		success:function(){
			window.location.reload(true);
			//set_table();
		}
	});
}

function get_paymentgateway(id)
{
   my_Date = new Date();
      	var id_branch=$("#id_branch").val();    

	$.ajax({
		type:"POST",
		data:{'id_branch':id_branch,'id_pg':id},
		url: base_url+"index.php/settings/payment_gateway/edit?nocache=" + my_Date.getUTCSeconds(),
		cache:false,		
		dataType:"JSON",
		success:function(data)
		{
			console.log(data);
			pg_name=data.pg_name;
			pg_code=data.pg_code;
			active=data.active;
			savecard=data.savecard;
			debitcard=data.debitcard;
			creditcard=data.creditcard;
			netbanking=data.netbanking;
			pg_icon=data.pg_icon;
			description=data.description;
			
		

            $('#ed_gateway_description').val(description);
		    $('#ed_pg_name').val(pg_name);
		    $('#ed_code').val(pg_code);
		    if(active == 1){
				$('#ed_gateway_active').prop('checked', true);
				$('#ed_gateway_inactive').prop('checked', false);
				$('#ed_gateway').val(1);
			}
			else{
				$('#ed_gateway_active').prop('checked', false);
				$('#ed_gateway_inactive').prop('checked', true);
				$('#ed_gateway').val(0);
			}
		    if(savecard == 1){
				$('#ed_save_card_active').prop('checked', true);
				$('#ed_save_card_inactive').prop('checked', false);
				$('#ed_save_card').val(1);
			}
			else{
				$('#ed_save_card_active').prop('checked', false);
				$('#ed_save_card_inactive').prop('checked', true);
				$('#ed_save_card').val(0);
			}
			if(debitcard == 1){
				$('#ed_debit_card_active').prop('checked', true);
				$('#ed_debit_card_inactive').prop('checked', false);
				$('#ed_debit_card').val(1);
			}
			else{
				$('#ed_debit_card_active').prop('checked', false);
				$('#ed_debit_card_inactive').prop('checked', true);
				$('#ed_debit_card').val(0);
			}
			if(creditcard == 1){
				$('#ed_credit_card_active').prop('checked', true);
				$('#ed_credit_card_inactive').prop('checked', false);
				$('#ed_credit_card').val(1);
			}
			else{
				$('#ed_credit_card_active').prop('checked', false);
				$('#ed_credit_card_inactive').prop('checked', true);
				$('#ed_credit_card').val(0);
			}

			if(netbanking == 1){
				$('#ed_netbanking_active').prop('checked', true);
				$('#ed_netbanking_inactive').prop('checked', false);
				$('#ed_netbanking').val(1);
			}
			else{
				$('#ed_netbanking_active').prop('checked', false);
				$('#ed_netbanking_inactive').prop('checked', true);
				$('#ed_netbanking').val(0);
			}
			if(pg_icon!="" && pg_icon!=null)
		    {
		    	var img=base_url+"assets/img/gateway/"+data.pg_icon;
		    	 $("#edit_paymentgateway_img_preview").attr('src',img);
		    }
		     else
		    {
		    	var no_img=base_url+"assets/img/no_image.png";
		    
		    	$("#edit_paymentgateway_img_preview").attr('src',no_img);
		    }
		    
		}
	});
}

//payment gateway//


$("input[name='general[enableSilver_rateDisc]']:checkbox").on("change",function(){
		
		if($("input[name='general[enableSilver_rateDisc]']:checked").is(":checked")){
		
		$("#silverDiscAmt").prop("disabled",false);
		
		}else{
				$("#silverDiscAmt").prop("disabled",true);		
			}
	});

	if($("input[name='general[enableSilver_rateDisc]']:checked").is(":checked")){
		
		$("#silverDiscAmt").prop("disabled",false);
		
		}else{
				$("#silverDiscAmt").prop("disabled",true);		
			}



     $('#branch_select').select2().on("change", function(e) {       
         
           switch(ctrl_page[1])

			{

				case 'payment':
				
					
					if(this.value!='')
					{  
						
					
						var id_branch=$(this).val();
						$("#id_branch").val(this.value);    
	
						set_paymentgateway_list(id_branch);
					}
					 break;
					case 'general':
				
					
					if(this.value!='')
					{  
						
						$("#id_branch").val(this.value);    
						var id_branch=$("#id_branch").val(); 
						var pg_code=$("#pg_code").val();
						var type=$("#type").val();
							if(pg_code!='')
							{
						
							 get_gateway(type,pg_code,id_branch) ;			
							}
						
					}
					 break;
				
			}
		
		  
   });
   
   	$("input[name='demo[is_default]']:checkbox").on("change",function(){
		
		if($("input[name='demo[is_default]']:checked").is(":checked"))
		{
		
		$('#is_default').val(1);
		}
		else
		{
				$('#is_default').val(0)
		}
	});
	
function get_gateway(type,pg_code,id_branch) 
{
 my_Date = new Date();	
$.ajax({
		data:{'type':type,'pg_code':pg_code,'id_branch':id_branch},
	   url:  base_url+'index.php/admin_settings/ajax_paymentgateway?nocache=' + my_Date.getUTCSeconds(),
		type:"POST",
        dataType:'json',
		cache: false,
		success:function(data){
		
		 console.log(data.param_1);
		 $('#param_1').val(data.param_1);
		 $('#param_2').val(data.param_2);
		 $('#param_3').val(data.param_3);
		 $('#param_4').val(data.param_4);
		 $('#api_url').val(data.api_url);
		 $('#pg_name').val(data.pg_name);
		 $('#gateway_content').show();
		 $('#id_pg').val(data.id_pg);
		 if(data.is_default==1)
		 {
		 	$('#is_default').prop("checked",true);
		 }
		 $(".overlay").css('display','none');	
		}
	});
}

function update_gateway()
{
		
	var param_1=$('#param_1').val();
	var param_2=$('#param_2').val();
	var param_3=$('#param_3').val();
	var param_4=$('#param_4').val();
	var api_url=$('#api_url').val();
	var is_default=$('#is_default').val();
	var pg_code=$("#pg_code").val();
	var type=$("#type").val();
	var id_pg=$("#id_pg").val();
	var id_branch=$("#id_branch").val();



	my_Date = new Date();
	$.ajax({
	data:{'id_branch':id_branch,'param_1':param_1,'param_2':param_2,'param_3':param_3,'param_4':param_4,'api_url':api_url,'is_default':is_default,'id_pg':id_pg},
	   url:  base_url+'index.php/admin_settings/update_gateway?nocache=' + my_Date.getUTCSeconds(),
		type:"POST",
        dataType:'json',
		cache: false,
		success:function(){
			// window.location.reload();
		}
	});	

}


$("#gateway").on('click',"li a.gateway_list",function(event){
	event.preventDefault();
var pg_code=($(this).attr('value'));
$('#gateway_type').css({"display":"block"});

$("#pg_code").val(pg_code);
var type=$("#type").val();
var id_branch=$("#id_branch").val();

if($("#type").val()!='')
{
	 get_gateway(type,pg_code,id_branch) ;

}

});


$("#gateway_type").on('click',"li a.gateway_type",function(event){

var type=($(this).attr('value'));
$(".overlay").css('display','block');	
$('#type').val(type);
var pg_code=$("#pg_code").val();
var id_branch=$("#id_branch").val();
var type=$("#type").val();
 get_gateway(type,pg_code,id_branch) ;


});


$("#gateway_submit").on('submit',function(event){
			event.preventDefault();
			update_gateway();
});


function get_branchname(){	
     	$(".overlay").css('display','block');	
     	$.ajax({		
         	type: 'GET',		
         	url: base_url+'index.php/branch/branchname_list',		
         	dataType:'json',		
         	success:function(data){	
			$("#branch_select option").remove(); // New code 05-12-2022
			
         	            	 //	var id_branch =  $('#id_branch').val();		   
			  	        	 	$.each(data.branch, function (key, item) {					  				  			   		
            	 	$('#branch_select').append(						
            	 	$("<option></option>")						
            	 	.attr("value", item.id_branch)						  						  
            	 	.text(item.name )						  					
            	 	);			   											
             	});						
             	$("#branch_select").select2({			    
            	 	placeholder: "Select branch name",			    
            	 	allowClear: true		    
             	});	
				
				console.log(data);
		
			var ar = $('#sel_bran').data('sel_bran');
			console.log(ar);
			$('#branch_select').select2('val', ar);

			
             	/* $("#branch_select").select2("val",(id_branch!=''?id_branch:''));
             	 */$(".overlay").css("display", "none");			
         	}	
        }); 
    }
    
    	//catlog module//HH

function set_modules_list()
{
	my_Date = new Date();
	$("div.overlay").css('display','block');
	$.ajax({
			 url:base_url+ "index.php/settings/module/ajax?nocache=" + my_Date.getUTCSeconds(),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
				 var module 	= data.module;
				 var access		= data.access;	
				 //$('#total_module').text(.module.length);
				  if(access.add == '0')
				 { 
			 			
					$('#add_modules').attr('disabled','disabled');
				//	$('#add_modules').style.visibility = 'hidden';
					
				 }
				 
			 var oTable = $('#module_list').DataTable();
				 oTable.clear().draw();
				  if (module!= null && module.length > 0)
							{  
					  	
							oTable = $('#module_list').dataTable({
									"bDestroy": true,
									"bInfo": true,
									"bFilter": true,
									"bSort": true,
									"aaData": module,
									"aoColumns": [	{ "mDataProp": "id_module" },
													{ "mDataProp": "m_name" },
													 { "mDataProp": function ( row, type, val, meta ){
					                	    module_url =base_url+"index.php/admin_usersms/module_status/"+'m_app'+'/'+(row.m_app==1?0:1)+"/"+row.id_module; 
					                		return "<a href='"+module_url+"'><i class='fa "+(row.m_app==1?'fa-check':'fa-remove')+"' style='color:"+(row.m_app==1?'green':'red')+"'></i></a>"
					                	}
					                },
					                 { "mDataProp": function ( row, type, val, meta ){
					                	    module_url =base_url+"index.php/admin_usersms/module_status/"+'m_web'+'/'+(row.m_web==1?0:1)+"/"+row.id_module; 
					                		return "<a href='"+module_url+"'><i class='fa "+(row.m_web==1?'fa-check':'fa-remove')+"' style='color:"+(row.m_web==1?'green':'red')+"'></i></a>"
					                	}
					                },
					                    { "mDataProp": function ( row, type, val, meta ){
					                	    module_url =base_url+"index.php/admin_usersms/module_status/"+'m_active'+'/'+(row.m_active==1?0:1)+"/"+row.id_module; 
					                		return "<a href='"+module_url+"'><i class='fa "+(row.m_active==1?'fa-check':'fa-remove')+"' style='color:"+(row.m_active==1?'green':'red')+"'></i></a>"
					                	}
					                    
					                },
										{ "mDataProp": function ( row, type, val, meta ) {
														 id= row.id_module;
                	 									 edit_url=(access.edit=='1'?base_url+'index.php/settings/module/edit/'+id:'#');
														 delete_url=(access.delete=='1' ? base_url+'index.php/settings/module/delete/'+id : '#' );
														 delete_confirm= (access.delete=='1' ?'#confirm-delete':'');
														  action_content='<div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button><ul class="dropdown-menu">'+
															'<li><a href="'+edit_url+'" class="btn-edit"><i class="fa fa-edit" ></i> Edit</a></li>'+
															'<li><a href="#" class=" btn-del" data-href='+delete_url+' data-toggle="modal" data-target="#confirm-delete" ><i class="fa fa-trash"></i> Delete</a></li>';
														
														 return action_content;
														 }
													   
													}]
												
								});			  	 	
			 }
								$("div.overlay").css('display','none');
					  },
					  error:function(error)  
					  {
						 $("div.overlay").css("display", "none"); 
					  }	 
			      });
	
	
}

	//catlog module//HH  
    
 
function get_village_list()
{
	my_Date = new Date();
	$("div.overlay").css("display", "block"); 
	$.ajax({
			 url:base_url+"index.php/admin_settings/ajax_village_list?nocache=" + my_Date.getUTCSeconds(),
			 dataType:"JSON",
			 type:"GET",
			 success:function(data){
			   			set_village_list(data);
			   			 $("div.overlay").css("display", "none"); 
					  },
					  error:function(error)  
					  {
						 $("div.overlay").css("display", "none"); 
					  }	 
			      });
}


function set_village_list(data)	
{
   var customer = data;
   var oTable = $('#village_list').DataTable();
	 oTable.clear().draw();
	oTable = $('#village_list').dataTable({

				                "bDestroy": true,

				                "bInfo": true,

				                "bFilter": true,

				                "bSort": true,

				                 "dom": 'T<"clear">lfrtip',

				                "tableTools": { "aButtons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },

				                "aaData": customer,

				                "order": [[ 0, "desc" ]],

				               "aoColumns": [

					                { "mDataProp": "id_village" },

					                { "mDataProp": "village_name" },

					                { "mDataProp": "post_office" },

					                { "mDataProp": "taluk" },					                



 									{ "mDataProp": function ( row, type, val, meta ) {

					                	 id= row.id_village;

					                	 edit_url= base_url+'index.php/settings/village_form/edit/'+id;

					                	 delete_url=base_url+'index.php/settings/village_form/delete/'+id;

					                	 delete_confirm= '#confirm-delete';

					                	 action_content='<div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button><ul class="dropdown-menu">'+

					    '<li><a href="'+edit_url+'" class="btn-edit"><i class="fa fa-edit" ></i> Edit</a></li>'+

					    '<li><a href="#" class="btn-del" data-href="'+delete_url+'" data-toggle="modal" data-target="'+delete_confirm+'"  ><i class="fa fa-trash"></i> Delete</a></li></ul></div>';

					                	return action_content;

					                	}

					            }] 

				            });	
							

}

//
$("#submit_maintn_tab,#submit_others_tab,#submit_sch_pay_tab,#submit_metal_tab").click(function(e){
	e.preventDefault();
	$("#tab_name").val($(this).val());
	$("#gen_settings").submit();
});
    
//edit branch with image//HH    
    
/*$("#edit_branch_img").change( function(e){
	
    	e.preventDefault(); 
		 //alert(asd);
    	validat_Image(this);
	});*/

    function validat_Image()
   	{

		  var height=($(this).height());
      	  var width=($(this).width());
       	 if(arguments[0].id == 'edit_sch_branch_img')
          {
    		 var preview = $('#edit_sch_branch_img_preview');
    	  }
    	  else if(arguments[0].id == 'branch_img')
    	  {
    	  	 var preview = $('#sch_branch_img_preview');
    	  }
    	if(arguments[0].files[0].size > 1048578)
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
			/*if(width>960 && height>525)
			{
				alert("Width and height should be less than  960 * 525 ");
				arguments[0].value = "";
				preview.css('display','none');
			}*/
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
  
/* $("#update_branch").on('click',function(){

		

		var branch = {

       

        description:  CKEDITOR.instances.description1.getData()

        };

        var id=$("#edit-id").val();			  

		update_branch(branch,id);

	});	
		
		$("#update_branch").on('click',function(){

		var branch = {

       

        description:  CKEDITOR.instances.description1.getData(),

        	file : $("#edit_sch_branch_img")[0].files[0]


        };
        console.log(branch);
        var id=$("#edit-id").val();			  

		update_branch(branch,id);

	});*/
	
	$("#branch_img,#edit_sch_branch_img").change( function(e){
    	e.preventDefault(); 
    	validat_Image(this);
	});	
 //edit branch with image//HH  
	
//Offline Rate List Admin with Branch Filter//HH
	 $('#branch_select').select2().on("change", function(e) {
         
	
	if(this.value!='')
	{  
	
	var from_date = $('#offrate_list1').text();
	var To_date  = $('#offrate_list2').text();
	
	var id_branch=$(this).val();
	get_offrate_list(from_date,To_date,id_branch);
	}
	  
  });
	
  //Offline Rate List Admin with date picker//HH
function get_offrate_list(from_date="",To_date="",id_branch='')
{
	my_Date = new Date();
	postData = (from_date !='' && To_date !='' || id_branch!='' ? {'from_date':from_date,'To_date':To_date,'id_branch':id_branch}: ''),
	 $("div.overlay").css("display", "block"); 
	$.ajax({
			  url:base_url+ "index.php/admin_settings/offratelist_data?nocache=" + my_Date.getUTCSeconds(),
			 data: (postData),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			 	$('#total_offrate').text(data.length);
			   			set_offrate_list(data);
			   			 $("div.overlay").css("display", "none"); 
					  },
					  error:function(error)  
					  {
						 $("div.overlay").css("display", "none"); 
					  }	 
		  });
}

function set_offrate_list(data)
{
     var ej_metalratehistory = data;
    var oTable = $('#offrate_list').DataTable();
    oTable.clear().draw();
    //console.log(kyc);
  
        oTable = $('#offrate_list').dataTable({
                "bDestroy": true,
                "bInfo": true,
                "bFilter": true,
                "bSort": true,				                
                "dom": 'T<"clear">lfrtip',				                
                "tableTools": { "aButtons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },
                "aaData": ej_metalratehistory,
                "order": [[ 0, "desc" ]],
                "aoColumns": [ { "mDataProp": function ( row, type, val, meta ){ 
		                	chekbox='<input type="checkbox" class="id_metalrates" name="id_metalrates[]" value="'+row.id_metalrates+'"/>'
		                	return chekbox+" "+row.id_metalrates;
		             
		                }}, 
                                { "mDataProp": "updatetime" },
								{ "mDataProp": "add_date" },
    							{ "mDataProp": "goldrate_22ct" },
                                { "mDataProp": "silverrate_1gm" },
    							{ "mDataProp": "platinum_1g" },
    							/*{ "mDataProp": "date" },
    							{ "mDataProp": "RATE" },
    						    { "mDataProp": "TIME" },
    						    { "mDataProp": "CREATEDDATETIME" },*/
    							{ "mDataProp": "id_branch" }
    						
    							
    						 ] 
            });
    				        
    
    $("div.overlay").css("display", "none");    
				            			  	 	
} 

 //Offline Rate List Admin with date picker//
 
 
 // Terms For App from admin side add//HH
  function get_terms_and_conditions()
{
	
	
	my_Date = new Date();	
		$.ajax({
		url: base_url+"index.php/settings/terms_and_conditions/ajax_list?nocache=" + my_Date.getUTCSeconds(),
		type:"GET",
        dataType:'json',
        cache : false,
		success:function(data){
	            set_terms_and_conditions(data.terms);
		}
	});
}

$('#select_type').on('change',function(){
    
    if(this.value!='')
    {
        $('#type').val(this.value);
    }
    else
    {
        $('#type').val('');
    }
    
  
});

function set_terms_and_conditions(data)	
{
   var oTable = $('#general_list').DataTable();
	 oTable.clear().draw();
	 	oTable = $('#general_list').dataTable({
				                "bDestroy": true,
				                "bSort": true,				          
				                "dom": 'T<"clear">lfrtip',
				               "tableTools": { "aButtons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } }] },
				                "aaData": data,
				               "aoColumns": [
					                { "mDataProp": "id_general" },
					                { "mDataProp": "name" },
						   			{ "data": function ( row, type, val, meta ) {
						                	 id= row.id_general;
						                	  action_content='<a  class="btn btn-primary btn-edit" role="button" href='+base_url+"index.php/settings/terms_and_conditions/edit/"+id+'  ><i class="fa fa-edit" ></i> Edit</a> <a  class="btn btn-danger btn-del"  data-href='+base_url+"index.php/settings/terms_and_conditions/delete/"+id+'  data-toggle="modal" data-target="#confirm-delete" ><i class="fa fa-trash" ></i> Delete</a>' 
						                	 //action_content='<a  class="btn btn-primary btn-edit" role="button" href='+base_url+"index.php/settings/terms_and_conditions/edit/"+id+'  ><i class="fa fa-edit" ></i> Edit</a> <a href="#" class="btn btn-danger btn-del" data-href='+base_url+"settings/terms_and_conditions/delete/"+id+' data-toggle="modal" data-target="#confirm-delete" ><i class="fa fa-trash"></i> Delete</a>'
						                	return action_content;
						                	}
						               
						            },					              
					            ] 
				            });	

}

//Retail Settings //HH 
function set_retail_settings_list()
{
my_Date = new Date();
$("div.overlay").css('display','block');
$.ajax({
		 url:base_url+ "index.php/settings/retail_setting/ajax?nocache=" + my_Date.getUTCSeconds(),
		 dataType:"JSON",
		 type:"POST",
		 success:function(data){
			 var retail_setting 	= data.retail_setting;
			 var access		= data.access;	
			 //$('#total_retail_settings').text(.retail_setting.length);
			  if(access.add == '0')
			 { 
					 
				$('#add_ret_setting').attr('disabled','disabled');
			//	$('#add_ret_setting').style.visibility = 'hidden';
				
			 }
			 
		 var oTable = $('#ret_setting_list').DataTable();
			 oTable.clear().draw();
			  if (retail_setting!= null && retail_setting.length > 0)
						{  
					  
						oTable = $('#ret_setting_list').dataTable({
								"bDestroy": true,
								"bInfo": true,
								"bFilter": true,
								"bSort": true,
								"aaData": retail_setting,
								"aoColumns": [	{ "mDataProp": "id_ret_settings" },
												{ "mDataProp": "name" },
												{ "mDataProp": "value"},
												{ "mDataProp": "description"},
												{ "mDataProp": "created_by"},
												{ "mDataProp": "updated_by"},
									   
									{ "mDataProp": function ( row, type, val, meta ) {
													 id= row.id_ret_settings;
													  edit_url=(access.edit=='1'?base_url+'index.php/settings/retail_setting/edit/'+id:'#');
													 delete_url=(access.delete=='1' ? base_url+'index.php/settings/retail_setting/delete/'+id : '#' );
													 delete_confirm= (access.delete=='1' ?'#confirm-delete':'');
													  action_content='<div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button><ul class="dropdown-menu">'+
														'<li><a href="'+edit_url+'" class="btn-edit"><i class="fa fa-edit" ></i> Edit</a></li>'+
														'<li><a href="#" class=" btn-del" data-href='+delete_url+' data-toggle="modal" data-target="#confirm-delete" ><i class="fa fa-trash"></i> Delete</a></li>';
													
													 return action_content;
													 }
												   
												}]
											
							});			  	 	
		 }
							$("div.overlay").css('display','none');
				  },
				  error:function(error)  
				  {
					 $("div.overlay").css("display", "none"); 
				  }	 
			  });


		   }

//Retail Settings //


//Profile Settings
$("input[name='profile[tag_transfer]']").click(function () {
    if($("#tag_transfer").is(":checked"))
    {
        $('#tag_transfer').val(1);
    }
    else
    {
		$('#tag_transfer').val(0);
    }
});

$("input[name='profile[packaging_item_transfer]']").click(function () {
    if($("#packaging_item_transfer").is(":checked"))
    {
        $('#packaging_item_transfer').val(1);
    }
    else
    {
		$('#packaging_item_transfer').val(0);
    }
});

$("input[name='profile[purchase_item_transfer]']").click(function () {
    if($("#purchase_item_transfer").is(":checked"))
    {
        $('#purchase_item_transfer').val(1);
    }
    else
    {
		$('#purchase_item_transfer').val(0);
    }
});


$("input[name='profile[non_tag_transfer]']").click(function () {
    if($("#non_tag_transfer").is(":checked"))
    {
        $('#non_tag_transfer').val(1);
    }
    else
    {
		$('#non_tag_transfer').val(0);
    }
});

//Profile Settings

function get_customer_list()

{

	my_Date = new Date();

	var lower = $('#lower').val();

	var upper = $('#upper').val();

	console.log(lower);

	console.log(upper);

	$.ajax({

			  url:base_url+ "index.php/settings/import/ajax_customer_list/"+lower+"/"+upper+"?nocache=" + my_Date.getUTCSeconds(),

			 data:{'lower':lower,'upper':upper},

			 dataType:"JSON",

			 type:"POST",

			 success:function(data){

			 	

			   			set_import_customer_list(data);

					  },

					  error:function(error)  

					  {

						 $("div.overlay").css("display", "none"); 

					  }	 

		  });

}







function set_import_customer_list(data)

{

	

	 var imported = data.imported;

	 var oTable = $('#customer_imported_list').DataTable();

	     oTable.clear().draw();

			  	 if (imported!= null && imported.length > 0)

			  	  {  	

					  	oTable = $('#customer_imported_list').dataTable({

				                "bDestroy": true,

				                "bInfo": true,

				                "bFilter": true,

				                "bSort": true,

				                "aaData": imported,

				                "aoColumns": [{ "mDataProp": function(row,type,val,meta){ 

				                     return "<label class='checkbox-inline'><input type='checkbox' name='account_id[]' value='"+row.id_customer+"'/> " + row.id_customer + "</label>"

				                   }				                    

				                },

					                { "mDataProp": "name" },

					                { "mDataProp": "mobile" },

					                { "mDataProp": "email" }]

				            });			  	 	

					  	 }	

}


$(document).on('click', "#profession_list  a.btn-edit", function(e) {

	

		$("#ed_profession").val('');

		e.preventDefault();

		id=$(this).data('id');

	    get_profession(id);

	    $("#edit-id").val(id);  

	});
	
$("#update_profession").on('click',function(){

		

		var profession =$("#ed_profession").val()			  

		var id=$("#edit-id").val();	

       if(profession)

       {

		   update_profession(profession,id);

	   }		   		

	});
	

function set_profession_table()

{

	my_Date = new Date();

	$.ajax({

			 url:base_url+ "index.php/settings/profession_list?nocache=" + my_Date.getUTCSeconds(),

			 dataType:"JSON",

			 type:"POST",

			 success:function(data){

				 var profession 	= data.data;

				 var access		= data.access;	

				 $('#total_profession').text(profession.length);

				  if(access.add == '0')

				 { 

			 			

					$('#add_profession').attr('disabled','disabled');

					

				 }

				 else{

					$("#add_profession").on('click',function(){

					if($('#profession').val())

					{			

						add_profession($('#profession').val());

						$('#profession').val('');

					}

					

				});

	

				 }

			 var oTable = $('#profession_list').DataTable();

				 oTable.clear().draw();

						 if (profession!= null && profession.length > 0)

							{  	

							oTable = $('#profession_list').dataTable({

									"bDestroy": true,

									"bInfo": true,

									"bFilter": true,

									"bSort": true,

									"aaData": profession,

									"aoColumns": [	{ "mDataProp": "id_profession" },

													{ "mDataProp": "name" },

													{ "mDataProp": function ( row, type, val, meta ) {

														 id= row.id_profession;

                	 									 edit_target=(access.edit=='0'?"":"#confirm-edit");

														 delete_url=(access.delete=='1' ? base_url+'index.php/settings/profession/delete/'+id : '#' );

														 delete_confirm= (access.delete=='1' ?'#confirm-delete':'');

														 action_content='<a href="#" class="btn btn-primary btn-edit" id="edit" role="button" data-toggle="modal" data-id='+id+'  data-target='+edit_target+'><i class="fa fa-edit" ></i> Edit</a> <a href="#" class="btn btn-danger btn-del" data-href='+delete_url+' data-toggle="modal" data-target="#confirm-delete" ><i class="fa fa-trash"></i> Delete</a>'

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


	
	
	
	
function get_profession(id)

{

   my_Date = new Date();

	$.ajax({

		type:"GET",

		url: base_url+"index.php/settings/profession/edit/"+id+"?nocache=" + my_Date.getUTCSeconds(),

		cache:false,		

		dataType:"JSON",

		success:function(data){

			profession=data.name;

		    $('#ed_profession').val(profession);

		   // console.log(wt);

		}

	});
}



function add_profession(profession)

{

	my_Date = new Date();

    

	$.ajax({

		data:{"profession":profession},

		url: base_url+"index.php/settings/profession/add?nocache=" + my_Date.getUTCSeconds(),

		type:"POST",

		cache:false,		

		success:function(data){

			//$('#department').val('');

			window.location.reload(true);

		}

			

		

		

	});

}

function update_profession(profession,id)
{

	my_Date = new Date();
	$.ajax({

		data:{"profession":profession},

		url: base_url+"index.php/settings/profession/update/"+id+"?nocache=" + my_Date.getUTCSeconds(),

		type:"POST",

		

		cache: false,

		success:function(){

			//console.log(get_weight(id));

			window.location.reload(true);

			//set_table();

		}

	});

}
	
 
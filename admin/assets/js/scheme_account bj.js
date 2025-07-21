var ctrl_page = path.route.split('/');

$(document).ready(function() {
    
    $('#acc_join').submit(function(e)  {
        $('#submit').prop('disabled', true);
    });
  
    get_group_list();
    $("#account_name").on('keypress', function (event) {
        var regex = new RegExp("^[a-zA-Z _ \r\s]+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
            event.preventDefault();
            alert('Account name must contain alphabets only');
            return false;
        }
    });
    
    customers = [];
    schCodes = [];
	
	if(ctrl_page[1]=='edit')
	{
	    gift_issued_list(ctrl_page[2]);
    	$('#gift_verify_otp').on('click',function(){
    		$(this).attr("disabled", true); 
            verify_gift_otp($('#otp').val());
    	});
	}
	
	if(ctrl_page[1]=='add')
	{
		$.each(customerListArr, function(key, val)
		{
			customerList.push({'label' : val.mobile+'  '+val.name, 'value' : val.id, 'cus' : val.name});
		});

		$( "#mobile_number" ).autocomplete(
		{

		source: customerList,
		select: function(e, i)
		{
		console.log("successfully");
		e.preventDefault();
		$("#mobile_number" ).val(i.item.label);
		$("#id_customer").val(i.item.value);
		$("#cus_name").val(i.item.cus);                  // cus name taken for a/c name in a/c join page and based on the settings//HH
		var id_customer=$('#id_customer').val();
		var cus_name = $("#cus_name").val();
		
		console.log(cus_name);
		 if($("#mobile_number" ).val().length>0)
		 {
			get_customer_detail(cus_name,id_customer);

		 }

		},
		response: function(e, i) {
            // ui.content is the array that's about to be sent to the response callback.
            if (i.content.length === 0) {
               alert('Please Enter a valid Number');
               $('#mobile_number').val('');
            } 
        },
		 minLength: 4,
		});
	}
	
	if(ctrl_page[1]=='account' && (ctrl_page[2]=='add' || ctrl_page[2]=='edit') ){
    	
        //Get gift issue & prize details for each account wise //
    }
	$('#calc_blc').select2().on("change", function(e) 
        {           
            if(this.value!='')
    		{  
                $("#id_type").val((this).value);
                $(".close_actionBtns").css("display", 'block');
                
                $("#gift_issued").prop("required", true);
                $("#gift_issued").css("display", '');
                $('#submit_gift').prop('disabled',true);
                
               // $("#verify_issue").css("display", '');
               // $("#verify_issue").prop('disabled', false);
    		}
        });
	if(ctrl_page[1]=='scheme_group' && (ctrl_page[2]=='add' || ctrl_page[2]=='edit') )
	{ 
			 var dateToday = new Date();	
		    
			$('#date_add').datepicker({
               format: 'dd-mm-yyyy',
                  "setValue": new Date(),
                  "autoclose": true
              
            }).datepicker("setDate");
			
			$('#date_update').datepicker({
               format: 'dd-mm-yyyy',
                  "setValue": new Date(),
                  "autoclose": true
              
            }).datepicker("setDate");
			
			get_schemename();
			
	}
	else if(ctrl_page[1]=='scheme_group' && ctrl_page[2]=='edit'){
		get_schemename();
	}
	
	
// enable & disable save button//HH	
 if(ctrl_page[1]=='scheme_group' && (ctrl_page[2]=='add')){
     $('#group').prop('disabled', true);
 }	
 		else if(ctrl_page[2]=='add' || ctrl_page[2]=='edit'){
	 $('#group').prop('disabled', false);
	}
// enable & disable save button//		
 	
 	
		$('#group_code').on('blur onchange',function(){
        var group_code=this.value;
        check_groupcode(group_code);
    });
	// scheme group//

	if(ctrl_page[1]=='scheme_reg' && ctrl_page[2]=='list')
	{
		
		// existing scheme reg// 
		 
			if(ctrl_page[3])
			 {
				 $('#filtered_status').val(ctrl_page[3]);
			 }
		// existing scheme reg// 
		
			
			initialize_branch_list("sel_branch");
			
			
			
			
	
			console.log(ctrl_page[1]);
			
			get_request_list();
			$('#sel_branch').select2().on("change", function(e) {
		      if(this.value!='')
		      {
		      	status =  $('#filtered_status').val();
		      	get_request_list('','',this.value,status);
		      	 
			  }
			});  
			$('#filtered_status').on('change', function() {
		 	  if(this.value == 1){
		 	      $("#reject").css("display", "none");
		 	      $("#approve").css("display", "none");
		 	      $("#revert").css("display", "block");
		 	  }else{
		 	      $("#reject").css("display", "block");
		 	      $("#approve").css("display", "block");
		 	      $("#revert").css("display", "none");
		 	  }
		 	  get_request_list('','','',this.value);
		 	});
		 	
		 	$('#payment_list1').empty();
            $('#payment_list2').empty();
            $('#payment_list1').text(moment().startOf('month').format('YYYY-MM-DD'));
             $('#payment_list2').text(moment().endOf('month').format('YYYY-MM-DD'));

			$('#reqList-dt-btn').daterangepicker(
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
	               $('#payment_list1').text(start.format('YYYY-MM-DD'));
                $('#payment_list2').text(end.format('YYYY-MM-DD'));       
	             get_request_list(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'),'',$('#filtered_status').val())
	          }
	        );
	        
			$("input[name='upd_status_btn']:radio").change(function(){
				if($("input[name='reg_request_id[]']:checked").val())
				{
				 	var selected = [];
				 	var approve=false;
					$("#scheme_reg_list tbody tr").each(function(index, value){
						if($(value).find("input[name='reg_request_id[]']:checked").is(":checked")){
				var firstPayment_amt=$(value).find(".firstPayment_amt").val();
				var scheme_type=$(value).find(".scheme_type").val();
			var firstpayamt_as_payamt=$(value).find(".firstpayamt_as_payamt").val();
			var get_amt_in_schjoin=$(value).find(".get_amt_in_schjoin").val();
							
			if(scheme_type==3 && firstPayment_amt=='' && firstpayamt_as_payamt==1 )
			{
				alert('Please Enter a first Installment Payment Amount');
				approve=false;
				 $('input[name=upd_status_btn]').removeAttr('checked');

			}
			else
			{
				approve=true;
			}


							transData = { 'id_reg_request'   : $(value).find(".id_reg_request").val(),
										  'remark'  : $(value).find(".remark").val(),						
										  'id_scheme'  : $(value).find(".id_scheme").val(),					
										  'id_branch'  : $(value).find(".id_branch").val(),				
										  'scheme_acc_number'  : $(value).find(".chit_no").val(),
										  'scheme_group_code'  :  $(value).find(".scheme_group_code").val(),
										  'group_code'  :  $(value).find(".group_code").val(),
										  'id_customer'  : $(value).find(".id_customer").val(),
										  'ac_name'  : $(value).find(".ac_name").val(),					
										  'mobile'  : $(value).find(".mobile").val(),	
										  'email'  : $(value).find(".email").val(),
										  'added_by'  : $(value).find(".added_by").val(),
										   'pan_no'  : $(value).find(".pan_no").val(),
										    'firstPayment_amt'  : $(value).find(".firstPayment_amt").val(),
										    'paid_installments'  : $(value).find(".paid_installments").val(),
										  'balance_amount'  : $(value).find(".balance_amount").val(),
										  'balance_weight'  : $(value).find(".balance_weight").val(),
										  'last_paid_weight'  : $(value).find(".last_paid_weight").val(),
										  'last_paid_chances'  : $(value).find(".last_paid_chances").val(),
										  'last_paid_date'  : $(value).find(".last_paid_date").val(),
							}

							
							selected.push(transData);	
				 		}
					})
					req_status = $("input[name='upd_status_btn']:checked").val();
					req_data = selected;
					console.log(req_data);
			    	if(approve==true)
					{
					update_request_status(req_status,req_data);
					}		
				}
		   });
		
	} 
   
     $("#block_payment").bootstrapSwitch();	
 $("#show_gift_article").bootstrapSwitch();	
     

    //to show Closed Acc detail

	$(document).on('click', "a.closed_ac_detail", function(e) {

       e.preventDefault();

		

		$('.closed_acc_detail').html(closedAccDetail($(this).data('id')));

		  $('#clsd_acc_detail').modal('show', {backdrop: 'static'});

	});

     
	  $('#close_save').click(function(event){
	  if($('#remark_close').val()==''){
	  	 msg='<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong> Enter comment.</div>';				
				$("div.overlay").css("display", "none");
				$('#error-msg').html(msg);
	  	return false;
	  }
	  return true;
    });	
	
	
	$( "#add_benefits,#add_charges").focus(function() {
 		 $('.close_actionBtns').css("display","block");
	});
	 
	 
	 
	 $('#calc_blc').click(function(event){
    	if($('#add_benefits').val()==''){
				$('#add_benefits').val(parseFloat('0').toFixed(2));		
			}
		if($('#add_charges').val()==''){
				$('#add_charges').val(parseFloat('0').toFixed(2));		
			}
    	
    	if( $('#sch_typ').val()==0 ||  $('#sch_typ').val()==3)
		{
			
	   	var c_bal = (parseFloat($('#closing_amount').val()) + parseFloat($('#benefits').val()) + parseFloat($('#add_benefits').val())) - (parseFloat($('#detections').val()) + parseFloat($('#bank_chgs').val()) + parseFloat($('#add_charges').val()));

   	   	$('#closing_balance').val(parseFloat(c_bal).toFixed(2));

	   }
	   /* else if($('#sch_typ').val()!=0 && $('#add_benefits').val()!=''){
			var c_bal =(parseFloat($('#closing_weight').val()) + parseFloat($('#add_benefits').val()));		
   	   		$('#closing_balance').val(parseFloat(c_bal).toFixed(3));   	   	
		} */
		
			else if($('#sch_typ').val()!=0 && $('#add_benefits').val()!=''){
			
			if($('#fixed_wgtschamt').val()==0 && $('#add_benefits').val()!='0.00')
			 {
				var c_bal = (parseFloat($('#closing_weight').val()) + (parseFloat($('#add_benefits').val())/parseFloat($('#metal_rate').val())));
				var c_amt = (parseFloat($('#closing_amount').val()) + parseFloat($('#add_benefits').val()));
				
				var add_benefits = parseFloat($('#add_benefits').val()).toFixed(3);	
				$('#calc_blc').attr('disabled', 'disabled');				
								
				 
			 }
			 if($('#fixed_wgtschwgt').val()==1 && $('#add_benefits').val()!='0.00')
			 {
				var c_bal =(parseFloat($('#closing_weight').val()) + parseFloat($('#add_benefits').val()));	
				var c_amt = (parseFloat($('#closing_amount').val()) + (parseFloat($('#add_benefits').val())*parseFloat($('#metal_rate').val())));
				var add_benefits = parseFloat($('#add_benefits').val()).toFixed(3);
				$('#calc_blc').attr('disabled', 'disabled');
			 }
			 
			if((($('#fixed_wgtschwgt').val()==1 || $('#fixed_wgtschamt').val()==0) && $('#add_benefits').val()!='0.00'))
			{
				$('#closing_balance').val(parseFloat(c_bal).toFixed(3));   	   	
				$('#closing_amt').val(parseFloat(c_amt).toFixed(3));   	   	
				$('#add_benefit').val(add_benefits);  
			}
			
		}
	  
	  $('#remark_close').css("display","block");
	  $('.close_actionBtns').css("display","block");
	  $('#close_actionBtns').css("display","block");
	  
	  
    });
	 
	 
	// add_benefits weight or amount
	 
	 if($('#sch_typ').val()!=0 || $('#sch_typ').val()!=3)
	 { 
		 $("#fixed_wgtschamt").prop("checked","checked");
		 $("#fixed_wgtschamt").val(0);
		 $('#add_benefits').val('0.00');
		 $('#wgt_symbol').hide();
	 }
	 
	   $("input[name='account[add_benefixed]']:radio").change(function () {

			 var closing_amt=$('#closing_amt').val();
		      var closing_weight=$('#closing_weight').val();	
	  
		   if($("#fixed_wgtschamt").is(':checked'))
		   {
			   $("#fixed_wgtschamt").val(0);
			    $('#fixed_wgtschwgt').val('');
			    $('#add_benefits').val('0.00');
				$('#wgt_symbol').hide();
				$('#curren_symbol').show();
			  $('#calc_blc').attr('disabled', false);
			   $('#closing_amount').val(closing_amt);
			   $('#closing_balance').val(closing_weight);
				
		   }

		   else if($("#fixed_wgtschwgt").is(':checked'))
		   {
		        $('#fixed_wgtschwgt').val(1);
				$("#fixed_wgtschamt").val('');
				$("#add_benefits").val('0.00');
				$('#wgt_symbol').show();
				$('#curren_symbol').hide();
				$('#calc_blc').attr('disabled', false);
				 $('#closing_amount').val(closing_amt);
			    $('#closing_balance').val(closing_weight);
		   }

    });
	 
	$('#clear_blc').click(function(event){
		
		       var closing_amt=$('#closing_amt').val();
		       var closing_weight=$('#closing_weight').val();
			   $('#add_benefits').val('0.00');
			   $('#closing_amount').val(closing_amt);
			   $('#closing_balance').val(closing_weight);
			   $('#calc_blc').attr('disabled', false);
		
	});
	 
	 
	 
	 
	 
	 // add_benefits weight or amount 
	 
	 
	 
	 
	 
	 

    function closedAccDetail(id)

	{

		var transaction="";

		$("div.overlay").css("display", "block");

			$.ajax({

				  url:base_url+ "index.php/account/closed/view/"+id+"?nocache=" + my_Date.getUTCSeconds(),

				

				 dataType:"JSON",

				 type:"POST",

				 async:false,

				 success:function(data){

			

				 transaction  = "<table class='table table-bordered trans'><tr><th>Customer Name</th><td>"+data.name+"</td></tr><tr><th>Customer Mobile</th><td>"+data.mobile+"</td></tr><tr><th>Account Name</th><td>"+data.account_name+"</td></tr><tr><th>Account No.</th><td>"+data.code+'-'+(data.scheme_acc_number!=''?data.scheme_acc_number:'Not Allocated')+"</td></tr><tr><th>Start Date</th><td>"+data.start_date+"</td></tr><tr><th>Closed Date</th><td>"+data.closing_date+"</td></tr><tr><th>Closed Employee</th><td>"+data.employee_closed+"</tr></td><tr><th>Scheme Name</th><td>"+data.scheme_name+"</td></tr><tr><th>Scheme Type</th><td>"+data.scheme_type+"</td></tr><tr><th>Amount Payable</th><td>"+data.sch_amt+"</td></tr><tr><th>Total Paid Installments</th><td>"+data.paid_installments+'/'+data.total_installments+"</td></tr><tr><th>Total Paid</th><td>"+data.total_paid+"</td></tr>"+(data.total_installments==data.paid_installments ? "<tr><th>Scheme Benefits</th><td>"+data.interest+"</td></tr>" :'')+"<tr><th>Additional Benefits</th><td>"+data.additional_benefits+"</td></tr><tr><th>Detections/Tax</th><td>"+data.tax+"</td></tr><tr><th>Bank Charges</th><td>"+data.bank_chgs+"</td></tr><tr><th>Additional Charges</th><td>"+data.closing_add_chgs+"</td></tr>"+(data.sch_typ==1 ? "<tr><th>Closing Weight</th><td>"+data.closing_balance+' g'+"</td></tr>" :"<tr><th>Closing Balance</th><td>"+data.closing_balance+"</td></tr>")+"<tr><th>Closed Request By</th><td>"+data.closed_by+" ("+data.closedBy+")</td></tr><tr><th>OTP Verified Mobile</th><td>"+data.otp_verified_mob+"</td></tr><tr><th>Closing Comments</th><td><span class='label bg-yellow'>"+data.remark_close+"</span></td></tr></table>"

					return transaction;

						$("div.overlay").css("display", "none");

						  },

						  error:function(error)  

						  {

							 $("div.overlay").css("display", "none"); 

						  }	 

			  });

			   $("div.overlay").css("display", "none"); 

			return transaction;	

			

	}



     

     if(ctrl_page[1] == 'new')

	{
			  var date = new Date();
			  
		    var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 6, 1); 
		
			var from_date =  firstDay.getFullYear()+'-'+(firstDay.getMonth()+1)+'-'+firstDay.getDate();
	
			var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
	$('#account_list1').empty();
	$('#account_list2').empty();
	$('id_branch').val();
	 get_scheme_acc_list(from_date,to_date);
	$('#account_list1').text(moment().startOf('month').format('YYYY-MM-DD'));
	$('#account_list2').text(moment().endOf('month').format('YYYY-MM-DD'));	
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
          	    var id_branch=$('#id_branch').val();
				var id_customer=$('#id_customer').val();
				var id_metal=$('#id_metal').val();	
              get_scheme_acc_list(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'),id_branch,id_customer,id_metal)			 
			  $('#account_list1').text(start.format('YYYY-MM-DD'));
			  $('#account_list2').text(end.format('YYYY-MM-DD')); 

          }

        );   

}

if(ctrl_page[1] == 'close')

{

	

	get_closed_acc_list();
	
	if($('#enable_closing_otp').val()==0)
	{

	$('#otp').prop('disabled',true);
	$('#send_otp').prop('disabled',true);
	$('#verify_otp').prop('disabled',true);  

/*	$('#send_otp').click(function(event) {
	var btn = $(this);
	btn.prop('disabled', true);
	setTimeout(function(){
	btn.prop('disabled', false);
	btn.prop('value', 'Resend OTP');
	}, fewSeconds*1000);
	close_acc_otp();
	});*/


	}
	else{

	$('#otp').prop('disabled',false);
	$('#send_otp').prop('disabled',false);
	$('#verify_otp').prop('disabled',false); 
    $('#close_save').prop('disabled',false); //disabled HH//


//	$('#close_save').prop('disabled',true);  // closed valide //
//	$('#close_save_print').prop('disabled',true);  // closed valide HH//
	}
	
	$('#closed_list1').empty();
	$('#closed_list2').empty();
	$('#closed_list1').text(moment().startOf('month').format('YYYY-MM-DD'));
	$('#closed_list2').text(moment().endOf('month').format('YYYY-MM-DD'));	

	$('#closed-acc-dt-btn').daterangepicker(

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

                     

             get_closed_acc_list(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'))
			 
			  $('#closed_list1').text(start.format('YYYY-MM-DD'));
			  $('#closed_list2').text(end.format('YYYY-MM-DD')); 

          }

        );   

		

}

/*-- Coded by ARVK --*/



	var fewSeconds = 90;

	$('#send_otp').click(function(event) {

		var btn = $(this);

   		btn.prop('disabled', true);

   		setTimeout(function(){

        btn.prop('disabled', false);

        btn.prop('value', 'Resend OTP');

    	}, fewSeconds*1000);

      	close_acc_otp();

    	

    });
//refferal report

$( "#referal_code" ).on( "blur", function(event) {
		if($("#referal_code").val() != "")
		{
			event.preventDefault();
			var codes=$("#referal_code").val();
			var referalcode=$("#referalcode_val").val();			
			if(codes!=referalcode){
			   checkreferalcode(codes);
			}else{
				$('#referal_code').val('');
				alert("If We Enter Wrong Referal Code");
				
			}
			
		}
		
});
	
	function checkreferalcode(codes)
	{  
			var id_customer=$("#id_customer").val();
			$('.overlay').css('display','block');
			$.ajax({
			  type: 'POST',
			   data:{'referal_code':codes,'id_customer':id_customer},
			  url:  base_url+'index.php/admin_manage/referralcode_check',
			  dataType: 'json',
			  success: function(data) {
				$('.overlay').css('display','none');
			   	if(data.status==0)
				{
				   alert(data.msg);
				   $('#referal_code').val('');
				   $('#referal_code').attr("placeholder", data.msg);			
					return false;
				}	
			  },
		  	 

			});
	}


//refferal report
	

    $('#verify_otp').click(function(event) {

    	

    	$(this).prop('disabled','disabled');

    	verify_close_acc_otp();	

    });



/*-- / Coded by ARVK --*/

     

	$('#select_all').click(function(event) {

		$("tbody tr td input[type='checkbox']").prop('checked', $(this).prop('checked'));

      event.stopPropagation();

    });

   /* $("tbody tr td input[type='checkbox']").click(function(event) {

		$("#select_all").prop('checked', $(this).prop('checked'));

      	event.stopPropagation();

    }); */

	

	//for confirm closing active account

	$(document).on('click', "a.confirm-close", function(e) {

       e.preventDefault();

        var link=$(this).data('href');   

        

       $('#confirm-close').find('.btn-confirm').attr('href',link);

    });

    

   //for reverting close account 

    $(document).on('click', "a.confirm-revert", function(e) {

       e.preventDefault();

        var link=$(this).data('href');   

        

       $('#confirm-revert').find('.btn-confirm').attr('href',link);

    });

   

	$('#confirm-delete .btn-cancel').on('click', function(e) {

		

		$('.btn-confirm').attr('href',"#");

	}); 

	

	

	if($('#start_date').length>0)

	{

		$('#start_date').datepicker({

               format: 'dd-mm-yyyy'

                })

            .on('changeDate', function(ev){

            	

              

            $(this).datepicker('hide');

            });

	}
	
	if($('#maturity_date').length>0)
	{
		$('#maturity_date').datepicker({
               format: 'dd-mm-yyyy'
                })
            .on('changeDate', function(ev){
            $(this).datepicker('hide');
            });
	}

	$('#last_paid_date').datepicker({

	   format: 'dd-mm-yyyy'

		})

    .on('changeDate', function(ev){

	   $(this).datepicker('hide');

	});



	

	if($('#close_date').length>0)

	{

		$('#close_date').datepicker({

               format: 'dd-mm-yyyy'

                })

            .on('changeDate', function(ev){

            	

              

            $(this).datepicker('hide');

            });

	}	

	

	if($("#is_opening").length>0)

	{

		 prev_bal_edit();

		 $('#is_opening').on('change', function() {

		 	  prev_bal_edit();		 	  

		 });

	}

		

	

	if($('#customer_select').length>0)

	{

		get_customers();

	}

	if($('#scheme').length>0)

	{

		get_schemes();

	}

	



	 $('#scheme').on('change', function() {

	 	
 	    get_scheme_detail(this.value);
 	    
 	 /*if(this.value!='')
 	    {
 	         get_group_name(this.value);    // get_groupname for new sch join //HH
 	    }*/
 	    
 	   

	}); 

	

	

	$('.btn-acc-close').click(function (e) {

       e.preventDefault();

        var link=$(this).data('href');

       $('#confirm-close').find('.btn-confirm').attr('href',link);



   });

   

   $("input[name='account[closed_by]']:radio").change(function () {

		   if($(this).val()==1)

		   {

		   		if($('#nominee_mobile').val()=='')

			    {

			   		$('#otp').prop('disabled',true);

		            $('#send_otp').prop('disabled',true);

		            $('#verify_otp').prop('disabled',true);

			    }

		   }

		   else

		   {

		   	    $('#otp').prop('disabled',false);

		        $('#send_otp').prop('disabled',false);

		        $('#verify_otp').prop('disabled',false);

		   }

    });   



		$('#nominee_mobile').blur(function () {

		   if($(this).val().length==10)

		   {

		   		$('#otp').prop('disabled',false);

		        $('#send_otp').prop('disabled',false);

		        $('#verify_otp').prop('disabled',false);

		   }

		   else

		   {

		   	    $('#otp').prop('disabled',true);

		        $('#send_otp').prop('disabled',true);

		        $('#verify_otp').prop('disabled',true);

		        $(this).val('');

		   }

    });   



//****allow only numeric value and '.' start****

	

	$("#add_charges").keypress(function (e){

		  var charCode = (e.which) ? e.which : e.keyCode;

		  if (charCode != 46 &&(charCode > 31 && (charCode < 48 || charCode > 57)))

		  {

			return false;

		  }

    });

    

    $("#detections").keypress(function (e){

		  var charCode = (e.which) ? e.which : e.keyCode;

		  if (charCode != 46 &&(charCode > 31 && (charCode < 48 || charCode > 57)))

		  {

			return false;

		  }

    });

//****allow only numeric value and '.' end****





//**** 0 to 0.00 Start****



$('#add_charges').on('keyup',function(){

    	

    	if($('#add_charges').val()=='0')// || $('#add_charges').val()=='0')

   	   {

	   	$('#add_charges').val(parseFloat('0').toFixed(2));	

	   }

   	   

   });

   

   $('#detections').on('keyup',function(){

    	

    	if($('#detections').val()=='0')// || $('#detections').val()=='0')

   	   {

	   	$('#detections').val(parseFloat('0').toFixed(2));	

	   }

   	   

   });

//**** 0 to 0.00 end****





//****Benefits and Detections calculation start****

    

    $('#add_charges').on('blur',function(){

    	

    	if($('#add_charges').val()=='')

   	   {

	   	$('#add_charges').val(parseFloat(0).toFixed(2));

	   }

    	

   });

 

    $('#add_charges').on('keyup blur onfocus',function(){

    	

    	if($('#add_charges').val()!='' && $('#sch_typ').val()==0)

   	   {

	   	var c_bal = (parseFloat($('#closing_amount').val()) + parseFloat($('#benefits').val())) - (parseFloat($('#detections').val()) + parseFloat($('#bank_chgs').val()) + parseFloat($('#add_charges').val()));

   	   

   	   	$('#closing_balance').val(parseFloat(c_bal).toFixed(2));

	   }

    	

   });

   

   $('#detections').on('keyup blur onfocus',function(){



   	   	if($('#detections').val()!='' && $('#sch_typ').val()==0)

   	   {

	   	var c_bal = (parseFloat($('#closing_amount').val()) + parseFloat($('#benefits').val())) - (parseFloat($('#detections').val()) + parseFloat($('#bank_chgs').val()) + parseFloat($('#add_charges').val()));

   	   

   	   	$('#closing_balance').val(parseFloat(c_bal).toFixed(2));

	   }

   });

   

//****Benefits and Detections calculation end****



// scheme group //
//get_group_list();



});



function get_customers()

{

	$(".overlay").css('display','block');

	$.ajax({

		type: 'GET',

		url: base_url+'index.php/customer/get_customers',

		dataType:'json',

		success:function(data){

		  var id_customer =  $('#id_customer').val();
		  var cus_name =  $('#cus_name').val();

		   $.each(data, function (key, item) {

			   		$('#customer_select').append(

						$("<option></option>")

						  .attr("value", item.id)

						  .text(item.mobile+' '+item.name+''+item.cus_name )
					);

			});

			

			$("#customer_select").select2({

			    placeholder: "Enter mobile number",

			    allowClear: true

			});

				

			$("#customer_select").select2("val",(id_customer!='' && id_customer>0?id_customer:''));
			$("#customer_select").select2("val",(cus_name!='' && cus_name>0?cus_name:''));

			 $(".overlay").css("display", "none");	

		}

	});

}



 //on selecting drawee account

   $('#customer_select')

        .on("change", function(e) {

          //console.log("change val=" + this.value);

         

          if(this.value!='')

          {

          	 $("#id_customer").val(this.value);
          	 $("#cus_name ").val(this.value);

		  	 get_customer_detail(cus_name,this.value);

		  }

		

		  

   });


// based on selected branch  to schemes showed in add page -admin //HH
function get_schemes(id)

{
	$('#scheme').empty();
		my_Date = new Date();
	$(".overlay").css('display','block');

	$.ajax({

		type: 'GET',

		url: base_url+'index.php/scheme/get_schemes/'+id+'/'+my_Date.getUTCSeconds(),

		dataType:'json',

		success:function(data){
$('#scheme').empty();
			//console.log(data);

				var selectid=$('#scheme_val').val();
					schCodes = data;
					$.each(data, function (key, data) {

				  	
						var name=(data.name.concat(data.active==0 ?  '(Inactive)' :''));
		    	

						$('#scheme').append(

							$("<option></option>")

							  .attr("value", data.id_scheme)
							.text(name)

							  

							 	 

						);


					});

					

			$("#scheme").select2({

			    placeholder: "Enter scheme",

			    allowClear: true

			});

				
			$("#scheme").select2("val",(selectid!=null && selectid>0?selectid:''));

			

		$(".overlay").css('display','none');	


		}

		

		

	});

	

}

$('#scheme').on('change', function() { 
 	if($('#scheme').val() != null){
 		var scheme = schCodes.filter(function(schCode) { 
          return schCode.id_scheme == $('#scheme').val() ;
        }); 
        console.log(scheme[0]);
        $('#total_installments').val(scheme[0].total_installments);
        $('#get_amt_in_schjoin').val(scheme[0].get_amt_in_schjoin);  // firstPayment_amt get from customer based on the scheme settings//HH
        $('#maturity_type').val(scheme[0].maturity_type);
        $('#id_scheme').val(scheme[0].id_scheme);
        $('#has_gift').val(scheme[0].has_gift);
        if(scheme[0].is_pan_required == '1'){
            $('#pan_no').prop('required',true);
            $('#pan_no').prop('minlength',10); 
        }else{
           $('#pan_no').prop('required',false);  
           $('#pan_no').prop('minlength',0); 
        }
        
        if(scheme[0].id_scheme !=null && scheme[0].has_gift ==0){  // based on the scheme selection(has gift=1 && id_sch !-null) to showed Gift issues black in sch jon page admin
           
            $(".hasgift").hide();
               } else{
                   $(".hasgift").show(); 
            }
        
 	}
	    
});	


function  get_customer_detail(cus_name,id)

{

	$.ajax({

		type:'GET',

		 url:base_url+'index.php/customer/get_customer/'+cus_name+'/'+id,

		 dataType:'json',

		 success:function(data){

		 	

		    //console.log(data);

		    set_customer(data);

		 	

		 }

	});

}







function  get_scheme_detail(id)

{

	 my_Date = new Date();

	$.ajax({

		type:'GET',

		 url:base_url+'index.php/scheme/get_scheme/'+id+'/'+my_Date.getUTCSeconds(),

		 dataType:'json',

		 cache:false,

		 success:function(data){

		 	if(data.sch_limit_value!=null && data.sch_limit==1)
		    {
					
		    	if(data.sch_joined_acc>=data.sch_limit_value)
			    {
			    	alert('maximum scheme group limit reached');
			    	window.location.reload(true); 
			    }
		    }
    console.log(data.flexible_sch_type);
            if(data.sch_type==3 && ((data.flexible_sch_type==1 || data.flexible_sch_type==2) && data.get_amt_in_schjoin==1))
            {
                $('#firstPayment_amt').prop('required',true);
                $('#flx_denomintion').val(data.flx_denomintion);
                $('#min_amount').val(data.min_amount);
                $('#max_amount').val(data.max_amount);
            }else
            {
                 $('#firstPayment_amt').prop('required',false);
            }

		 	set_scheme(data);

		 	$("#scheme_type").val(data.scheme_type);

		 	prev_bal_edit();

		 }

	});

}

$('#firstPayment_amt').on('change',function(){
    var pay_amt=$(this).val();
    var flx_denomintion=parseInt($('#flx_denomintion').val());
    var min_amount=parseInt($('#min_amount').val());
    var max_amount=parseInt($('#max_amount').val());
    if(flx_denomintion!='')
    {
        if((pay_amt>=min_amount) && (pay_amt<=max_amount))
		   {
		           if((pay_amt%flx_denomintion)!=0)
		           {
		              alert('Please Enter a Amount in Multiples of '+flx_denomintion+'');
		              $("#firstPayment_amt").val('');
		           }else
		           {
		               $validAmt=true;
		           }
		   }
		   else
		   {
		       alert('Your Amount Shold be Minimum'+min_amount+' and Maximum '+max_amount+'');
		        $("#firstPayment_amt").val('');
		   }
    }
    
    
});

function prev_bal_edit()

{

	var scheme_type = $("#scheme_type").val();



	if($("#is_opening").prop('checked')==true)

	{

		if(scheme_type=="Weight")

		{

			$("#paid_installments").prop('disabled', false);

			$("#balance_amount").prop('disabled', false);

			$("#last_paid_date").prop('disabled', false);

			$("#balance_weight").prop('disabled', false);

			$("#last_paid_weight").prop('disabled', false);

			$("#last_paid_chances").prop('disabled', false);

		}

		else if(scheme_type=="Amount to Weight")

		{

			$("#paid_installments").prop('disabled', false);

			$("#balance_amount").prop('disabled', false);

			$("#last_paid_date").prop('disabled', false);

			$("#balance_weight").prop('disabled', false);

			$("#last_paid_weight").prop('disabled', false);

			$("#last_paid_chances").prop('disabled', false);

		}

		else if(scheme_type=="Amount")

		{

			$("#paid_installments").prop('disabled', false);

			$("#balance_amount").prop('disabled', false);

			$("#last_paid_date").prop('disabled', false);

			$("#balance_weight").prop('disabled', true);

			$("#last_paid_weight").prop('disabled', true);

			$("#last_paid_chances").prop('disabled', true);

		}

		else

		{

			$("#paid_installments").prop('disabled', true);

			$("#balance_amount").prop('disabled', true);

			$("#last_paid_date").prop('disabled', true);

			$("#balance_weight").prop('disabled', true);

			$("#last_paid_weight").prop('disabled', true);

			$("#last_paid_chances").prop('disabled', true);

		}

	}	

	else

	{

		$("#paid_installments").prop('disabled', true);

		$("#balance_amount").prop('disabled', true);

		$("#last_paid_date").prop('disabled', true);

		$("#balance_weight").prop('disabled', true);

		$("#last_paid_weight").prop('disabled', true);

		$("#last_paid_chances").prop('disabled', true);

	}

}



function set_customer(data)

{

	var ac_name=$('#account_name').val();

	var address=(data.address?data.address:"")+(data.address1?data.address1+", ":"")+(data.city?data.city:"")+(data.pincode?" - "+data.pincode:"");

	var mobile=(data.mobile?data.mobile:"-");

	var img_url=base_url+'assets/img/customer/'+data.id_customer+"/";
	var img_url=base_url+'assets/img/customer/'+data.cus_name+"/";

	var default_img=base_url+'assets/img/default.png';

	var cus_img_src=(data.cus_img!=null?img_url+data.cus_img:default_img);

    var name=(ac_name==null? (data.firstname?__capitalizeString(data.firstname):"")+" "+(data.lastname?__capitalizeString(data.lastname):""):ac_name);
   
  /* if(ctrl_page[1] == 'add'&& ctrl_page[0] == 'account')

	{
   if(data.referal_code!=null){
      $('#referal_code').val(data.referal_code!=''?data.referal_code:'');
      $('#referalcode_val').val(data.mobile);
     $('div#referalcode').css('display','none');
    }else{
         $('#referalcode_val').val(data.mobile);
         $('div#referalcode').css('display','block');
         $('#referal_code').val('');
      }
	}else if(ctrl_page[1] == 'edit' && ctrl_page[0] == 'account'){
	    
	   $('#referal_code').attr('disabled', true);
	}*/
	
	if(ctrl_page[1] == 'edit' && ctrl_page[0] == 'account'){
	    
	   $('#referal_code').attr('disabled', true);
	}
  
   
	$('#cus_address').text((address?address:"-"));

	$('#cus_mobile').text(mobile);

	$('#cus_img').attr("src", cus_img_src);

	$('#account_name').val(name);

    

}



function set_scheme(data)

{

	var sch_type=data.scheme_type;

	var sch_code=(data.code?data.code:"-");

	var sch_duration=(data.total_installments?data.total_installments:"-");

	var pay_type=(data.payment_type?data.payment_type:"-");

	var amount=(sch_type=='Amount'?data.amount:'0.00')

	var interest=(data.interest?data.interest:"-");

	var tax=(data.tax?data.tax:"-");

	var min_weight=(data.min_weight?data.min_weight:"-");

	var max_weight=(data.max_weight?data.max_weight:"-");

	var min_chance=(data.min_chance?data.min_chance:"-");

	var max_chance=(data.max_chance?data.max_chance:"-");

	



	var html=__label("Scheme Type",sch_type)

	

	html +=__label("Scheme Code",sch_code); 

	

	html +=  __label("Installments",sch_duration);

	html += __label("Payment Type",pay_type);

	if(pay_type="Multiple")

	{

		html += __label("Payment Chance",max_chance); 

		

	

	}

	

	if(sch_type=='Weight')

	{

		html +=  __label("Min Weight",min_weight); 

		html += __label("Max Weight",max_weight); 

	}

	else

	{

		html += __label("Amount",amount);

	}

	

	html += __label("Interest",interest);

	html += __label("Tax",tax) 	

	

	

	$('#sch_content').html(html);

	

}

//get cus mobile no// HH

if(ctrl_page[1]=='new')
{
        $('#mobilenumber').on('keyup',function(){
        var mobile=$('#mobilenumber').val();
        
        if(mobile.length==0){
        $("#id_customer").val('');
        }
        });
}

        if(ctrl_page[1]=='new')
        {
               $("#mobilenumber" ).autocomplete({
                source: function( request, response ) 
                {
                var mobile=$("#mobilenumber").val();
                
                my_Date = new Date();
                $.ajax({
        			  url:base_url+ "index.php/admin_customer/ajax_get_customers_list?nocache=" + my_Date.getUTCSeconds(),
                dataType: "json",
                type: 'POST',
                data:{'mobile':mobile},
                success: function( data ) 
                {
                        var data = JSON.stringify(data);
                        data = JSON.parse(data);
                        var cus_list = new Array(data.length);
                        var i = 0;
                        data.forEach(function (entry) {
                        console.log(entry.mobile);
                        var customer= {
                        label: entry.mobile+'  '+entry.firstname,
                        value:entry.id_customer
                        
                        };
                        cus_list[i] = customer;
                        i++;
                        });
                        response(cus_list);
                }
                });
                },
                minLength: 4,
                delay: 300, 
                	select: function(e, i)
		{
		e.preventDefault();
		$("#mobilenumber" ).val(i.item.label);
		$("#id_customer").val(i.item.value);
		//$("#id_scheme_account").val(i.item.id_scheme_account);
		$('.overlay').css('display','block');
		
		if($('#id_customer').val()!='')
		{
		                var from_date = $('#account_list1').text();
						var to_date  = $('#account_list2').text();
						var id_customer=$('#id_customer').val();	
						var id_branch=$('#id_branch').val();
						var id_metal=$('#id_metal').val();	
						get_scheme_acc_list(from_date,to_date,id_branch,id_customer,id_metal);
		}
	

		},
			response: function(e, i) {
            // ui.content is the array that's about to be sent to the response callback.
            if (i.content.length === 0) {
               alert('Please Enter a valid Number');
               $('#mobilenumber').val('');
            } 
        },
                });
        }
     

//scheme account list not closed

function get_scheme_acc_list(from_date="",to_date="",id_branch ="",id_customer="",id_metal="")

	{
		
		
	my_Date = new Date();
    var type=$('#date_Select').find(":selected").val();
	 $("div.overlay").css("display", "block"); 

	

	$.ajax({

			  url:base_url+ "index.php/account/get/ajax_account_list?nocache=" + my_Date.getUTCSeconds(),

			 data: (from_date !='' && to_date !=''? {'from_date':from_date,'to_date':to_date,'id_branch':id_branch,'id_customer':id_customer,'id_metal':id_metal,'type':type}: ''),

			 dataType:"JSON",

			 type:"POST",

			 success:function(data){

			   			set_scheme_acc_list(data);
			   			$('body').addClass("sidebar-collapse");

			   			 $("div.overlay").css("display", "none"); 

					  },

					  error:function(error)  

					  {

						 $("div.overlay").css("display", "none"); 

					  }	 

			      });

}



function set_scheme_acc_list(data)

{
	
	
	

	 var account 	= data.data;

	 //console.log(account);

	 var access		= data.access;	

	 var close_acc	= 1;

	 $('#total_accounts').text(account.length);

	 if(access.add == '0')

	 {

		$('#add').attr('disabled','disabled');

	 }

	 var oTable = $('#sch_acc_list').DataTable();

	     oTable.clear().draw();
		 
		 
				  if (account!= null && account.length > 0)

			  	  {    
			  	      var schemeacc_no_set= (typeof data.data == 'undefined' ? '' :data.data[0].schemeacc_no_set);

			  	      // console.log(schemeacc_no_set);
			  	      
				     if ((schemeacc_no_set==0 || schemeacc_no_set==2 || schemeacc_no_set==3))
			  	     {  	
				 
				       

					  	oTable = $('#sch_acc_list').dataTable({

				                "bDestroy": true,

				                "bInfo": true,

				                "bFilter": true,

				                "bSort": true,

				                "order": [[ 0, "desc" ]],

								 "dom": 'lBfrtip',
           			              "buttons" : ['excel','print'],
						         "tableTools": { "buttons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },


				                "aaData": account,

				                 "order": [[ 0, "desc" ]],

				                "aoColumns": [
					                { "mDataProp": function ( row, type, val, meta ) {
					                	var url = base_url+'index.php/reports/payment/account/'+row.id_scheme_account;
					                	action = '<a href="'+url+'" target="_blank">'+row.id_scheme_account+'</a>';
					                	return action;
					                	}
					                },
					                { "mDataProp": "id_customer" },
					                { "mDataProp": "name" },

					                { "mDataProp": "mobile" },

					                { "mDataProp": "account_name" },
									{ "mDataProp": function ( row, type, val, meta ){
					                	return row.code;
					                	}
					                },

					                { "mDataProp": function ( row, type, val, meta ){
					                	if(row.has_lucky_draw==1){
					                	return row.group_code+' '+row.scheme_acc_number;
					                	}
					                	else{
					                		return row.scheme_acc_number;
					                	}
					                }},

					                { "mDataProp": "is_new" },

					                  { "mDataProp": function ( row, type, val, meta ){
                    	                	if(row.edit_custom_entry_date==0){
                    	                	return row.start_date;
                    	                	}
                    	                	else{
                    	                	return row.custom_entry_date;
                    	                	}
                    	                }},

					               // { "mDataProp": "scheme_type" }, //based on the scheme type to showed payable & sch type HH//
					               
					                       
                            { "mDataProp": function ( row, type, val, meta ){
                            min_max_weight="Min "+row.min_weight+" Max "+row.max_weight+" g/month";
                            
                              if(row.flexible_sch_type == '0' && row.scheme_types=='0')
                            
                              {
                            
                                return (row.scheme_types=='0'? "Amount" :"AmtToWgt");
                            
                            }
                            
                            else if(row.scheme_types == '1' && row.flexible_sch_type == '0'){
                                
                                return (row.scheme_types=='1' && (row.min_weight != row.max_weight)? "Flexible Weight" :"Fixed Weight");
                              
                            }
                            
                             else if(row.scheme_types == '2' && row.flexible_sch_type == '0'){
                                
                                return (row.scheme_types=='2'? "AmtToWgt" :"Amount");
                              
                            }
                            
                            else if(row.scheme_types == '3')
                            
                            {
                            
                            return (row.flexible_sch_type=='1'?"Flx Amount":(row.flexible_sch_type=='2'? "Flx AmtToWgt[Amt]":(row.flexible_sch_type=='3'? "Flx AmtToWgt[Wgt]":"Flx Wgt [Wgt]")));
                            
                            }
                           
                            else{
                                
                               return (row.scheme_types=='1' && (row.min_weight == row.max_weight) ?"Fixed Weight":"Flexible Weight");
                            }
                            
                            }
                            
                                           },

								
					                
                            { "mDataProp": function ( row, type, val, meta ){
                               amount=row.currency_symbol+" "+row.amount;
                                min_max_amount=row.currency_symbol+" Min "+row.min_amount+" Max "+row.max_amount;
                               
                                
                             weight= row.min_weight+" g/month";
                            min_max_weight="Min "+row.min_weight+" Max "+row.max_weight+" g/month";
                            
                                
                                  if(row.flexible_sch_type == '0' && row.scheme_types=='0')
                            
                              {
                            
                                return (row.scheme_types=='0'? amount :amount);
                            
                            }
                            
                            else if(row.scheme_types == '1' && row.flexible_sch_type == '0'){
                                
                               return (row.scheme_types=='1' && (row.min_weight == row.max_weight) ? weight: min_max_weight);
                            }
                            
                            else if(row.scheme_types == '2' && row.flexible_sch_type == '0'){
                                
                               return (row.scheme_types=='2' ? amount: min_max_amount);
                            }
                            
                            else if(row.scheme_types == '3')
                            
                            {
                            
                            return (row.flexible_sch_type=='1'?min_max_amount:(row.flexible_sch_type=='2'? min_max_amount:(row.flexible_sch_type=='3'? min_max_weight:min_max_weight)));
                            
                            }
                           
                            else{
                                
                               return (row.scheme_types=='1' && (row.min_weight == row.max_weight) ?weight: min_max_weight);
                            }
                            
                            }
                                           },
                            
                            
                            
                             /*if(row.scheme_types == '0')
                            
                              {
                            
                                           	return amount;
                            
                            }
                            
                            else if(row.scheme_types == '1')
                            
                            {
                            
                            return weight;
                            
                            }
                            
                            else if(row.scheme_types == '3')
                            
                            {
                            
                            return amount;
                            
                            }
                            else if(row.scheme_types=='2')
                            {
                            return amount;
                            }
                            else(row.scheme_types=='')
                            
                            }*/
                            
                                           
                                    { "mDataProp":"paid_installments"},
                                    
                                     { "mDataProp": function ( row, type, val, meta ) {

					                	

					                	return (row.added_by=='0'?"Web":(row.added_by=='1'?"Admin":(row.added_by=='5'?"Offline":"Mobile")));



					                	}},
					                
					                  { "mDataProp": "pan_no" },
					                
					                
					                
					                { "mDataProp":"gift_article"},
					                

									{ "mDataProp": function ( row, type, val, meta ){

					                	    active_url =base_url+"index.php/account/status/"+(row.active=='Active'?0:1)+"/"+row.id_scheme_account; 

					                		return "<a href='"+active_url+"'><i class='fa "+(row.active=='Active'?'fa-check':'fa-remove')+"' style='color:"+(row.active=='Active'?'green':'red')+"'></i></a>"

					                	}

					                },

					               

					                { "mDataProp": function ( row, type, val, meta ) {

					                	 id= row.id_scheme_account;
										 
										 close_url='';

					                	 edit_url=(access.edit=='1' ? base_url+'index.php/account/edit/'+id : '#' );

										 close_url=(close_acc=='1'&& row.paid_installments>=1? base_url+'index.php/account/close/scheme/'+id :'#' );

										 delete_url=(access.delete=='1' ? base_url+'index.php/account/delete/'+id : '#' );

					                	 delete_confirm= (access.delete=='1' ?'#confirm-delete':'');

					                	 action_content='<div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button><ul class="dropdown-menu">'+

											
											'<li><a href="'+edit_url+'" class="btn-edit"><i class="fa fa-edit" ></i> Edit</a></li>'+(access.allow_acc_closing==1 ? '<li><a href="'+close_url+'" class="btn-edit"><i class="fa fa-close" ></i> Close</a></li>' :'')+

											

											'<li><a href="#" class="btn-del" data-href="'+delete_url+'" data-toggle="modal" data-target="'+delete_confirm+'"  ><i class="fa fa-trash"></i> Delete</a></li>';

													  

					                	return action_content;

					                	}

					               

					            }] 



				            });			  	 	

					  	 }
						 
					 else{

					  	oTable = $('#sch_acc_list').dataTable({

				                "bDestroy": true,

				                "bInfo": true,

				                "bFilter": true,

				                "bSort": true,

				                "order": [[ 1, "desc" ]],
								
								'columnDefs': [{
										 'targets': 0,
										 'searchable':false,
										 'orderable':false,
										 "bSort": true,
										 'className': 'dt-body-center',
									  }],
                                 "dom": 'lBfrtip',
           			              "buttons" : ['excel','print'],
						                "tableTools": { "buttons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },

				                "aaData": account,
				                "aoColumns": [								
									{ "mDataProp": function ( row, type, val, meta ) {
										if((row.scheme_acc_number=='Not Allocated' && row.paid_installments==1 && row.schemeacc_no_set==1)){ 
										
					                	 return '<input type="checkbox" id="select_ids_'+row.id_scheme_account+'" class="select_ids"  value="'+row.id_scheme_account+'">';	
					                	 }else{
											 
											return '<input type="hidden" id="select_ids_'+row.id_scheme_account+'" class="select_ids" disabled="true" value="'+row.id_scheme_account+'">';	 
										 }
					                	}
					                },						
								
					                { "mDataProp": function ( row, type, val, meta ) {
					                	var url = base_url+'index.php/reports/payment/account/'+row.id_scheme_account;
					                	action = '<a href="'+url+'" target="_blank">'+row.id_scheme_account+'</a>';
					                	return action;
					                	}
					                },
					                { "mDataProp": "id_customer" },
					                { "mDataProp": "name" },

					                { "mDataProp": "mobile" },

					                { "mDataProp": "account_name" },
									
									 { "mDataProp": "code" },
									
									

									
									{ "mDataProp": function ( row, type, val, meta ){
										
										if((row.scheme_acc_number=='Not Allocated' && row.paid_installments==1 && row.schemeacc_no_set==1)){ 
											
					                	 return '<input  type="number"  id="id_schemeaccount" class="schemeaccount"  disabled="true" value="">';	
										 }else
											 
										if(row.has_lucky_draw==1){
					                	return row.group_code+' '+row.scheme_acc_number;
					                	}
					                	else{
					                		return row.code+' '+row.scheme_acc_number;
					                	}
										 } 
									   
					                },

					                { "mDataProp": "is_new" },

					                  { "mDataProp": function ( row, type, val, meta ){
                    	                	if(row.edit_custom_entry_date==0){
                    	                	return row.start_date;
                    	                	}
                    	                	else{
                    	                	return row.custom_entry_date;
                    	                	}
                    	                }},

					               // { "mDataProp": "scheme_type" },
					               
					               
					               
					               { "mDataProp": function ( row, type, val, meta ){
                            min_max_weight="Min "+row.min_weight+" Max "+row.max_weight+" g/month";
                            
                              if(row.flexible_sch_type == '0' && row.scheme_types=='0')
                            
                              {
                            
                                return (row.scheme_types=='0'? "Amount" :"AmtToWgt");
                            
                            }
                            
                           else if(row.scheme_types == '1' && row.flexible_sch_type == '0'){
                                
                                return (row.scheme_types=='1' && (row.min_weight != row.max_weight)? "Flexible Weight" :"Fixed Weight");
                              
                            }
                            
                             else if(row.scheme_types == '2' && row.flexible_sch_type == '0'){
                                
                                return (row.scheme_types=='2'? "AmtToWgt" :"Amount");
                              
                            }
                            
                            else if(row.scheme_types == '3')
                            
                            {
                            
                            return (row.flexible_sch_type=='1'?"Flx Amount":(row.flexible_sch_type=='2'? "Flx AmtToWgt[Amt]":(row.flexible_sch_type=='3'? "Flx AmtToWgt[Wgt]":"Flx Wgt [Wgt]")));
                            
                            }
                           
                            else{
                                
                               return (row.scheme_types=='1' && (row.min_weight == row.max_weight) ?"Fixed Weight":"Flexible Weight");
                            }
                            
                            }
                            
                                           },
					               
					                
                            { "mDataProp": function ( row, type, val, meta ){
                               amount=row.currency_symbol+" "+row.amount;
                                min_max_amount=row.currency_symbol+" Min "+row.min_amount+" Max "+row.max_amount;
                               
                                
                             weight= row.min_weight+" g/month";
                            min_max_weight="Min "+row.min_weight+" Max "+row.max_weight+" g/month";
                            
                                
                                  if(row.flexible_sch_type == '0')
                            
                              {
                            
                                return (row.scheme_types=='0'? amount :amount);
                            
                            }
                            
                            else if(row.scheme_types == '1' && row.flexible_sch_type == '0'){
                                
                               return (row.scheme_types=='1' && (row.min_weight == row.max_weight) ? weight: min_max_weight);
                            }
                             
                            else if(row.scheme_types == '2' && row.flexible_sch_type == '0'){
                                
                               return (row.scheme_types=='2' ? amount: min_max_amount);
                            }
                            
                            else if(row.scheme_types == '3')
                            
                            {
                            
                            return (row.flexible_sch_type=='1'?min_max_amount:(row.flexible_sch_type=='2'? min_max_amount:(row.flexible_sch_type=='3'? min_max_weight:min_max_weight)));
                            
                            }
                           
                            else{
                                
                               return (row.scheme_types=='1' && (row.min_weight == row.max_weight) ?weight: min_max_weight);
                            }
                            
                            }
                                           },
                            
                            
                             /*if(row.scheme_types == '0')
                            
                              {
                            
                                           	return amount;
                            
                            }
                            
                            else if(row.scheme_types == '1')
                            
                            {
                            
                            return weight;
                            
                            }
                            
                            else if(row.scheme_types == '3')
                            
                            {
                            
                            return amount;
                            
                            }
                            else if(row.scheme_types=='2')
                            {
                            return amount;
                            }
                            else(row.scheme_types=='')
                            
                            }*/
                            
                                           
                                    { "mDataProp":"paid_installments"},
                                    
                                    
                                    { "mDataProp": function ( row, type, val, meta ) {

					                	

					                	return (row.added_by=='0'?"Web":(row.added_by=='1'?"Admin":(row.added_by=='5'?"Offline":"Mobile")));



					                	}},
					                
					                  { "mDataProp": "pan_no" },
					                
					                
					                
					                { "mDataProp":"gift_article"},
					                

									{ "mDataProp": function ( row, type, val, meta ){

					                	    active_url =base_url+"index.php/account/status/"+(row.active=='Active'?0:1)+"/"+row.id_scheme_account; 

					                		return "<a href='"+active_url+"'><i class='fa "+(row.active=='Active'?'fa-check':'fa-remove')+"' style='color:"+(row.active=='Active'?'green':'red')+"'></i></a>"

					                	}

					                },
 
					                

									

					                { "mDataProp": function ( row, type, val, meta ) {

					                	 id= row.id_scheme_account;
										 
										  close_url='';

					                	 edit_url=(access.edit=='1' ? base_url+'index.php/account/edit/'+id : '#' );

										 close_url=(close_acc=='1'&& row.paid_installments>=1? base_url+'index.php/account/close/scheme/'+id :'#' );

										 delete_url=(access.delete=='1' ? base_url+'index.php/account/delete/'+id : '#' );

					                	 delete_confirm= (access.delete=='1' ?'#confirm-delete':'');

					                	 action_content='<div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button><ul class="dropdown-menu">'+

											'<li><a href="'+edit_url+'" class="btn-edit"><i class="fa fa-edit" ></i> Edit</a></li>'+

											
											'<li><a href="'+edit_url+'" class="btn-edit"><i class="fa fa-edit" ></i> Edit</a></li>'+(access.allow_acc_closing==1 ? '<li><a href="'+close_url+'" class="btn-edit"><i class="fa fa-close" ></i> Close</a></li>' :'')+

											'<li><a href="#" class="btn-del" data-href="'+delete_url+'" data-toggle="modal" data-target="'+delete_confirm+'"  ><i class="fa fa-trash"></i> Delete</a></li>';

													  

					                	return action_content;

					                	}

					               

					            }] 



				            });			  	 	

					  	 } 
					 }	

}


// scheme account number  manual
  var selectdatas =[];
 
$(document).on('click', '#select_aldata', function(e){
	
	 if($(this).prop("checked") == true){
		 
                $("tbody tr td input[type='checkbox']").prop('checked',true);
				$(".schemeaccount").attr('disabled', false);
            }
            else if($(this).prop("checked") == false)
			{
               
				$(".schemeaccount").val('');
				$(".schemeaccount").attr('disabled', true);
				$("tbody tr td input[type='checkbox']").prop('checked', false);
            }
	
 
});

$(document).on('click', '.select_ids', function(e){
	
 $("#sch_acc_list tbody tr").each(function(index, value) 
	{
			 if(!$(value).find(".select_ids").is(":checked"))
			 { 
				$(value).find(".schemeaccount").empty();			
				$(value).find(".schemeaccount").attr('disabled', true);
				$(value).find(".schemeaccount").val('');
			}
			else if($(value).find(".select_ids").is(":checked"))
			 { 
				$(value).find(".schemeaccount").attr('disabled', false);
			}
		

      });
});

 var selected = [];
 
$(document).on('click', '.conform_sch', function(e){
	
	
   $("#sch_acc_list tbody tr").each(function(index, value) 
	{
			 if(!$(value).find(".select_ids").is(":checked"))
			 { 
				$(value).find(".schemeaccount").empty();			
				$(value).find(".schemeaccount").attr('disabled', true);
			 }
		    else if(($(value).find(".select_ids").is(":checked") && $(value).find(".schemeaccount").val()!=''
			)){
				$("#conform_save").attr('disabled', true);
				  $(value).find(".schemeaccount").attr('disabled', false);
				  
				   var id_scheme_account=$(value).find(".select_ids").val();
				   var scheme_acc_number=$(value).find(".schemeaccount").val();
				   
				   var data = {'id_scheme_account':id_scheme_account, 'scheme_acc_number':scheme_acc_number}; 
				  // var sech = JSON.stringify(data);
				selected.push(data);	
					
			}
		else{
		  
		  msg='<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong>Select to proceed</div>';
				
				$("div.overlay").css("display", "none"); 
				        
				        //stop the form from submitting
				         $('#error-msg').html(msg);
		return false;
		  
	  }
		

      });
	  
	  if(selected.length>0){
	  
		$("div.overlay").css("display", "block"); 

		$.ajax({

			  url:base_url+ "index.php/schemeaccount/update",

			  data:{'selected':selected},

			 dataType:"JSON",

			 type:"POST",

			 success:function(data){

			   			 $("div.overlay").css("display", "none"); 
						  location.reload(true);

					  },

					  error:function(error)  

					  {

						 $("div.overlay").css("display", "none"); 

					  }	 

			      });
	  }
	   else{
		  
		  msg='<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong>Select to proceed</div>';
				
				$("div.overlay").css("display", "none"); 
				        
				        //stop the form from submitting
				         $('#error-msg').html(msg);
		return false;
		  
	  } 
	  
	  
	 });








//closed scheme account list 

function get_closed_acc_list(from_date="",to_date="",id_branch="")

{

	//$('body').addClass("sidebar-collapse");

	my_Date = new Date();

		 $("div.overlay").css("display", "block"); 

	$.ajax({

			  url:base_url+ "index.php/account/get/ajax_closed_acc_list?nocache=" + my_Date.getUTCSeconds(),

			 data: (from_date !='' && to_date !=''? {'from_date':from_date,'to_date':to_date,'id_branch':id_branch}: ''),

			 dataType:"JSON",

			 type:"POST",

			 success:function(data){

			   			set_closed_acc_list(data);

			   				 $("div.overlay").css("display", "none"); 

					  },

					  error:function(error)  

					  {

						 $("div.overlay").css("display", "none"); 

					  }	 

			      });

}



/*-- Coded by ARVK --*/



function set_closed_acc_list(data)

{

	 var account 	= data.data;

	 var access		= data.access;	

	 var close_acc	= data.close_acc;

	

	 $('#total_closed_accounts').text(account.length);

	 var oTable = $('#closed_list').DataTable();

	     oTable.clear().draw();

			  	 if (account!= null && account.length > 0)

			  	  {  	

					  	oTable = $('#closed_list').dataTable({

				                "bDestroy": true,

				                "bInfo": true,

				                "bFilter": true,

				                "bSort": true,
				                
                                "dom": 'lBfrtip',
                                
           			            "buttons" : ['excel','print'],
           			            
           			          "tableTools": { "buttons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },

           			             
				                "aaData": account,

				                "order": [[ 0, "desc" ]],

				                "aoColumns": [{ "mDataProp": "id_scheme_account" },

					                { "mDataProp": "name" },

					                { "mDataProp": "mobile" },

					                { "mDataProp": "account_name" },

					                

									 { "mDataProp": function ( row, type, val, meta ){
					                	if(row.has_lucky_draw==1){
					                	return row.scheme_group_code+' '+row.scheme_acc_number;
					                	}
					                	else{
					                		return row.code+'  '+row.scheme_acc_number;
					                	}
					                }},

					                { "mDataProp": "code" },

					                { "mDataProp": "start_date" },

					               // { "mDataProp": "scheme_type" },

								//	{ "mDataProp": "amount" },
								
								
								{ "mDataProp": function ( row, type, val, meta ){
                            
                            min_max_weight="Min "+row.min_weight+" Max "+row.max_weight+" g/month";
                              if(row.flexible_sch_type == '0' && row.sch_typ=='0')
                            
                              {
                            
                                return (row.sch_typ=='0'? "Amount" :"AmtToWgt");
                            
                            }
                            
                            else if(row.sch_typ == '1' && row.flexible_sch_type == '0'){
                                
                                return (row.sch_typ=='1' && (row.min_weight != row.max_weight)? "Flexible Weight" :"Fixed Weight");
                              
                            }
                              
                            
                            else if(row.sch_typ == '2' && row.flexible_sch_type == '0'){
                                
                                return (row.sch_typ=='2'? "AmtToWgt" :"Amount");
                              
                            }
                            
                         
                            else if(row.sch_typ == '3')
                            
                            {
                            
                            return (row.flexible_sch_type=='1'?"Flx Amount":(row.flexible_sch_type=='2'? "Flx AmtToWgt[Amt]":(row.flexible_sch_type=='3'? "Flx AmtToWgt[Wgt]":"Flx Wgt [Wgt]")));
                            
                            }
                           
                            else{
                                
                               return (row.sch_typ=='1' && (row.min_weight == row.max_weight) ?"Fixed Weight":"Flexible Weight");
                            }
                            
                            }
                            },

							{ "mDataProp": function ( row, type, val, meta ){
                               amounts=row.currency_symbol+" "+row.amounts;
                                min_max_amount=row.currency_symbol+" Min "+row.min_amount+" Max "+row.max_amount;
                               
                              //console.log(row.weight);  
                             weight= row.min_weight+" g/month";
                            min_max_weight="Min "+row.min_weight+" Max "+row.max_weight+" g/month";
                            
                                
                        if(row.flexible_sch_type == '0' && row.sch_typ=='0')
                            
                         {
                            
                                return (row.sch_typ=='0'? amounts :amounts);
                            
                            }
                            
                            else if(row.sch_typ == '1' && row.flexible_sch_type == '0'){
                                
                               return (row.sch_typ=='1' && (row.min_weight == row.max_weight) ? weight: min_max_weight);
                            }
                            
                             
                            
                            else if(row.sch_typ == '2' && row.flexible_sch_type == '0'){
                                
                               return (row.sch_typ=='2' ? amounts: min_max_amount);
                            }
                            
                            else if(row.sch_typ == '3')
                            
                            {
                            
                            return (row.flexible_sch_type=='1'?min_max_amount:(row.flexible_sch_type=='2'? min_max_amount:(row.flexible_sch_type=='3'? min_max_weight:min_max_weight)));
                            
                            }
                           
                            else{
                                
                               return (row.sch_typ=='1' && (row.min_weight == row.max_weight) ?weight: min_max_weight);
                            }
                              
                            
                            }
                                           },
								
								

									//{ "mDataProp": "closing_balance" },
									
									 { "mDataProp": function ( row, type, val, meta ){
					                	if(row.sch_typ=='1' || row.flexible_sch_type=='3' ||  row.flexible_sch_type=='4'){
					                	return row.closing_balance+' '+'g';
					                	}
					                	else{
					                		return row.closing_balance;
					                	}
					                }},

									/*{ "mDataProp": function ( row, type, val, meta ){

										 cls_amt= row.closing_balance;

										 cls_wt= row.closing_weight+" g";

					                	   return (row.scheme_type == 'Amount' ? cls_amt :cls_wt);

					                	   }

					                },*/
                                    { "mDataProp": "employee_closed" },
					                { "mDataProp": "closing_date" },

					                { "mDataProp": function ( row, type, val, meta ) {

					                	 id= row.id_scheme_account;

					                	 

										 close_url=(close_acc=='1'? base_url+'index.php/account/revert/'+id :'#' );
										 
										  printbtn='';
										  
										  
										  	 print_url = base_url+'index.php/account/close/scheme_history/'+id;
	                                        printbtn='<li><a href="'+print_url+'" target="_blank" class="btn-print"><i class="fa fa-print" ></i> Print</a></li>';

					                	 revert_confirm= ('#confirm-revert');

					                	     

					                	 action_content='<div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button><ul class="dropdown-menu">'+

					                	 

											'<li><a href="#" class="closed_ac_detail" data-href="#" data-toggle="modal" data-id="'+id+'" data-target="#closed_acc_detail"><i class="fa fa-list-alt"></i> Details</a></li>'+

											

											'<li><a href="#" class="btn-del confirm-revert" data-href="'+close_url+'" data-toggle="modal" data-target="'+revert_confirm+'"  ><i class="fa fa-backward"></i> Revert</a></li>'+
											
											'<li><a href="'+print_url+'" target="_blank" class="btn-edit"><i class="fa fa-print" ></i> Print</a></li>';

													  

					                	return action_content;

					                	}

					               

					            }] 

				            });			  	 	

					  	 }	

}



function close_acc_otp()

{

					//console.log($("input[name='account[closed_by]']:checked").val());

					 $("div.overlay").css("display", "block");

					if($("input[name='account[closed_by]']:checked").val()==0)

					{

						var mobile = $.trim($("#mobile").val());

						var email = $.trim($("#email").val());
						
						var id_customer = $.trim($("#id_customer").val());

						var name = $("#firstname").val();

					}

					else

					{

						var mobile = $.trim($("#nominee_mobile").val());

						var email = $.trim($("#email").val());
						
						var id_customer = $.trim($("#id_customer").val());

						var name = $("#nominee_name").val();

					}

					

						var otp={

							id_sch_acc:$("input[name='account[id_scheme_account]']").val(),

							id_emp:$("input[name='account[employee_closed]']").val(),

							send_resend:($("#send_otp").val()=='Send OTP'?'0':'1')

							

						}

				  		

				  		console.log(otp);

				  		

					$.ajax({



					   url:base_url+"index.php/account/close/otp/"+mobile+"/"+id_customer+"/"+name,

					   type : "POST",

					   data : otp,

					   dataType:'json',

					   success : function(result) {	



						  if(result == 1)



						  {		

						  		$('#otp_status').fadeIn();

						  		$("#otp_status").text("OTP Sent Successfully, Kindly verify it by entering in the above Text box.");

						  		$("#otp_status").css("color", 'green');

						  		$("div.overlay").css("display", "none");

						  		$('#otp_status').delay(10000).fadeOut(500);

								

						  }

					   },



					   error : function(error){



						   $("div.overlay").css("display", "none");



						   console.log(error);



					   }



					});

}



function verify_close_acc_otp()

{

					$("div.overlay").css("display", "block");

					

					if($("#otp").val().length==6)

					{

						

						var id_sch_acc = $("input[name='account[id_scheme_account]']").val();

						var otp_entered = $("#otp").val();

						

						//console.log(id_sch_acc);

						$.ajax({



						   	url:base_url+"index.php/account/fetch/otp/"+id_sch_acc+"/"+otp_entered,

						   	type : "POST",

						   	dataType:'json',

					   		success : function(result) {	



								if(result == 1)

								{		

							  		//console.log('success');

							  		$("#close_save").prop('disabled', false);
							  		$("#close_save_print").prop('disabled', false);

							  		$("#otp").prop('disabled','disabled');

							  		$("input[name='account[closedBy]']").val($("input[name='account[closed_by]']:checked").val());

							  		$("input[name='account[closed_by]']").prop('disabled','disabled');

							  		$("#send_otp").hide();

							  		$('#otp_status').fadeIn();

									$("#otp_status").text("OTP verified successfully, Kindly proceed with scheme closing.");

									$("#otp_status").css("color", 'green');

									$("div.overlay").css("display", "none");

                                     $("#close_actionBtns").css("display", "block");   // closed valide HH//
									//$('#otp_status').delay(10000).fadeOut(500);

							  	}

							  	else

							  	{

									$("#verify_otp").prop('disabled',false);

									$("#close_save").prop('disabled','disabled');
									
									$("#close_save_print").prop('disabled','disabled');

							  		$('#otp_status').fadeIn();

									$("#otp_status").text("Incorrect OTP, Kindly enter the correct one.");

									$("#otp_status").css("color", 'red');

									$("div.overlay").css("display", "none");

									$('#otp_status').delay(10000).fadeOut(500);

								}

						   	},



						   	error : function(error){



								$("div.overlay").css("display", "none");



								console.log(error);



						   }



						});

												

						

					}

					else

					{

						$("#verify_otp").prop('disabled',false);

						$('#otp_status').fadeIn();

						$("#otp_status").text("Kindly enter 6 digit OTP to verify.");

						$("#otp_status").css("color", 'red');

						$("div.overlay").css("display", "none");

						$('#otp_status').delay(5000).fadeOut(500);

						

						console.log('I\'m in else');

					}

					

					

}


 //branch_name
 
 
     
 $('#branch_select').select2().on("change", function(e) {       
         
           switch(ctrl_page[1])

			{

				case 'new':
				
					
					if(this.value!='')
					{  
						$("#id_branch").val(this.value)
						var from_date = $('#account_list1').text();
						var to_date  = $('#account_list2').text();
						var id=$(this).val();
						var id_customer=$("#id_customer").val();
						var id_metal=$('#id_metal').val();	
						get_scheme_acc_list(from_date,to_date,id,id_customer,id_metal);
					}
					 break;
					 
				case 'scheme_group':
				
					if(this.value!='')
					{  
						$("#id_branch").val(this.value);
					get_group_list(this.value);
					}
					 break;
					  
					 
					 case 'close':
				
					
					if(this.value!='')
					{  
						
						var from_date = $('#closed_list1').text();
						var to_date  = $('#closed_list2').text();
						var id=$(this).val();						
						get_closed_acc_list(from_date,to_date,id);
					}
					 break;


				case 'add':
					
				  if(this.value!='')
				  {
					 $("#id_branch").val(this.value);
					 var id=$(this).val();
					 //console.log(id);
					 get_schemes(id);
						 
				  }
					 break;	
			}
		
		  
   });
 
// Existing reg request functions 

function get_request_list(from_date="",to_date="",branch="",status="")
{
	my_Date = new Date();
	postData = (from_date !='' && to_date !='' ? {'from_date':from_date,'to_date':to_date,'status':status}: (branch != ''?  {'id_branch':branch,'status':status}:{'status':$('#filtered_status').val()}));
	 $("div.overlay").css("display", "block"); 
	$.ajax({
			  url:base_url+ "index.php/admin_manage/ajax_requests_list?nocache=" + my_Date.getUTCSeconds(),
			 data: (postData),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			 	$('#total_requests').text(data.requests.length);
			   			set_request_list(data);
			   			 $("div.overlay").css("display", "none"); 
					  },
					  error:function(error)  
					  {
						 $("div.overlay").css("display", "none"); 
					  }	 
		  });
}


function set_request_list(data)
{
	$('body').addClass("sidebar-collapse");
	 var accounts = data.requests;
	 var oTable = $('#scheme_reg_list').DataTable();
	 var schemesOptions ='';
	 var branchOptions ='';
	 var GroupOptions = '';
	  var pan_no='';
	  if(data.requests!=''){
var getExisting_balance=data.requests[0]['getExisting_balance'];
 }
 else{
  var getExisting_balance='';
 }

	 $.each(data.branches, function (key, item) {
	 	branchOptions += '<option value="'+item.id_branch+'">'+item.name+'</option>';	   		
	 });
	 $.each(data.schemes, function (key, item) {
	 	schemesOptions += '<option value="'+item.id_scheme+'">'+item.code+'</option>';	   		
	 });
	 $.each(data.groups, function (key, item) {
        GroupOptions += '<option value="'+item.group_code+'">'+item.group_code+'</option>';	  
     });
	     oTable.clear().draw();
			  	if (accounts!= null && accounts.length > 0 && getExisting_balance==1)
			  	  {  	
					 	oTable = $('#scheme_reg_list').dataTable({
				                "bDestroy": true,
				                "bInfo": true,
				                "bFilter": true,
				                "bSort": true,
				                "order": [[ 0, "desc" ]],
				                 "dom": 'lBfrtip',
           			             "buttons" : ['excel','print'],
						      "tableTools": { "buttons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },

				                "aaData": accounts,
				                "aoColumns": [ { "mDataProp": function ( row, type, val, meta ){ 
		                	chekbox='<input type="checkbox" class="id_reg_request" name="reg_request_id[]" value="'+row.id_reg_request+'"/> ' 
		                	if(row.status == 0){
		                	return chekbox+" "+row.id_reg_request;
		                	}
		                	else{
		                	    return row.id_reg_request;
		                	}
		                }},
					                { "mDataProp": "name" },
					                { "mDataProp": "mobile" },
					                { "mDataProp": function ( row, type, val,meta ) { 	
					                    element = '<select class="id_scheme form-control" name="id_scheme">'+schemesOptions+'</select>';
					                	return element+'<input type="hidden" value="'+row.group_code+'"/>';
					                	
					                }},
					                
                                    { "mDataProp": function ( row, type, val,meta ) { 
                                                       element = '<select class="scheme_group_code form-control" name="scheme_group_code">'+GroupOptions+'</select>';
                                                   	return element ;
                                                   	
                                                   }},
                                    
					                { "mDataProp": function ( row, type, val,meta ) { 	
					                   return '<input class="chit_no form-control" name="scheme_acc_number" value="'+row.scheme_acc_number+'" type="text" />';
					                	
					                }},

					                /* { "mDataProp": function ( row, type, val,meta ) { 	
					                   return '<input class="paid_installments form-control" name="paid_installments" value="'+row.paid_installments+'" type="text" />';
					                	
					                }},

					                 { "mDataProp": function ( row, type, val,meta ) { 	
					                   return '<input class="balance_amount form-control" name="balance_amount" value="'+row.balance_amount+'" type="text" />';
					                	
					                }},

					                 { "mDataProp": function ( row, type, val,meta ) { 	
					                   return '<input class="last_paid_date form-control" name="last_paid_date" id="last_paid_date" value="'+row.last_paid_date+'"  />';
					                	
					                }},

					                 { "mDataProp": function ( row, type, val,meta ) { 	
					                   return '<input class="balance_weight form-control" name="balance_weight" value="'+row.balance_weight+'" type="text" />';
					                	
					                }},

					                 { "mDataProp": function ( row, type, val,meta ) { 	
					                   return '<input class="last_paid_weight form-control" name="last_paid_weight" value="'+row.last_paid_weight+'" type="text" />';
					                	
					                }},

					                 { "mDataProp": function ( row, type, val,meta ) { 	
					                   return '<input class="last_paid_chances form-control" name="last_paid_chances" value="'+row.last_paid_chances+'" type="text" />';
					                	
					                }},*/
					                
					                { "mDataProp": function ( row, type, val,meta ) { 	
					                   paid_install= '<input  class="paid_installments form-control"  name="paid_installments" value="'+row.paid_installments+'" type="number" />';
					                	if(row.scheme_type == 0 || row.scheme_type == 1){
		                	return paid_install;
		                	}
		                	else{
		                	    return 
		                	}
					                }},
					                
					                { "mDataProp": function ( row, type, val,meta ) { 
					                	 is_opening= '<input type="checkbox" id="is_opening" class="is_opening" name="is_opening[]" '+(row.is_opening==1 ? "checked" :"")+' />'; 
					                
					                    if(row.scheme_type == 0 || row.scheme_type == 1){
		                	return is_opening;
		                	}
		                	else{
		                	    return 
		                	}
					                    
					                }},
					                
					                { "mDataProp": function ( row, type, val,meta ) { 	
					                   balance_amt= '<input class="balance_amount form-control" name="balance_amount" value="'+row.balance_amount+'" type="number" />';
					                  if(row.scheme_type == 0 || row.scheme_type == 1){
		                	return balance_amt;
		                	}
		                	else{
		                	    return 
		                	}
					                }},
					                
					                { "mDataProp": function ( row, type, val,meta ) { 	
					                   balance_wgt= '<input class="balance_weight form-control" name="balance_weight" value="'+row.balance_weight+'" type="number" />';
					                	if(row.scheme_type == 1){
		                	return balance_wgt;
		                	}
		                	else{
		                	    return ' - '
		                	}
					                }},
					                
					                { "mDataProp": function ( row, type, val,meta ) { 	
					                   paid_wgt= '<input class="last_paid_weight form-control" name="last_paid_weight" value="'+row.last_paid_weight+'" type="number" />';
					                	if(row.scheme_type == 1){
		                	return paid_wgt;
		                	}
		                	else{
		                	    return ' - '
		                	}
					                }},
					                
					                { "mDataProp": function ( row, type, val,meta ) { 	
					                   paid_chance= '<input class="last_paid_chances form-control" name="last_paid_chances" value="'+row.last_paid_chances+'" type="number" />';
					                	if(row.scheme_type == 0 || row.scheme_type == 1){
		                	return paid_chance;
		                	}
		                	else{
		                	    return 
		                	}
					                }},
					                
					                { "mDataProp": function ( row, type, val,meta ) { 	
					                   paid_date= '<input type="text" class="last_paid_date form-control date" name="last_paid_date" id="last_paid_date"  data-inputmask="alias: yyyy-mm-dd " data-mask  data-date-format="yyyy-mm-dd " value="'+row.last_paid_date+'"/>';
					                    if(row.scheme_type == 0 || row.scheme_type == 1){
		                	return paid_date;
		                	}
		                	else{
		                	    return 
		                	}      
					                }},
					                
					                { "mDataProp": function ( row, type, val,meta ) { 	
					                   return '<input class="firstPayment_amt form-control" name="firstPayment_amt" value="'+(row.firstPayment_amt ==0.00 ? '':row.firstPayment_amt)+'" type="text" required="'+(row.scheme_type==3 ? "true":'')+'" /><input class="scheme_type form-control" name="scheme_type" value="'+(row.scheme_type)+'" type="hidden" /><input class="firstPayamt_payable form-control" name="firstPayamt_payable" value="'+(row.firstPayamt_payable)+'" type="hidden" />';
					                	
					                }},
					                
					                { "mDataProp": function ( row, type, val,meta ) { 	
					                   return '<input class="ac_name form-control" name="ac_name" value="'+row.ac_name+'" type="text" />';
					                	
					                }}, 
					                { "mDataProp": function ( row, type, val,meta ) { 	
					                    element = '<select class="id_branch form-control" name="id_branch">'+branchOptions+'</select>';
					                	return element+'<input type="hidden" class="id_customer" value="'+row.id_customer+'"/><input type="hidden" class="pan_no" value="'+row.pan_no+'"/><input type="hidden" class="mobile" value="'+row.mobile+'"/><input type="hidden" class="email" value="'+row.email+'"/><input type="hidden" class="added_by" value="'+row.added_by+'"/>';
					                	
					                }},
					                { "mDataProp": "date_add" },
					                { "mDataProp": function ( row, type, val, meta ) { 
					                  return (row.status == 0? 'Processing' : (row.status == 1?'Approved':'Rejected'));
					                }},
					                 { "mDataProp": function ( row, type, val, meta ) { 
					                	return '<input class="form-control remark" value="'+row.remark+'" name="remark" type="text" />';
					                }
					                }],
					                "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
										 $('td .id_branch', nRow).val(aData['id_branch']);
										 $('td .id_scheme', nRow).val(aData['id_scheme']);
										 $('td .scheme_group_code', nRow).val(aData['scheme_group_code']);
									}

				            });				  	 	
					  	 }
						else
					  	 {
					  	 	Table = $('#scheme_reg_list').dataTable({
				                "bDestroy": true,
				                "bInfo": true,
				                "bFilter": true,
				                "bSort": true,
				                "order": [[ 0, "desc" ]],
				                 "dom": 'lBfrtip',
           			             "buttons" : ['excel','print'],
						      "tableTools": { "buttons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },

				                "aaData": accounts,
				                "aoColumns": [ { "mDataProp": function ( row, type, val, meta ){ 
		                	chekbox='<input type="checkbox" class="id_reg_request" name="reg_request_id[]" value="'+row.id_reg_request+'"/> ' 
		                	if(row.status == 0){
		                	return chekbox+" "+row.id_reg_request;
		                	}
		                	else{
		                	    return row.id_reg_request;
		                	}
		                }},
					                { "mDataProp": "name" },
					                { "mDataProp": "mobile" },
					                { "mDataProp": function ( row, type, val,meta ) { 	
					                    element = '<select class="id_scheme form-control" name="id_scheme">'+schemesOptions+'</select>';
					                	return element+'<input type="hidden" value="'+row.group_code+'"/>';
					                	
					                }},
					                
                                    { "mDataProp": function ( row, type, val,meta ) { 
                                                       element = '<select class="scheme_group_code form-control" name="scheme_group_code">'+GroupOptions+'</select>';
                                                   	return element ;
                                                   	
                                                   }},
                                    
					                { "mDataProp": function ( row, type, val,meta ) { 	
					                   return '<input class="chit_no form-control" name="scheme_acc_number" value="'+row.scheme_acc_number+'" type="text" />';
					                	
					                }},

					                 
					                { "mDataProp": function ( row, type, val,meta ) { 	
					                   return '<input class="firstPayment_amt form-control" name="firstPayment_amt" value="'+(row.firstPayment_amt ==0.00 ? '':row.firstPayment_amt)+'" type="text" required="'+(row.scheme_type==3 ? "true":'')+'" /><input class="scheme_type form-control" name="scheme_type" value="'+(row.scheme_type)+'" type="hidden" /><input class="firstPayamt_payable form-control" name="firstPayamt_payable" value="'+(row.firstPayamt_payable)+'" type="hidden" />';
					                	
					                }},
					                
					                { "mDataProp": function ( row, type, val,meta ) { 	
					                   return '<input class="ac_name form-control" name="ac_name" value="'+row.ac_name+'" type="text" />';
					                	
					                }}, 
					                { "mDataProp": function ( row, type, val,meta ) { 	
					                    element = '<select class="id_branch form-control" name="id_branch">'+branchOptions+'</select>';
					                	return element+'<input type="hidden" class="id_customer" value="'+row.id_customer+'"/><input type="hidden" class="pan_no" value="'+row.pan_no+'"/><input type="hidden" class="mobile" value="'+row.mobile+'"/><input type="hidden" class="email" value="'+row.email+'"/><input type="hidden" class="added_by" value="'+row.added_by+'"/>';
					                	
					                }},
					                { "mDataProp": "date_add" },
					                { "mDataProp": function ( row, type, val, meta ) { 
					                  return (row.status == 0? 'Processing' : (row.status == 1?'Approved':'Rejected'));
					                }},
					                 { "mDataProp": function ( row, type, val, meta ) { 
					                	return '<input class="form-control remark" value="'+row.remark+'" name="remark" type="text" />';
					                }
					                }],
					                "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
										 $('td .id_branch', nRow).val(aData['id_branch']);
										 $('td .id_scheme', nRow).val(aData['id_scheme']);
										 $('td .scheme_group_code', nRow).val(aData['scheme_group_code']);
									}

				            });		
					  	 }	
					  	 
}
function update_request_status(status="",data="")
{
	
	my_Date = new Date();
	$("div.overlay").css("display", "block"); 
	$.ajax({
			 url:base_url+ "index.php/admin_manage/update_request?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),
			 data:  {'status':status,'req_data':data},
			 type:"POST",
			 async:false,
			 	  success:function(data){
			           location.reload(true);
			   			$("div.overlay").css("display", "none"); 
				  },
				  error:function(error)  
				  {

				  		console.log(error);
					 $("div.overlay").css("display", "none"); 
				  }	 
		  });
}

function initialize_branch_list(elementID)
{
	
    my_Date = new Date();
	$('.overlay').css('display','block');
	$.ajax({
	  type: 'GET',
	  url:  base_url+'index.php/settings/branches?nocache=' + my_Date.getUTCSeconds(),
	  dataType: 'json',
	   cache:false,
	  success: function(data) {
	      	 $.each(data.branches, function (key, item) {
						   		$('#sync_branch').append(
									$("<option></option>")
									.attr("value", item.id_branch)						  
									  .text(item.name )									  
								);	
						});						
						$("#sync_branch").select2({
						    placeholder: "Branch",
						    allowClear: true
						});
	  	
	  	      $.each(data.branches, function (key, item) {
				$('#'+elementID).append(
					$("<option></option>")
					  .attr("value", item.id_branch)
					  .text(item.name)
					  
				);
				
			});
			
			$("#"+elementID).select2({
			    placeholder: "Select branch",
			    allowClear: true
			});	
				$("#sel_branch").select2("val",'');
			//disable spinner
				$('.overlay').css('display','none');
	  	}
	  });	
}

//get_group_list //HH

function get_group_list(branch="",from_date="",to_date="")
{
	my_Date = new Date();
	postData = (from_date !='' && to_date !='' ? {'from_date':from_date,'to_date':to_date}: (branch != ''? {'id_branch':branch}: ''));
	 $("div.overlay").css("display", "block"); 
	$.ajax({
			  url:base_url+ "index.php/account/ajaxscheme_group/list?nocache=" + my_Date.getUTCSeconds(),
			 data: (postData),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){ 
					
						
			   			set_group_list(data);
			   			 $("div.overlay").css("display", "none"); 
					  },
					  error:function(error)  
					  {
						 $("div.overlay").css("display", "none"); 
					  }	 
		  });
}
 
// scheme group //  
$('#scheme_select').select2().on("change", function(e) 
{           if(this.value!='')
			{  
			
				$("#id_scheme").val((this).value);
			}
});


$('#branch_select').select2().on("change", function(e) 
{           if(this.value!='')
			{  
			
				$("#id_branch").val((this).value);
			}
});

 $('#metal_select').select2().on("change", function(e) { //metal filter in acc page- based on the multi commodity settings //HH
			if(this.value!='')
			{	
			    $("#id_metal").val($(this).val());
				var from_date = $('#account_list1').text();
						var to_date  = $('#account_list2').text();
						var id_customer=$('#id_customer').val();	
						var id_branch=$('#id_branch').val();
						var id_metal=$('#id_metal').val();	
						get_scheme_acc_list(from_date,to_date,id_branch,id_customer,id_metal);
			}
		});

function set_group_list(data)
{
	
	
	console.log(data);
	$('body').addClass("sidebar-collapse");	 
	 var accounts = data.requests;
	 var access = data.access;
	 console.log(accounts);
	 var oTable = $('#group_list').DataTable();
	     oTable.clear().draw();
			  	 if (accounts!= null && accounts.length > 0)
			  	  {  	
					  	oTable = $('#group_list').dataTable({
				                "bDestroy": true,
				                "bInfo": true,
				                "bFilter": true,
				                "bSort": true,
				                "order": [[ 0, "desc" ]],
				                "aaData": accounts,
				                "aoColumns": [ 
					                { "mDataProp": "id_scheme_group" },
					                { "mDataProp": "scheme_code" },
					                { "mDataProp": "branch_name" },
					                { "mDataProp": "group_code" },
					                { "mDataProp": "start_date" },
					                { "mDataProp": "end_date" },
					              
									{ "mDataProp": function ( row, type, val, meta ) {
					                	 id= row.id_scheme_group;
					                	 edit_url=(access.edit=='1' ? base_url+'index.php/account/scheme_group/edit/'+id : '#' );
										 delete_url=(access.delete=='1' ? base_url+'index.php/account/scheme_group/delete/'+id : '#' );

					                	 delete_confirm= (access.delete=='1' ?'#confirm-delete':'');

					                	 action_content='<div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button><ul class="dropdown-menu">'+

											'<li><a href="'+edit_url+'" class="btn-edit"><i class="fa fa-edit" ></i> Edit</a></li>'+

									

											'<li><a href="#" class="btn-del" data-href="'+delete_url+'" data-toggle="modal" data-target="'+delete_confirm+'"  ><i class="fa fa-trash"></i> Delete</a></li>';

													  

					                	return action_content;

									}}		
						         
						],				            });			  	 	
					  	
					  	 
             }
}

//get_group_list

function get_schemename()
{
	
	$(".overlay").css('display','block');
	$.ajax({
		type: 'GET',
		url: base_url+'index.php/get/schemename_list',
		dataType:'json',
		success:function(data){
		console.log(data);
		 //var scheme_val =  $('#id_scheme').val();
		 //console.log( $('#id_schemes').val());
		   $.each(data, function (key, item) {
			  
			   		$('#scheme_select').append(
						$("<option></option>")
						.attr("value", item.id_scheme)						  
						  .text(item.code )
						  
					);			   				
				
			});
			
			$("#scheme_select").select2({
			    placeholder: "Select Scheme name",
			    allowClear: true
			});
				//console.log($('#id_scheme').val());
			 //$("#scheme_select").select2("val",(scheme_val!='' && scheme_val>0?scheme_val:''));
			 console.log($('#id_scheme').val());

			 
			  $("#scheme_select").select2("val", ($('#id_scheme').val()!=null?$('#id_scheme').val():''));

			 $(".overlay").css("display", "none");	
		}
	});
}

function check_groupcode(group_code)
{ 
     
    $("div.overlay").css("display", "block");
    $.ajax({
     type: 'POST',
     data:{'group_code':group_code},
     url:  base_url+'index.php/account/check_group',
     dataType: 'json',
     success: function(avail) {
      // console.log(avail); 
      if(avail==1)
      {
      $('#group_code').val('');
      $("#group").prop('disabled', true);
    $('#group_code').attr('placeholder', 'group code already exists')
    }
    else
    {
        $("#group").prop('disabled',false);
    }
     $("div.overlay").css("display", "none"); 
    },
    error:function(error) {
    $("div.overlay").css("display", "none"); 
    }	 	
    });

}
$('#pan_no').on('change',function(){
			
			if($("#pan_no").val() != ""){
				var regexp = /^[a-zA-Z]{5}\d{4}[a-zA-Z]{1}$/;
				if(!regexp.test($("#pan_no").val()))
		    	{
		    		 $("#pan_no").val("");
		    		 alert("Not a valid PAN No.");
		    		 $("#pan_no").focus();
		    		 $isValidPan = false;	
		    	}
			}else{
				alert("Enter valid PAN No.");
				$isValidPan = false;
			}
			
		});



function get_scheme_acc(mobile)

	{
		
   
   
	my_Date = new Date();

// $("div.overlay").css("display", "block"); 

	

	$.ajax({

			  url:base_url+ "index.php/account/get/ajax_account?nocache=" + my_Date.getUTCSeconds(),

			  data:{'mobile':mobile},

			 dataType:"JSON",

			 type:"POST",

			 success:function(data){

			   			set_scheme_acc_list(data);
			   			$('body').addClass("sidebar-collapse");

			   			 $("div.overlay").css("display", "none"); 

					  },

					  error:function(error)  

					  {

						 $("div.overlay").css("display", "none"); 

					  }	 

			      });

}
//Get gift issue & prize details for each account wise //HH

 $('#submit_gift').click(function(event) {
    $(this).prop('disabled','disabled');
       var id_customer = $("#id_customer").val();
    	  if((($('#req_gift_issue_otp').val()==1) && ($('#id_type').val()==1))  || (($('#req_prize_issue_otp').val()==1) && ($('#id_type').val()==2)))
    	  {
    	  	$.ajax({
    		url:base_url+ "index.php/admin_manage/generate_giftotp?nocache=" + my_Date.getUTCSeconds(),
    		data :  {'id_customer':id_customer}, 	
    		 type : "POST",
    		dataType: 'json',
    		 success : function(data) 
    		 {
    		 	 if(data.result==3)
    		 	 {
    		  		$('.gift_otp_blk').css("display", "block"); 
    				$("div.overlay").css("display", "none"); 
    			
    		 	 }
    		 	
    		 }
    		});
    	  }
    	  else{
        	verify_gift_issued();
    	  } 
    });
    
/*$('#verify_otp').on('click',function(){
			
			$('#status1').fadeIn();
						   	$("#status1").text("Otp Verified  Successfully,");
                          	$("#verify_otp").val('');
						  		$("#status1").css("color", 'green');
                          	$('#status1').delay(5000).fadeOut(500);
                          		$("#verify_otp").attr("disabled", true);
		  verify_gift_issued();
	//	$("div.overlay").css("display", "none");		
    
});*/


	$('#resendotp').on('click',function(){
       var id_customer = $("#id_customer").val();
    	$.ajax({
    		url:base_url+ "index.php/admin_manage/resend_giftotp?nocache=" + my_Date.getUTCSeconds(),
    		data :  {'id_customer':id_customer}, 
    		type : "POST",
    		dataType: 'json',
    		success:function(data){
    			if(data.result==3)
    			{
    				$("div.overlay").css("display", "none"); 
    				$('#otp_status').fadeIn();

						  		$("#otp_status").text("OTP Again Sent Successfully, Kindly verify it by entering in the above Text box.");

						  		$("#otp_status").css("color", 'green');
						  		$(".otp_block").css("display", 'block');

						  		$("div.overlay").css("display", "none");

						  		$('#otp_status').delay(1000).fadeOut(200);
    			}
    		}
    	});
    }); 
				
    $('#otp').on('keyup',function(){
        if(this.value.length==6)
        {        
            $('#gift_verify_otp').prop('disabled',false);
        }
        else
        {
            $('#gift_verify_otp').prop('disabled',true);
            // alert('Please fill the 6 digit Otp');
        }
    })
    
    $('#gift_issued').on('keyup',function(){
          var gift_issued = $.trim($('#gift_issued').val());
        if(gift_issued.length != '')
        {        
            $('#submit_gift').prop('disabled',false);
        }
       
 })
    /*function update_giftotp(post_data)
	{	
    	var post_otp=$('#otp').val();
    	$.ajax({
        	url:base_url+ "index.php/admin_manage/update_giftotp",
        	data: {'otp':post_otp},
        	type:"POST",
        	dataType:"JSON",
        	success:function(data)
        	{
        		if(data.result==1)
        		{
        			verify_gift_issued(post_data,post_otp);
        		}
        		else
        		{
        			 $("#resendotp").attr("disabled", false);
        			 $("#gift_verify_otp").attr("disabled", false);
        			 $('#otp').val('');
        			alert(data.msg);
        		}
        	}
		});
	}*/

    function verify_gift_issued() 
    {
        //  $("div.overlay").css("display", "block");
        var id_sch_acc = $("#id_scheme_account").val();
        var issue_entered = $("#gift_issued").val();
        var id_type = $("#id_type").val();
        my_Date = new Date();
        
        $.ajax({
            url:base_url+ "index.php/admin_manage/gift_issue?nocache=" + my_Date.getUTCSeconds(),
            type : "POST",
            data:{'id_sch_acc':id_sch_acc,'issue_entered':issue_entered,'id_type':id_type},
            dataType:'json',
            success : function(result) {
                if(result == 1)
                {
                    $('#status').fadeIn();
                    $("#status").text("Your Gift Issued  Successfully,");
                    $("#gift_issued").val('');
                    $("#status").css("color", 'green');
                    $('#status').delay(13000).fadeOut(1000);
                    $("#gift_issued").css("display", 'none');
                    $("#gift_issued").prop("required", false);
                    $("#verify_issue").css("display", 'none');
                    $("div.overlay").css("display", "none");
                    gift_issued_list(ctrl_page[2]);
                    $('.gift_otp_blk').css("display", "none"); 
                    $('.close_actionBtns').css("display", "none"); 
                    
                }
            }, 
            error : function(error){
                $("div.overlay").css("display", "none");
                console.log(error); 
            }
        });
    
    }
	/*$('#calc_blc').on('click',function(){
        	$(".close_actionBtns").css("display", 'block');
        		$("#gift_issued").prop("required", true);
        		  $("#gift_issued").css("display", '');
        $("#verify_issue").css("display", '');
         $("#verify_issue").prop('disabled', false);
    });*/
    
    
   


 //gift issued list HH //
function gift_issued_list(id)
{
	
//	$("div.overlay").css("display", "block"); 
		var oTable = $('#gift_issued_lists').DataTable();
		$.ajax({
				  type: 'GET',
				  url:  base_url+"index.php/admin_manage/get_gift_issued_list?id_scheme_account="+id,
				 dataType: 'json',
				  success: function(data) {
				        oTable = $('#gift_issued_lists').dataTable({
				                "bDestroy": true,
				                "bInfo": true,
				                "bFilter": true,
				                "bSort": true,
				                "aaData":data,
				                "aoColumns": [
							                    { "mDataProp": "id_gift_issued" },
								                { "mDataProp": "type" },
							                    { "mDataProp": "gift_desc" },
								                { "mDataProp": "id_employee" },		
								                { "mDataProp":  "date_issued"}
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

   //gift issued list  //

function verify_gift_otp(post_otp){
    var post_otp=$('#otp').val();
    var id_customer = $("#id_customer").val();
    my_Date = new Date();
    $.ajax({
		url: base_url+"index.php/admin_manage/gift_verify_otp?nocache="+my_Date.getUTCSeconds(),
		data : {'id_customer':id_customer,'otp':post_otp}, 
		type : "POST",
		dataType: 'json',
		success:function(data){
		    //console.log(data);
			if(data.result == 1)
			{
				$('#otp_status').fadeIn();
				$("#otp_status").text("OTP verified successfully, And  .");
				$("#otp_status").css("color", 'green');
				$("div.overlay").css("display", "none");
				$('#otp_status').delay(5000).fadeOut(200);
				$("#otp").css("display", '');
				$("#otp").val('');
				verify_gift_issued();
			}
			else
			{
				$("#gift_verify_otp").prop('disabled',false);
				$('#otp_status').fadeIn();
				$("#otp_status").text("Incorrect OTP, Kindly enter the correct one Or Resend the OTP.");
				$("#otp_status").css("color", 'red');
				$("div.overlay").css("display", "none");
				$('#otp_status').delay(10000).fadeOut(500);
				$('.gift_resent_otp').css("display", "block");
			}
		}
	});
}

 //Existing data fields added in Existing a/c apprvl page - admin //HH

        $(document).on('change',".is_opening",function()
        {
            
        if($(this).is(":checked"))
        {
   
        $(this).closest('tr').find('.balance_amount').prop('disabled',false);
        $(this).closest('tr').find('.paid_installments').prop('disabled',false);
        $(this).closest('tr').find('.balance_weight').prop('disabled',false);
        $(this).closest('tr').find('.last_paid_weight').prop('disabled',false);
        $(this).closest('tr').find('.last_paid_chances').prop('disabled',false);
        $(this).closest('tr').find('.last_paid_date').prop('disabled',false);


        }
        else
        {
        $(this).closest('tr').find('.balance_amount').prop('disabled',true);
        $(this).closest('tr').find('.paid_installments').prop('disabled',true);
        $(this).closest('tr').find('.balance_weight').prop('disabled',true);
        $(this).closest('tr').find('.last_paid_weight').prop('disabled',true);
        $(this).closest('tr').find('.last_paid_chances').prop('disabled',true);
        $(this).closest('tr').find('.last_paid_date').prop('disabled',true);
        }

        });

        $(document).on('click',".last_paid_date",function()
        {
        $('.date').datepicker({ dateFormat: 'yyyy-mm-dd', })
        });

        //Existing data fields added in Existing a/c apprvl page - admin //

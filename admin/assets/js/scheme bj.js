var pathArray = window.location.pathname.split( 'php/' );
var ctrl_page = pathArray[1].split('/');
$(document).ready(function() {
    if(ctrl_page[0]=='scheme')
	 { 
		get_scheme_list();
	 }
	 $('#scheme_create').submit(function(e)  {
	     
	    
	    $('#submit').prop('disabled', true); // preclose interest deduction base on scheme chart //HH
       /* var postData = [];
        $('#chart_creation_tbl > tbody tr').each(function(idx, row){
        	curRow = $(this);
        	var data = [];
        	//var id_debit_settings = (isNaN(curRow.find('.id_debit_settings').val()) || curRow.find('.id_debit_settings').val() == '')  ? 0 : curRow.find('.id_debit_settings').val();
        	var installment_from = (curRow.find('.installment_from').val() == '')  ? 0 : curRow.find('.installment_from').val();
        	var installment_to = (curRow.find('.installment_to').val() == '')  ? 0 : curRow.find('.installment_to').val();
        	var deduction_type = (curRow.find('.deduction_type').val() == '')  ? 0 : curRow.find('.deduction_type').val();
        	var deduction_value = (isNaN(curRow.find('.deduction_value').val()) || curRow.find('.deduction_value').val() == '')  ? 0 : curRow.find('.deduction_value').val();
           //console.log(deduction_value);
            if(installment_from != 0 && installment_to != 0 && (deduction_type == 0 || deduction_type==1)&& deduction_value > 0){
                postData.push({"installment_from":installment_from,"installment_to":installment_to,"deduction_type":deduction_type,"deduction_value":deduction_value}); 
            }
           console.log(postData);
            if(postData.length > 0 && postData.length){
            createChart(postData);
              //console.log(createChart(postData));
        }
        })*/
   	 
	    	});

  
   $("#discount").change(function(){
	  if($("input[name='sch[discount]']:checked").val()==1){
		  $("input[name='sch[firstPayDisc_by]']").prop('disabled',false);
		  $("#firstPayDisc_value").prop('disabled',false);
	  }else{
		  $("input[name='sch[firstPayDisc_by]']").prop('disabled',true);
		  $("#firstPayDisc_value").prop('disabled',true);
	  }
   });
      $("#all_pay_disc").change(function(){
	  if($("input[name='sch[all_pay_disc]']:checked").val()==1){
		  $("input[name='sch[allpay_disc_by]']").prop('disabled',false);
		  $("#allpay_disc_value").prop('disabled',false);
	  }else{
		  $("input[name='sch[allpay_disc_by]']").prop('disabled',true);
		  $("#allpay_disc_value").prop('disabled',true);
	  }
   });
if(ctrl_page[1]=='add' || ctrl_page[1]=='edit')
{
		var selectedValue="";
	 	var sch_check;
	 		if(($('#discount').is(':checked')))
			{		
				 $("input[name='sch[discount_type]']:radio").attr('disabled',false);
				 $('#disc_select').prop('disabled',false);
			}
			else
			{
				$("input[name='sch[discount_type]']:radio").attr('disabled',true);
				$('#disc_select').prop('disabled',true);
			}
			$('#discount').change(function () {
              	if(($('#discount').is(':checked')))
				{		
					 $("input[name='sch[discount_type]']:radio").attr('disabled',false);
					 $('#disc_select').prop('disabled',false);
				}
				else
				{
					$("input[name='sch[discount_type]']:radio").attr('disabled',true);
					$('#disc_select').prop('disabled',true);
				}
        });
		 $("input[name='sch[discount_type]']:radio").on('change',function(){
		  	if($(this).val()==1)
		  	{
		  		$('#disc_select').prop('disabled',false);
		  	}
		  	else
		  	{
		  		$('#disc_select').prop('disabled',true);
		  	}
		});
        $('#free_payment').change(
            function () {
                if ($('#free_payment').is(':checked')) {
                   $("#allowSecondPay,#approvalReqForFP").prop('disabled',false);  
                }    
                else {
                    $("#allowSecondPay,#approvalReqForFP").prop('disabled',true); 
                    $("#allowSecondPay,#approvalReqForFP").prop("checked", false); 
                }
        });
         if($('#free_payment').is(':checked'))
	     {		 
			$('#allowSecondPay').prop("disabled",false);	  
		 }else {
            $('#allowSecondPay').prop('disabled',true); 
         } 
		 if($('#has_free_ins').is(':checked'))
	     {		 
			$('#free_instalments').prop("disabled",false);	  
		 }
		 $("#free_instalments").change(function() {
			 var data = $("#free_instalments").select2('data');		
			selectedValue = $(this).val(); 		
			$(".free_payInstallments").val(selectedValue);
		}) 
		 $("#avg_installments").change(function() {
			 var data = $("#avg_installments").select2('data');		
			 selectedValue = $(this).val(); 		
			 $(".avg_installments").val(selectedValue);
		}) ; 
		 $("#branches").change(function() {
			 var data = $("#branches").select2('data');		
			// console.log(data);
			 selectedValue = $(this).val(); 		
			 $("#id_branch").val(selectedValue);
		}) ;
		//Stop payment //HH
		 $("#select_intall_to_stop").change(function() {
			 var data = $("#select_intall_to_stop").select2('data');		
			 //console.log(data);
			 selectedValue = $(this).val(); 		
			 $(".select_intall_to_stop").val(selectedValue);
		}) ;
		  //referrals installments//
		   $("#install_select").change(function() {
			 var data = $("#install_select").select2('data');		
			// console.log(data);
			 selectedValue = $(this).val(); 		
			 $(".installs_select").val(selectedValue);
		}) ;
		   $("#disc_select").change(function() {
			 var data = $("#disc_select").select2('data');		
			// console.log(data);
			 selectedValue = $(this).val(); 		
			 $(".disc_select").val(selectedValue);
		}) ; 
		 $("input[name='sch[ref_benifitadd_ins_type]']:radio").on('change click',function(){
			 if($(this).val()==0){				 
				 $('#install_select').prop('disabled',true);
			 }else{				 
				 $('#install_select').prop('disabled',false);
			 }
		});
			 $("input[name='sch[discount_type]']:radio").on('change click',function(){
			 if($(this).val()==0){				 
				 $('#dis_select').prop('disabled',true);
			 }else{				 
				 $('#dis_select').prop('disabled',false);
			 }
		});
		$("input[name='sch[emp_refferal]']").on('change',function(){			
			if($(this).is(':checked')){			
			$("#emp_refferal_value").prop("disabled",false);
			 $("input[name='sch[emp_refferal_by]']").prop('disabled',false);
			}else{				
				$("#emp_refferal_value").prop("disabled",true);
				$("#emp_refferal_value").val(0);
				 $("input[name='sch[emp_refferal_by]']").prop('disabled',true);
			}
		});
		$("input[name='sch[cus_refferal]']").on("change click",function(){			
			if($(this).is(':checked')){				
				$("#cus_refferal_value").prop("disabled",false);
				$("input[name='sch[cus_refferal_by]']").prop('disabled',false);
			}else{				
				 $("#cus_refferal_value").prop("disabled",true);
				 $("#cus_refferal_value").val(0);
				 $("input[name='sch[cus_refferal_by]']").prop('disabled',true);
			}
		})
		if($("#cus_refferal").val()!=1){
			$("#cus_refferal_value").prop("disabled",true);
		}
		if($("#emp_refferal").val()!=1){
			$("#emp_refferal_value").prop("disabled",true);
		}
	if($("input[name='sch[interest]']:checked").val() == 1)
    {
		 $('#interest_value').prop('disabled',false);
		 $('.interest_block').prop('disabled',false);
	}else{
		 $('#interest_value').prop('disabled',true);
		 $('.interest_block').prop('disabled',true);
	}
	
		if($("input[name='sch[is_lucky_draw]']:checked").val() == 1) //lucky draw settings In scheme master HH //
    {
		 $('#max_members').prop('disabled',false);
		 $('.isLuckyDraw_block').prop('disabled',false);
	}else{
		 $('#max_members').prop('disabled',true);
		 $('.isLuckyDraw_block').prop('disabled',true);
	}
	
	if($("input[name='sch[tax]']:checked").val() == 1)
    {
		 $('#tax_value').prop('disabled',false);
		 $('.tax_block').prop('disabled',false);
	}else{
		 $('#tax_value').prop('disabled',true);
		 $('.tax_block').prop('disabled',true);
	}
	if($("input[name='sch[discount]']:checked").val() == 1)
    {
		 $('#firstPayDisc_value').prop('disabled',false);
		 $("input[name='sch[firstPayDisc_by]']:radio").prop('disabled',false);
	}else{
		 $('#firstPayDisc_value').prop('disabled',true);
		 $("input[name='sch[firstPayDisc_by]']:radio").prop('disabled',true);
	}
	if($("input[name='sch[all_pay_disc]']:checked").val() == 1)
    {
		 $('#allpay_disc_value').prop('disabled',false);
		 $("input[name='sch[allpay_disc_by]']:radio").prop('disabled',false);
	}else{
		 $('#allpay_disc_value').prop('disabled',true);
		 $("input[name='sch[allpay_disc_by]']:radio").prop('disabled',true);
	}
		//branches
		//free ins options
		 if(ctrl_page[1]=='edit'){	
		    var id = ctrl_page[2];	
		    $.ajax({
				type: 'GET',
				url: base_url+'index.php/admin_scheme/getFreeInsBySchId/'+id,
				dataType: 'json',
				success: function(data){					
					console.log(data);
					//referrals installments//
					var ref = data.ref_benifitadd_ins;
					var dis = data.discount_installment;
					var ins = data.stop_payment_installment;
					$('#install_select').empty();
					var ref_data = [];
					//referrals installments//
					$('#free_instalments').empty();
					$('#disc_select').empty();
					var str = data.free_payInstallments;
					console.log(str);
					if(str!=null)
					{
					    var res = str.split(",");
					}
					
					var dt = [];
					var total_ins = $('#total_installments').val();
					for(var i=2;i<=total_ins;i++){
						dt.push({id:i,text:i});
					}
					 $("#free_instalments").select2({
					 	data: dt			 	
				   	 }).select2('val', res);	
						$("#avg_installments").select2({
					 	data: dt			 	
				   	 });
					for(var i=1;i<=total_ins;i++){
						ref_data.push({id:i,text:i});
				        }
					$("#install_select").select2({
					   data: ref_data			 	
					 }).select2('val', ref);
					$("#branches").select2();
					$("#disc_select").select2({
					   data: ref_data			 	
					 }).select2('val', dis);
					 $("#select_intall_to_stop").select2({
					   data: ref_data			 	
					 }).select2('val', ins);
				}
			});	
			if($("input[name='sch[cus_refferal]']").is(':checked')){
				$("#cus_refferal_value").prop('disabled',false);
			}else{
				$("#cus_refferal_value").prop('disabled',true);
				$("#cus_refferal_value").val(0);
			}	
			if($("input[name='sch[emp_refferal]']").is(':checked')){
				$("#emp_refferal_value").prop('disabled',false);
			}else{
				$("#emp_refferal_value").prop('disabled',true);
				$("#emp_refferal_value").val(0);
			}
			if($("input[name='sch[ref_benifitadd_ins_type]']:checked").val()==1){
				$("#install_select").prop('disabled',false);
			}else{
				$("#install_select").prop('disabled',true);
			}
			if($("input[name='sch[cus_refferal]']").is(':checked')){
				$("input[name='sch[cus_refferal_by]']").prop('disabled',false);
			}else{
				$("input[name='sch[cus_refferal_by]']").prop('disabled',true);
			}
			if($("input[name='sch[emp_refferal]']").is(':checked')){
				 $("input[name='sch[emp_refferal_by]']").prop('disabled',false);
			}else{
				 $("input[name='sch[emp_refferal_by]']").prop('disabled',true);
			}
		 }
		else if(ctrl_page[1]=='add' ){
		 	$("#free_instalments").select2();
			$("#avg_installments").select2();
			$("#branches").select2();
			$("#install_select").select2();
			$("#select_intall_to_stop").select2();
		 }
	 if($("input[name='sch[scheme_type]']:checked").val()==1 && $('#min_weight').val()!=$('#max_weight').val()){
			 $('#allow_unpaid').prop('disabled','disabled');
            $('#allow_advance').prop('disabled','disabled');
            $('#allow_preclose').prop('disabled','disabled');
		}
	$('#allow_unpaid').on('change click',function(){	
	     //$("input.unpaid_block").prop("disabled", !$(this).is(':checked'));
	     $('#unpaid_months').prop("disabled",!$(this).is(':checked'))
	     if($(this).is(':checked'))
	     {
	     	$('#unpaid_weight_limit').prop("disabled",!$("#opt_weight").is(':checked'))
		 }
		 else{
		 	$('#unpaid_weight_limit').prop("disabled",true);
		 	$('#unpaid_months').val(0);
		 }
	});	
	$('#total_installments').on('keyup', function() {
	    $('#maturity_installment').val(this.value);
		$('#avg_installments').empty();
		var b= $('#total_installments').val();
		   for(j=1;j<=b;j++)
			{
				$('#avg_installments').append(
						$("<option></option>")
						  .attr("value", j)
						  .text(j)	
				); 
			}
	}); 
	$('#total_installments').on('keyup', function() {
		$('#free_instalments').empty();
		var b= $('#total_installments').val();
		   for(i=2;i<=b;i++)
			{
				$('#free_instalments').append(
						$("<option></option>")
						  .attr("value", i)
						  .text(i)	
				); 
			}
	});
		//stop payment installments //HH
	$('#total_installments').on('keyup', function() {
		$('#select_intall_to_stop').empty();
		var b= $('#total_installments').val();
		   for(i=1;i<=b;i++)
			{
				$('#select_intall_to_stop').append(
						$("<option></option>")
						  .attr("value", i)
						  .text(i)	
				); 
			}
	}); 
	//referrals installments//
	$('#total_installments').on('keyup', function() {
		$('#disc_select').empty();
		$('#install_select').empty();
		$('#avg_calc_ins').empty();
		$('#apply_benefit_min_ins').empty();
		var b= $('#total_installments').val();
		$("#disc_select,#install_select,#avg_calc_ins,#apply_benefit_min_ins").append(
						$("<option></option>")
						  .attr("value", '')
						  .text('-- Select --')	
				);
		   for(i=1;i<=b;i++)
			{
				$("#disc_select,#install_select,#avg_calc_ins,#apply_benefit_min_ins").append(
						$("<option></option>")
						  .attr("value", i)
						  .text(i)	
				); 
			} 
	});
		$('#has_free_ins').on('change',function(){	
	     if($(this).is(':checked'))
	     {	
			$('#free_instalments').prop("disabled",false);
		 }
		 else{
			  $('#free_instalments').prop("disabled",true);
			}
		});	
//	$("[name='sch[free_payment]']").bootstrapSwitch();
	$('#allow_advance').on('change',function(){
		 $("#advance_months").prop("disabled", !$(this).is(':checked'));
		  if($(this).is(':checked'))
	     {
	     	$('#advance_weight_limit').prop("disabled",!$("#opt_weight").is(':checked'))
		 }
		 else{
		 	$('#advance_weight_limit').prop("disabled",true);
		 	$('#advance_months').val(0);
		 }
	});
	$('#allow_preclose').on('change click',function(){		
		 $("#preclose_months").prop("disabled", !$(this).is(':checked'));
		 (!$(this).is(':checked')? $("#preclose_months").val(0):'');
		 $("[name='sch[preclose_benefits]']").bootstrapSwitch('disabled',!$(this).is(':checked'));
	});
	
	$('#maturity_type').on('change',function(){  //  Maturity Days showed based on the maturity type-2//HH 
	       var maturity_type=$(this).val();
			   if(maturity_type==1 || maturity_type==3)
						{
							$('#maturity_setting').css('display','none');
						}
						else{
						    $('#maturity_setting').css('display','block');
						}
		});
	
	$('#maturity_type').on('change',function(){  //  Closing Maturity Days showed based on the maturity type-2//HH 
	       var maturity_type=$(this).val();
			   if(maturity_type==1 || maturity_type==3)
						{
							$('#close_maturity_setting').css('display','none');
						}
						else{
						    $('#close_maturity_setting').css('display','block');
						}
		});
}
/* -- Coded by ARVK -- */
if(ctrl_page[1]=='edit' || ctrl_page[1]=='add')
{
		   if($("input[name='sch[scheme_type]']:checked").val()==0)
		   {
				$("#paymenttype_settings").css("display", "none");
				$("#price_settings").css("display", "none");
		   		$('#amount').prop('disabled', false);
	            $('#min_weight').prop('disabled','disabled');
	            $('#max_weight').prop('disabled','disabled');
	            $('input.amtsch_block').prop('disabled',false);
	            $('#min_chance').prop('disabled','disabled');
	        	$('#max_chance').prop('disabled','disabled');
				$("#payment_setting").css("display","block");
				$("#free_pay_settings").css("display","block");
				$("div#settlement_settingsamt").css("display", "block");
				$("div#payment_type").css("display", "none");
				$('#pay_type').val('');
		   }
		   else if($("input[name='sch[scheme_type]']:checked").val()==2)
		   {
				$("#payment_setting").css("display","block");
				$("#free_pay_settings").css("display","block");
				$("#price_settings").css("display", "block"); 
				$("div#paymenttype_settings").css("display", "none");
				$("div#settlement_settingsamt").css("display", "block");
				$('#amount').prop('disabled', false);
				$('#min_weight').prop('disabled','disabled');
				$('#max_weight').prop('disabled','disabled');	         
				$('#min_amount').prop('disabled','disabled');
				$('#max_amount').prop('disabled','disabled');
				$('#min_chance').prop('disabled','disabled');
				$('#max_chance').prop('disabled','disabled');
				$('#pay_type').val('');
		   }
		    else if($("input[name='sch[scheme_type]']:checked").val()==3)
		   {		
				$("#payment_type").css("display", "block");
				$("div#paymenttype_settings").css("display", "none");
				$("div#paymentamount_limit").css("display", "block");
				$("#price_settings").css("display", "none");    
				$("div#weighttye_settings").css("display", "block");
				$("div#settlement_settingsamt").css("display", "block");
				$('#amount').prop('disabled', false); 
				$("#free_pay_settings").css("display","none"); 
				$('#min_chance').prop('disabled','disabled');
				$('#max_chance').prop('disabled','disabled');
						if($("input[name='sch[one_time_premium]']:checked").val()==1)
					   {
						   $('#price_settings').css('display','block');
							$('#paymenttype_settings').css('display','block');   //showed min &max one time premium==1//HH
							$('#paymentmethod_settings').css('display','none');
							$('#weight_convert').css('display','none');
							$('#weighttye_settings').css('display','none');
						//	$('#total_installments').prop('disabled',true);
							$('#amount').prop('disabled',true);
					   }
					   else{
							$('#price_settings').css('display','none');
							$('#paymenttype_settings').css('display','block');
							$('#paymentmethod_settings').css('display','block');
							$('#weight_convert').css('display','none');
							$('#weighttye_settings').css('display','none');
						//	$('#total_installments').prop('disabled',false);
							$('#amount').prop('disabled',false);
					   }
					   if($('#pay_type').val()==1)
					   {
					       $('#flx_denomintions').css('display','block');   // enabled based on the schem type- flx sch type 1,2//HH 
						  $('#premium_settings').css('display','block');
					   }
					   else if($('#pay_type').val()==2)
					   {
					       $('#flx_denomintions').css('display','block'); // enabled based on the schem type- flx sch type 1,2//HH
							$('#premium_settings').css('display','none');
							$('#price_settings').css('display','none');
							$('#total_installments').prop('disabled',false);
							$('#amount').prop('disabled',false);
							$('#paymenttype_settings').css('display','block');
							$('#weight_convert').css('display','none');
							$('#weighttye_settings').css('display','none');
							$('#paymentamount_limit').css('display','block');
							$('#paymentmethod_settings').css('display','block');
					   }
					   else if($('#pay_type').val()==3)
					   {		
							$('#premium_settings').css('display','none');
							$('#price_settings').css('display','none');
							$('#total_installments').prop('disabled',false);
							$('#amount').prop('disabled',false);
							$('#paymenttype_settings').css('display','block');
							$('#weight_convert').css('display','block');
							$('#weighttye_settings').css('display','block');
							$('#paymentamount_limit').css('display','block');
							$('#paymentmethod_settings').css('display','block');
					   }
					   else if($('#pay_type').val()==4){
							/*$('#premium_settings').css('display','block');
							$('#price_settings').css('display','none');
							$('#total_installments').prop('disabled',false);
							$('#amount').prop('disabled',false);
							$('#paymenttype_settings').css('display','none');
							$('#weight_convert').css('display','none');
							$('#weighttye_settings').css('display','none');
							$('#paymentamount_limit').css('display','none');
							$('#paymentmethod_settings').css('display','none');*/
							$('#premium_settings').css('display','block');
							$('#price_settings').css('display','none');
							$('#total_installments').prop('disabled',false);
							$('#paymenttype_settings').css('display','block');
							$('#weight_convert').css('display','block');
							$('#weighttye_settings').css('display','block');
							$('#paymentamount_limit').css('display','none');
							$('#paymentmethod_settings').css('display','block');
							$('#amount').prop('disabled',true);
					   }
					   if($("input[name='sch[otp_price_fixing]']:checked").val()==1)
					   {
						  $('#otp_price_fix_single').prop('disabled',false);
						  $('#otp_price_fix_multiple').prop('disabled',false);
					   }
					   else{
							$('#otp_price_fix_single').prop('disabled',true);
							$('#otp_price_fix_multiple').prop('disabled',true);
					   }
		   }
		   else
		   {
				$("#payment_setting").css("display","block");
				$("#free_pay_settings").css("display","block");
				$("#price_settings").css("display", "block");
				$("div#paymenttype_settings").css("display", "block");
				$("div#paymentamount_limit").css("display", "none");
				$("div#paymentmethod_settings").css("display", "none");
				$("div#weighttye_settings").css("display", "block");
				$("div#settlement_settingswgt").css("display", "block");
				$('#amount').prop('disabled', 'disabled');
				$('#amount').val('0.00');
				$('#min_weight').prop('disabled',false);
				$('#max_weight').prop('disabled',false);
				$('#min_chance').prop('disabled',false);
				$('#max_chance').prop('disabled',false); 
				$('#pay_type').val('');
		   }
		   //Scheme Type Change functions
			$("input[name='sch[scheme_type]']:radio").change(function () {
			if($(this).val()==0)
			{
				$("div#paymenttype_settings").css("display", "none");
				$('#amount').prop('disabled', false);
				$("#price_settings").css("display", "none");
				$("#payment_setting").css("display","block");
				$("#free_pay_settings").css("display","block");
				$('#min_weight').prop('disabled','disabled');
				$('#max_weight').prop('disabled','disabled');
				$('input.amtsch_block').prop('disabled',false);
				$("input[name='sch[payment_chances]']").prop('disabled','disabled');
				$('#min_chance').prop('disabled','disabled');
				$('#max_chance').prop('disabled','disabled');
				$('#min_amount').prop('disabled','disabled');
				$('#max_amount').prop('disabled','disabled');
				$("div#payment_type").css("display", "none");
				$('#pay_type').val('');
			}
			else if($(this).val()==2)
			{
				$("div#paymenttype_settings").css("display", "none");
				$("#payment_setting").css("display","block");
				$("#free_pay_settings").css("display","block");
				$("#price_settings").css("display", "block");  
				$('#amount').prop('disabled', false);
				$('#min_weight').prop('disabled','disabled');
				$('#max_weight').prop('disabled','disabled');
				$('input.amtsch_block').prop('disabled',false);
				$("input[name='sch[payment_chances]']").prop('disabled','disabled');
				$('#min_chance').prop('disabled','disabled');
				$('#max_chance').prop('disabled','disabled');
				$('#min_amount').prop('disabled','disabled');
				$('#max_amount').prop('disabled','disabled');
				$("div#payment_type").css("display", "none");
					$('#pay_type').val('');
			}
			else if($(this).val()==3)
			{
				$("#price_settings").css("display", "none");
				$("#payment_type").css("display", "block");
				$("div#paymenttype_settings").css("display", "none"); //Limit settings,Convert settings
				$("div#paymentamount_limit").css("display", "block");
				$("div#weighttye_settings").css("display", "block");	
				$("input[name='sch[payment_chances]']").prop('disabled',false);
				$('#amount').prop('disabled', 'disabled');
				$('#min_weight').prop('disabled',false);
				$('#max_weight').prop('disabled',false);
				$('#max_amt_chance').prop('disabled',false);
				$('#min_amt_chance').prop('disabled',false);
				$("#payment_setting").css("display","block"); 
				$("#free_pay_settings").css("display","none");
				$('input.amtsch_block').prop('disabled',false);
				$('#min_chance').prop('disabled','disabled');
				$('#min_amount').prop('disabled',false);
				$('#max_amount').prop('disabled',false);
				$('#max_chance').prop('disabled','disabled');
			}
			else
			{
				$("#price_settings").css("display", "none");
				$("div#paymentmethod_settings").css("display", "none");  
				$("div#paymenttype_settings").css("display", "block");
				$("div#paymentamount_limit").css("display", "none");
				$("div#weighttye_settings").css("display", "block");  
				$("#payment_setting").css("display","block");
				$("#free_pay_settings").css("display","block");
				$('#minimal').hide();
				$('#wgt_cnvrt').hide();
				$('#amount').prop('disabled', 'disabled');
				$('#amount').val('0.00');
				$('#min_weight').prop('disabled',false);
				$('#max_weight').prop('disabled',false);
				$('#min_amount').prop('disabled','disabled');
				$('#max_amount').prop('disabled','disabled');
				$("input[name='sch[payment_chances]']").prop('disabled',false);	   
				$("div#payment_type").css("display", "none");				
			}
			});
		    //Scheme Type Change functions
			$('#pay_type').on('change',function(){
				var pay_type=$(this).val();
			    if(pay_type!='')
				{
					$('#flexible_sch_type').val(pay_type);
						if(pay_type==1)
						{
							$('#premium_settings').css('display','block');
							$('#paymentmethod_settings').css('display','block'); 
							$('#paymenttype_settings').css('display','block');
							
							if($('#one_time_premium').val()==1)
							{
							    //	$('#total_installments').prop('disabled',true);
							    	$('#amount').prop('disabled',true);
							} 
							$('#price_settings').css('display','none');
							$('#total_installments').prop('disabled',false);
							$('#amount').prop('disabled',false); 
							$('#weight_convert').css('display','none');
							$('#weighttye_settings').css('display','none');
							$('#paymentamount_limit').css('display','block');
						}
						else  if(pay_type==2)
						{
							$('#premium_settings').css('display','none');
							$('#price_settings').css('display','none');
							$('#total_installments').prop('disabled',false);
							$('#amount').prop('disabled',false);
							$('#paymenttype_settings').css('display','block');
							$('#weight_convert').css('display','none');
							$('#weighttye_settings').css('display','none');
							$('#paymentamount_limit').css('display','block');
							$('#paymentmethod_settings').css('display','block');
						}
						else  if(pay_type==3)
						{
							$('#premium_settings').css('display','none');
							$('#price_settings').css('display','none');
							$('#total_installments').prop('disabled',false);
							$('#amount').prop('disabled',false);
							$('#paymenttype_settings').css('display','block');
							$('#weight_convert').css('display','block');
							$('#weighttye_settings').css('display','block');
							$('#paymentamount_limit').css('display','block');
							$('#paymentmethod_settings').css('display','block');
						}
						else  if(pay_type==4)
						{ 
						    $('#premium_settings').css('display','block');
							$('#price_settings').css('display','none');
							$('#total_installments').prop('disabled',false);
							$('#paymenttype_settings').css('display','block');
							$('#weight_convert').css('display','block');
							$('#weighttye_settings').css('display','block');
							$('#paymentamount_limit').css('display','none');
							$('#paymentmethod_settings').css('display','block');
							$('#amount').prop('disabled',true);
						}
				}
				else{
					$('#flexible_sch_type').val('');
				}
			});
			$("input[name='sch[one_time_premium]']:radio").on('change',function(){
				var one_time_premium=$(this).val();
				if(one_time_premium==1)
				{
					$('#price_settings').css('display','block');
					$('#paymenttype_settings').css('display','block');  //showed min &max one time premium==1//HH
					$('#paymentmethod_settings').css('display','none');
					$('#weight_convert').css('display','none');
					$('#weighttye_settings').css('display','none');
				//	$('#total_installments').prop('disabled',true);
					$('#amount').prop('disabled',true);
				}
				else{
				    if($('#pay_type').val()==4)
					{
					    	$('#weight_convert').css('display','none');
        					$('#weighttye_settings').css('display','block');
        					$('#paymenttype_settings').css('display','block');
    					    $('#paymentmethod_settings').css('display','block');
        				//	$('#total_installments').prop('disabled',true);
        					$('#amount').prop('disabled',true);
					}else
					{
					    $('#price_settings').css('display','none');
    					$('#paymenttype_settings').css('display','block');
    					$('#paymentmethod_settings').css('display','block');
    					$('#weight_convert').css('display','none');
    					$('#weighttye_settings').css('display','none');
    					$('#total_installments').prop('disabled',false);
    					$('#amount').prop('disabled',false);
					}
				}
			});
			$("input[name='sch[otp_price_fixing]']:radio").on('change',function(){
				var otp_price_fixing=$(this).val();
				if(otp_price_fixing==0)
				{
					$('#otp_price_fix_single').prop('disabled',true);
					$('#otp_price_fix_multiple').prop('disabled',true);
				}
				else{
					$('#otp_price_fix_single').prop('disabled',false);
					$('#otp_price_fix_multiple').prop('disabled',false);
				}
			});
}
	$("#min_weight,#max_weight").on('blur keyup',function(){		
		if($("input[name='sch[scheme_type]']:checked").val()==1 && $('#min_weight').val()==$('#max_weight').val()){
		//	alert($("input[name='sch[scheme_type]']:checked").val());
				 $('#allow_unpaid').prop('disabled',false);
	            $('#allow_advance').prop('disabled',false);
	            $('#allow_preclose').prop('disabled',false);
		}
		else if($("input[name='sch[scheme_type]']:checked").val()==1 && $('#min_weight').val()!=$('#max_weight').val()){
			//alert($("input[name='sch[scheme_type]']:checked").val());
			 $('#allow_unpaid').prop('disabled','disabled');
            $('#allow_advance').prop('disabled','disabled');
            $('#allow_preclose').prop('disabled','disabled');
		}
	})
/* -- / Coded by ARVK -- */
//For editor
 if($('#description').length > 0)
 {
 	CKEDITOR.replace('description');
 }
 $('#tax_value').on('keypress keyup blur change click', function() {
	$('#total_tax').val(calc_tax());
 }); 
 $("input:radio[name='sch[tax_by]']").on('change click',function(){
 	   $('#total_tax').val(calc_tax());
 	});
 $('#interest_value').on('keypress keyup keydown blur change click', function() {
	$('#total_interest').val(calc_interest());
 });
	$("input:radio[name='sch[interest_by]']").on('change click',function(){
		$('#total_interest').val(calc_interest());
	});
//to get metals
	get_metal();	
//to get Classifications
	get_classifications();
	// to get branches
	  get_branches();
	// to get branches
$("input[name='sch[payment_chances]']:radio").change(function () {
	if($(this).val()==1)
	  {
                  // $("#price_settings").css("display", "none");
	  		  $('#min_chance').prop('disabled',false);
			  $('#max_chance').prop('disabled',false);
	  }	
	  else
	  {
	  		$('#min_chance').prop('disabled','disabled');
	        $('#max_chance').prop('disabled','disabled');	  	    
	  }
});		
$('#isInterest').change(function(){
   $("input.interest_block").prop("disabled", !$(this).is(':checked'));
   $("#interest_value").prop("disabled", !$(this).is(':checked'));
   $("#interest_value").val('0');
   $("#total_interest").val('0.00');
});

$('#isLuckyDraw').change(function(){  //lucky draw settings In scheme master HH //
   $("input.isLuckyDraw_block").prop("disabled", !$(this).is(':checked'));
   $("#has_prize").prop("disabled", !$(this).is(':checked'));
   $("#max_members").prop("disabled", !$(this).is(':checked'));
   $("#has_prize").val('0');
   $("#max_members").val('');
});
$('#isTaxable').change(function(){
	$("input.tax_block").prop("disabled", !$(this).is(':checked'));
   $("#tax_value").prop("disabled", !$(this).is(':checked'));
   $("#tax_value").val('0');
   $("#total_tax").val('0.00');
});
});
function get_metal()
{
	$.ajax({
		type: 'GET',
		url: base_url+'index.php/scheme/get_metals',
		dataType: 'json',
		success: function(data){
			//console.log(data);
		$.each(data, function (key, data) {	
			$('#metal').append(
			 $('<option></option>')
			   .attr('value',data.id)
			   .text(data.name)	
			);
			selectid=$('#metal_val').val();
			$('#metal').val((selectid!=null?selectid:0));
		 });	
		}
	});
}
function get_classifications()
{
	$.ajax({
		type: 'GET',
		url: base_url+'index.php/scheme/get_classifications',
		dataType: 'json',
		success: function(data){
			//console.log(data);
		$.each(data, function (key, data) {	
			$('#classify').append(
			 $('<option></option>')
			   .attr('value',data.id)
			   .text(data.name)	
			);
			selectid=$('#classify_val').val();
			$('#classify').val((selectid!=null?selectid:0));
		 });	
		}
	});
}
function get_branches()
{
	$.ajax({
		type: 'GET',
		url: base_url+'index.php/scheme/get_branches',
		dataType: 'json',
		success: function(data){
		//	console.log(data);
		$.each(data, function (key, data) {	
			$('#branches').append(
			 $('<option></option>')
			   .attr('value',data.id_branch)
			   .text(data.name)	
			);
		 var ar = $('#sel_br').data('sel_br');
		 console.log(data);
		 console.log(ar);
			$('#branches').select2('val', ar);	
			console.log($('#branches').val());
		 });	
		}
	});
}
//calculate tax
function calc_tax()
 {
 	var selected = $("input[name='sch[tax_by]']:checked").val();
 	var duration=parseFloat($('#total_installments').val()).toFixed(2);
 	var amount=(parseFloat($('#amount').val())*parseFloat(duration)).toFixed(2);
 	var tax_val=parseFloat($("#tax_value").val()).toFixed(2);
 	var total=0;
 	if(selected==0)
 	{
		total=(parseFloat(amount)*(parseFloat(tax_val)/100)).toFixed(2);
	}
	else
	{
		 total= parseFloat(tax_val).toFixed(2);
	}
	if(isNaN(total))
	{
		total=0;
	}
	return total;
 }
 //calculate interest
 function calc_interest()
 {
 	var selected = $("input[name='sch[interest_by]']:checked").val();
 	var duration=parseFloat($('#total_installments').val()).toFixed(2);
 	var amount=(parseFloat($('#amount').val())*parseFloat(duration)).toFixed(2);
 	var interest_val=parseFloat($("#interest_value").val()).toFixed(2);
 	var total=0;
 	if(selected==0)
 	{
		total=(parseFloat(amount)*(parseFloat(interest_val)/100)).toFixed(2);
	}
	else
	{
		 total= parseFloat(interest_val).toFixed(2);
	}
	if(isNaN(total))
	{
		total=0;
	}
	return total;
 }
function get_scheme_list()
	{
	my_Date = new Date();
	$("div.overlay").css("display", "block");
	$.ajax({
			  url:base_url+ "index.php/scheme/ajax_scheme_list?nocache=" + my_Date.getUTCSeconds(),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			   			set_scheme_list(data);
			   			$("div.overlay").css("display", "none");
					  },
					  error:function(error)  
					  {
						 $("div.overlay").css("display", "none"); 
					  }	 
			      });
}
function set_scheme_list(data)
{
	 var scheme 	= data.data;
	 var access		= data.access;	
	 $('#total_schemes').text(scheme.length);
	 if(access.add == '0')
	 {
		$('#add_scheme').attr('disabled','disabled');
	 }
	 var oTable = $('#scheme_list').DataTable();
	     oTable.clear().draw();
			  	 if (scheme!= null && scheme.length > 0)
			  	  {  	
					  	oTable = $('#scheme_list').dataTable({
				                "bDestroy": true,
				                "bInfo": true,
				                "bFilter": true,
				                "bSort": true,
				                "aaData": scheme,
				                "order":[[0,'desc']],
				                "aoColumns": [{ "mDataProp": "id_scheme" },
					                { "mDataProp": "scheme_name" },
					                { "mDataProp": "code" },
									{ "mDataProp": function ( row, type, val, meta ){
										  var type=(row.scheme_type == '0'?"Amount":(row.scheme_type == '1'?"Weight" :row.scheme_type=='3'?"FLXIBLE_AMOUNT":"Amount to Weight"));
										  return type;
									}
					                },
									{ "mDataProp": function ( row, type, val, meta ){
										  var amount=((row.scheme_type == '0'||row.scheme_type == '2')?row.amount:"-");
										  return amount;
									}
					                },
					                { "mDataProp": "total_installments" },
									{ "mDataProp": function ( row, type, val, meta ){
											//active_url =base_url+"index.php/admin_scheme/customer_status/"+(row.active==1?0:1)+"/"+row.id_customer; 
					                		return "<i class='fa "+(row.active==1?'fa-check':'fa-remove')+"' style='color:"+(row.active==1?'green':'red')+"'></i></a>"
					                	}
					                },
									{ "mDataProp": function ( row, type, val, meta ){
										id= row.id_scheme;
					                	    edit_url=(access.edit=='1' ?  base_url+'index.php/scheme/edit/'+id :'#' );
											delete_url=(access.delete=='1' ? base_url+'index.php/scheme/delete/'+id : '#' );
											delete_confirm= (access.delete=='1' ?'#confirm-delete':'');
											action_content='<div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button><ul class="dropdown-menu">'+
					    '<li><a href="'+edit_url+'" class="btn-edit"><i class="fa fa-edit" ></i> Edit</a></li>'+
					    '<li><a href="#" class="btn-del" data-href="'+delete_url+'" data-toggle="modal" data-target="'+delete_confirm+'"  ><i class="fa fa-trash"></i> Delete</a></li></ul></div>';
					                	return action_content;	
													}
									}] 
				            });			  	 	
					  	 }
}
//GG
$("#edit_sch_img").change( function(e){
    	e.preventDefault(); 
		 //alert(asd);
    	validate_Image(this);
	});
function validate_Image()
   {
    console.log(arguments);
   	 if(arguments[0].id == 'edit_sch_img')
      {
		 var preview = $('#edit_sch_img_preview');
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
//GG update
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
	
	
// preclose interest deduction based on scheme chart //HH
		$("#proced").on("click",function(){ 
	
		createchart_table();
	});
	

  $("#isapply_benefit_by_chart").on("click",function() {    // show button based on the checkbox click//
    $(".answer").toggle(this.checked);
  });
 
  
  
  $("#isInterest").on("click",function() {    // show button based on the checkbox click//
    $(".black").toggle(this.checked);
  });

  
 
	$(document).on("click",'.delete',function(){  // remove row

   $(this).closest('tr').remove(); 
    });

function createchart_table(){
      var i = 1;
   var table_rows = $('#chart_creation_tbl tbody tr').length;
   var row = "<tr rowID='" + i + "'>"+"<td>From<input type='number' name='installmentchart["+table_rows+"][installment_from]' class='installment_from' value='1' style='width: 50px;'>To<input type='number' name='installmentchart["+table_rows+"][installment_to]' class='installment_to' value='1'  style='width: 50px;'></td><td><input type='radio' name='installmentchart["+table_rows+"][interest_type]' class='interest_type' value='1'  style='width: 50px;'>Amt<input type='radio' name='installmentchart["+table_rows+"][interest_type]' class='interest_type' checked  value='0'  style='width: 50px;'>%</td><td><input type='number' name='installmentchart["+table_rows+"][interest_value]' value='10' class='form-control interest_value' style='width: 100px;'   style='width: 100px;'></td><td><div><button id='" + i + "' class='delete btn btn-danger'   name='delete'type='button'><i class='fa fa-trash'></i> REMOVE</button></div></td></tr>";
    
         $('#chart_creation_tbl tbody').append(row);
        $("#save_blk").css("display","block");
    
}
        
    function createChart(postData){
        console.log(postData);
    $("div.overlay").css("display", "block");
    $.ajax({
        url:base_url+ "index.php/admin_scheme/sch_post/"+type,
        data: {'installment_from':installment_from,'installment_to':installment_to,'interest_type':interest_type,'interest_value':interest_value},
        type:"POST",
        dataType:"JSON",
        async:false,
		 	  success:function(data){
		 	      console.log(data);
		         //   location.reload(false);
		   			$("div.overlay").css("display", "none"); 
			  },
			  error:function(error)  
			  {
				 $("div.overlay").css("display", "none"); 
			  }
    });
} 
	

   
   // Pre-close settings benefit deduction based on scheme chart //HH
   
	$("#proceds").on("click",function(){ 
	 createpreclose_chart_table();
	});
	
	$("#apply_debit_on_preclose").on("click",function() {    // show button based on the checkbox click//
    $(".precloseblock").toggle(this.checked);
  });


  $("#isInterest").on("click",function() {    // show & Hide checkbox based on the checkbox click//
  
  if ($(this).is(":checked")){
      $(".precloseblock_open").hide();
   } else{
      $(".precloseblock_open").show();
   }
  });

  
 
	$(document).on("click",'.delete',function(){  // remove row

   $(this).closest('tr').remove(); 
    });

function createpreclose_chart_table(){
      var i = 1;
   var table_rows = $('#preclose_chart_creation_tbl tbody tr').length;
   var row = "<tr rowID='" + i + "'>"+"<td>From<input type='number' name='installmentpreclosechart["+table_rows+"][installment_from]' class='installment_from' value='1' style='width: 50px;'>To<input type='number' name='installmentpreclosechart["+table_rows+"][installment_to]' class='installment_to' value='1'  style='width: 50px;'></td><td><input type='radio' name='installmentpreclosechart["+table_rows+"][deduction_type]' class='deduction_type' value='1'  style='width: 50px;'>Amt<input type='radio' name='installmentpreclosechart["+table_rows+"][deduction_type]' class='deduction_type' checked  value='0'  style='width: 50px;'>%</td><td><input type='number' name='installmentpreclosechart["+table_rows+"][deduction_value]' value='10' class='form-control deduction_value' style='width: 100px;'   style='width: 100px;'></td><td><div><button id='" + i + "' class='delete btn btn-danger'   name='delete'type='button'><i class='fa fa-trash'></i> REMOVE</button></div></td></tr>";
    
         $('#preclose_chart_creation_tbl tbody').append(row);
        $("#save_blk").css("display","block");
    
}
        
    function createpreclose_Chart(post_Data){
        console.log(post_Data);
    $("div.overlay").css("display", "block");
    $.ajax({
        url:base_url+ "index.php/admin_scheme/sch_post/"+type,
        data: {'installment_from':installment_from,'installment_to':installment_to,'deduction_type':deduction_type,'deduction_value':deduction_value},
        type:"POST",
        dataType:"JSON",
        async:false,
		 	  success:function(data){
		 	      console.log(data);
		         //   location.reload(false);
		   			$("div.overlay").css("display", "none"); 
			  },
			  error:function(error)  
			  {
				 $("div.overlay").css("display", "none"); 
			  }
    });
} 
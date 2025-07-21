var path =  url_params();
var ctrl_page = path.route.split('/');
$(document).ready(function() 
{
    if(ctrl_page[1]=='insertTrans'){
		load_paystatus_select();
	}
	
	get_branchnames();
//	get_payments_data_list();
	/*if(ctrl_page[1]=='add')
	{
		$.each(customerListArr, function(key, val)
		{
			customerList.push({'label' : val.mobile+'  '+val.name, 'value' : val.id});
		});	
		$( "#mobile_number" ).autocomplete(
		{
			source: customerList,
			select: function(e, i)
			{
				e.preventDefault();
				$("#mobile_number" ).val(i.item.label);
				$("#id_customer").val(i.item.value);
				var id_customer=$('#id_customer').val();
				$('.overlay').css('display','block');
				var my_Date = new Date();
				if($('#id_customer').val()!='')
				{
					$.ajax({
					  type: 'GET',
					  url:  base_url+'index.php/payment/get/ajax/customer/account/'+id_customer+'?nocache=' + my_Date.getUTCSeconds(),
					  dataType: 'json',
					  cache:false,
						success: function(data) 
						{
							if($('#scheme_account').length>0)
								 {
									$('#scheme_account').empty();
									$("#scheme_account").select2("val",'');
									$.each(data.accounts, function (key, acc) {
										$('#scheme_account').append(
											$("<option></option>")
											  .attr("value", acc.id_scheme_account)
											  .text(acc.scheme_acc_number)
										);
									});
									$(".eligible_walletamt").css("display","none");
									if(data.wallet_balance){
										console.log(data.wallet_balance);
										$('.wallet_balance').val(parseFloat(data.wallet_balance.wal_balance));
										$('.redeem_percent').val(parseFloat(data.wallet_balance.redeem_percent));
										if($('.wallet_balance').val()!='0'){ 
											$(".eligible_walletamt").css("display","block"); 
										} 
										$('.wallet').val(parseFloat(data.wallet_balance.wal_balance));
									}
									$("#scheme_account").select2({
									  placeholder: "Select scheme account",
										allowClear: true
									});		
									$("#scheme_account").select2("val", ($('#id_scheme_account').val()!=null?$('#id_scheme_account').val():''));
								 }
							 //disable spinner
							$('.overlay').css('display','none');
						},
						error:function(error)
						{
						console.log(error);
						//disable spinner
						$('.overlay').css('display','none');
						}	
					});
				}
				else
				{
					$("#scheme_account").select2("val",'');
					$('#scheme_account').empty();
					$('#scheme-detail-box').addClass('box-default');
					$('#mobile_number').val('');
					$('#id_customer').val('');
					$('#id_scheme_account').val('');
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
	}*/
		if(ctrl_page[1]=='add')
	{
	/*	$.each(customerListArr, function(key, val)
		{
			customerList.push({'label' : val.mobile+'  '+val.name, 'value' : val.id});
		});	*/
		$("#id_branch").val(this.value);
					 var id=$(this).val();
		get_scheme(id);
	$( "#mobile_number" ).autocomplete({
      source: function( request, response ) 
	  {
      	var mobile=$( "#mobile_number" ).val();
		var id_scheme=$("#id_scheme").val(); 
        $.ajax({
	 	 url:  base_url+'index.php/admin_customer/ajax_get_customers_list',
          dataType: "json",
          type: 'POST',
         data:{'mobile':mobile,'id_scheme':id_scheme},
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
	  delay: 300, // this is in milliseconds
		select: function(e, i)
		{
		e.preventDefault();
		$("#mobile_number" ).val(i.item.label);
		$("#id_customer").val(i.item.value);
		//$("#id_scheme_account").val(i.item.id_scheme_account);
		$('.overlay').css('display','block');
		$('#scheme_account').empty();
		my_Date = new Date();
		var id_customer=$('#id_customer').val();
		if($('#id_customer').val()!='')
		{
			$.ajax({
			  type: 'GET',
			  url:  base_url+'index.php/payment/get/ajax/customer/account/'+id_customer+'?nocache=' + my_Date.getUTCSeconds(),
			  dataType: 'json',
			  cache:false,
			success: function(data) {
						$('#scheme_account').empty();
						if($('#scheme_account').length>0)
							 {
								$.each(data.accounts, function (key, acc) {
									$('#scheme_account').append(
										$("<option></option>")
										  .attr("value", acc.id_scheme_account)
										  .text(acc.scheme_acc_number)
									);
								});
								$(".eligible_walletamt").css("display","none");
								if(data.wallet_balance){
									console.log(data.wallet_balance);
									$('.wallet_balance').val(parseFloat(data.wallet_balance.wal_balance));
									$('.redeem_percent').val(parseFloat(data.wallet_balance.redeem_percent));
									if($('.wallet_balance').val()!='0'){ 
										$(".eligible_walletamt").css("display","block"); 
									} 
									$('.wallet').val(parseFloat(data.wallet_balance.wal_balance));
								}
								$("#scheme_account").select2({
								  placeholder: "Select scheme account",
									allowClear: true
								});		
								$("#scheme_account").select2("val", ($('#id_scheme_account').val()!=null?$('#id_scheme_account').val():''));
							 }
						 //disable spinner
						 			$('.overlay').css('display','none');
			},
				error:function(error)
			{
			console.log(error);
			//disable spinner
			$('.overlay').css('display','none');
			}	
			 });
		}
		else
		{
		$("#scheme_account").select2("val",'');
			$('#scheme_account').empty();
			$('#scheme-detail-box').addClass('box-default');
			$('#mobile_number').val('');
			$('#id_customer').val('');
			$('#id_scheme_account').val('');
			alert('Invalid Details');
		}
		},
		response: function(e, i) {
            // ui.content is the array that's about to be sent to the response callback.
            if (i.content.length === 0) {
               alert('Please Enter a valid Number');
               $('#mobile_number').val('');
            } 
        },
		focus: function(e, i) {
        e.preventDefault();
        $("#mobile_number").val(i.item.label);
		}
    });
	}
	 $("#mobile_number").on('keyup', function (event) {
        if($( "#mobile_number" ).val().length==0)
        {
        $("#scheme_account").select2("val",'');
	  	$('#scheme_account').empty();
	  	$('#scheme-detail-box').addClass('box-default');
	  	$('#mobile_number').val('');
	  	$('#id_customer').val('');
	  	$('#id_scheme_account').val('');
        }
       });
		$('#resendotp').on('click',function(){
       var id_customer = $("#id_customer").val();
	$.ajax({
		url:base_url+ "index.php/admin_payment/resend_otp?nocache=" + my_Date.getUTCSeconds(),
		data :  {'id_customer':id_customer}, 
		type : "POST",
		dataType: 'json',
		success:function(data){
			if(data.result==3)
			{
				alert(data.msg);
			}
		}
	});
    });
	$('#verify_otp').on('click',function(){
			$("#verify_otp").attr("disabled", true);
		  var post_data=$('#pay_form').serialize();
					update_otp(post_data);
				});
	  	$('#pay_table').DataTable( {
	"oLanguage": { sLengthMenu:"Show Entries: _MENU_" },
	"order"	   : [[0,'desc']],
	 fixedColumns: true
	} );
	if(ctrl_page[1]=='edit' && $('#pay_status').val() != 1){
	$('#payment_status').prop("disabled", true);
	}
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
				var id_branch=$('#id_branch').val();
				var id_employee=$('#id_employee').val();
              get_payment_list(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'),id_branch,id_employee)
	  $('#payment_list1').text(start.format('YYYY-MM-DD'));
	  $('#payment_list2').text(end.format('YYYY-MM-DD')); 
          }
        );   
	    if(path.route=='payment/list')
	    { 
            get_employee_list();
	        $('body').addClass("sidebar-collapse");
	          var date = new Date();
		    var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 6, 1); 
			var from_date =  firstDay.getFullYear()+'-'+(firstDay.getMonth()+1)+'-'+firstDay.getDate();
			var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
			//var to_date=(date.getFullYear()+"-"+(date.getMonth())+"-"+(date.getDate()));
			 var id_branch=$('#id_branch').val();
			get_payment_list(from_date,to_date,id_branch);
	}	
        else
		{
			$(".redeem_request").keyup(function(){
        	    if((parseFloat($(".wallet").val()) < parseFloat($(".redeem_request").val()) || parseFloat($(".redeem_request").val()) <0)){
        	    	$(".redeem_request").val($(".wallet").val()); 
        		}
        	});
		    $(".ischk_wallet_pay").on("click",function(ev){
				 // Set total amount and wallet amount 
				 var totamt = parseFloat($('#total_amt').val());
				 var can_redeem = 0; 
				 if($(".ischk_wallet_pay").is(":checked") && parseFloat($(".wallet_balance").val()) > 0){
					 var allowed_redeem =  ($('.redeem_percent').val() > 0 ? (totamt*(parseFloat($('.redeem_percent').val())/100)) : 0);
					 wallet_balance = parseFloat($('.wallet_balance').val());
					 if( allowed_redeem > wallet_balance ){
					 	can_redeem = wallet_balance;
					 }else{
					 	can_redeem = allowed_redeem;
					 }
				 } 
				 $('.wallet').val(can_redeem);$('.redeem_request').val(can_redeem);
			})
         	$(document).on('click', '#select_payrow', function(){
	$('#tableRow .select_payrow').prop('checked', $(this).prop('checked'));
	//console.log(get_selected_tablerows('tableRow'));
	});
               //load_customer_select();
	   load_schemeno_select();
	      	 //  $('#pay_date').datepicker("setDate", new Date());
	      	 if(($('#enable_editing').is(':checked'))){
	var content = '<div class="input-group date"><input type="text" class="form-control input-sm date" name="generic[date_payment]"   data-date-end-date="0d" id="pay_datetimepicker"  data-date-format="dd-mm-yyyy" /> <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span> </span> </div>';
	$('#date_payment_block').empty();
	$('#date_payment_block').append(content);
	}
	else{
	var d = new Date();
	var date = d.getFullYear()+'-'+(d.getMonth()+1)+'-'+d.getDate();
	var content = '<input type="text" class="form-control" readonly name="generic[date_payment]" value="'+date+'" />';
	$('#date_payment_block').empty();
	$('#date_payment_block').append(content);
	}
	if($('#edit_custom_entry_date').val()==0)
		{
			var d = new Date();
			var date = d.getFullYear()+'-'+(d.getMonth()+1)+'-'+d.getDate();
			var entry_date = '<div class="input-group date"><input type="text" readonly class="form-control" name="generic[entry_date]"   value='+date+'    </div>';
			$('#entry_date_payment_block').empty();
			$('#entry_date_payment_block').append(entry_date);
		}
		else
		{
			var d = new Date();
			var date = d.getFullYear()+'-'+(d.getMonth()+1)+'-'+d.getDate();
			var entry_date = '<div class="input-group date"><input type="text" class="form-control input-sm date" name="generic[entry_date]" value='+date+'  data-date-end-date="0d" id="entry_date"  data-date-format="dd-mm-yyyy" /> <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span> </span> </div>';
			$('#entry_date_payment_block').empty();
			$('#entry_date_payment_block').append(entry_date);
		}
        $('body').on('focus',"#pay_datetimepicker", function(){
        $('#pay_datetimepicker').attr("readonly",true);
        $('#pay_datetimepicker').datetimepicker({ format: 'yyyy-mm-dd hh:ii:ss',
        timezone: 'GMT'});
        });
        $('body').on('focus',"#edit_custom_entry_date", function(){
		$('#edit_custom_entry_date').attr("readonly",true);
		$('#edit_custom_entry_date').datetimepicker({ format: 'yyyy-mm-dd hh:ii:ss',
		timezone: 'GMT'});
		});
	        $("#expiry").inputmask("mm/yyyy", {"placeholder": "mm/yyyy"});
	      $("#weight").on('keyup',function(){
	calculate_total();
	  });
	 }
	 $('body').on('changeDate',"#pay_datetimepicker", function(){
	my_Date = new Date();
	var date_pay = $('#pay_datetimepicker').val();
	$("div.overlay").css("display", "block"); 
	$.ajax({
	  url:base_url+ "index.php/admin_payment/getMetalRateBydate?nocache=" + my_Date.getUTCSeconds(),
	 data: {'date_pay':date_pay},
	 dataType:"JSON",
	 type:"POST",
	 success:function(data){
	 	console.log(data);
	 	$('#metal_rate').val(data);
	 	$("input[name=weight_gold]").attr('checked',false);
	 	$('#selected_weight').val(" ");
	 	$('#total_amt').val(" ");
	 	$('#gst_amt').val(" ");
	 	$('#payment_amt').val(" ");
	 	$('#sel_wt').text("0.000");
	 	$('#rate').text(data);
	 	var amt = parseFloat($('#payamt').val());
	 	/*//GST Calculation
    	 var gst_val = 0;
    	 var gst_amt = 0;
    	 var gst = 0;
    	 if(parseFloat($('#gst_percent').val()) > 0 ){
    	 	 gst_val =parseFloat(amt)-(parseFloat(amt)*(100/(100+parseFloat($('#gst_percent').val()))));	
    	 	 gst_amt = gst_val*parseFloat($('#allowed_dues').val());
    	 	 if(parseFloat($('#gst_type').val()) == 1){
    	 	gst = gst_amt;
    	 }	 	
    	 }*/
    	 calculate_payAmt(amt);
    	total = parseFloat(parseFloat(amt) * parseFloat($('#allowed_dues').val())).toFixed(2);
        if($('#scheme_type').text() == 'Amount' || $('#scheme_type').text() == 'Amount to Weight'  ){
            $('#total_amt').val(total);
            // wallet calculation
            var can_redeem = 0;
            if($(".ischk_wallet_pay").is(":checked") && parseFloat($(".wallet_balance").val()) > 0){
            var allowed_redeem =  ($('.redeem_percent').val() > 0 ? (total*(parseFloat($('.redeem_percent').val())/100)) : 0);
            wallet_balance = parseFloat($('.wallet_balance').val());
            if( allowed_redeem > wallet_balance ){
                can_redeem = wallet_balance;
            }else{
                can_redeem = allowed_redeem;
            }
            }
            $('.wallet').val(can_redeem);
            $('.redeem_request').val(can_redeem);
            /*$('#gst_amt').val(parseFloat(gst_amt));
            $('#payment_amt').val(parseFloat(gst)+parseFloat(total));*/
        }
    	if($('#scheme_type').text() == 'Amount to Weight')
    	{
        	var total_amt = (parseFloat($('#gst_type').val()) == 1? parseFloat($('#total_amt').val()) : parseFloat($('#total_amt').val())-parseFloat(gst_amt));
        	var metal_rate = parseFloat($('#metal_rate').val());
        	if(total_amt != '' && metal_rate != ''){
        	var weight = total_amt/metal_rate;
        	$("#amttowgt").html(parseFloat(weight.toFixed(3))+' '+'<strong>'+'gm'+'</strong>');
        	}
    	}
	 	$('#weightsel_block_wt').html(data);
	   	$("div.overlay").css("display", "none"); 
	  },
	  error:function(error)  
	  {
	 $("div.overlay").css("display", "none"); 
	  }	 
	  });
	});
	 	//selected weights   
	$('#btn-payment').on('click',function(){	
	$("div.overlay").css("display", "block"); 
	});
    	$('#is_preclose').on('change',function(){
        	if($('#scheme_type').text() != 'Amount' && $('#scheme_type').text() != 'Amount to Weight'){
        	    $("input[name=weight_gold]").attr('checked',false);
        	 	$('#selected_weight').val(" ");
        	 	$('#total_amt').val(" ");
        	 	$('#payment_amt').val(" ");
        	 	$('#gst_amt').val(" ");
        	 	$('#sel_wt').text("0.000");
        	}
    	    if($(this).is(':checked'))
    	    {
    	    	//console.log($('#due_type').val());
            	$('#due_type').val('PC');
            	$('#allowed_dues').val($('#preclose').text());
            	$('#allowed_dues').prop('readonly',true);
            	$('#btn-submit').css('display', 'block');
            	$("div.overlay").css("display", "block"); 
            }
        	else{
            	$('#due_type').val($('#act_due_type').val());
            	$('#allowed_dues').val($('#act_allowed_dues').val());
            	if( $('#act_allowed_dues').val() > 0 ){
            	    $('#allowed_dues').prop('readonly',true);
            	}else{
            	    $('#allowed_dues').prop('readonly',false);
            	}
        	}
    	    var amt = parseFloat($('#payamt').val());
        	/*//GST Calculation
        	 var gst_val = 0;
        	 var gst_amt = 0;
        	 var gst = 0;
        	 if(parseFloat($('#gst_percent').val()) > 0 ){
        	 	 gst_val =  parseFloat(amt)-(parseFloat(amt)*(100/(100+parseFloat($('#gst_percent').val()))));
        	 	 gst_amt = gst_val*parseFloat($('#allowed_dues').val());	
        	 	 if(parseFloat($('#gst_type').val()) == 1){
            	 	gst = gst_amt ;
            	 }	 	
        	 }*/
        	calculate_payAmt(amt);
        	total = parseFloat(parseFloat(amt) * parseFloat($('#allowed_dues').val())).toFixed(2);
        	if($('#is_flexible_wgt').val() == 0 && $('#scheme_type').text() == 'Weight' ){
            	if( parseFloat($('#selected_weight').val()) > 0){	
                	$('#total_amt').val(total);
            	}
        	}
        	else{
            	$('#total_amt').val(total);
        	}
        	// wallet calculation
        	 var can_redeem = 0;
        	 if($(".ischk_wallet_pay").is(":checked") && parseFloat($(".wallet_balance").val()) > 0){
        		 var allowed_redeem =  ($('.redeem_percent').val() > 0 ? (total*(parseFloat($('.redeem_percent').val())/100)) : 0);
        		 wallet_balance = parseFloat($('.wallet_balance').val());
        		 if( allowed_redeem > wallet_balance ){
        		 	can_redeem = wallet_balance;
        		 }else{
        		 	can_redeem = allowed_redeem;
        		 }
        	 }
            $('.wallet').val(can_redeem);
            $('.redeem_request').val(can_redeem);
            $('#payment_amt').val(parseFloat(gst)+parseFloat(total));
            if($('#scheme_type').text() == 'Amount to Weight')
            {
                var total_amt = (parseFloat($('#gst_type').val()) == 1? parseFloat($('#total_amt').val()) : parseFloat($('#total_amt').val())-parseFloat(gst_amt));
                var metal_rate = parseFloat($('#metal_rate').val());
                if(total_amt != '' && metal_rate != ''){
                    var weight = total_amt/metal_rate;
                    $("#amttowgt").html(parseFloat(weight.toFixed(3))+' '+'<strong>'+'gm'+'</strong>');
                }
            }
	        $("div.overlay").css("display", "none"); 
	 });
	 
	// enable_editing
	$('#enable_editing').on('change',function(){	
	if(($('#enable_editing').is(':checked'))){
	var content = '<div class="input-group date"><input type="text" class="form-control input-sm date" name="generic[date_payment]"   data-date-end-date="0d" id="pay_datetimepicker"  data-date-format="dd-mm-yyyy" /> <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span> </span> </div>';
	$('#date_payment_block').empty();
	$('#date_payment_block').append(content);
	}
	else{
	var d = new Date();
	var date = d.getFullYear()+'-'+(d.getMonth()+1)+'-'+d.getDate();
	var content = '<input type="text" class="form-control" readonly name="generic[date_payment]" value="'+date+'" />';
	$('#date_payment_block').empty();
	$('#date_payment_block').append(content);
	}
	});
	$("#revert_approval").click(function(){
	if($("input[name='pay_id[]']:checked").val())
	{
	 	var selected = [];
	 	$("#payment_list tbody tr").each(function(index, value){
	$("input[name='pay_id[]']:checked").each(function() {
	if($(value).find("input[name='pay_id[]']:checked").is(":checked")){	
	clientid = $(value).find(".clientid").val();
	id_branch =  $(value).find(".id_branch").val();
	id_payment = $(this).val();
	console.log(id_branch);
	console.log(clientid);
	console.log(id_payment);	
	  selected.push({'id_payment':id_payment,'id_branch':id_branch,'clientid':clientid});	
	}	
	});
	payData = selected;
	})
	revert_approved(payData);	
	}	
   });	
});
function revert_approved(payData="")
{
	my_Date = new Date();
	 $("div.overlay").css("display", "block"); 
	$.ajax({
	  url:base_url+ "index.php/admin_payment/revertApproval_jil?nocache=" + my_Date.getUTCSeconds(),
	 data:  {'payData':payData},
	 type:"POST",
	 async:false,
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
function get_payment_list(from_date="",to_date="",id_branch="",id_employee="")
{
	my_Date = new Date();
	var type=$('#date_Select').find(":selected").val();
	$("div.overlay").css("display", "block"); 
	$.ajax({
	  url:base_url+ "index.php/payment/ajax_list?nocache=" + my_Date.getUTCSeconds(),
	 data: (from_date !='' && to_date !=''? {'from_date':from_date,'to_date':to_date,'id_branch':id_branch,'id_employee':id_employee,'date_type':type}: ''),
	 dataType:"JSON",
	 type:"POST",
	 success:function(data){
	 	$('#total_payments').text(data.data.length);
	 	console.log(data.dat);
	   	set_payment_list(data);
	   	$("div.overlay").css("display", "none"); 
	  },
	  error:function(error)  
	  {
	 $("div.overlay").css("display", "none"); 
	  }	 
	  });
}
function set_payment_list(data)
{
	 var payment = data.data;
	 var access = data.access;	
	 var profile = data.profile;
	 var oTable = $('#payment_list').DataTable();
	 	 if(access.add == '0')
	 {
	$('#add_post_payment').attr('disabled','disabled');
	 }
	     oTable.clear().draw();
	  if (payment!= null && payment.length > 0)
	  {  
	       var receipt_no_set= (typeof data.data == 'undefined' ? '' :data.data[0].receipt_no_set);
	       var entry_date=data.data[0].edit_custom_entry_date;
	  if(receipt_no_set==1 || receipt_no_set==2)
	  {
	  	oTable = $('#payment_list').dataTable({
	                "bDestroy": true,
	                "bInfo": true,
	                "bFilter": true,
	                  "bSort": true,
	                "dom": 'lBfrtip',
           			"buttons" : ['excel','print'],
				// "tableTools": { "buttons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },
	                "aaData": payment,    
	    "order": [[ 0, "desc" ]],
	                "aoColumns": [{ "mDataProp": function ( row, type, val, meta ){
	                 	if(row.id_status == 1 && row.is_offline == 0){
	chekbox=' <input type="checkbox" class="pay_status" name="pay_id[]" value="'+row.id_payment+'"  /> <input class="id_branch" type="hidden" name="id_branch" value="'+row.id_branch+'" /><input type="hidden" class="clientid" name="clientid" value="'+row.ref_no+'" />' 
	                	    return chekbox+" "+row.id_payment;
	}else{
	return row.id_payment;
	}	                	
	                  }
	                },
	                 { "mDataProp": function ( row, type, val, meta ){
	                	if(row.edit_custom_entry_date==0){
	                	return row.date_payment;
	                	}
	                	else{
	                	return row.entry_Date;
	                	}
	                }},
	                { "mDataProp": "name" },
	                { "mDataProp": "account_name" }, 
	                { "mDataProp": "code" }, 
	                { "mDataProp": function ( row, type, val, meta ){
	                	if(row.has_lucky_draw==1){
	                	return row.scheme_group_code+' '+row.scheme_acc_number;
	                	}
	                	else{
	                	return row.scheme_acc_number;
	                	}
	                }},
	                { "mDataProp": "mobile" },
	                { "mDataProp": "paid_installments" },
	                { "mDataProp": "payment_type" },
	                { "mDataProp": "payment_mode" },
	                { "mDataProp": "metal_rate" },
	                { "mDataProp": "metal_weight" },
	                 { "mDataProp": function(row,type,val,meta)
	                	{
	                	return (row.payment_type=='Payu Checkout' && row.id_status!=1 && (row.due_type=='A' || row.due_type=='P')?row.act_amount:row.payment_amount);	
	                	}
	               },
	                { "mDataProp": "payment_ref_number" },
	                { "mDataProp": function(row,type,val,meta)
	                	{return "<span class='label bg-"+row.status_color+"-active'>"+row.payment_status+"</span>";	}
	               },
	                { "mDataProp": function ( row, type, val, meta ) {
					                	 id= row.id_payment;
					                	 id_scheme_account=row.id_scheme_account;     // Get Payment page Print chked//hh
					                	 edit_url=(access.edit=='1' ? base_url+'index.php/payment/edit/'+id : '#' );
					                	 status_url = base_url+'index.php/payment/status/'+id+'/'+id_scheme_account ;
					                	 detail_url = base_url+'index.php/online/get/ajax_payment/'+id ;
					                	 printbtn_normalrecpt='';
					                	 delbtn='';
										 printbtn_thermalrecpt='';
					                  if(row.id_status=='1')  
					                  {
											print_normalurl=(access.edit=='1' ? base_url+'index.php/payment/invoice/'+id+'/'+id_scheme_account : '#' );
										 	printbtn_normalrecpt='<li><a href="'+print_normalurl+'" target="_blank" class="btn-print"><i class="fa fa-print" ></i> Print</a></li>';	
											print_thermalurl=(access.edit=='1' ? base_url+'index.php/payment/thermal_invoice/'+id+'/'+'Payment' : '#' );
											printbtn_thermalrecpt='<li><a href="'+print_thermalurl+'" target="_blank" class="btn-print"><i class="fa fa-print" ></i> ThermalPrint</a></li>';
									  }
						              else{
									   	 delete_url=(access.delete=='1' ? base_url+'index.php/payment/delete/'+id : '#' );
						                 delete_confirm= (access.delete=='1' ?'#confirm-delete':'');
						                 delbtn= '<li><a href="#" class="btn-del" data-href="'+delete_url+'" data-toggle="modal" data-target="'+delete_confirm+'"  ><i class="fa fa-trash"></i> Delete</a></li>'
									    }
					                	 action_content='<div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button><ul class="dropdown-menu">'+
					    '<li><a href="'+edit_url+'" class="btn-edit"><i class="fa fa-edit" ></i> Edit</a></li><li><a href="#" class="btn-edit" onClick="transaction_detail('+id+')"><i class="fa fa-eye" ></i> Detail</a></li>'+delbtn+
					    '<li><a href="'+status_url+'" class="btn-edit"><i class="fa fa-search-plus" ></i> Status</a></li>'+printbtn_normalrecpt+' '+printbtn_thermalrecpt+'</ul></div>';
					                	return action_content;
					                	}
	            },
				 { "mDataProp": "emp_code" },
				 { "mDataProp": function ( row, type, val, meta ) {

					                	

					                	return (row.added_by=='0'?"Admin":(row.added_by=='1'?"Web":(row.added_by=='5'?"Offline":"Mobile")));



					                	}}
				 ], 
	"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
	                  if(aData['payment_type']=='Payu Checkout'){	 
	                  switch(aData['due_type'])
	  {
	     case 'A':
	        if(aData['id_status']==2 || aData['id_status']==7)
	$(nRow).css('color', '#e71847');
	   break;
	 case 'P':
	 	 if(aData['id_status']==2 || aData['id_status']==7)
	$(nRow).css('color', '#e71847');
	   break;
	  }
	 }
	}
	            });	  	 	
	  	 }	
	 else{	 	
	  	oTable = $('#payment_list').dataTable({
	                "bDestroy": true,
	                "bInfo": true,
	                "bFilter": true,
	                "dom": 'lBfrtip',
           			"buttons" : ['excel'],
	                "tableTools": { "aButtons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },
	                "aaData": payment,    
	    "order": [[ 1, "desc" ]],	
	'columnDefs': [{
	 'targets': 0,
	 'searchable':false,
	 'orderable':false,
	 "bSort": true,
	 'className': 'dt-body-center',
	  }],
	                "aoColumns": [
	{ "mDataProp": function ( row, type, val, meta ) {
	if(row.scheme_acc_number!='Not Allocated' && (row.receipt_no_set=='0' && (row.receipt_no==null ||row.receipt_no=='' ) && row.id_status=='1')){
	return '<input type="checkbox" id="select_ids_'+row.id_payment+'" class="select_ids"  value="'+row.id_payment+'">';
	}else{	
	  	return null;
	  }
	                	}
	                }, 
	  { "mDataProp": "id_payment" },
	                    { "mDataProp": function ( row, type, val, meta ){
	                	if(row.edit_custom_entry_date==0){
	                	return row.date_payment;
	                	}
	                	else{
	                	return row.entry_Date;
	                	}
	                }},
	                { "mDataProp": "name" },
	                { "mDataProp": "account_name" },
	                { "mDataProp": function ( row, type, val, meta ){
	                	return row.code;
	                	}
	                },
	                  { "mDataProp": function ( row, type, val, meta ){
	                	if(row.has_lucky_draw==1){
	                	return row.scheme_group_code+' '+row.scheme_acc_number;
	                	}
	                	else{
	                	return row.code+' '+row.scheme_acc_number;
	                	}
	                }},
	                { "mDataProp": "mobile" },
	                { "mDataProp": "paid_installments" },
	                { "mDataProp": "payment_type" },
	                { "mDataProp": "payment_mode" },
	                { "mDataProp": "metal_rate" },
	                { "mDataProp": "metal_weight" },
	                 { "mDataProp": function(row,type,val,meta)
	                	{
	                	return (row.payment_type=='Payu Checkout' && row.id_status!=1 && (row.due_type=='A' || row.due_type=='P')?row.act_amount:row.payment_amount);	
	                	}
	               },
	                { "mDataProp": "payment_ref_number" },
	{ "mDataProp": function ( row, type, val, meta )
	    {
	if(row.scheme_acc_number!='Not Allocated' && (row.receipt_no_set=='0' && (row.receipt_no==null||row.receipt_no=='') && row.id_status=='1')){	
	return '<input  type="text"  id="receipt_no" class="receiptno"  disabled="true" value="">';}
	else{
	    return row.receipt_no; } 
	      }
	                },	
	                { "mDataProp": function(row,type,val,meta)
	                	{return "<span class='label bg-"+row.status_color+"-active'>"+row.payment_status+"</span>";	}
	               },
	                { "mDataProp": function ( row, type, val, meta ) {
	                	 id= row.id_payment;
						 is_print_taken= row.is_print_taken;
	                	 id_scheme_account=row.id_scheme_account;
	                	 // console.log(id1);
	                	 edit_url=(access.edit=='1' ? base_url+'index.php/payment/edit/'+id : '#' );
	                	  status_url = base_url+'index.php/payment/status/'+id+'/'+id_scheme_account ;
	                	 detail_url = base_url+'index.php/online/get/ajax_payment/'+id ;
	                	 printbtn='';
	                	 delbtn='';
	                  if(row.id_status=='1')  
	                  {
	                  	//if(row.receipt=='0'){
	  print_url=(access.edit=='1' ? base_url+'index.php/payment/invoice/'+id+'/'+id_scheme_account : '#' );
	 	printbtn='<li><a href="'+print_url+'" target="_blank" class="btn-print"><i class="fa fa-print" ></i> Print</a></li>';
	/*}else{
	 printbtn='<li><a href="#" onclick="get_print_data('+id+')" class="custom_print"><i class="fa fa-print" ></i> Print</a></li>';
	}*/
	  }
	              else{
	   	 delete_url=(access.delete=='1' ? base_url+'index.php/payment/delete/'+id : '#' );
	                 delete_confirm= (access.delete=='1' ?'#confirm-delete':'');
	                 delbtn= '<li><a href="#" class="btn-del" data-href="'+delete_url+'" data-toggle="modal" data-target="'+delete_confirm+'"  ><i class="fa fa-trash"></i> Delete</a></li>'
	    }
	                	 action_content='<div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button><ul class="dropdown-menu">'+
	    '<li><a href="'+edit_url+'" class="btn-edit"><i class="fa fa-edit" ></i> Edit</a></li><li><a href="#" class="btn-edit" onClick="transaction_detail('+id+')"><i class="fa fa-eye" ></i> Detail</a></li>'+delbtn+
	    '<li><a href="'+status_url+'"  class="btn-edit"><i class="fa fa-search-plus" ></i> Status</a></li>'+(is_print_taken==0|| profile==2 || profile==1 ? printbtn : ''  )+'</ul></div>';
	                	return action_content;
	                	}
	            },
				  { "mDataProp": "emp_code" },
				  { "mDataProp": function ( row, type, val, meta ) {

					                	

					                	return (row.added_by=='0'?"Admin":(row.added_by=='1'?"Web":(row.added_by=='5'?"Offline":"Mobile")));



					                	}}], 
	"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
	                  if(aData['payment_type']=='Payu Checkout'){	 
	                  switch(aData['due_type'])
	  {
	     case 'A':
	        if(aData['id_status']==2 || aData['id_status']==7)
	$(nRow).css('color', '#e71847');
	   break;
	 case 'P':
	 	 if(aData['id_status']==2 || aData['id_status']==7)
	$(nRow).css('color', '#e71847');
	   break;
	  }
	 }
	}
	            });	  	 	
	  	 }
	  	 if(showExport == 0){
             $(".dt-buttons").css("display","none");
    	 }
	 }	
}
/*  Receipt number  manual enrty */
  var selectdatas =[];
$(document).on('click', '#select_recpt', function(e){	
	 if($(this).prop("checked") == true){
                $("tbody tr td input[type='checkbox']").prop('checked',true);
	$(".receiptno").attr('disabled', false);
            }
            else if($(this).prop("checked") == false)
	{
	$(".receiptno").val('');
	$(".receiptno").attr('disabled', true);
	$("tbody tr td input[type='checkbox']").prop('checked', false);
            }
});
$(document).on('click', '.select_ids', function(e){
 $("#payment_list tbody tr").each(function(index, value) 
	{	
	 if(!$(value).find(".select_ids").is(":checked"))
	 { 
	$(value).find(".receiptno").empty();	
	$(value).find(".receiptno").attr('disabled', true);
	$(value).find(".receiptno").val('');
	}
	else if($(value).find(".select_ids").is(":checked"))
	 { 	
	$(value).find(".receiptno").attr('disabled', false);
	}
      });
});
 var selected = [];
$(document).on('click', '.conform_recpt', function(e){
   $("#payment_list tbody tr").each(function(index, value) 
	{
	 if(!$(value).find(".select_ids").is(":checked"))
	 { 
	$(value).find(".receiptno").empty();	
	$(value).find(".receiptno").attr('disabled', true);
	 }
	    else if(($(value).find(".select_ids").is(":checked") && $(value).find(".receiptno").val()!=''
	)){
	$("#conform_save").attr('disabled', true);
	  $(value).find(".receiptno").attr('disabled', false);
	   var id_payment=$(value).find(".select_ids").val();
	   var scheme_acc_number=$(value).find(".receiptno").val();	   
	   var data = {'id_payment':id_payment, 'receipt_no':scheme_acc_number}; 	  
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
	  url:base_url+ "index.php/receipt_number/update",
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
/*  Receipt number  manual enrty */
function load_customer_select()
{
	my_Date = new Date();
	//show spinner
	$('.overlay').css('display','block');
	$.ajax({
	  type: 'GET',
	  url:  base_url+'index.php/customer/get_customers?nocache=' + my_Date.getUTCSeconds(),
	  dataType: 'json',
	  cache:false,
	success: function(data) {
	      if($('#customer').length>0)
	     {
	 	$.each(data, function (key, cus) {
	$('#customer').append(
	$("<option></option>")
	  .attr("value", cus.id)
	  .text(cus.mobile+" "+cus.name)	  
	);
	});
	$("#customer").select2({
	  placeholder: "Enter Mobile Number",
	    allowClear: true
	});	
	$("#customer").select2("val", ($('#id_customer').val()!=null?$('#id_customer').val():''));
	 }
	 //disable spinner
	$('.overlay').css('display','none');
	},
	  	error:function(error)
	{
	console.log(error);
	//disable spinner
	$('.overlay').css('display','none');
	}	
	 }); 	
}
	$('#branch_select').on('change',function(e){
	if(this.value!='')
	{
		$("#id_branch").val(this.value);
	}
	else
	{
		$("#id_branch").val('');
	}
	});
 $('#customer').select2().on("change", function(e) {
          //console.log("change val=" + this.value);
      if(this.value!='')
      {
      	 $("#id_customer").val(this.value);
      	 my_Date = new Date();
      	 //load customer schemes
	//show spinner
	$('.overlay').css('display','block');
	$.ajax({
	  type: 'GET',
	  url:  base_url+'index.php/payment/get/ajax/customer/account/'+this.value+'?nocache=' + my_Date.getUTCSeconds(),
	  dataType: 'json',
	  cache:false,
	success: function(data) {
				if($('#scheme_account').length>0)
				     {
				     	$('#scheme_account').empty();
					 	$.each(data.accounts, function (key, acc) {
							$('#scheme_account').append(
								$("<option></option>")
								  .attr("value", acc.id_scheme_account)
								  .text(acc.scheme_acc_number)
							);
						});
						$(".eligible_walletamt").css("display","none");
						if(data.wallet_balance){
							console.log(data.wallet_balance);
							$('.wallet_balance').val(parseFloat(data.wallet_balance.wal_balance));
							$('.redeem_percent').val(parseFloat(data.wallet_balance.redeem_percent));
							if($('.wallet_balance').val()!='0'){ 
								$(".eligible_walletamt").css("display","block"); 
							} 
							$('.wallet').val(parseFloat(data.wallet_balance.wal_balance));
						}
						$("#scheme_account").select2({
						  placeholder: "Select scheme account",
						    allowClear: true
						});		
						$("#scheme_account").select2("val", ($('#id_scheme_account').val()!=null?$('#id_scheme_account').val():''));
					 }
				 //disable spinner
				$('.overlay').css('display','none');
	},
	  	error:function(error)
	{
	console.log(error);
	//disable spinner
	$('.overlay').css('display','none');
	}	
	 }); 
	  }
	  else
	  {
	  	$("#scheme_account").select2("val",'');
	  	$('#scheme_account').empty();
	  }
   });
function load_schemeno_select(id_scheme='')
{
	my_Date = new Date();
	//show spinner
	$('.overlay').css('display','block');
	$.ajax({
	  type: 'POST',
	  data:{'id_scheme':id_scheme},
	  url:  base_url+'index.php/payment/get/ajax_data?nocache=' + my_Date.getUTCSeconds(),
	  dataType: 'json',
	   cache:false,
	  success: function(data) {
	  	console.log($('#scheme_account option').length);
	 if(ctrl_page[1]=='edit'){	
	$('#scheme_account').prop('disabled', true);
	} 
	 	$.each(data.account, function (key, acc) {
				$('#scheme_account').append(
				$("<option></option>")
				  .attr("value", acc.id_scheme_account)
				  .text(acc.scheme_acc_number)
				);
		});
	$("#scheme_account").select2({
	  placeholder: "Select scheme account",
	    allowClear: true
	});	
	$("#scheme_account").select2("val", ($('#id_scheme_account').val()!=null?$('#id_scheme_account').val():''));
	    if($('#pay_mode').length)
	    {
	$.each(data.mode, function (key, mode) {
	   if( mode.mode_name!='ECS')
	   {
	   	$('#pay_mode').append(
	$("<option></option>")
	  .attr("value", mode.short_code)
	  .text(mode.mode_name)
	);
	   }	
	});
	if(data.mode.length == 0){
		var payment_mode = '';
	}else{
		var payment_mode = data.mode[0].short_code;	
	}
	$("#pay_mode").select2({
	    placeholder: "Select payment mode",
	    allowClear: true
	});
	$("#pay_mode").select2("val", payment_mode);
	}
	  if($('#payment_status').length)
	  {
	  	$.each(data.payment_status, function (key, pay) {
	   	$('#payment_status').append(
	$("<option></option>")
	  .attr("value", pay.id_status_msg)
	  .text(pay.payment_status)
	);
	});
	if(data.payment_status.length == 0){
		var payment_status = '';
	}else{
		var payment_status = data.payment_status[0].id_status_msg;	
	}
	$('#pay_status').val(payment_status); 
	$("#payment_status").select2({
	    placeholder: "Select payment status",
	    allowClear: true
	});
	$("#payment_status").select2("val", ($('#pay_status').val()!=null?$('#pay_status').val():''));
	  }
	      if($('#payee_bank').length)
	      {
	  	$.each(data.bank, function (key, item) {	  	
	   	$('#payee_bank').append(
	$("<option></option>")
	  .attr("value", item.id_bank)
	  .text(item.bank_name)
	);
	});
	$("#payee_bank").select2({
	    placeholder: "Select payee bank",
	    allowClear: true
	});
	$("#payee_bank").select2("val", '');
	  }
	  if($('#payment_status').length)
	  {
	  	  $.each(data.drawee, function (key, bank) {
	$('#drawee_acc_no').append(
	$("<option></option>")
	  .attr("value", bank.id_drawee)
	  .text(bank.account_no)
	);
	});
	$("#drawee_acc_no").select2({
	  placeholder: "Select account number",
	    allowClear: true
	});	
	$("#drawee_acc_no").select2("val", ($('#id_drawee_bank').val()!=null?$('#id_drawee_bank').val():''));
	  }
	//get rate from api
	get_rate();
	//disable spinner
	$('.overlay').css('display','none');
	},
	error:function(error)
	{
	console.log(error);
	//disable spinner
	$('.overlay').css('display','none');
	}	
	  });	
}
 //on selecting drawee account
   $('#drawee_acc_no').select2()
        .on("change", function(e) {
          //console.log("change val=" + this.value);
          if(this.value!='')
          {
          	 $("#id_drawee_bank").val(this.value);
	  	 get_drawee_detail(this.value);
	  }
   });  
    $('#payment_status').select2()
        .on("change", function(e) {
          //console.log("change val=" + this.value);
          if(this.value!='')
          {
          	 $("#pay_status").val(this.value);
	  }
   });  
   $('#pay_mode').select2()
        .on("change", function(e) {
          //console.log("change val=" + this.value);
          if(this.value!='')
          {
          	 $("#payment_mode").val(this.value);
	  }
   });
 $('.weight').select2()
        .on("change", function(e) {
     console.log(1);
});
  //to get drawee detail
  function get_drawee_detail(id)
  {
  	my_Date = new Date();
  	$.ajax({
	  type: 'GET',
	  url:  base_url+'index.php/settings/drawee/ajax_list/'+id+'?nocache=' + my_Date.getUTCSeconds(),
	  dataType: 'json',
	   cache:false,
	  success: function(data) {
	  	$('#drawee_bank').val(data.data.bank_name);
	  	$('#drawee_bank_branch').val(data.data.branch);
	  	$('#drawee_ifsc').val(data.data.ifsc_code);
	  }
	});  	
  } 
if(ctrl_page[1]=='status'){
	$("#id_scheme_account").val();
    // load_account_detail($("#id_scheme_account").val());
     load_account(ctrl_page[2],ctrl_page[3]);
}
else{
				 $('.overlay').css('display','block');
//get account detail on change
  $('#scheme_account').select2()
        .on("change", function(e) {
          //console.log("change val=" + this.value);
          if(this.value!='')
          {
          	 $("#id_scheme_account").val(this.value);
			load_account_detail(this.value);
			// get_branchnames();
	  }
	  else
	  {
	  	clear_account_detail();
	  }
   });
}
 $('#pay_mode').select2()
    .on("change", function(e) {
     if(this.value=='CSH')
     {
     	$('.Cash-container').css('display','none');
     }	
     else
     {
	 	$('.Cash-container').css('display','block');
	 }
});
//get rate   
function get_rate()
{
	my_Date = new Date();
	var baseURL = base_url.replace('admin/','');
	$.ajax({
	type: "GET",
	url: baseURL+"api/rate.txt"+"?nocache=" + my_Date.getUTCSeconds(),
	dataType: "json",
	cache: false,
	success: function(data) {
	   var currentRate = data.goldrate_22ct;
 	   $("#metal_rate").val(currentRate);
	}
	});
} 
//get weights   
function get_weight(element,eligible)
{
	my_Date = new Date();
	$.ajax({
	type: "GET",
	url: base_url+"index.php/settings/weight_list?nocache=" + my_Date.getUTCSeconds(),
	dataType: "json",
	cache: false,
	success: function(data) {
	  var weights = data.data;
	  if(weights!='')
	  {
	  	$.each(weights,function(key,weight){
	  	if(weight.weight <= eligible)
	  	{
	$('#'+element).append(
	$("<option></option>")
	  .attr("value", weight.weight)
	  .text(weight.weight)
	);
	}
	});
	$("#"+element).select2({
	  placeholder: "Select weight",
	    allowClear: true
	});	
	$("#"+element).select2("val", '');
	  }
	}
	});
} 
 //to get account detail //
 //  Depends on cost center settings, have to make the branch selection. [select the scheme accounts branch and make the field readonly] //HH
 
 function load_account_detail(id)
 {
     
       $('#select_branch').empty();
	  
 	my_Date = new Date();
	//show spinner
	$('.overlay').css('display','block');
 	$.ajax({
        type: 'GET',
        url:  base_url+'index.php/payment/get/ajax/account/'+id+'?nocache=' + my_Date.getUTCSeconds(),
        dataType: 'json',
        cache:false,
	    success: function(data) {
	          $('#select_branch').empty();
	  
            $('#id_scheme_account').val((data.account.id_scheme_account));
            $('#id_customer').val((data.account.id_customer));
            $('#mobile_number').val((data.account.mobile));
   console.log(data.account.cost_center);
     if(data.account.cost_center==1 || data.account.cost_center==2)
    	{     
            $('#id_branch').val((data.account.id_branch));
         $("#select_branch").attr("disabled", true); 
   // console.log(data.account.id_branch);
        		 var id_branch =  $('#id_branch').val();		   
        	 	$.each(data, function (key, item) {					  				  			   		
            	 	$('#select_branch').append(						
            	 	$("<option></option>")						
            	 	.attr("value", item.id_branch)						  						  
            	 	.text(item.name)						  					
            	 	);			   											
             	});
             		$("#select_branch").select2({
	    placeholder: "Select branch name",
	    allowClear: true
	});
	  //	$("#select_branch").select2("val", id_branch);
	  	
    	}
	  	    account_detail_view(data.account)
            $.AdminLTE.boxWidget.activate();
            $('.overlay').css('display','none');
            
            
	 	 },
	 	 
	 	 
	 	 	 /*   success: function(data) {
            $('#id_scheme_account').val((data.account.id_scheme_account));
            $('#id_customer').val((data.account.id_customer));
            $('#mobile_number').val((data.account.mobile));
          
            $('#branch_select').val(data.account.sch_join_branch);
            account_detail_view(data.account)
            $.AdminLTE.boxWidget.activate();
            $('.overlay').css('display','none');
            
            
	 	 },*/
	 	 
        error:function(error)
        {
            console.log(error);
            //disable spinner
            $('.overlay').css('display','none');
        }
	});	 	
 }
 //  Depends on cost center settings, have to make the branch selection. [select the scheme accounts branch and make the field readonly] //
 
 function load_account(id,id_scheme_account)
 {
 	 my_Date = new Date();
	//show spinner
	$('.overlay').css('display','block');
 	$.ajax({
	  type: 'GET',
	  data:{'id_payment':id,'id_sch_ac':id_scheme_account},
	  url:  base_url+'index.php/admin_payment/ajax_load_account',
	  dataType: 'json',
	   cache:false,
	  success: function(data) {
	  	           account_detail_view(data.account)
	  	            $.AdminLTE.boxWidget.activate();
	  	           $('.overlay').css('display','none');
	 	 },
	error:function(error)
	{
	console.log(error);
	//disable spinner
	$('.overlay').css('display','none');
	}
	 	  });	 	
 }  
 function clear_account_detail()
 {
    $("#error-msg").html("");
 	$("#start_date").html("");
 	$("#acc_name").html("");
 	$("#disable_pay_reason").html("");
 	$("#disable_payment").html("");
	$("#scheme_code").html("");
	$("#scheme_type").html("");
	$("#payable").html("");
	$("#paid_installments").html("");
	$("#total_amount_paid").html("");
	$("#total_weight_paid").html("");
	$("#total_amt").val("");
	$("#gst_amt").val("");
	$("#payment_amt").val("");
	$("#payment_weight").val("");
	$(".hidden_allow").css('display','none');
	$("#last_paid_date").html("");
	$("#unpaid_dues").html("");
	$("#total_pdc").html("");
	$("#allow_pay").html("");
	$("#is_preclose").val(0);
	$("#payment_container").html("");
	$('#scheme-detail-box').removeClass('box-success');
	$('#scheme-detail-box').removeClass('box-danger');
	$('#scheme-detail-box').addClass('box-default');
	$("#fix_weight").val("");
	$("#is_flexible_wgt").val("");
	$("#wgt_cvrt").val("");
	$("#sch_amt").val("");
	$("#firstPayamt_maxpayable").val("");
	$("#firstPayment_amt").val("");
	$("#sch_type").val("");
	$("#flexible_sch_type").val("");
	$("#metal_wgt_roundoff").val("");
	$("#metal_wgt_decimal").val("");
	$("#total_installments").val("");
	$("#select_branch").val("");
	
 }    
 //to load account detail view
    function account_detail_view(data)
    {	
      //$("#overlay").css("display","none");
 	 	clear_account_detail();	 
    	$('#max_dues').val(data.allowed_dues); 
    	$('#pay').val(data.payable);
    	var table="";
    	maximum_weight = 0;
    	if(data.due_type=='AN'&&data.allowed_dues>1)
    	{
    	  	var allowed_dues =1;
    	    $('#allowed_dues').prop('readonly',false);
    	}
    	else
    	{
    	    var allowed_dues =parseInt(data.allowed_dues);
    	    $('#allowed_dues').prop('readonly',true);
    	}
        var allowed_dues =1;
        var schID = $("#id_scheme_account").val();
        $('#discount_type').val(data.discount_type);
        $('#discount_installment').val(data.discount_installment);
        $("#flexible_sch_type").val(data.flexible_sch_type); 
        $("#sch_type").val(data.scheme_type); 
        $("#maturity_type").val(data.maturity_type);
        $("#total_installments").val(data.total_installments);
        $('#discount').val(data.discount);
        $('#firstPayDisc_value').val(data.firstPayDisc_value);
        $('#cost_center').val(data.cost_center);
        console.log(data.cost_center);
       if(data.cost_center==1 || data.cost_center==2)
    	{  
        $('#select_branch').val(data.id_branch);
        var id_branch=$('#id_branch').val();
    	}
        $('#metal_rate').val(data.metal_rate);
        $("#id_scheme_account").val(data.id_scheme_account);
        
        var discount_installment=$('#discount_installment').val();
        var discount_type=$('#discount_type').val();
        var discount=$('#discount').val();
        var paid_installments=$('#paidinstall').val();
        var firstPayDisc_value=$('#firstPayDisc_value').val();
        var one_time_premium=$('#one_time_premium').val(data.one_time_premium);
        $("#metal_wgt_roundoff").val(data.metal_wgt_roundoff); 
        $("#metal_wgt_decimal").val(data.metal_wgt_decimal); 
    	 if(discount_type==0)
    	 {
    		 $('#discountedAmt').val(data.firstPayDisc_value);
    	 }
    	 else if(discount_installment==(paid_installments+1))
    	 {
    		 $('#discountedAmt').val(data.firstPayDisc_value);
    	 }
    	 else
    	 {
    		 $('#discountedAmt').val('');
    	 }
     	 if(schID!='')
    	 {	
	 	    if(data.allow_pay == 'Y')
	 	    {
            	$('#scheme-detail-box').addClass('box-success');
            	$("#allow_pay").html("<span class='label label-success'>Yes</span>");
            	$('#payment_container').html("<table id='tableHead' class='table table-bordered'></table><table id='tableRow' class='table table-bordered'></table>");
            	$('#btn-submit').css('display', 'block');
        	}
        	else
        	{
            	$('#btn-payment').prop('disabled', true);
            	$("#allow_pay").html("<span class='label label-danger'>No</span>");
            	$('#scheme-detail-box').addClass('box-danger');
            	$('#btn-submit').css('display', 'none');
        	}
        	if(data.scheme_type == 0 || data.scheme_type == 2 ) // AMOUNT , AMOUNT TO WEIGHT
            {
            	$('#total_amt').prop('readonly',true);
            	$('#proced').css("display", 'none');
            	$('#enable_editing_blk').css("display", 'block');
            	$("#payable").html("<strong>"+data.currency_symbol+"</strong> "+parseFloat(data.payable).toFixed(2));
            	$("#total_amount_paid").html("<strong>"+data.currency_symbol+"</strong> "+parseFloat(data.total_paid_amount).toFixed(2));
        	      //draw_payment_table(data);
        	    var amount= parseFloat(data.payable).toFixed(2) * parseFloat(allowed_dues).toFixed(2)-parseFloat((discount==1 ?(discount_type==0 ?firstPayDisc_value :(discount_installment==(paid_installments+1)?firstPayDisc_value :0.00)) :0.00));
                console.log(amount);
        	    $('#total_amt').val(amount);
        	    $('#payamt').val(data.payable);
        	    $('.hidden_allow').css('display','block');
                /*	if(allowed_dues >1)
            	{
            	$('#allowed_dues').prop('readonly',false);
            	amount = parseFloat(data.payable).toFixed(2) * parseFloat(allowed_dues).toFixed(2)-parseFloat((discount==1 ?(discount_type==0 ?firstPayDisc_value :(discount_installment==paid_installments ?firstPayDisc_value :0.00)) :0.00));
            	$('#total_amt').val(amount);
            	}  */
            	$('#payment_container').html('');
            	var pending_dues = parseInt(data.total_installments - data.paid_installments);
            	if(data.preclose ==1 && parseInt(data.preclose_benefits)== pending_dues)
            	{
                	allowed_dues=parseInt(data.preclose_benefits);
                	amount = parseFloat(data.payable).toFixed(2) * parseFloat(allowed_dues).toFixed(2)-parseFloat((discount==1 ?(discount_type==0 ?firstPayDisc_value :(discount_installment==paid_installments ?firstPayDisc_value :0.00)) :0.00));
                	$('#total_amt').val(amount);
            	}
        	    // wallet calculation
        	    var total_amount = parseFloat($('#total_amt').val());
        	    var can_redeem = 0;
        	    if($(".ischk_wallet_pay").is(":checked") && parseFloat($(".wallet_balance").val()) > 0){
            		 var allowed_redeem =  ($('.redeem_percent').val() > 0 ? (total_amount*(parseFloat($('.redeem_percent').val())/100)) : 0);
            		 wallet_balance = parseFloat($('.wallet_balance').val());
            		 if( allowed_redeem > wallet_balance ){
            		 	can_redeem = wallet_balance;
            		 }else{
            		 	can_redeem = allowed_redeem;
            		 }
        	    }
            	 $('.wallet').val(can_redeem);
            	 $('.redeem_request').val(can_redeem);
            	 /*//GST Calculation
            	 var gst_val = 0;
            	 var gst_amt = 0;
            	 var gst = 0;
            	 if(data.gst > 0 ){
            	 	 gst_val = parseFloat(data.payable)-(parseFloat(data.payable)*(100/(100+parseFloat(data.gst))));
            	 	 gst_amt = gst_val*allowed_dues;
            	 	 if(data.gst_type == 1){	 	
            	 	gst = gst_amt ;
            	 }	
            	 }*/
            	 calculate_payAmt(data.payable);
            	 /*$('#gst_amt').val(gst_amt);
            	 $('#payment_amt').val(parseFloat(gst)+parseFloat(amount));*/
    	    } 
    	    else if(data.scheme_type == 3 && (data.flexible_sch_type != 4 || data.firstPayment_wgt > 0))
            {
            	$('#total_amt').prop('readonly',false);
            	if(data.paid_installments>0 && data.one_time_premium==1)
        		{
        		    msg='<div class = "alert alert-warning"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong>  You have already paid '+data.total_paid_amount+'</strong></div>';
        		    $('#error-msg').html(msg);
        		    $('#proced').css("display", 'none');
        		    $('#total_amt').css("readonly", true);
        		}
        		else if(data.current_chances_used == data.max_chance && data.min_chance > data.max_chance){
        		    msg='<div class = "alert alert-warning"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong>  You have already reached max payment chance ['+data.max_chance+']</strong></div>';
        		    $('#error-msg').html(msg);
        		    $('#proced').css("display", 'none');
        		    $('#total_amt').css("readonly", true);
        		}
        		else if(data.max_amount == 0){
        		    msg='<div class = "alert alert-warning"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong>  You have already reached max payment amount '+data.current_total_amount+'</strong></div>';
        		    $('#error-msg').html(msg);
        		    $('#proced').css("display", 'none');
        		    $('#total_amt').css("readonly", true);
        		}
        		else{
        		    console.log(data.get_amt_in_schjoin);
        		    if(data.flexible_sch_type <= 2 && data.get_amt_in_schjoin !=1){
        		       msg='<div class = "alert alert-info"><a href = "#" class = "close" data-dismiss = "alert">&times;</a> Min '+data.min_amount+'  Max '+data.max_amount+'</strong></div>';
        		    }
        		    else if(data.flexible_sch_type == 3){
        		       msg='<div class = "alert alert-info"><a href = "#" class = "close" data-dismiss = "alert">&times;</a> Min '+data.min_weight+'  Max '+data.max_weight+'</strong></div>'; 
        		    }
        		    else if(data.flexible_sch_type == 4){
        		       msg='<div class = "alert alert-info"><a href = "#" class = "close" data-dismiss = "alert">&times;</a> Min '+data.min_weight+'  Max '+data.max_weight+'</strong></div>'; 
        		    }
        		    else if(data.flexible_sch_type <= 4 && data.get_amt_in_schjoin ==1){  // firstPayment_amt get from customer based on the scheme settings//HH
        		       msg='<div class = "alert alert-info"><a href = "#" class = "close" data-dismiss = "alert">&times;</a> You have already Fixed  '+data.firstPayment_amt+'</strong></div>';
        		    $('#total_amt').prop('readonly',true);
        		        
        		    }
        		    $('#error-msg').html(msg); 
        		    if(data.firstPayment_amt > 0 || data.firstPayment_wgt > 0){
        		        if(data.firstPayment_amt > 0){
        		            calculate_payAmt(data.firstPayment_amt);
        		            $('#total_amt').val(data.firstPayment_amt);
        		        }else if(data.firstPayment_wgt > 0){
        		            var totAmt = data.firstPayment_wgt*$('#metal_rate').val();
        		            calculate_payAmt(totAmt);
        		            $('#total_amt').val(totAmt);
        		        }
        		        if(data.allow_pay == 'Y'){
							$("#btn-submit").css("display", "block"); 
						}
            	        $('#proced').css("display", 'none');
            	        $('#total_amt').css("readonly", true);
        		    }else{
        		        $('#total_amt').val(data.min_amount);
        		        calculate_payAmt(data.min_amount);
        		        $('#proced').css("display", 'block');
        		        $("#btn-submit").css("display", "none");
        		    }
        		} 
                $("div.overlay").css("display", "none"); 
                //stop the form from submitting
            	$('#enable_editing_blk').css("display", 'block');
            	
            	if(data.flexible_sch_type == 1 || data.flexible_sch_type == 2){
            		$("#payable").html("<strong>"+data.currency_symbol+"</strong> "+parseFloat(data.payable).toFixed(2));
            	}else{
            		$("#payable").html("Max "+parseFloat(data.payable).toFixed(3)+" g/month");
            	}
            	$("#total_amount_paid").html("<strong>"+data.currency_symbol+"</strong> "+parseFloat(data.total_paid_amount).toFixed(2));
            	$("#total_weight_paid").html(parseFloat(data.total_paid_weight)+" <strong>gm</strong>");
            	// Set Payable weight
            	/*if(data.firstPayment_wgt > 0){
            	    var installment_amt = $('#metal_rate').val()*data.firstPayment_wgt;
                    $('#total_amt').val(installment_amt);
            	    $('#total_amt').prop('readonly',true);
            	    calculate_payAmt(installment_amt);
            	}
            	if(data.firstPayamt_as_payamt==1 && data.flexible_sch_type!=3)
            	{
                    $('#total_amt').prop('readonly',false);
                    $('#total_amt').val(data.payable);
                    $('#payment_amt').val(data.payable);
            	}
            	if(data.firstPayment_amt > 0){
            	    $('#total_amt').val(data.firstPayment_amt);
            	}*/
            	$('.hidden_allow').css('display','block');
                if(allowed_dues > 1){
                    $('#allowed_dues').prop('readonly',false);
                }
                
            	$("#total_amt").on('change',function()
            	{
            		var amt=$('#total_amt').val();
            		if( (amt%(data.flx_denomintion)!=0 ) && data.flexible_sch_type!=4)
    				{
    					alert('Please Enter a amount in  multiples of '+data.flx_denomintion+'');
    					$("#total_amt").val('');
    					$("#btn-submit").css("display", "none"); 
    				}
    				else
    				{
    					$('#proced').css("display", 'block');
    				}
            	});
        	
            	 $( "#proced" ).on( "click", function(event) {
                	if($("#total_amt").val() != "")
                	{
                	    var amt = $("#total_amt").val();
                	}  
                	$('#payamt').val(amt);
                    var metal_rates=$("#metal_rate").val();
                    var sel_due = parseFloat($('#sel_due').val());
                    var amount = amt;
                    /*//GST Calculation
                    var gst_val = 0;
                    var gst_amt = 0;
                    var weight	 = 0;
                    var wight_amount	 = 0;
                    var metal_weights	 = 0;
                    var gst = 0;
                	if(data.gst > 0 ){ 
                	    if(parseFloat($('#gst_type').val()) == 1){ // Exclusive
                            gst = parseFloat(amount)*(parseFloat($('#gst_percent').val())/100);
                            $("#gst_amt").val(gst);
                            metal_weights = parseFloat(amount)/parseFloat(metal_rates);
                        }	
                        if(parseFloat($('#gst_type').val()) == 0){ // Inclusive
                            gst = parseFloat(amount)-(parseFloat(amount)*(100/(100+parseFloat($('#gst_percent').val()))));
                            metal_weights = parseFloat(amount)/parseFloat(metal_rates*(parseFloat($('#gst_percent').val())/100));
                            console.log(parseFloat($('#gst_percent').val())/100);
                            console.log(metal_rates+'--'+amount);
                        }
                	}else{
                	    metal_weights = parseFloat(amount)/parseFloat(metal_rates);
                	}
                	var metal_weight_cal= metal_weights; */
                	if(amount >= parseFloat(data.min_amount) && amount <= parseFloat(data.max_amount) && parseFloat(data.max_chance) > parseFloat(data.current_chances_use))
                	{ 
                        msg='<div class = "alert " style="background-color:green; color:white;"><a href = "#" class = "close" data-dismiss = "alert">&times;</a> Sucess Click Save </div>';
                        $("div.overlay").css("display", "none"); 
                        //stop the form from submitting
                        $('#error-msg').html(msg);
                        $("div.overlay").css("display", "none"); 
                        $("#btn-submit").css("display", "block"); 
                        calculate_payAmt(amount/sel_due); 
                	} 
                	else{	
                        //var  Eligible_pay = data.firstPayamt_maxpayable==1 && data.paid_installments>0 || data.is_registered==1 ? data.max_amount:(data.max_amount!=0 && data.max_weight==0 ?  parseFloat(data.max_amount) - parseFloat(data.current_total_amount):(parseFloat((parseFloat(data.max_weight) - parseFloat(data.current_total_weight))*$("#metal_rate").val()).toFixed(3)));
                        if(data.paid_installments>0 && data.one_time_premium==1)
                		{
                		    msg='<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong>  You have already paid '+data.total_paid_amount+'</strong></div>';
                		}
                		else if(data.current_chances_used == data.max_chance ){
                		    msg='<div class = "alert alert-warning"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong>  You have already reached max payment chance ['+data.max_chance+']</strong></div>';
                		}
                		else{
                            msg='<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong>  You could not pay less than <strong> Rs  '+data.min_amount+'</strong>  and'+' '+ ' You could not pay more than <strong> Rs  '+data.max_amount+'</strong></div>';
                        }
                        $("div.overlay").css("display", "none"); 
                        //stop the form from submitting
                        $('#error-msg').html(msg);
                        $("#btn-submit").css("display", "none"); 
                        $('#payment_amt').val(0);
                        $('#payment_weight').val(0);
                        return false;	
                	}
                });
        	
        	}
        	else  if(data.scheme_type == 1 && data.is_flexible_wgt ==0)
        	{
            	$('#total_amt').prop('readonly',true);
            	$('#proced').css("display", 'none');
            	$('#enable_editing_blk').css("display", 'block');
            	$('.hidden_allow').css('display','block');
            	$('#payamt').val(data.max_weight * parseFloat($('#metal_rate').val()).toFixed(2));
            	if(allowed_dues > 1){
            	    $('#allowed_dues').prop('readonly',false);
            	}
                var eligible_weight = parseFloat(data.max_weight).toFixed(3);
                $("#payable").html(parseFloat(data.payable).toFixed(2)+" <strong>gm</strong> ");
                $("#total_amount_paid").html("<strong>"+data.currency_symbol+"</strong> "+parseFloat(data.total_paid_amount).toFixed(2));
                $("#total_weight_paid").html(parseFloat(data.total_paid_weight).toFixed(3)+" <strong>gm</strong>");
            	//draw_payment_table(data);
            	var   weight_check='<div class="rate-table"><table class="table table-bordered table-striped table-responsive text-center">'+
            	       '<tr><th colspan="3" style="text-align:center" ><h3 > Gold 22k 1gm rate  : <span id="rate"> '+data.currency_symbol+' '+parseFloat($('#metal_rate').val()).toFixed(2)+'</span></h3></th></tr>'+
            	        '<tr><td><h4><div style="float:left">Eligible:</div><div style="float:right">'+parseFloat(eligible_weight).toFixed(3)+' g<input type="hidden" id="eligible_weight" value="'+parseFloat(eligible_weight).toFixed(3)+'" /></div></h4></td><td><h4><div style="float:left">Selected:</div><div style="float:right"><span id="sel_wt" >0.000</span> g</div><input type="hidden" id="selected_weight" name="generic[metal_weight]"  value="0"/></h3></td></tr>'+ 
            	                           '<tr "><th colspan="3">Weight</th></tr>';
                $.each(data.weights, function() { 
                    if(parseFloat(this.weight) == parseFloat(data.max_weight))
                    {
                        weight_check +="<tr style='text-align:center'><td><input type='checkbox' name='weight_gold' value='"+this.weight+"'/>	"+parseFloat(this.weight).toFixed(3)+" gram </td><td>  "+data.currency_symbol+" "+parseFloat(this.weight*$('#metal_rate').val()).toFixed(2)+" </td></tr>";
                    } 
                });	   
            	weight_check +='<table></div>'; 
            	$('[type=checkbox][name=weight_gold]').trigger('change');
            	$('#payment_container').html(weight_check);
        	}
    	    else if((data.scheme_type == 1  && data.is_flexible_wgt ==1) || ( data.scheme_type == 3 && data.flexible_sch_type == 4))
        	{	
        	    if(data.scheme_type == 3 && data.flexible_sch_type == 4){
        	        $('.hidden_allow').css('display','block');
        	    }
                $('#total_amt').prop('readonly',true);
                $('#proced').css("display", 'none');
                $('#enable_editing_blk').css("display", 'none');
                var eligible_weight= parseFloat(data.max_weight).toFixed(3) - parseFloat(data.current_total_weight).toFixed(3);
                $("#payable").html(parseFloat(data.payable).toFixed(2)+" <strong>gm</strong> ");
                $("#total_amount_paid").html("<strong>"+data.currency_symbol+"</strong> "+parseFloat(data.total_paid_amount).toFixed(2));
                $("#total_weight_paid").html(parseFloat(data.total_paid_weight).toFixed(3)+" <strong>gm</strong>");
            	//draw_payment_table(data);
            	var   weight_check='<div class="rate-table"><table class="table table-bordered table-striped table-responsive text-center">'+
            	       '<tr><th colspan="3" style="text-align:center" ><h3> Gold 22k 1gm rate  : '+data.currency_symbol+' '+parseFloat($('#metal_rate').val()).toFixed(2)+'</h3></th></tr>'+
            	        '<tr><td><h4><div style="float:left">Eligible:</div><div style="float:right">'+parseFloat(eligible_weight).toFixed(3)+' g<input type="hidden" id="eligible_weight" value="'+parseFloat(eligible_weight).toFixed(3)+'" /></div></h4></td><td><h4><div style="float:left">Selected:</div><div style="float:right"><span id="sel_wt" >0.000</span> g</div><input type="hidden" id="selected_weight" name="generic[metal_weight]"  value="0"/></h3></td></tr>'+ 
            	                           '<tr><th>Weight</th><th>Amount</th></tr>';
            	$.each(data.weights, function() {	 
            	    if(( parseFloat(data.current_total_weight) + parseFloat(this.weight)) <= parseFloat(data.max_weight)&&( parseFloat(data.current_total_weight) + parseFloat(this.weight)) >= parseFloat(data.min_weight))
            	    {
            	 	  weight_check +="<tr><td><input type='checkbox' name='weight_gold' value='"+this.weight+"' />	"+parseFloat(this.weight).toFixed(3)+" gram </td><td>  "+data.currency_symbol+" "+parseFloat(this.weight*$('#metal_rate').val()).toFixed(2)+" </td></tr>";
            	    } 
            	});	   
            	weight_check +='<table></div>';
        	    $('#payment_container').html(weight_check);
        	}
        	if(data.scheme_type==2)
        	{
            	$("#amt_to_wgt").html("<span class='label label-success'>Yes</span>");
            	var total_amt = (parseFloat($('#gst_type').val()) == 1? parseFloat($('#total_amt').val()) : parseFloat($('#total_amt').val())-parseFloat('#gst_amt'));
            	var metal_rate = parseFloat($('#metal_rate').val());
            	if(total_amt != '' && metal_rate != ''){
                	var weight = total_amt/metal_rate;
                	$("#amttowgt").html(parseFloat(weight.toFixed(3))+' '+'<strong>'+'gm'+'</strong>');
            	}
        	}
        	else
        	{
            	$("#amt_to_wgt").html("<span class='label label-danger'>No</span>");
            	$("#amttowgt").html("N/A");
        	}
        	if(data.allow_preclose == 1){
        	    $("#is_preclose_blk").css('display','block');
        	}
        	var id_scheme_account=$('#id_scheme_account').val();
        	var url=base_url+'index.php/reports/payment/account/'+id_scheme_account;	
        	$("#start_date").html(data.start_date);
         	$("#acc_name").html(data.account_name);
         	// show the reason for stop payment in admin payment page//HH
         	$("#disable_payment").html(data.disable_payment);
         	if(data.disable_payment==1)
        	{
        	    $("#disable_pay_reason").html("<span class='badge bg-red'>"+data.disable_pay_reason+"</span>");
        	}
            else{
              $("#disable_pay_reason").html("");
            }
        	$("#scheme_code").html(data.code);
        	$("#scheme_type").html((data.scheme_type==0?'Amount':(data.scheme_type==1?'Weight':data.scheme_type==2?'Amount to Weight':(data.scheme_type==3?(data.flexible_sch_type == 2 ? "Flexible Amount":(data.flexible_sch_type == 3 ? "Flexible Weight":"Flexible")):""))));
        	$("#last_paid_date").html((data.last_paid_date!=null?data.last_paid_date:"-"));
        	$("#paid_installments").html("<span class='badge bg-green'><a style='color:white;' target='_blank' href='"+url+"'>"+data.paid_installments+"/"+data.total_installments+"</a></span>");
        	$("#paid_ins").val(data.paid_installments);
        	$("#fix_weight").val(data.scheme_type);
        	$("#wgt_cvrt").val(data.wgt_convert);
        	$("#is_flexible_wgt").val(data.is_flexible_wgt);
        	$("#sch_amt").val(data.payable);
        	$("#unpaid_dues").html((data.totalunpaid > 0 ? data.totalunpaid : 0));
        	$("#due_type").val(data.due_type);
        	$("#act_due_type").val(data.due_type);
        	$("#allowed_dues").val(allowed_dues);
        	$("#act_allowed_dues").val(data.allowed_dues);
        	$("#total_pdc").html((data.cur_month_pdc>0?data.cur_month_pdc+ " / ":'')+data.cur_month_pdc);
            $("#preclose").html(data.preclose);  
            $('#gst_percent').val(data.gst);
            $('#gst_type').val(data.gst_type);	
            $('#ref_benifit_ins').val(data.ref_benifitadd_ins);
            $('#referal_code').val(data.referal_code);
         
            $('#ref_benifitadd_by').val(data.ref_benifitadd_ins_type);
            $("#paidinstall").val(data.paid_installments); 	 
            $("#firstPayamt_maxpayable").val(data.firstPayamt_maxpayable); 	 
            $("#firstPayamt_as_payamt").val(data.firstPayamt_as_payamt); 
            $("#one_time_premium").val(data.one_time_premium); 	 
    	    return false;
    	}
    	else
    	{
    	    clear_account_detail();	
    	}
 }
 $('#pay_form').submit(function(e) {
 	if($('#scheme_type').html()=='Weight')
 	{
	var eligible_weight = parseFloat($('#eligible_weight').val()).toFixed(2);
	 	 var selected_weight = parseFloat( $('#selected_weight').val()).toFixed(2);
	 	 if(parseFloat(selected_weight) > parseFloat(eligible_weight))
	 	 {
	 	 	  msg='<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong> Your have selected weight more than eligible.</div>';
	$("div.overlay").css("display", "none"); 
	        //stop the form from submitting
	         $('#error-msg').html(msg);
	 	  return false;	
	 }
	 	  if(parseFloat(selected_weight) == 0)
	 	  {
	  	  msg='<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong> Please select at least one weight to proceed payment.</div>';
	$("div.overlay").css("display", "none"); 
	        //stop the form from submitting
	         $('#error-msg').html(msg);
	 	  return false;	
	  }
	}
	 if($('#pay_datetimepicker').val()=='')
	 	  {
	  	  msg='<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong> Please select payment date.</div>';
	$("div.overlay").css("display", "none"); 
	        //stop the form from submitting
	         $('#error-msg').html(msg);
	 	  return false;	
	  }
	if($('#scheme_account').val() == null)
	{
	  msg='<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong> Select Scheme A/C No.</div>';
	$("div.overlay").css("display", "none"); 
	        //stop the form from submitting
	         $('#error-msg').html(msg);
	return false;
	}
	if($('#pay_mode').val() == null)
	{
	  msg='<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong> Select payment mode.</div>';
	$("div.overlay").css("display", "none"); 
	        //stop the form from submitting
	         $('#error-msg').html(msg);
	return false;
	}
	if($('#payment_status').val() == null)
	{
	  msg='<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong> Select payment status.</div>';
	$("div.overlay").css("display", "none"); 
	        //stop the form from submitting
	         $('#error-msg').html(msg);
	return false;
	}
 })	
$('#allowed_dues').on('keyup change', function(e) {
	$("div.overlay").css("display", "block"); 
	var max = parseInt($('#act_allowed_dues').val());
	var min = 1; 
    var amt = parseFloat($('#payamt').val());
	 var discount_installment=$('#discount_installment').val();
	 var discount_type=$('#discount_type').val();
	 var discount=$('#discount').val();
	 var paid_installments=$('#paidinstall').val();
	 var firstPayDisc_value=$('#firstPayDisc_value').val();
    var discountedAmt= $('#discountedAmt').val();
	 if(this.value==discount_installment)
	 {
	     var discountedAmt=firstPayDisc_value;
	     $('#discountedAmt').val(discountedAmt);
	 }
	 else if(this.value<=discount_installment)
	 {
	     $('#discountedAmt').val('');
	 }
        console.log(discountedAmt);
	    if(parseInt(this.value) < min || isNaN(this.value) || this.value.length <= 0) 
	       this.value= min; 
	    else if(parseInt(this.value)> max) 
	        this.value= max; 
	    else this.value= this.value;
	total = parseFloat(parseFloat(amt) * parseFloat(this.value)).toFixed(2)-parseFloat((discount==1 ?(discount_type==0 ?firstPayDisc_value :((discount_installment==(this.value)||discountedAmt!='')?firstPayDisc_value :0.00)) :0.00));
    console.log(total);
	if($('#is_flexible_wgt').val() == 0 && $('#scheme_type').text() == 'Weight' ){
	if( parseFloat($('#selected_weight').val()) > 0){	
	$('#total_amt').val(total);
	}
	}
	else{
	$('#total_amt').val(total);
	}
	 var can_redeem = 0;
	 if($(".ischk_wallet_pay").is(":checked") && parseFloat($(".wallet_balance").val()) > 0){
		 var allowed_redeem =  ($('.redeem_percent').val() > 0 ? (total*(parseFloat($('.redeem_percent').val())/100)) : 0);
		 wallet_balance = parseFloat($('.wallet_balance').val());
		 if( allowed_redeem > wallet_balance ){
		 	can_redeem = wallet_balance;
		 }else{
		 	can_redeem = allowed_redeem;
		 }
	 }
	 console.log(parseFloat($('.ischk_wallet_pay').val()));
	 $('.wallet').val(can_redeem);
	 $('.redeem_request').val(can_redeem);
	 /*//GST Calculation
	 var gst_val = 0;
	 var gst_amt = 0;
	 var gst = 0;
	  if(parseFloat($('#gst_percent').val()) > 0 ){
	 	 gst_val = parseFloat(amt)-(parseFloat(amt)*(100/(100+parseFloat($('#gst_percent').val()))));	
	 	 gst_amt = gst_val*parseFloat($('#allowed_dues').val());
	 	 if(parseFloat($('#gst_type').val()) == 1){
	 	gst = gst_amt;
	 }	 	
	 }
	 $('#gst_amt').val(gst_amt);
	 $('#payment_amt').val(parseFloat(gst)+parseFloat(total));*/
	 calculate_payAmt(amt);
	if($('#scheme_type').text() == 'Amount to Weight')
	{
	var total_amt = (parseFloat($('#gst_type').val()) == 1? parseFloat($('#total_amt').val()) : parseFloat($('#total_amt').val())-parseFloat(gst_amt));
	var metal_rate = parseFloat($('#metal_rate').val());
	if(total_amt != '' && metal_rate != ''){
	var weight = total_amt/metal_rate;
	$("#amttowgt").html(parseFloat(weight.toFixed(3))+' '+'<strong>'+'gm'+'</strong>');
	}
	}
    $("div.overlay").css("display", "none"); 
});

    $(document).on('change', '[type=checkbox][name=weight_gold]', function() {
        var selected_weight=0.000; 
        var eligible_weight = parseFloat($('#eligible_weight').val()).toFixed(2);
        var metal_rate = parseFloat($('#metal_rate').val()).toFixed(2); 
        $("input[name=weight_gold]:checked").each(function() {
            selected_weight= parseFloat(parseFloat(selected_weight)+ parseFloat($(this).val())).toFixed(3);
        });
        $('#selected_weight').val(selected_weight);
        $('#payment_weight').val(selected_weight);
        $('#sel_wt').html(parseFloat(selected_weight).toFixed(3));
        var tot_amt = Math.round(parseFloat(selected_weight) * parseFloat(metal_rate) * parseFloat($('#sel_due').val()));
        $('#total_amt').val(parseFloat(tot_amt).toFixed(2));
        var can_redeem = 0;
        if($(".ischk_wallet_pay").is(":checked") && parseFloat($(".wallet_balance").val()) > 0){
            var allowed_redeem =  ($('.redeem_percent').val() > 0 ? (tot_amt*(parseFloat($('.redeem_percent').val())/100)) : 0);
            wallet_balance = parseFloat($('.wallet_balance').val());
            if( allowed_redeem > wallet_balance ){
                can_redeem = wallet_balance;
            }else{
                can_redeem = allowed_redeem;
            }
        }
        $('.wallet').val(can_redeem);
        $('.redeem_request').val(can_redeem);
        /*//GST Calculation
        var gst_val = 0;
        var gst_amt = 0;
        var gst = 0;
        if(parseFloat($('#gst_percent').val()) > 0 ){
            gst_val = parseFloat(tot_amt)-(parseFloat(tot_amt)*(100/(100+parseFloat($('#gst_percent').val()))));
            if(parseFloat($('#gst_type').val()) == 1){
                gst_val = parseFloat(tot_amt)*(parseFloat($('#gst_percent').val())/100);
            }	
            if(parseFloat($('#gst_type').val()) == 0){
                gst_val = parseFloat(tot_amt)-(parseFloat(tot_amt)*(100/(100+parseFloat($('#gst_percent').val()))));
            }		 
            console.log(gst_val+'--'+parseFloat($('#sel_due').val()));
            gst_amt = gst_val*parseFloat($('#sel_due').val());	
        }
        $('#gst_amt').val(gst_amt);
        $('#payment_amt').val(parseFloat(gst_val)+parseFloat(tot_amt));*/
        calculate_payAmt(parseFloat(selected_weight) * parseFloat(metal_rate));
    });
function sumSelected(ele,eligible)
{
	var id = $(ele).attr('id');
	var metal_rate = parseFloat($('#metal_rate').val());
	var idno= id.split('t');
	var spanid='#total_amt'+idno[1];
	var amtid='#amount'+idno[1];
	var wtid='#metal_wt'+idno[1];
	var sum = 0;
    $('#'+id+' :selected').each(function() {
       if($(this).val()<=eligible)
       {
	   	  sum += Number($(this).val());	
	   }
    });
    if(sum <= eligible)
    {
    	 total = parseFloat(sum * metal_rate).toFixed(2);
	  console.log(sum * metal_rate);
	  $(spanid).html(total);
	  $(amtid).val(parseFloat(total).toFixed(2));
	  $(wtid).val(parseFloat(sum).toFixed(3));
	}
    $('#grand_total').html(parseFloat(sum_by_class('payment_amount')).toFixed(2));
    $('#grand_weight').html(parseFloat(sum_by_class('payment_weight')).toFixed(3));
}
 $('#adjust_unpaid').change(function(){
    if($(this).is(':checked'))
    {
	$('#no_of_unpaids').prop('disabled',false);
	}
	else
	{
	$('#no_of_unpaids').prop('disabled',true);
	}
 });
 function sum_by_class(classname)
 {
 	var sum = 0;
	 	$('.'+classname).each(function(){
	    sum += parseFloat($(this).val());  
	});
	return sum;	
 }
//to calculate weight
 function calculate_total()
{
	 var schID = $("#id_scheme_account").val();
	 if(schID!='')
	 {
	$("#payment_amount").val(0);
	if ($("#scheme_type").html() == 'Weight') {	
	var eligibleQty = isNaN($("#eligible_qty").html()) || $("#eligible_qty").html() == '' ? 0 :$("#eligible_qty").html();
	var weight =  isNaN($("#weight").val()) || $("#weight").val() == '' ? 0 :$("#weight").val();
	if(parseFloat(weight) <= parseFloat(eligibleQty))
	{
	totalAmt = parseFloat($("#weight").val()) * parseFloat($("#metal_rate").val());
	$("#payment_amount").val(parseFloat(isNaN(totalAmt)?0.00:totalAmt).toFixed(2));
	}
	else
	{
	$("#payment_amount").val(0);
	$("#weight").val(0);
	}
	}
	}
}
function sumColumn(selector,column)
{
	var sum=0;
	   	  $("#"+selector+" > tbody > tr").each(function() {
	    var row = $(this);
	     value=row.find('td:eq('+column+')').html();
	     console.log(value);
	    // add only if the value is number
	    if(!isNaN(value) && value.length != 0 ) {
	        sum += parseFloat(value);
	    }
	});
	return sum;	
}
function getselected_data()
{
	   	 var sum=0;
	   	  $("#rep_post_payment_list > tbody > tr ").each(function() {
	    var row = $(this);
	     value=row.find('td:eq(9)').html();
	    // add only if the value is number
	    if(!isNaN(value) && value.length != 0 ) {
	        sum += parseFloat(value);
	    }
	    $('#ftotal').html(parseFloat(sum).toFixed(2));
	});	
}
$("input[name='type']:radio").change(function() {
 	if($('#scheme_type').html()=='Weight')
 	{
 	//	$('#btn-submit').load(path +  ' #btn-submit');
	var eligible_weight = parseFloat($('#eligible_weight').val()).toFixed(2);
	 	 var selected_weight = parseFloat( $('#selected_weight').val()).toFixed(2);
	 	 if(parseFloat(selected_weight) > parseFloat(eligible_weight))
	 	 {
	 	 	  msg='<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong> Your have selected weight more than eligible.</div>';
	$("div.overlay").css("display", "none"); 
	        //stop the form from submitting
	         $('#error-msg').html(msg);
	 	  return false;	
	 }
	 	  if(parseFloat(selected_weight) == 0 || selected_weight=='NaN' || $('#total_amt').val()==0.00)
	 	  {
	  	  msg='<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong> Please select at least one weight to proceed payment.</div>';
	$("div.overlay").css("display", "none"); 
	        //stop the form from submitting
	         $('#error-msg').html(msg);
	 	  return false;	
	  }
	}
	   if($('#pay_datetimepicker').val()=='')
	 	  {
	  	  msg='<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong> Please select payment date.</div>';
	$("div.overlay").css("display", "none"); 
	        //stop the form from submitting
	         $('#error-msg').html(msg);
	 	  return false;	
	  }
	if($('#scheme_account').val() == null)
	{
	  msg='<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong> Select Scheme A/C No.</div>';
	$("div.overlay").css("display", "none"); 
	        //stop the form from submitting
	         $('#error-msg').html(msg);
	return false;
	}
	if($('#pay_mode').val() == null)
	{
	  msg='<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong> Select payment mode.</div>';
	$("div.overlay").css("display", "none"); 
	        //stop the form from submitting
	         $('#error-msg').html(msg);
	return false;
	}
	if($('#payment_status').val() == null)
	{
	  msg='<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong> Select payment status.</div>';
	$("div.overlay").css("display", "none"); 
	        //stop the form from submitting
	         $('#error-msg').html(msg);
	return false;
	}
	if($('#branch_settings').val()==1)
	{
		if($('#id_branch').val()=='')
		{
 msg='<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong> Select branch.</div>';
	$("div.overlay").css("display", "none"); 
	        //stop the form from submitting
	         	 $('#error-msg').html(msg);	
		return false;
		}
	}
	var form_data=$('#pay_form').serialize();
	 insert_payment(form_data);
 });
 /*function insert_payment(post_data)
{
	my_Date = new Date();
	 $("div.overlay").css("display", "block"); 
	 $("#pay_print").attr("disabled", true); 
	 $("#pay_save").attr("disabled", true); 
	 var id_customer = $("#id_customer").val();
	  if($('#otp_save').val()==1)
	{
		$.ajax({
		url:base_url+ "index.php/admin_payment/generateotp?nocache=" + my_Date.getUTCSeconds(),
		data :  {'id_customer':id_customer}, 	
		 type : "POST",
		dataType: 'json',
		 success : function(data) {	
					   $("#spinner").css('display','none');
						   if(data.result==3)
						  {
						  		$('#otp_modal').modal({
								backdrop: 'static',
								keyboard: false
							 });
							 //setTimeout(forgot.enableBtn, 90000); 
							$('#verify_otp').on('click',function(){
								var post_otp=$('#otp').val();
								if(post_otp==data.otp)
								{
									verify_otp(post_otp);
										$.ajax({
	 url:base_url+ "index.php/payment/save_all?nocache=" + my_Date.getUTCSeconds(),
	 data: post_data,
	 type:"POST",
	 dataType:"JSON",
	 	 success:function(data){
	 $("#pay_print").attr("disabled", false); 
	 $("#pay_save").attr("disabled", false); 
	if(data.type ==1 && data.payment_status==1)
	{
	 $.each(data.payid,function(index,value) {
	window.open( base_url+'index.php/admin_payment/generateInvoice/'+value,'_blank');
	 });
	  $("div.overlay").css("display", "none"); 
	window.location.href= base_url+'index.php/payment/list';
	 }
	else{
	 $("div.overlay").css("display", "none"); 
	 window.location.href= base_url+'index.php/payment/list';
	}
	 $("div.overlay").css("display", "none"); 
	  },
	  error:function(error)  
	  {
	  	 $("#pay_print").attr("disabled", false); 	
	  	 $("#pay_save").attr("disabled", false); 	
	 $("div.overlay").css("display", "none"); 
	  }	 
	  });
								}
								else
								{
									alert('Invalid OTP');
									var otp=false;
								}
								});
					 }
					}
				});
	}
	 else{
	$.ajax({
	 url:base_url+ "index.php/payment/save_all?nocache=" + my_Date.getUTCSeconds(),
	 data: post_data,
	 type:"POST",
	 dataType:"JSON",
	 	 success:function(data){
	 $("#pay_print").attr("disabled", false); 
	 $("#pay_save").attr("disabled", false); 
	if(data.type ==1 && data.payment_status==1){
	 $.each(data.payid,function(index,value) {
	window.open( base_url+'index.php/admin_payment/generateInvoice/'+value,'_blank');
	 });
	  $("div.overlay").css("display", "none"); 
	window.location.href= base_url+'index.php/payment/list';
	 }
	else{
	 $("div.overlay").css("display", "none"); 
	window.location.href= base_url+'index.php/payment/list';
	}
	 $("div.overlay").css("display", "none"); 
	  },
	  error:function(error)  
	  {
	  	 $("#pay_print").attr("disabled", false); 	
	  	 $("#pay_save").attr("disabled", false); 	
	 $("div.overlay").css("display", "none"); 
	  }	 
	  });
}
}*/ 
/*function insert_payment(post_data)
{
	my_Date = new Date();
	 $("div.overlay").css("display", "block"); 
	 $("#pay_print").attr("disabled", true); 
	 $("#pay_save").attr("disabled", true); 
	  $("#resendotp").attr("disabled", true); 	
	 var otp=true;
	var id_customer = $("#id_customer").val();
	 if($('#otp_save').val()==1)
	{
		$.ajax({
		url:base_url+ "index.php/admin_payment/generateotp?nocache=" + my_Date.getUTCSeconds(),
		data :  {'id_customer':id_customer}, 	
		 type : "POST",
		dataType: 'json',
		 success : function(data) {	
					   $("#spinner").css('display','none');
						   if(data.result==3)
						  {
								 $("div.overlay").css("display", "none"); 
						  		$('#otp_modal').modal({
								backdrop: 'static',
								keyboard: false
								});
							//setTimeout(forgot.enableBtn, 90000); 
							$('#otp').on('change',function()
							{
								var post_otp=$('#otp').val();
								if(post_otp==data.otp)
								{
									 $("#resendotp").attr("disabled", false); 	
									$.ajax({
										 url:base_url+ "index.php/admin_payment/update_otp",
										  data: {'otp':post_otp},
										 type:"POST",
										 dataType:"JSON",
										 	 success:function(data){
										 	 	if(data.result==1)
										 	 	{
										 $.ajax({
										 url:base_url+ "index.php/payment/save_all?nocache=" + my_Date.getUTCSeconds(),
										 data: post_data,
										 type:"POST",
										 dataType:"JSON",
										 	 success:function(data){
										 $("#pay_print").attr("disabled", false); 
										 $("#pay_save").attr("disabled", false); 
										if(data.type ==1 && data.payment_status==1)
										{
										 $.each(data.payid,function(index,value) {
										window.open( base_url+'index.php/admin_payment/generateInvoice/'+value,'_blank');
										 });
										  $("div.overlay").css("display", "none"); 
										 window.location.href= base_url+'index.php/payment/list';
										 }
										else{
										 $("div.overlay").css("display", "none"); 
										  window.location.href= base_url+'index.php/payment/list';
										}
										 $("div.overlay").css("display", "none"); 
										  },
										  error:function(error)  
										  {
										  	 $("#pay_print").attr("disabled", false); 	
										  	 $("#pay_save").attr("disabled", false); 	
										 $("div.overlay").css("display", "none"); 
										  }	 
										  });
									}
										 	 	else
										 	 	{
										 	 		  $("#resendotp").attr("disabled", false); 
										 	 		alert(data.msg)
										 	 		$('#otp').val('');
										 	 			$('#resendotp').on('click',function()
														{
										 	 				$.ajax({
										 	 		url:base_url+ "index.php/admin_payment/generateotp?nocache=" + my_Date.getUTCSeconds(),
													data :  {'id_customer':id_customer}, 	
													 type : "POST",
													dataType: 'json',
													success:function(data)
													{
													$.ajax({
														url:base_url+ "index.php/admin_payment/generateotp?nocache=" + my_Date.getUTCSeconds(),
													data :  {'id_customer':id_customer}, 	
													 type : "POST",
													dataType: 'json',
													success:function(data)
													{
													if(data.result==3)
													{
										 	 		$.ajax({
										 url:base_url+ "index.php/payment/save_all?nocache=" + my_Date.getUTCSeconds(),
										 data: post_data,
										 type:"POST",
										 dataType:"JSON",
										 	 success:function(data){
										 $("#pay_print").attr("disabled", false); 
										 $("#pay_save").attr("disabled", false); 
										if(data.type ==1 && data.payment_status==1)
										{
										 $.each(data.payid,function(index,value) {
										window.open( base_url+'index.php/admin_payment/generateInvoice/'+value,'_blank');
										 });
										  $("div.overlay").css("display", "none"); 
										 window.location.href= base_url+'index.php/payment/list';
										 }
										else{
										 $("div.overlay").css("display", "none"); 
										  window.location.href= base_url+'index.php/payment/list';
										}
										 $("div.overlay").css("display", "none"); 
										  },
										  error:function(error)  
										  {
										  	 $("#pay_print").attr("disabled", false); 	
										  	 $("#pay_save").attr("disabled", false); 	
										 $("div.overlay").css("display", "none"); 
										  }	 
										  });
										}
													}
													});
									}
													});
										 	 			});
										 	 	}
										  },
										  });
								}
								else
								{
									alert('Invalid OTP');
									var otp=false;
								}
						});
					 }
					}
				});
	}
	 else 
	 {
	 	$.ajax({
	 url:base_url+ "index.php/payment/save_all?nocache=" + my_Date.getUTCSeconds(),
	 data: post_data,
	 type:"POST",
	 dataType:"JSON",
	 	 success:function(data){
	 $("#pay_print").attr("disabled", false); 
	 $("#pay_save").attr("disabled", false); 
	if(data.type ==1 && data.payment_status==1)
	{
	 $.each(data.payid,function(index,value) {
	window.open( base_url+'index.php/admin_payment/generateInvoice/'+value,'_blank');
	 });
	  $("div.overlay").css("display", "none"); 
	window.location.href= base_url+'index.php/payment/list';
	 }
	else{
	 $("div.overlay").css("display", "none"); 
	 window.location.href= base_url+'index.php/payment/list';
	}
	 $("div.overlay").css("display", "none"); 
	  },
	  error:function(error)  
	  {
	  	 $("#pay_print").attr("disabled", false); 	
	  	 $("#pay_save").attr("disabled", false); 	
	 $("div.overlay").css("display", "none"); 
	  }	 
	  });
	 }
} */
 function insert_payment(post_data)
{
	my_Date = new Date();
	 $("div.overlay").css("display", "block"); 
	 $("#pay_print").attr("disabled", true); 
	 $("#pay_save").attr("disabled", true); 
	 var id_customer = $("#id_customer").val();
	  if($('#isOTPRegForPayment').val()==1)
	  {
	  	$.ajax({
		url:base_url+ "index.php/admin_payment/generateotp?nocache=" + my_Date.getUTCSeconds(),
		data :  {'id_customer':id_customer}, 	
		 type : "POST",
		dataType: 'json',
		 success : function(data) 
		 {
		 	 if(data.result==3)
		 	 {
		  		$('#otp_modal').modal({
    				backdrop: 'static',
    				keyboard: false
				});
				$("div.overlay").css("display", "none"); 
		 	 }
		 }
		});
	  }
	  else
	  {
		   payment_success(post_data);
	  }
}
	function update_otp(post_data)
	{	
	var post_otp=$('#otp').val();
	$.ajax({
	url:base_url+ "index.php/admin_payment/update_otp",
	data: {'otp':post_otp},
	type:"POST",
	dataType:"JSON",
	success:function(data)
	{
		if(data.result==1)
		{
			payment_success(post_data,post_otp);
		}
		else
		{
			 $("#resendotp").attr("disabled", false);
			 $("#verify_otp").attr("disabled", false);
			 $('#otp').val('');
			alert(data.msg);
		}
	}
		});
	}
function payment_success(post_data,post_otp="")
{
    my_Date = new Date();
    $("div.overlay").css("display", "block"); 
    $("#pay_print").attr("disabled", true); 
    $("#pay_save").attr("disabled", true); 
	$("#verify_otp").attr("disabled", true); 
    var id_scheme_account= $("#id_scheme_account").val();
    $.ajax({ 
        url:base_url+ "index.php/payment/save_all?nocache=" + my_Date.getUTCSeconds(),
        data: post_data,
        type:"POST",
        dataType:"JSON",
        success:function(data){
            $("#pay_print").attr("disabled", false); 
            $("#pay_save").attr("disabled", false); 
			if(data.type ==1 && data.payment_status==1){
                $.each(data.payid,function(index,value) {
					window.open( base_url+'index.php/admin_payment/generateInvoice/'+value+'/'+id_scheme_account,'_blank');
                });
                $("div.overlay").css("display", "none"); 
				 window.location.href= base_url+'index.php/payment/list';
            }
            else{
                $("div.overlay").css("display", "none"); 
					 window.location.href= base_url+'index.php/payment/list';
            }
            $("div.overlay").css("display", "none"); 
        },
        error:function(error)  
        {	
        $("#pay_print").attr("disabled", false); 	
        $("#pay_save").attr("disabled", false); 	
        $("div.overlay").css("display", "none"); 
        } 
    });
}
$("input[name='type1']:radio").change(function(){
	var edit_data=$('#payment_form').serialize();
	  update_payment(edit_data);
 }); 
 function update_payment(post_data)
{
	my_Date = new Date();
	 $("div.overlay").css("display", "block"); 
	 var id = $('#payment_id').val();
	$.ajax({
	 url:base_url+ 'index.php/payment/update/'+id+'?nocache=' + my_Date.getUTCSeconds(),
	 data: post_data,	
	 type:"POST",
	 dataType:"JSON",
	 	 success:function(data){
	 console.log(data);
	if(data.type1 ==1 && data.payment_status==1){
	 $.each(data.paymentid,function(index,value) {
	  window.open( base_url+'index.php/admin_payment/generateInvoice/'+value,'_blank');
	 });
	    $("div.overlay").css("display", "none"); 
	window.location.href= base_url+'index.php/payment/list';
	 }
	else{
	 $("div.overlay").css("display", "none"); 
window.location.href= base_url+'index.php/payment/list';
	}
	 $("div.overlay").css("display", "none"); 
	  },
	  error:function(error)  
	  {
	 $("div.overlay").css("display", "none"); 
	  }	 
	  });
}
	function transaction_detail(id){
	$('.trans-det').html(transactionData(id));
	$('#pay_detail').modal('show', {backdrop: 'static'});
	}
	function transactionData(id)
	{
	var transaction="";
	$("div.overlay").css("display", "block");
	$.ajax({
	  url:base_url+ "index.php/online/get/ajax_payment/"+id,
	 dataType:"JSON",
	 type:"POST",
	 async:false,
	 success:function(data){
	 	payment	=	data;
	 	var gst =(payment.gst >0 ?  payment.currency_symbol+' '+ (payment.gst_type == 1 ?(payment.payment_amount*(payment.gst/100)):Math.round(parseFloat(payment.payment_amount)-(parseFloat(payment.payment_amount)*(100/(100+parseFloat(payment.gst))))))+' '+(payment.gst_type == 0?"(Amount inclusive of GST)":"(Amount exclusive of GST)"):'0.00');
	 var discount = payment.discount > 0 ? "<tr ><th>Discount</th><td>"+payment.discount+"</td></tr></tr>" : '';
	 	transaction  = "<table class='table table-bordered trans'><tr><th>Account Name</th><td>"+data.account_name+"</td></tr><tr><th>Mobile</th><td>"+data.mobile+"</td></tr><tr><th>Account No.</th><td>"+(data.scheme_acc_number == null ? 'Not Allocated':data.scheme_acc_number)+"</td></tr><tr><th>Date</th><td>"+payment.date_payment+"</td></tr><tr><th>Transaction ID</th><td>"+payment.trans_id+"</td></tr><tr><th>PayU ID</th><td>"+payment.payu_id+"</td></tr><tr><th>Mode</th><td>"+payment.payment_mode+"</td></tr><tr><th>Bank</th><td>"+payment.bank_name+"</td></tr><tr><th>Card No</th><td>"+payment.card_no+"</td></tr><tr><th>Paid Amount</th><td> "+payment.currency_symbol+"  "+(payment.no_of_dues>1?payment.act_amount:payment.payment_amount)+' + Charge : '+payment.currency_symbol+' '+payment.bank_charges+"</td></tr><tr ><th>GST</th><td>"+(gst)+"</td></tr></tr>"+discount+"<tr ><th>Remark</th><td><span class='label bg-yellow'>"+payment.remark+"</span></td></tr></table>"
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
 //branch select/////pay_list/hh
 $('#branch_select').select2().on("change", function(e) {
	 switch(ctrl_page[1])
	{
	case 'list':
	if(this.value!='')
	{  
	var from_date = $('#payment_list1').text();
	var to_date  = $('#payment_list2').text();
	var id_employee  = $('#id_employee').text();
	var id=$(this).val();
	get_payment_list(from_date,to_date,id,id_employee);
	}
	 break;	
} 
  });
//scheme_name
   function get_scheme(id){
       	my_Date = new Date();
     	$(".overlay").css('display','block');	
     	$.ajax({		
         	type: 'GET',		
         	url: base_url+'index.php/admin_scheme/ajax_get_schemes'+id+'/'+my_Date.getUTCSeconds(),		
         	dataType:'json',		
         	success:function(data){		
         			 var id_scheme =  $('#id_scheme').val();		   
        	 	$.each(data, function (key, item) {					  				  			   		
            	 	$('#scheme_select').append(						
            	 	$("<option></option>")						
            	 	.attr("value", item.id_scheme)						  						  
            	 	.text(item.name)						  					
            	 	);			   											
             	});						
             	$("#scheme_select").select2({			    
            	 	placeholder: "Select scheme name",			    
            	 	allowClear: true		    
             	});				
             	$("#scheme_select").select2("val",(id_scheme!=''?id_scheme:''));
             	$(".overlay").css("display", "none");			
         	}	
        }); 
   }
$('#scheme_select').select2().on("change", function(e) { 
	if(this.value!='')
	{   
		$("#id_scheme").val(this.value);    
		var id_scheme=$("#id_scheme").val(); 
		load_schemeno_select(id_scheme);
		$('#id_scheme_account').val('');
		 $('#scheme_account').empty();
	}
 	else
	{   
	$("#id_scheme").val('');       
	}
});
// get_payment data //hh
 $('#trans_submit').on('click',function(){
     //alert(1);
	 var ref_trans_id=$('#transid').val();
	 	// var id_branch=$('#id_branch').val();
	get_payments_data_list(ref_trans_id);
});
function get_payments_data_list(ref_trans_id)
{
	 $("div.overlay").css("display", "block"); 
	 $('body').addClass("sidebar-collapse");
    	my_Date = new Date(); 
		var oTable = $('#payments_data_list').DataTable(); 
		oTable.clear().draw();
		$.ajax({
				  type: 'POST',
				  url:  base_url+'index.php/payment/payments_data_list',
		          data: {'ref_trans_id':ref_trans_id},
				  dataType: 'json',
				  success: function(data) {	
				      $("div.overlay").css("display", "none"); 
				       oTable = $('#payments_data_list').dataTable({
				                "bDestroy": true,
				                "bInfo": true,
				                "bFilter": true,
				                // 'searchable':false,
				                "bSort": true,
				                "aaSorting": [[ 0, "desc" ]], 
				                "tableTools": { "aButtons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'all' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'all' } } ] },
				                "aaData": data,    
	                            "order": [[ 0, "desc" ]],
								"aoColumns": [{ "mDataProp": "id_payment" },
								{ "mDataProp":  function(row,type,val,meta)
									{return row.id_transaction+ '<br>B.Ref No &nbsp;:&nbsp; '+row.payment_ref_number+' <br>Act Amt &nbsp; :&nbsp; '+row.act_amount;	}
								}, 
								{ "mDataProp": "payment_type" }, 
								{ "mDataProp": "date_payment" },
								{ "mDataProp": "name" }, 
								{ "mDataProp": "account_name" }, 
								{ "mDataProp": function ( row, type, val, meta ){
								if(row.has_lucky_draw==1){
								return row.scheme_group_code+' '+row.scheme_acc_number;
								}
								else{
								return row.code+' '+row.scheme_acc_number;
								}
								}},
								{ "mDataProp": "id_branch" },
								{ "mDataProp": function(row,type,val,meta)
								{return 'MR: '+row.metal_rate+ '<br> Wgt: '+row.metal_weight+'g'+'<br> Amt: INR'+row.payment_amount;	}
								}, 
								{ "mDataProp": "receipt_no" },
								{ "mDataProp": function(row,type,val,meta)
								{return "<span class='label bg-"+row.status_color+"-active'>"+row.payment_status+"</span>";	}
								},
								{ "mDataProp": "remark" },
								{ "mDataProp": "last_update" },
								 	 ], 
	                 	 /*	"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
	                  if(aData['payment_type']=='Payu Checkout'){	 
	                  switch(aData['due_type'])
	  {
	     case 'A':
	        if(aData['id_status']==2 || aData['id_status']==7)
	$(nRow).css('color', '#e71847');
	   break;
	 case 'P':
	 	 if(aData['id_status']==2 || aData['id_status']==7)
	$(nRow).css('color', '#e71847');
	   break;
	  }
	 }
	}*/
   });            
				  } 
	        });	
}   
  // get_payment data //  
$(document).on('click', '.incre_due', function(e){
	var incre_due_id = ($(this).val());
	var sel_due =$('#sel_due').val();
	var allowed_dues =$('#max_dues').val();
	var sel_value=parseFloat(sel_due)+parseFloat(1);
	var discount_installment=$('#discount_installment').val();
	var discount_type=$('#discount_type').val();
	var discount=$('#discount').val();
	var paid_installments=$('#paidinstall').val();
	var firstPayDisc_value=$('#firstPayDisc_value').val();
	var payable= parseFloat($('#pay').val()).toFixed(2);
	if(sel_value>allowed_dues)
	{
		$('#sel_due').val(1);
		$('#discountedAmt').val(0.00);
	}
	else
	{
		$('#sel_due').val(sel_value);
	}
	if(discount_type==0)
	{
	    $('#discountedAmt').val(firstPayDisc_value);
	}
	else if(discount_installment==$('#sel_due').val())
    {
        $('#discountedAmt').val(firstPayDisc_value);
    }
    var payable_amt = ( $('#sch_type').val() == 1 || $('#flexible_sch_type').val() == 4 ? payable * $('#metal_rate').val() : parseFloat(payable).toFixed(2) );
	var amount= payable_amt * parseFloat($('#sel_due').val()).toFixed(2)-($('#discountedAmt').val()!='' ?parseFloat($('#discountedAmt').val()):0.00);
	console.log(payable_amt+'--'+payable+'--'+$('#sch_type').val()+'--'+$('#metal_rate').val());
    $('#total_amt').val(amount);
	calculate_payAmt(payable_amt); 
});
$(document).on('click', '.dec_due', function(e){
	var incre_due_id = ($(this).val());
	var sel_due =$('#sel_due').val();
	var allowed_dues =$('#max_dues').val();
	var sel_value=parseFloat(sel_due)-parseFloat(1);
	var discount_installment=$('#discount_installment').val();
	var discount_type=$('#discount_type').val();
	var discount=$('#discount').val();
	var paid_installments=$('#paidinstall').val();
	var firstPayDisc_value=$('#firstPayDisc_value').val();
	var payable= parseFloat($('#pay').val()).toFixed(3);
	$('#discountedAmt').val(0.00);
	if(sel_value<allowed_dues)
	{
		$('#sel_due').val(1);
		$('#discountedAmt').val(0.00);
	}
	else
	{
		$('#sel_due').val(sel_value);
	}
	if(discount_type==0)
	{
	    $('#discountedAmt').val(firstPayDisc_value);
	}
	else if(discount_installment==$('#sel_due').val())
    {
        $('#discountedAmt').val(firstPayDisc_value);
    }
    var payable_amt = ( $('#sch_type').val() == 1 || $('#flexible_sch_type').val() == 4 ? payable * $('#metal_rate').val() : parseFloat(payable).toFixed(2) );
	var amount= payable_amt * parseFloat($('#sel_due').val()).toFixed(2)-($('#discountedAmt').val()!='' ?parseFloat($('#discountedAmt').val()):0.00);
	console.log(payable_amt+'--'+payable+'--'+$('#sch_type').val()+'--'+$('#metal_rate').val());
    $('#total_amt').val(amount);
	calculate_payAmt(payable_amt); 
});

   
    function get_employee_list()
    {	
     	$(".overlay").css('display','block');	
     	$.ajax({		
         	type: 'GET',		
         	url: base_url+'index.php/admin_employee/get_employee',		
         	dataType:'json',		
         	success:function(data){		
         		var id_employee=$('#id_employee').val();			  	   
        	 	$.each(data, function (key, item) {					  				  			   		
            	 	$('#employee_select').append(						
            	 	$("<option></option>")						
            	 	.attr("value", item.id_employee)						  						  
            	 	.text(item.firstname )						  					
            	 	);			   											
             	});						
             	$("#employee_select").select2({			    
            	 	placeholder: "Select Employee name",			    
            	 	allowClear: true		    
             	});				
             	$("#employee_select").select2("val", ($('#id_employee').val()!=null?$('#id_employee').val():''));
             	var selectid=$('#id_employee').val();
             		if(selectid!=null && selectid > 0)
                	{
            				$('#id_employee').val(selectid);
            				$('.overlay').css('display','block');
            		}		
         	}	
        }); 
    }
    $('#employee_select').select2().on("change", function(e) {
    	if(this.value!='')
    	{  
            $('#id_employee').val(this.value);
            var from_date = $('#payment_list1').text();
            var to_date  = $('#payment_list2').text();
            var id_branch  = $('#id_branch').text();
            var id_employee=$(this).val();
            get_payment_list(from_date,to_date,id_branch,id_employee);
    	}
    });
    $('#date_Select').on('change',function(e){
       if(this.value!='')
       {
           $('#id_type').val(this.value);
       }
       else
       {
            $('#id_type').val('');
       }
    });
    //offline date insert manual
    function load_paystatus_select()
    {
    	my_Date = new Date();
    	//show spinner
    	$('.overlay').css('display','block');
    	$.ajax({
    	  type: 'POST',
    	  url:  base_url+'index.php/payment/get/ajax_data?nocache=' + my_Date.getUTCSeconds(),
    	  dataType: 'json',
    	  cache:false,
    	  success: function(data) {
        	   if($('#pay_mode').length)
        	    {
                	$.each(data.mode, function (key, mode) {
                	   	$('#pay_mode').append(
                    	$("<option></option>")
                    	  .attr("value", mode.short_code)
                    	  .text(mode.mode_name)
                    	);
                	});
                	if(data.mode.length == 0){
                		var payment_mode = '';
                	}
                	$("#pay_mode").select2({
                	    placeholder: "Select payment mode",
                	    allowClear: true
                	});
                	$("#pay_mode").select2("val", payment_mode);
            	}
            	if($('#payment_status').length)
            	{
            	    $.each(data.payment_status, function (key, pay) {
            	   	    $('#payment_status').append(
                	    $("<option></option>")
                	      .attr("value", pay.id_status_msg)
                	      .text(pay.payment_status)
                	    );
            	    });
                	$('#pay_status').val(payment_status); 
                	$("#payment_status").select2({
                	    placeholder: "Select payment status",
                	    allowClear: true
                	});
                	$("#payment_status").select2("val", ($('#pay_status').val()!=null?$('#pay_status').val():''));
            	}
            	//get rate from api
            	get_rate();
            	//disable spinner
            	$('.overlay').css('display','none');
    	    },
        	error:function(error)
        	{
            	console.log(error);
            	//disable spinner
            	$('.overlay').css('display','none');
        	}	
    	  });	
     } 
     
    function calculate_payAmt(instalment_amt){
        var gst_percent = parseFloat($('#gst_percent').val());
        var gold_metal_rate = parseFloat($('#metal_rate').val());
        var gst = 0;
        var gst_type = parseFloat($('#gst_type').val());
        var sel_dues = parseFloat($('#sel_due').val());
        var discount = parseFloat($('#discountedAmt').val()); 
        var metal_weight = 0;
        var insAmt_withoutDisc = instalment_amt - discount;
        var gst_amt = 0;
        if(gst_percent > 0){
            if(gst_type == 0){ 
                console.log("gst_percent : "+gst_percent);
                console.log("sel_dues : "+sel_dues);
                // Inclusive
            	var gst_removed_amt = insAmt_withoutDisc*100/(100+gst_percent);
            	gst_amt = insAmt_withoutDisc - gst_removed_amt;
            	// Set Value
            	if($("#flexible_sch_type").val() == 2 || $("#sch_type").val() == 2 ){
            	    metal_weight = (gst_removed_amt+discount)/gold_metal_rate;
            	}
            	else if($("#sch_type").val() == 1){
                    metal_weight = $('#selected_weight').val();
            	}
            	/*$('#payment_weight').val(metal_weight*sel_dues);
                $('#gst_amt').val(gst_amt*sel_dues); 
                $('#payment_amt').val(insAmt_withoutDisc*sel_dues); */
               
            	var metal_weight = setMetalWgt(metal_weight);
                console.log({"gst_removed_amt" : gst_removed_amt, "gst_amt" : gst_amt, "metal_weight" : metal_weight});
            	return {"payment_amt":insAmt_withoutDisc,"gst_removed_amt" : gst_removed_amt, "gst_amt" : gst_amt, "metal_weight" : metal_weight};
            }
            else if(gst_type == 1){ 
                // Exclusive
            	var amt_with_gst = insAmt_withoutDisc*((100+gst_percent)/100);
            	gst_amt = amt_with_gst - insAmt_withoutDisc ; 
            	// Set Value
            	if($("#flexible_sch_type").val() == 2 || $("#sch_type").val() == 2 ){
            	    metal_weight = instalment_amt/gold_metal_rate ;
            	}
            	else if($("#sch_type").val() == 1){
                    metal_weight = $('#selected_weight').val();
            	}
            /*	$('#payment_weight').val(metal_weight*sel_dues);
            	$('#gst_amt').val(gst_amt*sel_dues); 
            	$('#payment_amt').val(amt_with_gst*sel_dues); */
            	
            	var metal_weight = setMetalWgt(metal_weight);
            	console.log({"amt_with_gst" : amt_with_gst, "gst_amt" : gst_amt, "metal_weight" : metal_weight});
            	return {"payment_amt":amt_with_gst,"amt_with_gst" : amt_with_gst, "gst_amt" : gst_amt, "metal_weight" : metal_weight};
            } 
        }else{
            if($("#flexible_sch_type").val() == 2 || $("#sch_type").val() == 2 ){
                metal_weight = instalment_amt/gold_metal_rate ;
        	}
        	else if($("#sch_type").val() == 1){
                metal_weight = $('#selected_weight').val();
        	}
        /*	$('#payment_weight').val(metal_weight*sel_dues);
        	$('#gst_amt').val(gst_amt*sel_dues); 
        	$('#payment_amt').val(insAmt_withoutDisc*sel_dues); */
        	var metal_weight = setMetalWgt(metal_weight);
        	return {"payment_amt":insAmt_withoutDisc, "gst_amt" : gst_amt, "metal_weight" : metal_weight};
        }
        
        function setMetalWgt(metal_wgt)
        {
          var metal_weight = metal_wgt.toString();
          console.log(metal_weight);
          //var metal_wgt_roundoff = $("#metal_wgt_roundoff").val();
          //var metal_wgt_decimal = $("#metal_wgt_decimal").val(); 
         var metal_wgt_roundoff = 0;
         var metal_wgt_decimal = 2; 
       let isnum = /^\d+$/.test(metal_wgt); 
          //console.log(metal_weight +'--'+ isnum);
           console.log(metal_weight +'--'+ isnum);
          if(metal_wgt_roundoff == 0 && isnum == false && metal_wgt != ""){
              var arr = metal_weight.split(".");  
              var str = arr[1];
              var deci = str.substring(0, metal_wgt_decimal); // Take first 2 decimal places
              console.log(deci);
              return arr[0]+"."+deci;
          }else{
              return metal_wgt;
          }
        }
        
        
       // below is actual function bcz will worked that funtion above so commanded this//HH
       
       /*function calculate_payAmt(instalment_amt){
        var gst_percent = parseFloat($('#gst_percent').val());
        var gold_metal_rate = parseFloat($('#metal_rate').val());
        var gst = 0;
        var gst_type = parseFloat($('#gst_type').val());
        var sel_dues = parseFloat($('#sel_due').val());
        var discount = parseFloat($('#discountedAmt').val()); 
        var metal_weight = 0;
        var insAmt_withoutDisc = instalment_amt - discount;
        var gst_amt = 0;
        if(gst_percent > 0){
            if(gst_type == 0){ 
                console.log("gst_percent : "+gst_percent);
                console.log("sel_dues : "+sel_dues);
                // Inclusive
            	var gst_removed_amt = insAmt_withoutDisc*100/(100+gst_percent);
            	gst_amt = insAmt_withoutDisc - gst_removed_amt;
            	// Set Value
            	if($("#flexible_sch_type").val() == 2 || $("#sch_type").val() == 2 ){
            	    metal_weight = (gst_removed_amt+discount)/gold_metal_rate;
            	}
            	else if($("#sch_type").val() == 1){
                    metal_weight = $('#selected_weight').val();
            	}
            	$('#payment_weight').val(metal_weight*sel_dues);
                $('#gst_amt').val(gst_amt*sel_dues); 
                $('#payment_amt').val(insAmt_withoutDisc*sel_dues); 
                console.log({"gst_removed_amt" : gst_removed_amt, "gst_amt" : gst_amt, "metal_weight" : metal_weight, "gst_type" : gst_type, "payment_amt":instalment_amt*sel_dues});
            	return {"gst_removed_amt" : gst_removed_amt, "gst_amt" : gst_amt, "metal_weight" : metal_weight, "gst_type" : gst_type};
            }
            else if(gst_type == 1){ 
                // Exclusive
            	var amt_with_gst = insAmt_withoutDisc*((100+gst_percent)/100);
            	gst_amt = amt_with_gst - insAmt_withoutDisc ; 
            	// Set Value
            	if($("#flexible_sch_type").val() == 2 || $("#sch_type").val() == 2 ){
            	    metal_weight = instalment_amt/gold_metal_rate ;
            	}
            	else if($("#sch_type").val() == 1){
                    metal_weight = $('#selected_weight').val();
            	}
            	$('#payment_weight').val(metal_weight*sel_dues);
            	$('#gst_amt').val(gst_amt*sel_dues); 
            	$('#payment_amt').val(amt_with_gst*sel_dues); 
            	console.log({"amt_with_gst" : amt_with_gst, "gst_amt" : gst_amt, "metal_weight" : metal_weight, "gst_type" : gst_type, "payment_amt":amt_with_gst});
            	return {"amt_with_gst" : amt_with_gst, "gst_amt" : gst_amt, "metal_weight" : metal_weight, "gst_type" : gst_type};
            } 
        }else{
            if($("#flexible_sch_type").val() == 2 || $("#sch_type").val() == 2 ){
                metal_weight = instalment_amt/gold_metal_rate ;
        	}
        	else if($("#sch_type").val() == 1){
                metal_weight = $('#selected_weight').val();
        	}
        	$('#payment_weight').val(metal_weight*sel_dues);
        	$('#gst_amt').val(gst_amt*sel_dues); 
        	$('#payment_amt').val(insAmt_withoutDisc*sel_dues); 
        }
        
        function setMetalWgt(metal_wgt)
        {
          var metal_weight = metal_wgt.toString();
          var metal_wgt_roundoff = $("#metal_wgt_roundoff").val();
          var metal_wgt_decimal = $("#metal_wgt_decimal").val(); 
          let isnum = /^\d+$/.test(metal_wgt); 
          console.log(metal_weight +'--'+ isnum);
          if(metal_wgt_roundoff == 0 && isnum == false && metal_wgt != ""){
              var arr = metal_weight.split(".");  
              var str = arr[1];
              var deci = str.substring(0, metal_wgt_decimal); // Take first 2 decimal places
              console.log(deci);
              return arr[0]+"."+deci;
          }else{
              return metal_wgt;
          }
        }
		}*/
        
        /*GST Inclusive :
        ===============
        Gold Rate = 5000
        Installment amount = Rs. 500
        Amount Inclusive of GST = Rs. 490
        Discount = Rs. 10
        GST rate = 3%  
        Payment Amount = 490
        Remove GST = 490*100/(100+3) = 475.7281553398058
        GST 3% = 490 - 475.7281553398058 = 14.2718446601942
        Weight = (475.7281553398058 +10)/5000 = 0.0971456310679612
        
        GST Exclusive :
        ===============
        Gold Rate = 5000
        Installment amount = Rs. 500
        Amount Exclusive of GST = Rs. 490
        GST rate = 3%  
        Payment Amount = 490*((100+3)/100) = 504.7
        GST 3% = 504.7 - 490 = 14.7
        Weight = 500/5000 = 0.1*/
    }
    
    //  Depends on cost center settings, have to make the branch selection. [select the scheme accounts branch and make the field readonly] //HH 
    function get_branchnames(){	
         	//$(".overlay").css('display','block');	
         	$.ajax({		
             	type: 'GET',		
             	url: base_url+'index.php/branch/branchname_list',		
             	dataType:'json',		
             	success:function(data){				 
            	 	var id_branch =  $('#id_branch').val();
            	 	console.log(id_branch);
            	 
            	 		// var sch_join_branch =  $('#sch_join_branch').val();		   
        	 	$.each(data, function (key, item) {					  				  			   		
            	$('#select_branch').append(						
                	 	$("<option></option>")						
                	 	.attr("value", item.id_branch)						  						  
                	 	.text(item.name )						  					
                	 	);			   											
                 	});						
              				
                 	
                						
                 	$("#select_branch").select2({			    
                	 	placeholder: "Select branch name",			    
                	 	allowClear: true		    
                 	});					
                 	
                 	  //  $("#select_branch").select2("val",(id_branch!='' && id_branch>0?id_branch:''));	 
                 	$(".overlay").css("display", "none");			
             	}	
            }); 
        }
        
        
    
    
  
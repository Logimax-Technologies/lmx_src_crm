var path =  url_params();
var ctrl_page = path.route.split('/');

$(document).ready(function() {
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
              
			  		  
              get_payment_list(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'))
			  $('#payment_list1').text(start.format('YYYY-MM-DD'));
			  $('#payment_list2').text(end.format('YYYY-MM-DD')); 
			
			 
          }
        );   
	  
		
	    if(path.route=='payment/list')
	    { 
	        $('body').addClass("sidebar-collapse");
	        get_payment_list();
		}
        else
		{
         	$(document).on('click', '#select_payrow', function(){
					$('#tableRow .select_payrow').prop('checked', $(this).prop('checked'));
					//console.log(get_selected_tablerows('tableRow'));
			});
         

               load_customer_select();
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
		
	      	
	        $('body').on('focus',"#pay_datetimepicker", function(){
			   $('#pay_datetimepicker').attr("readonly",true);
				      	    $('#pay_datetimepicker').datetimepicker({ format: 'yyyy-mm-dd hh:ii:ss',
			        timezone: 'GMT'});
			})
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
							 		//GST Calculation
									 var gst_val = 0;
									 var gst_amt = 0;
									 var gst = 0;
									 if(parseFloat($('#gst_percent').val()) > 0 ){
									 	 gst_val =parseFloat(amt)-(parseFloat(amt)*(100/(100+parseFloat($('#gst_percent').val()))));	
									 	 gst_amt = gst_val*parseFloat($('#allowed_dues').val());
									 	 if(parseFloat($('#gst_type').val()) == 1){
										 	gst = gst_amt;
										 }			 	
									 }
		   
									total = parseFloat(parseFloat(amt) * parseFloat($('#allowed_dues').val())).toFixed(2);
									if($('#scheme_type').text() == 'Amount' || $('#scheme_type').text() == 'Amount to Weight'  ){
										
										$('#total_amt').val(total);
										$('#gst_amt').val(parseFloat(gst_amt));
										$('#payment_amt').val(parseFloat(gst)+parseFloat(total));
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
		//GST Calculation
		 var gst_val = 0;
		 var gst_amt = 0;
		 var gst = 0;
		 if(parseFloat($('#gst_percent').val()) > 0 ){
		 	 gst_val =  parseFloat(amt)-(parseFloat(amt)*(100/(100+parseFloat($('#gst_percent').val()))));
		 	 gst_amt = gst_val*parseFloat($('#allowed_dues').val());	
		 	 if(parseFloat($('#gst_type').val()) == 1){
			 	gst = gst_amt ;
			 }			 	
		 }
		   
			total = parseFloat(parseFloat(amt) * parseFloat($('#allowed_dues').val())).toFixed(2);
				if($('#is_flexible_wgt').val() == 0 && $('#scheme_type').text() == 'Weight' ){
					if( parseFloat($('#selected_weight').val()) > 0){				
						$('#total_amt').val(total);
						$('#gst_amt').val(gst_amt);
					}
				}
				else{
					$('#total_amt').val(total);
					$('#gst_amt').val(gst_amt);
				}
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

function get_payment_list(from_date="",to_date="",id_branch="")
{
	my_Date = new Date();
	$("div.overlay").css("display", "block"); 
	$.ajax({
			  url:base_url+ "index.php/payment/ajax_list?nocache=" + my_Date.getUTCSeconds(),
			 data: (from_date !='' && to_date !=''? {'from_date':from_date,'to_date':to_date,'id_branch':id_branch}: ''),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			 	$('#total_payments').text(data.data.length);
				
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
	 
	 var oTable = $('#payment_list').DataTable();
	 	 if(access.add == '0')
			 {
				$('#add_payment').attr('disabled','disabled');
			 }
	     oTable.clear().draw();
					  
			  if (payment!= null && payment.length > 0)
			  {  
			       var receipt_no_set= (typeof data.data == 'undefined' ? '' :data.data[0].receipt_no_set);
			  
				  if(receipt_no_set==0)
				  {
					
			  
			  
			  
					  	oTable = $('#payment_list').dataTable({
				                "bDestroy": true,
				                "bInfo": true,
				                "bFilter": true,
				                "bSort": true,
				                "dom": 'T<"clear">lfrtip',
				                "tableTools": { "aButtons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },
				                "aaData": payment,    
							    "order": [[ 1, "desc" ]],
				                "aoColumns": [{ "mDataProp": function ( row, type, val, meta ){
					                 	if(row.id_status == 1 && row.is_offline == 0){
											chekbox=' <input type="checkbox" class="pay_status" name="pay_id[]" value="'+row.id_payment+'"  /> <input class="id_branch" type="hidden" name="id_branch" value="'+row.id_branch+'" /><input type="hidden" class="clientid" name="clientid" value="'+row.ref_no+'" />' 
				                		    return chekbox+" "+row.id_payment;
										}else{
											return row.id_payment;
										}					                	
					                  }
					                },
					                { "mDataProp": "date_payment" },
					                { "mDataProp": "name" },
					                { "mDataProp": "account_name" },
					                 { "mDataProp": function ( row, type, val, meta ){
					                	return row.code+'-'+row.scheme_acc_number;
					                	}
					                },
					                { "mDataProp": "mobile" },
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
					                	 edit_url=(access.edit=='1' ? base_url+'index.php/payment/edit/'+id : '#' );
					                	 status_url = base_url+'index.php/payment/status/'+id ;
					                	 detail_url = base_url+'index.php/online/get/ajax_payment/'+id ;
					                	 printbtn='';
					                	 delbtn='';
					                	
					                  if(row.id_status=='1')  
					                  {
					                  	//if(row.receipt=='0'){
											print_url=(access.edit=='1' ? base_url+'index.php/payment/invoice/'+id : '#' );
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
					    '<li><a href="'+status_url+'" class="btn-edit"><i class="fa fa-search-plus" ></i> Status</a></li>'+printbtn+'</ul></div>';
					              
					                	return action_content;
					                	}
					               
					            }], 
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
				                "bSort": true,
				                "dom": 'T<"clear">lfrtip',
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
										
										if(row.scheme_acc_number!='Not Allocated' && (row.receipt_no_set=='1' && row.receipt_no=='' && row.id_status=='1')){
											
											return '<input type="checkbox" id="select_ids_'+row.id_payment+'" class="select_ids"  value="'+row.id_payment+'">';
											
										}else{											
										  	return null;
										  }
					                	}
					                }, 
								
								  { "mDataProp": "id_payment" },
					                { "mDataProp": "date_payment" },
					                { "mDataProp": "name" },
					                { "mDataProp": "account_name" },
					                 { "mDataProp": function ( row, type, val, meta ){
					                	return row.code+'-'+row.scheme_acc_number;
					                	}
					                },
					                { "mDataProp": "mobile" },
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
										
										if(row.scheme_acc_number!='Not Allocated' && (row.receipt_no_set=='1' && row.receipt_no=='' && row.id_status=='1')){									
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
					                	 edit_url=(access.edit=='1' ? base_url+'index.php/payment/edit/'+id : '#' );
					                	 status_url = base_url+'index.php/payment/status/'+id ;
					                	 detail_url = base_url+'index.php/online/get/ajax_payment/'+id ;
					                	 printbtn='';
					                	 delbtn='';
					                	
					                  if(row.id_status=='1')  
					                  {
					                  	//if(row.receipt=='0'){
											print_url=(access.edit=='1' ? base_url+'index.php/payment/invoice/'+id : '#' );
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
					    '<li><a href="'+status_url+'" class="btn-edit"><i class="fa fa-search-plus" ></i> Status</a></li>'+printbtn+'</ul></div>';
					              
					                	return action_content;
					                	}
					               
					            }], 
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
					 	$.each(data, function (key, acc) {
								  	
						   
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
   

function load_schemeno_select()
{
	my_Date = new Date();
	//show spinner
	$('.overlay').css('display','block');
	$.ajax({
	  type: 'GET',
	  url:  base_url+'index.php/payment/get/ajax_data?nocache=' + my_Date.getUTCSeconds(),
	  dataType: 'json',
	   cache:false,
	  success: function(data) {
	  //	console.log(country);
	     if($('#scheme_account').length>0)
	     {
			 
			 
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
		 }
		
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
				
				$("#pay_mode").select2({
				    placeholder: "Select payment mode",
				    allowClear: true
				});
					
				$("#pay_mode").select2("val", ($('#payment_mode').val()!=null?$('#payment_mode').val():''));
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
    load_account_detail($("#id_scheme_account").val());
}
else{
	
	
//get account detail on change
  $('#scheme_account').select2()
        .on("change", function(e) {
          //console.log("change val=" + this.value);
         
          if(this.value!='')
          {
          	 $("#id_scheme_account").val(this.value);
		  	 load_account_detail(this.value);
		  
		  }
		  else
		  {
		  	clear_account_detail();
		  }
   });
	
}
   
 $('#pay_mode').select2()
    .on("change", function(e) {
 
     if(this.value=='CHQ')
     {
     	$('.cheque-container').css('display','block');
     }	
     else
     {
	 	$('.cheque-container').css('display','none');
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
   
//to get account detail         
 function load_account_detail(id)
 {
 	my_Date = new Date();
	//show spinner
	$('.overlay').css('display','block');
 		$.ajax({
				  type: 'GET',
				  url:  base_url+'index.php/payment/get/ajax/account/'+id+'?nocache=' + my_Date.getUTCSeconds(),
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
 	
 	$("#start_date").html("");
 	$("#acc_name").html("");
	$("#scheme_code").html("");
	$("#scheme_type").html("");
	
	$("#payable").html("");
	$("#paid_installments").html("");
	$("#total_amount_paid").html("");
	$("#total_weight_paid").html("");
	$("#total_amt").val("");
	$("#gst_amt").val("");
	$("#payment_amt").val("");
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

 }    
 //to load account detail view
 function account_detail_view(data)
{		
      //$("#overlay").css("display","none");
 	 	clear_account_detail();	
		var table="";
		maximum_weight = 0;
		var allowed_dues =parseInt(data.allowed_dues);
//		var allowed_dues =1;
		 var schID = $("#id_scheme_account").val();
		 
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
				
		 	    
		 		if(data.scheme_type == 0 || data.scheme_type == 2)
				{
					$('#total_amt').prop('readonly',true);
					$('#proced').css("display", 'none');
					$('#enable_editing_blk').css("display", 'block');
					$("#payable").html("<strong>"+data.currency_symbol+"</strong> "+parseFloat(data.payable).toFixed(2));
				
					$("#total_amount_paid").html("<strong>"+data.currency_symbol+"</strong> "+parseFloat(data.total_paid_amount).toFixed(2));
				      //draw_payment_table(data);
				  
				  
				    $('#total_amt').val(data.payable);
				    $('#payamt').val(data.payable);
				    $('.hidden_allow').css('display','block');
				    var amount = data.payable;
				    if(allowed_dues >1)
				    {
						$('#allowed_dues').prop('readonly',false);
							amount = parseFloat(data.payable).toFixed(2) * parseFloat(allowed_dues).toFixed(2);
						$('#total_amt').val(amount);
					}  
					$('#payment_container').html('');
					var pending_dues = parseInt(data.total_installments - data.paid_installments);
					if(data.preclose ==1 && parseInt(data.preclose_benefits)== pending_dues)
					{
						allowed_dues=parseInt(data.preclose_benefits);
						amount = parseFloat(data.payable).toFixed(2) * parseFloat(allowed_dues).toFixed(2);
						$('#total_amt').val(amount);
					}
					 //GST Calculation
					 var gst_val = 0;
					 var gst_amt = 0;
					 var gst = 0;
					 if(data.gst > 0 ){
					 	 gst_val = parseFloat(data.payable)-(parseFloat(data.payable)*(100/(100+parseFloat(data.gst))));
					 	 gst_amt = gst_val*allowed_dues;
					 	 if(data.gst_type == 1){						 	
						 	gst = gst_amt ;
						 }	
							
					 }
					 $('#gst_amt').val(gst_amt);
					 $('#payment_amt').val(parseFloat(gst)+parseFloat(amount));
					
				}
				
				
				// flxi scheme 
				
				
				else if(data.scheme_type == 3 )
				{
					
					$('#total_amt').prop('readonly',false);
					$('#proced').css("display", 'block');
					$('#enable_editing_blk').css("display", 'block');
					$("#payable").html("<strong>"+data.currency_symbol+"</strong> "+parseFloat(data.payable).toFixed(2));
				
					$("#total_amount_paid").html("<strong>"+data.currency_symbol+"</strong> "+parseFloat(data.total_paid_amount).toFixed(2));
					$("#total_weight_paid").html(parseFloat(data.total_paid_weight)+" <strong>gm</strong>");
		
		
		$( "#proced" ).on( "click", function(event) {
			
			
			if($("#total_amt").val() != "")
			{
				
			var amt=$("#total_amt").val();
			
			}  
			
			$('#payamt').val(amt);
				
		
				var metal_rates=$("#metal_rate").val();
				
			
			   var amount = amt;
				   
					 //GST Calculation
					 var gst_val = 0;
					 var gst_amt = 0;
					 var weight	 = 0;
					 var wight_amount	 = 0;
					 var metal_weights	 = 0;
					 var gst = 0;
					 if(data.gst > 0 ){
					 	 gst_val = parseFloat(amount)-(parseFloat(amount)*(100/(100+parseFloat(data.gst))));
					 	 gst_amt = gst_val*allowed_dues;
					 	 if(data.gst_type == 1){						 	
						 	gst = gst_amt ;
						 }
					 }
					 
					 
					 metal_weights=parseFloat(amount)/parseFloat(metal_rates);
					
					var metal_weight_cal= metal_weights
					var cm_amt=parseFloat(amount)+parseFloat(data.current_total_amount);
					
					if( (parseFloat(data.min_amount) <= parseFloat(amount) && parseFloat(data.max_amount) >= parseFloat( amount))&& (parseFloat(cm_amt) <= parseFloat(data.scheme_overall_amount))&& parseFloat(data.max_chance) > parseFloat(data.current_chances_use)&&parseFloat(cm_amt)<=parseFloat(data.max_amount)){
						
						// console.log(data.max_amount);
					 console.log(data.total_paid_amount);
						msg='<div class = "alert " style="background-color:green; color:white;"><a href = "#" class = "close" data-dismiss = "alert">&times;</a> Sucess Click Save </div>';
						
						$("div.overlay").css("display", "none"); 
						
						      
						        //stop the form from submitting
						         $('#error-msg').html(msg);
						
						$("div.overlay").css("display", "none"); 
						$("#btn-submit").css("display", "block"); 
						
						$('#payment_amt').val(parseFloat(gst)+parseFloat(amount));
						if(data.wgt_convert==0)
					 {
								$('#payment_weight').val(parseFloat(metal_weight_cal).toFixed(3));
					 }
					 else{
						 $('#payment_weight').val('-');
					 }
							 //$('#gst_amt').val(gst_amt);
							// $('#payment_amt').val(parseFloat(gst)+parseFloat(amount)); 
							 
								
							 
					}
				else{	
				
				
				/* //msg='<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong> Your have Enter The amount below the minimum amount.Please Enter The amount</div>'; */
				
				 var maxamount = (data.max_amount!=0 ?(data.max_amount-data.current_total_amount):((data.max_weight - data.current_total_weight)*data.metal_rate))
				
				msg='<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong>  You could not pay less than <strong> Rs  '+$("#sch_amt").val()+'</strong>  and'+' '+ ' You could not pay more than <strong> Rs  '+data.max_amount +'</strong></div>';
						
						
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
				
				
				// flxi scheme 
				
				
				
				
				
				
				
				
				
				
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
					
					 
					//console.log(data.current_total_weight);
					 if(parseFloat(this.weight) == parseFloat(data.max_weight))
					 {
					 		  weight_check +="<tr style='text-align:center'><td><input type='checkbox' name='weight_gold' value='"+this.weight+"' />	"+parseFloat(this.weight).toFixed(3)+" gram </td></tr>";
					 } 
				
				});	   
				weight_check +='<table></div>';
				console.log(weight_check);
				$('#payment_container').html(weight_check);
				
				}
				else  if(data.scheme_type == 1  && data.is_flexible_wgt ==1)
				{	$('#total_amt').prop('readonly',true);
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
					
					 
					//console.log(data.current_total_weight);
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
					var total_amt = (parseFloat($('#gst_type').val()) == 1? parseFloat($('#total_amt').val()) : parseFloat($('#total_amt').val())-parseFloat(gst_amt));
					
					
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
				
				$("#start_date").html(data.start_date);
			 	$("#acc_name").html(data.account_name);
				$("#scheme_code").html(data.code);
				$("#scheme_type").html((data.scheme_type==0?'Amount':(data.scheme_type==1?'Weight':data.scheme_type==2?'Amount to Weight':"Flxeble Amount")));
				$("#last_paid_date").html((data.last_paid_date!=null?data.last_paid_date:"-"));
				$("#paid_installments").html("<span class='badge bg-green'>"+data.paid_installments+"/"+data.total_installments+"</span>");
				$("#paid_ins").val(data.paid_installments);
				$("#fix_weight").val(data.scheme_type);
				$("#wgt_cvrt").val(data.wgt_convert);
				
				$("#is_flexible_wgt").val(data.is_flexible_wgt);
				$("#sch_amt").val(data.payable);
				$("#unpaid_dues").html((data.totalunpaid > 0 ? data.totalunpaid : 0));
				$("#due_type").val(data.due_type);
				$("#act_due_type").val(data.due_type);
				$("#allowed_dues").val(allowed_dues);
				$("#act_allowed_dues").val(allowed_dues);
				$("#total_pdc").html((data.cur_month_pdc>0?data.cur_month_pdc+ " / ":'')+data.cur_month_pdc);
				
				$("#preclose").html(data.preclose);  
			    $('#gst_percent').val(data.gst);
			    $('#gst_type').val(data.gst_type);				
				$('#ref_benifit_ins').val(data.ref_benifitadd_ins);
			    $('#referal_code').val(data.referal_code);
			    $('#ref_benifitadd_by').val(data.ref_benifitadd_ins_type);
                $("#paidinstall").val(data.paid_installments); 		
			
				
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
   console.log(amt);
	    if(parseInt(this.value) < min || isNaN(this.value) || this.value.length <= 0) 
	       this.value= min; 
	    else if(parseInt(this.value)> max) 
	        this.value= max; 
	    else this.value= this.value;
   
		 total = parseFloat(parseFloat(amt) * parseFloat(this.value)).toFixed(2);
		if($('#is_flexible_wgt').val() == 0 && $('#scheme_type').text() == 'Weight' ){
			if( parseFloat($('#selected_weight').val()) > 0){				
				$('#total_amt').val(total);
			}
		}
		else{
			$('#total_amt').val(total);
		}
		 //GST Calculation
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

$(document).on('change', '[type=checkbox][name=weight_gold]', function() {
	
	  var selected_weight=0.000; 
	  var eligible_weight = parseFloat($('#eligible_weight').val()).toFixed(2);
	  var metal_rate = parseFloat($('#metal_rate').val()).toFixed(2); 
        $("input[name=weight_gold]:checked").each(function() {
        	selected_weight= parseFloat(parseFloat(selected_weight)+ parseFloat($(this).val())).toFixed(3);
	
	   });
	         $('#selected_weight').val(selected_weight);
		 	  $('#sel_wt').html(parseFloat(selected_weight).toFixed(3));
			  var tot_amt = Math.round(parseFloat(selected_weight) * parseFloat(metal_rate) * parseFloat($('#allowed_dues').val()));
			  $('#total_amt').val(parseFloat(tot_amt).toFixed(2));
			  
				 //GST Calculation
				 var gst_val = 0;
				 var gst_amt = 0;
				 var gst = 0;
				 if(parseFloat($('#gst_percent').val()) > 0 ){
				 	 gst_val = parseFloat(tot_amt)-(parseFloat(tot_amt)*(100/(100+parseFloat($('#gst_percent').val()))));		 	 
					
				 	 if(parseFloat($('#gst_type').val()) == 1){
						
					 	gst_val = parseFloat(tot_amt)*parseFloat($('#gst_percent').val())/100;
					 }			

					 if(parseFloat($('#gst_type').val()) == 0){
					 	gst_val = parseFloat(tot_amt)-(parseFloat(tot_amt)*(100/(100+parseFloat($('#gst_percent').val()))));
					 }	
						 gst_amt = gst_val*parseFloat($('#allowed_dues').val());	
				 }
				 $('#gst_amt').val(gst_amt);
				 $('#payment_amt').val(parseFloat(gst_val)+parseFloat(tot_amt));
		
		 
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
	var form_data=$('#pay_form').serialize();
	
		 insert_payment(form_data);
 	 
 });
 
 function insert_payment(post_data)
{
	my_Date = new Date();
	 $("div.overlay").css("display", "block"); 
	 $("#pay_print").attr("disabled", true); 
	 $("#pay_save").attr("disabled", true); 
	 
	 
	$.ajax({
		/* var payid = []; */
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


 
 
 $('#branch_select').select2().on("change", function(e) {
         
		 
			 switch(ctrl_page[1])

			{

				case 'list':
				
					
					if(this.value!='')
					{  
						
						var from_date = $('#payment_list1').text();
						var to_date  = $('#payment_list2').text();
						var id=$(this).val();
						get_payment_list(from_date,to_date,id);
					}
					 break;		
			
			     } 
		  
   });

//branch_name
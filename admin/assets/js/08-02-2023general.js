var path =  url_params();
var ctrl_page = path.route.split('/');
console.log(ctrl_page);
$(document).ready(function() {
    
   /* $(document).on('input[type="number"]').on('keypress',function(e){
        if (e.keyCode == 45)
        {
            e.preventDefault();
            return false;
        }  
     });*/
     
     if(ctrl_page[1]!='access' && ctrl_page[1]!='billing' && ctrl_page[1]!='estimation')
     {
         get_metalname();
     }
      
    $('#tempToMain').click(function() {
        if($('#id_branch').val() != '' && $('#entry_date').val() != ''){
            if($('#entry_date').val().length ==10){
                getTempToMainData();
            }else{
                alert("Invalid date format. YYYY-MM-DD")
            }
        }
    });
     $('[data-toggle="tooltip"]').tooltip(); 	
     
	 /*$('.grid').dataTable({
          "bPaginate": true,
          "bLengthChange": true,
          "bFilter": true,
          "bSort": true,
          "bInfo": true,
          "bAutoWidth": false,
		  "order": [[ 0, "desc" ]]
        }); */
        
        var company_name    = $('#company_name').val();
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
    var branch_name    = ($('#branch_name').val()!='' && $('#branch_name').val()!=undefined ? $('#branch_name').val() : $("#branch_select option:selected").text());
    var report_name = $('#report_name').val();
	// alert(report_name);
    var title="<div style='text-align: center;'><b><span style='font-size:15pt;'>"+company_name+"</span></b><b><span style='font-size:12pt;'></span></b></br>"
    +report_print(branch_name,report_name,from_date,to_date);
    my_Date = new Date();
    //$(".overlay").css("display", "block");
	
     
	 $('.grid').dataTable({
          "bPaginate": true,
          "bLengthChange": true,
          "bFilter": true,
          "bSort": true,
          "bInfo": true,
          "bAutoWidth": false,
		  "order": [[ 0, "asc" ]],
		  "dom": 'lBfrtip',
		  "buttons": [
                        {   
                        extend: 'print',                                       
                        footer: true,
						title: '',
                        messageTop: title,
                        customize: function ( win ) {
                                            $(win.document.body).find( 'table' )
                                                .addClass( 'compact' )
                                                .css( 'font-size', 'inherit' );
                                        },
                         },
                         {
                         extend: 'excel',   
                          }
                              ]

        });
 /*   //branch_name//	 */				
/* if(($('#branch_set').val()==1 || $('#branch_set').val()!=1) &&  ctrl_page[1] != 'dashboard' && ctrl_page[0] != 'settings' && ctrl_page[1] != 'payment_employee_wise' && ctrl_page[1] != 'payment_daterange' && ctrl_page[1] != 'Employee_account' && ctrl_page[1] != 'payment_datewise_schemedata'  && ctrl_page[1] != 'billing'){		
 get_branchname();			
 }	
*/

if($('#branch_set').val()==1 &&  ctrl_page[1] != 'dashboard' && ctrl_page[0] != 'settings' && ctrl_page[1] != 'payment_employee_wise' && ctrl_page[1] != 'payment_daterange' && ctrl_page[1] != 'Employee_account' && ctrl_page[1] != 'payment_datewise_schemedata' && ctrl_page[1] != 'branch_transfer'  ){		
	 	get_branchname();			
	}
	
 $('#branch_select').select2().on("change", function(e) {
    if(((ctrl_page[2] !=='add' || ctrl_page[2] !=='edit') && this.value!='')) // based on the branch settings to showed branch filter iN send Notifi page admin//
    {	 
    	var id_branch   = $(this).val();						
    	$('#id_branch').val(id_branch);
    }
});


 
 /*   //branch_name//	 */																															
   $(".mySwitch").bootstrapSwitch();	
   $("#active").bootstrapSwitch();	
   $("#empactive").bootstrapSwitch();	
   $("#visible").bootstrapSwitch();	
                  
 function minmax(value, min, max) 
{
    if(parseInt(value) < min || isNaN(value) || value.length <= 0) 
        return min; 
    else if(parseInt(value)> max) 
        return max; 
    else return value;
}


$('.myDatePicker').datepicker({
               format: 'dd-mm-yyyy',
               "setValue": new Date(),
                  "autoclose": true
              
            }).datepicker("setDate", "");

 //Confirm Delete function

   	$(document).on('click', "a.btn-del", function(e) {
       e.preventDefault();
        var link=$(this).data('href');        
       $('#confirm-delete').find('.btn-confirm').attr('href',link);
    });
   
	$('#confirm-delete .btn-cancel').on('click', function(e) {
		
		$('.btn-confirm').attr('href',"#");
	}); 
	

 // end of Confirm Delete function
 
 
 
 
 //date range payment report
 $("#generate_report").click(function () {
		data = $('#payment_range').serialize();
	  
		var p_status = $("input:radio[name='pay_status']:checked").val();
		var p_mode = $("input:radio[name='pay_mode']:checked").val();
		var frm_date = $("#frm_date").val();
		var to_date = $("#to_date").val();
		
		p_status =(p_status !='' ? p_status: 'ALL' );
		p_mode =(p_mode !='' ? p_mode: 'ALL' );
		if(frm_date !="" && to_date!="")
		{
			
			
			$.ajax({
					  type: "POST",
					  url: base_url+"index.php/reports/payment/range/date",
					  data: {from_date:frm_date, to_date: to_date,p_status:p_status, p_mode: p_mode},
					  dataType: 'json',
					  success:function(data){
						 // console.log(data);
						 trHead ='<tr>'+
							    '<th>P.ID</th>'+	
								'<th>Paid Date</th>'+							
								'<th>Trans ID</th>'+	
								'<th>PayU ID</th>'+									
								'<th>Client ID</th>'+
								'<th>Name</th>'+
								'<th>Mobile</th>'+
								'<th>Sch. Code</th>'+    
								'<th>Ms.No</th>'+
								'<th>Pay Mode</th>'+    
								'<th>Card No</th>'+    
								'<th>Metalrate (&#8377;)</th>'+
								'<th>Metalweight (g)</th>'+
								'<th>Amount (&#8377;)</th>'+
								'<th>Charge (&#8377;)</th>'+
								'<th>Total Paid (&#8377;)</th>'+
								'<th>Pay Status</th>'+
								'<th>Remark</th></tr>';
								
						//appending header		 
						
						$('#payment_list > thead').html(trHead);
				
						 trHTML ='';
						  var payment_list = $('#payment_list').DataTable();
						  
						 //destroy datatable
						 
						 payment_list.destroy();
					    
						$.each(data, function (i, item) {
							trHTML += '<tr>' +
										'<td>' + item.id_payment + '</td>' +
										'<td>' + item.trans_date + '</td>' +								
										'<td>' + item.trans_id + '</td>' +
										'<td>' + item.payu_id + '</td>' +								
										'<td>' + item.client_id + '</td>' +
										'<td>' + item.name + '</td>' +
										'<td>' + item.mobile + '</td>' +
										'<td>' + item.group_code + '</td>' +
										'<td>' + item.msno + '</td>' +
										'<td>' + item.payment_mode + '</td>' +
										'<td>' + item.card_no + '</td>' +
										'<td>' + item.rate + '</td>' +
										'<td>' + item.weight + '</td>' +
										'<td>' + item.amount + '</td>' +
										'<td>' + item.bank_charges + '</td>' +
										'<td>' + item.paid_amt + '</td>' +
										'<td>' + item.pay_status + '</td>' +								
										'<td>' + item.remark + '</td>' +								
										'</tr>';
						});
                       
                     
                  
                      $('#payment_list > tbody').html(trHTML);
						
					  payment_list =	$('#payment_list').dataTable({
							  "bPaginate": true,
							  "bLengthChange": true,
							  "bFilter": true,
							  "bSort": true,
							  "bInfo": true,
							  "bAutoWidth": true
							});
					  }
				  });
		}	
		
	});
 //end of date range payment report
       
});

function url_params()
{
	var url = window.location.href;
	var path=window.location.pathname;
	var params = path.split( 'php/' );
	
	return {'url':url,'pathname':path,'route':params[1]};
}

// get the user access details
function get_user_rights()
{
	var url = window.location.pathname.split( 'php/' );
    var url_params = url[1].split('/');
    if(url_params[1].substr(url_params[1].length - 4) == 'list')
    {
		$.post(base_url+"index.php/settings/get/user_rights",
	    {
	        url: url[1]
	    },
	    function(data, status){
	       // console.log("Data: " + data + "\nStatus: " + status);
	        return data;
	    });
	}
    
}

function _calculateAge(birthday) { // birthday is a date
    dob=new Date(birthday);
    var ageDifMs = Date.now() -  dob.getTime();
    var ageDate = new Date(ageDifMs); // miliseconds from epoch
    return Math.abs(ageDate.getUTCFullYear() - 1970);
}

function __capitalizeString(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function __textbox(caption,value)
{
	return "<div class='col-sm-6'><div class='form-group'><label >"+caption+"</label><input  type='text' class='form-control input-sm' id='' readonly=true name='' value='"+value+"'/></div></div>";
}

function __label(caption,value)
{
	return "<div class='col-sm-6'><div class='form-group'><label>"+caption+"</label><label class='pull-right' style='font-weight:normal!important;' id=''>"+value+"</label></div></div>";	
}

  function GetMonthName(monthNumber) {
      var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
      return months[monthNumber - 1];
    }


// to get the selected table data in json object
function get_selected_tablerows(table)
{
		var table_data = [];
			    var values = {};
			    $("#"+table+"> tbody > tr").each(function(i){
			        values = new Object;
			
			       if( $(this).find('input[type="checkbox"]').is(':checked')){ 
			       	   
			       	   //update status for selected row
				   	  // $('input[name="payment_status"]').val($('#payment_status_select').val());
				      
				       //fetch values
				        $('input', this).each(function(){
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
			        }
			    });
			    
	return table_data;		    
}

//to generate data table 
function generate_datatable(tableID,data,columns)
{
	 var oTable = $('#'+tableID).DataTable();		              
     oTable.clear().draw();
	  	 if (data!= null && data.length > 0)
	  	  {  	
	  	     
			  	oTable = $('#'+tableID).dataTable({
			  		     "dom": 'T<"clear">lfrtip', 
			  		     "tableTools": { "aButtons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] }, 
		                "bDestroy": true,
		                "bInfo": true,
		                "bFilter": true,
		                "bSort": true,
		                "aaData": data,
		                "aoColumns":columns,
		                 "fnInitComplete": function (oSettings, json) {   
  			 console.log(oSettings);
  			               if($("#ftotal").length>0)
  			               {
						   	 var sum=0;
						   	  $("#rep_post_payment_list > tbody > tr ").each(function() {
									   
									    var row = $(this);
									     value=row.find('td:eq(10)').html();
									    // add only if the value is number
									    if(!isNaN(value) && value.length != 0 ) {
									        sum += parseFloat(value);
									        
									    }
									    $('#ftotal').html(parseFloat(sum).toFixed(2));
									});						   
						  }
  			}

		            });			  	 	
		  }	
		  
				  
}

//function to allow numbers and decimal
$('.input_currency').on("keypress keyup blur",function (event) {
  if ((event.which != 46 || $(this).val().indexOf('.') != -1) &&
    ((event.which < 48 || event.which > 57) &&
      (event.which != 0 && event.which != 8))) {
    event.preventDefault();
  }

  var text = $(this).val();

  if ((text.indexOf('.') != -1) &&
    (text.substring(text.indexOf('.')).length > 2) &&
    (event.which != 0 && event.which != 8) &&
    ($(this)[0].selectionStart >= text.length - 2)) {
    event.preventDefault();
  }
});

//function to allow numbers and decimal
$('.input_weight').on("keypress keyup blur",function (event) {
  if ((event.which != 46 || $(this).val().indexOf('.') != -1) &&
    ((event.which < 48 || event.which > 57) &&
      (event.which != 0 && event.which != 8))) {
    event.preventDefault();
  }

  var text = $(this).val();

  if ((text.indexOf('.') != -1) &&
    (text.substring(text.indexOf('.')).length > 3) &&
    (event.which != 0 && event.which != 8) &&
    ($(this)[0].selectionStart >= text.length - 3)) {
    event.preventDefault();
  }
});

//function to allow only number
$(".input_number").on("keypress keyup blur",function (event) {    
   var key = window.event ? event.keyCode : event.which;

	if (event.keyCode == 8 || event.keyCode == 46
	 || event.keyCode == 37 || event.keyCode == 39) {
	    return true;
	}
	else if ( key < 48 || key > 57 ) {
	    return false;
	}
	else return true;
});

//function to allow only alphabets
 $(".input_text").on("keypress keyup blur",function (event) {    
        var inputValue = event.charCode;
        if((inputValue > 47 && inputValue < 58) && (inputValue != 32)){
            event.preventDefault();
        }
    });
  
    
//function to mask date
 $(".datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});  
 
 
 //for check box style
 
    //Flat red color scheme for iCheck
        $('input[type="checkbox"].flat-green, input[type="radio"].flat-green').iCheck({
          checkboxClass: 'icheckbox_flat-green',
          radioClass: 'iradio_flat-green'
        });
        
   //Date range as a button
        $('.btn_date_range').daterangepicker(
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
           //alert(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
             rangeStartDate = start.format('MMMM D, YYYY');
             rangeEndDate = end.format('MMMM D, YYYY');
         }
        );  
        
       function get_branchname(){	
         	//$(".overlay").css('display','block');	
         	$.ajax({		
             	type: 'GET',		
             	url: base_url+'index.php/branch/branchname_list',		
             	dataType:'json',		
             	success:function(data){	
					$("#branch_select option").remove(); // New code 05-12-2022
					
            	 	var id_branch =  $('#id_branch').val();	
            	 	if((data.profile==1 || data.profile==2 || data.profile==3))
            	 	{
            	 	     var add_all = ((ctrl_page[1]=='add' || ctrl_page[2]=='add' || ctrl_page[1]=='edit' || ctrl_page[2]=='edit' || ctrl_page[1]=='admin_ret_reports' || (ctrl_page[1]=='reorder_settings' && ctrl_page[2]=='list')) ? false : true);
            	 	    if(add_all === true && branchSettings == 1 && loggedInBranch == 0){
        	 	        	$("#branch_select,.branch_filter").append(						
                    	 	$("<option></option>")						
                    	 	.attr("value", 0)						  						  
                    	 	.text('All' )
                    	 	);
            	 	    }
            	 	}
            	 	
            	 	
            	 	
            	  $.each(data.branch, function (key, item) {
            	 //	$.each(data, function (key, item) {					  				  			   		
                	 	$("#branch_select,#sync_branch,.ret_branch,.branch_filter").append(						
                	 	$("<option></option>")						
                	 	.attr("value", item.id_branch)						  						  
                	 	.text(item.name )						  					
                	 	);			   											
                 	});						
                 	$("#branch_select,#sync_branch,.ret_branch,.branch_filter").select2({			    
                	 	placeholder: "Select Branch",			    
                	 	allowClear: true		    
                 	});					
                 	if($("#branch_select").length || $(".ret_branch").length){
                 	    console.log(id_branch);
						$("#branch_select,.ret_branch").select2("val",(id_branch!='' && id_branch>0?id_branch:''));	 
					}   
                 	//$(".overlay").css("display", "none");			
             	}	
            }); 
        }
        
        
       $('#close_model').on('click',function(){

     	location.reload();

     }) ;  

 $("#can_payment").click(function(){


 	$('#cancel_payment').modal({
			backdrop: 'static',
			keyboard: false
			}); 

   }); 

 $('#yes').on('click',function(){
	
	
      var data = { 'id_payment' : [] };

	
		$("input[name='payment_cancel[]']:checked").each(function() {

			  data['id_payment'].push($(this).val());

			  	});	

		console.log(data);
			
					$.ajax({



					      type: "POST",



						  url: base_url+"index.php/admin_reports/cancel_payment",



						  data: data,

						    dataType: 'json',

						  sync:false,



						  success: function(data){

									 	 location.reload();
			            	 		
			            	 	}	   											
		             			 
			   });
});
$('#no').on('click',function(){
		 location.reload();
});

$('#cancel_all').click(function(event) {

		$("tbody tr td input[type='checkbox']").prop('checked', $(this).prop('checked'));

      event.stopPropagation();

    });
    
function getTempToMainData(till_updated="")
{
    
 	my_Date = new Date();
    $("div.overlay").css("display", "block"); 
	$.ajax({
    url:base_url+"index.php/admin_services/ajaxtempToMain?nocache=" + my_Date.getUTCSeconds(),
    data: ({'id_branch':$('#id_branch').val(),'entry_date':$('#entry_date').val(),'till_updated':(till_updated != ''?till_updated:0)}),
    dataType:"JSON",
    type:"POST",
        success:function(data){
            if(data == 0){
                $("div.overlay").css("display", "none"); 
                $("#result").html("Execution completed successfully."); 
                var audio = document.getElementById('audio');
                audio.play();
            }else if(data == 400){
                getTempToMainData(data);
            }else{
                $("#result").html("Execution completed successfully."); 
                $("div.overlay").css("display", "none"); 
                var audio = document.getElementById('audio');
                audio.play();
            }
        },
    error:function(error) 
    {  
    
    }	 
    
    }); 

}
//Metal list //HH
function get_metalname()
{
	
	$(".overlay").css('display','block');
	$.ajax({
		type: 'GET',
		url: base_url+'index.php/metal/metalname_list',
		dataType:'json',
		success:function(data){
		console.log(data);
		 //var scheme_val =  $('#id_metal').val();
		 //console.log( $('#id_metal').val());
		   $.each(data, function (key, item) {
			  
			   		$('#metal_select').append(
						$("<option></option>")
						.attr("value", item.id_metal)						  
						  .text(item.metal )
						  
					);			   				
				
			});
			
			$("#metal_select").select2({
			    placeholder: "Select metal name",
			    allowClear: true
			});
				
			 //$("#metal_select").select2("val",(scheme_val!='' && scheme_val>0?scheme_val:''));
			 //console.log($('#id_metal').val());

				if($("#metal_select").length)
				{
				    $("#metal_select").select2("val", ($('#id_metal').val()!=null?$('#id_metal').val():''));
				}
			  

			 $(".overlay").css("display", "none");	
		}
	});
}
        
       
   //Metal list //
   
   
   
   function getDisplayDateTime()
   {
        var today = new Date();
        var dispdate = today.getDate()+'-'+(today.getMonth()+1)+'-'+today.getFullYear();
        var disptime = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
        return dispdate + " " + disptime;
    }
	
	
	function report_print(branch_name,report_name,from_date,to_date)
    {
    
        if(branch_name==''|| (from_date=='' && to_date=='')){
           branch_name = "ALL"
    	   from_date = ""
    	   to_date= ""
        }
         
            var data = "<span>"+report_name+" - "+branch_name+"</span></br>"
                     +(from_date!='' && to_date != '' ? "<span>FROM&nbsp;:&nbsp;"+from_date+" &nbsp;&nbsp;TO&nbsp;&nbsp; "+to_date+ "</span></br> " : '')
                     + $('.hidden-xs').html()+" &nbsp; - &nbsp;"+ "</span>"+"<span style='font-size:11pt;'>"+getDisplayDateTime()  +"</span></br>";
            return data;
        
    }

    



   
   
   
 
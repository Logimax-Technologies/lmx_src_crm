var path =  url_params();
let indianCurrency = Intl.NumberFormat('en-IN');
var fewSeconds = 30;
var timer=null;
var ctrl_page = path.route.split('/'); 

$(document).ready(function() {
    
    
//pending remarks report
if(ctrl_page[1] == 'accountRemarks')
	{
	    
			var date = new Date();
		    var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 6, 1); 
		
			var from_date =  firstDay.getFullYear()+'-'+(firstDay.getMonth()+1)+'-'+firstDay.getDate();
	
			var to_date=(date.getFullYear()+"-"+(date.getMonth() + 1)+"-"+date.getDate());
			//$('#rpt_payments1').empty();
			//$('#rpt_payments2').empty();
			get_paymentRemarks(from_date,to_date);
			$('#rpt_payments1').text(moment().startOf('month').format('YYYY-MM-DD'));
			$('#rpt_payments2').text(moment().endOf('month').format('YYYY-MM-DD'));	
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
              get_paymentRemarks(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'))			 
			  $('#rpt_payments1').text(start.format('YYYY-MM-DD'));
			  $('#rpt_payments2').text(end.format('YYYY-MM-DD')); 

          }
        );   
}

get_sch_enq_list();	


if(ctrl_page[1]=='payment_cancel_report')
	{
	    get_cancel_pay_list();
	}
	
if(ctrl_page[1] == 'Employee_account')

	{
			  var date = new Date();
			  
		    var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 6, 1); 
		
			var from_date =  firstDay.getFullYear()+'-'+(firstDay.getMonth()+1)+'-'+firstDay.getDate();
	
			var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
	$('#account_list1').empty();
	$('#account_list2').empty();
	get_employee_acc_list(from_date,to_date);
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
              get_employee_acc_list(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'))			 
			  $('#account_list1').text(start.format('YYYY-MM-DD'));
			  $('#account_list2').text(end.format('YYYY-MM-DD')); 

          }

        );   

}
	
	
	
        $('#cancel_payment_list1').empty();
        $('#cancel_payment_list2').empty();
        $('#cancel_payment_list1').text(moment().startOf('month').format('YYYY-MM-DD'));
        $('#cancel_payment_list2').text(moment().endOf('month').format('YYYY-MM-DD'));
        $('#cancel_payment-dt-btn').daterangepicker(
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
        $('#cancel_payment_list1').text(start.format('YYYY-MM-DD'));
        $('#cancel_payment_list2').text(end.format('YYYY-MM-DD'));		       
        var branch=$('#branch_select').val();
        get_cancel_pay_list(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'),branch)
        }
        );

//Purchase Payment - Akshaya Thiruthiyai Spl updt// 
if(ctrl_page[1]=='get_purchase_payment')
{
    
    var date = new Date();
    var firstDay    =   new Date(date.getFullYear(), date.getMonth(),date.getDate() - 30, 1); 
    var from_date   =   firstDay.getFullYear()+'-'+(firstDay.getMonth()+1)+'-'+firstDay.getDate();
	var to_date     =   (date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
    get_purchase_payment(from_date,to_date); 
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
    get_purchase_payment(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'))
    $('#payment_list1').text(start.format('YYYY-MM-DD'));
    $('#payment_list2').text(end.format('YYYY-MM-DD')); 
    }
    );
    
        $("#mobilenumber" ).autocomplete({
        source: function( request, response ) 
        {
        var mobile=$("#mobilenumber").val();
        my_Date = new Date();
        $.ajax({
        url:base_url+ "index.php/admin_reports/ajax_get_customers_list?nocache=" + my_Date.getUTCSeconds(),
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
        var customer= {
        label: entry.mobile+'  '+entry.firstname,
        value:entry.id_purch_customer
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
         var from_date = $('#payment_list1').text();
		 var to_date  = $('#payment_list2').text();
        $("#mobilenumber" ).val(i.item.label);
        $("#id_customer").val(i.item.value);
        get_purchase_payment(from_date,to_date,$('#id_customer').val());
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
//Purchase Payment - Akshaya Thiruthiyai Spl updt//

//Online Payment Report
if(ctrl_page[1]=='online_payment_report')
{
    $('#online_payment_report_list_info').hide();
    $('.dataTables_info').hide();
    get_payment_status();
    
    var date = new Date();
    var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 0, 1); 
    var from_date =  firstDay.getFullYear()+'-'+(firstDay.getMonth()+1)+'-'+firstDay.getDate();
    var to_date =  date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate();
    $('#from_date').html(from_date);
    $('#to_date').html(to_date);
    get_online_payment_report();
    
    $('#online_payment_report_date').daterangepicker(
    {
        ranges: {
        'Today': [moment(), moment()],
        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(0, 'days'),
        endDate: moment()
    },
    function (start, end) 
    {
        get_online_payment_report(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
        //$('#online_payment_report_date').html(start.format('D/M/YYYY') + ' - ' + end.format('D/M/YYYY'));
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        $('#from_date').text(start.format('DD-MM-YYYY'));
        $('#to_date').text(end.format('DD-MM-YYYY'));  
    }
    );
    
}

//old metal report
if(ctrl_page[1]=='old_metal_report')
{
    var date = new Date();
    var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 0, 1); 
    var from_date =  firstDay.getFullYear()+'-'+(firstDay.getMonth()+1)+'-'+firstDay.getDate();
    var to_date =  date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate();
    $('#rpt_payments1').html(from_date);
    $('#rpt_payments2').html(to_date);
    get_old_metal_report();
    
    $('#rpt_payment_date').daterangepicker(
    {
        ranges: {
        'Today': [moment(), moment()],
        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(0, 'days'),
        endDate: moment()
    },
    function (start, end) 
    {
        $('#rpt_payments1').text(start.format('YYYY-MM-DD'));
		$('#rpt_payments2').text(end.format('YYYY-MM-DD'));		        
    }
    );
    
}
//old metal report

$("#pay_reprint").click(function(){
	if($("input[name='payment_reprint[]']:checked").val())
	{
	    var selected = [];
		$("input[name='payment_reprint[]']:checked").each(function() {
		  selected.push($(this).val());
		});
		pay_id = selected;
		if(selected.length){
		    pay_reprint(pay_id);
		}else{
		    alert("Please select payment to reprint");
		}
	}
});
function pay_reprint(pay_id="")
{
	my_Date = new Date();
	$("div.overlay").css("display", "block"); 
	$.ajax({
        url: base_url+ "index.php/admin_manage/passbook_reprint?nocache=" + my_Date.getUTCSeconds(),
        data: {'pay_ids' : pay_id},
        type: "POST",
        async: false,
        success:function(data){
			$("div.overlay").css("display", "none");
			window.open(base_url+ "index.php/admin_manage/passbook_print/B/"+ctrl_page[3], "_blank");
			$("input[name='payment_reprint[]']").removeAttr('checked');
		},
		error:function(error)  
		{
		    $("div.overlay").css("display", "none"); 
		}	 
	});
}

//Autodebit subscription Report//HH
if(ctrl_page[1]=='get_autodebit_subscription')
{
    
    var date = new Date();
    var firstDay    =   new Date(date.getFullYear(), date.getMonth(),date.getDate() - 30, 1); 
    var from_date   =   firstDay.getFullYear()+'-'+(firstDay.getMonth()+1)+'-'+firstDay.getDate();
	var to_date     =   (date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
    get_autodebit_subscription(from_date,to_date);
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
    get_autodebit_subscription(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'))
    $('#payment_list1').text(start.format('YYYY-MM-DD'));
    $('#payment_list2').text(end.format('YYYY-MM-DD')); 
    }
    );
    
     $('#branch_select').on('change',function(){
        var from_date = $('#payment_list1').text();
        var to_date  = $('#payment_list2').text();
        get_autodebit_subscription(from_date,to_date,$('#id_customer').val());
    })
    
    
        $("#mobilenumber" ).autocomplete({
        source: function( request, response ) 
        {
        var mobile=$("#mobilenumber").val();
        my_Date = new Date();
        $.ajax({
        url:base_url+ "index.php/admin_reports/ajax_get_customers_lists?nocache=" + my_Date.getUTCSeconds(),
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
         var from_date = $('#payment_list1').text();
		 var to_date  = $('#payment_list2').text();
        $("#mobilenumber" ).val(i.item.label);
        $("#id_customer").val(i.item.value);
        get_autodebit_subscription(from_date,to_date,$('#id_customer').val());
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

//Autodebit subscription Report//

		//get_kyc_list();
if(ctrl_page[1]=='payment_employee_wise'||ctrl_page[1]=='payment_daterange' || ctrl_page[1]=='gift_report' ||ctrl_page[1]=='scheme_payment_daterange' || ctrl_page[1]=='Employee_account' ||  ctrl_page[1]=='payment_datewise_schemedata')
{
    get_employee_name();
    get_branchname();
    get_payModeList();
}
if(ctrl_page[1]=='employee_wise_collection')
{
    get_employee_name();
}

//closed A/C report with date picker, cost center based branch fillter//HH
if(ctrl_page[1]=='closed_acc_report')
{
    get_cls_branchname();
}

//Plan 2 and Plan 3 Scheme Enquiry Data with date filter//hh
	        $('#sch_enq_list1').empty();
            $('#sch_enq_list2').empty();
            $('#sch_enq_list1').text(moment().startOf('month').format('YYYY-MM-DD'));
             $('#sch_enq_list2').text(moment().endOf('month').format('YYYY-MM-DD'));

			$('#sch_enq-dt-btn').daterangepicker(
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

		             
						$('#sch_enq_list1').text(start.format('YYYY-MM-DD'));
						$('#sch_enq_list2').text(end.format('YYYY-MM-DD'));		            
                       get_sch_enq_list(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'))
		          }

	        );
//Plan 2 and Plan 3 Scheme Enquiry Data with date filter//hh

//Kyc Approval Data status filter with date picker//hh
  
	if(ctrl_page[1]=='kyc_data' && ctrl_page[2]=='list')
	{
	//	get_kyc_list();
		// Kyc reg// 
		  if(ctrl_page[3])
			 {
				 $('#filtered_status').val(ctrl_page[3]);
			 }
	
	//console.log(ctrl_page[1]);
	        $('#kyc_Select').on('change', function() {
	            if(this.value != '')
	            {
	                var type = $('#kyc_select').val();
	                get_kyc_list('','','',this.value);
	            }
	        });

	
			$('#filtered_status').on('change', function() {
		 	   if(status = 0){
		 	       $("#in_progress").css("display", "none");
		 	      $("#verified").css("display", "none");
		 	      $("#reject").css("display", "none");
		 	  }else{
		 	       $("#in_progress").css("display", "block");
		 	      $("#verified").css("display", "block");
		 	      $("#reject").css("display", "block");
		 	  }


		 	  get_kyc_list("","",status,"");
		 	});
		 	
		 	$('#kyc_list1').empty();
            $('#kyc_list2').empty();
            $('#kyc_list1').text(moment().startOf('month').format('YYYY-MM-DD'));
             $('#kyc_list2').text(moment().endOf('month').format('YYYY-MM-DD'));

			$('#kyc-dt-btn').daterangepicker(
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

		             
						$('#kyc_list1').text(start.format('YYYY-MM-DD'));
						$('#kyc_list2').text(end.format('YYYY-MM-DD'));
                       get_kyc_list(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'),$('#filtered_status').val(),$("#kyc_select").val())
		          }

	        );
	        
	        
            $("input[name='upd_status_btn']:radio").change(function(){
            if($("input[name='kyc_id[]']:checked").val())
            {
                    var selected = [];
                    var in_progress=false;
                    var kyc_status = $("input[name='upd_status_btn']:checked").val();
                    $("#kyc_list tbody tr").each(function(index, value){
                            if($(value).find("input[name='kyc_id[]']:checked").is(":checked"))
                            { 
                                data = { 
                                    'id_kyc'   : $(value).find(".kyc_id").val(),
                                    'cus'   : $(value).find(".cus").val(),
                                    'status'   : kyc_status
                                    }
                                in_progress=true;
                                selected.push(data);
                            }
                    });
                   
                    kyc_data = selected;
                    if(in_progress==true)
                    {
                         var kyc_type = ctrl_page[3];
                         update_kyc_status(kyc_data,kyc_type);
                    }
                   
            }
            });
	        
	       
	} 
	
//Kyc Approval Data status filter with date picker//hh



if(ctrl_page[1] == 'Employee_account')

	{
			  var date = new Date();
			  
		    var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 6, 1); 
		
			var from_date =  firstDay.getFullYear()+'-'+(firstDay.getMonth()+1)+'-'+firstDay.getDate();
	
			var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
	$('#account_list1').empty();
	$('#account_list2').empty();
	get_employee_acc_list(from_date,to_date);
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
              get_employee_acc_list(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'))			 
			  $('#account_list1').text(start.format('YYYY-MM-DD'));
			  $('#account_list2').text(end.format('YYYY-MM-DD')); 

          }

        );   

}

var dateToday = new Date();	


 $('#emp_list').DataTable();
 

var dateToday = new Date();	

$('#filter_by').on('change', function() {
    if(this.value != ''){
        $(".filter_by_ip").css('display','block');
    }else{
        $(".filter_by_ip").css('display','none');
    }
});


$("#searchWalTrans").click(function(){
    var searchTerm = $("#searchTerm").val();
    var id_branch=$("#branch_select").val();
   var from_date=$('#rpt_payments1').text();
	var to_date=$('#rpt_payments2').text();
   
    if(searchTerm != ''){
        get_interWalTrans_list(from_date,to_date,'');
    }
});	

// Enquiry KVP
$("#add_enq_status").click(function(){  
	$("div.overlay").css("display", "block"); 
    $("#add_enq_status").prop('disabled',true);
    var postData = {"internal_status":$("#internal_stat").val(),"enq_description":$("#enq_desc").val(),"enq_status":$("#enq_status").val(),"id_enquiry":$("#id_enquiry").val()};
    $.ajax({
        url:base_url+ "index.php/admin_reports/enquiry/UpdateStatus/",
        dataType:"JSON",
        data : postData,
        type:"POST",
        async:false,
        success:function(data){
            if(data.status){
                $("#internal_stat").val('');
                 $("#enq_desc").val('');
                $("#enq_status").val(1);
                $("#id_enquiry").val('');
            }
            $("#add_enq_status").prop('disabled',false); 
            $("div.overlay").css("display", "none"); 
            window.location.reload();
        },
        error:function(error)  
        {
            $("div.overlay").css("display", "none"); 
        }	 
    }); 
 }); 

$("#sub_date").datepicker({

	 "minDate":new Date(dateToday.getFullYear(), dateToday.getMonth(), dateToday.getDate()),

	  "setDate": new Date(new Date().toString('dd/MM/yyyy'))

});	

$('#emp_acc_list').dataTable({



    "bDestroy": true,



    "bInfo": true,



    "bFilter": true,



    "bSort": true,

    

     "dom": 'T<"clear">lfrtip',

    

    "tableTools": { "aButtons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } }] }

       });

 /*$('.det_pay_report').dataTable({

          "bPaginate": true,

          "bLengthChange": true,

          "bFilter": true,

          "bSort": true,

          "bAutoWidth": false,

		  "order": [[ 0, "desc" ]],
		  
		  "bDestroy": true, 
		   
		  "responsive": true, 
				                
		  "bInfo": false,
				              					
		   "scrollX":'100%',
		  
		  "dom": 'Bfrtip',
		   
		   "lengthMenu":[[ 10, 25, 50, -1 ],[ '10 rows', '25 rows', '50 rows', 'Show all' ]],
								
			"buttons": [
						{	
						extend: 'print',									   
						footer: true,
						customize: function ( win ) {
											$(win.document.body).find( 'table' )
												.addClass( 'compact' )
												.css( 'font-size', 'inherit' );
										},
						 },
						 {
						 extend: 'excel',	
						  },
						  {
						  extend:'pageLength',
						  customize: function ( win ) {
											$(win.document.body).find( 'table' )
												.addClass( 'compact' )
												.css( 'font-size', 'inherit' );
										},
						   }
							  ],

        });*/
		
		$('.refferal_report').dataTable({

          "bPaginate": true,

          "bLengthChange": true,

          "bFilter": true,

          "bSort": true,

          "bInfo": true,

          "bAutoWidth": false,

		  "order": [[ 0, "desc" ]],

		  "dom": 'T<"clear">lfrtip', "tableTools": { "aButtons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] }

        });
		
		
		$('.reff_reports').dataTable({

          "bPaginate": true,

          "bLengthChange": true,

          "bFilter": true,

          "bSort": true,

          "bInfo": true,

          "bAutoWidth": false,

		  "order": [[ 0, "desc" ]],

		  "dom": 'T<"clear">lfrtip', "tableTools": { "aButtons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] }

        });
		
		
		
 




 $('.date_pay_report').dataTable({

          "bPaginate": true,

          "bLengthChange": true,

          "bFilter": true,

          "bSort": true,

          "bInfo": true,

          "bAutoWidth": false,

		  "order": [[ 0, "asc" ]],

		  "dom": 'T<"clear">lfrtip', "tableTools": { "aButtons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] }

        });
		
		
		
		$('.refferal_counts').dataTable({

          "bPaginate": true,

          "bLengthChange": true,

          "bFilter": true,

          "bSort": true,

          "bInfo": true,

          "bAutoWidth": false,

		  "order": [[ 0, "asc" ]],

		  "dom": 'T<"clear">lfrtip', "tableTools": { "aButtons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] }

        });


       

	switch(ctrl_page[2])

	{

		case 'postdated':

		         var payment_type = (ctrl_page[6]==7?'Presentable':'Presented')+' '+(ctrl_page[5]=='chq'?'Cheque':'ECS')+' ';  

		         $('#total_payments').text(0);

		        

	  	        $('#pay_type').text(payment_type);

		       get_postdated_data(ctrl_page[4],ctrl_page[5],ctrl_page[6]);

		       

			break;

		case 'payment':   

			  generate_failed_payments();	

			

		

		    break;

		default:

			break;

	}

	switch(ctrl_page[1])

	{
	    case 'msg91_translog' : 
	            get_msg_translist();
	            
	    case 'msg91_delivery' :  
                $('#msg_rep_date1').empty();
                $('#msg_rep_date2').empty();  
                get_msgDeliv_report(moment().startOf('month').format('MMMM D, YYYY'),moment().endOf('month').format('MMMM D, YYYY'));
                $('#msg_rep_date1').text(moment().startOf('month').format('YYYY-MM-DD'));
                $('#msg_rep_date2').text(moment().endOf('month').format('YYYY-MM-DD'));
                
                $('#msg_rep_date-dt-btn').daterangepicker(
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
                        get_msgDeliv_report(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
                        $('#msg_rep_date1').text(start.format('YYYY-MM-DD'));
                        $('#msg_rep_date2').text(end.format('YYYY-MM-DD'));		            
                    }
                ); 

		case 'payment_daterange':
				$('#rpt_payments1').empty();
				$('#rpt_payments2').empty();
				 get_schemename();						 
		         generate_payment_daterange(moment().startOf('month').format('MMMM D, YYYY'),moment().endOf('month').format('MMMM D, YYYY'));
				 $('#rpt_payments1').text(moment().startOf('month').format('YYYY-MM-DD'));
				 $('#rpt_payments2').text(moment().endOf('month').format('YYYY-MM-DD'));

				

			  		 $('#rpt_payment_date').daterangepicker(

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

		             generate_payment_daterange(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
						$('#rpt_payments1').text(start.format('YYYY-MM-DD'));
						$('#rpt_payments2').text(end.format('YYYY-MM-DD'));		            

		          }

			 ); 

			break;
			
			
		// Scheme source wise report  --- scheme wise payment details report with mode wise + online & showroom collection report -- START
		case 'scheme_payment_daterange':
                  
                    get_schemename();
				   // get_schemeclassifyname();
                    var date = new Date();
        		    var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 0, 1); 
        			var from_date =  firstDay.getFullYear()+'-'+(firstDay.getMonth()+1)+'-'+firstDay.getDate();
        			var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
			        $('#rpt_payments1').html(from_date);
                    $('#rpt_payments2').html(to_date);
                    getPaymentDateRangeList(from_date,to_date);
                    getPaymentSummary(from_date,to_date);
                  
                    $('#rpt_payment_date').daterangepicker(
                    {
                        ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                        },
                        startDate: moment().subtract(0, 'days'),
                        endDate: moment()
                    },
                    function (start, end) {
                    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                    //getPaymentDateRangeList(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
                    $('#rpt_payments1').html(start.format('YYYY-MM-DD'));
                    $('#rpt_payments2').html(end.format('YYYY-MM-DD'));		            
                    }
                    ); 
			break;
			// Scheme source wise report  --- scheme wise payment details report with mode wise + online & showroom collection report  -- END
			
		// Gift issued report  ---- START
		case 'gift_report':
                  
                    get_schemename();
                    var date = new Date();
        		    var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 0, 1); 
        			var from_date =  firstDay.getFullYear()+'-'+(firstDay.getMonth()+1)+'-'+firstDay.getDate();
        			var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
			        $('#rpt_payments1').html(from_date);
                    $('#rpt_payments2').html(to_date);
                    var id_branch = $('#id_branch').val();
                    getGiftIssuedList(from_date,to_date,id_branch);
                  
                    $('#rpt_payment_date').daterangepicker(
                    {
                        ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                        },
                        startDate: moment().subtract(0, 'days'),
                        endDate: moment()
                    },
                    function (start, end) {
                    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                    $('#rpt_payments1').html(start.format('YYYY-MM-DD'));
                    $('#rpt_payments2').html(end.format('YYYY-MM-DD'));	
                    getGiftIssuedList(from_date,to_date,id_branch);
                    }
                    ); 
			break;
			// Gift issued report  ---  END
			
			
				case 'payment_employee_wise':   //hh
				$('#rpt_payments1').empty();
				$('#rpt_payments2').empty();
				 get_schemename();						 
		         get_paymentlist(moment().startOf('month').format('MMMM D, YYYY'),moment().endOf('month').format('MMMM D, YYYY'));
				 $('#rpt_payments1').text(moment().startOf('month').format('YYYY-MM-DD'));
				 $('#rpt_payments2').text(moment().endOf('month').format('YYYY-MM-DD'));

				

			  		 $('#empwisereport_date').daterangepicker(

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
                        console.log($('#emp_select').find(":selected").val());
                        console.log($('#branch_select').find(":selected").val());
		             get_paymentlist(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'),$('#branch_select').find(":selected").val(),$('#emp_select').find(":selected").val());  //hh
						$('#rpt_payments1').text(start.format('YYYY-MM-DD'));
						$('#rpt_payments2').text(end.format('YYYY-MM-DD'));		            

		          }

			 ); 
			 break;
			 
			 	case 'employee_wise_collection':   //hh
				$('#rpt_payments1').empty();
				$('#rpt_payments2').empty();
								 
		         get_emp_summary_list(moment().startOf('month').format('MMMM D, YYYY'),moment().endOf('month').format('MMMM D, YYYY'));
				 $('#rpt_payments1').text(moment().startOf('month').format('YYYY-MM-DD'));
				 $('#rpt_payments2').text(moment().endOf('month').format('YYYY-MM-DD'));

				

			  		 $('#empwisereport_date').daterangepicker(

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

		             get_emp_summary_list(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));  //hh
						$('#rpt_payments1').text(start.format('YYYY-MM-DD'));
						$('#rpt_payments2').text(end.format('YYYY-MM-DD'));		            

		          }

			 ); 
            
            break;

		case 'payment_modewise_data':
				$('#rpt_payments1').empty();
				$('#rpt_payments2').empty();
				get_schemename();							 
		         generate_paymodewise_list(moment().startOf('month').format('MMMM D, YYYY'),moment().endOf('month').format('MMMM D, YYYY'));
				 $('#rpt_payments1').text(moment().startOf('month').format('YYYY-MM-DD'));
				 $('#rpt_payments2').text(moment().endOf('month').format('YYYY-MM-DD'));

			  		 $('#paymentmodewise_date').daterangepicker(

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
                     var id_branch = $('#branch_select').val();
		             generate_paymodewise_list(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'),'','','',id_branch);
						$('#rpt_payments1').text(start.format('YYYY-MM-DD'));
						$('#rpt_payments2').text(end.format('YYYY-MM-DD'));			            

		          }

			 ); 			  


		    break;
		
		case 'accounts_schemewise':
			
			
			scheme_wise_account();
				

		break;
		
		case 'payment_details':
			customer_wise_payment();
			$('#rpt_customer_unpaid1').empty();
			   $('#rpt_customer_unpaid2').empty();
		       //customer_wise_payment(moment().startOf('month').format('MMMM D, YYYY'),moment().endOf('month').format('MMMM D, YYYY'));
			    $('#rpt_customer_unpaid1').text(moment().startOf('month').format('YYYY-MM-DD'));
			    $('#rpt_customer_unpaid2').text(moment().endOf('month').format('YYYY-MM-DD'));

					 $('#rpt_customer_unpaid').daterangepicker(

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

		      function (start, end) 
		      {
		            var id_branch = $('#branch_select').val();
		           //customer_wise_payment(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'),id_branch);
			   		$('#rpt_customer_unpaid1').text(start.format('YYYY-MM-DD'));
			   		$('#rpt_customer_unpaid2').text(end.format('YYYY-MM-DD'));			            

		      }

			);
			

		break;
		
		case 'inter_wallet_woc':

				get_inter_wallet_woc();
			
		break;
		
		case 'payment_schemewise':
			
			
			$('#rpt_scheme_payment1').empty();
			   $('#rpt_scheme_payment2').empty();
		       payment_schemewise(moment().startOf('month').format('MMMM D, YYYY'),moment().endOf('month').format('MMMM D, YYYY'));
			    $('#rpt_scheme_payment1').text(moment().startOf('month').format('YYYY-MM-DD'));
			    $('#rpt_scheme_payment2').text(moment().endOf('month').format('YYYY-MM-DD'));

					 $('#rpt_scheme_payment').daterangepicker(

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

		      function (start, end) 
		      {
		        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                    var id_branch = $('#branch_select').val();
		           payment_schemewise(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'),id_branch);
			   		$('#rpt_scheme_payment1').text(start.format('YYYY-MM-DD'));
			   		$('#rpt_scheme_payment2').text(end.format('YYYY-MM-DD'));			            

		      }

			);
				

		break;
			

		case 'payment_datewise_schemedata':
			
			
			var selected_date = $("#schreport_date").val();

			generate_paymodewise_schemelist(selected_date);
				

		break;
		
		case 'payment_online_offline_collec_data':
			
			
			var selected_date = $("#modereport_date").val();

			generate_online_offline_collection(selected_date);
				

		break;

		case 'paydatewise_schcoll_data':

			
		
			 var selected_date = $("#schwisereport_date").val();

			 generate_paydatewise_schcoll(selected_date);

		break;
		
		case 'payment_outstanding':

			var selected_date = $("#payoutcus").val();
			var id_branch = $("#branch_select").val();

			generate_payout_cuslist(selected_date,id_branch);

		break;
		
				
		case 'interwalTrans_list':
		    get_interWalTrans_list();
				$('#rpt_payments1').empty();
				$('#rpt_payments2').empty();
            $('#wallet_trans_date').daterangepicker(
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
            	get_interWalTrans_list(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'))
            	$('#rpt_payments1').text(start.format('YYYY-MM-DD'));
				$('#rpt_payments2').text(end.format('YYYY-MM-DD'));
            	}
            );
		break;
		
		case 'closed_acc_report':
                   
                    var date = new Date();
        		    var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 0, 1); 
        			var from_date =  firstDay.getFullYear()+'-'+(firstDay.getMonth()+1)+'-'+firstDay.getDate();
        			var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
			        $('#rpt_payments1').html(from_date);
                    $('#rpt_payments2').html(to_date);
                    get_closed_acc_list();
                  
                    $('#rpt_payment_date').daterangepicker(
                    {
                        ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                        },
                        startDate: moment().subtract(0, 'days'),
                        endDate: moment()
                    },
                    function (start, end) {
                    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                    $('#rpt_payments1').text(start.format('YYYY-MM-DD'));
                    $('#rpt_payments2').text(end.format('YYYY-MM-DD'));		            
                    }
                    ); 
			break;
		
		case 'collection_report':
			       
				    get_schemeclassifyname();
                    var date = new Date();
        		    var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 0, 1); 
        			var from_date =  firstDay.getFullYear()+'-'+(firstDay.getMonth()+1)+'-'+firstDay.getDate();
        			var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
			        $('#rpt_payments1').html(from_date);
                    $('#rpt_payments2').html(to_date);
                    get_collection_report(from_date,to_date);
                  
                    $('#rpt_payment_date').daterangepicker(
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
                   // getPaymentDateRangeList(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
                    $('#rpt_payments1').text(start.format('YYYY-MM-DD'));
                    $('#rpt_payments2').text(end.format('YYYY-MM-DD'));		            
                    }
                    ); 
			break;
		case 'customer_account_details':
            var date = new Date();
            
            var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 30, 1); 
            var from_date =  firstDay.getDate()+'-'+(firstDay.getMonth()+1)+'-'+firstDay.getFullYear();
            var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
            
            $('#rpt_payments1').text(from_date);
            $('#rpt_payments2').text(to_date);
            get_schemename();
            get_customer_account_details(from_date,to_date);
            
            $('#rpt_payment_date').daterangepicker(
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
                function (start, end) 
                {
                    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                    get_customer_account_details(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'),'','');
                    $('#rpt_payments1').text(start.format('YYYY-MM-DD'));
                    $('#rpt_payments2').text(end.format('YYYY-MM-DD'));		            
                }
            ); 
        break;
		
//emp reff_report begin

case 'employee_ref_success':
			 
			 $('#rpt_payments1').empty();
			 $('#rpt_payments2').empty();
			 
			  payment_employee_ref_success(moment().startOf('month').format('MMMM D, YYYY'),moment().endOf('month').format('MMMM D, YYYY'));
			  
			
			 $('#rpt_emp_ref').text(moment().startOf('month').format('YYYY-MM-DD'));
			 $('#rpt_emp_ref2').text(moment().endOf('month').format('YYYY-MM-DD'));
			$('#rpt_payment_date').daterangepicker(

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

                     
			  var id_branch=$("#branch_select").val();
             payment_employee_ref_success(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'),id_branch)
			 
			$('#rpt_payments1').text(start.format('YYYY-MM-DD'));
			$('#rpt_payments2').text(end.format('YYYY-MM-DD'));	

          }

        );   
			break;
			
			
			
		case 'cus_ref_success':
		
		
			payment_cus_ref_success();
			
			$('#cus_ref_report_data').daterangepicker(

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

                     

             payment_cus_ref_success(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'))

          }

        );   
		
		
		
			
		break;
		
		case 'customer_enquiry':
		            $("#feed_filter_status,#feed_filter_type").select2().on("change", function(e) 
            		{
            		    get_enquiry_list();
            		})
		            get_enquiry_list();
		            
        			$('#enquiry_date').daterangepicker(

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
        
                     get_enquiry_list(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'))
        
                  }
        
                );  
        break;			

			
//emp reff_report end
		

		default:

			break;

	} 

		  if(ctrl_page[2]=="range")

			  {     console.log($('input[name=pay_type]').val());

			  		generate_payment_list(moment().startOf('month').format('MMMM D, YYYY'),moment().endOf('month').format('MMMM D, YYYY'));
			  		
			  		 $('#rpt_payment_date1').empty();
                     $('#rpt_payment_date2').empty();
                     $('#rpt_payment_date1').text(moment().startOf('month').format('YYYY-MM-DD'));
                     $('#rpt_payment_date2').text(moment().endOf('month').format('YYYY-MM-DD'));
			  		 $('#rpt_payment_date').daterangepicker(

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
                    $('#rpt_payment_date1').text(start.format('YYYY-MM-DD'));
                    $('#rpt_payment_date2').text(end.format('YYYY-MM-DD'));
		                

		             generate_payment_list(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'))

		          }

        ); 

			  }

	

	//var payment_list = $('.payreport_customer').DataTable( { "dom": 'T<"clear">lfrtip', "tableTools": { "aButtons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] } } );

	 

   $("#check_transaction").click(function(){

   	 // $('form#failed_txn').submit();

	   	 var data = { 'txn_ids[]' : []};

		$("input[name='txnid[]']:checked").each(function() {

		  data['txn_ids[]'].push($(this).val());

		});

		

				$.ajax({

					      type: "POST",

						  url: base_url+"index.php/payment/verify",

						  data: data,

						  success: function(response){

							  	 $('#alert_msg').html(response);

							  	 $(".alert").css("display","block"); 

							  	 setTimeout(function(){

							

							         window.location.reload();

							         /* or window.location = window.location.href; */

							   

							}, 5000);

						 }				 

			   });

   });

   

   

//date range payment report

 $("#gen_rep").click(function () {

									   

		data = $('#payment_range').serialize();

	  

		var p_status = $("input:radio[name='pay_status']:checked").val();

		var p_mode = $("input:radio[name='pay_mode']:checked").val();

		var frm_date = $("#frm_date").val();

		var to_date = $("#to_date").val();

		p_status = (p_status !='' ? p_status: 'ALL' );

		p_mode   = (p_mode !='' ? p_mode: ' ' );

		if(frm_date !="" && to_date!="")

		{

			

			

			$.ajax({

					  type: "POST",

					  url: base_url+"index.php/reports/payment/range/date",

					  data: {from_date:frm_date, to_date: to_date,p_status:p_status, p_mode: p_mode},

					  dataType: 'json',

					  success:function(data){

						  

						  console.log(data);

						 table_list ='<table id="payment_list" class="table table-bordered table-striped text-center"><thead>'+

						        '<tr>'+

							    '<th>P.ID</th>'+	

								'<th>Paid Date</th>'+							

							//	'<th>Receipt.No</th>'+							

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

							//	'<th>Charge (&#8377;)</th>'+

								'<th>Total Paid (&#8377;)</th>'+

								'<th>Pay Status</th>'+

								'<th>Remark</th>'+

								'</tr></thead><tbody></tbody></table>';

								

						//appending header		 

						

						$('#report_wrapper').html(table_list);

				

						 trHTML ='';

						  /*var payment_list = $('#payment_list').DataTable();*/

						  var payment_list = $('.payment_list').DataTable( { "dom": 'T<"clear">lfrtip', "tableTools": { "aButtons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] } } );

						 //destroy datatable

						 

						 payment_list.destroy();

					    

						$.each(data, function (i, item) {

							trHTML += '<tr>' +

										'<td>' + item.id_payment + '</td>' +

										'<td>' + item.trans_date + '</td>' +						

									//	'<td>' + item.receipt_jil + '</td>' +							

										'<td>' + item.id_transaction + '</td>' +

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

									//	'<td>' + item.bank_charges + '</td>' +

										'<td>' + item.paid_amt + '</td>' +

										'<td>' + item.pay_status + '</td>' +								

										'<td>' + item.remark + '</td>' +								

										'</tr>';

						});

                       

                     

                  

                      $('#payment_list > tbody').html(trHTML);



						

					 /* payment_list =	$('#payment_list').dataTable({

							  "bPaginate": true,

							  "bLengthChange": true,

							  "bFilter": true,

							  "bSort": true,

							  "bInfo": true,

							  "bAutoWidth": true

							});*/

						payment_list = $('#payment_list').DataTable(  { "dom": 'T<"clear">lfrtip', "tableTools": { "aButtons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] } } );				

							

					  }

					  

				  });

		}	

		

	});

 //end of date range payment report

 
 
 //emp_reff_begin
 
 
	function payment_cus_ref_success(from_date="",to_date="")
{
	
	my_Date = new Date();

	 $("div.overlay").css("display", "block"); 	
	$.ajax({
			 type: "POST",	
			 url:base_url+ "index.php/reports/payment_cus_ref_success?nocache=" + my_Date.getUTCSeconds(),
			data: (from_date !='' && to_date !=''? {'from_date':from_date,'to_date':to_date}: ''),			 
			dataType: 'json',			
			success:function(data){	
			
			var oTable = $('#cus_refferal').DataTable();			 
				oTable.clear().draw();				
			  	 if (data.accounts!= null && data.accounts.length >0)
			  	  { 	
					oTable = $('#cus_refferal').dataTable({
				                        "bDestroy": true,
										"bInfo": false,
										"bFilter": true,								
										"scrollX":'100%',
										"bAutoWidth": false,
										"bSort": true,
										"lengthMenu":[
										 [ 10, 25, 50, -1 ],
										 [ '10 rows', '25 rows', '50 rows', 'Show all' ]
										],
										
										 "dom": 'Bfrtip',
										 "buttons": [
											{
											   extend: 'print',											   
											   customize: function ( win ) {
													$(win.document.body).find( 'table' )
														.addClass( 'compact' )
														.css( 'font-size', 'inherit' );
												},
											 },
											 {
												extend:'pageLength',
												customize: function ( win ) {
														$(win.document.body).find( 'table' )
															.addClass( 'compact' )
															.css( 'font-size', 'inherit' );
													},
										 }, 
											 ], 
				                "aaData": data.accounts,
				                "aoColumns": [
							{ "mDataProp": function ( row, type, val, meta ) {
					                	
					                	action = '<a href="'+base_url+'index.php/reports/payment/cus_refferl_account/'+row.mobile+'" target="_blank">'+row.id_customer+'</a>';
					                	return action;
					                	}
					                },               
					                { "mDataProp": "name"}, 
					                { "mDataProp": "cus_referalcode"},					                
					                { "mDataProp": "refferal_count"},        
					                { "mDataProp": "benifits"},        
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
									   i : 0;  };
			// paid Total 
            paid = api
                .column(4,{ page: 'current'})
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            $( api.column(4).footer() ).html(parseFloat(paid));
			
			}
		}
			else{
					var data=0;
					 var api = this.api(), data;
					 $( api.column(4).footer() ).html('');
				  }
				
			  }

									
					          });			  	 	
					  	 }	
					  	 
					  },
					  error:function(error)  
					  {
						
						 $("div.overlay").css("display", "none"); 
					  }	 
			      });
}





	function payment_employee_ref_success(from_date="",to_date="",id_branch="")
{
	
	my_Date = new Date();

	 $("div.overlay").css("display", "block"); 	
	$.ajax({
			 type: "POST",	
			 url:base_url+ "index.php/reports/employee_ref_success_list?nocache=" + my_Date.getUTCSeconds(),
			data: (from_date !='' && to_date !=''? {'from_date':from_date,'to_date':to_date}: ''),			 
			dataType: 'json',			
			success:function(data){	
			
			var oTable = $('#employee_refferal').DataTable();			 
				oTable.clear().draw();				
			  	 if (data.accounts!= null && data.accounts.length >0)
			  	  { 	
					oTable = $('#employee_refferal').dataTable({
										"bDestroy": true,
										"bInfo": false,
										"bFilter": true,								
										"scrollX":'100%',
										"bAutoWidth": false,
										"bSort": true,
									
										 "dom": 'Bfrtip',
										 'columnDefs': [{
											 'targets': 0,
											 'searchable':false,
											 'orderable':false,
											 "bSort": true,
											 'className': 'dt-body-center',
											 }],
										 "buttons": [
											/* {
											   extend: 'print',											   
											   customize: function ( win ) {
													$(win.document.body).find( 'table' )
														.addClass( 'compact' )
														.css( 'font-size', 'inherit' );
												},
											 }, */
											 {
												extend:'pageLength',
												customize: function ( win ) {
														$(win.document.body).find( 'table' )
															.addClass( 'compact' )
															.css( 'font-size', 'inherit' );
													},
										   }, 
											 ], 
				                "aaData": data.accounts,
				                "aoColumns": [
							    { "mDataProp": function ( row, type, val, meta ) {
					                	
					                	action = '<input type="checkbox" id="select_emp_'+row.id_employee+'" class="select_idemp"  value="'+row.id_employee+'">'+'&nbsp;&nbsp;&nbsp;&nbsp;'+'<a href="'+base_url+'index.php/reports/payment/refferl_account/'+row.emp_code+'" target="_blank">'+row.id_employee+'</a>';
					                	return action;
					                	}
					                },               
					                { "mDataProp": "name" },
					                { "mDataProp": "emp_code" },					                
					                { "mDataProp": "refferal_count" }, 
					              /*   { "mDataProp": function ( row, type, val, meta ) {
					                	if(row.issue_type == 'Credit')
					                	{
					                	    action = '<b style="color:#48e116;">Credit</b>';
					                	    
					                	}else{
					                	    action = '<b style="color:red;">Debit</b>';
					                	}
					                	return action;
					                }},     */
					                { "mDataProp": "total_amount" }, 
					                { "mDataProp": "benifits" },        
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
									   i : 0;  };
			/* // paid Total 
            paid = api
                .column(5,{ page: 'current'})
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            $( api.column(5).footer() ).html(parseFloat(paid)); */
			
			// Amt Total  //hh 
            paid = api
                .column(4,{ page: 'current'})
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            $( api.column(4).footer() ).html(parseFloat(paid));
			
				// BenAmt Total 
            paid = api
                .column(5,{ page: 'current'})
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            $( api.column(5).footer() ).html(parseFloat(paid));
			
			}
		}
			else{
					 var data=0;
					 var api = this.api(), data;
					 $( api.column(4).footer() ).html('');
					 $( api.column(5).footer() ).html('');
				  }
				
			  }
					});			  	 	
					  	 }	
					  	 
					  },
					  error:function(error)  
					  {
						
						 $("div.overlay").css("display", "none"); 
					  }	 
			      });
}


// Employee referral Reports//


$(document).on('click', '#select_emp', function(e){
	
			if($(this).prop("checked") == true){
		 
                $("tbody tr td input[type='checkbox']").prop('checked',true);}
            else if($(this).prop("checked") == false){
				$("tbody tr td input[type='checkbox']").prop('checked', false);
            }	
 
});



 
$(document).on('click', '.print_emp', function(e){
 var empdata = [];
 var ids='';
	  
		
	   $("#employee_refferal tbody tr").each(function(index, value) 
		{
		if(!$(value).find(".select_idemp").is(":checked"))
			 { 
				$(value).find(".select_idemp").empty();	
			 }
		    else if(($(value).find(".select_idemp").is(":checked"))){
					   var id_employee=$(value).find(".select_idemp").val();
						 var data ={'id_employee':id_employee}; 
					  // var sech = JSON.stringify(data);
					   empdata.push(data);
					   
					   ids+='<input type="hidden" name=emp[] value='+$(value).find(".select_idemp").val()+'>';
				   }
				else{
				  
				  msg='<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong>Select to proceed</div>';
						
						$("div.overlay").css("display", "none"); 
								
								//stop the form from submitting
								 $('#error-msg').html(msg);
				return false;
				  
			  }

      });
	  
	  if(empdata.length>0 && ids!=''){		  
	     $('.refdata').append(ids);
		 $('.print_emp').attr('disabled',true);
		 $('#emp_ref').submit();
	  }
});


// Employee referral Reports//

  //emp_reff_end

 /*Coded by ARVK*/

 

 	$('#report_date').datepicker({

	               dateFormat: 'dd-mm-yyyy'

	})

    .on('changeDate', function(ev){

	

		$(this).datepicker('hide');

		var selected_date = $("#report_date").val();

		//console.log(selected_date);

			my_Date = new Date();

			$.ajax({

					  type: "POST",

					  url: base_url+"index.php/reports/payment_datewise_ajax?nocache=" + my_Date.getUTCSeconds(),

					  data: {date:selected_date},

					  dataType: 'json',

					  success:function(data){

					  	

					  	var oTable = $('#datewise_paid_report').DataTable();

		     			oTable.clear().draw();

		     			

		     			var today_total=0;

		     			$.each(data.payments, function(i,item) {

		     				today_total = (parseInt(today_total) + parseInt(item.classification_total));

		     				});

		     			var closing_bal = (parseInt(today_total) + parseInt(data.opening_balance));

		     			

		     			/*console.log(parseFloat(data.opening_balance).toFixed(2));

		     			console.log(parseFloat(today_total).toFixed(2));

		     			console.log(parseFloat(closing_bal).toFixed(2));*/

		     			

				  	 	if (data.payments!= null && data.payments.length > 0)

				  	  	{  	

						  	oTable = $('#datewise_paid_report').dataTable({

					                "bDestroy": true,

					                "bInfo": true,

					                "bFilter": true,

					                "bSort": true,

					                "dom": 'T<"clear">lfrtip',

					                "tableTools": { "aButtons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },

					                "aaData": data.payments,

					                "aoColumns": [{ "mDataProp": "id_classification" },

						                { "mDataProp": "classification_name" },

						                { "mDataProp": "classification_total" }] 



					            });			  	 	

						  	 }

						  	 

						  	 $('#open_bal').html(parseFloat(data.opening_balance).toFixed(2));

						  	 $('#tot_coll').html(parseFloat(today_total).toFixed(2));

						  	 $('#close_bal').html(parseFloat(closing_bal).toFixed(2));

						  	 

						  }

		});

	});

	

// payment_datewise_schemedata	

	

$('#schreport_date').datepicker({

	              dateFormat: 'yyyy-mm-dd',

	})

    .on('changeDate', function(ev){

	

		$(this).datepicker('hide');

		var selected_date = $("#schreport_date").val();
		
		 var id_branch    = $("#branch_select").val();
		 
		  var id_employee=$('#id_employee').val();
		  
		  var added_by=$('#added_by').val();

		if(selected_date!=''){

			generate_paymodewise_schemelist(selected_date,id_branch,id_employee,added_by);

		}
	});
	
		$('#modereport_date').datepicker({

	              dateFormat: 'yyyy-mm-dd',

	})
	
	 .on('changeDate', function(ev){

	

		$(this).datepicker('hide');
       
		var selected_date = $("#modereport_date").val();
		
		var added_by=$('#added_by').val();

		if(selected_date!=''){

			generate_online_offline_collection(selected_date,added_by);

		}
	});
	
	
	// payment outstanding 

	$('#payoutcus').datepicker({

	              dateFormat: 'yyyy-mm-dd',

	})

    .on('changeDate', function(ev){

	

		$(this).datepicker('hide');

		var selected_date = $("#payoutcus").val();
		var id_branch = $("#branch_select").val();

		if(selected_date!=''){

			generate_payout_cuslist(selected_date,id_branch);

		}
	});
	
// payment_datewise_schemedata	



////paydatewise_schcoll_data	

	

	$('#schwisereport_date').datepicker({

	              dateFormat: 'yyyy-mm-dd',

	})

    .on('changeDate', function(ev){

	

		$(this).datepicker('hide');

		var selected_date = $("#schwisereport_date").val();
		var id_branch     = $("#branch_select").val();

		if(selected_date!=''){

			generate_paydatewise_schcoll(selected_date,id_branch);

		}

		

		

	});

	

//paydatewise_schcoll_data	

	

});

$('#scheme_select').select2().on("change", function(e) 
		{
			switch(ctrl_page[1])

			{

				case 'payment_daterange':
				
					
					if(this.value!='')
					{  
						
						var from_date=$('#rpt_payments1').text();
						var to_date=$('#rpt_payments2').text();
						var id_branch = $('#branch_select').val();
						var id_employee = $('#id_employee').val();
						var id_scheme=$(this).val();
						generate_payment_daterange(from_date,to_date,'','',id_scheme,id_branch,id_employee);
					}
					 break;		
									
									
			case 'payment_modewise_data':	

					if(this.value!='')
					{	 
						var from_date=$('#rpt_payments1').text();
						var to_date=$('#rpt_payments2').text();
						var id_branch = $('#branch_select').val();
						var id_scheme=$(this).val();						
						generate_paymodewise_list(from_date,to_date,'','',id_scheme,id_branch);
					}

					break;
			case 'customer_account_details':
					        var from_date=$('#rpt_payments1').text();
                            var to_date=$('#rpt_payments2').text();
                            get_customer_account_details(from_date,to_date);
					break;
			}
					
          });






 $('#update_status').click(function(){

 		get_table_values();						

 });

 	//for select all

	  	$('#select_all').click(function(e){

	  		

	  		 if (e.stopPropagation !== undefined) {

		        e.stopPropagation();

		         $('input[name="id_payment"]').prop('checked', $(this).prop('checked'));

		       

		    } else {

		        e.cancelBubble = true;

		    }

	  	});	  	 

	  	

function generate_failed_payments()

{

		

		$("#img_loader").show();	

			

			$.ajax({

					  type: "GET",

					  url: base_url+"index.php/reports/get/payment/failed",

					  dataType: 'json',

					  success:function(data){

						 // console.log(data);

						 table_list ='<table id="payment_list" class="table table-bordered table-striped text-center"><thead>'+

						        '<tr>'+

							    '<th><label class="checkbox-inline"><input type="checkbox" id="sel_failed_all" name="select_all" value="all"/>All</label></th>'+	

								'<th>Paid Date</th>'+							

								'<th>Trans ID</th>'+	

								'<th>PayU ID</th>'+									

								'<th>Client ID</th>'+

								'<th>Name</th>'+

								'<th>Mobile</th>'+ 

								'<th>Chit.No</th>'+   

								'<th>Pay Mode</th>'+    

								'<th>Metalrate (&#8377;)</th>'+

								'<th>Metalweight (g)</th>'+

								'<th>Amount (&#8377;)</th>'+

								'<th>Total Paid (&#8377;)</th>'+

								'<th>Pay Status</th>'+

								'<th>Remark</th>'+

								'</tr></thead><tbody></tbody></table>';

								

						//appending header		 

						

						$('#failed_report').html(table_list);

				

						 trHTML ='';

						/*  var payment_list = $('#payment_list').DataTable();*/

						  payment_list = $('#payment_list').DataTable( { "dom": 'T<"clear">lfrtip', "tableTools": { "aButtons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } } ] } } );	

						 //destroy datatable

						 

						 payment_list.destroy();

					    

						$.each(data, function (i, item) {

							trHTML += '<tr>' +

										'<td><label class="checkbox-inline"><input type="checkbox" name="txnid[]" value="'+item.trans_id+'"/> ' + item.id_payment + '</label></td>' +

										'<td>' + item.trans_date + '</td>' +						

										'<td>' + item.trans_id + '</td>' +

										'<td>' + item.payu_id + '</td>' +								

										'<td>' + item.client_id + '</td>' +

										'<td>' + item.name + '</td>' +

										'<td>' + item.mobile + '</td>' +

										'<td>' + item.chit_number + '</td>' +

										'<td>' + item.payment_mode + '</td>' +

										'<td>' + item.rate + '</td>' +

										'<td>' + item.weight + '</td>' +

										'<td>' + item.amount + '</td>' +

										'<td>' + item.paid_amt + '</td>' +

										'<td>' + item.pay_status + '</td>' +							

										'<td>' + item.remark + '</td>' +								

										'</tr>';

						});

                       

                     

                  

                      $('#payment_list > tbody').html(trHTML);



						

					  /*payment_list =	$('#payment_list').dataTable({

							  "bPaginate": true,

							  "bLengthChange": true,

							  "bFilter": true,

							  "bSort": false,

							  "bInfo": true,

							  "bAutoWidth": true

							});*/

						

						payment_list = $('#payment_list').DataTable( { "dom": 'T<"clear">lfrtip', "tableTools": { "aButtons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } } ] } } );								 

						$('#sel_failed_all').click(function(event) {							

							$("tbody tr td input[type='checkbox']").prop('checked', $(this).prop('checked'));

					        event.stopPropagation();					     

					    });

					    

					    $("#img_loader").hide();

							

					  }

					  

				  });

}







function get_postdated_data(filter,mode,status)

{ 

	my_Date = new Date();

	  $('body').addClass("sidebar-collapse");	

	$('.overlay').css('display','block');

	$.ajax({

	  type: 'POST',	 

	  url:  base_url+'index.php/postdated/status/list?nocache=' + my_Date.getUTCSeconds(),

	  data:{'payment':{'filter':filter,'mode':mode,'status':status}},

	  // data:{'payment':{'status':status}},

	  dataType: 'json',

	  success: function(data) {

	  	  if(ctrl_page[6]==2)

	  	  {

		  	$('#datepicker_container').css('display','none');

		  	

		  }

		  else

		  {

		  	$('#datepicker_container').css('display','block');

		  }

	  	  	  	 

	  	  $('#total_payments').text(data.payments.length);

	  	  

	  	  //fill list 

	  	  set_postdated_list(data.payments);

	  	  var dropdown_select = (data.payments.length>0?(ctrl_page[6]==7?2:1):'');

	  	  //fill Dropdown

	  	  fill_status_dropdown('sel_payment_status',data.payment_status,dropdown_select);

	  	  //remove cheque_no column for ecs

	 /* 	  if(ctrl_page[5]=='ecs')

	  	  {

	  	   $('#rep_post_payment_list tr').find('td:eq(4),th:eq(4)').remove();

	  	  } */

	  	  

	  	  $('.overlay').css('display','none');

	  	  

	  	},

	   error: function(XMLHttpRequest, textStatus, errorThrown) {

	     console.log("some error");

	     $('.overlay').css('display','none');

	  }	

	  });

	  	    

}



 function sumByClass(column_class)

 {

 	var sum = 0;

	// iterate through each td based on class and add the values

	$("."+column_class).each(function() {



	    var value = $(this).text();

	    // add only if the value is number

	    if(!isNaN(value) && value.length != 0) {

	        sum += parseFloat(value);

	    }

	});

	return sum;

 }  

function set_postdated_list(data)

{

	 var payment = data;	

	 

	 var columns =[{ "mDataProp":function (row,type,val,meta){

						id=row.id_post_payment;

						return "<label class='checkbox-inline'><input type='checkbox' class='flat-red' name='id_payment' value='"+id+"' /> "+id+" </label>";

									} },

					{ "mDataProp":"date_payment"},	

					{ "mDataProp":"cus_name"},	

					{ "mDataProp":"account_name"},			

					{ "mDataProp":"scheme_acc_number"},

					{ "mDataProp":"pay_mode"},

		      	 	  { "mDataProp":function (row,type,val,meta){

				    	 return "<input type='hidden' name='cheque_no' value='"+row.cheque_no+"' />"+row.cheque_no

			         }

				    },

				 	{ "mDataProp":"payee_short_code"},

					{ "mDataProp":"drawee_account_name"},

					{ "mDataProp":"drawee_acc_no"},

					{ "mDataProp":"drawee_short_code"},

					{ "mDataProp":"amount"},

				    { "mDataProp":  function ( row, type, val, meta ) {				                	 

	                	 return "<input type='text' class='form-control input-sm' name='payment_ref_number' />";

	                   }

				    },

					{ "mDataProp":function(row,type,val,meta){

						

						action_content ="<input type='hidden' name='date_payment' value='"+row.date_payment+"' />"+

								"<input type='hidden' name='id_scheme_account' value='"+row.id_scheme_account+"' />"+

								"<input type='hidden' name='pay_mode' value='"+row.pay_mode+"' />"+

								"<input type='hidden' name='bank_acc_no' value='"+row.payee_acc_no+"' />"+

								"<input type='hidden' name='bank_name' value='"+row.payee_bank+"' />"+

								"<input type='hidden' class='pdc_amount' name='payment_amount' value='"+row.amount+"' />"+

								"<input type='hidden' name='payment_status'/>"+

								"<input type='hidden'  name='date_presented' />"+

								"<input type='hidden'  name='charges' />"+

								"<span class='label bg-"+row.status_color+"-active'>"+row.payment_status+"</span>";

							//"<select class='form-control pay_status' name='payment_status'>";

						return action_content;

					 },

				  }	 ,

					 { "mDataProp": function ( row, type, val, meta ) {

									 id= row.id_post_payment;

									 edit_url= base_url+'index.php/postdated/payment_entry/edit/'+id;

									 status_url = base_url+'index.php/postdated/payment_entry/status/'+id ;

								

								

									

									 action_content='<div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button><ul class="dropdown-menu">'+

					'<li><a href="'+edit_url+'" class="btn-edit"><i class="fa fa-edit" ></i> Edit</a></li>'+

		

					'<li><a href="'+status_url+'" class="btn-edit"><i class="fa fa-search-plus" ></i> Status</a></li></ul></div>';

							  

							return action_content;

							}

					}		

				 ];

				              

	generate_datatable('rep_post_payment_list',data,columns)	

	/*var tfoot = "<tfoot><td colspan='9'>Total</td><td>"+sumByClass('pdc_amount')+"</td><td></td><td></td></tfoot>";

	$('#rep_post_payment_list').append(tfoot);		*/       

      



}





//to fill select box

function fill_status_dropdown(elementID,data,selected="")

{

	     $.each(data, function (key, item) {

					  	

			   

				$('#'+elementID).append(

					$("<option></option>")

					  .attr("value", item.id_status_msg)

					  .text(item.payment_status)

					  

				);

				

			});

			

			$("#"+elementID).select2({

			    placeholder: 'Select payment status',

			    allowClear: true

			});		

			

	

				$("#"+elementID).select2("val",selected);

}



//to fill select box

function fill_dropdownbyclass(elementID,data,selected="")

{

     $.each(data, function (key, item) {

			$('.'+elementID).append(

				$("<option></option>")

				  .attr("value", item.id_status_msg)

				  .text(item.payment_status)

			);

		});

		

		$("."+elementID).select2({

		    placeholder: 'Select payment status',

		    allowClear: true

		});	



		$("."+elementID).select2("val",selected);

}







//for get all selected values

function get_table_values()

{		

	var table_data = [];

    var values = {};

    $("#rep_post_payment_list > tbody > tr").each(function(i){

        values = new Object;



       if( $(this).find('input[type="checkbox"]').is(':checked') && $('#sel_payment_status').val()!=null){ 

       	   

       	   //update status for selected row

	   	   $('input[name="payment_status"]').val($('#sel_payment_status').val());

	   	   $('.pay_status').select2("val",$('#sel_payment_status').val());

	   	   $('input[name="charges"]').val($('#sub_charge').val());

	   	 

	   	    //fetch values

	        $('input', this).each(function(){

	        	if($(this).val()!='')

	        	{ 

	        	  if($(this).attr('type') == 'checkbox')

				  {

				  	 values[$(this).attr('name')] =($(this).is(':checked')?$(this).val():0);

				  }

				  else

				  {

				  	

				  		 values[$(this).attr('name')]=$(this).val();

				  }	

		         }

	        });

	        

        	table_data.push(values);

        }

        console.log(table_data);

    });

    

     $("#sel_payment_status").select2("val", '');

     //removes the first elemet

     //table_data.shift(); 

   update_postdata(table_data);

     	

}



function update_postdata(data)

{

	if (data.length != 0 ) {

     $("div.overlay").css("display", "block"); 

		var postData ={'postpay_data':JSON.stringify(data)};

		var my_Date = new Date();

		

		

			$.ajax({

				url : base_url+"index.php/postdated/payment/update",

				type : "POST",

				data : postData,

				success: function(result)

				{

				 	$("div.overlay").css("display", "none"); 

				   	 	 $('#pdp-alert').delay(500).fadeIn('normal', function() {

					   	 	  $(this).find("p").html(result);

					   	 	  $(this).addClass("alert-success ");

						      $(this).delay(1000).fadeOut();

						 });

					window.location.reload();

				},

				error:function(error)

				{

					console.log(error);

						$("div.overlay").css("display", "none"); 

							$('#pdp-alert').delay(500).fadeIn('normal', function() {

						   	 	  $(this).find("p").html("Unable to proceed request");

						   	 	  $(this).addClass("alert-danger ");

							      $(this).delay(2500).fadeOut();

							 });

								 	

									   			

				}

			});

	}	

}





function generate_payment_list(from_date="",to_date="",type="",limit="")

{



	my_Date = new Date();

	var branch=$('#branch_select').val();
	//console.log(branch);
	$.ajax({

			  url:base_url+"index.php/payment/ajax_list/range?nocache=" + my_Date.getUTCSeconds(),

			 data: (from_date !='' && to_date !=''? {'from_date':from_date,'to_date':to_date,'type':type,'limit':limit,'id_branch':branch}: ''),

			 dataType:"JSON",

			 type:"POST",

			 success:function(data){

			   			 var payment = data.data;

			   		

	 var access = data.access;	

	 var oTable = $('#report_payment_daterange').DataTable();

	     oTable.clear().draw();

			  	 if (payment!= null && payment.length > 0)

			  	  {  	

					  	oTable = $('#report_payment_daterange').dataTable({

				                "bDestroy": true,

				                "bInfo": true,

				                "bFilter": true,

				                "scrollX":'100%',

				                "bSort": true,

				                "dom": 'lBfrtip',
        		                "order": [[ 0, "desc" ]],
        		                "buttons": [
        							{
        							   extend: 'print',
        							   footer: true,
        							   title: 'Payment Date Range '+$('#rpt_payment_date1').text()+' - '+$('#rpt_payment_date2').text(),
        							   customize: function ( win ) {
        								$(win.document.body).find( 'table' )
        									.addClass( 'compact' )
        									.css( 'font-size', 'inherit' );
        								},
        							 },
        							 {
        								extend:'excel',
        								footer: true,
        							    title: 'Payment Date Range '+$('#rpt_payment_date1').text()+' - '+$('#rpt_payment_date2').text(),
        							  }
        						],

				                "aaData": payment,

				                "aoColumns": [{ "mDataProp": "id_payment" },
					                
					                { "mDataProp": "date_payment" },

					                { "mDataProp": "name" },
					                
					                { "mDataProp": "branch_name" },

					                { "mDataProp": "account_name" },

					                { "mDataProp": "scheme_acc_number" },

					                { "mDataProp": "mobile" },

					                { "mDataProp": "payment_type" },

					                { "mDataProp": "payment_mode" },

					                { "mDataProp": "metal_rate" },

					                { "mDataProp": "metal_weight" },

					                { "mDataProp": "payment_amount" },

					                { "mDataProp": "payment_ref_number" },

					                { "mDataProp": function(row,type,val,meta)

					                	{return "<span class='label bg-"+row.status_color+"-active'>"+row.payment_status+"</span>";	}

					                

					               

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





//new reports  
//payment_list_daterange acc wise filter//hh

$('#acc_Select').select2().on("change", function(e) 
{           if(this.value!='')
			{  
			
				$("#id_pay").val((this).value);
			}
});
//payment_list_daterange acc wise filter//hh

//payment_by_daterange // hh	

function generate_payment_daterange(from_date="",to_date="",type="",limit="",id="",id_branch="",id_employee="",acc="")
{
	
	my_Date = new Date();
    var date_type=$('#date_Select').find(":selected").val();
    var acc=$('#acc_Select').find(":selected").val();
	$("div.overlay").css("display", "block"); 
	
	$.ajax({
			  url:base_url+"index.php/payment/ajax_list/range_list?nocache=" + my_Date.getUTCSeconds(),
			  data: (from_date !='' && to_date !=''? {'from_date':from_date,'to_date':to_date,'type':type,'limit':limit,'id':id,'id_branch':id_branch,'id_employee':id_employee,'date_type':date_type,'acc':acc}: ''),
			 dataType:"JSON",
			 type:"POST",
			 success:function(payment){	
			 
			    var gst_number=payment.gst_number;
				var data=payment.account;				
				
				// get gst settings
				var gstsetting= (typeof payment.account == 'undefined' ? '' :payment.account[0].gst_setting);
				
				if(gstsetting==1)
				{
				var gstno="<span style='font-size:13pt; float:right;'> GST Number - "+gst_number+"</span>";
				}else{
					var gstno='';
				}

				
				var oTable = $('#report_payment_daterange').DataTable();
				oTable.clear().draw();
				
				 var fdate = new Date(from_date);
				 var tdate = new Date(to_date);
				 var date1=fdate.getDate()+'.'+ (fdate.getMonth() + 1) + '.' +  fdate.getFullYear()
				 var date2=tdate.getDate()+'.'+ (tdate.getMonth() + 1) + '.' +  tdate.getFullYear()
				 
				var select_date="<b><span style='font-size:15pt;'>All Scheme Report</span></b></br>"+"<span style=font-size:13pt;>Transaction Details &nbsp;&nbsp;From Date&nbsp;:&nbsp;"+date1+" &nbsp;&nbsp;To Date&nbsp;:&nbsp;"+date2+"</span>"+gstno;
				
				
				
			  	 if (data!= null && data.length > 0)
			  	  {  	 var i=1;
			  
						if(gstsetting ==1 ){
			  
					  	oTable = $('#report_payment_daterange').dataTable({
							
				                "bDestroy": true,
								"responsive": true, 
				                "bInfo": false,
				                "bFilter": true,								
				                "scrollX":'100%',
								"bAutoWidth": false,
				                "bSort": true,
								"dom": 'Bfrtip',
								 "order": [[ 0, "desc" ]],
							    "lengthMenu":[
										 [ 10, 25, 50, -1 ],
										 [ '10 rows', '25 rows', '50 rows', 'Show all' ]
										],
								  buttons: [
								{
									
									   extend: 'print',									   
									   footer: true,
									   title:select_date,
									   customize: function ( win ) {
											$(win.document.body).find( 'table' )
												.addClass( 'compact' )
												.css( 'font-size', 'inherit' );
										},
								},
								
								{
									  extend: 'excel',	
								},
								 {
									extend:'pageLength',
									customize: function ( win ) {
											$(win.document.body).find( 'table' )
												.addClass( 'compact' )
												.css( 'font-size', 'inherit' );
										},
								     }
							  ], 									 
				                "aaData": data,						
								"aoColumns": [								
									{ "mDataProp": "sno" },
									{ "mDataProp": "code" },			
									 { "mDataProp": function ( row, type, val, meta ){
					                	if(row.has_lucky_draw==1 && row.is_lucky_draw==1){
					                	return row.group_code+' '+row.scheme_acc_number;
					                	}
					                	else{
					                		return row.code+' '+row.scheme_acc_number;
					                	}
					                }},

									{ "mDataProp": "receipt_no" },
									{ "mDataProp": "name" },
									{ "mDataProp": "amount" },
									{ "mDataProp": "paid_installments" },
									{ "mDataProp": "date_payment" },
									{ "mDataProp": "emp_code" },
									{ "mDataProp": "payment_ref_number" },
									{ "mDataProp": "ref_trans_id" },
									{ "mDataProp": "card_no" },
									{ "mDataProp": "payment_mode" },
									{ "mDataProp": "metal_rate" },
									{ "mDataProp": "metal_weight" },
									{ "mDataProp": "bank_name" },
									{ "mDataProp": function ( row, type, val, meta ){
										return parseFloat(row.payment_amount).toFixed(2);
									}},
									{ "mDataProp": function ( row, type, val, meta ){
										return parseFloat(row.payment_amount).toFixed(2);
									}},
									{ "mDataProp": function ( row, type, val, meta ){
										return parseFloat(row.discountAmt).toFixed(2);
									}},
									{ "mDataProp": function ( row, type, val, meta ){
										 var gst=parseFloat(parseFloat(row.cgst)+parseFloat(row.sgst)).toFixed(2);
										return parseFloat(gst/2).toFixed(3);
									}},
									{ "mDataProp": function ( row, type, val, meta ){
										 var gst=parseFloat(parseFloat(row.cgst)+parseFloat(row.sgst)).toFixed(2);
										return parseFloat(gst/2).toFixed(3);
									}},{ "mDataProp": function ( row, type, val, meta ){
										
										return parseFloat(parseFloat(row.cgst)+parseFloat(row.sgst)).toFixed(2);
									}},
					                { "mDataProp": function ( row, type, val, meta ){
										
										return parseFloat(parseFloat(row.payment_amount)+
										parseFloat(row.cgst)+parseFloat(row.sgst)).toFixed(2);										
										
									}},		               
					                ],
									
				
				
		"footerCallback": function( row, data, start, end, display ) 
		{
			if(data.length>0){
				 var cshtotal=0;
				 var cctotal=0;
				 var dctotal=0;
				 var chqtotal=0;
				 var ecstotal=0;
				 var nbtotal=0;
				 var canceltotal=0;
				 var fptotal=0;
				 var length=0;
				length=data.length;
			
			 var api = this.api(), data;

					 var intVal = function ( i ) {
						return typeof i === 'string' ?
							i.replace(/[\$,]/g, '')*1 :
							typeof i === 'number' ?i : 
							0;
					};
						
			 
			for( var i=0; i<=data.length-1;i++){				
				
				if(data[i]['payment_mode'] =='CSH'){					
					cshtotal +=  parseFloat(data[i]['payment_amount']);}
				if(data[i]['payment_mode'] =='CC'){
					cctotal += parseFloat(data[i]['payment_amount']);}
				if(data[i]['payment_mode'] =='DC'){
					dctotal += parseFloat(data[i]['payment_amount']);}
				if(data[i]['payment_mode'] =='CHQ'){
					chqtotal+= parseFloat(data[i]['payment_amount']);}
				if(data[i]['payment_mode'] =='ECS'){
					ecstotal+= parseFloat(data[i]['payment_amount']);}
				if(data[i]['payment_mode'] =='NB'){
					nbtotal += parseFloat(data[i]['payment_amount']);}
				if(data[i]['payment_mode'] =='FP'){					
						fptotal +=  parseFloat(data[i]['payment_amount']);}
				if(data[i]['payment_status'] =='Canceled'){					
					canceltotal +=  parseFloat(data[i]['payment_amount']);}
					
            //$( api.column(0).footer() ).html(length);		   
			// Amount Total over this page
            amttotal = api
                .column(5,{ page: 'current'})
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            $( api.column(5).footer() ).html(parseFloat(amttotal).toFixed(2));
			
			// pay_amt 
            pay_amt = api
                .column(12,{ page: 'current'})
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            $( api.column(12).footer() ).html(parseFloat(pay_amt).toFixed(2));

			
			// incen amount
			
			incen = api
                .column(13,{ page: 'current'})
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
			$( api.column(13).footer() ).html(parseFloat(incen).toFixed(2));
			
			
			
			// gstamt amount
             gstamt = api
                .column(16)
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 ); 		
			$( api.column(16).footer() ).html(gstamt.toFixed(2))
			
			 $( api.column(14).footer() ).html(parseFloat(gstamt/2).toFixed(3));
			$( api.column(15).footer() ).html(parseFloat(gstamt/2).toFixed(3));
			
		
			
			
			
			// totamt amount
            totamt = api
                .column(17,{ page: 'current'})
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
			$( api.column(17).footer() ).html(parseFloat(totamt).toFixed(2))
			

		  }
		}
		else{
			var data=0;
			var api = this.api(), data;
			//$( api.column(0).footer() ).html(""); 
			$( api.column(5).footer() ).html("");
            $( api.column(12).footer() ).html(""); 
            $( api.column(13).footer() ).html(""); 
            $( api.column(14).footer() ).html(""); 
            $( api.column(15).footer() ).html(""); 
            $( api.column(16).footer() ).html("");
            $( api.column(17).footer() ).html("");
		}
	}
	
		}); 

	}	
		else{
			
			
			oTable = $('#report_payment_daterange').dataTable({
							
				                "bDestroy": true,
								"responsive": true, 
				                "bInfo": false,
				                "bFilter": true,								
				                "scrollX":'100%',
								"bAutoWidth": false,
				                "bSort": true,
								"dom": 'Bfrtip',
								 "order": [[ 0, "desc" ]],
							    "lengthMenu":[
										 [ 10, 25, 50, -1 ],
										 [ '10 rows', '25 rows', '50 rows', 'Show all' ]
										],
								 buttons: [
								{
									
									   extend: 'print',									   
									   footer: true,
									   title:select_date,
									   customize: function ( win ) {
											$(win.document.body).find( 'table' )
												.addClass( 'compact' )
												.css( 'font-size', 'inherit' );
										},
								},
								
								{
									  extend: 'excel',	
								},
								 {
									extend:'pageLength',
									customize: function ( win ) {
											$(win.document.body).find( 'table' )
												.addClass( 'compact' )
												.css( 'font-size', 'inherit' );
										},
								     }
							  ], 									 
				                "aaData": data,						
								"aoColumns": [								
									{ "mDataProp": "sno" },
									{ "mDataProp": "code" },			
								 { "mDataProp": function ( row, type, val, meta ){
					                	if(row.has_lucky_draw==1 && row.is_lucky_draw==1){
					                	return row.group_code+' '+row.scheme_acc_number;
					                	}
					                	else{
					                		return row.code+' '+row.scheme_acc_number;
					                	}
					                }},

									{ "mDataProp": "receipt_no" },
									{ "mDataProp": "name" },
									{ "mDataProp": "amount" },
									{ "mDataProp": "paid_installments" },
									{ "mDataProp": "date_payment" },
									{ "mDataProp": "emp_code" },
									{ "mDataProp": "payment_ref_number" },
									{ "mDataProp": "id_transaction" },
									{ "mDataProp": "card_no" },
									{ "mDataProp": "payment_mode" },
									{ "mDataProp": "metal_rate" },
									{ "mDataProp": "metal_weight" },
									{ "mDataProp": "bank_name" },
									{ "mDataProp": function ( row, type, val, meta ){
										return parseFloat(row.payment_amount).toFixed(2);
									}},
									{ "mDataProp": function ( row, type, val, meta ){
										return parseFloat(row.discountAmt).toFixed(2);
									}},
									{ "mDataProp": function ( row, type, val, meta ){
										return parseFloat(row.incentive).toFixed(2);
									}},
					               { "mDataProp": function ( row, type, val, meta ){
																				
										return (parseFloat(row.payment_amount)+parseFloat(row.discountAmt)).toFixed(2);
									}}		               
					                ],
									
				
				
		"footerCallback": function( row, data, start, end, display ) 
		{
			if(data.length>0){
				 var cshtotal=0;
				 
				 var cctotal=0;
				 var dctotal=0;
				 var chqtotal=0;
				 var ecstotal=0;
				 var nbtotal=0;
				 var canceltotal=0;
				 var fptotal=0;
				 var length=0;
				 
				length=data.length;
			
			 var api = this.api(), data;

					 var intVal = function ( i ) {
						return typeof i === 'string' ?
							i.replace(/[\$,]/g, '')*1 :
							typeof i === 'number' ?i : 
							0;
					};
						
		
			for( var i=0; i<=data.length-1;i++){				
				
				if(data[i]['payment_mode'] =='CSH'){					
					cshtotal +=  parseFloat(data[i]['payment_amount']);}
				if(data[i]['payment_mode'] =='CC'){
					cctotal += parseFloat(data[i]['payment_amount']);}
				if(data[i]['payment_mode'] =='DC'){
					dctotal += parseFloat(data[i]['payment_amount']);}
				if(data[i]['payment_mode'] =='CHQ'){
					chqtotal+= parseFloat(data[i]['payment_amount']);}
				if(data[i]['payment_mode'] =='ECS'){
					ecstotal+= parseFloat(data[i]['payment_amount']);}
				if(data[i]['payment_mode'] =='NB'){
					nbtotal += parseFloat(data[i]['payment_amount']);}
				if(data[i]['payment_mode'] =='FP'){					
						fptotal +=  parseFloat(data[i]['payment_amount']);}
				if(data[i]['payment_status'] =='Canceled'){					
					canceltotal +=  parseFloat(data[i]['payment_amount']);}
					
            //$( api.column(0).footer() ).html(length);		   
			// Amount Total over this page
            amttotal = api
                .column(5,{ page: 'current'})
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            $( api.column(5).footer() ).html(parseFloat(amttotal).toFixed(2));
			
			// pay_amt 
            pay_amt = api
                .column(13,{ page: 'current'})
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
               
            $( api.column(13).footer() ).html(parseFloat(pay_amt).toFixed(2));
            
            //discount
           
             pay_amt = api
                .column(14,{ page: 'current'})
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            $( api.column(14).footer() ).html(parseFloat(pay_amt).toFixed(2));

			
			// incen amount
			
			incen = api
                .column(15,{ page: 'current'})
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
			$( api.column(15).footer() ).html(parseFloat(incen).toFixed(2));
			
				 console.log(api);		
			// totamt amount
            totamt = api
                .column(16,{ page: 'current'})
                .data()
                .reduce( function (a, b,c) {
                    return intVal(a) + intVal(b);
                }, 0 );
			$( api.column(16).footer() ).html(parseFloat(totamt).toFixed(2))
			

		  }
		}
		else{
			var data=0;
			var api = this.api(), data;
			//$( api.column(0).footer() ).html(""); 
			$( api.column(5).footer() ).html("");
            $( api.column(12).footer() ).html("");
            $( api.column(13).footer() ).html("");
            $( api.column(14).footer() ).html("");
             $( api.column(15).footer() ).html("");
             $( api.column(16).footer() ).html("");
		}
	}
	
		});	
  }
	
}
	 
$("div.overlay").css("display", "none");
	 
   },
		  error:function(error)  
		  {
			 $("div.overlay").css("display", "none"); 
		  }	 
	  });
}
//payment_by_daterange // hh


// paymode_wise_list



function generate_paymodewise_list(from_date="",to_date="",type="",limit="",id="",id_branch="")
{
	$("div.overlay").css("display", "block"); 
	
	my_Date = new Date();
	var date_type=$('#date_Select').find(":selected").val();
	$.ajax({
			  url:base_url+"index.php/reports/paymentmodewise_datalist",
			  data: (from_date !='' && to_date !=''? {'from_date':from_date,'to_date':to_date,'type':type,'limit':limit,'id':id,'id_branch':id_branch,'date_type':date_type}: ''),
			  dataType:"JSON",
			  type:"POST",
			 success:function(payment){
				 
				 
				var data=payment.account;
				var gst_number=payment.gst_number;
				
				var gstsetting= (typeof payment.account == 'undefined' ? '' :payment.account[0].gst_setting);
				
				if(gstsetting ==1)
				{
				var gstno ="<span style='font-size:13pt; float:right;'> GST Number - "+gst_number+"</span>";
				}else{
					var gstno='';
				} 				
				var oTable = $('#paymentmodewise_list').DataTable();
				oTable.clear().draw();
				
				
				var fdate = new Date(from_date);
				 var tdate = new Date(to_date);
				 var date1=fdate.getDate()+'.'+ (fdate.getMonth() + 1) + '.' +  fdate.getFullYear()
				 var date2=tdate.getDate()+'.'+ (tdate.getMonth() + 1) + '.' +  tdate.getFullYear()
				 
				var select_date="<b><span style='font-size:15pt;'>Collection Summary</span></b></br>"+"<span style=font-size:13pt;>Transaction Details &nbsp;&nbsp;From Date&nbsp;:&nbsp;"+date1+" &nbsp;&nbsp;To Date&nbsp;:&nbsp;"+date2+"</span>"+gstno;
				
			  	 if (data!= null && data.length > 0)
			  	  { 
			  
				if(gstsetting ==1){
			  
			  
			  oTable = $('#paymentmodewise_list').dataTable({
							
				                 "bDestroy": true, 
				                "responsive": true, 
				                "bInfo": false,
				                "bFilter": true,								
				                "scrollX":'100%',
								"bAutoWidth": false,
				                "bSort": true,
								 "dom": 'Bfrtip',
								 "lengthMenu":[
										 [ 10, 25, 50, -1 ],
										 [ '10 rows', '25 rows', '50 rows', 'Show all' ]
										],
								buttons: [
								{
									
									   extend: 'print',									   
									   footer: true,
									   title:select_date,
									   customize: function ( win ) {
											$(win.document.body).find( 'table' )
												.addClass( 'compact' )
												.css( 'font-size', 'inherit' );
										},
								},
								
								{
									  extend: 'excel',	
								},
								 {
									extend:'pageLength',
									customize: function ( win ) {
											$(win.document.body).find( 'table' )
												.addClass( 'compact' )
												.css( 'font-size', 'inherit' );
										},
								     }
							  ], 
				                "aaData": data,								
								"aoColumns": [								
									{ "mDataProp": "sno" },
									{ "mDataProp": "mode_name" },									
									{ "mDataProp": function ( row, type, val, meta ){
										return parseFloat(row.payment_amount).toFixed(2);
									}},
									{ "mDataProp": function ( row, type, val, meta ){
										return parseFloat(row.sgst).toFixed(2);
									}},
									{ "mDataProp": function ( row, type, val, meta ){
										return parseFloat(row.cgst).toFixed(2);
									}},
									{ "mDataProp": function ( row, type, val, meta ){
										return parseFloat(parseFloat(row.cgst)+parseFloat(row.sgst)).toFixed(2);
									}},	
									{ "mDataProp": function ( row, type, val, meta ){
										return parseFloat(parseFloat(row.payment_amount)+parseFloat(row.sgst)+parseFloat(row.cgst)).toFixed(2);
									}},
									],				
		"footerCallback": function( row, data, start, end, display ) 
		{
			
			
			if(data.length>0){
			 var length=0;
			length=data.length;
			 var api = this.api(), data;
			 
			for( var i=0; i<=data.length-1;i++){
				
				
			 var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;   };
						
						
			 $( api.column(0).footer() ).html(length);		   
				
		
			
			//collection amount
				
				collection = api
                .column(2,{ page: 'current'})
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
			$( api.column(2).footer() ).html(parseFloat(collection).toFixed(2));
			
			
			// sgst amt
			sgst_amt = api
                .column(3,{ page: 'current'})
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
			$( api.column(3).footer() ).html(parseFloat(sgst_amt).toFixed(2));	

			// cgst amt
			cgst_amt = api
                .column(4,{ page: 'current'})
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
			$( api.column(4).footer() ).html(parseFloat(cgst_amt).toFixed(2));
			
			// cgst amt
			tot_gst = api
                .column(5,{ page: 'current'})
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
			$( api.column(5).footer() ).html(parseFloat(tot_gst).toFixed(2));
			
			// total
			total = api
                .column(6,{ page: 'current'})
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
			$( api.column(6).footer() ).html(parseFloat(total).toFixed(2));			
		 } 
	   }
	   else{
		   var data=0;
		   var api = this.api(), data;
		   $( api.column(0).footer() ).html("");
		   $( api.column(2).footer() ).html("");
		   $( api.column(3).footer() ).html("");
		   $( api.column(4).footer() ).html("");
		   $( api.column(5).footer() ).html("");
		   $( api.column(6).footer() ).html("");		   
	     }
		} 
		 
	  });
				 
	}else{
		
		oTable = $('#paymentmodewise_list').dataTable({
							
				                 "bDestroy": true, 
				                "responsive": true, 
				                "bInfo": false,
				                "bFilter": true,								
				                "scrollX":'100%',
								"bAutoWidth": false,
				                "bSort": true,
								 "dom": 'Bfrtip',
								 "lengthMenu":[
										 [ 10, 25, 50, -1 ],
										 [ '10 rows', '25 rows', '50 rows', 'Show all' ]
										],
								 "buttons": [
									{
									   extend: 'print',
									   footer: true,
									   title:select_date,
									   customize: function ( win ) {
											$(win.document.body).find( 'table' )
												.addClass( 'compact' )
												.css( 'font-size', 'inherit' );
										},
									 },
									 {
										extend: 'excel',	
									 },
									 {
									extend:'pageLength',
									customize: function ( win ) {
											$(win.document.body).find( 'table' )
												.addClass( 'compact' )
												.css( 'font-size', 'inherit' );
										},
								     }
									 ],  
				                "aaData": data,								
								"aoColumns": [								
									{ "mDataProp": "sno" },
									{ "mDataProp": "mode_name" },									
									{ "mDataProp": function ( row, type, val, meta ){
										return parseFloat(row.payment_amount).toFixed(2);
									}},
									{ "mDataProp": function ( row, type, val, meta ){
										return parseFloat(row.payment_amount).toFixed(2);
									}},
									],				
		"footerCallback": function( row, data, start, end, display ) 
		{
			
			
			if(data.length>0){
			 var length=0;
			length=data.length;
			 var api = this.api(), data;
			 
			for( var i=0; i<=data.length-1;i++){
				
				
			 var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;   };
						
						
			 $( api.column(0).footer() ).html(length);
			
			//collection amount
				
				collection = api
                .column(2,{ page: 'current'})
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
		$( api.column(2).footer() ).html(parseFloat(collection).toFixed(2));			
			
			// total
			total = api
                .column(3,{ page: 'current'})
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
			$( api.column(3).footer() ).html(parseFloat(total).toFixed(2));			
		 } 
	   }
	   else{
		   var data=0;
		   var api = this.api(), data;
		   $( api.column(0).footer() ).html("");
		   $( api.column(2).footer() ).html("");
		   $( api.column(3).footer() ).html("");
		   		   
	     }
		} 
		 
	  })
		
	}
	  
	 }	
	  $("div.overlay").css("display", "none");
   },
		  error:function(error)  
		  {
			 $("div.overlay").css("display", "none"); 
		  }	 
	  });
}


// payment_datewise_schemedata

function generate_paymodewise_schemelist(selected_date ="",id_branch ="",id_employee="",added_by="")
{
	
		my_Date = new Date();
			var date_type=$('#date_Select').find(":selected").val();
		 $("div.overlay").css("display", "block");
			$.ajax({
			  url:base_url+"index.php/reports/payment_datewise_schemelist",
			  data: {'date':selected_date,'id_branch':id_branch,'id_employee':id_employee,'date_type':date_type,'added_by':added_by},  
			  dataType:"JSON",
			  type:"POST",
			 success:function(payment){
			var data=payment.account;
		
			var gst_number=payment.gst_number;
			
			var gstsetting= (typeof payment.account == 'undefined' ? '' :payment.account[0].gst_setting);
			
			if(gstsetting==1)
				{
				var gstno="<span style='font-size:13pt; float:right;'> GST Number - "+gst_number+"</span>";
				}else{
					var gstno='';
				}
				 var select_date="<b><span style='font-size:15pt;'>All Scheme Report As on Date   </span></b></br>"+"<span style=font-size:13pt;>Transaction Details &nbsp;&nbsp;Selected Date&nbsp;&nbsp;:&nbsp;"+selected_date+"</span>"+gstno;
				 
						var oTable = $('#schdatewise_report').DataTable();
						oTable.clear().draw();
						 if (data!= null && data.length > 0)
						  {
							  
							if(gstsetting ==1)
							{  
							  
							oTable = $('#schdatewise_report').dataTable({
									
										"bDestroy": true,
										"bInfo": false,
										"bFilter": false,								
										"scrollX":'100%',
										"bAutoWidth": false,
										"bSort": true,
										"dom": 'Bfrtip',
										"lengthMenu":[
										 [ 10, 25, 50, -1 ],
										 [ '10 rows', '25 rows', '50 rows', 'Show all' ]
										],
										"buttons": [
										{
										   extend: 'print',
										   footer: true,
										   title:select_date,
										   customize: function ( win ) {
												$(win.document.body).find( 'table' )
													.addClass( 'compact' )
													.css( 'font-size', 'inherit' );
											},
										 },
										 {
											extend:'excel' 
										 },
										 {
											extend:'pageLength',
											customize: function ( win ) {
											$(win.document.body).find( 'table' )
												.addClass( 'compact' )
												.css( 'font-size', 'inherit' );
													},
										 }
										 
										 
										 
										 
										 
										 /* ,{
										   extend: 'excelHtml5',
										   footer: true,
										 } */],  			
										"aaData": data,								
										"aoColumns": [								
											{ "mDataProp": "date_payment" },	
											{ "mDataProp": "code" },
											{ "mDataProp": "branch_name" },
											{ "mDataProp": "receipt" },
											{ "mDataProp": function ( row, type, val, meta ){
													 return parseFloat(row.payment_amount).toFixed(2);
												}},
												{ "mDataProp": function ( row, type, val, meta ){
													return parseFloat(row.sgst).toFixed(3);
												}},
												{ "mDataProp": function ( row, type, val, meta ){
													return parseFloat(row.cgst).toFixed(3);
												}},
												{ "mDataProp": function ( row, type, val, meta ){
													return parseFloat(parseFloat(row.cgst)+parseFloat(row.sgst)).toFixed(2);
												}},	
												{ "mDataProp": function ( row, type, val, meta ){
													return parseFloat(parseFloat(row.payment_amount)+parseFloat(row.sgst)+parseFloat(row.cgst)).toFixed(2);
												}},
											],				
				"footerCallback": function( row, data, start, end, display ) 
				{
					 var cshtotal=0;
					 var cardtotal=0;
					 var chqtotal=0;
					 var ecstotal=0;
					 var nbtotal=0;
					 var fptotal=0;
					 var upitotal=0;		 
						 if(data.length>0){
								 var api = this.api(), data;
								 
								for( var i=0; i<=data.length-1;i++){
									
									if(data[i]['payment_mode'] =='CSH'){
										cshtotal +=  parseFloat(data[i]['payment_amount']);}
									 if(data[i]['payment_mode'] =='Card'){
										cardtotal +=  parseFloat(data[i]['payment_amount']);}
									if(data[i]['payment_mode'] =='CHQ'){
										chqtotal +=  parseFloat(data[i]['payment_amount']);}
									if(data[i]['payment_mode'] =='ECS'){
										ecstotal +=  parseFloat(data[i]['payment_amount']);}
									 if(data[i]['payment_mode'] =='NB'){
										nbtotal +=  parseFloat(data[i]['payment_amount']);}
									if(data[i]['payment_mode'] =='FP'){					
										fptotal +=  parseFloat(data[i]['payment_amount']);}	
									if(data[i]['payment_mode'] =='UPI'){					
										upitotal +=  parseFloat(data[i]['payment_amount']);}	

								//console.log(data[i]['payment_mode']);			
							// total
							
								var intVal = function ( i ) {
									   return typeof i === 'string' ?
										   i.replace(/[\$,]/g, '')*1 :
										   typeof i === 'number' ?
											   i : 0;
								   };	
							//Total over this page
							
							$(api.column(0).footer() ).html('Total');	
							
							// recepit Total over this page
							rec_tot = api
								.column(2)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
							$( api.column(2).footer() ).html(rec_tot);			
								
								
							// pay_amt tot	
								
								pay_amt = api
								.column(3)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								
								$(api.column(3).footer()).html(parseFloat(pay_amt).toFixed(2));
								
								// sgst_amt tot	
								
								sgst_amt = api
								.column(4)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								
							$(api.column(4).footer()).html(parseFloat(sgst_amt).toFixed(3));	
								
							// cgst_amt tot	
								
								cgst_amt = api
								.column(5)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
							$(api.column(5).footer()).html(parseFloat(cgst_amt).toFixed(3));
							
							
								// tgst_amt tot	
							
							$(api.column(6).footer()).html(parseFloat(parseFloat(sgst_amt)+parseFloat(cgst_amt)).toFixed(2));
							
							
							total = api
								.column(7)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
							$(api.column(7).footer()).html(parseFloat(total).toFixed(2));	
								
			 $('tr:eq(1) td:eq(2)', api.table().footer()).html('Description');
			var a=2;
			var tot_amt2 = 0;
			$.each(payment.mode_wise, function (i, item) {
                $('tr:eq('+a+') td:eq(1)', api.table().footer()).html(item.payment_mode);						
                $('tr:eq('+a+') td:eq(3)', api.table().footer()).html(parseFloat(item.received_amt).toFixed(2));
                tot_amt2 = parseFloat(tot_amt2) + parseFloat(item.received_amt);
                console.log(tot_amt2)
                a++;
			})
			
			/*//cash
			$('tr:eq(2) td:eq(1)', api.table().footer()).html('Cash  ');						
			$('tr:eq(2) td:eq(3)', api.table().footer()).html(parseFloat(cshtotal).toFixed(2));	
			
			//dc and cc card
			$('tr:eq(3) td:eq(1)', api.table().footer()).html('Card');	
			$('tr:eq(3) td:eq(3)', api.table().footer()).html(parseFloat(cardtotal).toFixed(2));
			
			//Ecs
			$('tr:eq(4) td:eq(1)', api.table().footer()).html('Ecs');	
			$('tr:eq(4) td:eq(3)', api.table().footer()).html(parseFloat(ecstotal).toFixed(2));
			
			//net baking
			$('tr:eq(5) td:eq(1)', api.table().footer()).html('Net Banking  ');	
			$('tr:eq(5) td:eq(3)', api.table().footer()).html(parseFloat(nbtotal).toFixed(2));
			
			//fb
			$('tr:eq(6) td:eq(1)', api.table().footer()).html('Free payment ');
			$('tr:eq(6) td:eq(3)', api.table().footer()).html(parseFloat(fptotal).toFixed(2));
			
			//Chq
			$('tr:eq(7) td:eq(1)', api.table().footer()).html('Chq ');
			$('tr:eq(7) td:eq(3)', api.table().footer()).html(parseFloat(chqtotal).toFixed(2));
			
			//UPI
			$('tr:eq(8) td:eq(1)', api.table().footer()).html('UPI');
			$('tr:eq(8) td:eq(3)', api.table().footer()).html(parseFloat(upitotal).toFixed(2));*/
			
			//total
			$('tr:eq('+a+') td:eq(1)', api.table().footer()).html('Total');	

			
			$('tr:eq('+a+') td:eq(3)', api.table().footer()).html(parseFloat(tot_amt2).toFixed(2));
					} 
				}else{
					 var api = this.api(), data;
					 $(api.column(0).footer()).html('');
					 $(api.column(2).footer()).html('');
					 $(api.column(3).footer()).html('');
					 $(api.column(4).footer()).html('');
					 $(api.column(5).footer()).html('');
					 $(api.column(6).footer()).html('');
					 $(api.column(7).footer()).html('');
					 $(api.column(8).footer()).html('');
					 $('tr:eq(2) td:eq(3)', api.table().footer()).html('');
					 $('tr:eq(3) td:eq(3)', api.table().footer()).html('');
					 $('tr:eq(4) td:eq(3)', api.table().footer()).html('');
					 $('tr:eq(5) td:eq(3)', api.table().footer()).html('');
					 $('tr:eq(6) td:eq(3)', api.table().footer()).html('');
					 $('tr:eq(7) td:eq(3)', api.table().footer()).html('');
					 $('tr:eq(8) td:eq(3)', api.table().footer()).html('');
					 $('tr:eq(9) td:eq(3)', api.table().footer()).html('');
					 //Text CLEAR
					 $('tr:eq(1) td:eq(2)', api.table().footer()).html('');
					 $('tr:eq(2) td:eq(1)', api.table().footer()).html('');
					 $('tr:eq(3) td:eq(1)', api.table().footer()).html('');
					 $('tr:eq(4) td:eq(1)', api.table().footer()).html('');
					 $('tr:eq(5) td:eq(1)', api.table().footer()).html('');
					 $('tr:eq(6) td:eq(1)', api.table().footer()).html('');
					 $('tr:eq(7) td:eq(1)', api.table().footer()).html('');
					 $('tr:eq(8) td:eq(1)', api.table().footer()).html('');
					 $('tr:eq(9) td:eq(1)', api.table().footer()).html('');
								 
				     }
				}
			  });
		}
	  else{
		  
					oTable = $('#schdatewise_report').dataTable({
									
										"bDestroy": true,
										"bInfo": false,
										"bFilter": false,						
										"scrollX":'100%',
										"bAutoWidth": false,
										"bSort": true,
										"dom": 'Bfrtip',
										"lengthMenu":[
										 [ 10, 25, 50, -1 ],
										 [ '10 rows', '25 rows', '50 rows', 'Show all' ]
										],
										"buttons": [
										{
										   extend: 'print',
										   footer: true,
										   title:select_date,
										   customize: function ( win ) {
												$(win.document.body).find( 'table' )
													.addClass( 'compact' )
													.css( 'font-size', 'inherit' );
											},
										 },
										  {
											extend:'excel' 
										 },
										 {
											extend:'pageLength',
											customize: function ( win ) {
											$(win.document.body).find( 'table' )
												.addClass( 'compact' )
												.css( 'font-size', 'inherit' );
													},
										 }
										
										],  			
										"aaData": data,								
										"aoColumns": [								
											{ "mDataProp": "payment_mode" },
											{ "mDataProp": "code" },
											{ "mDataProp": "branch_name" },
											{ "mDataProp": "receipt" },
											{ "mDataProp": function ( row, type, val, meta ){
													 return parseFloat(row.payment_amount).toFixed(2);
											}},
												{ "mDataProp": function ( row, type, val, meta ){
													return parseFloat(row.payment_amount).toFixed(2);
												}},
											],				
				"footerCallback": function( row, data, start, end, display ) 
				{
					 var cshtotal=0;
					 var cardtotal=0;
					 var chqtotal=0;
					 var ecstotal=0;
					 var nbtotal=0;
					 var fptotal=0;
					 var upitotal=0;		 
						 if(data.length>0){
								 var api = this.api(), data;
								 
								for( var i=0; i<=data.length-1;i++){
									
									if(data[i]['payment_mode'] =='CSH'){
										cshtotal +=  parseFloat(data[i]['payment_amount']);}
									 if(data[i]['payment_mode'] =='Card'){
										cardtotal +=  parseFloat(data[i]['payment_amount']);}
									if(data[i]['payment_mode'] =='CHQ'){
										chqtotal +=  parseFloat(data[i]['payment_amount']);}
									if(data[i]['payment_mode'] =='ECS'){
										ecstotal +=  parseFloat(data[i]['payment_amount']);}
									 if(data[i]['payment_mode'] =='NB'){
										nbtotal +=  parseFloat(data[i]['payment_amount']);}
									if(data[i]['payment_mode'] =='FP'){					
										fptotal +=  parseFloat(data[i]['payment_amount']);}	
									if(data[i]['payment_mode'] =='UPI'){					
										upitotal +=  parseFloat(data[i]['payment_amount']);}

								//console.log(data[i]['payment_mode']);			
							// total
							
								var intVal = function ( i ) {
									   return typeof i === 'string' ?
										   i.replace(/[\$,]/g, '')*1 :
										   typeof i === 'number' ?
											   i : 0;
								   };	
							//Total over this page
							
							$(api.column(0).footer() ).html('Total');	
							
							// recepit Total over this page
							rec_tot = api
								.column(2)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
							$( api.column(2).footer() ).html(rec_tot);			
								
								
							// pay_amt tot	
								
								pay_amt = api
								.column(4)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								
								$(api.column(4).footer()).html(parseFloat(pay_amt).toFixed(2));
							
							total = api
								.column(5)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
							$(api.column(5).footer()).html(parseFloat(total).toFixed(2));	
								
			 $('tr:eq(1) td:eq(2)', api.table().footer()).html('Description');
			var b=2;
			var tot_amt1 = 0;
			$.each(payment.mode_wise, function (i, item) {
			    $('tr:eq('+b+') td:eq(1)', api.table().footer()).html(item.payment_mode);						
			    $('tr:eq('+b+') td:eq(3)', api.table().footer()).html(parseFloat((item.received_amt!=null?item.received_amt:0)).toFixed(2));
                tot_amt1 = parseFloat(tot_amt1) + parseFloat((item.received_amt!=null?item.received_amt:0));
                console.log(tot_amt1)
                b++;
			}) 
							
			/*//cash
			$('tr:eq(2) td:eq(1)', api.table().footer()).html('Cash  ');						
			$('tr:eq(2) td:eq(3)', api.table().footer()).html(parseFloat(cshtotal).toFixed(2));	
			
			//dc and cc card
			$('tr:eq(3) td:eq(1)', api.table().footer()).html('Card');	
			$('tr:eq(3) td:eq(3)', api.table().footer()).html(parseFloat(cardtotal).toFixed(2));
			
			//Ecs
			$('tr:eq(4) td:eq(1)', api.table().footer()).html('Ecs');	
			$('tr:eq(4) td:eq(3)', api.table().footer()).html(parseFloat(ecstotal).toFixed(2));
			
			//net baking
			$('tr:eq(5) td:eq(1)', api.table().footer()).html('Net Banking  ');	
			$('tr:eq(5) td:eq(3)', api.table().footer()).html(parseFloat(nbtotal).toFixed(2));
			
			//fb
			$('tr:eq(6) td:eq(1)', api.table().footer()).html('Free payment ');
			$('tr:eq(6) td:eq(3)', api.table().footer()).html(parseFloat(fptotal).toFixed(2));
			
			//Chq
			$('tr:eq(7) td:eq(1)', api.table().footer()).html('Chq ');
			$('tr:eq(7) td:eq(3)', api.table().footer()).html(parseFloat(chqtotal).toFixed(2));
			
			//UPI
			$('tr:eq(8) td:eq(1)', api.table().footer()).html('UPI');
			$('tr:eq(8) td:eq(3)', api.table().footer()).html(parseFloat(upitotal).toFixed(2));*/
			
			//total
			$('tr:eq('+b+') td:eq(1)', api.table().footer()).html('Total');	

			
			$('tr:eq('+b+') td:eq(3)', api.table().footer()).html(parseFloat(tot_amt1).toFixed(2));
					} 
				}else{
					 var api = this.api(), data;
					 $(api.column(0).footer()).html('');
					 $(api.column(2).footer()).html('');
					 $(api.column(4).footer()).html('');
					 $(api.column(5).footer()).html('');				 
					 $('tr:eq(2) td:eq(3)', api.table().footer()).html('');
					 $('tr:eq(3) td:eq(3)', api.table().footer()).html('');
					 $('tr:eq(4) td:eq(3)', api.table().footer()).html('');
					 $('tr:eq(5) td:eq(3)', api.table().footer()).html('');
					 $('tr:eq(6) td:eq(3)', api.table().footer()).html('');
					 $('tr:eq(7) td:eq(3)', api.table().footer()).html('');
					 $('tr:eq(8) td:eq(3)', api.table().footer()).html('');
					 $('tr:eq(9) td:eq(3)', api.table().footer()).html('');
					 //Text CLEAR
					 $('tr:eq(1) td:eq(2)', api.table().footer()).html('');
					 $('tr:eq(2) td:eq(1)', api.table().footer()).html('');
					 $('tr:eq(3) td:eq(1)', api.table().footer()).html('');
					 $('tr:eq(4) td:eq(1)', api.table().footer()).html('');
					 $('tr:eq(5) td:eq(1)', api.table().footer()).html('');
					 $('tr:eq(6) td:eq(1)', api.table().footer()).html('');
					 $('tr:eq(7) td:eq(1)', api.table().footer()).html('');
					 $('tr:eq(8) td:eq(1)', api.table().footer()).html('');
					 $('tr:eq(9) td:eq(1)', api.table().footer()).html('');			 
				     }
				}
			  });
		  
		  
		  
	  }
			  
			  
			  
			}
				$("div.overlay").css("display", "none");
				},
			  error:function(error)  
			  {
				 $("div.overlay").css("display", "none"); 
			  }	 
	  });
	
}


// payment_datewise_schemedata


function generate_paydatewise_schcoll(selected_date ="",id_branch ="")
{
		my_Date = new Date();
		 $("div.overlay").css("display", "block");
			$.ajax({
			  url:base_url+"index.php/reports/paydatewise_schcoll_list",
			  data: {'date':selected_date,'id_branch':id_branch},  
			  dataType:"JSON",
			  type:"POST",
			 success:function(payment){
				 
				 var data=payment.account;
				 var gst_number=payment.gst_number;
						
						
						var gstsetting = (typeof payment.account == 'undefined' ? '' :payment.account[0].gst_setting);	
			
						if(gstsetting==1)
							{
							var gstno="<span style='font-size:13pt; float:right;'> GST Number - "+gst_number+"</span>";
							}else{
								var gstno='';
							}
						
						var select_date="<b><span style='font-size:15pt;'>Payment Scheme Wise Report</span></b></br>"+"<span style=font-size:13pt;>Transaction Details &nbsp;&nbsp;Selected Date&nbsp;&nbsp;:&nbsp;"+selected_date+"</span>"+gstno;
						
						
						var oTable = $('#payschcoll_data').DataTable();
						oTable.clear().draw();
						 if (data!= null && data.length > 0)
						  {	  console.log(data);
							oTable = $('#payschcoll_data').dataTable({
									
										"bDestroy": true,
										"bInfo": false,
										"bFilter": true,								
										"scrollX":'100%',
										"bAutoWidth": false,
										"bSort": true,
										 "dom": 'Bfrtip',										 
										 "lengthMenu":[
										 [ 10, 25, 50, -1 ],
										 [ '10 rows', '25 rows', '50 rows', 'Show all' ]
										 ],
										 "buttons": [
											{
											   extend: 'print',
											   footer: true,
											   title:select_date,
											   customize: function ( win ) {
													$(win.document.body).find( 'table' )
														.addClass( 'compact' )
														.css( 'font-size', 'inherit' );
												},
											 },
											 {
														extend:'excel',
														footer: true,
													    title: select_date,
											 },
											 {
												extend:'pageLength',
												customize: function ( win ) {
												$(win.document.body).find( 'table' )
												.addClass( 'compact' )
												.css( 'font-size', 'inherit' );
										            },
											  }
											 ], 	
										"aaData": data,	
										"aoColumns": [								
										//	{ "mDataProp": "scheme_name" },	
										{ "mDataProp": function ( row, type, val, meta ){
					                	if(row.has_lucky_draw==1 && row.is_lucky_draw==1){
					                	return row.scheme_name+' - '+row.group_code;
					                	}
					                	else{
					                		return row.scheme_name;
					                	}
					                }},
											{ "mDataProp": "branch_name" },
											{ "mDataProp": "opening_bal" },
											{ "mDataProp": "collection" },
											{ "mDataProp": "incentive" },
											{ "mDataProp": "paid" },
											{ "mDataProp": "cancel_payment" },		
											{ "mDataProp": "charge" },		
											{ "mDataProp": "closing_balance" },
											{ "mDataProp": function ( row, type, val, meta ){
													return parseFloat(parseFloat(row.opening_bal)+parseFloat(row.collection)).toFixed(2);
												}},
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
									   i : 0;  };	
					// opentotal amt
					opentotal = api
						.column(2,{ page: 'current'})
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						}, 0 );
					$( api.column(2).footer() ).html(parseFloat(opentotal).toFixed(2));
					
				// collection amt 
				colltotal = api
					.column(3,{ page: 'current'})
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
				$( api.column(3).footer() ).html(parseFloat(colltotal).toFixed(2));
				
			// collection gst 
            incen = api
                .column(4,{ page: 'current'})
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            $( api.column(4).footer() ).html(parseFloat(incen));
			
			// paid Total 
            paid = api
                .column(5,{ page: 'current'})
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            $( api.column(5).footer() ).html(parseFloat(paid));
			
			// cancel 
			
            cancel = api
                .column(6,{ page: 'current'})
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            $( api.column(6).footer() ).html(parseFloat(cancel));
			
		// charge
			charge = api
                .column(7,{ page: 'current'})
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
					$( api.column(7).footer() ).html(parseFloat(charge).toFixed(2));
	
	// close_bal 	
			close_bal = api
                .column(8,{ page: 'current'})
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
					$( api.column(8).footer() ).html(parseFloat(close_bal).toFixed(2));
					
	//  Total 
			Total = api
                .column(9)
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
					$( api.column(9).footer() ).html(parseFloat(Total).toFixed(2));
	
			}
		}
			else{
					var data=0;
					 var api = this.api(), data;
					 //$( api.column(1).footer() ).html('');
					 $( api.column(3).footer() ).html('');
					 $( api.column(4).footer() ).html('');
					 $( api.column(5).footer() ).html('');
					 $( api.column(6).footer() ).html('');
					 $( api.column(7).footer() ).html('');
					 $( api.column(8).footer() ).html('');
					 $( api.column(9).footer() ).html('');
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

// payment outstanding 

function generate_payout_cuslist(selected_date="",id_branch="")
{
		my_Date = new Date();
		 $("div.overlay").css("display", "block");
			$.ajax({
			  url:base_url+"index.php/reports/payment_outstanding_list",
			  data: {'date':selected_date,'id_branch':id_branch},  
			  dataType:"JSON",
			  type:"POST",
			 success:function(payment){
				 
				 console.log(payment);
				 var data=payment.account;
				 
				 var gst_number=payment.gst_number;

				var gstsetting = (typeof payment.account == 'undefined' ? '' :payment.account[0].gst_setting);	
				 
				if(gstsetting==1)
				{
				var gstno="<span style='font-size:13pt; float:right;'> GST Number - "+gst_number+"</span>";
				}else{
					var gstno='';
				}
				 
				 
						var select_date="<b><span style='font-size:15pt;'>Out Standing Report</span></b></br>"+"<span style=font-size:13pt;>Transaction Details &nbsp;&nbsp;As On Date&nbsp;&nbsp;:&nbsp;"+selected_date+ "<span>"+gstno;
						
						var oTable = $('#payout_list').DataTable();
						oTable.clear().draw();
						 if (data!= null && data.length > 0)
						  {	  console.log(data);
							oTable = $('#payout_list').dataTable({
									
										"bDestroy": true,
										"bInfo": false,
										"bFilter": true,								
										"scrollX":'100%',
										"bAutoWidth": false,
										"bSort": true,
										 "dom": 'Bfrtip',
										 "lengthMenu":[
										  [ 10, 25, 50, -1 ],
										  [ '10 rows', '25 rows', '50 rows', 'Show all' ]
										  ],
										 
										 "buttons": [
											{
											   extend: 'print',
											   footer: true,
											   title:select_date,
											   customize: function ( win ) {
													$(win.document.body).find( 'table' )
														.addClass( 'compact' )
														.css( 'font-size', 'inherit' );
												},
											 },
											 { extend:'excel', footer: true, title: select_date, },
											 {
												extend:'pageLength',
												customize: function ( win ) {
												$(win.document.body).find( 'table' )
												.addClass( 'compact' )
												.css( 'font-size', 'inherit' );
													},
											 }											 
											 ], 	
										"aaData": data,	
										"aoColumns": [								
											{ "mDataProp": "sno" },	
											{ "mDataProp": "code" },
										//	{ "mDataProp": "scheme_acc_number" },
										{ "mDataProp": function ( row, type, val, meta ){
					                	if(row.has_lucky_draw==1 && row.is_lucky_draw==1){
					                	return row.group_code+' '+row.scheme_acc_number;
					                	}
					                	else{
					                		return row.code+' '+row.scheme_acc_number;
					                	}
					                }},
											{ "mDataProp": "name" },
											{ "mDataProp": "total_installments" },
											{ "mDataProp": "paid_installments" },
											{ "mDataProp": "amount" },		
											{ "mDataProp": "joined_date" },		
											{ "mDataProp": "total_paid_amount" },		
											{ "mDataProp": "total_paid_weight" },		
											{ "mDataProp": "due_count" },
											{ "mDataProp": "mobile" },
											{ "mDataProp": "last_paid_date" },
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




function get_schemename()
{
	
	$(".overlay").css('display','block');
	$.ajax({
		type: 'GET',
		url: base_url+'index.php/get/schemename_list',
		dataType:'json',
		success:function(data){
		
		 var scheme_val =  $('#id_schemes').val();
		   $.each(data, function (key, item) {
					  	
			  
			   		$('#scheme_select').append(
						$("<option></option>")
						.attr("value", item.id_scheme)						  
						  .text(item.scheme_name )
						  
					);			   				
				
			});
			
			$("#scheme_select").select2({
			    placeholder: "Select Scheme name",
			    allowClear: true
			});
				
			 $("#scheme_select").select2("val",(scheme_val!='' && scheme_val>0?scheme_val:''));
			 $(".overlay").css("display", "none");	
		}
	});
}

//end of new reports

 
 //branch on change event
 
 
 $('#branch_select').select2().on("change", function(e) {
         
		 //console.log(ctrl_page[1]);
			 switch(ctrl_page[1])

			{

				case 'payment_daterange':
				
					
					if(this.value!='')
					{  
						
						$('#emp_select').empty();
						$('#id_employee').val('');
						$('#id_branch').val(this.value);
						var from_date =$('#rpt_payments1').text();
						var to_date  = $('#rpt_payments2').text();
						var id_scheme = $('#scheme_select').val();
						var id_employee = $('#id_employee').val();
						var id_branch=$(this).val();						
						generate_payment_daterange(from_date,to_date,'','',id_scheme,id_branch,id_employee);
						 get_employee_name(id_branch);
					}
					 break;	
					 
					 case 'interwalTrans_list':

						if(this.value!='')
						{
							
							var id_branch=$(this).val();
							
							var from_date =$('#rpt_payments1').text();
							var to_date  = $('#rpt_payments2').text();
							get_interWalTrans_list(from_date,to_date,id_branch);

						}
						
				break;
				
			
				
				case 'payment_employee_wise':  //hh
				      
				   if(this.value!='')
				        {
							$('#emp_select').empty();
				            var id_branch=$(this).val();
				            var from_date =$('#rpt_payments1').text();
					     	var to_date  = $('#rpt_payments2').text();
				            $('#id_branch').val(this.value);
				            var id_employee=$('#id_employee').val();
				            get_employee_name(id_branch);
				            get_paymentlist(from_date,to_date,id_branch,id_employee); 
				        }
				      
						
				break;
				
				case 'employee_wise_collection':  //hh
				      
				   if(this.value!='')
				        {
						
				            var id_branch=$(this).val();
				            var from_date =$('#rpt_payments1').text();
					     	var to_date  = $('#rpt_payments2').text();
				            var id_emp=$('#emp_select').val();
				            get_emp_summary_list(from_date,to_date,id_branch,id_emp); 
				        }
				      
						
				break;
				
				
				case 'Employee_account':
				      
				   if(this.value!='')
				        {
							var from_date =$('#account_list1').text();
							var to_date  = $('#account_list2').text();
							$('#emp_select').empty();
				            var id_branch=$(this).val();
				            $('#id_branch').val(this.value);
				            var id_employee=$('#id_employee').val();
				            get_employee_name(id_branch);
				            get_employee_acc_list(from_date,to_date,id_branch,id_employee);
				        }
				        
				        break;
					 
					 case 'employee_ref_success':
				
					
					if(this.value!='')
					{  
						
						var from_date =$('#rpt_payments1').text();
						var to_date  = $('#rpt_payments2').text();
						var id_branch=$(this).val();						
						payment_employee_ref_success(from_date,to_date,id_branch);
					}
					 break;	
					 
				case 'payment_modewise_data':	

					if(this.value!='')
					{	 
						var from_date = $('#rpt_payments1').text();
						var to_date = $('#rpt_payments2').text();
						var id_scheme = $('#scheme_select').val();					
						var id_branch   = $(this).val();						
						generate_paymodewise_list(from_date,to_date,'','',id_scheme,id_branch);
					}

					break;
					
					
					case 'accounts_schemewise':
				
						if(this.value!='')
						{  
						
						var id_branch=$(this).val();
							scheme_wise_account(id_branch);
						}
					 break;

					case 'payment_details':
				
						if(this.value!='')
						{  
						
						var id_branch=$(this).val();
						var from_date=$('#rpt_customer_unpaid1').text();
						var to_date=$('#rpt_customer_unpaid2').text();
					    customer_wise_payment(id_branch);
						}
						
					 break;
					 
					 case 'payment_schemewise':
				
						if(this.value!='')
						{  
						
						var id_branch=$(this).val();
							var from_date=$('#rpt_scheme_payment1').text();
							var to_date=$('#rpt_scheme_payment2').text();	
							payment_schemewise(from_date,to_date,id_branch);
						}
					 break;	

					case 'payment_datewise_schemedata':	

					if(this.value!='')
					{	 
						 $('#emp_select').empty();
					    $('#id_employee').val('');
					     $('#id_branch').val(this.value);
						var selected_date = $("#schreport_date").val();
						var id_branch   = $(this).val();	
						var id_employee= $('#id_employee').val();
						get_employee_name(id_branch);
						var added_by=$('#added_by').val();
						generate_paymodewise_schemelist(selected_date,id_branch,id_employee,added_by);
					}

					break;
					
					case 'payment_online_offline_collec_data':	

					if(this.value!='')
					{	 
						
						var selected_date = $("#modereport_date").val();
					  var added_by=$('#added_by').val();
						generate_online_offline_collection(selected_date,added_by);
					}

					break;
					
					
					
					case 'paydatewise_schcoll_data':	

					if(this.value!='')
					{	 
						var selected_date = $("#schwisereport_date").val();
						var id_branch   = $(this).val();
						generate_paydatewise_schcoll(selected_date,id_branch);
					}

					break;		
				case 'customer_account_details':
					        var from_date=$('#rpt_payments1').text();
                            var to_date=$('#rpt_payments2').text();
                            get_customer_account_details(from_date,to_date);
			        break;
			    case 'payment_cancel_report':
			    	if(this.value!='')
				    {
    				    var id_branch=$(this).val();
    			        var from_date=$('#cancel_payment_list1').text();
                        var to_date=$('#cancel_payment_list2').text();
                        get_cancel_pay_list(from_date,to_date,id_branch);
				    }
			    break;
			    
			    case 'payment':
			    	if(this.value!='')
				    {
    				    var id_branch=$(this).val();
    			        var from_date=$('#rpt_payment_date1').text();
                        var to_date=$('#rpt_payment_date2').text();
                        generate_payment_list(from_date,to_date);
				    }
			    break;
			    
			    case 'payment_outstanding':
			    	if(this.value!='')
				    {
    				    var id_branch=$(this).val();
    			        var select_date=$('#payoutcus').val();
                        generate_payout_cuslist(select_date,id_branch);
				    }
			    break;
			    
			    case 'gift_report':
			    	if(this.value!='')
				    {
    				    var id_branch=$(this).val();
    			        var from_date=$('#rpt_payment_date1').text();
                        var to_date=$('#rpt_payment_date2').text();
                        getGiftIssuedList(from_date,to_date,id_branch);
				    }
			    break;
			    
			    
			
			} 
		  
   });

function get_employee_name(id_branch='')
{
	//$("#spinner").css('display','none');
	//$(".overlay").css('display','block');
	$.ajax({
		type: 'POST',
		data :{'id_branch':id_branch},
		url: base_url+'index.php/reports/employee_list',
		dataType:'json',
		success:function(data){	
		console.log(data);
		 $("#spinner").css('display','none');
		   $.each(data.employee, function (key, item) {	  	
			   		$('#emp_select').append(
						$("<option></option>")
						.attr("value", item.id_employee)						  
						  .text(item.employee_name )
					);
			});
			$("#emp_select").select2({
			    placeholder: "Select Employee Name ",
			    allowClear: true,
		    });
			 if($("#emp_select").length)
			 {
			     $("#emp_select").select2("val", ($('#id_employee').val()!=null?$('#id_employee').val():''));
			 }
			 
			 $(".overlay").css("display", "none");	
		}

	});
}

$('#emp_select').select2().on("change", function(e){					
		if(this.value!='')
		{	 
         
		var id_emp=this.value;
		 var from_date =$('#rpt_payments1').text();
        var to_date  = $('#rpt_payments2').text();
		var id_branch=$('#id_branch').val();
		 $('#id_employee').val(this.value);
		 if(ctrl_page[1] == 'payment_employee_wise')
		 {
		     	get_paymentlist(from_date,to_date,id_branch,id_emp);  //hh
		 }
		 else if(ctrl_page[1] =='payment_daterange')
		 {
		                var from_date =$('#rpt_payments1').text();
						var to_date  = $('#rpt_payments2').text();
						var id_scheme = $('#scheme_select').val();
						var id_branch=$('#id_branch').val();
					    var id_employee=$('#id_employee').val();
						generate_payment_daterange(from_date,to_date,'','',id_scheme,id_branch,id_employee);
		 }
		 else if(ctrl_page[1] =='Employee_account')
		 {
		                var from_date =$('#account_list1').text();
						var to_date  = $('#account_list2').text();
						var id_scheme = $('#scheme_select').val();
						var id_branch=$('#id_branch').val();
					    var id_employee=$('#id_employee').val();
						$('#id_employee').val(this.value);
						get_employee_acc_list(from_date,to_date,id_branch,id_employee);
		 }
		 
		   else if(ctrl_page[1] =='payment_datewise_schemedata')
           {
                        var selected_date = $("#schreport_date").val();
						var id_branch   = $('#id_branch').val();
						var id_employee=$('#id_employee').val();
						var added_by=$('#added_by').val();
						generate_paymodewise_schemelist(selected_date,id_branch,id_employee,added_by);
           }
           
            else if(ctrl_page[1] =='payment_online_offline_collec_data')
           {
                        var selected_date = $("#modereport_date").val();
						var added_by=$('#added_by').val();
						generate_online_offline_collection(selected_date,added_by);
           }
           else if(ctrl_page[1] =='employee_wise_collection')
           {
				            var id_emp=$(this).val();
				            var from_date =$('#rpt_payments1').text();
					     	var to_date  = $('#rpt_payments2').text();
				            var id_branch=$('#branch_select').val();
				            get_emp_summary_list(from_date,to_date,id_branch,id_emp); 
           }
		
		}
		
		
});

function get_paymentlist(from_date,to_date,id_branch,id_emp)
{

	my_Date = new Date();
	var date_type=$('#date_Select').find(":selected").val();
// 	var id_emp=$('#emp_select').find(":selected").val();
// 	var id_branch=$('#branch_select').find(":selected").val();
		 $("div.overlay").css("display", "block");
			$.ajax({
		data: (from_date !='' && to_date !=''? {'from_date':from_date,'to_date':to_date,'id_branch':id_branch,'id_emp':id_emp,'date_type':date_type}: ''), //hh

			url: base_url+'index.php/reports/payment_employee_collection',
			
			  dataType:"JSON",
			  type:"POST",
			 success:function(data){
				get_payment(data);	
				 $("div.overlay").css("display", "none"); 
		 	 	},
		 	 	error:function(error)  

					  {

						 $("div.overlay").css("display", "none"); 

					  }	
	  });
}
 function get_payment(data){
 
 	var payment=data;
 	from=$("#rpt_payments1").text();
 	to=$("#rpt_payments2").text();
 	var filename="<b><span style='font-size:15pt;'> Sri Krishna Nagai Maligai | Admin</span></b></br>"+"<span style=font-size:13pt;>&nbsp;Selected Date&nbsp;:&nbsp;"+from+"</span><span style=font-size:13pt;>&nbsp;-&nbsp;"+to+"</span>";
 	var oTable = $('#emp_list').DataTable();

	     oTable.clear().draw();

			  	 if (payment!= null)

			  	  {  	
					  	oTable = $('#emp_list').dataTable({

				                "bDestroy": true,

				                "bInfo": true,

				                "bFilter": true,

				                "bSort": true,
				                
				                 "dom": 'lBfrtip',
				                 "lengthMenu":[
										 [ 10, 25, 50, -1 ],
										 [ '10 rows', '25 rows', '50 rows', 'Show all' ]
										],
           			             "buttons" : [
           			                      {
													   extend: 'print',
													   footer: true,
													   title: filename,
													   orientation: 'landscape',
													   customize: function ( win ) {
                            							    $(win.document.body).find('table')
                                                            .addClass('compact');
                                                    
                            								$(win.document.body).find( 'table' )
                            									.addClass('compact')
                            									.css('font-size','10px')
                            									.css('font-family','sans-serif');
                            								
                            							},
													 },
													 {
														extend:'excel',
														footer: true,
													    title: filename,
													  }
           			                 ],
						       "tableTools": { "buttons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },
                              

				                "aaData": payment.payments,

				                "aoColumns": [    

					                { "mDataProp": "id_employee" },
                                    { "mDataProp": "date_payment" },
					                { "mDataProp": "employee_name" },

					                { "mDataProp": "name" },
					                { "mDataProp": "mobile" },
					                { "mDataProp": "payment_amount" },

					               ],
					                "footerCallback": function ( row, data, start, end, display )
                                 {
                                     var api = this.api(), data;
                                     var length=data.length;
                                     // Remove the formatting to get integer data for summation   /// for total amt footer
                                        var intVal = function ( i ) {
                                        return typeof i === 'string' ?
                                        i.replace(/[\$,]/g, '')*1 :
                                        typeof i === 'number' ?
                                        i : 0;
                                        };
                                        
                                       // Total over all pages
                                        total = api
                                        .column( 5 )
                                        .data()
                                        .reduce( function (a, b) {
                                        return intVal(a) + intVal(b);
                                        }, 0 );
                                        
                                        
                                          // Total over this page
                                        pageTotal = api
                                        .column( 5, { page: 'current'} )
                                        .data()
                                        .reduce( function (a, b) {
                                        return intVal(a) + intVal(b);
                                        }, 0 );
                                        
                                        // Update footer
                                         $( api.column(0).footer() ).html(length);		
                                        $( api.column(5).footer() ).html(parseFloat(pageTotal).toFixed(2));

                                 }
                                
				            });			  	 	
					  	 }	
 }
 
function get_enquiry_list(from_date="",to_date="")
{
    var type = $("#feed_filter_type").val();
    var status = $("#feed_filter_status").val();
	my_Date = new Date();
    $("div.overlay").css("display", "block"); 
	$.ajax({
		 url:base_url+ "index.php/admin_reports/ajax_enquiry_list?nocache=" + my_Date.getUTCSeconds(),
		 data: (from_date !='' && to_date !=''? {'from_date':from_date,'to_date':to_date,'type':type,'status':status}: {'type':type,'status':status}),
		 dataType:"JSON",
		 type:"POST",
		 success:function(data){
		 	$('#total_enquiry').text(data.enquiry.length);
		   			set_enquiry_list(data);
		   			 $("div.overlay").css("display", "none"); 
				  },
				  error:function(error)  
				  {
					 $("div.overlay").css("display", "none"); 
				  }	 
	});
}


function set_enquiry_list(data)
{
	$('body').addClass("sidebar-collapse");
	 var enquiry = data.enquiry;
	 var access = data.access;
	 var oTable = $('#enquiry_list').DataTable();
	 
	     oTable.clear().draw();

			  	 if (enquiry!= null && enquiry.length > 0)
			  	  {
			  	      
			  	      oTable = $('#enquiry_list').dataTable({
				                "bDestroy": true,
				                "bInfo": true,
				                "bFilter": true,
				                "bSort": true,				                
				                "dom": 'T<"clear">lfrtip',				                
				                "tableTools": { "aButtons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },
				                "aaData": enquiry,
				                "order": [[ 0, "desc" ]],
				                "aoColumns": [  { "mDataProp": "id_enquiry" },
				                                { "mDataProp": function ( row, type, val, meta ){
													return (row.ticket_no == '' || row.ticket_no == null ? '-': row.ticket_no);
												}}, 
				                                { "mDataProp": "name" },
												{ "mDataProp": "mobile" },
            								// 	{ "mDataProp": "email" },
												{ "mDataProp": "date_add" },
												{ "mDataProp": "coin_type" },
												{ "mDataProp": "gram" },
												{ "mDataProp": "product_name" },
												{ "mDataProp": "title" },
												{ "mDataProp": "comments" },
												{ "mDataProp": function ( row, type, val, meta ){
												    //  0-Open, 1-In Follow up, 2-Closed
													var status = (row.status == 0 ? 'Open' : (row.status == 1 ?'In Follow up' : (row.status == 2 ? 'Closed':'-' )) );
													var color = (row.status == 0 ? 'bg-teal' : (row.status == 1 ?'label-warning' : (row.status == 2 ? 'bg-green':'' )) );
													return "<span class='label "+color+"'>"+status+"</span>"
												}}, 
												{ "mDataProp": function ( row, type, val, meta ){
												    return (row.last_narration == null ? "-":row.last_narration);
												}},
            					                { "mDataProp": function ( row, type, val, meta ){
													return (row.enq_from == 1 ? 'Web App': row.enq_from == 2 ?'Mobile App':'');
												}}, 
												{ "mDataProp": function ( row, type, val, meta ){
												    edit = ((row.status < 2)?'<li><a href="#" class="btn-edit" onClick="update_enq_status('+row.id_enquiry+')"><i class="fa fa-edit" ></i> Update Status</a></li>':'');
                                            	    action_content='<div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button><ul class="dropdown-menu">'+
                            	                    edit+'<li><a href="#" class="btn-edit" onClick="enq_detail('+row.id_enquiry+')"><i class="fa fa-eye" ></i> Detail</a></li></ul></div>';
                            	                    return action_content;
												}},

											 ] 
				            });
									        
				  }  
				  $("div.overlay").css("display", "none");    
				            			  	 	
}


    function update_enq_status(id){
    	$('#update_enq_status').modal('show', {backdrop: 'static'});
    	$("#id_enquiry").val(id); 
    } 
    
    
    function enq_detail(id){
    	$('.enq_status_dtl').html(enqStatusData(id));
    	$('#enq_status_detail').modal('show', {backdrop: 'static'});
    }
    	
    function enqStatusData(id)
    {
        var transaction="";
        $("div.overlay").css("display", "block");
        $.ajax({
            url:base_url+ "index.php/admin_reports/enquiry/View/"+id,
            dataType:"JSON",
            type:"POST",
            async:false,
            success:function(data){
                transaction  = "<table class='table table-bordered trans'><tr ><th>ID</th><th>Date</th><th>Description</th><th>Internal Status</th><th>Employee</th><th>Status</th></tr>";
                $.each(data, function (key, val) {
                    var status = (val.status == 0 ? 'Open' : (val.status == 1 ?'In Follow up' : (val.status == 2 ? 'Closed':'' )) );
					var color = (val.status == 0 ? 'bg-teal' : (val.status == 1 ?'label-warning' : (val.status == 2 ? 'bg-green':'' )) );
                    transaction = transaction+"<tr><td><span>"+val.id_cusenq_status+"</span></td><td><span>"+val.date_add+"</span></td><td><span>"+val.enq_description+"</span></td><td><span>"+val.internal_status+"</span></td><td>"+val.emp_name+"</td><td><span class='label "+color+"'>"+status+"</span></td></tr>";
                })
                transaction = transaction+"</table>";
                $("div.overlay").css("display", "none");
                return transaction;
            },
            error:function(error)  
            {
                $("div.overlay").css("display", "none"); 
            }	 
        }); 
        return transaction;	
    	
    }

function get_interWalTrans_list(from_date="",to_date="",id_branch="")
{  
	my_Date = new Date();
	 $("div.overlay").css("display", "block"); 
	 var searchTerm = $("#searchTerm").val();
	 var filterBy = $("#filter_by").val();
	 var id_branch = $("#branch_select").val();
	 var from_date=from_date;
	 var to_date=to_date;
	$.ajax({
			  url:base_url+"index.php/admin_reports/ajax_interWallet_trans?nocache=" + my_Date.getUTCSeconds(),
			 data: ((from_date !='' && to_date !='') || id_branch!='' ? ( filterBy !='' && searchTerm !='' ? {'from_date':from_date,'to_date':to_date,'searchTerm':searchTerm,'filterBy':filterBy,'id_branch':id_branch}:{'from_date':from_date,'to_date':to_date,'id_branch':id_branch} ): (searchTerm != '' && filterBy != ''? {'from_date':from_date,'to_date':to_date,'searchTerm':searchTerm,'filterBy':filterBy,'id_branch':id_branch}:'')),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			   			set_interWalTrans_list(data);
			   			 $("div.overlay").css("display", "none"); 
					  },
					  error:function(error)  
					  {
						 $("div.overlay").css("display", "none"); 
					  }	 
			      });
}
function set_interWalTrans_list(data)	
{
   var customer = data.trans; 
   var oTable = $('#interWalList').DataTable();
   $("#total_customers").text(customer.length);
	 oTable.clear().draw();
   	 if (customer!= null && customer.length > 0)
	 {
	 	oTable = $('#interWalList').dataTable({
				                "bDestroy": true,
				                "bInfo": true,
				                "bFilter": true,
				                "bSort": true,				                
				                "dom": 'lBfrtip',
           		             	"buttons" : ['excel','print'],
				                "tableTools": { "buttons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },
				                "aaData": customer,
				                "order": [[ 0, "desc" ]],
				                "aoColumns": [ { "mDataProp": "id_inter_waltransdetail" },
					                { "mDataProp": "branch" },
					                { "mDataProp": "mobile" },
					                { "mDataProp": "name" },
					                { "mDataProp": "trans_type" },
					                { "mDataProp": "bill_date" },
					                { "mDataProp": "trans_date" },
					                { "mDataProp": "bill_no" },
					                { "mDataProp": "cat_name" },
					                { "mDataProp": "bill_amount" },
					                { "mDataProp": "credit" },
					                { "mDataProp": "debit" }] 
				            });	
	 }  
}

function scheme_wise_account(id_branch="")
{

	
	my_Date = new Date();
	 $("div.overlay").css("display", "block"); 
	
	$.ajax({
			  url:base_url+"index.php/admin_reports/accounts_schemewise_detail?nocache=" + my_Date.getUTCSeconds(),
			 dataType:"JSON",
				data: {'id_branch':id_branch},  
			 type:"POST",
			 success:function(data){
			   			set_scheme_wise_account(data);
			   			 $("div.overlay").css("display", "none"); 
					  },
					  error:function(error)  
					  {
						 $("div.overlay").css("display", "none"); 
					  }	 
			      });

}

function set_scheme_wise_account(data)
{

	var id_branch=$('#branch_select').val();



	 var oTable = $('#scheme_wise_account').DataTable();
   	
	 oTable.clear().draw();
   	
	 	oTable = $('#scheme_wise_account').dataTable({

				               
										"bDestroy": true,
										"bInfo": false,
										"bFilter": false,								
										"scrollX":'100%',
										"bAutoWidth": false,
										"bSort": true,
										"dom": 'Bfrtip',
										"lengthMenu":[
										 [ 10, 25, 50, -1 ],
										 [ '10 rows', '25 rows', '50 rows', 'Show all' ]
										],
										"buttons": [
										{
										   extend: 'print',
										   footer: true,
										 
										   customize: function ( win ) {
												$(win.document.body).find( 'table' )
													.addClass( 'compact' )
													.css( 'font-size', 'inherit' );
											},
										 },
										 {
												extend:'excel',
										 },
										 {
											extend:'pageLength',
											customize: function ( win ) {
											$(win.document.body).find( 'table' )
												.addClass( 'compact' )
												.css( 'font-size', 'inherit' );
													},
										 }
										 
										 ],  
				                "aaData": data.accounts,
				                "order": [[ 0, "desc" ]],
				               "aoColumns": [ 
				               		{ "mDataProp": "id_scheme" },
					               // { "mDataProp": "scheme_name" },
					               { "mDataProp": function ( row, type, val, meta ){
					                	if(row.has_lucky_draw==1 && row.is_lucky_draw==1){
					                	return row.scheme_name+' - '+row.group_code;
					                	}
					                	else{
					                		return row.scheme_name;
					                	}
					                }},
					                { "mDataProp": "code" },
					                { "mDataProp": "accounts" },
					                { "mDataProp": "inactive" },
					                { "mDataProp": function ( row, type, val, meta ){
													return parseFloat(parseFloat(row.accounts)+parseFloat(row.inactive));
												}},
											],
					                
				            });	
	  
}

function payment_schemewise(from_date="",to_date="",id_branch="")
{

	
	my_Date = new Date();
	 $("div.overlay").css("display", "block"); 
	
	$.ajax({
			  url:base_url+"index.php/admin_reports/payment_schemewise_detail?nocache=" + my_Date.getUTCSeconds(),
			 dataType:"JSON",
				data: (from_date !='' && to_date !=''? {'from_date':from_date,'to_date':to_date,'id_branch':id_branch}: ''),  
			 type:"POST",
			 success:function(data){
			   			set_payment_schemewise(data);
			   			 $("div.overlay").css("display", "none"); 
					  },
					  error:function(error)  
					  {
						 $("div.overlay").css("display", "none"); 
					  }	 
			      });

}

function set_payment_schemewise(data)
{

	var id_branch=$('#branch_select').val();



	 var oTable = $('#payment_schemewise').DataTable();
   	
	 oTable.clear().draw();
   	
	 	oTable = $('#payment_schemewise').dataTable({

										"bDestroy": true,
										"bInfo": false,
										"bFilter": false,								
										"scrollX":'100%',
										"bAutoWidth": false,
										"bSort": true,
										"dom": 'Bfrtip',
										"lengthMenu":[
										 [ 10, 25, 50, -1 ],
										 [ '10 rows', '25 rows', '50 rows', 'Show all' ]
										],
										"buttons": [
										{
										   extend: 'print',
										   footer: true,
										   title: 'Scheme-wise Payment Report '+$('#rpt_scheme_payment1').text()+' - '+$('#rpt_scheme_payment2').text(),
										   customize: function ( win ) {
												$(win.document.body).find( 'table' )
													.addClass( 'compact' )
													.css( 'font-size', 'inherit' );
											},
										 },
										 {
											extend:'excel',
											title: 'Scheme-wise Payment Report '+$('#rpt_scheme_payment1').text()+' - '+$('#rpt_scheme_payment2').text(),
										 },
										 {
											extend:'pageLength',
											customize: function ( win ) {
											$(win.document.body).find( 'table' )
												.addClass( 'compact' )
												.css( 'font-size', 'inherit' );
													},
										 }
										 
										 ],  
				                "aaData": data.payments,
				                "order": [[ 0, "desc" ]],
				               "aoColumns": [ 
				               		{ "mDataProp": "id_scheme" },
					                { "mDataProp": "scheme_name" },
					                { "mDataProp": "name" },
					                { "mDataProp": "code" },
					              { "mDataProp": function ( row, type, val, meta ){
													return parseFloat(parseFloat(row.paid)+parseFloat(row.unpaid));
												}},
					                { "mDataProp": "paid" },
					                { "mDataProp": "unpaid" },
					                
											],
					                
				            });	
	  
}


function customer_wise_payment(id_branch="")
{
	my_Date = new Date();
	 $("div.overlay").css("display", "block"); 
	
	$.ajax({
			  url:base_url+"index.php/admin_reports/ajax_customer_payment_details?nocache=" + my_Date.getUTCSeconds(),
			 dataType:"JSON",
			 data: {'id_branch':id_branch}, 
				//data: (from_date !='' && to_date !=''? {'from_date':from_date,'to_date':to_date,'id_branch':id_branch}: ''),  
			 type:"POST",
			 success:function(data){
			   			set_customer_payment_details(data);
			   			 $("div.overlay").css("display", "none"); 
					  },
					  error:function(error)  
					  {
						 $("div.overlay").css("display", "none"); 
					  }	 
			      });

}


function set_customer_payment_details(data,village_name)
{
	var id_branch=$('#branch_select').val();
	if(data.accounts.length > 0){
		$("#total").html(data.accounts.length);
	}
	 var oTable = $('#customer_pay_details').DataTable();
	 oTable.clear().draw();
	 oTable = $('#customer_pay_details').dataTable({ 
                "bDestroy": true,
				"responsive": true, 
                "bInfo": false,
                "bFilter": true,								
                "scrollX":'100%',
				"bAutoWidth": false,
                "bSort": true,
				 "dom": 'lBfrtip',
			    "lengthMenu":[
						 [ 10, 25, 50, -1 ],
						 [ '10 rows', '25 rows', '50 rows', 'Show all' ]
						],
				 buttons: [
					{						
						   extend: 'print',	
						    title: function(){
							var printTitle = 'Sri Krishna Jewellers -'+village_name;
							return printTitle
						  }
					},
					{
						  extend: 'excel',
						  title: function(){
							var printTitle = 'Sri Krishna Jewellers -'+village_name;
							return printTitle
						  }
					}
			    ], 
                "order": [[ 0, "desc" ]],
				"aaData": data.accounts,				
                "aoColumns": [ 
               		 { "mDataProp":function (row,type,val,meta){
								id=row.id_customer;
								if(row.current_due=="Paid"){
									return  row.id_scheme_account;
								}else{
								return "<label class='checkbox-inline'><input type='checkbox' class='flat-red' name='id_customer[]' value='"+row.id_customer+"' /> "+row.id_scheme_account+" </label>";
					 } }},
               		{ "mDataProp": function ( row, type, val, meta ) {
	                	var url = base_url+'index.php/reports/payment/account/'+row.id_scheme_account;
	                	action = '<a href="'+url+'" target="_blank">'+row.id_customer+'</a>';
	                	return action;
	                	}
	                },
	                { "mDataProp": "name" },
	                { "mDataProp": "mobile" },
					{ "mDataProp": function ( row, type, val, meta ){
						return row.code+"-"+row.scheme_acc_number;
					}},					                
	                { "mDataProp": "start_date" },
	                { "mDataProp": "last_paid_date" },
	              /*  { "mDataProp": function ( row, type, val, meta ){
						var month_count = row.months_count.replace("-","");
						if (parseInt(month_count) > row.total_installments){
							return row.total_installments - row.paid_installments;
						}
						else if(parseInt(month_count) == row.total_installments){
							return 0;
						}
						else{
							if(parseInt(month_count) == row.paid_installments){
								return 0;
							}
							else{ 
								return parseInt(month_count) - row.paid_installments;
							} 
						}
					}},
					{ "mDataProp": function ( row, type, val, meta ){
						var month_count = row.months_count.replace("-","");
						if (parseInt(month_count) > row.total_installments){
							return row.amount * (row.total_installments - row.paid_installments);
						}
						else if(parseInt(month_count) == row.total_installments){
							return 0;
						}
						else{
							if(parseInt(month_count) == row.paid_installments){
								return 0;
							}
							else{
								return row.amount * (parseInt(month_count) - row.paid_installments);
							} 
						}
					}},*/
					/*{ "mDataProp": function ( row, type, val, meta ){
					//	GG
						var d = new Date(),
								month = '' + (d.getMonth() + 1),
								day = '' + d.getDate(),
								year = d.getFullYear();
						if (month.length < 2)
							month = '0' + month;
						if (day.length < 2)
							day = '0' + day;
						var date = new Date();
							date.toLocaleDateString();
							var format =  [year,month,day].join('-');
							var sdate = row.start_date;
							var s = (format.split('-')[0]);
							var b = (sdate.split('-')[0]); 
							var yearinmonth = ((s-b)*12);
							var c = (format.split('-')[1]);
							var d = (sdate.split('-')[1]);
							var monthcount = (c-d);
							var month = yearinmonth+monthcount;
							skippeddue = month - row.total_paid  ;
							//console.log(skippeddue);
							if(skippeddue<= '0' ){
								return '0'
							}else if(skippeddue >= row.total_installments){
								skippeddue = row.total_installments - row.total_paid;
								return skippeddue;
							}else{
								return (month - row.total_paid)+1;
							}
						}					                	
	                },*/
	               /* { "mDataProp": function ( row, type, val, meta ){
						var d = new Date(),
								month = '' + (d.getMonth() + 1),
								day = '' + d.getDate(),
								year = d.getFullYear();
						if (month.length < 2)
							month = '0' + month;
						if (day.length < 2)
							day = '0' + day;
						var date = new Date();
							date.toLocaleDateString();
							var format =  [year,month,day].join('-');
							var sdate = row.start_date;
							sdate = sdate.split(' ')[0];
							var s = (format.split('-')[0]);
							var b = (sdate.split('-')[0]); 
							var yearinmonth = ((s-b)*12);
							var c = (format.split('-')[1]);
							var d = (sdate.split('-')[1]);
							var monthcount = (c-d);
							var month = yearinmonth+monthcount;
							skippeddue = month - row.total_paid  ;
							//console.log(skippeddue);
							if(row.scheme_type == 'Amount' || row.scheme_type == 'Amount to Weight'){
							if(skippeddue<= '0' ){
								return '-'
							}else if(skippeddue >= row.total_installments){
								skippeddue = row.total_installments - row.total_paid;
								return (skippeddue*row.amount).toFixed(2);
							}else{
								return (((month - row.total_paid)+1)*row.amount).toFixed(2);
							}
							}
							else{
								return pendngAmount ='-';
							}
						}			//GG		                	
	                },*/
	                { "mDataProp": "amount" },
	                { "mDataProp": "total_installments" },
	                { "mDataProp": "paid_installments" },
	                { "mDataProp": function ( row, type, val, meta ){
	                	return row.total_installments - row.paid_installments;
	                }	
	                },
	                { "mDataProp": "current_due" },
							],
            });	
		 $("div.overlay").css("display", "none"); 
}

function get_inter_wallet_woc() {
	my_Date = new Date();
	 $("div.overlay").css("display", "block"); 
	
	$.ajax({
			  url:base_url+"index.php/admin_dashboard/inter_wallet_accounts__woc_det?nocache=" + my_Date.getUTCSeconds(),
			 dataType:"JSON",
				
			 type:"POST",
			 success:function(data){
			   			set_acc_list(data);
			   			 $("div.overlay").css("display", "none"); 
					  },
					  error:function(error)  
					  {
						 $("div.overlay").css("display", "none"); 
					  }	 
			      });
}

function set_acc_list(data) {
	 var oTable = $('#inter_wallet').DataTable();
   	
	 oTable.clear().draw();
   	
	 	oTable = $('#inter_wallet').dataTable({

				                "bDestroy": true,
								"responsive": true, 
				                "bInfo": false,
				                "bFilter": true,								
				                "scrollX":'100%',
								"bAutoWidth": false,
				                "bSort": true,
								
							    "lengthMenu":[
										 [ 10, 25, 50, -1 ],
										 [ '10 rows', '25 rows', '50 rows', 'Show all' ]
										],
								 
				                "order": [[ 0, "desc" ]],
								 "aaData": data.accounts,				
				               "aoColumns": [ 
				               		{ "mDataProp": "id_inter_wal_ac" },
					                { "mDataProp": "mobile" },
					                { "mDataProp": "date_add" },
					               
					                { "mDataProp": "available_points" },
					                
					                
											],
					                
				            });	
}



 /*function get_branchname(){	
     	$(".overlay").css('display','block');	
     	$.ajax({		
         	type: 'GET',		
         	url: base_url+'index.php/branch/branchname_list',		
         	dataType:'json',		
         	success:function(data){	
         	    var id_branch=$('#id_branch').val();
         		$('#branch_select').append(						
            	 	$("<option></option>")						
            	 	.attr("value", 0)						  						  
            	 	.text('All' )
						
            	 	);			  	   
        	 	$.each(data, function (key, item) {					  				  			   		
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
             	$("#branch_select").select2("val",(id_branch!=''?id_branch:''));
             	$(".overlay").css("display", "none");			
         	}	
        }); 
    }*/
	
function get_employee_acc_list(from_date="",to_date="",id_branch ="",id_employee="")

	{
		
	my_Date = new Date();

	 $("div.overlay").css("display", "block"); 

	

	$.ajax({

			  url:base_url+ "index.php/reports/ajax_emp_account_list?nocache=" + my_Date.getUTCSeconds(),

			 data: (from_date !='' && to_date !=''? {'from_date':from_date,'to_date':to_date,'id_branch':id_branch,'id_employee':id_employee}: ''),

			 dataType:"JSON",

			 type:"POST",

			 success:function(data){

			   			set_employee_acc_list(data);
			   			$('body').addClass("sidebar-collapse");

			   			 $("div.overlay").css("display", "none"); 

					  },

					  error:function(error)  

					  {

						 $("div.overlay").css("display", "none"); 

					  }	 

			      });

}

   
   $(document).on('click', '.select_ids', function(e){
	
 $("#emp_acc_list tbody tr").each(function(index, value) 
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

function set_employee_acc_list(data)


{
	
	
	

	 var account 	= data;



	 
	 var oTable = $('#emp_acc_list').DataTable();

	     oTable.clear().draw();
		 
		        

					  	oTable = $('#emp_acc_list').dataTable({
							
							

				                "bDestroy": true,

				                "bInfo": true,

				                "bFilter": true,

				                "bSort": true,

				                "order": [[ 0, "desc" ]],

								 "dom": 'lBfrtip',
           			              "buttons": [
										{
										   extend: 'print',
										   footer: true,
										   title:'Employee Wise Scheme Accounts Report '+$('#account_list1').text()+' - '+$('#account_list2').text(),
										   customize: function ( win ) {
												$(win.document.body).find( 'table' )
													.addClass( 'compact' )
													.css( 'font-size', 'inherit' );
											},
										 },
										 {
										     title:'Employee Wise Scheme Accounts Report '+$('#account_list1').text()+' - '+$('#account_list2').text(),
											extend:'excel' 
										 },
									    ], 
						         "tableTools": { "buttons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },
                                "lengthMenu": [[-1, 25, 50, 100, 250], ["All" ,25, 50, 100, 250]],

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
					                	if(row.has_lucky_draw==1 && row.is_lucky_draw==1){
					                	return row.group_code+' '+row.scheme_acc_number;
					                	}
					                	else{
					                		return row.code+' '+row.scheme_acc_number;
					                	}
					                }},

					                { "mDataProp": "is_new" },

					                { "mDataProp": "start_date" },

					                { "mDataProp": "scheme_type" },

								
					                
                            { "mDataProp": function ( row, type, val, meta ){
								
							
                            
                            amount=row.currency_symbol+" "+row.amount;
                            
                            weight="Max "+row.amount+" g/month";
                            
                                           	  if(row.scheme_types == '0')
                            
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
                            
                            }
                            
                                           },
					                
					                  { "mDataProp": "pan_no" },
									  
									  
					                
					                { "mDataProp":"paid_installments"},
					                
					              
					                

									{ "mDataProp": function ( row, type, val, meta ){

					                	    active_url =base_url+"index.php/account/status/"+(row.active=='Active'?0:1)+"/"+row.id_scheme_account; 

					                		return "<a href='"+active_url+"'><i class='fa "+(row.active=='Active'?'fa-check':'fa-remove')+"' style='color:"+(row.active=='Active'?'green':'red')+"'></i></a>"

					                	}

					                },
									
									{ "mDataProp":"employee_name"},

					                { "mDataProp": function ( row, type, val, meta ) {

					                	

					                	return (row.added_by=='0'?"Customer":(row.added_by=='1'?"Employee":"Customer"));



					                	}},

								],
								 "footerCallback": function ( row, data, start, end, display )
                                 {
                                     var api = this.api(), data;
                                     var length=data.length;
                                     /* // Remove the formatting to get integer data for summation   /// for total amt footer
                                        var intVal = function ( i ) {
                                        return typeof i === 'string' ?
                                        i.replace(/[\$,]/g, '')*1 :
                                        typeof i === 'number' ?
                                        i : 0;
                                        };
                                        
                                       // Total over all pages
                                        total = api
                                        .column( 4 )
                                        .data()
                                        .reduce( function (a, b) {
                                        return intVal(a) + intVal(b);
                                        }, 0 );
                                        
                                        
                                          // Total over this page
                                        pageTotal = api
                                        .column( 4, { page: 'current'} )
                                        .data()
                                        .reduce( function (a, b) {
                                        return intVal(a) + intVal(b);
                                        }, 0 );
                                         */
                                        // Update footer
                                         $( api.column(0).footer() ).html(length);		
                                        //$( api.column(4).footer() ).html(parseFloat(pageTotal).toFixed(2));

                                 }



				            });			  	 	
  
}


//Employee collection summary

function get_emp_summary_list(from_date="",to_date="",id_branch="",id_emp="")
{

	my_Date = new Date();
	console.log(id_branch);
	console.log(id_emp);
	var date_type=$('#date_Select').find(":selected").val();
		 $("div.overlay").css("display", "block");
			$.ajax({
		data: (from_date !='' && to_date !=''? {'from_date':from_date,'to_date':to_date,'id_branch':id_branch,'id_emp':id_emp}: ''), //hh

			  url:base_url+ "index.php/reports/employee_wise_summary?nocache=" + my_Date.getUTCSeconds(),
			
			  dataType:"JSON",
			  type:"POST",
			 success:function(data){
				get_employee_summary(data);	
				 $("div.overlay").css("display", "none"); 
		 	 	},
		 	 	error:function(error)  

					  {

						 $("div.overlay").css("display", "none"); 

					  }	
	  });
}
 function get_employee_summary(data){
 
 	var payment=data;
 

 	var oTable = $('#emp_summary_list').DataTable();
    var groupColumn = 1;
	     oTable.clear().draw();

			  	 if (payment!= null)

			  	  {  	
					  	oTable = $('#emp_summary_list').dataTable({
					  	    
                                "columnDefs": [
                                { "visible": false, "targets": groupColumn }
                                ],
                                
                            "drawCallback": function ( settings ) {
                           var api = this.api();
                            var rows = api.rows( {page:'current'} ).nodes();
                            var last=null;
                            var subTotal = new Array();
                            var groupID = -1;
                            var aData = new Array();
                            var index = 0;
                             
                            api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                                
                                var data = api.row(api.row($(rows).eq(i)).index()).data();  //Response data
                                 var payment_amount = data.payment_amount;
                                  
                                if ( typeof aData[group] == 'undefined') {
                                aData[group] = new Array();
                                aData[group].rows = [];
                                aData[group].payment_amount = [];
                                }
                                
                                aData[group].rows.push(i); 
        		            	aData[group].payment_amount.push(payment_amount); 
                                
                           
                            } );
                            
                           
                            var idx= 0;
                             var sum = 0; 
                            for(var employee in aData)  //column name
                            {  
                            idx =  Math.max.apply(Math,aData[employee].rows);
                            $.each(aData[employee].payment_amount,function(k,v){
                            sum = parseFloat(sum)+parseFloat(v);
                            });
                            
                            $(rows).eq( idx ).after(
                            '<tr class="group" style="    background-color: #ccc;font-weight: bold;"><td class="tot-label" colspan="5">Total</td>'+
                            '<td class="total">'+(sum.toFixed(2))+'</td></tr>'
                            );
                          
                            };
                            },

				                "bDestroy": true,

				                "bInfo": true,

				                "bFilter": true,

				                "bSort": true,
				                 "dom": 'Bfrtip',
				                "buttons": [ 
				                { 
				                        extend: 'print', 
				                        footer: true, 
				                        title:'Employee collection summary '+$('#rpt_payments1').text()+' - '+$('#rpt_payments2').text(), 
				                        customize: function ( win ) 
				                        { $(win.document.body).find( 'table' ) .addClass( 'compact' ) .css( 'font-size', 'inherit' ); }, 
				                        
				                    }, 
				                    { 
				                        extend:'excel',
				                        footer: true, 
				                        title:'Employee collection summary '+$('#rpt_payments1').text()+' - '+$('#rpt_payments2').text()
				                    }
				                ],
				                
				                 
				                 "lengthMenu":[
										 [ 10, 25, 50, -1 ],
										 [ '10 rows', '25 rows', '50 rows', 'Show all' ]
										],
										
				                "aaData": payment.payments,

				                "aoColumns": [    

					                { "mDataProp": "id_employee" },

					                { "mDataProp": "firstname" },
					                { "mDataProp": "firstname" },
					                 { "mDataProp": "name" },
                                    { "mDataProp": "code" },
					                { "mDataProp": "receipt" },
					                { "mDataProp": "payment_amount" },
					               

					               ]
					       
                                
				            });			  	 	
					  	 }	
 }

   $("#print").on('click',function(){
	   
        	
			newWin= window.open("");
			var divToPrint=document.getElementById("emp_summary_list");
			$('#emp_summary_list').css('text-align', 'left'); 
			newWin.document.write(divToPrint.outerHTML);
			newWin.document.title ='Summary Collection Report';
		   newWin.print();
		   newWin.close();
		
		
		   
    });
    
//Employee collection summary


$('#pay_mode').on('change',function(){
    
        $('#added_by').val(this.value);
    	var selected_date = $("#schreport_date").val();
    	var id_branch   = $('#id_branch').val();	
    	var id_employee= $('#id_employee').val();
    	var added_by=$('#added_by').val();
    	generate_paymodewise_schemelist(selected_date,id_branch,id_employee,added_by);
    
});

$('#pay_mode').on('change',function(){
    
        $('#added_by').val(this.value);
    	var selected_date = $("#modereport_date").val();
        var added_by=$('#added_by').val();
    	generate_online_offline_collection(selected_date,added_by);
    
});


//mob no,ref no,clientid,sch A/c no wise filter & change options in inter table Data's // 
// Customer Reg& transaction records  // HH	

 
 $("input[name='upd_mob_btn']:radio").change(function(){
		if($("input[name='id_customer_reg[]']:checked").val())
		{
			var selected = [];
			var update=true;
			$("#intertable_list tbody tr").each(function(index, value){
				if($(value).find("input[name='id_customer_reg[]']:checked").is(":checked")){ 
					data = { 'id_customer_reg'   : $(value).find(".id_customer_reg").val(), 
							 'mobile'  : $(value).find(".mobile").val(),  'scheme_ac_no'  : $(value).find(".scheme_ac_no").val(), 
						   'group_code'  : $(value).find(".group_code").val(), 
					    
					}

					selected.push(data);
					update=true;
					$("input[name='upd_mob_btn']").removeAttr('checked'); 
				}
				else
				{
					update=true;
				}
				  
			}) 
			if(update==true)
			{
				update_cus_datas(selected);
			}	
		}
	});
 	  
function update_cus_datas(postData="")
{
	
	my_Date = new Date();
	$("div.overlay").css("display", "block"); 
	$.ajax({
			 url:base_url+ "index.php/admin_reports/update_cusdatas?nocache=" + my_Date.getUTCSeconds(),
		   data: {postData},
			 type:"POST",
			 async:false,
			 	  success:function(data){
			          //  location.reload(false);
			   			$("div.overlay").css("display", "none"); 
			   			location.reload(true);
				  },
				  error:function(error)  
				  {

				  		console.log(error);
					 $("div.overlay").css("display", "none"); 
				  }	 
		  });
}		

 
 $('#Table_Select').select2().on("change", function(e) 
{           
      
      if(this.value==1)
      {
           $("#table1").css("display", "block");
           $("#table2").css("display", "none");
                      
           $("#table").css("display", "block");
                   //$('#mobile').val(''); 
                           $("#mob").show();
           	          $("#mobilenumber").show();
           	              $("#mob1").show();
           	          $("#group_code").show();
				        var mobile = $('#mobilenumber').text();
						var clientid  = $('#clientid').text();
						var ref_no  = $('#ref_no').text();
						var group_code  = $('#group_code').text();
						//var scheme_ac_no  = $('#scheme_ac_no').text();
				          $("#id_cus").val((this).value);
			//get_intertable_cusdata(mobile,clientid,ref_no,group_code,cus="");
      }
      else if(this.value==2)
      {
            $("#table1").css("display", "none");
           $("#table2").css("display", "block");
      	       $("#table").css("display", "block");
				    $("#mob").hide();
					$("#mobilenumber").hide();
					$("#mob1").hide();
					$("#group_code").hide();
					
						var client_id  = $('#client_id').text();
						var ref_no  = $('#ref_no').text();
						//var group_code  = $('#group_code').text();
						//var scheme_ac_no  = $('#scheme_ac_no').text();
				          $("#id_cus").val((this).value);
			//get_intertable_transdata(client_id,ref_no,cus="");
          
      }
      
});
 
 $('#mob_submit').on('click',function()
 {
     var id_cus=$('#id_cus').val();
	if(id_cus==1)
	{
        var mobile=$('#mobilenumber').val();
        var clientid=$('#clientid').val();
        var ref_no=$('#ref_no').val();
        var group_code=$('#group_code').val();
        get_intertable_cusdata(mobile,clientid,ref_no,group_code);
	}
	else
	{
        var clientid=$('#clientid').val();
        var ref_no=$('#ref_no').val();
       // console.log(client_id);
        get_intertable_transdata(clientid,ref_no);
	}
});

function get_intertable_cusdata(mobile,clientid,ref_no,group_code,cus="")
{
    	 var cus=$('#Table_Select').find(":selected").val();
	 $("div.overlay").css("display", "block"); 
    	my_Date = new Date();
	$("div.overlay").css("display", "block");
		var oTable = $('#intertable_list').DataTable(); 
		oTable.clear().draw();
		$.ajax({
				  type: 'POST',
				  url:  base_url+'index.php/reports/intertable_list',
		          data: {'mobile':mobile,'clientid':clientid,'ref_no':ref_no,'group_code':group_code,'cus':cus},
				  dataType: 'json',
				  success: function(data) {	
					  console.log(data);
				       oTable = $('#intertable_list').dataTable({
				                "bDestroy": true,
				     
				                "bFilter": true,
				                "bSort": true,
				                "aaSorting": [[ 0, "desc" ]], 
				                "tableTools": { "aButtons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'all' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'all' } } ] },
				                "aaData": data,
				                "aoColumns": [  { "mDataProp": function ( row, type, val, meta ){ 
		                	chekbox='<input type="checkbox" class="id_customer_reg" name="id_customer_reg[]" value="'+row.id_customer_reg+'"/> ' 
		                if(row.is_transferred=='N' || row.is_transferred=='Y'){
					                  return chekbox+" "+row.id_customer_reg;
									    	}
									    		else{
									    	    return row.id_customer_reg;
									    		}
		                	
		                }},
							     
							                    { "mDataProp": "clientid" },
							                    { "mDataProp": "id_branch" },
							                    { "mDataProp": "record_to" },
							                    { "mDataProp": "is_modified" },
							                    { "mDataProp": "reg_date" },
							                    { "mDataProp": "ac_name" },
							                    { "mDataProp": "firstname" },
							                    { "mDataProp": "lastname" },
							                    { "mDataProp": "address1" },
							                    { "mDataProp": "address2" },
							                    { "mDataProp": "address3" },
							                    { "mDataProp": function ( row, type, val,meta ) { 	
									    	if(row.is_transferred=='N' || row.is_transferred=='Y'){
					                   return '<input type="number" class="mobile no form-control" name="mobile" value="'+row.mobile+'" type="text" />';
									    	}
									    		else{
									    	    return row.mobile;
									    		}
					                }},	
				                         
							                    { "mDataProp": "new_customer" },
							                    { "mDataProp": "ref_no" },
							                    { "mDataProp": "id_scheme_account"},
							                    { "mDataProp": "sync_scheme_code"},
							                    { "mDataProp": function ( row, type, val,meta ) { 	
									    	if(row.is_transferred=='N' || row.is_transferred=='Y'){
					                   return '<input type="email" class="group_code no form-control" name="group_code" value="'+row.group_code+'" type="text" />';
									    	}
									    		else{
									    	    return row.group_code;
									    		}
					                }},	
							                    { "mDataProp": function ( row, type, val,meta ) {
								                	if(row.is_transferred=='N' || row.is_transferred=='Y'){
					                   return '<input type="email" class="scheme_ac_no no form-control" name="scheme_ac_no" value="'+row.scheme_ac_no+'" type="text" />';
									    	}
									    		else{
									    	    return row.scheme_ac_no;
									    		}
					                }},	
				                        
								                { "mDataProp": "is_closed" },
								                { "mDataProp": "closed_by" },
								                { "mDataProp": "closing_date" },
								                { "mDataProp": "closing_amount" },
								                { "mDataProp": "closing_weight" },
								                { "mDataProp": "is_transferred" },
								                { "mDataProp": "transfer_date" },
								                { "mDataProp": "date_update" },
								                { "mDataProp": "date_add" },
								                {"mDataProp": "is_registered_online"}
								                
								                
								             ],
					            });
					                
					    $("div.overlay").css("display", "none");           
				  },
			  	  error:function(error)  
				  {
					 $("div.overlay").css("display", "none"); 
				  }	 
				  
	        });	
}   
    
    
function get_intertable_transdata(client_id,ref_no,cus="")
{
   // var log = {'client_id':client_id,'ref_no':ref_no,'cus':cus};
   // console.log(log);
    	 var cus=$('#Table_Select').find(":selected").val();
	 $("div.overlay").css("display", "block"); 
    	my_Date = new Date();
	$("div.overlay").css("display", "block");
		var oTable = $('#intertable_translist').DataTable(); 
		oTable.clear().draw();
		$.ajax({
				  type: 'POST',
				  url:  base_url+'index.php/reports/intertable_translist',
		          data: {'client_id':client_id,'ref_no':ref_no,'cus':cus},
				  dataType: 'json',
				  success: function(data) {	
					  console.log(data);
				       oTable = $('#intertable_translist').dataTable({
				                "bDestroy": true,
				     
				                "bFilter": true,
				                "bSort": true,
				                "aaSorting": [[ 0, "desc" ]], 
				                "tableTools": { "aButtons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'all' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'all' } } ] },
				                "aaData": data,
				                "aoColumns": [  { "mDataProp": function ( row, type, val, meta ){ 
		                	chekbox='<input type="checkbox" class="id_transaction" name="id_transaction[]" value="'+row.id_transaction+'"/> ' 
		                if(row.is_transferred=='N' || row.is_transferred=='Y'){
					                  return chekbox+" "+row.id_transaction;
									    	}
									    		else{
									    	    return row.id_transaction;
									    		}
		                	
		                }},                
							                 	
							                    { "mDataProp": "client_id" },
							                    { "mDataProp": "record_to" },
							                    { "mDataProp": "payment_date" },
							                    { "mDataProp": "amount" },
							                    { "mDataProp": "weight" },
							                    { "mDataProp": "rate" },
							                    { "mDataProp": "payment_mode" },
							                    { "mDataProp": "ref_no" },
							                    { "mDataProp": "is_transferred" },
							                    { "mDataProp": "is_modified" },
							                    { "mDataProp": "transfer_date" },
							                    { "mDataProp": "new_customer" },
							                    { "mDataProp": "id_scheme_account" },
							                    { "mDataProp": "id_branch" },
							                    { "mDataProp": "payment_status" },
							                    { "mDataProp": "payment_type" },
							                    { "mDataProp": "due_type" },
							                    { "mDataProp": "receipt_no" },
							                    { "mDataProp": "date_add" },
							                    { "mDataProp": "date_upd" },
							                    { "mDataProp": "installment_no" },
				                                { "mDataProp": "emp_code" }
				                               
								                
								                
								             ],
					            });
					                
					    $("div.overlay").css("display", "none");           
				  },
			  	  error:function(error)  
				  {
					 $("div.overlay").css("display", "none"); 
				  }	 
				  
	        });	
}   
//mob no,ref no,clientid,sch A/c no wise filter & change options in inter table Data's // 
// Customer Reg& transaction records  // HH	    



// MSG 91 log listing
function get_msg_translist(from_date="",to_date="")
{
	my_Date = new Date();
    $("div.overlay").css("display", "block"); 
	$.ajax({
		 url:base_url+ "index.php/admin_reports/getCreditHistory?nocache=" + my_Date.getUTCSeconds(),
		 data: (from_date !='' && to_date !=''? {'from_date':from_date,'to_date':to_date}: ''),
		 dataType:"JSON",
		 type:"POST",
		 success:function(data){
		 	     $('#total_trans').text(data.length);
		   			set_msg_translist(data);
		   			 $("div.overlay").css("display", "none"); 
				  },
				  error:function(error)  
				  {
					 $("div.overlay").css("display", "none"); 
				  }	 
	});
}


function set_msg_translist(data)
{
    var trans = data;
    var oTable = $('#msg_trans_list').DataTable();
    oTable.clear().draw();
    if (trans!= null && trans.length > 0)
    {
        
        oTable = $('#msg_trans_list').dataTable({
                "bDestroy": true,
                "bInfo": true,
                "bFilter": true,
                "bSort": true,				                
                "dom": 'T<"clear">lfrtip',				                
                "tableTools": { "aButtons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },
                "aaData": trans,
                "order": [[ 0, "desc" ]],
                "aoColumns": [  { "mDataProp": "trans_date" },
                                { "mDataProp": function ( row, type, val, meta ){
    								return (row.trans_type == 'Add' ? 'Top up': row.trans_type);
    							}},
    							{ "mDataProp": "trans_sms" },
    							{ "mDataProp": "amount" },
    			                { "mDataProp": function ( row, type, val, meta ){
    								return (row.route == 1 ? 'Promotional': row.route == 4 ?'Transactional':'');
    							}},
    							{ "mDataProp": "From" },
    						 ],
    			"footerCallback": function( row, data, start, end, display ) 
        		{
                	if(data.length>0){
                	    var promo_route = 0;
                	    var trans_route = 0;
                	    var api = this.api(), data;
        			    var intVal = function ( i ) {
        				    return typeof i === 'string' ?
        					    i.replace(/[\$,]/g, '')*1 :
        					    typeof i === 'number' ?i : 
        					    0;
        			    };
        			    
                	    for( var i=0; i<=data.length-1;i++){
                    		if(data[i]['route'] ==1){					
                    			promo_route +=  parseFloat(data[i]['trans_sms']);
                    		}
                    		if(data[i]['route'] ==4){					
                    			trans_route +=  parseFloat(data[i]['trans_sms']);
                    		}
                            $( api.column(2).footer() ).html("P.Route : "+promo_route+"<br/> T.Route : "+trans_route); 
                        }
                }
                else{
                	var data=0;
                	var api = this.api(), data;
                	$( api.column(2).footer() ).html(""); 
                }
                }
            });
    				        
    }  
    $("div.overlay").css("display", "none");    
				            			  	 	
}

// MSG 91 delivery report
function get_msgDeliv_report(from_date="",to_date="")
{
	my_Date = new Date();
    $("div.overlay").css("display", "block"); 
	$.ajax({
		 url:base_url+ "index.php/reports/msg91_delivery/ajax_report?nocache=" + my_Date.getUTCSeconds(),
		 data: (from_date !='' && to_date !=''? {'from_date':from_date,'to_date':to_date}: ''),
		 dataType:"JSON",
		 type:"POST",
		 success:function(data){
		 	     $('#total').text(data.length);
		   			set_msgDeliv_report(data);
		   			 $("div.overlay").css("display", "none"); 
				  },
				  error:function(error)  
				  {
					 $("div.overlay").css("display", "none"); 
				  }	 
	});
}


function set_msgDeliv_report(data)
{
    var trans = data;
    var oTable = $('#msg_deliv_report').DataTable();
    oTable.clear().draw();
    console.log(trans);
    if (trans!= null && trans.length > 0)
    {
        oTable = $('#msg_deliv_report').dataTable({
                "bDestroy": true,
                "bInfo": true,
                "bFilter": true,
                "bSort": true,				                
                "dom": 'T<"clear">lfrtip',				                
                "tableTools": { "aButtons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },
                "aaData": trans,
                "order": [[ 0, "desc" ]],
                "aoColumns": [  { "mDataProp": "id_msg91_status" },
    							{ "mDataProp": "request_id" },
                                { "mDataProp": "date" },
    							{ "mDataProp": "receiver" },
    							{ "mDataProp": "description" },
    						 ] 
            });
    				        
    }  
    $("div.overlay").css("display", "none");    
				            			  	 	
}


//Kyc Approval Data status filter with date picker//hh
function get_kyc_list(from_date="",to_date="",status="",type="")
{
	my_Date = new Date();
	postData = (from_date !='' && to_date !='' ? {'from_date':from_date,'to_date':to_date,'status':status,'type':type}:{'status':$('#filtered_status').val(),'type':type});
	 $("div.overlay").css("display", "block");
	$.ajax({
			  url:base_url+ "index.php/admin_reports/kycapproval_data?nocache=" + my_Date.getUTCSeconds(),
			 data: (postData),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			 	$('#total_kyc').text(data.length);
			   			set_kyc_list(data);
			   			 $("div.overlay").css("display", "none"); 
					  },
					  error:function(error)  
					  {
						 $("div.overlay").css("display", "none"); 
					  }	 
		  });
}

function set_kyc_list(data)
{
     var kyc = data;
    var oTable = $('#kyc_list').DataTable();
    oTable.clear().draw();
    //console.log(kyc);
  
        oTable = $('#kyc_list').dataTable({
                "bDestroy": true,
                "bInfo": true,
                "bFilter": true,
                "bSort": true,				                
                "dom": 'T<"clear">lfrtip',				                
                "tableTools": { "aButtons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },
                "aaData": kyc,
                "order": [[ 0, "desc" ]],
                "aoColumns": [ { "mDataProp": function ( row, type, val, meta ){ 
		                	chekbox='<input type="checkbox" class="kyc_id" name="kyc_id[]" value="'+row.id_kyc+'"/> <input type="hidden" class="cus" value="'+row.cus+'"/>' 
		                	return chekbox+" "+row.id_kyc;
		                /*	if(row.status=='0' || row.status=='1')
		                {
		                	return chekbox+" "+row.id_kyc;
		                    
		                }
		                	else{
		                	    	return row.id_kyc;
		                	}*/
		                }}, 
                                { "mDataProp": "id_customer" },
    							{ "mDataProp": "kyc_type" },
                                { "mDataProp": "number" },
    							{ "mDataProp": "name" },
    							{ "mDataProp": "bank_ifsc" },
    							{ "mDataProp": "bank_branch" },
    						    { "mDataProp": "status" },
    						    { "mDataProp": "dob" },
    							{ "mDataProp": "emp_verified_by" },
    							{ "mDataProp": "verification_type" },
    							{ "mDataProp": "last_update" },
    							{ "mDataProp": "date_add" }
    							
    						 ] 
            });
    				        
    
    $("div.overlay").css("display", "none");    
				            			  	 	
} 

function update_kyc_status(kyc_data="",kyc_type="")
{
	
	my_Date = new Date();
	$("div.overlay").css("display", "block"); 
	$.ajax({
			 url:base_url+ "index.php/admin_reports/update_kyc?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),
			 data:  {kyc_data,kyc_type},
			 type:"POST",
			 async:false,
			 	  success:function(data){
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

 //Kyc Approval Data status filter with date picker//hh
 
 //Plan 2 and Plan 3 Scheme Enquiry Data with date picker//hh 
  function get_sch_enq_list(from_date="",to_date="")
{
	my_Date = new Date();
	postData = (from_date !='' && to_date !=''? {'from_date':from_date,'to_date':to_date}: ''),
	 $("div.overlay").css("display", "block"); 
	$.ajax({
			  url:base_url+ "index.php/admin_reports/schenquiry_list?nocache=" + my_Date.getUTCSeconds(),
			 data: (postData),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			 	$('#total_sch_enq').text(data.length);
			   			set_sch_enq_list(data);
			   			 $("div.overlay").css("display", "none"); 
					  },
					  error:function(error)  
					  {
						 $("div.overlay").css("display", "none"); 
					  }	 
		  });
}

function set_sch_enq_list(data)
{
   
    var oTable = $('#sch_enquiry_list').DataTable();
    oTable.clear().draw();
    //console.log(data);
  
        oTable = $('#sch_enquiry_list').dataTable({
                "bDestroy": true,
                "bInfo": true,
                "bFilter": true,
                "bSort": true,				                
                "dom": 'T<"clear">lfrtip',				                
                "tableTools": { "aButtons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },
                "aaData": data,
                "order": [[ 0, "desc" ]],
                "aoColumns": [ { "mDataProp": function ( row, type, val, meta ){ 
		                	chekbox='<input type="checkbox" class="id_sch_enquiry" name="id_sch_enquiry[]" value="'+row.id_sch_enquiry+'"/> ' 
		                	return chekbox+" "+row.id_sch_enquiry;
		               
		                }}, 
                                { "mDataProp":function (row,type,val,meta){
											var title = 	row.title!=null?row.title+". ":'';
											return title+""+row.id_customer;

								} },
                                { "mDataProp": "mobile" },
    							{ "mDataProp": "intresred_amt" },
                                { "mDataProp": "message" },
    							{ "mDataProp": "intrested_wgt" },
    							{ "mDataProp": "enquiry_date" }
    							
    						 ] 
            });
    				        
    
    $("div.overlay").css("display", "none");    
				            			  	 	
} 
  
  //Plan 2 and Plan 3 Scheme Enquiry Data with date picker//HH
  
  //Purchase Payment - Akshaya Thiruthiyai Spl updt//
  function get_purchase_payment(from_date="",to_date="",id_customer="")
 {
     my_Date = new Date();
     $.ajax({
        url: base_url+'index.php/admin_reports/ajax_get_purchase_payment/?nocache=' + my_Date.getUTCSeconds(),             
        dataType: "json", 
        method: "POST",
        data :{'from_date':from_date,'to_date':to_date,'id_purch_customer':id_customer},
        success: function (data) { 
		    set_purchase_payment(data);
        }
     });
 }
 
 function set_purchase_payment(data)
{

	var oTable = $('#purchase_history').DataTable();
	oTable.clear().draw();
	oTable = $('#purchase_history').dataTable({
    "bDestroy": true,
    "bInfo": true,
    "bFilter": true,
    "bSort": true,				                
    "dom": 'lBfrtip',				                
    "buttons" : ['excel','print'],
    "aaData": data,
    "order": [[ 0, "desc" ]],
    "pageLength":25,
	"aoColumns": [
	{ "mDataProp": "id_purch_payment" },    
	{ "mDataProp": "name" },				                
	{ "mDataProp": "mobile" },				                
	{ "mDataProp": "type" },
	{ "mDataProp": "delivery_preference" },
	//{ "mDataProp": "id_branch" },
	{ "mDataProp": "payment_amount" },				                
	{ "mDataProp": "metal_weight" },	
	{ "mDataProp": "id_transaction" },	
	{ "mDataProp": "payment_status" },	
	//{ "mDataProp": "is_delivered" },
	{ "mDataProp": "date_add" },
	                	
					           	{ "mDataProp": function ( row, type, val, meta ){
					                	    	if( row.is_delivered== 1){
												  //  action_content = ((row.status < 2)?'<li><a href="#" class="btn-edit" onClick="update_status('+row.id_purch_payment+')"><i class="fa fa-edit" ></i> Deliver</a></li>':'');
                                            	 return ' Delivered ';
					                	    	}
					                	    	
					                	    	if(row.payment_status== 'Success' || row.payment_status== 'Awaiting'){
												  //  action_content = ((row.status < 2)?'<li><a href="#" class="btn-edit" onClick="update_status('+row.id_purch_payment+')"><i class="fa fa-edit" ></i> Deliver</a></li>':'');
                                            	  return '<button type="button" onClick="otp_model(' + row.mobile + ',' + row.id_purch_payment + ',' + row.id_purch_customer + ')">Deliver</button>';
					                	    	}
					                	    	
					                	    	
					                	    	/* else if(row.payment_status== 'Success' && row.payment_status== 'Awaiting' && row.is_delivered== 1) {
					                	    	    return ' Delivered '
					                	           }*/
					                	    	    else{
									    	    return ' - '
									    		}
									    	
												}},
	] 
	});	
}

//Need otp when purchase the jewel for AT special //HH

    function otp_model(id,id_purch_payment,id_purch_customer){
        $("#otp_model").modal({
                backdrop: 'static',
                  keyboard: false
                     });
        
    	//$('#otp_model').modal('show', {backdrop: 'static'});
    	$("#id_purch_customer").val(id_purch_customer); 
    	$("#id_purch_payment").val(id_purch_payment); 
    	$("#mobile").val(id); 
    }
  
    $('#close').on('click',function(){
        
    clearTimeout(timer); //clears the previous timer.
        
    $(".otp_block").css("display", 'none');
    
    $(".close_actionBtns").css("display", 'none');
    
    $("#otp_status").css("display", 'none');
    
    $("#otp").val('');
    
    $("#closed").val('');
    
    $("#send_otp").attr("disabled", false);
    
    var btn = $("#send_otp");
    
    btn.prop('disabled', false);
    
    btn.prop('value', 'Send OTP');
    
    });
  
  
    $('#verify_otp').on('click',function(){
    $("#verify_otp").attr("disabled", true);
    var post_data=$('#otp_model').serialize();
    verify_otp(post_data);
    });
    
    $('#verify_otp').on('click',function(){
    $(".close_actionBtns").css("display", 'none');
    $("#closed").prop("required", true);
    $("#closed").css("display", '');
    $("#verify_issue").css("display", '');
    $("#verify_issue").prop('disabled', false);
    });    
    
	
var fewSeconds = 30;
	$('#send_otp').click(function(event) {

		var btn = $(this);

   		btn.prop('disabled', true);

   		timer=setTimeout(function(){

        btn.prop('disabled', false);

        btn.prop('value', 'Resend OTP');

    	}, fewSeconds*1000);

      	close_purch_otp();

    	

    });
    
    $('#otp').on('keyup',function(){
       
        if(this.value.length==6)
        {        
              $('#verify_otp').prop('disabled',false);
               
        }
        else
        {
            $('#verify_otp').prop('disabled',true);
           // alert('Please fill the 6 digit Otp');
          
        }
    })
    
    
    function close_purch_otp(post_data)
     {
	my_Date = new Date();
	 $("div.overlay").css("display", "block"); 

	 $("#send_otp").attr("disabled", true); 
		 var mobile = $("#mobile").val();
	 var id_purch_payment = $("#id_purch_payment").val();
	 var id_purch_customer = $("#id_purch_customer").val();
	 
	  	$.ajax({
		url:base_url+ "index.php/admin_reports/generateotp?nocache=" + my_Date.getUTCSeconds(),
		data :  {'mobile':mobile,'id_purch_payment':id_purch_payment,'id_purch_customer':id_purch_customer}, 	
		 type : "POST",
		dataType: 'json',
		 success : function(data) 
		 {
		 	 if(data.result==3)
		 	 {
	
		  		$('#otp_model').modal({
    				backdrop: 'static',
    				keyboard: false
				});
				 {		
				     

						  		$('#otp_status').fadeIn();

						  		$("#otp_status").text("OTP Sent Successfully, Kindly verify it by entering in the above Text box.");

						  		$("#otp_status").css("color", 'green');
						  		$(".otp_block").css("display", 'block');

						  		$("div.overlay").css("display", "none");

						  		$('#otp_status').delay(1000).fadeOut(200);

								

						  }
				$("div.overlay").css("display", "none"); 
				
		 	 }
		 	 
		 }
		});
	  
	 
}    
      function verify_otp(post_data)
	{	
		
	var post_otp=$('#otp').val();
	 var id_purch_payment = $("#id_purch_payment").val();
	    
	    $.ajax({
	url:base_url+ "index.php/admin_reports/verify_otp",
	data: {'otp':post_otp,'id_purch_payment':id_purch_payment},
	type:"POST",
	dataType:"JSON",
	success:function(data)
	{
		//console.log(data);
		if(data.result==1)
		{
			
							  		//$("#send_otp").hide();
                                	$(".close_actionBtns").css("display", 'block');
							  		$('#otp_status').fadeIn();

									$("#otp_status").text("OTP verified successfully, Kindly proceed with delivery.");

									$("#otp_status").css("color", 'green');

									$("div.overlay").css("display", "none");
									
									$('#otp_status').delay(1000).fadeOut(200);
									
									
		}
		else
		{
			                         $("#verify_otp").prop('disabled',false); 

									
									$('#otp_status').fadeIn();

									$("#otp_status").text("Incorrect OTP, Kindly enter the correct one.");

									$("#otp_status").css("color", 'red');

									$("div.overlay").css("display", "none");

									$('#otp_status').delay(10000).fadeOut(500);
			
		}
		
	}
																
																
		});

	}
	
    $('#verify_issue').click(function(event) {
        $(this).prop('disabled','disabled');
        location.reload();
        var id_purch_payment = $("#id_purch_payment").val();
        var delivery_remark = $("#closed").val();
        var post_otp=$('#otp').val();
        $.ajax({
        url:base_url+ "index.php/admin_reports/purch_delivered",
        data: {'otp':post_otp,'delivery_remark':delivery_remark,'id_purch_payment':id_purch_payment},
        type:"POST",
        dataType:"JSON",
        success:function(data)
        {
        if(data.result==5)
        {
        
        $("div.overlay").css("display", "none");
        }
        }
        });
    });

  //Purchase Payment - Akshaya Thiruthiyai Spl updt//
    
    // Payment Online/offline collection // HH
  function generate_online_offline_collection(selected_date ="",added_by="")
{
	
		my_Date = new Date();
			var date_type=$('#date_Select').find(":selected").val();
		 $("div.overlay").css("display", "block");
			$.ajax({
			  url:base_url+"index.php/reports/payment_online_offline_collec_list",
			  data: {'date':selected_date,'date_type':date_type,'added_by':added_by},  
			  dataType:"JSON",
			  type:"POST",
			 success:function(payment){
			var data=payment.account;
		
			var gst_number=payment.gst_number;
			
			var gstsetting= (typeof payment.account == 'undefined' ? '' :payment.account[0].gst_setting);
			
			if(gstsetting==1)
				{
				var gstno="<span style='font-size:13pt; float:right;'> GST Number - "+gst_number+"</span>";
				}else{
					var gstno='';
				}
				 var select_date="<b><span style='font-size:15pt;'>All Scheme Report As on Date   </span></b></br>"+"<span style=font-size:13pt;>Transaction Details &nbsp;&nbsp;Selected Date&nbsp;&nbsp;:&nbsp;"+selected_date+"</span>"+gstno;
				 
						var oTable = $('#on_off_paycollec_report').DataTable();
						oTable.clear().draw();
						 if (data!= null && data.length > 0)
						  {
							  
							if(gstsetting ==1)
							{  
							  
							oTable = $('#on_off_paycollec_report').dataTable({
									
										"bDestroy": true,
										"bInfo": false,
										"bFilter": false,								
										"scrollX":'100%',
										"bAutoWidth": false,
										"bSort": true,
										"dom": 'Bfrtip',
										"lengthMenu":[
										 [ 10, 25, 50, -1 ],
										 [ '10 rows', '25 rows', '50 rows', 'Show all' ]
										],
										"buttons": [
										{
										   extend: 'print',
										   footer: true,
										   title:select_date,
										   customize: function ( win ) {
												$(win.document.body).find( 'table' )
													.addClass( 'compact' )
													.css( 'font-size', 'inherit' );
											},
										 },
										 {
											extend:'excel' 
										 },
										 {
											extend:'pageLength',
											customize: function ( win ) {
											$(win.document.body).find( 'table' )
												.addClass( 'compact' )
												.css( 'font-size', 'inherit' );
													},
										 }
										 
										 
										 
										 
										 
										 /* ,{
										   extend: 'excelHtml5',
										   footer: true,
										 } */],  			
										"aaData": data,								
										"aoColumns": [								
											{ "mDataProp": "date_payment" },	
											{ "mDataProp": "code" },
											{ "mDataProp": "receipt" },
											{ "mDataProp": function ( row, type, val, meta ){
													 return parseFloat(row.payment_amount).toFixed(2);
												}},
												{ "mDataProp": function ( row, type, val, meta ){
													return parseFloat(row.sgst).toFixed(3);
												}},
												{ "mDataProp": function ( row, type, val, meta ){
													return parseFloat(row.cgst).toFixed(3);
												}},
												{ "mDataProp": function ( row, type, val, meta ){
													return parseFloat(parseFloat(row.cgst)+parseFloat(row.sgst)).toFixed(2);
												}},	
												{ "mDataProp": function ( row, type, val, meta ){
													return parseFloat(parseFloat(row.payment_amount)+parseFloat(row.sgst)+parseFloat(row.cgst)).toFixed(2);
												}},
											],				
				"footerCallback": function( row, data, start, end, display ) 
				{
					 var cshtotal=0;
					 var cardtotal=0;
					 var chqtotal=0;
					 var ecstotal=0;
					 var nbtotal=0;
					 var fptotal=0;
							 
						 if(data.length>0){
								 var api = this.api(), data;
								 
								for( var i=0; i<=data.length-1;i++){
									
									if(data[i]['payment_mode'] =='CSH'){
										cshtotal +=  parseFloat(data[i]['payment_amount']);}
									 if(data[i]['payment_mode'] =='Card'){
										cardtotal +=  parseFloat(data[i]['payment_amount']);}
									if(data[i]['payment_mode'] =='CHQ'){
										chqtotal +=  parseFloat(data[i]['payment_amount']);}
									if(data[i]['payment_mode'] =='ECS'){
										ecstotal +=  parseFloat(data[i]['payment_amount']);}
									 if(data[i]['payment_mode'] =='NB'){
										nbtotal +=  parseFloat(data[i]['payment_amount']);}
									if(data[i]['payment_mode'] =='FP'){					
										fptotal +=  parseFloat(data[i]['payment_amount']);}	

								//console.log(data[i]['payment_mode']);			
							// total
							
								var intVal = function ( i ) {
									   return typeof i === 'string' ?
										   i.replace(/[\$,]/g, '')*1 :
										   typeof i === 'number' ?
											   i : 0;
								   };	
							//Total over this page
							
							$(api.column(0).footer() ).html('Total');	
							
							// recepit Total over this page
							rec_tot = api
								.column(2)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
							$( api.column(2).footer() ).html(rec_tot);			
								
								
							// pay_amt tot	
								
								pay_amt = api
								.column(3)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								
								$(api.column(3).footer()).html(parseFloat(pay_amt).toFixed(2));
								
								// sgst_amt tot	
								
								sgst_amt = api
								.column(4)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								
							$(api.column(4).footer()).html(parseFloat(sgst_amt).toFixed(3));	
								
							// cgst_amt tot	
								
								cgst_amt = api
								.column(5)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
							$(api.column(5).footer()).html(parseFloat(cgst_amt).toFixed(3));
							
							
								// tgst_amt tot	
							
							$(api.column(6).footer()).html(parseFloat(parseFloat(sgst_amt)+parseFloat(cgst_amt)).toFixed(2));
							
							
							total = api
								.column(7)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
							$(api.column(7).footer()).html(parseFloat(total).toFixed(2));	
								
			 $('tr:eq(1) td:eq(2)', api.table().footer()).html('Description');
							
			//cash
			$('tr:eq(2) td:eq(1)', api.table().footer()).html('Cash  ');						
			$('tr:eq(2) td:eq(3)', api.table().footer()).html(parseFloat(cshtotal).toFixed(2));	
			
			//dc and cc card
			$('tr:eq(3) td:eq(1)', api.table().footer()).html('Card');	
			$('tr:eq(3) td:eq(3)', api.table().footer()).html(parseFloat(cardtotal).toFixed(2));
			
			//Ecs
			$('tr:eq(4) td:eq(1)', api.table().footer()).html('Ecs');	
			$('tr:eq(4) td:eq(3)', api.table().footer()).html(parseFloat(ecstotal).toFixed(2));
			
			//net baking
			$('tr:eq(5) td:eq(1)', api.table().footer()).html('Net Banking  ');	
			$('tr:eq(5) td:eq(3)', api.table().footer()).html(parseFloat(nbtotal).toFixed(2));
			
			//fb
			$('tr:eq(6) td:eq(1)', api.table().footer()).html('Free payment ');
			$('tr:eq(6) td:eq(3)', api.table().footer()).html(parseFloat(fptotal).toFixed(2));
			
			//Chq
			$('tr:eq(7) td:eq(1)', api.table().footer()).html('Chq ');
			$('tr:eq(7) td:eq(3)', api.table().footer()).html(parseFloat(chqtotal).toFixed(2));
			
			//total
			$('tr:eq(8) td:eq(1)', api.table().footer()).html('Total');	

			
			$('tr:eq(8) td:eq(3)', api.table().footer()).html(parseFloat(parseFloat(cshtotal)+parseFloat(cardtotal)+parseFloat(ecstotal)+parseFloat(nbtotal)+parseFloat(fptotal)+parseFloat(chqtotal)).toFixed(2));
					} 
				}else{
					 var api = this.api(), data;
					 $(api.column(0).footer()).html('');
					 $(api.column(2).footer()).html('');
					 $(api.column(3).footer()).html('');
					 $(api.column(4).footer()).html('');
					 $(api.column(5).footer()).html('');
					 $(api.column(6).footer()).html('');
					 $(api.column(7).footer()).html('');
					 $('tr:eq(2) td:eq(3)', api.table().footer()).html('');
					 $('tr:eq(3) td:eq(3)', api.table().footer()).html('');
					 $('tr:eq(4) td:eq(3)', api.table().footer()).html('');
					 $('tr:eq(5) td:eq(3)', api.table().footer()).html('');
					 $('tr:eq(6) td:eq(3)', api.table().footer()).html('');
					 $('tr:eq(7) td:eq(3)', api.table().footer()).html('');
					 $('tr:eq(8) td:eq(3)', api.table().footer()).html('');
					 //Text CLEAR
					 $('tr:eq(1) td:eq(2)', api.table().footer()).html('');
					 $('tr:eq(2) td:eq(1)', api.table().footer()).html('');
					 $('tr:eq(3) td:eq(1)', api.table().footer()).html('');
					 $('tr:eq(4) td:eq(1)', api.table().footer()).html('');
					 $('tr:eq(5) td:eq(1)', api.table().footer()).html('');
					 $('tr:eq(6) td:eq(1)', api.table().footer()).html('');
					 $('tr:eq(7) td:eq(1)', api.table().footer()).html('');
					 $('tr:eq(8) td:eq(1)', api.table().footer()).html('');
								 
				     }
				}
			  });
		}
	  else{
		  
					oTable = $('#on_off_paycollec_report').dataTable({
									
										"bDestroy": true,
										"bInfo": false,
										"bFilter": false,						
										"scrollX":'100%',
										"bAutoWidth": false,
										"bSort": true,
										"dom": 'Bfrtip',
										"lengthMenu":[
										 [ 10, 25, 50, -1 ],
										 [ '10 rows', '25 rows', '50 rows', 'Show all' ]
										],
										"buttons": [
										{
										   extend: 'print',
										   footer: true,
										   title:select_date,
										   customize: function ( win ) {
												$(win.document.body).find( 'table' )
													.addClass( 'compact' )
													.css( 'font-size', 'inherit' );
											},
										 },
										  {
											extend:'excel' 
										 },
										 {
											extend:'pageLength',
											customize: function ( win ) {
											$(win.document.body).find( 'table' )
												.addClass( 'compact' )
												.css( 'font-size', 'inherit' );
													},
										 }
										
										],  			
										"aaData": data,								
										"aoColumns": [								
											{ "mDataProp": "payment_mode" },
											{ "mDataProp": "payment_type" },
										
											{ "mDataProp": function ( row, type, val, meta ){
													 return parseFloat(row.payment_amount).toFixed(2);
											}},
												{ "mDataProp": function ( row, type, val, meta ){
													return parseFloat(row.payment_amount).toFixed(2);
												}},
											],				
				"footerCallback": function( row, data, start, end, display ) 
				{
					 var cshtotal=0;
					 var cardtotal=0;
					 var upitotal =0;
					 var chqtotal=0;
					 var ecstotal=0;
					 var nbtotal=0;
					 var fptotal=0;
							 
						 if(data.length>0){
								 var api = this.api(), data;
								 
								for( var i=0; i<=data.length-1;i++){
									
									if(data[i]['payment_mode'] =='CSH'){
										cshtotal +=  parseFloat(data[i]['payment_amount']);}
									 if(data[i]['payment_mode'] =='Card'){
										cardtotal +=  parseFloat(data[i]['payment_amount']);}
										if(data[i]['payment_mode'] =='UPI'){
										upitotal +=  parseFloat(data[i]['payment_amount']);}
									if(data[i]['payment_mode'] =='CHQ'){
										chqtotal +=  parseFloat(data[i]['payment_amount']);}
									if(data[i]['payment_mode'] =='ECS'){
										ecstotal +=  parseFloat(data[i]['payment_amount']);}
									 if(data[i]['payment_mode'] =='NB'){
										nbtotal +=  parseFloat(data[i]['payment_amount']);}
									if(data[i]['payment_mode'] =='FP'){					
										fptotal +=  parseFloat(data[i]['payment_amount']);}	

								//console.log(data[i]['payment_mode']);			
							// total
							
								var intVal = function ( i ) {
									   return typeof i === 'string' ?
										   i.replace(/[\$,]/g, '')*1 :
										   typeof i === 'number' ?
											   i : 0;
								   };	
							//Total over this page
							
							$(api.column(0).footer() ).html('Total');	
						
							// pay_amt tot	
								
								pay_amt = api
								.column(2)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								
								$(api.column(2).footer()).html(parseFloat(pay_amt).toFixed(2));
							
							total = api
								.column(3)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
							$(api.column(3).footer()).html(parseFloat(total).toFixed(2));	
		
					} 
				}else{
					 var api = this.api(), data;
					 $(api.column(0).footer()).html('');
					 $(api.column(2).footer()).html('');
					 $(api.column(3).footer()).html('');
					 //$(api.column(4).footer()).html('');	
			
								 
				     }
				}
			  });
		  
		  
		  
	  }
			  
			  
			  
			}
				$("div.overlay").css("display", "none");
				},
			  error:function(error)  
			  {
				 $("div.overlay").css("display", "none"); 
			  }	 
	  });
	
}

   // Payment Online/offline collection //
   
     //Autodebit subscription Status Report//HH
  function get_autodebit_subscription(from_date="",to_date="",id_customer="")
 {
     my_Date = new Date();
     $.ajax({
        url: base_url+'index.php/admin_reports/ajax_get_autodebit_subscription/?nocache=' + my_Date.getUTCSeconds(),             
        dataType: "json", 
        method: "POST",
        data :{'from_date':from_date,'to_date':to_date,'id_customer':id_customer,'id_branch':$("#branch_select").val()},
        success: function (data) { 
		    set_autodebit_subscription(data);
        }
     });
 }
 
 function set_autodebit_subscription(data)
{

	var oTable = $('#autodebit_subscription').DataTable();
	oTable.clear().draw();
	oTable = $('#autodebit_subscription').dataTable({
        "bDestroy": true,
        "bInfo": true,
        "bFilter": true,
        "bSort": true,				                
        "dom": 'lBfrtip',				                
        "buttons" : ['excel','print'],
        "aaData": data,
        "order": [[ 0, "desc" ]],
        "pageLength":25,
    	"aoColumns": [
    	    
    	   { "mDataProp": function ( row, type, val, meta ) {
					                	var url = base_url+'index.php/reports/payment/account/'+row.id_scheme_account;
					                	action = '<a href="'+url+'" target="_blank">'+row.id_scheme_account+'</a>';
					                	return action;
					                	}
					                },
									
    	    { "mDataProp": "branch_name" },
        	{ "mDataProp": "name" },				                
        	{ "mDataProp": "mobile" },				                
        	{ "mDataProp": "account_name" },
        	{ "mDataProp": "scheme_acc_number" },
        	{ "mDataProp": "auto_debit_status" },				                
        	{ "mDataProp": "date_upd" },	
     
	    ] 
	});	
}

 //Autodebit subscription Status Report//
 
 

$('#scheme_wise_search').on('click',function(){
    payment_schemewise();
});


function get_schemeclassifyname()
{
	$(".overlay").css('display','block');
	$.ajax({
		type: 'GET',
		url: base_url+'index.php/get/schemeclassify_list',
		dataType:'json',
		success:function(data){
		 var schemeclassify_val =  $('#id_classifications').val();
		   $.each(data, function (key, item) {
			   		$('#classify_select').append(
						$("<option></option>")
						.attr("value", item.id_classification)						  
						  .text(item.classification_name )
					);			   				
			});
			$("#classify_select").select2({
			    placeholder: "Select Scheme Classify name",
			    allowClear: true
			});
			 $("#classify_select").select2("val",(schemeclassify_val!='' && schemeclassify_val>0?schemeclassify_val:''));
			 $(".overlay").css("display", "none");	
		}
	});
}
 
 
$('#schemw_wise_collection').on('click',function(){
    get_collection_report();
});


function get_collection_report()
{
    $("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+"index.php/admin_reports/scheme_daily_collection_details?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'from_date':$('#rpt_payments1').html(),'to_date' :$('#rpt_payments2').html(),'id_branch':$('#branch_select').val()}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
		    
        			$("#collection_report > tbody > tr").remove();  
        	 		$('#collection_report').dataTable().fnClearTable();
            		$('#collection_report').dataTable().fnDestroy();
            		trHTML = ''; 
            		
            		opening_blc_amt=0;
            		opening_bonus_amt=0;
            		opening_blc_wgt=0;
            		
            		today_collection_amt=0;
            		today_bonus_amt=0;
            		today_collection_wgt=0;
            		
            		today_closed_amount=0;
            		today_bonus_deduction=0;
            		today_closed_weight=0;
            		
            		closing_balance_amt=0;
            		closing_balance_wgt=0;
            		closing_bonus_amt=0;
            		var i=1;
            		
            		$.each(data,function(key,items){

            		  opening_blc_amt+=parseFloat(items.opening_blc_amt);
            		  opening_bonus_amt+=parseFloat(items.opening_bonus_amt);
            		  opening_blc_wgt+=parseFloat(items.opening_blc_wgt);
            		  
            		  today_collection_amt+=parseFloat(items.today_collection_amt);
            		  today_bonus_amt+=parseFloat(items.today_bonus_amt);
            		  today_collection_wgt+=parseFloat(items.today_collection_wgt);
            		  
            		  today_closed_amount+=parseFloat(items.today_closed_amount);
            		  today_bonus_deduction+=parseFloat(items.today_bonus_deduction);
            		  today_closed_weight+=parseFloat(items.today_closed_weight);
            		  
            		  closing_balance_amt+=parseFloat(items.closing_balance_amt);
            		  closing_balance_wgt+=parseFloat(items.closing_balance_wgt);
            		  closing_bonus_amt+=parseFloat(items.closing_bonus_amt);

            		    trHTML+= '<tr>'+
            		                '<td>'+i+'</td>'+
            		                '<td>'+items.scheme_name+'</td>'+
            		                '<td>'+items.opening_blc_amt+'</td>'+
            		                '<td>'+items.opening_bonus_amt+'</td>'+
            		                '<td>'+items.opening_blc_wgt+'</td>'+
            		                '<td>'+items.today_collection_amt+'</td>'+
            		                '<td>'+items.today_bonus_amt+'</td>'+
            		                '<td>'+items.today_collection_wgt+'</td>'+
            		                '<td>'+items.today_closed_amount+'</td>'+
            		                '<td>'+items.today_bonus_deduction+'</td>'+
            		                '<td>'+items.today_closed_weight+'</td>'+
            		                '<td>'+items.closing_balance_amt+'</td>'+
            		                '<td>'+items.closing_bonus_amt+'</td>'+
            		                '<td>'+items.closing_balance_wgt+'</td>'+
            		             '</tr>';
            		    i++;
            		});
            		trHTML+='<tr>'+
            		                '<td></td>'+
            		                '<td></td>'+
            		                '<td></td>'+
            		                '<td></td>'+
            		                '<td></td>'+
            		                '<td></td>'+
            		                '<td style="text-align:left;" colspan="2"><strong>Opening Blc Amount</strong></td>'+
            		                '<td style="text-align:right;">'+parseFloat(opening_blc_amt).toFixed(2)+'</td>'+
            		                '<td></td>'+
            		                '<td></td>'+
            		                '<td></td>'+
            		                '<td></td>'+
            		                '<td></td>'+
            		                '<td></td>'+
            		             '</tr>';
            		trHTML+='<tr>'+
            		                '<td></td>'+
            		                '<td></td>'+
            		                '<td></td>'+
            		                '<td></td>'+
            		                '<td></td>'+
            		                '<td></td>'+
            		                '<td style="text-align:left;" colspan="2"><strong>Opening Bonus Amount</strong></td>'+
            		                '<td style="text-align:right;">'+parseFloat(opening_bonus_amt).toFixed(2)+'</td>'+
            		                '<td></td>'+
            		                '<td></td>'+
            		                '<td></td>'+
            		                '<td></td>'+
            		                '<td></td>'+
            		                '<td></td>'+
            		             '</tr>';
        		 	trHTML+='<tr>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td style="text-align:left;" colspan="2"><strong>Opening Blc Weight</strong></td>'+
        		                '<td style="text-align:right;">'+parseFloat(opening_blc_wgt).toFixed(2)+'</td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		             '</tr>';
        		   trHTML+='<tr>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td style="text-align:left;" colspan="2"><strong>Received Amount</strong></td>'+
        		                '<td style="text-align:right;">'+parseFloat(today_collection_amt).toFixed(2)+'</td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		             '</tr>';
        		     trHTML+='<tr>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td style="text-align:left;" colspan="2"><strong>Bonus Allocated</strong></td>'+
        		                '<td style="text-align:right;">'+parseFloat(today_bonus_amt).toFixed(2)+'</td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		             '</tr>';
        		   trHTML+='<tr>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td style="text-align:left;" colspan="2"><strong>Received Weight</strong></td>'+
        		                '<td style="text-align:right;">'+parseFloat(today_collection_wgt).toFixed(3)+'</td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		             '</tr>';
        		  trHTML+='<tr>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td style="text-align:left;" colspan="2"><strong>Closed Amount</strong></td>'+
        		                '<td style="text-align:right;">'+parseFloat(today_closed_amount).toFixed(2)+'</td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		             '</tr>';
        		  trHTML+='<tr>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td style="text-align:left;" colspan="2"><strong>Bonus Deduction</strong></td>'+
        		                '<td style="text-align:right;">'+parseFloat(today_bonus_deduction).toFixed(2)+'</td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		             '</tr>';
        		   trHTML+='<tr>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td style="text-align:left;" colspan="2"><strong>Closed Weight</strong></td>'+
        		                '<td style="text-align:right;">'+parseFloat(today_closed_weight).toFixed(3)+'</td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		             '</tr>';
        		             
        		  trHTML+='<tr>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td style="text-align:left;" colspan="2"><strong>Closing Blc Amount</strong></td>'+
        		                '<td style="text-align:right;">'+parseFloat(closing_balance_amt).toFixed(2)+'</td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		             '</tr>';
        		  trHTML+='<tr>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td style="text-align:left;" colspan="2"><strong>Closing Blc Bonus</strong></td>'+
        		                '<td style="text-align:right;">'+parseFloat(closing_bonus_amt).toFixed(2)+'</td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		             '</tr>';
        		  trHTML+='<tr>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td style="text-align:left;" colspan="2"><strong>Closing Blc Weight</strong></td>'+
        		                '<td style="text-align:right;">'+parseFloat(closing_balance_wgt).toFixed(3)+'</td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		                '<td></td>'+
        		             '</tr>';
            	    $('#collection_report > tbody').html(trHTML);
            	    
            	       if ( ! $.fn.DataTable.isDataTable( '#collection_report' ) ) 
	                 { 
	                     oTable = $('#collection_report').dataTable({ 
						                "bSort": false, 
						                "bInfo": true, 
						                "scrollX":'100%',  
						                "dom": 'lBfrtip',
                                      
						                "lengthMenu": [[-1, 25, 50, 100, 250], ["All" ,25, 50, 100, 250]],
						                "buttons": [
													{
													   extend: 'print',
													   footer: true,
													   title: 'Scheme Wise Collection Report',
													   orientation: 'landscape',
													   customize: function ( win ) {
                            							    $(win.document.body).find('table')
                                                            .addClass('compact');
                                                    
                            								$(win.document.body).find( 'table' )
                            									.addClass('compact')
                            									.css('font-size','10px')
                            									.css('font-family','sans-serif');
                            								
                            							},
													 },
													 {
														extend:'excel',
														footer: true,
													    title: 'Scheme Wis Collection Report',
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




//closing branch//
 function get_cls_branchname(){	
		//alert('sa.js');
         	//$(".overlay").css('display','block');	
         	$.ajax({		
             	type: 'GET',		
             	url: base_url+'index.php/branch/branchname_list',		
             	dataType:'json',		
             	success:function(data){	
				console.log(data);
            	 	var id_branch =  $('#close_id_branch').val();		   
            	 	$.each(data.branch, function (key, item) {	
                	 	$('#close_branch_select').append(						
                	 	$("<option></option>")						
                	 	.attr("value", item.id_branch)						  						  
                	 	.text(item.name )						  					
                	 	);			   											
                 	});						
					$("#close_branch_select").select2({			    
                	 	placeholder: "Select branch name",			    
                	 	allowClear: true		    
                 	});				 
                 	    $("#close_branch_select").select2("val",(close_id_branch!='' && close_id_branch>0?close_id_branch:''));	 
						$(".overlay").css("display", "none");			
             	}	
            }); 
        }
//closing branch //
//closed A/C report with date picker, cost center based branch fillter//HH

$('#closed_acc_search').on('click',function(){
    get_closed_acc_list();
});

function get_closed_acc_list()
{
	my_Date = new Date();
	postData = {'from_date':$('#rpt_payments1').text(),'to_date':$('#rpt_payments2').text(),'id_employee':$('#emp_select').val(),'close_id_branch':($('#branch_filter').val()!='' && $('#branch_filter').val()!=undefined ? $('#branch_filter').val(): $("#close_branch_select").val())},
	 $("div.overlay").css("display", "block"); 
	$.ajax({
			 url:base_url+ "index.php/admin_reports/closedaccount_list?nocache=" + my_Date.getUTCSeconds(),
			 data: (postData),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			 	$('#total_closed_accounts').text(data.length);
			   			set_closed_acc_list(data);
			   			 $("div.overlay").css("display", "none"); 
					  },
					  error:function(error)  
					  {
						 $("div.overlay").css("display", "none"); 
					  }	 
		  });
}
//Customer Account Details

function get_customer_account_details(from_date,to_date)
{
    var company_name=$('#company_name').val();
    var title="<b><span style='font-size:15pt;margin-left:30%;'>"+company_name+"</span></b><b><span style='font-size:12pt;'></span></b></br>"+"<span style=font-size:13pt;margin-left:25%;>&nbsp;&nbsp;Customer Account Details Report</span>";
    $("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_reports/customer_account_details/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'from_date':from_date,'to_date':to_date,'id_branch':$('#branch_select').val(),'id_scheme':$('#scheme_select').val(),}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data)
			 {
    				var oTable = $('#acc_list').DataTable();
    				oTable.clear().draw();				  
    				if (data!= null && data.length > 0)
    				{  	
    					oTable = $('#acc_list').dataTable({
    						"bDestroy": true,
    		                "bInfo": true,
    		                "bFilter": true,
    		                "scrollX":'100%',
    		                "bSort": true,
    		                "dom": 'lBfrtip',
    		                "order": [[ 0, "desc" ]],
    		                "buttons": [
    								 {
    								   extend: 'print',
    								   footer: true,
    								   title: "",
    								   messageTop: title,
    								   customize: function ( win ) {
    										$(win.document.body).find( 'table' )
    											.addClass( 'compact' )
    											.css( 'font-size', 'inherit' );
    									},
    								 },
    								 {
    									extend:'excel',
    									footer: true,
    								    title: "Chit Acccount Details",
    								  }
    								 ],
    						"aaData": data,
    						"aoColumns": [	
    						                { "mDataProp": function ( row, type, val, meta )
    										{ 
    										  var url = base_url+'index.php/admin_reports/scheme_account_report/'+row.id_scheme_account;
    										  return '<a href='+url+' target="_blank">'+row.id_scheme_account+'</a>';
                    		                }},
    									
    										{ "mDataProp": "name" },
    										{ "mDataProp": "mobile" },
    										{ "mDataProp": "code" },
    										{ "mDataProp": "scheme_acc_number" },
    										{ "mDataProp": "tot_acc" },
    										{ "mDataProp": function ( row, type, val, meta )
                                            {
                    		                if(row.active_acc>0)
											{
											    return '<span class="badge bg-green">'+row.active_acc+'</span>';
											}else{
											    return '<span class="badge bg-red">'+row.active_acc+'</span>';
											}
                                            }},
    										
    										{ "mDataProp": "start_date" },
    										{ "mDataProp": function ( row, type, val, meta )
    										{ 
    										  return row.paid_installments+'/'+row.total_installments;
                    		                }},
                    		               	{ "mDataProp": function ( row, type, val, meta )
    										{ 
    										  var pending_due=row.paid_installments-row.total_installments;
    										  if(pending_due<0)
    										  {
    										      return pending_due*(-1);
    										  }else{
    										       return pending_due;
    										  }
                    		                }},
                    		                { "mDataProp": "last_paid_date" },
                    		                { "mDataProp": "month_ago" },
                    		                { "mDataProp": "closing_date" },
                                            { "mDataProp": function ( row, type, val, meta )
                                            {
                    		                if(row.is_closed==1)
											{
											    return '<span class="badge bg-orange">Closed</span>';
											}
											else if(row.month_ago>3)
											{
											    return '<span class="badge bg-red">Inactive</span>';
											}else{
											    return '<span class="badge bg-green">Live</span>';
											}
                                            }},
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

//Customer Account Details
function set_closed_acc_list(data)
{
    console.log($('#rpt_payments1').text());
    console.log($('#rpt_payments2').text());
    var oTable = $('#closed_list').DataTable();
    oTable.clear().draw();
    //console.log(data);
        oTable = $('#closed_list').dataTable({
                "bDestroy": true,
                "bInfo": true,
                "bFilter": true,
                "bSort": true,	
                "lengthMenu": [[-1, 25, 50, 100, 250], ["All" ,25, 50, 100, 250]],
                "dom": 'lBfrtip',
                "buttons": [
						{
							extend: 'print',
							footer: true,
							title: 'Closed Account Report '+$('#rpt_payments1').text()+' - '+$('#rpt_payments2').text(),
							customize: function ( win ) {
							    $(win.document.body).find('table')
                                .addClass('compact');
                        
								$(win.document.body).find( 'table' )
									.addClass('compact')
									.css('font-size','10px')
									.css('font-family','sans-serif');
								
							},
						},
						{
							extend:'excel',
							footer: true,
						   	title: 'Closed Account Report '+$('#rpt_payments1').text()+' - '+$('#rpt_payments2').text(),
						}
						], 
                "aaData": data,
                "order": [[ 0, "desc" ]],
                "aoColumns": [
                                    { "mDataProp": function ( row, type, val, meta )
                                    { 
                                              var url = base_url+'/index.php/reports/payment/account/'+row.id_scheme_account;
                                              return '<a href='+url+' target="_blank">'+row.id_scheme_account+'</a>';
                                    }},
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
					                { "mDataProp": function ( row, type, val, meta )
                                    { 
                                              var url = base_url+'/index.php/admin_ret_billing/billing_invoice/'+row.bill_id;
                                              return '<a href='+url+' target="_blank">'+row.bill_no+'</a>';
                                    }},
					                { "mDataProp": "code" },
					                { "mDataProp": "start_date" },
					                { "mDataProp": "scheme_type" },
								
								
									 { "mDataProp": function ( row, type, val, meta ){
									     if(row.scheme_types!=2 && row.scheme_types!=3)
									     {
									         return row.closing_balance;
									     }else{
									         return row.closing_amount;
									     }
					                   
					                 }},
								
								
								    { "mDataProp": function ( row, type, val, meta ){
									     if(row.scheme_types==3 || row.scheme_types==2)
									     {
									         return parseFloat(parseFloat(row.closing_balance!="" && row.closing_balance!=null?row.closing_balance:0)+parseFloat(row.balance_weight)).toFixed(3);
									     }else{
									         return '0';
									     }
					                   
					                 }},
								
                                    { "mDataProp": "employee_closed" },
                                    { "mDataProp" : "closing_branch"},
					                { "mDataProp": "closing_date" },
					                
					                { "mDataProp": function ( row, type, val, meta ){
					                		return row.paid_installments+'/'+row.total_installments;
					                }},
					               /* { "mDataProp": function ( row, type, val, meta ){
					                		if(row.paid_installments!=row.total_installments)
					                		{
					                		    var bonus_deduction=parseFloat(row.paid_installments)*parseFloat(row.firstPayDisc_value);
					                		    return parseFloat(row.pay_amount)-parseFloat(bonus_deduction);
					                		}else{
					                		    return row.pay_amount;
					                		}
					                }},*/
					                
					                { "mDataProp": function ( row, type, val, meta ){
					                		return parseFloat(parseFloat(row.closing_paid_amt)+parseFloat(row.balance_amount)-parseFloat(row.closing_benefits)).toFixed(2);
					                }},
					                

					                { "mDataProp": function ( row, type, val, meta ){
					                		return row.closing_add_chgs;
					                }},
					              /*  { "mDataProp": function ( row, type, val, meta ){
					                        if(row.paid_installments==row.total_installments)
					                        {
					                            return parseFloat(parseFloat(row.firstPayDisc_value) * parseFloat(row.paid_installments)).toFixed(2);
					                        }else{
					                            return 0;
					                        }
					                		
					                }},*/
					                { "mDataProp": "closing_benefits" },
					                { "mDataProp": "pay_amount" },
					                ],
					                
                                    "footerCallback": function( row, data, start, end, display )
                                    {
                                        if(data.length>0)
                                        {
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
                                                
                                                closing_amt = api
                                                .column(9)
                                                .data()
                                                .reduce( function (a, b) {
                                                return intVal(a) + intVal(b);
                                                }, 0 );
                                                $(api.column(9).footer()).html(parseFloat(closing_amt).toFixed(2));
                                                
                                                closing_wgt = api
                                                .column(10)
                                                .data()
                                                .reduce( function (a, b) {
                                                return intVal(a) + intVal(b);
                                                }, 0 );
                                                $(api.column(10).footer()).html(parseFloat(closing_wgt).toFixed(3));
                                                
                                                cus_paid_amt = api
                                                .column(15)
                                                .data()
                                                .reduce( function (a, b) {
                                                return intVal(a) + intVal(b);
                                                }, 0 );
                                                $(api.column(15).footer()).html(parseFloat(cus_paid_amt).toFixed(3));
                                                
                                                pre_close_charges = api
                                                .column(16)
                                                .data()
                                                .reduce( function (a, b) {
                                                return intVal(a) + intVal(b);
                                                }, 0 );
                                                $(api.column(16).footer()).html(parseFloat(pre_close_charges).toFixed(3));
                                                
                                                bonus_utilized_amt = api
                                                .column(17)
                                                .data()
                                                .reduce( function (a, b) {
                                                return intVal(a) + intVal(b);
                                                }, 0 );
                                                $(api.column(17).footer()).html(parseFloat(bonus_utilized_amt).toFixed(2));

                                            } 
                                        }
                                        else
                                        {
                                            var api = this.api(), data; 
                                            $(api.column(9).footer()).html('');  
                                            $(api.column(10).footer()).html('');
                                            $(api.column(15).footer()).html('');  
                                            $(api.column(16).footer()).html('');  
                                            $(api.column(17).footer()).html('');
                                        }
                                    }
            });
    $("div.overlay").css("display", "none");    
} 
//closed A/C report with date picker, cost center based branch fillter//

//Online Payment Report

$('#online_report_search').on('click',function(){
    get_online_payment_report();
});

function get_online_payment_report()
{
	my_Date = new Date();
	$("div.overlay").css("display", "block");
	$.ajax({
        data: ( {'from_date':$('#from_date').html(),'to_date':$('#to_date').html(),'id_status_msg':$('#pay_status').val(),'id_branch':$('#branch_select').val()}),
			  url:base_url+ "index.php/admin_reports/get_online_payment_report?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),
			  dataType:"JSON",
			  type:"POST",
			  success:function(data)
			  {
				  console.log(data);
			      online_payment_report_list(data);
			   	  $("div.overlay").css("display", "none");
			  },
			  error:function(error)
			  {
				  $("div.overlay").css("display", "none");
			  }
		  });
}
function online_payment_report_list(data)
{
     $("div.overlay").css("display", "block");
	 var online_payment = data;
	 var trHtml='';
	 var total_amount=0;
	 $("#online_payment_report_list > tbody > tr").remove();  
	 $('#online_payment_report_list').dataTable().fnClearTable();
    $('#online_payment_report_list').dataTable().fnDestroy();
	 $.each(online_payment,function(branch,payment){
	      var branch_total_amount=0;
	     trHtml+='<tr style="font-weight:bold;">'
	                +'<td>'+branch+'</td>'
	                +'<td></td>'
	                +'<td></td>'
	                +'<td></td>'
	                +'<td></td>'
	                +'<td></td>'
	                +'<td></td>'
	                +'<td></td>'
	                +'<td></td>'
	                +'<td></td>'
	                +'<td></td>'
	                +'<td></td>'
	                +'<td></td>'
	                +'<td></td>'
	                +'<td></td>'
	                +'<td></td>'
	                +'<td></td>'
	             +'</tr>';
	       $.each(payment,function(key,items){
	           total_amount+=parseFloat(parseFloat(parseFloat(items.payment_amount)-parseFloat(items.discountAmt)).toFixed(2));
	           branch_total_amount+=parseFloat(parseFloat(parseFloat(items.payment_amount)-parseFloat(items.discountAmt)).toFixed(2));
	           trHtml+='<tr>'
	                   +'<td>'+items.id_payment+'</td>'
	                   +'<td>'+items.date_payment+'</td>'
	                   +'<td>'+items.name+'</td>'
	                   +'<td>'+items.account_name+'</td>'
	                   +'<td>'+items.code+'</td>'
	                   +'<td>'+items.scheme_acc_number+'</td>'
	                   +'<td>'+items.mobile+'</td>'
	                   +'<td>'+parseFloat(parseFloat(items.payment_amount)-parseFloat(items.discountAmt)).toFixed(2)+'</td>'
	                   +'<td>'+items.discountAmt+'</td>'
	                   +'<td>'+items.metal_weight+'</td>'
	                   +'<td>'+items.metal_rate+'</td>'
	                   +'<td><span class="label bg-'+items.status_color+'-active">'+items.payment_status+'</span></td>'
	                   +'<td>'+(items.paid_installments!=null ?items.paid_installments :0)+'</td>'
	                   +'<td>'+items.payment_type+'</td>'
	                   +'<td>'+(items.payment_mode!=null ? items.payment_mode:'')+'</td>'
	                   +'<td>'+items.payment_ref_number+'</td>'
	                   +'<td>'+(items.added_by==0 ? 'Admin' :(items.added_by==1 ? 'Web App' :(items.added_by==2 ? 'Mobile' : 'Collection App' )))+'</td>'
	                   +'</tr>';
	       });
	       trHtml+='<tr style="font-weight:bold;">'
	                +'<td>SUB TOTAL</td>'
	                +'<td></td>'
	                +'<td></td>'
	                +'<td></td>'
	                +'<td></td>'
	                +'<td></td>'
	                +'<td></td>'
	                +'<td>'+parseFloat(branch_total_amount).toFixed(2)+'</td>'
	                +'<td></td>'
	                +'<td></td>'
	                +'<td></td>'
	                +'<td></td>'
	                +'<td></td>'
	                +'<td></td>'
	                +'<td></td>'
	                +'<td></td>'
	                +'<td></td>'
	             +'</tr>';
	 });
	 trHtml+='<tr style="font-weight:bold;">'
	                +'<td>GRAND TOTAL</td>'
	                +'<td></td>'
	                +'<td></td>'
	                +'<td></td>'
	                +'<td></td>'
	                +'<td></td>'
	                +'<td></td>'
	                +'<td>'+parseFloat(total_amount).toFixed(2)+'</td>'
	                +'<td></td>'
	                +'<td></td>'
	                +'<td></td>'
	                +'<td></td>'
	                +'<td></td>'
	                +'<td></td>'
	                +'<td></td>'
	                +'<td></td>'
	                +'<td></td>'
	             +'</tr>';
	 
	 $('#online_payment_report_list > tbody').html(trHtml);
	 
	 if ( ! $.fn.DataTable.isDataTable( '#online_payment_report_list' ) ) 
	                 { 
	                     oTable = $('#online_payment_report_list').dataTable({ 
						                "bSort": false, 
						                "bInfo": false, 
						                "scrollX":'100%',  
						                "dom": 'lBfrtip',
                                        
						                "lengthMenu": [[-1, 25, 50, 100, 250], ["All" ,25, 50, 100, 250]],
						                "buttons": [
													{
													   extend: 'print',
													   footer: true,
													   title: 'Online Payment Report '+$('#from_date').html()+' - '+$('#to_date').html(),
													   orientation: 'landscape',
													   customize: function ( win ) {
                            							    $(win.document.body).find('table')
                                                            .addClass('compact');
                                                    
                            								$(win.document.body).find( 'table' )
                            									.addClass('compact')
                            									.css('font-size','10px')
                            									.css('font-family','sans-serif');
                            								
                            							},
													 },
													 {
														extend:'excel',
														footer: true,
													    title: 'Online Payment Report '+$('#from_date').html()+' - '+$('#to_date').html(),
													  }
													 ], 
			
							
									 });
						}
	 	$("div.overlay").css("display", "none");
	
}

//Online Payment Report

function get_payment_status()
{
    $(".overlay").css('display','block');
	$.ajax({
		type: 'GET',
		url: base_url+'index.php/admin_reports/get_payment_status',
		dataType:'json',
		success:function(data){
		 var id =  $('#pay_status').val();
		   $.each(data, function (key, item) {
			   		$('#pay_status').append(
						$("<option></option>")
						.attr("value", item.id_status_msg)						  
						  .text(item.payment_status )
					);			   				
			});
			$("#pay_status").select2({
			    placeholder: "Select Pay Status",
			    allowClear: true
			});
			 $("#pay_status").select2("val",(id!='' && id>0?id:''));
			 $(".overlay").css("display", "none");	
		}
	});
}

$('#old_metal_search').on('click',function(){
    get_old_metal_report();
});

function get_old_metal_report()
{
    var company_name=$('#company_name').val();
    var title="<b><span style='font-size:15pt;margin-left:30%;'>"+company_name+"</span></b><b><span style='font-size:12pt;'></span></b></br>"+"<span style=font-size:13pt;margin-left:25%;>&nbsp;&nbsp;Old Metal Details Report </span>"+$('#rpt_payments1').text()+' - '+$('#rpt_payments2').text();
    $("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_reports/old_metal_report/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'from_date':$('#rpt_payments1').html(),'to_date':$('#rpt_payments2').html(),'id_branch':($('#branch_select').val()!='' && $('#branch_select').val()!=undefined ? $('#branch_select').val(): $("#branch_filter").val())}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data)
			 {
			        var list=data.list;
    				var oTable = $('#old_metal_report').DataTable();
    				oTable.clear().draw();				  
    				if (list!= null && list.length > 0)
    				{  	
    					oTable = $('#old_metal_report').dataTable({
    						"bDestroy": true,
    		                "bInfo": true,
    		                "bFilter": true,
    		                "scrollX":'100%',
    		                "bSort": true,
    		                "dom": 'lBfrtip',
    		                "order": [[ 0, "desc" ]],
    		                "buttons": [
    								 {
    								   extend: 'print',
    								   footer: true,
    								   //title: 'Old Metal Details '+$('#rpt_payments1').text()+' - '+$('#rpt_payments2').text(),
    								   messageTop: title,
    								   customize: function ( win ) {
    										$(win.document.body).find( 'table' )
    											.addClass( 'compact' )
    											.css( 'font-size', 'inherit' );
    									},
    								 },
    								 {
    									extend:'excel',
    									footer: true,
    								    title: 'Old Metal Details '+$('#rpt_payments1').text()+' - '+$('#rpt_payments2').text(),
    								  }
    								 ],
    						"aaData": list,
    						"aoColumns": [	
    						               /* { "mDataProp": function ( row, type, val, meta )
    										{ 
    										  var url = base_url+'index.php/admin_reports/scheme_account_report/'+row.id_scheme_account;
    										  return '<a href='+url+' target="_blank">'+row.id_scheme_account+'</a>';
                    		                }},*/
    									
    										{ "mDataProp": "id_payment" },
    										{ "mDataProp": "payment_date" },
    										{ "mDataProp": "branch_name" },
    										{ "mDataProp": "cus_name" },
    										{ "mDataProp": function ( row, type, val, meta )
    										{ 
    										  var url = base_url+'index.php/payment/invoice/'+row.id_payment+'/'+row.id_scheme_account;
    										  return '<a href='+url+' target="_blank">'+row.acc_number+'</a>';
                    		                }},
    										{ "mDataProp": "account_name" },
    										{ "mDataProp": function ( row, type, val, meta )
    										{ 
    										  var url = base_url+'index.php/admin_ret_estimation/generate_invoice/'+row.estimation_id;
    										  return '<a href='+url+' target="_blank">'+row.esti_no+'</a>';
                    		                }},
    										{ "mDataProp": "emp_name" },
    										{ "mDataProp": "gross_wt" },
    										{ "mDataProp": "net_wt" },
    										{ "mDataProp": "old_metal_amount" },
    										{ "mDataProp": "pay_emp" },
    									
    									  ],
    									  "footerCallback": function( row, data, start, end, display ){
                        						if(list.length>0){
                        							var api = this.api(), data;
                        
                        							for( var i=0; i<=data.length-1;i++){
                        
                        								var intVal = function ( i ) {
                        									return typeof i === 'string' ?
                        									i.replace(/[\$,]/g, '')*1 :
                        									typeof i === 'number' ?
                        									i : 0;
                        								};	
                        								
                        								$(api.column(0).footer() ).html('Total');	
                        
                        								gross_wt = api
                        								.column(8)
                        								.data()
                        								.reduce( function (a, b) {
                        									return intVal(a) + intVal(b);
                        								}, 0 );
                        								$(api.column(8).footer()).html(parseFloat(gross_wt).toFixed(2));
                        								
                        								net_wt = api
                        								.column(9)
                        								.data()
                        								.reduce( function (a, b) {
                        									return intVal(a) + intVal(b);
                        								}, 0 );
                        								$(api.column(9).footer()).html(parseFloat(net_wt).toFixed(2));
                        								
                        								total_amount = api
                        								.column(10)
                        								.data()
                        								.reduce( function (a, b) {
                        									return intVal(a) + intVal(b);
                        								}, 0 );
                        								$(api.column(10).footer()).html(parseFloat(total_amount).toFixed(2));
                        								
                        						} 
                        						}else{
                        							 var api = this.api(), data; 
                        							 $(api.column(9).footer()).html('');  
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

function get_cancel_pay_list(from_date="",to_date="",branch="")
{
	my_Date = new Date();
	postData = (from_date !='' && to_date !=''? {'from_date':from_date,'to_date':to_date,'id_branch':branch}: ''),
	 $("div.overlay").css("display", "block"); 
	$.ajax({
			 url:base_url+ "index.php/admin_reports/paymentcancel_list?nocache=" + my_Date.getUTCSeconds(),
			 data: (postData),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			 	$('#total_cancel_payments').text(data.length);
			   			set_cancel_pay_list(data);
			   			 $("div.overlay").css("display", "none"); 
					  },
					  error:function(error)  
					  {
						 $("div.overlay").css("display", "none"); 
					  }	 
		  });
}
function set_cancel_pay_list(data)
{
    var oTable = $('#payment_cancel_list').DataTable();
    oTable.clear().draw();
    //console.log(data);
        oTable = $('#payment_cancel_list').dataTable({
                "bDestroy": true,
                "bInfo": true,
                "bFilter": true,
                "bSort": true,				                
                "dom": 'lBfrtip',
    		                "order": [[ 0, "desc" ]],
    		                "buttons": [
    							{
    							   extend: 'print',
    							   footer: true,
    							   title: 'Payment Cancelled Report '+$('#cancel_payment_list1').text()+' - '+$('#cancel_payment_list2').text(),
    							   customize: function ( win ) {
    								$(win.document.body).find( 'table' )
    									.addClass( 'compact' )
    									.css( 'font-size', 'inherit' );
    								},
    							 },
    							 {
    								extend:'excel',
    								footer: true,
    							    title: 'Payment Cancelled Report '+$('#cancel_payment_list1').text()+' - '+$('#cancel_payment_list2').text(),
    							  }
    						],
                "aaData": data,
                "order": [[ 0, "desc" ]],
                  "aoColumns": [{ "mDataProp": "id_payment" },
                                  { "mDataProp": "id_scheme_account" },
					       { "mDataProp": "date_payment" },
	                 { "mDataProp": "approval_date" },
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
	                //{ "mDataProp": "paid_installments" },
	                { "mDataProp": "payment_type" },
	                { "mDataProp": "payment_mode" },
	                { "mDataProp": "metal_rate" },
	                { "mDataProp": "metal_weight" },
	                { "mDataProp": "employee" },
	                { "mDataProp": function(row,type,val,meta)
	                	{return "<span class='label bg-"+row.status_color+"-active'>"+row.payment_status+"</span>";	}
	                
	               },
	                 { "mDataProp": function(row,type,val,meta)
	                	{
	                	
	                	return (row.payment_type=='Payu Checkout' && row.id_status!=1 && (row.due_type=='A' || row.due_type=='P')?row.act_amount:row.payment_amount);	
	                	}
	                
	               },
	                { "mDataProp": "payment_ref_number" },
	                 { "mDataProp": "emp_code" }
					               
					               
					               ]
            });
    $("div.overlay").css("display", "none");    
} 



 // Scheme source wise report  --- scheme wise payment details report with mode wise + online & showroom collection report   --> START
 
 
//reportjs
function getPaymentDateRangeList()
{
    $("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+"index.php/admin_reports/scheme_payment_list_daterange?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'from_date':$('#rpt_payments1').html(),'to_date' :$('#rpt_payments2').html(),'id_classfication':$('#id_classifications').val(),'id_scheme':$('#scheme_select').val(),'mode':$('#mode_select').val(),'pay_mode':$('#select_pay_mode').val(),'id_branch':($('#branch_filter').val()!='' && $('#branch_filter').val()!=undefined ? $('#branch_filter').val(): $("#id_branch").val())}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			     
			    var branch_name=($('#branch_name').val()!='' && $('#branch_name').val()!=undefined ? $('#branch_name').val() : $('#branch_select option:selected').toArray().map(item => item.text).join());
                var company_name=$('#company_name').val();
                var company_code=$('#company_code').val();
                var company_code=$('#company_code').val();
                var company_address1=$('#company_address1').val();
                var company_address2=$('#company_address2').val();
                var company_city=$('#company_city').val();
                var pincode=$('#pincode').val();
                var company_email=$('#company_email').val();
                var company_gst_number=$('#company_gst_number').val();
                var phone=$('#phone').val();
                var title="<div style='text-align: center;'><b><span style='font-size:12pt;'>"+company_code+"</span></b></br>"
                +"<span style='font-size:11pt;'>"+company_address1+"</span></br>"
                +"<span style='font-size:11pt;'>"+company_address2 + company_city+"-"+pincode+"</span></br>"
                +"<span style='font-size:11pt;'>GSTIN:"+company_gst_number +", EMAIL:"+ company_email+"</span></br>"
                +"<span style='font-size:11pt;'>Contact :"+phone +"</span></br>"
                +"<span style=font-size:12pt;>&nbsp;&nbsp;Source Wise Payment Report - "+branch_name+" &nbsp;From&nbsp;:&nbsp;"+$('#rpt_payments1').html()+" &nbsp;&nbsp;- to "+$('#rpt_payments2').html() + " - " + $('.hidden-xs').html()+ "</span><br>"
                +"<span style=font-size:11pt;>Print Taken On "+moment().format("dddd, MMMM Do YYYY, h:mm:ss a")+"</span>";

                title+='</br><div"><table class="table table-bordered table-striped text-center" style="border: 1px solid black;border-collapse: collapse; width:65%;margin-left:150px;">'+
		'<thead style="font-size:11pt;">'+
		//'<tr><th colspan="3">Payment Summary</th></tr>'+
		'<tr><th style="text-align: center;"><span >Showroom Collection</span></th>'+
		//'<th><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></th>'
		'<th><span style="text-align: center;"><span >Online Collection</span></th></tr>'+
		'</thead>'+
		'<tbody style="font-size:11pt;"></br>';         
            
 //offline
    if(data.offline_total == null){
    
    }else{
         title+='<tr><td>';
        $.each(data.mode_wise_sum.offline,function(key,val){
            if(val.mode_name == null){
                var mode =  val.payment_mode;
            }else{
            	var mode = val.mode_name;
            }
            if(mode != null && val.offline_amt != null){
                var pay_mode = mode;
                var pay_amt = val.offline_amt;
indianCurrency.format(data.online_total)
              // title+='<td><table><tr><td>'+pay_mode+'</td><td></td><td>:</td><td></td><td><strong>'+pay_amt+'</strong></td></tr></table></td>';
               title+= '<span class="pull-left">'+pay_mode+'</span><span></span><span class="pull-right" ><strong>'+indianCurrency.format(pay_amt)+'</strong></span><br>';          
            }
        });
        
        title+= '</td>';
    }	
    
   // title+='<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
    //offline
    if(data.online_total == null){
    
    }else{
        title+='<td >';
        $.each(data.mode_wise_sum.online,function(key,val){
            if(val.mode_name == null){
                var mode =  val.payment_mode;
            }else{
            	var mode = val.mode_name;
            }
            if(mode != null && val.online_amt != null){
                var pay_mode = mode;
                var pay_amt = val.online_amt;

               title+= '<span class="pull-left">'+pay_mode+'</span><span></span><span class="pull-right" ><b>'+indianCurrency.format(pay_amt)+'</b></span><br>';   
                         
            }
        });
        
        title+= '</td></tr>';

        
    }	
    
    if(data.online_total == null){
        title+='</br></tr><tr><td> <strong>Total Payment &nbsp;&nbsp;: &nbsp;&nbsp;'+indianCurrency.format(data.offline_total)+' </strong></td><td> <strong>No Data Available</strong> </td></tr>';
    }else if(data.offline_total == null){
        title+='</br></tr><tr><td> <strong>No Data Available </strong></td><td> <strong>Total Payment &nbsp;&nbsp; :  &nbsp;&nbsp;'+indianCurrency.format(data.online_total)+' </strong> </td></tr>';
    }else{
       title+='</br></tr><tr><td> <strong>Total Payment  &nbsp;&nbsp;: &nbsp;&nbsp;'+indianCurrency.format(data.offline_total)+'  </strong></td><td> <strong>Total Payment &nbsp;&nbsp; :&nbsp;&nbsp;  '+indianCurrency.format(data.online_total)+' </strong> </td></tr>'; 
    }
		
	title+='</tbody>'+
		 '</table></div></br>';
		 
			  $("div.overlay").css("display", "none");   
		    $("#report_payment_daterange > tbody > tr").remove();
	 		$('#report_payment_daterange').dataTable().fnClearTable();
	 		$('#report_payment_daterange').dataTable().fnDestroy();  
                trHTML = ''; 
                total_pay_amount=0;
                total_bonus_amount=0;
                total_metal_weight=0;
				total_count=0
				i=0;
                $.each(data.schemes,function(key,payment){
					i++;
                    var paid_amount=0;
                    var bonus_amount=0;
                    var metal_weight=0;
					var scheme=data.schemes_sum;
					table_id=key.split(' ',).join('_'); 
					table_id=table_id.split('-',).join('_'); 
					console.log(scheme[key]['count']);
                     trHTML+='<tr>'+
					           '<td>'+i+'</td>'+
                                '<td  style="text-align:left;"><strong>'+key.toUpperCase()+'</strong></td>'+  
                           
								'<td style="text-align:right;"><strong>'+scheme[key]['count']+'</strong></td>'+  

								'<td style="text-align:right;"><strong>'+parseFloat(scheme[key]['total_weight']).toFixed(3) +'</strong></td>'+  
								'<td style="text-align:right;" ><strong>'+ indianCurrency.format(parseFloat(scheme[key]['total_amount']).toFixed(2))+'</strong></td>'+  
                          
                                '<td><a onclick=show_scheme_details("'+table_id+'")><i id="'+table_id+'_icon" class="fa fa-plus" aria-hidden="true"></i></a></td>'+
                              
                            '</tr>';
							trHTML+='<tr id="'+table_id+'" style="display:none; "  >';
							trHTML+='<td colspan="6"> <div class="innerDetails" > <table class="table table-bordered table-striped text-center" id=product_'+table_id+'>'
						
							trHTML+= '<tr class="table-info" style="text-transform:uppercase; background-color: #a3d2fa;" >'+
							'<th width="1%">S.No</th>'+
							'<th width="1%">Mobile</th>'+
						
							'<th width="1%">Recpt.No</th>'+
							'<th width="1%">Acc Name</th>'+
							
							'<th width="1%">Pay.Date</th>'+
							
							'<th width="5%">Mode</th>'+
							'<th width="5%">M.Rate</th>'+
							'<th width="1%">Ins</th>'+
							'<th width="5%">M.weight</th>'+
						    '<th width="5%">Received.Amt</th>'+
						
							'<th width="5%">Cost Center</th>'+
							'<th width="5%">Paid Through</th>'+
						
						'</tr>';


                    $.each(payment,function(key,items){
                        paid_amount+=parseFloat(parseFloat(items.payment_amount)-parseFloat(items.discountAmt));
                        bonus_amount+=parseFloat(items.discountAmt);
                        metal_weight+=parseFloat(items.metal_weight);
                        var sales_ledger = base_url+'index.php/admin_ret_reports/customer_history/list/'+items.mobile;
                        var acc_ledger = base_url+'index.php/reports/payment/account/'+items.id_scheme_account;
                        var receipt_url = base_url+'index.php/payment/invoice/'+items.id_payment+'/'+items.id_scheme_account;
						
                        trHTML+='<tr>'+
                                    '<td>'+parseFloat(key+1)+'</td>'+  
                                    '<td><input type="hidden" class="mobile" value="'+items.mobile+'"><a href='+sales_ledger+' target="_blank">'+items.mobile+'</td>'+
                               
                                    '<td><a href='+receipt_url+' target="_blank">'+items.receipt_no+'</td>'+
                                    '<td>'+items.name+'</td>'+
                                   
                                    '<td>'+items.date_payment+'</td>'+
                               
                                    '<td>'+items.payment_mode+'</td>'+
                                    '<td>'+items.metal_rate+'</td>'+
									'<td>'+items.paid_installments+'</td>'+
                                 '<td>'+items.metal_weight+'</td>'+
                                    '<td>'+parseFloat(parseFloat(items.payment_amount)-parseFloat(items.discountAmt)).toFixed(2)+'</td>'+

                                    '<td>'+items.pay_branch+'</td>'+
                                    '<td>'+items.payment_through+'</td>'+
                               
                                '</tr>';
                    });
                  

					total_count+=parseFloat(scheme[key]['count']);	
                    total_pay_amount+=parseFloat(paid_amount);
                    total_bonus_amount+=parseFloat(bonus_amount);
                    total_metal_weight+=parseFloat(metal_weight);
					trHTML+='</table>'+
					'</div></td>  <td style="display: none"></td>  <td style="display: none"></td>  <td style="display: none"></td>  <td style="display: none"></td>  <td style="display: none"></td>  <td style="display: none"></td>  <td style="display: none"></td>  <td style="display: none"></td>  <td style="display: none"></td>  <td style="display: none"></td>  <td style="display: none"></td></tr>';
                });
                
			
				            trHTML+='<tr>'+
                                '<td></td>'+
                              
								'<td style="text-align:left;"><strong>GRAND TOTAL</strong></td>'+
								'<td style="text-align:right;" ><strong>'+total_count+'</strong></td>'+  
								'<td style="text-align:right;" ><strong>'+parseFloat(total_metal_weight).toFixed(3)+'</strong></td>'+
                                '<td style="text-align:right;" ><strong>'+indianCurrency.format(parseFloat(total_pay_amount).toFixed(2))+'</strong></td>'+
                                '<td></td>'+
                            '</tr>';
                            
             
            /*var total_pay_amt=0;
            trHTML+='<tr>'+
            '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td><strong>Payment Mode</strong></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
             '</tr>';
            $.each(data.mode_wise,function(i,row){
                total_pay_amt+=parseFloat(row.received_amt==null ?0.00:parseFloat(row.received_amt).toFixed(2));
                    trHTML+='<tr>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td><strong>'+(row.payment_mode==null ?'Collection App' :row.payment_mode)+'</strong></td>'+
                                '<td><strong>'+(row.received_amt==null ?0.00:parseFloat(row.received_amt).toFixed(2))+'</strong></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                            '</tr>';
                }); 
                
                trHTML+='<tr>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td><strong>TOTAL AMOUNT</strong></td>'+
                '<td><strong>'+parseFloat(total_pay_amt).toFixed(2)+'</strong></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '</tr>';
            
            */     console.log(trHTML);           
                console.log(data.schemes.length !== 0);
                $('#report_payment_daterange > tbody').html(trHTML);
               // Check and initialise datatable
	                 if ( ! $.fn.DataTable.isDataTable( '#report_payment_daterange' ) ) 
	                 { 
	                     if(data.schemes.length !== 0)
	                     {
	                         oTable = $('#report_payment_daterange').dataTable({ 
						                "bSort": false, 
						                "bInfo": false, 
						                "scrollX":'100%',  
						                "dom": 'lBfrtip',
                                      
						                "lengthMenu": [[-1, 25, 50, 100, 250], ["All" ,25, 50, 100, 250]],
						                "buttons": [
													{
													   extend: 'print',
													   footer: true,
													   //title: 'Collection Report '+$('#rpt_payments1').html()+' - '+$('#rpt_payments2').html(),
													   title: '',
                                                       messageTop :title,
													   orientation: 'landscape',
													   customize: function ( win ) {
                            							    $(win.document.body).find('table')
                                                            .addClass('compact');
                                                    
                            								$(win.document.body).find( 'table' )
                            									.addClass('compact')
                            									.css('font-size','10px')
                            									.css('font-family','sans-serif');
                            								
                            							},
													 },
													 {
														extend:'excel',
														footer: true,
													    title: 'Collection Report '+$('#rpt_payments1').html()+' - '+$('#rpt_payments2').html(),
													  }
													 ], 
			
							
									 });
	                     }
	                     
						}
						
						console.log($('#modesummary').html());
						
                
				 
			  },
			  error:function(error)  
			  {
				 $("div.overlay").css("display", "none"); 
			  }	 
	      });
	      
           /* var table = $('#report_payment_daterange').DataTable({
            orderCellsTop: true,
            fixedHeader: true
            });
            $('#report_payment_daterange thead tr').clone(true).appendTo( '#report_payment_daterange thead' );
            
            $('#report_payment_daterange thead tr:eq(1) th').each( function (i) {
            var title = $(this).text();
            $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
            
            $( 'input', this ).on( 'keyup change', function () {
            if ( table.column(i).search() != this.value ) {
            table
            .column(i)
            .search( this.value )
            .draw();
            }
            } );
            } );*/
        
       
}

function getPaymentSummary(){
    $.ajax({
			 url:base_url+"index.php/admin_reports/payment_summary_modewise?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'from_date':$('#rpt_payments1').html(),'to_date' :$('#rpt_payments2').html(),'id_classfication':$('#id_classifications').val(),'mode':$('#mode_select').val(),'id_scheme':$('#scheme_select').val(),'pay_mode':$('#select_pay_mode').val(),'id_branch':($('#branch_filter').val()!='' && $('#branch_filter').val()!=undefined ? $('#branch_filter').val(): $("#id_branch").val())}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
                  
                 var total_amount=parseFloat(data.offline_total)+parseFloat(data.online_total)+parseFloat(data.admin_app_total);
				 var from_date=$('#rpt_payments1').html();
				 var to_date=$('#rpt_payments2').html();
				 var total_count =parseFloat(data.offline_count)+parseFloat(data.online_count)+parseFloat(data.admin_app_count);
				 $('#total_sum_from').html(from_date);
				 $('#total_sum_to').html(to_date);
				 $('#total_sum_count').html(total_count);
				 $('#total_sum_amt').html(indianCurrency.format(total_amount));
				 

			     offHTML = '';
			     onHTML = '';
				 adminHTML ='';
				 offHTML_cash ='';
				 offHTML_online ='';
				 offHTML_mulit ='';
				 offHTML_sub='';
			     var off_tot_amt = 0;
			     var on_tot_amt = 0;
				 
            //offline	
                if(data.offline_total ===  0){
                     offHTML+='<strong><span>No data available</span></strong>';
                    $('#offline_modewise').html(offHTML);
                }else{
					online_transtion=0;
					offHTML+=' <div id="offline_modewise_details" style="display:none;"><div class="row">'+
                    			 '<div class="col-md-5" style="text-align: left;"> <strong><span>Mode </span></strong></div>'+
                    			 '<div class="col-md-1"><lable> : </lable></div>'+
								 //'<div class="col-md-3" style="text-align: right;" ><strong><span><lable>Payments</span></strong></div>'+
                    			 '<div class="col-md-5" style="text-align: right;"><lable><strong><span> Amount</span></strong></div>'+
								 '<div class="col-md-1"></div>'+
                			   '</div> <hr> ' ;
        			     $.each(data.mode_summary.offline,function(key,val){
							offHTML_sub='';
							offHTML_online_head='';
							table_id=key.split(' ',).join('_'); 
							table_id=table_id.split('-',).join('_'); 
							offHTML_sub+='<div class="'+table_id+'_mode"  style="display:none; "> ';
							offline_amt=0;
							offHTML_cash+='<div class="'+table_id+'_mode"  style="display:none; "> ';
							total_amount=0;
							$.each(val,function(keys,vals){
								
								if(vals.mode_name == null){
									var mode =  vals.payment_mode;
								 }else{
									var mode = vals.mode_name;                       
								 }
								 if(mode != null && vals.offline_amt != null){ 

								offHTML_sub+=' <div class="row " >'+
								'<div class="col-md-5" style="text-align: left; background-color: #a3d2fa;">'+mode+'</div>'+
								'<div class="col-md-1" style="background-color: #a3d2fa;" ><lable> : </lable></div>'+
							   //  '<div class="col-md-3" style="text-align: right;" ><lable> '+val.payment_count+' </lable></div>'+
								'<div class="col-md-5" style="text-align: right; background-color: #a3d2fa;">  <strong><span>'+indianCurrency.format(vals.offline_amt)+'</span></strong> </div>'+
								'<div class="col-md-1"></div>'+
							  '</div>';
							  mode_name=mode;
							  offline_amt+=parseFloat(vals.offline_amt);
							}
							});
							// online_transtion+=parseFloat(offline_amt);
        			        offHTML_sub+=' </div>';
							console.log(val.length);
							if(val.length!=1){
								if(key == 'Cash'){
									offHTML+=' <div class="row">'+
									'<div class="col-md-5" style="text-align: left;">'+key+'</div>'+
									'<div class="col-md-1"><lable> : </lable></div>'+
								   //  '<div class="col-md-3" style="text-align: right;" ><lable> '+val.payment_count+' </lable></div>'+
									'<div class="col-md-5" style="text-align: right;"><a onclick="show_scheme_details(\''+table_id+'_mode\')"><strong><span>'+indianCurrency.format(offline_amt)+'</span></strong></a></div>'+
									'<div class="col-md-1"></div>'+
								  '</div>';
								  offHTML+=offHTML_sub;
								}else{
									offHTML_online+=' <div class="row online_transation_offline "  style="display:none" >'+
									'<div class="col-md-5" style="text-align: left; ">'+key+'</div>'+
									'<div class="col-md-1" style=""><lable> : </lable></div>'+
								   //  '<div class="col-md-3" style="text-align: right;" ><lable> '+val.payment_count+' </lable></div>'+
									'<div class="col-md-5" style="text-align: right; "><a onclick="show_scheme_details(\''+table_id+'_mode\')"><strong><span>'+indianCurrency.format(offline_amt)+'</span></strong></a></div>'+
									'<div class="col-md-1"></div>'+
								  '</div>';
								  online_transtion+=parseFloat(offline_amt);
								  offHTML_online+=offHTML_sub;
								}
							
								
							}else{
								if(key == 'Cash'){
								offHTML+=' <div class="row ">'+
								'<div class="col-md-5" style="text-align: left;">'+mode_name+'</div>'+
								'<div class="col-md-1"><lable> : </lable></div>'+
							   //  '<div class="col-md-3" style="text-align: right;" ><lable> '+val.payment_count+' </lable></div>'+
								'<div class="col-md-5" style="text-align: right;"><strong><span>'+indianCurrency.format(offline_amt)+'</span></strong></div>'+
								'<div class="col-md-1"></div>'+
							  '</div>';
							    } else{
									online_transtion+=parseFloat(offline_amt);
									offHTML_online+=' <div class="row online_transation_offline" style="display:none">'+
									'<div class="col-md-5" style="text-align: left; ">'+mode_name+'</div>'+
									'<div class="col-md-1" style="" ><lable> : </lable></div>'+
								   //  '<div class="col-md-3" style="text-align: right;" ><lable> '+val.payment_count+' </lable></div>'+
									'<div class="col-md-5" style="text-align: right; "><strong><span>'+indianCurrency.format(offline_amt)+'</span></strong></div>'+
									'<div class="col-md-1"></div>'+
								  '</div>';
								}
								// offHTML_sub='';
							
						}
							
							 
        
        			     });

						

                        //  offHTML+=offHTML_cash;
						//  offHTML+=offHTML_online;
					if(online_transtion!=0){
						offHTML+='<div class="row">'+
						'<div class="col-md-5" style="text-align: left;"> Online Transation</div>'+
						'<div class="col-md-1"><lable> : </lable></div>'+
					   //  '<div class="col-md-3" style="text-align: right;" ><lable> '+val.payment_count+' </lable></div>'+
						'<div class="col-md-5" style="text-align: right;"><a onclick="show_scheme_details(\'online_transation_offline\')" ><strong><span>'+indianCurrency.format(online_transtion)+'</span></strong></a></div>'+
						'<div class="col-md-1"></div>'+
					  '</div> ';
					  offHTML+=offHTML_online;
					}
					
					  
					   //offHTML+=offHTML_online_head;
        			     offHTML+='<div class="row"></div><hr> </div>'+
        							'<div class="row">'+
        							'<div class="col-md-5" style="text-align: left;"> <strong>Total Payment</strong></div>'+
        							'<div class="col-md-1"><lable> : </lable></div>'+
									// '<div class="col-md-3"style="text-align: right;" ><lable> '+data.offline_count+' </lable></div>'+
        							'<div class="col-md-5" style="text-align: right;"><strong><span>'+indianCurrency.format(data.offline_total)+'</span></strong></div>'+
									'<div class="col-md-1"></div>'+
        							'</div>';

						offHTML+='<div class="row"></div><hr>'+
        							'<div class="row">'+
        							'<div class="col-md-5" style="text-align: left;"> <strong>Total Count</strong></div>'+
        							'<div class="col-md-1"><lable> : </lable></div>'+
									// '<div class="col-md-3"style="text-align: right;" ><lable> '+data.offline_count+' </lable></div>'+
        							'<div class="col-md-5" style="text-align: right;"><strong><span>'+indianCurrency.format(data.offline_count)+'</span></strong></div>'+
									'<div class="col-md-1"></div>'+
        							'</div>';
            			   $('#offline_modewise').html(offHTML);
                }		   
            			   
          // online  	
          
                if(data.online_total == 0){
                    onHTML+='<div class="col-md-12"><strong><span>No data available</span></strong></div>';

                    $('#online_modewise').html(onHTML);
                }else{
				online_transtion=0;

				onHTML_online='';
				onHTML+=' <div id="online_modewise_details" style="display:none;"><div class="row">'+
                    			 '<div class="col-md-5" style="text-align: left;"> <strong><span>Mode </span></strong></div>'+
                    			 '<div class="col-md-1"><lable> : </lable></div>'+
								 //'<div class="col-md-3" style="text-align: right;" ><strong><span><lable>Payments</span></strong></div>'+
                    			 '<div class="col-md-5" style="text-align: right;"><lable><strong><span> Amount</span></strong></div>'+
								 '<div class="col-md-1"></div>'+
                			   '</div> <hr> ' ;
        			     $.each(data.mode_summary.online,function(key,val){
							onHTML_sub='';
							
							table_id=key.split(' ',).join('_'); 
							table_id=table_id.split('-',).join('_'); 
							onHTML_sub+='<div class="'+table_id+'_mode"  style="display:none; "> ';
							online_amt=0;
							$.each(val,function(keys,vals){
								
								if(vals.mode_name == null){
									var mode =  vals.payment_mode;
								 }else{
									var mode = vals.mode_name;                       
								 }
								 if(mode != null && vals.online_amt != null){ 

									onHTML_sub+=' <div class="row" >'+
								'<div class="col-md-5" style="text-align: left; background-color: #a3d2fa;">'+mode+'</div>'+
								'<div class="col-md-1" style="background-color: #a3d2fa;" ><lable> : </lable></div>'+
							   //  '<div class="col-md-3" style="text-align: right;" ><lable> '+val.payment_count+' </lable></div>'+
								'<div class="col-md-5" style="text-align: right; background-color: #a3d2fa;">  <strong><span>'+indianCurrency.format(vals.online_amt)+'</span></strong> </div>'+
								'<div class="col-md-1"></div>'+
							  '</div>';
							  mode_name=mode;
							  online_amt+=parseFloat(vals.online_amt);
							}
							});

        			        onHTML_sub+=' </div>';
							console.log(val.length);
							if(val.length!=1){

								if(key == 'Cash'){
									onHTML+=' <div class="row ">'+
									'<div class="col-md-5" style="text-align: left;">'+key+'</div>'+
									'<div class="col-md-1"><lable> : </lable></div>'+
								   //  '<div class="col-md-3" style="text-align: right;" ><lable> '+val.payment_count+' </lable></div>'+
									'<div class="col-md-5" style="text-align: right;"><a onclick="show_scheme_details(\''+table_id+'_mode\')"><strong><span>'+indianCurrency.format(online_amt)+'</span></strong></a></div>'+
									'<div class="col-md-1"></div>'+
								  '</div>';
								  onHTML+=onHTML_sub;
									} else{
										onHTML_online+=' <div class="row online_transation_online" style="display:none">'+
										'<div class="col-md-5" style="text-align: left; ">'+key+'</div>'+
										'<div class="col-md-1" style="" ><lable> : </lable></div>'+
									   //  '<div class="col-md-3" style="text-align: right;" ><lable> '+val.payment_count+' </lable></div>'+
										'<div class="col-md-5" style="text-align: right; "> <a onclick="show_scheme_details(\''+table_id+'_mode\')"><strong><span>'+indianCurrency.format(online_amt)+'</span></strong></a></div>'+
										'<div class="col-md-1"></div>'+
									  '</div>';
									  onHTML_online+=onHTML_sub;
									   online_transtion+=parseFloat(online_amt);
									}
								
						
							}else{
								if(key == 'Cash'){
									onHTML+=' <div class="row">'+
									'<div class="col-md-5" style="text-align: left;">'+mode_name+'</div>'+
									'<div class="col-md-1"><lable> : </lable></div>'+
								   //  '<div class="col-md-3" style="text-align: right;" ><lable> '+val.payment_count+' </lable></div>'+
									'<div class="col-md-5" style="text-align: right;"><strong><span>'+indianCurrency.format(online_amt)+'</span></strong></div>'+
									'<div class="col-md-1"></div>'+
								  '</div>';
								  
								  
								}else{
									onHTML_online+=' <div class="row online_transation_online "  style="display:none" >'+
									'<div class="col-md-5" style="text-align: left; ">'+mode_name+'</div>'+
									'<div class="col-md-1" style=""><lable> : </lable></div>'+
								   //  '<div class="col-md-3" style="text-align: right;" ><lable> '+val.payment_count+' </lable></div>'+
									'<div class="col-md-5" style="text-align: right; "><strong><span>'+indianCurrency.format(online_amt)+'</span></strong></div>'+
									'<div class="col-md-1"></div>'+
								  '</div>';
								  online_transtion+=parseFloat(online_amt);
								 
								}
						
							}
							
							
							
							 
        
        			     });

						 
						 if(online_transtion!=0){
							onHTML+='<div class="row">'+
							'<div class="col-md-5" style="text-align: left;"> Online Transation</div>'+
							'<div class="col-md-1"><lable> : </lable></div>'+
						   //  '<div class="col-md-3" style="text-align: right;" ><lable> '+val.payment_count+' </lable></div>'+
							'<div class="col-md-5" style="text-align: right;"><a onclick="show_scheme_details(\'online_transation_online\')" ><strong><span>'+indianCurrency.format(online_transtion)+'</span></strong></a></div>'+
							'<div class="col-md-1"></div>'+
						  '</div>';	
						  onHTML+=onHTML_online;	
						 }
									
        			     onHTML+='<div class="row"></div><hr> </div>'+
        							'<div class="row">'+
        							'<div class="col-md-5" style="text-align: left;"> <strong>Total Payment</strong></div>'+
        							'<div class="col-md-1"><lable> : </lable></div>'+
									// '<div class="col-md-3"style="text-align: right;" ><lable> '+data.offline_count+' </lable></div>'+
        							'<div class="col-md-5" style="text-align: right;"><strong><span>'+indianCurrency.format(data.online_total)+'</span></strong></div>'+
									'<div class="col-md-1"></div>'+
        							'</div>';

									onHTML+='<div class="row"></div><hr>'+
        							'<div class="row">'+
        							'<div class="col-md-5" style="text-align: left;"> <strong>Total Count</strong></div>'+
        							'<div class="col-md-1"><lable> : </lable></div>'+
									// '<div class="col-md-3"style="text-align: right;" ><lable> '+data.offline_count+' </lable></div>'+
        							'<div class="col-md-5" style="text-align: right;"><strong><span>'+indianCurrency.format(data.online_count)+'</span></strong></div>'+
									'<div class="col-md-1"></div>'+
        							'</div>';
            			   $('#online_modewise').html(onHTML);
                }

				if(data.admin_app_total == 0){
                    adminHTML+='<div class="col-md-12"><strong><span>No data available</span></strong></div>';

                    $('#app_modewise').html(adminHTML);
                }else{
					adminHTML_online='';
					online_transtion=0;
					adminHTML+='<div id="app_modewise_details" style="display:none;"> <div class="row">'+
					'<div class="col-md-5" style="text-align: left;"> <strong><span>Mode </span></strong></div>'+
					'<div class="col-md-1"><lable> : </lable></div>'+
					//'<div class="col-md-3" style="text-align: right;" ><strong><span><lable>Payments</span></strong></div>'+
					'<div class="col-md-5" style="text-align: right;"><lable><strong><span> Amount</span></strong></div>'+
					'<div class="col-md-1"></div>'+
				  '</div> <hr> ' ;
           
				$.each(data.mode_summary.admin_app,function(key,val){
					adminHTML_sub='';
					table_id=key.split(' ',).join('_'); 
					table_id=table_id.split('-',).join('_'); 
					adminHTML_sub+='<div class="'+table_id+'_mode"  style="display:none; "> ';
					admin_app_amt=0;
					$.each(val,function(keys,vals){
						
						if(vals.mode_name == null){
							var mode =  vals.payment_mode;
						 }else{
							var mode = vals.mode_name;                       
						 }
						 if(mode != null && vals.admin_app_amt != null){ 

							adminHTML_sub+=' <div class="row" >'+
						'<div class="col-md-5" style="text-align: left; background-color: #a3d2fa;">'+mode+'</div>'+
						'<div class="col-md-1" style="background-color: #a3d2fa;" ><lable> : </lable></div>'+
					   //  '<div class="col-md-3" style="text-align: right;" ><lable> '+val.payment_count+' </lable></div>'+
						'<div class="col-md-5" style="text-align: right; background-color: #a3d2fa;">  <strong><span>'+indianCurrency.format(vals.admin_app_amt)+'</span></strong> </div>'+
						'<div class="col-md-1"></div>'+
					  '</div>';
					  mode_name=mode;
					  admin_app_amt+=parseFloat(vals.admin_app_amt);
					}
					});

					adminHTML_sub+=' </div>';
					console.log(val.length);
					if(val.length!=1){
						if(key == 'Cash'){
							adminHTML+=' <div class="row">'+
							'<div class="col-md-5" style="text-align: left;">'+key+'</div>'+
							'<div class="col-md-1"><lable> : </lable></div>'+
						   //  '<div class="col-md-3" style="text-align: right;" ><lable> '+val.payment_count+' </lable></div>'+
							'<div class="col-md-5" style="text-align: right;"><a onclick="show_scheme_details(\''+table_id+'_mode\')"><strong><span>'+indianCurrency.format(admin_app_amt)+'</span></strong></a></div>'+
							'<div class="col-md-1"></div>'+
						  '</div>';
						  adminHTML+=adminHTML_sub;
						}else{
							adminHTML_online+=' <div class="row online_transation "  style="display:none" >'+
							'<div class="col-md-5" style="text-align: left; ">'+key+'</div>'+
							'<div class="col-md-1" style=""><lable> : </lable></div>'+
						   //  '<div class="col-md-3" style="text-align: right;" ><lable> '+val.payment_count+' </lable></div>'+
							'<div class="col-md-5" style="text-align: right; "><a onclick="show_scheme_details(\''+table_id+'_mode\')"><strong><span>'+indianCurrency.format(admin_app_amt)+'</span></strong></a></div>'+
							'<div class="col-md-1"></div>'+
						  '</div>';
						 
						  adminHTML_online+=adminHTML_sub;
						  online_transtion+=parseFloat(admin_app_amt);
						}
					
					
					}else{

						if(key == 'Cash'){
							adminHTML+=' <div class="row ">'+
							'<div class="col-md-5" style="text-align: left;">'+mode_name+'</div>'+
							'<div class="col-md-1"><lable> : </lable></div>'+
						   //  '<div class="col-md-3" style="text-align: right;" ><lable> '+val.payment_count+' </lable></div>'+
							'<div class="col-md-5" style="text-align: right;"><strong><span>'+indianCurrency.format(admin_app_amt)+'</span></strong></div>'+
							'<div class="col-md-1"></div>'+
						    '</div>';
						} else{
							adminHTML_online+=' <div class="row online_transation" style="display:none">'+
								'<div class="col-md-5" style="text-align: left; ">'+mode_name+'</div>'+
								'<div class="col-md-1" style="" ><lable> : </lable></div>'+
							   //  '<div class="col-md-3" style="text-align: right;" ><lable> '+val.payment_count+' </lable></div>'+
								'<div class="col-md-5" style="text-align: right; "><strong><span>'+indianCurrency.format(admin_app_amt)+'</span></strong></div>'+
								'<div class="col-md-1"></div>'+
							  '</div>';
							  online_transtion+=parseFloat(admin_app_amt);
							}

					
					}
					
					
					
					 

				 });
				 if(online_transtion!=0){
					adminHTML+='<div class="row">'+
					'<div class="col-md-5" style="text-align: left;"> Online Transation</div>'+
					'<div class="col-md-1"><lable> : </lable></div>'+
				   //  '<div class="col-md-3" style="text-align: right;" ><lable> '+val.payment_count+' </lable></div>'+
					'<div class="col-md-5" style="text-align: right;"><a onclick="show_scheme_details(\'online_transation\')" ><strong><span>'+indianCurrency.format(online_transtion)+'</span></strong></a></div>'+
					'<div class="col-md-1"></div>'+
				  '</div>';
				  adminHTML+=adminHTML_online;
				}
                    adminHTML+='<div class="row"></div><hr></div>'+
            							'<div class="row">'+
            							'<div class="col-md-5" style="text-align: left;"> <strong>Total Payment</strong></div>'+
            							'<div class="col-md-1"><lable> : </lable></div>'+
										//'<div class="col-md-3"style="text-align: right;" ><lable> '+data.admin_app_count+' </lable></div>'+
            							'<div class="col-md-5" style="text-align: right;"><strong><span>'+indianCurrency.format(data.admin_app_total)+'</span></strong></div>'+
										'<div class="col-md-1"></div>'+
										'</div>';
					adminHTML+='<div class="row"></div><hr>'+
            							'<div class="row">'+
            							'<div class="col-md-5" style="text-align: left;"> <strong>Total Count</strong></div>'+
            							'<div class="col-md-1"><lable> : </lable></div>'+
										//'<div class="col-md-3"style="text-align: right;" ><lable> '+data.admin_app_count+' </lable></div>'+
            							'<div class="col-md-5" style="text-align: right;"><strong><span>'+indianCurrency.format(data.admin_app_count)+'</span></strong></div>'+
										'<div class="col-md-1"></div>'+
										'</div>';					
                	$('#app_modewise').html(adminHTML);
                }
				
        
		},
			 
			error:function(error){
				$("div.overlay").css("display", "none"); 
			}
			
	    });
    
}


 $('#search_payment_list').on('click',function(){
    getPaymentDateRangeList();
    getPaymentSummary();
});
 
 
 function get_payModeList()
{
	
	$(".overlay").css('display','block');
	$.ajax({
		type: 'GET',
		url: base_url+'index.php/admin_reports/ajax_getPayModeList',
		dataType:'json',
		success:function(data){
		
		 var mode_val =  $('#id_pay_mode').val();
		   $.each(data, function (key, item) {
					  	
			  
			   		$('#mode_select').append(
						$("<option></option>")
						.attr("value", item.short_code)						  
						  .text(item.mode_name )
						  
					);			   				
				
			});
			
			$("#mode_select").select2({
			    placeholder: "Select Mode Name",
			    allowClear: true
			});
				
			 $("#mode_select").select2("val",(mode_val!='' && mode_val>0?mode_val:''));
			 $(".overlay").css("display", "none");	
		}
	});
}

 // Scheme source wise report  --- scheme wise payment details report with mode wise + online & showroom collection report   --> END
 
 
 
 //gift issued report -- start
 
function getGiftIssuedList(from_date="",to_date="",id_branch="")
{
    my_Date = new Date();
    postData = {'from_date':from_date,'to_date':to_date,'id_branch':id_branch};
    $("div.overlay").css("display", "block");
    
    $.ajax({
        url:base_url+ "index.php/admin_reports/ajax_gift_report?nocache=" + my_Date.getUTCSeconds(),
        data: (postData),
        dataType:"JSON",
        type:"POST",
        success:function(data){
            set_gift_list(data);
            $("div.overlay").css("display", "none"); 
        },
        error:function(error){
            $("div.overlay").css("display", "none"); 
        }	 
    });
}
 
 
 function set_gift_list(data)
{
    var gift = data.gift;
    var oTable = $('#gift_list').DataTable();
    oTable.clear().draw();
    console.log(gift);
  
        oTable = $('#gift_list').dataTable({
                "bDestroy": true,
                "bInfo": true,
                "bFilter": true,
                "bSort": true,				                
                "dom": 'T<"clear">lfrtip',				                
                "tableTools": { "aButtons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },
                "aaData": gift,
                "order": [[ 0, "desc" ]],
                "aoColumns": [  { "mDataProp": "id" },
    							{ "mDataProp": "cus_name" },
                                { "mDataProp": "mobile" },
    							{ "mDataProp": "account_no" },
    							{ "mDataProp": "joined_date" },
    							{ "mDataProp": "paid_installment" },
    						    { "mDataProp": "issued_date" },
    						    { "mDataProp": "payment_amount" },
    							{ "mDataProp": "gift_desc" },
    							{ "mDataProp": "status" }
    							
    						 ] 
            });
    				        
    
    $("div.overlay").css("display", "none");    
				            			  	 	
} 
 
 //gift issued report ends
 
function get_paymentRemarks(from_date,to_date,id_emp)
{

	    my_Date = new Date();

		$("div.overlay").css("display", "block");
		$.ajax({
		data: (from_date !='' && to_date !=''? {'from_date':from_date,'to_date':to_date}: ''), //hh

			url: base_url+'index.php/admin_manage/getRemarkPayments',
			
			  dataType:"JSON",
			  type:"POST",
			 success:function(data){
				set_paymentRemarks(data);	
				 $("div.overlay").css("display", "none"); 
		 	 	},
		 	 	error:function(error)  

					  {

						 $("div.overlay").css("display", "none"); 

					  }	
	  });
}
 
 function set_paymentRemarks(data){
 
 	var payment=data;
 	from=$("#rpt_payments1").text();
 	to=$("#rpt_payments2").text();
 	var filename="<b><span style='font-size:15pt;'> Pending Collection Remarks | Admin</span></b></br>"+"<span style=font-size:13pt;>&nbsp;Selected Date&nbsp;:&nbsp;"+from+"</span><span style=font-size:13pt;>&nbsp;-&nbsp;"+to+"</span>";
 	var oTable = $('#pending_remarks_list').DataTable();

	     oTable.clear().draw();

			  	 if (payment!= null)

			  	  {  	
					  	oTable = $('#pending_remarks_list').dataTable({

				                "bDestroy": true,

				                "bInfo": true,

				                "bFilter": true,

				                "bSort": true,
				                
				                 "dom": 'lBfrtip',
				                 "lengthMenu":[
										 [ 10, 25, 50, -1 ],
										 [ '10 rows', '25 rows', '50 rows', 'Show all' ]
										],
           			             "buttons" : [
           			                      {
													   extend: 'print',
													   footer: true,
													   title: filename,
													   orientation: 'landscape',
													   customize: function ( win ) {
                            							    $(win.document.body).find('table')
                                                            .addClass('compact');
                                                    
                            								$(win.document.body).find( 'table' )
                            									.addClass('compact')
                            									.css('font-size','10px')
                            									.css('font-family','sans-serif');
                            								
                            							},
													 },
													 {
														extend:'excel',
														footer: true,
													    title: filename,
													  }
           			                 ],
						       "tableTools": { "buttons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },
                              

				                "aaData": payment,

				                "aoColumns": [    

					                { "mDataProp": "id_employee" },
                                    { "mDataProp": "date_created" },
					                { "mDataProp": "employee_name" },

					                { "mDataProp": "name" },
					                { "mDataProp": "mobile" },
					                { "mDataProp": "id_scheme_account" },
					                { "mDataProp": "scheme_acc_number" },
					                { "mDataProp": "remark" },

					               ]
                                
				            });			  	 	
					  	 }	
 }
 
 
 function show_scheme_details(key){
   $('#'+key).toggle();
   $('#'+key+'_icon').toggleClass("fa fa-plus fa fa-minus");

   $('.'+key).toggle();
   $('.'+key+'_icon').toggleClass("fa fa-plus fa fa-minus");
}
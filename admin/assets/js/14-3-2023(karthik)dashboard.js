var path =  url_params();
var ctrl_page = path.route.split('/');
let indianCurrency = Intl.NumberFormat('en-IN');
$(document).ready(function() 
 
{	
    
       //dashboard new work.... 
    
     $('#branch_select').select2().on("change", function(e) { 
        	if(this.value!='')
        	{   
        		$("#id_branch").val(this.value);    
        		var id_branch=$("#id_branch").val(); 
        		    if($('.tab-pane.active').attr('id') == "crm") 
        			 {
        					getindex();  
        			} 
        	}
        	else
        	{   
        	    $("#id_branch").val('');       
        	}
    });
    
    
    var date = new Date();
    var from_date=(date.getFullYear()+"-"+(date.getMonth() + 1)+"-"+date.getDate());
    var to_date=(date.getFullYear()+"-"+(date.getMonth() + 1)+"-"+date.getDate());
    $('#payment_list1').text(from_date);
	$('#payment_list2').text(to_date);
    
	//$('#payment_show1').text(moment().format('DD-MM-YYYY'));
    //$('#payment_show2').text(moment().format('DD-MM-YYYY'));
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
               startDate: moment(),
              endDate: moment()
            },
            function (start, end) {
              $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                  
                  $('#payment_list1').text(start.format('YYYY-MM-DD'));
                  //$('#payment_show1').text(start.format('DD-MM-YYYY'));
                  $('#payment_list2').text(end.format('YYYY-MM-DD')); 
                 // $('#payment_show2').text(end.format('DD-MM-YYYY')); 
                  $('#payment-dt-btn').text(start.format('D-MM-YYYY') + ' - ' + end.format('D-MM-YYYY'));

                  
    			   
    			   if($('.tab-pane.active').attr('id') == "crm") {
        				getindex();  
        				
        				 $('#payment_list tbody').remove();
                      $('#account_list tbody').remove();
                      $('#interWalList tbody').remove(); 
                    
                      $('#mob').empty();
                    
                        $('#web').empty();
                        $('#admin').empty();
                        $('#collection').empty();
                        $('#thr_m').empty();
                        $('#thr_w').empty();
                        $('#thr_a').empty();
                        $('#test').empty();
                        $('#all_reg').empty();
                        $('#registered').empty();
                        $('#not_registered').empty();
                        $('#cus_web').empty(); //date wise filter cus reg  by hh//
                        $('#cus_mob').empty();
                        $('#cus_admin').empty();
                        $('#thr_c').empty();
                        $('#cus_collection').empty();

        				
        			} 
        			
        			if($('.tab-pane.active').attr('id') == "live_cockpit")
        			{
        			    retail_dashboard_details(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
        				get_EstimationStatus(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
        				get_BillingStatus(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
        				get_GreentagSalesDetails(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
        				get_VitrualTagStatus(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
        				get_SalesreturnDetails(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
        				get_metal_purchase_status(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
        				get_CreditSalesDetails(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
        				get_GiftVoucherDetails(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
        				get_BillClassficationDetails(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
        				get_BranchTransferDetails(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
        				get_lot_tag_details(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
        				get_OrderDetails(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
        				get_StockDetails(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
        				get_ReorderDetails(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
        				get_KarigarOrderDetails();
        				get_CustomerOrderDetails();
        				get_MetalStockDetails();
        				get_RecentBillDetails(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
        				get_cash_abstract_details(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
        				getEstimationDetails(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
        				get_CustomerDetails(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
        				get_stock_details(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
        			}
        			
            }
        ); 

// ends..
 
   $("body").on("click","#tab_stock_and_branch_transfer", function()
    {	
    	let from_date =  $('#payment_list1').text();
    	let to_date   =  $('#payment_list2').text();
    	branch_transfer_details_dashboard_data(from_date,to_date);
    });
    
    $("body").on("click","#tab_livecockpit", function(){
		//retail_dashboard_details(from_date,to_date);
	    get_live_cockpit_dashboard_details();
	});
	
 $('#emp_select').select2().on("change", function(e){
    if(this.value!=''){
    var id_emp=this.value;
        if(ctrl_page[0] == 'admin_dashboard' && ctrl_page[1] == 'get_account'){
            get_scheme_account_list_bybranch(id_emp,ctrl_page[2],ctrl_page[3],ctrl_page[4]);
        }else if(ctrl_page[0] == 'admin_dashboard' && ctrl_page[1] == 'get_account_joined'){
            get_scheme_account_list_bysource(id_emp,ctrl_page[4],ctrl_page[2],ctrl_page[3]);
        }
    }
});   
    
if(ctrl_page[0] == 'admin_dashboard' && ctrl_page[1] == 'get_account'){
    
    var id_branch = ctrl_page[2];
    var from_date  = ctrl_page[3];
    var to_date = ctrl_page[4];
    
    if(id_branch != '' && from_date != '' && to_date!= ''){
        get_scheme_account_list_bybranch("",id_branch,from_date,to_date);
        get_employee_name(id_branch);
    }
}

if(ctrl_page[0] == 'admin_dashboard' && ctrl_page[1] == 'get_account_joined'){
    
    var added_by = ctrl_page[4];
    var from_date  = ctrl_page[2];
    var to_date = ctrl_page[3];
    
    if(added_by != '' && from_date != '' && to_date!= ''){
      get_scheme_account_list_bysource("",added_by,from_date,to_date);
      get_employee_name('');
    }
}


if(ctrl_page[0] == 'admin_dashboard' && ctrl_page[1] == 'get_payment_joined'){
    
    var added_by = ctrl_page[4];
    var from_date  = ctrl_page[2];
    var to_date = ctrl_page[3];
    
    
    
    if(added_by != '' && from_date != '' && to_date!= ''){
        
        $.ajax({           
				type: 'POST',            
				url: base_url+'index.php/admin_dashboard/ajax_get_payment_joined',            
				dataType: 'json',           
				async: false,                       
				data: {'added_by':added_by,'from_date':from_date,'to_date':to_date},           
				success: function (data)
				 {               

				    set_scheme_payment_list(data);
									                         
		
				 }           
		}); 
    }
}

// Created by RK - 15/12/2022
    if(ctrl_page[0] == 'admin_dashboard' && ctrl_page[1] =='customer_wishes')
    {
        if(ctrl_page[3]=='T')
        {
            var type=ctrl_page[2];
            var byfilter=ctrl_page[3];

            $.ajax({
                type: 'POST',            
                    url: base_url+'index.php/admin_dashboard/ajax_customer_wishes',            
                    dataType: 'json',           
                    async: false,                       
                    data: {'type':type,'byfilter':byfilter},           
                    success: function (data)
                     {       
                        console.log(data);        
    
                        set_customer_wish_list(data);
                                                                 
                     }           
    
            });
        }
        
    }
    
    
    function set_customer_wish_list(data)
    {
        var accounts = data.accounts;
           
        console.log(accounts);

        var oTable = $('#customer_list').dataTable();
        if (accounts!= null && accounts.length > 0)
        {
        
            oTable = $('#customer_list').dataTable({
                
                "bDestroy": true,

                "bInfo": true,

                "bFilter": true,

                "bSort": true,

                "dom": 'lBfrtip',
                
                "buttons" : ['excel','print'],
                   
                "tableTools": { "buttons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },
                

                "aaData": accounts,

                "order": [[ 0, "desc" ]],


               "aoColumns": [
                   
                    { "mDataProp": "id_customer" },
                    { "mDataProp": "cus_name" },
                    { "mDataProp": "cus_mobile" },
                    { "mDataProp": "village_name" },
                    { "mDataProp": "date_of_birth" },
                    { "mDataProp": "date_of_wed" },
                    { "mDataProp": "total_gold_wt" },
                    { "mDataProp": "total_silver_wt" },
                    { "mDataProp": "active_acc" },
                    { "mDataProp": "closed_count" }

               ]

            });

        }
    }
    //Created by RK - 15/12/2022--- ends here


function get_scheme_account_list_bysource(id_employee="",added_by,from_date,to_date){
      $.ajax({           
				type: 'POST',            
				url: base_url+'index.php/admin_dashboard/ajax_get_account_joined',            
				dataType: 'json',           
				async: false,                       
				data: {'added_by':added_by,'from_date':from_date,'to_date':to_date,'id_employee':id_employee},           
				success: function (data)
				 {               

				    set_scheme_account_list(data);
									                         
				 }           
		}); 
        
}

function get_scheme_account_list_bybranch(id_employee="",id_branch,from_date,to_date){
  
    $.ajax({           
				type: 'POST',            
				url: base_url+'index.php/admin_dashboard/ajax_get_account',            
				dataType: 'json',           
				async: false,                       
				data: {'id_branch':id_branch,'from_date':from_date,'to_date':to_date,'id_employee':id_employee},           
				success: function (data)
				 {               

				    set_scheme_account_list(data);
									                         
				 }           
		}); 
    
}



	$('#mobilenumber').val('');
   $('body').addClass("sidebar-collapse");
	$("#print").val('1');
	 $('#mob_submit').on('click',function(){
	    var mobile_number=$('#mobilenumber').val();    
	    console.log(mobile_number);
	$.ajax({           
				type: 'POST',            
				url: base_url+'index.php/admin_customer/get_customer_by_mobile',            
				dataType: 'json',           
				async: false,                       
				data: {'mobile':mobile_number},           
				success: function (data)
				 {               
					if(data.result==true)
					{
						if($('#mobilenumber').val().length==10)	
						{
    				window.location.href= base_url+'index.php/customer/customer_edit/'+mobile_number;
    						} 					
    				}
    				else
    				{
    					alert('please Enter a Valid Number');
    					$('#mobilenumber').val('')
    				}				                         
				 }           
				
			});         
    });
    if($('#branch_set').val()==1  ){		
    //get_branchname();
    var date = new Date();
    var from_date=(date.getFullYear()+"-"+(date.getMonth() + 1)+"-"+date.getDate());
    var to_date=(date.getFullYear()+"-"+(date.getMonth() + 1)+"-"+date.getDate());
    
    get_payment_list(from_date,to_date);
    get_account_list(from_date,to_date);
    get_customer_list(from_date,to_date);
    get_collection_list();	
    get_collection_summary();
    inter_wallet_list(from_date,to_date);	
    get_register_list();	
    //$('#payment_list1').empty();
    //$('#payment_list2').empty();
	
    $("#detailed").on('click',function(){
        $("#collDetail").css('display','block');
        $("#collSummary").css('display','none');
        $("#print").val('2');
       
    });
    
    $("#summary").on('click',function(){
        $("#collDetail").css('display','none');
        $("#collSummary").css('display','block');
		$("#print").val('1');
        
    });
    
   
   
    
   $("#rtl").on('click',function(){ 
   		$("#retail").css('display','block');
   		$("#crm").css('display','none');
   })
   $("#crmBtn").on('click',function(){
   		$("#retail").css('display','none');
   		$("#crm").css('display','block');
   })
   $("#print").on('click',function(){
	   
        if($("#print").val()==1)
		{	
			newWin= window.open("");
			var divToPrint=document.getElementById("collSummary");
			
			newWin.document.write(divToPrint.outerHTML);
			newWin.document.title = 'Summary Collection Report';
		   newWin.print();
		   newWin.close();
		}
		else
		{
			newWin= window.open("");
			var divToPrint=document.getElementById("collDetail");
			
			 newWin.document.write(divToPrint.outerHTML);
			  newWin.document.title = 'Detailed Collection Report';
		   newWin.print();
		   newWin.close();
		}
		
		   
    });
    

        
//23-12-2022

/*console.log($('.tab-pane.active').attr('id'));

	        
    
	$('#payment_list1').text(moment().startOf('month').format('YYYY-MM-DD'));
	$('#payment_list2').text(moment().endOf('month').format('YYYY-MM-DD'));	
        $('#payment-dt-btn').daterangepicker({
              ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
              },
               startDate: moment(),
              endDate: moment()
        },
        function (start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            $('#payment_list1').text(start.format('YYYY-MM-DD'));
            $('#payment_list2').text(end.format('YYYY-MM-DD')); 

            if($('.tab-pane.active').attr('id') == "crm") {
                
                $('#payment_list tbody').remove();
                $('#account_list tbody').remove();
                $('#interWalList tbody').remove(); 
				getindex();  
				
			} 

              

			if($('.tab-pane.active').attr('id') == "live_cockpit") {
			    retail_dashboard_details(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
				get_EstimationStatus(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
				get_BillingStatus(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
				get_GreentagSalesDetails(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
				get_VitrualTagStatus(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
				get_SalesreturnDetails(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
				get_metal_purchase_status(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
				get_CreditSalesDetails(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
				get_GiftVoucherDetails(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
				get_BillClassficationDetails(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
				get_BranchTransferDetails(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
				get_lot_tag_details(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
				get_OrderDetails(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
				get_StockDetails(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
				get_ReorderDetails(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
				get_KarigarOrderDetails();
				get_CustomerOrderDetails();
				get_MetalStockDetails();
				get_RecentBillDetails(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
				get_cash_abstract_details(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
				getEstimationDetails(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
				get_CustomerDetails(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
				
			}

           
			
			  if($('.tab-pane.active').attr('id') == "sales") {
				sales_dashboard_data(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
			  }
			 
			  if($('.tab-pane.active').attr('id') == "order_management") {
			     
				    get_order_management_details();
			  }

              
            
                $('#mob').empty();
            
                $('#web').empty();
                $('#admin').empty();
                $('#collection').empty();
                $('#thr_m').empty();
                $('#thr_w').empty();
                $('#thr_a').empty();
                $('#test').empty();
                $('#all_reg').empty();
                $('#registered').empty();
                $('#not_registered').empty();
                $('#cus_web').empty(); //date wise filter cus reg  by hh//
                $('#cus_mob').empty();
                $('#cus_admin').empty();
                $('#thr_c').empty();
                $('#cus_collection').empty();
                
	
	 
          }
        ); 	
*/
        
        
        
        
        
        
 }	

var line = new Morris.Line

		({   
			element: 'rate-chart',    
			resize: true,    
			data: getRateData(),    
			xkey: 'updatetime',    
			ykeys: ['rate'],    
			labels: ['Gold 22ct'],    
			lineColors: ['#efefef'],   
			lineWidth: 2,   
			hideHover: 'auto',    
			gridTextColor: "#fff",    
			gridStrokeWidth: 0.4,   
			pointSize: 4,    
			pointStrokeColors: ["#efefef"],    
			gridLineColor: "#efefef",    
			gridTextFamily: "Open Sans",    
			gridTextSize: 10  
			
		});				
			
	});	
			
function getRateData()
{
	var data = "";       
	$.ajax({           
				type: 'GET',            
				url: base_url+'index.php/rate/ajax/weekstat',            
				dataType: 'json',           
				async: false,                       
				data: {},           
				success: function (result) {               
									data = result;                           
									},           
				error: function (xhr, status, error) 
				{                
				console.log(error);           
				}        
			
			});         
			return data;
}

    function get_branchname(){	
     	$(".overlay").css('display','block');	
     	$.ajax({		
         	type: 'GET',		
         	url: base_url+'index.php/branch/branchname_list',		
         	dataType:'json',		
         	success:function(data){		
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
             	$("#branch_select").select2("val",(branch_id!=''?branch_id:''));
             	$(".overlay").css("display", "none");			
         	}	
        }); 
    }

/*$('#branch_select').select2().on("change", function(e) { 
	if(this.value!='')
	{   
		  

		$("#id_branch").val(this.value);    
		var id_branch=$("#id_branch").val(); 
		    if($('.tab-pane.active').attr('id') == "crm") 
			 {
					getindex();  
			} 
		
		
			
	}
	else
	{   
	$("#id_branch").val('');       
	}
}); */

 
	
	function getindex()
	{        
		$("div.overlay").css("display", "block"); 
		var id_branch=$("#id_branch").val();   
		var from_date = $('#payment_list1').html();	
		var to_date = $('#payment_list2').html();              
		$.ajax({                          
		type: "POST",                          
		url: base_url+"index.php/admin_dashboard/dashboard",                         
		data: {'id_branch':id_branch,'from_date':from_date,'to_date':to_date},                       
		sync:false,						  						  
		dataType: 'json',                          
		success: function(response)
		{         
		    
		     //12-12-2022,AB
			get_account_list(from_date,to_date);	
			get_payment_list(from_date,to_date);
			get_customer_list(from_date,to_date);
		    get_collection_list(from_date,to_date);
            get_collection_summary(from_date,to_date); 
		    
		    
				//payments
				
				
				
		   $("#test").text(response.payment.today.paid);
		   $("#tot_pay").text(indianCurrency.format(response.payment.today.paid));
		   $("#t_paid").text(response.payment.today.paid);
		   $("#y_paid").text(response.payment.yesterday.paid);
		   $("#tw_paid").text(response.payment.week.paid);
		   $("#tm_paid").text(response.payment.month.paid);
		   $("#awaiting").text(response.payment.awaiting.paid);
		   $("#thr_a").text(response.payment.admin_paid.joined_thro);
		   $("#thr_w").text(response.payment.web_paid.joined_thro);
		   $("#thr_m").text(response.payment.mob_paid.joined_thro);
		   $("#thr_c").text(response.payment.collection_paid.joined_thro);
		   //acounts
		   $("#wk_reg").text(response.account.wk_reg);
		   $("#yes_reg").text(response.account.yes_reg);
		   $("#m_reg").text(response.account.m_reg);
		   $("#today_reg").text(response.account.today_reg);
		   $("#all_acc").text(response.account.all_reg);
		   $("#all_reg").text(response.account.all_reg);
		   $("#mob_reg").text(response.account.mob.joined_thro);
		   $("#w_reg").text(response.account.web.joined_thro);
		   $("#a_reg").text(response.account.admin.joined_thro);
		   $("#acc_wo_pay").text(response.account.acc_wo_pay);
		   $("#c_reg").text(response.account.collection.joined_thro);
			//inter wallet 
		   $("#t_trans").text(response.inter_wallet.t_trans);
		   $("#t_redeem_trans").text(response.inter_wallet.t_redeem_trans);
		   $("#y_trans").text(response.inter_wallet.y_trans);
		   $("#y_redeem_trans").text(response.inter_wallet.y_redeem_trans);
		   $("#tw_trans").text(response.inter_wallet.tw_trans);
		   $("#tw_redeem_trans").text(response.inter_wallet.tw_redeem_trans);
		   $("#tm_trans").text(response.inter_wallet.tm_trans);
		   $("#tm_redeem_trans").text(response.inter_wallet.tm_redeem_trans);
			//closed
			  $("#closed").text(response.closed);
			  $("#renewal").text(response.renewal);
			  $("#two_pending").text(response.two_pending);
			  $("#one_pending").text(response.one_pending);
		 		//existing		
			$("#e_all_reg").text(response.existing_request.all_reg);
			$("#total_request").text(response.existing_request.total_request);
			$("#exiting_processing").text(response.existing_request.exiting_processing);
			$("#exiting_approved").text(response.existing_request.exiting_approved);
			$("#exiting_rejected").text(response.existing_request.exiting_rejected);
				$("div.overlay").css("display", "none"); 
		},
			  error:function(error)  
				{
					$("div.overlay").css("display", "none"); 
				}	
	}); 
	}                        

function get_payment_list(from_date="",to_date="")
{
     var id_branch = $('#branch_select').val();
	my_Date = new Date();
	// $("div.overlay").css("display", "block"); 
	$.ajax({
	  url:base_url+ "index.php/admin_dashboard/payment_status?nocache=" + my_Date.getUTCSeconds(),
	 //data: (from_date !='' && to_date !=''? {'from_date':from_date,'to_date':to_date,'id_branch':id_branch}: ''),
	  data:{'from_date':from_date,'to_date':to_date,'id_branch':id_branch},
	 dataType:"JSON",
	 type:"POST",
	 success:function(data){
	 	set_payment_list(data,from_date,to_date);
	 	
	  },
	  error:function(error)  
	  {
	  $("div.overlay").css("display", "none"); 
	  }	 
	  });
}

function set_payment_list(data,from_date,to_date)   // Sch Pay & Act Paid Split n Showed in Dashboard//HH
{
     //23-12-2022,AB
    $('#payment_list').empty();
	 $('#thr_a').empty();
	 $('#thr_w').empty();
	 $('#thr_m').empty();
	 $('#thr_c').empty();
	 $('#thr_aw').empty();
	 $('#thr_ww').empty();
	 $('#thr_mw').empty();
	 $('#thr_cw').empty();
	 
	 
		var sum=0;
		var divide=0;
		$.each(data.payment_status,  function (index, element) { 

				id_branch=element.id_branch;
			 sum += Number(element.paid);
				// sum += Number(element.act_paid);
			var num_parts = element.paid.toString().split(".");
		    num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		    var paid =num_parts.join(".");

            $("#test").text(sum);
			$("#tot_pay").text(indianCurrency.format(sum));
            list_url=base_url+'index.php/admin_dashboard/get_payment/'+id_branch +"/"+from_date +"/"+to_date; 
            $('#payment_list').append(
            $('<tbody><tr>').append($('<td style="text-align: left;padding: 5px;">').append(element.short_name))
            
            // .append($('<td style="padding: 5px;">').append(element.currency_symbol+' '+element.paid))
            .append($('<td>').append('<a href='+list_url+'><span class="badge bg-green">'+element.id_payment+'</span></a>')).append($('<td style="padding: 5px; text-align:right;">').append('<span> &#8377;</span>'+' '+indianCurrency.format(element.paid)))
            );
            var rowCount = $('#payment_list td').length;
            });
            
           /* online_list_url=base_url+'index.php/admin_dashboard/get_online_payment/'+from_date +'/'+to_date;
            $('#payment_list').append(
            $('<tbody><tr>').append($('<td style="text-align: left;padding: 5px;">').append(data.online.branch_name))
            .append($('<td style="padding: 5px;">').append("INR"+' '+data.online.paid))
            .append($('<td style="padding: 5px;">').append("INR"+' '+data.online.paid))
            .append($('<td>').append('<a href='+online_list_url+'><span class="badge bg-green">'+data.online.count+'</span></a>'))
            ); */
                   
	  		admin_url=base_url+'index.php/admin_dashboard/get_payment_joined/'+from_date+"/"+to_date+"/"+0; 
			web_url=base_url+'index.php/admin_dashboard/get_payment_joined/'+from_date+"/"+to_date+"/"+1; 
			app_url=base_url+'index.php/admin_dashboard/get_payment_joined/'+from_date+"/"+to_date+"/"+2; 
			collection_url=base_url+'index.php/admin_dashboard/get_payment_joined/'+from_date+"/"+to_date+"/"+3;
	  		// $("#").text(data.mob_paid.joined_thro);
		   // $("#web_paid").text(data.web_paid.joined_thro);
		   // $("#admin_paid").text(data.admin_paid.joined_thro);

            $("#thr_a").append('<a href='+admin_url+'><span class="badge bg-green">'+data.admin_paid.joined_thro+'</span></a>')
			$("#thr_aw").append('<span style="margin-left: 50px;">'+'&#8377;'+' '+indianCurrency.format(data.admin_paid.amount)+'</span>');
            $("#thr_w").append('<a href='+web_url+'><span class="badge bg-green">'+data.web_paid.joined_thro+'</span></a>')
			$("#thr_ww").append('<span style="margin-left: 50px;">'+'&#8377;'+' '+indianCurrency.format(data.web_paid.amount)+'</span>');
            $("#thr_m").append('<a href='+app_url+'><span class="badge bg-green">'+data.mob_paid.joined_thro+'</span></a>')
			$("#thr_mw").append('<span style="margin-left: 50px;">'+'&#8377;'+' '+indianCurrency.format(data.mob_paid.amount)+'</)span>');
            $("#thr_c").append('<a href='+collection_url+'><span class="badge bg-green">'+data.collection_paid.joined_thro+'</span></a>')
			$("#thr_cw").append('<span style="margin-left: 50px;">'+'&#8377;'+' '+indianCurrency.format(data.collection_paid.amount)+'</span>');
	
	 
}


function get_account_list(from_date="",to_date="")
{
    var id_branch = $('#branch_select').val();
	my_Date = new Date();
	// $("div.overlay").css("display", "block"); 
	$.ajax({
	  url:base_url+ "index.php/admin_dashboard/account_status?nocache=" + my_Date.getUTCSeconds(),
	 //data: (from_date !='' && to_date !=''? {'from_date':from_date,'to_date':to_date,'id_branch':id_branch}: ''),
	 data:{'from_date':from_date,'to_date':to_date,'id_branch' : id_branch},
	 dataType:"JSON",
	 type:"POST",
	 success:function(data){
	 	set_account_list(data,from_date,to_date);
	 	
	  },
	  error:function(error)  
	  {
	  $("div.overlay").css("display", "none"); 
	  }	 
	  });
}

function set_account_list(data,from_date,to_date)
{

	 	//23-12-2022,AB
	 $('#account_list tbody').remove();
	 $('#mob').empty();
	 $('#mob_c').empty();
	 $('#admin').empty();
	 $('#admin_c').empty();
	 $('#web').empty();
	 $('#web_c').empty();
	 $('#collection').empty();
	 $('#collection_c').empty();

		
		var sum=0;
	
		$.each(data.account_stat, function (index, element) 
		{
					 sum += Number(element.total);
					 
					  $("#all_acc").text(sum);
					  $("#all_reg").text(sum);
					   id_branch=element.id_branch;
			$.each(data.acc_wo_pay, function (index, element)
			{
				acc_wo_pay=element.count;
				

			}); 	 
		list_url=base_url+'index.php/admin_dashboard/get_account/'+id_branch +"/"+from_date +"/"+to_date; 
		 	 $('#account_list').append(
                  $('<tbody><tr>').append($('<td style="text-align: left;padding: 5px;">').append(element.short_name))
                    .append($('<td>').append('<a href='+list_url+'><span class="badge bg-green">'+element.total+'</span></a>'))
                    .append($('<td style="text-align: right;padding: 5px;">').append('&#8377; '+indianCurrency.format(element.amount)))
                    .append($('<td style="text-align: right;padding: 5px;">').append(element.weight))
                  );
  
		 
		 	
	  	});

			admin_url=base_url+'index.php/admin_dashboard/get_account_joined/'+from_date+"/"+to_date+"/"+1; 
			web_url=base_url+'index.php/admin_dashboard/get_account_joined/'+from_date+"/"+to_date+"/"+0; 
			app_url=base_url+'index.php/admin_dashboard/get_account_joined/'+from_date+"/"+to_date+"/"+2; 
			collection_url=base_url+'index.php/admin_dashboard/get_account_joined/'+from_date+"/"+to_date+"/"+3;

		    $("#mob").append('<a href='+app_url+'><span class="badge bg-aqua">'+data.mob.joined_thro+'</span></a>')
			$("#mob_c").append('<span style="margin-left: 40px;">'+'&#8377;'+' '+indianCurrency.format(data.mob.amount)+'</span>');
            $("#web").append('<a href='+web_url+'><span class="badge bg-aqua">'+data.web.joined_thro+'</span></a>')
			$("#web_c").append('<span style="margin-left: 40px;">'+'&#8377;'+' '+indianCurrency.format(data.web.amount)+'</span>');
            $("#admin").append('<a href='+admin_url+'><span class="badge bg-aqua">'+data.admin.joined_thro+'</span></a>')
			$("#admin_c").append('<span style="margin-left: 40px;">'+'&#8377;'+' '+indianCurrency.format(data.admin.amount)+'</span>');
            $("#collection").append('<a href='+collection_url+'><span class="badge bg-aqua">'+data.collection.joined_thro+'</span></a>')
			$("#collection_c").append('<span style="margin-left: 40px;">'+'&#8377;'+' '+indianCurrency.format(data.collection.amount)+'</span>');

}



function inter_wallet_list(from_date="",to_date="")
{

	my_Date = new Date();
	// $("div.overlay").css("display", "block"); 
	$.ajax({
	  url:base_url+ "index.php/admin_dashboard/inter_wallet_status?nocache=" + my_Date.getUTCSeconds(),
	 data:{'from_date':from_date,'to_date':to_date},
	 dataType:"JSON",
	 type:"POST",
	 success:function(data){
	 	set_interwallet_list(data,from_date,to_date);
	 	
	  },
	  error:function(error)  
	  {
	  $("div.overlay").css("display", "none"); 
	  }	 
	  });
}

function set_interwallet_list(data,from_date,to_date)
{
	 	 $.each(data, function (index, element) 
		 {
		     if(parseFloat(element.credit) > 0 || parseFloat(element.debit) > 0 ){
		        id_branch = element.id_branch;
    			credit_url = base_url+'index.php/admin_dashboard/inter_wallet_transcation_details/'+id_branch+'/'+from_date+"/"+to_date+"/"+1; 
    			redeem_url = base_url+'index.php/admin_dashboard/inter_wallet_transcation_details/'+id_branch+'/'+from_date+"/"+to_date+"/"+2; 
    			$('#interWalList').append(
                  $('<tbody><tr>').append($('<td style="text-align: left;padding: 5px;">').append(element.branch_name))
                  .append($('<td>').append('<a href='+credit_url+'><span class="badge bg-purple">'+element.currency_symbol+' '+element.credit+'</span></a>'))
                  .append($('<td>').append('<a href='+redeem_url+'><span class="badge bg-purple">'+element.currency_symbol+' '+element.debit+'</span></a>'))
                );
		     }
		 });
		 
		 

		 /* $.each(data.inter_wallet_redeem, function (index, element) 
		 {
	
		 	id_branch=element.id_branch;
		 	redeem_url=base_url+'index.php/admin_dashboard/inter_wallet_transcation_details/'+id_branch+'/'+from_date+"/"+to_date+"/"+2; 
			$('#redeem_list').append(
                  $('<tbody><tr>').append($('<td style="text-align: left;padding: 5px;">').append(element.branch_name))
                  
                  .append($('<td>').append('<a href='+redeem_url+'><span class="badge bg-purple">'+element.total+'</span></a>'))
                  );
		 });*/
		
}

function get_collection_list(from_date="",to_date="")
{

	my_Date = new Date();
	// $("div.overlay").css("display", "block"); 
	$.ajax({
	  	  url:base_url+ "index.php/admin_dashboard/ajax_collectionData?nocache=" + my_Date.getUTCSeconds(),
    	 data:{'date':from_date,'to_date':to_date},
    	 dataType:"JSON",
    	 type:"POST",
    	 success:function(data){
    	 	set_collection_list(data,from_date,to_date);
    	 	
    	  },
    	  error:function(error)  
    	  {
    	  $("div.overlay").css("display", "none"); 
    	  }	 
	  });
}




function set_collection_list(data,from_date,to_date)
{


        /*collection_url= base_url+'index.php/admin_dashboard/get_payment/'+id_branch+'/'+from_date+"/"+to_date;
		closed_collection =base_url+'index.php/admin_manage/ajax_close_account_list/'+id_branch+'/'+from_date+"/"+to_date;
		cancelled_list =base_url+'index.php/admin_dashboard/get_cancelled_payment/'+id_branch+'/'+from_date+"/"+to_date;
		*/	
		$('#collection_list tbody').remove();
        $.each(data.pay_collection,  function (index, element) { 
        var closing_balance=0;
        closing_balance = (parseFloat(element.opening_bal)+parseFloat(element.collection)-parseFloat(element.closing)); 
        id_branch = element.id_branch; 
		/*var opening_bal = element.opening_bal.toString().split(".");
		    opening_bal[0] = opening_bal[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		    var opening_bal =opening_bal.join(".");*/
        //list_url=base_url+'index.php/admin_dashboard/get_payment/'+id_branch +"/"+from_date +"/"+to_date; 
        $('#collection_list').append(
        $('<tbody><tr>').append($('<td style="text-align: left;padding: 5px;">').append(element.branch_name))
           		.append($('<td style="padding: 5px;text-align: right;">').append(element.currency_symbol+' '+element.opening_bal))
           		.append($('<td style="padding: 5px;text-align: right;">').append(element.currency_symbol+' '+element.collection))
           		.append($('<td style="padding: 5px;text-align: right;">').append(element.currency_symbol+' '+element.closing))
           		.append($('<td style="padding: 5px;text-align: right;">').append(element.currency_symbol+' '+closing_balance))
           		
        );
        
        
        });

}

function get_register_list()
{

	my_Date = new Date();
	// $("div.overlay").css("display", "block"); 
	$.ajax({
	  	  url:base_url+ "index.php/admin_dashboard/regisert_list?nocache=" + my_Date.getUTCSeconds(),
    	
    	 dataType:"JSON",
    	 type:"POST",
    	 success:function(data){
    	 	set_registered_list(data);
    	 	
    	  },
    	  error:function(error)  
    	  {
    	  $("div.overlay").css("display", "none"); 
    	  }	 
	  });
}

function set_registered_list(data)
{

		
       registered=base_url+'index.php/admin_dashboard/inter_wallet_accounts_detail/'; 
		not_registered=base_url+'index.php/admin_dashboard/inter_wallet_accounts__woc/'; 
			
		  $("#registered").append($('<td>').append('<a href='+registered+'><span class="badge bg-aqua">'+data.inter_wallet_accounts+'</span></a>'));
		  $("#not_registered").append($('<td>').append('<a href='+not_registered+'><span class="badge bg-aqua">'+data.inter_wallet_accounts_woc+'</span></a>'));

}

// Coded by KVP for daily collection summary
function get_collection_summary(from_date="",to_date="")
{

	my_Date = new Date();  
	$.ajax({
	  	  url:base_url+ "index.php/admin_dashboard/ajax_daily_collection?nocache=" + my_Date.getUTCSeconds(),
    	 data:{'date':from_date,'to_date':to_date},
    	 dataType:"JSON",
    	 type:"POST",
    	 success:function(data){ 
    	     console.log(data);
    	 	set_collection_summary(data,from_date,to_date); 
    	  },
    	  error:function(error)  
    	  {
    	  $("div.overlay").css("display", "none"); 
    	  }	 
	  });
}

function set_collection_summary(data,from_date,to_date)
{
    
 
		$('#collDetail tbody').remove();
		$('#collSummary tbody').remove();
		 var closing_total=0;
		 var op_total=0;
		 var closed_total=0;
		 var cancelled_total=0;
		 var closing_balance_total=0;
		 var op_total_amt=0;var op_total_wt=0;var collection_tot_amt=0;
		 var op_total_wt_scheme=0;var collection_tot_wt=0;var today_collection_wgt=0;var closed_total_amt=0;
		 var closed_total_wt=0;var closed_total_wt_scheme=0;var cancelled_total_amt=0;var cancelled_total_wt=0;
		 var cancelled_total_wt_scheme=0;
		 var closing_balance_total_amt=0;
		 var closing_balance_total_wt=0;
		 var closing_balance_total_wt_scheme=0;
		//if(data.type == 1){

		    $.each(data.collection,  function (index, element) {  // Summary
				
               id_branch = element.id_branch; 
               from_date = element.date;
               to_date = element.date
			 
			  
			collection_url= base_url+'index.php/admin_dashboard/get_payment/'+id_branch+'/'+from_date+"/"+to_date;
			closed_collection =base_url+'index.php/admin_manage/ajax_close_account_list/'+id_branch+'/'+from_date+"/"+to_date;
			cancelled_list =base_url+'index.php/admin_dashboard/get_cancelled_payment/'+id_branch+'/'+from_date+"/"+to_date;
			
				 total_collection=parseFloat(element.today_collection_amt)+parseFloat(element.today_collection_wgt);
				 closing_total+=Number(total_collection);	
				 
				 opening_total=parseFloat(element.opening_blc_amt)+parseFloat(element.opening_blc_wgt);
				 op_total+=Number(opening_total);
				 
				 closed=parseFloat(element.amtSchClosedAmt)+parseFloat(element.wgtSchClosedAmt);
				 closed_total+=Number(closed);
				 
				 cancelled=parseFloat(element.today_cancelled_amt)+parseFloat(element.today_cancelled_wgt);
				 cancelled_total+=Number(cancelled);

				closing_bal=parseFloat(element.closing_balance_amt)+parseFloat(element.closing_balance_wgt);
				 closing_balance_total+=Number(closing_bal);
				 
				 detailed_op=parseFloat(element.opening_blc_amt);
				 op_total_amt+=Number(detailed_op);
				 
				 opening_blc_wgt=parseFloat(element.opening_blc_wgt);
				 op_total_wt+=Number(opening_blc_wgt);
				 
				  opening_weight=parseFloat(element.opening_weight);
				 op_total_wt_scheme+=Number(opening_weight);
				 
				  today_collection_amt=parseFloat(element.today_collection_amt);
				 collection_tot_amt+=Number(today_collection_amt);
				 
				  today_collection=parseFloat(element.today_collection_wgt);
				 today_collection_wgt+=Number(today_collection);

				today_weight=parseFloat(element.today_weight);
				 collection_tot_wt+=Number(today_weight);
				 
				 amtSchClosedAmt=parseFloat(element.amtSchClosedAmt);
				 closed_total_amt+=Number(amtSchClosedAmt);
				 
				  wgtSchClosedAmt=parseFloat(element.wgtSchClosedAmt);
					closed_total_wt+=Number(wgtSchClosedAmt);
				 
				 wgtSchClosedWgt=parseFloat(element.wgtSchClosedWgt);
				 closed_total_wt_scheme+=Number(wgtSchClosedWgt);
				 
				 today_cancelled_amt=parseFloat(element.today_cancelled_amt);
				 cancelled_total_amt+=Number(today_cancelled_amt);
				 
				  today_cancelled_wgt=parseFloat(element.today_cancelled_wgt);
				 cancelled_total_wt+=Number(today_cancelled_wgt);
				 
				 
				 weight_cancelled=parseFloat(element.weight_cancelled);
				 cancelled_total_wt_scheme+=Number(weight_cancelled);


				closing_balance_amt=parseFloat(element.closing_balance_amt);
				 closing_balance_total_amt+=Number(closing_balance_amt);
				 
				  closing_balance_wgt=parseFloat(element.closing_balance_wgt);
				 closing_balance_total_wt+=Number(closing_balance_wgt);
				 
				 
				 closing_weight=parseFloat(element.closing_weight);
				 closing_balance_total_wt_scheme+=Number(closing_weight);
				 
				 collection_op_balance=((parseFloat(element.opening_blc_amt)+parseFloat(element.opening_blc_wgt)).toFixed(2));

				 
				$('#closing_total').text(get_convert((closing_total).toFixed(2)));
				$('#op_total').text(get_convert(op_total.toFixed(2)));
				$('#closed_total').text(get_convert(closed_total.toFixed(2)));
				$('#cancelled_total').text(get_convert(cancelled_total.toFixed(2)));
				$('#closing_balance_total').text(get_convert(closing_balance_total.toFixed(2)));
				$('#op_total_amt').text(get_convert(op_total_amt.toFixed(2)));
				$('#op_total_wt').text(get_convert(op_total_wt.toFixed(2)));
				$('#op_total_wt_scheme').text((op_total_wt_scheme).toFixed(3)+'g');
				$('#collection_tot_amt').text(get_convert(collection_tot_amt.toFixed(2)));
				$('#today_collection_wgt').text(get_convert((today_collection_wgt).toFixed(2)));
				$('#collection_tot_wt').text((collection_tot_wt).toFixed(3)+'g');
				$('#closed_total_amt').text(get_convert((closed_total_amt).toFixed(2)));
				$('#closed_total_wt').text(get_convert(closed_total_wt.toFixed(2)));
				$('#closed_total_wt_scheme').text((closed_total_wt_scheme).toFixed(3)+'g');
				$('#cancelled_total_amt').text(get_convert((cancelled_total_amt).toFixed(2)));
				$('#cancelled_total_wt').text((cancelled_total_wt).toFixed(2));
				$('#cancelled_total_wt_scheme').text((cancelled_total_wt_scheme).toFixed(3)+'g');
				$('#closing_balance_total_amt').text(get_convert(closing_balance_total_amt.toFixed(2)));
				$('#closing_balance_total_wt').text(get_convert((closing_balance_total_wt).toFixed(2)));
				$('#closing_balance_total_wt_scheme').text((closing_balance_total_wt_scheme).toFixed(3)+'g');
				
				if(element.branch_name != 'HEAD OFFICE'){
                    $('#collSummary').append(
                    $('<tbody><tr>').append($('<td class="branch" style="text-align: left;padding: 5px;">').append(element.branch_name))
    						
    
                       		.append($('<td class="price" style="padding:5px;text-align: right;color:#605ca8 ;">').append(get_convert(collection_op_balance)))
    						
    						.append($('<td style="padding: 5px;text-align: right;color:#00a65a ;text-decoration:none !important;">').append('<a style="text-decoration:none !important"; href='+collection_url+'>'
    						+get_convert((total_collection.toFixed(2)))+'</a>'))
    						
                       		.append($('<td style="padding: 5px;text-align: right;">').append('<a style="text-decoration:none !important"; href='+closed_collection+'>'+get_convert(((parseFloat(element.amtSchClosedAmt)+parseFloat(element.wgtSchClosedAmt)).toFixed(2)))+'</a>'))
                       		.append($('<td style="padding: 5px;text-align: right;">').append('<a style="text-decoration:none !important"; href='+cancelled_list+'>'+get_convert(((parseFloat(element.today_cancelled_amt)+parseFloat(element.today_cancelled_wgt)).toFixed(2)))+'</a>'))
                       		.append($('<td style="padding: 5px;text-align: right;color:#0073b7 ;">').append(
    						get_convert((parseFloat(element.closing_balance_amt)+parseFloat(element.closing_balance_wgt)).toFixed(2))))
                    );
				}
            }); 
		   $.each(data.collection,  function (index, element) {  // Detailed report 
               from_date = element.date;
               to_date = element.date
               
               id_branch = element.id_branch; 
				amt_scheme =base_url+'index.php/admin_dashboard/get_payment/'+id_branch+'/'+from_date+"/"+to_date+"/"+'0';
				wt_scheme =base_url+'index.php/admin_dashboard/get_payment/'+id_branch+'/'+from_date+"/"+to_date+"/"+'1';
				
				wt_scheme_closed =base_url+'index.php/admin_manage/ajax_close_account_list/'+id_branch+'/'+from_date+"/"+to_date+"/"+'1';
				amt_scheme_closed =base_url+'index.php/admin_manage/ajax_close_account_list/'+id_branch+'/'+from_date+"/"+to_date+"/"+'0';
				
				amt_scheme_canceled =base_url+'index.php/admin_dashboard/get_cancelled_payment/'+id_branch+'/'+from_date+"/"+to_date+"/"+'0';
				wt_scheme_canceled =base_url+'index.php/admin_dashboard/get_cancelled_payment/'+id_branch+'/'+from_date+"/"+to_date+"/"+'1';
				
				if(element.branch_name != 'HEAD OFFICE'){
                    $('#collDetail').append(
                    $('<tbody><tr>').append($('<td style="text-align: left;padding: 5px;">').append(element.branch_name))
                       		.append($('<td style="padding: 5px;text-align: right;color:#605ca8 ;">').
    						append(get_convert(element.opening_blc_amt)))
                       		.append($('<td style="padding: 5px;text-align: right;color:#605ca8 ;">').append
    						(get_convert(element.opening_blc_wgt)+'<br/>'+element.opening_weight+' g'))
                       		
                       		.append($('<td style="padding: 5px;text-align: right;">').append('<a style="color:#00a65a;text-decoration:none !important;" href='+amt_scheme+'>'
    						 +(get_convert(parseFloat(element.today_collection_amt).toFixed(2)))+'</a><br/>&nbsp;'))
                       		.append($('<td style="padding: 5px;text-align: right;color:#00a65a ;">').append('<a style="color:#00a65a;text-decoration:none !important;" href='+wt_scheme+'>'+(
    						get_convert(parseFloat(element.today_collection_wgt).toFixed(2)))+'<br/>'+
    						element.today_weight+'</a> g'))
                       		
                       		.append($('<td style="padding: 5px;text-align: right;color:#ff851b;">').append('<a style="color:#ff851b;text-decoration:none !important;" href='+amt_scheme_closed+'>'+
    						(get_convert(parseFloat(element.amtSchClosedAmt).toFixed(2)))+'</a><br/>&nbsp;'))
    						.append($('<td style="padding: 5px;text-align: right;color:#ff851b;">').append('<a style="color:#ff851b;text-decoration:none !important;" href='+wt_scheme_closed+'>'+
    						(get_convert(parseFloat(element.wgtSchClosedAmt).toFixed(2)))+'<br/>'+element.wgtSchClosedWgt+'</a> g'))
    						
                       		
                       		.append($('<td style="padding: 5px;text-align: right;color:#dd4b39;">').append('<a style="color:#dd4b39;text-decoration:none !important;" href='+amt_scheme_canceled+'>'+
    						(get_convert(parseFloat(element.today_cancelled_amt).toFixed(2)))+'</a><br/>&nbsp;'))
                       		.append($('<td style="padding: 5px;text-align: right;color:#dd4b39;">').append('<a style="color:#dd4b39;text-decoration:none !important;" href='+wt_scheme_canceled+'>'+
    						(get_convert(parseFloat(element.today_cancelled_wgt).toFixed(2)))+'<br/>'+element.weight_cancelled+'</a> g'))
    						
                       		
                       		.append($('<td style="padding: 5px;text-align: right;color:#0073b7;">').append(
    						get_convert(element.closing_balance_amt)+'<br/>&nbsp;'))
                       		.append($('<td style="padding: 5px;text-align: right;color:#0073b7;">').append(
    						get_convert(element.closing_balance_wgt)+'<br/>'+element.closing_weight+' g'))
                       		
                       		
                    ); 
				}
            
            }); 
		//}
      
	 

}
function get_convert(num)
{
	var str=num;
	var format = /[.]/;
	var x = num;
	if(format.test && (typeof str != 'number')){  
	   var number = str.split(".");
	   var x=number[0]; 
	}
	 
	if(x.length >=4 ){
		x=x.toString();
		var lastThree = x.substring(x.length-3);

		var otherNumbers = x.substring(0,x.length-3); 
		if(otherNumbers != '')
		{
		lastThree = ',' + lastThree;
		var res = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree;
		 var total=res.concat('.',number[1]);
		 return total;
		}
		else
		{
			return '-';
		}
	}else{
		return num==0 ?'-':num;
	}
}
 

// filter Date wise cus reg in dashboard//hh

function get_customer_list(from_date="",to_date="")
{var id_branch = $('#branch_select').val();
//alert("1");
	my_Date = new Date();
	// $("div.overlay").css("display", "block"); 
	$.ajax({
	  url:base_url+ "index.php/admin_dashboard/customer_status?nocache=" + my_Date.getUTCSeconds(),
	 //data: (from_date !='' && to_date !=''? {'from_date':from_date,'to_date':to_date,'id_branch':id_branch}: ''),
	data:{'from_date':from_date,'to_date':to_date,'id_branch':id_branch},
	 dataType:"JSON",
	 type:"POST",
	 success:function(data){
	 	set_customer_list(data,from_date,to_date);
	 	
	  },
	  error:function(error)  
	  {
	  $("div.overlay").css("display", "none"); 
	  }	 
	  });
}

function set_customer_list(data,from_date,to_date)
{
        $('#cus_web').empty(); //date wise filter cus reg  by hh//
        $('#cus_mob').empty();
        $('#cus_admin').empty();
        $('#cus_collection').empty();

	 	var sum=0;
	        admin_url=base_url+'index.php/admin_dashboard/get_customer_joined/'+from_date+"/"+to_date+"/"+1; 
			web_url=base_url+'index.php/admin_dashboard/get_customer_joined/'+from_date+"/"+to_date+"/"+0; 
			app_url=base_url+'index.php/admin_dashboard/get_customer_joined/'+from_date+"/"+to_date+"/"+2; 
			collection_url=base_url+'index.php/admin_dashboard/get_customer_joined/'+from_date+"/"+to_date+"/"+3;
			
			console.log(data.admin.joined_thro);
		    $("#cus_mob").append('<a href='+app_url+'><span class="badge bg-aqua">'+data.mob.joined_thro+'</span></a>');
            $("#cus_web").append('<a href='+web_url+'><span class="badge bg-aqua">'+data.web.joined_thro+'</span></a>');
            $("#cus_admin").append('<a href='+admin_url+'><span class="badge bg-aqua">'+data.admin.joined_thro+'</span></a>');
            $("#cus_collection").append('<a href='+collection_url+'><span class="badge bg-aqua">'+data.collection.joined_thro+'</span></a>');

}
// filter Date wise cus reg in dashboard//hh

// Coded by KVP ends for daily collection summary




//Customer Order Management
function get_order_management_details()
{
    let from_date =  $('#payment_list1').text();
	let to_date =  $('#payment_list2').text();
	get_customer_order_details(from_date,to_date);
}

function get_customer_order_details(from_date,to_date)
{
    $("#customer_order_table tbody").empty();
    $("#cus_order_details tbody").empty();
    $("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
	     url:base_url+ "index.php/admin_ret_dashboard/get_customer_order_details?nocache=" + my_Date.getUTCSeconds(),
			 dataType:"JSON",
			 data:{'from_date':from_date,'to_date':to_date,'id_branch':$('#id_branch').val()},
			 type:"POST",
			 cache:false,
			  success:function(data){ 
            var table_value='';      
            var cus_details=''; 
            var total_pcs=0;
            var total_wt=0;
                $.each(data.cus_orders_details,function(key,items){
                    total_pcs+=parseFloat(items.totalitems);
                    total_wt+=parseFloat(items.weight);
                    cus_details+='<tr>'+
                                        '<td>'+items.branch_name+'</td>'+
                                        '<td>'+items.cus_name+'</td>'+
                                        '<td>'+items.product_name+'</td>'+
                                        '<td>'+(items.weight+'/'+items.totalitems)+'</td>'+
                                        '<td>'+items.order_status+'</td>'+
                                 '</tr>'
                });
                $('.total_pcs').html(parseFloat(total_wt).toFixed(3)+'/'+total_pcs);
                $("#cus_order_details tbody ").append(cus_details);
                
        		$.each(data.cus_orders, function (bwd_key, bwd_element)  {	
        		    var pending_url = base_url+'index.php/admin_ret_reports/order_status/list/?order_staus=0&filter_type=1&id_branch='+bwd_element.order_from;
        		    var order_placed_url = base_url+'index.php/admin_ret_reports/order_status/list/?order_staus=3&filter_type=1&id_branch='+bwd_element.order_from;
        		    
        		    var karigar_pending_url = base_url+'index.php/admin_ret_reports/order_status/list/?order_staus=0&filter_type=4&id_branch='+bwd_element.order_from;
        		    var karigar_delivered_url = base_url+'index.php/admin_ret_reports/order_status/list/?order_staus=4&filter_type=7&id_branch='+bwd_element.order_from;
        		    var karigar_over_due_url = base_url+'index.php/admin_ret_reports/order_status/list/?order_staus=3&filter_type=6&id_branch='+bwd_element.order_from;
        		    
        		    var customer_pending_url = base_url+'index.php/admin_ret_reports/order_status/list/?order_staus=3&filter_type=3&id_branch='+bwd_element.order_from;
        		    var customer_delivery_ready_url= base_url+'index.php/admin_ret_reports/order_status/list/?order_staus=4&filter_type=7&id_branch='+bwd_element.order_from;
        		    var customer_delivered_url= base_url+'index.php/admin_ret_reports/order_status/list/?order_staus=5&filter_type=2&id_branch='+bwd_element.order_from;
        		    var customer_over_due_url= base_url+'index.php/admin_ret_reports/order_status/list/?order_staus=3&filter_type=5&id_branch='+bwd_element.order_from;
        		    
					table_value +='<tr>'+				
    					'<td>'+bwd_element.branch_name+'</td>'+
    					'<td class="numbers"><a href='+pending_url+' target="_blank">'+(bwd_element.allocation_pending_wt+'/'+bwd_element.allocation_pending_pcs)+'</td>'+		
    					'<td class="numbers"><a href='+order_placed_url+' target="_blank">'+(bwd_element.allocation_done_wt+'/'+bwd_element.allocation_done_pcs)+'</td>'+		
    					'<td class="numbers"><a href='+karigar_pending_url+' target="_blank">'+(bwd_element.karigar_pending_wt+'/'+bwd_element.karigar_pending_pcs)+'</a></td>'+
    					'<td class="numbers"><a href='+karigar_delivered_url+' target="_blank">'+(bwd_element.karigar_delivered_wt+'/'+bwd_element.karigar_delivered_pcs)+'</a></td>'+
    					'<td class="numbers"><a href='+karigar_over_due_url+' target="_blank" '+(bwd_element.karigar_over_due_pcs==0 ? 'style="text-color:red;"' :'')+' >'+(bwd_element.karigar_over_due_wt+'/'+bwd_element.karigar_over_due_pcs)+'</a></td>'+
    					'<td class="numbers"><a href='+customer_pending_url+' target="_blank">'+(bwd_element.cus_pending_wt+'/'+bwd_element.cus_pending_pcs)+'</a></td>'+
    					'<td class="numbers"><a href='+customer_delivery_ready_url+' target="_blank" >'+(bwd_element.cus_delivery_ready_wt+'/'+bwd_element.cus_delivery_ready_pcs)+'</a></td>'+
    					'<td class="numbers"><a href='+customer_delivered_url+' target="_blank">'+(bwd_element.cus_delivered_wt+'/'+bwd_element.cus_delivered_pcs)+'</a></td>'+
    					'<td class="numbers"><a href='+customer_over_due_url+' target="_blank" '+(bwd_element.cus_over_due_pcs>0 ? 'style="color:red;"' :'')+'>'+(bwd_element.cus_over_due_wt+'/'+bwd_element.cus_over_due_pcs)+'</a></td>'+
    					'</tr>';   
					}); 
				$("#customer_order_table tbody").append(table_value);
        		$("div.overlay").css("display", "none"); 
			  },
			  error:function(error)  
			  {
				 $("div.overlay").css("display", "none"); 
			  }	 
	});
} 


//Customer Order Management







function set_scheme_account_list(data){
    
    var accounts = data.accounts;
    
    console.log(accounts.length);

    var oTable = $('#sch_acc_list').dataTable();
    
    if (accounts!= null && accounts.length > 0){
        
        oTable = $('#sch_acc_list').dataTable({
                                
                                "bDestroy": true,

				                "bInfo": true,

				                "bFilter": true,

				                "bSort": true,

				                "dom": 'lBfrtip',
				                
           		                "buttons" : ['excel','print'],
           		                
						        "tableTools": { "buttons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },

				                "aaData": accounts,

				                "order": [[ 0, "desc" ]],


				               "aoColumns": [
				                   
				                    { "mDataProp": "id_scheme_account" },
				                    { "mDataProp": function (row,type,val,meta){
                                        if(row.has_lucky_draw==1){
                                            return row.group_code+' '+row.scheme_acc_number;
                                        }else{
                                            return row.code+' '+row.scheme_acc_number;
                                        }
									} },
				                    { "mDataProp": "name" },
				                    { "mDataProp": "mobile" },
				                    { "mDataProp": "code" },
				                    { "mDataProp": "scheme_type" },
				                    { "mDataProp": "start_date" },
				                    { "mDataProp": "added_by" },
				                    { "mDataProp": function (row,type,val,meta){
                                        if(row.emp_name != null){
                                            return row.emp_name+' / '+row.emp_code;
                                        }else{
                                            return '-';
                                            
                                        }
									}},
				                    { "mDataProp": "payment_amount" },
				                    
				                    { "mDataProp": "total_installments" },
				                    { "mDataProp": function (row,type,val,meta){
                                        if(row.scheme_type==1){
                                            return 'max'+row.max_weight+'g/month';
                                        }else{
                                            return row.amount;
                                            
                                        }
									}}
				                   
				                   
				                ],
				                
		"footerCallback": function( row, data, start, end, display ) 
		{
			if(data.length>0){

			 var api = this.api(), data;

					 var intVal = function ( i ) {
						return typeof i === 'string' ?
							i.replace(/[\$,]/g, '')*1 :
							typeof i === 'number' ?i : 
							0;
					};

			// Amount Total over this page
            amttotal = api
                .column(9,{ page: 'current'})
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            $( api.column(9).footer() ).html(parseFloat(amttotal).toFixed(2));
					
			}
		}

        });	
    }  
}


function set_scheme_payment_list(data){
    
    console.log(data);
    var payments = data.payments;

    var oTable = $('#sch_payment_list').dataTable();
    
    if (payments!= null && payments.length > 0){
        
        oTable = $('#sch_payment_list').dataTable({
                                
                                "bDestroy": true,

				                "bInfo": true,

				                "bFilter": true,

				                "bSort": true,

				                "dom": 'lBfrtip',
				                
           		                "buttons" : ['excel','print'],
           		                
						        "tableTools": { "buttons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },

				                "aaData": payments,

				                "order": [[ 0, "desc" ]],


				               "aoColumns": [
				                   
				                    { "mDataProp": "id_payment" },
				                    { "mDataProp": "name" },
				                    { "mDataProp": "mobile" },
				                    { "mDataProp": "ref_no" },
				                    { "mDataProp": "code" },
				                    { "mDataProp": function (row,type,val,meta){
                                        if(row.has_lucky_draw==1){
                                            return row.group_code+' '+row.scheme_acc_number;
                                        }else{
                                            return row.code+' '+row.scheme_acc_number;
                                        }
									} },
				                    { "mDataProp": "scheme_type" },
				                    { "mDataProp": "date_payment" },
				                    { "mDataProp": "id_transaction" },
				                    { "mDataProp": "payment_mode" },
				                    { "mDataProp": "payment_amount"},
				                    { "mDataProp": function (row,type,val,meta){
                                            return '<span class= "badge bg-'+row.color+'">'+row.payment_status+'</span>';
									} }
				                   
				                   
				                ],
				                
		"footerCallback": function( row, data, start, end, display ) 
		{
			if(data.length>0){

			 var api = this.api(), data;

					 var intVal = function ( i ) {
						return typeof i === 'string' ?
							i.replace(/[\$,]/g, '')*1 :
							typeof i === 'number' ?i : 
							0;
					};

			// Amount Total over this page
            amttotal = api
                .column(10,{ page: 'current'})
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            $( api.column(10).footer() ).html(parseFloat(amttotal).toFixed(2));
					
			}
		}

        });	
    }  
}


   
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
      console.log(item.id_employee);  
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
    
    $("#emp_select").select2("val", ($('#id_employee').val()!=null?$('#id_employee').val():''));
    $(".overlay").css("display", "none");
    }
    
    });
    }
   
   
 
var path =  url_params();
var ctrl_page = path.route.split('/');
var export_list = [];
$(document).ready(function() 
{   
    
      $(window).scroll(function() {    // this will work when your window scrolled.
		var height = $(window).scrollTop();  //getting the scrolling height of window
		if(height  > 300) {
			$(".stickyBlk").css({"position": "fixed"});
		} else{
			$(".stickyBlk").css({"position": "static"});
		}
	}); 
	
	$('.dateRangePicker').daterangepicker({ 
	    format: 'DD/MM/YYYY',
	    //startDate:  moment().subtract(6, 'days'), 
	    endDate: moment(), 
	});
	
   
	
 
	switch(ctrl_page[1])
	{
		case 'old_metal_purchase':
			    set_old_metal_table();
	 	break;
	 	case 'bill_wise_transcation':
	 	        get_ActiveFloors();
		        get_ActiveCounters();
			    get_ActiveVillage();
	 	break;
	 	
	 	case 'day_closing_report':
	 	    $('.datePicker').datepicker({
	 	        dateFormat: 'mm-dd-yy'
	 	    });
	 	   
	 	break;
	 	
	 	
	 	
		case 'stock_age':
				get_ActiveKarigars();
				get_ActiveMetals();
				get_category(); 
				get_ActiveProduct(); 
				switch(ctrl_page[2])
				{
				    case 'tag_list':
				        get_stock_age_tag_list();
				    break;
				}
			break;
		case 'lot_wise': 
				get_ActiveKarigars();
                var date = new Date();
        		    var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 0, 1); 
        			var from_date=(firstDay.getDate()+"-"+(firstDay.getMonth() + 1)+"-"+firstDay.getFullYear());
        			var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
			        $('#rpt_payments1').html(from_date);
                    $('#rpt_payments2').html(to_date);
                    set_lotwise_table();
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
		case 'partly_sold': 
				set_partlysold_table();
			break;
		case 'cash_abstract': 
		    
	            var date = new Date();
    		    var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 0, 1); 
    			var from_date=(firstDay.getDate()+"-"+(firstDay.getMonth() + 1)+"-"+firstDay.getFullYear());
    			var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
		        $('#rpt_payments1').html(from_date);
                $('#rpt_payments2').html(to_date);
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
    			 
		        get_ActiveFloors();
		        get_ActiveCounters();
			break;
		case 'branch_trans': 
		       if(ctrl_page[2]=='approval_pending')
		       {
		           getApprovalPending();
		       }else{
		           set_btreport_table();
		       }
				
			break;
		case 'credit_issued':
	    		set_credit_issued_table();
	    break;
	    case 'credit_history':
	            get_ActiveCustomer();
	    		set_credit_history_table();
	    break;
	    case 'advance_history':
	    		set_advance_history_table();
	    break;
	    case 'chit_closing_details':
	    		set_chit_closing_table();
	    break;
	    case 'card_payment':
	    		set_card_payment_table();
	    break;
	    case 'copy_bill_details':
	    		set_copy_bill_table();
	    break;
	    case 'pan_bill_details':
	    		set_pan_bill_table();
	    break;
	    case 'cancelled_bills':
	    		set_cancelledBills();
	    break;
	    case 'bill_discount':
	    		set_discBills();
	    break;
	    case 'gst_bill':
	    		set_gstBills();
	    break;
		case 'reorder_items':
	    		//set_reorder_items();
	    	    get_ActiveProduct();
	    	    $("#select_size").select2({			    
                    placeholder: "Select Size",			    
                    allowClear: true		    
                });
                
                $("#sub_des_select").select2({			    
                    placeholder: "Select Sub Design",			    
                    allowClear: true		    
                });
                
                $("#wt_select").select2({			    
                    placeholder: "Select Weight Range",			    
                    allowClear: true		    
                });
                
                $("#des_select").select2({			    
                    placeholder: "Select Design",			    
                    allowClear: true		    
                });
                
	    		//get_weight_range();
	    break;
	    case 'branchreorder_items':
	    		//set_reorder_items();
	    	    get_ActiveProduct();
	    	    $("#select_size").select2({			    
                    placeholder: "Select Size",			    
                    allowClear: true		    
                });
                
                $("#sub_des_select").select2({			    
                    placeholder: "Select Sub Design",			    
                    allowClear: true		    
                });
                
                $("#wt_select").select2({			    
                    placeholder: "Select Weight Range",			    
                    allowClear: true		    
                });
                
                $("#des_select").select2({			    
                    placeholder: "Select Design",			    
                    allowClear: true		    
                });
                set_branchreorder_items();
                 $('#des_select').on('change',function()
                {
                	if(this.value!='')
                	{
                		if(ctrl_page[1]=='branchreorder_items')
                		{
                			get_ActiveSubDesign();
                		}
                	}
                });
                
	    		//get_weight_range();
	    break;
	   
	    case 'tag_items_designwise':
	            get_ActiveMetals();
	    		get_Activedesign('');
	    		get_ActiveProduct();
	    		get_category();
	    		get_ActiveKarigars();
	    		get_ActiveCollection();
	    		 $("#select_size").select2({			    
                placeholder: "Select Size",			    
                allowClear: true		    
                });	
                
                $("#select_collection").select2({			    
                placeholder: "Select Collection",			    
                allowClear: true		    
                });	
                
	    break;
	    case 'stock_report':
	            get_ActiveMetals();
	            get_category(); 
	    		get_ActiveProduct();
	    		//stock_report();
	    break;
	    
	    case 'gst_bills':
	        get_category(); 
	    break;
	    
	    case 'stock_details':
	            get_ActiveMetals();
	            get_category(); 
	    		get_ActiveProduct();
	    		//get_stock_details();
	    break;
	    
	     case 'stock_checking':
	    		get_ActiveProduct();
	    		stock_checking();
	    break;
	    case 'tag_scan_missing':
	    		get_ActiveProduct();
	    		tag_missing_report();
	    break;
	    case 'scan_report':
	    		get_ActiveProduct();
	    		if(ctrl_page[2]=='scanned_details')
	    		{
	    		    	get_scanned_details_report();
	    		}
	    break;
	    case 'item_sales':
	    		get_ActiveProduct();
	    		get_ActiveMetals();
	    		//itemwise_sales();
	    break;
	    case 'home_bill':
	    		get_home_bill_sales();
	    break;
	    case 'order_advance':
	        set_order_advance();
	    break;
	    case 'est_referral':
	        get_ActiveMetals();
	        get_category(); 
			get_ActiveProduct(); 
	        set_estimation_referral();
	    break;
	    case 'other_issue':
	    		get_ActiveProduct();
	    		set_other_issue();
	    break;
	    case 'tag_history':
	    		//set_tag_history();
	    break;
	    case 'order_status':
	    			$('#order_status').select2({			    
                	 	placeholder: "Select Order Status",			    
                	 	allowClear: true		    
                 	}); 
                 	$('#order_status').select2("val",'');
                 	get_order_status();
                 	
                 	
                 	var date = new Date();
        		    var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 0, 1); 
        			var from_date=(firstDay.getDate()+"-"+(firstDay.getMonth() + 1)+"-"+firstDay.getFullYear());
        			var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
    		        $('#rpt_payments1').html(from_date);
                    $('#rpt_payments2').html(to_date);
                    set_order_status_report();
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
	    
	    case 'monthly_sales':
	    		set_monthly_sales();
	    break;
	    
	    case 'old_metal_analyse':
	    		get_old_metal_type();
	    		set_old_metal_analyse_table();
	    break;
	    
	    case 'sales_analysis_report':
	            
	            if(ctrl_page[2]=='list')
	            {
	                var date = new Date();
        		    var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 30, 1); 
        			var from_date=(firstDay.getDate()+"-"+(firstDay.getMonth() + 1)+"-"+firstDay.getFullYear());
        			var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
    		        $('#rpt_payments1').html(from_date);
                    $('#rpt_payments2').html(to_date);
                    //get_sales_analysis_report();
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
    		      
    						$('#rpt_payments1').text(start.format('YYYY-MM-DD'));
    						$('#rpt_payments2').text(end.format('YYYY-MM-DD'));		            
    		          }
    			    ); 
    			    
    			    //get_ActiveVillage();
    	    		//get_ActiveProduct();
    	    		//get_ActiveZone();
	    		
	            } 
	            else if(ctrl_page[2]=='chit_details')
	            {
	                get_chit_analysis_report();
	            }
	    		else if(ctrl_page[2]=='sales_details')
	    		{
	    		    get_sales_analysis_details_report();
	    		}
	    		else if(ctrl_page[2]=='product_details')
	    		{
	    		    get_product_analysis_details_report();
	    		}
	    		else if(ctrl_page[2]=='without_acc_list')
	    		{
	    		    get_without_acc_list();
	    		}
	    break;
	    
	    case 'unbilled_estimation':
	    		set_unbilled_estimation();
	    break;
	    
	    case 'karigar_wise_sales':
	            get_karigar_wise_sales();
	            get_ActiveProduct();
	    break;
	    
	    case 'lot_history':
	            switch(ctrl_page[2])
	            {
	                case 'list':
	                        setLotHisory();
	                break;
	                case 'lot_details':
	                        get_lot_details();
	                break;
	            }
	            
	    break;
	    
	    case 'green_tag':
	            var date = new Date();
                    var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 30, 1); 
                    var from_date=(firstDay.getDate()+"-"+(firstDay.getMonth() + 1)+"-"+firstDay.getFullYear());
                    var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
                    $('#rpt_payments1').html(from_date);
                    $('#rpt_payments2').html(to_date);
                    get_getGreenTagDetails();
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
                          startDate: moment().subtract(30, 'days'),
                          endDate: moment()
                        },
                      function (start, end) {
                      $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                            $('#rpt_payments1').text(start.format('YYYY-MM-DD'));
                            $('#rpt_payments2').text(end.format('YYYY-MM-DD'));                 
                      }
                 ); 
	    break;
	    
	    case 'sales_comparision':
	    		get_ActiveProduct();
	    		//get_sales_comparision();
	    break;
	    
	   
	    
	    case 'customer_history':
	            if(ctrl_page[3]!='' && ctrl_page[3]!=undefined)
	            {
	                get_customer_details();
	            }
	    break;
	    
	    case 'credit_pending':
	        
	        var date = new Date();
            var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 0, 1); 
            var from_date=(firstDay.getDate()+"-"+(firstDay.getMonth() + 1)+"-"+firstDay.getFullYear());
            var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
            $('#rpt_from_date').html(from_date);
            $('#rpt_to_date').html(to_date);
            $('#rpt_date_picker').daterangepicker(
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
            $('#rpt_date_picker').html(start.format('D/M/YYYY') + ' - ' + end.format('D/M/YYYY'));
            $('#rpt_from_date').text(start.format('DD-MM-YYYY'));
            $('#rpt_to_date').text(end.format('DD-MM-YYYY'));		            
            }
            );
            
	        set_credit_pending_list();
	    break;
	    
	    case 'stock_and_sales_report':
	        set_stock_and_sales_list();
	    break;
	    
	     case 'incentive_report':
	         switch(ctrl_page[2])
	         {
	             case 'emp_list':
	                        set_incentive_emp_list();
	             break;
	         }
	         
	           var date = new Date();
    		    var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 30, 1); 
    			var from_date=(firstDay.getDate()+"-"+(firstDay.getMonth() + 1)+"-"+firstDay.getFullYear());
    			var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
		       /* $('#rpt_payments1').html(from_date);
                $('#rpt_payments2').html(to_date);
               */  set_incentive_report();
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
		              startDate: moment().subtract(30, 'days'),
		              endDate: moment()
		            },
		          function (start, end) {
		          $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
		      
						$('#rpt_payments1').text(start.format('YYYY-MM-DD'));
						$('#rpt_payments2').text(end.format('YYYY-MM-DD'));		            
		          }
			    ); 
			    
	       
	    break;
	    
	    case 'monthly_slaes_comparision':
	       
            if(ctrl_page[2]=='list')
            {
                $('#yearpicker').select2({			    
                placeholder: "Select Year",			    
                allowClear: true		    
                }); 
                
                current_year = new Date().getFullYear();
                $('#cur_year').val(current_year);
                $("#yearpicker").select2("val",current_year);	 
                for (i = new Date().getFullYear(); i > 2000; i--)
                {
                    $('#yearpicker').append($('<option />').val(i).html(i));
                }
                
                $('#yearpicker').on('change',function(){
                $('#cur_year').val(this.value);
                });
            
                get_ActiveVillage();
	            monthly_slaes_comparision();
            }else if(ctrl_page[2]=='detailed_list')
            {
                get_monthly_sales_details();
            }
               
	           
	    break;
	    
	    case 'telecalling':
	        
	            $('#select_zone').select2({			    
            	 	placeholder: "Select Zone",			    
            	 	allowClear: true		    
             	}); 
             	
             	$('#select_village').select2({			    
            	 	placeholder: "Select Area",			    
            	 	allowClear: true		    
             	}); 
             	
                get_ActiveZone();
                var date = new Date();
                var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 0, 1);
            
                var from_date=(firstDay.getDate()+"-"+(firstDay.getMonth() + 1)+"-"+firstDay.getFullYear());
            
                var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
       
                $('#rpt_cus1').html(from_date);
                $('#rpt_cus2').html(to_date);
                $('#rpt_customer').daterangepicker(
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
                        $('#rpt_cus1').text(start.format('YYYY-MM-DD'));
                        $('#rpt_cus2').text(end.format('YYYY-MM-DD'));            
                        }
                        );
        break;
        
        case 'feedback_report':
                get_feedback_report();
        break;
        
         case 'customer_edit_log':
		      var date = new Date();
        		    var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 0, 1); 
        			var from_date =  firstDay.getFullYear()+'-'+(firstDay.getMonth()+1)+'-'+firstDay.getDate();
        			var to_date=(date.getFullYear()+"-"+(date.getMonth() + 1)+"-"+date.getDate());
			        $('#customer_edit_log_date1').html(from_date);
                    $('#customer_edit_log_date2').html(to_date);
                    set_customer_edit_log_table();
                    
                    $('#customer_edit_log_date').daterangepicker(
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
                    $('#customer_edit_log_date1').text(start.format('YYYY-MM-DD'));
                    $('#customer_edit_log_date2').text(end.format('YYYY-MM-DD'));
                    set_customer_edit_log_table();		            
                    }
                    ); 
	    break;
	    
	    case 'gt_return_report':
		            set_gt_return_report_table();
		 break;
		 
		 case 'dashboard_estimation':
			set_dashboard_estimationList();
		break;
		case 'dashboard_sales':
			set_dashboard_salesList();
		break;
		case 'dashboard_greentag':
			set_dashboard_greentagList();
		break;
		case 'dashboard_oldmetal':
			set_dashboard_oldmetalList();
		break;
		case 'dashboard_creditsales':
			set_dashboard_creditsalesList();
		break;
		case 'dashboard_giftcard':
			set_dashboard_giftcardList();
		break;
		case 'dashboard_virtualsales':
			set_dashboard_virtualsalesList();
		break;
		case 'dashboard_lottag':
			set_dashboard_lottagList();
		break;
		case 'dashboard_customerorder':
			set_dashboard_customerorderList();
		break;
		case 'dashboard_salereturn':
			set_dashboard_salereturnList();
		break;
	 
	    case 'dashboard_greentag_incent':
			set_dashboard_greentagincentList();
		break;
		
		case 'dashboard_lot':
			set_dashboard_lotList();
		break;
		case 'dashboard_tag':
			set_dashboard_tagList();
		break;
		case 'purchase': 
		        get_category(); 
				get_ActiveKarigars();
				get_po_bills_details();
			break;
		case 'popayments': 
				get_ActiveKarigars();
			break;
		case 'qcstatus':
		    get_qc_issue_details();
		    break;
		case 'pohmstatus':
		    get_hm_issue_details();
		    break;
		    
		case 'approvaltag_items_designwise':
	    		get_Activedesign('');
	    		get_ActiveProduct();
	    		 $("#select_size").select2({			    
                placeholder: "Select Size",			    
                allowClear: true		    
                });	
	    break;
	    
	    case 'customer_ledger_statement':
	            get_CustomerLedger();
	        break;
	    
	    case 'supplierledger': 
				get_ActiveKarigars();
			break;
	    
	    case 'old_sale_report':
                var date = new Date();
                var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 0, 1);
                var from_date=(firstDay.getDate()+"-"+(firstDay.getMonth() + 1)+"-"+firstDay.getFullYear());
                var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
                $('#old_tag_report_date1').html(from_date);
                $('#old_tag_report_date2').html(to_date);
                get_old_sale_report_report();
                $('#old_tag_report_date').daterangepicker(
                {
                ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                startDate: moment().subtract(1, 'days'),
                endDate: moment()
                },
                function(start, end)
                {
                    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                    $('#old_tag_report_date1').text(start.format('DD-MM-YYYY'));
                    $('#old_tag_report_date2').text(end.format('DD-MM-YYYY'));		   
                }
                );
        break;
		
		case 'metal_stock_details':
	                var date = new Date();
        		    var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 0, 1); 
        			var from_date=(firstDay.getDate()+"-"+(firstDay.getMonth() + 1)+"-"+firstDay.getFullYear());
        			var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
			        $('#rpt_payments1').html(from_date);
                    $('#rpt_payments2').html(to_date);
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
    		                set_cashabstract_table();
    						$('#rpt_payments1').text(start.format('YYYY-MM-DD'));
    						$('#rpt_payments2').text(end.format('YYYY-MM-DD'));		            
    		          }
    			 ); 
	           //get_metal_stock_details();
	    break;
	    
	     case'advance_total_details':
	         
            var date = new Date();
            var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 0, 1); 
            var from_date=(firstDay.getDate()+"-"+(firstDay.getMonth() + 1)+"-"+firstDay.getFullYear());
            var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
            $('#rpt_from_date').html(from_date);
            $('#rpt_to_date').html(to_date);
            $('#rpt_date_picker').daterangepicker(
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
            $('#rpt_date_picker').html(start.format('D/M/YYYY') + ' - ' + end.format('D/M/YYYY'));
            $('#rpt_from_date').text(start.format('DD-MM-YYYY'));
            $('#rpt_to_date').text(end.format('DD-MM-YYYY'));		            
            }
            );
        	get_advance_details();
        	
        break;
        
        case 'dashboard_branchtransfer':
        	set_dashboard_btlist();
        break;
        
        case 'acc_stock_details':
        	set_acc_stock_details();
        break;
        
         case 'tag_stone':
        	get_ActiveMetals();
        	get_Tagcategory();
        	get_Activedesign('');
        	get_ActiveProduct();
        	$("#select_size").select2({			    
        	placeholder: "Select Size",			    
        	allowClear: true		    
        	});
        	$('#metal').on('change',function(e){
        	if(this.value!='' && ctrl_page[1]=='tag_stone')
        	{
        		get_Tagcategory();
        	}
        	});	
        break;
        
        case 'retag_report': 
		    
	            var date = new Date();
    		    var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 0, 1); 
    			var from_date=(firstDay.getDate()+"-"+(firstDay.getMonth() + 1)+"-"+firstDay.getFullYear());
    			var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
		        $('#rpt_payments1').html(from_date);
                $('#rpt_payments2').html(to_date);
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
			    
			    $('#retag_report_type').select2({			    
            	 	placeholder: "Select Report Type",			    
            	 	allowClear: true		    
             	}); 
    			 
			break;
			
			case 'purchase_itemwise':
			    get_ActiveProduct();
                get_Activedesign('');
                $('#des_select').on('change',function()
                {
                	if(this.value!='')
                	{
                		if(ctrl_page[1]=='purchase_itemwise')
                		{
                			get_ActiveSubDesign();
                		}
                	}
                });
                
                $("#sub_des_select").select2(
                {
                	placeholder:"Select Sub Design",
                	allowClear: true		    
                });


			        get_ActiveKarigars();
                    var date = new Date();
                    var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 0, 1); 
                    var from_date=(firstDay.getDate()+"-"+(firstDay.getMonth() + 1)+"-"+firstDay.getFullYear());
                    var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
                    $('#rpt_payments1').html(from_date);
                    $('#rpt_payments2').html(to_date);
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
                    get_purchase_itemwise();
            break;
            
            case 'sales_import':
            
                var date = new Date();
    		    var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 0, 1); 
    			var from_date=(firstDay.getDate()+"-"+(firstDay.getMonth() + 1)+"-"+firstDay.getFullYear());
    			var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
		        $('#rpt_payments1').html(from_date);
                $('#rpt_payments2').html(to_date);
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
	    	get_sales_import();
	    break;
	    
	    case 'purchase_import':
            
                var date = new Date();
    		    var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 0, 1); 
    			var from_date=(firstDay.getDate()+"-"+(firstDay.getMonth() + 1)+"-"+firstDay.getFullYear());
    			var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
		        $('#rpt_payments1').html(from_date);
                $('#rpt_payments2').html(to_date);
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
	    	get_purchase_import();
	    break;
	    
	    
	    case 'payment_mode_import':
            
                var date = new Date();
    		    var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 0, 1); 
    			var from_date=(firstDay.getDate()+"-"+(firstDay.getMonth() + 1)+"-"+firstDay.getFullYear());
    			var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
		        $('#rpt_payments1').html(from_date);
                $('#rpt_payments2').html(to_date);
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
	    	get_payment_mode_import();
	    break;
	    
	    case 'stock_rotation':
            
                var date = new Date();
    		    var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 0, 1); 
    			var from_date=(firstDay.getDate()+"-"+(firstDay.getMonth() + 1)+"-"+firstDay.getFullYear());
    			var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
		        $('#rpt_payments1').html(from_date);
                $('#rpt_payments2').html(to_date);
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
			 get_ActiveProduct(); 
	    	get_stock_rotation_list();
	    break;
	    
	    //ACCOUNTS REPORTS
	    case 'categorywise_bt_report':
	         get_category(); 
	         $('#branch_select').select2({			    
        	 	placeholder: "To Centre",			    
        	 	allowClear: true		    
         	}); 
         	$('#branch_select_to').select2({			    
        	 	placeholder: "Cost Centre",			    
        	 	allowClear: true		    
         	}); 
	    break;
	    case 'gst_abstract':
	                get_category(); 
	    			$('#report_type').select2();
	    			$('#gst_filter').select2();
	    			$('#report_type').select2({			    
                	 	placeholder: "Select Report Type",			    
                	 	allowClear: true		    
                 	}); 
                 	
                 	$('#gst_filter').select2({			    
                	 	placeholder: "Select GST Type",			    
                	 	allowClear: true		    
                 	}); 
                 	
                 	var date = new Date();
        		    var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 0, 1); 
        			var from_date=(firstDay.getDate()+"-"+(firstDay.getMonth() + 1)+"-"+firstDay.getFullYear());
        			var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
    		        $('#rpt_from_date').html(from_date);
                    $('#rpt_to_date').html(to_date);
    			  		$('#rpt_date_picker').daterangepicker(
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
    					  $('#rpt_date_picker').html(start.format('D/M/YYYY') + ' - ' + end.format('D/M/YYYY'));
    				      $('#rpt_from_date').text(start.format('DD-MM-YYYY'));
    					  $('#rpt_to_date').text(end.format('DD-MM-YYYY'));		            
    		          }
    			    ); 
	    break;
	    
	    case 'sales_return_abstract':
	        get_category(); 
            var date = new Date();
            var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 0, 1); 
            var from_date=(firstDay.getDate()+"-"+(firstDay.getMonth() + 1)+"-"+firstDay.getFullYear());
            var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
            $('#rpt_from_date').html(from_date);
            $('#rpt_to_date').html(to_date);
            sales_return_details();
            $('#rpt_date_picker').daterangepicker(
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
            $('#rpt_date_picker').html(start.format('D/M/YYYY') + ' - ' + end.format('D/M/YYYY'));
            $('#rpt_from_date').text(start.format('DD-MM-YYYY'));
            $('#rpt_to_date').text(end.format('DD-MM-YYYY'));		            
            }
            ); 
	    break;
	    
	    case 'card_collection_report':
            
            
            
            var date = new Date();
            var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 0, 1);
            var from_date=(firstDay.getDate()+"-"+(firstDay.getMonth() + 1)+"-"+firstDay.getFullYear());
            var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
            $('#rpt_payments1').html(from_date);
            $('#rpt_payments2').html(to_date);
            get_card_collection_report();
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
            function(start, end)
            {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                $('#rpt_payment_date').html(start.format('D/M/YYYY') + ' - ' + end.format('D/M/YYYY'));
                $('#rpt_payments1').text(start.format('DD-MM-YYYY'));
                $('#rpt_payments2').text(end.format('DD-MM-YYYY'));		   
            }
            );
        break;
        
        case 'cheque_collection_report':
                var date = new Date();
                var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 0, 1);
                var from_date=(firstDay.getDate()+"-"+(firstDay.getMonth() + 1)+"-"+firstDay.getFullYear());
                var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
                $('#rpt_payments1').html(from_date);
                $('#rpt_payments2').html(to_date);
                get_cheque_collection_report();
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
                function(start, end)
                {
                    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                    $('#rpt_payment_date').html(start.format('D/M/YYYY') + ' - ' + end.format('D/M/YYYY'));
                    $('#rpt_payments1').text(start.format('DD-MM-YYYY'));
                    $('#rpt_payments2').text(end.format('DD-MM-YYYY'));		   
                }
                );
        break;
        
        case 'netbanking_collection_report':
            var date = new Date();
                var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 6, 1);
                var from_date=(firstDay.getDate()+"-"+(firstDay.getMonth() + 1)+"-"+firstDay.getFullYear());
                var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
                $('#nb_coll_rpt1').html(from_date);
                $('#nb_coll_rpt2').html(to_date);
                get_netbanking_collection_report();
                $('#nb_coll_rpt').daterangepicker(
                {
                ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                startDate: moment().subtract(6, 'days'),
                endDate: moment()
                },
                function(start, end)
                {
                    $('#nb_coll_rpt').html(start.format('D/M/YYYY') + ' - ' + end.format('D/M/YYYY'));
                    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                    $('#nb_coll_rpt1').text(start.format('DD-MM-YYYY'));
                    $('#nb_coll_rpt2').text(end.format('DD-MM-YYYY'));  
                }
                );
        break;
        
        case 'advance_receipt_report':
           var date = new Date();
            var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 7, 1);
            var from_date=(firstDay.getDate()+"-"+(firstDay.getMonth() + 1)+"-"+firstDay.getFullYear());
            var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
            $('#advance_list_report_date1').html(from_date);
            $('#advance_list_report_date2').html(to_date);
            set_advance_receipt_table();
            $('#advance_list_report_date').daterangepicker(
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
            $('#advance_list_report_date').html(start.format('D/M/YYYY') + ' - ' + end.format('D/M/YYYY'));
            $('#advance_list_report_date1').text(start.format('YYYY-MM-DD'));
            $('#advance_list_report_date2').text(end.format('YYYY-MM-DD'));            
            }
            );
            //get_metal_stock_details();
    break;
    
    
    case 'daytransactions':
	    			
                 	var date = new Date();
        		    var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 0, 1); 
        			var from_date=(firstDay.getDate()+"-"+(firstDay.getMonth() + 1)+"-"+firstDay.getFullYear());
        			var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
    		        $('#rpt_from_date').html(from_date);
                    $('#rpt_to_date').html(to_date);
    			  		$('#rpt_date_picker').daterangepicker(
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
    					  $('#rpt_date_picker').html(start.format('D/M/YYYY') + ' - ' + end.format('D/M/YYYY'));
    					  $('#rpt_from_date').text(start.format('DD-MM-YYYY'));
    					  $('#rpt_to_date').text(end.format('DD-MM-YYYY'));		            
    		          }
    			    ); 
	    break;
	    
	    //ACCOUNTS REPORTS
	    
    case 'item_sales_detail':
    		get_ActiveProduct();
    		get_ActiveMetals();
    		get_category(); 
    break;
    
    case 'pay_device':
		    set_devicepayBills();
			get_ActiveDevicename();		
    break;
    
    case 'weight_range_sales':

			var date = new Date();
			var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 0, 1); 
			var from_date=(firstDay.getDate()+"-"+(firstDay.getMonth() + 1)+"-"+firstDay.getFullYear());
			var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
			$('#rpt_payments1').html(from_date);
			$('#rpt_payments2').html(to_date);
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
	 		
			get_ActiveProduct();
	        get_ActiveKarigars();
	 		//get_weight_range_wise_sales_list();


	 		$("#sub_des_select").select2({			    
				placeholder: "Select Sub Design",			    
				allowClear: true		    
			});
	
	
			$("#wt_select").select2({			    
				placeholder: "Select Weight Range",			    
				allowClear: true		    
			});
	
	
			$("#des_select").select2({			    
				placeholder: "Select Design",			    
				allowClear: true		    
			});

	
			$('#des_select').on('change',function(){
				if(this.value!='')
				{
					if(ctrl_page[1]=='stock_rotation_sales')
					{
						get_ActiveSubDesign();
					}
		   
				}
			});

    break;
    
    case 'staff_incentive_report':
	 	    if(ctrl_page[2]=='list')
	 	    {
	 	        var date = new Date();
    		    var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 0, 1); 
    			var from_date=(firstDay.getDate()+"-"+(firstDay.getMonth() + 1)+"-"+firstDay.getFullYear());
    			var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
		        $('#rpt_payments1').html(from_date);
                $('#rpt_payments2').html(to_date);
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
			    
			    get_staff_incentive_report();
	 	    }if(ctrl_page[2]=='detailed')
	 	    {
	 	        get_staff_incentive_detailed_report();
	 	    }
	 	        
	 	break;
	 	
	 	case 'karigar_metal_issue':
            get_ActiveKarigars();
            get_ActiveMetals();
            get_category();
            get_ActiveProduct();
            var date = new Date();
            var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 0, 1); 
            var from_date=(firstDay.getDate()+"-"+(firstDay.getMonth() + 1)+"-"+firstDay.getFullYear());
            var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
            $('#rpt_from_date').html(from_date);
            $('#rpt_to_date').html(to_date);
            $('#rpt_date_picker').daterangepicker(
            {
            ranges: {
            'Today'        : [moment(), moment()],
            'Yesterday'    : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days'  : [moment().subtract(6, 'days'), moment()],
            'Last 30 Days' : [moment().subtract(29, 'days'), moment()],
            'This Month'   : [moment().startOf('month'), moment().endOf('month')],
            'Last Month'   : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            startDate: moment().subtract(0, 'days'),
            endDate: moment()
            },
            function (start, end) 
            {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            $('#rpt_date_picker').html(start.format('D/M/YYYY') + ' - ' + end.format('D/M/YYYY'));
            $('#rpt_from_date').text(start.format('DD-MM-YYYY'));
            $('#rpt_to_date').text(end.format('DD-MM-YYYY'));		            
            }
            ); 
            
            get_karigar_metal_issue_itemwise();
        break;
        
        case  'customer_detail':
            var date = new Date();
            var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 0, 1);
            var from_date=(firstDay.getDate()+"-"+(firstDay.getMonth() + 1)+"-"+firstDay.getFullYear());
            var to_date=(date.getDate()+"-"+(date.getMonth() + 1)+"-"+date.getFullYear());
            $('#rpt_cus1').html(from_date);
            $('#rpt_cus2').html(to_date);
            $('#rpt_customer').daterangepicker(
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
            $('#rpt_cus1').text(start.format('YYYY-MM-DD'));
            $('#rpt_cus2').text(end.format('YYYY-MM-DD'));            
            }
            );
        break;
	 	

	}
	$('#filter_metal').select2({			    
	 	placeholder: "Select type",			    
	 	allowClear: true		    
 	}); 
 	
	$("#karigar").select2({
		placeholder:"Select Supplier / Karigar",
		allowClear: true		    
	}); 
 
	$('#lot').select2({
		placeholder: "Select Lot ID",			    
		allowClear: true
	});
	
	jQuery("#category").on("change", function(){  
		$("#product").val(''); 
		$("#design").val(''); 
	});
	
	$("#karigar").on("change", function(){  
		/*if($("#karigar").val() != "" && $("#karigar").val() != 0 && ctrl_page[1] == 'supplierledger'){
		    getSupplierLedger();
		}*/
		if($("#karigar").val() != "" &&  ctrl_page[1] == 'supplierledger'){
		    getSupplierLedger();
		}
	});
	
    $("input[name='ledger_type']:radio").change(function(){
		if($("#karigar").val() != "" && ctrl_page[1] == 'supplierledger'){
		    getSupplierLedger();
		}
	});
	
	$('#supplier_search').click(function(event) {
		getSupplierLedger();
	});
	
	jQuery("#product").on("input", function(){  
		var prod = $("#product").val(); 
		$("#design").val(''); 
		if(prod.length >= 3) { 
			getSearchProd(prod);
		} 
	});
	
	jQuery("#design").on("input", function(){ 
		var des = $("#design").val(); 
		if(des.length >= 3) { 
			getSearchDesign(des);
        }
	});
	
	$('#old_metal_search').click(function(event) {
		set_old_metal_table();
	});
	
	$('#soldNpend_search').click(function(event) {
		set_lotwise_table();
	});
	
	$('#partlySold_search').click(function(event) {
		set_partlysold_table();
	});
	$('#credit_issued_search').click(function(event) {
		set_credit_issued_table();
	});
	$('#credit_history_search').click(function(event) {
		set_credit_history_table();
	});
	$('#advance_search').click(function(event) {
		set_advance_history_table();
	});
	$('#chit_closing_search').click(function(event) {
		set_chit_closing_table();
	});
	$('#pay_search').click(function(event) {
		set_card_payment_table();
	});
	$('#copy_bill_search').click(function(event) {
		set_copy_bill_table();
	});
	$('#pan_bill_search').click(function(event) {
		set_pan_bill_table();
	});
	
	$('#stockAge_search').click(function(event) {
		set_stock_age_table();
	});
	
	$('#cancelled_bill_search').click(function(event) {
		set_cancelledBills();
	});
	
	$('#disc_bill_search').click(function(event) {
		set_discBills();
	});
	
	$('#gst_bill_search').click(function(event) {
		set_gstBills();
	}); 
	
	$('#reorder_items_search').click(function(event){
		set_reorder_items();
	});
	
	$('#branchreorder_items_search').click(function(event){
		set_branchreorder_items();
	});
	
	
	
	$('#tag_design_search').click(function(event){
		set_tagged_items();
	});
	
	$('#stock_search').click(function(event){
		stock_report();
	});	
	
	$('#stock_checking').click(function(event){
		stock_checking();
	});	
	
	$('#item_sale_search').click(function(event){
		itemwise_sales();
	});	
	
	$('#bill_wise_search').click(function(event){
		get_bill_wise_trns_list();
	});
	
	$('#home_bill_search').click(function(event){
		get_home_bill_sales();
	});	
	$('#order_adv_search').click(function(event){
    	set_order_advance()
	});
	
	$('#ref_search').click(function(event){
    	set_estimation_referral()
	});
	
	$('#other_issue_search').click(function(event){
		set_other_issue();
	});	
	
	$('#gift_search').click(function(event){
		set_gift_voucher_list();
	});
	
	$('#order_status_search').click(function(event){
		set_order_status_report();
	});
	
	$('#sales_search').click(function(event){
		set_monthly_sales();
	});
	
	$('#old_metal_analyse_search').click(function(event) {
		set_old_metal_analyse_table();
	});
    $('#tag_history_search').click(function(event) {
		set_tag_history();
	});
	
	 $('#sales_analysis').click(function(event) {
		get_sales_analysis_report();
	});
	
	$("body").on("click","#home_city", function(){
 		$('.other_city').css('display','none');
 		$('.home_city').css('display','block');
 		get_sales_analysis_report();
    });
    
    $("body").on("click","#other_city", function(){
 		$('.home_city').css('display','none');
 		$('.other_city').css('display','block');
 		get_sales_analysis_other_city_report();
    });
	
	$('#unbilled_est_search').click(function(event) {
		set_unbilled_estimation();
	});
	
	$('#karigar_wise_sales').click(function(event) {
		get_karigar_wise_sales();
	});
	
	$('#sales_comparision_search').click(function(event) {
		get_sales_comparision();
	});
	
	$('#old_tag_report_search').on('click',function(){
	    get_old_sale_report_report();
	});
	
	
    $('#tag_scanned_search').click(function(event){
			getScanned_details();
	});
	
    $('#credit_pending_search').click(function(event) {
		set_credit_pending_list();
	});
	
	$('#scanned_details_report').click(function(event){
			get_scanned_details_report();
	});
	
	$('#sales_stock_Search').click(function(event){
			set_stock_and_sales_list();
	});
	
	$('#monthly_sales_comparision_search').click(function(event){
			monthly_slaes_comparision();
	});
    
    $('#customer_search').click(function(event){
			get_telecalling_report();
	});
    
    $('#incentive_search').on('click',function(){
        set_incentive_report();
    });
    
    $('#metal_stock_search').click(function(event){
            if($('#report_type').val()==1 || $('#report_type').val()==2 || $('#report_type').val()==5)
            {
                get_metal_stock_details();
            }else
            {
                get_old_metal_stock_details();
            }
			
	});
	
	$('#po_bills_search').click(function(event){
			get_po_bills_details();
	});
	
	$('#po_payment_search').click(function(event){
	   if($('#karigar').val() != "" && $('#karigar').val() != "0"){
			get_po_payment_details();
	   }
	});
	
	$('#po_qcbills_search').click(function(event){
			get_qc_issue_details();
	});
	
	$('#po_bills_hmsearch').click(function(event){
			get_hm_issue_details();
	});
	
	$('#tag_wise_profit_search').click(function(event){
		get_tag_wise_profit_list();
	});
	
	$('#approvaltag_design_search').click(function(event){
		set_approval_tagged_items();
	});
	
	$('#metal_available_stock_search').click(function(event){
			get_metal_available_stock_details();
	});
	
	$('#disc_rep_type').select2();
	
	$('#cash_abstract_print').click(function(event) {
		var dt_range=($("#dt_range").val()).split('-');
		var from_date=date_format(dt_range[0].trim());
		var to_date=date_format(dt_range[1].trim());
		var id_branch=$("#branch_select").val();
		window.open(base_url+'index.php/admin_ret_reports/generate_cash_abstract/'+id_branch+'/'+from_date+'/'+to_date);
	});
	$('#export_csv').click(function(event) {
		var dt_range=($("#dt_range").val()).split('-');
		var from_date=date_format(dt_range[0].trim());
		var to_date=date_format(dt_range[1].trim());
		var id_branch=$("#branch_select").val();
		window.location.href=base_url+'index.php/admin_ret_reports/export_csv/'+id_branch+'/'+from_date+'/'+to_date;
	});
	
	$('#cash_abstract_search').click(function(event) {
		set_cashabstract_table();
	});
	
	$('#bt_report_search').click(function(event) {
		set_btreport_table();
	});
	
	 $('#advance_total_search').on('click',function()
    {
        get_advance_details();
    });
	
	$('.range_inputs .applyBtn').click(function(event) {
	    if(ctrl_page[1] == 'supplierledger'){
	        getSupplierLedger();    
	    }
		
	});
});
function date_format(dt_range)
	{
		var date = dt_range.split('/');
		return date[2] + '-' + date[1] + '-' + date[0];
	}
function set_stock_age_table(branch="")
{
	$("div.overlay").css("display", "block"); 
	var id_branch = ($('#branch_filter').val()!='' && $('#branch_filter').val()!=undefined ? $('#branch_filter').val(): $("#branch_select").val());
	my_Date = new Date();
	$.ajax({
		url:base_url+ "index.php/admin_ret_reports/stock_age/ajax?nocache=" + my_Date.getUTCSeconds(),
		data: ( {'id_branch':id_branch,'id_product':$("#prod_select").val(),'id_metal':$("#metal").val(),'id_ret_category':$("#category").val()}),
		dataType:"JSON",
		type:"POST",
		success:function(data){  
			$("div.overlay").css("display", "none"); 
		 	var list = data.list;
			$('#total_count').text(list.length);
			var oTable = $('#stock_age_list').DataTable();
			oTable.clear().draw();				  
			if (list!= null && list.length > 0)
			{  	
				oTable = $('#stock_age_list').dataTable({
					"bDestroy": true,
	                "bInfo": true,
	                "bFilter": true,
	                "scrollX":'100%',
	                "bSort": true,
	                "dom": 'lBfrtip',
	                "order": [[ 0, "asc" ]],
	                "buttons": [
							 {
							   extend: 'print',
							   footer: true,
							   title: "Stock Age Analysis",
							   customize: function ( win ) {
									$(win.document.body).find( 'table' )
										.addClass( 'compact' )
										.css( 'font-size', 'inherit' );
								},
							 },
							 {
								extend:'excel',
								footer: true,
							    title: "Stock Age Analysis",
							  }
							 ],
					"aaData": list,
					"aoColumns": [	
					                { "mDataProp": "pro_id" },
					                { "mDataProp": "product_name" },
					                
					                { "mDataProp": function ( row, type, val, meta ){
									    var url=base_url+'index.php/admin_ret_reports/stock_age/tag_list/0/120/'+row.pro_id+'/'+id_branch;
										 return '<a href='+url+' target="_blank">'+row.below_120_days+'</a>';
									}
									},
									
									{ "mDataProp": function ( row, type, val, meta ){
									    var url=base_url+'index.php/admin_ret_reports/stock_age/tag_list/120/180/'+row.pro_id+'/'+id_branch;
										 return '<a href='+url+' target="_blank">'+row.above_120_days+'</a>';
									}
									},
									{ "mDataProp": function ( row, type, val, meta ){
									    var url=base_url+'index.php/admin_ret_reports/stock_age/tag_list/180/240/'+row.pro_id+'/'+id_branch;
										 return '<a href='+url+' target="_blank">'+row.above_180_days+'</a>';
									}
									},
									{ "mDataProp": function ( row, type, val, meta ){
									    var url=base_url+'index.php/admin_ret_reports/stock_age/tag_list/240/360/'+row.pro_id+'/'+id_branch;
										 return '<a href='+url+' target="_blank">'+row.above_240_days+'</a>';
									}
									},
									{ "mDataProp": function ( row, type, val, meta ){
									    var url=base_url+'index.php/admin_ret_reports/stock_age/tag_list/360/'+''+'/'+row.pro_id+'/'+id_branch;
										 return '<a href='+url+' target="_blank">'+row.above_360_days+'</a>';
									}
									},
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
function get_stock_age_tag_list()
{
    $("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
		url:base_url+ "index.php/admin_ret_reports/stock_age/tagging?nocache=" + my_Date.getUTCSeconds(),
		data: ( {'from_days':ctrl_page[3],'to_days':ctrl_page[4],'id_product':ctrl_page[5],'id_branch':ctrl_page[6]}),
		dataType:"JSON",
		type:"POST",
		success:function(data){  
			$("div.overlay").css("display", "none"); 
		 	var list = data;
			var oTable = $('#stock_age_tag').DataTable();
			oTable.clear().draw();				  
			if (list!= null && list.length > 0)
			{  	
				oTable = $('#stock_age_tag').dataTable({
					"bDestroy": true,
	                "bInfo": true,
	                "bFilter": true,
	                "scrollX":'100%',
	                "bSort": true,
	                "dom": 'lBfrtip',
	                "order": [[ 0, "asc" ]],
	                "lengthMenu": [[-1, 25, 50, 100, 250], ["All" ,25, 50, 100, 250]],
	                "buttons": [
							 {
							   extend: 'print',
							   footer: true,
							   title: "Stock Age Analysis",
							   customize: function ( win ) {
									$(win.document.body).find( 'table' )
										.addClass( 'compact' )
										.css( 'font-size', 'inherit' );
								},
							 },
							 {
								extend:'excel',
								footer: true,
							    title: "Stock Age Analysis",
							  }
							 ],
					"aaData": list,
					"aoColumns": [	
                                    { "mDataProp": function ( row, type, val, meta )
                                    { 
                                    return '<input type="checkbox" class="tag_id" name="tag_id[]" value="'+row.tag_id+'"/>'+row.tag_id;
                                    }},
					                { "mDataProp": "tag_code"},
					                { "mDataProp": "old_tag_id"},
					                { "mDataProp": "tag_datetime"},
					                { "mDataProp": "old_tag_date"},
					                { "mDataProp": "age"},
					                { "mDataProp": "product_name"},
					                { "mDataProp": "karigar_name"},
					                { "mDataProp": "tag_lot_id"},
					                { "mDataProp": "total_wt"},
					                { "mDataProp": "retail_max_wastage_percent"},
					                { "mDataProp": "tag_mc_value"},
					                { "mDataProp": function ( row, type, val, meta )
                                    { 
                                        if(row.tag_mark==1)
										{
										     return '<span class="badge bg-green">'+row.tag_status+(row.is_green_tag_printed==1 ? ' - <i class="fa fa-print" aria-hidden="true"></i>' :'')+'</span>';
										}else{
										    return '-'+(row.is_green_tag_printed==1 ? '<i class="fa fa-print" aria-hidden="true"></i>' :'');
										}
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
												i : 0;
											};	
											
											$(api.column(0).footer() ).html('Total');	
											gross_wgt = api
											.column(5)
											.data()
											.reduce( function (a, b) {
												return intVal(a) + intVal(b);
											}, 0 );
											$(api.column(5).footer()).html(parseFloat(gross_wgt).toFixed(3));	 
									} 
									}else{
										 var api = this.api(), data; 
										 $(api.column(5).footer()).html('');
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
$("input[name='upd_status_btn']:radio").change(function(){
    if($("input[name='tag_id[]']:checked").val())
        {
            $('#set_green_tag').prop('disabled',true);
            var selected = [];
            var approve=false;
            $("#stock_age_tag tbody tr").each(function(index, value)
            {
                if($(value).find("input[name='tag_id[]']:checked").is(":checked"))
                {
                    transData = { 
                    'tag_id'            : $(value).find(".tag_id").val(),
                    "req_status"        : $("input[name='upd_status_btn']:checked").val(),
                    }
                    selected.push(transData);	
                }
            })
            req_data = selected;
            update_green_tag(req_data);
        }else{
            $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select Any One Tag..'});
        }
});
function update_green_tag(data="")
{
    my_Date = new Date();
    $("div.overlay").css("display", "block"); 
    $.ajax({
    url:base_url+ "index.php/admin_ret_reports/update_green_tag?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),
    data:  {'req_data':data},
    type:"POST",
    async:false,
    success:function(data){
    $('#set_green_tag').prop('disabled',false);
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
$("#tag_print").on('click',function(){
        if($("input[name='tag_id[]']:checked").val())
    	{
    		var selected = [];
    		var tag_id='';
    		$("#stock_age_tag tbody tr").each(function(index, value){
    		if($(value).find("input[name='tag_id[]']:checked").is(":checked"))
    		{
    		tag_id+= $(value).find(".tag_id").val()+',';
    		transData = { 
    		'tag_id': $(value).find(".tag_id").val(),
    		}
    		}
    		});
    		req_data = tag_id;
    		tagging_print(req_data);
    	}
    	else
    	{
            $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select Any One Tag..'});
        }
});
function tagging_print(req_data)
{
	//var tag = JSON.stringify(req_data);
	window.open(base_url+'index.php/admin_ret_tagging/tagging/generate_barcode?tag='+req_data,'_blank');
	window.location.reload();
}
function get_ActiveKarigars()
{
	$.ajax({
		type: 'GET',
		url: base_url+'index.php/admin_ret_catalog/karigar/active_list',
		dataType:'json',
		success:function(data){
			var id =  $("#karigar").val();
			$("#karigar").append(						
				$("<option></option>")						
				.attr("value", 0)						  						  
				.text('All')
				);
		   $.each(data,function (key, item) {
			   		$('#karigar').append(
						$("<option></option>")
						  .attr("value", item.id_karigar)
						  .text(item.karigar)
					);
			}); 
			
			$('#karigar').select2("val","");
		}
	});
}
$("#metal").on('change',function(){
   if(this.value!='')
   {
       get_category();
   }
});
function get_ActiveMetals()
{
	$.ajax({
		type: 'GET',
		url: base_url+'index.php/admin_ret_catalog/ret_product/active_metal',
		dataType:'json',
		success:function(data){
			var id =  $("#metal").val();
			$("#metal").append(						
				$("<option></option>")						
				.attr("value", 0)						  						  
				.text('All' )
				);
		    $.each(data,function (key, item) {
			   		$('#metal').append(
						$("<option></option>")
						  .attr("value", item.id_metal)
						  .text(item.metal)
					);
			});
			$("#metal").select2(
			{
				placeholder:"Select Metal",
				allowClear: true		    
			});  	
		}
	});
}
function get_ActiveFloors()
{
    $('#floor_sel option').remove();
    $("div.overlay").css("display", "block"); 
    $.ajax({
    type: 'POST',
    url: base_url+'index.php/admin_ret_catalog/get_ActiveBranchFloor',
    dataType:'json',
    data:{'id_branch':($('#branch_filter').val()!='' && $('#branch_filter').val()!=undefined ? $('#branch_filter').val(): $("#branch_select").val())},
    success:function(data){
        var id =  $("#floor_sel").val();
        $("#floor_sel").append(						
        $("<option></option>")						
        .attr("value", 0)						  						  
        .text('All' )
        );
        $.each(data,function (key, item) {
            $('#floor_sel').append(
            $("<option></option>")
            .attr("value", item.floor_id)
            .text(item.floor_name)
            );
        });
        $("#floor_sel").select2(
        {
        placeholder:"Select Floor",
        allowClear: true		    
        });
        $('#floor_sel').select2("val",(id!='' && id>0?id:''));
        $(".overlay").css("display", "none");	
    }
    });
}
$(document).on("change", "#floor_sel", function() {
    if(this.value!='')
    {
        get_ActiveCounters();
    }
    
});
function get_ActiveCounters()
{
    $('#counter_sel option').remove();
    $("div.overlay").css("display", "block"); 
    $.ajax({
    type: 'POST',
    url: base_url+'index.php/admin_ret_catalog/get_ActiveCounter',
    dataType:'json',
    data:{'id_branch':($('#branch_filter').val()!='' && $('#branch_filter').val()!=undefined ? $('#branch_filter').val(): $("#branch_select").val()),'id_floor' : $("#floor_sel").val()},
    success:function(data){
        var id =  $("#counter").val();
        $("#counter_sel").append(						
        $("<option></option>")						
        .attr("value", 0)						  						  
        .text('All' )
        );
        $.each(data,function (key, item) {
            $('#counter_sel').append(
            $("<option></option>")
            .attr("value", item.counter_id)
            .text(item.counter_name)
            );
        });
        $("#counter_sel").select2(
        {
        placeholder:"Select Counter",
        allowClear: true		    
        });
        $('#counter_sel').select2("val",(id!='' && id>0?id:''));
        $(".overlay").css("display", "none");	
    }
    });
} 
function set_lotwise_table()
{
	$("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/lot_wise/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'lot_no' :$("#filter_lot").val(),'karigar' :$("#karigar").val(),'id_product' :$("#id_product").val(),'from_date' :$("#rpt_payments1").html(),'to_date':$("#rpt_payments2").html(),'id_branch':$("#branch_select").val()}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			 	$("div.overlay").css("display", "none"); 
			 	var list = data.list;
				$('#total_count').text(list.length);
				var oTable = $('#lotwise_list').DataTable();
				oTable.clear().draw();				  
				if (list!= null && list.length > 0)
				{  	
					oTable = $('#lotwise_list').dataTable({
						"bDestroy": true,
		                "bInfo": true,
		                "bFilter": true,
						"order": [[ 0, "desc" ]],
		                "scrollX":'100%',
		                "bSort": true,
		                "dom": 'lBfrtip',
		                "buttons": [
								 {
								   extend: 'print',
								   footer: true,
								   title: "Lot-wise Sold & Pending - "+$("#dt_range").val(),
								   customize: function ( win ) {
										$(win.document.body).find( 'table' )
											.addClass( 'compact' )
											.css( 'font-size', 'inherit' );
									},
								 },
								 {
									extend:'excel',
									footer: true,
								    title: "Lot-wise Sold & Pending - "+$("#dt_range").val(), 
								  }
								 ],
						"aaData": list,
						"aoColumns": [	
						                { "mDataProp": "lot_no" },
						                { "mDataProp": "karigar_name" },
										{ "mDataProp": "no_of_pcs" },
										{ "mDataProp": "total_gwt" },
										{ "mDataProp": "sold_pcs" },
										{ "mDataProp": "sold_gwt" },
										{ "mDataProp": "pending_pcs" },
										{ "mDataProp": "pending_gwt" },
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
function getSearchProd(searchTxt){
	my_Date = new Date();
	$.ajax({
        url: base_url+'index.php/admin_ret_catalog/product/active_prodBySearch/?nocache=' + my_Date.getUTCSeconds(),             
        dataType: "json", 
        method: "POST", 
        data: {'searchTxt':searchTxt,'lotno':$("#lotno").val(),'cat_id':$("#category").val()}, 
        success: function (data) { 
			$( "#product" ).autocomplete(
			{
				source: data,
				select: function(e, i)
				{ 
					e.preventDefault();
					$("#product" ).val(i.item.label); 
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
        data: {'searchTxt':searchTxt,'prodId':$("#id_product").val(),'lotno':$("#lotno").val()}, 
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
function set_partlysold_table()
{
	$("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/partly_sold/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'lot_no' :$("#filter_lot").val(),'id_product' :$("#id_product").val(),'dt_range' :$("#dt_range").val(),'id_branch':($('#branch_filter').val()!='' && $('#branch_filter').val()!=undefined ? $('#branch_filter').val(): $("#branch_select").val())}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			 	$("div.overlay").css("display", "none"); 
			 	var list = data.list;
				$('#total_count').text(list.length);
				var oTable = $('#partlysold_list').DataTable();
				oTable.clear().draw();				  
				if (list!= null && list.length > 0)
				{  	
					oTable = $('#partlysold_list').dataTable({
						"bDestroy": true,
		                "bInfo": true,
		                "bFilter": true,
						"order": [[ 0, "desc" ]],
		                "scrollX":'100%',
		                "bSort": true,
		                "dom": 'lBfrtip',
		                "buttons": [
								 {
								   extend: 'print',
								   footer: true,
								   title: "Partly Sold & Pending - "+$("#dt_range").val(),
								   customize: function ( win ) {
										$(win.document.body).find( 'table' )
											.addClass( 'compact' )
											.css( 'font-size', 'inherit' );
									},
								 },
								 {
									extend:'excel',
									footer: true,
								    title: "Partly Sold & Pending - "+$("#dt_range").val(), 
								  }
								 ],
						"aaData": list,
						"aoColumns": [	
						                { "mDataProp": "branch_name" },
						                { "mDataProp": "bill_date" },
										{ "mDataProp": "bill_no" },
										{ "mDataProp": "tag_code" },
										{
                                        "mDataProp": null,
                                        "sClass": "control center", 
                                        "sDefaultContent":  '<span class="drill-val"><i class="fa fa-chevron-circle-down text-teal"></i></span>'
                                        },
										{ "mDataProp": "product" },
										{ "mDataProp": "design" },
										{ "mDataProp": "actual_pieces" },
										{ "mDataProp": "actual_gross_wt" },
										{ "mDataProp": "actual_net_wt" },
										{ "mDataProp": "sold_pieces" },
										{ "mDataProp": function ( row, type, val, meta ){
											var sold_net_wt=0;
			 								var sold_gross_wt=0;
											var partial_details=row.partial_details;
											console.log(partial_details);
											$.each(partial_details,function(key,item){
												sold_gross_wt+= parseFloat(item.sold_gross_wt);
											});
											console.log(sold_gross_wt);
											return sold_gross_wt;
										}
										},
										{ "mDataProp": function ( row, type, val, meta ){
											var sold_net_wt=0;
			 								var sold_gross_wt=0;
											var partial_details=row.partial_details;
											$.each(partial_details,function(key,item){
												sold_net_wt+= parseFloat(item.sold_net_wt);
											});
											return sold_net_wt;
										}
										},
										{ "mDataProp": function ( row, type, val, meta ){
											return  parseFloat(row.actual_pieces) - parseFloat(row.sold_pieces);
										}
										},
										{ "mDataProp": function ( row, type, val, meta ){
			 								var sold_gross_wt=0;
											var partial_details=row.partial_details;
											console.log(partial_details);
											$.each(partial_details,function(key,item){
												sold_gross_wt+= parseFloat(item.sold_gross_wt);
											});
											return parseFloat(row.actual_gross_wt-sold_gross_wt).toFixed(2);
										}
										},
										{ "mDataProp": function ( row, type, val, meta ){
			 								var sold_net_wt=0;
											var partial_details=row.partial_details;
											console.log(partial_details);
											$.each(partial_details,function(key,item){
												sold_net_wt+= parseFloat(item.sold_net_wt);
											});
											return parseFloat(row.actual_net_wt-sold_net_wt).toFixed(2);
										}
										},
									
									],
						"footerCallback": function( row, data, start, end, display ){
							var cshtotal = 0; 
							if(data.length>0){
								var api = this.api(), data;
								for( var i=0; i<=data.length-1;i++){
									var intVal = function ( i ) {
										return typeof i === 'string' ?
										i.replace(/[\$,]/g, '')*1 :
										typeof i === 'number' ?
										i : 0;
									};	
									
									$(api.column(0).footer() ).html('Total');	
									
									pieces = api
									.column(7)
									.data()
									.reduce( function (a, b) {
										return intVal(a) + intVal(b);
									}, 0 );
									$(api.column(7).footer()).html(parseFloat(pieces).toFixed(2));
									
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
									
									
									sold_pieces = api
									.column(10)
									.data()
									.reduce( function (a, b) {
										return intVal(a) + intVal(b);
									}, 0 );
									$(api.column(10).footer()).html(parseFloat(sold_pieces).toFixed(2));
									
									sold_gross_wt = api
									.column(11)
									.data()
									.reduce( function (a, b) {
										return intVal(a) + intVal(b);
									}, 0 );
									$(api.column(11).footer()).html(parseFloat(sold_gross_wt).toFixed(2));
									
									
									sold_net_wt = api
									.column(12)
									.data()
									.reduce( function (a, b) {
										return intVal(a) + intVal(b);
									}, 0 );
									$(api.column(12).footer()).html(parseFloat(sold_net_wt).toFixed(2));
									
									
									blc_pieces = api
									.column(13)
									.data()
									.reduce( function (a, b) {
										return intVal(a) + intVal(b);
									}, 0 );
									$(api.column(13).footer()).html(parseFloat(blc_pieces).toFixed(2));
									
									blc_gross_wt = api
									.column(14)
									.data()
									.reduce( function (a, b) {
										return intVal(a) + intVal(b);
									}, 0 );
									$(api.column(14).footer()).html(parseFloat(blc_gross_wt).toFixed(2));
									
									
									blc_net_wt = api
									.column(15)
									.data()
									.reduce( function (a, b) {
										return intVal(a) + intVal(b);
									}, 0 );
									$(api.column(15).footer()).html(parseFloat(blc_net_wt).toFixed(2));
									
									
									
									 
									 
									
 								} 
							}else{
								 var api = this.api(), data; 
								 $(api.column(5).footer()).html('');
								 $(api.column(6).footer()).html('');
								 $(api.column(7).footer()).html('');
								 $(api.column(8).footer()).html('');    
								 $(api.column(9).footer()).html('');    
								 $(api.column(10).footer()).html('');    
								 $(api.column(11).footer()).html('');    
								 $(api.column(12).footer()).html('');    
								 $(api.column(13).footer()).html('');     
							}
						}
	
					});	
					
					var anOpen =[]; 
            		$(document).on('click',"#partlysold_list .control", function(){ 
            		   var nTr = this.parentNode;
            		   var i = $.inArray( nTr, anOpen );
            		 
            		   if ( i === -1 ) { 
            				$('.drill-val', this).html('<i class="fa fa-chevron-circle-up text-teal"></i>'); 
            				oTable.fnOpen( nTr, fnFormatRowTagPartialDetails(oTable, nTr), 'details' );
            				anOpen.push( nTr ); 
            		    }
            		    else { 
            				$('.drill-val', this).html('<i class="fa fa-chevron-circle-down text-teal"></i>');
            				oTable.fnClose( nTr );
            				anOpen.splice( i, 1 );
            		    }
            		} );
				}
			  },
			  error:function(error)  
			  {
				 $("div.overlay").css("display", "none"); 
			  }	 
	      });
}
function fnFormatRowTagPartialDetails( oTable, nTr )
{
  var oData = oTable.fnGetData( nTr );
  var rowDetail = '';
  var prodTable = 
     '<div class="innerDetails">'+
      '<table class="table table-responsive table-bordered text-center table-sm">'+ 
        '<tr class="bg-teal">'+
        '<th>S.No</th>'+ 
        '<th>Tag Id</th>'+
        '<th>G.wt </th>'+
        '<th>N.wt </th>'+
        '</tr>';
    var partial_details = oData.partial_details; 
  $.each(partial_details, function (idx, val) {
  	var ref_no = val.tag_id+'_'+oData.product_id;
  	prodTable += 
        '<tr class="prod_det_btn">'+
        '<td>'+parseFloat(idx+1)+'</td>'+
        '<td>'+val.tag_id+'</td>'+
        '<td>'+val.sold_gross_wt+'</td>'+
        '<td>'+val.sold_net_wt+'</td>'+
        '</tr>'; 
  }); 
  rowDetail = prodTable+'</table></div>';
  return rowDetail;
}
/*function set_cashabstract_table()
{
		$("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/cash_abstract/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'dt_range' :$("#dt_range").val(),'id_branch':$("#branch_select").val()}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
				$("div.overlay").css("display", "none"); 
			 	var credit_details = data.list.credit_details;
			 	var due_details = data.list.due_details;
			 	var sales_details = data.list.item_details;
			 	var purchase_details = data.list.old_matel_details;
			 	var return_details = data.list.return_details;
			 	var payment_details = data.list.payment_details;
			 	var chit_details = data.list.chit_details;
			 	var advance_detals = data.list.advance_detals;
			 	var voucher_details = data.list.voucher_details;
			 	var cash_return = data.list.cash_return;
			 	var branch_transfer = data.list.branch_transfer_details;
				var groupColumn = 1;
				var total_return_amount=0;
				var total_return_tax=0;
				var total_taxable_amount = 0; 
				var total_item_tax = 0; 
				var total_purchase_amount = 0; 
				var total_sales_return = 0; 
				var final_price = 0; 
				var cash_return_payment= 0; 
				var cash_payment=0
				var card_payment=0
				var cheque_payment=0
				var online_payment=0
				var chit_payment=0
				var gift_voucher=0
				var partial_gwt=0;
				var partial_nwt=0;
				var due_amt=0;
				var credit_amount=0;
				
				//SALES DETAILS START	
				if(due_details.length>0)
				{
					$.each(due_details,function(key,items){
						due_amt +=parseFloat(items.due_amt);		
					});
				}
				if(credit_details.length>0)
				{
					$.each(credit_details,function(key,items){
						credit_amount +=parseFloat(items.tot_amt_received);		
					});
				}
				var oTable = $('#sales_list').DataTable();
				oTable.clear().draw();			  
				if (sales_details.length > 0)
				{  	
					$('.sales_details').css('display','block');
					var avg_rate=0;
					oTable = $('#sales_list').dataTable({
						"bDestroy": true,
		                "bInfo": true,
		                "bFilter": true,
						"order": [[ 0, "desc" ]],
		                "scrollX":'100%',
		                "bSort": true,
		                "paging": false,
						"aaData": sales_details,
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
						api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) 
						{
							var data = api.row(api.row($(rows).eq(i)).index()).data();  //Response data
							var taxable_amount = parseFloat(data.item_cost) - parseFloat(data.item_total_tax);
							var bill_discount = data.bill_discount;
							var net_wt = data.net_wt;
							if(data.is_partial_sale==1)
							{
								$('.partial_sale_details').css('display','block');
								partial_gwt=parseFloat(data.gross_wt)/2;
								partial_nwt=parseFloat(data.net_wt)/2;
							}
							if ( typeof aData[group] == 'undefined') 
							{
								aData[group] = new Array();
								aData[group].rows = [];
								aData[group].sales_details = [];
							}
							aData[group].rows.push(i); 
							aData[group].sales_details.push({'taxable_amount':taxable_amount,'item_total_tax':data.item_total_tax,'bill_discount':bill_discount,'net_wt':net_wt,'id_ret_category':data.id_ret_category}); 
						});
						var idx= 0;
							for(var category_name in aData)  //column name
							{  
								
								var cat_amount = 0; 
								var cat_bill_discount = 0; 
								var cat_net_wt = 0; 
								idx =  Math.max.apply(Math,aData[category_name].rows);
								$.each(aData[category_name].sales_details,function(k,v){
										total_taxable_amount +=parseFloat(v.taxable_amount);
										total_item_tax +=parseFloat(v.item_total_tax);
										cat_amount = parseFloat(cat_amount)+parseFloat(v.taxable_amount);
										cat_bill_discount = parseFloat(cat_bill_discount)+parseFloat(v.bill_discount);
										cat_net_wt = parseFloat(cat_net_wt)+parseFloat(v.net_wt);
								});
								$(rows).eq( idx ).after(
								'<tr class="group" style="background-color: #ccc;font-weight: bold;"><td class="tot-label" colspan="3">Total</td>'+
								'<td class="total">'+(cat_net_wt.toFixed(3))+'</td><td class="total">'+(cat_amount.toFixed(2))+'</td><td class="total">'+(cat_bill_discount.toFixed(2))+'</td><td></td><td></td><td></td></tr>'
								);
								
							};
						},
						"aoColumns": [	
										{ "mDataProp": "category_name" },
										{ "mDataProp": "category_name" },
										{ "mDataProp": "product_name" },
										{ "mDataProp": "piece" },
										{ "mDataProp": "net_wt" },
										{ "mDataProp": function ( row, type, val, meta ){
											return  parseFloat(parseFloat(row.item_cost) - parseFloat(row.item_total_tax)).toFixed(2);
										}
										},
										{ "mDataProp": "bill_discount" },
										{ "mDataProp": function ( row, type, val, meta ){
											return 0;
										}
										},
										{ "mDataProp": function ( row, type, val, meta ){
											var amount=parseFloat(row.item_cost) - parseFloat(row.item_total_tax) ;
											if(row.has_fixed_price==0)
											{
												avg_rate=parseFloat(parseFloat(amount)/parseFloat(row.net_wt)).toFixed(2);
											}else{
												avg_rate=parseFloat(parseFloat(amount)/parseFloat(row.piece)).toFixed(2);
											}
											//return avg_rate;
											return 0;
										}
										},
										{ "mDataProp": function ( row, type, val, meta ){
											//return parseFloat(parseFloat(avg_rate) - parseFloat(row.rate_per_grm)).toFixed(2);
											return 0;
										}
										},
			
									],
							"footerCallback": function( row, data, start, end, display ){
							var cshtotal = 0; 
							if(data.length>0){
								var api = this.api(), data;
								for( var i=0; i<=data.length-1;i++){
									var intVal = function ( i ) {
										return typeof i === 'string' ?
										i.replace(/[\$,]/g, '')*1 :
										typeof i === 'number' ?
										i : 0;
									};	
									
									$(api.column(0).footer() ).html('Total');	
									net_wgt = api
									.column(4)
									.data()
									.reduce( function (a, b) {
										return intVal(a) + intVal(b);
									}, 0 );
									$(api.column(4).footer()).html(parseFloat(net_wgt).toFixed(2));
									
 									taxable_amount = api
									.column(5)
									.data()
									.reduce( function (a, b) {
										return intVal(a) + intVal(b);
									}, 0 );
									$(api.column(5).footer()).html(parseFloat(taxable_amount).toFixed(2));
									
 									discount = api
									.column(6)
									.data()
									.reduce( function (a, b) {
										return intVal(a) + intVal(b);
									}, 0 );
									$(api.column(6).footer()).html(parseFloat(discount).toFixed(2));
									
 								} 
							}else{
								 var api = this.api(), data; 
								 $(api.column(4).footer()).html('');
								 $(api.column(5).footer()).html('');
								 $(api.column(6).footer()).html('');
								 
							}
						}
	
					});		  	 	
				}
				$('.partial_gwt').html(partial_gwt);
							$('.partial_nwt').html(partial_nwt);
				//SALES DETAILS END
				//PURCHASE DETAILS 	START
				var purchase = $('#purchase_list').DataTable();
				purchase.clear().draw();	
				if(purchase_details.length > 0)
				{  		
					$('.purchase_details').css('display','block');
					var avg_rate=0;
					var table_data = new Array();
						var index = 0;
					purchase = $('#purchase_list').dataTable({
						"bDestroy": true,
		                "bInfo": true,
		                "bFilter": true,
						"order": [[ 0, "desc" ]],
		                "scrollX":'100%',
		                "bSort": true,
		                "paging": false,
						"aaData": purchase_details,
						"columnDefs": [
                                		{ "visible": false, "targets": groupColumn }
                                	  ],
						"drawCallback": function ( settings ) {
						var api = this.api();
						var rows = api.rows( {page:'current'} ).nodes();
						
						api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) 
						{
							var data = api.row(api.row($(rows).eq(i)).index()).data();  //Response data
							if ( typeof table_data[group] == 'undefined') 
							{
								table_data[group] = new Array();
								table_data[group].rows = [];
								table_data[group].purchase_items = [];
							}
							table_data[group].rows.push(i); 
							table_data[group].purchase_items.push({'amount':data.amount,'gross_wt':data.gross_wt,'stone_wt':data.stone_wt,'dust_wt':data.dust_wt,'purpose':data.purpose}); 
						});
						var idx= 0;
							for(var metal in table_data)  //column name
							{  
								var amount = 0; 
								var gross_wt = 0; 
								var net_wt = 0; 
								idx =  Math.max.apply(Math,table_data[metal].rows);
								console.log(table_data);
								$.each(table_data[metal].purchase_items,function(k,v){
										total_purchase_amount += parseFloat(v.amount);
										amount = parseFloat(amount)+parseFloat(v.amount);
										gross_wt = parseFloat(gross_wt)+parseFloat(v.gross_wt);
										net_wt = parseFloat(net_wt)+parseFloat(parseFloat(v.gross_wt) - (parseFloat(v.stone_wt)+parseFloat(v.dust_wt)));
								});
								$(rows).eq( idx ).after(
								'<tr class="group" style="background-color: #ccc;font-weight: bold;"><td class="tot-label" colspan="1">Total</td>'+
								'<td class="total">'+(gross_wt.toFixed(2))+'</td><td class="total">'+(net_wt.toFixed(2))+'</td><td class="total">'+(amount.toFixed(2))+'</td><td></td></tr>'
								);
								
							};
						},
						"aoColumns": [	
										{ "mDataProp": "metal" },
										{ "mDataProp": "metal" },
										{ "mDataProp": "gross_wt" },
										{ "mDataProp": function ( row, type, val, meta ){
											return  parseFloat(parseFloat(row.gross_wt) - (parseFloat(row.stone_wt)+parseFloat(row.dust_wt))).toFixed(3);
										}
										},
										
										{ "mDataProp": "amount" },
										{ "mDataProp": function ( row, type, val, meta ){
											avg_rate=parseFloat(parseFloat(row.amount)/(parseFloat(row.net_wt))).toFixed(2);
											return avg_rate;
										}
										},
										
									],
							"footerCallback": function( row, data, start, end, display ){
							if(data.length>0){
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
									.column(2)
									.data()
									.reduce( function (a, b) {
										return intVal(a) + intVal(b);
									}, 0 );
									$(api.column(2).footer()).html(parseFloat(gross_wt).toFixed(2));
									
									net_wgt = api
									.column(3)
									.data()
									.reduce( function (a, b) {
										return intVal(a) + intVal(b);
									}, 0 );
									$(api.column(3).footer()).html(parseFloat(net_wgt).toFixed(2));
									
 									
 									amount = api
									.column(4)
									.data()
									.reduce( function (a, b) {
										return intVal(a) + intVal(b);
									}, 0 );
									$(api.column(4).footer()).html(parseFloat(amount).toFixed(2));
 								} 
							}else{
								 var api = this.api(), data; 
								 $(api.column(2).footer()).html('');
								 $(api.column(3).footer()).html('');
								 $(api.column(4).footer()).html('');
								 
							}
						}
						
	
					});		  	 	
				}
				//PURCHASE DETAILS END
				//RETURN DETAILS START
				var return_item = $('#return_item').DataTable();
				return_item.clear().draw();	
				if (return_details.length > 0 && return_details[0]['item_cost']>0)
				{  	
					$('.return_details').css('display','block');
					var avg_rate=0;
					var aData = new Array();
					var index = 0;
					return_item = $('#return_item').dataTable({
						"bDestroy": true,
		                "bInfo": true,
		                "bFilter": true,
						"order": [[ 0, "desc" ]],
		                "scrollX":'100%',
		                "bSort": true,
		                "paging": false,
						"aaData": return_details,
						"columnDefs": [
                                		{ "visible": false, "targets": groupColumn }
                                	  ],
						"drawCallback": function ( settings ) {
						var api = this.api();
						var rows = api.rows( {page:'current'} ).nodes();
						
						api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) 
						{
							var data = api.row(api.row($(rows).eq(i)).index()).data();  //Response data
							var return_taxable_amount = parseFloat(data.item_cost) - parseFloat(data.item_total_tax);
							if ( typeof aData[group] == 'undefined') 
							{
								aData[group] = new Array();
								aData[group].rows = [];
								aData[group].return_details = [];
							}
							aData[group].rows.push(i); 
							aData[group].return_details.push({'item_cost':data.item_cost,'item_total_tax':data.item_total_tax,'gross_wt':data.gross_wt,'net_wt':data.net_wt,'rate_per_grm':data.rate_per_grm,'return_taxable_amount':return_taxable_amount,'bill_discount':data.bill_discount}); 
						});
						var idx= 0;
							for(var category_name in aData)  //column name
							{  
								var taxable_amount = 0; 
								var gross_wt = 0; 
								var net_wt = 0; 
								var item_discount = 0; 
								idx =  Math.max.apply(Math,aData[category_name].rows);
								$.each(aData[category_name].return_details,function(k,v){
										console.log(v);
										gross_wt = parseFloat(gross_wt)+parseFloat(v.gross_wt);
										net_wt = parseFloat(net_wt)+parseFloat(v.net_wt);
										taxable_amount = parseFloat(taxable_amount)+parseFloat(v.return_taxable_amount);
										total_return_amount = parseFloat(v.return_taxable_amount);
										total_return_tax = parseFloat(v.item_total_tax);
										item_discount = parseFloat(item_discount)+parseFloat(v.bill_discount);
								});
								$(rows).eq( idx ).after(
								'<tr class="group" style="background-color: #ccc;font-weight: bold;"><td class="tot-label" colspan="2">Total</td>'+
								'<td class="total">'+(gross_wt.toFixed(2))+'</td><td class="total">'+(net_wt.toFixed(2))+'</td><td class="total">'+(taxable_amount.toFixed(2))+'</td><td></td><td class="total">'+(item_discount.toFixed(2))+'</td></tr>'
								);
								
							};
						},
						"aoColumns": [	
										{ "mDataProp": "category_name" },
										{ "mDataProp": "product_name" },
										{ "mDataProp": "product_name" },
										{ "mDataProp": "gross_wt" },
										{ "mDataProp": "net_wt" },
										{ "mDataProp": function ( row, type, val, meta ){
											taxable_amount=parseFloat(parseFloat(row.item_cost)-(parseFloat(row.item_total_tax))).toFixed(2);
											return taxable_amount;
										}
										},
										{ "mDataProp": function ( row, type, val, meta ){
											avg_rate=parseFloat(parseFloat(parseFloat(row.item_cost)-parseFloat(row.item_total_tax))/(parseFloat(row.net_wt))).toFixed(2);
											return avg_rate;
										}
										},
										{ "mDataProp": "bill_discount" },
										
									],
							"footerCallback": function( row, data, start, end, display ){
							if(data.length>0){
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
									.column(3)
									.data()
									.reduce( function (a, b) {
										return intVal(a) + intVal(b);
									}, 0 );
									$(api.column(3).footer()).html(parseFloat(gross_wt).toFixed(2));
									
									net_wgt = api
									.column(4)
									.data()
									.reduce( function (a, b) {
										return intVal(a) + intVal(b);
									}, 0 );
									$(api.column(4).footer()).html(parseFloat(net_wgt).toFixed(2));
									
 									
 									amount = api
									.column(5)
									.data()
									.reduce( function (a, b) {
										return intVal(a) + intVal(b);
									}, 0 );
									$(api.column(5).footer()).html(parseFloat(amount).toFixed(2));
									discount = api
									.column(7)
									.data()
									.reduce( function (a, b) {
										return intVal(a) + intVal(b);
									}, 0 );
									$(api.column(7).footer()).html(parseFloat(discount).toFixed(2));
 								} 
							}else{
								 var api = this.api(), data; 
								 $(api.column(4).footer()).html('');
								 $(api.column(5).footer()).html('');
								 $(api.column(3).footer()).html('');
								 $(api.column(6).footer()).html('');
								 $(api.column(7).footer()).html('');
								 
							}
						}
						
	
					});		  	 	
				}
				//RETURN DETAILS END
				//Branch Transfer
					if(branch_transfer)
					{
						$('.branch_transfer_details').css('display','block');
						$('.no_of_piece').html(branch_transfer.pieces);
						$('.gwt').html(branch_transfer.gross_wt);
						$('.nwt').html(branch_transfer.net_wt);
					}
				//Branch Transfer
				//SUMMARY START
				final_price=parseFloat((parseFloat(total_taxable_amount)+parseFloat(total_item_tax)+parseFloat(credit_amount)-parseFloat(due_amt))-(parseFloat(total_purchase_amount)+parseFloat(total_sales_return)+parseFloat(total_return_amount)+parseFloat(total_return_tax))).toFixed(2);
				$('.taxable_amount').html(parseFloat(total_taxable_amount).toFixed(2));
				$('.sales_tax').html(parseFloat(total_item_tax).toFixed(2));
				$('.purchase_amt').html(parseFloat(total_purchase_amount).toFixed(2));
				$('.sale_ret_amt').html(parseFloat(total_return_amount).toFixed(2));
				$('.sale_ret_tax').html(parseFloat(total_return_tax).toFixed(2));
				$('.final_price').html(final_price);
				$('.due_amt').html(due_amt);
				$('.credit_amt').html(credit_amount);
				//SUMMARY ENDS
				//PAYMENT DETAILS
				if(payment_details.length>0){
					$.each(payment_details,function(key,item){
						if(item.payment_mode=='Cash')
						{
							cash_payment+=parseFloat(item.payment_amount);
						}
						if(item.payment_mode=='CC' || item.payment_mode=='DC')
						{
							card_payment+=parseFloat(item.payment_amount);
						}
						if(item.payment_mode=='CHQ')
						{
							cheque_payment+=parseFloat(item.payment_amount);
						}
						if(item.payment_mode=='NB')
						{
							online_payment+=parseFloat(item.payment_amount);
						}
					});
				}
				if(advance_detals.length>0){
					$.each(advance_detals,function(key,item){
						if(item.payment_mode=='Cash')
						{
							cash_payment+=parseFloat(item.payment_amount);
						}
						if(item.payment_mode=='CC' || item.payment_mode=='DC')
						{
							card_payment+=parseFloat(item.payment_amount);
						}
						if(item.payment_mode=='CHQ')
						{
							cheque_payment+=parseFloat(item.payment_amount);
						}
						if(item.payment_mode=='NB')
						{
							online_payment+=parseFloat(item.payment_amount);
						}
					});
				}
			
				if(chit_details.length>0)
				{
					$.each(chit_details,function(key,item){
							chit_payment+=parseFloat(item.closing_amount);
					});
				}
				if(voucher_details.length>0)
				{
					$.each(voucher_details,function(key,item){
							gift_voucher+=parseFloat(item.gift_voucher_amt);
					});
				}
				var cash_pay=parseFloat(parseFloat(cash_payment)-parseFloat(cash_return_payment)).toFixed(2);
				$('.total_cash').html(cash_pay);
				$('.total_card').html(parseFloat(card_payment).toFixed(2));
				$('.total_cheque').html(parseFloat(cheque_payment).toFixed(2));
				$('.online_payment').html(parseFloat(online_payment).toFixed(2));
				$('.chit_payment').html(parseFloat(chit_payment).toFixed(2));
				$('.gift_voucher').html(parseFloat(gift_voucher).toFixed(2));
				$('.total_amount').html(parseFloat(parseFloat(cash_pay)+parseFloat(card_payment)+parseFloat(cheque_payment)+parseFloat(online_payment)+parseFloat(chit_payment)+parseFloat(gift_voucher)).toFixed(2));
				//PAYMENT DETAILSpayment_details
			  },
			  error:function(error)  
			  {
				 $("div.overlay").css("display", "none"); 
			  }	 
	      });
}*/
function set_cashabstract_table()
{
    var branch_name    = ($('#branch_name').val()!='' && $('#branch_name').val()!=undefined ? $('#branch_name').val() : $("#branch_select option:selected").text());
	var company_name    = $('#company_name').val();
	var counter         = $("#counter_sel option:selected").text();
	var title="<b><span style='font-size:15pt;margin-left:32%;'>"+company_name+"</span></b><b><span style='font-size:12pt;'></span></b></br>"+"<span style=font-size:12pt;margin-left:20%;>&nbsp;&nbsp;CASH ABSTRACT &nbsp;"+($("#branch_select").val()!='' ? "COST CENTER:"+branch_name+" ":'')+""+(counter!='' && counter!=null ?"-"+counter:'')+"  From&nbsp;:&nbsp;"+$('#rpt_payments1').html()+" &nbsp;&nbsp;- "+$('#rpt_payments2').html()+"</span>";
	
	$("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/cash_abstract/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'from_date':$('#rpt_payments1').html(),'to_date' :$('#rpt_payments2').html(),'id_branch':($('#branch_filter').val()!='' && $('#branch_filter').val()!=undefined ? $('#branch_filter').val(): $("#branch_select").val()),'counter_id':$('#counter_sel').val(),'floor_id':$('#floor_sel').val()}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			     
			    $("#sales_list > tbody > tr").remove();  
	 		    $('#sales_list').dataTable().fnClearTable();
    		    $('#sales_list').dataTable().fnDestroy();
    		    
			 	var trHTML='';
			 	var sales_details=data.list.item_details;
			 	var old_matel_details=data.list.old_matel_details;
			 	var return_details=data.list.return_details;
			 	var payment_details = data.list.payment_details;
			 	var adv_adj_details = data.list.advance_adjusted;
			 	var due_details = data.list.due_details;
			 	var chit_details = data.list.chit_details;
			 	var credit_details = data.list.credit_details;
			 	var wallet_adjusted = data.list.wallet_adjusted;
			 	var advance_detals = data.list.advance_detals;
			 	var general_adv_details = data.list.general_adv_details;
			 	var general_pay_details = data.list.general_pay;
			 	var order_adj_details = data.list.order_adj;
			 	var home_bill_details = data.list.home_bill;
			 	var voucher_details = data.list.voucher_details;
			 	var partly_sale_details = data.list.partly_sale;
			 	var round_off_amt=parseFloat(data.list.bill_det.round_off_amt).toFixed(2);
			 	var advance_ref_details = data.list.adv_refund;
			 	var repair_order_delivered = data.list.repair_order_delivered;
			 	var general_credit_collection = data.list.general_credit_collection;
			 	var other_expense_amount=data.list.other_expense.tot_amount;
			 	var advance_deposit_amt = data.list.advance_deposit.advance_deposit_amt;

			 	var closing_amount=0;
			 	var adv_amt=0;
			 	var gen_adv_amt=0;
			 	var order_adj_amt=0;
			 	var voucher_amt=0;
			 	//Sales
			 	var total_tax=0;
	 			var total_amt=0;
	 			var total_discount=0;
	 			var total_wt=0;
	 			var total_gwt=0;
	 			var total_less_wt=0;
	 			var no_of_pcs=0;
			 	var tot_sales_amt=0;
	 			var tot_sales_wt=0;
	 			var tot_sales_gwt=0;
	 			var tot_sales_less_wt=0;
	 			var tot_sales_tax=0;
	 			var tot_sales_discount=0;
	 			var tot_sales_pcs=0;
	 			var sales_taxable_amount=0;
	 			var tot_sale_with_tax=0;
	 			var tot_taxable_amount=0;
	 			//Sales
	 			//Sales return
	 			var sales_ret_tax=0;
	 			var sales_ret_amt=0;
	 			var sales_ret_tax_with_amt=0;
	 			var sales_ret_discount=0;
	 			var sales_ret_gwt=0;
	 			var sales_ret_wt=0;
	 			var sales_ret_less_wt=0;
	 			var sales_ret_pcs=0;
			 	var tot_sales_ret_amt=0;
			 	var tot_sales_with_tax=0;
	 			var tot_sales_ret_gwt=0;
	 			var tot_sales_ret_wt=0;
	 			var tot_sales_ret_less_wt=0;
	 			var tot_sales_ret_tax=0;
	 			var tot_sales_ret_discount=0;
	 			var tot_sales_ret_pcs=0;
	 			var sales_ret_taxable_amt=0;
	 			var tot_sales_ret_taxable_amt=0;
	 			//Sales return
	 			//Purchase
	 			var tot_pur_amt=0;
	 			var tot_pur_gwt=0;
	 			var tot_pur_nwt=0;
	 			var pur_gwt=0;
	 			var pur_nwt=0;
			 	var pur_amount=0;
	 			//Purchase
	 			//Payment
	 			var cash_payment=0;
				var card_payment=0;
				var cheque_payment=0;
				var online_payment=0;
				var chit_payment=0;
				var gift_voucher=0;
				var partial_gwt=0;
				var partial_nwt=0;
				var due_amt=0;
				var credit_amount=0;
	 			//Payment
	 			
	 			//genral_pay
	 		    var gen_cash_payment=0
				var gen_card_payment=0
				var gen_cheque_payment=0
				var gen_online_payment=0
	 			//genral_pay
	 			//Credit Given
	 			var due_amount=0;
	 			//Credit Given
	 			//Advance Adjusement
	 			var adv_adj_amt=adv_adj_details.adj_amt;
	 			//Advance Adjusement
	 			
	 			//Wallet amt adj
	 			var wallet_amt=0;
	 			
	 			//advance refund
	 			var adv_refund_amt=0;
	 			
	 			if(advance_ref_details.length>0)
			 	{
			 	    $.each(advance_ref_details,function(key,items){
			 	        adv_refund_amt+=parseFloat(items.payment_amount);
			 	    });
			 	}
			 	
			 	//Advance refund
	 			
                if(credit_details.length>0)
				{
					$.each(credit_details,function(key,items){
						credit_amount +=parseFloat(items.tot_amt_received);		
					});
				}
				
				if(general_credit_collection.length>0)
				{
					$.each(general_credit_collection,function(key,item){
						credit_amount+=parseFloat(item.amount);
					});
				}
			 	
			 	
                
                
                if(general_adv_details.length>0)
			 	{
			 	    var advance_amt=0;
			 	    var weight_amt=0;
			 	    $.each(general_adv_details,function(key,items){
						advance_amt +=parseFloat(items.amount);		
						weight_amt +=parseFloat((items.weight_amt!=null ? items.weight_amt:0));		
					});
					gen_adv_amt=parseFloat(parseFloat(advance_amt)+parseFloat(weight_amt)).toFixed(2);
			 	}
							trHTML += '<tr class="sales">' +
											'<td class="sales" style="font-weight:bold;"><strong>SALES<strong></td>'+
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
											'<td></td>'+
									    '</tr>';
			 				$.each(sales_details,function(key,category){
			 					 trHTML += '<tr style="font-weight:bold;">' +
			 					 				'<td style="text-align: left;">'+key+'</td>'+
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
			 					 				'<td></td>'+
			 					 		 '</tr>';
			 					 $.each(category,function(k,items){
			 					 	var taxable_amount 	=parseFloat(items.item_cost-items.item_total_tax).toFixed(2);
			 					 	tot_taxable_amount  +=parseFloat(items.item_cost-items.item_total_tax);
			 					 	var sales_rate 		=parseFloat(items.sales_rate/items.piece).toFixed(2);
			 					 	var avg_rate 		=parseFloat(taxable_amount/items.net_wt).toFixed(2);
			 					 	var rate_diff 		=parseFloat(avg_rate-sales_rate).toFixed(2);
			 					 	total_amt 			+=parseFloat(items.item_cost);
			 					 	total_discount 		+=parseFloat(items.bill_discount);
			 					 	total_gwt 			+=parseFloat(items.gross_wt);
			 					 	total_wt 			+=parseFloat(items.net_wt);
			 					 	total_less_wt 		+=parseFloat(items.less_wt);
			 					 	total_tax 			+=parseFloat(items.item_total_tax);
			 					 	no_of_pcs 			+=parseFloat(items.piece);
		 					 		trHTML += '<tr>' +
		 					 				'<td >'+items.product_name+'</td>'+
		 					 				'<td >'+items.piece+'</td>'+
		 					 				'<td >'+(items.gross_wt>0 ?items.gross_wt :'')+'</td>'+
		 					 				'<td >'+(items.net_wt>0 ?items.net_wt :'')+'</td>'+
		 					 				'<td >'+(items.less_wt>0 ?items.less_wt :'')+'</td>'+
		 					 				'<td >'+taxable_amount+'</td>'+
		 					 				'<td >'+items.item_total_tax+'</td>'+
											'<td >'+items.item_cost+'</td>'+ 
		 					 				'<td >'+items.bill_discount+'</td>'+
		 					 				'<td >'+sales_rate+'</td>'+
		 					 				'<td >'+(items.sales_mode==2 ?avg_rate :parseFloat(taxable_amount-items.bill_discount).toFixed(2))+'</td>'+
		 					 				'<td>'+(items.sales_mode==2 ? rate_diff:'')+'</td>'+
		 					 		 '</tr>';
			 					 });
			 					 	/*trHTML += '<tr style="font-weight:bold;text-align:left;">' +
			 					 				'<td>TAXABLE AMOUNT</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td style="font-weight:bold;">'+parseFloat(tot_taxable_amount).toFixed(2)+'</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 		 '</tr>';
			 					 		 
		 					 		trHTML += '<tr style="font-weight:bold;text-align:left;">' +
		 					 				'<td>TAX</td>'+
		 					 				'<td></td>'+
		 					 				'<td></td>'+
		 					 				'<td></td>'+
		 					 				'<td></td>'+
		 					 				'<td style="font-weight:bold;">'+parseFloat(total_tax).toFixed(2)+'</td>'+
		 					 				'<td></td>'+
		 					 				'<td></td>'+
		 					 				'<td></td>'+
		 					 				'<td></td>'+
		 					 		 '</tr>';*/
			 					 		 
			 						 trHTML += '<tr style="font-weight:bold;">' +
			 					 				'<td><strong>SUB TOTAL</strong></td>'+
			 					 				'<td><strong>'+no_of_pcs+'</strong></td>'+
			 					 				'<td><strong>'+parseFloat(total_gwt).toFixed(3)+'</strong></td>'+
			 					 				'<td><strong>'+parseFloat(total_wt).toFixed(3)+'</strong></td>'+
			 					 				'<td><strong>'+(total_less_wt!=0?parseFloat(total_less_wt).toFixed(3):'')+'</strong></td>'+
			 					 				'<td><strong>'+parseFloat(tot_taxable_amount).toFixed(2)+'</strong></td>'+
			 					 				'<td><strong>'+parseFloat(total_tax).toFixed(2)+'</strong></td>'+
												'<td><strong>'+parseFloat(total_amt).toFixed(2)+'</strong></td>'+
			 					 				'<td><strong>'+parseFloat(total_discount).toFixed(2)+'</strong></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 		 '</tr>';
			 					 	tot_sales_amt+=total_amt;
			 					 	tot_sales_wt+=total_wt;
			 					 	tot_sales_gwt+=total_gwt;
			 					 	tot_sales_less_wt+=total_less_wt;
			 					 	tot_sales_tax+=total_tax;
			 					 	tot_sales_discount+=total_discount;
			 					 	tot_sales_pcs+=no_of_pcs;
			 					 	sales_taxable_amount+=tot_taxable_amount;
			 					 	tot_sale_with_tax+=total_amt;
			 					 	total_amt=0;
			 					 	total_gwt=0;
			 					 	total_wt=0;total_less_wt=0;
			 					 	total_discount=0;total_tax=0;no_of_pcs=0;tot_taxable_amount=0;
			 					 
			 				});
			 				//Total Sales Details			 				
			 					trHTML += '<tr style="font-weight:bold;">' +
			 					 				'<td><strong>SALES TOTAL</strong></td>'+
			 					 				'<td><strong>'+tot_sales_pcs+'</strong></td>'+
			 					 				'<td><strong>'+parseFloat(tot_sales_gwt).toFixed(3)+'</strong></td>'+
			 					 				'<td><strong>'+parseFloat(tot_sales_wt).toFixed(3)+'</strong></td>'+
			 					 				'<td><strong>'+parseFloat(tot_sales_less_wt).toFixed(3)+'</strong></td>'+
			 					 				'<td><strong>'+parseFloat(sales_taxable_amount).toFixed(2)+'</strong></td>'+
			 					 				'<td><strong>'+parseFloat(tot_sales_tax).toFixed(2)+'</strong></td>'+
												'<td><strong>'+parseFloat(tot_sale_with_tax).toFixed(2)+'</strong></td>'+
			 					 				'<td><strong>'+parseFloat(tot_sales_discount).toFixed(2)+'</strong></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 		 '</tr>'; 
			 				//Total Sales Details
			 				//Sales Return
			 				trHTML += '<tr style="font-weight:bold;">' +
											'<td><strong>SALES RETURN</strong></td>'+
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
											'<td></td>'+
									    '</tr>';
			 				$.each(return_details,function(key,category){
			 					 trHTML += '<tr style="font-weight:bold;">' +
			 					 				'<td style="text-align: left;">'+key+'</td>'+
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
			 					 				'<td></td>'+
			 					 		 '</tr>';
			 				console.log(category);
			 				if(category.length>0 && category!='')
			 				{
			 					 $.each(category,function(k,items){
			 					 	var taxable_amount 	=parseFloat(items.item_cost-items.item_total_tax).toFixed(2);
			 					 	sales_ret_taxable_amt 		+=parseFloat(items.item_cost-items.item_total_tax);
			 					 	var sales_rate 		=parseFloat(items.sales_rate/items.piece).toFixed(2);
			 					 	var avg_rate 		=parseFloat(taxable_amount/items.net_wt).toFixed(2);
			 					 	var rate_diff 		=parseFloat(avg_rate-sales_rate).toFixed(2);
			 					 	//sales_ret_amt 			+=parseFloat(items.item_cost);
			 					 	sales_ret_discount 		+=parseFloat(items.bill_discount);
			 					 	sales_ret_gwt 			+=parseFloat(items.gross_wt);
			 					 	sales_ret_wt 			+=parseFloat(items.net_wt);
			 					 	sales_ret_less_wt 			+=parseFloat(items.less_wt);
			 					 	sales_ret_tax 			+=parseFloat(items.item_total_tax);
			 					 	sales_ret_tax_with_amt  +=parseFloat(items.item_cost);
			 					 	sales_ret_pcs 			+=parseFloat(items.piece);
		 					 		trHTML += '<tr>' +
		 					 				'<td >'+items.product_name+'</td>'+
		 					 				'<td >'+items.piece+'</td>'+
		 					 				'<td >'+(items.gross_wt>0 ?items.gross_wt :'')+'</td>'+
		 					 				'<td >'+(items.net_wt>0 ?items.net_wt :'')+'</td>'+
		 					 				'<td ></td>'+
		 					 				'<td >'+taxable_amount+'</td>'+
		 					 				'<td >'+items.item_total_tax+'</td>'+
											'<td >'+items.item_cost+'</td>'+
		 					 				'<td >'+items.bill_discount+'</td>'+
		 					 				'<td >'+sales_rate+'</td>'+
		 					 				'<td >'+(items.sales_mode==2 ?avg_rate :parseFloat(taxable_amount-items.bill_discount).toFixed(2))+'</td>'+
		 					 				'<td >'+(items.sales_mode==2 ? rate_diff:'')+'</td>'+
		 					 		 '</tr>';
			 					 });
			 				}
			 					 	/*trHTML += '<tr style="font-weight:bold;">' +
			 					 				'<td>TAX</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td style="font-weight:bold;">'+parseFloat(sales_ret_tax).toFixed(2)+'</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 		 '</tr>';*/
			 						 trHTML += '<tr style="font-weight:bold;">' +
			 					 				'<td><strong>SUB TOTAL</strong></td>'+
			 					 				'<td><strong>'+sales_ret_pcs+'</strong></td>'+
			 					 				'<td><strong>'+parseFloat(sales_ret_gwt).toFixed(3)+'</strong></td>'+
			 					 				'<td><strong>'+parseFloat(sales_ret_wt).toFixed(3)+'</strong></td>'+
			 					 				'<td></td>'+
			 					 				'<td><strong>'+parseFloat(sales_ret_taxable_amt).toFixed(2)+'</strong></td>'+
												'<td><strong>'+parseFloat(sales_ret_tax).toFixed(2)+'</strong></td>'+
                                                '<td><strong>'+parseFloat(sales_ret_tax_with_amt).toFixed(2)+'</strong></td>'+
			 					 				'<td><strong>'+parseFloat(sales_ret_discount).toFixed(2)+'</strong></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 		 '</tr>';
			 					 	tot_sales_ret_amt+=sales_ret_amt;
			 					 	tot_sales_ret_gwt+=sales_ret_gwt;
			 					 	tot_sales_ret_wt+=sales_ret_wt;
			 					 	tot_sales_ret_less_wt+=sales_ret_less_wt;
			 					 	tot_sales_ret_tax+=sales_ret_tax;
			 					 	tot_sales_ret_discount+=sales_ret_discount;
			 					 	tot_sales_ret_pcs+=sales_ret_pcs;
			 					 	tot_sales_ret_taxable_amt+=sales_ret_taxable_amt;
			 					 	tot_sales_with_tax+=sales_ret_tax_with_amt;
			 					 	sales_ret_amt=0;
			 					 	sales_ret_wt=0;
			 					 	sales_ret_gwt=0;
			 					 	sales_ret_tax_with_amt=0;
			 					 	sales_ret_discount=0;sales_ret_tax=0;sales_ret_pcs=0;sales_ret_taxable_amt=0;
			 					 
			 				});
			 					trHTML += '<tr style="font-weight:bold;">' +
			 					 				'<td><strong>SALES RETURN TOTAL</strong></td>'+
			 					 				'<td><strong>'+tot_sales_ret_pcs+'</strong></td>'+
			 					 				'<td><strong>'+parseFloat(tot_sales_ret_gwt).toFixed(3)+'</strong></td>'+
			 					 				'<td><strong>'+parseFloat(tot_sales_ret_wt).toFixed(3)+'</strong></td>'+
			 					 				'<td></td>'+
			 					 				'<td><strong>'+parseFloat(tot_sales_ret_taxable_amt).toFixed(2)+'</strong></td>'+
												'<td><strong>'+parseFloat(tot_sales_ret_tax).toFixed(2)+'</strong></td>'+
												'<td><strong>'+parseFloat(tot_sales_with_tax).toFixed(2)+'</strong></td>'+
			 					 				'<td><strong>'+parseFloat(tot_sales_ret_discount).toFixed(2)+'</strong></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 		 '</tr>'; 
			 				//Sales Return
			 				
			 				//REPAIR ORDER DELIVERED
			 				trHTML += '<tr style="font-weight:bold;">' +
 				 				'<td><strong>REPAIR ORDER</strong></td>'+
 				 				'<td>'+(repair_order_delivered.pcs != null ? repair_order_delivered.pcs :'-')+'</td>'+
			 					'<td>'+(repair_order_delivered.weight != null ? parseFloat(repair_order_delivered.weight).toFixed(2) :'-')+'</td>'+
			 					'<td></td>'+
			 					'<td></td>'+
			 					'<td>'+(repair_order_delivered.amount != null ? parseFloat(repair_order_delivered.amount).toFixed(2) :'-')+'</td>'+
 				 				'<td></td>'+
 				 				'<td></td>'+
 				 				'<td></td>'+
 				 				'<td></td>'+
 				 				'<td></td>'+
 				 				'<td></td>'+
 				 		 '</tr>';
			 				//REPAIR ORDER DELIVERED
			 				//Purchase Details
			 				trHTML += '<tr style="font-weight:bold;">' +
			 					 				'<td><strong>PURCHASE</strong></td>'+
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
			 					 				'<td></td>'+
			 					 		 '</tr>';
			 				$.each(old_matel_details,function(key,old_metal){
			 					/*trHTML += '<tr style="font-weight:bold;">' +
			 					 				'<td>'+key+'</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 		 '</tr>';*/
			 					 $.each(old_metal,function(i,items){
			 					 	pur_gwt +=parseFloat(items.gross_wt);
			 					 	pur_nwt +=parseFloat(items.net_wt);
			 					 	pur_amount +=parseFloat(items.amount);
			 					 	var sales_rate=parseFloat(items.rate_per_grm/items.tot_pur).toFixed(2);
			 					 	var avg_rate=parseFloat(items.amount/items.net_wt).toFixed(2);
			 					 	trHTML += '<tr>' +
			 					 				'<td >'+items.old_metal_type+'</td>'+
			 					 				'<td></td>'+
			 					 				'<td>'+items.gross_wt+'</td>'+
			 					 				'<td>'+items.net_wt+'</td>'+
			 					 				'<td></td>'+
			 					 				'<td>'+items.amount+'</td>'+
			 					 				'<td></td>'+
			 					 				'<td>'+sales_rate+'</td>'+
			 					 				'<td>'+avg_rate+'</td>'+
			 					 				'<td>'+parseFloat(avg_rate-sales_rate).toFixed(2)+'</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 		 '</tr>';
			 					 });
			 					 /* trHTML += '<tr style="font-weight:bold;">' +
			 					 				'<td>SUB TOTAL</td>'+
			 					 				'<td></td>'+
			 					 				'<td>'+parseFloat(pur_gwt).toFixed(3)+'</td>'+
			 					 				'<td>'+parseFloat(pur_amount).toFixed(2)+'</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 		 '</tr>';*/
			 					 tot_pur_amt+=pur_amount;
			 					 tot_pur_gwt+=pur_gwt;
			 					 tot_pur_nwt+=pur_nwt;
			 					 pur_gwt=0;pur_amount=0;
			 					 pur_nwt=0;
			 				});
			 				trHTML += '<tr style="font-weight:bold;">' +
			 					 				'<td style="text-align: left;"><strong>PURCHASE TOTAL</strong></td>'+
			 					 				'<td></td>'+
			 					 				'<td><strong>'+parseFloat(tot_pur_gwt).toFixed(3)+'</strong></td>'+
			 					 				'<td><strong>'+parseFloat(tot_pur_nwt).toFixed(3)+'</strong></td>'+
			 					 				'<td></td>'+
			 					 				'<td><strong>'+parseFloat(tot_pur_amt).toFixed(2)+'</strong></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 		 '</tr>';
			 				//Purchase Details
			 			    
			 			    //Home Bill Details
			 			    if(home_bill_details.length>0)
			 			    {
			 			        trHTML += '<tr >' +
			 					 				'<td style="text-align: left;font-weight:bold;"><strong>HOME BILL</strong></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 		 '</tr>';
			 			        $.each(home_bill_details,function(key,item){
			 			            trHTML += '<tr>' +
			 					 				'<td>'+item.product_name+'</td>'+
			 					 				'<td>'+item.pcs+'</td>'+
			 					 				'<td>'+item.gross_wt+'</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td>'+item.item_cost+'</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 		 '</tr>';
			 			        });
			 			    }
			 			    //Home Bill Details
			 			    
			 			    //Partly sale details
			 			    if(partly_sale_details.length>0)
			 			    {
			 			        trHTML += '<tr style="font-weight:bold;">' +
			 					 				'<td style="text-align: left;"><strong>PARTLY SALE</strong></td>'+
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
			 					 				'<td></td>'+
			 					 		 '</tr>';
			 			        $.each(partly_sale_details,function(key,item){
			 			            trHTML += '<tr>' +
			 					 				'<td>'+item.product_name+'</td>'+
			 					 				'<td>'+item.pcs+'</td>'+
			 					 				'<td>'+item.gross_wt+'</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 		 '</tr>';
			 			        });
			 			    }
			 			    //Partly sale details
			 			//Cash Abstract
			 			if(due_details.length>0)
						{
							$.each(due_details,function(key,item){
								due_amount+=parseFloat(item.due_amt);
							});
						}
						
						
			 		    
			 		    if(advance_detals.length>0)
			 		    {
			 		        var adv_wt_amt=0;
			 		        $.each(advance_detals,function(key,item){
			 		            if(item.received_weight>0)
			 		            {
			 		               adv_wt_amt+=parseFloat(item.received_weight*item.rate_per_gram);
			 		            }
								adv_amt+=parseFloat(item.received_amount);
							});
							adv_amt=parseFloat(parseFloat(adv_amt)+parseFloat(adv_wt_amt)).toFixed(2);
			 		    }
			 		    
			 		    if(order_adj_details.length>0)
        			 	{
        			 	    var ord_adj_wt=0;
        			 	    $.each(order_adj_details,function(key,items){
        						if(items.received_weight>0)
			 		            {
			 		               ord_adj_wt+=parseFloat(items.received_weight*items.rate_per_gram);
			 		            }
			 		            order_adj_amt+=parseFloat(items.received_amount);
        					});
        					order_adj_amt=parseFloat(parseFloat(order_adj_amt)+parseFloat(ord_adj_wt)).toFixed(2);
        			 	}
			 			trHTML += '<tr style="font-weight:bold;">' +
			 					 				'<td><strong>CASH ABSTRACT</strong></td>'+
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
			 					 				'<td></td>'+
			 					 		 '</tr>';
			 			trHTML += '<tr style="font-weight:bold;>' +
			 					 				'<td>SALES</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td>'+parseFloat(sales_taxable_amount).toFixed(2)+'</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 		 '</tr>';
			 			trHTML += '<tr style="font-weight:bold;">' +
			 					 				'<td style="text-align: left;">SALES</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td>'+parseFloat(sales_taxable_amount).toFixed(2)+'</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 		 '</tr>';
			 			trHTML += '<tr style="font-weight:bold;">' +
			 					 				'<td style="text-align: left;">TAX/VAT</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td>'+parseFloat(tot_sales_tax).toFixed(2)+'</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 		 '</tr>';
			 			trHTML += '<tr style="font-weight:bold;">' +
			 					 				'<td style="text-align: left;">SALES RETURN</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td>'+parseFloat(tot_sales_ret_taxable_amt).toFixed(2)+'</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 		 '</tr>';
			 			trHTML += '<tr style="font-weight:bold;">' +
			 					 				'<td>TAx/VAT- SR</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td>'+parseFloat(tot_sales_ret_tax).toFixed(2)+'</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 		 '</tr>';
			 			trHTML += '<tr style="font-weight:bold;">' +
			 					 				'<td style="text-align: left;">PURCHASE</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td>'+parseFloat(tot_pur_amt).toFixed(2)+'</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 		 '</tr>';
			 			
			 		
			 			trHTML += '<tr style="font-weight:bold;text-transform:uppercase">' +
			 					 				'<td style="text-align: left;">ADVANCE RECEIPT</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td>'+parseFloat(parseFloat(adv_amt)+parseFloat(gen_adv_amt)).toFixed(2)+'</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 		 '</tr>';
			 		    trHTML += '<tr style="font-weight:bold;text-transform:uppercase">' +
		 					 				'<td style="text-align: left;">ADVANCE  DEPOSIT</td>'+
		 					 				'<td></td>'+
		 					 				'<td></td>'+
		 					 				'<td></td>'+
		 					 				'<td></td>'+
		 					 				'<td>'+parseFloat(advance_deposit_amt).toFixed(2)+'</td>'+
		 					 				'<td></td>'+
		 					 				'<td></td>'+
		 					 				'<td></td>'+
		 					 				'<td></td>'+
		 					 				'<td></td>'+
		 					 				'<td></td>'+
		 					 		 '</tr>';
		 					 		 
			 		    trHTML += '<tr style="font-weight:bold;text-transform:uppercase">' +
			 					 				'<td style="text-align: left;">ADVANCE REFUND</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td>'+parseFloat(adv_refund_amt).toFixed(2)+'</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 		 '</tr>';
			 					 		 
			 			trHTML += '<tr style="font-weight:bold;text-transform:uppercase">' +
			 					 				'<td style="text-align: left;">CREDIT RECEIPT</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td>'+parseFloat(credit_amount).toFixed(2)+'</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 		 '</tr>';
			 			trHTML += '<tr style="font-weight:bold;text-transform:uppercase">' +
			 					 				'<td style="text-align: left;">CREDIT SALES</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td>'+parseFloat(due_amount).toFixed(2)+'</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 		 '</tr>';
			 			trHTML += '<tr style="font-weight:bold;text-transform:uppercase">' +
 				 				'<td style="text-align: left;">OTHER EXPENSES</td>'+
 				 				'<td></td>'+
 				 				'<td></td>'+
 				 				'<td></td>'+
 				 				'<td></td>'+
 				 				'<td>'+parseFloat(other_expense_amount).toFixed(2)+'</td>'+
 				 				'<td></td>'+
 				 				'<td></td>'+
 				 				'<td></td>'+
 				 				'<td></td>'+
 				 				'<td></td>'+
 				 				'<td></td>'+
 				 		 '</tr>';
			 					 		 
			 			trHTML += '<tr style="font-weight:bold;">' +
			 					 				'<td>HANDLING CHARGES</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td>'+parseFloat(data.list.bill_det.handling_charges).toFixed(2)+'</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 		 '</tr>';
			 					 		 
			 			trHTML += '<tr style="font-weight:bold;text-transform:uppercase">' +
			 					 				'<td style="text-align: left;"><strong>TOTAL</strong></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td style="text-align: left;"><strong>'+parseFloat(parseFloat(tot_sales_amt)+parseFloat(credit_amount)+parseFloat(adv_amt)+parseFloat(gen_adv_amt)+parseFloat(advance_deposit_amt)+parseFloat(data.list.bill_det.handling_charges)+parseFloat(repair_order_delivered.amount)-parseFloat(tot_pur_amt)-parseFloat(tot_sales_ret_amt)-parseFloat(due_amount)-parseFloat(adv_refund_amt)-parseFloat(other_expense_amount)).toFixed(2)+'</strong></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 		 '</tr>';
			 			//cash abstract
			 			
			 			//Payment
			 	if(payment_details.length>0){
					$.each(payment_details,function(key,item){
						if(item.payment_mode=='Cash')
						{
							cash_payment+=parseFloat(item.payment_amount);
						}
						if(item.payment_mode=='CC' || item.payment_mode=='DC')
						{
							card_payment+=parseFloat(item.payment_amount);
						}
						if(item.payment_mode=='CHQ')
						{
							cheque_payment+=parseFloat(item.payment_amount);
						}
						if(item.payment_mode=='NB')
						{
							online_payment+=parseFloat(item.payment_amount);
						}
					});
				}
				
				if(general_pay_details.length>0){
					$.each(general_pay_details,function(key,item){
						if(item.payment_mode=='Cash')
						{
						    if(item.transcation_type==1)
						    {
						        gen_cash_payment+=parseFloat(item.payment_amount);
						    }
						    if(item.transcation_type==2)
						    {
						        gen_cash_payment-=parseFloat(item.payment_amount);
						    }
							
						}
						if(item.payment_mode=='CC' || item.payment_mode=='DC')
						{
						    if(item.transcation_type==1)
						    {
						        gen_card_payment+=parseFloat(item.payment_amount);
						    }
						    if(item.transcation_type==2)
						    {
						       gen_card_payment-=parseFloat(item.payment_amount);
						    }
						}
						if(item.payment_mode=='CHQ')
						{
						    if(item.transcation_type==1)
						    {
						        gen_cheque_payment+=parseFloat(item.payment_amount);
						    }
						    if(item.transcation_type==2)
						    {
						       gen_cheque_payment-=parseFloat(item.payment_amount);
						    }
						}
						if(item.payment_mode=='NB')
						{
						    if(item.transcation_type==1)
						    {
						        gen_online_payment+=parseFloat(item.payment_amount);
						    }
						    if(item.transcation_type==2)
						    {
						       gen_online_payment-=parseFloat(item.payment_amount);
						    }
						}
					});
				}
				
				if(chit_details.length>0)
				{       
				    	$.each(chit_details,function(key,item){
				    	   closing_amount+=parseFloat(item.utilized_amt);
				    	});
				}
				
				if(voucher_details.length>0)
				{       
				    	$.each(voucher_details,function(key,item){
				    	   voucher_amt+=parseFloat(item.gift_voucher_amt);
				    	});
				}
				
			 
				
					/*trHTML += '<tr style="font-weight:bold;">' +
			 					 				'<td style="text-align: left;">TOTAL</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 		 '</tr>';*/
			 			trHTML += '<tr style="font-weight:bold;text-transform:uppercase">' +
			 					 				'<td style="text-align: left;">CASH</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td style="text-align: left;">'+parseFloat(parseFloat(cash_payment)+parseFloat(gen_cash_payment)).toFixed(2)+'</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 		 '</tr>';
			 			trHTML += '<tr style="font-weight:bold;text-transform:uppercase">' +
			 					 				'<td style="text-align: left;">CHEQUE RECEIVED</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td style="text-align: left;">'+parseFloat(parseFloat(cheque_payment)+parseFloat(gen_cheque_payment)).toFixed(2)+'</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 		 '</tr>';
			 			trHTML += '<tr style="font-weight:bold;text-transform:uppercase">' +
			 					 				'<td style="text-align: left;">CARD</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td style="text-align: left;">'+(parseFloat(card_payment)+parseFloat(gen_card_payment)).toFixed(2)+'</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 		 '</tr>';
			 			trHTML += '<tr style="font-weight:bold;text-transform:uppercase">' +
			 					 				'<td style="text-align: left;">NET BANKING</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td style="text-align: left;">'+parseFloat(parseFloat(online_payment)+parseFloat(gen_online_payment)).toFixed(2)+'</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 		 '</tr>';
			 			trHTML += '<tr style="font-weight:bold;text-transform:uppercase">' +
			 					 				'<td style="text-align: left;">ADVANCE  ADJ</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td>'+parseFloat(adv_adj_amt).toFixed(2)+'</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 		 '</tr>';
			 		    
			 		    		 
			 			trHTML += '<tr style="font-weight:bold;text-transform:uppercase">' +
			 					 				'<td style="text-align: left;">CHIT UTILIZED</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td>'+parseFloat(closing_amount).toFixed(2)+'</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 		 '</tr>';
			 		
			 		
			 			trHTML += '<tr style="font-weight:bold;text-transform:uppercase">' +
			 					 				'<td style="text-align: left;">ORDER ADJ</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td>'+parseFloat(order_adj_amt).toFixed(2)+'</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 		 '</tr>';
			 		trHTML += '<tr style="font-weight:bold;">' +
			 					 				'<td style="text-align: left;">GIFT VOUCHER</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td>'+parseFloat(voucher_amt).toFixed(2)+'</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 		 '</tr>';
			 			
			 			trHTML += '<tr style="font-weight:bold;text-transform:uppercase">' +
			 					 				'<td style="text-align: left;">ROUND off</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td>'+round_off_amt+'</td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 		 '</tr>';
			 			//TOTAL
			 			if(round_off_amt>=0)
			 			{
			 			    var collection_amt=parseFloat(parseFloat(cash_payment)+parseFloat(cheque_payment)+parseFloat(card_payment)+parseFloat(online_payment)+parseFloat(adv_adj_amt)+parseFloat(closing_amount)+parseFloat(gen_cash_payment)+parseFloat(gen_card_payment)+parseFloat(gen_online_payment)+parseFloat(gen_cheque_payment)+parseFloat(order_adj_amt)+parseFloat(voucher_amt)-parseFloat(round_off_amt));
			 			}else{
			 			    var collection_amt=parseFloat(parseFloat(cash_payment)+parseFloat(cheque_payment)+parseFloat(card_payment)+parseFloat(online_payment)+parseFloat(adv_adj_amt)+parseFloat(closing_amount)+parseFloat(gen_cash_payment)+parseFloat(gen_card_payment)+parseFloat(gen_online_payment)+parseFloat(gen_cheque_payment)+parseFloat(order_adj_amt)+parseFloat(voucher_amt)-parseFloat(round_off_amt*-1));
			 			}
			 			//TOTAL
			 			trHTML += '<tr style="font-weight:bold;text-transform:uppercase">' +
			 					 				'<td style="text-align: left;"><strong>TOTAL</strong></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td style="text-align: left;"><strong>'+parseFloat(collection_amt).toFixed(2)+'</strong></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 				'<td></td>'+
			 					 		 '</tr>';
			 			//Payment
			 		  $('#sales_list > tbody').html(trHTML);
			 			if ( ! $.fn.DataTable.isDataTable( '#sales_list' ) ) { 
						oTable = $('#sales_list').dataTable({ 
						"bSort": false, 
						"bDestroy": true,
						"bInfo": true, 
						"scrollX":'100%',
						"paging": false,  
						"dom": 'Bfrtip',
						"bAutoWidth": false,
						"responsive": true,
						"buttons": [
						{
							extend: 'print',
							footer: true,
							title: '',
							messageTop: title,
							customize: function ( win ) {
							    $(win.document.body).find('table')
                                .addClass('compact');
                        
								$(win.document.body).find( 'table' )
									.addClass('compact')
									.css('font-size','10px')
									.css('font-family','sans-serif');
								
							},
						    exportOptions: {
        	                    columns: ':visible',
                          		stripHtml: false
        	                }
						},
						{
							extend:'excel',
							footer: true,
						    title: '',
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
function set_btreport_table(){
	$("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
		 url:base_url+ "index.php/admin_ret_reports/branch_trans/ajax?nocache=" + my_Date.getUTCSeconds(),
		 data: ( {'dt_range' :$("#dt_range").val(),'id_branch':$("#branch_filter").val()}),
		 dataType:"JSON",
		 type:"POST",
		 success:function(data){ 
		 		$("div.overlay").css("display", "none"); 
				var list = data.list;
				var oTable = $('#bt_report').DataTable();
				oTable.clear().draw();
				if (list!= null && list.length > 0)
				{  	
					 oTable = $('#bt_report').dataTable({
						"bDestroy": true,
		                "bInfo": true,
		                "bFilter": true,
						"order": [[ 0, "desc" ]],
		                "scrollX":'100%', 
		                "bSort": true, 
		                "dom": 'lBfrtip',
		                "buttons": [
									{
									   extend: 'print',
									   footer: true,
									   title: "Branch Transfer Report - "+$("#dt_range").val(),
									   customize: function ( win ) {
											$(win.document.body).find( 'table' )
												.addClass( 'compact' )
												.css( 'font-size', 'inherit' );
										},
									 },
									 {
										extend:'excel',
										footer: true,
									    title: "Branch Transfer Report - "+$("#dt_range").val(),
									  }
									 ], 
						"aaData": list,
						"aoColumns": [	
						                { "mDataProp": "date_add" },
						                { "mDataProp": "branch_transfer_id" },
    					                { "mDataProp": "branch_trans_code" },
    					                { "mDataProp": "from_branch" },
    					                { "mDataProp": "to_branch" },
    					                { "mDataProp": "product_name" },
    									{ "mDataProp": "pieces" },
    									{ "mDataProp": "grs_wt" },
    									{ "mDataProp": "net_wt" },
    									{ "mDataProp": "bt_status" },
    									{ "mDataProp": "approved_datetime" },
    									{ "mDataProp": "dwnload_datetime" },
    									{ "mDataProp": "created_emp" },
    									{ "mDataProp": "downloaded_emp" },
									],
						"footerCallback": function( row, data, start, end, display ){ 
							if(data.length>0){
								var api = this.api(), data;
								for( var i=0; i<=data.length-1;i++){
									var intVal = function ( i ) {
										return typeof i === 'string' ?
										i.replace(/[\$,]/g, '')*1 :
										typeof i === 'number' ?
										i : 0;
									};	
									
									$(api.column(4).footer() ).html('Total');	
									
									pcs = api
									.column(5)
									.data()
									.reduce( function (a, b) {
										return intVal(a) + intVal(b);
									}, 0 );
									$(api.column(5).footer()).html(parseFloat(pcs).toFixed(2));	 
									
									grs_wgt = api
									.column(6)
									.data()
									.reduce( function (a, b) {
										return intVal(a) + intVal(b);
									}, 0 );
									$(api.column(6).footer()).html(parseFloat(grs_wgt).toFixed(2));	 
									
									net_wgt = api
									.column(7)
									.data()
									.reduce( function (a, b) {
										return intVal(a) + intVal(b);
									}, 0 );
									$(api.column(7).footer()).html(parseFloat(net_wgt).toFixed(2));	 
									 
									
							} 
							}else{
								 var api = this.api(), data; 
								 $(api.column(5).footer()).html('');
								 $(api.column(6).footer()).html('');
								 $(api.column(7).footer()).html('');
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
$('#oldmetal_report_type').on('change',function(){
    $('.detailed_report').css("display","none");
    $('.summary_report').css("display","none");
    if(this.value==2)
    {
        $('.detailed_report').css("display","block");
    }else if(this.value==1)
    {
        $('.summary_report').css("display","block");
    }
    set_old_metal_table();
});
function set_old_metal_table()
{
	$("div.overlay").css("display", "block"); 
	var dt_range=($("#dt_range").val()).split('-');
    var branch_name=($('#branch_name').val()!='' && $('#branch_name').val()!=undefined ? $('#branch_name').val() : $("#branch_select option:selected").text());
    var metal_type=$("#filter_metal option:selected").text();
	var company_name=$('#company_name').val();
	var title="<b><span style='font-size:15pt;margin-left:35%;'>"+company_name+"</span></b><b><span style='font-size:15pt;'></span></b></br>"+"<span style=font-size:13pt;margin-left:25%;>&nbsp;&nbsp;PURCHASE AND EXCHANGE "+($("#branch_filter").val()!='' ? "Cost Center:"+branch_name+"":'')+" "+($("#filter_metal").val()!='' ? "Metal Type:"+metal_type+"":'')+" &nbsp;From&nbsp;:&nbsp;"+dt_range[0]+" &nbsp;&nbsp;To Date&nbsp;:&nbsp;"+dt_range[1]+"</span>";
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/old_metal_purchase/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'metal' :$("#filter_metal").val(),'dt_range' :$("#dt_range").val(),'id_branch':($('#branch_filter').val()!='' && $('#branch_filter').val()!=undefined ? $('#branch_filter').val(): $("#branch_select").val()),'report_type':$('#oldmetal_report_type').val()}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
		 		$("div.overlay").css("display", "none"); 
		 		var list = data.list.item_details; 
		 		
		 		if($('#oldmetal_report_type').val()==2)
		 		{
        		 		$("#old_metal_report > tbody > tr").remove();  
        				$('#old_metal_report').dataTable().fnClearTable();
            		    $('#old_metal_report').dataTable().fnDestroy();
        				if (list!= null )
        				{  	
        				    var return_details=data.list.return_details;
        				    var partly_sale_det=data.list.partly_sale_det;
        					trHTML = ''; 
        					tfootHTML = ''; 
        					var total_gross_wt = 0;
        					var total_stone_wt = 0;
        					var total_dust_wt = 0;
        					var total_pure_wt = 0;
        					var total_wast_wt = 0;
        					var total_net_wt = 0;
        					var total_rate = 0;
        					var sales_ret_gwt=0;
        					var sales_ret_nwt=0;
        					var partly_sale_gwt=0;
        					var partly_sale_nwt=0;
        					var tot_refund_amt=0;
        					
        					//Sales Return
        					 /*trHTML += '<tr style="font-weight:bold;">' +
        			 					 				'<td style="text-align: left;"><strong>SALES RETURN</strong></td>'+
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
        			 					 				'<td></td>'+
        			 					 				'<td></td>'+
        			 					 				'<td></td>'+
        			 					 		 '</tr>';
        						$.each(return_details,function(key,category){
        			 					 trHTML += '<tr style="font-weight:bold;">' +
        			 					 				'<td style="text-align: left;">'+key+'</td>'+
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
        			 					 				'<td></td>'+
        			 					 				'<td></td>'+
        			 					 				'<td></td>'+
        			 					 		 '</tr>';
        			 				console.log(category);
        			 				if(category.length>0 && category!='')
        			 				{
        			 					 $.each(category,function(k,items){
        			 					     sales_ret_gwt+=parseFloat(items.gross_wt);
        			 					     sales_ret_nwt+=parseFloat(items.net_wt);
        			 					    var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+items.bill_id;
        			 					 	trHTML += '<tr>' +
        		 					 				'<td >'+items.bill_date+'</td>'+
        		 					 			    '<td><a href='+url+' target="_blank">'+items.bill_no+'</a></td>'+
        		 					 				'<td >'+items.gross_wt+'</td>'+
        		 					 				'<td></td>'+
        		 					 				'<td></td>'+
        		 					 				'<td></td>'+
        		 					 				'<td>'+(items.calculation_based_on==1 || items.calculation_based_on==1 ? parseFloat((items.gross_wt*items.wastage_percent)/100).toFixed(3) : parseFloat(items.net_wt*items.wastage_percent)/100).toFixed(3)+'</td>'+
        		 					 				'<td >'+items.net_wt+'</td>'+
        		 					 				'<td></td>'+
        		 					 				'<td></td>'+
        		 					 				'<td>'+items.item_cost+'</td>'+
        		 					 				'<td>' + items.customer + '</td>' +
        		 					 				'<td></td>'+
        		 					 				'<td>'+items.emp_name+'</td>'+
        		 					 		 '</tr>';
        			 					 });
        			 				}
        						});*/
        					//Sales Return
        					
        					
        					//Partly sale
        							 
        
        			 				/*if(partly_sale_det.length>0 && partly_sale_det!='')
        			 				{
        			 				    trHTML += '<tr style="font-weight:bold;">' +
        			 					 				'<td style="text-align: left;"><strong>PARTLY SALE</strong></td>'+
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
        			 					 				'<td></td>'+
        			 					 				'<td></td>'+
        			 					 				'<td></td>'+
        			 					 		 '</tr>';
        			 					 		 
        			 					 $.each(partly_sale_det,function(k,items){
        			 					    partly_sale_gwt+=parseFloat(items.gross_wt);
        			 					    var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+items.bill_id;
        			 					 	trHTML += '<tr>' +
        		 					 				'<td >'+items.bill_date+'</td>'+
        		 					 			    '<td><a href='+url+' target="_blank">'+items.bill_no+'</a></td>'+
        		 					 				'<td >'+items.gross_wt+'</td>'+
        		 					 				'<td></td>'+
        		 					 				'<td></td>'+
        		 					 				'<td></td>'+
        		 					 				'<td>'+(items.calculation_based_on==1 || items.calculation_based_on==1 ? parseFloat((items.gross_wt*items.wastage_percent)/100).toFixed(3) : parseFloat(items.net_wt*items.wastage_percent)/100).toFixed(3)+'</td>'+
        		 					 				'<td ></td>'+
        		 					 				'<td></td>'+
        		 					 				'<td></td>'+
        		 					 				'<td>'+items.item_cost+'</td>'+
        		 					 				'<td>' + items.customer + '</td>' +
        		 					 				'<td></td>'+
        		 					 				'<td>'+items.emp_name+'</td>'+
        		 					 		 '</tr>';
        			 					 });
        			 					 
        			 					  trHTML += '<tr style="font-weight:bold;">' +
        			 					 				'<td style="text-align: left;"><strong>TOTAL</strong></td>'+
        			 					 				'<td></td>'+
        			 					 				'<td ><strong>'+partly_sale_gwt+'</strong></td>'+
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
        			 					 				'<td></td>'+
        			 					 		 '</tr>';
        			 				}*/
        					//Partly sale
        					
        					
        					//Old Metal Purchase
        					
        					$.each(list, function (i, category) { 
        					    
        					    trHTML += '<tr style="font-weight:bold;">' +
                                    '<td style="text-align: left; text-transform:uppercase;">'+i+'</td>'+
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
                                    '<td></td>'+
                                    '<td></td>'+
                                    '<td></td>'+
                                    '<td></td>'+
                                    '<td></td>'+
                                    '<td></td>'+
                                    '<td></td>'+
                                '</tr>';
        			 					 		 
        					 	 var gross_wt = 0;
        					 	 var stone_wt = 0;
        					 	 var dust_wt = 0;
        					 	 var pure_wt = 0;
        					 	 var wast_wt = 0;
        					 	 var net_wt = 0;
        					 	 var rate = 0;
        					 	 var purity_per=0;
        					 	 
        						 $.each(category, function (idx, bill_no) {
        						      trHTML += '<tr style="font-weight:bold;">' +
                                        '<td style="text-align: left; text-transform:uppercase;">'+idx+'</td>'+
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
                                        '<td></td>'+
                                        '<td></td>'+
                                        '<td></td>'+
                                        '<td></td>'+
                                        '<td></td>'+
                                        '<td></td>'+
                                        '<td></td>'+
                                    '</tr>';

        						     var refund_amt = (bill_no[0]['refund_amt']*-1);
        						     tot_refund_amt += (bill_no[0]['refund_amt']*-1);
        						     $.each(bill_no, function (bill, item) {
        						         
        			 					 		 
        			 					 		 
        			 					 		 var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+item.bill_id;
                    						     if(item.goldrate_24ct>0)
                    						     {
                    						         purity_per=parseFloat((item.rate/item.gross_wt)/item.goldrate_24ct).toFixed(2);
                    						     }
                    						     
                    						     
                    						     
                    							trHTML += '<tr>' +
                                                    '<td>' + item.branch + '</td>' +	
                                                    '<td>' + item.bill_date + '</td>' +									
                                                    '<td><a href='+url+' target="_blank">'+item.bill_no+'</a></td>'+
                                                    '<td>' + item.old_metal_category + '</td>' +
                                                    '<td>' + item.gross_wt + '</td>' +
                                                    '<td>' + item.stone_wt + '</td>' +
                                                    '<td>' + item.dust_wt + '</td>' +
                                                    '<td>' + item.pure_wt + '</td>' +
                                                    '<td>' + item.wast_wt + '</td>' +
                                                    '<td>' + item.net_wt + '</td>' +
                                                    '<td>' + item.purity + '</td>' +
                                                    '<td>' + item.rate_per_grm + '</td>' +
                                                    '<td>' + item.rate + '</td>' +
                                                    '<td>' + parseFloat(refund_amt).toFixed(2) + '</td>' +
                                                    '<td>' + item.old_metal_status + '</td>' +
                                                    '<td>' + item.customer + '</td>' +
                                                    '<td>' + item.esti_no + '</td>' +							
                                                    '<td>' + item.emp_name + '</td>' +							
                                                '</tr>'; 
           
                                                refund_amt=0;
                    							gross_wt = parseFloat(gross_wt) + parseFloat(item.gross_wt);
                    							stone_wt = parseFloat(stone_wt) + parseFloat(item.stone_wt);
                    							dust_wt  = parseFloat(dust_wt) + parseFloat(item.dust_wt);
                    							pure_wt  = parseFloat(pure_wt) + parseFloat(item.pure_wt);
                    							wast_wt  = parseFloat(wast_wt) + parseFloat(item.wast_wt);
                    							net_wt   = parseFloat(net_wt) + parseFloat(item.net_wt);
                    							rate     = parseFloat(rate) + parseFloat(item.rate);
                    							
        							
        						     });
        						     
        						     
        						 });
        						 /*trHTML += '<tr>' +
        										'<td>Sub Total</td>' +
        										'<td>' + parseFloat(gross_wt).toFixed(3); + '</td>' +
        										'<td>' + parseFloat(stone_wt).toFixed(3) + '</td>' +
        										'<td>' + parseFloat(dust_wt).toFixed(3) + '</td>' +
        										'<td>' + parseFloat(pure_wt).toFixed(3) + '</td>' +
        										'<td>' + parseFloat(wast_wt).toFixed(3) + '</td>' +
        										'<td>' + parseFloat(net_wt).toFixed(3) + '</td>' +
        										'<td></td>' +
        										'<td></td>' +
        										'<td>' + rate + '</td>' +
        										'<td></td>' +
        										'<td></td>' +							
        										'</tr>';*/
        							total_gross_wt = total_gross_wt+gross_wt;
        							total_stone_wt = total_stone_wt+stone_wt;
        							total_dust_wt = total_dust_wt+dust_wt;
        							total_pure_wt = total_pure_wt+pure_wt;
        							total_wast_wt = total_wast_wt+wast_wt;
        							total_net_wt = total_net_wt+net_wt;
        							total_rate = total_rate+rate;
        					 });
                                    trHTML += '<tr style="font-weight:bold;">' +
                                        '<td>Grand Total</td>' +
                                        '<td></td>' +
                                        '<td></td>' +
                                        '<td></td>' +
                                        '<td>' + parseFloat(total_gross_wt).toFixed(3) + '</td>' +
                                        '<td>' + parseFloat(total_stone_wt).toFixed(3) + '</td>' +
                                        '<td>' + parseFloat(total_dust_wt).toFixed(3) + '</td>' +
                                        '<td>' + parseFloat(total_pure_wt).toFixed(3) + '</td>' +
                                        '<td>' + parseFloat(total_wast_wt).toFixed(3) + '</td>' +
                                        '<td>' + parseFloat(total_net_wt).toFixed(3) + '</td>' +
                                        '<td></td>' +
                                        '<td></td>' +
                                        '<td>' + total_rate + '</th>' +
                                        '<td>'+parseFloat(tot_refund_amt).toFixed(2)+'</td>' +
                                        '<td></td>' +
                                        '<td></td>' +
                                        '<td></td>' +							
                                        '<td></td>' +							
                                    '</tr>'; 
        				    //Old Metal Purchase
        				    
        				    
        	                 $('#old_metal_report > tbody').html(trHTML);
        	                 	                 
        	                 // Check and initialise datatable
        	                 if ( ! $.fn.DataTable.isDataTable( '#old_metal_report' ) ) { 
        	                     oTable = $('#old_metal_report').dataTable({ 
        						                "bSort": false, 
        						                "bInfo": true, 
        						                "scrollX":'100%',  
        						                "dom": 'lBfrtip',
        						                "lengthMenu": [[-1, 25, 50, 100, 250], ["All" ,25, 50, 100, 250]],
        						                "buttons": [
        													{
        													   extend: 'print',
        													   footer: true,
        													   title: '',
        													   messageTop: 'Old Metal Purchase Report',
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
        													    title: 'Old Metal Purchase Report',
        													  }
        													 ], 
        			
        							
        									 });
        								}	  	 	
        				}
        				
			    }else if($('#oldmetal_report_type').val()==1)
			    {
			        
			                var oTable = $('#old_metal_detailed_report').DataTable();
                            oTable.clear().draw();  
                            if (data.list!= null && data.list.length > 0)
                            {   
                                oTable = $('#old_metal_detailed_report').dataTable({
                                "bDestroy": true,
                                "bInfo": true,
                                "bFilter": true,
                                "bSort": true,
                                "order": [[ 0, "asc" ]],
                                "dom": 'lBfrtip',
                                "buttons": [
                    			 {
                    			   extend: 'print',
                    			   footer: true,
                    			   title: "Old Metal Purchase Report",
                    			   customize: function ( win ) {
                    					$(win.document.body).find( 'table' )
                    						.addClass( 'compact' )
                    						.css( 'font-size', 'inherit' );
                    				},
                    			 },
                    			 {
                    				extend:'excel',
                    				footer: true,
                    			    title: "Old Metal Purchase Report",
                    			  }
                    			 ],
                                "aaData": data.list,
                                "aoColumns": [  
                                { "mDataProp": "bill_date" },
                                { "mDataProp": "branch_name" },
                                { "mDataProp": "old_metal_category" },
                                { "mDataProp": "total_gross_wt" },
                                { "mDataProp": "total_net_wt" },
                                {"mDataProp": "total_amount"},
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
                    							i : 0;
                    						};	
                    						
                    						$(api.column(0).footer() ).html('Total');	
                    
                    						gross_wgt = api
                    						.column(3)
                    						.data()
                    						.reduce( function (a, b) {
                    							return intVal(a) + intVal(b);
                    						}, 0 );
                    						$(api.column(3).footer()).html(parseFloat(gross_wgt).toFixed(3));
                    						
                    						net_wgt = api
                    						.column(4)
                    						.data()
                    						.reduce( function (a, b) {
                    							return intVal(a) + intVal(b);
                    						}, 0 );
                    						$(api.column(4).footer()).html(parseFloat(net_wgt).toFixed(3));
                    						
                    						total_amount = api
                    						.column(5)
                    						.data()
                    						.reduce( function (a, b) {
                    							return intVal(a) + intVal(b);
                    						}, 0 );
                    						$(api.column(5).footer()).html(parseFloat(total_amount).toFixed(3));
                    						
                    				} 
                    				}else{
                    					 var api = this.api(), data; 
                    					 $(api.column(3).footer()).html('');
                    					 $(api.column(3).footer()).html('');
                    					 $(api.column(5).footer()).html('');
                    				}
                    			}
                                }); 
                            }
			    }
		} 
		});
} 
//credit issued
function set_credit_issued_table()
{
    var dt_range=($("#dt_range").val()).split('-');
    var branch_name=($('#branch_name').val()!='' && $('#branch_name').val()!=undefined ? $('#branch_name').val() : $("#branch_select option:selected").text());
	var company_name=$('#company_name').val();
	var report_type=($('#report_type').val()==1 ? 'CREDIT ISSUED REPORT' :'CREDIT RECEIVED REPORT');
	var title="<b><span style='font-size:15pt;margin-left:32%;'>"+company_name+"</span></b><b><span style='font-size:15pt;'></span></b></br>"+"<span style=font-size:13pt;margin-left:20%;>&nbsp;&nbsp "+(report_type)+" &nbsp;"+($("#branch_select").val()!='' ? "COST CENTER:"+branch_name+" ":'')+"From&nbsp;:&nbsp;"+dt_range[0]+" &nbsp;&nbsp;- "+dt_range[1]+"</span>";
	
	
	$("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/credit_issued/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'dt_range' :$("#dt_range").val(),'report_type':$('#report_type').val(),'id_branch':($('#branch_filter').val()!='' && $('#branch_filter').val()!=undefined ? $('#branch_filter').val(): $("#branch_select").val())}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			 	var list = data.list;
			 	
			 	$('#credit_issue_details').css('display','block');
			 	$('#credit_received_details').css('display','none');
			 	$("#credit_issued_list > tbody > tr").remove();  
	 		    $('#credit_issued_list').dataTable().fnClearTable();
    		    $('#credit_issued_list').dataTable().fnDestroy();
    		    
    		    $("#credit_received_list > tbody > tr").remove();  
	 		    $('#credit_received_list').dataTable().fnClearTable();
    		    $('#credit_received_list').dataTable().fnDestroy();
    		    
			 	if($('#report_type').val()==1)
			 	{
			 	
    		    
    				if (list!= null && list != 0)
    				{  	
    					trHTML = ''; 
    					tfootHTML = ''; 
    					total_bill_amount=0;
    					total_paid_amount=0;
    					total_bal_amount=0;
    					$.each(list, function (i, branch) { 
    							var bill_amount=0;
    							var paid_amount=0;
    							var bal_amount=0;
    						$.each(branch, function (idx, item) { 
    							
    								trHTML += '<tr>'
    												+'<td>'+item.cus_name+'</td>'
    												+'<td>'+item.mobile+'</td>'
    												+'<td>'+item.bill_no+'</td>'
    												+'<td>'+item.bill_date+'</td>'
    												+'<td>'+item.credit_due_date+'</td>'
    												+'<td>'+item.tot_bill_amount+'</td>'
    												+'<td>'+item.tot_amt_received+'</td>'
    												+'<td>'+item.credit_ret_amt+'</td>'
    												+'<td>'+item.due_amt+'</td>'
    										   '</tr>';
    								bill_amount = parseFloat(bill_amount) + parseFloat(item.tot_bill_amount);
    								paid_amount = parseFloat(paid_amount) + parseFloat(item.tot_amt_received);
    								bal_amount  = parseFloat(bal_amount) + parseFloat(item.due_amt);
    						});	
    
    								trHTML += '<tr style="font-weight:bold;">' +
    												+'<td></td>'
    												+'<td></td>'
    												+'<td></td>'
    												+'<td></td>'
    											    +'<td></td>'
    												+'<td>Sub Total</td>'
    												+'<td>'+bill_amount+'</td>'
    												+'<td>'+paid_amount+'</td>'
    												+'<td></td>'
    												+'<td>'+bal_amount+'</td>'
    											
    											
    							    		 '</tr>';
    							    total_bill_amount=total_bill_amount+bill_amount;
    							    total_paid_amount=total_paid_amount+paid_amount;
    							    total_bal_amount=total_bal_amount+bal_amount;
    						
    					});
    					trHTML += '<tr style="font-weight:bold;">'
    									+'<td></td>'
    									+'<td></td>'
    									+'<td></td>'
    									+'<td></td>'
    									+'<td>Grand Total</td>'
    									+'<td>'+total_bill_amount+'</td>'
    									+'<td>'+total_paid_amount+'</td>'
    									+'<td></td>'
    									+'<td>'+total_bal_amount+'</td>'
    									
    								
    				    		 '</tr>';
    					$('#credit_issued_list > tbody').html(trHTML);
    					 // Check and initialise datatable
    					if ( ! $.fn.DataTable.isDataTable( '#credit_issued_list' ) ) { 
    						oTable = $('#credit_issued_list').dataTable({ 
    						"bSort": false, 
    						"bInfo": true, 
    						"scrollX":'100%',  
    						"dom": 'lBfrtip',
    						"buttons": [
    						{
    							extend: 'print',
    							footer: true,
    							messageTop: title,
    							title: '',
    							customize: function ( win ) {
    							$(win.document.body).find( 'table' )
    							.addClass( 'compact' )
    						    .css('font-size','10px')
								.css('font-family','sans-serif');
    							},
    						},
    						{
    							extend:'excel',
    							footer: true,
    							title: company_name+'-'+(branch_name!='' ? branch_name:'')+' - '+report_type+' '+dt_range[0]+'-'+dt_range[1],
    						}
    						], 
    						
    						});
    					}  		 	
    				}
			    }
			    else
			    {
			        	if (list!= null && list != 0)
			        	{
			        	     $('#credit_received_details').css('display','block');
        	                $('#credit_issue_details').css('display','none');
                            var oTable = $('#credit_received_list').DataTable();
                            oTable.clear().draw();
                            oTable = $('#credit_received_list').dataTable({
                            "bDestroy": true,
                            "bInfo": true,
                            "bFilter": true,
                            "order": [[ 0, "desc" ]],
                            "scrollX":'100%', 
                            "bSort": true, 
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
                            extend:'excel',
                            footer: true,
                            title: company_name+'-'+(branch_name!='' ? branch_name:'')+' - '+report_type+' '+dt_range[0]+'-'+dt_range[1],
                            }
                            ], 
                            "aaData": list,
                            
                            "aoColumns": [	
                            { "mDataProp": "cus_name" },
                            { "mDataProp": "mobile" },
                            { "mDataProp": "bill_date" },
                            { "mDataProp": "bill_no" },
                            { "mDataProp": "tot_amt_received" },
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
														i : 0;
													};	
													
													$(api.column(0).footer() ).html('Total');	
													gross_wgt = api
													.column(4)
													.data()
													.reduce( function (a, b) {
														return intVal(a) + intVal(b);
													}, 0 );
													$(api.column(4).footer()).html(parseFloat(gross_wgt).toFixed(2));	 
											} 
											}else{
												 var api = this.api(), data; 
												 $(api.column(4).footer()).html('');
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
//credit issued
//credit history
function get_ActiveCustomer()
{
    $('.overlay').css('display','block');
    $.ajax({
        type: 'GET',
        url:  base_url+'index.php/admin_ret_reports/get_bill_customer',
        dataType: 'json',
        success: function(data) {
        var id_village='';
        $.each(data, function (key, data) {
        $('#select_customer').append(
        $("<option></option>")
        .attr("value", data.id_customer)
        .text(data.mobile+'-'+data.cus_name)
        );
        
        $('#select_customer').select2({
            placeholder:"Select Customer",
            allowClear:true
        });
        $("#select_customer").select2("val",(id_village!=null ?id_village:''));
        });
        
            $('.overlay').css('display','none');
        },
        error:function(error)  
        {
            $("div.overlay").css("display", "none"); 
        }
    });
}
function set_credit_history_table()
{
    
    var dt_range=($("#dt_range").val()).split('-');
    var branch_name=($('#branch_name').val()!='' && $('#branch_name').val()!=undefined ? $('#branch_name').val() : $("#branch_select option:selected").text());
	var company_name=$('#company_name').val();
	var title="<b><span style='font-size:15pt;margin-left:32%;'>"+company_name+"</span></b><b><span style='font-size:15pt;'></span></b></br>"+"<span style=font-size:13pt;margin-left:20%;>&nbsp;&nbsp;CREDIT SALES HISTORY REPORT &nbsp;"+($("#branch_select").val()!='' ? "COST CENTER:"+branch_name+" ":'')+"From&nbsp;:&nbsp;"+dt_range[0]+" &nbsp;&nbsp;- "+dt_range[1]+"</span>";
	var report_type='CREDIT HISTORY REPORT';
	
	$("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/credit_history/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'id_customer':$('#select_customer').val(),'dt_range' :$("#dt_range").val(),'id_branch':($('#branch_filter').val()!='' && $('#branch_filter').val()!=undefined ? $('#branch_filter').val(): $("#branch_select").val())}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			 	var list = data.list;
				$("#credit_issued_list > tbody > tr").remove();  
	 		    $('#credit_issued_list').dataTable().fnClearTable();
    		    $('#credit_issued_list').dataTable().fnDestroy();
				if (list!= null && list!='')
				{  	
					trHTML = ''; 
					tfootHTML = ''; 
					total_bill_amount=0;
					total_paid_amount=0;
					total_bal_amount=0;
					total_credit_disc_amt=0;
					total_credit_ret_amt=0;
					total_old_metal_amount=0;
					$.each(list, function (i, branch) { 
							var bill_amount=0;
							var paid_amount=0;
							var credit_ret_amt=0;
							var bal_amount=0;
							var bill_bal_amt=0;
							var credit_disc_amt=0;
							var old_metal_amount=0;
						$.each(branch, function (idx, item) { 
						    if(item.type==0)
						    {
						        var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+item.bill_id;
						    }else
						    {
						        var url = base_url+'index.php/admin_ret_billing/issue/issue_print/'+item.bill_id;
						    }
						
								trHTML += '<tr>' +
												'<td>'+item.branch_name+'</td>'
												+'<td>'+item.cus_name+'</td>'
												+'<td>'+item.mobile+'</td>'
												+'<td><a href='+url+' target="_blank">'+item.bill_no+'</a></td>'
												+'<td>'+item.bill_date+'</td>'
												+'<td>'+parseFloat(item.tot_bill_amount).toFixed(2)+'</td>'
												+'<td>'+parseFloat(item.tot_amt_received).toFixed(2)+'</td>'
												+'<td>'+parseFloat(item.credit_disc_amt).toFixed(2)+'</td>'
												+'<td>'+parseFloat(item.credit_ret_amt).toFixed(2)+'</td>'
												+'<td></td>'
												+'<td>'+parseFloat(item.bal_amt).toFixed(2)+'</td>'
										   '</tr>';
								bill_amount = parseFloat(bill_amount) + parseFloat(item.tot_bill_amount);
								paid_amount = parseFloat(paid_amount) + parseFloat(item.tot_amt_received);
								credit_ret_amt = parseFloat(credit_ret_amt) + parseFloat(item.credit_ret_amt);
								bill_bal_amt  = parseFloat(item.bal_amt);
									
								if((item.credit_collection).length>0)
								{
									$.each(item.credit_collection,function(key,value)
									{
    									var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+value.bill_id;
    									bill_bal_amt=parseFloat(bill_bal_amt)-parseFloat(value.tot_amt_received)-parseFloat(value.credit_disc_amt)-parseFloat(value.old_metal_amount);
    									credit_disc_amt+=parseFloat(value.credit_disc_amt);
    									old_metal_amount+=parseFloat(value.old_metal_amount);
    									
    									if(item.type==0)
            						    {
            						        var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+value.bill_id;
            						    }else
            						    {
            						        var url = base_url+'index.php/admin_ret_billing/receipt/receipt_print/'+value.bill_id;
            						    }
    									
										trHTML +='<tr>' +
												+'<td></td>'
												+'<td></td>'
												+'<td></td>'
												+'<td></td>'
												+'<td><a href='+url+' target="_blank">'+value.bill_no+'</a></td>'
												+'<td>'+value.bill_date+'</td>'
												+'<td>'+(parseFloat(value.tot_bill_amount)+parseFloat(value.credit_disc_amt)).toFixed(2)+'</td>'
												+'<td>'+parseFloat(value.tot_amt_received).toFixed(2)+'</td>'
												+'<td>'+parseFloat(value.credit_disc_amt).toFixed(2)+'</td>'
												+'<td></td>'
												+'<td>'+parseFloat(value.old_metal_amount).toFixed(2)+'</td>'
												+'<td>'+parseFloat(bill_bal_amt).toFixed(2)+'</td>'
										   '</tr>';
										 paid_amount = parseFloat(paid_amount) + parseFloat(value.tot_amt_received);
									});
								}
						});	
					
				    	trHTML += '<tr style="font-weight:bold;">' +
										'<td></td>'
										+'<td></td>'
										+'<td></td>'
										+'<td></td>'
										+'<td>Sub Total</td>'
										+'<td>'+bill_amount+'</td>'
										+'<td>'+paid_amount+'</td>'
										+'<td>'+credit_disc_amt+'</td>'
										+'<td></td>'
										+'<td>'+old_metal_amount+'</td>'
										+'<td>'+parseFloat(parseFloat(paid_amount)-parseFloat(credit_ret_amt)-parseFloat(credit_disc_amt)-parseFloat(old_metal_amount)).toFixed(2);+'</td>'
										+'<td></td>'
					    		 '</tr>';
					     total_bill_amount=total_bill_amount+bill_amount;
						 total_paid_amount=total_paid_amount+paid_amount;
					     total_bal_amount=total_bal_amount+(parseFloat(paid_amount)-parseFloat(credit_ret_amt)-parseFloat(credit_disc_amt)-parseFloat(old_metal_amount));
					     total_credit_disc_amt=total_credit_disc_amt+credit_disc_amt;
					     total_credit_ret_amt=total_credit_ret_amt+credit_ret_amt;
					     total_old_metal_amount=total_old_metal_amount+old_metal_amount;
					});
					trHTML += '<tr style="font-weight:bold;">' +
									'<td></td>'
									+'<td></td>'
									+'<td></td>'
									+'<td></td>'
									+'<td>Grand Total</td>'
									+'<td>'+total_bill_amount+'</td>'
									+'<td>'+total_paid_amount+'</td>'
									+'<td>'+total_credit_disc_amt+'</td>'
									+'<td></td>'
									+'<td>'+total_old_metal_amount+'</td>'
									+'<td>'+total_bal_amount+'</td>'
									+'<td></td>'
				    		 '</tr>'; 	
				    $('#credit_issued_list > tbody').html(trHTML);
				     // Check and initialise datatable
					if ( ! $.fn.DataTable.isDataTable( '#credit_issued_list' ) ) { 
						oTable = $('#credit_issued_list').dataTable({ 
						"bSort": false, 
						"bInfo": true, 
						"scrollX":'100%',  
						"dom": 'lBfrtip',
						"paging":false,
						"buttons": [
						{
							extend: 'print',
							footer: true,
							title: '',
							messageTop: title,
							customize: function ( win ) {
							$(win.document.body).find( 'table' )
							.addClass( 'compact' )
							.css('font-size','10px')
							.css('font-family','sans-serif');
							},
						},
						{
							extend:'excel',
							footer: true,
							title: company_name+'-'+(branch_name!='' ? branch_name:'')+' - '+report_type+' '+dt_range[0]+'-'+dt_range[1],
						}
						], 
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
//credit history
function set_advance_history_table()
{
	$("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/advance_history/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'dt_range' :$("#dt_range").val(),'id_branch':$("#branch_select").val()}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			 	var list = data.list;
						  
				if (list!= null)
				{  	
					trHTML = ''; 
					tfootHTML = ''; 
					total_adv_amount=0;
					total_adjusted_amount=0;
					$.each(list, function (i, branch) { 
							var adv_amount=0;
							var adjusted_amt=0;
						$.each(branch, function (idx, item) { 
								trHTML += '<tr>' +
												'<td>'+item.branch_name+'</td>'
												+'<td>'+item.cus_name+'</td>'
												+'<td>'+item.mobile+'</td>'
												+'<td>'+item.total_adv_amount+'</td>'
												+'<td>'+item.adjusted_amt+'</td>'
										   '</tr>';
								adv_amount   = parseFloat(adv_amount) + parseFloat(item.total_adv_amount);
								adjusted_amt = parseFloat(adjusted_amt) + parseFloat(item.adjusted_amt);
						});	
					
				    	trHTML += '<tr style="font-weight:bold;">' +
										+'<td></td>'
										+'<td></td>'
										+'<td></td>'
										+'<td>Sub Total</td>'
										+'<td>'+adv_amount+'</td>'
										+'<td>'+adjusted_amt+'</td>'
					    		 '</tr>';
					     total_adv_amount=total_adv_amount+adv_amount;
						 total_adjusted_amount=total_adjusted_amount+adjusted_amt;
					});
					trHTML += '<tr style="font-weight:bold;">' +
									+'<td></td>'
									+'<td></td>'
									+'<td></td>'
									+'<td>Grand Total</td>'
									+'<td>'+total_adv_amount+'</td>'
									+'<td>'+total_adjusted_amount+'</td>'
				    		 '</tr>'; 	
				    $('#advance_list > tbody').html(trHTML);
				     // Check and initialise datatable
					if ( ! $.fn.DataTable.isDataTable( '#advance_list' ) ) { 
						oTable = $('#advance_list').dataTable({ 
						"bSort": false, 
						"bInfo": true, 
						"scrollX":'100%',  
						"dom": 'lBfrtip',
						"paging":false,
						"buttons": [
						{
							extend: 'print',
							footer: true,
							title: "Advance History Report - "+$("#dt_range").val(),
							customize: function ( win ) {
							$(win.document.body).find( 'table' )
							.addClass( 'compact' )
							.css( 'font-size', 'inherit' );
							},
						},
						{
							extend:'excel',
							footer: true,
							title: "Advance History Report - "+$("#dt_range").val(),
						}
						], 
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
//chit closing
function set_chit_closing_table()
{
	$("div.overlay").css("display", "block"); 
	var dt_range=($("#dt_range").val()).split('-');
	var branch_name=$("#branch_select option:selected").text();
	var company_name=$('#company_name').val();
	var title="<b><span style='font-size:15pt;margin-left:40%;'>"+company_name+"</span></b><b><span style='font-size:15pt;'></span></b></br>"+"<span style=font-size:13pt;margin-left:25%;>&nbsp;&nbsp;Chit Closing  Report "+($("#branch_select").val()!='' ? "Cost Center:"+branch_name+" ":'')+" From&nbsp;:&nbsp;"+dt_range[0]+" &nbsp;&nbsp;To Date&nbsp;:&nbsp;"+dt_range[1]+"</span>";
	
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/chit_closing_details/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'dt_range' :$("#dt_range").val(),'id_branch':$("#branch_select").val()}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			 	var list = data.list;
				$("#chit_closing_list > tbody > tr").remove();  
	 		    $('#chit_closing_list').dataTable().fnClearTable();
    		    $('#chit_closing_list').dataTable().fnDestroy();
				if (list!= null && list.length>0)
				{  	
					trHTML = ''; 
					tfootHTML = ''; 
					total_bill_amount=0;
					total_closing_amount=0;
						var bill_amount=0;
							var closing_amt=0;
					$.each(list, function (i, item) { 
						
						    var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+item.bill_id;
						    var chit_url = base_url+'index.php/reports/payment/account/'+item.scheme_account_id;
								trHTML += '<tr>'
								                +'<td>'+item.bill_date+'</td>'
								                +'<td><a href='+url+' target="_blank">'+item.bill_no+'</a></td>'
												+'<td>'+item.cus_name+'</td>'
												+'<td><a href='+chit_url+' target="_blank">'+item.scheme_acc_number+'</a></td>'
												+'<td>'+item.paid_installments+'/'+item.total_installments+'</td>'
												+'<td>'+item.utilized_amt+'</td>'
												+'<td>'+item.closing_add_chgs+'</td>'
												+'<td><a href='+chit_url+' target="_blank">'+item.scheme_account_id+'</a></td>'
												
										   '</tr>';
								bill_amount   = parseFloat(bill_amount) + parseFloat(item.tot_bill_amount);
								closing_amt = parseFloat(closing_amt) + parseFloat(item.utilized_amt);
						});	
					
				    	trHTML += '<tr style="font-weight:bold;">' +
										+'<td></td>'
										+'<td></td>'
										+'<td></td>'
										+'<td></td>'
										+'<td></td>'
										+'<td>Sub Total</td>'
										+'<td>'+closing_amt+'</td>'
										+'<td></td>'
										+'<td></td>'
										+'<td></td>'
										+'<td></td>'
										+'<td></td>'
					    		 '</tr>';
						 total_closing_amount=total_closing_amount+closing_amt;
					trHTML += '<tr style="font-weight:bold;">' +
									+'<td></td>'
									+'<td></td>'
									+'<td></td>'
									+'<td></td>'
									+'<td></td>'
									+'<td>Grand Total</td>'
									+'<td>'+total_closing_amount+'</td>'
									+'<td></td>'
									+'<td></td>'
									+'<td></td>'
									+'<td></td>'
				    		 '</tr>'; 	
				    $('#chit_closing_list > tbody').html(trHTML);
				     // Check and initialise datatable
					if ( ! $.fn.DataTable.isDataTable( '#chit_closing_list' ) ) { 
						oTable = $('#chit_closing_list').dataTable({ 
						"bSort": false, 
						"bInfo": true, 
						"scrollX":'100%',  
						"dom": 'lBfrtip',
						"paging":false,
						"buttons": [
						{
							extend: 'print',
							footer: true,
							title: '',
							messageTop: title,
							customize: function ( win ) {
                                $(win.document.body)
                                    .css( 'font-size', '12pt' );
             
                            },
						},
						{
							extend:'excel',
							footer: true,
							title: "Chit Clsoing Report - "+$("#dt_range").val(),
						}
						], 
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
//chit closing
//Card Payment
function set_card_payment_table()
{
    var dt_range=($("#dt_range").val()).split('-');
    var branch_name=($('#branch_name').val()!='' && $('#branch_name').val()!=undefined ? $('#branch_name').val() : $("#branch_select option:selected").text());
	var company_name=$('#company_name').val();
	var title="<b><span style='font-size:15pt;margin-left:30%;'>"+company_name+"</span></b><b><span style='font-size:12pt;'></span></b></br>"+"<span style=font-size:13pt;margin-left:20%;>&nbsp;&nbsp;Card Payment Report "+($("#branch_select").val()!='' ? "Cost Center:"+branch_name+" ":'')+" From&nbsp;:&nbsp;"+dt_range[0]+" -"+dt_range[1]+"</span>";
	
	
	$("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/card_payment/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'dt_range' :$("#dt_range").val(),'id_branch':($('#branch_filter').val()!='' && $('#branch_filter').val()!=undefined ? $('#branch_filter').val(): $("#branch_select").val()),'report_tpe':$('#report_tpe').val()}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			 	var list = data.list;
			 	$("#bill_wise_list > tbody > tr").remove();  
	 		    $('#bill_wise_list').dataTable().fnClearTable();
    		    $('#bill_wise_list').dataTable().fnDestroy();
    		    
    		    $("#date_wise_list > tbody > tr").remove();  
	 		    $('#date_wise_list').dataTable().fnClearTable();
    		    $('#date_wise_list').dataTable().fnDestroy();
    		    
			 
			 		$('#date_wise_report').css('display','none');
					$('#bill_wise_report').css('display','block');
			 		$("#bill_wise_list > tbody > tr").remove();  				
			 		$("#date_wise_list > tbody > tr").remove();  		
					if (list!= null)
					{  	
						trHTML = ''; 
						tfootHTML = ''; 
						total_debit_payment=0;
						total_credit_payment=0;
						total_net_payment=0;
						$.each(list, function (i, title) { 
								var debit_payment=0;
								var credit_payment=0;
								var net_payment=0;
								 trHTML += '<tr>' +
                                            '<td><strong>'+i+'</strong></td>'
                                            +'<td></td>'
                                            +'<td></td>'
                                            +'<td></td>'
                                            +'<td></td>'
                                            +'<td></td>'
                                            +'<td></td>'
                                        '</tr>';
							$.each(title, function (idx, item) { 
									trHTML += '<tr>' +
									                 '<td>'+item.bill_date+'</td>'
									                 +'<td>'+item.bill_no+'</td>'
													+'<td>'+item.cus_name+'</td>'
													+'<td>'+item.mobile+'</td>'
													+'<td>'+item.crdit_payment+'</td>'
													+'<td>'+item.debit_payment+'</td>'
													+'<td>'+item.net_payment+'</td>'
											   '</tr>';
									credit_payment   = parseFloat(credit_payment) + parseFloat(item.crdit_payment);
									debit_payment = parseFloat(debit_payment) + parseFloat(item.debit_payment);
									net_payment = parseFloat(net_payment) + parseFloat(item.net_payment);
							});	
						
					    	trHTML += '<tr style="font-weight:bold;">' +
											+'<td></td>'
											+'<td></td>'
											+'<td></td>'
											+'<td></td>'
											+'<td>Sub Total</td>'
											+'<td>'+credit_payment+'</td>'
											+'<td>'+debit_payment+'</td>'
											+'<td>'+net_payment+'</td>'
											+'<td></td>'
						    		 '</tr>';
						     total_credit_payment=total_credit_payment+credit_payment;
							 total_debit_payment=total_debit_payment+debit_payment;
							 total_net_payment=total_net_payment+net_payment;
						});
						trHTML += '<tr style="font-weight:bold;">' +
										+'<td></td>'
										+'<td></td>'
										+'<td></td>'
										+'<td></td>'
										+'<td>Grand Total</td>'
										+'<td>'+total_credit_payment+'</td>'
										+'<td>'+total_debit_payment+'</td>'
										+'<td>'+total_net_payment+'</td>'
					    		 '</tr>'; 	
					    $('#bill_wise_list > tbody').html(trHTML);
					     // Check and initialise datatable
						if ( ! $.fn.DataTable.isDataTable( '#bill_wise_list' ) ) { 
							oTable = $('#bill_wise_list').dataTable({ 
							"bSort": false, 
							"bInfo": true, 
							"scrollX":'100%',  
							"dom": 'lBfrtip',
							"paging":false,
							"buttons": [
							{
								extend: 'print',
								footer: true,
								title: '',
								messageTop :title,
								customize: function ( win ) {
								$(win.document.body).find( 'table' )
								.addClass( 'compact' )
								.css( 'font-size', 'inherit' );
								},
							},
							{
								extend:'excel',
								footer: true,
								title: "Card Payment Bill Wise Report - "+$("#dt_range").val(),
							}
							], 
							});
						} 
					}
			
				/*else
				{
					$('#bill_wise_report').css('display','none');
					$('#date_wise_report').css('display','block');
			 		$("#bill_wise_list > tbody > tr").remove();  				
			 		$("#date_wise_list > tbody > tr").remove();  				
					if (list!= null)
					{  	
						trHTML = ''; 
						tfootHTML = ''; 
						total_debit_payment=0;
						total_credit_payment=0;
						$.each(list, function (i, branch) { 
								var debit_payment=0;
								var credit_payment=0;
							$.each(branch, function (idx, item) { 
									trHTML += '<tr>' +
													'<td>'+item.branch_name+'</td>'
													+'<td>'+item.bill_date+'</td>'
													+'<td>'+item.crdit_payment+'</td>'
													+'<td>'+item.debit_payment+'</td>'
											   '</tr>';
									credit_payment   = parseFloat(credit_payment) + parseFloat(item.crdit_payment);
									debit_payment = parseFloat(debit_payment) + parseFloat(item.debit_payment);
							});	
						
					    	trHTML += '<tr style="font-weight:bold;">' +
											+'<td></td>'
											+'<td></td>'
											+'<td>Sub Total</td>'
											+'<td>'+credit_payment+'</td>'
											+'<td>'+debit_payment+'</td>'
											+'<td></td>'
						    		 '</tr>';
						     total_credit_payment=total_credit_payment+credit_payment;
							 total_debit_payment=total_debit_payment+debit_payment;
						});
						trHTML += '<tr style="font-weight:bold;">' +
										+'<td></td>'
										+'<td></td>'
										+'<td>Grand Total</td>'
										+'<td>'+total_credit_payment+'</td>'
										+'<td>'+total_debit_payment+'</td>'
					    		 '</tr>'; 	
					    $('#date_wise_list > tbody').html(trHTML);
					     // Check and initialise datatable
						if ( ! $.fn.DataTable.isDataTable( '#date_wise_list' ) ) { 
							oTable = $('#date_wise_list').dataTable({ 
							"bSort": false, 
							"bInfo": true, 
							"scrollX":'100%',  
							"dom": 'lBfrtip',
							"paging":false,
							"buttons": [
							{
								extend: 'print',
								footer: true,
								title: "Card Payment Bill Wise Report - "+$("#dt_range").val(),
								customize: function ( win ) {
								$(win.document.body).find( 'table' )
								.addClass( 'compact' )
								.css( 'font-size', 'inherit' );
								},
							},
							{
								extend:'excel',
								footer: true,
								title: "Card Payment Bill Wise Report - "+$("#dt_range").val(),
							}
							], 
							});
						} 
					}
				}*/
				$("div.overlay").css("display", "none"); 
			  },
			  error:function(error)  
			  {
				 $("div.overlay").css("display", "none"); 
			  }	 
	      });
}
//Card Payment
//Duplicate bill
function set_copy_bill_table()
{
	$("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/copy_bill_details/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'dt_range' :$("#dt_range").val(),'id_branch':$("#branch_select").val()}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			 	var list = data.list;
			 		$("#copy_bill_list > tbody > tr").remove();  		
					if (list!= null)
					{  	
						trHTML = ''; 
						tfootHTML = ''; 
						$.each(list, function (i, branch) { 
							$.each(branch, function (idx, item) { 
									trHTML += '<tr>' +
													'<td>'+item.branch_name+'</td>'
													+'<td>'+item.cus_name+'</td>'
													+'<td>'+item.mobile+'</td>'
													+'<td>'+item.bill_no+'</td>'
													+'<td>'+item.bill_date+'</td>'
													+'<td>'+item.tot_bill_amount+'</td>'
													+'<td>'+item.print_date+'</td>'
													+'<td>'+item.emp_name+'</td>'
													+'<td>'+item.total_copy+'</td>'
											   '</tr>';
							});	
						
					    	trHTML += '<tr style="font-weight:bold;">' +
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
						    		 '</tr>';
						});
						
					    $('#copy_bill_list > tbody').html(trHTML);
					     // Check and initialise datatable
						if ( ! $.fn.DataTable.isDataTable( '#copy_bill_list' ) ) { 
							oTable = $('#copy_bill_list').dataTable({ 
							"bSort": false, 
							"bInfo": true, 
							"scrollX":'100%',  
							"dom": 'lBfrtip',
							"paging":false,
							"buttons": [
							{
								extend: 'print',
								footer: true,
								title: "Duplicate Report - "+$("#dt_range").val(),
								customize: function ( win ) {
								$(win.document.body).find( 'table' )
								.addClass( 'compact' )
								.css( 'font-size', 'inherit' );
								},
							},
							{
								extend:'excel',
								footer: true,
								title: "Duplicate Copy Bill Report - "+$("#dt_range").val(),
							}
							], 
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
//duplicate bill
//PAN Bill details
function set_pan_bill_table()
{
    
    var dt_range=($("#dt_range").val()).split('-');
    var branch_name=($('#branch_name').val()!='' && $('#branch_name').val()!=undefined ? $('#branch_name').val() : $("#branch_select option:selected").text());
	var company_name=$('#company_name').val();
	var title="<b><span style='font-size:15pt;margin-left:30%;'>"+company_name+"</span></b><b><span style='font-size:12pt;'></span></b></br>"+"<span style=font-size:13pt;margin-left:20%;>&nbsp;&nbsp;PAN Bill Report "+($("#branch_select").val()!='' ? "Cost Center:"+branch_name+" ":'')+" From&nbsp;:&nbsp;"+dt_range[0]+" -"+dt_range[1]+"</span>";
	
	
	$("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/pan_bill_details/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'dt_range' :$("#dt_range").val(),'id_branch':($('#branch_filter').val()!='' && $('#branch_filter').val()!=undefined ? $('#branch_filter').val(): $("#branch_select").val())}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			 	var list = data.list;
			 			$("#pan_bill_list > tbody > tr").remove();  
	 		            $('#pan_bill_list').dataTable().fnClearTable();
    		            $('#pan_bill_list').dataTable().fnDestroy();
					if (list!= null)
					{  	
						trHTML = ''; 
						tfootHTML = ''; 
						$.each(list, function (i, branch) { 
							$.each(branch, function (idx, item) { 
							    var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+item.bill_id;
									trHTML += '<tr>' +
										            '<td>'+item.bill_date+'</td>'
										            +'<td><a href='+url+' target="_blank">'+item.bill_no+'</a></td>'
													+'<td>'+item.cus_name+'</td>'
													+'<td>'+item.mobile+'</td>'
													+'<td>'+item.tot_bill_amount+'</td>'
													+'<td>'+item.pan_no+'</td>'
											   '</tr>';
							});	
						
					    	trHTML += '<tr style="font-weight:bold;">' +
											+'<td></td>'
											+'<td></td>'
											+'<td></td>'
											+'<td></td>'
											+'<td></td>'
											+'<td></td>'
											+'<td></td>'
						    		 '</tr>';
						});
						
					    $('#pan_bill_list > tbody').html(trHTML);
					     // Check and initialise datatable
						if ( ! $.fn.DataTable.isDataTable( '#pan_bill_list' ) ) { 
							oTable = $('#pan_bill_list').dataTable({ 
							"bSort": false, 
							"bInfo": true, 
							"scrollX":'100%',  
							"dom": 'lBfrtip',
							"paging":false,
							"buttons": [
							{
								extend: 'print',
								title: '',
								messageTop :title,
								customize: function ( win ) {
								$(win.document.body).find( 'table' )
								.addClass( 'compact' )
								.css( 'font-size', 'inherit' );
								},
							},
							{
								extend:'excel',
								footer: true,
								title: "Card Payment Bill Wise Report - "+$("#dt_range").val(),
							}
							], 
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
//PAN Bill details
function get_category()
{ 
    $("div.overlay").css("display", "block"); 
    $("#category option").remove();
	$.ajax({
		type: 'POST',
		url: base_url+'index.php/admin_ret_catalog/category/active_category',
		dataType:'json',
		data:{'id_metal':$('#metal').val()},
		success:function(data){ 
			$("#category").append(						
				$("<option></option>")						
				.attr("value", 0)						  						  
				.text('All' )
				);
		    $.each(data, function (key, item) {
			   		$('#category').append(
						$("<option></option>")
						  .attr("value", item.id_ret_category)
						  .text(item.name)
					);
			});
			$("#category").select2({
			    placeholder: "Select Category",
			    allowClear: true
			}); 
			if(ctrl_page[1] == 'categorywise_bt_report'){
			    $('#branch_select').select2({			    
            	 	placeholder: "To Centre",			    
            	 	allowClear: true		    
             	}); 
             	$('#branch_select_to').select2({			    
            	 	placeholder: "Cost Centre",			    
            	 	allowClear: true		    
             	}); 
			}
			
		}
	});
	$("div.overlay").css("display", "none"); 
}
 
// Cancelled Bills
function set_cancelledBills()
{
	$("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
		 url:base_url+ "index.php/admin_ret_reports/cancelled_bills/ajax?nocache=" + my_Date.getUTCSeconds(),
		 data: ( {'dt_range' :$("#dt_range").val(),'id_branch':$("#branch_select").val()}),
		 dataType:"JSON",
		 type:"POST",
		 success:function(data){
		 	var list = data.list;
	 		$("#cancelled_bill_list > tbody > tr").remove();  		
			if (list!= null)
			{  	
				trHTML = ''; 
				tfootHTML = '';
				total_bill_amount = 0;
				$.each(list, function (i, branch) { 
					var bill_amount = 0;
					$.each(branch, function (idx, item) { 
					    var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+item.bill_id;
						trHTML += '<tr>' 
									+'<td>'+item.cus_name+'</td>'
									+'<td>'+item.mobile+'</td>'
									+'<td><a href='+url+' target="_blank">'+item.bill_no+'</a></td>'
									+'<td>'+item.bill_type+'</td>'
									+'<td>'+item.bill_date+'</td>'
									+'<td>'+item.cancelled_date+'</td>'
									+'<td>'+item.tot_bill_amount+'</td>'
									+'<td>'+item.cancel_reason+'</td>'
					   			'</tr>';
						bill_amount = parseFloat(bill_amount) + parseFloat(item.tot_bill_amount);
					});	 
			    	trHTML += '<tr style="font-weight:bold;">' +
									+'<td></td>'
									+'<td></td>'
									+'<td></td>'
									+'<td></td>'
									+'<td></td>'
									+'<td></td>'
									+'<td>Sub Total</td>'
									+'<td>'+bill_amount+'</td>'
									+'<td></td>'
				    		 '</tr>';
				     total_bill_amount = total_bill_amount+bill_amount;
				});
				trHTML += '<tr style="font-weight:bold;">' +
								+'<td></td>'
								+'<td></td>'
								+'<td></td>'
								+'<td></td>'
								+'<td></td>'
								+'<td></td>'
								+'<td>Grand Total</td>'
								+'<td>'+total_bill_amount+'</td>'
								+'<td></td>'
			    		 '</tr>';    
			    $('#cancelled_bill_list > tbody').html(trHTML);
			     // Check and initialise datatable
				if ( ! $.fn.DataTable.isDataTable( '#cancelled_bill_list' ) ) { 
					oTable = $('#cancelled_bill_list').dataTable({ 
					"bSort": false, 
					"bInfo": true, 
					"scrollX":'100%',  
					"dom": 'lBfrtip',
					"paging":false,
					"buttons": [
					{
						extend: 'print',
						footer: true,
						title: "Cancelled Bills Report - "+$("#dt_range").val(),
						customize: function ( win ) {
						$(win.document.body).find( 'table' )
						.addClass( 'compact' )
						.css( 'font-size', 'inherit' );
						},
					},
					{
						extend:'excel',
						footer: true,
						title: "Cancelled Bills Report - "+$("#dt_range").val(),
					}
					], 
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
//Cancelled Bills - Ends
// Discounted Bills
$("#disc_rep_type").on("change", function(){  
	 if(this.value == 1){
	 	$(".without_bill_detail").css("display","block");
	 	$(".with_bill_detail").css("display","none");
	 }
	 else if(this.value == 2){
	 	$(".with_bill_detail").css("display","block");
	 	$(".without_bill_detail").css("display","none");
	 }
});
/*function set_discBills()
{
	$("div.overlay").css("display", "block"); 
	
	var dt_range=($("#dt_range").val()).split('-');
    var branch_name=($('#branch_name').val()!='' && $('#branch_name').val()!=undefined ? $('#branch_name').val() : $("#branch_select option:selected").text());
	var company_name=$('#company_name').val();
	var title="<b><span style='font-size:15pt;margin-left:30%;'>"+company_name+"</span></b><b><span style='font-size:12pt;'></span></b></br>"+"<span style=font-size:13pt;margin-left:20%;>&nbsp;&nbsp;Discount Bill Report "+($("#branch_select").val()!='' ? "Cost Center:"+branch_name+" ":'')+" From&nbsp;:&nbsp;"+dt_range[0]+" -"+dt_range[1]+"</span>";
	
	
	my_Date = new Date();
	$.ajax({
		 url:base_url+ "index.php/admin_ret_reports/bill_discount/ajax?nocache=" + my_Date.getUTCSeconds(),
		 data: ( {'dt_range' :$("#dt_range").val(),'id_branch':($('#branch_filter').val()!='' && $('#branch_filter').val()!=undefined ? $('#branch_filter').val(): $("#branch_select").val()),'disc_rep_type':$("#disc_rep_type").val()}),
		 dataType:"JSON",
		 type:"POST",
		 success:function(data){
		 	$("div.overlay").css("display", "none");
		 
				var list = data.list;
		 		$("#disc_bill_detail > tbody > tr").remove();  		
				if (list!= null)
				{  	
					trHTML = ''; 
					tfootHTML = '';
					total_bill_amount = 0;
					total_bill_discount = 0;
					$.each(list, function (i, bran) {  
						var bill_amount = 0;
						var discount = 0;
						var prev_bill_no = "";
						var bill_item_amt = 0;
						var bill_item_disc = 0; 
					
						$.each(bran, function (idx, item) { 
						    	
							$.each(item, function (i, bill_item) { 
							     var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+bill_item.bill_id;
						        var discount_per=parseFloat((bill_item.bill_discount/bill_item.item_cost)*100).toFixed(2);
								   	trHTML += '<tr>'  
												+'<td>'+bill_item.bill_date+'</td>' 
												+'<td><a href='+url+' target="_blank">'+bill_item.bill_no+'</a></td>'
											 	+'<td>'+bill_item.cus_name+'</td>'     
												+'<td>'+bill_item.mobile+'</td>' 
												+'<td>'+bill_item.product_name+'</td>'
												+'<td>'+bill_item.design_name+'</td>'
												+'<td>'+bill_item.mc_value+'</td>'
												+'<td>'+bill_item.net_wt+'</td>'
												+'<td>'+bill_item.bill_discount+'</td>'
												+'<td>'+discount_per+'</td>'
												+'<td>'+bill_item.item_cost+'</td>'
								   			'</tr>';  
								discount = parseFloat(discount) + parseFloat(bill_item.bill_discount); 
								bill_amount = parseFloat(bill_amount) + parseFloat(bill_item.tot_bill_amount);
						   	}) 
						});	 
				    	trHTML += '<tr style="font-weight:bold;">' +
										+'<td></td>'
										+'<td></td>' 
										+'<td></td>'
										+'<td></td>'
										+'<td></td>'
										+'<td></td>'
										+'<td></td>'
										+'<td></td>'
										+'<td>Sub Total</td>'
										+'<td>'+discount+'</td>'
										+'<td></td>'
										+'<td>'+bill_amount+'</td>'
					    		 '</tr>';
					     total_bill_amount = total_bill_amount+bill_amount;
					     total_bill_discount = total_bill_discount+discount;
					});
					trHTML += '<tr style="font-weight:bold;">' +
									+'<td></td>'
									+'<td></td>'
									+'<td></td>' 
									+'<td></td>'
									+'<td></td>'
									+'<td></td>'
									+'<td></td>'
									+'<td></td>'
									+'<td>Grand Total</td>'
									+'<td>'+total_bill_discount+'</td>'
									+'<td></td>'
									+'<td>'+total_bill_amount+'</td>'
				    		 '</tr>';    
				    $('#disc_bill_detail > tbody').html(trHTML);
					if ( ! $.fn.DataTable.isDataTable( '#disc_bill_detail' ) ) { 
						oTable = $('#disc_bill_detail').dataTable({ 
							"bSort": false, 
							"bInfo": true, 
							"scrollX":'100%',  
							"dom": 'lBfrtip',
							"paging":false,
							"buttons": [
							{
								extend: 'print',
								footer: true,
								title: '',
								messageTop: title,
								customize: function ( win ) {
								$(win.document.body).find( 'table' )
								.addClass( 'compact' )
								.css('font-size','10px')
        						.css('font-family','sans-serif');
								},
							},
							{
								extend:'excel',
								footer: true,
								title: "Discount Bills Report - "+$("#dt_range").val(),
							}
							], 
						});
					} 
				} 	
			//} 
	  	},
	  	error:function(error)  
	  	{
			$("div.overlay").css("display", "none"); 
	  	}	 
  	});
}*/
function set_discBills()
{
    $("div.overlay").css("display", "block");
    
    var dt_range=($("#dt_range").val()).split('-');
    var branch_name=($('#branch_name').val()!='' && $('#branch_name').val()!=undefined ? $('#branch_name').val() : $("#branch_select option:selected").text());
	var company_name=$('#company_name').val();
	var title="<b><span style='font-size:15pt;margin-left:30%;'>"+company_name+"</span></b><b><span style='font-size:12pt;'></span></b></br>"+"<span style=font-size:13pt;margin-left:20%;>&nbsp;&nbsp;Discount Bill Report "+($("#branch_select").val()!='' ? "Cost Center:"+branch_name+" ":'')+" From&nbsp;:&nbsp;"+dt_range[0]+" -"+dt_range[1]+"</span>";
	
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/bill_discount/ajax?nocache=" + my_Date.getUTCSeconds(),
		     data: ( {'dt_range' :$("#dt_range").val(),'id_branch':($('#branch_filter').val()!='' && $('#branch_filter').val()!=undefined ? $('#branch_filter').val(): $("#branch_select").val()),'disc_rep_type':$("#disc_rep_type").val()}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			 	$("div.overlay").css("display", "none"); 
			 	var list = data.list;
				$('#total_count').text(list.length);
				var oTable = $('#disc_bill_detail').DataTable();
				oTable.clear().draw();				  
				if (list!= null && list.length > 0)
				{  	
					oTable = $('#disc_bill_detail').dataTable({
						"bDestroy": true,
		                "bInfo": true,
		                "bFilter": true,
						"order": [[ 0, "desc" ]],
		                "scrollX":'100%',
		                "bSort": true,
		                "dom": 'lBfrtip',
		                "fixedHeader": true,
		                "paging": false,
			            "autoWidth": true,
		                "buttons": [
								 {
								   extend: 'print',
								   footer: true,
								   title: '',
								   messageTop: title,
								   	customize: function ( win ) {
            						$(win.document.body).find( 'table' )
            						.addClass( 'compact' )
            						.css( 'font-size', '10px' );
            						},
								 },
								 {
									extend:'excel',
									footer: true,
								    title: 'Discount Bill Report', 
								  }
								 ],
						"aaData": list,
						"aoColumns": [	
										{ "mDataProp": "bill_date" },
										{ "mDataProp": function ( row, type, val, meta ){
										    var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+row.bill_id;
											return '<a href='+url+' target="_blank">'+row.bill_no+'</a>';
										},
										},
										{ "mDataProp": "cus_name" },
										{ "mDataProp": "product_name" },
										{ "mDataProp": "design_name" },
										{ "mDataProp": "sub_design_name" },
										{ "mDataProp": "net_wt" },
										{ "mDataProp": "wastage_percent" },
										{ "mDataProp": function ( row, type, val, meta ){
										    var mc_value=0;
										    if(row.calculation_based_on==0 || row.calculation_based_on==2)
										    {
										        mc_value=(row.mcType==1 ? parseFloat(row.mc_value*row.gross_wt):parseFloat(row.mc_value*row.piece));
										    }else if(row.calculation_based_on==1)
										    {
										        mc_value=(row.mcType==1 ? parseFloat(row.mc_value*row.net_wt):parseFloat(row.mc_value*row.piece));
										    }
										    return parseFloat(mc_value).toFixed(3);
										},
										},
										
										{ "mDataProp": function ( row, type, val, meta ){
                                           var item_cost=(row.item_cost-row.item_total_tax)
                                            return item_cost.toFixed(2);
                                             
                                        },
                                        },
										
										{ "mDataProp": "bill_discount" },
										
										{ "mDataProp": function ( row, type, val, meta ){
										    var mc_value=0;
										    var wast_wt=0;
										    var disc_per='-';
										    /*if(row.calculation_based_on==0 || row.calculation_based_on==2)
										    {
										        var wast_wt=parseFloat(parseFloat(row.gross_wt)*parseFloat(row.wastage_percent)).toFixed(3);
										        mc_value=(row.mcType==1 ? parseFloat(row.mc_value*row.gross_wt):parseFloat(row.mc_value*row.piece));
										    }else if(row.calculation_based_on==1)
										    {
										        var wast_wt=parseFloat(parseFloat(row.net_wt)*parseFloat(row.wastage_percent)).toFixed(3);
										        mc_value=(row.mcType==1 ? parseFloat(row.mc_value*row.net_wt):parseFloat(row.mc_value*row.piece));
										    }
										    if(mc_value<row.bill_discount && mc_value>0)
										    {
										        disc_per=parseFloat(((row.bill_discount-mc_value)/parseFloat(row.rate_per_grm)/row.net_wt)*100).toFixed(2);
										    }*/
										    
										    /*disc_per=parseFloat((row.bill_discount/row.item_cost)*100).toFixed(2);
										    
										    return disc_per;*/
										    
										    
										    var dis_per=(row.bill_discount/row.item_cost)*100
                                            return dis_per.toFixed(2);
                                             
										},
										},
										
										
										
										
									   /* { "mDataProp": function ( row, type, val, meta ){
									         var mc_value=0;
										    if(row.calculation_based_on==0 || row.calculation_based_on==2)
										    {
										        mc_value=(row.mcType==1 ? parseFloat(row.mc_value*row.gross_wt):parseFloat(row.mc_value*row.piece));
										    }else if(row.calculation_based_on==1)
										    {
										        mc_value=(row.mcType==1 ? parseFloat(row.mc_value*row.net_wt):parseFloat(row.mc_value*row.piece));
										    }
										    return parseFloat(parseFloat(row.mc_value)-parseFloat(row.bill_discount)/parseFloat(row.gross_wt)*100)
										},
										},*/
										
										/*{ "mDataProp": function ( row, type, val, meta ){
											var tag_details=row.tag_details;
											return tag_details.gross_wt;
										},
										},
										{ "mDataProp": "min_pcs" },
										{ "mDataProp": "max_pcs" },*/
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
/*
$(document).on('click', ".btn-disc-det", function(e) {
	var table = $('#disc_bill_list').DataTable();
	var tr = $(this).closest('tr');
	var row = table.row( tr ); 
	if (row.child.isShown()) {
		// This row is already open - close it.
		row.child.hide();
		tr.removeClass('shown');
		$(".btn-disc-det b").html('+');
	} else {
		// Open row.
		$(".btn-disc-det b").html('-');
		//row.child('foo').show();
		row.child(discount_detRow(row.data())).show();
		tr.addClass('shown');
	}
});
function discount_detRow (data) {
	console.log(data);
  return '<div class="details-container">'+
      '<table class="details-table">'+ //cellpadding="5" cellspacing="0" border="0" 
          '<tr>'+
              '<td class="title">Person ID:</td>'+
              '<td>'+data.id+'</td>'+
          '</tr>'+
          '<tr>'+
              '<td class="title">Name:</td>'+
              '<td>'+data.first_name + ' ' + data.last_name +'</td>'+
              '<td class="title">Email:</td>'+
              '<td>'+data.email+'</td>'+
          '</tr>'+
          '<tr>'+
              '<td class="title">Country:</td>'+
              '<td>'+data.country+'</td>'+
              '<td class="title">IP Address:</td>'+
              '<td>'+data.ip_address+'</td>'+
          '</tr>'+
      '</table>'+
    '</div>';
};*/
//Discounted Bills - Ends
// GST Bills
function set_gstBills()
{
    var dt_range=($("#dt_range").val()).split('-');
    var branch_name=($('#branch_name').val()!='' && $('#branch_name').val()!=undefined ? $('#branch_name').val() : $("#branch_select option:selected").text());
	var company_name=$('#company_name').val();
	var title="<b><span style='font-size:15pt;margin-left:50%;'>"+company_name+"</span></b><b><span style='font-size:15pt;'></span></b></br>"+"<span style=font-size:13pt;margin-left:25%;>&nbsp;&nbsp;GST Bill"+($("#branch_select").val()!='' ? "Cost Center:"+branch_name+" ":'')+"From&nbsp;:&nbsp;"+dt_range[0]+" &nbsp;&nbsp;To Date&nbsp;:&nbsp;"+dt_range[1]+"&nbsp;&nbsp;"+ getDisplayDateTime() +"</span>";
	$("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
		 url:base_url+ "index.php/admin_ret_reports/gst_bills/ajax?nocache=" + my_Date.getUTCSeconds(),
		 data: ( {'dt_range' :$("#dt_range").val(),'id_branch':($('#branch_filter').val()!='' && $('#branch_filter').val()!=undefined ? $('#branch_filter').val(): $("#branch_select").val()), 'id_category':($('#category').val()!='' && $('#category').val()!=undefined && $('#category').val() != null) ? $('#category').val(): 0}),
		 dataType:"JSON",
		 type:"POST",
		 success:function(data){
		 	var list = data.list;
	 		$("#gst_bill_list > tbody > tr").remove();  
	 		    $('#gst_bill_list').dataTable().fnClearTable();
    		    $('#gst_bill_list').dataTable().fnDestroy();
			if (list!= null)
			{  	
				trHTML = ''; 
				tfootHTML = '';
				total_bill_amount = 0;
				total_bill_tax_value = 0;
				total_bill_sgst = 0;
				total_bill_cgst = 0;
				total_bill_igst = 0;
				$.each(list, function (i, branch) { 
					var bill_amount = 0;
					$.each(branch, function (idx, item) { 
					    var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+item.bill_id;
						trHTML += '<tr>' 
						            +'<td><a href='+url+' target="_blank">'+item.bill_no+'</a></td>'
						            +'<td>'+item.bill_date+'</td>'
									+'<td>'+item.cus_name+'</td>'
									+'<td>'+item.mobile+'</td>'
									+'<td>'+item.gst_number+'</td>'
									+'<td>'+item.pan+'</td>'
									+'<td>'+item.tot_bill_amount+'</td>'
					   			'</tr>';
						bill_amount = parseFloat(bill_amount) + parseFloat(item.tot_bill_amount);
					});	 
			    	/*trHTML += '<tr style="font-weight:bold;">' +
									+'<td></td>'
									+'<td></td>'
									+'<td></td>'
									+'<td></td>'
									+'<td></td>'
									+'<td></td>'
									+'<td>Sub Total</td>'
									+'<td>'+bill_amount+'</td>'
				    		 '</tr>';
				     total_bill_amount = total_bill_amount+bill_amount;*/
				});
				/*trHTML += '<tr style="font-weight:bold;">' +
								+'<td></td>'
								+'<td></td>'
								+'<td></td>'
								+'<td></td>'
								+'<td></td>'
								+'<td></td>'
								+'<td>Grand Total</td>'
				                +'<td>'+parseFloat(total_bill_amount).toFixed(2)+'</td>'
			    		 '</tr>';*/    
			    $('#gst_bill_list > tbody').html(trHTML);
			     // Check and initialise datatable
				if ( ! $.fn.DataTable.isDataTable( '#gst_bill_list' ) ) { 
					oTable = $('#gst_bill_list').dataTable({ 
					"bSort": false, 
					"bInfo": true, 
					"scrollX":'100%',  
					"dom": 'lBfrtip',
					"paging":false,
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
						extend:'excel',
						footer: true,
						title: title,
					}
					], 
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
//GST Bills - Ends
//re-order items
$('#prod_select').on('change',function(){
    if(this.value!='')
    {
        if(ctrl_page[1]!='scan_report' && ctrl_page[1]!='sales_comparision')
        {
             get_Activedesign(this.value);
        }
        
        if(ctrl_page[1]=='tag_items_designwise')
        {
            get_Activesize(this.value);
        }
        
        if(ctrl_page[1]=='reorder_items')
        {
            get_Activesize(this.value);
        }
       
    }
});
function get_Activedesign(id_product)
{
	$('#des_select option').remove();
	$.ajax({
	type: 'POST',
	url: base_url+'index.php/admin_ret_reports/get_Activedesign',
	dataType:'json',
	data :{'id_product':id_product},
	success:function(data){
		var id =  $("#id_design").val();
		$.each(data, function (key, item) {   
		    $("#des_select").append(
		    $("<option></option>")
		    .attr("value", item.design_no)    
		    .text(item.design_name)  
		    );
		});
		   
		$("#des_select").select2(
		{
			placeholder:"Select Design",
			allowClear: true		    
		});
		    $("#des_select").select2("val",(id!='' && id>0?id:''));
		}
	});
}
$('#des_select').on('change',function(){
    if(this.value!='')
    {
        if(ctrl_page[1]=='reorder_items')
        {
            get_ActiveSubDesign();
        }
        
        if(ctrl_page[1]=='weight_range_sales')
        {
            get_ActiveSubDesign();
        }
       
    }
});
function get_ActiveSubDesign()
{
	$('#sub_des_select option').remove();
	$.ajax({
	type: 'POST',
	url: base_url+'index.php/admin_ret_reports/get_ActiveSubDesign',
	dataType:'json',
	data :{'id_product':$('#prod_select').val(),'id_design':$('#des_select').val()},
	success:function(data){
		var id =  $("#id_design").val();
		$.each(data, function (key, item) {   
		    $("#sub_des_select").append(
		    $("<option></option>")
		    .attr("value", item.id_sub_design)    
		    .text(item.sub_design_name)  
		    );
		});
		   
		$("#sub_des_select").select2(
		{
			placeholder:"Select Design",
			allowClear: true		    
		});
		    $("#sub_des_select").select2("val",(id!='' && id>0?id:''));
		}
	});
}
function get_weight_range(id_product)
{
	$('#wt_select option').remove();
	$('#ed_wt_select option').remove();
	$.ajax({
	type: 'POST',
	url: base_url+'index.php/admin_ret_reports/get_weight_range',
	dataType:'json',
	data:{'id_product':id_product},
	success:function(data){
		var id =  $("#wt_range").val();
		$.each(data, function (key, item) {   
		    $("#wt_select").append(
		    $("<option></option>")
		    .attr("value", item.id_weight)    
		    .text(item.weight_description)  
		    );
		});
		   
		$("#wt_select").select2(
		{
			placeholder:"Weight Range",
			allowClear: true		    
		});
		    $("#wt_select").select2("val",(id!='' && id>0?id:''));
		}
	});
}
function set_reorder_items()
{
	var branch_name=$("#branch_select option:selected").text();
	var company_name=$('#company_name').val();
	var title="<b><span style='font-size:15pt;margin-left:50%;'>"+company_name+"</span></b><b><span style='font-size:15pt;'></span></b></br>"+"<span style=font-size:13pt;margin-left:25%;>&nbsp;&nbsp;Re-order Items Report "+($("#branch_select").val()!='' ? "Cost Center:"+branch_name+" ":'')+"</span>";
	
	$("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/reorder_items/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: ({'id_design' :$("#des_select").val(),'id_sub_design' :$("#sub_des_select").val(),'id_product' :$("#prod_select").val(),'id_branch':($('#branch_filter').val()!='' && $('#branch_filter').val()!=undefined ? $('#branch_filter').val(): $("#branch_select").val()),'id_weight':$('#wt_select').val(),'id_size':$('#select_size').val()}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			 	$("div.overlay").css("display", "none"); 
			 	var list = data.list;
				$('#total_count').text(list.length);
				var oTable = $('#reorder_item_list').DataTable();
				oTable.clear().draw();				  
				if (list!= null && list.length > 0)
				{  	
					oTable = $('#reorder_item_list').dataTable({
						"bDestroy": true,
		                "bInfo": true,
		                "bFilter": true,
						"order": [[ 0, "desc" ]],
		                "scrollX":'100%',
		                "bSort": true,
		                "dom": 'lBfrtip',
		                "fixedHeader": true,
		                "paging": true,
		                
			            "autoWidth": true,
		                "buttons": [
								 {
								   extend: 'print',
								   footer: true,
								   title: title,
								   customize: function ( win ) {
										$(win.document.body).find( 'table' )
											.addClass( 'compact' )
											.css( 'font-size', 'inherit' );
									},
								 },
								 {
									extend:'excel',
									footer: true,
								    title: title, 
								  }
								 ],
						"aaData": list,
						"aoColumns": [	
						                
										{ "mDataProp": function ( row, type, val, meta ){
									        var design_name=row.design_name;
									        var product_name=row.product_name;
									        
									        var shortage=0;
											var excess=0;
											var tag_details=row.tag_details;
											if(parseFloat(row.min_pcs)>parseFloat(tag_details.tot_pcs) && (row.min_pcs>0))
											{
												if(tag_details.tot_pcs>0)
												{
												    shortage=parseFloat(row.min_pcs)-parseFloat(tag_details.tot_pcs); 
												}else{
												    shortage=parseFloat(row.min_pcs) 
												}
												
											}
											if(shortage>0 && (row.is_cart==0 && row.is_order==0))
											{
											    return '<input type="checkbox" class="reorder_items" name="reorder_items[]"/><input type="hidden" class="product_name" value='+row.product_name+'><input type="hidden" class="product_id" value='+row.product_id+'><input type="hidden" class="design_id" value='+row.design_id+'><input type="hidden" class="id_sub_design" value='+row.id_sub_design+'><input type="hidden" class="id_branch" value='+row.id_branch+'><input type="hidden" class="id_category" value='+row.id_category+'><input type="hidden" class="id_wt_range" value='+row.id_wt_range+'><input type="hidden" class="max_pcs" value='+row.max_pcs+'><input type="hidden" class="min_pcs" value='+row.min_pcs+'><input type="hidden" class="product_name" value='+product_name+'><input type="hidden" class="design_name" value='+design_name+'><input type="hidden" class="weight_name" value='+row.weight_name+'><input type="hidden" class="is_cart" value='+row.is_cart+'><input type="hidden" class="shortage" value='+shortage+'><input type="hidden" class="size" value='+(row.id_size!=null && row.id_size!='' ? row.id_size :'')+'>'+row.product_name;
											}
											else
											{
											    return '<input type="hidden" class="product_name" value='+row.product_name+'><input type="hidden" class="product_id" value='+row.product_id+'><input type="hidden" class="design_id" value='+row.design_id+'><input type="hidden" class="id_sub_design" value='+row.id_sub_design+'><input type="hidden" class="id_branch" value='+row.id_branch+'><input type="hidden" class="id_category" value='+row.id_category+'><input type="hidden" class="id_wt_range" value='+row.id_wt_range+'><input type="hidden" class="max_pcs" value='+row.max_pcs+'><input type="hidden" class="min_pcs" value='+row.min_pcs+'><input type="hidden" class="product_name" value='+product_name+'><input type="hidden" class="design_name" value='+design_name+'><input type="hidden" class="weight_name" value='+row.weight_name+'><input type="hidden" class="is_cart" value='+row.is_cart+'>'+row.product_name;
											}
										},
										},
										{ "mDataProp": "design_name" },
										{ "mDataProp": "sub_design_name" },
										{ "mDataProp": "size" },
										{ "mDataProp": "weight_name" },
										{ "mDataProp": function ( row, type, val, meta ){
											var tag_details=row.tag_details;
											return tag_details.gross_wt;
										},
										},
										
									    { "mDataProp": function ( row, type, val, meta ){
											var tag_details=row.tag_details;
											return tag_details.net_wt;
										},
										},
										{ "mDataProp": function ( row, type, val, meta ){
											return row.min_pcs+'/'+row.max_pcs;
										},
										},
										
										{ "mDataProp": function ( row, type, val, meta ){
											var tag_details=row.tag_details;
											return tag_details.tot_pcs;
										},
										},
										
										{ "mDataProp": function ( row, type, val, meta ){
											var shortage=0;
											var excess=0;
											var tag_details=row.tag_details;
										
										
											if(parseFloat(row.min_pcs)>parseFloat(tag_details.tot_pcs) && (row.min_pcs>0))
											{
												if(tag_details.tot_pcs>0)
												{
												    shortage=parseFloat(row.min_pcs)-parseFloat(tag_details.tot_pcs); 
												}else{
												    shortage=parseFloat(row.min_pcs) 
												}
												
											}
											if(shortage>0)
											{
											    return '<a href="" data-toggle="modal" data-toggle="tooltip" title="Click Here to Add Cart" onclick="create_new_order($(this).closest(\'tr\'));" ><span class="badge bg-red">'+shortage+'</span></a>';
											}else{
											    return '<span class="badge bg-red">'+shortage+'</span>';
											}
										
										},
										},
										
										{ "mDataProp": function ( row, type, val, meta ){
											var excess=0;
											var product_id=row.product_id;
											var design_id=row.design_id;
											var id_branch=row.id_branch;
											var id_category=row.id_category;
											var tag_details=row.tag_details;
											create_order= '';
											if(parseFloat(tag_details.tot_pcs)>parseFloat(row.max_pcs))
											{
												excess=parseFloat(row.max_pcs)-parseFloat(tag_details.tot_pcs); 
												excess=excess<0 ? parseFloat(excess*-1) :excess;
											}
											return '<span class="badge bg-green">'+excess+'</span>';
										},
										},
										
										{ "mDataProp": function ( row, type, val, meta ){
											if(row.is_cart>0 || row.is_order>0)
											{
											    var cart_url = base_url+'index.php/admin_ret_order/cart/list/';
											    var order_url = base_url+'index.php/admin_ret_purchase/purchase/order_status';
												return (row.is_cart>0 ?'<a href='+cart_url+' target="_blank" data-toggle="tooltip" title="In Cart"><i class="fa fa-shopping-cart" aria-hidden="true" style="margin-left: 23%;"></i><span class="badge bg-green" ></span></a> / ' :'')+(row.is_order>0 ? ' <a href='+order_url+' target="_blank" data-toggle="tooltip" title="Order Placed"><span class="badge bg-green"><i class="fa fa-cart-plus" aria-hidden="true"></i></span></a>' :'');
											}else{
											    return '';
											}
											
										
										},
										},
										
									],
					"footerCallback": function( row, data, start, end, display ){
						if(data.length>0){
							var api = this.api(), data;
							for( var i=0; i<=data.length-1;i++){
								var intVal = function ( i ) {
									return typeof i === 'string' ?
									i.replace(/[\$,]/g, '')*1 :
									typeof i === 'number' ?
									i : 0;
								};	
								
								$(api.column(0).footer() ).html('Total');	
								pieces = api
								.column(9)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(9).footer()).html(parseFloat(pieces).toFixed(2));
								
								shortage = api
								.column(10)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(10).footer()).html(parseFloat(shortage).toFixed(2));
								
								excess = api
								.column(11)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(11).footer()).html(parseFloat(excess).toFixed(2));
								
								gross_wgt = api
								.column(5)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(5).footer()).html(parseFloat(gross_wgt).toFixed(2));
								
								net_wgt = api
								.column(6)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(6).footer()).html(parseFloat(net_wgt).toFixed(2)); 
						} 
						}else{
							 var api = this.api(), data; 
							 $(api.column(9).footer()).html('');  
							 $(api.column(5).footer()).html('');  
							 $(api.column(6).footer()).html('');  
							 $(api.column(10).footer()).html('');  
							 $(api.column(11).footer()).html('');  
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


function set_branchreorder_items()
{
	var company_name=$('#company_name').val();
	var title="<b><span style='font-size:15pt;margin-left:50%;'>"+company_name+"</span></b><b><span style='font-size:15pt;'></span></b></br>"+"<span style=font-size:13pt;margin-left:25%;>&nbsp;&nbsp;Re-order Items Report </span>";
	
	$("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/branchreorder_items/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: ({'id_design' :$("#des_select").val(),'id_sub_design' :$("#sub_des_select").val(),'id_product' :$("#prod_select").val(),'id_weight':$('#wt_select').val(),'id_size':$('#select_size').val()}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			 	$("div.overlay").css("display", "none"); 
			 	var list = data.list;
				$('#total_count').text(list.length);
				var oTable = $('#reorder_item_list').DataTable();
				oTable.clear().draw();				  
				if (list!= null && list.length > 0)
				{  	
					oTable = $('#reorder_item_list').dataTable({
						"bDestroy": true,
		                "bInfo": true,
		                "bFilter": true,
						"order": [[ 0, "desc" ]],
		                "scrollX":'100%',
		                "bSort": true,
		                "dom": 'lBfrtip',
		                "fixedHeader": true,
		                "paging": true,
		                
			            "autoWidth": true,
		                "buttons": [
								 {
								   extend: 'print',
								   footer: true,
								   title: title,
								   customize: function ( win ) {
										$(win.document.body).find( 'table' )
											.addClass( 'compact' )
											.css( 'font-size', 'inherit' );
									},
								 },
								 {
									extend:'excel',
									footer: true,
								    title: title, 
								  }
								 ],
						"aaData": list,
						"aoColumns": [	
										{ "mDataProp": "product_name" },
										{ "mDataProp": "design_name" },
										{ "mDataProp": "sub_design_name" },
										{ "mDataProp": "size" },
										{ "mDataProp": "weight_name" },
										{ "mDataProp": function ( row, type, val, meta ){
											return row.Btotgrswt;
										},
										},
										
									    { "mDataProp": function ( row, type, val, meta ){
											
											return row.Btotnetwt;
										},
										},
										{ "mDataProp": function ( row, type, val, meta ){
											return row.Bminmax;
										},
										},
										
										{ "mDataProp": function ( row, type, val, meta ){
											return row.Btotpcs;
										},
										},
										{ "mDataProp": function ( row, type, val, meta ){
											
											if(row.Bshortage>0)
											{
											    return '<a href="" data-toggle="modal" data-toggle="tooltip" title="Click Here to Add Cart" onclick="create_new_branch_order($(this).closest(\'tr\'));" ><span class="badge bg-red">'+row.Bshortage+'</span></a>';
											}else{
											    return '<span class="badge bg-green">'+row.Bexcess+'</span>';
											}
										
										},
										},
										{ "mDataProp": function ( row, type, val, meta ){
											return row.Ctotgrswt;
										},
										},
										
									    { "mDataProp": function ( row, type, val, meta ){
											
											return row.Ctotnetwt;
										},
										},
										{ "mDataProp": function ( row, type, val, meta ){
											return row.Cminmax;
										},
										},
										
										{ "mDataProp": function ( row, type, val, meta ){
											return row.Ctotpcs;
										},
										},
										{ "mDataProp": function ( row, type, val, meta ){
											
											if(row.Cshortage>0)
											{
											    return '<a href="" data-toggle="modal" data-toggle="tooltip" title="Click Here to Add Cart" onclick="create_new_branch_order($(this).closest(\'tr\'));" ><span class="badge bg-red">'+row.Cshortage+'</span></a>';
											}else{
											    return '<span class="badge bg-green">'+row.Cexcess+'</span>';
											}
										
										},
										},
										
										{ "mDataProp": function ( row, type, val, meta ){
											return row.Dtotgrswt;
										},
										},
										
									    { "mDataProp": function ( row, type, val, meta ){
											
											return row.Dtotnetwt;
										},
										},
										{ "mDataProp": function ( row, type, val, meta ){
											return row.Dminmax;
										},
										},
										
										{ "mDataProp": function ( row, type, val, meta ){
											return row.Dtotpcs;
										},
										},
										{ "mDataProp": function ( row, type, val, meta ){
											
											if(row.Dshortage>0)
											{
											    return '<a href="" data-toggle="modal" data-toggle="tooltip" title="Click Here to Add Cart" onclick="create_new_branch_order($(this).closest(\'tr\'));" ><span class="badge bg-red">'+row.Dshortage+'</span></a>';
											}else{
											    return '<span class="badge bg-green">'+row.Dexcess+'</span>';
											}
										
										},
										},
										
										{ "mDataProp": function ( row, type, val, meta ){
											return row.Etotgrswt;
										},
										},
										
									    { "mDataProp": function ( row, type, val, meta ){
											
											return row.Etotnetwt;
										},
										},
										{ "mDataProp": function ( row, type, val, meta ){
											return row.Eminmax;
										},
										},
										
										{ "mDataProp": function ( row, type, val, meta ){
											return row.Etotpcs;
										},
										},
										{ "mDataProp": function ( row, type, val, meta ){
											
											if(row.Eshortage>0)
											{
											    return '<a href="" data-toggle="modal" data-toggle="tooltip" title="Click Here to Add Cart" onclick="create_new_branch_order($(this).closest(\'tr\'));" ><span class="badge bg-red">'+row.Eshortage+'</span></a>';
											}else{
											    return '<span class="badge bg-green">'+row.Eexcess+'</span>';
											}
										
										},
										},
										
									],
					"footerCallback": function( row, data, start, end, display ){
						if(data.length>0){
							var api = this.api(), data;
							for( var i=0; i<=data.length-1;i++){
								var intVal = function ( i ) {
									return typeof i === 'string' ?
									i.replace(/[\$,]/g, '')*1 :
									typeof i === 'number' ?
									i : 0;
								};	
								
								$(api.column(0).footer() ).html('Total');	
								pieces = api
								.column(9)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(9).footer()).html(parseFloat(pieces).toFixed(2));
								
								shortage = api
								.column(10)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(10).footer()).html(parseFloat(shortage).toFixed(2));
								
								excess = api
								.column(11)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(11).footer()).html(parseFloat(excess).toFixed(2));
								
								gross_wgt = api
								.column(5)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(5).footer()).html(parseFloat(gross_wgt).toFixed(2));
								
								net_wgt = api
								.column(6)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(6).footer()).html(parseFloat(net_wgt).toFixed(2)); 
						} 
						}else{
							 var api = this.api(), data; 
							 $(api.column(9).footer()).html('');  
							 $(api.column(5).footer()).html('');  
							 $(api.column(6).footer()).html('');  
							 $(api.column(10).footer()).html('');  
							 $(api.column(11).footer()).html('');  
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
$("#add_to_cart").click(function(){
	if($("input[name='reorder_items[]']:checked").val())
	{
		var selected = [];
		$("#reorder_item_list tbody tr").each(function(index, value){
		if($(value).find("input[name='reorder_items[]']:checked").is(":checked"))
		{
		    curRow = $(this);   
    		transData = { 
    	        'product_id'    : $(value).find(".product_id").val(),
    	        'design_id'     : $(value).find(".design_id").val(),
    	        'id_sub_design' : $(value).find(".id_sub_design").val(),
    	        'id_wt_range'   : $(value).find(".id_wt_range").val(),
    	        'shortage_pcs'  : $(value).find(".shortage").val(),
    	        'id_size'       : $(value).find(".size").val(),
		    }
		    selected.push(transData);
		}
		});
		req_data = selected;
		add_to_cart(req_data);
	}
	else{
		alert('Please Select Atleast One Row');
	}
});
function add_to_cart(req_data)
{
    $("div.overlay").css("display", "none"); 
    var url=base_url+ "index.php/admin_ret_order/add_to_cart?nocache=" + my_Date.getUTCSeconds();
    $.ajax({ 
    url:url,
    data: {'req_data':req_data,'id_branch':$('#branch_select').val()},
    type:"POST",
    dataType:"JSON",
    success:function(data)
    {
        if(data.status)
        {
           $.toaster({ priority : 'success', title : 'Warning!', message : ''+"</br>"+data.message});
           $("div.overlay").css("display", "none");                    
        }
        else
        {
            $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+data.message});
            $("div.overlay").css("display", "none"); 
        }
         set_reorder_items();
        
    },
    error:function(error)  
    {	
        $("div.overlay").css("display", "none"); 
    } 
    });
}
 
 $('#create_order').on('click',function(){
     
     if(validateOrderDetailRow())
     {
         var form_data=$('#order_cart').serialize();
         
         var url=base_url+ "index.php/admin_ret_order/cart/save?nocache=" + my_Date.getUTCSeconds();
                $.ajax({ 
                url:url,
                data: form_data,
                type:"POST",
                dataType:"JSON",
                success:function(data)
                {
                    if(data.status)
                    {
                    	$('#success_res').css("display", "block");
                       	$('#success_msg').html(data.msg);
                        setTimeout(function() { 
                       		$("#success-alert").hide();
                       		$('#success_msg').html('');
                       		$("#success-alert").show();
                       		$('#success_res').css("display", "none");
                   		}, 1000);                       
                    }else{
                        $('#failed_msg').html(data.msg);
                        $('#success_res').css("display", "none")
                    }
                    set_reorder_items();
                    $("div.overlay").css("display", "none"); 
                },
                error:function(error)  
                {	
                    $("div.overlay").css("display", "none"); 
                } 
                });
                $('#confirm-add').modal('toggle');
         
     }
 });
 
 function validateOrderDetailRow()
{
    var validate = true;
	$('#item_detail > tbody  > tr').each(function(index, tr) 
	{
		if($(this).find('.product').val() == "" || $(this).find('.design').val() == ""  || $(this).find('.qty').val() == "" || ($(this).find('.qty').val() ==0)  || $(this).find('.id_wt_range').val() == ""  || $(this).find('.purity_sel').val() == "" || $(this).find('.purity_sel').val() == null){
			validate = false;
		}
		if($(this).find('.qty').val()=='' || $(this).find('.qty').val()==0)
		{
		    msg='<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong>Please Enter Total Pieces</div>';
	        $('#chit_alert').html(msg);
		}
		else if($(this).find('.karigar').val() == "" || $(this).find('.karigar').val() == null)
		{
		     msg='<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong>Please Select Karigar</div>';
	        $('#chit_alert').html(msg);
		}
		else if($(this).find('.purity_sel').val() == "" || $(this).find('.purity_sel').val() == null)
		{
		     msg='<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong>Please Select Purity</div>';
	        $('#chit_alert').html(msg);
		}
	});
	return validate;
}
//re-order items
//tag items -design wise
function get_ActiveProduct()
{
	$('#prod_select option').remove();
	$('#prod_filter option').remove();
	$.ajax({
	url: base_url+'index.php/admin_ret_reports/get_ActiveProduct',
	data: ( { 'id_category' : $('#category').length > 0 ? $('#category').val() : 0 }),
	dataType:"JSON",
	 type:"POST",
	success:function(data){
		var id =  $("#id_design").val();
		$.each(data, function (key, item) {   
		    $("#prod_select,#prod_filter").append(
		    $("<option></option>")
		    .attr("value", item.pro_id)    
		    .text(item.product_name)  
		    );
		});
		   
		$("#prod_select,#prod_filter").select2(
		{
			placeholder:"Select Product",
			allowClear: true		    
		});
		    $("#prod_select").select2("val",(id!='' && id>0?id:''));
		    if($("#prod_filter").length)
		    {
		        $("#prod_filter").select2("val",(id!='' && id>0?id:''));
		    }
		    
		    
		}
	});
}
$('#category').on('change',function(){
    get_ActiveProduct();
});
$('#prod_select').on('change',function(){
    if(this.value!='')
    {
        if(ctrl_page[1]!='scan_report' && ctrl_page[1]!='sales_comparision')
        {
            get_weight_range(this.value);
        }
        
    }
    
});

function get_ActiveCollection()
{
	$('#select_collection option').remove();  
	$("div.overlay").css("display", "block"); 
	$.ajax({
	type: 'GET',
	url: base_url+'index.php/admin_ret_tagging/get_ActiveCollection',
	dataType:'json',
	success:function(data){
		var id =  $("#select_collection").val();
		$.each(data, function (key, item) {   
		    $("#select_collection").append(
		    $("<option></option>")
		    .attr("value", item.id_collection)    
		    .text(item.collection_name)  
		    );
		});
	
		if($("#select_collection").length)
		{
		    $("#select_collection").select2("val",(id!='' && id>0?id:''));
		}
		    
		}
	});
	$("div.overlay").css("display", "none"); 
}

function set_tagged_items()
{
	$("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/tag_items_designwise/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'id_category':$('#category').val(), 'id_product':$('#prod_select').val(),'id_design' :$("#des_select").val(),'id_branch':($('#branch_filter').val()!='' && $('#branch_filter').val()!=undefined ? $('#branch_filter').val(): $("#branch_select").val()),'from_weight':$('#from_weight').val(),'to_weight':$('#to_weight').val(),'id_size':$('#select_size').val(),'id_metal':$('#metal').val(),'id_collection':$('#select_collection').val(),'id_karigar':$('#karigar').val()}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			 	$("div.overlay").css("display", "none"); 
			 	var list = data.list;
			 	export_list    = [];
				export_list    = list;	
			 	var stock_details = data.stock_details;
			 	var tot_weight=0;
			 	var tot_piece=0;
			 	$.each(list,function(key,item){
			 	    tot_piece+=parseFloat(item.piece);
			 	    tot_weight+=parseFloat(item.gross_wt);
			 	})
				$('#total_count').text(tot_piece);
				$('#total_wt').text(parseFloat(tot_weight).toFixed(3));
				
				$("#tag_item_branchwise > tbody > tr").remove();
				if((stock_details.length>0) && ($("#branch_select").val()==0))
				{
				    var trHtml='';
				    var tot_pcs=0;
				    var tot_gross_wt=0;
				    var tot_net_wt=0;
				    $.each(stock_details,function(key,items){
				        tot_pcs+=parseFloat(items.piece);
				        tot_gross_wt+=parseFloat(items.gross_wt);
				        tot_net_wt+=parseFloat(items.gross_wt);
				        trHtml+='<tr>'
				                    +'<td>'+items.branch_name+'</td>'
				                    +'<td>'+items.piece+'</td>'
				                    +'<td>'+items.gross_wt+'</td>'
				                    +'<td>'+items.net_wt+'</td>'
				                +'</tr>';
				    });
				    trHtml+='<tr style="font-weight:bold;">'
			                    +'<td>TOTAL</td>'
			                    +'<td>'+parseFloat(tot_pcs).toFixed(2)+'</td>'
			                    +'<td>'+parseFloat(tot_gross_wt).toFixed(2)+'</td>'
			                    +'<td>'+parseFloat(tot_net_wt).toFixed(2)+'</td>'
			                +'</tr>';
				    $('#tag_item_branchwise').append(trHtml);
				}
				
				var oTable = $('#tag_items_list').DataTable();
				oTable.clear().draw();				  
				if (list!= null && list.length > 0)
				{  	
					oTable = $('#tag_items_list').dataTable({
						"bDestroy": true,
		                "bInfo": true,
		                "bFilter": true,
						"order": [[ 0, "desc" ]],
		                "scrollX":'100%',
		                "bSort": true,
		                "dom": 'lBfrtip',
		                "buttons": [
        			
        					{
        						extend: 'print',
        						footer: true,
        						title: '',
        						messageTop: 'Design Wise Tag Items',
        						exportOptions: {
                                    columns: ':visible'
                                },
        						customize: function ( win ) {
        						$(win.document.body).find( 'table' )
        						.addClass( 'compact' )
        				    	.css('font-size','12px')
        						.css('font-family','sans-serif');
        						},
        					},
        					'colvis',
        					
        					{
        						extend:'excel',
        						footer: true,
        						title: 'Design Wise Tag Items',
        					}
        				],
						"aaData": list,
						"aoColumns": [	
										/*{ "mDataProp": "tag_lot_id" },*/
										/*{
                                            "className":      'dt-control',
                                            "orderable":      false,
                                            "data":           "tag_lot_id",
                                            "defaultContent": '<span class="drill-val"><i class="fa fa-chevron-circle-down text-teal"></i></span>',
                                            
                                        },*/
                                        { 
                                            "className":      'dt-control',
                                            "mDataProp": function ( row, type, val, meta )
                                            { 
                                                if(row.stone_details.length > 0){
                                                    return '<span class="drill-val"><i class="fa fa-chevron-circle-down text-teal"></i>'+row.tag_lot_id+'</span>';
                                                }else{
                                                    return '<span class="badge bg-red">'+row.tag_lot_id+'</span>';
                                                }
                                            }
                                            
                                        },
										{ "mDataProp": "karigarname" },
										{ "mDataProp": "tag_code" },
										{ "mDataProp": "old_tag_id" },
										{ "mDataProp": "tag_date" },
										{ "mDataProp": "branch_name" },
										{ "mDataProp": "catname" },
										{ "mDataProp": "product_name" },
										{ "mDataProp": "design_name" },
										{ "mDataProp": "sub_design_name" },
										{ "mDataProp": "collection_name" },
										{ "mDataProp": "piece" },
										{ "mDataProp": "gross_wt" },
										{ "mDataProp": "net_wt" },
										{ "mDataProp": "size_name" },
										{ "mDataProp": "retail_max_wastage_percent" },
										{ "mDataProp": function ( row, type, val, meta ){
											if(row.tag_mc_type==2)
											{
												return row.tag_mc_value;
											}else{
												return '-';
											}
											
										},
										},
										{ "mDataProp": function ( row, type, val, meta ){
										    if(row.tag_mc_type==1)
											{
												return row.tag_mc_value;
											}else{
												return '-';
											}
											
										},
										},
										{ "mDataProp": "sales_value" },
									
										
										{ "mDataProp": "attrvalues" },
										{ "mDataProp": "cert_no" },
										{ "mDataProp": "style_code" },
									
                                        { "mDataProp": function ( row, type, val, meta )
                                        { 
                                            return '<span class="badge bg-red">'+row.tot_est+'</span>';
                                        }},
									],
					});	
					
					    var anOpen =[]; 
                    		$(document).on('click',"#tag_items_list .dt-control", function(){ 
                    		   var nTr = this.parentNode;
                    		   var i = $.inArray( nTr, anOpen );
                    		 
                    		   if ( i === -1 ) { 
                    				//$('.drill-val', this).html('<i class="fa fa-chevron-circle-up text-teal"></i>'); 
                    				$('.drill-val i', this).toggleClass("fa-chevron-circle-down fa-chevron-circle-up");
                    				
                    				oTable.fnOpen( nTr, formatstoneDetails(oTable, nTr), 'details' );
                    				anOpen.push( nTr ); 
                    		    }
                    		    else { 
                    				//$('.drill-val', this).html('<i class="fa fa-chevron-circle-down text-teal"></i>');
                    				$('.drill-val i', this).toggleClass("fa-chevron-circle-up fa-chevron-circle-down");
                    				oTable.fnClose( nTr );
                    				anOpen.splice( i, 1 );
                    		    }
                    		} );
				}
			  },
			  error:function(error)  
			  {
				 $("div.overlay").css("display", "none"); 
			  }	 
	      });
}
function formatstoneDetails(oTable, nTr)
{
    var oData = oTable.fnGetData( nTr );
    var rowDetail = '';
    var prodTable = 
     '<div class="innerDetails">'+
      '<table class="table table-responsive table-bordered text-center table-sm">'+ 
        '<tr class="bg-teal">'+
         '<th>Is Less Wt</th>'+ 
         '<th>Stone</th>'+
         '<th>Pcs</th>'+
         '<th>Weight</th>'+
         '<th>Unit</th>'+
         '<th>Rate</th>'+
         '<th>Amount</th>'+
        '</tr>';
    var st_details = oData.stone_details; 
 
    $.each(st_details, function (idx, val) {
        var is_lesswt = val.is_apply_in_lwt == 1 ? "Yes" : "No";
    	prodTable += 
        '<tr class="prod_det_btn">'+
        '<td>'+is_lesswt+'</td>'+
        '<td>'+val.stone_name+'</td>'+
        '<td>'+val.pieces+'</td>'+
        '<td>'+val.wt+'</td>'+
        '<td>'+val.uom_name+'</td>'+
        '<td>'+val.rate_per_gram+'</td>'+
        '<td>'+val.price+'</td>'+
        '</tr>'; 
    }); 
 
        
  rowDetail = prodTable+'</table></div>';
  return rowDetail;
}
function formatInnerDatastoneDetails(oData)
{
    var st_details = oData.stone_details; 
    var rowDetail = '';
    var prodTable = 
     '<div class="innerDetails" style="margin-bottom: 20px;border-bottom: 2px solid #39cccc;">'+
      '<table class="table table-responsive table-bordered text-center table-sm">';
        /*+'<tr class="bg-teal">'+
         '<th>Is Less Wt</th>'+ 
         '<th>Stone</th>'+
         '<th>Pcs</th>'+
         '<th>Weight</th>'+
         '<th>Unit</th>'+
         '<th>Rate</th>'+
         '<th>Amount</th>'+
        '</tr>';*/
   
 
    $.each(st_details, function (idx, val) {
        var is_lesswt = val.is_apply_in_lwt == 1 ? "Yes" : "No";
    	prodTable += 
        '<tr class="prod_det_btn">'+
        '<td colspan="20"></td>'+
        '<td>'+val.stone_name+'</td>'+
        '<td>'+is_lesswt+'</td>'+
        '<td>'+val.pieces+'</td>'+
        '<td>'+val.wt+'</td>'+
        '<td>'+val.uom_name+'</td>'+
        '<td>'+val.rate_per_gram+'</td>'+
        '<td>'+val.price+'</td>'+
        '</tr>'; 
    }); 
 
        
  rowDetail = prodTable+'</table></div>';
  return rowDetail;
}
function fntaggedItemsExcelReport(export_type)
{
	if(export_list.length >= 1) {
		   var htmls = "";
		  	htmls +='<table class="table table-bordered table-striped text-center"><thead><tr class="bg-teal"><th colspan=26 style="background-color:#39cccc;text-align:center;"> Design-wise Tag Items</th></tr>'
		  	        +'<tr><th width="10%;">Lot No</th><th width="10%;">Karigar</th>'
					+'<th width="10%;">Tag No</th>'
					+'<th width="10%;">Karigar</th>'
					+'<th width="10%;">Tag Date</th>'
					+'<th width="10%;">Branch</th>'
					+'<th width="10%;">Cagtegory</th>'
					+'<th width="10%;">Product</th>'
					+'<th width="10%;">Design</th>'
					+'<th width="10%;">Sub Design</th>'
					+'<th width="10%;">Pcs</th>'
					+'<th width="10%;">Gross Wgt</th>'
					+'<th width="10%;">Net Wgt</th>'
					+'<th width="1%">Size/Length</th>'
				    +'<th width="10%;">Wastage</th>'
					+'<th width="10%;">Mc Type </th>'
				    +'<th width="10%;">Mc Val </th>'
				    +'<th width="10%;">Cost</th>'
					+'<th width="5%;">Attributes</th>'
					+'<th width="5%;">Remark</th>'
					+'<th width="10%;">Tot Est</th>'
					+'<th width="5%;">St Name</th>'
					+'<th width="5%;">Is Less</th>'
					+'<th width="5%;">St Pcs</th>'
					+'<th width="5%;">St Weight</th>'
					+'<th width="5%;">UOM</th>'
					+'<th width="5%;">Rate</th>'
					+'<th width="5%;">St Amount</th>'
					+'</tr></thead><tbody>';
			var textRange; var j=0;
			$.each(export_list, function (index, val) {
				var datas           = "";
				htmls +='<div class="innerDetails" style="margin-bottom: 20px;border-bottom: 2px solid #39cccc;"><table class="table table-responsive table-bordered text-center table-sm"><tr class="prod_det_btn"><td style="width: 8%;color:red;">'+val.tag_lot_id+'</td><td style="width: 8%;">'+val.karigarname+'</td><td style="width: 8%;">'+val.tag_code+'</td><td style="width: 8%;">'+val.tag_date+'</td><td style="width: 8%;">'+val.branch_name+'</td><td style="width: 8%;">'+val.catname+'</td><td style="width: 8%;">'+val.product_name+'</td><td style="width: 8%;">'+val.design_name+'</td><td style="width: 8%;">'+val.sub_design_name+'</td><td style="width: 8%;">'+val.piece+'</td><td style="width: 8%;">'+val.gross_wt+'</td><td style="width: 8%;">'+val.net_wt+'</td><td style="width: 8%;">'+val.size_name+'</td><td style="width: 8%;">'+val.retail_max_wastage_percent+'</td><td style="width: 8%;">'+val.mc_type+'</td><td style="width: 8%;">'+val.tag_mc_value+'</td><td style="width: 8%;">'+val.sales_value+'</td><td style="width: 10%;color:red;">'+val.attrvalues+'</td><td style="width: 10%;color:red;">'+val.remarks+'</td><td style="width: 10%;color:red;">'+val.tot_est+'</td><td style="width: 10%;color:red;"></td><td style="width: 10%;color:red;"></td><td style="width: 10%;color:red;"></td><td style="width: 10%;color:red;"></td><td style="width: 10%;color:red;"><td style="width: 10%;color:red;"><td style="width: 10%;color:red;"></tr>';
				
				if(val.stone_details.length >0 && export_type == '1')
				{
						htmls += formatInnerDatastoneDetails(val)+'</table></div>';
				}
			});
		  
		  htmls+='</tbody><tfoot></tfoot></table></div>';
		  
		  if(export_type == '1') {
			var uri = 'data:application/vnd.ms-excel;base64,';
            var template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'; 
            var base64 = function(s) {
                return window.btoa(unescape(encodeURIComponent(s)))
            };
            var format = function(s, c) {
                return s.replace(/{(\w+)}/g, function(m, p) {
                    return c[p];
                })
            };
			   var ctx = {
                worksheet : 'Worksheet',
                table : htmls
            }
            var link = document.createElement("a");
            link.download = "Design_Wise_Tagged_Items.xls";
            link.href = uri + base64(format(template, ctx));
            link.click();
		}
	 }
}
function exportTableToCSV($table, filename) {
 
     //rescato los títulos y las filas
     var $Tabla_Nueva = $table.find('tr:has(td,th)');
     // elimino la tabla interior.
     var Tabla_Nueva2= $Tabla_Nueva.filter(function() {
          return (this.childElementCount != 1 );
     });
 
     var $rows = Tabla_Nueva2,
         // Temporary delimiter characters unlikely to be typed by keyboard
         // This is to avoid accidentally splitting the actual contents
         tmpColDelim = String.fromCharCode(11), // vertical tab character
         tmpRowDelim = String.fromCharCode(0), // null character
 
         // Solo Dios Sabe por que puse esta linea
         colDelim = (filename.indexOf("xls") !=-1)? '"\t"': '","',
         rowDelim = '"\r\n"',
 
 
         // Grab text from table into CSV formatted string
         csv = '"' + $rows.map(function (i, row) {
             var $row = $(row);
             var   $cols = $row.find('td:not(.hidden),th:not(.hidden)');
 
             return $cols.map(function (j, col) {
                 var $col = $(col);
                 var text = $col.text().replace(/\./g, '');
                 return text.replace('"', '""'); // escape double quotes
 
             }).get().join(tmpColDelim);
             csv =csv +'"\r\n"' +'fin '+'"\r\n"';
         }).get().join(tmpRowDelim)
             .split(tmpRowDelim).join(rowDelim)
             .split(tmpColDelim).join(colDelim) + '"';
 
 
      download_csv(csv, filename);
 
 
 }
 
 
 
function download_csv(csv, filename) {
     var csvFile;
     var downloadLink;
 
     // CSV FILE
     csvFile = new Blob([csv], {type: "text/csv"});
 
     // Download link
     downloadLink = document.createElement("a");
 
     // File name
     downloadLink.download = filename;
 
     // We have to create a link to the file
     downloadLink.href = window.URL.createObjectURL(csvFile);
 
     // Make sure that the link is not displayed
     downloadLink.style.display = "none";
 
     // Add the link to your DOM
     document.body.appendChild(downloadLink);
 
     // Lanzamos
     downloadLink.click();
 }
//tag items -design wise
//stock report
function stock_report()
{
    var title='';
	$("div.overlay").css("display", "block"); 
	var dt_range=($("#dt_range").val()).split('-');
    var branch_name=($('#branch_name').val()!='' && $('#branch_name').val()!=undefined ? $('#branch_name').val() : $("#branch_select option:selected").text());
	var company_name=$('#company_name').val();
	title="<b><span style='font-size:15pt;margin-left:40%;'>"+company_name+"</span></b><b><span style='font-size:15pt;'></span></b></br>"+"<span style=font-size:13pt;margin-left:25%;>&nbsp;&nbsp;Stock Report "+($("#branch_select").val()!='' ? "Cost Center:"+branch_name+" ":'')+"From&nbsp;:&nbsp;"+dt_range[0]+" &nbsp;&nbsp;To Date&nbsp;:&nbsp;"+dt_range[1]+"</span>";
	my_Date = new Date();
	$.ajax({
		 url:base_url+ "index.php/admin_ret_reports/stock_report/ajax?nocache=" + my_Date.getUTCSeconds(),
		 data: ( {'dt_range' :$("#dt_range").val(),'id_branch':($('#branch_filter').val()!='' && $('#branch_filter').val()!=undefined ? $('#branch_filter').val(): $("#branch_select").val()),'id_product':$('#prod_select').val(),'id_metal':$('#metal').val(),'id_category':$('#category').val()}),
		 dataType:"JSON",
		 type:"POST",
		 success:function(data){
		 	var list = data.list;
		 	var non_tag_items = data.non_tag_items;
		 	
	 		$("#stock_list > tbody > tr").remove();
	 		$('#stock_list').dataTable().fnClearTable();
    		$('#stock_list').dataTable().fnDestroy();	
    		
			if (list!= null)
			{  	
				trHTML = ''; 
				tfootHTML = '';
				total_bill_amount = 0;
				total_bill_tax_value = 0;
				
				    var total_op_blc_pcss = 0;
					var total_op_blc_gwt = 0;
					var total_op_blc_nwt = 0;
					
					var total_inw_pcss = 0;
					var total_inw_gwt = 0;
					var total_inw_nwt = 0;
					
					var total_closing_inw_pcs=0;
					var total_closing_inw_gwt=0;
					var total_closing_net_wt=0;
					
				$.each(list, function (i, metal) { 
				    
				    var metal_op_blc_pcss = 0;
					var metal_op_blc_gwt = 0;
					var metal_op_blc_nwt = 0;
					
					var metal_inw_pcss = 0;
					var metal_inw_gwt = 0;
					var metal_inw_nwt = 0;
					
					var metal_closing_inw_pcs=0;
					var metal_closing_inw_gwt=0;
					var metal_closing_net_wt=0;
					
				    $.each(metal, function (i, category) { 
					var op_blc_pcss = 0;
					var op_blc_gwt = 0;
					var op_blc_nwt = 0;
					
					var inw_pcss = 0;
					var inw_gwt = 0;
					var inw_nwt = 0;
					
					var sold_pcs = 0;
					var sold_gwt = 0;
					var sold_nwt = 0;
					
					var other_s_pcs = 0;
					var other_s_gwt = 0;
					var other_s_nwt = 0;
					
					var closing_inw_pcs=0;
					var closing_inw_gwt=0;
					var closing_net_wt=0;
                    	trHTML += '<tr>' 
									+'<td style="text-align:left;" colspan="2"><strong>'+i+'</strong></td>'
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
					   			'</tr>';
			 					 		 
					$.each(category, function (idx, item) { 
						
						op_blc_pcss+=parseFloat(item.op_blc_pcs);
						op_blc_gwt+=parseFloat(item.op_blc_gwt);
						op_blc_nwt+=parseFloat(item.op_blc_nwt);
						
						//Product Inward Details
						inw_pcss+=parseFloat(item.inw_pcs);
						inw_gwt+=parseFloat(item.inw_gwt);
						inw_nwt+=parseFloat(item.inw_nwt);
						//Product Inward Details
						
						
						//Product SOld Details
						sold_pcs+=parseFloat(item.sold_pcs);
						sold_gwt+=parseFloat(item.sold_gwt);
						sold_nwt+=parseFloat(item.sold_nwt);
						//Product SOld Details
						
						//Other Outward Details
						other_s_pcs+=parseFloat(item.br_out_pcs);
						other_s_gwt+=parseFloat(item.br_out_gwt);
						other_s_nwt+=parseFloat(item.br_out_nwt);
						//Other Outward Details
						
						//Product closing Details
						var closing_pcs=parseFloat(item.op_blc_pcs)+parseFloat(item.inw_pcs)-parseFloat(parseFloat(item.sold_pcs)+parseFloat(item.br_out_pcs));
						var closing_gwt=parseFloat(parseFloat(item.op_blc_gwt)+parseFloat(item.inw_gwt)-parseFloat(item.sold_gwt)-parseFloat(item.br_out_gwt)).toFixed(3);
						var closing_nwt=parseFloat(parseFloat(item.op_blc_nwt)+parseFloat(item.inw_nwt)-parseFloat(item.sold_nwt)-parseFloat(item.br_out_nwt)).toFixed(3);
						//Product closing Details
						
						trHTML += '<tr>' 
									+'<td style="text-align:left;">'+item.product_name+'</td>'
									+'<td>'+item.op_blc_pcs+'</td>'
									+'<td>'+item.op_blc_gwt+'</td>'
									+'<td>'+item.op_blc_nwt+'</td>'
									+'<td>'+item.inw_pcs+'</td>'
									+'<td>'+item.inw_gwt+'</td>'
									+'<td>'+item.inw_nwt+'</td>'
									+'<td>'+parseFloat(parseFloat(item.sold_pcs)+parseFloat(item.br_out_pcs))+'</td>'
									+'<td>'+parseFloat(parseFloat(item.sold_gwt)+parseFloat(item.br_out_gwt)).toFixed(3)+'</td>'
									+'<td>'+parseFloat(parseFloat(item.sold_nwt)+parseFloat(item.br_out_nwt)).toFixed(3)+'</td>'
									+'<td>'+closing_pcs+'</td>'
									+'<td>'+closing_gwt+'</td>'
									+'<td>'+closing_nwt+'</td>'
									+'<td>'+item.in_trans_pcs+'</td>'
									+'<td>'+parseFloat(parseFloat(closing_pcs)+parseFloat(item.in_trans_pcs))+'</td>'
					   			'</tr>';
					   	closing_inw_pcs+=parseFloat(closing_pcs); 
					   	closing_inw_gwt+=parseFloat(closing_gwt); 
					   	closing_net_wt+=parseFloat(closing_nwt); 
					});	
					
			    	trHTML += '<tr style="font-weight:bold;">' +
									+'<td></td>'
									+'<td style="text-align:left;"><strong>SUB TOTAL</strong></td>'
									+'<td>'+op_blc_pcss+'</td>'
									+'<td>'+parseFloat(op_blc_gwt).toFixed(3)+'</td>'
									+'<td>'+parseFloat(op_blc_nwt).toFixed(3)+'</td>'
									+'<td>'+inw_pcss+'</td>'
									+'<td>'+parseFloat(inw_gwt).toFixed(3)+'</td>'
									+'<td>'+parseFloat(inw_nwt).toFixed(3)+'</td>'
									+'<td>'+parseFloat(sold_pcs+other_s_pcs)+'</td>'
									+'<td>'+parseFloat(sold_gwt+other_s_gwt).toFixed(3)+'</td>'
									+'<td>'+parseFloat(sold_nwt+other_s_nwt).toFixed(3)+'</td>'
									+'<td>'+closing_inw_pcs+'</td>'
									+'<td>'+parseFloat(closing_inw_gwt).toFixed(3)+'</td>'
									+'<td>'+parseFloat(closing_net_wt).toFixed(3)+'</td>'
									+'<td></td>'
									+'<td></td>'
				    		 '</tr>';
				    		metal_op_blc_pcss+=parseFloat(op_blc_pcss);
				    		metal_op_blc_gwt+=parseFloat(op_blc_gwt);
				    		metal_op_blc_nwt+=parseFloat(op_blc_nwt);
				    		
				    		metal_inw_pcss+=parseFloat(inw_pcss);
				    		metal_inw_gwt+=parseFloat(inw_gwt);
				    		metal_inw_nwt+=parseFloat(inw_nwt);
				    		
				    		metal_closing_inw_pcs+=parseFloat(closing_inw_pcs);
				    		metal_closing_inw_gwt+=parseFloat(closing_inw_gwt);
				    		metal_closing_net_wt+=parseFloat(closing_net_wt);
				});
				
				trHTML += '<tr style="font-weight:bold;">' +
									+'<td></td>'
									+'<td style="text-align:left;">TOTAL</td>'
									+'<td>'+metal_op_blc_pcss+'</td>'
									+'<td>'+parseFloat(metal_op_blc_gwt).toFixed(3)+'</td>'
									+'<td>'+parseFloat(metal_op_blc_nwt).toFixed(3)+'</td>'
									+'<td>'+metal_inw_pcss+'</td>'
									+'<td>'+parseFloat(metal_inw_gwt).toFixed(3)+'</td>'
									+'<td>'+parseFloat(metal_inw_nwt).toFixed(3)+'</td>'
									+'<td></td>'
								    +'<td></td>'
									+'<td></td>'
									+'<td>'+metal_closing_inw_pcs+'</td>'
									+'<td>'+parseFloat(metal_closing_inw_gwt).toFixed(3)+'</td>'
									+'<td>'+parseFloat(metal_closing_net_wt).toFixed(3)+'</td>'
									+'<td></td>'
									+'<td></td>'
				    		 '</tr>';
				    		 
				total_op_blc_pcss+=parseFloat(metal_op_blc_pcss);
	    		total_op_blc_gwt+=parseFloat(metal_op_blc_gwt);
	    		total_op_blc_nwt+=parseFloat(metal_op_blc_nwt);
	    		
	    		total_inw_pcss+=parseFloat(metal_inw_pcss);
	    		total_inw_gwt+=parseFloat(metal_inw_gwt);
	    		total_inw_nwt+=parseFloat(metal_inw_nwt);
	    		
	    		total_closing_inw_pcs+=parseFloat(metal_closing_inw_pcs);
	    		total_closing_inw_gwt+=parseFloat(metal_closing_inw_gwt);
	    		total_closing_net_wt+=parseFloat(metal_closing_net_wt);
				    		
			});
			
			
                trHTML += '<tr style="font-weight:bold;">' +
						+'<td></td>'
						+'<td style="text-align:left;">GRAND TOTAL</td>'
						+'<td>'+total_op_blc_pcss+'</td>'
						+'<td>'+parseFloat(total_op_blc_gwt).toFixed(3)+'</td>'
						+'<td>'+parseFloat(total_op_blc_nwt).toFixed(3)+'</td>'
						+'<td>'+total_inw_pcss+'</td>'
						+'<td>'+parseFloat(total_inw_gwt).toFixed(3)+'</td>'
						+'<td>'+parseFloat(total_inw_nwt).toFixed(3)+'</td>'
						+'<td></td>'
					    +'<td></td>'
						+'<td></td>'
						+'<td>'+total_closing_inw_pcs+'</td>'
						+'<td>'+parseFloat(total_closing_inw_gwt).toFixed(3)+'</td>'
						+'<td>'+parseFloat(total_closing_net_wt).toFixed(3)+'</td>'
						+'<td></td>'
						+'<td></td>'
	    		 '</tr>';
			
			
			    $.each(non_tag_items,function(key,metal){
			        trHTML += '<tr>' 
									+'<td style="font-weight:bold;text-align:left;text-transform:uppercase;" colspan="2">Non Tag Items</td>'
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
					   			'</tr>';
			        $.each(metal,function(k,category){
			            trHTML += '<tr>' 
									+'<td style="font-weight:bold;text-align:left;" colspan="2">'+k+'</td>'
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
					   			'</tr>';
					   		$.each(category,function(c,items){
					   		    trHTML += '<tr style="font-weight:bold;">' 
									+'<td style="font-weight:bold;text-align:left;">'+items.product_name+'</td>'
									+'<td>'+items.op_blc_pcs+'</td>'
								    +'<td>'+items.op_blc_gwt+'</td>'
							    	+'<td>'+items.op_blc_nwt+'</td>'
							    	+'<td>'+items.inw_pcs+'</td>'
								    +'<td>'+items.inw_gwt+'</td>'
									+'<td>'+items.inw_nwt+'</td>'
									+'<td>'+items.out_pcs+'</td>'
									+'<td>'+items.out_gwt+'</td>'
									+'<td>'+items.out_nwt+'</td>'
									+'<td>'+parseFloat(parseFloat(items.op_blc_pcs)+parseFloat(items.inw_pcs)-parseFloat(items.out_pcs)).toFixed(3)+'</td>'
								    +'<td>'+parseFloat(parseFloat(items.op_blc_gwt)+parseFloat(items.inw_gwt)-parseFloat(items.out_gwt)).toFixed(3)+'</td>'
							    	+'<td>'+parseFloat(parseFloat(items.op_blc_nwt)+parseFloat(items.inw_nwt)-parseFloat(items.out_nwt)).toFixed(3)+'</td>'
							    	+'<td></td>'
							    	+'<td></td>'
					   			'</tr>';
					   		});
			        });
			    });
			    $('#stock_list > tbody').html(trHTML);
			     // Check and initialise datatable
				if ( ! $.fn.DataTable.isDataTable( '#stock_list' ) ) { 
					oTable = $('#stock_list').dataTable({ 
					"bSort": false, 
					"bInfo": true, 
					"scrollX":'100%',  
					"dom": 'lBfrtip',
					"paging":false,
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
						extend:'excel',
						footer: true,
						title: 'STOCK REPORT '+branch_name+'-'+dt_range[0]+'-'+dt_range[1],
					}
					], 
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

$('#stock_detail_search').on('click',function(){
    get_stock_details();
});

function get_stock_details()
{
    var title='';
	$("div.overlay").css("display", "block"); 
	var dt_range=($("#dt_range").val()).split('-');
    var branch_name=($('#branch_name').val()!='' && $('#branch_name').val()!=undefined ? $('#branch_name').val() : $("#branch_select option:selected").text());
	var company_name=$('#company_name').val();
	title="<b><span style='font-size:15pt;margin-left:40%;'>"+company_name+"</span></b><b><span style='font-size:15pt;'></span></b></br>"+"<span style=font-size:13pt;margin-left:25%;>&nbsp;&nbsp;Stock Report "+($("#branch_select").val()!='' ? "Cost Center:"+branch_name+" ":'')+"From&nbsp;:&nbsp;"+dt_range[0]+" &nbsp;&nbsp;To Date&nbsp;:&nbsp;"+dt_range[1]+"</span>";
	my_Date = new Date();
	$.ajax({
		 url:base_url+ "index.php/admin_ret_reports/stock_details/ajax?nocache=" + my_Date.getUTCSeconds(),
		 data: ( {'dt_range' :$("#dt_range").val(),'id_branch':($('#branch_filter').val()!='' && $('#branch_filter').val()!=undefined ? $('#branch_filter').val(): $("#branch_select").val()),'id_product':$('#prod_select').val(),'id_metal':$('#metal').val(),'id_category':$('#category').val(),'group_by':$('#select_group_by').val()}),
		 dataType:"JSON",
		 type:"POST",
		 success:function(data){
		 	var list = data.list;
		 	var non_tag_items = data.non_tag_items;
		 	
	 		$("#stock_list > tbody > tr").remove();
	 		$('#stock_list').dataTable().fnClearTable();
    		$('#stock_list').dataTable().fnDestroy();	
    		
			if (list!= null)
			{  	
				trHTML = ''; 
				tfootHTML = '';
				total_bill_amount = 0;
				total_bill_tax_value = 0;
				
				    var total_op_blc_pcss = 0;
					var total_op_blc_gwt = 0;
					var total_op_blc_nwt = 0;
					
					var total_inw_pcss = 0;
					var total_inw_gwt = 0;
					var total_inw_nwt = 0;
					
					var total_closing_inw_pcs=0;
					var total_closing_inw_gwt=0;
					var total_closing_net_wt=0;
					
					var total_outward_pcs=0;
					var total_outward_gwt=0;
					var total_outward_nwt=0;
					
				$.each(list, function (i, metal) { 
				    
				    var metal_op_blc_pcss = 0;
					var metal_op_blc_gwt = 0;
					var metal_op_blc_nwt = 0;
					
					var metal_inw_pcss = 0;
					var metal_inw_gwt = 0;
					var metal_inw_nwt = 0;
					
					var metal_outward_pcs = 0;
					var metal_outward_gwt = 0;
					var metal_outward_nwt = 0;
					
					var metal_closing_inw_pcs=0;
					var metal_closing_inw_gwt=0;
					var metal_closing_net_wt=0;
					
				    $.each(metal, function (i, category) { 
					var op_blc_pcss = 0;
					var op_blc_gwt = 0;
					var op_blc_nwt = 0;
					
					var inw_pcss = 0;
					var inw_gwt = 0;
					var inw_nwt = 0;
					
					var sold_pcs = 0;
					var sold_gwt = 0;
					var sold_nwt = 0;
					
					var other_s_pcs = 0;
					var other_s_gwt = 0;
					var other_s_nwt = 0;
					
					var closing_inw_pcs=0;
					var closing_inw_gwt=0;
					var closing_net_wt=0;
					if($('#select_group_by').val()==1)
					{
					    trHTML += '<tr>' 
									+'<td style="text-align:left;" colspan="2"><strong>'+i+'</strong></td>'
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
					   			'</tr>';
					}
                    	
			 					 		 
					$.each(category, function (idx, item) { 
						
						op_blc_pcss+=parseFloat(item.op_blc_pcs);
						op_blc_gwt+=parseFloat(item.op_blc_gwt);
						op_blc_nwt+=parseFloat(item.op_blc_nwt);
						
						//Product Inward Details
						inw_pcss+=parseFloat(item.inw_pcs);
						inw_gwt+=parseFloat(item.inw_gwt);
						inw_nwt+=parseFloat(item.inw_nwt);
						//Product Inward Details
						
						
						//Product SOld Details
						sold_pcs+=parseFloat(item.sold_pcs);
						sold_gwt+=parseFloat(item.sold_gwt);
						sold_nwt+=parseFloat(item.sold_nwt);
						//Product SOld Details
						
						//Other Outward Details
						other_s_pcs+=parseFloat(item.br_out_pcs);
						other_s_gwt+=parseFloat(item.br_out_gwt);
						other_s_nwt+=parseFloat(item.br_out_nwt);
						//Other Outward Details
						
						//Product closing Details
						var closing_pcs=parseFloat(item.op_blc_pcs)+parseFloat(item.inw_pcs)-parseFloat(parseFloat(item.sold_pcs)+parseFloat(item.br_out_pcs));
						var closing_gwt=parseFloat(parseFloat(item.op_blc_gwt)+parseFloat(item.inw_gwt)-parseFloat(item.sold_gwt)-parseFloat(item.br_out_gwt)).toFixed(3);
						var closing_nwt=parseFloat(parseFloat(item.op_blc_nwt)+parseFloat(item.inw_nwt)-parseFloat(item.sold_nwt)-parseFloat(item.br_out_nwt)).toFixed(3);
						//Product closing Details
						
						trHTML += '<tr>' 
									+'<td style="text-align:left;">'+($('#select_group_by').val()==1 ? item.product_name : item.category_name )+'</td>'
									+'<td>'+item.op_blc_pcs+'</td>'
									+'<td>'+item.op_blc_gwt+'</td>'
									+'<td>'+item.op_blc_nwt+'</td>'
									+'<td>'+item.inw_pcs+'</td>'
									+'<td>'+item.inw_gwt+'</td>'
									+'<td>'+item.inw_nwt+'</td>'
									+'<td>'+parseFloat(parseFloat(item.sold_pcs)+parseFloat(item.br_out_pcs))+'</td>'
									+'<td>'+parseFloat(parseFloat(item.sold_gwt)+parseFloat(item.br_out_gwt)).toFixed(3)+'</td>'
									+'<td>'+parseFloat(parseFloat(item.sold_nwt)+parseFloat(item.br_out_nwt)).toFixed(3)+'</td>'
									+'<td>'+closing_pcs+'</td>'
									+'<td>'+closing_gwt+'</td>'
									+'<td>'+closing_nwt+'</td>'
									+'<td>'+item.in_trans_pcs+'</td>'
									+'<td>'+parseFloat(parseFloat(closing_pcs)+parseFloat(item.in_trans_pcs))+'</td>'
					   			'</tr>';
					   	closing_inw_pcs+=parseFloat(closing_pcs); 
					   	closing_inw_gwt+=parseFloat(closing_gwt); 
					   	closing_net_wt+=parseFloat(closing_nwt); 
					});	
					if($('#select_group_by').val()==1)
					{
					    trHTML += '<tr style="font-weight:bold;">' +
									+'<td></td>'
									+'<td style="text-align:left;"><strong>SUB TOTAL</strong></td>'
									+'<td>'+op_blc_pcss+'</td>'
									+'<td>'+parseFloat(op_blc_gwt).toFixed(3)+'</td>'
									+'<td>'+parseFloat(op_blc_nwt).toFixed(3)+'</td>'
									+'<td>'+inw_pcss+'</td>'
									+'<td>'+parseFloat(inw_gwt).toFixed(3)+'</td>'
									+'<td>'+parseFloat(inw_nwt).toFixed(3)+'</td>'
									+'<td>'+parseFloat(sold_pcs+other_s_pcs)+'</td>'
									+'<td>'+parseFloat(sold_gwt+other_s_gwt).toFixed(3)+'</td>'
									+'<td>'+parseFloat(sold_nwt+other_s_nwt).toFixed(3)+'</td>'
									+'<td>'+closing_inw_pcs+'</td>'
									+'<td>'+parseFloat(closing_inw_gwt).toFixed(3)+'</td>'
									+'<td>'+parseFloat(closing_net_wt).toFixed(3)+'</td>'
									+'<td></td>'
									+'<td></td>'
				    		 '</tr>';
					}
			    	
				    		metal_op_blc_pcss+=parseFloat(op_blc_pcss);
				    		metal_op_blc_gwt+=parseFloat(op_blc_gwt);
				    		metal_op_blc_nwt+=parseFloat(op_blc_nwt);
				    		
				    		metal_inw_pcss+=parseFloat(inw_pcss);
				    		metal_inw_gwt+=parseFloat(inw_gwt);
				    		metal_inw_nwt+=parseFloat(inw_nwt);
				    		
				    		metal_outward_pcs+=parseFloat(sold_pcs+other_s_pcs);
				    		metal_outward_gwt+=parseFloat(sold_gwt+other_s_gwt);
				    		metal_outward_nwt+=parseFloat(sold_nwt+other_s_nwt);
				    		
				    		metal_closing_inw_pcs+=parseFloat(closing_inw_pcs);
				    		metal_closing_inw_gwt+=parseFloat(closing_inw_gwt);
				    		metal_closing_net_wt+=parseFloat(closing_net_wt);
				});
				if($('#select_group_by').val()==1)
				{
				    trHTML += '<tr style="font-weight:bold;">' +
									+'<td></td>'
									+'<td style="text-align:left;">TOTAL</td>'
									+'<td>'+metal_op_blc_pcss+'</td>'
									+'<td>'+parseFloat(metal_op_blc_gwt).toFixed(3)+'</td>'
									+'<td>'+parseFloat(metal_op_blc_nwt).toFixed(3)+'</td>'
									+'<td>'+metal_inw_pcss+'</td>'
									+'<td>'+parseFloat(metal_inw_gwt).toFixed(3)+'</td>'
									+'<td>'+parseFloat(metal_inw_nwt).toFixed(3)+'</td>'
									+'<td>'+parseFloat(metal_outward_pcs)+'</td>'
								    +'<td>'+parseFloat(metal_outward_gwt).toFixed(3)+'</td>'
									+'<td>'+parseFloat(metal_outward_nwt).toFixed(3)+'</td>'
									+'<td>'+metal_closing_inw_pcs+'</td>'
									+'<td>'+parseFloat(metal_closing_inw_gwt).toFixed(3)+'</td>'
									+'<td>'+parseFloat(metal_closing_net_wt).toFixed(3)+'</td>'
									+'<td></td>'
									+'<td></td>'
				    		 '</tr>';
				}
				
				    		 
				total_op_blc_pcss+=parseFloat(metal_op_blc_pcss);
	    		total_op_blc_gwt+=parseFloat(metal_op_blc_gwt);
	    		total_op_blc_nwt+=parseFloat(metal_op_blc_nwt);
	    		
	    		total_inw_pcss+=parseFloat(metal_inw_pcss);
	    		total_inw_gwt+=parseFloat(metal_inw_gwt);
	    		total_inw_nwt+=parseFloat(metal_inw_nwt);
	    		
	    		total_outward_pcs+=parseFloat(metal_outward_pcs);
	    		total_outward_gwt+=parseFloat(metal_outward_gwt);
	    		total_outward_nwt+=parseFloat(metal_outward_nwt);
	    		
	    		total_closing_inw_pcs+=parseFloat(metal_closing_inw_pcs);
	    		total_closing_inw_gwt+=parseFloat(metal_closing_inw_gwt);
	    		total_closing_net_wt+=parseFloat(metal_closing_net_wt);
				    		
			});
			
			
                trHTML += '<tr style="font-weight:bold;">' +
						+'<td></td>'
						+'<td style="text-align:left;">GRAND TOTAL</td>'
						+'<td>'+total_op_blc_pcss+'</td>'
						+'<td>'+parseFloat(total_op_blc_gwt).toFixed(3)+'</td>'
						+'<td>'+parseFloat(total_op_blc_nwt).toFixed(3)+'</td>'
						+'<td>'+total_inw_pcss+'</td>'
						+'<td>'+parseFloat(total_inw_gwt).toFixed(3)+'</td>'
						+'<td>'+parseFloat(total_inw_nwt).toFixed(3)+'</td>'
						+'<td>'+parseFloat(total_outward_pcs)+'</td>'
					    +'<td>'+parseFloat(total_outward_gwt).toFixed(3)+'</td>'
						+'<td>'+parseFloat(total_outward_nwt).toFixed(3)+'</td>'
						+'<td>'+total_closing_inw_pcs+'</td>'
						+'<td>'+parseFloat(total_closing_inw_gwt).toFixed(3)+'</td>'
						+'<td>'+parseFloat(total_closing_net_wt).toFixed(3)+'</td>'
						+'<td></td>'
						+'<td></td>'
	    		 '</tr>';
			
			
			    $.each(non_tag_items,function(key,metal){
			        trHTML += '<tr>' 
									+'<td style="font-weight:bold;text-align:left;text-transform:uppercase;" colspan="2">Non Tag Items</td>'
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
					   			'</tr>';
			        $.each(metal,function(k,category){
			            trHTML += '<tr>' 
									+'<td style="font-weight:bold;text-align:left;" colspan="2">'+k+'</td>'
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
					   			'</tr>';
					   		$.each(category,function(c,items){
					   		    trHTML += '<tr style="font-weight:bold;">' 
									+'<td style="font-weight:bold;text-align:left;">'+items.product_name+'</td>'
									+'<td>'+items.op_blc_pcs+'</td>'
								    +'<td>'+items.op_blc_gwt+'</td>'
							    	+'<td>'+items.op_blc_nwt+'</td>'
							    	+'<td>'+items.inw_pcs+'</td>'
								    +'<td>'+items.inw_gwt+'</td>'
									+'<td>'+items.inw_nwt+'</td>'
									+'<td>'+items.out_pcs+'</td>'
									+'<td>'+items.out_gwt+'</td>'
									+'<td>'+items.out_nwt+'</td>'
									+'<td>'+parseFloat(parseFloat(items.op_blc_pcs)+parseFloat(items.inw_pcs)-parseFloat(items.out_pcs)).toFixed(3)+'</td>'
								    +'<td>'+parseFloat(parseFloat(items.op_blc_gwt)+parseFloat(items.inw_gwt)-parseFloat(items.out_gwt)).toFixed(3)+'</td>'
							    	+'<td>'+parseFloat(parseFloat(items.op_blc_nwt)+parseFloat(items.inw_nwt)-parseFloat(items.out_nwt)).toFixed(3)+'</td>'
							    	+'<td></td>'
							    	+'<td></td>'
					   			'</tr>';
					   		});
			        });
			    });
			    $('#stock_list > tbody').html(trHTML);
			     // Check and initialise datatable
				if ( ! $.fn.DataTable.isDataTable( '#stock_list' ) ) { 
					oTable = $('#stock_list').dataTable({ 
					"bSort": false, 
					"bInfo": true, 
					"scrollX":'100%',  
					"dom": 'lBfrtip',
					"paging":false,
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
						extend:'excel',
						footer: true,
						title: 'STOCK REPORT '+branch_name+'-'+dt_range[0]+'-'+dt_range[1],
					}
					], 
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

//stock report
function stock_checking()
{
	$("div.overlay").css("display", "block"); 
	var dt_range=($("#dt_range").val()).split('-');
	var branch_name=$("#branch_select option:selected").text();
	var company_name=$('#company_name').val();
	var title="<b><span style='font-size:15pt;margin-left:50%;'>"+company_name+"</span></b><b><span style='font-size:15pt;'></span></b></br>"+"<span style=font-size:13pt;margin-left:25%;>&nbsp;&nbsp;Stock Report "+($("#branch_select").val()!='' ? "Cost Center:"+branch_name+" ":'')+"From&nbsp;:&nbsp;"+dt_range[0]+" &nbsp;&nbsp;To Date&nbsp;:&nbsp;"+dt_range[1]+"</span>";
	
	my_Date = new Date();
	$.ajax({
		 url:base_url+ "index.php/admin_ret_reports/stock_checking/ajax?nocache=" + my_Date.getUTCSeconds(),
		 data: ( {'dt_range' :$("#dt_range").val(),'id_branch':$("#branch_select").val(),'id_product':$('#prod_select').val()}),
		 dataType:"JSON",
		 type:"POST",
		 success:function(data){
		 	var list = data.list;
	 		$("#stock_list > tbody > tr").remove();  		
			if (list!= null)
			{  	
				trHTML = ''; 
				tfootHTML = '';
				total_bill_amount = 0;
				total_bill_tax_value = 0;
				$.each(list, function (i, branch) { 
					var op_blc_pcss = 0;
					var op_blc_gwt = 0;
					var op_blc_nwt = 0;
					var inw_pcss = 0;
					var inw_gwt = 0;
					var inw_nwt = 0;
					var sold_pcs = 0;
					var sold_gwt = 0;
					var sold_nwt = 0;
					var closing_inw_pcs=0;
					var closing_inw_gwt=0;
					var closing_net_wt=0;
					$.each(branch, function (idx, item) { 
						
						op_blc_pcss+=parseFloat(item.op_blc_pcs);
						op_blc_gwt+=parseFloat(item.op_blc_gwt);
						op_blc_nwt+=parseFloat(item.op_blc_nwt);
						inw_pcss+=parseFloat(item.inw_pcs);
						inw_gwt+=parseFloat(item.inw_gwt);
						inw_nwt+=parseFloat(item.inw_nwt);
						sold_pcs+=parseFloat(item.sold_pcs);
						sold_gwt+=parseFloat(item.sold_gwt);
						sold_nwt+=parseFloat(item.sold_nwt);
						var closing_pcs=parseFloat(item.op_blc_pcs)+parseFloat(item.inw_pcs)-parseFloat(item.sold_pcs);
						var closing_gwt=parseFloat(parseFloat(item.op_blc_gwt)+parseFloat(item.inw_gwt)-parseFloat(item.sold_gwt)).toFixed(3);
						var closing_nwt=parseFloat(parseFloat(item.op_blc_nwt)+parseFloat(item.inw_nwt)-parseFloat(item.sold_nwt)).toFixed(3);
						trHTML += '<tr>' 
									+'<td>'+item.product_name+'</td>'
									+'<td>'+item.op_blc_pcs+'</td>'
									+'<td>'+item.op_blc_gwt+'</td>'
									+'<td>'+item.op_blc_nwt+'</td>'
									+'<td>'+item.inw_pcs+'</td>'
									+'<td>'+item.inw_gwt+'</td>'
									+'<td>'+item.inw_nwt+'</td>'
									+'<td>'+item.sold_pcs+'</td>'
									+'<td>'+item.sold_gwt+'</td>'
									+'<td>'+item.sold_nwt+'</td>'
									+'<td>'+closing_pcs+'</td>'
									+'<td>'+closing_gwt+'</td>'
									+'<td>'+closing_nwt+'</td>'
					   			'</tr>';
					   	closing_inw_pcs+=parseFloat(closing_pcs); 
					   	closing_inw_gwt+=parseFloat(closing_gwt); 
					   	closing_net_wt+=parseFloat(closing_nwt); 
					});	
					
			    	trHTML += '<tr style="font-weight:bold;">' +
									+'<td></td>'
									+'<td>Sub Total</td>'
									+'<td>'+op_blc_pcss+'</td>'
									+'<td>'+parseFloat(op_blc_gwt).toFixed(3)+'</td>'
									+'<td>'+parseFloat(op_blc_nwt).toFixed(3)+'</td>'
									+'<td>'+inw_pcss+'</td>'
									+'<td>'+parseFloat(inw_gwt).toFixed(3)+'</td>'
									+'<td>'+parseFloat(inw_nwt).toFixed(3)+'</td>'
									+'<td>'+sold_pcs+'</td>'
									+'<td>'+parseFloat(sold_gwt).toFixed(3)+'</td>'
									+'<td>'+parseFloat(sold_nwt).toFixed(3)+'</td>'
									+'<td>'+closing_inw_pcs+'</td>'
									+'<td>'+parseFloat(closing_inw_gwt).toFixed(3)+'</td>'
									+'<td>'+parseFloat(closing_net_wt).toFixed(3)+'</td>'
				    		 '</tr>';
				});
			    $('#stock_list > tbody').html(trHTML);
			     // Check and initialise datatable
				if ( ! $.fn.DataTable.isDataTable( '#stock_list' ) ) { 
					oTable = $('#stock_list').dataTable({ 
					"bSort": false, 
					"bInfo": true, 
					"scrollX":'100%',  
					"dom": 'lBfrtip',
					"paging":false,
					"buttons": [
					{
						extend: 'print',
						footer: true,
						title: title,
						customize: function ( win ) {
						$(win.document.body).find( 'table' )
						.addClass( 'compact' )
						.css( 'font-size', 'inherit' );
						},
					},
					{
						extend:'excel',
						footer: true,
						title: title,
					}
					], 
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
//Tag scan missing report
function tag_missing_report()
{
	$("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/tag_scan_missing/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'id_product':$('#prod_select').val(),'id_design' :$("#des_select").val(),'id_branch':$("#branch_select").val(),'id_weight':$('#wt_select').val()}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			 	$("div.overlay").css("display", "none"); 
			 	var list = data.list;
				var oTable = $('#tag_missing_list').DataTable();
				oTable.clear().draw();				  
				if (list!= null && list.length > 0)
				{  	
					oTable = $('#tag_missing_list').dataTable({
						"bDestroy": true,
		                "bInfo": true,
		                "bFilter": true,
						"order": [[ 0, "desc" ]],
		                "scrollX":'100%',
		                "bSort": true,
		                "dom": 'lBfrtip',
		                "buttons": [
								 {
								   extend: 'print',
								   footer: true,
								   title: "Tag Missing Report"+($("#branch_select").val()!='' ?'- '+$("#branch_select option:selected").text() :''),
								   customize: function ( win ) {
										$(win.document.body).find( 'table' )
											.addClass( 'compact' )
											.css( 'font-size', 'inherit' );
									},
								 },
								 {
									extend:'excel',
									footer: true,
								     title: "Tag Missing Report"+($("#branch_select").val()!='' ?'- '+$("#branch_select option:selected").text() :''),
								  }
								 ],
						"aaData": list,
						"aoColumns": [	
										{ "mDataProp": "branch_name" },
										{ "mDataProp": "tag_date" },
										{ "mDataProp": "tag_id" },
										{ "mDataProp": "product_name" },
										{ "mDataProp": "design_name" },
										{ "mDataProp": "piece" },
										{ "mDataProp": "gross_wt" },
										{ "mDataProp": "net_wt" },
										{ "mDataProp": "tag_mc_value" },
										{ "mDataProp": function ( row, type, val, meta ){
											if(row.tag_mc_type==1 &&  row.tag_mc_value>0)
											{
												var tag_mc_value=0;
												if(row.calculation_based_on==0 || row.calculation_based_on==2)
												{
													tag_mc_value=parseFloat(row.tag_mc_value/row.gross_wt).toFixed(2);
												}else if(row.calculation_based_on==1)
												{
													tag_mc_value=parseFloat(row.tag_mc_value/row.net_wt).toFixed(2);
												}else{
												    tag_mc_value='-';
												}
												return tag_mc_value;
											}
											else{
												return '-';
											}
											
										},
										},
										{ "mDataProp": function ( row, type, val, meta ){
										    if(row.tag_mc_type==2 && row.tag_mc_value>0)
											{
												var tag_mc_value=0;
												tag_mc_value=parseFloat(parseFloat(row.tag_mc_value)/parseFloat(row.piece)).toFixed(2);
												return tag_mc_value;
											}else{
												return '-';
											}
										},
										},
										{ "mDataProp": "sales_value" },
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
//Tag scan missing report
//Tag scan report
function get_scanned_details_report()
{
    $("div.overlay").css("display", "block");
    var branch_name=($('#branch_name').val()!='' && $('#branch_name').val()!=undefined ? $('#branch_name').val() : $("#branch_select option:selected").text());
	var company_name=$('#company_name').val();
	var title="<b><span style='font-size:15pt;margin-left:30%;'>"+company_name+"</span></b><b><span style='font-size:12pt;'></span></b></br>"+"<span style=font-size:13pt;margin-left:25%;>&nbsp;&nbsp;STOCK CHECKING "+($('#report_type option:selected').text())+" Report"+($("#branch_select").val()!='' ? "-"+branch_name+" ":'')+"</span>";
	
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/scan_report/scanned_report?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'dt_range' :$("#dt_range").val(),'id_product':$('#prod_select').val(),'id_branch':($('#branch_filter').val()!='' && $('#branch_filter').val()!=undefined ? $('#branch_filter').val(): $("#branch_select").val()),'report_type':$('#report_type').val()}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			 	
			 	var list = data;
				var oTable = $('#scanned_list').DataTable();
				oTable.clear().draw();				  
				if (list!= null && list.length > 0)
				{  	
					oTable = $('#scanned_list').dataTable({
						"bDestroy": true,
		                "bInfo": true,
		                "bFilter": true,
						"order": [[ 0, "desc" ]],
		                "scrollX":'100%',
		                "bSort": true,
		                "dom": 'lBfrtip',
		                "buttons": [
								 {
								   extend: 'print',
								   footer: true,
								   title: '',
								   messageTop:title ,
								   customize: function ( win ) {
										$(win.document.body).find( 'table' )
											.addClass( 'compact' )
											.css( 'font-size', 'inherit' );
									},
								 },
								 {
									extend:'excel',
									footer: true,
								   title: "Scan Report",
								  }
								 ],
						"aaData": list,
						"aoColumns": [	
										{ "mDataProp": "id_scanned" },
										{ "mDataProp": "tag_code" },
										{ "mDataProp": "old_tag_id" },
										{ "mDataProp": "tag_date" },
										{ "mDataProp": "scanned_date" },
										{ "mDataProp": "product_name" },
										{ "mDataProp": "gross_wt" },
										{ "mDataProp": "net_wt" },
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
	
function getScanned_details()
{
	$("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/scan_report/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'id_product':$('#prod_filter').val(),'id_branch':$("#branch_scan_filter").val()}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			 	
			 	var list = data.list.total_items;
				var oTable = $('#tag_list').DataTable();
				oTable.clear().draw();				  
				if (list!= null && list.length > 0)
				{  	
					oTable = $('#tag_list').dataTable({
						"bDestroy": true,
		                "bInfo": true,
		                "bFilter": true,
						"order": [[ 0, "desc" ]],
		                "scrollX":'100%',
		                "bSort": true,
		                "dom": 'lBfrtip',
		                "buttons": [
								 {
								   extend: 'print',
								   footer: true,
								   title: "Stock Checking Tag Scan Report"+($("#branch_select").val()!='' ?'- '+$("#branch_select option:selected").text() :''),
								   customize: function ( win ) {
										$(win.document.body).find( 'table' )
											.addClass( 'compact' )
											.css( 'font-size', 'inherit' );
									},
								 },
								 {
									extend:'excel',
									footer: true,
								   title: "Stock Checking Tag Scan Report"+($("#branch_select").val()!='' ?'- '+$("#branch_select option:selected").text() :''),
								  }
								 ],
						"aaData": list,
						"aoColumns": [	
										{ "mDataProp": "branch_name" },
										{ "mDataProp": "product_name" },
										
										{ "mDataProp": "scanned_pcs" },
										{ "mDataProp": "scanned_gwt" },
										{ "mDataProp": "scanned_nwt" },
										
										{ "mDataProp": "unscanned_pcs" },
										{ "mDataProp": "unscanned_gwt" },
										{ "mDataProp": "unscanned_nwt" },
										
										{ "mDataProp": "sold_pcs" },
										{ "mDataProp": "sold_gwt" },
										{ "mDataProp": "sold_nwt" },
										
										{ "mDataProp": "tot_pcs" },
										{ "mDataProp": "tot_gwt" },
										{ "mDataProp": "tot_nwt" },
										
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
 $("body").on("click","#tag_scan", function(){
 		$('.tag_scan_filter').css('display','block');
 		$('.tag_details').css('display','none');
 		$('.tag_filter').css('display','none');
 		$('.report_filter').css('display','none');
 		$('.scan_details').css('display','block');
 		$('.tag_scan').css('display','block');
        get_ActiveProduct();
        get_branchname_list();
 });
 
 
  $("body").on("click","#scanned_details", function(){
 		$('.tag_scan_filter').css('display','none');
 		$('.report_filter').css('display','block');
 		$('.tag_details').css('display','block');
 		$('.scan_details').css('display','none');
 		$('.tag_scan').css('display','none');
 		get_ActiveProduct();
        get_branchname_list();
 });
 $('#scan_close').on('click',function(){
     if($('#prod_select').val()==null || $('#prod_select').val()=='')
     {
         $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select Product'});
     }
     else if($('#branch_select').val()=='' || $('#branch_select').val()==null)
     {
         $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select Branch'});
     }else
     {
         proceed = confirm("Are You Sure Want to Close This Scan ?"); 
         if(proceed)
         {
             close_tag_scan();
         }
 	     
     }
 });
 $('#tag_scan_search').on('click',function(e){
		
		var input=$('#tag_id').val();
		var old_tag_id=$('#old_tag_id').val().replaceAll(' ',''); 
		var tag_id=input.split('/')[0];
		var print_taken=input.split('/')[1];
		if(tag_id!='' || old_tag_id!='')
		{
			get_tag_scan_details(tag_id,old_tag_id);
		}else{
			alert('Please Enter Tag Number');
		}
		
	});
	
    $("#tag_id,#old_tag_id").keypress(function(e) {
        if(e.which == 13) 
        {
            var old_tag_id=$('#old_tag_id').val().replaceAll(' ',''); 
            get_tag_scan_details($("#tag_id").val(),old_tag_id)
        }
    });
    
    
    function get_tag_scan_details(tag_id,old_tag_id)
    {
    	$("div.overlay").css("display", "block"); 
       var my_Date = new Date();
    	$.ajax({
            url: base_url+'index.php/admin_ret_reports/get_tag_scan_details/?nocache=' + my_Date.getUTCSeconds(),             
            dataType: "json", 
            method: "POST", 
            data: {'tag_id': tag_id,'old_tag_id': old_tag_id,'id_product':$('#prod_select').val(),'id_branch':$('#branch_select').val()}, 
            success: function (data) {
    			if(data.status)
    			{
    				set_tag_scanned_list(data.tag_details);
    				
    			}else{
    				alert(data.msg);
    				$("div.overlay").css("display", "none"); 
    			}
    			$('#tag_id').val('');
    			$('#old_tag_id').val('');
            }
         });
    }
function close_tag_scan()
{
  $("div.overlay").css("display", "block"); 
   var my_Date = new Date();
	$.ajax({
        url: base_url+'index.php/admin_ret_reports/close_tag_scan/?nocache=' + my_Date.getUTCSeconds(),             
        dataType: "json", 
        method: "POST", 
        data: {'id_product':$('#prod_select').val(),'id_branch':$('#branch_select').val()}, 
        success: function (data) {
			if(data.status)
			{
				alert(data.msg)
				//set_tag_scanned_list(data.tag_details);
				
			}else{
				alert(data.msg);
			
			}
			$('#tag_id').val('');
        }
     });
     
     	$("div.overlay").css("display", "none"); 
}
function set_tag_scanned_list(tag_details)
{
    var data=tag_details;
    console.log(data);
    var trHtml='';
    trHtml+='<tr>'
            +'<td>'+data.tag_code+'</td>'
            +'<td>'+data.product_name+'</td>'
            +'<td>'+data.product_name+'</td>'
            +'<td>'+data.design_name+'</td>'
            +'<td><input type="hidden" class="piece" value="'+data.piece+'">'+data.piece+'</td>'
            +'<td>'+data.gross_wt+'</td>'
            +'<td>'+data.tag_mc_value+'</td>'
            +'<td>'+data.sales_value+'</td>'
            +'</tr>';
     if($('#tagging_scan_list > tbody  > tr').length>0)
	{
	    $('#tagging_scan_list > tbody > tr:first').before(trHtml);
	}else{
	    $('#tagging_scan_list tbody').append(trHtml);
	}
	
    calcluate_tag_scanned();
    $("div.overlay").css("display", "none"); 
			 
}
function calcluate_tag_scanned()
{
    var total_pcs=0;
    var trHtml='';
    $('#tagging_scan_list > tbody tr').each(function(idx, row){
        curRow = $(this);
        total_pcs+=parseFloat(curRow.find('.piece').val());
    });
    trHtml+='<tr style="font-weight:bold;">'
                +'<td colspan="4">Total</td>'
                +'<td>'+total_pcs+'</td>'
                +'<td></td>'
                +'<td></td>'
                +'<td></td>'
            +'</tr>';
    $('#tagging_scan_list > tfoot').html(trHtml);
    $('#tot_scanned_pcs').html(parseFloat(total_pcs));
}
function calculateTag()
{
	var tot_pcs=0;
	var tot_gwt=0;
	var tot_nwt=0;
	$('#tagging_scan_list > tbody  > tr').each(function(index, tr) {
			tot_pcs += parseFloat((isNaN($(this).find('.tot_pcs').val()) || $(this).find('.tot_pcs').val() == '')  ? 0 : $(this).find('.tot_pcs').val());
	});
	
	console.log(tot_pcs);
	console.log(tot_gwt);
	console.log(tot_nwt);
}
//Tag scan report
// Product-wise Sales
/*function itemwise_sales()
{
    var dt_range=($("#dt_range").val()).split('-');
	var branch_name=$("#branch_select option:selected").text();
	var company_name=$('#company_name').val();
	var title="<b><span style='font-size:15pt;margin-left:50%;'>"+company_name+"</span></b><b><span style='font-size:15pt;'></span></b></br>"+"<span style=font-size:13pt;margin-left:25%;>&nbsp;&nbsp;Product Wise Sales Report "+($("#branch_select").val()!='' ? "Cost Center:"+branch_name+" ":'')+"From&nbsp;:&nbsp;"+dt_range[0]+" &nbsp;&nbsp;To Date&nbsp;:&nbsp;"+dt_range[1]+"</span>";
	my_Date = new Date();
	$(".overlay").css("display", "block");	 
	$.ajax({		
	 	type: 'POST',		
	 	url:base_url+ "index.php/admin_ret_reports/item_sales/ajax?nocache=" + my_Date.getUTCSeconds(),
		data: ( {'dt_range' :$("#dt_range").val(),'id_branch':($('#branch_filter').val()!='' && $('#branch_filter').val()!=undefined ? $('#branch_filter').val(): $("#branch_select").val()),'id_product':$('#prod_select').val(),'id_metal':$('#metal').val()}),
	 	dataType : 'json',
		success:function(data){ 
		
		 $(".overlay").css("display", "none");
        var list = data.list;
        var oTable = $('#itemwise-sales').DataTable();
		oTable.clear().draw();				  
			if (list!= null)
			{  	    
				$("#itemwise-sales > tbody > tr").remove();  
				$('#itemwise-sales').dataTable().fnClearTable();
    		    $('#itemwise-sales').dataTable().fnDestroy();
                var trHtml='';
                var grand_total_pcs=0;
                var grand_total_gwt=0;
                var grand_total_nwt=0;
                var grand_total_amount=0;
                $.each(list,function(k,prod){
					
                    var tot_pcs=0;
                    var tot_gwt=0;
                    var tot_nwt=0;
                    var tot_amount=0;
					var product=prod.product_name;
					
                        $.each(prod.design_details,function(key,items){
                            tot_pcs+=parseFloat(items.tot_pcs);	
                            tot_gwt+=parseFloat(items.gross_wt);
                            tot_nwt+=parseFloat(items.net_wt);
                            tot_amount+=parseFloat(items.total_cost);
                            
                            grand_total_pcs+=parseFloat(items.tot_pcs);
                            grand_total_gwt+=parseFloat(items.gross_wt);
                            grand_total_nwt+=parseFloat(items.net_wt);
                            grand_total_amount+=parseFloat(items.total_cost);
                            
                             var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+items.bill_id;
							 trHtml+='<tr>'
							 +'<td style="text-align:left;"><strong>'+product+" - "+items.design_name+'</strong></td>'
							 +'<td></td>'
							 +'<td></td>'
							 +'<td></td>'
							 +'<td></td>'
							 +'<td></td>'
							 +'<td></td>'
						 +'</tr>';
			   
                            trHtml+='<tr>'
                                +'<td style="text-align:left;">'+items.sub_design_name +' </td>'
                                +'<td>'+items.tot_pcs+'</td>'
                                +'<td>'+items.tag_code+'</td>'
                                +'<td><a href='+url+' target="_blank">'+items.bill_no+'</a></td>'
                                +'<td>'+items.gross_wt+'</td>'
                                +'<td>'+items.net_wt+'</td>'
                                +'<td>'+items.total_cost+'</td>'
							
                            +'</tr>';
                        });
                        trHtml+='<tr>'
                                +'<td style="text-align:left;"><strong>SUB TOTAL</strong></td>'
                                +'<td><strong>'+parseFloat(tot_pcs)+'</strong></td>'
                                +'<td></td>'
                                +'<td></td>'
                                +'<td><strong>'+parseFloat(tot_gwt).toFixed(3)+'</strong></td>'
                                +'<td><strong>'+parseFloat(tot_nwt).toFixed(3)+'</strong></td>'
                                +'<td><strong>'+parseFloat(tot_amount).toFixed(3)+'</strong></td>'
                            +'</tr>';
                   
                });
                
                trHtml+='<tr>'
                                +'<td style="text-align:left;"><strong>GRAND TOTAL</strong></td>'
                                +'<td><strong>'+parseFloat(grand_total_pcs)+'</strong></td>'
                                +'<td></td>'
                                +'<td></td>'
                                +'<td><strong>'+parseFloat(grand_total_gwt).toFixed(3)+'</strong></td>'
                                +'<td><strong>'+parseFloat(grand_total_nwt).toFixed(3)+'</strong></td>'
                                +'<td><strong>'+parseFloat(grand_total_amount).toFixed(2)+'</strong></td>'
                            +'</tr>';
                            
                $('#itemwise-sales > tbody').html(trHtml);
                // Check and initialise datatable
	                 if ( ! $.fn.DataTable.isDataTable( '#itemwise-sales' ) ) { 
	                     oTable = $('#itemwise-sales').dataTable({ 
						                "bSort": false, 
						                "bInfo": true, 
						                "scrollX":'100%',  
						                "dom": 'lBfrtip',
						                "lengthMenu": [[-1, 25, 50, 100, 250], ["All" ,25, 50, 100, 250]],
						                "buttons": [
													{
													   extend: 'print',
													   footer: true,
													   title: '',
													   messageTop: title,
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
													    title: 'STOCK AND SALES',
													  }
													 ], 
			
							
									 });
					}
			}
		},
	  	error:function(error)  
	  	{
			$("div.overlay").css("display", "none"); 
	  	}	 
  	});
		
} */
function itemwise_sales()
{
    var dt_range=($("#dt_range").val()).split('-');
	var branch_name=$("#branch_select option:selected").text();
	var company_name=$('#company_name').val();
	var title="<b><span style='font-size:15pt;margin-left:50%;'>"+company_name+"</span></b><b><span style='font-size:15pt;'></span></b></br>"+"<span style=font-size:13pt;margin-left:25%;>&nbsp;&nbsp;Product Wise Sales Report "+($("#branch_select").val()!='' ? "Cost Center:"+branch_name+" ":'')+"From&nbsp;:&nbsp;"+dt_range[0]+" &nbsp;&nbsp;To Date&nbsp;:&nbsp;"+dt_range[1]+"</span>";
	my_Date = new Date();
	$(".overlay").css("display", "block");	 
	$.ajax({		
	 	type: 'POST',		
	 	url:base_url+ "index.php/admin_ret_reports/item_sales/ajax?nocache=" + my_Date.getUTCSeconds(),
		data: ( {'dt_range' :$("#dt_range").val(),'id_branch':($('#branch_filter').val()!='' && $('#branch_filter').val()!=undefined ? $('#branch_filter').val(): $("#branch_select").val()),'id_product':$('#prod_select').val(),'id_metal':$('#metal').val()}),
	 	dataType : 'json',
		success:function(data){ 
		   
		 $(".overlay").css("display", "none");
        var list = data.list;
        var oTable = $('#itemwise-sales').DataTable();
		oTable.clear().draw();				  
			if (list!= null)
			{  	    
				$("#itemwise-sales > tbody > tr").remove();  
				$('#itemwise-sales').dataTable().fnClearTable();
    		    $('#itemwise-sales').dataTable().fnDestroy();
                var trHtml='';
                var grand_total_pcs=0;
                var grand_total_gwt=0;
                var grand_total_nwt=0;
                var grand_total_amount=0;
				console.log(list);
				let result = Array.from( //convert new set value to set value
					new Set( // remove duplicate values from string array 
						(list.map(listItem=>
							listItem.design_details.map((item)=>{ 
								return JSON.stringify({
									'product_name': item.product_name, 
									'design_name': item.design_name, 
									//'sub_design_name': item.sub_design_name 
								}); 							
							})
						)).flat(Infinity)
					) 
				).map(item=>JSON.parse(item));
				result.forEach((item)=>{
					item['list'] = (list.find(listItem=>listItem.product_name==item.product_name) || {})?.design_details?.filter(designDet=>designDet.design_name == item.design_name) || [];
					
				});
				Array.from(new Set(list.map(item=>item.product_name))).forEach(product=>{
					var tot_pcs=0;
					var tot_gwt=0;
					var tot_nwt=0;
					var tot_amount=0;
					let finalArr = result.filter(item=>item.product_name==product);
					console.log(finalArr);
					$.each(finalArr,function(key,item) {
						trHtml+='<tr>'
							+'<td style="text-align:left;"><strong>'+item.product_name+" - "+item.design_name+'</strong></td>'
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
							
						$.each(item.list,function(key,items) {						
	
							tot_pcs+=parseFloat(items.tot_pcs);	
							tot_gwt+=parseFloat(items.gross_wt);
							tot_nwt+=parseFloat(items.net_wt);
							tot_amount+=parseFloat(items.total_cost);
							
							grand_total_pcs+=parseFloat(items.tot_pcs);
							grand_total_gwt+=parseFloat(items.gross_wt);
							grand_total_nwt+=parseFloat(items.net_wt);
							grand_total_amount+=parseFloat(items.total_cost);
	
							var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+items.bill_id;
				
							trHtml+='<tr>'
								+'<td style="text-align:left;">'+items.sub_design_name +' </td>'
								+'<td>'+items.tot_pcs+'</td>'
								+'<td>'+items.tag_code+'</td>'
								+'<td>'+items.old_tag_id+'</td>'
								+'<td><a href='+url+' target="_blank">'+items.bill_no+'</a></td>'
								+'<td>'+items.gross_wt+'</td>'
								+'<td>'+items.net_wt+'</td>'
								+'<td>'+items.cert_no+'</td>'
								+'<td>'+items.style_code+'</td>'
								+'<td>'+items.total_cost+'</td>'
							
							+'</tr>';
	
						});
	
					});
	
					trHtml+='<tr>'
							+'<td style="text-align:left;"><strong>SUB TOTAL</strong></td>'
							+'<td><strong>'+parseFloat(tot_pcs)+'</strong></td>'
							+'<td></td>'
							+'<td></td>'
							+'<td></td>'
							+'<td><strong>'+parseFloat(tot_gwt).toFixed(3)+'</strong></td>'
							+'<td><strong>'+parseFloat(tot_nwt).toFixed(3)+'</strong></td>'
							+'<td></td>'
							+'<td></td>'
							+'<td><strong>'+parseFloat(tot_amount).toFixed(3)+'</strong></td>'
						+'</tr>';					
				});
                
                trHtml+='<tr>'
                                +'<td style="text-align:left;"><strong>GRAND TOTAL</strong></td>'
                                +'<td><strong>'+parseFloat(grand_total_pcs)+'</strong></td>'
                                +'<td></td>'
                                +'<td></td>'
                                +'<td></td>'
                                +'<td><strong>'+parseFloat(grand_total_gwt).toFixed(3)+'</strong></td>'
                                +'<td><strong>'+parseFloat(grand_total_nwt).toFixed(3)+'</strong></td>'
                                +'<td></td>'
								+'<td></td>'
                                +'<td><strong>'+parseFloat(grand_total_amount).toFixed(2)+'</strong></td>'
                            +'</tr>';
                            
                $('#itemwise-sales > tbody').html(trHtml);
                // Check and initialise datatable
	                 if ( ! $.fn.DataTable.isDataTable( '#itemwise-sales' ) ) { 
	                     oTable = $('#itemwise-sales').dataTable({ 
						                "bSort": false, 
						                "bInfo": true, 
						                "scrollX":'100%',  
						                "dom": 'lBfrtip',
						                "lengthMenu": [[-1, 25, 50, 100, 250], ["All" ,25, 50, 100, 250]],
						                "buttons": [
													{
													   extend: 'print',
													   footer: true,
													   title: '',
													   messageTop: title,
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
													    title: 'STOCK AND SALES',
													  }
													 ], 
			
							
									 });
					}
			}
		},
	  	error:function(error)  
	  	{
			$("div.overlay").css("display", "none"); 
	  	}	 
  	});
		
} 
function formatItemsRow( oTable, nTr )
{
	var oData = oTable.fnGetData( nTr );
	var rowDetail = '';
	var designsTable = 
	 '<div class="innerDetails">'+
	  '<table class="table table-responsive table-bordered text-center table-sm">'+ 
	    '<tr class="bg-teal">'+
        '<th>Design</th>'+ 
        '<th>Pieces </th>'+ 
        '<th>Gwt </th>'+ 
        '<th>Nwt </th>'+ 
        '<th>Amount </th>'+ 
        '<th>Detail </th>'+ 
        '</tr>';
	var designs = oData.design_details;
	$.each(designs, function (idx, val) {
		var ref_no = val.design_id;
		var bills = val.bills[idx]; 
		
		var bill_details=
		      '<tr class="tagsTable collapsed bg-teal '+ref_no+' drill-close"> '+	             
        	        '<th>S.No</th>'+ 
        	        '<th>Bill Date </th>'+
        	        '<th>Bill No </th>'+
        	        '<th>Tag Code </th>'+
        	        '<th>Pcs  </th>'+
        	        '<th>G.wt </th>'+
        	        '<th>N.wt </th>'+
        	        '<th></th>'+
	        '</tr>';
	        $.each(val.bills,function(key,items){
	             var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+items.bill_id;
	            bill_details += 
        	         '<tr class="tagsTable collapsed bg-info '+ref_no+' drill-close"> '+	     
        	        '<td>'+parseFloat(key+1)+'</td>'+
        	        '<td>'+items.bill_date+'</td>'+
        	        '<td><a href='+url+' target="_blank">'+items.bill_no+'</a></td>'+
        	        '<td>'+items.tag_code+'</td>'+
        	        '<td>'+items.piece+'</td>'+
        	        '<td>'+items.gross_wt+'</td>'+
        	        '<td>'+items.net_wt+'</td>'+
        	        '</tr><span>';
	        });
		designsTable += 
		    '<tr class="design_det_btn">'+
		    '<td>'+val.design_name+'</td>'+
		    '<td>'+val.tot_pcs+'</td>'+
		    '<td>'+val.gross_wt+'</td>'+
		    '<td>'+val.net_wt+'</td>'+
		    '<td>'+val.total_cost+'</td>'+ 
		    '<td><input type="hidden" id="ref_no" value="'+ref_no+'"/><i class="fa fa-chevron-circle-down text-info open"></i></i></td>'+
		     bill_details+
		    '</tr>'; 
	}); 
	rowDetail = designsTable+'</table></div>';
	return rowDetail;
}
// .Product-wise Sales
//bill wise transcation
function get_ActiveVillage()
{
    $('#select_village option').remove();
    $('.overlay').css('display','block');
    $.ajax({
        type: 'POST',
        url:  base_url+'index.php/admin_settings/ajax_village_list',
        dataType: 'json',
        data:{'id_zone':$('#select_zone').val()},
        success: function(data) {
        var id_village='';
        $.each(data, function (key, data) {
        $('#select_village').append(
        $("<option></option>")
        .attr("value", data.id_village)
        .text(data.village_name)
        );
        
        $('#select_village').select2({
            placeholder:"Select Area",
            allowClear:true
        });
        $("#select_village").select2("val",(id_village!=null ?id_village:''));
        });
        
            $('.overlay').css('display','none');
        },
        error:function(error)  
        {
            $("div.overlay").css("display", "none"); 
        }
    });
}
function get_bill_wise_trns_list()
{
$("div.overlay").css("display", "block");
	var dt_range=($("#dt_range").val()).split('-');
     var branch_name=($('#branch_name').val()!='' && $('#branch_name').val()!=undefined ? $('#branch_name').val() : $("#branch_select option:selected").text());
	var company_name=$('#company_name').val();
	var title="<b><span style='font-size:15pt;margin-left:30%;'>"+company_name+"</span></b><b><span style='font-size:12pt;'></span></b></br>"+"<span style=font-size:13pt;margin-left:25%;>&nbsp;&nbsp;BILL WISE DETAILED REPORT "+($("#branch_select").val()!='' ? "COST CENTER:"+branch_name+" ":'')+" From&nbsp;:&nbsp;"+dt_range[0]+" &nbsp;&nbsp;To Date&nbsp;:&nbsp;"+dt_range[1]+"</span>";
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/bill_wise_transcation/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'dt_range' :$("#dt_range").val(),'id_product':$('#prod_select').val(),'id_design' :$("#des_select").val(),'id_branch':($('#branch_filter').val()!='' && $('#branch_filter').val()!=undefined ? $('#branch_filter').val(): $("#branch_select").val()),'id_weight':$('#wt_select').val(),'id_village':$('#select_village').val(),'id_counter':$('#counter_sel').val()}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			 var list = data.list;
	 		$("#bill_list > tbody > tr").remove();  
	 		$('#bill_list').dataTable().fnClearTable();
    		$('#bill_list').dataTable().fnDestroy();	
			if (list!= null)
			{  	
				trHTML = ''; 
				tfootHTML = '';
				var tot_ret_amt         =0;
				var tot_net_amt         =0;
				var total_cash_amt      =0;
				var total_card_amt=0;
				var total_net_banking_amt   =0;
				var total_chq_amt       =0;
				var total_adv_adj_amt   =0;
				var total_chit_adj_amt  =0;
				var total_adv_adj  =0;
				var tot_due_amt  =0;
				var tot_due_amt  =0;
				var tot_gift_adj=0;
				var advance_paid_amt=0;
				var handling_charges=0;
				
				
				$.each(list, function (i, bill) { 
				    
				total_adv_adj+=parseFloat(bill.order_adj_amt+bill.adv_adj_amt); // Order adj + general adj
				
				tot_ret_amt+= parseFloat(bill.tot_ret_amt);  //tot sales return
				
				handling_charges+=parseFloat(bill.handling_charges);
				
				if(bill.is_credit==1)
				{
				    tot_due_amt+=parseFloat(bill.due_amt);
				}
				
				
				//total_chit_adj_amt+=parseFloat(bill.bill_wise_details.utilized_amt);
				
				//tot_gift_adj+=parseFloat(bill.bill_wise_details.gift_voucher_amt);
				
				var taxable_amt         =0;
				var tot_taxable_amt     =0;
				total_bill_amount       =0;
				total_bill_tax_value    =0;
				total_advance_amt       =0;
				cash_amt                =0;
				card_amt                =0;
				chq_amt                 =0;
				tot_sales_ret           =0;
				net_banking_amt         =0;
				
						$.each(bill.sale_details, function (idx, item) {
						    
						    taxable_amt=parseFloat(item.item_cost-item.item_total_tax);
						    
						    tot_taxable_amt+=parseFloat(item.item_cost-item.item_total_tax);
						    
						    advance_paid_amt+=parseFloat(item.advance_amt!=undefined ?item.advance_amt :0) 
						   
						    var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+item.bill_id;
						    
							trHTML += '<tr>' +
							'<td>'+ '<a href='+url+' target="_blank">'+item.bill_no+'</td>'
							+'<td>'+bill.bill_date+'</td>'
							+'<td>'+bill.customer_name+'</td>'
							+'<td>'+bill.mobile+'</td>'
							+'<td>'+bill.village_name+'</td>'
							+'<td>'+item.product_name+'</td>'
							+'<td>'+(item.bill_type==5 ? 'Order Advance'+'-'+item.order_no: item.tag_id)+'</td>'
							+'<td>'+(item.piece!=undefined ? item.piece:'')+'</td>'
							+'<td>'+(item.net_wt!=undefined ? item.net_wt:'')+'</td>'
							+'<td>'+(item.bill_discount!=undefined ? item.bill_discount:'')+'</td>'
							+'<td>'+parseFloat((taxable_amt!=undefined ? taxable_amt:0)).toFixed(2)+'</td>'
							+'<td>'+parseFloat((item.total_cgst!=undefined ?item.total_cgst :0)).toFixed(2)+'</td>'
							+'<td>'+parseFloat((item.total_sgst!=undefined ?item.total_sgst :0)).toFixed(2)+'</td>'
							+'<td>'+parseFloat((item.total_igst!=undefined ? item.total_igst:0)).toFixed(2)+'</td>'
							+'<td><strong>'+parseFloat((item.item_cost!=undefined ? item.item_cost:0)).toFixed(2)+'</strong></td>'
							+'<td></td>'
							+'<td></td>'
							+'<td></td>'
							+'<td></td>'
							+'<td>'+parseFloat(item.advance_amt!=undefined ?item.advance_amt :0).toFixed(2)+'</td>'
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
							'</tr>';
						total_bill_amount +=parseFloat((item.bill_type==5 ?item.tot_bill_amount:item.item_cost));
						tot_net_amt +=parseFloat((item.bill_type==5 ?item.tot_bill_amount:item.item_cost));
						});	
						$.each(bill.old_sales_details, function (idx, item) {
						    var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+item.bill_id;
							trHTML += '<tr>' +
							'<td>'+ '<a href='+url+' target="_blank">'+item.bill_no+'</td>'
							+'<td></td>'
							+'<td></td>'
							+'<td></td>'
							+'<td></td>'
							+'<td>Purchase</td>'
							+'<td>'+item.pur_ref_no+'</td>'
							+'<td></td>'
							+'<td></td>'
							+'<td></td>'
							+'<td></td>'
							+'<td></td>'
							+'<td></td>'
							+'<td></td>'
							+'<td></td>'
							+'<td>'+item.gross_wt+'</td>'
							+'<td>'+item.amount+'</td>'
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
							'</tr>';
						total_bill_amount -=parseFloat(item.amount);
						tot_net_amt -=parseFloat(item.amount);
						});	
						
						$.each(bill.pay_details, function (idx, item) {
							if(item.payment_mode=='Cash')
							{
								cash_amt+=parseFloat(item.payment_amount);
								total_cash_amt+=parseFloat(item.payment_amount);
							}
							if(item.payment_mode=='CC' || item.payment_mode=='DC')
							{
								card_amt+=parseFloat(item.payment_amount);
								total_card_amt+=parseFloat(item.payment_amount);
							}
							if(item.payment_mode=='CHQ')
							{
								chq_amt+=parseFloat(item.payment_amount);
								total_chq_amt+=parseFloat(item.payment_amount);
							}
							if(item.payment_mode=='NB')
							{
								net_banking_amt+=parseFloat(item.payment_amount);
								total_net_banking_amt+=parseFloat(item.payment_amount);
							}
						});	
						var net_amount=parseFloat((total_bill_amount)-parseFloat(bill.tot_ret_amt)+parseFloat(bill.handling_charges)+parseFloat(bill.advance_deposit)).toFixed(2);
						var final_amount=parseFloat(parseFloat(cash_amt)+parseFloat(card_amt)+parseFloat(chq_amt)+parseFloat(net_banking_amt)+parseFloat(bill.order_adj_amt)+parseFloat(bill.adv_adj_amt)+parseFloat(bill.utilized_amt)+parseFloat(bill.gift_voucher_amt)).toFixed(2)
						var amount_difference=parseFloat(net_amount)-parseFloat(final_amount);
						trHTML += '<tr style="font-weight:bold;">' +
							+'<td></td>'
							+'<td></td>'
							+'<td></td>'
							+'<td></td>'
							+'<td></td>'
							+'<td></td>'
							+'<td>Total</td>'
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
							+'<td>'+parseFloat(bill.tot_ret_amt).toFixed(3)+'</td>'
							+'<td>'+parseFloat((bill.is_credit=1 ? bill.due_amt:0)).toFixed(2)+'</td>'
						    +'<td>'+parseFloat(bill.advance_deposit)+'</td>'
							+'<td>'+parseFloat(bill.handling_charges).toFixed(2)+'</td>'
							+'<td style="width:2px;">'+net_amount+'</td>'
							+'<td>'+parseFloat(cash_amt).toFixed(2)+'</td>'
							+'<td>'+parseFloat(card_amt).toFixed(2)+'</td>'
							+'<td>'+parseFloat(chq_amt).toFixed(2)+'</td>'
							+'<td>'+parseFloat(net_banking_amt).toFixed(2)+'</td>'
							+'<td>'+parseFloat(bill.order_adj_amt+bill.adv_adj_amt).toFixed(2)+'</td>'
							+'<td>'+parseFloat(bill.utilized_amt).toFixed(2)+'</td>'
							+'<td>'+parseFloat(bill.gift_voucher_amt).toFixed(2)+'</td>'
							+'<td style="width:2px;">'+final_amount+'</td>'
							'</tr>';					
					});
				trHTML += '<tr style="font-weight:bold;">' +
							+'<td></td>'
							+'<td></td>'
							+'<td></td>'
							+'<td></td>'
							+'<td></td>'
							+'<td></td>'
							+'<td>GrandTotal</td>'
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
							+'<td>'+tot_ret_amt+'</td>'
							+'<td>'+parseFloat(tot_due_amt).toFixed(2)+'</td>'
							+'<td></td>'
							+'<td>'+parseFloat(handling_charges).toFixed(2)+'</td>'
							+'<td style="width:2px;">'+parseFloat((tot_net_amt)-parseFloat(tot_ret_amt)-parseFloat(tot_due_amt)+parseFloat(advance_paid_amt)+parseFloat(handling_charges)).toFixed(2)+'</td>'
							+'<td>'+parseFloat(total_cash_amt).toFixed(2)+'</td>'
							+'<td>'+parseFloat(total_card_amt).toFixed(2)+'</td>'
							+'<td>'+parseFloat(total_chq_amt).toFixed(2)+'</td>'
							+'<td>'+parseFloat(total_net_banking_amt).toFixed(2)+'</td>'
							+'<td>'+parseFloat(total_adv_adj).toFixed(2)+'</td>'
							+'<td>'+parseFloat(total_chit_adj_amt).toFixed(2)+'</td>'
							+'<td>'+parseFloat(tot_gift_adj).toFixed(2)+'</td>'
							+'<td style="width:2px;">'+parseFloat(parseFloat(total_cash_amt)+parseFloat(total_card_amt)+parseFloat(total_chq_amt)+parseFloat(total_net_banking_amt)+parseFloat(total_adv_adj)+parseFloat(total_chit_adj_amt)+parseFloat(tot_gift_adj)+parseFloat(tot_gift_adj)).toFixed(2)+'</td>'
							'</tr>';
			    $('#bill_list > tbody').html(trHTML);
			     // Check and initialise datatable
			     
				if ( ! $.fn.DataTable.isDataTable( '#bill_list' ) ) { 
					oTable = $('#bill_list').dataTable({ 
					"bSort": false, 
					"bInfo": true, 
					"scrollX":'100%',  
					"dom": 'lBfrtip',
					"paging":false,
                    "fixedHeader": true,
        			"buttons": [
        			
					{
						extend: 'print',
						footer: true,
						title: '',
						messageTop: title,
						exportOptions: {
                            columns: ':visible'
                        },
						customize: function ( win ) {
						$(win.document.body).find( 'table' )
						.addClass( 'compact' )
				    	.css('font-size','12px')
						.css('font-family','sans-serif');
						},
					},
					'colvis',
					
					{
						extend:'excel',
						footer: true,
						title: 'Bill Wise Transcation List',
					}
					], 
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
//bill wise transcation
//home bills
function get_home_bill_sales()
{
    var dt_range=($("#dt_range").val()).split('-');
    var branch_name=($('#branch_name').val()!='' && $('#branch_name').val()!=undefined ? $('#branch_name').val() : $("#branch_select option:selected").text());
	var company_name=$('#company_name').val();
	var title="<b><span style='font-size:15pt;margin-left:32%;'>"+company_name+"</span></b><b><span style='font-size:15pt;'></span></b></br>"+"<span style=font-size:13pt;margin-left:20%;>&nbsp;&nbsp;HOME BILL REPORT &nbsp;"+($("#branch_select").val()!='' ? "Cost Center:"+branch_name+" ":'')+"From&nbsp;:&nbsp;"+dt_range[0]+" &nbsp;&nbsp;- "+dt_range[1]+"</span>";
	
	$("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/home_bill/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'dt_range' :$("#dt_range").val(),'id_branch':($('#branch_filter').val()!='' && $('#branch_filter').val()!=undefined ? $('#branch_filter').val(): $("#branch_select").val())}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			 	$("div.overlay").css("display", "none"); 
			 	var list = data.list;
				var oTable = $('#bill_list').DataTable();
				oTable.clear().draw();				  
				if (list!= null && list.length > 0)
				{  	
					oTable = $('#bill_list').dataTable({
						"bDestroy": true,
		                "bInfo": true,
		                "bFilter": true,
						"order": [[ 0, "desc" ]],
		                "scrollX":'100%',
		                "bSort": true,
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
									extend:'excel',
									footer: true,
								    title: title, 
								    customize: function ( win ) {
										$(win.document.body).find( 'table' )
											.addClass( 'compact' )
											.css( 'font-size', 'inherit' );
									},
								  }
								 ],
						"aaData": list,
						"aoColumns": [	{ "mDataProp": "bill_no" },
										{ "mDataProp": "bill_date" },
										{ "mDataProp": "product_name" },
										{ "mDataProp": "design_name" },
										{ "mDataProp": "tag_id" },
										{ "mDataProp": "piece" },
										{ "mDataProp": "gross_wt" },
										{ "mDataProp": "net_wt" },
										{ "mDataProp": "item_total_tax" },
										{ "mDataProp": "item_cost" },
										{ "mDataProp": "st_price" },
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
								piece = api
								.column(5)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(5).footer()).html(parseFloat(piece).toFixed(2));
								
								gross_wt = api
								.column(6)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(6).footer()).html(parseFloat(gross_wt).toFixed(2));
								
								net_wt = api
								.column(7)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(7).footer()).html(parseFloat(net_wt).toFixed(2)); 
								item_total_tax = api
								.column(8)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(8).footer()).html(parseFloat(item_total_tax).toFixed(2)); 
								item_cost = api
								.column(9)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(9).footer()).html(parseFloat(item_cost).toFixed(2)); 
						} 
						}else{
							 var api = this.api(), data; 
							 $(api.column(5).footer()).html('');  
							 $(api.column(6).footer()).html('');  
							 $(api.column(7).footer()).html('');  
							 $(api.column(8).footer()).html('');  
							 $(api.column(9).footer()).html('');  
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
//home bills
//order advance details
function set_order_advance()
{
	var branch_name=$("#branch_select option:selected").text();
	var company_name=$('#company_name').val();
	var title="<b><span style='font-size:15pt;margin-left:50%;'>"+company_name+"</span></b><b><span style='font-size:15pt;'></span></b></br>"+"<span style=font-size:13pt;margin-left:25%;>&nbsp;&nbsp;Order Advance Details "+($("#branch_select").val()!='' ? "Cost Center:"+branch_name+" ":'')+"</span>";
	
	$("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/order_advance/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'id_branch':$("#branch_select").val(),'dt_range' :$("#dt_range").val()}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			 	$("div.overlay").css("display", "none"); 
			 	var list = data.list;
				var oTable = $('#adv_list').DataTable();
				oTable.clear().draw();				  
				if (list!= null && list.length > 0)
				{  	
					oTable = $('#adv_list').dataTable({
						"bDestroy": true,
		                "bInfo": true,
		                "bFilter": true,
						"order": [[ 0, "desc" ]],
		                "scrollX":'100%',
		                "bSort": true,
		                "dom": 'lBfrtip',
		                "buttons": [
								 {
								   extend: 'print',
								   footer: true,
								   title: title,
								   customize: function ( win ) {
										$(win.document.body).find( 'table' )
											.addClass( 'compact' )
											.css( 'font-size', 'inherit' );
									},
								 },
								 {
									extend:'excel',
									footer: true,
								    title: title, 
								  }
								 ],
						"aaData": list,
						"aoColumns": [	{ "mDataProp": "branch_name" },
										{ "mDataProp": function ( row, type, val, meta ){
										  var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+row.bill_id;
											return '<a href='+url+' target="_blank">'+row.bill_no;
										}
										},
										{ "mDataProp": "bill_date" },
										{ "mDataProp": "cus_name" },
										{ "mDataProp": "mobile" },
										{ "mDataProp": "order_no" },
										{ "mDataProp": "advance_amount" },
										{ "mDataProp": "advance_weight" },
										{ "mDataProp": "rate_per_gram" },
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
//order advance details
//Estimation referral
function set_estimation_referral()
{
	$("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/est_referral/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'id_branch':$("#branch_select").val(),'dt_range' :$("#dt_range").val(),'id_metal':$('#metal').val(),'id_ret_category':$('#category').val(),'id_product':$('#prod_select').val()}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			 	var list = data.list;
				var oTable = $('#ref_list').DataTable();
				oTable.clear().draw();				  
				if (list!= null && list.length > 0)
				{  	
					oTable = $('#ref_list').dataTable({
						"bDestroy": true,
		                "bInfo": true,
		                "bFilter": true,
						"order": [[ 0, "desc" ]],
		                "scrollX":'100%',
		                "bSort": true,
		                "dom": 'lBfrtip',
		                
		                "lengthMenu": [[-1, 25, 50, 100, 250], ["All" ,25, 50, 100, 250]],
		                "buttons": [
								 {
								   extend: 'print',
								   footer: true,
								   title: "Employee Sales Details",
								   customize: function ( win ) {
										$(win.document.body).find( 'table' )
											.addClass( 'compact' )
											.css( 'font-size', 'inherit' );
									},
								 },
								 {
									extend:'excel',
									footer: true,
								   title: "Employee Sales Details",
								  }
								 ],
						"aaData": list,
						"aoColumns": [	
										{ "mDataProp": "branch_name" },
										{ "mDataProp": "emp_name" },
										{ "mDataProp": "emp_code" },
										{ "mDataProp": "tot_est" },
										{ "mDataProp": "piece" },
										{ "mDataProp": "gross_wt" },
										{ "mDataProp": "less_wt" },
										{ "mDataProp": "net_wt" },
										{ "mDataProp": "dia_wt" },
										{ "mDataProp": "tot_bill_amount" },
										{
                                        "mDataProp": null,
                                        "sClass": "control center", 
                                        "sDefaultContent":  '<span class="drill-val"><i class="fa fa-chevron-circle-down text-teal"></i></span>'
                                        },
									],
					});	
			
                    
                    var anOpen =[]; 
            		$(document).on('click',"#ref_list .control", function(){ 
            		   var nTr = this.parentNode;
            		   var i = $.inArray( nTr, anOpen );
            		 
            		   if ( i === -1 ) { 
            				$('.drill-val', this).html('<i class="fa fa-chevron-circle-up text-teal"></i>'); 
            				oTable.fnOpen( nTr, fnFormatRowDetails(oTable, nTr), 'details' );
            				anOpen.push( nTr ); 
            		    }
            		    else { 
            				$('.drill-val', this).html('<i class="fa fa-chevron-circle-down text-teal"></i>');
            				oTable.fnClose( nTr );
            				anOpen.splice( i, 1 );
            		    }
            		} );
		
				}
				$("div.overlay").css("display", "none"); 
			  },
			  error:function(error)  
			  {
				 $("div.overlay").css("display", "none"); 
			  }	 
	      });
}
function fnFormatRowDetails( oTable, nTr )
{
  var oData = oTable.fnGetData( nTr );
  var rowDetail = '';
  var prodTable = 
     '<div class="innerDetails">'+
      '<table class="table table-responsive table-bordered text-center table-sm">'+ 
        '<tr class="bg-teal">'+
        '<th>S.No</th>'+ 
        '<th>Product Name  </th>'+
        '<th>Pcs</th>'+
        '<th>Weight</th>'+
        '<th>Amount</th>'+
        '</tr>';
    var bill_details = oData.bill_details; 
    var total_amount=0;
  $.each(bill_details, function (idx, val) {
  	prodTable += 
        '<tr class="prod_det_btn">'+
        '<td>'+parseFloat(idx+1)+'</td>'+
        '<td>'+val.product_name+'</td>'+
        '<td>'+val.piece+'</td>'+
		'<td>'+val.gross_wt+'</td>'+
        '<td>'+val.item_cost+'</td>'+
        '</tr>'; 
    total_amount +=parseFloat(val.item_cost);
  }); 
  prodTable += 
        '<tr class="prod_det_btn" style="font-weight:bold;">'+
        '<td colspan="4">Total</td>'+
        '<td>'+parseFloat(total_amount).toFixed(2)+'</td>'+
        '</tr>'; 
  rowDetail = prodTable+'</table></div>';
  return rowDetail;
}
//Estimation referral
// Other Issue
function set_other_issue()
{
	$("div.overlay").css("display", "block");
	var dt_range=($("#dt_range").val()).split('-');
	var branch_name=$("#branch_select option:selected").text();
	var company_name=$('#company_name').val();
	var title="<b><span style='font-size:15pt;margin-left:50%;'>"+company_name+"</span></b><b><span style='font-size:15pt;'></span></b></br>"+"<span style=font-size:13pt;margin-left:25%;>&nbsp;&nbsp;Other Issue Report "+($("#branch_select").val()!='' ? "Cost Center:"+branch_name+" ":'')+" From&nbsp;:&nbsp;"+dt_range[0]+" &nbsp;&nbsp;To Date&nbsp;:&nbsp;"+dt_range[1]+"</span>";
	my_Date = new Date();
	$.ajax({
		url	:base_url+ "index.php/admin_ret_reports/other_issue/ajax?nocache=" + my_Date.getUTCSeconds(),
		data: ( {'dt_range' :$("#dt_range").val(),'id_product':$('#prod_select').val()}),
		dataType:"JSON",
		type:"POST",
		success:function(data){
			var list = data.list;
	 		$("#other_issue_list > tbody > tr").remove();  
	 		$('#other_issue_list').dataTable().fnClearTable();
    		$('#other_issue_list').dataTable().fnDestroy();	
			if (list!= null)
			{  	
				var trHTML = ''; 
				var pieces	= 0;
				var gross_wt= 0;
				var net_wt  = 0;
				$.each(list, function (i, item) {   
						trHTML += '<tr>'
						+'<td>'+item.product+'</td>'
						+'<td>'+item.tag_id+'</td>'
						+'<td>'+item.piece+'</td>'
						+'<td>'+item.gross_wt+'</td>'
						+'<td>'+item.net_wt+'</td>'
						+'<td>'+item.from_branch+'</td>'
						+'<td>'+item.to_branch+'</td>' 
						+'</tr>'; 
					pieces +=parseFloat(item.piece);  
					gross_wt +=parseFloat(item.gross_wt);  
					net_wt +=parseFloat(item.net_wt);  
				});
				trHTML += '<tr style="font-weight:bold;">'
						+'<td>TOTAL</td>'
						+'<td></td>'
						+'<td>'+pieces+'</td>'
						+'<td>'+gross_wt+'</td>'
						+'<td>'+net_wt+'</td>'
						+'<td></td>' 
						+'<td></td>'
						+'</tr>';
				
			    $('#other_issue_list > tbody').html(trHTML);
			     // Check and initialise datatable
				if ( ! $.fn.DataTable.isDataTable( '#other_issue_list' ) ) { 
					oTable = $('#other_issue_list').dataTable({ 
					"bSort": false, 
					"bInfo": true, 
					"scrollX":'100%',  
					"dom": 'lBfrtip',
					"paging":false,
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
						extend:'excel',
						footer: true,
						title: 'Other Issue Report',
					}
					], 
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
// Other Issue
 function get_branchname_list()
 {	        
                //$(".overlay").css('display','block');	
             	$('#branch_scan_filter option').remove();
             	$('#branch_select option').remove();
             	$.ajax({		
                 	type: 'GET',		
                 	url: base_url+'index.php/branch/branchname_list',		
                 	dataType:'json',		
                 	success:function(data){				 
                	 	var id_branch =  $('#id_branch').val();	
                	  $.each(data.branch, function (key, item) {
                    	 	$("#branch_scan_filter,#branch_select").append(						
                    	 	$("<option></option>")						
                    	 	.attr("value", item.id_branch)						  						  
                    	 	.text(item.name )						  					
                    	 	);			   											
                     	});						
                     	$("#branch_scan_filter,#branch_select").select2({			    
                    	 	placeholder: "Select Branch",			    
                    	 	allowClear: true		    
                     	});		
                     	
                     	$("#branch_select").select2("val",(id_branch!='' && id_branch>0?id_branch:''));	 
    			
                     	if($("#branch_scan_filter").length){
    						$("#branch_scan_filter").select2("val",(id_branch!='' && id_branch>0?id_branch:''));	 
    					}   
                     	$(".overlay").css("display", "none");			
                 	}	
                }); 
}
function set_gift_voucher_list()
{
    $("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/gift_voucher/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'id_branch':$("#branch_select").val(),'dt_range' :$("#dt_range").val(),'report_type':$('#report_type').val()}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			 	var list = data.list;
			 	if($('#report_type').val()==1)
			 	{
			 	    $('#issued').css('display','block');
			 	    $('#received').css('display','none');
    				var oTable = $('#gift_issued').DataTable();
    				oTable.clear().draw();				  
    				if (list!= null && list.length > 0)
    				{  	
    					oTable = $('#gift_issued').dataTable({
    						"bDestroy": true,
    		                "bInfo": true,
    		                "bFilter": true,
    		                "scrollX":'100%',
    		                "bSort": false,
    		                "dom": 'lBfrtip',
    		                "order": [[ 0, "desc" ]],
    		                "buttons": [
    								 {
    								   extend: 'print',
    								   footer: true,
    								   title: "Stock Checking Tag Scan Report"+($("#branch_select").val()!='' ?'- '+$("#branch_select option:selected").text() :''),
    								   customize: function ( win ) {
    										$(win.document.body).find( 'table' )
    											.addClass( 'compact' )
    											.css( 'font-size', 'inherit' );
    									},
    								 },
    								 {
    									extend:'excel',
    									footer: true,
    								   title: "Stock Checking Tag Scan Report"+($("#branch_select").val()!='' ?'- '+$("#branch_select option:selected").text() :''),
    								  }
    								 ],
    						"aaData": list,
    						"aoColumns": [	
    										{ "mDataProp": function ( row, type, val, meta )
    										{ 
    										    if(row.status==0)
    										    {
    										        return '<input type="checkbox" class="id_gift_card" name="id_gift_card[]" value="'+row.id_gift_card+'"/>'+row.id_gift_card;
    										    }else{
    										        return row.id_gift_card;
    										    }
                    		                	
                    		                }},
    										{ "mDataProp": "code" },
    										{ "mDataProp": "branch_name" },
    										{ "mDataProp": "date" },
    										{ "mDataProp": "owned_by" },
    										{ "mDataProp": "amount" },
    										{ "mDataProp": "gift_status" },
    									],
    					});
    				}
			    }
			    else
			    {
			        $('#issued').css('display','none');
			 	    $('#received').css('display','block');
			        var oTable = $('#gift_reeived').DataTable();
    				oTable.clear().draw();				  
    				if (list!= null && list.length > 0)
    				{  	
    					oTable = $('#gift_reeived').dataTable({
    						"bDestroy": true,
    		                "bInfo": true,
    		                "bFilter": true,
    						"order": [[ 0, "desc" ]],
    		                "scrollX":'100%',
    		                "bSort": true,
    		                "dom": 'lBfrtip',
    		                "buttons": [
    								 {
    								   extend: 'print',
    								   footer: true,
    								   title: "Stock Checking Tag Scan Report"+($("#branch_select").val()!='' ?'- '+$("#branch_select option:selected").text() :''),
    								   customize: function ( win ) {
    										$(win.document.body).find( 'table' )
    											.addClass( 'compact' )
    											.css( 'font-size', 'inherit' );
    									},
    								 },
    								 {
    									extend:'excel',
    									footer: true,
    								   title: "Stock Checking Tag Scan Report"+($("#branch_select").val()!='' ?'- '+$("#branch_select option:selected").text() :''),
    								  }
    								 ],
    						"aaData": list,
    						"aoColumns": [	
    						                { "mDataProp": "branch_name" },
    						                { "mDataProp": function ( row, type, val, meta )
    										{ 
    										    var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+row.bill_id;
    										    return '<a href='+url+' target="_blank">'+row.bill_no;
    										}},
    										{ "mDataProp": "code" },
    										{ "mDataProp": "bill_date" },
    										{ "mDataProp": "owned_by" },
    										{ "mDataProp": "gift_voucher_amt" },
    									],
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
$('#select_all').click(function(event) {
    $("tbody tr td input[type='checkbox']").prop('checked', $(this).prop('checked'));
    event.stopPropagation();
});
$('#cancel_gift').on('click',function(){
    if($("input[name='id_gift_card[]']:checked").val())
    {
         var selected = [];
        $("#gift_issued tbody tr").each(function(index, value){
            if($(value).find("input[name='id_gift_card[]']:checked").is(":checked"))
            {
				transData = { 
				'id_gift_card': $(value).find(".id_gift_card").val(),
				}
				selected.push(transData);	
			}
        });
        console.log(selected);
        update_gift_status(selected);
    }else{
        alert('Please Select Any One Gift');
    }
});
function update_gift_status(req_data)
{
	my_Date = new Date();
	$("div.overlay").css("display", "block"); 
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/update_gift_status?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),
			 data:  {'req_data':req_data},
			 type:"POST",
			 async:false,
			 	  success:function(data){
			            set_gift_voucher_list();
			   			$("div.overlay").css("display", "none"); 
				  },
				  error:function(error)  
				  {
					 $("div.overlay").css("display", "none"); 
				  }	 
		  });
}
function get_order_status()
{
    my_Date = new Date();
	$.ajax({
		type: 'GET',
		url: base_url+'index.php/admin_ret_reports/order_status/order_status?nocache='+my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),
		dataType:'json',
		success:function(data){
			var id =  $("#order_status").val();
		
		   $.each(data,function (key, item) {
			   		$('#order_status').append(
						$("<option></option>")
						  .attr("value", item.id_order_msg)
						  .text(item.order_status)
					);
			});
			
		    $('#order_status').select2("val",'');
		}
	});
}
function set_order_status_report()
{
  var urlParams = new URLSearchParams(window.location.search);
  var order_staus = urlParams.get("order_staus");
  var id_branch = urlParams.get("id_branch");
  var filter_by = urlParams.get("filter_type");
  var from_date = urlParams.get("from_date");
  var to_date = urlParams.get("to_date");
 
    $("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/order_status/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'id_branch':(id_branch!=undefined && id_branch!='' ? id_branch: $("#branch_select").val() ),'from_date' :(from_date!=undefined ? from_date:$("#rpt_payments1").text()),'to_date':(to_date!=undefined ? to_date:$("#rpt_payments2").text()),'orderstatus':(order_staus!=undefined && order_staus!='' ? order_staus:$('#order_status').val()),'filter_by':(filter_by!=undefined && filter_by!='' ? filter_by:$('#filter_by').val())}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			 	var list = data.list;
			 	    $('#issued').css('display','block');
			 	    $('#received').css('display','none');
    				var oTable = $('#order_detail').DataTable();
    				oTable.clear().draw();				  
    				if (list!= null && list.length > 0)
    				{  	
    					oTable = $('#order_detail').dataTable({
    						"bDestroy": true,
    		                "bInfo": true,
    		                "bFilter": true,
    		                "scrollX":'100%',
    		                "bSort": true,
    		                "dom": 'lBfrtip',
    		                "buttons": [
    								 {
    								   extend: 'print',
    								   footer: true,
    								   title: "Order Status Report",
    								   customize: function ( win ) {
    										$(win.document.body).find( 'table' )
    											.addClass( 'compact' )
    											.css( 'font-size', 'inherit' );
    									},
    								 },
    								 {
    									extend:'excel',
    									footer: true,
    								   title: "Order Status Report",
    								  }
    								 ],
    						"aaData": list,
    						"aoColumns": [	
    							
    										{ "mDataProp": "branch_name" },
    										{ "mDataProp": "order_type" },
    										{ "mDataProp": "order_no" },
    										{ "mDataProp": function ( row, type, val, meta ){
                                                    var url = base_url+'index.php/admin_ret_purchase/get_karigar_acknowladgement/'+row.purord_id;
    										        return '<a href='+url+' target="_blank">'+row.pur_no;
    										},
    										},
    										
    										{ "mDataProp": "tag_code" },
    										
    										{ "mDataProp": function ( row, type, val, meta ){
                                                return '<span class="badge bg-'+row.color+'">'+row.order_status+'</span>';
    										},
    										},
    										{ "mDataProp": function ( row, type, val, meta ){
                                                if(row.bill_no!='')
                                                {
                                                    var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+row.bill_id;
    										        return '<a href='+url+' target="_blank">'+row.bill_no;
                                                }else{
                                                    return '-';
                                                }
    										},
    										},
    										
    										{ "mDataProp": "karigar_name" },
    										{ "mDataProp": "order_date" },
    										{ "mDataProp": "delivered_date" },
    										{ "mDataProp": "cus_due_date" },
    										{ "mDataProp": "smith_due_date" },
    										{ "mDataProp": "cus_name" },
    										{ "mDataProp": "mobile" },
    										{ "mDataProp": "product_name" },
    										{ "mDataProp": "design_name" },
    										{ "mDataProp": "sub_design_name" },
    										{ "mDataProp": "totalitems" },
    										{ "mDataProp": "weight" },
    										{ "mDataProp": "wast_percent" },
    										{ "mDataProp": "mc" },
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
// tag history
/*$('#tag_number').on('keyup',function(){
    getSearchTags(this.value);
});*/
function getSearchTags(searchTxt){
	my_Date = new Date();
	$.ajax({
        url: base_url+'index.php/admin_ret_reports/getTaggingBySearch/?nocache=' + my_Date.getUTCSeconds(),             
        dataType: "json", 
        method: "POST", 
        data: {'searchTxt': searchTxt}, 
        success: function (data) {
			$("#tag_number").autocomplete(
			{
				source: data,
				select: function(e, i)
				{ 
					e.preventDefault(); 
					$('#tag_id').val(i.item.value);
					set_tag_history();
					
				},
				change: function (event, ui) {
					if (ui.item === null) {
					
					}
			    },
				response: function(e, i) {
				    $('#tag_id').val('');
		            // ui.content is the array that's about to be sent to the response callback.
		        },
				 minLength: 1,
			});
        }
     });
}
function set_tag_history()
{
    
    $("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/tag_history/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'tag_id':$("#tag_number").val(),'old_tag_id':$("#old_tag_number").val()}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			 	var list = data.list;
    				var oTable = $('#tag_history').DataTable();
    				oTable.clear().draw();				  
    				if (list!= null && list.length > 0)
    				{  	
    					oTable = $('#tag_history').dataTable({
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
    								   title: "Tag History Report",
    								   customize: function ( win ) {
    										$(win.document.body).find( 'table' )
    											.addClass( 'compact' )
    											.css( 'font-size', 'inherit' );
    									},
    								 },
    								 {
    									extend:'excel',
    									footer: true,
    								    title: "Tag History Report",
    								  }
    								 ],
    						"aaData": list,
    						"aoColumns": [	
    							
    										{ "mDataProp": "tag_id" },
    										{ "mDataProp": "tag_code" },
    										{ "mDataProp": "old_tag_id" },
    										{ "mDataProp": "tag_date" },
    										{ "mDataProp": "supplier_name" },
    										{ "mDataProp": "product_name" },
    										{ "mDataProp": "design_name" },
    										{ "mDataProp": "sub_design_name" },
    										
    										{ "mDataProp": "gross_wt" },
    										{ "mDataProp": "net_wt" },
    										{ "mDataProp": function ( row, type, val, meta )
    										{ 
    										    if(row.status==1)
    										    {
    										        return '<span class="badge bg-green">'+row.tag_status+'</span>';
    										    }else
    										    {
    										        return '<span class="badge bg-red">'+row.tag_status+'</span>';
    										    }
                    		                }},
                    		                { "mDataProp": "emp_name" },
    										{
                                                "mDataProp": null,
                                                "sClass": "control center", 
                                                "sDefaultContent":  '<span class="drill-val"><i class="fa fa-chevron-circle-down text-teal"></i></span>'
                                            },
    									  ],
    					});
    					
        				var anOpen =[]; 
                		$(document).on('click',"#tag_history .control", function(){ 
                		   var nTr = this.parentNode;
                		   var i = $.inArray( nTr, anOpen );
                		 
                		   if ( i === -1 ) { 
                				$('.drill-val', this).html('<i class="fa fa-chevron-circle-up text-teal"></i>'); 
                				oTable.fnOpen( nTr, fnFormatRowTagDetails(oTable, nTr), 'details' );
                				anOpen.push( nTr ); 
                		    }
                		    else { 
                				$('.drill-val', this).html('<i class="fa fa-chevron-circle-down text-teal"></i>');
                				oTable.fnClose( nTr );
                				anOpen.splice( i, 1 );
                		    }
                		} );
    				}
				$("div.overlay").css("display", "none"); 
			  },
			  error:function(error)  
			  {
				 $("div.overlay").css("display", "none"); 
			  }	 
	      });
}
function fnFormatRowTagDetails( oTable, nTr )
{
  var oData = oTable.fnGetData( nTr );
  var rowDetail = '';
  var tot_row=0;
  var prodTable = 
     '<div class="innerDetails">'+
      '<table class="table table-responsive table-bordered text-center table-sm">'+ 
        '<tr class="bg-teal">'+
        '<th>S.No</th>'+ 
        '<th>Date</th>'+
        '<th>From Branch</th>'+
        '<th>To Branch</th>'+
        '<th>Status</th>'+
        '<th>Est No</th>'+
        '<th>Bill No</th>'+
        '</tr>';
    var tag_details = oData.tag_history; 
    var est_details = oData.est_details; 
    tot_row=tag_details.length;
  $.each(tag_details, function (idx, val) {
  
  	var ref_no = val.tag_id;
  	prodTable += 
        '<tr class="prod_det_btn">'+
        '<td>'+parseFloat(idx+1)+'</td>'+
        '<td>'+val.date+'</td>'+
        '<td>'+val.from_branch+'</td>'+
        '<td>'+val.to_branch+'</td>'+
        '<td>'+val.tag_status+'</td>'+
        '<td>-</td>'+
        '<td>-</td>'+
        '</tr>'; 
  }); 
   $.each(est_details, function (idx, val) {
    var bill_url = base_url+'index.php/admin_ret_billing/billing_invoice/'+val.bill_id;
  	var est_url = base_url+'index.php/admin_ret_estimation/generate_invoice//'+val.estimation_id;   
  	prodTable += 
        '<tr class="prod_det_btn">'+
        '<td>'+parseFloat(idx+tot_row+1)+'</td>'+
        '<td>'+val.date_add+'</td>'+
        '<td>-</td>'+
        '<td>-</td>'+
        '<td>'+(val.bill_no!='' ? 'Sold Out':'Estimated')+'</td>'+
        '<td><a href='+est_url+' target="_blank">'+val.esti_no+'</a></td>'+
        '<td><a href='+bill_url+' target="_blank">'+(val.bill_no!='' ? val.bill_no:'-')+'</a></td>'+
        '</tr>'; 
  }); 
  rowDetail = prodTable+'</table></div>';
  return rowDetail;
}
// tag history
//Monthly sales
function set_monthly_sales()
{
    var company_name=$('#company_name').val();
    var dt_range=($("#dt_range").val()).split('-');
    var title="<b><span style='font-size:15pt;margin-left:30%;'>"+company_name+"</span></b><b><span style='font-size:12pt;'></span></b></br>"+"<span style=font-size:13pt;margin-left:25%;>&nbsp;&nbsp;SALES REPORT  From&nbsp;:&nbsp;"+dt_range[0]+" &nbsp;&nbsp;To Date&nbsp;:&nbsp;"+dt_range[1]+"</span>";
    $("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/monthly_sales/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'dt_range' :$("#dt_range").val()}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			 	var list = data.list;
    				var oTable = $('#monthly_sales').DataTable();
    				oTable.clear().draw();				  
    				if (list!= null && list.length > 0)
    				{  	
    					oTable = $('#monthly_sales').dataTable({
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
    								    title: "Sales Report",
    								  }
    								 ],
    						"aaData": list,
    						"aoColumns": [	
    										{ "mDataProp": "branch_name" },
    										{ "mDataProp": "starting_bill" },
    										{ "mDataProp": "ending_bill" },
    										{ "mDataProp": "gold_sales" },
    										{ "mDataProp": "silver_sales" },
    										{ "mDataProp": "pur_starting_bill" },
    										{ "mDataProp": "pur_ending_bill" },
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
//Monthly sales
//Old Metal Profit and Loss
function get_old_metal_type()
{
    $('#karigar option').remove();
    $.ajax({
        type: 'GET',
        url: base_url+'index.php/admin_ret_reports/get_old_metal_type',
        dataType:'json',
        success:function(data){
        var id_karigar =  $("#karigar").val();
        $("#old_metal_type").select2(
        {
            placeholder:"Select Category",
            allowClear: true		    
        });
        $.each(data,function (key, item) {
            $('#old_metal_type').append(
            $("<option></option>")
            .attr("value", item.id_metal_type)
            .text(item.metal_type)
            );
        }); 
            $("#old_metal_type").select2("val",'');	 
        }
    });
}
    $("#branch_select").change(function() {
        var data = $("#branch_select").select2('data');		
        selectedValue = $(this).val(); 		
        $("#id_branch").val(selectedValue);
    });
    
    $("#old_metal_type").change(function() {
        var data = $("#old_metal_type").select2('data');		
        selectedValue = $(this).val(); 		
        $("#id_old_metal_type").val(selectedValue);
    });
function set_old_metal_analyse_table()
{
    
    console.log($('#sel_bran').data('old_metal_type'));
	$("div.overlay").css("display", "block"); 
	var dt_range=($("#dt_range").val()).split('-');
    var branch_name=($('#branch_name').val()!='' && $('#branch_name').val()!=undefined ? $('#branch_name').val() : $("#branch_select option:selected").text());
    var metal_type=$("#filter_metal option:selected").text();
	var company_name=$('#company_name').val();
	var title="<b><span style='font-size:15pt;margin-left:35%;'>"+company_name+"</span></b><b><span style='font-size:15pt;'></span></b></br>"+"<span style=font-size:13pt;margin-left:25%;>&nbsp;&nbsp;PURCHASE AND EXCHANGE "+($("#branch_filter").val()!='' ? "Cost Center:"+branch_name+"":'')+" "+($("#filter_metal").val()!='' ? "Metal Type:"+metal_type+"":'')+" &nbsp;From&nbsp;:&nbsp;"+dt_range[0]+" &nbsp;&nbsp;To Date&nbsp;:&nbsp;"+dt_range[1]+"</span>";
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/old_metal_analyse/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'metal' :$("#filter_metal").val(),'dt_range' :$("#dt_range").val(),'id_branch':$("#id_branch").val(),'old_metal_type':$('#id_old_metal_type').val()}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
		 		$("div.overlay").css("display", "none"); 
		 		var list = data.list.item_details; 
		 		var goldrate_24ct=data.list.metal_rate.goldrate_24ct;
		 	
		 		$("#old_metal_report > tbody > tr").remove();  
				$('#old_metal_report').dataTable().fnClearTable();
    		    $('#old_metal_report').dataTable().fnDestroy();
				if (list!= null )
				{  	
				    var return_details=data.list.return_details;
				    var partly_sale_det=data.list.partly_sale_det;
					trHTML = ''; 
					tfootHTML = ''; 
					var total_gross_wt = 0;
					var total_stone_wt = 0;
					var total_dust_wt = 0;
					var total_pure_wt = 0;
					var total_wast_wt = 0;
					var total_net_wt = 0;
					var total_rate = 0;
					var sales_ret_gwt=0;
					var sales_ret_nwt=0;
					var partly_sale_gwt=0;
					var partly_sale_nwt=0;
				    var total_purity_per=0;
				    var length=0;
				    var tot_length=0;
					
					
					//Old Metal Purchase
				
					$.each(list, function (i, category) { 
					      tot_length=tot_length+1;
					 	 var gross_wt = 0;
					 	 var stone_wt = 0;
					 	 var dust_wt = 0;
					 	 var pure_wt = 0;
					 	 var wast_wt = 0;
					 	 var net_wt = 0;
					 	 var rate = 0;
					 	 var purity_per=0;
					 	 var profit_per=0;
					 	 var tot_pur_per=0;
					 	 
					 	   trHTML += '<tr>' +
									'<td colspan="2"><strong>'+i+'</strong></td>' +
									'<td></td>' +
									'<td></td>' +
									'<td></td>' +
									'<td></td>' +
									'<td></td>' +
									'<td></td>' +
									'<td></td>' +
									'<td></td>' +
									'<td></th>' +
									'<td></td>' +
									'<td></td>' +
									'<td></td>' +							
									'</tr>';
									
						 $.each(category, function (idx, item) {
						     length=length+1;
						    
						      var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+item.bill_id;
						     if(item.goldrate_24ct>0)
						     {
						         purity_per=parseFloat(((item.rate/item.pure_wt)/item.goldrate_24ct)*100).toFixed(2);
						         tot_pur_per+=parseFloat(((item.rate/item.pure_wt)/item.goldrate_24ct)*100);
						     }
							trHTML += '<tr>' +
										
										'<td>' + item.bill_date + '</td>' +	
										'<td><a href='+url+' target="_blank">'+item.bill_no+'</a></td>'+
										'<td>' + item.gross_wt + '</td>' +
										'<td>' + item.stone_wt + '</td>' +
										'<td>' + item.dust_wt + '</td>' +
										'<td>' + item.pure_wt + '</td>' +
										'<td>' + item.wast_wt + '</td>' +
										'<td>' + item.net_wt + '</td>' +
										'<td>' + purity_per + '</td>' +
										'<td>' + item.rate_per_grm + '</td>' +
										'<td>' + item.goldrate_24ct + '</td>' +
										'<td>' + item.rate + '</td>' +
										'<td>' + item.esti_no + '</td>' +							
										'</tr>';
							gross_wt = parseFloat(gross_wt) + parseFloat(item.gross_wt);
							stone_wt = parseFloat(stone_wt) + parseFloat(item.stone_wt);
							dust_wt  = parseFloat(dust_wt) + parseFloat(item.dust_wt);
							pure_wt  = parseFloat(pure_wt) + parseFloat(item.pure_wt);
							wast_wt  = parseFloat(wast_wt) + parseFloat(item.wast_wt);
							net_wt   = parseFloat(net_wt) + parseFloat(item.net_wt);
							rate     = parseFloat(rate) + parseFloat(item.rate);
						 });
						    tot_pur_per=parseFloat(tot_pur_per/length);
						    trHTML += '<tr>' +
									'<td ><strong>SUB TOTAL</strong></td>' +
									'<td></td>' +
									'<td><strong>' + parseFloat(gross_wt).toFixed(3) + '</strong></td>' +
									'<td><strong>' + parseFloat(stone_wt).toFixed(3) + '</strong></td>' +
									'<td><strong>' + parseFloat(dust_wt).toFixed(3) + '</strong></td>' +
									'<td><strong>' + parseFloat(pure_wt).toFixed(3) + '</strong></td>' +
									'<td><strong>' + parseFloat(wast_wt).toFixed(3) + '</strong></td>' +
									'<td><strong>' + parseFloat(net_wt).toFixed(3) + '</strong></td>' +
									'<td><strong>' + parseFloat(tot_pur_per).toFixed(2) + '</strong></td>' +
									'<td></td>' +
									'<td></td>' +
									'<td><strong>' + parseFloat(rate).toFixed(2) + '</strong></th>' +
									'<td></td>' +
									'<td></td>' +
									'</tr>';
							 tfootHTML += '<tr>' +
							 	    '<td></td>' +
							 	    '<td></td>' +
									'<td></td>' +
									'<td></td>' +
									'<td colspan="2"><strong>'+i+'</strong></td>' +
									'<td>'+parseFloat(tot_pur_per).toFixed(2)+'</td>'+
									'<td></td>' +
									'<td></td>' +
									'<td></td>' +
									'<td></th>' +
									'<td></th>' +
									'<td></td>' +
									'<td></td>' +							
									'</tr>';
                            total_purity_per+=tot_pur_per;
							tot_pur_per=0;
						    length=0;
									
							total_gross_wt = total_gross_wt+gross_wt;
							total_stone_wt = total_stone_wt+stone_wt;
							total_dust_wt = total_dust_wt+dust_wt;
							total_pure_wt = total_pure_wt+pure_wt;
							total_wast_wt = total_wast_wt+wast_wt;
							total_net_wt = total_net_wt+net_wt;
							total_rate = total_rate+rate;
					 });
					 trHTML += '<tr>' +
									'<td><strong>Grand Total</strong></td>' +
									'<td></td>' +
									'<td><strong>' + parseFloat(total_gross_wt).toFixed(3) + '</strong></td>' +
									'<td><strong>' + parseFloat(total_stone_wt).toFixed(3) + '</strong></td>' +
									'<td><strong>' + parseFloat(total_dust_wt).toFixed(3) + '</strong></td>' +
									'<td><strong>' + parseFloat(total_pure_wt).toFixed(3) + '</strong></td>' +
									'<td><strong>' + parseFloat(total_wast_wt).toFixed(3) + '</strong></td>' +
									'<td><strong>' + parseFloat(total_net_wt).toFixed(3) + '</strong></td>' +
									'<td><strong>' + parseFloat(total_purity_per/tot_length).toFixed(2) + '</strong></td>' +
									'<td></td>' +
									'<td></td>' +		
									'<td><strong>' + parseFloat(total_rate).toFixed(2) + '</strong></th>' +
									'<td></td>' +
									'<td></td>' +							
									'</tr>'; 
									
					trHTML+=tfootHTML;
				    //Old Metal Purchase
				    
				    
	                 $('#old_metal_report > tbody').html(trHTML);
	                 	                 
	                 // Check and initialise datatable
	                 if ( ! $.fn.DataTable.isDataTable( '#old_metal_report' ) ) { 
	                     oTable = $('#old_metal_report').dataTable({ 
						                "bSort": false, 
						                "bInfo": true, 
						                "scrollX":'100%',  
						                "dom": 'lBfrtip',
						                "lengthMenu": [[-1, 25, 50, 100, 250], ["All" ,25, 50, 100, 250]],
						                "buttons": [
													{
													   extend: 'print',
													   footer: true,
													   title: '',
													   messageTop: title,
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
													    title: 'Old Metal Purchase Analyse Report',
													  }
													 ], 
			
							
									 });
								}	  	 	
				} 
		} 
		});
} 
//Old Metal Profit and Loss
//Sales Analysis
$('#select_zone').on('change',function(){
    if(this.value!='')
    {
         get_ActiveVillage();
    }
   
});
$('#branch_select').on('change',function(){
    if(ctrl_page[1]=='sales_analysis_report')
    {
        get_ActiveZone();
    }
    
    if(ctrl_page[1]=='cash_abstract' || ctrl_page[1]=='bill_wise_transcation')
    {
        get_ActiveFloors();
        get_ActiveCounters(); 
    }
         
});
function get_ActiveZone()
{
    $('#select_zone option').remove();
    $('.overlay').css('display','block');
    $.ajax({
        type: 'POST',
        url:  base_url+'index.php/admin_ret_reports/ajax_zone_list',
        dataType: 'json',
        data:{'id_branch':$('#branch_select').val()},
        success: function(data) {
        var id_village='';
        $.each(data, function (key, data) {
        $('#select_zone').append(
        $("<option></option>")
        .attr("value", data.id_zone)
        .text(data.name)
        );
        
        $('#select_zone').select2({
            placeholder:"Select Zone",
            allowClear:true
        });
        $("#select_zone").select2("val",'');
        });
        
            $('.overlay').css('display','none');
        },
        error:function(error)  
        {
            $("div.overlay").css("display", "none"); 
        }
    });
}
$("body").on("click","#retail_tab", function(){
	get_sales_analysis_report();
});
$("body").on("click","#crm_tab", function(){
    getChitAnalysisReport();
});
function getChitAnalysisReport()
{
     $("div.overlay").css("display", "block"); 
    var company_name=$('#company_name').val();
    var dt_range=($("#dt_range").val()).split('-');
    var title="<b><span style='font-size:15pt;margin-left:30%;'>"+company_name+"</span></b><b><span style='font-size:12pt;'></span></b></br>"+"<span style=font-size:13pt;margin-left:25%;>&nbsp;&nbsp;CHIT ANALYSIS REPORT  From&nbsp;:&nbsp;"+dt_range[0]+" &nbsp;&nbsp;To Date&nbsp;:&nbsp;"+dt_range[1]+"</span>";
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/sales_analysis_report/crm_details?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'dt_range' :$("#dt_range").val(),'id_branch':$('#branch_select').val(),'id_village':$('#select_village').val(),'id_zone':$('#select_zone').val()}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			     
			        var oTable = $('#chit_analysis_list').DataTable();
    				oTable.clear().draw();	
    			    if(data!=null )
					{
					   	
					   chit_Table = $('#chit_analysis_list').dataTable({
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
    								    title: "Chit Analysis Report",
    								  }
    								 ],
    						"aaData": data,
    						"aoColumns": [	
    										{ "mDataProp": "id_customer" },
    										{ "mDataProp": "cus_name" },
    										{ "mDataProp": function ( row, type, val, meta )
    										{ 
    										  var url = base_url+'index.php/admin_ret_reports/customer_history/list/'+row.mobile;
    										  return '<a href='+url+' target="_blank">'+row.mobile+'</a>';
                    		                }},
    										{ "mDataProp": "zone_name" },
    										{ "mDataProp": "village_name" },
    										{ "mDataProp": "tot_acc" },
    										{ "mDataProp": "active_acc" },
    										{ "mDataProp": "closed_acc" },
    										{ "mDataProp": "tot_bill" },
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
    
function get_sales_analysis_report()
{
   
     $("div.overlay").css("display", "block"); 
    var company_name=$('#company_name').val();
    var title="<b><span style='font-size:15pt;margin-left:30%;'>"+company_name+"</span></b><b><span style='font-size:12pt;'></span></b></br>"+"<span style=font-size:13pt;margin-left:25%;>&nbsp;&nbsp;SALES ANALYSIS REPORT  From&nbsp;:&nbsp;"+$('#rpt_payments1').html()+" &nbsp;&nbsp;- "+$('#rpt_payments2').html()+"</span>";
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/sales_analysis_report/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'from_date':$('#rpt_payments1').html(),'to_date' :$('#rpt_payments2').html(),'id_branch':$('#branch_select').val(),'id_village':$('#select_village').val(),'id_product':$('#prod_select').val()}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			     
			    $("#sales_analysis_list > tbody > tr").remove();  
				$('#sales_analysis_list').dataTable().fnClearTable();
    		    $('#sales_analysis_list').dataTable().fnDestroy(); 
    		    
			      var data=data.list;
			      var trHTML='';
			      var total_cus=0;
			      var totnew_cus=0;
			      var total_gold_wt=0;
			      var total_silver_wt=0;
			      var total_mrp_amt=0;
			      var total_acc=0;
			      var total_active_acc=0;
			      var total_closed_acc=0;
			      $i=1;
			      
			      
			      
			      
			      	var oTable = $('#sales_analysis_list').DataTable();
    				oTable.clear().draw();				  
    				if (data!= null && data.length > 0)
    				{  	
    					oTable = $('#sales_analysis_list').dataTable({
    						"bDestroy": true,
    		                "bInfo": true,
    		                "bFilter": true,
    		                "scrollX":'100%',
    		                "bSort": true,
    		                "dom": 'lBfrtip',
    		                "order": [[ 0, "asc" ]],
    		                "buttons": [
    								 {
    								   extend: 'print',
    								   footer: true,
    								   title: "",
    								   messageTop: 'Area Analysts Report',
    								   customize: function ( win ) {
    										$(win.document.body).find( 'table' )
    											.addClass( 'compact' )
    											.css( 'font-size', 'inherit' );
    									},
    								 },
    								 {
    									extend:'excel',
    									footer: true,
    								    title: "Area Analysts Report",
    								  }
    								 ],
    						"aaData": data,
    						"aoColumns": [	
    						                
    										{ "mDataProp": "id_village" },
    										{ "mDataProp": "village_name" },
    										{ "mDataProp": "branch_name" },
    										{ "mDataProp": function ( items, type, val, meta )
    										{ 
    										  var tot_customer = base_url+'index.php/admin_ret_reports/sales_analysis_report/sales_details/'+items.id_village+'/'+$('#branch_select').val()+'/'+$('#rpt_payments1').html()+'/'+$('#rpt_payments2').html()+'/1';
    										  return '<a href='+tot_customer+' target="_blank">'+items.tot_cus+'</a>';
                    		                }},
                    		                { "mDataProp": function ( items, type, val, meta )
    										{ 
    										  var tot_customer = base_url+'index.php/admin_ret_reports/sales_analysis_report/sales_details/'+items.id_village+'/'+$('#branch_select').val()+'/'+$('#rpt_payments1').html()+'/'+$('#rpt_payments2').html()+'/2';
    										  return '<a href='+tot_customer+' target="_blank">'+items.totnew_cus+'</a>';
                    		                }},
                    		                { "mDataProp": function ( items, type, val, meta )
    										{ 
    										  var tot_customer = base_url+'index.php/admin_ret_reports/sales_analysis_report/without_acc_list/'+items.id_village+'/'+$('#branch_select').val()+'/'+$('#rpt_payments1').html()+'/'+$('#rpt_payments2').html()+'/3';
    										  return '<a href='+tot_customer+' target="_blank">'+items.without_acc+'</a>';
                    		                }},
                    		                { "mDataProp": function ( items, type, val, meta )
    										{ 
    										  return '<a href='+base_url+'index.php/admin_ret_reports/sales_analysis_report/product_details/'+items.id_village+'/'+$('#branch_select').val()+'/'+$('#rpt_payments1').html()+'/'+$('#rpt_payments2').html()+'/1/2'+' target="_blank">'+items.tot_gold_wt+'</a>';
                    		                }},
                    		                { "mDataProp": function ( items, type, val, meta )
    										{ 
    										  return '<a href='+base_url+'index.php/admin_ret_reports/sales_analysis_report/product_details/'+items.id_village+'/'+$('#branch_select').val()+'/'+$('#rpt_payments1').html()+'/'+$('#rpt_payments2').html()+'/2/2'+' target="_blank">'+items.tot_silver_wt+'</a>';
                    		                }},
                    		                { "mDataProp": function ( items, type, val, meta )
    										{ 
    										  return '<a href='+base_url+'index.php/admin_ret_reports/sales_analysis_report/product_details/'+items.id_village+'/'+$('#branch_select').val()+'/'+$('#rpt_payments1').html()+'/'+$('#rpt_payments2').html()+'/1/1'+' target="_blank">'+items.tot_amt+'</a>';
                    		                }},
                    		                { "mDataProp": function ( items, type, val, meta )
    										{ 
    										  return parseFloat(parseFloat(items.active_acc)+parseFloat(items.closed_acc));
                    		                }},
                    		                { "mDataProp": function ( items, type, val, meta )
    										{ 
    										    return '<a href='+base_url+'index.php/admin_ret_reports/sales_analysis_report/chit_details/'+items.id_village+'/'+$('#branch_select').val()+'/'+$('#rpt_payments1').html()+'/'+$('#rpt_payments2').html()+'/0'+' target="_blank">'+items.active_acc+'</a>';
                    		                }},
                    		                { "mDataProp": function ( items, type, val, meta )
    										{ 
    										    return '<a href='+base_url+'index.php/admin_ret_reports/sales_analysis_report/chit_details/'+items.id_village+'/'+$('#branch_select').val()+'/'+$('#rpt_payments1').html()+'/'+$('#rpt_payments2').html()+'/1'+' target="_blank">'+items.closed_acc+'</a>';
                    		                }},
    									
    									  ],
									   "footerCallback": function( row, data, start, end, display )
                						{ 
											if(data.length>0){
												var api = this.api(), data;
                                                var tot_cus=0;
                                                var new_cus=0;
                                                var tot_gold=0;
                                                var tot_silver=0;
                                                var tot_amt=0;
                                                var tot_acc=0;
                                                var active_acc=0;
                                                var closed_acc=0;
                                                var without_acc=0;
												for( var i=0; i<=data.length-1;i++){
													var intVal = function ( i ) {
														return typeof i === 'string' ?
														i.replace(/[\$,]/g, '')*1 :
														typeof i === 'number' ?
														i : 0;
													};	
													
													$(api.column(0).footer() ).html('Total');	
                                            
                                                    tot_cus += parseInt(data[i].tot_cus);
                                                    new_cus += parseInt(data[i].totnew_cus);
                                                    without_acc += parseInt(data[i].without_acc);
                                                    tot_gold += parseInt(data[i].tot_gold_wt);
                                                    tot_silver += parseInt(data[i].tot_silver_wt);
                                                    tot_amt += parseInt(data[i].tot_silver_wt);
                                                    tot_acc += parseInt(parseInt(data[i].active_acc)+parseInt(data[i].closed_acc));
                                                    active_acc += parseInt(parseInt(data[i].active_acc));
                                                    closed_acc += parseInt(parseInt(data[i].closed_acc));
													
												
													$(api.column(3).footer()).html(parseFloat(tot_cus).toFixed(2));	
													$(api.column(4).footer()).html(parseFloat(new_cus).toFixed(2));	
													$(api.column(5).footer()).html(parseFloat(without_acc).toFixed(2));	
													$(api.column(6).footer()).html(parseFloat(tot_gold).toFixed(2));
													$(api.column(7).footer()).html(parseFloat(tot_silver).toFixed(2));
													$(api.column(8).footer()).html(parseFloat(tot_amt).toFixed(2));
													$(api.column(9).footer()).html(parseFloat(tot_acc).toFixed(2));
													$(api.column(10).footer()).html(parseFloat(active_acc).toFixed(2));
													$(api.column(11).footer()).html(parseFloat(closed_acc).toFixed(2));
											
											} 
											}else{
												 var api = this.api(), data; 
												 $(api.column(4).footer()).html('');
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
function get_sales_analysis_other_city_report()
{
   
     $("div.overlay").css("display", "block"); 
    var company_name=$('#company_name').val();
    var title="<b><span style='font-size:15pt;margin-left:30%;'>"+company_name+"</span></b><b><span style='font-size:12pt;'></span></b></br>"+"<span style=font-size:13pt;margin-left:25%;>&nbsp;&nbsp;SALES ANALYSIS REPORT  From&nbsp;:&nbsp;"+$('#rpt_payments1').html()+" &nbsp;&nbsp;- "+$('#rpt_payments2').html()+"</span>";
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/sales_analysis_report/sales_analysis_other_city?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'from_date':$('#rpt_payments1').html(),'to_date' :$('#rpt_payments2').html(),'id_branch':$('#branch_select').val(),'id_village':$('#select_village').val(),'id_product':$('#prod_select').val()}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			     
			     
			      	var oTable = $('#sales_analysis_other_city_list').DataTable();
    				oTable.clear().draw();				  
    				if (data!= null && data.length > 0)
    				{  	
    					oTable = $('#sales_analysis_other_city_list').dataTable({
    						"bDestroy": true,
    		                "bInfo": true,
    		                "bFilter": true,
    		                "scrollX":'100%',
    		                "bSort": true,
    		                "dom": 'lBfrtip',
    		                "order": [[ 0, "asc" ]],
    		                "buttons": [
    								 {
    								   extend: 'print',
    								   footer: true,
    								   title: "",
    								   messageTop: 'Area Analysts Report',
    								   customize: function ( win ) {
    										$(win.document.body).find( 'table' )
    											.addClass( 'compact' )
    											.css( 'font-size', 'inherit' );
    									},
    								 },
    								 {
    									extend:'excel',
    									footer: true,
    								    title: "Area Analysts Report",
    								  }
    								 ],
    						"aaData": data,
    						"aoColumns": [	
    						                
    										{ "mDataProp": "id_village" },
    										{ "mDataProp": "village_name" },
    										{ "mDataProp": "branch_name" },
    										{ "mDataProp": function ( items, type, val, meta )
    										{ 
    										  var tot_customer = base_url+'index.php/admin_ret_reports/sales_analysis_report/sales_details/'+items.id_village+'/'+$('#branch_select').val()+'/'+$('#rpt_payments1').html()+'/'+$('#rpt_payments2').html()+'/1';
    										  return '<a href='+tot_customer+' target="_blank">'+items.tot_cus+'</a>';
                    		                }},
                    		                { "mDataProp": function ( items, type, val, meta )
    										{ 
    										  var tot_customer = base_url+'index.php/admin_ret_reports/sales_analysis_report/sales_details/'+items.id_village+'/'+$('#branch_select').val()+'/'+$('#rpt_payments1').html()+'/'+$('#rpt_payments2').html()+'/2';
    										  return '<a href='+tot_customer+' target="_blank">'+items.totnew_cus+'</a>';
                    		                }},
                    		                { "mDataProp": function ( items, type, val, meta )
    										{ 
    										  var tot_customer = base_url+'index.php/admin_ret_reports/sales_analysis_report/without_acc_list/'+items.id_village+'/'+$('#branch_select').val()+'/'+$('#rpt_payments1').html()+'/'+$('#rpt_payments2').html()+'/3';
    										  return '<a href='+tot_customer+' target="_blank">'+items.without_acc+'</a>';
                    		                }},
                    		                { "mDataProp": function ( items, type, val, meta )
    										{ 
    										  return '<a href='+base_url+'index.php/admin_ret_reports/sales_analysis_report/product_details/'+items.id_village+'/'+$('#branch_select').val()+'/'+$('#rpt_payments1').html()+'/'+$('#rpt_payments2').html()+'/1/2'+' target="_blank">'+items.tot_gold_wt+'</a>';
                    		                }},
                    		                { "mDataProp": function ( items, type, val, meta )
    										{ 
    										  return '<a href='+base_url+'index.php/admin_ret_reports/sales_analysis_report/product_details/'+items.id_village+'/'+$('#branch_select').val()+'/'+$('#rpt_payments1').html()+'/'+$('#rpt_payments2').html()+'/2/2'+' target="_blank">'+items.tot_silver_wt+'</a>';
                    		                }},
                    		                { "mDataProp": function ( items, type, val, meta )
    										{ 
    										  return '<a href='+base_url+'index.php/admin_ret_reports/sales_analysis_report/product_details/'+items.id_village+'/'+$('#branch_select').val()+'/'+$('#rpt_payments1').html()+'/'+$('#rpt_payments2').html()+'/1/1'+' target="_blank">'+items.tot_amt+'</a>';
                    		                }},
                    		                { "mDataProp": function ( items, type, val, meta )
    										{ 
    										  return parseFloat(parseFloat(items.active_acc)+parseFloat(items.closed_acc));
                    		                }},
                    		                { "mDataProp": function ( items, type, val, meta )
    										{ 
    										    return '<a href='+base_url+'index.php/admin_ret_reports/sales_analysis_report/chit_details/'+items.id_village+'/'+$('#branch_select').val()+'/'+$('#rpt_payments1').html()+'/'+$('#rpt_payments2').html()+'/0'+' target="_blank">'+items.active_acc+'</a>';
                    		                }},
                    		                { "mDataProp": function ( items, type, val, meta )
    										{ 
    										    return '<a href='+base_url+'index.php/admin_ret_reports/sales_analysis_report/chit_details/'+items.id_village+'/'+$('#branch_select').val()+'/'+$('#rpt_payments1').html()+'/'+$('#rpt_payments2').html()+'/1'+' target="_blank">'+items.closed_acc+'</a>';
                    		                }},
    									
    									  ],
									   "footerCallback": function( row, data, start, end, display )
                						{ 
											if(data.length>0){
												var api = this.api(), data;
                                                var tot_cus=0;
                                                var new_cus=0;
                                                var tot_gold=0;
                                                var tot_silver=0;
                                                var tot_amt=0;
                                                var tot_acc=0;
                                                var active_acc=0;
                                                var closed_acc=0;
                                                var without_acc=0;
												for( var i=0; i<=data.length-1;i++){
													var intVal = function ( i ) {
														return typeof i === 'string' ?
														i.replace(/[\$,]/g, '')*1 :
														typeof i === 'number' ?
														i : 0;
													};	
													
													$(api.column(0).footer() ).html('Total');	
                                            
                                                    tot_cus += parseInt(data[i].tot_cus);
                                                    new_cus += parseInt(data[i].totnew_cus);
                                                    without_acc += parseInt(data[i].without_acc);
                                                    tot_gold += parseInt(data[i].tot_gold_wt);
                                                    tot_silver += parseInt(data[i].tot_silver_wt);
                                                    tot_amt += parseInt(data[i].tot_silver_wt);
                                                    tot_acc += parseInt(parseInt(data[i].active_acc)+parseInt(data[i].closed_acc));
                                                    active_acc += parseInt(parseInt(data[i].active_acc));
                                                    closed_acc += parseInt(parseInt(data[i].closed_acc));
													
												
													$(api.column(3).footer()).html(parseFloat(tot_cus).toFixed(2));	
													$(api.column(4).footer()).html(parseFloat(new_cus).toFixed(2));	
													$(api.column(5).footer()).html(parseFloat(without_acc).toFixed(2));	
													$(api.column(6).footer()).html(parseFloat(tot_gold).toFixed(2));
													$(api.column(7).footer()).html(parseFloat(tot_silver).toFixed(2));
													$(api.column(8).footer()).html(parseFloat(tot_amt).toFixed(2));
													$(api.column(9).footer()).html(parseFloat(tot_acc).toFixed(2));
													$(api.column(10).footer()).html(parseFloat(active_acc).toFixed(2));
													$(api.column(11).footer()).html(parseFloat(closed_acc).toFixed(2));
											
											} 
											}else{
												 var api = this.api(), data; 
												 $(api.column(4).footer()).html('');
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
function foramtRowSalesDetails( oTable, nTr )
{
  var oData = oTable.fnGetData( nTr );
  var rowDetail = '';
  var prodTable = 
     '<div class="innerDetails">'+
      '<table class="table table-responsive table-bordered text-center table-sm">'+ 
        '<tr class="bg-teal">'+
        '<th>S.No</th>'+ 
        '<th>Design Name</th>'+
        '<th>Pcs</th>'+
        '<th>Gross Wt</th>'+
        '<th>Net Wt</th>'+
        '<th>Amount</th>'+
        '</tr>';
    var design_details = oData.design_details; 
  $.each(design_details, function (idx, val) {
  	prodTable += 
        '<tr class="prod_det_btn">'+
        '<td>'+parseFloat(idx+1)+'</td>'+
        '<td>'+val.design_name+'</td>'+
        '<td>'+val.total_pcs+'</td>'+
        '<td>'+val.total_gwt+'</td>'+
        '<td>'+val.total_nwt+'</td>'+
        '<td>'+val.total_cost+'</td>'+
        '</tr>'; 
  }); 
  rowDetail = prodTable+'</table></div>';
  return rowDetail;
}
function fnFormatRowcusSalesDetails( oTable, nTr )
{
  var oData = oTable.fnGetData( nTr );
  var rowDetail = '';
  var prodTable = 
     '<div class="innerDetails">'+
      '<table class="table table-responsive table-bordered text-center table-sm">'+ 
        '<tr class="bg-teal">'+
        '<th>S.No</th>'+ 
        '<th>Customer</th>'+
        '<th>Mobile</th>'+
        '<th>Sales-Gold</th>'+
        '<th>Sales-Silver</th>'+
        '<th>MRP Amount</th>'+
        '<th>Purchase-Gold</th>'+
        '<th>Purchase-Silver</th>'+
        '<th>Return-Gold</th>'+
        '<th>Return-Silver</th>'+
        
        '</tr>';
    var cusSalesDetails = oData.cusSalesDetails;
    var sales_gold_gwt=0;
    var sales_silver_gwt=0;
    var old_gold_wt=0;
    var old_silver_wt=0;
    var return_gold=0;
    var return_silver=0;
    var mrp_cost=0;
  $.each(cusSalesDetails, function (idx, val) {
      sales_gold_gwt+=parseFloat(val.sales_gold_gwt);
      sales_silver_gwt+=parseFloat(val.sales_silver_gwt);
      old_gold_wt+=parseFloat(val.old_gold_wt);
      old_silver_wt+=parseFloat(val.old_silver_wt);
      return_gold+=parseFloat(val.return_gold);
      return_silver+=parseFloat(val.return_silver);
      mrp_cost+=parseFloat(val.mrp_cost);
      var url = base_url+'index.php/admin_ret_reports/customer_history/list/'+val.mobile;
  	prodTable += 
        '<tr class="prod_det_btn">'+
        '<td>'+parseFloat(idx+1)+'</td>'+
        '<td>'+val.cus_name+'</td>'+
         '<td><a href='+url+' target="_blank">'+val.mobile+'</a></td>'+
        '<td>'+val.sales_gold_gwt+'</td>'+
        '<td>'+val.sales_silver_gwt+'</td>'+
        '<td>'+val.mrp_cost+'</td>'+
        '<td>'+val.old_gold_wt+'</td>'+
        '<td>'+val.old_silver_wt+'</td>'+
        '<td>'+val.return_gold+'</td>'+
        '<td>'+val.return_silver+'</td>'+
        
        '</tr>'; 
  }); 
 prodTable+= '<tr class="prod_det_btn" style="font-weight:bold;">'+
        '<td>Total</td>'+
        '<td></td>'+
        '<td></td>'+
        '<td>'+parseFloat(sales_gold_gwt).toFixed(3)+'</td>'+
        '<td>'+parseFloat(sales_silver_gwt).toFixed(3)+'</td>'+
        '<td>'+parseFloat(mrp_cost).toFixed(3)+'</td>'+
        '<td>'+parseFloat(old_gold_wt).toFixed(3)+'</td>'+
        '<td>'+parseFloat(old_silver_wt).toFixed(3)+'</td>'+
        '<td>'+parseFloat(return_gold).toFixed(3)+'</td>'+
        '<td>'+parseFloat(return_silver).toFixed(3)+'</td>'+
        
        '</tr>'; 
  rowDetail = prodTable+'</table></div>';
  return rowDetail;
}
//Sales Analysis
//Customer and Tag History
$('#cus_search').on('click',function(){
    var mobile_num=$('#mobilenumber').val();
    if(mobile_num.length!=10)
    {
        alert('Please Enter The Valid Number..');
        $('#mobilenumber').val('');
        $('#mobilenumber').focus();
    }else{
        get_customer_details();
        $('#mobilenumber').val('');
    }
});
$('#tag_search').on('click',function(){
    if($("#tag_number").val()!='' || $("#old_tag_number").val()!='')
    {
        set_tag_history();
    }else
    {
        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Enter The Tag Number..'});
    }
    
});
function get_customer_details()
{
     $('.overlay').css('display','block');
    	my_Date = new Date();
	 $.ajax({
		url:base_url+ "index.php/admin_ret_reports/customer_history/ajax?nocache=" + my_Date.getUTCSeconds(),
        type: 'POST',
        dataType: 'json',
        data:{'mobile':(ctrl_page[3]!='' && ctrl_page[3]!=undefined ? ctrl_page[3] :$('#mobilenumber').val())},
        success: function(data) {
            var accHtml='';
            var salesHtml='';
            var purHtml='';
            var creditHtml='';
            var customer=data.customer;
            var accounts=data.accounts;
            var sales_details=data.sales;
            var purchase_details=data.purchase;
            var credit_details=data.credit;
            $('#cus_name').html(customer.cus_name);
            $('#cus_mobile').html(customer.mobile);
            $('#cus_mail').html(customer.email);
            $('#cus_mail').html(customer.email);
            $('#cus_country').html(customer.county_name);
            $('#cus_state').html(customer.state_name);
            $('#cus_city').html(customer.city_name);
            $('#cus_address1').html(customer.address1);
            $('#cus_address2').html(customer.address2);
            $('#cus_address3').html(customer.address3);
            if(customer.cus_img!='')
            {
                var image='<img  class="img-thumbnail" src="'+base_url+'assets/img/customer/'+customer.cus_img+'/customer.jpg" width="240" height="240" >';
            }else{
                var image='<img  class="img-thumbnail" src="'+base_url+'assets/img/default.png" width="240" height="240" >';
            }
            $('#cus_img').html(image);
            
            $.each(accounts,function(key,item){
                var url = base_url+'index.php/admin_reports/scheme_account_report/'+item.id_scheme_account;
                accHtml+= '<tr>' +
                                '<td><a href='+url+' target="_blank">'+item.id_scheme_account+'</a></td>'+
                                '<td>'+item.scheme_acc_number+'</td>' +
                                '<td>'+item.account_name+'</td>' +
                                '<td>'+item.scheme_name+'</td>' +
                                '<td>'+item.start_date+'</td>' +
                                '<td>'+item.paid_installments+'/'+item.total_installments+'</td>' +
                                '<td>'+item.status+'</td>' +
                                
                            '</tr>';
            });
            $('#account_list > tbody').html(accHtml);
            
             $.each(sales_details,function(key,item){
                var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+item.bill_id;
                salesHtml+= '<tr>' +
                                '<td><a href='+url+' target="_blank">'+item.bill_no+'</a></td>'+
                                '<td>'+item.bill_date+'</td>' +
                                '<td>'+item.gold_wt+'</td>' +
                                '<td>'+item.silver_wt+'</td>' +
                                '<td>'+item.mrp_amount+'</td>' +
                                '<td>'+item.tot_bill_amount+'</td>' +
                            '</tr>';
            });
            
            $('#sales_list > tbody').html(salesHtml);
            
            $.each(purchase_details,function(key,item){
                var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+item.bill_id;
                purHtml+= '<tr>' +
                                '<td><a href='+url+' target="_blank">'+item.bill_no+'</a></td>'+
                                '<td>'+item.bill_date+'</td>' +
                                '<td>'+item.tot_pur_amt+'</td>' +
                            '</tr>';
            });
            $('#purchase_list > tbody').html(purHtml);
            
             $.each(credit_details,function(key,item){
                var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+item.bill_id;
                creditHtml+= '<tr>' +
                                '<td><a href='+url+' target="_blank">'+item.bill_no+'</a></td>'+
                                '<td>'+item.bill_date+'</td>' +
                                '<td>'+item.due_amount+'</td>' +
                                '<td>'+item.credit_status+'</td>' +
                            '</tr>';
            });
            $('#credit_list > tbody').html(creditHtml);
            
            $('.overlay').css('display','none');
        },
        error:function(error)  
        {
            $("div.overlay").css("display", "none"); 
        }
    });
}
//Customer and Tag History
//Unbilled Estimation
function set_unbilled_estimation()
{
    var company_name=$('#company_name').val();
    var dt_range=($("#dt_range").val()).split('-');
    var title="<b><span style='font-size:15pt;margin-left:30%;'>"+company_name+"</span></b><b><span style='font-size:12pt;'></span></b></br>"+"<span style=font-size:13pt;margin-left:25%;>&nbsp;&nbsp;Unbilled Estimation Report  From&nbsp;:&nbsp;"+dt_range[0]+" &nbsp;&nbsp;To Date&nbsp;:&nbsp;"+dt_range[1]+"</span>";
    $("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/unbilled_estimation/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'dt_range' :$("#dt_range").val(),'id_branch':$('#branch_select').val(),'id_village':$('#select_village').val(),'id_zone':$('#select_zone').val()}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data)
			 {
    				var oTable = $('#unbilled_est_list').DataTable();
    				oTable.clear().draw();				  
    				if (data!= null && data.length > 0)
    				{  	
    					oTable = $('#unbilled_est_list').dataTable({
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
    								    title: "Unbilled Estimation Report",
    								  }
    								 ],
    						"aaData": data,
    						"aoColumns": [	
    						                { "mDataProp": function ( row, type, val, meta )
    										{ 
    										  var url = base_url+'index.php/admin_ret_estimation/generate_invoice/'+row.estimation_id;
    										  return '<a href='+url+' target="_blank">'+row.esti_no+'</a>';
                    		                }},
    										{ "mDataProp": "est_date" },
    										{ "mDataProp": "cus_name" },
    										{ "mDataProp": "mobile" },
    										{ "mDataProp": "total_cost" },
    										{ "mDataProp": "emp_name" },
    										{
                                                "mDataProp": null,
                                                "sClass": "control center", 
                                                "sDefaultContent":  '<span class="drill-val"><i class="fa fa-chevron-circle-down text-teal"></i></span>'
                                            },
    									  ],
    					});
    					
    						var anOpen =[]; 
                    		$(document).on('click',"#unbilled_est_list .control", function(){ 
                    		   var nTr = this.parentNode;
                    		   var i = $.inArray( nTr, anOpen );
                    		 
                    		   if ( i === -1 ) { 
                    				$('.drill-val', this).html('<i class="fa fa-chevron-circle-up text-teal"></i>'); 
                    				oTable.fnOpen( nTr, fnFormatRowUnbilledEstDetails(oTable, nTr), 'details' );
                    				anOpen.push( nTr ); 
                    		    }
                    		    else { 
                    				$('.drill-val', this).html('<i class="fa fa-chevron-circle-down text-teal"></i>');
                    				oTable.fnClose( nTr );
                    				anOpen.splice( i, 1 );
                    		    }
                    		} );
    				}
				$("div.overlay").css("display", "none"); 
              },
			  error:function(error)  
			  {
				 $("div.overlay").css("display", "none"); 
			  }	 
	      });
}
function fnFormatRowUnbilledEstDetails( oTable, nTr )
{
  var oData = oTable.fnGetData( nTr );
  var rowDetail = '';
  var prodTable = 
     '<div class="innerDetails">'+
      '<table class="table table-responsive table-bordered text-center table-sm">'+ 
        '<tr class="bg-teal">'+
        '<th>S.No</th>'+ 
        '<th>Tag Id</th>'+
        '<th>Product Name</th>'+
        '<th>Design Name</th>'+
        '<th>N.wt</th>'+
        '<th>Amount</th>'+
        '</tr>';
    var est_details = oData.est_details; 
  $.each(est_details, function (idx, val) {
  	var ref_no = val.tag_id;
  	prodTable += 
        '<tr class="prod_det_btn">'+
        '<td>'+parseFloat(idx+1)+'</td>'+
        '<td>'+val.tag_id+'</td>'+
        '<td>'+val.product_name+'</td>'+
        '<td>'+val.design_name+'</td>'+
        '<td>'+val.net_wt+'</td>'+
        '<td>'+val.item_cost+'</td>'+
        '</tr>'; 
  }); 
  rowDetail = prodTable+'</table></div>';
  return rowDetail;
}
//Unbilled Estimation
//Karigar Wise Sales
function get_karigar_wise_sales()
{
    var company_name=$('#company_name').val();
    var title="<b><span style='font-size:15pt;margin-left:30%;'>"+company_name+"</span></b><b><span style='font-size:12pt;'></span></b></br>"+"<span style=font-size:13pt;margin-left:25%;>&nbsp;&nbsp;Karigar Wise Sales Report  From&nbsp;:&nbsp;</span>";
    $("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/karigar_wise_sales/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: {'id_product':$('#prod_select').val()},
			 dataType:"JSON",
			 type:"POST",
			 success:function(data)
			 {
    				var oTable = $('#karigar_wise_sales_list').DataTable();
    				oTable.clear().draw();				  
    				if (data!= null && data.length > 0)
    				{  	
    					oTable = $('#karigar_wise_sales_list').dataTable({
    						"bDestroy": true,
    		                "bInfo": true,
    		                "bFilter": true,
    		                "scrollX":'100%',
    		                "bSort": true,
    		                "dom": 'lBfrtip',
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
    								    title: "Karigar Wise Sales Report",
    								  }
    								 ],
    						"aaData": data,
    						"aoColumns": [	
    										{ "mDataProp": "gold_smith" },
    										{ "mDataProp": "kaigar_name" },
    										{ "mDataProp": "total_received_pcs" },
    										{ "mDataProp": "total_received_weight" },
    									    {
                                                "mDataProp": null,
                                                "sClass": "received center", 
                                                "sDefaultContent":  '<span class="drill-val"><i class="fa fa-chevron-circle-down text-teal"></i></span>'
                                            },
    										{ "mDataProp": "total_sold_pcs" },
    										{ "mDataProp": "total_sold_gwt" },
    										{
                                                "mDataProp": null,
                                                "sClass": "control center", 
                                                "sDefaultContent":  '<span class="drill-val"><i class="fa fa-chevron-circle-down text-teal"></i></span>'
                                            },
    									  ],
    					});
    					
    						var anOpen =[]; 
                    		$(document).on('click',"#karigar_wise_sales_list .control", function(){ 
                    		   var nTr = this.parentNode;
                    		   var i = $.inArray( nTr, anOpen );
                    		 
                    		   if ( i === -1 ) { 
                    				$('.drill-val', this).html('<i class="fa fa-chevron-circle-up text-teal"></i>'); 
                    				oTable.fnOpen( nTr, fnFormatRowSaleProductDetails(oTable, nTr), 'details' );
                    				anOpen.push( nTr ); 
                    		    }
                    		    else { 
                    				$('.drill-val', this).html('<i class="fa fa-chevron-circle-down text-teal"></i>');
                    				oTable.fnClose( nTr );
                    				anOpen.splice( i, 1 );
                    		    }
                    		} );
                    		
                    		$(document).on('click',"#karigar_wise_sales_list .received", function(){ 
                    		   var nTr = this.parentNode;
                    		   var i = $.inArray( nTr, anOpen );
                    		 
                    		   if ( i === -1 ) { 
                    				$('.drill-val', this).html('<i class="fa fa-chevron-circle-up text-teal"></i>'); 
                    				oTable.fnOpen( nTr, fnFormatRowInwardProductDetails(oTable, nTr), 'details' );
                    				anOpen.push( nTr ); 
                    		    }
                    		    else { 
                    				$('.drill-val', this).html('<i class="fa fa-chevron-circle-down text-teal"></i>');
                    				oTable.fnClose( nTr );
                    				anOpen.splice( i, 1 );
                    		    }
                    		} );
    				}
				$("div.overlay").css("display", "none"); 
              },
			  error:function(error)  
			  {
				 $("div.overlay").css("display", "none"); 
			  }	 
	      });
}
function fnFormatRowInwardProductDetails(oTable, nTr)
{
    var oData = oTable.fnGetData( nTr );
  var rowDetail = '';
  var prodTable = 
     '<div class="innerDetails">'+
      '<table class="table table-responsive table-bordered text-center table-sm">'+ 
        '<tr class="bg-teal">'+
        '<th>S.No</th>'+ 
         '<th>Product Name</th>'+
         '<th>Total Pcs</th>'+
         '<th>Total Weight</th>'+
        '</tr>';
    var lot_details = oData.lot_details; 
    var total_pcs=0;
    var total_gwt=0;
  $.each(lot_details, function (idx, val) {
      total_pcs+=parseFloat(val.tot_pcs);
      total_gwt+=parseFloat(val.tot_gwt);
  	prodTable += 
        '<tr class="prod_det_btn">'+
        '<td>'+parseFloat(idx+1)+'</td>'+
        '<td>'+val.product_name+'</td>'+
        '<td>'+val.tot_pcs+'</td>'+
        '<td>'+val.tot_gwt+'</td>'+
        '</tr>'; 
  }); 
  
  	prodTable += 
        '<tr class="prod_det_btn">'+
        '<td colspan="2"><strong>Total</strong></td>'+
        '<td><strong>'+parseFloat(total_pcs)+'</td></strong>'+
        '<td><strong>'+parseFloat(total_gwt).toFixed(3);+'</strong></td>'+
        '</tr>'; 
        
  rowDetail = prodTable+'</table></div>';
  return rowDetail;
}
function fnFormatRowSaleProductDetails(oTable, nTr)
{
    var oData = oTable.fnGetData( nTr );
  var rowDetail = '';
  var prodTable = 
     '<div class="innerDetails">'+
      '<table class="table table-responsive table-bordered text-center table-sm">'+ 
        '<tr class="bg-teal">'+
        '<th>S.No</th>'+ 
         '<th>Product Name</th>'+
         '<th>Total Pcs</th>'+
         '<th>Total Weight</th>'+
        '</tr>';
    var sales_details = oData.sales_details; 
    var total_pcs=0;
    var total_gwt=0;
  $.each(sales_details, function (idx, val) {
      total_pcs+=parseFloat(val.tot_pcs);
      total_gwt+=parseFloat(val.tot_gwt);
  	prodTable += 
        '<tr class="prod_det_btn">'+
        '<td>'+parseFloat(idx+1)+'</td>'+
        '<td>'+val.product_name+'</td>'+
        '<td>'+val.tot_pcs+'</td>'+
        '<td>'+val.tot_gwt+'</td>'+
        '</tr>'; 
  }); 
  
  	prodTable += 
        '<tr class="prod_det_btn">'+
        '<td colspan="2"><strong>Total</strong></td>'+
        '<td><strong>'+parseFloat(total_pcs)+'</td></strong>'+
        '<td><strong>'+parseFloat(total_gwt).toFixed(3);+'</strong></td>'+
        '</tr>'; 
        
  rowDetail = prodTable+'</table></div>';
  return rowDetail;
}
//Karigar Wise Sales
//Lot History
function setLotHisory()
{
    var company_name=$('#company_name').val();
    var title="<b><span style='font-size:15pt;margin-left:30%;'>"+company_name+"</span></b><b><span style='font-size:12pt;'></span></b></br>"+"<span style=font-size:13pt;margin-left:25%;>&nbsp;&nbsp;Lot History Report  From&nbsp;:&nbsp;</span>";
    $("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/lot_history/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: {'id_product':$('#prod_select').val()},
			 dataType:"JSON",
			 type:"POST",
			 success:function(data)
			 {
    				var oTable = $('#lot_history_list').DataTable();
    				oTable.clear().draw();				  
    				if (data!= null && data.length > 0)
    				{  	
    					oTable = $('#lot_history_list').dataTable({
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
    								    title: "Karigar Wise Sales Report",
    								  }
    								 ],
    						"aaData": data,
    						"aoColumns": [	
    									    { "mDataProp": function ( row, type, val, meta )
    										{ 
    										  var url = base_url+'index.php/admin_ret_reports/lot_history/lot_details/'+row.lot_no;
    										  return '<a href='+url+' target="_blank">'+row.lot_no+'</a>';
                    		                }},
                    		                {"mDataProp": "category_name"},
                    		                
                    		                {"mDataProp": "pur_ref_no"},
											{"mDataProp": "po_date"},
    										{ "mDataProp": "kaigar_name" },
    										{ "mDataProp": "total_received_pcs" },
    										{ "mDataProp": "total_received_weight" },
    										{ "mDataProp": "total_sold_pcs" },
    										{ "mDataProp": "total_sold_gwt" },
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
//Lot History
function get_lot_details()
{
    var company_name=$('#company_name').val();
    var title="<b><span style='font-size:15pt;margin-left:30%;'>"+company_name+"</span></b><b><span style='font-size:12pt;'></span></b></br>"+"<span style=font-size:13pt;margin-left:25%;>&nbsp;&nbsp;Lot History Report  From&nbsp;:&nbsp;</span>";
    $("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/lot_details?nocache=" + my_Date.getUTCSeconds(),
			 data: {'lot_no':ctrl_page[3]},
			 dataType:"JSON",
			 type:"POST",
			 success:function(data)
			 {
    				var oTable = $('#lot_detail_list').DataTable();
    				oTable.clear().draw();				  
    				if (data!= null && data.length > 0)
    				{  	
    					oTable = $('#lot_detail_list').dataTable({
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
    								    title: "Karigar Wise Sales Report",
    								  }
    								 ],
    						"aaData": data,
    						"aoColumns": [	
    										{ "mDataProp": "tag_id" },
    										{ "mDataProp": "tag_date" },
    										{ "mDataProp": "tag_code" },
    										{ "mDataProp": "branch_name" },
    										{ "mDataProp": "product_name" },
    										{ "mDataProp": "design_name" },
    										
    										{ "mDataProp": "sub_design_name" },
    										{ "mDataProp": "piece" },
    										{ "mDataProp": "net_wt" },
    										{ "mDataProp": function ( row, type, val, meta )
    										{ 
    										    if(row.tag_status==1)
    										    {
    										        return '<span class="badge bg-green">'+row.tag_status_name+'</span>';
    										    }else
    										    {
    										        return '<span class="badge bg-red">'+row.tag_status_name+'</span>';
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
//Green Tag Report
$('#green_tag_search').click(function(event) {
        get_getGreenTagDetails();
});
function get_getGreenTagDetails()
{
    var company_name=$('#company_name').val();
    var title="<b><span style='font-size:15pt;margin-left:30%;'>"+company_name+"</span></b><b><span style='font-size:12pt;'></span></b></br>"+"<span style=font-size:13pt;margin-left:25%;>&nbsp;&nbsp;Green Tag Report &nbsp;:&nbsp;</span>";
    $("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/green_tag/ajax?nocache=" + my_Date.getUTCSeconds(),
			 dataType:"JSON",
			 type:"POST",
			 data:{'from_date':$('#rpt_payments1').html(),'to_date' :$('#rpt_payments2').html(),'id_branch':$('#branch_select').val()},
			 success:function(data)
			 {
    				var oTable = $('#green_tag_list').DataTable();
    				oTable.clear().draw();				  
    				if (data!= null && data.length > 0)
    				{  	
    					oTable = $('#green_tag_list').dataTable({
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
    								    title: "Green Tag Sales Report",
    								  }
    								 ],
    						"aaData": data,
    						"aoColumns": [	
    									    { "mDataProp": function ( row, type, val, meta )
    										{ 
    										  var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+row.bill_id;
    										  return '<a href='+url+' target="_blank">'+row.bill_no+'</a>';
                    		                }},
                    		                { "mDataProp": function ( row, type, val, meta )
    										{ 
    										  var url = base_url+'index.php/admin_ret_estimation/generate_invoice/'+row.estimation_id;
    										  return '<a href='+url+' target="_blank">'+row.esti_no+'</a>';
                    		                }},
    										{ "mDataProp": "bill_date" },
    										{ "mDataProp": "tag_date" },
    										{ "mDataProp": "tag_code" },
    										{ "mDataProp": "gross_wt" },
    										{ "mDataProp": "net_wt" },
    										{ "mDataProp": function ( row, type, val, meta )
    										{ 
    										  if(row.id_metal==1)
    										  {
    										      return parseFloat(row.net_wt*$('#emp_sales_incentive_gold_perg').val()).toFixed(2);
    										  }else{
    										      return parseFloat(row.net_wt*$('#emp_sales_incentive_silver_perg').val()).toFixed(2);
    										  }
                    		                }},
    										{ "mDataProp": "product_name" },
    										{ "mDataProp": "item_cost" },
    										{ "mDataProp": "emp_name" },
    										{ "mDataProp": "emp_code" },
    									  ],
    									  	"footerCallback": function( row, data, start, end, display ){
                        						var cshtotal = 0; 
                        						if(data.length>0){
                        							var api = this.api(), data;
                        
                        							for( var i=0; i<=data.length-1;i++){
                        
                        								var intVal = function ( i ) {
                        									return typeof i === 'string' ?
                        									i.replace(/[\$,]/g, '')*1 :
                        									typeof i === 'number' ?
                        									i : 0;
                        								};	
                        								
                        								$(api.column(0).footer() ).html('Total');	
                        
                        								gross_wgt = api
                        								.column(6)
                        								.data()
                        								.reduce( function (a, b) {
                        									return intVal(a) + intVal(b);
                        								}, 0 );
                        								$(api.column(6).footer()).html(parseFloat(gross_wgt).toFixed(2));
                        								
                        								net_wgt = api
                        								.column(7)
                        								.data()
                        								.reduce( function (a, b) {
                        									return intVal(a) + intVal(b);
                        								}, 0 );
                        								$(api.column(7).footer()).html(parseFloat(net_wgt).toFixed(2));
                        								
                        								amount = api
                        								.column(5)
                        								.data()
                        								.reduce( function (a, b) {
                        									return intVal(a) + intVal(b);
                        								}, 0 );
                        								$(api.column(5).footer()).html(parseFloat(amount).toFixed(2)); 
                        						} 
                        						}else{
                        							 var api = this.api(), data; 
                        							 $(api.column(6).footer()).html('');  
                        							 $(api.column(7).footer()).html('');  
                        							 $(api.column(8).footer()).html('');  
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
//Green Tag Report
//Sales Comparision
function get_sales_comparision()
{
    
    const monthNames = ["", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
        "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    var chart_details=getMonthlySalesDetails();
    var line = new Morris.Line
	({   
        element: 'sales_chart',    
        data:chart_details.pro_details,
        xkey: 'x',
        parseTime: false,
        ykeys: ['y'],
        xLabelFormat: function (x) {
            console.log(x);
            var index = parseInt(x.src.x);
            return monthNames[index];
        },
        xLabels: "month",
        labels: ['Weight'],
        lineColors: ['#a0d0e0', '#3dbeee'],
        hideHover: 'auto'
	});
	
	
	Morris.Donut({
      element: 'metal_chart',
      data:chart_details.metal_details,
     series: {
        pie: {
          show       : false,
          radius     : 1,
          innerRadius: 0.5,
          resize :false,
          label      : {
            show     : false,
            radius   : 2 / 3,
            formatter: labelFormatter,
            threshold: 0.1
          }
        }
      },
      legend: {
        show: false
      }
    });
	 
}
function labelFormatter(label, series) {
    return '<div style="font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;">'
      + label
      + '<br>'
      + Math.round(series.percent) + '%</div>'
  }
function getMonthlySalesDetails()
{
    my_Date = new Date();
	var data = "";       
	$.ajax({           
				type: 'POST',            
				url:base_url+ "index.php/admin_ret_reports/sales_comparision/ajax?nocache=" + my_Date.getUTCSeconds(),
				dataType: 'json',           
				async: false,                       
				data: {'id_product':$('#prod_select').val()},           
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
//Sales Comparision
//Approval Pending
function getApprovalPending()
{
    var company_name=$('#company_name').val();
    var title="<b><span style='font-size:15pt;margin-left:30%;'>"+company_name+"</span>-</b><b><span style='font-size:12pt;'></span></b>"+"<span style=font-size:13pt;>&nbsp;&nbsp;Intransit Report &nbsp;&nbsp;</span>";
    $("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/branch_trans/intransit?nocache=" + my_Date.getUTCSeconds(),
			 dataType:"JSON",
			 type:"POST",
			 data:{'id_branch':ctrl_page[3]},
			 success:function(data)
			 {
    				var oTable = $('#approval_pending').DataTable();
    				oTable.clear().draw();				  
    				if (data!= null && data.length > 0)
    				{  	
    					oTable = $('#approval_pending').dataTable({
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
    								    title: "Intransit Report",
    								  }
    								 ],
    						"aaData": data,
    						"aoColumns": [	
    										{ "mDataProp": "branch_trans_code" },
    										{ "mDataProp": "created_time" },
    										{ "mDataProp": "from_branch" },
    										{ "mDataProp": "to_branch" },
    										{ "mDataProp": "transfer_item_type" },
    										{ "mDataProp": "product" },
    										{ "mDataProp": "pieces" },
    										{ "mDataProp": "grs_wt" },
    										{ "mDataProp": "net_wt" },
    									  ],
    							"footerCallback": function( row, data, start, end, display ){
						var cshtotal = 0; 
						if(data.length>0){
							var api = this.api(), data;
							for( var i=0; i<=data.length-1;i++){
								var intVal = function ( i ) {
									return typeof i === 'string' ?
									i.replace(/[\$,]/g, '')*1 :
									typeof i === 'number' ?
									i : 0;
								};	
								
								$(api.column(0).footer() ).html('Total');	
								pieces = api
								.column(6)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(6).footer()).html(parseFloat(pieces).toFixed(2));
								
								gross_wgt = api
								.column(7)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(7).footer()).html(parseFloat(gross_wgt).toFixed(2));
								
								net_wgt = api
								.column(8)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(8).footer()).html(parseFloat(net_wgt).toFixed(2)); 
						} 
						}else{
							 var api = this.api(), data; 
							 $(api.column(6).footer()).html('');  
							 $(api.column(7).footer()).html('');  
							 $(api.column(8).footer()).html('');  
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
//Approval Pending
//Credit Pending
function set_credit_pending_list()
{
    
    var branch_name=($('#branch_name').val()!='' && $('#branch_name').val()!=undefined ? $('#branch_name').val() : $("#branch_select option:selected").text());
	var company_name=$('#company_name').val();
	var title="<b><span style='font-size:15pt;margin-left:36%;'>"+company_name+"</span></b><b><span style='font-size:15pt;'></span></b></br>"+"<span style=font-size:13pt;margin-left:38%;>&nbsp;&nbsp;Credit Pending Report &nbsp;</span>";
	$("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/credit_pending/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'id_customer':$('#select_customer').val(),'id_branch':($('#branch_filter').val()!='' && $('#branch_filter').val()!=undefined ? $('#branch_filter').val(): $("#branch_select").val()),'from_date':$('#rpt_from_date').html(),'to_date' :$('#rpt_to_date').html()}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data)
			 {
			        var list=data.list;
    				var oTable = $('#credit_issued_list').DataTable();
    				oTable.clear().draw();				  
    				if (list!= null && list.length > 0)
    				{  	
    					oTable = $('#credit_issued_list').dataTable({
    						"bDestroy": true,
    		                "bInfo": true,
    		                "bFilter": true,
    		                "scrollX":'100%',
    		                "bSort": true,
    		                "dom": 'lBfrtip',
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
    								    title: "Credit Pending Report",
    								  }
    								 ],
    						"aaData": list,
    						"aoColumns": [	
    										{ "mDataProp": "branch_name" },
    										{ "mDataProp": "cus_name" },
    										{ "mDataProp": "mobile" },
    										{ "mDataProp": function ( row, type, val, meta )
    										{ 
    										    if(row.type==0)
    										    {
    										        var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+row.bill_id;
    										    }else
    										    {
    										        var url = base_url+'index.php/admin_ret_billing/issue/issue_print/'+row.bill_id;
    										    }
    										  
    										  return '<a href='+url+' target="_blank">'+row.bill_no+'</a>';
                    		                }},
    										{ "mDataProp": "bill_date" },
    										{ "mDataProp": "tot_bill_amount" },
    										{ "mDataProp": "due_amount" },
    										{ "mDataProp": "paid_amount" },
    										{ "mDataProp": "credit_ret_amt" },
    										{ "mDataProp": "bal_amt" },
    										{
                                                "mDataProp": null,
                                                "sClass": "control center", 
                                                "sDefaultContent":  '<span class="drill-val"><i class="fa fa-chevron-circle-down text-teal"></i></span>'
                                            },
    									  ],
    									  
                                        "footerCallback": function( row, data, start, end, display ){
                                            var cshtotal = 0; 
                                            if(list.length>0)
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
                                                    
                                                     total_amount = api
                                                    .column(8)
                                                    .data()
                                                    .reduce( function (a, b) {
                                                    return intVal(a) + intVal(b);
                                                    }, 0 );
                                                    $(api.column(8).footer()).html(parseFloat(total_amount).toFixed(2));
                                                } 
                                            }
                                            else
                                            {
                                                var api = this.api(), data; 
                                                $(api.column(7).footer()).html('');  
                                            }
                                        }
    						
    					});
						var anOpen =[]; 
                		$(document).on('click',"#credit_issued_list .control", function(){ 
                		   var nTr = this.parentNode;
                		   var i = $.inArray( nTr, anOpen );
                		 
                		   if ( i === -1 ) { 
                				$('.drill-val', this).html('<i class="fa fa-chevron-circle-up text-teal"></i>'); 
                				oTable.fnOpen( nTr, fnFormatRowCreditDetails(oTable, nTr), 'details' );
                				anOpen.push( nTr ); 
                		    }
                		    else { 
                				$('.drill-val', this).html('<i class="fa fa-chevron-circle-down text-teal"></i>');
                				oTable.fnClose( nTr );
                				anOpen.splice( i, 1 );
                		    }
                		} );
                    		
    				}
				$("div.overlay").css("display", "none"); 
              },
			  error:function(error)  
			  {
				 $("div.overlay").css("display", "none"); 
			  }	 
	      });
}
function fnFormatRowCreditDetails(oTable, nTr)
{
    var oData = oTable.fnGetData( nTr );
    var rowDetail = '';
    var prodTable = 
        '<div class="innerDetails">'+
        '<table class="table table-responsive table-bordered text-center table-sm">'+ 
        '<tr class="bg-teal">'+
        '<th>S.No</th>'+ 
        '<th>Bill No</th>'+
        '<th>Bill Date</th>'+
        '<th>Bill Amount</th>'+
        '<th>Discount Amount</th>'+
        '<th>Purchase Amount</th>'+
        '</tr>';
    var credit_collection = oData.credit_collection; 
    $.each(credit_collection, function (idx, val) {
    var ref_no = val.tag_id+'_'+oData.product_id;
    if(val.type==0)
    {
         var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+val.bill_id;
    }
    else if(val.type==1)
    {
         var url = base_url+'index.php/admin_ret_billing/receipt/receipt_print/'+val.bill_id;
    }
   
    prodTable += 
    '<tr class="prod_det_btn">'+
    '<td>'+parseFloat(idx+1)+'</td>'+
    '<td><a href='+url+' target="_blank">'+val.bill_no+'</a></td>'+
    '<td>'+val.bill_date+'</td>'+
    '<td>'+val.tot_amt_received+'</td>'+
    '<td>'+val.credit_disc_amt+'</td>'+
    '<td>'+val.old_metal_amount+'</td>'+
    '</tr>'; 
    }); 
    rowDetail = prodTable+'</table></div>';
    return rowDetail;
}
//Credit Pending
//Stock and Sales
function set_stock_and_sales_list()
{
    
    var branch_name=($('#branch_name').val()!='' && $('#branch_name').val()!=undefined ? $('#branch_name').val() : $("#branch_select option:selected").text());
	var company_name=$('#company_name').val();
	var title="<b><span style='font-size:15pt;margin-left:36%;'>"+company_name+"</span></b><b><span style='font-size:15pt;'></span></b></br>"+"<span style=font-size:13pt;margin-left:38%;>&nbsp;&nbsp;STOCK AND SALES REPORT &nbsp;</span>";
	$("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/stock_and_sales_report/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'dt_range' :$("#dt_range").val(),'id_branch':($('#branch_filter').val()!='' && $('#branch_filter').val()!=undefined ? $('#branch_filter').val(): $("#branch_select").val())}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data)
			 {
			     
			    $("#stock_details > tbody > tr").remove();  
				$('#stock_details').dataTable().fnClearTable();
    		    $('#stock_details').dataTable().fnDestroy();
    		    
			    var trHtml='';
			    var branch_inw_details=data.list.branch_inw_details;
			    var branch_out_details=data.list.branch_out_details;
			    var lot_details=data.list.lot_details;
			    var other_issue_details=data.list.other_issue_details;
			    var sales_details=data.list.sales_details;
			    if(lot_details.length>0)
			    {
			       
			        $.each(lot_details,function(key,lot){
			            branch_summary_url=base_url+'index.php/admin_ret_lot/branch_acknowladgement/1/'+lot.tag_lot_id+'/'+lot.current_branch;
			            trHtml+='<tr>'
			                    +'<td><a href='+branch_summary_url+' target="_blank">'+lot.tag_lot_id+'</a></td>'
			                    +'<td>'+lot.inw_date+'</td>'
			                    +'<td>'+lot.product_name+'</td>'
			                    +'<td>'+lot.tot_pcs+'</td>'
			                    +'<td>'+lot.branch_name+'</td>'
			                +'</tr>';
			        });
			    }
			    if(branch_inw_details.length>0)
			    {
			        trHtml+='<tr>'
			                    +'<td><strong>BRANCH INWARD<strong></td>'
			                    +'<td></td>'
			                    +'<td></td>'
			                    +'<td></td>'
			                    +'<td></td>'
			                +'</tr>';
	                 trHtml+='<tr>'
	                    +'<td><strong>BT Code<strong></td>'
	                    +'<td><strong>From Branch<strong></td>'
	                    +'<td><strong>To Branch<strong></td>'
	                    +'<td><strong>Pcs<strong></td>'
	                    +'<td><strong>Status<strong></td>'
	                +'</tr>';
	                $.each(branch_inw_details,function(k,inw){
	                    summary_url=base_url+'index.php/admin_ret_brntransfer/branch_transfer/print/'+inw.branch_trans_code+'/'+inw.transfer_item_type+'/'+1;
	                    trHtml+='<tr>'
			                    +'<td><a href='+summary_url+' target="_blank">'+inw.branch_transfer_id+'</a></td>'
			                    +'<td>'+inw.from_branch+'</td>'
			                    +'<td>'+inw.to_branch+'</td>'
			                    +'<td>'+inw.pieces+'</td>'
			                    +'<td>'+inw.bt_status+'</td>'
			                +'</tr>';
	                    
	                });
			    }
			    if(other_issue_details.length>0)
			    {
			        trHtml+='<tr>'
			                    +'<td><strong>OTHER ISSUE<strong></td>'
			                     +'<td></td>'
			                     +'<td></td>'
			                     +'<td></td>'
			                     +'<td></td>'
			                +'</tr>';
	                 trHtml+='<tr>'
	                    +'<td><strong>BT Code<strong></td>'
	                    +'<td><strong>From Branch<strong></td>'
	                    +'<td><strong>To Branch<strong></td>'
	                    +'<td><strong>Pcs<strong></td>'
	                    +'<td><strong>Status<strong></td>'
	                +'</tr>';
	                $.each(other_issue_details,function(k,out){
	                    summary_url=base_url+'index.php/admin_ret_brntransfer/branch_transfer/print/'+out.branch_trans_code+'/'+out.transfer_item_type+'/'+1;
	                    trHtml+='<tr>'
			                     +'<td><a href='+summary_url+' target="_blank">'+out.branch_transfer_id+'</a></td>'
			                    +'<td>'+out.from_branch+'</td>'
			                    +'<td>'+out.to_branch+'</td>'
			                    +'<td>'+out.pieces+'</td>'
			                    +'<td>'+out.bt_status+'</td>'
			                +'</tr>';
	                    
	                });
			    }
			    if(branch_out_details.length>0)
			    {
			        trHtml+='<tr>'
			                    +'<td><strong>BRANCH OUTWARD<strong></td>'
			                     +'<td></td>'
			                     +'<td></td>'
			                     +'<td></td>'
			                     +'<td></td>'
			                +'</tr>';
	                 trHtml+='<tr>'
	                    +'<td><strong>BT Code<strong></td>'
	                    +'<td><strong>From Branch<strong></td>'
	                    +'<td><strong>To Branch<strong></td>'
	                    +'<td><strong>Pcs<strong></td>'
	                    +'<td><strong>Status<strong></td>'
	                +'</tr>';
	                $.each(branch_out_details,function(k,out){
	                    summary_url=base_url+'index.php/admin_ret_brntransfer/branch_transfer/print/'+out.branch_trans_code+'/'+out.transfer_item_type+'/'+1;
	                    trHtml+='<tr>'
			                     +'<td><a href='+summary_url+' target="_blank">'+out.branch_transfer_id+'</a></td>'
			                    +'<td>'+out.from_branch+'</td>'
			                    +'<td>'+out.to_branch+'</td>'
			                    +'<td>'+out.pieces+'</td>'
			                    +'<td>'+out.bt_status+'</td>'
			                +'</tr>';
	                    
	                });
			    }
			 /*   if(sales_details.length>0)
			    {
			        trHtml+='<tr>'
			                    +'<td colspan="5"><strong>SALES DETAILS<strong></td>'
			                +'</tr>';
	                 trHtml+='<tr>'
	                    +'<td><strong>S.No<strong></td>'
	                    +'<td><strong>Product Name<strong></td>'
	                    +'<td><strong>Weight<strong></td>'
	                    +'<td><strong>Pcs<strong></td>'
	                    +'<td><strong>Amount<strong></td>'
	                +'</tr>';
	                var i=1;
	                var total_sales_amt=0;
	                var total_sales_wt=0;
	                var total_sales_pcs=0;
	                $.each(sales_details,function(k,s){
	                    total_sales_amt+=parseFloat(s.sale_amt);
	                    total_sales_wt+=parseFloat(s.tot_sale_wt);
	                    total_sales_pcs+=parseFloat(s.tot_pcs);
	                    trHtml+='<tr>'
			                    +'<td>'+i+'</td>'
			                    +'<td>'+s.product_name+'</td>'
			                    +'<td>'+s.tot_sale_wt+'</td>'
			                    +'<td>'+s.tot_pcs+'</td>'
			                    +'<td>'+s.sale_amt+'</td>'
			                +'</tr>';
			                i++;
	                });
	                 trHtml+='<tr>'
	                    +'<td colspan="1"><strong>TOTAL<strong></td>'
	                    +'<td><strong><strong></td>'
	                    +'<td><strong>'+parseFloat(total_sales_wt).toFixed(3)+'<strong></td>'
	                    +'<td><strong>'+parseFloat(total_sales_pcs).toFixed(3)+'<strong></td>'
	                    +'<td><strong>'+parseFloat(total_sales_amt).toFixed(2)+'<strong></td>'
	                +'</tr>';
			    }*/
			    $('#stock_details > tbody').html(trHtml);
			    
			     // Check and initialise datatable
	                 if ( ! $.fn.DataTable.isDataTable( '#stock_details' ) ) { 
	                     oTable = $('#stock_details').dataTable({ 
						                "bSort": false, 
						                "bInfo": true, 
						                "scrollX":'100%',  
						                "dom": 'lBfrtip',
						                "lengthMenu": [[-1, 25, 50, 100, 250], ["All" ,25, 50, 100, 250]],
						                "buttons": [
													{
													   extend: 'print',
													   footer: true,
													   title: '',
													   messageTop: title,
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
													    title: 'STOCK AND SALES',
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
//Stock and Sales
//Incentive
function set_incentive_report()
{
    var company_name=$('#company_name').val();
    var title="<b><span style='font-size:15pt;margin-left:30%;'>"+company_name+"</span>-</b><b><span style='font-size:12pt;'></span></b>"+"<span style=font-size:13pt;>&nbsp;&nbsp;Incentive Report &nbsp;&nbsp;</span>";
    $("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/incentive_report/ajax?nocache=" + my_Date.getUTCSeconds(),
			 dataType:"JSON",
			 type:"POST",
			 data:{'from_date':$('#rpt_payments1').html(),'to_date' :$('#rpt_payments2').html()},
			 success:function(data)
			 {
			        var list=data.list;
    				var oTable = $('#incentive_list').DataTable();
    				oTable.clear().draw();				  
    				if (list!= null && list.length > 0)
    				{  	
    					oTable = $('#incentive_list').dataTable({
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
    								    title: "Incentive Report",
    								  }
    								 ],
    						"aaData": list,
    						"aoColumns": [	
    						                { "mDataProp": function ( row, type, val, meta ){ 
                                                chekbox='<input type="checkbox" class="id_wallet_account" name="id_wallet_account[]" value="'+row.id_wallet_account+'"/>'+row.id_wallet_account; 
                                                return chekbox;
                                            }},
        
    										{ "mDataProp": function ( row, type, val, meta )
    										{ 
    										  var url = base_url+'index.php/admin_ret_reports/incentive_report/emp_list/'+row.id_wallet_account;
    										  return '<a href='+url+' target="_blank">'+row.wallet_acc_number+'</a>';
                    		                }},
    										{ "mDataProp": "emp_name" },
    										{ "mDataProp": "branch_name" },
    										{ "mDataProp": "mobile" },
    										{ "mDataProp": "total_value" },
    										{ "mDataProp": function ( row, type, val, meta )
    										{ 
    										  return '<input type="text" class="form-control debit_amount" value="'+row.total_value+'" placeholder="Enter Debit Amount"><input type="hidden" class="form-control act_wallet_amt" value="'+row.total_value+'">';
                    		                }},
    									  ],
    							"footerCallback": function( row, data, start, end, display ){
						var cshtotal = 0; 
						if(data.length>0){
							var api = this.api(), data;
							for( var i=0; i<=data.length-1;i++){
								var intVal = function ( i ) {
									return typeof i === 'string' ?
									i.replace(/[\$,]/g, '')*1 :
									typeof i === 'number' ?
									i : 0;
								};	
								
								$(api.column(0).footer() ).html('Total');	
							
								total_value = api
								.column(5)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(5).footer()).html(parseFloat(total_value).toFixed(2));
								
							
						} 
						}else{
							 var api = this.api(), data; 
							 $(api.column(2).footer()).html('');  
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
$(document).on('keyup','.debit_amount',function(){
    var row = $(this).closest('tr');
    var act_wallet_amt=row.find('.act_wallet_amt').val();
    if(parseFloat(act_wallet_amt)<parseFloat(this.value))
    {
         $(this).val(act_wallet_amt);
         $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Exceed Amount Limit..'});
         row.find("td:first input:checkbox").prop("checked",false);
    }else{
        row.find("td:first input:checkbox").prop("checked",true);
    }
});
$('#incentive_pay').on('click',function(){
    if($("input[name='id_wallet_account[]']:checked").val())
        {
            $('#incentive_pay').prop('disabled',true);
            var selected = [];
            $("#incentive_list tbody tr").each(function(index, value)
            {
                if($(value).find("input[name='id_wallet_account[]']:checked").is(":checked"))
                {
                    transData = { 
                    'id_wallet_account'  : $(value).find(".id_wallet_account").val(),
                    'debit_amount'  : $(value).find(".debit_amount").val(),
                    }
                    selected.push(transData);	
                }
            })
            req_data = selected;
            debit_payment(req_data);
        }
        else
        {
            $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select Account..'});
            $('#incentive_pay').prop('disabled',false);
        }
});
function debit_payment(req_data)
{
    my_Date = new Date();
    $("div.overlay").css("display", "block"); 
    $.ajax({
    url:base_url+ "index.php/admin_ret_reports/debit_payment?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),
    data:  {'req_data':req_data},
    type:"POST",
    dataType:"JSON",
    async:false,
    success:function(data){
        if(data.status)
	    {
	        $.toaster({ priority : 'success', title : 'Warning!', message : ''+"</br>"+data.message});
	    }else{
	        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+data.message});
	    }
        location.reload();
        $("div.overlay").css("display", "none"); 
    },
    error:function(error)  
    {
        console.log(error);
        $("div.overlay").css("display", "none"); 
    }	 
    });
}
function set_incentive_emp_list()
{
    var company_name=$('#company_name').val();
    var title="<b><span style='font-size:15pt;margin-left:30%;'>"+company_name+"</span>-</b><b><span style='font-size:12pt;'></span></b>"+"<span style=font-size:13pt;>&nbsp;&nbsp;Incentive Report &nbsp;&nbsp;</span>";
    $("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/incentive_report/emp_report/?nocache=" + my_Date.getUTCSeconds(),
			 dataType:"JSON",
			 type:"POST",
			 data:{'id_wallet_account':ctrl_page[3]},
			 success:function(data)
			 {
			        var list=data;
    				var oTable = $('#emp_list').DataTable();
    				oTable.clear().draw();				  
    				if (list!= null && list.length > 0)
    				{  	
    					oTable = $('#emp_list').dataTable({
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
    								    title: "Incentive Report",
    								  }
    								 ],
    						"aaData": list,
    						"aoColumns": [	
    										{ "mDataProp": "id_wallet_transaction" },
    										{ "mDataProp": "date_transaction" },
    										{ "mDataProp": function ( row, type, val, meta ){
                                                if(row.type==1)
                                                {
                                                    var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+row.bill_id;
                                                    return '<a href='+url+' target="_blank">'+row.bill_no+'</a>';
                                                }else{
                                                    var url = base_url+'index.php/reports/payment/account/'+row.id_sch_ac;
                                                    return '<a href='+url+' target="_blank">'+row.id_sch_ac+'</a>';
                                                }
                                            
                                            },
                                            },
    									
                                            { "mDataProp": function ( row, type, val, meta )
                                            { 
            								    return '<span class="badge bg-'+row.color+'">'+row.transaction_type+'</span>';
                                            }},
    										{ "mDataProp": "tag_code" },
    										{ "mDataProp": "product_name" },
                                            { "mDataProp": "value" },
                                            { "mDataProp": "description" },
    									  ],
    							"footerCallback": function( row, data, start, end, display ){
						var cshtotal = 0; 
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
							
								total_value = api
								.column(6)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(6).footer()).html(parseFloat(total_value).toFixed(2));
								
							
						} 
						}else{
							 var api = this.api(), data; 
							 $(api.column(2).footer()).html('');  
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
//Incentive
//Month on month sales report
function monthly_slaes_comparision()
{
    $("div.overlay").css("display", "block"); 
    my_Date = new Date();
	$.ajax({
		url: base_url+"index.php/admin_ret_reports/monthly_slaes_comparision/ajax?nocache=" + my_Date.getUTCSeconds(),
		type:"POST",
		data:{'year':$('#cur_year').val(),'id_branch':$('#branch_select').val(),'id_village':$('#select_village').val()},
		dataType:"JSON",
		cache:false,
		success:function(data){
		    var list=data.list;
			$("#sales_comparision > tbody > tr").remove();  
			$('#sales_comparision').dataTable().fnClearTable();
    		$('#sales_comparision').dataTable().fnDestroy();			  
			    var trHTML='';
			      var J_grand_total_gold_wt=0;
			      var J_grand_total_silver_wt=0;
			      var J_grand_total_fixed_amt=0;
			      var F_grand_total_gold_wt=0;
			      var F_grand_total_silver_wt=0;
			      var F_grand_total_fixed_amt=0;
			      var M_grand_total_gold_wt=0;
			      var M_grand_total_silver_wt=0;
			      var M_grand_total_fixed_amt=0;
			      var A_grand_total_gold_wt=0;
			      var A_grand_total_silver_wt=0;
			      var A_grand_total_fixed_amt=0;
			      var may_grand_total_gold_wt=0;
			      var may_grand_total_silver_wt=0;
			      var may_grand_total_fixed_amt=0;
			      var june_grand_total_gold_wt=0;
			      var june_grand_total_silver_wt=0;
			      var june_grand_total_fixed_amt=0;
			      var july_grand_total_gold_wt=0;
			      var july_grand_total_silver_wt=0;
			      var july_grand_total_fixed_amt=0;
			      var aug_grand_total_gold_wt=0;
			      var aug_grand_total_silver_wt=0;
			      var aug_grand_total_fixed_amt=0;
			      var sep_grand_total_gold_wt=0;
			      var sep_grand_total_silver_wt=0;
			      var sep_grand_total_fixed_amt=0;
			      var oct_grand_total_gold_wt=0;
			      var oct_grand_total_silver_wt=0;
			      var oct_grand_total_fixed_amt=0;
			      var nov_grand_total_gold_wt=0;
			      var nov_grand_total_silver_wt=0;
			      var nov_grand_total_fixed_amt=0;
			      var dec_grand_total_gold_wt=0;
			      var dec_grand_total_silver_wt=0;
			      var dec_grand_total_fixed_amt=0;
			    $.each(list,function(village,branch){
			        
			      var id_branch='';
			      var id_village='';
			      var J_total_gold_wt=0;
			      var J_total_silver_wt=0;
			      var J_total_fixed_amt=0;
			      var F_total_gold_wt=0;
			      var F_total_silver_wt=0;
			      var F_total_fixed_amt=0;
			      var M_total_gold_wt=0;
			      var M_total_silver_wt=0;
			      var M_total_fixed_amt=0;
			      var A_total_gold_wt=0;
			      var A_total_silver_wt=0;
			      var A_total_fixed_amt=0;
			      var may_total_gold_wt=0;
			      var may_total_silver_wt=0;
			      var may_total_fixed_amt=0;
			      var june_total_gold_wt=0;
			      var june_total_silver_wt=0;
			      var june_total_fixed_amt=0;
			      var july_total_gold_wt=0;
			      var july_total_silver_wt=0;
			      var july_total_fixed_amt=0;
			      var aug_total_gold_wt=0;
			      var aug_total_silver_wt=0;
			      var aug_total_fixed_amt=0;
			      var sep_total_gold_wt=0;
			      var sep_total_silver_wt=0;
			      var sep_total_fixed_amt=0;
			      var oct_total_gold_wt=0;
			      var oct_total_silver_wt=0;
			      var oct_total_fixed_amt=0;
			      var nov_total_gold_wt=0;
			      var nov_total_silver_wt=0;
			      var nov_total_fixed_amt=0;
			      var dec_total_gold_wt=0;
			      var dec_total_silver_wt=0;
			      var dec_total_fixed_amt=0;
			        $.each(branch,function(branches,months){
			            var J_gold_wt=0;
			            var J_silver_wt=0;
			            var J_fixed_amt=0;
			            var F_gold_wt=0;
			            var F_silver_wt=0;
			            var F_fixed_amt=0;
			            var M_gold_wt=0;
			            var M_silver_wt=0;
			            var M_fixed_amt=0;
			            var A_gold_wt=0;
			            var A_silver_wt=0;
			            var A_fixed_amt=0;
			            var may_gold_wt=0;
			            var may_silver_wt=0;
			            var may_fixed_amt=0;
			            var june_gold_wt=0;
			            var june_silver_wt=0;
			            var june_fixed_amt=0;
			            var july_gold_wt=0;
			            var july_silver_wt=0;
			            var july_fixed_amt=0;
			            var aug_gold_wt=0;
			            var aug_silver_wt=0;
			            var aug_fixed_amt=0;
			            var sep_gold_wt=0;
			            var sep_silver_wt=0;
			            var sep_fixed_amt=0;
			            var oct_gold_wt=0;
			            var oct_silver_wt=0;
			            var oct_fixed_amt=0;
			            var nov_gold_wt=0;
			            var nov_silver_wt=0;
			            var nov_fixed_amt=0;
			            var dec_gold_wt=0;
			            var dec_silver_wt=0;
			            var dec_fixed_amt=0;
			            $.each(months,function(month,items){
			            id_branch=items[0].id_branch;
			            id_village=items[0].id_village;
			                if(month=='January')
			                {
			                    $.each(items,function(key,item){
			                        if(item.metal=='GOLD')
			                        {
			                             J_gold_wt+=parseFloat(item.total_net_wt);
			                        }
			                        if(item.metal=='SILVER')
			                        {
			                             J_silver_wt+=parseFloat(item.total_net_wt);
			                        }
			                        J_fixed_amt+=parseFloat(item.tot_fixed_amt);
			                    });
			                }
			                if(month=='February')
			                {
			                    $.each(items,function(key,item){
			                        F_fixed_amt+=parseFloat(item.tot_fixed_amt);
			                        if(item.metal=='GOLD')
			                        {
			                             F_gold_wt+=parseFloat(item.total_net_wt);
			                        }
			                        if(item.metal=='SILVER')
			                        {
			                             F_silver_wt+=parseFloat(item.total_net_wt);
			                        }
			                    });
			                }
			                if(month=='March')
			                {
			                    $.each(items,function(key,item){
			                        M_fixed_amt+=parseFloat(item.tot_fixed_amt);
			                        if(item.metal=='GOLD')
			                        {
			                             M_gold_wt+=parseFloat(item.total_net_wt);
			                        }
			                        if(item.metal=='SILVER')
			                        {
			                            M_silver_wt+=parseFloat(item.total_net_wt);
			                        }
			                    });
			                }
			                if(month=='April')
			                {
			                    $.each(items,function(key,item){
			                        A_fixed_amt+=parseFloat(item.tot_fixed_amt);
			                        if(item.metal=='GOLD')
			                        {
			                             A_gold_wt+=parseFloat(item.total_net_wt);
			                        }
			                        if(item.metal=='SILVER')
			                        {
			                            A_silver_wt+=parseFloat(item.total_net_wt);
			                        }
			                    });
			                }
			                if(month=='May')
			                {
			                    $.each(items,function(key,item){
			                        may_fixed_amt+=parseFloat(item.tot_fixed_amt);
			                        if(item.metal=='GOLD')
			                        {
			                           may_gold_wt+=parseFloat(item.total_net_wt);
			                        }
			                        if(item.metal=='SILVER')
			                        {
			                            may_silver_wt+=parseFloat(item.total_net_wt);
			                        }
			                    });
			                }
			                if(month=='June')
			                {
			                    $.each(items,function(key,item){
			                        june_fixed_amt+=parseFloat(item.tot_fixed_amt);
			                        if(item.metal=='GOLD')
			                        {
			                           june_gold_wt+=parseFloat(item.total_net_wt);
			                        }
			                        if(item.metal=='SILVER')
			                        {
			                            june_silver_wt+=parseFloat(item.total_net_wt);
			                        }
			                    });
			                }
			                if(month=='July')
			                {
			                    $.each(items,function(key,item){
			                        july_fixed_amt+=parseFloat(item.tot_fixed_amt);
			                        if(item.metal=='GOLD')
			                        {
			                           july_gold_wt+=parseFloat(item.total_net_wt);
			                        }
			                        if(item.metal=='SILVER')
			                        {
			                            july_silver_wt+=parseFloat(item.total_net_wt);
			                        }
			                    });
			                }
			                if(month=='August')
			                {
			                    $.each(items,function(key,item){
			                        aug_fixed_amt+=parseFloat(item.tot_fixed_amt);
			                        if(item.metal=='GOLD')
			                        {
			                           aug_gold_wt+=parseFloat(item.total_net_wt);
			                        }
			                        if(item.metal=='SILVER')
			                        {
			                            aug_silver_wt+=parseFloat(item.total_net_wt);
			                        }
			                    });
			                }
			                if(month=='September')
			                {
			                    $.each(items,function(key,item){
			                        sep_fixed_amt+=parseFloat(item.tot_fixed_amt);
			                        if(item.metal=='GOLD')
			                        {
			                           sep_gold_wt+=parseFloat(item.total_net_wt);
			                        }
			                        if(item.metal=='SILVER')
			                        {
			                            sep_silver_wt+=parseFloat(item.total_net_wt);
			                        }
			                    });
			                }
			                if(month=='October')
			                {
			                    $.each(items,function(key,item){
			                        oct_fixed_amt+=parseFloat(item.tot_fixed_amt);
			                        if(item.metal=='GOLD')
			                        {
			                           oct_gold_wt+=parseFloat(item.total_net_wt);
			                        }
			                        if(item.metal=='SILVER')
			                        {
			                            oct_silver_wt+=parseFloat(item.total_net_wt);
			                        }
			                    });
			                }
			                if(month=='November')
			                {
			                    $.each(items,function(key,item){
			                        nov_fixed_amt+=parseFloat(item.tot_fixed_amt);
			                        if(item.metal=='GOLD')
			                        {
			                           nov_gold_wt+=parseFloat(item.total_net_wt);
			                        }
			                        if(item.metal=='SILVER')
			                        {
			                            nov_silver_wt+=parseFloat(item.total_net_wt);
			                        }
			                    });
			                }
			                if(month=='December')
			                {
			                    $.each(items,function(key,item){
			                        dec_fixed_amt+=parseFloat(item.tot_fixed_amt);
			                        if(item.metal=='GOLD')
			                        {
			                           dec_gold_wt+=parseFloat(item.total_net_wt);
			                        }
			                        if(item.metal=='SILVER')
			                        {
			                            dec_silver_wt+=parseFloat(item.total_net_wt);
			                        }
			                    });
			                }
			                
			            });
			            
			             trHTML+='<tr>'
			               +'<td>'+village+'</td>'
			               +'<td>'+branches+'</td>'
			               +'<td><a href='+base_url+'index.php/admin_ret_reports/monthly_slaes_comparision/detailed_list/?id_branch='+id_branch+'&id_village='+id_village+'&month=01&year='+$('#cur_year').val()+'&id_metal=1&sales_mode=2 target="_blank">'+parseFloat(J_gold_wt).toFixed(3)+'</td>'
			               +'<td><a href='+base_url+'index.php/admin_ret_reports/monthly_slaes_comparision/detailed_list/?id_branch='+id_branch+'&id_village='+id_village+'&month=01&year='+$('#cur_year').val()+'&id_metal=2&sales_mode=2 target="_blank">'+parseFloat(J_silver_wt).toFixed(3)+'</td>'
			               +'<td><a href='+base_url+'index.php/admin_ret_reports/monthly_slaes_comparision/detailed_list/?id_branch='+id_branch+'&id_village='+id_village+'&month=01&year='+$('#cur_year').val()+'&id_metal=0&sales_mode=1 target="_blank">'+parseFloat(J_fixed_amt).toFixed(3)+'</td>'
			               +'<td><a href='+base_url+'index.php/admin_ret_reports/monthly_slaes_comparision/detailed_list/?id_branch='+id_branch+'&id_village='+id_village+'&month=02&year='+$('#cur_year').val()+'&id_metal=1&sales_mode=2 target="_blank">'+parseFloat(F_gold_wt).toFixed(3)+'</td>'
			               +'<td><a href='+base_url+'index.php/admin_ret_reports/monthly_slaes_comparision/detailed_list/?id_branch='+id_branch+'&id_village='+id_village+'&month=02&year='+$('#cur_year').val()+'&id_metal=2&sales_mode=2 target="_blank">'+parseFloat(F_silver_wt).toFixed(3)+'</td>'
			               +'<td><a href='+base_url+'index.php/admin_ret_reports/monthly_slaes_comparision/detailed_list/?id_branch='+id_branch+'&id_village='+id_village+'&month=02&year='+$('#cur_year').val()+'&id_metal=1&sales_mode=1 target="_blank">'+parseFloat(F_fixed_amt).toFixed(3)+'</td>'
			               +'<td><a href='+base_url+'index.php/admin_ret_reports/monthly_slaes_comparision/detailed_list/?id_branch='+id_branch+'&id_village='+id_village+'&month=03&year='+$('#cur_year').val()+'&id_metal=1&sales_mode=2 target="_blank">'+parseFloat(M_gold_wt).toFixed(3)+'</td>'
			               +'<td><a href='+base_url+'index.php/admin_ret_reports/monthly_slaes_comparision/detailed_list/?id_branch='+id_branch+'&id_village='+id_village+'&month=03&year='+$('#cur_year').val()+'&id_metal=2&sales_mode=2 target="_blank">'+parseFloat(M_silver_wt).toFixed(3)+'</td>'
			               +'<td><a href='+base_url+'index.php/admin_ret_reports/monthly_slaes_comparision/detailed_list/?id_branch='+id_branch+'&id_village='+id_village+'&month=03&year='+$('#cur_year').val()+'&id_metal=1&sales_mode=1 target="_blank">'+parseFloat(M_fixed_amt).toFixed(3)+'</td>'
			               +'<td><a href='+base_url+'index.php/admin_ret_reports/monthly_slaes_comparision/detailed_list/?id_branch='+id_branch+'&id_village='+id_village+'&month=04&year='+$('#cur_year').val()+'&id_metal=1&sales_mode=2 target="_blank">'+parseFloat(A_gold_wt).toFixed(3)+'</td>'
			               +'<td><a href='+base_url+'index.php/admin_ret_reports/monthly_slaes_comparision/detailed_list/?id_branch='+id_branch+'&id_village='+id_village+'&month=04&year='+$('#cur_year').val()+'&id_metal=2&sales_mode=2 target="_blank">'+parseFloat(A_silver_wt).toFixed(3)+'</td>'
			               +'<td><a href='+base_url+'index.php/admin_ret_reports/monthly_slaes_comparision/detailed_list/?id_branch='+id_branch+'&id_village='+id_village+'&month=04&year='+$('#cur_year').val()+'&id_metal=1&sales_mode=1 target="_blank">'+parseFloat(A_fixed_amt).toFixed(3)+'</td>'
			               +'<td><a href='+base_url+'index.php/admin_ret_reports/monthly_slaes_comparision/detailed_list/?id_branch='+id_branch+'&id_village='+id_village+'&month=05&year='+$('#cur_year').val()+'&id_metal=1&sales_mode=2 target="_blank">'+parseFloat(may_gold_wt).toFixed(3)+'</td>'
			               +'<td><a href='+base_url+'index.php/admin_ret_reports/monthly_slaes_comparision/detailed_list/?id_branch='+id_branch+'&id_village='+id_village+'&month=05&year='+$('#cur_year').val()+'&id_metal=2&sales_mode=2 target="_blank">'+parseFloat(may_silver_wt).toFixed(3)+'</td>'
			               +'<td><a href='+base_url+'index.php/admin_ret_reports/monthly_slaes_comparision/detailed_list/?id_branch='+id_branch+'&id_village='+id_village+'&month=05&year='+$('#cur_year').val()+'&id_metal=1&sales_mode=1 target="_blank">'+parseFloat(may_fixed_amt).toFixed(3)+'</td>'
			               +'<td><a href='+base_url+'index.php/admin_ret_reports/monthly_slaes_comparision/detailed_list/?id_branch='+id_branch+'&id_village='+id_village+'&month=06&year='+$('#cur_year').val()+'&id_metal=1&sales_mode=2 target="_blank">'+parseFloat(june_gold_wt).toFixed(3)+'</td>'
			               +'<td><a href='+base_url+'index.php/admin_ret_reports/monthly_slaes_comparision/detailed_list/?id_branch='+id_branch+'&id_village='+id_village+'&month=06&year='+$('#cur_year').val()+'&id_metal=2&sales_mode=2 target="_blank">'+parseFloat(june_silver_wt).toFixed(3)+'</td>'
			               +'<td><a href='+base_url+'index.php/admin_ret_reports/monthly_slaes_comparision/detailed_list/?id_branch='+id_branch+'&id_village='+id_village+'&month=06&year='+$('#cur_year').val()+'&id_metal=1&sales_mode=1 target="_blank">'+parseFloat(june_fixed_amt).toFixed(3)+'</td>'
			               +'<td><a href='+base_url+'index.php/admin_ret_reports/monthly_slaes_comparision/detailed_list/?id_branch='+id_branch+'&id_village='+id_village+'&month=07&year='+$('#cur_year').val()+'&id_metal=1&sales_mode=2 target="_blank">'+parseFloat(july_gold_wt).toFixed(3)+'</td>'
			               +'<td><a href='+base_url+'index.php/admin_ret_reports/monthly_slaes_comparision/detailed_list/?id_branch='+id_branch+'&id_village='+id_village+'&month=07&year='+$('#cur_year').val()+'&id_metal=2&sales_mode=2 target="_blank">'+parseFloat(july_silver_wt).toFixed(3)+'</td>'
			               +'<td><a href='+base_url+'index.php/admin_ret_reports/monthly_slaes_comparision/detailed_list/?id_branch='+id_branch+'&id_village='+id_village+'&month=07&year='+$('#cur_year').val()+'&id_metal=1&sales_mode=1 target="_blank">'+parseFloat(july_fixed_amt).toFixed(3)+'</td>'
			               +'<td><a href='+base_url+'index.php/admin_ret_reports/monthly_slaes_comparision/detailed_list/?id_branch='+id_branch+'&id_village='+id_village+'&month=08&year='+$('#cur_year').val()+'&id_metal=1&sales_mode=2 target="_blank">'+parseFloat(aug_gold_wt).toFixed(3)+'</td>'
			               +'<td><a href='+base_url+'index.php/admin_ret_reports/monthly_slaes_comparision/detailed_list/?id_branch='+id_branch+'&id_village='+id_village+'&month=08&year='+$('#cur_year').val()+'&id_metal=2&sales_mode=2 target="_blank">'+parseFloat(aug_silver_wt).toFixed(3)+'</td>'
			               +'<td><a href='+base_url+'index.php/admin_ret_reports/monthly_slaes_comparision/detailed_list/?id_branch='+id_branch+'&id_village='+id_village+'&month=08&year='+$('#cur_year').val()+'&id_metal=1&sales_mode=1 target="_blank">'+parseFloat(aug_fixed_amt).toFixed(3)+'</td>'
			               +'<td><a href='+base_url+'index.php/admin_ret_reports/monthly_slaes_comparision/detailed_list/?id_branch='+id_branch+'&id_village='+id_village+'&month=09&year='+$('#cur_year').val()+'&id_metal=1&sales_mode=2 target="_blank">'+parseFloat(sep_gold_wt).toFixed(3)+'</td>'
			               +'<td><a href='+base_url+'index.php/admin_ret_reports/monthly_slaes_comparision/detailed_list/?id_branch='+id_branch+'&id_village='+id_village+'&month=09&year='+$('#cur_year').val()+'&id_metal=2&sales_mode=2 target="_blank">'+parseFloat(sep_silver_wt).toFixed(3)+'</td>'
			               +'<td><a href='+base_url+'index.php/admin_ret_reports/monthly_slaes_comparision/detailed_list/?id_branch='+id_branch+'&id_village='+id_village+'&month=09&year='+$('#cur_year').val()+'&id_metal=1&sales_mode=1 target="_blank">'+parseFloat(sep_fixed_amt).toFixed(3)+'</td>'
			               +'<td><a href='+base_url+'index.php/admin_ret_reports/monthly_slaes_comparision/detailed_list/?id_branch='+id_branch+'&id_village='+id_village+'&month=10&year='+$('#cur_year').val()+'&id_metal=1&sales_mode=2 target="_blank">'+parseFloat(oct_gold_wt).toFixed(3)+'</td>'
			               +'<td><a href='+base_url+'index.php/admin_ret_reports/monthly_slaes_comparision/detailed_list/?id_branch='+id_branch+'&id_village='+id_village+'&month=10&year='+$('#cur_year').val()+'&id_metal=2&sales_mode=2 target="_blank">'+parseFloat(oct_silver_wt).toFixed(3)+'</td>'
			               +'<td><a href='+base_url+'index.php/admin_ret_reports/monthly_slaes_comparision/detailed_list/?id_branch='+id_branch+'&id_village='+id_village+'&month=10&year='+$('#cur_year').val()+'&id_metal=1&sales_mode=1 target="_blank">'+parseFloat(oct_fixed_amt).toFixed(3)+'</td>'
			               +'<td><a href='+base_url+'index.php/admin_ret_reports/monthly_slaes_comparision/detailed_list/?id_branch='+id_branch+'&id_village='+id_village+'&month=11&year='+$('#cur_year').val()+'&id_metal=1&sales_mode=2 target="_blank">'+parseFloat(nov_gold_wt).toFixed(3)+'</td>'
			               +'<td><a href='+base_url+'index.php/admin_ret_reports/monthly_slaes_comparision/detailed_list/?id_branch='+id_branch+'&id_village='+id_village+'&month=11&year='+$('#cur_year').val()+'&id_metal=2&sales_mode=2 target="_blank">'+parseFloat(nov_silver_wt).toFixed(3)+'</td>'
			               +'<td><a href='+base_url+'index.php/admin_ret_reports/monthly_slaes_comparision/detailed_list/?id_branch='+id_branch+'&id_village='+id_village+'&month=11&year='+$('#cur_year').val()+'&id_metal=1&sales_mode=1 target="_blank">'+parseFloat(nov_fixed_amt).toFixed(3)+'</td>'
			               +'<td><a href='+base_url+'index.php/admin_ret_reports/monthly_slaes_comparision/detailed_list/?id_branch='+id_branch+'&id_village='+id_village+'&month=12&year='+$('#cur_year').val()+'&id_metal=1&sales_mode=2 target="_blank">'+parseFloat(dec_gold_wt).toFixed(3)+'</td>'
			               +'<td><a href='+base_url+'index.php/admin_ret_reports/monthly_slaes_comparision/detailed_list/?id_branch='+id_branch+'&id_village='+id_village+'&month=12&year='+$('#cur_year').val()+'&id_metal=&sales_mode=2 target="_blank">'+parseFloat(dec_silver_wt).toFixed(3)+'</td>'
			               +'<td><a href='+base_url+'index.php/admin_ret_reports/monthly_slaes_comparision/detailed_list/?id_branch='+id_branch+'&id_village='+id_village+'&month=12&year='+$('#cur_year').val()+'&id_metal=1&sales_mode=1 target="_blank">'+parseFloat(dec_fixed_amt).toFixed(3)+'</td>'
			               +'</tr>';
			             J_total_gold_wt+=parseFloat(J_gold_wt);
			             J_total_silver_wt+=parseFloat(J_silver_wt);
			             J_total_fixed_amt+=parseFloat(J_fixed_amt);
			             F_total_gold_wt+=parseFloat(F_gold_wt);
			             F_total_silver_wt+=parseFloat(F_silver_wt);
			             F_total_fixed_amt+=parseFloat(F_fixed_amt);
			             M_total_gold_wt+=parseFloat(M_gold_wt);
			             M_total_silver_wt+=parseFloat(M_silver_wt);
			             M_total_fixed_amt+=parseFloat(M_fixed_amt);
			             A_total_gold_wt+=parseFloat(A_gold_wt);
			             A_total_silver_wt+=parseFloat(A_silver_wt);
			             A_total_fixed_amt+=parseFloat(A_fixed_amt);
			             may_total_gold_wt+=parseFloat(may_gold_wt);
			             may_total_silver_wt+=parseFloat(may_silver_wt);
			             may_total_fixed_amt+=parseFloat(may_fixed_amt);
			             june_total_gold_wt+=parseFloat(june_gold_wt);
			             june_total_silver_wt+=parseFloat(june_silver_wt);
			             june_total_fixed_amt+=parseFloat(june_fixed_amt);
			             july_total_gold_wt+=parseFloat(july_gold_wt);
			             july_total_silver_wt+=parseFloat(july_silver_wt);
			             july_total_fixed_amt+=parseFloat(july_fixed_amt);
			             aug_total_gold_wt+=parseFloat(aug_gold_wt);
			             aug_total_silver_wt+=parseFloat(aug_silver_wt);
			             aug_total_fixed_amt+=parseFloat(aug_fixed_amt);
			             sep_total_gold_wt+=parseFloat(sep_gold_wt);
			             sep_total_silver_wt+=parseFloat(sep_silver_wt);
			             sep_total_fixed_amt+=parseFloat(sep_fixed_amt);
			             oct_total_gold_wt+=parseFloat(oct_gold_wt);
			             oct_total_silver_wt+=parseFloat(oct_silver_wt);
			             oct_total_fixed_amt+=parseFloat(oct_fixed_amt);
			             nov_total_gold_wt+=parseFloat(nov_gold_wt);
			             nov_total_silver_wt+=parseFloat(nov_silver_wt);
			             nov_total_fixed_amt+=parseFloat(nov_fixed_amt);
			             dec_total_gold_wt+=parseFloat(dec_gold_wt);
			             dec_total_silver_wt+=parseFloat(dec_silver_wt);
			             dec_total_fixed_amt+=parseFloat(dec_fixed_amt);
			        });
			        
			         trHTML+='<tr>'
			               +'<td><strong>SUB TOTAL</strong></td>'
			               +'<td></td>'
			               
			               +'<td><strong>'+parseFloat(J_total_gold_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(J_total_silver_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(J_total_fixed_amt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(F_total_gold_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(F_total_silver_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(F_total_fixed_amt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(M_total_gold_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(M_total_silver_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(M_total_fixed_amt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(A_total_gold_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(A_total_silver_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(A_total_fixed_amt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(may_total_gold_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(may_total_silver_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(may_total_fixed_amt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(june_total_gold_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(june_total_silver_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(june_total_fixed_amt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(july_total_gold_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(july_total_silver_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(july_total_fixed_amt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(aug_total_gold_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(aug_total_silver_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(aug_total_fixed_amt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(sep_total_gold_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(sep_total_silver_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(sep_total_fixed_amt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(oct_total_gold_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(oct_total_silver_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(oct_total_fixed_amt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(nov_total_gold_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(nov_total_silver_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(nov_total_fixed_amt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(dec_total_gold_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(dec_total_silver_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(dec_total_fixed_amt).toFixed(3)+'</strong></td>'
			             
			               
			             J_grand_total_gold_wt+=parseFloat(J_total_gold_wt);
			             J_grand_total_silver_wt+=parseFloat(J_total_silver_wt);
			             J_grand_total_fixed_amt+=parseFloat(J_total_fixed_amt);
			             F_grand_total_gold_wt+=parseFloat(F_total_gold_wt);
			             F_grand_total_silver_wt+=parseFloat(F_total_silver_wt);
			             F_grand_total_fixed_amt+=parseFloat(F_total_fixed_amt);
			             M_grand_total_gold_wt+=parseFloat(M_total_gold_wt);
			             M_grand_total_silver_wt+=parseFloat(M_total_silver_wt);
			             M_grand_total_fixed_amt+=parseFloat(M_total_fixed_amt);
			             A_grand_total_gold_wt+=parseFloat(A_total_gold_wt);
			             A_grand_total_silver_wt+=parseFloat(A_total_silver_wt);
			             A_grand_total_fixed_amt+=parseFloat(A_total_fixed_amt);
			             may_grand_total_gold_wt+=parseFloat(may_total_gold_wt);
			             may_grand_total_silver_wt+=parseFloat(may_total_silver_wt);
			             may_grand_total_fixed_amt+=parseFloat(may_total_fixed_amt);
			             june_grand_total_gold_wt+=parseFloat(june_total_gold_wt);
			             june_grand_total_silver_wt+=parseFloat(june_total_silver_wt);
			             june_grand_total_fixed_amt+=parseFloat(june_total_fixed_amt);
			             july_grand_total_gold_wt+=parseFloat(july_total_gold_wt);
			             july_grand_total_silver_wt+=parseFloat(july_total_silver_wt);
			             july_grand_total_fixed_amt+=parseFloat(july_total_fixed_amt);
			             aug_grand_total_gold_wt+=parseFloat(aug_total_gold_wt);
			             aug_grand_total_silver_wt+=parseFloat(aug_total_silver_wt);
			             aug_grand_total_fixed_amt+=parseFloat(aug_total_fixed_amt);
			             sep_grand_total_gold_wt+=parseFloat(sep_total_gold_wt);
			             sep_grand_total_silver_wt+=parseFloat(sep_total_silver_wt);
			             sep_grand_total_fixed_amt+=parseFloat(sep_total_fixed_amt);
			             oct_grand_total_gold_wt+=parseFloat(oct_total_gold_wt);
			             oct_grand_total_silver_wt+=parseFloat(oct_total_silver_wt);
			             oct_grand_total_fixed_amt+=parseFloat(oct_total_fixed_amt);
			             nov_grand_total_gold_wt+=parseFloat(nov_total_gold_wt);
			             nov_grand_total_silver_wt+=parseFloat(nov_total_silver_wt);
			             nov_grand_total_fixed_amt+=parseFloat(nov_total_fixed_amt);
			             dec_grand_total_gold_wt+=parseFloat(dec_total_gold_wt);
			             dec_grand_total_silver_wt+=parseFloat(dec_total_silver_wt);
			             dec_grand_total_fixed_amt+=parseFloat(dec_total_fixed_amt);
			        
			    });
			        
			        trHTML+='<tr>'
			               +'<td><strong>GRAND TOTAL</strong></td>'
			               +'<td></td>'
			               +'<td><strong>'+parseFloat(J_grand_total_gold_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(J_grand_total_silver_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(J_grand_total_fixed_amt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(F_grand_total_gold_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(F_grand_total_silver_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(F_grand_total_fixed_amt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(M_grand_total_gold_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(M_grand_total_silver_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(M_grand_total_fixed_amt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(A_grand_total_gold_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(A_grand_total_silver_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(A_grand_total_fixed_amt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(may_grand_total_gold_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(may_grand_total_silver_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(may_grand_total_fixed_amt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(june_grand_total_gold_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(june_grand_total_silver_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(june_grand_total_fixed_amt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(july_grand_total_gold_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(july_grand_total_silver_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(july_grand_total_fixed_amt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(aug_grand_total_gold_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(aug_grand_total_silver_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(aug_grand_total_fixed_amt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(sep_grand_total_gold_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(sep_grand_total_silver_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(sep_grand_total_fixed_amt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(oct_grand_total_gold_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(oct_grand_total_silver_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(oct_grand_total_fixed_amt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(nov_grand_total_gold_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(nov_grand_total_silver_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(nov_grand_total_fixed_amt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(dec_grand_total_gold_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(dec_grand_total_silver_wt).toFixed(3)+'</strong></td>'
			               +'<td><strong>'+parseFloat(dec_grand_total_fixed_amt).toFixed(3)+'</strong></td>'
			               +'</tr>';
			    
			      $('#sales_comparision > tbody').html(trHTML);
			     // Check and initialise datatable
				if ( ! $.fn.DataTable.isDataTable( '#sales_comparision' ) ) { 
					oTable = $('#sales_comparision').dataTable({ 
					"bSort": false, 
					"bInfo": true, 
					"scrollX":'100%',  
					"dom": 'lBfrtip',
					"paging":false,
					"buttons": [
					{
						extend: 'print',
						footer: true,
						title: "Monthly Comparision Report",
						customize: function ( win ) {
						$(win.document.body).find( 'table' )
						.addClass( 'compact' )
						.css( 'font-size', 'inherit' );
						},
					},
					{
						extend:'excel',
						footer: true,
						title: "Monthly Comparision Report",
					}
					], 
					});
				}
				$("div.overlay").css("display", "none"); 
		}
	});
}
function get_monthly_sales_details()
{
    var urlParams = new URLSearchParams(window.location.search);
    var id_branch=urlParams.get('id_branch');
    var id_village=urlParams.get('id_village');
    var month=urlParams.get('month');
    var year=urlParams.get('year');
    var sales_mode=urlParams.get('sales_mode');
    var id_metal=urlParams.get('id_metal');
    var company_name=$('#company_name').val();
    var title="<b><span style='font-size:15pt;margin-left:30%;'>"+company_name+"</span>-</b><b><span style='font-size:12pt;'></span></b>"+"<span style=font-size:13pt;>&nbsp;&nbsp;Monthly Sales Report &nbsp;&nbsp;</span>";
    $("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/monthly_slaes_comparision/sales_details/?nocache=" + my_Date.getUTCSeconds(),
			 dataType:"JSON",
			 type:"POST",
			 data:{'id_branch':id_branch,'id_village':id_village,'month':month,'year':year,'id_metal':id_metal,'sales_mode':sales_mode},
			 success:function(data)
			 {
			        var list=data;
    				var oTable = $('#sales_details').DataTable();
    				oTable.clear().draw();				  
    				if (list!= null && list.length > 0)
    				{  	
    					oTable = $('#sales_details').dataTable({
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
    								    title: "Sales Details",
    								  }
    								 ],
    						"aaData": list,
    						"aoColumns": [	
    										{ "mDataProp": "bill_date" },
    										{ "mDataProp": "cus_name" },
    										{ "mDataProp": function ( row, type, val, meta ){
                                            var url=base_url+'index.php/admin_ret_reports/customer_history/list/'+row.mobile;
                                            return '<a href='+url+' target="_blank">'+row.mobile+'</a>';
                                            }},
                                            { "mDataProp": function ( row, type, val, meta ){
                                            var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+row.bill_id;
                                            return '<a href='+url+' target="_blank">'+row.bill_no+'</a>';
                                            }},
                                            { "mDataProp": "metal" },
                                            { "mDataProp": "product_name" },
                                            { "mDataProp": "total_net_wt" },
                                            { "mDataProp": "tot_fixed_amt" },
    									  ],
                							"footerCallback": function( row, data, start, end, display ){
                        						var cshtotal = 0; 
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
                        
                        							
                        								total_value = api
                        								.column(6)
                        								.data()
                        								.reduce( function (a, b) {
                        									return intVal(a) + intVal(b);
                        								}, 0 );
                        								$(api.column(6).footer()).html(parseFloat(total_value).toFixed(3));
                        								
                        								total_value = api
                        								.column(7)
                        								.data()
                        								.reduce( function (a, b) {
                        									return intVal(a) + intVal(b);
                        								}, 0 );
                        								$(api.column(7).footer()).html(parseFloat(total_value).toFixed(2));
                        								
                        							
                        						} 
                        						}else{
                        							 var api = this.api(), data; 
                        							 $(api.column(2).footer()).html('');  
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
//Month on month sales report
//Telecalling
$("#set_vip_cus").on('click',function(){
        if($("input[name='id_customer[]']:checked").val())
        {
            $('#set_vip_cus').prop('disabled',true);
            var selected = [];
            $("#telecalling_list tbody tr").each(function(index, value)
            {
                if($(value).find("input[name='id_customer[]']:checked").is(":checked"))
                {
                    transData = { 
                    'id_customer'  : $(value).find(".id_customer").val(),
                    }
                    selected.push(transData);	
                }
            })
            req_data = selected;
            update_vip_customer(req_data);
        }
        else
        {
            $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Please Select Customer..'});
            $('#set_vip_cus').prop('disabled',false);
        }
});
function update_vip_customer(req_data)
{
    my_Date = new Date();
    $("div.overlay").css("display", "block"); 
    $.ajax({
    url:base_url+ "index.php/admin_ret_reports/update_vip_customer?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),
    data:  {'req_data':req_data},
    type:"POST",
    dataType:"JSON",
    async:false,
    success:function(data){
        if(data.status)
	    {
	        $.toaster({ priority : 'success', title : 'Warning!', message : ''+"</br>"+data.message});
	    }else{
	        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+data.message});
	    }
        location.reload();
        $("div.overlay").css("display", "none"); 
    },
    error:function(error)  
    {
        console.log(error);
        $("div.overlay").css("display", "none"); 
    }	 
    });
}
function get_telecalling_report()
{
    my_Date = new Date();
    $('.overlay').css('display','block');
    $.ajax({
            url:base_url+"index.php/admin_ret_reports/get_telecalling_report?nocache=" + my_Date.getUTCSeconds(),
            data: ( {'from_date' :$("#rpt_cus1").html(),'to_date' :$("#rpt_cus2").html(),'id_zone':$('#select_zone').val(),'id_branch':($('#branch_filter').val()!='' && $('#branch_filter').val()!=undefined ? $('#branch_filter').val(): $("#branch_select").val()),'id_village':$('#select_village').val()}),
            dataType:"JSON",
            type:"POST",
            success:function(data){
            
            set_telecalling_report(data);
            $('.overlay').css('display','none');
            },
            error:function(error)  
            {
            $('.overlay').css('display','none');
            }    
            });
}
function set_telecalling_report(data)
{
    var list = data;
    var oTable = $('#telecalling_list').DataTable();
    oTable.clear().draw();
    if(list!= null && list.length > 0)
    {
        oTable = $('#telecalling_list').dataTable({
        "bDestroy": true,
        "bInfo": true,
        "bFilter": true,
        "bSort": true,
        "dom": 'lBfrtip',
        "buttons": [
    		 {
    		   extend: 'print',
    		   footer: true,
    		   title: "",
    		   messageTop: "Tele Calling Details",
    		   customize: function ( win ) {
    				$(win.document.body).find( 'table' )
    					.addClass( 'compact' )
    					.css( 'font-size', 'inherit' );
    			},
    		 },
    		 {
    			extend:'excel',
    			footer: true,
    		    title: "Tele Calling Details",
    		  }
		 ],
        "aaData": list,
        "order": [[ 1, "desc" ]],
        "aoColumns": [
        { "mDataProp": function ( row, type, val, meta ){ 
            chekbox='<input type="checkbox" class="id_customer" name="id_customer[]" value="'+row.id_customer+'"/>'+row.id_customer; 
            return chekbox;
        }},
         { "mDataProp":"firstname" },    
        { "mDataProp": function(row,type,val,meta){
        mobile_report = base_url + "index.php/admin_ret_reports/customer_history/list/" + row.mobile;
        action = '<a href="'+ mobile_report +'" target="_blank">'+row.mobile;
        return action;
        }},
        { "mDataProp":"vip_customer" },
       
        { "mDataProp":"village_name" },
        { "mDataProp":"zone_name" },
        { "mDataProp":"branch_name" },
        { "mDataProp":"estimation_no" },
        { "mDataProp":"bill_count" },
        { "mDataProp":"last_billdate" },
        { "mDataProp":"gold_wt" },
        { "mDataProp":"silver_wt" },
        { "mDataProp":"tot_fixed_rate" },
        { "mDataProp":"tot_account" },
        { "mDataProp":"active_acc" },
        { "mDataProp":"inactive_acount" },
        { "mDataProp":"closed_count" },
        { "mDataProp": function ( row, type, val, meta )
        {
        return '<a href="https://api.whatsapp.com/send?phone=+91'+row.mobile+'" target="_blank"><i class="fa fa-whatsapp" aria-hidden="true" style="font-size: 30px;color: #fff;background: linear-gradient(#25d366,#25d366) 14% 84%/16% 16% no-repeat, radial-gradient(#25d366 58%,transparent 0);"></i></a>';
        }},
        { "mDataProp": function ( row, type, val, meta )
        {
        var id = row.id_customer;
        feedback_target="#modal-feedback";
        action_content= '<a href=# class="btn btn-info fb_add" id="fb" data-toggle="modal"   role="button" data-id='+id+' data-target='+feedback_target+'><i class="fa fa-comments-o" aria-hidden="true"></i></a>';
        return action_content;
        }},
        ]
        });
    }  
}
$(document).on('click', "#telecalling_list a.fb_add", function(e) {
	var customer_id=$(this).data('id');
	get_feedback_modal(customer_id);
});	
$("#add_feedback").click(function(){
	add_feedback();
});
function add_feedback()
{
	var form_data=$('#feedback_form').serialize();
	console.log(form_data);
	my_Date = new Date();
	$.ajax({
		type:"POST",
		url: base_url+"index.php/admin_ret_catalog/feedback/save?nocache=" +my_Date.getUTCSeconds(),
		cache: false,
		dataType:"JSON",
		data:form_data,
		success:function(data){
		    if(data.status)
		    {
		        $.toaster({ priority : 'success', title : 'Warning!', message : ''+"</br>"+data.message});
		    }else{
		        $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+data.message});
		    }
			$("div.overlay").css("display", "none"); 
			//location.reload(true);
		}
	});
}
function get_feedback_modal(customer_id)
{
my_Date = new Date();
$.ajax({
	type:"GET",
	url: base_url+"index.php/admin_ret_catalog/feedback/ajax?nocache=" + my_Date.getUTCSeconds(),
	cache:false,		
	dataType:"JSON",
	success:function(data){
		var feedback=data.list;
		$('#feedback_content').html(""); 
		
		var i=1;
		$.each(feedback,function (key, item) {
				
				var feedbackRow="<div class='col-md-8'><lable>"+ item.name +"</label><br>"
				+"<input type='hidden'  name='id_customer' value=" +customer_id+ ">"
				+"<input type='hidden' name='id_feedback[]' value="+item.id_feedback+"><input type='radio' name='feedback_option_"+i+"' checked value='1' /><label for='1'> &nbsp;&nbsp; Excellent &nbsp;&nbsp;</label>"
				+"<input type='radio' name='feedback_option_"+i+"' value='2' /><label for='2'> &nbsp;&nbsp; Good &nbsp;&nbsp;</label>"
				+"<input type='radio' name='feedback_option_"+i+"' value='3' /><label for='3'> &nbsp;&nbsp; Fair &nbsp;&nbsp;</label>"
				+"<input type='radio' name='feedback_option_"+i+"' value='4' /><label for='4'> &nbsp;&nbsp; Poor &nbsp;&nbsp;</label><br /><br>"
			$('#feedback_content').append(feedbackRow);
			i++;
 		});
		 $('#feedback_content').append("<div class='col-md-8'><label>Comments :</label><textarea name='comments' rows='3' cols='60' value='' /></div>");
	}
});
}
function get_feedback_report()
{
    my_Date = new Date();
    $('.overlay').css('display','block');
    $.ajax({
            url:base_url+"index.php/admin_ret_reports/get_feedbackReport?nocache=" + my_Date.getUTCSeconds(),
            dataType:"JSON",
            type:"GET",
            success:function(data){
            set_feedback_report(data);
            $('.overlay').css('display','none');
            },
            error:function(error)  
            {
            $('.overlay').css('display','none');
            }    
            });
}
function set_feedback_report(data)  
{
    var feedback = data;
    var oTable = $('#feedback_list').DataTable();
    oTable.clear().draw();
    if (feedback!= null && feedback.length > 0)
    {
        oTable = $('#feedback_list').dataTable({
        "bDestroy": true,
        "bInfo": true,
        "bFilter": true,
        "bSort": false,
        "dom": 'T<"clear">lfrtip',
        "tableTools": { "aButtons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } }] },
        "aaData": feedback,
        "order": [[ 1, "asc" ]],
        "aoColumns": [
            { 
                render: function (data, type, row, meta) 
                {
                return meta.row + 1;
                } 
            },
            { "mDataProp" : "firstname"},
            {"mDataProp" :  function(row,type,val,meta){
            var customer_mobile=row.mobile;
            var customer_id=row.id_customer;
            feedback_target="#modal_feedback";
            action_content= '<a href=# data-toggle="modal" class="customer_fb" role="button" data-id='+customer_id+' data-target='+feedback_target+'>'+customer_mobile+'</a>';
            return action_content;
            } },
            {"mDataProp" : "feedback_date"},
            {"mDataProp" : "feedback_takenby"},
        ] 
        }); 
    }  
}
$(document).on('click', "#feedback_list a.customer_fb", function(e) {
    var customer_id=$(this).data('id');
    $.ajax({
        type:"GET",
        url: base_url+"index.php/admin_ret_reports/feedback_report/ajax/"+customer_id+"?nocache=" + my_Date.getUTCSeconds(),
        cache:false,        
        dataType:"JSON",
        success(data){
            $('#fb_report_content').html(""); 
            var i=1;
            
        $.each(data,function (key, item) {
                // alert(item.feedback_response);
                var x = item.feedback_response;
                if(x==1)
                {
                    var feedbackRow="<div class='col-md-8'><lable>"+ item.name + "<br>&nbsp;&nbsp;<b>Excellent</b></label><br>"
                +"<input type='hidden' id='cus_comm' name='comments'  value=" +item.comments+ "><br>"
                }
                if(x==2){
                    var feedbackRow="<div class='col-md-8'><lable>"+ item.name + "<br>&nbsp;&nbsp;<b>Good</b></label><br>"
                +"<input type='hidden' id='cus_comm' name='comments'  value=" +item.comments+ "><br>"
                }
                if(x==3)
                {
                    var feedbackRow="<div class='col-md-8'><lable>"+ item.name + "<br>&nbsp;&nbsp;<b>Fair</b></label><br>"
                +"<input type='hidden' id='cus_comm' name='comments'  value=" +item.comments+ "><br>"
                }
                if(x==4)
                {
                    var feedbackRow="<div class='col-md-8'><lable>"+ item.name + "<br>&nbsp;&nbsp;<b>Poor</b></label><br>"
                +"<input type='hidden' id='cus_comm' name='comments'  value=" +item.comments+ "><br>"
                }
                
                
                $('#fb_report_content').append(feedbackRow);
            
            i++;
        });
         var cus=$('#cus_comm').val();
         $('#fb_report_content').append("<div class='col-md-8'><label>Comments :</label><textarea disabled id='cus_comment' name='comments' rows='3' cols='60'>"+cus+"</textarea></div>");
         
         
        }
    });
});
//Telecalling
function get_Activesize(id_product)
{
	$('#select_size option').remove();
    $.ajax({		
        type: 'POST',		
        url: base_url+'index.php/admin_ret_catalog/get_Activesize',		
        dataType:'json',
        data:{'id_product':id_product},
        success:function(data){		
        var size = $('#select_size').val();
        $.each(data, function (key, item) {
        $("#select_size").append(						
        $("<option></option>")						
        .attr("value", item.id_size)						  						  
        .text(item.name )						  					
        );			   											
        });		
        $("#select_size").select2({			    
        placeholder: "Select Size",			    
        allowClear: true		    
        });	
        $("#select_size").select2("val",'');	 
        				
        }	
    }); 
}
//Customer Edit Log
function set_customer_edit_log_table()
{
	
	$("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/customer_edit_log/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'from_date':$('#customer_edit_log_date1').text(),'to_date':$('#customer_edit_log_date2').text()}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
				 console.log(data);
			 	$("div.overlay").css("display", "none"); 
			 	var list = data.list;
				$('#total_count').text(list.length);
				var oTable = $('#customer_edit_log_list').DataTable();
				oTable.clear().draw();				  
				if (list!= null && list.length > 0)
				{
					  	oTable = $('#customer_edit_log_list').DataTable({
				                "bDestroy": true,
				                "bInfo": true,
				                "bFilter": true,
				                "bSort": true,
				                "order": [[ 0, "desc" ]],
				                "order": [[ 0, "desc" ]],
				                "aaData": list,
				                "aoColumns": [
                          { "mDataProp": "id_customer" },
						 { "mDataProp": function ( row, type, val, meta ) {
							 
							 if(row.previous_firstname != null)
							 {
								 var name = "Name&nbsp;:&nbsp;"+"<span>"+row.previous_firstname+"<br></span>"
							 }
							 if(row.previous_lastname != null)
							 {
								 var last_name = "Last Name&nbsp;:&nbsp;"+"<span>"+row.previous_lastname+"<br></span>"
							 }
							 if(row.previous_mobile != null)
							 {
								 var mobile = "Mobile&nbsp;:&nbsp;"+"<span>"+row.previous_mobile+"<br></span>"
							 }if(row.previous_email != null)
							 {
								 var email = "Email&nbsp;:&nbsp;"+"<span>"+row.previous_email+"<br></span>"
							 }if(row.previous_address1 != null)
							 {
								 var address1 = "Address&nbsp;:&nbsp;"+"<span>"+row.previous_address1+"<br></span>"
							 }
							 content = ''.concat(name !=null || name != undefined ? name:'',(last_name!=null ? last_name :''),mobile !=null || mobile != undefined ? mobile:'',email !=null || email != undefined ? email:'',address1 !=null || address1 != undefined ? address1:'');
							 return content;
                          }},
						  { "mDataProp": function ( row, type, val, meta ) {
							  spanclass = 'label-success';
							 if(row.updated_firstname != null)
							 {
								 var up_name = "Name&nbsp;:&nbsp;"+"<span class='label "+spanclass+"'>"+row.updated_firstname+"<br></span>"
							 }
							 if(row.updated_lastname != null)
							 {
								 var up_lastname = "Last Name&nbsp;:&nbsp;"+"<span class='label "+spanclass+"'>"+row.updated_lastname+"<br></span>"
							 }
							 if(row.updated_mobile != null)
							 {
								 var up_mobile = "Mobile&nbsp;:&nbsp;"+"<span class='label "+spanclass+"'>"+row.updated_mobile+"<br></span>"
							 }if(row.updated_email != null)
							 {
								 var up_email = "Email&nbsp;:&nbsp;"+"<span class='label "+spanclass+"'>"+row.updated_email+"<br></span>"
							 }if(row.updated_address1 != null)
							 {
								 var up_address1 = "Address&nbsp;:&nbsp;"+"<span class='label "+spanclass+"'>"+row.updated_address1+"<br></span>"
							 }
							 up_content = ''.concat(up_name !=null || up_name != undefined ? up_name:'',(up_lastname!=null ? up_lastname :''),up_mobile !=null || up_mobile != undefined ? up_mobile:'',up_email !=null || up_email != undefined ? up_email:'',up_address1 !=null || up_address1 != undefined ? up_address1:'');
							 return up_content;
                          }},
						  { "mDataProp": function ( row, type, val, meta ) {
                      		  return row.firstname !=null?row.firstname:'-';
                          }},
						  { "mDataProp": function ( row, type, val, meta ) {
                      		  return row.updated_on !=null?row.updated_on:'-';
                          }}
						 ]
				            });
					  	 }
			  },
			  error:function(error)  
			  {
				 $("div.overlay").css("display", "none"); 
			  }	 
	      });
}
//Customer Edit Log
//Green tag return report
function set_gt_return_report_table()
{
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/gt_return_report/ajax_data?nocache=" + my_Date.getUTCSeconds(),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data)
			 {
				 
    				var oTable = $('#gt_return_report_list').DataTable();
    				oTable.clear().draw();				  
    				if (data!= null && data.length > 0)
    				{  	
    					oTable = $('#gt_return_report_list').dataTable({
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
    								   messageTop: "Green Tag Sales Return Report",
    								   customize: function ( win ) {
    										$(win.document.body).find( 'table' )
    											.addClass( 'compact' )
    											.css( 'font-size', 'inherit' );
    									},
    								 },
    								 {
    									extend:'excel',
    									footer: true,
    								    title: "Green Tag Sales Return Report",
    								  }
    								 ],
    						"aaData": data,
    						"aoColumns": [	
    									    { "mDataProp": function ( row, type, val, meta )
    										{ 
    										  var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+row.bill_id;
    										  return '<a href='+url+' target="_blank">'+row.bill_no+'</a>';
                    		                }},
                    		                { "mDataProp": function ( row, type, val, meta )
    										{ 
    										  var url = base_url+'index.php/admin_ret_estimation/generate_invoice/'+row.estimation_id;
    										  return '<a href='+url+' target="_blank">'+row.esti_no+'</a>';
                    		                }},
    										{ "mDataProp": "bill_date" },
    										{ "mDataProp": "tag_date" },
    										{ "mDataProp": "tag_code" },
											{ "mDataProp": "bill_date" },
    										{ "mDataProp": "gross_wt" },
    										{ "mDataProp": "net_wt" },
    										{ "mDataProp": function ( row, type, val, meta )
    										{ 
    										  if(row.id_metal==1)
    										  {
    										      return parseFloat(row.net_wt*$('#emp_sales_incentive_gold_perg').val()).toFixed(2);
    										  }else{
    										      return parseFloat(row.net_wt*$('#emp_sales_incentive_silver_perg').val()).toFixed(2);
    										  }
                    		                }},
    										{ "mDataProp": "product_name" },
    										{ "mDataProp": "item_cost" },
    										{ "mDataProp": "emp_name" },
    										{ "mDataProp": "emp_code" },
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
//Green tag return report
function set_dashboard_estimationList()
{
	$("div.overlay").css("display", "block"); 
    my_Date = new Date();
	$.ajax({
		url: base_url+"index.php/admin_ret_reports/dashboard_estimationList?nocache=" + my_Date.getUTCSeconds(),
		type:"POST",
		data:{'from_date':ctrl_page[2],'to_date':ctrl_page[3],'type':ctrl_page[4],  'id_branch':ctrl_page[5] },
		dataType:"JSON",
		cache:false,
		success:function(data){
			
			$("div.overlay").css("display", "none"); 
			 	var list = data.estimate_list;
				var oTable = $('#dash_est_list').DataTable();
				oTable.clear().draw();				  
				if (list!= null && list.length > 0)
				{  	
					oTable = $('#dash_est_list').dataTable({
						"bDestroy": true,
		                "bInfo": true,
		                "bFilter": true,
		                "scrollX":'100%',
		                "bSort": true,
		                "dom": 'lBfrtip',
		                "buttons": [
								 {
								   extend: 'print',
								   footer: true,
								   title: '',
								   customize: function ( win ) {
										$(win.document.body).find( 'table' )
											.addClass( 'compact' )
											.css( 'font-size', 'inherit' );
									},
								 },
								 {
									extend:'excel',
									footer: true,
									title: 'Estimation',
								    
								  }
								 ],
						"aaData": list,
						"aoColumns": [	{render: function (data, type, row, meta) 
											{
											return meta.row + 1;
											} 
										},	
										{ "mDataProp": function ( row, type, val, meta )
    										{ 
    										  var url = base_url+'index.php/admin_ret_estimation/generate_invoice/'+row.estimation_id;
    										  return '<a href='+url+' target="_blank">'+row.esti_no+'</a>';
                    		                }},
										{ "mDataProp": "firstname" },
										{ "mDataProp": "date" },
										{ "mDataProp": "total_cost" },
										
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
								total_val = api
								.column(4)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(4).footer()).html(parseFloat(total_val).toFixed(2));
						} 
						}else{
							 var api = this.api(), data; 
							 $(api.column(4).footer()).html('');  
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
function set_dashboard_salesList()
{
	$("div.overlay").css("display", "block"); 
    my_Date = new Date();
	$.ajax({
		url: base_url+"index.php/admin_ret_reports/dashboard_salesList?nocache=" + my_Date.getUTCSeconds(),
		type:"POST",
		data:{'from_date':ctrl_page[2],'to_date':ctrl_page[3],'type':ctrl_page[4],  'id_branch':ctrl_page[5] },
		dataType:"JSON",
		cache:false,
		success:function(data){
			
			$("div.overlay").css("display", "none"); 
			 	var list = data.sales_list;
				var oTable = $('#dash_sales_list').DataTable();
				oTable.clear().draw();				  
				if (list!= null && list.length > 0)
				{  	
					oTable = $('#dash_sales_list').dataTable({
						"bDestroy": true,
		                "bInfo": true,
		                "bFilter": true,
		                "scrollX":'100%',
		                "bSort": true,
		                "dom": 'lBfrtip',
		                "buttons": [
								 {
								   extend: 'print',
								   footer: true,
								   title: '',
								   customize: function ( win ) {
										$(win.document.body).find( 'table' )
											.addClass( 'compact' )
											.css( 'font-size', 'inherit' );
									},
								 },
								 {
									extend:'excel',
									footer: true,
									title: 'Sales',
								  }
								 ],
						"aaData": list,
						"aoColumns": [	{render: function (data, type, row, meta) 
											{
											return meta.row + 1;
											} 
										},	
										{ "mDataProp": function ( row, type, val, meta )
    										{ 
    										  var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+row.bill_id;
    										  return '<a href='+url+' target="_blank">'+row.bill_no+'</a>';
                    		                }},
										{ "mDataProp": "firstname" },
										{ "mDataProp": "bill_date" },
										{ "mDataProp": "product_name" },
										{ "mDataProp": "piece" },
										{ "mDataProp": "tot_bill_amount" },
										{ "mDataProp": "net_wt" },
										
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
								total_val = api
								.column(6)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(6).footer()).html(parseFloat(total_val).toFixed(2));
								total_wt = api
								.column(7)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(7).footer()).html(parseFloat(total_wt).toFixed(2));
						} 
						}else{
							 var api = this.api(), data; 
							 $(api.column(6).footer()).html('');  
							 $(api.column(7).footer()).html('');  
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
function set_dashboard_greentagList()
{
	$("div.overlay").css("display", "block"); 
    my_Date = new Date();
	$.ajax({
		url: base_url+"index.php/admin_ret_reports/dashboard_greentagList?nocache=" + my_Date.getUTCSeconds(),
		type:"POST",
		data:{'from_date':ctrl_page[2],'to_date':ctrl_page[3],'id_branch':ctrl_page[4] },
		dataType:"JSON",
		cache:false,
		success:function(data){
			
			$("div.overlay").css("display", "none"); 
			 	var list = data.greentag_list;
				var oTable = $('#dash_greentag_list').DataTable();
				oTable.clear().draw();				  
				if (list!= null && list.length > 0)
				{  	
					oTable = $('#dash_greentag_list').dataTable({
						"bDestroy": true,
		                "bInfo": true,
		                "bFilter": true,
		                "scrollX":'100%',
		                "bSort": true,
		                "dom": 'lBfrtip',
		                "buttons": [
								 {
								   extend: 'print',
								   footer: true,
								   title: '',
								   customize: function ( win ) {
										$(win.document.body).find( 'table' )
											.addClass( 'compact' )
											.css( 'font-size', 'inherit' );
									},
								 },
								 {
									extend:'excel',
									footer: true,
								    title: 'Green Tag Sales',
								  }
								 ],
						"aaData": list,
						"aoColumns": [	{render: function (data, type, row, meta) 
											{
											return meta.row + 1;
											} 
										},	
										{ "mDataProp": function ( row, type, val, meta )
    										{ 
    										  var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+row.bill_id;
    										  return '<a href='+url+' target="_blank">'+row.bill_no+'</a>';
                    		                }},
										{ "mDataProp": "firstname" },
										{ "mDataProp": "bill_date" },
										{ "mDataProp": "product_name" },
										{ "mDataProp": "piece" },
										{ "mDataProp": function ( row, type, val, meta )
										{ 
										    if(row.net_wt>0)
										    {
										        if(row.id_metal==1)
										        {
										            return parseFloat(row.net_wt*$('#emp_sales_incentive_gold_perg').val()).toFixed(2);
										        }else{
										             return parseFloat(row.net_wt*$('#emp_sales_incentive_silver_perg').val()).toFixed(2);
										        }
										    }else{
										        return 0;
										    }
                		                }},
										{ "mDataProp": "net_wt" },
										
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
								total_pcs = api
								.column(5)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(5).footer()).html(parseFloat(total_pcs));
								
								total_val = api
								.column(6)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(6).footer()).html(parseFloat(total_val).toFixed(2));
								total_wt = api
								.column(7)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(7).footer()).html(parseFloat(total_wt).toFixed(3));
						} 
						}else{
							 var api = this.api(), data; 
							 $(api.column(5).footer()).html('');  
							 $(api.column(6).footer()).html('');  
							 $(api.column(7).footer()).html('');  
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
function set_dashboard_oldmetalList(){
	$("div.overlay").css("display", "block"); 
    my_Date = new Date();
	$.ajax({
		url: base_url+"index.php/admin_ret_reports/dashboard_oldmetalList?nocache=" + my_Date.getUTCSeconds(),
		type:"POST",
		data:{'from_date':ctrl_page[2],'to_date':ctrl_page[3],'type':ctrl_page[4],'id_branch':ctrl_page[5] },
		dataType:"JSON",
		cache:false,
		success:function(data){
			
			$("div.overlay").css("display", "none"); 
			 	var list = data.oldmetal_list;
				var oTable = $('#dash_oldmetal_list').DataTable();
				oTable.clear().draw();				  
				if (list!= null && list.length > 0)
				{  	
					oTable = $('#dash_oldmetal_list').dataTable({
						"bDestroy": true,
		                "bInfo": true,
		                "bFilter": true,
		                "scrollX":'100%',
		                "bSort": true,
		                "dom": 'lBfrtip',
		                "buttons": [
								 {
								   extend: 'print',
								   footer: true,
								   title: '',
								   customize: function ( win ) {
										$(win.document.body).find( 'table' )
											.addClass( 'compact' )
											.css( 'font-size', 'inherit' );
									},
								 },
								 {
									extend:'excel',
									footer: true,
									title: 'Old Metal',
								  }
								 ],
						"aaData": list,
						"aoColumns": [	{render: function (data, type, row, meta) 
											{
											return meta.row + 1;
											} 
										},	
										{ "mDataProp": "firstname" },
										{ "mDataProp": function ( row, type, val, meta )
    										{ 
    										  var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+row.bill_id;
    										  return '<a href='+url+' target="_blank">'+row.bill_no+'</a>';
                    		                }},
										{ "mDataProp": "bill_date" },
										{ "mDataProp": "metal_type" },
										{ "mDataProp": "amount" },
										{ "mDataProp": "weight" },
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
								total_amt = api
								.column(5)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(5).footer()).html(parseFloat(total_amt).toFixed(2));
								
								total_val = api
								.column(6)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(6).footer()).html(parseFloat(total_val).toFixed(3));
								
						} 
						}else{
							 var api = this.api(), data; 
							 $(api.column(5).footer()).html('');  
							 $(api.column(6).footer()).html('');  
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
function set_dashboard_creditsalesList()
{
	$("div.overlay").css("display", "block"); 
    my_Date = new Date();
	$.ajax({
		url: base_url+"index.php/admin_ret_reports/dashboard_creditsalesList?nocache=" + my_Date.getUTCSeconds(),
		type:"POST",
		data:{'from_date':ctrl_page[2],'to_date':ctrl_page[3],'type':ctrl_page[4],'id_branch':ctrl_page[5] },
		dataType:"JSON",
		cache:false,
		success:function(data){
			
			$("div.overlay").css("display", "none"); 
			 	var list = data.issrec_list;
				var oTable = $('#dash_issrec_list').DataTable();
				oTable.clear().draw();				  
				if (list!= null && list.length > 0)
				{  	
					oTable = $('#dash_issrec_list').dataTable({
						"bDestroy": true,
		                "bInfo": true,
		                "bFilter": true,
		                "scrollX":'100%',
		                "bSort": true,
		                "dom": 'lBfrtip',
		                "buttons": [
								 {
								   extend: 'print',
								   footer: true,
								   title: '',
								   customize: function ( win ) {
										$(win.document.body).find( 'table' )
											.addClass( 'compact' )
											.css( 'font-size', 'inherit' );
									},
								 },
								 {
									extend:'excel',
									footer: true,
									title: 'Credit Sales',
								  }
								 ],
						"aaData": list,
						"aoColumns": [	{render: function (data, type, row, meta) 
											{
											return meta.row + 1;
											} 
										},	
										
										{ "mDataProp": function ( row, type, val, meta )
    										{ 
    										  var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+row.bill_id;
    										  return '<a href='+url+' target="_blank">'+row.bill_no+'</a>';
                    		                }},
										{ "mDataProp": "firstname" },
										{ "mDataProp": "bill_date" },
										{ "mDataProp": "amount" },
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
								total_amt = api
								.column(4)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(4).footer()).html(parseFloat(total_amt).toFixed(2));
						} 
						}else{
							 var api = this.api(), data; 
							 $(api.column(4).footer()).html('');  
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
function set_dashboard_giftcardList()
{
	$("div.overlay").css("display", "block"); 
    my_Date = new Date();
	$.ajax({
		url: base_url+"index.php/admin_ret_reports/dashboard_giftcardList?nocache=" + my_Date.getUTCSeconds(),
		type:"POST",
		data:{'from_date':ctrl_page[2],'to_date':ctrl_page[3],'type':ctrl_page[4],'id_branch':ctrl_page[5] },
		dataType:"JSON",
		cache:false,
		success:function(data){
			
			$("div.overlay").css("display", "none"); 
			 	var list = data.giftcard_list;
				var oTable = $('#dash_giftcard_list').DataTable();
				oTable.clear().draw();				  
				if (list!= null && list.length > 0)
				{  	
					oTable = $('#dash_giftcard_list').dataTable({
						"bDestroy": true,
		                "bInfo": true,
		                "bFilter": true,
		                "scrollX":'100%',
		                "bSort": true,
		                "dom": 'lBfrtip',
		                "buttons": [
								 {
								   extend: 'print',
								   footer: true,
								   title: '',
								   customize: function ( win ) {
										$(win.document.body).find( 'table' )
											.addClass( 'compact' )
											.css( 'font-size', 'inherit' );
									},
								 },
								 {
									extend:'excel',
									footer: true,
								    title: 'Gift Card',
								  }
								 ],
						"aaData": list,
						"aoColumns": [	{render: function (data, type, row, meta) 
											{
											return meta.row + 1;
											} 
										},	
										{ "mDataProp": "firstname" },
										{ "mDataProp": "date" },
										{ "mDataProp": "amount" },
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
								total_amt = api
								.column(3)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(3).footer()).html(parseFloat(total_amt).toFixed(2));
						} 
						}else{
							 var api = this.api(), data; 
							 $(api.column(4).footer()).html('');  
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
function set_dashboard_virtualsalesList()
{
	$("div.overlay").css("display", "block"); 
    my_Date = new Date();
	$.ajax({
		url: base_url+"index.php/admin_ret_reports/dashboard_virtualsalesList?nocache=" + my_Date.getUTCSeconds(),
		type:"POST",
		data:{'from_date':ctrl_page[2],'to_date':ctrl_page[3],'type':ctrl_page[4],'id_branch':ctrl_page[5] },
		dataType:"JSON",
		cache:false,
		success:function(data){
			
			$("div.overlay").css("display", "none"); 
			 	var list = data.virtual_list;
				var oTable = $('#dash_virtual_list').DataTable();
				oTable.clear().draw();				  
				if (list!= null && list.length > 0)
				{  	
					oTable = $('#dash_virtual_list').dataTable({
						"bDestroy": true,
		                "bInfo": true,
		                "bFilter": true,
		                "scrollX":'100%',
		                "bSort": true,
		                "dom": 'lBfrtip',
		                "buttons": [
								 {
								   extend: 'print',
								   footer: true,
								   title: '',
								   customize: function ( win ) {
										$(win.document.body).find( 'table' )
											.addClass( 'compact' )
											.css( 'font-size', 'inherit' );
									},
								 },
								 {
									extend:'excel',
									footer: true,
								    title: 'Virtual Tag Sales',
								  }
								 ],
						"aaData": list,
						"aoColumns": [	{render: function (data, type, row, meta) 
											{
											return meta.row + 1;
											} 
										},	
										{ "mDataProp": function ( row, type, val, meta )
    										{ 
    										  var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+row.bill_id;
    										  return '<a href='+url+' target="_blank">'+row.bill_no+'</a>';
                    		                }},
										{ "mDataProp": "firstname" },
										{ "mDataProp": "bill_date" },
										{ "mDataProp": "pcs" },
										{ "mDataProp": "gross_wt" },
										{ "mDataProp": "item_cost" },
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
								total_pcs = api
								.column(4)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(4).footer()).html(parseFloat(total_pcs));
								total_wt = api
								.column(5)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(5).footer()).html(parseFloat(total_wt).toFixed(3));
								total_amt = api
								.column(6)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(6).footer()).html(parseFloat(total_amt).toFixed(2));
						} 
						}else{
							 var api = this.api(), data; 
							 $(api.column(4).footer()).html('');  
							 $(api.column(5).footer()).html('');  
							 $(api.column(6).footer()).html('');  
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
function set_dashboard_lottagList()
{
	$("div.overlay").css("display", "block"); 
    my_Date = new Date();
	$.ajax({
		url: base_url+"index.php/admin_ret_reports/dashboard_lottagList?nocache=" + my_Date.getUTCSeconds(),
		type:"POST",
		data:{'from_date':ctrl_page[2],'to_date':ctrl_page[3],'type':ctrl_page[4],'id_branch':ctrl_page[5] },
		dataType:"JSON",
		cache:false,
		success:function(data){
			
			$("div.overlay").css("display", "none"); 
			 	var list = data.lottag_list;
				var oTable = $('#dash_lottag_list').DataTable();
				oTable.clear().draw();				  
				if (list!= null && list.length > 0)
				{  	
					oTable = $('#dash_lottag_list').dataTable({
						"bDestroy": true,
		                "bInfo": true,
		                "bFilter": true,
		                "scrollX":'100%',
		                "bSort": true,
		                "dom": 'lBfrtip',
		                "buttons": [
								 {
								   extend: 'print',
								   footer: true,
								   title: '',
								   customize: function ( win ) {
										$(win.document.body).find( 'table' )
											.addClass( 'compact' )
											.css( 'font-size', 'inherit' );
									},
								 },
								 {
									extend:'excel',
									footer: true,
								    title: 'Lot & Tag',
								  }
								 ],
						"aaData": list,
						"aoColumns": [	{render: function (data, type, row, meta) 
											{
											return meta.row + 1;
											} 
										},	
										
										{ "mDataProp": "date" },
										{ "mDataProp": "piece" },
										{ "mDataProp": "gross_wt" },
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
								total_pcs = api
								.column(2)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(2).footer()).html(parseFloat(total_pcs));
								total_wt = api
								.column(3)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(3).footer()).html(parseFloat(total_wt).toFixed(3));
								
						} 
						}else{
							 var api = this.api(), data; 
							 $(api.column(4).footer()).html('');  
							 $(api.column(5).footer()).html('');  
							 $(api.column(6).footer()).html('');  
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
function set_dashboard_customerorderList()
{
	$("div.overlay").css("display", "block"); 
    my_Date = new Date();
	$.ajax({
		url: base_url+"index.php/admin_ret_reports/dashboard_customerorderList?nocache=" + my_Date.getUTCSeconds(),
		type:"POST",
		data:{'from_date':ctrl_page[2],'to_date':ctrl_page[3],'type':ctrl_page[4],'id_branch':ctrl_page[5] },
		dataType:"JSON",
		cache:false,
		success:function(data){
			
			$("div.overlay").css("display", "none"); 
			 	var list = data.customerorder_list;
				var oTable = $('#dash_customerorder_list').DataTable();
				oTable.clear().draw();				  
				if (list!= null && list.length > 0)
				{  	
					oTable = $('#dash_customerorder_list').dataTable({
						"bDestroy": true,
		                "bInfo": true,
		                "bFilter": true,
		                "scrollX":'100%',
		                "bSort": true,
		                "dom": 'lBfrtip',
		                "buttons": [
								 {
								   extend: 'print',
								   footer: true,
								   title: '',
								   customize: function ( win ) {
										$(win.document.body).find( 'table' )
											.addClass( 'compact' )
											.css( 'font-size', 'inherit' );
									},
								 },
								 {
									extend:'excel',
									footer: true,
									title: 'Customer Order',
								    
								  }
								 ],
						"aaData": list,
						"aoColumns": [	{render: function (data, type, row, meta) 
											{
											return meta.row + 1;
											} 
										},	
										{ "mDataProp": "orderno" },
										{ "mDataProp": "branch" },
										{ "mDataProp": "order_date" },
										{ "mDataProp": "product_name" },
										{ "mDataProp": "weight" },
										{ "mDataProp": "rate" },
										
										
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
								total_wt = api
								.column(5)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(5).footer()).html(parseFloat(total_wt).toFixed(3));
								total_rate = api
								.column(6)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(6).footer()).html(parseFloat(total_rate).toFixed(2));
								
						} 
						}else{
							 var api = this.api(), data; 
							 $(api.column(5).footer()).html('');  
							 $(api.column(6).footer()).html('');  
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
function set_dashboard_greentagincentList()
{
	$("div.overlay").css("display", "block"); 
    my_Date = new Date();
	$.ajax({
		url: base_url+"index.php/admin_ret_reports/dashboard_greentagList_incent?nocache=" + my_Date.getUTCSeconds(),
		type:"POST",
		data:{'from_date':ctrl_page[2],'to_date':ctrl_page[3],'id_branch':ctrl_page[4] },
		dataType:"JSON",
		cache:false,
		success:function(data){
			
			$("div.overlay").css("display", "none"); 
			 	var list = data.greentagincent_list;
				var oTable = $('#dash_greentagincent_list').DataTable();
				oTable.clear().draw();				  
				if (list!= null && list.length > 0)
				{  	
					oTable = $('#dash_greentagincent_list').dataTable({
						"bDestroy": true,
		                "bInfo": true,
		                "bFilter": true,
		                "scrollX":'100%',
		                "bSort": true,
		                "dom": 'lBfrtip',
		                "buttons": [
								 {
								   extend: 'print',
								   footer: true,
								   title: '',
								   customize: function ( win ) {
										$(win.document.body).find( 'table' )
											.addClass( 'compact' )
											.css( 'font-size', 'inherit' );
									},
								 },
								 {
									extend:'excel',
									footer: true,
									title: 'Customer Order',
								    
								  }
								 ],
						"aaData": list,
						"aoColumns": [	{render: function (data, type, row, meta) 
											{
											return meta.row + 1;
											} 
										},	
										{ "mDataProp": function ( row, type, val, meta )
    										{ 
    										  var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+row.bill_id;
    										  return '<a href='+url+' target="_blank">'+row.bill_no+'</a>';
                    		                }},
    										{ "mDataProp": "bill_date" },
    										{ "mDataProp": "tag_id" },
    										{ "mDataProp": "tag_date" },
    										{ "mDataProp": "tag_code" },
    										{ "mDataProp": "emp_name" },
    										{ "mDataProp": "emp_code" },
										
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
function set_dashboard_salereturnList()
{
	$("div.overlay").css("display", "block"); 
    my_Date = new Date();
	$.ajax({
		url: base_url+"index.php/admin_ret_reports/dashboard_salereturnList?nocache=" + my_Date.getUTCSeconds(),
		type:"POST",
		data:{'from_date':ctrl_page[2],'to_date':ctrl_page[3],'id_branch':ctrl_page[4]},
		dataType:"JSON",
		cache:false,
		success:function(data){
			
			$("div.overlay").css("display", "none"); 
			 	var list = data.salereturn_list;
				var oTable = $('#dash_salereturn_list').DataTable();
				oTable.clear().draw();				  
				if (list!= null && list.length > 0)
				{  	
					oTable = $('#dash_salereturn_list').dataTable({
						"bDestroy": true,
		                "bInfo": true,
		                "bFilter": true,
		                "scrollX":'100%',
		                "bSort": true,
		                "dom": 'lBfrtip',
		                "buttons": [
								 {
								   extend: 'print',
								   footer: true,
								   title: '',
								   customize: function ( win ) {
										$(win.document.body).find( 'table' )
											.addClass( 'compact' )
											.css( 'font-size', 'inherit' );
									},
								 },
								 {
									extend:'excel',
									footer: true,
									title: 'Sale Return',
								    
								  }
								 ],
						"aaData": list,
						"aoColumns": [	{render: function (data, type, row, meta) 
											{
											return meta.row + 1;
											} 
										},	
										{ "mDataProp": function ( row, type, val, meta )
    										{ 
    										  var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+row.bill_id;
    										  return '<a href='+url+' target="_blank">'+row.bill_no+'</a>';
                    		                }},
										{ "mDataProp": "bill_date" },
										{ "mDataProp": "firstname" },
										{ "mDataProp": "pcs" },
										{ "mDataProp": "wt" },
										
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
								total_pcs = api
								.column(4)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(4).footer()).html(parseFloat(total_pcs));
								total_wt = api
								.column(5)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(5).footer()).html(parseFloat(total_wt).toFixed(3));
								
						} 
						}else{
							 var api = this.api(), data; 
							 $(api.column(5).footer()).html('');  
							 $(api.column(6).footer()).html('');  
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
function set_dashboard_lotList()
{
	$("div.overlay").css("display", "block"); 
    my_Date = new Date();
	$.ajax({
		url: base_url+"index.php/admin_ret_reports/dashboard_lotList?nocache=" + my_Date.getUTCSeconds(),
		type:"POST",
		data:{'from_date':ctrl_page[2],'to_date':ctrl_page[3],'type':ctrl_page[4],'id_branch':ctrl_page[5] },
		dataType:"JSON",
		cache:false,
		success:function(data){
			
			$("div.overlay").css("display", "none"); 
			 	var list = data.lot_list;
				var oTable = $('#dash_lot_list').DataTable();
				oTable.clear().draw();				  
				if (list!= null && list.length > 0)
				{  	
					oTable = $('#dash_lot_list').dataTable({
						"bDestroy": true,
		                "bInfo": true,
		                "bFilter": true,
		                "scrollX":'100%',
		                "bSort": true,
		                "dom": 'lBfrtip',
		                "buttons": [
								 {
								   extend: 'print',
								   footer: true,
								   title: '',
								   customize: function ( win ) {
										$(win.document.body).find( 'table' )
											.addClass( 'compact' )
											.css( 'font-size', 'inherit' );
									},
								 },
								 {
									extend:'excel',
									footer: true,
								    title: 'Lot',
								  }
								 ],
						"aaData": list,
						"aoColumns": [	{render: function (data, type, row, meta) 
											{
											return meta.row + 1;
											} 
										},	
										{ "mDataProp": function ( row, type, val, meta )
    										{ 
    										  var url = base_url+'index.php/admin_ret_tagging/get_tag_detail_list/'+row.lot_no;
    										  return '<a href='+url+' target="_blank">'+row.lot_no+'</a>';
                    		                }},
										{ "mDataProp": "date" },
										{ "mDataProp": "firstname" },
										{ "mDataProp": "piece" },
										{ "mDataProp": "gross_wt" },
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
								total_pcs = api
								.column(4)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(4).footer()).html(parseFloat(total_pcs));
								total_wt = api
								.column(5)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(5).footer()).html(parseFloat(total_wt).toFixed(3));
								
						} 
						}else{
							 var api = this.api(), data; 
							 $(api.column(4).footer()).html('');  
							 $(api.column(5).footer()).html('');  
							 $(api.column(6).footer()).html('');  
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
function set_dashboard_tagList()
{
	$("div.overlay").css("display", "block"); 
    my_Date = new Date();
	$.ajax({
		url: base_url+"index.php/admin_ret_reports/dashboard_tagList?nocache=" + my_Date.getUTCSeconds(),
		type:"POST",
		data:{'from_date':ctrl_page[2],'to_date':ctrl_page[3],'type':ctrl_page[4],'id_branch':ctrl_page[5] },
		dataType:"JSON",
		cache:false,
		success:function(data){
			
			$("div.overlay").css("display", "none"); 
			 	var list = data.tag_list;
				var oTable = $('#dash_tag_list').DataTable();
				oTable.clear().draw();				  
				if (list!= null && list.length > 0)
				{  	
					oTable = $('#dash_tag_list').dataTable({
						"bDestroy": true,
		                "bInfo": true,
		                "bFilter": true,
		                "scrollX":'100%',
		                "bSort": true,
		                "dom": 'lBfrtip',
		                "buttons": [
								 {
								   extend: 'print',
								   footer: true,
								   title: '',
								   customize: function ( win ) {
										$(win.document.body).find( 'table' )
											.addClass( 'compact' )
											.css( 'font-size', 'inherit' );
									},
								 },
								 {
									extend:'excel',
									footer: true,
								    title: 'Tag',
								  }
								 ],
						"aaData": list,
						"aoColumns": [	{render: function (data, type, row, meta) 
											{
											return meta.row + 1;
											} 
										},	
										{ "mDataProp": "tag_code" },
										{ "mDataProp": "design_name" },
										{ "mDataProp": "product_name" },
										{ "mDataProp": "date" },
										{ "mDataProp": "piece" },
										{ "mDataProp": "gross_wt" },
										{ "mDataProp": "net_wt" },
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
								total_pcs = api
								.column(5)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(5).footer()).html(parseFloat(total_pcs));
								total_gwt = api
								.column(6)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(6).footer()).html(parseFloat(total_gwt).toFixed(3));
								total_nwt = api
								.column(7)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(7).footer()).html(parseFloat(total_nwt).toFixed(3));
								
						} 
						}else{
							 var api = this.api(), data; 
							 $(api.column(5).footer()).html('');  
							 $(api.column(6).footer()).html('');  
							 $(api.column(7).footer()).html('');  
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
//Metal Stock Details
$('#report_type').on('change',function(){
    $('.purchase_item_details').css("display","none");
    $('.sales_details').css("display","none");
    if(this.value==1 || this.value==2 || this.value==5)
    {
        $('.sales_details').css("display","block");
    }
    else if(this.value==3 || this.value==4)
    {
        $('.purchase_item_details').css("display","block");
    }
});
function get_metal_stock_details()
{
     $("div.overlay").css("display", "block"); 
    my_Date = new Date();
	$.ajax({
		url: base_url+"index.php/admin_ret_reports/metal_stock_details/ajax?nocache=" + my_Date.getUTCSeconds(),
		data:{'from_date':$('#rpt_payments1').html(),'to_date' :$('#rpt_payments2').html(),'id_branch':$('#branch_select').val(),'report_type':$('#report_type').val()},
		type:"POST",
		dataType:"JSON",
		cache:false,
		success:function(data){
		    	$("div.overlay").css("display", "none"); 
			 	var list = data.list;
			 	$('#sales_details').dataTable().fnClearTable();
				var oTable = $('#sales_details').DataTable();
				oTable.clear().draw();				  
				if (list!= null && list.length > 0)
				{  	
					oTable = $('#sales_details').dataTable({
						"bDestroy": true,
		                "bInfo": true,
		                "bFilter": true,
		                "scrollX":'100%',
		                "bSort": true,
		                "dom": 'lBfrtip',
		                "buttons": [
								 {
								   extend: 'print',
								   footer: true,
								   title: '',
								   customize: function ( win ) {
										$(win.document.body).find( 'table' )
											.addClass( 'compact' )
											.css( 'font-size', 'inherit' );
									},
								 },
								 {
									extend:'excel',
									footer: true,
								    title: 'Tag',
								  }
								 ],
						"aaData": list,
						"aoColumns": [	
										{ "mDataProp": "product_name" },
										{ "mDataProp": "op_blc_pcs" },
										{ "mDataProp": "op_blc_gwt" },
										{ "mDataProp": "op_blc_nwt" },
										{ "mDataProp": "inw_pcs" },
										{ "mDataProp": "inw_gwt" },
										{ "mDataProp": "inw_nwt" },
										{ "mDataProp": "outward_pcs" },
										{ "mDataProp": "outward_gwt" },
										{ "mDataProp": "outward_nwt" },
										{ "mDataProp": "closing_pcs" },
										{ "mDataProp": "closing_gwt" },
										{ "mDataProp": "closing_nwt" },
									],
									"footerCallback": function( row, data, start, end, display ){
                                            var cshtotal = 0; 
                                            if(list.length>0)
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
                                                    
                                                    op_blc_pcs = api
                                                    .column(1)
                                                    .data()
                                                    .reduce( function (a, b) {
                                                    return intVal(a) + intVal(b);
                                                    }, 0 );
                                                    $(api.column(1).footer()).html(parseFloat(op_blc_pcs).toFixed(0));
                                                    
                                                    op_blc_gwt = api
                                                    .column(2)
                                                    .data()
                                                    .reduce( function (a, b) {
                                                    return intVal(a) + intVal(b);
                                                    }, 0 );
                                                    $(api.column(2).footer()).html(parseFloat(op_blc_gwt).toFixed(3));
                                                    
                                                    op_blc_nwt = api
                                                    .column(3)
                                                    .data()
                                                    .reduce( function (a, b) {
                                                    return intVal(a) + intVal(b);
                                                    }, 0 );
                                                    $(api.column(3).footer()).html(parseFloat(op_blc_nwt).toFixed(3));
                                                    
                                                    inw_pcs = api
                                                    .column(4)
                                                    .data()
                                                    .reduce( function (a, b) {
                                                    return intVal(a) + intVal(b);
                                                    }, 0 );
                                                    $(api.column(4).footer()).html(parseFloat(inw_pcs).toFixed(0));
                                                    
                                                    inw_gwt = api
                                                    .column(5)
                                                    .data()
                                                    .reduce( function (a, b) {
                                                    return intVal(a) + intVal(b);
                                                    }, 0 );
                                                    $(api.column(5).footer()).html(parseFloat(inw_gwt).toFixed(3));
                                                    
                                                    inw_nwt = api
                                                    .column(6)
                                                    .data()
                                                    .reduce( function (a, b) {
                                                    return intVal(a) + intVal(b);
                                                    }, 0 );
                                                    $(api.column(6).footer()).html(parseFloat(inw_nwt).toFixed(3));
                                                    
                                                    out_w_pcs = api
                                                    .column(7)
                                                    .data()
                                                    .reduce( function (a, b) {
                                                    return intVal(a) + intVal(b);
                                                    }, 0 );
                                                    $(api.column(7).footer()).html(parseFloat(out_w_pcs).toFixed(0));
                                                    
                                                    out_w_gwt = api
                                                    .column(8)
                                                    .data()
                                                    .reduce( function (a, b) {
                                                    return intVal(a) + intVal(b);
                                                    }, 0 );
                                                    $(api.column(8).footer()).html(parseFloat(out_w_gwt).toFixed(3));
                                                    
                                                    out_w_nwt = api
                                                    .column(9)
                                                    .data()
                                                    .reduce( function (a, b) {
                                                    return intVal(a) + intVal(b);
                                                    }, 0 );
                                                    $(api.column(9).footer()).html(parseFloat(out_w_nwt).toFixed(3));
                                                    
                                                    clc_pcs = api
                                                    .column(10)
                                                    .data()
                                                    .reduce( function (a, b) {
                                                    return intVal(a) + intVal(b);
                                                    }, 0 );
                                                    $(api.column(10).footer()).html(parseFloat(clc_pcs).toFixed(0));
                                                    
                                                    clc_gwt = api
                                                    .column(11)
                                                    .data()
                                                    .reduce( function (a, b) {
                                                    return intVal(a) + intVal(b);
                                                    }, 0 );
                                                    $(api.column(11).footer()).html(parseFloat(clc_gwt).toFixed(3));
                                                    
                                                    clc_nwt = api
                                                    .column(12)
                                                    .data()
                                                    .reduce( function (a, b) {
                                                    return intVal(a) + intVal(b);
                                                    }, 0 );
                                                    $(api.column(12).footer()).html(parseFloat(clc_nwt).toFixed(3));
                                                } 
                                            }
                                            else
                                            {
                                                var api = this.api(), data; 
                                                $(api.column(7).footer()).html('');  
                                            }
                                        }
					});			  	 	
				} 
		}
	});
}
function get_old_metal_stock_details()
{
     $("div.overlay").css("display", "block"); 
    my_Date = new Date();
	$.ajax({
		url: base_url+"index.php/admin_ret_reports/metal_stock_details/ajax?nocache=" + my_Date.getUTCSeconds(),
		data:{'from_date':$('#rpt_payments1').html(),'to_date' :$('#rpt_payments2').html(),'id_branch':$('#branch_select').val(),'report_type':$('#report_type').val()},
		type:"POST",
		dataType:"JSON",
		cache:false,
		success:function(data){
		    	$("div.overlay").css("display", "none"); 
			 	var list = data.list;
			 	$('#purchase_item_details').dataTable().fnClearTable();
				var oTable = $('#purchase_item_details').DataTable();
				oTable.clear().draw();				  
				if (list!= null && list.length > 0)
				{  	
					oTable = $('#purchase_item_details').dataTable({
						"bDestroy": true,
		                "bInfo": true,
		                "bFilter": true,
		                "scrollX":'100%',
		                "bSort": true,
		                "dom": 'lBfrtip',
		                "buttons": [
								 {
								   extend: 'print',
								   footer: true,
								   title: '',
								   customize: function ( win ) {
										$(win.document.body).find( 'table' )
											.addClass( 'compact' )
											.css( 'font-size', 'inherit' );
									},
								 },
								 {
									extend:'excel',
									footer: true,
								    title: 'Tag',
								  }
								 ],
						"aaData": list,
						"aoColumns": [	
										{ "mDataProp": "metal_type" },
										{ "mDataProp": "op_blc_gwt" },
										{ "mDataProp": "op_blc_nwt" },
										{ "mDataProp": "inw_gwt" },
										{ "mDataProp": "inw_nwt" },
										{ "mDataProp": "br_out_gwt" },
										{ "mDataProp": "br_out_nwt" },
										{ "mDataProp": "closing_gwt" },
										{ "mDataProp": "closing_nwt" },
									],
									"footerCallback": function( row, data, start, end, display ){
                                            var cshtotal = 0; 
                                            if(list.length>0)
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
                                                    
                                                    op_blc_gwt = api
                                                    .column(1)
                                                    .data()
                                                    .reduce( function (a, b) {
                                                    return intVal(a) + intVal(b);
                                                    }, 0 );
                                                    $(api.column(1).footer()).html(parseFloat(op_blc_gwt).toFixed(3));
                                                    
                                                    op_blc_nwt = api
                                                    .column(2)
                                                    .data()
                                                    .reduce( function (a, b) {
                                                    return intVal(a) + intVal(b);
                                                    }, 0 );
                                                    $(api.column(2).footer()).html(parseFloat(op_blc_nwt).toFixed(3));
                                                    
                                                    inw_gwt = api
                                                    .column(3)
                                                    .data()
                                                    .reduce( function (a, b) {
                                                    return intVal(a) + intVal(b);
                                                    }, 0 );
                                                    $(api.column(3).footer()).html(parseFloat(inw_gwt).toFixed(3));
                                                    
                                                    inw_nwt = api
                                                    .column(4)
                                                    .data()
                                                    .reduce( function (a, b) {
                                                    return intVal(a) + intVal(b);
                                                    }, 0 );
                                                    $(api.column(4).footer()).html(parseFloat(inw_nwt).toFixed(3));
                                                    
                                                    br_out_gwt = api
                                                    .column(5)
                                                    .data()
                                                    .reduce( function (a, b) {
                                                    return intVal(a) + intVal(b);
                                                    }, 0 );
                                                    $(api.column(5).footer()).html(parseFloat(br_out_gwt).toFixed(3));
                                                    
                                                    br_out_nwt = api
                                                    .column(6)
                                                    .data()
                                                    .reduce( function (a, b) {
                                                    return intVal(a) + intVal(b);
                                                    }, 0 );
                                                    $(api.column(6).footer()).html(parseFloat(br_out_nwt).toFixed(3));
                                                    
                                                    closing_gwt = api
                                                    .column(7)
                                                    .data()
                                                    .reduce( function (a, b) {
                                                    return intVal(a) + intVal(b);
                                                    }, 0 );
                                                    $(api.column(7).footer()).html(parseFloat(closing_gwt).toFixed(3));
                                                    
                                                    closing_nwt = api
                                                    .column(8)
                                                    .data()
                                                    .reduce( function (a, b) {
                                                    return intVal(a) + intVal(b);
                                                    }, 0 );
                                                    $(api.column(8).footer()).html(parseFloat(closing_nwt).toFixed(3));
                                                    
                                                    
                                                } 
                                            }
                                            else
                                            {
                                                var api = this.api(), data; 
                                                $(api.column(7).footer()).html('');  
                                            }
                                        }
					});			  	 	
				} 
		}
	});
}
$('#acc_stock_report_type').on('change',function(){
    
    $('#old_metal').css("display","none");
    $('#old_metal_process').css("display","none");
    $('#stone_details').css("display","none");
    
    if(this.value==0)
    {
        $('#old_metal').css("display","block");
    }
    else
    {
        $('#old_metal_process').css("display","block");
    }
});
function get_metal_available_stock_details()
{
    $("div.overlay").css("display", "block"); 
    my_Date = new Date();
	$.ajax({
		url: base_url+"index.php/admin_ret_reports/metal_available_stock_details/ajax?nocache=" + my_Date.getUTCSeconds(),
		data:{'id_branch':$('#branch_select').val()},
		type:"POST",
		dataType:"JSON",
		cache:false,
		success:function(data){
		    $("div.overlay").css("display", "none"); 
	 	    var list = data.list;
	 	    var oTable = $('#stock_details').DataTable();
	 	    var groupColumn = 0;
            oTable.clear().draw();	
           			  
            if (list!= null && list.length > 0)
            {  	
                oTable = $('#stock_details').dataTable({
                "columnDefs": [
                { "visible": false, "targets": groupColumn }
                ],
                
                "drawCallback": function ( settings ) {
                    var api = this.api();
                    var rows = api.rows( {page:'current'} ).nodes();
                    var last=null;
         
                    api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                        if ( last !== group ) {
                            $(rows).eq( i ).before(
                                '<tr class="group"><td colspan="6" style="text-align: left;"><b>'+group+'</b></td></tr>'
                            );
         
                            last = group;
                        }
                    } );
                },
                
                "bDestroy": true,
                "bInfo": true,
                "bFilter": true,
                "scrollX":'100%',
                "bSort": true,
                 "ordering": false,
                "dom": 'lBfrtip',
                "lengthMenu": [[-1, 25, 50, 100, 250], ["All" ,25, 50, 100, 250]],
                
                
                "buttons": [
                {
                extend: 'print',
                footer: true,
                title: '',
                customize: function ( win ) {
                $(win.document.body).find( 'table' )
                .addClass( 'compact' )
                .css( 'font-size', 'inherit' );
                },
                },
                {
                extend:'excel',
                footer: true,
                title: 'Tag',
                }
                ],
                "aaData": list,
                "aoColumns": [	
                { "mDataProp": "type" },
                
                {"mDataProp":function ( row, type, val, meta )
				{
                    url=base_url+'index.php/admin_ret_reports/acc_stock_details/list/'+row.id_stock_summary;
					action_content='<a href="'+url+'" target="_blank" >'+row.product_name+'</a>'
					return action_content;
				}},
					
                { "mDataProp": "gross_wt" },
                { "mDataProp": "net_wt" },
                { "mDataProp": "purity" },
                { "mDataProp": "rate" },
                {
                    "mDataProp": null,
                    "sClass": "control center", 
                    "sDefaultContent":  '<span class="drill-val"><i class="fa fa-chevron-circle-down text-teal"></i></span>'
                },
                ],
                });	
                
                var anOpen =[]; 
        		$(document).on('click',"#stock_details .control", function(){ 
        		   var nTr = this.parentNode;
        		   var i = $.inArray( nTr, anOpen );
        		 
        		   if ( i === -1 ) { 
        				$('.drill-val', this).html('<i class="fa fa-chevron-circle-up text-teal"></i>'); 
        				oTable.fnOpen( nTr, fnFormatRowOldMetalDetails(oTable, nTr), 'details' );
        				anOpen.push( nTr ); 
        		    }
        		    else { 
        				$('.drill-val', this).html('<i class="fa fa-chevron-circle-down text-teal"></i>');
        				oTable.fnClose( nTr );
        				anOpen.splice( i, 1 );
        		    }
        		} );
            }
		},
			  error:function(error)  
			  {
				 $("div.overlay").css("display", "none"); 
			  }	 
	});
 
}
    function fnFormatRowOldMetalDetails(oTable, nTr)
    {
        var oData = oTable.fnGetData( nTr );
        
        if(oData.item_type==0)
        {
            var rowDetail = '';
            var prodTable = 
            '<div class="innerDetails">'+
            '<table class="table table-responsive table-bordered text-center table-sm">'+ 
            '<tr class="bg-teal">'+
            '<th>S.No</th>'+ 
            '<th>Bill Date</th>'+
            '<th>Branch</th>'+
            '<th>Bill No</th>'+
            '<th>Customer</th>'+
            '<th>Gross Wt</th>'+
            '<th>Net Wt</th>'+
            '<th>Purity</th>'+
            '</tr>';
            var sales_details = oData.item_details; 
            var total_gross_wt=0;
            var total_net_wt=0;
            $.each(sales_details, function (idx, val) {
                total_net_wt+=parseFloat(val.net_wt);
                total_gross_wt+=parseFloat(val.gross_wt);
                
                print_url=base_url+'index.php/admin_ret_billing/billing_invoice/'+val.bill_id;
    
                prodTable += 
                '<tr class="prod_det_btn">'+
                '<td>'+parseFloat(idx+1)+'</td>'+
                '<td>'+val.bill_date+'</td>'+
                '<td>'+val.bill_from+'</td>'+
                '<td><a href="'+print_url+'" target="_blank" title="Billing Receipt">'+val.bill_no+'</a></td>'+
                '<td>'+val.cus_name+'</td>'+
                '<td>'+val.gross_wt+'</td>'+
                '<td>'+val.net_wt+'</td>'+
                '<td>'+val.purity+'</td>'+
                '</tr>'; 
            }); 
           
            
            prodTable += 
            '<tr class="prod_det_btn">'+
            '<td colspan="5"><strong>Total</strong></td>'+
            '<td><strong>'+parseFloat(total_gross_wt).toFixed(3)+'</td></strong>'+
            '<td><strong>'+parseFloat(total_net_wt).toFixed(3);+'</strong></td>'+
            '</tr>'; 
            
            rowDetail = prodTable+'</table></div>';
        }
        else if(oData.item_type==1)
        {
            var rowDetail = '';
            var prodTable = 
            '<div class="innerDetails">'+
            '<table class="table table-responsive table-bordered text-center table-sm">'+ 
            '<tr class="bg-teal">'+
            '<th>S.No</th>'+ 
            '<th>Tag No</th>'+
            '<th>Gross Wt</th>'+
            '<th>Net Wt</th>'+
            '<th>Sold Gwt</th>'+
            '<th>Sold Nwt</th>'+
            '<th>Blc Gwt</th>'+
            '<th>Blc Nwt</th>'+
            '</tr>';
            var sales_details = oData.item_details; 
            var total_gross_wt=0;
            var total_net_wt=0;
            $.each(sales_details, function (idx, val) {
                total_net_wt+=parseFloat(val.blc_nwt);
                total_gross_wt+=parseFloat(val.blc_gwt);
                
                print_url=base_url+'index.php/admin_ret_billing/billing_invoice/'+val.bill_id;
    
                prodTable += 
                '<tr class="prod_det_btn">'+
                '<td>'+parseFloat(idx+1)+'</td>'+
                '<td>'+val.tag_code+'</td>'+
                '<td>'+val.gross_wt+'</td>'+
                '<td>'+val.net_wt+'</td>'+
                '<td>'+val.sold_gwt+'</td>'+
                '<td>'+val.sold_nwt+'</td>'+
                '<td>'+val.blc_gwt+'</td>'+
                '<td>'+val.blc_nwt+'</td>'+
                '</tr>'; 
            }); 
           
            
            prodTable += 
            '<tr class="prod_det_btn">'+
            '<td colspan="6"><strong>Total</strong></td>'+
            '<td><strong>'+parseFloat(total_gross_wt).toFixed(3)+'</td></strong>'+
            '<td><strong>'+parseFloat(total_net_wt).toFixed(3);+'</strong></td>'+
            '</tr>'; 
            
            rowDetail = prodTable+'</table></div>';
        }
         else if(oData.item_type==2)
         {
             var rowDetail = '';
         }
        return rowDetail;
    }
//Metal Stock Details
//PO Bills details start here
function get_po_bills_details()
{
    var dt_range    = ($("#dt_range").val()).split('-');
	var from_date   = date_format(dt_range[0].trim());
	var to_date     = date_format(dt_range[1].trim());
		
    $("div.overlay").css("display", "block"); 
    my_Date = new Date();
	$.ajax({
		url: base_url+"index.php/admin_ret_reports/purchase/ajax?nocache=" + my_Date.getUTCSeconds(),
		data:{'from_date': from_date,'to_date' : to_date,'type':$('#pur_type').val(), 'karigar':$('#karigar').val(),'id_category':$('#category').val()},
		type:"POST",
		dataType:"JSON",
		cache:false,
		success:function(data){
		    
			
			$("div.overlay").css("display", "none"); 
			 	var list = data.list;
				var oTable = $('#purchase_bills').DataTable();
				oTable.clear().draw();				  
				if (list!= null && list.length > 0)
				{  	
					oTable = $('#purchase_bills').dataTable({
						"bDestroy": true,
		                "bInfo": true,
		                "bFilter": true,
		                "scrollX":'100%',
		                "bSort": true,
		                "dom": 'lBfrtip',
		                "buttons": [
								 {
								   extend: 'print',
								   footer: true,
								   title: '',
								   customize: function ( win ) {
										$(win.document.body).find( 'table' )
											.addClass( 'compact' )
											.css( 'font-size', 'inherit' );
									},
								 },
								 {
									extend:'excel',
									footer: true,
								    title: 'Tag',
								  }
								 ],
						"aaData": list,
						"aoColumns": [	{ "mDataProp": "po_ref_no" },	
										{ "mDataProp": "karigar" },
										{ "mDataProp": "po_type" },
										{ "mDataProp": "po_date" },
										{ "mDataProp": "category_name" },
										{ "mDataProp": "tot_pcs" },
										{ "mDataProp": "gross_wt" },
										{ "mDataProp": "tot_lwt" },
										{ "mDataProp": "tot_nwt" },
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
								total_pcs = api
								.column(5)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(5).footer()).html(parseFloat(total_pcs));
								total_gwt = api
								.column(6)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(6).footer()).html(parseFloat(total_gwt).toFixed(3));
								total_lswt = api
								.column(7)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(7).footer()).html(parseFloat(total_lswt).toFixed(3));
								total_nwt = api
								.column(8)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(8).footer()).html(parseFloat(total_nwt).toFixed(3));
								
						} 
						}else{
							 var api = this.api(), data; 
							 $(api.column(5).footer()).html('');  
							 $(api.column(6).footer()).html('');  
							 $(api.column(7).footer()).html('');  
							 $(api.column(8).footer()).html('');
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
//PO Bills details end here 
//PO Payment details start here
function get_po_payment_details(){
    
    var dt_range    = ($("#dt_range").val()).split('-');
	var from_date   = date_format(dt_range[0].trim());
	var to_date     = date_format(dt_range[1].trim());
		
    $("div.overlay").css("display", "block"); 
    my_Date = new Date();
	$.ajax({
		url: base_url+"index.php/admin_ret_reports/popayments/ajax?nocache=" + my_Date.getUTCSeconds(),
		data:{'from_date': from_date,'to_date' : to_date, 'karigar':$('#karigar').val()},
		type:"POST",
		dataType:"JSON",
		cache:false,
		success:function(data){
		    
			
			$("div.overlay").css("display", "none"); 
			 	var list = data.list;
				var oTable = $('#purchase_pay_list').DataTable();
				oTable.clear().draw();				  
				if (list!= null && list.length > 0)
				{  	
					oTable = $('#purchase_pay_list').dataTable({
						"bDestroy": true,
		                "bInfo": true,
		                "bFilter": true,
		                "scrollX":'100%',
		                "bSort": true,
		                "dom": 'lBfrtip',
		                "buttons": [
								 {
								   extend: 'print',
								   footer: true,
								   title: '',
								   customize: function ( win ) {
										$(win.document.body).find( 'table' )
											.addClass( 'compact' )
											.css( 'font-size', 'inherit' );
									},
								 },
								 {
									extend:'excel',
									footer: true,
								    title: 'Tag',
								  }
								 ],
						"aaData": list,
						"aoColumns": [	{ "mDataProp": "po_ref_no" },	
										{ "mDataProp": "receivedpcs" },
										{ "mDataProp": "received_gwt" },
										{ "mDataProp": "item_cost" },
										{ "mDataProp": "paidamt" }
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
								total_pcs = api
								.column(1)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(1).footer()).html(parseFloat(total_pcs));
								total_gwt = api
								.column(2)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(2).footer()).html(parseFloat(total_gwt).toFixed(3));
								total_cost = api
								.column(3)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(3).footer()).html(parseFloat(total_cost).toFixed(3));
								total_receivedamt = api
								.column(4)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(4).footer()).html(parseFloat(total_receivedamt).toFixed(3));
								
						} 
						}else{
							 var api = this.api(), data; 
							 $(api.column(1).footer()).html('');  
							 $(api.column(2).footer()).html('');  
							 $(api.column(3).footer()).html('');  
							 $(api.column(4).footer()).html('');
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
//PO Payment details end here 
//PO QC Issue / Receipt
function get_qc_issue_details()
{
    var dt_range    = ($("#dt_range").val()).split('-');
	var from_date   = date_format(dt_range[0].trim());
	var to_date     = date_format(dt_range[1].trim());
    $("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
	 url:base_url+ "index.php/admin_ret_reports/qcstatus/ajax?nocache=" + my_Date.getUTCSeconds(),
	 data:{'from_date': from_date,'to_date' : to_date},
	 dataType:"JSON",
	 type:"POST",
	 success:function(data){
	 	var list=data.list;
		var oTable = $('#purchase_qcbills').DataTable();
		oTable.clear().draw();				  
		if (list!= null && list.length > 0)
		{  	
			oTable = $('#purchase_qcbills').dataTable({
            "bDestroy": true,
            "bInfo": true,
            "bFilter": true,
            "bSort": true,
            "order": [[ 0, "desc" ]],
            "dom": 'lBfrtip',
            "buttons" : ['excel','print'],
            "tableTools": { "buttons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },
            "aaData": list,
            "aoColumns": [
                { "mDataProp": "po_ref_no" },
                { "mDataProp": "date_add" },
                { "mDataProp": "emp_name" },
                { "mDataProp": "product_name" },
                { "mDataProp": "design_name" },
                { "mDataProp": "sub_design_name" },
                { "mDataProp": "total_pcs" },
                { "mDataProp": "gross_wt" },
                { "mDataProp": "less_wt" },
                { "mDataProp": "net_wt" },
                { "mDataProp": function ( row, type, val, meta ){
                return '<span class="badge bg-'+(row.status==1 ? 'red' :'green')+'">'+row.qc_status+'</span>';
                },
                },
                { "mDataProp": "qc_passed_pcs" },
                { "mDataProp": "qc_passed_gwt" },
                { "mDataProp": "qc_passed_lwt" },
                { "mDataProp": "qc_passed_nwt" },
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
								total_pcs = api
								.column(6)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(6).footer()).html(parseFloat(total_pcs));
							
								
								var total_gwt = api
								.column(7)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(7).footer()).html(parseFloat(total_gwt).toFixed(3));
								total_lswt = api
								.column(8)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(8).footer()).html(parseFloat(total_lswt).toFixed(3));
								total_nwt = api
								.column(9)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(9).footer()).html(parseFloat(total_nwt).toFixed(3));
								
								
								
								var total_accpcs = api
								.column(11)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(11).footer()).html(parseFloat(total_accpcs).toFixed(0));
								
								var total_accgwt = api
								.column(12)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(12).footer()).html(parseFloat(total_accgwt).toFixed(3));
								
								var total_accnwt = api
								.column(13)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(13).footer()).html(parseFloat(total_accnwt).toFixed(3));
								
								var total_acclwt = api
								.column(14)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(14).footer()).html(parseFloat(total_acclwt).toFixed(3));
								
						} 
						}else{
							 var api = this.api(), data; 
							 $(api.column(6).footer()).html('');  
							 $(api.column(7).footer()).html('');  
							 $(api.column(8).footer()).html('');
							 $(api.column(9).footer()).html('');
							 
							 $(api.column(10).footer()).html('');  
							 $(api.column(11).footer()).html('');  
							 $(api.column(12).footer()).html('');
							 $(api.column(13).footer()).html(''); 
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
//PO QC Issue / Receipt
function get_hm_issue_details()
{
    var dt_range    = ($("#dt_range").val()).split('-');
	var from_date   = date_format(dt_range[0].trim());
	var to_date     = date_format(dt_range[1].trim());
    $("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
	 url:base_url+ "index.php/admin_ret_reports/pohmstatus/ajax?nocache=" + my_Date.getUTCSeconds(),
	 data:{'from_date': from_date,'to_date' : to_date},
	 dataType:"JSON",
	 type:"POST",
	 success:function(data){
	 	var list=data.list;
		var oTable = $('#purchase_hmbills').DataTable();
		oTable.clear().draw();				  
		if (list!= null && list.length > 0)
		{  	
			oTable = $('#purchase_hmbills').dataTable({
            "bDestroy": true,
            "bInfo": true,
            "bFilter": true,
            "bSort": true,
            "order": [[ 0, "desc" ]],
            "dom": 'lBfrtip',
            "buttons" : ['excel','print'],
            "tableTools": { "buttons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },
            "aaData": list,
            "aoColumns": [
                { "mDataProp": "hm_ref_no" },
                { "mDataProp": "issue_date" },
                { "mDataProp": "karigar_name" },
                { "mDataProp": "hm_process_pcs" },
                { "mDataProp": "hm_process_gwt" },
                { "mDataProp": "hm_process_lwt" },
                { "mDataProp": "hm_process_nwt" },
                { "mDataProp": "total_hm_charges" },
                { "mDataProp": function ( row, type, val, meta ){
                return '<span class="badge bg-'+(row.status==1 ? 'red' :'green')+'">'+row.hm_status+'</span>';
                },
                },
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
								total_pcs = api
								.column(3)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(3).footer()).html(parseFloat(total_pcs));
							
								
								var total_gwt = api
								.column(4)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(4).footer()).html(parseFloat(total_gwt).toFixed(3));
								total_lswt = api
								.column(5)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(5).footer()).html(parseFloat(total_lswt).toFixed(3));
								total_nwt = api
								.column(6)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(6).footer()).html(parseFloat(total_nwt).toFixed(3));
								
								var	total_hmchrg = api
								.column(7)
								.data()
								.reduce( function (a, b) {
									return intVal(a) + intVal(b);
								}, 0 );
								$(api.column(7).footer()).html(parseFloat(total_hmchrg).toFixed(3));
								
						} 
						}else{
							 var api = this.api(), data; 
							 $(api.column(3).footer()).html('');  
							 $(api.column(4).footer()).html('');  
							 $(api.column(5).footer()).html('');
							 $(api.column(6).footer()).html('');
							 $(api.column(7).footer()).html('');
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
//tag wise profit list
function get_tag_wise_profit_list()
{
$("div.overlay").css("display", "block");
	var dt_range=($("#dt_range").val()).split('-');
     var branch_name=($('#branch_name').val()!='' && $('#branch_name').val()!=undefined ? $('#branch_name').val() : $("#branch_select option:selected").text());
	var company_name=$('#company_name').val();
	var title="<b><span style='font-size:15pt;margin-left:30%;'>"+company_name+"</span></b><b><span style='font-size:12pt;'></span></b></br>"+"<span style=font-size:13pt;margin-left:25%;>&nbsp;&nbsp;BILL WISE DETAILED REPORT "+($("#branch_select").val()!='' ? "COST CENTER:"+branch_name+" ":'')+" From&nbsp;:&nbsp;"+dt_range[0]+" &nbsp;&nbsp;To Date&nbsp;:&nbsp;"+dt_range[1]+"</span>";
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/tagwiseprofit/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'dt_range' :$("#dt_range").val(), 'id_branch':($('#branch_filter').val()!='' && $('#branch_filter').val()!=undefined ? $('#branch_filter').val(): $("#branch_select").val())}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			 var list = data.list;
	 		$("#tagprofit_details > tbody > tr").remove();  
	 		$('#tagprofit_details').dataTable().fnClearTable();
    		$('#tagprofit_details').dataTable().fnDestroy();	
			if (list!= null)
			{  	
				trHTML = ''; 
				tfootHTML = '';
				var tot_ret_amt     = 0;
				var tot_sales_wast  = 0;
				var total_pur_wast  = 0;
				
				
				
				$.each(list, function (i, plbill) { 
			            trHTML += '<tr>' +
			                    '<td><strong>'+ i+'</strong></td>'
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
    							+'<td></td>'
    							'</tr>';
    					var tot_pcs         = 0;
    					var tot_salesgwt    = 0;
    					var tot_salesnwt    = 0;
    					var tot_saleswast   = 0;
    					var tot_saleamt     = 0;
    					var tot_wastprofit  = 0;
    					var tot_profitamt  = 0;
    					var tot_tag_purchase_cost  = 0;
				        $.each(plbill, function (ekey, bill) { 
        				    tot_pcs+= parseInt(bill.piece); 
        				
        				    tot_salesgwt+=parseFloat(bill.salegrosswt);
        				
        				    tot_salesnwt+=parseFloat(bill.salenetwt);
        				    
        				    tot_saleswast+=parseFloat(bill.wastagewt);
        				    
        				    tot_saleamt+=parseFloat(bill.tot_bill_amount);
        				    
        				    tot_wastprofit+=parseFloat(bill.wastageprofit);
        				    tot_profitamt+=parseFloat(bill.profitamt);
        				    tot_tag_purchase_cost+=parseFloat(bill.tag_purchase_cost);
				
							trHTML += '<tr>' +
							'<td>'+ bill.product_name+'</td>'
							+'<td>'+bill.design_name+'</td>'
							+'<td>'+bill.sub_design_name+'</td>'
							+'<td>'+bill.bill_no+'</td>'
							+'<td>'+bill.bill_date+'</td>'
							+'<td>'+bill.tag_code+'</td>'
							+'<td>'+bill.piece+'</td>'
							+'<td>'+bill.salegrosswt+'</td>'
							+'<td>'+bill.salenetwt+'</td>'
							+'<td>'+bill.wastagewt+'</td>'
							+'<td>'+bill.rate_per_grm+'</td>'
							+'<td>'+bill.tot_bill_amount+'</td>'
							+'<td>'+bill.purwastagewt+'</td>'
							+'<td></td>'
							+'<td>'+bill.tag_purchase_cost+'</td>'
							+'<td>'+bill.wastageprofit+'</td>'
							+'<td>'+bill.profitwastper+'</td>'
							+'<td>'+bill.profitamt+'</td>'
							'</tr>';
					
						
					});
					
					trHTML += '<tr>' +
			                    '<td><strong>'+ i +' Total </strong></td>'
    							+'<td></td>'
    							+'<td></td>'
    							+'<td></td>'
    							+'<td></td>'
    							+'<td></td>'
    							+'<td><strong>'+ tot_pcs +'</strong></td>'
    							+'<td><strong>'+ parseFloat(tot_salesgwt).toFixed(3) +'</strong></td>'
    							+'<td><strong>'+ parseFloat(tot_salesnwt).toFixed(3) +'</strong></td>'
    						    +'<td><strong>'+ parseFloat(tot_saleswast).toFixed(3) +'</strong></td>'
    							+'<td></td>'
    							+'<td><strong>'+ parseFloat(tot_saleamt).toFixed(3) +'</strong></td>'
    							+'<td></td>'
    							+'<td></td>'
    							+'<td><strong>'+ parseFloat(tot_tag_purchase_cost).toFixed(2) +'</strong></td>'
    							+'<td><strong>'+ parseFloat(tot_wastprofit).toFixed(3) +'</strong></td>'
    							+'<td></td>'
    							+'<td><strong>'+ parseFloat(tot_profitamt).toFixed(2) +'</strong></td>'
    							'</tr>';
			       
				});
				/* += '<tr style="font-weight:bold;">' +
							+'<td></td>'
							+'<td></td>'
							+'<td></td>'
							+'<td></td>'
							+'<td></td>'
							+'<td>GrandTotal</td>'
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
							'</tr>';*/
			    $('#tagprofit_details > tbody').html(trHTML);
			     // Check and initialise datatable
			     
				if ( ! $.fn.DataTable.isDataTable( '#tagprofit_details' ) ) { 
					oTable = $('#tagprofit_details').dataTable({ 
					"bSort": false, 
					"bInfo": true, 
					"scrollX":'100%',  
					"dom": 'lBfrtip',
					"paging":false,
                    "fixedHeader": true,
        			"buttons": [
        			
					{
						extend: 'print',
						footer: true,
						title: '',
						messageTop: title,
						exportOptions: {
                            columns: ':visible'
                        },
						customize: function ( win ) {
						$(win.document.body).find( 'table' )
						.addClass( 'compact' )
				    	.css('font-size','12px')
						.css('font-family','sans-serif');
						},
					},
					'colvis',
					
					{
						extend:'excel',
						footer: true,
						title: 'Tag Wise Profit List',
					}
					], 
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
//tag wise profit list
//Approval tag item report
function set_approval_tagged_items()
{
	$("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/approvaltag_items_designwise/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'id_product':$('#prod_select').val(),'id_design' :$("#des_select").val(),'id_branch':($('#branch_filter').val()!='' && $('#branch_filter').val()!=undefined ? $('#branch_filter').val(): $("#branch_select").val()),'from_weight':$('#from_weight').val(),'to_weight':$('#to_weight').val(),'id_size':$('#select_size').val()}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			 	$("div.overlay").css("display", "none"); 
			 	var list = data.list;
			 	var stock_details = data.stock_details;
			 	var tot_weight=0;
			 	$.each(list,function(key,item){
			 	    tot_weight+=parseFloat(item.gross_wt);
			 	})
				$('#total_count').text(list.length);
				$('#total_wt').text(parseFloat(tot_weight).toFixed(3));
				
				$("#tag_item_branchwise > tbody > tr").remove();
				if((stock_details.length>0) && ($("#branch_select").val()==0))
				{
				    var trHtml='';
				    var tot_pcs=0;
				    var tot_gross_wt=0;
				    var tot_net_wt=0;
				    $.each(stock_details,function(key,items){
				        tot_pcs+=parseFloat(items.piece);
				        tot_gross_wt+=parseFloat(items.gross_wt);
				        tot_net_wt+=parseFloat(items.gross_wt);
				        trHtml+='<tr>'
				                    +'<td>'+items.branch_name+'</td>'
				                    +'<td>'+items.piece+'</td>'
				                    +'<td>'+items.gross_wt+'</td>'
				                    +'<td>'+items.net_wt+'</td>'
				                +'</tr>';
				    });
				    trHtml+='<tr style="font-weight:bold;">'
			                    +'<td>TOTAL</td>'
			                    +'<td>'+parseFloat(tot_pcs).toFixed(2)+'</td>'
			                    +'<td>'+parseFloat(tot_gross_wt).toFixed(2)+'</td>'
			                    +'<td>'+parseFloat(tot_net_wt).toFixed(2)+'</td>'
			                +'</tr>';
				    $('#tag_item_branchwise').append(trHtml);
				}
				
				var oTable = $('#tag_items_list').DataTable();
				oTable.clear().draw();				  
				if (list!= null && list.length > 0)
				{  	
					oTable = $('#tag_items_list').dataTable({
						"bDestroy": true,
		                "bInfo": true,
		                "bFilter": true,
						"order": [[ 0, "desc" ]],
		                "scrollX":'100%',
		                "bSort": true,
		                "dom": 'lBfrtip',
		                "buttons": [
								 {
								   extend: 'print',
								   footer: true,
								   title: "Design-wise Tag Items",
								   customize: function ( win ) {
										$(win.document.body).find( 'table' )
											.addClass( 'compact' )
											.css( 'font-size', 'inherit' );
									},
								 },
								 {
									extend:'excel',
									footer: true,
								    title: "Design-wise Tag Items", 
								  }
								 ],
						"aaData": list,
						"aoColumns": [	
										{ "mDataProp": "tag_lot_id" },
										{ "mDataProp": "po_ref_no" },
										{ "mDataProp": "karigar" },
										{ "mDataProp": "tag_code" },
										{ "mDataProp": "tag_date" },
										{ "mDataProp": "branch_name" },
										{ "mDataProp": "product_name" },
										{ "mDataProp": "design_name" },
										{ "mDataProp": "sub_design_name" },
										{ "mDataProp": "piece" },
										{ "mDataProp": "gross_wt" },
										{ "mDataProp": "net_wt" },
										{ "mDataProp": "size_name" },
										{ "mDataProp": "retail_max_wastage_percent" },
										{ "mDataProp": function ( row, type, val, meta ){
											if(row.tag_mc_type==2)
											{
												return row.tag_mc_value;
											}else{
												return '-';
											}
											
										},
										},
										{ "mDataProp": function ( row, type, val, meta ){
										    if(row.tag_mc_type==1)
											{
												return row.tag_mc_value;
											}else{
												return '-';
											}
											
										},
										},
										{ "mDataProp": "sales_value" },
									
                                        { "mDataProp": function ( row, type, val, meta )
                                        { 
                                            return '<span class="badge bg-red">'+row.tot_est+'</span>';
                                        }},
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
////Approval tag item report
//Customer Ledger
function get_CustomerLedger()
{
    $("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/customer_ledger_statement/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: {},
			 dataType:"JSON",
			 type:"POST",
			 success:function(data)
			 {
			        var list = data.list;
    				var oTable = $('#customer_ledgere_list').DataTable();
    				oTable.clear().draw();				  
    				if (list!= null && list.length > 0)
    				{  	
    					oTable = $('#customer_ledgere_list').dataTable({
    						"bDestroy": true,
    		                "bInfo": true,
    		                "bFilter": true,
    		                "scrollX":'100%',
    		                "bSort": true,
    		                "dom": 'lBfrtip',
    		                "buttons": [
    								 {
    								   extend: 'print',
    								   footer: true,
    								   title: "Cusomer Ledger",
    								   messageTop: "Cusomer Ledger",
    								   customize: function ( win ) {
    										$(win.document.body).find( 'table' )
    											.addClass( 'compact' )
    											.css( 'font-size', 'inherit' );
    									},
    								 },
    								 {
    									extend:'excel',
    									footer: true,
    								    title: "Cusomer Ledger",
    								  }
    								 ],
    						"aaData": list,
    						"aoColumns": [	
    										{ "mDataProp": "cusName" },
    										{ "mDataProp": "Debit" },
    										{ "mDataProp": "Credit" },
    										{ "mDataProp": "balance" },
    										{
                                                "mDataProp": null,
                                                "sClass": "control center", 
                                                "sDefaultContent":  '<span class="drill-val"><i class="fa fa-chevron-circle-down text-teal"></i></span>'
                                            },
    									  ],
    					});
    					
    						var anOpen =[]; 
                    		$(document).on('click',"#customer_ledgere_list .control", function(){ 
                    		   var nTr = this.parentNode;
                    		   var i = $.inArray( nTr, anOpen );
                    		 
                    		   if ( i === -1 ) { 
                    				$('.drill-val', this).html('<i class="fa fa-chevron-circle-up text-teal"></i>'); 
                    				oTable.fnOpen( nTr, fnFormatRowLedgerDetails(oTable, nTr), 'details' );
                    				anOpen.push( nTr ); 
                    		    }
                    		    else { 
                    				$('.drill-val', this).html('<i class="fa fa-chevron-circle-down text-teal"></i>');
                    				oTable.fnClose( nTr );
                    				anOpen.splice( i, 1 );
                    		    }
                    		} );
    				}
				$("div.overlay").css("display", "none"); 
              },
			  error:function(error)  
			  {
				 $("div.overlay").css("display", "none"); 
			  }	 
	      });
 
}
function fnFormatRowLedgerDetails(oTable, nTr)
{
    var oData = oTable.fnGetData( nTr );
  var rowDetail = '';
  var prodTable = 
     '<div class="innerDetails">'+
      '<table class="table table-responsive table-bordered text-center table-sm">'+ 
        '<tr class="bg-teal">'+
        '<th>Date</th>'+ 
         '<th>Ref No</th>'+
         '<th>Acc type</th>'+
         '<th>Debit</th>'+
         '<th>Credit</th>'+
         '<th>Balance</th>'+
        '</tr>';
    var ledger_details = oData.statements; 
    var total_pcs=0;
    var total_gwt=0;
  $.each(ledger_details, function (idx, val) {
  	prodTable += 
        '<tr class="prod_det_btn">'+
        '<td>'+val.billDate+'</td>'+
        '<td>'+val.refNo+'</td>'+
        '<td>'+val.accType+'</td>'+
        '<td>'+val.Debit+'</td>'+
        '<td>'+val.Credit+'</td>'+
        '<td>'+val.balance+'</td>'+
        '</tr>'; 
  }); 
 
        
  rowDetail = prodTable+'</table></div>';
  return rowDetail;
}
//Customer Ledger
//Supplier Ledger
function getSupplierLedger()
{
    $("div.overlay").css("display", "block"); 
	my_Date = new Date();
	var dt_range=($("#dt_range").val()).split('-');
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/supplierledger/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: { 'dt_range' :$("#dt_range").val(), "ledgerType" : $("input[name='ledger_type']:checked").val(), "partyId" : $("#karigar").val() },
			 dataType:"JSON",
			 type:"POST",
			 success:function(data)
			 {
			        var opening_value = "";
			        var closing_value = "";
			        var list = data.list;
			        $.each(data.list,function (key, item) {
			            if(key == 0){
			                opening_value = "Opening Balance : " + item.openingbalance;
			            }
			            if(data.list.length - 1 == key){
			                closing_value = "Closing Balance : " + item.balance;
			            }
			        });
			        
    				var oTable = $('#supplier_ledgere_list').DataTable();
    				oTable.clear().draw();				  
    				if (list!= null && list.length > 0)
    				{  	
    					oTable = $('#supplier_ledgere_list').dataTable({
    						"bDestroy": true,
    		                "bInfo": true,
    		                "bFilter": true,
    		                "scrollX":'100%',
    		                "bSort": false,
    		                "dom": 'lBfrtip',
    		                "buttons": [
    								 {
    								   extend: 'print',
    								   footer: true,
    								   title: "Supplier Ledger",
    								   messageTop: "Supplier Ledger",
    								   customize: function ( win ) {
    										$(win.document.body).find( 'table' )
    											.addClass( 'compact' )
    											.css( 'font-size', 'inherit' );
    									},
    								 },
    								 {
    									extend:'excel',
    									footer: true,
    								    title: "Supplier Ledger",
    								  }
    								 ],
    						"aaData": list,
    						"aoColumns": [	
    						                { "mDataProp": "transdate" },
    						                { "mDataProp": function ( row, type, val, meta ) {
    						                    if($("input[name='ledger_type']:checked").val() == 1){
                                                    return row.accType + " " + row.ref_no;
    						                    }else{
    						                         return row.accType + " " + row.ref_no + " Issue Wt : " + row.issue_wt + " Purity : " + row.purity;
    						                    }
                                            }},
                                            { "mDataProp": "openingbalance" },
    										{ "mDataProp": "Debit" },
    										{ "mDataProp": "Credit" },
    										{ "mDataProp": "balance" }
    									  ],
    					});
    				}
    				//$( "<p>Test</p>" ).insertAfter(  ".dt-buttons"  );
    				if($(".insertopenclose").length > 0 ){
    				    $( ".insertopenclose" ).remove();
    				}
    				$(".dt-buttons").after('<div class="col-md-6 insertopenclose" style="font-weight: bold;text-align: center;">'+ opening_value +' \t\t '+ closing_value +'</div>');
    			
    				
				$("div.overlay").css("display", "none"); 
              },
			  error:function(error)  
			  {
				 $("div.overlay").css("display", "none"); 
			  }	 
	      });
 
}
//Supplier Ledger
//Old tag import
$("#upload_csv").on('submit',function(evt){
    evt.preventDefault();
});
function uploadFile_new()
{
	file = $('#csv_file')[0].files[0];
	if($('#branch_select').val() == null || $('#branch_select').val() =='')
	{
		$.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Select Branch..'});
	   return false;
	}
	else if ($('input[name="is_check_import_type"]:checked').val() == '' || $('input[name="is_check_import_type"]:checked').val() == undefined) {
		 $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Select Import Type..'});
		 return false;
	}
	else if(file == null || file =='')
	{
		$.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br>"+'Select File..'});
		 return false;
	}
	is_check_import_type  = $('input[name="is_check_import_type"]:checked').val();
		if (is_check_import_type  == 1) {
		import_type = "file_upload_new_tags";
		} else {
			import_type = "file_upload_old_tags";
		}
	file_update(import_type);
}
function get_old_sale_report_report()
{
	my_Date = new Date();
        $.ajax({
            url:base_url+ "index.php/admin_ret_reports/old_sale_report/ajax?nocache=" + my_Date.getUTCSeconds(),
        data: ( {'from_date':$('#old_tag_report_date1').html(),'to_date':$('#old_tag_report_date2').html()}),
            dataType:"JSON",
			cache:false,
            type:"POST",
            success:function(data)
            {
				console.log(data);
				var finance = data.list;
	            var oTable = $('#old_tag_report_list').DataTable();
	            oTable.clear().draw();
			  	 if (finance!= null && finance.length > 0)
			  	  {
					  	oTable = $('#old_tag_report_list').DataTable({
				                "bDestroy": true,
				                "bInfo": true,
				                "bFilter": true,
				                "bSort": true,
				                "order": [[ 0, "desc" ]],
				                "order": [[ 0, "desc" ]],
				                "aaData": finance,
				                "aoColumns": [
                          { "mDataProp": function ( row, type, val, meta ) {
                           return row.import_date !=null && row.import_date !="" ?row.import_date:'-';
                         }},
                          { "mDataProp": function ( row, type, val, meta ) {
                      		  return row.total !=null?row.total:'-';
                          }},
                          { "mDataProp": function ( row, type, val, meta ) {
                      		  return row.upt_tag !=null?row.upt_tag:'-';
                          }},
						  { "mDataProp": function ( row, type, val, meta ) {
                      		  return row.mis_tag !=null?row.mis_tag:'-';
                          }},
                          { "mDataProp": function ( row, type, val, meta ) {
                      		  return row.name !=null?row.name:'-';
                          }}
                        
                        ]
				            });
					  	 }
            },
            error:function(error)
            {
               $("div.overlay").css("display", "none");
            },
            complete: function(data)
            {
            }
        });
    
}	
function file_update(import_type)
{
   $("div.overlay").css("display", "block"); 
  my_Date = new Date();
  file = $('#csv_file')[0].files[0];
  if(file != undefined){
    formData= new FormData();
	formData.append("id_branch", $('#branch_select').val());
      formData.append("filepath", file);
	  
      $.ajax({
        url: base_url+ "index.php/admin_ret_reports/"+import_type+"?nocache=" + my_Date.getUTCSeconds()+''+my_Date.getUTCMinutes()+''+my_Date.getUTCHours(),
        type: "POST",
        data: formData,
        dataType:"JSON",
        processData: false,
        contentType: false,
        success: function(data){
			console.log(data);
			if(data.staus)
			{
				$.toaster({ priority : 'success', title : 'Success!', message : ''+"</br>"+data.message});
				window.location.reload(true);
			}
			else{
				$.toaster({ priority : 'warning', title : 'Warning!', message : ''+"</br>"+data.message});
			}
			$("div.overlay").css("display", "none"); 
        }
      });
  }
	
}
//Old tag import
//Advance Details Report
function get_advance_details()
{
    $("div.overlay").css("display","block");
    my_Date = new Date();
    $.ajax({
    url:base_url+ "index.php/admin_ret_reports/advance_total_details/ajax?nocache=" + my_Date.getUTCSeconds(),
    data: ( {'customer_id': $('#Cus_id').val(),'id_branch':$("#branch_select").val(),'from_date':$('#rpt_from_date').html(),'to_date' :$('#rpt_to_date').html()}),
    dataType:"JSON",
    type:"POST",
    success:function(data){
    var list=data;
    $("div.overlay").css("display", "none"); 
    var oTable = $('#advance_total_list').DataTable();
    oTable.clear().draw();  
    if (list!= null && list.length > 0)
    {   
    oTable = $('#advance_total_list').dataTable({
        "bDestroy": true,
        "bInfo": true,
        "bFilter": true,
        "bSort": true,
        "order": [[ 0, "asc" ]],
        "aaData": list,
        "aoColumns": [  
            { "mDataProp": "cus_name" },
            { "mDataProp": "mobile" },
            {"mDataProp": "advance_amount"},
            {"mDataProp": "utilized_amount"},
            {"mDataProp": "refund_amount"},
            {"mDataProp": "balance_amount"},
            {
            "mDataProp": null,
            "sClass": "control center", 
            "sDefaultContent":  '<span class="drill-val"><i class="fa fa-chevron-circle-down text-teal"></i></span>'
            },
        ],
        "footerCallback": function(row,data,start,end,display)
		{
			if(list.length>0)
			{
				var api=this.api(), data;
				for(var i=0; i<data.length-1;i++)
				{
					var intVal = function(i)
					{
						return typeof i === 'string' ?
						i.replace(/[\$,]/g, '')*1 :
						typeof i === 'number' ?
						i : 0;
					
				};
				$(api.column(0).footer() ).html('Total');
				total_adv_amt = api
				.column(2)
				.data()
				.reduce(function(a,b){
					return intVal(a) + intVal(b);
				}, 0);
				$(api.column(2).footer()).html(parseFloat(total_adv_amt).toFixed(2));
				
				total_utilized_amt = api
				.column(3)
				.data()
				.reduce(function(a,b){
					return intVal(a) + intVal(b);
				}, 0);
				$(api.column(3).footer()).html(parseFloat(total_utilized_amt).toFixed(2));
				total_refund_amt = api
				.column(4)
				.data()
				.reduce(function(a,b){
					return intVal(a) + intVal(b);
				}, 0);
				$(api.column(4).footer()).html(parseFloat(total_refund_amt).toFixed(2));
				
				total_refund_amt = api
				.column(5)
				.data()
				.reduce(function(a,b){
					return intVal(a) + intVal(b);
				}, 0);
				$(api.column(5).footer()).html(parseFloat(total_refund_amt).toFixed(2));
			}
		}else{
			var api = this.api(), data; 
			 $(api.column(6).footer()).html('');
			 $(api.column(4).footer()).html('');
			 $(api.column(5).footer()).html('');
		}
		}
    
    }); 
    var anOpen =[]; 
    $(document).on('click',"#advance_total_list .control", function(){ 
    var nTr = this.parentNode;
    var i = $.inArray( nTr, anOpen );
    
    if ( i === -1 ) { 
    $('.drill-val', this).html('<i class="fa fa-chevron-circle-up text-teal"></i>'); 
    oTable.fnOpen( nTr, fnFormatRowAdvanceDetails(oTable, nTr), 'details' );
    anOpen.push( nTr ); 
    
    }
    else { 
    $('.drill-val', this).html('<i class="fa fa-chevron-circle-down text-teal"></i>');
    oTable.fnClose( nTr );
    anOpen.splice( i, 1 );
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
function fnFormatRowAdvanceDetails(oTable, nTr)
{
        var oData = oTable.fnGetData( nTr );
        var rowDetail = '';
        var tot_row=0;
        var prodTable = 
        '<div class="innerDetails">'+
        '<table class="table table-responsive table-bordered text-center table-sm">'+ 
        '<tr class="bg-teal">'+
        '<th>S.No</th>'+ 
        '<th>Bill No</th>'+
        '<th>Bill Date</th>'+
        '<th>Amount</th>'+
        '<th>Type</th>'+
        '</tr>';
        var details = oData.advance_details; 
        
        tot_row=details.length;
        $.each(details, function (idx, val) 
        {
            if(val.type =='Advance')
            {
            url=base_url+'index.php/admin_ret_billing/receipt/receipt_print/'+val.id_issue_receipt;
            action_content='<a href="'+url+'" target="_blank" title="Billing Receipt">'+val.bill_no+'</a>'
            }
            else if(val.type=='Utilized')
            {
                print_url=base_url+'index.php/admin_ret_billing/billing_invoice/'+val.bill_id;
                action_content='<a href="'+print_url+'" target="_blank" title="Billing Receipt">'+val.bill_no+'</a>'
            }
            else if(val.type=='CHIT')
            {
                print_url=base_url+'index.php/payment/invoice/'+val.id_payment+'/'+val.id_scheme_account;
                action_content='<a href="'+print_url+'" target="_blank" title="Payment Receipt">'+val.bill_no+'</a>'
            }
        
        prodTable += 
        '<tr class="prod_det_btn">'+
        '<td>'+parseFloat(idx+1)+'</td>'+
        '<td>'+(val.bill_no == null ? '-' : action_content)+'</td>'+
        '<td>'+(val.bill_date == null ? '-' : val.bill_date)+'</td>'+
        '<td>'+(val.amount== null ? '-': val.amount )+'</td>'+
        '<td>'+val.type+'</td>'+
        '</tr>'; 
        }); 
        rowDetail = prodTable+'</table></div>';
        return rowDetail;
}
jQuery("#Mob_search").on("input", function(){  
    var mob_ledger = $("#Mob_search").val(); 
    if(mob_ledger.length >= 3) { 
    getMobileSearch(mob_ledger);
    } 
});
		
	
	
	
function getMobileSearch(searchTxt)
{
    my_Date = new Date();
    $.ajax({
    url: base_url+'index.php/admin_ret_reports/advance_total_details/mobileBySearch/?nocache=' + my_Date.getUTCSeconds(),             
    dataType: "json", 
    method: "POST", 
    data: {'mob_no':searchTxt}, 
    success: function (data) { 
    $( "#Mob_search" ).autocomplete(
    {
    source: data,
    select: function(e, i)
    { 
    e.preventDefault();
    $("#Mob_search" ).val(i.item.label); 
    $("#Cus_id" ).val(i.item.value); 
    },
    response: function(e, i) 
    {
    // ui.content is the array that's about to be sent to the response callback.
    if (i.content.length === 0) {
    $("#mob_err").html('<p style="color:red">Enter a valid Mobile Number</p>');
    $('#Cus_id').val('');
    }
    else
    {
    $("#mob_err").html('');
    } 
    },
    minLength: 0,
    });
    }
    });
}
//Advance Details Report
// Sales Return Report
$('#sales_return_search').on('click',function()
{
	get_sales_return();
});
function get_sales_return()
{
	$("div.overlay").css("dislpay","block");
	my_Date = new Date();
	$.ajax({
    	url: base_url+'index.php/admin_ret_reports/sales_return/ajax/?nocache=' + my_Date.getUTCSeconds(), 
		data: ( {'metal' :$("#filter_metal").val(),'dt_range' :$("#dt_range").val(),'id_branch':$("#branch_filter").val()}),           
    	dataType: "json", 
    	type: "POST",
		success:function(data)
		{
			$("div.overlay").css("dislpay","none");	
			var list=data.list;
			//console.log(list);
			var oTable=$('#sales_return_report').DataTable();
			oTable.clear().draw();
			if(list!=null && list.length > 0)
			{
				oTable=$('#sales_return_report').dataTable({
					"bDestroy": true,
					"bInfo": true,
					"bFilter": true,
					"order": [[ 0, "desc" ]],
					"scrollX":'100%', 
					"bSort": true, 
					"dom": 'lBfrtip',
					"buttons": [
						{
							extend: 'print',
							footer: true,
							title: "Sales Return Report - "+$("#dt_range").val()+"&nbsp;&nbsp;"+ getDisplayDateTime(),
							customize: function ( win ) 
							{
								$(win.document.body).find( 'table' )
								.addClass( 'compact' )
								.css( 'font-size', 'inherit' );
							},
						},
					 	{
							extend:'excel',
							footer: true,
							title: "Sales Return Report - "+$("#dt_range").val()+"&nbsp;&nbsp;"+ getDisplayDateTime(),
					  	}
					], 
					"aaData": list,
					"aoColumns":[
					{"mDataProp":"branch_name"},
					{"mDataProp":function ( row, type, val, meta )
					{
                        print_url=base_url+'index.php/admin_ret_billing/billing_invoice/'+row.bill_id;
						action_content='<a href="'+print_url+'" target="_blank" title="Billing Receipt">'+row.s_ret_refno+'</a>'
						return action_content;
					}},
					{"mDataProp":"bill_date"},
					{"mDataProp":"emp_name"},
                    {"mDataProp":"emp_code"},
					{"mDataProp":"tag_code"},
					{"mDataProp":"product_name"},
					{"mDataProp":"design_name"},
					{"mDataProp":"sub_design_name"},
					{"mDataProp":"gross_wt"},
					{"mDataProp":"net_wt"},
					{"mDataProp":"wastage_percent"},
					{"mDataProp":"item_cost"},
					{"mDataProp":"customer"},
					{"mDataProp":"tag_process"}
				],
				"footerCallback": function(row,data,start,end,display)
				{
					if(list.length>0)
					{
						var api=this.api(), data;
						for(var i=0; i<data.length-1;i++)
						{
							var intVal = function(i)
							{
								return typeof i === 'string' ?
								i.replace(/[\$,]/g, '')*1 :
								typeof i === 'number' ?
								i : 0;
							
						};
						$(api.column(0).footer() ).html('Total');
						gross_wt = api
						.column(8)
						.data()
						.reduce(function(a,b){
							return intVal(a) + intVal(b);
						}, 0);
						$(api.column(8).footer()).html(parseFloat(gross_wt).toFixed(3));
						
						net_wt = api
						.column(9)
						.data()
						.reduce(function(a,b){
							return intVal(a) + intVal(b);
						}, 0);
						$(api.column(9).footer()).html(parseFloat(net_wt).toFixed(3));
						
					}
				}else{
					var api = this.api(), data; 
					 $(api.column(6).footer()).html('');
					 $(api.column(4).footer()).html('');
					 $(api.column(5).footer()).html('');
				}
				}
				
			});
		}
$("div.overlay").css("display","none");
},
error:function(error)  
{
   $("div.overlay").css("display", "none"); 
}
});
}
// Sales Return Report
function set_dashboard_btlist()
{
    $("div.overlay").css("display","block");
    my_Date=new Date();
    $.ajax({
    url: base_url+"index.php/admin_ret_reports/dashboard_btList?nocache=" + my_Date.getUTCSeconds(),
    type:"POST",
    data:{'from_date':ctrl_page[2],'to_date':ctrl_page[3],'type':ctrl_page[4],'id_branch':ctrl_page[5],'id_product':ctrl_page[6] },
    dataType:"JSON",
    cache:false,
    success:function(data){
    $("div.overlay").css("display","none");
    var list=data.bt_list;
    console.log(list);
    var oTable= $('#dash_bt_list').DataTable();
    oTable.clear().draw();				  
        if (list!= null && list.length > 0)
        {  	
            oTable = $('#dash_bt_list').dataTable({
            "bDestroy": true,
            "bInfo": true,
            "bFilter": true,
            "scrollX":'100%',
            "bSort": true,
            "dom": 'lBfrtip',
            "buttons": [
            {
            extend: 'print',
            footer: true,
            title: '',
            customize: function ( win ) {
            $(win.document.body).find( 'table' )
            .addClass( 'compact' )
            .css( 'font-size', 'inherit' );
            },
            },
            {
            extend:'excel',
            footer: true,
            title: 'Branch Transfer',
            }
            ],
            "aaData": list,
            "aoColumns": [	{render: function (data, type, row, meta) 
            {
            return meta.row + 1;
            } 
            },	
            { "mDataProp": "from_branch_name" },
            { "mDataProp": "to_branch_name" },
            { "mDataProp": "product_name" },
            { "mDataProp": "tot_pcs" },
            { "mDataProp": "tot_gwt" },
            { "mDataProp":  function ( row, type, val, meta ){
            if(row.status==1)
            {
            return 'Approval Pending';
            }
            else
            {
            return 'Download Pending';
            }
            
            
            }},
            ],
            "footerCallback":function( row, data, start, end, display )
            {
                if(list.length>0)
                {
                    var api = this.api(), data;
                    
                    for( var i=0; i<=data.length-1;i++){
                    
                    var intVal = function ( i ) {
                    return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                    i : 0;
                    };	
                    
                    $(api.column(0).footer() ).html('Total');	
                    
                    tot_pcs = api
                    .column(4)
                    .data()
                    .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                    }, 0 );
                    $(api.column(4).footer()).html(parseFloat(tot_pcs));
                    
                    tot_gwt = api
                    .column(5)
                    .data()
                    .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                    }, 0 );
                    $(api.column(5).footer()).html(parseFloat(tot_gwt).toFixed(3));
                    } 
                }else{
                var api = this.api(), data; 
                $(api.column(4).footer()).html('');  
                $(api.column(5).footer()).html('');  
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
    $('#tag_stone_search').click(function(event)
    {
            set_tag_stone();
    });
    function set_tag_stone()
    {
        $("div.overlay").css("display","block");
        my_Date = new Date();
        $.ajax({
        url:base_url+ "index.php/admin_ret_reports/tag_stone/ajax?nocache=" + my_Date.getUTCSeconds(),
        data: ( {'id_category':$('#tag_category').val(),'id_metal':$('#metal').val(),'id_product':$('#prod_select').val(),'id_design' :$("#des_select").val(),'id_branch':($('#branch_filter').val()!='' && $('#branch_filter').val()!=undefined ? $('#branch_filter').val(): $("#branch_select").val()),'from_weight':$('#from_weight').val(),'to_weight':$('#to_weight').val(),'id_size':$('#select_size').val()}),
        dataType:"JSON",
        type:"POST",
        success:function(data)
        {
            $("div.overlay").css("display","none");
            var list=data.list;
            console.log(list);
            var oTable=$('#tag_items_stone_list').DataTable();
            oTable.clear().draw();
            if(list!=null && list.length>0)
            {
                oTable=$('#tag_items_stone_list').dataTable({
                "bDestroy": true,
                "bInfo": true,
                "bFilter": true,
                "order": [[ 0, "asc" ]],
                "scrollX":'100%', 
                "bSort": true, 
                "dom": 'lBfrtip',
                "buttons": [
                {
                extend: 'print',
                footer: true,
                title: "Stone Available Report - "+$("#dt_range").val(),
                customize: function ( win ) 
                {
                $(win.document.body).find( 'table' )
                .addClass( 'compact' )
                .css( 'font-size', 'inherit' );
                },
                },
                {
                extend:'excel',
                footer: true,
                title: "Sales Available Report - "+$("#dt_range").val(),
                }
                ], 
                "aaData": list,
                "aoColumns":[
                {"mDataProp":"lotno"},
                {"mDataProp":"karigarname"},
                {"mDataProp":"tag_code"},
                {"mDataProp":"tag_date"},
                {"mDataProp":"branchname"},
                {"mDataProp":"catname"},
                {"mDataProp":"product_name"},
                {"mDataProp":"design_name"},
                {"mDataProp":"sub_design_name"},
                {"mDataProp":"gross_wt"},
                {"mDataProp":"net_wt"},
                {"mDataProp":"stone_name"},
                {"mDataProp":"pieces"},
                {"mDataProp":"wt"},
                {"mDataProp":"uom_name"},
                {"mDataProp":function ( row, type, val, meta )
                {
                if(row.stone_cal_type == 1)
                {
                return 'Weight';
                }
                else
                {
                return 'Piece';
                }
                
                }
                },
                
                {"mDataProp":"rate_per_gram"},
                {"mDataProp":"amount"},
                
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
    
    function get_Tagcategory($id_metal='')
    { 
        $(".overlay").css("display", "block");
        $('#tag_category option').remove();
        my_Date = new Date();
        $.ajax({
        type: 'POST',
        url: base_url+'index.php/admin_ret_reports/active_tagcategory',
        data:{'id_metal':$('#metal').val()},
        dataType:'json',
        success:function(data){ 
            $("#tag_category").append(                      
            $("<option></option>")                      
            .attr("value", 0)                                                 
            .text('All' )
            );
            $.each(data, function (key, item) {
            $('#tag_category').append(
            $("<option></option>")
            .attr("value", item.id_ret_category)
            .text(item.name)
            );
            });
            $("#tag_category").select2({
            placeholder: "Select Category",
            allowClear: true
            }); 
            $(".overlay").css("display", "none");
        }
        });
    }
    
    
    function set_acc_stock_details()
    {
        $("div.overlay").css("display","block");
        my_Date = new Date();
        $.ajax({
        url:base_url+ "index.php/admin_ret_reports/acc_stock_details/ajax?nocache=" + my_Date.getUTCSeconds(),
        data: ( {'id_stock_summary': ctrl_page[3]}),
        dataType:"JSON",
        type:"POST",
        success:function(data){
        var list=data.list;
        $("div.overlay").css("display", "none"); 
        var oTable = $('#acc_stock_details').DataTable();
        oTable.clear().draw();  
        if (list!= null && list.length > 0)
        {   
            oTable = $('#acc_stock_details').dataTable({
            "bDestroy": true,
            "bInfo": true,
            "bFilter": true,
            "bSort": true,
            "dom": 'lBfrtip',
            "buttons": [
			 {
			   extend: 'print',
			   footer: true,
			   title: "Account Stock Details",
			   customize: function ( win ) {
					$(win.document.body).find( 'table' )
						.addClass( 'compact' )
						.css( 'font-size', 'inherit' );
				},
			 },
			 {
				extend:'excel',
				footer: true,
			    title: "Account Stock Details",
			  }
			 ],
            "order": [[ 0, "asc" ]],
            "aaData": list,
            "aoColumns": [  
            { "mDataProp": "product_name" },
            { "mDataProp": "trans_type" },
            { "mDataProp": "date_add" },
            {"mDataProp":function ( row, type, val, meta )
            {
               if(row.transcation_type==1)
               {
                   if(row.debit_type==1)
                   {
                       var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+row.ref_no;
					   return '<a href='+url+' target="_blank">'+row.ref_no+'</a>';
                   }
               }
               else
               {
                   return row.ref_no;
               }
            } },
                
            { "mDataProp": "piece" },
            {"mDataProp": "gross_wt"},
            {"mDataProp": "net_wt"},
            {"mDataProp": "remarks"},
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
							total_gwt = api
							.column(4)
							.data()
							.reduce( function (a, b) {
								return intVal(a) + intVal(b);
							}, 0 );
							$(api.column(4).footer()).html(parseFloat(total_gwt).toFixed(3));
							total_nwt = api
							.column(5)
							.data()
							.reduce( function (a, b) {
								return intVal(a) + intVal(b);
							}, 0 );
							$(api.column(5).footer()).html(parseFloat(total_nwt).toFixed(3));
							
					} 
					}else{
						 var api = this.api(), data; 
						 $(api.column(3).footer()).html('');  
						 $(api.column(4).footer()).html('');  
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
    
    
    //retag report
    
    $('#retag_report_type').on('change',function(){
		$('.old_metal').css("display","none");
		$('.sales_return').css("display","none");
		$('.partly_sale').css("display","none");
		if(this.value==4)
		{
			$('.old_metal').css("display","block");
		}
		if(this.value==3)
		{
			$('.partly_sale').css("display","block");
		}
		else 
		{
			$('.sales_return').css("display","block");
		}
		get_retag_report_details();

	});
	
    $('#retag_report_search').on('click',function(){
        get_retag_report_details();
    });
    
    function get_retag_report_details()
    {
        $("div.overlay").css("display","block");
        my_Date = new Date();
        $.ajax({
        url:base_url+ "index.php/admin_ret_reports/retag_report/ajax?nocache=" + my_Date.getUTCSeconds(),
        data: ({'from_date':$('#rpt_payments1').html(),'to_date':$('#rpt_payments2').html(),'retag_report_type':$('#retag_report_type').val()}),
        dataType:"JSON",
        type:"POST",
        success:function(data){
        var list=data.list;
		if($('#retag_report_type').val()==4 )
		{
			$("div.overlay").css("display", "none"); 
			var oTable = $('#retag_list').DataTable();
			oTable.clear().draw();  
			if (list!= null && list.length > 0)
			{   
				oTable = $('#retag_list').dataTable({
				"bDestroy": true,
				"bInfo": true,
				"bFilter": true,
				"bSort": true,
				"order": [[ 0, "asc" ]],
				"dom": 'lBfrtip',
				"buttons": [
				{
				extend: 'print',
				footer: true,
				title: "Re-Tagging Report",
				customize: function ( win ) {
						$(win.document.body).find( 'table' )
							.addClass( 'compact' )
							.css( 'font-size', 'inherit' );
					},
				},
				{
					extend:'excel',
					footer: true,
					title: "ReTagging Report",
				}
				],
				"aaData": list,
				"aoColumns": [  
				{ "mDataProp": "id_process" },
				{ "mDataProp": "date_add" },
				{ "mDataProp": "type" },
				{ "mDataProp": "process_for" },
				{"mDataProp": "product_name"},
				{"mDataProp": "weight"},
				{"mDataProp": "net_weight"},
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
								i : 0;
							};	
							
							$(api.column(0).footer() ).html('Total');	
							gross_wgt = api
							.column(5)
							.data()
							.reduce( function (a, b) {
								return intVal(a) + intVal(b);
							}, 0 );
							$(api.column(5).footer()).html(parseFloat(gross_wgt).toFixed(3));
							
							net_wgt = api
							.column(6)
							.data()
							.reduce( function (a, b) {
								return intVal(a) + intVal(b);
							}, 0 );
							$(api.column(6).footer()).html(parseFloat(net_wgt).toFixed(3));
							
					} 
					}else{
						var api = this.api(), data; 
						$(api.column(5).footer()).html('');
					}
				}
				}); 
			}
			$("div.overlay").css("display", "none");
		}
		else if($('#retag_report_type').val()==3 )
		{
			$("div.overlay").css("display", "none"); 
			var oTable = $('#partly_sale_list').DataTable();
			oTable.clear().draw();  
			if (list!= null && list.length > 0)
			{   
				oTable = $('#partly_sale_list').dataTable({
				"bDestroy": true,
				"bInfo": true,
				"bFilter": true,
				"bSort": true,
				"order": [[ 0, "asc" ]],
				"dom": 'lBfrtip',
				"buttons": [
				{
				extend: 'print',
				footer: true,
				title: "Re-Tagging Report",
				customize: function ( win ) {
						$(win.document.body).find( 'table' )
							.addClass( 'compact' )
							.css( 'font-size', 'inherit' );
					},
				},
				{
					extend:'excel',
					footer: true,
					title: "ReTagging Report",
				}
				],
				"aaData": list,
				"aoColumns": [  
				{ "mDataProp": "id_process" },
				{ "mDataProp": "date_add" },
				{ "mDataProp": "type" },
				{ "mDataProp": "process_for" },
				{"mDataProp": "tag_code"},
				{"mDataProp": "product_name"},
				{"mDataProp": "weight"},
				{"mDataProp": "net_weight"},
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
								i : 0;
							};	
							
							$(api.column(0).footer() ).html('Total');	
							gross_wgt = api
							.column(6)
							.data()
							.reduce( function (a, b) {
								return intVal(a) + intVal(b);
							}, 0 );
							$(api.column(6).footer()).html(parseFloat(gross_wgt).toFixed(3));
							
							net_wgt = api
							.column(7)
							.data()
							.reduce( function (a, b) {
								return intVal(a) + intVal(b);
							}, 0 );
							$(api.column(7).footer()).html(parseFloat(net_wgt).toFixed(3));
							
					} 
					}else{
						var api = this.api(), data; 
						$(api.column(5).footer()).html('');
					}
				}
				}); 
			}
			$("div.overlay").css("display", "none");
		}
		else
		{
			$("div.overlay").css("display", "none"); 
			var oTable = $('#salesreturn_retag_list').DataTable();
			oTable.clear().draw();  
			if (list!= null && list.length > 0)
			{   
				oTable = $('#salesreturn_retag_list').dataTable({
				"bDestroy": true,
				"bInfo": true,
				"bFilter": true,
				"bSort": true,
				"order": [[ 0, "asc" ]],
				"dom": 'lBfrtip',
				"buttons": [
				 {
				   extend: 'print',
				   footer: true,
				   title: "Re-Tagging Report",
				   customize: function ( win ) {
						$(win.document.body).find( 'table' )
							.addClass( 'compact' )
							.css( 'font-size', 'inherit' );
					},
				 },
				 {
					extend:'excel',
					footer: true,
					title: "ReTagging Report",
				  }
				 ],
				"aaData": list,
				"aoColumns": [  
				{ "mDataProp": "id_process" },
				{ "mDataProp": "branch" },
				{ "mDataProp": function ( row, type, val, meta ){
					var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+row.bill_id;
					return '<a href='+url+' target="_blank">'+row.bill_no+'</a>';
				}
				},				
				{ "mDataProp": "bill_date" },
				{ "mDataProp": "tag_no" },
				{"mDataProp" : "product"},
				{ "mDataProp": "date_add" },
				{ "mDataProp": "new_tag" },
				{"mDataProp" : "gross_wt"},
				{"mDataProp" : "net_wt"},
				{ "mDataProp": "type" },
				{ "mDataProp": "process_for" },
				
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
								i : 0;
							};	
							
							$(api.column(0).footer() ).html('Total');	
							gross_wgt = api
							.column(8)
							.data()
							.reduce( function (a, b) {
								return intVal(a) + intVal(b);
							}, 0 );
							$(api.column(8).footer()).html(parseFloat(gross_wgt).toFixed(3));
							
							net_wgt = api
							.column(9)
							.data()
							.reduce( function (a, b) {
								return intVal(a) + intVal(b);
							}, 0 );
							$(api.column(9).footer()).html(parseFloat(net_wgt).toFixed(3));
							
					} 
					}else{
						 var api = this.api(), data; 
						 $(api.column(8).footer()).html('');
					}
				}
				}); 
			}
			$("div.overlay").css("display", "none");
		}
        },
        error:function(error)  
        {
        $("div.overlay").css("display", "none"); 
        }    
        });
    }
    //retag report
    
    
    
    //purchase item wise
    
    $('#purchase_item_wise_search').on('click',function(){
        get_purchase_itemwise();
    });
    
    function get_purchase_itemwise()
    {
        var title="<b><span style='font-size:15pt;margin-left:30%;'>"+company_name+"</span></b><b><span style='font-size:12pt;'></span></b></br>"+"<span style=font-size:13pt;margin-left:25%;>&nbsp;&nbsp;BILL WISE DETAILED REPORT</span>";
        $("div.overlay").css("display","block");
        my_Date = new Date();
        $.ajax({
        url:base_url+ "index.php/admin_ret_reports/purchase_itemwise/ajax?nocache=" + my_Date.getUTCSeconds(),
        data: ( {'from_date': $('#rpt_payments1').html(),'to_date':$('#rpt_payments2').html(),'id_karigar':$('#karigar').val(),'id_product':$('#prod_select').val(),'id_design':$('#des_select').val(),'id_sub_design':$('#sub_des_select').val()}),
        dataType:"JSON",
        type:"POST",
        success:function(data){
        var list=data.list;
        $("div.overlay").css("display", "none"); 
        var oTable = $('#item_wise_list').DataTable();
        oTable.clear().draw();  
        if (list!= null)
        {   
                var trHtml='';
                $("#item_wise_list > tbody > tr").remove();  
    	 	    $('#item_wise_list').dataTable().fnClearTable();
        	    $('#item_wise_list').dataTable().fnDestroy();
        	    
        	    var grand_total_pcs=0;
                var grand_total_gross_wt=0;
                var grand_total_less_wt=0;
                var grand_total_net_wt=0;
                var grand_total_item_cost=0;
                
               $.each(list,function(key,po_details){
                   trHtml+='<tr style="font-weight:bold;">'
                                +'<td>'+key+'</td>'
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
                var total_pcs=0;
                var total_gross_wt=0;
                var total_less_wt=0;
                var total_net_wt=0;
                var total_item_cost=0;
                          $.each(po_details,function(k,val){
                              total_pcs+=parseFloat(val.no_of_pcs);
                              total_gross_wt+=parseFloat(val.gross_wt);
                              total_less_wt+=parseFloat(val.less_wt);
                              total_net_wt+=parseFloat(val.net_wt);
                              total_item_cost+=parseFloat(val.item_cost);
                              
                              grand_total_pcs+=parseFloat(val.no_of_pcs);
                              grand_total_gross_wt+=parseFloat(val.gross_wt);
                              grand_total_less_wt+=parseFloat(val.less_wt);
                              grand_total_net_wt+=parseFloat(val.net_wt);
                              grand_total_item_cost+=parseFloat(val.item_cost);
                              
                             if(val.po_stone_amount>0)
                             {
                                action_content= '<a href=# class="" onclick="po_stone_details('+val.po_item_id+');">'+val.po_stone_amount+'</a>';
                             }else
                             {
                                 action_content=val.po_stone_amount;
                             }
                              trHtml+='<tr>'
                                +'<td>'+val.product_name+'</td>'
                                +'<td>'+val.design_name+'</td>'
                                +'<td>'+val.sub_design_name+'</td>'
                                +'<td>'+val.no_of_pcs+'</td>'
                                +'<td>'+val.gross_wt+'</td>'
                                +'<td>'+val.less_wt+'</td>'
                                +'<td>'+val.net_wt+'</td>'
                                +'<td>'+val.item_wastage+'</td>'
                                +'<td>'+val.mc_value+'</td>'
                                +'<td>'+val.fix_rate_per_grm+'</td>'
                                +'<td>'+action_content+'</td>'
                                +'<td>'+val.item_cost+'</td>'
                           +'</tr>';
                          });
                          
                          trHtml+='<tr style="font-weight:bold;">'
                                +'<td>SUB TOTAL</td>'
                                +'<td></td>'
                                +'<td></td>'
                                +'<td>'+total_pcs+'</td>'
                                +'<td>'+parseFloat(total_gross_wt).toFixed(3)+'</td>'
                                +'<td>'+parseFloat(total_less_wt).toFixed(3)+'</td>'
                                +'<td>'+parseFloat(total_net_wt).toFixed(3)+'</td>'
                                +'<td></td>'
                                +'<td></td>'
                                +'<td></td>'
                                +'<td></td>'
                                +'<td>'+parseFloat(total_item_cost).toFixed(3)+'</td>'
                           +'</tr>';
                           
               });
               
                trHtml+='<tr style="font-weight:bold;">'
                                +'<td>GRAND TOTAL</td>'
                                +'<td></td>'
                                +'<td></td>'
                                +'<td>'+grand_total_pcs+'</td>'
                                +'<td>'+parseFloat(grand_total_gross_wt).toFixed(3)+'</td>'
                                +'<td>'+parseFloat(grand_total_less_wt).toFixed(3)+'</td>'
                                +'<td>'+parseFloat(grand_total_net_wt).toFixed(3)+'</td>'
                                +'<td></td>'
                                +'<td></td>'
                                +'<td></td>'
                                +'<td></td>'
                                +'<td>'+parseFloat(grand_total_item_cost).toFixed(3)+'</td>'
                           +'</tr>';
              
              $('#item_wise_list > tbody').html(trHtml);
			     // Check and initialise datatable
			     
				if ( ! $.fn.DataTable.isDataTable( '#item_wise_list' ) ) { 
					oTable = $('#item_wise_list').dataTable({ 
					"bSort": false, 
					"bInfo": true, 
					"scrollX":'100%',  
					"dom": 'lBfrtip',
					"paging":false,
                    "fixedHeader": true,
        			"buttons": [
        			
					{
						extend: 'print',
						footer: true,
						title: 'Bill Wise Detailed Report',
						exportOptions: {
                            columns: ':visible'
                        },
						customize: function ( win ) {
						$(win.document.body).find( 'table' )
						.addClass( 'compact' )
				    	.css('font-size','12px')
						.css('font-family','sans-serif');
						},
					},
					'colvis',
					
					{
						extend:'excel',
						footer: true,
						title: 'Bill Wise Detailed Report',
					}
					], 
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
    
    function po_stone_details(po_item_id)
    {
        $('#stone_modal').modal('toggle');
        $('#stone_modal #stone_details > tbody').empty();
        my_Date = new Date();
        $.ajax({
        type: 'POST',
        url: base_url+'index.php/admin_ret_reports/purchase_itemwise/stn_details',
        data:{'po_item_id':po_item_id},
        dataType:'json',
        success:function(data){ 
            var trHtml='';
            $.each(data,function(key,items){
                trHtml+='<tr>'
                        +'<td>'+items.stone_name+'</td>'
                        +'<td>'+items.po_stone_pcs+'</td>'
                        +'<td>'+items.po_stone_wt+'</td>'
                        +'<td>'+items.po_stone_rate+'</td>'
                        +'<td>'+items.po_stone_amount+'</td>'
                        +'</tr>';
            });
            $('#stone_modal #stone_details > tbody').append(trHtml);
        }
        });
    }
    
    //purchase item wise
//sales import
$('#sales_import_search').on('click',function(){
    get_sales_import();
});
function get_sales_import()
{
    $("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/sales_import/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: {'from_date':$('#rpt_payments1').html(),'to_date':$('#rpt_payments2').html(),'id_branch':$('#branch_select').val()},
			 dataType:"JSON",
			 type:"POST",
			 success:function(data)
			 {
			        var list = data.list;
    				var oTable = $('#sales_list').DataTable();
    				oTable.clear().draw();				  
    				if (list!= null && list.length > 0)
    				{  	
    					oTable = $('#sales_list').dataTable({
    						"bDestroy": true,
    		                "bInfo": true,
    		                "bFilter": true,
    		                "scrollX":'100%',
    		                "bSort": true,
    		                "dom": 'lBfrtip',
    		                "buttons": [
    								 {
    								   extend: 'print',
    								   footer: true,
    								   title: "SALES IMPORT",
    								   customize: function ( win ) {
    										$(win.document.body).find( 'table' )
    											.addClass( 'compact' )
    											.css( 'font-size', 'inherit' );
    									},
    								 },
    								 {
    									extend:'excel',
    									footer: true,
    								    title: "SALES IMPORT",
    								  }
    								 ],
    						"aaData": list,
    						"aoColumns": [	
    										{ "mDataProp": "INVOICEDATE" },
    										{ "mDataProp": "INVOICENO" },
    										{ "mDataProp": "VOUCHERTYPE" },
    										{ "mDataProp": "PARTYCODE" },
    										{ "mDataProp": "PARTYNAME" },
    										{ "mDataProp": "PARTYGROUP" },
    										{ "mDataProp": "GSTNO" },
    										{ "mDataProp": "ADRESS1" },
    										{ "mDataProp": "ADRESS2" },
    										{ "mDataProp": "ADRESS3" },
    										{ "mDataProp": "CONTACTNO" },
    										{ "mDataProp": "PRODUCTGROUP" },
    										{ "mDataProp": "PRODUCTCODE" },
    										{ "mDataProp": "PRODUCTNAME" },
    										{ "mDataProp": "QTY" },
    										{ "mDataProp": "RATE" },
    										{ "mDataProp": "VALUE" },
    										{ "mDataProp": "SALESTAX" },
    										{ "mDataProp": "SALESTAXAMT" },
    										{ "mDataProp": "SERVICEAMT" },
    										{ "mDataProp": "WEIGHT" },
    										{ "mDataProp": "TOTAL" },
    										{ "mDataProp": "REMARKS" },
    										{ "mDataProp": "GROSSWT" },
    										{ "mDataProp": "NETWT" },
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
//sales import
//purchase import
$('#purchase_import_search').on('click',function(){
    get_purchase_import();
});
function get_purchase_import()
{
    $("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/purchase_import/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: {'from_date':$('#rpt_payments1').html(),'to_date':$('#rpt_payments2').html(),'id_branch':$('#branch_select').val()},
			 dataType:"JSON",
			 type:"POST",
			 success:function(data)
			 {
			        var list = data.list;
    				var oTable = $('#purchase_list').DataTable();
    				oTable.clear().draw();				  
    				if (list!= null && list.length > 0)
    				{  	
    					oTable = $('#purchase_list').dataTable({
    						"bDestroy": true,
    		                "bInfo": true,
    		                "bFilter": true,
    		                "scrollX":'100%',
    		                "bSort": true,
    		                "dom": 'lBfrtip',
    		                "buttons": [
    								 {
    								   extend: 'print',
    								   footer: true,
    								   title: "PURCHASE IMPORT",
    								   customize: function ( win ) {
    										$(win.document.body).find( 'table' )
    											.addClass( 'compact' )
    											.css( 'font-size', 'inherit' );
    									},
    								 },
    								 {
    									extend:'excel',
    									footer: true,
    								    title: "PURCHASE IMPORT",
    								  }
    								 ],
    						"aaData": list,
    						"aoColumns": [	
    										{ "mDataProp": "INVOICEDATE" },
    										{ "mDataProp": "INVOICENO" },
    										{ "mDataProp": "VOUCHERTYPE" },
    										{ "mDataProp": "PARTYCODE" },
    										{ "mDataProp": "PARTYNAME" },
    										{ "mDataProp": "PARTYGROUP" },
    										{ "mDataProp": "GSTNO" },
    										{ "mDataProp": "ADRESS1" },
    										{ "mDataProp": "ADRESS2" },
    										{ "mDataProp": "ADRESS3" },
    										{ "mDataProp": "CONTACTNO" },
    										{ "mDataProp": "PRODUCTGROUP" },
    										{ "mDataProp": "PRODUCTCODE" },
    										{ "mDataProp": "PRODUCTNAME" },
    										{ "mDataProp": "QTY" },
    										{ "mDataProp": "RATE" },
    										{ "mDataProp": "VALUE" },
    										{ "mDataProp": "SALESTAX" },
    										{ "mDataProp": "SALESTAXAMT" },
    										{ "mDataProp": "WEIGHT" },
    										{ "mDataProp": "TOTAL" },
    										{ "mDataProp": "REMARKS" },
    										{ "mDataProp": "GRSWT" },
    										{ "mDataProp": "NETWT" },
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
//purchase import
//payment mode import
$('#payment_mode_import_search').on('click',function(){
    get_payment_mode_import();
});
function get_payment_mode_import()
{
    $("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/payment_mode_import/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: {'from_date':$('#rpt_payments1').html(),'to_date':$('#rpt_payments2').html(),'id_branch':$('#branch_select').val()},
			 dataType:"JSON",
			 type:"POST",
			 success:function(data)
			 {
			        var list = data.list;
    				var oTable = $('#pay_mode_list').DataTable();
    				oTable.clear().draw();				  
    				if (list!= null && list.length > 0)
    				{  	
    					oTable = $('#pay_mode_list').dataTable({
    						"bDestroy": true,
    		                "bInfo": true,
    		                "bFilter": true,
    		                "scrollX":'100%',
    		                "bSort": true,
    		                "dom": 'lBfrtip',
    		                "buttons": [
    								 {
    								   extend: 'print',
    								   footer: true,
    								   title: "PAYMENT MODE IMPORT",
    								   customize: function ( win ) {
    										$(win.document.body).find( 'table' )
    											.addClass( 'compact' )
    											.css( 'font-size', 'inherit' );
    									},
    								 },
    								 {
    									extend:'excel',
    									footer: true,
    								    title: "PAYMENT MODE IMPORT",
    								  }
    								 ],
    						"aaData": list,
    						"aoColumns": [	
    										{ "mDataProp": "bill_date" },
    										{ "mDataProp": "bill_id" },
    										{ "mDataProp": "PARTICULAR" },
    										{ "mDataProp": "GRSWT" },
    										{ "mDataProp": "AMOUNT" },
    										{ "mDataProp": "GST" },
    										{ "mDataProp": "CASH" },
    										{ "mDataProp": "CHEQUE" },
    										{ "mDataProp": "card_amt" },
    										{ "mDataProp": "SCHEME" },
    										{ "mDataProp": "SCH_BONUS" },
    										{ "mDataProp": "SCH_PRIZE" },
    										{ "mDataProp": "DISCOUNT" },
    										{ "mDataProp": "ADVANCE" },
    										{ "mDataProp": "DUE" },
    										{ "mDataProp": "OTHERS" },
    										{ "mDataProp": "sales_return" },
    										{ "mDataProp": "gold_pur" },
    										{ "mDataProp": "sil_pur" },
    										{ "mDataProp": "plat_pur" },
    										{ "mDataProp": "dia_pur" },
    										{ "mDataProp": "stn_pur" },
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
//payment mode import
//stock rotation
$('#stock_rotation_search').on('click',function(){
    get_stock_rotation_list();
});
function get_stock_rotation_list()
{
    $("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/stock_rotation/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: {'from_date':$('#rpt_payments1').html(),'to_date':$('#rpt_payments2').html(),'id_branch':$('#branch_select').val(),'id_product':$('#prod_select').val()},
			 dataType:"JSON",
			 type:"POST",
			 success:function(data)
			 {
			        var list = data.list;
    				var oTable = $('#item_list').DataTable();
    				oTable.clear().draw();				  
    				if (list!= null && list.length > 0)
    				{  	
    					oTable = $('#item_list').dataTable({
    						"bDestroy": true,
    		                "bInfo": true,
    		                "bFilter": true,
    		                "scrollX":'100%',
    		                "bSort": true,
    		                "dom": 'lBfrtip',
    		                "buttons": [
    								 {
    								   extend: 'print',
    								   footer: true,
    								   title: "PURCHASE IMPORT",
    								   customize: function ( win ) {
    										$(win.document.body).find( 'table' )
    											.addClass( 'compact' )
    											.css( 'font-size', 'inherit' );
    									},
    								 },
    								 {
    									extend:'excel',
    									footer: true,
    								    title: "PURCHASE IMPORT",
    								  }
    								 ],
    						"aaData": list,
    						"aoColumns": [	
    										{ "mDataProp": "product_name" },
    										{ "mDataProp": "design_name" },
    										{ "mDataProp": "sub_design_name" },
    										{ "mDataProp": "weight_description" },
    										{ "mDataProp": "sales_pcs" },
    										{ "mDataProp": "sales_gwt" },
    										
    										{ "mDataProp": function ( row, type, val, meta ){
                                                 return parseFloat(row.avg_stock_wt).toFixed(3);
                            
                                            }},
                                            
                                            { "mDataProp": function ( row, type, val, meta ){
                                                 return parseFloat(row.avg_stock_pcs).toFixed(3);
                            
                                            }},
    									    
    									    { "mDataProp": function ( row, type, val, meta ){
                                                 return parseFloat(row.no_of_rotation_wt).toFixed(3);
                            
                                            }},
    									    
    									    { "mDataProp": "no_of_rotation" },
    										
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
function oldget_stock_rotation_list()
{
    $("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/stock_rotation/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: {'from_date':$('#rpt_payments1').html(),'to_date':$('#rpt_payments2').html(),'id_branch':$('#branch_select').val(),'id_product':$('#prod_select').val()},
			 dataType:"JSON",
			 type:"POST",
			 success:function(data)
			 {
			        var list = data.list;
    				var oTable = $('#item_list').DataTable();
    				oTable.clear().draw();				  
    				if (list!= null && list.length > 0)
    				{  	
    					oTable = $('#item_list').dataTable({
    						"bDestroy": true,
    		                "bInfo": true,
    		                "bFilter": true,
    		                "scrollX":'100%',
    		                "bSort": true,
    		                "dom": 'lBfrtip',
    		                "buttons": [
    								 {
    								   extend: 'print',
    								   footer: true,
    								   title: "PURCHASE IMPORT",
    								   customize: function ( win ) {
    										$(win.document.body).find( 'table' )
    											.addClass( 'compact' )
    											.css( 'font-size', 'inherit' );
    									},
    								 },
    								 {
    									extend:'excel',
    									footer: true,
    								    title: "PURCHASE IMPORT",
    								  }
    								 ],
    						"aaData": list,
    						"aoColumns": [	
    										{ "mDataProp": "product_name" },
    										{ "mDataProp": "design_name" },
    										{ "mDataProp": "sub_design_name" },
    										{ "mDataProp": "weight_description" },
    										{ "mDataProp": "sales_pcs" },
    										{ "mDataProp": "sales_gwt" },
    									
    										
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
//stock rotation
//ACCOUNTS REPORTS
//Gst Abstract
$('#gst_abstract_search').on('click',function(){
	    get_gst_abstract_details();
});

$('#day_trans_search').on('click',function(){
	    getdaytransactions_details();
});
/*function get_gst_abstract_details()
{
   
    var branch_name=($('#branch_name').val()!='' && $('#branch_name').val()!=undefined ? $('#branch_name').val() : $('#branch_select option:selected').toArray().map(item => item.text).join());
   
    var company_code=$('#company_code').val();
    var company_address1=$('#company_address1').val();
    var company_address2=$('#company_address2').val();
    var company_city=$('#company_city').val();
    var pincode=$('#pincode').val();
    var company_email=$('#company_email').val();
    var company_gst_number=$('#company_gst_number').val();
    var phone=$('#phone').val();
    var report_type=$('#sale_ret_filter').find(':selected').text();
    
    var title="<div style='text-align: center;'><b><span style='font-size:12pt;'>"+company_code+"</span></b></br>"
    +"<span style='font-size:11pt;'>"+company_address1+"</span></br>"
    +"<span style='font-size:11pt;'>"+company_address2 + company_city+"-"+pincode+"</span></br>"
    +"<span style='font-size:11pt;'>GSTIN:"+company_gst_number +", EMAIL:"+ company_email+"</span></br>"
    +"<span style='font-size:11pt;'>Contact :"+phone +"</span></br>"
    +"<span style=font-size:12pt;>&nbsp;&nbsp;Sales Abstract GST Report - "+report_type + " - " +branch_name+" &nbsp;From&nbsp;:&nbsp;"+$('#rpt_from_date').html()+" &nbsp;&nbsp;- to "+$('#rpt_to_date').html() + " - " + $('.hidden-xs').html()+ "</span></div>";
    $("div.overlay").css("display", "block");
    my_Date = new Date();
    $.ajax({
    url:base_url+ "index.php/admin_ret_reports/gst_abstract/ajax?nocache=" + my_Date.getUTCSeconds(),
    dataType:"JSON",
    data:{'report':$('#report_format').val(),'group_by':$('#gst_group_by').val(),'id_branch':$('#branch_select').val(),'from_date':$('#rpt_from_date').html(),'to_date' :$('#rpt_to_date').html(),'report_type':$('#report_type').val(),'gst_filter':$('#gst_filter').val(),'sale_ret_filter':$('#sale_ret_filter').val()},
    type:"POST",
    success:function(data)
    {
            var company_bills=data.list.B2B;
            var customer_bills=data.list.B2C;
            var sales_transfer=data.list.sales_transfer;
            var repair=data.list.repair;
            var overseas = data.list.overseas;
            
            var cmp_round_off=0;
            var cus_round_off=0;
            var rep_round_off=0;
            var overseas_round_off=0;
    
           var trHTML='';
           $("#gst_abstract_list > tbody > tr").remove();
           $('#gst_abstract_list').dataTable().fnClearTable();
               $('#gst_abstract_list').dataTable().fnDestroy();
               
               var cmp_pcs=0;
               var cmp_gwt=0;
               var cmp_nwt=0;
               var cmp_sgst=0;
               var cmp_cgst=0;
               var cmp_igst=0;
               var cmp_gst=0;
               var cmp_total_amt=0;
               var cmp_taxable_amt=0;
               
               var cus_pcs=0;
               var cus_gwt=0;
               var cus_nwt=0;
               var cus_sgst=0;
               var cus_cgst=0;
               var cus_igst=0;
               var cus_gst=0;
               var cus_total_amt=0;
               var cus_taxable_amt=0;
    
                var rep_pcs=0;
                var rep_gwt=0;
                var rep_taxable=0;
                var rep_sgst=0;
                var rep_cgst=0;
                var rep_igst=0;
                var rep_tax=0;
                var rep_tot_amount=0;
                
                var overseas_pcs=0;
                var overseas_gwt=0;
                var overseas_taxable=0;
                var overseas_sgst=0;
                var overseas_cgst=0;
                var overseas_igst=0;
                var overseas_tax=0;
                var overseas_tot_amount=0;
				var st_pcs=0;
                var st_gwt=0;
                var st_taxable=0;
                var st_sgst=0;
                var st_cgst=0;
                var st_igst=0;
                var st_tax=0;
                var st_tot_amount=0;
				var st_round_off=0;
    
               
           if(company_bills.length>0)
           {
               trHTML+='<tr>'
                       +'<td><strong>B2B BILLS</strong></td>'
                       +'<td></td>'
                       +'<td></td>'
                       +'<td></td>'
                       +'<td></td>'
                       +'<td></td>'
                       // +'<td></td>'s
                       +'<td></td>'
                       +'<td></td>'
                       +'<td></td>'
                       +'<td></td>'
                       +'<td></td>'
                       +'</tr>';
               $.each(company_bills,function(key,items){
                   cmp_pcs+=parseFloat(items.piece);
                   cmp_gwt+=parseFloat(items.gross_wt);
                   cmp_nwt+=parseFloat(items.net_wt);
                   cmp_taxable_amt+=parseFloat(items.taxable_amt);
                   cmp_sgst+=parseFloat(items.total_sgst);
                   cmp_cgst+=parseFloat(items.total_cgst);
                   cmp_igst+=parseFloat(items.total_igst);
                   cmp_gst+=parseFloat(items.tax_amt);
                   cmp_total_amt+=parseFloat(items.total_amount);
                   cmp_round_off+=parseFloat(items.round_off);
                   trHTML+='<tr>'
                    +'<td><strong>'+items.category_name+'</strong></td>'
                       +'<td>'+($('#report_format').val() == 1 ? (items.starting_bill+' - '+items.ending_bill) : items.sales_ref_no)+'</td>'
                       +'<td>'+items.piece+'</td>'
                       +'<td>'+items.gross_wt+'</td>'
                       +'<td>'+items.net_wt+'</td>'
                       +'<td>'+items.taxable_amt+'</td>'
                       +'<td>'+items.total_sgst+'</td>'
                       +'<td>'+items.total_cgst+'</td>'
                       +'<td>'+items.total_igst+'</td>'
                       +'<td>'+items.tax_amt+'</td>'
                       +'<td>'+parseFloat(items.round_off).toFixed(2)+'</td>'
                       +'<td>'+items.total_amount+'</td>'
                       +'</tr>';
               });
               
               trHTML+='<tr>'
                       +'<td><strong>SUB TOTAL</strong></td>'
                       +'<td></td>'
                       +'<td><strong>'+cmp_pcs+'</td>'
                       +'<td><strong>'+parseFloat(cmp_gwt).toFixed(3)+'</strong></td>'
                       +'<td><strong>'+parseFloat(cmp_nwt).toFixed(3)+'</strong></td>'
                       +'<td><strong>'+parseFloat(cmp_taxable_amt).toFixed(2)+'</strong></td>'
                       +'<td><strong>'+parseFloat(cmp_sgst).toFixed(2)+'</strong></td>'
                       +'<td><strong>'+parseFloat(cmp_cgst).toFixed(2)+'</strong></td>'
                       +'<td><strong>'+parseFloat(cmp_igst).toFixed(2)+'</strong></td>'
                       +'<td><strong>'+parseFloat(cmp_gst).toFixed(2)+'</strong></td>'
                       +'<td><strong>'+parseFloat(cmp_round_off).toFixed(2)+'</strong></td>'
                       +'<td><strong>'+parseFloat(cmp_total_amt).toFixed(2)+'</strong></td>'
                +'</tr>';
           }
           
           if(customer_bills.length>0)
           {
               trHTML+='<tr>'
                       +'<td><strong>B2C BILLS</strong></td>'
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
               $.each(customer_bills,function(key,items){
                   cus_pcs+=parseFloat(items.piece);
                   cus_gwt+=parseFloat(items.gross_wt);
                   cus_nwt+=parseFloat(items.net_wt);
                   cus_taxable_amt+=parseFloat(items.taxable_amt);
                   cus_sgst+=parseFloat(items.total_sgst);
                   cus_cgst+=parseFloat(items.total_cgst);
                   cus_igst+=parseFloat(items.total_igst);
                   cus_gst+=parseFloat(items.tax_amt);
                   cus_total_amt+=parseFloat(items.total_amount);
                   cus_round_off+=parseFloat(items.round_off);
                   trHTML+='<tr>'
                    +'<td><strong>'+items.category_name+'</strong></td>'
                        +'<td>'+( $('#report_format').val() == 1 ? (items.starting_bill+' - '+items.ending_bill) : items.sales_ref_no)+'</td>'
                       +'<td>'+items.piece+'</td>'
                       +'<td>'+items.gross_wt+'</td>'
                       +'<td>'+items.net_wt+'</td>'
                       +'<td>'+items.taxable_amt+'</td>'
                       +'<td>'+items.total_sgst+'</td>'
                       +'<td>'+items.total_cgst+'</td>'
                       +'<td>'+items.total_igst+'</td>'
                       +'<td>'+items.tax_amt+'</td>'
                       +'<td>'+parseFloat(items.round_off).toFixed(2)+'</td>'
                       +'<td>'+items.total_amount+'</td>'
                       +'</tr>';
               });
               
               trHTML+='<tr>'
                       +'<td><strong>SUB TOTAL</strong></td>'
                       +'<td></td>'
                       +'<td><strong>'+cus_pcs+'</strong></td>'
                       +'<td><strong>'+parseFloat(cus_gwt).toFixed(3)+'</strong></td>'
                       +'<td><strong>'+parseFloat(cus_nwt).toFixed(3)+'</strong></td>'
                       +'<td><strong>'+parseFloat(cus_taxable_amt).toFixed(2)+'</strong></td>'
                       +'<td><strong>'+parseFloat(cus_sgst).toFixed(2)+'</strong></td>'
                       +'<td><strong>'+parseFloat(cus_cgst).toFixed(2)+'</strong></td>'
                       +'<td><strong>'+parseFloat(cus_igst).toFixed(2)+'</strong></td>'
                       +'<td><strong>'+parseFloat(cus_gst).toFixed(2)+'</strong></td>'
                       +'<td><strong>'+parseFloat(cus_round_off).toFixed(2)+'</strong></td>'
                       +'<td><strong>'+parseFloat(cus_total_amt).toFixed(2)+'</strong></td>'
                +'</tr>';
           }
		   if(overseas.length>0)
                {
                    trHTML+='<tr>'
                    +'<td><strong>OVERSEAS BILLS</strong></td>'
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
                    $.each(overseas,function(key,items){
                    overseas_pcs+=parseFloat(items.piece);
                    overseas_gwt+=parseFloat(items.gross_wt);
                    overseas_taxable+=parseFloat(items.taxable_amt);
                    overseas_sgst+=parseFloat(items.total_sgst);
                    overseas_cgst+=parseFloat(items.total_cgst);
                    overseas_igst+=parseFloat(items.total_igst);
                    overseas_tax+=parseFloat(items.tax_amt);
                    overseas_tot_amount+=parseFloat(items.total_amount);
                    overseas_round_off+=parseFloat(items.round_off);
                    trHTML+='<tr>'
                     +'<td>'+items.category_name+'</td>'
                    +'<td>'+( $('#report_format').val() == 1 ? (items.starting_bill+' - '+items.ending_bill) : items.sales_ref_no)+'</td>'
                    +'<td>'+items.piece+'</td>'
                    +'<td>'+items.gross_wt+'</td>'
                    +'<td></td>'
                    +'<td>'+items.taxable_amt+'</td>'
                    +'<td>'+items.total_sgst+'</td>'
                    +'<td>'+items.total_cgst+'</td>'
                    +'<td>'+items.total_igst+'</td>'
                    +'<td>'+items.tax_amt+'</td>'
                    +'<td>'+parseFloat(items.round_off).toFixed(2)+'</td>'
                    +'<td>'+items.total_amount+'</td>'
                    +'</tr>';
                    });
                    trHTML+='<tr>'
                    +'<td><strong>SUB TOTAL</strong></td>'
                    +'<td></td>'
                    +'<td><strong>'+overseas_pcs+'</strong></td>'
                    +'<td><strong>'+parseFloat(overseas_gwt).toFixed(3)+'</strong></td>'
                    +'<td><strong></strong></td>'
                    +'<td><strong>'+parseFloat(overseas_taxable).toFixed(2)+'</strong></td>'
                    +'<td><strong>'+parseFloat(overseas_sgst).toFixed(2)+'</strong></td>'
                    +'<td><strong>'+parseFloat(overseas_cgst).toFixed(2)+'</strong></td>'
                    +'<td><strong>'+parseFloat(overseas_igst).toFixed(2)+'</strong></td>'
                    +'<td><strong>'+parseFloat(overseas_tax).toFixed(2)+'</strong></td>'
                    +'<td><strong>'+parseFloat(overseas_round_off).toFixed(2)+'</strong></td>'
                    +'<td><strong>'+parseFloat(overseas_tot_amount).toFixed(2)+'</strong></td>'
                     +'</tr>';
                }
			if(customer_bills.length>0 || company_bills.length>0 || overseas.length>0){
				trHTML+='<tr>'
				+'<td><strong>GRAND TOTAL</strong></td>'
				+'<td></td>'
				+'<td><strong>'+parseFloat(cus_pcs+cmp_pcs+overseas_pcs)+'</strong></td>'
				+'<td><strong>'+parseFloat(cus_gwt+cmp_gwt+overseas_gwt).toFixed(3)+'</strong></td>'
				+'<td><strong>'+parseFloat(cus_nwt+cmp_nwt).toFixed(3)+'</strong></td>'
				+'<td><strong>'+parseFloat(cus_taxable_amt+cmp_taxable_amt+overseas_taxable).toFixed(2)+'</strong></td>'
				+'<td><strong>'+parseFloat(cus_sgst+cmp_sgst+overseas_sgst).toFixed(2)+'</strong></td>'
				+'<td><strong>'+parseFloat(cus_cgst+cmp_cgst+overseas_cgst).toFixed(2)+'</strong></td>'
				+'<td><strong>'+parseFloat(cus_igst+cmp_igst+overseas_igst).toFixed(2)+'</strong></td>'
				+'<td><strong>'+parseFloat(cus_gst+cmp_gst+overseas_tax).toFixed(2)+'</strong></td>'
				+'<td><strong>'+parseFloat(cus_round_off+cmp_round_off+overseas_round_off).toFixed(2)+'</strong></td>'
				+'<td><strong>'+parseFloat(cus_total_amt+cmp_total_amt+overseas_tot_amount).toFixed(2)+'</strong></td>'
				+'</tr>';
			}
    
    
            if(repair.length>0)
            {
               trHTML+='<tr>'
                       +'<td><strong>REPAIR CHARGES</strong></td>'
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
               $.each(repair,function(key,items){
                   rep_pcs+=parseFloat(items.pcs);
                   rep_gwt+=parseFloat(items.weight);
                   rep_taxable+=parseFloat(items.tot_taxable);
                   rep_sgst+=parseFloat(items.tot_sgst);
                   rep_cgst+=parseFloat(items.tot_cgst);
                   rep_igst+=parseFloat(items.tot_igst);
                   rep_tax+=parseFloat(items.tot_tax);
                   rep_tot_amount+=parseFloat(items.amount);
                   rep_round_off+=parseFloat(items.round_off);
                   trHTML+='<tr>'
                    +'<td><strong>'+items.name+'</strong></td>'
                       +'<td>'+($('#report_format').val() == 1 ? (items.starting_bill+' - '+items.ending_bill): items.bill_no)+'</td>'
                       +'<td>'+items.pcs+'</td>'
                       +'<td>'+items.weight+'</td>'
                       +'<td></td>'
                       +'<td>'+items.tot_taxable+'</td>'
                       +'<td>'+items.tot_sgst+'</td>'
                       +'<td>'+items.tot_cgst+'</td>'
                       +'<td>'+items.tot_igst+'</td>'
                       +'<td>'+items.tot_tax+'</td>'
                       +'<td>'+parseFloat(items.round_off).toFixed(2)+'</td>'
                       +'<td>'+items.amount+'</td>'
                       +'</tr>';
               });
               
               trHTML+='<tr>'
                       +'<td><strong>GRANT TOTAL</strong></td>'
                       +'<td></td>'
                       +'<td><strong>'+rep_pcs+'</strong></td>'
                       +'<td><strong>'+parseFloat(rep_gwt).toFixed(3)+'</strong></td>'
                       +'<td><strong></strong></td>'
                       +'<td><strong>'+parseFloat(rep_taxable).toFixed(2)+'</strong></td>'
                       +'<td><strong>'+parseFloat(rep_sgst).toFixed(2)+'</strong></td>'
                       +'<td><strong>'+parseFloat(rep_cgst).toFixed(2)+'</strong></td>'
                       +'<td><strong>'+parseFloat(rep_igst).toFixed(2)+'</strong></td>'
                       +'<td><strong>'+parseFloat(rep_tax).toFixed(2)+'</strong></td>'
                       +'<td><strong>'+parseFloat(rep_round_off).toFixed(2)+'</strong></td>'
                       +'<td><strong>'+parseFloat(rep_tot_amount).toFixed(2)+'</strong></td>'
                +'</tr>';
           	}
			
           
           $('#gst_abstract_list > tbody').html(trHTML);
       
       
            if(!$.fn.DataTable.isDataTable('#gst_abstract_list'))
            {
                    oTable = $('#gst_abstract_list').dataTable({
                    "bSort": false,
                    "bDestroy": true,
                    "bInfo": true,
                    "scrollX":'100%',
                    "paging": false,  
                    "dom": 'Bfrtip',
                    "bAutoWidth": false,
                    "responsive": true,
           
                   
                    "buttons": [
                    {
                    extend: 'print',
                    footer: false,
                    title: '',
                    messageTop: title,
                orientation: 'landscape',
                    customize: function(win)
                    {
                    
                    var last = null;
                    var current = null;
                    var bod = [];
                    
                    var css = '@page { size: landscape; }',
                    head = win.document.head || win.document.getElementsByTagName('head')[0],
                    style = win.document.createElement('style');
                    
                    style.type = 'text/css';
                    style.media = 'print';
                    
                    if (style.styleSheet)
                    {
                    style.styleSheet.cssText = css;
                    }
                    else
                    {
                    style.appendChild(win.document.createTextNode(css));
                    }
                    
                    head.appendChild(style);
                    },
                    exportOptions: {
                    columns: ':visible',
                    stripHtml: false
                    }
                    },
                    {
                    extend:'excel',
                    footer: true,
                    title: 'GST Abstract Report',
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
}*/



function get_gst_abstract_details()
{
	/*var branch_name=($('#branch_name').val()!='' && $('#branch_name').val()!=undefined ? $('#branch_name').val() : $("#branch_select option:selected").text());*/

	var branch_name=($('#branch_name').val()!='' && $('#branch_name').val()!=undefined ? $('#branch_name').val() : $('#branch_select option:selected').toArray().map(item => item.text).join());

	var company_code=$('#company_code').val();
	var company_address1=$('#company_address1').val();
	var company_address2=$('#company_address2').val();
	var company_city=$('#company_city').val();
	var pincode=$('#pincode').val();
	var company_email=$('#company_email').val();
	var company_gst_number=$('#company_gst_number').val();
	var phone=$('#phone').val();
	var report_type=$('#sale_ret_filter').find(':selected').text();

	var title="<div style='text-align: center;'><b><span style='font-size:12pt;'>"+company_code+"</span></b></br>"
	+"<span style='font-size:11pt;'>"+company_address1+"</span></br>"
	+"<span style='font-size:11pt;'>"+company_address2 + company_city+"-"+pincode+"</span></br>"
	+"<span style='font-size:11pt;'>GSTIN:"+company_gst_number +", EMAIL:"+ company_email+"</span></br>"
	+"<span style='font-size:11pt;'>Contact :"+phone +"</span></br>"
	+"<span style=font-size:12pt;>&nbsp;&nbsp;Sales Abstract GST Report - "+report_type + " - " +branch_name+" &nbsp;From&nbsp;:&nbsp;"+$('#rpt_from_date').html()+" &nbsp;&nbsp;- to "+$('#rpt_to_date').html() + " - " + $('.hidden-xs').html()+"&nbsp;&nbsp;"+ getDisplayDateTime() + "</span></div>";
	$("div.overlay").css("display", "block");
	my_Date = new Date();
	$.ajax({
		url:base_url+ "index.php/admin_ret_reports/gst_abstract/ajax?nocache=" + my_Date.getUTCSeconds(),
		dataType:"JSON",
		data:{'group_by':$('#gst_group_by').val(),'id_branch':$('#branch_select').val(),'from_date':$('#rpt_from_date').html(),'to_date' :$('#rpt_to_date').html(),'report_type':$('#report_type').val(),'gst_filter':$('#gst_filter').val(),'sale_ret_filter':$('#sale_ret_filter').val(),'report':$('#report_format').val(), 'id_category':($('#category').val()!='' && $('#category').val()!=undefined && $('#category').val() != null) ? $('#category').val(): 0},
		type:"POST",
		success:function(data)
			{
				var company_bills=data.list.B2B;
				var customer_bills=data.list.B2C;
				var sales_transfer=data.list.sales_transfer;
				var repair=data.list.repair;
				var overseas = data.list.overseas;



				var cmp_round_off=0;
				var cus_round_off=0;
				var rep_round_off=0;
				var overseas_round_off=0;

				var tot_cmp_round_off=0;
				var tot_cus_round_off=0;
				var tot_rep_round_off=0;
				var tot_overseas_round_off=0;

				var trHTML='';
				$("#gst_abstract_list > tbody > tr").remove();
				$('#gst_abstract_list').dataTable().fnClearTable();
				$('#gst_abstract_list').dataTable().fnDestroy();

				var cmp_pcs=0;
				var cmp_gwt=0;
				var cmp_nwt=0;
				var cmp_lwt=0;
				var cmp_sgst=0;
				var cmp_cgst=0;
				var cmp_igst=0;
				var cmp_gst=0;
				var cmp_total_amt=0;
				var cmp_taxable_amt=0;

				var tot_cmp_pcs=0;
				var tot_cmp_gwt=0;
				var tot_cmp_nwt=0;
				var tot_cmp_lwt=0;
				var tot_cmp_sgst=0;
				var tot_cmp_cgst=0;
				var tot_cmp_igst=0;
				var tot_cmp_gst=0;
				var tot_cmp_total_amt=0;
				var tot_cmp_taxable_amt=0;

				
				
				var cus_pcs=0;
				var cus_gwt=0;
				var cus_nwt=0;
				var cus_lwt=0;
				var cus_sgst=0;
				var cus_cgst=0;
				var cus_igst=0;
				var cus_gst=0;
				var cus_total_amt=0;
				var cus_taxable_amt=0;

				var tot_cus_pcs=0;
				var tot_cus_gwt=0;
				var tot_cus_nwt=0;
				var tot_cus_lwt=0;
				var tot_cus_sgst=0;
				var tot_cus_cgst=0;
				var tot_cus_igst=0;
				var tot_cus_gst=0;
				var tot_cus_total_amt=0;
				var tot_cus_taxable_amt=0;


				
				var rep_pcs=0;
				var rep_gwt=0;
				var rep_taxable=0;
				var rep_sgst=0;
				var rep_cgst=0;
				var rep_igst=0;
				var rep_tax=0;
				var rep_tot_amount=0;

				var tot_rep_pcs=0;
			 	var	tot_rep_gwt=0;
				var tot_rep_taxable=0;
				var	tot_rep_sgst=0;
				var	tot_rep_cgst=0;
				var	tot_rep_igst=0;
				var	tot_rep_tax=0;
				var	tot_rep_tot_amount=0;

				
				
				var overseas_pcs=0;
				var overseas_gwt=0;
				var overseas_taxable=0;
				var overseas_sgst=0;
				var overseas_cgst=0;
				var overseas_igst=0;
				var overseas_tax=0;
				var overseas_tot_amount=0;


				
				var tot_overseas_pcs=0;
				var tot_overseas_gwt=0;
				var tot_overseas_taxable=0;
				var tot_overseas_sgst=0;
				var tot_overseas_cgst=0;
				var tot_overseas_igst=0;
				var tot_overseas_tax=0;
				var tot_overseas_tot_amount=0;
				
				
				var st_pcs=0;
				var st_gwt=0;
				var st_taxable=0;
				var st_sgst=0;
				var st_cgst=0;
				var st_igst=0;
				var st_tax=0;
				var st_tot_amount=0;
				var st_round_off=0;


				if(company_bills!='')
				{
					trHTML+='<tr>'
					+'<td><strong>B2B BILLS</strong></td>'
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
					+'<td></td>'
					+'</tr>';
					$.each(company_bills,function(key,cat){
						if($('#report_format').val()==2)
						{
							trHTML+='<tr>'
							+'<td><strong>'+key+'</strong></td>'
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
							+'<td></td>'
							+'</tr>';
						}
						
						$.each(cat,function(k,items){


							cmp_pcs+=parseFloat(items.piece);
							cmp_gwt+=parseFloat(items.gross_wt);
							cmp_nwt+=parseFloat(items.net_wt);
							cmp_lwt+=parseFloat(items.less_wt);
							cmp_taxable_amt+=parseFloat(items.taxable_amt);
							cmp_sgst+=parseFloat(items.total_sgst);
							cmp_cgst+=parseFloat(items.total_cgst);
							cmp_igst+=parseFloat(items.total_igst);
							cmp_gst+=parseFloat(items.tax_amt);
							cmp_total_amt+=parseFloat(items.total_amount);
							cmp_round_off+=parseFloat(items.round_off);

							tot_cmp_pcs+=parseFloat(items.piece);
							tot_cmp_gwt+=parseFloat(items.gross_wt);
							tot_cmp_nwt+=parseFloat(items.net_wt);
							tot_cmp_lwt+=parseFloat(items.less_wt);
							tot_cmp_taxable_amt+=parseFloat(items.taxable_amt);
							tot_cmp_sgst+=parseFloat(items.total_sgst);
							tot_cmp_cgst+=parseFloat(items.total_cgst);
							tot_cmp_igst+=parseFloat(items.total_igst);
							tot_cmp_gst+=parseFloat(items.tax_amt);
							tot_cmp_total_amt+=parseFloat(items.total_amount);
							tot_cmp_round_off+=parseFloat(items.round_off);

							print_url=base_url+'index.php/admin_ret_billing/billing_invoice/'+items.bill_id;
						
							action_content='<a href="'+print_url+'" target="_blank" title="Billing Receipt">SA-'+items.short_name+'-'+items.metal_code+'-'+items.sales_ref_no+'</a>'


							trHTML+='<tr>'
							+'<td><strong>'+($('#report_format').val()==1 ? items.category_name :'')+'</strong></td>'
							+'<td>'+($('#report_format').val()==1?('SA-'+ items.short_name+'-'+ items.metal_code+'-'+items.starting_bill+' - '+'SA-'+items.short_name+'-'+items.metal_code+'-'+items.ending_bill):action_content)+'</td>' 
							+'<td>'+($('#report_format').val()==2 ? items.bill_date :'')+'</td>'
							+'<td>'+($('#report_format').val()==2 ? items.cus_name :'')+'</td>'
							+'<td>'+($('#report_format').val()==2 ? items.statename :'')+'</td>'
							+'<td>'+items.piece+'</td>'
							+'<td>'+items.gross_wt+'</td>'
							+'<td>'+items.net_wt+'</td>'
							+'<td>'+items.less_wt+'</td>'
							+'<td>'+items.taxable_amt+'</td>'
							+'<td>'+items.total_sgst+'</td>'
							+'<td>'+items.total_cgst+'</td>'
							+'<td>'+items.total_igst+'</td>'
							+'<td>'+items.tax_amt+'</td>'
							+'<td>'+parseFloat(items.round_off).toFixed(2)+'</td>'
							+'<td>'+items.total_amount+'</td>'
							+'<td>'+items.gst_number+'</td>'
							+'<td>'+items.pan+'</td>'
							+'</tr>';
							
						});
						if($('#report_format').val()==2)
						{
						trHTML+='<tr>'
							+'<td><strong>SUB TOTAL</strong></td>'
							+'<td></td>'
							+'<td></td>'
							+'<td></td>'
							+'<td></td>'
							+'<td><strong>'+cmp_pcs+'</strong></td>'
							+'<td><strong>'+parseFloat(cmp_gwt).toFixed(3)+'</strong></td>'
							+'<td><strong>'+parseFloat(cmp_nwt).toFixed(3)+'</strong></td>'
							+'<td><strong>'+parseFloat(cmp_lwt).toFixed(3)+'</strong></td>'
							+'<td><strong>'+parseFloat(cmp_taxable_amt).toFixed(2)+'</strong></td>'
							+'<td><strong>'+parseFloat(cmp_sgst).toFixed(2)+'</strong></td>'
							+'<td><strong>'+parseFloat(cmp_cgst).toFixed(2)+'</strong></td>'
							+'<td><strong>'+parseFloat(cmp_igst).toFixed(2)+'</strong></td>'
							+'<td><strong>'+parseFloat(cmp_gst).toFixed(2)+'</strong></td>'
							+'<td><strong>'+parseFloat(cmp_round_off).toFixed(2)+'</strong></td>'
							+'<td><strong>'+parseFloat(cmp_total_amt).toFixed(2)+'</strong></td>'
							+'<td></td>'
							+'<td></td>'
							+'</tr>';

							cmp_pcs=0;
							cmp_gwt=0;
							cmp_nwt=0;
							cmp_lwt=0;
							cmp_sgst=0;
							cmp_cgst=0;
							cmp_igst=0;
							cmp_gst=0;
							cmp_total_amt=0;
							cmp_taxable_amt=0;

					
						}

					});

					if($('#report_format').val()==1)
					{

					trHTML+='<tr>'
					+'<td><strong>SUB TOTAL</strong></td>'
					+'<td></td>'
					+'<td></td>'
					+'<td></td>'
					+'<td></td>'
					+'<td><strong>'+cmp_pcs+'</td>'
					+'<td><strong>'+parseFloat(cmp_gwt).toFixed(3)+'</strong></td>'
					+'<td><strong>'+parseFloat(cmp_nwt).toFixed(3)+'</strong></td>'
					+'<td><strong>'+parseFloat(cmp_lwt).toFixed(3)+'</strong></td>'
					+'<td><strong>'+parseFloat(cmp_taxable_amt).toFixed(2)+'</strong></td>'
					+'<td><strong>'+parseFloat(cmp_sgst).toFixed(2)+'</strong></td>'
					+'<td><strong>'+parseFloat(cmp_cgst).toFixed(2)+'</strong></td>'
					+'<td><strong>'+parseFloat(cmp_igst).toFixed(2)+'</strong></td>'
					+'<td><strong>'+parseFloat(cmp_gst).toFixed(2)+'</strong></td>'
					+'<td><strong>'+parseFloat(cmp_round_off).toFixed(2)+'</strong></td>'
					+'<td><strong>'+parseFloat(cmp_total_amt).toFixed(2)+'</strong></td>'
					+'<td></td>'
					+'<td></td>'
					+'</tr>';
						
				}
				
			}

				if(customer_bills!='')
				{
					trHTML+='<tr>'
					+'<td><strong>B2C BILLS</strong></td>'
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
					+'<td></td>'
					+'</tr>';
					
					$.each(customer_bills,function(key,cat){


						if($('#report_format').val()==2)
						{
							trHTML+='<tr>'
							+'<td><strong>'+key+'</strong></td>'
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
							+'<td></td>'
							+'</tr>';
						}

						$.each(cat,function(k,items){


							cus_pcs+=parseFloat(items.piece);
							cus_gwt+=parseFloat(items.gross_wt);
							cus_nwt+=parseFloat(items.net_wt);
							cus_lwt+=parseFloat(items.less_wt);
							cus_taxable_amt+=parseFloat(items.taxable_amt);
							cus_sgst+=parseFloat(items.total_sgst);
							cus_cgst+=parseFloat(items.total_cgst);
							cus_igst+=parseFloat(items.total_igst);
							cus_gst+=parseFloat(items.tax_amt);
							cus_total_amt+=parseFloat(items.total_amount);
							cus_round_off+=parseFloat(items.round_off);

							tot_cus_pcs+=parseFloat(items.piece);
							tot_cus_gwt+=parseFloat(items.gross_wt);
							tot_cus_nwt+=parseFloat(items.net_wt);
							tot_cus_lwt+=parseFloat(items.less_wt);
							tot_cus_taxable_amt+=parseFloat(items.taxable_amt);
							tot_cus_sgst+=parseFloat(items.total_sgst);
							tot_cus_cgst+=parseFloat(items.total_cgst);
							tot_cus_igst+=parseFloat(items.total_igst);
							tot_cus_gst+=parseFloat(items.tax_amt);
							tot_cus_total_amt+=parseFloat(items.total_amount);
							tot_cus_round_off+=parseFloat(items.round_off);


							print_url=base_url+'index.php/admin_ret_billing/billing_invoice/'+items.bill_id;
						
							action_content='<a href="'+print_url+'" target="_blank" title="Billing Receipt">SA-'+items.short_name+'-'+items.metal_code+'-'+items.sales_ref_no+'</a>'


							trHTML+='<tr>'
							+'<td><strong>'+($('#report_format').val()==1 ? items.category_name :'')+'</strong></td>'
							+'<td>'+($('#report_format').val()==1?('SA-'+items.short_name+'-'+items.metal_code+'-'+items.starting_bill+' - '+'SA-'+items.short_name+'-'+items.metal_code+'-'+items.ending_bill):action_content)+'</td>' 
							+'<td>'+($('#report_format').val()==2 ? items.bill_date :'')+'</td>'
							+'<td>'+($('#report_format').val()==2 ? items.cus_name :'')+'</td>'
							+'<td>'+($('#report_format').val()==2 ? items.statename :'')+'</td>'
							+'<td>'+items.piece+'</td>'
							+'<td>'+items.gross_wt+'</td>'
							+'<td>'+items.net_wt+'</td>'
							+'<td>'+items.less_wt+'</td>'
							+'<td>'+items.taxable_amt+'</td>'
							+'<td>'+items.total_sgst+'</td>'
							+'<td>'+items.total_cgst+'</td>'
							+'<td>'+items.total_igst+'</td>'
							+'<td>'+items.tax_amt+'</td>'
							+'<td>'+parseFloat(items.round_off).toFixed(2)+'</td>'
							+'<td>'+items.total_amount+'</td>'
							+'<td>'+items.gst_number+'</td>'
							+'<td>'+items.pan+'</td>'
							+'</tr>';
						
						
						});

						
						if($('#report_format').val()==2)
						{
						trHTML+='<tr>'
							+'<td><strong>SUB TOTAL</strong></td>'
							+'<td></td>'
							+'<td></td>'
							+'<td></td>'
							+'<td></td>'
							+'<td><strong>'+cus_pcs+'</strong></td>'
							+'<td><strong>'+parseFloat(cus_gwt).toFixed(3)+'</strong></td>'
							+'<td><strong>'+parseFloat(cus_nwt).toFixed(3)+'</strong></td>'
							+'<td><strong>'+parseFloat(cus_lwt).toFixed(3)+'</strong></td>'
							+'<td><strong>'+parseFloat(cus_taxable_amt).toFixed(2)+'</strong></td>'
							+'<td><strong>'+parseFloat(cus_sgst).toFixed(2)+'</strong></td>'
							+'<td><strong>'+parseFloat(cus_cgst).toFixed(2)+'</strong></td>'
							+'<td><strong>'+parseFloat(cus_igst).toFixed(2)+'</strong></td>'
							+'<td><strong>'+parseFloat(cus_gst).toFixed(2)+'</strong></td>'
							+'<td><strong>'+parseFloat(cus_round_off).toFixed(2)+'</strong></td>'
							+'<td><strong>'+parseFloat(cus_total_amt).toFixed(2)+'</strong></td>'
							+'<td></td>'
							+'<td></td>'
							+'</tr>';

							cus_pcs=0;
							cus_gwt=0;
							cus_nwt=0;
							cus_lwt=0;
							cus_sgst=0;
							cus_cgst=0;
							cus_igst=0;
							cus_gst=0;
							cus_total_amt=0;
							cus_taxable_amt=0;
					
						}

						
					
						
					});


					if($('#report_format').val()==1)
					{

					trHTML+='<tr>'
					+'<td><strong>SUB TOTAL</strong></td>'
					+'<td></td>'
					+'<td></td>'
					+'<td></td>'
					+'<td></td>'
					+'<td><strong>'+cus_pcs+'</strong></td>'
					+'<td><strong>'+parseFloat(cus_gwt).toFixed(3)+'</strong></td>'
					+'<td><strong>'+parseFloat(cus_nwt).toFixed(3)+'</strong></td>'
					+'<td><strong>'+parseFloat(cus_lwt).toFixed(3)+'</strong></td>'
					+'<td><strong>'+parseFloat(cus_taxable_amt).toFixed(2)+'</strong></td>'
					+'<td><strong>'+parseFloat(cus_sgst).toFixed(2)+'</strong></td>'
					+'<td><strong>'+parseFloat(cus_cgst).toFixed(2)+'</strong></td>'
					+'<td><strong>'+parseFloat(cus_igst).toFixed(2)+'</strong></td>'
					+'<td><strong>'+parseFloat(cus_gst).toFixed(2)+'</strong></td>'
					+'<td><strong>'+parseFloat(cus_round_off).toFixed(2)+'</strong></td>'
					+'<td><strong>'+parseFloat(cus_total_amt).toFixed(2)+'</strong></td>'
					+'<td></td>'
					+'<td></td>'
					+'</tr>';
					}
				}




				if(overseas!='')
				{
					trHTML+='<tr>'
					+'<td><strong>OVERSEAS BILLS</strong></td>'
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
					+'<td></td>'
					+'</tr>';
					$.each(overseas,function(key,cat){

						if($('#report_format').val()==2)
						{
							trHTML+='<tr>'
							+'<td><strong>'+key+'</strong></td>'
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
							+'<td></td>'
							+'</tr>';
						}

						$.each(cat,function(k,items){


							overseas_pcs+=parseFloat(items.piece);
							overseas_gwt+=parseFloat(items.gross_wt);
							overseas_taxable+=parseFloat(items.taxable_amt);
							overseas_sgst+=parseFloat(items.total_sgst);
							overseas_cgst+=parseFloat(items.total_cgst);
							overseas_igst+=parseFloat(items.total_igst);
							overseas_tax+=parseFloat(items.tax_amt);
							overseas_tot_amount+=parseFloat(items.total_amount);
							overseas_round_off+=parseFloat(items.round_off);

							tot_overseas_pcs+=parseFloat(items.piece);
							tot_overseas_gwt+=parseFloat(items.gross_wt);
							tot_overseas_taxable+=parseFloat(items.taxable_amt);
							tot_overseas_sgst+=parseFloat(items.total_sgst);
							tot_overseas_cgst+=parseFloat(items.total_cgst);
							tot_overseas_igst+=parseFloat(items.total_igst);
							tot_overseas_tax+=parseFloat(items.tax_amt);
							tot_overseas_tot_amount+=parseFloat(items.total_amount);
							tot_overseas_round_off+=parseFloat(items.round_off);
							
							print_url=base_url+'index.php/admin_ret_billing/billing_invoice/'+items.bill_id;
						
							action_content='<a href="'+print_url+'" target="_blank" title="Billing Receipt">'+items.metal_code+'-'+items.sales_ref_no+'</a>'

							trHTML+='<tr>'
							+'<td><strong>'+($('#report_format').val()==1 ? items.category_name :'')+'</strong></td>'
							+'<td>'+($('#report_format').val()==1?(items.metal_code+'-'+items.starting_bill+' - '+items.metal_code+'-'+items.ending_bill):action_content)+'</td>' 
							+'<td>'+($('#report_format').val()==2 ? items.bill_date :'')+'</td>'
							+'<td>'+($('#report_format').val()==2 ? items.cus_name :'')+'</td>'
							+'<td>'+items.piece+'</td>'
							+'<td>'+items.gross_wt+'</td>'
							+'<td></td>'
							+'<td></td>'
							+'<td>'+items.taxable_amt+'</td>'
							+'<td>'+items.total_sgst+'</td>'
							+'<td>'+items.total_cgst+'</td>'
							+'<td>'+items.total_igst+'</td>'
							+'<td>'+items.tax_amt+'</td>'
							+'<td>'+parseFloat(items.round_off).toFixed(2)+'</td>'
							+'<td>'+items.total_amount+'</td>'
							+'<td>'+items.gst_number+'</td>'
							+'<td>'+items.pan+'</td>'
					        +'<td></td>'
							+'</tr>';
						});

						
						if($('#report_format').val()==2)
						{

							trHTML+='<tr>'
								+'<td><strong>SUB TOTAL</strong></td>'
								+'<td></td>'
								+'<td></td>'
								+'<td></td>'
								+'<td></td>'
								+'<td><strong>'+overseas_pcs+'</strong></td>'
								+'<td><strong>'+parseFloat(overseas_gwt).toFixed(3)+'</strong></td>'
								+'<td><strong></strong></td>'
								+'<td><strong></strong></td>'
								+'<td><strong>'+parseFloat(overseas_taxable).toFixed(2)+'</strong></td>'
								+'<td><strong>'+parseFloat(overseas_sgst).toFixed(2)+'</strong></td>'
								+'<td><strong>'+parseFloat(overseas_cgst).toFixed(2)+'</strong></td>'
								+'<td><strong>'+parseFloat(overseas_igst).toFixed(2)+'</strong></td>'
								+'<td><strong>'+parseFloat(overseas_tax).toFixed(2)+'</strong></td>'
								+'<td><strong>'+parseFloat(overseas_round_off).toFixed(2)+'</strong></td>'
								+'<td><strong>'+parseFloat(overseas_tot_amount).toFixed(2)+'</strong></td>'
								+'<td></td>'
								+'<td></td>'
							+'</tr>';

							overseas_pcs=0;
							overseas_gwt=0;
							overseas_taxable=0;
							overseas_sgst=0;
							overseas_cgst=0;
							overseas_igst=0;
							overseas_tax=0;
							overseas_tot_amount=0;
						}
					});
					
					if($('#report_format').val()==1)
						{
							trHTML+='<tr>'
							+'<td><strong>SUB TOTAL</strong></td>'
							+'<td></td>'
							+'<td></td>'
							+'<td></td>'
							+'<td><strong>'+overseas_pcs+'</strong></td>'
							+'<td><strong>'+parseFloat(overseas_gwt).toFixed(3)+'</strong></td>'
							+'<td><strong></strong></td>'
							+'<td><strong></strong></td>'
							+'<td><strong>'+parseFloat(overseas_taxable).toFixed(2)+'</strong></td>'
							+'<td><strong>'+parseFloat(overseas_sgst).toFixed(2)+'</strong></td>'
							+'<td><strong>'+parseFloat(overseas_cgst).toFixed(2)+'</strong></td>'
							+'<td><strong>'+parseFloat(overseas_igst).toFixed(2)+'</strong></td>'
							+'<td><strong>'+parseFloat(overseas_tax).toFixed(2)+'</strong></td>'
							+'<td><strong>'+parseFloat(overseas_round_off).toFixed(2)+'</strong></td>'
							+'<td><strong>'+parseFloat(overseas_tot_amount).toFixed(2)+'</strong></td>'
							+'<td></td>'
					        +'<td></td>'
							+'</tr>';
						}
				}
			
				if(customer_bills!='' || company_bills.length!='' || overseas.length!=''){
					trHTML+='<tr>'
					+'<td><strong>GRAND TOTAL</strong></td>'
					+'<td></td>'
					+'<td></td>'
					+'<td></td>'
					+'<td><strong>'+parseFloat(tot_cus_pcs+tot_cmp_pcs+overseas_pcs)+'</strong></td>'
					+'<td><strong>'+parseFloat(tot_cus_gwt+tot_cmp_gwt+overseas_gwt).toFixed(3)+'</strong></td>'
					+'<td><strong>'+parseFloat(tot_cus_nwt+tot_cmp_nwt).toFixed(3)+'</strong></td>'
					+'<td><strong>'+parseFloat(tot_cus_lwt+tot_cmp_lwt).toFixed(3)+'</strong></td>'
					+'<td><strong>'+parseFloat(tot_cus_taxable_amt+tot_cmp_taxable_amt+tot_overseas_taxable).toFixed(2)+'</strong></td>'
					+'<td><strong>'+parseFloat(tot_cus_sgst+tot_cmp_sgst+tot_overseas_sgst).toFixed(2)+'</strong></td>'
					+'<td><strong>'+parseFloat(tot_cus_cgst+tot_cmp_cgst+tot_overseas_cgst).toFixed(2)+'</strong></td>'
					+'<td><strong>'+parseFloat(tot_cus_igst+tot_cmp_igst+tot_overseas_igst).toFixed(2)+'</strong></td>'
					+'<td><strong>'+parseFloat(tot_cus_gst+tot_cmp_gst+tot_overseas_tax).toFixed(2)+'</strong></td>'
					+'<td><strong>'+parseFloat(tot_cus_round_off+tot_cmp_round_off+tot_overseas_round_off).toFixed(2)+'</strong></td>'
					+'<td><strong>'+parseFloat(tot_cus_total_amt+tot_cmp_total_amt+tot_overseas_tot_amount).toFixed(2)+'</strong></td>'
					+'<td></td>'
					+'<td></td>'
					+'</tr>';
				}


				if(repair!='')
				{
					trHTML+='<tr>'
					+'<td><strong>REPAIR CHARGES</strong></td>'
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
					+'<td></td>'
					+'</tr>';
					$.each(repair,function(key,cat){
						
						if($('#report_format').val()==2)
						{
							trHTML+='<tr>'
							+'<td><strong>'+key+'</strong></td>'
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
							+'<td></td>'
							+'</tr>';
						}

						$.each(cat,function(k,items){



							rep_pcs+=parseFloat(items.pcs);
							rep_gwt+=parseFloat(items.weight);
							rep_taxable+=parseFloat(items.tot_taxable);
							rep_sgst+=parseFloat(items.tot_sgst);
							rep_cgst+=parseFloat(items.tot_cgst);
							rep_igst+=parseFloat(items.tot_igst);
							rep_tax+=parseFloat(items.tot_tax);
							rep_tot_amount+=parseFloat(items.amount);
							rep_round_off+=parseFloat(items.round_off);

							tot_rep_pcs+=parseFloat(items.pcs);
							tot_rep_gwt+=parseFloat(items.weight);
							tot_rep_taxable+=parseFloat(items.tot_taxable);
							tot_rep_sgst+=parseFloat(items.tot_sgst);
							tot_rep_cgst+=parseFloat(items.tot_cgst);
							tot_rep_igst+=parseFloat(items.tot_igst);
							tot_rep_tax+=parseFloat(items.tot_tax);
							tot_rep_tot_amount+=parseFloat(items.amount);
							tot_rep_round_off+=parseFloat(items.round_off);

							print_url=base_url+'index.php/admin_ret_billing/billing_invoice/'+items.bill_id;
						
							action_content='<a href="'+print_url+'" target="_blank" title="Billing Receipt">'+items.bill_no+'</a>'

							trHTML+='<tr>'
							+'<td><strong>'+($('#report_format').val()==1 ? items.name :'')+'</strong></td>'
							+'<td>'+($('#report_format').val()==1?(items.starting_bill+' - '+items.ending_bill):action_content)+'</td>' 
							+'<td>'+($('#report_format').val()==2 ? items.bill_date :'')+'</td>'
							+'<td>'+($('#report_format').val()==2 ? items.cus_name :'')+'</td>'
							+'<td>'+items.pcs+'</td>'
							+'<td>'+items.weight+'</td>'
							+'<td></td>'
							+'<td></td>'
							+'<td>'+items.tot_taxable+'</td>'
							+'<td>'+items.tot_sgst+'</td>'
							+'<td>'+items.tot_cgst+'</td>'
							+'<td>'+items.tot_igst+'</td>'
							+'<td>'+items.tot_tax+'</td>'
							+'<td>'+parseFloat(items.round_off).toFixed(2)+'</td>'
							+'<td>'+items.amount+'</td>'
							+'<td>'+items.gst_number+'</td>'
							+'<td>'+items.pan+'</td>'
							+'</tr>';
						});

						if($('#report_format').val()==2)
						{

						trHTML+='<tr>'
							+'<td><strong> SUB TOTAL</strong></td>'
							+'<td></td>'
							+'<td></td>'
							+'<td></td>'
							+'<td><strong>'+rep_pcs+'</strong></td>'
							+'<td><strong>'+parseFloat(rep_gwt).toFixed(3)+'</strong></td>'
							+'<td><strong></strong></td>'
							+'<td><strong></strong></td>'
							+'<td><strong>'+parseFloat(rep_taxable).toFixed(2)+'</strong></td>'
							+'<td><strong>'+parseFloat(rep_sgst).toFixed(2)+'</strong></td>'
							+'<td><strong>'+parseFloat(rep_cgst).toFixed(2)+'</strong></td>'
							+'<td><strong>'+parseFloat(rep_igst).toFixed(2)+'</strong></td>'
							+'<td><strong>'+parseFloat(rep_tax).toFixed(2)+'</strong></td>'
							+'<td><strong>'+parseFloat(rep_round_off).toFixed(2)+'</strong></td>'
							+'<td><strong>'+parseFloat(rep_tot_amount).toFixed(2)+'</strong></td>'
							+'<td></td>'
							+'<td></td>'
							+'</tr>';

							rep_pcs=0;
							rep_gwt=0;
							rep_taxable=0;
							rep_sgst=0;
							rep_cgst=0;
							rep_igst=0;
							rep_tax=0;
							rep_tot_amount=0;
                            rep_round_off=0;

							

						}
                              

						
						});

						

					

						trHTML+='<tr>'
							+'<td><strong>GRANT TOTAL</strong></td>'
							+'<td></td>'
							+'<td></td>'
							+'<td></td>'
							+'<td><strong>'+tot_rep_pcs+'</strong></td>'
							+'<td><strong>'+parseFloat(tot_rep_gwt).toFixed(3)+'</strong></td>'
							+'<td><strong></strong></td>'
							+'<td><strong></strong></td>'
							+'<td><strong>'+parseFloat(tot_rep_taxable).toFixed(2)+'</strong></td>'
							+'<td><strong>'+parseFloat(tot_rep_sgst).toFixed(2)+'</strong></td>'
							+'<td><strong>'+parseFloat(tot_rep_cgst).toFixed(2)+'</strong></td>'
							+'<td><strong>'+parseFloat(tot_rep_igst).toFixed(2)+'</strong></td>'
							+'<td><strong>'+parseFloat(tot_rep_tax).toFixed(2)+'</strong></td>'
							+'<td><strong>'+parseFloat(tot_rep_round_off).toFixed(2)+'</strong></td>'
							+'<td><strong>'+parseFloat(tot_rep_tot_amount).toFixed(2)+'</strong></td>'
							+ '<td></td>'
							+ '<td></td>'
						+'</tr>';
							
						
						
				}


				$('#gst_abstract_list > tbody').html(trHTML);


				if(!$.fn.DataTable.isDataTable('#gst_abstract_list'))
				{
					oTable = $('#gst_abstract_list').dataTable({
						columnDefs: [{
							"defaultContent": "-",
							"targets": "_all"
						  }],
					"bSort": false,
					"bDestroy": true,
					"bInfo": true,
					"scrollX":'100%',
					"paging": false,  
					"dom": 'Bfrtip',
					"bAutoWidth": false,
					"responsive": true,
					/*"buttons": [
					{
					extend: 'print',
					footer: true,
					title: '',
					messageTop: title,
					customize: function ( win ) {
					$(win.document.body).find('table')
					.addClass('compact');

					$(win.document.body).find( 'table' )
					.addClass('compact')
					.css('font-size','10px')
					.css('font-family','sans-serif');

					},
					exportOptions: {
					columns: ':visible',
					stripHtml: false
					}
					},
					{
					extend:'excel',
					footer: true,
					title: 'GST Abstract Report',
					}
					], */
					"buttons": [
					{
						extend: 'print',
						footer: false,
						title: '',
						messageTop: title,
						orientation: 'landscape',
						customize: function(win)
						{

							var last = null;
							var current = null;
							var bod = [];

							var css = '@page { size: landscape; }',
							head = win.document.head || win.document.getElementsByTagName('head')[0],
							style = win.document.createElement('style');

							style.type = 'text/css';
							style.media = 'print';

							if (style.styleSheet)
							{				
								style.styleSheet.cssText = css;
							}
							else
							{
								style.appendChild(win.document.createTextNode(css));
							}

							head.appendChild(style);
						},
						exportOptions: {
							columns: ':visible',
							stripHtml: false
						}
					},
					{
						extend:'excel',
						footer: true,
						title: 'GST Abstract Report',
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

//gst abstract search
//sales return abstract
$('#sales_ret_abstract_search').on('click',function(){
	    sales_return_details();
});
function sales_return_details()
{
    
	/*var branch_name=($('#branch_name').val()!='' && $('#branch_name').val()!=undefined ? $('#branch_name').val() : $("#branch_select option:selected").text());*/
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
	+"<span style=font-size:12pt;>&nbsp;&nbsp;SALE RETURN ABSTRACT REPORT - "+branch_name+" &nbsp;From&nbsp;:&nbsp;"+$('#rpt_from_date').html()+" &nbsp;&nbsp;- to "+$('#rpt_to_date').html()+  " - " + $('.hidden-xs').html()+"&nbsp;&nbsp;"+ getDisplayDateTime() +"</span>"
	$("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/sales_return_abstract/ajax?nocache=" + my_Date.getUTCSeconds(),
			 dataType:"JSON",
			 data:{'group_by':$('#sale_ret_group_by').val(),'id_branch':$('#branch_select').val(),'from_date':$('#rpt_from_date').html(),'to_date' :$('#rpt_to_date').html(), 'id_category':($('#category').val()!='' && $('#category').val()!=undefined && $('#category').val() != null) ? $('#category').val(): 0},
			 type:"POST",
			 success:function(data)
			 {
				if($('#sale_ret_group_by').val()==0)
				{
			    var list=data.list;
			     
			    var trHTML='';
			    $("#sales_ret_abstract > tbody > tr").remove();  
	 		    $('#sales_ret_abstract').dataTable().fnClearTable();
    		    $('#sales_ret_abstract').dataTable().fnDestroy();
    		    
    		         var tot_piece=0;
			         var tot_gross_wt=0;
			         var tot_netwt=0;
			         var tot_diawt=0;
			         var tot_cgst=0;
			         var tot_sgst=0;
			         var tot_igst=0;
			         var tot_gst=0;
			         var tot_taxable_amt=0;
			         var tot_item_amount=0;
			         
			     $.each(list,function(k,bill_details){
			         var piece=0;
			         var gross_wt=0;
			         var netwt=0;
			         var diawt=0;
			         var cgst=0;
			         var sgst=0;
			         var igst=0;
			         var gst=0;
			         var taxable_amt=0;
			         var item_amount=0;
			         trHTML += '<tr style="font-weight:bold;">' +
 			 				'<td style="text-align: left;">'+k+'</td>'+
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
 			 				'<td></td>'+
 			 				'<td></td>'+
 			 				'<td></td>'+
 			 				'<td></td>'+
 			 		 '</tr>';
 			 		 $.each(bill_details,function(key,items){
 			 		     
         			 		 tot_piece+=parseFloat(items.piece);
         			 		 tot_gross_wt+=parseFloat(items.gross_wt);
         			 		 tot_netwt+=parseFloat(items.net_wt);
         			 		 tot_diawt+=parseFloat(items.tot_dia_wt);
         			 		 tot_taxable_amt+=parseFloat(items.taxable_amt);
         			 		 tot_sgst+=parseFloat(items.total_sgst);
         			 		 tot_cgst+=parseFloat(items.total_cgst);
         			 		 tot_igst+=parseFloat(items.total_igst);
         			 		 tot_gst+=parseFloat(items.tax_amt);
 			 		         tot_item_amount+=parseFloat(items.total_amount);
 			 		        
 			 		        
 			 		         piece+=parseFloat(items.piece);
         			 		 gross_wt+=parseFloat(items.gross_wt);
         			 		 netwt+=parseFloat(items.net_wt);
         			 		 diawt+=parseFloat(items.tot_dia_wt);
         			 		 taxable_amt+=parseFloat(items.taxable_amt);
         			 		 sgst+=parseFloat(items.total_sgst);
         			 		 cgst+=parseFloat(items.total_cgst);
         			 		 igst+=parseFloat(items.total_igst);
         			 		 gst+=parseFloat(items.tax_amt);
 			 		        item_amount+=parseFloat(items.total_amount);
 			 		        
 			 		     var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+items.sales_bill_id;
 			 		     var returnurl = base_url+'index.php/admin_ret_billing/billing_invoice/'+items.ret_bill_id;
 			 		     var returnbillurl = '<a href="'+returnurl+'" target="_blank" title="Billing Receipt">'+items.ret_bill_no+'</a>';
 			 		     var salebillurl = '<a href="'+url+'" target="_blank" title="Billing Receipt">'+items.sales_bill_no+'</a>';
 			 		     
 			 		     
 			 		     trHTML += '<tr>' +
 			 				'<td></td>'+
 			 				'<td>'+returnbillurl+'</td>'+
 			 				'<td>'+items.ret_bill_date+'</td>'+
 			 				'<td>'+items.piece+'</td>'+
 			 				'<td>'+items.gross_wt+'</td>'+
 			 				'<td>'+items.net_wt+'</td>'+
 			 				'<td>'+items.tot_dia_wt+'</td>'+
 			 				'<td>'+items.taxable_amt+'</td>'+
 			 				'<td>'+items.total_sgst+'</td>'+
 			 				'<td>'+items.total_cgst+'</td>'+
 			 				'<td>'+items.total_igst+'</td>'+
 			 				'<td>'+items.tax_amt+'</td>'+
 			 				'<td>'+items.total_amount+'</td>'+
 			 				'<td>'+salebillurl+'</td>'+
 			 				'<td>'+items.sales_date+'</td>'+
 			 		 '</tr>';
 			 		 });
 			 		  trHTML += '<tr style="font-weight:bold;">' +
 			 				'<td>SUB TOTAL</td>'+
 			 				'<td></td>'+
 			 				'<td></td>'+
 			 				'<td>'+piece+'</td>'+
 			 				'<td>'+parseFloat(gross_wt).toFixed(3)+'</td>'+
 			 				'<td>'+parseFloat(netwt).toFixed(3)+'</td>'+
 			 				'<td>'+parseFloat(diawt).toFixed(3)+'</td>'+
 			 				'<td>'+parseFloat(taxable_amt).toFixed(2)+'</td>'+
 			 				'<td>'+parseFloat(sgst).toFixed(2)+'</td>'+
 			 				'<td>'+parseFloat(cgst).toFixed(2)+'</td>'+
 			 				'<td>'+parseFloat(igst).toFixed(2)+'</td>'+
 			 				'<td>'+parseFloat(gst).toFixed(2)+'</td>'+
 			 				'<td>'+parseFloat(item_amount).toFixed(2)+'</td>'+
 			 				'<td></td>'+
 			 				'<td></td>'+
 			 		 '</tr>';
 			 		 
 			 		/* tot_piece+=parseFloat(tot_piece)+parseFloat(piece);
 			 		 tot_gross_wt+=parseFloat(tot_gross_wt)+parseFloat(gross_wt);
 			 		 tot_netwt+=parseFloat(tot_netwt)+parseFloat(netwt);
 			 		 tot_sgst+=parseFloat(tot_sgst)+parseFloat(sgst);
 			 		 tot_cgst+=parseFloat(tot_cgst)+parseFloat(cgst);
 			 		 tot_igst+=parseFloat(tot_igst)+parseFloat(igst);
 			 		 tot_taxable_amt+=parseFloat(tot_taxable_amt)+parseFloat(taxable_amt);
 			 		 tot_gst+=parseFloat(tot_gst)+parseFloat(gst);
 			 		 tot_item_amount+=parseFloat(tot_item_amount)+parseFloat(item_amount);*/
 			 		 
			     });
			     
			      trHTML += '<tr style="font-weight:bold;">' +
 			 				'<td>GRAND TOTAL</td>'+
 			 				'<td></td>'+
 			 				'<td></td>'+
 			 				'<td>'+tot_piece+'</td>'+
 			 				'<td>'+parseFloat(tot_gross_wt).toFixed(3)+'</td>'+
 			 				'<td>'+parseFloat(tot_netwt).toFixed(3)+'</td>'+
 			 				'<td>'+parseFloat(tot_diawt).toFixed(3)+'</td>'+
 			 				'<td>'+parseFloat(tot_taxable_amt).toFixed(2)+'</td>'+
 			 				'<td>'+parseFloat(tot_sgst).toFixed(2)+'</td>'+
 			 				'<td>'+parseFloat(tot_cgst).toFixed(2)+'</td>'+
 			 				'<td>'+parseFloat(tot_igst).toFixed(2)+'</td>'+
 			 				'<td>'+parseFloat(tot_gst).toFixed(2)+'</td>'+
 			 				'<td>'+parseFloat(tot_item_amount).toFixed(2)+'</td>'+
 			 				'<td></td>'+
 			 				'<td></td>'+
 			 		 '</tr>';
 			 		 
    			//$('#sales_ret_abstract > tbody').html(trHTML);
    			
    			
					$('#sales_ret_abstract > tbody').html(trHTML);
				}	 
				else{
					var list=data.list;
					var trHTML='';
					$("#sales_ret_abstract > tbody > tr").remove();  
					$('#sales_ret_abstract').dataTable().fnClearTable();
					$('#sales_ret_abstract').dataTable().fnDestroy();
					var tot_piece=0;
					var tot_gross_wt=0;
					var tot_netwt=0;
					var tot_diawt=0;
					var tot_cgst=0;
					var tot_sgst=0;
					var tot_igst=0;
					var tot_gst=0;
					var tot_taxable_amt=0;
					var tot_item_amount=0;
					$.each(list,function(k,bill_details){
						var piece=0;
						var gross_wt=0;
						var netwt=0;
						var diawt=0;
						var cgst=0;
						var sgst=0;
						var igst=0;
						var gst=0;
						var taxable_amt=0;
						var item_amount=0;
						
						  $.each(bill_details,function(key,items){
							  
								  tot_piece+=parseFloat(items.piece);
								  tot_gross_wt+=parseFloat(items.gross_wt);
								  tot_netwt+=parseFloat(items.net_wt);
								  tot_diawt+=parseFloat(items.tot_dia_wt);
								  tot_taxable_amt+=parseFloat(items.taxable_amt);
								  tot_sgst+=parseFloat(items.total_sgst);
								  tot_cgst+=parseFloat(items.total_cgst);
								  tot_igst+=parseFloat(items.total_igst);
								  tot_gst+=parseFloat(items.tax_amt);
								  tot_item_amount+=parseFloat(items.total_amount);
								 
								 
								  piece+=parseFloat(items.piece);
								  gross_wt+=parseFloat(items.gross_wt);
								  netwt+=parseFloat(items.net_wt);
								  diawt+=parseFloat(items.tot_dia_wt);
								  taxable_amt+=parseFloat(items.taxable_amt);
								  sgst+=parseFloat(items.total_sgst);
								  cgst+=parseFloat(items.total_cgst);
								  igst+=parseFloat(items.total_igst);
								  gst+=parseFloat(items.tax_amt);
								 item_amount+=parseFloat(items.total_amount);
								 
							  //var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+items.sales_bill_id;
							  trHTML += '<tr>' +
								 '<td style="font-weight:bold;">'+k+'</td>'+
								 '<td></td>'+
								 '<td></td>'+
								 '<td>'+items.piece+'</td>'+
								 '<td>'+items.gross_wt+'</td>'+
								 '<td>'+items.net_wt+'</td>'+
								 '<td>'+items.tot_dia_wt+'</td>'+
								 '<td>'+items.taxable_amt+'</td>'+
								 '<td>'+items.total_sgst+'</td>'+
								 '<td>'+items.total_cgst+'</td>'+
								 '<td>'+items.total_igst+'</td>'+
								 '<td>'+items.tax_amt+'</td>'+
								 '<td>'+items.total_amount+'</td>'+
								 '<td></td>'+
								 '<td></td>'+
						  '</tr>';
						  });
						   trHTML += '<tr style="font-weight:bold;">' +
								 '<td>SUB TOTAL</td>'+
								 '<td></td>'+
								 '<td></td>'+
								 '<td>'+piece+'</td>'+
								 '<td>'+parseFloat(gross_wt).toFixed(3)+'</td>'+
								 '<td>'+parseFloat(netwt).toFixed(3)+'</td>'+
								 '<td>'+parseFloat(diawt).toFixed(3)+'</td>'+
								 '<td>'+parseFloat(taxable_amt).toFixed(2)+'</td>'+
								 '<td>'+parseFloat(sgst).toFixed(2)+'</td>'+
								 '<td>'+parseFloat(cgst).toFixed(2)+'</td>'+
								 '<td>'+parseFloat(igst).toFixed(2)+'</td>'+
								 '<td>'+parseFloat(gst).toFixed(2)+'</td>'+
								 '<td>'+parseFloat(item_amount).toFixed(2)+'</td>'+
								 '<td></td>'+
								 '<td></td>'+
						  '</tr>';
						  
						 
						  
					});
					trHTML += '<tr style="font-weight:bold;">' +
 			 				'<td>GRAND TOTAL</td>'+
 			 				'<td></td>'+
 			 				'<td></td>'+
 			 				'<td>'+tot_piece+'</td>'+
 			 				'<td>'+parseFloat(tot_gross_wt).toFixed(3)+'</td>'+
 			 				'<td>'+parseFloat(tot_netwt).toFixed(3)+'</td>'+
 			 				'<td>'+parseFloat(tot_diawt).toFixed(3)+'</td>'+
 			 				'<td>'+parseFloat(tot_taxable_amt).toFixed(2)+'</td>'+
 			 				'<td>'+parseFloat(tot_sgst).toFixed(2)+'</td>'+
 			 				'<td>'+parseFloat(tot_cgst).toFixed(2)+'</td>'+
 			 				'<td>'+parseFloat(tot_igst).toFixed(2)+'</td>'+
 			 				'<td>'+parseFloat(tot_gst).toFixed(2)+'</td>'+
 			 				'<td>'+parseFloat(tot_item_amount).toFixed(2)+'</td>'+
 			 				'<td></td>'+
 			 				'<td></td>'+
 			 		 '</tr>';
					   $('#sales_ret_abstract > tbody').html(trHTML);
				}
    			
    			
	 			if(!$.fn.DataTable.isDataTable('#sales_ret_abstract')) 
	 			{ 
    				oTable = $('#sales_ret_abstract').dataTable({ 
    				"bSort": false, 
    				"bDestroy": true,
    				"bInfo": true, 
    				"scrollX":'100%',
    				"paging": false,  
    				"dom": 'Bfrtip',
    				"bAutoWidth": false,
    				"responsive": true,
    				"buttons": [
    				{ 
    					extend: 'print',
    					footer: true,
    					title: '',
    					messageTop: title,
						orientation: 'landscape',
    					customize: function(win)
						{
			
							var last = null;
							var current = null;
							var bod = [];
			
							var css = '@page { size: landscape; }',
								head = win.document.head || win.document.getElementsByTagName('head')[0],
								style = win.document.createElement('style');
			
							style.type = 'text/css';
							style.media = 'print';
			
							if (style.styleSheet)
							{
							style.styleSheet.cssText = css;
							}
							else
							{
							style.appendChild(win.document.createTextNode(css));
							}
			
							head.appendChild(style);
						},
							exportOptions: {
								columns: ':visible',
								stripHtml: false
							}
						},
						{
							extend:'excel',
							footer: true,
							title: 'GST Abstract Report',
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
//sales return details
//card collection report
$('#card_collection_search').on('click',function(){
    get_card_collection_report();
});
function get_card_collection_report()
{
	/*var branch_name=($('#branch_name').val()!='' && $('#branch_name').val()!=undefined ? $('#branch_name').val() : $("#branch_select option:selected").text());*/
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
	+"<span style=font-size:12pt;>&nbsp;&nbsp;CARD COLLECTION REPORT - "+branch_name+" &nbsp;FROM&nbsp;:&nbsp;"+$('#rpt_payments1').html()+" &nbsp;&nbsp;TO&nbsp;&nbsp; "+$('#rpt_payments2').html() + " - " + $('.hidden-xs').html()+ "</span>";
	$("div.overlay").css("display", "block");
    my_Date = new Date();
    $.ajax({
    url:base_url+ "index.php/admin_ret_reports/card_collection_report/ajax?nocache=" + my_Date.getUTCSeconds(),
    data: ( {'from_date':$('#rpt_payments1').html(),'to_date':$('#rpt_payments2').html(),'id_branch':($('#branch_filter').val()!='' && $('#branch_filter').val()!=undefined ? $('#branch_filter').val(): $("#branch_select").val())}),
    dataType:"JSON",
    type:"POST",
    success:function(data){
        var list = data.list;
        $("#card_collection_list > tbody > tr").remove();  
        $('#card_collection_list').dataTable().fnClearTable();
        $('#card_collection_list').dataTable().fnDestroy();
        
        if (list!= null)
        {  
            trHTML = '';
            tfootHTML = '';
            total_debit_payment=0;
            $.each(list, function (i, bank) {
                
                var debit_payment=0;
            
                $.each(bank, function (idx, item) {
                    if(item.bill_id==undefined && item.id_issue_receipt!=''){
                        print_url=base_url+'index.php/admin_ret_billing/receipt/receipt_print/'+item.id_issue_receipt;
                    }
                    else{
                        print_url=base_url+'index.php/admin_ret_billing/billing_invoice/'+item.bill_id;
                    }
                    action_content='<a href="'+print_url+'" target="_blank" title="Billing Receipt">'+item.bill_no+'</a>'
                    trHTML += '<tr>' +
                    '<td>'+action_content+'</td>'
                    +'<td>'+item.bill_date+'</td>'
                    +'<td>'+item.branch_name+'</td>'
                    +'<td>'+item.card_name+'</td>'
                    +'<td>'+item.card_no+'</td>'
                    +'<td>'+item.payment_amount+'</td>'
                    +'<td>'+item.approvalno+'</td>'
                    +'<td>'+item.device_name+'</td>'
                    +'<td>'+item.cus_name+'</td>'
                    '</tr>';
                    debit_payment = parseFloat(debit_payment) + parseFloat(item.payment_amount);
                });
            
                trHTML += '<tr style="font-weight:bold;">' +
                +'<td></td>'
                +'<td></td>'
                +'<td></td>'
                +'<td></td>'
                +'<td></td>'
                +'<td><strong>SUB TOTAL</strong></td>'
                +'<td><strong>'+parseFloat(debit_payment).toFixed(2)+'</strong></td>'
                +'<td></td>'
                +'<td></td>'
                +'<td></td>'
                '</tr>';
                total_debit_payment=total_debit_payment+debit_payment;
            });
                trHTML += '<tr style="font-weight:bold;">' +
                +'<td></td>'
                +'<td></td>'
                +'<td></td>'
                +'<td></td>'
                +'<td></td>'
                +'<td><strong>GRAND TOTAL</strong></td>'
                +'<td><strong>'+parseFloat(total_debit_payment).toFixed(2)+'<strong></td>'
                +'<td></td>'
                +'<td></td>'
                +'<td></td>'
                '</tr>';
            $('#card_collection_list > tbody').html(trHTML);
            // Check and initialise datatable
            if ( ! $.fn.DataTable.isDataTable( '#card_collection_list' ) ) {
            oTable = $('#card_collection_list').dataTable({
            "bSort": false,
            "bInfo": true,
            "scrollX":'100%',  
            "dom": 'lBfrtip',
            "paging":false,
            "buttons": [
            {
            extend: 'print',
            footer: true,
            title: '',
            messageTop :title,
            customize: function ( win ) {
            $(win.document.body).find( 'table' )
            .addClass( 'compact' )
            .css( 'font-size', 'inherit' );
            },
            },
            {
            extend:'excel',
            footer: true,
            }
            ],
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
$('#cheque_collection_search').on('click',function(){
    get_cheque_collection_report();
});
//Cheque Collection Report
function get_cheque_collection_report()
{
	/*var branch_name=($('#branch_name').val()!='' && $('#branch_name').val()!=undefined ? $('#branch_name').val() : $("#branch_select option:selected").text());*/
	
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
	+"<span style=font-size:12pt;>&nbsp;&nbsp;CHEQUE COLLECTION REPORT - "+branch_name+" &nbsp;FROM&nbsp;:&nbsp;"+$('#rpt_payments1').html()+" &nbsp;&nbsp;TO&nbsp;&nbsp; "+$('#rpt_payments2').html() +" - " + $('.hidden-xs').html() +"</span>";        
        
	$("div.overlay").css("display", "block");
        my_Date = new Date();
        $.ajax({
        url:base_url+ "index.php/admin_ret_reports/cheque_collection_report/ajax?nocache=" + my_Date.getUTCSeconds(),
        data: ( {'from_date':$('#rpt_payments1').html(),'to_date':$('#rpt_payments2').html(),'id_branch':($('#branch_filter').val()!='' && $('#branch_filter').val()!=undefined ? $('#branch_filter').val(): $("#branch_select").val())}),
        dataType:"JSON",
        type:"POST",
        success:function(data){
            var list = data.list;
            $("#cheque_collection_list > tbody > tr").remove();  
            $('#cheque_collection_list').dataTable().fnClearTable();
            $('#cheque_collection_list').dataTable().fnDestroy();
            
            if (list!= null)
            {  
                trHTML = '';
                tfootHTML = '';
                    
                var debit_payment=0;
                    $.each(list, function (idx, item) {
                        
						if(item.bill_id == undefined && item.id_issue_receipt != ''){
							var url = base_url+'index.php/admin_ret_billing/receipt/receipt_print/'+item.id_issue_receipt;
							action_content='<a href="'+url+'" target="_blank" title="Billing Receipt">'+item.bill_no+'</a>';
						}
						else{
							var url=base_url+'index.php/admin_ret_billing/billing_invoice/'+item.bill_id;
							action_content='<a href="'+url+'" target="_blank" title="Billing Receipt">'+item.bill_no+'</a>';
						}
                        trHTML += '<tr>' +
                        '<td>'+action_content+'</td>'
                        +'<td>'+item.bill_date+'</td>'
                        +'<td>'+item.branch_name+'</td>'
                        +'<td>'+item.type+'</td>'
                        +'<td>'+item.cheque_no+'</td>'
                        +'<td>'+item.cheque_date+'</td>'
                        +'<td>'+item.payment_amount+'</td>'
                        +'<td>'+item.cus_name+'</td>'
                        '</tr>';
                        debit_payment = parseFloat(debit_payment) + parseFloat(item.payment_amount);
                    });
                
                    trHTML += '<tr style="font-weight:bold;">' +
                    +'<td></td>'
                    +'<td></td>'
                    +'<td></td>'
                    +'<td></td>'
                    +'<td></td>'
                    +'<td></td>'
                    +'<td><strong>GRAND TOTAL</strong></td>'
                    +'<td><strong>'+parseFloat(debit_payment).toFixed(2)+'<strong></td>'
                    +'<td></td>'
                    '</tr>';
    
                $('#cheque_collection_list > tbody').html(trHTML);
                // Check and initialise datatable
                if ( ! $.fn.DataTable.isDataTable( '#cheque_collection_list' ) ) {
                oTable = $('#cheque_collection_list').dataTable({
                "bSort": false,
                "bInfo": true,
                "scrollX":'100%',  
                "dom": 'lBfrtip',
                "paging":false,
                "buttons": [
                {
                extend: 'print',
                footer: true,
                title: '',
                messageTop :title,
                customize: function ( win ) {
                $(win.document.body).find( 'table' )
                .addClass( 'compact' )
                .css( 'font-size', 'inherit' );
                },
                },
                {
                extend:'excel',
                footer: true,
                }
                ],
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
$('#nb_collection_search').click(function(event) {
    get_netbanking_collection_report();
});
    
function get_netbanking_collection_report()
{
	$('div.overlay').css('display','block');
	/*var branch_name=($('#branch_name').val()!='' && $('#branch_name').val()!=undefined ? $('#branch_name').val() : $("#branch_select option:selected").text());*/
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
	+"<span style=font-size:12pt;>&nbsp;&nbsp;Net Banking Collection Report - "+branch_name+" &nbsp;From&nbsp;:&nbsp;"+$('#nb_coll_rpt1').html()+" &nbsp;&nbsp;- to "+$('#nb_coll_rpt2').html() + " - " + $('.hidden-xs').html()+ "</span>";	
	my_Date = new Date();
        $.ajax({
            url:base_url+ "index.php/admin_ret_reports/netbanking_collection_report/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'from_date':$('#nb_coll_rpt1').html(),'to_date':$('#nb_coll_rpt2').html(),'id_branch':($('#branch_filter').val()!='' && $('#branch_filter').val()!=undefined ? $('#branch_filter').val(): $("#branch_select").val()),'nb_type':$('#nb_group_by').val() }),
            dataType:"JSON",
			cache:false,
            type:"POST",
            success:function(data)
            {
                
                $("#nb_collection_list > tbody > tr").remove();
                $('#nb_collection_list').dataTable().fnClearTable();
                $('#nb_collection_list').dataTable().fnDestroy();
                
				var list = data.list;
				var trHTML='';
				console.log(list);
	            // var oTable = $('#nb_collection_list').DataTable();
	            // oTable.clear().draw();
				if (list!= null)
				{
					var sub_tot_payment=0;
					var tot_payment=0;
					$.each(list, function (idx, item) {
						trHTML += '<tr>' 
						+'<th>'+idx+'</th>'
						+'<td></td>'
						+'<td></td>'
						+'<td></td>'
						+'<td></td>'
						+'<td></td>'
						+'<td></td>'
						+'<td></td>'
						+'<td></td>'
						+'<td></td>'
						'</tr>';
						$.each(item, function (key, val) {
							if(val.bill_id != undefined ){
								print_url=base_url+'index.php/admin_ret_billing/billing_invoice/'+val.bill_id;
								action_content='<a href="'+print_url+'" target="_blank" title="Billing Receipt">'+val.bill_no+'</a>'
							}else{
								print_url=base_url+'index.php/admin_ret_billing/receipt/receipt_print/'+val.id_issue_receipt;
								action_content='<a href="'+print_url+'" target="_blank" title="Billing Receipt">'+val.bill_no+'</a>'
							}
							
							sub_tot_payment += parseFloat(val.payment_amount);
							trHTML += '<tr>' 
							+'<td>'+action_content+'</td>'
							+'<td>'+val.bill_date+'</td>'
							+'<td>'+val.payment_date+'</td>'
							+'<td>'+val.name+'</td>'
							+'<td>'+val.nb_type+'</td>'
							+'<td>'+val.payment_type+'</td>'
							+'<td>'+val.payment_ref_number+'</td>'
							+'<td>'+val.payment_amount+'</td>'
							+'<td>'+val.firstname+'</td>'
							+'<td>'+val.mobile+'</td>'
							'</tr>';
						});
						tot_payment += parseFloat(sub_tot_payment);
						trHTML += '<tr>' 
						+'<td></td>'
						+'<td></td>'
						+'<td></td>'
						+'<td></td>'
						+'<td></td>'
						+'<td></td>'
						+'<th>SUB TOTAL</th>'
						+'<th>'+sub_tot_payment+'</th>'
						+'<td></td>'
						+'<td></td>'
						'</tr>';
					});
					trHTML += '<tr>' 
						+'<td></td>'
						+'<td></td>'
						+'<td></td>'
						+'<td></td>'
						+'<td></td>'
						+'<td></td>'
						+'<th>GRAND TOTAL</th>'
						+'<th>'+tot_payment+'</th>'
						+'<td></td>'
						+'<td></td>'
					'</tr>';
				}
				$('#nb_collection_list > tbody').html(trHTML);
				// Check and initialise datatable
				if ( ! $.fn.DataTable.isDataTable( '#nb_collection_list' ) ) {
					oTable = $('#nb_collection_list').dataTable({
					"bSort": false,
					"bInfo": true,
					"scrollX":'100%',  
					"dom": 'lBfrtip',
					"paging":false,
					"buttons": [
						{
							extend: 'print',
							footer: true,
							title: '',
							messageTop :title,
							customize: function ( win ) 
							{
								$(win.document.body).find( 'table' )
								.addClass( 'compact' )
								.css( 'font-size', 'inherit' );
								},
							},
						{
							extend:'excel',
							footer: true,
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
$('#advance_list_search').on('click',function(){
   set_advance_receipt_table();
});
function set_advance_receipt_table()
{
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
	+"<span style=font-size:12pt;>&nbsp;&nbsp;Advance Receipt Report - "+branch_name+" &nbsp;From&nbsp;:&nbsp;"+$('#advance_list_report_date1').html()+" &nbsp;&nbsp;- to "+$('#advance_list_report_date2').html() + " - " + $('.hidden-xs').html()+ "</span>";
		
	$("div.overlay").css("display", "block");
    my_Date = new Date();
    $.ajax({
    url:base_url+ "index.php/admin_ret_reports/advance_receipt_report/ajax?nocache=" + my_Date.getUTCSeconds(),
    data: ( {'from_date':$('#advance_list_report_date1').html(),'to_date':$('#advance_list_report_date2').html(),'id_branch':($('#branch_filter').val()!='' && $('#branch_filter').val()!=undefined ? $('#branch_filter').val(): $("#branch_select").val())}),
    dataType:"JSON",
    type:"POST",
    success:function(data){
    var list = data.list;
    $("#advance_list > tbody > tr").remove();  
    $('#advance_list').dataTable().fnClearTable();
    $('#advance_list').dataTable().fnDestroy();
    
    if (list!= null)
    {  
    
    trHTML = '';
    if(list.advance_receipt.length>0)
    {
    advance_receipt_amount=0;
    trHTML += '<tr style="font-weight:bold;">' +
    '<td><strong>ADVANCE RECEIPT</strong></td>'+
    '<td></td>'+
    '<td></td>'+
    '<td></td>'+
    '<td></td>'+
    '<td></td>'+
    '</tr>';
    $.each(list.advance_receipt, function (idx, item) {
	if(item.bill_id != undefined ){
		print_url=base_url+'index.php/admin_ret_billing/billing_invoice/'+item.bill_id;
		action_content='<a href="'+print_url+'" target="_blank" title="Billing Receipt">'+item.bill_no+'</a>'
	}else{
		print_url=base_url+'index.php/admin_ret_billing/receipt/receipt_print/'+item.id_issue_receipt;
		action_content='<a href="'+print_url+'" target="_blank" title="Advance Receipt">'+item.bill_no+'</a>'
	}
    trHTML += '<tr>' +
    '<td>'+action_content+'</td>'
    +'<td>'+item.bill_date+'</td>'
    +'<td>'+item.firstname+'</td>'
    +'<td>'+item.mobile+'</td>'
    +'<td>'+item.name+'</td>' 
    +'<td>'+parseFloat(item.amount).toFixed(2)+'</td>'
    '</tr>';
    advance_receipt_amount = parseFloat(advance_receipt_amount) + parseFloat(item.amount);
    });
    
    trHTML += '<tr style="font-weight:bold;">' +
    
    +'<td></td>'
    +'<td></td>'
    +'<td></td>'
    +'<td></td>'
    +'<td></td>'
    +'<td><strong>TOTAL</strong></td>'
    +'<td><strong>'+parseFloat(advance_receipt_amount).toFixed(2)+'</strong></td>'
    
    '</tr>';
    // total_debit_payment=total_debit_payment+debit_payment;
    }
    // ADVANCE UTILIZED
    if(list.advance_utilized.length>0)
    {
    
    advance_adjusted_amount=0;
    trHTML += '<tr style="font-weight:bold;">' +
    '<td><strong>ADVANCE UTILIZED</strong></td>'+
    '<td></td>'+
    '<td></td>'+
    '<td></td>'+
    '<td></td>'+
    '<td></td>'+
    '</tr>';
    $.each(list.advance_utilized, function (idx, item) {
    
		if(item.bill_id != undefined ){
			print_url=base_url+'index.php/admin_ret_billing/billing_invoice/'+item.bill_id;
			action_content='<a href="'+print_url+'" target="_blank" title="Billing Receipt">'+item.bill_no+'</a>'
		}else{
			print_url=base_url+'index.php/admin_ret_billing/receipt/receipt_print/'+item.id_issue_receipt;
			action_content='<a href="'+print_url+'" target="_blank" title="Advance Receipt">'+item.bill_no+'</a>'
		}
    
    trHTML += '<tr>' +
    '<td>'+action_content+'</td>'
    +'<td>'+item.bill_date+'</td>'
    +'<td>'+item.firstname+'</td>'
    +'<td>'+item.mobile+'</td>'
    +'<td>'+item.name+'</td>' 
    +'<td>'+parseFloat(item.advance_adj).toFixed(2)+'</td>'
    '</tr>';
    advance_adjusted_amount = parseFloat(advance_adjusted_amount) + parseFloat(item.advance_adj);
    });
    
    trHTML += '<tr style="font-weight:bold;">' +
    
    +'<td></td>'
    +'<td></td>'
    +'<td></td>'
    +'<td></td>'
    +'<td></td>'
    +'<td><strong>TOTAL</strong></td>'
    +'<td><strong>'+parseFloat(advance_adjusted_amount).toFixed(2)+'</strong></td>'
    
    '</tr>';
    }
	//ADVANCE REFUND
	if(list.advance_refund.length>0)
    {
    
    advance_refund_amount=0;
    trHTML += '<tr style="font-weight:bold;">' +
    '<td><strong>ADVANCE REFUND</strong></td>'+
    '<td></td>'+
    '<td></td>'+
    '<td></td>'+
    '<td></td>'+
    '<td></td>'+
    '</tr>';
    $.each(list.advance_refund, function (idx, item) {
    
		if(item.bill_id != undefined ){
			print_url=base_url+'index.php/admin_ret_billing/billing_invoice/'+item.bill_id;
			action_content='<a href="'+print_url+'" target="_blank" title="Billing Receipt">'+item.bill_no+'</a>'
		}else{
			print_url=base_url+'index.php/admin_ret_billing/receipt/receipt_print/'+item.id_issue_receipt;
			action_content='<a href="'+print_url+'" target="_blank" title="Advance Receipt">'+item.bill_no+'</a>'
		}
    
    trHTML += '<tr>' +
    '<td>'+action_content+'</td>'
    +'<td>'+item.bill_date+'</td>'
    +'<td>'+item.firstname+'</td>'
    +'<td>'+item.mobile+'</td>'
    +'<td>'+item.name+'</td>' 
    +'<td>'+parseFloat(item.amount).toFixed(2)+'</td>'
    '</tr>';
    advance_refund_amount = parseFloat(advance_refund_amount) + parseFloat(item.amount);
    });
    
    trHTML += '<tr style="font-weight:bold;">' +
    
    +'<td></td>'
    +'<td></td>'
    +'<td></td>'
    +'<td></td>'
    +'<td></td>'
    +'<td><strong>TOTAL</strong></td>'
    +'<td><strong>'+parseFloat(advance_refund_amount).toFixed(2)+'</strong></td>'
    
    '</tr>';
    }
	//ADVANCE REFUND
    
    
    $('#advance_list > tbody').html(trHTML);
    // Check and initialise datatable
    if ( ! $.fn.DataTable.isDataTable( '#advance_list' ) ) {
    oTable = $('#advance_list').dataTable({
    "bSort": false,
    "bInfo": true,
    "scrollX":'100%',  
    "dom": 'lBfrtip',
    "paging":false,
    "buttons": [
    {
    extend: 'print',
    footer: true,
    title: '',
    messageTop :title,
    customize: function ( win ) {
    $(win.document.body).find( 'table' )
    .addClass( 'compact' )
    .css( 'font-size', 'inherit' );
    },
    },
    {
    extend:'excel',
    footer: true,
    }
    ],
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


$('#categorywise_bt_search').click(function(event){
		get_category_bt_report();
});

$('#transitemtype').on('change',function(e){
	if(this.value == 3)
	{
		$(".disp-purchasetype").css("display", "block");
	}else{
	    $("#transpurchasetype").val(0);
	    $(".disp-purchasetype").css("display", "none");
	}
});

$('#transtype').on('change',function(e){
	if(this.value == 1)
	{
		 $('#branch_select').select2({			    
    	 	placeholder: "To Centre",			    
    	 	allowClear: true		    
     	}); 
     	$('.trans_centre').html("To Centre");
	}else{
	     $('#branch_select').select2({			    
    	 	placeholder: "From Centre",			    
    	 	allowClear: true		    
     	}); 
     	$('.trans_centre').html("From Centre");
	}
});


function get_category_bt_report()
{
	$("div.overlay").css("display", "block"); 
	var dt_range=($("#dt_range").val()).split('-');
	var company_name=$('#company_name').val();
	var company_code=$('#company_code').val();
	var branch_name=($('#branch_name').val()!='' && $('#branch_name').val()!=undefined ? $('#branch_name').val() : $('#branch_select option:selected').toArray().map(item => item.text).join());
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
	+"<span style=font-size:12pt;>Categorywise Tagged Report - "+ branch_name +" &nbsp;&nbsp; From: "+dt_range[0]+ " To:" +dt_range[1]+ " - " + $('.hidden-xs').html()+ "</span>";
	my_Date = new Date();
	var transitemtype = 0;
	if($("#transpurchasetype").val() == 0){
	    transitemtype = $('#transitemtype').val();
	}else{
	    if($("#transpurchasetype").val() == 1){ // Old Metal
	        transitemtype = 4;
	    }else if($("#transpurchasetype").val() == 2){ // Sales Return
	        transitemtype = 5;
	    }else if($("#transpurchasetype").val() == 2){ // Partly Sale
	        transitemtype = 6;
	    }
	}
	
	
	
	//($('#transitemtype').val()!='' && $('#transitemtype').val()!=undefined && $('#transitemtype').val() != null) ? $('#transitemtype').val(): 0
	$.ajax({
		 url:base_url+ "index.php/admin_ret_reports/categorywise_bt_report/ajax?nocache=" + my_Date.getUTCSeconds(),
		 data: ( {'transtype' : $("#transtype").val(), 'dt_range' :$("#dt_range").val(),'id_branch':$("#branch_select").val(), 'from_branch':$('#branch_select_to').val(), 'id_category':($('#category').val()!='' && $('#category').val()!=undefined && $('#category').val() != null) ? $('#category').val(): 0, 'transitemtype': transitemtype}),
		 dataType:"JSON",
		 type:"POST",
		 success:function(data){
			 console.log(data);
		    var list = data.list;
			print_stock_issue = list;
			console.log(data);
			var oTable = $('#categorywise_bt_list').DataTable();
				oTable.clear().draw();				  
				if (list!= null && list.length > 0)
				{  	
					oTable = $('#categorywise_bt_list').dataTable({
						"bDestroy": true,
		                "bInfo": true,
		                "bFilter": true,
						"order": [[ 0, "asc" ]],
						 "lengthMenu": [[-1, 25, 50, 100, 250], ["All" ,25, 50, 100, 250]],
		                "scrollX":'100%',
		                "bSort": true,
		                "dom": 'lBfrtip',
		               
		                "buttons": [
								 {
								   extend: 'print',
								   footer: true,
								   stripHtml: false,
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
    							   title: title, 
    							 }
								 ],
						"aaData": list,
						"aoColumns": [	
										{ "mDataProp": "category_name" },
	                                    { "mDataProp": "from_branch" },
	                                    { "mDataProp": "to_branch_name" },
										{ "mDataProp": "piece" },
										{ "mDataProp": "grs_wt" },
										{ "mDataProp": "net_wt" },
										{ "mDataProp": "totaldiawt" },
										{ "mDataProp": "sales_value" },
										{ "mDataProp": "trans_code" },
										{ "mDataProp": "branch_transfer_id" },
										{ "mDataProp": "approvedon" },
										{ "mDataProp": "downloadedon" }
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
												i : 0;
											};	
											
											$(api.column(0).footer() ).html('Total');	
											pcs = api
											.column(3, {search:'applied'})
											.data()
											.reduce( function (a, b) {
												return intVal(a) + intVal(b);
											}, 0 );
											$(api.column(3).footer()).html(parseFloat(pcs).toFixed(0));	 
											
											gross_wgt = api
											.column(4, {search:'applied'})
											.data()
											.reduce( function (a, b) {
												return intVal(a) + intVal(b);
											}, 0 );
											$(api.column(4).footer()).html(parseFloat(gross_wgt).toFixed(3));
											
											net_wgt = api
											.column(5, {search:'applied'})
											.data()
											.reduce( function (a, b) {
												return intVal(a) + intVal(b);
											}, 0 );
											$(api.column(5).footer()).html(parseFloat(net_wgt).toFixed(3));
											
											dia_wgt = api
											.column(6, {search:'applied'})
											.data()
											.reduce( function (a, b) {
												return intVal(a) + intVal(b);
											}, 0 );
											$(api.column(6).footer()).html(parseFloat(dia_wgt).toFixed(3));
											
											tot_amt = api
											.column(7, {search:'applied'})
											.data()
											.reduce( function (a, b) {
												return intVal(a) + intVal(b);
											}, 0 );
											$(api.column(7).footer()).html(parseFloat(tot_amt).toFixed(3));
											
									} 
									}else{
										 var api = this.api(), data; 
										 $(api.column(8).footer()).html('');
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

//ACCOUNTS REPORTS

$('#item_sale_detail_search').click(function(event){
	itemwise_sales_detail();
});

function itemwise_sales_detail()
{
    var dt_range=($("#dt_range").val()).split('-');
	var branch_name=$("#branch_select option:selected").text();
	var company_name=$('#company_name').val();
	var title="<b><span style='font-size:15pt;margin-left:50%;'>"+company_name+"</span></b><b><span style='font-size:15pt;'></span></b></br>"+"<span style=font-size:13pt;margin-left:25%;>&nbsp;&nbsp;Product Wise Sales Report "+($("#branch_select").val()!='' ? "Cost Center:"+branch_name+" ":'')+"From&nbsp;:&nbsp;"+dt_range[0]+" &nbsp;&nbsp;To Date&nbsp;:&nbsp;"+dt_range[1]+"</span>";
	my_Date = new Date();
	$(".overlay").css("display", "block");	 
	$.ajax({		
	 	type: 'POST',		
	 	url:base_url+ "index.php/admin_ret_reports/item_sales/ajax?nocache=" + my_Date.getUTCSeconds(),
		data: ( {'dt_range' :$("#dt_range").val(),'id_branch':($('#branch_filter').val()!='' && $('#branch_filter').val()!=undefined ? $('#branch_filter').val(): $("#branch_select").val()),'id_product':$('#prod_select').val(),'id_category':$('#category').val(),'id_metal':$('#metal').val()}),
	 	dataType : 'json',
		success:function(data){ 
		   
		 $(".overlay").css("display", "none");
        var list = data.list;
        var oTable = $('#itemwise-sales_detail').DataTable();
		oTable.clear().draw();				  
			if (list!= null)
			{  	    
				$("#itemwise-sales_detail > tbody > tr").remove();  
				$('#itemwise-sales_detail').dataTable().fnClearTable();
    		    $('#itemwise-sales_detail').dataTable().fnDestroy();
                var trHtml='';
                var grand_total_pcs=0;
                var grand_total_gwt=0;
                var grand_total_nwt=0;
                var grand_total_amount=0;
				console.log(list);
				let result = Array.from( //convert new set value to set value
					new Set( // remove duplicate values from string array 
						(list.map(listItem=>
							listItem.design_details.map((item)=>{ 
								return JSON.stringify({
									'product_name': item.product_name, 
									'design_name': item.design_name, 
									//'sub_design_name': item.sub_design_name 
								}); 							
							})
						)).flat(Infinity)
					) 
				).map(item=>JSON.parse(item));
				result.forEach((item)=>{
					item['list'] = (list.find(listItem=>listItem.product_name==item.product_name) || {})?.design_details?.filter(designDet=>designDet.design_name == item.design_name) || [];
					
				});
				Array.from(new Set(list.map(item=>item.product_name))).forEach(product=>{
					var tot_pcs=0;
					var tot_gwt=0;
					var tot_nwt=0;
					var tot_amount=0;
					let finalArr = result.filter(item=>item.product_name==product);
					console.log(finalArr);
					$.each(finalArr,function(key,item) {
							
						$.each(item.list,function(key,items) {						
	
							tot_pcs+=parseFloat(items.tot_pcs);	
							tot_gwt+=parseFloat(items.gross_wt);
							tot_nwt+=parseFloat(items.net_wt);
							tot_amount+=parseFloat(items.total_cost);
							
							grand_total_pcs+=parseFloat(items.tot_pcs);
							grand_total_gwt+=parseFloat(items.gross_wt);
							grand_total_nwt+=parseFloat(items.net_wt);
							grand_total_amount+=parseFloat(items.total_cost);
	
							var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+items.bill_id;
				
							trHtml+='<tr>'
								
								
								+'<td>'+items.branch+'</td>'
								+'<td><a href='+url+' target="_blank">'+items.metal_code+'-'+items.sales_ref_no+'</a></td>'

								+'<td>'+items.bill_date+'</td>'
								+'<td>'+items.karigar+'</td>'

								+'<td>'+items.tag_code+'</td>'								
								+'<td>'+items.old_tag_id+'</td>'


								+'<td>'+items.product_name+'</td>'
								+'<td>'+items.design_name+'</td>'

								+'<td>'+items.sub_design_name +' </td>'									
								+'<td>'+items.tot_pcs+'</td>'
								
								
								+'<td>'+items.gross_wt+'</td>'
								+'<td>'+items.net_wt+'</td>'
								
								+'<td>'+items.less_wt+'</td>'
								+'<td>'+items.total_cost+'</td>'

								+'<td>'+items.age+'</td>'


								+'<td>'+items.cert_no+'</td>'								
								+'<td>'+items.style_code+'</td>'
								
							
							+'</tr>';
	
						});
	
					});
			
				});
                
                trHtml+='<tr>'
                                
								

								+'<td style="text-align:left;"><strong>GRAND TOTAL</strong></td>'
                                +'<td></td>'
								
								+'<td></td>'  
								+'<td></td>'
								
								+'<td></td>'
								+'<td></td>'
								
							    +'<td></td>' 
								+'<td></td>'
								
								+'<td></td>'
								+'<td><strong>'+parseFloat(grand_total_pcs)+'</strong></td>'

                                +'<td><strong>'+parseFloat(grand_total_gwt).toFixed(3)+'</strong></td>'                              
								+'<td><strong>'+parseFloat(grand_total_nwt).toFixed(3)+'</strong></td>'
                               
								+'<td></td>'
								+'<td><strong>'+parseFloat(grand_total_amount).toFixed(2)+'</strong></td>'

								
								+'<td></td>'	
								
								+'<td></td>'								
								+'<td></td>'
                            +'</tr>';
                            
                $('#itemwise-sales_detail > tbody').html(trHtml);
                // Check and initialise datatable
	                 if ( ! $.fn.DataTable.isDataTable( '#itemwise-sales_detail' ) ) { 
	                     oTable = $('#itemwise-sales_detail').dataTable({ 
						                "bSort": false, 
						                "bInfo": true, 
						                "scrollX":'100%',  
						                "dom": 'lBfrtip',
						                "lengthMenu": [[-1, 25, 50, 100, 250], ["All" ,25, 50, 100, 250]],
						                "buttons": [
													{
													   extend: 'print',
													   footer: true,
													   title: '',
													   messageTop: title,
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
													    title: 'STOCK AND SALES',
													  }
													 ], 
			
							
									 });
					}
			}
		},
	  	error:function(error)  
	  	{
			$("div.overlay").css("display", "none"); 
	  	}	 
  	});
		
}



//device wise collection

$('#device_pay_search').click(function(event){
	    set_devicepayBills();
});


function get_ActiveDevicename()
{
	$('#device_name option').remove();
	$.ajax({
	url: base_url+'index.php/admin_ret_reports/get_ActiveDevicename',
	dataType:"JSON",
	 type:"POST",
	success:function(data){
		var id =  $("#id_pay_device").val();

		$("#device_name").append(						
			$("<option></option>")						
			.attr("value", 0)						  						  
			.text('All' )
			);

		$.each(data, function (key, item) {   
		    $("#device_name").append(
		    $("<option></option>")
		    .attr("value", item.id_device)    
		    .text(item.device_name)  
		    );
		});
		   
		$("#device_name").select2(
		{
			placeholder:"Select Device Name",
			allowClear: true		    
		});
		    $("#device_name").select2("val",(id!='' && id>0?id:''));
		    if($("#device_name").length)
		    {
		        $("#device_name").select2("val",(id!='' && id>0?id:''));
		    }
		    
		    
		}
	});
}

function set_devicepayBills()
{
	$("div.overlay").css("display","block");
	my_Date=new Date();
	$.ajax({
		url:base_url+"index.php/admin_ret_reports/pay_device/ajax?noncache=" +my_Date.getUTCSeconds(),
		data: ( {'dt_range' :$("#dt_range").val(),'id_branch':$("#branch_select").val(),'id_pay_device':$("#device_name").val()}),
		dataType:"JSON",
		type:"POST",
		success:function(data){
			$("div.overlay").css("display", "none"); 
			var list = data.list;
		   $('#total_count').text(list.length);
		   var oTable = $('#pay_device_list').DataTable();
		   oTable.clear().draw();				  
		   if (list!= null && list.length > 0)
		   {  	
			   oTable = $('#pay_device_list').dataTable({
				   "bDestroy": true,
				   "bInfo": true,
				   "bFilter": true,
				   "order": [[ 0, "desc" ]],
				   "scrollX":'100%',
				   "bSort": true,
				   "dom": 'lBfrtip',
				   "buttons": [
							{
							  extend: 'print',
							  footer: true,
							  title: "Pay Device Report - "+$("#dt_range").val(),
							  customize: function ( win ) {
								   $(win.document.body).find( 'table' )
									   .addClass( 'compact' )
									   .css( 'font-size', 'inherit' );
							   },
							},
							{
							   extend:'excel',
							   footer: true,
							   title: "Pay Device Report - "+$("#dt_range").val(), 
							 }
							],
				   "aaData": list,
				   "aoColumns": [	
								   { "mDataProp": "branch" },
								   { "mDataProp": function ( row, type, val, meta ){
									var url = base_url+'index.php/admin_ret_billing/billing_invoice/'+row.bill_id;
									return '<a href='+url+' target="_blank">'+row.bill_no+'</a>';
								   }
									},
								   { "mDataProp": "bill_date" },
								   { "mDataProp": "device_name" },
								   { "mDataProp": "amount" },
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
												i : 0;
											};	
											
											$(api.column(0).footer() ).html('Total');	
											amount = api
											.column(4)
											.data()
											.reduce( function (a, b) {
												return intVal(a) + intVal(b);
											}, 0 );
											$(api.column(4).footer()).html(parseFloat(amount).toFixed(2));	 
									} 
									}else{
										 var api = this.api(), data; 
										 $(api.column(4).footer()).html('');
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

//device wise collection


$('#weight_range_sales_search').click(function(event){
	get_weight_range_wise_sales_list();
});


function get_weight_range_wise_sales_list()
{
    $("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/weight_range_sales/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: {'from_date':$('#rpt_payments1').html(),'to_date':$('#rpt_payments2').html(),'id_branch':$('#branch_select').val(),'id_product':$('#prod_select').val(),'id_design':$('#des_select').val(),'id_sub_design':$('#sub_des_select').val(),'id_weight':$('#wt_select').val(),'id_karigar':$('#karigar').val()},
			 dataType:"JSON",
			 type:"POST",
			 success:function(data)
			 {
			        var list = data.list;
    				var oTable = $('#stock_sales_list').DataTable();
    				oTable.clear().draw();				  
    				if (list!= null && list.length > 0)
    				{  	
    					oTable = $('#stock_sales_list').dataTable({
    						"bDestroy": true,
    		                "bInfo": true,
    		                "bFilter": true,
    		                "scrollX":'100%',
    		                "bSort": true,
    		                "dom": 'lBfrtip',
    		                "buttons": [
    								 {
    								   extend: 'print',
    								   footer: true,
    								   title: "PURCHASE IMPORT",
    								   customize: function ( win ) {
    										$(win.document.body).find( 'table' )
    											.addClass( 'compact' )
    											.css( 'font-size', 'inherit' );
    									},
    								 },
    								 {
    									extend:'excel',
    									footer: true,
    								    title: "PURCHASE IMPORT",
    								  }
    								 ],
    						"aaData": list,
    										"aoColumns": [	
    										{ "mDataProp": "product_name" },
    										{ "mDataProp": "design_name" },
    										{ "mDataProp": "sub_design_name" },
    										{ "mDataProp": "weight_description" },
    										{ "mDataProp": "sales_pcs" },
    										{ "mDataProp": "sales_gwt" },
    										{ "mDataProp": "age" },
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



$('#staff_incentive_search').on('click',function(){
    get_staff_incentive_report();
});

function get_staff_incentive_report()
{
    $("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/staff_incentive_report/ajax?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'from_date' :$("#rpt_payments1").html(),'to_date' :$("#rpt_payments2").html(),'id_branch':$('#branch_select').val()}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			 	var list = data.list;
    				var oTable = $('#emp_list').DataTable();
    				oTable.clear().draw();				  
    				if (list!= null && list.length > 0)
    				{  	
    					oTable = $('#emp_list').dataTable({
    						"bDestroy": true,
    		                "bInfo": true,
    		                "bFilter": true,
    		                "scrollX":'100%',
    		                "bSort": true,
    		                "dom": 'lBfrtip',
    		                //"order": [[ 0, "ASC" ]],
    		                "buttons": [
    								 {
    								   extend: 'print',
    								   footer: true,
    								   title: "",
    								   messageTop: 'Staff Incentive Report',
    								   customize: function ( win ) {
    										$(win.document.body).find( 'table' )
    											.addClass( 'compact' )
    											.css( 'font-size', 'inherit' );
    									},
    								 },
    								 {
    									extend:'excel',
    									footer: true,
    								    title: "Staff Incentive Report",
    								  }
    								 ],
    						"aaData": list,
    						"aoColumns": [	
    						                { "mDataProp": "id_employee" },
    										{ "mDataProp": "emp_name" },
    										{ "mDataProp": "emp_code" },
    										{ "mDataProp": "branch_name" },
    									
    										{ "mDataProp": function ( row, type, val, meta ){
    										    var url = base_url+'index.php/admin_ret_reports/staff_incentive_report/detailed/'+row.id_employee+'/'+$("#rpt_payments1").html()+'/'+$("#rpt_payments2").html()+'/'+'1';
    											return '<a href='+url+' target="_blank">'+row.tot_acc_joined+'</a>';
    										},
    										},
    										{ "mDataProp": "sch_join_amt" },
    										
    										{ "mDataProp": function ( row, type, val, meta ){
    										    var url = base_url+'index.php/admin_ret_reports/staff_incentive_report/detailed/'+row.id_employee+'/'+$("#rpt_payments1").html()+'/'+$("#rpt_payments2").html()+'/'+'2';
    											return '<a href='+url+' target="_blank">'+row.total_acc_closed+'</a>';
    										},
    										},
    										
    										{ "mDataProp": "closing_benefit_amt" },
    										{ "mDataProp": "total_amount" },
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


function get_staff_incentive_detailed_report()
{
    $("div.overlay").css("display", "block"); 
	my_Date = new Date();
	$.ajax({
			 url:base_url+ "index.php/admin_ret_reports/staff_incentive_report/account_details?nocache=" + my_Date.getUTCSeconds(),
			 data: ( {'id_employee':ctrl_page[3],'from_date' :ctrl_page[4],'to_date' :ctrl_page[5],'type':ctrl_page[6]}),
			 dataType:"JSON",
			 type:"POST",
			 success:function(data){
			 	var list = data;
    				var oTable = $('#emp_list').DataTable();
    				oTable.clear().draw();				  
    				if (list!= null && list.length > 0)
    				{  	
    					oTable = $('#emp_list').dataTable({
    						"bDestroy": true,
    		                "bInfo": true,
    		                "bFilter": true,
    		                "scrollX":'100%',
    		                "bSort": true,
    		                "dom": 'lBfrtip',
    		                //"order": [[ 0, "ASC" ]],
    		                "buttons": [
    								 {
    								   extend: 'print',
    								   footer: true,
    								   title: "",
    								   messageTop: 'Above Two Lakhs Report',
    								   customize: function ( win ) {
    										$(win.document.body).find( 'table' )
    											.addClass( 'compact' )
    											.css( 'font-size', 'inherit' );
    									},
    								 },
    								 {
    									extend:'excel',
    									footer: true,
    								    title: "Above Two Lakhs Report",
    								  }
    								 ],
    						"aaData": list,
    						"aoColumns": [	
    						                { "mDataProp": "id_scheme_account" },
    										{ "mDataProp": "cus_name" },
    										{ "mDataProp": "mobile" },
    										{ "mDataProp": "acc_num" },
    										{ "mDataProp": "paid_installments" },
    										{ "mDataProp": "start_date" },
    										{ "mDataProp": "closing_date" },
    										{ "mDataProp": "closing_balance" },
    										{ "mDataProp": "clsoing_benefit" },
    										{ "mDataProp": "emp_incentive_amt" },
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
												i : 0;
											};	
											
											$(api.column(0).footer() ).html('Total');	

											total_amount = api
											.column(9)
											.data()
											.reduce( function (a, b) {
												return intVal(a) + intVal(b);
											}, 0 );
											$(api.column(9).footer()).html(parseFloat(total_amount).toFixed(2));	 
									} 
									}else{
										 var api = this.api(), data; 
										 $(api.column(8).footer()).html('');
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




$('#karigar_metal_issue').on('click',function(){
	get_karigar_metal_issue_itemwise();
 });
 
function get_karigar_metal_issue_itemwise()
{
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
	+"<span style=font-size:12pt;>&nbsp;&nbsp;Advance Receipt Report - "+branch_name+" &nbsp;From&nbsp;:&nbsp;"+$('#advance_list_report_date1').html()+" &nbsp;&nbsp;- to "+$('#advance_list_report_date2').html() + " - " + $('.hidden-xs').html()+ "</span>";
		
	$("div.overlay").css("display", "block");
    my_Date = new Date();
 
    $.ajax({
    url:base_url+ "index.php/admin_ret_reports/karigar_metal_issue/ajax?nocache=" + my_Date.getUTCSeconds(),
    data: ( {'from_date':$('#rpt_from_date').html(),'to_date' :$('#rpt_to_date').html(),'id_karigar':$('#karigar').val(),'id_metal':$('#metal').val(),'id_category':$('#category').val(),'id_product':$('#prod_select').val()}),
    dataType:"JSON",
    type:"POST",
    success:function(data){
		
	      $("#karigar_metal_issue_list > tbody > tr").remove();
          $('#karigar_metal_issue_list').dataTable().fnClearTable();
          $('#karigar_metal_issue_list').dataTable().fnDestroy();

		  var list = data.list;
		
		  var trHTML='';
		  if(list!=null)
		  {
			var sub_metal_issue_wt=0;
			var sub_metal_pure_wt=0;
			var tot_metal_weight=0;
			var tot_metal_pur_weight=0;
			$.each(list, function (idx, item) {
				
			trHTML += '<tr>' 
				+'<td></td>'
				+'<td></td>'
				+'<td></td>'
				+'<td></td>'
				+'<td></td>'
				+'<td></td>'
				+'<td></td>'
				+'<td></td>'
				
				'</tr>';
				$.each(item, function (key, val) {
					sub_metal_issue_wt += parseFloat(val.issue_metal_wt);
					sub_metal_pure_wt  += parseFloat(val.issue_metal_pur_wt)
					trHTML += '<tr>' 
					+'<td>'+val.metal_issue_date+'</td>'
					+'<td>'+val.met_issue_ref_id+'</td>'
					+'<td>'+val.karigar_name+'</td>'
					+'<td>'+val.metal_name+'</td>'
					+'<td>'+val.category_name+'</td>'
					+'<td>'+val.product_name+'</td>'
					+'<td>'+val.issue_metal_wt+'</td>'
					+'<td>'+val.issue_metal_pur_wt+'</td>'
					'</tr>'
				
					
				});
				tot_metal_weight     += parseFloat(sub_metal_issue_wt);
				tot_metal_pur_weight += parseFloat(sub_metal_pure_wt);
				trHTML += '<tr>' 
				+'<td></td>'
				+'<td></td>'
				+'<td></td>'
				+'<td></td>'
				+'<td></td>'
				+'<th>SUB TOTAL</th>'
				+'<th>'+(sub_metal_issue_wt).toFixed(3)+'</th>'
				+'<th>'+(sub_metal_pure_wt).toFixed(3)+'</th>'
				'</tr>';
				sub_metal_issue_wt=0
				sub_metal_pure_wt=0
			});
			trHTML += '<tr>' 
				+'<td></td>'
				+'<td></td>'
				+'<td></td>'
				+'<td></td>'
				+'<td></td>'
				+'<th>GRAND TOTAL</th>'
				+'<th>'+(tot_metal_weight).toFixed(3)+'</th>'
				+'<th>'+(tot_metal_pur_weight).toFixed(3)+'</th>'

			'</tr>';
		}

		$('#karigar_metal_issue_list > tbody').html(trHTML);
				// Check and initialise datatable
				if ( ! $.fn.DataTable.isDataTable( '#karigar_metal_issue_list' ) ) {
					oTable = $('#karigar_metal_issue_list').dataTable({
					"bSort": false,
					"bInfo": true,
					"scrollX":'100%',  
					"dom": 'lBfrtip',
					"paging":false,
					"buttons": [
						{
							extend: 'print',
							footer: true,
							title: '',
							messageTop :title,
							customize: function ( win ) 
							{
								$(win.document.body).find( 'table' )
								.addClass( 'compact' )
								.css( 'font-size', 'inherit' );
								},
							},
						{
							extend:'excel',
							footer: true,
						}
						],
					});
					}
				$("div.overlay").css("display", "none");
     },
})
}


function fnFormatRowMetalDetails( oTable, nTr )
{
      var oData = oTable.fnGetData( nTr );
      var rowDetail = '';
      var tot_row=0;
      var prodTable = 
         '<div class="innerDetails">'+
          '<table class="table table-responsive table-bordered text-center table-sm">'+ 
            '<tr class="bg-teal">'+
            '<th>S.No</th>'+ 
            '<th>Category Name</th>'+
            '<th>Metal </th>'+
            '<th>Product </th>'+
            '<th>Weight</th>'+
            '</tr>';
      var issue_details = oData.issue_detilas; 
      console.log(issue_details);
      tot_row=issue_details.length;
      $.each(issue_details, function (idx, val) {
         console.log(val);
    	prodTable += 
    	  '<tr class="prod_det_btn">'+
    	  '<td>'+parseFloat(idx+1)+'</td>'+
    	  '<td>'+val.category_name+'</td>'+
    	  '<td>'+val.metal+'</td>'+
    	  '<td>'+val.product_name+'</td>'+
    	  '<td>'+val.issue_metal_pur_wt+'</td>'+
      '</tr>'; 
    }); 
    
    rowDetail = prodTable+'</table></div>';
    return rowDetail;
}


$('#customer_detail_search').click(function(event){
		get_customer_details_report();
});

function get_customer_details_report()
{
    my_Date = new Date();
    $('.overlay').css('display','block');
    $.ajax({
            url:base_url+"index.php/admin_ret_reports/get_customer_details_report?nocache=" + my_Date.getUTCSeconds(),
            data: ( {'from_date' :$("#rpt_cus1").html(),'to_date' :$("#rpt_cus2").html(),'id_zone':$('#select_zone').val(),'id_branch':($('#branch_filter').val()!='' && $('#branch_filter').val()!=undefined ? $('#branch_filter').val(): $("#branch_select").val()),'id_village':$('#select_village').val()}),
            dataType:"JSON",
            type:"POST",
            success:function(data){

				var list = data;
				var oTable = $('#customer_details').DataTable();
				oTable.clear().draw();
				if(list!= null && list.length > 0)
				{
					oTable = $('#customer_details').dataTable({
					"bDestroy": true,
					"bInfo": true,
					"bFilter": true,
					"bSort": true,
                    "dom": 'lBfrtip',
					"buttons" : ['excel','print'],
				    "tableTools": { "buttons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },
					"aaData": list,
					"order": [[ 1, "desc" ]],
					"aoColumns": [
			
					{ "mDataProp":"id_customer" },
				    { "mDataProp":"cus_name" },
					{ "mDataProp":"mobile" },
					{ "mDataProp":"email" },
					{ "mDataProp":"address1" },
					{ "mDataProp":"address2" },
					{ "mDataProp":"address3" },
					{ "mDataProp":"village" },
					{ "mDataProp":"city_name" },
					{ "mDataProp":"state_name" },
					{ "mDataProp":"pan_no" },
					{ "mDataProp":"date_of_birth" },
					{ "mDataProp":"pincode" },
					{ "mDataProp":"wedding_date" },
				
					]
					});
				}  
				$('.overlay').css('display','none');
			}
            });
}


///DAY CLOSING REPORT

$('#day_close_report_search').on('click',function(){
    day_closing_report();
});

function day_closing_report()
{
    $("div.overlay").css("display","block");
    my_Date = new Date();
    $.ajax({
    url:base_url+ "index.php/admin_ret_reports/day_closing_report/ajax?nocache=" + my_Date.getUTCSeconds(),
    data: ( {'stock_date': $('.datePicker').val(),'id_branch':($('#branch_filter').val()!='' && $('#branch_filter').val()!=undefined ? $('#branch_filter').val(): $("#branch_select").val())}),
    dataType:"JSON",
    type:"POST",
    success:function(data){
    var list=data.list;
    $("div.overlay").css("display", "none"); 
    var oTable = $('#day_close_report').DataTable();
    oTable.clear().draw();  
    if (list!= null && list.length > 0)
    {   
        oTable = $('#day_close_report').dataTable({
        "bDestroy": true,
        "bInfo": true,
        "bFilter": true,
        "bSort": true,
        "dom": 'lBfrtip',
        "buttons": [
		 {
		   extend: 'print',
		   footer: true,
		   title: "Account Stock Details",
		   customize: function ( win ) {
				$(win.document.body).find( 'table' )
					.addClass( 'compact' )
					.css( 'font-size', 'inherit' );
			},
		 },
		 {
			extend:'excel',
			footer: true,
		    title: "Account Stock Details",
		  }
		 ],
        "order": [[ 0, "asc" ]],
        "aaData": list,
        "aoColumns": [  
        { "mDataProp": "branch_name" },
        { "mDataProp": "stockType" },
        { "mDataProp": "records" },
        {"mDataProp":function ( row, type, val, meta )
        {
           if(row.records==0)
           {
               return '<button type="button"  class="btn btn-primary" style="padding: 4px;" onclick ="update_stock_details('+row.stock_type+','+row.id_branch+');" >Update Stock</button>';
           }
           else
           {
               return '-';
           }
        } },
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

function update_stock_details(stock_type,id_branch)
{
    $('#confirm-add').modal('toggle');
    $('#stock_branch').val(id_branch);
    $('#stock_type').val(stock_type);
}


$('#update_stock').on('click',function(){
    $("div.overlay").css("display", "block"); 
    if($('#stock_type').val() == 1)
    {
        stock_url =  base_url+'index.php/admin_ret_services/stock_balance/manual/'+$('#stock_branch').val()+'/'+date_format($('.datePicker').val())
    }else
    {
        stock_url = base_url+'index.php/admin_ret_services/stock_balance/manual/'+$('#stock_branch').val()+'/'+date_format($('.datePicker').val())
    }
	$.ajax({
		  type: 'POST',
		  url:  stock_url,
		  dataType: 'json',
		  success: function(data) { 
		  		$("div.overlay").css("display", "none"); 
		  },
	  	  error:function(error)  
		  {

		  }	 
    });
});

    
///DAY CLOSING REPORT


function getdaytransactions_details()
{
    var branch_name = $('#branch_filter').find(':selected').text();

	var company_code=$('#company_code').val();
	var company_address1=$('#company_address1').val();
	var company_address2=$('#company_address2').val();
	var company_city=$('#company_city').val();
	var pincode=$('#pincode').val();
	var company_email=$('#company_email').val();
	var company_gst_number=$('#company_gst_number').val();
	var phone=$('#phone').val();
	var report_type=$('#mode_select').find(':selected').text();
	
	var today = new Date();

    var dispdate = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
    var disptime = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();

	var title="<div style='text-align: center;'><b><span style='font-size:12pt;'>"+company_code+"</span></b></br>"
	+"<span style='font-size:11pt;'>"+company_address1+"</span></br>"
	+"<span style='font-size:11pt;'>"+company_address2 + company_city+"-"+pincode+"</span></br>"
	+"<span style='font-size:11pt;'>GSTIN:"+company_gst_number +", EMAIL:"+ company_email+"</span></br>"
	+"<span style='font-size:11pt;'>Contact :"+phone +"</span></br>"
	+"<span style=font-size:12pt;>&nbsp;&nbsp;Day Transactions Report - "+report_type + " - " +branch_name+" &nbsp;From&nbsp;:&nbsp;"+$('#rpt_from_date').html()+" &nbsp;&nbsp;- to "+$('#rpt_to_date').html() + " &nbsp;&nbsp; " + dispdate + " " + disptime + " - " + $('.hidden-xs').html()+ "</span></div>";
	$("div.overlay").css("display", "block");
	my_Date = new Date();
	$(".summery_description").html(report_type + " - " +branch_name+" &nbsp;From&nbsp;:&nbsp;"+$('#rpt_from_date').html()+" &nbsp;&nbsp;- to "+$('#rpt_to_date').html() + " &nbsp;&nbsp; (" + dispdate + " " + disptime + " ) ");
	$.ajax({
		url:base_url+ "index.php/admin_ret_reports/daytransactions/ajax?nocache=" + my_Date.getUTCSeconds(),
		dataType:"JSON",
		data:{'from_date':$('#rpt_from_date').html(),'to_date' :$('#rpt_to_date').html(), 'id_branch':$('#branch_filter').val(), 'mode' : $('#mode_select').val()},
		type:"POST",
        success:function(data){
            var list=data.list;
            $("div.overlay").css("display", "none"); 
            var oTable = $('#day_transactiton_list').DataTable();
            oTable.clear().draw();  
            
            if(data.paymodes != undefined){
                $('.total_cash_receipts').html(data.paymodes.receipts.total_cash);
                $('.total_card_receipts').html(data.paymodes.receipts.total_card);
                $('.total_cashfree_receipts').html(data.paymodes.receipts.total_cashfree);
                $('.total_cheque_receipts').html(data.paymodes.receipts.total_cheque);
                $('.total_nb_receipts').html(data.paymodes.receipts.total_nb);
                $('.total_paytm_receipts').html(data.paymodes.receipts.total_paytm);
                
                 $('.total_amt_receipts').html(data.paymodes.receipts.total_receipts);
                
                $('.total_cash_paymet').html(data.paymodes.payments.total_cash);
                $('.total_card_paymet').html(data.paymodes.payments.total_card);
                $('.total_cashfree_paymet').html(data.paymodes.payments.total_cashfree);
                $('.total_cheque_paymet').html(data.paymodes.payments.total_cheque);
                $('.total_nb_paymet').html(data.paymodes.payments.total_nb);
                $('.total_paytm_paymet').html(data.paymodes.payments.total_paytm);
                
                $('.total_amt_payments').html(data.paymodes.payments.total_payments);
            }
            if (list!= null && list.length > 0)
            {   
                oTable = $('#day_transactiton_list').dataTable({
                "bDestroy": true,
                "bInfo": true,
                "bFilter": true,
                "bSort": true,
                "dom": 'lBfrtip',
                "buttons": [
        		 {
        		   extend: 'print',
        		   footer: true,
        		   title: title,
        		   customize: function ( win ) {
        				$(win.document.body).find( 'table' )
        					.addClass( 'compact' )
        					.css( 'font-size', 'inherit' );
        			},
        		 },
        		 {
        			extend:'excel',
        			footer: true,
        		    title: company_code + ' - ' + branch_name + ' - Transations' + dispdate + " " + disptime,
        		  }
        		 ],
                "order": [[ 0, "asc" ]],
                "aaData": list,
                "aoColumns": [  
                    { "mDataProp": "voucherno" },
                    { "mDataProp": "billdate" },
                    { "mDataProp": "firstname" },
                    { "mDataProp": "mobile" },
                    { "mDataProp": "grsswt" },
                    { "mDataProp": "netwt" },
                    { "mDataProp": "diawt" },
                    { "mDataProp": "total_item_cost" },
                    { "mDataProp": "total_sgst" },
                    { "mDataProp": "total_cgst" },
                    { "mDataProp": "total_igst" },
                    { "mDataProp": "total_tax" },
                    { "mDataProp": "tot_bill_amount" },
                    { "mDataProp": "total_cash" },
                    { "mDataProp": "total_card" },
                    { "mDataProp": "total_paytm" },
                    { "mDataProp": "total_cheque" },
                    { "mDataProp": "total_nb" },
                    { "mDataProp": "total_cashfree" },
                    { "mDataProp": "total_chit_utlize" },
                    { "mDataProp": "dueamount" },
                    { "mDataProp": "tot_discount" },
                    { "mDataProp": "handling_charges" },
                    { "mDataProp": "total_order_adv_adj" },
                    { "mDataProp": "total_adv_adj" },
                    { "mDataProp": "pur_ref_no" },
                    { "mDataProp": "total_old_metal_cost" },
                    { "mDataProp": "s_ret_refno" },
                    { "mDataProp": "ret_bill_amt" },
                    { "mDataProp": "roundoff" },
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
												i : 0;
											};	
											
											$(api.column(0).footer() ).html('Total');	
											
											grswt = api
											.column(4, {search:'applied'})
											.data()
											.reduce( function (a, b) {
												return intVal(a) + intVal(b);
											}, 0 );
											$(api.column(4).footer()).html(parseFloat(grswt).toFixed(3));	 
											
											
											net_wgt = api
											.column(5, {search:'applied'})
											.data()
											.reduce( function (a, b) {
												return intVal(a) + intVal(b);
											}, 0 );
											$(api.column(5).footer()).html(parseFloat(net_wgt).toFixed(3));
											
											dia_wgt = api
											.column(6, {search:'applied'})
											.data()
											.reduce( function (a, b) {
												return intVal(a) + intVal(b);
											}, 0 );
											$(api.column(6).footer()).html(parseFloat(dia_wgt).toFixed(3));
											
											amt = api
											.column(7, {search:'applied'})
											.data()
											.reduce( function (a, b) {
												return intVal(a) + intVal(b);
											}, 0 );
											$(api.column(7).footer()).html(parseFloat(amt).toFixed(2));
											
											sgst = api
											.column(8, {search:'applied'})
											.data()
											.reduce( function (a, b) {
												return intVal(a) + intVal(b);
											}, 0 );
											$(api.column(8).footer()).html(parseFloat(sgst).toFixed(2));
											
											cgst = api
											.column(9, {search:'applied'})
											.data()
											.reduce( function (a, b) {
												return intVal(a) + intVal(b);
											}, 0 );
											$(api.column(9).footer()).html(parseFloat(cgst).toFixed(2));
											
											igst = api
											.column(10, {search:'applied'})
											.data()
											.reduce( function (a, b) {
												return intVal(a) + intVal(b);
											}, 0 );
											$(api.column(10).footer()).html(parseFloat(igst).toFixed(2));
											
											gst = api
											.column(11, {search:'applied'})
											.data()
											.reduce( function (a, b) {
												return intVal(a) + intVal(b);
											}, 0 );
											$(api.column(11).footer()).html(parseFloat(gst).toFixed(2));
											
											total = api
											.column(12, {search:'applied'})
											.data()
											.reduce( function (a, b) {
												return intVal(a) + intVal(b);
											}, 0 );
											$(api.column(12).footer()).html(parseFloat(total).toFixed(2));
											
											cash = api
											.column(13, {search:'applied'})
											.data()
											.reduce( function (a, b) {
												return intVal(a) + intVal(b);
											}, 0 );
											$(api.column(13).footer()).html(parseFloat(cash).toFixed(2));
											
											card = api
											.column(14, {search:'applied'})
											.data()
											.reduce( function (a, b) {
												return intVal(a) + intVal(b);
											}, 0 );
											$(api.column(14).footer()).html(parseFloat(card).toFixed(2));
											
											paytm = api
											.column(15, {search:'applied'})
											.data()
											.reduce( function (a, b) {
												return intVal(a) + intVal(b);
											}, 0 );
											$(api.column(15).footer()).html(parseFloat(paytm).toFixed(2));
											
											cheque = api
											.column(16, {search:'applied'})
											.data()
											.reduce( function (a, b) {
												return intVal(a) + intVal(b);
											}, 0 );
											$(api.column(16).footer()).html(parseFloat(cheque).toFixed(2));
											
											nb = api
											.column(17, {search:'applied'})
											.data()
											.reduce( function (a, b) {
												return intVal(a) + intVal(b);
											}, 0 );
											$(api.column(17).footer()).html(parseFloat(nb).toFixed(2));
											
											cashfree = api
											.column(18, {search:'applied'})
											.data()
											.reduce( function (a, b) {
												return intVal(a) + intVal(b);
											}, 0 );
											$(api.column(18).footer()).html(parseFloat(cashfree).toFixed(2));
											
											chituti = api
											.column(19, {search:'applied'})
											.data()
											.reduce( function (a, b) {
												return intVal(a) + intVal(b);
											}, 0 );
											$(api.column(19).footer()).html(parseFloat(chituti).toFixed(2));
											
											due = api
											.column(20, {search:'applied'})
											.data()
											.reduce( function (a, b) {
												return intVal(a) + intVal(b);
											}, 0 );
											$(api.column(20).footer()).html(parseFloat(due).toFixed(2));
											
											discount = api
											.column(21, {search:'applied'})
											.data()
											.reduce( function (a, b) {
												return intVal(a) + intVal(b);
											}, 0 );
											$(api.column(21).footer()).html(parseFloat(discount).toFixed(2));
											
											handling = api
											.column(22, {search:'applied'})
											.data()
											.reduce( function (a, b) {
												return intVal(a) + intVal(b);
											}, 0 );
											$(api.column(22).footer()).html(parseFloat(handling).toFixed(2));
											
											orderadv = api
											.column(23, {search:'applied'})
											.data()
											.reduce( function (a, b) {
												return intVal(a) + intVal(b);
											}, 0 );
											$(api.column(23).footer()).html(parseFloat(orderadv).toFixed(2));
											
											advadj = api
											.column(24, {search:'applied'})
											.data()
											.reduce( function (a, b) {
												return intVal(a) + intVal(b);
											}, 0 );
											$(api.column(24).footer()).html(parseFloat(advadj).toFixed(2));
											
											purchase = api
											.column(26, {search:'applied'})
											.data()
											.reduce( function (a, b) {
												return intVal(a) + intVal(b);
											}, 0 );
											$(api.column(26).footer()).html(parseFloat(purchase).toFixed(2));
											
											salesreturn = api
											.column(28, {search:'applied'})
											.data()
											.reduce( function (a, b) {
												return intVal(a) + intVal(b);
											}, 0 );
											$(api.column(28).footer()).html(parseFloat(salesreturn).toFixed(2));
											
											roundoff = api
											.column(29, {search:'applied'})
											.data()
											.reduce( function (a, b) {
												return intVal(a) + intVal(b);
											}, 0 );
											$(api.column(29).footer()).html(parseFloat(roundoff).toFixed(2));
											
									} 
									}else{
										 var api = this.api(), data; 
										 //$(api.column(30).footer()).html('');
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

function getDisplayDateTime(){
    var today = new Date();

    var dispdate = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
    var disptime = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
    return dispdate + " " + disptime;
}


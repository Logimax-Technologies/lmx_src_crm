<!-- Content Wrapper. Contains page content -->
<style>
	.remove-btn{
		margin-top: -168px;
	    margin-left: -38px;
	    background-color: #e51712 !important;
	    border: none;
	    color: white !important;
	}
	.summary_lbl{
		font-weight:bold;
	}
	.stickyBlk {
	    margin: 0 auto;
	    top: 0;
	    width: 100%;
	    z-index: 999;
	    background: #fff;
	}
	.custom-label{
		font-weight: 600 !important;
	    letter-spacing: 0.5px !important;
	    text-transform: uppercase !important;
	}
	
    .form-group {
        margin-bottom: 1px;
    }
    
    
    
    *[tabindex]:focus {
    outline: 1px black solid;
    }
    
    .billType{
        padding : 3px !important;
        margin : 0px !important;
        height: auto;
    }
    
</style>
  <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <!--<section class="content-header">
          <h1>
        	Billing
            <small>Customer Billing</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Billings</a></li>
            <li class="active">Billing</li>
          </ol>
        </section>-->
        <!-- Main content -->
        <section class="content product">
          <!-- Default box -->
          <div class="box box-primary">
           
            <div class="box-body">
			<?php 
            	if($this->session->flashdata('chit_alert'))
            	 {
            		$message = $this->session->flashdata('chit_alert');
            ?>
                   <div  class="alert alert-<?php echo $message['class']; ?> alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-check"></i> <?php echo $message['title']; ?>!</h4>
                    <?php echo $message['message']; ?>
                  </div>
            <?php } ?> 
         <!-- form container -->
          <div class="row">
             <!-- form -->
			<form id="bill_pay">
				<div class="col-md-12"> 	
					
					<div align="left"  style="background: #f5f5f5">
                		<ul class="nav nav-tabs" id="billing-tab">
                	      	<li class="active"><a id="tab_items" href="#pay_items" data-toggle="tab">ITEM</a></li>
                		  	<li id="tab_tot_summary"><a href="#tot_summary" data-toggle="tab">PAYMENT SUMMARY</a></li>
                		  	<li id="tab_make_pay"><a href="#make_pay" data-toggle="tab">PAYMENT HISTORY</a></li>
                	    </ul>
                	</div>
                	<div class="tab-content">
                		<div class="tab-pane active" id="pay_items">
                		    <div class="box box-default ">

                		         <div class="box-body" align="center">
					            	    <div class="row">
                    						 <div class="col-sm-8 advance_type"> 
                    							<div class="row">				    	
                    					    		<div class="col-sm-4">
                    					    			<label>Select Karigar</label>
                    						 		</div>
                    						 		<div class="col-sm-4">
                    						 			<div class="form-group" > 
                    							 			<div class="input-group" > 
                    											<select class="form-control" id="select_karigar" name="suppay[pay_sup_id]" style="width:100%;"></select>
                    											<input type="hidden" name="suppay[bill_type]" value="1" id="bill_type" class="bill_type" />
                    										</div>
                    									</div>
                    						 		</div>
                    						 		<div class="col-sm-4">
                    					    			Payable : <span style="font-weight:bold;" class="selectedpayable"></span>
                    						 		</div>
                    						 	</div>
                    						 </div>
					            	    </div>
								</div>
        						<div class="box-body">
                			        <!-- Search Block	 -->
                					
        							<div class="row sale_details">
        								<div class="col-md-12">
        							       <p class="text-light-blue">PURCHASE DETAILS</p>
        								   <div class="table-responsive">
        									 <table id="po_pay_details" class="table table-bordered table-striped text-center" style="text-transform:uppercase;">
        										<thead>
        										  <tr>
        										    <th><input type="checkbox" class="selectAllPO" id="payAllPo" /></th>
        											<th>PO Ref</th>
        											<th>Received Pcs</th>
        											<th>Received Wt</th>  
        											<th>QC Passed Pcs</th>  
        											<th>QC Passed Wt</th>   											
        											<th>PO Cost</th>   
        											<th>Paid Amount</th>   
        										    <th>Balance Amount</th>
        										  </tr>
        										</thead> 
        										<tbody>
        										</tbody>
        									 </table>
        								  </div>
        								</div> 
        							</div> 
                				</div>
                    		</div>
                		</div>
                		<div class="tab-pane" id="tot_summary">
                		    <div class="row">
                		        <div class="col-sm-12">
        							<div class="box box-default total_summary_details">
        								<div class="box-body">
        									<div class="row">
        										<div class="col-md-6">
        										    <div class="box-header with-border">
                    								  <h3 class="box-title">Summary</h3>
                        						    </div>
        										   <div class="table-responsive">
        											 <table id="total_summary_details" class="table table-bordered table-striped" style="text-transform:uppercase;">
        												<thead>
        													<tr>
        														<th width="1%;">Payable</th>
        														<th width="5%;">Paid</th>
        														<th width="1%;">Balance</th>
        														<th width="10%;">Payment</th>
        													</tr>
        												</thead> 
        												<tbody> 
        													<tr>
        													    <td><span class="payable_amt"></span></td>
        													    <td><span class="paid_amt"></span></td>
        													    <td style="font-weight:bold;"><span class="balance_amt"></span></td>
        													    <td><input class="form-control received_amount" name="suppay[received_amount]" type="number" placeholder="Amount"></td>
        													</tr>
        													
        												</tbody>
        												<tfoot>
        													<tr></tr>
        												</tfoot>
        											 </table>
        										  </div>
        										</div>
        										<div class="col-md-6">
        										    <div class="box-header with-border">
                    								  <h3 class="box-title">Make Payment</h3>
                    								</div>
        										    <div class="table-responsive">
        										        <table id="payment_modes" class="table table-bordered table-striped">
        										            <tbody>
        										                <tr>
        															<td class="text-right">Net Banking</td>
        															<td class="text-right"><?php echo $this->session->userdata('currency_symbol')?></td>
        															<td>
        																<span id="tot_net_banking_amt"></span>
        																<a class="btn bg-olive btn-xs pull-right" id="chit_util_modal" href="#" data-toggle="modal" data-target="#netbanking-confirm-add" ><b>+</b></a> 
        																<input type="hidden"id="net_banking_pay_details" name="suppay[net_banking]" value="">
        															</td>
        														</tr>
        														<tr>
        															<td class="text-right">Sales Adjustment</td>
        															<td class="text-right"><?php echo $this->session->userdata('currency_symbol')?></td>
        															<td>
        																<span id="tot_sales_amt"></span>
        																<a class="btn bg-olive btn-xs pull-right" id="" href="#" data-toggle="modal" data-target="#sales_adjustment_add" ><b>+</b></a> 
        																<input type="hidden"id="sales_details" name="suppay[sales_details]" value="">
        															</td>
        														</tr>
        														<tr>
        															<td class="text-right">Advance Adjustment</td>
        															<td class="text-right"><?php echo $this->session->userdata('currency_symbol')?></td>
        															<td>
        																<span id="tot_chit_amt"></span>
        																<a class="btn bg-olive btn-xs pull-right" id="chit_util_modal" href="#" data-toggle="modal" data-target="#chit-confirm-add" ><b>+</b></a> 
        																<input type="hidden"id="chit_details" value="">
        															</td>
        														</tr>
        														<tr>
        															<td class="text-right">Total Amount</td>
        															<td class="text-right"><?php echo $this->session->userdata('currency_symbol')?></td>
        															<td><span id="total_pay_amount"></span></td>
        														</tr>
        														<tr>
        															<td class="text-right">Balance Amount</td>
        															<td class="text-right"><?php echo $this->session->userdata('currency_symbol')?></td>
        															<td><span id="bal_amount"></span></td>
        														</tr>
        										            </tbody>
        										        </table>
        										    </div>
        										</div>
        										
        									</div>
        								</div>
        							</div>
        						</div>
                		    </div>
                		</div>
                		<div class="tab-pane" id="make_pay">
                		    <div class="row">
        						<div class="col-sm-12">
        							<div class="box box-default payment_blk">
        								<div class="box-body">
        									<div class="row sale_details">
                								<div class="col-md-12">
                								   <div class="table-responsive">
                									 <table id="pay_history" class="table table-bordered table-striped text-center">
                										<thead>
                										  <tr>
                										    <th>PAYMENT TYPE</th>
                											<th>PAYMENT REF NO</th>
                											<th>PAYMENT DATE</th>
                											<th>PO REFERENCE</th>
                											<th>PAID AMOUNT</th> 
                										  </tr>
                										</thead> 
                										<tbody>
                										</tbody>
                									 </table>
                								  </div>
                								</div> 
                							</div>
        								</div>
        							</div>
        						</div>
        					</div>
                		</div>
                		<div class="row">
						    <div class="col-sm-12" align="center">
						        <?php if($this->uri->segment(3) != 'edit'){?>
        						<button type="button" id="po_pay_submit" class="btn btn-primary" >Save</button> 
        						<?php }?>
        						<button type="button" class="btn btn-default btn-cancel">Cancel</button>
						    </div>
						</div>
                	</div>
				 	<p></p>
				</div>	<!--/ Col --> 
			</div>	 <!--/ row -->
			   <p class="help-block"> </p>  
	            </div>  
	          <?php echo form_close();?>
	            <div class="overlay" style="display:none">
				  <i class="fa fa-refresh fa-spin"></i>
				</div>
	             <!-- /form -->
	          </div>
             </section>
        </div>
        
        

<div class="modal fade" id="netbanking-confirm-add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Net Banking Details</h4>
			</div>
			<div class="modal-body"> 
				<div class="box-body chit_details">
					<div class="row"> 
						<div class="col-sm-12 pull-right">
							<button type="button" id="create_net_banking_row" class="btn bg-olive btn-sm pull-right"><i class="fa fa-plus"></i> Add</button>
						</div>
					</div>
					<div class="row">
						<div class="box-body">
						   <div class="table-responsive">
							 <table id="net_banking_details" class="table table-bordered text-center">
								<thead>
								  <tr>
									<th>Amount</th>
									<th>Type</th>
									<th>Ref NO</th>
									<th>Action</th>
								  </tr>
								</thead> 
								<tbody>
										<tr>
											<td><input type="number" class="form-control pay_amount" type="number"/></td>
											<td>
    											<select class="form-control nb_type">
    											    <option value="RTGS">RTGS</option>
    											    <option value="NEFT">NEFT</option>
    											</select>
											</td>
											<td><input type="number" class="form-control ref_no" type="text"/></td>
											<td><a href="#" onclick="remove_net_banking_row($(this).closest('tr'))" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>
										</tr>
								</tbody>
								<tfoot>
									<tr>
										<th colspan=2>Total</th>
										<th colspan=2><span class="total_amount"></span></th>
									</tr>
								</tfoot>
							 </table>
						  </div>
						</div> 
					</div> 
				</div> 
			</div>
		  <div class="modal-footer" >
			<a id="save_net_banking" class="btn btn-success">Save</a>
			<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
		  </div>
		</div>
	</div>
</div>



<div class="modal fade" id="sales_adjustment_add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Sales Details</h4>
			</div>
			<div class="modal-body"> 
				<div class="box-body chit_details">
					<div class="row"> 
						<div class="col-sm-12 pull-right">
							<button type="button" id="create_sales_details_row" class="btn bg-olive btn-sm pull-right"><i class="fa fa-plus"></i> Add</button>
						</div>
					</div>
					<div class="row">
						<div class="box-body">
						   <div class="table-responsive">
							 <table id="bill_details" class="table table-bordered text-center">
								<thead>
								  <tr>
									<th>Bill No</th>
									<th>Amount</th>
									<th>Action</th>
								  </tr>
								</thead> 
								<tbody>
										<tr>
											<td><input type="text" class="form-control bill_no"/><input type="hidden" class="form-control bill_id"/></td>
											<td><input type="number" class="form-control payment_amount" type="text" readonly/></td>
											<td><a href="#" onclick="remove_net_banking_row($(this).closest('tr'))" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>
										</tr>
								</tbody>
								<tfoot>
									<tr>
										<th colspan=2>Total</th>
										<th colspan=2><span class="total_amount"></span></th>
									</tr>
								</tfoot>
							 </table>
						  </div>
						</div> 
					</div> 
				</div> 
			</div>
		  <div class="modal-footer" >
			<a id="save_sales_details" class="btn btn-success">Save</a>
			<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
		  </div>
		</div>
	</div>
</div>
 
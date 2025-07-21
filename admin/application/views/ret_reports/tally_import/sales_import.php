    <style>
   @media print 
   {    
        table tr td.sales
        { 
          font-weight:bold;
        }
    }
    </style> 
  <!-- Content Wrapper. Contains page content -->

      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Sales Import
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
               
               <div class="box box-primary">
			   
                 <div class="box-body">  
                  <div class="row">
				  	<div class="col-md-offset-2 col-md-8">  
	                  <div class="box box-default">  
	                   <div class="box-body">  
						   <div class="row">
						       	
						       	<div class="col-md-3">
								    <div class="form-group">
								        <label>Select Branch</label>
            		                    <div class="input-group">
            		                        <select class="form-control" id="branch_select" style="width:100%;"></select>
            		                    </div>
            		                </div>
								</div>
								
							
								
								<div class="col-md-3"> 
									 <div class="form-group">
            		                    <div class="input-group">
            		                        <br>
            		                       <button class="btn btn-default btn_date_range" id="rpt_payment_date">
            							    <span  style="display:none;" id="rpt_payments1"></span>
            							    <span  style="display:none;" id="rpt_payments2"></span>
            		                        <i class="fa fa-calendar"></i> Date range picker
            		                        <i class="fa fa-caret-down"></i>
            		                      </button>
            		                    </div>
            		                 </div><!-- /.form group -->
								</div>
								
								
								
								<div class="col-md-2"> 
									<label></label>
									<div class="form-group">
										<button type="button" id="sales_import_search" class="btn btn-info">Search</button>   
									</div>
								</div>
							</div>
						 </div>
	                   </div> 
	                  </div> 
                   </div> 
                
				  <div id="cash_abstract">
				   	<div class="box box-info sales_details" >
						<div class="box-header with-border">
						  <div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
						  </div>
						</div>
                        <div class="box-body">
                            <div class="row">
                                <div class="box-body">
                                    <div class="table-responsive old_metal" style="">
                                        <table id="sales_list" class="table table-bordered table-striped text-center sales_list" style="width:100%;">
                                            <thead style="text-transform:uppercase;">
                                            <tr>
                                                <th>INVOICEDATE</th>
                                                <th>INVOICENO</th>
                                                <th>VOUCHERTYPE</th>
                                                <th>PARTYCODE</th>
                                                <th>PARTYNAME</th>
                                                <th>PARTYGROUP</th>
                                                <th>GSTNO</th>
                                                <th>ADRESS1</th>
                                                <th>ADDRESS2</th>
                                                <th>ADDRESS3</th>
                                                <th>CONTACTNO</th>
                                                <th>PRODUCTGROUP</th>
                                                <th>PRODUCTCODE</th>
                                                <th>PRODUCTNAME</th>
                                                <th>QTY</th>
                                                <th>RATE</th>
                                                <th>VALUE</th>
                                                <th>SALESTAX</th>
                                                <th>SALESTAXAMT</th>
                                                <th>SERVICEAMT</th>
                                                <th>WEIGHT</th>
                                                <th>TOTAL</th>
                                                <th>REMARKS</th>
                                                <th>GROSSWT</th>
                                                <th>NETWT</th>
                                                
                                                </tr>
                                            </thead>
                                            <tbody ></tbody>
                                        </table>
                                    </div>
                                 </div> 
                            </div> 
                        </div>
					</div>
					</div>


				   	<div class="box box-info purchase_details" style="display: none;">
						<div class="box-header with-border">
						  <h3 class="box-title">Purchase Details</h3>
						  <div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
						  </div>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="box-body">
								   <div class="table-responsive">
									  <table id="purchase_list" class="table table-bordered table-striped text-center">
						                    <thead>
											  <tr>
											    <th width="15%">Metal</th>                          
											    <th width="15%">Metal</th>                                
											    <th width="15%">Gross Wt</th>                               
						                        <th width="10%">Net Wt</th>
						                        <th width="10%">Amount</th>
						                        <th width="10%">Avg Rate</th>
											  </tr>
						                    </thead>
						                    <tfoot><tr><th></th><th></th><th></th><th></th><th></th><th></th></tr></tfoot>
						               </table>
								  </div>
								</div> 
							</div> 
						</div>
					</div>
                </div><!-- /.box-body -->
                <div class="overlay" style="display:none">
				  <i class="fa fa-refresh fa-spin"></i>
				</div>
              </div>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      

<!-- CHIT DEPOSIT -->
<div class="modal fade" id="stone_modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg">
	<div class="modal-content">
	    <div class="modal-header">
			<h4 class="modal-title" id="myModalLabel">Stone Details</h4>
		</div>
		<div class="modal-body">
			<div>
			<table id="stone_details" class="table table-bordered table-striped text-center">
				<thead>
				<tr>
				<th>Stone Name</th>
				<th>Stone Pcs</th>
				<th>Weight</th>
				<th>Rate</th>
				<th>Amount</th>
				</tr>
				</thead> 
				<tbody>
				</tbody>										
			</table>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
		</div>
	</div>
</div>
</div>
<!-- CHIT DEPOSIT -->
 <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Payment Report
            <small></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Reports</a></li>
            <li class="active">scheme wise Payment  Report</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
           
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Scheme wise Payment Report </h3>      
                <!--           <a class="btn btn-success pull-right" href="<?php echo base_url('index.php/account/add'); ?>"><i class="fa fa-user-plus"></i> Add</a>--> 
                	
                	<div class="col-md-12">
	                	<div class="form-group col-md-offset-3 col-md-2">
			                <label for="report_date">Report Date</label>
			            </div>
			            <div class="col-md-3">
			                	<input class="form-control datemask"  data-date-format="dd-mm-yyyy" id="schwisereport_date" name="customer[report_date]" value="<?php echo date('d-m-Y'); ?>" placeholder="Report date" type="text" />
			            </div>
	                </div>
                </div><!-- /.box-header -->
                
                <div class="box-body">
                <!-- Alert -->
                <?php 
                	if($this->session->flashdata('chit_alert'))
                	 {
                		$message = $this->session->flashdata('chit_alert');
                ?>
                       <div class="alert alert-<?php echo $message['class']; ?> alert-dismissable">
	                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	                    <h4><i class="icon fa fa-check"></i> <?php echo $message['title']; ?>!</h4>
	                    <?php echo $message['message']; ?>
	                  </div>
	                  
	            <?php } ?>      
                   <div class="table-responsive">
                  <table id="payschcoll_data" class="table table-bordered table-striped text-center date_pay_report"  >
                    <thead>
                      <tr>
                        <th>Scheme Name</th>
                        <th>Opening</th>                        
                        <th>Collection</th>                        
                        <th>Coll GST</th>                        
                        <th>Paid</th>                        
                        <th>Return</th>                        
                        <th>Charge</th>        
                        <th>Closing Amt</th> 
						<th>Amt GST</th>        						
                        <th>Amount</th>
                        <th>Incentive</th>                        
                                              
                        					                      
                      </tr>
                    </thead>
					 <tfoot>
					<th>Total</th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th>
					 </tfoot>
                  </table>
                  
                </div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      


<!-- modal -->      

<!-- / modal -->      


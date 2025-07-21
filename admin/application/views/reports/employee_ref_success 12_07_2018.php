




<style type="text/css">
.DTTT_container{
margin-bottom:0 !important;
}
</style>
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
            <li class="active">Payment Report</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
           
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Employee Refferal Report</h3>      
                <!--           <a class="btn btn-success pull-right" href="<?php echo base_url('index.php/account/add'); ?>"><i class="fa fa-user-plus"></i> Add</a>--> 
					<!--	 <div class="col-sm-12">
							<div class="col-md-6">
								<div class="col-md-4">
									<div class="form-group">	
										<label for="" ><a  data-toggle="tooltip" title="Select branch "> Select Branch  </a> <span class="error">*</span></label>
										<select id="branch_select" class="form-control"></select>
														
											<input id="id_branch" name="account[id_branch]" type="hidden" value="" />	
									</div>
								</div>
							</div>
						</div>-->
				
				
				
				
				
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
	                  
	            <?php } ?>       <div class="row">
	                 
	                 	<div class="col-sm-12">
	                 		         	 <!-- Date and time range -->
		                  <div class="form-group">
		                    <div class="input-group">
		                       <button class="btn btn-default btn_date_range" id="rpt_payment_date">
		                        <i class="fa fa-calendar"></i> Date range picker
		                        <i class="fa fa-caret-down"></i>
		                      </button>
		                    </div>
		                 </div><!-- /.form group -->
		                 </div>
	                 </div>
                <div class="table-responsive">
                  <table  id="employee_refferal" class="table table-bordered table-striped text-center employee_refferals " >
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Referral Code</th>                     
                        <th>No of Referred </th>
                        <!-- <th>Type</th>-->
                        <th>Benefit Amount</th>
                           <!--   <th>Action</th> -->
                      </tr>
                    </thead>
                     <tfoot>
                <th></th> <th></th> <th></th> <th></th><th></th>
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


<style>
@media print {

    html,
    body {
        height: auto;
        width: 100vh;
        margin: 0 !important;
        padding: 0 !important;
        overflow: hidden;
    }
}
	</style>


	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
	    <!-- Content Header (Page header) -->
	    <section class="content-header">
	        <h1>
	            Advance Receipt Report
	        </h1>
	        <ol class="breadcrumb">
	            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
	            <li><a href="#">Accounts Report</a></li>
	            <li class="active">Advance Receipt Report</li>
	        </ol>
	    </section>

	    <!-- Main content -->
	    <section class="content">
	        <div class="row">
	            <div class="col-xs-12">

	                <div class="box box-primary">
	                    <div class="box-header with-border">
	                        <span id="advance_list_total_count" class="badge bg-green"></span>

	                    </div>
	                    <div class="box-body">
	                        <div class="row">
	                            <div class="col-md-offset-2 col-md-8">
	                                <div class="box box-default">
	                                    <div class="box-body">
	                                        <div class="row">
	                                            <?php if($this->session->userdata('branch_settings')==1 && $this->session->userdata('id_branch')==0){?>
	                                            <div class="col-md-3">
	                                                <div class="form-group tagged">
	                                                    <label>Select Branch</label>
	                                                    <select id="branch_select"
	                                                        class="form-control branch_filter" multiple></select>
	                                                </div>
	                                            </div>
	                                            <?php }else{?>
	                                            <input type="hidden" id="branch_filter"
	                                                value="<?php echo $this->session->userdata('id_branch') ?>">
	                                            <input type="hidden" id="branch_name"
	                                                value="<?php echo $this->session->userdata('branch_name') ?>">
	                                            <?php }?>

	                                            <div class="col-md-3">
	                                                <div class="form-group">
	                                                    <button class="btn btn-default" id="advance_list_report_date"
	                                                        style="margin-top: 20px;">
	                                                        
	                                                        <i class="fa fa-calendar"></i> Date range picker
	                                                        <i class="fa fa-caret-down"></i>
	                                                    </button>
														<span style="display:none;"
	                                                            id="advance_list_report_date1"></span>
	                                                        <span style="display:none;"
	                                                            id="advance_list_report_date2"></span>
	                                                </div>
	                                            </div>
	                                            <!--<div class="col-md-3"> 
									<div class="form-group tagged">
										<label>Report Type</label>
										<select id="report_tpe" class="form-control">
											<option value="1">Bill Wise</option>
											<option value="2">Date Wise</option>
										</select>
									</div> 
								</div> -->
	                                            <div class="col-md-2">
	                                                <label></label>
	                                                <div class="form-group">
	                                                    <button type="button" id="advance_list_search"
	                                                        class="btn btn-info">Search</button>
	                                                </div>
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>

	                        <div class="row">
	                            <div class="col-xs-12">
	                                <!-- Alert -->
	                                <?php 
							if($this->session->flashdata('chit_alert'))
							 {
								$message = $this->session->flashdata('chit_alert');
						?>
	                                <div class="alert alert-<?php echo $message['class']; ?> alert-dismissable">
	                                    <button type="button" class="close" data-dismiss="alert"
	                                        aria-hidden="true">&times;</button>
	                                    <h4><i class="icon fa fa-check"></i> <?php echo $message['title']; ?>!</h4>
	                                    <?php echo $message['message']; ?>
	                                </div>

	                                <?php } ?>
	                            </div>
	                        </div>

	                        <div class="row" id="bill_wise_report">
	                            <div class="col-md-12">
	                                <div class="table-responsive">
	                                    <table id="advance_list" class="table table-bordered table-striped text-center">
	                                        <thead>
	                                            <tr>
	                                                <th>Bill No</th>
	                                                <th>Bill Date</th>
	                                                <th>Customer</th>
	                                                <th>Mobile</th>
	                                                <th>Branch</th>
	                                                <th>Amount</th>

	                                            </tr>
	                                        </thead>
	                                        <tbody>
	                                        </tbody>

	                                    </table>
	                                </div>
	                            </div>
	                        </div>

	                        <!-- <div class="row" id="date_wise_report" style="display: none;">
	                   <div class="col-md-12">
	                   	<div class="table-responsive">
		                 <table id="date_wise_list" class="table table-bordered table-striped text-center">
		                    <thead>
							  <tr>
							    <th>Branch</th>
							    <th>Bill Date</th>
							    <th>Credit Card</th>
							    <th>Debit Card</th>
							  </tr>
		                    </thead> 
		                     <tbody> 
	                    </tbody>
							   
		                 </table>
	                  </div>
	                   </div>
                   </div>-->

	                    </div><!-- /.box-body -->
	                    <div class="overlay" style="display:none">
	                        <i class="fa fa-refresh fa-spin"></i>
	                    </div>
	                </div>
	            </div><!-- /.col -->
	        </div><!-- /.row -->
	    </section><!-- /.content -->
	</div><!-- /.content-wrapper -->
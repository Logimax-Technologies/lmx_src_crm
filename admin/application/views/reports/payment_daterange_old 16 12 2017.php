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
            <li class="active">Payment List</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
           
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">All Scheme</h3> <span id="total" class="badge bg-green"></span>     
                         
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
	                 <div class="row">
	                 
	                 <div class="col-md-12">
	                 	<div class="col-md-2">
	                 		         	 <!-- Date and time range -->
		                  <div class="form-group">
		                    <div class="input-group">
		                       <button class="btn btn-default btn_date_range" id="rpt_payment_date">
							  <!-- <input id="rpt_payments"  name="rpt_payment" type="hidden" value="" />-->
							    <span  style="display:none;" id="rpt_payments1"></span>
							    <span  style="display:none;" id="rpt_payments2"></span>
		                        <i class="fa fa-calendar"></i> Date range picker
		                        <i class="fa fa-caret-down"></i>
		                      </button>
		                    </div>
		                 </div><!-- /.form group -->
		                </div>
						
						   <div class="col-md-4">
								<div class="form-group" >
									<label>Scheme name</label>									
									<select id="scheme_select" class="form-control" style="width:200px; margin-left: 15px !important;"></select>
									<input id="id_schemes"  name="id_scheme" type="hidden" value="" />
								</div>
						   </div>
						   
						  <?php if($this->session->userdata('branch_settings')==1){?>
							<div class="col-md-5">
									<div class="form-group" >
									<label>Select Branch </label>
									<select id="branch_select" class="form-control" style="width:200px;" ></select>
									<input id="id_branch" name="scheme[id_branch]" type="hidden" value=""/>
								</div>
							</div>
							<?php }?>
		                 </div>
	                 </div>
					 
					 
					 
                      <table id="report_payment_daterange" class="table table-bordered table-striped text-center ">
	                    <thead>
	                      <tr>
							<th>S.No</th>							
	                        <th>Group</th>
							<th>M.No</th>
							<th>Amount</th>
							<th>Ins</th>
							<th>PayDate</th>
							<th>Mode</th>
							<th>Weight(g)</th>
							<th>B.Name</th>
							<th>Payamt</th>
							<th>Incen</th> 		
							<th>SGST</th>							
							<th>CGST</th>							
							<th>T.GST</th>							
							<th>Total</th> 
	                      </tr>
	                    </thead>
						<tfoot>
							<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
							<!--<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>	-->					
						</tfoot> 
					<tbody> 
					</tbody>
	               </table>
                </div>
				  <div class="overlay" style="display:none">
				  <i class="fa fa-refresh fa-spin"></i>
				  </div>
				<!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

<!-- / modal -->  
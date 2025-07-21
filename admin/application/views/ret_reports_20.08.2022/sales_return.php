<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Reports
			 <small>Sales Return</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Reports</a></li>
            <li class="active">Sales Return</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
               
               <div class="box box-primary">
			    <div class="box-header with-border">
                   <?php if($this->session->userdata('branch_settings')==1 && $this->session->userdata('id_branch')==0){?>
		                  <div class="col-md-3"> 
		                     <div class="form-group tagged">
									<select id="branch_filter" class="form-control ret_branch"></select>
		                     </div> 
		                  </div> 
						    <?php }else{?>
		                     <input type="hidden" id="branch_filter"  value="<?php echo $this->session->userdata('id_branch') ?>"> 
		                     <input type="hidden" id="branch_name"  value="<?php echo $this->session->userdata('branch_name') ?>"> 
		                  <?php }?>
		                  <div class="col-md-3">
		                     <div class="form-group">
									<select id="filter_metal" class="form-control">
									<option value=""></option>
									<option value="1">Gold</option>
									<option value="2">Silver</option>
									</select>
		                     </div>
		                  </div>
						  <div class="col-md-3"> 
		                     <div class="form-group">    
		                   		  <?php   
									$fromdt = date("d/m/Y", strtotime('-0 days'));
									$todt = date("d/m/Y");
								  ?>
		                   		  <input type="text" class="form-control pull-right dateRangePicker" id="dt_range" placeholder="From Date -  To Date" value="<?php echo $fromdt.' - '.$todt?>" readonly="">  
		                     </div>
		                  </div> 
						   <div class="col-md-1 pull-right"> 
		                     <div class="form-group">
				                <button type="button" id="sales_return_search" class="btn btn-info">Search</button>   
		                    </div>
		                  </div>
                 
                </div>
                 <div class="box-body">  
				   <div class="row">
						<div class="col-xs-12">
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
						</div>
				   </div>
			  
                  <div class="table-responsive">
	                 <table id="sales_return_report" class="table table-bordered table-striped text-center">
	                    <thead>
	                      <tr>
	                        <th width="10%">Branch</th>
	                        <th width="10%">Bill No</th>
	                        <th width="5%">Bill Date</th>
	                        <th width="5%">Emp Name</th>							
                            <th width="5%">Emp Code</th>
	                        <th width="5%">Tag Code</th>
	                        <th width="5%">Product</th>
	                        <th width="5%">Design</th>
	                        <th width="5%">Sub Design</th>
	                        <th width="10%">Gross Wgt</th>                                       
                            <th width="10%">Net Wgt</th> 
                            <th width="10%">Wastage</th>
                            <th width="10%">Amount</th>
                            <th width="10%">Customer</th>
                            <th width="10%">Process Status</th>
	                      </tr>
	                    </thead> 
	                    <tbody> 
	                    </tbody>
                        <tfoot>
                        <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                        </tfoot>
	                 </table>
                  </div>
                </div><!-- /.box-body -->
                <div class="overlay" style="display:none">
				  <i class="fa fa-refresh fa-spin"></i>
				</div>
            
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      



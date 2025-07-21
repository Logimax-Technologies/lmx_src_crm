  <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Reports
			 <small>Branch Transfer</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Reports</a></li>
            <li class="active">Branch Transfer</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
               <div class="box box-primary">
			    <div class="box-header with-border">
                  <h3 class="box-title">Branch Transfer List</h3>  <span id="total_count" class="badge bg-green"></span>  
                </div>
                 <div class="box-body">  
                  <div class="row">
				  <div class="col-md-offset-3 col-md-6">  
	                  <div class="box box-default">  
	                   <div class="box-body">  
					   <div class="row">
					   	  <?php if($this->session->userdata('branch_settings')==1 && $this->session->userdata('id_branch')==0){?>
		                  <div class="col-md-4"> 
		                     <div class="form-group tagged">
		                       <label>Select Branch</label>
									<select id="branch_filter" class="form-control ret_branch"></select>
		                     </div> 
		                  </div> 
						    <?php }else{?>
		                     <input type="hidden" id="branch_filter"  value="<?php echo $this->session->userdata('id_branch') ?>"> 
		                  <?php }?>
						  <div class="col-md-offset-1 col-md-4"> 
		                     <div class="form-group">    
		                   		  <label>Date</label> 
		                   		  <?php   
									$fromdt = date("d/m/Y", strtotime('-7 days'));
									$todt = date("d/m/Y");
								  ?>
		                   		  <input type="text" class="form-control pull-right dateRangePicker" id="dt_range" placeholder="From Date -  To Date" value="<?php echo $fromdt.' - '.$todt?>" readonly="">  
		                     </div>
		                  </div> 
						   <div class="col-md-3"> 
						   	 <label></label>
		                     <div class="form-group">
				                <button type="button" id="bt_report_search" class="btn btn-info pull-right">Search</button>   
		                    </div>
		                  </div>
						   </div>
	                   </div> 
	                  </div> 
                   </div> 
                
                </div>
                <p></p>
                
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
	                 <table id="bt_report" class="table table-bordered table-striped text-center">
	                    <thead>
	                      <tr>
	                        <th width="5%">BT Date</th> 
	                        <th width="5%">BT Id</th>       
	                        <th width="5%">BT Code</th>                                     
	                        <th width="5%">From Branch</th>                                     
	                        <th width="5%">To Branch</th>                                     
	                        <th width="5%">Product</th>                                     
	                        <th width="5%">Pieces</th>  
	                        <th width="5%">Gross Wt</th>  
                            <th width="5%">Net Wt</th> 
                            <th width="15%">Status</th> 
                            <th width="15%">Approved Date</th> 
                            <th width="15%">Download Date</th> 
                            <th width="15%">Approved By</th> 
                            <th width="15%">Download By</th> 
	                      </tr>
	                    </thead> 
						   <tfoot>
	                    	<tr> 
	                    		<td></td> <td></td> <td></td> <td></td> <td></td> <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
	                    	</tr>
	                    </tfoot>
	                 </table>
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
      



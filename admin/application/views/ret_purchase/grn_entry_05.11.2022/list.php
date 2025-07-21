  <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
       
        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
               
               <div class="box box-primary">
			    <div class="box-header with-border">
                  <h3 class="box-title">GRN Entry List</h3>  <span id="total_count" class="badge bg-green"></span>  
                  <div class="pull-right">
                  	 <a class="btn btn-success pull-right" id="add_Order" href="<?php echo base_url('index.php/admin_ret_purchase/grnentry/add');?>" ><i class="fa fa-plus-circle"></i> Add</a> 
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
	                 <table id="grn_list" class="table table-bordered table-striped text-center">
	                    <thead>
	                      <tr>
	                        <th width="1%;">#</th>
	                        <th width="5%;">Ref No</th>
	                        <th width="5%;">Date</th>   
	                        <th width="5%;">Supplier</th>   
	                        <th width="5%;">Mobile</th>   
	                        <th width="5%;">IRN NO</th>     
	                        <th width="5%;">Amount</th>     
	                        <th width="5%;">Status</th>     
	                        <th width="10%;">Action</th>
	                      </tr>
	                    </thead> 
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
      


<!-- modal -->      
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Cancel GRN Entry</h4>
        <input type="hidden" id="grn_id">
      </div>
      <div class="modal-body">
               <strong>Are you sure! You want to Cancel this Entry?</strong>
      </div>
      
      <div class="col-md-12 bill_remarks">
        <label>Remarks<span class="error">*</span></label>
        <textarea class="form-control" id="cancel_remark" placeholder="Enter Remarks"  rows="5" cols="10"> </textarea>
     </div>
                
      <div class="modal-footer">
      	<button type="button" id="grn_cancel" class="btn btn-danger btn-confirm" data-dismiss="modal" disabled>Cancel</button>
        <button type="button" class="btn btn-warning btn-cancel" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- / modal -->      

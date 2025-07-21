<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
           Stock Issue
            <small>Stock Issue List</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Stock Issue List</a></li>
            <li class="active">Stock Issue List</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
               
               <div class="box box-primary">
			    <div class="box-header with-border">
                  <h3 class="box-title">Stock Issue List</h3>  <span id="total_count" class="badge bg-green"></span>  
                  <div class="pull-right">
                  	 <a class="btn btn-success pull-right" id="add_Order" href="<?php echo base_url('index.php/admin_ret_stock_issue/stock_issue/add');?>" ><i class="fa fa-plus-circle"></i> Add</a> 
				  </div>
                </div>
                 <div class="box-body"> 
                                     
                   <div class="row">
                   <div class="col-md-2"> 
                    <div class="form-group tagged">
                    <label>Status</label>
                      <select id="issue_status" class="form-control">
                                  <option value="0">All</option>
                                  <option value="1">Issued</option>
                                  <option value="2">Rejected</option>
                                  <option value="3">Recieved</option>
                              </select>
                            </div> 
                          </div> 
        
                          <div class="col-md-2"> 
                            <label></label>
                            <div class="form-group">
                              <button type="button" id="issue_status_search" class="btn btn-info">Search</button>   
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
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								<h4><i class="icon fa fa-check"></i> <?php echo $message['title']; ?>!</h4>
								<?php echo $message['message']; ?>
							  </div>
							  
						<?php } ?>  
						</div>
				   </div>
			  
                  <div class="table-responsive">
	                 <table id="issue_list" class="table table-bordered table-striped text-center">
	                    <thead>
	                      <tr>
	                        <!-- <th>#</th>
	                        <th>Issue No</th>
	                        <th>Status</th>
	                        <th>Branch</th>
	                        <th>Issue Date</th>   
	                        <th>Issue Type</th>                                   
	                        <th>Issued By</th>                                       
	                        <th>Action</th>   
                            -->
                          <th>#</th>
	                        <th>Issue No</th>
	                        <th>Branch</th>
                          <th>Tag Code</th>
                          <th>Category Name</th>
	                        <th>Issue Date</th>   
	                        <th>Issue Type</th>                                   
	                        <th>Issued By</th>  
                          <th></th>                                                                            
	                        <th>Action</th>         
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
        <h4 class="modal-title" id="myModalLabel">Delete Order</h4>
      </div>
      <div class="modal-body">
               <strong>Are you sure! You want to delete this Order?</strong>
      </div>
      <div class="modal-footer">
      	<a href="#" class="btn btn-danger btn-confirm" >Delete</a>
        <button type="button" class="btn btn-warning btn-cancel" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- / modal -->  
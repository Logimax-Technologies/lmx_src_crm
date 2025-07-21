  <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
           Master
            <small>Repair Order</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Master</a></li>
            <li class="active">Repair Order</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
               <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Repair Order List</h3>  <span id="total_count" class="badge bg-green"></span>  
                </div>
                 <div class="box-body">  
                <p></p>
                <div class="row"> 
	                <div class="col-md-10">
  	                    <div class="box box-black "> 
              						  <div class="box-body">
                                <div class="row"> 
                                  <?php if($this->session->userdata('branch_settings')==1 && $this->session->userdata('id_branch')==0){?>
                                  <div class="col-md-2"> 
                                    <div class="form-group">
                                      <label>Select Branch</label>
                                      <select id="branch_select" class="form-control ret_branch" style="width:100%;"></select>
                                        <input type="hidden" id="branch_filter"  value="">
                                    </div> 
                                  </div> 
                                  <?php }else{?>
                                  <input type="hidden" id="branch_filter"  value="<?php echo $this->session->userdata('id_branch') ?>">
                                  <input type="hidden" id="branch_name"  value="<?php echo $this->session->userdata('branch_name') ?>"> 
                                  <?php }?>
                                  
                                  <div class="col-md-2"> 
									 <div class="form-group">
            		                    <div class="input-group">
            		                       <label>Select Order Date</label>
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
									<div class="form-group tagged">
										<label>Order Status</label>
										<select id="order_status" class="form-control"></select>
									</div> 
								</div> 
								
								<div class="col-md-2">
                                      <div class="form-group">
                                          <label></label>
                                          <button type="button" class="btn btn-warning" id="repair_order_search" style="margin-top: 20px;">Search</button>
                                      </div>
                                  </div> 
								
                                  <div class="col-md-2">
                                      <div class="form-group">
                                          <label></label>
                                          <button type="button" class="btn btn-success" id="repair_order_status" style="margin-top: 20px;">Complete</button>
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
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								<h4><i class="icon fa fa-check"></i> <?php echo $message['title']; ?>!</h4>
								<?php echo $message['message']; ?>
							  </div>
							  
						<?php } ?>  
						</div>
				   </div>
			  
                  <div class="table-responsive">
	                 <table id="repair_order_list" class="table table-bordered table-striped text-center">
	                    <thead>
	                      <tr>
	                        <th><label class="checkbox-inline"><input type="checkbox" id="select_all" name="select_all" value="all"/>All</label></th>
                                <th>Order From</th>
                                <th>Current Branch</th>
                                 <th>Order Date</th>
                                <th>Order No</th>
                                <th>Bill No</th>
                                <th>Customer</th>                                     
                                <th>Employee</th>                                       
                                <th>Product</th>                                       
                                <th>Design</th>
                                <th>Image</th>
                                <th>Order Weight</th>
                                <th>Completed Weight</th>
                                <th>Amount</th>
                                <th>Status</th>
                               
                                <th>Karigar</th>
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

<!-- modal -->      
<div class="modal fade" id="confirm-view" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Order Details</h4>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
        <input type="hidden" id="id_orderdetails"  name="">
        <a href="#" class="btn btn-success btn-confirm" id="reason_submit" >Submit</a>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="image-view" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Order Images</h4>
      </div>
      <div class="modal-body">
             <div id="imagePreview"></div>  
      </div>
    
    </div>
  </div>
</div>
<!-- / modal -->    
<div class="modal fade" id="imageModal_bulk_edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
          aria-hidden="true" data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog" style="width:90%;">
              <div class="modal-content">
                  <div class="modal-header">
                      <h4 class="modal-title" id="myModalLabel">Image Preview</h4>
                  </div>
                  <div class="modal-body">
					  <div class="row">
                      	<div id="order_images" style="margin-top: 2%;"></div>
					  </div>
                  </div>
                  <div class="modal-footer">
                      </br>
                      <button type="button" id="close_stone_details" class="btn btn-warning"
                          data-dismiss="modal">Close</button>
                  </div>
              </div>
          </div>
      </div>  

  <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
           Payment 
            <small>Manage your offline payments</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo site_url('admin/dashboard');?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo site_url('payment/list');?>">Payment</a></li>
            <li class="active">Payment List</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
         
          <div class="row">
            <div class="col-xs-12">
           
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Payment List</h3> <span id="total_payments" class="badge bg-green"></span>    
                  <div class="pull-right">
                    <?php if(($this->config->item('showToAdminsOnly') == 1 && $this->session->userdata('profile') <=2) || $this->config->item('showToAdminsOnly') == 0){?> 
                 
    <a class="btn btn-primary" href="<?php echo base_url('index.php/admin_payment/insertTransInPayment'); ?>"><i class="fa fa-retweet"></i> Sync Offline Payments</a>
					 &nbsp;&nbsp;<button type="button"  id="revert_approval" class="btn btn-warning"><i class="fa fa-user-plus" ></i>   Revert Approval</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					  <?php } ?>
                  	 <a class="btn btn-success" id="add_post_payment" href="<?php echo base_url('index.php/payment/add');?>" ><i class="fa fa-plus-circle"></i> Add</a> 
				    
				  </div>
                </div>
                    
                 
                <div class="box-body">
                <!-- Alert -->
				
				<div class="row">

				   <div class="col-sm-8 col-sm-offset-2">

						<div id="error-msg"></div>

						<div id="payment_container"></div>


					</div>
				</div>
				
				
				
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
		
		                   </br> <div class="row">
								<div class="col-md-12">
								    <?php if($this->payment_model->entry_date_settings()==1){?>	
    								<div class="col-md-2">
    									<div class="form-group">
    									   <label>Filter Date By</label>
    										<select id="date_Select" class="form-control">
    										    <option value=1 selected>Payment Date</option>
    										     <option value=2>Entry Date</option>
    										</select>
    										<input id="id_type" name="scheme[id_type]" type="hidden" value=""/>
    									</div>
    							    </div>
    							    <?php }else{?>
    							        <input id="id_type" name="scheme[id_type]" type="hidden" value=""/>
    							    <?php }?> 
							    
									<div class="col-md-2">
									    <br/>
										<div class="form-group">
										   <button class="btn btn-default btn_date_range" id="payment-dt-btn"> 
											<span  style="display:none;" id="payment_list1"></span>
											<span  style="display:none;" id="payment_list2"></span>
											<i class="fa fa-calendar"></i> Date range picker
											<i class="fa fa-caret-down"></i>
											</button>
										</div>					
									</div> 					  							
								    <div class="col-md-2">
    							         <?php if($this->session->userdata('branch_settings')==1){?>				
    										<div class="form-group">
    										    <label>Filter By Branch</label>
    											<select id="branch_select" class="form-control"></select>
    											<input id="id_branch" name="scheme[id_branch]" type="hidden" value=""/>
    										</div>
    							       <?php }?>
    							    </div>	
									<div class="col-md-2">
    							         <?php if($this->session->userdata('branch_settings')==1){?>
    										<div class="form-group">
    										    <label>Filter By Employee</label>
    											<select id="employee_select" class="form-control" ></select>
    											<input id="id_employee" name="scheme[id_employee]" type="hidden" value=""/>
    										</div>
    							       <?php }?>
    							    </div>	
    							    <div class="col-md-2">
    							        <br/>
    							        <a class="btn bg-aqua pull-right" target="_blank" href="<?php echo base_url('index.php/payment/pay_list');?>" ><i class="fa fa fa-search"></i> Search transactions</a>
				                    </div>
				                    <?php if($this->payment_model->get_rptnosettings()==0){?>
    				                    <br/>
        								<div class="col-md-2"> 
    										<div class="form-group">
    										   <button type="button"  id="conform_save" class="btn btn-primary pull-right conform_recpt"><i class="fa fa-user-plus"></i>Update Receipt</button>
    										</div> 
        							    </div>
    							    <?php }?>
						   </div>
					  </div></br>
		
						   
				<?php if($this->payment_model->get_rptnosettings()==1 || $this->payment_model->get_rptnosettings()==2){?>		   
                  <div class="table-responsive">
	                 <table id="payment_list" class="table table-bordered table-striped text-center">
	                    <thead>
	                      <tr>
	                        <th>ID</th>
	                         <?php if($this->payment_model->entry_date_settings()==1){?>	
	                        <th>Entry Date</th>
	                        <?php }else{?>
	                        <th>Payment Date</th>
	                        <?php }?>
	                        <th>Customer</th>
	                        <th>A/c Name</th>
	                        <th>Scheme code</th>
	                        <th>A/c No</th>
	                        <th>Mobile</th> 
	                        <th>Total Paid Instal.</th>
	                        <th>Type</th>                                           
	                        <th>Mode</th>                                           
	                        <th>Metal Rate (<?php echo $this->session->userdata('currency_symbol');?>)</th>  
	                        <th>Metal Weight(g)</th>                                           
	                        <th>Amount (<?php echo $this->session->userdata('currency_symbol');?>)</th>   
	                        <th>Ref No</th>                                           
	                        <th>Status</th> 
								
	                        <th>Action</th>
							  <th>Employee Code</th> 
							  <th>Added Through</th>
	                      </tr>
	                    </thead> 

	                 </table>
                  </div>
				  
				<?php }else{?>
				
					 <div class="table-responsive">
	                 <table id="payment_list" class="table table-bordered table-striped text-center">
	                    <thead>
	                      <tr>						  
						    <th><label class="checkbox-inline"><input type="checkbox" id="select_recpt"  name="select_all" value="all"/>All</label></th>
	                        <th>ID</th>
	                        <?php if($this->payment_model->entry_date_settings()==1){?>	
	                        <th>Entry Date</th>
	                        <?php }else{?>
	                        <th>Payment Date</th>
	                        <?php }?>
	                        <th>Customer</th>
	                        <th>A/c Name</th>
	                        <th>Scheme code</th>
	                        <th>A/c No</th>
	                        <th>Mobile</th> 
	                        <th>Total Paid Instal.</th>
	                        <th>Type</th>                                           
	                        <th>Mode</th>                                           
	                        <th>Metal Rate (<?php echo $this->session->userdata('currency_symbol');?>)</th>  
	                        <th>Metal Weight(g)</th>                                           
	                        <th>Amount (<?php echo $this->session->userdata('currency_symbol');?>)</th>   
	                        <th>Ref No</th> 
                            <th>Receipt No</th>    							
	                        <th>Status</th>                                           
	                                                             
	                        <th>Action</th>
							 <th>Employee Code</th> 
							 <th>Added Through</th>
	                      </tr>
	                    </thead> 

	                 </table>
                  </div>
				
				
				
				
				
				
				<?php }?>
				  
				  <label>Note:&nbsp;Last 7 days Payment List</label>
                </div><!-- /.box-body -->
                <div class="overlay" style="display:none">
				  <i class="fa fa-refresh fa-spin"></i>
				</div>
              </div><!-- /.box -->
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
        <h4 class="modal-title" id="myModalLabel">Delete Payment</h4>
      </div>
      <div class="modal-body">
               <strong>Are you sure! You want to delete this payment?</strong>
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

<div class="modal fade" id="pay_detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

  <div class="modal-dialog">

    <div class="modal-content">

      <div class="modal-header bg-yellow">

        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

        <h4 class="modal-title" id="myModalLabel" align="center">Transaction Detail</h4>

      </div>

      <div class="modal-body">

    	       

           <div class="trans-det"></div>    

      </div>

      <!--<div class="modal-footer">

      	<div class="col-sm-6 col-sm-offset-3">

          <button type="button" class="btn btn-block btn-warning" data-dismiss="modal">Close</button>

        </div>

      </div>-->

    </div>

  </div>

</div>

<!-- / modal -->   
   <script type="text/javascript">

    var showExport ="<?php echo ((($this->config->item('showToAdminsOnly') == 1 && $this->session->userdata('profile') <=2) || $this->config->item('showToAdminsOnly') == 0)?1 : 0); ?>";     
    
  </script>


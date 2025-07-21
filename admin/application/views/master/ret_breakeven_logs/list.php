<style>
	#brevn_items input{width:100%}
</style>
 <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
			<h1>
				Breakeven Logs
				<small></small>
			</h1>
			<ol class="breadcrumb">
				<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
				<li><a href="#">Masters</a></li>
				<li class="active">Breakeven Logs</li>
			</ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
           
              <div class="box box-primary">
              <div class="box-header with-border">
					<h3 class="box-title">Breakeven Logs</h3> 
                  <?php if($access['add']==1){?>
                    <a class="btn btn-success pull-right" id="add_breakeven" href="#" data-toggle="modal" data-target="#confirm-add" ><i class="fa fa-user-plus"></i> Add</a> 
                    <?php }?>
                  </div><!-- /.box-header -->
				
                <div class="box-body">
                <!-- Alert -->
                <?php 
                	if($this->session->flashdata('chit_alert'))
                	 {
                		$message = $this->session->flashdata('chit_alert');
                ?>
                       <div  class="alert alert-<?php echo $message['class']; ?> alert-dismissable">
	                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	                    <h4><i class="icon fa fa-check"></i> <?php echo $message['title']; ?>!</h4>
	                    <?php echo $message['message']; ?>
	                  </div>
	                  
	            <?php } ?>  
				  <div class="row">
                        <?php if($this->session->userdata('branch_settings')==1 && $this->session->userdata('id_branch')==0){?>
                        <div class="col-md-2"> 
    	                     <div class="form-group tagged">
    	                       <label>Select Branch</label>
    								<select id="branch_select" class="form-control ret_branch" style="width:100%;"></select>
    	                     </div> 
    	                  </div> 
        		        <?php }else{?>
        		            <input type="hidden" id="branch_filter"  value="<?php echo $this->session->userdata('id_branch') ?>">
        		            <input type="hidden" id="branch_name"  value="<?php echo $this->session->userdata('branch_name') ?>"> 
        		        <?php }?>
                        <div class="col-md-2"> 
                            <div class="form-group"><br>
                                <div class="input-group">
                                    <button class="btn btn-default" id="rpt_payment_date">
                                        <span  style="display:none;" id="fl_date1"></span>
                                        <span  style="display:none;" id="fl_date2"></span>
                                        <i class="fa fa-calendar"></i> Date range picker
                                        <i class="fa fa-caret-down"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-2"> 
    						<label></label>
    						<div class="form-group">
    							<button type="button" id="breakeven_search" class="btn btn-info">Search</button>   
    						</div>
    					</div>
								
					<div class="col-sm-10 col-sm-offset-1">
					<div id="chit_alert"></div>
					 
					</div>
				  </div>
				
                  <div class="table-responsive">
					  <table id="breakeven_list" class="table table-bordered table-striped text-center">
						<thead>
						  <tr>
							<th>ID</th>
							<th>Date</th>
							<th style="text-align:left">Branch</th>
							<th style="text-align:right">Gold (Gram)</th>
							<th style="text-align:right">Silver (Gram)</th> 
							<th style="text-align:right">Diamond (Ct)</th>
							 <th>Action</th>
						  </tr>
						</thead>
					 
					  </table>
                  </div> <div class="overlay" style="display:none">
				  <i class="fa fa-refresh fa-spin"></i>
				</div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      

<!-- modal -->      
<div class="modal fade" id="confirm-delete"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Delete Breakeven Logs</h4>
      </div>
      <div class="modal-body">
               <strong>Are you sure! You want to delete this Breakeven Logs ?</strong>
      </div>
      <div class="modal-footer">
      	<a href="#" class="btn btn-danger btn-confirm" >Delete</a>
        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- / modal -->  
<!-- modal -->      
<div class="modal fade" id="confirm-add"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Add Breakeven Logs</h4>
      </div>
         <div class="modal-body">
            <div id="error-msg"></div>
			<form id="myform">
				 <?php if($this->session->userdata('branch_settings')==1){?>
				 <input type="hidden" name="settings[branch_settings]" id="branch_settings" value="<?php echo $this->session->userdata('branch_settings');?>">
                <div class="row">
                    <div class="col-md-12">
                        <table id="brevn_items" class="table table-bordered table-striped text-center" style="width:100%;margin-left: 00%;">
                            <thead>
                                <tr>
                                <th>Branch</th>
                                <th>Gold (Gram)</th>
                                <th>Silver (Gram)</th>
								<th>Diamond (Ct)</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php }?>
				<p class="help-block"></p>
            </form>
        </div>
		<div class="modal-footer">
			<a href="#" id="add_breakeven_name" class="btn btn-success" >Save & Close</a>
			<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
		</div>
    </div>
  </div>
</div>
<!-- / modal -->
<!-- modal -->      
<div class="modal fade" id="confirm-edit"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Breakeven Logs</h4>
      </div>
        <div class="modal-body">
            <div class="row" >
                <div class="col-md-offset-1 col-md-10" id='error_message'></div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label for="" class="col-sm-4 col-md-offset-1 ">Enter Gold Value<span class="error">*</span></label>
					<div class="col-sm-4">
						<input type="hidden" id="edit-id" value="" />
						<input type="number" id="ed_gold_value" class="form-control" placeholder="Enter Gold Value" style=" text-transform: uppercase;">
					</div>
                </div>
            </div> <p class="help-block"></p> 
			
			<div class="row">
                <div class="form-group">
                    <label for="" class="col-sm-4 col-md-offset-1 ">Enter Silver Value<span class="error">*</span></label>
					<div class="col-sm-4">
						<input type="hidden" id="edit-id" value="" />
						<input type="number" id="ed_silver_value" class="form-control" placeholder="Enter Silver Value" style=" text-transform: uppercase;">
					</div>
                </div>
            </div> <p class="help-block"></p> 
			
			<div class="row">
                <div class="form-group">
                    <label for="" class="col-sm-4 col-md-offset-1 ">Enter Dimond Value<span class="error">*</span></label>
					<div class="col-sm-4">
						<input type="hidden" id="brevn_log_id" value="" />
						<input type="number" id="ed_dimond_value" class="form-control" placeholder="Enter Dimond Value" style=" text-transform: uppercase;">
					</div>
                </div>
            </div> <p class="help-block"></p> 
           

        </div>
      <div class="modal-footer">
      	<a href="#" id="update_brevnlog" class="btn btn-success" >Update</a>
        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- / modal -->      



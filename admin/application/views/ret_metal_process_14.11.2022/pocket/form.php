  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Old Metal Pocket
			 <small>Old Metal Analyse</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Old Metal Pocket</a></li>
            <li class="active">Add</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
               <div class="box box-primary">
                 <div class="box-body">  
                    <div class="row">
                        <div class="col-md-offset-1 col-md-10">  
                            <div class="box box-default">  
                                <div class="box-body">  
                                    <div class="row">
                                        <?php if($this->session->userdata('branch_settings')==1 && $this->session->userdata('id_branch')==0){?>
            						        <div  class="col-md-3">
            						           <label>Select Branch</label>
            						            <select class="form-control" id="branch_select"  style="width:100%;" disabled></select>
            						            <input type="hidden" id="id_branch"  value="1"> 
            						            
            						        </div>
            						        <?php }else{?>
            						            <input type="hidden" id="branch_filter"  value="<?php echo $this->session->userdata('id_branch') ?>"> 
						                 <?php }?>
						                 
						                 <div  class="col-md-2">
        						           <label>From Branch</label>
        						            <select class="form-control branch_filter" id=""  style="width:100%;" ></select>
        						        </div>
        						        
        						         <div class="col-md-2"> 
                                            <div class="form-group">    
                                                <label>DateRange</label></br>
                                                <button class="btn btn-default btn_date_range" id="rpt_payment_date">
                                                <span  style="display:none;" id="rpt_payments1"></span>
                                                <span  style="display:none;" id="rpt_payments2"></span>
                                                <i class="fa fa-calendar"></i> Date range picker
                                                <i class="fa fa-caret-down"></i>
                                                </button>
                                            </div>
                                        </div> 
						                 
						                 <div  class="col-md-2">
            						           <label>Metal</label>
            						            <select class="form-control" id="metal" style="width:100%;"></select>
            						        </div>
            						        
                                       
                                        <div class="col-md-3"> 
                                            <label></label>
                                            <div class="form-group">
                                                <button type="button" id="metal_search" class="btn btn-info">Search</button>   
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
	                 <table id="metal_list" class="table table-bordered table-striped text-center">
                     
	                    <thead>
	                      <tr>
                            <th width="10%"><label class="checkbox-inline"><input type="checkbox" id="old_metal_select_all" name="select_all" value="all"/>All</label></th>  
                            <th width="10%">Pcs</th>                                       
                            <th width="10%">Gross Wgt</th>                                       
                            <th width="10%">Net Wgt</th> 
                            <th width="10%">Purity %</th> 
                            <th width="10%">Amount</th>
                            <th width="10%">Detail</th>
	                      </tr>
	                    </thead> 
                      <tfoot>
		                        <tr>
                                    <td colspan="1" style="width:0px;">Total</th>
                                    <td width="10%"><input type="text" class="total_pcs" disabled="true" style="width: 100px;"/></td>
                                    <td width="10%"><input type="text" class="total_gross_wt" disabled="true" style="width: 100px;"/></td>
                                    <td width="10%"><input type="text" class="total_net_wt" disabled="true" style="width: 100px;"/></td>
                                    <td width="10%"><input type="text" class="avg_purity_per" disabled="true" style="width: 100px;"/></td>
                                    <td width="10%"><input type="hidden" class="total_item_purity"/><input type="text" class="total_amount" disabled="true" style="width: 100px;"/></td>
                                    <td width="10%"></td>
		                    	</tr>
		                    </tfoot>
	                 </table>
                  </div>
                </div><!-- /.box-body -->

				 	<!-- <legend>Preview</legend>
				 	 <div class="row"> 
					 	<div class="col-sm-12"> 
					 		<div class="table-responsive">
			                 <table id="lt_preview" class="table table-bordered table-striped text-center">
			                    <thead>
			                      <tr>
                                <th>S.No</th>
                                <th>Gross Wt(Grams)</th>
                                <th>Net Wt(Grams)</th>
                                <th>Purity %</th>
                                <th>Amount</th>
                                <th>Action</th>
			                      </tr>
			                    </thead> 
			                    <tbody></tbody>
			                 </table>
		                  </div> 
					 	</div>
				 	</div>  -->

                <div class="row">
                  <div class="box box-default"><br/>
                    <div class="col-xs-offset-5">
                    <button type="button" id="pocket_save" class="btn btn-primary" >Save</button> 
                    <button type="button" class="btn btn-default btn-cancel">Cancel</button>
                    </div> <br/>
                  </div>
				        </div>

                <div class="overlay" style="display:none">
				  <i class="fa fa-refresh fa-spin"></i>
				</div>
            
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      



      <!-- Content Wrapper. Contains page content -->
    <style>
    	.remove-btn{
			margin-top: -168px;
		    margin-left: -38px;
		    background-color: #e51712 !important;
		    border: none;
		    color: white !important;
		}
    </style>
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
        	Tagging
            <small>Tag</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Tagging</a></li>
            <li class="active">Tag</li>
          </ol>
        </section>
        <!-- Main content -->
        <section class="content product">
          <!-- Default box -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Add Tagging</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
             <!-- form container -->
              <div class="row">
	             <!-- form -->
				<?php echo form_open_multipart(( $tagging['tag_id'] != NULL && $tagging['tag_id'] > 0 ? 'admin_ret_tagging/tagging/update/'.$tagging['tag_id']:'admin_ret_tagging/tagging/save')); ?>
				<div class="col-sm-12"> 
					<!-- Lot Details Start Here -->
					<div class="row">				    	
			    		<div class="col-sm-3">
			    			<?php if($this->session->userdata('branch_settings')==1){?>
							 	<div class="row">				    	
						    		<div class="col-sm-3">
						    			<label>Branch </label>
							 		</div>
							 			<div class="col-sm-8">
							 			<div class="form-group">
							 				<?php if($tagging['lot_recv_branch']==1){?>
								 			<select id="branch_select" class="form-control ret_branch" required disabled></select>
								 			<input id="id_branch" name="lt_item[id_branch]" type="hidden" value="<?php echo set_value('tagging[id_branch]',$tagging['id_branch']); ?>" />
								 			<?php }else{?>
								 				<select id="branch_select" class="form-control ret_branch" required></select>
								 				<input id="id_branch" name="lt_item[id_branch]" type="hidden"  value="<?php echo set_value('tagging[id_branch]',$tagging['id_branch']); ?>" />
								 			<?php }?>
										</div>
							 		</div>
							 	</div>
							 <?php }?>
			    			<div class="row">				    	
					    		<div class="col-sm-3">
					    			<label>Lot No </label>
						 		</div>
						 		<div class="col-sm-8">
						 			<div class="form-group">
							 			<select id="tag_lot_received_id" class="form-control" required></select>
										<input id="tag_lot_id" name="tagging[tag_lot_id]" type="hidden" value="<?php echo set_value('tagging[tag_lot_id]',$tagging['tag_lot_id']); ?>" />
										<input id="tag_id" name="tagging[tag_id]" type="hidden" value="<?php echo set_value('tagging[tag_id]',$tagging['tag_id']); ?>" />
									</div>
						 		</div>
						 	</div> 
						 	<div class="row">				    	
					    		<div class="col-sm-3">
					    			<label>Product </label>
						 		</div>
						 		<div class="col-sm-8">
						 			<div class="form-group">
						 			    <select class="form-control" id="tag_lt_prod"></select>
							 			<!--<input type="text" name="" id="tag_lt_prod" class="form-control" placeholder="Enter Product" required>-->
										<input id="tag_lt_prodId" name="tagging[tag_lt_prodId]" type="hidden" value="" />
										<span id="productAlert" class="error"></span>
									</div>
						 		</div>
						 	</div>
						 	<div class="row">				    	
					    		<div class="col-sm-3">
					    			<label>Design </label>
						 		</div>
						 		<div class="col-sm-8">
						 			<div class="form-group">
						 			    <select class="form-control" id="tag_lt_design"></select>
							 		<!--	<input id="tag_lt_design" class="form-control" required placeholder="Enter Design">-->
										<input id="tag_lt_designId" name="tagging[tag_lt_prodId]" type="hidden" value="" />
										<span id="designAlert" class="error"></span>
									</div>
						 		</div>
						 	</div>
						 	<?php if($this->session->userdata('branch_settings')==1){?>
							 	<div class="row">				    	
						    		<div class="col-sm-3">
						    			<label>ToBranch </label>
							 		</div>
							 			<div class="col-sm-8">
							 			<div class="form-group">
							 				<select id="current_branch" class="form-control ret_branch" required></select>
								 				<input id="current_branch_id" name="lt_item[to_branch]" type="hidden"  value="<?php echo set_value('tagging[to_branch]',$tagging['current_branch']); ?>" />
										</div>
							 		</div>
							 	</div>
							 <?php }?>
						 	<!--<div class="row">				    	
					    		<div class="col-sm-3">
					    			<label>TagDate </label>
						 		</div>
						 		<div class="col-sm-8">
						 			<div class="form-group">
							 			<div class="input-group" > 
											<input class="form-control" id="tag_datetime" data-date-format="dd-mm-yyyy" name="lt_item[tag_datetime]" type="text" required="true" placeholder="Lot Date" value="<?php echo set_value('tagging[tag_datetime]',$tagging['tag_datetime']); ?>" readonly />
										</div>
									</div>
						 		</div>
						 	</div>-->	 
				 			<!--<div class="row">				    	
					    		<div class="col-sm-3">
					    			<label>Employee </label>
						 		</div>
						 		<div class="col-sm-8">
						 			<div class="form-group">
							 			<select id="emp_select" class="form-control" required></select>
										<input id="id_employee" name="tagging[created_by]" type="hidden" value="<?php echo set_value('tagging[created_by]',$tagging['created_by']); ?>" />
									</div>
						 		</div>
						 	</div>-->
				 		</div>
				 		<div class="col-sm-9">
				 			<!-- Lot remaining wait details start here -->
							<div class="box box-solid">
								<div class="box-header with-border">
								  <h3 class="box-title">Lot Details</h3>
								  <div class="box-tools pull-right">
									<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
								  </div>
								</div>
								<div class="box-body">   
									<div class="row">				    	
										<div class="col-sm-3">
											<label>Metal : </label>&nbsp;&nbsp;<span id="lt_metal" class=""></span>
										</div>
										<div class="col-sm-3"> 
											<label>Category : </label>&nbsp;&nbsp;<span id="lt_category" class=""></span>
										</div>
										<div class="col-sm-3"> 
											<label>Tax Group : </label>&nbsp;&nbsp;<span id="lt_tax_group" class=""></span>
											<input type="hidden" id="lt_id_tax_group" name="lt_id_tax_group"/>
											<input type="hidden" id="tax_percentage" name="">
											<input type="hidden" id="tgi_calculation" name="">
											<input type="hidden" id="metal_rate" name="">
											<input type="hidden" id="purity" name="lt_item[purity]">
										</div>
										<div class="col-sm-3"> 
											<label>Lot Date : </label>&nbsp;&nbsp;<span id="lt_date" class=""></span>
										</div>
									</div>
									<h5 class="text-red">Lot Balance :</h5>
									<div class="row">				    	
										<div class="col-sm-3"> 
										<legend class="sub-title small">Item Details</legend> 
											<div class="row">				    	
												<div class="col-sm-4">
													<label>Gross Wgt </label>
												</div>
												<div class="col-sm-8">
													<div class="form-group">
														<div class="input-group ">
															<input class="form-control lot_bal_wt" id="lot_bal_wt" type="number" step=any  value="0" readonly />
															<span class="input-group-addon input-sm lot_bal_wt_uom">UOM</span>
														</div>
													</div>
												</div>
											</div>
											<div class="row">				    	
												<div class="col-sm-4">
													<label>Pieces </label>
												</div>
												<div class="col-sm-8">
													<div class="form-group">
														<div class="input-group ">
															<input class="form-control lot_bal_pcs" id="lot_bal_pcs" type="number" step=any  value="0" readonly />
															<span class="input-group-addon input-sm">Pcs</span>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-sm-3">
										<legend class="sub-title small">Precious</legend>
											<div class="row">				    	
												<div class="col-sm-4">
													<label>Weight </label>
												</div>
												<div class="col-sm-8">
													<div class="form-group">
														<div class="input-group ">
															<input class="form-control lot_bal_prec_wt" id="lot_bal_prec_wt" type="number" step=any  value="0" readonly />
															<span class="input-group-addon input-sm lot_bal_prec_wt_uom">UOM</span>
														</div>
													</div>
												</div>
											</div>
											<div class="row">				    	
												<div class="col-sm-4">
													<label>Pieces </label>
												</div>
												<div class="col-sm-8">
													<div class="form-group">
														<div class="input-group ">
															<input class="form-control lot_bal_prec_pcs" id="lot_bal_prec_pcs" type="number" step=any  value="0" readonly />
															<span class="input-group-addon input-sm">Pcs</span>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-sm-3">
										<legend class="sub-title small">Semi-Precious</legend>
											<div class="row">				    	
												<div class="col-sm-4">
													<label>Weight </label>
												</div>
												<div class="col-sm-8">
													<div class="form-group">
														<div class="input-group ">
															<input class="form-control lot_bal_semi_pre_wt" id="lot_bal_semi_pre_wt" type="number" step=any  value="0" readonly />
															<span class="input-group-addon input-sm lot_bal_semi_pre_wt_uom">UOM</span>
														</div>
													</div>
												</div>
											</div>
											<div class="row">				    	
												<div class="col-sm-4">
													<label>Pieces </label>
												</div>
												<div class="col-sm-8">
													<div class="form-group">
														<div class="input-group">
															<input class="form-control lot_bal_semi_pre_pcs" id="lot_bal_semi_pre_pcs" type="number" step=any  value="0" readonly />
															<span class="input-group-addon input-sm">Pcs</span>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-sm-3">
										<legend class="sub-title small">Normal</legend>
											<div class="row">				    	
												<div class="col-sm-4">
													<label>Weight </label>
												</div>
												<div class="col-sm-8">
													<div class="form-group">
														<div class="input-group ">
															<input class="form-control lot_bal_normal_wt" id="lot_bal_normal_wt" type="number" step=any  value="0" readonly />
															<span class="input-group-addon input-sm lot_bal_normal_wt_uom">UOM</span>
														</div>
													</div>
												</div>
											</div>
											<div class="row">				    	
												<div class="col-sm-4">
													<label>Pieces </label>
												</div>
												<div class="col-sm-8">
													<div class="form-group">
														<div class="input-group ">
															<input class="form-control lot_bal_normal_pcs" id="lot_bal_normal_pcs" type="number" step=any  value="0" readonly />
															<span class="input-group-addon input-sm">Pcs</span>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div> 
								</div>
							</div>
							<!-- Lot remaining wait details end here -->
				 		</div>
				 	</div>
					<!-- Lot Details End Here -->
					<legend class="sub-title">Tag Details</legend>
				 	<!--Block 3-->   
				 	<div class="row">	  
						<!--<div class="col-sm-4">	
				 			<div class="row">				    	
					    		<div class="col-sm-4">
					    			<label>Counter </label>
						 		</div>
						 		<div class="col-sm-8">
						 			 <div class="form-group" >  
										<input type="radio" id="counter" name="tagging[counter]" value="1" <?php echo (isset($tagging['counter']) ? ($tagging['counter'] == 1 ? 'checked="true"' : ""):"")?>> KDM
										&nbsp;&nbsp;&nbsp;
										<input type="radio" id="counter" name="tagging[counter]" value="2"<?php echo (isset($tagging['counter']) ? ($tagging['counter'] == 2 ? 'checked="true"' : ""):"")?>> Non KDM
									</div>
						 		</div>
						 		<div class="col-sm-8">
						 			<div class="form-group" > 
							 			<select id="select_purity" class="form-control"></select>
										<input id="purity" name="tagging[purity]" type="hidden" value="<?php echo set_value('tagging[purity]',$tagging['purity']); ?>" />
									</div>
						 		</div>
						 	</div> 		
				 		</div>-->						
				 	</div> 
				 	<div class="row" align="right">
				 		<div class="col-md-12">
				 			<button type="button"  id="add_more_tag" class="btn btn-success pull-right">Add Tag</button>
				 		</div> 
				 	</div>
				 	<p></p>
				 	<div class="row"> 
					 	<div class="col-sm-12"> 
					 		<div class="table-responsive">
					 			 	<input type="hidden" id="custom_active_id"  name="">
			                 <table id="lt_item_list" class="table table-bordered table-striped text-center">
			                    <thead>
			                      <tr>                                        
			                        <th width="5%">Lot</th>
			                        <th width="10%">Product</th>
			                        <th width="10%">Design</th>
			                        <th width="10%">Design For</th>
			                        <th width="10%">Calc Type</th>
			                        <th width="5%">Pieces</th> 
			                        <th width="10%">Gross Wgt</th>
			                        <th width="10%">Less Wgt</th>
			                        <th width="10%">Net Wgt</th>
			                        <th width="5%">Wast %</th>
			                        <th width="5%">MC Type</th>
			                        <th width="10%">Making Charge</th>
			                        <th width="5%">Sell Rate</th>
			                        <th width="10%">Size</th> 
			                        <th width="5%">Stone</th>
			                        <th width="5%">Image</th>
			                        <th width="5%">Calculated Item Rate</th>
			                        <th width="5%">Item Rate</th>
			                        <th width="5%">Sale Value</th>
			                        <th width="10%">Action</th>
			                      </tr>
			                    </thead> 
			                    <tbody>
			                    </tbody>
			                 </table>
		                  </div> 
					 	</div>
				 	</div>  
				 	<p class="help-block"></p>			 
				 	<!--/Block 2--> 
				</div>	<!--/ Col --> 
			</div>	 <!--/ row -->
			   <p class="help-block"> </p>  
			     <div class="row">
				   <div class="box box-default"><br/>
					  <div class="col-xs-offset-5">
						<button type="submit"  class="btn btn-primary">Save</button> 
						<button type="button" class="btn btn-default btn-cancel">Cancel</button>
					  </div> <br/>
					</div>
				  </div> 
	            </div>  
	          <?php echo form_close();?>
	            <div class="overlay" style="display:none">
				  <i class="fa fa-refresh fa-spin"></i>
				</div>
	             <!-- /form -->
	          </div>
             </section>
            </div>
<!--  custom items-->
<div class="modal fade" id="cus_stoneModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:60%;">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Add Stone</h4>
			</div>
			<div class="modal-body">
				<div class="row">
			<div class="box-tools pull-right">
			<button type="button" id="create_stone_item_details" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add</button>
			</div>
			</div>
				<div class="row">
					<table id="estimation_stone_cus_item_details" class="table table-bordered table-striped text-center">
					<thead>
					<tr>
					<th>Stone</th>
					<th>Pcs</th>   
					<th>Wt</th>
					<th>Price</th>
					<th>Action</th>
					</tr>
					</thead> 
					<tbody>
					</tbody>										
					<tfoot>
					<tr></tr>
					</tfoot>
					</table>
			</div>
		  </div>
		  <div class="modal-footer">
			<button type="button" id="update_stone_details" class="btn btn-success">Save</button>
			<button type="button" id="close_stone_details" class="btn btn-warning" data-dismiss="modal">Close</button>
		  </div>
		</div>
	</div>
</div>
</div>
<!--  Image Upload-->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:60%;">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Add Image</h4>
			</div>
			<div class="modal-body">
				<div id="uploadArea_p_stn" class="col-md-12">
				<input type="file" name="pre_images" id="pre_images" multiple="multiple">
			</div>
		  </div></br>
		  <div class="modal-footer">
			<button type="button" id="update_img" class="btn btn-success">Save</button>
			<button type="button" id="close_stone_details" class="btn btn-warning" data-dismiss="modal">Close</button>
		  </div>
		</div>
	</div>
</div>
</div>
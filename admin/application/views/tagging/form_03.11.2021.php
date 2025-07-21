      <!-- Content Wrapper. Contains page content -->

    <style>

    	.remove-btn{

			margin-top: -168px;

		    margin-left: -38px;

		    background-color: #e51712 !important;

		    border: none;

		    color: white !important;

		}



		.stickyBlk {

		    margin: 0 auto;

		    top: 0;

		    width: 100%;

		    z-index: 999;

		    background: #fff;

		}

    </style>
		<?php if(isset($tag_prints) && trim($tag_prints) != '') { ?>
	  	<script type="text/javascript">
	  	 	window.open('<?php echo base_url() ?>index.php/admin_ret_tagging/tagging/generate_barcode?tag=<?php echo $tag_prints ?>', '_blank');
	  	</script>
	  	<?php } ?>
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

				<?php echo form_open_multipart(( $tagging['tag_id'] != NULL && $tagging['tag_id'] > 0 ? 'admin_ret_tagging/tagging/update/'.$tagging['tag_id']:'admin_ret_tagging/tagging/save'),array('id'=>'tag_form')); ?>

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

							 			<select id="tag_lot_received_id" tabindex="1" class="form-control" required></select>

										<input id="tag_lot_id" name="tagging[tag_lot_id]" type="hidden" value="<?php echo set_value('tagging[tag_lot_id]',$tagging['tag_lot_id']); ?>" />

										<input id="tag_id" name="tagging[tag_id]" type="hidden" value="<?php echo set_value('tagging[tag_id]',$tagging['tag_id']); ?>" />

										<input id="weight_per" name="tagging[weight_per]" type="hidden" value="<?php echo set_value('tagging[weight_per]',$tagging['weight_per']); ?>" />

									    <input id="allow_tag_pcs" name="tagging[allow_tag_pcs]" type="hidden" value="<?php echo set_value('tagging[allow_tag_pcs]',$tagging['allow_tag_pcs']); ?>" />

									</div>

						 		</div>

						 	</div> 

						 	<div class="row">				    	

					    		<div class="col-sm-3">

					    			<label>Product </label>

						 		</div>

						 		<div class="col-sm-8">

						 			<div class="form-group">

						 			    <select class="form-control" tabindex="2" id="tag_lt_prod"></select>

										<input id="tag_lt_prodId" name="tagging[tag_lt_prodId]" type="hidden" value="" />

										<span id="productAlert" class="error"></span>

									</div>

						 		</div>

						 	</div>

						 	<!--<div class="row">				    	

					    		<div class="col-sm-3">

					    			<label>Design </label>

						 		</div>

						 		<div class="col-sm-8">

						 			<div class="form-group">

						 			    <select class="form-control" id="tag_lt_design"></select>

										<input id="tag_lt_designId" name="tagging[tag_lt_prodId]" type="hidden" value="" />

										<span id="designAlert" class="error"></span>

									</div>

						 		</div>

						 	</div>-->

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

											<input type="hidden" id="silverrate_1gm" name="">

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

				 	<p></p>

				 	<legend class="sub-title">Tag Detail</legend> 

				 	<div class="row" >

					 	<div class="col-sm-12">

						 	<div class="box box-warning">

					            <!-- /.box-header -->

					            <div class="box-body">

					            	<div class="row">

								 		<div class="col-sm-12">

								 			<div class="row">

									 			<input type="hidden" class="form-control" id="tag_id_lot_inward_detail"/>

									 			<input type="hidden" class="form-control" id="calculation_based_on"/>

						 		        		<input type="hidden" class="form-control" id="id_metal"/>

						 		        		<input type="hidden" class="form-control" id="tax_group_id"/>

						 		        		<input type="hidden" class="form-control" id="tag_lot_no"/>

						 		        		<input type="hidden" class="form-control" id="tag_sales_mode"/>

						 		        		<input type="hidden" class="form-control" id="id_purity"/>

						 		        		<input type="hidden" class="form-control" id="tag_tax_type"/>

						 		        		<input type="hidden" class="form-control" id="tag_product_short_code"/>

						 		        		

									 			

									 			<div class="col-sm-2">

									 				<label>Design <span class="error">*</span></label>

									 				<div class="form-group"> 

								 		        		<select class="form-control" id="des_select" tabindex="3"></select>

								 		        		<input type="hidden" id="id_design">

													</div>

									 			</div>

									 			<div class="col-md-1" style="width: 120px;">

									 				<label>Pieces <span class="error">*</span></label>

									 				<div class="form-group"> 

								 		        		<input id="tag_pcs" class="form-control custom-inp" type="number" step="any" value="1" tabindex="4"/>

								 		        		<input id="tag_act_blc_pcs" type="hidden" />

								 		        		<input id="tag_blc_pcs" type="hidden" />

								 		        		<span id="tag_blc_pcs_disp" class="text-red"></span>

													</div>

									 			</div>

									 			<div class="col-md-2" style="width: 130px;">

									 				<label>G Wgt <span class="error">*</span></label>

									 				<div class="form-group"> 

								 		        		<input id="tag_gwt" class="form-control custom-inp" type="number" step="any" tabindex="5"/>

								 		        		<input id="tag_act_gross_blc" type="hidden" />

								 		        		<input id="tag_blc_gross" type="hidden" />

								 		        		<span id="tag_blc_gross_disp" class="text-red"></span>

													</div>

									 			</div>

									 			<div class="col-md-2" style="width: 130px;">

									 				<label>L Wgt</label>

									 				<div class="form-group"> 

								 		        		<input id="tag_lwt" class="form-control custom-inp" type="number" step="any" readonly tabindex="6"/>

													</div>

									 			</div>

									 			<div class="col-md-2" style="width: 130px;">

									 				<label>N Wgt</label>

									 				<div class="form-group"> 

								 		        		<input id="tag_nwt" class="form-control custom-inp" type="number" step="any" readonly tabindex="7"/>

													</div>

									 			</div>

									 			<div class="col-md-2" style="width: 130px;">

									 				<label>Wastage %</label>

									 				<div class="form-group"> 

								 		        		<input id="tag_wast_perc" class="form-control custom-inp" type="number" step="any" value="0" tabindex="8" />

													</div>

									 			</div>

									 			<div class="col-md-2" style="width: 176px;">

									 				<label>MC Type</label>

									 				<select class="form-control" id="tag_id_mc_type" tabindex="9">

									 					<option value="">--N/A--</option>

				                               	 		<option value="1">Per Piece</option>

				                               	 		<option value="2">Per Gram</option>

				                               	 		<option value="3">% On Price</option>

				                               	 	</select>

									 			</div>

									 			<div class="col-md-2" style="width: 120px;">

									 				<label>MC <span class="error">*</span></label>

									 				<div class="form-group">

									 					<input id="tag_mc_value" class="form-control custom-inp" type="number" step="any" autocomplete="off" value="0" tabindex="10">

									 				</div>

									 			</div>	

								 			</div>

								 			<div class="row">

									 			<div class="col-sm-2">

									 				<label>Size</label>

									 				<select class="form-control no-padding" id="tag_size"></select>

									 			</div>

									 			

									 			<div class="col-sm-2">

									 				<label>Design For <span class="error">*</span></label>

									 				<div class="form-group"> 

								 		        		<select class="form-control" id="tag_design_for" tabindex="11">

								 		        			<option value="2">Female</option>

								 		        			<option value="1">Male</option>

								 		        			<option value="3">Unisex</option>

								 		        		</select>

													</div>

									 			</div>

									 			<div class="col-sm-2">

									 				<label>Calculation Type <span class="error">*</span></label>

									 				<div class="form-group"> 

								 		        		<select class="form-control" id="tag_calculation_based_on" disabled>

								 		        			<option value="">--Choose--</option>

								 		        			<option value="0">Mc & Wast On Gross</option>

								 		        			<option value="1">Mc & Wast On Net</option>

								 		        			<option value="2">Mc on Gross,Wast On Net</option>

								 		        			<option value="3">MRP</option>

								 		        			<option value="4">Fixed Rate based on Weight</option>

								 		        		</select>

													</div>

									 			</div>

									 			

									 			<div class="col-sm-2">

									 				<label>MRP <span class="error">*</span></label>

									 				<div class="form-group"> 

								 		        		<input id="tag_sell_rate" class="form-control" type="number" step="any"  tabindex="13"/>

													</div>

									 			</div>

									 			<div class="col-sm-2">

									 				<label>Sale Value</label>

									 				<div class="form-group"> 

								 		        		<input id="tag_sale_value" class="form-control" type="number" step="any" readonly tabindex="14"/>

													</div>

									 			</div>
									 			
									 			<div class="col-sm-3">	
									 			    <label>HUID<span id=""></span></label>									 				
									 			    <div class="form-group"> 								 		        		
									 			        <input id="hu_id" class="form-control" tabindex="18" type="text" placeholder="HUID"/>												
									 			    </div>									 			
									 			</div>
									 			

								 			</div>

								 			<div class="row">

									 			<input type="hidden" id="tag_stone_details">

									 			<!--<div class="col-sm-2">

									 				<label>Image</label>

									 				<div>

									 					<input type="hidden" id="custom_active_id">

						 		        				<div id="tag_img" data-img='[]'></div>

						 		        				<input type="hidden" class="form-control" id="tag_img_copy"/>

						 		        				<input type="hidden" class="form-control" id="tag_img_default"/>

									 					<a href="#" onclick="update_image_upload();" class="btn btn-default btn-sm"><i class="fa fa-plus"></i></a>

									 				</div>

									 			</div> -->

												<div class="row">

													<div class="col-sm-6">

														<p class="text-light-blue">CHARGES : </p> 

													</div>

													<div class="col-sm-2">

														<button type="button" class="btn btn-success btn-sm pull-right" data-toggle="tooltip" title="Add Charges" onclick="add_charge()"><i class="fa fa-plus"></i> Add</button>

													</div>

												</div>

												<br/>

												<div class="row">

													<div class="col-sm-8"> 

														<div class="table-responsive">

														<table id="table_charges" class="table table-bordered text-center">

															<thead>

															<tr>                                        

																<th width="10%">Charges Type  <span class="error">*</span></th>

																<th width="10%">Value  <span class="error">*</span></th>

																<th width="10%">Action</th> 

															</tr>

															</thead> 

															<tbody>

															</tbody>

														</table>

													</div> 

													</div> 

												</div>

									 			<div class="col-sm-8" align="center" style="    margin-top: 25px;"> 

									 				<button type="button" id="addTagToPreview" class="btn btn-success btn-flat" tabindex="15">Add</button>

									 				<button type="button" disabled id="addTagToPreviewAndCopy" class="btn btn-info btn-flat">Add & Copy</button>

									 				<button type="button" style="display: none" id="updateTagInPreview" class="btn btn-primary btn-flat">Update</button>

									 				<button type="button" id="reset_tag_form" class="btn btn-default btn-flat">Reset</button>

									 			</div>

									 		</div>

								 		</div>

								 	</div>

				 					<!--Tag Detail Ends Here-->

					            </div>

					            <!-- /.box-body -->

					          </div>

				          </div>

			          </div> 

				 	<hr/>

		

				 	<div class="row stickyBlk" style="display: none;">

				 		<div class="col-md-12">

				 			<div class="col-md-1"> 

				 				<label>Copy Row</label>

				 			</div>

				 			<div class="col-md-2">  

			 		        	<div class="input-group"> 

			 		        		<input type="number" class="form-control" id="row_value" placeholder="No.of Rows" tabindex="19">

			 		                <input type="hidden" id="id_cmp_emp" name="billing[id_cmp_emp]">

	                                <span class="input-group-btn">

	                                	<button type="button" id="add_multiple_tag" class="btn btn-info" tabindex="20">Copy</button>

	                                </span>

			 		        	</div> 

				 		    </div>

				 		    <div class="col-md-2 pull-right">

				 		        <button type="button"  id="add_more_tag" class="btn btn-success" tabindex="18">Add Tag</button>

				 		    </div>

				 		</div> 

				 	</div>

				    	<p>PREVIEW</p>

    				 	<div class="row"> 

    					 	<div class="col-sm-12"> 

    					 		<div class="table-responsive">

    					 		    <input type="hidden" id="custom_active_id"  name="">

    			                 <table id="lt_item_tag_preview" class="table table-bordered table-striped text-center"> <!--old id="lt_item_list"-->

    			                    <thead>

    			                      <tr>                                        
                                         <th width="5%">HUID</th>
    			                        <th width="5%">Lot</th>	

    			                        <th width="10%">Product</th>

    			                        <th width="10%">Design</th>

    			                        <th width="10%">Design For</th>

    			                        <th width="10%">Calc Type</th>

    			                        <th width="10%">Size</th> 

    			                        <th width="5%">Pieces</th> 

    			                        <th width="10%">Gross Wgt</th>

    			                        <th width="10%">Less Wgt</th>

    			                        <th width="10%">Net Wgt</th>

    			                        <th width="5%">Wast %</th>

    			                        <th width="5%">MC Type</th>

    			                        <th width="10%">Making Charge</th>

    			                        <th width="5%">Stone</th>

    			                        <th width="10%">Sell Rate</th>

    			                        <th width="5%">Sales Rate</th>

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

					    <button type="submit"  id="tag_submit" class="btn btn-primary">Save</button> 

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

<div class="modal fade" id="cus_stoneModal"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

    <div class="modal-dialog" style="width:72%;">

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

					<input type="hidden" id="stone_active_row" value="0">

					<table id="estimation_stone_cus_item_details" class="table table-bordered table-striped text-center">

					<thead>

					<tr>

					<th width="5%">LWT</th>

					<th width="15%">Type</th>

					<th width="15%">Name</th>

					<th width="10%">Pcs</th>   

					<th width="30%">Wt</th>

					<th width="12%">Rate</th>

					<th width="17%">Amount</th>

					<th width="10%">Action</th>

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
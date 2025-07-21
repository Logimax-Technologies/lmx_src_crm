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

	.tag-form .form-group{

	    margin-bottom: 1px;

	    display: block;

        width: 100%;

        height: 25px;

        padding: 2px 6px;

	}

	.tag-form .input-group {

	    margin-bottom: 1px;

        width: 100%;

        height: 25px;

	}

	.tag-form input, .tag-form .input-group-addon{

	    height : 25px;

	}

	.tag-form select{

	    height : 25px;

	    padding:0px 0px 0px 12px;

	}

	.add_attributes, .add_charges {

		cursor: pointer;

		color: blue;

	}

	.remove_tag_attribute, .remove_charge_item_details{

		margin-left: 5px;

	}

	#update_attribute_detail tr td .select2-container {

		width: 100% !important;

	}

	

	*[tabindex]:focus {

        outline: 1px black solid;

        }

        

</style>

<?php if(isset($tag_prints) && trim($tag_prints) != '') { ?>

  	<script type="text/javascript">

  	 	window.open('<?php echo base_url() ?>index.php/admin_ret_tagging/tagging/generate_barcode?tag=<?php echo $tag_prints ?>', '_blank');

  	</script>

<?php } ?>

<div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <!--<section class="content-header">

          <h1>

        	Tagging

            <small>Tag</small>

          </h1>

          <ol class="breadcrumb">

            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>

            <li><a href="#">Tagging</a></li>

            <li class="active">Tag</li>

          </ol>

    </section>-->

    <!-- Main content -->

    <section class="content product">

          <!-- Default box -->

          <div class="box box-primary">

            <!--<div class="box-header with-border">

              <h3 class="box-title">Add Tagging</h3>

              <div class="box-tools pull-right">

                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>

                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>

              </div>

            </div>-->

            <div class="box-body">

             <!-- form container -->

              <div class="row">

	             <!-- form -->

				<?php echo form_open_multipart(( $tagging['tag_id'] != NULL && $tagging['tag_id'] > 0 ? 'admin_ret_tagging/tagging/update/'.$tagging['tag_id']:'admin_ret_tagging/tagging/save'),array('id'=>'tag_form')); ?>

				<div class="col-sm-12"> 

					<!-- Lot Details Start Here -->

					<div class="row">				    	

			    		<div class="col-sm-4">

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

							 			<select id="tag_lot_received_id" tabindex="1" class="form-control" required autofocus></select>

										<input id="tag_lot_id" name="tagging[tag_lot_id]" type="hidden" value="<?php echo set_value('tagging[tag_lot_id]',$tagging['tag_lot_id']); ?>" />

										<!--<input id="tag_id" name="tagging[tag_id]" type="hidden" value="<?php echo set_value('tagging[tag_id]',$tagging['tag_id']); ?>" />-->

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

							 				<select id="current_branch" tabindex="3" class="form-control ret_branch" required></select>

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

						 	<div class="lot-table">

    						 	<div class="table-responsive">

    								<table id="lt-det" class="table table-bordered text-center">

    									<thead>

        									<tr> 

        									    <th width="10%"></th>

        										<th width="10%"><span class="lt_desc_tab">Lot</span></th>

        										<th width="10%"><span class="lt_desc_tab">Completed</span></th>

        										<th width="10%"><span class="lt_desc_tab">Balance</span></th> 

        									</tr>

    									</thead> 

    									<tbody>

    									    <tr>

        									    <td><b>Pieces</b></td>

        									    <td><span class="lt_disp_val disp_lot_pcs"></span></td>

        									    <td><span class="lt_disp_val disp_lot_tag_pcs"></span></td>

        									    <td><span class="lt_disp_val disp_lot_bal_pcs"></span></td>

    									    </tr>

    									    <tr>

        									    <td><b>Weight</b></td>

        									    <td><span class="lt_disp_val disp_lot_wt"></span></td>

        									    <td><span class="lt_disp_val disp_lot_tag_wt"></span></td>

        									    <td><span class="lt_disp_val disp_lot_bal_wt"></span></td>

    									    </tr>

    									</tbody>

    								</table>

    							</div>

    							<input type="hidden" id="lt_id_tax_group" name="lt_id_tax_group"/>

								<input type="hidden" id="tax_percentage" name="">

								<input type="hidden" id="tgi_calculation" name="">

								<input type="hidden" id="metal_rate" name="">

								<input type="hidden" id="silverrate_1gm" name="">

								<input type="hidden" id="purity" name="lt_item[purity]">

								<input class="form-control lot_bal_wt" id="lot_bal_wt" type="hidden" step=any  value="0" readonly />

                                <input class="form-control lot_bal_prec_wt" id="lot_bal_prec_wt" type="hidden" step=any  value="0" readonly />

                                <input class="form-control lot_bal_prec_pcs" id="lot_bal_prec_pcs" type="hidden" step=any  value="0" readonly />

                                <input class="form-control lot_bal_semi_pre_wt" id="lot_bal_semi_pre_wt" type="hidden" step=any  value="0" readonly />

                                <input class="form-control lot_bal_normal_wt" id="lot_bal_normal_wt" type="hidden" step=any  value="0" readonly />

                                <input class="form-control lot_bal_normal_pcs" id="lot_bal_normal_pcs" type="hidden" step=any  value="0" readonly />

							</div>

								<div class="row">

								    <div class="col-sm-3">

						 		            <b><span class="lt_desc_tab">Metal</span></b>

						 		   </div>

						 		   <div class="col-sm-8">

					 		            <!--<span class="lt_disp_val lot_metal"></span>-->

					 		            <span id="lt_metal" class="">-</span>

					 		        </div>

								</div>

								<div class="row">

								    <div class="col-sm-3">

						 		            <b><span class="lt_desc_tab">Category</span></b>

						 		   </div>

						 		   <div class="col-sm-8">

					 		            <!--<span class="lt_disp_val lot_cat"></span>-->

					 		            <span id="lt_category" class="">-</span>

					 		        </div>

								</div>

								<div class="row">

								    <div class="col-sm-3">

						 		            <b><span class="lt_desc_tab">Receipt On</span></b>

						 		   </div>

						 		   <div class="col-sm-8">

					 		            <!--<span class="lt_disp_val lot_receipton"></span>-->

					 		            <span id="lt_date" class="">-</span>

					 		        </div>

								</div>

				 		</div>

				 		<div class="col-sm-8"> 

        				 	<div class="row" >

        					 	<div class="col-sm-7 tag-form">

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

				 		        	</div>

				 		        	<div class="row">

				 		        	    <div class="col-sm-3">

							 				<label>Design <span class="error">*</span></label>

							 			</div>

							 			<div class="col-sm-7">

							 				<div class="form-group"> 

						 		        		<select class="form-control" id="des_select" tabindex="3"></select>

						 		        		<input type="hidden" id="id_design">

						 		        		<input type="hidden" id="tag_id" value="">

						 		        		<input type="hidden" id="tag_saved" value="">

											</div>

							 			</div>

				 		        	</div>

				 		        	<div class="row">

				 		        	    <div class="col-sm-3">

							 				<label>Sub Design <span class="error">*</span></label>

							 			</div>

							 			<div class="col-sm-7">

							 				<div class="form-group"> 

							 				    <input type="hidden" id="id_sub_design">

						 		        		<select class="form-control" id="sub_des_select" tabindex="3"></select>

											</div>

							 			</div>

				 		        	</div>

				 		        	<div class="row">

				 		        	    <div class="col-sm-3">

							 				<label>Pieces <span class="error">*</span></label>

							 			</div>

							 			<div class="col-sm-7">

							 				<div class="form-group"> 

						 		        		<input id="tag_pcs" class="form-control custom-inp" type="number" step="any" value="1" tabindex="5"/>

						 		        		<input id="tag_act_blc_pcs" type="hidden" />

						 		        		<input id="tag_blc_pcs" type="hidden" />

						 		        		<span id="tag_blc_pcs_disp" class="text-red"></span>

											</div>

							 			</div>

				 		        	</div>

				 		        	<div class="row">

				 		        	    <div class="col-sm-3">

							 				<label>Gross Wgt <span class="error">*</span></label>

							 			</div>

							 			<div class="col-sm-7">

							 				<div class="form-group"> 

						 		        		<input id="tag_gwt" class="form-control custom-inp" type="number" step="any" tabindex="6"/>

						 		        		<input id="tag_act_gross_blc" type="hidden" />

						 		        		<input id="tag_blc_gross" type="hidden" />

						 		        		<span id="tag_blc_gross_disp" class="text-red"></span>

											</div>

							 			</div>

				 		        	</div> 

				 		        	<div class="row">

				 		        	    <div class="col-sm-3">

							 				<label>Less Wgt</label>

							 			</div>

							 			<div class="col-sm-7">

							 			    <div class="form-group">

												<div class="input-group ">

													<input id="tag_lwt" class="form-control custom-inp add_tag_lwt" type="number" step="any" readonly tabindex="7"/>

													<span class="input-group-addon input-sm add_tag_lwt" tabindex="8">+</span>

												</div>

											</div>

							 			</div>

				 		        	</div> 

				 		            <div class="row">

				 		        	    <div class="col-sm-3">

							 				<label>Net Wgt</label>

							 			</div>

							 			<div class="col-sm-7">

							 				<div class="form-group"> 

						 		        		<input id="tag_nwt" class="form-control custom-inp" type="number" step="any" readonly/>

											</div>

							 			</div>

				 		        	</div>

				 		        	<div class="row">

				 		        	    <div class="col-sm-3">

							 				<label>Wastage %</label>

							 			</div>

							 			<div class="col-sm-7">

							 				<div class="form-group"> 

						 		        		<input id="tag_wast_perc" class="form-control custom-inp" type="number" step="any" value="0" tabindex="9" />

											</div>

							 			</div>

				 		        	</div>

				 		        	<div class="row">

				 		        	    <div class="col-sm-3">

							 				<label>MC Type</label>

							 			</div>

							 			<div class="col-sm-7">

							 				<div class="form-group"> 

						 		        		<select class="form-control" id="tag_id_mc_type" tabindex="10">

								 					<option value="">--N/A--</option>

			                               	 		<option value="1">Per Piece</option>

			                               	 		<option value="2">Per Gram</option>

			                               	 		<option value="3">% On Price</option>

			                               	 	</select>

											</div>

							 			</div>

				 		        	</div>  

				 		        	<div class="row">

				 		        	    <div class="col-sm-3">

							 				<label>MC <span class="error">*</span></label>

							 			</div>

							 			<div class="col-sm-7">

							 				<div class="form-group"> 

						 		        		<input id="tag_mc_value" class="form-control custom-inp" type="number" step="any" autocomplete="off" value="0" tabindex="10">

											</div>

							 			</div>

				 		        	</div>

				 		        	<div class="row">

				 		        	    <div class="col-sm-3">

							 				<label>Size</label>

							 			</div>

							 			<div class="col-sm-7">

							 				<div class="form-group"> 

						 		        		<select class="form-control no-padding" id="tag_size" tabindex="12"></select>

											</div>

							 			</div>

				 		        	</div>

				 		        	<div class="row">

				 		        	    <div class="col-sm-3">

							 				<label>Design For <span class="error">*</span></label>

							 			</div>

							 			<div class="col-sm-7">

							 				<div class="form-group"> 

						 		        		<select class="form-control" id="tag_design_for" tabindex="13">

						 		        			<option value="2">Female</option>

						 		        			<option value="1">Male</option>

						 		        			<option value="3">Unisex</option>

						 		        		</select>

											</div>

							 			</div>

				 		        	</div>

				 		        	<div class="row">

				 		        	    <div class="col-sm-3">

							 				<label>Calc Type <span class="error">*</span></label>

							 			</div>

							 			<div class="col-sm-7">

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

				 		        	</div>

				 		        	<div class="row">

				 		        	    <div class="col-sm-3">

							 				<label>HUID</label>

							 			</div>

							 			<div class="col-sm-7">

							 				<div class="form-group"> 

						 		        		<input id="tag_huid" class="form-control custom-inp" type="number" step="any" value="" tabindex="15" />

											</div>

							 			</div>

				 		        	</div>

				 		        	<div class="row">

				 		        	    <div class="col-sm-3">

							 				<label>Rate / MRP <span class="error">*</span></label>

							 			</div>

							 			<div class="col-sm-7">

							 				<div class="form-group"> 

						 		        		<input id="tag_sell_rate" class="form-control" type="number" step="any"  tabindex="16"/>

											</div>

							 			</div>

				 		        	</div>

									 <!--	Charges	 -->

							 		<input type="hidden" id="tag_stone_details"> 

							 		<input type="hidden" id="tag_charge_amt"> 

									<div class="row">

				 		        	    <div class="col-sm-3">

							 				<label>Charges</label>

							 			</div>

							 			<div class="col-sm-7">

							 			    <div class="form-group">

												<div class="input-group ">

													<input id="tag_charge" class="form-control custom-inp add_tag_charge" type="number" step="any" readonly tabindex="17"/>

													<span class="input-group-addon input-sm add_tag_charge">+</span>

												</div>

											</div>

							 				<!--<button type="button" class="btn btn-success btn-sm" data-toggle="tooltip" title="Add Charges" onclick="add_charge()"><i class="fa fa-plus"></i> Add</button>-->

							 			</div>

				 		        	</div>

									<div class="row">

				 		        	    <div class="col-sm-3">

							 				<label>Attributes</label>

							 			</div>

							 			<div class="col-sm-7">

							 			    <div class="form-group">

												<div class="input-group ">

													<input id="tag_attribute" class="form-control custom-inp display_attribute_modal" type="number" step="any" readonly tabindex="18"/>
													<span class="input-group-addon input-sm display_attribute_modal">+</span>

												</div>
											</div>

							 			</div>
				 		        	</div>

				 		        	<div class="row">

				 		        	    <div class="col-sm-3">

							 				<label>Sale Value</label>

							 			</div>

							 			<div class="col-sm-7">

							 				<div class="form-group"> 

						 		        		<input id="tag_sale_value" class="form-control" type="number" step="any" readonly tabindex="19"/>

											</div>

							 			</div>

				 		        	</div> 

									 <div class="row">

				 		        	    <div class="col-sm-3">

							 				<label>Manuf Code</label>

							 			</div>

							 			<div class="col-sm-7">

							 				<div class="form-group"> 

						 		        		<input id="manufacture_code" class="form-control" type="text" />

											</div>

							 			</div>

				 		        	</div>

									<div class="row">

				 		        	    <div class="col-sm-3">

							 				<label>Style Code</label>

							 			</div>

							 			<div class="col-sm-7">

							 				<div class="form-group"> 

						 		        		<input id="style_code" class="form-control" type="text"/>

											</div>

							 			</div>

				 		        	</div>

									 <div class="row">

				 		        	    <div class="col-sm-3">

							 				<label>Remarks</label>

							 			</div>

							 			<div class="col-sm-7">

							 				<div class="form-group"> 

						 		        		<input id="remarks" class="form-control" type="text"/>

											</div>

							 			</div>

				 		        	</div>

				 		        	<!--<div class="row">

				 		        	    <div class="col-sm-3"> 

							 			</div>

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

									</div>-->

									<br/>

						 			<div class="row">

							 			<div class="col-sm-12" align="center"> 

							 			   

							 			        <iframe  id="iFramePdf" name="iFramePdf" src="" style="display:none;"></iframe>

							 			   

							 				<button type="button" id="addTagToPreview" class="btn btn-success btn-flat" tabindex="20">Add</button>

							 				<button type="button" disabled id="addTagToPreviewAndCopy" class="btn btn-info btn-flat" tabindex="21">Add & Copy</button>

							 				<button type="button" style="display: none" id="updateTagInPreview" class="btn btn-primary btn-flat" tabindex="20">Update</button>

							 				<button type="button" id="reset_tag_form" class="btn btn-default btn-flat" tabindex="22">Reset</button>

							 			</div>

							 	    </div>

        						</div>

        					   <div class="col-sm-5">

                                    <div class="box box-info">

                                        <!--<div class="box-header with-border">

                                            <h3 class="box-title">Stone Details</h3>

                                        </div>-->

                                        <div class="box-body">

                                            <p>STONE DETAILS</p>

                                            <table id="stone-det" class="table table-bordered text-center">

            									<thead>

                									<tr> 

                										<th width="10%"><span class="lt_desc_tab">Stone</span></th>

                										<th width="10%"><span class="lt_desc_tab">Pieces</span></th>

                										<th width="10%"><span class="lt_desc_tab">Amount</span></th> 

                									</tr>

            									</thead> 

            									<tbody>

            									</tbody>

            								</table>

                                        </div>

                                    </div>

                                    <br/>

                                    <div class="box box-info">

                                        <!--<div class="box-header with-border">

                                            <h3 class="box-title">Charges Details</h3>

                                        </div>-->

                                        <div class="box-body">

                                            <p>CHARGES</p>

                                            <table id="charges-det" class="table table-bordered text-center">

            									<thead>

                									<tr> 

                										<th width="10%"><span class="lt_desc_tab">Charge</span></th>

                										<th width="10%"><span class="lt_desc_tab">Amount</span></th> 

                									</tr>

            									</thead> 

            									<tbody>

            									</tbody>

            								</table>

                                        </div>

                                    </div>

									<br/>
                                    <div class="box box-info">

                                        <div class="box-body">

                                            <p>ATTRIBUTES</p>

                                            <table id="attributes-det" class="table table-bordered text-center">

            									<thead>

                									<tr> 

                										<th width="10%"><span class="lt_desc_tab">Attribute Name</span></th>

                										<th width="10%"><span class="lt_desc_tab">Value</span></th> 

                									</tr>

            									</thead> 

            									<tbody>

            									</tbody>

            								</table>

                                        </div>

                                    </div>

        					   </div>

    					   </div>

    				    </div>

				 		<!--<div class="col-sm-4">-->

				 			<!-- Lot remaining wait details start here -->

							<!-- <div class="box box-solid">

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

							</div> -->

							<!-- Lot remaining wait details end here -->

				 		<!--</div>-->

				 	</div>

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

				    	<p style="border-bottom: 1px solid #e5e5e5;">PREVIEW</p>

    				 	<div class="row"> 

    					 	<div class="col-sm-12"> 

    					 		<div class="table-responsive">

    					 		    <input type="hidden" id="custom_active_id"  name="">

    			                 <table id="lt_item_tag_preview" class="table table-bordered table-striped text-center"> <!--old id="lt_item_list"-->

    			                    <thead>

    			                      <tr>                                        

    			                        <th width="5%">Lot</th>	
    			                        
    			                        <th width="5%">Tag No</th>	

    			                        <th width="10%">Product</th>

    			                        <th width="10%">Design</th>

    			                        <th width="10%">Sub Design</th>

    			                        <!--<th width="10%">Design For</th>-->

    			                        <th width="10%">Calc Type</th>

    			                        <!--<th width="10%">Size</th> -->

    			                        <th width="5%">Pieces</th> 

    			                        <th width="10%">Gross Wgt</th>

    			                        <th width="10%">Less Wgt</th>

    			                        <th width="10%">Net Wgt</th>

    			                        <th width="5%">Wast %</th>

    			                        <!--<th width="5%">MC Type</th>-->

    			                        <th width="10%">Making Charge</th>

    			                        <!--<th width="5%">Stone</th>-->

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

		     <!--<div class="row">

			   <div class="box box-default"><br/>

				  <div class="col-xs-offset-5">

				    <button type="submit"  id="tag_submit" class="btn btn-primary">Save</button> 

					<button type="button" class="btn btn-default btn-cancel">Cancel</button>

				  </div> <br/>

				</div>

			  </div> -->

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

				<!--<div class="row">-->

    <!--    			<div class="col pull-right">-->

    <!--    			    <button type="button" id="create_stone_item_details" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add</button>-->

    <!--    			</div>-->

    <!--			</div>-->

				<div class="row">

					<input type="hidden" id="stone_active_row" value="0">

					<table id="estimation_stone_cus_item_details" class="table table-bordered table-striped text-center">

					<thead>

					<tr>

					<th width="13%">LWT</th>

					<th width="15%">Type</th>

					<th width="15%">Name</th>

					<th width="5%">Pcs</th>   

					<th width="22%">Wt</th>

					<th width="12%">Rate</th>

					<th width="15%">Amount</th>

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

<!-- Charges Modal -->

<div class="modal fade" id="cus_chargeModal"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

    <div class="modal-dialog" style="width:50%;">

		<div class="modal-content">

			<div class="modal-header">

				<h4 class="modal-title" id="myModalLabel">Add Charges( <span class="add_charges"><i class="fa fa-plus"></i></span> )</h4>

			</div>

			<div class="modal-body">

				<!--<div class="row">

        			<div class="col pull-right">

        			    <button type="button" id="create_charge_item_details" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add</button>

        			</div>

    			</div>-->

				<div class="row">

					<input type="hidden" id="charge_active_row" value="0">

					<table id="table_charges" class="table table-bordered table-striped text-center">

    					<thead>

        					<tr>

								<th>SNo</th>

            					<th>Charge Name</th>

            					<th>Charge</th>

            					<th>Action</th>

        					</tr>

    					</thead> 

    					<tbody></tbody>										

    					<tfoot><tr></tr></tfoot>

					</table>

			    </div>

		    </div>

		  <div class="modal-footer">

			<button type="button" id="update_charge_details" class="btn btn-success">Save</button>

			<button type="button" id="close_charge_details" class="btn btn-warning" data-dismiss="modal">Close</button>

		  </div>

		</div>

	</div>

</div>

<!-- ./Charges Modal -->

<!-- Delete tag modal -->      

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

  <div class="modal-dialog">

    <div class="modal-content">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

        <h4 class="modal-title" id="myModalLabel">Delete Tagging</h4>

      </div>

      <div class="modal-body">

               <strong>Are you sure! You want to delete this tagging?</strong>

      </div>

      <div class="modal-footer">

      	<a href="#" class="btn btn-danger btn-confirm" >Delete</a>

        <button type="button" class="btn btn-warning btn-cancel" data-dismiss="modal">Close</button>

      </div>

    </div>

  </div>

</div>

<!-- / Delete Tag modal --> 

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

<!-- Attribute Modal -->
<div class="modal fade" id="attribute_modal"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

    <div class="modal-dialog" style="width:50%;">

		<div class="modal-content">

			<div class="modal-header">

				<h4 class="modal-title" id="myModalLabel">Add Attributes( <span class="add_attributes"><i class="fa fa-plus"></i></span> )</h4>

			</div>

			<div class="modal-body">

				<div class="row">

					<table id="table_attribute_detail" class="table table-bordered table-striped text-center">

    					<thead>

        					<tr>

								<th width="10%">SNo</th>

            					<th width="35%">Attribute Name</th>

            					<th width="35%">Value</th>

            					<th width="20%">Action</th>

        					</tr>

    					</thead> 

    					<tbody></tbody>

					</table>

			    </div>

		    </div>

		  <div class="modal-footer">

			<button type="button" id="update_attribute_details" class="btn btn-success">Save</button>

			<button type="button" id="close_attribute_details" class="btn btn-warning" data-dismiss="modal">Close</button>

		  </div>

		</div>

	</div>
	
</div>
<!-- Attribute Modal -->

</div>






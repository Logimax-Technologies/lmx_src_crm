      <!-- Content Wrapper. Contains page content -->
    <style>
    	.remove-btn{
			margin-top: -168px;
		    margin-left: -38px;
		    background-color: #e51712 !important;
		    border: none;
		    color: white !important;
		}
		.sm{
			font-weight: normal;
		}
		}
		
		*[tabindex]:focus {
            outline: 1px black solid;
        }
        
    </style>
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
       

        <!-- Main content -->
        <section class="content order">

          <!-- Default box -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">BILL DETAILS</h3>
            </div>
            <div class="box-body">
             <!-- form container --> 
	             <!-- form -->
				<form id="grn_entry_form">
				  <div class="tab-content">
				        <div class="row">
        				    <div class="col-md-12">
        				            <?php 
                				 		$this->session->unset_userdata('FORM_SECRET');
            				 		    $form_secret=md5(uniqid(rand(), true));
            					        $this->session->set_userdata('FORM_SECRET', $form_secret);
        				 		    ?>
        				        <div class="col-md-2">
            	                     <div class="form-group">
            	                       <label>Type</label>
            							<div class="form-group" >  
            							        <input type="hidden" id="FORM_SECRET" name="order[form_secret]" value="<?php echo $form_secret; ?>">
        										<input type="radio" id="oranment_type" name="order[grn_type]" value="1"  checked><label for="oranment_type">Bill</label>
        										<input type="radio" id="mt_type" name="order[grn_type]" value="2"  ><label for="mt_type">Receipt</label>
        										<input type="radio" id="st_type" name="order[grn_type]" value="3"  ><label for="st_type">Charges</label>
        									</div>
            	                     </div> 
        				        </div>
        				        
        				        <div class="col-md-2">
            	                     <div class="form-group">
            	                       <label>Select Karigar<span class="error">*</span></label>
            								<select id="select_karigar" class="form-control" name="order[id_karigar]" style="width:100%;" tabindex="1"></select>
            								<input type="hidden" id="id_karigar" value="<?php echo $po_item['po_karigar_id'];?>">
            								<input type="hidden" id="cmp_country" name="order[cmp_country]" value="<?php echo $comp_details['id_country'];?>">
            								<input type="hidden" id="cmp_state" name="order[cmp_country]" value="<?php echo $comp_details['id_state'];?>">
            								<input type="hidden" id="supplier_country" name="order[supplier_country]" value="">
            								<input type="hidden" id="supplier_state" name="order[supplier_state]" value="">
            	                     </div> 
        				        </div>
        				        
        				        
        				        <div class="col-md-2">
            	                     <div class="form-group">
            	                       <label>Ref No<span class="error">*</span></label>
            							<input type="text" class="form-control referenceno" name="order[po_supplier_ref_no]" value="<?php echo $po_item['po_supplier_ref_no'];?>" placeholder="Enter supplier bill Ref no." >
            	                     </div> 
        				        </div>
        				        
        				        <div class="col-md-2">
            	                     <div class="form-group">
            	                       <label>Ref Date<span class="error">*</span></label>
            							<input type="date" class="form-control referencedate" name="order[po_ref_date]" value="<?php echo $po_item['po_ref_date'];?>" dateformat="d-M-y"  placeholder="Select bill Ref date." tabindex="4">
            	                     </div> 
        				        </div>
        				        
        				        
            			        <div class="col-md-2">
            	                     <div class="form-group">
            	                       <label>E-Way Bill No</label>
            							<input type="text" class="form-control" id="ewaybillno" name="order[ewaybillno]" value="<?php echo $po_item['ewaybillno'];?>" placeholder="Enter The Bill No." tabindex="3">
            	                     </div> 
        				        </div>
        				        
        				        
        				        <div class="col-md-2">
            	                     <div class="form-group">
            	                       <label>IRN No</label>
            							<input type="text" class="form-control" id="invoice_ref_no" name="order[invoice_ref_no]" value="<?php echo $po_item['invoice_ref_no'];?>" placeholder="Enter The IRN No." tabindex="2">
            	                     </div> 
        				        </div>
        				        
        				        
        				        <div class="col-md-2">
            	                     <div class="form-group">
            	                       <label>Dispatch Through<span class="error">*</span></label>
            							<select id="despatch_through" class="form-control" name="order[despatch_through]" style="width:100%;" >
            							    <option value="1" <?php echo ($po_item['despatch_through']==1 ? 'selected' :'')?> >Courier</option>
            							     <option value="2" <?php echo ($po_item['despatch_through']==2 ? 'selected' :'')?> >Manual Delivery</option>
            							</select>
            	                     </div> 
        				        </div>
        				        
        				        
        				        
        				        <div class="col-md-2 item_details">
            	                     <div class="form-group">
            	                       <br>
            							<button id="add_item_details" type="button" class="btn btn-success" tabindex="5"><i class="fa fa-plus"></i> Add Item </button>
            	                     </div> 
        				        </div>
        				        
        				    </div>
        				</div>
        				
        				<div class="row item_details">
				            <div class="col-md-12">
            			         <div class="box-body">
								   <div class="table-responsive">
								       <input type="hidden" id="custom_active_id" value="0">
									 <table id="grn_item_details" class="table table-bordered table-striped text-center">
										<thead>
										  <tr>
											<th width="10%;">Category<span class="error">*</span></th>
											<th width="5%;">Pcs<span class="error">*</span></th>   
											<th width="5%;">G.Wt<span class="error">*</span></th>   
											<th width="10%;">L.Wt</th>   
											<th width="10%;">Other Metal</th>   
											<th width="5%;">N.Wt<span class="error">*</span></th>
											<th width="15%;">Rate<span class="error">*</span></th>
											<th width="5%;">Taxable Amount<span class="error">*</span></th>
											<th width="5%;">Tax<span class="error">*</span></th>
											<th width="5%;">Amount<span class="error">*</span></th>
											<th width="5%;">Action</th>
										  </tr>
										</thead> 
										<tbody></tbody>
										<tfoot>
											<tr></tr>
										</tfoot>
									 </table>
								  </div>
								</div> 
            			    </div>
        				</div>
        				
        					<div class="row">
        				    <div class="col-md-12">
        				        
        				    </div>
        				    <div class="box box-default total_summary_details">
								<div class="box-body">
									<div class="row">
										<div class="col-md-offset-1 col-md-6">
										    <label>Total Summary Details</label>
										   <div class="table-responsive">
											 <table id="total_summary_details" class="table table-bordered table-striped">
												<tbody> 
													<tr>
												
														<th>Amount</th>
														<th><input type="number" class="form-control total_summary_payable_amt" readonly></th>
													</tr>
													<tr>
														
														<th>Other Charges</th>
														<th>
														    <div class="input-group ">
                            									<input id="other_charges_amount"  name="order[other_charges_amount]" class="form-control custom-inp add_other_charges" type="number" step="any" readonly tabindex="19" />
                            									<span class="input-group-addon input-sm add_other_charges">+</span>
                            									<input type="hidden" id="other_charges_details"  name="order[other_charges_details]" />
                            								</div>
														</th>
													</tr>
													<tr>
													
														<th>Discount</th>
														<th>
														    <input type="number" class="form-control grn_discount" name="order[discount]" tabindex="20"  />
														</th>
													</tr>
												    <tr>
													    <th>TCS</th>
													    <th>
													        <div class="input-group" >
                                			                       <input class="form-control tcs_percent" type="number" name="order[tcs_percent]" id="tcs_percent" tabindex="21"  />
                                			                       <span class="input-group-btn"><input type="number" class="form-control tcs_tax_value" name="order[tcs_tax_value]" id="tcs_tax_value" tabindex="22" style="width:200px;" readonly /></span>
                                			                </div>
													    </th>
													</tr>
													<tr>
														
														<th>Round Off</th>
														<th><input type="number" class="form-control grn_round_off" name="order[round_off]"></th>
													</tr>
													<tr>
														
														<th>Final Price</th>
														<th><input type="number" class="form-control grn_total_cost" name="order[total_cost]"></th>
													</tr>
														
												</tbody>
												<tfoot>
													<tr></tr>
												</tfoot>
											 </table>
											
										  </div>
										</div>
									</div>
								</div>
							</div>
        				</div>
        				
        	        <div class="row">
        				   <div class=""><br/>
        					  <div class="col-xs-offset-5">
        						<button type="button" id="submit_grn_entry" class="btn btn-primary">Save</button>
        						<button type="button" class="btn btn-default btn-cancel">Cancel</button>
        						
        					  </div> <br/>
        					</div>
        				</div>
                </div>
				<?php echo form_close();?>
				 </div>
				  <div class="overlay" style="display:none;">
				  <i class="fa fa-refresh fa-spin"></i>
				</div>
	           </div>  
	           
	           
	       </div>  
        </section>
    </div>
    
    




<div class="modal fade" id="cus_stoneModal" tabindex="-1"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:73%;">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Stone Details</h4>
			</div>
			
			<div class="modal-body">
			    <div class="row">
        			<div class="col pull-right">
        			    <button type="button" id="create_stone_item_details" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add</button>
        			</div>
    			</div>
    			<div class="row">
    					<table id="estimation_stone_cus_item_details" class="table table-bordered table-striped text-center">
        					<thead>
            					<tr>
                					<th width="5%">LWT</th>
                					<th width="10%">Type</th>
                					<th width="13%">Name</th>
                					<th width="10%">Pcs</th>   
                					<th width="17%">Wt</th>
                					<th width="10%">Cal.Type</th>
                					<th width="10%">Rate</th>
                					<th width="15%">Amount</th>
                					<th width="10%">Action</th>
                					
            					</tr>
        					</thead> 
        					<tbody></tbody>										
        					<tfoot>
        					    <tr></tr>
        					</tfoot>
    					</table>
    			</div>
		  </div>
		  <div class="modal-footer">
			<button type="button" id="update_grn_stn_details" class="btn btn-success">Save</button>
			<button type="button" id="close_stone_details" class="btn btn-warning" data-dismiss="modal">Close</button>
		  </div>
		</div>
	</div>
</div>

<div class="modal fade" id="cus_orderdetailsModal" tabindex="-1"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:73%;">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Order Details</h4>
			</div>
			<div class="modal-body">
			    <div class="row">
			         <div class="col-md-12">
        				        
        				        <div class="col-md-6">
            	                     <div class="form-group">
            	                       <label>Ordered Pcs : </label>
            							<strong>  
            							      <span id="orderedpcs"></span> 
        								</strong>
            	                     </div> 
        				        </div>
        				        <div class="col-md-6">
            	                     <div class="form-group">
            	                       <label>Ordered Wt : </label>
            							<strong>  
            							      <span id="orderedwt"></span> 
        								</strong>
            	                     </div> 
        				        </div>
        				        <div class="col-md-6">
            	                     <div class="form-group">
            	                       <label>Received Pcs : </label>
            							<strong>  
            							      <span id="receivedpcs"></span> 
        								</strong>
            	                     </div> 
        				        </div>
        				        <div class="col-md-6">
            	                     <div class="form-group">
            	                       <label>Received Wt : </label>
            							<strong>  
            							      <span id="receivedwt"></span> 
        								</strong>
            	                     </div> 
        				        </div>
					</div>
    			</div>
    			<div class="row">
    					<input type="hidden" id="cur_order_row" value="0">
    					<table id="order_items_details" class="table table-bordered table-striped text-center">
        					<thead>
            					<tr>
                					<th width="10%">Category</th>
                					<th width="10%">Product</th>
                					<th width="10%">Design</th>
                					<th width="10%">Sub Design</th>   
                					<th width="7%">Pcs</th>
                					<th width="7%">Weight Range</th>
                					<th width="7%">Rec Pcs</th>
                					<th width="10%">Rec Wt</th>
            					</tr>
        					</thead> 
        					<tbody></tbody>										
        					<tfoot>
        					    <tr></tr>
        					</tfoot>
    					</table>
    			</div>
		  </div>
		  <div class="modal-footer">
			<button type="button" id="close_order_details" class="btn btn-warning" data-dismiss="modal">Close</button>
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
    
<div class="modal fade" id="other_metalmodal"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:72%;">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Other Metals</h4>
			</div>
			<div class="modal-body">
				<div class="row">
        			<div class="col pull-right">
        			    <button type="button" id="create_other_metal_item_details" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add</button>
        			</div>
    			</div>
				<div class="row">
					<input type="hidden" id="charge_active_row" value="0">
					<table id="other_metal_table" class="table table-bordered table-striped text-center">
    					<thead>
        					<tr>
            					<th width="15%">Category</th>
            					<th width="10%">Purity</th>
            					<th width="10%">Pcs</th>
            					<th width="10%">Gwt</th>
            					<th width="10%" style="display:none;">V.A(%)</th>
            					<th width="10%" style="display:none;">Mc Type</th>
            					<th width="10%" style="display:none;">Mc</th>
            					<th width="10%">Rate</th>
            					<th width="10%">Amount</th>
            					<th width="10%">Action</th>
        					</tr>
    					</thead> 
    					<tbody></tbody>										
    					<tfoot><tr style="font-weight:bold;"><td>Total</td><td></td><td class="total_pcs"></td><td class="total_wt"></td><td style="display:none;"></td><td style="display:none;"></td><td style="display:none;"></td><td></td><td class="total_amount"></td><td></td></tr></tfoot>
					</table>
			    </div>
		    </div>
		  <div class="modal-footer">
			<button type="button" id="update_grn_other_metal_details" class="btn btn-success">Save</button>
			<button type="button" id="close_charge_details" class="btn btn-warning" data-dismiss="modal">Close</button>
		  </div>
		</div>
	</div>
</div>
            



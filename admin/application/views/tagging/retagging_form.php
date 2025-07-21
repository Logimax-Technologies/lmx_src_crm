 <!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">

	<!-- Content Header (Page header) -->

	<section class="content-header">

	  <h1>

	        Re-Tagging Process

	  </h1>

	  

	</section>



	<!-- Main content -->

	<section class="content">

	  <div class="row">

	    <div class="col-xs-12">

	       <div class="box box-primary">

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

			   </div></br>

			   

			   

			    <div class="row">

                        <div class="col-md-12">

                            <div class="col-md-6">  

                                <div class="box box-primary">  

                                    <div class="box-body"> 

                                        <div class="box-header with-border">

                                        </div>

                                        <div class="row">

                                           <div class="col-md-3"> 

                        						<?php if($this->session->userdata('branch_settings')==1 && $this->session->userdata('id_branch')==0){?>

                        						<div class="form-group tagged">

                        							<label>Select Branch</label>

                        							<select id="branch_select" class="form-control branch_filter" style="width:100%;" disabled></select>

                        							<input type="hidden" id="id_branch"  value="1"> 

                        						</div> 

                        						<?php }else{?>

                        							<input type="hidden" id="id_branch"  value="1"> 

                        						<?php }?> 

                        					</div>  

                        					

                        					<div class="col-md-3">

                        					    <label>Type</label>

                        					    <select class="form-control" id="report_type">

                        					        <option value="1">Sales Return</option>

                        					        <option value="3">Partly Sale</option>

                        					        <option value="4">Old Metal</option>
                        					        
                        					        <option value="6">H.O Other Issue</option>

                        					       <option value="5">Non Tag Return</option>

												   <option value="7">Non Tag Other Issue</option>

                        					    </select>

                        					</div>

                        					
											 <div class="col-md-3 branch_transfer"> 

											<label>BT Code</label>

											<div class="form-group">
											
											<input type="text" class="form-control" id="bt_number" placeholder="Enter BT Code">

											</div>

											</div>

                        					

                        					<div class="col-md-2"> 

                        						<label></label>

                        						<div class="form-group">

                        							<button type="button" id="retag_search" class="btn btn-info">Search</button>   

                        						</div>

                        					</div>

					

                                        </div>

                                    

                                    </div>

                                </div> 

                            </div>

                            

                            <div class="col-md-6">  

                                <div class="box box-primary">  

                                    <div class="box-body"> 

                                         <div class="box-header with-border">

                                        </div><!-- /.box-header -->

                                        <div class="row">

                                            <div class="col-md-3">

                        					    <label>Select Process</label>

                        					    <select class="form-control" id="tag_process">

                        					        <option value="1">Add to ReTag</option>

                        					        <option value="2">Other Issue</option>

                        					        <option value="4">Add to Non Tag</option>

													<option value="6">Add to Pocket</option>


                        					    </select>

                        					</div>

                                            

                                            

                                            <div class="col-md-3 category" style="display:block;"> 

                        				        <label>Select Category</label>

                        					    <select class="form-control" id="select_category" style="width:100%;"></select>

                        					</div>

                        					

                        					<div class="col-md-3 purity" style="display:block;"> 

                        				        <label>Select Purity</label>

                        					    <select class="form-control" id="select_purity" style="width:100%;"></select>

                        					</div>

											
                                            <div class="col-md-3 design" style="display:block;"> 

                        				        <label>Select Section</label>

                        					    <select class="form-control" id="section_select" style="width:100%;"></select>

                        					</div>


											</div>



								                <div class="col-md-3 product" style="display:block;"> 

													<label>Select Product</label>

												 <select class="form-control" id="prod_select" style="width:100%;"></select>

												</div>
																

											<div class='row'>

                        					<div class="col-md-3 design" style="display:block;"> 

                        				        <label>Select Design</label>

                        					    <select class="form-control" id="des_select" style="width:100%;"></select>

                        					</div>

                        					<div class="col-md-3 sub_design" style="display:block;"> 

                        				        <label>Select Sub Design</label>

                        					    <select class="form-control" id="sub_des_select" style="width:100%;"></select>

                        					</div>

											</div>

                                         <!-- <div class="row"> -->

										   <div class="col-md-3 product" style="display:block;"> 

												<label>Select Karigar</label>

												<select class="form-control" id="tag_karigar" style="width:100%;"></select>

											</div>


											<div class="col-sm-3 remark"  style="display:block;"> 
											
											<label>Remark</label>	
											
											<textarea class="form-control" id="remark" rows="2" cols="50"> </textarea>
										   
										   </div>


                                			<div class="col-md-3"> 

                        						<label></label>

                        						<div class="form-group">

                        						    <button type="button" id="create_retag" class="btn btn-primary" >Save</button> 

                        						</div>

                        					</div>

                                        <!-- </div> -->

                                    </div>

                                </div> 

                            </div>

                        </div>

                   </div> 

                   

		  

	           <div class="row retag_details">

	               <div class="col-md-12">

	               	<div class="table-responsive">
                    <input type="hidden" id="custom_active_id" class="custom_active_id"  name="" value="" />
	                 <table id="retagging_list" class="table table-bordered table-striped text-center">

	                    <thead>

						    <tr> 

                            	<th width="5%"><label class="checkbox-inline"><input type="checkbox" id="select_all" name="select_all" value="all"/>All</label></th>     

                            	<th>Branch</th>

                            	<th>Bill No</th>

                            	<th>Bill Date</th>

								<th>BT Code</th>

                            	<th>Tag Code</th>

								<th>Purity</th>

                            	<th>Product</th>

                            	<th>Design</th>

								<th>Pcs</th>

                            	<th>Gross Wt(g)</th>

                            	<th>LWt(g)</th>
                            	
                            	<th>Net Wt(g)</th>
                            	
                            	<th>Dia Wt(g)</th>

                            	<th>Amount</th>

								<th>Narration</th>

                            </tr>

	                    </thead> 

	                     <tbody> 

	                </tbody>

					<tfoot>
						<tr style="font-weight:bold;" >
							<td>Total :</td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td class="sr_pcs"></td>
							<td class="sr_gwt"></td>
							<td class="sr_lwt"></td>
							<td class="sr_nwt"></td>
							<td class="sr_diawt"></td>
							<td><input type="hidden" class="total_pcs"><input type="hidden" class="total_gross_wt"><input type="hidden" class="total_net_wt"><input type="hidden" class="total_dia_wt"><input type="hidden" class="avg_purity_per"><input type="hidden" class="total_item_purity"><input type="hidden" class="total_amount"></td>
							<td></td>
						</tr>
					</tfoot>
						   

	                 </table>

	              </div>

	               </div>

	           </div></br>

	           

	           <div class="row non_tag" style="display:none;">

	               <div class="col-md-12">

	               	<div class="table-responsive">

	                 <table id="non_tag_list" class="table table-bordered table-striped text-center">

	                    <thead>

						    <tr> 

                            	<th width="5%"><label class="checkbox-inline"><input type="checkbox" id="non_tag_select_all" name="select_all" value="all"/>All</label></th>     

                            	<th>Branch</th>

                            	<th>Bill No</th>

                            	<th>Bill Date</th>

								<th>BT Code </th>

                            	<th>Product</th>

                            	<th>Design</th>

								<th>Pcs</th>

                            	<th>Gross Wt(g)</th>

                            	<th>Net Wt(g)</th>

                            	<th>Amount</th>

                            </tr>

	                    </thead> 

	                     <tbody> 

	                </tbody>

					<!--<tfoot style="font-weight:bold;">
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
					</tfoot>-->

	                 </table>

	              </div>

	               </div>

	           </div></br>

	           

	           <div class="row partly_sale_details" style="display:none;">

	               <div class="col-md-12">

	               	<div class="table-responsive">

	                 <table id="partly_sale_list" class="table table-bordered table-striped text-center">

	                    <thead>

						    <tr> 

                            	<th width="10%"><label class="checkbox-inline"><input type="checkbox" id="select_all_tag" name="select_all" value="all"/>Tag Code</label></th>     

                            	<th>Branch</th>

                            	<th>Bill No</th>

                            	<th>Bill Date</th>

								<th>BT Code</th>

								<th>Purity</th>

                            	<th>Product</th>

                            	<th>Design</th>

                            	<th>Sub Design</th>

                            	<th>Gross Wt(g)</th>

                            	<th>Sold Wt(g)</th>

                            	<th>Bal Pcs</th>
                            	<th>Bal GWt(g)</th>
                            	<th>Bal LWt(g)</th>
                            	<th>Bal NWt(g)</th>
								<th>Bal DiaWt</th>

								<th>Narration</th>

                            </tr>

	                    </thead> 

	                     <tbody> 

	                </tbody>

					<tfoot style="font-weight:bold;">
							<tr>
								<td>Total :</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td class="bal_pcs"></td>
								<td class="bal_gwt"></td>
								<td class="bal_lwt"></td>
								<td class="bal_nwt"></td>
								<td></td>
							</tr>
					</tfoot>

	                 </table>

	              </div>

	               </div>

	           </div>

	           

	           <div class="row old_metal_details" style="display:none;">

	               <div class="col-md-12">

	               	<div class="table-responsive">

	                 <table id="old_metal_sale_list" class="table table-bordered table-striped text-center">

	                    <thead>

						<tr> 

							<th width="10%"><label class="checkbox-inline"><input type="checkbox" id="select_all_old_metal" name="select_all" value="all"/>All</label></th>     

							<th>Branch</th>

							<th>Bill No</th>

							<th>Bill Date</th>

							<th>BT Code</th>

							<th>Category</th>	

							<th>Piece</th>	

							<th>Gross Wt(g)</th>

							<th>Less Wt(g)</th>

							<th>Net Wt(g)</th>

							<th>Dia Wt</th>

							<th>Purity</th>

							<th>Amount</th>

						</tr>

	                    </thead> 

	                     <tbody> 

	                </tbody>


					<tfoot style="font-weight:bold;">

						<tr>
							<td>Total :</td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td class="om_pcs"></td>
							<td class="om_gwt"></td>
							<td class="om_lwt"></td>
							<td class="om_nwt"></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						
					</tfoot>
						   

	                 </table>

	              </div>

	               </div>

	           </div>


			  	<div class="row non_tag_otr_issue" style="display:none;">

					<div class="col-md-12">

						<div class="table-responsive">

							<table id="non_tag_otherissue_list" class="table table-bordered table-striped text-center">

								<thead>

									<tr> 

										<th width="5%"><label class="checkbox-inline"><input type="checkbox" id="non_tag_select_all" name="select_all" value="all"/>All</label></th>     

										<th>Branch</th>

										<th>BT Date</th>

										<th>BT Code </th>

										<th>Product</th>

										<th>Design</th>

										<th>Pcs</th>

										<th>Gross Wt(g)</th>

										<th>Net Wt(g)</th>

									</tr>

								</thead> 

								<tbody> 

								</tbody>

								<tfoot style="font-weight:bold;">
										<tr>
											<td>TOTAL :</td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td class="nt_otr_pieces"></td>
											<td class="nt_otr_gwt"></td>
											<td class="nt_otr_nwt"></td>
										</tr>
								</tfoot>

							</table>

						</div>

					</div>

				</div></br>

	           

	           	<div class="row">

				    <div class="col-sm-12" align="center">

						<button type="button" class="btn btn-default btn-cancel">Cancel</button>

				    </div>

				</div>

	     

	        </div><!-- /.box-body -->

	        <div class="overlay" style="display:none">

			  <i class="fa fa-refresh fa-spin"></i>

			</div>

	      </div>

	    </div><!-- /.col -->

	  </div><!-- /.row -->

	</section><!-- /.content -->

</div><!-- /.content-wrapper -->

      

<div class="modal fade" id="cus_stoneModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:72%;">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Add Stone</h4>
			</div>
			<div class="modal-body">
				<div class="row">
			
			</div>
				<div class="row">
					<table id="estimation_stone_cus_item_details" class="table table-bordered table-striped text-center">
					<thead>
					<tr>
					<th width="15%">Stone Type</th>
					<th width="15%">Stone Name</th>
					<th width="15%">UOM</th>
					<th width="10%">Pcs</th>   
					<th width="20%">Wt</th>
					
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
			<button type="button" id="update_partial_sold_stone_details" class="btn btn-success">Save</button>
			<button type="button" id="close_stone_details" class="btn btn-warning" data-dismiss="modal">Close</button>
		  </div>
		</div>
	</div>
</div>




<div class="modal fade" id="cus_return_stoneModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:72%;">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Add Stone</h4>
			</div>
			<div class="modal-body">
				<div class="row">
			
			</div>
				<div class="row">
					<table id="estimation_stone_cus_item_details" class="table table-bordered table-striped text-center">
					<thead>
					<tr>
					<th width="15%">Stone Type</th>
					<th width="15%">Stone Name</th>
					<th width="10%">Pcs</th>   
					<th width="20%">Wt/UOM</th>
					
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
			<button type="button" id="update_retstone_details" class="btn btn-success">Save</button>
			<button type="button" id="close_stone_details" class="btn btn-warning" data-dismiss="modal">Close</button>
		  </div>
		</div>
	</div>
</div>




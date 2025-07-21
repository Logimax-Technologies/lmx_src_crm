  <!-- Content Wrapper. Contains page content -->

      <div class="content-wrapper">

        <!-- Content Header (Page header) -->

        <section class="content-header">

          <h1>

            Reports

			 <small>Scanned Details Report</small>

          </h1>

          <ol class="breadcrumb">

            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>

            <li><a href="#">Retail Reports</a></li>

            <li class="active">Scanned Details Report</li>

          </ol>

        </section>



        <!-- Main content -->

        <section class="content">

          <div class="row">

            <div class="col-xs-12">

               

               <div class="box box-primary">

			    <div class="box-header with-border">

                  <h3 class="box-title">Scanned Details List</h3>  <span id="total_count" class="badge bg-green"></span>  

                 

                </div>

                 <div class="box-body">  

                     <div class="row">

    				  	<div class="col-md-offset-2 col-md-8">  

    	                  <div class="box box-default">  

    	                   <div class="box-body">  

    						   <div class="row">

    								

    								<?php if($this->session->userdata('branch_settings')==1 && $this->session->userdata('id_branch')==0){?>

            		                  <div class="col-md-3"> 

            		                     <div class="form-group tagged">

            		                       <label>Select Branch</label>

            									<select id="branch_select" class="form-control ret_branch"  style="width:100%;"multiple></select>

            		                     </div> 

            		                  </div> 

            						    <?php }else{?>

            		                    	<input type="hidden" id="branch_filter"  value="<?php echo $this->session->userdata('id_branch') ?>">

            		                    	<input type="hidden" id="branch_name"  value="<?php echo $this->session->userdata('branch_name') ?>"> 

            		                  <?php }?>
									  
									  
									  <div class="col-md-3"> 

            		                     <div class="form-group tagged">

            		                       <label>Select Section</label>

            									<select id="section_select" class="form-control"  style="width:100%;" multiple></select>

            		                     </div> 

            		                  </div> 

									  <div class="col-md-3">

									  <div class="form-group tagged">

										 <label>Select Category</label> 

										<select id="category_select" class="form-control" style="width:100%;"multiple></select>

									 </div>

									</div>



    								<div class="col-md-3"> 

            		                     <div class="form-group tagged">

            		                       <label>Select Product</label>

            									<select id="prod_filter" class="form-control"  style="width:100%;" multiple></select>

            		                     </div> 

            		                  </div> 

									  </div> 

									  
									  <div class="row">

									  <div class="col-md-3"> 

										<div class="form-group tagged">

										<label>Select Design</label>

											<select id="des_select" class="form-control"  style="width:100%;" multiple></select>

										</div> 

										</div> 


										<div class="col-md-3"> 

											<div class="form-group tagged">

											<label>Select Sub Design</label>

												<select id="sub_des_select" class="form-control"   style="width:100%;" multiple></select>

											</div> 

											</div> 


            		                  <div class="col-md-3"> 

            		                     <div class="form-group tagged">

            		                       <label>Report Type</label>

            									<select id="report_type" class="form-control">

            									    <option value="1">Scanned</option>

            									    <option value="2">UnScanned</option>

            									</select>

            		                     </div> 

            		                  </div> 

									  
									  <div class="row">

    								<div class="col-md-3"> 

    									<div class="form-group">    

    										<label>Date</label> 

    										<?php   

    											$fromdt = date("d/m/Y");

    											$todt = date("d/m/Y");

    									    ?>

    			                   		    <input type="text" class="form-control pull-right dateRangePicker" id="dt_range" placeholder="From Date -  To Date" value="<?php echo $fromdt.' - '.$todt?>" readonly="">  

    									</div> 

    								</div>

									</div>


    								<div class="col-md-2"> 

    									<label></label>

    									<div class="form-group">

    										<button type="button" id="scanned_details_report" class="btn btn-info">Search</button>   

    									</div>

    								</div>

    							</div>

    						 </div>

    	                   </div> 

    	                  </div> 

                       </div> 

          

                	   	<div class="box box-info">

						<div class="box-header with-border">

						  <h3 class="box-title">Scanned Details Report</h3>

						  <div class="box-tools pull-right">

							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>

						  </div>

						</div>

						<div class="box-body">

							<div class="row">

								<div class="box-body">

								   <div class="table-responsive">

									  <table id="scanned_list" class="table table-bordered table-striped text-center">

										 <thead>

            							  <tr>

            							    <th>#</th>

            							    <th>Tag Code</th>

            							    <th>Old Tag No</th>

											<th>Image</th>

            							    <th>Tag Date</th>

            							    <th>Scanned Date</th>

											<th>Section</th>

											<th>Category</th>

            							    <th>Product</th>

											<th>Design</th>

											<th>Sub Design</th>

											<th>Size</th>

            							    <th>Pcs</th>

            							    <th>Gross wt</th>

            							    <th>Net Wt</th>

											<th>Dia Wt</th>

											<th>Remark</th>



            							  </tr>

		                            </thead> 

		                             <tbody></tbody>

		                             <tfoot style="font-weight:bold;">
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
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>

									    </tr></tfoot>

									 </table>

								  </div>

								</div> 

							</div> 

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



      
	  <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

<div class="modal-dialog">

<div class="modal-content">

<div class="modal-header">

  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

  <h4 class="modal-title" id="myModalLabel">Image Preview</h4>

</div>

<div class="modal-body">

  <img src="" id="imagepreview" style="width: 300px; height: 264px;" >

</div>

<div class="modal-footer">

  <button type="button" class="btn btn-default danger" data-dismiss="modal">Close</button>

</div>

</div>

</div>

</div>
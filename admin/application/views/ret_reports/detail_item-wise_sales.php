<style>

  	/* CSS for Drill-down */

  	.drill-collapsed {

	    display: none;

	}

	.drill-close {

	    display: none;

	}

	.drill-open {

	    display: block;

	}

	.drill-detail {

	    background:#fdfdfd

	}

	.tagged-options {

		padding-top: 20px;

	}

	/* .CSS for Drill-down */

  </style>

     <!-- Content Wrapper. Contains page content -->

      <div class="content-wrapper">

        <!-- Content Header (Page header) -->

        <section class="content-header">

          <h1>

            Reports

			 <small>Product-wise Sales</small>

          </h1>

          <ol class="breadcrumb">

            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>

            <li><a href="#">Reports</a></li>

            <li class="active">Product-wise Sales report</li>

          </ol>

        </section>

        <!-- Main content -->

        <section class="content">

          <div class="row">

            <div class="col-xs-12">

               <div class="box box-primary">

			    <div class="box-header with-border">

                  <h3 class="box-title">Product-wise Sales</h3>  <span id="total_count" class="badge bg-green"></span>  

                </div>

                 <div class="box-body">  

                  <div class="row">

				  	<div class="col-md-12">  

	                  <div class="box box-default">  

	                   <div class="box-body">  

						   <div class="row">

								<?php if($this->session->userdata('branch_settings')==1 && $this->session->userdata('id_branch')==0){?>

								<div class="col-md-2"> 

									<div class="form-group tagged">

										<label>Select Branch</label>

										<select id="branch_select" class="form-control branch_filter"></select>

									</div> 

								</div> 

								<?php }else{?>

									<input type="hidden" id="branch_filter"  value="<?php echo $this->session->userdata('id_branch') ?>"> 

								<?php }?> 

								<div class="col-md-2"> 

									<div class="form-group">    

										<label>Date</label> 

										<?php   

											$fromdt = date("d/m/Y");

											$todt = date("d/m/Y");

									    ?>

			                   		    <input type="text" class="form-control pull-right dateRangePicker" id="dt_range" placeholder="From Date -  To Date" value="<?php echo $fromdt.' - '.$todt?>" readonly="">  

									</div> 

								</div>

								<div class="col-md-2"> 

									<label>Select Metal</label>

									<select id="metal" class="form-control" style="width:100%;" multiple></select>

								</div>

								

								<div class="col-md-2"> 

									<label>Select category</label>

									<select id="category" style="width: 100%;"></select>

								</div>

								<div class="col-md-2"> 

                                		<label>Select Section</label>

                                		<select id="section_select" class="form-control" style="width:100%;"></select>

                                </div>

								<div class="col-md-2"> 

									<label>Select Product</label>

									<select id="prod_select" class="form-control" style="width:100%;"></select>

								</div>

								

							</div>

							<div class="row">

                                

                                <div class="col-md-2"> 

									<label>Report Type</label>

									<select id="sales_report_type" class="form-control" style="width:100%;">

									    <option value="1" >Summary</option>

									    <option value="2" selected>Detailed</option>

									</select>

								</div>

								

								<div class="col-md-3 tagged-options"> 


							    	<!-- <input type="radio" id="all" value="0" name="tag_or_nontag" checked/> <label for="tagged">All</label> &nbsp; -->

								
									<input type="radio" id="tagged" value="1" name="tag_or_nontag" checked/> <label for="tagged">Tagged</label> &nbsp;

									

									<input type="radio" id="non_tagged" value="2" name="tag_or_nontag" /> <label for="non_tagged">Non Tagged</label> &nbsp;

									

									<!--<input type="radio" id="all_bills" value="3" name="tag_or_nontag" checked/> <label for="all_bills">All</label>-->

									

								</div>

							    <div class="col-md-2"> 

									<label></label>

									<div class="form-group">

										<button type="button" id="item_sale_detail_search" class="btn btn-info">Search</button>   

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

				   	<div class="box box-info stock_details">

						<div class="box-body">

							<div class="row">

								<div class="box-body">

								   <div class="table-responsive detail_report" style="">

									  <table id="itemwise-sales_detail" class="table table-bordered table-striped text-center" style="text-transform:uppercase;">

										 <thead>

										  <tr>

											<th width="10%">Branch</th>

											<th width="10%">Bill No</th>

											<th width="20%">BillDate</th>

											<th width="10%">Est No</th>

											<th width="10%">Customer</th>

											
											<th width="10%">Address</th>

											<th width="15%">State</th>

											<th width="10%">GST No</th>

											<th width="10%">Karigar</th>

											<th width="10%">Image</th>

											
											<th width="10%">Tag No</th>

										    <th width="10%">Old Tag No</th>

                                            <th width="10%">Section</th>

											<th width="10%">Purity</th>

                                            <th width="10%">Product</th>

                                            
											<th width="10%">Design</th>

                                            <th width="10%">Sub Design</th>

											<th width="5%">HSN</th>

										    <th width="5%">Pcs</th>					    

										    <th width="5%">Gwt(Grams)</th>

                                            
											<th width="5%">Lwt(Grams)</th>

										    <th width="5%">Nwt(Grams)</th>

										    <th width="5%">DiaWt(CT)</th>

										    <th width="5%">TAG Gwt(Grams)</th>

										    <th width="5%">Diff Wt(Grams)</th>

										   
											<th width="5%">Wastage(%)</th>

										    <th width="5%">Wastage(Grams)</th>

										    <th width="5%">MC</th>

											<th width="5%">Amount(Rs)</th>

											<th width="5%">CGST(Rs)</th>

											
											<th width="5%">SGST(Rs)</th>

											<th width="5%">IGST(Rs)</th>

											<th width="5%">Est Rate(Rs)</th>

											<th width="5%">Total Amt(Rs)</th>

											<th width="5%">Stn Amount(Rs)</th>

											
											<th width="5%">Age</th>

										    <th width="5%">CERT NO</th>

										    <th width="5%">STYLE code</th>

											<th width="5%">Tag Status</th>


										  </tr>

					                    </thead><tbody></tbody>

									 </table>

									</div>

									 <div class="table-responsive summary_report" style="display:none;">

									     <table id="sales_summary_report" class="table table-bordered table-striped text-center" style="text-transform:uppercase;">

    										 <thead>

    										  <tr>

    											<th width="10%">Section</th>

												<th width="10%">Purity</th>

    											<th width="10%">Tags</th>

    										    <th width="5%">Pcs</th>					    

    										    <th width="5%">Gwt(Grams)</th>

    										    <th width="5%">Dia Wt(Grams)</th>

    										    <th width="5%">Nwt(Grams)</th>

    										    <th width="5%">TAG Gwt(Grams)</th>

    										    <th width="5%">Diff Wt(Grams)</th>

    										    <th width="5%">Wastage(%)</th>

    										    <th width="5%">Wastage(Grams)</th>

    										    <th width="5%">MC</th>

    											<th width="5%">Amount(Rs)</th>

    											<th width="5%">Stn Amount(Rs)</th>

    										  </tr>

    					                    </thead><tbody></tbody>

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
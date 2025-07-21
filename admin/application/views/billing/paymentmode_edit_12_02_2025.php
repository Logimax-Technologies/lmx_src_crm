      <!-- Content Wrapper. Contains page content -->
      <style>
      	.remove-btn {
      		margin-top: -168px;
      		margin-left: -38px;
      		background-color: #e51712 !important;
      		border: none;
      		color: white !important;
      	}

      	.sm {
      		font-weight: normal;
      	}
      </style>
      <div class="content-wrapper">
      	<!-- Content Header (Page header) -->
      	<section class="content-header">
      		<h1>
      			Bill Edit
      		</h1>
      		<ol class="breadcrumb">
      			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      			<li><a href="#">Billing</a></li>
      			<li class="active">Bill Edit</li>
      		</ol>
      	</section>

      	<!-- Main content -->
      	<section class="content order">

      		<!-- Default box -->
      		<div class="box box-primary">
      			<div class="box-header with-border">
      			</div>
      			<div class="box-body">
      				<?php
						if ($this->session->flashdata('chit_alert')) {
							$message = $this->session->flashdata('chit_alert');
						?>
      					<div class="alert alert-<?php echo $message['class']; ?> alert-dismissable">
      						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      						<h4><i class="icon fa fa-check"></i> <?php echo $message['title']; ?>!</h4>
      						<?php echo $message['message']; ?>
      					</div>

      				<?php } ?>
      				<!-- form container -->
      				<!-- form -->

      				<!-- <div class="row">

                               <div class="form-group">
                        <label for="" class="col-md-2 col-md-offset-3">Branch<span class="error">*</span></label>
                            <div class="col-md-3">
                                <?php if ($this->session->userdata('id_branch') == '') {  ?>
                                     <select id="branch_select" style="width:64%;" required tabindex="1"></select>
                                <?php } else { ?>
                                    <select id="branch_select" style="width:50%;" required disabled></select>
                                    <?php  } ?>
                            </div>
                               </div>

							   </div> -->
      				<br>
      				<div class="row">
      					<label for="tire_minimum_required" class="col-md-2 col-md-offset-3">Bill No<span class="error">*</span> </label>

      					<div class="col-md-3">
      						<div class="input-group">
      							<div class="input-group">
      								<span class="input-group-btn">
      									<select class="form-control" id="fin_year_select" style="width:100px;">
      										<?php
												foreach ($billing['financial_year'] as $fin_year) { ?>
      											<option value=<?php echo $fin_year['fin_year_code']; ?> <?php echo ($fin_year['fin_status'] == 1 ? 'selected' : '')  ?>><?php echo $fin_year['fin_year_name']; ?></option>
      										<?php }
												?>
      									</select>
      								</span>
      								<input type="text" class="form-control text" id="paymentmode_ed" placeholder="Enter Bill No">
      								<span class="input-group-btn">
      									<button type="button" id="billed_search" class="btn btn-default btn-flat"><i class="fa fa-search"></i></button>
      								</span>
      							</div>
      						</div>
      					</div>
      				</div>
      				<br>

      				<div class="row">
      					<label for="" class="col-md-2 col-md-offset-3">Customer Name</label>
      					<div class="col-md-3">
      						<div class="input-group">
      							<input type="text" class="form-control text" id="c_name" style="text-transform:uppercase;">
      						</div>
      					</div>
      				</div>

      				<br>
      				<div class="row">
      					<label for="" class="col-md-2 col-md-offset-3">Received Amount</label>
      					<div class="col-md-2">
      						<div class="input-group">
      							<input class="form-control" id="billed_cash" name="billing[billed_cash]" type="text" disabled value="" />
      						</div>
      					</div>
      				</div>
      				<br>
      				<div class="row">
      					<label for="" class="col-md-2 col-md-offset-3">Mobile</label>
      					<div class="col-md-2">
      						<div class="input-group">
      							<input type="text" class="form-control text" id="c_num" disabled>
      						</div>
      					</div>
      				</div>
      				<br>
      				<div class="row">
      					<label for="" class="col-md-2 col-md-offset-3">Cash</label>
      					<div class="col-md-2">
      						<div class="input-group">
      							<input class="form-control" id="make_pay_cash" autocomplete="off" name="billing[cash_payment]" type="text" placeholder="Enter Amount" value="" />
      						</div>
      					</div>
      				</div>
      				<br>
      				<div class="row">
      					<label for="" class="col-md-2 col-md-offset-3">Card Payment</label>
      					<div class="col-md-2">
      						<div class="input-group">
      							<input class="form-control" id="cc_billed_cash" name="billing[cc_billed_cash]" style="margin-right:-2px" type="text" disabled value="" />
      							<br>
      							<a class="btn bg-olive btn-xs pull-right" id="card_detail_modal" href="#" data-toggle="modal" data-target="#card-detail-modal" style="margin-right:-50px;margin-top:-13px;"><b>+</b></a>
      						</div>
      					</div>
      				</div>
      				<!--  <br>
					  <div class="row">
                          <label for="" class="col-md-2 col-md-offset-3">Debit Card</label>
						  <div class="col-md-2">
                         <div class="input-group">
						 <input class="form-control" id="dc_billed_cash" name="billing[dc_billed_cash]" style="margin-right:-2px" type="text" disabled value=""/>
						 <br>
                                 <a class="btn bg-olive btn-xs pull-right" id="card_detail_modal" href="#" data-toggle="modal" data-target="#card-detail-modal" style="margin-right:-50px;margin-top:-13px;" ><b>+</b></a>
                              </div>
							  </div>
                      </div>-->
      				<br>
      				<div class="row">
      					<label for="" class="col-md-2 col-md-offset-3">Cheque</label>
      					<div class="col-md-2">
      						<div class="input-group">
      							<input class="form-control" id="chq_billed_cash" name="billing[chq_billed_cash]" style="margin-right:-2px" type="text" disabled value="" />
      							<br>
      							<a class="btn bg-olive btn-xs pull-right" id="cheque_modal" href="#" data-toggle="modal" data-target="#cheque-detail-modal" style="margin-right:-50px;margin-top:-13px;"><b>+</b></a>
      						</div>
      					</div>
      				</div>
      				<br>
      				<div class="row">
      					<label for="" class="col-md-2 col-md-offset-3">Net Banking</label>
      					<div class="col-md-2">
      						<div class="input-group">
      							<input class="form-control" id="nb_billed_cash" name="billing[nb_billed_cash]" style="margin-right:-2px" type="text" disabled value="" />
      							<br>
      							<a class="btn bg-olive btn-xs pull-right" id="net_bank_modal" href="#" data-toggle="modal" data-target="#net_banking_modal" style="margin-right:-50px;margin-top:-13px;"><b>+</b></a>
      						</div>
      					</div>
      				</div>

      			</div>

      			<p class="hepl-block"></p>
      			<div class="row">
      				<br />
      				<div class="col-xs-offset-5">
      					<input type="hidden" id="card_payment" name="billing[card_pay]" value="">
      					<input type="hidden" id="chq_payment" name="billing[chq_pay]" value="">
      					<input type="hidden" id="nb_payment" name="billing[net_bank_pay]" value="">
      					<input type="hidden" id="hidden_bill_id" value="">
      					<button class="btn btn-primary" id="save_bill_edit">Save</button>
      					<button type="button" class="btn btn-default btn-cancel" id="cancel_bill_edit">Cancel</button>

      				</div> <br />

      			</div>
      		</div> <!-- box-body-->
      		<div class="overlay" style="display:none">
      			<i class="fa fa-refresh fa-spin"></i>
      		</div>
      </div> <!-- Default box-->
      <?php echo form_close(); ?>

      <!-- /form -->
      </section>
      </div>
      </div>

      <div class="modal fade" id="card-detail-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      	<div class="modal-dialog" style="width:60%;">
      		<div class="modal-content">
      			<div class="modal-header">
      				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
      				<h4 class="modal-title" id="myModalLabel">Card Details</h4>
      			</div>
      			<div class="modal-body">
      				<div class="box-body">
      					<div class="row">
      						<div class="col-sm-12 pull-right">
      							<button type="button" class="btn bg-olive btn-sm pull-right" id="new_card"><i class="fa fa-user-plus"></i>ADD</button>
      							<p class="error "><span id="cardPayAlert"></span></p>
      						</div>
      					</div>
      					<p></p>
      					<div class="table-responsive">
      						<table id="card_details" class="table table-bordered">
      							<thead>
      								<tr>
      									<th>Card Name</th>
      									<th>Type</th>
      									<th>Device<span class="error">*</span></th>
      									<th>Card No</th>
      									<th>Amount</th>
      									<th>Approval No</th>
      									<th>Action</th>
      								</tr>
      							</thead>
      							<tbody>

      							</tbody>
      							<tfoot>
      								<tr>
      									<th colspan=3>Total</th>
      									<th colspan=3>
      										<span class="cc_total_amount"></span>
      										<span class="cc_total_amt" style="display: none;"></span>
      										<span class="dc_total_amt" style="display: none;"></span>
      									</th>
      								</tr>
      							</tfoot>
      						</table>
      					</div>
      				</div>
      			</div>
      			<div class="modal-footer">
      				<a href="#" id="add_newcc" class="btn btn-success">Save</a>
      				<button type="button" class="btn btn-close btn-warning" data-dismiss="modal">Close</button>
      			</div>
      		</div>
      	</div>
      </div>
      <!-- / Advance Adj -->
      <!-- cheque-->
      <!-- Card Details -->
      <div class="modal fade" id="cheque-detail-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      	<div class="modal-dialog" style="width:70%;">
      		<div class="modal-content">
      			<div class="modal-header">
      				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
      				<h4 class="modal-title" id="myModalLabel">Cheque Details</h4>
      			</div>
      			<div class="modal-body">
      				<div class="box-body">
      					<div class="row">
      						<div class="col-sm-12 pull-right">
      							<button type="button" class="btn bg-olive btn-sm pull-right" id="new_chq"><i class="fa fa-user-plus"></i>ADD</button>
      							<p class="error "><span id="chqPayAlert"></span></p>
      						</div>
      					</div>
      					<p></p>
      					<div class="table-responsive">
      						<table id="chq_details" class="table table-bordered">
      							<thead>
      								<tr>
      									<!-- <th>Cheque Date</th>
      									<th>Bank</th>
      									<th>Cheque No</th>
      									<th>Amount</th>
      									<th>Action</th> -->

										<th width="20%">Cheque Date</th>
										<th width="20%">Bank</th>
										<th width="25%">Cheque No</th>
										<th width="30%">Amount</th>
										<th width="5%">Action</th>
      								</tr>
      							</thead>
      							<tbody>

      							</tbody>
      							<tfoot>
      								<tr>
      									<!-- <td>Total</td>
      									<td></td>
      									<td></td>
      									<td><span class="chq_total_amount"></span></td>
      									<td></td> -->

										  <td colspan="2">Total</td>
										  <td colspan="3"><span class="chq_total_amount"></span></td>
      								</tr>
      							</tfoot>
      						</table>
      					</div>
      				</div>
      			</div>
      			<div class="modal-footer">
      				<a href="#" id="add_newchq" class="btn btn-success">Save</a>
      				<button type="button" class="btn btn-close btn-warning" data-dismiss="modal">Close</button>
      			</div>
      		</div>
      	</div>
      </div>
      <!-- cheque-->
      <!-- Net Banking-->
      <div class="modal fade" id="net_banking_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      	<div class="modal-dialog" style="width:60%;">
      		<div class="modal-content">
      			<div class="modal-header">
      				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
      				<h4 class="modal-title" id="myModalLabel">Bank Details</h4>
      			</div>
      			<div class="modal-body">
      				<div class="box-body">
      					<div class="row">
      						<div class="col-sm-12 pull-right">
      							<button type="button" class="btn bg-olive btn-sm pull-right" id="new_net_bank"><i class="fa fa-user-plus"></i>ADD</button>
      							<p class="error "><span id="NetBankAlert"></span></p>
      						</div>
      					</div>
      					<p></p>
      					<div class="table-responsive">
      						<table id="net_bank_details" class="table table-bordered">
      							<thead>
      								<tr>
      									<!-- <th>Type</th>
      									<th class="upi_type">Bank</th>
      									<th>Payment Date</th>
      									<th class="device">Device</th>
      									<th>Ref No</th>
      									<th>Amount</th>
      									<th>Action</th> -->

										<th width="15%">Type</th>
										<th width="20%">Bank/Device</th> 
										<th width="20%">Payment Date</th> 
										<th width="20%">Ref No</th>  
										<th width="20%">Amount</th>  
										<th width="5%">Action</th>
      								</tr>
      							</thead>
      							<tbody>
      								<!--	<tr>
								<td><select name="nb_details[nb_type][]" class="nb_type"><option value=1>RTGS</option><option value=2>IMPS</option></select></td>
								<td><select class="form-control id_bank" name="nb_details[id_bank][]" ></select></td>
								<td><input type="number" step="any" class="ref_no" name="nb_details[ref_no][]"/></td>
								<td><input type="number" step="any" class="amount" name="nb_details[amount][]"/></td>
								<td><a href="#" oonclick="removeNb_row($(this).closest('tr'))" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>
							</tr> -->
      							</tbody>
      							<tfoot>
      								<tr>
      									<th colspan=3>Total</th>
      									<th colspan=3>
      										<span class="nb_total_amount"></span>
      									</th>
      								</tr>
      							</tfoot>
      						</table>
      					</div>
      				</div>
      			</div>
      			<div class="modal-footer">
      				<a href="#" id="add_newnb" class="btn btn-success">Save</a>
      				<button type="button" class="btn btn-close btn-warning" data-dismiss="modal">Close</button>
      			</div>
      		</div>
      	</div>
      </div>
      <!-- Net Banking-->
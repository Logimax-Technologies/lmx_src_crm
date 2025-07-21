  <!-- Content Wrapper. Contains page content --> 
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Receipt
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Receipt</a></li>
        <li class="active">Branch Transfer</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Add Receipt</h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body"> 
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
             <!-- form -->
			<form id="receipt_billing">
			<p class="help-block"></p> 
			<div class="row">
				<div class="col-md-7">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
			                  <label for="">Select Branch <span class="error"> *</span></label>
			                 	<select id="branch_select" class="form-control" name="receipt[id_branch]"></select>
			                </div>
			                <span id="branchAlert" class="error"></span>
						</div>
						<div class="col-md-4">
							<div class="form-group">
			                  <label for="">Customer <span class="error"> *</span></label>
			                  <input type="text" name="name" id="name" class="form-control" autocomplete="off" placeholder="Enter Name/Mobile"> 
			                  <input type="hidden" name="receipt[id_customer]" id="id_customer">  
			                </div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
			                  <label for="">Receipt Type <span class="error"> *</span></label>
			                  <div class="form-group"> 
			                      <input type="radio" name="receipt[receipt_type]" id="receipt_type1" value="1" checked=""> Credit Collection &nbsp;&nbsp;
			                      <input type="radio" name="receipt[receipt_type]" id="receipt_type2" value="2" > Advance  &nbsp;&nbsp;
				              </div>
			                </div>
						</div>
						
					</div> 
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
			                  <label for="">Credit No <span class="error"> *</span></label>
			                  <div class="form-group"> 
			                     <input type="text" id="receipt_for" name="receipt[receipt_for]" class="form-control" autocomplete="off" placeholder="Enter Credit No"> 
			                     <input type="hidden" name="receipt[receipt_ref_id]" id="receipt_ref_id">
			                     <input type="hidden" name="receipt[due_amount]" id="due_amount">
			                     <input type="hidden" name="receipt[paid_amount]" id="paid_amount">
				              </div>
				              <span id="creditAlert"></span>
			                </div>
						</div> 
						<div class="col-md-3">
							<div class="form-group">
			                  <label for="">Receipt As <span class="error"> *</span></label>
			                  <div class="form-group"> 
			                      <input type="radio" name="receipt[receipt_as]" id="receipt_as1" value="1" checked=""> Amount &nbsp;&nbsp;
			                      <input type="radio" name="receipt[receipt_as]" id="receipt_as2" value="2"> Weight  &nbsp;&nbsp;
				              </div>
			                </div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
			                  <label for="">Store As <span class="error"> *</span></label>
			                  <div class="form-group"> 
			                      <input type="radio" name="receipt[store_receipt_as]" id="store_receipt_as_1" value="1" checked=""> Amount &nbsp;&nbsp;
			                      <input type="radio" name="receipt[store_receipt_as]" id="store_receipt_as_2" value="2"> Weight  &nbsp;&nbsp;
				              </div>
			                </div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
			                  <label for="">Rate Calculation From <span class="error"> *</span></label>
			                  <div class="form-group"> 
			                      <input type="radio" name="receipt[rate_calc]" id="store_receipt_as_1" value="1" checked=""> Gold &nbsp;&nbsp;
			                      <input type="radio" name="receipt[rate_calc]" id="store_receipt_as_2" value="2"> Silver  &nbsp;&nbsp;
				              </div>
			                </div>
						</div> 
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
			                  <label for="">Amount <span class="error"> *</span></label>
			                  <div class="form-group"> 
			                      <input type="number" step="any" name="receipt[amount]" id="amount" class="form-control" autocomplete="off" disabled> 
				              </div>
			                </div>
						</div>						
						<div class="col-md-4">
							<div class="form-group">
			                  <label for="">Weight </label>
			                  <div class="form-group"> 
			                      <input type="number" step="any" name="receipt[weight]" id="weight" class="form-control" readonly=""> 
				              </div>
			                </div>
						</div>
						<div class="col-md-4">
							<label for="">Esti No. </label>
							<div class="input-group" >
								<input type="number" name="esti_no" id="esti_no" class="form-control" autocomplete="off" /> 
								<span class="input-group-btn">
			                      <button type="button" id="est_search" class="btn btn-default btn-flat" >Search</button>
			                    </span>
							</div>
							 <span id="searchEstiAlert" style="color: red;"></span>
						</div> 
					</div>
					
						<div class="row">
						<div class="col-md-12">  
						   <div class="row">
								<div class="box-body">
								   <div class="table-responsive">
									 <table id="sales_item_details" class="table table-bordered table-striped text-center">
										<thead>
										  <tr>
											<th>Tag</th>
											<th>Product</th> 
											<th>G.Wt</th>
											<th>L.Wt</th>   
											<th>N.Wt</th>   
											<th>Wastage(%)</th>
											<th>Amount</th>
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
							</div> 
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-12">  
						   <div class="row">
								<div class="box-body">
								   <div class="table-responsive">
									 <table id="purchase_item_details" class="table table-bordered table-striped text-center">
										<thead>
										  <tr>
											<th>Purpose</th>
											<th>Product</th> 
											<th>G.Wt</th>   
											<th>D.Wt</th>   
											<th>S.Wt</th>   
											<th>N.Wt</th>   
											<th>Wastage(%)</th>
											<th>Wastage.Wt(%)</th>
											<th>Rate Per Gram</th>
											<th>Amount</th>
											<th>Est No</th>
											<th>Action</th>
										  </tr>
										</thead> 
										<tbody>
											<!--<?php if($this->uri->segment(3) == 'edit'){
									foreach($est_other_item['old_matel_details'] as $ikey => $ival){
									$net_wt=0;
									$other_stone_price=0;
									$other_stone_wt=0;
									$stone_data=array();
									$net_wt=$ival['gross_wt']-($ival['dust_wt']+$ival['stone_wt']);
									foreach ($ival['stone_details'] as $data) {
									$other_stone_price	+=	$data['price'];
									$other_stone_wt	+=	$data['wt'];
									$stone_data[]=array(
									'bill_item_stone_id'=>$data['bill_item_stone_id'],
									'stone_id'			=>$data['stone_id'],
									'stone_pcs'			=>$data['pieces'],
									'stone_wt'			=>$data['wt'],
									'stone_price'		=>$data['price']
									);
									}
									$stone_details=json_encode($stone_data);
									echo '<tr id="'.$ikey.'">
									<td><span>'.($ival['purpose']==1 ? 'Cash' :'Exchange').'</span></td>
									<td><span>'.($ival['metal_type']==1 ? 'Gold':'Silver').'</span>
										<input type="hidden" class="is_est_details" value="1" name="purchase[is_est_details][]" />
										<input type="hidden" class="item_type" name="purchase[itemtype][]" value="'.$ival['item_type'].'" />
									    <input type="hidden" class="pur_metal_type"value="'.$ival['metal_type'].'" name="purchase[metal_type][]"/>
									    <input type="hidden" class="old_metal_sale_id" value="'.$ival['old_metal_sale_id'].'" name="purchase[old_metal_sale_id][]"/>
										</td>
									<td>-</td>
									<td><input type="number" class="pur_pcs" name="purchase[pcs][]" value="1" /></td>
									<td><span>'.$ival['gross_wt'].'</span><input type="hidden" class="pur_gross_val" name="purchase[gross][]" value="'.$ival['gross_wt'].'"/></td>
									<td><span>-</span><input type="hidden" class="pur_less_val" name="purchase[less][]" value="" /></td>
									<td>
										<span>'.$net_wt.'</span>
										<input type="hidden" class="pur_net_val" name="purchase[net][]" value="'.$net_wt.'" />
										<input type="hidden" class="est_old_dust_val" name="purchase[dust_wt][]" value="'.$ival[
											'dust_wt'].'" />
										<input type="hidden" name="purchase[stone_wt][]" class="est_old_stone_val" value="'.$ival['stone_wt'].'"/>
									</td>
									<td><span>'.$ival['wastage_percent'].'</span><input type="hidden" class="pur_wastage" name="purchase[wastage][]" value="'.$ival['wastage_percent'].'" />
									</td>
									<td><input type="number" class="pur_discount" name="purchase[discount][]" value="'.$ival['bill_discount'].'" />
									</td>
									<td>
									<a href="#" onClick="create_new_empty_bill_purchase_stone_item($(this).closest(\'tr\'));" class="btn btn-success"><i class="fa fa-plus"></i></a><input type="hidden" class="stone_details"  name="purchase[stone_details][]" value='.$stone_details.'><input type="hidden" class="other_stone_price" value="'.$other_stone_price.'" /><input type="hidden" class="other_stone_wt" value="'.$other_stone_wt.'" /><input type="hidden" class="bill_material_price" value=""/>
									</td>
									<td><input type="number" class="bill_amount" name="purchase[billamount][]" value="'.$ival['amount'].'" step="any" readonly /><input type="hidden" class="bill_rate_per_grm" name="purchase[rate_per_grm][]" value="'.$ival['rate_per_gram'].'" step="any" readonly /></td>
									<td><span>'.$ival['est_id'].'</span><input type="hidden" class="pur_est_id" name="purchase[estid][]" value="'.$ival['est_id'].'" /></td>
									<td>-</td>
									</tr>';
												}
											}?>-->
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
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
			                  <label for="">Narration <span class="error"> *</span></label>
			                  <div class="form-group"> 
			                      <textarea name="receipt[narration]" id="narration"  
			                      class="form-control" rows="5" cols="100"> </textarea>
				              </div>
			                </div>
						</div>
					</div> 
				</div>
				<div class="col-sm-5">
					<div class="box box-info payment_blk">
						<div class="box-header with-border">
						  <h3 class="box-title">Make Payment</h3>
						  <div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
						  </div>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="box-body">
									   <div class="table-responsive">
										 <table id="payment_modes" class="table table-bordered table-striped">
											<thead>
											</thead> 
											<tbody>
												<tr>
													<td class="text-right"><b class="custom-label">Receive</b></td>
													<th class="text-right"><?php echo $this->session->userdata('currency_symbol')?></th>
													<td> 
														<input type="number" style="width:130px" class="form-control receive_amount" name="payment[tot_amt_received]" value="<?php echo set_value('payment[tot_amt_received]',isset($payment['tot_amt_received'])?$payment['pan_no']:0); ?>" >
													</td>
												</tr> 
												<tr>
													<td class="text-right"><b class="custom-label">PAN No</b></td>
													<th class="text-right"></th>
													<td> 
													<input type="hidden" id="min_pan_amt" value="<?php echo $settings['min_pan_amt'];?>">
													<input type="hidden" id="is_pan_required" value="<?php echo $settings['is_pan_required'];?>">
													<input type="text" style="width:130px" class="form-control pan_no" name="receipt[pan_no]" id="pan_no" value="<?php echo set_value('payment[pan_no]',isset($payment['pan_no'])?$payment['pan_no']:NULL); ?>" disabled>
													</td>
												</tr>
												<tr>
													<td class="text-right"><b class="custom-label">Image</b></td>
													<th class="text-right"></th>
													<td> 
													<input type="file" id="pan_images"  multiple disabled>
													<input type="hidden" 
													id="panimg" name="receipt[pan_img]">
													<div id="pan_preview" ></div>
													</td>
												</tr>  
												<?php 
												$modes = $this->ret_billing_model->get_payModes();
												if(sizeof($modes)>0){
												foreach($modes as $mode){
													$cash = ($mode['short_code'] == "CSH" ? '<input class="form-control cash_pay" style="width:130px" id="make_pay_cash" name="payment[cash_payment]" type="text" placeholder="Enter Amount" value=""/>' : '');
													$card = ($mode['short_code'] == "CC" || $mode['short_code'] == "DC" ? '<a class="btn bg-olive btn-xs pull-right" id="card_detail_modal" href="#" data-toggle="modal" data-target="#card-detail-modal" ><b>+</b>	<input type="hidden" id="card_payment" name="payment[card_pay]" value=""></a> ' : '');
													$cheque = ($mode['short_code'] == "CHQ"  ? '<a class="btn bg-olive btn-xs pull-right" id="cheque_modal" href="#" data-toggle="modal" data-target="#cheque-detail-modal" ><b>+</b><input type="hidden" id="chq_payment" name="payment[chq_pay]" value=""></a> ' : '');
													$net_banking = ($mode['short_code'] == "NB"  ? '<a class="btn bg-olive btn-xs pull-right"  href="#" data-toggle="modal" data-target="#net_banking_modal" ><b>+</b><input type="hidden" id="nb_payment" name="payment[net_bank_pay]" value=""></a> ' : '');
												?>
												<tr>
													<td class="text-right"><?php echo $mode['mode_name']; ?>
													</td>
													<td class="text-right"><?php echo $this->session->userdata('currency_symbol')?></td>
													<td class="mode_<?php echo $mode['short_code']; ?>">
													<span class="<?php echo $mode['short_code'];?>"></span>
													<?php echo $cash; ?> 
													<?php echo $card; ?> 
													<?php echo $cheque; ?> 
													<?php echo $net_banking; ?> 
													</td> 
												</tr>
												<?php }}?>
											
												
												
											</tbody>
											<tfoot>
												<tr>
													<th class="text-right custom-label">Total</th>
													<th class="text-right"><?php echo $this->session->userdata('currency_symbol')?></th>
													<th class="receipt_total_amount"></th>
													
												</tr>
												<tr>
													<th class="text-right custom-label">Balance</th>
													<th class="text-right"><?php echo $this->session->userdata('currency_symbol')?></th>
													<th class="receipt_bal_amount"></th>
												</tr>
											</tfoot>
										 </table>
									  </div>
									</div> 
								</div> 
							</div>
						</div>
					</div>
				</div>
			</div> 
			</form>
			<p class="help-block"></p> 
			<!--End of row-->
		</div>	     
		<div class="box-footer clearfix"> 
			<div class="row">
				<div class="col-xs-offset-5">
					<button id="save_receipt" type="submit" class="btn btn-primary btn-flat" disabled=""><i class="fa fa-plus"></i> Save</button>
					<button type="button" class="btn btn-default btn-flat btn-cancel">Back</button>
				</div> <br/>
			</div>
		</div> 
        <div class="overlay" style="display:none">
		  <i class="fa fa-refresh fa-spin"></i>
		</div>
  </div>    
 </section>
</div> 
  

  <!-- Card Details -->
<div class="modal fade" id="card-detail-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
								<th>Card No</th> 
								<th>Amount</th> 
								<th>Approval No</th> 
								<th>Action</th>
							</tr>											
						</thead> 
						<tbody>
							<?php if($this->uri->segment(3) == 'edit'){
								/*foreach($est_other_item['card_details'] as $ikey => $ival){
										echo '<tr><td><input class="card_name" type="number" name="card_details[card_name][]" value="'.$ival['card_name'].'" /></td><td><input class="card_type" type="number" name="card_details[card_type][]" value="'.$ival['card_type'].'" /></td><td><input type="number" class="card_no" style="width: 100px;"  name="card_details[card_no][]" value="'.$ival['card_no'].'"  /></td><td><input type="number" class="card_amt" style="width: 100px;"  name="card_details[card_amt][]" value="'.$ival['card_amt'].'"  /></td><td>-</td></tr>';
								}*/
							}else{ ?>
							<tr> 
								<td><select name="card_details[card_name][]" class="card_name"><option value=2>VISA</option><option value=2>RuPay</option><option value=3>Mastro</option><option value=4>Master</option></select></td>
								<td><select name="card_details[card_type][]" class="card_type"><option value=1>CC</option><option value=2>DC</option></select></td>
								<td><input type="number" step="any" class="card_no" name="card_details[card_no][]"/></td> 
								<td><input type="number" step="any" class="card_amt" name="card_details[card_amt][]"/></td> 
								<td><input type="number" step="any" class="ref_no" name="card_details[ref_no][]"/></td> 
								
								<td><a href="#" onclick="removeCC_row($(this).closest('tr')) ;" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>
							</tr> 
							<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<th  colspan=3>Total</th>
								<th colspan=2>
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
			<a href="#" id="add_card" class="btn btn-success">Save</a>
			<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
		  </div>
		</div>
	</div>
</div>
<!-- Card Details -->

<div class="modal fade" id="cheque-detail-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
							<button type="button" class="btn bg-olive btn-sm pull-right" id="new_chq"><i class="fa fa-user-plus"></i>ADD</button>
							<p class="error "><span id="chqPayAlert"></span></p>
						</div>
					</div>
					<p></p>
				   <div class="table-responsive">
					 <table id="chq_details" class="table table-bordered">
						<thead>
							<tr> 
								<th>Cheque Date</th>
								<th>Bank</th> 
								<th>Branch</th>  
								<th>Cheque No</th>  
								<th>IFSC Code</th>  
								<th>Amount</th>  
								<th>Action</th> 
							</tr>											
						</thead> 
						<tbody>
							<tr> 
								<td><input id="cheque_datetime" data-date-format="dd-mm-yyyy hh:mm:ss" class="cheque_date" name="cheque_details[cheque_date][]" type="text"  placeholder="Cheque Date" /></td>
								<td><input name="cheque_details[bank_name][]" type="text"  class="bank_name"></td>
								<td><input name="cheque_details[bank_branch][]" type="text" class="bank_branch"></td>
								<td><input type="number" step="any" class="cheque_no" name="cheque_details[cheque_no][]"/></td> 
								<td><input type="text" step="any" class="bank_IFSC" name="cheque_details[bank_IFSC][]"/></td> 
								<td><input type="number" step="any" class="payment_amount" name="cheque_details[payment_amount][]"/></td> 

								<td><a href="#" onclick="removeCC_row($(this).closest('tr')) ;" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>
							</tr> 
						</tbody>
						<tfoot>
							<tr>
								<td>Total</td><td></td><td></td><td></td><td></td><td><span class="chq_total_amount"></span></td><td></td>
							</tr>
						</tfoot>
					 </table>
				  </div>
				</div>  
			</div>
		  <div class="modal-footer">
			<a href="#" id="save_chq" class="btn btn-success">Save</a>
			<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
		  </div>
		</div>
	</div>
</div>
<!-- cheque-->
<!-- Net Banking-->
<div class="modal fade" id="net_banking_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:60%;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Net Banking Details</h4>
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
								<th>Type</th>
								<th>Bank</th> 
								<th>Ref No</th>  
								<th>Amount</th>  
								<th>Action</th> 
							</tr>											
						</thead> 
						<tbody>
						<!--	<tr> 
								<td><select name="nb_details[nb_type][]" class="nb_type"><option value=1>RTGS</option><option value=2>IMPS</option></select></td>
								<td><input type="number" step="any" class="ref_no" name="nb_details[ref_no][]"/></td> 
								<td><input type="number" step="any" class="amount" name="nb_details[amount][]"/></td> 
								<td><a href="#" onClick="removeChq_row($(this).closest(\'tr\'));" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>  
							</tr> -->
						</tbody>
						<tfoot>
							<tr>
								<th  colspan=2>Total</th>
								<th colspan=2>
									<span class="nb_total_amount"></span>
								</th>
							</tr>
						</tfoot>
					 </table>
				  </div>
				</div>  
			</div>
		  <div class="modal-footer">
			<a href="#" id="save_net_banking" class="btn btn-success">Save</a>
			<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
		  </div>
		</div>
	</div>
</div>
<!-- Net Banking-->
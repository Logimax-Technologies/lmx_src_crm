      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Payment
            <small></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo site_url('admin/dashboard');?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo site_url('payment/list');?>">Payment</a></li>
            <li class="active">Scheme Payment</li>
          </ol>
        </section>
        <!-- Main content -->
        <section class="content">
          <!-- Default box -->
          <div class="box">
            <div class="box-header with-border">
             <div class="row"> 
            	<div class="col-md-4">
             		 <h3 class="box-title">Payment Form</h3>
            	</div>
             	 <div class="col-md-3">
                  	 <a class="btn btn-success pull-right" id="add_customer" href="<?php echo base_url('index.php/customer/add');?>" ><i class="fa fa-plus-circle"></i> Add Customer</a> 
				  </div>
              	<div class="col-md-3">
                  	 <a class="btn btn-success pull-right" id="add_customer" href="<?php echo base_url('index.php/account/add');?>" ><i class="fa fa-plus-circle"></i> Add Account</a> 
				  </div>
            </div>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <!-- <?php $attributes = array('autocomplete' => 'off', 'id' => 'pay_form');
              echo form_open((  $pay['id_payment']!=NULL && $pay['id_payment']>0 ?'payment/update/'.$pay['id_payment']:'payment/save_all'),$attributes) ?>-->
             
		    <form id="pay_form">
              <div class="col-md-12">   
                    <div class="row">
                    	<div class="col-sm-4">
                    		<div class="form-group">
                    			<label for="" >Customer Mobile</label>
								<input type="text" class="form-control mobile_number" name="mobile_number" placeholder="Enter Mobile Number" id="mobile_number" style="width: 99%;">
                    			<!--<select class="form-control" id="customer"></select>-->
                    			<input type="hidden" name="generic[id_customer]" id="id_customer"/>
                    		</div>	
                    	</div>
						<!--<div class="col-md-4">
							<div class="form-group" >
							   <label>Select Scheme &nbsp;</label>
								<select id="scheme_select" class="form-control">
								</select>
								<input id="id_scheme" name="generic[id_scheme]" type="hidden" value=""/>
							</div>
						</div>-->
						<div class="col-md-4">
						  <div class="form-group">
                           <label for="" >Scheme A/c No</label>
                             <select class="form-control" name="generic[id_scheme_account]" id="scheme_account"></select>
                          <input type="hidden" class="form-control" id="id_scheme_account"  value="<?php echo set_value('generic[id_scheme_account]',$pay['id_scheme_account']); ?>" />
                          </div>
					    </div>
                    	<div class="col-sm-4 pull-right" id="enable_editing_blk" style="display<?php echo  ($pay['edit_addpay_page'] == 1 ? ':block':':none')?>" >
                    	    <?php if(($this->config->item('showToAdminsOnly') == 1 && $this->session->userdata('profile') <=2) || $this->config->item('showToAdminsOnly') == 0){?> 
                    		<div class="form-group">
                    			<label for="" >Enable editing</label>
                    			<input type="checkbox" name="generic[enable_editing]" id="enable_editing" value='1' />
                    		</div>	
                    		<?php }else {?>
                    		<input type="hidden" name="generic[enable_editing]" value='0' />
                    		<?php } ?>
                    	</div>
                    </div>
<!-- setting based branch option hh -->
              		<div class="row">
                      <?php if(($this->session->userdata('branch_settings')==1) && (($this->session->userdata('id_branch')=='')&&($this->session->userdata('is_branchwise_cus_reg')!=1) || ($this->session->userdata('branchWiseLogin')!=1 ))){?>
						<div class="col-md-4">
							         <?php if(($this->session->userdata('branch_settings')==1) && (($this->session->userdata('id_branch')=='')&&($this->session->userdata('is_branchwise_cus_reg')!=1))  && ($pay['cost_center'] == 1 || $pay['cost_center'] == 2) && ($this->session->userdata('branchWiseLogin')!=1 )){?> 
							       <!-- Depends on cost center settings, have to make the branch selection. [select the scheme accounts branch and make the field readonly]  HH-->
							       			<div class="form-group" >
										   <label>Select The Branch &nbsp;</label>
											<select id="select_branch" class="form-control" placeholder="Select branch name" readonly disabled>
											</select>
											<input id="id_branch" name="generic[id_branch]" type="hidden" value="" />
										
											<input type="hidden"  id="branch_settings" value="<?php echo$this->session->userdata('branch_settings'); ?>" >
										</div>
							       
							       <?php } else if(($this->session->userdata('branch_settings')==1) && (($this->session->userdata('id_branch')=='')&&($this->session->userdata('is_branchwise_cus_reg')!=1)) &&  ($this->session->userdata('branchWiseLogin')!=1 )){?>
										<div class="form-group" >
										   <label>Select Branch &nbsp;</label>
											<select id="branch_select" class="form-control" >
											</select>
											<input id="id_branch" name="generic[id_branch]" type="hidden" value=""/>
										
											<input type="hidden"  id="branch_settings" value="<?php echo$this->session->userdata('branch_settings'); ?>" >
										</div>
							       <?php } else {?>
							       	<input type="hidden" name="generic[id_branch]"  value="<?php echo$this->session->userdata('id_branch'); ?>" >
							       <?php }?>
							    </div>
						<?php }?>	
	              		<div class="col-sm-4">
	              			<div class="form-group"> <label>Today's Rate</label>
	              			<div class="input-group ">
	              			    <span class="input-group-addon input-sm"><?php echo $this->session->userdata('currency_symbol')?></span>
	              				<input type="text" class="form-control input-sm" id="metal_rate" name="generic[metal_rate]" readonly="true" />
	                     <input type="hidden" class="form-control"  name="generic[payment_type]" value="Manual"  /> 	
	              			</div>
	              			</div>
	              		</div>
	              		<div class="col-sm-4 ">
		              		<div class="form-group">
		          				<label for="">Payment Date</label>
		          					<div  id="date_payment_block">
					                </div>
		          			</div>	
	              		</div>	
	              	</div>
	              	<div class="row">
			            <div class="col-xs-12 col-md-12 col-lg-12">
			                <div  id="scheme-detail-box" class="box box-solid box-default">
		                    <div class="box-header with-border">
			                    <h3 class="box-title">Scheme A/c Details</h3>
	                    	</div>
			                    <div class="box-body">
				                    <div class="col-xs-12 col-md-4 col-lg-4 pull-left">
				                       <table class="table table-condensed">
				                       	<tr>
				                       		<th>Joined on</th>
				                       		<td><span id="start_date"></span></td>
				                       	</tr> 	
				                       	<tr>
				                       		<th>A/c Name</th>
				                       		<td><span id="acc_name"></span></td>
				                       	</tr> 	
				                       	<tr>
				                       		<th>Scheme Code</th>
				                       		<td><span id="scheme_code"></span></td>
				                       	</tr> 	
				                       	<tr>
				                       		<th>Type</th>
				                       		<td><span  id="scheme_type"></span></td>
				                       	</tr>
				                       	<tr>
				                       		<th>Amount to weight Conversion</th>
				                       		<td>
				                       		<input type="hidden" id="fix_weight" name="generic[fix_weight]" value="<?php echo set_value('generic[fix_weight]'); ?>" />
				                       		<input type="hidden" id="is_flexible_wgt" />
				                       		<!-- weight conbvert daily or closing-->
											<input type="hidden" id="wgt_cvrt" name="generic[wgt_convert]" value="<?php echo set_value('generic[wgt_convert]'); ?>" />
				                       		<!-- weight conbvert daily or closing-->
				                       		<span id="amt_to_wgt"></span></td>
				                       	</tr>
				                       	<tr >
				                       		<th >Assumed Weight</th>
				                       		<td>
				                       		<span id="amttowgt"></span></td>
				                       	</tr>
				                       		<tr >
				                       		<th >Disabled Payment Reason.</th>
				                       		<td>
				                       		<span id="disable_pay_reason"></span></td>
				                       	</tr>
				                       </table>			                        
				                     </div>          
				                     <div class="col-xs-12 col-md-4 col-lg-4">
				                       <table class="table table-condensed">
				                       	<tr>
				                       		<th>Payable</th>
				                       		<td>
				                       		<input type="hidden" id="sch_amt" name="generic[sch_amt]" value="<?php echo set_value('generic[sch_amt]'); ?>" />
				                       		<span id="payable"></span></td>
				                       	</tr> 
				                       	<tr>
				                       		<th>Paid Installments</th>
				                       		<td>
				                       			<span id="paid_installments"></span>
				                       			<input type="hidden" id="paid_ins" name="generic[total_paid]"/>				
												<input type="hidden" id="paidinstall" name="generic[paid_installments]"/>
				                       			<input type="hidden" id="ref_benifit_ins" name="generic[ref_benifitadd_ins]"/>
				                       			<input type="hidden" id="referal_code" name="generic[referal_code]"/> 
				                       			<input type="hidden" id="discount" name="generic[discount]"/> 
				                       			<input type="hidden" id="discount_installment" name="generic[discount_installment]"/> 
				                       			<input type="hidden" id="discount_type" name="generic[discount_type]"/> 
				                       			<input type="hidden" id="firstPayDisc_value" name="generic[firstPayDisc_value]"/> 
				                       			<input type="hidden" id="discountedAmt" name="generic[discountedAmt]"/> 
				                       			<input type="hidden" id="ref_benifitadd_by" name="generic[ref_benifitadd_ins_type]"/> 
												<input type="hidden" id="max_dues" name="generic[max_dues]"/> 
												<input type="hidden" id="pay" name="generic[payable]"/> 
												<input type="hidden" id="firstPayamt_maxpayable" name="generic[firstPayamt_maxpayable]"/> 
												<input type="hidden" id="firstPayamt_as_payamt" name="generic[firstPayamt_as_payamt]"/> 
												<input type="hidden" id="sch_type" name="generic[sch_type]"/> 
												<input type="hidden" id="flexible_sch_type" name="generic[flexible_sch_type]"/> 
												<input type="hidden" id="maturity_type" name="generic[maturity_type]"/> 
												<input type="hidden" id="total_installments" name="generic[total_installments]"/> 
				                       		</td>
				                       	</tr> 	
				                       	<tr>
				                       		<th>Amount Paid</th>
				                       		<td><span id="total_amount_paid"></span></td>
				                       	</tr> 	
				                       	<tr>
				                       		<th>Weight Paid</th>
				                       		<td><span id="total_weight_paid"></span></td>
				                       	</tr>
				                       <!--	<tr style="display:none;">
				                       		<th>Preclose</th>
				                       		<td><input type="checkbox" id="is_preclose" />  Need to close</td>
				                       	</tr>-->
				                       </table>			                        
				                     </div>
				                     <div class="col-xs-12 col-md-4 col-lg-4">
				                       <table class="table table-condensed">
				                       	<tr>
				                       		<th>Last Paid Date</th>
				                       		<td><span id="last_paid_date"></span></td>
				                       	</tr> 					                    	
				                       	<tr>
				                       		<th>PDC/ECS </th>
				                       		<td><span id="total_pdc"></span></td>
				                       	</tr> 	
				                       	<tr>
				                       		<th>Can pay?</th>
				                       		<td><span id="allow_pay"></span></td>
				                       	</tr>
				                       	   	<tr>
				                       		<th>Unpaid Dues</th>
				                       		<td><span id="unpaid_dues"></span></td>
				                       	</tr> 
				                       	<tr id="is_preclose_blk" style="display<?php echo ($pay['allow_preclose'] == 1 ? ':block':':none')?>" >
				                       	<th> Is Preclose </th>
		                    			<td><input type="checkbox" name="generic[totalunpaid]" id="is_preclose" value='1' /></td>
		                    			 <input type="hidden" class="form-control" id="preclose" />
				                       	</tr> 
				                       	<tr class="hidden_allow" style="display:none">
				                       		<th>Allowed Dues</th>
				                       		<input type='hidden' id="allowed_dues" />
				                       		<!--<td><input type='number' class='form-control input_number' id="allowed_dues" name='generic[installments]' style="width:50%;float:right;" readonly="true"/>   <input type='hidden' id="payamt" />
				                       		     <input type='hidden' id="act_allowed_dues" />
				                       		     <input type='hidden' id="act_due_type" /></td>-->
										<td>
											<?php $i=0;?>
											<button type="button" value="<?php echo $i; ?>" class="dec_due">-</button>
											<input id="sel_due" class="sel_due" name="generic[installments]"  Style="width:10%"  value="1" readonly="true"/>
											<button type="button"  value="<?php echo $i; ?>" class="incre_due">+</button>
										</td>
				                       	</tr> 
				                       		<tr class="hidden_allow" style="display:none">
				                       		<th>Due Type</th>
				                       		<td><input type='text' class='form-control' name='generic[due_type]' id="due_type" style="width:50%;float:right;" readonly="true"/></td>
				                       	</tr> 
				                       </table>			                        
				                     </div>
			                    </div>
			                </div>
			            </div>
			        </div>
			    </div>
			    <!--<div class="row">
				  <div class="col-md-3">
				 	<div class="form-group col-md-10 col-md-offset-1">
                       <label for="chargeseme_name" >  <input type="checkbox"   id="adjust_unpaid" disabled="true" value="1"/> Adjust Unpaid</label>
                    </div>
                  </div>  
                  <div class="col-md-3">
				 	<div class="form-group">
                       <label for="chargeseme_name" class="col-md-6 col-md-offset-1 ">Unpaid Dues</label>
                       <div class="col-md-4">
                       <input type="text" class="form-control input_number"  id="no_of_unpaids" disabled="true" value=""/>
                        <p class="help-block"></p>
                       </div>
                    </div>
                  </div>
			    </div>--> 
	    	<div class="row">
      			<div class="col-sm-7 col-sm-offset-3">
      			<div id="error-msg"></div>
      			  <div  id="payment_container">
      			  </div>
      			</div>
      		</div>
            <div class="row">	
	 <?php if($this->payment_model->get_gstsettings()==1){?>
             	<div class="col-sm-2">
             	<div class="form-group">
             	<label for="">GST Amount</label>
              		<div class="input-group input-group-sm">
              			<span class="input-group-addon"><?php echo $this->session->userdata('currency_symbol')?></span>
              			<input type="text" class="form-control"   id="gst_amt" readonly="true" />
              			<input type="hidden" class="form-control"   id="gst_type"  name="generic[gst_type]" />
              			<input type="hidden" class="form-control"   id="gst_percent"  name="generic[gst]" />
              		</div>
              	</div>
              	</div>
	 <?php }?>		
              	<div class="col-sm-3">
             	<div class="form-group">
             	<label for="">Amount</label>
              		<div class="input-group input-group-sm">
              			<span class="input-group-addon"><?php echo $this->session->userdata('currency_symbol')?></span>
              			<input type="text" class="form-control"   id="total_amt"  name="generic[payment_amount]" readonly="true"/>
              		</div>
              	</div>
              	</div>
				<div class="col-sm-1" style=" margin-top: 25px;">
             	<div class="form-group">
              		<div class="input-group input-group-sm">
              			<input type="button" class="form-control btn btn-primary"   id="proced"  value="Proced"/>
              		</div>
              	</div>
              	</div>
					<div class="col-sm-3">
             	<div class="form-group">
             	<label for="">Payment Amount</label>
              		<div class="input-group input-group-sm">
              			<span class="input-group-addon"><?php echo $this->session->userdata('currency_symbol')?></span>
              			<input type="text" class="form-control"   id="payment_amt" readonly="true" />
              		</div>
              	</div>
              	</div>
				<!--flx scheme-->
				<div class="col-sm-3">
             	<div class="form-group">
             	<label for="">Payment Weight</label>
              		<div class="input-group input-group-sm">
              			<span class="input-group-addon"><?php echo $this->session->userdata('currency_symbol')?></span>
              			<input type="text" class="form-control"   id="payment_weight" ame="generic[metal_weight]"readonly="true" />
              		</div>
              	</div>
              	</div>
         </div></br>
				<!--flx scheme-->
       <div class="row">	
              	<div class="col-sm-3">
              		<div class="form-group">
              			<label for="">Payment Mode</label>
              			<select class="form-control"   id="pay_mode" name="generic[payment_mode]" ></select>
              			   						<input type="hidden" class="form-control" id="paymode"  value="<?php echo set_value('generic[payment_mode]',$pay['payment_mode']); ?>" />
              		</div>
              	</div> 	
              	<div class="col-sm-3">
              		<div class="form-group">
              			<label for="">Ref No</label>
              			<input type="text" class="form-control input_number input-sm" id="payment_ref_number" name="generic[payment_ref_number]" value="<?php echo set_value('generic[payment_ref_number]',$pay['payment_ref_number']); ?>" />
              		</div>
              	</div>
              	<div class="col-sm-3">
	              	<div class="form-group">	              
	              		<label for="">Payment Status</label> <br/>
	              	  <select class="form-control" name="generic[payment_status]" id="payment_status"></select>
	              	  	<input type="hidden" class="form-control" id="pay_status"  value="<?php echo set_value('generic[payment_status]',$pay['payment_status']); ?>" />
		             </div>	
		        </div>
             </div></br> 
               <div class="row Cash-container" style="display: block;">
             <!--	<div class="col-sm-2">
              		<div class="form-group">
              			<label for="">Cheque Date</label>
              				<div class='input-group date'>
				                    <input type='text' class="form-control input-sm datemask myDatePicker" name="pdc[date_payment]"  id='pay_date' data-inputmask="'alias': 'dd/mm/yyyy'" data-mask  data-date-format="dd/mm/yyyy" value="<?php echo set_value('pdc[date_payment',$pay['pdc']['date_payment']); ?>" />
				                    <span class="input-group-addon">
				                        <span class="glyphicon glyphicon-calendar"></span>
				                    </span>
				                </div>
              		</div>
              	</div> 
              	<div class="col-sm-3">
              		<div class="form-group">
              			<label for="">Cheque No</label>
              			<input type="text" class="form-control input_number input-sm" id="cheque_no" name="pdc[cheque_no]" value="<?php echo set_value('pdc[cheque_no]',$pay['pdc']['cheque_no']); ?>" />
              		</div>
              	</div>-->
              	<div class="col-sm-2">
              		<div class="form-group">
              			<label for="">Bank</label>
              			<select class="form-control" id="payee_bank" name="pdc[payee_bank]" style="width: 100%;"></select>
              					<input type="hidden" id="id_payee_bank" value="<?php echo set_value('pdc[payee_bank]',$pay['pdc']['payee_bank']); ?>" />
              		</div>
              	</div>
              	<div class="col-sm-3">
              		<div class="form-group">
              			<label for="">Branch</label>
              			<input type="text" class="form-control input_text input-sm" id="payee_branch" name="pdc[payee_branch]" value="<?php echo set_value('pdc[payee_branch]',$pay['pdc']['payee_branch']); ?>" />
              		</div>
              	</div>  
				<div class="col-sm-2">
              		<div class="form-group">
              			<label for="">IFSC Code</label>
              			<input type="text" class="form-control" id="bank_IFSC" name="pdc[payee_ifsc]" value="<?php echo set_value('pdc[payee_ifsc]',$pay['pdc']['payee_ifsc']); ?>" />
              		</div>
              	</div>
              	<div class="col-sm-3">
              		<div class="form-group">
              			<label for="">Drawee A/c</label>
              				<select class="form-control" id="drawee_acc_no" name="pdc[id_drawee]" style="width: 100%;"></select>
              		</div>
              	</div></br>
              	   <!-- <div class="col-md-3">			
					 	<div class="form-group">
	                       <label for="">Drawee Bank</label>
	                       <input type="text" class="form-control input_number"  id="drawee_bank" readonly="true" />
	                    </div>	
                    </div>	</br>
                   <div class="col-md-3">			
					 	<div class="form-group">
	                       <label for="">Drawee Branch</label>
	                       <input type="text" class="form-control input_text"  id="drawee_bank_branch" readonly="true"  />
	                    </div>	
                    </div>
                    <div class="col-md-3">			
					 	<div class="form-group">
	                       <label for="">Drawee IFSC Code</label>
	                       <input type="text" class="form-control ucase"  id="drawee_ifsc" readonly="true"  />
	                    </div>	
                    </div>-->	
             </div></br>
				    <div class="row">
				    	<div class="col-md-12">
				    	   <div class="form-group">
                    			<label>Comments</label>
				    		    <textarea class="form-control" name="generic[remark]"><?php echo set_value('generic[remark]',$pay['remark']); ?></textarea>
				    		</div>
				    	</div>
				    </div>
				     <div class="row">
				     <div class="col-md-12">
				   <div class="box box-default"><br/>
				   <?php if($pay['useWalletForChit']==1 && $pay['allow_wallet']==1){?>
				   		<input name="generic[wallet]" type="hidden" class="wallet">
						<input type="hidden" name="generic[wallet_balance]" class="wallet_balance"/>
						<input type="hidden" name="generic[redeem_percent]" class="redeem_percent"/>
						<div class="col-md-12" style="margin-top: 40px;">					  
							<div class="col-md-6">
							<div class="eligible_walletamt" style="display:none"> 
								<input type="checkbox" name="generic[is_use_wallet]" class="ischk_wallet_pay" value="1" />
								Use Wallet <input name="generic[redeem_request]" step="1" type="number" class="redeem_request">
						     </div>
						    </div>
						</div> 
					<?php }?> 
					  <div class="col-sm-offset-4">
						<!--<input type="checkbox" name="generic[is_use_wallet]"  value='1' /> Use Wallet &nbsp; &nbsp; &nbsp; &nbsp;<br/>-->
						<!--<button type="submit" class="btn btn-primary" id="btn-payment" >Save</button> -->
						<div class="btn-group" id="btn-submit" data-toggle="buttons">
				        <label class="btn btn-primary">
				            <input type="radio" id="pay_print" name="type" value="1">Save and Print
				        </label>
				        <label class="btn btn-primary">
				            <input type="radio" id="pay_save" name="type" value="2"> Save
							  <input type="hidden" id="isOTPRegForPayment" name="isOTPRegForPayment"  value="<?php echo $pay['isOTPRegForPayment'] ?>">
				        </label>				       
					 </div>
						<button type="button" class="btn btn-default btn-cancel">Cancel</button>
					  </div> <br/>
					  </div> 
					</div>
				  </div> 
				  </form>
            </div><!-- /.box-body -->
				<div class="overlay" style="display:none">
					<i class="fa fa-refresh fa-spin"></i>
				</div>
            <div class="box-footer">
            </div><!-- /.box-footer-->
               <div class="overlay" style="display:none">
				  <i class="fa fa-refresh fa-spin"></i>
				</div>
          </div><!-- /.box -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
	   <div id="otp_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header ">
      <button type="button" id="close_model" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h3 id="myModalLabel">Mobile Number Verification</h3>
      </div>
        <div class="modal-body">
          <p>Please enter the code sent to your mobile number</p>
          <div>
          <label style="display:inline; margin:5px" for="otp">Enter Code:</label>
          <input  style="display:inline; width:30%; margin:5px" type="text" id="otp" name="otp" value="" class="form-control" required/>
           <input style="margin-left:1%" type="submit" value="Verify" id="verify_otp" style="background-color:#0079C0"  class="button btn btn-primary" />
          <span id="OTPloader"><img src="<?php echo base_url()?>assets/img/loader.gif" ></span>
        </div>
        <div class="modal-footer">
		       <input type="submit" id="resendotp" value="Resend OTP" class="resendotp">  </input>
        </div>
        </div>
      </div>
    </div>
  </div>
   <!-- <script type="text/javascript">
     var customerList  = new Array();
     var customerListArr = new Array();
     customerListArr = JSON.parse('<?php echo json_encode($cus); ?>');
  </script>-->
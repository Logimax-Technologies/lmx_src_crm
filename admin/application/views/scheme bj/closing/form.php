 <!-- Content Wrapper. Contains page content -->
 <script>
 	function DisableBackButton() {
		window.history.forward()
	}
	DisableBackButton();
	window.onload = DisableBackButton;
	window.onpageshow = function(evt) { if (evt.persisted) DisableBackButton() }
	window.onunload = function() { void (0) }
 </script>
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Scheme Account
            <small>Closing Scheme account</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Manage Savings Scheme</a></li>
            <li class="active">Close Account</li>
          </ol>
        </section>
        <!-- Main content -->
        <section class="content">
          <!-- Default box -->
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Account Closing Form</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
                <?php echo form_open(( $account['id_scheme_account']!=NULL && $account['id_scheme_account']>0 ?'account/close/update/'.$account['id_scheme_account']:'account/close/save')); ?>
              <div class="col-md-10 col-md-offset-1">              	
              		<div class="row">
	              		<div class="col-sm-6">
	              			 <div class="form-group">
	              					<label for="" >Scheme Account Name</label>
	              					<input type="text" class="form-control" name="account[account_name]" id="account_name" value="<?php echo set_value('account[account_name]',$account['account_name']); ?>" readonly="true"/>
	              			<!--		<select class="form-control" name="scheme[id_scheme_account]" id="name"></select>-->
	              			 </div>
	              		</div>
	              		<div class="col-sm-6">
	              		<div class="form-group">
	              					<label for="">Closing Date</label>
	              					<div class='input-group date' >
					                    <input type='text' id='close_date' name="account[close_date]" value="<?php echo set_value('account[close_date]',$account['close_date']); ?>" class="form-control" />
					                    <span class="input-group-addon">
					                        <span class="glyphicon glyphicon-calendar"></span>
					                    </span>
					                </div>
	              				</div>		
	              		</div>	   
	              	</div>
              		<div class="row">
              			<div class="col-sm-12">
              				<div class="box box-default">
							  <div class="box-header with-border">
							    <h3 class="box-title">Account Details</h3>
							    <div class="box-tools pull-right">
							      <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							    </div><!-- /.box-tools -->
							  </div><!-- /.box-header -->
							  <div class="box-body">
								<div class="row">
									<div class="col-sm-4">
				              			 <div class="form-group">
				              					<label for="" >Customer Name</label>
				              					<label class="form-control input-sm"><?php echo set_value('account[name]',$account['name']); ?></label>
				              					<input type="hidden" id="firstname" name="account[firstname]" value = "<?php echo set_value('account[name]',$account['name']); ?>" />
				              			 </div> 
				              			 <div class="form-group">
				              					<label for="" >Scheme Name</label>
				              					<label class="form-control input-sm"><?php echo set_value('account[scheme_name]',$account['scheme_name']); ?></label>
				              			 </div>	
				              			  <div class="form-group">
				              					<label for="" >Mobile</label>
												<div class="input-group">
							              			<span class="input-group-addon input-sm"><?php echo $this->session->userdata('mob_code')?></span>
							              	     	<label class="form-control input-sm"><?php echo set_value('account[mobile]',$account['mobile']); ?></label>
							              	     	<input type="hidden" id="mobile" name="account[mobile]" value = "<?php echo set_value('account[mobile]',$account['mobile']); ?>" />
												</div>
										 </div> 
				              			  <div class="form-group">
				              					<label for="" >Nominee</label>
				              					<label class="form-control input-sm"><?php echo set_value('account[nominee_name]',$account['nominee_name']); ?></label>
				              			 </div>
				              		 </div>
	              					 <div class="col-sm-4">	 
	              					   
			              			 <div class="col-sm-6">			              			 
				              			 <div class="form-group"> 
				              			 	<label for="" >Code</label>
				              					<label class="form-control input-sm"><?php echo set_value('account[code]',$account['code']); ?></label>
				              			</div>
				              		</div>
				              		<div class="col-sm-6">			              			 
				              			 <div class="form-group"> 
				              					<label for="" >Scheme A/c No</label>
				              					<label class="form-control input-sm"><?php echo set_value('account[scheme_acc_number]',$account['scheme_acc_number']); ?></label>
												<input type="hidden" name="account[scheme_acc_number]" value = "<?php echo set_value('account[scheme_acc_number]',$account['scheme_acc_number']); ?>" />
												<input type="hidden" name="account[id_scheme_account]" value = "<?php echo set_value('account[id_scheme_account]',$account['id_scheme_account']); ?>" />
												<input type="hidden" name="account[employee_closed]" value = "<?php echo set_value('account[employee_closed]',$account['employee_closed']); ?>" /> 
				              			 </div>
			              			 </div>
				              		 <div class="col-sm-6">	 
				              			 <div class="form-group">
				              					<label for="" >Scheme Type</label>
				              					<label class="form-control input-sm"><?php echo set_value('account[scheme_type]',$account['scheme_type']); ?></label>
				              					<input type="hidden" id="sch_typ" name="account[sch_typ]" value = "<?php echo set_value('account[sch_typ]',$account['sch_typ']); ?>" />
				              			 </div>
				              		  </div>	 
				              		  <div class="col-sm-6">	 
				              			 <div class="form-group">
				              					<label for="" >Amount</label>
				              					<div class="input-group ">
              				<span class="input-group-addon input-sm"><?php echo $this->session->userdata('currency_symbol')?></span>
				              					<label class="form-control input-sm"><?php echo ($account['firstPayment_amt']!='' || $account['firstPayment_amt']!=NULL)? set_value('account[firstPayment_amt]',$account['firstPayment_amt']) :set_value('account[amount]',$account['amount']); ?></label>
				              			 </div>
				              			 </div>
				              		  </div>
				              		   <div class="col-sm-6">
	              						<div class="form-group">
				              					<label for="" >Start Date</label>
				              					<label class="form-control input-sm"><?php echo set_value('account[start_date]',$account['start_date']); ?></label>
				              			 </div>
				              		   </div>
				              		   <div class="col-sm-6">
	              						<div class="form-group">
				              					<label for="" class="error">Maturity Date</label>
				              					<label class="form-control input-sm"><?php echo set_value('account[maturity_date]',$account['maturity_date']); ?></label>
				              			 </div>
				              		   </div>
				              			 <div class="col-sm-6">
					              			 <div class="form-group">
					              					<label for="" >Installments</label>
					              					<label class="form-control input-sm"><?php echo set_value('account[total_installments]',$account['total_installments']); ?></label>
					              			 </div>
				              			 </div> 
				              			 <div class="col-sm-6">
					              			 <div class="form-group">
					              					<label for="" >Paid</label>
					              					<label class="form-control input-sm"><?php echo set_value('account[paid_installments]',$account['paid_installments']); ?></label>
					              			 </div>
				              			 </div> 
				              			 <div class="col-sm-6">
					              			 <div class="form-group">
					              					<label for="" >Pending</label>
					              					<label class="form-control input-sm"><?php echo set_value('account[pending_installments]',$account['pending_installments']); ?></label>
					              			 </div>
				              			 </div>
				              			 <div class="col-sm-6">
					              			 <div class="form-group">
					              					<label for="" >Unapproved</label>
					              					<label class="form-control input-sm"><?php echo set_value('account[unapproved_payment]',$account['unapproved_payment']); ?></label>
					              			 </div>
				              			 </div>
	              					 </div>
				              		<div class="col-sm-4">	 
				              		 <div class='form-group'>
								      		<img src="<?php echo base_url();?>assets/img/customer/<?php echo $account['cus_img']; ?>" class="img-thumbnail" alt="<?php echo ($account['name']===NULL?'Customer':$account['name']) ?>" width="304" height="236"> 
	              					</div>
								</div>
								<div class="col-sm-4">	 
								<button  type="button" class="btn btn-primary"> <a href="<?php echo base_url('index.php/account/close/scheme_history/'.$account['id_scheme_account']); ?>"><font color="white"> Report</font></a></button>
							  </div>
							  </div><!-- /.box-body -->
							</div><!-- /.box -->
              			</div>
              		</div>
              </div>	
               <!-- For OTP Scheme. below condition baseds on the showed scheme closing HH-->  
             <!--<?php  if($account['pending_installments']>0 && $account['one_time_premium']==0) { ?> 
              <div class="row">
                <div class="col-sm-12">
                	<div class="callout callout-danger lead">
					    <h4>Warning!</h4>
					    <p style="font-size: 17px;">
					     <?php echo $account['name']; ?> has paid only <?php echo $account['paid_installments']; ?> installments.</p>
					    <p style="font-size: 17px;">  <?php echo ($account['allow_preclose']==1?'Pre-close option is available for this scheme (Allowed Installments:'.$account['preclose_months'].', Benefits:'.($account['preclose_benefits']==1?'Available':'Not available').')':'Pre-close option is not available for this scheme'); ?>.</p>
					    <p style="font-size: 17px;"> Please provide the reason for closing in the comments.</p>
					    <p style="font-size: 15px;"><strong>Note :</strong> No benefits will be provided, if the scheme account closed without completing the duration of <?php echo $account['total_installments']; ?> installments.<br/>
					    </p>
                  </div>
                </div>
              </div>
            <?php }?> -->
           <?php  if(($account['pending_installments'] == $account['totalunpaid']) && $account['one_time_premium']==1 && $account['paid_installments']==1) { ?> 
                <div class="row">
                    <div class="callout callout-success lead">
                        <p style="font-size: 17px;">
				<p style="font-size: 15px;"><strong>Proceed! </strong>The installment month is  over. Now You can close it...<br/>
				</p>
                </div>
                </div>
                 <?php } else if($account['one_time_premium']==1 && $account['paid_installments']==1){ ?>
				<div class="row">
                <div class="col-sm-10">
				<div class="callout callout-danger lead">
				<p style="font-size: 17px;">
				<p style="font-size: 15px;"><strong>Warning! </strong>From your first paid month,The installment month is not over yet. You must close it at the end of the installments month..<br/>
				</p>
			 </div>
				  </div>
				  </div>
			  <?php } else if(($account['pending_installments']>0 || $account['reached_maturity_date'] == 0 ) && $account['one_time_premium']==0 && ($account['apply_debit_on_preclose'] == 1 || $account['apply_benefit_by_chart'] == 1)) {?> 
			<div class="row">
				<div class="col-sm-10">
					<div class="callout callout-danger lead"> 
					<strong>Warning! </strong>
						<p style="font-size: 17px;"> <?php echo $account['name']; ?> has paid only <?php echo $account['paid_installments']; ?> installments.</p>
						<?php if($account['maturity_date'] != NULL){
							echo '<p style="font-size: 15px;">Scheme maturity date is '.$account['maturity_date'].'</p>'.($account['apply_debit_on_preclose'] == 1 ? '<p style="font-size: 15px;">No benefits will be provided, if the scheme account closed before maturity date.</p>' : ''); 
						} 
						echo ($account['apply_debit_on_preclose'] == 1 && $account['pending_installments']>0? '<p style="font-size: 15px;">No benefits will be provided, if the scheme account closed without completing the duration of '.$account['total_installments'].' installments. </p> '.($account['debit'] > 0 ? '<p style="font-size: 15px;"> Rs.'.$account['debit'].' will be debited from closing balance as per cancellation chart.</p>' : '') : ($account['apply_benefit_by_chart'] == 1 ? '<p style="font-size: 15px;">Benefits will be applied as per chart.</p>' : '')) .'</p>'?>
					</div>
				</div>
			</div>
              <?php } else if($account['pending_installments']>0 && $account['one_time_premium']==0 && $account['maturity_date']) {?>
              <div class="row">
				<div class="col-sm-10">
					<div class="callout callout-danger lead">
						<p style="font-size: 17px;">
						<p style="font-size: 15px;"><strong>Warning! </strong>You have not yet reached the maturity date <?php echo $account['maturity_date']; ?>. You can close it once you reach the maturity date.<br/>
						<p style="font-size: 17px;"> <?php echo $account['name']; ?> has paid only <?php echo $account['paid_installments']; ?> installments.</p>
						</p>
					</div>
		  		</div>
			  </div>
              <?php } else if($account['pending_installments']>0 && $account['one_time_premium']==0) { ?> 
              <div class="row">
                <div class="col-sm-12">
                	<div class="callout callout-danger lead">
					    <h4>Warning!</h4>
					    <p style="font-size: 17px;">
					     <?php echo $account['name']; ?> has paid only <?php echo $account['paid_installments']; ?> installments.</p>
					    <p style="font-size: 17px;">  <?php echo ($account['allow_preclose']==1?'Pre-close option is available for this scheme (Allowed Installments:'.$account['preclose_months'].', Benefits:'.($account['preclose_benefits']==1?'Available':'Not available').')':'Pre-close option is not available for this scheme.'); ?>.</p>
					    <p style="font-size: 17px;"> Please provide the reason for closing in the comments.</p>
					    <p style="font-size: 15px;"><strong>Note :</strong> No benefits will be provided, if the scheme account closed without completing the duration of <?php echo $account['total_installments']; ?> installments.<br/>
					    </p>
                  </div>
                </div>
              </div>
              <?php } ?>
              <!-- For OTP Scheme. Above conditions based on the showed scheme closing HH-->  
              <div class="row">
                <div class="col-sm-4">
              		<div class="form-group">
              			<label for=""><a  data-toggle="tooltip" title="Actual amount paid by the customer" style="color:#FF0000;">Total amount paid</a> </label>
              			<div class="input-group ">
              				<span class="input-group-addon input-sm"><?php echo $this->session->userdata('currency_symbol')?></span>
              		<input type="text" id="closing_amount" name="account[closing_amount]" class="form-control" readonly="true" value="<?php echo set_value('account[closing_amount]',$account['closing_amount']); ?>"/>
						<input type="hidden" id="closing_amt" name="account[closing_amount]" class="form-control" readonly="true" value="<?php echo set_value('account[closing_amount]',$account['closing_amount']); ?>"/>  <!--h-->
              		</div>
              		</div>
              	</div>
              	<div class="col-sm-4">
              	<div class="form-group">
              		<label for="">Benefits</label>
              		<div class="input-group">
					<?php if($account['scheme_type']=='Amount' || $account['scheme_type']=='FLXEBLE_AMOUNT') { ?>
              				<span class="input-group-addon input-sm"><?php echo $this->session->userdata('currency_symbol')?></span>
								  	<?php  } ?>
	              	     	<input type="text" placeholder="Enter a valid amount" id="benefits" name="account[interest]" class="form-control" required readonly="true" value="<?php echo ($account['total_installments'] == $account['paid_installments'])? set_value('account[interest]',$account['interest']):''; ?>"/>
						<?php if($account['scheme_type']=="Weight" || $account['scheme_type']=="Amt to Wgt") {?>
              			    <span class="input-group-addon">gm</span>
              			    	<?php  } ?>
					</div>
	             </div>       
	            </div> 
              	<div class="col-sm-4">
              		<div class="form-group">
              			<label for="">Detections/Tax</label>
              			<div class="input-group">
	              			<span class="input-group-addon input-sm"><?php echo $this->session->userdata('currency_symbol')?></span>
	              	     	<input type="text" placeholder="Enter a valid amount or 0" id="detections" name="account[tax]" class="form-control" required readonly="" value="<?php echo set_value('account[tax]',$account['tax']); ?>"/>
					</div>
	             	</div>       
	          	</div>       
            </div>
         	<div class="row">
	         	<div class="col-sm-4">
	              	<div class="form-group">
	              		<label for="">Bank Charges</label>
	              		<div class="input-group">
	              			<span class="input-group-addon input-sm"><?php echo $this->session->userdata('currency_symbol')?></span>
	              	     	<input type="text" id="bank_chgs" name="account[bank_chgs]" class="form-control" readonly="true" value="<?php echo set_value('account[bank_chgs]',$account['bank_chgs']); ?>"/>
						</div>       
		            </div>       
		        </div>
         		<div class="col-sm-4">
              		<div class="form-group">
              			<label for="" ><a data-toggle="tooltip" title="Bank charges,detections,tax and service charges if applicable" style="color:black;">Additional Detection/Tax</a></label>
              		  	<div class="input-group">
              				<span class="input-group-addon input-sm"><?php echo $this->session->userdata('currency_symbol')?></span>
              	     		<input type="text" id="add_charges" name="account[add_charges]" class="form-control" value="<?php echo set_value('account[add_charges]',$account['add_charges']); ?>"/>
	             		</div>       
	             	</div> 
              	</div>
              	<div class="col-sm-4">
              		<div class="form-group">
              			<label for="">Additional Benefits</label> 
	             		<!--<div class="input-group">
							<?php if($account['scheme_type']=='Amount') { ?>
              				<span class="input-group-addon input-sm"><?php echo $this->session->userdata('currency_symbol')?></span>
								  	<?php  } ?>
	              	     	<input type="text" id="add_benefits" name="account[add_benefits]" class="form-control" value="<?php echo set_value('account[add_benefits]',$account['add_benefits']); ?>"/>
						<?php if($account['scheme_type']=="Weight" || $account['scheme_type']=="Amt to Wgt") {?>
              			    <span class="input-group-addon">gm</span>
		              	  	<?php  } ?>
						</div>   -->   
						<?php if($account['scheme_type']=="Weight" || $account['scheme_type']=="Amt to Wgt"){ ?>
						<label> <input type="radio"  name="account[add_benefixed]" id="fixed_wgtschamt" class="minimal" value="" /> Amount</label> &nbsp;&nbsp;&nbsp;&nbsp;
						<label> <input type="radio" name="account[add_benefixed]" id="fixed_wgtschwgt" class="minimal" value="" /> Weight</label> 
						<?php }?>
						<?php if($account['scheme_type']=='Amount' || ($account['scheme_type']=="FLXEBLE_AMOUNT" )) { ?>
							<div class="input-group">
								<span class="input-group-addon input-sm"><?php echo $this->session->userdata('currency_symbol')?></span>
								<input type="text" id="add_benefits" name="account[add_benefits]" class="form-control" value="<?php echo set_value('account[add_benefits]',$account['add_benefits']); ?>"/>
							</div>
							<?php } 
							else if($account['scheme_type']=="Weight" || $account['scheme_type']=="Amt to Wgt"){ ?>
						<div class="input-group">
								<span id="curren_symbol" class="input-group-addon input-sm"><?php echo $this->session->userdata('currency_symbol')?></span>
								<input type="text" id="add_benefits"  class="form-control" />
								<input type="hidden" id="add_benefit" name="account[add_benefits]" class="form-control" value="<?php echo set_value('account[add_benefits]',$account['add_benefits']); ?>"/>
								<input type="hidden" id="metal_rate" name="account[metal_rate]" class="form-control" value="<?php echo set_value('account[metal_rate]',$account['metal_rate']); ?>"/>
              			       <span  id="wgt_symbol" class="input-group-addon">gm</span>
					     </div>
		              	  	<?php  } ?>
	             	</div>       
              	</div>
              <div class="col-sm-4">
              		<div class="form-group">
              			<label for="" style="color:#FF0000;"><?php echo ($account['scheme_type']=='Amount' ||($account['scheme_type']=='FLXEBLE_AMOUNT'&& $account['wgt_convert']==1)?'<a  data-toggle="tooltip" title="Amount after interest & tax calculation" style="color:#FF0000;">Closing Balance</a>':'Closing Balance')?> </label> 
              			 <div class="input-group">
              		  <?php if($account['scheme_type']=='Amount' || (($account['scheme_type']=="FLXEBLE_AMOUNT" && $account['flexible_sch_type']==1 || $account['flexible_sch_type']!=2) && $account['wgt_convert']==0)) { ?>
              				<span class="input-group-addon input-sm"><?php echo $this->session->userdata('currency_symbol')?></span>
								  	<?php  } ?>
								  
              			<input type="text" id="closing_balance" name="account[closing_balance]" class="form-control" readonly="true" value="<?php echo set_value('account[closing_balance]',$account['closing_balance']); ?>"/>
              			<input type="hidden" id="closing_weight" value="<?php echo set_value('account[closing_balance]',$account['closing_balance']); ?>">
						<?php if($account['scheme_type']=="Weight" || $account['scheme_type']=="Amt to Wgt" ||($account['scheme_type']=="FLXEBLE_AMOUNT" && ($account['flexible_sch_type']==3 || $account['flexible_sch_type']==2) && $account['wgt_convert']==0)) {?>
              			    <span class="input-group-addon">gm</span> 
              			    	<?php  } ?>
              			</div>
              		</div>
              	</div>	
				<?php if($account['sch_typ']==1){?>
				<div class="col-sm-2">
              		<div class="form-group">
						<label style="color:#FF0000;">Closing Amount</label>
              			<input type="text" id="closing_amt" name="account[closing_amount]" class="form-control" readonly="true" value="<?php echo set_value('account[closing_amount]',$account['closing_amount']); ?>"/>	
              		</div>
              	</div>
				<?php }?>
              	<!--<div class="col-sm-2">
              		<div class="form-group">
              		<br />
              			<button  type="button" class="btn btn-warning" id="calc_blc">Calculate Balance</button> 	
              		</div>
              	</div>-->
				<div class="col-sm-2">
              		<div class="form-group">
              		<br />
              			<button  type="button" class="btn btn-clear" id="clear_blc">Clear</button> 	
              		</div>
              	</div>
              </div>
              <div class="row">
              <div class="col-sm-4">
              	<div class="form-group">
              		<label for="">Closed by <span class="error">*<span></label> <br/>
                       	<label class="radio-inline" >
	                      <input type="radio" name="account[closed_by]" id="" class="minimal" value="0" <?php if($account['closed_by']==0){?>checked <?php } ?> /> Self
	                    </label>	
	                    <label class="radio-inline">
	                      <input type="radio" name="account[closed_by]" id="" class="minimal" value="1" <?php if($account['closed_by']==1){?>checked <?php } ?> /> Nominee
	                    </label>
	                    <input type="hidden" name="account[closedBy]" id="closed_by" value = "" /> 
	             </div>       
              	</div>
              	<div class="col-sm-4">
              		<div class="form-group">
              			<label for="">Nominee Name</label>
              			<input type="text" id="nominee_name" name="account[nominee_name]" disabled="true" class="form-control" value="<?php echo set_value('account[nominee_name]',$account['nominee_name']); ?>"/>
              		</div>
              	</div>
              	<div class="col-sm-4">
              		<div class="form-group">
              			<label for="">Nominee Mobile</label>
              			<div class="input-group">
	              			<span class="input-group-addon input-sm"><?php echo $this->session->userdata('mob_code')?></span>
	              	     	<input type="number" id="nominee_mobile" name="account[nominee_mobile]" disabled="true" class="form-control" placeholder="Enter a valid mobile no" value="<?php echo set_value('account[nominee_mobile]',$account['nominee_mobile']); ?>"/>
						</div> 
              		</div>
              	</div>
              </div>	
              <br />
			<div class="row" >
			    	<div class="col-md-2">
			    		<div class='form-group'>
			                <label for="">OTP Verification:</label>
			            </div>
			    	</div>
			    	<div class="col-md-4">
			    		<div class='form-group'>
			                <input type="text" id="otp" name="account[otp]" class="form-control" required />
			                <input type="hidden" id="otp_sent" name="account[sent_otp]" value=""/>
					<input type="hidden" id="enable_closing_otp" value="<?php echo set_value('account[enable_closing_otp]',$account['enable_closing_otp']); ?>"/> 
			            </div>
			    	</div>
			    	<div class="col-md-2">
			    		<div class='form-group'>
			                <input type="button" id="send_otp" class="btn btn-warning" value="Send OTP"  disabled="true"/> 
							<input type="hidden" id="email" value="<?php echo set_value('account[email]',$account['email']); ?>"/> 
							<input type="hidden" id="id_customer" value="<?php echo set_value('account[id_customer]',$account['id_customer']); ?>"/> 
							<input type="hidden" id="enable_closing_otp" value="<?php echo set_value('account[enable_closing_otp]',$account['enable_closing_otp']); ?>"/> 
			            </div>
			    	</div>
			    	<div class="col-md-2">
			    		<div class='form-group'>
			               <button type="button" id="verify_otp" class="btn btn-success"  disabled="true">Verify OTP</button>
			            </div>
			    	</div>
			</div>
			<div class="row">
			    	<div class="col-md-12">
			    		<div class='form-group'>
			                <label id="otp_status"></label>
			        	</div>
			    	</div>
			</div>
			<div class="row">
			    	<div class="col-md-12">
			    		<div class='form-group'>
			                <label for="user_lastname">Comments</label>
			               <textarea class="form-control" id="remark_close" name="account[remark_close]" <?php if($account['pending_installments']>0) { ?> required="true" <?php } ?>><?php echo set_value('account[remark_close]',$account['remark_close']); ?></textarea>
			        	</div>
			    	</div>
			</div>
			    <div class="row">
	      			<div class="col-sm-6 col-sm-offset-3">
	      			<div id="error-msg"></div>
	      			</div>
      			</div>
			     <div class="row">
					<div class="box-footer clearfix">
		                  <button class="btn btn-sm btn-app pull-left btn-cancel" type="button"><i class="fa fa-remove"></i> Cancel</button>
						<span id="close_actionBtns" style="display: none;">
		                  <button type="submit" class="btn btn-sm btn-primary btn-app pull-right" name="account[saveNprint]" value="1" id="close_save_print" ><i class="fa fa-print"></i> Save and print</button>
		                  <button type="submit" class="btn btn-sm  btn-primary btn-app pull-right"  id="close_save" ><i class="fa fa-save"></i> Save</button>
		                  </span>
	              	  </div>
					  <br/>
					</div> <div class="overlay" style="display:none">
				  <i class="fa fa-refresh fa-spin"></i>
				</div>
				  </div> 
				 </div> 
				  </form>
            </div><!-- /.box-body -->
          </div><!-- /.box -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
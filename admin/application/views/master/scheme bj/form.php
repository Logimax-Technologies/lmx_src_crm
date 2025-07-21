  <style type="text/css">
.ui-autocomplete { 
max-height: 200px; 
overflow-y: scroll; 
overflow-x: hidden;
}

#myCheck:checked + #area {
  display: block !important;
}

.col-sm-3 {
    width: 33.333333%;
}

</style>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Scheme 
        <small>Create new scheme</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Masters</a></li>
        <li class="active">Scheme</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Scheme Master</h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <div class="">
			<?php echo form_open_multipart(( $sch['id_scheme']!=NULL && $sch['id_scheme']>0 ?'scheme/update/'.$sch['id_scheme']:'scheme/save'),array('id'=>'scheme_create')) ?>
		<div class="col-md-11 col-md-offset-1">
			<div class="row">
		    	<div class="col-md-2">
			    	<div <?php if($discount['free_first_payment']==1) { ?> class="form-group" <?php }else{ ?> class="form-group" <?php } ?>>
			    		<label>Visible to Customer</label>
			    		<input type="checkbox" id="visible" data-on-text="YES"
   data-off-text="NO" name="sch[visible]" value="1" <?php if($sch['visible']==1) { ?> checked="true" <?php } ?>/>
			    	</div>
			    </div>
			    <div class="col-md-2">
			    	<div <?php if($discount['free_first_payment']==1) { ?> class="form-group" <?php }else{ ?> class="form-group" <?php } ?>>
			    		<label>Active to Customer</label>
			    		<input type="checkbox" id="active" data-on-text="YES"
   data-off-text="NO" name="sch[active]" value="1" <?php if($sch['active']==1) { ?> checked="true" <?php } ?>/>
			    	</div>
			    </div>
		    	<div class="col-md-3"> 
				   	<label>Stop Payment for Defaulter</label>
				   	<input type="checkbox"  id="block_payment" name="sch[disable_sch_payment]" value="1" <?php if($sch['disable_sch_payment']==1) { ?> checked="true" <?php } ?>/>
				   	<select id="select_intall_to_stop" class="form-control" data-placeholder="Select Your installments" ></select>
				   	<input type="hidden" class="select_intall_to_stop" name='sch[stop_payment_installment]' />  
		    	</div>
		    	
		    	 <div class="col-md-2">
			    	
			    		<label>Has Gift</label>
			    		<input type="checkbox" id="has_gift" data-on-text="YES"
   data-off-text="NO" name="sch[has_gift]" value="1" <?php if($sch['has_gift']==1) { ?> checked="true" <?php } ?>/>
			    
			    </div>
			        <!-- auto debit plan type  select option HH-->
			    	<div class="col-md-3"> 
				   	<label>Select Auto Debit Plan Type</label>
				   	<div class="form-group" >
				   	<select id="2" name="sch[auto_debit_plan_type]" class="form-control"> 
                    <option value="0" <?php if($sch['auto_debit_plan_type'] == 0){ ?> selected="true" <?php } ?>>Not Applicable</option>
                    <option value="1" <?php if($sch['auto_debit_plan_type'] == 1){ ?> selected="true" <?php } ?>>Periodic</option>
                    <option value="2" <?php if($sch['auto_debit_plan_type'] == 2){ ?> selected="true" <?php } ?>>OnDemand</option>
                                                    
                    </select>
                    </div>
		    	</div>
			    
			 
		    
		    </div>
			<p class="help-block"></p>
			<legend> <a  data-toggle="tooltip" title="Enter Your Scheme Details">Scheme Detail</a></legend> 
			<div class="row">
				<div class="col-sm-8"> 
					<div class="row">
					   <div class="col-sm-6">
					 	<div class="form-group">
		                   <label>Scheme Name</label>
						    	<input type="text" class="form-control" id="name" name="sch[scheme_name]" value="<?php echo set_value('sch[scheme_name]',$sch['scheme_name']); ?>" placeholder="Your Scheme name" required="true"> 
							<p class="help-block"></p>
		                </div> 	
		               </div> 
		                <div class="col-sm-6">
						 	<div class="form-group">
		                       <label for="classi" >Classification</label>
		                       <input type="hidden" id="classify_val" name="id_classification" value="<?php echo set_value('sch[id_classification]',$sch['id_classification']); ?>"  />
		                       	 <select id="classify" name="sch[id_classification]" class="form-control" required="true" ></select>
		                  		 <p class="help-block"></p>                       	
		                    </div>
		                </div>   
					   <div class="col-sm-6">
					 	<div class="form-group">
		                   <label for="scheme_code" class="">Scheme Code</label>
						    <input type="text" class="form-control" id="code" name="sch[code]" value="<?php echo set_value('sch[code]',$sch['code']); ?>" placeholder="Scheme code"> 
							<p class="help-block"></p>
		                </div> 	
		               </div>
		               <div class="col-sm-6">
					 	<div class="form-group">
		                   <label for="scheme_code" class="">Sync Scheme Code *</label>
						    <input type="text" class="form-control" required name="sch[sync_scheme_code]" value="<?php echo set_value('sch[sync_scheme_code]',$sch['sync_scheme_code']); ?>" placeholder="Sync Scheme code"> 
							<p class="help-block"></p>
		                </div> 	
		               </div>
		                  <div class="col-sm-6">
						 	<div class="form-group">
		                       <label for="metal" >Commodity </label>
		                       <input type="hidden" id="metal_val" name="id_metal" value="<?php echo set_value('sch[id_metal]',$sch['id_metal']); ?>"  />
		                       	 <select id="metal" name="sch[id_metal]" class="form-control" required="true" ></select>
		                  		 <p class="help-block"></p>                       	
		                    </div>
		                </div>    
		                <?php if($gst['sch_limit']==1){?>
						<div class="col-sm-6">
						 	<div class="form-group">
			                   <label>Scheme limit value</label>
							   	 <input type="text" class="form-control input_number" required="true" id="sch_limit_value" name="sch[sch_limit_value]" value="<?php echo set_value('sch[sch_limit_value]',$sch['sch_limit_value']); ?>" placeholder="Scheme limit" required="true"/>
								<p class="help-block"></p>
			                </div> 	
		                </div>
						<?php }?>
			    	<?php if($this->session->userdata('branch_settings')==1 && $sch['branchwise_scheme']==1){
						if($sch['id_scheme']==NULL){ ?>
							<div class="col-sm-6">
								<div class="form-group"> 
										 <label for="Branch" class=""> <a  data-toggle="tooltip" title="Select scheme applicable branches"> Branch</a></label>
		                            <select class="form-control select2 cls" id='branches' name='branch_data[id_branch]"' multiple="multiple" data-placeholder="Select Your Baranch" 
				                       style="width: 100%;">
				                </select>
				                <input type="hidden" id="id_branch"class="id_branch" name='branch_data[id_branch]'  value=""  />
							 </div>
					   </div>
						<?php  }else{
							?>
							<div class="col-sm-6">
								<div class="form-group"> 
										 <label for="Branch" class=""> <a  data-toggle="tooltip" title="Select scheme applicable branches"> Branch</a></label>
								 <select class="form-control select2 cls" id='branches' name='branch_data[id_branch]"' multiple="multiple" data-placeholder="Select Your Baranch" 
				                       style="width: 100%;">
				                </select>
								<div id="sel_br" data-sel_br='<?php echo $branch_data;?>'></div> 
								<input type="hidden" id="id_branch"class="id_branch" name='branch_data[id_branch]'/>
							 </div>
					   </div>
						<?php  } }?>
					   <div class="col-sm-6">
					    <label>Scheme Type</label>
					   	<div class="row">
					   	   <div class="col-md-12">	
							   <label>
								   <input type="radio" id="opt_amount"  name="sch[scheme_type]" value="0" class="minimal" <?php if($sch['scheme_type']==0){ ?> checked <?php } ?>/>  Amount</label>
							</div>
						   <div class="col-md-12">	
								<label>									  		
								  <input type="radio" id="opt_weight"  name="sch[scheme_type]" value="1" class="minimal" <?php if($sch['scheme_type']==1){ ?> checked <?php } ?>/> Weight</label>
						   </div>		
						   <div class="col-md-12">	
								<label>									  		
								  <input type="radio" id="opt_amtToWgt"  name="sch[scheme_type]" value="2" class="minimal" <?php if($sch['scheme_type']==2){ ?> checked <?php } ?>/> Amount to weight</label>
						   </div>	
							<div class="col-md-12">	
								<label>									  		
								  <input type="radio" id="opt_flexible amount"  name="sch[scheme_type]" value="3" class="minimal" <?php if($sch['scheme_type']==3){ ?> checked <?php } ?>/> Flexible</label>
						   </div>								   
						   <p class="help-block"></p>                       	
						 </div>
					   </div>
					   <div class="col-md-3">
					 	<div class="form-group">
		                   <label for="scheme_code" class="">Amount</label>
		                   <div class="input-group ">
		          				<span class="input-group-addon input-sm"><?php echo $this->session->userdata('currency_symbol')?></span>
						    <input type="text" class="form-control input_currency" id="amount" name="sch[amount]" value="<?php echo set_value('sch[amount]',$sch['amount']); ?>" <?php if($sch['scheme_type']==0){ ?>
							placeholder="Scheme amount" required="true" <?php } ?> disabled="ture" required="false"/>
							<p class="help-block"></p>
		                </div> 	
		                </div> 	
		               </div>   
					   <div class="col-md-3">
					 	<div class="form-group">
		                   <label>Installments</label>
						   	 <input type="text" class="form-control input_number" required="true" id="total_installments" name="sch[total_installments]" value="<?php echo set_value('sch[total_installments]',$sch['total_installments']); ?>" placeholder="Total installments" required="true"/> 
							<p class="help-block"></p>
		                </div> 	
		               </div>
		               <div class="col-md-3">
					 	<div class="form-group">
		                   <label>Maturity Installment</label>
						   	 <input type="text" class="form-control input_number" required="true" id="maturity_installment" name="sch[maturity_installment]" value="<?php echo set_value('sch[maturity_installment]',$sch['maturity_installment']); ?>" placeholder="Maturity installment" required="true"/> 
							<p class="help-block"></p>
		                </div> 	
		               </div>
					   	<div class="col-md-3">
						 	<div class="form-group">
			                   <label>Maturity Type</label>
							   	 <select id="maturity_type" class="form-control" data-placeholder="Type" name='sch[maturity_type]'>
							   	     <option value=1 <?php if($sch['maturity_type']==1){  ?> selected <?php }?>>Flexible [Can pay installments and close]</option>
							   	     <option value=2 <?php if($sch['maturity_type']==2){  ?> selected <?php }?>>Fixed</option>
							   	     <option value=3 <?php if($sch['maturity_type']==3){  ?> selected <?php }?>>Fixed Flexible [Increase maturity if has Default]</option>
							   	 </select> 
							   	 <!--<input type="hidden" id="maturity_type" name="sch[maturity_type]" value="<?php echo set_value('sch[maturity_type]',$sch['maturity_type']); ?>"  />-->
								<p class="help-block"></p>
			                </div> 	
		               </div>
					   
		               
		               	<div class="col-md-3">
						 	<div class="form-group">
			                   <label><a  data-toggle="tooltip" title="Average of selected installment will be set as max payable">Average Calculation Installment</a></label>
							   	 <select id="avg_calc_ins" class="form-control" data-placeholder="Select installment" name='sch[avg_calc_ins]'></select> 
								<p class="help-block"></p>
			                </div> 	
		               </div>
		               	
		               	  <div class="col-sm-3">
							<div class="form-group"> 
								 <label> <a  data-toggle="tooltip" title="Enter Close Maturity Days only fix Maturity Date is fixed"> Closing Maturity Days</a></label>
								 <input  class="form-control" type="nnumber" placeholder="Close Maturity Days"  value="<?php echo set_value('sch[closing_maturity_days]',$sch['closing_maturity_days']); ?>" id="closing_maturity_days"name="sch[closing_maturity_days]"/>
							 </div>
					   </div>
		               	
		               	<div class="col-sm-3"  id="maturity_setting" style="<?php echo $sch['maturity_type'] == 2 ? "" : "display: none;" ;?>">
							<div class="form-group"> 
								 <label> <a  data-toggle="tooltip" title="Enter Maturity Days only fix Maturity Date is fixed"> Maturity Days</a></label>
								 <input  class="form-control" type="nnumber" placeholder="Maturity Days"  value="<?php echo set_value('sch[maturity_days]',$sch['maturity_days']); ?>" id="maturity_days"name="sch[maturity_days]"/>
							 </div>
					   </div>
		               <div class="col-sm-6">
            		   		<div class="form-group">
                           	   <label>
                           		<input type="checkbox" name="sch[is_pan_required]" value="1" class="minimal"  <?php if($sch['is_pan_required']==1){  ?> checked <?php }?> />
                           		 &nbsp;Required PAN Number
                           	   </label>	
                       		</div>
                       	</div> 
                       	<div class="col-sm-3">
            		   		<div class="form-group">
                           	   <label><a  data-toggle="tooltip" title="PAN Number is made mandatory on scheme joining, when scheme amount is greater than or equal to PAN Required Amount"> PAN Required Amount</a></label>	
                           		 <input  class="form-control" type="number" placeholder="Amount"  value="<?php echo set_value('sch[pan_req_amt]',$sch['pan_req_amt']); ?>" id="pan_req_amt"name="sch[pan_req_amt]"/>
                           	   
                       		</div>
                       	</div> 
					 </div>
				</div> 
				<div class="col-sm-4"> 
					<div class="form-group">
					   <label for="chargeseme_name">Upload Scheme image</label> 
						<input id="edit_sch_img" name="sch[edit_sch_img]" accept="image/*" type="file" >
							 <img src="<?php echo(isset($sch['logo'])? base_url().'assets/img/sch_image/'.$sch['logo']: base_url().('assets/img/no_image.png')); ?>" class="img-thumbnail" id="edit_sch_img_preview" style="width:304px;height:100%;" alt="scheme image"> 
						<p class="help-block"></p>  
					</div>
				</div>
			</div> 
		  <div id="payment_type" style="display:none;">
		  	<legend><a  data-toggle="tooltip" title="Flexible Scheme Settings ">Flexible Scheme Settings</a></legend>				  <div class="row">
				<div class="col-sm-3">
					<div class="form-group">
					   <label> Flexible Scheme Type </label>
						<select class='form-control' id="pay_type">
							<option   value="" <?php if($sch['flexible_sch_type']==''){  ?>selected <?php } ?>> -- Select --</option>
							<option value="1"  <?php if($sch['flexible_sch_type']==1){  ?>selected <?php } ?>>Amount</option>
							<option  value="2"  <?php if($sch['flexible_sch_type']==2){  ?>selected <?php } ?>>Amount to Weight(based on Amount)</option>
							<option  value="3"  <?php if($sch['flexible_sch_type']==3){  ?>selected <?php } ?>>Amount to Weight(based on Weight)</option>
							<option   value="4"  <?php if($sch['flexible_sch_type']==4){  ?>selected <?php } ?>>Weight</option>
						</select>
						<input type="hidden" id="flexible_sch_type" name="sch[flexible_sch_type]" value="<?php echo set_value('sch[flexible_sch_type]',$sch['flexible_sch_type']); ?>"  />
						<p class="help-block"></p>  
					</div>  
		      	</div>
		      	<div class="col-sm-3"  id="premium_settings" style="display:none;">
					<div class="form-group"> 
						<label>One Time Premium</label> 
						<div class="row">
							<div class="col-sm-3">
								<input type="radio"  id="one_time_premium" class="minimal"  name="sch[one_time_premium]"  <?php if($sch['one_time_premium']==1){  ?>checked <?php } ?> value="1"/> Yes
							</div> 
							<div class="col-sm-3">
								<input type="radio"  id="one_time_premium" class="minimal" name="sch[one_time_premium]"   <?php if($sch['one_time_premium']==0){  ?>checked <?php } ?> value="0"/>
							No 
							</div> 
						</div> 
						<p class="help-block"></p> 
					</div>
				</div>
				<div class="col-sm-4" id="weight_convert">
				  <label><a  data-toggle="tooltip" title="Select sheme payment Weight Convertion">Weight Convertion</a></label>			
					<div class="form-group">
					   <div class="col-md-6">
						   <label>
							   <input type="radio" id="wgt_convert_daily" name="sch[wgt_convert]" class="minimal" <?php if($sch['wgt_convert']==0){  ?> checked<?php } ?> value="0"/> Daily
							</label>
					   </div>
					   <div class="col-md-6">		
						<label>
						 <input type="radio" id="wgt_convert_month" name="sch[wgt_convert]" class="minimal" <?php if($sch['wgt_convert']==1){  ?> checked<?php } ?> value="1"/> End Of Scheme 
						</label>
					   </div>
					   <div class="col-md-6">		
						<label>
						 <input type="radio" id="wgt_convert_month" name="sch[wgt_convert]" class="minimal" <?php if($sch['wgt_convert']==2){  ?> checked<?php } ?> value="2"/> None 
						</label>
					   </div>
					   <p class="help-block"></p> 
	                </div>
				</div>
			  </div> 
			  
			  <div id="price_settings" style="display:none;">
				<div class="row">
					<div class="form-group"> 
							<div class="col-md-2">
								<label>Price Fixing</label>
								<br />
							</div>
							<div class="col-sm-8">
								<div class="col-sm-4">
									 <input type="radio" id="otp_price_fixing" name="sch[otp_price_fixing]" class="minimal"  <?php if($sch['otp_price_fixing']==1){  ?> checked<?php } ?>  value="1"> 
	                             Yes
								</div> 
								<div class="col-sm-4">
									 <input type="radio" id="otp_price_fixing" name="sch[otp_price_fixing]" class="minimal" <?php if($sch['otp_price_fixing']==0){  ?> checked<?php } ?>  value="0"> 
									  No
								</div> 
							</div> 
							<p class="help-block"></p> 
					</div>
			    </div>
				<div class="row">
					<div class="form-group"> 
						<div class="col-md-2">
							<br />
						</div>
						<div class="col-sm-8">
							<div class="col-sm-4">
								<input type="radio" id="otp_price_fix_single" name="sch[otp_price_fix_type]"  class="minimal" <?php if($sch['otp_price_fix_type']==1){  ?> checked<?php } ?> value="1"> 
								Single
							</div> 
							<div class="col-sm-4">
								  <input type="radio" id="otp_price_fix_multiple" name="sch[otp_price_fix_type]"  class="minimal" <?php if($sch['otp_price_fix_type']==2){  ?> checked<?php } ?>value="2" > 
						  Multiple
							</div> 
						</div> 
						<p class="help-block"></p> 
					</div>
				</div> 
		     </div>
		 </div>
		<br/>  
		<div id="paymenttype_settings" style="display:none"> 
			<div class="row">				 
				<div class="col-sm-offset-1 col-sm-3" id="paymentmethod_settings" style="display:none">
				  	<legend><a  data-toggle="tooltip" title="Set Payment Allowed per month ">Payment Type</a></legend>
					<div class="form-group">
					   <div class="col-sm-12">
						   <label>
							   <input type="radio" id="payment_single" name="sch[pay_duration]" class="minimal" <?php if($sch['pay_duration']==0){  ?> checked<?php } ?> value="0"/>
		                     Daily Payment
							</label>
					   </div>
					   <div class="col-sm-12">		
						<label>
						 <input type="radio" id="payment_multiple" name="sch[pay_duration]" class="minimal" <?php if($sch['pay_duration']==1){  ?> checked<?php } ?> value="1"/>
						  Monthly Payment
						</label>
					   </div>	
						 <p class="help-block"></p>                       	
					 </div> 
			     </div>
			     <div class="col-sm-4">
			     	 <legend><a  data-toggle="tooltip" title="Set Payment Allowed per month ">Payment Allowed per month</a></legend>
						<div class="form-group">
						   <div class="col-md-12">		
							 <label>
							 <input type="radio" id="pay_multiple" name="sch[payment_chances]" class="minimal" <?php if($sch['payment_chances']==1){  ?> checked<?php } ?> value="1"/> Multiple Payment</label>
						   </div>	
						   <div class="col-md-12">
							   <label>
								   <input type="radio" id="pay_single" name="sch[payment_chances]" class="minimal" <?php if($sch['payment_chances']==0){  ?> checked<?php } ?> value="0"/>  Single Payment</label>
						   </div>
						    <p class="help-block"></p>                       	
						</div>
			         
			     </div>    
			     <div class="col-md-4">
				   <legend><a  data-toggle="tooltip" title="Set your scheme payment Chances limit ">Payment Chances</a></legend>
					<div class="form-group">
					   <div class="col-sm-6">
						   <label for="units" >Minimum Limit</label>
							<input type="text" class="form-control" id="min_chance" required="true" name="sch[min_chance]" value="<?php echo set_value('sch[min_chance]',$sch['min_chance']); ?>" <?php if($sch['payment_chances']==0){  ?> disabled="true" <?php } ?> />
						 </div>	
						 <div class="col-sm-6"> 
							<label for="units" >Maximum Limit</label>
							<input type="text" class="form-control" id="max_chance" required="true" name="sch[max_chance]" value="<?php echo set_value('sch[max_chance]',$sch['max_chance']); ?>" <?php if($sch['payment_chances']==0){  ?>disabled="true" <?php } ?> />		
					     </div>
					</div>
				 	<p class="help-block"></p> 
				</div>  
		     </div> 
		     <br/>
		     <div class="row">   
				<div class="col-sm-offset-1 col-sm-3" id="weighttye_settings" style="display:none;">
					 <legend><a  data-toggle="tooltip" title="Enter your scheme max_weight and min_weight Limit"> Payment Weight Limit</a></legend>
				    <div class="form-group">
					   <div class="col-sm-6">
						   <label for="units" >Minimum</label>
						   <input type="text" class="form-control input_weight" id="min_weight" name="sch[min_weight]" value="<?php echo set_value('sch[min_weight]',$sch['min_weight']); ?>" <?php if($sch['scheme_type']==0){ ?> disabled="true" <?php } ?> />
						</div>
						<div class="col-sm-6">
							<label for="units" >Maximum</label>
							 <input type="text" class="form-control input_weight" id="max_weight" name="sch[max_weight]" value="<?php echo set_value('sch[max_weight]',$sch['max_weight']); ?>" <?php if($sch['scheme_type']==0){ ?> disabled="true" <?php } ?>  />		
						</div>
						 <p class="help-block"></p>
				    </div>
				</div> 
				<div class="row"> 
    				<div class="col-sm-offset-1 col-sm-3"  id="paymentamount_limit" style="display:none;">  
    					<legend><a  data-toggle="tooltip" title="Enter your scheme max_amount and mini_amount Limit"> Payment Amount Limit</a></legend>
    		            <div class="form-group">
    					   <div class="col-sm-6">					  								   
    						   <label for="units" >Minimum</label>
    						   <input type="text" class="form-control input_amount" id="min_amount" name="sch[min_amount]" value="<?php echo set_value('sch[min_amount]',$sch['min_amount']); ?>" <?php if($sch['scheme_type']==0){ ?> disabled="true" <?php } ?> />
    						</div>
    						<div class="col-sm-6">							
    							<label for="units" >Maximum</label>
    							 <input type="text" class="form-control input_amount" id="max_amount" name="sch[max_amount]" value="<?php echo set_value('sch[max_amount]',$sch['max_amount']); ?>" <?php if($sch['scheme_type']==0){ ?> disabled="true" <?php } ?>  />		
    						</div>  
    			        <p class="help-block"></p> 
    			        </div> 
    			        <div id="flx_denomintions" style="display:block;"> <!-- enabled based on the schem type- flx sch type 1,2 H-->
    			            <div class="col-sm-12">	
    			                <label for="units" >Flx Denomintion(Mult of val)</label>
    						   <input type="text" class="form-control input_amount" id="flx_denomintion" name="sch[flx_denomintion]" value="<?php echo set_value('sch[flx_denomintion]',$sch['flx_denomintion']); ?>" <?php if($sch['scheme_type']==3 && $sch['flexible_sch_type']==1 && $sch['flexible_sch_type']==2 ){ ?> disabled="true" <?php } ?> />
    						</div>
    					</div>
    				</div> 
    				<div class="col-sm-6"  id="paymentamount_limit" style="display:none;">  
    				    <legend><a  data-toggle="tooltip" title="Scheme Payable Settings"> Payable Settings</a></legend>
        				<div class="form-group">
    			    		<input type="checkbox" value=1 id="firstPayamt_as_payamt"  name="sch[firstPayamt_as_payamt]" <?php if($sch['firstPayamt_as_payamt']==1){ ?> checked="checked" <?php } ?> />
    			    	  	<label for="firstPayamt_as_payamt">Fix First payment as Payable </label> 
    			    	</div> 
    			        
    			        <div class="form-group">
    			    		<input type="checkbox" value=1 id="firstPayamt_maxpayable"  name="sch[firstPayamt_maxpayable]" <?php if($sch['firstPayamt_maxpayable']==1){ ?> checked="checked" <?php } ?> />
    			    	    <label for="firstPayamt_maxpayable">Fix First payment as Max Amt </label> 
    			        </div> 
    			        
    			        <div class="form-group">
    			    		<input type="checkbox" value=1 id="get_amt_in_schjoin"  name="sch[get_amt_in_schjoin]" <?php if($sch['get_amt_in_schjoin']==1){ ?> checked="checked" <?php } ?> />
    			    	    <label for="get_amt_in_schjoin">Get payment amount in scheme join </label> 
    			        </div> 
				    </div>
				</div> 
			</div>
		</div>
		<br/>
	    <div class="row" id="settlement_settings" style="display:none">
	    	<div class="col-sm-12">
	    	<div class="box">
				<div class="box-header with-border">
				  <h3 class="box-title">Settlement Setting</h3>
				</div>
				<div class="box-body">
       			<div class="row">
				 	<div class="form-group">
                       <label for="chargeseme_name" class="col-md-2 col-md-offset-1 ">Type</label>
                       <div class="col-md-6">
                            <label class="radio-inline">
						      <input type="radio" name="sch[type]" <?php if($sch['type']==1){?> checked="true" <?php } ?> value="1" data-toggle="tooltip" title="Settlement will be done by considering Gold rates of the whole month">Monthly
						    </label>
						    <label class="radio-inline">
						      <input type="radio" name="sch[type]" <?php if($sch['type']==2){?> checked="true" <?php } ?> value="2" data-toggle="tooltip" title="Settlement will be done by considering Gold rates of scheme payment dates">Purchase
						    </label>    
						    <label class="radio-inline">
						      <input type="radio" name="sch[type]"  <?php if($sch['type']==3){?> checked="true" <?php } ?> value="3">No Settlement
						    </label> 
						     <!--<label class="radio-inline">
						      <input type="radio" name="set[type]"  <?php if($set['type']==4){?> checked="true" <?php } ?> value="4">Manual
						    </label>-->
                       	       <p class="help-block"></p>	                       	
                       </div>
                    </div>
				 </div>		
				 
				 <div class="row adjust_block">
				 	<div class="form-group">
                       <label for="chargeseme_name" class="col-md-2 col-md-offset-1 ">Adjust By</label>
                       <div class="col-md-6">
                            <label class="radio-inline">
						      <input type="radio" name="sch[adjust_by]" <?php if($sch['adjust_by']==1){?> checked="true" <?php } ?> value="1">Highest
						    </label>
						    <label class="radio-inline">
						      <input type="radio" name="sch[adjust_by]"  <?php if($sch['adjust_by']==2){?> checked="true" <?php } ?> value="2">Lowest
						    </label>    
						    <label class="radio-inline">
						      <input type="radio" name="sch[adjust_by]"  <?php if($sch['adjust_by']==3){?> checked="true" <?php } ?> value="3">Average
						    </label>
						     <label class="radio-inline">
						      <input type="radio" name="sch[adjust_by]"  <?php if($sch['adjust_by']==4){?> checked="true" <?php } ?> value="4">Manual
						    </label>
                       	       <p class="help-block"></p>	                       	
                       </div>
                    </div>
                 </div>	 
				</div>           			
            </div>    
	    </div>
	</div> 
	
<div class="row"> <!--lucky draw settings HH -->
		<div class="col-sm-12">
	         <legend><a  data-toggle="tooltip" title="enable  Lucky Draw settings">  Lucky Draw</a></legend>
	         
			<div class="form-group"> 
			<div class="col-sm-8" style="margin-top: 29px;">
				<div class="col-sm-4">
					<input type="checkbox" id="isLuckyDraw" name="sch[is_lucky_draw]" value="1"<?php if($sch['is_lucky_draw']==1){?>checked="true" <?php } ?> />
					Enable  Lucky Draw
				<br />
				</div>
				<div id="isLuckyDraw_block"> 
				<div class="col-sm-4">
				       <input type="checkbox"  class="minimal isLuckyDraw_block"   name="sch[has_prize]" value="1"<?php if($sch['has_prize']==1){?>checked="true" <?php } ?> />
				       Has Prize
				   </div> 
			  
			<div class="col-sm-4">
					<label>Maximum Members</label>
					<input type="number" class="form-control isLuckyDraw_block" id="max_members" name="sch[max_members]" value="<?php echo set_value('sch[max_members]',$sch['max_members']); ?>" placeholder="Enter Members" /> 
				<br />
				</div>
				</div> 
		</div> 
			 <p class="help-block"></p> 	   
			</div>
		
	  </div>
  </div><br/>
 
	<!--Add price fixing settings option HH-->	 
	 <div class="row">
		<div class="col-sm-12">
	         <legend><a  data-toggle="tooltip" title="Add Your Payment Charges"> Payment Charges</a></legend>
			<div class="form-group"> 
				<div class="col-sm-4">
					<label>Label for Charge</label>
					<input type="text" class="form-control" required="true" name="sch[charge_head]" value="<?php echo set_value('sch[charge_head]',$sch['charge_head']); ?>" />
				<br />
				</div>
				<div class="col-sm-8" style="margin-top: 29px;">
				  <div class="col-sm-4">
					   <input type="radio"  name="sch[charge_type]" class="minimal" <?php if($sch['charge_type']==0){  ?>checked <?php } ?> value="0"/> Percentage
				   </div> 
				   <div class="col-sm-4">
					   <input type="radio" name="sch[charge_type]" class="minimal"  <?php if($sch['charge_type']==1){  ?>checked <?php } ?> value="1"/>
					  Amount 
				   </div> 
				   <div class="col-sm-4">
					<input type="text" class="form-control"  name="sch[charge]" value="<?php echo set_value('sch[charge]',$sch['charge']); ?>" /> 
				   </div>
				</div> 
				   <p class="help-block"></p> 
			</div>
		</div>
	  </div><br/>
	  <div id="payment_setting">	
		 <div class="row" id="free_pay_settings">
			<div class="col-sm-12">
				<legend><a  data-toggle="tooltip" title="Select single or multiple payment"> Free Payment settings</a></legend>
            <div class="row">
			    <div class="col-sm-12">
			      <div class="form-group"> 
    			    <div class="col-md-3">
    			    	<div <?php if($discount['free_first_payment']==1) { ?> class="form-group" <?php }else{ ?> class="form-group" <?php } ?>>
    					   	<label>Free Installments</label>
    					   	<input type="checkbox"  id="has_free_ins" name="sch[has_free_ins]" value="1" <?php if($sch['has_free_ins']==1) { ?> checked="true" <?php } ?>/>
    					   <!--	<input type="text" id="free_payInstallments" 
    					name="sch[free_payInstallments]" value=" <?php echo set_value('sch[free_payInstallments]',$sch['free_payInstallments']); ?>"/>-->
    					<div class="form-group">
    		              <select class="form-control select2 cls" id='free_instalments'  multiple="multiple" data-placeholder="Select a installment no" 
    		                       style="width: 100%;" disabled="true">
    		                </select>
    		                <input type="hidden" class="free_payInstallments" name='sch[free_payInstallments]' />
    		              </div>
    					</div>
    		    	</div>
			        <div class="col-sm-3">
			    	  <label>
			    		<input type="checkbox" id="free_payment"  name="sch[free_payment]" <?php if($sch['free_payment']==1){ ?> checked="checked" <?php } ?> value="1" /><span  data-toggle="tooltip" title="Free payment will be credited on a/c joining"> First payment free </span>
			    	  </label>
			        </div> 
			        <div class="col-sm-3">
				      <div class="form-group "> 
			    	  <label>
			    		<input type="checkbox"  id="allowSecondPay" name="sch[allowSecondPay]" value="1" <?php if($sch['allowSecondPay']==1) { ?> checked="true" <?php } ?>/><span  data-toggle="tooltip" title="Allow 2nd ins payment after 1st free payment"> Allow Second Pay </span>
			    	  </label> 			    	
				      </div>
			   		</div>    
			   	   <div class="col-sm-3">
                         <div class="form-group "> 
    				    	  <label>
    				    		<input type="checkbox"  id="approvalReqForFP" name="sch[approvalReqForFP]" value="1" <?php if($sch['approvalReqForFP']==1) { ?> checked="true" <?php } ?>/><span  data-toggle="tooltip" title="Admin approval required"> Approval required for Free Payment </span>
    				    	  </label> 			    	
				      </div>
			   		</div>  				   		
			      </div>   
			    </div>   				 			 	
			 </div>
			 </div>
			 </div>
			 <!-- Pending payment -->
			 <div class="row">
			    <div class="col-sm-12">
				<legend><a  data-toggle="tooltip" title="Select single or multiple payment"> Payment Settings</a></legend>
			      <div class="form-group">
			        <div class="col-sm-4">
			    	  <label>
			    		<input type="checkbox" id="allow_unpaid" class="amtsch_block" name="sch[allow_unpaid]" <?php if($sch['allow_unpaid']==1){ ?> checked="checked" <?php } ?> value="1" /> <span  data-toggle="tooltip" title="If selected,customers are allowed to pay pending dues.">Allow pending due</span> 
			    	  </label>
			        </div> 
			        <div class="col-sm-4">
				      <div class="form-group ">
					    	<label class="col-sm-6" ><span  data-toggle="tooltip" title="Number of dues allowed.">No.of dues</span></label>
					    	<div class="col-sm-6">
					    		 <input type="text" class="form-control unpaid_block" id="unpaid_months" name="sch[unpaid_months]" value="<?php echo set_value('sch[unpaid_months]',$sch['unpaid_months']); ?>"  <?php if($sch['allow_unpaid']!=1){ ?> disabled="true" <?php } ?>  />
					    	</div>					    	
				      </div>
			   		</div>    
			   	   <div class="col-sm-4">
			   		</div>  
			      </div>   
			    </div>   
			 </div>	 
			 <!-- /Pending payment -->
			 <br/>
			 <!-- Advance payment -->
			 <div class="row">
			    <div class="col-sm-12">
			      <div class="form-group">
			        <div class="col-sm-4">
			    	  <label>
			    		<input type="checkbox" id="allow_advance" class="amtsch_block" name="sch[allow_advance]" <?php if($sch['allow_advance']==1){ ?> checked="checked" <?php } ?> value="1" /><span  data-toggle="tooltip" title="If selected,customers are allowed to pay advance payment."> Allow advance payment </span>
			    	  </label>
			        </div> 
			        <div class="col-sm-4">
				      <div class="form-group ">
					    	<label class="col-sm-6" ><span  data-toggle="tooltip" title="Number of dues allowed.">No.of dues</span></label>
					    	<div class="col-sm-6">
					    		 <input type="text" class="form-control advance_block" id="advance_months" name="sch[advance_months]" value="<?php echo set_value('sch[advance_months]',$sch['advance_months']); ?>"  <?php if($sch['allow_advance']!=1){ ?> disabled="true" <?php } ?>  />
					    	</div>					    	
				      </div>
			   		</div>    
			   	   <div class="col-sm-4">
			   		</div>  				   		
			      </div>   
			    </div>   				 			 	
			 </div>
		      <!--/ Advance payment -->   		
		      	<br/>
		     <!-- Pre-close -->
			 <div class="row">
			    <div class="col-sm-12">
			      <div class="form-group">
			        <div class="col-sm-4">
			    	  <label>
			    		<input type="checkbox" id="allow_preclose" class="amtsch_block" name="sch[allow_preclose]" <?php if($sch['allow_preclose']==1){ ?> checked="checked" <?php } ?> value="1" /><span  data-toggle="tooltip" title="If selected,customers can pre-close the scheme account."> Allow pre-close </span>
			    	  </label>
			        </div> 
			        <div class="col-sm-4">
				      <div class="form-group ">
					    	<label class="col-sm-6 "><span  data-toggle="tooltip" title="Number of dues pending.">No.of dues</span></label>
					    	<div class="col-sm-6">
					    		 <input type="text" class="form-control preclose_block" id="preclose_months" name="sch[preclose_months]" value="<?php echo set_value('sch[preclose_months]',$sch['preclose_months']); ?>" <?php if($sch['allow_preclose']!=1){ ?> disabled="true" <?php } ?>   />
					    	</div>					    	
				      </div>
			   		</div>    
			   	   <div class="col-sm-4">
				      <div class="form-group ">
					    	<label class="col-sm-6" ><span  data-toggle="tooltip" title="Select 'YES' if benefits are applicable for pre-closer. ">Avail Benefits</span></label>
					    	<div class="col-sm-6">
					    				    		<input type="checkbox" id="preclose_benefits" class="mySwitch preclose_block" data-on-text="YES" data-off-text="NO" name="sch[preclose_benefits]" value="1" <?php if($sch['preclose_benefits']==1) { ?> checked="true" <?php } ?> disabled/>
					    	</div>					    	
				      </div>
			   		</div>  				   		
			      </div>   
			    </div>   				 			 	
			 </div>
         </div></br> 
		      <!--/ Pre-close -->  
		 <div class="row">
			   <div class="col-sm-12">
				 <legend><a  data-toggle="tooltip" title="Add Your Scheme Payment Benefits"> Benefits</a></legend>
			 	<div class="form-group">
                   <label for="payment_limit" class="col-md-2">
					<input type="checkbox" id="isInterest" name="sch[interest]" class="minimal" value="1" <?php if($sch['interest']==1){  ?>checked <?php } ?>/>
                  	Interest
               	 </label>
               <div id="interest_amtblock"> 
				  <div class="col-md-2">
					<label>
					   <input type="radio"  name="sch[interest_by]" class="minimal interest_block" <?php if($sch['interest_by']==0){  ?>checked <?php } ?> value="0"/> Percentage
					</label>
				   </div> 
				   <div class="col-md-2">
					<label>
					   <input type="radio" name="sch[interest_by]" class="minimal interest_block"  <?php if($sch['interest_by']==1){  ?>checked <?php } ?> value="1"/>
					  Amount 
					</label>
				   </div> 
				   <div class="col-md-2">
					<input type="text" class="form-control interest_block" id="interest_value" name="sch[interest_value]" value="<?php echo set_value('sch[interest_value]',$sch['interest_value']); ?>" /> 
				   </div> 
				  <div class="col-md-4">
					   <label class="col-md-3">Total</label>
					   <div class="col-md-8">
					     <div class="input-group">
							<span class="input-group-addon input-sm">
								<?php echo $this->session->userdata('currency_symbol')?>
							</span>
							<input type="text" class="form-control interest_block" id="total_interest" name="sch[total_interest]"  value="<?php echo set_value('sch[total_interest]',$sch['total_interest']); ?>" readonly="true"/>
						 </div>
					   </div> 
				  </div>
			</div>	 
		 	<div id="interest_wgtblock" style="display: none;">
				  <div class="col-sm-offset-6 col-sm-4">
					   <label class="col-sm-4">Weight</label>
					   <div class="col-sm-8">
					     <div class="input-group">
							<input type="text" class="form-control interest_block" id="interest_weight" name="sch[interest_weight]"  value="<?php echo set_value('sch[interest_weight]',$sch['interest_weight']); ?>"/>
							<span class="input-group-addon input-sm">
								g
							</span>
						 </div>
					   </div> 
				  </div>
			</div>             
			<p class="help-block"></p>                       	 	
			 </div> 
			 </div> 
			</div></br>	
			
			 <div class="row">
			   <div class="col-sm-12">
				<div class="form-group">
                   <label for="payment_limit" class="col-md-2 black" style="<?php echo $sch['apply_benefit_by_chart'] == 1 ? "" : "display: none;" ;?>">
					
					<input type="checkbox" id="isapply_benefit_by_chart" name="sch[apply_benefit_by_chart]" value="1"<?php if($sch['apply_benefit_by_chart']==1){?>checked="true" <?php } ?> />
                  	Apply interest by chart
               	 </label>
               	 
               	  <div class="col-md-2 answer"  style="<?php echo $sch['apply_benefit_by_chart'] == 1 ? "" : "display: none;" ;?>" >
					<button type="button" id="proced" class="btn btn-success">ADD+</button>
	                    </span>
					</div> 
					<div class="col-sm-10 answer" style="<?php echo $sch['apply_benefit_by_chart'] == 1 ? "" : "display: none;" ;?>" > 
                	<div class="table-responsive"> 
                         <table id="chart_creation_tbl" width="50%" cellpadding="0" cellspacing="0" class="table table-bordered table-striped text-center" border="#729111 1px solid" >
                         <thead>
                             <tr>  
                       
                        <th>Installment</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Action</th>
                      </tr>
                             
                         </thead>
                          <tbody>
                              <?php foreach($chartData as $key => $chart) {
                                  $amtType = $chart['interest_type'] == 1 ? "checked='checked'" : "";
                                  $perType = $chart['interest_type'] == 0 ? "checked='checked'" : "";
                                  echo "<tr rowID='" .$key. "'><td>From<input type='number' name='installmentchart[".$key."][installment_from]' class='installment_from' value='".$chart['installment_from']."' style='width: 50px;'>To<input type='number' name='installmentchart[".$key."][installment_to]' class='installment_to' value='".$chart['installment_to']."'  style='width: 50px;'></td><td><input type='radio' name='installmentchart[".$key."][interest_type]' class='interest_type' ". $amtType ." value='".$chart['interest_type']."'  style='width: 50px;'>Amt<input type='radio' name='installmentchart[".$key."][interest_type]' class='interest_type'  ". $perType ."  value='".$chart['interest_type']."'  style='width: 50px;'>%</td><td><input type='number' name='installmentchart[".$key."][interest_value]' value='".$chart['interest_value']."' class='form-control interest_value' style='width: 100px;'   style='width: 100px;'></td><td><div><button id='" .$key. "' class='delete btn btn-danger'  name='delete'type='button'><i class='fa fa-trash'></i> REMOVE</button></div></td></tr>";
    
                              } ?>
                          </tbody>
 
                         </table> 
                          
                    </div>
                </div><!-- /.col -->
			
				<p class="help-block"></p>                       	 	
			 </div> 
			</div> 
			</div></br>	
			
			<div class="row">
			   <div class="col-sm-12">
			 	<div class="form-group">
                  <label for="payment_limit" class="col-sm-2">
					 <input type="checkbox" id="isTaxable" class="minimal" name="sch[tax]" <?php if($sch['tax']==1){  ?>checked <?php } ?> value="1"  />
                  	Tax
               	  </label> 
				  <div class="col-sm-2">
					<label>
					   <input type="radio" name="sch[tax_by]" class="minimal tax_block" <?php if($sch['tax_by']==0){  ?>checked <?php } ?> value="0" /> Percentage
					</label>
				   </div> 
				   <div class="col-sm-2">
					<label>
					  <input type="radio" name="sch[tax_by]" class="minimal tax_block" <?php if($sch['tax_by']==1){  ?>checked <?php } ?> value="1"  />
					  Amount 
					</label>
				   </div> 
				   <div class="col-sm-2">
					<input type="text" class="form-control tax_block" id="tax_value" name="sch[tax_value]" value="<?php echo set_value('sch[tax_value]',$sch['tax_value']); ?>" /> 
				   </div> 
				   <div class="col-sm-4">
					   <label class="col-sm-3">Total</label>
					   <div class="col-sm-8">
					     <div class="input-group">
							<span class="input-group-addon input-sm">
									<?php echo $this->session->userdata('currency_symbol')?>
							</span>
							<input type="text" id="total_tax" name="sch[total_tax]" class="form-control tax_block" value="<?php echo set_value('sch[total_tax]',$sch['total_tax']); ?>" readonly="true"/>
						 </div>
					   </div> 
				   </div>
			 </div>
			</div>
		</div><br>
	<!--	<div class="row">
			   <div class="col-sm-offset-1 col-sm-11">
			 	<div class="form-group">
                   <label for="payment_limit" class="col-sm-2">
					 <input type="checkbox" id="isFirstPayDisc" class="minimal" name="sch[firstPayDisc]" <?php if($sch['firstPayDisc']==1){  ?>checked <?php } ?> value="1"  />
                  	First Pay Disc
               	 </label>
                  <div class="col-sm-6">
					  <div class="col-sm-4">
						<label>
						   <input type="radio" name="sch[firstPayDisc_by]" class="minimal" <?php if($sch['firstPayDisc_by']==0){  ?>checked <?php } ?> value="0" /> Percentage
						</label>
					   </div> 
					   <div class="col-sm-4">
						<label>
						  <input type="radio" name="sch[firstPayDisc_by]" class="minimal" <?php if($sch['firstPayDisc_by']==1){  ?>checked <?php } ?> value="1"  />
						  Amount 
						</label>
					   </div> 
					   <div class="col-sm-4">
						<input type="text" class="form-control" id="firstPayDisc_value" name="sch[firstPayDisc_value]" value="<?php echo set_value('sch[firstPayDisc_value]',$sch['firstPayDisc_value']); ?>" /> 
					   </div>
				  </div> 
                   <p class="help-block"></p> 
			 </div> 
			 </div> 
			</div></br>
				<div class="row">
			   <div class="col-sm-offset-1 col-sm-11">
			 	<div class="form-group">
                   <label for="payment_limit" class="col-sm-2">
					 <input type="checkbox" id="all_pay_disc" class="minimal" name="sch[all_pay_disc]" <?php if($sch['all_pay_disc']==1){  ?>checked <?php } ?> value="1"  />
                  	All  Pay Disc
               	 </label>
                  <div class="col-sm-6">
					  <div class="col-sm-4">
						<label>
						   <input type="radio" name="sch[allpay_disc_by]" class="minimal" <?php if($sch['allpay_disc_by']==0){  ?>checked <?php } ?> value="0" /> Percentage
						</label>
					   </div> 
					   <div class="col-sm-4">
						<label>
						  <input type="radio" name="sch[allpay_disc_by]" class="minimal" <?php if($sch['allpay_disc_by']==1){  ?>checked <?php } ?> value="1"  />
						  Amount 
						</label>
					   </div> 
					   <div class="col-sm-4">
						<input type="text" class="form-control" id="allpay_disc_value" name="sch[allpay_disc_value]" value="<?php echo set_value('sch[allpay_disc_value]',$sch['allpay_disc_value']); ?>" /> 
					   </div>
				  </div> 
                   <p class="help-block"></p> 
			 </div> 
			 </div> 
			</div></br>-->
			<!--<div class="row">
			   <div class="col-sm-offset-1 col-sm-11">
			    <legend><a  data-toggle="tooltip" title="Select Scheme Refferal Payment Benefits"> Refferal Benifits</a></legend>
			 	<div class="form-group">
                   <label for="payment_limit" class="col-sm-3">
						Referral By 
               	 </label>
                  <div class="col-sm-9" style="margin-left: -51px;">
					  <div class="col-sm-4">
						<label for="payment_limit">
							 <input type="checkbox" id="emp_refferal" class="minimal" name="sch[emp_refferal]" <?php if($sch['emp_refferal']==1){  ?>checked <?php } ?> value="1"  />
							Employee
						</label>
					   </div> 
					   <div class="col-sm-4">
						 <label for="payment_limit">
							 <input type="checkbox" id="cus_refferal" class="minimal" name="sch[cus_refferal]" <?php if($sch['cus_refferal']==1){  ?>checked <?php } ?> value="1"  />
							Customer 
						 </label>
					   </div> 
				  </div> 
                   <p class="help-block"></p> 
			 </div>			
			 </div> 
			</div></br>-->
			<br>
			<div class="row">
			   <div class="col-sm-12">
			 	<div class="form-group">
               	 <label for="payment_limit" class="col-sm-2">
					 <input type="checkbox" id="discount" class="minimal" name="sch[discount]" <?php if($sch['discount']==1){  ?>checked <?php } ?> value="1"  />
                  	Discount
               	 </label>
				  <div class="col-sm-2">
					<label>
					   <input type="radio" id="discount_type" name="sch[discount_type]" class="minimal" <?php if($sch['discount_type']==0){  ?>checked <?php } ?> value="0" /> All Installments
					</label>
				   </div> 
				   <div class="col-sm-2">
					<label>
					  <input type="radio" id="discount_type" name="sch[discount_type]" class="minimal" <?php if($sch['discount_type']==1){  ?>checked <?php } ?> value="1"  /> Specific Installments
					 </label>
				   </div> 
				    <div class="col-sm-2">
				     <select id="disc_select" class="form-control" data-placeholder="Select Your installments" ></select>
	                <input type="hidden" class="disc_select" name='sch[discount_installment]' />
				   </div>
				</div> 
                <p class="help-block"></p> 
			  </div> 
			</div></br>
			<div class="row">
				<div class="col-sm-12">
					<label for="" class="col-sm-2">Discount Type</label> 
					<div class=" col-sm-2">
						<label>
							<input type="radio" name="sch[firstPayDisc_by]" class="minimal" <?php if($sch['firstPayDisc_by']==0){  ?>checked <?php } ?> value="0" /> Percentage
						</label>
					</div> 
					<div class="col-sm-2">
						<label>
							<input type="radio" name="sch[firstPayDisc_by]" class="minimal" <?php if($sch['firstPayDisc_by']==1){  ?>checked <?php } ?> value="1"  />
						Amount 
						</label>
					</div> 
					<div class="col-sm-2">
						<input type="text" class="form-control" id="firstPayDisc_value" name="sch[firstPayDisc_value]" value="<?php echo set_value('sch[firstPayDisc_value]',$sch['firstPayDisc_value']); ?>" /> 
					</div>
				
				
				</div>
			</div><br/>
			
			<div class="row">
			   <div class="col-sm-12">
				<div class="form-group">
                   <label for="payment_limit" class="col-md-2 precloseblock_open" style="<?php echo $sch['apply_debit_on_preclose'] == 1 ? "" : "display: black;" ;?>">
					
					<input type="checkbox" id="apply_debit_on_preclose" name="sch[apply_debit_on_preclose]" value="1"<?php if($sch['apply_debit_on_preclose']==1){?>checked="true" <?php } ?> />
                  	Deduct closing balance on pre-close
               	 </label>
               	 
               	  <div class="col-md-2 precloseblock" style="<?php echo $sch['apply_debit_on_preclose'] == 1 ? "" : "display: none;" ;?>" >
					<button type="button" id="proceds" class="btn btn-success">ADD+</button>
	                    </span>
					</div> 
					<div class="col-sm-10 precloseblock" style="<?php echo $sch['apply_debit_on_preclose'] == 1 ? "" : "display: none;" ;?>"> 
                	<div class="table-responsive"> 
                         <table id="preclose_chart_creation_tbl" width="50%" cellpadding="0" cellspacing="0" class="table table-bordered table-striped text-center" border="#729111 1px solid" >
                         <thead>
                             <tr>  
                       
                        <th>Installment</th>
                        <th>Duduction Type</th>
                        <th>Duduction Value</th>
                        <th>Action</th>
                      </tr>
                             
                         </thead>
                          <tbody>
                              <?php foreach($preclosechartdata as $key => $chart) {
                                  $amtType = $chart['deduction_type'] == 1 ? "checked='checked'" : "";
                                  $perType = $chart['deduction_type'] == 0 ? "checked='checked'" : "";
                                  echo "<tr rowID='" .$key. "'><td>From<input type='number' name='installmentpreclosechart[".$key."][installment_from]' class='installment_from' value='".$chart['installment_from']."' style='width: 50px;'>To<input type='number' name='installmentpreclosechart[".$key."][installment_to]' class='installment_to' value='".$chart['installment_to']."'  style='width: 50px;'></td><td><input type='radio' name='installmentpreclosechart[".$key."][deduction_type]' class='deduction_type' ". $amtType ." value='".$chart['deduction_type']."'  style='width: 50px;'>Amt<input type='radio' name='installmentpreclosechart[".$key."][deduction_type]' class='deduction_type'  ". $perType ."  value='".$chart['deduction_type']."'  style='width: 50px;'>%</td><td><input type='number' name='installmentpreclosechart[".$key."][deduction_value]' value='".$chart['deduction_value']."' class='form-control deduction_value' style='width: 100px;'   style='width: 100px;'></td><td><div><button id='" .$key. "' class='delete btn btn-danger'  name='delete'type='button'><i class='fa fa-trash'></i> REMOVE</button></div></td></tr>";
    
                              } ?>
                          </tbody>
 
                         </table> 
                          
                    </div>
                </div><!-- /.col -->
			
				<p class="help-block"></p>                       	 	
			 </div> 
			</div> 
			
				<div class="col-sm-4">
					   <label class="col-sm-3"><a  data-toggle="tooltip" title="Minimun installment for preclose with discount/benefit available."> Preclose Min Ins</a></label>
					   <div class="col-sm-8">
					     <div class="form-group">
							<select id="apply_benefit_min_ins" class="form-control" data-placeholder="Select installment" name='sch[apply_benefit_min_ins]'></select> 
						 </div>
					   </div> 
				   </div> 
			</div></br>
			
			
			<div class="row">
			   <div class="col-sm-12">
				    <legend><a  data-toggle="tooltip" title="Select Scheme Refferal Payment Benefits"> Refferal Benifits</a></legend>
				 	<div class="form-group">
	                   <label for="payment_limit" class="col-sm-2">
					   Referrals Applicable
	               	 </label> 
					  <div class="col-sm-2">
						<label>
						   <input type="radio" name="sch[ref_benifitadd_ins_type]" class="minimal" <?php if($sch['ref_benifitadd_ins_type']==0){  ?>checked <?php } ?> value="0" /> All Installments
						</label>
					   </div> 
					   <div class="col-sm-2">
						<label>
						  <input type="radio" name="sch[ref_benifitadd_ins_type]" class="minimal" <?php if($sch['ref_benifitadd_ins_type']==1){  ?>checked <?php } ?> value="1"  /> Specific Installments
						 </label>
					   </div> 
					   <div class="col-sm-2">
					     <select id="install_select" class="form-control" data-placeholder="Select Your installments" ></select>
		                 <input type="hidden" class="installs_select" name='sch[ref_benifitadd_ins]' />
					   </div> 
	                   <p class="help-block"></p> 
				 	</div>			
			 	</div> 
			</div></br>				
			<div class="row">
			   <div class="col-sm-12">
			 	<div class="form-group">
                   <label for="payment_limit" class="col-sm-2">
					 <input type="checkbox" id="emp_refferal" class="minimal" name="sch[emp_refferal]" <?php if($sch['emp_refferal']==1){  ?>checked <?php } ?> value="1"  />
                  	Employee
               	 </label> 
				  <div class="col-sm-2">
					<label>
					   <input type="radio" name="sch[emp_refferal_by]" class="minimal" <?php if($sch['emp_refferal_by']==0){  ?>checked <?php } ?> value="0" /> Percentage
					</label>
				   </div> 
				   <div class="col-sm-2">
					<label>
					  <input type="radio" name="sch[emp_refferal_by]" class="minimal" <?php if($sch['emp_refferal_by']==1){  ?>checked <?php } ?> value="1"  />
					  Amount 
					</label>
				   </div> 
				   <div class="col-sm-2">
					<input type="text" class="form-control" id="emp_refferal_value" name="sch[Emp_ref_values]" value="<?php echo set_value('sch[Emp_ref_values]',$sch['Emp_ref_values']); ?>" /> 
				   </div> 
                   <p class="help-block"></p> 
			 </div>			
			 </div> 
			</div></br>
			<div class="row">
			   <div class="col-sm-12">
			 	<div class="form-group"> 
                   <label for="payment_limit" class="col-sm-2">
					 <input type="checkbox" id="cus_refferal" class="minimal" name="sch[cus_refferal]" <?php if($sch['cus_refferal']==1){  ?>checked <?php } ?> value="1"  />
                  	Customer 
	               </label> 
				   <div class="col-sm-2">
						<label>
						   <input type="radio" name="sch[cus_refferal_by]" class="minimal" <?php if($sch['cus_refferal_by']==0){  ?>checked <?php } ?> value="0" /> Percentage
						</label>
					   </div>  
					   <div class="col-sm-2">
						<label>
						  <input type="radio" name="sch[cus_refferal_by]" class="minimal" <?php if($sch['cus_refferal_by']==1){  ?>checked <?php } ?> value="1"  />
						  Amount 
						</label>
					   </div> 
					   <div class="col-sm-2">
						<input type="text" class="form-control" id="cus_refferal_value" name="sch[cus_ref_values]" value="<?php echo set_value('sch[cus_ref_values]',$sch['cus_ref_values']); ?>" /> 
					   </div> 
	                   <p class="help-block"></p>                                          	 	
				 	</div> 
			 </div> 
			</div>
		<input type="hidden"  name="gst_setting" value="<?php echo set_value('gst[gst_setting]',$gst['gst_setting']) ?>" />
					 <?php if($gst['gst_setting']==1) { ?>
			<!-- START OF GST Settings -->
             <legend>GST Settings</legend>
              <div class="row">
               <div class="col-md-12">
                  <div class="col-sm-12">
					  <label>GST type</label>
					  <div class="row">
					  <div class="col-sm-3">
						  <input type="radio" name="sch[gst_type]" value="0" <?php if($sch['gst_type'] == 0){ ?> checked="true" <?php } ?> > Inclusive
					   </div> 
					   <div class="col-sm-3">
						   <input type="radio" name="sch[gst_type]" value="1" <?php if($sch['gst_type'] == 1){ ?> checked="true" <?php } ?> > Exclusive	
					   </div> 
					   <div class="col-sm-3">
						   <input type="text" class="form-control" id="hsn_code" name="sch[hsn_code]" value="<?php echo set_value('sch[hsn_code]',$sch['hsn_code']); ?>" placeholder="HSN Code" >
						    HSN Code	
					   </div> 
					   </div>
					     <p class="help-block"></p>       
				    </div>
				      <p class="help-block"></p>       
               </div>   
			   <div class="row">
				   <div class="col-sm-offset-1 col-sm-10">
				    	<div class="box box-primary">
							<div class="box-header with-border">
							  <h3 class="box-title">GST Split up</h3>
							</div> 
							<div class="box-body">
							    <?php if($sch['id_scheme']==NULL || $sch['id_scheme']!=''){?>
							    	<div class="row">
									  <div class="form-group">
								        <div class="col-sm-2">
								    	  <input type="text" readonly="true" value="GST" class="form-control " name="gst_data[0][splitup_name]" />
								        </div>
								        <div class="col-sm-2">
									         <div class="input-group ">
									    	  <input type="text" class="form-control " name="gst_data[0][percentage]"  value='3'  />
									    	  <input type="hidden" class="form-control " name="gst_data[0][type]"  value=''  />
									    	  <span class="input-group-addon input-sm"><?php echo '%';?></span>
									        </div>
								        </div>	
									 </div>		                  
									</div>
									<p class="help-block"></p>
				           			<div class="row">
									  <div class="form-group">
								        <div class="col-sm-2">
								    	  <input type="text" class="form-control " name="gst_data[1][splitup_name]"  value="CGST"  />
								        </div>
								         <div class="col-sm-2">
									         <div class="input-group ">
									    	  <input type="text" class="form-control " name="gst_data[1][percentage]"  value="1.5"   />
									    	  <span class="input-group-addon input-sm">%</span>
									        </div>
								        </div>
								        <div class="col-md-6" >
										  <div class="row">
											  <div class="col-md-5">
												  <input type="radio" name="gst_data[1][type]" value="0"  checked="true"  > Same State GST
											   </div> 
											   <div class="col-md-5">
												   <input type="radio" name="gst_data[1][type]" value="1"  > Other State GST	
											   </div> 										  
										   </div>
									    </div> 
									    										 </div>		                  
									</div>
									<p class="help-block"></p>
									<div class="row">
									  <div class="form-group">
								        <div class="col-sm-2">
								    	  <input type="text" class="form-control " name="gst_data[3][splitup_name]"  value="SGST"   />
								        </div>
								         <div class="col-sm-2">
									         <div class="input-group ">
									    	  <input type="text" class="form-control " name="gst_data[3][percentage]"  value="1.5"  />
									    	  <span class="input-group-addon input-sm">%</span>
									        </div>
								        </div>
								        									        <div class="col-md-6" >
										  <div class="row">
											  <div class="col-md-5">
												  <input type="radio" name="gst_data[3][type]" value="0"  checked="true"  > Same State GST
											   </div> 
											   <div class="col-md-5">
												   <input type="radio" name="gst_data[3][type]" value="1"  > Other State GST	
											   </div> 										  
										   </div>
									    </div> 
									    										 </div>		                  
									</div>
									<p class="help-block"></p>
									<div class="row">
									  <div class="form-group">
								        <div class="col-sm-2">
								    	  <input type="text" class="form-control " name="gst_data[4][splitup_name]"  value="IGST"   />
								        </div>
								         <div class="col-sm-2">
									         <div class="input-group ">
									    	  <input type="text" class="form-control " name="gst_data[4][percentage]"  value="3.00" />
									    	  <span class="input-group-addon input-sm">%</span>
									        </div>
								        </div>
								        									        <div class="col-md-6" >
										  <div class="row">
											  <div class="col-md-5">
												  <input type="radio" name="gst_data[4][type]" value="0"    > Same State GST
											   </div> 
											   <div class="col-md-5">
												   <input type="radio" name="gst_data[4][type]" value="1" checked="true" > Other State GST	
											   </div> 										  
										   </div>
									    </div> 
									    										 </div>		                  
									</div>
									<p class="help-block"></p>
								<?php
								}else{
								   $i=0;									    
								   foreach ($gst_data as $gst){?>	
				           			<div class="row">
									  <div class="form-group">
								        <div class="col-sm-2">
								    	  <input type="text" class="form-control " name="gst_data[<?php echo $i?>][splitup_name]"  value="<?php echo set_value('gst_data[splitup_name]',$gst['splitup_name']); ?>"  <?php if($gst['type'] == NULL){?> readonly="true"<?php }?>/>
								        </div>
								         <div class="col-sm-2">
									         <div class="input-group ">
									    	  <input type="text" class="form-control " name="gst_data[<?php echo $i?>][percentage]"  value="<?php echo set_value('gst_data[percentage]',$gst['percentage']); ?>"  />
									    	  <span class="input-group-addon input-sm"><?php echo '%';?></span>
									        </div>
								        </div>
								        <?php if($gst['type'] != NULL){?>
								        <div class="col-md-6" >
										  <div class="row">
											  <div class="col-md-5">
												  <input type="radio" name="gst_data[<?php echo $i?>][type]" value="0" <?php if($gst['type'] == 0){ ?> checked="true" <?php } ?> > Same State GST
											   </div> 
											   <div class="col-md-5">
												   <input type="radio" name="gst_data[<?php echo $i?>][type]" value="1" <?php if($gst['type'] == 1){ ?> checked="true" <?php } ?> > Other State GST	
											   </div> 										  
										   </div>
									    </div>
									    <?php }?>
									 </div>		                  
									</div>
									<p class="help-block"></p>
								<?php 
									$i++;
									}
									if(sizeof($gst_data) > 0){ ?>
               						<input  type="checkbox" name="sch[update_gst]" value="1" class="minimal" /> <span style="color:red;">Check to update GST</span>
								  <?php }
								 }?>										                  
							</div> 
			            </div>    
				    </div>
					 </div>   <?php  } ?>
		      <!-- END OF GST Settings -->  
		  </div>  
		  <div class="row">
			<div class="col-sm-offset-1 col-sm-11">
				<legend><a  data-toggle="tooltip" title="Notification Content"> Notification Content</a></legend>
	      	</div>
	     </div>  
		  <div class="row">			
	    	<div class="col-md-10 col-md-offset-1">
	    		<div class='form-group'>
	               <label for="user_lastname"></label>
	               <textarea class="form-control" name="sch[noti_msg]" id="noti_msg" cols="35" rows="5" tabindex="4" ><?php echo set_value('sch[noti_msg]',$sch['noti_msg']);?></textarea>
	        	</div>
	    	</div>
	      </div>
	      <div class="row">
			<div class="col-sm-offset-1 col-sm-11">
				<legend><a  data-toggle="tooltip" title="Scheme Description"> Description</a></legend>
	      	</div>
	     </div> 
		<div class="row">			
	    	<div class="col-md-10 col-md-offset-1">
	    		<div class='form-group'>
	                <label for="user_lastname"></label>
	               <textarea  id="description" name="sch[description]" ><?php echo set_value('sch[description]',$sch['description']); ?></textarea>
	        	</div>
	    	</div>
	    </div>	
			  <br/>
			 <div class="row">
			  <div class="col-md-12">
			   <div class="box box-default"><br/>
				  <div class="col-xs-offset-5">
					<button type="submit" id="submit" class="btn btn-primary">Save</button> 
					<button type="button" class="btn btn-default btn-cancel">Cancel</button>
				  </div> <br/>
				  </div> 
				</div>
			  </div>      
           </form>              	              	
          </div>
        </div><!-- /.box-body -->
        <div class="box-footer">
        </div><!-- /.box-footer-->
      </div><!-- /.box -->
    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->   
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
		Karigar
		
		</h1>
		<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Master</a></li>
		<li class="active">Add Karigar</li>
		</ol>
	</section>
	<!-- Main content -->
	<section class="content">
		<!-- form -->
		<?php echo form_open_multipart(( $karigar['id_karigar']!=NULL && $karigar['id_karigar']>0 ?'admin_ret_catalog/karigar/update/'.$karigar['id_karigar']:'admin_ret_catalog/karigar/save'),array('id'=>'karigar_form')); ?>
		<!-- Default box -->
		<div class="box">
			<div class="box-header with-border">
			<h3 class="box-title">Add User</h3>
			<div class="box-tools pull-right">
			<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
			<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
			</div>
			</div>
			<div class="box-body">
				<div class="col-md-12">  
					<ul class="nav nav-pills nav-stacked col-md-2">
					<li class="active"><a href="#tab_1" data-toggle="pill">General</a></li>
					
					
					</ul>
					
					<div class="tab-content col-md-10">
						<div class="tab-pane active" id="tab_1"> 
							<div class="col-md-12">
							<div class="row" >
                <div class="col-md-offset-1 col-md-10" id='error-msg'></div>
           </div>
							<div class="row"> 
    							<div class="col-md-3"> 
    								      		<div class='form-group'>
    								               <label for="gender">User Type <span class="error">*</span></label>
    								                    <div class="form-group">
    													  <p class="help-block"></p>
    														  <input type="radio" class = "user_type" id = "click_label_individual" name="karigar[user_type]" value="0" <?php if($karigar['karigar_type']==0){ ?> checked <?php } ?>   ><label for="click_label_individual">&nbsp;&nbsp;Individual</label>&nbsp;&nbsp;
    														  <input type="radio" class = "user_type" id = "click_label_company" name="karigar[user_type]" value="1" <?php if($karigar['karigar_type']==1){ ?> checked <?php } ?> ><label for="click_label_company">&nbsp;&nbsp;Company</label>
    								         	   </div> 
    								            </div> 
    								</div> 
				      	 		
			                </div>	
							<div class='row'>							       
						        <div class='col-sm-4'>
						            <div class='form-group'>
						                <label for="first">First Name<span class="error">*</span></label>
						                <input class="form-control input_text" id="first_name" name="karigar[first_name]" type="text" value="<?php echo set_value('karigar[firstname]',$karigar['firstname']); ?>" placeholder="First Name"/>
						            </div>
						        </div>
						        <div class='col-sm-4'>
						            <div class='form-group'>
						                <label for="last">Last Name</label>
						                <input class="form-control input_text" id="last_name_karigar" name="karigar[last_name_karigar]"  type="text" value="<?php echo set_value('karigar[lastname]',$karigar['lastname']); ?>" placeholder="Last Name"/>
						            </div>
						        </div>
								<div class="col-sm-4">
								    <div class='form-group'>
    					                <label for="code_number">Code<span class="error">*</span></label>
    					                <input class="form-control titlecase" id="karigar_code" name="karigar[karigar_code]"  type="text" placeholder="Code"  value="<?php echo set_value('karigar[code_karigar]',$karigar['code_karigar']); ?>"/>
						            </div>
						    	</div>
									
						        <div class='col-sm-4'>
						            <div class='form-group'>
						                <label for="company">Company Name</label>
						                <input class="form-control input_text" id="company_karigar" name="karigar[company_karigar]" type="text" placeholder="Company Name" value="<?php echo set_value('karigar[company]',$karigar['company']); ?>"/>
						            </div>
						        </div>	
						        <div class="col-sm-4">
								    <div class='form-group'>
    					                <label for="gst_number_karigar">GST Number</label>
    					                <input class="form-control" id="gst_number_karigar"  name="karigar[gst_number]"  type="text" placeholder="GST Number" value="<?php echo set_value('karigar[gst_number]',$karigar['gst_number']); ?>" />
						            </div>
						    	</div>
						    	
						    	<div class="col-sm-4">
						    		<div class='form-group'>
						                <label for="pan">PAN Number</label>
						                <input class="form-control" id="pan" name="karigar[pan_number]"  type="text" placeholder="PAN Number"  value="<?php echo set_value('karigar[pan_no]',$karigar['pan_no']); ?>"/>
						            </div> 
						    	</div>
								<div class="col-sm-4" id="acc_no">
						    		<div class='form-group'>
						                <label for="pan">Account Number</label>
						                <input class="form-control" id="ac_num"  name="karigar[acc_number]" type="text" placeholder="Account Number" value="<?php echo set_value('karigar[acc_number]',$karigar['acc_number']); ?>" />
						            </div> 
						    	</div>
						    	<div class="col-sm-4" id="ifsc_no">
						    		<div class='form-group'>
						                <label for="pan">IFSC Code</label>
						                <input class="form-control" id="ifsc_num" name="karigar[ifsc_code]"  type="text" placeholder="IFSC Code" value="<?php echo set_value('karigar[ifsc_code]',$karigar['ifsc_code']); ?>"/>
						            </div> 
						    	</div>
							     <div class='col-sm-4'>
							        <div class='form-group'>
						                <label for="phone">Mobile<span class="error">*</span></label>
						                <input class="form-control input_number"  name="karigar[mobile]" id="karigar_mobile"  type="text" placeholder="Mobile Number" value="<?php echo set_value('karigar[contactno1]',$karigar['contactno1']); ?>" />
						            </div>
					            </div> 	
                                <div class="col-sm-4">
							     	 <div class='form-group'>
						                <label for="email">Email</label>
						                <input class="form-control" id="email_karigar"  name="karigar[email]" type="text" placeholder="Email"  value="<?php echo set_value('karigar[email]',$karigar['email']); ?>" />
						            </div>
							     </div>									
						    </div>
						    <div class="row">
							     					    
							      <div class="col-sm-4">
							    	<div class='form-group'>
						                <label for="phone">Phone</label>
						                <input class="form-control input_number" id="phone_karigar" name="karigar[phone]"  type="text" placeholder="Phone Number" value="<?php echo set_value('karigar[contactno2]',$karigar['contactno2']); ?>" />
						            </div>
							     </div>
								 <div class='col-sm-4'>
						            <div class='form-group'>
						                <label for="username" data-toggle="tooltip" title="Invalid characters 0-9"> User name</label>
						                <input class="form-control" id="user_name_karigar" name="karigar[user_name]"  type="text" placeholder="Username" value="<?php echo set_value('karigar[urname]',$karigar['urname']); ?>"/>
						            </div>
						        </div>	
							     <div class="col-sm-4"> 
					    			 <div class='form-group'>
						                <label for="password">Password</label>
						                <input class="form-control" id="password" name="karigar[password]"  type="text" placeholder="Password" value="<?php echo set_value('karigar[psword]',$karigar['psword']); ?>" />
						            </div>
							      </div>	
							      
								<div class="col-sm-4">
							      		<div class='form-group'>
							                <label for="address1">Address1</label>
							                <input class="form-control titlecase" id="address1" name="karigar[address1]" type="text" placeholder="Address" value="<?php echo set_value('karigar[address1]',$karigar['address1']); ?>"/>
							            </div>	
							        </div>
							        <div class="col-sm-4">	
							            <div class='form-group'>
							                <label for="address2">Address2</label>
							                <input class="form-control titlecase" id="address2_karigar" name="karigar[address2]"  type="text" placeholder="Address" value="<?php echo set_value('karigar[address2]',$karigar['address2']); ?>" />
							            </div>
							        </div>
							        <div class="col-sm-4">	
										<div class='form-group'>
							                <label for="address3">Address3</label>
							                <input class="form-control titlecase" id="address3" name="karigar[address3]"  type="text" placeholder="Address" value="<?php echo set_value('karigar[address3]',$karigar['address3']); ?>"/>
							            </div>
							        </div>
									<div class="col-sm-4">	
							           <div class='form-group'>
							                <label for="country">Country</label>
							                 <input type="hidden" class="form-control" id="countryval"   value="<?php echo set_value('karigar[id_country]',$karigar['id_country']); ?>"> 
                          					 <select class="form-control" id="country"  name="karigar[country]" placeholder="Enter Short Name"></select>
							            </div>
							        </div>
									 <div class="col-sm-4">	
							            <div class='form-group'>
							                <label for="state">State</label>
							                <input  type="hidden" id="stateval"   value="<?php echo set_value('karigar[id_state]',$karigar['id_state']); ?>" />
							                <select class="form-control" id="state" name="karigar[stateval]" ></select>
							            </div>
							        </div>
							    </div>
						   
							     <div class="row">
							        <div class="col-sm-4">	
							            <div class='form-group'>
							                <label for="city">City</label>
							                  <input  type="hidden" id="cityval"   value="<?php echo set_value('karigar[id_city]',$karigar['id_city']); ?>" />
							                <select class="form-control" id="city" name="karigar[cityval]" ></select>
							            </div>
							        </div>
									<div class="col-sm-4">
							       <div class="form-group">
											<label for="chargeseme_name">Upload image</label>
											<br>
											<input id="user_img" name="karigar[user_img]" accept="image/*" type="file" >
											<p class="help-block">File size should not exceed 1MB<br/>Image format should be .jpg or .png</p>
											<img src="<?php echo(isset($karigar['image'])?$karigar['image']: base_url().('assets/img/no_image.png')); ?>" class="img-thumbnail" id="user_img_preview" style="width:148px;height:100%;" alt="classfication image"> 
											<p class="help-block"></p>
											</div> 
							      	 
							      </div>
									<div class="col-sm-4">	
										<div class='form-group'>
											<label for="scheme_code">Status</label>&nbsp;&nbsp;
											<input type="checkbox" checked="true" class="alert-status" id="user" name="user" data-on-text="YES" data-off-text="NO" />
											<input type="hidden" id="user_status" value="1">
											<p class="help-block"></p>
										</div>
										</div>
									</div>
							    </div>
						</div>
						
					</div>  
				</div>  <!-- /Tab content --> 
			</div><!-- /.box-body -->
			<div class="overlay" style="display:none">
				  <i class="fa fa-refresh fa-spin"></i>
				</div>
		</div><!-- /.box -->
		<div class="row">
		   <div class="box box-default"><br/>
			  <div class="col-xs-offset-5">
				<button type="button" id="add_newuser"  class="btn btn-primary">Save</button> 
				<button type="button" class="btn btn-default btn-cancel">Cancel</button>
			  </div> <br/>
			</div>
		  </div> 
		<?php echo form_close();?> 
	</section><!-- /.content -->
</div><!-- /.content-wrapper -->
<script type="text/javascript">

var cust_id ="<?php echo $customer['id_customer']; ?>";   

var mob_no_len ="<?php echo $this->session->userdata('mob_no_len')?>";   

</script>
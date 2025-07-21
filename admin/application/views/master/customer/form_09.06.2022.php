<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
		Customer
		<small>Complete profile</small>
		</h1>
		<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Master</a></li>
		<li class="active">Add Customer</li>
		</ol>
	</section>
	<!-- Main content -->
	<section class="content">
		<!-- form -->
		<?php echo form_open_multipart(( $customer['id_customer']!=NULL && $customer['id_customer']>0 ?'customer/update/'.$customer['id_customer']:'customer/save'),array('id'=>'cus_create')); ?>
		<!-- Default box -->
		<div class="box">
			<div class="box-header with-border">
			<h3 class="box-title">Profile</h3>
			<div class="box-tools pull-right">
			<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
			<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
			</div>
			</div>
			<div class="box-body">
				<div class="col-md-12">
					<ul class="nav nav-pills nav-stacked col-md-2">
					<li class="active"><a href="#tab_1" data-toggle="pill">Personal</a></li>
					<li><a href="#tab_2" data-toggle="pill">Others</a></li>
					</ul>

					<div class="tab-content col-md-10">
						<div class="tab-pane active" id="tab_1">
							<div class="col-md-12">
							<?php if(isset($customer['id_customer'])) { ?>
							<div class="row">
						    	<div class="col-md-12">
							    	<div class="form-group pull-right">
							    		<label>Active</label>
							    		<input type="checkbox" id="active" class="switch" data-on-text="YES" data-off-text="NO" name="customer[active]" value="1" <?php if($customer['active']==1) { ?> checked="true" <?php } ?>/>
							    	</div>
						    	</div>
						    </div>
							<?php } ?>
							<div class='row'>
						        <div class='col-sm-4'>
						            <div class='form-group'>
						                <label for="customer_firstname"> <a  data-toggle="tooltip" title="Invalid characters 0-9"> First name</a> <span class="error">*</span></label>
						                <?php if($customer['is_cus_synced']!=1) {?>
						                <input class="form-control input_text" id="firstname" name="customer[firstname]" required="true" value="<?php echo set_value('customer[firstname]',$customer['firstname']); ?>" type="text" />
						            <?php }else{?>
						            <input class="form-control input_text" id="firstname" name="customer[firstname]"   required="true" value="<?php echo set_value('customer[firstname]',($customer['firstname']!=null?$customer['firstname'].'. ':null).''.$customer['firstname']); ?>" type="text" readonly="true"/>
						           <?php } ?>

						            </div>
						        </div>
						        <div class='col-sm-4'>
						            <div class='form-group'>
						                <label for="customer_lastname" data-toggle="tooltip" title="Invalid characters 0-9"> Last name</label>
						                <?php if($customer['is_cus_synced']!=1) {?>
						                <input class="form-control input_text" id="lastname" name="customer[lastname]" value="<?php echo set_value('customer[lastname]',$customer['lastname']); ?>" type="text" />
						            <?php }else{?>
						            <input class="form-control input_text" id="lastname" name="customer[lastname]" value="<?php echo set_value('customer[lastname]',$customer['lastname']); ?>" type="text"  readonly="true"/>
						            <?php } ?>
						            </div>
						        </div>
							     <div class='col-sm-4'>
							        <div class='form-group'>
						                <label for="mobile"><a  data-toggle="tooltip" title="Enter Valid mobile number"> Mobile </a><span class="error">*</span></label>
						                <div class="input-group">
						  				<span class="input-group-addon input-sm"><?php echo $this->session->userdata('mob_code')?></span>
								    	<input class="form-control input_number" id="mobile" name="customer[mobile]" required="true" value="<?php echo set_value('customer[mobile]',$customer['mobile']); ?>" type="text" />
										</div>
						            </div>
					            </div>
						    </div>
						    <div class="row">
							     <div class="col-sm-4">
							     	 <div class='form-group'>
							                <label for="email">E-Mail</label>
							                <input class="form-control" id="email" name="customer[email]"  value="<?php echo set_value('customer[email]',$customer['email']); ?>"  type="email" />

							            </div>
							     </div>
							      <div class="col-sm-4">
							    	<div class='form-group'>
						                <label for="phone">Phone</label>
						                <input class="form-control input_number" id="phone" name="customer[phone]" value="<?php echo set_value('customer[phone]',$customer['phone']); ?>"  type="text" />
						            </div>
							     </div>
							     <div class="col-sm-4">
					    			<div class="form-group">
					    			<label for="customer_lastname"><a  data-toggle="tooltip" title="Enter login Password">Login Password </a><span class="error">*</span></label>
					    				<input type="password" class="form-control" id="passwd" name="customer[passwd]" required="true" value="<?php echo set_value('customer[passwd]',$customer['passwd']); ?>" />
					    			</div>
							      </div>

							    </div>
						    <div class="row">
							      <div class="col-sm-4">
							      		<div class='form-group'>
							                <label for="address1">Address1 <span class="error"></span></label>
							                <?php if($customer['is_cus_synced']!=1) {?>
							                <input class="form-control titlecase" id="address1" name="customer[address1]" value="<?php echo set_value('customer[address1]',$customer['address1']); ?>"  type="text" />
							           <?php }else{?>
							           <input class="form-control titlecase" id="address1" name="customer[address1]" value="<?php echo set_value('customer[address1]',$customer['address1']); ?>"  type="text" readonly="true"/>

							           <?php } ?>

							            </div>
							        </div>
							        <div class="col-sm-4">
							            <div class='form-group'>
							                <label for="address2">Address2</label>
							                <?php if($customer['is_cus_synced']!=1) {?>
							                <input class="form-control titlecase" id="address2" name="customer[address2]" value="<?php echo set_value('customer[address2]',$customer['address2']); ?>"   type="text" />
							            <?php }else{?>
							            <input class="form-control titlecase" id="address2" name="customer[address2]" value="<?php echo set_value('customer[address2]',$customer['address2']); ?>"   type="text" readonly="true"/>
							            <?php } ?>

							            </div>
							        </div>
							        <div class="col-sm-4">
										<div class='form-group'>
							                <label for="address3">Address3</label>
							                <?php if($customer['is_cus_synced']!=1) {?>
							                <input class="form-control titlecase" id="address3" name="customer[address3]" value="<?php echo set_value('customer[address3]',$customer['address3']); ?>"   type="text"  />
							           <?php }else{?>
							           <input class="form-control titlecase" id="address3" name="customer[address3]" value="<?php echo set_value('customer[address3]',$customer['address3']); ?>"   type="text" readonly="true" />
							           <?php } ?>

							            </div>
							        </div>
							     </div>
							     <div class="row">
							        <div class="col-sm-4">
							            <div class='form-group'>
							                <label for="country">Country</label>
							                <?php if($customer['is_cus_synced']!=1) {?>
							                 <input  type="hidden" id="countryval" name="countryval" value="<?php echo set_value('countryval',$customer['id_country']); ?>"/>
							                <select class="form-control" id="country" name="customer[country]"  ></select>
							                <?php }else{?>
							                <input  type="hidden" id="countryval" name="countryval" value="<?php echo set_value('countryval',$customer['id_country']); ?>"/>
							                <select class="form-control" id="country" name="customer[country]"  disabled="disabled" ></select>
							                <?php } ?>
							            </div>
							        </div>
							        <div class="col-sm-4">
							            <div class='form-group'>
							                <label for="state">State</label>
							                <?php if($customer['is_cus_synced']!=1) {?>
							                <input  type="hidden" id="stateval" name="stateval" value="<?php echo set_value('stateval',$customer['id_state']); ?>"/>
							                <select class="form-control" id="state" name="customer[state]" ></select>
							                <?php }else{?>
							                <input  type="hidden" id="stateval" name="stateval" value="<?php echo set_value('stateval',$customer['id_state']); ?>"/>
							                <select class="form-control" id="state" name="customer[state]" disabled="disabled"></select>
							                 <?php } ?>
							            </div>
							        </div>
							        <div class="col-sm-4">
							            <div class='form-group'>
							                <label for="city">City</label>
							                <?php if($customer['is_cus_synced']!=1) {?>
							                  <input  type="hidden" id="cityval" name="cityval" value="<?php echo set_value('id_cityval',$customer['id_city']); ?>"/>
							                <select class="form-control" id="city" name="customer[city]" ></select>
							                <?php }else{?>
							                <input  type="hidden" id="cityval" name="cityval" value="<?php echo set_value('id_cityval',$customer['id_city']); ?>"/>
							                <select class="form-control" id="city" name="customer[city]"disabled="disabled" ></select>
							                <?php } ?>
							            </div>
							        </div>
							     </div>
							     <div class="row">
							           <div class="col-sm-4">
							            <div class='form-group'>
							                <label for="city">Village</label>
							                  <input  type="hidden" id="id_village" name="customer[id_village]" value="<?php echo set_value('customer[id_village]',$customer['id_village']); ?>"/>
							                <select class="form-control" id="Village" name="customer[city]" ></select>
							            </div>
							        </div>
							       <div class="col-sm-4">
							            <div class='form-group'>
							                <label for="address2">Post office</label>
							                <input class="form-control titlecase" id="post_office" name="customer[post_office]" value="<?php echo set_value('customer[post_office]',$customer['post_office']); ?>"   type="text" readonly />
							            </div>
							        </div>
							       <div class="col-sm-4">
							            <div class='form-group'>
							                <label for="address2">Taluk</label>
							                <input class="form-control titlecase" id="taluk" name="customer[taluk]" value="<?php echo set_value('customer[taluk]',$customer['taluk']); ?>"   type="text"  readonly/>
							            </div>
							        </div>
							     </div>

							     <div class="row">
							     <div class="col-sm-4">
							      	 <div class='form-group'>
							      <label for="pincode">Pincode</label>
							     <input class="form-control input_number" id="pincode" name="customer[pincode]" value="<?php echo set_value('customer[pincode]',$customer['pincode']); ?>"   type="text" />
							  </div>
							     </div>
								  <?php if($this->session->userdata('branch_settings')==1 && $this->session->userdata('is_branchwise_cus_reg')==1  ){?>
								<div class="col-sm-4">
								<div class="form-group">
								 <label>Filter By Branch <span class="error">* </label>
    								<select required id="branch_select" class="form-control"></select>
    								<input id="id_branch" name="customer[id_branch]"  type="hidden" value="<?php echo set_value('customer[id_branch]',$customer['id_branch']);?>"   />
    							         </div>
										  </div>
								  <?php }?>
								  <div class="col-sm-4">
								<div class="form-group">
												  <label for="" ><a  data-toggle="tooltip" title="Select branch to create Scheme Account"> Select Religion</a></label>
												  <select  id="religion_select" class="form-control">
												  <option> Select Religion Name</option>
												  <option value="1">Hindu</option>
												  <option value="2">Muslim</option>
												  <option value="3">Christian</option>
												  </select>
												  <input type="hidden" name="customer[religion]" id="religion" value="<?php echo set_value('customer[religion]',$customer['religion']);?>">
											  </div>
										  </div>
							      </div>
							      </div>
							      <br/>
							      <br/>
							      <legend></legend>

							      <div class="row">
							      <div class="col-sm-4">
							        <div class='form-group'>
							                 <input id="cus_image" name="cus_img" accept="image/*" type="file" >
											 <img src="<?php echo base_url((isset($customer['cus_img_path'])?$customer['cus_img_path']:'assets/img/default.png')); ?>" class="img-thumbnail" id="cus_img_preview" style="width:175px;height:100%;" alt="Customer image">
											 <input type="hidden" name="customer[customer_img]" value="<?php echo set_value('customer[customer_img]',$customer['cus_img'])?>" />
							      	</div>
							      	<?php if(isset($customer['cus_img_path']) && $customer['id_customer']!=null){?>
							      	  <a class="btn bg-purple btn-sm"  href="<?php echo base_url('index.php/customer/dload/'.$customer['id_customer'].'/customer');?>" ><i class="fa fa-download"></i> Download</a>
							      	  <?php } ?>

							      </div>
							      	<div class='col-sm-8'>
							      	 <div class="row">
						      	 		<div class="col-sm-8">
								      	<div class='form-group'>
								               <label for="gender">Gender <span class="error">*</span></label>
								                    <div class="form-group">
													  <p class="help-block"></p>
														   <input type="radio"  name="customer[gender]" value="0" class="minimal" <?php if($customer['gender']==0){ ?> checked <?php } ?> required/>
								              Male

														  <input type="radio"   name="customer[gender]" value="1" class="minimal" <?php if($customer['gender']==1){ ?> checked <?php } ?>/>
								              Female


														  <input type="radio"   name="customer[gender]" value="3" class="minimal" <?php if($customer['gender']==3){ ?> checked <?php } ?>/>
								              Others

								         	   </div>
								            </div>
								        </div>
							      	 </div>
								      <div class="row">
							      		<div class='col-sm-4'>
								            <div class='form-group'>
								                <label for="date_of_birth">Date of Birth</label>
								                <input class="form-control datemask"  data-date-format="dd-mm-yyyy" id="date_of_birth" name="customer[date_of_birth]" value="<?php echo set_value('customer[date_of_birth]',$customer['date_of_birth']); ?>" type="text" />
								            </div>
								        </div>
								      <div class="col-sm-4">
								      	  <div class='form-group'>
								                <label for="age">Age</label>
								                <input class="form-control " id="age" name="customer[age]" required="true" size="30" readonly="true" type="text" value="<?php echo set_value('customer[age]',$customer['age']); ?>"/>
								          </div>
								      </div>
							      	</div>
								      <div class="row">
								        <div class='col-sm-4'>
								            <div class='form-group'>
								                <label for="date_of_birth ">Wedding Date</label>
								                <input class="form-control datemask"  data-date-format="dd-mm-yyyy" id="date_of_wed" name="customer[date_of_wed]" value="<?php echo set_value('customer[date_of_wed]',$customer['date_of_wed']); ?>" type="text" />
								            </div>
								        </div>
								      </div>
							      </div>

							      </div>

						  </div>

						<div class="tab-pane" id="tab_2">
						    <legend>Nominee Details</legend>
						    <div class="row">
						    	<div class="col-sm-4">
						    		<div class='form-group'>
						                <label for="nominee">Name</label>
						                <input class="form-control input_text" id="nominee_name" name="customer[nominee_name]"  value="<?php echo set_value('customer[nominee_name]',$customer['nominee_name']); ?>"  type="text" />
						            </div>
						    	</div>
						    	<div class="col-sm-4">
						    		<div class='form-group'>
						    			<label for="customer_lastname">Relationship</label>
						                <input class="form-control input_text" id="nominee_relationship" name="customer[nominee_relationship]" value="<?php echo set_value('customer[nominee_relationship]',$customer['nominee_relationship']); ?>"  type="text" />
						    		</div>
						    	</div>
						    	<div class="col-sm-4">
						    		<div class='form-group'>
						                <label for="nominee_mobile">Mobile</label>

			<!-- coded by ARVK -->
						                <div class="input-group">
			              				<span class="input-group-addon input-sm"><?php echo $this->session->userdata('mob_code')?></span>
								    	<input class="form-control input_number" id="nominee_mobile" name="customer[nominee_mobile]" value="<?php echo set_value('customer[nominee_mobile]',$customer['nominee_mobile']); ?>" type="text" />
										</div>
			<!-- /coded by ARVK -->

						        	</div>
						        </div>
						    </div>

						<legend>Others</legend>
						    <div class="row">
						    	<div class="col-sm-4">
								<div class='form-group'>
					                <label for="company_name">Company Name</label>
					                <input class="form-control titlecase" id="company_name" name="customer[company_name]" value="<?php echo set_value('customer[company_name]',$customer['company_name']); ?>"  type="text" />
						         </div>
						    	</div>
						    	<div class="col-sm-4">
								<div class='form-group'>
					                <label for="gst_number">GST Number</label>
					                <input class="form-control titlecase" id="gst_number" name="customer[gst_number]" value="<?php echo set_value('customer[gst_number]',$customer['gst_number']); ?>"  type="text" />
						          </div>
						    	</div>
						    </div>

						    	<legend>Proof</legend>
						    	    <div class="row">
						    	<div class="col-sm-4">
						    		<div class='form-group'>
						                <label for="pan">Pancard No</label>
						                <input class="form-control" id="pan" name="customer[pan]" type="text" value="<?php echo set_value('customer[pan]',$customer['pan']); ?>" />
						            </div>
						    	</div>
						    	<div class="col-sm-4">
						    		<div class='form-group'>
						    			<label for="voterid">Voter ID No</label>
						                <input class="form-control" id="voterid" name="customer[voterid]" type="text" value="<?php echo set_value('customer[voterid]',$customer['voterid']); ?>" />
						    		</div>
						    	</div>
						    	<div class="col-sm-4">
						    		<div class='form-group'>
						                <label for="customer_lastname">Rationcard No</label>
						                <input class="form-control" id="rationcard" name="customer[rationcard]" type="text" value="<?php echo set_value('customer[rationcard]',$customer['rationcard']); ?>" />
						        	</div>
						        </div>
						    </div>

						    <div class="row">
						    	 <div class="col-sm-4">
						    	 	<label for="customer_lastname">Attach Pancard</label>
						        <div class='form-group'>
						            <input id="pan_proof" name="pan_proof" type="file">
						      		<img src="<?php echo base_url((isset($customer['pan_path'])? $customer['pan_path'] : 'assets/img/no_image.png')); ?>" class="img-thumbnail" id="pan_proof_preview" alt="Pan card" width="150" height="75">
						      		<input type="hidden" name="customer[pan_img]" value="<?php echo set_value('customer[pan_img]',$customer['pan_proof'])?>" />
						      	</div>
						      	<?php if(isset($customer['pan_path']) && $customer['id_customer']!=null){?>
						      	  <a class="btn bg-purple btn-sm"  href="<?php echo base_url('index.php/customer/dload/'.$customer['id_customer'].'/pan');?>" ><i class="fa fa-download"></i> Download</a>
						      	  <?php } ?>
						      </div>

						      <div class="col-sm-4">
						    	 	<label for="customer_lastname">Attach Voter ID</label>
						        <div class='form-group'>
						          <input id="voterid_proof" name="voterid_proof" type="file">
						      		<img src="<?php echo base_url((isset($customer['voterid_path'])?$customer['voterid_path']:'assets/img/no_image.png')); ?>" class="img-thumbnail" id="voterid_proof_preview" alt="Voter ID" width="150" height="75">
									<input type="hidden" name="customer[voter_img]" value="<?php echo set_value('customer[voter_img]',$customer['voterid_proof'])?>" />

						      	</div>
						      		<?php if(isset($customer['voterid_path']) && $customer['id_customer']!=null){?>
						      	  <a class="btn bg-purple btn-sm"  href="<?php echo base_url('index.php/customer/dload/'.$customer['id_customer'].'/voterid');?>" ><i class="fa fa-download"></i> Download</a>
						      	  <?php } ?>
						      </div>

						      <div class="col-sm-4">
						    	 	<label for="customer_lastname">Attach Ration Card</label>
						        <div class='form-group'>
						           <input id="rationcard_proof" name="rationcard_proof" type="file">
						      		<img src="<?php echo base_url((isset($customer['rationcard_path'])?$customer['rationcard_path']:'assets/img/no_image.png')); ?>" class="img-thumbnail" id="rationcard_proof_preview" alt="Ration card" width="150" height="75">
						      		 <input type="hidden" name="customer[ration_img]" value="<?php echo set_value('customer[ration_img]',$customer['rationcard_proof'])?>" />
						      	</div>
						      		<?php if(isset($customer['rationcard_path']) && $customer['id_customer']!=null){?>
						      	  <a class="btn bg-purple btn-sm"  href="<?php echo base_url('index.php/customer/dload/'.$customer['id_customer'].'/rationcard');?>" ><i class="fa fa-download"></i> Download</a>
						      	  <?php } ?>
						      </div>

						    </div>
						    <div class="row">

						    	<div class="col-md-12">
						    		<div class='form-group'>
						                <label for="comments">Comments</label>
						               <textarea class="form-control" id="comments" name="customer[comments]"><?php echo set_value('customer[comments]',$customer['comments']); ?></textarea>
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
				<button type="submit" id="save"  class="btn btn-primary">Save</button>
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

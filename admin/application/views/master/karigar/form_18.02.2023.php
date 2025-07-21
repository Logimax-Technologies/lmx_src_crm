<style type="text/css">
.add_wastage {
	cursor: pointer;
	color: blue;
}
.add_karigar_wastage, .remove_karigar_wastage {
	text-align: center;
}
.remove_karigar_wastage {
	margin-left: 5px;
}
.title-add-wastage {
	padding-bottom: 15px;
    padding-top: 15px;
}
.karigar_wastage_buttons {
	padding: 0px;
	text-align: center;
}
.label_wastage_product, .label_wastage_design, .label_sub_design {
	width: 50% !important;
}
.select2 {
	width: 100% !important;
}
</style>
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
						    <li><a href="#tab_2" data-toggle="pill">Wastage</a></li>
						    <li><a href="#tab_3" data-toggle="pill">Stone</a></li>
					</ul>
					
					<div class="tab-content col-md-10">
						<div class="tab-pane active" id="tab_1"> 
							<div class="col-md-12">
							<div class="row" >
								<div class="col-md-offset-1 col-md-10" id='error-msg'></div>
							</div>
							<div class="row"> 
    							<div class="col-md-4"> 
    								      		<div class='form-group'>
    								               <label for="gender">User Type <span class="error">*</span></label>
    								                    <div class="form-group">
    													  <p class="help-block"></p>
    													    <input type="hidden" id="user_type" value="<?php echo $karigar['karigar_type']?>">
    														<input type="radio" class = "user_type" id = "click_label_individual" name="karigar[user_type]" value="0" <?php if($karigar['karigar_type']==0){ ?> checked <?php } ?>   ><label for="click_label_individual">&nbsp;&nbsp;Individual</label>&nbsp;&nbsp;
    														<input type="radio" class = "user_type" id = "click_label_company" name="karigar[user_type]" value="1" <?php if($karigar['karigar_type']==1){ ?> checked <?php } ?> ><label for="click_label_company">&nbsp;&nbsp;Company</label>
    														<input type="radio" class = "user_type" id = "click_label_company" name="karigar[user_type]" value="2" <?php if($karigar['karigar_type']==2){ ?> checked <?php } ?> ><label for="click_label_company">&nbsp;&nbsp;Firm</label>
    								         	   </div> 
    								            </div> 
    								</div> 
    								<div class="col-md-2"> 
    							      		<div class='form-group'>
    							               <label for="gender">User For <span class="error">*</span></label>
    							                    <div class="form-group">
    												  <p class="help-block"></p>
    												  <select class="form-control item-required" id="karigar_for" name="karigar[karigar_for]" required>
    												      <option value="1" <?php if($karigar['karigar_for']==1){ ?> selected <?php } ?> >Jewellery Manufacturer</option>
    												      <option value="2" <?php if($karigar['karigar_for']==2){ ?> selected <?php } ?>>Jewellery Supplier</option>
    												      <option value="5" <?php if($karigar['karigar_for']==5){ ?> selected <?php } ?>>Supplier & Manufacturer</option>
    												      <option value="3" <?php if($karigar['karigar_for']==3){ ?> selected <?php } ?>>Testing / HM Centers</option>
    												      <option value="4" <?php if($karigar['karigar_for']==4){ ?> selected <?php } ?>>Other Inventory Supplier</option>
    												  </select>
    							         	   </div> 
    							            </div> 
    								</div>
									
    								<div class="col-md-2"> 
							      		<div class='form-group'>
							               <label for="gender">Is TCS <span class="error">*</span></label>
							                    <div class="form-group">
												  <p class="help-block"></p>
												    <input type="hidden" id="is_tcs" value="<?php echo $karigar['is_tcs']?>">
												    
													<input type="radio" class = "is_tcs" id = "click_label_tcsyes" name="karigar[is_tcs]" value="1" <?php if($karigar['is_tcs']==1){ ?> checked <?php } ?>   ><label for="click_label_tcsyes">&nbsp;&nbsp;Yes</label>&nbsp;&nbsp;
													<input type="radio" class = "is_tcs" id = "click_label_tcsno" name="karigar[is_tcs]" value="0" <?php if($karigar['is_tcs']==0){ ?> checked <?php } ?> ><label for="click_label_tcsno">&nbsp;&nbsp;No</label>
							         	   </div> 
							            </div> 
    								</div> 
    								<div class="col-md-3"> 
							      		<div class='form-group'>
    						                <label for="first">TCS(%)<span class="error">*</span></label>
    						                <input class="form-control" id="tcs" name="karigar[tcs_tax]" type="number" step="any" value="<?php echo set_value('karigar[tcs_tax]',$karigar['tcs_tax']); ?>" placeholder="TCS (%)" />
    						            </div>
    								</div> 
				      	 		
			                </div>	
		                	<div class='row'>
		                	    <div class="col-md-2"> 
							      		<div class='form-group'>
							               <label for="gender">Is TDS <span class="error">*</span></label>
							                    <div class="form-group">
												  <p class="help-block"></p>
												    <input type="hidden" id="is_tds" value="<?php echo $karigar['is_tds']?>">
												    
													<input type="radio" class = "is_tds" id = "click_label_tdsyes" name="karigar[is_tds]" value="1" <?php if($karigar['is_tds']==1){ ?> checked <?php } ?>   ><label for="click_label_tdsyes">&nbsp;&nbsp;Yes</label>&nbsp;&nbsp;
													<input type="radio" class = "is_tds" id = "click_label_tdsno" name="karigar[is_tds]" value="0" <?php if($karigar['is_tds']==0){ ?> checked <?php } ?> ><label for="click_label_tdsno">&nbsp;&nbsp;No</label>
							         	   </div> 
							            </div> 
    								</div> 
    								<div class="col-md-3"> 
							      		<div class='form-group'>
    						                <label for="first">TDS(%)<span class="error">*</span></label>
    						                <input class="form-control" id="tds_tax" name="karigar[tds_tax]" type="number" step="any" value="<?php echo set_value('karigar[tds_tax]',$karigar['tds_tax']); ?>" placeholder="TDS(%)"/>
    						            </div>
    								</div> 
									<?php if($this->uri->segment(3) == 'edit'){?>
										<div class="col-md-3 kar_calc" <?php echo $karigar['karigar_for']==1 ? 'style="display:block;"': 'style="display:none;"' ?>>
											<div class='form-group'>
												<label for="gender">Calc Type<span></span></label>
												<div class="form-group">
													<p class="help-block"></p>
													<select class="form-control" id="karigar_calc_type" name="karigar[karigar_calc_type]">
														<option value=""></option>
														<option value="1" <?php if($karigar['karigar_calc_type']==1){ ?> selected <?php } ?>>Pure Weight x Rate</option>
														<option value="2" <?php if($karigar['karigar_calc_type']==2){ ?> selected <?php } ?>>Purchase Touch</option>
														<option value="3" <?php if($karigar['karigar_calc_type']==3){ ?> selected <?php } ?>>Pure Weight x Wastage %</option>
													</select>
												</div>
											</div>	
										</div>
									<?php }else{?>
										<div class="col-md-3 kar_calc">
											<div class='form-group'>
												<label for="gender">Calc Type<span></span></label>
												<div class="form-group">
													<p class="help-block"></p>
													<select class="form-control" id="karigar_calc_type" name="karigar[karigar_calc_type]">
														<option value=""></option>
														<option value="1" <?php if($karigar['karigar_calc_type']==1){ ?> selected <?php } ?>>Pure Weight x Rate</option>
														<option value="2" <?php if($karigar['karigar_calc_type']==2){ ?> selected <?php } ?>>Purchase Touch</option>
														<option value="3" <?php if($karigar['karigar_calc_type']==3){ ?> selected <?php } ?>>Pure Weight x Wastage %</option>
													</select>
												</div>
											</div>	
										</div>		
									<?php }?> 
									<div class="col-sm-2">	
    										<div class='form-group'>
    											<label for="scheme_code">Status</label>&nbsp;&nbsp;
    											<input type="checkbox" checked="true" class="alert-status" id="user" name="user" data-on-text="YES" data-off-text="NO" />
    											<input type="hidden" id="user_status" value="1">
    											<p class="help-block"></p>
    										</div>
    									</div>
		                	</div>
							<div class='row'>							       
						        <div class='col-sm-4'>
						            <div class='form-group'>
						                <label for="first">First Name<span class="error">*</span></label>
						                <input class="form-control input_text item-required" id="first_name" name="karigar[first_name]" type="text" value="<?php echo set_value('karigar[firstname]',$karigar['firstname']); ?>" required placeholder="First Name"/>
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
									
						        <div class='col-sm-4 registered_type'>
						            <div class='form-group'>
						                <label for="company">Company Name<span class="error">*</span></label>
						                <input class="form-control input_text" id="company_karigar" name="karigar[company_karigar]" type="text" placeholder="Company Name" value="<?php echo set_value('karigar[company]',$karigar['company']); ?>"/>
						            </div>
						        </div>	
						        <div class="col-sm-4 registered_type">
								    <div class='form-group'>
    					                <label for="gst_number_karigar">GST Number<span class="error">*</span></label>
    					                <input class="form-control" id="gst_number_karigar"  name="karigar[gst_number]"  type="text" placeholder="GST Number" value="<?php echo set_value('karigar[gst_number]',$karigar['gst_number']); ?>" />
						            </div>
						    	</div>
						    	
						    	<div class="col-sm-4">
						    		<div class='form-group'>
						                <label for="pan">PAN Number<span class="error">*</span></label>
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
							                <label for="address1">Address1<span class="error">*</span></label>
							                <input class="form-control titlecase" id="address1" name="karigar[address1]" type="text" placeholder="Address" value="<?php echo set_value('karigar[address1]',$karigar['address1']); ?>" required />
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
							                <label for="country">Country<span class="error">*</span></label>
                          					 <select class="form-control item-required" id="country"  name="karigar[country]" placeholder="Enter Short Name" required></select>
											 <input id="id_country" type="hidden"  ></input>
											 <input id="ed_id_country" type="hidden" value="<?php echo $karigar['id_country'] ?>" ></input>
							            </div>
							        </div>
									 <div class="col-sm-4">
							            <div class='form-group'>
							                <label for="state">State<span class="error">*</span></label>
							                <select class="form-control item-required edit_karigar" id="state" name="karigar[stateval]" required></select>
											<input id="id_state" type="hidden"></input>
											<input id="ed_id_state" type="hidden"  value="<?php echo ($karigar['id_state']); ?>" ></input>
							            </div>
							        </div>
									<div class="col-sm-4">	
							            <div class='form-group'>
							                <label for="city">City<span class="error">*</span></label>
							                <select class="form-control item-required" id="city" name="karigar[cityval]" required></select>
											<input id="id_city" type="hidden"></input>
											<input id="ed_id_city" type="hidden"  value="<?php echo ($karigar['id_city']); ?>"></input>
							            </div>
							        </div>
							    </div>
						   
							     <div class="row">
    								 	<div class="col-sm-4">	
    							            <div class='form-group'>
    							                <label for="pincode">Pincode<span class="error">*</span></label>
    							                <input class="form-control item-required" type="text" id="kar_pincode" name="karigar[pincode]" value="<?php echo set_value('karigar[pincode]',$karigar['pincode']); ?>" required/>
    							            </div>
    							        </div>
    						            <div class="col-md-4">
                    	                     <div class="form-group">
                    	                       <label>Narration</label>
                                                <textarea class="form-control" id="remarks" name="karigar[remarks]" rows="5" cols="500"><?php echo ($karigar['remarks']); ?> </textarea>
                    	                     </div> 
                				        </div>
    								    <!---->
                                        <div class="row">
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
									</div>
									</div>
									
							    </div>
						</div>
						<div class="tab-pane tab-wastage col-md-12" id="tab_2"> 
							<div class='row title-add-wastage'>
								Add wastage ( <span class="add_wastage"><i class="fa fa-plus"></i></span> )
							</div>
							
							<div class="row">
    						 	<div class="table-responsive">
    						 	    <input type="hidden" id="i_increment">
                                      <table id="product_item_list" class="table table-bordered table-striped text-center">
                                            <thead>
                                              <tr>
                                                <th>All</th>
                                                <th>Product</th>
                                                <th>Design</th>
                                                <th>Sub Design</th>
                        						<th width="11%">V.A(%)</th>
                                                <th>M.C Type</th>
                                                <th width="10%">M.C</th>
                                                <th width="10%">Touch</th>
                                                <th>Image</th>
                                                <th>Charges</th>
                                                <th>Action</th>
                                              </tr>
                                         </thead>
                                         <tbody></tbody>
                                      </table>
                                </div>
    						</div>
						</div>
						<div class="tab-pane tab-stones" id="tab_3">
							<div class='row title-add-stone'>
								Add Stone ( <span class="add_stone"><i class="fa fa-plus"></i></span> )
							</div>
							<div class='row headings'>
								<div class='col-md-2'>
									<div class='form-group'>
										<label for="">Stone Type<span class="error">*</span></label>
									</div>
								</div>	
								<div class='col-md-2'>
									<div class='form-group'>
										<label for="">Stone Name<span class="error">*</span></label>
									</div>
								</div>	
								<div class='col-md-2'>
									<div class='form-group'>
										<label for="">UOM<span class="error">*</span></label>
									</div>
								</div>
								<div class='col-md-2'>
									<div class='form-group'>
										<label for="">Calc Type<span class="error">*</span></label>
									</div>
								</div>
								<div class='col-md-2'>
									<div class='form-group'>
										<label for="">Rate<span class="error">*</span></label>
									</div>
								</div>
								<div class="col-md-2">
									<div class='form-group'></div>
								</div>
								</div>				
	
								<?php
								if($karigar['id_karigar'] > 0) { 
									$total_stones = count($karigar_stones);
									$i = 1;
									?>
									<?php foreach($karigar_stones as $key => $kar_stn) { ?>
										<div class="row stones">
											<div class="col-md-2">
												<div class="form-group">
													<select class="form-control stone_type"  name="karigar[stone][stn_type][]" placeholder="Stone Type">
													<?php foreach($stones['stone_type'] as $stnType) { 
														?>
														<option value="<?php echo $stnType['id_stone_type'] ?>" <?php if($stnType['id_stone_type'] == $kar_stn['stone_type'] ) { ?> selected <?php } ?>><?php echo $stnType['stone_type'] ?></option>
													<?php } ?>
													</select>
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<select class="form-control stone_name"  name="karigar[stone][stn_name][]" placeholder="Stone Name">
													<?php foreach($kar_stn['stones'] as $stn) { 
														?>
														<option value="<?php echo $stn['stone_id'] ?>" <?php if($stn['stone_id'] == $kar_stn['uom_id'] ) { ?> selected <?php } ?>><?php echo $stn['stone_name'] ?></option>
													<?php } ?>
													</select>
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<select class="form-control uom_id"  name="karigar[stone][uom_id][]" placeholder="UOM Name">
													<?php foreach($stones['uom'] as $uom) { 
														?>
														<option value="<?php echo $uom['uom_id'] ?>" <?php if($uom['uom_id'] == $kar_stn['uom_id'] ) { ?> selected <?php } ?>><?php echo $uom['uom_name'] ?></option>
													<?php } ?>
													</select>
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<select class="form-control stn_calc_type" name="karigar[stone][stn_calc_type][]" placeholder=" Type"><option value="1" <?php if($kar_stn['stone_cal_type']==1){ ?> selected <?php } ?>>Wt</option><option value="2" <?php if($kar_stn['stone_cal_type']==2){ ?> selected <?php } ?>>Pcs</option></select>
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<input class="form-control stone_rate"  name="karigar[stone][stn_rate][]"  type="number" placeholder="Rate" value="<?php echo $kar_stn['rate_per_gram'] ?>" />
												</div>
											</div>
											<div class="col-md-2 karigar_stone_buttons">
												<div class="form-group">
													<?php if($total_stones == $i) { ?>
														<button type="button" class="btn btn-success add_karigar_stones"><i class="fa fa-plus"></i></button>
													<?php } ?>
														<button type="button" class="btn btn-danger remove_karigar_stones"><i class="fa fa-trash"></i></button>
												</div>
											</div>
											
										</div>
									<?php 
									$i++; 
									} } ?>
							
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
<!--  Image Upload-->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:60%;">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Add Image</h4>
			</div>
			<div class="modal-body">
				<input type="file" name="order_images" id="order_images" multiple="multiple">
			</div></br>
			<div id="uploadArea_p_stn" class="col-md-12"></div>
		  <div class="modal-footer">
			<button type="button" id="update_img" class="btn btn-success">Save</button>
			<button type="button" id="close_stone_details" class="btn btn-warning" data-dismiss="modal">Close</button>
		  </div>
		</div>
	</div>
</div>
<div class="modal fade" id="cus_chargeModal"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:50%;">
		<div class="modal-content">
			<div class="modal-body">
				<div class="row">
					<input type="hidden" id="charge_active_row" value="0">
					<table id="table_charges" class="table table-bordered table-striped text-center">
    					<thead>
        					<tr>
								<th>SNo</th>
            					<th>Charge Name</th>
            					<th>Calc Type</th>
            					<th>Charge</th>
            					<th>Action</th>
        					</tr>
    					</thead> 
    					<tbody></tbody>										
    					<tfoot><tr></tr></tfoot>
					</table>
			    </div>
		    </div>
		  <div class="modal-footer">
			<button type="button" id="update_charge_details" class="btn btn-success">Save</button>
			<button type="button" id="close_charge_details" class="btn btn-warning" data-dismiss="modal">Close</button>
		  </div>
		</div>
	</div>
</div>
<script type="text/javascript">
var cust_id ="<?php echo $customer['id_customer']; ?>";   
var mob_no_len ="<?php echo $this->session->userdata('mob_no_len')?>";   
</script>
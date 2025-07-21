<!-- Content Wrapper. Contains page content -->
	  <style>
    	.remove-btn{
			margin-top: -168px;
		    margin-left: -38px;
		    background-color: #e51712 !important;
		    border: none;
		    color: white !important;
		}
		.sm{
			font-weight: normal;
		}
		}
    </style>
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Inventory
            <small>Stock Issue</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Inventory</a></li>
            <li class="active">Stock Issue</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content order">

          <!-- Default box -->
          <div class="box box-primary">
        
            <div class="box-body">
                	<?php 
                	if($this->session->flashdata('chit_alert'))
                	 {
                		$message = $this->session->flashdata('chit_alert');
                ?>
                       <div  class="alert alert-<?php echo $message['class']; ?> alert-dismissable">
	                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	                    <h4><i class="icon fa fa-check"></i> <?php echo $message['title']; ?>!</h4>
	                    <?php echo $message['message']; ?>
	                  </div>
	            <?php } ?> 
             <!-- form container --> 
	             <!-- form -->
				<form id="stock_issue_form">
				<div class="row">    
				  	<div class="col-sm-12">
					  	<div class="row">
					  	    <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo get_form_secret_key(); ?>">
					  	    <div class="col-md-2">
        	                     <div class="form-group">
        	                       <label>Type</label>
        							<div class="form-group" >  
    										<input type="radio" id="type_issue" name="order[issue_receipt_type]" value="1" checked><label for="type_issue"> Issue </label>
    										&nbsp;&nbsp;&nbsp;
    										<input type="radio" id="type_receipt" name="order[issue_receipt_type]" value="2" ><label for="type_receipt"> Receipt </label>
    									</div>
        	                     </div> 
    				        </div>
    				        
    				        <div class="col-md-2 type_receipt"  style="display:none;">
        						<label>Select Issue No <span class="error">*</span> </label>
						   	 	<div class="form-group">
				 					<select id="select_issue_no" name="order[issue_id]" class="form-control" style="width:100%;"></select>
				 				</div>
    		                </div>
    				        
					  		<div class="col-md-2 branch">
								<label>Issue From <span class="error">*</span> </label>
						   	 	<div class="form-group">
						   	 	<?php if($this->session->userdata('id_branch')==''){?>
				 					<select id="branch_select" class="form-control order_from" required style="width:100%;"></select>
				 					<input type="hidden" name="order[order_from]" id="id_branch" value="1" required="">
				 				<?php }else{?>
				 					<select id="branch_select" class="form-control order_from" disabled style="width:100%;"></select>
				 					<input id="id_branch" name="order[order_from]"  type="hidden" value="<?php echo $this->session->userdata('id_branch'); ?>"/>
				 				<?php }?>
				 				</div>
			 				</div>
			 				
			 				<div class="col-md-2 type_issue">
        	                     <div class="form-group">
        	                       <label>Issue Type<span class="error">*</span></label>
        							<div class="form-group" >  
        							        <select class="form-control" id="issue_type" name="order[issue_type]">
											<input class="form-control" id="issue_to_cus" name="order[issue_to_cus]" type="hidden" value="">
        							        </select>
    									</div>
        	                     </div> 
    				        </div>
    				        
    				        <div class="col-md-2 type_issue">
        						<label class="metal" >Select Metal <span class="error">*</span></label>
    						   	 	<div class="form-group">
    				 					<select id="metal" name="order[id_metal]" class="metal_select" style="width:100%;"></select>
    				 				</div>
        		           </div>

    				        <div class="col-md-2 ">

        	                     <div class="form-group issued_to">

        	                       <label>Issue to <span class="error">*</span></label>

        							<select id="issued_to" class="form-control " name="order[issued_to]" style="width:100%;" tabindex="5">

        							    <option value="1">Customer</option>

        							     <option value="2">Employee</option>

										 <option value="3">karigar</option>

        							</select>

        	                     </div> 

    				        </div>

							<div class="col-sm-3 customer"> 
                            	<label>Customer<span class="error" id="cus_req"> *</span></label>
                            	<div class="form-group" >
                            		<div class="input-group " style="width: 100%;">
                            			<input class="form-control" id="est_cus_name" name="order[cus_name]" type="text"  placeholder="Customer Name / Mobile"  value="<?php echo set_value('order[cus_name]',isset($order['cus_name'])?$order['cus_name']:NULL); ?>" required autocomplete="off"/>
                            			<input class="form-control" id="cus_id" name="order[cus_id]" type="hidden" value="<?php echo set_value('order[cus_id]',$order['cus_id']); ?>"/>
                            			<span class="input-group-btn">
                            				<button type="button" id="add_new_customer" class="btn btn-success"><i class="fa fa-plus"></i></button>
                            			</span>
                            			<span class="input-group-btn">
                            				<button type="button" id="edit_customer" class="btn btn-primary"><i class="fa fa-edit"></i></button>
                            			</span>
                            			<input class="form-control" id="goldrate_22ct" name="metal_rates[goldrate_22ct]" type="hidden" value=""/>
                            			<input class="form-control" id="silverrate_1gm" name="metal_rates[silverrate_1gm]" type="hidden" value=""/>
                            			<span id="customerAlert"></span> 
                            		</div>
                            	</div> 
                            	<p id=cus_info></p> 
                            </div>

							<div class="col-md-2 employee" style="display:none">
        						<label class="emp_select" >Select Employee <span class="error">*</span></label>
						   	 	<div class="form-group">
				 					<select id="issue_employee" name="order[id_employee]" class="emp_select" style="width:100%;"></select>
				 				</div>
    		                </div>

							<div class="col-md-2 karigar" style="display:none">
        						<label class="kar_select" >Select karigar <span class="error">*</span></label>
						   	 	<div class="form-group">
				 					<select id="karigar" name="order[id_karigar]" class="kar_select" style="width:100%;"></select>
				 				</div>
    		                </div>
    		                
			 			</div>	

			 			</div>	
    				        
						 </div>
			 			<div class="row issueothers type_issue" >
			 			    <div class="col-sm-12">
			 			        <div class="row" class="issue_tag_items">
                					<div class="col-md-12">
                						<legend><i>Issue Items</i>
                						<p class="help-block"></p></legend>
                						
                						<div class="row">
                						    <div class="col-sm-2">
            	    						    <div class="box-tools pull-left"> 
            	        						    <div class="form-group" > 
            	        					 			<div class="input-group" > 
            	        								    <input type="text" id="issue_tag_code" class="form-control" placeholder="Tag Scan Code">
            	        									<span class="input-group-btn">
            	        									    <button type="button" id="issue_tag_search" class="btn btn-default btn-flat"><i class="fa fa-search"></i></button>
            	        				                    </span>
            	        								</div>
            	        								<p id="searchEstiAlert" class="error" align="left"></p>
            	        							</div>
            	    							</div>
                							</div>
                							
                							<div class="col-sm-2">
                                              <div class="input-group"> 
                                                 <input type="text" id="issue_old_tag_code" class="form-control" placeholder="OLD Tag Scan">
                                                  <span class="input-group-btn">
                                                    <button type="button" id="issue_old_tag_search" class="btn btn-default btn-flat"><i class="fa fa-search"></i></button>
	                                               </span>
                                               </div>
                                            </div>

											<div class="col-sm-2">
                                              <div class="input-group"> 
                                                 <input type="text" id="rate_per_gram" class="form-control" placeholder="Rate per Gram">
                                               </div>
                                            </div>

                                            
                						</div>
                						
                						<input  type="hidden" value="0" id="sto_i_increment" />	
                					    <table id="tagissue_item_detail" class="table table-bordered table-striped">
                							<thead>
                						          <tr>	
												  <th width="10%;">Tag Code</th>
                    						          <th width="10%;">Category</th>
                    						          <th width="10%;">Purity</th>	
                    						          <th width="10%;">Product</th>	
                    						          <th width="10%;">Design</th>	
                    						          <th width="10%;">Sub Design</th>
                    						          <th width="10%;">Pcs</th> 
                    						          <th width="10%;">GWgt</th> 
                    						          <th width="10%;">NWgt</th> 	
													  <th width="10%;">Rate</th> 	
													  <th width="10%;">Stone</th> 	
													  <th width="10%;">Other Metal</th> 	
                    						          <th width="10%;" style="text-align:right;">Taxable Amount</th> 	
                    						          <th width="10%;" style="text-align:right;">Tax</th> 	
                    						          <th width="10%;" style="text-align:right;">Tax Amount</th> 	
                    						          <th width="10%;" style="text-align:right;">Net Amount</th> 	
                    						          <th width="10%;">Action</th> 		
                						          </tr>
                					         </thead>
                					         <tbody> 
                					         </tbody>
                					         <tfoot>
                					             <tr  style="font-weight:bold;"><td colspan="6" style="text-align: center;">Total</td>
												 <td class="total_pieces"></td>
												 <td class="total_gross_wt"></td>
												 <td class="total_nwt"></td>
												 <td ></td>
												 <td class="total_stone_amount" style="text-align:right;"></td>
												 <td class="total_othermetal_amount" style="text-align:right;"></td>
												 <td class="total_taxable_amount" style="text-align:right;"></td>
												 <td style="text-align:right;"></td>
												 <td class="total_tax_amount" style="text-align:right;"></td>
												 <td class="total_amount" style="text-align:right;"></td>
												 <td></td></tr>
                					         </tfoot>
                						</table>
                					</div> 
                				</div>
			 			    </div>
			 		    </div>
			 		    
			 		    
			 		    <div class="row type_receipt" style="display:none;">
			 			    <div class="col-sm-12">
			 			        <div class="row" class="issue_tag_items">
                					<div class="col-md-12">
                						<legend><i>Receipt Items</i>
                						<p class="help-block"></p></legend>
                						
									
										<div class="row">
                						    <div class="col-sm-2">
            	    						    <div class="box-tools pull-left"> 
            	        						    <div class="form-group" > 
            	        					 			<div class="input-group" > 
            	        								    <input type="text" id="receipt_tag_code" class="form-control" placeholder="Tag Scan Code">
            	        									<span class="input-group-btn">
            	        									    <button type="button" id="receipt_tag_search" class="btn btn-default btn-flat"><i class="fa fa-search"></i></button>
            	        				                    </span>
            	        								</div>
            	        								<p id="searchEstiAlert" class="error" align="left"></p>
            	        							</div>
            	    							</div>
                							</div>
                							
                							<div class="col-sm-2">
                                              <div class="input-group"> 
                                                 <input type="text" id="receipt_old_tag_code" class="form-control" placeholder="OLD Tag Scan">
                                                  <span class="input-group-btn">
                                                    <button type="button" id="receipt_old_tag_search" class="btn btn-default btn-flat"><i class="fa fa-search"></i></button>
	                                               </span>
                                               </div>
                                            </div>

										
                						</div>

                						<input  type="hidden" value="0" id="sto_i_increment" />	
                					    <table id="tag_receipt_item_detail" class="table table-bordered table-striped">
                							<thead>
                						          <tr>	
                						            
													  <th width="10%;">Tag Code</th>
                    						          <th width="10%;">Category</th>
                    						          <th width="10%;">Purity</th>	
                    						          <th width="10%;">Product</th>	
                    						          <th width="10%;">Design</th>	
                    						          <th width="10%;">Sub Design</th>
                    						          <th width="10%;">Pcs</th> 
                    						          <th width="10%;">GWgt</th> 
                    						          <th width="10%;">NWgt</th> 	
													  <th width="10%;">Rate</th> 	
													  <th width="10%;">Stone</th> 	
													  <th width="10%;">Other Metal</th> 	
                    						          <th width="10%;" style="text-align:right;">Taxable Amount</th> 	
                    						          <th width="10%;" style="text-align:right;">Tax</th> 	
                    						          <th width="10%;" style="text-align:right;">Tax Amount</th> 	
                    						          <th width="10%;" style="text-align:right;">Net Amount</th> 	
                    						          <th width="10%;">Action</th> 		
                						          </tr>
                					         </thead>
                					         <tbody> 
                					         </tbody>
                					         <tfoot>
                					             <tr  style="font-weight:bold;"><td colspan="6" style="text-align: center;">Total</td>
												 <td class="receipt_total_pieces"></td>
												 <td class="receipt_total_gross_wt"></td>
												 <td class="receipt_total_nwt"></td>
												 <td ></td>
												 <td class="receipt_total_stone_amount" style="text-align:right;"></td>
												 <td class="receipt_total_othermetal_amount" style="text-align:right;"></td>
												 <td class="receipt_total_taxable_amount" style="text-align:right;"></td>
												 <td style="receipt_text-align:right;"></td>
												 <td class="receipt_total_tax_amount" style="text-align:right;"></td>
												 <td class="receipt_total_amount" style="text-align:right;"></td>
												 <td></td></tr>
												</tr>
                					         </tfoot>
                						</table>
                					</div> 
                				</div>
			 			    </div>
			 		    </div>
			 			
				    </div>  
				</div>
				
			    <div class="row type_issue">
				    <div class="col-md-12">
				        <div class="col-sm-10">
				 			<div class="form-group">
					 			<div class="input-group">
					 			    <label>Remarks</label>
					 				<textarea class="form-control" name="order[remark]" id="remark" rows="5" cols="100"> </textarea>
								</div>
							</div>
				 		</div>
				    </div>
				</div>
					 <p class="hepl-block"></p>  
				<!--End of row-->  
			     <div class="row">
				   <div class="box box-default"><br/>
					  <div class="col-xs-offset-5">
						<button type="button"  class="btn btn-primary" id="stock_issue_submit" >Save</button> 
						<button type="button" class="btn btn-default btn-cancel">Cancel</button>
						
					  </div> <br/>
					</div>
				  </div> 
	            </div> <!-- box-body--> 
	            <div class="overlay" style="display:none">
				  <i class="fa fa-refresh fa-spin"></i>
				</div>
	            </div>  <!-- Default box--> 
	         </form>  
	             <!-- /form -->
             </section>
  		</div>
  		
  
 <div class="modal fade" id="confirm-add"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

				<h4 class="modal-title" id="myModalLabel">Add Customer</h4>

			</div>

			<div class="modal-body">

				<div class="row">

					<div class="form-group">

					   <label for="cus_first_name" class="col-md-3 col-md-offset-1 ">First Name<span class="error">*</span></label>

					   <div class="input-group">

							<span class="input-group-addon">

								<select name="title" id="title">

									<option value="none"  disabled="" hidden=""></option>

									<option value="Mr" selected>Mr</option>

									<option value="Ms">Ms</option>

									<option value="Mrs">Mrs</option>

									<option value="Dr">Dr</option>

									<option value="Prof">Prof</option>

								</select>

							</span>

							<input type="text" class="form-control" style="width:65%;" id="cus_first_name" name="cus[first_name]" placeholder="Enter customer first name"  required="true">						 

						</div>

					</div>

				</div>

				<div class="row">

                	<div class="form-group">

                		<label for="cus_gender" class="col-md-3 col-md-offset-1 ">Gender<span class="error">*</span></label>

                		<div class="col-md-6">

                			<input type="radio"  name="customer[gender]" value="0" class="minimal" <?php if($customer['gender']==0){ ?> checked <?php } ?> required/>Male

                			<input type="radio"   name="customer[gender]" value="1" class="minimal" <?php if($customer['gender']==1){ ?> checked <?php } ?>/>Female

                			<input type="radio"   name="customer[gender]" value="3" class="minimal" <?php if($customer['gender']==3){ ?> checked <?php } ?>/>Others 

                

                			<p class="help-block cus_gender error"></p>

                		</div>

                	</div>

                </div>

				<div class="row">   

					<div class="form-group">

					   <label for="cus_mobile" class="col-md-3 col-md-offset-1 ">Mobile<span class="error">*</span></label>

					   <div class="col-md-6">

							<input type="number" class="form-control" id="cus_mobile" name="cus[mobile]" placeholder="Enter customer mobile"> 

							<p class="help-block cus_mobile error"></p>

					   </div>

					</div>

				</div>

				

				<div class="row">

					<div class="form-group">

					   <label for="cus_email" class="col-md-3 col-md-offset-1 ">Email</label>

					   <div class="col-md-6">

							<input type="text" class="form-control" id="cus_email" name="cus[cus_email]" placeholder="Enter Email ID"> 



							<p class="help-block cus_email error"></p>

					   </div>

					</div>

				</div>

				

				<div class="row">   

					<div class="form-group">

					   <label for="" class="col-md-3 col-md-offset-1 ">Select Country<span class="error">*</span></label>

					   <div class="col-md-6">

						 <select class="form-control" id="country" style="width:100%;"></select>

						 <input type="hidden" name="cus[id_country]" id="id_country"> 

					   </div>

					</div>

				</div></br>

				

			    <div class="row">   

					<div class="form-group">

					   <label for="" class="col-md-3 col-md-offset-1 ">Select State<span class="error">*</span></label>

					   <div class="col-md-6">

						 <select class="form-control" id="state" style="width:100%;"></select>

						  <input type="hidden" name="cus[id_state]" id="id_state">

					   </div>

					</div>

				</div></br>

				

				 <div class="row">   

					<div class="form-group">

					   <label for="" class="col-md-3 col-md-offset-1 ">Select City<span class="error">*</span></label>

					   <div class="col-md-6">

						 <select class="form-control" id="city"  style="width:100%;"></select>

						  <input type="hidden" name="cus[id_city]" id="id_city">

					   </div>

					   

					</div>

				</div></br>

			

				<div class="row">

					<div class="form-group">

					    <label for="address1" class="col-md-3 col-md-offset-1 ">Address1<span class="error">*</span></label>

						   <div class="col-md-6">

								<input class="form-control" id="address1" name="customer[address1]" value=""  type="text" placeholder="Enter Address Here 1" required />

								<p class="help-block address1 error"></p>

							</div>

					</div>

				</div></br>

				<div class="row">

					<div class="form-group">

					    <label for="address2" class="col-md-3 col-md-offset-1">Address2</label>

						   <div class="col-md-6">

								<input class="form-control" id="address2" name="customer[address2]" placeholder="Enter Address Here 2" value=""  type="text" />

							</div>

					</div>

				</div></br>

				<div class="row">

					<div class="form-group">

					    <label for="address3" class="col-md-3 col-md-offset-1">Address3</label>

						   <div class="col-md-6">

								<input class="form-control titlecase" id="address3" name="customer[address3]" value=""  type="text" placeholder="Enter Address Here 3" />

							</div>

					</div>

				</div></br>

				

				<div class="row">   

                	<div class="form-group">

                		<label for="" class="col-md-3 col-md-offset-1 ">Select Area<span class="error"></span></label>

                		<div class="col-md-6">

                			<select class="form-control" id="sel_village" style="width:100%;"></select>

                			<input type="hidden" id="id_village">

                		</div>

                	</div>

                </div></br>



				<div class="row">

					<div class="form-group">

					    <label for="pincode" class="col-md-3 col-md-offset-1">Pin Code<span class="error">*</span></label>

						   <div class="col-md-6">

								<input class="form-control titlecase" id="pin_code_add" type="text" placeholder="Enter Pincode" onkeypress='return  (event.charCode >= 48 && event.charCode <= 57)' required />

								<p class="help-block pincode error"></p>

							</div>

					</div>

				</div></br>

				

				<div class="row">   

                	<div class="form-group">

                		<label for="" class="col-md-3 col-md-offset-1 ">Select Profession</label>

                		<div class="col-md-6">

                			<select class="form-control" id="profession" style="width:100%;"></select>

                			<input type="hidden" name="cus[profession]" id="professionval"> 

                		</div>

                	</div>

                </div></br>

                

                <div class="row">

                	<div class="form-group">

                		<label for="pincode" class="col-md-3 col-md-offset-1">Date of Birth</label>

                			<div class="col-md-6">

                			<input class="form-control ed_date_of_birth"  id="date_of_birth" name="customer[date_of_birth]" value="<?php echo set_value('customer[date_of_birth]',$customer['date_of_birth']); ?>" type="text" />

                				<p class="help-block pincode error"></p>

                			</div>

                	</div>

                </div></br>

                

                

                <div class="row">

                	<div class="form-group">

                		<label for="pincode" class="col-md-3 col-md-offset-1">Wedding Date</label>

                			<div class="col-md-6">

                			<input class="form-control ed_date_of_wed"  id="date_of_wed" name="customer[date_of_wed]" value="<?php echo set_value('customer[date_of_wed]',$customer['date_of_wed']); ?>" type="text" />

                				<p class="help-block pincode error"></p>

                			</div>

                	</div>

                </div></br>



				

				<div class="row" >   

					<div class="form-group">

					   <label for="" class="col-md-3 col-md-offset-1 ">Upload Image<span class="error"></span></label>

					    <div class="col-md-6"> 

					        <input id="cus_image" name="cus_img" accept="image/*" type="file" >

							<p class="help-block cus_mobile"></p>

							

							<input type="button" value="Take Snapshot"  class="btn btn-warning" id="snap_shots"><br>



							<div class="row">

								<div class="col-md-12">

									<div class="col-md-3"></div>

									<div class="col-md-6" id="my_camera"></div>

									<input type="hidden" name="image" class="image-tag">

									<div class="col-md-3"></div>

								</div>

							</div>



							<img src="<?php echo base_url('assets/img/default.png')?>" class="img-thumbnail" id="cus_img_preview" style="width:175px;height:100%;" alt="Customer image"> 

							<input type="hidden" id="customer_img" name="customer[customer_img]" value="<?php echo set_value('customer[customer_img]',$customer['cus_img'])?>" />  				

					    </div>

					   

						



					</div>

				</div>

				

			<!--	<div class="row cus_type">   

					<div class="form-group">

					   <label for="" class="col-md-3 col-md-offset-1 ">Customer Type<span class="error"></span></label>

					   <div class="col-md-6">

						 <input type="radio" id="cus_type1"  name="cus[cus_type]" value="1" class="minimal" checked/> Individual

						 <input type="radio" id="cus_type2"  name="cus[cus_type]" value="2" class="minimal" /> Business

					   </div>

					</div>

				</div></br>-->

				<div class="row gst" style="display:none">   

					<div class="form-group">

					   <label for="" class="col-md-3 col-md-offset-1 ">GST No<span class="error">*</span></label>

					   <div class="col-md-6"> 

							<input type="text" class="form-control" id="gst_no" name="cus[gst_no]" placeholder="Enter GST No"> 

							<p class="help-block cus_mobile"></p>

					   </div>

					</div>

				</div>

			</div>

		  <div class="modal-footer">

		     <input type="hidden" name="cus[id_customer]" id="id_customer" value="">

			 <a href="#" id="add_newcutomer" class="btn btn-success">Add</a>

			<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>

		  </div>

		</div>

	</div>

</div>







<!-- / modal -->        

<!--Customer Update-->

<div class="modal fade" id="confirm-edit"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

				<h4 class="modal-title" id="myModalLabel">Edit Customer</h4>

			</div>

			<div class="modal-body">

				<div class="row">

					<div class="form-group">

					   <label for="cus_first_name" class="col-md-3 col-md-offset-1 ">First Name<span class="error">*</span></label>

					   <div class="input-group">

							<span class="input-group-addon">

								<select name="title" id="ed_title">

									<option value="none" selected="" disabled="" hidden=""></option>

									<option value="Mr">Mr</option>

									<option value="Ms">Ms</option>

									<option value="Mrs">Mrs</option>

									<option value="Dr">Dr</option>

									<option value="Prof">Prof</option>

								</select>

							</span>

							<input type="text" class="form-control" style="width:65%;" id="ed_cus_first_name" name="cus[first_name]" placeholder="Enter customer first name"  required="true">						 

						</div>

					</div>

				</div> 

				<div class="row">

                	<div class="form-group">

                		<label for="cus_gender" class="col-md-3 col-md-offset-1 ">Gender<span class="error">*</span></label>

                		<div class="col-md-6">

                			<input type="radio"  name="customer[gender]" value="0" class="minimal" id="gender0" <?php if($customer['gender']==0){ ?> checked <?php } ?> required/>Male

                			<input type="radio"   name="customer[gender]" value="1" class="minimal" id="gender1" <?php if($customer['gender']==1){ ?> checked <?php } ?>/>Female

                			<input type="radio"   name="customer[gender]" value="3" class="minimal" id="gender2" <?php if($customer['gender']==3){ ?> checked <?php } ?>/>Others 

                

                			<p class="help-block cus_gender error"></p>

                		</div>

                	</div>

                </div>

				<div class="row">   

					<div class="form-group">

					   <label for="cus_mobile" class="col-md-3 col-md-offset-1 ">Mobile<span class="error">*</span></label>

					   <div class="col-md-6">

							<input type="text" class="form-control" id="ed_cus_mobile" name="cus[mobile]" placeholder="Enter customer mobile" readonly> 

							<p class="help-block cus_mobile error"></p>

					   </div>

					</div>

				</div>

				

				<div class="row">

					<div class="form-group">

					   <label for="cus_email" class="col-md-3 col-md-offset-1 ">Email</label>

					   <div class="col-md-6">

							<input type="text" class="form-control" id="ed_cus_email" name="cus[cus_email]" placeholder="Enter Email ID"> 



							<p class="help-block cus_email error"></p>

					   </div>

					</div>

				</div>

				

				<div class="row">   

					<div class="form-group">

					   <label for="" class="col-md-3 col-md-offset-1 ">Select Country<span class="error">*</span></label>

					   <div class="col-md-6">

						 <select class="form-control" id="ed_cus_country" style="width:100%;"></select>

						 <input type="hidden" name="cus[id_country]" id="ed_id_country"> 

					   </div>

					</div>

				</div></br>

				

			    <div class="row">   

					<div class="form-group">

					   <label for="" class="col-md-3 col-md-offset-1 ">Select State<span class="error">*</span></label>

					   <div class="col-md-6">

						 <select class="form-control" id="ed_cus_state" style="width:100%;"></select>

						  <input type="hidden" name="cus[id_state]" id="ed_id_state">

					   </div>

					</div>

				</div></br>

				

				 <div class="row">   

					<div class="form-group">

					   <label for="" class="col-md-3 col-md-offset-1 ">Select City<span class="error"></span></label>

					   <div class="col-md-6">

						 <select class="form-control" id="ed_cus_city"  style="width:100%;"></select>

						  <input type="hidden" name="cus[id_city]" id="ed_id_city">

					   </div>

					   

					</div>

				</div></br>

			

				<div class="row">

					<div class="form-group">

					    <label for="address1" class="col-md-3 col-md-offset-1 ">Address1<span class="error"></span></label>

						   <div class="col-md-6">

								<input class="form-control" id="ed_cus_address1" name="customer[address1]" value=""  type="text" placeholder="Enter Address Here 1" required />

								<p class="help-block address1 error"></p>

							</div>

					</div>

				</div></br>

				<div class="row">

					<div class="form-group">

					    <label for="address2" class="col-md-3 col-md-offset-1">Address2</label>

						   <div class="col-md-6">

								<input class="form-control" id="ed_cus_address2" name="customer[address2]" placeholder="Enter Address Here 2" value=""  type="text" />

							</div>

					</div>

				</div></br>

				<div class="row">

					<div class="form-group">

					    <label for="address3" class="col-md-3 col-md-offset-1">Address3</label>

						   <div class="col-md-6">

								<input class="form-control titlecase" id="ed_cus_address3" name="customer[address3]" value=""  type="text" placeholder="Enter Address Here 3" />

							</div>

					</div>

				</div></br>

				

				<div class="row">   

                	<div class="form-group">

                		<label for="" class="col-md-3 col-md-offset-1 ">Select Area<span class="error"></span></label>

                		<div class="col-md-6">

                			<select class="form-control" id="ed_sel_village" style="width:100%;"></select>

                			<input type="hidden" id="ed_id_village">

                		</div>

                	</div>

                </div></br>



				<div class="row">

					<div class="form-group">

					    <label for="pincode" class="col-md-3 col-md-offset-1">Pin Code<span class="error">*</span></label>

						   <div class="col-md-6">

								<input class="form-control titlecase" id="ed_cus_pin_code_add" type="text" placeholder="Enter Pincode" onkeypress='return  (event.charCode >= 48 && event.charCode <= 57)' required />

								<p class="help-block pincode error"></p>

							</div>

					</div>

				</div></br>

				

				<div class="row">   

                	<div class="form-group">

                		<label for="" class="col-md-3 col-md-offset-1 ">Select Profession<span class="error">*</span></label>

                		<div class="col-md-6">

                			<select class="form-control" id="ed_profession" style="width:100%;"></select>

                			<input type="hidden" name="cus[profession]" id="ed_professionval"> 

                		</div>

                	</div>

                </div></br>

                

                <div class="row">

                	<div class="form-group">

                		<label for="pincode" class="col-md-3 col-md-offset-1">Date of Birth</label>

                			<div class="col-md-6">

                			<input class="form-control ed_date_of_birth"  id="ed_date_of_birth" name="customer[date_of_birth]" value="<?php echo set_value('customer[date_of_birth]',$customer['date_of_birth']); ?>" type="text" />

                				<p class="help-block pincode error"></p>

                			</div>

                	</div>

                </div></br>

                <div class="row">

                	<div class="form-group">

                		<label for="pincode" class="col-md-3 col-md-offset-1">Wedding Date</label>

                			<div class="col-md-6">

                			<input class="form-control ed_date_of_wed"  id="ed_date_of_wed" name="customer[date_of_wed]" value="<?php echo set_value('customer[date_of_wed]',$customer['date_of_wed']); ?>" type="text" />

                				<p class="help-block pincode error"></p>

                			</div>

                	</div>

                </div></br>

				

				<div class="row" >   

					<div class="form-group">

					   	<label for="" class="col-md-3 col-md-offset-1 ">Upload Image<span class="error"></span></label>

						

					   <div class="col-md-6"> 

					        <input id="ed_cus_image" name="cus_img" accept="image/*" type="file" >

							<p class="help-block cus_mobile"></p>



							<input type="button" value="Take Snapshot"  class="btn btn-warning" id="ed_snap_shots"><br>



							<div class="row">

								<div class="col-md-12">

									<div class="col-md-3"></div>

									<div class="col-md-6" id="ed_my_camera"></div>

									<input type="hidden" name="image" class="ed_image-tag">

									<div class="col-md-3"></div>

								</div>

							</div>

													

							<img src="<?php echo base_url('assets/img/default.png')?>" class="img-thumbnail" id="ed_cus_img_preview" style="width:175px;height:100%;" alt="Customer image"> 



							

							<input type="hidden" id="ed_customer_img" name="customer[ed_customer_img]" value="<?php echo set_value('customer[customer_img]',$customer['cus_img'])?>" />  

					   </div>



					</div>

				</div>	

				

				<!--<div class="row cus_type">   

					<div class="form-group">

					   <label for="" class="col-md-3 col-md-offset-1 ">Customer Type<span class="error"></span></label>

					   <div class="col-md-6">

						 <input type="radio" id="ed_cus_type1"  name="ed_cus[cus_type]" value="1" class="minimal" /> Individual

						 <input type="radio" id="ed_cus_type2"  name="ed_cus[cus_type]" value="2" class="minimal" /> Business

					   </div>

					</div>

				</div></br>-->

				<div class="row gst">   

					<div class="form-group">

					   <label for="" class="col-md-3 col-md-offset-1 ">GST No<span class="error"></span></label>

					   <div class="col-md-6"> 

							<input type="text" class="form-control" id="ed_gst_no" name="cus[gst_no]" placeholder="Enter GST No"> 

							<p class="help-block cus_mobile"></p>

					   </div>

					</div>

				</div>

			</div>

		  <div class="modal-footer">

		     <input type="hidden" name="cus[id_customer]" id="id_customer" value="">

			 <a href="#" id="update_cutomer" class="btn btn-success">Add</a>

			<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>

		  </div>

		</div>

	</div>

</div>

<!--Customer Update-->

<!-- / modal -->        

<!--Customer Update-->
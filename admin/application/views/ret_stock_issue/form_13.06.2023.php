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
    				        
					  		<div class="col-md-2 type_issue">
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
        							        </select>
    									</div>
        	                     </div> 
    				        </div>
    				        
    				        
    				        
    				        <div class="col-md-2 type_issue">
        						<label>Select Employee <span class="error">*</span> </label>
						   	 	<div class="form-group">
				 					<select id="issue_employee" name="order[id_employee]" class="form-control" style="width:100%;"></select>
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
                    						          <th width="10%;">Action</th> 			
                						          </tr>
                					         </thead>
                					         <tbody> 
                					         </tbody>
                					         <tfoot>
                					             <tr  style="font-weight:bold;"><td colspan="6" style="text-align: center;">Total</td><td class="total_pieces"></td><td class="total_gross_wt"></td><td></td><td></td></tr>
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
                    						          <th width="10%;">Action</th> 			
                						          </tr>
                					         </thead>
                					         <tbody> 
                					         </tbody>
                					         <tfoot>
                					             <tr  style="font-weight:bold;"><td colspan="6" style="text-align: center;">Total</td><td class="receipt_total_pieces"></td><td class="receipt_total_gross_wt"></td><td></td><td></td></tr>
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
			
				<p class="help-block"></p>
				
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
</div>

<div class="modal fade" id="order_des" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  	<div class="modal-dialog">
    	<div class="modal-content">
      		<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        		<h4 class="modal-title" id="myModalLabel">Add Description</h4>
      		</div>
      		
		    <div class="modal-body">
			
				<div class="row">			
           
			    	<div class="col-md-10 col-md-offset-1">
              <label for="user_lastname">Item Description</label>
						<div class='form-group'>
			               	<textarea  cols="70"  id="description" name="description" ><?php echo set_value('sch[description]',(isset($sch['description'])?$sch['description']:"")); ?></textarea>
			        	</div>
			    	</div>
			    </div> 
			</div>
			
			<div class="modal-footer">
				<a href="#" class="btn btn-success add_order_desc">Add</a>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- / modal -->



<!--  Image Upload-->
<div class="modal fade" id="BillModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:60%;">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Billing Details</h4>
			</div>

			<div class="modal-body">
			
				<div class="row" id="bill_items_for_return" style="display:none;">
					<div class="box-body">
						<div class="table-responsive">
							<table id="bill_items_tbl_for_return" class="table table-bordered table-striped text-center">
								<thead>
									<tr>
										<th>Select</th>
										<th>Product</th>
										<th>Design</th>
										<th>Pcs</th>    
										<th>Purity</th>   
										<th>Size</th> 
										<th>G.Wt</th>   
										<th>L.Wt</th>   
										<th>N.Wt</th>   
										<th>Amount</th>
									</tr>
								</thead> 
								<tbody>
								</tbody>
								<tfoot>
									<tr></tr>
								</tfoot>
							</table>
							<p></p>
						</div>
					</div> 
				</div> 
			</div></br>
			<div id="uploadArea_p_stn" class="col-md-12"></div>
		  <div class="modal-footer">
			<button type="button" id="update_bill_details" class="btn btn-success">Save</button>
			<button type="button" id="close_stone_details" class="btn btn-warning" data-dismiss="modal">Close</button>
		  </div>
		</div>
	</div>
</div>
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
					   <div class="col-md-6">
							<input type="text" class="form-control" id="cus_first_name" name="cus[first_name]" placeholder="Enter customer first name" required="true"> 

							<p class="help-block cus_first_name error"></p>
					   </div>
					</div>
				</div> 
				<div class="row">   
					<div class="form-group">
					   <label for="cus_mobile" class="col-md-3 col-md-offset-1 ">Mobile<span class="error">*</span></label>
					   <div class="col-md-6">
							<input type="text" class="form-control" id="cus_mobile" name="cus[mobile]" placeholder="Enter customer mobile"> 
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
			
			<!--	<div class="row">   
					<div class="form-group">
					   <label for="" class="col-md-3 col-md-offset-1 ">Select Area</label>
					   <div class="col-md-6">
						 <select class="form-control" id="sel_village" ></select>
							<input type="hidden" name="cus[id_village]" id="id_village" name="">
							<p class="help-block sel_village error"></p>
					   </div>
					</div>
				</div></br>-->
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
					    <label for="pincode" class="col-md-3 col-md-offset-1">Pin Code<span class="error"></span></label>
						   <div class="col-md-6">
								<input class="form-control titlecase" id="pin_code_add" type="text" placeholder="Enter Pincode" onkeypress='return  (event.charCode >= 48 && event.charCode <= 57)' required />
								<p class="help-block pincode error"></p>
							</div>
					</div>
				</div></br>
				<div class="row">   
					<div class="form-group">
					   <label for="" class="col-md-3 col-md-offset-1 ">Customer Type<span class="error"></span></label>
					   <div class="col-md-6">
						 <input type="radio" id="cus_type"  name="cus[cus_type]" value="1" class="minimal" checked/> Individual
						 <input type="radio" id="cus_type"  name="cus[cus_type]" value="2" class="minimal" /> Business
					   </div>
					</div>
				</div></br>
				<div class="row">   
					<div class="form-group">
					   <label for="" class="col-md-3 col-md-offset-1 ">GST No<span class="error"></span></label>
					   <div class="col-md-6">
							<input type="text" class="form-control" id="gst_no" name="cus[gst_no]" placeholder="Enter GST No"> 
							<p class="help-block cus_mobile"></p>
					   </div>
					</div>
				</div>
			</div>
		  <div class="modal-footer">
		     <input type="hidden" name="cus[id_customer]" id="id_customer" value="">
			 <a href="#" id="add_newcutomer_repair" class="btn btn-success">Add</a>
			<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
		  </div>
		</div>
	</div>
</div>

<script type="text/javascript">
     var Categories  = new Array();
     var CategorysArr = new Array();
     CategorysArr = JSON.parse('<?php echo json_encode($categories); ?>');
</script>
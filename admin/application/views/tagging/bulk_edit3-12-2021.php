      <!-- Content Wrapper. Contains page content -->
    <style>
    	.remove-btn{
			margin-top: -168px;
		    margin-left: -38px;
		    background-color: #e51712 !important;
		    border: none;
		    color: white !important;
		}
    </style>
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
        	Tagging
            <small>Tag</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Tagging</a></li>
            <li class="active">Tag</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content product">

          <!-- Default box -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Edit Tagging</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
             <!-- form container -->
              <div class="row">
				<div class="col-sm-12"> 
					<!-- Lot Details Start Here -->
					<div class="row">				    	
						 	    
						 		<div class="col-md-2">
						 			<div class="form-group">
						 				<label><a data-toggle="tooltip" title="Branch">Select Branch</a><span class="error">*</span></label>
							 			<select id="branch_select" class="form-control" required></select>
										<input id="id_branch" name="id_branch" type="hidden" />
									</div>
						 		</div>
						 		 <div class="col-md-2">
							 		<label><a data-toggle="tooltip" title="MC Type">MC TYpe</a><span class="error">*</span></label>
							 		<div class="form-group">
							 			<select id="mc_type" class="form-control">
							 				<option value="1">Per Gram</option>
							 				<option value="2">Per Piece</option>
							 			</select>
							 			<input type="hidden" id="id_mc_type" name="">
							 		</div>
							 	</div>
							 	<div class="col-md-2">
							 			<div class="form-group">
							 				<label><a  data-toggle="tooltip" title="Enter Product">Select Product</a>  </label>
								 			<select id="prod_select" class="form-control" style="width:100%;"></select>
								 			<input type="hidden" id="id_product" name="">
										</div>
							 	</div>
							 	<div class="col-md-2">
							 			<div class="form-group">
							 				<label><a  data-toggle="tooltip" title="Enter Design">Select Design</a>  </label>
								 			<select class="form-control" id="des_select" style="width:100%;"></select>
										</div>
							 	</div>
							 	<div class="col-md-2">
							 			<div class="form-group">
							 				<label><a  data-toggle="tooltip" title="Enter Design">Select Sub Design</a>  </label>
								 			<select class="form-control" id="sub_des_select" style="width:100%;"></select>
										</div>
							 	</div>
							 		<div class="col-md-2" id="edit_mc_value" >
							 		<label><a data-toggle="tooltip" title="Making Charge">Making Charge</a></label>
							 		<input type="number" class="form-control" id="old_mc_value" placeholder="Making Charge Value" >
							 	</div>
							 
					</div>
					<div class="row">
						 		<div class="col-md-2" id="edit_was_per" >
							 		<label><a data-toggle="tooltip" title="Wastage Percentage">Wastage Percentage</a></label>
							 		<input type="number" class="form-control" id="old_mc_per" placeholder="Wastage Percentage" >
							 	</div>
						 		
							 	<div class="col-md-2">
							 		<div class="form-group">
							 			<label><a  data-toggle="tooltip" title="From Weight">From weight</a>  </label>
							 			<input class="form-control" type="number" name="from_weight" id="from_weight" placeholder="Enter From Weight">
							 		</div>
							 	</div>
							 	<div class="col-md-2">
							 		<label><a  data-toggle="tooltip" title="From Weight">To weight</a>  </label>
							 		<div class="form-group">
							 			<input class="form-control" type="number" name="to_weight" id="to_weight" placeholder="Enter To Weight">
							 		</div>
							 	</div>
							 	<div class="col-md-2">
							 		<label></label>
							 		<div class="form-group">
							 			<button class="btn btn-primary" id="get_tag_details" disabled>Apply Filter</button>
							 		</div>
							 	</div>
				 	</div> 			 
				 	<p class="help-block"></p>			 
				</div>	<!--/ Col --> 
			</div>	 <!--/ row -->
			<div class="table-responsive">
	                 <table id="tagging_list" class="table table-bordered table-striped text-center">
	                    <thead>
	                      <tr>
	                        <th width="5%"><label class="checkbox-inline"><input type="checkbox" id="select_all" name="select_all" value="all"/>All</label></th>                                        
	                        <th width="5%">Tag Code</th>
	                        <th width="5%">Product</th>
							<th width="5%">Design</th>
							<th width="5%">Sub Design</th>
	                        <th width="5%">Tag Date</th>
	                        <th width="10%">GWT(g)</th>
							<th width="10%">LWT(g)</th>
	                        <th width="15%">NWT(g)</th>
							<th width="15%">Wastage(%)</th>
							<th width="15%">MC Type</th>
							<th width="15%">MC value</th>
							<th width="15%">Amount</th>
	                      </tr>
	                    </thead> 
	                 </table>
                  </div>
			   <p class="help-block"> </p>  
		
			<div class="row" id="editable_block" style="display: block;">
				<div class="col-sm-12"> 
					<div class="row">				    	
						 		<div class="col-md-2">
						 			<div class="form-group">
						 				<label>Update MC Type</label>
						 				 <select class="form-control" id="update_mc_type">
						 				     <option value="">Change MC Type</option>
						 				     <option value="1">Mc Per Gram</option>
						 				     <option value="2">Mc Per Piece</option>
						 				 </select>
									</div>
						 		</div>
						 		<div class="col-md-2">
						 			<div class="form-group">
						 				<label>Update Wastage(%)</label>
						 				<input type="hidden" id="metal_rate" name="">
							 			<input class="form-control" id="wastage_percent" name="tagging[retail_max_wastage_percent]" type="number"  step=any  placeholder="Enter wastage percentage." />
									</div>
						 		</div>
						 		<div class="col-md-2">
						 			<div class="form-group">
						 				<label>Update MC Value</label>
						 				<input class="form-control" id="mc_value" name="tagging[tag_mc_value]" type="number"  step=any  placeholder="Making Charge"/>
									</div>
						 		</div>
						 		<div class="col-md-2">
						 			<div class="form-group">
						 				<label>Update Design</label>
						 				<input class="form-control" id="tag_design_no" name="tagging[designno]" type="text"  placeholder="Enter Design Code" autocomplete="off"/>
							 			<input class="form-control" id="design_id" name="tagging[design_id]" type="hidden"/>
									</div>
						 		</div>
						 		<div class="col-md-2">
						 			<div class="form-group">
						 				<button class="btn btn-info" id="otp_submit" style="margin-top: 11%;" >Submit</button>
									</div>
						 		</div>
						 		

				 	</div> 			 
				 	<p class="help-block"></p>			 
				</div>	<!--/ Col --> 
			</div>	 <!--/ row -->
			   <p class="help-block"> </p>  
			     
	            </div>  
	            <div class="overlay" style="display:none">
				  <i class="fa fa-refresh fa-spin"></i>
				</div>

	             <!-- /form -->
	          </div>
    </section>
</div>
<div id="otp_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">

      <div class="modal-content">

        <div class="modal-header ">
        
        <button type="button" id="close_modal" class="close" >&times;</button>

      <h3 id="myModalLabel">Mobile Number Verification</h3>

      </div>

        <div class="modal-body">

          <p>Please enter the code sent to your mobile number</p>

          <div>

          <label style="display:inline; margin:5px" for="otp">Enter Code:</label>

          <input  style="display:inline; width:30%; margin:5px" type="number" id="tag_otp" name="tag_otp" value="" class="form-control" required/>
		  
		   <input style="margin-left:1%" type="submit" value="Verify" id="bulk_edit" style="background-color:#0079C0"  class="button btn btn-primary btn-large" />

       

          <span id="OTPloader"><img src="<?php echo base_url()?>assets/img/loader.gif" ></span>

        </div>

        <div class="modal-footer">
	
		   <input type="submit" id="resendotp" value="Resend OTP" class="resendotp">  </input>
         

        </div>

        </div>

      </div>
    
    </div>
  
  </div>  


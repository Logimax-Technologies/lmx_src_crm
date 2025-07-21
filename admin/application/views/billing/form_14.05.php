      <!-- Content Wrapper. Contains page content -->
    <style>
    	.remove-btn{
			margin-top: -168px;
		    margin-left: -38px;
		    background-color: #e51712 !important;
		    border: none;
		    color: white !important;
		}
		.summary_lbl{
			font-weight:bold;
		}
		.stickyBlk {
		    margin: 0 auto;
		    top: 0;
		    width: 100%;
		    z-index: 999;
		    background: #fff;
		}
    </style>
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
        	Billing
            <small>Customer Billing</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Billings</a></li>
            <li class="active">Billing</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content product">

          <!-- Default box -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Add Billing</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
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
              <div class="row">
	             <!-- form -->
				<?php echo form_open_multipart(( $billing['bill_id'] != NULL && $billing['bill_id'] > 0 ? 'admin_ret_billing/billing/update/'.$billing['bill_id']:'admin_ret_billing/billing/save')); ?>
				<?php 
					$metal_rates = $this->admin_settings_model->metal_ratesDB("last");
					
				?>
				<div class="col-sm-12"> 
					<!-- Lot Details Start Here -->
					<div class="row">				    	
			    		<div class="col-sm-3">
			    			<div class="row">				    	
					    		<div class="col-sm-4">
					    			<label>Customer </label>
						 		</div>
						 		<div class="col-sm-8">
						 			<div class="form-group">
							 			<div class="input-group">
											<input class="form-control" id="bill_cus_name" name="billing[cus_name]" type="text"  placeholder="Customer Name / Mobile"  value="<?php echo set_value('billing[cus_name]',isset($billing['cus_name'])?$billing['cus_name']:NULL); ?>" required autocomplete="off"/>
											
											<input class="form-control" id="bill_cus_id" name="billing[bill_cus_id]" type="hidden" value="<?php echo set_value('billing[bill_cus_id]',$billing['bill_cus_id']); ?>"/>
											
											</label>
											<label style="display:none;" class="per-grm-sale-value"> </label>
											<label style="display:none;" class="silver_per-grm-sale-value"> </label>
											
											<span id="customerAlert"></span>
										</div>
										
										
										<input id="bill_id" name="billing[bill_id]" type="hidden" value="<?php echo set_value('billing[bill_id]',$billing['bill_id']); ?>" />
									</div>
						 		</div>
						 	</div>
				 		</div>
				 		<div class="col-sm-3">	
				 			<div class="row">				    	
					    		<div class="col-sm-4">
					    			<label>Bill Date </label>
						 		</div>
						 		<div class="col-sm-8">
						 			<div class="form-group" > 
							 			<div class="input-group" > 
											<input class="form-control" id="billing_datetime" data-date-format="dd-mm-yyyy hh:mm:ss" name="billing[bill_date]" type="text" required="true" placeholder="Billing Date" value="<?php echo set_value('billing[bill_date]',$billing['bill_date']); ?>" readonly />
										</div>
									</div>
						 		</div>
						 	</div> 		
				 		</div>
						<div class="col-sm-3">	
				 			<div class="row">				    	
					    		<div class="col-sm-4">
					    			<label>Bill Branch </label>
						 		</div>
						 		<div class="col-sm-8">
						 			<div class="form-group" > 
									<?php if($this->session->userdata('branchWiseLogin') == 1 && $this->session->userdata('id_branch') == "") { ?>
										<select name="billing[id_branch]" id="id_branch" class="form-control" required>
											<?php echo $this->ret_billing_model->get_currentBranches($billing['id_branch']); ?>
										</select>
									<?php }else { ?>
							 			<label><?php echo $this->ret_billing_model->get_currentBranchName($type == 'add' ? $this->session->userdata('id_branch') : $billing['id_branch']); ?> </label>
										<input type="hidden" id="id_branch" name="billing[id_branch]" value="<?php echo $type == 'add' ? $this->session->userdata('id_branch') : $billing['id_branch'];?>" />
									<?php } ?>
									</div>
						 		</div>
						 	</div> 		
				 		</div>
						<div class="col-sm-3">	
							<a class="btn btn-success pull-right" id="add_new_customer" href="#" data-toggle="modal" data-target="#confirm-add" ><i class="fa fa-user-plus"></i>New Customer</a> 
						</div>
						
				 	</div> 
				 	<p></p>
				 	<p></p>
					<div class="row"> <!--stickyBlk-->
						<div class="col-sm-5 bg-teal">
							<div class="form-group"> 
								<div class="col-md-10">
									<label for="Offer">Bill Type :</label>
									<div class="row">
										<div class="col-md-6">
											<input type="checkbox" id="select_sales_details" name="sales" value="1" > <label for="select_sales_details">Sales </label>
										</div>
										<div class="col-md-6">
											<input type="checkbox" id="select_purchase_details" name="purchase" value="1"> <label for="select_purchase_details">Purchase </label>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<input type="checkbox" id="select_order_advanceamt_details" name="orderadvanceamt" value="1" <?php echo !empty($est_other_item['stone_details']) ? 'checked' : '' ;?>> <label for="select_order_advanceamt_details">Order  Advance Amount </label>
										</div>
										<div class="col-md-6">
											<input type="checkbox" id="select_advance_oldmatel_details" name="oldmateladvance" value="1" > <label for="select_advance_oldmatel_details">Order Advance Metal </label>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<input type="checkbox" id="select_advance_bill_details" name="advancebill" value="1" > <label for="select_advance_bill_details">Advance Bill </label>
										</div>
										<div class="col-md-6">
											<input type="checkbox" id="select_salesreturn_details" name="salesreturn" value="1"> <label for="select_salesreturn_details">Sales Return </label>
										</div>
									</div>   
							   </div>
							</div>
						</div>
						<div class="col-sm-7">				    	
				    		<div class="col-sm-6">
				    			<p></p>
								<div class="row">				    	
						    		<div class="col-sm-3">
						    			<label>Est No. </label>
							 		</div>
							 		<div class="col-sm-7">
							 			<div class="form-group" > 
								 			<div class="input-group" > 
												<input class="form-control" id="filter_est_no" name="filter_est_no" type="text" placeholder="Enter Estimation No." value="" />
											</div>
										</div>
							 		</div>
									<div class="col-sm-2">
										<span id="search_est_no" class="badge bg-green"><i class="fa fa-search"></i></span>
									</div>
							 	</div>
							 	<div class="row">				    	
						    		<div class="col-sm-3">
						    			<label>Tag No. </label>
							 		</div>
							 		<div class="col-sm-7">
							 			<div class="form-group" > 
								 			<div class="input-group" > 
												<input class="form-control" id="filter_tag_no" name="filter_tag_no" type="text" placeholder="Enter Tag No." value="" />
											</div>
										</div>
							 		</div>
									<div class="col-sm-2">
										<span id="search_tag_no" class="badge bg-green"><i class="fa fa-search"></i></span>
									</div>
							 	</div>
							</div> 
							<div class="col-sm-6">
								<p></p>
								<div class="row">				    	
						    		<div class="col-sm-4">
						    			<label>Order No. </label>
							 		</div>
							 		<div class="col-sm-6">
							 			<div class="form-group" > 
								 			<div class="input-group" > 
												<input class="form-control" id="filter_order_no" name="filter_order_no" type="text" placeholder="Enter Order No." value="" />
											</div>
										</div>
							 		</div>
									<div class="col-sm-2">
										<span id="search_order_no" class="badge bg-green"><i class="fa fa-search"></i></span>
									</div>
							 	</div>
							 	<div class="row">				    	
						    		<div class="col-sm-4">
						    			<label>Bill No. </label>
							 		</div>
							 		<div class="col-sm-6">
							 			<div class="form-group" > 
								 			<div class="input-group" > 
												<input class="form-control" id="filter_bill_no" name="filter_bill_no" type="text" placeholder="Enter Bill No." value="" disabled="true"/>
											</div>
										</div>
							 		</div>
									<div class="col-sm-2">
										<span id="search_bill_no" class="badge bg-green"><i class="fa fa-search"></i></span>
									</div>
							 	</div>
							</div> 
						</div>
					</div>
					
					<div class="box box-primary sale_details">
						<div class="box-header with-border">
						  <h3 class="box-title">Sales Details</h3>
						  <div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
						  </div>
						</div>
						<div class="box-body">
							<!-- <div class="row">
							  <div class="box-tools pull-right">
								<button type="button" id="create_sale_details" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add</button>
							  </div>
							</div> -->
							<div class="row">
								<div class="box-body">
								   <div class="table-responsive">
									 <table id="billing_sale_details" class="table table-bordered table-striped text-center">
										<thead>
										  <tr>
											<th>HSN Code</th>
											<th>Product</th>
											<th>Design</th>  
											<th>Pcs</th>   											
											<th>G.Wt</th>   
											<th>L.Wt</th>   
											<th>N.Wt</th>   
											<th>Wast(%)</th>   
											<th>MC</th>   
											<th>Discount</th>   
											<th>Taxable Amt</th>
											<th>Tax(%)</th>
											<th>Tax</th>
											<th>Amount</th>
											<th>Partly</th>
											<th>Tag No</th>
											<th>Order No</th>
											<th>Est No</th>
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
					<div class="box box-primary purchase_details" >
						<div class="box-header with-border">
						  <h3 class="box-title">Purchase Details</h3>
						  <div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
						  </div>
						</div>
						<div class="box-body">
							<div class="row">
							  <div class="box-tools pull-right">
								<button type="button" id="create_catalog_details" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add</button>
							  </div>
							</div>
							<div class="row">
								<div class="box-body">
								   <div class="table-responsive">
									 <table id="purchase_item_details" class="table table-bordered table-striped text-center">
										<thead>
										  <tr>
											<th>Product</th>
											<th>Design</th>
											<!--<th>Purity</th> -->  
											<th>Pcs</th>   
											<th>G.Wt</th>   
											<th>L.Wt</th>   
											<th>N.Wt</th>   
											<th>Wastage(%)</th>
											<th>Discount</th>
											<th>Amount</th>
											<th>Est No</th>
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
					<div class="box box-primary custom_details" >
						<div class="box-header with-border">
						  <h3 class="box-title">Custom Details</h3>
						  <div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
						  </div>
						</div>
						<div class="box-body">
							<div class="row">
							  <div class="box-tools pull-right">
								<button type="button" id="create_custom_details" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add</button>
							  </div>
							</div>
							<div class="row">
								<div class="box-body">
								   <div class="table-responsive">
									 <table id="estimation_custom_details" class="table table-bordered table-striped text-center">
										<thead>
										  <tr>
											<th>Product</th>
											<th>Qty</th>    
											<th>Purity</th>   
											<th>Size</th>   
											<th>Pcs</th>   
											<th>G.Wt</th>   
											<th>L.Wt</th>   
											<th>N.Wt</th>   
											<th>Wastage(%)</th>   
											<th>MC/grm</th>   
											<th>Amount</th>
											<th>Action</th>
										  </tr>
										</thead> 
										<tbody>
											<?php if($this->uri->segment(3) == 'edit'){
												foreach($est_other_item['item_details'] as $ikey => $ival){
													if($ival['item_type'] == 1){
														echo '<tr><td><input type="text" name="est_custom[product][]" value="'.$ival['product_name'].'" class="cus_product" required /><input class="cus_product_id" type="hidden" name="est_custom[pro_id][]" value="'.$ival['product_id'].'" /></td><td><input class="cus_qty" type="number" name="est_custom[qty][]" value="'.$ival['quantity'].'" /></td><td><div>'.$ival['purname'].'</div><input class="cus_purity" name="est_custom[purity][]" value="'.$ival['purid'].'" /></td><td><input type="number" class="cus_size" name="est_custom[size][]" value="'.$ival['size'].'" /></td><td><input class="cus_pcs" type="number" name="est_custom[pcs][]" value="'.$ival['piece'].'" /></td><td><input type="number" class="cus_gwt" name="est_custom[gwt][]" value="'.$ival['gross_wt'].'" /></td><td><input class="cus_lwt" type="number" name="est_custom[lwt][]" value="'.$ival['less_wt'].'" /></td><td><input type="number" class="cus_nwt" name="est_custom[nwt][]" value="'.$ival['net_wt'].'" readonly /></td><td><input class="cus_wastage" type="number" name="est_custom[wastage][]" value="'.$ival['wastage_percent'].'" /></td><td><input type="number" class="cus_mc" name="est_custom[mc][]" value="'.$ival['mc_per_grm'].'" /></td><td><input class="cus_amount" type="number" name="est_custom[amount][]" value="'.$ival['item_cost'].'" readonly /><input type="hidden" class="cus_calculation_based_on" name="est_custom[calculation_based_on][]" value="'.$ival['calculation_based_on'].'" /></td></tr>';
													}
												}
											}?>
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
					<div class="box box-primary old_matel_details" <?php echo !empty($est_other_item['old_matel_details']) ? '' : 'style="display:none;"' ;?>>
						<div class="box-header with-border">
						  <h3 class="box-title">Old Matel Details</h3>
						  <div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
						  </div>
						</div>
						<div class="box-body">
							<div class="row">
							  <div class="box-tools pull-right">
								<button type="button" id="create_old_matel_details" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add</button>
							  </div>
							</div>
							<div class="row">
								<div class="box-body">
								   <div class="table-responsive">
									 <table id="estimation_old_matel_details" class="table table-bordered table-striped text-center">
										<thead>
										  <tr>
											<th>Type</th>
											<th>Category</th>    
											<th>Purity</th>   
											<th>G.Wt</th>   
											<th>L.Wt</th>   
											<th>N.Wt</th>   
											<th>Wastage(%)</th>
											<th>Rate</th>   
											<th>Amount</th>
											<th>Send To</th>
											<th>Action</th>
										  </tr>
										</thead> 
										<tbody>
											<?php if($this->uri->segment(3) == 'edit'){
												foreach($est_other_item['old_matel_details'] as $ikey => $ival){
														echo '<tr><td><div>'.$ival['receiveditem'].'</div><input type="hidden" class="old_item_type" name="est_oldmatel[item_type][]" value="'.$ival['item_type'].'"  /></td><td><div>'.$ival['metal'].'</div><input type="hidden" class="old_id_category"  name="est_oldmatel[id_category][]"value="'.$ival['id_category'].'"  /></td><td><div>'.$ival['purname'].'</div><input type="hidden" class="old_purity"  name="est_oldmatel[purity][]" value="'.$ival['purid'].'" /></td><td><input type="number" class="old_gwt" name="est_oldmatel[gwt][]" value="'.$ival['gross_wt'].'" /></td><td><input class="old_lwt" type="number" name="est_oldmatel[lwt][]" value="'.$ival['dust_wt'].'" /></td><td><input type="number" class="old_nwt" name="est_oldmatel[nwt][]" value="'.$ival['ls_wt'].'" readonly /></td><td><input class="old_wastage" type="number" name="est_oldmatel[wastage][]" value="'.$ival['wastage_percent'].'" /></td><td><input type="number" class="old_rate" name="est_oldmatel[rate][]" value="'.$ival['rate_per_gram'].'" /></td><td><input class="old_amount" type="number" name="est_oldmatel[amount][]" value="'.$ival['amount'].'" /></td><td><input type="hidden" class="old_use_type" name="est_oldmatel[use_type][]" value="'.$ival['type'].'" /><div>'.$ival['reusetype'].'</div></td></tr>';
												}
											}?>
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
						<div class="col-md-6">
							<div class="box box-primary stone_details" <?php echo !empty($est_other_item['stone_details']) ? '' : 'style="display:none;"' ;?>>
								<div class="box-header with-border">
								  <h3 class="box-title">Stone Details</h3>
								  <div class="box-tools pull-right">
									<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
								  </div>
								</div>
								<div class="box-body">
									<div class="row">
									  <div class="box-tools pull-right">
										<button type="button" id="create_stone_details" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add</button>
									  </div>
									</div>
									<div class="row">
										<div class="box-body">
										   <div class="table-responsive">
											 <table id="estimation_stone_details" class="table table-bordered table-striped text-center">
												<thead>
												  <tr>
													<th>Stone</th>
													<th>Pcs</th>   
													<th>Wt</th>
													<th>Price</th>
													<th>Action</th>
												  </tr>
												</thead> 
												<tbody>
													<?php if($this->uri->segment(3) == 'edit'){
														foreach($est_other_item['stone_details'] as $ikey => $ival){
																echo '<tr><td><div>'.$ival['stone_name'].'</div><input type="hidden" class="stone_id" name="est_stones[stone_id][]" value="'.$ival['stone_id'].'" /></td><td><input type="number" class="stone_pcs" name="est_stones[stone_pcs][]" value="'.$ival['pieces'].'" /></td><td><input class="stone_wt" type="number" name="est_stones[stone_wt][]" value="'.$ival['wt'].'" /></td><td><input type="number" class="stone_price" name="est_stones[stone_price][]" value="'.$ival['price'].'" /></td></tr>';
														}
													}?>
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
						</div>
						<div class="col-md-6">
							<div class="box box-primary material_details" <?php echo !empty($est_other_item['other_material_details']) ? '' : 'style="display:none;"' ;?>>
								<div class="box-header with-border">
								  <h3 class="box-title">Materials Details</h3>
								  <div class="box-tools pull-right">
									<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
								  </div>
								</div>
								<div class="box-body">
									<div class="row">
									  <div class="box-tools pull-right">
										<button type="button" id="create_material_details" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add</button>
									  </div>
									</div>
									<div class="row">
										<div class="box-body">
										   <div class="table-responsive">
											 <table id="estimation_material_details" class="table table-bordered table-striped text-center">
												<thead>
												  <tr>
													<th>Material</th>
													<th>Wt</th>
													<th>Price</th>
													<th>Action</th>
												  </tr>
												</thead> 
												<tbody>
													<?php if($this->uri->segment(3) == 'edit'){
												foreach($est_other_item['other_material_details'] as $ikey => $ival){
														echo '<tr><td><div>'.$ival['material_code'].'</div><input type="hidden" class="material_id" name="est_materials[material_id][]" value="'.$ival['material_id'].'" /></td><td><input class="material_wt" type="number" name="est_materials[material_wt][]" value="'.$ival['wt'].'" /></td><td><input type="number" class="material_price" name="est_materials[material_price][]" value="'.$ival['price'].'"  /></td></tr>';
												}
											}?>
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
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="box box-primary gift_voucher_details" <?php echo !empty($est_other_item['voucher_details']) ? '' : 'style="display:none;"' ;?>>
								<div class="box-header with-border">
								  <h3 class="box-title">Gift Voucher Details</h3>
								  <div class="box-tools pull-right">
									<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
								  </div>
								</div>
								<div class="box-body">
									<div class="row">
									  <div class="box-tools pull-right">
										<button type="button" id="create_gift_voucher_details" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add</button>
									  </div>
									</div>
									<div class="row">
										<div class="box-body">
										   <div class="table-responsive">
											 <table id="estimation_gift_voucher_details" class="table table-bordered table-striped text-center">
												<thead>
												  <tr>
													<th>Voucher No</th>
													<th>Voucher Detail</th>   
													<th>Amount</th>
													<th>Action</th>
												  </tr>
												</thead> 
												<tbody>
													<?php if($this->uri->segment(3) == 'edit'){
														foreach($est_other_item['voucher_details'] as $ikey => $ival){
																echo '<tr><td><input class="voucher_no" type="number" name="gift_voucher[voucher_no][]" value="'.$ival['voucher_no'].'" /></td><td></td><td><input type="number" class="gift_voucher_amt" name="gift_voucher[gift_voucher_amt][]" value=""'.$ival['gift_voucher_amt'].'  /></td></tr>';
														}
													}?>
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
						</div>
						<div class="col-md-6">
							<div class="box box-primary chit_details" <?php echo !empty($est_other_item['chit_details']) ? '' : 'style="display:none;"' ;?>>
								<div class="box-header with-border">
								  <h3 class="box-title">Chit Details</h3>
								  <div class="box-tools pull-right">
									<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
								  </div>
								</div>
								<div class="box-body">
									<div class="row">
									  <div class="box-tools pull-right">
										<button type="button" id="create_chit_details" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add</button>
									  </div>
									</div>
									<div class="row">
										<div class="box-body">
										   <div class="table-responsive">
											 <table id="estimation_chit_details" class="table table-bordered table-striped text-center">
												<thead>
												  <tr>
													<th>Scheme Acc no</th>
													<th>Amount</th>
												  </tr>
												</thead> 
												<tbody>
													<?php if($this->uri->segment(3) == 'edit'){
														foreach($est_other_item['chit_details'] as $ikey => $ival){
																echo '<tr><td><input class="scheme_account_id" type="number" name="chit_uti[scheme_account_id][]" value="'.$ival['scheme_account_id'].'" /></td><td><input type="number" class="chit_amt" name="chit_uti[chit_amt][]" value="'.$ival['utl_amount'].'"  /></td></tr>';
														}
													}?>
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
						</div>
					</div>
					<div class="box box-primary total_summary_details">
						<div class="box-header with-border">
						  <h3 class="box-title">Total Summary</h3>
						  <div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
						  </div>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="box-body">
								   <div class="table-responsive">
									 <table id="total_summary_details" class="table table-bordered table-striped text-center">
										<thead>
										  
										</thead> 
										<tbody>
											<tr>
												<td>Total Purchase Weight(Grms)</td>
												<td><span class="summary_lbl summary_pur_weight"></span></td>
												<td>Total Purchase Amount</td>
												<td><span class="summary_lbl summary_pur_amt"></span></td>
											</tr>
											<tr>
												<td>Total Sale Weight(Grms)</td>
												<td><span class="summary_lbl summary_sale_weight"></span></td>
												<td>Total Sale Amount</td>
												<td><span class="summary_lbl summary_sale_amt"></span></td>
											</tr>
											<tr>
												<td></td>
												<td></td>
												<td>Discount</td>
												<td><input type="number" class="summary_discount_amt summary_lbl" name="billing[discount]" readonly value="" step="any"></td>
											</tr>
											<tr>
												<td></td>
												<td></td>
												<td>Final Price</td>
												<td><input type="number" class="total_cost summary_lbl" name="billing[total_cost]" value="" required readonly></td>
											</tr>
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
					
				</div>	<!--/ Col --> 
			</div>	 <!--/ row -->
				 			  	 
			   <p class="help-block"> </p>  
			     
			     <div class="row">
				   <div class="box box-default"><br/>
					  <div class="col-xs-offset-5">
						<button type="submit"  class="btn btn-primary">Save</button> 
						<button type="button" class="btn btn-default btn-cancel">Cancel</button>
						
					  </div> <br/>
					</div>
				  </div> 
			      
	            </div>  
	          <?php echo form_close();?>
	            <div class="overlay" style="display:none">
				  <i class="fa fa-refresh fa-spin"></i>
				</div>

	             <!-- /form -->
	          </div>
             </section>
     
            </div>
  <!-- modal -->      
<div class="modal fade" id="confirm-add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:75%;">
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
							<p class="help-block cus_first_name"></p>
					   </div>
					</div>
				</div> 
				<div class="row">   
					<div class="form-group">
					   <label for="cus_mobile" class="col-md-3 col-md-offset-1 ">Mobile<span class="error">*</span></label>
					   <div class="col-md-6">
							<input type="text" class="form-control" id="cus_mobile" name="cus[mobile]" placeholder="Enter customer mobile"> 
							<p class="help-block cus_mobile"></p>
					   </div>
					</div>
				</div>
			</div>
		  <div class="modal-footer">
			<a href="#" id="add_newcutomer" class="btn btn-success">Add</a>
			<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
		  </div>
		</div>
	</div>
</div>
<!-- / modal -->     
<!-- modal for fetch estimation details -->      
<div class="modal fade" id="estimation-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:75%;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Select the estimation details to billing</h4>
			</div>
			<div class="modal-body">
				<div class="row" id="est_items_to_sale_convertion_tbl" style="display:none;">
					<div class="box-body">
						<div class="table-responsive">
							<table id="est_items_to_sale_convertion" class="table table-bordered table-striped text-center">
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
										<th>Wast(%)</th>   
										<th>MC</th>   
										<th>Discount</th>   
										<th>Tax Group</th>   
										<th>Tax</th>   
										<th>Amount</th>
										<th>Partly</th>
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
				<div class="row" id="est_olditems_to_sale_convertion_tbl" style="display:none;">
					<div class="box-body">
						<div class="table-responsive">
							<table id="est_olditems_to_sale_convertion" class="table table-bordered table-striped text-center">
								<thead>
									<tr>
										<th>Select</th>
										<th>Purpose</th>
										<th>Category</th>
										<!--<th>Purity</th>  --> 
										<th>G.Wt</th>   
										<th>Dust.Wt</th>   
										<th>Stn.Wt</th>   
										<th>N.Wt</th>   
										<th>Wastage(%)</th>   
										<th>Rate Per grm</th>  
										<th>Discount</th> 
										<th>Amount</th>
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
		  <div class="modal-footer">
			<a href="#" id="update_estimation_to_bill" class="btn btn-success">Add</a>
			<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
		  </div>
		</div>
	</div>
</div>
<!-- / modal -->        
